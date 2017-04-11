<?php

namespace JsonBundle\Services\Validator;

use JsonBundle\Category\Hydrator;
use JsonBundle\Category\Validators;
use Symfony\Component\Validator\Validation;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Document\Link;

class Validator extends AbstractValidator
{

    protected $requestAttributes;

    protected $validator;

    protected $entity;


//    public function __construct(Request $request)
    public function __construct()
    {
//        $this->requestAttributes = $request->getAttributes();
        $this->requestAttributes = [
            'title' => 'qweqw',
            'description' => '',
        ];
        $this->validator = Validation::createValidator();
//        $this->entity = $request->getType();
    }



    /**
     * @return array
     */
    protected function getValidatorAttributes()
    {
        // TODO: нужно генерить димамически класс

        $class = '\\JsonBundle\\' . $this->entity . '\\Validators';
        $object = (new $class);

        /** @var Validators $object */
        return $object->getAttributeRules();
    }

    /**
     * @return array
     */
    protected function getHydratorAttributes()
    {
        $class = '\\JsonBundle\\' . $this->entity . '\\Hydrator';
        $object = (new $class);

        /** @var Hydrator */
        return $object->getAttributes();
    }

   public function validate($requestAttributes, $relationAttributes, $type)
   {
       $this->requestAttributes = $requestAttributes;

//       $this->requestAttributes = [
//           'title' => 'qweqw',
//           'description' => '',
//       ];

       $this->validator = Validation::createValidator();
       $this->entity = $type;




       $errors = [];

       foreach ($this->getValidatorAttributes() as $fieldName => $rule) {
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
                           ['some' => 'meta']
                       );
                   }

               }
           }
       }

//       return Encoder::instance()->encodeErrors($errors);
       dump(Encoder::instance()->encodeErrors($errors));
   }
}