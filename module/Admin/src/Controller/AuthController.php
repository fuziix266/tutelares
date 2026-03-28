<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    private \Admin\Model\UsuarioTable $usuarioTable;

    public function __construct(\Admin\Model\UsuarioTable $usuarioTable)
    {
        $this->usuarioTable = $usuarioTable;
    }

    /**
     * Fallback de emergencia si la DB está vacía o inaccesible.
     */
    private const FALLBACK_USUARIOS = [
        'SUPERUSUARIO' => [
            'password' => 'PASSWORD',
            'role'     => 'super',
            'redirect' => 'admin-super',
        ]
    ];

    public function loginAction()
    {
        $this->startSession();

        if (!empty($_SESSION['admin_role'])) {
            return $this->redirect()->toRoute($this->getRoleRoute($_SESSION['admin_role']));
        }

        $error = null;

        if ($this->getRequest()->isPost()) {
            $data     = $this->getRequest()->getPost();
            $usuarioInput  = trim($data['usuario'] ?? '');
            $usuarioUpper  = strtoupper($usuarioInput);
            $password = $data['password'] ?? '';

            // 1. Intentar buscar en DB
            $dbUser = $this->usuarioTable->getUsuarioByName($usuarioInput);
            if ($dbUser && $dbUser['password'] === $password) {
                $_SESSION['admin_logged']   = true;
                $_SESSION['admin_usuario']  = $dbUser['usuario'];
                $_SESSION['admin_role']     = $dbUser['rol'];
                $_SESSION['admin_avatar']   = $dbUser['imagen'] ?? '';
                return $this->redirect()->toRoute($this->getRoleRoute($dbUser['rol']));
            }

            // 2. Fallback hardcoded (solo si falla DB)
            if (isset(self::FALLBACK_USUARIOS[$usuarioUpper]) && self::FALLBACK_USUARIOS[$usuarioUpper]['password'] === $password) {
                $info = self::FALLBACK_USUARIOS[$usuarioUpper];
                $_SESSION['admin_logged']   = true;
                $_SESSION['admin_usuario']  = $usuarioUpper;
                $_SESSION['admin_role']     = $info['role'];
                $_SESSION['admin_avatar']   = '';
                return $this->redirect()->toRoute($info['redirect']);
            }

            $error = 'Usuario o contraseña incorrectos.';
        }

        $vm = new ViewModel(['error' => $error]);
        $vm->setTemplate('admin/auth/login');
        $this->layout('layout/admin-login');
        return $vm;
    }

    public function logoutAction()
    {
        $this->startSession();
        $_SESSION = [];
        session_destroy();
        return $this->redirect()->toRoute('admin-login');
    }

    private function getRoleRoute(string $role): string
    {
        return match ($role) {
            'publisher' => 'admin-publisher',
            'radio'     => 'admin-radio',
            'super'     => 'admin-super',
            default     => 'admin-login',
        };
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
