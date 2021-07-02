<?php

declare(strict_types=1);

namespace App\Repository\City;

use App\Entity\City;
use Symfony\Contracts\Cache\CacheInterface;

final class CityCachedDataRepository implements CityDataInterface
{
    private $repository;
    private $cache;

    public function __construct(
        CityDataInterface $repository,
        CacheInterface $cache
    ) {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Local caching is done so the CSV isn't reloaded at every call.
     *
     * @throws \InvalidArgumentException
     *
     * @return array<int, City>
     */
    public function getCities(): array
    {
        return $this->cache->get('cities', function () {
            return $this->repository->getCities();
        });
    }
}
