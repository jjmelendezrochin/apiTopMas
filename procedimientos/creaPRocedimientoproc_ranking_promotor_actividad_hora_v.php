<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../database.php';
require 'proc_ranking_promotor_actividad_hora.php';
require 'proc_reporte.php';
require 'proc_reporteasistencia.php';
require 'proc_reporte_todos.php';
require 'proc_reporteestanciacadena.php';
require 'proc_preciosproductos.php';

echo proc_ranking_promotor_actividad_hora(1);
echo proc_ranking_promotor_actividad_hora(2);
echo proc_ranking_promotor_actividad_hora(3);
echo proc_ranking_promotor_actividad_hora(4);
echo proc_reporte(1);
echo proc_reporte(2);
echo proc_reporte(3);
echo proc_reporte(4);
echo proc_reporteasistencia(1);
echo proc_reporteasistencia(2);
echo proc_reporteasistencia(3);
echo proc_reporteasistencia(4);
echo proc_reporte_todos(1);
echo proc_reporte_todos(2);
echo proc_reporte_todos(3);
echo proc_reporte_todos(4);
echo proc_reporteestanciascadenas(1);
echo proc_reporteestanciascadenas(2);
echo proc_reporteestanciascadenas(3);
echo proc_reporteestanciascadenas(4);
echo proc_preciosproductos(1);
echo proc_preciosproductos(2);
echo proc_preciosproductos(3);
echo proc_preciosproductos(4);

?>