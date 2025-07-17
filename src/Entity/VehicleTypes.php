<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'vehicle_types')]
class VehicleTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $typeName;

    #[ORM\OneToMany(targetEntity: Vehicles::class, mappedBy: 'vehicleType', cascade: ['remove'])]
    private Collection $vehicles;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): self
    {
        $this->typeName = $typeName;
        return $this;
    }

    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicles $vehicle): self
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setVehicleType($this);
        }
        return $this;
    }

    public function removeVehicle(Vehicles $vehicle): self
    {
        if ($this->vehicles->removeElement($vehicle)) {
            if ($vehicle->getVehicleType() === $this) {
                $vehicle->setVehicleType(null);
            }
        }
        return $this;
    }
}
