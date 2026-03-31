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
            
            // Garantizar valores booleanos
            $data['fijada'] = isset($data['fijada']) ? 1 : 0;

            if (empty(trim($data['titulo'] ?? ''))) {
                $error = 'El título es obligatorio.';
            } else {
                $this->noticiaTable->insert($data);
                return $this->redirect()->toRoute('admin-noticia');
            }
        }

        $vm = new ViewModel([
            'error' => $error,
            'fijadaActual' => $this->noticiaTable->getUltimaHora()
        ]);
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

            // Garantizar valores booleanos
            $data['fijada'] = isset($data['fijada']) ? 1 : 0;

            if (empty(trim($data['titulo'] ?? ''))) {
                $error = 'El título es obligatorio.';
            } else {
                $this->noticiaTable->update($id, $data);
                
                // Redirección inteligente basada en el rol del usuario
                if (session_status() === PHP_SESSION_NONE) session_start();
                $route = (($_SESSION['admin_role'] ?? '') === 'publisher') ? 'admin-publisher' : 'admin-noticia';
                
                return $this->redirect()->toRoute($route);
            }
        }

        $vm = new ViewModel([
            'noticia' => $noticia, 
            'error' => $error,
            'fijadaActual' => $this->noticiaTable->getUltimaHora()
        ]);
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
