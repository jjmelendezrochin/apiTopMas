<?php 

/*Seccion de prueba*/
/*$arr = [];


$arr[0]['cadena'] = 'cadena1';
$arr[0]['1'] = '12.5';
$arr[0]['2'] = '2.2';
$arr[0]['3'] = '6.2';
$arr[0]['4'] = '4.2';


$arr[1]['cadena'] = 'cadena2';
$arr[1]['1'] = '11.5';
$arr[1]['2'] = '3.2';
$arr[1]['3'] = '4.2';
$arr[1]['4'] = '1.2';


echo json_encode(conversion_filasACols($arr,[["dia"=>"1"],["dia"=>"2"],["dia"=>"3"],["dia"=>"4"]]));*/

function conversion_filasACols($arr,$dias){
	$preciosXCadena = []; //Variable tipo arreglo que se usara para construir el arreglo convertifo de filas a columnas
	$enc_arr = []; //Variable tipo arreglo donde se almacenaran las cadenas extraidas
	for($i = 0; $i < sizeof($arr); $i++){
		$enc_arr['cadena' . $i] = $arr[$i]['cadena']; //añade cadena al arreglo
	}

	$preciosXCadena[] = $enc_arr; //Agrega las cadenas en el primer registro
	
	/*Aqui realiza la rutina de recorrer los dias y meterlos en el arreglo $preciosXCadena pero 
	a modo de introduciar cada valor en una fila distinta*/
	for($i = 0; $i < sizeof($arr); $i++){//Recorre el arreglo original
	for($d = 1; $d <= $dias; $d++){//Recorre los dias 
	   	$preciosXCadena[$d]['cadena' . $i] = $arr[$i][$d];//Asigna el precio por cada cadena (contruye filas)
	 }
	}
	
  return $preciosXCadena;		
}

?>