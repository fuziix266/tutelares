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

    public function verAction(): \Laminas\View\Model\ViewModel|\Laminas\Http\Response
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('home');
        }

        $noticia = $this->noticiaTable->getById($id);
        if (!$noticia) {
            return $this->redirect()->toRoute('home');
        }

        return new ViewModel(['noticia' => $noticia]);
    }
}
