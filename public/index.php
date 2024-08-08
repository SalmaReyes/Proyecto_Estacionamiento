<?php
// Incluye la clase Vehiculo
include '../models/Vehiculo.php'; // Ajusta la ruta según la estructura de tu proyecto

// Incluye Dompdf
require '../vendor/autoload.php'; // Ajusta la ruta según la ubicación del autoload de Composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Crea una instancia de la conexión a la base de datos
require '../config/config.php'; // Incluye el archivo de configuración

// Crea una instancia de la clase Vehiculo
$vehiculo = new Vehiculo($db);

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['registrar_entrada'])) {
        $placa = $_POST['placa'];
        $tipo = $_POST['tipo'];
        if ($vehiculo->registrarEntrada($placa, $tipo)) {
            echo "Entrada registrada con éxito.";
        } else {
            echo "Error al registrar entrada.";
        }
    }

    if (isset($_POST['registrar_salida'])) {
        $placa = $_POST['placa'];
        if ($vehiculo->registrarSalida($placa)) {
            echo "Salida registrada con éxito.";
        } else {
            echo "Error al registrar salida.";
        }
    }

    if (isset($_POST['generar_pdf'])) {
        // Obtener todos los registros
        $todos = $vehiculo->obtenerTodos();

        // Verificar que se están obteniendo datos
        if ($todos === false || empty($todos)) {
            echo "No se encontraron registros para generar el reporte.";
            exit();
        }

        // Configurar Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Generar HTML para el PDF
        $html = '<h1>Reportes de Estacionamiento</h1>';
        $html .= '<table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<thead><tr><th>ID</th><th>Placa</th><th>Tipo</th><th>Hora Entrada</th><th>Hora Salida</th><th>Cobro</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($todos as $registro) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($registro['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($registro['placa']) . '</td>';
            $html .= '<td>' . htmlspecialchars($registro['tipo']) . '</td>';
            $html .= '<td>' . htmlspecialchars($registro['hora_entrada']) . '</td>';
            $html .= '<td>' . htmlspecialchars($registro['hora_salida']) . '</td>';
            $html .= '<td>' . htmlspecialchars($registro['cobro']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configurar tamaño y orientación de la página
        $dompdf->setPaper('A4', 'landscape');

        // Renderizar el PDF
        $dompdf->render();

        // Enviar el PDF al navegador para descarga
        $dompdf->stream('reportes.pdf', array('Attachment' => 1));
        exit();
    }
}

// Obtener todos los registros para mostrar en la tabla
$todos = $vehiculo->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estacionamiento</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <h1>Gestión de Estacionamiento</h1>
    <div class="container">
        <div class="forms-container">
            <!-- Formulario para registrar entrada -->
            <form action="" method="post">
                <h2>Registrar Entrada</h2>
                <label for="placa">Placa:</label>
                <input type="text" name="placa" id="placa" required>
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="residente">Residente</option>
                    <option value="no_residente">No Residente</option>
                    <option value="oficial">Oficial</option>
                </select>
                <button type="submit" name="registrar_entrada">Registrar Entrada</button>
            </form>

            <!-- Formulario para registrar salida -->
            <form action="" method="post">
                <h2>Registrar Salida</h2>
                <label for="placa_salida">Placa:</label>
                <input type="text" name="placa" id="placa_salida" required>
                <button type="submit" name="registrar_salida">Registrar Salida</button>
            </form>
            
            <!-- Botón para generar PDF con todos los registros -->
            <form action="" method="post">
                <h2>Generar Reporte PDF</h2>
                <button type="submit" name="generar_pdf">Generar PDF</button>
            </form>
        </div>

        <!-- Mostrar todos los registros -->
        <h2>Todos los Registros</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Tipo</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
                <th>Cobro</th>
            </tr>
            <?php foreach ($todos as $registro): ?>
            <tr>
                <td><?php echo htmlspecialchars($registro['id']); ?></td>
                <td><?php echo htmlspecialchars($registro['placa']); ?></td>
                <td><?php echo htmlspecialchars($registro['tipo']); ?></td>
                <td><?php echo htmlspecialchars($registro['hora_entrada']); ?></td>
                <td><?php echo htmlspecialchars($registro['hora_salida']); ?></td>
                <td><?php echo htmlspecialchars($registro['cobro']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>

