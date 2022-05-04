<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
class Marque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'marque', targetEntity: Annonce::class)]
    private $list_cars;

    public function __construct()
    {
        $this->list_cars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getListCars(): Collection
    {
        return $this->list_cars;
    }

    public function addListCar(Annonce $listCar): self
    {
        if (!$this->list_cars->contains($listCar)) {
            $this->list_cars[] = $listCar;
            $listCar->setMarque($this);
        }

        return $this;
    }

    public function removeListCar(Annonce $listCar): self
    {
        if ($this->list_cars->removeElement($listCar)) {
            // set the owning side to null (unless already changed)
            if ($listCar->getMarque() === $this) {
                $listCar->setMarque(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
