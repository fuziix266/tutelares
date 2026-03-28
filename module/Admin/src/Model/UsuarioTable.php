<?php
namespace Admin\Model;

use PDO;

class UsuarioTable
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuario(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUsuarioByName(string $name): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function save(array $data): void
    {
        if (isset($data['id']) && $data['id'] > 0) {
            // Update
            $sql = "UPDATE usuarios SET usuario = ?, password = ?, rol = ?, nombre = ?, imagen = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['usuario'],
                $data['password'],
                $data['rol'],
                $data['nombre'] ?? null,
                $data['imagen'] ?? null,
                $data['id']
            ]);
        } else {
            // Insert
            $sql = "INSERT INTO usuarios (usuario, password, rol, nombre, imagen) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['usuario'],
                $data['password'],
                $data['rol'],
                $data['nombre'] ?? null,
                $data['imagen'] ?? null
            ]);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    }
}
