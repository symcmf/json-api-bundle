<?php

namespace JsonBundle\Services\Validator;

abstract class AbstractValidator
{
    protected function getAttributeRules()
    {
        return [];
    }
}