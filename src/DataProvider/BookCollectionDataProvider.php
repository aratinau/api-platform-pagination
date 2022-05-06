<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use App\DTO\SessionParameter;
use App\Entity\Book;
use App\Entity\Session;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Validator\ValidatorInterface;

class BookCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private $collectionExtensions,
        private ValidatorInterface $validator,
        private AuthorizationCheckerInterface $authorizationChecker,
        private Security $security
    ) {
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        $includeArchived = $context['filters']['includeArchived'] ?? 'false';

        $manager = $this->managerRegistry->getManagerForClass($resourceClass);
        $repository = $manager->getRepository($resourceClass);
        $queryBuilder = $repository->createQueryBuilder('o');
        $alias = $queryBuilder->getRootAliases()[0];

        if ($includeArchived === 'false') {
            $queryBuilder->andWhere("$alias.isArchived = false");
        }

        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            /**
             * Extensions are (in this order)
             * - "App\Doctrine\BookExtension"
             * - "ApiPlatform\Doctrine\Orm\Extension\FilterExtension"
             * - "ApiPlatform\Doctrine\Orm\Extension\FilterEagerLoadingExtension"
             * - "ApiPlatform\Doctrine\Orm\Extension\EagerLoadingExtension"
             * - "ApiPlatform\Doctrine\Orm\Extension\OrderExtension"
             * - "ApiPlatform\Doctrine\Orm\Extension\PaginationExtension"
             */
            $extension->applyToCollection(
                $queryBuilder,
                $queryNameGenerator,
                $resourceClass,
                $operationName,
                $context
            );

            if (
                $extension instanceof QueryResultCollectionExtensionInterface
                &&
                $extension->supportsResult($resourceClass, $operationName, $context)
            ) {
                return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
            }
        }


        return $queryBuilder->getQuery()->getResult();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Book::class === $resourceClass;
    }
}
