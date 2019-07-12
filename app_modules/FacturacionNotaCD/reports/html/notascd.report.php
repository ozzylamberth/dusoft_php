<?php

	/**************************************************************************************
	 * $Id: notascd.report.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 *
	 * 
	 **************************************************************************************/

	class notascd_report 
	{ 
		//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
		var $datos;
		
		//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
		//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
	    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	    function notascd_report($datos=array())
	    {
			$this->datos=$datos;
	        return true;
	    }
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'<b '.$estilo.' >INFORMACIÓN DE LA FACTURA Nº '.$this->datos['factura'].'</b>',
										'subtitulo'=>'<b '.$estilo.' >Datos Nota Credito</b>',
										'logo'=>'logocliente.png',
										'align'=>'left'));
			return $Membrete;
		}
	    //FUNCION CrearReporte()
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	    function CrearReporte()
	    {
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$this->ObtenerInformacionGlosaFactura();
			$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"85%\" border=\"1\" rules=\"all\" $estilo>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td width=\"20%\"><b > ENTIDAD</b></td>\n";
			$Salida .= "				<td width=\"30%\">".$this->TerceroNombre."</td>\n";
			$Salida .= "				<td width=\"20%\"><b >".$this->TerceroTipoDoc."</b></td>\n";
			$Salida .= "				<td  width=\"30%\">".$this->TerceroDocumento."</td>\n";
			$Salida .= "			</tr><tr>\n";
			$Salida .= "				<td ><b >FACTURA Nº</b></td>\n";
			$Salida .= "				<td >".$this->FacturaNumero."</td>\n";		
			$Salida .= "				<td ><b>FECHA</b></td>\n";
			$Salida .= "				<td >".$this->FacturaFechaRegistro."</td>\n";				
			$Salida .= "			</tr><tr>\n";
			$Salida .= "				<td ><b >ENVIO Nº</b></td>\n";
			$Salida .= "				<td >".$this->EnvioNumero."</td>\n";
			$Salida .= "				<td ><b >FECHA RADICACIÓN</b></td>\n";
			$Salida .= "				<td >".$this->EnvioFechaRadicacion."</td>\n";				
			$Salida .= "			</tr><tr>\n";
			$Salida .= "				<td ><b >PLAN</b></td>\n";
			$Salida .= "				<td >".$this->PlanDescripcion."</td>\n";
			$Salida .= "				<td ><b> Nº CONTRATO</b></td>\n";
			$Salida .= "				<td >".$this->PlanNumeroContrato."</td>\n";
			$Salida .= "			</tr><tr>\n";
			$Salida .= "				<td ><b >NOMBRE USUARIO</b></td>\n";
			$Salida .= "				<td >".$this->GlosaUsuario."</td>\n";
			$Salida .= "				<td ><b>FECHA REGISTRO</b></td>\n";
			$Salida .= "				<td >".$this->GlosaFechaRegistro."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "		</table>\n";
			$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"85%\" border=\"1\" rules=\"all\" $estilo>\n";
			$Salida .= "			<tr>\n";
			$Salida .= "				<td width=\"50%\"><b $estilo>FECHA DE LA GLOSA</b></td>\n";
			$Salida .= "				<td >".$this->GlosaFechaGlosamiento."</td>\n";
			$Salida .= "			</tr>\n";
			if($this->GlosaMotivoGlosamiento != "")
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td ><b $estilo>MOTIVO DE LA GLOSA</b></td>\n";
				$Salida .= "				<td align=\"justify\">".$this->GlosaMotivoGlosamiento."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->GlosaClasificacion != "")
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td ><b $estilo>CLASIFICACIÓN DE LA GLOSA</b></td>\n";
				$Salida .= "				<td >".$this->GlosaClasificacion."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->AuditorNombre != "")
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td ><b $estilo>AUDITOR (A)</b></td>\n";
				$Salida .= "				<td >".$this->AuditorNombre."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td ><b $estilo>TIPO GLOSA DOCUMENTO</b>\n";
				
			switch($this->GlosaSwGlosaFactura)
			{
				case '0':
					$mensaje = "LA GLOSA ES SOBRE CARGOS DE LA FACTURA";
				break;
				case '1':
					$mensaje = "LA GLOSA ES SOBRE TODA LA FACTURA";
				break;
			}
				
			$Salida .= "				</td><td >\n";
			$Salida .= "					<b $estilo>".$mensaje."</b>\n";
			$Salida .= "				</td>\n";
			
			$Salida .= "			</tr>\n";
			if($this->GlosaObservacionGlosamiento != "")
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td ><b $estilo>DESCRIPCIÓN</b></td>\n";
				$Salida .= "				<td align=\"justify\">";
				$Salida .= "					".$this->GlosaObservacionGlosamiento."\n";
				$Salida .= "				</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->GlosaDocumentoCliente != "")
			{
				$Salida .= "			<tr>\n";
				$Salida .= "				<td ><b $estilo>DOCUMENTO DE GLOSAMIENTO DEL CLIENTE Nº</b></td>\n";
				$Salida .= "				<td >\n";
				$Salida .= "					".$this->GlosaDocumentoCliente."\n";
				$Salida .= "				</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr>\n";
			$Salida .= "				<td ><b $estilo>VALOR DE LA GLOSA</b></td>\n";
			$Salida .= "				<td >\n";
			$Salida .= "					".$this->GlosaValorGlosado."\n";
			$Salida .= "				</td>\n";
			$Salida .= "			</tr><tr>\n";
			$Salida .= "				<td ><b $estilo>VALOR ACEPTADO DE LA GLOSA</b></td>\n";
			$Salida .= "				<td >\n";
			$Salida .= "					".$this->GlosaValorAceptado."\n";
			$Salida .= "				</td>\n";
			$Salida .= "			</tr><tr>\n";
			$Salida .= "				<td ><b $estilo>ESTADO DE LA GLOSA</b></td>\n";
			$Salida .= "				<td >\n";
			$Salida .= "					POR CONTABILIZAR\n";
			$Salida .= "				</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "		</table><br>\n";
				
			if($this->GlosaSwGlosaFactura == '0')
			{
				$Cargos = $this->ObtenerCargosGlosados($this->GlosaId);					
				if(sizeof($Cargos) > 0)
				{
					for($i=0; $i<sizeof($Cargos);)
					{
						$j = $i;
						$SiguienteMotivo = "";
						$cargo = $insumo = false;
						$Celdas = explode("*",$Cargos[$i]);
						$NumeroCuenta = $SigNumeroCuenta = $Celdas[0];
						
						while($NumeroCuenta == $SigNumeroCuenta)
						{
							$Motivo = $Celdas[1];
							switch($Celdas[5])
							{
								case 'DT':
									$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"85%\" rules=\"all\" border=\"1\">\n";
									$Salida .= "			<tr $estilo>\n";
									$Salida .= "				<td width=\"18%\" align=\"center\"><b>NUMERO CUENTA: </b></td>\n";
									$Salida .= "				<td width=\"32%\" align=\"center\">".$Celdas [0]."</td>\n";
									$Salida .= "				<td width=\"15%\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
									$Salida .= "				<td width=\"10%\" align=\"center\">".formatoValor($Celdas[6])."</td>\n";
									$Salida .= "				<td width=\"15%\" align=\"center\"><b>VALOR ACEPTADO</b></td>\n";
									$Salida .= "				<td width=\"10%\" align=\"center\">".formatoValor($Celdas[2])."</td>\n";
									$Salida .= "			</tr>\n";
									$Salida .= "			<tr $estilo>\n";
									$Salida .= "				<td width=\"18%\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
									$Salida .= "				<td colspan=\"5\" width=\"82%\">".$Celdas[1]."</td>\n";
									$Salida .= "			</tr>\n";
								break;
								case 'DA':
									$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"85%\" border=\"1\" rules=\"all\" class=\"modulo_table_list\">\n";
									$Salida .= "			<tr $estilo>\n";
									$Salida .= "				<td width=\"18%\" colspan=\"2\" ><b>NUMERO CUENTA: </b></td>\n";
									$Salida .= "				<td width=\"32%\" colspan=\"2\" align=\"center\">".$Celdas [0]."</td>\n";
									if($Celdas[3] > 0)
									{
										$Salida .= "				<td width=\"15%\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
										$Salida .= "				<td width=\"10%\" align=\"center\">".formatoValor($Celdas[6])."</td>\n";
										$Salida .= "				<td width=\"15%\" align=\"center\"><b>VALOR ACEPTADO</b></td>\n";
										$Salida .= "				<td width=\"10%\" align=\"center\">".formatoValor($Celdas[2])."</td>\n";

									}
									else
									{
										$Salida .= "				<td colspan=\"5\" width=\"50%\"></td>\n";
									}
									
									$Salida .= "			</tr>\n";
									if($Celdas[1] != "")
									{
										$Salida .= "			<tr $estilo>\n";
										$Salida .= "				<td colspan=\"2\" width=\"18%\" align=\"center\"><b>MOTIVO DE GLOSA</b></td>\n";
										$Salida .= "				<td colspan=\"6\" width=\"82%\">".$Celdas[1]."</td>\n";
										$Salida .= "			</tr>\n";
									}
								break;
								case 'DC':
									if(!$cargo)
									{
										$Salida .= "			<tr $estilo>\n";
										$Salida .= "				<td colspan=\"8\" align=\"center\" ><b>CARGOS</b></td>\n";
										$Salida .= "			</tr>\n";
										$cargo = true;
									}
									
									if($Motivo != $SiguienteMotivo)
									{
										$SiguienteMotivo = $Celdas[1];
										$Salida .= "			<tr $estilo>\n";
										$Salida .= "				<td colspan=\"2\" width=\"18%\" align=\"center\"><b>MOTIVO DE GLOSA</b></td>\n";
										$Salida .= "				<td colspan=\"6\" width=\"82%\">".$Celdas[1]."</td>\n";
										$Salida .= "			</tr>\n";
									}
									else
									{
										$Motivo = $Celdas[1];
									}
									
									$Salida .= "			<tr $estilo>\n";
									$Salida .= "				<td width=\"9%\"  valign=\"top\" align=\"center\"><b>CARGO </b></td>\n";
									$Salida .= "				<td width=\"9%\"  valign=\"top\" align=\"center\">".$Celdas[3]."</td>\n";
									$Salida .= "				<td align=\"justify\" colspan=\"2\">".$Celdas[4]."</td>\n";
									$Salida .= "				<td width=\"15%\" valign=\"top\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
									$Salida .= "				<td width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas[6])."</td>\n";
									$Salida .= "				<td width=\"15%\" valign=\"top\" align=\"center\"><b>VALOR ACEPTADO</b></td>\n";
									$Salida .= "				<td width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas[2])."</td>\n";
									$Salida .= "			</tr>\n";
								break;
								case 'DI':
									if(!$insumo)
									{
										$Salida .= "			<tr $estilo>\n";
										$Salida .= "				<td colspan=\"8\" align=\"center\"><b>INSUMOS Y MEDICAMENTOS</b></td>\n";
										$Salida .= "			</tr>\n";
										$insumo = true;
									}
									if($Motivo != $SiguienteMotivo)
									{
										$SiguienteMotivo = $Celdas[1];
										$Salida .= "			<tr $estilo>\n";
										$Salida .= "				<td width=\"18%\" colspan=\"2\" align=\"center\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
										$Salida .= "				<td colspan=\"6\" width=\"82%\">".$Celdas[1]."</td>\n";
										$Salida .= "			</tr>\n";
									}
									else
									{
										$Motivo = $Celdas[1];
									}

									
									$Salida .= "			<tr $estilo>\n";
									$Salida .= "				<td  width=\"9%\"  valign=\"top\" align=\"center\"><b>PRODUCTO</b></td>\n";
									$Salida .= "				<td  width=\"9%\"  valign=\"top\" align=\"center\">".$Celdas[3]."</td>\n";
									$Salida .= "				<td  align=\"justify\" colspan=\"2\" >".$Celdas[4]."</td>\n";
									$Salida .= "				<td  width=\"15%\" valign=\"top\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
									$Salida .= "				<td  width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas[6])."</td>\n";
									$Salida .= "				<td  width=\"15%\" valign=\"top\" align=\"center\"><b>VALOR ACEPTADO</b></td>\n";
									$Salida .= "				<td  width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas[2])."</td>\n";
									$Salida .= "			</tr>\n";
							break;
							}
							$j++;
							$Celdas = explode("*",$Cargos[$j]);
							$SigNumeroCuenta = $Celdas[0];
						}
						$i = $j;
						$Salida .= "		</table><br>\n";
					}
				}
			} 
		    return $Salida;
		}
	
		function ObtenerInformacionGlosaFactura()
		{
			$this->EnvioNumero = $this->datos['envio'];
			$this->FacturaNumero = $this->datos['factura'];
			
			$sql  = "SELECT T.nombre_tercero,";
			$sql .= "		F.tipo_id_tercero, ";
			$sql .= "		F.tercero_id,";
			$sql .= "		TO_CHAR(F.fecha_registro,'DD/MM/YYYY'),";
			$sql .= "		TO_CHAR(E.fecha_radicacion,'DD/MM/YYYY'),";
			$sql .= "		P.num_contrato,"; 
			$sql .= "		P.plan_descripcion,";
			$sql .= "		TO_CHAR(G.fecha_glosa,'DD/MM/YYYY'),";
			$sql .= "		M.motivo_glosa_descripcion,";
			$sql .= "		G.observacion,";
			$sql .= "		TC.descripcion,";
			$sql .= "		G.documento_interno_cliente_id,";
			$sql .= "		G.valor_glosa,";
			$sql .= "		G.valor_aceptado,";
			$sql .= "		coalesce(G.auditor_id,0),";
			$sql .= "		G.sw_glosa_total_factura,";
			$sql .= "		TO_CHAR(G.fecha_registro,'DD/MM/YYYY'),";
			$sql .= "		TO_CHAR(G.fecha_cierre,'DD/MM/YYYY'),";
			$sql .= "		G.glosa_id, ";
			$sql .= "		U.nombre ";
			$sql .= "FROM terceros T,fac_facturas F,envios_detalle ED,envios E, ";
			$sql .= "	  planes P,";
			$sql .= " 	  system_usuarios U,";
			$sql .= "	  	glosas G LEFT JOIN glosas_motivos M";
			$sql .= "		ON(G.motivo_glosa_id = M.motivo_glosa_id ) ";
			$sql .= "		LEFT JOIN glosas_tipos_clasificacion TC ";
			$sql .= "		ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "WHERE ED.prefijo = F.prefijo "; 
			$sql .= "AND ED.factura_fiscal = F.factura_fiscal ";	
			$sql .= "AND ED.empresa_id = F.empresa_id ";
			$sql .= "AND ED.envio_id = E.envio_id ";
			$sql .= "AND ED.envio_id = ".$this->EnvioNumero." ";
			$sql .= "AND F.prefijo||F.factura_fiscal = '".$this->FacturaNumero."' ";
			$sql .= "AND F.tercero_id = T.tercero_id ";
			$sql .= "AND F.tipo_id_tercero = T.tipo_id_tercero ";
			$sql .= "AND F.empresa_id = '".$_SESSION['NotasCD']['empresa']."' ";
			$sql .= "AND F.sw_clase_factura = '1' ";
			$sql .= "AND F.plan_id = P.plan_id ";
			$sql .= "AND F.empresa_id = P.empresa_id ";
			$sql .= "AND F.empresa_id = G.empresa_id ";
			$sql .= "AND F.prefijo = G.prefijo ";
			$sql .= "AND F.factura_fiscal = G.factura_fiscal ";
			$sql .= "AND G.sw_estado <> '0' ";
			$sql .= "AND G.usuario_id = U.usuario_id ";
			$sql .= "AND G.sw_estado = '2' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$this->TerceroNombre = $rst->fields[0];
				$this->TerceroTipoDoc = $rst->fields[1];
				$this->TerceroDocumento = $rst->fields[2];
				$this->FacturaFechaRegistro = $rst->fields[3];
				$this->EnvioFechaRadicacion = $rst->fields[4];
				$this->PlanNumeroContrato = $rst->fields[5];
				$this->PlanDescripcion = $rst->fields[6];
				$this->GlosaFechaGlosamiento = $rst->fields[7];
				$this->GlosaMotivoGlosamiento = $rst->fields[8];
				$this->GlosaObservacionGlosamiento = $rst->fields[9];
				$this->GlosaClasificacion = $rst->fields[10];
				$this->GlosaDocumentoCliente = $rst->fields[11];
				$this->GlosaValorGlosado = $rst->fields[12];
				$this->GlosaValorAceptado = $rst->fields[13];
				$this->GlosaAuditor = $rst->fields[14];
				$this->GlosaSwGlosaFactura = $rst->fields[15];
				$this->GlosaFechaRegistro = $rst->fields[16];
				$this->GlosaFechaCierre = $rst->field[17];
				$this->GlosaId = $rst->fields[18];
				$this->GlosaUsuario = $rst->fields[19];

				if($this->GlosaAuditor != 0)
				{
					$this->AuditorNombre = $this->ObtenerUsuarioNombre($this->GlosaAuditor);
				}
									
				$rst->MoveNext();
		    }
			$rst->Close();
			
			return true;
		}
	    
    function ObtenerCargosGlosados($glosaId)
		{
			$sql  = "SELECT	C.numerodecuenta, ";
			$sql .= "		GM.motivo_glosa_descripcion,";
			$sql .= "		GC.valor_aceptado ,";
			$sql .= "		'---' ,";
			$sql .= "		'---' ,";
			$sql .= "		CASE WHEN GC.sw_glosa_total_cuenta = '0' THEN 'DA' ";
			$sql .= "		     WHEN GC.sw_glosa_total_cuenta = '1' THEN 'DT' END ,";
			$sql .= "		CASE WHEN GC.sw_glosa_total_cuenta = '0' ";
			$sql .= " 			 THEN GC.valor_glosa_copago + GC.valor_glosa_cuota_moderadora ";
			$sql .= "		     WHEN GC.sw_glosa_total_cuenta = '1' THEN C.total_cuenta END ";
			$sql .= "FROM	cuentas C,";
			$sql .= "		glosas_detalle_cuentas GC LEFT JOIN glosas_motivos GM ";
			$sql .= "			ON(GM.motivo_glosa_id = GC.motivo_glosa_id) ";
			$sql .= "WHERE	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 	C.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND	GC.sw_estado = '2' ";
			$sql .= "UNION  ";
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "		GM.motivo_glosa_descripcion, ";
			$sql .= "		GC.valor_aceptado, ";
			$sql .= "		CD.cargo,  ";
			$sql .= "		TD.descripcion, ";
			$sql .= "		'DC', ";
			$sql .= "		GC.valor_glosa ";
			$sql .= "FROM 	glosas_detalle_cargos GC, ";
			$sql .= "		cuentas_detalle CD, ";
			$sql .= "		glosas_motivos GM,";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		tarifarios_detalle TD ";
			$sql .= "WHERE 	GC.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GC.transaccion = CD.transaccion ";
			$sql .= "AND 	GC.sw_estado = '2' ";
			$sql .= "AND 	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 	TD.cargo = CD.cargo ";
			$sql .= "AND 	TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT CD.numerodecuenta, ";
			$sql .= "		GM.motivo_glosa_descripcion, ";
			$sql .= "		GI.valor_aceptado, ";
			$sql .= "		GI.codigo_producto, ";
			$sql .= "		ID.descripcion, ";
			$sql .= "		'DI', ";
			$sql .= "		GI.valor_glosa ";
			$sql .= "FROM 	glosas_detalle_inventarios GI, ";
			$sql .= "		cuentas CD, ";
			$sql .= "		glosas_motivos GM, ";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		inventarios_productos ID ";
			$sql .= "WHERE	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GI.sw_estado = '2' ";
			$sql .= "AND 	GI.glosa_id = ".$glosaId." ";
			$sql .= "AND	GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 	GD.sw_estado = '2' ";
			$sql .= "AND 	GD.glosa_id = GI.glosa_id ";
			$sql .= "ORDER BY 1,6 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$cargos[$i] = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4]."*".$rst->fields[5]."*".$rst->fields[6];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $cargos;
		}
		
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
		
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$UsuarioNombre = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $UsuarioNombre;
		}
	    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	    //---------------------------------------
	}

?>
