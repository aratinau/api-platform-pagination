<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\FilterExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Car;
use Doctrine\Persistence\ManagerRegistry;

final class CarCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $managerRegistry;
    private $collectionExtensions;

    public function __construct(ManagerRegistry $managerRegistry,
                                $collectionExtensions
    )
    {
        $this->managerRegistry = $managerRegistry;
        $this->collectionExtensions = $collectionExtensions;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Car::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $queryNameGenerator = new QueryNameGenerator();
        $queryBuilder = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass)
            ->createQueryBuilder('c');

        if (isset($context['filters']['color'])) {
            $queryBuilder
                ->where('c.color = :color')
                ->setParameter('color', $context['filters']['color'])
            ;
        }

        /** @var FilterExtension $extension */
        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operationName, $context);
            if ($extension instanceof QueryResultCollectionExtensionInterface &&
                $extension->supportsResult($resourceClass, $operationName, $context)
            ) {
                return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
