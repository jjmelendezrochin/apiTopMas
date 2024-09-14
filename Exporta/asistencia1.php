<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('America/Mexico_City');

/** PHPExcel */
require_once '../Classes/PHPExcel.php';
require_once '../database.php';

$arreglodatos=[];
$arreglodatos1=[];

$objPHPExcel = new PHPExcel();
$objWorksheet = $objPHPExcel->getActiveSheet()->setTitle('Asistencia');
$objWorkSheet1 = $objPHPExcel->createSheet();
$objWorkSheet1->setTitle('Efectividad');

$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

$nombre_corto = '/asistencia_' . bin2hex(openssl_random_pseudo_bytes(8)) . '.xlsx';
$nombre_archivo = getcwd() . $nombre_corto;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save($nombre_archivo);

//$objPHPExcel->getActiveSheet()->setTitle("Asistencia"); // Si se cambia el nombre de la hoja la grafica no se muestra
// *********************************
// Titulos 1a tabla
$arraycols = array(
    "Cadena"   => "Cadena",
    "Objetivo" => "Objetivo",    
    "Checkin"  => "Checkin",
    "Checkout" => "Checkout",
);
array_push($arreglodatos,$arraycols);
// *********************************
// Titulos 2a tabla
$arraycols1 = array(
    "Cadena"   => "Cadena",
    "Checkin"  => "% Checkin",
    "Checkout" => "% Checkout",
    "Efectividad" => "% Efectividad",    
);
array_push($arreglodatos1,$arraycols1);

// *********************************
// Consulta los datos de la tabla1
$sql = "Select Cadena, `El Objetivo` Objetivo, `Cta Checkin` Checkin, `Cta Checkout` Checkout from tmp_prueba where not Cadena is null;";
if($result = mysqli_query($con,$sql))
{
  $i = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $miarreglo[$i]=[$row['Cadena'],$row['Objetivo'],$row['Checkin'],$row['Checkout']];
    array_push($arreglodatos,$miarreglo[$i]);
    $i++;
  }
} 
// *********************************
// Consulta los datos de la tabla2
//$sql = "Select Cadena, `El Objetivo` Objetivo, `Cta Checkin` Checkin, `Cta Checkout` Checkout from tmp_prueba;";
$sql = "Select Cadena, `% Checkin` Checkin, `% Checkout` Checkout, `% Efectividad` Efectividad from tmp_prueba_efectividad where not Cadena is null;";
if($result = mysqli_query($con,$sql))
{
  $k = 0;
  while($row = mysqli_fetch_assoc($result))
  {
    $miarreglo1[$k]=[$row['Cadena'],$row['Checkin'],$row['Checkout'],$row['Efectividad']];
    array_push($arreglodatos1,$miarreglo1[$k]);
    $k++;
  }
} 
// *********************************

$longitud = count($arreglodatos);
//echo 'Longitud arreglo ' . $longitud . '<br>';
$longitud1 = count($arreglodatos1);
//echo 'Longitud arreglo ' . $longitud1 . '<br>';
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
$objWorksheet->fromArray($arreglodatos);
$objWorksheet1 = $objPHPExcel->setActiveSheetIndex(1);
$objWorksheet1->fromArray($arreglodatos1);

//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesLabels = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Asistencia!$B$1', NULL, 1),	//	Objetivo
	new PHPExcel_Chart_DataSeriesValues('String', 'Asistencia!$C$1', NULL, 1),	//	Checkin
	new PHPExcel_Chart_DataSeriesValues('String', 'Asistencia!$D$1', NULL, 1),	//	Checkout
);
$dataSeriesLabels1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Efectividad!$B$1', NULL, 1),	//	Objetivo
	new PHPExcel_Chart_DataSeriesValues('String', 'Efectividad!$C$1', NULL, 1),	//	Checkin
	new PHPExcel_Chart_DataSeriesValues('String', 'Efectividad!$D$1', NULL, 1),	//	Checkout
);
//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Asistencia!$A$2:$A$10', NULL, 4),	//	Q1 to Q4
);
$xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'Efectividad!$A$2:$A$10', NULL, 4),	//	Q1 to Q4
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesValues = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Asistencia!$B$2:$B$10', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Asistencia!$C$2:$C$10', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Asistencia!$D$2:$D$10', NULL, 4),
);
$dataSeriesValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Efectividad!$B$2:$B$10', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Efectividad!$C$2:$C$10', NULL, 4),
	new PHPExcel_Chart_DataSeriesValues('Number', 'Efectividad!$D$2:$D$10', NULL, 4),
);
//	Build the dataseries
$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataSeriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);
$series1 = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues1)-1),			// plotOrder
	$dataSeriesLabels1,								// plotLabel
	$xAxisTickValues1,								// plotCategory
	$dataSeriesValues1								// plotValues
);
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
$series->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
$series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

//	Set the series in the plot area
$plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series));
$plotArea1 = new PHPExcel_Chart_PlotArea(NULL, array($series1));
//	Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

$title = new PHPExcel_Chart_Title('Gráfica de Asistencia');
$title1 = new PHPExcel_Chart_Title('Gráfica de Efectividad');

//	Create the chart
$chart = new PHPExcel_Chart(
	'chart1',		// name
	$title,			// title
	$legend,		// legend
	$plotArea,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	null,			// xAxisLabel
	null		// yAxisLabel
);

$chart1 = new PHPExcel_Chart(
	'chart2',		// name
	$title1,			// title
	$legend1,		// legend
	$plotArea1,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	null,			// xAxisLabel
	null		// yAxisLabel
);
//	Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('A12');
$chart->setBottomRightPosition('G26');
$chart1->setTopLeftPosition('A12');
$chart1->setBottomRightPosition('G26');

//	Add the chart to the worksheet
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
$objWorksheet->addChart($chart);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
cellColor('A1', 'D7D7D7');
cellColor('B1', 'D7D7D7');
cellColor('C1', 'D7D7D7');
cellColor('D1', 'D7D7D7');
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');

$objWorksheet1 = $objPHPExcel->setActiveSheetIndex(1);
$objWorksheet1->addChart($chart1);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
cellColor('A1', 'D7D7D7');
cellColor('B1', 'D7D7D7');
cellColor('C1', 'D7D7D7');
cellColor('D1', 'D7D7D7');
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');

// Colocando la hoja 1 nuevamente como hoja activa
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

$objWriter->save($nombre_archivo);
echo json_encode(["url" => 'https://' . $servidor . '/TopMas/Exporta' . $nombre_corto]);  // Envìa el resultado

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}