<?php
$conexionn = pg_connect("host=localhost port=5432 dbname=dusoft_24_11_2013 user=admin password=admin");


$fp = fopen ( "eps_afiliados.csv" , "r" ); 
while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
 
    //$fila = explode(",", $data[0]);
    $fila = $data;
 
 //print_r($data[0]);
 
    $s_eps_afiliacion_id = str_replace('"', '', $fila[0]);
    $s_afiliado_tipo_id = str_replace('"', '', $fila[1]);
    $s_afiliado_id = str_replace('"', '', $fila[2]);
    $s_eps_tipo_afiliado_id = str_replace('"', '', $fila[3]);
    $s_fecha_afiliacion = str_replace('"', '', $fila[4]);
    $s_eps_anterior = str_replace('"', '', $fila[5]);
    $s_semanas_cotizadas_eps_anterior = str_replace('"', '', $fila[6]);
    $s_semanas_cotizadas = str_replace('"', '', $fila[7]);    
    $s_estado_afiliado_id = str_replace('"', '', $fila[8]);
    $s_subestado_afiliado_id = str_replace('"', '', $fila[9]);
    $s_observaciones = str_replace('"', '', $fila[10]);
    $s_usuario_registro = str_replace('"', '', $fila[11]);
    $s_fecha_registro = str_replace('"', '', $fila[12]);
    
    $s_usuario_ultima_actualizacion = str_replace('"', '', $fila[13]);
    if($s_usuario_ultima_actualizacion == '\N' || $s_usuario_ultima_actualizacion == '') {
        $s_usuario_ultima_actualizacion = "NULL";
    } else {
        $s_usuario_ultima_actualizacion = "'".$s_usuario_ultima_actualizacion."'";
    }
    
    $s_accion_ultima_actualizacion = str_replace('"', '', $fila[14]);
    
    $s_fecha_ultima_actualizacion = str_replace('"', '', $fila[15]);
    if($s_fecha_ultima_actualizacion == '\N' || $s_fecha_ultima_actualizacion == '') {
        $s_fecha_ultima_actualizacion = "NULL";
    } else {
        $s_fecha_ultima_actualizacion = "'".$s_fecha_ultima_actualizacion."'";
    }
    
    $s_plan_atencion = str_replace('"', '', $fila[16]);
    if($s_plan_atencion == '\N' || $s_plan_atencion == '') {
        $s_plan_atencion = "NULL";
    } else {
        $s_plan_atencion = "'".$s_plan_atencion."'";
    }
    
    $s_tipo_afiliado_atencion = str_replace('"', '', $fila[17]);
    $s_rango_afiliado_atencion = str_replace('"', '', $fila[18]);
    $s_eps_punto_atencion_id = str_replace('"', '', $fila[19]);
    
    $s_fecha_vencimiento = str_replace('"', '', $fila[20]);
    if($s_fecha_vencimiento == '\N' || $s_fecha_vencimiento == '') {
        $s_fecha_vencimiento = "NULL";
    } else {
        $s_fecha_vencimiento = "'".$s_fecha_vencimiento."'";
    }
    
    $s_fecha_afiliacion_eps_anterior = str_replace('"', '', $fila[21]);    
    if($s_fecha_afiliacion_eps_anterior == '\N' || $s_fecha_afiliacion_eps_anterior == '') {
        $s_fecha_afiliacion_eps_anterior = "NULL";
    } else {
        $s_fecha_afiliacion_eps_anterior = "'".$s_fecha_afiliacion_eps_anterior."'";
    }
 
 $sql = "SELECT guardar_eps_afiliados(".$s_eps_afiliacion_id.", '".$s_afiliado_tipo_id."', '".$s_afiliado_id."', '".$s_eps_tipo_afiliado_id."', '".$s_fecha_afiliacion."', '".$s_eps_anterior."', ".$s_semanas_cotizadas_eps_anterior.", ".$s_semanas_cotizadas.", '".$s_estado_afiliado_id."', '".$s_subestado_afiliado_id."', '".$s_observaciones."', ".$s_usuario_registro.", '".$s_fecha_registro."', ".$s_usuario_ultima_actualizacion.", '".$s_accion_ultima_actualizacion."', ".$s_fecha_ultima_actualizacion.", ".$s_plan_atencion.", '".$s_tipo_afiliado_atencion."', '".$s_rango_afiliado_atencion."', '".$s_eps_punto_atencion_id."', ".$s_fecha_vencimiento.", ".$s_fecha_afiliacion_eps_anterior.");";
 
 
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