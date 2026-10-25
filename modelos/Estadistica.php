<?php
// Modelo Estadistica: calcula la tabla de posiciones a partir de los partidos finalizados
class Estadistica
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Devuelve, por cada equipo: partidos jugados, ganados, perdidos,
    // puntos a favor y puntos en contra. Solo cuentan los partidos con resultado.
    public function posiciones(): array
    {
        $sql = 'SELECT e.id, e.nombre,
                       COUNT(t.partido_id)                 AS pj,
                       COALESCE(SUM(t.favor > t.contra), 0) AS pg,
                       COALESCE(SUM(t.favor < t.contra), 0) AS pp,
                       COALESCE(SUM(t.favor), 0)           AS pf,
                       COALESCE(SUM(t.contra), 0)          AS pc
                FROM equipos e
                LEFT JOIN (
                    SELECT id AS partido_id, equipo_local_id AS equipo_id,
                           puntos_local AS favor, puntos_visitante AS contra
                    FROM partidos
                    WHERE puntos_local IS NOT NULL AND puntos_visitante IS NOT NULL
                    UNION ALL
                    SELECT id, equipo_visitante_id, puntos_visitante, puntos_local
                    FROM partidos
                    WHERE puntos_local IS NOT NULL AND puntos_visitante IS NOT NULL
                ) t ON t.equipo_id = e.id
                GROUP BY e.id, e.nombre
                ORDER BY pg DESC, (pf - pc) DESC, e.nombre';

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}