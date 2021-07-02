# Api Platform Sort, Filter and Pagination on raw data

Inspired from https://github.com/api-platform/demo

This is an example using a custom `CityFilter` filtering on raw data from the file `Repository/City/data/worldcities.csv`

CityFilter pass the `sort` and `order` params to the context and this context it's processed in the `CityCollectionDataProvider`

## Install

    composer install

## Launch

    symfony serve --no-tls

## Usage

`/api/cities?search[key]=tokyo&order[key]=desc&page=1`

### Keys available

- id
- city
- city_ascii
- lat
- lng
- country
- iso2
- iso3
- admin_name
- capital
- population
