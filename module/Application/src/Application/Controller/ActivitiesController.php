<?php

namespace Application\Controller;

use Application\Contracts\Service\ActivityServiceInterface;
use Application\Form\ActivityForm;
use Application\Form\ObservationForm;
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
        $form = new ActivityForm("ActivityForm");
        $form->setData(['formAction'=>"add"]);

        $response['activity'] = [];
        $response['action'] = "add";
        $response['form'] = $form;

        $viewModel = new ViewModel($response);
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
        $activity = (empty($data)) ? [] : $data;

        if(empty($activity)){
            $this->flashMessenger()->setNamespace('error')->addMessage("No se ha encontrado la actividad con el id especificado.");
            return $this->redirect()->toRoute('activities', ['action' => 'index']);
        }

        $activity['formAction'] = "show";

        $form = new ActivityForm("ActivityForm");
        $form->setData($activity);

        $response['activity'] = $activity;
        $response['action'] = "show";
        $response['form'] = $form;

        $this->layout()->setVariable('jsModule', 'activities');
        $viewModel = new ViewModel($response);
        $viewModel->setTemplate('application/activities/add.phtml');
        return $viewModel;
    }

    public function updateAction()
    {
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
        $data = $this->activityService->show($id);
        $activity = (empty($data)) ? [] : $data;

        if(empty($activity)){
            $this->flashMessenger()->setNamespace('error')->addMessage("No se ha encontrado la actividad con el id especificado.");
            return $this->redirect()->toRoute('activities', ['action' => 'index']);
        }

        $activity['formAction'] = "update";
        $form = new ActivityForm("ActivityForm");
        $form->setData($activity);

        $response['activity'] = $activity;
        $response['action'] = "update";
        $response['form'] = $form;
        $response['observationForm'] = new ObservationForm("ObservationForm");

        $this->layout()->setVariable('jsModule', 'activities');
        $viewModel = new ViewModel($response);
        $viewModel->setTemplate('application/activities/add.phtml');
        return $viewModel;
    }

    public function saveAction()
    {
        $payload = $this->params()->fromPost();
        $response = $this->activityService->save($payload);

        $message = ($payload['formAction'] == "add") ? "guardar" : "actualizar";

        if($response['code'] == 500){
            $this->flashMessenger()->setNamespace('error')->addMessage("Ocurrio un error al " . $message . " la actividad");
        }else if($response['code'] == 200 || $response['code'] == 201){
            $message = $response['body']['message'];
            $this->flashMessenger()->setNamespace('success')->addMessage($message);
        }

        return $this->redirect()->toRoute('activities', ['action' => 'index']);
    }

}