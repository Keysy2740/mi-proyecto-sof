<?php
require_once __DIR__ . '/../modelos/Jugador.php';
require_once __DIR__ . '/../modelos/Equipo.php';

// Controlador Jugador: valida las reglas del negocio y usa los modelos para guardar
class JugadorControlador
{
    private Jugador $modelo;
    private Equipo $modeloEquipo;

    public function __construct(PDO $pdo)
    {
        $this->modelo       = new Jugador($pdo);
        $this->modeloEquipo = new Equipo($pdo);
    }

    // Lista los jugadores, con filtro opcional por equipo (0 = todos)
    public function listar(int $equipoId = 0): array
    {
        return $this->modelo->todos($equipoId);
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
    // Devuelve un mensaje de error, o una cadena vacía si todo salió bien.
    public function guardar(?int $id, string $nombre, int $numero, string $posicion, int $equipoId): string
    {
        $nombre   = trim($nombre);
        $posicion = trim($posicion);

        if ($nombre === '' || $posicion === '' || $equipoId === 0) {
            return 'Nombre, posición y equipo son obligatorios.';
        }

        if ($numero < 0 || $numero > 99) {
            return 'El número de camiseta debe estar entre 0 y 99.';
        }

        // Regla: un número de camiseta no puede repetirse dentro del mismo equipo
        if ($this->modelo->numeroOcupado($equipoId, $numero, $id ?? 0)) {
            return 'Ese número de camiseta ya lo usa otro jugador del equipo.';
        }

        if ($id) {
            $this->modelo->actualizar($id, $nombre, $numero, $posicion, $equipoId);
        } else {
            $this->modelo->crear($nombre, $numero, $posicion, $equipoId);
        }

        return '';
    }
}