<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Noticia\Model\NoticiaTable;

class PublisherController extends AbstractActionController
{
    private NoticiaTable $noticiaTable;
    private \Admin\Service\SocialMediaService $socialMediaService;

    public function __construct(NoticiaTable $noticiaTable, \Admin\Service\SocialMediaService $socialMediaService)
    {
        $this->noticiaTable = $noticiaTable;
        $this->socialMediaService = $socialMediaService;
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

                // --- PUBLICAR EN REDES SOCIALES ---
                $title   = $data['titulo'];
                $summary = $data['resumen'] ?? substr(strip_tags($data['contenido'] ?? ''), 0, 200);
                $image   = $data['imagen'] ?? null;

                if (!empty($data['post_facebook'])) {
                    $this->socialMediaService->postToFacebook($title, $summary, $image);
                }

                if (!empty($data['post_instagram']) && $image) {
                    $this->socialMediaService->postToInstagram($title, $summary, $image);
                }
                // ----------------------------------

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
