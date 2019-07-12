<?php

$tabla = '';
if (!isset($_REQUEST['tabla']))
{
    die("Favor especificar el tipo de archivo a cargar");
}

$tabla = $_REQUEST['tabla'];
$archivo = realpath('./') . "/{$tabla}.csv";


$dbconn3 = pg_connect("host=10.0.2.246 port=5432 dbname=dusoft user=siis password=.123mauro*");

$tabla($archivo, $dbconn3);

function afiliaciones($archivo, $dbconn3)
{

    $fila = 1;
    $insertados = 0;
    $error = 0;
    $modificasdos = 0;
    if (($gestor = fopen($archivo, "r")) !== FALSE)
    {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE)
        {

            if ($fila > 1)
            {

                $eps_afiliacion_id = $datos[0];
                $eps_tipo_afiliacion_id = $datos[1];
                $fecha_recepcion = $datos[2];
                $usuario_registro = 1350;//$datos[3];
                $fecha_registro = $datos[4];
                $usuario_ultima_actualizacion = 1350;//$datos[5];
                $fecha_ultima_actualizacion = $datos[7];
                
                /*echo print_r($datos);
                die();*/
                //eps afiliaciones
                
                $sql = "UPDATE eps_afiliaciones SET eps_afiliacion_id = '{$eps_afiliacion_id}', eps_tipo_afiliacion_id = '{$eps_tipo_afiliacion_id}',
                        fecha_recepcion = '{$fecha_recepcion}', usuario_registro = '{$usuario_registro}', fecha_registro = '{$fecha_registro}',
                        usuario_ultima_actualizacion = '{$usuario_ultima_actualizacion}', fecha_ultima_actualizacion = '{$fecha_ultima_actualizacion}' WHERE  eps_afiliacion_id = '{$eps_afiliacion_id}'  AND eps_tipo_afiliacion_id = '{$eps_tipo_afiliacion_id}'";
                
               
                $result = pg_query($sql);


                if (pg_last_error($dbconn3) != "")
                {
                    $error++;
                    echo "<p style = 'color: red;'>Error en fila  # " . $fila . " con el sql " . $sql . " descripcion: " . pg_last_error() . "</p></br>";
                }
                else
                {
                    
                     if (pg_affected_rows($result) == 0){
                         
                        $sql = "INSERT INTO eps_afiliaciones VALUES ('{$eps_afiliacion_id}', '{$eps_tipo_afiliacion_id}', '{$fecha_recepcion}', '{$usuario_registro}', '{$fecha_registro}', '{$usuario_ultima_actualizacion}', NOW(), '{$fecha_ultima_actualizacion}') RETURNING eps_afiliacion_id; ";
                        
                         $result = pg_query($sql);
                         
                         
                         if (pg_last_error($dbconn3) != ""){
                            $error++;
                            echo "<p style = 'color: red;'>Error en fila  # " . $fila . " con el sql " . $sql . " descripcion: " . pg_last_error() . "</p></br></br>";
                        }
                        else
                        {
                            $insertados++;
                        }
                     } else {
                         $modificasdos++;
                     }
                    
                    //echo "Se inserto la fila # " . $fila . " con sql " . $sql;
                }
            }

            $fila++;
        }

        echo "Filas insertadas " . $insertados . "</br></br>";
        echo "Filas con error " . $error . "</br></br>";
        echo "Filas modificadas " . $modificasdos . "</br></br>";
        fclose($gestor);
    }
}

function afiliadosDatos($archivo, $dbconn3)
{
    $fila = 1;
    $insertados = 0;
    $modificasdos = 0;
    $error = 0;
    if (($gestor = fopen($archivo, "r")) !== FALSE)
    {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE)
        {

            if ($fila > 1)
            {

                $afiliado_tipo_id = $datos[0];
                $afiliado_id = $datos[1];
                $primer_apellido = $datos[2];
                $segundo_apellido = $datos[3];
                $primer_nombre = $datos[4];
                $segundo_nombre = $datos[5];
                $fecha_nacimiento = $datos[6];
                $fecha_afiliacion_sgss = "'".$datos[7]."'";
                $tipo_sexo_id = $datos[8];
                $ciuo_88_grupo_primario = $datos[9];
                $tipo_pais_id = $datos[10];
                $tipo_dpto_id = $datos[11];
                $tipo_mpio_id = $datos[12];
                $zona_residencia = $datos[13];
                $direccion_residencia = $datos[14];
                $telefono_residencia = $datos[15];
                $telefono_movil = $datos[16];
                $usuario_registro = 1350; //$datos[17];
                $fecha_registro = $datos[18];
                $usuario_ultima_actualizacion = 1350; //$datos[19];
                $accion_ultima_actualizacion = $datos[20];
                $fecha_ultima_actualizacion = $datos[21];
                
                if($datos[7] == '\N'){
                   $fecha_afiliacion_sgss = "now()"; 
                }
                //eps afiliaciones
                $sql = "UPDATE eps_afiliados_datos SET primer_apellido='{$primer_apellido}', segundo_apellido='{$segundo_apellido}', primer_nombre='{$primer_nombre}', segundo_nombre='{$segundo_nombre}',fecha_nacimiento='{$fecha_nacimiento}',fecha_afiliacion_sgss={$fecha_afiliacion_sgss},tipo_sexo_id='{$tipo_sexo_id}',ciuo_88_grupo_primario='{$ciuo_88_grupo_primario}',tipo_pais_id='{$tipo_pais_id}',tipo_dpto_id='{$tipo_dpto_id}',tipo_mpio_id='{$tipo_mpio_id}',zona_residencia='{$zona_residencia}',direccion_residencia='{$direccion_residencia}',telefono_residencia='{$telefono_residencia}',telefono_movil='{$telefono_movil}',usuario_registro='{$usuario_registro}',fecha_registro='{$fecha_registro}',usuario_ultima_actualizacion='{$usuario_ultima_actualizacion}',accion_ultima_actualizacion='{$accion_ultima_actualizacion}',fecha_ultima_actualizacion='{$fecha_ultima_actualizacion}' WHERE afiliado_id='{$afiliado_id}' AND afiliado_tipo_id='{$afiliado_tipo_id}'";
                
                
                

                $result = pg_query($sql);


                if (pg_last_error($dbconn3) != "")
                {
                    $error++;
                    echo "<p style = 'color: red;'>Error en fila  # " . $fila . " con el sql " . $sql . " descripcion: " . pg_last_error() . "</p></br></br>";
                }
                else
                {
                    /* $insertados++;
                      echo "Se actualizo la fila # ".$fila. " con sql ".$sql; */

                    if (pg_affected_rows($result) == 0)
                    {
                        
                       /* echo $sql . "</br>";
                        echo pg_affected_rows($result);
                        die();*/

                        $sql = "INSERT INTO eps_afiliados_datos(
                                afiliado_tipo_id, afiliado_id, primer_apellido, segundo_apellido, 
                                primer_nombre, segundo_nombre, fecha_nacimiento, fecha_afiliacion_sgss, 
                                tipo_sexo_id, ciuo_88_grupo_primario, tipo_pais_id, tipo_dpto_id, 
                                tipo_mpio_id, zona_residencia, direccion_residencia, telefono_residencia, 
                                telefono_movil, usuario_registro, fecha_registro, usuario_ultima_actualizacion, 
                                accion_ultima_actualizacion, fecha_ultima_actualizacion)
                                VALUES ('{$afiliado_tipo_id}', '{$afiliado_id}', '{$primer_apellido}', '{$segundo_apellido}', 
                                '{$primer_nombre}', '{$segundo_nombre}', '{$fecha_nacimiento}', {$fecha_afiliacion_sgss}, 
                                '{$tipo_sexo_id}', '{$ciuo_88_grupo_primario}', '{$tipo_pais_id}', '{$tipo_dpto_id}', 
                                '{$tipo_mpio_id}', '{$zona_residencia}', '{$direccion_residencia}', '{$telefono_residencia}', 
                                '{$telefono_movil}', '{$usuario_registro}', '{$fecha_registro}', '{$usuario_ultima_actualizacion}', 
                                '{$accion_ultima_actualizacion}', '{$fecha_ultima_actualizacion}')";

                        $result = pg_query($sql);

                        if (pg_last_error($dbconn3) != "")
                        {
                            $error++;
                            echo "<p style = 'color: red;'>Error en fila  # " . $fila . " con el sql " . $sql . " descripcion: " . pg_last_error() . "</p></br></br>";
                        }
                        else
                        {
                           //  echo "Se inserto la fila # " . $fila . " con sql </br>" ;
                            $insertados++;
                        }
                    }
                    else
                    {
                        // echo "Se modifico la fila # " . $fila . " con sql </br>" ;
                        $modificasdos++;
                    }
                }
            }

            $fila++;
        }

        echo "Filas insertadas " . $insertados . "</br></br>";
        echo "Filas modificadas " . $modificasdos . "</br></br>";
        echo "Filas con error " . $error . "</br></br>";
        fclose($gestor);
    }
}

function afiliados($archivo, $dbconn3)
{
    $fila = 1;
    $insertados = 0;
    $error = 0;
    $modificasdos = 0;
    if (($gestor = fopen($archivo, "r")) !== FALSE)
    {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE)
        {

            if ($fila > 1)
            {

                $eps_afiliacion_id = $datos[0];
                $afiliado_tipo_id = $datos[1];
                $afiliado_id = $datos[2];
                $eps_tipo_afiliado_id = $datos[3];
                $fecha_afiliacion = $datos[4];
                $eps_anterior = $datos[5];
                $semanas_cotizadas_eps_anterior = $datos[6];
                $semanas_cotizadas = $datos[7];
                $estado_afiliado_id = $datos[8];
                $subestado_afiliado_id = $datos[9];
                $observaciones = $datos[10];
                $usuario_registro = 1350; //$datos[11];
                $fecha_registro = $datos[12];
                $usuario_ultima_actualizacion = 1350;//$datos[13];
                $accion_ultima_actualizacion = $datos[14];
                $fecha_ultima_actualizacion = $datos[15];
                $plan_atencion = $datos[16];
                $tipo_afiliado_atencion = $datos[17];
                $rango_afiliado_atencion = $datos[18];
                $eps_punto_atencion_id = $datos[19];
                $fecha_vencimiento = $datos[20];
                $fecha_afiliacion_eps_anterior = $datos[21];

                //eps afiliaciones

                $sql = "UPDATE eps_afiliados SET eps_tipo_afiliado_id='{$eps_tipo_afiliado_id}', estado_afiliado_id='{$estado_afiliado_id}',subestado_afiliado_id='{$subestado_afiliado_id}',plan_atencion='{$plan_atencion}',tipo_afiliado_atencion='{$tipo_afiliado_atencion}',rango_afiliado_atencion='{$rango_afiliado_atencion}',eps_punto_atencion_id='{$eps_punto_atencion_id}' WHERE eps_afiliacion_id='{$eps_afiliacion_id}' AND afiliado_tipo_id='{$afiliado_tipo_id}' AND afiliado_id='{$afiliado_id}'";

                $result = pg_query($sql);



                if (pg_last_error($dbconn3) != "")
                {
                    $error++;
                    echo "<p style = 'color: red;'>Error en fila  # " . $fila . " con el sql " . $sql . " descripcion: " . pg_last_error() . "</p></br>";
                }
                else
                {
                    if (pg_affected_rows($result) == 0)
                    {


                        $sql = "INSERT INTO eps_afiliados VALUES ('{$eps_afiliacion_id}', '{$afiliado_tipo_id}', '{$afiliado_id}', '{$eps_tipo_afiliado_id}', '{$fecha_afiliacion}', '{$eps_anterior}','{$semanas_cotizadas_eps_anterior}','{$semanas_cotizadas}','{$estado_afiliado_id}','{$subestado_afiliado_id}','{$observaciones}','{$usuario_registro}','{$fecha_registro}','{$usuario_ultima_actualizacion}','{$accion_ultima_actualizacion}','{$fecha_ultima_actualizacion}','{$plan_atencion}','{$tipo_afiliado_atencion}','{$rango_afiliado_atencion}','{$eps_punto_atencion_id}',NULL,NULL) ";

                        $result = pg_query($sql);

                        if (pg_last_error($dbconn3) != "")
                        {
                            $error++;
                            echo "<p style = 'color: red;'>Error en fila  # " . $fila . " con el sql " . $sql . " descripcion: " . pg_last_error() . "</p></br>";
                        }
                        else
                        {
                             //echo "Se inserto la fila # " . $fila . " con sql " ;
                            $insertados++;
                        }
                    }
                    else
                    {
                         //echo "Se modifico la fila # " . $fila . " con sql " ;
                        $modificasdos++;
                    }
                }
            }

            $fila++;
        }

        echo "Filas insertadas " . $insertados . "</br></br>";
        echo "Filas modificadas " . $modificasdos . "</br></br>";
        echo "Filas con error " . $error . "</br></br>";
        fclose($gestor);
    }
}