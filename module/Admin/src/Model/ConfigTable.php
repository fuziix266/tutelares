<?php
namespace Admin\Model;

use PDO;

class ConfigTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get(string $clave, string $default = ''): string
    {
        $stmt = $this->pdo->prepare("SELECT valor FROM configuracion WHERE clave = ?");
        $stmt->execute([$clave]);
        $res = $stmt->fetch();
        return $res ? $res['valor'] : $default;
    }

    public function set(string $clave, string $valor): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO configuracion (clave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = ?");
        $stmt->execute([$clave, $valor, $valor]);
    }
}
