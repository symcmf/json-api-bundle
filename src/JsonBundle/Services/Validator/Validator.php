<?php

namespace JsonBundle\Services\Validator;

use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\ConstraintViolation;
use Doctrine\ORM\EntityManager;
use JsonBundle\Category\Hydrator;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Document\Link;

class Validator extends AbstractValidator
{
    const ENTITY_NAMESPACE = 'AppBundle\Entity\\';

    protected $requestAttributes;

    protected $validator;

    protected $entityManager;

    /**
     * Validator constructor.
     * @param RecursiveValidator $validator
     * @param EntityManager $entityManager
     */
    public function __construct($validator, $entityManager)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $className
     * @return object
     */
    protected function getEntity($className)
    {
        $class = Validator::ENTITY_NAMESPACE . $className;

        return new $class;
    }

    /**
     * @param string $type
     * @return object
     */
    protected function getHydrator($type)
    {
        $class = 'JsonBundle\\' . $type . '\\Hydrator';

        return new $class($this->entityManager);
    }

    /**
     * @param array $requestAttributes
     * @param array $relationAttributes
     * @param string $type
     * @return bool|string
     */
     public function validate($requestAttributes, $relationAttributes, $type)
    {
        /** @var Hydrator $hydrator */
        $hydrator = $this->getHydrator($type);

        $object = $this->getEntity($type);

        foreach ($hydrator->getAttributes() as $fieldName) {
            if (array_key_exists($fieldName, $requestAttributes)) {

                /** @var Hydrator $hydrator */
                $hydrator->setParamsToObject($object, $requestAttributes, $hydrator->getAttributes());
            }
        }
        $violations = $this->validator->validate($object);

        $errors = [];

        if (count($violations) !== 0) {
            /** @var ConstraintViolation $violation*/
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

        return ($violations) ? Encoder::instance()->encodeErrors($errors) : true;
    }
}