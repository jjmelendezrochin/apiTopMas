<?php

require '../conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

$cat = [];

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    $cond = " vw_competencia_promocion_pptx cp WHERE CAST(cp.fecha AS DATE) >= CAST('{$request->FechaInicial}' AS DATE) AND CAST(cp.fecha AS DATE) <= CAST('{$request->FechaFinal}' AS DATE) AND cp.idcadena = '{$request->idcadena}' AND cp.idempresa = '{$request->idempresa}' ORDER BY cp.idcompetenciapromo DESC ";

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

    if (sizeof($cat) > 0) {
        $i = 0;
        foreach ($cat as $_cat) {
            $cat[$i]["generar_ppt"] = (trim(strtolower($_cat["generar_ppt"])) == 'true') ? true : false;
            $i++;
        }
    }


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