<?php
	/************************************************************************************  
	* $Id: app_Glosas_userclasses_HTML.php,v 1.1 2009/09/02 13:02:28 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	*
	* Clase de la vista HTML del modulo de glosas 
	* 
	* @author Hugo F. Manrique 
  *
	*/
	IncludeClass("ClaseHTML");
	class app_Glosas_userclasses_HTML extends app_Glosas_user
	{
		/****************************************************************************** 
		* Constructor
		* 
		* @access private
		*******************************************************************************/
		function app_Glosas_user_HTML()
		{
			$this->app_Glosas_user();
			$this->salida='';
		}
		/****************************************************************************** 
		* Función principal que da las opciones para tener acceso a los 
		* datos de CARTERA
		*******************************************************************************/
		function PrincipalCartera2()//Llama a todas las opciones posibles
		{
			UNSET($_SESSION['glosas']);
			SessionDelVar('PermisosResponder');
			if($this->UsuariosCartera()==false)	return false;
			
			return true;
		}
		/****************************************************************************** 
		* Muestra el menú principal de cartera.
		* 
		* @access public 
		******************************************************************************/ 
		function MostrarMenuPrincipalGlosas()
		{
			if(empty($_SESSION['glosas']['empresa_id']))
			{
				$_SESSION['glosas']['empresa_id']=$_REQUEST['permisoglosas']['empresa_id'];
				$_SESSION['glosas']['sw_clientes']=$_REQUEST['permisoglosas']['sw_clientes'];
				$_SESSION['glosas']['razon_social']=$_REQUEST['permisoglosas']['razon_social'];
				$_SESSION['glosas']['tipo_id'] = $_REQUEST['permisoglosas']['tipo_id_tercero'];
				$_SESSION['glosas']['id'] = $_REQUEST['permisoglosas']['id'];
				SessionSetVar('PermisosResponder',$_REQUEST['permisoglosas']['sw_responder']);
			}
			
			unset($_SESSION['SqlBuscar']);
			unset($_SESSION['SqlContar']);
			$this->MostrarInformacionFacturas();
			return true;
		}
		/******************************************************************************************
		* Funcion donde se realiza la forma que muestra la informacion de todas las facturas que 
		* hay o de las que se buscaron 
		* 
		* @return boolean 
		*******************************************************************************************/
		function FormaMostrarInformacionFacturas()
		{
			$this->salida .= ThemeAbrirTabla("CONSULTA GLOSAS");
			if($_SESSION['glosas']['sw_clientes'] == 1 ||
				($_SESSION['glosas']['sw_clientes'] == 0 && $this->ObtenerPermisosUsuariosGlosas()>0))
			{
				$this->salida .= "<script language=\"javascript\">\n";
				$this->salida .= "	function mOvr(src,clrOver)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		src.style.background = clrOver;\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function mOut(src,clrIn)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		src.style.background = clrIn;\n";
				$this->salida .= "	}\n";
				$this->salida .= "</script>\n";
				$this->salida .= "<table width=\"70%\" align=\"center\" >\n";		
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			".$this->Buscador();
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td><br>\n";
				$this->salida .= "			".$this->BuscadorRapidoFactura();
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table><br>\n";
				
				if($this->glosas)
				{
					$FacturasG = $this->glosas;
					if(sizeof($FacturasG) > 0)
					{
						$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
						$this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"21\">\n";
						$this->salida .= "				<td width=\"6%\">FACTURA</td>\n";
						$this->salida .= "				<td width=\"6%\">GLOSA</td>\n";
						$this->salida .= "				<td width=\"8%\">FECHA</td>\n";
						$this->salida .= "				<td width=\"%\" >CLIENTE</td>\n";
						$this->salida .= "				<td width=\"8%\" >Nº ENVIO</td>\n";
						$this->salida .= "				<td width=\"11%\">F. RADICACION</td>\n";
						$this->salida .= "				<td width=\"8%\">TOTAL</td>\n";
						$this->salida .= "				<td width=\"8%\">SALDO</td>\n";
						$this->salida .= "				<td width=\"11%\">ESTADO</td>\n";
						$this->salida .= "				<td width=\"5%\">OPCION</td>\n";
						$this->salida .= "			</tr>";
						
						$datosr = array();
						$estilo = 'modulo_list_oscuro';
						
						foreach($FacturasG as $key => $cliente )
						{
							foreach($cliente as $keyII => $facturas)
							{
								if($estilo == "modulo_list_claro")
								{
								  $estilo='modulo_list_oscuro';  $background = "#CCCCCC";
								}
								else
								{
								  $estilo='modulo_list_claro';  $background = "#DDDDDD";
								}
			
								$metodo = "ConsultarInformacionGlosa";
								
								if($facturas['sw_estado'] === null)
									$metodo = "MostrarInformacionDetalleFactura";
																
								$datosr['sistema'] = $facturas['sistema'];
								$datosr['prefijo'] = $facturas['prefijo'];
								$datosr['glosa_id'] = $facturas['glosa_id'];
								$datosr['sw_estado'] = $facturas['sw_estado'];
								$datosr['envio_numero'] = $facturas['envio_id'];
								$datosr['factura_fiscal'] = $facturas['factura_fiscal'];
													
								($facturas['envio_id'] == '0' && $facturas['sistema'] != "SIIS")? $envio = " ":$envio = $facturas['envio_id'];
								($facturas['fecha_radicacion'] == '0')? $fecha_envio = "":$fecha_envio =$facturas['fecha_radicacion'];
															
								switch($facturas['sw_estado'])
								{
									case '0': $info = "ANULADA"; break;
									case '1': $info = "POR REVISAR"; break;
									case '2': $info = "POR CONTABILIZAR"; break;
									case '3': $info = "CERRADA"; break;
								}
								
								$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
								$this->salida .= "				<td align=\"left\"   >".$facturas['prefijo']." ".$facturas['factura_fiscal']."</td>\n";
								$this->salida .= "				<td align=\"center\" >".$facturas['glosa_id']."</td>\n";
								$this->salida .= "				<td align=\"center\" >".$facturas['fecha_registro']."</td>\n";
								$this->salida .= "				<td align=\"justify\">".$key."</td>\n";
								$this->salida .= "				<td align=\"right\"  >".$envio."</td>\n";
								$this->salida .= "				<td align=\"center\" >".$fecha_envio."</td>\n";
								$this->salida .= "				<td align=\"right\"  >".FormatoValor($facturas['total_factura'])."</td>\n";
								$this->salida .= "				<td align=\"right\"  >".FormatoValor($facturas['saldo'])."</td>\n";
								$this->salida .= "				<td align=\"center\" ><b class='label_mark'>".$info."</b></td>\n";
								$this->salida .= "				<td align=\"center\" >\n";
								$this->salida .= "					<a class=\"label-error\" href=\"".(ModuloGetURL('app','Glosas','user',$metodo,$datosr))."\" title=\"GLOSAR FACTURA/VER DETALLE GLOSA\">\n";
								$this->salida .= "						<img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"><b>VER</b></a>\n";
								$this->salida .= "				</td>\n";
								$this->salida .= "			</tr>\n";
							}
						}
						$this->salida .= "	</table><br>\n";
						
						if($this->mostrar_reporte != '0' && $this->mostrar_reporte)
						{	
							$reporte = new GetReports();
							$mostrar = $reporte->GetJavaReport('app','Glosas','registrogrupoglosas',$this->request,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							$funcion = $reporte->GetJavaFunction();
							$this->salida .= $mostrar;
							$this->salida .= "  <br><center><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion\" class=\"label_error\">REPORTE BUSQUEDA</a></center><br>\n";
						}
						
						$this->salida .= "		".ClaseHTML::ObtenerPaginado($this->conteo,$this->paginaActual,$this->action1['paginador']);
						$this->salida .= "		<br>\n";
					}
					else
					{
						$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
					}
				}
			}				
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">SU USUARIO NO TIENE PERMISOS SOBRE ESTA EMPRESA</b></center><br><br>\n";
			}

			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del buscador 
		* 
		* @return string 
		**********************************************************************************************/
		function Buscador()
		{
			$buscador  = "<form name=\"buscador\" action=\"".$this->actionBuscador."\" method=\"post\">\n";
			$buscador .= "	<script>\n";
			$buscador .= "		function acceptDate(evt)\n";
			$buscador .= "		{\n";
			$buscador .= "			var nav4 = window.Event ? true : false;\n";
			$buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$buscador .= "		}\n";
			$buscador .= "		function acceptNum(evt)\n";
			$buscador .= "		{\n";
			$buscador .= "			var nav4 = window.Event ? true : false;\n";
			$buscador .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$buscador .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$buscador .= "		}\n";
			$buscador .= "	function LimpiarCampos(frm)\n";
			$buscador .= "	{\n";
			$buscador .= "		for(i=0; i<frm.length; i++)\n";
			$buscador .= "		{\n";
			$buscador .= "			switch(frm[i].type)\n";
			$buscador .= "			{\n";
			$buscador .= "				case 'text': frm[i].value = ''; break;\n";
			$buscador .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$buscador .= "			}\n";
			$buscador .= "		}\n";
			$buscador .= "	}\n";
			$buscador .= "	</script>\n";
			$buscador .= "	<fieldset class=\"fieldset\"><legend>BUSCADOR AVANZADO</legend>\n";
			$buscador .= "		<table>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">TIPO DOCUMENTO CLIENTE</td>\n";
			$buscador .= "				<td colspan=\"2\">\n";
			$buscador .= "					<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			$TiposTerceros = $this->ObtenerTipoIdTerceros();
			foreach($TiposTerceros as $key => $opciones)
			{
				($this->TipoIdTercero == $opciones['tipo_id_tercero'])? $sel = "selected":$sel="";
				$buscador .= "						<option value='".$opciones['tipo_id_tercero']."' $sele>".ucwords(strtolower($opciones['descripcion']))."</option>\n";
			}
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td class=\"label\" >DOCUMENTO</td>\n";
			$buscador .= "				<td >\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"tercero_id\" size=\"25\" maxlength=\"32\"  value=\"".$this->TerceroId."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">CLIENTE</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" size=\"25\" value=\"".$this->NombreTercero."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">Nº ENVIO</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"numero\" size=\"25\" maxlength=\"100\" onkeypress=\"return acceptNum(event)\" value=\"".$this->Numero."\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">TIPO DE VISTA</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			
			switch($this->TipoDocumento )
			{
				case '1': $uno = "selected"; break;	
				
				case '2': $dos = "selected"; break;	
	
				case '3': $tres = "selected"; break;	
	
				case '4': $cuatro = "selected";break;
				
				default:  $cero = "selected"; break;	
	
			}
			
			$buscador .= "					<select name=\"tipo_documento\" class=\"select\">\n";
			$buscador .= "						<option value='0' $cero>ENVIOS RADICADOS</option>\n";
			$buscador .= "						<option value='1' $uno >ENVIOS SIN RADICAR</option>\n";
			$buscador .= "						<option value='2' $dos >TODOS LOS ENVIOS</option>\n";
			$buscador .= "						<option value='3' $tres>FACTURAS SIN ENVIAR</option>\n";
			$buscador .= "						<option value='4' $cuatro>TODAS LAS FACTURAS</option>\n";
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";			
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">FECHA</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaInicio."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_inicio','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "				<td>\n";
			$buscador .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaFin."\">\n";
			$buscador .= "				</td><td class=\"label\">\n";
			$buscador .= "					".ReturnOpenCalendario('buscador','fecha_fin','/')."\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">ESTADO DE LA GLOSA</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			switch($this->EstadoGlosa)
			{
				case '1': $opc1 = "selected"; break;
				case '2': $opc2 = "selected"; break;	
				case '3': $opc3 = "selected"; break;
				case '4': $opc4 = "selected"; break;
				case '5': $opc5 = "selected"; break;
			}
			$buscador .= "					<select name=\"estado_glosa\" class=\"select\">\n";
			$buscador .= "						<option value='0' >---- SELECCIONAR ----</option>\n";
			$buscador .= "						<option value='4' $opc4>ANULADAS</option>\n";
			$buscador .= "						<option value='5' $opc5>CERRADAS</option>\n";
			$buscador .= "						<option value='1' $opc1>CON GLOSA ACTIVA</option>\n";
			$buscador .= "						<option value='2' $opc2>POR CONTABLIZAR</option>\n";
			$buscador .= "						<option value='3' $opc3>POR REVISAR</option>\n";
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\">AUDITOR</td>\n";
			$buscador .= "				<td colspan=\"4\">\n";
			$buscador .= "					<select name=\"auditor_sel\" class=\"select\">\n";
			$buscador .= "						<option value='0'>-----SELECCIONAR-----</option>\n";
			
			IncludeClass('GlosaDetalle','','app','Glosas');
			$gld = new GlosaDetalle();
			$Auditores = $gld->ObtenerAuditoresInternos();
			for($i=0; $i<sizeof($Auditores); $i++)
			{
				$opciones = $Auditores[$i];
				($this->AuditorSel == $opciones['usuario_id'])? $sel = "selected":$sel="";
				
				$buscador .= "						<option value='".$opciones['usuario_id']."' $sel>".ucwords(strtolower($opciones['nombre']))."</option>\n";
			}
			
			$buscador .= "					</select>\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "			<tr>\n";
			$buscador .= "				<td class=\"label\" align=\"center\" colspan=\"5\"><br>\n";
			$buscador .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
			$buscador .= "				</td>\n";
			$buscador .= "			</tr>\n";
			$buscador .= "		</table>\n";
			$buscador .= "	</fieldset>\n";
			$buscador .= "</form>\n";
			
			return $buscador; 
		}
		/*********************************************************************************************
		* Funcion donde se realiza la forma del buscador rapido de facturas  
		* 
		* @return string  
		**********************************************************************************************/
		function BuscadorRapidoFactura()
		{
			$buscador  = "<form name=\"buscadorfacturas\" action=\"".$this->actionBuscadorF."\" method=\"post\">\n";
			$buscador .= "	<table class=\"modulo_table_list\" width=\"100%\">\n";
			$buscador .= "		<tr><td class=\"modulo_table_list_title\">\n";
			$buscador .= "				BUSCADOR RAPIDO DE FACTURAS:&nbsp;\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<select name=\"prefijo_factura\" class=\"select\">\n";
			$Filas = $this->ObtenerPrefijos();
			for($i=0; $i<sizeof($Filas); $i++)
			{
				($this->request['prefijo_factura'] == $Filas[$i])? $sel = "selected": $sel = "";

				$buscador .= "				<option value='".$Filas[$i]."' $sel>".$Filas[$i]."</option>\n";
			}
			$buscador .= "				</select>\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"text\" class=\"input-text\" name=\"factura_fiscal\" size=\"25\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->request['factura_fiscal']."\">\n";
			$buscador .= "			</td>\n";
			$buscador .= "			<td>\n";
			$buscador .= "				<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$buscador .= "		</td></tr>\n";
			$buscador .= "	</table>\n";
			$buscador .= "</form>\n"; 
			return $buscador;
		}
		/*********************************************************************************************
		* Forma donde se muestra el detalle de la factura 
		**********************************************************************************************/
		function FormaMostrarDetalleFactura()
		{
			$this->salida .= ThemeAbrirTabla("INFORMACION FACTURA A GLOSAR");
//echo $this->action;
			$this->salida .= "<form name=\"informacion_factura\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "	<script>\n";
			$this->salida .= "		function MostrarCapa(valor)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				ele = document.getElementById('responder').style\n";
			$this->salida .= "				if(valor == true)\n";
			$this->salida .= "					ele.display= 'block';\n";
			$this->salida .= "				else\n";
			$this->salida .= "					ele.display= 'none';\n";
			$this->salida .= "			}catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ResponderGlosa(frm)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			//alert(document.getElementById('glosa_documento').value);\n";
			$this->salida .= "			if(document.getElementById('glosa_documento').value == 'on' && document.getElementById('concepto_general').value == 'V')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.getElementById('error').innerHTML='<center>DEBE SELECCIONAR UN CONCEPTO GENERAL</center>';\n";
			$this->salida .= "				return;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			frm.action = '".$this->actiong['responder']."';\n";
			$this->salida .= "			frm.submit();\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptDate(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function AsignarClasificacion(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var tipo = objeto.motivos_glosa.value.split(\"/\")[1];\n";
			$this->salida .= "			if(tipo != undefined)\n";
			$this->salida .= "				objeto.clasificacion_glosa.selectedIndex = tipo;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";	
			$this->salida .= "		function pasarValor(objeto)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			objeto.glosaValor.value = objeto.saldoValor.value;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<div class=\"label_error\">\n";
			$this->salida .= "			<center>".$this->frmError['MensajeError']."</center>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "	<div class=\"label_error\" id=\"error\">\n";
			$this->salida .= "			\n";
			$this->salida .= "	</div>\n";
			
			IncludeClass('Glosas','','app','Glosas');
			$gl = new Glosas();
			$cerradas = $gl->ObtenerGlosasAnteriores($this->request['prefijo'],$this->request['factura_fiscal'],$_SESSION['glosas']['empresa_id']);
			
			if($cerradas)
			{	
				$this->salida .= "	<center>\n";
				$this->salida .= "		<fieldset class=\"fieldset\" style=\"width:90%\">\n";
				$this->salida .= "			<legend>GLOSAS HECHAS ANTERIORMENTE</legend>\n";
				$this->salida .= "			<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"formulacion_table_list\">\n";
				$this->salida .= "					<td width=\"8%\">Nro</td>\n";
				$this->salida .= "					<td width=\"12%\">FECHA</td>\n";
				$this->salida .= "					<td width=\"22%\">MOTIVO - C. GENERAL / ESPECIFICO </td>\n";
				$this->salida .= "					<td width=\"22%\">AUDITOR</td>\n";
				$this->salida .= "					<td width=\"12%\">VALOR</td>\n";
				$this->salida .= "					<td width=\"12%\">ACEPTADO</td>\n";
				$this->salida .= "					<td width=\"12%\">NO ACEPTADO</td>\n";
				$this->salida .= "				</tr>\n";
				foreach($cerradas as $key => $gls)
				{	
					$this->salida .= "				<tr class=\"normal_10AN\">\n";
					$this->salida .= "					<td>".$gls['glosa_id']."</td>\n";
					$this->salida .= "					<td align=\"center\">".$gls['registro']."</td>\n";
					if($gls['motivo_glosa_descripcion'] <> 'NINGUNO' AND $gls['motivo_glosa_descripcion'] <> '')
					{
						$this->salida .= "					<td>".$gls['motivo_glosa_descripcion']."</td>\n";
					}
					else
					{
						$this->salida .= "					<td>".$gls['descripcion_concepto_general']." / ".$gls['descripcion_concepto_especifico']."</td>\n";
					}
					$this->salida .= "					<td>".$gls['nombre']."</td>\n";
					$this->salida .= "					<td align=\"right\">$".FormatoValor($gls['valor_glosa'])."</td>\n";
					$this->salida .= "					<td align=\"right\">$".FormatoValor($gls['valor_aceptado'])."</td>\n";
					$this->salida .= "					<td align=\"right\">$".FormatoValor($gls['valor_no_aceptado'])."</td>\n";
					$this->salida .= "				</tr>\n";
				}
				$this->salida .= "			</table>\n";
				$this->salida .= "		</fielset>\n"; 
				$this->salida .= "	</center><br>\n";
			}
			
			$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:8pt\" width=\"17%\">ENTIDAD</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">".$this->EntidadNombre."</td>\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:8pt\" width=\"17%\">".$this->EntidadNit."</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\" width=\"15%\">".$this->EntidadId."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td style=\"text-align:left;text-indent:8pt\">FACTURA No</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">".$this->request['prefijo']." ".$this->request['factura_fiscal'];
			$this->salida .= "			<td style=\"text-align:left;text-indent:8pt\">FECHA</td>\n";
			$this->salida .= "			<td align=\"left\" class=\"modulo_list_claro\">".$this->FacturaFecha;
			$this->salida .= "		</tr>\n";
			if($this->request['envio_numero'])
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\">ENVIO No</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->request['envio_numero']."</td>\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\">F.RADICACION</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->EnvioFecha;
				$this->salida .= "			</tr>\n";
			}
			if($this->PlanDescripcion)
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\">PLAN</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanDescripcion."</td>\n";
				$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\">No CONTRATO</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanNumContrato."</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "		</table><br>\n";
			
			$this->salida .= "		<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>FECHA DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" align=\"left\">\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_glosamiento\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->FechaGlosamiento."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\">".ReturnOpenCalendario('informacion_factura','fecha_glosamiento','/')."</td>\n";
			$this->salida .= "			</tr>\n";
/*			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>MOTIVO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "					<select name=\"motivos_glosa\" class=\"select\" onChange=\"AsignarClasificacion(document.informacion_factura)\" disabled>\n";
			$this->salida .= "						<option value='V'>-------SELECCIONAR-------</option>\n";			
			
			IncludeClass('GlosaDetalle','','app','Glosas');
			$gld = new GlosaDetalle();
			
			$Motivos = $gld->ObtenerMotivosGlosas();
 		
			foreach($Motivos as $key => $dtl)
			{
				($this->MotivoGlosa == $dtl['motivo_glosa_id'])? $sel = "selected": $sel = "";

				$this->salida .= "						<option value='".$dtl['motivo_glosa_id']."/".$dtl['glosa_tipo_clasificacion_id']."' $sel title=\"".$dtl['motivo_glosa_descripcion']."\" >".substr($dtl['motivo_glosa_descripcion'],0,32)."</option>\n";			
			}
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";*/
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>CLASIFICACION DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "					<select name=\"clasificacion_glosa\" class=\"select\">\n";
			$this->salida .= "						<option value='V'>-------SELECCIONAR-------</option>\n";			
			
			$Clasificacion = $this->ObtenerClasificacionGlosas();
			for($i=0; $i<sizeof($Clasificacion); $i++)
			{
				($this->ClasificacionGlosa == $Clasificacion[$i]['gtci'])? $sel = "selected": $sel = "";
				
				$this->salida .= "						<option value='".$Clasificacion[$i]['gtci']."' $sel>".$Clasificacion[$i]['descripcion']."</option>\n";			
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>AUDITOR</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "					<select name=\"auditor_interno\" class=\"select\">\n";
			$this->salida .= "						<option value='V'>-------SELECCIONAR-------</option>\n";			

			IncludeClass('GlosaDetalle','','app','Glosas');
			$gld = new GlosaDetalle();
			
			$Auditores = $gld->ObtenerAuditoresInternos();
			for($i=0; $i<sizeof($Auditores); $i++)
			{
				($this->AuditorId == $Auditores[$i]['usuario_id'])? $sel = "selected": $sel = "";

				$this->salida .= "						<option value='".$Auditores[$i]['usuario_id']."' $sel>".$Auditores[$i]['nombre']."</option>\n";
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			
			//CONCEPTO GENERAL / ESPECIFICO
			$js = "<script>\n";
			$js .= "function GetConceptos(valor,vector){\n";
			$js .= "  var vect;\n";
			$js .= "  for(i=0; i<vector.length; i++){\n";
			$js .= "	  switch(vector[i].type)\n";
			$js .= "	  {\n";
			$js .= "		  case 'radio':  \n";
			$js .= "			  vect = vector[i].value.split('||//')[0];\n";
			$js .= "			  document.getElementById('concepto'+vect).style.display = 'none';\n";
			$js .= "		  break;\n";
			$js .= "	  }\n";
			$js .= "   \n";
			$js .= "  }\n";
			$js .= "  identificador = 'concepto'+valor;\n";
			$js .= "  if(document.getElementById(identificador).style.display == 'none'){\n";
			$js .= "    document.getElementById(identificador).style.display = 'block';\n";
			$js .= "  }else{ \n";
			$js .= "    document.getElementById(identificador).style.display = 'none';\n";
			$js .= "  } ;\n";
			$js .= "}\n";
			$js .= "</script>\n";
			$this->salida .= "$js";
			IncludeClass('Glosas','','app','Glosas');
			$gld = new Glosas();
		
			$ConceptosGenerales = $gld->ObtenerConceptosGeneralesEdit();
			
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>CONCEPTO GENERAL</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "					<select name=\"concepto_general\" id=\"concepto_general\" class=\"select\" onchange=\"GetConceptos(this.value,document.informacion_factura);\">\n";
			$this->salida .= "						<option value='V' selected>-------SELECCIONAR-------</option>\n";			

			for($i=0; $i<sizeof($ConceptosGenerales); )
			{
				$k = $i;
				while($ConceptosGenerales[$i]['codigo_concepto_general'] == $ConceptosGenerales[$k]['codigo_concepto_general'])
				{
					$k++;
				}
				$i = $k;
 				(trim($this->CodigoCG) == trim($ConceptosGenerales[$i-1]['codigo_concepto_general']))? $sel = "selected": $sel = "";
				$this->salida .= "					<option value='".$ConceptosGenerales[$i-1]['codigo_concepto_general']."' $sel>".$ConceptosGenerales[$i-1]['descripcion_concepto_general']."</option>\n";
			}
			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			//
			$this->salida .= "			<tr class=\"modulo_table_list\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\" colspan=\"3\">\n";
			for($i=0; $i<sizeof($ConceptosGenerales);)
			{
        $none = 'none';
        if($ConceptosGenerales[$i][codigo_concepto_general] == $this->CodigoCG AND $this->CodigoCE)
        { $none = 'block';}
          $this->salida .= "<div id='concepto".$ConceptosGenerales[$i][codigo_concepto_general]."' style=\"display:$none\">";
          $this->salida .= "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
          $this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
          $this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
          $this->salida .= "						</td>\n";
          $this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
          $this->salida .= "						</td>\n";
          $this->salida .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
          $this->salida .= "						</td>\n";
          $this->salida .= "					</tr>\n";
          $k = $i;
          while ($ConceptosGenerales[$i][codigo_concepto_general] == $ConceptosGenerales[$k][codigo_concepto_general])
          {
            $checked = "";
            if($this->CodigoCE == $ConceptosGenerales[$k][codigo_concepto_especifico] && $ConceptosGenerales[$i][codigo_concepto_general] == $this->CodigoCG)
            {$checked = 'checked';}
            $this->salida .= "				<tr class=\"modulo_table_list\">\n";
            $this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"concepto_especifico\" value=\"".$ConceptosGenerales[$k][codigo_concepto_general]."||//".$ConceptosGenerales[$k][codigo_concepto_especifico]."\" ".$checked.">\n";
            $this->salida .= "					</td>\n";
            $this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][codigo_concepto_especifico]."</b>\n";
            $this->salida .= "					</td>\n";
            $this->salida .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$ConceptosGenerales[$k][descripcion_concepto_especifico]."</b>\n";
            $this->salida .= "					</td>\n";
            $this->salida .= "				</tr>\n";
            $k++;
          }
          $i = $k;
          $this->salida .= "				</table><br>\n";
          $this->salida .= "</div>";  
        
			}
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			//
			
			//FIN CONCEPTO GENERAL / ESPECIFICO
			
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			if($this->GlosaDocumento == 'on')
			{
				($this->Sistema == "EXT")? $check = "checked disabled=\"true\" ": $check = "checked";
			}		
			$this->salida .= "				<td colspan=\"4\" class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">\n";
			$this->salida .= "					<input type=\"checkbox\" name=\"glosa_documento\" id=\"glosa_documento\" $check onclick=\"MostrarCapa(this.checked)\">GLOSA DE TODO EL DOCUMENTO\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td colspan=\"4\"><b>DESCRIPCION</b></td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\">";
			$this->salida .= "					<textarea class=\"textarea\" name=\"descripcion_glosa\" style=\"width:100%\" rows=\"3\">".$this->DescripcionGlosa."</textarea>";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>No DE DOCUMENTO DE INTERNO DEL CLIENTE</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"3\">\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"documento_glosa\" size=\"25\" maxlength=\"80\" value=\"".$this->DocumentoGlosa."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			if($this->Sistema == "EXT")
			{
				$this->salida .= "				<td style=\"text-align:left;text-indent:8pt\"><b>VALOR DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td class=\"modulo_list_claro\" align=\"right\">$".formatoValor($this->SaldoFactura)."</td>\n";
				$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\">\n";
				$this->salida .= "					<img src=\"".GetThemePath()."/images/ultimo.png\" title=\"PASAR EL VALOR DE LA FACTURA\" onclick=\"pasarValor(document.informacion_factura)\">\n";
				$this->salida .= "					<input type=\"hidden\" name=\"saldoValor\" value=\"".$this->SaldoFactura."\"></td>\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\">\n";
				$this->salida .= "					<b>$</b><input type=\"text\" name=\"glosaValor\" class=\"input-text\" size=\"10\" value=\"".$this->GlosaValor."\" onKeypress=\"return  acceptNum(event)\">\n";
				$this->salida .= "				</td>\n";
			}
			else
			{
				$this->salida .= "				<td colspan=\"4\">\n";
				$this->salida .= "					<input type=\"hidden\" name=\"saldoValor\" value=\"".$this->SaldoFactura."\">\n";
				$this->salida .= "					<input type=\"hidden\" name=\"glosaValor\" value=\"".$this->SaldoFactura."\">\n";
				$this->salida .= "				</td>\n";
			}
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= "		<table width=\"60%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Guardar\">\n";
			$this->salida .= "				</td>\n";
			if(SessionGetVar('PermisosResponder') == '1' && !$this->NoResponder)
			{
				$this->salida .= "				<td align=\"center\">\n";
				$display = "none";
				if($this->Sistema == "EXT" || $this->GlosaDocumento == 'on') $display = "block";
				$this->salida .= "					<div id=\"responder\" style=\"display:$display\">\n";
				$this->salida .= "						<input type=\"button\" class=\"input-submit\" value=\"Aceptar - Responder\" onclick=\"ResponderGlosa(document.informacion_factura)\">\n";
				$this->salida .= "					</div>\n";
				$this->salida .= "				</td>\n";
			}
			$this->salida .= "			</form>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->actionV."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************** 
		* Funcion que retorna el mensaje que se desea desplegar en la forma 
		* 
		* @return String cadena con el mensaje 
		***********************************************************************************/
		function SetStyle($campo)
		{
			if ($this->frmError[$campo])
			{
				if ($campo=="MensajeError")
				{
					return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
				}
				if($campo == "Informacion")
				{
					return ("<tr><td class='label' colspan='3' align='center'>".$this->frmError["Informacion"]."</td></tr>");
				}
				return ("<tr><td>&nbsp;</td></tr>");
			}
			return ("<tr><td>&nbsp;</td></tr>");
		}
		/********************************************************************************* 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaInformacion($parametro)
		{
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			".$this->SetStyle("MensajeError")."\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$parametro."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			if($this->actionM)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->actionM."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/*********************************************************************************************
		* Forma donde se muestra la informacion de la factura cuando se ha glosado 
		**********************************************************************************************/
		function FormaMostrarConsultaGlosa()
		{
			$style = "style=\"text-align:left;text-indent:6pt\" ";
			SessionSetVar("EstadoGlosa",$this->EstadoGlosa);
			
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			
			$this->SetXajax(array("AnularGlosa"),"app_modules/Glosas/RemoteXajax/Observaciones.php");

			$this->salida .= ThemeAbrirTabla("INFORMACIÓN GLOSA Nro ".$this->request['glosa_id']);
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  window.status = '';\n";
			$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
			$this->salida .= "	  ele.myTotalMX = 0;\n";
			$this->salida .= "	  ele.myTotalMY = 0;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  if (ele.id == titulo) {\n";
			$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	  else {\n";
			$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$this->salida .= "	  }  \n";
			$this->salida .= "	  ele.myTotalMX += mdx;\n";
			$this->salida .= "	  ele.myTotalMY += mdy;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	}\n";	

			$this->salida .= "	function IniciarAnulacion()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'ContenedorI';\n";
			$this->salida .= "		titulo = 'tituloI';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,350, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+100);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,330, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarI');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 330, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function EvaluarDatosAnulacion(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		if(frm.observacion.value == '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			xGetElementById('error').innerHTML = 'SE DEBE INGRESAR EL MOTIVO POR EL CUAL SE ESTA ANULADO LA GLOSA'; \n";
			$this->salida .= "			return;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		xajax_AnularGlosa(frm.observacion.value,'".$this->request['glosa_id']."')\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			
			$this->salida .= "<div id='ContenedorI' class='d2Container' style=\"display:none;z-index:4\">\n";
			$this->salida .= "	<div id='tituloI' class='draggable' style=\"	text-transform: uppercase;text-align:center\">ANULAR GLOSA CUENTA</div>\n";
			$this->salida .= "	<div id='cerrarI' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('ContenedorI')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div>\n";
			$this->salida .= "	<div id='ContenidoI' class='d2Content' style=\"background:#EFEFEF\"><br><br>\n";
			$this->salida .= "		<form name=\"anulacion\" action=\"javascript:EvaluarDatosAnulacion(document.anulacion)\" method=\"post\">\n";
			$this->salida .= "			<center>\n";
			$this->salida .= "				<div id=\"error\" class=\"label_error\"></div>\n";
			$this->salida .= "			</center>\n";
			$this->salida .= "			<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "					<td>MOTIVO DE LA ANULACIÓN DE LA GLOSA</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\">\n";
			$this->salida .= "						<textarea id=\"observacion\" name=\"observacion\" style=\"width:100%\" class=\"textarea\" rows=\"3\"></textarea>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table><br>\n";
			$this->salida .= "			<center>\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$this->salida .= "				<input type=\"button\" class=\"input-submit\" value=\"Cancelar\" onclick=\"OcultarSpan('ContenedorI')\">\n";
			$this->salida .= "			</center><br>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= "		<table align=\"center\" width=\"65%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"25%\">ENTIDAD</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"50%\" colspan=\"3\">".$this->EntidadNombre."</td>\n";
			$this->salida .= "				<td $style width=\"8%\" ><b>".$this->EntidadNit."</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"17%\">".$this->EntidadId."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style >FACTURA Nro</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"30%\">".$this->request['prefijo']." ".$this->request['factura_fiscal'];
			$this->salida .= "				<td $style width=\"8%\" >TOTAL</td>\n";
			$this->salida .= "				<td align=\"right\" class=\"modulo_list_claro\">$".formatovalor($this->TotalFactura);
			$this->salida .= "				<td $style >FECHA</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" >".$this->FacturaFecha;
			$this->salida .= "			</tr>\n";
			if($this->request['envio_numero'])
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style>ENVIO Nro</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->request['envio_numero']."</td>\n";
				$this->salida .= "				<td $style width=\"25%\" colspan=\"2\">FECHA RADICACIÓN</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  width=\"25%\" colspan=\"2\">".$this->EnvioFecha;
				$this->salida .= "			</tr>\n";
			}
			if($this->PlanDescripcion)
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style>PLAN</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->PlanDescripcion."</td>\n";
				$this->salida .= "				<td $style colspan=\"2\">Nº CONTRATO</td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"  colspan=\"2\">".$this->PlanNumContrato."</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style>RESPONSABLE GLOSA</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan= \"5\">".$this->Usuario."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style >FECHA REGISTRO</td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">".$this->FechaRegistro."</td>\n";
			$this->salida .= "				<td $style colspan=\"2\"><b>FECHA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">".$this->FechaGlosamiento."</td>\n";			
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" cellpading=\"0\" width=\"65%\" border=\"0\" class=\"modulo_table_list\">\n";
			
			if($this->MotivoGlosaDescripcion != "" AND $this->MotivoGlosaDescripcion != 'NINGUNO')
			{
echo "-".$this->MotivoGlosaDescripcion."-";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>MOTIVO DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" >\n";
				$this->salida .= "					".$this->MotivoGlosaDescripcion."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->DescripcionCG)
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>CONCEPTO GENERAL</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" >\n";
				$this->salida .= "					".$this->DescripcionCG."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>CONCEPTO ESPECIFICO</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\" >\n";
				$this->salida .= "					".$this->DescripcionCE."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->ClasificacionGlosaDescripcion != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>CLASIFICACIÓN DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->ClasificacionGlosaDescripcion."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			if($this->AuditorInterno != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style ><b>AUDITOR</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->AuditorInterno."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style width=\"40%\"><b>TIPO GLOSA DOCUMENTO</b></td>\n";
			
			$boton = "0";
			if($this->SwGlosaTotal == '0' )
			{
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					<b class=\"label_mark\">GLOSA SOBRE INSUMOS Y/O CARGOS</b>\n";
				if($this->EstadoGlosa == '1' || $this->EstadoGlosa == '2')
					$this->salida .= "					<a href=\"".$this->action['cargos'] ."\"><b>GLOSAR DETALLE</b></font></a>\n";
				$this->salida .= "				</td>\n";
			}
			else
			{
				$boton = "1";
				($this->SwGlosaTotal == "1")? $desc = "LA GLOSA ES SOBRE TODO EL DOCUMENTO":$desc = "LA GLOSA PERTENECE A UNA FACURA EXTERNA";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"><b class=\"label_mark\">".$desc."</b></td>\n";
			}
			
			$this->salida .= "			</tr>\n";

			if($this->DocumentoGlosa != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>DOCUMENTO INTERNO DEL CLIENTE Nº</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".$this->DocumentoGlosa."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>VALOR DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->ValorGlosa)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>VALOR ACEPTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "					".formatoValor($this->ValorAceptado)."\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			
			if($this->ValorPendiente > 0 )
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td $style><b>VALOR PENDIENTE DE LA GLOSA</b></td>\n";
				$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\">\n";
				$this->salida .= "					".formatoValor($this->ValorPendiente)."\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			switch($this->EstadoGlosa)
			{
				case '1': $estado = "POR REVISAR"; 			break;
				case '2': $estado = "POR CONTABILIZAR"; break;
				case '3': $estado = "CERRADA"; 					break;
				case '0': $estado = "ANULADA"; 		 			break;
			}
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $style><b>ESTADO DE LA GLOSA</b></td>\n";
			$this->salida .= "				<td align=\"left\" class=\"modulo_list_claro\"><b class=\"label_mark\">".$estado."</td>\n";
			$this->salida .= "			</tr>\n";
			if($this->DescripcionGlosa != "")
			{
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td colspan=\"2\"><b>OBSERVACIÓN</b></td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"4\">";
				$this->salida .= "					<textarea class=\"textarea\" name=\"descripcion_glosa\" style=\"width:100%\" rows=\"3\" readonly>".$this->DescripcionGlosa."</textarea>";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
			}
			$this->salida .= "		</table><br>\n";
			
			if($this->SwGlosaTotal == '0')
			{				
				$this->salida .= "		<table align=\"center\" cellpading=\"0\" width=\"65%\" border=\"0\" class=\"modulo_table_list\">\n";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "				<td>Nº CUENTAS DE LA FACTURA</td>\n";
				$this->salida .= "				<td>CUENTAS GLOSADAS TOTALMENTE</td>\n";
				$this->salida .= "				<td>CARGOS - INSUMOS CON GLOSA ACTIVA</td>\n";
				$this->salida .= "				<td>CARGOS - INSUMOS CON GLOSA CERRADA</td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">\n";
				$this->salida .= "				<td class=\"normal_10AN\">".$this->datos_factura ['cuentas_numero']."</td>\n";
				$this->salida .= "				<td class=\"normal_10AN\">".$this->datos_factura ['cuentas_glosa']."</td>\n";
				$this->salida .= "				<td class=\"normal_10AN\">".$this->datos_factura ['cargos_insumos_activo']."</td>\n";
				$this->salida .= "				<td class=\"normal_10AN\">".$this->datos_factura ['cargos_insumos_cierre']."</td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "		</table><br>\n";
				
				if($this->datos_factura ['cargos_insumos_activo'] > 0 || $this->datos_factura ['cargos_insumos_cierre'] > 0)
				{
					$gl = new Glosas();
					$Cargos = $gl->ObtenerCargosGlosados($this->GlosaId,$this->EstadoGlosa);
					
					if(sizeof($Cargos) > 0)
					{
						foreach($Cargos as $key => $cuentas)
						{
							$this->salida .= "		<table align=\"center\" cellpading=\"0\"  width=\"80%\" border=\"0\" class=\"modulo_table_list\">\n";
							$this->salida .= "			<tr>\n";
							$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"20%\">NUMERO CUENTA: </td>\n";
							$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\" width=\"30%\"><label class=\"normal_10AN\">".$key."</label></td>\n";
							$bool = false;
							$tipo = "";
							foreach($cuentas as $keyII => $motivos)
							{
								$mtv = false;
								foreach($motivos as $keyIII => $detalle)
								{
									if(!$bool)
									{	
										if($detalle['tipo'] == "DA")
										{
											if($detalle['valor_glosa'] > 0)
											{
												$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"20%\">VALOR GLOSA</td>\n";
												$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\" width=\"30%\">".formatoValor($detalle['valor_glosa'])."</td>\n";
											}
											else
												$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"2\" width=\"50%\"></td>\n";
																		
											$this->salida .= "			</tr>\n";
											if($detalle['descripcion_asociado'])
											{
												$this->salida .= "			<tr>\n";
												$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"20%\">PACIENTE</td>\n";
												$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\"><b>".$detalle['asociado']." - ".$detalle['descripcion_asociado']."</b></td>\n";
												$this->salida .= "			</tr>\n";
											}									
											if($detalle['motivo_glosa_descripcion'])
											{
												$this->salida .= "			<tr>\n";
												$this->salida .= "				<td class=\"modulo_table_list_title\" width=\"20%\">MOTIVO DE GLOSA</td>\n";
												$this->salida .= "				<td class=\"modulo_list_claro\" colspan=\"3\">".$detalle['motivo_glosa_descripcion']."</td>\n";
												$this->salida .= "			</tr>\n";
											}
										}
										$bool = true;
									}
									if($tipo != $detalle['tipo'])
									{
										if($detalle['tipo'] == "DI")
										{
											$mtv = false;
											$this->salida .= "			<tr class=\"formulacion_table_list\">\n";
											$this->salida .= "				<td  colspan=\"4\">INSUMOS Y MEDICAMENTOS</td>\n";
											$this->salida .= "			</tr>\n";
											$this->salida .= "			<tr class=\"formulacion_table_list\">\n";
											$this->salida .= "				<td>PRODUCTO</td>\n";
											$this->salida .= "				<td colspan=\"2\">DESCRIPCION</td>\n";
											$this->salida .= "				<td>V GLOSA</td>\n";
											$this->salida .= "			</tr>\n";
										}
										else if($detalle['tipo'] == "DC")
										{
											$mtv = false;
											$this->salida .= "			<tr class=\"formulacion_table_list\">\n";
											$this->salida .= "				<td  colspan=\"4\">CARGOS</td>\n";
											$this->salida .= "			</tr>\n";
											$this->salida .= "			<tr class=\"formulacion_table_list\">\n";
											$this->salida .= "				<td>CARGO CUPS</td>\n";
											$this->salida .= "				<td colspan=\"2\">DESCRIPCION</td>\n";
											$this->salida .= "				<td>V GLOSA</td>\n";
											$this->salida .= "			</tr>\n";
										}
									}
									$tipo = $detalle['tipo'];
									
									if($detalle['tipo'] != "DA")
									{
										if(!$mtv)
										{
											$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
											$this->salida .= "				<td colspan=\"4\">MOTIVO DE GLOSA: ".$detalle['motivo_glosa_descripcion']."</td>\n";
											$this->salida .= "			</tr>\n";
											$mtv = true;
										}
										$this->salida .= "			<tr class=\"modulo_list_claro\">\n";
										$this->salida .= "				<td width=\"20%\" align=\"center\">".$detalle['asociado']."</td>\n";
										$this->salida .= "				<td class=\"normal_10AN\" colspan=\"2\" align=\"justify\">".$detalle['descripcion_asociado']."</td>\n";
										$this->salida .= "				<td width=\"20%\" align=\"center\"><b>$".FormatoValor($detalle['valor_glosa'])."</b></td>\n";
										$this->salida .= "			</tr>\n";
									}
								}
							}
							$this->salida .= "		</table>\n";
						}
					}
				}
			}

			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','Glosas','registroglosa',array("glosa"=>$this->request['glosa_id'],"sistema"=>$this->Sistema),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion = $reporte->GetJavaFunction();
			$this->salida .= $mostrar;
			$this->salida .= "  <br><center><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion\" class=\"label_error\">REPORTE</a></center><br>\n";

			$this->salida .= "	<table width=\"70%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			if($this->EstadoGlosa == '1')
			{
				$this->salida .= "				<td align=\"center\">\n";
				$this->salida .= "					<form name=\"informacion_factura\" action=\"".$this->action['editar']."\" method=\"post\">\n";
				$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Editar Glosa\">\n";
				$this->salida .= "					</form>\n";
				$this->salida .= "				</td>\n";
			}
			if(SessionGetVar('PermisosResponder') == '1' && ($this->EstadoGlosa == '1' || $this->EstadoGlosa == '2'))
			{
				$this->salida .= "				<td align=\"center\">\n";
				$this->salida .= "					<form name=\"respuesta\" action=\"".$this->action['responder']."\" method=\"post\">\n";
				$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Responder Glosa\">\n";
				$this->salida .= "					</form>\n";
				$this->salida .= "				</td>\n";
			}
			if($this->EstadoGlosa == '1' || $this->EstadoGlosa == '2')
			{
				$this->salida .= "				<td align=\"center\">\n";
				$this->salida .= "					<form name=\"modificarglosa\" action=\"javascript:IniciarAnulacion();MostrarSpan('ContenedorI')\" method=\"post\">\n";
				$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Anular Glosa\">\n";
				$this->salida .= "					</form>\n";
				$this->salida .= "				</td>\n";
			}
			$this->salida .= "				<td align=\"center\">\n";
			$this->salida .= "					<form name=\"volver\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "						<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "					</form>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/***********************************************************************************************
		* Forma donde se muestran los cargos que han sido glosados 
		************************************************************************************************/
		function FormaMostrarListadoCuentasFactura()
		{
			$this->MostrarListadoCuentasFactura();
			IncludeClass('GlosaDetalleHTML','','app','Glosas');
			$gldh = new GlosaDetalleHTML();
			
			$this->salida .= ThemeAbrirTabla("CUENTAS DE LA FACTURA Nº ".$this->request['datos_glosa']['prefijo']." ".$this->request['datos_glosa']['factura_fiscal']);
			$this->salida .= $gldh->FormaListarCuentas($this->request['datos_glosa'],$this->action);
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/***********************************************************************************************
		* Forma donde se muestran los cargos que han sido glosados 
		************************************************************************************************/
		function FormaMostrarDetalleCuenta()
		{			
			$this->MostrarDetalleCuenta();
			IncludeClass('GlosaDetalleHTML','','app','Glosas');
			$gldh = new GlosaDetalleHTML();
			
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			
			$this->SetXajax(array("ObtenerObservacion","IngresarObservacion","AnularGlosaCuenta","ModificarValores","AsignarConceptos", "ObtenerConcepGeneralesEspecificos"),"app_modules/Glosas/RemoteXajax/Observaciones.php");
			
			$this->salida .= ThemeAbrirTabla("DETALLE CUENTA Nro ".$this->request['datos_glosa']['numerodecuenta']);
			$this->salida .= $gldh->FormaDetalleCuenta($this->request['datos_glosa'],$this->action,SessionGetVar('PermisosResponder'));
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************************
		* 
		**************************************************************************************************/
		function FormaModificarGlosaCargoCuenta()
		{
			$this->ModificarGlosaCargoCuenta();
			IncludeClass('GlosaDetalleHTML','','app','Glosas');
			$gldh = new GlosaDetalleHTML();
			
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			
			$this->SetXajax(array("ActualizarDetalleGlosa","AnularDetalleGlosa", "ObtenerConcepGeneralesEspecificos"),"app_modules/Glosas/RemoteXajax/Observaciones.php");
			
			$this->salida .= ThemeAbrirTabla("EDITAR CARGO DE LA CUENTA Nro ".$this->request['datos_glosa']['numerodecuenta']);
			$this->salida .= $gldh->FormaModificarCargosInsumos($this->request,$this->action);
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/***********************************************************************************************
		* Forma donde se muestran los cargos que han sido glosados 
		************************************************************************************************/
		function FormaGuardarGlosa()
		{
			$this->GuardarGlosa();

			$this->salida .= ThemeAbrirTabla('INFORMACION');
			$this->salida .= "<form name=\"formaInformacion\" action=\"".$this->action['volver']."\" method=\"post\">\n";
			$this->salida .= "	<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$this->frmError['MensajeError']."<br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "	<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/***************************************************************************************************
		* Forma donde se muestra la informacion de la cuenta 
		****************************************************************************************************/
		function FormaResponderGlosa()
		{
			$rst = $this->ResponderGlosa();
			if($rst) 
			{
				$respuesta = $this->ReturnModuloExterno('app','AuditoriaCuentas','user');
				$datos = $respuesta->BuscarEmpresasUsuario($this->empresa); 
				
				$responder = array();
				foreach($datos as $key => $responder);
				
				$datos['factura_f'] = $this->request['factura_f'];
				$datos['prefijo'] = $this->request['prefijo'];
				
				$datos = $respuesta->ObtenerDatosClienteXFactura($datos, $this->empresa);
				$datos = $respuesta->ObtenerSqlBuscarDatosCliente($datos);
			
				$_REQUEST['tipo_id_tercero'] = $datos[0]['tipo_id_tercero'];
				$_REQUEST['tercero_id'] = $datos[0]['tercero_id'];
				$_REQUEST['nombre_tercero'] = $datos[0]['nombre_tercero'];
				
				$respuesta->SetActionVolver($this->actiong['volver'],$responder);
				$respuesta->SetActionNotaCreada($this->actiong['nota']);
				$respuesta->ConsultarInformacionGlosa();
				$this->salida = $respuesta->salida;
			}
			return true;
		}
		/***************************************************************************************************
		* Forma donde se muestra la informacion de la cuenta 
		****************************************************************************************************/
		function FormaResponderGlosaTotalFactura()
		{
			$rst = $this->ResponderGlosaTotal();
			if($rst) 
			{
				$respuesta = $this->ReturnModuloExterno('app','AuditoriaCuentas','user');
				$datos = $respuesta->BuscarEmpresasUsuario($this->empresa); 
				
				$responder = array();
				foreach($datos as $key => $responder);
				
				$responder['sistema'] = $this->request['sistema'];
				$respuesta->SetActionVolver($this->actiong['volver'],$responder);
				$respuesta->SetActionNotaCreada($this->actiong['nota']);
				
				$datos = $respuesta->ObtenerDatosClienteXFactura($this->request, $this->empresa);
				$datos = $respuesta->ObtenerSqlBuscarDatosCliente($datos);
				
				$_REQUEST['tipo_id_tercero'] = $datos[0]['tipo_id_tercero'];
				$_REQUEST['tercero_id'] = $datos[0]['tercero_id'];
				$_REQUEST['nombre_tercero'] = $datos[0]['nombre_tercero'];
				
				$respuesta->ConsultarInformacionGlosa();
				$this->salida = $respuesta->salida;
			}
			return true;
		}
	}//Fin de la clase
?>
