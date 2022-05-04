<?php

namespace App\DataProvider;

use ApiPlatform\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;

class DiscussionSubresourceDataProvider implements SubresourceDataProviderInterface, RestrictedDataProviderInterface
{
    private $managerRegistry;

    private $paginationExtension;

    public function __construct(
        ManagerRegistry $managerRegistry,
        PaginationExtension $paginationExtension
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->paginationExtension = $paginationExtension;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Message::class;
    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null)
    {
        $queryBuilder = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass)
            ->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->innerJoin('m.discussion', 'd', 'WITH', 'm.discussion = :discussion')
            ->setParameter('discussion', $identifiers['id']['id'])
        ;

        $this->paginationExtension->applyToCollection($queryBuilder, new QueryNameGenerator(), $resourceClass, $operationName, $context);

        if ($this->paginationExtension instanceof QueryResultCollectionExtensionInterface &&
            $this->paginationExtension->supportsResult($resourceClass, $operationName, $context))
        {
            return $this->paginationExtension->getResult($queryBuilder, $resourceClass, $operationName, $context);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
