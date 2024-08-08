<?php
namespace App\Controllers;

use Dompdf\Dompdf;
use PDO;

class ReporteController {
    protected $conn;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function generarReportePDF() {
        // Inicializa Dompdf
        $dompdf = new Dompdf();

        // Consulta a la base de datos
        $sql = "SELECT * FROM vehiculo"; // Ajusta la consulta a tu tabla
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Crear contenido HTML para el PDF
        $html = '<h1>Reporte de Vehículos</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
        $html .= '<thead><tr><th>ID</th><th>Placa</th><th>Tipo</th><th>Hora Entrada</th><th>Hora Salida</th><th>Cobro</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($datos as $row) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['id']) . '</td>'; // Asegúrate de usar htmlspecialchars para evitar XSS
            $html .= '<td>' . htmlspecialchars($row['placa']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['tipo']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['hora_entrada']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['hora_salida']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['cobro']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        // Cargar contenido HTML en Dompdf
        $dompdf->loadHtml($html);

        // (Opcional) Configurar el tamaño de papel y la orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el HTML como PDF
        $dompdf->render();

        // Ruta del archivo PDF
        $directoryPath = '../reports/';
        $filePath = $directoryPath . 'reporte.pdf';

        // Crear el directorio si no existe
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true); // Crear el directorio con permisos completos
        }

        // Guardar el PDF en un archivo
        $output = $dompdf->output();
        if (file_put_contents($filePath, $output)) {
            return $filePath;
        } else {
            return false; // Indicar que hubo un error al guardar el archivo
        }
    }

    //public function generarReporteExcel() {
        // Implementar la lógica para generar el archivo Excel si es necesario
    //}
}
?>
