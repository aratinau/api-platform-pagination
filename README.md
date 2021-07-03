# Api Platform Sort, Filter and Pagination on custom data

Inspired from https://github.com/api-platform/demo

There is tree examples in this repo

## First Example use raw data from a csv file

Example using a custom `CityFilter` filtering on raw data from the file `Repository/City/data/worldcities.csv`

CityFilter pass the `sort` and `order` params to the context and this context it's processed in the `CityCollectionDataProvider`

### Usage

sort and filter on raw data from csv
`/api/cities?search[key]=tokyo&order[key]=desc&page=1`

#### Keys available

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


## Second example use custom controller

On path `/movies/custom-action` the action `CustomMovieAction` is called and page and order use `getCustom` method from `MovieRepository`.
The pagination is available

### Usage

custom controller action from database using Paginator
`api/movies/custom-action?page=1&order[id]=asc&order[title]=desc`

## Third example use MovieCollectionDataProvider and repository

On the normalization_context group `normalization-custom-action-using-dataprovider` the MovieCollectionDataProvider is called and the resust is from the repository. The param `isPublished` can be used and the result is sorted by the value (true or false) asked.
The param `order` can be `title` or `id` and ordered by `asc|desc`. The pagination is available

### Usage

data provider using repository (by group from normalization_context on Movie entity)
`api/movies/custom-action-using-dataprovider?page=2&order[title]=desc&isPublished=false`

## Install

    composer install

    php bin/console doctrine:schema:update --env=dev --dump-sql

    php bin/console doctrine:schema:update --env=dev --force

    php bin/console hautelook:fixtures:load 

## Launch

    symfony serve --no-tls
