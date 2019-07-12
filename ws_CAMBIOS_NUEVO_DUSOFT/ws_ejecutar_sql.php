<?php
/***************************************************************************************************
*
* Web Service ws_afiliaciones.php
*
* Fecha: 20-VI-2013
* Autor: Steven H. Gamboa
*
* Descripción:
*
***************************************************************************************************/

require_once('../nusoap/lib/nusoap.php');
$ns="http://10.0.0.44/SIIS/ws/ws_ejecutar_sql.php";
$server = new soap_server();
$server->configureWSDL('EJECUTAR QUERY',$ns);
$server->wsdl->schemaTargetNamespace=$ns;

$server->register('ejecutar_query',array( 'sql'=>'xsd:string'
								   ),
							 array('return' => 'xsd:string'),
							 $ns);

function ejecutar_query($query)
{
	require_once("codificacion_productos/conexionpg.php");
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos: ".$query;
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