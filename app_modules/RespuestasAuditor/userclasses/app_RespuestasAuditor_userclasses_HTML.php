<?php

/**
 * $Id: app_RespuestasAuditor_userclasses_HTML.php,v 1.3 2005/11/22 21:19:33 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las autorizaciones.
 */

class app_RespuestasAuditor_userclasses_HTML extends app_RespuestasAuditor_user
{

     function app_RespuestasAuditor_user_HTML()
	{
          $this->salida='';
          $this->app_RespuestasAuditor_user();
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
	*
	*/
	function FormaMenus()
	{	
          if(empty($_SESSION['RESPUESTAS']['EMPRESA']))
          {
               $_SESSION['RESPUESTAS']['EMPRESA_ID']=$_REQUEST['DatosRespuesta']['empresa_id'];
               $_SESSION['RESPUESTAS']['EMPRESA']=$_REQUEST['DatosRespuesta']['razon_social'];
          }

          $this->FormaMetodoBuscar();/*salida .= ThemeAbrirTabla('MENU AUDITORIA MEDICA');
          $buscar_planes = $this->BuscarPlan_Auditor();
          $this->salida .= "<br>";
          $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">PLANES</td>";
          $this->salida .= "</tr>";
          unset($_SESSION['AUDITORIA']['PLAN']);
          unset($_SESSION['AUDITORIA']['NOM_PLAN']);
          unset($_SESSION['AUDITORIA']['TIPO_PLAN']);
          unset($_SESSION['AUDITORIA']['TIPO_AUDITORIA']);
          foreach ($buscar_planes as $k => $v)
          {
          	$this->salida .= "<tr>";          
               $accionF=ModuloGetURL('app','AuditoriaMedica','user','FormaMetodoBuscar',array('plan_id'=>$v[plan_id],'desc_plan'=>$v[plan_descripcion],'tipo_plan'=>$v[tipo],'tipo_auditoria'=>$v[sw_tipo_auditoria]));
               $this->salida .= "<td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionF\">".strtoupper($v[plan_descripcion])."</a></td>";
	          $this->salida .= "</tr>";
          }
          $this->salida .= "           </table>";
          $accion=ModuloGetURL('app','AuditoriaMedica','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();*/
          return true;
	}

	
     /*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
     function Encabezado()
	{
		$this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= " <tr class=\"modulo_table_title\">";
		$this->salida .= " <td>EMPRESA</td>";
		$this->salida .= " <td>MODULO</td>";
		$this->salida .= " <td>FECHA</td>";
		$this->salida .= " </tr>";
		$this->salida .= " <tr align=\"center\">";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['RESPUESTAS']['EMPRESA']."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">RESPUESTAS AUDITORES</td>";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$this->FormateoFechaLocal(date("Y-m-d"))."</td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		return true;
	}


     function GetHtmlPlan($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[plan_id]==$TipoId){
                    $this->salida .=" <option align=\"center\" value=\"".$titulo[plan_id]."\" selected>".$titulo[plan_descripcion]."</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"".$titulo[plan_id]."\">".$titulo[plan_descripcion]."</option>";
               }
          }
     }

     
     function GetHtmlProfesional($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[usuario_id]==$TipoId){
                    $this->salida .=" <option value=\"$titulo[usuario_id]\" selected>".strtoupper($titulo[nombre])."</option>";
               }else{
                    $this->salida .=" <option value=\"$titulo[usuario_id]\">".strtoupper($titulo[nombre])."</option>";
               }
          }
     }
	

	/*
	* Esta funcion realiza la busqueda de las ordenes de servicio seg?n filtros como numero de orden
	* documento y plan
	* @return boolean
	*/
	function FormaMetodoBuscar($Busqueda,$arr,$f)
	{
          $this->salida.= ThemeAbrirTabla('RESPUESTAS AUDITORES - BUSQUEDA');
          $this->Encabezado();

          if($_SESSION['RESPUESTAS']['PLAN'] =='')
          {
          	$_SESSION['RESPUESTAS']['PLAN'] = $_REQUEST['plan_id'];
               $_SESSION['RESPUESTAS']['NOM_PLAN'] = $_REQUEST['desc_plan'];
          }

          $RUTA = "app_modules/RespuestasAuditor/buscador.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.="  function xxx(a){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    url2 +=a;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
			
          $mostrar.="  function limpiar(){\n";
          $mostrar.="  document.data.centroutilidad.value='';\n";
          $mostrar.="  document.data.unidadfunc.value='';\n";
          $mostrar.="  document.data.departamento.value='';\n";
          
          $mostrar.="  document.data.fechaini.value='';\n";
          $mostrar.="  document.data.fechafin.value='';\n";   
         
          $mostrar.="  document.data.centroU.value='';\n";
          $mostrar.="  document.data.unidadF.value='';\n";
          $mostrar.="  document.data.DptoSel.value='';\n";
        
          $mostrar.="  };\n";
          $mostrar.="</script>\n";
          $this->salida .="$mostrar";
          
          if(!$Busqueda){ $Busqueda=1; }
          $accion=ModuloGetURL('app','RespuestasAuditor','user','BuscarOrden');
          $this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td width=\"80%\" >";
          $this->salida .= "<br><table border=\"0\" width=\"90%\" align=\"center\">";

          $this->salida .= "<tr><td><fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>";
          $this->salida .= "<table width=\"95%\" align=\"center\" border=\"0\">";
          $this->salida .= "<form name=\"data\" action=\"$accion\" method=\"post\">";

          $this->salida .= "<tr><td width=\"15%\" class=\"label\">CENTRO UTILIDAD: </td>";
          $this->salida .= "<td width=\"15%\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"centroutilidad\" value=\"".$_REQUEST['centroutilidad']."\" maxlength=\"100\" size=\"50\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"20%\" class=\"label\">UNIDAD FUNCIONAL: </td>";
          $this->salida .= "<td width=\"15%\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"unidadfunc\" value=\"".$_REQUEST['unidadfunc']."\" maxlength=\"100\" size=\"50\" readonly></td></tr>";
          $this->salida .= "<tr><td width=\"15%\" class=\"label\">DEPARTAMENTO: </td>";
          $this->salida .= "<td width=\"15%\" colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$_REQUEST['departamento']."\" maxlength=\"100\" size=\"50\" readonly>";
          $this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:xxx(1)\"><img title='Busqueda del Departamento' src=\"". GetThemePath() ."/images/auditoria.png\" border='0' width='14' height='14'></td></tr>";
          
          $this->salida .= "<input type=\"hidden\" name=\"centroU\" class=\"input-text\" value=\"".$_REQUEST['centroU']."\">";
          $this->salida .= "<input type=\"hidden\" name=\"unidadF\" class=\"input-text\" value=\"".$_REQUEST['unidadF']."\">";
          $this->salida .= "<input type=\"hidden\" name=\"DptoSel\" class=\"input-text\" value=\"".$_REQUEST['DptoSel']."\">";
          
          $this->salida .= "<tr><td class=\"label\">PLAN: </td><td colspan=\"2\"><select name=\"plan\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
					$vector = $this->BuscarPlan_Auditor();
          $this->GetHtmlPlan($vector,$_REQUEST['plan']);
          $this->salida .= "</select></td></tr>";

          $this->salida .= "<tr><td class=\"label\">PROFESIONAL: </td><td colspan=\"2\"><select name=\"profesional_escojer\" class=\"select\">";
          $this->salida .= "<option value=-1 selected>--  SELECCIONE --</option>";
          $vector_P=$this->Get_Profesionales();
          $this->GetHtmlProfesional($vector_P,$_REQUEST['profesional_escojer']);
          $this->salida .= "</select></td></tr>";

          $this->salida .= "<tr><td class=\"label\">FECHA</td>";
          $this->salida .= "<td align=\"left\" class=\"label\" width=\"50%\" colspan=\"2\">DESDE &nbsp;<input type=\"text\" class=\"input-text\" name=\"fechaini\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechaini']."\"><sub>".ReturnOpenCalendario('data','fechaini','-')."</sub>&nbsp;&nbsp;HASTA &nbsp;<input type=\"text\" class=\"input-text\" name=\"fechafin\" size='11' maxlength=\"10\" READONLY value=\"".$_REQUEST['fechafin']."\"><sub>".ReturnOpenCalendario('data','fechafin','-')."</sub></label></td></tr>";
          $this->salida .= "</table>";					
				 	
          $this->salida .= "<table width=\"95%\" align=\"center\" border=\"0\">";
          $this->salida .= "<tr><td align='center' colspan=\"$col\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
          $this->salida .= "</form>";
          $actionM=ModuloGetURL('app','RespuestasAuditor','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
          $this->salida .= "<td align=\"left\"><br><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"BORRAR CASILLAS\" onclick='limpiar();'></td>";
          $this->salida .= "<td align=\"left\">&nbsp;</td>";
          $this->salida .= "</tr>";
					 
          $this->salida .= "</fieldset></td></tr></table>";
          $this->salida .= "</table>";
          $this->salida .= "</td>";

          $this->salida .= "</tr>";
          $this->salida .= "</table>";
			
          if (empty($this->dos)){
			$this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
	     }
          if(!empty($arr) AND !empty($f))
          {
               $mostrar ="\n<script language='javascript'>\n";
               $mostrar.="function mOvr(src,clrOver) {;\n";
               $mostrar.="src.style.background = clrOver;\n";
               $mostrar.="}\n";

               $mostrar.="function mOut(src,clrIn) {\n";
               $mostrar.="src.style.background = clrIn;\n";
               $mostrar.="}\n";
               $mostrar.="</script>\n";
               $this->salida .="$mostrar";

               $this->salida .= "<table class=\"modulo_table_list_title\" width=\"80%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
               
               $this->salida .= "<tr>";
               $this->salida .= "<td align=\"center\" width=\"10%\" class=\"modulo_table_title\">Fecha.</td>";
               $this->salida .= "<td align=\"center\" width=\"10%\" class=\"modulo_table_title\">Prioridad.</td>";
               $this->salida .= "<td align=\"center\" width=\"60%\" class=\"modulo_table_title\">Nota de Auditoria.</td>";
               $this->salida .= "<td align=\"center\" width=\"10%\" class=\"modulo_table_title\">Ver.</td>";
               $this->salida .= "<td align=\"center\" width=\"10%\" class=\"modulo_table_title\">Cerrar.</td>";
               $this->salida .= "</tr>";
               //$vector=array();//reiniciamos el vector q va a comparar.

               $backgrounds=array('modulo_list_claro'=>'#F4F4F4','modulo_list_oscuro'=>'#F4F4F4');
               //$reporte= new GetReports();
               $a = 0;
               for($i=0;$i<sizeof($arr);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_claro';}

                    if($arr[$i][nota_auditoria_id] != $arr[$i-1][nota_auditoria_id])
                    {
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_claro';}
                         
                         $PLAN = $this->TraerNombre_Plan($arr[$i][plan_id]);     
                         
                         $this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
                         $accionVer = "<a href=\"".ModuloGetURL('app','RespuestasAuditor','user','Informacion_NotaAuditoria',array('ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id],'hc_evolucion'=>$arr[$i][hc_evolucion],'nota_auditoria_id'=>$arr[$i][nota_auditoria_id],'nombre'=>$arr[$i][nombre],'paciente_id'=>$arr[$i][paciente_id],'tipo_id_paciente'=>$arr[$i][tipo_id_paciente], 'nombre_plan'=>$PLAN[plan_descripcion])) ."\" target=\"Contenido\"><img src=\"". GetThemePath() ."/images/Listado.png\" border='0' width='17' height='17' title=\"Ver informaci?n de la nota de auditoria.\"></a>";
                    
                         $fecha = explode(' ', $arr[$i][fecha_registro]);
                         $this->salida .= "<td align=\"center\" width=\"10%\">".$fecha[0]."</td>";

                         if($arr[$i][sw_prioridad] == '0')
                         { $prioridad = "<img src=\"". GetThemePath() ."/images/baja.png\" border=\"0\" title=\"Prioridad Baja\">"; }
                         elseif($arr[$i][sw_prioridad] == '1')
                         { $prioridad = "<img src=\"". GetThemePath() ."/images/media.png\" border=\"0\" title=\"Prioridad Media\">"; }
                         elseif($arr[$i][sw_prioridad] == '2')
                         { $prioridad = "<img src=\"". GetThemePath() ."/images/alta.png\" border=\"0\" title=\"Prioridad Alta\">"; }

                         $this->salida .= "<td align=\"center\" width=\"10%\">".$prioridad."</td>";
                         if($arr[$i][sw_responder] == 1)
                         {  $estilo='label_mark';  }
                         $this->salida .= "<td align=\"left\" width=\"60%\" class=\"$estilo\">".$arr[$i][nota]."</td>";
                         $this->salida .= "<td align=\"center\" width=\"10%\">".$accionVer."</td>";
                         //$AccionCerrar = ModuloGetURL('app','RespuestasAuditor','user','cerrarCasoAuditoria', array('nota_auditoria_id'=>$arr[$i][nota_auditoria_id]));
												 
												$msg='ESTA SEGURO QUE DESEA CERRAR ESTE CASO.';
												$arreglo=array('nota_auditoria_id'=>$arr[$i][nota_auditoria_id]);
												$AccionCerrar=ModuloGetURL('app','RespuestasAuditor','user','ConfirmarAccion',array('c'=>'app','m'=>'RespuestasAuditor','me2'=>'BuscarOrden','me'=>'cerrarCasoAuditoria','mensaje'=>$msg,'titulo'=>'CERRAR CASO DE AUDITORIA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
												 
												$this->salida .= "<td align=\"center\" width=\"10%\"><a href=\"$AccionCerrar\"><img src=\"". GetThemePath() ."/images/desmonitorizado.png\" border=\"0\" title=\"Cerrar Caso de Auditoria.\"></a></td>";
												$this->salida .= "</tr>";

												$this->salida .= "<tr class=\"modulo_list_claro\">";
												$this->salida .= "<td align=\"left\" width=\"10%\">RESPONSABLE: </td>";
												$this->salida .= "<td colspan=\"4\" align=\"left\" width=\"90%\">".$PLAN[plan_descripcion]."</td>";
												$this->salida .= "</tr>";
                    }
               }
               $this->salida.="</table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra1();
          }
          $this->salida .= ThemeCerrarTabla();
          return true;
     }
     
     
     function Informacion_NotaAuditoria()
     {
          $nota_auditoria_id = $_REQUEST['nota_auditoria_id'];
          $nombre = $_REQUEST['nombre'];
          $paciente_id = $_REQUEST['paciente_id'];
          $tipo_id_paciente = $_REQUEST['tipo_id_paciente'];
     	$ingreso = $_REQUEST['ingreso'];
          $evolucion = $_REQUEST['evolucion'];
          $hc_evolucion = $_REQUEST['hc_evolucion'];
          $nombre_plan = $_REQUEST['nombre_plan'];
          
          $this->salida = ThemeAbrirTabla('INFORMACION');
          $titulo = "NOTA DE AUDITORIA";
          $this->Encabezado($titulo);
		
          $info_nota = $this->GetInformacion_NotaAuditoria($nota_auditoria_id);
          $info_respuesta = $this->GetRespuesta_NotaAuditoria($nota_auditoria_id);
                    
          $actionM=ModuloGetURL('app','RespuestasAuditor','user','InsertarRespuesta_NotaAuditoria',array('nota_auditoria_id'=>$nota_auditoria_id,
          'nombre'=>$nombre, 'paciente_id'=>$paciente_id, 'tipo_id_paciente'=>$tipo_id_paciente,
          'ingreso'=>$ingreso, 'evolucion'=>$evolucion, 'hc_evolucion'=>$hc_evolucion));
          
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          
          $this->salida.="<br><table border=\"0\" align=\"center\"  width=\"100%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";
          
          $this->salida.="<br><table border=\"0\" align=\"center\"  width=\"80%\">";
          $this->salida.= "<tr><td align=\"center\" class=\"label_mark\" width=\"90%\"> PLAN:  ";
          $this->salida.= strtoupper($nombre_plan);
          $this->salida.= "</td></tr>";
          $this->salida.="</table>";

          /*DIALOGO DE NOTAS Y RESPUESTAS*/
          $this->salida .= "<br><table width=\"80%\" border=\"0\" class=\"modulo_list_oscuro\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";

          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">";
          $this->salida .= "PACIENTE";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          

               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">DATOS PACIENTE:</td>";
               $this->salida .= "<td colspan=\"2\" align=\"justify\" width=\"50%\" class=\"modulo_list_claro\"><b>".$tipo_id_paciente." ".$paciente_id."  -  ".$nombre."</b></td>";
               $this->salida .= "</tr>";
                              
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">NOTA DE LA AUDITORIA</td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "</tr>";
               $this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"modulo_list_claro\"><b>NOTA: </b><br>".$info_nota[0][nota]."</td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "</tr>";
               $this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"modulo_list_claro\">&nbsp;</td>";
               $this->salida .= "</tr>";

               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">DATOS DE LA NOTA DE AUDITORIA</td>";
               $this->salida .= "</tr>";

               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">FECHA Y HORA DE REGISTRO: </td>";
               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">HISTORIA CLINICA: </td>";
               $this->salida .= "</tr>";
                
               $this->salida .= "<tr>";
               $fecha = explode(' ', $info_nota[0][fecha_registro]);
               $hora = explode(':', $fecha[1]);
               $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"50%\" class=\"modulo_list_claro\"><b>".$fecha[0]." a las,  ".$hora[0]." : ".$hora[1]."</b></td>";
               if(!empty($info_nota[0][evolucion_id]))
               {
               	$dato_evolucion = "y la Evoluci?n: <b>".$info_nota[0][evolucion_id]."</b>";
               }else
               {
               	$dato_evolucion = "&nbsp;";               
               }
               $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"50%\" class=\"modulo_list_claro\">Del Ingreso: <b>".$info_nota[0][ingreso]."</b>&nbsp;&nbsp;&nbsp;&nbsp;".$dato_evolucion."</td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr>";
               $this->salida .= "<td width=\"25%\" class=\"hc_table_submodulo_list_title\">PRIVACIDAD</td>";
               if($info_nota[0][sw_privada] == '0')
               { $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITOR</td>"; }
               elseif($info_nota[0][sw_privada] == '1')
               { $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITORES Y MEDICOS</td>"; }
               elseif($info_nota[0][sw_privada] == '2')
               { $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITOR EXTERNO</td>"; }
               elseif($info_nota[0][sw_privada] == '3')
               { $this->salida .= "<td width=\"25%\" class=\"modulo_list_claro\">AUDITOR INTERNO</td>"; }
               
               $this->salida .= "<td width=\"25%\" class=\"hc_table_submodulo_list_title\">PRIORIDAD</td>";
               if($info_nota[0][sw_prioridad] == '0')
               { $this->salida .= "<td width=\"25%\" align=\"center\" class=\"modulo_list_claro\"><b>BAJA</b></td>"; }
               elseif($info_nota[0][sw_prioridad] == '1')
               { $this->salida .= "<td width=\"25%\" align=\"center\" class=\"modulo_list_claro\"><b>MEDIA</b></td>"; }
               elseif($info_nota[0][sw_prioridad] == '2')
               { $this->salida .= "<td width=\"25%\" align=\"center\" class=\"modulo_list_claro\"><b>ALTA</b></td>"; }

               $this->salida .= "</tr>";

               
               if($info_nota[0][sw_tipo_auditor]=='1')
               { $auditor = 'AUDITOR INTERNO A CARGO: ';
               }else
               { $auditor = 'AUDITOR EXTERNO A CARGO: '; }
               
               $USR = $this->TraerUsuario($info_nota[0][usuario_id]);

               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">AUDITOR</td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">".$auditor."</td>";
               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"modulo_list_claro\"><b>".strtoupper($USR[nombre])."</b></td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">CAUSA</td>";
               $this->salida .= "</tr>";

								$this->salida .= "<tr>";
								$this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">DESCRIPCION DEL TIPO DE AUDITORIA: </td>";
								$this->salida .= "<td colspan=\"2\" align=\"justify\" width=\"50%\" class=\"modulo_list_claro\">";
								for($i=0; $i<sizeof($info_nota); $i++)
								{
										$this->salida .= "<li><div>".$info_nota[$i][descripcion_tipo_nota]."</div></li>";
								}
								$this->salida .= "</td>";
								$this->salida .= "</tr>";	
             
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">RESPONDER</td>";
               $this->salida .= "</tr>";
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"hc_table_submodulo_list_title\">";
               $this->salida .= "<textarea name=\"respuesta\" rows=\"7\" style=\"width:100%\" class=\"textarea\"></textarea>";
               $this->salida .= "</td>";
               $this->salida .= "</tr>";
               $this->salida .= "<tr>";
               $this->salida .= "<td colspan=\"4\" align=\"center\" width=\"100%\" class=\"modulo_list_claro\">";
               $this->salida .= "<input type=\"submit\" class=\"input-submit\" name=\"responder\" value=\"RESPONDER\">";
               $this->salida .= "</td>";
               $this->salida .= "</tr>";

          $this->salida .= "</tr>";
          $this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"modulo_list_claro\">&nbsp;</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"4\" width=\"100%\" class=\"modulo_table_title\">RESPUESTAS</td>";
          $this->salida .= "</tr>";
					

          foreach ($info_respuesta as $k2 => $v1)
          {
               $USR = $this->TraerUsuario($v1[usuario_id]);
               $this->salida .= "<tr>";
               if($v1[sw_tipo_usuario] == '1')
               {
	               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">PROFESIONAL</td>";               
               }else
               {
	               $this->salida .= "<td colspan=\"2\" width=\"50%\" align=\"center\" class=\"modulo_table\"><b>AUDITOR</b></td>";                              
               }
               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"modulo_list_claro\"><b>".strtoupper($USR[nombre])."</b></td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "<tr>";
               $fecha1 = explode(' ', $v1[fecha_registro]);
               $hora1 = explode(':', $fecha1[1]);
               if($v1[sw_tipo_usuario] == '1')
               {	
               	$estilo='modulo_list_claro';
	               $this->salida .= "<td colspan=\"2\" width=\"50%\" class=\"hc_table_submodulo_list_title\">FECHA Y HORA DE REGISTRO: </td>";
               }else
               {
               	$estilo='modulo_list_oscuro';
                    $this->salida .= "<td colspan=\"2\" width=\"50%\" align=\"center\" class=\"modulo_table\"><b>FECHA Y HORA DE REGISTRO: </b></td>";
               }
               $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"50%\" class=\"modulo_list_claro\">".$fecha1[0]." a las,  ".$hora1[0]." : ".$hora1[1]."</td>";
               $this->salida .= "</tr>";
               
               $this->salida .= "</tr>";
               $this->salida .= "<td colspan=\"4\" align=\"justify\" width=\"100%\" class=\"$estilo\"><b>NOTA: </b><br>".$v1[respuesta]."</td>";
               $this->salida .= "</tr>";
          }

          $this->salida .= "</table>";
          $this->salida .= "</form>";
 		/*DIALOGO DE NOTAS Y RESPUESTAS*/
          
          $actionM=ModuloGetURL('app','RespuestasAuditor','user','FormaMenus');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";
          
          
          /*INSERTAR NOTA MEDICA*/
          /*if(!empty($evolucion))
          {
          	$hc_evolucion = $evolucion;
          	$accionHIS=ModuloHCGetURL($evolucion,'','','','');
          }elseif(!empty($hc_evolucion))
          {
          	$accionHIS=ModuloHCGetURL($hc_evolucion,'','','','');          
          }else
          {
          	$hc_evolucion = $_REQUEST['Evolucion_Para_Modulo'];
          	$accionHIS=ModuloHCGetURL($hc_evolucion,'','','','');          
          }
          
          $this->salida .= "<br><center><div class='label_mark'><font size=\"3\">HISTORIA CLINICA</font></div></center>";
          $this->salida .= "<br><center><div><IFRAME border=\"0\" width=\"79%\" align=\"center\" height=\"600\" SRC='$accionHIS'>";
          $this->salida .= "</IFRAME></div></center>";

          $actionM=ModuloGetURL('app','RespuestasAuditor','user','InsertNotaMedica',array('ingreso'=>$ingreso,'evolucion_id'=>$evolucion,
          'Evolucion_Para_Modulo'=>$hc_evolucion,'forma_volver'=>'Ok','nota_auditoria_id'=>$nota_auditoria_id,'nombre'=>$nombre,
          'paciente_id'=>$paciente_id,'tipo_id_paciente'=>$tipo_id_paciente,'evolucion'=>$evolucion));
           
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";

          $this->salida .= "<br><table width=\"80%\" border=\"1\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">";
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"100%\" class=\"modulo_table_title\">";
          $this->salida .= "NOTA MEDICA";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td colspan=\"2\" align=\"center\" width=\"100%\" class=\"modulo_list_claro\">";
          $this->salida .= "<textarea name=\"nota_medica\" rows=\"7\" style=\"width:100%\" class=\"textarea\">".$_REQUEST['nota_medica']."</textarea>";
          $this->salida .= "</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">";
          $this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"nota_medica_boton\" value=\"INSERTAR\">";
          $this->salida .= "</td>";
          
          $this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">";
          $this->salida .= "<input type=\"radio\" name=\"monitorizar\" value=\"1\"><b>Monitorizar Ingreso<b>&nbsp;&nbsp;&nbsp;";
          $this->salida .= "<input type=\"radio\" name=\"monitorizar\" value=\"2\"><b>Monitorizar Evolucion<b>";
          $this->salida .= "</td>";

          $this->salida .= "</tr>";
          $this->salida .= "</table>";*/
          /*INSERTAR NOTA MEDICA*/
          /*$this->salida .= "</form>";
                              
          $actionM=ModuloGetURL('app','RespuestasAuditor','user','FormaMenus');
          $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
          $this->salida .= "<br><table align=\"center\"><tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
          $this->salida .= "</td></tr></table></form>";*/

          $this->salida.=ThemeCerrarTabla();
          return true;
     }
	
	
     function RetornarBarra1()
     {
          if($this->limit>=$this->conteo)
          {
               return '';
		}
          
          $paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		$vec='';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
			{   $vec[$v]=$v1;   }
		}
		
		$accion=ModuloGetURL('app','RespuestasAuditor','user','BuscarOrden',$vec);
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">P?ginas</td>";
		if($paso > 1){
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
      		$diferencia=$numpasos-9;
			if($diferencia<0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$colspan++;
			}
		}
      	if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>P?gina&nbsp; $paso de $numpasos</td><tr></table>";
			//$this->salida.="</table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>P?gina&nbsp; $paso de $numpasos</td><tr></table>";
		
		}
    
	}
	//fin de las funciones para la barra de segnentacion


     /**
     *
     */
     function CalcularNumeroPasos($conteo){
          $numpaso=ceil($conteo/$this->limit);
          return $numpaso;
     }
     
     function CalcularBarra($paso){
          $barra=floor($paso/10)*10;
          if(($paso%10)==0){
               $barra=$barra-10;
     	}
     	return $barra;
     }
     
     function CalcularOffset($paso){
          $offset=($paso*$this->limit)-$this->limit;
          return $offset;
     }
     
     function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				return  ceil($date[2])." - ".ceil($date[1])." - ".ceil($date[0]);
		}
	}


//----------------------------------------------------------------------------------------------------

}//fin clase

?>

