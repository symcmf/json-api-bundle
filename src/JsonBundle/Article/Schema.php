<?php

namespace JsonBundle\Article;

use AppBundle\Entity\Article;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'articles';

    /**
     * @param object $article
     *
     * @return int
     */
    public function getId($article)
    {
        /** @var Article $article */
        return $article->getId();
    }

    /**
     * @param object $article
     *
     * @return array
     */
    public function getAttributes($article)
    {
        /** @var Article $article */
        return [
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
        ];
    }

    /**
     * @param object $article
     * @param bool $isPrimary
     * @param array $includeList
     *
     * @return array
     */
    public function getRelationships($article, $isPrimary, array $includeList)
    {
        /** @var Article $article */
        return [
            'categories' => [self::DATA => $article->getCategory()],
        ];
    }
}
