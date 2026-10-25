<?php
require_once __DIR__ . '/../modelos/Resumen.php';

// Controlador Resumen: prepara los datos de la portada
class ResumenControlador
{
    private Resumen $modelo;

    public function __construct(PDO $pdo)
    {
        $this->modelo = new Resumen($pdo);
    }

    public function totales(): array
    {
        return [
            'equipos'   => $this->modelo->contar('equipos'),
            'jugadores' => $this->modelo->contar('jugadores'),
            'partidos'  => $this->modelo->contar('partidos'),
        ];
    }

    public function ultimos(): array
    {
        return $this->modelo->ultimosResultados(3);
    }
}