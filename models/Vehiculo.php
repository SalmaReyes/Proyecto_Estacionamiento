<?php
class Vehiculo {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrarEntrada($placa, $tipo) {
        $sql = "INSERT INTO vehiculo (placa, tipo, hora_entrada) VALUES (:placa, :tipo, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':placa', $placa);
        $stmt->bindValue(':tipo', $tipo);
        return $stmt->execute();
    }

    public function registrarSalida($placa) {
        $sql = "UPDATE vehiculo SET hora_salida = NOW() WHERE placa = :placa AND hora_salida IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':placa', $placa);
        $stmt->execute();

        // Obtener el último registro actualizado
        $vehiculo = $this->obtenerUltimoRegistro($placa);
        if ($vehiculo && $vehiculo['hora_salida'] != '0000-00-00 00:00:00') {
            // Calcular el cobro
            $cobro = $this->calcularCobro($vehiculo['tipo'], $vehiculo['hora_entrada'], $vehiculo['hora_salida']);
            // Actualizar el cobro en la base de datos
            $this->actualizarCobro($vehiculo['id'], $cobro);
            return true;
        } else {
            // Si la hora de salida es inválida, manejar el error
            return false;
        }
    }

    public function obtenerUltimoRegistro($placa) {
        $sql = "SELECT * FROM vehiculo WHERE placa = :placa ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':placa', $placa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarCobro($id, $cobro) {
        $sql = "UPDATE vehiculo SET cobro = :cobro WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':cobro', $cobro);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM vehiculo";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function calcularCobro($tipo, $hora_entrada, $hora_salida) {
        $entrada = new DateTime($hora_entrada);
        $salida = new DateTime($hora_salida);
        $intervalo = $entrada->diff($salida);

        // Calcula el total en minutos
        $minutos = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

        // Determina el cobro basado en el tipo de vehículo
        switch ($tipo) {
            case 'oficial':
                return 0.00;
            case 'residente':
                // Se cobra $1.00 MXN por minuto
                return $minutos * 1.00;
            case 'no_residente':
                // Se cobra $3.00 MXN por minuto
                return $minutos * 3.00;
            default:
                return 0.00;
        }
    }
}
?>
