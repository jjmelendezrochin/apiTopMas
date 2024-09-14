<?php 
function proc_reporteestanciascadenas($idempresa){
$con1 = connect();
    
	$sql_d = "DROP PROCEDURE IF EXISTS wwtopm_topmkt.proc_reporteestanciascadenas_" . $idempresa . ";";
	 
	$sql = "CREATE PROCEDURE wwtopm_topmkt.`proc_reporteestanciascadenas_" . $idempresa . "`( IN 
sFechaIni varchar(10), 
sFechaFin varchar(10), 
idpromotor int, 
idtienda int)
Begin
 Declare Instruccion varchar(1000);
 Declare condfecha varchar(100);
 Declare condpromotor varchar(100);
 Declare condtienda varchar(100);
 Declare orden varchar(100);

  Set @FechaIni   = sFechaIni;  
  Set @FechaFin   = sFechaFin;   
  Set @idpromotor = idpromotor; 
  Set @idcadena   = 0; 

  Call Proc_Reporte_Todos_" . $idempresa . "(@FechaIni, @FechaFin, @idpromotor, @idcadena);

  Truncate table tmp_reporteestancia;

  set @Instruccion = \"
  Insert into tmp_reporteestancia
  (Fecha, idruta1, idruta2, idpromotor1, idpromotor2, Promotor, Cadena, Formato, Tienda, HoraCheckin, DistanciaCheckin, HoraCheckout, DistanciaCheckout, 
  Estancia, objetivo, checkin, checkout)
  Select
  DATE_FORMAT(vr.Fecha, '%d/%m/%Y') as Fecha, 
  vr.idruta as idruta1, 
  vg.idruta as idruta2,
  vr.idpromotor as idpromotor1, 
  vg.idpromotor as idpromotor2,
  cp.Nombre as Promotor, 
  cr.Cadena, 
  cr.Formato, 
  cr.Tienda,
  v1.FechaHora as HoraCheckin, Cast(v1.Distancia_m as char) as DistanciaCheckin,
  v2.FechaHora as HoraCheckout, Cast(v2.Distancia_m as char) as DistanciaCheckout,
  if(isnull(v1.FechaHora) or isnull(v2.FechaHora), 0, 
  timediff(v2.FechaHora, v1.FechaHora)) as Estancia,
  1 as objetivo, 
  if(isnull(v1.FechaHora),0,1) as checkin, 
  if(isnull(v2.FechaHora),0,1) as checkout
  from vw_base_reporte_" . $idempresa . " vr
  left join vw_tiendas_" . $idempresa . " cr
  on vr.idruta = cr.idruta
  left join vw_promotores_supervisores_" . $idempresa . " cp
  on vr.idpromotor = cp.idpromotor
  left join vw_vistagrupo_fechapromotortienda_" . $idempresa . " vg
  on cr.idruta = vg.idruta and cp.idpromotor = vg.idpromotor and vr.fecha = vg.Fecha
  left join vw_vistagrupo_fechapromotortienda_checkin_" . $idempresa . " v1
  on vg.Fecha = v1.Fecha and vg.idpromotor = v1.idpromotor and vg.idruta = v1.idruta
  left join vw_vistagrupo_fechapromotortienda_checkout_" . $idempresa . " v2
  on vg.Fecha = v2.Fecha and vg.idpromotor = v2.idpromotor and vg.idruta = v2.idruta
  inner join (
  Select idpromotor from cat_promotor where tipo = 0 and idestatus = 1 and idempresa = " . $idempresa . " 
  ) as pro on vr.idpromotor = pro.idpromotor
  \";
  
  set @condfecha = Concat(\" where Cast(vr.Fecha as Date) between  '\", sFechaIni, \"' and '\", sFechaFin , \"'\");
  set @orden = \"order by DATE_FORMAT(vr.Fecha, '%d/%m/%Y')\";
  
  -- idpromotor
  if(idpromotor>0) then 
     set @condpromotor = Concat(\" and vg.idpromotor = \", Cast(idpromotor as char));
  else
    set @condpromotor = \"\";
  end if;
  
   -- idtienda
  if(idtienda>0) then 
     set @condtienda = Concat(\" and vg.idruta = \", Cast(idtienda as char));
  else
    set @condtienda = \"\";
  end if;
  
  set @Instruccion = Concat(@Instruccion, \" \" , @condfecha, \" \" , @condpromotor, \" \" , @condtienda, \" \" , @orden);
 
  -- SELECT (@Instruccion);
  PREPARE Ins FROM @Instruccion;
  EXECUTE Ins;
  DEALLOCATE PREPARE Ins;
  
  Select distinct Fecha, Promotor, Tienda, HoraCheckin, DistanciaCheckin, HoraCheckout, DistanciaCheckout,
  Estancia, cadena, formato, objetivo, checkin, checkout
  from tmp_reporteestancia
  where not cadena is null
  order by Fecha desc, Promotor asc, Tienda Asc;
  
End;";

  if(mysqli_query($con1,$sql_d)) 
  {
	  echo "Procedimiento almacenado 'proc_reporteestanciascadenas_" . $idempresa . "' eliminado<br>";
  }
  
  if(mysqli_query($con1,$sql)) 
  {
	  mysqli_close($con1);
	  return "Procedimiento almacenado 'proc_reporteestanciascadenas_" . $idempresa . "' creado<br>";  
  }
}

?>