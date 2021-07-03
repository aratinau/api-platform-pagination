<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataProvider\Extension\MovieCollectionExtensionInterface;
use App\Entity\Movie;
use App\Repository\MovieRepository;

final class MovieCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $paginationExtension;
    private $movieRepository;

    public function __construct(
        MovieCollectionExtensionInterface $paginationExtension,
        MovieRepository $movieRepository
    ) {
        $this->paginationExtension = $paginationExtension;
        $this->movieRepository = $movieRepository;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        if (empty($context["groups"])) {
            return false;
        }

        return
            Movie::class === $resourceClass &&
            $context["groups"] === "normalization-custom-action-using-dataprovider"
        ;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws \RuntimeException
     *
     * @return iterable<Movie>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $isPublished = [];
        if (isset($context['filters']['isPublished'])) {
            $isPublished['isPublished'] = $context['filters']['isPublished'];
        }

        $movies = $this->movieRepository->findBy($isPublished,
            $context['filters']['order']
        );

        return $this->paginationExtension->getResult($movies, $resourceClass, $operationName, $context);
    }
}
