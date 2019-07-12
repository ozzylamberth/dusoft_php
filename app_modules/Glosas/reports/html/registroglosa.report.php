<?php

	/**************************************************************************************
	 * $Id: registroglosa.report.php,v 1.1 2009/09/02 13:02:28 hugo Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 * 
	 **************************************************************************************/
	include_once "./app_modules/Glosas/classes/Glosas.class.php";
	class registroglosa_report 
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
	  function registroglosa_report($datos=array())
	  {
			$this->datos=$datos;
			$this->datos['sw_estado'] = str_replace("\'","",SessionGetVar("EstadoGlosaBuscar"));
			if($this->datos['sw_estado'])
			$this->datos['sw_estado'] = str_replace("'","",$this->datos['sw_estado']);
			//$this->datos['sw_estado'] = "'1','2'";
	    return true;
	  }
		
		function GetMembrete()
		{
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10pt\"";
			
			$idempresa = $_SESSION['glosas']['tipo_id']." ".$_SESSION['glosas']['id'];
			$titulo .= "<b $estilo >".$_SESSION['glosas']['razon_social']."<br>";
			$titulo .= $idempresa."<br>";
			$titulo .= "REGISTRO DE GLOSA<br>";
			$titulo .= "ESTADO DE LA GLOSA: ";
			switch($this->datos['sw_estado'])
			{
				case '0': $titulo .= "ANULADA"; break;
				case '1': $titulo .= "POR REVISAR"; break;
				case '2': $titulo .= "POR CONTABILIZAR"; break;
				case '3': $titulo .= "CERRADA"; break;
			}
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}
	  //FUNCION CrearReporte()
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	  {
			$gls = new Glosas();			
			$this->ObtenerInformacionGlosaFactura();
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"80%\" class=\"label\">\n";
			$Salida .= "		<tr>";
			$Salida .= "			<td align=\"left\"><b>Fecha ".date("d/m/Y h:i a")."</td>";
			$Salida .= "		</tr>";
			$Salida .= "		<tr>";
			$Salida .= "			<td align=\"left\"><b>Usuario: ".$gls->ObtenerUsuarioNombre(UserGetUID())."</td>";
			$Salida .= "		</tr>";
			$Salida .= "	</table><br>";
			
			$Salida .= "		<table border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" width=\"80%\"  rules=\"all\" >\n";
			$Salida .= "			<tr class=\"label\">\n";
			$Salida .= "				<td width=\"20%\"><b > GLOSA Nº</b></td>\n";
			$Salida .= "				<td width=\"30%\">".$this->GlosaId."</td>\n";
			$Salida .= "				<td width=\"25%\"><b >FECHA GLOSA</b></td>\n";
			$Salida .= "				<td width=\"25%\" colspan=\"2\">".$this->GlosasFechaGlosa."</td>\n";
			$Salida .= "			</tr><tr class=\"label\">\n";
			$Salida .= "				<td ><b >EMPRESA</b></td>\n";
			$Salida .= "				<td colspan=\"2\">".$this->TerceroNombre."</td>\n";		
			$Salida .= "				<td width=\"8%\"><b>".$this->TerceroTipoId."</b></td>\n";
			$Salida .= "				<td >".$this->TerceroId."</td>\n";				
			$Salida .= "			</tr><tr class=\"label\">\n";
			$Salida .= "				<td ><b >FACTURA</b></td>\n";
			$Salida .= "				<td >".$this->FacturaNumero."</td>\n";
			$Salida .= "				<td ><b>DOCUMENTO CLIENTE Nº</b></td>\n";
			$Salida .= "				<td colspan=\"2\">".$this->GlosasDocumentoInterno."</td>\n";				
			$Salida .= "			</tr><tr class=\"label\">\n";
			$Salida .= "				<td ><b>VALOR</b></td>\n";
			$Salida .= "				<td align=\"right\">".formatoValor($this->FacturaTotal)."&nbsp;&nbsp;</td>\n";
			$Salida .= "				<td ><b>VALOR GLOSA</b></td>\n";
			$Salida .= "				<td align=\"right\" colspan=\"2\">".formatoValor($this->GlosasValor)."&nbsp;&nbsp;</td>\n";
			$Salida .= "			</tr>\n";
			if($this->PlanDescripcion)
			{
				$Salida .= "			<tr class=\"label\">\n";
				$Salida .= "				<td><b>PLAN</b></td>\n";
				$Salida .= "				<td colspan=\"4\">".$this->PlanDescripcion."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->GlosasMotivo != "" AND $this->GlosasMotivo != 'NINGUNO')
			{
				$Salida .= "			<tr class=\"label\">\n";
				$Salida .= "				<td ><b>MOTIVO GLOSA</b></td>\n";
				$Salida .= "				<td colspan=\"4\">".$this->GlosasMotivo."</td>\n";
				$Salida .= "			</tr>\n";
			}
			if($this->DescripcionCG)
			{
				$Salida .= "			<tr class=\"label\">\n";
				$Salida .= "				<td ><b>CONCEPTO GENERAL</b></td>\n";
				$Salida .= "				<td align=\"left\" >\n";
				$Salida .= "				".$this->DescripcionCCG." ".$this->DescripcionCG."\n";
				$Salida .= "				</td>\n";
				$Salida .= "				<td ><b>CONCEPTO ESPECIFICO</b></td>\n";
				$Salida .= "				<td align=\"left\" colspan=\"2\">\n";
				$Salida .= "				".$this->DescripcionCCE." ".$this->DescripcionCE."\n";
				$Salida .= "				</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "			<tr class=\"label\">\n";
			$Salida .= "				<td ><b>AUDITOR</b></td>\n";
			$Salida .= "				<td >".$this->GlosasAuditor."&nbsp;</td>\n";
			
			if($this->GlosasClasificacion != "")
			{
				$Salida .= "				<td ><b>TIPO GLOSA</b></td>\n";
				$Salida .= "				<td colspan=\"2\">".$this->GlosasClasificacion."</td>\n";
			}
			else
			{
				$Salida .= "				<td colspan=\"3\">&nbsp;</td>\n";
			}
			
			$Salida .= "			</tr>\n";
			
			if($this->GlosasObservacion != "")
			{
				$Salida .= "			<tr class=\"label\">\n";
				$Salida .= "				<td colspan=\"5\" align=\"center\"><b>OBSERVACION</b></td>\n";
				$Salida .= "			</tr><tr class=\"label\">\n";
				$Salida .= "				<td colspan=\"5\">".$this->GlosasObservacion."</td>\n";
				$Salida .= "			</tr>\n";
			}
			
			$Salida .= "		</table><br>\n";
	
			if($this->GlosaSwGlosaFactura == '0')
			{ 				
				$Cargos = $this->ObtenerCargosGlosados();			
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
									$Salida .= "		<table bordercolor=\"#000000\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"80%\" border=\"1\" rules=\"all\" $estilo>\n";
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td width=\"20%\"><b>NUMERO CUENTA: </b></td>\n";
									$Salida .= "				<td width=\"30%\" align=\"center\">".$Celdas [0]."</td>\n";
									$Salida .= "				<td width=\"30%\" colspan=\"2\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
									$Salida .= "				<td width=\"20%\" align=\"right\">".formatoValor($Celdas[2])."&nbsp;&nbsp;</td>\n";
									$Salida .= "			</tr>\n";
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td width=\"20%\"><b>PACIENTE: </b></td>\n";
									$Salida .= "				<td width=\"45%\" colspan=\"2\">".$Celdas [4]."</td>\n";
									$Salida .= "				<td width=\"15%\"><b>IDENTIFICACION</b></td>\n";
									$Salida .= "				<td width=\"20%\"  colspan=\"2\" align=\"center\">".$Celdas[3]."</td>\n";
									$Salida .= "			</tr>\n";
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td  width=\"20%\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
									$Salida .= "				<td  colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
									$Salida .= "			</tr>\n";
								break;
								case 'DA':
									$Salida .= "		<table  bordercolor=\"#000000\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"80%\" border=\"1\" rules=\"all\" $estilo>\n";
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td width=\"20%\" colspan=\"2\" ><b>NUMERO CUENTA: </b></td>\n";
									$Salida .= "				<td width=\"30%\" align=\"center\">".$Celdas [0]."</td>\n";
									if($Celdas[2] > 0)
									{
										$Salida .= "				<td width=\"30%\" colspan=\"2\" align=\"center\"><b>VALOR GLOSA COPAGO - CUOTA</b></td>\n";
										$Salida .= "				<td width=\"20%\" align=\"right\">".formatoValor($Celdas[2])."&nbsp;&nbsp;</td>\n";
									}
									else
									{
										$Salida .= "				<td colspan=\"3\" width=\"50%\"></td>\n";
									}
									
									$Salida .= "			</tr>\n";
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td width=\"20%\" colspan=\"2\"><b>PACIENTE: </b></td>\n";
									$Salida .= "				<td width=\"45%\" colspan=\"2\">".$Celdas [4]."</td>\n";
									$Salida .= "				<td width=\"15%\"><b>IDENTIFICACION</b></td>\n";
									$Salida .= "				<td width=\"20%\" align=\"center\">".$Celdas[3]."</td>\n";
									$Salida .= "			</tr>\n";
									if($Celdas[1] != "")
									{
										$Salida .= "			<tr class=\"label\">\n";
										$Salida .= "				<td  width=\"20%\" colspan=\"2\" align=\"center\"><b $estilo>MOTIVO DE GLOSA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>\n";
										$Salida .= "				<td  colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
										$Salida .= "			</tr>\n";
									}
								break;
								case 'DC':
									if(!$cargo)
									{
										$Salida .= "			<tr class=\"label\">\n";
										$Salida .= "				<td colspan=\"8\" align=\"center\" ><b>CARGOS</b></td>\n";
										$Salida .= "			</tr>\n";
										$cargo = true;
									}

									if($Motivo != $SiguienteMotivo)
									{
										$SiguienteMotivo = $Celdas[1];
										//$Salida .= "			<tr class=\"label\">\n";
										//$Salida .= "				<td  colspan=\"2\" width=\"20%\" align=\"center\"><b $estilo>MOTIVO DE GLOSA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>\n";
										//$Salida .= "				<td  colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
										//$Salida .= "			</tr>\n";
									}
									else
									{
										$Motivo = $Celdas[1];
									}
									$dat=$this->ObtenerDescripcionConceptos($Celdas[6],$Celdas[7]);
									$conceptos = explode("||//",$dat);
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td  width=\"10%\" align=\"center\"><b>CARGO </b></td>\n";
									$Salida .= "				<td  width=\"10%\" align=\"center\">".$Celdas[3]."</td>\n";
									$Salida .= "				<td  align=\"justify\" colspan=\"2\" >".$Celdas[4]."</td>\n";
									$Salida .= "				<td  width=\"10%\" >".$Celdas[6]."".$Celdas[7]."</td>\n";
									$Salida .= "				<td  width=\"10%\" align=\"center\">".$conceptos[0]."</td>\n";
									$Salida .= "				<td  align=\"justify\" >".$conceptos[1]."</td>\n";
									$Salida .= "				<td  width=\"15%\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
									$Salida .= "				<td  width=\"20%\" align=\"right\">".formatoValor($Celdas[2])."&nbsp;&nbsp;</td>\n";
									$Salida .= "			</tr>\n";
								break;
								case 'DI':
									if(!$insumo)
									{
										$Salida .= "			<tr class=\"label\">\n";
										$Salida .= "				<td colspan=\"6\" align=\"center\"><b>INSUMOS Y MEDICAMENTOS</b></td>\n";
										$Salida .= "			</tr>\n";
										$insumo = true;
										$Salida .= "			<tr class=\"label\">\n";
										$Salida .= "				<td colspan=\"4\" align=\"center\">&nbsp;</td><td align=\"center\">C. GENERAL</td><td align=\"center\">C. ESPECIFICO</td><td colspan=\"2\" align=\"center\">&nbsp;</td>\n";
										$Salida .= "			</tr>\n";
										$insumo = true;
									}
									
									if($Motivo != $SiguienteMotivo)
									{
										$SiguienteMotivo = $Celdas[1];
										$Salida .= "			<tr class=\"label\">\n";
										$Salida .= "				<td  width=\"20%\" colspan=\"2\" align=\"center\"><b $estilo>MOTIVO DE GLOSA</b></td>\n";
										$Salida .= "				<td  colspan=\"4\" width=\"80%\">".$Celdas[1]."</td>\n";
										$Salida .= "			</tr>\n";
									}
									else
									{
										$Motivo = $Celdas[1];
									}

									$dat=$this->ObtenerDescripcionConceptos($Celdas[6],$Celdas[7]);
									$conceptos = explode("||//",$dat);
														
									$Salida .= "			<tr class=\"label\">\n";
									$Salida .= "				<td  width=\"20%\" colspan=\"2\" align=\"center\"><b $estilo>PRODUCTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>\n";
									$Salida .= "				<td  align=\"justify\" colspan=\"2\" >".$Celdas[4]."</td>\n";
                  $Salida .= "				<td  width=\"10%\" >".$Celdas[6]."".$Celdas[7]."</td>\n";
									$Salida .= "				<td  width=\"10%\" align=\"center\">".$conceptos[0]."</td>\n";
									$Salida .= "				<td  align=\"justify\" >".$conceptos[1]."</td>\n";
									$Salida .= "				<td  width=\"15%\" align=\"center\"><b>VALOR GLOSA</b></td>\n";
									$Salida .= "				<td  align=\"right\">".formatoValor($Celdas[2])."&nbsp;&nbsp;</td>\n";
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
			
			$Salida .= "		<table border=\"1\" align =\"center\" cellpading=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" width=\"80%\"  rules=\"all\" $estilo>\n";
			$Salida .= "			<tr class=\"label\">\n";
			$Salida .= "				<td width=\"20%\"><b>USUARIO</b></td>\n";
			$Salida .= "				<td width=\"30%\">".$this->GlosasResponsable."</td>\n";
			$Salida .= "				<td width=\"25%\"><b >FECHA REGISTRO</b></td>\n";
			$Salida .= "				<td  width=\"25%\">".$this->GlosasFechaRegistro."</td>\n";
			$Salida .= "			</tr>\n";
			$Salida .= "		</table><br>\n";
			
	        return $Salida;
		}

		function ObtenerInformacionGlosaFactura()
		{
			$this->GlosaId = $this->datos['glosa'];
			$this->Sistema = $this->datos['sistema']; 
			
			$sql  = "SELECT G.observacion, ";
			$sql .= "				G.auditor_id,";
			$sql .= "				G.valor_glosa,";
			$sql .= "				G.documento_interno_cliente_id,"; 
			$sql .= "				G.sw_glosa_total_factura, ";
			$sql .= "				TO_CHAR(G.fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa, ";
			$sql .= "				M.motivo_glosa_descripcion,";
			$sql .= "				TC.descripcion,";
			$sql .= "				U.nombre,";
			$sql .= "				G.prefijo,";
			$sql .= "				G.factura_fiscal, ";
      $sql .= "       G.motivo_glosa_id, ";
			$sql .= "				GCG.descripcion_concepto_general, ";
			$sql .= "				GCE.descripcion_concepto_especifico, ";
			$sql .= "				GCG.codigo_concepto_general, ";
			$sql .= "				GCE.codigo_concepto_especifico ";
			$sql .= "FROM 	system_usuarios U, ";
			$sql .= "				glosas G LEFT JOIN glosas_motivos M";
			$sql .= "				ON(G.motivo_glosa_id = M.motivo_glosa_id) LEFT JOIN ";
			$sql .= "				glosas_tipos_clasificacion TC ";
			$sql .= "				ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "				LEFT JOIN glosas_concepto_general GCG ";
			$sql .= "				ON(G.codigo_concepto_general = GCG.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico GCE ";
			$sql .= "				ON(G.codigo_concepto_especifico = GCE.codigo_concepto_especifico) ";
			$sql .= "WHERE 	G.glosa_id = ".$this->GlosaId." ";
			$sql .= "AND 		G.empresa_id = '".$_SESSION['glosas']['empresa_id']."' ";
			//$sql .= "AND 		G.sw_estado = '".$this->datos['sw_estado']."' ";
			$sql .= "AND 		G.usuario_id = U.usuario_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$this->FacturaP = $glosa['prefijo'];
			$this->FacturaF = $glosa['factura_fiscal'];
			$this->GlosasValor = $glosa['valor_glosa'];
			if($glosa['motivo_glosa_id'] != '-1')
        $this->GlosasMotivo = $glosa['motivo_glosa_descripcion'];
			
      $this->GlosasFechaGlosa = $glosa['fecha_glosa'];
			$this->GlosasObservacion = $glosa['observacion'];
			$this->GlosasResponsable = $glosa['nombre'];
			$this->GlosasClasificacion = $glosa['descripcion'];
			$this->GlosaSwGlosaFactura = $glosa['sw_glosa_total_factura'];
			$this->GlosasFechaRegistro = $glosa['registro'];
			$this->GlosasDocumentoInterno = $glosa['documento_interno_cliente_id'];
			$this->DescripcionCG = $glosa['descripcion_concepto_general'];
			$this->DescripcionCE = $glosa['descripcion_concepto_especifico'];
			$this->DescripcionCCG = $glosa['codigo_concepto_general'];
			$this->DescripcionCCE = $glosa['codigo_concepto_especifico'];
			//if($this->GlosasAuditor)
			$this->GlosasAuditor = $this->ObtenerUsuarioNombre($glosa['auditor_id']);
			
			$this->ObtenerInformacionFactura();
			return true;
		}
	  /*****************************************************************************************
	  *
	  ******************************************************************************************/  
	  function ObtenerCargosGlosados()
		{			
			$sql  = "SELECT	C.numerodecuenta, ";
			$sql .= "		GM.motivo_glosa_descripcion,";
			$sql .= "		CASE WHEN GC.sw_glosa_total_cuenta = '0' ";
			$sql .= " 			 THEN GC.valor_glosa_copago + GC.valor_glosa_cuota_moderadora ";
			$sql .= "		     WHEN GC.sw_glosa_total_cuenta = '1' THEN C.total_cuenta END ,";
			$sql .= "		PA.tipo_id_paciente||' '||PA.paciente_id, ";
			$sql .= "		PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido, ";
			$sql .= "		CASE WHEN GC.sw_glosa_total_cuenta = '0' THEN 'DA' ";
			$sql .= "		     WHEN GC.sw_glosa_total_cuenta = '1' THEN 'DT' END, ";
			$sql .= "		GC.codigo_concepto_general, ";
			$sql .= "		GC.codigo_concepto_especifico ";
			$sql .= "FROM	planes P, ";
			$sql .= "		ingresos I,";
			$sql .= "		pacientes PA,"; 
			$sql .= "		cuentas C,";
			$sql .= "		glosas_detalle_cuentas GC LEFT JOIN glosas_motivos GM ";
			$sql .= "			ON(GM.motivo_glosa_id = GC.motivo_glosa_id) ";
			$sql .= "WHERE	GC.glosa_id = ".$this->GlosaId." ";
			$sql .= "AND 	C.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND 	C.ingreso = I.ingreso ";
			$sql .= "AND 	I.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND 	I.paciente_id = PA.paciente_id ";
			$sql .= "AND 	C.plan_id = P.plan_id ";
			$sql .= "AND	GC.sw_estado IN ('".$this->datos['sw_estado']."') ";
			$sql .= "UNION  ALL ";
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "		    GM.motivo_glosa_descripcion, ";
			$sql .= "		    GC.valor_glosa, ";
			$sql .= "		    CD.cargo_cups,  ";
			$sql .= "		    TD.descripcion, ";
			$sql .= "		    'DC', ";
			$sql .= "		    GC.codigo_concepto_general, ";
			$sql .= "		    GC.codigo_concepto_especifico ";
			$sql .= "FROM   glosas_detalle_cargos GC, ";
      $sql .= "       glosas_motivos GM, ";
			$sql .= "		    cuentas_detalle CD, ";
      $sql .= "		    glosas_detalle_cuentas GD, ";
			$sql .= "		    tarifarios_detalle TD ";
			$sql .= "WHERE 	GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND    GM.motivo_glosa_id = GC.motivo_glosa_id ";
			$sql .= "AND 	  GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	  GC.transaccion = CD.transaccion ";
			$sql .= "AND 	  GC.sw_estado IN ('".$this->datos['sw_estado']."') ";
			$sql .= "AND 	  GC.glosa_id = ".$this->GlosaId." ";
			$sql .= "AND 	  TD.cargo = CD.cargo ";
			$sql .= "AND 	  TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ALL ";
			$sql .= "SELECT   CD.numerodecuenta, ";
			$sql .= "		      GM.motivo_glosa_descripcion, ";
			$sql .= "		      GI.valor_glosa, ";
			$sql .= "		      '--', ";
			$sql .= "		      ID.descripcion, ";
			$sql .= "		      'DI', ";
			$sql .= "		      GI.codigo_concepto_general, ";
			$sql .= "		      GI.codigo_concepto_especifico ";
			$sql .= "FROM 	  glosas_detalle_inventarios GI,";
      $sql .= "         glosas_motivos GM, ";
			$sql .= "		      cuentas CD, ";
			$sql .= "		      glosas_detalle_cuentas GD, ";
			$sql .= "		      inventarios_productos ID ";
			$sql .= "WHERE	  GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
 			$sql .= "AND      GM.motivo_glosa_id = GI.motivo_glosa_id ";
			$sql .= "AND 	    GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	    GI.glosa_id = ".$this->GlosaId." ";
			$sql .= "AND	    GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 	    GD.sw_estado IN ('".$this->datos['sw_estado']."') ";
			$sql .= "AND 	    GD.glosa_id = GI.glosa_id ";
			$sql .= "ORDER BY 1,6 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$cargos[$i] = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4]."*".$rst->fields[5]."*".$rst->fields[6]."*".$rst->fields[7];
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
				
			return $cargos;
		}
		/***************************************************************************************
		*
		****************************************************************************************/
		function ObtenerInformacionFactura()
		{
			if($this->Sistema == "EXT")
			{
				$sql  = "SELECT T.nombre_tercero,";
				$sql .= "				F.tipo_id_tercero,";
				$sql .= "				F.tercero_id,";
				$sql .= "				F.saldo, ";
				$sql .= "				F.total_factura,";
				$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
				$sql .= "FROM 	terceros T,facturas_externas F ";
				$sql .= "WHERE 	F.empresa_id = '".$_SESSION['glosas']['empresa_id']."' "; 
				$sql .= "AND 		F.prefijo = '".$this->FacturaP."' ";
				$sql .= "AND 		F.factura_fiscal = ".$this->FacturaF." ";
				$sql .= "AND 		F.tercero_id = T.tercero_id ";
				$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";		
			}
			else
			{
				$sql  = "SELECT T.nombre_tercero,";
				$sql .= "				F.tipo_id_tercero,";
				$sql .= "				F.tercero_id,";
				$sql .= "				F.total_factura,";
				$sql .= "				F.saldo,";
				$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
				$sql .= "				P.num_contrato,"; 
				$sql .= "				P.plan_descripcion,";
				$sql .= "				P.plan_id ";
				$sql .= "FROM 	terceros T,fac_facturas F,";
				$sql .= "	  		planes P ";
				$sql .= "WHERE 	F.empresa_id = '".$_SESSION['glosas']['empresa_id']."' "; 
				$sql .= "AND 		F.prefijo = '".$this->FacturaP."' ";
				$sql .= "AND 		F.factura_fiscal = ".$this->FacturaF." ";
				$sql .= "AND 		F.tercero_id = T.tercero_id ";
				$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";
				$sql .= "AND 		F.sw_clase_factura = '1' ";
				$sql .= "AND 		F.plan_id = P.plan_id ";
				$sql .= "AND 		F.empresa_id = P.empresa_id ";
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$factura = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$this->TerceroNombre = $factura['nombre_tercero'];
			$this->TerceroTipoId = $factura['tipo_id_tercero'];
			$this->TerceroId = $factura['tercero_id'];
			$this->FacturaNumero = $this->FacturaP." ".$this->FacturaF;
			$this->FacturaTotal = $factura['saldo'];
			$this->PlanDescripcion = $factura['plan_descripcion'];
			$this->PlanId = $factura['plan_id'];
			
			return true;
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
		
		function ObtenerDescripcionConceptos($CGeneral,$CEspecifico)
		{
			$sql  = "SELECT descripcion_concepto_general
			FROM  glosas_concepto_general
			WHERE  codigo_concepto_general = '".$CGeneral."'";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$CG = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			$sql  = "SELECT descripcion_concepto_especifico
			FROM  glosas_concepto_especifico
			WHERE  codigo_concepto_especifico = '".$CEspecifico."'";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$CE = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $CG."||//".$CE;
	 	}
	 	
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