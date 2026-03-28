<?php
namespace Categoria\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CategoriaController extends AbstractActionController
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        if (!$id) {
            return new ViewModel();
        }

        $noticias = $this->table->fetchByCategory($id);
        return new ViewModel([
            'categoria' => $id,
            'noticias' => $noticias
        ]);
    }
}
