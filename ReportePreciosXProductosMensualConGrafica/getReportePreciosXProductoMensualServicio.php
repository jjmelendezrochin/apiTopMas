<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


require '../database.php';
require '../conversion_array_filas_cols.php';


$condicion = "";
$dias = [];
$cat = [];
$cat_grafica = [];
$obj = []; 

date_default_timezone_set('America/Mexico_City');



// Get the posted data.

$postdata = file_get_contents("php://input");

if(isset($postdata) && empty($postdata)){
/*Seccion de parametros de Testeo*/
//$postdata = ['anio' => (isset($_GET['anio']))? $_GET['anio'] : 2020, 'mes' => (isset($_GET['mes']))? $_GET['mes'] : 9, 'producto' => (isset($_GET['producto']))? $_GET['producto'] : 1];

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
$idEmpresa =  mysqli_real_escape_string($con, (int)$request->idEmpresa);

//$usuario = [];
if(intval($idEmpresa) > 0){
   $condicion = "_" . $idEmpresa;	
}

$sql = "call proc_preciosproductos_0 ('{$anio}','{$mes}','{$producto}',{$idEmpresa});";
         
if($result = mysqli_query($con,$sql))
{
  $i = 0;  
  $cols = mysqli_num_fields($result); //Obtiene el numero de campos en la tabla
  $rows = mysqli_num_rows($result);
  
  $col = 0;
  
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['cadena']   =   $row['cadena'];
    //$cat[$i]['producto']   =   $row['producto'];	  
	$col = 1; //Posiciona al primer dia del mes
	$c = 2; //Indice de la tabla donde inicia la numeracion de los dias
	while($c < $cols){
	$cat[$i][$col] = $row[$col];
	$col++;	//Recorre los dias del mes solicitado
	$c++; //Recorre los campos de la tabla
	}
    $i++;
  }
  
  if(intval($col) > 0){
     /*Asgina dias*/
	 	 $dias = $col-1; 
    
	/*Aqui se invoca el metodo de conversion de filas a columnas*/
  	$cat_grafica = conversion_filasACols($cat,$dias);
	
	/*Aqui se registran los dias como ultimo registro en el arreglo $cat (Auxliar para la construccion de los encabezados dinamicos en archivo HTML)*/
	$cat[]['dias'] = $col-1;
	
  }else{
    $cat[]['dias'] = $col;
  }
	/*Se añade el arreglo $cat a el arreglo $obj*/
	$obj = [['tabla' => $cat, 'grafica' => $cat_grafica, 'filas' => $rows]];
  echo json_encode($obj);
}
else
{
  $i = 0;
  ///  $cat[$i]['sql']      =  $sql;        
    echo $sql;    
 // http_response_code(404);
}
}
?>