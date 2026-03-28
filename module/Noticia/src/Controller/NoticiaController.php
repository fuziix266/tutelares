<?php
namespace Noticia\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class NoticiaController extends AbstractActionController
{
    private \Noticia\Model\NoticiaTable $noticiaTable;

    public function __construct(\Noticia\Model\NoticiaTable $noticiaTable)
    {
        $this->noticiaTable = $noticiaTable;
    }

    public function indexAction(): ViewModel
    {
        $noticias = $this->noticiaTable->fetchAll();
        return new ViewModel(['noticias' => $noticias]);
    }
}
