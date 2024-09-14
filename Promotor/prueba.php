<?php
echo "Buenos días, hoy es ".date("w");
$dia  = date("w");
switch ($dia) {
    case 1:
        $criterio = " and rpd.lunes = 1";
        $orden = " order by rpd.lunesp asc";
        break;
    case 2:
        $criterio = " and rpd.martes = 1";
        $orden = " order by rpd.martesp asc";
        break;
    case 3:
        $criterio = " and rpd.miercoles = 1";
        $orden = " order by rpd.miercolesp asc";
        break;
    case 4:
        $criterio = " and rpd.jueves = 1";
        $orden = " order by rpd.juevesp asc";
        break;
    case 5:
        $criterio = " and rpd.viernes = 1";
        $orden = " order by rpd.viernesp asc";
        break;
    case 6:
        $criterio = " and rpd.sabado = 1";
        $orden = " order by rpd.sabadop asc";
        break;
    default:
        $criterio = " and rpd.domingo = 1";
        $orden = " order by rpd.domingop asc";
        break;    
}

echo $criterio;
echo $orden;
?>