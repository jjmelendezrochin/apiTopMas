<?php

require '../conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    $sql = "UPDATE competencia_promocion cp SET cp.generar_ppt = '{$request->generar_ppt}' WHERE cp.idcompetenciapromo = '{$request->idcompetenciapromo}';";

    $mysql->Bitacora($sql);

    $mysql->QueryAsNormal($sql);

    $res = [
        'idResp' => 0,
        'Mensaje' => ''
    ];
    echo json_encode($res);
}    