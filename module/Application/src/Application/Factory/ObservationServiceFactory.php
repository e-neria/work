<?php

namespace Application\Factory;

use Application\Service\ObservationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ObservationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get("config");
        $requestsToApiService = $serviceLocator->get("Application\Service\RequestsToApiService");
        $productivityApp = $config['productivityApp'];
        return new ObservationService($requestsToApiService, $productivityApp);
    }
}