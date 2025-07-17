<?php

namespace App\Tests\Controller;

use App\Repository\ManufacturersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VehicleApiControllerTest extends WebTestCase
{
    public function testGetManufacturersByVehicleTypeReturnsManufacturers(): void
    {
        $manufacturerData = [
            ['id' => 1, 'name' => 'Toyota'],
            ['id' => 2, 'name' => 'Honda']
        ];
        $vehicleType = 'car';

        $mockManufacturersRepository = $this->createMock(ManufacturersRepository::class);
        $mockManufacturersRepository
            ->expects($this->once())
            ->method('getAllManufacturersByVehicleType')
            ->with($vehicleType)
            ->willReturn($manufacturerData);

        $client = static::createClient();
        $container = $client->getContainer();
        $container->set(ManufacturersRepository::class, $mockManufacturersRepository);

        $client->request('GET', '/manufacturersByType/car');

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode($manufacturerData),
            $response->getContent()
        );
    }
}
