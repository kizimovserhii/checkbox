<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class BookDTO
{
    #[Assert\NotBlank]
    public string $title;

    public string $description;

    public string $image;

    #[Assert\NotBlank]
    public array $authors;

    #[Assert\NotBlank]
    public string $releaseDate;

    public function __construct($data)
    {
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->image = $data['image'] ?? '';
        $this->authors = $data['authors'] ?? [];
        $this->releaseDate = $data['release_date'] ?? '';
    }

}