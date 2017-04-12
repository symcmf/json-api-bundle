<?php

namespace JsonBundle\Category;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
            'name' => [
                new Length(['min' => 10]),
                new NotBlank(),

            ],
            'description' => [
                new Length(['min' => 10]),
                new NotBlank()
            ],
        ];
    }
}
