<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Doctrine\Common\Collections\Criteria;


/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    const ITEMS_PER_PAGE = 20;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function getCustom(int $page = 1, array $orders = []): Paginator
    {
        $firstResult = ($page -1) * self::ITEMS_PER_PAGE;

        $queryBuilder = $this->createQueryBuilder('b');

        if (false === empty($orders)) {
            foreach ($orders as $sort => $order) {
                $queryBuilder->addOrderBy('b.'.$sort, $order);
            }
        }

        $criteria = Criteria::create()
            ->setFirstResult($firstResult)
            ->setMaxResults(self::ITEMS_PER_PAGE)
        ;
        $queryBuilder->addCriteria($criteria);

        $doctrinePaginator = new DoctrinePaginator($queryBuilder);
        $paginator = new Paginator($doctrinePaginator);

        return $paginator;
    }

}
