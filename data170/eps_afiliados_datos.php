<?php
$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_24_11_2013 user=admin password=admin");


$fp = fopen ( "eps_afiliados_datos.csv" , "r" ); 
while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
 
    //$fila = explode(",", $data[0]);
    $fila = $data;
 
 //print_r($data[0]);
 
    $s_afiliado_tipo_id = str_replace('"', '', $fila[0]);
    $s_afiliado_id = str_replace('"', '', $fila[1]);
    $s_primer_apellido = str_replace('"', '', $fila[2]);
    $s_segundo_apellido = str_replace('"', '', $fila[3]);
    $s_primer_nombre = str_replace('"', '', $fila[4]);
    $s_segundo_nombre = str_replace('"', '', $fila[5]);
    $s_fecha_nacimiento = str_replace('"', '', $fila[6]);
    $s_fecha_afiliacion_sgss = str_replace('"', '', $fila[7]);    
    $s_tipo_sexo_id = str_replace('"', '', $fila[8]);
    $s_ciuo_88_grupo_primario = str_replace('"', '', $fila[9]);
    $s_tipo_pais_id = str_replace('"', '', $fila[10]);
    $s_tipo_dpto_id = str_replace('"', '', $fila[11]);
    $s_tipo_mpio_id = str_replace('"', '', $fila[12]);
    $s_zona_residencia = str_replace('"', '', $fila[13]);
    $s_direccion_residencia = str_replace('"', '', $fila[14]);
    $s_telefono_residencia = str_replace('"', '', $fila[15]);    
    $s_telefono_movil = str_replace('"', '', $fila[16]);
    $s_usuario_registro = str_replace('"', '', $fila[17]);
    $s_fecha_registro = str_replace('"', '', $fila[18]);
    $s_usuario_ultima_actualizacion = str_replace('"', '', $fila[19]);
    $s_accion_ultima_actualizacion = str_replace('"', '', $fila[20]);
    $s_fecha_ultima_actualizacion = str_replace('"', '', $fila[21]);
 
 $sql = "SELECT guardar_eps_afiliados_datos('".$s_afiliado_tipo_id."', '".$s_afiliado_id."', '".$s_primer_apellido."', '".$s_segundo_apellido."', '".$s_primer_nombre."', '".$s_segundo_nombre."', '".$s_fecha_nacimiento."', '".$s_fecha_afiliacion_sgss."', '".$s_tipo_sexo_id."', '".$s_ciuo_88_grupo_primario."', '".$s_tipo_pais_id."', '".$s_tipo_dpto_id."', '".$s_tipo_mpio_id."', '".$s_zona_residencia."', '".$s_direccion_residencia."', '".$s_telefono_residencia."', '".$s_telefono_movil."', ".$s_usuario_registro.", '".$s_fecha_registro."', '".$s_usuario_ultima_actualizacion."', '".$s_accion_ultima_actualizacion."', '".$s_fecha_ultima_actualizacion."');";
 
 
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