<?php



$dbconn3 = pg_connect("host=10.0.2.246 port=5432 dbname=dusoft user=siis password=.123mauro*");



function afiliados($dbconn3)
{
    $insertados = 0;
    $error = 0;
    
    $sql = "
            SELECT a.*
            FROM eps_afiliados_datos a
            WHERE NOT EXISTS ( 
                SELECT * FROM pacientes
                WHERE
                pacientes.paciente_id = a.afiliado_id AND
                pacientes.tipo_id_paciente = a.afiliado_tipo_id
            ) ";
    
    $result = pg_query($sql);
     while ($row = pg_fetch_array($result)){
        
        
        $sql = "INSERT INTO pacientes (
                    paciente_id, tipo_id_paciente, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, fecha_nacimiento,
                    sexo_id, tipo_estado_civil_id, tipo_pais_id, tipo_dpto_id, tipo_mpio_id, usuario_id, tipo_estrato_id, fecha_registro
                 ) 
                VALUES('{$row["afiliado_id"]}','{$row["afiliado_tipo_id"]}', '{$row["primer_apellido"]}', '{$row["segundo_apellido"]}', 
                 '{$row["primer_nombre"]}','{$row["segundo_nombre"]}', '{$row["fecha_nacimiento"]}', '{$row["tipo_sexo_id"]}',
                 null, 'CO', '76', '001', 1350, null, now()) ";
                 

       pg_query($sql);


        if (pg_last_error($dbconn3) != ""){
            
            echo "<p style = 'color: red;'>Error   # " . pg_last_error(). " ". print_r($row) . "</p></br>";
            $error++;
        } else {
           echo "insertado ". print_r($row)."</br>"; 
           $insertados++;
        }
        
       // echo print_r($row). "</br></br>";
    }
    
    echo "Filas insertadas " . $insertados . "</br></br>";
    echo "Filas con error " . $error . "</br></br>";
    
    
    
}




afiliados($dbconn3);