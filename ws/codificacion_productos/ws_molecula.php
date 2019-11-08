<?php

/*******************************************************************************************************************
* Web Service ws_molecula.php
*
* Fecha: 03-V-2013
* Autor: Steven H. Gamboa
* 
* Descripcion:  Contiene 3 Funciones para ingreso y actualizacion de moleculas e insumos, en la base de datos de Cosmitet.
* 
* insertar_molecula: Permite ingresar la molecula en las tablas inv_moleculas y inv_med_cod_principios_activos
*
* modificar_molecula: Permite actualizar la descripcion, concentracion y unidad de medidada, en la tablas
* 					  inv_moleculas, inv_subclases_inventarios, inv_med_cod_principios_activos
*
* cambiar_estado: Permite cambiar el estado de la molecula sea activo (1) o inactivo (0) en la tabla inv_moleculas
*
*******************************************************************************************************************
*/

require_once('../../nusoap/lib/nusoap.php'); //Ruta relativa
$ns="http://10.0.0.44/dusoft/ws/codificacion_productos/ws_molecula.php"; //Cambiar URL
$server = new soap_server();
$server->configureWSDL('MOLECULAS (SUBCLASES)',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('insertar_molecula',array('molecula_id' => 'xsd:string',
                                            'descripcion' => 'xsd:string',
                                            'concentracion' => 'xsd:string',
                                            'unidad_medida_medicamento_id' => 'xsd:string',
                                            'sw_medicamento' => 'xsd:string',
                                            'estado' => 'xsd:string'
                                            ),
                                      array('return' => 'xsd:string'),
                                      $ns);
                                      
$server->register('modificar_molecula',array('molecula_id' => 'xsd:string',
                                             'descripcion' => 'xsd:string',
                                             'concentracion' => 'xsd:string',
                                             'unidad_medida_medicamento_id' => 'xsd:string',
                                             'sw_medicamento' => 'xsd:string'
                                            ),
                                       array('return' => 'xsd:string'),
                                       $ns);

$server->register('cambiar_estado',array('tabla' => 'xsd:string',
                                             'campo' => 'xsd:string',
                                             'valor' => 'xsd:string',
                                             'id' => 'xsd:string',
                                             'campo_id' => 'xsd:string'
                                            ),
                                       array('return' => 'xsd:string'),
                                       $ns);
                                       
function insertar_molecula($molecula_id,$descripcion,$concentracion,$unidad_medida_medicamento_id,$sw_medicamento,$estado)
{
	require_once("conexionproductopg.php");
        
    $query = "INSERT INTO inv_moleculas ( molecula_id, 
        								  descripcion, 
        								  concentracion, 
        								  unidad_medida_medicamento_id, 
        								  sw_medicamento, 
        								  estado)
              VALUES ('".$molecula_id."', 
               		  '".$descripcion."', 
               		  '".$concentracion."',
               		  '".$unidad_medida_medicamento_id."',
               		  '".$sw_medicamento."',
               		  '".$estado."'); ";
                  		  
    $query .= " INSERT INTO inv_med_cod_principios_activos ( cod_principio_activo, 
       													    descripcion) 
       			VALUES ('".$molecula_id."', 
       					'".$descripcion."'); ";
                  
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

function modificar_molecula($molecula_id,$descripcion,$concentracion,$unidad_medida_medicamento_id,$sw_medicamento)
{
	require_once("conexionproductopg.php");
	
	$query = "	UPDATE 	inv_moleculas 
				SET 	descripcion = '".$descripcion."', 
						concentracion = '".$concentracion."', 
						unidad_medida_medicamento_id = '".$unidad_medida_medicamento_id."' 
				WHERE 	molecula_id = '".$molecula_id."';
				
				UPDATE 	inv_subclases_inventarios 
				SET 	descripcion = '".$descripcion."' 
				WHERE 	subclase_id = '".$molecula_id."';
				
				UPDATE 	inv_med_cod_principios_activos  
				SET 	descripcion = '".$descripcion."' 
				WHERE 	cod_principio_activo = '".$molecula_id."'; ";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el update de los datos";
    }
}

function cambiar_estado($tabla,$campo,$valor,$id,$campo_id)
{
	require_once("conexionproductopg.php");
	
	$query  = "UPDATE ".$tabla." ";
    $query .= "SET ".$campo." = '".$valor."'";
    $query .= "WHERE ";
    $query .= $campo_id."='".$id."';";
    
    $result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el update de los datos";
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