<?php

namespace JsonBundle\Article;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class Validators
{
    /**
     * Array format: attributeName => validationRule
     *
     * @return array
     */
    public function getAttributeRules()
    {
        return [
            'title' => [
                new Length(['min' => 10]),
                new NotBlank()
            ],
            'description' => [
                new Length(['min' => 10]),
                new NotBlank()
            ],
        ];
    }
}