<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Job;

final class JobEmployeeDataProvider implements RestrictedDataProviderInterface, DenormalizedIdentifiersAwareItemDataProviderInterface
{
    private $itemDataProvider;
    private $pagination;

    public function __construct(ItemDataProviderInterface $itemDataProvider, Pagination $pagination)
    {
        $this->itemDataProvider = $itemDataProvider;
        $this->pagination = $pagination;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return
            $resourceClass === Job::class &&
            $operationName === "custom-subresource-job-employees"
        ;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $itemDataProvider = $this->itemDataProvider->getItem($resourceClass, $id, $operationName, $context);

        [$page, $offset, $itemPerPage] = $this->pagination->getPagination($resourceClass, $operationName, $context);

        return $itemDataProvider->getEmployees()->getValues();
    }
}
