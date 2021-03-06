<?php
  /******************************************************************************
  * $Id: OpcionesCuentasHTML.class.php,v 1.12 2011/07/25 20:37:18 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.12 $ 
	* 
	* @autor
  ********************************************************************************/
  IncludeClass('Habitaciones','','app','Cuentas');
	IncludeClass('OpcionesCuentas','','app','Cuentas');
	IncludeClass('LiquidacionHabitacionesCorte');
	class OpcionesCuentasHTML
	{
		function OpcionesCuentasHTML(){}
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
		* 
		* @return array 
		*/
		function FormaOpcionesCuentas($PlanId,$Cuenta,$Ingreso,$Estado,$TipoId,$PacienteId)
		{
    /*
      0 =>	FACTURADA
      1 =>	ACTIVA
      2 =>	INACTIVA
      3 =>	CUADRADA
      4 =>	ANTICIPOS
      5 =>	ANULADA
    */
      if($Cuenta AND $Estado)
      {
        SessionSetVar('CuentaOp',$Cuenta);
        SessionSetVar('EstadoOp',$Estado);
      }
      else
      {
        $Cuenta = SessionGetVar('CuentaOp');
        $Estado = SessionGetVar('EstadoOp');
      }

      UNSET($_SESSION['CUENTAS']['ADD_CARGOS']);
      UNSET($_SESSION['TMP_DATOS']['Cuenta']);

      $html .= "<script>\n";    
      $html .= "function MostrarCambioResponsable(valor){\n";           
      $html .= "  if(valor==1){\n";

      $html .= "    document.getElementById('Visualizar').style.display = 'none';\n";
      $html .= "    document.getElementById('NoVisualizar').style.display = 'block';\n";
      $html .= "    document.getElementById('CambioResponsable').style.display = 'block';\n";
      $html .= "    var x;\n";      
      $html .= "  }\n";
      $html .= "  if(valor==2){\n";
      $html .= "    document.getElementById('Visualizar').style.display = 'block';\n";
      $html .= "    document.getElementById('NoVisualizar').style.display = 'none';\n";
      $html .= "    document.getElementById('CambioResponsable').style.display = 'none';\n";
      $html .= "  }\n";
      $html .= "}\n";
      $html .= "function InactivarCuenta(valor){\n";           
      $html .= "  if(valor==1){\n";
      $html .= "    document.getElementById('VisualizarInactiva').style.display = 'none';\n";
      $html .= "    document.getElementById('NoVisualizarInactiva').style.display = 'block';\n";
      $html .= "    document.getElementById('Inactivar').style.display = 'block';\n";
      $html .= "    var x;\n";      
      $html .= "  }\n";
      $html .= "  if(valor==2){\n";
      $html .= "    document.getElementById('VisualizarInactiva').style.display = 'block';\n";
      $html .= "    document.getElementById('NoVisualizarInactiva').style.display = 'none';\n";
      $html .= "    document.getElementById('Inactivar').style.display = 'none';\n";
      $html .= "  }\n";
      $html .= "}\n";
      //FechaCambio
      $html .= "function FechaCambio(){\n";           
      $html .= "    document.getElementById('FechaCambio').style.display = 'none';\n";
      //$html .= "    document.getElementById('NoVisualizarActivar').style.display = 'none';\n";
      $html .= "    document.getElementById('FechaCambA').style.display = 'block';\n";
      $html .= "}\n";
      $html .= "function ActivarCuenta(valor){\n";           
      $html .= "  if(valor==1){\n";
      $html .= "    document.getElementById('VisualizarActivar').style.display = 'none';\n";
      $html .= "    document.getElementById('NoVisualizarActivar').style.display = 'block';\n";
      $html .= "    document.getElementById('Activar').style.display = 'block';\n";
      $html .= "    var x;\n";      
      $html .= "  }\n";
      $html .= "  if(valor==2){\n";
      $html .= "    document.getElementById('VisualizarActivar').style.display = 'block';\n";
      $html .= "    document.getElementById('NoVisualizarActivar').style.display = 'none';\n";
      $html .= "    document.getElementById('Activar').style.display = 'none';\n";
      $html .= "  }\n";
      $html .= "}\n";
      $html .= "function ActivarReliquidar(valor){\n";           
      $html .= "  if(valor==1){\n";
      $html .= "    document.getElementById('VisualizarReliquidar').style.display = 'none';\n";
      $html .= "    document.getElementById('NoVisualizarReliquidar').style.display = 'block';\n";
      $html .= "    document.getElementById('Reliquidar').style.display = 'block';\n";
      $html .= "    var x;\n";      
      $html .= "  }\n";
      $html .= "  if(valor==2){\n";
      $html .= "    document.getElementById('VisualizarReliquidar').style.display = 'block';\n";
      $html .= "    document.getElementById('NoVisualizarReliquidar').style.display = 'none';\n";
      $html .= "    document.getElementById('Reliquidar').style.display = 'none';\n";
      $html .= "  }\n";
      $html .= "}\n";
      $html .= "function ActivarDividir1(valor){\n";           
      $html .= "  if(valor==1){\n";
      $html .= "    document.getElementById('VisualizarDividir1').style.display = 'none';\n";
      $html .= "    document.getElementById('NoVisualizarDividir1').style.display = 'block';\n";
      $html .= "    document.getElementById('Dividir1').style.display = 'block';\n";
      $html .= "    var x;\n";      
      $html .= "  }\n";
      $html .= "  if(valor==2){\n";
      $html .= "    document.getElementById('VisualizarDividir1').style.display = 'block';\n";
      $html .= "    document.getElementById('NoVisualizarDividir1').style.display = 'none';\n";
      $html .= "    document.getElementById('Dividir1').style.display = 'none';\n";
      $html .= "  }\n";
      $html .= "}\n";
      $html .= "function ActivarDividir(){\n";  
      $html .= "  if(document.getElementById('Dividir').style.display == 'block'){\n";
      $html .= "    document.getElementById('Dividir').style.display = 'none';\n";
      $html .= "  }else{\n";
      $html .= "    document.getElementById('Dividir').style.display = 'block';\n";
      $html .= " }\n";
      $html .= "}\n";
      
    
      $html .= " function validarFechaIngeso(frms,fecha_cambio)\n";
      $html .= " {\n";
      $html .= "   f = frms.fecha_ingreso.value.split('-')\n";
      $html .= "   f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0] + ' '+frms.text_hora_com.value+':'+frms.text_hora_com_min.value+':00'); \n";
      $html .= "   fecha = fecha_cambio.split(' ');";
      $html .= "   f = fecha[0].split('-');\n";
      $html .= "   f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]+' '+fecha[1]);\n";
      $html .= "   if(f1 >= f2 )\n";
      $html .= "   {\n";
      $html .= "      alert('LA FECHA SALIDA DEL PACIENTE ES MENOR A LA SELECCIONADA');\n";
      $html .= "      return;\n";
      $html .= "    } \n";
      //$html .= "    alert(hora);\n";
      //$html .= "    alert(minutos);\n";
      //$html .= "    alert(frms.text_hora_com.value);\n";
      /*$html .= "    if(frms.empresas.selectedIndex==0)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR UNA EMPRESA PARA REALIZAR LA DEVOLUCION';\n";
      $html .= "      return;\n";
      $html .= "    } \n";
      $html .= "    if(frms.empresas.selectedIndex!=-1)\n";
      $html .= "    {\n";
      $html .= "         xajax_TransEmpresaDestino(frms.empresas.value);";
      $html .= "      return;\n";
      $html .= "    }\n";*/
      $html .= "   frms.submit();\n";
      $html .= " }\n";
      $html .= " function validarInactivarCuen(frms,InactivarCuentaP,SolicMedic,SolicDevol,PacienUrgen,MovimHabit)\n";
      $html .= " {\n";
      $html .= "  if(PacienUrgen> '0' | MovimHabit> '0')\n";
      $html .= "  {\n";
      $html .= "   if(InactivarCuentaP== '0')\n";
      $html .= "   {\n";
      $html .= "      alert('FALTA VISTO BUENO DE LA ESTACION DE ENFERMERIA');\n";
      $html .= "      return;\n";
      $html .= "    } \n";
      $html .= "   if(SolicMedic>'0')\n";
      $html .= "   {\n";
      $html .= "      alert('HAY SOLICITUDES DE INSUMOS O MEDICAMENTOS PENDIENTES POR CONFIRMAR');\n";
      $html .= "      return;\n";
      $html .= "    } \n";
      $html .= "   if(SolicDevol>'0')\n";
      $html .= "   {\n";
      $html .= "      alert('HAY DEVOLUCIONES  DE INSUMOS O MEDICAMENTOS PENDIENTES POR CONFIRMAR');\n";
      $html .= "      return;\n";
      $html .= "    } \n";
       $html .= "}\n";  
      $html .= "   document.formabuscar_3.submit();\n";
      $html .= " }\n";  
      $html .="  </script>\n";
      
      $html .= "</script>\n";    

      $html .= "<table width=\"100%\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr align=\"center\">\n";
      $html .= "    <td width=\"50%\" valign=\"top\">\n";
      $html .= "      <table width=\"50%\" border=\"0\" align=\"right\">\n";
      //CAMBIO RESPONSABLE
      $html .= "        <tr align=\"center\">\n";
      $html .= "          <td class=\"label_mark\">\n";
      $html .= "            <div id=\"Visualizar\">\n";
      $html .= "              <a href=\"javascript:MostrarCambioResponsable(1);\">CAMBIO RESPONSABLE</a>\n";
      $html .= "            </div>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr align=\"center\">\n";
      $html .= "          <td class=\"label_mark\">\n";
      $html .= "            <div id=\"NoVisualizar\" style=\"display:none\">\n";
      $html .= "              <a href=\"javascript:MostrarCambioResponsable(2);\">CAMBIO RESPONSABLE</a>\n";
      $html .= "            </div>\n";
      $html .= "          </td>\n";
      $html .= "        </tr>\n";
      $html .= "        <tr align=\"center\"><td class=\"modulo_table_list\">"; 
      $html .= "        <div id='CambioResponsable' style=\"display:none\">";
      $action=ModuloGetURL('app','Cuentas','user','LlamaNuevoResponsable',array('Cuenta'=>$Cuenta,'Ingreso'=>$Ingreso,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $html .= "            <table width=\"30%\" align=\"center\" border=\"0\">";
      $html .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
      $html .= $this->SetStyle("MensajeError");
      $html .= "               <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
      $responsables = $this->Llamaresponsables();
      $html .= $this->MostrarResponsable($responsables,$PlanId);
      $html .= "              </select></td></tr>";
      $html .= "               <tr><td align=\"center\" colspan=\"2\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td>";
      $html .= "           </form>";
/*								$actionM=ModuloGetURL('app','Cuentas','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $html .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
      $html .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td></tr>";
      $html .= "           </form>";*/
      $html .= "           </table>";
      $html .= "      </div>";
      $html .= "        </td></tr>";
      //FIN CAMBIO RESPONSABLE
      if($Estado=='1')//1 =>	ACTIVA
      {
          $msg='Esta seguro que desea Inactivar la Cuenta No. '.$Cuenta;
          $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
          //$accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'InactivarCuenta','mensaje'=>$msg,'titulo'=>'INACTIVAR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
          //$html .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
          //$html .= "  <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accionEstado\">INACTIVAR CUENTA</a></td></tr>";
          $html .= "      <tr align=\"center\"><td class=\"label_mark\"><div id=\"VisualizarInactiva\"><a href=\"javascript:InactivarCuenta(1);\">INACTIVAR CUENTA</a></div></td></tr>";
          $html .= "       <tr align=\"center\"><td class=\"label_mark\"><div id=\"NoVisualizarInactiva\" style=\"display:none\"><a href=\"javascript:InactivarCuenta(2);\">INACTIVAR CUENTA</div></a></td></tr>";
          //Inactivar
          $InactivarCuentaP = $this->LlamaBuscarInactivarCuentaP($Cuenta);
          $SolicMedic = $this->LlamaBuscarSolicMedic($Cuenta);
          $SolicDevol = $this->LlamaBuscarSolicDevol($Cuenta);
          $PacienUrgen = $this->LlamaBuscarPacienUrgen($Cuenta);
          $MovimHabit = $this->LlamaBuscarMovimHabit($Cuenta);
          $html .= "  <tr align=\"center\"><td class=\"modulo_table_list\">"; 
          $html .= "      <div id='Inactivar' style=\"display:none\">";
          $html .= "    <table width=\"60%\" align=\"center\">";
          $html .= "      <tr><td colspan=\"2\" class=\"label\" align=\"center\">$msg<br><br></td></tr><br><br>";
          $html .= "        <tr>";
          $accion1=ModuloGetURL('app','Cuentas','user','LlamaInactivarCuenta',array("Cuenta"=>$Cuenta));
          $html .= "     <form name=\"formabuscar_3\" action=\"$accion1\" method=\"post\">";
          $html .= "     <td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Boton1\" value=\"ACEPTAR\" onclick=\"validarInactivarCuen(document.formabuscar_3,'".$InactivarCuentaP[0]['vistosali']."','".$SolicMedic[0]['solicimed']."','".$SolicDevol[0]['solidevo']."','".$PacienUrgen[0]['pacienurge']."','".$MovimHabit[0]['movimhabit']."')\">";
          $html .= "      </td>";
          $html .= "    </form>";
          //$html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"ACEPTAR\"></form></td>";
          $html .= "        <tr>";
          $html .= "    </table>";
          $html .= "      </div>";
          $html .= "   </td></tr>";
          //
          //$html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"INACTIVAR CUENTA\"></td>";
          //$html .= "    </form>";
      }
      if($Estado=='2')//2 =>	INACTIVA
      {
          $msg = $this->LlamaBuscarCuentaParaActivar($Cuenta,$TipoId,$PacienteId);
          $mensaje = explode('/**/',$msg);
          $html .= "      <tr align=\"center\"><td class=\"label_mark\"><div id=\"VisualizarActivar\"><a href=\"javascript:ActivarCuenta(1);\">ACTIVAR CUENTA</a></div></td></tr>";
          $html .= "       <tr align=\"center\"><td class=\"label_mark\"><div id=\"NoVisualizarActivar\" style=\"display:none\"><a href=\"javascript:ActivarCuenta(2);\">ACTIVAR CUENTA</div></a></td></tr>";
          //activar
          $html .= "  <tr align=\"center\"><td class=\"modulo_table_list\">"; 
          $html .= "      <div id='Activar' style=\"display:none\">";
          $html .= "    <table width=\"60%\" align=\"center\">";
          $html .= "      <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje[0]<br><br></td></tr><br><br>";

          
          $html .= "        <tr>";
          if($mensaje[1]==1)
          {
            //$html .= "<pre>".print_r($MovimHabit[0],true)."</pre>";
            $accion1=ModuloGetURL('app','Cuentas','user','LlamaOpcionActivarCuenta',array("Cuenta"=>$Cuenta));
            $html .= "    <form name=\"formabuscar_2\" action=\"$accion1\" method=\"post\">";
            $html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"ACEPTARd\"></form></td>";
            $html .= "      </td>";
            $html .= "    </form>";
   
          }
          $html .= "        <tr>";
          $html .= "    </table>";
          $html .= "      </div>";
          $html .= "   </td></tr>";
          //
      }

      //$accion=ModuloGetURL('app','Facturacion','user','FormaMenuReliquidar',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      //$html .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
      //$html .= "  <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accion\">RELIQUIDAR</a></td></tr>";
      $html .= "      <tr align=\"center\"><td class=\"label_mark\"><div id=\"VisualizarReliquidar\"><a href=\"javascript:ActivarReliquidar(1);\">RELIQUIDAR</a></div></td></tr>";
      $html .= "       <tr align=\"center\"><td class=\"label_mark\"><div id=\"NoVisualizarReliquidar\" style=\"display:none\"><a href=\"javascript:ActivarReliquidar(2);\">RELIQUIDAR</div></a></td></tr>";
      //OPCIONES RELIQUIDAR CUENTA
      $html .= "  <tr align=\"center\"><td class=\"modulo_table_list\">"; 
      $html .= "      <div id='Reliquidar' style=\"display:none\">";
      $html .= "            <table width=\"60%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
      $html .= "               <tr>";
      $html .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU RELIQUIDACIONES</td>";
      $html .= "               </tr>";
      $html .= "               <tr>";
      $mensaje='Esta seguro que desea Reliquidar los cargos de Insumos y Medicamentos de la Cuenta No. '.$Cuenta;
      $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
      $accion=ModuloGetURL('app','Cuentas','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Cuentas','me2'=>'FormaMostrarCuenta','me'=>'LlamaReliquidarMedicamentos','mensaje'=>$mensaje,'titulo'=>'RELIQUIDAR CARGOS DE MEDICAMENTOS DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
      $html .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accion\">Reliquidar Insumos y Medicamentos</a></td>";
      $html .= "               </tr>";
      $html .= "               <tr>";
      $msg='Esta seguro que desea Reliquidar la Cuenta No. '.$Cuenta;
      $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
      $accion=ModuloGetURL('app','Cuentas','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Cuentas','me2'=>'FormaMostrarCuenta','me'=>'LlamaReliquidarCargos','mensaje'=>$msg,'titulo'=>'RELIQUIDAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
      $html .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Reliquidar Cargos</a></td>";
      $html .= "               </tr>";

      $html .= "               <tr>";
      $msg='Esta seguro que desea Reliquidar la Cuenta No. '.$Cuenta;
      $arreglo=array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
      $accion=ModuloGetURL('app','Cuentas','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Cuentas','me2'=>'FormaMostrarCuenta','me'=>'LlamaReliquidar','mensaje'=>$msg,'titulo'=>'RELIQUIDAR CUENTA No. '.$Cuenta,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
      $html .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accion\">Reliquidar Cuenta</a></td>";
      $html .= "               </tr>";
      $html .= "           </table>";
      $html .= "      </div>";
      $html .= "   </td></tr>";
      //FIN OPCIONES RELIQUIDAR CUENTA

      if($Estado=='1')//1 =>	ACTIVA
      {
        $msg='Esta Seguro que desea dividir la Cuenta No. '.$Cuenta.'.';
        $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
        //$accionEstado=ModuloGetURL('app','Cuentas','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Cuentas','me2'=>'FormaMostrarCuenta','me'=>'LlamaTiposDivision','mensaje'=>$msg,'titulo'=>'DIVIDIR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
        //$accionEstado=ModuloGetURL('app','Facturacion','user','ConfirmarAccion',array('c'=>'app','m'=>'Facturacion','me2'=>'Cuenta','me'=>'TiposDivision','mensaje'=>$msg,'titulo'=>'DIVIDIR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
        //$html .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
        //$html .= "  <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accionEstado\">DIVIDIR CUENTA</a></td></tr>";
        //$html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"DIVIDIR CUENTA\"></td>";
          $html .= "      <tr align=\"center\"><td class=\"label_mark\"><div id=\"VisualizarDividir1\"><a href=\"javascript:ActivarDividir1(1);\">DIVIDIR CUENTA</a></div></td></tr>";
          $html .= "       <tr align=\"center\"><td class=\"label_mark\"><div id=\"NoVisualizarDividir1\" style=\"display:none\"><a href=\"javascript:ActivarDividir1(2);\">DIVIDIR CUENTA</div></a></td></tr>";
        //$html .= "    </form>";
          //DIVIDIR
          $html .= "  <tr align=\"center\"><td class=\"modulo_table_list\">"; 
          $html .= "      <div id='Dividir1' style=\"display:none\">";
          $html .= "    <table width=\"60%\" align=\"center\">";
          $html .= "      <tr><td colspan=\"2\" class=\"label\" align=\"center\">$msg<br><br></td></tr><br><br>";
          $html .= "        <tr>";
          $accion1=ModuloGetURL('app','Cuentas','user','LlamaTiposDivision',array("arreglo"=>$arreglo));
          $html .= "     <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
          $html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"ACEPTAR\"></form></td>";
          $html .= "        <tr>";
          $html .= "    </table>";
          $html .= "      </div>";
          $html .= "   </td></tr>";
          //FIN DIVIDIR
      }
      else
      {
        //$accionEstado=ModuloGetURL('app','Cuentas','user','LlamaTiposDivision',array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
        //$html .= "    <form name=\"formaborrar\" action=\"$accionEstado\" method=\"post\">";
        //$html .= "  <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accionEstado\">DIVIDIR CUENTA</a></td></tr>";
        $html .= "       <tr align=\"center\"><td class=\"label_mark\"><a href=\"javascript:ActivarDividir();\">DIVIDIR CUENTA</a></td></tr>";
        //$html .= "    </form>";
        //DIVIDIR
          $msg='Esta Seguro que desea dividir la Cuenta No. '.$Cuenta.'.';
          $html .= "  <tr align=\"center\"><td class=\"modulo_table_list\">"; 
          $html .= "      <div id='Dividir' style=\"display:none\">";
          $html .= "    <table width=\"60%\" align=\"center\">";
          $html .= "      <tr><td colspan=\"2\" class=\"label\" align=\"center\">$msg<br><br></td></tr><br><br>";
          $html .= "        <tr>";
          $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
          $accion1=ModuloGetURL('app','Cuentas','user','LlamaTiposDivision',array("arreglo"=>$arreglo));
          $html .= "     <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
          $html .= "         <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Boton1\" value=\"ACEPTAR\"></form></td>";
          $html .= "        <tr>";
          $html .= "    </table>";
          $html .= "      </div>";
          $html .= "   </td></tr>";
          //FIN DIVIDIR
      }
            
      $html .= "      </table>";
      $html .= "    </td>";
      $html .= "    <td width=\"50%\" valign=\"top\">";
      $html .= "      <table width=\"50%\" border=\"0\" align=\"left\">";
      //OPCION PAQUETES
      $accionPaq=ModuloGetURL('app','Cuentas','user','LlamaRealizarPaquetesCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId));
      //$accionPaq=ModuloGetURL('app','Facturacion','user','RealizarPaquetesCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
      $html .= "    <form name=\"formaPaq\" action=\"$accionPaq\" method=\"post\">";
      $html .= "  <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accionPaq\">PAQUETES</a></td></tr>";
      //$html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"PAQUETES\"></td>";
      $html .= "    </form>";
      //FIN OPCION PAQUETES
      
      //OPCI?N CORTE CUENTAS
      $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
      $accionEstado=ModuloGetURL('app','Cuentas','user','LlamaTiposCortes',array('CorteCuenta'=>'1','Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
      $html .= "      <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accionEstado\">CORTE CUENTA</a></td></tr>";
      //FIN OPCI?N CORTE CUENTAS
      
      //OPCI?N CORTE CUENTAS
      $arreglo=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado);
      $accionEstado=ModuloGetURL('app','Cuentas','user','LlamaTiposCortes',array('CorteCuenta'=>'1','Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Estado'=>$Estado));
      $html .= "      <tr align=\"center\">\n";
      $html .= "        <td class=\"label_mark\">\n";
      $html .= "          <div id=\"FechaCambio\">\n";
      $html .= "            <a href=\"javascript:FechaCambio();\">CAMBIO FECHA</a>\n";
      $html .= "          </div>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
    
      $fecha_cambio = $this->LlamaBuscarFechIngreso($Cuenta);
      $parametrizacion_fecha_cambio = $this->LlamaBuscarParametrizaCambioFe($Cuenta);
      $html .= "      <tr align=\"center\">";
      $html .= "        <td class=\"label_mark\">\n"; 
      $html .= "          <div id='FechaCambA' style=\"display:none\">\n";
      if($parametrizacion_fecha_cambio[0]['parame']>0)
      {
        $fecha_delcambio=explode(" ",$fecha_cambio[0][fecha_registro]);
        $fecha_CambioS=explode("-",$fecha_delcambio[0]);
        $total_fecha_CambioS=$fecha_CambioS[2]."-".$fecha_CambioS[1]."-".$fecha_CambioS[0];
        $hora_CambioS=explode(":",$fecha_delcambio[1]);
        $accion_fe=ModuloGetURL('app','Cuentas','user','LlamaOpcionFechaIngreso',array("Ingreso"=>$Ingreso));
        $html .= "          <form name=\"formabuscar1\" action=\"$accion_fe\" method=\"post\">";
        $html .= "          <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "            <tr align=\"center\">\n";
        $html .= "              <td colspan=\"3\" class=\"normal_10AN\">FECHA PACIENTE</td>\n";
        $html .= "            </tr>\n";
        $html .= "            <tr>\n";
        $html .= "              <td>\n";
        
        $html .= "		              <input type=\"text\" class=\"input-text\" name=\"fecha_ingreso\"  id=\"fecha_ingreso\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$total_fecha_CambioS."\"  >\n";
        $html .= "			            ".ReturnOpenCalendario('formabuscar1','fecha_ingreso','-')."\n";
        $html .= "              </td>\n";
        $html .= "              <td valign=\"top\">\n";
        $html .= "                  <select width=\"10%\" class=\"select\" name=\"text_hora_com\" id=\"text_hora_com\" onchange=\"MostrarDias()\">\n";
        for($x=1;$x<=24;$x++)
        {
            $m=str_pad($x, 2, "0", STR_PAD_LEFT);
            $html .= "      <option value=\"".$m."\"  ".( $hora_CambioS[0]==$m ? "selected=selected" : "" )."  >".$m."</option>\n";
         }
        $html .= "                  </select>\n";
        $html .= "              </td>\n";
        $html .= "              <td valign=\"top\">\n";
        $html .= "                  <select width=\"10%\" class=\"select\" name=\"text_hora_com_min\" id=\"text_hora_com_min\" onchange=\"MostrarDias()\">\n";
        for($x=1;$x<=60;$x++)
        {
           $m=str_pad($x, 2, "0", STR_PAD_LEFT);
           $html .= "       <option value=\"".$m."\" ".( $hora_CambioS[1]==$m ? "selected=selected" : "" )." >".$m."</option>\n";
        }
        $html .= "                  </select>\n";
        $html .= "               </td>";
        $html .= "             </tr>";
        $html .= "             <tr>";
        $html .= "               <td align=\"center\" colspan=\"3\">\n";
        $html .= "                 <input class=\"input-submit\" type=\"button\" name=\"Boton1\" value=\"ACEPTAR\" onclick=\"validarFechaIngeso(document.formabuscar1,'".$total_fecha_CambioS."')\">";
        $html .= "               </td>";
        $html .= "             </tr>";
        $html .= "           </table>";
        $html .= "           </form>";
      }
      else
      {
        $html .= " EL USUARIO NO TIENE PERMISOS PARA ESTA OPCION";
      }
      $html .= "          </div>";
      $html .= "         </td>";
      $html .= "       </tr>";
          
      $action = ModuloGetURL('app','Cuentas','user','DescuentosCuentas',array('Cuenta'=>$Cuenta));
      $html .= "       <tr>\n";
      $html .= "         <td align=\"center\">\n";
      $html .= "          <a href=\"".$action."\" class=\"label_error\">DESCUENTOS</a>\n";
      $html .= "         </td>\n";
      $html .= "       </tr>\n";
            //OPCION DESCUENTOS
/*								$accion=ModuloGetURL('app','Cuentas','user','LlamaCrearDescuentos',array('Cuenta'=>$Cuenta));
            $html .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
            $html .= "  <tr align=\"center\"><td class=\"label_mark\"><a href=\"$accion\">DESCUENTOS</a></td></tr>";
            //$html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"DESCUENTOS\"></td>";
            $html .= "    </form>";*/
            //OPCION DESCUENTOS
      //}
      $unificar = SessionGetVar("PermisoUnificacionCuenta");
      
      if($unificar == '1')
      {
        $opc = new OpcionesCuentas();
        $cuentas = $opc->ObtenerCuentasxIngreso($Ingreso,$Cuenta);
        if(!empty($cuentas))
        {
          $action = ModuloGetURL('app','Cuentas','user','UnificarCuenta',array('Cuenta'=>$Cuenta));
          $html .= "       <tr>\n";
          $html .= "         <td align=\"center\">\n";
          $html .= "          <a href=\"javascript:DesplegarUnificacionCuentas()\" class=\"label_error\">UNIFICAR CUENTAS</a>\n";
          $html .= "          <div id=\"divUnificacionCuentas\" class=\"modulo_table_list\" style=\"display:none\">\n";
          $html .= "            <form name=\"formUnificarCuenta\" action=\"".$action."\" method=\"post\">\n";
          $html .= "              <table align=\"center\" class=\"modulo_table_list\" width=\"100%\">\n";
          $html .= "                <tr class=\"formulacion_table_list\">\n";
          $html .= "                  <td colspan=\"6\">SELECCIONE LA CUENTA QUE DESEA UNIFICAR CON LA CUENTA ACTUAL</td>\n";
          $html .= "                </tr>\n";
          
          $i=$j=0;
          foreach($cuentas as $key => $dtl)
          {
            $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
            if($i%3 == 0)
            {
              if($i != 0) $html .= "                </tr>\n";
              $html .= "                <tr >\n";
              $j = 0;
            }
            $html .= "                  <td width=\"1%\" class=\"".$est."\">\n";
            $html .= "                    <input type=\"radio\" name=\"cuentaA\" value=\"".$dtl['numerodecuenta']."\">\n";
            $html .= "                  </td>\n";
            $html .= "                  <td width=\"32%\" class=\"".$est."\"><b>".$dtl['numerodecuenta']."</b></td>\n";
            $i++;
            $j++;
          }
          if($j < 3)
            $html .= "                      <td class=\"".$est."\" colspan=\"".((3 - $j)*2)."\"></td>\n";
          $html .= "                </tr>\n";
          $html .= "              </table>\n";
          $html .= "              <br>\n";
          $html .= "              <center>\n";
          $html .= "                <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\">\n";
          $html .= "              </center>\n";
          $html .= "            </form>\n";
          $html .= "          </div>\n";
          $html .= "         </td>\n";
          $html .= "       </tr>\n";
        }
      }
      $html .= "     </table>\n";
      $html .= "   </td>";
      $html .= "  </tr>";
      $html .= "</table>\n";
      $html .= "<script>\n";
      $html .= "  function DesplegarUnificacionCuentas()\n";
      $html .= "  {\n";
      $html .= "    e = document.getElementById('divUnificacionCuentas');\n";
      $html .= "    if(e.style.display == 'none')\n";
      $html .= "      e.style.display = 'block';\n";
      $html .= "    else\n";
      $html .= "      e.style.display = 'none';\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      return $html;
		}
//-------------------------DIVISION CUENTAS-----------------------------
  /**
  *FormaCambioResponsable
  */
  function FormaCambioResponsable($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
  {
        $action=ModuloGetURL('app','Facturacion','user','NuevoResponsable',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $html = ThemeAbrirTabla('CUENTAS -  CAMBIO RESPONSABLE');
        $html .= "            <table width=\"50%\" align=\"center\" border=\"0\">";
        $html .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "               <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
        $responsables=$this->Llamaresponsables();
        $html .= $this->MostrarResponsable($responsables,$PlanId);
        $html .= "              </select></td></tr>";
        $html .= "               <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"><br></td>";
        $html .= "           </form>";
        $actionM=SessionGetVar("AccionVolverCargosIYM");
        $html .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $html .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"><br></td></tr>";
        $html .= "           </form>";
        $html .= "           </table>";
        $html .= ThemeCerrarTabla();
        return $html;
  }

    /**
    * Metodo donde se muestran Los tipos de division de cuentas que existen.
    * 
    * @param integer $PlanId Identificador del plan
    * @param integer $Cuenta Numero de la cuenta
    * @param string $TipoId Tipo documento del paciente
    * @param string $PacienteId Numero documento del paciente
    * @param integer $Ingreso Identificador del ingreso
    * @param string $Nivel nivel
    * @param string $Fecha fecha de la cuenta
    * @param mixed $Tipo Identificador del tipo de division
    * @param array $infoPlan Arreglo de datos con la informacion del plan
    *
    * @return string
    */
    function FormaTiposDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Tipo,$infoPlan)
    {
      $Nombres=$this->LlamaBuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$this->LlamaBuscarApellidosPaciente($TipoId,$PacienteId);
      $html = ThemeAbrirTabla('CUENTAS -  DIVISION DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
      //$this->EncabezadoEmpresa($Caja);
      $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
      
      $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
       
      if(empty($Tipo))
      {
        $accion=ModuloGetURL('app','Cuentas','user','LlamaBuscarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "    <tr class=\"formulacion_table_list\">";
        $html .= "      <td align=\"center\" colspan=\"2\">DIVISION DE CUENTA</td>";
        $html .= "    </tr>";
        $html .= "    <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\">SELECCION EL CRITERIO: </td>";
        $html .= "      <td>\n";
        $html .= "        <select name=\"Tipo\" class=\"select\">";
        $html .= "          <option value=\"-1\">LISTAR TODOS</option>";
        $html .= "          <option value=\"1\">VALOR</option>";
        $html .= "          <option value=\"2\">FECHA</option>";
        $html .= "          <option value=\"3\">DEPARTAMENTO</option>";
        $html .= "          <option value=\"4\">SERVICIO</option>";
        if($infoPlan['sw_tipo_plan'] == '1')
          $html .= "          <option value=\"5\" selected>TOPES SOAT</option>";
        
        $html .= "        </select>\n";
        $html .= "      </td>";
        $html .= "    </tr>";
        $html .= "  </table>";
      }
      else
      {
          $accion=ModuloGetURL('app','Cuentas','user','LlamaDivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
          $html .= "     <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
          $html .= "          <tr class=\"modulo_table_list_title\">";
          $html .= "            <td align=\"center\" colspan=\"3\">DIVISION DE CUENTA</td>";
          $html .= "          </tr>";
          $html .= $this->SetStyle("MensajeError");
          if($Tipo==1){
            $html .= "                <tr>";
            $html .= "                    <td class=\"".$this->SetStyle("Valor")."\" align=\"center\">VALOR: </td>";
            $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Valor\"></td>";
            $html .= "                </tr>";
          }
          if($Tipo==2){
            $html .= "                <tr>";
            $html .= "                    <td class=\"".$this->SetStyle("FechaI")."\" align=\"center\">DESDE: </td>";
            $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\">".ReturnOpenCalendario('forma','FechaI','/')."</td>";
            $html .= "                </tr>";
            $html .= "                <tr>";
            $html .= "                    <td class=\"".$this->SetStyle("FechaF")."\" align=\"center\">HASTA: </td>";
            $html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\">".ReturnOpenCalendario('forma','FechaF','/')."</td>";
            $html .= "                </tr><br>";
          }
          if($Tipo==3){
            $html .= "                <tr>";
            $html .= "                <td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Departamento\" class=\"select\">";
            $departamento=$this->LlamaDepartamentos();
            //$this->BuscarDepartamento($departamento,$d,$Dpto);
            $html .=" <option value=\"-1\" selected>--TODOS--</option>";
              for($i=0; $i<sizeof($departamento); $i++)
              {
                  $html .=" <option value=\"".$departamento[$i][departamento]."\">".$departamento[$i][descripcion]."</option>";
              }
              $html .= "                  </select></td>";
              $html .= "                </tr>";
            }
            if($Tipo==4){
              $html .= "                <tr>";
              $html .= "                <td class=\"label\">TIPO SERVICIO: </td><td><select name=\"Servicio\" class=\"select\">";
              $tipo=$this->LlamaTiposServicios();
               $html .=" <option value=\"-1\" selected>--TODOS--</option>";
              for($i=0; $i<sizeof($tipo); $i++)
              {
                  $html .=" <option value=\"".$tipo[$i][servicio]."\">".$tipo[$i][descripcion]."</option>";
              }
              $html .= "                  </select></td>";
              $html .= "                </tr>";
            }
            $html .= "          <tr class=\"modulo_list_claro\">";
            $html .= "          </tr>";
            $html .= "     </table>";
            $html .= "    <input type=\"hidden\" name=\"Tipo\" value=\"$Tipo\">";
      }
      $html .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
      $html .= "          <tr>";
      $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
      $html .= "</form>";
      if(!empty($Tipo))
      {
          //$accion=ModuloGetURL('app','Facturacion','user','TiposDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $accion=ModuloGetURL('app','Cuentas','user','LlamaTiposDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER CRITERIOS\"></td>";
      }
      $accion=SessionGetVar("AccionVolverCargosIYM");
      $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
      $html .= "          </tr>";
      $html .= "     </table>";
      $html .= ThemeCerrarTabla();
      return $html;
    }
		/**
		* Muestra Los tipos de cortes de cuentas que existen.
		* @access private
		* @return boolean
		* @param int identificador del plan
		* @param int numero de la cuenta
		* @param string tipo documento
		* @param int numero documento
		* @param string ingreso
		* @param string nivel
		* @param date fecha de la cuenta
		* @param int tipo de corte de cuenta
		*/

    function FormaTiposCortes($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Tipo)
    {
     
        $Nombres=$this->LlamaBuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->LlamaBuscarApellidosPaciente($TipoId,$PacienteId);
        $html = ThemeAbrirTabla('CORTE DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
        //$this->EncabezadoEmpresa($Caja);
        $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        //$html .= $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        //$this->TotalesCuenta($Cuenta);
        //$html .= "     </table><br>";
        if(empty($Tipo))
        {
            $accion=ModuloGetURL('app','Cuentas','user','LlamaBuscarCortes',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $html .= "     <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
            $html .= "          <tr class=\"modulo_table_list_title\">";
            $html .= "            <td align=\"center\" colspan=\"2\">OPCIONES CORTE DE CUENTA</td>";
            $html .= "          </tr>";
            $html .= "          <tr class=\"modulo_list_claro\">";
            $html .= "            <td class=\"label\" align=\"center\">SELECCION EL CRITERIO: </td>";
            $html .= "            <td><select name=\"Tipo\" class=\"select\">";
            //$html .="                   <option value=\"-1\">LISTAR TODOS</option>";
            //$html .="                   <option value=\"1\">VALOR</option>";
            $html .="                   <option value=\"2\">FECHA - HORA</option>";
            //$html .="                   <option value=\"3\">DEPARTAMENTO</option>";
            //$html .="                   <option value=\"4\">SERVICIO</option>";
            $html .= "              </select></td>";
            $html .= "          </tr>";
            $html .= "     </table>";
        }
        else
        {
						//$accion=ModuloGetURL('app','Cuentas','user','LlamaCortesCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
						$accion=ModuloGetURL('app','Cuentas','user','LlamarFormaListadoCorte',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
						$html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
						$html .= "     <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
						$html .= "          <tr class=\"modulo_table_list_title\">";
						$html .= "            <td align=\"center\" colspan=\"4\">CORTE DE CUENTA</td>";
						$html .= "          </tr>";
						$html .= $this->SetStyle("MensajeError");
						if($Tipo==1){
							$html .= "                <tr>";
							$html .= "                    <td class=\"".$this->SetStyle("Valor")."\" align=\"center\">VALOR: </td>";
							$html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Valor\"></td>";
							$html .= "                </tr>";
						}
						if($Tipo==2){
							$html .= "                <tr>";
							$html .= "                    <td class=\"".$this->SetStyle("FechaI")."\" align=\"center\">DESDE: </td>";
							$html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaI\">".ReturnOpenCalendario('forma','FechaI','/')."</td>";
//HORA Y MINUTOS DESDE
							$html .= "                    <td>";
							$html .= "      <label class=\"label\"> DESDE LAS: </label>";
							$html .= "      <select name=\"horario\" class=\"select\">";
							$html .= "      <option value=\"-1\">--</option>";
							for($i=0;$i<24;$i++)
							{
									if($i<10)
									{
											if($_POST['horario']=="0$i")
											{
													$html .="<option value=\"0$i\" selected>0$i</option>";
											}
											else
											{
													$html .="<option value=\"0$i\">0$i</option>";
											}
									}
									else
									{
											if($_POST['horario']=="$i")
											{
													$html .="<option value=\"$i\" selected>$i</option>";
											}
											else
											{
													$html .="<option value=\"$i\">$i</option>";
											}
									}
							}
							$html .= "      </select>";
							$html .= " : ";
							$html .= "      <select name=\"minutero\" class=\"select\">";
							$html .= "      <option value=\"-1\">--</option>";
							for($i=0;$i<60;$i++)
							{
									if($i<10)
									{
											if($_POST['minutero']=="0$i")
											{
													$html .="<option value=\"0$i\" selected>0$i</option>";
											}
											else
											{
													$html .="<option value=\"0$i\">0$i</option>";
											}
									}
									else
									{
											if($_POST['minutero']=="$i")
											{
													$html .="<option value=\"$i\" selected>$i</option>";
											}
											else
											{
													$html .="<option value=\"$i\">$i</option>";
											}
									}
							}
							$html .= "      </select>";
							$html .= "                 </td>";
//FIN HORA Y MINUTOS DESDE
							$html .= "                </tr>";
							$html .= "                <tr>";
							$html .= "                    <td class=\"".$this->SetStyle("FechaF")."\" align=\"center\">HASTA: </td>";
							$html .= "                    <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"FechaF\">".ReturnOpenCalendario('forma','FechaF','/')."</td>";
//HORA Y MINUTOS HASTA
							$html .= "                    <td>";
							$html .= "      <label class=\"label\"> HASTA LAS: </label>";
							$html .= "      <select name=\"horario2\" class=\"select\">";
							$html .= "      <option value=\"-1\">--</option>";
							for($i=0;$i<24;$i++)
							{
									if($i<10)
									{
											if($_POST['horario2']=="0$i")
											{
													$html .="<option value=\"0$i\" selected>0$i</option>";
											}
											else
											{
													$html .="<option value=\"0$i\">0$i</option>";
											}
									}
									else
									{
											if($_POST['horario2']=="$i")
											{
													$html .="<option value=\"$i\" selected>$i</option>";
											}
											else
											{
													$html .="<option value=\"$i\">$i</option>";
											}
									}
							}
							$html .= "      </select>";
							$html .= " : ";
							$html .= "      <select name=\"minutero2\" class=\"select\">";
							$html .= "      <option value=\"-1\">--</option>";
							for($i=0;$i<60;$i++)
							{
									if($i<10)
									{
											if($_POST['minutero2']=="0$i")
											{
													$html .="<option value=\"0$i\" selected>0$i</option>";
											}
											else
											{
													$html .="<option value=\"0$i\">0$i</option>";
											}
									}
									else
									{
											if($_POST['minutero2']=="$i")
											{
													$html .="<option value=\"$i\" selected>$i</option>";
											}
											else
											{
													$html .="<option value=\"$i\">$i</option>";
											}
									}
							}
							$html .= "      </select>";
							$html .= "                 </td>";
//FIN HORA Y MINUTOS HASTA
              $html .= "                </tr><br>";
            }
            if($Tipo==3){
              $html .= "                <tr>";
              $html .= "                <td class=\"label\">DEPARTAMENTO: </td><td><select name=\"Departamento\" class=\"select\">";
              $departamento=$this->LlamaDepartamentos();
              //$this->BuscarDepartamento($departamento,$d,$Dpto);
              $html .= "                  </select></td>";
              $html .= "                </tr>";
            }
            if($Tipo==4){
              $html .= "                <tr>";
              $html .= "                <td class=\"label\">TIPO SERVICIO: </td><td><select name=\"Servicio\" class=\"select\">";
              $tipo=$this->LlamaTiposServicios();
               $html .=" <option value=\"-1\" selected>--TODOS--</option>";
              for($i=0; $i<sizeof($tipo); $i++)
              {
                  $html .=" <option value=\"".$tipo[$i][servicio]."\">".$tipo[$i][descripcion]."</option>";
              }
              $html .= "                  </select></td>";
              $html .= "                </tr>";
            }
            $html .= "          <tr class=\"modulo_list_claro\">";
            $html .= "          </tr>";
            $html .= "     </table>";
            $html .= "    <input type=\"hidden\" name=\"Tipo\" value=\"$Tipo\">";
        }
        $html .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
        $html .= "          <tr>";
        $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
        $html .= "</form>";
        if(!empty($Tipo))
        {
            //$accion=ModuloGetURL('app','Facturacion','user','TiposDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $accion=ModuloGetURL('app','Cuentas','user','LLamaTiposCortes',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER CRITERIOS\"></td>";
            $html .= "</form>";
        }
        $accion=SessionGetVar("AccionVolverCargos");
        $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
        $html .= "          </tr>";
        $html .= "</form>";
        $html .= "     </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }
		
		function CortesCuenta()
 		{
/*			if($_REQUEST[Tipo] == '1')
			{
				$html = $this->();
			}*/
			if($_REQUEST[Tipo] == '2')
			{
				$html = $this->CortesCuentaPorFechaHora();
			}
/*			if($_REQUEST[Tipo] == '3')
			{
				$html = $this->();
			}
			if($_REQUEST[Tipo] == '4')
			{
				$html = $this->();
			}*/
			
			return $html;
		}
		
		function CortesCuentaPorFechaHora()
		{
			//ADICIONAR CARGOS PENDIENTES POR CARGAR
			IncludeClass('CargosPendientesPorCargarHTML','','app','Cuentas');
			$CargosPendientes = new CargosPendientesPorCargarHTML();
			//FIN ADICIONAR CARGOS POR CARGAR
			
			//CARGOS PENDIENTES POR CARGAR
			$BuscarPendientesCargar=BuscarPendientesCargar($_REQUEST[Ingreso]);
			if(!empty($BuscarPendientesCargar))
			{
				$PendientesCargar=PendientesCargar($_REQUEST[Ingreso]);
				$html .=$CargosPendientes->FormaPendientesCargar($PendientesCargar,$_REQUEST[PlanId],$_REQUEST[Cuenta],$_REQUEST[TipoId],$_REQUEST[PacienteId],$_REQUEST[Nivel] ,$_REQUEST[Cama],$_REQUEST[Fecha],$_REQUEST[Ingreso]);
			}
			//FIN CARGOS PENDIENTES POR CARGAR

			//HABITACIONES
			//********************************
			unset($_SESSION['CUENTAS']['CAMA']['LIQ']);
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
			{
							die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
			
			$FechaI = $this->FechaFormato($_REQUEST[FechaI]);
			$FechaF = $this->FechaFormato($_REQUEST[FechaF]);
			$hora_inicial = $_REQUEST[horario].':'.$_REQUEST[minutero];
			$hora_final = $_REQUEST[horario2].':'.$_REQUEST[minutero2];
			$liqHab = new LiquidacionHabitacionesCorte;
			$hab = $liqHab->LiquidarCargosInternacion($_REQUEST[Cuenta],false,$FechaI,$FechaF,$hora_inicial,$hora_final);
			
			if(is_array($hab))
			{
				unset($_SESSION['CUENTAS']['MOVIMIENTOS']);                
				//$accion=ModuloGetURL('app','Cuentas','user','CargarHabitacion',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				$html = "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$html .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
				$html .= "    <tr align=\"center\" class=\"modulo_table_title\">";
				$html .= "    <td colspan=\"7\">HABITACIONES</td>";
				$html .= "    </tr>";
				$html .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
				$html .= "     <td width=\"8%\">TARIF.</td>";
				$html .= "     <td width=\"8%\">CARGO</td>";
				$html .= "     <td width=\"60%\">DESCRIPCION</td>";
				$html .= "     <td width=\"8%\">PRECIO</td>";
				$html .= "     <td width=\"8%\">CANTIDAD</td>";
				$html .= "     <td width=\"8%\">TOTAL</td>";
				$html .= "     <td width=\"4%\"></td>";
				$html .= "    </tr>";
				$total=0;
				for($i=0; $i<sizeof($hab); $i++)
				{
								if( $i % 2) $estilo='modulo_list_claro';
								else $estilo='modulo_list_oscuro';
								$html .= "    <tr class=\"$estilo\">";
								$html .= "     <td align=\"center\">".$hab[$i][tarifario_id]."</td>";
								$html .= "     <td align=\"center\">".$hab[$i][cargo]."</td>";
								$html .= "     <td>".$hab[$i][descripcion]."</td>";
								$html .= "     <td align=\"center\">".$hab[$i][precio_plan]."</td>";
								$html .= "     <td align=\"center\">".$hab[$i][cantidad]."</td>";
								$html .= "     <td align=\"center\">".$hab[$i][valor_cargo]."</td>";
								$html .= "     <td align=\"center\"><input type=\"checkbox\" name=\"HAB$i\" value=\"HAB".$i."\"></td>";
								$html .= "    </tr>";
								$total +=$hab[$i][valor_cargo];
				}
				$html .= "    <tr align=\"center\">";
				$html .= "    <td colspan=\"5\" align=\"right\" class=\"label\">TOTAL ESTANCIA:</td>";
				$html .= "    <td colspan=\"1\" align=\"right\" class=\"label\">".FormatoValor($total)."</td>";
				$html .= "    </tr>";

				$html .= "    <tr align=\"center\">";
				$camasMov=RetornarWinOpenDetalleCamas($_REQUEST[Ingreso],'DETALLE DE MOVIMIENTOS','label');
				$html .= "    <td colspan=\"3\" align=\"center\" class=\"label\">$camasMov</td>";
/*				$egreso = $this->LlamaValidarEgresoPaciente($_REQUEST[Ingreso]);       
				if(!empty($egreso))
				{*/
						$accion=ModuloGetURL('app','Cuentas','user','LlamaFormaLiquidacionManualHabitaciones',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
						$html .= "    <td colspan=\"3\" align=\"left\" class=\"label\"><a href=\"$accion\">LIQUIDACION MANUAL</a></td>";            
						$html .= "    </tr>";
						$html .= "    <tr>";
						$accion=ModuloGetURL('app','Cuentas','user','LlamadoCargarHabitacionCuenta',array("EmpresaId"=>$_SESSION['CUENTAS']['EMPRESA'],'Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
						$html .= "    <td colspan=\"3\" align=\"center\" class=\"label\"><a href=\"$accion\">CARGAR A LA CUENTA</a></td><td colspan=\"3\"><BR>&nbsp;</td>";
						$html .= "</form>";
/*				}
				else
				{   
						$html .= "  <td colspan=\"3\" align=\"center\" class=\"label_mark\">EL PACIENTE NO TIENE ORDEN DE SALIDA DE LA ESTACION</td>";              
				}                */
				$html .= "</form>";
				$html .= "    </tr>";
				$html .= "  </table><br>";
			}
			elseif(empty($hab))
			{       //ocurrio un error hay q mostrarlo
				$html .= "<p align=\"center\" class=\"label_error\">".$liqHab->Err()."<BR>".$liqHab->ErrMsg()."</p>";
			}
			//********************************
			//FIN HABITACIONES
			return $html;
		}
		
		/**
		***LlamaValidarEgresoPaciente
		**/
		function LlamaValidarEgresoPaciente($Ingreso)
		{
			$hab = new Habitaciones();
			$fact = $hab->ValidarEgresoPaciente($Ingreso);
			return $fact;
		}
		
		function LlamaTiposServicios()
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->TiposServicios();
			return $dat;
		}

		function LlamaPlanes($PlanId,$empresa_id)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->Planes($PlanId,$empresa_id);
			return $dat;
		}

		function LlamaDetalleNuevo($Cuenta,$paginador,$corte,$FechaI,$FechaF,$hora_inicial,$hora_final)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->DetalleNuevo($Cuenta,$paginador,$corte,$FechaI,$FechaF,$hora_inicial,$hora_final);
			return $dat;
		}
    /**
		* Metodo donde se evalua el tipo de divison seleccionado y se muestran
    * las opciones
    *
    * @return string
		*/
		function BuscarDivision()
		{
      $request = $_REQUEST;
      $html = "";
      $opc = new OpcionesCuentas();
      switch($request['Tipo'])
      {
        case '-1':
          $html = $opc->LlamarFormaListadoDivision($request['PlanId'],$request['Cuenta'],$request['TipoId'],$request['PacienteId'],$request['Ingreso'],$request['Nivel'],$request['Fecha'],'');
				break;
        case '5':
          $empresa = SessiongetVar("Empresa");
          $rst = $opc->DivisionCuentasSoat($request['Cuenta'],$empresa);
          $html = $this->FormaListadoDivision($request['PlanId'],$request['Cuenta'],$request['TipoId'],$request['PacienteId'],$request['Ingreso'],$request['Nivel'],$request['Fecha'],null,null,$opc->mensaje);
        break;
				default:
          $infoPlan = $opc->NombrePlan($_REQUEST['PlanId']);
					$html = $this->FormaTiposDivision($request['PlanId'],$request['Cuenta'],$request['TipoId'],$request['PacienteId'],$request['Ingreso'],$request['Nivel'],$request['Fecha'],$request['Tipo'],$infoPlan);
        break;
      }
      return $html;
		}
    
    function SeleccionCriterios()
		{
				$fact = new OpcionesCuentas();
				$html = $fact->LlamarFormaListadoDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],array('FechaI'=>$_REQUEST['FechaI'],'FechaF'=>$_REQUEST['FechaF'], 'Servicio'=>$_REQUEST['Servicio'], 'Departamento'=>$_REQUEST['Departamento'], 'Valor'=>$_REQUEST['Valor']));
				return $html;
				
		}
    /**
		*
		*/
		function BuscarCorte()
		{
/*				if($_REQUEST['Tipo']==-1)
				{
						$_REQUEST['Tipo'] = '';
						$fact = new OpcionesCuentas();
						$html = $fact->LlamarFormaListadoCorte($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],'');
						return $html;
				}
				else
				{*/
						$html = $this->FormaTiposCortes($_REQUEST['PlanId'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha'],$_REQUEST['Tipo']);
						return $html;
//				}
		}

    /**
    ***FormaListadoCorte
    **/
    function FormaListadoCorte($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars,$msg)
    {
    
      unset($_SESSION['CUENTAS']['CORTE']);

      unset($_SESSION['CUENTA']['DIVISION_CUENTA']['ACCION_FINALIZAR']);
      unset($_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']);

      if(empty($PlanId) AND empty($Cuenta)){
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $Nivel=$_REQUEST['Nivel'];
      }
      $_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']=$PlanId;      
      //IncludeLib("tarifario");
      IncludeLib("funciones_facturacion");
      $Nombres=$this->LlamaBuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$this->LlamaBuscarApellidosPaciente($TipoId,$PacienteId);
      $html = ThemeAbrirTabla('CUENTAS -  CORTE DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');      
      //$this->EncabezadoEmpresa($Caja);
      //$abono=$this->BuscarAbonos($Cuenta);
      $abono=PagosCuentaDivision($Cuenta);
      $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
      //$this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);

      /******************** ID ********************/
      //$html .= "<table border=\"0\" width=\"90%\" align=\"center\">";
      //$html .= "<tr><td align=\"center\" width=\"100%\" id=\"MostrarCargosOtraCuenta\">";

			$FechaI = $this->FechaFormato($_REQUEST[FechaI]);
			$FechaF = $this->FechaFormato($_REQUEST[FechaF]);
			$hora_inicial = $_REQUEST[horario].':'.$_REQUEST[minutero];
			$hora_final = $_REQUEST[horario2].':'.$_REQUEST[minutero2];
			
			$msg='Esta seguro que desea realizar Corte de la Cuenta No. '.$Cuenta.'?';
			$arreglo=array('PlanId'=>$PlanId,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'vars'=>$vars,'FechaI'=>$FechaI,'FechaF'=>$FechaF,'hora_inicial'=>$hora_inicial,'hora_final'=>$hora_final);
			$accion=ModuloGetURL('app','Cuentas','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Cuentas','me2'=>'LlamarFormaListadoCorte','me'=>'LlamaInsertarCorteCuenta','mensaje'=>$msg,'titulo'=>'CORTE DE CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'CONTINUAR','boton2'=>'CANCELAR'));          
      $html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
      $contcols=(sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']));
      //Manejo de los planes
      //if(!empty($msg)){$disabled = "disabled";}      
      $datPlan=$this->LlamaNombrePlan($PlanId); 
      $datTer=$this->LlamaNombreTercero($datPlan['tipo_tercero_id'],$datPlan['tercero_id']);     
      $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][0][$PlanId]=$datPlan['plan_descripcion'];      
       
      //$html .= "<BR><center><div class=\"label_mark\">$msg</div></center><BR>";      
     
      //abonos cuenta actual
     
  //CORTES HABITACIONES
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
			{
							die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
/*
$FechaI = $this->FechaFormato($_REQUEST[FechaI]);
$FechaF = $this->FechaFormato($_REQUEST[FechaF]);
$hora_inicial = $_REQUEST[horario].':'.$_REQUEST[minutero];
$hora_final = $_REQUEST[horario2].':'.$_REQUEST[minutero2];
*/
			$liqHab = new LiquidacionHabitacionesCorte;
			$hab = $liqHab->LiquidarCargosInternacion($_REQUEST[Cuenta],false,$FechaI,$FechaF,$hora_inicial,$hora_final);

			if(is_array($hab))
			{
					//$_SESSION['CUENTAS']['CAMA']['LIQ']=$hab;
					//IncludeClass('HabitacionesHTML','','app','Cuentas');  
					//$habitaciones = new HabitacionesHTML();
					//$obj->SetJavaScripts('DetalleCamas');
					//$html .= $habitaciones->FormaHabitaciones($hab,$PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso);
				//FORMA HABITACIONES
				$var = BuscarMoviemientosCamas($Ingreso,$_REQUEST[Cuenta],$FechaI,$FechaF,$hora_inicial,$hora_final);
				if(is_array($var))
				{
					$habitaciones = true;
					$html .="	<br><table width=\"80%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$html .="		<tr class=\"modulo_table_list_title\">";
					$html .="		<td colspan=\"".(10+$contcols)."\">MOVIMIENTOS HABITACIONES</td>";
					$html .="		</tr>";
					$html .="		<tr class=\"modulo_table_list_title\">";
					$html .="			<td>CARGO</td>";
					$html .="		  <td>DESCRIPCION</td>";
					$html .="			<td>FECHA INGRESO</td>";
					$html .="			<td>FECHA EGRESO</td>";
					$html .="			<td>PIEZA</td>";
					$html .="			<td>CAMA</td>";
					$html .="			<td>UBICACION</td>";
					$html .="			<td>DEPARTAMENTO</td>";
					$html .="			<td>ESTADO</td>";
					$html .= "		<td width=\"3%\">&nbsp;</td>";
/*					foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
						foreach($vector as $plan=>$plan_nom){
							if($indice!='0'){$indice=$indice;}else{$indice='';}
							$html .= "            <td width=\"3%\">$indice</td>";
						}
					}    */
					$html .="		</tr>";
					$_SESSION['CUENTAS']['CORTE']['DET_HAB'] = sizeof($var);
					for($i=0; $i<sizeof($var); $i++)
					{
						if( $i % 2) $estilo='modulo_list_claro';
						else $estilo='modulo_list_oscuro';
						$html .="		<tr  class=\"$estilo\">";
						$html .="		  <td align=\"center\">".$var[$i][cargo]."</td>";
						$html .="		  <td>".$var[$i][descar]."</td>";
						$html .="			<td align=\"center\">".$var[$i][fecha_ingreso]."</td>";
/*						$disabled = $class = "";
						if(empty($var[$i][fecha_egreso]))
						{
								$var[$i][fecha_egreso]='CAMA ACTUAL';
								$class='label_mark';
								$disabled = "disabled";
						}*/
						$html1 = "";
						if(empty($var[$i][fecha_egreso]))
						{
							//$var[$i][fecha_egreso]="<B>CAMA ACTUAL</B>";
							//FECHA HORA A LIQUIDAR PARA LA HABITACION ACTUAL
							$html1 = "<input type=\"text\" class=\"input-text\" name=\"FechaLiquidar\">".ReturnOpenCalendario('forma','FechaLiquidar','/')."";

							
							//FINFECHA HORA A LIQUIDAR PARA LA HABITACION ACTUAL
							$var[$i][movimiento_id] .= "*CAMACTUAL";
						}
						if(!empty($html1))
						{
							$html .="<td align=\"center\" class=\"$class\">".$html1."</td>";
						}
						else
						{
							$html .="<td align=\"center\" class=\"$class\">".$var[$i][fecha_egreso]."</td>";
						}
						$html .="			<td align=\"center\">".$var[$i][pieza]."</td>";
						$html .="			<td align=\"center\">".$var[$i][cama]."</td>";
						$html .="			<td align=\"center\">".$var[$i][ubicacion]."</td>";
						$html .="			<td align=\"center\">".$var[$i][descripcion]."</td>";
						$estado="<img src=\"".GetThemePath()."/images/checkS.gif\" title=\"Cargada a la Cuenta\">";

						if(empty($var[$i][transaccion]))
						{  $estado="<img src=\"".GetThemePath()."/images/checkN.gif\" title=\"Sin cargar a la Cuenta\">";/*$readonly="";*/}
						$html .="			<td align=\"center\">$estado</td>";
						//foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
						//	foreach($vector as $plan=>$plan_nom){
								$che='';                          
								//if($var[$i][cuenta]==$indice){$che='checked';}          
								$html .= "            <td width=\"3%\" align=\"center\"><input $che title=\"$plan_nom\" type=\"checkbox\" name=\"HAB".$i."\" value=\"".$var[$i][movimiento_id]."\" $disabled></td>";              
						//	}
						//}
						$html .="			</tr>";
					}
					$html .="</table>";
				}
				//FIN FORMA HABITACIONES
			}
			elseif(empty($hab))
			{       //ocurrio un error hay q mostrarlo
							$html .= "<p align=\"center\" class=\"label_error\">".$liqHab->Err()."<BR>".$liqHab->ErrMsg()."</p>";
			}
//FIN CORTES HABITACIONES
 //
    $js = "<SCRIPT>";
    $js .= "function Seleccion(frm,valor){";
    $js .= "  for(i=0;i<frm.elements.length;i++){";
    $js .= "    if(frm.elements[i].type == 'checkbox'){";
    $js .= "      if(frm.elements[i].checked == true){";
    $js .= "         frm.elements[i].checked = false;";
    $js .= "       }else{";
    $js .= "          frm.elements[i].checked = true;";
    $js .= "       }";
    $js .= "    }";
    $js .= "  }";
    $js .= " }\n";    
    $js .= "  function SeleccionActo(frm,valor,seleccion)\n";
    $js .= "  {\n";
    $js .= "    i = 0;\n";
    $js .= "    try\n";
    $js .= "    {\n";
    $js .= "      do\n";
    $js .= "      {\n";
    $js .= "        elemento = document.getElementById(valor+'_'+i);\n";
    $js .= "        elemento.checked = seleccion;\n";
    $js .= "        i++;\n";
    $js .= "      }while(1);\n";
    $js .= "    }\n";
    $js .= "    catch(error){}\n";
    $js .= "  }\n";
    $js .= "function disableCheck(field, value) {";
    
    $js .= " field.checked = !value;";
  
    $js .= "}";
 
    
    
    $js .= "</SCRIPT>";
 //   
      $det=$this->LlamaDetalleNuevo($Cuenta,$paginador=1,$corte=true,$FechaI,$FechaF,$hora_inicial,$hora_final);

      $html .= "$js";
      $html .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
      $html .= $this->SetStyle("MensajeError");
      $html .= "     </table>";     
      
      $html .= "<br><table width=\"90%\" align=\"center\">";
      $html .= "<tr><td id=\"capa_cargos\">"; 
          
      $html .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";          
      $html .= "          <tr class=\"modulo_table_list_title\">";      
      $html .= "            <td align=\"center\" colspan=\"".(12+$contcols)."\">CARGOS DE LA CUENTA ACTUAL</td>";
      $html .= "          </tr>";      
      $html .= "          <tr class=\"modulo_table_list_title\">";      
      $html .= "            <td align=\"center\" colspan=\"11\">&nbsp;</td>";            
      $chequeado='';
      $html .= "            <td align=\"center\"><input type=\"checkbox\" name=\"SeleccionTotal\"  onclick=\"Seleccion(this.form, this.value)\" align=\"center\"></td>";
      $html .= "          </tr>";
      
      $html .= "          <tr class=\"modulo_table_list_title\">";
      $html .= "            <td width=\"7%\">TARIFARIO--</td>";
      $html .= "            <td width=\"5%\">CARGO</td>";
      $html .= "            <td width=\"10%\">CODIGO</td>";
      $html .= "            <td>DESCRIPCION</td>";
      $html .= "            <td width=\"8%\">FECHA CARGO</td>";
      $html .= "            <td width=\"5%\">HORA</td>";
      $html .= "            <td width=\"7%\">CANT</td>";
      $html .= "            <td width=\"8%\">VALOR CARGO</td>";
      $html .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
      $html .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
      $html .= "            <td width=\"10%\">DPTO.</td>";     
      $html .= "            <td width=\"3%\">&nbsp;</td>";
      $html .= "          </tr>";
      $car=$cubi=$nocub=0;
      $_SESSION['CUENTAS']['CORTE']['DET'] = sizeof($det);
      $acto_qx = '';
      $j = 0;
      $id_campo = '';
      if(!empty($det))
      {        
        for($i=0; $i<sizeof($det);$i++)
        {          
          if($i % 2) {  $estilo="modulo_list_claro";  }
          else {  $estilo="modulo_list_oscuro";   }          
          //suma los totales del final
          $car+=$det[$i][valor_cargo];
          $cubi+=$det[$i][valor_cubierto];
          $nocub+=$det[$i][valor_nocubierto];
          if (!empty($det[$i][cuenta_liquidacion_qx_id]))
          {
            if($acto_qx != $det[$i][cuenta_liquidacion_qx_id])
            {
              $html .= "          <tr class=\"modulo_table_list_title\">\n";      
              $html .= "            <td align=\"center\" colspan=\"11\">".$det[$i][descripcion_grupo]." (".$det[$i][cuenta_liquidacion_qx_id].")</td>";            
              $html .= "            <td align=\"center\">\n";
              $html .= "              <input type=\"checkbox\" name=\"SeleccionActoQx[".$det[$i][cuenta_liquidacion_qx_id]."]\"  value=\"".$det[$i]['cuenta_liquidacion_qx_id']."\" onclick=\"SeleccionActo(this.form, this.value,this.checked)\" align=\"center\">\n";
              $html .= "            </td>\n";
              $html .= "          </tr>\n";
              $j = 0;
            }          
            $id_campo = "id=\"".$det[$i][cuenta_liquidacion_qx_id]."_".($j++)."\" ";
            $dis = " onclick=\"disableCheck(this,this.checked)\"";
          }
          else
          {
            $j = 0;
            $id_campo ="";
            $dis = "";
          }
          $acto_qx = $det[$i][cuenta_liquidacion_qx_id];
          
          $html .= "            <tr class=\"$estilo\">";
          $html .= "            <td width=\"7%\" align=\"center\">".$det[$i][tarifario_id]."</td>";
          $html .= "            <td width=\"5%\" align=\"center\">".$det[$i][cargo]."</td>";
          $html .= "            <td width=\"10%\" align=\"center\">".$det[$i][codigo_producto]."</td>";
          $html .= "            <td>".$det[$i][descripcion]."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
          $html .= "            <td width=\"5%\" align=\"center\">".$this->HoraStamp($det[$i][fecha_cargo])."</td>";
          $html .= "            <td width=\"7%\" align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";                                         
          $html .= "            <td>".$det[$i][departamento]."</td>\n";
          $valor=$det[$i-1][transaccion].'||//'.$det[$i-1][cargo_cups].'||//'.$det[$i-1][codigo_agrupamiento_id].'||//'.$det[$i-1][consecutivo];
              $che='';                           
          
          $html .= "            <td width=\"3%\" align=\"center\">\n";
          $html .= "              <input $che title=\"$plan_nom\" type=\"checkbox\" name=\"Transaccion$i\" ".$id_campo." value=\"".$det[$i][transaccion]."\" ".$dis." align=\"center\">\n";
          $html .= "            </td>\n";              
          
          $html .= "            </tr>";         
        }        
        
        if($i % 2) {  $estilo="modulo_list_claro";  }
        else {  $estilo="modulo_list_oscuro";   }
        $html .= "          <tr class=\"$estilo\">";
        $html .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
        $html .= "            <td>&nbsp;</td>";
        //$html .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom){
            $html .= "            <td width=\"3%\"></td>";
          }
        } 
        $html .= "          </tr>";        
      }      
      $html .= "     </table>";
      
      $html .= "     </td></tr>";
      $html .= "     </table>";
      $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','Cuentas','user','ObtenerFormaListadoDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
      $html .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
                     
			$html .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
			$html .= "     <tr>";
			$html .= "      <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"TERMINAR CORTE\"></td>";
      $html .= "</form>";

			$accion=ModuloGetURL('app','Cuentas','user','LlamaBuscarCortes',array('PlanId'=>$PlanId,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'vars'=>$vars));
			$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL CRITERIO\"></td>";
			$html .= "</form>";
			$accion = SessionGetVar("AccionVolverCargosIYM");
			$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
			$html .= "</form>";
			$html .= "          </tr>";
			$html .= "     </table>";
			$html .= "     <script>";
			//$html .= "     CrearVariables(new Array('$Cuenta','$PlanId','1'))";
			$html .= "     </script>";
			$html .= ThemeCerrarTabla();
			return $html;
    }
    
		/**
    *FormaListadoDivision
    */
    function FormaListadoDivision($PlanId,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$vars,$msg,$mensajeInfor)
    {   
      //GLOBAL $xajax;
      //$xajax->setFlag('debug',true);
      //unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
      unset($_SESSION['CUENTA']['DIVISION_CUENTA']['ACCION_FINALIZAR']);
      unset($_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']);

      if(empty($PlanId) AND empty($Cuenta)){
          $Cuenta=$_REQUEST['Cuenta'];
          $TipoId=$_REQUEST['TipoId'];
          $PacienteId=$_REQUEST['PacienteId'];
          $Nivel=$_REQUEST['Nivel'];
          $PlanId=$_REQUEST['PlanId'];
          $Ingreso=$_REQUEST['Ingreso'];
          $Fecha=$_REQUEST['Fecha'];
          $Nivel=$_REQUEST['Nivel'];
      }
      $_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']=$PlanId;      
      //IncludeLib("tarifario");
      IncludeLib("funciones_facturacion");
      //$this->Bajar();
      $Nombres=$this->LlamaBuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$this->LlamaBuscarApellidosPaciente($TipoId,$PacienteId);
      $html = ThemeAbrirTabla('CUENTAS -  DIVISION DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');      
      //$this->EncabezadoEmpresa($Caja);
      //$abono=$this->BuscarAbonos($Cuenta);
      $abono=PagosCuentaDivision($Cuenta);
      $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
      //$this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);

      /******************** ID ********************/
      //$html .= "<table border=\"0\" width=\"90%\" align=\"center\">";
      //$html .= "<tr><td align=\"center\" width=\"100%\" id=\"MostrarCargosOtraCuenta\">";

      $accion=ModuloGetURL('app','Cuentas','user','LlamaInsertarDivisionCuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
      $html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
      $contcols=(sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']));
      //Manejo de los planes
      if(!empty($msg)){$disabled = "disabled";}      
      $datPlan=$this->LlamaNombrePlan($PlanId); 
      $datTer=$this->LlamaNombreTercero($datPlan['tipo_tercero_id'],$datPlan['tercero_id']);     
      $_SESSION['DIVISION_CUENTA_VARIOS_PLANES'][0][$PlanId]=$datPlan['plan_descripcion'];      
      $html .= "    <table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
      $html .= "        <tr class=\"modulo_table_list_title\">";      
      $html .= "        <td width=\"50%\">RESPONSABLE PLAN:&nbsp;&nbsp;&nbsp;".$datTer['nombre_tercero']."</td>";      
      $html .= "        <td width=\"50%\">PLAN ACTUAL:&nbsp;&nbsp;&nbsp;".$datPlan['plan_descripcion']."</td>";
      $html .= "        </tr>";      
      $html .= "        <tr class=\"modulo_list_claro\">";
      $html .= "        <td colspan=\"2\" align=\"center\" class=\"label\">NUEVO PLAN: &nbsp;<select name=\"planNuevo\" class=\"select\" $disabled>";
      $cons = $this->LlamaPlanes($PlanId, $datPlan['empresa_id']);
      $html .="         <option value=\"-1\">---Seleccione---</option>";
      for($k=0; $k<sizeof($cons); $k++){
        $html .="       <option value=\"".$cons[$k][plan_id]."\">".$cons[$k][plan_descripcion]."</option>";
      }
      $html .= "        </select>";
      $html .= "        <input type=\"submit\" name=\"SeleccionarNuevoPlan\" value=\"SELECCIONAR\" class=\"input-submit\">";
      $html .= "        </td>";      
      $html .= "        </tr>";
      $html .= "        <tr class=\"modulo_list_claro\">"; 
      $html .= "        <td colspan=\"2\" align=\"center\">";
      $html .= "        <table border=\"0\" width=\"100%\" align=\"center\">";
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){
        if($indice!='0'){
          foreach($vector as $plan=>$plan_nom){
            $html .= "        <tr class=\"modulo_list_oscuro\">";
            $html .= "        <td width=\"10%\" class=\"label\">$indice</td>";
            $html .= "        <td>$plan_nom</td>";
            $html .= "        </tr>"; 
          }
        }  
      }
      $html .= "        </table>";     
      $html .= "        </td>"; 
      $html .= "        </tr>";     
      $html .= "    </table>";
      //fin
      if($mensajeInfor != "") $msg = $mensajeInfor;
      $html .= "<BR><center><div class=\"label_mark\">$msg</div></center><BR>";      
     
      //abonos cuenta actual
      $html .= "   <br><table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
      $html .= "          <tr class=\"modulo_table_list_title\">";
      $html .= "            <td align=\"center\" colspan=\"".(8+$contcols)."\">ABONOS DE LA CUENTA ACTUAL</td>";
      $html .= "          </tr>";
      unset($_SESSION['CUENTA']['ABONOS']['ACTUAL']);
      //if(!empty($abono[abonos]))
      if(!empty($abono))
      {
        $html .= "<tr class=\"modulo_table_list_title \">";
        $html .= "  <td width=\"12%\">RECIBO CAJA</td>";
        $html .= "  <td width=\"15%\">FECHA</td>";
        $html .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
        $html .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
        $html .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
        $html .= "  <td width=\"15%\">TOTAL BONOS</td>";
        $html .= "  <td width=\"15%\">TOTAL</td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom){
            if($indice!='0'){$indice=$indice;}else{$indice='';}
            $html .= "            <td width=\"3%\">$indice</td>";
          }
        }
        $html .= "</tr>";
        $total=0;
        $planes_abonos = array();
        for($j=0; $j<sizeof($abono); $j++){
          if(empty($_SESSION['CUENTA']['ABONOS'][$abono[$j][prefijo].$abono[$j][recibo_caja]])){
            $rcaja=$abono[$j][prefijo]."-".$abono[$j][recibo_caja];
            $fech=$abono[$j][fecha_ingcaja];
            $Te=FormatoValor($abono[$j][total_efectivo]);
            $Tc=FormatoValor($abono[$j][total_cheques]);
            $Tt=FormatoValor($abono[$j][total_tarjetas]);
            $Tb=FormatoValor($abono[$j][total_bonos]);
            $TOTAL=FormatoValor($abono[$j][total_abono]);
            if( $j % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}
            $html .= "<tr class=\"$estilo\" align=\"center\">";
            $html .= "  <td>$rcaja</td>";
            $html .= "  <td>$fech</td>";
            $html .= "  <td>$Te</td>";
            $html .= "  <td>$Tc</td>";
            $html .= "  <td>$Tt</td>";
            $html .= "  <td>$Tb</td>";
            $html .= "  <td class=\"label_error\">$TOTAL</td>";            
            $valor=$Cuenta."||//".$abono[$j][fecha_ingcaja]."||//".$abono[$j][total_efectivo]."||//".$abono[$j][total_cheques]."||//".$abono[$j][total_tarjetas]."||//".$abono[$j][total_bonos]."||//".$abono[$j][total_abono];
            //$html .= "            <td align=\"center\"><a href=\"javascript:AbonoOtraCuenta(document.forma,'$valor','$Cuenta');\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";                        
            foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
              foreach($vector as $plan=>$plan_nom){
                $che='';              
                if($abono[$j][cuenta]==$indice){ $planes_abonos[$plan] += $abono[$j]['total_abono']; $che='checked';}          
                $html .= "            <td align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" id=\"r_".$abono[$j][prefijo]."_".$abono[$j][recibo_caja]."\" name=\"".$abono[$j][prefijo]."||//".$abono[$j][recibo_caja]."\" value=\"".$indice."||//".$plan."||//".$valor."\" onclick=\"xajax_reqCambiarAbonoPlan(this.name,this.value)\"></td>";
              }
            } 
            $html .= "</tr>";
            $total+=$abono[$j][total_abono];
          }
        }
        $html .= "          <tr class=\"modulo_list_claro\">";
        $html .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom)
          {            
            $html .= "            <td width=\"4%\" class=\"label\" align=\"right\">\n";
            $html .= "              $<label  id=\"abono_".$plan."\">".Formatovalor($planes_abonos[$plan])."</label>\n";
            $html .= "            </td>\n";
          }
        }        
        $html .= "          </tr>";
      }
      $html .= "     </table>";
      
  //CORTES HABITACIONES
			//unset($_SESSION['CUENTAS']['CAMA']['LIQ']);
			if (!IncludeFile("classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php"))
			{
							die(MsgOut("Error al incluir archivo","El Archivo 'classes/LiquidacionHabitaciones/LiquidacionHabitaciones.class.php' NO SE ENCUENTRA"));
			}
			$liqHab = new LiquidacionHabitaciones;
			$hab = $liqHab->LiquidarCargosInternacion($Cuenta,false);

			if(is_array($hab))
			{
					
				//FORMA HABITACIONES
				$var = BuscarMoviemientosCamas($Ingreso);

				if(is_array($var))
				{
					$habitaciones = true;
					$html .="	<br><table width=\"80%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$html .="		<tr class=\"modulo_table_list_title\">";
					$html .="		<td colspan=\"".(10+$contcols)."\">MOVIMIENTOS HABITACIONES</td>";
					$html .="		</tr>";
					$html .="		<tr class=\"modulo_table_list_title\">";
					$html .="			<td>CARGO</td>";
					$html .="		  <td>DESCRIPCION</td>";
					$html .="			<td>FECHA INGRESO</td>";
					$html .="			<td>FECHA EGRESO</td>";
					$html .="			<td>PIEZA</td>";
					$html .="			<td>CAMA</td>";
					$html .="			<td>UBICACION</td>";
					$html .="			<td>DEPARTAMENTO</td>";
					$html .="			<td>ESTADO</td>";
					foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
						foreach($vector as $plan=>$plan_nom){
							if($indice!='0'){$indice=$indice;}else{$indice='';}
							$html .= "            <td width=\"3%\">$indice</td>";
						}
					}    
					$html .="		</tr>";
					for($i=0; $i<sizeof($var); $i++)
					{
						if( $i % 2) $estilo='modulo_list_claro';
						else $estilo='modulo_list_oscuro';
						$html .="		<tr  class=\"$estilo\">";
						$html .="		  <td align=\"center\">".$var[$i][cargo]."</td>";
						$html .="		  <td>".$var[$i][descar]."</td>";
						$html .="			<td align=\"center\">".$var[$i][fecha_ingreso]."</td>";
						if(empty($var[$i][fecha_egreso]))
						{
								$var[$i][fecha_egreso]='CAMA ACTUAL';
								$class='label_mark';
						}
						$html .="			<td align=\"center\" class=\"$class\">".$var[$i][fecha_egreso]."</td>";
						$html .="			<td align=\"center\">".$var[$i][pieza]."</td>";
						$html .="			<td align=\"center\">".$var[$i][cama]."</td>";
						$html .="			<td align=\"center\">".$var[$i][ubicacion]."</td>";
						$html .="			<td align=\"center\">".$var[$i][descripcion]."</td>";
						$estado="<img src=\"".GetThemePath()."/images/checkS.gif\" title=\"Cargada a la Cuenta\">";
						$readonly = "readonly";
						if(empty($var[$i][transaccion]))
						{  $estado="<img src=\"".GetThemePath()."/images/checkN.gif\" title=\"Sin cargar a la Cuenta\">";/*$readonly="";*/}
						$html .="			<td align=\"center\">$estado</td>";
						foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
							foreach($vector as $plan=>$plan_nom){
								$che='';        
								if($var[$i][cuenta]==$indice){$che='checked';}          
								$html .= "            <td width=\"3%\" align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" name=\"".$var[$i][movimiento_id]."\" value=\"".$var[$i][movimiento_id]."||//".$plan."\" OnClick=\"xajax_reqCambiarCargoPlan(this.name,this.value,$habitaciones)\" $readonly></td>";              
							}
						}
						$html .="			</tr>";
					}
					$html .="</table>";
				}
				//FIN FORMA HABITACIONES
			}
			elseif(empty($hab))
			{       //ocurrio un error hay q mostrarlo
							$html .= "<p align=\"center\" class=\"label_error\">".$liqHab->Err()."<BR>".$liqHab->ErrMsg()."</p>";
			}
//FIN CORTES HABITACIONES
    
      $det=$this->LlamaDetalleNuevo($Cuenta,$paginador=1,$corte=false);

      $html .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
      $html .= $this->SetStyle("MensajeError");
      $html .= "     </table>";     
      
      $html .= "<br><table width=\"90%\" align=\"center\">";
      $html .= "<tr><td id=\"capa_cargos\">"; 
          
      $html .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";          
      $html .= "          <tr class=\"modulo_table_list_title\">";      
      $html .= "            <td align=\"center\" colspan=\"".(12+$contcols)."\">CARGOS DE LA CUENTA ACTUAL</td>";
      $html .= "          </tr>";      
      $html .= "          <tr class=\"modulo_table_list_title\">";      
      $html .= "            <td align=\"center\" colspan=\"11\">&nbsp;</td>";            
      ksort($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
        foreach($vector as $plan=>$plan_nom){
          if($indice!='0'){
            $chequeado='';
            if($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']['SELECCION_TOTAL'][$indice]==$this->paginaActual){$chequeado='checked';}
            $html .= "            <td align=\"center\"><input type=\"checkbox\" name=\"SeleccionTotal$indice\" value=\"".$indice."||//".$plan."\" onclick=\"xajax_reqCambiarCargoPlanTotalPage(this.checked,'$Cuenta','".$this->limit."','".$this->offset."',this.value,'$plan_ini','".$this->paginaActual."')\" align=\"center\" $chequeado></td>";
          }else{
            $plan_ini=$plan;
            $html .= "            <td align=\"center\">&nbsp;</td>";      
          }
        }
      }     
      $html .= "          </tr>";
      
      $html .= "          <tr class=\"modulo_table_list_title\">";
      $html .= "            <td width=\"7%\">TARIFARIO--</td>";
      $html .= "            <td width=\"5%\">CARGO</td>";
      $html .= "            <td width=\"10%\">CODIGO</td>";
      $html .= "            <td>DESCRIPCION</td>";
      $html .= "            <td width=\"8%\">FECHA CARGO</td>";
      $html .= "            <td width=\"5%\">HORA</td>";
      $html .= "            <td width=\"7%\">CANT</td>";
      $html .= "            <td width=\"8%\">VALOR CARGO</td>";
      $html .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
      $html .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
      $html .= "            <td width=\"10%\">DPTO.</td>";     
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
        foreach($vector as $plan=>$plan_nom){
          if($indice!='0'){$indice=$indice;}else{$indice='';}
          $html .= "            <td width=\"3%\">$indice</td>";
        }
      }    
      $html .= "          </tr>";
      $car=$cubi=$nocub=0;
      if(!empty($det)){        
        for($i=0; $i<sizeof($det);$i++){          
          if($i % 2) {  $estilo="modulo_list_claro";  }
          else {  $estilo="modulo_list_oscuro";   }          
          //suma los totales del final
          $car+=$det[$i][valor_cargo];
          $cubi+=$det[$i][valor_cubierto];
          $nocub+=$det[$i][valor_nocubierto];                    
          $html .= "            <tr class=\"$estilo\">";
          $html .= "            <td width=\"7%\" align=\"center\">".$det[$i][tarifario_id]."</td>";
          $html .= "            <td width=\"5%\" align=\"center\">".$det[$i][cargo]."</td>";
          $html .= "            <td width=\"10%\" align=\"center\">".$det[$i][codigo_producto]."</td>";
          $html .= "            <td>".$det[$i][descripcion]."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
          $html .= "            <td width=\"5%\" align=\"center\">".$this->HoraStamp($det[$i][fecha_cargo])."</td>";
          $html .= "            <td width=\"7%\" align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
          $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";                                         
          $html .= "            <td>".$det[$i][departamento]."</td>";
          $valor=$det[$i-1][transaccion].'||//'.$det[$i-1][cargo_cups].'||//'.$det[$i-1][codigo_agrupamiento_id].'||//'.$det[$i-1][consecutivo];
          //$html .= "            <td align=\"center\"><a href=\"javascript:CargoOtraCuenta(document.forma,'$valor','$Cuenta');\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";
          foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
            foreach($vector as $plan=>$plan_nom){
              $che='';                           
              if($det[$i][cuenta]==$indice){$plan_cargo[$plan]+= $det[$i]['valor_cargo'];$che='checked';}          
              $html .= "            <td width=\"3%\" align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" name=\"".$det[$i][transaccion]."\" value=\"".$indice."||//".$plan."\" OnClick=\"xajax_reqCambiarCargoPlan(this.name,this.value)\"></td>";              
            }
          }
          $html .= "            </tr>";         
        }        
        
        if($i % 2) {  $estilo="modulo_list_claro";  }
        else {  $estilo="modulo_list_oscuro";   }
        $html .= "          <tr class=\"$estilo\">";
        $html .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
        $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
        $html .= "            <td>&nbsp;</td>";
        //$html .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector)
        {        
          foreach($vector as $plan=>$plan_nom)
          {
            $html .= "            <td width=\"3%\" class=\"label\" align=\"right\">\n";
            $html .= "              $<label  id=\"valor_".$plan."\">".Formatovalor($plan_cargo[$plan])."</label>\n";
            $html .= "            </td>\n";
          }
        } 
        $html .= "          </tr>";        
      }      
      $html .= "     </table>";
      
      $html .= "     </td></tr>";
      $html .= "     </table>";
      $Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','Cuentas','user','ObtenerFormaListadoDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
      $html .= "      ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
      
      $html .= "</form>";
      
      $datos_adicionales = SessionGetVar("CargoAdicionalesValor");

      if($datos_adicionales)
      {
        $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td class=\"normal_10AN\" colspan=\"11\">\n";
        $html .= "      <img src=\"".GetThemePath()."/images/informacion.png\">\n";
        $html .= "      LA ADICCION DEL SIGUIENTE CARGO SUBE EL VALOR DE LA DICVISON DE LA CUENTA A: $".FormatoValor($datos_adicionales['valor_total'] + $datos_adicionales['valor_cargo'])."\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"7%\">TARIFARIO--</td>";
        $html .= "    <td width=\"5%\">CARGO</td>";
        $html .= "    <td width=\"10%\">CODIGO</td>";
        $html .= "    <td>DESCRIPCION</td>";
        $html .= "    <td width=\"8%\">FECHA CARGO</td>";
        $html .= "    <td width=\"5%\">HORA</td>";
        $html .= "    <td width=\"7%\">CANT</td>";
        $html .= "    <td width=\"8%\">VALOR CARGO</td>";
        $html .= "    <td width=\"8%\">VAL. NO CUBIERTO</td>";
        $html .= "    <td width=\"8%\">VAL. CUBIERTO</td>";
        $html .= "    <td width=\"%\">DPTO.</td>";     
        $html .= "  </tr>\n";
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "    <td align=\"center\">".$datos_adicionales[tarifario_id]."</td>";
        $html .= "    <td align=\"center\">".$datos_adicionales[cargo]."</td>";
        $html .= "    <td align=\"center\">".$datos_adicionales[codigo_producto]."</td>";
        $html .= "    <td>".$datos_adicionales[descripcion]."</td>";
        $html .= "    <td align=\"center\">".$this->FechaStamp($datos_adicionales[fecha_cargo])."</td>";
        $html .= "    <td align=\"center\">".$this->HoraStamp($datos_adicionales[fecha_cargo])."</td>";
        $html .= "    <td align=\"center\">".FormatoValor($datos_adicionales[cantidad])."</td>";
        $html .= "    <td align=\"center\">".FormatoValor($datos_adicionales[valor_cargo])."</td>";
        $html .= "    <td align=\"center\">".FormatoValor($datos_adicionales[valor_nocubierto])."</td>";
        $html .= "    <td align=\"center\">".FormatoValor($datos_adicionales[valor_cubierto])."</td>";                                         
        $html .= "    <td>".$datos_adicionales[departamento]."</td>";
        $html .= "  </tr>\n";
        
        $action = ModuloGetURL('app','Cuentas','user','LlamaDivisionCuenta');
        $rq['Cuenta'] = $_REQUEST['Cuenta'];
        $rq['TipoId'] = $_REQUEST['TipoId'];
        $rq['PacienteId'] = $_REQUEST['PacienteId'];
        $rq['Nivel'] = $_REQUEST['Nivel'];
        $rq['PlanId'] = $_REQUEST['PlanId'];
        $rq['Cama'] = $_REQUEST['Cama'];
        $rq['Fecha'] = $_REQUEST['Fecha'];
        $rq['Ingreso'] = $_REQUEST['Ingreso'];
        $rq['Tipo'] = $_REQUEST['Tipo'];
        
        $html .= "  <tr>\n";
        $html .= "    <td class=\"normal_10AN\" colspan=\"11\">\n";
        $html .= "      <table width=\"80%\" align=\"center\">\n";
        $html .= "        <tr align=\"center\">\n";
        $html .= "          <td width=\"50%\">\n";
        $rq['Valor'] = $datos_adicionales['valor_total'] + $datos_adicionales['valor_cargo'];
        $html .= "            <a href=\"".$action.URLRequest($rq)."\" class=\"label_error\">ACEPTAR</a>\n";
        $html .= "          </td>\n";
        $html .= "          <td width=\"50%\">\n";
        $rq['Valor'] = $datos_adicionales['valor_total'];
        $html .= "            <a href=\"".$action.URLRequest($rq)."\" class=\"label_error\">DESCARTAR</a>\n";
        $html .= "          </td>\n";
        $html .= "        </tr>\n";
        $html .= "      </table>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        
        SessionDelVar("CargoAdicionalesValor");
      }
      
          $html .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
          $html .= "     <tr>";
          //$accion=ModuloGetURL('app','Facturacion','user','BuscarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $accion=ModuloGetURL('app','Cuentas','user','LlamaBuscarDivision',array('PlanId'=>$PlanId,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'vars'=>$vars));
          $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER CRITERIOS\"></td>";
          $html .= "</form>";
          //$accion=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
          $accion = SessionGetVar("AccionVolverCargosIYM");
          $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
          $html .= "</form>";
          if($contcols>1){
            $msg='Esta seguro que la Divisi?n de la Cuenta No. '.$Cuenta.' esta Correcta';
            $arreglo=array('PlanId'=>$PlanId,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Ingreso'=>$Ingreso,'Nivel'=>$Nivel,'Fecha'=>$Fecha,'vars'=>$vars);
            $accionEstado=ModuloGetURL('app','Cuentas','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Cuentas','me2'=>'LlamaFormaListadoDivision','me'=>'LlamaFinalizarDivision','mensaje'=>$msg,'titulo'=>'DIVIDIR CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos,'arreglo'=>$arreglo,'boton1'=>'CONTINUAR','boton2'=>'CANCELAR'));          
            $html .= "    <form name=\"formabuscar\" action=\"$accionEstado\" method=\"post\">";
            $html .= "      <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"TERMINAR DIVISION\"></td>";
            $html .= "    </form>";
          }
          $html .= "          </tr>";
          $html .= "     </table>";
          $html .= "     <script>";
          //$html .= "     CrearVariables(new Array('$Cuenta','$PlanId','1'))";
          $html .= "     </script>";
         
          $html .= ThemeCerrarTabla();
          return $html;
    }
    /**
    *FormaActivarCuentaDivision
    */
    function FormaActivarCuentaDivision($PlanId,$Cuenta1,$Cuenta,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$Corte)
    {
           $msg = " Divisi?n ";
          if($Corte)
          {
           $msg = " Corte ";
          }
          $html = ThemeAbrirTabla('CUENTAS -  ACTIVAR CUENTA');
          $det=$this->LlamaDetalleTotal($Cuenta);
          $html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
          $html .= $this->SetStyle("MensajeError");
          $html .= "     </table>";
          $accion=ModuloGetURL('app','Cuentas','user','ActivarCuentaDivision',array('Cuenta'=>$Cuenta,'Cuenta1'=>$Cuenta1,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'vars'=>$vars,'abajo'=>true));
          $html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
          $html .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"modulo_table_list\">";
          $html .= "          <tr class=\"modulo_table_list_title\">";
          $html .= "          <td align=\"center\" colspan=\"2\">ELIGA UNA CUENTA PARA SER ACTIVADA</td>";
          $html .= "          </tr>";
          $html .= "          <tr>";
          $html .= "          <td align=\"center\" colspan=\"2\"><br><br></td>";
          $html .= "          </tr>";
          $html .= "          <tr>";
          $html .= "          <td class=\"label\" align=\"right\" width=\"50%\">CUENTA No. $Cuenta1 (Inicial)</td>";
          $html .= "          <td width=\"50%\"><input type=\"radio\" name=\"CuentaA\" value=\"$Cuenta1\"></td>";
          $html .= "          </tr>";
          $html .= "          <tr>";
          $html .= "          <td class=\"label\" align=\"right\">CUENTA No. $Cuenta ($msg)</td>";
          $html .= "          <td><input type=\"radio\" name=\"CuentaA\" value=\"$Cuenta\"></td>";
          $html .= "          </tr>";
          $html .= "          <tr>";
          $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
          $html .= "</form>";
          $accion=ModuloGetURL('app','Cuentas','user','FormaMain');
          $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER A CUENTAS\"></td>";
          $html .= "</form>";
          $html .= "          </tr>";
          $html .= "     </table>";
          $html .= ThemeCerrarTabla();
          return $html;
    }

		function LlamaDetalleTotal($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->DetalleTotal($Cuenta);
			return $dat;
		}

		function LlamaBuscarCuentaParaActivar($Cuenta,$TipoId,$PacienteId)
		{
			$fact = new OpcionesCuentas();
			$msg = $fact->BuscarCuentaParaActivar($Cuenta,$TipoId,$PacienteId);
			return $msg;
		}

   function LlamaBuscarFechIngreso($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarFechIngreso($Cuenta);
			return $msg1;
		}
    function LlamaBuscarParametrizaCambioFe($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarParametFechCam($Cuenta,$_SESSION['DatosCentroUtilidadId'],$_SESSION['DatosEmpresaId']);
			return $msg1;
		}
    function LlamaBuscarInactivarCuentaP($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarVistoSalida($Cuenta);
			return $msg1;
		}
    function LlamaBuscarSolicMedic($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarSolicMedic($Cuenta);
			return $msg1;
		}
    function LlamaBuscarSolicDevol($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarSolicDevol($Cuenta);
			return $msg1;
		}
    function LlamaBuscarPacienUrgen($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarPacienUrgen($Cuenta);
			return $msg1;
		}
    function LlamaBuscarMovimHabit($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$msg1 = $fact->BuscarMovimHabit($Cuenta);
			return $msg1;
		}
			/**
			* Muestra el nombre del tercero con sus respectivos planes
			* @access private
			* @return string
			* @param array arreglor con los tipos de responsable
			* @param int el responsable que viene por defecto
			*/
		function MostrarResponsable($responsables,$Responsable)
		{
			$i=0;
			$html =" <option value=\"-1\">-------SELECCIONE-------</option>";
			while( $i < sizeof($responsables)){
					$concate=strtok($responsables[$i],'|/');
					for($l=0;$l<4;$l++)
					{
						$var[$l]=$concate;
						$concate = strtok('|/');
					}
					if($var[0]==$Responsable){
							$html .=" <option value=\"$var[0]\" selected>$var[1]</option>";
					}else{
							$html .=" <option value=\"$var[0]\">$var[1]</option>";
					}
			$i++;
			}
			return $html;
		}

	/**
	*
	*/
		function FormaDescuentos($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha)
		{
					//IncludeLib("tarifario");
					$html = ThemeAbrirTabla('DESCUENTOS CUENTA No. '.$Cuenta);
					$accion=ModuloGetURL('app','Cuentas','user','LlamaGuardarDescuentos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
					$html .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
					$html .= "           <br><table width=\"70%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
					$html .= $this->SetStyle("MensajeError");
					$html .= "               <tr align=\"center\" class=\"modulo_table_list_title\">";
					$html .= "                  <td>TIPOS DESCUENTOS</td>";
					$html .= "                  <td>DESC. EMPRESA</td>";
					$html .= "                  <td>DESC. PACIENTE</td>";
					$html .= "               </tr>";
					$Tipos=$this->LlamaBuscarSolicitudesDescuentos();
					for($i=0; $i<sizeof($Tipos); $i++)
					{
							$Des=$this->LlamaBuscarDescuentosCuenta($Cuenta,$Tipos[$i][grupo_tipo_cargo]);
							if($i % 2) {  $estilo="modulo_list_claro";  }
							else {  $estilo="modulo_list_oscuro";   }
							$html .= "               <tr class=\"$estilo\">";
							$html .= "                  <td aling=\"left\">&nbsp;".$Tipos[$i][descripcion]."</td>";
							$html .= "                  <td align=\"center\"><input type=\"text\" size=\"5\" value=\"".FormatoValor($Des[0][descuento_empresa])."\" name=\"DesEmp,".$i.",".$Tipos[$i][grupo_tipo_cargo]."\"> %</td>";
							$html .= "                  <td align=\"center\"><input type=\"text\" size=\"5\" value=\"".FormatoValor($Des[0][descuento_paciente])."\" name=\"DesPac,".$i.",".$Tipos[$i][grupo_tipo_cargo]."\"> %</td>";
							$html .= "               </tr>";
					}
					$html .= "           </table><BR>";
					$html .= "           <br><table width=\"70%\" border=\"0\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
					$html .= "               <tr align=\"center\">";
					$html .= "                <td width=\"50%\" ><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"GUARDAR\"></td>";
					$html .= "                  </form>";
					$accion = SessionGetvar("AccionVolverCargos");
					$html .= "                <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$html .= "                    <td><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
					$html .= "                </form>";
					$html .= "               </tr>";
					$html .= "           </table><BR>";
					$html .= ThemeCerrarTabla();
					return $html;
		}

		/**
			* Forma para los mansajes
			* @access private
			* @return void
		**/
		function FormaMensaje($mensaje,$titulo,$accion,$boton)
		{
			$html = ThemeAbrirTabla($titulo);
			$html .= "            <table width=\"60%\" align=\"center\" >";
			$html .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$html .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
			if($boton){
				$html .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
			}
			else{
				$html .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
			}
			$html .= "           </form>";
			$html .= "           </table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}

  /**
  *
  */
  function FormaDatosAfiliado($PlanId,$Cuenta,$Ingreso,$TipoId,$PacienteId,$Responsable)
  {
        $action=ModuloGetURL('app','Cuentas','user','LlamaGuardarNuevoPlan',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Responsable'=>$Responsable));
        $html = ThemeAbrirTabla('CUENTAS - CAMBIO RESPONSABLE');
        if(!empty($_REQUEST['descripcion_plan']))
        {  $html .= "<p class=\"label_mark\" align=\"center\">PLAN DE LA DIVISION - ".$_REQUEST['descripcion_plan']."</p>";  }
        $html .= "      <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "  <form name=\"forma\" action=\"$action\" method=\"post\">";
        $tipo_afiliado = $this->LlamaTipo_Afiliado($Responsable);
        $html .= "          <tr>";
        if(sizeof($tipo_afiliado)>1)
        {
            $html .= "               <td class=\"".$this->SetStyle("TipoAfiliado")."\" width=\"30%\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
            $html .= $this->BuscarIdTipoAfiliado($tipo_afiliado,$_REQUEST['TipoAfiliado']);
            $html .= "              </select></td>";
        }
        else
        {
            $html .= "            <td class=\"".$this->SetStyle("TipoAfiliado")."\" width=\"30%\">TIPO AFILIADO: </td>";
            $html .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$tipo_afiliado[0][tipo_afiliado_nombre]."</td>";
            $html .= "            <td></td>";
        }
        $html .= "          </tr>";
        $html .= "          <tr>";
        $niveles=$this->LlamaNiveles($Responsable);
        if(sizeof($niveles)>1)
        {
          $html .= "               <tr><td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
          $html .=" <option value=\"-1\">---Seleccione---</option>";
          for($i=0; $i<sizeof($niveles); $i++)
          {
              if($niveles[$i][rango]==$_REQUEST['Nivel' ]){
                $html .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
              }
              else{
                  $html .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
              }
          }
        }
        else
        {
            $html .= "             <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
            $html .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
            $html .= "            <td></td>";
        }
        $html .= "          </tr>";
        $html .= "          <tr>";
        $html .= "            <td class=\"".$this->SetStyle("SEM")."\">SEMANAS COTIZADAS: </td>";
        $html .= "            <td><input type=\"text\"  class=\"input-text\" name=\"Semanas\" size=\"10\" value=\"".$_REQUEST['Semanas']."\"></td>";
        $html .= "            <td></td>";
        $html .= "          </tr>";
        $html .= "          </table>";
        $html .= "      <table border=\"0\" width=\"40%\" align=\"center\" class=\"normal_10\">";
        $html .= "          <tr><td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"><br></td>";
        $html .= "  </form>";
/*                if(!empty($_REQUEST['descripcion_plan']))
                {   $actionM=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));   }
                else
                {   $actionM=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));  }*/
        //$actionM=ModuloGetURL('app','Facturacion','user','CancelarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $actionM = SessionGetVar("AccionVolverCargosIYM");
        $html .= "  <form name=\"formacancelar\" action=\"$actionM\" method=\"post\">";
        $html .= "               <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"><br></td>";
        $html .= "  </form>";
        $html .= "               </tr>";
        $html .= "          </table>";
        $html .= ThemeCerrarTabla();
        return $html;
  }

  /**
  *FormaCuentaGenerada
  */
	function FormaCuentaGenerada($plan,$descripcion,$Cuenta)
	{
					if(empty($Cuenta)){$Cuenta=$_REQUEST['Cuenta'];}
					$html = ThemeAbrirTabla('CUENTAS - CUENTAS GENERADAS HASTA AHORA DE LA DIVISION DE LA CUENTA No. '.$_SESSION['DIVISION']['CUENTA'][0]['cuenta']);
					$html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
					$html .= "   <tr>";
					$html .= "          <td align=\"center\" class=\"label_mark\">CUENTAS GENERADAS DESPUES DE LA DIVISION (estas cuentas estan inactivas)</td>";
					$html .= "   </tr>";
					$html .= "   <tr><td>&nbsp;</td></tr>";
					for($i=1; $i<sizeof($_SESSION['DIVISION']['CUENTA']); $i++)
					{
									$html .= "   <tr>";
									$datPlan=$this->LlamaNombrePlan($_SESSION['DIVISION']['CUENTA'][$i]['plan']);
									$html .= "          <td class=\"label\">CUENTA GENERADA No. ".$_SESSION['DIVISION']['CUENTA'][$i]['cuenta']." - PLAN ".$datPlan['plan_descripcion']."</td>";
									$html .= "   </tr>";
					}
					$html .= "    <tr>";
					$accion=ModuloGetURL('app','Cuentas','user','LlamaNuevoResponsable',array('Responsable'=>$plan,'descripcion_plan'=>$descripcion,'Cuenta'=>$_REQUEST['Cuenta'],"indice"=>$_REQUEST['indice'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
					$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CONTINUAR\"></td>";
					$html .= "</form>";
					$html .= "          </tr>";
					$html .= "</table>";
					$html .= ThemeCerrarTabla();
					return $html;
	}

  /**
  *
  */
	function FormaCuentasDivision()
	{
					$html = ThemeAbrirTabla('CUENTAS - CUENTAS GENERADAS DE LA DIVISION DE LA CUENTA No. '.$_SESSION['DIVISION']['CUENTA'][0]['cuenta']);
					$html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
					$html .= "   <tr>";
					$html .= "          <td align=\"center\" class=\"label_mark\" colspan=\"2\">CUENTAS GENERADAS DESPUES DE LA DIVISION (Todas las cuentas estan inactivas)</td>";
					$html .= "   </tr>";
					$html .= "   <tr><td colspan=\"2\">&nbsp;</td></tr>";
					$html .= "          <tr class=\"modulo_table_list_title\">";
					$html .= "          <td align=\"center\" colspan=\"2\">ELIGA UNA CUENTA PARA SER ACTIVADA</td>";
					$html .= "          </tr>";
					$accion=ModuloGetURL('app','Cuentas','user','LlamaActivarCuentaDivision',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
					$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$html .= "   <tr>";
					$html .= "          <td width=\"92%\" class=\"label\">CUENTA INICIAL ".$_SESSION['DIVISION']['CUENTA'][0]['cuenta']."</td>";
					$html .= "        <td width=\"8%\" align=\"center\"><input type=\"radio\" name=\"CuentaA\" value=\"".$_SESSION['DIVISION']['CUENTA'][0]['cuenta']."\"></td>";
					$html .= "   </tr>";
					for($i=1; $i<sizeof($_SESSION['DIVISION']['CUENTA']); $i++)
					{
									$html .= "   <tr>";
									$datPlan=$this->LlamaNombrePlan($_SESSION['DIVISION']['CUENTA'][$i]['plan']);
									$html .= "          <td class=\"label\">CUENTA GENERADA No. ".$_SESSION['DIVISION']['CUENTA'][$i]['cuenta']." - PLAN ".$datPlan['plan_descripcion']."</td>";
									$html .= "        <td align=\"center\"><input type=\"radio\" name=\"CuentaA\" value=\"".$_SESSION['DIVISION']['CUENTA'][$i]['cuenta']."\"></td>";
									$html .= "   </tr>";
					}
					$html .= "</table>";
					$html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
					//unset($_SESSION['DIVISION']['CUENTA']);
					$html .= "    <tr>";
					$html .= "        <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACTIVAR CUENTA\"></td>";
					$html .= "</form>";
					//$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$_REQUEST['Cuenta'],'TipoId'=>$_REQUEST['TipoId'],'PacienteId'=>$_REQUEST['PacienteId'],'Nivel'=>$_REQUEST['Nivel'],'PlanId'=>$_REQUEST['PlanId'],'Cama'=>$_REQUEST['Cama'],'Fecha'=>$_REQUEST['Fecha'],'Ingreso'=>$_REQUEST['Ingreso']));
					$accion= SessionGetVar("AccionVolverCargosIYM");
					$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER A LA CUENTA INICIAL\"></td>";
					$html .= "</form>";
					$html .= "          </tr>";
					$html .= "</table>";
					$html .= ThemeCerrarTabla();
					return $html;
	}

	/**
	*
	*/
	function ActivarCuentaDivision()
	{
			if(empty($_REQUEST['CuentaA']))
			{
					$this->frmError["MensajeError"]="Debe Elegir una Cuenta Para Activar.";
					$html = $this->FormaActivarCuentaDivision($_REQUEST['PlanId'],$_REQUEST['Cuenta1'],$_REQUEST['Cuenta'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
					return $html;
			}
				
			$fact = new OpcionesCuentas(); 
			$html = $fact->ActivarCuenta($_REQUEST['PlanId'],$_REQUEST['CuentaA'],$_REQUEST['TipoId'],$_REQUEST['PacienteId'],$_REQUEST['Ingreso'],$_REQUEST['Nivel'],$_REQUEST['Fecha']);
			return $html;
	}

  /**
  *
  */
  function Todos()
  {
      $script = "<SCRIPT>";
      $script .= "function Todos(frm,x){";
      $script .= "  if(x==true){";
      $script .= "    for(i=0;i<frm.elements.length;i++){";
      $script .= "      if(frm.elements[i].type=='checkbox'){";
      $script .= "        frm.elements[i].checked=true";
      $script .= "      }";
      $script .= "    }";
      $script .= "  }else{";
      $script .= "    for(i=0;i<frm.elements.length;i++){";
      $script .= "      if(frm.elements[i].type=='checkbox'){";
      $script .= "        frm.elements[i].checked=false";
      $script .= "      }";
      $script .= "    }";
      $script .= "  }";
      $script .= "}";
      $script .= "</SCRIPT>";
      return $script;
  }

  /**
  **FormaEquivalencias
  **/
  function FormaEquivalencias($PlanId,$Cuenta,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha,$Responsable)
  {
       if($_REQUEST[Responsable])
       {$Responsable = $_REQUEST[Responsable];}
        $Nombres=$this->LlamaBuscarNombresPaciente($TipoId,$PacienteId);
        $Apellidos=$this->LlamaBuscarApellidosPaciente($TipoId,$PacienteId);
        $html = ThemeAbrirTabla('CUENTAS -  EQUIVALENCIAS DE CARGOS CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
        //$this->EncabezadoEmpresa($Caja);
        $_REQUEST=$_SESSION['CUENTA']['REQUEST'];
        $argu=array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
        //$html .= $this->Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta);
        $act = $this->LlamaDetalleCambioACtual($PlanId,$Cuenta);
        $html .= $this->Todos();
        $html .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "     </table>";
        $accion=ModuloGetURL('app','Cuentas','user','LlamaInsertarNuevoPlan',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Nuevo_Responsable'=>$Responsable));
        $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $html .= "    <br> <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "          <tr class=\"modulo_table_list_title\">";
        $datPlan=$this->LlamaNombrePlan($PlanId);
        $html .= "              <td width=\"50%\" colspan=\"5\">PLAN ACTUAL: ".$datPlan['plan_descripcion']."</td>";
        //$datPlan=$this->LlamaNombrePlan($_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
        $datPlan=$this->LlamaNombrePlan($Responsable);
        $html .= "              <td width=\"50%\" colspan=\"4\">PLAN NUEVO: ".$datPlan['plan_descripcion']."</td>";
        $html .= "          </tr>";
        $html .= "          <tr class=\"modulo_table_list_title\">";
        $html .= "              <td width=\"9%\">TARIFARIO</td>";
        $html .= "              <td width=\"9%\">CARGO</td>";
        $html .= "              <td width=\"9%\">CODIGO</td>";
        $html .= "              <td width=\"4%\">CANTIDAD</td>";
        $html .= "              <td width=\"29%\">DESCRIPCION</td>";
        $html .= "              <td width=\"9%\">TARIFARIO</td>";
        $html .= "              <td width=\"9%\">CARGO</td>";
        $html .= "              <td width=\"30%\">DESCRIPCION</td>";
        $html .= "              <td width=\"4%\"><input type=\"checkbox\" name=\"Todo\" onClick=\"Todos(this.form,this.checked)\"></td>";
        $html .= "          </tr>";
        $html .= "          <tr>";
        //actual
        $d = 0;
        $f = 0;
        $html .= "              <td colspan=\"9\" width=\"100%\">";
        $html .= "                <table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
        for($i=0; $i<sizeof($act); $i++)
        {
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }
            $html .= "                    <tr class=\"$estilo\">";
            $html .= "                        <td width=\"8%\" align=\"center\">".$act[$i][tarifario_id]."</td>";
            $html .= "                        <td width=\"9%\" align=\"center\">".$act[$i][cargo]."</td>";
            $html .= "                        <td width=\"8%\" align=\"center\">".$act[$i][codigo_producto]."</td>";
            $html .= "                        <td width=\"6%\" align=\"center\">".FormatoValor($act[$i][cantidad])."</td>";
            $html .= "                        <td width=\"28%\">".$act[$i][descripcion]."</td>";
            //equivalencias
            $html .= "                        <td>";
            $new=$this->LlamaEquivalencias($PlanId,$Cuenta,$act[$i][cargo],$act[$i][tarifario_id],$Responsable);
            $html .= "                          <table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
                        for($j=0; $j<sizeof($new); $j++)
                        {
                                if($j % 2) {  $estilo="modulo_list_claro";  }
                                else {  $estilo="modulo_list_oscuro";   }
                                $cont=0;
                                //$cont=$this->LlamaValidarContratoEqui($new[$j][tarifarionew],$new[$j][cargonew],$_SESSION['CUENTA']['CAMBIO']['nuevo_plan']);
                                $cont=$this->LlamaValidarContratoEqui($new[$j][tarifarionew],$new[$j][cargonew],$Responsable);
                                $html .= "    <input type=\"hidden\" name=\"Cambio\" value=\"".$act[$i][cambio_responsable_id]."\">";
                                if(!empty($cont))
                                {
                                        $html .= "                                <tr class=\"$estilo\">";
                                        if((!empty($new[$j][gruponew]) AND !empty($new[$j][subnew]))
                                        AND (!empty($new[$j][cargonew]) AND !empty($new[$j][tarifarionew])) AND $cont>0)
                                        {
                                                $html .= "                                  <td width=\"11%\" align=\"center\">".$new[$j][tarifarionew]."</td>";
                                                $html .= "                                  <td width=\"11%\" align=\"center\">".$new[$j][cargonew]."</td>";
                                                $html .= "                                  <td width=\"31%\">".$new[$j][desnew]."</td>";
                                                $x="New".$i.$new[$j][tarifarionew].$new[$j][cargonew].$act[$i][tarifario_id].$act[$i][cargo];
                                                $z=$act[$i][cambio_responsable_detalle_actual_id].",".$new[$j][tarifarionew].",".$new[$j][cargonew].",".$act[$i][tarifario_id].",".$act[$i][cargo];

                                                if($_REQUEST[$x] == $z)
                                                {
                                                        $html .= "                                  <td width=\"1%\" align=\"center\"><input type=\"checkbox\" value=\"".$act[$i][cambio_responsable_detalle_actual_id].",".$new[$j][tarifarionew].",".$new[$j][cargonew].",".$act[$i][tarifario_id].",".$act[$i][cargo]."\" name=\"New".$i.$new[$j][tarifarionew].$new[$j][cargonew].$act[$i][tarifario_id].$act[$i][cargo]."\" checked></td>";
                                                }
                                                else
                                                {
                                                        $html .= "                                  <td width=\"1%\" align=\"center\"><input type=\"checkbox\" value=\"".$act[$i][cambio_responsable_detalle_actual_id].",".$new[$j][tarifarionew].",".$new[$j][cargonew].",".$act[$i][tarifario_id].",".$act[$i][cargo]."\" name=\"New".$i.$new[$j][tarifarionew].$new[$j][cargonew].$act[$i][tarifario_id].$act[$i][cargo]."\"></td>";
                                                }
                                                $f++;
                                        }
                                        elseif((!empty($new[$j][gruponew]) AND !empty($new[$j][subnew]))
                                        AND (empty($new[$j][cargonew]) AND empty($new[$j][tarifarionew])))
                                        {  $d++;echo 1;
                                                $html .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">No Existen Equivalencias</td>";
                                        }
                                        elseif((empty($new[$j][gruponew]) AND empty($new[$j][subnew]))
                                        AND (!empty($new[$j][cargonew]) AND !empty($new[$j][tarifarionew])))
                                        {$d++;echo 2;
                                                $html .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">El Cargo Equivalente No esta Contratado</td>";
                                        }
                                        elseif((empty($new[$j][gruponew]) AND empty($new[$j][subnew]))
                                        AND (empty($new[$j][cargonew]) AND empty($new[$j][tarifarionew])))
                                        {$d++;echo 3;
                                                $html .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">El Grupo y SubGrupo al que Pertenece el Cargo no estan Contratados</td>";
                                        }
                                        elseif((!empty($new[$j][gruponew]) AND !empty($new[$j][subnew]))
                                        AND (!empty($new[$j][cargonew]) AND !empty($new[$j][tarifarionew])) AND empty($cont))
                                        {$d++;echo 3;
                                                $html .= "                                  <td align=\"center\" class=\"label_error\" colspan=\"4\">El Grupo y SubGrupo al que Pertenece el Cargo no estan Contratados</td>";
                                        }
                                        $html .= "                                </tr>";
                                }elseif(!empty($act[$i][codigo_producto])){
                  $html .= "                                  <input type=\"hidden\" value=\"".$act[$i][cambio_responsable_detalle_actual_id].",".$act[$i][tarifario_id].",".$act[$i][cargo]."\" name=\"New$i\" checked></td>";
                }
            }//segund0 for
            $html .= "                            </table>";
            $html .= "                        </td>";
            //fin equivalencias
            $html .= "                    </tr>";
        }
        $html .= "                  </table>";
        $html .= "              </td>";
        $html .= "          </tr>";
        $html .= "          </table>";
        $html .= "    <input type=\"hidden\" name=\"Cant\" value=\"$f\">";
        //si hay cargos sin equivalencias no se puede cambiar de plan
        if($d > 0)
        {
            $html .= "</form>";
            $html .= "    <br> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            $html .= "          <tr class=\"label_error\">";
            $html .= "              <td align=\"center\">NO SE PUEDE CAMBIAR AL NUEVO PLAN: Existen $d Cargos que no Tienen Equivalencias o No han sido Contratados en el Nuevo Plan.</td>";
            $html .= "          </tr>";
            //$accion=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
            $accion = SessionGetVar("AccionVolverCargosIYM");
            $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $html .= "          <tr>";
            $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></td>";
            $html .= "</form>";
            $html .= "          </tr>";
            $html .= "          </table>";
        }
        else
        {
            $html .= "    <br> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            $html .= "          <tr>";
            $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
            $html .= "</form>";
/*                        if(!empty($_SESSION['CUENTA']['DIVISION']))
            {  $accion=ModuloGetURL('app','Facturacion','user','CancelarCambio',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));  }
                        else
                        {  $accion=ModuloGetURL('app','Facturacion','user','CancelarCambio',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));  }*/
            $accion = SessionGetVar("AccionVolverCargosIYM");
            $html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
            $html .= "          </tr>";
            $html .= "          </table>";
        }
        $html .= ThemeCerrarTabla();
        return $html;
  }

  /**
  ***
  **/
  function LlamaEquivalencias($PlanId,$Cuenta,$cargo,$tarifario_id,$Nuevo_Responsable)
	{
		$fact = new OpcionesCuentas();
		$dat = $fact->Equivalencias($PlanId,$Cuenta,$cargo,$tarifario_id,$Nuevo_Responsable);
		return $dat;
	}

  /**
  ***
  **/
  function LlamaValidarContratoEqui($tarifario,$cargo,$plan)
	{
		$fact = new OpcionesCuentas();
		$dat = $fact->ValidarContratoEqui($tarifario,$cargo,$plan);
		return $dat;
	}

  /**
  ***
  **/
  function LlamaBuscarNombresPaciente($TipoId,$PacienteId)
	{
		$fact = new OpcionesCuentas();
		$dat = $fact->BuscarNombresPaciente($TipoId,$PacienteId);
		return $dat;
	}

  function LlamaDetalleCambioACtual($PlanId,$Cuenta)
	{
		$fact = new OpcionesCuentas();
		$dat = $fact->DetalleCambioACtual($PlanId,$Cuenta);
		return $dat;
	}

  /**
  ***
  **/
  function LlamaBuscarApellidosPaciente($TipoId,$PacienteId)
	{
		$fact = new OpcionesCuentas();
		$dat = $fact->BuscarApellidosPaciente($TipoId,$PacienteId);
		return $dat;
	}
  /**
  * Muestra los datos del responsable(tercero) del paciente y los datos basicos del paciente
  * nombres, identificacion,numero de ingreso y la fecha y hora de apertura de la cuenta.
  * @access private
  * @return void
  * @param int plan_id
  * @param string tipo documento
  * @param int numero documento
  * @param int ingreso
  * @param string nivel
  * @param date fecha de registro de la cuenta
  */
    function Encabezado($PlanId,$TipoId,$PacienteId,$Ingreso,$Nivel,$Fecha,$argu,$Cuenta)
    {
        $datos=$this->LlamaCuentaParticular($Cuenta,$PlanId);
        if(!$datos)
        {
            $datos=$this->LlamaBuscarPlanes($PlanId,$Ingreso);
            $Responsable=$datos[nombre_tercero];
            $ident=$datos[tipo_id_tercero].' '.$datos[tercero_id];
        }
                $afi=$this->LlamaBuscarTipoAfiliado($Cuenta);
        //$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
        //$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
        $Nombre=$this->LlamaBuscarNombreCompletoPaciente($TipoId,$PacienteId);
        $Fecha1=$this->FechaStamp($Fecha);
        $Hora=$this->HoraStamp($Fecha);

        $html = "  <table border=\"0\" width=\"90%\" align=\"center\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"45%\">\n";
        $html .= "              <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\" >\n";
        $html .= "                  <tr>\n";
        $html .= "                      <td valign=\"top\">\n";
        $html .= "                          <fieldset><legend class=\"field\">RESPONSABLE</legend>\n";
        $html .= "                              <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">\n";
        $html .= "                                  <tr><td class=\"label\" width=\"24%\">RESPONSABLE: </td><td>$Responsable</td></tr>\n";
        $html .= "                                  <tr><td class=\"label\" width=\"24%\">IDENTIFICACION: </td><td>".$ident."</td></tr>\n";
        $html .= "                                  <tr><td class=\"label\" width=\"24%\">PLAN: </td><td>".$datos[plan_descripcion]."</td></tr>\n";
        $html .= "                                  <tr><td class=\"label\" width=\"24%\">TIPO AFILIADO: </td><td>".$afi[tipo_afiliado_nombre]."</td></tr>\n";
        $html .= "                                  <tr><td class=\"label\" width=\"24%\">RANGO: </td><td>".$afi[rango]."</td></tr>\n";
        if(!empty($datos[protocolos]))
        {
            if(file_exists("protocolos/".$datos[protocolos].""))
            {
                $Protocolo=$datos[protocolos];
                $html .= "  <script>\n";
                $html .= "      function Protocolo(valor)\n";
                $html .= "      {\n";
                $html .= "          window.open('protocolos/'+valor,'PROTOCOLO','');\n";
                $html .= "      }\n";
                $html .= "  </script>\n";
                $accion = "javascript:Protocolo('$datos[protocolos]')";
                $html .= "                              <tr><td class=\"label\" width=\"24%\">PROTOCOLO: </td><td><a href=\"$accion\">$Protocolo</a></td></tr>\n";
            }
        }
        if(!empty($argu))
        {
            $accion=ModuloGetURL('app','Facturacion','user','VerAutorizaciones',$argu);
            $html .= "<tr><td class=\"label\">AUTORIZACIONES: </td> ";
            $html .= "<td align=\"left\"><a href=\"$accion\">Ver Autorizaciones Plan</a></td></tr> ";
        }
        if($datos[sw_tipo_plan]==1)
        {
            if($datos[saldo]<=0)
            {
                $html .= "                              <tr><td class=\"label_error\" width=\"24%\">SALDO SOAT ($): </td><td>".$datos[saldo]."</td></tr>";
            }
            else
            {
                $html .= "                              <tr><td class=\"label\" width=\"24%\">SALDO SOAT ($): </td><td>".$datos[saldo]."</td></tr>";
            }
        }
        $html .= "                              </table>\n";
        $html .= "                      </fieldset>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "          </table>\n";
        $html .= "       </td>";
        $html .= "       <td width=\"45%\">";
        $html .= "          <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">";
        $html .= "              <tr>\n";
        $html .= "                  <td valign=\"top\">\n";
        $html .= "                      <fieldset><legend class=\"field\">DATOS DEL PACIENTE</legend>\n";
        $html .= "                          <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">\n";
        $html .= "                              <tr><td class=\"label\" width=\"35%\">PACIENTE: </td><td>$Nombre</td></tr>\n";
        $html .= "                              <tr><td class=\"label\">IDENTIFICACION: </td><td>$TipoId  $PacienteId</td></tr>\n";
        $html .= "                              <tr><td class=\"label\">No. INGRESO: </td><td>$Ingreso</td></tr>\n";
        $html .= "                              <tr><td class=\"label\">FECHA APERTURA: </td><td>$Fecha1</td></tr>\n";
        $html .= "                              <tr><td class=\"label\">HORA APERTURA: </td><td>$Hora</td></tr>\n";
        $html .= "                          </table>\n";
        $html .= "                      </fieldset>\n";
        $html .= "                  </td>\n";
        $html .= "              </tr>\n";
        $html .= "          </table>";
        $html .= "       </td>";
        $html .= "    </tr>";
        $html .= "  </table>\n";
        return $html;
    }

     /**
      * Se encarga de recibir la fecha en formato dd/mm/aaaa y devolverla en aaaa-mm-dd
      * @access private
      * @return string
      * @param date fecha
      */
     function FechaFormato($fecha)
     {
       if($fecha){
          $fech = explode("/",$fecha);
          return  $fech[2]."-".$fech[1]."-".$fech[0];
       }
     }
     
		 /**
      * Se encarga de separar la fecha del formato timestamp
      * @access private
      * @return string
      * @param date fecha
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
          //return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
          return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
       }
     }

     /**
      * Se encarga de separar la hora del formato timestamp
      * @access private
      * @return string
      * @param date hora
      */
      function HoraStamp($hora)
      {
        $hor = strtok ($hora," ");
        for($l=0;$l<4;$l++)
        {
          $time[$l]=$hor;
          $hor = strtok (":");
        }
            $x=explode('.',$time[3]);
        return  $time[1].":".$time[2].":".$x[0];
      }

  /**
  * Crear el combo de tipos de afiliados
  * @access private
  * @return string
  * @param array arreglo con los tipos de afiliados
  * @param int tipo de afiliado
  */
  function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
  {
        $html =" <option value=\"-1\">---Seleccione---</option>";
        for($i=0; $i<sizeof($tipo_afiliado); $i++)
        {
          if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
           $html .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
          else{
           $html .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
        }
     return $html;
  }

		/**
		**
		**/
		function LlamaTipo_Afiliado($Responsable)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->Tipo_Afiliado($Responsable);
			return $dat;
		}

		/**
		**
		**/
		function LlamaNiveles($Responsable)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->Niveles($Responsable);
			return $dat;
		}

		/**
		**
		**/
		function LlamaDepartamentos($EmpresaId,$CU)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->Departamentos($EmpresaId,$CU);
			return $dat;
		}

		/**
		**
		**/
		function LlamaNombreTercero($tipo_tercero_id,$tercero_id)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->NombreTercero($tipo_tercero_id,$tercero_id);
			return $dat;
		}
		/**
		**
		**/
		function LlamaNombrePlan($PlanId)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->NombrePlan($PlanId);
			return $dat;
		}

		/**
		**
		**/
		function Llamaresponsables()
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->responsables();
			return $dat;
		}

		/**
		**
		**/
		function LlamaBuscarSolicitudesDescuentos()
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->BuscarSolicitudesDescuentos();
			return $dat;
		}

		/**
		**
		**/
		function LlamaBuscarDescuentosCuenta($Cuenta,$Tipo)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->BuscarDescuentosCuenta($Cuenta,$Tipo);
			return $dat;
		}

		/**
		**
		**/
		function LlamaCuentaParticular($Cuenta,$PlanId)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->CuentaParticular($Cuenta,$PlanId);
			return $dat;
		}

		/**
		**
		**/
		function LlamaBuscarPlanes($PlanId,$Ingreso)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->BuscarPlanes($PlanId,$Ingreso);
			return $dat;
		}

		/**
		**
		**/
		function LlamaBuscarTipoAfiliado($Cuenta)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->BuscarTipoAfiliado($Cuenta);
			return $dat;
		}

		/**
		**
		**/
		function LlamaBuscarNombreCompletoPaciente($TipoId,$PacienteId)
		{
			$fact = new OpcionesCuentas();
			$dat = $fact->BuscarNombreCompletoPaciente($TipoId,$PacienteId);
			return $dat;
		}
    /**
    * Metodo donde se crea la lista de grupos cargos, en los que se puede aplicar descuentos
    *
    * @param array $action Arreglo de datos con los links de la aplicacion
    * @param string $empresa Identificador de la empresa
    * @param integer $cuenta Identificador de la cuenta
    * @param integer $detalle Arreglo de datos con el detalle de la cuenta
    *
    * @return string
    */
    function FormaListarGruposCargos($action,$empresa,$cuenta,$detalle)
		{
      $ctl = Autocarga::factory("ClaseUtil");
      $html  = ThemeAbrirTabla("DESCUENTOS CUENTA No.".$cuenta);
      $html .= $ctl->AcceptNum();
      $html .= "<script>\n";
      $html .= "  function ActivarCampo(check, valor)\n";
      $html .= "  {\n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('descuento_empresa_'+valor).disabled = !check;\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('descuento_paciente_'+valor).disabled = !check;\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      $html .= "  function EvaluarDatosDescuento()\n";
      $html .= "  {\n";
      $html .= "    xajax_EvaluarDatosDescuento(xajax.getFormValues('descuentos'));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= "<form name=\"descuentos\" id=\"descuentos\" action=\"javascript:EvaluarDatosDescuento()\" method=\"post\">";
      $html .= "  <input type=\"hidden\" name=\"numerodecuenta\" value=\"".$cuenta."\">\n";
      $html .= "  <input type=\"hidden\" name=\"empresa_id\" value=\"".$empresa."\">\n";
      $html .= "  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "    <tr align=\"center\" class=\"formulacion_table_list\">";
      $html .= "      <td width=\"38%\">GRUPO CARGO</td>\n";
      $html .= "      <td width=\"15%\">VALOR EMPRESA</td>\n";
      $html .= "      <td width=\"15%\">DESC. EMPRESA</td>\n";
      $html .= "      <td width=\"15%\">VALOR PACIENTE</td>\n";
      $html .= "      <td width=\"15%\">DESC. PACIENTE</td>\n";
      $html .= "      <td width=\"2%\" >OP</td>\n";
      $html .= "    </tr>\n";
      foreach($detalle as $key => $dtl)
      {
        $est = ($est == "modulo_list_oscuro")? "modulo_list_claro":"modulo_list_oscuro";
        $html .= "    <tr class=\"".$est."\">\n";
        $html .= "      <td class=\"normal_10AN\">".$dtl['descripcion']."</td>\n";
        $html .= "      <td align=\"right\" class=\"label\">".FormatoValor($dtl['valor_cubierto'])."</td>\n";
        $html .= "      <td>\n";
        if($dtl['valor_cubierto'] > 0)
          $html .= "        <input type=\"text\" class=\"input-text\" value=\"".$dtl['por_descuento_empresa']."\" name=\"descuento_empresa[".$dtl['codigo_agrupamiento_id']."]\" id=\"descuento_empresa_".$dtl['codigo_agrupamiento_id']."\" style=\"width:80%\" onkeypress=\"return acceptNum(event)\" ".(($dtl['por_descuento_empresa'] != "")? "":"disabled")."><b>%</b>\n";
        $html .= "      </td>\n";        
        $html .= "      <td align=\"right\" class=\"label\">".FormatoValor($dtl['valor_nocubierto'])."</td>\n";
        $html .= "      <td>\n";
        if($dtl['valor_nocubierto'] > 0)
          $html .= "        <input type=\"text\" class=\"input-text\" value=\"".$dtl['por_descuento_paciente']."\" name=\"descuento_paciente[".$dtl['codigo_agrupamiento_id']."]\" id=\"descuento_paciente_".$dtl['codigo_agrupamiento_id']."\" style=\"width:80%\" onkeypress=\"return acceptNum(event)\" ".(($dtl['por_descuento_paciente'] != "")? "":"disabled")."><b>%</b>\n";
        $html .= "      </td>\n";
        $html .= "      <td align=\"center\">\n";
        $check = "";
        if($dtl['por_descuento_empresa'] != "" || $dtl['por_descuento_paciente'] != "")
          $check = "checked";
        $html .= "        <input type=\"hidden\" name=\"descripcion[".$dtl['codigo_agrupamiento_id']."]\" value=\"".$dtl['descripcion']."\">\n";
        $html .= "        <input type=\"checkbox\" name=\"grupo[".$dtl['codigo_agrupamiento_id']."]\" value=\"".$dtl['codigo_agrupamiento_id']."\" onclick=\"ActivarCampo(this.checked,this.value)\" ".$check.">\n";
        $html .= "      </td>\n";
        $html .= "    </tr>\n";
      }
      $html .= "  </table>\n";
      $html .= "  <center>\n";
      $html .= "    <div id=\"error\"></div>\n";
      $html .= "  </center>\n";
      $html .= "  <table width=\"70%\" align=\"center\" >\n";
      $html .= "    <tr align=\"center\">\n";
      $html .= "      <td width=\"50%\" >\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"acepatar\" value=\"Aceptar\">\n";
      $html .= "      </td>\n";
      $html .= "</form>\n";
      $html .= "<form name=\"form_volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "      <td>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "</form>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= ThemeCerrarTabla();
      return $html;
		}
	}
?>