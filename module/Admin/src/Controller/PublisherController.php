<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Admin\Model\NoticiaTable;

class PublisherController extends AbstractActionController
{
    private NoticiaTable $noticiaTable;

    public function __construct(NoticiaTable $noticiaTable)
    {
        $this->noticiaTable = $noticiaTable;
    }

    public function indexAction()
    {
        $this->checkRole('publisher');
        $noticias = $this->noticiaTable->fetchAll();
        $vm = new ViewModel(['noticias' => $noticias]);
        $vm->setTemplate('admin/publisher/index');
        $this->layout('layout/admin');
        return $vm;
    }

    public function crearAction()
    {
        $this->checkRole('publisher');
        $error = null;

        if ($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            if (empty(trim($data['titulo'] ?? ''))) {
                $error = 'El título es obligatorio.';
            } else {
                $this->noticiaTable->insert($data);
                return $this->redirect()->toRoute('admin-publisher');
            }
        }

        $vm = new ViewModel(['error' => $error]);
        $vm->setTemplate('admin/noticia/form');
        $this->layout('layout/admin');
        return $vm;
    }

    public function editarAction()
    {
        $this->checkRole('publisher');
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) return $this->redirect()->toRoute('admin-publisher');

        $noticia = $this->noticiaTable->getById($id);
        $error   = null;

        if ($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            if (empty(trim($data['titulo'] ?? ''))) {
                $error = 'El título es obligatorio.';
            } else {
                $this->noticiaTable->update($id, $data);
                return $this->redirect()->toRoute('admin-publisher');
            }
        }

        $vm = new ViewModel(['noticia' => $noticia, 'error' => $error]);
        $vm->setTemplate('admin/noticia/form');
        $this->layout('layout/admin');
        return $vm;
    }

    public function eliminarAction()
    {
        $this->checkRole('publisher');
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id) {
            $this->noticiaTable->delete($id);
        }
        return $this->redirect()->toRoute('admin-publisher');
    }

    private function checkRole(string $required): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $role = $_SESSION['admin_role'] ?? '';
        // Super usuario puede acceder a todo
        if ($role === 'super') return;
        if ($role !== $required) {
            $this->redirect()->toRoute('admin-login')->send();
            exit;
        }
    }
}
