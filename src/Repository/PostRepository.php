<?php

namespace App\Repository;

use App\Entity\Post;
use App\DataProvider\Pagination\PostPaginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatest(int $page = 1): PostPaginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.publishedAt <= :now')
            ->orderBy('p.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
        ;

        $totalItems = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return (new PostPaginator($qb, $page, $totalItems));
    }

}
