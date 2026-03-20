<?php
namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    /**
     * Usuarios definidos por rol.
     * Formato: 'USUARIO' => ['password' => 'PASS', 'role' => 'role_key', 'redirect' => 'route-name']
     */
    private const USUARIOS = [
        'PUBLISHER'   => [
            'password' => 'PASSWORD',
            'role'     => 'publisher',
            'redirect' => 'admin-publisher',
        ],
        'RADIO'       => [
            'password' => 'PASSWORD',
            'role'     => 'radio',
            'redirect' => 'admin-radio',
        ],
        'SUPERUSUARIO' => [
            'password' => 'PASSWORD',
            'role'     => 'super',
            'redirect' => 'admin-super',
        ],
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
            $usuario  = strtoupper(trim($data['usuario'] ?? ''));
            $password = $data['password'] ?? '';

            if (
                isset(self::USUARIOS[$usuario]) &&
                self::USUARIOS[$usuario]['password'] === $password
            ) {
                $info = self::USUARIOS[$usuario];
                $_SESSION['admin_logged']   = true;
                $_SESSION['admin_usuario']  = $usuario;
                $_SESSION['admin_role']     = $info['role'];
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
