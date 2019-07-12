<?php

/**
 * $Id: FacturacionRecepcion.inc.php,v 1.1 2007/07/17 16:32:40 carlos Exp $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
	
		function DatosPrincipales($cuenta)
		{
					$query = "select count(*)
										from cuentas as a,
										fac_facturas_cuentas l
										where a.numerodecuenta=$cuenta 
										and a.numerodecuenta=l.numerodecuenta;";
					if(!$result = ConexionBaseDatos($query))
						return false;
	
					if ($result->fields[0] > 0)
					{
						$query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
											a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
											c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
											e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
											e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
											i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
											k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
											from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
											empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
											fac_facturas_cuentas l
											where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
											and b.tipo_tercero_id=c.tipo_id_tercero
											and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
											and d.paciente_id=e.paciente_id
											and a.numerodecuenta=l.numerodecuenta  
											and l.empresa_id=i.empresa_id  
											and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
											and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
											and d.departamento_actual=h.departamento";
					}
					else
					{
						$query = "select (a.valor_total_paciente -(a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos)) as saldo,
											a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
											c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
											e.primer_apellido||' '||e.segundo_apellido||' '||e.primer_nombre||' '||e.segundo_nombre as nombre,
											e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
											i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid, i.id, j.departamento,
											k.municipio, d.fecha_registro, a.valor_total_empresa, a.total_cuenta
											from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
											empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d
											where a.numerodecuenta=$cuenta 
											and a.plan_id=b.plan_id 
											and a.empresa_id=i.empresa_id
											and b.tercero_id=c.tercero_id
											and b.tipo_tercero_id=c.tipo_id_tercero
											and d.ingreso=a.ingreso 
											and d.tipo_id_paciente=e.tipo_id_paciente
											and d.paciente_id=e.paciente_id
											and i.tipo_pais_id=j.tipo_pais_id 
											and i.tipo_dpto_id=j.tipo_dpto_id
											and i.tipo_pais_id=k.tipo_pais_id 
											and i.tipo_dpto_id=k.tipo_dpto_id 
											and i.tipo_mpio_id=k.tipo_mpio_id
											and d.departamento_actual=h.departamento";
					}
					if(!$result = ConexionBaseDatos($query))
						return false;
	
					$var=$result->GetRowAssoc($ToUpper = false);
					$result->Close();
					return $var;
		}
	
		/**
		***
		**/
		function DatosUsuario()
		{
				$query = "SELECT *
									FROM system_usuarios
									WHERE usuario_id = ".UserGetUID()."";
				if(!$resulta = ConexionBaseDatos($query))
					return false;
				$var=$resulta->GetRowAssoc($ToUpper = false);
				return $var;
		}

		/**
		***
		**/
		function ObtenerDatosFacturas($request)
		{
			$empresa = $request[request][request][EmpresaId];
			$centroutiliad = $request[request][request][CentroUtilidadId];
			$whereAgrupadasNoAgrupadas = $whereNoAgrupadas = "";
			if($request[request][request][fechaInicial] AND $request[request][request][fechaFinal])
			{
				$FI = FormatoFechaPeriodo($request[request][request][fechaInicial]);
				$FF = FormatoFechaPeriodo($request[request][request][fechaFinal]);
				$whereAgrupadasNoAgrupadas = "AND		DATE(FF.fecha_registro) >= '$FI' ";
				$whereAgrupadasNoAgrupadas .= "AND		DATE(FF.fecha_registro) <= '$FF' ";
			}
			if($request[request][request][Estado] == 0)//Facturas No recibidas
			{
				$whereAgrupadasNoAgrupadas .= "AND	FF.sw_estado IN('0') ";
			}
			if($request[request][request][Estado] == 1)//Facturas recibidas
			{
				$whereAgrupadasNoAgrupadas .= "AND	FF.sw_estado IN('1') ";
			}
			if($request[request][request][NombreUsuario])
			{
				$whereAgrupadasNoAgrupadas .= "AND	SU.nombre LIKE (UPPER('%".$request[request][request][NombreUsuario]."%')) ";
			}
			if($request[request][request][departamentos] <> -1)
			{
				$dep = explode(',',$request[request][request][departamentos]);
				$whereNoAgrupadas .= "AND	I.departamento_actual = '".$dep[1]."' ";
			}

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
			$sql .= "				SU.nombre, ";
			$sql .= "				FF.sw_estado, ";
			$sql .= "				FF.observacion_movimiento, ";
			$sql .= "				FLM.fecha_movimento::date as fecha_registro, ";
			$sql .= "				FF.usuario_id, ";
			$sql .= "				FLM.fac_log_movimiento_id, ";
			$sql .= "				FF.tipo_factura ";
			 
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
			$sql .= "				cuentas C, ";
			$sql .= "				ingresos I, ";
			$sql .= "				pacientes P, ";
			$sql .= "				planes PL, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				fac_estados_movimiento FCR ";

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
			$sql .= $whereAgrupadasNoAgrupadas;
			$sql .= $whereNoAgrupadas;
			$sql .= "AND		FF.usuario_id = SU.usuario_id ";
			$sql .= "AND		FF.sw_estado IN ('0','1') ";
			$sql .= "AND		FF.estado NOT IN ('2','3') ";//FACTURAS Q NO ESTEN ANULADAS NI ANULADAS CON NOTAS
			$sql .= "AND		FF.sw_estado = FCR.sw_estado ";

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
			$sql .= "									SELECT FLM.fac_log_movimiento_id, ";
			$sql .= "										FLM.fecha_movimento,FF.empresa_id, ";
			$sql .= "										FF.prefijo,FF.factura_fiscal,"; 
			$sql .= "										FF.fac_grupo_id_recepcion, ";
			$sql .= "										FF.usuario_id_recepcion ";
			$sql .= "									FROM fac_log_movimientos FLM, ";
			$sql .= "										fac_facturas FF ";
			$sql .= "									WHERE FLM.empresa_id = '$empresa' ";
			$sql .= "									AND FF.empresa_id = FLM.empresa_id ";  
			$sql .= "									AND	FF.prefijo = FLM.prefijo ";   
			$sql .= "									AND	FF.factura_fiscal = FLM.factura_fiscal  "; 
			$sql .= "									AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion ";   
			$sql .= "									AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion ";
			$sql .= "									AND FLM.fac_log_movimiento_id IN ";
			$sql .= "										( ";
			$sql .= "												SELECT MAX(FLM.fac_log_movimiento_id) ";
			$sql .= "												FROM fac_log_movimientos FLM, ";
			$sql .= "																	fac_facturas FF ";
			$sql .= "												WHERE FLM.empresa_id = '$empresa' ";
			$sql .= "												AND FF.empresa_id = FLM.empresa_id ";   
			$sql .= "												AND	FF.prefijo = FLM.prefijo ";   
			$sql .= "												AND	FF.factura_fiscal = FLM.factura_fiscal ";   
			$sql .= "												AND FF.fac_grupo_id_recepcion = FLM.fac_grupo_id_recepcion ";   
			$sql .= "												AND	FF.usuario_id_recepcion = FLM.usuario_id_recepcion ";
			$sql .= "												AND		FF.sw_clase_factura IN ('1') ";
			$sql .= "												AND		FF.tipo_factura IN ('3','4') ";
			$sql .= "												AND		FF.sw_estado IN ('0','1') ";
			$sql .= "												AND		FF.estado NOT IN ('2','3') ";
			$sql .= "												GROUP BY FLM.prefijo,FLM.factura_fiscal ";
			$sql .= "										) ";
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
			$sql .= $whereAgrupadasNoAgrupadas;
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
			$sql .= ")) AS B ";
			
			$sql .= "ORDER BY B.tipo_factura DESC, B.usuario_id, B.prefijo, B.factura_fiscal ";
			if(!$rst = ConexionBaseDatos($sql))
				return false;
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}

	function FacturacionRecepcion($datos)
	{
			$_SESSION['REPORTES']['VARIABLE']='facturacion_recepcion';
			unset($_SESSION['REPORTES']['RECEPCION']['ARREGLO']);
			//$dat=DatosPrincipales($datos[numerodecuenta]);
			$_SESSION['REPORTES']['RECEPCION']['ARREGLO']=$datos;

			IncludeLib("funciones_facturacion");
			$Dir="cache/facturaRecepcion.pdf";
			require_once("classes/fpdf/html_class.php");
			include_once("classes/fpdf/conversor.php");
			define('FPDF_FONTPATH','font/');
			$pdf2=new PDF();
			$pdf2->AliasNbPages();
			$pdf2->AddPage();
			$pdf2->SetFont('Arial','',6);

			$var=ObtenerDatosFacturas($datos);
				if(is_array($var) AND sizeof($var) > 0)
				{
					for($i=0; $i<sizeof($var);)
					{
						$k = $i; 
						//$estilo = 'modulo_list_oscuro'; $backgrounds = "#DDDDDD";
						$html.= "<table border=\"1\" align=\"CENTER\" width=100 CELLSPACING=\"1\" CELLPADDING=\"1\">";
						$html.= "<tr>";
						$html.= "<td width=760>&nbsp;";
						$html.= "</td>";
						$html.= "</tr>";
						$html.= "<tr>";
						$html.= "<td width=760 align=\"LEFT\">Facturador: ".$var[$k][usuario_id].' '.$var[$k][nombre]."";
						$html.= "</td>";
						$html.= "</tr>";

						$estilo='modulo_table_title';
						//$html.= "<br><table border=\"0\" align=\"center\"   width=\"100%\">";
						$html.= "<tr>";
						$html.= "<td width=50 align='CENTER'>FACTURA";
						$html.= "</td>";
						$html.= "<td width=200 align='CENTER'>CLIENTE";
						$html.= "</td>";
						if($var[$i][tipo_factura] <> '3' AND $var[$i][tipo_factura] <> '4')
						{
							$label = "PACIENTE";
						}
						else
						{
							$label = "PLAN - AGRUPADO";
						}
						$html.= "<td width=250 align='CENTER'>$label";
						$html.= "</td>";
						$html.= "<td width=60 align=\"CENTER\">VALOR";
						$html.= "</td>";

						$html.= "<td width=200 align=\"CENTER\">OBSERVACIÓN";
						$html.= "</td>";
/*						$html.= "<td width=\"8%\" align=\"CENTER\">&nbsp;";
						$html.= "</td>";*/
						$html.= "</tr>";
						
						$total_usuario = $j= 0;
						while($var[$i][usuario_id]==$var[$k][usuario_id])
						{
				
							$total_usuario += $var[$k][valor];
							$html.= "<tr>";
							$html.= "<td width=50 align=\"CENTER\">".$var[$k][prefijo].' '.$var[$k][factura_fiscal]."";
							$html.= "</td>";
							$html.= "<td width=200 align=\"LEFT\">".substr($var[$k][cliente],0,35)."";
							$html.= "</td>";
							$html.= "<td width=250 align=\"LEFT\">".$var[$k][paciente]."";
							$html.= "</td>";
							$html.= "<td width=60 align=\"RIGHT\">".$var[$k][valor]."";
							$html.= "</td>";
							if($var[$k][sw_estado] == 0)
							{
								$observacion_recepcion = "FACTURA NO RECIBIDA";
							}
							elseif($var[$k][sw_estado] == 1)
							{
								if(!empty($var[$k][observacion_movimiento]))
								{
									$observacion_recepcion = $var[$k][fecha_registro].'/'.$var[$k][observacion_movimiento];
								}
								else
								{
									$observacion_recepcion = "FACTURA RECIBIDA SIN OBSERVACIÓN";
								}
							}
							$html.= "<td width=200 align=\"LEFT\">$observacion_recepcion";
							$html.= "</td>";
							$html.= "</tr>";
							$k++;
							$j++;
						}
						
						$html.= "<tr>";
						$html.= "<td width=500 align=\"RIGHT\"><B>TOTAL : </B>";
						$html.= "</td>";
						$html.= "<td width=60 align=\"RIGHT\"><B>$".FormatoValor($total_usuario)."</B>";
						$html.= "</td>";
						$html.= "<td width=200 align=\"RIGHT\">&nbsp;";
						$html.= "</td>";
						$html.= "</tr>";
						$i = $k;
						$html.="</table>";
					}
				}
				else
				{
					$html = "<br><center><b>NO HAY MOVIMIENTO</b></center>";
				}
			$usuario = DatosUsuario();
			$html.= "<table border=\"0\" align=\"CENTER\" width=100 CELLSPACING=\"1\" CELLPADDING=\"1\">";
			$html.="<tr><td width=760 align=\"RIGHT\"><B>Impresión realizada por ".$usuario[usuario_id]." ".$usuario[nombre]."</B></td></tr>";
			$html.="<tr><td width=760 align=\"RIGHT\">".date('d-m-Y h:m')."</td></tr>";
			$html.="<tr><td width=760 align=\"RIGHT\">".GetIpAddress()."</td></tr>";
			$html.="</table>";
			$pdf2->WriteHTML($html);
			$pdf2->Output($Dir,'F');
      return true;
 }

		/**
		* Cambia el formato de la fecha de dd/mm/YYYY hh:mm:ss a YYYY-mm-dd
		* @access private
		* @return string
		* @param date fecha
		* @var    cad   Cadena con el nuevo formato de la fecha
		*/
		function FormatoFechaPeriodo($f)
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
?>
