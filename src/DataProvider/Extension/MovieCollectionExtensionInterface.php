<?php

declare(strict_types=1);

namespace App\DataProvider\Extension;

use App\Entity\City;

interface MovieCollectionExtensionInterface
{
    /**
     * Returns the final result object.
     *
     * @param array<int, City>  $collection
     * @param array<string, mixed> $context
     *
     * @return iterable<City>
     */
    public function getResult(array $collection, string $resourceClass, string $operationName = null, array $context = []): iterable;

    /**
     * Tells if the extension is enabled or not.
     *
     * @param array<string, mixed> $context
     */
    public function isEnabled(string $resourceClass = null, string $operationName = null, array $context = []): bool;
}
