<?php
require_once('CalculoFechas.class.php');
require_once ("conexionpg.php");
require_once('../../nusoap/lib/nusoap.php');
$server = new nusoap_server;

$server->configureWSDL('DiasHabilesWs', 'urn:diasHabiles_ws');

// Sumar dias habiles
$server->register('SumarDiasHabiles', array('fecha_base' => 'xsd:string', 'dias_vigencia' => 'xsd:string'),
     array('return' => 'tns:WS_resultado'), "urn:diasHabiles_ws", "urn:diasHabiles_ws#sincronizarFormula", "rpc", "encoded", "Webservice: METODO ENCARGADO DE CALCULAR LOS DIAS HABILES DE UNA FECHA");
		
// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '', array('msj' => array('name' => 'msj', 'type' => 'xsd:string')));
  
function SumarDiasHabiles($fecha_base, $dias_vigencia) {
		
		// $dias_dipensados = ModuloGetVar('', '', 'dispensacion_dias_vigencia_formula');
		$fechaMaximaI=intervaloFechaformula($fecha_base,$dias_vigencia,"+");//+3	
		$calculo_fechas = new CalculoFechas(); 
		$cantidad_dias_habiles = $calculo_fechas->obtener_dias_habiles($fecha_base,$fechaMaximaI);
		
		while($cantidad_dias_habiles < $dias_vigencia){   
			$calculo_fechas = new CalculoFechas();   
			list($a, $m, $d) = split("-",$fechaMaximaI);
			$fechaMaximaI = date("Y-m-d", (mktime(0, 0, 0, $m, ($d+1), $a)));
			$cantidad_dias_habiles = $calculo_fechas->obtener_dias_habiles($fecha_base,$fechaMaximaI);        
		} 
	
        return array('msj' => $fechaMaximaI);
    
}

//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);

function intervaloFechaformula($fecha,$dias,$operacion){	
	global $conexion;
	$sql = "select to_char(fecha, 'yyyy-mm-dd')as fecha
				from 
			  (SELECT CAST('$fecha' AS DATE) $operacion CAST('$dias days' AS INTERVAL) as fecha)as d;";   
	$result = pg_query($conexion, $sql);	
	if ($result) {
		$continuar = true;
		$msj="";		
		if (pg_num_rows($result) > 0) {
			
			while ($row = pg_fetch_row($result)) {
			  $continuar = true;
			  $msj = $row[0];
			}		   
		}
	} else {
		$continuar = false;
		$msj = "Se ha generado un error en el proceso ( " . pg_last_error($conexion) . " ) ";
	}
	return array($msj);		
}

?>