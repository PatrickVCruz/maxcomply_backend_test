<?php

namespace App\Repository;

use App\Entity\Vehicles;
use App\Entity\VehicleSpecs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @extends ServiceEntityRepository<Vehicles>
 */
class VehiclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicles::class);
    }

    public function getVehicleSpecs(string $vehicle)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT v, vs
                     FROM App\Entity\Vehicles v
                     LEFT JOIN v.vehicleSpecs vs
                     WHERE v.name LIKE :vehicleName'
            )
            ->setParameter('vehicleName', '%' . $vehicle . '%')
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    private function getSingleVehicle(string $vehicleName): int
    {
        $vehicle = $this->getEntityManager()
            ->createQuery(
                'SELECT v, vs FROM App\Entity\Vehicles v
                    LEFT JOIN v.vehicleSpecs vs
                    WHERE v.name LIKE :vehicleName'
            )
            ->setParameter('vehicleName', '%' . $vehicleName . '%')
            ->getOneOrNullResult();

        if (!$vehicle) {
            throw new \Exception(sprintf('No vehicle found matching name "%s".', $vehicleName));
        }

        $specId = $vehicle->getVehicleSpecs()->getId() ?? null;
        if (!$specId) {
            throw new \Exception('No associated VehicleSpecs found for this vehicle.');
        }

        return $specId;
    }

    public function updateVehicleSpecs(string $vehicleName, array $dataToUpdate)
    {
        $specId  = $this->getSingleVehicle($vehicleName);
        $this->validateFieldsToUpdate($dataToUpdate);

        return $this->buildQuery($dataToUpdate, $specId)->execute();
    }

    private function validateFieldsToUpdate(array $dataToUpdate):void
    {
        foreach (array_keys($dataToUpdate) as $field) {
            if (!in_array($field, VehicleSpecs::FIELDS, true)) {
                throw new InvalidArgumentException(sprintf('Field "%s" is not allowed for update.', $field));
            }
        }
    }

    private function buildQuery(array $dataToUpdate, int $vehicleSpecID): Query
    {
        $updateQuery = [];
        foreach ($dataToUpdate as $field => $value) {
            $updateQuery[] = "vs.{$field} = :{$field}";
        }

        $dql = sprintf(
            "UPDATE App\Entity\VehicleSpecs vs
                    SET %s
                    WHERE vs.id = :id",
            implode(', ', $updateQuery)
        );

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $vehicleSpecID);
        foreach ($dataToUpdate as $field => $value) {
            $query->setParameter($field, $value);
        }

        return $query;
    }
}
