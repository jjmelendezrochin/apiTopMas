<?php

require './conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

$cat = [];

$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
// Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    $cond = " vw_ajuste_acumulado ac ";

    $Select_Type = SelectType::SELECT_WITH_PAGINATION;
    if (trim($request->resultsForPage) == '') {
        $Select_Type = SelectType::SELECT;
    }

    $mysql->resultsForPage = $request->resultsForPage;
    $mysql->currentPage = $request->page;
    $mysql->tableMoreCondition = $cond . ";";

    $sql = "SELECT * FROM " . $cond;

    $mysql->Bitacora($sql);

    $cat = $mysql->Query($sql, $Select_Type);

    if ($Select_Type == SelectType::SELECT_WITH_PAGINATION) {
        $res = [
            'resultsForPage' => $mysql->getPaginator()->getResultsForPage(),
            'currentPage' => $mysql->getPaginator()->getCurrentPage(),
            'totalPages' => $mysql->getPaginator()->getTotalPages(),
            'totalRecords' => $mysql->getPaginator()->getNResults(),
            'sql' => $sql,
            'regs' => $cat
        ];
    } else {
        $res = [
            'regs' => $cat
        ];
    }
    echo json_encode($res);
}