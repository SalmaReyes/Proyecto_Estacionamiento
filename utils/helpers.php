<?php
function calcularCobro($tipo, $hora_entrada, $hora_salida) {
    // Verifica si hora_salida no es null o vacía antes de convertirla
    if (!$hora_salida) {
        return 0.00;
    }

    // Asegúrate de que hora_entrada y hora_salida están en el formato correcto
    $hora_entrada = new DateTime($hora_entrada);
    $hora_salida = new DateTime($hora_salida);
    $intervalo = $hora_entrada->diff($hora_salida);

    // Convertir intervalo a minutos
    $minutos = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

    // Tarifas por minuto
    $tarifa_residente = 1.00; // Tarifa en pesos MXN por minuto para residentes
    $tarifa_no_residente = 3.00; // Tarifa en pesos MXN por minuto para no residentes

    // Calcular el cobro
    if ($tipo === 'oficial') {
        return 0.00; // Los vehículos oficiales no pagan
    } elseif ($tipo === 'residente') {
        return round($minutos * $tarifa_residente, 2);
    } else { // Suponiendo que 'no_residente' es el tipo predeterminado para otros tipos de vehículos
        return round($minutos * $tarifa_no_residente, 2);
    }
}
?>
