<?php

namespace App\Controller;

use App\Repository\ManufacturersRepository;
use App\Repository\VehiclesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VehicleApiController extends AbstractController
{
    /**
     * @param ManufacturersRepository $manufacturersRepository
     * @param string $vehicleType
     * @return Response
     * Endpoint 1. Retrieves all manufacturers takes manufactures a specific type of vehicle
     */
    #[Route('/manufacturersByType/{vehicleType}', name: 'get_manufacturers_by_vehicle_type', requirements: ['vehicle' => '^[a-zA-Z0-9_.-]*$'], methods: ['GET'])]
    public function getManufacturersByVehicleType(ManufacturersRepository $manufacturersRepository, string $vehicleType): Response
    {
        $results = $manufacturersRepository->getAllManufacturersByVehicleType($vehicleType);

        return $this->json($results);
    }

    /**
     * @param VehiclesRepository $vehiclesRepository
     * @param string $vehicle
     * @return Response
     * Endpoint 2. Retrieves all info about specific vehicle
     */
    #[Route('/vehicleSpecs/{vehicle}', name: 'Get Vehicle Specs', requirements: ['vehicle' => '^[a-zA-Z0-9_.-]*$'], methods: ['GET'])]
    public function getVehicleSpecs(VehiclesRepository $vehiclesRepository, string $vehicle): Response
    {
        $results = $vehiclesRepository->getVehicleSpecs($vehicle);

        return $this->json($results);
    }
}
