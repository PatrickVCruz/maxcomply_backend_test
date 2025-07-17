<?php

namespace App\Tests\Controller;

use App\Controller\VehicleApiController;
use App\Repository\ManufacturersRepository;
use App\Repository\VehiclesRepository;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleApiControllerTest extends WebTestCase
{

    private KernelBrowser $client;
    private ContainerInterface $container;
    private MockObject $mockManufacturersRepository;
    private MockObject $mockVehiclesRepository;
    private $mockController;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->mockManufacturersRepository = $this->createMock(ManufacturersRepository::class);
        $this->mockVehiclesRepository = $this->createMock(VehiclesRepository::class);
        $this->mockController = $this->container->get(VehicleApiController::class);
    }

    public function testGetManufacturersByVehicleTypeReturnsManufacturers(): void
    {
        $manufacturerData = [
            ['id' => 1, 'name' => 'Toyota'],
            ['id' => 2, 'name' => 'Honda']
        ];
        $vehicleType = 'car';

        $this->mockManufacturersRepository
            ->expects($this->once())
            ->method('getAllManufacturersByVehicleType')
            ->with($vehicleType)
            ->willReturn($manufacturerData);

        $this->container->set(ManufacturersRepository::class, $this->mockManufacturersRepository);
        $this->client->request('GET', '/manufacturersByType/car');
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode($manufacturerData),
            $response->getContent()
        );
    }

    public function testGetManufacturersByVehicleTypeHandlesInvalidVehicleType(): void
    {
        $vehicleType = 'invalid-type';

        $this->mockManufacturersRepository
            ->expects($this->once())
            ->method('getAllManufacturersByVehicleType')
            ->with($vehicleType)
            ->willReturn([]);

        $this->container->set(ManufacturersRepository::class, $this->mockManufacturersRepository);
        $this->client->request('GET', '/manufacturersByType/' . $vehicleType);
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([]),
            $response->getContent()
        );
    }

    public function testGetVehicleSpecsReturnsSpecs(): void
    {
        $vehicle = 'AE86';
        $vehicleSpecsData = [
            ['id' => 1, 'name' => 'AE86', 'specs' => ['horsepower' => '100', 'engine_type' => 'Petrol']],
        ];

        $this->mockVehiclesRepository
            ->expects($this->once())
            ->method('getVehicleSpecs')
            ->with($vehicle)
            ->willReturn($vehicleSpecsData);

        $this->container->set(VehiclesRepository::class, $this->mockVehiclesRepository);
        $this->client->request('GET', '/vehicleSpecs/AE86');
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode($vehicleSpecsData),
            $response->getContent()
        );
    }

    public function testGetVehicleSpecsReturnsEmptyArrayForNoData(): void
    {
        $vehicle = 'unknown-car';
        $vehicleSpecsData = [];

        $this->mockVehiclesRepository
            ->expects($this->once())
            ->method('getVehicleSpecs')
            ->with($vehicle)
            ->willReturn($vehicleSpecsData);

        $this->container->set(VehiclesRepository::class, $this->mockVehiclesRepository);
        $this->client->request('GET', '/vehicleSpecs/unknown-car');
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode($vehicleSpecsData),
            $response->getContent()
        );
    }

    public function testGetVehicleSpecsHandlesInvalidVehicle(): void
    {
        $vehicle = 'invalid-vehicle';

        $this->mockVehiclesRepository
            ->expects($this->once())
            ->method('getVehicleSpecs')
            ->with($vehicle)
            ->willReturn([]);

        $this->container->set(VehiclesRepository::class, $this->mockVehiclesRepository);
        $this->client->request('GET', '/vehicleSpecs/invalid-vehicle');
        $response = $this->client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode([]),
            $response->getContent()
        );
    }

    public function testUpdateVehicleSpecsValid(): void
    {
        $this->mockVehiclesRepository->expects($this->once())
            ->method('updateVehicleSpecs')
            ->willReturn(['success' => true]);

        $data = ['horsepower' => '100'];
        $request = new Request([], [], [], [], [], [], json_encode($data));
        $response = $this->mockController->updateVehicleSpecs($this->mockVehiclesRepository, 'AE86', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['success' => true]),
            $response->getContent()
        );
    }

    public function testUpdateVehicleSpecsInvalid(): void
    {
        $this->mockVehiclesRepository->expects($this->once())
            ->method('updateVehicleSpecs')
            ->willThrowException(new InvalidArgumentException('Field "unknown_field" is not allowed for update.'));

        $data = ['horsepowers' => 'asd'];
        $request = new Request([], [], [], [], [], [], json_encode($data));
        $response = $this->mockController->updateVehicleSpecs($this->mockVehiclesRepository, 'vehicle-1', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Field "unknown_field" is not allowed for update.']),
            $response->getContent()
        );
    }

    private function invokeCheckRequestBodyMethod(VehicleApiController $controller, array $data): ?JsonResponse
    {
        $reflection = new \ReflectionClass(VehicleApiController::class);
        $method = $reflection->getMethod('checkRequestBody');
        $method->setAccessible(true);
        return $method->invoke($controller, $data);
    }

    public function testCheckRequestBodyReturnsErrorForEmptyData(): void
    {

        $response = $this->invokeCheckRequestBodyMethod($this->mockController, []);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Invalid or missing request body.']),
            $response->getContent()
        );
    }

    public function testCheckRequestBodyHandlesValidData(): void
    {
        $response = $this->invokeCheckRequestBodyMethod(new VehicleApiController(), ['key' => 'value']);
        $this->assertNull($response);
    }


}
