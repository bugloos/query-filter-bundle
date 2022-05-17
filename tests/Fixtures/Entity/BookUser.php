<?php

namespace Bugloos\QueryFilterBundle\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bugloos\QueryFilterBundle\Tests\Fixtures\Repository\BookUserRepository")
 */
class BookUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Book
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="bookUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
