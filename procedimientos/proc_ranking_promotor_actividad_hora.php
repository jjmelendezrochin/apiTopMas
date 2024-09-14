<?php 
function proc_ranking_promotor_actividad_hora($idempresa){
$con1 = connect();
    
	$sql_d = "DROP PROCEDURE IF EXISTS wwtopm_topmkt.proc_ranking_promotor_actividad_hora_" . $idempresa . ";";
	 
	$sql = "CREATE PROCEDURE wwtopm_topmkt.proc_ranking_promotor_actividad_hora_" . $idempresa . "()
    NO SQL
Select  Nombre, Operacion, Fecha, Hora 
from vw_ranking_promotor_actividad_hora_" . $idempresa . "
Union
Select Concat(cp.nombre, \" \", cp.apellidos) as Nombre, 'N/A' as Operacion,
\"-\" as Fecha, \"-\" as Hora
from vw_rutas_promotor_dias_" . $idempresa . " rpd inner join cat_promotor cp
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
(Select idpromotor from vw_ranking_promotor_actividad_hora_" . $idempresa .") 
and cp.idempresa = " . $idempresa . " 
order by 4 desc, 1 asc;";

  if(mysqli_query($con1,$sql_d)) 
  {
	  echo "Procedimiento almacenado 'proc_ranking_promotor_actividad_hora_" . $idempresa . "' eliminado<br>";
  }
  
  if(mysqli_query($con1,$sql)) 
  {
	  mysqli_close($con1);
	  return "Procedimiento almacenado 'proc_ranking_promotor_actividad_hora_" . $idempresa . "' creado<br>";  
  }
}

?>