<?php
include '../models/Vehiculo.php';
include '../utils/helpers.php';

class VehiculoController {
    private $vehiculo;

    public function __construct($db) {
        $this->vehiculo = new Vehiculo($db);
    }

    public function registrar($placa, $tipo, $accion) {
        if ($accion == 'entrada') {
            return $this->vehiculo->registrarEntrada($placa, $tipo);
        } elseif ($accion == 'salida') {
            $success = $this->vehiculo->registrarSalida($placa);
            if ($success) {
                $vehiculo = $this->vehiculo->obtenerUltimoRegistro($placa);
                if ($vehiculo) {
                    $cobro = calcularCobro($vehiculo['tipo'], $vehiculo['hora_entrada'], $vehiculo['hora_salida']);
                    return $this->vehiculo->actualizarCobro($vehiculo['id'], $cobro);
                } else {
                    // Manejar el caso en que no se encontró el registro
                    return false;
                }
            }
            return false;
        }
        return false;
    }

    public function generarReporte($fecha_inicio, $fecha_fin) {
        return $this->vehiculo->obtenerReportes($fecha_inicio, $fecha_fin);
    }

    public function obtenerRegistros() {
        return $this->vehiculo->obtenerTodos();
    }
}
?>
