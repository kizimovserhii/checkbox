<?php

namespace App\Service;

use App\DTO\BookDTO;
use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraints as Assert;


class BookService
{

    public function __construct(private BookRepository $bookRepository,
                                private AuthorRepository $authorRepository,
                                private EntityManagerInterface $emi,
                                private ParameterBagInterface $parameterBag
    )
    {

    }
    public function saveBook($data, $image, $validator)
    {
        if ($image !== '') {
            $constraints = [
                new Assert\File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                    'mimeTypesMessage' => 'Please, upload image in format JPEG, PNG or GIF',
                ]),

            ];

            $violations = $validator->validate($image, $constraints);

            if (count($violations) > 0) {
                foreach ($violations as $violation) {
                    $errors[$violation->getPropertyPath()] = $violation->getMessage();
                }
                return ['errors' => $errors];
            }


            $savedPath = $this->saveImage($image);

            $data['image'] = $savedPath;
        }

        $bookDTO = new BookDTO($data);

        $validations = $validator->validate($bookDTO);

        if (count($validations) > 0) {
            $errors = [];
            foreach ($validations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return ['errors' => $errors];
        }

        $authors = $this->getAuthorsCollection($bookDTO->authors);

        if (isset($data['id'])) {
            $book = $this->emi->getRepository(Book::class)->find($data['id']);
            if (!$book) {
                return ['Error' => "This book doesnt exist"];
            }
        } else {
            $book = new  Book();
        }

        $book->setTitle($bookDTO->title);
        $book->setDescription($bookDTO->description);
        $book->setImage($bookDTO->image);
        foreach ($authors as $author){
            $book->addAuthor($author);
        }

        $book->setReleaseDate(new \DateTime($bookDTO->releaseDate));

        if(!isset($data['id'])) {
            $this->emi->persist($book);
        }

        try {
            $this->emi->flush();
            return ['Status' => 'Book saved'];
        } catch (ORMException $e) {
            return ['Error' => $e->getMessage()];
        }

    }

    private function saveImage($image): string
    {
        $uploadsDirectory = $this->parameterBag->get('kernel.project_dir') . '/public/uploads';
        $fileName = md5(uniqid()) . '.' . $image->guessExtension();
        $image->move($uploadsDirectory, $fileName);
        return '/uploads/' . $fileName;
    }

    public function getAuthorsCollection($data): array
    {

        $authors = [];
        foreach ($data as $author_id) {
            $authors[] = $this->emi->getRepository(Author::class)->find($author_id);
        }
        return $authors;
    }

    public function getBooks($data): array
    {


        $page = $data['page'] ?? 1;
        $limit = $data['limit'] ?? 10;

        $books = $this->bookRepository->findAllPaginated($page, $limit);

        return $books;
    }

    public function getBooksById($id): array
    {

        $books = $this->bookRepository->findById($id);

        return $books;
    }


}