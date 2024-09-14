<?php 
function proc_preciosproductos($idempresa){
$con1 = connect();
    
	$sql_d = "DROP PROCEDURE IF EXISTS wwtopm_topmkt.proc_preciosproductos_" . $idempresa . ";";
	 
	$sql = "CREATE PROCEDURE wwtopm_topmkt.`proc_preciosproductos_" . $idempresa . "`(
IN pAnio int,
IN pMes int ,
IN pidproducto int
)
Begin

-- Consultar el numero de dias por cada mes
Declare diasxmes int;
Declare esbisiesto int;
Declare vproducto varchar(200);

-- Declare Instruccion0 varchar(3000);
-- Declare Instruccion1 varchar(1000);
-- Declare Instruccion2 varchar(1000);


SET esbisiesto = (Select Mod(pAnio,4));
SET diasxmes = (Select diasmes FROM cat_meses where idmes = pMes);

-- Dia 29
if(pMes = 2 and esbisiesto = 0) then
  SET diasxmes = 29;
  SET @Dia29 = \",Sum(Case when day(Cast(a.fecha as date)) = '29' then Convert(a.precio, decimal(6,2)) else 0 end) as '29'\";
  SET @Dia30 = \"\";
  SET @Dia31 = \"\";
end if;

-- Dia 30
if(diasxmes=30) then
  SET @Dia29 = \",Sum(Case when day(Cast(a.fecha as date)) = '29' then Convert(a.precio, decimal(6,2)) else 0 end) as '29'\";
  SET @Dia30 = \",Sum(Case when day(Cast(a.fecha as date)) = '30' then Convert(a.precio, decimal(6,2)) else 0 end) as '30'\";
  SET @Dia31 = \"\";
end if;

-- Dia 31
if(diasxmes=31) then
  SET @Dia29 = \",Sum(Case when day(Cast(a.fecha as date)) = '29' then Convert(a.precio, decimal(6,2)) else 0 end) as '29'\";
  SET @Dia30 = \",Sum(Case when day(Cast(a.fecha as date)) = '30' then Convert(a.precio, decimal(6,2)) else 0 end) as '30'\";
  SET @Dia31 = \",Sum(Case when day(Cast(a.fecha as date)) = '31' then Convert(a.precio, decimal(6,2)) else 0 end) as '31'\";
end if;

SET @FechaInicial = Concat(pAnio,\"/\",RIGHT(Concat(\"0\",pMes),2),\"/01\");
SET @FechaFinal   = Concat(pAnio,\"/\",RIGHT(Concat(\"0\",pMes),2),\"/\",diasxmes);
SET @idprod       = pidproducto;
SET @vproducto = (Select descripcion FROM cat_productos where idproducto = pidproducto and idempresa = " . $idempresa . ");

set @Instruccion1 = \"
Select 
a.cadena, @vproducto as producto,
Sum(Case when day(Cast(a.fecha as date)) = '1' then Convert(a.precio, decimal(6,2)) else 0 end) as '1',
Sum(Case when day(Cast(a.fecha as date)) = '2' then Convert(a.precio, decimal(6,2)) else 0 end) as '2',
Sum(Case when day(Cast(a.fecha as date)) = '3' then Convert(a.precio, decimal(6,2)) else 0 end) as '3',
Sum(Case when day(Cast(a.fecha as date)) = '4' then Convert(a.precio, decimal(6,2)) else 0 end) as '4',
Sum(Case when day(Cast(a.fecha as date)) = '5' then Convert(a.precio, decimal(6,2)) else 0 end) as '5',
Sum(Case when day(Cast(a.fecha as date)) = '6' then Convert(a.precio, decimal(6,2)) else 0 end) as '6',
Sum(Case when day(Cast(a.fecha as date)) = '7' then Convert(a.precio, decimal(6,2)) else 0 end) as '7',
Sum(Case when day(Cast(a.fecha as date)) = '8' then Convert(a.precio, decimal(6,2)) else 0 end) as '8',
Sum(Case when day(Cast(a.fecha as date)) = '9' then Convert(a.precio, decimal(6,2)) else 0 end) as '9',
Sum(Case when day(Cast(a.fecha as date)) = '10' then Convert(a.precio, decimal(6,2)) else 0 end) as '10',
Sum(Case when day(Cast(a.fecha as date)) = '11' then Convert(a.precio, decimal(6,2)) else 0 end) as '11',
Sum(Case when day(Cast(a.fecha as date)) = '12' then Convert(a.precio, decimal(6,2)) else 0 end) as '12',
Sum(Case when day(Cast(a.fecha as date)) = '13' then Convert(a.precio, decimal(6,2)) else 0 end) as '13',
Sum(Case when day(Cast(a.fecha as date)) = '14' then Convert(a.precio, decimal(6,2)) else 0 end) as '14',
Sum(Case when day(Cast(a.fecha as date)) = '15' then Convert(a.precio, decimal(6,2)) else 0 end) as '15',
Sum(Case when day(Cast(a.fecha as date)) = '16' then Convert(a.precio, decimal(6,2)) else 0 end) as '16',
Sum(Case when day(Cast(a.fecha as date)) = '17' then Convert(a.precio, decimal(6,2)) else 0 end) as '17',
Sum(Case when day(Cast(a.fecha as date)) = '18' then Convert(a.precio, decimal(6,2)) else 0 end) as '18',
Sum(Case when day(Cast(a.fecha as date)) = '19' then Convert(a.precio, decimal(6,2)) else 0 end) as '19',
Sum(Case when day(Cast(a.fecha as date)) = '20' then Convert(a.precio, decimal(6,2)) else 0 end) as '20',
Sum(Case when day(Cast(a.fecha as date)) = '21' then Convert(a.precio, decimal(6,2)) else 0 end) as '21',
Sum(Case when day(Cast(a.fecha as date)) = '22' then Convert(a.precio, decimal(6,2)) else 0 end) as '22',
Sum(Case when day(Cast(a.fecha as date)) = '23' then Convert(a.precio, decimal(6,2)) else 0 end) as '23',
Sum(Case when day(Cast(a.fecha as date)) = '24' then Convert(a.precio, decimal(6,2)) else 0 end) as '24',
Sum(Case when day(Cast(a.fecha as date)) = '25' then Convert(a.precio, decimal(6,2)) else 0 end) as '25',
Sum(Case when day(Cast(a.fecha as date)) = '26' then Convert(a.precio, decimal(6,2)) else 0 end) as '26',
Sum(Case when day(Cast(a.fecha as date)) = '27' then Convert(a.precio, decimal(6,2)) else 0 end) as '27',
Sum(Case when day(Cast(a.fecha as date)) = '28' then Convert(a.precio, decimal(6,2)) else 0 end) as '28'\";

set @Instruccion2 =  
\"
from
(
Select distinct 
avg(Cast(pr.precio as decimal(6,2))) as precio, 
Cast(pr.fda as date) as Fecha, 
p.descripcion as descripcion, 
cc.nombrecorto as cadena
from producto_ruta_fecha pr 
inner join cat_productos p on pr.idproducto = p.idproducto
inner join cat_rutas cr on pr.idruta = cr.idruta
inner join cat_formato cf on cr.idformato = cf.idformato
inner join cat_cadena cc on cf.idcadena = cc.idcadena
where Cast(pr.fda as date) 
between @FechaInicial and @FechaFinal 
and p.idproducto = @idprod
and p.idempresa = " . $idempresa . "
and cc.idempresa = " . $idempresa . "
and cf.idempresa = " . $idempresa . "
and trim(cc.nombrecorto) <> 'CALIMAX'   -- A peticion de Leonel 091122
group by p.idproducto, cadena, descripcion, fecha
order by cadena asc, fecha asc
) as a
group by a.cadena;
\";

SET @Instruccion0 = (SELECT Concat(@Instruccion1, @Dia29, @Dia30, @Dia31, @Instruccion2));
PREPARE Ins FROM @Instruccion0;
EXECUTE Ins;
DEALLOCATE PREPARE Ins;

End;
";

  if(mysqli_query($con1,$sql_d)) 
  {
	  echo "Procedimiento almacenado 'proc_preciosproductos_" . $idempresa . "' eliminado<br>";
  }
  
  if(mysqli_query($con1,$sql)) 
  {
	  mysqli_close($con1);
	  return "Procedimiento almacenado 'proc_preciosproductos_" . $idempresa . "' creado<br>";  
  }
}

?>