<?php

namespace JsonBundle\Controller;

use JsonBundle\Services\BaseJSONApiBundle;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package JsonBundle\Controller
 */
abstract class BaseController extends Controller
{
    protected $link = 'http://example.com/api';

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
     * @return object
     */
    protected function getJsonRequest()
    {
        return $this->get('jsonapi.request');
    }

    /**
     * @return EncodingParameters
     */
    private function getEncodingParameters()
    {
        $params = new EncodingParameters(
            $this->getJsonRequest()->getIncludeAttributes(),
            $this->getJsonRequest()->getSparseFieldAttributes(),
            $this->getJsonRequest()->getSortAttributes(),
            $this->getJsonRequest()->getPaginationAttributes()
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
     * @param $object
     *
     * @return mixed
     */
    protected function viewObject($object)
    {
        return $this->getEncoder()->encodeData($object, $this->getEncodingParameters());
    }


    private function generateArrayOfPageLinks($page, $total)
    {

    }

    /**
     *
     * @return Response
     */
    protected function getList()
    {
        $objects = $this->getBaseService()->getQuery(
            $this->getClass(),
            $this->getJsonRequest()->getPaginationAttributes()
        );

        return $this->createResponse($this->viewObject($objects), Response::HTTP_OK);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    protected function getEntity($id)
    {
        $object = $this->getBaseService()->getObject($id, $this->getClass());

        return $this->createResponse($this->viewObject($object), Response::HTTP_OK);
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
     * @return Response
     */
    protected function postEntity()
    {
        $errorEncoder = $this->checkIdField($this->getJsonRequest()->getDataSection());

        // if catch error
        if ($errorEncoder) {
            return $this->createResponse($errorEncoder, Response::HTTP_FORBIDDEN);
        }

        $object = $this->getHydrator()->setValues(
            $this->getJsonRequest()->getDataAttributes(),
            $this->getJsonRequest()->getRelationSection());

        // TODO uncomment after debug
//        $this->get('jsonapi.base.service')->saveObject($object);

        return $this->createResponse($this->viewObject($object), Response::HTTP_CREATED);
    }

    /**
     * @param $id
     *
     * @return Response
     */
    protected function putEntity($id)
    {
        if (!$this->getBaseService()->getObject($id, $this->getClass())) {
            $this->createResponse($this->viewObject([]), Response::HTTP_NOT_FOUND);
        }

        $object = $this->getHydrator()->updateValues(
            $this->getJsonRequest()->getDataSection(),
            $this->getJsonRequest()->getRelationSection()
        );
        // TODO uncomment after debug
//        $this->getBaseService()->updateObject($object);

        return $this->createResponse($this->viewObject($object), Response::HTTP_OK);
    }
}
