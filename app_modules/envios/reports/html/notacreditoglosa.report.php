<?php
	/**************************************************************************************
	* $Id: notacredito.report.php,v 1.5 2007/02/07 18:52:53 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* 
	**************************************************************************************/
	class notacreditoglosa_report 
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
		function notacreditoglosa_report($datos=array())
		{
			$this->datos=$datos;
			return true;
		}
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			
			$Membrete = array('file'=>false,
							  'datos_membrete'=>array('titulo'=>'<b '.$estilo.' >NOTA CREDITO Nº '.$this->datos['prefijo_nota'].' '.$this->datos['nota_credito_ajuste'].'</b>',
										'subtitulo'=>'<b '.$estilo.' >Datos Nota Credito</b>',
										'logo'=>'logocliente.png',
										'align'=>'left'));
			return $Membrete;
		}
	    //FUNCION CrearReporte()
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-indent:7pt\"";
			$datos = $this->ObtenerInformacionNotaCredito();

			$Salida .= "	<table width=\"80%\" align=\"center\"  $estilo border=\"1\" bordercolor=\"#000000\" cellpading=\"0\" cellspacing=\"0\">\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>ENTIDAD</b></td>\n";
			$Salida .= "			<td width=\"50%\" colspan=\"2\" >".$datos['nombre_tercero']."</td>\n";
			$Salida .= "			<td width=\"%\" ><b>".$datos['tipo_id_tercero']."</b></td>\n";
			$Salida .= "			<td width=\"%\" >".$datos['tercero_id']."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr class=\"modulo_table_list_title\" $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>Nº NOTA</b></td>\n";
			$Salida .= "			<td width=\"25%\" >".$this->datos['prefijo_nota']." ".$this->datos['nota_credito_ajuste']."</td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA REGISTRO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\">".$datos['fecha_registro']."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>Nº GLOSA</b></td>\n";
			$Salida .= "			<td width=\"25%\" align=\"left\" >".$datos['glosa_id']."</td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FACTURA</b></td>\n";
			$Salida .= "			<td width=\"25%\" align=\"left\" colspan=\"2\">".$datos['factura']."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA REGISTRO</b></td>\n";
			$Salida .= "			<td width=\"25%\" >".$datos['fecha_glosa']."</td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA CIERRE</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\">".$datos['fecha_cierre']."</td>\n";
			$Salida .= "		</tr>\n";
			if($datos['descripcion'] != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"2\" ><b>CLASIFICACIÓN</b></td>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\">".$datos['descripcion']."</td>\n";
				$Salida .= "		</tr>\n";
			}
			if($datos['documento_interno_cliente_id'] != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"2\" ><b>DOCUMENTO INTERNO DEL CLIENTE</b></td>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" >".$datos['documento_interno_cliente_id']."</td>\n";
				$Salida .= "		</tr>\n";
			}
			if($datos['nombre'] != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"2\" ><b>AUDITOR(A)</b></td>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" >".$datos['nombre']."</td>\n";
				$Salida .= "		</tr>\n";
			}

			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\" ><b>VALOR GLOSADO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\" >".formatoValor($datos['valor_glosa'])."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\" ><b>VALOR ACEPTADO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\">".formatoValor($datos['valor_aceptado'])."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\" ><b>VALOR NO ACEPTADO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\">".formatoValor($datos['valor_no_aceptado'])."</td>\n";
			$Salida .= "		</tr>\n";
			if($datos['motivo_glosa_descripcion'] != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td colspan=\"5\" align=\"center\"><b>MOTIVO GLOSA</b></td>\n";
				$Salida .= "		</tr>\n";
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td colspan=\"5\" align=\"justify\">".$datos['motivo_glosa_descripcion']."</td>\n";
				$Salida .= "		</tr>\n";
			}
			
			$observa = $this->ObtenerObservaciones($datos['glosa_id']);
			if(sizeof($observa) > 0)
			{
				$Salida .= "		<tr>\n";
				$Salida .= "			<td colspan=\"5\" align=\"center\" ><b>OBSERVACIONES HECHAS A LA RESPUESTA DE LA GLOSA</b></td>\n";
				$Salida .= "		</tr>\n";
				$Salida .= "		<tr $estilo>\n";				
				$Salida .= "			<td colspan=\"5\" align=\"justify\">\n";
				$Salida .= "				<menu>\n";
				for($i=0; $i< sizeof($observa); $i++)
					$Salida .= "			<li>".$observa[$i]['observacion']."\n";
				
				$Salida .= "				</menu>\n";
				$Salida .= "			</td>\n";
				$Salida .= "		</tr>\n";
			}
			$Salida .= "	</table><br>\n";
			
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				
			$datosC = $this->ObtenerCargosGlosados($datos['glosa_id']);					
			if(sizeof($datosC) > 0)
			{
				$Salida .= "		<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" rules=\"all\" border=\"1\" $estilo>\n";
				foreach($datosC as $key => $Cargos)
				{
					$Salida .= "			<tr $estilo>\n";
					$Salida .= "				<td colspan=\"5\" align=\"center\" ><b>$key</b></td>\n";
					$Salida .= "			</tr>\n";
					
					foreach($Cargos as $key1 => $Motivos)
					{
						$Salida .= "			<tr $estilo>\n";
						$Salida .= "				<td align=\"center\"><b>MOTIVO DE GLOSA</b></td>\n";
						$Salida .= "				<td colspan=\"4\" >".$key1."</td>\n";
						$Salida .= "			</tr>\n";
						$Salida .= "			<tr $estilo align=\"center\">\n";
						if($key == "CARGOS")
							$Salida .= "				<td><b>CARGO</b></td>\n";
						else	
							$Salida .= "				<td><b>PRODUCTO</b></td>\n";
						
						$Salida .= "				<td><b>DETALLE</b></td>\n";
						$Salida .= "				<td><b>V. GLOSA</b></td>\n";
						$Salida .= "				<td><b>V. ACEPTADO</b></td>\n";
						$Salida .= "				<td><b>V. NO ACEPTADO</b></td>\n";
						$Salida .= "			</tr>\n";
						
						foreach($Motivos as $keyII => $Celdas)
						{
							$Salida .= "			<tr $estilo>\n";
							$Salida .= "				<td width=\"9%\"  valign=\"top\" align=\"center\">".$Celdas['cargo']."</td>\n";
							$Salida .= "				<td align=\"justify\" >".$Celdas['descripcion']."</td>\n";
							$Salida .= "				<td width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas['valor_glosa'])."</td>\n";
							$Salida .= "				<td width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas['valor_aceptado'])."</td>\n";
							$Salida .= "				<td width=\"10%\" valign=\"top\" align=\"right\">".formatoValor($Celdas['valor_no_aceptado'])."</td>\n";
							$Salida .= "			</tr>\n";
						}
					}
				}
				$Salida .= "		</table><br>\n";
			}			
			
			
			$Salida .= "		<br><br>\n";
			
			($this->Usuario[1])? $cargo = "AUDITOR INTERNO ": $cargo= "";
			$usuario = $this->ObtenerUsuarioNombre($datos['nota_usuario_id']);
			$Salida .= "	<table style=\"border-top:1px solid #000000\" width=\"30%\">\n";		
			$Salida .= "		<tr class=\"label\">";
			$Salida .= "			<td>".$usuario['nombre']."</td>\n";
			$Salida .= "		</tr>";
			$Salida .= "	</table>";
			
			$usuario = $this->ObtenerUsuarioNombre(UserGetUID());
			$Salida .= "	<br><table border='0' width=\"100%\">\n";
			$Salida .= "		<tr>\n";
      $Salida .= "			<td align=\"justify\" width=\"50%\">\n";
			$Salida .= "				<font size='1' face='arial'>\n";
			$Salida .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
			$Salida .= "				</font>\n";
			$Salida .= "			</td>\n";
			$Salida .= "			<td align=\"right\" width=\"50%\">\n";
			$Salida .= "				<font size='1' face='arial'>\n";
			$Salida .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
			$Salida .= "				</font>\n";
			$Salida .= "			</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "	</table>\n";
		  return $Salida;
		}		
		/**************************************************************************************
		* Funcion donde se obtiene la informacion de una nota credito, que se haya hecho sobre 
		* un cargo o un insumo o sobre la factura
		***************************************************************************************/
		function ObtenerInformacionNotaCredito()
		{	
			$sql .= "SELECT	TO_CHAR(NG.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
			$sql .= "				U.nombre,";
			$sql .= "				T.nombre_tercero,";
			$sql .= "				T.tipo_id_tercero,";
			$sql .= "				T.tercero_id,";
			$sql .= "				G.glosa_id,";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				TC.descripcion,";
			$sql .= "				G.prefijo||' '||G.factura_fiscal AS factura,";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa,";
			$sql .= "				G.documento_interno_cliente_id,";
			$sql .= "				COALESCE(NG.valor_glosa,0) AS valor_glosa,";
			$sql .= "				COALESCE(NG.valor_aceptado,0) AS valor_aceptado,";
			$sql .= "				COALESCE(NG.valor_no_aceptado,0) AS valor_no_aceptado,";
			$sql .= "				U.nombre,";
			$sql .= "				G.usuario_id,";
			$sql .= "				TO_CHAR(G.fecha_cierre,'DD/MM/YYYY') AS fecha_cierre,";
			$sql .= "				G.observacion, ";
			$sql .= "				NG.usuario_id AS nota_usuario_id ";
			$sql .= "FROM 	glosas G LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(G.motivo_glosa_id = GM.motivo_glosa_id) ";
			$sql .= "				LEFT JOIN glosas_tipos_clasificacion TC ";
			$sql .= "				ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "				LEFT JOIN system_usuarios U ";
			$sql .= "				ON(G.auditor_id = U.usuario_id), ";
			$sql .= "		 		notas_credito_glosas NG, ";
			$sql .= "				(	SELECT	tipo_id_tercero,  ";
			$sql .= "									tercero_id ";
			$sql .= "					FROM		fac_facturas  ";
			$sql .= "					WHERE		prefijo = '".$this->datos['prefijo']."'  ";
			$sql .= "					AND 		factura_fiscal = ".$this->datos['factura_fiscal']."  ";
			$sql .= "					AND			empresa_id = '".$this->datos['empresa']."'  ";
			$sql .= "					UNION ";
			$sql .= "					SELECT	tipo_id_tercero,  ";
			$sql .= "									tercero_id ";
			$sql .= "					FROM		facturas_externas ";
			$sql .= "					WHERE		prefijo = '".$this->datos['prefijo']."'  ";
			$sql .= "					AND 		factura_fiscal = ".$this->datos['factura_fiscal']."  ";
			$sql .= "					AND			empresa_id = '".$this->datos['empresa']."'  ";
			$sql .= "				)AS F,";
			$sql .= "				terceros T ";
			$sql .= "WHERE	F.tercero_id = T.tercero_id ";
			$sql .= "AND		F.tipo_id_tercero = T.tipo_id_tercero ";
			$sql .= "AND		NG.glosa_id = G.glosa_id ";
			$sql .= "AND		NG.numero = ".$this->datos['nota_credito_ajuste']." ";
			$sql .= "AND		NG.prefijo = '".$this->datos['prefijo_nota']."' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if (!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}	
			$rst->Close();

			return $glosa;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerUsuarioNombre($id)
		{
			$sql  = "SELECT U.nombre, A.usuario_id ";
			$sql .= "FROM 	system_usuarios U LEFT JOIN auditores_internos A ";
			$sql .= "				ON(U.usuario_id = A.usuario_id) ";
			$sql .= "WHERE 	U.usuario_id = ".$id;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$usuario = array();
			if (!$rst->EOF)
			{
				$usuario = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $usuario;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerObservaciones($glosa)
		{
			$sql .= "SELECT observacion ";
			$sql .= "FROM		respuesta_glosas "; 
			$sql .= "WHERE 	glosa_id = ".$glosa." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$observaciones[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}	
			$rst->Close();
			
			return $observaciones;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerCargosGlosados($glosaId)
		{
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "		GM.motivo_glosa_descripcion, ";
			$sql .= "		GC.valor_aceptado, ";
			$sql .= "		GC.valor_no_aceptado, ";
			$sql .= "		CD.cargo,  ";
			$sql .= "		TD.descripcion, ";
			$sql .= "		'CARGOS', ";
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
			$sql .= "AND 	GC.sw_estado = '3' ";
			$sql .= "AND 	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 	TD.cargo = CD.cargo ";
			$sql .= "AND 	TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT CD.numerodecuenta, ";
			$sql .= "		GM.motivo_glosa_descripcion, ";
			$sql .= "		GI.valor_aceptado, ";
			$sql .= "		GI.valor_no_aceptado, ";
			$sql .= "		GI.codigo_producto AS cargo, ";
			$sql .= "		ID.descripcion, ";
			$sql .= "		'INSUMOS Y MEDICAMENTOS', ";
			$sql .= "		GI.valor_glosa ";
			$sql .= "FROM 	glosas_detalle_inventarios GI, ";
			$sql .= "		cuentas CD, ";
			$sql .= "		glosas_motivos GM, ";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		inventarios_productos ID ";
			$sql .= "WHERE	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GI.sw_estado = '3' ";
			$sql .= "AND 	GI.glosa_id = ".$glosaId." ";
			$sql .= "AND	GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 	GD.glosa_id = GI.glosa_id ";
			$sql .= "ORDER BY 1,6 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$cargos[$rst->fields[6]][$rst->fields[1]][] = $rst->GetRowAssoc($ToUpper = false);;
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $cargos;
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
	} 
?>