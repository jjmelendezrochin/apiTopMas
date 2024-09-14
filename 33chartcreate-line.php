<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
$condicion = "";
$dias = [];
$cat = [];
$cat_grafica = [];
$arreglodatos = [];
$obj = []; 
date_default_timezone_set('America/Mexico_City');

//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('America/Mexico_City');
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** PHPExcel */
require 'database.php';
require_once 'Classes/PHPExcel.php';

$postdata = file_get_contents("php://input");
if(isset($postdata) && empty($postdata)){
/*Seccion de parametros de Testeo*/
$postdata = ['anio' => (isset($_GET['anio']))? $_GET['anio'] : 2020, 'mes' => (isset($_GET['mes']))? $_GET['mes'] : 9, 'producto' => (isset($_GET['producto']))? $_GET['producto'] : 1];

$postdata = json_encode($postdata);

/********************************+*/
}

if(isset($postdata) && !empty($postdata))
{

  // Extract the data.

  $request = json_decode($postdata);

  // Validate.

  if ((int)$request->anio < 1 || (int)$request->mes < 1  || (int)$request->producto < 1) {

    return http_response_code(400);

  }





  // Sanitize.

    

 $anio =  mysqli_real_escape_string($con, (int)$request->anio);                       
 $mes =  mysqli_real_escape_string($con, (int)$request->mes);                       
 $producto =  mysqli_real_escape_string($con, (int)$request->producto);                       

//$usuario = [];
$sql = "call proc_preciosproductos('{$anio}','{$mes}','{$producto}');";
//echo($sql);
//echo ('<br>');


         
if($result = mysqli_query($con,$sql))
{
  $i = 0;  
  $cols = mysqli_num_fields($result); //Obtiene el numero de campos en la tabla
  $rows = mysqli_num_rows($result);

     /*Asgina dias*/
	 	 $dias = $cols-2; 
		 
// Titulos 1a tabla
$arraycols = array(
    "Cadena"   => "Cadena");
	
	/*Agrega los dias a los titulos de la tabla*/
	for($d = 1; $d <= $dias; $d++){
	  $arraycols[$d] = $d;
	}
	
	array_push($arreglodatos,$arraycols); //Asigna titulos
  
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['cadena']   =   $row['cadena'];
    //$cat[$i]['producto']   =   $row['producto'];	  
	$col = 1; //Posiciona al primer dia del mes
	$c = 2; //Indice de la tabla donde inicia la numeracion de los dias	
	$miarreglo[$i] = [$row['cadena']]; //Añade la cadena 
    while($c < $cols){
	$cat[$i][$col] = $row[$col];
	$miarreglo[$i][$col] = $row[$col]; //Añade el dia
	$col++;	//Recorre los dias del mes solicitado
	$c++; //Recorre los campos de la tabla
	}
	array_push($arreglodatos,$miarreglo[$i]); //Asigna registros
    $i++;
  }

 $objPHPExcel = new PHPExcel();
$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Precio por Producto');



$objPHPExcel = new PHPExcel();
$objWorksheet = $objPHPExcel->getActiveSheet();
$objWorksheet->fromArray($arreglodatos);
/*$objWorksheet->fromArray(
	array(
		array('',	2010,	2011,	2012),
		array('Q1',   12,   15,		21),
		array('Q2',   56,   73,		86),
		array('Q3',   52,   61,		69),
		array('Q4',   30,   32,		0),
	)
);*/

/*Seccion de la grafica*/
/*Lista de letras de columnas en excel*/
$letras_columnas = ['B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA',
		   'AB','AC','AD','AE','AF'];

/*Variable de etiquetas (cadenas) */
$dataSeriesLabels = [];
$c = 0;
    /*Añade las etiquetas*/
    for($i = 2; $i <= $rows+1; $i++){
     array_push($dataSeriesLabels, new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A' . $i, NULL, $i));
	$c++;
	}
	
	$n_cols = $c;//Asigna numero de columnas en base al numero de cadenas
	
	/*Letra de principio de seleccion desde la primer fila y primer columna*/
	$letra_incial = '$B$1';
	 
	/*Letra de la columna de fin de seleccion de acuerdo al numero de dias -1*/
	$letra_final = '$' . $letras_columnas[$dias-1] . '$1'; 
   	
	/*Constuye el intervalo de columnas a seleccionar*/
	$intervalo_letras = $letra_incial . ':' . $letra_final; 


   /*Valores del eje de las x*/	
	$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!' . $intervalo_letras, NULL, $dias),	//	1 to 29, 30 o 31 dependiendo el mes
);

$c = 0;
$dataSeriesValues = [];
   /**/
   for($r = 2; $r <= $rows+1; $r++){	   
	$dataSeriesValues[$c] = new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$' . $r . ':$' . $letras_columnas[$dias-1] . '$' . $r, NULL, $dias);
	$c++;
	}

//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STACKED,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataSeriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);

//	Set the series in the plot area
$plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series));
//	Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, NULL, false);

$title = new PHPExcel_Chart_Title('Test Stacked Line Chart');
$yAxisLabel = new PHPExcel_Chart_Title('Value ($k)');


//	Create the chart
$chart = new PHPExcel_Chart(
	'chart1',		// name
	$title,			// title
	$legend,		// legend
	$plotArea,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	NULL,			// xAxisLabel
	$yAxisLabel		// yAxisLabel
);

//	Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('A20');
$chart->setBottomRightPosition('H23');

//	Add the chart to the worksheet
$objWorksheet->addChart($chart);

/*Fin de la seccion de la grafica*/

// Save Excel 2007 file
//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$nombre_corto = '/ReportePreciosXProductosMensualConGrafica/Exporta/precio_por producto_' . bin2hex(openssl_random_pseudo_bytes(8)) . '.xlsx';
$nombre_archivo = getcwd() . $nombre_corto;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save($nombre_archivo);
/*echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing file" , EOL;
echo 'File has been created in ' , getcwd() , EOL;*/
echo json_encode([["url" => 'http://' . $servidor . '/TopMas/ReportePreciosXProductosMensualConGrafica' . $nombre_corto]]);
}
}