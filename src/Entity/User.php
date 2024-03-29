<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Annonce::class)]
    private $author;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: AnnonceListByUser::class)]
    private $annonceFav;

    public function __construct()
    {
        $this->author = new ArrayCollection();
        $this->annonceFav = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasNotRoleAdmin(): bool
    {
        $this->roles = 'ROLE_ADMIN';
        return false;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Annonce>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Annonce $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author[] = $author;
            $author->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthor(Annonce $author): self
    {
        if ($this->author->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getAuthor() === $this) {
                $author->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnnonceListByUser>
     */
    public function getAnnonceFav(): Collection
    {
        return $this->annonceFav;
    }

    public function addAnnonceFav(AnnonceListByUser $annonceFav): self
    {
        if (!$this->annonceFav->contains($annonceFav)) {
            $this->annonceFav[] = $annonceFav;
            $annonceFav->setUsers($this);
        }

        return $this;
    }

    public function removeAnnonceFav(AnnonceListByUser $annonceFav): self
    {
        if ($this->annonceFav->removeElement($annonceFav)) {
            // set the owning side to null (unless already changed)
            if ($annonceFav->getUsers() === $this) {
                $annonceFav->setUsers(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username;
    }
}
