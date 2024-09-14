<?php
/**
 * Regresa el usuario.
 */
require '../database.php';

//date_default_timezone_set('America/Mexico_City');

$vw = "";
$condicion = "(Select cfg.valor from configuracion cfg where cfg.idconf = 2)";
$cat = [];
// Obteniendo la empresa
$idEmpresa = ($_GET['idEmpresa'] !== null && strlen($_GET['idEmpresa']) > 0)? mysqli_real_escape_string($con, (string)$_GET['idEmpresa']) : 0;


//$fecha=date('Y/m/d');

/*if($cadena){
$condicion = "and cadena like'{$cadena}%'";
}*/

//$usuario = [];
if(intval($idEmpresa) > 0){
	$vw = "_" . $idEmpresa;
	$condicion = $idEmpresa;
}

$sql = 'Select count(*) as Cta
from
(
Select distinct Concat(cp.nombre, " ", cp.apellidos) as Nombre, \'N/A\' as Operacion,
"-" as Fecha, "-" as Hora
from vw_rutas_promotor_dias'. $vw .'  rpd inner join cat_promotor cp
on rpd.idpromotor = cp.idpromotor
where 
  (CASE 
    WHEN DAYOFWEEK(CURDATE()) = 1 THEN rpd.domingo=1
    WHEN DAYOFWEEK(CURDATE()) = 2 THEN rpd.lunes=1
    WHEN DAYOFWEEK(CURDATE()) = 3 THEN rpd.martes=1
    WHEN DAYOFWEEK(CURDATE()) = 4 THEN rpd.miercoles=1
    WHEN DAYOFWEEK(CURDATE()) = 5 THEN rpd.jueves=1
    WHEN DAYOFWEEK(CURDATE()) = 6 THEN rpd.viernes=1
    WHEN DAYOFWEEK(CURDATE()) = 7 THEN rpd.sabado=1
   END)
and rpd.idpromotor not in 
(Select idpromotor from vw_ranking_promotor_actividad_hora' . $vw . ') 
and cp.idempresa = ' . $condicion . '
) as a';
//echo($sql);
//echo ('<br>');
if($result = mysqli_query($con,$sql))
{
    $i=0;
  while($row = mysqli_fetch_assoc($result))
  {
    $cat[$i]['usuarios_sin_actividad']     =   $row['Cta'];
    $i++;
  }

  echo json_encode($cat);
}
else
{
  http_response_code(404);
}
?>