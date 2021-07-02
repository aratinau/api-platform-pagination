<?php

declare(strict_types=1);

namespace App\Repository\City;

use App\Entity\City;

/**
 * Class CityDataRepository
 * @package App\Repository\City
 */
final class CityDataRepository implements CityDataInterface
{
    private const DATA_SOURCE = 'worldcities.csv';
    private const FIELDS_COUNT = 11;
    private $filter;
    private $order;

    /**
     * @return array<int, City>
     */
    public function getCities(): array
    {
        return $this->getFromCsv();
    }

    /**
     * @return array<int, City>
     */
    public function getFromCsv(): array
    {
        foreach ($this->getFileAsArray() as $line) {
            $data[] = str_getcsv($line, ',');
        }

        $cpt = 0;
        foreach ($data ?? [] as $row) {
            if (1 === ++$cpt) {
                continue;
            }
            if (self::FIELDS_COUNT !== count($row)) {
                throw new \RuntimeException(sprintf('Invalid data at row: %d', count($row)));
            }

            if ($this->filter) {
                foreach ($this->filter as $key => $filter) {
                    switch ($key) {
                        case "city":
                            $rowId = 0;
                            break;
                        case "city_ascii":
                            $rowId = 1;
                            break;
                        case "lat":
                            $rowId = 2;
                            break;
                        case "lng":
                            $rowId = 3;
                            break;
                        case "country":
                            $rowId = 4;
                            break;
                        case "iso2":
                            $rowId = 5;
                            break;
                        case "iso3":
                            $rowId = 6;
                            break;
                        case "admin_name":
                            $rowId = 7;
                            break;
                        case "capital":
                            $rowId = 8;
                            break;
                        case "population":
                            $rowId = 9;
                            break;
                    }
                }
                $city = strtolower($row[$rowId]);
                if (strpos($city, $filter) === false) {
                    continue;
                }
            }

            $city = new City(
                $cpt - 1,
                $this->sanitize($row[0] ?? ''),
                $this->sanitize($row[1] ?? ''),
                $this->sanitize($row[2] ?? ''),
                $this->sanitize($row[3] ?? ''),
                $this->sanitize($row[4] ?? ''),
                $this->sanitize($row[5] ?? ''),
                $this->sanitize($row[6] ?? ''),
                $this->sanitize($row[7] ?? ''),
                $this->sanitize($row[8] ?? ''),
                $this->sanitize($row[9] ?? ''),
                $this->sanitize($row[10] ?? '')
            );
            $cities[$cpt - 1] = $city;
        }

        if ($this->order) {
            foreach ($this->order as $key => $order)
            {
                usort($cities, function ($a, $b) use($key, $order) {
                    $getMethod = 'get' . ucfirst($key);
                    if ($a->{$getMethod}() === $b->{$getMethod}()) {
                        return 0;
                    }
                    if ($order === 'desc') {
                        return ($a->{$getMethod}() < $b->{$getMethod}());
                    } else {
                        return ($a->{$getMethod}() > $b->{$getMethod}());
                    }
                });
            }
        }

        return $cities ?? [];
    }

    /**
     * @return array<int, string>
     */
    private function getFileAsArray(): array
    {
        $csvFileName = __DIR__.'/data/'.self::DATA_SOURCE;
        if (!is_file($csvFileName)) {
            throw new \RuntimeException(sprintf("Can't find data source: %s", $csvFileName));
        }
        $file = file($csvFileName);
        if (!is_array($file)) {
            throw new \RuntimeException(sprintf("Can't load data source: %s", $csvFileName));
        }

        return $file;
    }

    /**
     * @param string|null $str
     * @return string
     */
    private function sanitize(?string $str): string
    {
        return trim(utf8_encode((string) $str));
    }

    /**
     * @return string|null
     */
    public function getFilter(): ?string
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     * @return $this
     */
    public function setFilter(array $filter): CityDataRepository
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @param array $order
     * @return $this
     */
    public function setOrder(array $order): CityDataRepository
    {
        $this->order = $order;

        return $this;
    }
}
