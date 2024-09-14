<?php

require '../vendor/autoload.php';
require './curl_descarga_imagenes_para_powerpoints_y_fotos.php';

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Shape\Drawing;
use \PhpOffice\PhpPresentation\Shape\RichText\Paragraph;

class ConstruyePowerPointEnBaseAMachote {

    private $ruta_powerpoint_origen;
    private $ruta_powerpoint_generados;
    private $ruta_zip;
    private $presentacion_orig;
    private $presentacion_nueva;

    function __construct($ruta_powerpoint_origen, $ruta_powerpoint_generados, $ruta_zip) {
        $this->ruta_powerpoint_origen = $ruta_powerpoint_origen;
        $this->ruta_powerpoint_generados = $ruta_powerpoint_generados;
        $this->ruta_zip = $ruta_zip;
    }

    function obtienePowerPointMachote() {
        $this->presentacion_orig = IOFactory::load($this->ruta_powerpoint_origen);
    }

    function crearPowerPointNuevo() {
        // Crea una instancia de la clase PhpPresentation
        $this->presentacion_nueva = new PhpPresentation();
    }

    function crearCarpetaParaGenerarPowerpoints() {
        // Si no existe la carpeta la crea
        if (!file_exists($this->ruta_powerpoint_generados)) {
            mkdir($this->ruta_powerpoint_generados, 0777);
        }
    }

    function copiarLayout() {
        // Copia el layout
        $this->presentacion_nueva->setLayout($this->presentacion_orig->getLayout());
// Copia las propiedades del documento
        $this->presentacion_nueva->setDocumentProperties($this->presentacion_orig->getDocumentProperties());
// Copia las propiedades de la presentación
        $this->presentacion_nueva->setPresentationProperties($this->presentacion_orig->getPresentationProperties());
    }

    function construyeDiapositiva($elem, $ini) {

        // Remplaza en las dos images el http por el https
        $elem['foto_exhibicion'] = str_replace(array('http://'), array('https://'), $elem['foto_exhibicion']);
        $elem['foto_competencia'] = str_replace(array('http://'), array('https://'), $elem['foto_competencia']);

        // Extrae el codigo de imagen de exhibición
        $imagen_exhibición = 'data:image/png;base64, ' . base64_encode(descargarImagenes($elem['foto_exhibicion']));

        // Extrae el codigo de imagen de competencia
        $imagen_competencia = 'data:image/png;base64, ' . base64_encode(descargarImagenes($elem['foto_competencia']));

// Accede a la diapositiva original
        $slide_orig = $this->presentacion_orig->getActiveSlide();

// Crea una diapositiva utilizando un diseño predefinido
        $slide_nuevo = ($ini == true) ? $this->presentacion_nueva->getActiveSlide() : $this->presentacion_nueva->createSlide();

//Pone la imagen de fondo
        $imagen_fondo = new Image();
        $imagen_fondo->setPath('./fondo_alula_gold-min.png');
        $slide_nuevo->setBackground($imagen_fondo);

// Accede al shape original base para poner el titulo
        $shape_orig_titulo = $slide_orig->getShapeCollection()[0];

// Agrega el shape donde va el titulo
        $shape_nuevo_titulo = $slide_nuevo->createRichTextShape();

        $shape_nuevo_titulo->setOffsetX($shape_orig_titulo->getOffsetX());
        $shape_nuevo_titulo->setOffsetY($shape_orig_titulo->getOffsetY());
        $shape_nuevo_titulo->setWidthAndHeight($shape_orig_titulo->getWidth() + 100, $shape_orig_titulo->getHeight() + 10);
        $txtTitulo = $shape_nuevo_titulo->createTextRun($elem['tienda'] . ' / ' . $elem['idruta'] . ' / ' . $elem['ciudad'] . ' / ' . $elem['fecha_para_info']);
        $txtTitulo->getFont()->setName('Century Gothic');
        $txtTitulo->getFont()->setBold(true);
        $txtTitulo->getFont()->setSize(12);
        $txtTitulo->getFont()->setColor(new Color('FFFFFF'));

        /*         * ********************************************************************************************************************************************* */

        // Accede al shape original base para poner la información de la promoción
        $shape_orig_info = $slide_orig->getShapeCollection()[1];

        // Agrega el shape donde va la información de la promoción
        $shape_nuevo_info = $slide_nuevo->createRichTextShape();

        $shape_nuevo_info->setOffsetX($shape_orig_info->getOffsetX());
        $shape_nuevo_info->setOffsetY($shape_orig_info->getOffsetY());
        $shape_nuevo_info->setWidthAndHeight($shape_orig_info->getWidth(), $shape_orig_info->getHeight() + 100);

        $paragraph = array();

        $p1 = new Paragraph();
        $txtinfo1 = $p1->createTextRun('Participación del ' . $elem['porparticipacion'] . ' %');
        $p1->setAlignment($shape_orig_info->getParagraph(0)->getAlignment());
        $p1->setBulletStyle($shape_orig_info->getParagraph(0)->getBulletStyle());
        $txtinfo1->getFont()->setName('Century Gothic');
        $txtinfo1->getFont()->setSize(16);

        $p2 = new Paragraph();
        $txtinfo2 = $p2->createTextRun($elem['nofrentes'] . ' frentes');
        $p2->setAlignment($shape_orig_info->getParagraph(1)->getAlignment());
        $p2->setBulletStyle($shape_orig_info->getParagraph(1)->getBulletStyle());
        $txtinfo2->getFont()->setName('Century Gothic');
        $txtinfo2->getFont()->setSize(16);

        $p3 = new Paragraph();
        $txtinfo3 = $p3->createTextRun($elem['conosinparticipacion_ppt'] . ' Promoción');
        $p3->setAlignment($shape_orig_info->getParagraph(2)->getAlignment());
        $p3->setBulletStyle($shape_orig_info->getParagraph(2)->getBulletStyle());
        $txtinfo3->getFont()->setName('Century Gothic');
        $txtinfo3->getFont()->setSize(16);

        $p4 = new Paragraph();
       // $txtinfo4 = $p4->createTextRun($elem['pordescuento'] . ' % de descuento');
        $txtinfo4 = $p4->createTextRun('Precio: '.$elem['precio'] );
        $p4->setAlignment($shape_orig_info->getParagraph(3)->getAlignment());
        $p4->setBulletStyle($shape_orig_info->getParagraph(3)->getBulletStyle());
        $txtinfo4->getFont()->setName('Century Gothic');
        $txtinfo4->getFont()->setSize(16);

        $p5 = new Paragraph();
        $txtinfo5 = $p5->createTextRun('Comentarios:');
        $p5->setAlignment($shape_orig_info->getParagraph(4)->getAlignment());
        $p5->setBulletStyle($shape_orig_info->getParagraph(4)->getBulletStyle());
        $txtinfo5->getFont()->setName('Century Gothic');
        $txtinfo5->getFont()->setSize(16);

        $p6 = new Paragraph();
        $txtinfo6 = $p6->createTextRun();
        $p6->setAlignment($shape_orig_info->getParagraph(5)->getAlignment());
        $txtinfo6->getFont()->setName('Century Gothic');
        $txtinfo6->getFont()->setSize(16);

        $p7 = new Paragraph();
        $txtinfo7 = $p7->createTextRun($elem['comentarios']);
        $p7->setAlignment($shape_orig_info->getParagraph(5)->getAlignment());
        $txtinfo7->getFont()->setName('Century Gothic');
        $txtinfo7->getFont()->setSize(16);

        array_push($paragraph, $p1);
        array_push($paragraph, $p2);
        array_push($paragraph, $p3);
        array_push($paragraph, $p4);
        array_push($paragraph, $p5);
        array_push($paragraph, $p6);
        array_push($paragraph, $p7);

        $shape_nuevo_info->setParagraphs($paragraph);

        /*         * ********************************************************************************************************************************************* */

// Accede al shape original base para poner el titulo de la foto de exhibición
        $shape_orig_titulo_fe = $slide_orig->getShapeCollection()[2];

// Agrega el shape donde va el titulo foto de exhibición
        $shape_nuevo_titulo_fe = $slide_nuevo->createRichTextShape();

        $shape_nuevo_titulo_fe->setOffsetX($shape_orig_titulo_fe->getOffsetX());
        $shape_nuevo_titulo_fe->setOffsetY($shape_orig_titulo_fe->getOffsetY());
        $shape_nuevo_titulo_fe->setWidthAndHeight($shape_orig_titulo_fe->getWidth(), $shape_orig_titulo_fe->getHeight() + 10);
        $txtTitulofe = $shape_nuevo_titulo_fe->createTextRun($shape_orig_titulo_fe->getPlainText());
        $txtTitulofe->getFont()->setName('Century Gothic');
        $txtTitulofe->getFont()->setSize(16);
        $shape_nuevo_titulo_fe->getParagraph()->setBulletStyle($shape_orig_titulo_fe->getParagraph()->getBulletStyle());

        /*         * ********************************************************************************************************************************************* */

// Accede al shape original base para poner el titulo de la foto de competencia
        $shape_orig_titulo_comp = $slide_orig->getShapeCollection()[3];

// Agrega el shape donde va el titulo foto de competencia
        $shape_nuevo_titulo_comp = $slide_nuevo->createRichTextShape();

        $shape_nuevo_titulo_comp->setOffsetX($shape_orig_titulo_comp->getOffsetX());
        $shape_nuevo_titulo_comp->setOffsetY($shape_orig_titulo_comp->getOffsetY());
        $shape_nuevo_titulo_comp->setWidthAndHeight($shape_orig_titulo_comp->getWidth(), $shape_orig_titulo_comp->getHeight() + 10);
        $txtTitulocomp = $shape_nuevo_titulo_comp->createTextRun($shape_orig_titulo_comp->getPlainText());
        $txtTitulocomp->getFont()->setName('Century Gothic');
        $txtTitulocomp->getFont()->setSize(16);
        $shape_nuevo_titulo_comp->getParagraph()->setBulletStyle($shape_orig_titulo_comp->getParagraph()->getBulletStyle());

        /*         * ********************************************************************************************************************************************* */

// Agrega el shape donde va la foto de exhibición
        $shape_nueva_img_e = new Drawing\Base64();

        $shape_nueva_img_e->setName('Foto de exhibición')
                ->setDescription('Exhibición')
                ->setData($imagen_exhibición)
                ->setResizeProportional(false)
                ->setOffsetX(20)
                ->setOffsetY(200)
                ->setWidth(430)
                ->setHeight(450);

        $slide_nuevo->addShape($shape_nueva_img_e);

        /*         * ********************************************************************************************************************************************* */

// Agrega el shape donde va la foto de competencia
        $shape_nueva_img_comp = new Drawing\Base64();

        $shape_nueva_img_comp->setName('Foto de competencia')
                ->setDescription('Competencia')
                ->setData($imagen_competencia)
                ->setResizeProportional(false)
                ->setOffsetX(470)
                ->setOffsetY(200)
                ->setWidth(430)
                ->setHeight(450);

        $slide_nuevo->addShape($shape_nueva_img_comp);
    }

    function guardarPowerPoint($nombre_archivo) {
// Guarda la presentación en un archivo
        $writer = IOFactory::createWriter($this->presentacion_nueva, 'PowerPoint2007');
        $writer->save($this->ruta_powerpoint_generados . '/' . $nombre_archivo . '.pptx');
    }

    function comprimirPowerPointEnZip($url_diapositiva_generada, $ruta_diapositiva_generada, $ruta_zip, $nombre_carpeta_diapositivas) {
        // Crear el zip
// Crea una nueva instancia de ZipArchive
        $zip = new ZipArchive();

        if ($zip->open($this->ruta_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            // Obtener la lista de archivos y carpetas en la ruta especificada
            $archivos = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($this->ruta_powerpoint_generados),
                    RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($archivos as $archivo) {
                // Obtener la ruta del archivo relativa a la carpeta raíz
                $rutaArchivo = $archivo->getRealPath();
                $archivoRelativo = substr($rutaArchivo, strlen($this->ruta_powerpoint_generados) + 1);
                // Agregar el archivo al archivo zip con su ruta relativa
                if (is_file($rutaArchivo)) {
                    $zip->addFile($rutaArchivo, $archivoRelativo);
                }
            }

            // Cierra el archivo ZIP
            $zip->close();
            return json_encode(['idRes' => 0, 'Mensaje' => 'El proceso de compresion termino de forma satisfactoria', 'url' => $url_diapositiva_generada, 'ruta_fisica_carpeta' => $ruta_diapositiva_generada, 'ruta_fisica_zip' => $ruta_zip, 'nombre_zip' => $nombre_carpeta_diapositivas . '.zip']);
        } else {
            return json_encode(['idRes' => 1, 'Mensaje' => 'No se pudo generar el comprimido']);
        }
    }

}
