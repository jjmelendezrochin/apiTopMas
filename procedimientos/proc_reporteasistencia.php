<?php 
function proc_reporteasistencia($idempresa){
$con1 = connect();
    
	$sql_d = "DROP PROCEDURE IF EXISTS wwtopm_topmkt.proc_reporteasistencia_" . $idempresa . ";";
	 
	$sql = "CREATE PROCEDURE wwtopm_topmkt.`proc_reporteasistencia_" . $idempresa . "`( IN 
sFechaIni varchar(10), 
sFechaFin varchar(10), 
idpromotor int)
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

  Call Proc_Reporte(@FechaIni, @FechaFin, @idpromotor, @idcadena);

  if(idpromotor>0) then 
     set @condpromotor = Concat(\" and vr.idpromotor = \", Cast(idpromotor as char));
  else
    set @condpromotor = \"\";
  end if;

  set @Instruccion = \"
  Select a.Fecha, UPPER(a.Promotor) as Promotor, 
  round(((((Sum(a.checkin)+Sum(a.checkout))) / ((count(*) * 2)))*100),0) as Avance
  from
  (
    Select distinct
    vr.idruta,
    vr.Fecha as Fecha0,
    DATE_FORMAT(vr.Fecha, '%d/%m/%Y') as Fecha, 
    cp.Nombre as Promotor,     
    if(isnull(v1.FechaHora),0,1) as checkin, 
    if(isnull(v2.FechaHora),0,1) as checkout, 0 as valor
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
    where vr.idpromotor > 0
    and vr.idpromotor not in (Select idpromotor from cat_promotor where tipo = 1 and idEstatus = 1 and idempresa = " . $idempresa . ")\";    
  
  set @Instruccion1 = 
  Concat(\"  
    and vr.idruta not in
    (Select idruta from rutas_promotor_temporal rpt
    where rpt.idpromotor = vr.idpromotor 
    and rpt.idruta = vr.idruta
    and Cast(rpt.dia as date) = '\", sFechaIni, \"'
    and rpt.asiste=0 and rpt.idestatus = 1
    and (Select (Select count(*) from cat_cadena cc where cc.idcadena = cr.idcadena and cc.idempresa = " . $idempresa . ") from cat_rutas cr where cr.idruta = rpt.idruta) > 0
    and (Select (Select count(*) from cat_formato cf where cf.idformato = cr.idformato and cf.idempresa = " . $idempresa . ") from cat_rutas cr where cr.idruta = rpt.idruta) > 0
    and (Select count(*) from cat_promotor p where p.idpromotor = rpt.idpromotor and p.idempresa = " . $idempresa . ") > 0
    )
    Union
    Select 
    vr.idruta,
    vr.dia as Fecha0,
    DATE_FORMAT(vr.dia, '%d/%m/%Y') as Fecha,    
    cp.Nombre as Promotor,
    if(isnull(v1.FechaHora),0,1) as checkin, 
    if(isnull(v2.FechaHora),0,1) as checkout, 1 as valor
    from rutas_promotor_temporal vr
    left join vw_tiendas_" . $idempresa . " cr
    on vr.idruta = cr.idruta and vr.asiste = 1 and vr.idestatus = 1
    left join vw_promotores_supervisores_" . $idempresa . " cp
    on vr.idpromotor = cp.idpromotor
    left join photos f
    on cr.idruta = f.idruta and cp.idpromotor = f.idpromotor and Cast(vr.dia as date) = cast(f.FechaHora as date)
    left join vw_vistagrupo_fechapromotortienda_checkin_" . $idempresa . " v1
    on Cast(f.FechaHora as date) = Cast(v1.Fecha as date) and f.idpromotor = v1.idpromotor and f.idruta = v1.idruta
    left join vw_vistagrupo_fechapromotortienda_checkout_" . $idempresa . " v2
    on Cast(f.FechaHora as date) = Cast(v2.Fecha as date) and f.idpromotor = v2.idpromotor and f.idruta = v2.idruta
    where vr.idpromotor > 0 and f.idoperacion in (1)
    \" , @condpromotor , \"  
    and Cast(f.FechaHora as Date) = '\", sFechaIni, \"' 
    and (Select (Select count(*) from cat_cadena cc where cc.idcadena = _cr.idcadena and cc.idempresa = " . $idempresa . ") from cat_rutas _cr where cr.idruta = f.idruta limit 1) > 0
    and (Select (Select count(*) from cat_formato cf where cf.idformato = _cr.idformato and cf.idempresa = " . $idempresa . ") from cat_rutas _cr where cr.idruta = f.idruta limit 1) > 0
    and (Select count(*) from cat_promotor cp1 where cp1.idpromotor = f.idpromotor and cp1.idempresa = " . $idempresa . " limit 1 ) > 0
  ) as a
  group by a.Fecha0, a.Promotor
  order by a.Fecha0,2;\");
    
  set @condfecha = Concat(\" and Cast(vr.Fecha as Date) between  '\", sFechaIni, \"' and '\", sFechaFin , \"'\");  
  set @Instruccion = Concat(@Instruccion, \" \", @condpromotor, \" \",  @condfecha, \" \", @Instruccion1);
  
  -- SELECT (@Instruccion);
  PREPARE Ins FROM @Instruccion;
  EXECUTE Ins;
  DEALLOCATE PREPARE Ins;
  
End;";

  if(mysqli_query($con1,$sql_d)) 
  {
	  echo "Procedimiento almacenado 'proc_reporteasistencia_" . $idempresa . "' eliminado<br>";
  }
  
  if(mysqli_query($con1,$sql)) 
  {
	  mysqli_close($con1);
	  return "Procedimiento almacenado 'proc_reporteasistencia_" . $idempresa . "' creado<br>";  
  }
}

?>