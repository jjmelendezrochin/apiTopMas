<?php

require '../conexion_y_paginacion/database.php';
require './construye_carpeta_con_fotos.php';

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    // Genera un id unico
    $uniqid = uniqid();

    $nombre_carpeta_imagenes = 'fotos_' . $uniqid;

    // Construye ruta fisica raiz del proyecto
    $ruta_fisica = $_SERVER['DOCUMENT_ROOT'];

    // Construye ruta fisica absoluta donde se descargaran las imagenes
    $ruta_imagenes_descargadas = $ruta_fisica . '/' . $proyecto . '/PowerPoints/' . $nombre_carpeta_imagenes;

    // Construye ruta fisica absoluta del archivo comprimido con las imagenes
    $ruta_zip = $ruta_fisica . '/' . $proyecto . '/PowerPoints/' . $nombre_carpeta_imagenes . '.zip';

    $sql = "SELECT * FROM  vw_competencia_promocion_pptx cp WHERE CAST(cp.fecha AS DATE) >= CAST('{$request->FechaInicial}' AS DATE) AND CAST(cp.fecha AS DATE) <= CAST('{$request->FechaFinal}' AS DATE) AND cp.idcadena = '{$request->idcadena}' AND cp.idempresa = '{$request->idempresa}' AND TRIM(LOWER(cp.generar_ppt)) = TRIM(LOWER('true')) ORDER BY cp.idcompetenciapromo DESC;";

    $mysql = new MysqlManager();

    $mysql->Bitacora($sql);

    $informacion = $mysql->Query($sql, SelectType::SELECT);

    if (sizeof($informacion) > 0) {

        $cccf = new ConstruyeCarpetaConFotos($ruta_imagenes_descargadas, $ruta_zip);

        $cccf->crearCarpetaParaGenerarFotos();

        foreach ($informacion as $elem) {
            $cccf->extraerSoloFotos($elem);
        }

        // Ruta url del archivo comprimido de las diapositivas
        $url_foto_generada = str_replace(array($ruta_fisica), array($dominio), $ruta_zip);

        echo $cccf->comprimirFotosEnZip($url_foto_generada, $ruta_imagenes_descargadas, $ruta_zip, $nombre_carpeta_imagenes);
    } else {
        echo json_encode(['idRes' => 1, 'Mensaje' => 'Por favor seleccione al menos un registro']);
    }
}
