<?php

require '../conexion_y_paginacion/database.php';
require './construye_powerpoint.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

// Genera un id unico
    $uniqid = uniqid();

    $nombre_carpeta_diapositivas = 'diapositivas_' . $uniqid;

// Construye ruta fisica raiz del proyecto
    $ruta_fisica = $_SERVER['DOCUMENT_ROOT'];

// Construye ruta fisica absoluta del powerpoint machote
    $ruta_diapositiva_machote = $ruta_fisica . '/' . $proyecto . '/PowerPoints/diapositiva_machote/diapositiva_machote.pptx';

// Construye ruta fisica absoluta donde se generaran los powerpoints
    $ruta_diapositiva_generada = $ruta_fisica . '/' . $proyecto . '/PowerPoints/' . $nombre_carpeta_diapositivas;

// Construye ruta fisica absoluta del archivo comprimido con los powerpoints
    $ruta_zip = $ruta_fisica . '/' . $proyecto . '/PowerPoints/' . $nombre_carpeta_diapositivas . '.zip';

    // Consulta para extraer la informacion para poner en las diapositivas
    $sql = "SELECT * FROM  vw_competencia_promocion_pptx cp WHERE CAST(cp.fecha AS DATE) >= CAST('{$request->FechaInicial}' AS DATE) AND CAST(cp.fecha AS DATE) <= CAST('{$request->FechaFinal}' AS DATE) AND cp.idcadena = '{$request->idcadena}' AND cp.idempresa = '{$request->idempresa}' AND TRIM(LOWER(cp.generar_ppt)) = TRIM(LOWER('true')) ORDER BY cp.idcompetenciapromo DESC;";

    $mysql = new MysqlManager();

    $mysql->Bitacora($sql);

    $informacion = $mysql->Query($sql, SelectType::SELECT);

    // Consulta para extraer el nombre corto de la cadena en base al id solicitado
    $sql = "SELECT c.nombrecorto FROM cat_cadena c WHERE c.idcadena = '{$request->idcadena}';";

    $nombre_corto_cadena = $mysql->Query($sql, SelectType::SELECT);

    if (sizeof($informacion) > 0) {

        $ppt = new ConstruyePowerPointEnBaseAMachote($ruta_diapositiva_machote, $ruta_diapositiva_generada, $ruta_zip);

        $ppt->obtienePowerPointMachote();

        $ppt->crearCarpetaParaGenerarPowerpoints();

        $ppt->crearPowerPointNuevo();

        $ppt->copiarLayout();

        $i = 0;
        foreach ($informacion as $elem) {
            $ppt->construyeDiapositiva($elem, ($i == 0) ? true : false);
            $i++;
        }

        $ppt->guardarPowerPoint($nombre_corto_cadena[0]['nombrecorto']);

        // Ruta url del archivo comprimido de las diapositivas
        $url_diapositiva_generada = str_replace(array($ruta_fisica), array($dominio), $ruta_zip);

        echo $ppt->comprimirPowerPointEnZip($url_diapositiva_generada, $ruta_diapositiva_generada, $ruta_zip, $nombre_carpeta_diapositivas);
    } else {
        echo json_encode(['idRes' => 1, 'Mensaje' => 'Por favor seleccione al menos un registro']);
    }
}