<?php
/**
 * Regresa el usuario.
 */
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



//if(isset($postdata) && !empty($postdata))

{

  // Extract the data.

  //$request = json_decode($postdata);




/*
  // Validate.

  if ((int)$request->anio < 1 || (int)$request->mes < 1  || (int)$request->producto < 1) {

    return http_response_code(400);

  }
*/




  // Sanitize.

    

 $anio =  2020;                       
 $mes =  10;                       
 $producto =  9;                       


//$usuario = [];
$sql = "call proc_preciosproductos('{$anio}','{$mes}','{$producto}');";
//echo($sql);
//echo ('<br>');


         
if($result = mysqli_query($con,$sql))
{
  $i = 0;  
  $cols = mysqli_num_fields($result); //Obtiene el numero de campos en la tabla
  $rows = mysqli_num_rows($result);
  
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
     /*Asgina dias*/
	 	 $dias = $col-1; 
    
	/*Aqui se invoca el metodo de conversion de filas a columnas*/
  	$cat_grafica = conversion_filasACols($cat,$dias);
	
	/*Aqui se registran los dias como ultimo registro en el arreglo $cat (Auxliar para la construccion de los encabezados dinamicos en archivo HTML)*/
	$cat[]['dias'] = $col-1;
	
	
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