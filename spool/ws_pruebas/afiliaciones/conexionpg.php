<?php
/**
Archivo de conexion a base de datos postgres
el password debe ser actualizado en la base produccion debido a la
autenticacion con md5 bajo el control del hba.conf
**/

$conexionn = pg_connect("host=10.0.2.170 port=5432 dbname=dusoft_prueba_produccion user=admin password=admin")
			or die('No se ha podido conectar: ' . pg_last_error());
?>