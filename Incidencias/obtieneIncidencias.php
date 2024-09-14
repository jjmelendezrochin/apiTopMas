<?php

require '../conexion_y_paginacion/database.php';

date_default_timezone_set('America/Mexico_City');

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $mysql = new MysqlManager();

    // Sanitize.
    $fechainicial = $mysql->real_escape_string((string) $request->fechainicial);
    $fechafinal = $mysql->real_escape_string((string) $request->fechafinal);
    $idEmpresa = $mysql->real_escape_string((string) $request->idEmpresa);
    $page = $mysql->real_escape_string((string) $request->page);
    $resultsForPage = $mysql->real_escape_string((string) $request->resultsForPage);

    // ****************************
    // Armado de consulta
    $sqlConsulta = "SELECT * FROM vw_incidencias i WHERE i.idempresa = '{$idEmpresa}' AND CAST(i.FechaHora AS DATE) BETWEEN  CAST('{$fechainicial}' AS DATE) AND CAST('{$fechafinal}' AS DATE)  ";
    $sqlCond = " vw_incidencias i WHERE i.idempresa = '{$idEmpresa}' AND CAST(i.FechaHora AS DATE) BETWEEN  CAST('{$fechainicial}' AS DATE) AND CAST('{$fechafinal}' AS DATE)  ";

    $sqlConsulta .= " order by 1 desc ";
    $sqlCond .= " order by 1 desc ";
    // ****************************

    $Select_Type = SelectType::SELECT_WITH_PAGINATION;
    if (trim($request->resultsForPage) == '') {
        $Select_Type = SelectType::SELECT;
    }

    $mysql->resultsForPage = $resultsForPage;
    $mysql->currentPage = $page;
    $mysql->tableMoreCondition = $sqlCond;
    // SelectType::NONE se establece para cuando solo se require jacer una modificiacion insert,update, delete

    $incidencias = $mysql->Query($sqlConsulta, $Select_Type);

    if ($Select_Type == SelectType::SELECT_WITH_PAGINATION) {
        $res = [
            'resultsForPage' => $mysql->getPaginator()->getResultsForPage(),
            'currentPage' => $mysql->getPaginator()->getCurrentPage(),
            'totalPages' => $mysql->getPaginator()->getTotalPages(),
            'totalRecords' => $mysql->getPaginator()->getNResults(),
            'sql' => $sqlConsulta,
            'regs' => $incidencias
        ];
    } else {
        $res = [
            'regs' => $incidencias
        ];
    }
    echo json_encode($res);
}