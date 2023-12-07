<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Table(name: "author")]
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fathers_name = null;

    /**
     * Many Authors have Many Books.
     * @var Collection<int, Book>
     */
    #[MaxDepth(1)]
    #[ManyToMany(targetEntity: "Book", mappedBy: "authors"  )]
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }


    public function addBook(Book $book)
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    /**
     * @return mixed
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param mixed $books
     */
    public function setBooks($books): void
    {
        $this->books = $books;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFathersName(): ?string
    {
        return $this->fathers_name;
    }

    public function setFathersName(?string $fathers_name): static
    {
        $this->fathers_name = $fathers_name;

        return $this;
    }
}
