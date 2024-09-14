<?php 
function proc_reporte_todos($idempresa){
$con1 = connect();
    
	$sql_d = "DROP PROCEDURE IF EXISTS wwtopm_topmkt.Proc_Reporte_Todos_" . $idempresa . ";";
	 
	$sql = "CREATE PROCEDURE wwtopm_topmkt.`Proc_Reporte_Todos_" . $idempresa . "`(
IN FechaIni Date ,
IN FechaFin Date,
IN idpromotor int,
IN idcadena   int
)
BEGIN
DECLARE vFecha Date;
DECLARE vFechaSig Date;
DECLARE vCond int;
DECLARE pFecha Date;
DECLARE done BOOLEAN DEFAULT FALSE;

Declare CURSORFechas CURSOR FOR 
Select * from rep_fecha_dia order by 1;

DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = TRUE;

set vCond = 1;

-- Truncado e insercion de tabla
Truncate table rep_fecha;
Truncate table rep_fecha_dia;
Truncate table rep_fechas;

Insert into rep_fecha (FechaIni, FechaFin) values(FechaIni, FechaFin);

SET vFechaSig = FechaIni;
WHILE vCond>0 DO
  if (vFechaSig<=FechaFin) then
    begin
      Insert into rep_fecha_dia(FechaDia) values (vFechaSig);
      set vFechaSig = (Select DATE_ADD(vFechaSig, INTERVAL 1 DAY));
      set vCond = 1;
    end;
  else
    set vCond = 0;
  end if;
END WHILE;

-- Consulta de fechas
-- Select * from rep_fecha_dia;

  OPEN CURSORFechas;
		
		REPEAT
			FETCH CURSORFechas INTO pFecha;
      
			  if(idpromotor=0 and idcadena=0) then
			      insert into rep_fechas(idcadena, idruta, idpromotor, Fecha)
            Select cr.idcadena, rpd.idruta, rpd.idpromotor, pFecha AS Fecha
			      from rutas_promotor_dias rpd
            inner join rutas_promotor rp
            on rpd.idpromotor = rp.idpromotor and rpd.idruta = rp.idruta and rp.idestatus = 1
			      left join cat_promotor cp on rpd.idpromotor = cp.idpromotor
			      left join cat_rutas cr on rpd.idruta = cr.idruta 
			      where 
			      (
			      case when (dayofweek(pFecha) = 1) then (`rpd`.`domingo` = 1) when (dayofweek(pFecha) = 2) then (`rpd`.`lunes` = 1) when (dayofweek(pFecha) = 3) then (`rpd`.`martes` = 1) when (dayofweek(pFecha) = 4) then (`rpd`.`miercoles` = 1) when (dayofweek(pFecha) = 5) then (`rpd`.`jueves` = 1) when (dayofweek(pFecha) = 6) then (`rpd`.`viernes` = 1) when (dayofweek(pFecha) = 7) then (`rpd`.`sabado` = 1) end
			      ) 
			      and cp.tipo >= 0 and cp.idestatus = 1
            and (Select count(*) from cat_promotor cp1 where cp1.idpromotor = rpd.idpromotor and cp1.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0
            and cp.idempresa = " . $idempresa . "            
            Union
            Select distinct cc.idcadena, p.idruta, p.idpromotor, pFecha AS Fecha 
            from photos p 
            inner join cat_rutas cr on p.idruta = cr.idruta
            inner join cat_cadena cc on cr.idcadena = cc.idcadena
            where Cast(p.FechaHora as date) = pFecha
            and p.idpromotor in (Select idpromotor from cat_promotor where tipo = 1 and idEstatus = 1 and idempresa = " . $idempresa . ") 
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0;           
			  elseif (idpromotor > 0 and idcadena = 0) then
            insert into rep_fechas(idcadena, idruta, idpromotor, Fecha)            
			      Select distinct cc.idcadena, p.idruta, p.idpromotor, pFecha AS Fecha 
            from photos p 
            inner join cat_rutas cr on p.idruta = cr.idruta
            inner join cat_cadena cc on cr.idcadena = cc.idcadena
            where Cast(p.FechaHora as date) = pFecha
            and p.idpromotor = idpromotor
            and p.idpromotor in (Select idpromotor from cat_promotor where tipo = 1 and idEstatus = 1 and idempresa = " . $idempresa . ")
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0            
            Union
            Select  cr.idcadena, rpd.idruta, rpd.idpromotor, 
			      pFecha AS Fecha
			      from rutas_promotor_dias rpd
            inner join rutas_promotor rp
            on rpd.idpromotor = rp.idpromotor and rpd.idruta = rp.idruta and rp.idestatus = 1
            left join cat_promotor cp on rpd.idpromotor = cp.idpromotor
			      left join cat_rutas cr on rpd.idruta = cr.idruta 
			      where 
			      (
			      case when (dayofweek(pFecha) = 1) then (`rpd`.`domingo` = 1) when (dayofweek(pFecha) = 2) then (`rpd`.`lunes` = 1) when (dayofweek(pFecha) = 3) then (`rpd`.`martes` = 1) when (dayofweek(pFecha) = 4) then (`rpd`.`miercoles` = 1) when (dayofweek(pFecha) = 5) then (`rpd`.`jueves` = 1) when (dayofweek(pFecha) = 6) then (`rpd`.`viernes` = 1) when (dayofweek(pFecha) = 7) then (`rpd`.`sabado` = 1) end
			      ) 
			      and cp.tipo >= 0 and cp.idestatus = 1 and rpd.idpromotor =idpromotor and cp.idempresa = " . $idempresa . "
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0;            
			  elseif (idpromotor = 0 and idcadena > 0) then
			      insert into rep_fechas(idcadena, idruta, idpromotor, Fecha)
            Select  cr.idcadena, rpd.idruta, rpd.idpromotor, 
			      pFecha AS Fecha
			      from rutas_promotor_dias rpd
            inner join rutas_promotor rp
            on rpd.idpromotor = rp.idpromotor and rpd.idruta = rp.idruta and rp.idestatus = 1
			      left join cat_promotor cp on rpd.idpromotor = cp.idpromotor
			      left join cat_rutas cr on rpd.idruta = cr.idruta 
			      where 
			      (
			      case when (dayofweek(pFecha) = 1) then (`rpd`.`domingo` = 1) when (dayofweek(pFecha) = 2) then (`rpd`.`lunes` = 1) when (dayofweek(pFecha) = 3) then (`rpd`.`martes` = 1) when (dayofweek(pFecha) = 4) then (`rpd`.`miercoles` = 1) when (dayofweek(pFecha) = 5) then (`rpd`.`jueves` = 1) when (dayofweek(pFecha) = 6) then (`rpd`.`viernes` = 1) when (dayofweek(pFecha) = 7) then (`rpd`.`sabado` = 1) end
			      ) 
			      and cp.tipo >= 0 and cp.idestatus = 1 and cr.idcadena = idcadena and cp.idempresa = " . $idempresa . "
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0            
            Union
            Select distinct cc.idcadena, p.idruta, p.idpromotor, pFecha AS Fecha 
            from photos p 
            inner join cat_rutas cr on p.idruta = cr.idruta
            inner join cat_cadena cc on cr.idcadena = cc.idcadena
            where Cast(p.FechaHora as date) = pFecha
            and cc.idcadena = idcadena
            and p.idpromotor in (Select idpromotor from cat_promotor where tipo = 1 and idEstatus = 1 and idempresa = " . $idempresa . ")
            and cc.idempresa = " . $idempresa . "
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0;            
			 elseif (idpromotor > 0 and idcadena > 0) then
            insert into rep_fechas(idcadena, idruta, idpromotor, Fecha)
			      Select  cr.idcadena, rpd.idruta, rpd.idpromotor, 
			      pFecha AS Fecha
			      from rutas_promotor_dias rpd
			      left join cat_promotor cp on rpd.idpromotor = cp.idpromotor
			      left join cat_rutas cr on rpd.idruta = cr.idruta 
			      where 
			      (
			      case when (dayofweek(pFecha) = 1) then (`rpd`.`domingo` = 1) when (dayofweek(pFecha) = 2) then (`rpd`.`lunes` = 1) when (dayofweek(pFecha) = 3) then (`rpd`.`martes` = 1) when (dayofweek(pFecha) = 4) then (`rpd`.`miercoles` = 1) when (dayofweek(pFecha) = 5) then (`rpd`.`jueves` = 1) when (dayofweek(pFecha) = 6) then (`rpd`.`viernes` = 1) when (dayofweek(pFecha) = 7) then (`rpd`.`sabado` = 1) end
			      ) 
			      and cp.tipo >= 0 and cp.idestatus = 1 and rpd.idpromotor =idpromotor and cr.idcadena = idcadena and cp.idempresa = " . $idempresa . "
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0
            Union
			      Select distinct cc.idcadena, p.idruta, p.idpromotor, pFecha AS Fecha 
            from photos p 
            inner join cat_rutas cr on p.idruta = cr.idruta
            inner join cat_cadena cc on cr.idcadena = cc.idcadena
            where Cast(p.FechaHora as date) = pFecha
            and p.idpromotor = idpromotor and cc.idcadena = idcadena
            and cc.idempresa = " . $idempresa . "
            and p.idpromotor in (Select idpromotor from cat_promotor where tipo = 1 and idEstatus = 1 and idempresa = " . $idempresa . ")
            and (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . " limit 1) > 0
            and (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . " limit 1) > 0;            
			 end if;
			
		UNTIL done END REPEAT;
	CLOSE CURSORFechas;

  
END;
";

  if(mysqli_query($con1,$sql_d)) 
  {
	  echo "Procedimiento almacenado 'Proc_Reporte_Todos_" . $idempresa . "' eliminado<br>";
  }
  
  if(mysqli_query($con1,$sql)) 
  {
	  mysqli_close($con1);
	  return "Procedimiento almacenado 'Proc_Reporte_Todos_" . $idempresa . "' creado<br>";  
  }
}

?>