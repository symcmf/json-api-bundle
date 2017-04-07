<?php

namespace JsonBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BaseJSONApiBundle
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var integer
     */
    private $totalCount;

    /**
     * @return integer
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * BaseJSONApiBundle constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $class
     * @param $pageAttributes
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQuery($class, $pageAttributes)
    {
        $offset = ($pageAttributes['number'] - 1) * $pageAttributes['size'];
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('object')
            ->from($class, 'object')
            ->setFirstResult($offset)
            ->setMaxResults($pageAttributes['size']);

        $paginator = new Paginator($qb);
        $this->totalCount = $paginator->count();

        return $qb;
    }
}
