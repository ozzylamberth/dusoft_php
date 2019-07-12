<?php
$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_24_11_2013 user=admin password=admin");


$fp = fopen ( "pacientes.csv" , "r" ); 
//$fp = fopen ( "pac.csv" , "r" ); 
while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
 
    //$fila = explode(",", $data[0]);
    $fila = $data;
 
 //print_r($data[0]);
 
    $s_paciente_id = str_replace('"', '', $fila[0]);
    $s_tipo_id_paciente = str_replace('"', '', $fila[1]);
    $s_primer_apellido = str_replace('"', '', $fila[2]);
    $s_segundo_apellido = str_replace('"', '', $fila[3]);
    $s_primer_nombre = str_replace('"', '', $fila[4]);
    $s_segundo_nombre = str_replace('"', '', $fila[5]);
    $s_fecha_nacimiento = str_replace('"', '', $fila[6]);
    $s_fecha_nacimiento_es_calculada = str_replace('"', '', $fila[7]);    
    $s_residencia_direccion = str_replace('"', '', $fila[8]);
    $s_residencia_telefono = str_replace('"', '', $fila[9]);
    $s_zona_residencia = str_replace('"', '', $fila[10]);
    $s_ocupacion_id = str_replace('"', '', $fila[11]);
    $s_fecha_registro = str_replace('"', '', $fila[12]);
    $s_sexo_id = str_replace('"', '', $fila[13]);
    $s_tipo_estado_civil_id = str_replace('"', '', $fila[14]);
    $s_foto = str_replace('"', '', $fila[15]);    
    $s_tipo_pais_id = str_replace('"', '', $fila[16]);
    $s_tipo_dpto_id = str_replace('"', '', $fila[17]);
    $s_tipo_mpio_id = str_replace('"', '', $fila[18]);
    $s_paciente_fallecido = str_replace('"', '', $fila[19]);
    $s_usuario_id = str_replace('"', '', $fila[20]);
    $s_nombre_madre = str_replace('"', '', $fila[21]);    
    $s_observaciones = str_replace('"', '', $fila[22]);
    $s_tipo_comuna_id = str_replace('"', '', $fila[23]);
    $s_tipo_barrio_id = str_replace('"', '', $fila[24]);
    $s_tipo_estrato_id = str_replace('"', '', $fila[25]);    
    $s_lugar_expedicion_documento = str_replace('"', '', $fila[26]);
    $s_sw_ficha = str_replace('"', '', $fila[27]);
    $s_celular_telefono = str_replace('"', '', $fila[28]);
    $s_email = str_replace('"', '', $fila[29]);
    $s_tipo_bloqueo_id = str_replace('"', '', $fila[30]);
 
 $sql = "SELECT guardar_pacientes('".$s_paciente_id."', '".$s_tipo_id_paciente."', '".$s_primer_apellido."', '".$s_segundo_apellido."', '".$s_primer_nombre."', '".$s_segundo_nombre."', '".$s_fecha_nacimiento."', '".$s_fecha_nacimiento_es_calculada."', '".$s_residencia_direccion."', '".$s_residencia_telefono."', '".$s_zona_residencia."', '".$s_ocupacion_id."', '".$s_fecha_registro."', '".$s_sexo_id."', '".$s_tipo_estado_civil_id."', '".$s_foto."', '".$s_tipo_pais_id."', '".$s_tipo_dpto_id."', '".$s_tipo_mpio_id."', '".$s_paciente_fallecido."', 1350, '".$s_nombre_madre."', '".$s_observaciones."', '".$s_tipo_comuna_id."', '".$s_tipo_barrio_id."', '".$s_tipo_estrato_id."', '".$s_lugar_expedicion_documento."', '".$s_sw_ficha."', '".$s_celular_telefono."', '".$s_email."', '".$s_tipo_bloqueo_id."');";
 
 
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