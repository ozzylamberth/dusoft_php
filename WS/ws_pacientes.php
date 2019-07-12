<?php
/**
 * WEB SERVICE GESTION DATOS PACIENTES
 * $Id: ws_pacientes.php,v 1.0 2013/01/22 08:45:00 roma Exp $
 * @copyright (C) 2013 DUANA & CIA LTDA
 * @author Ronald Marin - roma
 * @file
 * Servicio que gestiona los request de consumo externo para la creacion y 
 * actualizacion de datos de pacientes en la base local.
 *
 * La implementacion con nusoap esta soportada por GNU Library or Lesser General Public License (LGPL)
 * All code implementation in this web service using nusoap is supported by the GNU General Public License.
 *
**/

require_once('../nusoap/lib/nusoap.php');

$ns="http://10.0.2.170/DUSOFT_PRUEBAS/WS/ws_pacientes.php";
$server = new soap_server();
$server->configureWSDL('GESTION DATOS PACIENTES',$ns);
$server->wsdl->schemaTargetNamespace=$ns;


$server->register('crear_paciente',array('paciente_id' => 'xsd:string',
														 'tipo_id_paciente' => 'xsd:string',
														 'primer_apellido' => 'xsd:string',
														 'segundo_apellido' => 'xsd:string',
														 'primer_nombre' => 'xsd:string',
														 'segundo_nombre' => 'xsd:string',
														 'fecha_nacimiento' => 'xsd:string',
														 'fecha_nacimiento_es_calculada' => 'xsd:string',
														 'residencia_direccion' => 'xsd:string',
														 'residencia_telefono' => 'xsd:string',
														 'zona_residencia' => 'xsd:string',       
														 'ocupacion_id' => 'xsd:string',         
														 'sexo_id' => 'xsd:string', 
														 'tipo_estado_civil_id' => 'xsd:string', 
														 'tipo_pais_id' => 'xsd:string',      
														 'tipo_dpto_id' => 'xsd:string', 
														 'tipo_mpio_id' => 'xsd:string', 
														 'paciente_fallecido' => 'xsd:string', 
														 'usuario_id' => 'xsd:int', 
														 'nombre_madre' => 'xsd:string',
														 'observaciones' => 'xsd:string', 
														 'tipo_comuna_id' => 'xsd:string', 
														 'tipo_barrio_id' => 'xsd:string', 
														 'tipo_estrato_id' => 'xsd:string', 
														 'lugar_expedicion_documento' => 'xsd:string', 
														 'sw_ficha' => 'xsd:string', 
														 'celular_telefono' => 'xsd:string', 
														 'email' => 'xsd:string', 
														 'tipo_bloqueo_id' => 'xsd:string'),array('id' => 'xsd:int','descripcion' => 'xsd:string'),$ns);
														 
													
function crear_paciente($paciente_id,$tipo_id_paciente,$primer_apellido,$segundo_apellido,$primer_nombre,$segundo_nombre,$fecha_nacimiento, 
								   $fecha_nacimiento_es_calculada,$residencia_direccion,$residencia_telefono,$zona_residencia,$ocupacion_id,       
								   $sexo_id,$tipo_estado_civil_id,$tipo_pais_id,$tipo_dpto_id,$tipo_mpio_id,$paciente_fallecido,$usuario_id,$nombre_madre, 
								   $observaciones,$tipo_comuna_id,$tipo_barrio_id,$tipo_estrato_id,$lugar_expedicion_documento,$sw_ficha, 
								   $celular_telefono,$email,$tipo_bloqueo_id)
{
 require_once('../conexiondbpg.php');
 
 $query = "INSERT INTO pacientes ( 
								paciente_id,
								tipo_id_paciente,
								primer_apellido,
								segundo_apellido,
								primer_nombre,
								segundo_nombre,
								fecha_nacimiento,
								fecha_nacimiento_es_calculada, 
								residencia_direccion,
								residencia_telefono,
								zona_residencia,       
								ocupacion_id,                 
								sexo_id,
								tipo_estado_civil_id,
								tipo_pais_id,         
								tipo_dpto_id,          
								tipo_mpio_id,          
								paciente_fallecido,
								usuario_id,
								nombre_madre,
								observaciones,
								tipo_comuna_id,
								tipo_barrio_id,
								tipo_estrato_id,
								lugar_expedicion_documento,
								sw_ficha,
								celular_telefono,
								email,
								tipo_bloqueo_id 
                                ) 
							    VALUES
								(    ";
 $query .=                  "'".$paciente_id. "', ";
 $query .=                  "'".$tipo_id_paciente. "', ";
 $query .=                  "'".$primer_apellido."', ";
 $query .=                  "'".$segundo_apellido."', ";
 $query .=                  "'".$primer_nombre."', ";
 $query .=                  "'".$segundo_nombre."', ";
 $query .=                  "'".$fecha_nacimiento."', ";
 $query .=                  "'".$fecha_nacimiento_es_calculada."', "; 
 $query .=                  "'".$residencia_direccion."', ";
 $query .=                  "'".$residencia_telefono."', ";
 $query .=                  "'".$zona_residencia."', ";       
 $query .=                  "'".$ocupacion_id."', ";                 
 $query .=                  "'".$sexo_id."', ";
 $query .=                  "'".$tipo_estado_civil_id."', ";
 $query .=                  "'".$tipo_pais_id."', ";         
 $query .=                  "'".$tipo_dpto_id."', ";          
 $query .=                  "'".$tipo_mpio_id."', ";          
 $query .=                  "'".$paciente_fallecido."', ";
 $query .=                  " ".$usuario_id.", ";
 $query .=                  "'".$nombre_madre."', ";
 $query .=                  "'".$observaciones."', ";
 $query .=                  "'".$tipo_comuna_id."', ";
 $query .=                  "'".$tipo_barrio_id."', ";
 $query .=                  "'".$tipo_estrato_id."', ";
 $query .=                  "'".$lugar_expedicion_documento."', ";
 $query .=                  "'".$sw_ficha."', ";
 $query .=                  "'".$celular_telefono."', ";
 $query .=                  "'".$email."', ";
 $query .=                  "'".$tipo_bloqueo_id."'  "; 
 $query .=                  " )  "; 
 
 $result = pg_query($con,$query);

 $confirm = array();
 if($result)
 {
  $confirm['id'] = 1;
  $confirm['descripcion'] = 'Datos guardados';
 } 
 else
 {
  $confirm['id'] = 0;
  $confirm['descripcion'] = 'Error al guardar'; 
 }
 
return $confirm; 
								
}



if(isset($HTTP_RAW_POST_DATA)){
$input = $HTTP_RAW_POST_DATA;
}
else{
$input = implode("rn", file('php://input'));
}
$server->service($input);


?>