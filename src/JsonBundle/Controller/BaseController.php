<?php

namespace JsonBundle\Controller;

use AppBundle\Entity\Category;
use JsonBundle\Request\RequestTrait;
use JsonBundle\Services\BaseJSONApiBundle;
use JsonBundle\Services\Validator\Validator;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package JsonBundle\Controller
 */
abstract class BaseController extends Controller
{

    use RequestTrait;

    /**
     * @return string
     */
    abstract public function getClass();

    /**
     * @return mixed
     */
    abstract protected function getEncoder();

    /**
     * @return object
     */
    abstract protected function getHydrator();

    /**
     * @return BaseJSONApiBundle
     */
    protected function getBaseService()
    {
        return $this->get('jsonapi.base.service');
    }



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
     * @param $content
     * @param $code
     *
     * @return Response
     */
    protected function createResponse($content, $code)
    {
        $response = new Response();

        $response->setContent($content);
        $response->setStatusCode($code);

        $response->headers->set('Content-Type', 'application/vnd.api+json');

        return $response;
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

    /**
     * @param Request $request
     *
     * @return mixed
     */
    protected function getList(Request $request)
    {
        $this->setRequest($request);

        return $this
            ->get('jsonapi.base.service')
            ->getQuery($this->getClass(), $this->getPaginationAttributes());
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return object|null
     */
    protected function getEntity($id, Request $request)
    {
        $this->setRequest($request);

        return $this
            ->get('jsonapi.base.service')
            ->getObject($id, $this->getClass());
    }

    /**
     * @param $data - json api array (data section)
     *
     * @return Response
     */
    private function checkIdField($data)
    {
        // TODO need to user translations for errors

        if (array_key_exists('id', $data)) {
            $error = new Error(
                null,
                null,
                'Forbidden',
                Response::HTTP_FORBIDDEN,
                'Unsupported request',
                'Unsupported request to create a resource with a client-generated ID',
                ['source' => 'data/id'],
                null
            );

            return Encoder::instance()->encodeError($error);
        }
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function postEntity(Request $request)
    {
        $this->setRequest($request);

        /** @var Validator $validator */
        $validator = $this->get('jsonapi.validator');

        $validator->validate(
            $this->getDataAttributes(),
            $this->getRelationSection(),
            $this->getType()
        );


        $errorEncoder = $this->checkIdField($this->getDataSection());

        // if catch error
        if ($errorEncoder) {
            return $this->createResponse($errorEncoder, Response::HTTP_FORBIDDEN);
        }

        $object = $this->getHydrator()->setValues($this->getDataAttributes(), $this->getRelationSection());

        // TODO uncomment after debug
//        $this->get('jsonapi.base.service')->saveObject($object);

        return $this->createResponse($this->viewObject($request, $object), Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    protected function putEntity($id, Request $request)
    {
        $this->setRequest($request);

        if (!$this->getBaseService()->getObject($id, $this->getClass())) {
            $this->createResponse($this->viewObject($request, new Category()), Response::HTTP_NOT_FOUND);
        }

        $object = $this->getHydrator()->updateValues($this->getDataSection(), $this->getRelationSection());

        return $this->createResponse($this->viewObject($request, $object), Response::HTTP_OK);
    }
}
