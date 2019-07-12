
<?php

/**
 * $Id: app_EstacionE_Medicamentos_user.php,v 1.21 2005/10/19 16:21:03 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria (parte de medicamentos del paciente) 
 */



/**
* Modulo de EstacionE_Pacientes (PHP).
*
//*
*
* @author  <@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_EstacionE_Pacientes_user.php
*
//*
**/

class app_EstacionE_Medicamentos_user extends classModulo
{
	var $uno;//para los errores

	function app_EstacionE_Medicamentos_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		//$this->PrincipalCartera2();
		return true;
	}


		/******************FUNCIONES  DEL MODULO DE ESTACIONE_MEDICAMENTOS***********************/

		/**
		*		CallFrmInsumosPacientes
		*
		*		Llama a la vista en la que se muestra un listado de los pacientes de la estación
		*		para hacer el pedido de insumos
		*
		*		@Author Arley Velásquez Castillo
		*		@access Public
		*		@return bool
		*/
		function CallFrmInsumosPacientes()
		{
			if(!$this->FrmInsumosPacientes($_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmInsumosPaciente\"";
				return false;
			}
			return true;
		}

		/******************FUNCIONES  DEL MODULO DE ESTACIONE_MEDICAMENTOS***********************/


/*funcion del metodo estacione_medicamentos*/
	/**
	*		GetDiasHospitalizacion
	*
	*		Calcula los días que lleva hospitalizada una persona, basandose en la fecha de ingreso.
	*		Esta funcion tamben es llamada desde el modulo censo
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return integer
	*		@param tiemstamp => fecha de ingreso del paciente
	*/
	function GetDiasHospitalizacion($fecha_ingreso)
	{
		if(empty($fecha_ingreso)){
			$fecha_ingreso = '';
			$fecha_ingreso = $_REQUEST['fecha_ingreso'];
		}

		list($Fecha,$Horas) = explode(" ",$fecha_ingreso);//obtiene solo la fecha sin horas
		if($Fecha == date('Y-m-d'))
		{
			list($h,$m,$s) = explode(":",date('H:i:s'));
			list($hh,$mm,$ss) = explode(":",$Horas);

			$total = date('g:i:s',(mktime($h,$m,$s,date('m'),date('d'),date('Y')) - mktime($hh,$mm,$ss,date('m'),date('d'),date('Y'))));
			list($h,$m,$s) = explode(":",$total);
			$total = $h." h, ".$m." m, ".$s." s.";
		}
		else
		{
			$date = explode("-",$Fecha);//obtengo por separado año, mes, dia
			$annos = ceil(date("Y") - $date[0]);
			$meses = date("m") - $date[1];
			$dias = (date("d")-$date[2]);
			$total = ($annos*365) + ($meses*30) + $dias;
			$total.= " días";
		}
		return $total;
	}
/*funcion del metodo estacione_medicamentos*/


	/*funcion del mod estacione_medicamentos*/


	/*funcion del mod estacione_medicamentos*/
		/*
		*		CallFrmInsumosPorRecibir
		*
		*		Llama a  la vista que permite hacer busquedas completas o por camas de los
		*		insumos por recibir (despachados)
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmBuscaInsumosPorRecibir()
		{
			$TipoBusqueda = $_REQUEST['TipoBusqueda'];
			$ItemBusqueda = $_REQUEST['ItemBusqueda'];
			$SubmitRecibir = $_REQUEST['SubmitRecibir'];

			if($SubmitRecibir)
			{
				$Solicitudes =	$_REQUEST['Solicitudes'];
				$docsSolicitud = $_REQUEST['docsSolicitud'];
				$VM = $_REQUEST['vectorMedicamentos'];

				if($this->AceptarDespachoInsumos($Solicitudes,$docsSolicitud,$VM))
				{
					$mensaje = "TODOS LOS INSUMOS SE RECIBIERON CON EXITO";
					$titulo = "MENSAJE";//FrmBuscaInsumosPorRecibir($TipoBusqueda,$ItemBusqueda,$_REQUEST['datos_estacion'])){
					$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmBuscaInsumosPorRecibir',array("TipoBusqueda"=>$TipoBusqueda,"ItemBusqueda"=>$ItemBusqueda,"datos_estacion"=>$_REQUEST['datos_estacion']));
					$boton = "VOLVER AL LISTADO";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{
					$mensaje = "NO TODAS LOS INSUMOS SE RECIBIERON EXITOSAMENTE";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmBuscaInsumosPorRecibir',array("TipoBusqueda"=>$TipoBusqueda,"ItemBusqueda"=>$ItemBusqueda,"datos_estacion"=>$_REQUEST['datos_estacion']));
					$boton = "VOLVER AL LISTADO";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
			}//FIN PRESIONO EL BOTON DE RECIBIR

			if(!$this->FrmBuscaInsumosPorRecibir($TipoBusqueda,$ItemBusqueda,$_REQUEST['datos_estacion']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'FrmBuscaInsumosPorRecibir'";
				return false;
			}
			return true;
		}//CallFrmInsumosPorRecibir
		/*funcion del mod estacione_medicamentos*/



		/*funcion del mod estacione_medicamentos*/
	/**
	*		GetInsumosPendientesPorRecibir
	*
	*		Obtiene todos los medicamentos pendientes por recibir (despachados)
	*		El primer subquery obtiene los medicamentos de los insumos solicitados
	*		El segundo subquery obtiene los insumos solicitados
	*		El tercer subquery obtiene los medicamentos de los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*		El cuarto subquery obtiene los medicamentos de los insumos no despchados por bodega
	*		El quinto subquery obtiene los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*		El sexto subquery obtiene los insumos no despchados por bodega
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool - array
	*		@param array => datos de la ubicacion actual: dpto, estacion, empresa, usuario, etc
	*/
	function GetInsumosPendientesPorRecibir($datos_estacion)
	{
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.ingreso,
										B.codigo_producto as codigo_producto_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.codigo_producto as codigo_producto_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo,
										I.paciente_id,
										I.tipo_id_paciente,
										I.ingreso,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza
						from
						(
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												M.cod_forma_farmacologica,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												medicamentos M,
												inventarios_productos INV,
												inv_med_cod_forma_farmacologica FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												M.codigo_medicamento = INV.codigo_producto AND
												FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								UNION
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												NULL as cod_forma_farmacologica,
												NULL as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												inventarios_productos INV,
												hc_insumos_estacion HIE,
												hc_tipos_insumo HTI
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												HIE.codigo_producto = SID.codigo_producto AND
												HIE.insumo_id = HTI.insumo_id AND
												HTI.tipo_insumo = 'I'
						) as B
						LEFT JOIN
						(
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													M.cod_forma_farmacologica,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as cod_forma_farmacologica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
							)
						) as A
						ON (B.codigo_producto = A.codigo_producto AND B.solicitud_id = A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";
		/*UNION
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												NULL as cod_forma_farmacologica,
												NULL as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												inventarios_productos INV,
												hc_insumos_estacion HIE,
												hc_tipos_insumo HTI
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												HIE.codigo_producto = SID.codigo_producto AND
												HIE.insumo_id = HTI.insumo_id AND
												HTI.tipo_insumo = 'I';


												UNION
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as cod_forma_farmacologica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as cod_forma_farmacologica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
							)



												*/
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow()){
					$Solicitudes[$data['solicitud_sol']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//GetInsumosPendientesPorRecibir

	/*funcion del mod estacione_medicamentos*/





		/*esta funcion mod estacione_medicamentos*/
	/**
	*		AceptarDespachoInsumos
	*
	*		Realiza todos los procesos necesarios para aceptar y recibir los insumos despachados
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool
	*		@param array => solicitudes a aceptar
	*		@param array => documentos de bodega en los que fueron despachados las solicitudes
	*		@param array => medicamentos despachados por bodega
	*/
	function AceptarDespachoInsumos($Solicitudes,$docsSolicitud,$VM)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();

		foreach($docsSolicitud as $key => $value)
		{
			if(in_array($key,$Solicitudes))
			{
				$query = "UPDATE hc_solicitudes_medicamentos
									SET sw_estado = '2'
									WHERE solicitud_id = ".$key."";//$y[0]

				$dbconn->BeginTrans();
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Error al intentar aceptar la solicitud del insumo<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					//DOCUMENTOS DE CADA SOLICITUD
					foreach ($value as $A => $B)
					{
						$query = "UPDATE bodegas_documentos_hc_solicitudes
											SET estado = '1'
											WHERE solicitud_id = ".$key." AND
														bodegas_doc_id = ".$B."";

						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Error al intentar aceptar la solicitud del insumo<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}
					}//FIN DOCUMENTOS SOLICITUD

					//INSUMOS DE CADA SOLICITUD
					foreach ($VM[$key] as $keyMed => $valueMed)
					{
						$desp = unserialize(urldecode($valueMed));
						foreach ($desp as $k => $despachado)
						{
							$desp = unserialize(urldecode($despachado));
							$query = "SELECT cantidad_acum
												FROM hc_bodega_paciente
												WHERE ingreso = ".$despachado[ingreso]." AND
															medicamento_id = '".$despachado[codigo_producto]."'";

							$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
							$result = $dbconn->Execute($query);
							$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar obtener la cantidad del insumo en la bodega del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}
							else
							{
								$cantidad = (($result->fields[cantidad_acum]) + $despachado[cantidad]);

								if($result->EOF)
								{
									$query = "INSERT INTO hc_bodega_paciente( ingreso,
																																				medicamento_id,
																																				cantidad_acum)
																																VALUES (".$despachado[ingreso].",
																																				'".$despachado[codigo_producto]."',
																																				".$despachado[cantidad].")";

									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar actualizar la cantidad del insumo en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
									else{
										$dbconn->CommitTrans();	//$dbconn->RollbackTrans();
									}
								}
								else //ya existe el medicamento en la bodega del paciente
								{
									$query = "UPDATE hc_bodega_paciente
														SET cantidad_acum = ".$cantidad."
														WHERE ingreso = ".$despachado[ingreso]." AND
														medicamento_id = '".$despachado[codigo_producto]."'";

									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar actualizar la cantidad del insumo en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
									else{
										$dbconn->CommitTrans();//$dbconn->RollbackTrans();
									}
								}//ya existe el insumo en la bodega del paciente
							}//ejecuto el select cantidad_acum
						}
					}//FIN FOREACH MEDICAMENTOS
				}//realizó el update de solicitud de insumo
			}//FIN for ($i=0; $i<sizeof($Solicitudes); $i++) => CADA CHECK SELECCIONAD
		}//fin foreach

		return true;
	}//AceptarDespachoInsumos


	
	
     function GetPaciente_Consulta_Urgencia_con_med($Estacion,$paciente,$B)
     {
          if($_SESSION['ESTACION_CONTROL']['INGRESO'])
          {
                    $ingreso=$_SESSION['ESTACION_CONTROL']['INGRESO'];
                    $sql_extra_ur="AND I.ingreso='$ingreso'";
          }

               $query = "SELECT I.tipo_id_paciente,
                                   I.paciente_id,
                                   I.ingreso,
                                   I.fecha_ingreso,
                                   P.primer_apellido,
                                   P.segundo_apellido,
                                   P.primer_nombre,
                                   P.segundo_nombre,
                                   C.numerodecuenta,
                                   C.plan_id,
                                   G.plan_descripcion,
                                   G.tercero_id,
                                   G.tipo_tercero_id,
                                   H. nombre_tercero,
                                   C.rango,
                                   C.numerodecuenta
                         FROM  pacientes P,
                                   ingresos I,
                                   cuentas C,
                                   pacientes_urgencias PU,
                                   planes G,
                                   terceros H
                         WHERE I.ingreso = C.ingreso AND
                                   I.estado = 1 AND
                                   C.estado = 1 AND
                                   P.paciente_id = I.paciente_id AND
                                   P.tipo_id_paciente = I.tipo_id_paciente AND
                                   P.paciente_id = '".$paciente[paciente_id]."' AND
                                   P.tipo_id_paciente = '".$paciente[tipo_id_paciente]."' AND
                                   PU.ingreso = I.ingreso AND
                                   estacion_id = '".$Estacion[estacion_id]."' AND
                                   G.plan_id = C.plan_id AND
                                   H.tercero_id = G.tercero_id AND
                                   H.tipo_id_tercero = G.tipo_tercero_id
                                   $sql_extra_ur
                         ORDER BY P.primer_nombre,
                                   P.segundo_nombre,
                                   P.primer_apellido,
                                   P.segundo_apellido";

          list($dbconn) = GetDBconn();
          $resultEmp = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "conexion fallida al intentar consultar los medicamentos recetados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if ($resultEmp->EOF){
                    return $B;
               }
               else
               {
                    if(!is_array($B))
                    {
                         $i=0;
                         while (!$resultEmp->EOF)//while ($data = $resultEmp->FetchNextObject())
                         {
                              $datos[$i] = $resultEmp->GetRowAssoc($ToUpper = false);//mi primer GetRow
                              $i++;
                              $resultEmp->MoveNext();
                         }
                         return $datos;
                    }
                    else
                    {
                         $i=0;
                         while (!$resultEmp->EOF)//while ($data = $resultEmp->FetchNextObject())
                         {
                              $B[] = $resultEmp->GetRowAssoc($ToUpper = false);//mi primer GetRow
                              $i++;
                              $resultEmp->MoveNext();
                         }
                         return $B;
                    }			
               }
          }
     }

	
	
	
	
	
	/**
	*		GetPacientesPendientesXHospitalizar_Plantilla => Obtiene los pendientes por hospitalizar
	*
	*   Se diferencia de la funcion anterior, en la forma de sacar el vector.
	*		@Author JAJA
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesPendientesXHospitalizar_Con_medicamentos($datos_estacion,$paciente)
	{

	  if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
		 $query = "SELECT paciente_id,
											tipo_id_paciente,
											primer_apellido,
											segundo_apellido,
											primer_nombre,
											segundo_nombre,
											ingreso,
											ee_destino,
											orden_hosp,
											traslado,
											estacion_origen
							FROM pacientes,
									(	SELECT  I.ingreso,
														I.paciente_id as pac_id,
														I.tipo_id_paciente as tipo_id,
														P.estacion_destino as ee_destino,
														P.orden_hospitalizacion_id as orden_hosp,
														P.traslado as traslado,
														P.estacion_origen as estacion_origen
										FROM 	ingresos I,
													cuentas x,
													pendientes_x_hospitalizar P
													--,hc_medicamentos_recetados_hosp H
										WHERE I.ingreso = P.ingreso 
										AND I.ingreso =x.ingreso
										AND x.estado='1'
										AND I.ingreso='".$paciente[ingreso]."'
										AND P.estacion_destino = '".$datos_estacion[estacion_id]."'
									--	AND H.ingreso = P.ingreso 
									--	AND H.sw_estado='1'
										AND I.tipo_id_paciente='".$paciente[tipo_id_paciente]."' AND
										I.paciente_id='".$paciente[paciente_id]."' 
									) as HOLA
							WHERE paciente_id = pac_id AND
										tipo_id_paciente = tipo_id AND
										tipo_id_paciente='".$paciente[tipo_id_paciente]."' AND
										paciente_id='".$paciente[paciente_id]."' AND
										ee_destino = '".$datos_estacion[estacion_id]."'
							ORDER BY  primer_nombre,
												segundo_nombre,
												primer_apellido,
            segundo_apellido";//pacientes_x_ingreso_x_pxh

		list($dbconn) = GetDBconn();
		$resultEmp = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "conexion fallida al intentar consultar los medicamentos recetados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if ($resultEmp->EOF){
				return "ShowMensaje";
			}
			else
			{
				$i=0;
				while (!$resultEmp->EOF)//while ($data = $resultEmp->FetchNextObject())
				{
					$datos[$i] = $resultEmp->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$i++;
					$resultEmp->MoveNext();
				}
				return $datos;
			}
		}
}			
	
	
	
	

		/**
	*		GetPacientesConMedicamentosPorSolicitar
	*
	*		obtiene los pacientes que tengan medicamentos recetados vigentes
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesConMedicamentosPorSolicitar($datos_estacion,$paciente,$B)
	{
 		 if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}

	 $query = "(SELECT DISTINCT ON (C.paciente_id) C.paciente_id,
											F.pieza,
											E.cama,
											C.tipo_id_paciente,
											D.primer_nombre,
											D.primer_apellido,
											D.segundo_nombre,
											D.segundo_apellido,
											B.ingreso,
											B.numerodecuenta
								FROM  ingresos_departamento A,
											cuentas B,
											ingresos C,
											pacientes D,
											movimientos_habitacion E,
											camas F,
											hc_evoluciones G,
											hc_medicamentos_recetados_hosp H
								WHERE A.estacion_id = '".$datos_estacion[estacion_id]."' AND
											B.numerodecuenta = A.numerodecuenta AND
											C.ingreso = B.ingreso  AND
											D.tipo_id_paciente = C.tipo_id_paciente AND
											D.paciente_id = C.paciente_id AND
											C.tipo_id_paciente='".$paciente[tipo_id_paciente]."' AND
											C.paciente_id='".$paciente[paciente_id]."' AND
											E.ingreso_dpto_id = A.ingreso_dpto_id AND
											E.fecha_egreso IS NULL AND
											F.cama = E.cama AND
											G.ingreso = C.ingreso AND
											H.evolucion_id = G.evolucion_id AND
											H.sw_estado='1'
								ORDER BY C.paciente_id,F.pieza, E.cama
								)
								UNION
								(SELECT DISTINCT ON (C.paciente_id) C.paciente_id,
												F.pieza,
												E.cama,
												C.tipo_id_paciente,
												D.primer_nombre,
												D.primer_apellido,
												D.segundo_nombre,
												D.segundo_apellido,
												B.ingreso,
												B.numerodecuenta
								FROM		ingresos_departamento A,
												cuentas B,
												ingresos C,
												pacientes D,
												movimientos_habitacion E,
												camas F,
												hc_evoluciones G,
												hc_mezclas_recetadas MR,
												hc_mezclas_recetadas_medicamentos MRM,
												empresas I,
												centros_utilidad J,
												bodegas K,
												bodegas_estaciones M
								WHERE 	A.estacion_id = '".$datos_estacion[estacion_id]."' AND
												B.numerodecuenta = A.numerodecuenta AND
												C.ingreso = B.ingreso  AND
												D.tipo_id_paciente = C.tipo_id_paciente AND
												D.paciente_id = C.paciente_id AND
												C.tipo_id_paciente='".$paciente[tipo_id_paciente]."' AND
												C.paciente_id='".$paciente[paciente_id]."' AND
												E.ingreso_dpto_id = A.ingreso_dpto_id AND
												E.fecha_egreso IS NULL AND
												F.cama = E.cama AND
												G.ingreso = C.ingreso AND
												MR.evolucion_id = G.evolucion_id AND
												MR.sw_estado = '2' AND
												MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
												MRM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												MRM.centro_utilidad = '".$datos_estacion[empresa_id]."' AND
												I.empresa_id = MRM.empresa_id AND
												J.centro_utilidad = MRM.centro_utilidad AND
												K.bodega = MRM.bodega AND
												MRM.bodega = M.bodega
											);";


		list($dbconn) = GetDBconn();
		$resultEmp = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "conexion fallida al intentar consultar los medicamentos recetados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if ($resultEmp->EOF){
				return $B;
			}
			else
			{
			  if(!is_array($B))
				{
							$i=0;
							while (!$resultEmp->EOF)//while ($data = $resultEmp->FetchNextObject())
							{
								$datos[$i] = $resultEmp->GetRowAssoc($ToUpper = false);//mi primer GetRow
								$i++;
								$resultEmp->MoveNext();
							}
							return $datos;
				}
				else
				{
							$i=0;
							while (!$resultEmp->EOF)//while ($data = $resultEmp->FetchNextObject())
							{
								$B[] = $resultEmp->GetRowAssoc($ToUpper = false);//mi primer GetRow
								$i++;
								$resultEmp->MoveNext();
							}
							return $B;

				}			
			}
		}
	}//fin GetPacientesConMedicamentosPorSolicitar


	/**
		*		CallVerMedicamentosPorSolicitarPaciente
		*
		*		Hace un lladado  a la vista que muestra los medicamentos recetados de un paciente x
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallVerMedicamentosPorSolicitarPaciente()
		{
			//$datosPaciente = $_REQUEST["Paciente"];
			if(!$this->VerMedicamentosPorSolicitarPaciente($_REQUEST["Paciente"],$_REQUEST['datos_estacion']))//$_REQUEST['Paciente'],
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"VerMedicamentosPorSolicitarPaciente\"";
				return false;
			}
			return true;
		}


		/**
	*		GetMedicamentosPendientesSolicitadosBodega
	*
	*		Obtiene las solicitudes de medicamentos y mezclas de un ingreso X
	*		que han sido solicitados a bodega
	*		y que aun no se han recibido con estado en 0->sin depacho o 1->despachado
	*		utilizado para mostrarlo en el listado de medicamentos por solicitar
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer => numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesSolicitadosBodega($Ingreso,$datos_estacion)
	{
		 $query = "SELECT J.*,
											SM.fecha_solicitud,
											SM.sw_estado
							FROM hc_solicitudes_medicamentos SM,
									(SELECT SMD.solicitud_id,
													SMD.consecutivo_d,
													NULL as mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.evolucion_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF
									FROM	hc_solicitudes_medicamentos_d SMD,
												medicamentos M,
												inventarios_productos INV,
												inv_med_cod_forma_farmacologica FF
									WHERE INV.codigo_producto = SMD.medicamento_id AND M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION
									SELECT SMD.solicitud_id,
													SMD.consecutivo_d,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.evolucion_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF
										FROM	hc_solicitudes_medicamentos_mezclas_d SMD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
										WHERE INV.codigo_producto = SMD.medicamento_id and M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									) AS J
							WHERE (SM.sw_estado != 2) AND
										SM.solicitud_id = J.solicitud_id AND
										SM.ingreso = $Ingreso AND
										SM.empresa_id = '".$datos_estacion['empresa_id']."' AND
										SM.centro_utilidad = '".$datos_estacion['centro_utilidad']."'
							ORDER BY J.solicitud_id DESC";//(SM.sw_estado = 0 OR SM.sw_estado = 1 OR SM.sw_estado = 2)


		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{ 
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener las solicitudes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF){
			return "ShowMensaje";
		}
		else
		{
			$k = 0;
			while (!$result->EOF)
			{
				$datos[$k] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$result->MoveNext();
				$k++;
			}
			return $datos;
		}
	}//fin  GetMedicamentosPendientesSolicitadosBodega($ingreso)




		/**
		*		 Posologia($vector)
		*
		*		muestra la posología de los medicamentos recetados por el medico
		*
		*		@author: Arley Velásquez
		*		@acces Public
		*		@return array
		*		@param array => medicamento a consultar posología
		*/
		function Posologia($vector)
		{
			list($dbconn) = GetDBconn();
			$posologia="";
			$unidad="";


			if (!empty($vector['unidad_dosis']))
			{
				$query="SELECT * FROM hc_vias_administracion_uds
											WHERE via_administracion_id='".$vector['via_administracion_id']."' AND
											via_uds_id='".$vector['unidad_dosis']."';";
				$resultado =  $dbconn->Execute($query);

				if (!$resultado)  return false;
				$data = $resultado->FetchNextObject($toupper=false);
				$unidad=$data->descripcion;
			}
			$unidad = $vector['cantidad']." ".$unidad." ";

			if ($vector['horario'])
			{
				if ($vector['horario'] < 59)
				{ return ($unidad." cada ".$vector['horario']." minutos"); }
				else
				{
					$horas = floor($vector['horario'] / 60);
					$minutos = $vector['horario'] % 60;
					if($minutos && $horas)  return ($unidad." cada ".$horas." horas ".$minutos." minutos");
					else  return ($unidad." cada ".$horas." hora(s) ");
				}
			}
			if (!empty($vector['sw_rango']))
			{
				if ($vector['sw_rango']=='D')
					$posologia="Durante";
				if ($vector['sw_rango']=='A')
					$posologia="Antes";
				if ($vector['sw_rango']=='U')
					$posologia="Despues";

				if (($vector['desayuno']!=" ") && ($vector['almuerzo']!=" ") && ($vector['comida']!=" "))
				{
					return ($unidad." ".$posologia." (Desayuno,Almuerzo y Cena).");
				}
				if (($vector['desayuno']!=" ") && ($vector['almuerzo']!=" ") && ($vector['comida']==" "))
				{
					return ($unidad." ".$posologia." (Desayuno y Almuerzo).");
				}
				if (($vector['desayuno']!=" ") && ($vector['almuerzo']==" ") && ($vector['comida']!=" "))
				{
					return ($unidad." ".$posologia." (Desayuno y Cena).");
				}
				if (($vector['desayuno']==" ") && ($vector['almuerzo']!=" ") && ($vector['comida']!=" "))
				{
					return ($unidad." ".$posologia." (Almuerzo y Cena).");
				}
				if ($vector['desayuno']!=" ")
				{
					return ($unidad." ".$posologia." (Desayuno).");
				}
				if ($vector['almuerzo']!=" ")
				{
					return ($unidad." ".$posologia." (Almuerzo).");
				}
				if ($vector['comida']!=" ")
				{
					return ($unidad." ".$posologia." (Cena).");
				}
			}
			if (!empty($vector['duracion_id']))
			{
					$data=$this->GetHorario($vector['duracion_id']);
					if (!$data){
						return false;
					}
					$data_r=$data->FetchNextObject($toupper=false);
					if ($data_r->duracion_id=='01')
						return ($unidad." Durante el ".$data_r->descripcion);
					else
						return ($unidad." Durante la ".$data_r->descripcion);
			}
   if (!empty($vector['hora_especifica']))
			{
				$hora_especifica=unserialize($vector['hora_especifica']);
				$posologia=$unidad." durante las siguientes horas:<br>";
				$hora="";
				for($i=0;$i<sizeof($hora_especifica);$i++)
				{
					$hora.="<b>[".$hora_especifica[$i]."]</b>";
				}
				return ($posologia.$hora);
			}
		}//fin  posología



		/**
	*		GetBodegaDelDepartamento => obtiene la unica bodega
	*		asociada al dpto en el que me encuentro para poder realizar los pedidos
	*
	*		Utilizada desde la vista de solicitud(confirmación) de medicamentos del paciente
	*		y desde pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetBodegaDelDepartamento($datos_estacion)
	{
		$query = "SELECT  A.bodega,
											B.descripcion,
											A.sw_bodega_principal
							FROM  bodegas_estaciones A,
										bodegas B
							WHERE A.estacion_id = '".$datos_estacion[estacion_id]."' AND
										A.empresa_id = '".$datos_estacion[empresa_id]."' AND
										A.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
										A.sw_bodega_principal = '1' AND
										B.empresa_id = A.empresa_id AND
										B.centro_utilidad = A.centro_utilidad AND
										B.bodega = A.bodega AND
										B.departamento = '".$datos_estacion[departamento]."' AND
										B.estado = '1'";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener la bodega.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF)
		{
			$this->error = "El departamento no tiene asignada una bodega de medicamentos";
			$this->mensajeDeError = "Consulte con el administrador";
			return false;
		}
		while (!$result->EOF)//data = $result->FetchNextObject())
		{
			$Bodega = $result->GetRowAssoc($toUpper=false);
			$result->MoveNext();
		}
		return $Bodega;
	}



	/*
		*		 ObtenerPlanTerapeutico($evolucion,$medica)
		*
		*		@author: Arley Velásquez
		*		@acces Public
		*		@return array
		*		@param integer => evolucion del medicamento
		*		@param integer => medicamento_id
		*/
		function ObtenerPlanTerapeutico($evolucion,$medica,$datos_estacion)
		{
			list($dbconn) = GetDBconn();
			$cont=0;
			$vecPlanMedicamentos = array();//Vector que contiene el plan de medicamentos del paciente
								$query = "SELECT c.codigo_producto, c.descripcion, c.presentacion, c.formfarmnombre, c.concentracion, c.unidescripcion,
																a.*,
																b.nombre AS vianombre
													FROM hc_medicamentos_recetados a,
																hc_vias_administracion b,
																medicamentos_bodega c
													WHERE a.medicamento_id=c.codigo_producto AND
																a.medicamento_id='".$medica."' AND
																a.empresa_id = '".$datos_estacion[empresa_id]."' AND
																a.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
																c.empresa_id=a.empresa_id AND
																c.centro_utilidad=a.centro_utilidad AND
																a.evolucion_id=".$evolucion." AND
																a.sw_estado='2' AND
																c.bodega=a.bodega AND
																a.via_administracion_id=b.via_administracion_id
																ORDER BY a.fecha";


								$resultMedicamentos = $dbconn->Execute($query);
									if (!$resultMedicamentos)  return false;
									while (!$resultMedicamentos->EOF && $resultMedicamentos->RecordCount())
									{
										$vecPlanMedicamentos[$cont]=$resultMedicamentos->GetRowAssoc($toUpper=false);
										$cont++;
										$resultMedicamentos->MoveNext();
									}//End While
								return $vecPlanMedicamentos;
		}//End  ObtenerPlanTerapeutico




	/*
	*		GetMedicamentosPendientesSolicitadosBodega
	*
	*		Obtiene las solicitudes de medicamentos pedidos al paciente que aun no han sido recididos por EE
	*		en el ingreso utilizado para mostrarlo en el listado de medicamentos por solicitar
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param integer => numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesSolicitadosPaciente($Ingreso,$datos_estacion)
	{
		 $query = 	"(SELECT SMP.consecutivo,
											NULL as mezcla_recetada_id,
											SMP.medicamento_id,
											SMP.evolucion_id,
											SMP.cant_solicitada,
											SMP.fecha_solicitud,
											SMP.ingreso,
											M.cod_forma_farmacologica,
											INV.descripcion as nomMedicamento,
											FF.descripcion as FF
								FROM hc_solicitudes_medicamentos_pacientes SMP,
											medicamentos M,
											inventarios_productos INV,
											inv_med_cod_forma_farmacologica FF
								WHERE SMP.sw_estado = '0' AND
											SMP.ingreso = ".$Ingreso." AND
											INV.codigo_producto = SMP.medicamento_id AND
											M.codigo_medicamento = INV.codigo_producto AND
											FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								ORDER BY SMP.consecutivo
							)
							UNION
							(SELECT SMP.consecutivo,
											SMP.mezcla_recetada_id,
											SMP.medicamento_id,
											SMP.evolucion_id,
											SMP.cant_solicitada,
											SMP.fecha_solicitud,
											SMP.ingreso,
											M.cod_forma_farmacologica,
											INV.descripcion as nomMedicamento,
											FF.descripcion as FF
								FROM  hc_solicitudes_mezclas_pacientes SMP,
											medicamentos M,
											inventarios_productos INV,
											inv_med_cod_forma_farmacologica FF
								WHERE SMP.sw_estado = '0' AND
											SMP.ingreso = ".$Ingreso." AND
											INV.codigo_producto = SMP.medicamento_id AND
											M.codigo_medicamento = INV.codigo_producto AND
											FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								ORDER BY SMP.consecutivo
							)";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Atención";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener las solicitudes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		if($result->EOF)
		{
			return "ShowMensaje";
		}
		else
		{
			$k = 0;
			while (!$result->EOF)
			{
				$datos[$k] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$result->MoveNext();
				$k++;
			}
			return $datos;
		}
	}


		/**
	*		GetMedicamentosRecetados
	*
	*		obtiene los medicamentos recetados y vigentes del paciente X según el # ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, boolean ó string
	*		@param integer => es el numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosRecetados($ingreso,$datos_estacion)
	{
	/* unidad_dosis,cantidad, horario,sw_rango,duracion_id,hora_especifica
											H.empresa_id,
											H.centro_utilidad,
											H.bodega,
											H.medicamento_id,
											H.cantidad_total,
											H.indicacion_suministro
 */
 /*  $query = "SELECT DISTINCT ON (G.evolucion_id,H.medicamento_id)
											G.evolucion_id,
											N.razon_social,
											J.descripcion as nomCentro,
											K.descripcion as nomBodega,
											I.descripcion as nomMedicamento,
											L.concentracion_forma_farmacologica,
											V.nombre as viaAdmin,
											L.sw_pos,
											L.cod_forma_farmacologica,
											F.descripcion as nomFF,
											H.*
							FROM  hc_evoluciones G,
										hc_medicamentos_recetados H,
										bodegas K,
										bodegas_estaciones M,
										medicamentos L,
										inventarios_productos I,
										inv_med_cod_forma_farmacologica F,
										empresas N,
										centros_utilidad J,
										hc_vias_administracion V
							WHERE G.ingreso = $ingreso AND
										H.evolucion_id = G.evolucion_id AND
										H.sw_estado = '2' AND
										H.empresa_id = '".$datos_estacion[empresa_id]."' AND
										H.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
										K.bodega = H.bodega AND
										K.bodega = M.bodega AND
										H.medicamento_id = I.codigo_producto AND
										L.codigo_medicamento = I.codigo_producto AND
										F.cod_forma_farmacologica = L.cod_forma_farmacologica AND
										N.empresa_id = H.empresa_id AND
										J.centro_utilidad = H.centro_utilidad AND
										V.via_administracion_id = H.via_administracion_id";*/
										$query="SELECT DISTINCT ON (G.evolucion_id,H.codigo_producto) G.evolucion_id, null as razon_social, null as nomCentro, K.descripcion as nomBodega, I.descripcion as nomMedicamento, L.concentracion_forma_farmacologica, V.nombre as viaAdmin, L.sw_pos, L.cod_forma_farmacologica, F.descripcion as nomFF, H.codigo_producto as medicamento_id, h.evolucion_id, h.cantidad as cantidad_total, h.observacion, h.sw_paciente_no_pos, h.via_administracion_id, h.dosis, h.unidad_dosificacion, h.tipo_opcion_posologia_id FROM hc_evoluciones G, hc_medicamentos_recetados_amb H left join hc_vias_administracion V on(V.via_administracion_id = H.via_administracion_id), bodegas K, bodegas_estaciones M, medicamentos L, inventarios_productos I, inv_med_cod_forma_farmacologica F WHERE G.ingreso = $ingreso AND H.evolucion_id = G.evolucion_id AND K.bodega = M.bodega AND H.codigo_producto = I.codigo_producto AND L.codigo_medicamento = I.codigo_producto AND F.cod_forma_farmacologica = L.cod_forma_farmacologica;";

		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener los datos de los medicamentos recetados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				$i=0;
				while (!$result->EOF)//while ($data = $result->FetchNextObject())
				{
					$Medicamentos[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$i++;
					$result->MoveNext();
				}
				return $Medicamentos;
			}
		}
	}//fin GetMedicamentosRecetados()



	
	
	
	/**
	*		Get_Existencia_producto_Bodega
	*
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function Get_Existencia_producto_Bodega($codigo,$estacion)
	{
	
		list($dbconn) = GetDBconn();
  	$query="SELECT DISTINCT(bodega) FROM  existencias_bodegas
						WHERE codigo_producto='$codigo'
						AND empresa_id='".$estacion[empresa_id]."'
						AND centro_utilidad='".$estacion[centro_utilidad]."'";
		
								$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
       $result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}

		 if($result->EOF)
		 {
			 return '';
	   }
		 $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}

	  return $vector;
	}
	
	
	
	
	
	
	


	/**
	*		GetEstacionBodega
	*
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function GetEstacionBodega($datos,$sw)
	{
		if($sw==1)
		{
			$filtro="AND b.sw_consumo_directo='0'";
		}
		elseif($sw==2)
		{
			$filtro="AND b.sw_consumo_directo='1'";
		}
		list($dbconn) = GetDBconn();
    $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion
		 									FROM bodegas_estaciones a,bodegas b
											WHERE
											 a.estacion_id='".$datos[estacion_id]."'
											 AND a.centro_utilidad=b.centro_utilidad
											 AND a.empresa_id=b.empresa_id
											 AND a.bodega=b.bodega
											 $filtro
											 AND a.centro_utilidad='".$datos[centro_utilidad]."'
											 AND a.empresa_id='".$datos[empresa_id]."'";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
       $result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}

		 if($result->EOF)
		 {
			 return '';
	   }
		 $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}

	  return $vector;
	}


	
	/**
	*		GetEstacionBodega
	*
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function GetEstacionBodega_Existencias($datos,$sw,$codigo)
	{
		if($sw==1)
		{
			$filtro="AND b.sw_consumo_directo='0'";
		}
		elseif($sw==2)
		{
			$filtro="AND b.sw_consumo_directo='1'";
		}
		list($dbconn) = GetDBconn();
          $query="( SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion,c.existencia
                    FROM bodegas_estaciones a,bodegas b,existencias_bodegas c
                    WHERE
                    a.estacion_id='".$datos[estacion_id]."'
                    AND a.centro_utilidad=b.centro_utilidad
                    AND a.empresa_id=b.empresa_id
                    AND a.bodega=b.bodega
                    AND a.bodega=c.bodega
                    AND c.existencia > 0
                    $filtro
                    AND c.codigo_producto='$codigo'
                    AND a.empresa_id=c.empresa_id
                    AND a.centro_utilidad=c.centro_utilidad
                    AND a.centro_utilidad='".$datos[centro_utilidad]."'
                    AND a.empresa_id='".$datos[empresa_id]."')
                    UNION
                  ( SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.descripcion,c.existencia
                    FROM bodegas_estaciones a,bodegas b,existencias_bodegas c
                    WHERE
                    a.estacion_id='".$datos[estacion_id]."'
                    AND a.centro_utilidad=b.centro_utilidad
                    AND a.empresa_id=b.empresa_id
                    AND a.bodega=b.bodega
                    AND a.bodega=c.bodega
                    AND c.existencia >= 0
                    AND b.sw_restriccion_stock = '1'
                    $filtro
                    AND c.codigo_producto='$codigo'
                    AND a.empresa_id=c.empresa_id
                    AND a.centro_utilidad=c.centro_utilidad
                    AND a.centro_utilidad='".$datos[centro_utilidad]."'
                    AND a.empresa_id='".$datos[empresa_id]."');";



/*               $resulta=$dbconn->execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                         $this->error = "Error al Cargar el Modulo";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }*/
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          if($result->EOF)
          {
               return '';
     	}
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          $result->close();
	     return $vector;
	}
	
	
	
	
/**
	*		RevisarExistenciaBodega
	*
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function RevisarExistenciaBodega($estacion,$bodega,$codigo)
	{

				list($dbconn) = GetDBconn();

		$query="SELECT existencia FROM existencias_bodegas
							WHERE
							empresa_id='".$estacion['empresa_id']."'
							AND centro_utilidad='".$estacion['centro_utilidad']."'
							AND bodega='$bodega'
							AND codigo_producto='$codigo'";

												$resulta=$dbconn->execute($query);
									if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Cargar el Modulo";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
									}

			return $resulta->fields[0]; //empresa

	}



	/**
	*		GetEstacionBodega
	*
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	//buscar aqui
	function TraerNombreBodega($estacion,$bodega)
	{

			list($dbconn) = GetDBconn();

     $query="SELECT descripcion FROM bodegas
						 WHERE
						 empresa_id='".$estacion['empresa_id']."'
						 AND centro_utilidad='".$estacion['centro_utilidad']."'
						 AND bodega='$bodega'";
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}

		 return $resulta->fields[0]; //empresa

	}



	function Cancelar_Sol_X_Med_Pacientes()
	{
			$estacion=$_REQUEST['estacion'];
			$datos_estacion=$_REQUEST['datos_estacion'];
			$codigo=$_REQUEST['codigo_producto'];
			//$solicitud=$_REQUEST['solicitud'];//solicitud no va a llegar. ojo con eso...
		
			list($dbconn) = GetDBconn();
			$query="UPDATE hc_solicitudes_medicamentos_pacientes_d
							SET	sw_estado='2'
						  WHERE codigo_producto='$codigo'
							AND ingreso='".$datos_estacion[ingreso]."'";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "No se actualizo en hc_solicitudes_medicamentos_pacientes_d ";
					$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					return false;
				}
				
		$this->frmError["MensajeError"]="MEDICAMENTO CANCELADO SATISFACTORIAMENTE.";
		$this->FrmMedicamentos($estacion,$datos_estacion);
		return true;
  }

	
	/**
	*		InsertSolicitudMed_Para_Paciente
	*
	*		guarda o realiza la solicitud de los medicamentos para el paciente, 
	*   donde no se traen de bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function InsertSolicitudMed_Para_Paciente()
	{
	
		$bodega=$_REQUEST['bodega'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		//$op=$_REQUEST['op'];
		$op=$_SESSION['ESTACION_MED']['VECTOR_SOL_OP'];
		$cant=$_REQUEST['cantidad'];
		$area=$_REQUEST['area'];
		$nom=$_REQUEST['nom'];

		list($dbconn) = GetDBconn();


		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_pacientes_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$solicitud=$res->fields[0];


		 $query="INSERT INTO  hc_solicitudes_medicamentos_pacientes
						(
						 solicitud_id,
						 ingreso,
						 usuario_id,
						 sw_estado,
						 fecha_solicitud,
						 estacion_id,
						 observaciones,
						 nombre_recibe_solicitud,
						 tipo_solicitud
						 
						 )VALUES('$solicitud',
						 		".$datos_estacion[ingreso].",
						 		".UserGetUID().",
								'0',
								'".date("Y-m-d H:i:s")."',
								'".$estacion[estacion_id]."',
								'$area',
								'$nom',
								'M'
								)";
							$dbconn->StartTrans();
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "No se inserto en hc_solicitudes_medicamentos_pacientes ";
								$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}

						for($i=0;$i<sizeof($op);$i++)
						{
									$dat_op=explode(",",$op[$i]);
						$query="INSERT INTO hc_solicitudes_medicamentos_pacientes_d
											(
											solicitud_id,
											codigo_producto,
											sw_estado,
											cantidad,
											ingreso
											)VALUES('$solicitud',
													'".$dat_op[0]."',
													'0',
													'".$dat_op[2]."',
													".$datos_estacion[ingreso]."
													)";
												$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
													$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
													$dbconn->RollbackTrans();
													return false;
												}

						}
						
		if(is_array($_REQUEST['checo']) and $para_q_no_entre=='xxx')
		{
								//$dbconn=$this->Insertar_Extra_InsumosPaciente(&$dbconn,$estacion,$datos_estacion,$bodega);				
								
								
	
						//funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
						//las solicitudes de medicamentos.
								
							$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
							$res=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "No se pudo traer la secuencia ";
								$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								return false;
							}
					
							$solicitud=$res->fields[0];
					
					
						$query="INSERT INTO hc_solicitudes_medicamentos
											(
											solicitud_id,
											ingreso,
											bodega,
											empresa_id,
											centro_utilidad,
											usuario_id,
											sw_estado,
											fecha_solicitud,
											estacion_id,
											tipo_solicitud
											)VALUES('$solicitud',
													".$datos_estacion[ingreso].",
													'".$bodega."',
													'".$estacion[empresa_id]."',
													'".$estacion[centro_utilidad]."',
													".UserGetUID().",
													'0',
													'".date("Y-m-d H:i:s")."',
													'".$estacion[estacion_id]."',
													'I')";
												$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "No se inserto en hc_solicitudes_medicamentos ";
													$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
													$dbconn->RollbackTrans();
													return false;
												}
					
																	
											for($r=0;$r<sizeof($_REQUEST['checo']);$r++)
											{
											
												$codigo=explode("^",$_REQUEST['checo'][$r]);
												//[0]-> medicamento_id
												//[1]-> codigo_producto o insumo_id
												//[2]-> bodega
											
												$cantidad=$_REQUEST['cant'.$codigo[0].$codigo[1].$codigo[2]];
												
												if(!empty($cantidad))
												{
												$query="INSERT INTO hc_solicitudes_insumos_d
																	(
																	solicitud_id,
																	codigo_producto,
																	cantidad
																	)VALUES('$solicitud',
																			'".$codigo[1]."',
																			'".$cantidad."'
																		)";
																	$dbconn->Execute($query);
																		if ($dbconn->ErrorNo() != 0)
																		{
																			$this->error = "No se inserto en hc_solicitudes_insumos_d ";
																			$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
																			$dbconn->RollbackTrans();
																			return false;
																		}
												}
									
										}
			
		}	
		$dbconn->CompleteTrans();   //termina la transaccion
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$this->FrmMedicamentos($estacion,$datos_estacion);
		return true;
	}

	
	
	
	/**
	*		Insertar_Recibido_Para_Pacientes
	*
	*		guarda o realiza la solicitud de los medicamentos para el paciente, 
	*   donde no se traen de bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		
	*/
	function Insertar_Recibido_Para_Pacientes()
	{
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$cant=$_REQUEST['cantidad'];
		$cantidad_sol=$_REQUEST['cant_sol'];
		$area=$_REQUEST['area'];
		$nom=$_REQUEST['nom'];
		$codigo=$_REQUEST['codigo'];
		$solicitud=$_REQUEST['solicitud'];
		$data=$_REQUEST['data'];
		
	
		/*	if(!is_numeric($cant))
		{
			$this->frmError["MensajeError"]="DEBE DIGITAR SOLO NUMEROS EN LA CASILLA DE CANTIDAD";  
			$this->Recibir_X_Para_Pacientes($estacion,$datos_estacion,$codigo,$solicitud);
			return true;
		}
		
		if($cant==0)
		{
			$this->frmError["MensajeError"]="NO PUEDE GUARDAR VALORES DE CANTIDAD EN 0";  
			$this->Recibir_X_Para_Pacientes($estacion,$datos_estacion,$codigo,$solicitud);
			return true;
		}
		
		if($cant > $cantidad_sol)
		{
			$this->frmError["MensajeError"]="NO SE PUEDE RECIBIR MAS INSUMOS / MEDICAMENTOS DE LOS QUE SE SOLICITO";
			$this->Recibir_X_Para_Pacientes($estacion,$datos_estacion,$codigo,$solicitud);
			return true;
		}*/
		list($dbconn) = GetDBconn();
		$contador=0;


		$query="SELECT NEXTVAL('public.hc_recepcion_medicamentos_pacientes_recepcion_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$recepcion=$res->fields[0];


		 $query="INSERT INTO  hc_recepcion_medicamentos_pacientes
						(
						 recepcion_id,
						 ingreso,
						 usuario_id,
						 fecha_recepcion,
						 estacion_id,
						 observaciones,
						 nombre_entrega
						 
						 )VALUES('$recepcion',
						 		".$datos_estacion[ingreso].",
						 		".UserGetUID().",
								'".date("Y-m-d H:i:s")."',
								'".$estacion[estacion_id]."',
								'$area',
								'$nom'
								)";
							$dbconn->StartTrans();
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "No se inserto en hc_solicitudes_medicamentos_pacientes ";
								$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}

						
		
		for($w=0;$w<sizeof($data);$w++)
		{
			$e=explode(",",$data[$w]);
			if(!empty($e[0]))
			{
				$solicitud=$e[0];
				$codigo=$e[1];unset($e);
			}
			
					$cant=$_REQUEST['cantidad'][$w][$codigo];
					$cant_sol=$_REQUEST['cant_sol'][$w][$codigo];
					$cant_rec=$_REQUEST['cant_rec'][$w][$codigo];

						if(($cant)>0 and is_numeric($cant))
						{
						$query="INSERT INTO hc_recepcion_medicamentos_pacientes_d
											(
											recepcion_id,
											codigo_producto,
											cantidad,
											ingreso
											)VALUES('$recepcion',
													'".$codigo."',
													$cant,
													".$datos_estacion[ingreso].")";
												$dbconn->Execute($query);
									    	if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
													$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
													$dbconn->RollbackTrans();
													return false;
												}
												
												
												
										$query= "SELECT SUM(z.cantidad) AS cantidad
										FROM
										hc_recepcion_medicamentos_pacientes x,
										hc_recepcion_medicamentos_pacientes_d z
								
								
										WHERE
										x.ingreso = '".$datos_estacion[ingreso]."' AND
										z.codigo_producto = '$codigo' AND
										z.estado='0' AND
										x.estacion_id = '".$estacion[estacion_id]."'
										AND x.recepcion_id=z.recepcion_id";
								
										$result = $dbconn->Execute($query);
								
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al buscar en la consulta de medicamentos recetados";
											$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
										}
								
								if($result->fields[0] >= $cant_sol)
								{
									$query="UPDATE  	hc_solicitudes_medicamentos_pacientes_d SET
													sw_estado='1' WHERE ingreso='".$datos_estacion[ingreso]."' AND codigo_producto = '$codigo'"	;
									$dbconn->Execute($query);
								
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al buscar en la consulta de medicamentos recetados";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
										}
									//actualizamos la recepcion de med y cambiamos en 1 cuando ya hallamos cumplido
									//con la solicitud
								 	$sql="UPDATE hc_recepcion_medicamentos_pacientes_d SET
												estado='1' WHERE ingreso='".$datos_estacion[ingreso]."' AND codigo_producto = '$codigo'"	;
										$dbconn->Execute($sql);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al buscar en la consulta de medicamentos recetados";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$dbconn->RollbackTrans();
											return false;
										}
										
								}
												
					}
					else{$contador++;}
		}
		if($contador==sizeof($data))
		{
			$dbconn->RollbackTrans();
	  	$this->frmError["MensajeError"]="LOS DATOS NO SE GUARDARON.";
		}else
		{
			$dbconn->CompleteTrans();
			$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		}   //termina la transaccion
		$this->Recibir_X_Para_Pacientes($estacion,$datos_estacion,$codigo,$solicitud,$data);
		//$this->FrmMedicamentos($estacion,$datos_estacion);
		return true;
	}
	
	
	
	
	
	
	
	
	

	/**
	*		InsertSolicitudMed
	*
	*		guarda o realiza la solicitud de los medicamentos.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function InsertSolicitudMed()
	{
	
		$bodega=$_REQUEST['bodega'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		//$op=$_REQUEST['op'];
		$op=$_SESSION['ESTACION_MED']['VECTOR_SOL_OP'];
		$cant=$_REQUEST['cantidad'];

		list($dbconn) = GetDBconn();


		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$solicitud=$res->fields[0];


		 $query="INSERT INTO hc_solicitudes_medicamentos
						(
						 solicitud_id,
						 ingreso,
						 bodega,
						 empresa_id,
						 centro_utilidad,
						 usuario_id,
						 sw_estado,
						 fecha_solicitud,
						 estacion_id,
						 tipo_solicitud
						 )VALUES('$solicitud',
						 		".$datos_estacion[ingreso].",
						 		'".$bodega."',
								'".$estacion[empresa_id]."',
								'".$estacion[centro_utilidad]."',
								".UserGetUID().",
								'0',
								'".date("Y-m-d H:i:s")."',
								'".$estacion[estacion_id]."',
								'M')";
							$dbconn->StartTrans();
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "No se inserto en hc_solicitudes_medicamentos ";
								$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								return false;
							}

						for($i=0;$i<sizeof($op);$i++)
						{
									$dat_op=explode(",",$op[$i]);
						$query="INSERT INTO hc_solicitudes_medicamentos_d
											(
											solicitud_id,
											medicamento_id,
											evolucion_id,
											cant_solicitada
											)VALUES('$solicitud',
													'".$dat_op[0]."',
													'".$dat_op[1]."',
													'".$dat_op[2]."'
													)";
												$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "No se inserto en hc_solicitudes_medicamentos_d ";
													$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de medicamentos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
													$dbconn->RollbackTrans();
													return false;
												}

						}
						
		if(is_array($_REQUEST['checo']))
		{
								//$dbconn=$this->Insertar_Extra_InsumosPaciente(&$dbconn,$estacion,$datos_estacion,$bodega);				
								
								
	
						//funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
						//las solicitudes de medicamentos.
								
							$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
							$res=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "No se pudo traer la secuencia ";
								$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								return false;
							}
					
							$solicitud=$res->fields[0];
					
					
						$query="INSERT INTO hc_solicitudes_medicamentos
											(
											solicitud_id,
											ingreso,
											bodega,
											empresa_id,
											centro_utilidad,
											usuario_id,
											sw_estado,
											fecha_solicitud,
											estacion_id,
											tipo_solicitud
											)VALUES('$solicitud',
													".$datos_estacion[ingreso].",
													'".$bodega."',
													'".$estacion[empresa_id]."',
													'".$estacion[centro_utilidad]."',
													".UserGetUID().",
													'0',
													'".date("Y-m-d H:i:s")."',
													'".$estacion[estacion_id]."',
													'I')";
												$dbconn->Execute($query);
												if ($dbconn->ErrorNo() != 0)
												{
													$this->error = "No se inserto en hc_solicitudes_medicamentos ";
													$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
													$dbconn->RollbackTrans();
													return false;
												}
					
																	
											for($r=0;$r<sizeof($_REQUEST['checo']);$r++)
											{
											
												$codigo=explode("^",$_REQUEST['checo'][$r]);
												//[0]-> medicamento_id
												//[1]-> codigo_producto o insumo_id
												//[2]-> bodega
											
												$cantidad=$_REQUEST['cant'.$codigo[0].$codigo[1].$codigo[2]];
												
												if(!empty($cantidad))
												{
												$query="INSERT INTO hc_solicitudes_insumos_d
																	(
																	solicitud_id,
																	codigo_producto,
																	cantidad
																	)VALUES('$solicitud',
																			'".$codigo[1]."',
																			'".$cantidad."'
																		)";
																	$dbconn->Execute($query);
																		if ($dbconn->ErrorNo() != 0)
																		{
																			$this->error = "No se inserto en hc_solicitudes_insumos_d ";
																			$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
																			$dbconn->RollbackTrans();
																			return false;
																		}
												}
									
										}
			
		}	
		$dbconn->CompleteTrans();   //termina la transaccion
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$this->FrmMedicamentos($estacion,$datos_estacion);
		return true;
	}




	/**
	*		GetMezclasRecetadas
	*
	*		obtiene las mezclas recetadas al paciente según el # ingreso
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, boolean ó string
	*		@param integer => es el numero de ingreso del paciente
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMezclasRecetadas($ingreso,$datos_estacion)
	{
	/*
											MR.mezcla_recetada_id,
											MR.via_administracion_id,
											MR.evolucion_id,
											MR.observaciones,
	*/
		$query = "SELECT  MR.*,
											TUF. descripcion as des_tipo_calculo,
											MRM.mezcla_recetada_id,
											MRM.medicamento_id,
											MRM.empresa_id,
											MRM.centro_utilidad,
											MRM.bodega,
											MRM.cantidad,
											MRM.sw_pos,
											I.descripcion as nomMedicamento,
											L.cod_forma_farmacologica,
											FF.descripcion as nomFF,
											B.descripcion as nombodega,
											CU.descripcion as nomCentro,
											E.razon_social
							FROM 	hc_mezclas_recetadas MR,
									 	hc_mezclas_recetadas_medicamentos MRM,
										hc_evoluciones G,
										medicamentos L,
										inventarios_productos I,
										inv_med_cod_forma_farmacologica FF,
										bodegas B,
										centros_utilidad CU,
										empresas E,
										hc_tipo_unidades_frecuencia TUF
							WHERE MR.sw_estado = '2' AND
										MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
										G.ingreso = $ingreso AND
										MR.evolucion_id = G.evolucion_id AND
										MRM.medicamento_id = I.codigo_producto AND
										L.codigo_medicamento = I.codigo_producto AND
										MRM.medicamento_id = I.codigo_producto AND
										MRM.empresa_id = B.empresa_id AND
										MRM.centro_utilidad = B.centro_utilidad AND
										FF.cod_forma_farmacologica = L.cod_forma_farmacologica AND
										MRM.bodega = B.bodega AND
										MRM.empresa_id = '".$datos_estacion[empresa_id]."' AND
										MRM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
										CU.centro_utilidad = MRM.centro_utilidad AND
										CU.empresa_id = MRM.empresa_id AND
										E.empresa_id = MRM.empresa_id AND
										TUF.tipo_unidad_fr_id = MR.unidad_calculo
							ORDER BY MR.mezcla_recetada_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				/*$i=0;
				while (!$result->EOF)//while ($data = $result->FetchNextObject())
				{
					$Mezclas[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$i++;
					$result->MoveNext();
				}*/
				while ($data = $result->FetchRow())
				{
					$solucion = "SELECT sw_solucion
											 FROM medicamentos
											 WHERE codigo_medicamento = '".$data['codigo_medicamento']."'";

					GLOBAL $ADODB_FETCH_MODE;
					list($dbconn) = GetDBconn();
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$resultado = $dbconn->Execute($solucion);
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					$data['solucion'] = $resultado->fields[sw_solucion];
					$Mezclas[] = $data;
				}
				return $Mezclas;
			}
		}
	}//fin GetMezclasRecetadas($ingreso)



//funcion q saca la fecha del ingreso
function GetDatIngreso($ingreso)
{
	list($dbconn) = GetDBconn();
	$sql="SELECT fecha_ingreso FROM ingresos WHERE ingreso='$ingreso';";
	$result = $dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0)
	{
		$this->error = "Error al buscar en la consulta de medicamentos recetados";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
	}
	return $result->fields[0];

}


	//funcion del mod medicamentos(estacion e)
	/**
	*		FrmDevolucionMedicamentos
	*
	*		Muestra los medicamentos que pueden ser devueltos => Alex me dió esta formula:
	*		a la suma de medicamentos solicitados le resto la suma de los medicamentos devueltos
	*		ya sea que estén en espera de aceptacion de devoluciion o que ya hayan sido procesados
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return boolean
	*		@param array => pacientes con ordenes de medicamentos
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetDevolucionMedicamentos($ingreso,$bodega,$letra)
	{
		list($dbconn) = GetDBconn();
          /*(SELECT SUM(y.cantidad)
                         FROM ingresos v,cuentas w,cuentas_detalle x, bodegas_documentos_d y, bodegas_doc_numeraciones z
                         WHERE v.ingreso='$ingreso'
                         AND v.ingreso=w.ingreso
                         AND	w.numerodecuenta=x.numerodecuenta
                         AND	x.consecutivo is not null
                         AND	x.consecutivo=y.consecutivo
                         AND y.bodegas_doc_id=z.bodegas_doc_id
                         AND y.codigo_producto=d.codigo_producto AND z.empresa_id=e.empresa_id AND z.centro_utilidad=e.centro_utilidad
                         AND z.bodega=e.bodega AND e.bodega='$bodega' AND x.cargo = 'DIMD')
                         AS suma2*/
                         
		$query="SELECT DISTINCT d.codigo_producto,f.descripcion,e.empresa_id,
                         e.centro_utilidad,e.bodega,
                         
                         (SELECT SUM(y.cantidad)
                         FROM ingresos v,cuentas w,cuentas_detalle x, bodegas_documentos_d y, bodegas_doc_numeraciones z
                         WHERE v.ingreso='$ingreso'
                         AND v.ingreso=w.ingreso
                         AND	w.numerodecuenta=x.numerodecuenta
                         AND	x.consecutivo is not null
                         AND	x.consecutivo=y.consecutivo
                         AND y.bodegas_doc_id=z.bodegas_doc_id
                         AND y.codigo_producto=d.codigo_producto AND z.empresa_id=e.empresa_id AND z.centro_utilidad=e.centro_utilidad
                         AND z.bodega=e.bodega AND e.bodega='$bodega' AND x.cargo = 'IMD')
                         as suma1
                         
                         FROM
                         ingresos a,cuentas b,cuentas_detalle c, bodegas_documentos_d d, bodegas_doc_numeraciones e,inventarios_productos f,
                         hc_solicitudes_medicamentos ñ

                         WHERE a.ingreso='$ingreso'
                         AND a.ingreso=b.ingreso
                         AND	b.numerodecuenta=c.numerodecuenta
                         AND	c.consecutivo is not null
                         AND	c.consecutivo=d.consecutivo
                         AND d.bodegas_doc_id=e.bodegas_doc_id
                         AND	d.codigo_producto=f.codigo_producto
                         AND ñ.bodegas_doc_id=d.bodegas_doc_id
                         AND ñ.numeracion=d.numeracion
                         AND ñ.tipo_solicitud='$letra'
                         AND e.bodega='$bodega'";

          $result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		     return false;
		}

          if($result->EOF)
          {
               return '';
          }
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
	}//fin FrmDevolucionMedicamentos()
	
	
	
	//trae los insumos de la tabla inventarios
	function GetInsumos($bodega,$filtro)
	{
		list($dbconn) = GetDBconn();
		
		if($bodega=='*/*')
		{	$filtro_bodega="";}else{$filtro_bodega="AND a.bodega='$bodega'";}
		
		if(empty($_REQUEST['conteo'])){
		$query = "SELECT b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto
						FROM
						existencias_bodegas a,
						inventarios_productos b,
						inv_grupos_inventarios c
						WHERE 
						a.codigo_producto=b.codigo_producto
						AND c.grupo_id=b.grupo_id
						AND c.sw_insumos='1'
						$filtro_bodega
						$filtro";

		$result = $dbconn->Execute($query);
		list($this->conteo)=$result->RecordCount();
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
			$this->conteo=$result->RecordCount();
    }else{
      $this->conteo=$_REQUEST['conteo'];
		}
		if(!$_REQUEST['Of']){
      $Of='0';
		}else{
      $Of=$_REQUEST['Of'];
		}
		
				
		if($bodega=="-1" OR empty($bodega))
		{
			return '';
		}
    			$query="SELECT b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto
                         FROM
                         existencias_bodegas a,
                         inventarios_productos b,
                         inv_grupos_inventarios c
                         WHERE 
                         a.codigo_producto=b.codigo_producto
                         AND c.grupo_id=b.grupo_id
                         AND c.sw_insumos='1'
                         $filtro_bodega
                         $filtro
                         LIMIT " . $this->limit . " OFFSET $Of";
                    

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}
		  $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}

	  return $vector;
	}//
	
	
	

	
	
	/*
		*		SetDevolucionMedicamentos()
		*
		*		Hace la solicitud de devolucion de los medicamnetos seleccionados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function SetDevolucionMedicamentos()
		{
			$CheckMedicamentos = $_REQUEST['CheckMedicamentos'];
			$CantDevolver = $_REQUEST['CantDevolver'];
			$FechaCargo = $_REQUEST['FechaCargo'];
			$Observaciones = $_REQUEST['Observaciones'];
			$datos_estacion = $_REQUEST['datos_estacion'];
			$ingreso = $_REQUEST['ingreso'];
			list($dbconn) = GetDBconn();

			if(empty($Observaciones)){
				$Observaciones = "NULL";
			}
			else{
				$Observaciones = "'".$Observaciones."'";
			}

			if(!sizeof($CheckMedicamentos))
			{
				$mensaje = "DEBE SELECCIONAR AL MENOS UN MEDICAMENTO PARA HACER LA DEVOLUCION";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','FrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion));
				$boton = "REGRESAR";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{
				if(!$bodega = $this->GetBodegaDelDepartamento($datos_estacion))
				{
					$mensaje = "NO SE PUDO SELECCIONAR LA BODEGA DEL DEPARTAMENTO";
					$titulo = "MENSAJE";
					$boton = "REGRESAR";
					$accion = "javascript:histoy.back()";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}

				$query = "SELECT nextval('public.inv_solicitudes_devolucion_documento_seq')";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar obtener la secuencia de la solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
				else
				{
					if(!$result->EOF)
					{
						$documetoDevolucion = $result->fields[0];
						$dbconn->BeginTrans();
						$puedoHacerCommit = array();
						$query = "INSERT INTO inv_solicitudes_devolucion (
																															empresa_id,
																															centro_utilidad,
																															documento,
																															bodega,
																															fecha,
																															observacion,
																															usuario_id,
																															fecha_registro,
																															estacion_id,
																															estado,
																															ingreso
																															)
																											VALUES (
																															'".$datos_estacion[empresa_id]."',
																															'".$datos_estacion[centro_utilidad]."',
																															$documetoDevolucion,
																															'".$bodega[bodega]."',
																															'".$FechaCargo."',
																															$Observaciones,
																															".UserGetUID().",
																															'".date("Y-m-d H:i:s")."',
																															'".$datos_estacion[estacion_id]."',
																															'0',
																															".$ingreso.");";

						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar realizar la solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							$puedoHacerCommit[] = 0;
							return false;
						}
						else
						{//[codigo_producto], CantDevolver, $i, numerodecuenta, [facturado]
							foreach($CheckMedicamentos as $key => $value)
							{
								$Med = explode(".-.",$value);
								$query = "INSERT INTO inv_solicitudes_devolucion_d (
																																		documento,
																																		empresa_id,
																																		codigo_producto,
																																		cantidad,
																																		centro_utilidad,
																																		bodega
																																		)
																														VALUES (
																																		$documetoDevolucion,
																																		'".$datos_estacion[empresa_id]."',
																																		'".$Med[0]."',
																																		".$CantDevolver[$Med[2]].",
																																		'".$datos_estacion[centro_utilidad]."',
																																		'".$bodega[bodega]."');";

								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "Ocurrió un error al intentar realizar el detalle de la solicitud de devolucion.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									$dbconn->RollbackTrans();
									$puedoHacerCommit[] = 0;
									return false;
								}
								else{
									$puedoHacerCommit[] = 1;
								}
							}//fin foreach
							if(!in_array(0,$puedoHacerCommit))
							{
								$dbconn->CommitTrans();
								$mensaje = "LA SOLICITUD DE DEVOLUCIÓN DE MEDICAMENTOS SE REALIZÓ CON ÉXITO";
								$titulo = "MENSAJE";
								$boton = "VOLVER AL DEVOLUCION DE MEDICAMENTOS";
								$action =  ModuloGetURL('app','EstacionE_Medicamentos','user','FrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion));
								$this->FormaMensaje($mensaje,$titulo,$action,$boton);
								return true;
							}
						}//fin else detalle
					}//fin si hizo el nextval
				}//.sizeof($Medicamentos)."<br>"; print_r($Medicamentos); print_r($CheckMedicamentos);
			}//fin else => si hay CheckMedicamentos por devolver
		}//setdevolucionmedicamentos



	/*
		*		CallVerMedicamentosPorDevolverPaciente()
		*
		*		Llama la vista que muestra los medicamentos del paciente seleccionado (medicamentos por devolver)
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function  CallVerMedicamentosPorDevolverPaciente()
		{
			//$datos = unserialize(stripslashes(urldecode($_REQUEST['Paciente'])));
			//if(!$this->VerMedicamentosPorDevolverPaciente($datos,$_REQUEST['datos_estacion']))
			if(!$this->VerMedicamentosPorDevolverPaciente(unserialize(stripslashes(urldecode($_REQUEST['Paciente']))),$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"VerMedicamentosPorDevolverPaciente\"";
				return false;
			}
			return true;
		}



	/**
		*		function PosologiaMezcla
		*
		*		muestra la posología de las mezclas recetadas por el medico
		*
		*		@author: Arley Velásquez
		*		@acces Public
		*		@return array
		*		@param integer => evolucion en que se recetó la mezcla
		*		@param array => id de la mezcla
		*/
		function PosologiaMezcla($vector)
		{
			switch ($vector['unidad_calculo'])
			{
				case 1:
								return ($vector['cantidad_calculo']." cm3 cada hora.");
				break;
				case 2:
								return ($vector['cantidad_calculo']." gotas cada minuto.");
				break;
				case 3:
								return ($vector['cantidad_calculo']." microgotas cada minuto.");
				break;
				case 4:
								return ($vector['cantidad_calculo']." cm3 cada hora.");
				break;
				case 5:
								return ($vector['cantidad_calculo']." en bolo.");
				break;
			}
		}




	/**
		*		ObtenerPlanTerpeuticoMezclas
		*
		*		muestra la posología de las mezclas recetadas por el medico
		*
		*		@author: Arley Velásquez
		*		@acces Public
		*		@return array
		*		@param integer => evolucion en que se recetó la mezcla
		*		@param array => id de la mezcla
		*/
		function ObtenerPlanTerpeuticoMezclas($evolucion,$mezcla)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$cont=0;
			$vecPlanMedicamentos = array();//Vector que contiene el plan de medicamentos de mezclas para el paciente
			$i=0;
			$query =" SELECT a.*, b.nombre as vianombre
								FROM  hc_mezclas_recetadas a,
											hc_vias_administracion b
								WHERE a.sw_estado='2' AND
											a.mezcla_recetada_id=".$mezcla." AND
											a.evolucion_id=".$evolucion." AND
											b.via_administracion_id=a.via_administracion_id";


			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resultado = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
				if (!$resultado)  return false;
			while (!$resultado->EOF && $resultado->RecordCount())
			{
				$mezclas_med[$i]=$resultado->GetRowAssoc($toUpper=false);
				$resultado->MoveNext();
				$i++;
			}
			return $mezclas_med;
		}//End  ObtenerPlanTerpeuticoMezclas



	/*esta funcion mod estacione_medicamentos*/

	   /*funcion del mod estacione_mediicamentos*/
		/**
		*		GetInsumosPendientesPorRecibirCama
		*
		*		Obtiene los insumos por recibir de  una cama especifica
		*		El primer subquery obtiene los medicamentos de los insumos solicitados
		*		El segundo subquery obtiene los insumos solicitados
		*		El tercer subquery obtiene los medicamentos de los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
		*		El cuarto subquery obtiene los medicamentos de los insumos no despchados por bodega
		*		El quinto subquery obtiene los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
		*		El sexto subquery obtiene los insumos no despchados por bodega
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array
		*		@param array => datos de la ubicacion actual: dpto, estacion, empresa, usuario, etc
		*		@param string => id de la cama a buscar
		*/
		function GetInsumosPendientesPorRecibirCama($datos_estacion,$ItemBusqueda)
		{
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.ingreso,
										B.codigo_producto as codigo_producto_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.codigo_producto as codigo_producto_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo,
										I.paciente_id,
										I.tipo_id_paciente,
										I.ingreso,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza
						from
						(
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												M.cod_forma_farmacologica,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												medicamentos M,
												inventarios_productos INV,
												inv_med_cod_forma_farmacologica FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												M.codigo_medicamento = INV.codigo_producto AND
												FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								UNION
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												NULL as forma_farmaceutica,
												NULL as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												inventarios_productos INV,
												hc_insumos_estacion HIE,
												hc_tipos_insumo HTI
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												HIE.codigo_producto = SID.codigo_producto AND
												HIE.insumo_id = HTI.insumo_id AND
												HTI.tipo_insumo = 'I'
						) as B
						LEFT JOIN
						(
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													M.cod_forma_farmacologica,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
							)
						) as A
						ON (B.codigo_producto = A.codigo_producto AND B.solicitud_id = A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = '$ItemBusqueda' AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";

		/*UNION
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
							)*/
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow()){
					$Solicitudes[$data['solicitud_sol']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//GetInsumosPendientesPorRecibirCama
	   /*funcion del mod estacione_mediicamentos*/



		 

   /*funcion del mod estacione_medicamentos*/
	/**
	*		GetInsumosPendientesPorRecibirPaciente
	*
	*		Obtiene todos los medicamentos pendientes por recibir (despachados)
	*		El primer subquery obtiene los medicamentos de los insumos solicitados
	*		El segundo subquery obtiene los insumos solicitados
	*		El tercer subquery obtiene los medicamentos de los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*		El cuarto subquery obtiene los medicamentos de los insumos no despchados por bodega
	*		El quinto subquery obtiene los insumos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*		El sexto subquery obtiene los insumos no despchados por bodega
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool - array
	*		@param array => datos de la ubicacion actual: dpto, estacion, empresa, usuario, etc
	*/
	function GetInsumosPendientesPorRecibirPaciente($datos_estacion,$ItemBusqueda)
	{
		list($paciente_id,$tipo_id_paciente) = explode(".-.",$ItemBusqueda);

		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.ingreso,
										B.codigo_producto as codigo_producto_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.codigo_producto as codigo_producto_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo,
										I.paciente_id,
										I.tipo_id_paciente,
										I.ingreso,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza
						from
						(
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												M.cod_forma_farmacologica,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												medicamentos M,
												inventarios_productos INV,
												inv_med_cod_forma_farmacologica FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												M.codigo_medicamento = INV.codigo_producto AND
												FF.cod_forma_farmacologica = M.cod_forma_farmacologica
								UNION
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SID.consecutivo_d,
												SID.codigo_producto,
												SID.cantidad as cant_solicitada,
												INV.descripcion as nomMedicamento,
												NULL as forma_farmaceutica,
												NULL as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_insumos_d SID,
												inventarios_productos INV,
												hc_insumos_estacion HIE,
												hc_tipos_insumo HTI
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SID.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SID.codigo_producto AND
												HIE.codigo_producto = SID.codigo_producto AND
												HIE.insumo_id = HTI.insumo_id AND
												HTI.tipo_insumo = 'I'
						) as B
						LEFT JOIN
						(
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													M.cod_forma_farmacologica,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
							)
						) as A
						ON (B.codigo_producto = A.codigo_producto AND B.solicitud_id = A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									I.paciente_id = '".$paciente_id."' AND
									I.tipo_id_paciente = '".$tipo_id_paciente."' AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";

		/*UNION
							(		select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SM.ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SID.codigo_producto AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
									UNION
									select
													SM.solicitud_id,
													null as fecha_solicitud,
													SM.ingreso,
													null as bodega,
													null as ingreso,
													SID.consecutivo_d,
													SID.codigo_producto,
													SID.cantidad as cant_solicitada,
													INV.descripcion as nomMedicamento,
													NULL as forma_farmaceutica,
													NULL as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_insumos_d SID,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_ins BDDE,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SID.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SID.consecutivo_d  AND
													INV.codigo_producto = SID.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HIE.codigo_producto = SID.codigo_producto AND
													HTI.tipo_insumo = 'I'
							)*/
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow()){
					$Solicitudes[$data['solicitud_sol']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//GetInsumosPendientesPorRecibirPaciente

/*funcion del mod estacione_medicamentos*/




 //funcion medicamentos
		/*
		*		CallFrmDevolucionMedicamentos()
		*
		*		Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
		*		osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
		*		es mayor a 0
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmDevolucionMedicamentos()
		{
			if(!$this->FrmDevolucionMedicamentos($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['datos_pac']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
				return false;
			}
			return true;
		}
		
		
		 //funcion medicamentos
		/*
		*		CallFrmDevolucionMedicamentos()
		*
		*		Llama la vista que muestra los pacientes que tienen medicamentos que pueden ser devueltos:
		*		osea que los medicamentos del numero de cuenta X con cantidad despachada - cantidad devuelta
		*		es mayor a 0
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallFrmDevolucionInsumos()
		{
			if(!$this->FrmDevolucionInsumos($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['datos_pac']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmDevolucionMedicamentos\"";
				return false;
			}
			return true;
		}
		
		
		
     //funcion medicamentos


	//medicamentos estacionenfermeria
		/**
		*		CallBuscaMedicamentosPorRecibir
		*
		*		Muestra el listado de medicamentos despachados por bodega o pendientes por traer el paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallBuscaMedicamentosPorRecibir()
		{
			$TipoBusqueda = $_REQUEST['TipoBusqueda'];
			$ItemBusqueda = $_REQUEST['ItemBusqueda'];
			$SubmitRecibir = $_REQUEST['SubmitRecibir'];

			if($SubmitRecibir)
			{
				$PedirABodega =	$_REQUEST['PedirABodega'];
				$docsSolicitud = $_REQUEST['docsSolicitud'];
				$VM = $_REQUEST['vectorMedicamentos'];

				//PACIENTE
				$PedirAPaciente =	$_REQUEST['PedirAPaciente'];
				$CantRecibidaPaciente = $_REQUEST['CantRecibida'];

				if($this->AceptarDespacho($PedirABodega,$PedirAPaciente,$VM,$CantRecibidaPaciente,$docsSolicitud))
				{/*esto era cuando no mostraba estos mensajes de informacion
					if($this->BuscaMedicamentosPorRecibir($TipoBusqueda,$ItemBusqueda,$_REQUEST['datos_estacion'])){
						return true;
					}*/
					$mensaje = "TODAS LAS SOLICITUDES SE RECIBIERON CON EXITO";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallBuscaMedicamentosPorRecibir',array("TipoBusqueda"=>$TipoBusqueda,"ItemBusqueda"=>$ItemBusqueda,"datos_estacion"=>$_REQUEST['datos_estacion']));
					$boton = "VOLVER AL LISTADO";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
				else
				{//return false;
					$mensaje = "NO TODAS LAS SOLICITUDES SE RECIBIERON EXITOSAMENTE";
					$titulo = "MENSAJE";
					$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallBuscaMedicamentosPorRecibir',array("TipoBusqueda"=>$TipoBusqueda,"ItemBusqueda"=>$ItemBusqueda,"datos_estacion"=>$_REQUEST['datos_estacion']));
					$boton = "VOLVER AL LISTADO";
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
			}//FIN PRESIONO EL BOTON DE RECIBIR

			if(!$this->BuscaMedicamentosPorRecibir($TipoBusqueda,$ItemBusqueda,$_REQUEST['datos_estacion']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'BuscaMedicamentosPorRecibir'";
				return false;
			}
			return true;
		}///fin CallBuscaMedicamentosPorRecibir
    //medicamentos estacionenfermeria




		//buscar medicamentos por recibir
		/*function CallMedicamentosXRecibir()
		{
		if(!$this->MedicamentosXRecibir($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'BuscaMedicamentosPorRecibir'";
				return false;
			}
			return true;
		}*/

		
			//buscar insumos por recibir
		/*function CallInsumosXRecibir()
		{
			if(!$this->InsumosXRecibir($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'InsumosXrecibir'";
				return false;
			}
			return true;

		}*/
          
          function CallMedicamentosIns_X_Recibir()
		{
			if(!$this->MedicamentosIns_X_Recibir($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'InsumosXrecibir'";
				return false;
			}
			return true;
		}

          /*NUEVA PARTE DE SOLICITUD DE SUMINISTROS X ESTACION*/
          function CallConSuministros_x_estacion()
		{
			if(!$this->ConSuministros_x_estacion($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'ConSuministros_x_estacion'";
				return false;
			}
			return true;
		}
          
          /*NUEVA PARTE DE SOLICITUD DE SUMINISTROS X ESTACION*/
          function CallSolSuministros_x_estacion()
		{
			if(!$this->SolSuministros_x_estacion($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'InsumosXrecibir'";
				return false;
			}
			return true;
		}
          

		//funcion que trae las solicitudes pendientes
		function GetPacientesSolicitudePendientes($Estacion)
		{

               if(empty($Estacion))
			{
				return "ShowMensaje";
			}

			$query = "SELECT DISTINCT MH.fecha_ingreso,
                                        MH.cama,
                                        B.pieza,
                                        C.ingreso,
                                        C.numerodecuenta,
                                        D.paciente_id,
                                        D.tipo_id_paciente,
                                        E.primer_nombre,
                                        E.segundo_nombre,
                                        E.primer_apellido,
                                        E.segundo_apellido,
                                        hc_control_apoyod(C.ingreso)

								FROM movimientos_habitacion AS MH,
                                        (
                                             SELECT  ID.ingreso_dpto_id,
                                                     ID.numerodecuenta

                                             FROM  ingresos_departamento ID,
                                                   estaciones_enfermeria EE
                                             WHERE ID.estado = '1' AND
                                                   EE.estacion_id = ID.estacion_id AND
                                                   EE.estacion_id = '$Estacion'
								) AS A,
                                        camas B,
                                        cuentas C,
                                        ingresos D,
                                        pacientes E,
                                        hc_solicitudes_medicamentos F

								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
                                              MH.fecha_egreso IS NULL AND
                                              MH.cama = B.cama AND
                                              C.numerodecuenta = A.numerodecuenta AND
                                              C.ingreso = D.ingreso AND
                                              C.estado = '1' AND
                                              D.paciente_id = E.paciente_id AND
                                              D.tipo_id_paciente = E.tipo_id_paciente AND
                                              D.ingreso IN(F.ingreso) AND
                                              D.estado='1' AND
                                              F.sw_estado='0'
								ORDER BY MH.cama, B.pieza";

               GLOBAL $ADODB_FETCH_MODE;
               list($dbconn) = GetDBconn();
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $result = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
                         $datoscenso[hospitalizacion][] = $data;
				}
			}

			if(!$datoscenso){
				return "ShowMensaje";
			}
			return $datoscenso;
		}//GetPacientesControles

		
		
		
	/**
	*		GetPacientesPendientesXHospitalizar_Plantilla => Obtiene los pendientes por hospitalizar
	*
	*   Se diferencia de la funcion anterior, en la forma de sacar el vector.
	*		@Author JAJA
	*		@access Public
	*		@return bool-array-string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetPacientesPendientesXHospitalizar_Plantilla($datos_estacion,$spy,$datoscenso,$ingreso)
	{

	  if(!$datos_estacion)
		{
			$datos_estacion=$_REQUEST['datos'];
		}
		if(!empty($ingreso))
		{
			$sql="AND I.ingreso='$ingreso'";
		}
		 $query = "SELECT 	paciente_id,
											tipo_id_paciente,
											primer_apellido,
											segundo_apellido,
											primer_nombre,
											segundo_nombre,
											ingreso,
											ee_destino,
											orden_hosp,
											traslado,
											estacion_origen,
											plan_id,
											numerodecuenta
							FROM pacientes,
									(	SELECT  I.ingreso,
														I.paciente_id as pac_id,
														I.tipo_id_paciente as tipo_id,
														P.estacion_destino as ee_destino,
														P.orden_hospitalizacion_id as orden_hosp,
														P.traslado as traslado,
														P.estacion_origen as estacion_origen,
														x.plan_id,
														x.numerodecuenta
										FROM 	ingresos I,
													cuentas x,
													pendientes_x_hospitalizar P
										WHERE I.ingreso = P.ingreso 
										AND I.ingreso=x.ingreso
										AND x.estado ='1'
										$sql
										AND	P.estacion_destino = '".$datos_estacion[estacion_id]."'
									) as HOLA
							WHERE paciente_id = pac_id AND
										tipo_id_paciente = tipo_id AND
										ee_destino = '".$datos_estacion[estacion_id]."'
							ORDER BY  primer_nombre,
												segundo_nombre,
												primer_apellido,
            segundo_apellido";//pacientes_x_ingreso_x_pxh

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}

			//esta variable esporsi se llama desde otro lado
			if($spy==1)
			{
							if($result ->RecordCount() <=0)
							{
								return $datoscenso;
							}
							while ($data = $result->FetchRow()){
								$datoscenso[hospitalizacion][] = $data;//$i++;
							}
				
							return $datoscenso;
			}
			else
			{
						if($result->EOF)
						{
							return "";
						}
			
						while ($data = $result->FetchRow())
						{
								$datoscenso[ingresar][] = $data;
						}
			
						return $datoscenso;
			}
}			
			
			

		 function GetPaciente_Consulta_Urgencia($Estacion,$datoscenso)
		{
			if($_SESSION['ESTACION_CONTROL']['INGRESO'])
			{
					$ingreso=$_SESSION['ESTACION_CONTROL']['INGRESO'];
					$sql_extra_ur="AND I.ingreso='$ingreso'";
			}

			 $query = "SELECT I.tipo_id_paciente,
											I.paciente_id,
											I.ingreso,
											I.fecha_ingreso,
											P.primer_apellido,
											P.segundo_apellido,
											P.primer_nombre,
											P.segundo_nombre,
											C.numerodecuenta,
											C.plan_id,
											G.plan_descripcion,
											G.tercero_id,
											G.tipo_tercero_id,
											H. nombre_tercero,
											C.rango,
											C.numerodecuenta
								FROM  pacientes P,
											ingresos I,
											cuentas C,
											pacientes_urgencias PU,
											planes G,
											terceros H
								WHERE I.ingreso = C.ingreso AND
											I.estado = '1' AND
											C.estado = '1' AND
											P.paciente_id = I.paciente_id AND
											P.tipo_id_paciente = I.tipo_id_paciente AND
											PU.ingreso = I.ingreso AND
											PU.sw_estado='1' AND
											estacion_id = '$Estacion' AND
											G.plan_id = C.plan_id AND
											H.tercero_id = G.tercero_id AND
											H.tipo_id_tercero = G.tipo_tercero_id
											$sql_extra_ur
								ORDER BY P.primer_nombre,
													P.segundo_nombre,
													P.primer_apellido,
													P.segundo_apellido";
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla 'pacientes_urgencias'<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
			  if($result ->RecordCount() <=0)
				{
					return $datoscenso;
				}
				while ($data = $result->FetchRow()){
					$datoscenso[hospitalizacion][] = $data;//$i++;
				}
			}
			
  		return $datoscenso;
	}



		//funcion que trae las solicitudes pendientes x despachar
		function GetPacientesPendientesDesp($Estacion)
		{

      if(empty($Estacion))
			{
				return "ShowMensaje";
			}

		  $query = "SELECT DISTINCT MH.fecha_ingreso,
												MH.cama,
												B.pieza,
												C.ingreso,
												C.numerodecuenta,
												D.paciente_id,
												D.tipo_id_paciente,
												E.primer_nombre,
												E.segundo_nombre,
												E.primer_apellido,
												E.segundo_apellido,
												hc_control_apoyod(C.ingreso),
												C.plan_id

								FROM movimientos_habitacion AS MH,
										(
											SELECT  ID.ingreso_dpto_id,
															ID.numerodecuenta

											FROM  ingresos_departamento ID,
														estaciones_enfermeria EE
											WHERE ID.estado = '1' AND
														EE.estacion_id = ID.estacion_id AND
														EE.estacion_id = '$Estacion'
										) AS A,
											camas B,
											cuentas C,
											ingresos D,
											pacientes E,
											hc_solicitudes_medicamentos F

								WHERE MH.ingreso_dpto_id = A.ingreso_dpto_id AND
											MH.fecha_egreso IS NULL AND
											MH.cama = B.cama AND
											C.numerodecuenta = A.numerodecuenta AND
											C.ingreso = D.ingreso AND
											C.estado = '1' AND
											D.paciente_id = E.paciente_id AND
											D.tipo_id_paciente = E.tipo_id_paciente AND
											D.ingreso IN(F.ingreso) AND
											D.estado='1' AND
											(F.sw_estado='1' OR F.sw_estado='2')
								ORDER BY MH.cama, B.pieza";
			
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar los pacientes en las estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while ($data = $result->FetchRow())
				{
						$datoscenso[hospitalizacion][] = $data;
				}
			}

			if(!$datoscenso){
				return "ShowMensaje";
			}
  		return $datoscenso;

		}//GetPacientesControles





		//buscar medicamentos por recibir
		/*function CallMedicamentosXDespachar()
		{

			if(!$this->MedicamentosXDespachar($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'BuscaMedicamentosPorRecibir'";
				return false;
			}
			return true;

		}*/

		//buscar Insumos por despachar
		/*function CallInsumosXDespachar()
		{
			if(!$this->InsumosXDespachar($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'BuscaMedicamentosPorRecibir'";
				return false;
			}
			return true;
		}*/

          function CallInsumosMed_X_Despachar()
		{
			if(!$this->InsumosMed_X_Despachar($_REQUEST['datos_estacion'],$_REQUEST['bodega'],$_REQUEST['switche']))
			{
				$this->error = "Error al ejecutar el modulo";
				$this->mensajeDeError = "Ocurrió un error al intentar llamar la funcion 'BuscaMedicamentosPorRecibir'";
				return false;
			}
			return true;
          }



//estacion de enfermeria medicamentos
		/**
		*		AceptarDespacho
		*
		*		Acepta los despachos de medicamentos pendientes por recibir
		*		cambiando solicitudes_medicamentos.estado = 2 (recibido),
		*		bodegas_doc_despacho_medicamentos.estado = 1 => recibido y además ingresa
		*		a bodega_paciente los medicamentos recibidos
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function AceptarDespacho($PedirABodega,$PedirAPaciente,$VM,$CantRecibida,$docsSolicitud)
		{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			if($PedirABodega)
			{
				foreach($docsSolicitud as $key => $value)
				{
					if(in_array($key,$PedirABodega))
					{
						$query = "UPDATE hc_solicitudes_medicamentos
											SET sw_estado = '2'
											WHERE solicitud_id = ".$key."";//$y[0]

						$dbconn->BeginTrans();
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Error al intentar aceptar la solicitud del medicamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							return false;
						}
						else
						{
							//DOCUMENTOS DE CADA SOLICITUD
							foreach ($value as $A => $B)
							{
								$query = "UPDATE bodegas_documentos_hc_solicitudes
													SET estado = '1'
													WHERE solicitud_id = ".$key." AND
																bodegas_doc_id = ".$B."";

								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "Error al intentar aceptar la solicitud del medicamento<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									$dbconn->RollbackTrans();
									return false;
								}
							}//FIN DOCUMENTOS SOLICITUD

							//MEDICAMENTOS DE CADA SOLICITUD
							foreach ($VM[$key] as $keyMed => $valueMed)
							{
								$desp = unserialize(urldecode($valueMed));
								foreach ($desp as $k => $despachado)
								{
									$desp = unserialize(urldecode($despachado));
									$query = "SELECT cantidad_acum
														FROM hc_bodega_paciente
														WHERE ingreso = ".$despachado[ingreso]." AND
																	medicamento_id = '".$despachado[medicamento_id]."'";

									$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
									$result = $dbconn->Execute($query);
									$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar obtener la cantidad de medicamentos en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
										$cantidad = (($result->fields[cantidad_acum]) + $despachado[cantidad]);

										if($result->EOF)
										{
											$query = "INSERT INTO hc_bodega_paciente( ingreso,
																																						medicamento_id,
																																						cantidad_acum)
																																		VALUES (".$despachado[ingreso].",
																																						'".$despachado[medicamento_id]."',
																																						".$despachado[cantidad].")";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar la cantidad de medicamentos en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else{
												$dbconn->CommitTrans();	//$dbconn->RollbackTrans();
											}
										}
										else //ya existe el medicamento en la bodega del paciente
										{
											$query = "UPDATE hc_bodega_paciente
																SET cantidad_acum = ".$cantidad."
																WHERE ingreso = ".$despachado[ingreso]." AND
																medicamento_id = '".$despachado[medicamento_id]."'";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar la cantidad de medicamentos en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else{
												$dbconn->CommitTrans();//$dbconn->RollbackTrans();
											}
										}//ya existe el medicamento en la bodega del paciente
									}//ejecuto el select cantidad_acum
								}
							}//FIN FOREACH MEDICAMENTOS
						}//realizó el update de solicitud medicamentos
					}//FIN for ($i=0; $i<sizeof($PedirABodega); $i++) => CADA CHECK SELECCIONAD
				}//fin foreach
			}//FIN PEDIR A BODEGA

			if($PedirAPaciente)
			{

				/*
				[consecutivo]				[medicamento_id]				[evolucion_id]				[cant_solicitada]
				[fecha_solicitud]		[ingreso]								[numerodecuenta]			[cama]
				[pieza]							[paciente_id]						[tipo_id_paciente]		[primer_nombre]
				[segundo_nombre]		[primer_apellido]				[segundo_apellido]		[forma_farmaceutica_id]
				[descripcion]				[nombre]								[documento]						[despachada]
				print_r($PedirAPaciente);
				exit;
				*/
				for($i=0; $i<sizeof($PedirAPaciente); $i++)
				{
					$K = explode(".-.",$PedirAPaciente[$i]);
					//$K = [consecutivo], [medicamento_id], [ingreso],   $i ,[mezcla_recetada_id]

							$query = "SELECT cantidad_acum
												FROM hc_bodega_paciente
												WHERE ingreso = ".$K[2]." AND
															medicamento_id = '".$K[1]."'";

							$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
							$dbconn->BeginTrans();
							$result = $dbconn->Execute($query);
							$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar obtener la cantidad de medicamentos en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								return false;
							}
							else
							{
								if($result->EOF)
								{
									$query = "INSERT INTO hc_bodega_paciente( ingreso,
																																				medicamento_id,
																																				cantidad_acum)
																																VALUES (".$K[2].",
																																				'".$K[1]."',
																																				".$CantRecibida[$K[3]]."
																																				)";

									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar actualizar la cantidad de medicamentos en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
										if(empty($K[4]))//mezcla
										{
											$query = "UPDATE hc_solicitudes_medicamentos_pacientes
																SET sw_estado = '1'
																WHERE consecutivo = ".$K[0]."";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar el estado de la solicitud del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else{
												$dbconn->CommitTrans();
											}
										}
										else//es una mezlca
										{
											$query = "UPDATE hc_solicitudes_mezclas_pacientes
																SET sw_estado = '1'
																WHERE consecutivo = ".$K[0]."";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar el estado de la solicitud del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else{
												$dbconn->CommitTrans();
											}
										}
									}
								}
								else //ya existe el medicamento en la bodega del paciente
								{
									$cantidad = (($result->fields[cantidad_acum]) + $CantRecibida[$K[3]]);
									$query = "UPDATE hc_bodega_paciente
														SET cantidad_acum = ".$cantidad."
														WHERE ingreso = ".$K[2]." AND
														medicamento_id = '".$K[1]."'";

									$result = $dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->error = "Error al ejecutar la conexion";
										$this->mensajeDeError = "Error al intentar actualizar la cantidad de medicamentos en la bodega del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
										if(empty($K[4]))//mezcla
										{
											$query = "UPDATE hc_solicitudes_medicamentos_pacientes
																SET sw_estado = '1'
																WHERE consecutivo = ".$K[0]."";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar el estado de la solicitud del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else{
												$dbconn->CommitTrans();
											}
										}
										else//es una mezlca
										{
											$query = "UPDATE hc_solicitudes_mezclas_pacientes
																SET sw_estado = '1'
																WHERE consecutivo = ".$K[0]."";

											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0)
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Error al intentar actualizar el estado de la solicitud del paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$dbconn->RollbackTrans();
												return false;
											}
											else{
												$dbconn->CommitTrans();
											}
										}
									}
								}//ya existe el medicamento en la bodega del paciente
							}//else => obtuvo ejecutó el  select que selecciona la cant_acum
						}
			}//FIN PEDIR A PACIENTE
			return true;
		}//FIN InsertBodegaPaciente()



//funcion de estacion de enfermeria
   /*funcion del mod estacione_medicmanetos*/
	/**
	*		GetCamasOcupadasEstacion
	*
	*		Obtiene los datos de todas las camas ocupadas de la estacion
	*		llamada en buscaMedicamentosPendientesPorRecibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return bool-array
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetCamasOcupadasEstacion($datos_estacion)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT 	P.pieza,
											P.descripcion as desc_pieza,
											C.cama,
											C.estado
							FROM piezas P, camas C
							WHERE P.pieza = C.pieza AND
										C.estado = '0' AND
										P.estacion_id = '".$datos_estacion[estacion_id]."'
							ORDER BY P.pieza, C.cama";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{ 
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar obtener las camas ocupadas de la estación.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "No se pudo obtener los datos de las piezas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				$k=0;
				while (!$result->EOF)//while ($data = $result->FetchNextObject())
				{
					$datosCamas[$k] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$result->MoveNext();
					$k++;
				}
			}
		}
		return $datosCamas;
	}//fin



	 //funcion de estacion de enfermeria medicamentos
	/**
	*		GetMedicamentosPendientesPorRecibirCama
	*
	*		Obtiene los medicamentos por recibir de la cama seleccionada
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*		@param string => id de la cama a buscar
	*/
	function GetMedicamentosPendientesPorRecibirCama($datos_estacion,$ItemBusqueda)
	{
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.medicamento_id as medicamento_id_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										B.ingreso,
										A.evolucion_id,
										I.paciente_id,
										I.tipo_id_paciente,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.medicamento_id as medicamento_id_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo
						from
						(
								select
												SM.fecha_solicitud,
												SM.solicitud_id,
												SM.ingreso,
												SMD.consecutivo_d,
												SMD.medicamento_id,
												SMD.cant_solicitada,
												M.cod_forma_farmacologica,
												INV.descripcion as nomMedicamento,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_medicamentos_d SMD,
												medicamentos M,
												inventarios_productos INV,
												inv_med_cod_forma_farmacologica FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												SMD.solicitud_id = SM.solicitud_id AND
												INV.codigo_producto = SMD.medicamento_id AND
												INV.codigo_producto = M.codigo_medicamento AND
												FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as B
						left join
						(
									select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SMD.consecutivo_d,
													SMD.evolucion_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = M.codigo_medicamento AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION

									select
													SM.solicitud_id,
													null as fecha_solicitud,
													null as ingreso,
													null as bodega,
													null as evolucion_id,
													SMD.consecutivo_d,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_med BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SMD.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													INV.codigo_producto = M.codigo_medicamento AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as A
						on (B.medicamento_id=A.medicamento_id AND B.solicitud_id=A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = '$ItemBusqueda' AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			while ($data = $result->FetchRow()){
				$Solicitudes[$data['solicitud_sol']][] = $data;
			}
			return $Solicitudes;
		}
	}//GetMedicamentosPendientesPorRecibirCama


  //funcion de estacion de enfermeria medicamentos
	/**
	*		GetMedicamentosPendientesPorRecibirCama
	*
	*		Obtiene los medicamentos solicitados al paciente y que están pendientes por recibir de la cama seleccionada
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*		@param string => cama a buscar
	*/
	function GetMedicamentosPendientesPorRecibirCamaPaciente($datos_estacion,$ItemBusqueda)
	{
		$qMedPac= "SELECT  J.*,
											C.numerodecuenta,
											CM.cama,
											CM.pieza,
											I.paciente_id,
											I.tipo_id_paciente,
											P.primer_nombre,
											P.segundo_nombre,
											P.primer_apellido,
											P.segundo_apellido,
											M.cod_forma_farmacologica,
											INV.descripcion,
											FF.descripcion as nombre
							FROM  (
											SELECT  SMP.consecutivo,
															NULL as mezcla_recetada_id,
															SMP.medicamento_id,
															SMP.evolucion_id,
															SMP.cant_solicitada,
															SMP.fecha_solicitud,
															SMP.ingreso
											FROM hc_solicitudes_medicamentos_pacientes SMP,
														hc_medicamentos_recetados MR
											WHERE SMP.sw_estado = '0' AND
														MR.sw_estado = '2' AND
														MR.medicamento_id = SMP.medicamento_id
											UNION
											SELECT  SMP.consecutivo,
															SMP.mezcla_recetada_id,
															SMP.medicamento_id,
															SMP.evolucion_id,
															SMP.cant_solicitada,
															SMP.fecha_solicitud,
															SMP.ingreso
											FROM hc_solicitudes_mezclas_pacientes SMP,
													 hc_mezclas_recetadas MR,
													 hc_mezclas_recetadas_medicamentos MRM
											WHERE SMP.sw_estado = '0' AND
														MR.sw_estado = '2' AND
														MR.mezcla_recetada_id = SMP.mezcla_recetada_id AND
														MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
														MRM.medicamento_id = SMP.medicamento_id
										) AS J,
										cuentas C,
										movimientos_habitacion MH,
										camas CM,
										ingresos I,
										pacientes P,
										medicamentos M,
										inventarios_productos INV,
										inv_med_cod_forma_farmacologica FF
							WHERE C.ingreso = J.ingreso AND
										MH.numerodecuenta = C.numerodecuenta AND
										MH.fecha_egreso IS NULL AND
										MH.cama = '$ItemBusqueda' AND
										CM.cama = MH.cama AND
										I.ingreso = J.ingreso AND
										P.paciente_id = I.paciente_id AND
										P.tipo_id_paciente = I.tipo_id_paciente AND
										INV.codigo_producto = J.medicamento_id AND
										INV.codigo_producto = M.codigo_medicamento AND
										C.empresa_id = '".$datos_estacion['empresa_id']."' AND
										C.centro_utilidad  = '".$datos_estacion['centro_utilidad']."' AND
										FF.cod_forma_farmacologica = M.cod_forma_farmacologica
							ORDER BY J.consecutivo";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($qMedPac);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$qMedPac;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			$i=0;
			while (!$result->EOF)//while ($data = $result->FetchNextObject())
			{
				$Paciente[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$i++;
				$result->MoveNext();
			}
			return $Paciente;
		}
	}//GetMedicamentosPendientesPorRecibirCamaPaciente


 //funcion de estacion de enfermeria medicamentos
	/**
	*		GetMedicamentosMezclasPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos de mezclas de la cama especificada
	*		que ya fueron solicitados a bodega y despachados por la misma y que estan pendientes por recibir
	*		El primer subquery obtiene los medicamentos solicitados
	*		El tercer subquery obtiene los medicamentos no despchados por bodega
	*		El segundo subquery obtiene los medicamentos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*		@param string => id de la cama a buscar
	*/
	function GetMedicamentosMezclasPendientesPorRecibirCama($datos_estacion,$ItemBusqueda)
	{
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.mezcla_recetada_id,
										B.medicamento_id as medicamento_id_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										B.ingreso,
										A.evolucion_id,
										I.paciente_id,
										I.tipo_id_paciente,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.medicamento_id as medicamento_id_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo
						from
						(
								SELECT
												SM.fecha_solicitud,
												SM.solicitud_id,
												SM.ingreso,
												SMD.consecutivo_d,
												SMD.mezcla_recetada_id,
												SMD.medicamento_id,
												SMD.cant_solicitada,
												M.cod_forma_farmacologica,
												INV.descripcion as nomMedicamento,
												FF.descripcion as FF
								FROM  hc_solicitudes_medicamentos SM,
											hc_solicitudes_medicamentos_mezclas_d SMD,
											medicamentos M,
											inventarios_productos INV,
											inv_med_cod_forma_farmacologica FF
								WHERE SM.sw_estado='1' AND
											SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
											SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
											SMD.solicitud_id = SM.solicitud_id AND
											INV.codigo_producto = SMD.medicamento_id AND
											INV.codigo_producto = M.codigo_medicamento AND
											FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as B
						left join
						(
									select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SMD.consecutivo_d,
													SMD.evolucion_id,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_mezclas_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = M.codigo_medicamento AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION

									select
													SM.solicitud_id,
													null as fecha_solicitud,
													null as ingreso,
													null as bodega,
													null as evolucion_id,
													SMD.consecutivo_d,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_mezclas_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_mez BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.empresa_id = SM.empresa_id AND
													BDD.centro_utilidad = SM.centro_utilidad AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SMD.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													INV.codigo_producto = M.codigo_medicamento AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as A
						on (B.medicamento_id=A.medicamento_id AND B.solicitud_id=A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = '$ItemBusqueda' AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id,B.mezcla_recetada_id,B.medicamento_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow())
				{
					$ContMezclas[$data['mezcla_recetada_id']]++;
					$Solicitudes[$data['solicitud_sol']][$data['mezcla_recetada_id']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//fin GetMedicamentosMezclasPendientesPorRecibirCama


		 //funcion de medicamento estacione_medicamentos
	/**
	*		GetMedicamentosPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos que ya fueron solicitados a bodega
	*		y despachados por la misma y que estan pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesPorRecibir($datos_estacion)
	{
    if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}

	/*
		El primer subquery obtiene los medicamentos solicitados
		El tercer subquery obtiene los medicamentos no despchados por bodega
		El segundo subquery obtiene los medicamentos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
		*/
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.medicamento_id as medicamento_id_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										B.ingreso,
										A.evolucion_id,
										I.paciente_id,
										I.tipo_id_paciente,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.medicamento_id as medicamento_id_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo
						from
						(
								select
												SM.solicitud_id,
												SM.fecha_solicitud,
												SM.ingreso,
												SMD.consecutivo_d,
												SMD.medicamento_id,
												SMD.cant_solicitada,
												M.cod_forma_farmacologica,
												INV.descripcion as nomMedicamento,
												FF.descripcion as FF
								from
												hc_solicitudes_medicamentos SM,
												hc_solicitudes_medicamentos_d SMD,
												medicamentos M,
												inventarios_productos INV,
												inv_med_cod_forma_farmacologica FF
								where
												SM.sw_estado = '1' AND
												SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
												SMD.solicitud_id=SM.solicitud_id AND
												SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
												SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
												INV.codigo_producto = SMD.medicamento_id AND
												INV.codigo_producto = M.codigo_medicamento AND
												FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as B
						left join
						(
									select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SMD.consecutivo_d,
													SMD.evolucion_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = SMD.medicamento_id AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION

									select
													SM.solicitud_id,
													null as fecha_solicitud,
													null as ingreso,
													null as bodega,
													null as evolucion_id,
													SMD.consecutivo_d,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_med BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SMD.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as A
						on (B.medicamento_id = A.medicamento_id AND B.solicitud_id = A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow()){
					$Solicitudes[$data['solicitud_sol']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//fin GetMedicamentosPendientesPorRecibir
 //funcion de medicamento estacione_medicamentos


	//funcion de estacion de enfremeriae_medicamentos
		/**
		*		CallFrmSolicitarInsumosPaciente
		*
		*		Llama a la forma en la que se solicitan insumos para un paciente especifico
		*
		*		@Author Arley Velásquez Castillo
		*		@access Public
		*		@return bool
		*/
		function CallFrmSolicitarInsumosPaciente()
		{
			if(!$this->FrmSolicitarInsumosPaciente($_REQUEST['estacion'],$_REQUEST['ingreso']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmSolicitarInsumosPaciente\"";
				return false;
			}
			return true;
		}

 //funcion de estacion de enfermeria control pacientes
		/**
		*		GetDatosClavePaciente
		*
		*		Con el ingreso del paciente obtengo los datos personales, la cama, el numero de cuenta
		*		el numero de ingreso al depto, entre otros.
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool - array - string
		*/
		function GetDatosClavePaciente($ingreso)
		{
			$query = "SELECT
											PAC.primer_apellido,
											PAC.segundo_apellido,
											PAC.segundo_nombre,
											PAC.primer_nombre,
											PAC.paciente_id,
											PAC.tipo_id_paciente,
											INGS.ingreso,
											CTS.numerodecuenta,
											MH.ingreso_dpto_id,
											CAMA.cama,
											PIEZA.pieza
								FROM  movimientos_habitacion MH,
											cuentas CTS,
											camas CAMA,
											piezas PIEZA,
											ingresos INGS,
											pacientes PAC
								WHERE	INGS.ingreso = ".$ingreso." AND
											INGS.estado='1' AND
											PAC.paciente_id = INGS.paciente_id AND
											PAC.tipo_id_paciente = INGS.tipo_id_paciente AND
											CTS.ingreso = INGS.ingreso AND
											CTS.estado='1' AND
											MH.numerodecuenta = CTS.numerodecuenta AND
											MH.fecha_egreso IS NULL AND
											MH.fecha_ingreso IS NOT NULL AND
											MH.cama = CAMA.cama AND
											CAMA.pieza = PIEZA.pieza";

			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener datos del paciente.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				while (!$result->EOF){
					$datosPaciente = $result->FetchRow();
				}
				return $datosPaciente;
			}
		}//GetDatosClavePaciente


		/**
		*  CancelarSolicitudesInsumos
		*
		*		Pone en estado cancelado una solicitud que no ha sido despachada
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CancelarSolicitudesInsumos()
		{
			$datos_estacion = $_REQUEST['datos_estacion'];
			$CancelarSolicitudInsumo = $_REQUEST['CancelarSolicitudInsumo'];

			if(!$CancelarSolicitudInsumo)
			{
				$mensaje = "DEBE SELECCIONAR AL MENOS UN INSUMO";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmSolicitarInsumosPaciente',array("estacion"=>$datos_estacion,"ingreso"=>$_REQUEST['ingreso']));
				$boton = "REGRESAR";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{
				for($i=0; $i<sizeof($CancelarSolicitudInsumo); $i++)
				{
					$query = "UPDATE hc_solicitudes_medicamentos
										SET sw_estado = '3'
										WHERE solicitud_id = ".$CancelarSolicitudInsumo[$i]."";

					list($dbconn) = GetDBconn();
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar eliminar la solicitud de insumos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
				}
			}
			if(!$this->FrmSolicitarInsumosPaciente($datos_estacion,$_REQUEST['ingreso']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmSolicitarInsumosPaciente\"";
				return false;
			}
			return true;
		}//CancelarSolicitudesInsumos



		//funcion de estacion de enfermeriae_medicamentos
		/**
		*		GetInsumosSolicitadosbodega($ingreso)
		*
		*		Obtiene las solicitudes de insumos de un ingreso X que han sido solicitados a bodega
		*		y que aun no se han recibido con estado en 0->sin depacho o 1->despachado
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@param integer => numero de ingreso del paciente
		*		@param array => datos de la ubicacion actual: dpto, estacion, empresa, usuario, etc
		*		@return boll-string-array
		*/
		function GetInsumosSolicitadosbodega($Ingreso,$datos_estacion)
		{
			$query = "SELECT J.*,
												SM.fecha_solicitud,
												SM.sw_estado
								FROM hc_solicitudes_medicamentos SM,
										(SELECT SMD.solicitud_id,
														SMD.consecutivo_d,
														NULL as mezcla_recetada_id,
														SMD.codigo_producto,
														SMD.cantidad,
														M.cod_forma_farmacologica,
														INV.descripcion as nomMedicamento,
														FF.descripcion as FF
										FROM	hc_solicitudes_insumos_d SMD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
										WHERE INV.codigo_producto = SMD.codigo_producto AND
													M.codigo_medicamento = INV.codigo_producto AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
										UNION
										SELECT SMD.solicitud_id,
														SMD.consecutivo_d,
														NULL as mezcla_recetada_id,
														SMD.codigo_producto,
														SMD.cantidad,
														NULL as cod_forma_farmacologica,
														INV.descripcion as nomMedicamento,
														NULL as FF
										FROM	hc_solicitudes_insumos_d SMD,
													inventarios_productos INV,
													hc_insumos_estacion HIE,
													hc_tipos_insumo HTI
										WHERE inv.codigo_producto = SMD.codigo_producto AND
													HIE.codigo_producto = SMD.codigo_producto AND
													HIE.insumo_id = HTI.insumo_id AND
													HTI.tipo_insumo = 'I'
										) AS J
								WHERE (SM.sw_estado != 2) AND
											SM.solicitud_id = J.solicitud_id AND
											SM.ingreso = $Ingreso AND
											SM.empresa_id  = '".$datos_estacion['empresa_id']."' AND
											SM.centro_utilidad  = '".$datos_estacion['centro_utilidad']."'
								ORDER BY J.solicitud_id DESC";


			list($dbconn) = GetDBconn();
			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{ 
				$this->error = "Atención";
				$this->mensajeDeError = "Ocurrió un error al intentar obtener las solicitudes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				$k = 0;
				while (!$result->EOF)
				{
					$datos[$k] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
					$result->MoveNext();
					$k++;
				}
				return $datos;
			}
		}//fin  GetInsumosSolicitadosbodega($ingreso)


		//<DEASUACIADA>
	/*
	*		GetMedicamentosPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos, mezclas y medicamentos de estas
	*		que ya fueron solicitados al paciente y que estan pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosPendientesPorRecibirPaciente($datos_estacion)
	{
		if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}
		$qMedPac= "SELECT J.*,
											C.numerodecuenta,
											CM.cama,
											CM.pieza,
											I.paciente_id,
											I.tipo_id_paciente,
											P.primer_nombre,
											P.segundo_nombre,
											P.primer_apellido,
											P.segundo_apellido,
											M.cod_forma_farmacologica,
											INV.descripcion,
											FF.descripcion as nombre
							FROM  (
											SELECT  SMP.consecutivo,
															NULL as mezcla_recetada_id,
															SMP.medicamento_id,
															SMP.evolucion_id,
															SMP.cant_solicitada,
															SMP.fecha_solicitud,
															SMP.ingreso
											FROM hc_solicitudes_medicamentos_pacientes SMP,
														hc_medicamentos_recetados MR
											WHERE SMP.sw_estado = '0' AND
														MR.sw_estado = '2' AND
														MR.medicamento_id = SMP.medicamento_id
											UNION
											SELECT  SMP.consecutivo,
															SMP.mezcla_recetada_id,
															SMP.medicamento_id,
															SMP.evolucion_id,
															SMP.cant_solicitada,
															SMP.fecha_solicitud,
															SMP.ingreso
											FROM hc_solicitudes_mezclas_pacientes SMP,
													 hc_mezclas_recetadas MR,
													 hc_mezclas_recetadas_medicamentos MRM
											WHERE SMP.sw_estado = '0' AND
														MR.sw_estado = '2' AND
														MR.mezcla_recetada_id = SMP.mezcla_recetada_id AND
														MRM.mezcla_recetada_id = MR.mezcla_recetada_id AND
														MRM.medicamento_id = SMP.medicamento_id
										) AS J,
										cuentas C,
										movimientos_habitacion MH,
										camas CM,
										piezas PZ,
										ingresos I,
										pacientes P,
										medicamentos M,
										inventarios_productos INV,
										inv_med_cod_forma_farmacologica FF
							WHERE C.ingreso = J.ingreso AND
										MH.numerodecuenta = C.numerodecuenta AND
										MH.fecha_egreso IS NULL AND
										CM.cama = MH.cama AND
										PZ.pieza = CM.pieza AND
										PZ.estacion_id = '".$datos_estacion['estacion_id']."' AND
										I.ingreso = J.ingreso AND
										P.paciente_id = I.paciente_id AND
										P.tipo_id_paciente = I.tipo_id_paciente AND
										INV.codigo_producto = J.medicamento_id AND
										INV.codigo_producto = M.codigo_medicamento AND
										C.empresa_id = '".$datos_estacion['empresa_id']."' AND
										C.centro_utilidad  = '".$datos_estacion['centro_utilidad']."' AND
										FF.cod_forma_farmacologica = M.cod_forma_farmacologica
							ORDER BY J.consecutivo
							";


		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($qMedPac);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$qMedPac;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			$i=0;
			while (!$result->EOF)//while ($data = $result->FetchNextObject())
			{

				$Paciente[$i] = $result->GetRowAssoc($ToUpper = false);//mi primer GetRow
				$i++;
				$result->MoveNext();
			}
			return $Paciente;
		}
	}//GetMedicamentosPendientesPorRecibir


	 //medicamentos estacione_medicamentos
	/**
	*		GetMedicamentosMezclasPendientesPorRecibir
	*
	*		obtiene los pacientes y sus respectivos medicamentos de mezclas
	*		que ya fueron solicitados a bodega y que estan pendientes por recibir
	*
	*		@Author Rosa Maria Angel
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function GetMedicamentosMezclasPendientesPorRecibir($datos_estacion)
	{
    if(!$datos_estacion){$datos_estacion=$_REQUEST['datos'];}

	/*
		El primer subquery obtiene los medicamentos solicitados
		El tercer subquery obtiene los medicamentos no despchados por bodega
		El segundo subquery obtiene los medicamentos efectivamente despachados por bodega y su equivalente al solicitado en el caso que aplique
		*/
		$query="select  B.solicitud_id as solicitud_sol,
										B.fecha_solicitud as fecha_sol,
										B.mezcla_recetada_id,
										B.medicamento_id as medicamento_id_sol,
										B.cant_solicitada as cant_solicitada_sol,
										B.cod_forma_farmacologica as forma_farmaceutica_sol,
										B.nomMedicamento as nomMedicamento_sol,
										B.FF as FF_sol,
										B.ingreso,
										A.evolucion_id,
										I.paciente_id,
										I.tipo_id_paciente,
										P.primer_nombre,
										P.segundo_nombre,
										P.primer_apellido,
										P.segundo_apellido,
										CM.cama,
										PZ.pieza,
										A.solicitud_id as solicitud_id_des,
										A.fecha_solicitud as fecha_solicitud_des,
										A.bodega,
										A.medicamento_id as medicamento_id_des,
										A.cod_forma_farmacologica as forma_farmaceutica_des,
										A.nomMedicamento as nomMedicamento_des,
										A.FF as FF_des,
										A.bodegas_doc_id as documento_des,
										A.cant_enviada,
										A.reemplazo
						from
						(
								SELECT
												SM.fecha_solicitud,
												SM.solicitud_id,
												SM.ingreso,
												SMD.consecutivo_d,
												SMD.mezcla_recetada_id,
												SMD.medicamento_id,
												SMD.cant_solicitada,
												M.cod_forma_farmacologica,
												INV.descripcion as nomMedicamento,
												FF.descripcion as FF
								FROM  hc_solicitudes_medicamentos SM,
											hc_solicitudes_medicamentos_mezclas_d SMD,
											medicamentos M,
											inventarios_productos INV,
											inv_med_cod_forma_farmacologica FF
								WHERE SM.sw_estado='1' AND
											SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
											SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
											SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
											SMD.solicitud_id = SM.solicitud_id AND
											INV.codigo_producto = SMD.medicamento_id AND
											INV.codigo_producto = M.codigo_medicamento AND
											FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as B
						left join
						(
									select
													SM.solicitud_id,
													SM.fecha_solicitud,
													SM.ingreso,
													SM.bodega,
													SMD.consecutivo_d,
													SMD.evolucion_id,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													null as reemplazo
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_mezclas_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = SMD.medicamento_id AND
													INV.codigo_producto = M.codigo_medicamento AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
									UNION

									select
													SM.solicitud_id,
													null as fecha_solicitud,
													null as ingreso,
													null as bodega,
													null as evolucion_id,
													SMD.consecutivo_d,
													SMD.mezcla_recetada_id,
													SMD.medicamento_id,
													SMD.cant_solicitada,
													M.cod_forma_farmacologica,
													INV.descripcion as nomMedicamento,
													FF.descripcion as FF,
													BDHS.bodegas_doc_id,
													BDD.cantidad as cant_enviada,
													BDD.codigo_producto
									from
													hc_solicitudes_medicamentos SM,
													hc_solicitudes_medicamentos_mezclas_d SMD,
													bodegas_documentos_hc_solicitudes BDHS,
													bodegas_documentos_d BDD,
													bodegas_documentos_d_equiv_mez BDDE,
													medicamentos M,
													inventarios_productos INV,
													inv_med_cod_forma_farmacologica FF
									where
													SM.sw_estado = '1' AND
													SM.estacion_id = '".$datos_estacion[estacion_id]."' AND
													SM.empresa_id = '".$datos_estacion[empresa_id]."' AND
													SM.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													SMD.solicitud_id = SM.solicitud_id AND
													BDHS.estado = '0' AND
													BDHS.solicitud_id = SM.solicitud_id AND
													BDD.bodegas_doc_id = BDHS.bodegas_doc_id AND BDD.numeracion=BDHS.numeracion AND
													BDD.empresa_id = '".$datos_estacion[empresa_id]."' AND
													BDD.centro_utilidad = '".$datos_estacion[centro_utilidad]."' AND
													BDDE.consecutivo = BDD.consecutivo AND
													BDDE.consecutivo_d = SMD.consecutivo_d  AND
													INV.codigo_producto = BDD.codigo_producto AND
													INV.codigo_producto = M.codigo_medicamento AND
													FF.cod_forma_farmacologica = M.cod_forma_farmacologica
						) as A
						ON (B.medicamento_id=A.medicamento_id AND B.solicitud_id=A.solicitud_id),
								ingresos I,
								cuentas C,
								movimientos_habitacion MH,
								camas CM,
								piezas PZ,
								pacientes P
						WHERE I.ingreso = B.ingreso AND
									C.ingreso = I.ingreso AND
									MH.numerodecuenta = C.numerodecuenta AND
									MH.fecha_egreso IS NULL AND
									CM.cama = MH.cama AND
									CM.pieza = PZ.pieza AND
									Pz.estacion_id = '".$datos_estacion[estacion_id]."' AND
									P.paciente_id = I.paciente_id AND
									P.tipo_id_paciente = I.tipo_id_paciente
						ORDER BY B.solicitud_id,B.mezcla_recetada_id,B.medicamento_id";

		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Ocurrió un error al intentar consultar las solicitudes pendientes de medicamentos.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			if($result->EOF){
				return "ShowMensaje";
			}
			else
			{
				while ($data = $result->FetchRow())
				{
					$ContMezclas[$data['mezcla_recetada_id']]++;
					$Solicitudes[$data['solicitud_sol']][$data['mezcla_recetada_id']][] = $data;
				}
				return $Solicitudes;
			}
		}
	}//fin GetMedicamentosMezclasPendientesPorRecibir


		 //funcion de estacione_medicamentos
		/**
		*		SolicitarInsumosPaciente
		*
		*		Realiza una solicitud de insumos a la bodega
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function SolicitarInsumosPaciente()
		{
			$suministro = $_REQUEST['Suministro'];
			$Cantidad = $_REQUEST['Cantidad'];
			$datos_estacion = $_REQUEST['datos_estacion'];
			$ingreso = $_REQUEST['ingreso'];

			//PRIMERO VALIDO QUE LLEGEN LAS CANTIDADES
			foreach($suministro as $key => $value){
				if(empty($Cantidad[$key])){
					$mensajedevolver = 1;
				}
			}
			if($mensajedevolver)
			{
				$this->frmError["MensajeError"] = "DEBE ESCRIBIR LAS CANTIDADES DE LOS MEDICAMENTOS SELECCIONADOS";
				if(!$this->FrmSolicitarInsumosPaciente($datos_estacion,$ingreso))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmSolicitarInsumosPaciente\"";
					return false;
				}
				return true;
			}

			if(!is_array($suministro))
			{
				$this->frmError["MensajeError"] = "DEBE SELECCIONAR LOS MEDICAMENTOS A SOLICITAR";
				if(!$this->FrmSolicitarInsumosPaciente($datos_estacion,$ingreso))
				{
					$this->error = "No se puede cargar la vista";
					$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmSolicitarInsumosPaciente\"";
					return false;
				}
				return true;
			}

			$bodegas = $this->CallMetodoExterno('app', 'EstacionEnfermeria', 'admin', 'Bodegas', array(0=>$datos_estacion['empresa_id'],1=>$datos_estacion['centro_utilidad'],2=>$datos_estacion['estacion_id']));

			$query = "SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq') AS consecutivo;";

			GLOBAL $ADODB_FETCH_MODE;
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$puedoHacerCommit = array();
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar asignar el numero de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				if(!$result->EOF)
				{
					$consecutivo = $result->fields['consecutivo'];
					$query = "INSERT INTO hc_solicitudes_medicamentos
																										(
																										solicitud_id,
																										ingreso,
																										bodega,
																										empresa_id,
																										centro_utilidad,
																										usuario_id,
																										sw_estado,
																										fecha_solicitud,
																										estacion_id,
																										tipo_solicitud
																									)
																						VALUES
																									(
																										".$consecutivo.",
																										".$ingreso.",
																										'".$bodegas[0]['bodega']."',
																										'".$datos_estacion['empresa_id']."',
																										'".$datos_estacion['centro_utilidad']."',
																										".UserGetUID().",
																										'0',
																										'".date("Y-m-d h:i:s")."',
																										'".$datos_estacion['estacion_id']."',
																										'I');";

					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Ocurrió un error al intentar generar el maestro de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						$dbconn->RollbackTrans();
						$puedoHacerCommit[] = 0;
						return false;
					}
					else
					{
						//por cada medicamento se realiza un detalle
						foreach($suministro as $key => $value)
						{
							list($producto,$insumo_id,$tipo_insumo) = explode(".-.",$value);
							$query = "INSERT INTO hc_solicitudes_insumos_d
																												(
																													insumo_id,
																													codigo_producto,
																													estacion_id,
																													cantidad,
																													solicitud_id
																												)
																									VALUES
																												(
																													$insumo_id,
																													'$producto',
																													'".$datos_estacion['estacion_id']."',
																													".$Cantidad[$key].",
																													".$consecutivo.");";

							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Ocurrió un error al intentar ingresar el detalle de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								$puedoHacerCommit[] = 0;
								return false;
							}
							else{
								$puedoHacerCommit[] = 1;
							}
						}//fin foreach($value as $valKey=>$detalle) osea el detalle de solicitud

						if(!in_array(0,$puedoHacerCommit))
						{
							$dbconn->CommitTrans();
							/*$this->FrmSolicitarInsumosPaciente($datos_estacion,$ingreso);
							return true;*/
							$mensaje = "TODAS LAS SOLICITUDES SE REALIZARON CON EXITO";
							$titulo = "MENSAJE";
							$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmSolicitarInsumosPaciente',array("estacion"=>$datos_estacion,"ingreso"=>$ingreso));
							$boton = "VOLVER AL LISTADO";
							$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
							return true;
						}
						else
						{
							/*$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar realizar el proceso de solicitud de insumos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							return false;*/
							$mensaje = "NO TODAS LAS SOLICITUDES SE REALIZARON EXITOSAMENTE, DEBE REVISAR LAS SOLICITUDES REALIZADAS";
							$titulo = "MENSAJE";
							$accion = ModuloGetURL('app','EstacionEnfermeria','user','CallFrmSolicitarInsumosPaciente',array("estacion"=>$datos_estacion,"ingreso"=>$ingreso));
							$boton = "VOLVER AL LISTADO";
							$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
							return true;
						}
					}//fin else osea que insertó el maestro
				}//no seleccionó nextval
			}//ejecuto correctamente la seleccion del siguiente consecutivo
		}//Fin SolicitarInsumosPaciente


		/**
		*		ConsultarEInsertarMedicamento
		*
		*		Consulta si el medicamento existe en la tabla existencias_bodegas de lo contrario
		*		se crea el registro con todo en 0, esto ees para poder hacer la solicitud del medicamento
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function ConsultarEInsertarMedicamento($medicamento,$bodega,$datos_estacion)
		{
			$qExiste = "SELECT codigo_producto
									FROM existencias_bodegas
									WHERE empresa_id = '".$datos_estacion['empresa_id']."' AND
												centro_utilidad = '".$datos_estacion['centro_utilidad']."' AND
												codigo_producto = '".$medicamento."' AND
												bodega = '".$bodega."'";

			list($dbconn) = GetDBconn();
			$resultExiste = $dbconn->Execute($qExiste);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "ocurrió un error al intentar obtener el codigo del producto<br><br>".$dbconn->ErrorMsg()."<br><br>".$qExiste;
				return false;
			}
			else
			{
				if($resultExiste->EOF)
				{
					$qInsert = "INSERT INTO existencias_bodegas (
																												empresa_id,
																												centro_utilidad,
																												codigo_producto,
																												bodega,
																												existencia,
																												existencia_minima,
																												existencia_maxima,
																												usuario_id,
																												fecha_registro
																											)
																							VALUES (
																												'".$datos_estacion['empresa_id']."',
																												'".$datos_estacion['centro_utilidad']."',
																												'".$medicamento."',
																												'".$bodega."',
																												0,
																												0,
																												0,
																												".UserGetUID().",
																												'".date("Y-m-d H:i")."'
																										)";

					$resultInsert = $dbconn->Execute($qInsert);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al insertar en existancias_bodegas<br><br>".$dbconn->ErrorMsg()."<br><br>".$qInsert;
						return false;
					}
					else{
						return true;
					}
				}
				else//existe el medicamento
				{
					return true;
				}
				//return true;
			}//se ejecuto el query
		}//fin funtion ConsultarEInsertarMedicamento($detalle[medicamento_id]);


		/**
		*		PedirMedicamentos
		*
		*		Esta funcion realiza la solicitud a bodega y paciente de medicamentos y mezclas recetados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function PedirMedicamentos()
		{
			$datosPaciente = unserialize(stripslashes(urldecode($_REQUEST['datosPaciente'])));
			$datos_estacion = $_REQUEST['datos_estacion'];
			//Medicamentos
			$MedicamentosXconfirmar = $_REQUEST['MedicamentosXconfirmar'];
			$cantidad = $_REQUEST['cantidad'];
			//Mezclas
			$MezclasXconfirmar = $_REQUEST['MezclasXconfirmar'];
			$cantidadMezclas = $_REQUEST['cantidadMezclas'];
			$MezclasXcantidad = $_REQUEST['MezclasXcantidad'];
			//inicializo los vectores de comprobacion de commit
			$puedoHacerCommitMed = $puedoHacerCommitMezPac = $puedoHacerCommitMezBod = array();

			if(sizeof($MedicamentosXconfirmar) > 0) //SELECCIONÓ MEDICAMENTOS
			{
    		//ordenamiento de medicamentos por bodega para hacer el maestro de solicitud
				$Med_x_bodegas = $tmpBodegas = $tmpMedicamentos = $vb = array();
				for($i=0; $i<sizeof($MedicamentosXconfirmar); $i++)//each ($x as $posicion => $contenido)
				{

					$y = explode(".-.",$MedicamentosXconfirmar[$i]);
					//Y = [NoPedir, AlPaciente, $bodega], $i, medicamento_id, evolucion_id, [bodega_recetada]


					if($y[0] != "NoPedir")
					{
						if (in_array($y[0],$tmpBodegas))
						{
							$vb[medicamento_id] = $y[2];
							$vb[evolucion] = $y[3];
							$vb[cantidad] = $cantidad[$y[1]];
							if($y[0] != "AlPaciente"){
								$vb[bodega_recetada] = $y[4];
							}
							$Med_x_bodegas[$y[0]][sizeof($Med_x_bodegas[$y[0]])] = $vb;
							unset($vb); $vb=array();
						}
						else
						{
							array_push($tmpBodegas,$y[0]);
							$vb[medicamento_id] = $y[2];
							$vb[evolucion] = $y[3];
							$vb[cantidad] = $cantidad[$y[1]];
							if($y[0] != "AlPaciente"){
								$vb[bodega_recetada] = $y[4];
							}
							$Med_x_bodegas[$y[0]][sizeof($Med_x_bodegas[$y[0]])] = $vb;
							unset($vb); $vb=array();
						}
					}
				}//fin de ordenamiento de medicamentos por bodega


				################################ COMIENZO A INSERTAR LOS DATOS #########################
				$i=0;
				GLOBAL $ADODB_FETCH_MODE;
				list($dbconn) = GetDBconn();
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				######## Estos son medicamentos tanto a bodega como a paciente #######################
				foreach($Med_x_bodegas as $key=>$value)
				{
					$dbconn->BeginTrans();
					if ($key != "AlPaciente")
					{//por cada bodega, se realiza un maestro
						$query = "SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq') AS consecutivo;";
						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar asignar el numero de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
							return false;
						}
						else
						{
							if(!$result->EOF)
							{
								$consecutivo = $result->fields[consecutivo];	
								$query = "INSERT INTO hc_solicitudes_medicamentos
																													(
																													solicitud_id,
																													ingreso,
																													bodega,
																													empresa_id,
																													centro_utilidad,
																													usuario_id,
																													sw_estado,
																													fecha_solicitud,
																													estacion_id,
																													tipo_solicitud
																												)
																									VALUES
																												(
																													".$consecutivo.",
																													".$datosPaciente[ingreso].",
																													'".$key."',
																													'".$datos_estacion[empresa_id]."',
																													'".$datos_estacion[centro_utilidad]."',
																													".UserGetUID().",
																													'0',
																													'".date("Y-m-d h:i:s")."',
																													'".$datos_estacion[estacion_id]."',
																													'M');";

								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "Ocurrió un error al intentar generar el maestro de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
									$dbconn->RollbackTrans();
									$puedoHacerCommitMed[]='0';
									$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									return false;
								}
								else
								{
									//por cada medicamento se realiza un detalle
									foreach($value as $valKey=>$detalle)
									{
										if($key != $detalle[bodega_recetada])
										{
											//la bodega recetada es diferente a la que va a pedir
											if(!$this->ConsultarEInsertarMedicamento($detalle[medicamento_id],$key,$datos_estacion))
											{
												$this->error = "Error al ejecutar la conexion";
												$this->mensajeDeError = "Ocurrió un error al consultar las existencias en bodega<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
												$puedoHacerCommitMed[]='0';
												$dbconn->RollbackTrans();
												$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
												return false;
											}
										}
										$qMed = "INSERT INTO hc_solicitudes_medicamentos_d
																															(
																																solicitud_id,
																																medicamento_id,
																																evolucion_id,
																																cant_solicitada
																															)
																												VALUES
																															(
																																".$consecutivo.",
																																'".$detalle[medicamento_id]."',
																																".$detalle[evolucion].",
																																".$detalle[cantidad].");";

										$result = $dbconn->Execute($qMed);
										if ($dbconn->ErrorNo() != 0)
										{
											$this->error = "Error al ejecutar la conexion";
											$this->mensajeDeError = "Ocurrió un error al intentar ingresar el detalle de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$qMed;
											$dbconn->RollbackTrans();
											$puedoHacerCommitMed[]='0';
											$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
											return false;
										}
									}//fin foreach($value as $valKey=>$detalle) osea el detalle de solicitud
								}//fin else osea que insertó el maestro
							}//no seleccionó nextval
							else
								return false;
						}//ejecuto correctamente la seleccion del siguiente consecutivo
					}
					else
					{
						foreach($value as $valKey=>$detalle)
						{
							$query = "INSERT INTO hc_solicitudes_medicamentos_pacientes
																						(
																							medicamento_id,
																							evolucion_id,
																							cant_solicitada,
																							usuario_id,
																							sw_estado,
																							fecha_solicitud,
																							ingreso
																						)
																			VALUES
																						(
																							'".$detalle[medicamento_id]."',
																							".$detalle[evolucion].",
																							".$detalle[cantidad].",
																							".UserGetUID().",
																							'0',
																							'".date("Y-m-d h:i:s")."',
																							".$datosPaciente[ingreso].")";

							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al ejecutar la conexion";
								$this->mensajeDeError = "Error al intentar insetar la solicitud de medicamentos<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
								$dbconn->RollbackTrans();
								$puedoHacerCommitMed[]='0';
								$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
								return false;
							}
						}//fin foreach($value as $valKey=>$detalle) osea el detalle de solicitud*/
					}//fin else => el insert es para pacientes
					if(!in_array('0',$puedoHacerCommitMed)){
						$dbconn->CommitTrans();
					}
				}//fin foreach medicamentos
			}//fin solicitudes de medicamentos a bodega y paciente

			//######### S O L I C I T U D   D E  M E Z C L A S###########################################
			//ordeno la informacion por mezcla para realizar una solicitud por mezcla, no se puede realizar
			//una sola solicitud para las mezclas porque en el caso que un medicamento exista eos mezclas
			//al realizar el query que obtiene los medicamentos por recibir se duplican lor registros
			foreach ($MezclasXconfirmar as $key => $value)
			{
				if($value != "NoPedir")
				{
					for($i=0; $i < sizeof($MezclasXcantidad); $i++)
					{
						$y = explode(".-.",$MezclasXcantidad[$i]);
						//[mezcla_recetada_id]   [medicamento_id]  [evolucion_id] [posicion]

						if($y[0] == $key)
						{
							$vb[mezcla_id] = $y[0];
							$vb[medicamento_id]= $y[1];
							$vb[evolucion] = $y[2];
							$vb[cantidad] = $cantidadMezclas[$y[3]];
							if($value === "AlPaciente"){
								$Mezclas_x_pedir_paciente[] = $vb;
							}
							else{
								$bodega = $value;
								$Mezclas_x_pedir_bodega[$key][] = $vb;
							}
							unset($vb);
						}//fin if medicamentos a solicitar
					}//fin for
				}//fin si hay medicamentos por solicitar
			}//fin foreach

			###################### SOLICITUDES DE MEZCLAS AL PACIENTE ###############################
			$dbconn->BeginTrans();
			foreach($Mezclas_x_pedir_paciente as $key=>$detalle)
			{
				$query = "INSERT INTO hc_solicitudes_mezclas_pacientes
																										(
																											mezcla_recetada_id,
																											medicamento_id,
																											evolucion_id,
																											cant_solicitada,
																											usuario_id,
																											sw_estado,
																											fecha_solicitud,
																											ingreso
																										)
																							VALUES
																										(
																											".$detalle[mezcla_id].",
																											'".$detalle[medicamento_id]."',
																											".$detalle[evolucion].",
																											".$detalle[cantidad].",
																											".UserGetUID().",
																											'0',
																											'".date("Y-m-d h:i:s")."',
																											".$datosPaciente[ingreso].");";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar realizar la solicitud de mezclas<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$puedoHacerCommitMezPac[]='0';
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					return false;
				}
			}//fin solicitudes mezclas al paciente
			if(!in_array('0',$puedoHacerCommitMezPac)){
				$dbconn->CommitTrans();
			}

			###################### SOLICITUDES A LA BODEGA RECETADA (BODEGA DE LA ESTACION) ##########
			//MAESTRO SOLICITUDES
			foreach($Mezclas_x_pedir_bodega as $key => $value)
			{//por cada mezcla genero una slicitud
				$dbconn->BeginTrans();
				$query = "SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq') AS consecutivo;";
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Ocurrió un error al intentar asignar el numero de la solicitud<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					$dbconn->RollbackTrans();
					$puedoHacerCommitMezBod[]='0';
					$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
					return false;
				}
				else
				{
					if(!$result->EOF)
					{
						$consecutivo = $result->fields[consecutivo];
						$query = "INSERT INTO hc_solicitudes_medicamentos
																											(
																												solicitud_id,
																												ingreso,
																												bodega,
																												empresa_id,
																												centro_utilidad,
																												usuario_id,
																												sw_estado,
																												fecha_solicitud,
																												estacion_id,
																												tipo_solicitud
																											)
																								VALUES
																											(
																												".$consecutivo.",
																												".$datosPaciente[ingreso].",
																												'".$bodega."',
																												'".$datos_estacion[empresa_id]."',
																												'".$datos_estacion[centro_utilidad]."',
																												".UserGetUID().",
																												'0',
																												'".date("Y-m-d h:i:s")."',
																												'".$datos_estacion[estacion_id]."',
																												'Z');";

						$result = $dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0)
						{
							$this->error = "Error al ejecutar la conexion";
							$this->mensajeDeError = "Ocurrió un error al intentar generar el maestro de la solicitud de mezclas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
							$dbconn->RollbackTrans();
							$puedoHacerCommitMezBod[]='0';
							$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
							return false;
						}
						else
						{
							//por cada medicamento de la mezcla se realiza un detalle
							foreach($value as $valKey=>$detalle)
							{
								$qMezclas = "INSERT INTO hc_solicitudes_medicamentos_mezclas_d
																									(
																										solicitud_id,
																										mezcla_recetada_id,
																										medicamento_id,
																										evolucion_id,
																										cant_solicitada
																									)
																						VALUES
																									(
																										".$consecutivo.",
																										'".$detalle[mezcla_id]."',
																										'".$detalle[medicamento_id]."',
																										".$detalle[evolucion].",
																										".$detalle[cantidad]."
																									)";

								$resultMezclas = $dbconn->Execute($qMezclas);
								if ($dbconn->ErrorNo() != 0)
								{
									$this->error = "Error al ejecutar la conexion";
									$this->mensajeDeError = "ocurrió un error al intentar hacer el detalle de la solicitud de mezclas<br><br>".$dbconn->ErrorMsg()."<br><br>".$qMezclas;
									$dbconn->RollbackTrans();
									$puedoHacerCommitMezBod[]='0';
									$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
									return false;
								}
								else{
									$puedoHacerCommitMezBod[]='1';
								}
							}//fin FOREACH detalle de medicamentos de mezcla
						}//fin else ejecuto el insert del maestro de slicitud de mezcla
					}//fin si encontro el nextval para la solicitud
				}//fin else ejecutó el query del nextval
				if(!in_array('0',$puedoHacerCommitMezBod)){
					$dbconn->CommitTrans();
				}
			}//foreach mezcla solicitada
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			//$this->VerMedicamentosPorSolicitarPaciente($datosPaciente,$datos_estacion);

			if(in_array('0',$puedoHacerCommitMed) || in_array('0',$puedoHacerCommitMezPac) || in_array('0',$puedoHacerCommitMezBod))
			{
				$mensaje = "NO TODAS LAS SOLICITUDES SE REALIZARON EXITOSAMENTE, DEBE REVISAR LAS SOLICITUDES REALIZADAS";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallVerMedicamentosPorSolicitarPaciente',array("Paciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
				$boton = "VOLVER AL LISTADO";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
			else
			{
				$mensaje = "TODAS LAS SOLICITUDES SE REALIZARON CON EXITO";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallVerMedicamentosPorSolicitarPaciente',array("Paciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
				$boton = "VOLVER AL LISTADO";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}//fin PedirMedicamentos


		/*
		*		CancelarSolicitudesMedicamentos()
		*
		*		Cambia el estado de la solicitud en la tabla de solicitudes de bodega y paciente
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CancelarSolicitudesMedicamentos()
		{
			$datosPaciente = $_REQUEST['datosPaciente'];
			$CancelarSolicitudBodega = $_REQUEST['CancelarSolicitudBodega'];
			$CancelarSolicitudPaciente = $_REQUEST['CancelarSolicitudPaciente'];

			if(!$CancelarSolicitudPaciente && !$CancelarSolicitudBodega)
			{
				$mensaje = "DEBE SELECCIONAR AL MENOS UN MEDICAMENTO";
				$titulo = "MENSAJE";
				$accion = ModuloGetURL('app','EstacionE_Medicamentos','user','CallVerMedicamentosPorSolicitarPaciente',array("Paciente"=>$datosPaciente,"datos_estacion"=>$_REQUEST['datos_estacion']));
				$boton = "REGRESAR";
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}

			if($CancelarSolicitudPaciente)
			{
				for($i=0; $i<sizeof($CancelarSolicitudPaciente); $i++)
				{
					$z = explode(".-.",$CancelarSolicitudPaciente[$i]);
					if(!empty($z[1]))
					{
						$query = "UPDATE hc_solicitudes_mezclas_pacientes
											SET sw_estado = '3'
											WHERE consecutivo = ".$z[0]."";

					}
					else
					{
						$query = "UPDATE hc_solicitudes_medicamentos_pacientes
											SET sw_estado = '3'
											WHERE consecutivo = ".$z[0]."";

					}
					list($dbconn) = GetDBconn();
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar eliminar la solicitud de medicamentos a paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
				}
			}
			if($CancelarSolicitudBodega)
			{
				for($i=0; $i<sizeof($CancelarSolicitudBodega); $i++)
				{
					$query = "UPDATE hc_solicitudes_medicamentos
										SET sw_estado = '3'
										WHERE solicitud_id = ".$CancelarSolicitudBodega[$i]."";

					list($dbconn) = GetDBconn();
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al ejecutar la conexion";
						$this->mensajeDeError = "Error al intentar eliminar la solicitud de medicamentos a paciente<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
						return false;
					}
				}
			}
		 $this->VerMedicamentosPorSolicitarPaciente($datosPaciente,$_REQUEST['datos_estacion']);
		 return true;
		}




		/**
		*		CallFrmSignosVitales
		*
		*		Esta función lista a tdodos los pacientes de la estacion para poder insertar
		*		en un determinado horario el registro de los signos vitales
		*
		*		@Author Jairo Duvan Diaz
		*		@access Public
		*		@return bool
		*/
		function CallFrmMedicamentos()
		{
		  if(empty($_REQUEST['datos_estacion']['control_descripcion']))
			{
				$_REQUEST['datos_estacion']['control_descripcion']='MEDICAMENTOS';
				$_REQUEST['datos_estacion']['control_id']=$_REQUEST['control_id'];
			}
			if(!$this->FrmMedicamentos($_REQUEST['estacion'],$_REQUEST['datos_estacion']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"FrmSignosVitales\"";
				return false;
			}
			return true;
		}





		/* funcion del modulo estacione_medicamento
	//#######################################################################################
	// plan terapeutico
	//#######################################################################################
		/**
		*		CallListMedicamentosPendientesXSolicitar
		*
		*		Hace un llamado a la vista que muestra los pacientes con medicamentos recetados
		*
		*		@Author Rosa Maria Angel
		*		@access Public
		*		@return bool
		*/
		function CallListMedicamentosPendientesXSolicitar()
		{
			if(!$this->ListMedicamentosPendientesXSolicitar($_REQUEST['datos_estacion'],$_REQUEST['datosp']))
			{
				$this->error = "No se puede cargar la vista";
				$this->mensajeDeError = "Ocurrió un error al intentar cargar la vista \"ListMedicamentosPendientesXSolicitar\"";
				return false;
			}
			return true;
		}






/**********************esta va para estacionE_ControlPaciente******************************/
	/**
	*		FormaMensaje => muestra mensajes al usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => mensaje a mostrar
	*		@param string => titulo de la tabla
	*		@param string => action del form
	*		@param string => value del input-submit
	*		@return boolean
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
		$this->salida .= ThemeAbrirTabla($titulo)."<br>";
		$this->salida .= "<table width=\"60%\" align=\"center\" class=\"normal_10\" border='0'>\n";
		$this->salida .= "	<form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
		if(!empty($boton)){
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
		}
		else{
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
		}
		$this->salida .= "	</form>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FormaMensaje




//*
function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia, $evolucion_id)
{
		$pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();
		$query == '';
		if ($tipo_posologia == 1)
		{
				$query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 2)
		{
				$query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
		}
		if ($tipo_posologia == 3)
		{
    		$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 4)
		{
    		$query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 5)
		{
    		$query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}

		if ($query!='')
		{
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la consulta de medicamentos recetados";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				else
				{
					if ($tipo_posologia != 4)
					{
						while (!$result->EOF)
						{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
					else
					{
						while (!$result->EOF)
						{
							$vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
				}
				//$result->Close();
		}
	  return $vector;
}






//clzc - si
function Consulta_Solicitud_Medicamentos($ingreso)
{
		 list($dbconnect) = GetDBconn();
		//query igual que el de cexterna pero se altero uniendo profesionales para hospitalizacion
      $query= "select a.sw_estado,
		 k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
		 else 'NO POS' end as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
		 a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
		 h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
		 l.descripcion, a.evolucion_id from hc_medicamentos_recetados_hosp as a left join
		 hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		 unidades as l,

		 hc_evoluciones n

		 where n.ingreso = ".$ingreso."
		 and a.evolucion_id = n.evolucion_id and


     a.sw_estado = '1' and
		  (a.sw_ambulatorio ISNULL OR a.sw_ambulatorio='0') AND


		 k.cod_principio_activo = c.cod_principio_activo and
		 h.codigo_producto = k.codigo_medicamento and
		 a.codigo_producto = h.codigo_producto and
		 h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
		 order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}

//obtener el tipo de usuario
		if (($this->tipo_profesional=='1') OR ($this->tipo_profesional=='2'))
		{
			$_SESSION['PROFESIONAL'.$pfj]=1;
		}
		else
		{
			$_SESSION['PROFESIONAL'.$pfj]=3;
		}
//fin del tipo de usuario
		//$result->Close();
	  return $vector;
}
/**********************esta va para estacionE_ControlPaciente******************************/




     function CancelSolicitudMedicametos()
     {
		$matriz=$_REQUEST['matriz'];
		$bodega=$_REQUEST['bodega'];
		$SWITCHE=$_REQUEST['switche'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$obs=$_REQUEST['obs'];
		$spy=$_REQUEST['spia'];

		//este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
		//entonces lo dejamos alli mientras.
		$op=$_REQUEST['opcion'];

		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();
		for($i=0;$i<sizeof($matriz);$i++)
		{

               $query="UPDATE hc_solicitudes_medicamentos
                                   SET
                                   sw_estado='3'
                                   WHERE solicitud_id='".$matriz[$i]."'";
     
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

			$query="INSERT INTO hc_auditoria_solicitudes_medicamentos
						 (fecha_registro,usuario_id,observacion,solicitud_id)
						 VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

		}
		$dbconn->CompleteTrans();   //termina la transaccion

		if($spy==1)
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->MedicamentosIns_X_Recibir($estacion,$bodega,$SWITCHE);
			return true;
		}
		else
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->FrmMedicamentos($estacion,$datos_estacion);
			return true;
		}
	}




     function CancelSolicitud_Medicamentos_Para_Paciente()
     {
		$matriz=$_REQUEST['matriz'];
		$SWITCHE=$_REQUEST['switche'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$obs=$_REQUEST['obs'];
		$spy=$_REQUEST['spia'];

		//este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
		//entonces lo dejamos alli mientras.
		$op=$_REQUEST['opcion'];


		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();
		for($i=0;$i<sizeof($matriz);$i++)
		{

			 $query="UPDATE hc_solicitudes_medicamentos_pacientes
						 SET
						 sw_estado='2'
						 WHERE solicitud_id='".$matriz[$i]."'";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

			$query="INSERT INTO hc_auditoria_solicitudes_medicamentos_pacientes
						 (fecha_registro,usuario_id,observacion,solicitud_id)
						 VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
		$dbconn->CompleteTrans();   //termina la transaccion
	
		if($spy==1)
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->CallInsumosXRecibir($estacion,$bodega,$SWITCHE);
			return true;
		}
		else
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->FrmMedicamentos($estacion,$datos_estacion);
			return true;
		}
	}


	function CancelSolicitudInsumos()
	{
		$matriz=$_REQUEST['matriz'];
		$bodega=$_REQUEST['bodega'];
		$SWITCHE=$_REQUEST['switche'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$obs=$_REQUEST['obs'];
		$spy=$_REQUEST['spia'];

		//este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
		//entonces lo dejamos alli mientras.
		$op=$_REQUEST['opcion'];


		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();
		for($i=0;$i<sizeof($matriz);$i++)
		{

			 $query="UPDATE hc_solicitudes_medicamentos
						 SET
						 sw_estado='3'
						 WHERE solicitud_id='".$matriz[$i]."'";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }

			$query="INSERT INTO hc_auditoria_solicitudes_medicamentos
						 (fecha_registro,usuario_id,observacion,solicitud_id)
						 VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
		}
		$dbconn->CompleteTrans();   //termina la transaccion

		if($spy==1)
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->CallMedicamentosIns_X_Recibir($estacion,$bodega,$SWITCHE);
			return true;
		}
		else
		{
			$this->frmError["MensajeError"]="SOLICITUD CANCELADA SATISFACTORIAMENTE.";
			$this->FrmMedicamentos($estacion,$datos_estacion);
			return true;
		}
	}

     function Get_ParametrosDevolucion()
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();		
		$query="SELECT * 
          	   FROM estacion_enfermeria_parametros_devolucion
                  ORDER BY parametro_devolucion_id ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
				
          while ($data = $result->FetchRow()){
               $vars[] = $data;
          }
               
          $result->Close();
          return $vars;
     }



     function InsertDevolucionMedicamento()
     {
		$bodega=$_REQUEST['bodega'];
		$datos_pac=$_REQUEST['datos_pac'];
		$SWITCHE=$_REQUEST['switche'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$medic=$_REQUEST['medic'];//$_SESSION['ESTACION']['VECTOR_DEV'][$_REQUEST['ingreso']][$bodega];
		$op=$_REQUEST['opt'];//aqui van los values osea las cajas de texto....
          $justificacion_devo = $_REQUEST['justificacion_devo'];//Justificacion de devolucion
          $parametro_id = $_REQUEST['parametro'];

          if($parametro_id == '-1' ){
               $this->frmError["MensajeError"]="SELECCIONE LA JUSTIFICACION DE LA DEVOLUCION.";
               $this->ConfirmarDevMed();
               return true;
		}

		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();

		$query = "SELECT nextval('inv_solicitudes_devolucion_documento_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer el consecutivo ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}

		$doc=$res->fields[0];

		if(empty($doc))
		{
			$this->error = "Error al traer el consecutivo ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			$dbconn->RollbackTrans();
			return false;
		}

		$query="INSERT INTO inv_solicitudes_devolucion
                              (
                                   empresa_id,
                                   centro_utilidad,
                                   documento,
                                   bodega,
                                   fecha,
                                   observacion,
                                   usuario_id,
                                   fecha_registro,
                                   estacion_id,
                                   estado,
                                   ingreso,
                                   parametro_devolucion_id
                              )
                              VALUES
                              (
                                   '".$estacion[empresa_id]."',
                                   '".$estacion[centro_utilidad]."',
                                   '$doc',
                                   '".$medic[0][bodega]."',
	                              '".date("Y-m-d")."',
                                   '$justificacion_devo',                                   
                                   ".UserGetUID().",
                                   now(),
                                   '".$estacion[estacion_id]."',
                                   '0',
                                   '".$_REQUEST['ingreso']."',
                                   '$parametro_id'
                              )";

          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en inv_solicitudes_devolucion ";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

		for($i=0;$i<sizeof($medic);$i++)
		{
               if($op[$i]>0)
               {
                    $query="INSERT INTO inv_solicitudes_devolucion_d
                                   (
                                        documento,
                                        codigo_producto,
                                        cantidad
                                   )
                                   VALUES
                                   (
                                        '$doc',
                                        '".$medic[$i][codigo_producto]."',
                                        '".$op[$i]."'
                                   )";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en inv_solicitudes_devolucion_d ";
                         $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
		}
          $dbconn->CompleteTrans();   //termina la transaccion

          //$arreglo_bodega_estacion=$_SESSION['ESTACION']['VECTOR_DEV']['BODEGA_ESTACION'];
          //unset($_SESSION['ESTACION']['VECTOR_DEV']);
          
          if($_REQUEST['accion'] == '1')
          {
               $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
               $this->FrmDevolucionInsumos($estacion,$bodega,$datos_pac);
          }
          else
          {
               $this->frmError["MensajeError"]="DEVOLUCION REALIZADA SATISFACTORIAMENTE.";
               $this->FrmDevolucionMedicamentos($estacion,$bodega,$datos_pac);
		}
          return true;
	}
     
     function BusquedaDevoluciones_Pendientes($estacion,$bodega,$datos_pac,$producto)
     {
		GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
	     $query="SELECT SUM(b.cantidad) AS cantidad
                    FROM inv_solicitudes_devolucion a, 
                    	inv_solicitudes_devolucion_d b, inventarios c, 
                         inventarios_productos d,
                         existencias_bodegas e
                    WHERE a.empresa_id='".$estacion['empresa_id']."' 
                    AND a.centro_utilidad='".$estacion['centro_utilidad']."' 
                    AND a.bodega='".$bodega."' 
                    AND a.ingreso='".$datos_pac['ingreso']."'
                    AND a.estacion_id='".$estacion['estacion_id']."'
                    AND (a.estado='0' OR a.estado='1')
                    AND a.documento=b.documento
                    AND b.codigo_producto='$producto'
                    AND c.empresa_id=a.empresa_id 
                    AND c.codigo_producto=b.codigo_producto 
                    AND d.codigo_producto=b.codigo_producto 
                    AND a.empresa_id=e.empresa_id 
                    AND a.centro_utilidad=e.centro_utilidad 
                    AND a.bodega=e.bodega 
                    AND b.codigo_producto=e.codigo_producto;";
	
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          $devoluciones = $resultado->FetchRow();
          return $devoluciones;
     }

     
     function Requisito_CancelacionDevolucion()
     {
     	$bodega=$_REQUEST['bodega'];
		$datos_pac=$_REQUEST['datos_pac'];
          $estacion=$_REQUEST['estacion'];

          list($dbconn) = GetDBconn();
          
          $documento = $_REQUEST['documento'];
          $consecutivo = $_REQUEST['consecutivo'];
          
	     $query_Delete="DELETE FROM inv_solicitudes_devolucion_d
          			WHERE consecutivo ='".$consecutivo."'
                         AND documento ='".$documento."';";
	
          $resultado = $dbconn->Execute($query_Delete);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la Eliminacion";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          
          $query_busqueda="SELECT count(*)
          			  FROM inv_solicitudes_devolucion_d 
                           WHERE documento ='".$documento."';";
                           
          $resultado = $dbconn->Execute($query_busqueda);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la Busqueda";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          list($cantidad) = $resultado->FetchRow();
          
          if(empty($cantidad))
          {
               $query_Delete_doc="DELETE FROM inv_solicitudes_devolucion
		                        WHERE documento ='".$documento."';";
               
               $resultado = $dbconn->Execute($query_Delete);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error en la Eliminacion";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
          }
          
          $this->frmError["MensajeError"]="SE CANCELO LA DEVOLUCION CORRECTAMENTE.";
          $this->FrmDevolucionMedicamentos($estacion,$bodega,$datos_pac);
          return true;
     }



	//funcion que confirma el despacho de medicamentos en la estacion

	function InsertDespSolicitudMed()
	{
		$matriz=$_REQUEST['matriz'];
		$bodega=$_REQUEST['bodega'];
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$obs=$_REQUEST['obs'];
		$spy=$_REQUEST['spia'];
		$plan=$_REQUEST['plan'];
		$cuenta=$_REQUEST['cuenta'];
		$SWITCHE=$_REQUEST['switche'];

		//este vector se puede utilizar en un futuro ya que viene la solicitud y el consecutivo
		//entonces lo dejamos alli mientras.
		$op=$_REQUEST['opcion'];

		list($dbconn) = GetDBconn();
		//$dbconn->StartTrans();
		IncludeLib("despacho_medicamentos");

		for($i=0;$i<sizeof($matriz);$i++)
		{
               $_SESSION['DESPACHO']['MEDICAMENTOS']['CUENTA']=$cuenta;
               $_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']=$matriz[$i];
               $_SESSION['DESPACHO']['MEDICAMENTOS']['PLAN']=$plan;
               DocumentoDespachoMedicamentos();
               /*$query="UPDATE hc_solicitudes_medicamentos
                                   SET
                                   sw_estado='2'
                                   WHERE solicitud_id='".$matriz[$i]."'";
     
                                        $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                             $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                                             $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                             $dbconn->RollbackTrans();
                                             return false;
                                        }*/
     
               /*	$query="INSERT INTO hc_auditoria_solicitudes_medicamentos
                                   (fecha_registro,usuario_id,observacion,solicitud_id)
                                   VALUES(now(),'".UserGetUID()."','$obs','".$matriz[$i]."');";
     
                                   $dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                             $this->error = "no se inserto en  hc_auditoria_solicitudes_medicamentos";
                                             $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                             $dbconn->RollbackTrans();
                                             return false;
                                        }*/


               if($_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']==4)
               {
                         $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje'];
                         //$this->mensajeDeError = "Ocurrió error.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         //return false;
               }else{
                    $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje'];
                    //$this->mensajeDeError = "Ocurrió error.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }

     	}
		//$dbconn->CompleteTrans();   //termina la transaccion
          //$this->frmError["MensajeError"]="SOLICITUD DESPACHADA SATISFACTORIAMENTE.";
          $this->InsumosMed_X_Despachar($estacion,$bodega,$SWITCHE);
          return true;
	}





//funcion que trae los medicamentos solicitados para cancelarlos.
//pero desde controles de pacientes ya que no podemos filtrar por estacion
function GetMedicamentosSolicitadosControlPacientes($ingreso,$emp)
{
		 list($dbconnect) = GetDBconn();
	   $query= "select a.sw_estado,
  	 a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
		 a.dosis,a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
		 h.descripcion as producto, c.descripcion as principio_activo,
		 l.descripcion,x.solicitud_id,z.consecutivo_d,z.cant_solicitada,x.bodega

		 FROM
		 hc_medicamentos_recetados_hosp as a,
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		 unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_medicamentos_d z


		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.empresa_id='$emp'

		 and x.sw_estado = '0'
		 and z.medicamento_id=a.codigo_producto
		 and a.evolucion_id=z.evolucion_id

		 and a.sw_estado = '1'
		 and	k.cod_principio_activo = c.cod_principio_activo
		 and  h.codigo_producto = k.codigo_medicamento
		 and  h.codigo_producto = a.codigo_producto
		 and h.unidad_id = l.unidad_id
		 ORDER BY z.solicitud_id DESC";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}




//funcion que trae los med recibidos 
//[duvan] preguntar los estados q podemnos visualizar.... 0,1 ,2...
function Recepcion_Med_Ins_Para_Pacientes($ingreso,$codigo,$estacion)
{

		 list($dbconnect) = GetDBconn();
	   $query= "SELECT SUM(z.cantidad) AS cantidad
  	 FROM
		 hc_recepcion_medicamentos_pacientes x,
		 hc_recepcion_medicamentos_pacientes_d z


		 WHERE
		 x.ingreso = '$ingreso' AND
		 z.codigo_producto = '$codigo' AND
		 z.estado='0' AND
		 x.estacion_id = '$estacion' AND
		 x.recepcion_id=z.recepcion_id";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ 
		  return $result->fields[0];
		}
}





//funcion que trae los med solicitados del pacientes para para cancelarlos.
//pero desde controles de pacientes ya que no podemos filtrar por estacion
//[duvan] preguntar los estados q podemnos visualizar.... 0,1 ,2...
function Get_Medicamentos_Solicitados_Para_Pacientes($ingreso,$empresa,$solicitud,$codigo)
{

		 if($codigo)
		 {
				$filtro="AND z.codigo_producto='$codigo'";
				//AND x.solicitud_id='$solicitud'		 
		 }

		 list($dbconnect) = GetDBconn();
	  /*$query= "select 
  	 h.codigo_producto,z.cantidad,
		 h.descripcion as producto,h.descripcion_abreviada,
		 l.descripcion,x.solicitud_id

		 FROM
		 inventarios_productos as h,
		 unidades as l,hc_solicitudes_medicamentos_pacientes x,
		 hc_solicitudes_medicamentos_pacientes_d z


		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.sw_estado = '0'
		 and z.sw_estado = '0'
		 and  h.codigo_producto = z.codigo_producto
		 and h.unidad_id = l.unidad_id
		 $filtro
		 ORDER BY z.solicitud_id DESC";*/
		 
		 
		 $query= "select h.codigo_producto,h.descripcion as producto,
		 h.descripcion_abreviada, l.descripcion,sum(z.cantidad) as cantidad
		 
			FROM 
			inventarios_productos as h, unidades as l,
			hc_solicitudes_medicamentos_pacientes x,
			hc_solicitudes_medicamentos_pacientes_d z 
			WHERE 
			x.ingreso =$ingreso and x.solicitud_id=z.solicitud_id 
			and x.sw_estado = '0' and z.sw_estado = '0' 
			$filtro
			and h.codigo_producto = z.codigo_producto and h.unidad_id = l.unidad_id 
		 GROUP BY h.codigo_producto,h.descripcion,h.descripcion_abreviada, l.descripcion";
		 
		 

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}



//funcion que trae los imsunos solicitados para cancelarlos.
//pero desde controles de pacientes ya que no podemos filtrar por estacion
     function GetInsumosSolicitadosControlPacientes($ingreso,$emp)
     {
          list($dbconnect) = GetDBconn();
     	$query= "select 
	  	 h.codigo_producto,z.cantidad,
		 h.descripcion as producto,h.descripcion_abreviada,
		 l.descripcion,x.solicitud_id,z.consecutivo_d,x.bodega

		 FROM
		 inventarios_productos as h,
		 unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_insumos_d z


		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.empresa_id='$emp'

		 and x.sw_estado = '0'
		 and  h.codigo_producto = z.codigo_producto
		 and h.unidad_id = l.unidad_id
		 ORDER BY z.solicitud_id DESC";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
     	return $vector;
	}






//funcion que trae los Insumos solicitados para cancelarlos.
function GetInsumosSolicitados($ingreso,$estacion,$bodega)
{
		 list($dbconnect) = GetDBconn();
	 $query= "select 
  	 h.codigo_producto,z.cantidad,
		 h.descripcion as producto, 
		 l.descripcion,x.solicitud_id,z.consecutivo_d,x.bodega

		 FROM
		 inventarios_productos as h,
		 unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_insumos_d z
		 ,bodegas_estaciones y

		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.empresa_id='".$estacion[empresa_id]."'
		 and x.empresa_id=y.empresa_id
		 and x.centro_utilidad=y.centro_utilidad
		 and x.estacion_id=y.estacion_id
		 and x.bodega=y.bodega

		 and x.sw_estado = '0'
		 and  h.codigo_producto = z.codigo_producto
		 and x.tipo_solicitud='I'
		 and h.unidad_id = l.unidad_id
		 and y.estacion_id='".$estacion[estacion_id]."'
		 and y.empresa_id='".$estacion[empresa_id]."'
		 and y.centro_utilidad='".$estacion[centro_utilidad]."'
		 and y.bodega='$bodega'
		 ORDER BY z.solicitud_id DESC";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}







//funcion que trae los medicamentos solicitados para cancelarlos.
function GetMedicamentosSolicitados($ingreso,$estacion,$bodega)
{
		 list($dbconnect) = GetDBconn();
	 $query= "select a.sw_estado,
  	 a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
		 a.dosis,a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
		 h.descripcion as producto, c.descripcion as principio_activo,
		 l.descripcion,x.solicitud_id,z.consecutivo_d,z.cant_solicitada,x.bodega

		 FROM
		 hc_medicamentos_recetados_hosp as a,
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		 unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_medicamentos_d z
		 ,bodegas_estaciones y

		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.empresa_id='".$estacion[empresa_id]."'
		 and x.empresa_id=y.empresa_id
		 and x.centro_utilidad=y.centro_utilidad
		 and x.estacion_id=y.estacion_id
		 and x.bodega=y.bodega

		 and x.sw_estado = '0'
		 and z.medicamento_id=a.codigo_producto
		 and a.evolucion_id=z.evolucion_id

		 and a.sw_estado = '1'
		 and	k.cod_principio_activo = c.cod_principio_activo
		 and  h.codigo_producto = k.codigo_medicamento
		 and  h.codigo_producto = a.codigo_producto
		 and h.unidad_id = l.unidad_id
		 and y.estacion_id='".$estacion[estacion_id]."'
		 and y.empresa_id='".$estacion[empresa_id]."'
		 and y.centro_utilidad='".$estacion[centro_utilidad]."'
		 and y.bodega='$bodega'
		 ORDER BY z.solicitud_id DESC";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}



/*
* funcion q trae los datos si se despacho sale la cantidad, el producto
* si no se despacho pintarmos en la forma 'No despachado'
*/
     function GetDatosDespachoIns($doc,$serial,$solicitud)
     {

          list($dbconnect) = GetDBconn();
          $query="SELECT b.cantidad,c.descripcion,c.codigo_producto
                    FROM bodegas_documento_despacho_med a,
                         bodegas_documento_despacho_ins_d b,
                         inventarios_productos c
                         WHERE a.documento_despacho_id='$doc'
                         AND a.documento_despacho_id=b.documento_despacho_id
                         AND b.codigo_producto=c.codigo_producto
                         AND b.consecutivo_solicitud='$serial'";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
		     return false;
		}

          $vector[]=$result->GetRowAssoc($ToUpper = false);
          return $vector;
	}


/*
* funcion q trae los datos si se despacho sale la cantidad, el producto
* si no se despacho pintarmos en la forma 'No despachado'
*/
     function GetDatosDespacho($doc,$serial,$solicitud)
     {
          list($dbconnect) = GetDBconn();
          $query="SELECT b.cantidad,c.descripcion,c.codigo_producto
                    FROM bodegas_documento_despacho_med a,
                         bodegas_documento_despacho_med_d b,
                         inventarios_productos c
                         WHERE a.documento_despacho_id='$doc'
                         AND a.documento_despacho_id=b.documento_despacho_id
                         AND b.codigo_producto=c.codigo_producto
                         AND b.consecutivo_solicitud='$serial'";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
		}

          $vector[]=$result->GetRowAssoc($ToUpper = false);
          return $vector;
	}



//funcion que trae los medicamentos q se han devuelto. -- Anitguo.
     function GetMedicamentosDevueltos($ingreso,$estacion,$bodega,$codigo)
     {
          list($dbconnect) = GetDBconn();
          $query= "SELECT b.codigo_producto,b.cantidad FROM inv_solicitudes_devolucion a,
                                   inv_solicitudes_devolucion_d b,bodegas_documentos_d c
                                   WHERE
                                   a.ingreso = ".$ingreso."
                                   AND a.documento=b.documento
                                   AND a.empresa_id='".$estacion[empresa_id]."'
                                   AND a.centro_utilidad='".$estacion[centro_utilidad]."'
                                   AND a.bodega='".$bodega."'
                                   AND a.estacion_id='".$estacion[estacion_id]."'
                                   AND (a.estado='0' OR a.estado='1')
                                   AND b.codigo_producto='".$codigo."'
                                   AND a.bodegas_doc_id=c.bodegas_doc_id
                                   AND a.numeracion=c.numeracion
                         ORDER BY b.documento ASC";
     
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          { 
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          return $vector;
     }



//funcion que trae los medicamentos solicitados para cancelarlos.
function GetMedicamentosPendDesp($ingreso,$estacion,$bodega)
{
		 list($dbconnect) = GetDBconn();
	 $query= "select a.sw_estado,x.documento_despacho as doc,
  	 a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
		 a.dosis,a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
		 h.descripcion as producto, c.descripcion as principio_activo,
		 l.descripcion,x.solicitud_id,z.consecutivo_d,cant_solicitada,x.sw_estado as sw

		 FROM
		 hc_medicamentos_recetados_hosp as a,
		 inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
		 unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_medicamentos_d z
		 ,bodegas_estaciones y

		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.empresa_id='".$estacion[empresa_id]."'
		 and x.empresa_id=y.empresa_id
		 and x.bodega=y.bodega
		 and x.centro_utilidad=y.centro_utilidad
		 and x.estacion_id=y.estacion_id
		 and (x.sw_estado = '1'
		 OR	 x.sw_estado = '5')
   	 and z.medicamento_id=a.codigo_producto
		 and a.evolucion_id=z.evolucion_id

		 and a.sw_estado = '1'
		 and	k.cod_principio_activo = c.cod_principio_activo
		 and  h.codigo_producto = k.codigo_medicamento
		 and  h.codigo_producto = a.codigo_producto
		 and h.unidad_id = l.unidad_id
		 and y.estacion_id='".$estacion[estacion_id]."'
		 and y.empresa_id='".$estacion[empresa_id]."'
		 and y.centro_utilidad='".$estacion[centro_utilidad]."'
		 and y.bodega='$bodega'
		 ORDER BY z.solicitud_id DESC";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}



//funcion que trae los medicamentos solicitados para cancelarlos.
function GetInsumosPendDesp($ingreso,$estacion,$bodega)
{
		 list($dbconnect) = GetDBconn();
	$query= "select x.documento_despacho as doc,
  	 z.codigo_producto, z.cantidad,
		 h.descripcion as producto,
		 l.descripcion,x.solicitud_id,z.consecutivo_d,x.sw_estado as sw

		 FROM
		 inventarios_productos as h,
		 unidades as l,hc_solicitudes_medicamentos x,hc_solicitudes_insumos_d z
		 ,bodegas_estaciones y

		 WHERE
		 x.ingreso = ".$ingreso."
		 and x.solicitud_id=z.solicitud_id
		 and x.empresa_id='".$estacion[empresa_id]."'
		 and x.empresa_id=y.empresa_id
		 and x.bodega=y.bodega
		 and x.centro_utilidad=y.centro_utilidad
		 and x.estacion_id=y.estacion_id
		 and (x.sw_estado = '1'
		 OR	 x.sw_estado = '5')
   	 and x.tipo_solicitud='I'
		 and  h.codigo_producto = z.codigo_producto
		 and h.unidad_id = l.unidad_id
		 and y.estacion_id='".$estacion[estacion_id]."'
		 and y.empresa_id='".$estacion[empresa_id]."'
		 and y.centro_utilidad='".$estacion[centro_utilidad]."'
		 and y.bodega='$bodega'
		 ORDER BY z.solicitud_id DESC";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;
}




function ReporteFormulaMedica_Para_Pacientes()
{
	$reporte= new GetReports();
 	$mostrar=$reporte->GetJavaReport('app','EstacionE_Medicamentos','solicitud_medicamentos_pacientes_estacion_html',array('datos_estacion'=>$_REQUEST[datos_estacion],'estacion'=>$_REQUEST[estacion],'bodega'=>$_REQUEST[bodega_estacion], 'solicitud'=>$_REQUEST['solicitud']),array('rpt_name'=>'formula_medica_paciente','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
	$nombre_funcion=$reporte->GetJavaFunction();
	$this->salida .=$mostrar;
  $this->salida .="<body onload=".$nombre_funcion.">";
	$this->FrmMedicamentos($_REQUEST[estacion],$_REQUEST[datos_estacion]);
 	return true;
}


//funciones medicamentos
function ReporteFormulaMedica()
{
//si el reporte es pdf se redirecciona al reporte de la clase de alex sino
//sigue derecho y ejecuta el reporte pos
	if ($_REQUEST['mandarpdf']!='')
	{
		//lo de alex
		$reporte= new GetReports();
  	$mostrar=$reporte->GetJavaReport('app','EstacionE_Medicamentos','formula_medica_estacion_html',array('datos_estacion'=>$_REQUEST[datos_estacion],'estacion'=>$_REQUEST[estacion],'bodega'=>$_REQUEST[bodega_estacion], 'op'=>$_REQUEST['op']),array('rpt_name'=>'formula_medica_hosp_html','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$nombre_funcion=$reporte->GetJavaFunction();
		$this->salida .=$mostrar;
  	$this->salida .="<body onload=".$nombre_funcion.">";
		//fin de alex
	}
	else
	{
			if (!IncludeFile("classes/reports/reports.class.php"))
			{
					$this->error = "No se pudo inicializar la Clase de Reportes";
					$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
					return false;
			}

			//condicion para el reporte de los medicamentos seleccionados
			if(sizeof($_REQUEST['op'])>0)
			{
					$search="";
					$union= "";
					$arr = $_REQUEST['op'];
					$indice = 1;
					foreach($arr as $x=>$y)
					{
						$vector=explode (",",$y);
						if($indice==1)
						{
							$union = ' and  ((';
						}
						else
						{
							$union = ' or (';
						}
						$search.= "$union a.codigo_producto= '".$vector[0]."' and a.evolucion_id= ".$vector[1].")";
						$indice++;
					}
					$search.=")";
			}
			else
			{
					$AND="";
					$search="";
			}
			//fin de la condicion
			list($dbconn) = GetDBconn();
			$query="SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||
		  w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
		  w.tipo_id_paciente, w.paciente_id,

			n.ingreso, n.fecha, w.residencia_direccion, w.residencia_telefono,
			v.tipo_afiliado_id, t.plan_id, sw_tipo_plan, s.rango,
			v.tipo_afiliado_nombre, p.nombre_tercero,	u.nombre_tercero as cliente,
			r.descripcion as tipo_profesional,	p.tipo_id_tercero as tipo_id_medico,
			p.tercero_id as	medico_id, q.tarjeta_profesional,	t.plan_descripcion,
			a.evolucion_id, case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
			a.sw_paciente_no_pos, a.codigo_producto,  h.descripcion as producto,
			c.descripcion as principio_activo, m.nombre as via, a.dosis,
			a.unidad_dosificacion, a.tipo_opcion_posologia_id, a.cantidad, l.descripcion,
			h.contenido_unidad_venta,	a.observacion

			FROM hc_medicamentos_recetados_hosp as a left join hc_vias_administracion as m
			on (a.via_administracion_id = m.via_administracion_id)
			left join hc_evoluciones as n on (a.evolucion_id= n.evolucion_id) left join
			profesionales_usuarios as o on (n.usuario_id = o.usuario_id) left join
			terceros as p	on (o.tipo_tercero_id = p.tipo_id_tercero AND
			o.tercero_id = p.tercero_id) left join profesionales as q on
			(o.tipo_tercero_id = q.tipo_id_tercero AND o.tercero_id = q.tercero_id)
			left join tipos_profesionales as r on (q.tipo_profesional = r.tipo_profesional)
			left join cuentas as s on (n.numerodecuenta = s.numerodecuenta) left join
			planes as t	on (s.plan_id = t.plan_id) left join terceros as u on
			(t.tipo_tercero_id = u.tipo_id_tercero AND t.tercero_id	= u.tercero_id)
			left join tipos_afiliado as v on (s.tipo_afiliado_id = v.tipo_afiliado_id)
			left join pacientes as w on (w.paciente_id= '".$_REQUEST[datos_estacion]['paciente_id']."'
			and w.tipo_id_paciente = '".$_REQUEST[datos_estacion]['tipo_id_paciente']."'),
			inv_med_cod_principios_activos as c, inventarios_productos as h,
			medicamentos as k, unidades as l

			WHERE	n.estado = '0' and a.sw_estado = '1' and
			k.cod_principio_activo = c.cod_principio_activo
			and h.codigo_producto = k.codigo_medicamento and
			a.codigo_producto = h.codigo_producto
			and h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
			$search	order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";

			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
					while (!$result->EOF)
					{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
			}
			//$result->Close();
			$var[0][uso_controlado]=$uso_controlado;
			$var[0][razon_social]=$_SESSION['ESTACION_ENFERMERIA']['EMP'];

      //obteniendo la cuota moderadora solo para cuando el plan es = 3 y sw_pos = 1
			if($_REQUEST['sw_pos']=='1' AND $var[0][sw_tipo_plan]==3)
			{
					if((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND
					(!empty($var[0][tipo_afiliado_id])))
					{
							$query="select cuota_moderadora from planes_rangos
							where plan_id = ".$var[0][plan_id]."
							AND tipo_afiliado_id = '".$var[0][tipo_afiliado_id]."'
							AND rango = '".$var[0][rango]."';";

							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
							}
							else
							{
									$cuotam=$result->GetRowAssoc($ToUpper = false);
							}
							$var[0][cuota_moderadora]=$cuotam;
					}
			}

			//obteniendo la posologia para cada medicamento DE HOSPITALIZACION que se va a imprimir en la formula medica.
			for($i=0;$i<sizeof($var);$i++)
			{
					$query == '';
					unset ($vector);
					if ($var[$i][tipo_opcion_posologia_id] == 1)
					{
							$query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
					}
					if ($var[$i][tipo_opcion_posologia_id] == 2)
					{
							$query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."' and a.duracion_id = b.duracion_id";
					}
					if ($var[$i][tipo_opcion_posologia_id] == 3)
					{
							$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
					}
					if ($var[$i][tipo_opcion_posologia_id] == 4)
					{
							$query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
					}
					if ($var[$i][tipo_opcion_posologia_id] == 5)
					{
							$query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
					}

					if ($query!='')
					{
							$result = $dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al buscar en la consulta de medicamentos recetados";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							else
							{
								if ($var[$i][tipo_opcion_posologia_id] != 4)
								{
									while (!$result->EOF)
									{
										$vector[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
									}
								}
								else
								{
									while (!$result->EOF)
									{
										$vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
									}
								}
							}
					}
					$var[$i][posologia]=$vector;
					unset($vector);
			}

			//hallando la evolucion maxima  caso especial de hospitalizacion.
			$query= "SELECT a.evolucion_id, c.nombre_tercero,
			c.tipo_id_tercero as tipo_id_medico,
			c.tercero_id as medico_id, d.tarjeta_profesional,
			e.descripcion as tipo_profesional
			FROM hc_evoluciones a, profesionales_usuarios b,
			terceros c, profesionales d,
			tipos_profesionales e where (select max (evolucion_id) from hc_evoluciones
			where ingreso = ".$var[0][ingreso]." and estado ='0') = a.evolucion_id
			and a.usuario_id = b.usuario_id
			and b.tipo_tercero_id = c.tipo_id_tercero AND b.tercero_id = c.tercero_id
			and b.tipo_tercero_id = d.tipo_id_tercero AND b.tercero_id = d.tercero_id
			and d.tipo_profesional = e.tipo_profesional";

			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			else
			{
					$medico_evol_max=$result->GetRowAssoc($ToUpper = false);
			}
			$var[0][medico_evol_max]=$medico_evol_max;

			$classReport = new reports;
			$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
			$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='EstacionE_Medicamentos',$reporte_name='formula_medica_estacion',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
			if(!$reporte)
			{
					$this->error = $classReport->GetError();
					$this->mensajeDeError = $classReport->MensajeDeError();
					unset($classReport);
					return false;
			}

			$resultado=$classReport->GetExecResultado();
			unset($classReport);

			if(!empty($resultado[codigo]))
			{
					"El PrintReport retorno : " . $resultado[codigo] . "<br>";
			}
   }
	 $this->FrmImpresionMedicamentos();
	 return true;
}
//funciones medicamentos.


/*$num es el numero de opcion que escogio en el combo */
	/*$busca es la busqueda*/
	function GetFiltro($num,$busca)
	{
          switch($num)
          {
               case "1":
               {
                    $buscar = trim($busca);
                    if(is_numeric($buscar))
                    {
                         $filtro="AND a.codigo_producto like '%".$buscar."%'";
                         //$filtro="AND a.codigo_producto='".$buscar."'";
                    }
                    else
                    {
                         $filtro="";
                    }
                    break;
               }
               case "2":
               {
	               $buscar = strtolower(trim($busca));
                    if(!empty($buscar))
                    {
                         $filtro="AND lower(b.descripcion) like '%".$buscar."%'";
                    }
                    break;
               }
          }
          return $filtro;
	}


	
	//funcion para insertar las solicitudes de insumos para pacientes, por ejemplo el paciente xxx
	//se consigue los insumos por fuera de la clinica
	
	function Insertar_Solicitud_Insumos_Para_Paciente()
	{
		$estacion=$_REQUEST["datos_estacion"];
		$datos_estacion=$_REQUEST["datos_pac"];
          $bodega=$_REQUEST['bodega'];
		$op=$_REQUEST['op'];
		$cant=$_REQUEST['cant'];
		$area=$_REQUEST['area'];
		$nom=$_REQUEST['nom'];
		$nom=$_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_NOM'];
		$area=$_SESSION['MEDICA_DATOS_SOL_PAC']['SOL_PAC_AREA'];

		list($dbconn) = GetDBconn();
		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_pacientes_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$solicitud=$res->fields[0];

		$query="INSERT INTO hc_solicitudes_medicamentos_pacientes
						(
						 solicitud_id,
						 ingreso,
						 usuario_id,
						 sw_estado,
						 fecha_solicitud,
						 observaciones,
						 nombre_recibe_solicitud,
						 estacion_id,
						 tipo_solicitud
						)VALUES('$solicitud',
						 		".$datos_estacion[ingreso].",
						 		".UserGetUID().",
								'0',
								'".date("Y-m-d H:i:s")."',
								'$area',
								'$nom',
								'".$estacion[estacion_id]."',
								'I'
								)";
          $dbconn->StartTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No (x) se inserto en hc_solicitudes_medicamentos_pacientes ";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

							
          for($i=1;$i<=sizeof($_SESSION['EXISTENCIA']);$i++)							
          {			
               foreach($_SESSION['EXISTENCIA'][$i] as $index=>$valor)
               {
                    $dat_op=explode("*",$_SESSION['EXISTENCIA'][$i][$index]);
                    $query="INSERT INTO hc_solicitudes_medicamentos_pacientes_d
                                        (
                                        solicitud_id,
                                        codigo_producto,
                                        cantidad,
                                        ingreso
                                        )VALUES('$solicitud',
                                                  '".$dat_op[0]."',
                                                  '".$dat_op[1]."',
                                                  ".$datos_estacion[ingreso]."
                                             )";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
               }
          }
          $dbconn->CompleteTrans();   //termina la transaccion
          unset($_SESSION['EXISTENCIA']);
          unset($_SESSION['MEDICA_DATOS_SOL_PAC']);
          $this->frmError["MensajeError"]="INSUMOS PARA PACIENTES SOLICITADOS SATISFACTORIAMENTE.";
//		$this->AgregarInsumos_A_Paciente($estacion,$datos_estacion);
          $this->FrmMedicamentos($estacion,$datos_estacion);
          return true;
     }
	
	
	
	
	
	
	//esta funcion genera la solicitud de insumos 
	function InsertarInsumosPaciente()
	{
		$estacion=$_REQUEST["datos_estacion"];
		$datos_estacion=$_REQUEST["datos_pac"];
          $bodega=$_REQUEST['bodega'];
		$op=$_REQUEST['op'];
		$cant=$_REQUEST['cant'];

		list($dbconn) = GetDBconn();
		$query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$solicitud=$res->fields[0];


          $query="INSERT INTO hc_solicitudes_medicamentos
                         (
                              solicitud_id,
                              ingreso,
                              bodega,
                              empresa_id,
                              centro_utilidad,
                              usuario_id,
                              sw_estado,
                              fecha_solicitud,
                              estacion_id,
                              tipo_solicitud
                              )VALUES('$solicitud',
                                   ".$datos_estacion[ingreso].",
                                   '".$bodega."',
                                   '".$estacion[empresa_id]."',
                                   '".$estacion[centro_utilidad]."',
                                   ".UserGetUID().",
                                   '0',
                                   '".date("Y-m-d H:i:s")."',
                                   '".$estacion[estacion_id]."',
                                   'I')";
          $dbconn->StartTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_medicamentos ";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=1;$i<=sizeof($_SESSION['EXISTENCIA']);$i++)							
          {
               foreach($_SESSION['EXISTENCIA'][$i] as $index=>$valor)
               {
                    $dat_op=explode("*",$_SESSION['EXISTENCIA'][$i][$index]);
                    $query="INSERT INTO hc_solicitudes_insumos_d
                                   (
                                        solicitud_id,
                                        codigo_producto,
                                        cantidad
                                   )VALUES('$solicitud',
                                           '".$dat_op[0]."',
                                           '".$dat_op[1]."')";
                    
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }

               }
                         
		}
		$dbconn->CompleteTrans();   //termina la transaccion
		unset($_SESSION['EXISTENCIA']);
		$this->frmError["MensajeError"]="INSUMOS SOLICITADOS SATISFACTORIAMENTE.";
//		$this->AgregarInsumos_A_Paciente($estacion,$datos_estacion);
		$this->FrmMedicamentos($estacion,$datos_estacion);
		return true;
	}
	

	//aqui nos damos cuenta si ya se despacharon los medicamentos
	function GetPacientesConMedicamentosPorDesp($ingreso,$letra,$estacion)
	{
			list($dbconn) = GetDBconn();
		$query="SELECT COUNT(*) FROM hc_solicitudes_medicamentos
											WHERE ingreso='".$ingreso."'
											AND sw_estado='2'
											AND tipo_solicitud='$letra'
											AND estacion_id='$estacion'";
											
											$resulta=$dbconn->execute($query);
								if ($dbconn->ErrorNo() != 0) {
											$this->error = "Error al Cargar el Modulo";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											return false;
								}
								if($resulta->fields[0]>0)
								{
										return 1;
								}
								return '';
	}
	
	
	/**
	*		Revisar_Relacion_Medicamento_Bodegas
	*
	*		obtiene la estacion asociada a una bodega.
	*
	*		@Author Jairo Duvan Diaz M.
	*		@access Public
	*		@return array, false ó string
	*		@param array => datos de la ubicacion actual (dpto, EE, CU, usuario, etc)
	*/
	function Revisar_Relacion_Medicamento_Bodegas($codigo,$bodega,$cadena)
	{
		list($dbconn) = GetDBconn();
		if(strlen($cadena)>0)
		{
			$filtro="AND a.insumo_id IN($cadena)";
		}
		if(!empty($codigo))
		{
			$filtro2="AND	a.medicamento_id = '$codigo'";
		}
               
          $query="SELECT a.medicamento_id,a.insumo_id,b.codigo_producto,b.descripcion,a.cantidad 
                    FROM hc_solicitudes_relacion_medicamento_insumos a,
                    inventarios_productos b
                    WHERE
                    a.insumo_id = b.codigo_producto 
                    $filtro2
                    $filtro";

          $result=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while (!$result->EOF)
          {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
          }
          return $var;
	}

	function total_suministro($codigo_producto, $evolucion_id,$ingreso)
     {	
     	list($dbconn) = GetDBconn();
          $query = "select sum(a.cantidad_suministrada) as totalitario
          		from hc_control_suministro_medicamentos a, 
                    hc_medicamentos_recetados_hosp b, 
                    hc_evoluciones c, ingresos d 
                    where a.codigo_producto = '$codigo_producto' 
                    and a.evolucion_id = b.evolucion_id 
                    and a.codigo_producto = b.codigo_producto 
                    and a.evolucion_id = c.evolucion_id 
                    and c.ingreso = d.ingreso and d.ingreso = $ingreso;";
          
          $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar el medicamento";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
          list($total) = $result->FetchRow();
          return $total;
     }
     
     
	function Consultar_Control_Suministro($codigo_producto, $evolucion_id,$ingreso)
	{
		list($dbconn) = GetDBconn();
		//trae todo lo del ingreso
		$query= "select a.hc_control_suministro_id, a.codigo_producto,
			a.evolucion_id, a.usuario_id_control, a.fecha_realizado,
			a.fecha_registro_control, a.cantidad_suministrada, a.observacion,
			e.nombre, g.nombre as nombre_usuario

			from hc_control_suministro_medicamentos a left join profesionales_usuarios f on
			(a.usuario_id_control = f.usuario_id) left join profesionales e on
			(f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id )
			left join system_usuarios g on (a.usuario_id_control = g.usuario_id ),
			hc_medicamentos_recetados_hosp b, hc_evoluciones c, ingresos d

			where a.codigo_producto = '".$codigo_producto."'
			and a.evolucion_id = b.evolucion_id and a.codigo_producto = b.codigo_producto
			and a.evolucion_id = c.evolucion_id and	 c.ingreso = d.ingreso and
			d.ingreso =  ".$ingreso."
			order by a.hc_control_suministro_id desc";


			//trae todo lo de la evolucion en especial
			$query1= "select a.hc_control_suministro_id, a.codigo_producto,
			  a.evolucion_id,	a.usuario_id_control, a.fecha_realizado,
			a.fecha_registro_control,	a.cantidad_suministrada, a.observacion,
			e.nombre, g.nombre as nombre_usuario

			from hc_control_suministro_medicamentos a left join profesionales_usuarios f on
			(a.usuario_id_control = f.usuario_id) left join profesionales e on
  			(f.tipo_tercero_id = e.tipo_id_tercero and f.tercero_id = e.tercero_id)
			left join system_usuarios g on (a.usuario_id_control = g.usuario_id),
			hc_medicamentos_recetados_hosp b

			where a.codigo_producto = '".$codigo_producto."'
			and a.evolucion_id = '".$evolucion_id."' and
			a.evolucion_id = b.evolucion_id	and a.codigo_producto = b.codigo_producto
			order by a.hc_control_suministro_id";

		//and a.evolucion_id = ".$evolucion_id."
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al consultar el medicamento";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{ 
          	$i=0;
			while (!$result->EOF)
			{
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
			}
		}
		$result->Close();
	  	return $vector;
	}




/*********************FUNCIONES SUMATORIA PARA BODEGAS DE PACIENTES**********/
     function Sumatorias_Cantidades_Para_Bodegas_De_Pacientes($ingreso,$estacion,$codigo)
     {
	//(+)
          list($dbconn) = GetDBconn();
          $sql="SELECT CASE WHEN SUM(d.cantidad) ISNULL THEN 0 ELSE SUM(d.cantidad) END 
                    FROM
                    ingresos a,cuentas b,cuentas_detalle c, bodegas_documentos_d d, bodegas_doc_numeraciones e,inventarios_productos f,
                    hc_solicitudes_medicamentos p
          
                    WHERE a.ingreso='$ingreso'
                    AND a.ingreso=b.ingreso
                    AND	b.numerodecuenta=c.numerodecuenta
                    AND	c.consecutivo is not null
                    AND	c.consecutivo=d.consecutivo
                    AND d.bodegas_doc_id=e.bodegas_doc_id
                    AND	d.codigo_producto=f.codigo_producto
                    AND	d.codigo_producto='$codigo'
                    AND p.bodegas_doc_id=d.bodegas_doc_id
                    AND p.numeracion=d.numeracion";

		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
		}

		$sumatoria_despacho=$result->fields[0];//sumatoria de despacho
		
		
		//(-)
          $sql= "SELECT CASE WHEN SUM(b.cantidad) ISNULL THEN 0 ELSE SUM(b.cantidad) END  FROM inv_solicitudes_devolucion a,
                    inv_solicitudes_devolucion_d b,bodegas_documentos_d c
                    WHERE
                    a.ingreso = ".$ingreso."
                    AND a.documento=b.documento
                    AND a.empresa_id='".$estacion[empresa_id]."'
                    AND a.centro_utilidad='".$estacion[centro_utilidad]."'
                    AND a.estacion_id='".$estacion[estacion_id]."'
                    AND a.estado='0'
                    AND b.codigo_producto='".$codigo."'
                    AND a.bodegas_doc_id=c.bodegas_doc_id
                    AND a.numeracion=c.numeracion";
          $result = $dbconn->Execute($sql);
					
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
	
          $sumatoria_devolucion=$result->fields[0];//sumatoria de devolucion.
			
          //(-)
          $query= "SELECT CASE WHEN SUM(a.cantidad_suministrada) ISNULL THEN 0 ELSE 
                    SUM(a.cantidad_suministrada) END FROM 
                    hc_control_suministro_medicamentos a,
                    hc_evoluciones b
                    WHERE
                    b.ingreso = ".$ingreso."
                    AND a.codigo_producto='".$codigo."'
                    AND a.evolucion_id=b.evolucion_id
                    ";
                    //Estado de la evolucion 1=Activa, 0=Cerrada 

		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
			
		$sumatoria_suministro=$result->fields[0];//sumatoria de devolucion.
		
		//(-)
		 
		$query= "SELECT CASE WHEN SUM(cantidad) ISNULL THEN 0 ELSE SUM(cantidad)  END  FROM 
                    hc_control_suministro_medicamentos_perdidas 
                    WHERE
                    ingreso = ".$ingreso."
                    AND codigo_producto='".$codigo."'";
          $result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
          $sumatoria_suministro_perdidas=$result->fields[0];//sumatoria de devolucion.

          //(+)
          //preguntar despues si hay que filtrar estado=0
	   	$query= "SELECT CASE WHEN SUM(z.cantidad) ISNULL THEN 0 ELSE SUM(z.cantidad) END
                    FROM
                    hc_recepcion_medicamentos_pacientes x,
                    hc_recepcion_medicamentos_pacientes_d z
                    WHERE
                    x.ingreso = $ingreso 
                    AND	z.codigo_producto = '$codigo' 
                    AND x.recepcion_id=z.recepcion_id";
			
          $result = $dbconn->Execute($query);
			
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
			
          $sumatoria_recepcion=$result->fields[0];//sumatoria de devolucion.
			
          //(-)
          $query= "SELECT CASE WHEN SUM(cantidad) ISNULL THEN 0 ELSE SUM(cantidad) END FROM 
                    hc_devolucion_medicamentos_pacientes 
                    WHERE
                    ingreso = ".$ingreso."
                    $filtro
                    AND codigo_producto='".$codigo."'";
                    $result = $dbconn->Execute($query);
			
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
		$sumatoria_devolucion_paciente=$result->fields[0];//sumatoria de devolucion.
		
          $SUMATORIA=$sumatoria_despacho-$sumatoria_devolucion-
          $sumatoria_suministro-$sumatoria_suministro_perdidas+$sumatoria_recepcion-$sumatoria_devolucion_paciente;
          return $SUMATORIA;
	}
/********************FUNCIONES SUMATORIA PARA BODEGAS DE PACIENTES**********/



	//Funcion q carga a la cuenta los medicamentos consumidos por el paciente.
	//funcion que inserta los suministros a un determinado paciente
	function InsertarSuministroPaciente()
	{
		$estacion=$_REQUEST['estacion'];
		$datos_estacion=$_REQUEST['datos_estacion'];
		$tipo_solicitud=$_REQUEST['tipo_solicitud'];
		$fecha_realizado=$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora'].":".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos'];
		$bodega=$_REQUEST['bodega'];//aca va el id de la bodega solamente
		list($dbconn) = GetDBconn();
		$dbconn->StartTrans();//inicia la transaccion
		//si es diferente de esto es por que es una bodega,no una bodega de paciente
		if($bodega !="*/*")
		{
		
               //funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
               //las solicitudes de medicamentos.
               $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
               $res=$dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se pudo traer la secuencia ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    return false;
               }
               
               $solicitud=$res->fields[0];
               //if($tipo_solicitud=='M'){$letra="M";}else{$letra="I";}
               
               $query="INSERT INTO hc_solicitudes_medicamentos
                              (
                                   solicitud_id,
                                   ingreso,
                                   bodega,
                                   empresa_id,
                                   centro_utilidad,
                                   usuario_id,
                                   sw_estado,
                                   fecha_solicitud,
                                   estacion_id,
                                   tipo_solicitud
                              )VALUES( '$solicitud',
                                        ".$datos_estacion[ingreso].",
                                        '".$bodega."',
                                        '".$estacion[empresa_id]."',
                                        '".$estacion[centro_utilidad]."',
                                        ".UserGetUID().",
                                        '4',
                                        '".date("Y-m-d H:i:s")."',
                                        '".$estacion[estacion_id]."',
                                        'M')";
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                    $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
														 
               $query="INSERT INTO hc_solicitudes_medicamentos_d
                              (
                                   solicitud_id,
                                   medicamento_id,
                                   cant_solicitada,
                                   evolucion_id
                              )VALUES( '$solicitud',
                                        '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['codigo_producto']."',
                                        '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['cant']."',
                                        ".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['evolucion_id'].")";
                                                                                     
               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                    $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
                              
			//$dbconn->CompleteTrans();   //termina la transaccion	
		
			$query="SELECT plan_id,numerodecuenta 
                       FROM cuentas WHERE ingreso='".$datos_estacion[ingreso]."'
                       AND estado='1'";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "No se encontro plan ni cuenta  ";
				$this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
		
               unset($_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']);		
               $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['SOLICITUD']=$solicitud;
               $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['CUENTA']=$result->fields[1];
               $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['PLAN']=$result->fields[0];
	          $this->ReturnMetodoExterno('app','InvBodegas','user','DespachoMyIAutomatico');																
               $VALOR=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO'];

               if($VALOR ==1)//si retrona 4 es por que esta bien
               {
                    //$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS'][Mensaje]
                    $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje'];
                    $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos<br>".$query;
                    $dbconn->RollbackTrans();
                    return false;
               }
			unset($_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']);
		
		/******************hasta aca la parte de medicamentos***************/

          
          		
		/******************la parte de insumos***************/
               if(is_array($_SESSION['ESTACION_ENF_MED_VECT']['DATA']))
               {
                    //funcion que crea la solicitud de insumos automaticamente, cuando lo hacemos directamente desde 
                    //las solicitudes de medicamentos.
                    $query="SELECT NEXTVAL('public.hc_solicitudes_medicamentos_solicitud_id_seq');";
                    $res=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se pudo traer la secuencia ";
                         $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
                    $solicitud_i=$res->fields[0];
               
                    //$dbconn->StartTrans();//inicia la transaccion
                    $query="INSERT INTO hc_solicitudes_medicamentos
                                   (
                                        solicitud_id,
                                        ingreso,
                                        bodega,
                                        empresa_id,
                                        centro_utilidad,
                                        usuario_id,
                                        sw_estado,
                                        fecha_solicitud,
                                        estacion_id,
                                        tipo_solicitud
                                   )VALUES ('$solicitud_i',
                                             ".$datos_estacion[ingreso].",
                                             '".$bodega."',
                                             '".$estacion[empresa_id]."',
                                             '".$estacion[centro_utilidad]."',
                                             ".UserGetUID().",
                                             '4',
                                             '".date("Y-m-d H:i:s")."',
                                             '".$estacion[estacion_id]."',
                                             'I')";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_medicamentos ";
                         $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    
                    $vector_final=$_SESSION['ESTACION_ENF_MED_VECT']['DATA'];				
                    if(is_array($vector_final))
                    {
                         for($z=0;$z<sizeof($vector_final);$z++)
                         {
                              $datas=explode(",",$vector_final[$z]);
                              $query="INSERT INTO hc_solicitudes_insumos_d
                                             (
                                                  solicitud_id,
                                                  codigo_producto,
                                                  cantidad
                                             )VALUES ('$solicitud_i',
                                                       '".$datas[0]."',
                                                       '".$datas[1]."')";
                              $dbconn->Execute($query);
                              if ($dbconn->ErrorNo() != 0)
                              {
                                   $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                                   $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                                   $dbconn->RollbackTrans();
                                   return false;
                              }
                         }
                    }
                         //	$dbconn->CompleteTrans();   //termina la transaccion	
                    
                    $query="SELECT plan_id,numerodecuenta 
                         FROM cuentas WHERE ingreso='".$datos_estacion[ingreso]."'
                         AND estado='1'";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se encontro plan ni cuenta  ";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
                    
                    unset($_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']);		
                    $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['SOLICITUD']=$solicitud_i;
                    $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['CUENTA']=$result->fields[1];
                    $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['PLAN']=$result->fields[0];
                    $this->ReturnMetodoExterno('app','InvBodegas','user','DespachoMyIAutomatico');																
                    $VALOR=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO'];
                    
                    if($VALOR ==1)//si retrona 4 es por que esta bien
                    {
                         //$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS'][Mensaje]
                         $this->error = $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje'];
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos<br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }
                    unset($_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']);
               }//fin if(si exsite un array con los checkbox)		
                    
          }
		
          $sql= "INSERT INTO hc_control_suministro_medicamentos
                        (
                              codigo_producto,	
                              evolucion_id ,
                              usuario_id_control ,
                              fecha_realizado,
                              fecha_registro_control ,	
                              cantidad_suministrada ,
                              observacion) VALUES
                        (
                              '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['codigo_producto']."',
                              '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['evolucion_id']."',
                              '".UserGetUID()."',
                              '$fecha_realizado',
                              '".date("Y-m-d H:i:s")."',
                              '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['cant']."',
                              '".$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']."')";
          $dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar hc_control_suministro_medicamentos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $dbconn->RollbackTrans();
               return false;
          }
		$dbconn->CompleteTrans();   //termina la transaccion	
		$tipo_solicitud='M';
		unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['cant']);
		unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectHora']);
		unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['selectMinutos']);
		unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']['observacion_suministro']);
		unset($_SESSION['ESTACION_ENF_MED_VECT']['DATA']);
		$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
		$this->Control_Suministro($estacion,$datos_estacion,$datos_estacion[ingreso],$tipo_solicitud);
		return true;
	}
     
      //clzc
     function Finalizar_Medicamentos($Medicamento,$datos_estacion)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          
          $dbconn->BeginTrans();
          
          //INSERTANDO NOTA PARA LA FINALIZACION
     	$query="INSERT INTO hc_notas_suministro_medicamentos
                              (codigo_producto, evolucion_id, observacion, tipo_observacion,
                              usuario_id_nota, fecha_registro_nota)
                              VALUES
                              ('".$Medicamento['codigo_producto']."',
                               ".$Medicamento['evolucion_id'].",
                               'Finalizacion del Tratamiento (Estacion de Enfermeria)',
                               '3', ".UserGetUID().", now())";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al insertar en hc_justificaciones_no_pos_hosp_respuestas_pos";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               $this->frmError["MensajeError"]="NO HA SIDO POSIBLE GENERAR NOTA";
               $dbconn->RollbackTrans();
               return false;
          }
          //FIN DE LA INSERCION
          else
          {
               $query= "UPDATE hc_medicamentos_recetados_hosp SET  sw_estado= '0'
                        WHERE codigo_producto = '".$Medicamento['codigo_producto']."'
                        AND evolucion_id = ".$Medicamento['evolucion_id'].";";

               $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
               }
          }
          $dbconn->CommitTrans();
          return true;
	}
     
     
     //trae los insumos de la tabla inventarios
	function Get_SuministrosEstacion($bodega,$filtro)
	{
		list($dbconn) = GetDBconn();
		if($bodega=='-1')
		{$filtro_bodega="";}else{$filtro_bodega="AND a.bodega='$bodega'";}
		
		if(empty($_REQUEST['conteo'])){
               $query = "SELECT b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto
                         FROM
                              existencias_bodegas a,
                              inventarios_productos b,
                              inv_grupos_inventarios c
                         WHERE 
                              a.codigo_producto=b.codigo_producto
                              AND c.grupo_id=b.grupo_id
                              AND (c.sw_insumos='1' OR c.sw_medicamento='1')
                         $filtro_bodega
                         $filtro";
     
               $result = $dbconn->Execute($query);
               list($this->conteo)=$result->RecordCount();
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
          
          	$this->conteo=$result->RecordCount();
          }else{
      		$this->conteo=$_REQUEST['conteo'];
		}
		
          if(!$_REQUEST['Of']){
      	$Of='0';
		}else{
          $Of=$_REQUEST['Of'];
		}
		
		if($bodega=="-1" OR empty($bodega))
		{
			return '';
		}
    			
          $query="SELECT b.descripcion,b.descripcion_abreviada,producto_id,a.codigo_producto
                  FROM
                  		existencias_bodegas a,
                         inventarios_productos b,
                    	inv_grupos_inventarios c
                  WHERE 
                  		a.codigo_producto=b.codigo_producto
                    	AND c.grupo_id=b.grupo_id
                    	AND (c.sw_insumos='1' OR c.sw_medicamento='1')
                    	$filtro_bodega
                    	$filtro
                  LIMIT " . $this->limit . " OFFSET $Of";

		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de medicamentos recetados";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      		return false;
		}
          $i=0;
          while (!$result->EOF)
          {
               $vector[$i]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
               $i++;
          }
          return $vector;
	}//
     
     //esta funcion genera la solicitud de los suministros.
	function Solicitar_SuministrosEstacion()
	{
		$estacion=$_REQUEST["datos_estacion"];
          $bodega=$_REQUEST['bodega'];

          if(empty($_SESSION['ESTAR'])){
               $this->frmError["MensajeError"]="DEBE ADICIONAR PRIMERO LAS CANTIDADES DE LA SOLICITUD Y DESPUES GUARDARLAS.";
			$this->SolSuministros_x_estacion($estacion,$bodega);
               return true;
		}
          
          list($dbconn) = GetDBconn();
		$query="SELECT NEXTVAL('public.hc_solicitudes_suministros_estacion_solicitud_id_seq');";
		$res=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "No se pudo traer la secuencia ";
			$this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}

		$solicitud=$res->fields[0];


          $query="INSERT INTO hc_solicitudes_suministros_estacion
                         (
                              solicitud_id,
                              empresa_id,
                              centro_utilidad,
                              bodega,
                              estacion_id,
                              usuario_id,
                              fecha_registro                             
                              )VALUES('$solicitud',
                                   '".$estacion[empresa_id]."',
                                   '".$estacion[centro_utilidad]."',
                                   '".$bodega."',
                                   '".$estacion[estacion_id]."',
                                   ".UserGetUID().",
                                   now())";
          $dbconn->StartTrans();
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "No se inserto en hc_solicitudes_suministros_estacion";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener las mezclas recetadas.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               $dbconn->RollbackTrans();
               return false;
          }

          for($i=1;$i<=sizeof($_SESSION['ESTAR']);$i++)							
          {
               foreach($_SESSION['ESTAR'][$i] as $index=>$valor)
               {
                    $dat_op=explode("*",$_SESSION['ESTAR'][$i][$index]);
                    $query="INSERT INTO hc_solicitudes_suministros_estacion_detalle
                                   (
                                        solicitud_id,
                                        codigo_producto,
                                        cantidad,
                                        cantidad_despachada,
                                        sw_estado
                                   )VALUES('$solicitud',
                                           '".$dat_op[0]."',
                                           '".$dat_op[1]."',
                                           0,
                                           '0')";
                    
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "No se inserto en hc_solicitudes_insumos_d ";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         $dbconn->RollbackTrans();
                         return false;
                    }

               }
                         
		}
		$dbconn->CompleteTrans();//termina la transaccion
		unset($_SESSION['ESTAR']);
		$this->frmError["MensajeError"]="SUMINISTROS DE ESTACION SOLICITADOS SATISFACTORIAMENTE.";
		$this->SolSuministros_x_estacion($estacion,$bodega);
		return true;
	}
     
     function GetSolicitudes_x_Estacion($estacion,$bodega)
     {
	     GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_busqueda = "SELECT DISTINCT A.*
          				FROM hc_solicitudes_suministros_estacion A 
          				WHERE estacion_id = '".$estacion[estacion_id]."'
                              AND bodega = ".$bodega."
                              ORDER BY solicitud_id DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_busqueda);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }

	    
     function GetSuministrosSolicitadosConfirmar_x_Estacion($solicitud)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_solicitud = "SELECT A.*, B.descripcion, B.descripcion_abreviada, C.cantidad
          				FROM  hc_solicitudes_suministros_estacion_detalle AS A,
                              	 inventarios_productos AS B,
                                    hc_solicitudes_suministros_est_x_confirmar AS C
          				WHERE solicitud_id = ".$solicitud."
                              AND A.consecutivo = C.consecutivo
                              AND bodegas_doc_id IS NULL
                              AND numeracion IS NULL
                              AND B.codigo_producto = A.codigo_producto
                              AND (A.sw_estado='1' OR A.sw_estado='2')
                              ORDER BY consecutivo ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_solicitud);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }
     

     function GetSuministrosSolicitadosCancelar_x_Estacion($solicitud)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
		$query_solicitud = "SELECT A.*, B.descripcion, B.descripcion_abreviada
          				FROM  hc_solicitudes_suministros_estacion_detalle AS A,
                              	 inventarios_productos AS B
          				WHERE solicitud_id = ".$solicitud."
                              AND B.codigo_producto = A.codigo_producto
                              AND A.sw_estado='0'
                              ORDER BY consecutivo ASC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query_solicitud);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
               $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          while ($datos = $resultado->FetchRow())
          {
          	$solicitudes[] = $datos;
          }
          return $solicitudes;
     }
     
     function AccionCancelCon_Solicitud()
     {
     	$opcion = $_REQUEST['opcion'];
          $despacho = $_REQUEST['despachos'];
          $estacion = $_REQUEST['estacion'];
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          
     	if($_REQUEST['accion'] == 'confirmar')
          {
          	$this->ConfirmarSolicitud_ConSuministro($opcion,$despacho,$estacion,$bodega,$SWITCHE);
          }elseif($_REQUEST['accion'] == 'cancelar')
          {
          	$this->CancelarSolicitud_ConSuministro($opcion,$despacho,$estacion,$bodega,$SWITCHE);
          }
          return true;
     }
     
     
     function ConfirmarSolicitud_ConSuministro($opcion,$despacho,$estacion,$bodega,$SWITCHE,$sw)
     {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          for($i=0; $i<$despacho; $i++)
          {
          	$datos = explode(",",$opcion[$i]);
               if($datos[1] != '')
               {
                     $query ="SELECT confirmacion_id 
                    		FROM hc_solicitudes_suministros_est_x_confirmar
	                         WHERE consecutivo = ".$datos[1]."
                              AND bodegas_doc_id IS NULL
                              AND numeracion IS NULL;";
                    
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $resultado = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                    
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
                    while ($datos = $resultado->FetchRow())
                    {
                         $corfirmacion[] = $datos['confirmacion_id'];
                    }
               }
          }

          $_SESSION['SUMINISTRO_X_ESTACION']['Empresa'] = $estacion[empresa_id];
          $_SESSION['SUMINISTRO_X_ESTACION']['CentroUtili'] = $estacion[centro_utilidad];
          $_SESSION['SUMINISTRO_X_ESTACION']['BodegaId'] = $bodega;
          $_SESSION['SUMINISTRO_X_ESTACION']['CONFIRMACIONES'] = $corfirmacion;
          
          $this->CallMetodoExterno('app','InvBodegas','user','EgresoConfirmacionesSuministros');
          $this->frmError["MensajeError"]= $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE'];
          $this->ConSuministros_x_estacion($estacion,$bodega,$SWITCHE);
          return true;
     }
   
     
     
     function CancelarSolicitud_ConSuministro($opcion,$despacho,$estacion,$bodega,$SWITCHE)
     {
          list($dbconn) = GetDBconn();
          for($i=0; $i<$despacho; $i++)
          {
          	$datos = explode(",",$opcion[$i]);
               if($datos[1] != '')
               {
                    $query ="UPDATE hc_solicitudes_suministros_estacion_detalle
                             SET sw_estado='3'
                             WHERE consecutivo = ".$datos[1].";";

                    $resultado = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error en la consulta en hc_solicitudes_suministros_estacion_detalle";
                         $this->mensajeDeError = "Ocurrió un error al insertar la solicitud de insumos .<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
                         return false;
                    }
               }
          }
		$this->frmError["MensajeError"]="CANCELACION DE SUMINISTROS SATISFACTORIA.";
		$this->ConSuministros_x_estacion($estacion,$bodega,$SWITCHE);
          return true;
     }
     
         
     
     //DARLING
	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
	 function FechaStamp($fecha)
	 {
          if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          }
	}


	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
               $time[$l]=$hor;
               $hor = strtok (":");
          }

          $x = explode (".",$time[3]);
          return  $time[1].":".$time[2].":".$x[0];
	}

	
}//fin de la clase
?>
