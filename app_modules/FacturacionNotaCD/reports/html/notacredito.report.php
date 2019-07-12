<?php
	/**************************************************************************************
	* $Id: notacredito.report.php,v 1.1.1.1 2009/09/11 20:36:46 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* 
	**************************************************************************************/
	class notacredito_report 
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
		function notacredito_report($datos=array())
		{
			$this->datos=$datos;
			$this->numero = $this->datos['nota_credito'];
			if(empty($this->datos['nota_credito'])) $this->numero = $this->datos['prefijo']." ".$this->datos['numero'];
			
			return true;
		}
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			
			$Membrete = array('file'=>false,
							  'datos_membrete'=>array('titulo'=>'<b '.$estilo.' >NOTA CREDITO Nº '.$this->numero.'</b>',
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
			$this->ObtenerInformacionNotaCredito();

			$Salida .= "	<table width=\"80%\" align=\"center\"  $estilo border=\"1\" bordercolor=\"#000000\" cellpading=\"0\" cellspacing=\"0\">\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>ENTIDAD</b></td>\n";
			$Salida .= "			<td width=\"50%\" colspan=\"2\" >".$this->TerceroNombre."</td>\n";
			$Salida .= "			<td width=\"%\" ><b>".$this->TerceroTipoId."</b></td>\n";
			$Salida .= "			<td width=\"%\" >".$this->TerceroId."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr class=\"modulo_table_list_title\" $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>Nº NOTA</b></td>\n";
			$Salida .= "			<td width=\"25%\" >".$this->numero."</td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA REGISTRO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\">".$this->NotaCreditoFechaRegistro."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>Nº GLOSA</b></td>\n";
			$Salida .= "			<td width=\"25%\" align=\"left\" >".$this->GlosaIdentificador."</td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FACTURA</b></td>\n";
			$Salida .= "			<td width=\"25%\" align=\"left\" colspan=\"2\">".$this->GlosaFactura."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA REGISTRO</b></td>\n";
			$Salida .= "			<td width=\"25%\" >".$this->GlosaFechaGlosamiento."</td>\n";
			$Salida .= "			<td width=\"25%\" ><b>FECHA CIERRE</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\">".$this->GlosaFechaCierre."</td>\n";
			$Salida .= "		</tr>\n";
			if($this->GlosaTipoClasificacion != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"2\" ><b>CLASIFICACIÓN</b></td>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\">".$this->GlosaTipoClasificacion."</td>\n";
				$Salida .= "		</tr>\n";
			}
			if($this->GlosaDocumentoInterno != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"2\" ><b>DOCUMENTO INTERNO DEL CLIENTE</b></td>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" >".$this->GlosaDocumentoInterno."</td>\n";
				$Salida .= "		</tr>\n";
			}
			if($this->GlosaAuditor != "")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"2\" ><b>AUDITOR(A)</b></td>\n";
				$Salida .= "			<td width=\"50%\" colspan=\"3\" align=\"left\" >".$this->GlosaAuditor."</td>\n";
				$Salida .= "		</tr>\n";
			}

			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\" ><b>VALOR GLOSADO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\" >".formatoValor($this->GlosaValorGlosado)."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\" ><b>VALOR ACEPTADO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\">".formatoValor($this->GlosaValorAceptado)."</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"2\" ><b>VALOR NO ACEPTADO</b></td>\n";
			$Salida .= "			<td width=\"25%\" colspan=\"3\" align=\"right\">".formatoValor($this->GlosaValorNoAceptado)."</td>\n";
			$Salida .= "		</tr>\n";
			if($this->GlosaMotivoGlosamiento != "" && $this->GlosaMotivoGlosamiento != "-1")
			{
				if($this->GlosaMotivoGlosamiento <> 'NINGUNO')
				{
					if($this->GlosaMotivoGlosamiento)
					{
						$Salida .= "		<tr $estilo>\n";
						$Salida .= "			<td colspan=\"5\" align=\"center\"><b>MOTIVO GLOSA</b></td>\n";
						$Salida .= "		</tr>\n";
					}
					$Salida .= "		<tr $estilo>\n";
					$Salida .= "			<td colspan=\"5\" align=\"justify\">".$this->GlosaMotivoGlosamiento."</td>\n";
					$Salida .= "		</tr>\n";
				}
			}
			if(!empty($this->DescripcionCG) AND $this->DescripcionCG <> " ")
			{
				$Salida .= "		<tr $estilo>\n";
				$Salida .= "			<td colspan=\"1\" align=\"left\"><b>C. GENERAL / ESPECIFICO</b></td>\n";
				$Salida .= "			<td colspan=\"4\" align=\"justify\">".$this->DescripcionCCG."&nbsp;".$this->DescripcionCG." / ".$this->DescripcionCCE."&nbsp;".$this->DescripcionCE."</td>\n";
				$Salida .= "		</tr>\n";
			}
			else
			{
				$conceptos = $this->ObtenerConceptosCargosGlosados($this->datos['glosa']);
				if(!empty($conceptos))
        {
          $Salida .= "		<tr $estilo>\n";
  				$Salida .= "			<td colspan=\"5\" align=\"center\"><b>C. GENERAL / ESPECIFICO</b></td>\n";
  				$Salida .= "		</tr>\n";
  				foreach($conceptos AS $i => $v)
  				{
  					
  					$Salida .= "		<tr $estilo>\n";
  					$Salida .= "			<td colspan=\"5\" align=\"justify\">".$v[codigo_concepto_general]."&nbsp;".$v[descripcion_concepto_general]." / ".$v[codigo_concepto_especifico]."&nbsp;".$v[descripcion_concepto_especifico]."</td>\n";
  					$Salida .= "		</tr>\n";
  				}
				}
			}
			$observa = $this->ObtenerObservaciones();
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
			
			/*if($this->datos['codigo'] != 'NT')
			{*/
				$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
				
				$datosC = $this->ObtenerCargosGlosados($this->datos['glosa']);
        
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
								$Salida .= "			<tr $estilo>\n";
								$Salida .= "				<td align=\"center\" width=\"15%\"><b>C. GENERAL / ESPECIFICO</b></td>\n";
								$Salida .= "				<td colspan=\"4\" >".$Celdas[codigo_concepto_general]."&nbsp;".$Celdas[descripcion_concepto_general]." / ".$Celdas[codigo_concepto_especifico]."&nbsp;".$Celdas[descripcion_concepto_especifico]."</td>\n";
								$Salida .= "			</tr>\n";
                
                if(!empty($Celdas[observacion])){
                  $Salida .= "			<tr $estilo>\n";
                  $Salida .= "				<td align=\"center\" width=\"15%\"><b>OBSERVACION</b></td>\n";
                  $Salida .= "				<td colspan=\"4\" >".$Celdas[observacion]."</td>\n";
                  $Salida .= "			</tr>\n";
                }  
							}
						}
					}
					$Salida .= "		</table><br>\n";
				}			
			/*}*/
			
			$Salida .= "		<br><br>\n";
			
			($this->Usuario[1])? $cargo = "AUDITOR INTERNO ": $cargo= "";
			$usuario = $this->ObtenerUsuarioNombre($this->NotaCreditoUsuario);
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
			$glosa_id = $this->datos['glosa'];
			$numero = array();
			if(empty($this->datos['nota_credito']))
			{
				$numero[0] = $this->datos['prefijo'];
				$numero[1] = $this->datos['numero'];
			}
			else
				$numero = explode(" ",$this->datos['nota_credito']);
				
			$sql .= "SELECT	NC.prefijo||' '||NC.numero AS nota,";
			$sql .= "				TO_CHAR(NC.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
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
			
			/*if($this->datos['codigo'] == "NT")
			{*/			
				$sql .= "				COALESCE(NC.valor_glosa,0) AS valor_glosa,";
				$sql .= "				COALESCE(NC.valor_aceptado,0) AS valor_aceptado,";
				$sql .= "				COALESCE(NC.valor_no_aceptado,0) AS valor_no_aceptado,";
			/*}
			else
			{	
				$sql .= "				COALESCE(NC.valor_glosa,0) + COALESCE(NI.valor_glosa,0) AS valor_glosa,";
				$sql .= "				COALESCE(NC.valor_aceptado,0) + COALESCE(NI.valor_aceptado,0) AS valor_aceptado,";
				$sql .= "				COALESCE(NC.valor_no_aceptado,0) + COALESCE(NI.valor_no_aceptado,0) AS valor_no_aceptado,";
			}	*/
			
			$sql .= "				U.nombre,";
			$sql .= "				G.usuario_id,";
			$sql .= "				TO_CHAR(G.fecha_cierre,'DD/MM/YYYY') AS fecha_cierre,";
			$sql .= "				G.observacion, ";
			$sql .= "				NC.usuario_id AS nota_usuario_id, ";
			$sql .= "				CG.descripcion_concepto_general, ";
			$sql .= "				CE.descripcion_concepto_especifico, ";
			$sql .= "				CG.codigo_concepto_general, ";
			$sql .= "				CE.codigo_concepto_especifico ";
			$sql .= "FROM 	glosas G LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(G.motivo_glosa_id = GM.motivo_glosa_id) ";
			$sql .= "				LEFT JOIN glosas_tipos_clasificacion TC ";
			$sql .= "				ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "				LEFT JOIN system_usuarios U ";
			$sql .= "				ON(G.auditor_id = U.usuario_id) ";
			$sql .= "				LEFT JOIN glosas_concepto_general CG ";
			$sql .= "				ON(CG.codigo_concepto_general = G.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "				ON(CE.codigo_concepto_especifico = G.codigo_concepto_especifico) ";
			$sql .= "				LEFT JOIN ";
			/*if($this->datos['codigo'] == "NT")
			{*/
				$sql .= "				(	SELECT 	prefijo,numero,glosa_id,fecha_registro,usuario_id, ";
				$sql .= "									SUM(valor_glosa) AS valor_glosa,";
				$sql .= "									SUM(valor_aceptado) AS valor_aceptado,";
				$sql .= "									SUM(valor_no_aceptado) AS valor_no_aceptado ";
				$sql .= "		 			FROM 		notas_credito_glosas ";
				$sql .= "		 			WHERE  	glosa_id = ".$glosa_id." ";
				$sql .= "		 			AND			numero = ".$numero[1]." ";
				$sql .= "		 			GROUP BY 1,2,3,4,5) AS NC ";
				$sql .= "				ON(	NC.glosa_id = G.glosa_id), ";
			/*}
			else
			{
				$sql .= "		 		(	SELECT 	prefijo,numero,glosa_id,fecha_registro,usuario_id,";
				$sql .= "									SUM(valor_glosa) AS valor_glosa,";
				$sql .= "									SUM(valor_aceptado) AS valor_aceptado,";
				$sql .= "									SUM(valor_no_aceptado) AS valor_no_aceptado ";
				$sql .= "		 			FROM 		notas_credito_glosas_detalle_cargos ";
				$sql .= "		 			WHERE  	glosa_id =".$glosa_id." ";
				$sql .= "		 			AND			numero = ".$numero[1]." ";
				$sql .= "		 			GROUP BY 1,2,3,4,5) AS NC ";
				$sql .= "				ON(	NC.glosa_id = G.glosa_id) ";
				$sql .= "		 		LEFT JOIN ";
				$sql .= "		 		(	SELECT 	prefijo,numero,glosa_id,fecha_registro,usuario_id, ";
				$sql .= "									SUM(valor_glosa) AS valor_glosa,";
				$sql .= "									SUM(valor_aceptado) AS valor_aceptado,";
				$sql .= "									SUM(valor_no_aceptado) AS valor_no_aceptado ";
				$sql .= "		 			FROM		notas_credito_glosas_detalle_inventarios ";
				$sql .= "		 			WHERE  glosa_id = ".$glosa_id." ";
				$sql .= "		 			AND		numero = ".$numero[1]." ";
				$sql .= "		 			GROUP BY 1,2,3,4,5 ";
				$sql .= "				)AS NI ";
				$sql .= "				ON(	NI.glosa_id = G.glosa_id), ";
			}*/
			$sql .= "				(	SELECT	F.tipo_id_tercero,  ";
			$sql .= "									F.tercero_id, ";
			$sql .= "									G.glosa_id ";
			$sql .= "					FROM		fac_facturas F, ";
			$sql .= "									glosas G ";
			$sql .= "					WHERE		G.prefijo = F.prefijo  ";
			$sql .= "					AND 		G.factura_fiscal = F.factura_fiscal  ";
			$sql .= "					AND			G.glosa_id = ".$glosa_id."  ";
			$sql .= "					UNION ";
			$sql .= "					SELECT	F.tipo_id_tercero,  ";
			$sql .= "									F.tercero_id, ";
			$sql .= "									G.glosa_id ";
			$sql .= "					FROM		facturas_externas F, ";
			$sql .= "									glosas G ";
			$sql .= "					WHERE		G.prefijo = F.prefijo  ";
			$sql .= "					AND 		G.factura_fiscal = F.factura_fiscal  ";
			$sql .= "					AND			G.glosa_id = ".$glosa_id."  ";
			$sql .= "				)AS F,";
			$sql .= "				terceros T ";
			$sql .= "WHERE	G.glosa_id = F.glosa_id ";
			$sql .= "AND 		F.tercero_id = T.tercero_id ";
			$sql .= "AND		F.tipo_id_tercero = T.tipo_id_tercero ";
			
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if (!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}	
			$rst->Close();		  
			

			$this->TerceroId = $glosa['tercero_id'];
			$this->GlosaFactura = $glosa['factura'];
			$this->GlosaAuditor = $glosa['nombre'];
			$this->TerceroNombre = $glosa['nombre_tercero'];
			$this->TerceroTipoId = $glosa['tipo_id_tercero'];
			$this->GlosaFechaCierre = $glosa['fecha_cierre'];
			$this->GlosaObservacion = $glosa['observacion'];
			$this->GlosaResponsable = $glosa['usuario_id'];
			$this->GlosaValorGlosado = $glosa['valor_glosa'];
			$this->NotaCreditoNumero = $glosa['nota'];
			$this->GlosaIdentificador = $glosa['glosa_id'];
			$this->GlosaValorAceptado = $glosa['valor_aceptado'];
			$this->GlosaValorNoAceptado = $glosa['valor_no_aceptado'];
			$this->GlosaFechaGlosamiento = $glosa['fecha_glosa'];
			$this->GlosaDocumentoInterno = $glosa['documento_interno_cliente_id'];
			$this->GlosaMotivoGlosamiento = $glosa['motivo_glosa_descripcion'];
			$this->GlosaTipoClasificacion = $glosa['descripcion'];
			$this->NotaCreditoResponsable = $glosa['nombre'];
			$this->NotaCreditoUsuario = $glosa['nota_usuario_id'];
			$this->NotaCreditoFechaRegistro = $glosa['fecha_registro'];
			$this->DescripcionCG = $glosa['descripcion_concepto_general'];
			$this->DescripcionCE = $glosa['descripcion_concepto_especifico'];
			$this->DescripcionCCG = $glosa['codigo_concepto_general'];
			$this->DescripcionCCE = $glosa['codigo_concepto_especifico'];

			return true;
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
		function ObtenerObservaciones()
		{
			$sql .= "SELECT observacion ";
			$sql .= "FROM		respuesta_glosas "; 
			$sql .= "WHERE 	glosa_id = ".$this->datos['glosa']." ";
			
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
			$sql .= "		GC.valor_glosa, ";
			$sql .= "		CG.descripcion_concepto_general, ";
			$sql .= "		CE.descripcion_concepto_especifico, ";
			$sql .= "		CG.codigo_concepto_general, ";
			$sql .= "		CE.codigo_concepto_especifico, ";
      $sql .= "		RGC.observacion ";
			$sql .= "FROM 	glosas_detalle_cargos GC ";
			$sql .= "		LEFT JOIN glosas_concepto_general CG ";
			$sql .= "		ON(CG.codigo_concepto_general = GC.codigo_concepto_general) ";
			$sql .= "		LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "		ON(CE.codigo_concepto_especifico = GC.codigo_concepto_especifico) ";
      $sql .= "		LEFT JOIN respuesta_glosas_detalle_cargos RGC ";
			$sql .= "		ON(RGC.glosa_detalle_cargo_id = GC.glosa_detalle_cargo_id), ";
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
			$sql .= "		GI.valor_glosa, ";
			$sql .= "		CG.descripcion_concepto_general, ";
			$sql .= "		CE.descripcion_concepto_especifico, ";
			$sql .= "		CG.codigo_concepto_general, ";
			$sql .= "		CE.codigo_concepto_especifico, ";
      $sql .= "		RGI.observacion ";
			$sql .= "FROM 	glosas_detalle_inventarios GI ";
			$sql .= "		LEFT JOIN glosas_concepto_general CG ";
			$sql .= "		ON(CG.codigo_concepto_general = GI.codigo_concepto_general) ";
			$sql .= "		LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "		ON(CE.codigo_concepto_especifico = GI.codigo_concepto_especifico) ";
      $sql .= "		LEFT JOIN respuesta_glosas_detalle_inventarios RGI ";
			$sql .= "		ON(RGI.glosa_detalle_inventario_id = GI.glosa_detalle_inventario_id), ";
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
		function ObtenerConceptosCargosGlosados($glosaId)
		{
			$sql .= "SELECT	 ";
			$sql .= "		'CARGOS', ";
			$sql .= "		CG.descripcion_concepto_general, ";
			$sql .= "		CE.descripcion_concepto_especifico, ";
			$sql .= "		CG.codigo_concepto_general, ";
			$sql .= "		CE.codigo_concepto_especifico ";
			$sql .= "FROM 	glosas_detalle_cargos GC ";
			$sql .= "		LEFT JOIN glosas_concepto_general CG ";
			$sql .= "		ON(CG.codigo_concepto_general = GC.codigo_concepto_general) ";
			$sql .= "		LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "		ON(CE.codigo_concepto_especifico = GC.codigo_concepto_especifico), ";
      $sql .= "		cuentas_detalle CD, ";
			$sql .= "		glosas_motivos GM,";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		tarifarios_detalle TD ";
			$sql .= "WHERE 	GC.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GC.transaccion = CD.transaccion ";
			//$sql .= "AND 	GC.sw_estado = '3' ";
			$sql .= "AND 	GC.sw_estado NOT IN ('0') ";
			$sql .= "AND 	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 	TD.cargo = CD.cargo ";
			$sql .= "AND 	TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT ";
			$sql .= "		'INSUMOS Y MEDICAMENTOS', ";
			$sql .= "		CG.descripcion_concepto_general, ";
			$sql .= "		CE.descripcion_concepto_especifico, ";
			$sql .= "		CG.codigo_concepto_general, ";
			$sql .= "		CE.codigo_concepto_especifico ";
			$sql .= "FROM 	glosas_detalle_inventarios GI ";
			$sql .= "		LEFT JOIN glosas_concepto_general CG ";
			$sql .= "		ON(CG.codigo_concepto_general = GI.codigo_concepto_general) ";
			$sql .= "		LEFT JOIN glosas_concepto_especifico CE ";
			$sql .= "		ON(CE.codigo_concepto_especifico = GI.codigo_concepto_especifico), ";
      $sql .= "		cuentas CD, ";
			$sql .= "		glosas_motivos GM, ";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		inventarios_productos ID ";
			$sql .= "WHERE	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 	GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			//$sql .= "AND 	GI.sw_estado = '3' ";
			$sql .= "AND 	GI.sw_estado NOT IN ('0') ";
			$sql .= "AND 	GI.glosa_id = ".$glosaId." ";
			$sql .= "AND	GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 	GD.glosa_id = GI.glosa_id ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$cargos[] = $rst->GetRowAssoc($ToUpper = false);;
				$rst->MoveNext();
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