<?php
// Modelo Partido: concentra todas las consultas a la tabla "partidos"
class Partido
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista los partidos con el nombre de ambos equipos.
    // Se une la tabla equipos dos veces: una para el local y otra para el visitante.
    public function todos(): array
    {
        return $this->pdo->query(
            'SELECT p.*, l.nombre AS local, v.nombre AS visitante
             FROM partidos p
             JOIN equipos l ON l.id = p.equipo_local_id
             JOIN equipos v ON v.id = p.equipo_visitante_id
             ORDER BY p.fecha DESC, p.id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve un partido por su id, o false si no existe
    public function buscar(int $id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM partidos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Los puntos pueden ser null: eso significa que el partido está pendiente
    public function crear(int $local, int $visitante, ?int $puntosLocal, ?int $puntosVisitante, string $fecha): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO partidos (equipo_local_id, equipo_visitante_id, puntos_local, puntos_visitante, fecha)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$local, $visitante, $puntosLocal, $puntosVisitante, $fecha]);
    }

    public function actualizar(int $id, int $local, int $visitante, ?int $puntosLocal, ?int $puntosVisitante, string $fecha): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE partidos
             SET equipo_local_id = ?, equipo_visitante_id = ?, puntos_local = ?, puntos_visitante = ?, fecha = ?
             WHERE id = ?'
        );
        $stmt->execute([$local, $visitante, $puntosLocal, $puntosVisitante, $fecha, $id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM partidos WHERE id = ?');
        $stmt->execute([$id]);
    }
}