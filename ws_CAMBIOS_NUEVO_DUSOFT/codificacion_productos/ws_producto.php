<?php
/****************************************************************************************************
* Web Service ws_producto.php
*
* Fecha: 06-V-2013
* Autor: Steven H. Gamboa
* 
* Descripcion:  Contiene 99999 Funciones para ingreso y actualizacion de Productos, en la base de datos de Cosmitet.
*
* 1. insertar_grupo: Permite ingresar el grupo en la tabla inv_grupos_inventarios.
*
* 2. insertar_clasesagrupo: Permite ingresar una o varias clases al grupo asociado, en tabla inv_clases_inventarios.
*
* 3. insertar_subclaseaclase: Permite asociar una o mas subclases a la clase, en la tabla inv_subclases_inventarios.
*
* 4. insertar_productoinsumo: Permite ingresar la informacion de todo el producto, tabla inventarios_productos.
*
* 5. modificar_grupo: Permite actualizar la descripcion del grupo en la tabla inv_grupos_inventarios.
*
* 6. borrar_registro: Permite la eliminacion de un grupo. Puede ser usada para borrar otros elementos.
*
* 7. borrar_clase: Permite la eliminacion de la clase, no debe tener asociados ni productos ni subclases.
*
* 8. borrar_subclase: Permite la eliminacion de la clase, no debe tener asociados productos. AQUI
*
****************************************************************************************************/

require_once('../../nusoap/lib/nusoap.php'); //Ruta relativa
///$ns="http://10.0.0.44/SIIS/ws/codificacion_productos/ws_producto.php"; //Cambiar URL
$ns="http://10.0.2.237/APP/PRUEBAS_CRISTIAN/ws/codificacion_productos/ws_producto.php"; //Cambiar URL
$server = new soap_server();
$server->configureWSDL('CLASIFICACION GENERAL DE LOS PRODUCTOS',$ns);
$server->wsdl->schemaTargetNamespace=$ns;
$server->register('insertar_principioactivo',array('subclase_id' => 'xsd:string',
                                                  'descripcion' => 'xsd:string'
                                                  ),
                                        array('return' => 'xsd:string'),
                                        $ns);

$server->register('Modificar_principioActivo_medicamentos',array('subclase_id' => 'xsd:string',
                                                  'producto_id' => 'xsd:string'
                                                  ),
                                        array('return' => 'xsd:string'),
                                        $ns);
$server->register('insertar_grupo',array('grupo_id' => 'xsd:string',
                                         'descripcion' => 'xsd:string',
                                         'sw_medicamento' => 'xsd:string'
                                        ),
                                   array('return' => 'xsd:string'),
                                      $ns);
                                      
$server->register('insertar_clasesagrupo',array('grupo_id' => 'xsd:string',
                                          		'clase_id' => 'xsd:string',
                                          		'descripcion' => 'xsd:string',
                                          		'laboratorio_id' => 'xsd:string',
                                          		'sw_tipo_empresa' => 'xsd:string'
                                         		),
                                    	array('return' => 'xsd:string'),
                                      	$ns);

$server->register('insertar_subclaseaclase',array('grupo_id' => 'xsd:string',
                                          		  'clase_id' => 'xsd:string',
                                          		  'subclase_id' => 'xsd:string',
                                          		  'descripcion' => 'xsd:string',
                                          		  'molecula_id' => 'xsd:string'
                                         		),
                                    		array('return' => 'xsd:string'),
                                      		$ns);

$server->register('insertar_productoinsumo',array(	'grupo_id' => 'xsd:string',
													'clase_id' => 'xsd:string',
													'subclase_id' => 'xsd:string',
													'producto_id' => 'xsd:string',
													'descripcion' => 'xsd:string',
													'descripcion_abreviada' => 'xsd:string',
													'codigo_producto' => 'xsd:string',
													'codigo_cum' => 'xsd:string',
													'codigo_alterno' => 'xsd:string',
													'codigo_barras' => 'xsd:string',
													'fabricante_id' => 'xsd:string',
													'sw_pos' => 'xsd:string',
													'cod_acuerdo228_id' => 'xsd:string',
													'unidad_id' => 'xsd:string',
													'contenido_unidad_venta' => 'xsd:string',
													'cod_anatofarmacologico' => 'xsd:string',
													'mensaje_id' => 'xsd:string',
													'codigo_mindefensa' => 'xsd:string',
													'codigo_invima' => 'xsd:string',
													'vencimiento_codigo_invima' => 'xsd:string',
													'titular_reginvima_id' => 'xsd:string',
													'porc_iva' => 'xsd:string',
													'sw_generico' => 'xsd:string',
													'sw_venta_directa' => 'xsd:string',
													'tipo_pais_id' => 'xsd:string',
													'tipo_producto_id' => 'xsd:string',
													'presentacioncomercial_id' => 'xsd:string',
													'cantidad' => 'xsd:string',
													'tratamiento_id' => 'xsd:string',
													'usuario_id' => 'xsd:string',
													'cod_adm_presenta' => 'xsd:string',
													'dci_id' => 'xsd:string',
													'estado_unico' => 'xsd:string',
													'cod_forma_farmacologica' => 'xsd:string',
													'sw_solicita_autorizacion' => 'xsd:string',
                          'rips_no_pos' => 'xsd:string'
                                         		),
                                    		array('return' => 'xsd:string'),
                                      		$ns);

$server->register('insertar_productomedicamento',array( 'codigo_medicamento' => 'xsd:string',
                                          		  		'descripcion_alerta' => 'xsd:string',
                                          		  		'sw_farmacovigilancia' => 'xsd:string',
                                          		  		'sw_fotosensible' => 'xsd:string',
                                          		  		'cod_anatomofarmacologico' => 'xsd:string',
                                          		  		'cod_principio_activo' => 'xsd:string',
                                          		  		'cod_forma_farmacologica' => 'xsd:string',
                                          		  		'sw_pos' => 'xsd:string',
                                          		  		'codigo_cum' => 'xsd:string',
                                          		  		'dias_previos_vencimiento' => 'xsd:string',
                                          		  		'sw_liquidos_electrolitos' => 'xsd:string',
                                          		  		'sw_uso_controlado' => 'xsd:string',
                                          		  		'sw_antibiotico' => 'xsd:string',
                                          		  		'sw_refrigerado' => 'xsd:string',
                                          		  		'sw_alimento_parenteral' => 'xsd:string',
                                          		  		'unidad_medida_medicamento_id' => 'xsd:string',
                                          		  		'sw_alimento_enteral' => 'xsd:string',
                                          		  		'concentracion_forma_farmacologica' => 'xsd:string',
                                          		  		'cod_concentracion' => 'xsd:string',
                                          		  		'usuario_id' => 'xsd:int'
                                         		),
                                    		array('return' => 'xsd:string'),
                                      		$ns);

$server->register('modificar_grupo',array('grupo_id' => 'xsd:string',
                                          'descripcion' => 'xsd:string'
                                         ),
                                    array('return' => 'xsd:string'),
                                      $ns);
                                      
$server->register('modificar_productoinsumo',array('subclase_id' => 'xsd:string',
												   'descripcion' => 'xsd:string',
                                            	   'descripcion_abreviada' => 'xsd:string',
                                            	   'codigo_cum' => 'xsd:string',
                                            	   'codigo_alterno' => 'xsd:string',
                                            	   'codigo_barras' => 'xsd:string',
                                            	   'fabricante_id' => 'xsd:string',
                                            	   'sw_pos' => 'xsd:string',
                                            	   'cod_acuerdo228_id' => 'xsd:string',
                                            	   'unidad_id' => 'xsd:string',
                                            	   'cantidad' => 'xsd:string',
                                            	   'cod_anatofarmacologico' => 'xsd:string',
                                            	   'mensaje_id' => 'xsd:string',
                                            	   'codigo_mindefensa' => 'xsd:string',
                                            	   'codigo_invima' => 'xsd:string',
                                            	   'vencimiento_codigo_invima' => 'xsd:string',
                                            	   'titular_reginvima_id' => 'xsd:string',
                                            	   'porc_iva' => 'xsd:string',
                                            	   'sw_generico' => 'xsd:string',
                                            	   'sw_venta_directa' => 'xsd:string',
                                            	   'tipo_pais_id' => 'xsd:string',
                                            	   'tipo_producto_id' => 'xsd:string',
                                            	   'presentacioncomercial_id' => 'xsd:string',
                                            	   'cantidad_p' => 'xsd:string',
                                            	   'tratamiento_id' => 'xsd:string',
                                            	   'usuario_id' => 'xsd:string',
                                            	   'cod_presenta' => 'xsd:string',
                                            	   'dci' => 'xsd:string',
                                            	   'estado_unico' => 'xsd:string',
                                            	   'sw_solicita_autorizacion' => 'xsd:string',
                                            	   'codigo_producto' => 'xsd:string',
                                                   'rips_no_pos' => 'xsd:string' 
                                         		  ),
                                    array('return' => 'xsd:string'),
                                      $ns);

$server->register('modificar_productomedicamento',array('sw_manejo_luz' => 'xsd:string',
                                            	   'cod_forma_farmacologica' => 'xsd:string',
                                            	   'concentracion' => 'xsd:string',
                                            	   'cod_principio_activo' => 'xsd:string',
                                            	   'cod_concentracion' => 'xsd:string',
                                            	   'sw_liquidos_electrolitos' => 'xsd:string',
                                            	   'sw_uso_controlado' => 'xsd:string',
                                            	   'sw_antibiotico' => 'xsd:string',
                                            	   'sw_refrigerado' => 'xsd:string',
                                            	   'sw_alimento_parenteral' => 'xsd:string',
                                            	   'sw_alimento_enteral' => 'xsd:string',
                                            	   'dias_previos_vencimiento' => 'xsd:string',
                                            	   'cod_anatofarmacologico' => 'xsd:string',
                                            	   'sw_pos' => 'xsd:string',
                                            	   'codigo_cum' => 'xsd:string',
                                            	   'unidad_id' => 'xsd:string',
                                            	   'sw_farmacovigilancia' => 'xsd:string',
                                            	   'descripcion_alerta' => 'xsd:string',
                                            	   'usuario_id' => 'xsd:int',
                                            	   'codigo_producto' => 'xsd:string'
                                         		  ),
                                    array('return' => 'xsd:string'),
                                      $ns);

$server->register('borrar_registro',array('tabla' => 'xsd:string',
                                          'id' => 'xsd:string',
                                          'campo_id'=> 'xsd:string'
                                         ),
                                    array('return' => 'xsd:string'),
                                      $ns);

$server->register('borrar_clase',array('grupo_id' => 'xsd:string',
                                          'laboratorio_id' => 'xsd:string'
                                         ),
                                    array('return' => 'xsd:string'),
                                      $ns);
                                      
$server->register('borrar_subclase',array('grupo_id' => 'xsd:string',
                                          'clase_id' => 'xsd:string',
                                          'subclase_id'=> 'xsd:string'
                                         ),
                                    array('return' => 'xsd:string'),
                                      $ns);

									  
/*Inicio codigo factor conversion*/
$server->register('insertarFactorConversion',array('codigo_unidad_dosificacion' => 'xsd:string',
                                                    'codigo_medicamento' => 'xsd:string',
                                                    'unidad_dosificacion' => 'xsd:string',
                                                    'cant_factor_conversion' => 'xsd:string'
                                                    ), 
                                            array('return' => 'xsd:string'), 
                                            $ns);

$server->register('eliminarFactorConversion',array( 'codigo_unidad_dosificacion' => 'xsd:string',
                                                    'codigo_medicamento' => 'xsd:string',
                                                    'unidad_dosificacion' => 'xsd:string',
                                                    ), 
                                            array('return' => 'xsd:string'), 
                                              $ns);

$server->register('actualizarNivelesAtencion',array('codigo_producto' => 'xsd:string',
                                                    'niveles' => 'xsd:string'
                                                    ), 
                                              array('return' => 'xsd:string'), 
                                                $ns);

$server->register('actualizarNivelesUso',array('codigo_producto' => 'xsd:string',
                                                'niveles' => 'xsd:string'
                                                ), 
                                         array('return' => 'xsd:string'), 
                                           $ns);

$server->register('actualizarViasAdmin',array('codigo_producto' => 'xsd:string',
                                              'niveles' => 'xsd:string'
                                              ), 
                                        array('return' => 'xsd:string'), 
                                          $ns);
										  
										  
function insertar_principioactivo($subclase_id, $descripcion)
{
	require_once("conexionproductopg.php");
	
	$query = "INSERT INTO inv_med_cod_principios_activos(	
					cod_principio_activo,
					descripcion
			  )VALUES( 
					'".$subclase_id."',
					'".$descripcion."'
			  ); ";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos".$subclase_id." ".$descripcion;
    }
    return "Se Guardo Correctamente el Principio Activo";
}



function Modificar_principioActivo_medicamentos($subclase_id,$codigo_producto) {
    require_once("conexionproductopg.php");
    
    $query  = " UPDATE medicamentos";
    $query .= "       SET ";
    $query .= "       cod_principio_activo = '" .$subclase_id . "' ";
    $query .= " where ";
    $query .= " codigo_medicamento = ";    
    $query .= " (select codigo_producto from inventarios_productos where codigo_producto = '" .$codigo_producto. "' AND grupo_id='FO' AND clase_id='FO') ;";

    $query .= " UPDATE inventarios_productos";
    $query .= "       SET ";
    $query .= "       subclase_id = '" .$subclase_id. "' ";
    $query .= " where ";
    $query .= " codigo_producto = '" .$codigo_producto. "' AND grupo_id='FO' AND clase_id='FO' ; ";
    $result = pg_query($conexionn, $query);

    if ($result) {
        return true;
    } else {
        return "Error en el modificar los datos ". pg_last_error($conexionn);
    }
}
									  
function insertar_grupo($grupo_id,$descripcion,$sw_medicamento)
{
	require_once("conexionproductopg.php");
	
	$query = "INSERT INTO inv_grupos_inventarios (	grupo_id,
													descripcion,
													sw_medicamento
												 ) 
										 VALUES ( '".$grupo_id."',
										 		  '".$descripcion."', 
										 		  '".$sw_medicamento."'); ";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos ". pg_last_error($conexionn);
    }
}

function insertar_clasesagrupo ($grupo_id,$clase_id,$descripcion,$laboratorio_id,$sw_tipo_empresa)
{
	require_once("conexionproductopg.php");
	
	$query=" INSERT INTO inv_clases_inventarios(grupo_id,
												clase_id,
												descripcion,
												laboratorio_id,
												sw_tipo_empresa)
					VALUES ( 	'".$grupo_id."',
								'".$clase_id."',
								'".$descripcion."',
								'".$laboratorio_id."',
								'".$sw_tipo_empresa."');";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos ". pg_last_error($conexionn);
    }
}

function insertar_subclaseaclase ($grupo_id,$clase_id,$subclase_id,$descripcion,$molecula_id)
{
	require_once("conexionproductopg.php");
	
	$query=" INSERT INTO inv_subclases_inventarios(	grupo_id,
													clase_id,
													subclase_id,
													descripcion,
													molecula_id)
					VALUES ( 	'".$grupo_id."',
								'".$clase_id."',
								'".$subclase_id."',
								'".$descripcion."',
								'".$molecula_id."');";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos ". pg_last_error($conexionn);
    }
}

function insertar_productoinsumo ($grupo_id, $clase_id, $subclase_id, $producto_id, $descripcion, $descripcion_abreviada, 
$codigo_producto, $codigo_cum, $codigo_alterno, $codigo_barras, $fabricante_id, $sw_pos, $cod_acuerdo228_id, 
$unidad_id, $contenido_unidad_venta, $cod_anatofarmacologico, $mensaje_id, $codigo_mindefensa, $codigo_invima, 
$vencimiento_codigo_invima, $titular_reginvima_id, $porc_iva, $sw_generico, $sw_venta_directa, $tipo_pais_id, 
$tipo_producto_id, $presentacioncomercial_id, $cantidad, $tratamiento_id, $usuario_id, $cod_adm_presenta, 
$dci_id, $estado_unico, $cod_forma_farmacologica, $sw_solicita_autorizacion, $rips_no_pos)
{
	require_once("conexionproductopg.php");
	
	$query=" INSERT INTO inventarios_productos (
    											grupo_id, 
											    clase_id, 
											    subclase_id,
											    producto_id,
											    descripcion,
											    descripcion_abreviada,
											    codigo_producto,
											    codigo_cum,
											    codigo_alterno,
											    codigo_barras,
											    fabricante_id,
											    sw_pos,
											    cod_acuerdo228_id,
											    unidad_id,
											    contenido_unidad_venta,
											    cod_anatofarmacologico,
											    mensaje_id,
											    codigo_mindefensa,
											    codigo_invima,
											    vencimiento_codigo_invima,
											    titular_reginvima_id,
											    porc_iva,
											    sw_generico,
											    sw_venta_directa,
											    tipo_pais_id,
											    tipo_producto_id,
											    presentacioncomercial_id,
											    cantidad,
											    tratamiento_id,
											    usuario_id,
											    cod_adm_presenta,
											    dci_id,
											    estado_unico,
											    cod_forma_farmacologica,
											    sw_solicita_autorizacion, 
                          rips_no_pos 
											    ) 
    VALUES ( 
     		'".$grupo_id."',
			'".$clase_id."',
			'".$subclase_id."',
			'".$producto_id."',
			'".$descripcion."',
			'".$descripcion_abreviada."',
			'".$codigo_producto."',
			'".$codigo_cum."',
			'".$codigo_alterno."',
			'".$codigo_barras."',
			'".$fabricante_id."',
			'".$sw_pos."',
			'".$cod_acuerdo228_id."',
			'".$unidad_id."',
			'".$contenido_unidad_venta."',
			'".$cod_anatofarmacologico."',
			'".$mensaje_id."',
			'".$codigo_mindefensa."',
			'".$codigo_invima."',
			'".$vencimiento_codigo_invima."',
			'".$titular_reginvima_id."',
			'".$porc_iva."',
			'".$sw_generico."',
			'".$sw_venta_directa."',
			'".$tipo_pais_id."',
			'".$tipo_producto_id."',
			'".$presentacioncomercial_id."',
			'".$cantidad."',
			".$tratamiento_id.",
			".$usuario_id.",
			'".$cod_adm_presenta."',
			'".$dci_id."',
			'".$estado_unico."',
			'".$cod_forma_farmacologica."',
			'".$sw_solicita_autorizacion."',
			'".$rips_no_pos."'
			); ";
	
	registrar_logs($producto_id,$grupo_id,$clase_id,$subclase_id,$query,1);
	$result = pg_query($conexionn,$query);
        
	
		
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos ". pg_last_error($conexionn);
    }
}

function insertar_productomedicamento($codigo_medicamento,$descripcion_alerta,$sw_farmacovigilancia,
$sw_fotosensible,$cod_anatomofarmacologico,$cod_principio_activo,$cod_forma_farmacologica,$sw_pos,
$codigo_cum,$dias_previos_vencimiento,$sw_liquidos_electrolitos,$sw_uso_controlado,$sw_antibiotico,
$sw_refrigerado,$sw_alimento_parenteral,$unidad_medida_medicamento_id,$sw_alimento_enteral,
$concentracion_forma_farmacologica,$cod_concentracion,$usuario_id)
{
	require_once("conexionproductopg.php");
	
	$query= "	INSERT INTO medicamentos(
								codigo_medicamento,
								descripcion_alerta,
								sw_farmacovigilancia,
								sw_fotosensible,   
								cod_anatomofarmacologico,
								cod_principio_activo,
								cod_forma_farmacologica,
								sw_pos,
								codigo_cum,
								dias_previos_vencimiento,
								sw_liquidos_electrolitos,
								sw_uso_controlado,
								sw_antibiotico,
								sw_refrigerado,
								sw_alimento_parenteral,
								unidad_medida_medicamento_id,
								sw_alimento_enteral,
								concentracion_forma_farmacologica,
								cod_concentracion,
								usuario_id
							) 
					 VALUES (
					 			'".$codigo_medicamento."',
					 			'".$descripcion_alerta."',
					 			'".$sw_farmacovigilancia."',
					 			'".$sw_fotosensible."',
					 			'".$cod_anatomofarmacologico."',
					 			'".$cod_principio_activo."',
					 			'".$cod_forma_farmacologica."',
					 			'".$sw_pos."',
					 			'".$codigo_cum."',
					 			'".$dias_previos_vencimiento."',
					 			'".$sw_liquidos_electrolitos."',
					 			'".$sw_uso_controlado."',
					 			'".$sw_antibiotico."',
					 			'".$sw_refrigerado."',
					 			'".$sw_alimento_parenteral."',
					 			'".$unidad_medida_medicamento_id."',
					 			'".$sw_alimento_enteral."',
					 			'".$concentracion_forma_farmacologica."',
					 			'".$cod_concentracion."',
					 			 ".$usuario_id."
					  		)";
	/*
	* +Descripcion: se registra la insercion del medicamento
	*/
    
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el insert de los datos " . pg_last_error($conexionn);
    }
}

function modificar_grupo($grupo_id,$descripcion)
{
	require_once("conexionproductopg.php");
	
	$query = "UPDATE inv_grupos_inventarios
			  SET	 descripcion = '".$descripcion."' 
			  WHERE	 grupo_id = '".$grupo_id."' ";
	
	$result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el update de los datos ". pg_last_error($conexionn);
    }
}

function modificar_productoinsumo($subclase_id,$descripcion,$descripcion_abreviada,$codigo_cum,$codigo_alterno,
$codigo_barras,$fabricante_id,$sw_pos,$cod_acuerdo228_id,$unidad_id,$cantidad,$cod_anatofarmacologico,
$mensaje_id,$codigo_mindefensa,$codigo_invima,$vencimiento_codigo_invima,$titular_reginvima_id,$porc_iva,
$sw_generico,$sw_venta_directa,$tipo_pais_id,$tipo_producto_id,$presentacioncomercial_id,$cantidad_p,
$tratamiento_id,$usuario_id,$cod_presenta,$dci,$estado_unico,$sw_solicita_autorizacion,$codigo_producto,$rips_no_pos)
{

	require_once("conexionproductopg.php");
	
	$sql  = "UPDATE inventarios_productos";
	$sql .= "       SET ";
	$sql .= "       subclase_id =";
	$sql .= "        '".$subclase_id."',";
	$sql .= "       descripcion =";
	$sql .= "        '".$descripcion."',";
	$sql .= "       descripcion_abreviada =";
	$sql .= "        '".$descripcion_abreviada."',";
	$sql .= "       codigo_cum =";
	$sql .= "        '".$codigo_cum."',";
	$sql .= "       codigo_alterno =";
	$sql .= "        '".$codigo_alterno."',";
	$sql .= "       codigo_barras =";
	$sql .= "        '".$codigo_barras."',";
	$sql .= "       fabricante_id =";
	$sql .= "        '".$fabricante_id."',";
	$sql .= "       sw_pos =";
	$sql .= "        '".$sw_pos."',";
	$sql .= "       cod_acuerdo228_id =";
	$sql .= "        '".$cod_acuerdo228_id."',";
	$sql .= "       cod_forma_farmacologica =";
	$sql .= "        '".$unidad_id."',";
	$sql .= "       unidad_id =";
	$sql .= "        '".$unidad_id."',";
	$sql .= "       contenido_unidad_venta =";
	$sql .= "        '".$cantidad."',";
	$sql .= "       cod_anatofarmacologico =";
	$sql .= "        '".$cod_anatofarmacologico."',";
	$sql .= "       mensaje_id =";
	$sql .= "        '".$mensaje_id."',";
	$sql .= "       codigo_mindefensa =";
	$sql .= "        '".$codigo_mindefensa."',";
	$sql .= "       codigo_invima =";
	$sql .= "        '".$codigo_invima."',";
	$sql .= "       vencimiento_codigo_invima =";
	$sql .= "        '".$vencimiento_codigo_invima."',";
	$sql .= "       titular_reginvima_id =";
	$sql .= "        '".$titular_reginvima_id."',";
	$sql .= "       porc_iva =";
	$sql .= "        '".$porc_iva."',";
	$sql .= "       sw_generico =";
	$sql .= "        '".$sw_generico."',";
	$sql .= "       sw_venta_directa =";
	$sql .= "        '".$sw_venta_directa."',";
	$sql .= "       tipo_pais_id =";
	$sql .= "        '".$tipo_pais_id."',";     
	$sql .= "       tipo_producto_id =";
	$sql .= "        '".$tipo_producto_id."',";
	$sql .= "       presentacioncomercial_id =";
	$sql .= "        '".$presentacioncomercial_id."',";  
	$sql .= "       cantidad =";
	$sql .= "        '".$cantidad_p."', ";   
	$sql .= ($tratamiento_id)?"tratamiento_id =".$tratamiento_id.", ":"";  
	$sql .= "		 usuario_id = ".$usuario_id.", ";
	$sql .= "		 fecha_registro = NOW(), ";
	$sql .= "       cod_adm_presenta =";
	$sql .= "        '".$cod_presenta."', ";
	$sql .= "       dci_id =";
	$sql .= "        '".$dci."', ";
    $sql .= "       estado_unico =";
	$sql .= "        '".trim($estado_unico)."', ";
    $sql .= "       sw_solicita_autorizacion =";
	$sql .= "        '".trim($sw_solicita_autorizacion)."', ";    
    $sql .= "       rips_no_pos =";
    $sql .= "        '" . trim($rips_no_pos) . "' ";
	$sql .= "		WHERE ";
	$sql .= "codigo_producto =";			
	$sql .= "        '".$codigo_producto."';";
	
	
	//var_dump($sql);
	$result = pg_query($conexionn,$sql);
        
	//	var_dump($conexionn);
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el update de los datos " . pg_last_error($conexionn);
    }
}

function modificar_productomedicamento($sw_manejo_luz,$cod_forma_farmacologica,$concentracion,$cod_principio_activo,
$cod_concentracion,$sw_liquidos_electrolitos,$sw_uso_controlado,$sw_antibiotico,$sw_refrigerado,$sw_alimento_parenteral
,$sw_alimento_enteral,$dias_previos_vencimiento,$cod_anatofarmacologico,$sw_pos,$codigo_cum,$unidad_id,
$sw_farmacovigilancia,$descripcion_alerta,$usuario_id,$codigo_producto)
{
	require_once("conexionproductopg.php");
	
	$sql  = "UPDATE medicamentos";
	$sql .= "       SET ";
	$sql .= "       sw_fotosensible =";
	$sql .= "        '".$sw_manejo_luz."',";
	$sql .= "       cod_forma_farmacologica =";
	$sql .= "        '".$cod_forma_farmacologica."',";
	$sql .= "       concentracion_forma_farmacologica =";
	$sql .= "        '".$concentracion."',";   
	$sql .= "       cod_principio_activo =";
	$sql .= "        '".$cod_principio_activo."',";   
	$sql .= "       cod_concentracion =";
	$sql .= "        '".$cod_concentracion."',";   
	$sql .= "       sw_liquidos_electrolitos =";
	$sql .= "        '".$sw_liquidos_electrolitos."',";
	$sql .= "       sw_uso_controlado =";
	$sql .= "        '".$sw_uso_controlado."',";
	$sql .= "       sw_antibiotico =";
	$sql .= "        '".$sw_antibiotico."',";
	$sql .= "       sw_refrigerado =";
	$sql .= "        '".$sw_refrigerado."',";
	$sql .= "       sw_alimento_parenteral =";
	$sql .= "        '".$sw_alimento_parenteral."',";    
	$sql .= "       sw_alimento_enteral =";
	$sql .= "        '".$sw_alimento_enteral."',";
	$sql .= "       dias_previos_vencimiento =";
	$sql .= "        '".$dias_previos_vencimiento."',";
	$sql .= "       cod_anatomofarmacologico =";
	$sql .= "        '".$cod_anatofarmacologico."',";
	$sql .= "       sw_pos =";
	$sql .= "        '".$sw_pos."',";
	$sql .= "       codigo_cum =";
	$sql .= "        '".$codigo_cum."',";
	$sql .= "       unidad_medida_medicamento_id =";
	$sql .= "        '".$unidad_id."',";
	$sql .= "       sw_farmacovigilancia =";
	$sql .= "        '".$sw_farmacovigilancia."',";
	$sql .= "       descripcion_alerta =";
	$sql .= "        '".$descripcion_alerta."',";
	$sql .= "       usuario_id =";
	$sql .= "        ".$usuario_id.",";
	$sql .= "       fecha_registro = ";
	$sql .= "        NOW() ";
	$sql .= " WHERE ";
	$sql .= "codigo_medicamento =";			
	$sql .= "        '".$codigo_producto."';";
	
	
	$result = pg_query($conexionn,$sql);
     
	
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el update de los datos ". pg_last_error($conexionn);
    }
}

function borrar_registro ($tabla,$id,$campo_id)
{
	require_once("conexionproductopg.php");
	
	$query  = "Delete from ".$tabla." ";
    $query .= "Where ".$campo_id." = '".$id."';";
    
    $result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el delete de los datos ". pg_last_error($conexionn);
    }
}

function borrar_clase ($grupo_id,$laboratorio_id)
{
	require_once("conexionproductopg.php");
	
	$query  = "DELETE FROM 	inv_clases_inventarios
			   WHERE	   	grupo_id = '".$grupo_id."' 
			   AND			clase_id = '".$laboratorio_id."'; ";
    
    $result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el delete de los datos ". pg_last_error($conexionn);
    }
}

function borrar_subclase ($grupo_id,$clase_id,$subclase_id)
{
	require_once("conexionproductopg.php");
	
	$query  = "DELETE FROM 	inv_subclases_inventarios
			   WHERE	   	grupo_id = '".$grupo_id."' 
			   AND			clase_id = '".$clase_id."' 
			   AND			subclase_id = '".$subclase_id."'; ";
    
    $result = pg_query($conexionn,$query);
        
	if($result)
    {
		return true;
    }
    else
    {
        return "Error en el delete de los datos ". pg_last_error($conexionn);
    }
}

/**
* +Descripcion: Se registra la informacion detallada de cada transaccion del ws en la tabla logs_productos_ws
*  fecha: 09/10/2015
*/
function registrar_logs($codigo, $grupo_id, $clase_id, $subclase_id, $mensaje,$tipo='0') {

    require_once("conexionproductopg.php");
 
    $query = "INSERT INTO logs_productos_ws ( codigo, 
											  grupo_id, 
											  clase_id, 
											  subclase_id, 
											  mensaje, 
											  tipo
											 )
									VALUES ('".$codigo."', 
										    '".$grupo_id."', 
											'".$clase_id."',
											'".$subclase_id."', 
											'".$mensaje."', 
											'".$tipo."'); ";
     

    $result = pg_query($conexionn,$query);

    if ($result) {
        return true;
       
    } else {
       
        return $msj = "Se ha generado un error insertando en logs_productos_ws ( " . pg_last_error($conexionn) . " ) ";
    }

    //return array("msj" => $msj, "continuar" => $continuar);
}

function Modificar_ClasificacionProducto($grupo_id, $clase_id, $subclase_id, $codigo_producto) {
    require_once("conexionpg.php");

    $sql = " UPDATE medicamentos";
    $sql .= "       SET ";
    $sql .= "       cod_principio_activo = '" . $subclase_id . "' ";
    $sql .= "where ";
    $sql .= "codigo_medicamento = '" . $codigo_producto . "'; ";

    $sql .= " UPDATE inventarios_productos";
    $sql .= "       SET ";
    $sql .= "       grupo_id = '" . $grupo_id . "', ";
    $sql .= "       clase_id = '" . $clase_id . "', ";
    $sql .= "       subclase_id = '" . $subclase_id . "' ";
    $sql .= "where ";
    $sql .= "codigo_producto = '" . $codigo_producto . "' ; ";

    $result = pg_query($conexionn, $sql);

    if ($result) {
        return true;
    } else {
        return "Error en el insert de los datos ". pg_last_error($conexionn);
    }
}



/*Codigo para actualizar el factor de conversion*/
function insertarFactorConversion($CodigoUnidadDosificacion, $CodigoMedicamento, $UnidadDosificacion, $CantidadFactorConversion) {
    
    require_once("conexionpg.php");

    $sqlc = "SELECT * FROM hc_formulacion_factor_conversion 
             WHERE unidad_id = '$CodigoUnidadDosificacion' and codigo_producto = '$CodigoMedicamento' and unidad_dosificacion = '$UnidadDosificacion'";    
    
    $resultado = pg_query($conexionn, $sqlc);
    $i = 0;
    $documentos = Array();
    
    while ($row = pg_fetch_row($resultado)) {
        $i = 1;
        $documentos[] = $row;
    }
    
    $sql = '';
    if ($i == 0) {
        $sql = "INSERT INTO hc_formulacion_factor_conversion (
                        codigo_producto
                        ,unidad_id
                        ,unidad_dosificacion
                        ,factor_conversion
                        ,usuario_id
                        ,fecha_registro
                        )
                VALUES (
                        '$CodigoMedicamento'
                        ,'$CodigoUnidadDosificacion'
                        ,'$UnidadDosificacion'
                        ,$CantidadFactorConversion 
                        ,2
                        ,NOW()
                        );";
    } else {
        $sql = "UPDATE hc_formulacion_factor_conversion
                SET factor_conversion = $CantidadFactorConversion
                WHERE unidad_id = '$CodigoUnidadDosificacion'
                        AND codigo_producto = '$CodigoMedicamento'
                        AND unidad_dosificacion = '$UnidadDosificacion';";
    }

    $rst = @pg_query($conexionn, $sql);
    
    if (!$rst) {
        return 'Error al insertar o actualizar el factor de conversion '. pg_last_error($conexionn);
    }
    return '1';
}

function eliminarFactorConversion($CodigoUnidadDosificacion, $CodigoMedicamento, $UnidadDosificacion) 
{
    require_once("conexionpg.php");    
    $sql = "DELETE FROM hc_formulacion_factor_conversion 
            WHERE codigo_producto = '" . $CodigoMedicamento . "' And unidad_id = '" . $CodigoUnidadDosificacion . "' And unidad_dosificacion = '" . $UnidadDosificacion . "'";
    //echo $sql;
    $resultado = @pg_query($conexionn, $sql);

    if (!$resultado) {
        return 'Error, el factor de conversion no pudo ser eliminado '. pg_last_error($conexionn);
    }
    return '1';
}

/*Fin codigo actualizacion factor conversion*/

function asignar($Tabla, $Valor, $CodigoProducto, $Campos)
{
    require_once("conexionpg.php");
    $ProductoxNivel = $CodigoProducto . "" . $Valor;
    $nivel = ($Tabla === 'inv_producto_x_nivel_atencion')? 'nivel': 'nivel_de_uso_id';
    
    $sqls = "SELECT * FROM $Tabla WHERE codigo_producto='$CodigoProducto' AND $nivel='$Valor';";
    $select = @pg_query($conexionn, $sqls);
    $resultado = true;
    if (!pg_num_rows($select)) {
        $sql = "INSERT INTO $Tabla($Campos) VALUES('$ProductoxNivel','$Valor','$CodigoProducto');";    
        $resultado = @pg_query($conexionn, $sql);
    }
    if (!$resultado) {
        return 'Error, el nivel de uso o nivel de atencion no se ha podido insertar '. pg_last_error($conexionn);
    }
    return true;    
}

function borrar($Tabla, $Valor, $CodigoProducto, $Campo)
{
    require_once("conexionpg.php");
    $ProductoxNivel = $CodigoProducto . "" . $Valor;
    $nivel = ($Tabla === 'inv_producto_x_nivel_atencion')? 'producto_x_nivel': 'producto_x_nivel_de_uso';
    $sql = "DELETE FROM $Tabla WHERE $nivel = '$ProductoxNivel';";    
    $resultado = @pg_query($conexionn, $sql);
    if (!$resultado) {
        return 'Error, el nivel de uso o nivel de atencion no se ha podido borrar '. pg_last_error($conexionn);
    }
    return true;
}

function InsertarViadxProd($via_administracion_id, $CodigoProducto)
{
    require_once("conexionpg.php");
    $sql = "INSERT INTO inv_medicamentos_vias_administracion (
                    codigo_medicamento
                    ,via_administracion_id
                    )
            VALUES (
                    '$CodigoProducto'
                    ,'$via_administracion_id'
                    );";
    
    $resultado = @pg_query($conexionn, $sql);
    if (!$resultado) {
        return 'Error, la via de administracion no se ha podido insertar '. pg_last_error($conexionn);
    }
    return true;
}

function BorrarViadxProd($via_administracion_id, $CodigoProducto)
{
    require_once("conexionpg.php");
    $sql = "DELETE
            FROM inv_medicamentos_vias_administracion
            WHERE via_administracion_id = '$via_administracion_id'
                    AND codigo_medicamento = '$CodigoProducto';";

    $resultado = @pg_query($conexionn, $sql);
    if (!$resultado) {
        return 'Error, la via de administracion no se ha podido borrar '. pg_last_error($conexionn);
    }
    return true;
}

function existenciaProducto($codigoProducto)
{
    require_once("conexionpg.php");
    $sql = "SELECT * FROM inventarios_productos WHERE codigo_producto='$codigoProducto';";
    $resultado = @pg_query($conexionn, $sql);
    if (!$resultado) {
        return 'Error al consultar la existencia del medicamento '. pg_last_error($conexionn);
    }
    if (pg_num_rows($resultado) < 1) {
        return '0';
    }    
    return '1';
}

function actualizarViasAdmin($codigoProducto, $niveles)
{
    require_once("conexionpg.php");
    $niveles = explode(',',$niveles);
    
    $sql = "BEGIN; DELETE FROM | WHERE codigo_medicamento = '$codigoProducto';";
    foreach ($niveles as $nivel) {
        $sql .= "INSERT INTO inv_medicamentos_vias_administracion(codigo_medicamento, via_administracion_id) VALUES('$codigoProducto','$nivel');";
    }
    unset($nivel);
    $sql .= "COMMIT;";
    $rst = @pg_query($conexionn, $sql);

    if (!$rst) {
        return 'Error al insertar o actualizar las vias de administracion '. pg_last_error($conexionn);
    }
    return true;
} 

function actualizarNivelesAtencion($codigoProducto, $niveles)
{
    require_once("conexionpg.php");
    $niveles = explode(',',$niveles);
    
    $sql = "BEGIN; DELETE FROM inv_producto_x_nivel_atencion WHERE codigo_producto = '$codigoProducto';";
    foreach ($niveles as $nivel) {
        $sql .= "INSERT INTO inv_producto_x_nivel_atencion(producto_x_nivel, codigo_producto, nivel) VALUES('$codigoProducto$nivel','$codigoProducto','$nivel');";
    }
    unset($nivel);
    $sql .= "COMMIT;";
    $rst = @pg_query($conexionn, $sql);

    if (!$rst) {
        return 'Error al insertar o actualizar los niveles de atencion '. pg_last_error($conexionn);
    }
    return true;
}

function actualizarNivelesUso($codigoProducto, $niveles) 
{
    require_once("conexionpg.php");
    $niveles = explode(',',$niveles);
    
    $sql = "BEGIN; DELETE FROM inv_producto_x_nivel_de_uso WHERE codigo_producto = '$codigoProducto';";
    foreach ($niveles as $nivel) {
        $sql .= "INSERT INTO inv_producto_x_nivel_de_uso(producto_x_nivel_de_uso, codigo_producto, nivel_de_uso_id) VALUES('$codigoProducto$nivel','$codigoProducto','$nivel');";
    }
    unset($nivel);
    $sql .= "COMMIT;";
    $rst = @pg_query($conexionn, $sql);

    if (!$rst) {
        return 'Error al insertar o actualizar los niveles de uso '. pg_last_error($conexionn);
    }
    return true;
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