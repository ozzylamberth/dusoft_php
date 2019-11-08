<?php
 
require_once ("conexionpg.php");
require_once('../../nusoap/lib/nusoap.php');
require_once('Services_JSON.php'); 
 

$server = new nusoap_server;

$server->configureWSDL('DespachoFormulaWs', 'urn:despachoFormula_ws');

// Sumar dias habiles
$server->register('despachoFormula', array('formula' => 'xsd:string', 'tipo' => 'xsd:string'),
     array('return' => 'tns:WS_resultado'), "urn:despachoFormula_ws", "urn:despachoFormula_ws#sincronizarDespachoFormula", "rpc", "encoded", "Webservice: METODO ENCARGADO DE CONSULTAR EL DETALLE DE UNA FORMULA DISPENSADA");
		
// Estructura de respuesta
$server->wsdl->addComplexType('WS_resultado', 'complexType', 'struct', 'all', '',  array('name' => 'msj', 'type' => 'xsd:string'));
  
//INVOCA EL SERVICIO
if (isset($HTTP_RAW_POST_DATA)) {
    $input = $HTTP_RAW_POST_DATA;
} else {
    $input = implode("rn", file('php://input'));
}
$server->service($input);

/**
*@author Cristian Manuel Ardila
*+Descripcion Metodo encargado de consultar el detalle de la dispensacion de la formula
*@fecha 2016/07/09  YYYY/DD/MM
**/
function despachoFormula($formula,$tipo){	
	
	global $conexion;
	$Services_JSON = new Services_JSON;
	
	$sqlDispensados =  "SELECT  
						hc.codigo_medicamento as codigo_producto,
						dis.codigo_producto as codigo_producto_entrego,
						dis.cantidad as cantidad_entregada,
						hc.cantidad_entrega as cantidad_formulada,
						dis.total_costo,
						bd.fecha_registro as fecha_entrega


                        FROM hc_formulacion_antecedentes hc 
                        INNER JOIN inventarios_productos inv2
                        ON hc.codigo_medicamento = inv2.codigo_producto
                       INNER JOIN hc_formulacion_despachos_medicamentos des 
                       ON (des.evolucion_id = hc.evolucion_id )
                       INNER JOIN bodegas_documentos bd 
                        ON (bd.bodegas_doc_id = des.bodegas_doc_id  AND bd.numeracion =des.numeracion  )
                      
                       INNER JOIN( 
                       			SELECT med.cod_forma_farmacologica,
                                       bdd2.bodegas_doc_id,
                                       bdd2.numeracion,
                                       bdd2.codigo_producto,
                                       bdd2.cantidad,
                                       inv.subclase_id,
                                       (case when inv.sw_generico = 1 then (bdd2.total_costo *1.35) else (bdd2.total_costo*1.25) end) as total_costo
									   
                                FROM medicamentos med INNER JOIN bodegas_documentos_d bdd2
                                ON med.codigo_medicamento = bdd2.codigo_producto
                                INNER JOIN inventarios_productos inv 
                                ON (inv.codigo_producto =  med.codigo_medicamento)
                                
                       			)as dis ON (
                                			inv2.cod_forma_farmacologica = dis.cod_forma_farmacologica
                                            AND dis.bodegas_doc_id = bd.bodegas_doc_id
                                            AND dis.numeracion = bd.numeracion
                                            AND dis.subclase_id = inv2.subclase_id
                                            )
                          WHERE hc.numero_formula = {$formula}  and hc.transcripcion_medica = '{$tipo}'
							
                       AND hc_formulacion_despacho_id  = (
																SELECT max(hc_formulacion_despacho_id)
																FROM hc_formulacion_despachos_medicamentos WHERE evolucion_id = hc.evolucion_id );";
															
		$sqlPendientes = "select A.codigo_medicamento as codigo_producto,
				  (numero_unidades) as cantidad_pendiente, 
				 hc.cantidad_entrega as cantidad_formulada,
				 A.fecha_registro as fecha_pendiente
				   from  
					(  
				  
						select  
						dc.codigo_medicamento,  
						(dc.cantidad) as numero_unidades,
					   dc.fecha_registro  
						FROM  hc_pendientes_por_dispensar as dc  
						WHERE dc.evolucion_id =  (SELECT distinct(hci.evolucion_id) FROM hc_formulacion_antecedentes hci WHERE hci.numero_formula = {$formula}  and hci.transcripcion_medica = '{$tipo}' )
							  and dc.sw_estado = '0'  
							 
					 ) as A INNER JOIN hc_formulacion_antecedentes hc ON (hc.codigo_medicamento=A.codigo_medicamento)  
					  LEFT JOIN  medicamentos med ON(A.codigo_medicamento=med.codigo_medicamento)  
					   LEFT JOIN inv_med_cod_principios_activos pric ON (med.cod_principio_activo=pric.cod_principio_activo)  
					   LEFT JOIN  inventarios_productos invp ON(hc.codigo_medicamento=invp.codigo_producto)  
						WHERE hc.numero_formula = {$formula}  and hc.transcripcion_medica = '{$tipo}' ";
    
		$sqlPendientesDispensados = "SELECT  
						hc.codigo_medicamento as codigo_producto,
						dis.codigo_producto as codigo_producto_entrego,
						dis.cantidad as cantidad_entregada,
						hc.cantidad_entrega as cantidad_formulada,
						dis.total_costo,
						--max(bd.fecha_registro) as fecha_entrega
						 (bd.fecha_registro) as fecha_entrega

                        FROM hc_formulacion_antecedentes hc 
                        INNER JOIN inventarios_productos inv2
                        ON hc.codigo_medicamento = inv2.codigo_producto
                       INNER JOIN hc_formulacion_despachos_medicamentos_pendientes des 
                       ON (des.evolucion_id = hc.evolucion_id )
                       INNER JOIN bodegas_documentos bd 
                        ON (bd.bodegas_doc_id = des.bodegas_doc_id  AND bd.numeracion =des.numeracion  )
                      
                       INNER JOIN( 
                       			SELECT med.cod_forma_farmacologica,
                                       bdd2.bodegas_doc_id,
                                       bdd2.numeracion,
                                       bdd2.codigo_producto,
                                       bdd2.cantidad,
                                       inv.subclase_id,
                                       --bdd2.total_costo,
                                (case when inv.sw_generico = 1 then (bdd2.total_costo *1.35) else (bdd2.total_costo*1.25) end) as total_costo
                                
                                FROM medicamentos med INNER JOIN bodegas_documentos_d bdd2
                                ON med.codigo_medicamento = bdd2.codigo_producto
                                INNER JOIN inventarios_productos inv 
                                ON (inv.codigo_producto =  med.codigo_medicamento)
                                
                       			)as dis ON (
                                			inv2.cod_forma_farmacologica = dis.cod_forma_farmacologica
                                            AND dis.bodegas_doc_id = bd.bodegas_doc_id
                                            AND dis.numeracion = bd.numeracion
                                            AND dis.subclase_id = inv2.subclase_id
                                            )
                          WHERE hc.numero_formula = {$formula}  and hc.transcripcion_medica = '{$tipo}' 
							/*group by hc.codigo_medicamento,
						dis.codigo_producto,
						dis.cantidad,
						dis.total_costo*/";
		
	$resultDispensados = pg_query($conexion, $sqlDispensados);	
	$resultPendientes = pg_query($conexion, $sqlPendientes);	
	$resultPendientesDispensados = pg_query($conexion, $sqlPendientesDispensados);	
	
	$arregloDispensados = Array();
	$arregloPendientes = Array();
	$arregloPendientesDispensados = Array();
	$resultadoTodos = Array();
	$msj='';	
	
	
	if ($resultDispensados) {
		  
		/**
		*+Descripcion Se valida que hayan datos dispensados
		**/
		if (pg_num_rows($resultDispensados) > 0) {
			 
			 $cont =0;
			 while ($row = pg_fetch_object($resultDispensados)) {
			   
			   $arregloDispensados[$cont++] = $row;
			   
			  }
				 
		}
		
		/**
		*+Descripcion Se valida que hayan productos pendientes
		**/
		if (pg_num_rows($resultPendientes) > 0) {
			 
			 $cont2 =0;
			 while ($row = pg_fetch_object($resultPendientes)) {
			   
				$arregloPendientes[$cont2++] = $row;
			   
			  }
				 
		}
		
		/**
		*+Descripcion Se valida que hayan productos pendientes dispensados
		**/
		if (pg_num_rows($resultPendientesDispensados) > 0) {
			 
			 $cont3 =0;
			 while ($row = pg_fetch_object($resultPendientesDispensados)) {
			   
				$arregloPendientesDispensados[$cont3++] = $row;
			   
			  }
				 
		}
		
		/**
		*+Descripcion los de resultados de cada consulta se almacenan
		*			  en el arreglo asociativo
		**/
		$resultadoTodos['entregados'] = $arregloDispensados;
		$resultadoTodos['pendientes'] = $arregloPendientes;
		$resultadoTodos['pendientes_dispensados'] = $arregloPendientesDispensados;
			
			
		$msj = $Services_JSON->encode($resultadoTodos);
		
	} else {
		$continuar = false;
		$msj = "Se ha generado un error en el proceso ( " . pg_last_error($conexion) . " ) ";
	}
	return array("name"=>$msj);	
}






?>