<?php
require '../database.php';


   // Create.
  $sql_drop = "DROP PROCEDURE IF EXISTS topmasmx_topmas.proc_reportecadenas;";
  $sql = "CREATE PROCEDURE topmasmx_topmas.`proc_reportecadenas`(
    IN sFechaIni VARCHAR(10),
    sFechaFin VARCHAR(10),
    idcadena INT
)
BEGIN
    DECLARE Instruccion VARCHAR(1000); 
    DECLARE condfecha VARCHAR(100); 
    DECLARE condcadena VARCHAR(100); 
    DECLARE orden VARCHAR(100);
    
    SET
        @Instruccion = \"
Select ifnull(a.Fecha ,'N/A') Fecha, ifnull(a.Promotor,'N/A') Promotor, v.formato, v.Tienda, ifnull(a.Actividad,'N/A') Actividad, ifnull(a.FechaHora,'N/A') FechaHora, 
ifnull(a.Distancia_m,'N/A') Distancia_m, v.nombrecorto 
from vw_cadena_formato_ruta v
left join (    
  Select  cast(f.FechaHora as date)  as Fecha, 
  Concat(p.nombre, ' ', p.apellidos) as Promotor,
  fo.formato, r.Tienda,
  o.descripcion AS Actividad, 
  FechaHora,
  concat(Fn_DistanciaEntreLatLongs(f.latitud,f.longitud,r.latitud,r.longitud),' metros') AS Distancia_m,
  c.nombrecorto, c.idcadena, fo.idformato, r.idruta
  from photos f 
  inner join cat_promotor p on f.idpromotor=p.idpromotor
  inner join cat_rutas r on f.idruta = r.idruta
  inner join cat_formato fo on r.idformato = fo.idformato 
  inner join cat_cadena c on r.idcadena = c.idcadena
  inner join cat_operacion o on f.idoperacion = o.idoperacion ";
    
    // SET @condfecha = "CONCAT(' where Cast(f.FechaHora as Date) between  '," . sFechaIni . "' and '" . sFechaFin . "')";
        

    //SET @Instruccion1 = ") as a on v.idcadena = a.idcadena and v.idformato = a.idformato and v.idruta = a.idruta ";


    //IF(idcadena > 0) THEN
    //        SET @condcadena = CONCAT(\" and v.idcadena = \", CAST(idcadena AS CHAR)); 
    //ELSE
    //    SET @condcadena = \"\";
    //END IF;


//SET @orden.= \" order by cast(FechaHora as date)  asc\";
//SET @Instruccion = CONCAT(@Instruccion, \" \", @condfecha, \" \", @Instruccion, \" \",@condcadena,\" \",@orden);
    
$sql = "    
PREPARE
    Ins
FROM
    @Instruccion;
EXECUTE
    Ins;
DEALLOCATE
PREPARE
    Ins;
END;
    Ins;
END;
;";
  
  echo $sql;

//  if(mysqli_query($con,$sql_drop))
//  {   
//    echo "Procedimiento proc_reportecadenas eliminado";
//  }
//  else
//  {
//    echo $sql;
//  }
//
//  if(mysqli_query($con,$sql))
//  {   
//    echo "Procedimiento proc_reportecadenas creado";
//  }
//  else
//  {
//    echo $sql;
//  }

?>