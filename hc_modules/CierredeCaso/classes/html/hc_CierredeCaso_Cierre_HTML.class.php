<?php

/**
* clase atencion de atencion gestantes.
* $Id: hc_CierredeCaso_Cierre_HTML.class.php,v 1.2 2007/02/01 20:44:26 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

class Cierre_HTML
{
	function Cierre_HTML()
	{
		return true;
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
	
	function frmHistoria()
	{
		$this->salida="";
		return $this->salida;
	}
	
	function frmConsulta()
	{
		return true;
	}
	
	function frmCierreCaso($semana_gestante,$link_nacidos,$fcp)
	{
			
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			
			$this->salida.= ThemeAbrirTablaSubModulo('CIERRE DE CASO');
			
			if($this->ban==1)
			{
				if($this->req==1)
				{
					$_REQUEST=null;
				}
			}
			
			$this->frmGestacion($semana_gestante,$fcp);
			
			$this->salida .= "<script>";
			$this->salida .= "	function ActivarMuerteFetal(frm,x){\n";
			$this->salida .= "  		if(x==true){\n";
			$this->salida .= "    			for(i=0;i<frm.elements.length;i++){\n";
			$this->salida .= "      			if(frm.elements[i].type=='radio' &&  frm.elements[i].name == 'parto$pfj'){\n";
			$this->salida .= "        			frm.elements[i].disabled=false;\n";
			$this->salida .= "        			if(frm.elements[i].value==2 && frm.elements[i].checked){\n";
			$this->salida .= "    						e=xGetElementById('atencion');\n";
			$this->salida .= "      					e.style.display=\"none\"";
			$this->salida .= "    					}\n";
			$this->salida .= "        		}";
			$this->salida .= "    			}\n";
			$this->salida .= "    				frm.num_hijos_vivos$pfj.disabled=true;\n";
			$this->salida .= "    				frm.num_hijos_muertos$pfj.disabled=false;\n";
			$this->salida .= " 			}else{\n";
			$this->salida .= "    			for(i=0;i<frm.elements.length;i++){\n";
			$this->salida .= "      			if(frm.elements[i].type=='radio' &&  frm.elements[i].name == 'parto$pfj'){\n";
			$this->salida .= "        			frm.elements[i].disabled=true;\n";
			$this->salida .= "        			if(frm.elements[i].value==2 && frm.elements[i].checked){\n";
			$this->salida .= "    						e=xGetElementById('atencion');\n";
			$this->salida .= "      					e.style.display=\"\"";
			$this->salida .= "    					}\n";
			$this->salida .= "    				}\n";
			$this->salida .= "    			}\n";
			$this->salida .= "    			frm.num_hijos_vivos$pfj.disabled=false;\n";
			$this->salida .= "    			frm.num_hijos_muertos$pfj.disabled=true;\n";
			$this->salida .= "  		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>";
			
			$this->salida .= "<script>";
			$this->salida .= "	function ActivarMemRotas(frm,x){";
			$this->salida .= "  		if(x==true){";
			$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      			if(frm.elements[i].type=='text' &&  frm.elements[i].name == 'fecha_mem$pfj'){";
			$this->salida .= "        			frm.elements[i].disabled=false;";
			$this->salida .= "						}";
			$this->salida .= "      			if(frm.elements[i].name == 'horas_mem$pfj'){";
			$this->salida .= "        			frm.elements[i].disabled=false;";
			$this->salida .= "						}";
			$this->salida .= "      			if(frm.elements[i].name == 'min_mem$pfj'){";
			$this->salida .= "        			frm.elements[i].disabled=false;";
			$this->salida .= "						}";
			$this->salida .= "    			}";
			$this->salida .= " 			}else{";
			$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
			$this->salida .= "      			if(frm.elements[i].type=='text' &&  frm.elements[i].name == 'fecha_mem$pfj'){";
			$this->salida .= "        				frm.elements[i].disabled=true;";
			$this->salida .= "      			}";
			$this->salida .= "      			if(frm.elements[i].name == 'horas_mem$pfj'){";
			$this->salida .= "        				frm.elements[i].disabled=true;";
			$this->salida .= "      			}";
			$this->salida .= "      			if(frm.elements[i].name == 'min_mem$pfj'){";
			$this->salida .= "        				frm.elements[i].disabled=true;";
			$this->salida .= "      			}";
			$this->salida .= "    			}";
			$this->salida .= "  		}";
			$this->salida .= "	}";
			
			$this->salida .= "	function Mostrar(x){\n";
			$this->salida .= "  		if(x==true){\n";
			$this->salida .= "    			e=xGetElementById('atencion');\n";
			$this->salida .= "      		e.style.display=\"\"";
			$this->salida .= " 			}else{";
			$this->salida .= "    			e=xGetElementById('atencion');\n";
			$this->salida .= "      		e.style.display=\"none\"";
			$this->salida .= "  		}";
			$this->salida .= "  }";
			$this->salida .= "</script>";

			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'CierredeCaso'));
			$accionC=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'DatosRecienNacidos'));
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionGestacion'));
			
			$causa = array('PARTO','ABORTO','MUERTE MATERNA');
			
			$this->salida.="<form name=\"forma$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"60%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"100%\">CAUSA DE CIERRE DE CASO</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"modulo_list_oscuro\" align=\"center\">";
			$this->salida.="			<td class=\"label\"> SELECCIONE : ";
			$this->salida.="				<select name=\"causa_cierre$pfj\" class=\"select\" onChange=\"Causa(document.forma$pfj)\">";
			$this->salida.="					<option value=\"\">----SELECCIONE----</option>";
			for($i=1;$i<=sizeof($causa);$i++)
			{
				if($_REQUEST['causa_cie'.$pfj]==$i)
					$this->salida.="					<option value=\"$i\" selected>".$causa[$i-1]."</option>";
				else
					$this->salida.="					<option value=\"$i\">".$causa[$i-1]."</option>";
			}
			$this->salida.="				</select>";
			$this->salida.="			</td>";
			$this->salida.="		</tr>";
			$this->salida.="	</table><br>";
			$this->salida.="</form>";
			
			$this->salida .= "  <div id=\"mensaje\" class=\"label_error\" align=\"center\">";
			$this->salida .= "  </div><br>";
			
			$this->salida.="	<div id=\"cierre\" style=\"display:none\">";
			$this->salida.="	</div>";

			$this->salida .= "<script>\n";
			$this->salida .= "		var mensaje='';\n";
			$this->salida .= "		var muerte='';\n";
			$this->salida .= "		var feto=0;\n";
			$this->salida .= "		var error=0;\n";
			$this->salida .= "		var datosOp=new Array();\n";
			
			$this->salida .= "	function Causa(obj)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.getElementById('mensaje').innerHTML = '';\n";
			$this->salida .= "		var datosC=new Array();\n";
			$this->salida .= "		datosC[0]=obj.causa_cierre$pfj.value;\n";
			$this->salida .= "		datosC[1]='$pfj'\n";
			$this->salida .= "		jsrsExecute('hc_modules/CierredeCaso/RemoteScripting/CierreCaso.php',CierreCaso,'CierreCaso',datosC);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"\";\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function CierreCaso(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.getElementById('cierre').innerHTML = html;\n";
			$this->salida .= "		MostrarSpan('cierre');\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Semana(obj)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datosOp=new Array();\n";
			$this->salida .= "		datosOp[0]=obj.fecha_term$pfj.value;\n";
			$this->salida .= "		datosOp[1]='$pfj'\n";
			$this->salida .= "		jsrsExecute('hc_modules/CierredeCaso/RemoteScripting/CierreCaso.php',CalculoSemana,'CalculoSemana',datosOp);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function CalculoSemana(sem)\n";
			$this->salida .= "	{\n";
			//$this->salida .= "		document.GetElementById('sem_ges').innerHTML=sem\n";
			$this->salida .= "		document.formades$pfj.semana$pfj.value = sem;\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function LlamarCalendariofecha_term$pfj()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open('classes/calendariopropio/Calendario.php?forma=formades$pfj&campo=fecha_term$pfj&separador=-','CALENDARIO_SIIS','width=450,height=250,resizable=no,status=no,scrollbars=yes');\n";
			$this->salida .= "	}\n";

			$this->salida .= "	function EnviarDatos(obj,dato)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		mensaje='';\n";
			$this->salida .= "		var datos=new Array();\n";
			$this->salida .= "		datos[1]=''+dato;\n";
			$this->salida .= "		if(dato=='1')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(obj.fecha_term$pfj.value=='')\n";
			$this->salida .= "				mensaje='DEBE INGRESAR UNA FECHA';\n";
			$this->salida .= "			else if(obj.horas_term$pfj.value=='' || obj.min_term$pfj.value=='')\n";
			$this->salida .= "				mensaje='DEBE INGRESAR UNA HORA';\n";
			$this->salida .= "			else if(obj.semana$pfj.value=='')\n";
			$this->salida .= "				mensaje='FALTA LA SEMANA DE GESTACION';\n";
			$this->salida .= "			else if(obj.sw_muerte_fetal$pfj.checked==true && obj.parto$pfj"."[0].checked==false && obj.parto$pfj"."[1].checked==false)\n";
			$this->salida .= "				mensaje='SELECCIONE EL TIPO DE MUERTE FETAL';\n";
			$this->salida .= "			document.getElementById('mensaje').innerHTML = mensaje;";
			$this->salida .= "			if(mensaje=='')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				datos[2]=''+obj.fecha_term$pfj.value;\n";
			$this->salida .= "				datos[3]=''+obj.horas_term$pfj.value;\n";
			$this->salida .= "				datos[4]=''+obj.min_term$pfj.value;\n";
			$this->salida .= "				datos[5]='null';\n";
			$this->salida .= "				for(var i=0;i<obj.elements.length;i++){\n";
			$this->salida .= "					if(obj.elements[i].type=='radio' && obj.elements[i].name=='terminacion$pfj' && obj.elements[i].checked)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						datos[5]=''+obj.elements[i].value;\n";
			$this->salida .= "						break;\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				datos[6]=''+obj.semana$pfj.value;\n";
			$this->salida .= "				datos[7]='null';\n";
			$this->salida .= "				for(var i=0;i<obj.elements.length;i++){\n";
			$this->salida .= "					if(obj.elements[i].type=='radio' && obj.elements[i].name=='nivel_atencion$pfj' && obj.elements[i].checked)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						datos[7]=''+obj.elements[i].value;\n";
			$this->salida .= "						break;\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				datos[8]='0';\n";
			$this->salida .= "				datos[9]='0';\n";
			$this->salida .= "				datos[10]='0';\n";
			$this->salida .= "				datos[11]='0';\n";
			$this->salida .= "				datos[15]='null';\n";
			$this->salida .= "				if(obj.sw_episiotomia$pfj.checked)\n";
			$this->salida .= "					datos[8]=''+obj.sw_episiotomia$pfj.value;\n";
			$this->salida .= "				if(obj.sw_desgarros$pfj.checked)\n";
			$this->salida .= "					datos[9]=''+obj.sw_desgarros$pfj.value;\n";
			$this->salida .= "				if(obj.sw_muerte_fetal$pfj.checked)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					datos[10]=''+obj.sw_muerte_fetal$pfj.value;\n";
			$this->salida .= "					if(obj.sw_muerte_fetal$pfj.value!='')\n";
			$this->salida .= "						datos[15]=''+obj.num_hijos_muertos$pfj.value;\n";
			$this->salida .= "					else\n";
			$this->salida .= "						datos[15]='null';\n";
			$this->salida .= "					muerte=datos[10];\n";
			$this->salida .= "					for(var i=0;i<obj.elements.length;i++)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						if(obj.elements[i].type=='radio' && obj.elements[i].name=='parto$pfj' && obj.elements[i].checked)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							datos[11]=''+obj.elements[i].value;\n";
			$this->salida .= "							break;\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				if(obj.aten_parto$pfj.value!='')\n";
			$this->salida .= "					datos[12]=''+obj.aten_parto$pfj.value;\n";
			$this->salida .= "				else\n";
			$this->salida .= "					datos[12]='null';\n";
			$this->salida .= "				if(obj.aten_neonato$pfj.value!='')\n";
			$this->salida .= "					datos[13]=''+obj.aten_neonato$pfj.value;\n";
			$this->salida .= "				else\n";
			$this->salida .= "					datos[13]='null';\n";
			$this->salida .= "				if(obj.num_hijos_vivos$pfj.value!='')\n";
			$this->salida .= "					datos[14]=''+obj.num_hijos_vivos$pfj.value;\n";
			$this->salida .= "				else\n";
			$this->salida .= "					datos[14]='null';\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		if(dato=='2')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(obj.fecha_term$pfj.value=='')\n";
			$this->salida .= "				mensaje='DEBE INGRESAR UNA FECHA';\n";
			$this->salida .= "			document.getElementById('mensaje').innerHTML = mensaje;";
			$this->salida .= "			if(mensaje=='')\n";
			$this->salida .= "				datos[2]=''+obj.fecha_term$pfj.value;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		if(dato=='3')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(obj.fecha_term$pfj.value=='')\n";
			$this->salida .= "				mensaje='DEBE INGRESAR UNA FECHA';\n";
			$this->salida .= "			else if(obj.feto_vivo$pfj"."[0].checked==false && obj.feto_vivo$pfj"."[1].checked==false)\n";
			$this->salida .= "				mensaje='DEBE SELECCIONAR FETO VIVE (SI O NO)';\n";
			$this->salida .= "			else if(obj.causa$pfj.value=='')\n";
			$this->salida .= "				mensaje='INSERTE CAUSA DE MUERTE MATERNA';\n";
			$this->salida .= "			document.getElementById('mensaje').innerHTML = mensaje;";
			$this->salida .= "			if(mensaje=='')\n";
			$this->salida .= "			{\n";
			$this->salida .= "				datos[2]=''+obj.fecha_term$pfj.value;\n";
			$this->salida .= "				if(obj.feto_vivo$pfj"."[0].checked)";
			$this->salida .= "					datos[3]=''+obj.feto_vivo$pfj"."[0].value;\n";
			$this->salida .= "				else if(obj.feto_vivo$pfj"."[1].checked)\n";
			$this->salida .= "					datos[3]=''+obj.feto_vivo$pfj"."[1].value;\n";
			$this->salida .= "				feto = datos[3];\n";
			$this->salida .= "				datos[4]=''+obj.causa$pfj.value;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		datos[0]=''+mensaje;\n";
			$this->salida .= "		jsrsExecute('../../../hc_modules/CierredeCaso/RemoteScripting/CierreCaso.php',AlmacenaCierreCaso,'AlmacenaCierreCaso',datos);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function AlmacenaCierreCaso(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		if(html!='')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById('mensaje').innerHTML = html;\n";
			$this->salida .= "			error=1;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Enviar(forma,dato)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		if(mensaje=='' && error==0)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if((dato==1 && muerte=='') || (dato==3 && feto==1))\n";
			$this->salida .= "			{\n";
			$this->salida .= "				forma.action='$accionC';\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				forma.action='$accion2';\n";
			$this->salida .= "			}\n";
			$this->salida .= "			forma.submit();\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";

			//$this->salida.="<center><label class=\"label\"><a href=\"$accionC\">DATOS RECIEN NACIDO</a></label></center>";	

			$this->salida.="	<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="	<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver1$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="	</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			$this->salida.= ThemeCerrarTablaSubModulo();
			
			return $this->salida;
	}
	
	function frmCierreCasoConsulta($datosC)
	{
		
		$pfj=SessionGetVar("Prefijo");
		$evolucion=SessionGetVar("Evolucion");
		$paso=SessionGetVar("Paso");
		
		$this->salida.= ThemeAbrirTablaSubModulo('CIERRE DE CASO');
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\">";
		foreach($datosC as $datos)
		{
			switch($datos['sw_cierre'])
			{
				case 1:
					$this->salida.="	<tr>";
					$this->salida.="		<td width=\"15%\" class=\"modulo_list_oscuro\"><b>TIPO DE CIERRE DE CASO</b></td>";
					$this->salida.="		<td width=\"20%\" class=\"modulo_list_claro\">".$datos['tipo_cierre']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>SEMANA DE GESTACION</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['semanas_gestacion']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>FECHA TERMINACION</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['fecha_terminacion']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>TIPO TERMINACION</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['tipo_terminacion']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>NIVEL SE ATENCION</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['nivel_atencion']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>EPISIOTOMIA</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['sw_episiotomia']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>DESGARROS</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['sw_desgarros']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>MUERTE FETAL</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['sw_muerte_fetal']."</td>";
					$this->salida.="  </tr>";
					if($datos['sw_muerte_fetal']==1)
					{
						$this->salida.="	<tr>";
						$this->salida.="		<td class=\"modulo_list_oscuro\"><b>TIPO MUERTE FETAL</b></td>";
						$this->salida.="		<td class=\"modulo_list_claro\">".$datos['sw_tipo_muerte_fetal']."</td>";
						$this->salida.="  </tr>";
						$this->salida.="	<tr>";
						$this->salida.="		<td class=\"modulo_list_oscuro\"><b>NUMERO HIJOS MUERTOS</b></td>";
						$this->salida.="		<td class=\"modulo_list_claro\">".$datos['num_hijos_muertos']."</td>";
						$this->salida.="  </tr>";
					}
					if($datos['atendio_parto'])
					{
						$this->salida.="	<tr>";
						$this->salida.="		<td class=\"modulo_list_oscuro\"><b>ATENDIO PARTO</b></td>";
						$this->salida.="		<td class=\"modulo_list_claro\">".$datos['atendio_parto']."</td>";
						$this->salida.="  </tr>";
					}
					if($datos['atendio_neonato'])
					{	
						$this->salida.="	<tr>";
						$this->salida.="		<td class=\"modulo_list_oscuro\"><b>ATENDIO NEONATO</b></td>";
						$this->salida.="		<td class=\"modulo_list_claro\">".$datos['atendio_neonato']."</td>";
						$this->salida.="  </tr>";
					}
					if($datos['sw_muerte_fetal']==0)
					{
						$this->salida.="	<tr>";
						$this->salida.="		<td class=\"modulo_list_oscuro\"><b>NUMERO DE HIJOS VIVOS</b></td>";
						$this->salida.="		<td class=\"modulo_list_claro\">".$datos['num_hijos_vivos']."</td>";
						$this->salida.="  </tr>";
					}
				break;
				
				case 2:
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\" width=\"45%\"><b>TIPO DE CIERRE DE CASO</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['tipo_cierre']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>FECHA ABORTO</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['fecha_terminacion']."</td>";
					$this->salida.="  </tr>";
				break;
				
				case 3:
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>TIPO DE CIERRE DE CASO</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['tipo_cierre']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>FECHA MUERTE MATERNA</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['fecha_terminacion']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>CAUSA MUERTE MATERNA</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['causa_muerte_materna']."</td>";
					$this->salida.="  </tr>";
					$this->salida.="	<tr>";
					$this->salida.="		<td class=\"modulo_list_oscuro\"><b>FETO VIVO</b></td>";
					$this->salida.="		<td class=\"modulo_list_claro\">".$datos['feto_vivo']."</td>";
					$this->salida.="  </tr>";
				break;
				
			}
			if($datos['sw_cierre']==1 OR ($datos['sw_cierre']==3 AND $datos['feto_vivo']=='SI'))
			{
				$accionC=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'DatosRecienNacidos'));
				$this->salida.="<tr><td align=\"center\" colspan=\"2\"><br><label class=\"label\"><a href=\"$accionC\">DATOS RECIEN NACIDO</a></label></td></tr>";	
			}
		}
		$this->salida.="</table><br>";
		
		
		$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionGestacion'));

		$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="   <center><input class=\"input-submit\" type=\"submit\" name=\"volver1$pfj\" value=\"VOLVER\"></center>";
		$this->salida.="</form>";
	
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
	}
	
	function frmGestacion($semana,$fecha)
	{
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"40%\" cellpadding=\"0\" cellspacing=\"2\">";
		$this->salida.="	<tr>";
		$this->salida.="		<td class=\"modulo_table_list_title\">FECHA PROBLABLE DE PARTO</td>";
		$this->salida.="  </tr>";
		$this->salida.="	<tr>";
		$this->salida.="		<td class=\"hc_table_submodulo_list_title\"><label class=\"label\">$fecha</label></td>";
		$this->salida.="  </tr>";
		$this->salida.="</table><br>";
		return true;
	}
	
}

?>