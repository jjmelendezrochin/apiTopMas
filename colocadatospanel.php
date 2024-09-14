<?php
/**
 * Regresa el usuario.
 */
require 'database.php';

date_default_timezone_set('America/Mexico_City');

// *****************************************************
// Este procedimiento se debe de ejecutar cada 5 minutos
// *****************************************************
//  Consulta la lista de empesas activas
$sql = "Select idempresa from cat_empresa where idEstatus = 1 order by 1;";
echo $sql . '<br>';
$res = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($res))
{
	// Procedimiento que llena tablas temporales rep_fecha, rep_fecha_dia, rep_fechas
	$idempresa     =   $row['idempresa'];	
	$sql1 = 'call Proc_Reporte_Todos_0((cast(current_timestamp() as Date)),(cast(current_timestamp() as Date)),0,0, ' . $idempresa. ');';
	echo $sql1 . '<br>';
	mysqli_query($con,$sql1);
	
	// Procedimiento que llena datos del panel	
	$sql2 = 'Call Proc_Llenadatospanel(' . $idempresa. ');';
	echo $sql2 . '<br>';
	mysqli_query($con,$sql2);
    $j++;
}

// *****************************************************
// Borra wwtopm_topmkt.empresa_cadena que no se requieran
$sql3 = 'Delete from wwtopm_topmkt.empresa_cadena
where id not in (Select id from Vw_idempresa_idcadena_id);';
mysqli_query($con,$sql3);
// *****************************************************
?>