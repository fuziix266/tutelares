<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\NoticiaTable;

class SuperController extends AbstractActionController
{
    private NoticiaTable $noticiaTable;

    public function __construct(NoticiaTable $noticiaTable)
    {
        $this->noticiaTable = $noticiaTable;
    }

    public function indexAction()
    {
        $this->checkRole('super');
        $noticias = $this->noticiaTable->fetchAll();
        $vm = new ViewModel(['noticias' => $noticias, 'totalNoticias' => count($noticias)]);
        $vm->setTemplate('admin/super/index');
        $this->layout('layout/admin');
        return $vm;
    }

    private function checkRole(string $required): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (($_SESSION['admin_role'] ?? '') !== $required) {
            $this->redirect()->toRoute('admin-login')->send();
            exit;
        }
    }
}
