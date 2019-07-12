<?php
	/**************************************************************************************
	 * $Id: hojautorizacion.report.php,v 1.2 2009/11/04 19:08:36 hugo Exp $ 
	 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	 * @package IPSOFT-SIIS
	 * 
	 **************************************************************************************/
	include_once "./app_modules/NCAutorizaciones/classes/ConsultaAutorizaciones.class.php";

	class hojautorizacion_report 
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
		function hojautorizacion_report($datos=array())
		{
			$this->datos=$datos;
			if(!$this->datos['ingreso']) $this->datos['ingreso'] = SessionGetVar("IngresoAutorizacion");
			return true;
		}
		
		function GetMembrete()
		{
			$stl  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:13px\"";
			$titulo  = "<b $stl>SISTEMA GENERAL DE SEGURIDAD SOCIAL EN SALUD <br>";
			$titulo .= "REPORTE DE AUTORIZACIÓN Y NOVEDADES DE SERVICIOS</b>";
			
			$Mbr = array(	'file'=>false,
										'datos_membrete'=>array('titulo'=>$titulo,'subtitulo'=>' ',
																			'logo'=>'logocliente.png','align'=>'left'));
			return $Mbr;
		}
		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{     
			$clz = new ConsultaAutorizaciones();
					
			$Admin = $clz->ObtenerAutorizaciones($this->datos,"'OS'");
			$OServ = $clz->ObtenerAutorizaciones($this->datos,"'AD','**'");
			$paciente = $clz->ObtenerDatosPaciente($this->datos['ingreso']);
			
			$Afiliado = $paciente['ingreso'];
			
			$estado ="INACTIVO";
			if($Afiliado['estado'] == '1')	$estado = "ACTIVO";
			$stl = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$stt = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			
			$html  = "<table width=\"100%\" align=\"center\" $stl>\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label\">IDENTIFICACIÓN</td>\n";
			$html .= "		<td >".$Afiliado['tipo_id_paciente']." ".$Afiliado['paciente_id']."</td>\n";
			$html .= "		<td class=\"label\">PACIENTE</td>\n";
			$html .= "		<td colspan=\"3\">".$Afiliado['nombre']." ".$Afiliado['apellido']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label\">Nº INGRESO</td>\n";
			$html .= "		<td >".$Afiliado['ingreso']."</td>\n";
			$html .= "		<td class=\"label\">ESTADO</td>\n";
			$html .= "		<td colspan=\"3\">".$estado."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label\">PLAN</td>\n";
			$html .= "		<td colspan=\"5\" >".$Afiliado['plan_descripcion']."</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"label\">ENTIDAD</td>\n";
			$html .= "		<td colspan=\"5\">".$Afiliado['nombre_tercero']."</td >\n";
			$html .= "	</tr>\n";
			$html .= "      <tr>\n";
			$html .= "              <td class=\"label\">RANGO</td>\n";
			$html .= "              <td>".$Afiliado['rango']."</td >\n";
			$html .= "              <td class=\"label\">TIPO AFILIADO</td>\n";
			$html .= "              <td>".$Afiliado['tipo_afiliado_nombre']."</td >\n";
			$html .= "              <td class=\"label\">SEMANAS COT.</td>\n";
			$html .= "              <td>".$Afiliado['semanas_cotizadas']."</td >\n";
			$html .= "      </tr>\n";
																											
			
			foreach($paciente['cuentas'] as $key => $cuenta)
			{
				if($cuenta['cuentaestado'] == '1')
				{
					$html .= "	<tr>\n";
					$html .= "		<td class=\"label\">CUENTA Nº</td>\n";
					$html .= "		<td colspan=\"5\">".$cuenta['numerodecuenta']."</td>\n";
					$html .= "	</tr>\n";
					
					if($cuenta['plan_id'] != $Afiliado['plan_id'])
					{
						$html .= "	<tr>\n";
						$html .= "		<td class=\"label\">PLAN CUENTA</td>\n";
						$html .= "		<td colspan=\"5\">".$cuenta['plan_descripcion']."</td>\n";
						$html .= "	</tr>\n";
					}
					
					$html .= "	<tr>\n";
					$html .= "		<td width=\"15%\" class=\"label\">TIPO AFILIADO</td>\n";
					$html .= "		<td width=\"25%\">".$cuenta['tipo_afiliado_nombre']."</td>\n";
					$html .= "		<td width=\"10%\" class=\"label\">RANGO</td>\n";
					$html .= "		<td width=\"20%\">".$cuenta['rango']."</td>\n";
					$html .= "		<td class=\"label\">SEMANAS COTIZADAS</td>\n";
					$html .= "		<td width=\"10%\">".$cuenta['semanas_cotizadas']."</td>\n";
					$html .= "	</tr>\n";					
				}
			}
			$html .= "</table><br>\n";
			
			if(!empty($Admin))
			{
				$html .= "<center><b class=\"label\">SERVICIOS AUTORIZADOS</b></center>\n";
				$html .= "	<table width=\"100%\" $stt rules=\"all\" border=\"1\" cellspacing=\"0\">\n";
				$html .= "		<tr class=\"label\" align=\"center\">\n";
				$html .= "			<td width=\"6%\">Nº</td>\n";
				$html .= "			<td width=\"11%\">F. REGISTRO</td>\n";
				$html .= "			<td width=\"15%\">FUNCIONARIO CLINICA</td>\n";
				$html .= "			<td width=\"17%\">TIPO AUTORIZACION</td>\n";
				$html .= "			<td width=\"16%\">RESPONSABLE / TIPO DOCUMENTO</td>\n";
				$html .= "			<td width=\"12%\">CODIGO AUTORIZACIÓN</td>\n";
				$html .= "			<td width=\"23%\">OBSERVACIONES</td>\n";
				$html .= "		</tr>\n";
				
				$observa = "";
				foreach($Admin as $key => $autorizar)
				{
					foreach($autorizar as $keyI => $auto)
					{
						$html .= "						<tr class=\"label\">\n";
						$html .= "							<td align=\"center\" colspan=\"7\" $sttd>$keyI</td>\n";
						$html .= "						</tr>\n";
						foreach($auto as $keyII => $autoriza)
						{
							($autoriza['tipo_autorizador'] == 'I')? $tipo_auto = "INTERNA": $tipo_auto = "EXTERNA";
							$html .= "		<tr class=\"modulo_list_claro\">\n";
							$html .= "			<td>".$autoriza['autorizacion']."</td>\n";
							$html .= "			<td align=\"center\">".$autoriza['fecha']."</td>\n";
							$html .= "			<td>".$autoriza['responsable']."</td>\n";
							$html .= "			<td class=\"label\">".$tipo_auto." - ".$autoriza['tipo_autorizacion']."</td>\n";
							$html .= "			<td>".$autoriza['codigo_autorizacion_generador']."</td>\n";
							$html .= "			<td>".$autoriza['codigo_autorizacion']."</td>\n";
							$html .= "			<td>".$autoriza['descripcion_autorizacion']."</td>\n";
							$html .= "		</tr>\n";
							if($autoriza['observaciones'] != "")
								$observa .= "	<li>".$autoriza['observaciones']."</li>\n";
						}
					}
				}
				$html .= "	</table><br>\n";
				if($observa != "")
				{
					$html .= "	<table width=\"100%\">\n";
					$html .= "		<tr class=\"label\">\n";
					$html .= "			<td>\n";
					$html .= "				OBSERVACIONES GENERALES\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr>\n";
					$html .= "			<td $stl>\n";
					$html .= "				<ul>".$observa."</ul>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "	</table>\n";
				}
			}
			
			if(!empty($OServ))
			{				
				$html .= "	<center><b class=\"label\" >ORDENES DE SERVICIO</b></center>\n";
				$html .= "	<table width=\"100%\" $stt rules=\"all\" border=\"1\" cellspacing=\"0\">\n";
				$html .= "		<tr class=\"label\" align=\"center\">\n";
				$html .= "			<td width=\"8%\">Nº</td>\n";
				$html .= "			<td width=\"13%\">F. REGISTRO</td>\n";
				$html .= "			<td>FUNCIONARIO CLINICA</td>\n";
				$html .= "			<td>TIPO AUTORIZACION</td>\n";
				$html .= "			<td>RESPONSABLE / TIPO DOCUMENTO</td>\n";
				$html .= "			<td>CODIGO AUTO</td>\n";
				$html .= "			<td width=\"23%\">OBSERVACIONES</td>\n";
				$html .= "		</tr>\n";
				
				$observa = "";
				foreach($OServ as $key => $auto)
				{
					foreach($autorizar as $keyI => $auto)
					{
						$html .= "						<tr class=\"label\">\n";
						$html .= "							<td align=\"center\" colspan=\"7\">$keyI</td>\n";
						$html .= "						</tr>\n";
						foreach($auto as $keyI => $autoriza)
						{
							($autoriza['tipo_autorizador'] == 'I')? $tipo_auto = "INTERNA": $tipo_auto = "EXTERNA";
							$html .= "		<tr class=\"modulo_list_claro\">\n";
							$html .= "			<td>".$autoriza['autorizacion']."</td>\n";
							$html .= "			<td align=\"center\">".$autoriza['fecha']."</td>\n";
							$html .= "			<td>".$autoriza['responsable']."</td>\n";
							$html .= "			<td class=\"label\">".$tipo_auto." - ".$autoriza['tipo_autorizacion']."</td>\n";
							$html .= "			<td>".$autoriza['codigo_autorizacion_generador']."</td>\n";
							$html .= "			<td>".$autoriza['codigo_autorizacion']."</td>\n";
							$html .= "			<td>".$autoriza['descripcion_autorizacion']."</td>\n";
							$html .= "		</tr>\n";
							if($autoriza['observaciones'] != "")
								$observa = "	<li>".$autoriza['observaciones']."</li>\n";
						}
					}
				}
				$html .= "	</table>\n";
				if($observa != "")
				{
					$html .= "	<table width=\"100%\">\n";
					$html .= "		<tr class=\"label\">\n";
					$html .= "			<td>\n";
					$html .= "				OBSERVACIONES GENERALES\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr>\n";
					$html .= "			<td $stl>\n";
					$html .= "				<ul>".$observa."</ul>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "	</table>\n";
				}
			}
			$usuario = $clz->ObtenerInformacionUsuario(UserGetUID());
			$html .= "	<br><table border='0' width=\"100%\" align=\"left\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Fecha Impresión :&nbsp;&nbsp;".date("d/m/Y - h:i a")."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";			
			$html .= "			<td align=\"justify\">\n";
			$html .= "				<font size='1' face='arial'>\n";
			$html .= "					Imprimió:&nbsp;".$usuario['nombre']."\n";
			$html .= "				</font>\n";
			$html .= "			</td>\n";

			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			return $html;		
		}
	}
?>
