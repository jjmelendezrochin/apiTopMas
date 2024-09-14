<?php

require '../conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

$sql = '';
$idinc = '';
$banco = '';
$uda = '';
$fda = '';
$operacion = '';

/// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    // Sanitize.
    $idinc = $mysql->real_escape_string((string) $request->idinc);
    $respuesta = $mysql->real_escape_string((string) $request->respuesta);

    // Guarda respuesta
    $sql = "CALL `proc_guarda_respuesta_incidencia`('{$idinc}', '{$respuesta}');";

    // Obtiene los resultados
    $result = $mysql->QueryAsNormal($sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $res['idRes'] = $row['idRes'];
            $res['Mensaje'] = $row['Mensaje'];
        }
    }

    // Cierra la conexion
    $mysql->Close($mysql->getConnection());

    echo json_encode($res);
}