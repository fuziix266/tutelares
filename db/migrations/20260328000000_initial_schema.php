<?php

use Phinx\Migration\AbstractMigration;

class InitialSchema extends AbstractMigration
{
    /**
     * Initial schema for Tutelares
     */
    public function change()
    {
        // Table configuracion
        $config = $this->table('configuracion');
        $config->addColumn('clave', 'string', ['limit' => 255])
               ->addColumn('valor', 'text')
               ->addIndex(['clave'], ['unique' => true])
               ->create();

        // Initial record for portal_status
        $this->execute("INSERT INTO configuracion (clave, valor) VALUES ('portal_status', 'activo') ON DUPLICATE KEY UPDATE valor = 'activo'");

        // Table usuarios
        $usuarios = $this->table('usuarios');
        $usuarios->addColumn('usuario', 'string', ['limit' => 255])
                 ->addColumn('password', 'string', ['limit' => 255])
                 ->addColumn('rol', 'string', ['limit' => 50])
                 ->addColumn('nombre', 'string', ['limit' => 255, 'null' => true])
                 ->addColumn('imagen', 'string', ['limit' => 255, 'null' => true])
                 ->addIndex(['usuario'], ['unique' => true])
                 ->create();

        // Table noticias
        $noticias = $this->table('noticias');
        $noticias->addColumn('titulo', 'string', ['limit' => 255])
                 ->addColumn('categoria', 'integer', ['null' => true])
                 ->addColumn('resumen', 'text', ['null' => true])
                 ->addColumn('contenido', 'text', ['limit' => Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG, 'null' => true])
                 ->addColumn('imagen', 'string', ['limit' => 255, 'null' => true])
                 ->addColumn('autor', 'integer', ['null' => true])
                 ->addColumn('activo', 'boolean', ['default' => true])
                 ->addColumn('creado_en', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                 ->create();
    }
}
