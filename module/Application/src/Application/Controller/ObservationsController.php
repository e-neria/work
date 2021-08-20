<?php

namespace Application\Controller;

use Application\Contracts\Service\ObservationServiceInterface;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class ObservationsController extends AbstractActionController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ObservationServiceInterface
     */
    private $observationService;

    /**
     * @param LoggerInterface $logger
     * @param ObservationServiceInterface $observationService
     */
    public function __construct(LoggerInterface $logger, ObservationServiceInterface $observationService)
    {
        $this->logger = $logger;
        $this->observationService = $observationService;
    }

    public function indexAction()
    {
        $activityId = isset($_POST['activityId']) ? $_POST['activityId'] : null;
        return new JsonModel($this->observationService->index($activityId));
    }

    public function addAction()
    {
        $payload = $this->params()->fromPost();
        $data = $this->observationService->add($payload);

        $observations = [];
        if(!empty($payload['activityId'])){
            $observations = $this->observationService->index($payload['activityId']);
        }

        return new JsonModel(['data' => $data, 'observations' => $observations]);
    }
}