<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $model;

    #[ORM\Column(type: 'float')]
    private $prix;

    #[ORM\Column(type: 'integer')]
    private $year;

    #[ORM\Column(type: 'float')]
    private $km;

    #[ORM\Column(type: 'string', length: 255)]
    private $fuel;

    #[ORM\Column(type: 'boolean')]
    private $license;

    #[ORM\Column(type: 'string', length: 255)]
    private $imgfile;

    #[ORM\ManyToOne(targetEntity: Marque::class, inversedBy: 'list_cars')]
    private $marque;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'author')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\OneToMany(mappedBy: 'annonces', targetEntity: AnnonceListByUser::class)]
    private $usersFav;

    #[ORM\Column(type: 'boolean')]
    private $isVisible;

    public function __construct()
    {
        $this->usersFav = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getKm(): ?float
    {
        return $this->km;
    }

    public function setKm(float $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(string $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getLicense(): ?bool
    {
        return $this->license;
    }

    public function setLicense(bool $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function getImgfile(): ?string
    {
        return $this->imgfile;
    }

    public function setImgfile(string $imgfile): self
    {
        $this->imgfile = $imgfile;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, AnnonceListByUser>
     */
    public function getUsersFav(): Collection
    {
        return $this->usersFav;
    }

    public function addUsersFav(AnnonceListByUser $usersFav): self
    {
        if (!$this->usersFav->contains($usersFav)) {
            $this->usersFav[] = $usersFav;
            $usersFav->setAnnonces($this);
        }

        return $this;
    }

    public function removeUsersFav(AnnonceListByUser $usersFav): self
    {
        if ($this->usersFav->removeElement($usersFav)) {
            // set the owning side to null (unless already changed)
            if ($usersFav->getAnnonces() === $this) {
                $usersFav->setAnnonces(null);
            }
        }

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * @param User $user
     * @return boolean
     */
    public function isUserfav (User $user): bool
    {
        foreach($this->usersFav as $usersFav){
            if($usersFav ->getUsers() === $user) return true;
        }
        return false;
    }
}
