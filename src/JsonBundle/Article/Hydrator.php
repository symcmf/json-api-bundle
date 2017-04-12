<?php

namespace JsonBundle\Article;

use AppBundle\Entity\Article;
use JsonBundle\Services\BaseHydrator;

class Hydrator extends BaseHydrator
{
    /**
     * @return array
     */
    public function getAttributes()
    {
        return [
            'title',
            'description',
        ];
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        return [];
    }

    /**
     * @return Article
     */
    protected function getNewObject()
    {
        return new Article();
    }

    /**
     * @return array
     */
    protected function getRelatedObjects()
    {
        return [];
    }
}
