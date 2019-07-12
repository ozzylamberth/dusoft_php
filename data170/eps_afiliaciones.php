<?php
$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_24_11_2013 user=admin password=admin");


$fp = fopen ( "eps_afiliaciones.csv" , "r" ); 
while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
    
 //print_r($data);
 $fila = $data;
 //$fila = explode(",", $data[0]);
 
    $s_eps_afiliacion_id = $fila[0];
    $s_eps_tipo_afiliacion_id = str_replace('"', '', $fila[1]);
    $s_fecha_recepcion = str_replace('"', '', $fila[2]);
    $s_usuario_registro = str_replace('"', '', $fila[3]);
    $s_fecha_registro = str_replace('"', '', $fila[4]);
    $s_usuario_ultima_actualizacion = str_replace('"', '', $fila[5]);
    $s_accion_ultima_actualizacion = str_replace('"', '', $fila[6]);
    $s_fecha_ultima_actualizacion = str_replace('"', '', $fila[7]);
 
 $sql = "SELECT guardar_eps_afiliaciones(".$s_eps_afiliacion_id.", '".$s_eps_tipo_afiliacion_id."', '".$s_fecha_recepcion."', ".$s_usuario_registro.", '".$s_fecha_registro."', ".$s_usuario_ultima_actualizacion.", '".$s_accion_ultima_actualizacion."', '".$s_fecha_ultima_actualizacion."');";
 
 
 pg_query($conexionn,$sql);
 
 //$result = pg_query($conexionn,$sql);

/*if($result) {
    return true;
} else {
    return "Error en el insert de los datos: ".$sql;
}*/

 
 //print_r($data[0]);
 echo "<br>".$sql."<br><br><br>";

}
fclose ( $fp ); 
?>
