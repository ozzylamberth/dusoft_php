<?php
/********************************************************************************* 
 	* $Id: hc_InscripcionPlanFamiliar_InscripcionPF_HTML.class.php,v 1.2 2006/12/14 14:49:13 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_InscripcionPlanFamiliar_InscripcionPF_HTML
	* 
 	**********************************************************************************/
	
	class InscripcionPF_HTML
	{

		function InscripcionPF_HTML()
		{
			return true;
		}
		
		function frmHistoria()
		{
			$this->salida="";
			return $this->salida;
		}
		
		function frmConsulta()
		{
			return true;
		}

		function frmForma($signos,$sw_inscrito,$metodos,$motivos_susp,$registros_mpf,$consulta_solicitud,$apoyosI)
		{
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			$this->salida.= ThemeAbrirTablaSubModulo('INSCRIPCION AL PROGRAMA PLANIFICACION FAMILIAR');
			
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table><br>";
			
			$op1='';$op2='';
			if($sw_inscrito)
				$op1='disabled';
			else
				$op2='disabled';
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'InscripcionPlanFamiliar'));
			
			$this->salida.="<form name=\"formains$pfj\" action=\"$accion1\" method=\"post\">";
			
			$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			if(sizeof($signos)>0)
			{
				$ta_baja=$signos[0][tabaja];
				$ta_alta=$signos[0][taalta];
				$peso=$signos[0][peso];
			}
			else
			{
				$ta_baja=$_REQUEST['ta_baja'.$pfj];
				$ta_alta=$_REQUEST['ta_alta'.$pfj];
				$peso=$_REQUEST['peso'.$pfj];
				$this->salida.="<input type=\"hidden\" name=\"bandera$pfj\" value=\"1\">";
			}
			$style1="";
			if(!empty($ta_alta) AND $ta_alta>139)
				$style1="style=\"color:#990000;font-weight : bold; \"";
			
			$style2="";
			if(!empty($ta_baja) AND $ta_baja<55)
				$style2="style=\"color:#990000;font-weight : bold; \"";
				
			$check="";
			if($_REQUEST['planificado'.$pfj])
				$check="checked";

			$this->salida.="			<td align=\"left\"><label class=\"".$this->SetStyle("num_hijos_vivos")."\" width=\"25%\">NUMERO HIJOS VIVOS</label><input type=\"text\" class=\"input-text\" name=\"num_hijos_vivos$pfj\" size=\"6\" maxlength=\"2\" value=\"".$_REQUEST['num_hijos_vivos'.$pfj]."\"></td></td>";
			$this->salida.="			<td align=\"left\"><label class=\"".$this->SetStyle("ta")."\" width=\"25%\">PRESION ARTERIAL</label></td>";
			$this->salida.="			<td align=\"left\"><input type=\"text\" class=\"input-text\" $style1 name=\"ta_alta$pfj\" size=\"5\" maxlength=\"3\" value=\"$ta_alta\"> / <input type=\"text\" class=\"input-text\" $style2 name=\"ta_baja$pfj\" size=\"5\" maxlength=\"3\" value=\"$ta_baja\">";
			$this->salida.="			<td align=\"left\"><label class=\"".$this->SetStyle("peso")."\" width=\"25%\">PESO</label><input type=\"text\" class=\"input-text\" name=\"peso$pfj\" size=\"6\" maxlength=\"6\" value=\"$peso\"> Kg</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr>";
			$this->salida.=" 			<td colspan=\"5\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Inscribir$pfj\" value=\"INSCRIBIR\" $op1></td>";
			$this->salida.="  	</tr>";
			$this->salida.=" </table>";
			$this->salida.="</form>";
			
			if($sw_inscrito)
			{
				$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
				$this->salida.=" 	<tr class=\"label\">";
				$this->salida.="		<td colspan=\"4\"><a href=\"javascript:Iniciar('METODOS PLANIFICACION');MostrarSpan('d2Container');\">INGRESAR HISTORIAL DE METODOS DE PLANIFICACION</a></td>";
				$this->salida.="	</tr>";
				$this->salida.="</table>";
			}
			
			$this->salida.= "<div id=\"metodosP\">\n";
			
			if($registros_mpf)
			{
				$salida="";
				$salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
				$salida.="	<tr class=\"modulo_table_list_title\">";
				$salida.="		<td>METODO</td>";
				$salida.="		<td>FECHA INICIO</td>";
				$salida.="		<td>FECHA FIN</td>";
				$salida.="		<td>MOTIVO SUSPENSION</td>";
				$salida.="	</tr>";
	
				$k=0;
				foreach($registros_mpf as $mpf)
				{
					if($k%2==0)
						$estilo="hc_submodulo_list_claro";
					else
						$estilo="hc_submodulo_list_oscuro";
					
					$metodo=$mpf['desc_metodo'];
					if($mpf['sw_otro_met'])
						$metodo=$mpf['otro_metodo'];
					
					$motivo=$mpf['desc_motivo'];
					if($mpf['sw_otro_mot'])
						$motivo=$mpf['otro_motivo_suspencion'];
					
					$salida.=" <tr class=\"$estilo\" align=\"center\">";
					$salida.="		<td>$metodo</td>";
					$salida.="		<td>".$mpf['fecha_ini']."</td>";
					$salida.="		<td>".$mpf['fecha_fin']."</td>";
					$salida.="		<td>$motivo</td>";
					$salida.="	</tr>";
					$k++;
				}
				$salida.="	</table>";
				$this->salida.=$salida;
			}
			$this->salida .= "</div>\n";
			
			if($datosPaciente['sexo_id']=='F' AND $sw_inscrito)
			{
				
				$this->salida.=" <br><table align=\"center\" border=\"0\"  width=\"100%\">";
				$this->salida.="  <tr class=\"modulo_table_list_title\">";
				$this->salida.="   <td align=\"center\">LISTA DE APOYOS DIAGNOSTICOS RELACIONADOS</td>";
				$this->salida.="   <td align=\"center\">SOLICITAR</td>";
				$this->salida.="  </tr>";
				$k=0;
				
				for($i=0;$i<sizeof($apoyosI);$i++)
				{
					if($k%2==0)
						$estilo='hc_submodulo_list_claro';
					else
						$estilo='hc_submodulo_list_oscuro';
						
					$this->salida.="  <tr class=\"$estilo\">";
					if(empty($apoyosI[$i][alias]))
						$descripcion=$apoyosI[$i][descripcion];
					else
						$descripcion=$apoyosI[$i][alias];
						
					$this->salida.="   <td><label class=\"label\">".strtoupper($descripcion)."</label></td>";
					$ban=0;
					for($j=0;$j<sizeof($consulta_solicitud);$j++)
					{
						if($apoyosI[$i][cargo]==$consulta_solicitud[$j][cargo])
						{
							$this->salida.="   <td align=\"center\" width=\"10%\" id=\"capa_sol$i\"><img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></td>";
							$ban=1;
							break;
						}
					}
					if($ban==0)
						$this->salida.="   <td align=\"center\" width=\"10%\" id=\"capa_sol$i\"><a href=\"javascript:Solicitar('".$apoyosI[$i][cargo]."','capa_sol$i');\"><img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>";
					
					$this->salida.="  </tr>";
					$k++;
				}
				/*$this->salida.="  <tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="   <td align=\"right\" colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"solicitar$pfj\" value=\"SOLICITAR\"></td>";
				$this->salida.="  </tr>";*/
				$this->salida.=" </table>";
				
				/*if(!$consulta_solicitud)
					$this->salida.="<br><center><label class=\"label\"><a href=\"javascript:Solicitar();\">Solicitar Citologia</a></label></center>";
				else
					$this->salida.="<br><center><label class=\"label_error\">SOLICITUD DE CITOLOGIA REALIZADA</label></center>";
				*/
			}
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AtencionPlanFliar','Iniciar'=>'1'));
			$accion3=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>''));
			
			$this->salida.=" <table align=\"center\" border=\"0\" width=\"50%\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="	<form name=\"formaini$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"IniciarAtencion$pfj\" value=\"INICIAR ATENCION\" $op2></td>";
			$this->salida.="	</form>";
			$this->salida.="	<form name=\"formavolver$pfj\" action=\"$accion3\" method=\"post\">";
			$this->salida.="		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="  </tr>";
			$this->salida.="</form>";
			$this->salida.=" </table>";
			
			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='d2Contents'>\n";
			$this->salida.="			<form name=\"formaMet$pfj\" action=\"\" method=\"post\">";
			$this->salida.=" 				<table align=\"center\" border=\"0\" cellspacing=\"0\">";
			$this->salida.=" 					<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.=" 						<td class=\"modulo_table_list_title\">METODO</td>";
			$this->salida.=" 						<td>";
			$this->salida.=" 							<select name=\"metodo$pfj\" class=\"select\" onChange=\"Otro(document.formaMet$pfj.metodo$pfj.value,'0');\">";
			$this->salida.=" 							<option value=\"\">--SELECCIONE--</option>";
			foreach($metodos as $metodo)
				$this->salida.=" 							<option value=\"".$metodo['metodo_id']."ç".$metodo['sw_otro']."\">".strtoupper($metodo['descripcion'])."</option>";
			$this->salida.=" 							</select>";
			$this->salida.=" 						</td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 					<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.=" 						<td id=\"otro_metodo\" style=\"display:none\" class=\"modulo_table_list_title\">OTRO METODO</td>";
			$this->salida.=" 						<td id=\"x_otro_metodo\" style=\"display:none\"><input type=\"text\" class=\"input-text\" name=\"otro_metodo$pfj\"  size=\"40\"></td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 					<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.=" 						<td class=\"modulo_table_list_title\">FECHA INICIO</td>";
			$this->salida.=" 						<td><input type=\"text\" class=\"input-text\" name=\"fecha_ini$pfj\" maxlength=\"10\" size=\"10\" readonly><sub>".ReturnOpenCalendario("formaMet$pfj","fecha_ini$pfj","-")."</sub></td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 					<tr class=\"hc_submodulo_list_oscuro\">";
			$this->salida.=" 						<td class=\"modulo_table_list_title\">FECHA FIN</td>";
			$this->salida.=" 						<td><input type=\"text\" class=\"input-text\" name=\"fecha_fin$pfj\" maxlength=\"10\" size=\"10\" readonly><sub>".ReturnOpenCalendario("formaMet$pfj","fecha_fin$pfj","-")."</sub></td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 					<tr class=\"hc_submodulo_list_claro\">";
			$this->salida.=" 						<td class=\"modulo_table_list_title\">MOTIVO SUSPENSION</td>";
			$this->salida.=" 						<td>";
			$this->salida.=" 							<select name=\"motivo_susp$pfj\" class=\"select\" onChange=\"Otro(document.formaMet$pfj.motivo_susp$pfj.value,'1');\">";
			$this->salida.=" 							<option value=\"\">--SELECCIONE--</option>";
			foreach($motivos_susp as $motivo_susp)
				$this->salida.=" 							<option value=\"".$motivo_susp['motivo_suspencion_id']."ç".$motivo_susp['sw_otro']."\">".strtoupper($motivo_susp['descripcion'])."</option>";
			$this->salida.=" 							</select>";
			$this->salida.=" 						</td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 					<tr class=\"hc_submodulo_list_oscuro\" cellspacing=\"0\">";
			$this->salida.=" 							<td id=\"otro_motivo\" style=\"display:none\" class=\"modulo_table_list_title\">OTRO MOTIVO</td>";
			$this->salida.=" 							<td id=\"x_otro_motivo\" style=\"display:none\"><input type=\"text\" class=\"input-text\" name=\"otro_motivo$pfj\"  size=\"40\"></td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 					<tr align=\"center\" class=\"hc_submodulo_list_claro\">";
			$this->salida.=" 						<td colspan=\"4\"><input type=\"button\" class=\"input-submit\" name=\"guardar$pfj\" value=\"GUARDAR\" onclick=\"EvaluarDatos(this.form);\"></td>";
			$this->salida.=" 					</tr>";
			$this->salida.=" 				</table>";
			$this->salida.="			</form>";
			$this->salida.= "	</div>\n";
			$this->salida.= "</div>\n";
			
			$this->salida.=ThemeCerrarTablaSubModulo();
			
			$this->salida.=" <script>";
			$this->salida .= "	var mensaje='';\n";
			$this->salida .= "	var datos;\n";
			$this->salida .= "	var capa_actual='';\n";
			$this->salida .= "	var cargos=new Array();\n";
			
			$this->salida .= "	function Otro(valor,tipo)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valores=jsrsArrayFromString( valor, 'ç' );\n";
			$this->salida .= "		valores[0]=''+valores[0];\n";
			$this->salida .= "		valores[1]=''+valores[1];\n";
			$this->salida .= "		valores[2]=''+tipo;\n";
			$this->salida .= "	 	MetodoPlan(valores);\n";
			$this->salida .= "	}\n";
	
			$this->salida .= "	function MetodoPlan(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		jsrsExecute('hc_modules/InscripcionPlanFamiliar/RemoteScripting/MPF.php',metodosPF,'metodosPF',datos);";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function metodosPF(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		valor=jsrsArrayFromString( html, 'º' );\n";
			$this->salida .= "		if(valor[2]==0)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(valor[1])\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.getElementById('otro_metodo').innerHTML = 'OTRO METODO';\n";
			$this->salida .= "				document.getElementById('x_otro_metodo').innerHTML = valor[0];\n";
			$this->salida .= "				MostrarSpan('otro_metodo');\n";
			$this->salida .= "				MostrarSpan('x_otro_metodo');\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.getElementById('otro_metodo').innerHTML = '';\n";
			$this->salida .= "				document.getElementById('x_otro_metodo').innerHTML = '';\n";
			$this->salida .= "				Cerrar('otro_metodo');\n";
			$this->salida .= "				Cerrar('x_otro_metodo');\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		else\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(valor[1])\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.getElementById('otro_motivo').innerHTML = 'OTRO MOTIVO';\n";
			$this->salida .= "				document.getElementById('x_otro_motivo').innerHTML = valor[0];\n";
			$this->salida .= "				MostrarSpan('otro_motivo');\n";
			$this->salida .= "				MostrarSpan('x_otro_motivo');\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				document.getElementById('otro_motivo').innerHTML = '';\n";
			$this->salida .= "				document.getElementById('x_otro_motivo').innerHTML = '';\n";
			$this->salida .= "				Cerrar('otro_motivo');\n";
			$this->salida .= "				Cerrar('x_otro_motivo');\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		MostrarSpan('d2Container');\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function EvaluarDatos(forma)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		datos=new Array();\n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		var metodo =jsrsArrayFromString(forma.metodo$pfj.value, 'ç' );\n";
			$this->salida .= "		var f_ini = forma.fecha_ini$pfj.value;\n";
			$this->salida .= "		var f_fin = forma.fecha_fin$pfj.value;\n";
			$this->salida .= "		var motivo = jsrsArrayFromString(forma.motivo_susp$pfj.value, 'ç' );\n";
			$this->salida .= "		var otro_metodo = '';\n";
			$this->salida .= "		var otro_motivo = '';\n";
			
			$this->salida .= "		if(!metodo[0])\n";
			$this->salida .= "			mensaje = 'SE DEBE ESCOGER UN METODO';\n";
			
			$this->salida .= "		if(metodo[1])\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			otro_metodo=forma.otro_metodo$pfj.value;\n";
			$this->salida .= "			if(!otro_metodo)\n";
			$this->salida .= "				mensaje='INGRESE EL METODO';\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		if(motivo[1])\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			otro_motivo=forma.otro_motivo$pfj.value;\n";
			$this->salida .= "			if(!otro_motivo)\n";
			$this->salida .= "				mensaje='INGRESE EL MOTIVO DE SUSPENCION';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje == '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			datos[0] = ''+metodo[0];\n";
			$this->salida .= "			datos[1] = ''+metodo[1];\n";
			$this->salida .= "			datos[2] = ''+f_ini;\n";
			$this->salida .= "			datos[3] = ''+f_fin;\n";
			$this->salida .= "			datos[4] = ''+motivo[0];\n";
			$this->salida .= "			datos[5] = ''+motivo[1];\n";
			$this->salida .= "			datos[6] = ''+otro_metodo;\n";
			$this->salida .= "			datos[7] = ''+otro_motivo;\n";
			$this->salida .= "			MetodosPlanificacion(datos);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
		
			
			$this->salida .= "	function MetodosPlanificacion(datos)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		jsrsExecute('hc_modules/InscripcionPlanFamiliar/RemoteScripting/MPF.php',PlanificacionF,'PlanificacionF',datos);";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function PlanificacionF(html)\n";
			$this->salida .=	" {";
			$this->salida .= "		document.getElementById('metodosP').innerHTML = html;\n";
			$this->salida .= "		Cerrar('d2Container');\n";
			$this->salida .=	" }";
			
			$this->salida .= "	function Solicitar(cargo,capa)\n";
			$this->salida .=	" {";
			$this->salida .= "		capa_actual=capa;\n";
			$this->salida .= "		cargos=new Array();\n";
			$this->salida .= "		cargos[0]=cargo;\n";
			$this->salida .= "		jsrsExecute('hc_modules/InscripcionPlanFamiliar/RemoteScripting/MPF.php',SolicitarExamen,'SolicitarExamen',cargos);";
			$this->salida .=	" }";
			
			$this->salida .= "	function SolicitarExamen(html)\n";
			$this->salida .=	" {";
			$this->salida .= "		document.getElementById(capa_actual).innerHTML = html;\n";
			$this->salida .=	" }";
				
			$this->salida.=" </script>";

			$this->salida.=" <script>";
			
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	var titulo = '';\n";
			$this->salida .= "	var contenedor = '';\n";
			
			$this->salida .= "	function Iniciar(tit)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  titulo = 'titulo';\n";
			$this->salida .= "	  contenedor = 'd2Container';\n";
			$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
			$this->salida .= "		document.formaMet$pfj.metodo$pfj.value='';\n";
			$this->salida .= "		document.formaMet$pfj.motivo_susp$pfj.value='';\n";
			$this->salida .= "		document.formaMet$pfj.fecha_ini$pfj.value='';\n";
			$this->salida .= "		document.formaMet$pfj.fecha_fin$pfj.value='';\n";
			$this->salida .= "		document.formaMet$pfj.otro_metodo$pfj.value='';\n";
			$this->salida .= "		document.formaMet$pfj.otro_motivo$pfj.value='';\n";
			$this->salida .= "		Cerrar('otro_metodo');\n";
			$this->salida .= "		Cerrar('x_otro_metodo');\n";
			$this->salida .= "		Cerrar('otro_motivo');\n";
			$this->salida .= "		Cerrar('x_otro_motivo');\n";
			$this->salida .= "		ele = xGetElementById('d2Contents');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/10, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,330,'auto');\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/10, xScrollTop()+24);\n";
			$this->salida .= "	  xResizeTo(ele,330, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,310, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 310, 0);\n";
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
			
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Cerrar(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"none\";\n";
			$this->salida .= "	}\n";
			
			$this->salida.=" </script>";
			
			
			
			return $this->salida;
		}

		function SetStyle($campo)
		{
			if ($this->frmError[$campo]||$campo=="MensajeError")
			{
				if ($campo=="MensajeError")
				{
					return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
			}
			return ("label");
		}
	}
?>