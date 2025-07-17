<?php

namespace App\Repository;

use App\Entity\Manufacturers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Manufacturers>
 */
class ManufacturersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Manufacturers::class);
    }

    public function getAllManufacturersByVehicleType(string $vehicleType): array
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT DISTINCT m.name
                    FROM App\Entity\Manufacturers m
                    LEFT JOIN m.vehicles v
                    LEFT JOIN v.vehicleType vt
                    WHERE vt.typeName = :vehicleType'
            )
            ->setParameter('vehicleType', $vehicleType)
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
