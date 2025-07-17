<?php

namespace App\Controller;

use App\Repository\ManufacturersRepository;
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
    #[Route('/manufacturersByType/{vehicleType}')]
    public function getManufacturersByVehicleType(ManufacturersRepository $manufacturersRepository, string $vehicleType): Response
    {
        $results = $manufacturersRepository->getAllManufacturersByVehicleType($vehicleType);

        return $this->json($results);
    }
}
