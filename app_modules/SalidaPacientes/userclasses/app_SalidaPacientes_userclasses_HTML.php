<?php

/**
 * $Id: app_SalidaPacientes_userclasses_HTML.php,v 1.18 2006/08/25 13:25:39 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_SalidaPacientes_userclasses_HTML extends app_SalidaPacientes_user
{
	/**
	*Constructor de la clase app_Autorizacion_user_HTML
	*El constructor de la clase app_Autorizacion_user_HTML se encarga de llamar
	*a la clase app_Autorizacion_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_SalidaPacientes_user_HTML()
	{
				$this->salida='';
				$this->app_SalidaPacientes_user();
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
	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}

				return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
 }

	/**
	* Forma del menu
	* @access private
	* @return boolean
	*/
	function FormaMenus()
	{
          IncludeLib("funciones_admision");
          unset($_SESSION['SPY']);
          $this->salida .= ThemeAbrirTabla('MENU SALIDAS PACIENTES');
          $this->salida .= "			      <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "				       <tr>";
          $this->salida .= "				          <td align=\"center\" class=\"modulo_table_list_title\">MENU SALIDAS PACIENTES</td>";
          $this->salida .= "				       </tr>";
          $this->salida .= "				       <tr>";
          $urg=sizeof(PacienteSalidaUrgencias($_SESSION['SALIDA']['EMPRESA'],'','','','','',$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU']));
          $accionB=ModuloGetURL('app','SalidaPacientes','user','LlamarBuscarPaciente',array('tipo_salida'=>'URG'));
          $this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionB\" class=\"LABEL\">SALIDA PACIENTES CONSULTA URGENCIAS [ $urg ]</a></td>";
          $this->salida .= "				       </tr>";
          $this->salida .= "				       <tr>";
          $hos=sizeof(PacienteSalidaEstacion($_SESSION['SALIDA']['EMPRESA'],'','','','','',$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU']));
          $accionM=ModuloGetURL('app','SalidaPacientes','user','LlamarBuscarPaciente',array('tipo_salida'=>'HOS'));
          $this->salida .= "				          <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionM\" class=\"LABEL\">SALIDA PACIENTES HOSPITALIZACION [ $hos ]</a></td>";
          $this->salida .= "				       </tr>";
          $this->salida .= "				       <tr>";
          $cir=sizeof(PacienteSalidaCirugia($_SESSION['SALIDA']['EMPRESA'],'','','','','',$_SESSION['SALIDA']['CENTROUTILIDAD'],$_SESSION['SALIDA']['CU']));
          $accionI=ModuloGetURL('app','SalidaPacientes','user','LlamarBuscarPaciente',array('tipo_salida'=>'CIR'));
          $this->salida .= "				          <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionI\" class=\"LABEL\">SALIDA PACIENTES DE CIRUGIA [ $cir ]</a></td>";
          $this->salida .= "				       </tr>";
          $this->salida .= "			     </table>";
          $accion=ModuloGetURL('app','SalidaPacientes','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
    }
    /**
    *
    */
    function FormaBuscar($arr,$tipoPac)
    {
      IncludeLib("datospaciente");
      IncludeLib("funciones_admision");
      $action=ModuloGetURL('app','SalidaPacientes','user','BuscarPacienteSalida');
      $this->salida .= ThemeAbrirTabla('SALIDA PACIENTES '.$_SESSION['SALIDA']['TITULO'].' - BUSCAR PACIENTE');
      $this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
      $this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "				       <tr><td  class=\"".$this->SetStyle("Tipo")."\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
      $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
      $tipos=TiposIdPacientes();
          for($i=0; $i<sizeof($tipos); $i++)
          {
               if($tipos[$i][tipo_id_paciente]==$_REQUEST['Tipo'])
               {  $this->salida .=" <option value=\"".$tipos[$i][tipo_id_paciente]."\" selected>".$tipos[$i][descripcion]."</option>";  }
               else
               {  $this->salida .=" <option value=\"".$tipos[$i][tipo_id_paciente]."\">".$tipos[$i][descripcion]."</option>";  }
          }
          $this->salida .= "              </select></td></tr>";
          $this->salida .= "				       <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
          $this->salida .= "				       <tr><td class=\"".$this->SetStyle("nombre")."\">NOMBRES: </td><td><input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"".$_REQUEST['nombre']."\"></td></tr>";
          $campo=BuscarCamposObligatoriosPacientes();
          if($campo[historia_prefijo][sw_mostrar]==1)
          {
               $this->salida .= "    <tr height=\"20\">";
               $this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";
               $this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"".$_REQUEST['prefijo']."\" class=\"input-text\"></td>";
               $this->salida .= "      <td></td>";
               $this->salida .= "    </tr>";
          }
          if($campo[historia_numero][sw_mostrar]==1)
          {
               $this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";
               $this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"".$_REQUEST['historia']."\" class=\"input-text\"></td>";
               $this->salida .= "      <td></td>";
               $this->salida .= "    </tr>";
          }
          $this->salida .= "				       <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
          $actionM=ModuloGetURL('app','SalidaPacientes','user','FormaMenus');
          $this->salida .= "<td align=\"center\"><form name=\"enter\" action=\"$actionM\" method=\"post\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
          $this->salida .= "			     </table>";

          //salida de pacientes
          if(!empty($arr))
          {    $this->FormaSalidas($arr,$tipoPac);  }
          $this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	*
	*/
	function FormaSalidas($arr,$tipoPac)
	{
          IncludeLib("funciones_facturacion");
          IncludeLib("funciones_admision");			
          IncludeLib('validacion_salida');
          unset($_SESSION['SALIDAPACIENTES']);
          $reporte= new GetReports();
          
          $action = ModuloGetURL('app','SalidaPacientes','user','ImprimirsalidaPaciente');
          
          $this->salida .= "<script>\n";
          $this->salida .= "  function ImprimirPost(cadena)\n";
          $this->salida .= "  {\n";
          $this->salida .= "    var width = 400;\n";
          $this->salida .= "    var height = 300;\n";
          $this->salida .= "    var winX = Math.round(screen.width/2)-(width/2);\n";
          $this->salida .= "    var winY = Math.round(screen.height/2)-(height/2);\n";
          $this->salida .= "    var nombre = \"Printer_Mananger\";\n";
          $this->salida .= "    var str = \"width=400,height=300,left=\"+winX+\",top=\"+winY+\",location=yes,resizable=no,status=no,scrollbars=yes\";\n";
          $this->salida .= "    url = \"".$action."\"+cadena;\n";
          $this->salida .= "    window.open(url, nombre, str).focus();\n";
          $this->salida .= "  }\n";
          $this->salida .= "</script>\n";
          $this->salida .= "			    <br><table width=\"90%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "				       <tr align=\"center\" class=\"modulo_table_list_title\">";
          $this->salida .= "				         <td width=\"15%\">IDENTIFICACION</td>";
          $this->salida .= "				         <td width=\"20%\">PACIENTE</td>";
          $this->salida .= "				         <td width=\"65%\"></td>";
          $this->salida .= "				       </tr>";
          for($i=0; $i<sizeof($arr); $i++)
          {
               if( $i % 2){ $estilo='modulo_list_claro';}
               else {$estilo='modulo_list_oscuro';}
               $this->salida .= "				       <tr align=\"center\" class=\"$estilo\">";
               $this->salida .= "				         <td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
               $this->salida .= "				         <td>".$arr[$i][nombre]."</td>";
               $this->salida .= "				         <td>";
               //paciente  que esta en una estacion

               if($tipoPac==9)
               {
                    $this->salida .= "<table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                    $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
                    $msg='- PENDIENTE DE SALIDA';
                    $this->salida .= "<td colspan=\"2\" class=label_mark>EL PACIENTE ESTA EN LA ESTACION ".$arr[$i][descripcion]." ".$msg."</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "<tr class=\"$estilo\" align=\"left\">";
                    $accion=ModuloGetURL('app','SalidaPacientes','user','UbicacionPacienteEstacion',array('ingreso'=>$arr[$i][ingreso],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'nombre_estacion'=>$arr[$i][descripcion],'cama'=>$arr[$i][cama],'pieza'=>$arr[$i][pieza],'ubicacion'=>$arr[$i][ubicacion]));
                    $this->salida .= "<td><a href=\"$accion\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;  UBICACION DEL PACIENTE</a></td>";
                    $accion1=ModuloGetURL('app','SalidaPacientes','user','LlamarModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                    $this->salida .= "<td><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                    $this->salida .= "</tr>";
                    if(!empty($arr[$i][triage_id]))
                    {
                         $nivel=$this->NivelTriage($arr[$i][triage_id]);
                         if(!empty($nivel))
                         {
                              $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                              $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                              $this->salida .= "				         				 <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                              $col=1;
                              $this->salida .= "				       				 </tr>";
                         }
                    }

                    //-----------------------------------------------------------------
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $saldo=0;
                    $saldo=SaldoCuentaPaciente($arr[$i][numerodecuenta]);
                    if($saldo > 0)
                    {
                         $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/plata.png\" border='0'>&nbsp; TIENE UN SALDO DE $ ".FormatoValor($saldo)."</a></td>";
                    }
                    else
                    {
                         $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarImpresionSolicitudes',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'evolucion'=>$arr[$i][evolucion_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
                         $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  IMPRIMIR SOLICITUDES MEDICAS</a></td>";
                    }
                    if($arr[$i][estado]==='0')
                    {  $d='CERRAR';}
                    else
                    {  $d='VER';}
                    $accion=ModuloGetURL('app','SalidaPacientes','user','VerCuenta',array('cuenta'=>$arr[$i][numerodecuenta],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ingreso'=>$arr[$i][ingreso],'rango'=>$arr[$i][rango],'estado'=>$arr[$i][estado],'fecha'=>$arr[$i][fecha_registro]));
                    $this->salida .= "				         				 <td ><a href=\"$accion\"><img src=\"".GetThemePath()."/images/pcopagos.png\" border='0'>&nbsp; $d CUENTA</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                         
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $col=2;
                    if(!empty($arr[$i][triage_id]))
                    {
                         $nivel=$this->NivelTriage($arr[$i][triage_id]);
                         if(!empty($nivel))
                         {
                              $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                              $this->salida .= "				         				 <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                              $col=1;
                         }
                    }
                    $mostrar=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida .= "				         				 <td colspan=\"$col\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR HISTORIA CLINICA</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                    //remision
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $cols=2;
                    $rem=BuscarRemisionPaciente($arr[$i][ingreso]);
                    if(!empty($rem))
                    {
                         $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarRemisionMedica',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                         $this->salida .= "				         				 <td colspan=\"2\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/pparacar.png\" border='0'>&nbsp; REMISION</a></td>";
                         $cols=1;
                    }
                    $this->salida .= "				       				 </tr>";
                    //impresion epicrisis
                    $mostrar=$reporte->GetJavaReport_Epicrisis($arr[$i][ingreso],array());
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $this->salida .=$mostrar;
                    $this->salida .= "				         				 <td colspan=\"2\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR EPICRISIS</a></td>";
                    $this->salida .= "				       				 </tr>";		
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $salida='';
                    $salida=RevisarSalidaPaciente($arr[$i][ingreso]);
                    if(!is_array($salida))
                    {
                         $mostrar=$reporte->GetJavaReport('app','SalidaPacientes','salida',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]),array('rpt_name'=>'salida','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                         $funcion=$reporte->GetJavaFunction();
                         $this->salida .=$mostrar;
                         $this->salida.="  				 <td colspan=\"1\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR SALIDA PACIENTE</a></td>";
                         $accionS=ModuloGetURL('app','SalidaPacientes','user','LlamarFormaSalidaPaciente',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                         $this->salida.="  				 <td colspan=\"1\" class=\"label\"><a href=\"$accionS\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0'>&nbsp; DAR SALIDA AL PACIENTE</a></td>";
                    }
                    else
                    {
                         $this->salida.=" <td colspan=\"2\" class=\"label_error\">".$salida['mensaje']."</td>";
                    }
                    $this->salida .= " </tr>";
                    //-----------------------------------------------------------------

                    $this->salida .= "	</table>";
               }
               //PACIENTE ALTA DE URGENCIAS
               if($tipoPac==10)
               {
                    $msg='';
                    if($arr[$i][historia_clinica_tipo_cierre_id]==9)
                    {  $msg=' (REMISION MEDICA) ';  }
                    $this->salida .= "			               <table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"center\">";
                    $this->salida .= "				         				 <td colspan=\"2\" class=label_mark>EL PACIENTE DADO DE ALTA DE OBSERVACION DE URGENCIAS $msg</td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $accion=ModuloGetURL('app','SalidaPacientes','user','UbicacionPacienteEstacion',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'nombre_estacion'=>$arr[$i][descripcion],'cama'=>$arr[$i][cama],'pieza'=>$arr[$i][pieza],'ubicacion'=>$arr[$i][ubicacion]));
                    $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;  UBICACION DEL PACIENTE</a></td>";
                    $accion1=ModuloGetURL('app','SalidaPacientes','user','LlamarModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                    $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $saldo=0;
                    $saldo=SaldoCuentaPaciente($arr[$i][numerodecuenta]);
                    if($saldo > 0)
                    {
                         $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/plata.png\" border='0'>&nbsp; TIENE UN SALDO DE $ $saldo</a></td>";
                    }
                    else
                    {
                         $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarImpresionSolicitudes',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'evolucion'=>$arr[$i][evolucion_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
                         $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  IMPRIMIR SOLICITUDES MEDICAS</a></td>";
                    }
                    if($arr[$i][estado]==='0')
                    {  $d='CERRAR';}
                    else
                    {  $d='VER';}
                    $accion=ModuloGetURL('app','SalidaPacientes','user','VerCuenta',array('cuenta'=>$arr[$i][numerodecuenta],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ingreso'=>$arr[$i][ingreso],'rango'=>$arr[$i][rango],'estado'=>$arr[$i][estado],'fecha'=>$arr[$i][fecha_registro]));
                    $this->salida .= "				         				 <td><a href=\"$accion\"><img src=\"".GetThemePath()."/images/pcopagos.png\" border='0'>&nbsp; $d CUENTA</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                    //remision
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $cols=2;
                    if($arr[$i][historia_clinica_tipo_cierre_id]==9)
                    {
                                   $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarRemisionMedica',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                                   $this->salida .= "				         				 <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/pparacar.png\" border='0'>&nbsp; REMISION</a></td>";
                                   $cols=1;
                    }
                    $salida='';
                    $salida=RevisarSalidaPaciente($arr[$i][ingreso]);
                    if(!is_array($salida))
                    {
                         $accionS=ModuloGetURL('app','SalidaPacientes','user','LlamarFormaSalidaPaciente',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                         $this->salida.="  				 <td colspan=\"$cols\" class=\"label\"><a href=\"$accionS\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0'>&nbsp; DAR SALIDA AL PACIENTE</a></td>";
                    }
                    else
                    {
                         $this->salida.="  				 <td colspan=\"$cols\" class=\"label_error\">".$salida['mensaje']."</td>";
                    }
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $col=2;
                    if(!empty($arr[$i][triage_id]))
                    {
                         $nivel=$this->NivelTriage($arr[$i][triage_id]);
                         if(!empty($nivel))
                         {
                              $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                              $this->salida .= "				         				 <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                              $col=1;
                         }
                    }
                    $mostrar=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida .= "				         				 <td colspan=\"$col\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR HISTORIA CLINICA</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //impresion epicrisis
                    $mostrar=$reporte->GetJavaReport_Epicrisis($arr[$i][ingreso],array());
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $this->salida .=$mostrar;
                    $this->salida .= "				         				 <td colspan=\"2\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR EPICRISIS</a></td>";
                    $this->salida .= "				       				 </tr>";							
                    //salida paciente
                    if(!is_array($salida))
                    {
                      //$action=ModuloGetURL('app','SalidaPacientes','user','ImprimirsalidaPaciente',);
                      $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                      //$mostrar=$reporte->GetJavaReport('app','SalidaPacientes','salida',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]),array('rpt_name'=>'salida','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                      //$funcion=$reporte->GetJavaFunction();
                      //$this->salida .=$mostrar;
                      $this->salida .= "  				 <td colspan=\"2\">\n";
                      //$this->salida .= "            <a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR SALIDA PACIENTE</a></td>";
                      $this->salida .= "            <a href=\"javascript:ImprimirPost('".URLRequest(array("datos_reporte"=>array('cuenta'=>$arr[$i][numerodecuenta],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ingreso'=>$arr[$i][ingreso],'rango'=>$arr[$i][rango],'estado'=>$arr[$i][estado],'fecha'=>$arr[$i][fecha_registro])))."')\">\n";
                      $this->salida .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
                      $this->salida .= "              &nbsp; IMPRIMIR SALIDA PACIENTE\n";
                      $this->salida .= "            </a>\n";
                      $this->salida .= "          </td>\n";
                      $this->salida .= "        </tr>\n";
                    }
                    //-----------------------------------------------------------------
                    $this->salida .= "				             </table>";
               }
               
               //PACIENTE ALTA DE CIRUGIA
               if($tipoPac==11)
               {
                    $this->salida .= "<table width=\"100%\" border=\"0\" align=\"left\"  class=\"modulo_table_list_title\" cellspacing=\"3\" cellpadding=\"3\">";
                    $this->salida .= "<tr class=\"$estilo\" align=\"center\">";
                    $msg='- PENDIENTE DE SALIDA';
                    $this->salida .= "<td colspan=\"2\" class=label_mark>EL PACIENTE ESTA EN LA ESTACION ".$arr[$i][descripcion]." ".$msg."</td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "<tr class=\"$estilo\" align=\"left\">";
                    $accion=ModuloGetURL('app','SalidaPacientes','user','UbicacionPacienteEstacion',array('ingreso'=>$arr[$i][ingreso],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'nombre_estacion'=>$arr[$i][descripcion],'cama'=>$arr[$i][cama],'pieza'=>$arr[$i][pieza],'ubicacion'=>$arr[$i][ubicacion]));
                    $this->salida .= "<td><a href=\"$accion\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;  UBICACION DEL PACIENTE</a></td>";
                    $accion1=ModuloGetURL('app','SalidaPacientes','user','LlamarModificarDatosPaciente',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                    $this->salida .= "<td><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0'>&nbsp; MODIFICAR DATOS</a></td>";
                    $this->salida .= "</tr>";
                    if(!empty($arr[$i][triage_id]))
                    {
                         $nivel=$this->NivelTriage($arr[$i][triage_id]);
                         if(!empty($nivel))
                         {
                              $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                              $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                              $this->salida .= "				         				 <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                              $col=1;
                              $this->salida .= "				       				 </tr>";
                         }
                    }

                    //-----------------------------------------------------------------
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $saldo=0;
                    $saldo=SaldoCuentaPaciente($arr[$i][numerodecuenta]);
                    if($saldo > 0)
                    {
                         $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion3\"><img src=\"".GetThemePath()."/images/plata.png\" border='0'>&nbsp; TIENE UN SALDO DE $ ".FormatoValor($saldo)."</a></td>";
                    }
                    else
                    {
                         $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarImpresionSolicitudes',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'evolucion'=>$arr[$i][evolucion_id],'ingreso'=>$arr[$i][ingreso],'nombre'=>$arr[$i][nombre]));
                         $this->salida .= "				         				 <td width=\"50%\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  IMPRIMIR SOLICITUDES MEDICAS</a></td>";
                    }
                    if($arr[$i][estado]==='0')
                    {  $d='CERRAR';}
                    else
                    {  $d='VER';}
                    $accion=ModuloGetURL('app','SalidaPacientes','user','VerCuenta',array('cuenta'=>$arr[$i][numerodecuenta],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'plan'=>$arr[$i][plan_id],'ingreso'=>$arr[$i][ingreso],'rango'=>$arr[$i][rango],'estado'=>$arr[$i][estado],'fecha'=>$arr[$i][fecha_registro]));
                    $this->salida .= "				         				 <td ><a href=\"$accion\"><img src=\"".GetThemePath()."/images/pcopagos.png\" border='0'>&nbsp; $d CUENTA</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                         
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $col=2;
                    if(!empty($arr[$i][triage_id]))
                    {
                         $nivel=$this->NivelTriage($arr[$i][triage_id]);
                         if(!empty($nivel))
                         {
                              $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarConsultaTriage',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]));
                              $this->salida .= "				         				 <td><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/especialidad.png\" border='0'>&nbsp; CONSULTAR TRIAGE</a></td>";
                              $col=1;
                         }
                    }
                    $mostrar=$reporte->GetJavaReport_HC($arr[$i][ingreso],array());
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .=$mostrar;
                    $this->salida .= "				         				 <td colspan=\"$col\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR HISTORIA CLINICA</a></td>";
                    $this->salida .= "				       				 </tr>";
                    //-----------------------------------------------------------------
                    //remision
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $cols=2;
                    $rem=BuscarRemisionPaciente($arr[$i][ingreso]);
                    if(!empty($rem))
                    {
                         $accion2=ModuloGetURL('app','SalidaPacientes','user','LlamarRemisionMedica',array('triage'=>$arr[$i][triage_id],'tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                         $this->salida .= "				         				 <td colspan=\"2\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/pparacar.png\" border='0'>&nbsp; REMISION</a></td>";
                         $cols=1;
                    }
                    $this->salida .= "				       				 </tr>";
                    //impresion epicrisis
                    $mostrar=$reporte->GetJavaReport_Epicrisis($arr[$i][ingreso],array());
                    $funcion=$reporte->GetJavaFunction();
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $this->salida .=$mostrar;
                    $this->salida .= "				         				 <td colspan=\"2\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR EPICRISIS</a></td>";
                    $this->salida .= "				       				 </tr>";		
                    $this->salida .= "				               <tr class=\"$estilo\" align=\"left\">";
                    $salida='';
                    $salida=RevisarSalidaPaciente($arr[$i][ingreso]);
                    if(!is_array($salida))
                    {
                         $mostrar=$reporte->GetJavaReport('app','SalidaPacientes','salida',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre]),array('rpt_name'=>'salida','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                         $funcion=$reporte->GetJavaFunction();
                         $this->salida .=$mostrar;
                         $this->salida.="  				 <td colspan=\"1\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp; IMPRIMIR SALIDA PACIENTE</a></td>";
                         $accionS=ModuloGetURL('app','SalidaPacientes','user','LlamarFormaSalidaPaciente',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre],'ingreso'=>$arr[$i][ingreso]));
                         $this->salida.="  				 <td colspan=\"1\" class=\"label\"><a href=\"$accionS\"><img src=\"".GetThemePath()."/images/checksi.png\" border='0'>&nbsp; DAR SALIDA AL PACIENTE</a></td>";
                    }
                    else
                    {
                         $this->salida.=" <td colspan=\"2\" class=\"label_error\">".$salida['mensaje']."</td>";
                    }
                    $this->salida .= " </tr>";
                    //-----------------------------------------------------------------

                    $this->salida .= "	</table>";
               }

               $this->salida .= "				         </td>";
               $this->salida .= "				       </tr>";
          }
          $this->salida .= "				      </table>";
          $this->conteo=$_SESSION['SPY'];
          $this->salida .=$this->RetornarBarra();
          unset($reporte);
	}


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

	function RetornarBarra()
	{
    if($this->limit>=$this->conteo){
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
    $accion=ModuloGetURL('app','SalidaPacientes','user','BuscarPacienteSalida',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
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
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P?gina $paso de $numpasos</td><tr></table><br>";
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
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P?gina $paso de $numpasos</td><tr></table><br>";
    }
	}


	/**
	*
	*/
	function FormaUbicacionEstacion($paciente,$tipoid,$nombre,$nombre_estacion,$ingreso)
	{
				IncludeLib('funciones_facturacion');
				$dat=BuscarUbicacionPaciente($ingreso);
				$this->salida .= ThemeAbrirTabla('UBICACION PACIENTE ESTACION');
				$this->salida .= "<table width=\"40%\" border=\"1\" align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "<tr class=\"modulo_table_list_title\">";
				$this->salida .= "<td  colspan=\"2\"><img src=\"".GetThemePath()."/images/servicios.png\" border='0'>&nbsp;&nbsp; UBICACION EN LA ESTACION</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td class=\"label\" width=\"33%\">IDENTIFICACION: </td>";
				$this->salida .= "<td class=\"label_mark\" align=\"left\">".$tipoid." ".$paciente."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td class=\"label\">PACIENTE: </td>";
				$this->salida .= "<td class=\"label_mark\" align=\"left\">".$nombre."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td class=\"label\">ESTACION ENFERMERIA: </td>";
				$this->salida .= "<td class=\"label_mark\" align=\"left\">".$nombre_estacion."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td class=\"label\">PIEZA: </td>";
				$this->salida .= "<td class=\"label_mark\" align=\"left\">".$dat['pieza']."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td class=\"label\">CAMA: </td>";
				$this->salida .= "<td class=\"label_mark\" align=\"left\">".$dat['cama']."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$this->salida .= "<td class=\"label\">UBICACION: </td>";
				$this->salida .= "<td class=\"label_mark\" align=\"left\">".$dat['ubicacion']."</td>";
				$this->salida .= "</tr>";
				$this->salida.="</table><br>";
				$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
				$this->salida .= "<tr>";
				$accion=ModuloGetURL('app','SalidaPacientes','user','BuscarPacienteSalida',array('TipoDocumento'=>$tipoid,'Documento'=>$paciente));
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></td>";
				$this->salida .= "</form>";
				$this->salida .= "</tr>";
				$this->salida .= " </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	function FormaSalidaPaciente($tipoid,$paciente,$nombre,$ingreso)
	{
				$this->salida .= ThemeAbrirTabla('SALIDA PACIENTE');
				$accion=ModuloGetURL('app','SalidaPacientes','user','DarSalidaPaciente',array('TipoDocumento'=>$tipoid,'Documento'=>$paciente,'ingreso'=>$ingreso));
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "<tr>";
				$this->salida .= "<td class=\"label_mark\" align=\"center\">ESTA SEGURO QUE EL  PACIENTE $tipoid $paciente $nombre <br>ESTA LISTO PARA SALIR DE LA INSTITUCION.</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td align=\"center\"><textarea cols=\"65\" rows=\"3\" class=\"textarea\"name=\"observacion\"></textarea></td>";
				$this->salida .= "</tr>";
				$this->salida.="</table><br>";
				$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
				$this->salida .= "<tr>";
				$this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"ACEPTAR\"></td>";
				$this->salida .= "</form>";
				$accion=ModuloGetURL('app','SalidaPacientes','user','BuscarPacienteSalida',array('TipoDocumento'=>$tipoid,'Documento'=>$paciente));
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "<td  align=\"center\"><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"CANCELAR\"></td>";
				$this->salida .= "</form>";
				$this->salida .= "</tr>";
				$this->salida .= " </table>";

				$this->salida .= ThemeCerrarTabla();
				return true;
	}
//----------------------------------------------------------------------------------------------------
    /**
		* Funcion principal del modulo
    *
    * @return boolean
		*/
		function ImprimirsalidaPaciente()
		{
      $request = $_REQUEST;
      
      IncludeFile("classes/reports/reports.class.php");
      $classReport = new reports;
      $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
      $reporte=$classReport->PrintReport("pos","app","SalidaPacientes","salida",$request['datos_reporte'],$impresora,$orientacion='',$unidades='',$formato='',$html=1);
      if(!$reporte)
      {
        $this->error = $classReport->GetError();
        $this->mensajeDeError = $classReport->MensajeDeError();
        unset($classReport);
        
        $html  = ThemeAbrirTabla('MENSAJE');
  			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
  			$html .= "	<tr>\n";
  			$html .= "		<td>\n";
  			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
  			$html .= "		    <tr class=\"normal_10AN\">\n";
  			$html .= "		      <td align=\"center\">\n".$this->mensajeDeError."</td>\n";
  			$html .= "		    </tr>\n";
  			$html .= "		  </table>\n";
  			$html .= "		</td>\n";
  			$html .= "	</tr>\n";
  			$html .= "	<tr>\n";
  			$html .= "		<td align=\"center\"><br>\n";
  			$html .= "			<form name=\"form\" action=\"javascript:window.close()\" method=\"post\">";
  			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
  			$html .= "			</form>";
  			$html .= "		</td>";
  			$html .= "	</tr>";
  			$html .= "</table>";
  			$html .= ThemeCerrarTabla();			
      }
      else
      {
        $resultado=$classReport->GetExecResultado();
        $html .= "<script>\n";
        $html .= "  window.close();";
        $html .= "</script>\n";
      }
      
      $this->salida .= $html;
			return true;
		}
}//fin clase

?>