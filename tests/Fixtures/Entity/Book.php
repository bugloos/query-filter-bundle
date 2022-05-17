<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\BookRepository")
 */
class Book
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
    private $title;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=true)
     */
    private $country;

    /**
     * @var BookUser[]|Collection
     * @ORM\OneToMany(
     *     targetEntity=BookUser::class,
     *     mappedBy="book",
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

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
            $bookUser->setBook($this);
        }

        return $this;
    }

    public function removeBookUser(BookUser $bookUser): self
    {
        if ($this->bookUsers->removeElement($bookUser)) {
            // set the owning side to null (unless already changed)
            if ($bookUser->getBook() === $this) {
                $bookUser->setBook(null);
            }
        }

        return $this;
    }
}
