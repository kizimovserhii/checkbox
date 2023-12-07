<?php

namespace App\Controller;

use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorController extends AbstractController
{
    private $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    #[Route('/api/author/new', methods: ["POST"])]
    public function  newAuthor(Request $request, ValidatorInterface $validator): Response
    {

        $data = $request->request->all();

        $response = $this->authorService->addAuthor($data, $validator);

        return $this->json($response);

    }

    #[Route('/api/author/all')]
    public function  getAuthors(Request $request): Response
    {

        $data = json_decode($request->getContent(), true);

        $response = $this->authorService->getAuthors($data);

        return $this->json($response);

    }

    #[Route('/api/author/bysurname')]
    public function  getAuthorsBySurname(Request $request): Response
    {

        $data = $request->request->all();

        $response = $this->authorService->getAuthorsBySurname($data);

        return $this->json($response);

    }

}
