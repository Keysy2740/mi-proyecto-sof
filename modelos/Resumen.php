<?php
// Modelo Resumen: datos generales para mostrar en la portada
class Resumen
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Cuenta cuántos registros hay en una tabla permitida
    public function contar(string $tabla): int
    {
        $permitidas = ['equipos', 'jugadores', 'partidos'];
        if (!in_array($tabla, $permitidas, true)) {
            return 0;
        }
        return (int) $this->pdo->query("SELECT COUNT(*) FROM $tabla")->fetchColumn();
    }

    // Últimos partidos que ya tienen resultado
    public function ultimosResultados(int $cantidad = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, l.nombre AS local, v.nombre AS visitante
             FROM partidos p
             JOIN equipos l ON l.id = p.equipo_local_id
             JOIN equipos v ON v.id = p.equipo_visitante_id
             WHERE p.puntos_local IS NOT NULL AND p.puntos_visitante IS NOT NULL
             ORDER BY p.fecha DESC, p.id DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Próximos partidos: los que todavía no tienen resultado
    public function proximosPartidos(int $cantidad = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.*, l.nombre AS local, v.nombre AS visitante
             FROM partidos p
             JOIN equipos l ON l.id = p.equipo_local_id
             JOIN equipos v ON v.id = p.equipo_visitante_id
             WHERE p.puntos_local IS NULL OR p.puntos_visitante IS NULL
             ORDER BY p.fecha ASC, p.id ASC
             LIMIT ?'
        );
        $stmt->bindValue(1, $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}