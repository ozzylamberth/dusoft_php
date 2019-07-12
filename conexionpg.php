<?php
/**
Archivo de conexion a base de datos postgres
el password debe ser actualizado en la base produccion debido a la
autenticacion con md5 bajo el control del hba.conf
**/

//$con = pg_connect("host=localhost port=5432 dbname=copia_produccion user=admin password=admin");
$con = pg_connect("host=10.0.2.169 port=5432 dbname=copia_produccion user=admin password=admin");

// if ($con==true) 
// { 
   // echo "conectado"; 
// } 
// else 
// { 
  // echo "desconectado"; 
// }  

?>