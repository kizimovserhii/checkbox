<?php

namespace App\Repository;


use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);

    }

    public function findAllPaginated($page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }


    public function findById($id)
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->andWhere('b.id = :val')
            ->setParameter('val', $id);

        return $queryBuilder->getQuery()->getResult();
    }

}
