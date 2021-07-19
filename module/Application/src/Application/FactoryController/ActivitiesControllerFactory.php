<?php

namespace Application\FactoryController;

use Application\Controller\ActivitiesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ActivitiesControllerFactory
 * @package Application\FactoryController
 */
class ActivitiesControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();
        $logger = $parentLocator->get("logger");
        $activityService = $parentLocator->get("Application\Service\ActivityService");
        return new ActivitiesController($logger, $activityService);
    }

}