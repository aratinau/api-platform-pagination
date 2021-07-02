<?php

declare(strict_types=1);

namespace App\Repository\City;

use App\Entity\City;

interface CityDataInterface
{
    /**
     * @return array<int, City>
     */
    public function getCities(): array;
}
