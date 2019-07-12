<?php
	/**************************************************************************************
	* $Id: app_Os_CentralAtencion_userclasses_HTML.php,v 1.1 2010/01/20 20:58:30 hugo Exp $
	*
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* $Revision: 1.1 $
	*
	* @autor Hugo F  Manrique
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	IncludeClass('AtencionOsHtml','','app','Os_CentralAtencion');
	class app_Os_CentralAtencion_userclasses_HTML extends app_Os_CentralAtencion_user
	{
		function app_Os_CentralAtencion_userclasses_HTML(){}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function main()
		{
			$this->FormaMenuPrincipal();
			return true;
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMenuPrincipal()
		{
			$url[0]='app';
			$url[1]='Os_CentralAtencion';
			$url[2]='user';
			$url[3]='FormaMenuAtencion';
			$url[4]='os_atencion';

			$titulo[0]='EMPRESA';
			$titulo[1]='Centro Utilidad';
			$titulo[2]='ATENCION DE ORDENES DE SERVICIO';
			
			$this->PermisosUsuario();
			$this->salida.= gui_theme_menu_acceso('ATENCION DE ORDENES DE SERVICIO',$titulo,$this->Permisos,$url,ModuloGetURL('system','Menu','user','main'));			
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMenuAtencion()
    {
			$aosh = new AtencionOsHtml();
			$this->MenuAtencion();
			$this->IncludeJS("TabPaneLayout");
			$this->IncludeJS("TabPaneApi");
      $this->IncludeJS("TabPane");
			$this->SetXajax(array("BuscarPaciente"),"app_modules/Os_CentralAtencion/RemoteXajax/Solicitud.php");
			$this->SetXajax(array("CitasPaciente","Ocultar"),"app_modules/Os_CentralAtencion/RemoteXajax/AtencionCitas.php");

			$this->salida .= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
			$this->salida .= "	<script>\n";
			$this->salida .= "		window.onload = function () {	Timer(); };\n";
			$this->salida .= "		function Timer()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.setTimeout('ActualizarListado()', 180000);\n";
			$this->salida .= "		};\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function EvaluarDatos(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			if(objeto.documento.value == '' && objeto.nombres1.value == '' && objeto.nombres2.value == '' &&";
			$this->salida .= "					objeto.apellidos1.value == '' && objeto.apellidos2.value == '' && objeto.numIngreso.value == '' && objeto.responsable.value == '-1')\n";
			$this->salida .= "				document.getElementById('error').innerHTML = \"<b class='label_error'>PARA HACER UNA BUSQUEDA, DIGITE UN VALOR.... (Documento, Nombres, Apellidos �Numero de orden)</b>\"\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				objeto.action = '".$this->action[0]."';\n";
			$this->salida .= "				objeto.submit();\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function AsignarValor(valor)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			var planes = valor.split('~'); \n";
			$this->salida .= "			if(planes[1] == '1')\n";
			$this->salida .= "			{ \n";
			$this->salida .= "				document.getElementById('errorM').innerHTML = \"<b class='label_error'>LOS PLANES SOAT DEBEN REALIZAR EL PROCESO EN LA CENTRAL DE AUTORIZACIONES.</b><br>\";\n";
			$this->salida .= "				document.formasolicitudmanual.plan_id.value = \"\";\n";
			$this->salida .= "			} \n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.formasolicitudmanual.plan_id.value = planes[0];\n";
			$this->salida .= "				document.getElementById('errorM').innerHTML = \"\";\n";			
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function BuscarLista()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.location.href=\"".$this->action[4]."\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ActualizarListado()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(tabPane.getSelectedIndex() == 1)\n";
			$this->salida .= "				BuscarLista();\n";
			$this->salida .= "			else\n";
			$this->salida .= "				Timer();";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EvaluarDatosSolicitud(objeto)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			if(objeto.plan.value == '-1' || objeto.tipo_id_paciente.value == '' || objeto.paciente_id.value == '' )\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.getElementById('errorM').innerHTML = \"<b class='label_error'>PARA LA REALIZACION DE LA SOLICITUD MANUAL SE DEBEN INGRESAR LOS DATOS SOLICITADOS</b>\"\n";
			$this->salida .= "				return;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			objeto.action = '".$this->action[3]."';\n";
			$this->salida .= "			objeto.submit();\n";
			$this->salida .= "		}\n";
			$this->salida .= "	function BuscarPaciente(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xajax_BuscarPaciente(frm.ingreso.value,frm.cuenta.value,frm.tipodocumento.value,frm.documento.value,frm.nombres.value,frm.apellidos.value,0);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function BuscarPacientesII(ingreso,cuenta,tipodocumento,documento,nombres,apellidos,offset)\n ";
			$this->salida .= "	{\n";
			$this->salida .= "		xajax_BuscarPaciente(ingreso,cuenta,tipodocumento,documento,nombres,apellidos,offset);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function LimpiarCampos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			switch(frm[i].type)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 'text': frm[i].value = ''; break;\n";
			$this->salida .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= $aosh->Encabezado($this->Datos)."<br>";
			if($this->frmError['Nota'])
			{
				$this->salida .= "<center>\n";
				$this->salida .= "	<div class=\"modulo_table_list\" style=\"width:80%; padding:4pt\">\n";
				$this->salida .= "		<label class=\"label_error\"> NOTA : ".$this->frmError['Nota']."</label>\n";
				$this->salida .= "	</div>\n";
				$this->salida .= "</center>\n";
			}
			$this->salida .= "	<table width=\"98%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<div class=\"tab-pane\" id=\"grupos\">\n";
			$this->salida .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"grupos\" ), false); </script>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"solicitudes\">\n";
			$this->salida .= "									<h2 class=\"tab\">BUSCAR ORDENES</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"solicitudes\")); </script>\n";
			$this->salida .= "									<table border=\"0\" width=\"81%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td id=\"error\" align=\"center\"></td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td>\n";
			$this->salida .= "												<fieldset class=\"fieldset\"><legend >BUSCADOR AVANZADO</legend>\n";
			$this->salida .= "													<form name=\"formabuscar\" action=\"javascript:EvaluarDatos(document.formabuscar)\" method=\"post\">";
			$this->salida .= "														<table width=\"100%\" align=\"center\" border=\"0\">";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\" width=\"20%\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "																<td colspan=\"3\">\n";
			$this->salida .= "																	<select name=\"tipoDocumento\" class=\"select\">\n";
			$this->salida .= "                										<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($this->Rst['tiposid'] as $key => $tipos)
			{
				($this->request['tipoDocumento'] == $key)? $csk = "selected": $csk = ""; 
				$this->salida .= "                										<option value=\"$key\" $csk >".$tipos['descripcion']."</option>";
			}
			$this->salida .= "																	</select>\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">DOCUMENTO:</td>\n";
			$this->salida .= "																<td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"documento\" maxlength=\"32\" value=".$this->request['documento']."></td>\n";
			
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">PRIMER NOMBRE:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"nombres1\" maxlength=\"32\" value=".$this->request['nombres1']."></td>\n";
			$this->salida .= "																<td class=\"normal_10AN\">SEGUNDO NOMBRE:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"nombres2\" maxlength=\"32\" value=".$this->request['nombres2']."></td>\n";			
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">PRMIER APELLIDO:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"apellidos1\" maxlength=\"32\" value=".$this->request['apellidos1']."></td>\n";
			$this->salida .= "																<td class=\"normal_10AN\">SEGUNDO APELLIDO:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"apellidos2\" maxlength=\"32\" value=".$this->request['apellidos2']."></td>\n";
			$this->salida .= "															</tr>";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">PLAN:</td>\n";
			$this->salida .= "																<td colspan=\"3\">\n";
			$this->salida .= "																	<select name=\"responsable\" class=\"select\">";
			$this->salida .= "                										<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($this->Rst['responsables'] as $key => $tipos)
			{
				($this->request['responsable'] == $key)? $csk = "selected": $csk = ""; 
				$this->salida .= "                										<option value=\"$key\" $csk>".$tipos['plan_descripcion']."</option>";
			}
			$this->salida .= "																	</select>\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">No. ORDEN</td>\n";
			$this->salida .= "																<td colspan=\"3\">\n";
			$this->salida .= "																	<input type=\"text\" class=\"input-text\" name=\"numIngreso\" maxlength=\"32\" value=\"".$this->request['numIngreso']."\" onkeypress=\"return acceptNum(event)\">\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td align='center' colspan=\"4\">\n";
			$this->salida .= "																	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "														</table>\n";
			$this->salida .= "													</form>\n";
			$this->salida .= "												</fieldset>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			
			if($this->request['buscador'] == '1')
			{
				if(sizeof($this->Rst['ordeness']) > 0)
				{
					$this->salida .= "									<br><table width=\"81%\" class=\"modulo_table_list\" align=\"center\">";
					$this->salida .= "										<tr align=\"center\" class=\"modulo_table_list_title\">\n";
					$this->salida .= "  										<td width=\"20%\">IDENTIFICACION</td>\n";
					$this->salida .= "											<td width=\"58%\">DATOS DEL PACIENTE</td>\n";
					$this->salida .= "											<td width=\"8%\" colspan=\"3\">ESTADOS</td>\n";
					$this->salida .= "											<td width=\"5%\" >OPCION</td>\n";
					$this->salida .= "										</tr>\n";
					
					$i = 0;
					foreach($this->Rst['ordeness'] as $key => $ordenes)
					{
						$est = 'modulo_list_oscuro'; $back = "#DDDDDD";
						if($i % 2 == 0)
						{
							$est = 'modulo_list_claro'; $back = "#CCCCCC";
						}
						$i++;
						
						$img1 = $img2 = $img3 = "checkN.gif";
						$title1 = "No hay ordenes para pagos";
						$title2 = "No hay ordenes para Cumplimiento";
						$title3 = "No Hay Solicitudes";
						
						if($ordenes['por_pago'] > 0)//1 son activas,para pagos //0
						{
							$img1="cargar.png";$title1="Existen ordenes para Pagar";
						}

						if($ordenes['por_cumplir'] > 0)//2 son pagas,para cumplimiento 0 ->cargos realizados en la atenci� no cargados a una cuenta quedando pendiente por cobrar
						{
							$img2="cargos.png";$title2="Existen ordenes para Cumplimiento";
						}

						if($ordenes['solicitudes'] > 0)//3 son pagas,para atencion
						{
							$img3="atencion_citas.png";$title3 = "Con Solicitudes";
						}
						
						$act = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',array('tipoid'=>$ordenes['tipo_id_paciente'],'idp'=>$ordenes['paciente_id'],'grupo'=>$this->grupo,'plan_id'=>$ordenes['plan_id']));
						
						$this->salida .= "										<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
						$this->salida .= "											<td class='normal_10AN'>".$ordenes['tipo_id_paciente']." - ".$ordenes['paciente_id']."</td>\n";
						$this->salida .= "											<td class='label_mark'>".trim($ordenes['nombre']." ".$ordenes['apellido'])."</td>\n";						
						$this->salida .= "											<td width=\"3%\" align=\"center\"><img title='$title1' src=\"". GetThemePath() ."/images/$img1\" border='0'></td>";
						$this->salida .= "											<td width=\"3%\" align=\"center\"><img title='$title2' src=\"". GetThemePath() ."/images/$img2\" border='0'></td>";
						$this->salida .= "											<td width=\"3%\" align=\"center\"><img title='$title3' src=\"". GetThemePath() ."/images/$img3\" border='0'></td>";
						$this->salida .= "											<td width=\"10%\" align=\"center\" class=\"label_error\">\n";
						$this->salida .= "												<a href=".$act.">VER</a>\n";
						$this->salida .= "											</td>\n";
						$this->salida .= "										</tr>\n";
					}
					$this->salida .= "										</table><br>\n";
					$Paginador = new ClaseHTML();
					$this->salida .= "								".$Paginador->ObtenerPaginado($this->conteo,$this->paginaA,$this->action[2]);
					$this->salida .= "								<br>\n";
				}
				else
				{
					$this->salida .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
				}
			}
			
			$this->salida .= "								</div>\n";
			
			$this->salida .= "								<div class=\"tab-page\" id=\"listado\" >\n";
			$this->salida .= "									<h2 id=\"listaOs\" class=\"tab\" >LISTADO DE ORDENES</h2>\n";
			$this->salida .= "									<script>\n";
			$this->salida .= "										tabPane.addTabPage( document.getElementById(\"listado\"));\n";
			$this->salida .= " 									</script>\n";
			$this->salida .= "									<table border=\"0\" width=\"81%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\" class=\"normal_11N\">LISTADO DE ORDENES DE SERVICIO</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td align=\"center\" >\n";
			$this->salida .= "												<a href=\"javascript:BuscarLista()\" class=\"label_error\">REFRESCAR</a>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";

			if($this->grupo == 1)
			{
				if(sizeof($this->Rst['listaos']) > 0)
				{
					$this->salida .= "									<br><table width=\"90%\" class=\"modulo_table_list\" align=\"center\">";
					$this->salida .= "										<tr align=\"center\" class=\"modulo_table_list_title\">\n";
					$this->salida .= "  										<td width=\"20%\">IDENTIFICACION</td>\n";
					$this->salida .= "											<td width=\"48%\">DATOS DEL PACIENTE</td>\n";
					$this->salida .= "											<td width=\"12%\">F. REGISTRO</td>\n";
					$this->salida .= "											<td width=\"9%\" colspan=\"3\">ESTADOS</td>\n";
					$this->salida .= "											<td width=\"%\" >OPCION</td>\n";
					$this->salida .= "										</tr>\n";
					
					$i = 0;
					foreach($this->Rst['listaos'] as $key => $ordenes)
					{
						$est = 'modulo_list_oscuro'; $back = "#DDDDDD";
						if($i % 2 == 0)
						{
							$est = 'modulo_list_claro'; $back = "#CCCCCC";
						}
						$i++;
						
						$img1 = $img2 = $img3 = "checkN.gif";
						$title1 = "No hay ordenes para pagos";
						$title2 = "No hay ordenes para Cumplimiento";
						$title3 = "No hay ordenes para Atencion";
						
						if($ordenes['por_pago'] > 0)//1 son activas,para pagos //0
						{
							$img1="cargar.png";$title1="Existen ordenes para Pagar";
						}

						if($ordenes['por_cumplir'] > 0)//2 son pagas,para cumplimiento 0 ->cargos realizados en la atenci� no cargados a una cuenta quedando pendiente por cobrar
						{
							$img2="cargos.png";$title2="Existen ordenes para Cumplimiento";
						}

						if($ordenes['por_atencion'] > 0)//3 son pagas,para atencion
						{
							$img3="atencion_citas.png";$title3="Existen ordenes para Atencion";
						}
						
						$act = ModuloGetURL('app','Os_CentralAtencion','user','FormaOrdenar',array('tipoid'=>$ordenes['tipo_id_paciente'],'idp'=>$ordenes['paciente_id'],'grupo'=>$this->grupo,'plan_id'=>$ordenes['plan_id']));
						
						$this->salida .= "										<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
						$this->salida .= "											<td class='normal_10AN'>".$ordenes['tipo_id_paciente']." - ".$ordenes['paciente_id']."</td>\n";
						$this->salida .= "											<td class='label_mark'>".trim($ordenes['nombre']." ".$ordenes['apellido'])."</td>\n";						
						$this->salida .= "											<td class='label' align='center'>".$ordenes['fecha_registro']."</td>\n";						
						$this->salida .= "											<td width=\"3%\" align=\"center\"><img title='$title1' src=\"". GetThemePath() ."/images/$img1\" border='0'></td>";
						$this->salida .= "											<td width=\"3%\" align=\"center\"><img title='$title2' src=\"". GetThemePath() ."/images/$img2\" border='0'></td>";
						$this->salida .= "											<td width=\"3%\" align=\"center\"><img title='$title3' src=\"". GetThemePath() ."/images/$img3\" border='0'></td>";
						$this->salida .= "											<td width=\"10%\" align=\"center\" class=\"label_error\">\n";
						$this->salida .= "												<a href=".$act.">VER</a>\n";
						$this->salida .= "											</td>\n";
						$this->salida .= "										</tr>\n";
					}
					$this->salida .= "										</table><br>\n";
					$Paginador = new ClaseHTML();
					$this->salida .= "								".$Paginador->ObtenerPaginado($this->conteo1,$this->paginaA1,$this->action[4]);
					$this->salida .= "								<br>\n";
				}
			}
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"osmanuales\">\n";
			$this->salida .= "									<h2 class=\"tab\">SOLICITUDES MANUALES</h2>\n";
			$this->salida .= "									<script>\n";
			$this->salida .= "										tabPane.addTabPage( document.getElementById(\"osmanuales\"));\n";
			$this->salida .= "									</script>\n";
			$this->salida .= "									<div id=\"errorM\" style=\"text-align:center\">".$this->Error."</div>\n";
			$this->salida .= "									<div class=\"tab-pane\" id=\"grupos_solicitudes\">\n";
			$this->salida .= "										<div class=\"tab-page\" id=\"ambulatorias\">\n";
			$this->salida .= "											<h2 class=\"tab\">AMBULATORIAS</h2>\n";
			$this->salida .= "             					<form name=\"formasolicitudmanual\" action=\"javascript:EvaluarDatosSolicitud(document.formasolicitudmanual)\" method=\"post\">";
			$this->salida .= "                				<table width=\"80%\" align=\"center\" border=\"0\"  class=\"modulo_table_list\">\n";
      $this->salida .= "                					<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "                						<td align=\"left\" style=\"text-indent:5pt\">PLAN:</td>\n";
			$this->salida .= "                						<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "                							<input type=\"hidden\" name=\"plan_id\" value=\"\">\n";
			$this->salida .= "															<select name=\"plan\" class=\"select\" onChange=\"AsignarValor(this.value)\">\n";
			$this->salida .= "                								<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($this->Rst['responsables'] as $key => $tipos)
			{
				if($tipos['sw_tipo_plan'] != "1")
				{
					($this->request['plan'] == $key)? $csk = "selected": $csk = ""; 
					$this->salida .= "                								<option value=\"".$key."~".$tipos['sw_tipo_plan']."\" $csk>".$tipos['plan_descripcion']."</option>";
				}
			}
			$this->salida .= "															</select>\n";
			$this->salida .= "                						</td>\n";
			$this->salida .= "                					</tr>\n";
			$this->salida .= "                					<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "                						<td align=\"left\" style=\"text-indent:5pt\">TIPO DOCUMENTO:</td>\n";
			$this->salida .= "														<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "															<select name=\"tipo_id_paciente\" class=\"select\">\n";
			$this->salida .= "                								<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($this->Rst['tiposid'] as $key => $tipos)
			{
				($this->request['tipodc'] == $key)? $csk = "selected": $csk = ""; 
				$this->salida .= "                								<option value=\"$key\" $csk >".$tipos['descripcion']."</option>";
			}	
			$this->salida .= "															</select>\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "													<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "														<td align=\"left\" style=\"text-indent:5pt\">DOCUMENTO:</td>\n";
			$this->salida .= "														<td align=\"left\" class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"paciente_id\" maxlength=\"32\" value=".$this->request['documentom']."></td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "													<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "														<td align='center' colspan=\"2\">\n";
			$this->salida .= "															<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "												</table>\n";
			$this->salida .= "											</form>\n";
			$this->salida .= "										</div>\n";
			$this->salida .= "										<div class=\"tab-page\" id=\"ambulatorias\">\n";
			$this->salida .= "											<h2 class=\"tab\">HOSPITALARIAS</h2>\n";
			$this->salida .= "											<form name=\"formahospitalaria\" action=\"javascript:BuscarPaciente(document.formahospitalaria)\" method=\"post\" >\n";
			$this->salida .= "												<table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "													<tr class=\"modulo_table_list_title\">\n";
			
			$this->salida .= "														<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">N INGRESO</td>\n";
			$this->salida .= "														<td width=\"30%\" style=\"text-align:left\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "															<input class=\"input-text\" type=\"text\" name=\"ingreso\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "														<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">N CUENTA</td>\n";
			$this->salida .= "														<td width=\"30%\" style=\"text-align:left\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "															<input class=\"input-text\" type=\"text\" name=\"cuenta\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "													<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "														<td style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "														<td width=\"30%\" align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "															<select name=\"tipodocumento\" class=\"select\">\n";
			$this->salida .= "																<option value=\"0\">---Seleccionar---</option>\n";
			$sel="";
			foreach($this->Rst['tiposid'] as $value => $dat)
				$this->salida .= "																<option value=\"".$dat['tipo_id_paciente']."\" $sel>".$dat['descripcion']."</option>\n";
			
			$this->salida .= "															</select>\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "														<td style=\"text-align:left;text-indent:8pt\" >DOCUMENTO: </td>\n";
			$this->salida .= "														<td align=\"left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "															<input type=\"text\" class=\"input-text\" name=\"documento\" value=\"".$datos['documento']."\" style=\"width:50%\" >\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "													<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "														<td style=\"text-indent:8pt;text-align:left\">NOMBRES</td>\n";
			$this->salida .= "														<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "															<input class=\"input-text\" type=\"text\" name=\"nombres\" style=\"width:100%\" value=\"".$datos['nombres']."\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "														<td style=\"text-indent:8pt;text-align:left\">APELLIDOS</td>\n";
			$this->salida .= "														<td style=\"text-align:left\" class=\"modulo_list_claro\">\n";
			$this->salida .= "															<input class=\"input-text\" type=\"text\" name=\"apellidos\" style=\"width:100%\" value=\"".$datos['apellidos']."\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "												</table>\n";
			$this->salida .= "												<table width=\"50%\" align=\"center\" height=\"25\">\n";
			$this->salida .= "													<tr>\n";
			$this->salida .= "														<td align=\"center\" >\n";
			$this->salida .= "															<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "														<td align=\"center\" >\n";
			$this->salida .= "															<input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.formahospitalaria)\">\n";
			$this->salida .= "														</td>\n";
			$this->salida .= "													</tr>\n";
			$this->salida .= "												</table>\n";
			$this->salida .= "											</form>\n";
			$this->salida .= "											<div id=\"busquedahospitalaria\"></div>\n";
			$this->salida .= "										</div>\n";
			$this->salida .= "									</div>\n";
			$this->salida .= "								</div>\n";
			$this->salida .= "								<div class=\"tab-page\" id=\"citas\">\n";
			$this->salida .= "									<h2 class=\"tab\">ATENCION CITAS</h2>\n";
			$this->salida .= "									<script>	tabPane.addTabPage( document.getElementById(\"citas\")); </script>\n";
			$this->salida .= "									<table border=\"0\" width=\"81%\" align=\"center\">\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td id=\"error\" align=\"center\"></td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "										<tr>\n";
			$this->salida .= "											<td>\n";
			$this->salida .= "												<fieldset class=\"fieldset\"><legend>BUSCADOR CITAS</legend>\n";
			$this->salida .= "													<form name=\"formacitas\" action=\"".$this->action[5]."\" method=\"post\">";
			$this->salida .= "														<table width=\"100%\" align=\"center\" border=\"0\">";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\" width=\"20%\">TIPO DOCUMENTO: </td>\n";
			$this->salida .= "																<td colspan=\"3\">\n";
			$this->salida .= "																	<select name=\"citas[tipo_documento_id]\" class=\"select\">\n";
			$this->salida .= "                										<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($this->Rst['tiposid'] as $key => $tipos)
			{
				($this->request['citas']['tipo_documento_id'] == $key)? $csk = "selected": $csk = ""; 
				$this->salida .= "                										<option value=\"$key\" $csk >".$tipos['descripcion']."</option>";
			}
			$this->salida .= "																	</select>\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">DOCUMENTO:</td>\n";
			$this->salida .= "																<td colspan=\"3\"><input type=\"text\" class=\"input-text\" name=\"citas[documento_id]\" maxlength=\"32\" value=".$this->request['citas']['documento_id']."></td>\n";
			
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">PRIMER NOMBRE:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"citas[nombres1]\" maxlength=\"32\" value=".$this->request['citas']['nombres1']."></td>\n";
			$this->salida .= "																<td class=\"normal_10AN\">SEGUNDO NOMBRE:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"citas[nombres2]\" maxlength=\"32\" value=".$this->request['citas']['nombres2']."></td>\n";			
			$this->salida .= "															</tr>\n";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td class=\"normal_10AN\">PRMIER APELLIDO:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"citas[apellidos1]\" maxlength=\"32\" value=".$this->request['citas']['apellidos1']."></td>\n";
			$this->salida .= "																<td class=\"normal_10AN\">SEGUNDO APELLIDO:</td>\n";
			$this->salida .= "																<td><input type=\"text\" class=\"input-text\" name=\"citas[apellidos2]\" maxlength=\"32\" value=".$this->request['citas']['apellidos2']."></td>\n";
			$this->salida .= "															</tr>";
			$this->salida .= "															<tr>\n";
			$this->salida .= "																<td align='center' colspan=\"4\">\n";
			$this->salida .= "																	<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
			$this->salida .= "																</td>\n";
			$this->salida .= "															</tr>\n";
			$this->salida .= "														</table>\n";
			$this->salida .= "													</form>\n";
			$this->salida .= "												</fieldset>\n";
			$this->salida .= "											</td>\n";
			$this->salida .= "										</tr>\n";
			$this->salida .= "									</table>\n";
			if($this->request['grupo'] == '3')				
				$this->salida .= $aosh->FormaCitas($this->request['citas'],$this->request['offset'],$this->action,$this->Datos['departamento']);
			
			$this->salida .= "								</div>\n";
			$this->salida .= "							</div>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "<form name=\"volver\" action=\"".$this->action[1]."\" method=\"post\">";
			$this->salida .= "	<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "<script type=\"text/javascript\">\n";
			$this->salida .= "	setupAllTabs();\n";
			$this->salida .= "	var html1 = document.getElementById('listaOs').innerHTML;\n";
			$this->salida .= "	html1 = html1.replace(\"#\",\"javascript:BuscarLista()\");\n";
			$this->salida .= "	document.getElementById('listaOs').innerHTML = html1;\n";
			$this->salida .= "	tabPane.setSelectedIndex(".$this->grupo.");";
			$this->salida .= "</script>\n";
			$this->salida .= ThemeCerrarTabla(); 
			return true;
    }
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaOrdenar()
    {
			$this->Ordenar();
			$aosh = new AtencionOsHtml();
			
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->IncludeJS("CrossBrowser");
			$this->salida .= ThemeAbrirTabla('CUMPLIMIENTO DE ORDENES DE SERVICIO');
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function Iniciar()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,600, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/5, 100);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,580, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 580, 0);\n";
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
			$this->salida .= "	function MarcarTodos(frm,nombre,flag)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		chks = document.getElementsByName(nombre);\n";
			$this->salida .= "		for(i=0; i<chks.length; i++)\n";
			$this->salida .= "			chks[i].checked = flag;\n";
			$this->salida .= "	}\n";

			$this->salida .= "	function MostrarLiquidacion(nombre,url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var j = 0; \n";
			$this->salida .= "		var flag = false; \n";
			$this->salida .= "		var datos = ''; \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			chks = document.getElementsByName(nombre);\n";
			$this->salida .= "			for(i=0; i<chks.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(chks[i].checked == true)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					url += '&cargos['+(j++)+']='+chks[i].value;\n";
			$this->salida .= "					flag = true;\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}catch(error){alert(error);}\n";
			$this->salida .= "		if(flag)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.open(url,'cargar','toolbar=no,width=700,height=400,resizable=no,scrollbars=yes').focus();\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<form name=\"actualizar\" action=\"".$this->action[6]."\" method=\"post\"></form>\n";
			$this->salida .= "<form name=\"recarga\" action=\"".$this->action[4]."\" method=\"post\"></form>\n";
			$this->salida .= $aosh->Encabezado($this->Datos)."<br>";
			
			$this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= " 		<td style=\"text-indent:8pt;text-align:left;\" width=\"25%\">NOMBRE</td>\n";
			$this->salida .= " 		<td colspan=\"3\" style=\"text-align:left\" class=\"modulo_list_claro\" >".$this->Paciente['nombre']." ".$this->Paciente['apellido']."</td>\n";
			$this->salida .= " 		<td colspan=\"2\" class=\"modulo_list_claro style=\"text-align:left\" width=\"30%\">\n";
			if($this->Paciente['estado'] == '1')
				$this->salida .= " 			<a href=\"javascript:Iniciar();MostrarSpan('Contenedor')\" class=\"label_error\">DATOS INGRESO</a>\n";
			else
				$this->salida .= " 			&nbsp;\n";
				
			$this->salida .= " 		</td>\n";

			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= " 		<td style=\"text-indent:8pt;text-align:left\">IDENTIFICACI�</td>\n";
			$this->salida .= " 		<td class=\"modulo_list_claro\" colspan=\"5\" style=\"text-align:left\" >".$this->Paciente['tipo_id_paciente']." ".$this->Paciente['paciente_id']."</td>\n";
			$this->salida .= "	</tr>\n";
			if($this->Ingreso['cuentaestado'] == '1')
			{
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td width=\"20%\" style=\"text-indent:8pt;text-align:left\">\n";
				$this->salida .= "			RESPONSABLE\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$this->Ingreso['nombre_tercero']."</td >\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td style=\"text-indent:8pt;text-align:left\">PLAN</td>\n";
				$this->salida .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$this->Ingreso['plan_descripcion']."</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td style=\"text-indent:8pt;text-align:left\">N CUENTA</td>\n";
				$this->salida .= "		<td align=\"left\" colspan=\"5\" class=\"modulo_list_claro\">".$this->Ingreso['numerodecuenta']."</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td style=\"text-indent:8pt;text-align:left;font-weight:bold\" >TIPO AFILIADO</td>\n";
				$this->salida .= "		<td style=\"text-align:left\" width=\"20%\" class=\"modulo_list_claro\">".$this->Ingreso['tipo_afiliado_nombre']."</td>\n";
				$this->salida .= "		<td style=\"text-indent:8pt;text-align:left\" >RANGO</td>\n";
				$this->salida .= "		<td style=\"text-align:left\" width=\"10%\" class=\"modulo_list_claro\">".$this->Ingreso['rango']."</td>\n";
				$this->salida .= "		<td style=\"text-indent:8pt;text-align:left\">SEMANAS COTIZADAS</td>\n";
				$this->salida .= "		<td style=\"text-align:left\" width=\"10%\" class=\"modulo_list_claro\">".$this->Ingreso['semanas_cotizadas']."</td>\n";
				$this->salida .= "	</tr>\n";
			}
			$this->salida .= "</table><br>\n";
			$this->salida .= "<table align=\"center\" width=\"80%\">\n";
			$this->salida .= "	<tr>\n";
			//$this->salida .= "		<td width=\"25%\" align=\"center\"><a href=\"\" class=\"label\">ORDENES VENCIDAS AMBULATORIAS</a>\n";
			//$this->salida .= "		<td width=\"25%\" align=\"center\"><a href=\"\" class=\"label\">ORDENES VENCIDAS HOSPITALIZACI�</a>\n";
			if($this->Contador['contador'] > 0)
				$this->salida .= "		<td width=\"25%\" align=\"center\"><a href=\"".$this->action[2]."\" class=\"label\">SOLICITUDES POR AUTORIZAR</a>\n";
		
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			
			if(!empty($this->PorOrden))
			{
				$this->salida .= "<table align=\"center\" width=\"50%\">\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "			<fieldset><legend class=\"normal_10AN\">SOLICITUDES POR ORDENAR</legend>\n";
				$this->salida .= "				<table width=\"100%\" class=\"modulo_table_list\">\n";
				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td width=\"35%\" class=\"modulo_table_list_title\">N DE AUTORIZACION:</td>\n";
				$this->salida .= "						<td class=\"modulo_list_claro\">\n";
				foreach($this->PorOrden as $key => $auto)
				{
					$this->salida .= "							<a href=\"".$this->action[3]."&numero_autorizacion=".$auto['autorizacion']."\" class=\"label_error\">".$auto['autorizacion']."</a>\n";
				}
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "				</table>\n";
				$this->salida .= "			</fieldset>\n";
				$this->salida .= "		<td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			if($this->permiso == 1)
			{
				if($this->CuentasActivas)
				{
					$this->salida .= "<center class=\"label_error\">\n";
					$this->salida .= "	<label class=\"normal_10AN\">\n";
					$this->salida .= "		CUENTA ASOCIADA: \n";
					$this->salida .= "	</label>\n";
					foreach($this->CuentasActivas as $keyA => $vlr)
						$this->salida .= "	<a href=\"".$this->action[4].UrlRequest(array("numerodecuenta"=>$keyA))."\">".$keyA."</a>\n";
			
					$this->salida .= "</center><br>\n";
				}
			}
			
			if(!empty($this->Ordenes))
			{
				$l = 0;
				foreach($this->Ordenes as $keyP => $planesA)
				{
					$i =0;
					$this->salida .= "			<fieldset style=\"width:98%\" class=\"fieldset\"><legend class=\"normal_10AN\">ORDENES DE SERVICIO - PLAN: ".$keyP." </legend>\n";
					foreach($planesA as $keySw => $sw_interna)
					{
						foreach($sw_interna as $key => $ordeninterna)
						{	
							$ttl = "EXTERNA";
							$mdl = "modulo_table_title";
							
							if($keySw == '1') 
							{	
								$ttl = "INTERNA";
								$mdl = "formulacion_table_list";
							} 
							$this->salida .= "			<form name=\"orden".$ordeninterna['orden_servicio_id']."\" action=\"".$this->action[1]."\" method=\"post\">\n";
							$this->salida .= "				<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
							$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "						<td rowspan=\"2\" style=\"font-size:12px;text-indent:0pt;text-align:center\" class=\"".$mdl." \" width=\"15%\">O.S. ".$ttl."</td>\n";
							$this->salida .= "						<td style=\"text-indent:8pt;text-align:left\" width=\"18%\">N ORDEN:</td>\n";
							$this->salida .= "						<td class=\"modulo_list_claro\" align=\"left\" >".$ordeninterna['orden_servicio_id']."</td>\n";
							$this->salida .= "						<td style=\"text-indent:8pt;text-align:left\" width=\"18%\">SERVICIO:</td>\n";
							$this->salida .= "						<td class=\"modulo_list_claro\" align=\"left\" colspan=\"3\">".$ordeninterna['servicio']."</td>\n";
							$this->salida .= "					</tr>\n";
							$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "						<td style=\"text-indent:8pt;text-align:left\">FECHA ACTIVACION:</td>\n";
							$this->salida .= "						<td class=\"modulo_list_claro\">".$ordeninterna['activacion']."</td>\n";
							$this->salida .= "						<td style=\"text-indent:8pt;text-align:left\">FECHA VENCIMIENTO:</td>\n";
							$this->salida .= "						<td class=\"modulo_list_claro\">".$ordeninterna['vencimiento']."</td>\n";
							$this->salida .= "						<td style=\"text-indent:8pt;text-align:left\">REFRENDAR:</td>\n";
							$this->salida .= "						<td class=\"modulo_list_claro\">".$ordeninterna['refrendar']."</td>\n";
							$this->salida .= "					</tr>\n";
							
							if($ordeninterna['fecha_turno']) {
							$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "						<td>FECHA CITA:</td>\n";
							$this->salida .= "						<td colspan=\"6\" class=\"modulo_list_claro\">".$ordeninterna['fecha_turno']." ".$ordeninterna['hora']."</td>\n";
							$this->salida .= "					</tr>\n";
							}
							
							if($ordeninterna['observacion'])
							{
								$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
								$this->salida .= "						<td>OBSERVACION:</td>\n";
								$this->salida .= "						<td colspan=\"6\" class=\"modulo_list_claro\">".$ordeninterna['observacion']."</td>\n";
								$this->salida .= "					</tr>\n";					
							}
							$facturar = "<input type=\"checkbox\" name=\"factura\" onClick=\"MarcarTodos(document.orden".$ordeninterna['orden_servicio_id'].",'facturar".$ordeninterna['orden_servicio_id']."',this.checked)\">\n";
							$cumplir  = "<input type=\"checkbox\" name=\"cumplir\" onClick=\"MarcarTodos(document.orden".$ordeninterna['orden_servicio_id'].",'cumplir".$ordeninterna['orden_servicio_id']."',this.checked)\">\n";
							
							$this->salida .= "					<tr>\n";
							$this->salida .= "						<td colspan=\"7\">\n";
							$this->salida .= "							<table width=\"100%\" class=\"modulo_table_list\">\n";
							$this->salida .= "								<tr class=\"modulo_table_list_title\">\n";
							$this->salida .= "									<td valign=\"top\" width=\"7%\">CUPS</td>\n";
							$this->salida .= "									<td valign=\"top\" width=\"14%\" colspan=\"2\">TARIFARIO - CARGO</td>\n";
							$this->salida .= "									<td valign=\"top\">DESCRIPCION</td>\n";
							$this->salida .= "									<td width=\"4%\">FACT<br>".$facturar."</td>\n";
							$this->salida .= "									<td width=\"4%\">CUMP<br>".$cumplir."</td>\n";
							$this->salida .= "								</tr>\n";
							$f = $c = $i = 0;
							//echo '<pre>';print_r($this->Cargos);
							////aqui
							foreach($this->Cargos[$ordeninterna['plan_id']][$key] as $keyC => $cups)
							{
								$dcargo = "";
								foreach($cups as $keyCC => $cargos)
								{
									if(!$cargos['transaccion'])
									{
										$facturar = "<input type=\"checkbox\" name=\"facturar".$ordeninterna['orden_servicio_id']."\" value=\"".$cargos['os_maestro_cargos_id']."\">\n";
										$cumplir  = "";
										$f++;
									}
									else
									{
										$facturar = ""; 
										$cumplir  = "<input type=\"checkbox\" name=\"cumplir".$ordeninterna['orden_servicio_id']."\"  value=\"".$cargos['os_maestro_cargos_id']."\">\n";
										$c++;
									}
									
									$this->salida .= "									<tr class=\"modulo_list_claro\">\n";
									if($dcargo != $cargos['descripcion_cups'])
									{
										$this->salida .= "										<td align=\"center\" rowspan=\"".sizeof($cups)."\" title=\"".$cargos['descripcion_cups']."\">$keyC</td>\n";
										$dcargo = $cargos['descripcion_cups'];
									}
									
									$this->salida .= "										<td width=\"7%\" align=\"center\">\n";
									$this->salida .= "											<input type=\"hidden\" name=\"cargos\" value=\"".$cargos['cargo']."\">\n";
									$this->salida .= "											<input type=\"hidden\" name=\"tarifarios\" value=\"".$cargos['tarifario_id']."\">\n";
									$this->salida .= "											".$cargos['tarifario_id']."\n";
									$this->salida .= "										</td>\n";
									$this->salida .= "										<td width=\"7%\" align=\"center\">".$cargos['cargo']."</td>\n";
									$this->salida .= "										<td style=\"text-align:justify\">".$cargos['descripcion_cargo']."</td>\n";

									$this->salida .= "										<td align=\"center\">$facturar</td>\n";
									$this->salida .= "										<td align=\"center\">$cumplir</td>\n";
									$this->salida .= "									</tr>\n";
								}
								$i++;
							}
							$this->salida .= "									<tr class=\"modulo_list_oscuro\">\n";
							$this->salida .= "										<td colspan=\"4\"></td>\n";
							$this->salida .= "										<td class=\"modulo_list_claro\" align=\"center\">\n";
							if($f > 0)
							{
								$url = $this->action[1].UrlRequest(array("plan_id"=>$ordeninterna['plan_id'],"orden_id"=>$ordeninterna['orden_servicio_id'],"autorizacion"=>$ordeninterna['autorizacion_int']));
								if($cargos['vencimiento'] == date("d/m/Y") || $cargos['vencimiento'] == "")
								{
									$this->salida .= "<a href=\"".$url."\"  target=\"cargar\" onclick=\"MostrarLiquidacion('facturar".$ordeninterna['orden_servicio_id']."','".$url."'); return false;\" title=\"FACTURAR\">\n";
									//$m = "FECHA DE VENCIMIENTO".$cargos['vencimiento'];
								}
								else{$m = "FECHA DE VENCIMIENTO ".$cargos['vencimiento'];}	
								$this->salida .= "<img src=\"". GetThemePath() ."/images/cargar.png\" border='0' title='".$m."'>\n";
								$this->salida .= "</a>\n";
							}
							$this->salida .= "										</td>\n";
							$this->salida .= "										<td class=\"modulo_list_claro\"  align=\"center\">\n";
							if($c > 0)
							{
								$url = $this->action[5].UrlRequest(array("plan_id"=>$ordeninterna['plan_id'],"orden_id"=>$ordeninterna['orden_servicio_id']));
								$this->salida .= "											<a href=\"".$url."\" target=\"cargar\" onclick=\"MostrarLiquidacion('cumplir".$ordeninterna['orden_servicio_id']."','".$url."'); return false;\" title='CUMPLIR ORDENES'>\n";
								$this->salida .= "												<img src=\"". GetThemePath() ."/images/cargos.png\" border='0'>\n";
								$this->salida .= "											</a>\n";
							}
							$this->salida .= "										</td>\n";
							$this->salida .= "									</tr>\n";
							$this->salida .= "								</table>\n";

							$this->salida .= "							</td>\n";
							$this->salida .= "						</tr>\n";					
							$this->salida .= "					</table>\n";
							$this->salida .= "				</form>\n";						
						}
					}
					//$this->salida .= "					<center>\n";
					//$this->salida .= "						<a href=\"".$this->action[2].URLRequest(array("plan_id"=>$ordeninterna['plan_id']))."\" class=\"label_error\">CREAR SOLICITUD MANUAL PARA ESTE PLAN</a>\n";
					//$this->salida .= "					</center>\n";
					$this->salida .= "				<br>\n";
					$this->salida .= "				</fieldset>\n";
				}
			}

			$this->salida .= "<form name=\"volver\" action=\"".$this->action[0]."\" method=\"post\">";
			$this->salida .= "	<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			
			$this->salida .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:3\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\">datos ingreso</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br>\n";
			$this->salida .= "	<div id='Contenido' class='d2Content'>\n";
			$this->salida .= "		<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td><br>\n";
			$this->salida .= "					<table width='95%' align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td width=\"30%\" style=\"text-indent:8pt;text-align:left\">PACIENTE</td>\n";
			$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">".strtoupper($this->Ingreso['nombre'])." ".strtoupper($this->Ingreso['apellido'])."</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td style=\"text-indent:8pt;text-align:left\">IDENTIFICACION</td>\n";
			$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">".$this->request['tipoid']." ".$this->request['idp']."</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td style=\"text-indent:8pt;text-align:left\">SEXO</td>\n";
			$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">".$this->Ingreso['genero']."</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td style=\"text-indent:8pt;text-align:left\">FECHA NACIMIENTO</td>\n";
			$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">".$this->Ingreso['fecha_nacimiento']."</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td style=\"text-indent:8pt;text-align:left\">DIRECCION RESIDENCIA</td>\n";
			$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">".$this->Ingreso['residencia_direccion'].". ".$this->Ingreso['municipio']." - ".$this->Ingreso['departamento']." - ".$this->Ingreso['pais']."</td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td style=\"text-indent:8pt;text-align:left\" >TELEFONO RESIDENCIA</td>\n";
			$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">".$this->Ingreso['residencia_telefono']."</td>\n";
			$this->salida .= "						</tr>\n";
			
			if(sizeof($this->Ingreso['ubicacion']) > 0)
			{
				$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<td style=\"text-indent:8pt;text-align:left\" >UBICACI�</td>\n";
				$this->salida .= "							<td align=\"left\" class=\"modulo_list_claro\">";
				
				switch($this->Ingreso['ubicacion']['tabla'])
				{
					case 'URG':
						$this->salida .= "EL PACIENTE SE ENCUENTRA EN CONSULTA DE URGENCIAS, EN LA ESTACI�: ".$this->Ingreso['ubicacion']['estacion']." ";
					break;
					case 'EEF':
						$this->salida .= "EL PACIENTE POSEE UN INGRESO PENDIENTE, EN LA ESTACI�: ".$this->Ingreso['ubicacion']['estacion']." ";
					break;
					case 'MVH':
						$this->salida .= "EL PACIENTE SE ENCUENTRA HOSPITALIZADO EN LA HABITACION ".$this->Ingreso['ubicacion']['pieza'].", CAMA ".$this->Ingreso['ubicacion']['cama']."DE URGENCIAS, LA ESTACI�: ".$this->Ingreso['ubicacion']['estacion']." ";
					break;
				}
				$this->salida .= "							</td>\n";			
				$this->salida .= "						</tr>\n";
			}
			$this->salida .= "					</table>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";			
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "					<a href=\"javascript:OcultarSpan('Contenedor')\" class=\"label_error\">CERRAR</a>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaLiquidarCargos()
    {
			IncludeClass("LiquidacionCargos");
			$rst = $this->LiquidarCargos();
			
			if($rst === false)
			{
				$this->CrearNuevaAutorizacion($this->request);
				$Autoriza = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
				$Autoriza->SetActionVolver($this->action['cancelar']);
				$Autoriza->SetActionAceptar($this->action['aceptar']);
				if(!$Autoriza->SetClaseAutorizacion('AD'))
				{
					$this->FormaMensajeError('ERROR','center',$this->action['cancelar']);
					return true;
				}
				//print_r($this->request);
				$Autoriza->FormaValidarAutoAdmisionHospitalizacion($this->request);
				$this->salida = $Autoriza->salida;
				return true;
			}
				
			$this->SetXajax(array("CrearCuentaAmbulatoria"),"app_modules/Os_CentralAtencion/RemoteXajax/CuentaAmbulatoria.php");
			$this->salida .= ThemeAbrirTabla('ORDENES DE SERVICIO');
			$this->salida .= "<script>\n";
			$this->salida .= "	function RecargarPagina()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.opener.document.recarga.submit();\n";
			$this->salida .= "		window.close()\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CrearCuenta(cuenta)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xajax_CrearCuentaAmbulatoria(".$this->request['orden_id'].",'".$this->datos['departamento']."','".$this->request['plan_id']."',cuenta,'".$this->request['autorizacion']."');\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<center>\n";
			$this->salida .= "	<div id=\"error\" class=\"label_error\" style=\"text-align:center;width:90%\"></div>\n";
			
			$class= "";
			
			/*echo '<pre>';print_r($this->cuenta);
			echo '<pre>';print_r($this->request);
			echo '<pre>';print_r($this->datos);*/
			if(empty($this->cuenta))
			$class = "class=\"modulo_table_list\"";
			
			$this->salida .= "	<div  $class style=\"text-align:justify;width:90%;padding:4px\">\n";
			$this->salida .= "		<label class=\"normal_10AN\">".$this->frmError['MensajeError']."</label>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</center>\n";
			
			if(!empty($this->cuenta))
			{
				$this->salida .= "<br>\n";
				$this->salida .= "<table align=\"center\" width=\"90%\" class=\"modulo_table_list\">\n";			
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td>N CUENTA</td>\n";
				$this->salida .= "		<td>PLAN</td>\n";
				$this->salida .= "		<td>ESTADO</td>\n";
				$this->salida .= "		<td>CANTIDAD ORDENES</td>\n";
				$this->salida .= "		<td>OPCION</td>\n";
				$this->salida .= "	</tr>\n";
				
				foreach($this->cuenta as $key => $cuenta)
				{
					$Plan = explode(",", $this->NombrePlanCuenta($cuenta['numerodecuenta']));
					
					$this->salida .= "	<tr class=\"modulo_list_claro\">\n";
					$this->salida .= "		<td align=\"center\" class=\"label\">".$cuenta['numerodecuenta']."</td>\n";
					$this->salida .= "		<td align=\"center\" class=\"normal_10AN\">".$Plan[0]."</td>\n";
					$this->salida .= "		<td align=\"center\" class=\"normal_10AN\">".$cuenta['estado']."</td>\n";
					$this->salida .= "		<td align=\"center\" class=\"normal_10AN\">".$this->CantidadOrdenesCuentaDpto($cuenta['numerodecuenta'],$this->datos['departamento'],$Plan[1])."</td>\n";
					$this->salida .= "		<td align=\"center\">\n";
					$this->salida .= "			<a href=\"javascript:CrearCuenta('".$cuenta['numerodecuenta']."')\" class=\"label_error\">\n";
					$this->salida .= "				<img title='CARGAR A ESTA CUENTA' src=\"". GetThemePath() ."/images/cargar.png\" border='0'>CARGAR A ESTA CUENTA\n";
					$this->salida .= "			</a>\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";
				}
				$this->salida .= "</table>\n";
			}
			
			$this->salida .= "<br>\n";
			$this->salida .= "<table align=\"center\" width=\"50%\">\n";			
			$this->salida .= "	<tr>\n";
			$ttl = "Crear Cuenta";
			if(!empty($this->cuenta))
				$ttl = "Crear Nueva Cuenta";
				
			$this->salida .= "		<td align=\"center\">\n";
			$this->salida .= "			<input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"".$ttl."\" onclick=\"CrearCuenta()\">\n";
			$this->salida .= "		</td>\n";
			
			$this->salida .= "		<td align=\"center\">\n";
			$this->salida .= "			<input class=\"input-submit\" type=\"button\" name=\"cancelar\" value=\"Cerrar\" onclick=\"window.close()\">\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";			
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaSolicitudes()
		{
			$this->Solicitudes();
			$aosh = new AtencionOsHtml();
			$this->salida .= ThemeAbrirTabla('CUMPLIMIENTO DE ORDENES DE SERVICIO');
			$this->salida .= $aosh->FormaCargosAutorizar($this,$this->action,$this->Datos,$this->request);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaCrearOrden()
		{
			$rst = $this->CrearOrden();
			if(!$rst)
				$this->FormaMensajeError('MENSAJE','center',$this->action['aceptar']);
			else
				$this->FormaMensajeError('MENSAJE','center',$this->action['aceptar']);
			return true;
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaAutorizarCargos()
    {
			IncludeClass('AutorizacionesHTML','','app','NCAutorizaciones');
			
			$this->AutorizarCargos($flag);
			$aosh = new AtencionOsHtml();
			$auhtml = new AutorizacionesHTML();
			
			$Autoriza = $this->ReturnModuloExterno('app','NCAutorizaciones','user');
			
			$Autoriza->SetActionVolver($this->action['cancelar']);
			$Autoriza->SetActionAceptar($this->action['aceptar']);
			if(!$Autoriza->SetClaseAutorizacion('OS'))
			{
				$this->FormaMensaje($Autoriza->frmError['mensajeError'],'AUTORIZACIONES');	
				return true;
			}
			$Autoriza->ValidarAdmisionHospitalizacion($this->request,$this->request['cargos']);
			
			if($Autoriza->automatico == true)
				$Autoriza->FormaMensajeError('MENSAJE','center',$Autoriza->action['aceptar']);
			else
				$Autoriza->FormaMostrarDatosIngreso($Autoriza->datos,$this->request['cargos']);
			
			$this->salida = $Autoriza->salida;
			
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaCargosOrdenesServicio()
		{
			$this->CargosOrdenesServicio();
			$aosh = new AtencionOsHtml();
			
			$this->salida .= ThemeAbrirTabla('CARGOS ORDENES DE SERVICIO');
			$this->salida .= $aosh->FormaCargosAutorizados($this,$this->action,$this->Datos,$this->auto,$this->cargos,$_REQUEST[numero_orden_id]);
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************************
		* @access public
		*************************************************************************************/
		function FormaMensajeError($titulo,$align,$action1,$action2 = null)
		{
			$this->salida .= ThemeAbrirTabla($titulo);
			$this->salida .= "	<script>\n";
			$this->salida .= "		function CerrarVentana(num_ingreso)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			window.opener.document.formabuscar.ingreso.value = num_ingreso;\n";
			$this->salida .= "			window.opener.document.formabuscar.submit();\n";
			$this->salida .= "			window.close();\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$action1."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" align=\"".$align."\" colspan=\"3\"><br>";
			$this->salida .= "				".$this->frmError['MensajeError']."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form><br>\n";
			
			if($action2)
			{
				$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "			<td align=\"center\">\n";
				$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
				$this->salida .= "			</td></form>\n";
				
			}
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaDatosPaciente()
		{
			SessionDelVar("CargosAdicionados");
			$this->DatosPaciente();
			$pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
			
			$pct->SetActionVolver($this->action['volver']);
			$pct->FormaDatosPaciente($this->action);
			//print_r($this->action);
			$this->SetJavaScripts("Ocupaciones");
			$this->salida = $pct->salida;
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaIngresarCargos()
		{
			$this->IngresarCargos();
			IncludeClass('ClaseHTML');
			IncludeClass('SolicitudManualHTML','','app','Os_CentralAtencion');
			$slm = new SolicitudManualHTML();

			$this->SetXajax(array("BuscarCargos","AdicionarCargo","AdicionarEquivalencia","EliminarCargo"),"app_modules/Os_CentralAtencion/RemoteXajax/Solicitud.php");
			$this->salida .= $slm->FormaDatosSolicitud($this->request,$this->action,$this->depart);
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaCumplirOrdenes()
		{
			$rst = $this->CumplirOrdenes();
			IncludeClass('CumplimientoHtml','','app','Os_CentralAtencion');
			$cphtml = new CumplimientoHtml();
			
			if($rst === false)
			{
				$this->salida = $cphtml->FormaMensaje("MENSAJE ERROR","center",$this->action,$this->frmError['MensajeError']);
			}
			else
			{
				if(empty($this->cargos))
					$this->salida = $cphtml->FormaMensaje("MENSAJE","center",$this->action,"SE HA HECHO EL CUMPLIMIENTO DE LAS ORDENES DE MANERA CORRECTA");
				else
					$this->salida .= $cphtml->CumplirOrdenes($this->Datos['departamento'],$this->request,$this->action,$this->cumplidos);
			}
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaDatosCuenta()
		{
			$this->DatosCuenta();
			
			$cnt = $this->ReturnModuloExterno('app','Cuentas','user');
			
			$cnt->SetActionVolver($this->action['volver']);
			$cnt->FormaMostrarCuenta(&$this,$this->numerodecuenta);
			
			$this->salida = $cnt->salida;
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function FormaIngresarCumplimiento()
		{
			$rst = $this->IngresarCumplimiento();
			IncludeClass('CumplimientoHtml','','app','Os_CentralAtencion');
			$cphtml = new CumplimientoHtml($this->ASIGNADAS,$this->CNT_CITA_ASIGNAR,$this->CITAS_ASIGNADAS);
			
			$titulo = "MENSAJE";
			if($rst === false) $titulo = "MENSAJE ERROR ";
			
			$this->salida = $cphtml->FormaMensaje($titulo,"center",$this->action,$this->frmError['MensajeError']);
			
			return true;
		}
		/*************************************************************************
		*
		**************************************************************************/
		function EvaluarRequest()
		{
			switch($_REQUEST['opcion'])
			{
				case '2':	
					$this->BuscarListaOs();
					$this->FormaMenuAtencion();
				break;
				default:	$this->main();	break;
			}
			return true;
		}
		/********************************************************************************** 
		* Funci� principal del m�ulo 
		* 
		* @return boolean
		***********************************************************************************/
		function FormaCrearSolicitud()
    {
			$rst = $this->CrearSolicitud();
			if($rst === false)
			{
				$this->FormaMensajeError('ERROR','center',$this->action['cancelar']);
			}
			else
			{
				$this->salida .= "<script>\n";
				$this->salida .= "	location.href = \"".$this->action['aceptar']."\"\n";
				$this->salida .= "</script>\n";
			}
			
			return true;
		}
	}
?>