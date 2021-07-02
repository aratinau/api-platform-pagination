<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Filter\CityFilter;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"
 *     }
 * )
 * @ApiFilter(CityFilter::class, arguments={"throwOnInvalid"=false})
 */
class City
{
    private $id;
    private $city;
    private $cityAscii;
    private $lat;
    private $lng;
    private $country;
    private $iso2;
    private $iso3;
    private $capital;
    private $population;
    private $density;

    public function __construct(
        int $id,
        string $city,
        string $cityAscii,
        string $lat,
        string $lng,
        string $country,
        string $iso2,
        string $iso3,
        string $capital,
        string $population,
        string $density
    ) {
        $this->id = $id;
        $this->city = $city;
        $this->cityAscii = $cityAscii;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->country = $country;
        $this->iso2 = $iso2;
        $this->iso3 = $iso3;
        $this->capital = $capital;
        $this->population = $population;
        $this->density = $density;
    }

    /**
     * @ApiProperty(
     *     identifier=true
     * )
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): City
    {
        $this->id = $id;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): City
    {
        $this->city = $city;

        return $this;
    }

    public function getCityAscii(): ?string
    {
        return $this->cityAscii;
    }

    public function setCityAscii(string $city_ascii): City
    {
        $this->cityAscii = $city_ascii;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): City
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): City
    {
        $this->lng = $lng;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): City
    {
        $this->country = $country;

        return $this;
    }

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(string $iso2): City
    {
        $this->iso2 = $iso2;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(string $iso3): City
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(string $capital): City
    {
        $this->capital = $capital;

        return $this;
    }

    public function getPopulation(): ?string
    {
        return $this->population;
    }

    public function setPopulation(string $population): City
    {
        $this->population = $population;

        return $this;
    }

    public function getDensity(): ?string
    {
        return $this->density;
    }

    public function setDensity(string $density): City
    {
        $this->density = $density;

        return $this;
    }
}
