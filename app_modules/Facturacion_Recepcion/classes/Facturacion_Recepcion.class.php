<?php
  /******************************************************************************
  * $Id: Facturacion_Recepcion.class.php,v 1.6 2007/06/26 23:29:14 carlos Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.6 $ 
	* 
  ********************************************************************************/
	IncludeClass('Facturacion_RecepcionHTML','','app','Facturacion_Recepcion');
	IncludeClass('app_Facturacion_Recepcion_user','','app','Facturacion_Recepcion');
	class Facturacion_Recepcion
	{
		var $offset = 0;
		
		function Facturacion_Recepcion(){}
		/**********************************************************************************
		* 
		* 
		* @params int $usuario Identificador del usuario
		* @return 
		***********************************************************************************/
		function ObtenerPermisos($usuario)
		{
			GLOBAL $ADODB_FETCH_MODE;
			$sql = "SELECT d.descripcion as cent,e.empresa_id,e.razon_social as emp,
								d.centro_utilidad , b.usuario_id, b.fac_grupo_id
							FROM fac_grupos_usuarios_recepcion b,centros_utilidad d,empresas e
							WHERE b.usuario_id=".$usuario."
							AND e.empresa_id=d.empresa_id
							AND d.centro_utilidad=b.centro_utilidad
							AND b.empresa_id=d.empresa_id
							ORDER BY cent";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

			if(!$resulta = $this->ConexionBaseDatos($sql))
				return false;
		
			$datos = array();

			while($data = $resulta->FetchRow())
			{
				$datos[$data['emp']][$data['cent']][$data['cent']]=$data;
			}
			
			return $datos;
		}

		/**
		***
		**/
		function ObtenerDatosFacturasCredito($empresa,$centroutiliad,$fechaInicial,$fechaFinal,$offset)
		{
			$FI = $this->FormatoFecha($fechaInicial);
			$FF = $this->FormatoFecha($fechaFinal);
			$datos = array();
			$sql = "SELECT B.* FROM ";
			$sql .= "(( ";
			$sql .= "SELECT DISTINCT CU.empresa_id, ";
			$sql .= "				CU.centro_utilidad, ";
			$sql .= "				FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "				PL.plan_descripcion AS cliente, ";
			$sql .= "				P.primer_nombre||' '||P.segundo_nombre||' '||P.primer_apellido||' '||P.segundo_apellido AS paciente, ";
			$sql .= "				FF.total_factura AS valor, ";
			//$sql .= "				FFCO.caja_id, ";
			//$sql .= "				SU.usuario_id, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				FF.sw_estado, ";
			$sql .= "				FF.observacion_movimiento, ";
			$sql .= "				FLM.fecha_movimento::date as fecha_registro, ";
			$sql .= "				FF.usuario_id, ";
			$sql .= "				FLM.fac_log_movimiento_id, ";
			$sql .= "				FF.tipo_factura ";
			/*$sql .= "				 ";*/
			 
			$sql .= "FROM 	empresas EM, ";
			$sql .= "				centros_utilidad CU, ";
			$sql .= "				fac_facturas_cuentas FFC, ";
			$sql .= "				fac_facturas FF LEFT JOIN fac_log_movimientos FLM ";
			$sql .= "				ON (";
 			$sql .= "							FF.empresa_id = FLM.empresa_id ";
 			$sql .= "							AND	FF.prefijo = FLM.prefijo ";
 			$sql .= "							AND	FF.factura_fiscal = FLM.factura_fiscal ";
 			$sql .= "							AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion ";
 			$sql .= "							AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion ";
 			$sql .= "						), ";
			//$sql .= "				fac_facturas FF LEFT JOIN fac_facturas_contado FFCO ";
			//$sql .= "				ON (FF.empresa_id = FFCO.empresa_id ";
			//$sql .= "					AND FF.prefijo = FFCO.prefijo ";
			//$sql .= "					AND FF.factura_fiscal = FFCO.factura_fiscal), ";
			$sql .= "				cuentas C, ";
			$sql .= "				ingresos I, ";
			$sql .= "				pacientes P, ";
			$sql .= "				planes PL, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				fac_estados_movimiento FCR ";
			//$sql .= "				fac_log_movimientos FLM ";
			//$sql .= "				 ";
			//$sql .= "				 ";
			//$sql .= "				 ";

			$sql .= "WHERE EM.empresa_id = '$empresa' ";
			$sql .= "AND		CU.centro_utilidad = '$centroutiliad' ";
			$sql .= "AND		CU.empresa_id = EM.empresa_id ";
			$sql .= "AND		FF.sw_clase_factura IN ('1') ";//CREDITO
			$sql .= "AND		FF.tipo_factura  NOT IN ('3','4') ";//NO AGRUPADA CAPITACION / AGRUPADA NO CAPITA
			$sql .= "AND		FFC.sw_tipo IN ('1','2') ";//CLIENTE-PARTICULAR
			$sql .= "AND		C.estado IN ('0') ";//FACTURADAS
			$sql .= "AND		CU.empresa_id = C.empresa_id ";
			$sql .= "AND		C.numerodecuenta = FFC.numerodecuenta ";
			$sql .= "AND		FFC.empresa_id = FF.empresa_id  ";
			$sql .= "AND		FFC.prefijo = FF.prefijo  ";
			$sql .= "AND		FFC.factura_fiscal = FF.factura_fiscal  ";
			$sql .= "AND		C.ingreso = I.ingreso ";
			$sql .= "AND		I.tipo_id_paciente = P.tipo_id_paciente ";
			$sql .= "AND		I.paciente_id = P.paciente_id ";
			$sql .= "AND		C.plan_id = PL.plan_id ";
			$sql .= "AND		FF.plan_id = PL.plan_id ";
			//$sql .= "AND		DATE(FF.fecha_registro) >= '$FI' ";
			//$sql .= "AND		DATE(FF.fecha_registro) <= '$FF' ";
			$sql .= "AND		FF.usuario_id = SU.usuario_id ";
			$sql .= "AND		FF.sw_estado IN ('0','1') ";
			$sql .= "AND		FF.estado NOT IN ('2','3') ";//FACTURAS Q NO ESTEN ANULADAS NI ANULADAS CON NOTAS
			$sql .= "AND		FF.sw_estado = FCR.sw_estado ";
			//$sql .= "ORDER BY FF.usuario_id,FF.prefijo,FF.factura_fiscal ";
			//$sql .= "AND		FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion ";
			//$sql .= "AND		FF.usuario_id_recepcion = FLM.usuario_id_recepcion ";

 			$sql .= ") ";
			$sql .= "UNION		 ";
			$sql .= "( "; 
  		//FACTURAS AGRUPADAS
			$sql .= "SELECT CU.empresa_id,    "; 
			$sql .= " 			CU.centro_utilidad,   ";  
			$sql .= "				FF.prefijo, ";   
			$sql .= "				FF.factura_fiscal, ";   
			$sql .= "				PL.plan_descripcion, ";   
			$sql .= "				T.nombre_tercero, "; 
			$sql .= "  			FF.total_factura AS valor, ";    
			//$sql .= "  			SU.usuario_id, ";   
			$sql .= "  			SU.nombre, ";   
			$sql .= "  			FF.sw_estado,";    
			$sql .= "  			FF.observacion_movimiento, ";   
			$sql .= " 			A.fecha_movimento::date as fecha_registro, ";
			$sql .= "				FF.usuario_id, ";
			$sql .= "				A.fac_log_movimiento_id, ";
			$sql .= "				FF.tipo_factura ";
			$sql .= "	FROM 	empresas EM, ";   
			$sql .= "				centros_utilidad CU, ";   
			$sql .= "				fac_facturas FF ";
			$sql .= "					LEFT JOIN ";
			$sql .= "								( ";
			$sql .= "								SELECT FLM.fac_log_movimiento_id, ";
			$sql .= "									FLM.fecha_movimento,FF.empresa_id, ";
			$sql .= "									FF.prefijo,FF.factura_fiscal,"; 
			$sql .= "									FF.fac_grupo_id_recepcion, ";
			$sql .= "									FF.usuario_id_recepcion ";
			$sql .= "								FROM fac_log_movimientos FLM, ";
			$sql .= "										fac_facturas FF ";
			$sql .= "								WHERE FLM.empresa_id = '$empresa' ";
			$sql .= "								AND FF.empresa_id = FLM.empresa_id ";  
			$sql .= "								AND	FF.prefijo = FLM.prefijo ";   
			$sql .= "								AND	FF.factura_fiscal = FLM.factura_fiscal  "; 
			$sql .= "								AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion ";   
			$sql .= "								AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion ";
			$sql .= "								AND FLM.fac_log_movimiento_id IN ";
			$sql .= "												( ";
			$sql .= "														SELECT MAX(FLM.fac_log_movimiento_id) ";
			$sql .= "														FROM fac_log_movimientos FLM, ";
			$sql .= "																	fac_facturas FF ";
			$sql .= "														WHERE FLM.empresa_id = '$empresa' ";
			$sql .= "														AND FF.empresa_id = FLM.empresa_id ";   
			$sql .= "														AND	FF.prefijo = FLM.prefijo ";   
			$sql .= "														AND	FF.factura_fiscal = FLM.factura_fiscal ";   
			$sql .= "														AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion ";   
			$sql .= "														AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion ";
			$sql .= "														AND		FF.sw_clase_factura IN ('1') ";
			$sql .= "														AND		FF.tipo_factura IN ('3','4') ";
			$sql .= "														AND		FF.sw_estado IN ('0','1') ";
			$sql .= "														AND		FF.estado NOT IN ('2','3') ";
			$sql .= "														GROUP BY FLM.prefijo,FLM.factura_fiscal ";
			$sql .= "												) ";
			$sql .= "								) ";
			$sql .= "								A ";    
			$sql .= "								ON ( ";  
			$sql .= "										FF.empresa_id = A.empresa_id ";   
			$sql .= "										AND	FF.prefijo = A.prefijo ";   
			$sql .= "										AND	FF.factura_fiscal = A.factura_fiscal ";   
			$sql .= "										AND FF.fac_grupo_id_recepcion = A.fac_grupo_id_recepcion ";   
			$sql .= "										AND	FF.usuario_id_recepcion = A.usuario_id_recepcion ";
			$sql .= "									), ";   
			$sql .= "							terceros T, ";
			$sql .= "							planes PL, ";    
			$sql .= "							system_usuarios SU, ";   
			$sql .= "							fac_estados_movimiento FCR ";   
			$sql .= "	WHERE EM.empresa_id = '$empresa' ";
			$sql .= "	AND		CU.centro_utilidad = '$centroutiliad' ";
			$sql .= "	AND		CU.empresa_id = EM.empresa_id ";
			$sql .= "	AND		EM.empresa_id = FF.empresa_id ";
			$sql .= "	AND		FF.sw_clase_factura IN ('1') ";
			$sql .= "	AND		FF.tipo_factura IN ('3','4') ";
			$sql .= "	AND		FF.tipo_id_tercero = T.tipo_id_tercero ";
			$sql .= "	AND		FF.tercero_id = T.tercero_id ";
			$sql .= "	AND		FF.plan_id = PL.plan_id ";
			$sql .= "	AND		T.tipo_id_tercero = PL.tipo_tercero_id ";
			$sql .= "	AND		T.tercero_id = PL.tercero_id ";
			$sql .= "	AND		PL.sw_facturacion_agrupada IN ('1') ";
			$sql .= "	AND		PL.estado IN ('1') ";
			$sql .= "	AND		PL.fecha_inicio <= now() ";
			$sql .= "	AND		PL.fecha_final >= now() ";
			$sql .= "	AND		FF.usuario_id = SU.usuario_id "; 
			$sql .= "	AND		FF.sw_estado IN ('0','1') ";
			$sql .= "	AND		FF.estado NOT IN ('2','3') ";
			$sql .= "	AND		FF.sw_estado = FCR.sw_estado ";
			//$sql .= "	ORDER BY FF.usuario_id,FF.prefijo,FF.factura_fiscal ";
			$sql .= ")) AS B ";
		//$sql .= "AND		 ";
			

			$sql2 = "SELECT COUNT(*) FROM ($sql) AS A ";

			$this->ProcesarSqlConteo($sql2,null,$offset);

			$sql .= "ORDER BY B.tipo_factura DESC, B.usuario_id, B.prefijo, B.factura_fiscal ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }

			$rst->Close();
			return $datos;
		}


		/**
		***ObtenerDatosFacturasCreditoAgrupada
		**/
		function ObtenerDatosFacturasCreditoAgrupadas($empresa,$centroutiliad,$fechaInicial,$fechaFinal,$offset)
		{
			$FI = $this->FormatoFecha($fechaInicial);
			$FF = $this->FormatoFecha($fechaFinal);

			$datos = array();
			$sql = "SELECT CU.empresa_id,    
			  				CU.centro_utilidad,    
			  				FF.prefijo,    
			  				FF.factura_fiscal,    
			  				PL.plan_descripcion,    
			  				T.nombre_tercero,    
			  				FF.total_factura AS valor,    
			  				SU.usuario_id,    
			  				SU.nombre,    
			  				FF.sw_estado,    
			  				FF.observacion_movimiento,    
			  				A.fecha_movimento::date as fecha_registro,
								FF.usuario_id,
								A.fac_log_movimiento_id,
								FF.tipo_factura 
							FROM 	empresas EM,    
												centros_utilidad CU,    
												fac_facturas FF 
												LEFT JOIN 
														(
															SELECT FLM.fac_log_movimiento_id,
																FLM.fecha_movimento,FF.empresa_id,
																FF.prefijo,FF.factura_fiscal,
																FF.fac_grupo_id_recepcion,
																FF.usuario_id_recepcion
															FROM fac_log_movimientos FLM,
																	fac_facturas FF 
															WHERE FLM.empresa_id = '$empresa' 
															AND FF.empresa_id = FLM.empresa_id    
															AND	FF.prefijo = FLM.prefijo    
															AND	FF.factura_fiscal = FLM.factura_fiscal    
															AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion    
															AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion
															AND FLM.fac_log_movimiento_id IN
																	(
																			SELECT MAX(FLM.fac_log_movimiento_id)
																			FROM fac_log_movimientos FLM,
																					fac_facturas FF 
																			WHERE FLM.empresa_id = '$empresa' 
																			AND FF.empresa_id = FLM.empresa_id    
																			AND	FF.prefijo = FLM.prefijo    
																			AND	FF.factura_fiscal = FLM.factura_fiscal    
																			AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion    
																			AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion
																			AND		FF.sw_clase_factura IN ('1') 
																			AND		FF.tipo_factura IN ('3','4') 
																			AND		FF.sw_estado IN ('0','1') 
																			AND		FF.estado NOT IN ('2','3')
																			GROUP BY FLM.prefijo,FLM.factura_fiscal
																	)
														)
													A     
													ON (   
															FF.empresa_id = A.empresa_id    
															AND	FF.prefijo = A.prefijo    
															AND	FF.factura_fiscal = A.factura_fiscal    
															AND FF.fac_grupo_id_recepcion = A.fac_grupo_id_recepcion    
															AND	FF.usuario_id_recepcion = A.usuario_id_recepcion
														),    
												terceros T, 
												planes PL,    
												system_usuarios SU,    
												fac_estados_movimiento FCR    
				
								WHERE EM.empresa_id = '$empresa' 
								AND		CU.centro_utilidad = '$centroutiliad'
								AND		CU.empresa_id = EM.empresa_id 
								AND		EM.empresa_id = FF.empresa_id 
								AND		FF.sw_clase_factura IN ('1') 
								AND		FF.tipo_factura IN ('3','4') 
								AND		FF.tipo_id_tercero = T.tipo_id_tercero 
								AND		FF.tercero_id = T.tercero_id 
								AND		FF.plan_id = PL.plan_id 
								AND		T.tipo_id_tercero = PL.tipo_tercero_id 
								AND		T.tercero_id = PL.tercero_id 
								AND		PL.sw_facturacion_agrupada IN ('1') 
								AND		PL.estado IN ('1') 
								AND		PL.fecha_inicio <= now() 
								AND		PL.fecha_final >= now() 
								AND		FF.usuario_id = SU.usuario_id  
								AND		FF.sw_estado IN ('0','1') 
								AND		FF.estado NOT IN ('2','3') 
								AND		FF.sw_estado = FCR.sw_estado 
								ORDER BY FF.usuario_id,FF.prefijo,FF.factura_fiscal   
 ";             
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }

			$rst->Close();
			return $datos;
		}

	function ActualizarMovimientoFacturasCredito($dat)
	{
			$EmpresaId = $dat[EmpresaId];
			$CentroUtilidadId = $dat[CentroUtilidadId];
			$Empresa = $dat[Empresa];
			$CentroUtilidad = $dat[CentroUtilidad];

// 			$sql = "SELECT NEXTVAL('facturas_credito_recepcion_seq');";
// 						if(!$rst = $this->ConexionBaseDatos($sql))
// 							return false;
// 							$recepcion_id=$rst->fields[0];
// 			$sql = "INSERT INTO facturas_credito_recepcion
// 							(
// 								recepcion_id,
// 								fecha_registro,
// 								usuario_id
// 							)
// 							VALUES ('$recepcion_id',now(),".UserGetUID().");";
// 						if(!$rst = $this->ConexionBaseDatos($sql))
// 							return false;
			foreach($dat as $k => $v)
			{
					if(substr_count($k,'check'))
					{
							$dat=explode('//||',$v);
							$sql = "UPDATE fac_facturas
											SET sw_estado = '1',
													observacion_movimiento = '$dat[3]',
													fac_grupo_id_recepcion = ".$_SESSION['FACTURACION_RECEPCION']['fac_grupo_id'].",
													usuario_id_recepcion = ".UserGetUID()."
											WHERE empresa_id = '$dat[0]'
											AND prefijo = '$dat[1]'
											AND factura_fiscal = $dat[2];";
								if(!$rst = $this->ConexionBaseDatos($sql))
									return false;
					}
			}
	//		$rst->Close();
// 			if(is_array($_SESSION['FACTURACION_RECEPCION']['OBSERVACION']))
// 			{
// 				foreach($_SESSION['FACTURACION_RECEPCION']['OBSERVACION'] as $k => $v)
// 				{
// 					$sql = "UPDATE fac_facturas SET observacion_movimiento = '$v[Observacion]'
// 								WHERE empresa_id = '$v[EmpresaId]'
// 								AND prefijo = '$v[Prefijo]'
// 								AND factura_fiscal = $v[Numero];";
// 	
// 								if(!$rst = $this->ConexionBaseDatos($sql))
// 									return false;
// 				}
// 			}
			//$rst->Close();
			//$fact = new Facturacion_RecepcionHTML;
			//$html = $fact->Menu($EmpresaId,$CentroUtilidadId,$Empresa,$CentroUtilidad);
			$fact = new app_Facturacion_Recepcion_user;
			$html = $fact->LlamaMenuRecepcion();
			return $html;
	}
		/**
		* Cambia el formato de la fecha de dd/mm/YYYY hh:mm:ss a YYYY-mm-dd
		* @access private
		* @return string
		* @param date fecha
		* @var    cad   Cadena con el nuevo formato de la fecha
		*/
		function FormatoFecha($f)
		{
				$fecha = explode(' ',$f);

				if($f)
				{
						$fech = strtok ($fecha[0],"/");
						for($i=0;$i<3;$i++)
						{
								$date[$i]=$fech;
								$fech = strtok ("/");
						}
						$cad = $date[2]."-".$date[1]."-".$date[0];
						return $cad;
				}
		}
		/**********************************************************************************
		* Funcion donde se seleccionan los tipos de documentos de la base de datos, 
		* su descripcion el documento asignado y el prefijo asociado
		*
		* @params	char $empresa Empresa relacionada a los documentos
		* @params char $tipodc	Tipo de documento que servira como filtro
		* @return array datos de los documentos
		***********************************************************************************/
		function ObtenerTiposDocumentos($empresa,$tipodc)
		{
			$doc = "";
			$datos = array();
			
			$sql .= "SELECT DC.documento_id, ";
			$sql .= "				DC.descripcion, ";
			$sql .= "				UC.empresa_id, ";
			$sql .= "				CU.centro_utilidad ";
			$sql .= "FROM 	empresas EM, ";
			$sql .= "				userpermisos_cuentas UC, ";
			$sql .= "				documentos DC, ";
			$sql .= "				departamentos DE, ";
			$sql .= "				centros_utilidad CU ";
			$sql .= "WHERE 	UC.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UC.empresa_id = '".$empresa."' ";
			$sql .= "AND		DC.empresa_id = UC.empresa_id ";
			$sql .= "AND		DC.documento_id = UC.documento_id ";
			$sql .= "AND		DC.empresa_id = EM.empresa_id ";
			$sql .= "AND		DE.empresa_id = EM.empresa_id ";
			$sql .= "AND		UC.departamento = DE.departamento ";
			$sql .= "AND		CU.empresa_id = EM.empresa_id ";
			$sql .= "AND		DC.sw_estado IN ('0','1') ";
			$sql .= "ORDER BY 2 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			$todos = "";
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				
				if($doc != $datos[$rst->fields[1]]['descripcion'] )
				{
					if($i > 0)
					{
						$cadena = trim($cadena);
						$cadena = str_replace(" ",",",$cadena);
						$datos[$doc]['documento_id'] = $cadena;
						$todos .= $cadena." ";
						$cadena = "";
					}
					$doc = $rst->fields[1];
				}
				$cadena .= "'".$rst->fields[0]."' ";					
				$rst->MoveNext();
				$i++;
		  }
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			$datos[$doc]['documento_id'] = $cadena;
			
			$todos .= $cadena;
			$todos = trim($todos);
			$todos = str_replace(" ",",",$todos);
			$rst->Close();
			return $datos;
		}

		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$num_reg = null,$offset=null,$limite=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$num_reg)
			{
				if(!$result = $this->ConexionBaseDatos($consulta))
					return false;

				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
			else
			{
				$this->conteo = $num_reg;
			}
			return true;
		}

		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>