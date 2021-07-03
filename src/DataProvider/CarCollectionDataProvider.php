<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Car;
use Doctrine\Persistence\ManagerRegistry;

final class CarCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $managerRegistry;
    private $paginationExtension;

    public function __construct(ManagerRegistry $managerRegistry, PaginationExtension $paginationExtension)
    {
        $this->managerRegistry = $managerRegistry;
        $this->paginationExtension = $paginationExtension;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Car::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $queryBuilder = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass)->createQueryBuilder('c');

        if (isset($context['filters']['color'])) {
            $queryBuilder
                ->where('c.color = :color')
                ->setParameter('color', $context['filters']['color'])
            ;
        }

        $this->paginationExtension->applyToCollection($queryBuilder, new QueryNameGenerator(), $resourceClass, $operationName, $context);

        if ($this->paginationExtension instanceof QueryResultCollectionExtensionInterface &&
            $this->paginationExtension->supportsResult($resourceClass, $operationName, $context)) {
            return $this->paginationExtension->getResult($queryBuilder, $resourceClass, $operationName, $context);
        }

        return $queryBuilder->getQuery()->getResult();

    }
}
