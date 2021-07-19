<?php

namespace Application\Factory;

use Zend\Log\Filter\Priority;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $logger = new Logger();
        $priority = "ERR";
        $config = $serviceLocator->get("config");
        if (key_exists('logger', $config)) {
            if (key_exists('stream', $config['logger'])) {
                $stream = $config['logger']['stream'];
                $writer = new Stream($stream);
                $logger->addWriter($writer);
                if (key_exists('priorityFilter', $config['logger'])) {
                    $priority = $config['logger']['priorityFilter'];
                }
                switch ($priority){
                    case 'EMERG':
                        $realPriority = Logger::EMERG;
                        break;
                    case 'ALERT':
                        $realPriority = Logger::ALERT;
                        break;
                    case 'CRIT':
                        $realPriority = Logger::CRIT;
                        break;
                    case 'ERR':
                        $realPriority = Logger::ERR;
                        break;
                    case 'WARN':
                        $realPriority = Logger::WARN;
                        break;
                    case 'NOTICE':
                        $realPriority = Logger::NOTICE;
                        break;
                    case 'INFO':
                        $realPriority = Logger::INFO;
                        break;
                    case 'DEBUG':
                    default:
                        $realPriority = Logger::DEBUG;
                        break;
                }
                $filter = new Priority($realPriority);
                $writer->addFilter($filter);
            }
        }
        return $logger;
    }

}