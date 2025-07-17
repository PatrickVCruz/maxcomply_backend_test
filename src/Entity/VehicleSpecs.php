<?php

namespace App\Entity;

use App\Repository\VehicleSpecsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleSpecsRepository::class)]
#[ORM\Table(name: 'vehicle_specs')]
class VehicleSpecs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'vehicleSpecs', cascade: ['remove'])]
    #[ORM\JoinColumn(name: 'vehicle_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Vehicles $vehicle_id;

    #[ORM\Column(length: 20)]
    private ?string $engine_type = null;

    #[ORM\Column(nullable: true)]
    private ?int $horsepower = null;

    #[ORM\Column(nullable: true)]
    private ?int $top_speed = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $length = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getVehicleId(): ?Vehicles
    {
        return $this->vehicle_id;
    }

    public function setVehicleId(Vehicles $vehicle_id): static
    {
        $this->vehicle_id = $vehicle_id;

        return $this;
    }

    public function getEngineType(): ?string
    {
        return $this->engine_type;
    }

    public function setEngineType(string $engine_type): static
    {
        $this->engine_type = $engine_type;

        return $this;
    }

    public function getHorsepower(): ?int
    {
        return $this->horsepower;
    }

    public function setHorsepower(?int $horsepower): static
    {
        $this->horsepower = $horsepower;

        return $this;
    }

    public function getTopSpeed(): ?int
    {
        return $this->top_speed;
    }

    public function setTopSpeed(?int $top_speed): static
    {
        $this->top_speed = $top_speed;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): static
    {
        $this->width = $width;

        return $this;
    }
}
