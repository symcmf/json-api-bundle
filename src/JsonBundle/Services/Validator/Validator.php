<?php

namespace JsonBundle\Services\Validator;

use Symfony\Component\Validator\Validation;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Document\Link;

class Validator extends AbstractValidator
{
    protected $requestAttributes;
    protected $validator;

//    public function __construct(Request $request)
    public function __construct()
    {
//        $this->requestAttributes = $request->getAttributes();
        $this->requestAttributes = [
            'title' => 'qweqw',
            'description' => '',
        ];
        $this->validator = Validation::createValidator();
    }

   protected function getAttributeRules()
   {
       // TODO: нужно генерить димамически класс
       return (new \JsonBundle\Article\Validators)->getAttributeRules();
   }

   public function validate()
   {
       $errors = [];

       foreach ($this->getAttributeRules() as $fieldName => $rule) {
           if (array_key_exists($fieldName, $this->requestAttributes)) {

               $violations = $this->validator->validate($this->requestAttributes[$fieldName], $rule);

               if (count($violations) !== 0) {

                   foreach ($violations as $violation) {
                       $errors[] = new Error(
                           'some-id',
                           new Link('about-link'),
                           'some-status',
                           'some-code',
                           'some-title',
                           $violation->getMessage(),
                           ['source' => 'data'],
                           ['some'   => 'meta']
                       );
                   }

               }
           }
       }

//       return Encoder::instance()->encodeErrors($errors);
       dump(Encoder::instance()->encodeErrors($errors));
   }
}