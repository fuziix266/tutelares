<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RadioAdminController extends AbstractActionController
{
    public function indexAction()
    {
        $this->checkRole('radio');
        $vm = new ViewModel();
        $vm->setTemplate('admin/radio/index');
        $this->layout('layout/admin');
        return $vm;
    }

    private function checkRole(string $required): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $role = $_SESSION['admin_role'] ?? '';
        if ($role === 'super') return;
        if ($role !== $required) {
            $this->redirect()->toRoute('admin-login')->send();
            exit;
        }
    }
}
