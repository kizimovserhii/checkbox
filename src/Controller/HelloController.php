<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends AbstractController
{
    public function __construct()
    {

    }

    #[Route('/')]
    public function  root(): Response
    {

        return $this->json(['message' => 'hello']);
    }
}