<?php

function descargarImagenes($imageUrl) {
// Inicializar cURL
    $ch = curl_init($imageUrl);

// Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Devolver el resultado en lugar de mostrarlo
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // Transferencia en binario
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignorar verificación SSL (solo para ejemplos, no recomendado en producción)
// Ejecutar la solicitud cURL
    $imageData = curl_exec($ch);

// Cerrar la conexión cURL
    curl_close($ch);

    return $imageData;
}
