<?php

namespace Application\Controller;

use Application\Contracts\Service\ActivityServiceInterface;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class ActivitiesController
 * @package Application\Controller
 */
class ActivitiesController extends AbstractActionController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $activityService;

    /**
     * ActivitiesController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, ActivityServiceInterface $activityService)
    {
        $this->logger = $logger;
        $this->activityService = $activityService;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->layout()->setVariable('jsModule', 'activities');
        return new ViewModel();
    }

    /**
     * @return JsonModel
     */
    public function getIndicatorsAction()
    {
        return new JsonModel($this->activityService->getIndicators());
    }

    /**
     * @return ViewModel
     */
    public function addAction()
    {
        $this->layout()->setVariable('jsModule', 'activities');
        $activity = [];
        $viewModel = new ViewModel(array('action' => "add", 'activity' => $activity));
        $viewModel->setTemplate('application/activities/add.phtml');
        return $viewModel;
    }

    public function getListActivitiesPaginatedAction()
    {
        $queryStrings = $this->params()->fromQuery();
        $data = $this->activityService->getGridActivities($queryStrings);
        return new JsonModel($data);
    }

    public function getListActivitiesAction()
    {
        $data = $this->activityService->getListActivities();
        return new JsonModel($data);
    }

    public function showAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');;
        $data = $this->activityService->show($id);
        //return new JsonModel($data);
        $activity = (empty($data)) ? [] : $data['body']['data'][0];

        $this->layout()->setVariable('jsModule', 'activities');
        $viewModel = new ViewModel(array('action' => "show", 'activity' => $activity));
        $viewModel->setTemplate('application/activities/add.phtml');
        return $viewModel;
    }
}