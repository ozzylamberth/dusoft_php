<?php

$dbconn3 = pg_connect("host=10.0.2.170 port=5432 dbname=dusoft_prueba_produccion user=admin password=admin");



$query = "SELECT * FROM documentos";
 $result  = pg_query($query);

 while($row=pg_fetch_assoc($result)){
     echo print_r($row);
     
 }

