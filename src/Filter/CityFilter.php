<?php

namespace App\Filter;

use ApiPlatform\Core\Serializer\Filter\FilterInterface;
use Symfony\Component\HttpFoundation\Request;

class CityFilter implements FilterInterface
{
    public const CITY_FILTER_SEARCH_CONTEXT = 'city_search_filter';
    public const CITY_FILTER_ORDER_CONTEXT = 'city_order_filter';
    private $throwOnInvalid;

    public function __construct(bool $throwOnInvalid = false)
    {
        $this->throwOnInvalid = $throwOnInvalid;
    }

    public function apply(Request $request, bool $normalization, array $attributes, array &$context)
    {
        $search = $request->query->get('search');
        $order = $request->query->get('order');

        if (!$search && $this->throwOnInvalid) {
            return;
        }

        if ($search) {
            $context[self::CITY_FILTER_SEARCH_CONTEXT] = $search;
        }

        if ($order) {
            $context[self::CITY_FILTER_ORDER_CONTEXT] = $order;
        }
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'search' => [
                'property' => null,
                'type' => 'string',
                'required' => false,
                'openapi' => [
                    'description' => 'Search and Order by key from array',
                ],
            ]
        ];
    }

}
