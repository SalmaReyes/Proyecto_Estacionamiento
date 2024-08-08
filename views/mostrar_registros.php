<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Estacionamiento</title>
    <link rel="stylesheet" href="../assets/estilos.css">
</head>
<body>
    <h1>Administrar Estacionamiento</h1>
    <form method="post" action="index.php">
        <label for="placa">Número de Placa:</label>
        <input type="text" id="placa" name="placa" required>
        
        <label for="tipo">Tipo de Vehículo:</label>
        <select id="tipo" name="tipo" required>
            <option value="oficial">Oficial</option>
            <option value="residente">Residente</option>
            <option value="no residente">No Residente</option>
        </select>
        
        <label for="accion">Acción:</label>
        <select id="accion" name="accion" required>
            <option value="entrada">Entrada</option>
            <option value="salida">Salida</option>
        </select>
        
        <button type="submit">Registrar</button>
    </form>

    <h2>Registros de Vehículos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Tipo</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
                <th>Cobro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $registros = $vehiculoController->obtenerRegistros();
            foreach ($registros as $registro) {
                echo "<tr>";
                echo "<td>{$registro['id']}</td>";
                echo "<td>{$registro['placa']}</td>";
                echo "<td>{$registro['tipo']}</td>";
                echo "<td>{$registro['hora_entrada']}</td>";
                echo "<td>{$registro['hora_salida']}</td>";
                echo "<td>{$registro['cobro']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Descargar Reportes</h2>
    <a href="index.php?reporte=excel">Descargar Reporte en Excel</a><br>
    <a href="index.php?reporte=pdf">Descargar Reporte en PDF</a>
</body>
</html>
