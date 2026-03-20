<?php
namespace Radio\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RadioController extends AbstractActionController
{
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }
}
