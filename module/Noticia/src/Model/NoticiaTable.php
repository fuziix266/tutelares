<?php
namespace Noticia\Model;

class NoticiaTable
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM noticias ORDER BY fijada DESC, creado_en DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchByCategory(string $cat): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM noticias WHERE categoria = ? ORDER BY fijada DESC, creado_en DESC');
        $stmt->execute([$cat]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUltimaHora(): ?array
    {
        $stmt = $this->pdo->query('SELECT * FROM noticias WHERE fijada = 1 ORDER BY creado_en DESC LIMIT 1');
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM noticias WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function insert(array $data): void
    {
        $data = $this->sanitize($data);
        if (($data['fijada'] ?? 0) == 1) {
            $this->pdo->exec('UPDATE noticias SET fijada = 0');
        }
        $cols = implode(', ', array_keys($data));
        $vals = implode(', ', array_fill(0, count($data), '?'));
        $stmt = $this->pdo->prepare("INSERT INTO noticias ($cols) VALUES ($vals)");
        $stmt->execute(array_values($data));
    }

    public function update(int $id, array $data): void
    {
        $data  = $this->sanitize($data);
        if (($data['fijada'] ?? 0) == 1) {
            $this->pdo->exec('UPDATE noticias SET fijada = 0');
        }
        $set   = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $stmt  = $this->pdo->prepare("UPDATE noticias SET $set WHERE id = ?");
        $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM noticias WHERE id = ?');
        $stmt->execute([$id]);
    }

    private function sanitize(array $data): array
    {
        $allowed = ['titulo', 'categoria', 'resumen', 'contenido', 'imagen', 'autor', 'activo', 'fijada'];
        $clean   = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $clean[$field] = $data[$field];
            }
        }
        return $clean;
    }
}
