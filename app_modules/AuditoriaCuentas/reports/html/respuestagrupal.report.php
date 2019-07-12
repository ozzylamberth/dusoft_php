<?php

	/**************************************************************************************
	 * $Id: respuestagrupal.report.php,v 1.4 2009/03/19 20:32:41 cahenao Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 * 
	 **************************************************************************************/

	class respuestagrupal_report 
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
    function respuestagrupal_report($datos=array())
    {
			$this->datos=$datos;
    	return true;
    }
		
		function GetMembrete()
		{		
			$titulo  = "<b class=\"label\">".$_SESSION['Auditoria']['razon']."<br>";
			$titulo .= "".$_SESSION['Auditoria']['tipo_id_tercero']." ".$_SESSION['Auditoria']['id']."</b>";
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  				'subtitulo'=>' ','logo'=>'logocliente.png','align'=>'center'));
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:11px; text-align:center\""; 
			
			$mes= array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
			
			$empresa = $_SESSION['Auditoria']['empresa'];
			$this->ObtenerInfoEmpresa($this->datos['trid'],$this->datos['trdc']);
			$this->ObtenerInfoGlosa($this->datos['trid'],$this->datos['trdc'],$empresa);
			$this->ObtenerUsuarioNombre();
			
			$Salida .= "	<table cellpading=\"0\" cellspacing=\"0\" $estilo>\n";
			$Salida .= "		<tr>\n";
			$Salida .= "			<td><b>".ucfirst(strtolower($_SESSION['Auditoria']['municipio'])).", ".date("d")." de ".$mes[date("n")-1]." de ".date("Y")."</b></td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "	</table><br>\n";
			$Salida .= "	<table cellpading=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" $estilo>\n";
			$Salida .= "		<tr><td><b>Señores</b></td></tr>\n";
			$Salida .= "		<tr><td><b>".$this->Empresa['nombre_tercero']."</b></td></tr>\n";
			$Salida .= "		<tr><td><b>".$this->Empresa['tipo_id_tercero']." ".$this->Empresa['tercero_id']."</b></td></tr>\n";
			$Salida .= "		<tr><td><b>DIRECCIÓN: ".$this->Empresa['direccion']."</b></td></tr>\n";
			$Salida .= "		<tr><td><b>".$this->Empresa['municipio']." - ".$this->Empresa['departamento']."</b></td></tr>\n";
			$Salida .= "		<tr><td><br><b>Asunto: Respuesta Glosa Factura</b></td></tr>\n";
			$Salida .= "		<tr><td><br><b>Cordial saludo.</b></td></tr>\n";
			$Salida .= "		<tr><td><br><b>Me permito hacer respuesta de la(s) glosa(s) presentada(s) a nuestra entidad, así:</b></td></tr>\n";
			$Salida .= "	</table><br>\n";
			
			foreach($this->Glosa as $key => $glosas)
			{
				$this->Poliza = array();
				$this->Factura = array();
				if($glosas['sistema'] == 'SIIS')
				{
					$this->ObtenerInfoFactura($glosas['prefijo'],$glosas['factura_fiscal'],$empresa);
					$this->ObtenerInfoPoliza($this->Factura['ingreso']);
				}
				$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" border=\"1\" bordercolor=\"#000000\" rules=\"none\" $estilo>\n";		
				$Salida .= "		<tr>\n";
				$Salida .= "			<td>\n";
				$Salida .= "				<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" $estilo>\n";		
				$Salida .= "					<tr>\n";
				$Salida .= "						<td width=\"10%\"><b>Factura: </b></td>\n";
				$Salida .= "						<td width=\"15%\"><b>".$glosas['prefijo']." ".$glosas['factura_fiscal']."</b></td>\n";
				$Salida .= "						<td width=\"75%\"><b>Plan: ".$this->Factura['plan_descripcion']."</b></td>\n";
				$Salida .= "					</tr>\n";
				if($this->Factura['envio_id'])
				{
					$Salida .= "					<tr>\n";
					$Salida .= "						<td><b>Envio: </b></td>\n";
					$Salida .= "						<td colspan=\"2\">".$this->Factura['envio_id']."</td>\n";
					$Salida .= "					</tr>\n";
				}
				if($this->Factura['paciente_id'])
				{
					$Salida .= "					<tr>\n";
					$Salida .= "						<td width=\"10%\"><b>Paciente: </b></td>\n";
					$Salida .= "						<td width=\"%\" colspan=\"2\" >\n";
					$Salida .= "							".$this->Factura['tipo_id_paciente']." ".$this->Factura['paciente_id']."&nbsp;&nbsp;&nbsp;&nbsp;\n";
					$Salida .= "							".$this->Factura['apellido']." ".$this->Factura['nombre']."";
					$Salida .= "						</td>\n";
					$Salida .= "					</tr>\n";
				}
				if($this->Poliza['poliza'])
				{
					$Salida .= "					<tr>\n";
					$Salida .= "						<td><b>Nº Póliza: </b></td>\n";
					$Salida .= "						<td colspan=\"2\">".$this->Poliza['poliza']."</td>\n";
					$Salida .= "					</tr>\n";
				}
				
				$Salida .= "				</table>\n";
				$Salida .= "			</td>\n";
				$Salida .= "		</tr>\n";
				$Salida .= "		<tr>\n";
				$Salida .= "			<td>\n";
				$Salida .= "				<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" border=\"1\" bordercolor=\"#000000\">\n";
				$Salida .= "					<tr $estilo2>\n";
				$Salida .= "						<td width=\"15%\"><b>Documento Interno</b></td>\n";
				$Salida .= "						<td width=\"10%\"><b>Glosa Nº</b></td>\n";
				$Salida .= "						<td width=\"10%\"><b>Fecha</b></td>\n";
				$Salida .= "						<td width=\"35%\"><b>Motivo Glosa</b></td>\n";			
				$Salida .= "						<td width=\"10%\"><b>V. Glosa</b></td>\n";
				$Salida .= "						<td width=\"10%\"><b>V. Aceptado</b></td>\n";
				$Salida .= "						<td width=\"10%\"><b>V. No Aceptado</b></td>\n";
				$Salida .= "					</tr>\n";
				$Salida .= "					<tr $estilo>\n";
				$Salida .= "						<td width=\"%\"  align=\"left\"><b>".$glosas['documento_interno_cliente_id']."&nbsp;</b></td>\n";		
				$Salida .= "						<td align=\"right\" ><b>".$key."</b></td>\n";
				$Salida .= "						<td align=\"center\"><b>".$glosas['registro']."</b></td>\n";
				$Salida .= "						<td >".$glosas['motivo_glosa_descripcion']."&nbsp;</td>\n";
				$Salida .= "						<td align=\"right\" ><b>$".formatoValor($glosas['valor_glosa'])."</b></td>\n";
				$Salida .= "						<td align=\"right\" ><b>$".formatoValor($glosas['valor_aceptado'])."</b></td>\n";
				$Salida .= "						<td align=\"right\" ><b>$".formatoValor($glosas['valor_no_aceptado'])."</b></td>\n";
				$Salida .= "					</tr>\n";			
				$Salida .= "				</table>";
				$Salida .= "			<td>\n";
				$Salida .= "		</tr>\n";
				
				$this->Observa = array();
				$this->ObtenerObservaciones($key);
				if(sizeof($this->Observa) > 0)
				{
					$Salida .= "		<tr>\n";
					$Salida .= "			<td>\n";
					$Salida .= "				<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" rules=\"all\" width=\"100%\" $estilo>\n";
					$Salida .= "					<tr>\n";
					$Salida .= "						<td width=\"15%\" valign=\"top\"><b>Observaciones:</b></td>\n";
					$Salida .= "						<td align=\"justify\">\n";
					$Salida .= "							<menu>\n";
					for($i=0; $i< sizeof($this->Observa); $i++)
						$Salida .= "								<li>".$this->Observa[$i]['observacion']."\n";
					
					$Salida .= "							</menu>\n";
					$Salida .= "						</td>\n";
					$Salida .= "					</tr>\n";
					$Salida .= "				</table>\n";
					$Salida .= "			<td>\n";
					$Salida .= "		</tr>\n";
				}
				
				$this->ObtenerCargosGlosados($glosas['glosa_id']);
				$cargo = $this->Cargos['0'];
				$insumo = $this->Cargos['1'];
				if(sizeof($cargo)>0 || sizeof($insumo)>0 )
				{
					$Salida .= "		<tr>\n";
					$Salida .= "			<td>\n";
					$Salida .= "				<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" border=\"1\" bordercolor=\"#000000\">\n";
					$Salida .= "					<tr class=\"label\">\n";
					$Salida .= "						<td align=\"center\" width=\"8%\">Cargo</td>\n";
					$Salida .= "						<td align=\"center\" width=\"30%\">Descripcion</td>\n";
					$Salida .= "						<td align=\"center\" width=\"32%\">Motivo</td>\n";
					$Salida .= "						<td align=\"center\" width=\"10%\">V. Glosa</td>\n";
					$Salida .= "						<td align=\"center\" width=\"10%\">V. Aceptado</td>\n";
					$Salida .= "						<td align=\"center\" width=\"10%\">V. No Aceptado</td>\n";
					$Salida .= "					</tr>\n";
					if(sizeof($cargo) > 0)
					{
						$Salida .= "					<tr class=\"label\">\n";
						$Salida .= "						<td colspan=\"6\" align=\"center\" width=\"10%\">CARGOS</td>\n";
						$Salida .= "					</tr>\n";
					}
					foreach($cargo  as $key => $cargos)
					{
						$Salida .= "					<tr class=\"label\">\n";
						$Salida .= "						<td align=\"center\" valign=\"top\">".$cargos['cargo_cups']."&nbsp;</td>\n";
						$Salida .= "						<td valign=\"top\">".$cargos['descripcion']."</td>\n";
						$Salida .= "						<td valign=\"top\">".$cargos['motivo_glosa_descripcion']."</td>\n";
						$Salida .= "						<td align=\"right\" valign=\"top\">$".formatoValor($cargos['valor_glosa'])."</td>\n";
						$Salida .= "						<td align=\"right\" valign=\"top\">$".formatoValor($cargos['valor_aceptado'])."</td>\n";
						$Salida .= "						<td align=\"right\" valign=\"top\">$".formatoValor($cargos['valor_no_aceptado'])."</td>\n";
						$Salida .= "					</tr>\n";
					}
					if(sizeof($insumo) > 0)
					{
						$Salida .= "					<tr class=\"label\">\n";
						$Salida .= "						<td colspan=\"6\" align=\"center\" width=\"10%\">INSUMOS</td>\n";
						$Salida .= "					</tr>\n";
					}
					foreach($insumo  as $key => $insumos)
					{
						$Salida .= "					<tr class=\"label\">\n";
						$Salida .= "						<td align=\"center\" >".$insumos['cargo_cups']."&nbsp;</td>\n";
						$Salida .= "						<td >".$insumos['descripcion']."</td>\n";
						$Salida .= "						<td >".$insumos['motivo_glosa_descripcion']."</td>\n";
						$Salida .= "						<td align=\"right\" >$".formatoValor($insumos['valor_glosa'])."</td>\n";
						$Salida .= "						<td align=\"right\" >$".formatoValor($insumos['valor_aceptado'])."</td>\n";
						$Salida .= "						<td align=\"right\" >$".formatoValor($insumos['valor_no_aceptado'])."</td>\n";
						$Salida .= "					</tr>\n";
					}
					$Salida .= "				</table>\n";
					$Salida .= "			<td>\n";
					$Salida .= "		</tr>\n";
				}
				$Salida .= "	</table><br>\n";
			}
			
			$Salida .= "	<br><br>\n";
			$Salida .= "	<table align=\"center\" cellpading=\"0\" cellspacing=\"0\" width=\"100%\" $estilo>\n";		
			$Salida .= "		<tr>\n";
			$Salida .= "			<td colspan=\"2\"><b>Atentamente,</b><br><br><br><br></td>\n";
			$Salida .= "		</tr>\n";
			
			($this->Usuario[1])? $cargo = "AUDITOR ": $cargo= "";
			for($i=strlen($this->Usuario[0]); $i<50; $i++)
				$line .= "&nbsp;"; 
			
			$Salida .= "		<tr>";
			$Salida .= "			<td width=\"45%\"><b style=\"text-decoration :overline\">".$this->Usuario[0]."$line</b></td>\n";
			$Salida .= "			<td width=\"55%\"></td>\n";
			$Salida .= "		</tr>";
			$Salida .= "		<tr>";
			$Salida .= "			<td ><b>$cargo</b></td>\n";
			$Salida .= "			<td ></td>\n";
			$Salida .= "		</tr>";
			$Salida .= "	</table>";
			
	    return $Salida;
		}
		/************************************************************************************
		* 
		*************************************************************************************/
		function ObtenerInfoGlosa($tr_id,$tr_dc,$empresa)
		{					
			$sql  = "SELECT GL.glosa_id, ";
			$sql .= "				GL.prefijo, ";
			$sql .= "				GL.factura_fiscal,";
			$sql .= "       GL.valor_glosa AS valor_glosa, ";
			$sql .= "       GL.valor_aceptado AS valor_aceptado, ";
			$sql .= "       GL.valor_no_aceptado AS valor_no_aceptado, ";
			$sql .= "				GL.documento_interno_cliente_id, ";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				TO_CHAR(GL.fecha_glosa,'DD/MM/YYYY') AS registro, ";
			$sql .= "				GL.sw_estado, ";
			$sql .= "				VF.sistema ";
			$sql .= "FROM		glosas GL LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(GL.motivo_glosa_id = GM.motivo_glosa_id), ";
			$sql .= "				view_fac_facturas VF ";
			$sql .= "WHERE 	GL.empresa_id = '".$empresa."' ";
			$sql .= "AND		VF.factura_fiscal = GL.factura_fiscal ";
			$sql .= "AND		VF.prefijo = GL.prefijo ";
			$sql .= "AND		VF.tercero_id = '".$tr_dc."' ";
			$sql .= "AND 		VF.tipo_id_tercero = '".$tr_id."' ";
			$sql .= "AND		VF.saldo >=0 ";
			$sql .= "AND 		GL.sw_estado IN ('2') ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
				
			while(!$rst->EOF)
			{
				$this->Glosa[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);				
				$rst->MoveNext();
			}
			$rst->Close();
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerInfoFactura($prefijo,$factura,$empresa)
		{
			$sql .= "SELECT	DISTINCT PL.plan_descripcion, ";
			$sql .= "				PA.primer_apellido||' '||PA.segundo_apellido AS apellido, ";
			$sql .= "				PA.primer_nombre||' '||PA.segundo_nombre AS nombre, ";
			$sql .= "				PA.tipo_id_paciente, ";
			$sql .= "				PA.paciente_id, ";
			$sql .= "				ED.envio_id, ";
			$sql .= "				IG.ingreso ";
			$sql .= "FROM		fac_facturas_cuentas FF LEFT JOIN ";
			$sql .= "				(	SELECT	ED.prefijo, ";
			$sql .= "									ED.factura_fiscal, ";
			$sql .= "									E.fecha_radicacion, ";
			$sql .= "									E.envio_id, ";
			$sql .= "									E.sw_estado ";
			$sql .=	"					FROM 		envios_detalle ED, ";
			$sql .= "									envios E ";
			$sql .= "					WHERE		ED.envio_id = E.envio_id  ";
			$sql .= "					AND			E.sw_estado != '2' ) AS ED ";
			$sql .= "				ON( ED.prefijo = FF.prefijo AND ";
			$sql .= "						ED.factura_fiscal = FF.factura_fiscal ), ";
			$sql .= "				cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				planes PL, ";
			$sql .= "				pacientes PA ";
			$sql .= "WHERE	FF.prefijo = '".$prefijo."' ";
			$sql .= "AND		FF.factura_fiscal = ".$factura." ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND		CU.ingreso = IG.ingreso ";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			while(!$rst->EOF)
			{
				$this->Factura = $rst->GetRowAssoc($ToUpper = false);	
				$rst->MoveNext();
			}
			$rst->Close();
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerInfoEmpresa($tr_id, $tr_dc)
		{			
			$sql  = "SELECT	TE.nombre_tercero, ";
			$sql .= "				TE.tercero_id, ";
			$sql .= "				TE.tipo_id_tercero, ";	
			$sql .= "				TE.direccion, ";
			$sql .= "				TM.municipio, ";
			$sql .= "				TD.departamento ";
			$sql .= "FROM		terceros TE, ";
			$sql .= "				tipo_mpios TM,";
			$sql .= "				tipo_dptos TD "; 
			$sql .= "WHERE	TE.tercero_id = '".$tr_dc."' ";
			$sql .= "AND 		TE.tipo_id_tercero = '".$tr_id."' ";
			$sql .= "AND 		TE.tipo_mpio_id = TM.tipo_mpio_id ";
			$sql .= "AND 		TE.tipo_dpto_id = TD.tipo_dpto_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$this->Empresa = $rst->GetRowAssoc($ToUpper = false);	
				$rst->MoveNext();
			}
			$rst->Close();
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerInfoPoliza($ingreso)
		{			
			$sql  = "SELECT	SE.poliza ";
			$sql .= "FROM		soat_eventos SE ,";
			$sql .= "				ingresos_soat SI "; 
			$sql .= "WHERE	SI.ingreso = ".$ingreso." ";
			$sql .= "AND		SI.evento = SE.evento ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$this->Poliza = $rst->GetRowAssoc($ToUpper = false);	
				$rst->MoveNext();
			}
			$rst->Close();
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerObservaciones($glosa_id)
		{
			$sql .= "SELECT observacion ";
			$sql .= "FROM		respuesta_glosas "; 
			$sql .= "WHERE 	glosa_id = ".$glosa_id." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$this->Observa[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}	
			$rst->Close();
		}
		/**************************************************************************************
		*
		***************************************************************************************/
		function ObtenerUsuarioNombre()
		{
			$sql  = "SELECT U.nombre, A.usuario_id ";
			$sql .= "FROM 	system_usuarios U LEFT JOIN auditores_internos A ";
			$sql .= "				ON(U.usuario_id = A.usuario_id) ";
			$sql .= "WHERE 	U.usuario_id = ".UserGetUID();
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			if (!$rst->EOF)
			{
				$this->Usuario[0] = $rst->fields[0];
				$this->Usuario[1] = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
		}
		/****************************************************************************************
		* Funcion mediante la cual se buscan los cargos glosados de las cuentas pertenecientes 
		* a una factura 
		* 
		* @param string identificador de la glosa 
		* @return array datos de los cargos glosados  
		*****************************************************************************************/
		function ObtenerCargosGlosados($glosaId)
		{
			$sql  = "SELECT '0' AS id,";
			$sql .= "				CD.cargo_cups,";
			$sql .= "				CD.numerodecuenta,";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				GC.valor_glosa, ";
			$sql .= "				GC.valor_aceptado, ";
			$sql .= "				GC.valor_no_aceptado, ";
			$sql .= "				TD.descripcion ,"; 
			$sql .= "				GC.glosa_detalle_cargo_id AS glosa "; 
			$sql .=	"FROM 	glosas_detalle_cargos GC, cuentas_detalle CD, ";
			$sql .=	"     	glosas_motivos GM,";
			$sql .= "				tarifarios_detalle TD, ";
			$sql .= "				glosas_detalle_cuentas GD ";
			$sql .=	"WHERE 	GC.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .=	"AND 		GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 		GD.numerodecuenta = CD.numerodecuenta ";
			$sql .=	"AND 		GC.transaccion = CD.transaccion ";
			$sql .=	"AND 		GC.sw_estado IN ('1','2') ";
			$sql .= "AND 		TD.cargo = CD.cargo ";
			$sql .= "AND 		TD.tarifario_id = CD.tarifario_id ";
			$sql .= "AND 		CD.valor_cargo >= 0 ";
			$sql .= "AND		CD.tarifario_id <> 'SYS' ";
			$sql .=	"AND 		GC.glosa_id = ".$glosaId." ";
			$sql .=	"UNION ";
			$sql .=	"SELECT '1' AS id,";
			$sql .=	"				'---' AS cargo_cups,";
			$sql .= "				CD.numerodecuenta,";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				GI.valor_glosa, ";
			$sql .= "				GI.valor_aceptado, ";
			$sql .= "				GI.valor_no_aceptado, ";
			$sql .= "				IP.descripcion, ";
			$sql .= "				GI.glosa_detalle_inventario_id AS glosa "; 
			$sql .=	"FROM 	glosas_detalle_inventarios GI, cuentas CD, ";
			$sql .=	"     	glosas_motivos GM, ";
			$sql .= "				inventarios_productos IP, ";
			$sql .= "				glosas_detalle_cuentas GD  ";
			$sql .=	"WHERE 	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .=	"AND 		GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 		GD.numerodecuenta = CD.numerodecuenta ";
			$sql .=	"AND 		GI.sw_estado IN ('1','2') ";
			$sql .= "AND 		GD.glosa_id = GI.glosa_id ";
			$sql .=	"AND 		GI.glosa_id = ".$glosaId." ";
			$sql .= "AND		IP.codigo_producto = GI.codigo_producto ";
			$sql .= "ORDER BY 1 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$this->Cargos = array();
			while (!$rst->EOF)
			{
				$this->Cargos[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
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
