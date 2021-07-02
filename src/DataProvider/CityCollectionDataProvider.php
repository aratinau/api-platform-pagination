<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataProvider\Extension\CityCollectionExtensionInterface;
use App\Entity\City;
use App\Filter\CityFilter;
use App\Repository\City\CityDataInterface;
use App\Repository\City\CityDataRepository;

final class CityCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $repository;
    private $paginationExtension;

    public function __construct(
        CityDataInterface $repository,
        CityCollectionExtensionInterface $paginationExtension
    ) {
        $this->repository = $repository;
        $this->paginationExtension = $paginationExtension;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return City::class === $resourceClass;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws \RuntimeException
     *
     * @return iterable<City>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $cityFilterSearch = $context[CityFilter::CITY_FILTER_SEARCH_CONTEXT] ?? null;
        $cityFilterOrder = $context[CityFilter::CITY_FILTER_ORDER_CONTEXT] ?? null;

        try {
            /** @var CityDataRepository $repository */
            $repository = $this->repository;

            if ($cityFilterSearch) {
                $repository->setFilter($cityFilterSearch);
            }
            if ($cityFilterOrder) {
                $repository->setOrder($cityFilterOrder);
            }

            $collection = $repository->getCities();
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Unable to retrieve cities from external source: %s', $e->getMessage()));
        }

        if (!$this->paginationExtension->isEnabled($resourceClass, $operationName, $context)) {
            return $collection;
        }

        return $this->paginationExtension->getResult($collection, $resourceClass, $operationName, $context);
    }
}
