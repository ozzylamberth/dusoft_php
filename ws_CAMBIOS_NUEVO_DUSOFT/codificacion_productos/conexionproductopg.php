<?php
/**
Archivo de conexion a base de datos postgres
el password debe ser actualizado en la base produccion debido a la
autenticacion con md5 bajo el control del hba.conf
**/

//$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_23_01_2014 user=admin password=admin");

$conexionn = pg_connect("host=10.0.2.170 dbname=dusoft  user=admin password=.123mauro*") or
            die("Fallo en el establecimiento de la conexion");


?> 