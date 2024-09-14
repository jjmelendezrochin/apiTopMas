<?php

require '../conexion_y_paginacion/database.php';

$mysql = new MysqlManager();

$sql = "CALL `Proc_optimizatablas`();";

$mysql->QueryAsNormal($sql);

$mysql->Close($mysql->getConnection());
