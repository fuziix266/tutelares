<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\NoticiaTable;

class IndexController extends AbstractActionController
{
    private NoticiaTable $noticiaTable;

    public function __construct(NoticiaTable $noticiaTable)
    {
        $this->noticiaTable = $noticiaTable;
    }

    public function indexAction()
    {
        $this->checkAuth();
        $noticias = $this->noticiaTable->fetchAll();
        $vm = new ViewModel(['noticias' => $noticias]);
        $vm->setTemplate('admin/index/index');
        $this->layout('layout/admin');
        return $vm;
    }

    private function checkAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['admin_logged'])) {
            $this->redirect()->toRoute('admin-login')->send();
            exit;
        }
    }
}
