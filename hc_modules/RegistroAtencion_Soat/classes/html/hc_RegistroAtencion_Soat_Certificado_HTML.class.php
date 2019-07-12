<?php
/**
* Submodulo de Registro de Atencion de Soat
*
* @author Tizziano Perea Ocoro
* @version 1.0
* @package SIIS
* $Id: hc_RegistroAtencion_Soat.php
*/


class Certificado_HTML
{
     function Certificado_HTML()
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
          $this->salida="";
          return $this->salida;
     }
     
     /**
     * Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
     * @return boolean
     */
     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError")
          {
               if ($campo=="MensajeError")
               {
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
               return ("label_error");
          }
          return ("label");
     }

     /**
     * Funcion que pinta el HTML
     * @return boolean
     */
     function frmForma($Signos, $Conciencia, $Expedicion, $Datos_Ingreso, $Examenes, $Diagnosticos, $VectorI, $enlace, $VectorE, $enlace1, $DatosFechaAc, $DatosFechaIn, $SwImprime, $VectorTipo)
     {
     	//Variable prefijo
          $pfj = SessionGetVar("prefijo");
          $datosPaciente = SessionGetVar("DatosPaciente");
          
          $this->salida = ThemeAbrirTablaSubModulo('REGISTRO DE ATENCION SOAT');
          
          $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= $this->SetStyle("MensajeError");
		$this->salida.="</table><br>";
          
          $this->salida.="<table border=\"0\" align=\"center\" width=\"90%\" class=\"hc_submodulo_list_oscuro\">";
          
          $accion=ModuloHCGetURL(SessionGetVar("Evolucion"),SessionGetVar("Paso"),0,'',false,array('accion'.$pfj=>'InsertarDatos'));
          $this->salida.="<form name=\"forma$pfj\" action=\"$accion\" method=\"POST\">";
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\">REGISTRO ATENCION DE SOAT";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\" class=\"modulo_list_claro\">&nbsp;</td>";
          $this->salida.="</tr>";

          $fecha = explode(" ", $DatosFechaAc['fecha_accidente']);
          $fechaAC = explode("-", $fecha[0]);
          $horaAC  = explode(":", $fecha[1]);
          
          $fecha = explode(" ", $DatosFechaIn['fecha_ingreso']);
          $fechaIN = explode("-", $fecha[0]);
          $horaIN  = explode(":", $fecha[1]);
          
          $nombrePaciente = $datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido'];
          
          if(empty($Datos_Ingreso['nombre_acudiente']))
          	$Datos_Ingreso['nombre_acudiente'] = $nombrePaciente;

          if(empty($Datos_Ingreso['id_acudiente']))
          	$Datos_Ingreso['id_acudiente'] = SessionGetVar("paciente");
               
          if(empty($Datos_Ingreso['expedicion_identificacion']))
          	$Datos_Ingreso['expedicion_identificacion'] = $Expedicion;
          
          $this->salida.="<tr>";
          $this->salida.="<td class=\"modulo_list_claro\">";
          $this->salida.="<label class=\"normal_10\">Certifica que atendió en el servicio de Urgencias al Señor(a)</label>&nbsp;&nbsp;&nbsp;<lable class=\"label_mark\">".$nombrePaciente."</label>&nbsp;&nbsp;&nbsp;<br>";
          $this->salida.="<label class=\"normal_10\">Identificado con</label>&nbsp;&nbsp;&nbsp;<label class=\"label_mark\">".SessionGetVar("tipoidpaciente")."</label>&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">número</label>&nbsp;&nbsp;&nbsp;<label class=\"label_mark\">".SessionGetVar("paciente")."</label>";
          $this->salida.="&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">de</label>&nbsp;&nbsp;&nbsp;<label class=\"label_mark\">".$Expedicion."</label>.&nbsp;&nbsp;&nbsp;<br><br>";
          $this->salida.="<label class=\"normal_10\">Quien según declaración de</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"acudiente$pfj\" size=\"50\" value=\"".$Datos_Ingreso['nombre_acudiente']."\"><br><br>";
          $this->salida.="<select name=\"tipo_id$pfj\" class=\"select\">";
          $this->GetTipos_ID($VectorTipo, $_REQUEST['tipo_id'.$pfj]);
          $this->salida .= "</select>";
          $this->salida .= "&nbsp;&nbsp;&nbsp;<label class=\"normal_10\"> No.</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"id_acudiente\" name=\"id_acudiente$pfj\" size=\"10\" value=\"".$Datos_Ingreso['id_acudiente']."\">&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">Expedida en:</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"expedicion_doc$pfj\" size=\"30\" value=\"".$Datos_Ingreso['expedicion_identificacion']."\">&nbsp;&nbsp;&nbsp;";
          $this->salida.="<label class=\"normal_10\">Fue victima de un</label><br><br><label class=\"normal_10\">accidente de tránsito ocurrido el día</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"dia_accidente$pfj\" size=\"2\" maxlength=\"2\" value=\"$fechaAC[2]\" readonly>&nbsp;&nbsp;&nbsp;";
          $this->salida.="<label class=\"normal_10\">mes</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"mes_accidente$pfj\" size=\"2\" maxlength=\"2\" value=\"$fechaAC[1]\" readonly>&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">año</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"año_accidente$pfj\" size=\"3\" maxlength=\"4\" value=\"$fechaAC[0]\" readonly>";
          $this->salida.="&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">a las</label>&nbsp;&nbsp;&nbsp";
          
          //Hora.
          $this->salida.= "<input type=\"text\" name=\"selectHoraAccidente$pfj\" size=\"2\" maxlength=\"2\" value=\"$horaAC[0]\" readonly>&nbsp;&nbsp;:&nbsp;\n";
          $this->salida.= "<input type=\"text\" name=\"selectMinutosAccidente$pfj\" size=\"2\" maxlength=\"2\" value=\"$horaAC[1]\" readonly><br><br>\n";
		//Fin seleccion de la hora.
          
          $this->salida.="<label class=\"normal_10\">horas ingresando al servicio de urgencias de esta institución el día</label>";
		$this->salida.="&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"dia_atencion$pfj\" size=\"2\" maxlength=\"2\" value=\"$fechaIN[2]\" readonly>&nbsp;&nbsp;&nbsp;";
          $this->salida.="<label class=\"normal_10\">mes</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"mes_atencion$pfj\" size=\"2\" maxlength=\"2\" value=\"$fechaIN[1]\" readonly>&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">año</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"año_atencion$pfj\" size=\"3\" maxlength=\"4\" value=\"$fechaIN[0]\" readonly>";
          $this->salida.="<br><br><label class=\"normal_10\">a las</label>&nbsp;&nbsp;&nbsp";
          
          //Hora.
          $this->salida.= "<input type=\"text\" name=\"selectHoraAtencion$pfj\" size=\"2\" maxlength=\"2\" value=\"$horaIN[0]\" readonly>&nbsp;&nbsp;:&nbsp;\n";
          $this->salida.= "<input type=\"text\" name=\"selectMinutosAtencion$pfj\" size=\"2\" maxlength=\"2\" value=\"$horaIN[1]\" readonly>&nbsp;&nbsp;&nbsp;<label class=\"normal_10\">horas con los siguientes hallazgos:</label>\n";
		//Fin seleccion de la hora.
          
          $this->salida.="</td>";
          $this->salida.="</tr>";
          
          $fc = $Signos['fc'];
          $fr = $Signos['fr'];
          
          $temp = $Signos['temp_piel'];
          if(empty($temp))
          	$temp = $Signos['temperatura'];
          
          $t_alta = $Signos['ta_alta'];
          if(empty($t_alta))
          	$t_alta = $Signos['t_alta'];
          
          $t_baja = $Signos['ta_baja'];
          if(empty($t_baja))
          	$t_baja = $Signos['t_baja'];
          
          $this->salida.="<tr class=\"hc_submodulo_list_claro\" >";
          $this->salida.="<td width=\"100%\"><br>";
          $this->salida.="<div id=\"signosV\">";
          $this->salida.="<table width=\"100%\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr width=\"100%\" class=\"modulo_table_list_title\">";
          $this->salida.="<td colspan=\"5\">SIGNOS VITALES";
		$this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr width=\"100%\" class=\"modulo_list_claro\" align=\"center\">";
          $this->salida.="<td><label class='label_mark'>FC:</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"fc$pfj\" size=\"3\" maxlength=\"3\" value=\"".$fc."\">&nbsp;&nbsp;x min.</td>";
          $this->salida.="<td><label class='label_mark'>FR:</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"fr$pfj\" size=\"3\" maxlength=\"3\" value=\"".$fr."\">&nbsp;&nbsp;x min.</td>";
          $this->salida.="<td><label class='label_mark'>Tº:</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"temp$pfj\" size=\"3\" maxlength=\"2\" value=\"".$temp."\">&nbsp;&nbsp;ºC</td>";
          $this->salida.="<td colspan=\"2\"><label class='label_mark'>TENSION:</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"talta$pfj\" size=\"3\" maxlength=\"3\" value=\"".$t_alta."\"> &nbsp;&nbsp;&nbsp;<B>/</B>&nbsp;&nbsp;&nbsp;";
          $this->salida.="<input type=\"text\" name=\"tbaja$pfj\" size=\"3\" maxlength=\"3\" value=\"".$t_baja."\">&nbsp;&nbsp;mmHg</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</div>";          
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $glasgow = $Conciencia['glasgow'];
          
          $this->salida.="<tr class=\"hc_submodulo_list_claro\" >";
		$this->salida.="<td width=\"100%\"><br>";
		$this->salida.="<div id=\"conciencia\">";
          $this->salida.="<table width=\"100%\" class=\"hc_table_submodulo_list\">";
		$this->salida.="<tr width=\"100%\" class=\"modulo_table_list_title\">";
          $this->salida.="<td colspan=\"5\">ESTADO DE CONCIENCIA";
		$this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr width=\"100%\" class=\"modulo_list_claro\" align=\"center\">";
          if($Conciencia['alerta'] == 1){$checked = "checked";}else{$checked = "";}
          $this->salida.="<td><label class='label_mark'>ALERTA:</label>&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"nivel$pfj\" size=\"2\" maxlength=\"1\" value=\"alerta\" $checked></td>";
          if($Conciencia['obnubilado'] == 1){$checked = "checked";}else{$checked = "";}
          $this->salida.="<td><label class='label_mark'>OBNUBILADO:</label>&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"nivel$pfj\" size=\"2\" maxlength=\"1\" value=\"obnubilado\" $checked></td>";
          if($Conciencia['estuporoso'] == 1){$checked = "checked";}else{$checked = "";}
          $this->salida.="<td><label class='label_mark'>ESTUPOROSO:</label>&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"nivel$pfj\" size=\"2\" maxlength=\"1\" value=\"estuporoso\" $checked></td>";
          if($Conciencia['comatoso'] == 1){$checked = "checked";}else{$checked = "";}
          $this->salida.="<td><label class='label_mark'>COMA:</label>&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"nivel$pfj\" size=\"2\" maxlength=\"1\" value=\"coma\" $checked></td>";
          $this->salida.="<td><label class='label_mark'>GLASGOW (7):</label>&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"glasgow$pfj\" size=\"2\" maxlength=\"1\" value=\"".$glasgow."\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table></br>";
          $this->salida.="</div>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          
          $this->salida.="<tr>";
		$this->salida.="<td width=\"100%\" class=\"normal_10N\"><label class='label_mark'>ESTADO DE EMBRIAGUEZ:</label>";
          if($Datos_Ingreso['estado_embriaguez'] == "1"){$checked = "checked";}else{$checked = "";}
          $this->salida.="&nbsp;&nbsp;&nbsp;<b>SI</b><input type=\"radio\" name=\"estado$pfj\" value=\"1\" $checked>";
          if($Datos_Ingreso['estado_embriaguez'] == "0"){$checked = "checked";}else{$checked = "";}          
          $this->salida.="&nbsp;&nbsp;&nbsp;<b>NO</b><input type=\"radio\" name=\"estado$pfj\" value=\"0\" $checked>";
          $this->salida.="&nbsp;&nbsp;&nbsp;<label class=\"normal_10N\">En caso de positivo tomar muestra para alcoholemia u otras drogas.</label><br>";
          $this->salida.="</td>";
		$this->salida.="</tr>";
		
          $this->salida.="<tr>";
          $this->salida.="<td class=\"hc_submodulo_list_claro\" width=\"100%\">";
          $this->salida.="<br><table class=\"modulo_table_list\" width=\"100%\">";
          
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">DATOS POSITIVOS</td>";
          $this->salida.="</tr>";
          
          $this->salida.="<tr>";
          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Cabeza y Organos de los Sentidos</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"cabeza".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['cabeza']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";
          
          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Cuello</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"cuello".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['cuello']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";
          $this->salida.="</tr>";
          
          $this->salida.="<tr>";
          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Torax y Cardiopulmonar</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"torax".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['torax']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";

          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Abdomen</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"abdomen".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['abdomen']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr>";
          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Genitourinario</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"urinario".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['genitourinario']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";
          
          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Pelvis</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"pelvis".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['pelvis']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";
          $this->salida.="</tr>";
          
          $this->salida.="<tr>";
          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Dorso y Extremidades</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"dorso".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['dorso']."</textarea>";          
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";

          $this->salida.="<td align='center' width=\"50%\"><fieldset><legend class=\"field\">Neurológico</legend>";
          $this->salida.="<p>";
          $this->salida.="<textarea name=\"neurologico".$pfj."\" cols=\"40\" rows=\"3\" style = \"width:85%\" class=\"textarea\">".$Examenes['neurologico']."</textarea>";
          $this->salida.="</p>";
          $this->salida.="</fieldset></td>";
          $this->salida.="</tr>";

          $this->salida.="</table><BR>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          
          // Diagnosticos de Ingreso
          /*$this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"center\">DIAGNOSTICOS ASIGNADOS</td>";
          $this->salida.="</tr>";*/

          $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
          $this->salida.="<td width=\"100%\"><br>";
          if($VectorI)
          {
               $this->salida.="<div id=\"DXAsignadosI\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"5\">DIAGNOSTICOS DE INGRESO</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"8%\">CODIGO</td>";
               $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="<td width=\"7%\">ELIMINAR</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($VectorI);$i++)
               {
                    if( $i % 2){$estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_claro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td width=\"8%\" align=\"center\">".$VectorI[$i][diagnostico_id]."</td>";
                    $this->salida.="<td width=\"60%\" align=\"left\">".$VectorI[$i][diagnostico_nombre]."</td>";
                    if($enlace == "0")
                    {
					$this->salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";                    
                    }
                    else
                    {
                    	$this->salida.="<td align=\"center\" width=\"10%\"><a href=\"javascript:Cerrar('DXAsignadosI');MostrarCapa('enlace')\"><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                    }
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
               $this->salida.="</div>";
          }
          $javaAccion = "javascript:Cerrar('DXAsignadosI');MostrarCapa('ContenedorDiagnosticos');IniciarCapaDX('BUSQUEDA DE DIAGNOSTICOS','ContenedorDiagnosticos');CargarContenedor('ContenedorDiagnosticos');VarIngresoEgreso('0');";
          $this->salida.="<div id=\"enlace\" style=\"display:none\" align=\"center\"><a href=\"$javaAccion\"><b>BUSCAR DIAGNOSTICOS</b></a></div>";
          $this->salida.="<div id=\"dx_insertarI\" align=\"center\"></div>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

		// Diagnosticos de Egreso         
          /*$this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"center\">DIAGNOSTICOS ASIGNADOS</td>";
          $this->salida.="</tr>";*/
          $this->salida.="<tr class=\"hc_submodulo_list_claro\">";
          $this->salida.="<td width=\"100%\"><br>";
          $this->salida.="<div id=\"DXAsignadosE\">";  
          if($VectorE)
          {      
               $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"5\">DIAGNOSTICOS DE EGRESO</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"8%\">CODIGO</td>";
               $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="<td width=\"7%\">ELIMINAR</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($VectorE);$i++)
               {
                    if( $i % 2){$estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_claro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td width=\"8%\" align=\"center\">".$VectorE[$i][diagnostico_id]."</td>";
                    $this->salida.="<td width=\"60%\" align=\"left\">".$VectorE[$i][diagnostico_nombre]."</td>";
                    if($enlace1 == "0")
                    {
                         $this->salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";                    
                    }
                    else
                    {
                         $this->salida.="<td align=\"center\" width=\"10%\"><a href=\"javascript:Cerrar('DXAsignadosE');MostrarCapa('enlace1')\"><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
                    }
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
	     }
          $this->salida.="</div>";
          $javaAccion = "javascript:Cerrar('DXAsignadosE');MostrarCapa('ContenedorDiagnosticos');IniciarCapaDX('BUSQUEDA DE DIAGNOSTICOS','ContenedorDiagnosticos');CargarContenedor('ContenedorDiagnosticos');VarIngresoEgreso('1');";
          if($enlace1 != "0")
          {
          	$this->salida.="<div id=\"enlace1\" align=\"center\"><a href=\"$javaAccion\"><b>BUSCAR DIAGNOSTICOS</b></a></div>";
		}
          $this->salida.="<div id=\"dx_insertarE\"></div>";          
                    
          $this->salida.="<div id='ContenedorDiagnosticos' class='d2Container' style=\"display:none\">";
          $this->salida.= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida.= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDiagnosticos');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida.= "    <div id='ContenedorDiagnosticosII'>\n";
          
          $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"4\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type=\"text\" class=\"input-text\" size =\"6\" maxlength =\"6\" name=\"codigo$pfj\" id=\"codigo\" onkeyup=\"xajax_BusquedaDX(document.getElementById('codigo').value, '')\"></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type=\"text\" size=\"50\" class=\"input-text\" name=\"diagnostico$pfj\" id=\"descripcion\" onkeyup=\"xajax_BusquedaDX('', document.getElementById('descripcion').value)\"></td>" ;
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
          
          $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\" width=\"100%\">";
          $this->salida.="	<div>\n";
          $this->salida.="		<div id=\"listaE\"></div>\n";
          $this->salida.="	</div>\n";
          $this->salida.="	<div>\n";
          $this->salida.="		<div id=\"lista\"></div>\n";
          $this->salida.="	</div>\n";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</div>\n";     
          $this->salida.="</div>";

          $this->salida.="</td>";
          $this->salida.="</tr>";

          
          //Funciones JavaScript
          $javaC = "<script>\n";
          $javaC .= "   var contenedor;\n";
          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC.="	    Datos = new Array();\n";
          $javaC.="	    Diagnosticos = new Array();\n"; 
          $javaC.="	    var Retener;\n"; 
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   function IniciarCapaDX(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 680, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/18, xScrollTop()+70);\n";
          $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       ele = xGetElementById('titulo');\n";
          $javaC .= "       xResizeTo(ele, 660, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrar');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 660, 0);\n";
          $javaC .= "   }\n";         
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "	function MostrarCapa(Elemento)\n";
          $javaC.= "	{\n;";
          $javaC.= "		capita = xGetElementById(Elemento);\n";
          $javaC.= "		capita.style.display = \"\";\n";
          $javaC.= "	}\n";
          
          $javaC.= "	function Cerrar(Elemento, Tipo)\n";
          $javaC.= "	{\n";
          $javaC.= "		capita = xGetElementById(Elemento);\n";          
          $javaC.= "		capita.style.display = \"none\";\n";
          $javaC.= "	}\n";                    

          $javaC.= "	function CerrarCapa()\n";
          $javaC.= "	{\n";
          $javaC.= "     	capita = xGetElementById('ContenedorDiagnosticos');\n";
          $javaC.= "     	capita.style.display = \"none\";\n";
          $javaC.= "     	document.getElementById('codigo').value = '';\n";
          $javaC.= "     	document.getElementById('descripcion').value = '';\n";
		$javaC.= "     	document.getElementById('lista').innerHTML = '';\n";
          $javaC.= "     	document.getElementById('listaE').innerHTML = '';\n";
          $javaC.= "     	Datos = '';\n";
          $javaC.= "     	Datos = new Array();\n";
          $javaC.= "	}\n";                    

          $javaC.="		function BusquedaDX(Code, Dx, pag)\n";
          $javaC.="		{\n";
          $javaC.="			xajax_BusquedaDX(Code, Dx, pag);\n";
          $javaC.="		}\n";

          $javaC.="		function LlenarVectorDX(Code, Dx, Sw, Evo)\n";
          $javaC.="		{\n";
          $javaC.="			if(Evo == 1)\n";
          $javaC.="			{\n";
          $javaC.="				Retener = Evo;\n";
          $javaC.="			}\n";
          $javaC.="			if(Code != '')\n";
          $javaC.="			{\n";          
          $javaC.="				if(Datos.length == 0)\n";
          $javaC.="				{\n";
          $javaC.="					Datos[0] = Code;\n";
          $javaC.="					Diagnosticos[0] = Dx;\n";
          $javaC.="				}\n";
          $javaC.="				else\n";
          $javaC.="				{\n";
          $javaC.="					a = Datos.length ++;\n";
          $javaC.="					Datos[a] = Code;\n";
          $javaC.="					Diagnosticos[a] = Dx;\n";        
          $javaC.="				}\n";
          $javaC.="			}\n";
          $javaC.= "     	if(Sw == 1)\n";
          $javaC.= "     	{\n";
          $javaC.= "     		xajax_VectorDX(Datos, Diagnosticos, Retener);\n";
          $javaC.= "     	}\n";
          $javaC.="		}\n";
          
          $javaC.="		function ActivarDesplegarConsulta(Evolucion, Identificador)\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_DesplegarConsulta(Evolucion, Identificador);\n";
          $javaC.="		}\n";
          
          $javaC.="		function OcultarCapa(Identificador)\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_DesplegarConsulta('', Identificador, 1);\n";
          $javaC.="		}\n";
          
          $javaC.="		function VarIngresoEgreso(Tipo)\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_RegistrarVar(Tipo);\n";
          $javaC.="		}\n";
         
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

          
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\">";
          $this->salida.="<div id=\"Bottom\">";
          $this->salida.="<input name=\"guardar".$pfj."\" class=\"input-submit\" type=\"submit\" onclick=\"validarEntero();\" value=\"GUARDAR REGISTRO\">";
          $this->salida.="</div>";
          $this->salida.="</td>";
          $this->salida.="</tr>";

          $reporte = new GetReports();
          $mostrar=$reporte->GetJavaReport('hc','RegistroAtencion_Soat','ReporteAtencion_Soat',array(),array('rpt_name'=>'AtencionSoat'.SessionGetVar("Ingreso"),'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $nombre_funcion=$reporte->GetJavaFunction();
          $this->salida.=$mostrar;

          if($SwImprime == 1)
          {
               $this->salida.="<tr>";
               $this->salida.="<td align=\"center\">";
               $this->salida.="<a href=\"javascript:$nombre_funcion\"><B>IMPRIMIR REPORTE</B></a>";
               $this->salida.="</td>";
               $this->salida.="</tr>";
          }
          
          $this->salida.="</form>";
          $this->salida.="</table>";
          $this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
	}
     
     function GetTipos_ID($vect, $TipoId)
     {
          foreach($vect as $value=> $titulo)
          {
               if($value==$TipoId){
                    $this->salida .=" <option value=\"".$titulo['tipo_id_paciente']."\" selected>".$titulo['tipo_id_paciente']."</option>";
               }else{
                    $this->salida .=" <option value=\"".$titulo['tipo_id_paciente']."\">".$titulo['tipo_id_paciente']."</option>";
               }
          }
     }

}
?>