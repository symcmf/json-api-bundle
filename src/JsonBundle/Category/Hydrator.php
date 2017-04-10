<?php

namespace JsonBundle\Category;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use JsonBundle\Services\BaseHydrator;

class Hydrator extends BaseHydrator
{
    /**
     * @return array
     */
    protected function getAttributes()
    {
        return [
            'name',
            'description',
        ];
    }

    /**
     * @return array
     */
    protected function getRelations()
    {
        return [
            'articles',
        ];
    }

    /**
     * @return Category
     */
    protected function getNewObject()
    {
        return new Category();
    }

    /**
     * @return array
     */
    protected function getRelatedObjects()
    {
        return [
            'articles' => new Article()
        ];
    }
}
