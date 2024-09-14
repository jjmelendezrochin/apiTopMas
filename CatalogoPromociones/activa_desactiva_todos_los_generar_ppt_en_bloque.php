<?php

require '../conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    $sql = "CALL proc_activa_desactiva_checkboxes_en_bloque('{$request->FechaInicial}','{$request->FechaFinal}', '{$request->idcadena}', '{$request->idempresa}', '{$request->generar_ppt}');";

    $mysql->Bitacora($sql);

    $mysql->QueryAsNormal($sql);

    $res = [
        'idResp' => '',
        'Mensaje' => ''
    ];
    echo json_encode($res);
}    
    