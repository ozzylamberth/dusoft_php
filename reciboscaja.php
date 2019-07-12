<?php


$archivo = realpath('./'). '/reciboscaja.csv';
$dbconn3 = pg_connect("host=10.0.2.246 port=5432 dbname=dusoft user=admin password=.123mauro*");




$fila = 1;
if (($gestor = fopen($archivo, "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
        $numero = count($datos);
          if($fila > 1){
           // print_r($datos[2]). "</br></br></br>";
           $sql =  "update recibos_caja set fecha_registro = '{$datos[1]}' WHERE prefijo  = '{$datos[0]}' and recibo_caja = {$datos[2]};";
           //echo $sql . "</br>";
           //$sql = "UPDATE  documentos_transaccionales SET fecha_registro='{$datos[3]}' WHERE prefijo= '{$datos[1]}' AND numero={$datos[0]}; ";

           $result = pg_query( $dbconn3, $sql);
            echo "Se cambio la fecha del rcd #{$datos[2]}{$datos[0]} a la fecha {$datos[1]} </br>";
            //echo $sql.  pg_last_error($dbconn3). "</br>";
          }

        $fila++;
    }
    fclose($gestor);
    
    
    echo "</br>Se importaron {$fila} registros";
}
