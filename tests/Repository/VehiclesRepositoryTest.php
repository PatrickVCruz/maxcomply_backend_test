<?php

namespace App\Tests\Repository;

use App\Repository\VehiclesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class VehiclesRepositoryTest extends TestCase
{
    public function testValidateFieldsToUpdateWithInvalidField(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $managerRegistry
            ->method('getManager')
            ->willReturn($entityManager);
        $repository = new VehiclesRepository($managerRegistry);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field "invalid_field" is not allowed for update.');

        $dataToUpdate = ['invalid_field' => 'value'];

        $method = (new \ReflectionClass(VehiclesRepository::class))->getMethod('validateFieldsToUpdate');
        $method->setAccessible(true);
        $method->invokeArgs($repository, [$dataToUpdate]);
    }
}
