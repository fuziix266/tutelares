<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private \Noticia\Model\NoticiaTable $noticiaTable;

    public function __construct(\Noticia\Model\NoticiaTable $noticiaTable)
    {
        $this->noticiaTable = $noticiaTable;
    }

    public function indexAction()
    {
        $noticias = $this->noticiaTable->fetchAll();
        return new ViewModel(['noticias' => $noticias]);
    }
}
