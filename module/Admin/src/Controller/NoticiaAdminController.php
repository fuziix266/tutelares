<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Noticia\Model\NoticiaTable;

class NoticiaAdminController extends AbstractActionController
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
        $vm->setTemplate('admin/noticia/index');
        $this->layout('layout/admin');
        return $vm;
    }

    public function crearAction()
    {
        $this->checkAuth();
        $error = null;

        if ($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            if (empty(trim($data['titulo'] ?? ''))) {
                $error = 'El título es obligatorio.';
            } else {
                $this->noticiaTable->insert($data);
                return $this->redirect()->toRoute('admin-noticia');
            }
        }

        $vm = new ViewModel(['error' => $error]);
        $vm->setTemplate('admin/noticia/form');
        $this->layout('layout/admin');
        return $vm;
    }

    public function editarAction()
    {
        $this->checkAuth();
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin-noticia');
        }

        $noticia = $this->noticiaTable->getById($id);
        $error   = null;

        if ($this->getRequest()->isPost()) {
            $data = (array) $this->getRequest()->getPost();
            if (empty(trim($data['titulo'] ?? ''))) {
                $error = 'El título es obligatorio.';
            } else {
                $this->noticiaTable->update($id, $data);
                return $this->redirect()->toRoute('admin-noticia');
            }
        }

        $vm = new ViewModel(['noticia' => $noticia, 'error' => $error]);
        $vm->setTemplate('admin/noticia/form');
        $this->layout('layout/admin');
        return $vm;
    }

    public function eliminarAction()
    {
        $this->checkAuth();
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id) {
            $this->noticiaTable->delete($id);
        }
        return $this->redirect()->toRoute('admin-noticia');
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
