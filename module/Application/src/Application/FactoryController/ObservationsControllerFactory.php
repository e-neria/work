<?php

namespace Application\FactoryController;

use Application\Controller\ObservationsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ObservationsControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();
        $logger = $parentLocator->get("logger");
        $observationService = $parentLocator->get("Application\Service\ObservationService");
        return new ObservationsController($logger, $observationService);
    }
}