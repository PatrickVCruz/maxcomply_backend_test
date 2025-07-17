<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Manufacturers;
use App\Entity\VehicleTypes;

#[ORM\Entity]
#[ORM\Table(name: 'vehicles')]
class Vehicles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Manufacturers::class, inversedBy: 'vehicles')]
    #[ORM\JoinColumn(name: 'manufacturer_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Manufacturers $manufacturer;

    #[ORM\ManyToOne(targetEntity: VehicleTypes::class, inversedBy: 'vehicles')]
    #[ORM\JoinColumn(name: 'vehicle_type_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private VehicleTypes $vehicleType;

    #[ORM\OneToOne(targetEntity: VehicleSpecs::class, mappedBy: 'vehicle_id', cascade: ['remove'])]
    private VehicleSpecs $vehicleSpecs;


    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getManufacturer(): Manufacturers
    {
        return $this->manufacturer;
    }

    public function setManufacturer(Manufacturers $manufacturer): self
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    public function getVehicleType(): VehicleTypes
    {
        return $this->vehicleType;
    }

    public function setVehicleType(VehicleTypes $vehicleType): self
    {
        $this->vehicleType = $vehicleType;
        return $this;
    }

    public function getVehicleSpecs(): ?VehicleSpecs
    {
        return $this->vehicleSpecs;
    }
}
