<?php

namespace JsonBundle\Services;

use Neomerx\JsonApi\Document\Error;

class JSONApiError extends Error
{
    public function getBadRequestError($message, $source)
    {

    }
}
