<?php

namespace App\DataProvider\Pagination;

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class PostPaginator implements PaginatorInterface, \IteratorAggregate
{
    public const PAGE_SIZE = 3;

    private $postsIterator;
    private $currentPage;
    private $maxResults;
    private $queryBuilder;
    private $totalResult;
    private $results;

    public function __construct(DoctrineQueryBuilder $queryBuilder,
                                int $currentPage,
                                $totalResult,
                                int $maxResults = self::PAGE_SIZE)
    {
        $this->currentPage = $currentPage;
        $this->maxResults = $maxResults;
        $this->queryBuilder = $queryBuilder;
        $this->totalResult = $totalResult;
    }

    public function getLastPage(): float
    {
        return ceil($this->getTotalItems() / $this->getItemsPerPage()) ?: 1.;
    }

    public function getTotalItems(): float
    {
        return $this->totalResult;
    }

    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): float
    {
        return $this->maxResults;
    }

    public function count()
    {
        return iterator_count($this->getIterator());
    }

    public function getIterator()
    {
        if ($this->postsIterator === null) {
            $offset = ($this->currentPage - 1) * $this->maxResults;

            $query = $this->queryBuilder
                ->setFirstResult($offset)
                ->setMaxResults($this->maxResults)
                ->getQuery()
            ;
            $this->results = $query->getResult();

            $this->postsIterator = new \ArrayIterator(
                $this->results
            );
        }

        return $this->postsIterator;
    }

    public function getResults(): \Traversable
    {
        return $this->results;
    }
}
