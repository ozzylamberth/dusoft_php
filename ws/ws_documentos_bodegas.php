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

$ns="http://10.0.2.237/APP/DUSOFT_DUANA/WS/ws_documentos_bodegas.php";
$server = new soap_server();
$server->configureWSDL('GESTION DOCUMENTOS BODEGAS',$ns);
$server->wsdl->schemaTargetNamespace=$ns;


$server->register('crear_documento_pedido_farmacia',array('empresa_id' => 'xsd:string',
									   'prefijo' => 'xsd:string',
									   'numero' => 'xsd:int',
									   'farmacia_id' => 'xsd:string',
									   'solicitud_prod_a_bod_ppal_id' => 'xsd:int',
									   'usuario_id' => 'xsd:int',
									   'fecha_registro' => 'xsd:string',
									   'sw_revisado' => 'xsd:string',
									   'rutaviaje_destinoempresa_id' => 'xsd:string'),array('id' => 'xsd:int', 'descripcion' => 'xsd:string'),$ns);
									   
									   
function crear_documento_pedido_farmacia($empresa_id,$prefijo,$numero,$farmacia_id,$solicitud_prod_a_bod_ppal_id,$usuario_id,$fecha_registro,$sw_revisado,$rutaviaje_destinoempresa_id)
{
		require_once('../conexiondbpg.php');
		
		$query = "INSERT INTO inv_bodegas_movimiento_despachos_farmacias
                (empresa_id, prefijo, numero, farmacia_id, solicitud_prod_a_bod_ppal_id, usuario_id, fecha_registro, sw_revisado, rutaviaje_destinoempresa_id)
                VALUES ('".$empresa_id."', '".$prefijo."', '".$numero."', '".$farmacia_id."', '".$solicitud_prod_a_bod_ppal_id."', ".$usuario_id.", '".$fecha_registro."', '".$sw_revisado."', '".$rutaviaje_destinoempresa_id."');";
				
		echo "<br><br><br>".$query."<br><br><br>";
				
		$result = pg_query($con,$query);
        
        if($result)
        {
            return array('id' => '1', 'descripcion' => 'OK');
        }
        else
        {
            return array('id' => '0', 'descripcion' => 'Error');
        }
}


$server->register('crear_documento_pedido_cliente',array('empresa_id' => 'xsd:string',
									   'prefijo' => 'xsd:string',
									   'numero' => 'xsd:int',
									   'tipo_id_tercero' => 'xsd:string',
									   'tercero_id' => 'xsd:string',
									   'usuario_id' => 'xsd:int',
									   'fecha_registro' => 'xsd:string',
									   'pedido_cliente_id' => 'xsd:int',
									   'rutaviaje_destinoempresa_id' => 'xsd:string'),array('id' => 'xsd:int', 'descripcion' => 'xsd:string'),$ns);
									   
									   
function crear_documento_pedido_cliente($empresa_id,$prefijo,$numero,$tipo_id_tercero,$tercero_id,$usuario_id,$fecha_registro,$pedido_cliente_id,$rutaviaje_destinoempresa_id)
{
		require_once('../conexiondbpg.php');
		
		$query = "INSERT INTO inv_bodegas_movimiento_despachos_clientes
                (empresa_id, prefijo, numero, tipo_id_tercero, tercero_id, usuario_id, fecha_registro, pedido_cliente_id, rutaviaje_destinoempresa_id)
                VALUES ('".$empresa_id."', '".$prefijo."', '".$numero."', '".$tipo_id_tercero."', '".$tercero_id."', ".$usuario_id.", '".$fecha_registro."', '".$pedido_cliente_id."', '".$rutaviaje_destinoempresa_id."');";
				
		echo "<br><br><br>".$query."<br><br><br>";
				
		$result = pg_query($con,$query);
        
        if($result)
        {
            return array('id' => '1', 'descripcion' => 'OK');
        }
        else
        {
            return array('id' => '0', 'descripcion' => 'Error');
        }
}



if(isset($HTTP_RAW_POST_DATA)){
$input = $HTTP_RAW_POST_DATA;
}
else{
$input = implode("rn", file('php://input'));
}
$server->service($input);


?>