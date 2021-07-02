<?php

declare(strict_types=1);

namespace App\Repository\City;

use App\Entity\City;

final class CityDataRepository implements CityDataInterface
{
    private const DATA_SOURCE = 'worldcities.csv';
    private const FIELDS_COUNT = 11;

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

    private function sanitize(?string $str): string
    {
        return trim(utf8_encode((string) $str));
    }
}
