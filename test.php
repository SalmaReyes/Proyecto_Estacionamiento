<?php
require '../vendor/autoload.php'; // Ruta desde el directorio `public`

use App\Controllers\ReporteController;

if (class_exists('App\Controllers\ReporteController')) {
    echo "La clase ReporteController se carga correctamente.";
} else {
    echo "No se pudo cargar la clase ReporteController.";
}

echo "La carpeta public está funcionando.";


?>
