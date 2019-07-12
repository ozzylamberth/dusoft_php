<?php

//$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_23_01_2014 user=admin password=admin");

$conexion = pg_connect("host=10.0.2.170 dbname=dusoft_prueba_produccion user=admin password=admin") or
            die("Fallo en el establecimiento de la conexión");
?> 