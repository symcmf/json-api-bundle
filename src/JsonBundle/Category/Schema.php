<?php

namespace JsonBundle\Category;

use AppBundle\Entity\Category;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    protected $resourceType = 'categories';

    public function getId($category)
    {
        /** @var Category $category */
        return $category->getId();
    }

    /**
     * @param Category $category
     * @return array
     */
    public function getAttributes($category)
    {
        /** @var Category $category */
        return [
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];
    }

    /**
     * @param Category $category
     * @param bool $isPrimary
     * @param array $includeList
     * @return array
     */
    public function getRelationships($category, $isPrimary, array $includeList)
    {
        /** @var Category $category */
        return [
            'articles' => [self::DATA => $category->getArticles()],
        ];
    }

    /**
     * @return array
     */
    public function getIncludePaths()
    {
        return [
            'articles'
        ];
    }
}