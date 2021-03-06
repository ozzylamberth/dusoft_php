<?php


$directorio  = '/INTERFACE';
$separador   = "\\";

$datos=array();

if(file_exists($directorio) && (is_dir($directorio)))
{
    $handle=opendir($directorio);
    while ($archivo = readdir($handle)) 
    {
        $file = $directorio."/$archivo";
        if (is_file($file) && is_writable($file)) 
        {
            $contenido= @implode('', @file($file));
            $datos=explode($separador,$contenido);
            unset($contenido);
            if(RealizarInterfaceBX4(&$datos))
            {
                if(!unlink($file))
                {
                    echo "NO SE ESTAN ELIMINANDO LOS ARCHIVOS DE INTERFACE";
                }    
            }
        }        
    }    
    
    closedir($handle);
}


function RealizarInterfaceBX4($datos)
{
    //validar que el vector este correcto;
    //------------------------------------
            if(sizeof($datos)!=26)
            {
                    return false;
            }
    //------------------------------------
    
    
    //Insertar Datos en la BD (en transacciones)
    //------------------------------------
            list($dbconn) = GetDBconn();

            $query = "SELECT paciente_id FROM pacientes
            WHERE paciente_id='$datos[0]' AND tipo_id_paciente='$datos[1]'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {   return false;   }

            $dbconn->BeginTrans();
            //paciente nuevos
            if($result->EOF)
            {
                    $query = "INSERT INTO pacientes (
                                                                                paciente_id,
                                                                                tipo_id_paciente,
                                                                                primer_apellido,
                                                                                segundo_apellido,
                                                                                primer_nombre,
                                                                                segundo_nombre,
                                                                                fecha_nacimiento,
                                                                                residencia_direccion,
                                                                                residencia_telefono,
                                                                                zona_residencia,
                                                                                ocupacion_id,
                                                                                fecha_registro,
                                                                                sexo_id,
                                                                                tipo_pais_id,
                                                                                tipo_dpto_id,
                                                                                tipo_mpio_id,
                                                                                nombre_madre,
                                                                                usuario_id)
                                        VALUES ('$datos[0]','$datos[1]','$datos[2]','$datos[3]','$datos[4]','$datos[5]','$datos[6]','$datos[20]','$datos[21]','$datos[7]','$datos[23]','$datos[24]','$datos[8]','$datos[9]','$datos[10]','$datos[11]','$datos[22]',$datos[12])";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                                    $dbconn->RollbackTrans();
                                    return false;
                    }

                    $query = "INSERT INTO historias_clinicas( tipo_id_paciente,
                                                                                                            paciente_id,
                                                                                                            historia_numero,
                                                                                                            historia_prefijo,
                                                                                                            fecha_creacion)
                                        VALUES ('$datos[1]','$datos[0]','$datos[25]','','now()')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                                    $dbconn->RollbackTrans();
                                    return false;
                    }
            }
            else
            {        //existe
                    $query = "UPDATE pacientes SET
                                                                    primer_apellido='$datos[2]',
                                                                    segundo_apellido='$datos[3]',
                                                                    primer_nombre='$datos[4]',
                                                                    segundo_nombre='$datos[5]',
                                                                    fecha_nacimiento='$datos[6]',
                                                                    residencia_direccion='$datos[20]',
                                                                    residencia_telefono='$datos[21]',
                                                                    zona_residencia='$datos[7]',
                                                                    ocupacion_id='$datos[23]',
                                                                    sexo_id='$datos[8]',
                                                                    tipo_pais_id='$datos[9]',
                                                                    tipo_dpto_id='$datos[10]',
                                                                    tipo_mpio_id='$datos[11]',
                                                                    nombre_madre='$datos[22]',
                                                                    fecha_registro='$datos[24]',
                                                                    usuario_id=$datos[12]
                                                        WHERE paciente_id='$datos[0]' AND tipo_id_paciente='$datos[1]'";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $dbconn->RollbackTrans();
                                    return false;
                    }

                    $query = "UPDATE historias_clinicas SET historia_prefijo='$prefijo',
                                        historia_numero='$datos[25]'
                                        WHERE paciente_id='$datos[0]' AND tipo_id_paciente='$datos[1]'";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $dbconn->RollbackTrans();
                                    return false;
                    }

            }

            $query="SELECT nextval('ingresos_ingreso_seq')";
            $result=$dbconn->Execute($query);
            $IngresoId=$result->fields[0];

            $query = "INSERT INTO ingresos (ingreso,
                                                                            tipo_id_paciente,
                                                                            paciente_id,
                                                                            fecha_ingreso,
                                                                            causa_externa_id,
                                                                            via_ingreso_id,
                                                                            comentario,
                                                                            departamento,
                                                                            estado,
                                                                            fecha_registro,
                                                                            usuario_id,
                                                                            departamento_actual,
                                                                            autorizacion_int,
                                                                            autorizacion_ext)
                                VALUES($IngresoId,'$datos[1]','$datos[0]','$datos[24]','$datos[13]','$datos[14]','','$datos[15]','1','$datos[24]',$datos[12],'$datos[15]',NULL,NULL)";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error ingresos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
            }

            $query = "INSERT INTO cuentas ( numerodecuenta,
                                                                            empresa_id,
                                                                            centro_utilidad,
                                                                            ingreso,
                                                                            plan_id,
                                                                            estado,
                                                                            usuario_id,
                                                                            fecha_registro,
                                                                            tipo_afiliado_id,
                                                                            rango,
                                                                            autorizacion_int,
                                                                            autorizacion_ext,
                                                                            semanas_cotizadas)
                                VALUES(nextval('cuentas_numerodecuenta_seq'),'01','01',$IngresoId,".$datos[16].",1,".$datos[12].",'now()','".$datos[18]."','".$datos[17]."',NULL,NULL,".$datos[19].")";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                            $dbconn->RollbackTrans();
                            return false;
            }

            $sqls=" INSERT into pacientes_urgencias(
                                                                                    ingreso,
                                                                                    estacion_id,
                                                                                    triage_id,
                                                                                    paciente_urgencia_consultorio_id)
                            VALUES($IngresoId,'URG1',NULL,NULL)";
            $result = $dbconn->Execute($sqls);
            if ($dbconn->ErrorNo() != 0) {
                    $dbconn->RollbackTrans();
                    return false;
            }

            $dbconn->CommitTrans();
            
            return true;
    //------------------------------------

}

?>
