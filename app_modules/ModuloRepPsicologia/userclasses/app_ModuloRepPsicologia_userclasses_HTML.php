<?php

/**
 * $Id: app_Notas_y_Monitoreo_userclasses_HTML.php,v 1.11 2005/11/30 23:07:54 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las consultas de pacientes psicologicos.
 */


class app_ModuloRepPsicologia_userclasses_HTML extends app_ModuloRepPsicologia_user
{

	function app_ModuloRepPsicologia_user_HTML()
	{
		$this->salida='';
		$this->app_ModuloRepPsicologia_user();
		return true;
	}


  	function SetStyle($campo)
	{
		if ($this->frmError[$campo] || $campo=="MensajeError"){
			if ($campo=="MensajeError"){
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
     
          
	/**
	* Menu de selecion.
     * FormaInicial()
	*/
	function FormaInicial()
	{
		$this->salida .= ThemeAbrirTabla('MODULO DE REPORTES PSICOLOGICOS');
		$this->Encabezado();
		$this->salida .= "<br>";
		$this->salida .= "<table width=\"60%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "      <td align=\"center\" class=\"modulo_table_list_title\" colspan=\"2\">MENU DE SELECCION</td>";
		$this->salida .= "</tr>";
		
		$this->salida .= "<tr>";
		$accionF=ModuloGetURL('app','ModuloRepPsicologia','user','RepGestionPsicologos');
		$this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a href=\"$accionF\">REPORTE DE GESTION PSICOLOGOS</a></td>";
		$accionF=ModuloGetURL('app','ModuloRepPsicologia','user','RepGestionPsicologos');
		$this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\"><img src=\"".GetThemePath()."/images/estadistica.gif\" border=0></a></td>\n";
		$this->salida .= "</tr>";
		
		$this->salida .= "<tr>";
		$accionH=ModuloGetURL('app','ModuloRepPsicologia','user','RepGestionPacientes');
		$this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\"><a href=\"$accionH\">REPORTE DE GESTION PACIENTES</a></td>";
		$accionH=ModuloGetURL('app','ModuloRepPsicologia','user','RepGestionPacientes');
		$this->salida .= "      <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionH\"><img src=\"".GetThemePath()."/images/atencion_citas.png\" border=0></a></td>\n";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		
		$accion=ModuloGetURL('app','ModuloAdminPsicologia','user','FormaInicial');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
     
     
	/**
	* Forma para consulta de gestion de atencion de citas por psicologos.
	*
	* @return boolean True si se ejecuto correctamente
	* @access private
	*/
	function RepGestionPsicologos()
	{
		$this->salida .= ThemeAbrirTabla('REPORTE DE GESTION PSICOLOGICA');
		$this->Encabezado("PSICOLOGICOS");
		
		$this->salida .= "<br>\n";                    
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		
		$this->salida .= "<br>\n";
		$accion = ModuloGetURL('app','ModuloRepPsicologia','user','Llama_RepConsultaGestionPsicologos');
		$this->salida .= "<form name=\"frmparam\" action=\"$accion\" method=\"post\">";          
		$this->salida .= "<table align=\"center\" width=\"65%\"  border=\"0\" class=\"hc_table_submodulo_list\">\n";
		
		$this->salida .= "<tr class=\"label\" align=\"center\">\n";
		$this->salida .= "<td colspan='4' height='30'>PARAMETROS DE BUSQUEDA</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"10%\">";
		$this->salida .= "<label class=\"label\">FECHA INICIAL:</label>";
		$this->salida .= "</td>";
		$this->salida .= "<td width=\"20%\" class=\"label\">";
		if(!$_REQUEST['feinictra'])
		{
			$_REQUEST['feinictra']=date('01/m/Y');
		}
		$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "".ReturnOpenCalendario('frmparam','feinictra','/')."";
		$this->salida .= "</td>";
		$this->salida .= "<td width=\"10%\">";
		$this->salida .= "<label class=\"label\">FECHA FINAL:</label>";
		$this->salida .= "</td>";
		$this->salida .= "<td class=\"label\" width=\"20%\">";
		if(!$_REQUEST['fefinctra'])
		{
			$_REQUEST['fefinctra']=date('d/m/Y');
		}
		$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "".ReturnOpenCalendario('frmparam','fefinctra','/')."";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"label\">\n";
		$this->salida .= "<td colspan='2' height='30'>PROFESIONAL</td>\n";
		$this->salida .= "<td colspan=\"2\" align=\"left\">";
		$this->salida .= "<select name=\"responsable\" class=\"select\">";
		$psiologos=$this->profesionalesPsicologos();
		$this->salida .= "<option value=\"-1\">---Seleccione---</option>";
		for($i=0;$i<sizeof($psiologos);$i++)
		{
			$value = $psiologos[$i]['usuario_id'];
			$titulo=$psiologos[$i]['nombre'];
			if($value==$_REQUEST['responsable'])
			{
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}	  
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		
          $this->salida .= "<tr class=\"label\">\n";
		$this->salida .= "<td colspan='4' align=\"center\">\n";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"repConsulta\" value=\"CONSULTAR\">";
          $this->salida .= "</td>\n";
		$this->salida .= "</tr>\n";
		          
          $this->salida .= "</table>\n";
		$this->salida .= "</form>";
		
		$accionI=ModuloGetURL('app','ModuloRepPsicologia','user','FormaInicial');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accionI\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
     
     function RepConsultaGestionPsicologos($profesional_escojer,$feinictra,$fefinctra)
     { 
          $archivoPlano='';
          $this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE RENDIMIENTO PROFESIONALES');
          $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "<tr><td>";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr><td>";
          $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida .= "<tr><td colspan=\"2\">REPORTE ESTADISTICO DE RENDIMIENTO PROFESIONALES UNIVALLE";
          $this->salida .= "</td></tr>";
          if(!empty($feinictra)){
               $this->salida .= "      <tr class=modulo_list_claro>";
               $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
               $this->salida .= "      </td>";
               $this->salida .= "      <td align=\"center\" width=\"70%\">";
               $this->salida .= "      ".$feinictra."";
               $this->salida .= "      </td>";
               $this->salida .= "      </tr>";
          }
          if(!empty($fefinctra)){
               $this->salida .= "      <tr class=modulo_list_claro>";
               $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
               $this->salida .= "      </td>";
               $this->salida .= "      <td align=\"center\" width=\"70%\">";
               $this->salida .= "      ".$fefinctra."";
               $this->salida .= "      </td>";
               $this->salida .= "      </tr>";
          }
          
          $this->salida .= "      </table><br>";
          $this->salida .= "    </td></tr>";
          $this->salida .= "   </table><br>";
          $registros=$this->ConsultaEstadisticaRendimientoProf($profesional_escojer,$feinictra,$fefinctra);
          if($registros)
          {
               $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\">";
               $this->salida .= "      <tr class=\"modulo_table_list_title\">";      
               $this->salida .= "      <td>PROFESIONALES</td>";
               $this->salida .= "      <td width=\"9%\">ASIGNADAS</td>";
               $this->salida .= "      <td width=\"10%\">CANCELADAS</td>";
               $this->salida .= "      <td width=\"10%\">ATENDIDAS</td>";
               //$this->salida .= "      <td width=\"10%\">HC ABIERTAS</td>";
               $this->salida .= "      <td width=\"10%\">DIAS</td>";
               $this->salida .= "      <td width=\"15%\">PROMEDIO DE ATENCION (HH:mm)</td>";
               $this->salida .= "      <td width=\"15%\">PROMEDIO CONSULTAS POR DIA</td>";
               $this->salida .= "      </tr>";
               for($i=0; $i<sizeof($registros); $i++)
               {
                    if($i % 2){ $estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td>".$registros[$i]['nombre']."</td>";
                    $this->salida .= "      <td>".$registros[$i]['asignadas']."</td>";
                    $this->salida .= "      <td>".$registros[$i]['canceladas']."</td>";
                    $this->salida .= "      <td>".$registros[$i]['atendidas']."</td>";
                    //$this->salida .= "      <td>".$registros[$i]['abiertas']."</td>";	
                    if($registros[$i]['promedio'])
                    {
                         $diasConsulta=$this->DiasLaboradosProfesional($feinictra,$fefinctra,$registros[$i]['usuario']);
                         $this->salida .= "      <td>".$diasConsulta."</td>";
                         (list($duracion,$minutos)=explode(':',$registros[$i]['promedio']));
                         $this->salida .= "      <td>".$duracion.":".$minutos."</td>";					
                         $this->salida .= "      <td>".round($registros[$i]['atendidas']/$diasConsulta,1)."</td>";
                    }
                    else
                    {
                         $this->salida .= "      <td>&nbsp;</td>";
                         $this->salida .= "      <td>&nbsp;</td>";
                         $this->salida .= "      <td>&nbsp;</td>";
                    }						
			}
               $this->salida .= "      </table><br>";
          }else{
               $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
               $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
               $this->salida .= "      </table><br>";
          }
          //$reporte= new GetReports();//FALSE
          /*$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteRendimientoProfesionales',array("empresa"=>$_SESSION['recoex']['razonso'],"centroutilidad"=>$centroutilidad,"unidadfunc"=>$unidadfunc,
                                             "departamento"=>$departamento,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra,"centroU"=>$centroU,"unidadF"=>$unidadF,"DptoSel"=>$DptoSel),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $funcion=$reporte->GetJavaFunction();
          $this->salida .= "$mostrar";
          $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"left\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
          $this->salida .="   </td></tr>";

     
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
          $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
          $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
          $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosRendimientoProf',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
          $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
          $this->salida .="       </td></tr>";
          $this->salida .= "      </table><BR>";  
               
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoRendimientoProf',array("centroU"=>$centroU,
          "centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,"departamento"=>$departamento,"DptoSel"=>$DptoSel,
          "profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra));*/
          $this->salida .= " <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"center\">";
          $accionF=ModuloGetURL('app','ModuloRepPsicologia','user','RepGestionPsicologos');
		$this->salida .= "  <form name=\"forma\" action=\"$accionF\" method=\"post\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </form>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }	


	/**
	* Forma para consulta de gestion de atencion de citas por psicologos.
	*
	* @return boolean True si se ejecuto correctamente
	* @access private
	*/
	function RepGestionPacientes()
	{
		$this->salida .= ThemeAbrirTabla('REPORTE DE GESTION PSICOLOGICA');
		$this->Encabezado("PACIENTES");
		
		$this->salida .= "<br>\n";                    
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		
		$this->salida .= "<br>\n";
		$accion = ModuloGetURL('app','ModuloRepPsicologia','user','Llama_RepConsultaGestionPacientes');
		$this->salida .= "<form name=\"frmparam\" action=\"$accion\" method=\"post\">";          
		$this->salida .= "<table align=\"center\" width=\"65%\"  border=\"0\" class=\"hc_table_submodulo_list\">\n";
		
		$this->salida .= "<tr class=\"label\" align=\"center\">\n";
		$this->salida .= "<td colspan='4' height='30'>PARAMETROS DE BUSQUEDA</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"20%\">";
		$this->salida .= "<label class=\"label\">FECHA INICIAL:</label>";
		$this->salida .= "</td>";
		$this->salida .= "<td width=\"30%\" class=\"label\">";
		if(!$_REQUEST['feinictra'])
		{
			$_REQUEST['feinictra']=date('01/m/Y');
		}
		$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"feinictra\" value=\"".$_REQUEST['feinictra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "".ReturnOpenCalendario('frmparam','feinictra','/')."";
		$this->salida .= "</td>";
		$this->salida .= "<td width=\"10%\">";
		$this->salida .= "<label class=\"label\">FECHA FINAL:</label>";
		$this->salida .= "</td>";
		$this->salida .= "<td class=\"label\" width=\"20%\">";
		if(!$_REQUEST['fefinctra'])
		{
			$_REQUEST['fefinctra']=date('d/m/Y');
		}
		$this->salida .= "<input type=\"text\" class=\"input-text\" name=\"fefinctra\" value=\"".$_REQUEST['fefinctra']."\" maxlength=\"10\" size=\"10\">";
		$this->salida .= "".ReturnOpenCalendario('frmparam','fefinctra','/')."";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"label\">\n";
		$this->salida .= "<td colspan='2' height='30'>PROFESIONAL</td>\n";
		$this->salida .= "<td colspan=\"2\" align=\"left\">";
		$this->salida .= "<select name=\"responsable\" class=\"select\">";
		$psiologos=$this->profesionalesPsicologos();
		$this->salida .= "<option value=\"-1\">---Seleccione---</option>";
		for($i=0;$i<sizeof($psiologos);$i++)
		{
			$value = $psiologos[$i]['usuario_id'];
			$titulo=$psiologos[$i]['nombre'];
			if($value==$_REQUEST['responsable'])
			{
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}	  
		$this->salida .= "</select>";
		$this->salida .= "</td>";
          $this->salida .= "</tr>\n";
		
          $this->salida .= "<tr class=\"label\">\n";
		$this->salida .= "<td colspan='2' align=\"left\"height='30'>PACIENTES</td>\n";
		$this->salida .= "<td align=\"left\">";
          $this->salida .= "<input type=\"text\" class=\"input-text\" name=\"identificacion\" size=\"15\">&nbsp;";
		$this->salida .= "</td>";          
		$this->salida .= "<td align=\"left\">";
          $this->salida .= "<select name=\"tipo_id\" id=\"tipo_id\" class=\"select\">";
          $psiologos=$this->ConsultaTipos_ID();
          $this->salida .=" 	<option value=\"-1\" selected>---Seleccione---</option>";
          for($i=0;$i<sizeof($psiologos);$i++)
          {
               $value = $psiologos[$i]['tipo_id_paciente'];
               $titulo=$psiologos[$i]['descripcion'];
               if($value==$_REQUEST['tipo_id'])
               {
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }
          $this->salida .= "	</select>";
		$this->salida .= "</td>";
          $this->salida .= "</tr>\n";
		
          $this->salida .= "<tr class=\"label\">\n";
		$this->salida .= "<td colspan='4' align=\"center\">\n";
		$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"repConsulta\" value=\"CONSULTAR\">";
          $this->salida .= "</td>\n";
		$this->salida .= "</tr>\n";
		          
          $this->salida .= "</table>\n";
		$this->salida .= "</form>";
		
		$accionI=ModuloGetURL('app','ModuloRepPsicologia','user','FormaInicial');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accionI\" method=\"post\">";
		$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></p>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
     
     function RepConsultaGestionPacientes($profesional_escojer,$feinictra,$fefinctra,$tipo_paciente,$paciente)
     { 
          $archivoPlano='';
          $this->salida  = ThemeAbrirTabla('REPORTE ESTADISTICO DE ASISTENCIA PACIENTES');
          $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "<tr><td>";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr><td>";
          $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          if(!empty($feinictra)){
               $this->salida .= "      <tr class=modulo_list_claro>";
               $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA INICIAL";
               $this->salida .= "      </td>";
               $this->salida .= "      <td align=\"center\" width=\"70%\">";
               $this->salida .= "      ".$feinictra."";
               $this->salida .= "      </td>";
               $this->salida .= "      </tr>";
          }
          if(!empty($fefinctra)){
               $this->salida .= "      <tr class=modulo_list_claro>";
               $this->salida .= "      <td class=\"modulo_table_list_title\" width=\"30%\">FECHA FINAL";
               $this->salida .= "      </td>";
               $this->salida .= "      <td align=\"center\" width=\"70%\">";
               $this->salida .= "      ".$fefinctra."";
               $this->salida .= "      </td>";
               $this->salida .= "      </tr>";
          }
          
          $this->salida .= "      </table><br>";
          $this->salida .= "    </td></tr>";
          $this->salida .= "   </table><br>";
          $registros=$this->ConsultaEstadisticaRendimientoPac($profesional_escojer,$feinictra,$fefinctra,$tipo_paciente,$paciente);
          if($registros)
          {
               $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\">";
               $this->salida .= "      <tr class=\"modulo_table_list_title\">";      
               $this->salida .= "      <td>PROFESIONALES</td>";
               $this->salida .= "      <td width=\"9%\">ASIGNADAS</td>";
               $this->salida .= "      <td width=\"10%\">CANCELADAS</td>";
               $this->salida .= "      <td width=\"10%\">PRESENCIADAS</td>";
               //$this->salida .= "      <td width=\"10%\">HC ABIERTAS</td>";
               //$this->salida .= "      <td width=\"10%\">DIAS</td>";
               //$this->salida .= "      <td width=\"15%\">PROMEDIO DE ATENCION (HH:mm)</td>";
               $this->salida .= "      </tr>";
               for($i=0; $i<sizeof($registros); $i++)
               {
                    if($i % 2){ $estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
                    $this->salida .= "      <tr class=\"$estilo\">";
                    $this->salida .= "      <td>".$registros[$i]['nombre']."</td>";
                    $this->salida .= "      <td>".$registros[$i]['asignadas']."</td>";
                    $this->salida .= "      <td>".$registros[$i]['canceladas']."</td>";
                    $this->salida .= "      <td>".$registros[$i]['atendidas']."</td>";
			}
               $this->salida .= "      </table><br>";
          }else{
               $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
               $this->salida .= "      <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON DATOS CON ESTOS PARAMETROS</td></tr>";
               $this->salida .= "      </table><br>";
          }
          //$reporte= new GetReports();//FALSE
          /*$mostrar=$reporte->GetJavaReport('app','Reportes_Consulta_Externa','ReporteRendimientoProfesionales',array("empresa"=>$_SESSION['recoex']['razonso'],"centroutilidad"=>$centroutilidad,"unidadfunc"=>$unidadfunc,
                                             "departamento"=>$departamento,"profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra,"centroU"=>$centroU,"unidadF"=>$unidadF,"DptoSel"=>$DptoSel),array('rpt_name'=>'ConsultaExternas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $funcion=$reporte->GetJavaFunction();
          $this->salida .= "$mostrar";
          $this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"left\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"button\" name=\"imprimir1\" value=\"IMPRIMIR REPORTE\" onclick=\"javascript:$funcion\">";
          $this->salida .="   </td></tr>";

     
          $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";        
          $this->salida .= "      <tr><td class=\"label\" align=\"center\">";
          $_SESSION['DESCARGA_DATOS_REPORTES']['DATOS']=$archivoPlano;                    
          $descarga=ModuloGetURL('app','Reportes_Consulta_Externa','user','DescargaDatosRendimientoProf',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
          'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
          'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));          
          $this->salida .= "      <a href=\"$descarga\">Descargar Datos <img title=\"Descargar Archivo\" border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a>";
          $this->salida .="       </td></tr>";
          $this->salida .= "      </table><BR>";  
               
          $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteEstadisticoRendimientoProf',array("centroU"=>$centroU,
          "centroutilidad"=>$centroutilidad,"unidadF"=>$unidadF,"unidadfunc"=>$unidadfunc,"departamento"=>$departamento,"DptoSel"=>$DptoSel,
          "profesional_escojer"=>$profesional_escojer,"feinictra"=>$feinictra,"fefinctra"=>$fefinctra));*/
          $this->salida .= " <table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida .= "  <tr><td align=\"center\">";
          $accionF=ModuloGetURL('app','ModuloRepPsicologia','user','RepGestionPacientes');
		$this->salida .= "  <form name=\"forma\" action=\"$accionF\" method=\"post\">";
          $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\">";
          $this->salida .= "  </form>";
          $this->salida .= "  </td></tr>";
          $this->salida .= "</table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
     /*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
     function Encabezado($titulo)
	{
          $empresa = $_SESSION['AdminPsico']['EMPRESA'];
          if(!$titulo)
          { $titulo = "MENU INICIAL"; }
		$this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= " <tr class=\"modulo_table_title\">";
		$this->salida .= " <td>EMPRESA</td>";
		$this->salida .= " <td>MODULO</td>";
		$this->salida .= " <td>FECHA</td>";
		$this->salida .= " </tr>";
		$this->salida .= " <tr align=\"center\">";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$empresa."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">$titulo</td>";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$this->FormateoFechaLocal(date("Y-m-d"))."</td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		return true;
	}
	
     
//----------------------------------------------------------------------------------------------------

}//fin clase

?>