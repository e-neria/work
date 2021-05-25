<?php

namespace Application\Controller;

use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\AbstractActionController;
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

    /**
     * ActivitiesController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
     * @return ViewModel
     */
    public function addAction()
    {
        $this->layout()->setVariable('jsModule', 'activities');
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/activities/add.phtml');
        return $viewModel;
    }
}