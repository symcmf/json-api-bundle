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
     * Return attributes for POST and PUT methods
     *
     * @return array
     */
    public function getFormAttributes()
    {
        return $this->request->request->all();
    }
}
