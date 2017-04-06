<?php

namespace JsonBundle\Article;

use AppBundle\Entity\Article;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    protected $resourceType = 'articles';

    public function getId($article)
    {
        /** @var Article $article */
        return $article->getId();
    }

    public function getAttributes($article)
    {
        /** @var Article $article */
        return [
            'title' => $article->getTitle(),
            'description' => $article->getDescription(),
        ];
    }
}