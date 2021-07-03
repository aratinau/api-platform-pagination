# Api Platform Sort, Filter and Pagination on raw data

Inspired from https://github.com/api-platform/demo

This is an example using a custom `CityFilter` filtering on raw data from the file `Repository/City/data/worldcities.csv`

CityFilter pass the `sort` and `order` params to the context and this context it's processed in the `CityCollectionDataProvider`

## Install

    composer install

    php bin/console doctrine:schema:update --env=dev --dump-sql

    php bin/console doctrine:schema:update --env=dev --force

    php bin/console hautelook:fixtures:load 

## Launch

    symfony serve --no-tls

## Usage

sort and filter on raw data from csv 

`/api/cities?search[key]=tokyo&order[key]=desc&page=1`

custom controller action from database using Paginator
`api/movies/custom-action?page=1&order[id]=asc&order[title]=desc`

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
