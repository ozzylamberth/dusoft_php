<?php

	/**************************************************************************************
	 * $Id: reporteacta.report.php,v 1.8 2009/03/19 20:32:41 cahenao Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 * 
	 **************************************************************************************/

	class reporteacta_report 
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
	    function reporteacta_report($datos=array())
	    {
			$this->datos=$datos;
	        return true;
	    }
		
		function GetMembrete()
		{
			$this->ObtenerInformacionActa();
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= "<b $estilo>CONCILIACION GLOSAS ".$this->NombreEmpresa." - ";
			$titulo .= "".$_SESSION['Auditoria']['razon'];
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
	    {
			$this->ObtenerInformacionActa();
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\""; 

			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" $estilo>\n";
			$Salida .= "		<tr>";
			$Salida .= "			<td width=\"30%\"><b>FECHA:</b></td>\n";
			$Salida .= "			<td width=\"70%\"><b>".$this->Fecha."</b></td>\n";
			$Salida .= "		</tr>";
			if($this->ObservacionA)
			{
				$Salida .= "		<tr>";
				$Salida .= "			<td valign=\"top\"><b>OBSERVACIÓN GENERAL</b></td>\n";
				$Salida .= "			<td align=\"justify\"><b>".$this->ObservacionA."</b></td>\n";
				$Salida .= "		</tr>";
			}
			$Salida .= "	</table><br>";
			
			$Datos = $this->ObtenerFacturasGlosadas();
				
			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\" width=\"100%\" rules=\"all\" $estilo>\n";
			$Salida .= "		<tr>\n";
			$Salida .= "			<td align=\"center\" width=\"14%\"><b>Nº FACTURA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"14%\"><b>Nº GLOSA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"14%\"><b>V. FACTURA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"14%\"><b>V. GLOSA</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"15%\"><b>V. ACEPTADO</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"15%\"><b>V. A PAGAR E.P.S.</b></td>\n";
			$Salida .= "			<td align=\"center\" width=\"14%\"><b>V. PENDIENTE</b></td>\n";
			$Salida .= "		</tr>\n";
			
			for($i=0; $i< sizeof($Datos); $i++)
			{	
				$Salida .= "			<tr>\n";
				$Salida .= "				<td >".$Datos[$i]['prefijo']." ".$Datos[$i]['factura_fiscal']."</td>\n";
				$Salida .= "				<td >".$Datos[$i]['glosa_id']."</td>\n";
				$Salida .= "				<td align=\"right\">".formatoValor($Datos[$i]['total_factura'])."</td>\n";		
				$Salida .= "				<td align=\"right\">".formatoValor($Datos[$i]['valor_glosa'])."</td>\n";
				$Salida .= "				<td align=\"right\">".formatoValor($Datos[$i]['valor_aceptado'])."</td>\n";		
				$Salida .= "				<td align=\"right\">".formatoValor($Datos[$i]['valor_no_aceptado'])."</td>\n";
				$Salida .= "				<td align=\"right\">".formatoValor($Datos[$i]['valor_pendiente'])."</td>\n";
				$Salida .= "			</tr>\n";
			}
			$Salida .= "	</table><br><br>\n";
			
			$Salida .= "	<table width=\"100%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">\n";
			$Salida .= "		<tr $estilo>\n";
			$Salida .= "			<td width=\"28%\" align=\"center\"><b>&nbsp;</b></td>\n";
			$Salida .= "			<td width=\"14%\" align=\"center\"><b>V. FACTURA</b></td>\n";
			$Salida .= "			<td width=\"14%\" align=\"center\"><b>V. GLOSA</b></td>\n";
			$Salida .= "			<td width=\"15%\" align=\"center\"><b>V. ACEPTADO</b></td>\n";
			$Salida .= "			<td width=\"15%\" align=\"center\"><b>V. A PAGAR E.P.S.</b></td>\n";
			$Salida .= "			<td width=\"14%\">&nbsp;</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "		<tr $estilo height=\"19\">\n";
			$Salida .= "			<td ><b>TOTAL</b></td>\n";
			$Salida .= "			<td align=\"right\"><b>".formatoValor($this->Totales[3])."</b></td>\n";
			$Salida .= "			<td align=\"right\"><b>".formatoValor($this->Totales[0])."</b></td>\n";
			$Salida .= "			<td align=\"right\"><b>".formatoValor($this->Totales[1])."</b></td>\n";
			$Salida .= "			<td align=\"right\"><b>".formatoValor($this->Totales[2])."</b></td>\n";
			$Salida .= "			<td align=\"center\">&nbsp;</td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "	</table><br><br>\n";
			
			for($i=strlen($this->AuditorClinica); $i<50; $i++)
				$line1 .= "&nbsp;"; 
			
			for($i=strlen($this->AuditorEmpresa); $i<50; $i++)
				$line2 .= "&nbsp;";
			
			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" $estilo>\n";		
			$Salida .= "		<tr>";
			$Salida .= "			<td width=\"44%\"><b style=\"text-decoration :overline\">".$this->AuditorClinica."$line1</b></td>\n";
			$Salida .= "			<td ></td>\n";
			$Salida .= "			<td width=\"44%\"><b style=\"text-decoration :overline\">".$this->AuditorEmpresa."$line2</b></td>\n";
			$Salida .= "		</tr>";
			$Salida .= "		<tr>";
			$Salida .= "			<td ><b>AUDITOR(A)</b></td>\n";
			$Salida .= "			<td ></td>\n";
			$Salida .= "			<td ><b>AUDITOR(A)</b></td>\n";
			$Salida .= "		</tr>";
			$Salida .= "		<tr>";
			$Salida .= "			<td width=\"40%\"><b>".$_SESSION['Auditoria']['razon']."</b></td>\n";
			$Salida .= "			<td ></td>\n";
			$Salida .= "			<td width=\"40%\"><b>".$this->NombreEmpresa."</b></td>\n";
			$Salida .= "		</tr>";
			$Salida .= "	</table>";
	    return $Salida;
		}
		/************************************************************************************ 
		* Funcion que permite traer la informacion de la glosa y el detalle del acta de 
		* conciliacion (si la hay) de las factura pertenecientes a un cliente
		* 
		* @return array datos de las facturas
		*************************************************************************************/
		function ObtenerFacturasGlosadas()
		{		
			$empresa = $_SESSION['Auditoria']['empresa'];
			$tercero = explode("/",$this->datos['tercero']);
			
			$sql  = "SELECT FF.prefijo, ";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura, ";
			$sql .= "       SUM(GL.valor_glosa) AS valor_glosa, ";
			$sql .= "       SUM(GL.valor_aceptado) AS valor_aceptado, ";
			$sql .= "       SUM(GL.valor_no_aceptado) AS valor_no_aceptado, ";
			$sql .= "       SUM(GL.valor_pendiente)AS valor_pendiente, ";
			$sql .= "       GL.glosa_id ";
			$sql .= "FROM		glosas GL, ";
			$sql .= "				view_fac_facturas FF, ";
			$sql .= "				actas_conciliacion_glosas_detalle AC ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		GL.prefijo = FF.prefijo ";
			$sql .= "AND		GL.factura_fiscal = FF.factura_fiscal ";	
			$sql .= "AND		GL.empresa_id = FF.empresa_id ";
			$sql .= "AND		GL.sw_estado IN ('1','2') ";
			$sql .= "AND		FF.tipo_id_tercero = '".$tercero[0]."' ";
			$sql .= "AND 		FF.tercero_id = '".$tercero[1]."' ";
			$sql .= "AND		AC.acta_conciliacion_id = ".$this->datos['acta_id']." ";
			$sql .= "AND		AC.glosa_id = GL.glosa_id ";
			$sql .= "GROUP BY 1,2,3,8 ";
			$sql .= "ORDER BY 1,2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			$this->Totales[0] = 0;
			$this->Totales[1] = 0;
			$this->Totales[2] = 0;
			$this->Totales[3] = 0;
			 
			while(!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);

		    $this->Totales[0] += $rst->fields[3];
				$this->Totales[1] += $rst->fields[4];
				$this->Totales[2] += $rst->fields[5];
				$this->Totales[3] += $rst->fields[2];
				
				$rst->MoveNext();
			}
			$rst->Close();
			return $facturas;
		}
		
		/************************************************************************************
		* Funcion donde se obtiene la informacion de un acta de conciliacion
		* 
		* @return boolean
		*************************************************************************************/
		function ObtenerInformacionActa()
		{
			$sql .= "SELECT	SU.nombre, ";
			$sql .= "		TE.nombre_tercero, ";
			$sql .= "		AC.auditor_empresa, ";
			$sql .= "		TO_CHAR(AC.fecha_acta,'DD /MM /YYYY'), ";
			$sql .= "		AC.observacion ";
			$sql .= "FROM	actas_conciliacion_glosas AC,";
			$sql .= "		terceros TE, ";
			$sql .= "		system_usuarios SU ";
			$sql .= "WHERE	AC.auditor_id = SU.usuario_id ";
			$sql .= "AND	AC.acta_conciliacion_id = ".$this->datos['acta_id']." ";
			$sql .= "AND	AC.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND	AC.tercero_id = TE.tercero_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			if(!$rst->EOF)
			{
				$this->AuditorClinica = $rst->fields[0];
				$this->NombreEmpresa = $rst->fields[1];
				$this->AuditorEmpresa = $rst->fields[2];
				$this->Fecha = $rst->fields[3];
				$this->ObservacionA = $rst->fields[4];
				
				$rst->MoveNext();
			}
			$rst->Close();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerDetalleGlosa($glosaId)
		{			
			$sql  = "SELECT	C.numerodecuenta, ";
			$sql .= "		obtener_observacion_acta(".$glosaId.",".$this->datos['acta_id'].",GC.glosa_detalle_cuenta_id,0,'C'), ";	
			$sql .= "		CASE WHEN GC.sw_glosa_total_cuenta = '0' ";
			$sql .= " 			 THEN GC.valor_glosa_copago + GC.valor_glosa_cuota_moderadora ";
			$sql .= "		     WHEN GC.sw_glosa_total_cuenta = '1' THEN C.total_cuenta END AS valor_glosa,";
			$sql .= "		'--',";
			$sql .= "		'--',";
			$sql .= "		CASE WHEN GC.sw_glosa_total_cuenta = '0' THEN 'DA' ";
			$sql .= "		     WHEN GC.sw_glosa_total_cuenta = '1' THEN 'DT' END, ";
			$sql .= "		GC.valor_aceptado, ";
			$sql .= "		GC.valor_no_aceptado ";
			$sql .= "FROM	planes P, ";
			$sql .= "		ingresos I,";
			$sql .= "		pacientes PA,"; 
			$sql .= "		cuentas C,";
			$sql .= "		glosas_detalle_cuentas GC  ";
			$sql .= "WHERE	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 	C.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND 	C.ingreso = I.ingreso ";
			$sql .= "AND 	I.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND 	I.paciente_id = PA.paciente_id ";
			$sql .= "AND 	C.plan_id = P.plan_id ";
			$sql .= "AND	GC.sw_estado <> '0' ";
			//$sql .= "AND	valor_glosa > 0 ";
			$sql .= "UNION  ";
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "		obtener_observacion_acta(".$glosaId.",".$this->datos['acta_id'].",GC.glosa_detalle_cuenta_id,GC.glosa_detalle_cargo_id,'A'), ";
			$sql .= "		GC.valor_glosa, ";
			$sql .= "		CD.cargo,  ";
			$sql .= "		TD.descripcion, ";
			$sql .= "		'DC', ";
			$sql .= "		GC.valor_aceptado, ";
			$sql .= "		GC.valor_no_aceptado ";
			$sql .= "FROM 	glosas_detalle_cargos GC, ";
			$sql .= "		cuentas_detalle CD, ";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		tarifarios_detalle TD ";
			$sql .= "WHERE 	GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GC.transaccion = CD.transaccion ";
			$sql .= "AND 	GC.sw_estado <> '0' ";
			$sql .= "AND 	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND	GC.valor_glosa > 0 ";
			$sql .= "AND 	TD.cargo = CD.cargo ";
			$sql .= "AND 	TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT CD.numerodecuenta, ";
			$sql .= "		obtener_observacion_acta(".$glosaId.",".$this->datos['acta_id'].",GI.glosa_detalle_cuenta_id,GI.glosa_detalle_inventario_id,'I'), ";
			$sql .= "		GI.valor_glosa, ";
			$sql .= "		ID.codigo_producto, ";
			$sql .= "		ID.descripcion, ";
			$sql .= "		'DI', ";
			$sql .= "		GI.valor_aceptado, ";
			$sql .= "		GI.valor_no_aceptado ";
			$sql .= "FROM 	glosas_detalle_inventarios GI, ";
			$sql .= "		cuentas CD, ";
			$sql .= "		glosas_detalle_cuentas GD, ";
			$sql .= "		inventarios_productos ID ";
			$sql .= "WHERE	GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 	GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 	GI.glosa_id = ".$glosaId." ";
			$sql .= "AND	GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 	GI.sw_estado <> '0' ";
			$sql .= "AND	GI.valor_glosa > 0 ";
			$sql .= "AND 	GD.glosa_id = GI.glosa_id ";
			$sql .= "ORDER BY 1,6 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$cargos[$i]  = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4]."*".$rst->fields[5];
				$cargos[$i] .= "*".$rst->fields[6]."*".$rst->fields[7];
				$this->TotalCuetas[$rst->fields[0]]['glosa'] += $rst->fields[2];
				$this->TotalCuetas[$rst->fields[0]]['aceptado'] += $rst->fields[6];
				$this->TotalCuetas[$rst->fields[0]]['no_aceptado'] += $rst->fields[7];
				
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
				
			return $cargos;
		}
		/************************************************************************************
		*
		*************************************************************************************/
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
		
	    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	    //---------------------------------------
	}

?>
