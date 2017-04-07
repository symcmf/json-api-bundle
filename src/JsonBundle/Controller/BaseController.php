<?php

namespace JsonBundle\Controller;

use AppBundle\Entity\Category;
use JsonBundle\Request\RequestTrait;
use JsonBundle\Services\BaseJSONApiBundle;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 * @package JsonBundle\Controller
 */
abstract class BaseController extends Controller
{

    use RequestTrait;

    /**
     * @return EncodingParameters
     */
    private function getEncodingParameters()
    {
        $params = new EncodingParameters(
            $this->getIncludeAttributes(),
            $this->getSparseFieldAttributes(),
            $this->getSortAttributes(),
            $this->getPaginationAttributes()
        );

        return $params;
    }

    /**
     * @param Request $request
     * @param $object
     *
     * @return mixed
     */
    protected function viewObject(Request $request, $object)
    {
        $this->setRequest($request);
        return $this
            ->getEncoder()->encodeData($object, $this->getEncodingParameters());
    }

    protected function getList(Request $request)
    {
        $this->setRequest($request);
        $pageConfig = $this->getPaginationAttributes();




        $offset = ($pageConfig['number'] - 1) * $pageConfig['size'];
        $object = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findBy([], [], $pageConfig['size'], $offset);

        return $object;
    }

    abstract protected function getEncoder();
}
