<?php

namespace Application\Factory;

use Application\Service\ActivityService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ActivityServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get("config");
        $requestsToApiService = $serviceLocator->get("Application\Service\RequestsToApiService");
        $productivityApp = $config['productivityApp'];
        return new ActivityService($requestsToApiService, $productivityApp);
    }

}