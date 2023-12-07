<?php

namespace App\Controller;


use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class BookController extends AbstractController
{
    private $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    #[Route('/api/book/save', methods: ["POST"])]
    public function  newBook(Request $request, ValidatorInterface $validator): Response
    {
        $image = $request->files->get('image') ?? '' ;

        $data = $request->request->all();

        $respons = $this->bookService->saveBook($data, $image, $validator);

        return $this->json($respons);
    }

    #[Route('/api/book/all')]
    public function  getBooks(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);

        $response = $this->bookService->getBooks($data);

        return $this->json($response);

    }

    #[Route('/api/book/view')]
    public function viewBooks(Request $request): Response
    {

        $data = $request->request->all();

        $response = $this->bookService->getBooksById($data['id']);

        return $this->json($response);

    }
}
