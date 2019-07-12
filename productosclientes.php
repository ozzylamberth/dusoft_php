<?php

$archivo = realpath('./'). '/productosclientes.csv';
//$dbconn3 = pg_connect("host=10.0.2.169 port=5432 dbname=dusoft_23_01_2014 user=admin password=admin");
$dbconn3 = pg_connect("host=10.0.2.246 port=5432 dbname=dusoft user=admin password=.123mauro*");




$fila = 1;
if (($gestor = fopen($archivo, "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
        
          if($fila > 1){
           
              $codigo_prodcuto = trim($datos[1]);
              $query = "UPDATE  vnts_contratos_clientes_productos SET precio_pactado = '{$datos[2]}', fecha_registro = now() WHERE codigo_producto = '{$codigo_prodcuto}' and contrato_cliente_id = '{$datos[0]}'";
              
              //echo $query."</br>";
              $resultado  = pg_query($query);
             
              
              if(pg_affected_rows($resultado) == 0){
                 $query = "INSERT INTO vnts_contratos_clientes_productos(contrato_cliente_id,codigo_producto,precio_pactado,usuario_id)
                 VALUES('{$datos[0]}','{$codigo_prodcuto}',{$datos[2]},'{$datos[3]}')";
                 
                 $insert  = pg_query($query);
                 
                 if(pg_last_error() == ''){
                     
                    echo "Se inserto el precio del producto {$codigo_prodcuto} por valor {$datos[2]} y contrato {$datos[0]} </br>";
                 } else {
                    echo "<p style='color:red;'>Error insertando el precio del producto {$codigo_prodcuto} por valor {$datos[2]} y contrato {$datos[0]} ".pg_last_error()." </br></p>";
                 }
                 
              } else {
                  echo "Se modifico el precio del producto {$codigo_prodcuto} por valor {$datos[2]} y contrato {$datos[0]} </br>";
              }
              

          }

        $fila++;
    }
    fclose($gestor);
}



