<?php

namespace App\Service;

use App\DTO\AuthorDTO;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorService
{
    public function __construct(private AuthorRepository $authorRepository, private EntityManagerInterface $emi)
    {

    }

    public function addAuthor($data, ValidatorInterface $validator)
    {

        $authorDTO = new AuthorDTO($data);

        $validations = $validator->validate($authorDTO);

        if (count($validations) > 0) {
            $errors = [];
            foreach ($validations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return ['errors' => $errors];
        }


        $book = new Author();
        $book->setName($authorDTO->name);
        $book->setSurname($authorDTO->surname);
        $book->setFathersName($authorDTO->fathersName);

        $this->emi->persist($book);
        try {
            $this->emi->flush();
            return ['Status' => 'Author saved'];
        } catch (ORMException $e) {
            return ['Error' => $e->getMessage()];
        }


    }

    public function getAuthors($data): array
    {

        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 10;

        $authors = $this->authorRepository->findAllPaginated($page, $limit);

        return $authors;
    }

    public function getAuthorsBySurname($data): array
    {

        $surname = $data['surname'] ?? '';

        $authors = $this->authorRepository->findAllBySurname($surname);

        return $authors;
    }


}