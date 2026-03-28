<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Noticia\Model\NoticiaTable;

class SuperController extends AbstractActionController
{
    private NoticiaTable $noticiaTable;
    private \Admin\Model\UsuarioTable $usuarioTable;
    private \Admin\Model\ConfigTable $configTable;

    public function __construct(NoticiaTable $noticiaTable, \Admin\Model\UsuarioTable $usuarioTable, \Admin\Model\ConfigTable $configTable)
    {
        $this->noticiaTable = $noticiaTable;
        $this->usuarioTable = $usuarioTable;
        $this->configTable = $configTable;
    }

    public function indexAction()
    {
        $this->checkRole('super');
        $noticias = $this->noticiaTable->fetchAll();
        $usuarios = $this->usuarioTable->fetchAll();
        $portalStatus = $this->configTable->get('portal_status', 'activo');
        
        $vm = new ViewModel([
            'noticias'      => $noticias, 
            'totalNoticias' => count($noticias),
            'usuarios'      => $usuarios,
            'totalUsuarios' => count($usuarios),
            'portalStatus'  => $portalStatus
        ]);
        $vm->setTemplate('admin/super/index');
        $this->layout('layout/admin');
        return $vm;
    }

    public function toggleStatusAction()
    {
        $this->checkRole('super');
        $current = $this->configTable->get('portal_status', 'activo');
        $new = $current === 'activo' ? 'standby' : 'activo';
        $this->configTable->set('portal_status', $new);
        return $this->redirect()->toRoute('admin-super');
    }

    public function estadoAction()
    {
        $this->checkRole('super');
        $status  = $this->configTable->get('portal_status', 'activo');
        $message = $this->configTable->get('portal_message', 'El portal está en mantenimiento.');

        $vm = new ViewModel([
            'status'  => $status,
            'message' => $message,
        ]);
        $vm->setTemplate('admin/super/estado');
        $this->layout('layout/admin');
        return $vm;
    }

    public function saveEstadoAction()
    {
        $this->checkRole('super');
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()->toRoute('admin-super', ['action' => 'estado']);
        }
        $data = $request->getPost();
        $message = trim($data['message'] ?? '');
        if ($message !== '') {
            $this->configTable->set('portal_message', $message);
        }
        // Opcional: también permitir cambiar estado desde aquí
        if (isset($data['status'])) {
            $newStatus = $data['status'] === 'activo' ? 'activo' : 'standby';
            $this->configTable->set('portal_status', $newStatus);
        }
        return $this->redirect()->toRoute('admin-super', ['action' => 'estado']);
    }

    public function usuariosAction()
    {
        $this->checkRole('super');
        $usuarios = $this->usuarioTable->fetchAll();
        $vm = new ViewModel(['usuarios' => $usuarios]);
        $vm->setTemplate('admin/super/usuarios');
        $this->layout('layout/admin');
        return $vm;
    }

    public function saveUsuarioAction()
    {
        $this->checkRole('super');
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            $this->usuarioTable->save($data);
            return $this->redirect()->toRoute('admin-super');
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        $usuario = $id > 0 ? $this->usuarioTable->getUsuario($id) : null;
        
        $vm = new ViewModel(['usuario' => $usuario]);
        $vm->setTemplate('admin/super/usuario-form');
        $this->layout('layout/admin');
        return $vm;
    }

    public function deleteUsuarioAction()
    {
        $this->checkRole('super');
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 1) { // Evitar borrar al super inicial
            $this->usuarioTable->delete($id);
        }
        return $this->redirect()->toRoute('admin-super');
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
