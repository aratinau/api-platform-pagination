<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Book;
use Doctrine\ORM\QueryBuilder;

class BookExtension implements QueryCollectionExtensionInterface
{
    private const LOCALE = 'fr';

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if ($resourceClass === Book::class) {
            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder
                ->andWhere("$rootAlias.locale = :locale")
                ->setParameter('locale', self::LOCALE)
            ;
        }
    }
}
