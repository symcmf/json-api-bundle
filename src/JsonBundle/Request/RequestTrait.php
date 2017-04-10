<?php

namespace JsonBundle\Request;

use Symfony\Component\HttpFoundation\Request;

trait RequestTrait
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $contentType = 'application/vnd.api+json';

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $separator
     * @param $request
     *
     * @return array
     */
    private function getArraySeparator($separator, $request)
    {
        $requestString = str_replace(' ', '', $request);
        return explode($separator, $requestString);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * Return attributes for sort action
     *
     * @return array
     */
    public function getSortAttributes()
    {
        return $this->getArraySeparator(',', $this->request->query->get('sort'));
    }

    /**
     * @return array
     */
    public function getSparseFieldAttributes()
    {
        $result = [];
        $fields = $this->request->get('fields');

        if ($fields) {
            foreach ($fields as $key => $value) {
                $result[$key] = $this->getArraySeparator(',', $value);
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getIncludeAttributes()
    {
        return $this->getArraySeparator(',', $this->request->query->get('include'));
    }

    /**
     * @return array
     */
    public function getPaginationAttributes()
    {
        return $this->request->query->get('page');
    }

    /**
     * @return array
     */
    private function parseJson()
    {
        $data = [];

        if ($this->request->getMethod() == 'POST' ||
            $this->request->getMethod() == 'PUT') {

            if ($this->request->headers->get('content-type') == $this->contentType) {
                $data = json_decode($this->request->getContent(), true);
                // TODO check correct format of request json
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getDataSection()
    {
        $data = $this->parseJson();
        return (!empty($data)) ? $data['data'] : [];
    }

    /**
     * @return array
     */
    public function getDataAttributes()
    {
        $data = $this->parseJson();

        if (array_key_exists('attributes', $data['data'])) {
            return (!empty($data)) ? $data['data']['attributes'] : [];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getRelationSection()
    {
        $data = $this->parseJson();

        if (array_key_exists('relationships', $data['data'])) {
            return (!empty($data)) ? $data['data']['relationships'] : [];
        }

        return [];
    }
}
