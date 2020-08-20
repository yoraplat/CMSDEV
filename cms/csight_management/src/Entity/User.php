<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Period::class, mappedBy="client")
     */
    private $userPeriods;

    /**
     * @ORM\OneToMany(targetEntity=Worksheet::class, mappedBy="employee")
     * @ApiSubresource
     */
    private $userWorksheets;


    public function __construct()
    {
        $this->periods = new ArrayCollection();
        $this->userPeriods = new ArrayCollection();
        $this->userWorksheets = new ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        return $this->roles;
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        $roles[] = ['ROLE_USER'];

        return $this;
    }

    /**
     * @return Collection|Period[]
     */
    public function getUserPeriods(): Collection
    {
        return $this->userPeriods;
    }

    public function addUserPeriod(Period $userPeriod): self
    {
        if (!$this->userPeriods->contains($userPeriod)) {
            $this->userPeriods[] = $userPeriod;
            $userPeriod->setClient($this);
        }

        return $this;
    }

    public function removeUserPeriod(Period $userPeriod): self
    {
        if ($this->userPeriods->contains($userPeriod)) {
            $this->userPeriods->removeElement($userPeriod);
            // set the owning side to null (unless already changed)
            if ($userPeriod->getClient() === $this) {
                $userPeriod->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Worksheet[]
     */
    public function getUserWorksheets(): Collection
    {
        return $this->userWorksheets;
    }

    public function addUserWorksheet(Period $userWorksheet): self
    {
        if (!$this->userWorksheets->contains($userWorksheet)) {
            $this->userWorksheets[] = $userWorksheet;
            $userWorksheet->setEmployee($this);
        }

        return $this;
    }

    public function removeUserWorksheet(Worksheet $userWorksheet): self
    {
        if ($this->userWorksheets->contains($userWorksheet)) {
            $this->userWorksheets->removeElement($userWorksheet);
            // set the owning side to null (unless already changed)
            if ($userWorksheet->getEmployee() === $this) {
                $userWorksheet->setEmployee(null);
            }
        }

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
