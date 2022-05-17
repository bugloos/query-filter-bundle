<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $surname;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var BookUser[]|Collection
     * @ORM\OneToMany(
     *     targetEntity=BookUser::class,
     *     mappedBy="user",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    private $bookUsers;

    public function __construct()
    {
        $this->bookUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return BookUser[]|Collection
     */
    public function getBookUsers(): Collection
    {
        return $this->bookUsers;
    }

    public function addBookUser(BookUser $bookUser): self
    {
        if (!$this->bookUsers->contains($bookUser)) {
            $this->bookUsers[] = $bookUser;
            $bookUser->setUser($this);
        }

        return $this;
    }

    public function removeBookUser(BookUser $bookUser): self
    {
        if ($this->bookUsers->removeElement($bookUser)) {
            // set the owning side to null (unless already changed)
            if ($bookUser->getUser() === $this) {
                $bookUser->setUser(null);
            }
        }

        return $this;
    }
}
