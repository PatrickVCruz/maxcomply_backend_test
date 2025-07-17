<?php

namespace App\Tests\Controller;

use App\Repository\ManufacturersRepository;
use App\Repository\VehiclesRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class VehicleApiControllerTest extends WebTestCase
{

    private KernelBrowser $client;
    private ContainerInterface $container;
    private MockObject $mockManufacturersRepository;
    private MockObject $mockVehiclesRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->mockManufacturersRepository = $this->createMock(ManufacturersRepository::class);
        $this->mockVehiclesRepository = $this->createMock(VehiclesRepository::class);
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
}
