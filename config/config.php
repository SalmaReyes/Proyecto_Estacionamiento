<?php
// Definir las variables de conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=vehiculo'; // Cambia 'vehiculo' por el nombre real de tu base de datos
$usuario = 'root'; // Cambia 'root' por el nombre real del usuario de la base de datos
$contraseña = ''; // Cambia '' por la contraseña real del usuario de la base de datos

try {
    // Crear una nueva instancia de PDO para la conexión a la base de datos
    $db = new PDO($dsn, $usuario, $contraseña);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Capturar cualquier error y mostrar un mensaje
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>
