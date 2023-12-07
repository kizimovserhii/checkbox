<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class AuthorDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public string $name;

    #[Assert\NotBlank]
    public string $surname;

    public string $fathersName;

    public function __construct($data)
    {
        $this->name = $data['name'] ?? '';
        $this->surname = $data['surname'] ?? '';
        $this->fathersName = $data['fathers_name'] ?? '';
    }
}