<?php

namespace JsonBundle\Services\Validator;

use AppBundle\Entity\Category;
use JsonBundle\Category\Hydrator;
use JsonBundle\Category\Validators;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Validation;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Document\Link;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class Validator extends AbstractValidator
{

    protected $requestAttributes;

    protected $validator;

    protected $entity;


//    public function __construct(Request $request)
    public function __construct($validator)
    {
        $this->validator = $validator;

//        $this->requestAttributes = $request->getAttributes();
//        $this->requestAttributes = [
//            'title' => 'qweqw',
//            'description' => '',
//        ];
//        $this->validator = Validation::createValidator();
//        $this->entity = $request->getType();
    }



    /**
     * @return array
     */
    protected function getValidatorAttributes()
    {
        // TODO: нужно генерить димамически класс

        $class = 'JsonBundle\\' . $this->entity . '\\Validators';
        $object = new $class;

        /** @var Validators $object */
        return $object->getAttributeRules();
    }

    /**
     * @return array
     */
    protected function getHydratorAttributes()
    {
        $class = 'JsonBundle\\' . $this->entity . '\\Hydrator';
        $object = new $class;

        /** @var Hydrator $object */
        return $object->getAttributes();
    }

   public function validate($requestAttributes, $relationAttributes, $type)
   {
//       $this->requestAttributes = $requestAttributes;

//       $this->requestAttributes = [
//           'title' => 'qweqw',
//           'description' => '',
//       ];

//       $this->validator = Validation::createValidator();
       $this->entity = $type;

//       $validator = Validation::createValidatorBuilder()
//           ->enableAnnotationMapping()
//           ->getValidator();

       $object = new Category();
       $object->setName('test');
//
       $errors = $this->validator->validate($object);
//
       if (count($errors) !== 0)
       {
           foreach ($errors as $error) {
                var_dump($error->getMessage());
           }
       }

       die();

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

       return Encoder::instance()->encodeErrors($errors);
//       dump(Encoder::instance()->encodeErrors($errors));
   }
}