<?php
require_once __DIR__ . '/../modelos/Partido.php';
require_once __DIR__ . '/../modelos/Equipo.php';

// Controlador Partido: valida las reglas del básquetbol y usa los modelos para guardar
class PartidoControlador
{
    private Partido $modelo;
    private Equipo $modeloEquipo;

    public function __construct(PDO $pdo)
    {
        $this->modelo       = new Partido($pdo);
        $this->modeloEquipo = new Equipo($pdo);
    }

    public function listar(): array
    {
        return $this->modelo->todos();
    }

    // Equipos para llenar los menús desplegables
    public function equipos(): array
    {
        return $this->modeloEquipo->todos();
    }

    public function buscar(int $id)
    {
        return $this->modelo->buscar($id);
    }

    public function eliminar(int $id): void
    {
        $this->modelo->eliminar($id);
    }

    // Valida y guarda (crea si no hay id, actualiza si lo hay).
    // Los puntos llegan como texto: una cadena vacía significa partido pendiente.
    // Devuelve un mensaje de error, o una cadena vacía si todo salió bien.
    public function guardar(
        ?int $id,
        int $local,
        int $visitante,
        string $fecha,
        string $puntosLocalTexto,
        string $puntosVisitanteTexto
    ): string {
        $puntosLocal     = $puntosLocalTexto === '' ? null : (int)$puntosLocalTexto;
        $puntosVisitante = $puntosVisitanteTexto === '' ? null : (int)$puntosVisitanteTexto;

        if ($local === 0 || $visitante === 0 || $fecha === '') {
            return 'Debes elegir los dos equipos y la fecha.';
        }

        if ($local === $visitante) {
            return 'Un equipo no puede jugar contra sí mismo.';
        }

        // Los puntos se llenan para ambos equipos o para ninguno
        if (($puntosLocal === null) !== ($puntosVisitante === null)) {
            return 'Ingresa los puntos de ambos equipos, o deja los dos vacíos si el partido está pendiente.';
        }

        if ($puntosLocal !== null) {
            if ($puntosLocal < 0 || $puntosVisitante < 0) {
                return 'Los puntos no pueden ser negativos.';
            }
            if ($puntosLocal === $puntosVisitante) {
                return 'En básquetbol no hay empates: los puntos deben ser distintos.';
            }
        }

        if ($id) {
            $this->modelo->actualizar($id, $local, $visitante, $puntosLocal, $puntosVisitante, $fecha);
        } else {
            $this->modelo->crear($local, $visitante, $puntosLocal, $puntosVisitante, $fecha);
        }

        return '';
    }
}