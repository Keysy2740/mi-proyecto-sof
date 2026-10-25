<?php
// Modelo Equipo: concentra todas las consultas a la tabla "equipos"
class Equipo
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Devuelve todos los equipos ordenados por nombre
    public function todos(): array
    {
        return $this->pdo
            ->query('SELECT * FROM equipos ORDER BY nombre')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve un equipo por su id, o false si no existe
    public function buscar(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM equipos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(string $nombre, string $ciudad, string $entrenador): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO equipos (nombre, ciudad, entrenador) VALUES (?, ?, ?)'
        );
        $stmt->execute([$nombre, $ciudad, $entrenador]);
    }

    public function actualizar(int $id, string $nombre, string $ciudad, string $entrenador): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE equipos SET nombre = ?, ciudad = ?, entrenador = ? WHERE id = ?'
        );
        $stmt->execute([$nombre, $ciudad, $entrenador, $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM equipos WHERE id = ?');
        $stmt->execute([$id]);
    }
}