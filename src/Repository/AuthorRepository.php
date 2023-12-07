<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findAllPaginated($page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findAllBySurname($surname)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.surname = :val')
            ->setParameter('val', $surname);

        return $queryBuilder->getQuery()->getResult();
    }

}
