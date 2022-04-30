# Api Platform Sort, Filter and Pagination on custom data

Inspired by https://github.com/api-platform/demo

# Summary

- [First Example use raw data from a csv file](#first-example-use-raw-data-from-a-csv-file)
- [Second example use custom controller](#second-example-use-custom-controller)
- [Third example use MovieCollectionDataProvider and repository](#third-example-use-moviecollectiondataprovider-and-repository)
- [Fourth example use QueryBuilder in CarCollectionDataProvider (collectionExtensions)](#fourth-example-use-querybuilder-in-carcollectiondataprovider-collectionextensions)
- [Fifth example use JobCollectionDataProvider (paginationExtension)](#fifth-example-use-jobcollectiondataprovider-paginationextension)
- [Sixth example use FurnitureDataProvider (collectionDataProvider)](#sixth-example-use-furnituredataprovider-collectiondataprovider)
- [Seventh example use QueryBuilder in subresource](#seventh-example--simple-dataprovider-using-subresourcedataprovider)
- [Eighth example use QueryBuilder in subresource](#eight-example-use-querybuilder-in-subresource)
- [Ninth use custom subresource with provider (without subresourceDataProvider)](#ninth-example---custom-subresource-with-provider-without-subresourcedataprovider)

## Install

Build the project `make build`

Start the project `make start`

Enter the php container with `make sh`

Install dependencies (in php container) with `composer install`

Load fixtures with `make init-fixtures`

You can go to `http://127.0.0.1:8000/api/`


## First Example use raw data from a csv file

Example using a custom `CityFilter` filtering on raw data from the file `Repository/City/data/worldcities.csv`

CityFilter pass the `sort` and `order` params to the context and this context it's processed in the `CityCollectionDataProvider`

The pagination is available

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

On the normalization_context group `normalization-custom-action-using-dataprovider` the MovieCollectionDataProvider is called and the resust is from the repository. The param `isPublished=true|false` can be used and the result is filtered by the value asked.
The param `order` can be `title` or `id` and ordered by `asc|desc`. The pagination is available

### Usage

data provider using repository (by group from normalization_context on Movie entity)
`api/movies/custom-action-using-dataprovider?page=2&order[title]=desc&isPublished=false`

## Fourth example use QueryBuilder in CarCollectionDataProvider (collectionExtensions)

This example show how use QueryBuilder and filters with CollectionExtensions in CarCollectionDataProvider.
The collection is filtered by color from the `context` with the QueryBuilder and filtered by `name` and `isPublished` from SearchFilter in Car entity.
The pagination is available

### Usage

`/api/cars?color=color_name&isPublished=true|false&page=1`

#### Color name available

- red
- orange
- green
- yellow
- black

## Fifth example use JobCollectionDataProvider (paginationExtension)

This example show how use PaginationExtension in JobCollectionDataProvider

### Usage

`api/jobs?page=1`

## Sixth example use FurnitureDataProvider (collectionDataProvider)

Basic example showing how use and configure CollectionDataProvider

### Usage

`api/furniture?page=2`

## Seventh example : simple DataProvider using subresourceDataProvider

CommentSubresourceDataProvider show how use the standard behaviour

### Usage

`api/movies/{id}/comments`

## Eighth example use QueryBuilder in subresource

DiscussionSubresourceDataProvider show how use QueryBuilder and order by DESC the result

### Usage

`api/discussions/{id}/messages?page=1`

## Ninth example - custom subresource with provider (without subresourceDataProvider)

With JobDataProvider and path `jobs/{id}/employees` return employees from id's job

[ ] TODO Pagination

### Usage

`api/jobs/{id}/employees/{arg1}`

## Tenth example - Custom Paginator in Provider with QueryBuilder

PostCollectionDataProvider call the method findLatest from PostRepository and PostRepository call PostPaginator

### Usage

`/api/posts?page=2`

## Notes

`Album` and `Artist` are ready to be used
