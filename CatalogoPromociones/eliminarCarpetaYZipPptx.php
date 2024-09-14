<?php

ini_set("upload_max_filesize", "1024M");
ini_set("post_max_size", "1024M");
ini_set("max_execution_time", 3000);
ini_set("max_input_time", 3000);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Get the posted data.
$postdata = file_get_contents("php://input");

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $request = json_decode($postdata);

    $ruta_fisica_carpeta = $request->ruta_fisica_carpeta;
    $ruta_fisica_zip = $request->ruta_fisica_zip;

    if (file_exists($ruta_fisica_carpeta)) {

        chmod($ruta_fisica_carpeta, 0777);
        // Obtener la lista de archivos y carpetas en la ruta especificada
        $archivos = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($ruta_fisica_carpeta),
                RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($archivos as $archivo) {
            // Obtener la ruta del archivo relativa a la carpeta raíz
            $rutaArchivo = $archivo->getRealPath();

            if (is_file($rutaArchivo)) {
                chmod($rutaArchivo, 0777);
                unlink($rutaArchivo);
            }
        }
        rmdir($ruta_fisica_carpeta);
    }

    if (file_exists($ruta_fisica_zip)) {
        chmod($ruta_fisica_zip, 0777);
        unlink($ruta_fisica_zip);
    }

    echo json_encode(['idRes' => 0, 'Mensaje' => 'Se realizo el borrado de forma satisfactoria']);
}