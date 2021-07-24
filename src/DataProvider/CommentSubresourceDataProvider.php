<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use App\Entity\Comment;

final class CommentSubresourceDataProvider implements SubresourceDataProviderInterface, RestrictedDataProviderInterface
{
    private $alreadyInvoked = false;
    private $subresourceDataProvider;

    public function __construct(SubresourceDataProviderInterface $subresourceDataProvider)
    {
        $this->subresourceDataProvider = $subresourceDataProvider;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return false === $this->alreadyInvoked && $resourceClass === Comment::class;
    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null)
    {
        $this->alreadyInvoked = true;

        return $this->subresourceDataProvider->getSubresource($resourceClass, $identifiers, $context,  $operationName);
    }
}
