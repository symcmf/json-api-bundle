<?php

namespace JsonBundle\Listeners;

use JsonBundle\Controller\BaseController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RequestListener constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) // this is @service_container
    {
        $this->container = $container;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof BaseController) {

            /** @var \JsonBundle\Request\JSONApiRequest $jsonApiRequest */
            $jsonApiRequest =  $this->container->get('jsonapi.request');

            $validator = $this->container->get('jsonapi.validator');

            return $validator->validate(
                $jsonApiRequest->getDataAttributes(),
                $jsonApiRequest->getRelationSection(),
                $jsonApiRequest->getClassNameByType()
            );
        }
    }
}