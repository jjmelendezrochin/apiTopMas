<?php

require '../conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    $sql = "SELECT fn_generar_ppt_todos('{$request->FechaInicial}','{$request->FechaFinal}','{$request->idcadena}','{$request->idempresa}') AS marcar_todos;";

    $mysql->Bitacora($sql);

    $marcar_todos = $mysql->Query($sql, SelectType::SELECT);

    $res = [
        'marcar_todos' => (trim(strtolower($marcar_todos[0]['marcar_todos'])) == 'true') ? true : false
    ];
    echo json_encode($res);
}