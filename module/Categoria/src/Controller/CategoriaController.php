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

        $labels = [
            'Politica'      => 'Política',
            'Economia'      => 'Economía',
            'Deportes'      => 'Deportes',
            'Tecnologia'    => 'Tecnología',
            'Cultura'       => 'Cultura',
            'Sociedad'      => 'Sociedad',
            'Internacional' => 'Internacional',
            'Salud'         => 'Salud',
            'Religion'      => 'Religión',
            'Policial'      => 'Policial'
        ];
        $nombre = $labels[$id] ?? $id;

        $noticias = $this->table->fetchByCategory($id);
        return new ViewModel([
            'categoria' => $nombre,
            'noticias' => $noticias
        ]);
    }
}
