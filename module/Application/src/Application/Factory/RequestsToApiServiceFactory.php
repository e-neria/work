<?php

namespace Application\Factory;

use Application\Service\RequestsToApiService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RequestsToApiServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RequestsToApiService();
    }

}