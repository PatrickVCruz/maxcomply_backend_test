<?php

namespace App\Repository;

use App\Entity\Vehicles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

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
}
