<?php
namespace Noticia\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class NoticiaController extends AbstractActionController
{
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }
}
