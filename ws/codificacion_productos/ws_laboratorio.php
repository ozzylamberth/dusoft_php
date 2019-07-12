<?php

/****************************************************************************************************

* Web Service ws_laboratorio.php
*
* Fecha: 06-V-2013
* Autor: Steven H. Gamboa
* 
* Descripcion:  Contiene 2 Funciones para ingreso y actualizacion de laboratorios, en la base de datos de Cosmitet.
*
*---------------------------------------------------------------------------------------------------------
* NOTA: La actualizacion del estado, con el WS ws_molecula.php, en la funcion cambiar_estado, lo realiza.
*---------------------------------------------------------------------------------------------------------
*
* insertar_laboratorio: Permite ingresar el laboratorio, en la tabla inv_laboratorios.
*
* modificar_laboratorio: Permite actualizar los datos del laboratorio, descripcion, direccion, 
*						 telefono, pais, en la tabla inv_laboratorios
*
*
*
****************************************************************************************************/

require_once('../../nusoap/lib/nusoap.php'); //Ruta relativa
$ns="http://10.0.0.44/dusoft/ws/codificacion_productos/ws_laboratorio.php"; //Cambiar URL
$server = new soap_server();
$server->configureWSDL('LABORATORIOS (CLASES)',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('insertar_laboratorio',array('laboratorio_id' => 'xsd:string',
                                            'descripcion' => 'xsd:string',
                                            'direccion' => 'xsd:string',
                                            'telefono' => 'xsd:string',
                                            'pais' => 'xsd:string',
                                            'estado' => 'xsd:string',
                                            'usuario_id' => 'xsd:int'
                                            ),
                                      array('return' => 'xsd:string'),
                                      $ns);
                                      
$server->register('modificar_laboratorio',array('laboratorio_id' => 'xsd:string',
                                            'descripcion' => 'xsd:string',
                                            'direccion' => 'xsd:string',
                                            'telefono' => 'xsd:string',
                                            'pais' => 'xsd:string',
                                            'usuario_id' => 'xsd:int'
                                            ),
                                      array('return' => 'xsd:string'),
                                      $ns);

function insertar_laboratorio ($laboratorio_id,$descripcion,$direccion,$telefono,$pais,$estado,$usuario_id)
{
	require_once("conexionproductopg.php");
	
	$query = "INSERT INTO inv_laboratorios ( laboratorio_id,
											 descripcion,
											 direccion,
											 telefono,
											 tipo_pais_id,
											 estado,
											 usuario_id
										   ) 
			  						VALUES ( '".$laboratorio_id."',
									  		 '".$descripcion."',
			  		   						 '".$direccion."',
									  		 '".$telefono."',
									  		 '".$pais."',
									  		 '".$estado."',
			  								  ".$usuario_id." ); ";

	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos";
    }
}

function modificar_laboratorio($laboratorio_id,$descripcion,$direccion,$telefono,$pais,$usuario_id)
{
	require_once("conexionproductopg.php");
	
	$query  = "	UPDATE  inv_laboratorios 
				SET		descripcion = '".strtoupper($descripcion)."', 
						telefono = '".$telefono."', 
						direccion = '".$direccion."', 
						tipo_pais_id = '".$pais."', 
						usuario_id = ".$usuario_id.", 
						fecha_registro = NOW()
				WHERE	laboratorio_id = '".$laboratorio_id."'; ";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos";
    }
}
                                      

if(isset($HTTP_RAW_POST_DATA))
{
	$input = $HTTP_RAW_POST_DATA;
}
else
{
	$input = implode("rn", file('php://input'));
}
$server->service($input);
?>