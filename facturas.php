<?php


$archivo = realpath('./'). '/factura.csv';
$dbconn3 = pg_connect("host=10.0.2.204 port=5432 dbname=Financiero3 user=duana password=4cc3s0F1Maur14");




$fila = 1;
if (($gestor = fopen($archivo, "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
        $numero = count($datos);
        
          if($fila > 1){
            
           $sql =  "update documentos_transaccionales set fecha_registro = '{$datos[7]}', fecha_creacion = '{$datos[7]}' WHERE prefijo  = '{$datos[0]}' and numero = {$datos[1]}";
          //  echo $sql . "</br>";
         //   $sql = "UPDATE  documentos_transaccionales SET fecha_registro='{$datos[3]}' WHERE prefijo= '{$datos[1]}' AND numero={$datos[0]}; ";

            $result = pg_query( $dbconn3, $sql);
          //  echo $sql. "</br>";
          }

        $fila++;
    }
    fclose($gestor);
}
