<?php

namespace App\Controller;

use App\Repository\ManufacturersRepository;
use App\Repository\VehiclesRepository;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/vehicleSpecs/{vehicle}', name: 'update_vehicle_specs', requirements: ['vehicle' => '^[a-zA-Z0-9_.-]+$'], methods: ['PATCH'])]
    public function updateVehicleSpecs(VehiclesRepository $vehiclesRepository, string $vehicle, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $checkRequestBody = $this->checkRequestBody($data);
        if (!is_null($checkRequestBody)) {
            return $checkRequestBody;
        }



        return $this->json([]);
    }

    private function checkRequestBody(array $data): ?JsonResponse
    {
        if (!$data) {
            return $this->json(
                ['error' => 'Invalid or missing request body.'],
                Response::HTTP_BAD_REQUEST
            );
        }
        return null;
    }
}
