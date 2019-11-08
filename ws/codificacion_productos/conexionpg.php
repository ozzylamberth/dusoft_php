<?php

//$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_23_01_2014 user=admin password=admin");

$conexion = pg_connect("host=10.0.2.246 dbname=dusoft user=admin password=.123mauro*") or
            die("Fallo en el establecimiento de la conexión");
?>