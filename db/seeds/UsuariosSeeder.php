<?php

use Phinx\Seed\AbstractSeed;

class UsuariosSeeder extends AbstractSeed
{
    /**
     * Run the seed for admin users
     */
    public function run()
    {
        $data = [
            [
                'usuario'  => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'rol'      => 'super',
                'nombre'   => 'Super Administrador',
            ]
        ];

        $table = $this->table('usuarios');
        $this->execute('DELETE FROM usuarios WHERE usuario = "admin"');
        $table->insert($data)
              ->saveData();
    }
}
