<?php
include '../config/config.php'; // Asegúrate de que la ruta es correcta
include '../controllers/VehiculoController.php';

$controller = new VehiculoController($conn);

if (isset($_POST['generar_reporte'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    
    $result = $controller->generarReporte($fecha_inicio, $fecha_fin);
    
    // Aquí puedes agregar el código para exportar los datos a Excel o PDF.
}

include '../views/reportes.php';
?>
