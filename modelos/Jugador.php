<?php
// Modelo Jugador: concentra todas las consultas a la tabla "jugadores"
class Jugador
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista los jugadores con el nombre de su equipo.
    // Si se indica un equipo, solo devuelve los de ese equipo.
    public function todos(int $equipoId = 0): array
    {
        if ($equipoId > 0) {
            $stmt = $this->pdo->prepare(
                'SELECT j.*, e.nombre AS equipo
                 FROM jugadores j
                 JOIN equipos e ON e.id = j.equipo_id
                 WHERE j.equipo_id = ?
                 ORDER BY j.numero'
            );
            $stmt->execute([$equipoId]);
        } else {
            $stmt = $this->pdo->query(
                'SELECT j.*, e.nombre AS equipo
                 FROM jugadores j
                 JOIN equipos e ON e.id = j.equipo_id
                 ORDER BY e.nombre, j.numero'
            );
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve un jugador por su id, o false si no existe
    public function buscar(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM jugadores WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Indica si el número de camiseta ya lo usa otro jugador del mismo equipo.
    // $ignorarId sirve al editar, para que el jugador no choque consigo mismo.
    public function numeroOcupado(int $equipoId, int $numero, int $ignorarId = 0): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM jugadores WHERE equipo_id = ? AND numero = ? AND id <> ?'
        );
        $stmt->execute([$equipoId, $numero, $ignorarId]);
        return (bool) $stmt->fetch();
    }

    public function crear(string $nombre, int $numero, string $posicion, int $equipoId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO jugadores (nombre, numero, posicion, equipo_id) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$nombre, $numero, $posicion, $equipoId]);
    }

    public function actualizar(int $id, string $nombre, int $numero, string $posicion, int $equipoId): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE jugadores SET nombre = ?, numero = ?, posicion = ?, equipo_id = ? WHERE id = ?'
        );
        $stmt->execute([$nombre, $numero, $posicion, $equipoId, $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM jugadores WHERE id = ?');
        $stmt->execute([$id]);
    }
}