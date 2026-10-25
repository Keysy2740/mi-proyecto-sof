<?php
require_once __DIR__ . '/../modelos/Equipo.php';

// Controlador Equipo: recibe los datos, valida y le pide al modelo que los guarde
class EquipoControlador
{
    private Equipo $modelo;

    public function __construct(PDO $pdo)
    {
        $this->modelo = new Equipo($pdo);
    }

    public function listar(): array
    {
        return $this->modelo->todos();
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
    public function guardar(?int $id, string $nombre, string $ciudad, string $entrenador): string
    {
        $nombre     = trim($nombre);
        $ciudad     = trim($ciudad);
        $entrenador = trim($entrenador);

        if ($nombre === '' || $ciudad === '') {
            return 'El nombre y la ciudad son obligatorios.';
        }

        if ($id) {
            $this->modelo->actualizar($id, $nombre, $ciudad, $entrenador);
        } else {
            $this->modelo->crear($nombre, $ciudad, $entrenador);
        }

        return '';
    }
}