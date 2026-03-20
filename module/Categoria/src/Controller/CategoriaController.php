<?php
namespace Categoria\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CategoriaController extends AbstractActionController
{
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }
}
