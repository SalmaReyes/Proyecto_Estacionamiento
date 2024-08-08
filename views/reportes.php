<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Reportes</title>
</head>
<body>
    <h1>Generar Reportes</h1>
    <form method="post" action="../public/reportes.php">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required>
        
        <button type="submit" name="generar_reporte">Generar Reporte</button>
    </form>
</body>
</html>
