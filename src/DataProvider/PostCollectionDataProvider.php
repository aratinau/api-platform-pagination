<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Post;
use App\Repository\PostRepository;

class PostCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $postRepository;
    private $pagination;

    public function __construct(PostRepository $postRepository,
                                Pagination $pagination)
    {
        $this->postRepository = $postRepository;
        $this->pagination = $pagination;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Post::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        [$page] = $this->pagination->getPagination($resourceClass, $operationName, $context);

        return $this->postRepository->findLatest($page);
    }
}
