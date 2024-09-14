<?php

require './curl_descarga_imagenes_para_powerpoints_y_fotos.php';

class ConstruyeCarpetaConFotos {

    private $ruta_imagenes_descargadas;
    private $ruta_zip;

    function __construct($ruta_imagenes_descargadas, $ruta_zip) {
        $this->ruta_imagenes_descargadas = $ruta_imagenes_descargadas;
        $this->ruta_zip = $ruta_zip;
    }

    function crearCarpetaParaGenerarFotos() {
        // Si no existe la carpeta la crea
        if (!file_exists($this->ruta_imagenes_descargadas)) {
            mkdir($this->ruta_imagenes_descargadas, 0777);
        }
    }

    function extraerSoloFotos($elem) {
        // Remplaza el http por el https en la foto de exhibición
        $elem['foto_exhibicion'] = str_replace(array('http:'), array('https:'), $elem['foto_exhibicion']);

        // Extrae extención de foto de exhibición
        $extencion_foto_exhibicion = pathinfo(basename($elem['foto_exhibicion']), PATHINFO_EXTENSION);

        // Remplaza el http por el https en la foto de competencia;
        $elem['foto_competencia'] = str_replace(array('http:'), array('https:'), $elem['foto_competencia']);

        // Extrae extención de foto de competencia
        $extencion_foto_competencia = pathinfo(basename($elem['foto_competencia']), PATHINFO_EXTENSION);

        // Extrae el codigo de foto de exhibición
        $imagen_exhibición = descargarImagenes($elem['foto_exhibicion']);

        // Extrae el codigo de foto de competencia
        $imagen_competencia = descargarImagenes($elem['foto_competencia']);

        // Guardar contenido de la foto de exhibición en la ruta solicitada
        file_put_contents($this->ruta_imagenes_descargadas . '/' . $elem['idfoto'] . '_' . $elem['tienda'] . '_' . $elem['fecha_para_concatenar_en_url'] . '.' . $extencion_foto_exhibicion, $imagen_exhibición);

        // Guardar contenido de la foto de competencia en la ruta solicitada
        file_put_contents($this->ruta_imagenes_descargadas . '/' . $elem['idfoto1'] . '_' . $elem['tienda'] . '_' . $elem['fecha_para_concatenar_en_url'] . '.' . $extencion_foto_competencia, $imagen_competencia);
    }

    function comprimirFotosEnZip($url_foto_generada, $ruta_foto_generada, $ruta_zip, $nombre_carpeta_fotos) {
        // Crear el zip
// Crea una nueva instancia de ZipArchive
        $zip = new ZipArchive();

        if ($zip->open($this->ruta_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            // Obtener la lista de archivos y carpetas en la ruta especificada
            $archivos = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($this->ruta_imagenes_descargadas),
                    RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($archivos as $archivo) {
                // Obtener la ruta del archivo relativa a la carpeta raíz
                $rutaArchivo = $archivo->getRealPath();
                $archivoRelativo = substr($rutaArchivo, strlen($this->ruta_imagenes_descargadas) + 1);
                // Agregar el archivo al archivo zip con su ruta relativa
                if (is_file($rutaArchivo)) {
                    $zip->addFile($rutaArchivo, $archivoRelativo);
                }
            }

            // Cierra el archivo ZIP
            $zip->close();
            return json_encode(['idRes' => 0, 'Mensaje' => 'El proceso de compresion termino de forma satisfactoria', 'url' => $url_foto_generada, 'ruta_fisica_carpeta' => $ruta_foto_generada, 'ruta_fisica_zip' => $ruta_zip, 'nombre_zip' => $nombre_carpeta_fotos . '.zip']);
        } else {
            return json_encode(['idRes' => 1, 'Mensaje' => 'No se pudo generar el comprimido']);
        }
    }

}
