<?php
require_once __DIR__ . '/../modelos/Estadistica.php';

// Controlador Estadistica: prepara la tabla de posiciones para la vista
class EstadisticaControlador
{
    private Estadistica $modelo;

    public function __construct(PDO $pdo)
    {
        $this->modelo = new Estadistica($pdo);
    }

    // Devuelve las posiciones con la diferencia de puntos ya calculada
    public function posiciones(): array
    {
        $tabla = $this->modelo->posiciones();

        foreach ($tabla as &$fila) {
            $fila['dif'] = (int)$fila['pf'] - (int)$fila['pc'];
        }
        unset($fila);

        return $tabla;
    }
}