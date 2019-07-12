<?php

/**
 * $Id: app_EE_AdministracionMedicamentosQX_userclasses_HTML.php,v 1.4 2006/03/29 23:35:03 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_AdministracionMedicamentosQX_userclasses_HTML extends app_EE_AdministracionMedicamentosQX_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_AdministracionMedicamentosQX_user_HTML()
     {
          $this->app_EE_AdministracionMedicamentosQX_user();
          $this->salida='';
          return true;
     }
     
     
     /**
     * Metodo Default
     *
     * @return boolean
     */
     function main()
     {
          $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $titulo='FALTA METODO EN EL LLAMADO';
          $mensaje='Este modulo requiere un METODO especifico y debe ser llamado desde la Estacion De Enfermeria.';
          $this->frmMSG($url, $titulo, $mensaje);
          return true;
     }
     
     
     /**
     * Forma para mostrar mensaje
     *
     * @param string $url opcional url de retorno
     * @param string $titulo opcional titulo de la ventana
     * @param string $mensaje opcional mensaje a mostrar
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function frmMSG($url='', $titulo='', $mensaje='', $link='')
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
          if(empty($link)) $link = "VOLVER";
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
          if($url)
          {
               $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
               $this->salida.="    <tr>\n";
               $this->salida.="        <td align='center' class=\"label_error\">\n";
               $this->salida.="            <a href='$url'>$link</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     /**
     * Forma para mostrar la cabecera de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmDatosEstacion($datos)
     {
          $this->salida .= ThemeAbrirTabla("ESTACI&Oacute;N DE ENFERMERIA : ".$datos['estacion_descripcion']);
          $this->salida .= "<center>\n";
          $this->salida .= "    <table class='modulo_table_title' border='0' width='80%'>\n";
          $this->salida .= "        <tr class='modulo_table_title'>\n";
          $this->salida .= "            <td>Empresa</td>\n";
          $this->salida .= "            <td>Centro Utilidad</td>\n";
          $this->salida .= "            <td>Unidad Funcional</td>\n";
          $this->salida .= "            <td>Departamento</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "        <tr class='modulo_list_oscuro'>\n";
          $this->salida .= "            <td>".$datos['empresa_descripcion']."</td>\n";
          $this->salida .= "            <td>".$datos['centro_utilidad_descripcion']."</td>\n";
          $this->salida .= "            <td>".$datos['unidad_funcional_descripcion']."</td>\n";
          $this->salida .= "            <td>".$datos['departamento_descripcion']."</td>\n";
          $this->salida .= "        </tr>\n";
          $this->salida .= "    </table>\n";
          $this->salida .= "</center>\n";
          return true;
     }
     
     
     /**
     * Forma para mostrar el pie de pagina de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmPieDePagina()
     {
          $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
     
          $this->salida .= "<center>\n";
          $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
          $this->salida.="    <tr>\n";
          $this->salida.="        <td align='center' class=\"label_error\">\n";
          $this->salida.="            <a href='$url'>VOLVER</a>\n";
          $this->salida.="        </td>\n";
          $this->salida.="    </tr>\n";
          $this->salida.="  </table>\n";
          $this->salida .= "</center>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }
     
     
     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError"){
               if ($campo=="MensajeError"){
                    $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
     	     return ("label_error");
     	}
     	return ("label");
	}
     
     
     /**
     * Forma para el ingreso de un paciente a la estacion.
     *
     * @return boolean True si se ejecuto correctamente
     * @access public
     */
     function CallFrmMedicamentos($datosPaciente,$datos_estacion)
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('52'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Solicitud de Insumos y Medicamentos (Pacientes) [52]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          //Vector que contiene los datos del paciente internado.
          if(empty($datosPaciente))
          	$datosPaciente = $_REQUEST['datosPaciente'];

          if($datosPaciente===false)
          {
               if(empty($this->error))
               {
                    $this->error = "EE_AdministracionMedicamentosQX - FrmMedicamentos - 52";
                    $this->mensajeDeError = "El metodo FrmMedicamentos() retorno false.";
               }
               return false;
          }
          elseif(!is_array($datosPaciente))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PACIENTE';
               if(empty($datosPaciente))
               {
                    $mensaje = "No se pudo obtener los datos del paciente a INGRESAR.";
               }
               else
               {
                    $mensaje = $datosPaciente;
               }
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          unset($_SESSION['codigos_I']);
          unset($_SESSION['cantidad_a_perdi_sol_I']);
          unset($_SESSION['Interna']);

		if(empty($datos_estacion))
          $datos_estacion = &$this->GetdatosEstacion();

          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }
     
          //CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
          $this->FrmDatosEstacion($datos_estacion);

          $this->FrmMedicamentos($datos_estacion,$datosPaciente);
          //$this->FrmAciones_Medicamentos($datos_estacion,$datosPaciente);   
          
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
          return true;
     }
     
     /*
     * Forma que muestra informacion acerca de los medicamentos
     * pendientes por suministrar al paciente.
     *
     * Adaptacion Tizziano Perea.
     */
     function FrmMedicamentos($datos_estacion,$datosPaciente)
     {
		unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
		unset ($_SESSION['MEDICAMENTOS'.$pfj]);
		unset ($_SESSION['POSOLOGIA4'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
		unset ($_SESSION['JUSTIFICACION'.$pfj]);
		unset ($_SESSION['MODIFICANDO'.$pfj]);
		unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);
		unset ($_SESSION['MEDICAMENTOSM'.$pfj]);
		unset ($_SESSION['EXISTENCIA']);//session q tiene el vector de seleccion de insumos
		unset($_SESSION['MEDICA_DATOS_SOL_PAC']);//session q guarda las observaciones y el nombre 
		//al cual le solicitaron los insumos del paciente.
		unset($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']);//vector de productos seleccionados(control suministro)
          unset($_SESSION['MEDICINAS']);

          $vector1 = $this->Consulta_Solicitud_Medicamentos($datosPaciente[programacion_id]);
          $_SESSION['MEDICINAS'] = $vector1;
          $href = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','Control_Suministro',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
          $this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
          $this->salida .= "<SCRIPT>";
          $this->salida .= "function chequeoTotal(frm,x){";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "      if(frm.elements[i].disabled==false){";
          $this->salida .= "        frm.elements[i].checked=true";
          $this->salida .= "      }";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=false";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
          $this->salida .= "</SCRIPT>";
          $this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_title\">\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          $this->salida .= "		<td>PACIENTE</td>\n";
          $this->salida .= "		<td>IDENTIFICACIÓN</td>\n";
          $this->salida .= "		<td>CUENTA</td>\n";
          $this->salida .= "		<td>INGRESO</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[numerodecuenta]."</td>\n";
          $this->salida .= "			<td>".$datosPaciente[ingreso]."</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "	</table><br>\n";

		$m = 0;
		if($vector1)
		{
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"3\" width=\"80%\">PLAN TERAPEUTICO - MEDICAMENTOS FORMULADOS</td>";
			$this->salida.="<td align=\"center\" colspan=\"3\" width=\"20%\">MEDICAMENTOS A SUMINISTRAR</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"15%\">CODIGO</td>";
			$this->salida.="  <td width=\"35%\">PRODUCTO</td>";
			$this->salida.="  <td width=\"30%\">PRINCIPIO ACTIVO</td>";
			$this->salida.="  <td  width=\"14%\" colspan=\"2\">CANTIDAD</td>";
			$this->salida.="  <td  width=\"5%\"><input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
			$this->salida.="</tr>";
			
               for($i=0;$i<sizeof($vector1);$i++)
			{
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";

                    if($vector1[$i][item] == 'NO POS')
                    {
                         $this->salida.="  <td align=\"center\" width=\"15%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"center\" width=\"15%\">".$vector1[$i][codigo_producto]."</td>";
                    }
                    
                    $this->salida.="  <td align=\"left\" width=\"35%\">".$vector1[$i][producto]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"30%\">".$vector1[$i][principio_activo]."</td>";
                    
                    $devueltos = $this->SumatoriaDevoluciones_QX($datosPaciente[programacion_id],$vector1[$i][codigo_producto]);
                    $suministrados = $this->SumatoriaSuministrados_QX($datosPaciente[programacion_id],$vector1[$i][codigo_producto]);
                    $Cantidad_real = ($vector1[$i][cantidad] - ($devueltos + $suministrados));
                    $this->salida.="  <td align=\"center\" width=\"14%\" colspan=\"2\"><input type='text' class='input-text' size='4' maxlength='4' name=cantidad[] value='".floor($Cantidad_real)."' readonly></td>";
                    $this->salida.="  <td width=\"5%\" align=\"center\"><input id=op$i  type=checkbox name=op[] value=".$vector1[$i][codigo_producto].",".urlencode($vector1[$i][principio_activo]).",".urlencode($vector1[$i][producto]).",".urlencode($vector1[$i][descripcion]).",".urlencode($vector1[$i][contenido_unidad_venta]).",".$Cantidad_real."></td>";
                    $this->salida.="</tr>";
               }
               $this->salida .= "<SCRIPT>\n";
               $this->salida .= "function compare(frm,x){\n";
               $this->salida .= "var cadena = new String();\n";
               $this->salida .= "var bandera=new Boolean(true);\n";
               $this->salida .= "    for(i=0;i<$contador_sys+1;i++){\n";
               $this->salida .= "cadena='';\n";
               $this->salida .= "cadena=document.getElementById(i).value;\n";
               $this->salida .= "arrayofstring=new Array();\n";
               $this->salida .= "arrayofstring=cadena.split(',');\n";
               $this->salida .= "for (var n=0; n < arrayofstring.length ; n++) {\n";
               $this->salida .= "if(arrayofstring[n]==x){\n";
               $this->salida .= "bandera=false;";
               $this->salida .= "break;\n";
               $this->salida .= "}";//fin if
               $this->salida .= "}\n";//fin 2do for
                    
               $this->salida .= "if(x=='*/*'){";
               $this->salida .= "document.getElementById('op'+i).disabled=false;\n";
               $this->salida .= "}else{";
                    
               $this->salida .= "if(bandera==true){";
               $this->salida .= "document.getElementById('op'+i).checked=false;\n";
               $this->salida .= "}";
               $this->salida .= "document.getElementById('op'+i).disabled=bandera;\n";
               $this->salida .= "}\n";//fin else
               
               $this->salida .= "}\n";//fin 1er for
               $this->salida .= "}\n";//fin funcion
               $this->salida .= "</SCRIPT>\n";
					 
               $this->salida.="<tr class=\"$estilo\">";
               $accion1 = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','FrmImpresionMedicamentos',array('ingreso'=>$datosPaciente[ingreso],"datos_estacion"=>$datosPaciente,"estacion"=>$datos_estacion));
               $this->salida.="<td colspan =\"6\" class=\"hc_table_submodulo_list_title\" width=\"63%\"><input type=\"submit\" class=\"ipunt-submit\" name=\"suministrar\" value=\"SUMINISTRAR\"></td>";
               $this->salida.="</tr>";

               //<duvan>  --> el link de solicitud de mediamentos.
               $this->salida.="<tr class=modulo_table_title>";

               if(UserGetUID()==0)
               {
                    $this->salida.="  <td colspan = 9 align=\"center\" width=\"80%\"><font color='white'>LA ESTACION ".$datos_estacion['estacion_descripcion']." &nbsp;ESTA EN MODO DE LECTURA</font></td>";
               }

               $this->salida.="</form></tr>";
               $this->salida.="</table><br>";
          }
          else
          {
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida.="<tr  align=\"center\"><td><label class='label_mark'>EL PACIENTE NO TIENE MEDICAMENTOS SOLICITADOS";
               $this->salida.="</tr></td></label>";
               $this->salida.="</table><br>";
          }
		$this->salida .= "</form>";
          if ($_SESSION['PROFESIONAL'.$pfj]!=1)
          {
          	if($m==2)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
          }

		if($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO'])
		{
			$href = ModuloGetURL($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo'],
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']);
		}
		else
		{
			 if(UserGetUID()==0)
			 {
                    $href = ModuloGetURL('app','EstacionEnfermeriaPlantilla','user','CallMenu',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			 }
			 else
			 {
		   		$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("control_id"=>$datos_estacion['control_id'],"estacion"=>$estacion,"control_descripcion"=>$datos_estacion['control_descripcion']));
			 }
		}
		return true;
	}
     
     
     /*
     * Forma que permite seleccionar alguna de las transacciones referentes a los
     * medicamentos.
     *
     * @autor Tizziano Perea
     */
     function FrmAciones_Medicamentos($datos_estacion,$datosPaciente)
     {
          $datosBodega = $this->GetEstacionBodega($datos_estacion,1);
          for ($i=0;$i<2;$i++)
          {
          	if($i==0)
               { 
                    $PacientesConOrdenes[0] = $this->GetPacientesConMedicamentosPorDesp($datosPaciente['ingreso'],'M',$datos_estacion['estacion_id'],'');
               }else
               {
                    $PacientesConOrdenes[1] = $this->GetPacientesConMedicamentosPorDesp($datosPaciente['ingreso'],'I',$datos_estacion['estacion_id'],'');               
               }
          }

          if(empty($PacientesConOrdenes[0]) AND empty($PacientesConOrdenes[1]))
          {
               $enlacepend = "Confirmación de Despacho";
               $imgpend='';
               $sw=0;
          }
          elseif($PacientesConOrdenes[0]==1 OR $PacientesConOrdenes[1]==1 )
		{
			$enlacepend = "Confirmación de Despacho";
               $_SESSION['Interna'] = true;
			$enlacepend = "<a href=\"".ModuloGetURL('app','EE_Insumos_y_Medicamentos','user','FrmShowBodega',array("datos_estacion"=>$datos_estacion,'switche'=>'despacho',"datosPaciente"=>$datosPaciente,"cargar"=>'admin')) ."\" target=\"Contenido\">Confirmacion Despacho: Insumos y Medicamentos Pendientes</a>";
			$imgpend = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
		}
		
          $insumos = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente))."\" target=\"Contenido\">Agregar Insumos</a>";
          $imgism = "<img src=\"".GetThemePath()."/images/EstacionEnfermeria/alarma.png\" border=0 width=12 heigth=12>";
              
          $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          $this->salida .= "		<td width=\"50%\">Insumos Y Medicamentos</td>\n";
          $this->salida .= "		<td width=\"50%\">Devoluciones</td>\n";
          $this->salida .= "		</tr>\n";          
          $this->salida .= "	<tr class=\"modulo_list_claro\">\n";
          $this->salida .= "		<td width=\"50%\">$imgpend&nbsp;$enlacepend</td>\n";
          
          $conteoI=$this->GetPacientesConMedicamentosPorDesp($datosPaciente['ingreso'],'I',$datos_estacion['estacion_id'],1);
          if($conteoI==1)
          {
               $devo_i = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionInsumos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$datosBodega))."\" target=\"Contenido\">Devolución Insumos</a>";
          }else{ $devo_i = "Devolución Insumos";}

          $this->salida .= "		<td width=\"50%\">$img&nbsp;$devo_i</td>\n";
          $this->salida .= "		</tr>\n";     
          $this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "		<td width=\"50%\">&nbsp;</td>\n";
          
          $conteo=$this->GetPacientesConMedicamentosPorDesp($datosPaciente['ingreso'],'M',$datos_estacion['estacion_id'],1);
          if($conteo==1)
          {
               $devo_m = "<a href=\"".ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmDevolucionMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$datosBodega))."\" target=\"Contenido\">Devolución Medicamentos</a>";
          }else{ $devo_m = "Devolución Medicamentos";}
          $this->salida .= "		<td width=\"50%\">$img&nbsp;$devo_m</td>\n";
          $this->salida .= "		</tr>\n";     
          $this->salida .= "	<tr class=\"modulo_table_title\">\n";
          $this->salida .= "		<td width=\"50%\">Solicitar Insumos Para Pacientes</td>\n";
          $this->salida .= "		<td rowspan=\"2\" align=\"center\" class=\"modulo_list_claro\" width=\"50%\"><b>ESTACI&Oacute;N DE ENFERMERIA : ".$datos_estacion['estacion_descripcion']."</b></td>\n";
          $this->salida .= "		</tr>\n";     
          $this->salida .= "	<tr class=\"modulo_list_claro\">\n";
          $this->salida .= "		<td width=\"50%\">$imgism&nbsp;$insumos</td>\n";
          $this->salida .= "		</tr>\n";     
          $this->salida .= "	</table><br>\n";
     	return true;
     }
     
     /*
     * Funcion que solicita los medicamentos enviados al
     * paciente, para estos ser despachados desde bodega
     *
     * Adaptacion Tizziano Perea
     */
     function ConfirmarSolicitud()
     {
          $bodega = $_REQUEST['bodega'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $op = $_REQUEST['op'];
          $cant = $_REQUEST['cantidad'];
          //$dat_op es el vecto separado por ',' donde esta el producto,principio activo, y el codigo.
     
          if($bodega=='-1')//por si entramos con el combo "SELECCIONE"
          {unset($op);}
          
          if(is_array($op))
          {
               $nom_bodega=$this->TraerNombreBodega($datos_estacion,$bodega);
               if($bodega=='*/*')
               {
                    $this->salida .= ThemeAbrirTabla("CONFIRMACION DE SOLICITUDES PARA EL PACIENTE");
               }
               else
               {
                    $this->salida .= ThemeAbrirTabla("CONFIRMACION DE SOLICITUDES DE MEDICAMENTOS A LA BODEGA &nbsp;".$nom_bodega."");
               }	
                    
               if($bodega=='*/*')
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertSolicitudMed_Para_Paciente',array("cantidad"=>$cant,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','InsertSolicitudMed',array("cantidad"=>$cant,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega));
               }
                    
               $this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td width=\"10%\">CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\">PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"30%\">PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\">CANT</td>\n";
               $this->salida .= "			<td width=\"5%\">EXIST</td>\n";
               $this->salida .= "			<td width=\"4%\"></td>\n";
               $this->salida .= "		</tr>\n";
               unset($vect);
               $k=0;

               $java ="<script>";
               $java.="function CambioValor(valor,frm,identi){";
               $java.=" vector=valor.split(',');";               
               $java.=" frm.codigo_producto_S[identi].value=vector[0];";
               $java.=" frm.cantidad[identi].value=vector[2];";		
               $java.="};";
               $java.="</script>";
               $this->salida.= $java;

                                   
               for($i=0;$i<=sizeof($cant);$i++)
               {
                    if(($op[$i]))
                    {
                         $dat_op=explode(",",$op[$i]);
                         $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
                         $this->salida .= "			<td width=\"10%\">".$dat_op[1]."</td>\n";
                         $this->salida .= "			<td width=\"25%\">".urldecode($dat_op[3])."</td>\n";
                         $this->salida .= "			<td width=\"30%\">".urldecode($dat_op[2])."</td>\n";
                         $cantidad_solicitada_medicamento = floor($cant[$i]);
                         $this->salida .= "			<td width=\"5%\">".$cantidad_solicitada_medicamento."</td>\n";
                         $existencia=$this->RevisarExistenciaBodega($datos_estacion,$bodega,$dat_op[1]);

                         if($existencia > 0)
                         {
                              $this->salida .= "			<td width=\"5%\">".FormatoValor($existencia)."</td>\n";
                         }else
                         {
                              $this->salida .= "			<td width=\"5%\"><label class=label_mark>No aplica</label></td>\n";
                         }

                         $this->salida .= "			<td width=\"4%\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>\n";

                         $vect[$k]="".$dat_op[1].",".$dat_op[4].",".floor($cant[$i])."";
                         $k++;
                         
                         $a = 0;
                         $arr_rel=$this->Revisar_Relacion_Medicamento_Bodegas($dat_op[1],$bodega);
                         if(is_array($arr_rel))
                         {
                              //parte de los insumos relacionados con los suministros q se hacen al paciente.
                              $this->salida.= "<tr class=\"$estilo\">";
                              $this->salida.= "<td colspan='6' width=\"10%\">\n";
                              $this->salida.= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\"\">\n";
                              for($y=0;$y<sizeof($arr_rel);$y++)
                              {
                                   if($y==0)
                                   {
                                        $this->salida .= "<tr class=\"modulo_list_table_title\">\n";
                                        $this->salida .= "<td colspan='4'>SOLICITUD DE INSUMOS RELACIONADOS CON MEDICAMENTOS</td>\n";
                                        $this->salida .= "</tr>\n";
                                        $this->salida .= "<tr class=\"modulo_list_table_title\">\n";
                                        $this->salida .= "<td width=\"40%\" align=\"center\">DESCRIPCION INSUMO</td>\n";
                                        $this->salida .= "<td width=\"13%\" align=\"center\">CODIGO</td>\n";
                                        $this->salida .= "<td width=\"13%\" align=\"center\">CANTIDAD</td>\n";
                                        $this->salida .= "<td width=\"4%\" align=\"center\">&nbsp;</td>\n";                                   
                                        $this->salida .= "</tr>\n";
                                   }	
                                   
                                   if($arr_rel[$y][codigo_agrupamiento] != $arr_rel[$y-1][codigo_agrupamiento])
                                   {	
                                        $this->salida .= "<tr align='center' class='modulo_list_claro'>\n";
                                        $this->salida .= "<td width=\"40%\" align=\"left\">";
                                        $this->salida .= "<select name=\"insumo_rel$y\" class=\"select\" Onchange=\"CambioValor(this.value,document.med,$a)\">";
                                        $relacion=$this->Revisar_Relacion_Medicamento_Bodegas($vect[codigo_producto],$bodega,'',$arr_rel[$y][codigo_agrupamiento]);
                                        for ($jj=0; $jj<sizeof($relacion); $jj++)
                                        {
                                             $this->salida .= "<option value=\"".$relacion[$jj][codigo_producto].",".$relacion[$jj][descripcion].",".$relacion[$jj][cantidad]."\">".$relacion[$jj][descripcion]."</option>";
                                             $codigo = $relacion[0][codigo_producto];
                                             $cantidad = $relacion[0][cantidad];
                                        }                                   
                                        $this->salida .= "</select>";
                                        $this->salida .= "</td>";
                                        
                                        $this->salida .= "<td width=\"13%\" align=\"center\">";
                                        $this->salida .= "<input type=\"input-text\" id=\"codigo_producto_S\" name=\"codigo_producto_S$y\" size=\"10\" maxlength=\"12\" value=\"$codigo\" readonly>";
                                        $this->salida .= "</td>";
                                        
                                        $this->salida .= "<td width=\"13%\" align=\"center\">";
                                        $this->salida .= "<input type=\"input-text\" id=\"cantidad\" name=\"cantidad$y\" size=\"5\" maxlength=\"4\" value=\"$cantidad\" readonly>";
                                        $this->salida .= "</td>";
          
                                        $this->salida .= "<td width=\"4%\" align=\"center\"><input type='checkbox'$checked  name='checo[]' value=\"".$codigo.",".$cantidad."\" checked></td>\n";
                                        $this->salida .= "</tr>";
                                        $a++;
                                   }
                              }
                              $this->salida .="</table>\n";
                              $this->salida .="</td>\n";
                              $this->salida .="</tr>";
                         }
                         }
                    }
                    $this->salida .= "		<script>";
                    $this->salida .= "		function AsignarValorCheckbox(valor,objeto){";
                    $this->salida .= "    objeto.value=valor;";
                    $this->salida .= "		}";
                    $this->salida .= "		</script>";
                    if($op){
                         $productosSoliciones=$this->TiposSolucionesProductos($op);
                    }
                    if($productosSoliciones){
                    $this->salida .= "		<tr rowspan='2' align='center' class='modulo_list_claro'>\n";
                    $this->salida .= "			<td colspan='6' width=\"10%\">\n";
                    $this->salida .= "	    <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_list_title\">\n";
                    $this->salida .= "		    <tr class=\"modulo_list_table_title\">\n";
                    $this->salida .= "			  <td colspan='4'>Solicitudes de Soluciones para Mezclar los Medicamentos</td>\n";
                    $this->salida .= "		    </tr>\n";
                    $this->salida .= "		    <tr class=\"modulo_list_table_title\">\n";
                    $this->salida .= "			  <td>Medicamento</td>";
                    $this->salida .= "			  <td>Descripcion</td>";
                    $this->salida .= "			  <td>Cantidad Solucion</td>";
                    $this->salida .= "			  <td>Solucion Solicitada y Soluciones existentes en el Inventario</td>";
                    $this->salida .= "		    </tr>\n";
                    $SolucionIdAnt=-1;
                    $conta=0;
                    foreach($productosSoliciones as $SolucionId=>$vector){
                         foreach($vector as $CodigoProducto=>$datoss){
                         $this->salida .= "		    <tr align='center' bgcolor='#FFFFFF'>\n";
                         $this->salida .= "			  <td width=\"10%\"><label class='label_mark'>".$CodigoProducto."</label></td>\n";
                         $this->salida .= "			  <td width=\"25%\"><label class='label_mark'>".$datoss['descripcion']."</label></td>\n";
                         $this->salida .= "			  <td width=\"25%\"><label class='label_mark'>".$datoss['cantidad']." ".$datoss['unidad_id']."</label></td>\n";
                         if($SolucionId!=$SolucionIdAnt){
                         $this->salida .= "			  <td rowspan=\"".sizeof($vector)."\"><label class='label_mark'>";
                         $this->salida .= "	      <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
                         $this->salida .= "			  <tr><td colspan=\"3\"><label class=\"label_mark\">".$datoss['nom_solucion']."</label></td></tr>";
                         $this->salida .= "			  <tr>";
                         $vect1=$this->ProductosInventarioSolucion($SolucionId,$datos_estacion['empresa_id'],$datos_estacion['centro_utilidad'],$bodega);
                         if(sizeof($vect1)>1){
                         $this->salida.="          <td>";
                         $this->salida.="          <select name=\"productoSolucion\" class=\"select\" onchange=\"AsignarValorCheckbox(this.options[selectedIndex].value,this.form.opid$conta)\">";
                         $this->salida.="          <option value=-1 SELECTED>--SELECCIONE--</option>";
                         for($l=0;$l<sizeof($vect1);$l++){
                              $this->salida.="        <option value=".$vect1[$l]['codigo_producto'].",".$datoss['evolucion_id'].">".$vect1[$l]['codigo_producto']."=>".$vect1[$l]['descripcion']."</option>";
                         }
                         $this->salida .="         </select>";
                         $this->salida .="         </td>";
                         $this->salida .= "			  <td width=\"10%\"><input type='text' name=\"cantidadesSol[]\" class='input-text' size='7' maxlength='7' value=1></td>\n";
                         $this->salida .= "		    <td width=\"4%\" ><input type='checkbox'  name=\"Seleccion[]\" id=\"opid$conta\"></td>\n";
                         $this->salida .="         </td>";
                         }else{
                              $this->salida.="          <td>";
                              $this->salida .= "			 ".$vect1[$l]['codigo_producto']."=>".$vect1[$l]['descripcion']."";
                              $this->salida .="         </td>";
                              $this->salida .= "			  <td width=\"10%\"><input type='text' name=\"cantidadesSol[]\" class='input-text' size='7' maxlength='7' value=1></td>\n";
                              $this->salida .= "		    <td width=\"4%\" ><input type='checkbox'  name=\"Seleccion[]\" id=\"opid$conta\" value=".$vect1[0]['codigo_producto'].",".$datoss['evolucion_id']."></td>\n";
                         }
                         $this->salida .= "			  </tr>";
                         $this->salida .= "	      </table>";
                         $this->salida .= "			  </label></td>\n";
                         $SolucionIdAnt=$SolucionId;
                         }
                         $this->salida .= "		    </tr>\n";
                         }
                         $conta++;
                    }
                    $this->salida .= "		  </td>";
                    $this->salida .= "		  </table>\n";
                    $this->salida .= "		</tr>\n";
               }
               //variable de session que me va a guardar la informacion del vector de solicitudes
               unset($_SESSION['ESTACION_MED']['VECTOR_SOL_OP']);
               $_SESSION['ESTACION_MED']['VECTOR_SOL_OP']=$vect;
               $this->salida.="</tr></table><br>";
               
               if($_REQUEST['bodega']=='*/*')
               {
                    $this->salida .= "<br><table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td ><label class='label_mark'>NOMBRE SOLICITANTE</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td ><label class='label_mark'>observaciones :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table>";
               }	
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";
               $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla('SOLICITUD DE MEDICAMENTOS',"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SOLICITO NINGUN MEDICAMENTO AL PACIENTE !</label></td>\n";
               $this->salida.="</tr></table>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }
     
     //funcion que confirma si se va a cancelar la solicitud de medicamentos para el paciente
     //esta pantalla muestra para confirmar la cancelación de los insumos 
     function ConfirmarCancelSolicitud_Medicamentos_Para_Pacientes()
     {
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $op = $_REQUEST['opcion'];
          $spy = $_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso = $_REQUEST['ingreso'];
          $medic = $_SESSION['ESTACION']['VECTOR_SOL_MED_PAC'][$ingreso];
          
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS PARA EL PACIENTE');
               $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CancelSolicitud_Medicamentos_Para_Paciente',array("spia"=>$spy,"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='6'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANTIDAD</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 4 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }

                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cantidad])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='4'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"Confirmar\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Cancelar\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla("CONTROL DE SOLICITUD DE MEDICAMENTOS PARA EL PACIENTE","50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     
     }

     
     //funcion que recibe los medicamentos / insumos por parte de la enfermera o el auxiliar.
     function Recibir_X_Para_Pacientes($datos_estacion,$datosPaciente,$codigo,$solicitud,$data)
     {
		if(empty($datos_estacion))
		{
			$datos_estacion = $_REQUEST['datos_estacion'];
			$datosPaciente = $_REQUEST['datosPaciente'];
			$codigo=$_REQUEST['codigo_producto'];
			$data[0]='';
		}

		$this->salida .= ThemeAbrirTabla("RECIBIR MEDIAMENTOS / INSUMOS");
			
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>HABITACION</td>\n";
		$this->salida .= "			<td>CAMA</td>\n";
		$this->salida .= "			<td>PISO</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[pieza]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[cama]."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[estacion_descripcion]."</td>\n";
		$this->salida.="</tr></table><br>";
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
          //parte de las solicitudes de medicmanetos por parte d e los pacientes
		$cont=0;
		for($w=0;$w<sizeof($data);$w++)
		{
			$e=explode(",",$data[$w]);
			if(!empty($e[0]))
			{
				$ingreso=$e[0];
				$codigo=$e[1];unset($e);
			}
			
               unset($medic);
			$medic=$this->Get_Medicamentos_Solicitados_Para_Pacientes($datosPaciente[ingreso],$datos_estacion[empresa_id],$solicitud,$codigo);

			if(is_array($medic))
			{$cont=1;}
			else
			{
				if($cont==0)
				{
					$cont=0;
				}
			}
			if(sizeof($medic))
			{
				$f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','Insertar_Recibido_Para_Pacientes',array("ingreso"=>$datosPaciente['ingreso'],"datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion,"solicitud"=>$solicitud,"codigo"=>$codigo,"data"=>$data));
				$this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
				$this->salida .= "	<table align=\"center\" width=\"80%\"  border=\"1\" class='modulo_list_table'\n>";
				if($w==0)
				{
					$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS E INSUMOS POR RECIBIR</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
				$this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
				$this->salida .= "			<td width=\"40%\" >DESCRIPCION PRODUCTO</td>\n";
				$this->salida .= "			<td width=\"13%\" >CANT SOL</td>\n";
				$this->salida .= "			<td width=\"12%\" >CANT REC</td>\n";
				$this->salida .= "			<td width=\"12%\" >CANT FALT</td>\n";
				$this->salida .= "			<td width=\"12%\" ></td>\n";
				$this->salida .= "		</tr>\n";


				for($k=0;$k<sizeof($medic);$k++)
				{
					if($k % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
					$this->salida .= "<tr $estilo>\n";
					$this->salida .= "<td $estilo width=\"12%\">".$medic[$k][codigo_producto]."</td>\n";
					$this->salida .= "<td $estilo align='center' width=\"44%\"><label class='label_mark'>".$medic[$k][producto]."</label></td>\n";
					$this->salida .= "<td $estilo align=\"center\" width=\"15%\"><label class='label_mark'>".floor($medic[$k][cantidad])."</label></td>\n";
					
                         //aca colocar el query de las cantidades recibisdas...........
					$recepcion=$this->Recepcion_Med_Ins_Para_Pacientes($datosPaciente[ingreso],$medic[$k][codigo_producto],$datos_estacion[estacion_id]);
					$faltante=$medic[$k][cantidad]-$recepcion;
					$this->salida .= "<td $estilo width=\"13%\">".FormatoValor($recepcion)."</td>\n";
					$this->salida .= "<td $estilo width=\"13%\">".FormatoValor($faltante)."</td>\n";unset($faltante);
				
					$this->salida .= "<td $estilo width=\"18%\"><input type='text' name='cantidad[][".$medic[$k][codigo_producto]."]' size='5' maxlength='10' ></td>\n";unset($faltante);
					$this->salida .= "<input type='hidden' name='cant_sol[][".$medic[$k][codigo_producto]."]' value='".floor($medic[$k][cantidad])."'>\n";
					$this->salida .= "<input type='hidden' name='cant_rec[][".$medic[$k][codigo_producto]."]' value='".floor($recepcion)."'>\n";
				
					$this->salida.=" </tr>";
				}
                    $this->salida.="</table>";
			}
			//fin de solicitudes por parte de los pacientes.		
	  	}//fin for primero
			
		if($cont >0)
		{
			$this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table_title\">\n";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td ><label class='label_mark'>NOMBRE DE LA PERSONA QUE ENTREGA</label></td><td><input type='text' name='nom' size='55' maxlength='60' value='$nom'></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td ><label class='label_mark'>OBSERVACIONES :</label></td><td><TEXTAREA name='area' rows='5' cols='80'>$area</TEXTAREA></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			
			$this->salida .= '<br><br><table align="center" width="40%" border="0">';
			$this->salida .= '<tr>';
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
		
			$o = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
			$this->salida .= '<form name="volver" method="post" action="'.$o.'">';
		
			$this->salida .= '<td align="center">';
			$this->salida .= '<input type="submit" name="volver" value="VOLVER" class="input-submit">';
			$this->salida .= '</form>';
			$this->salida .= '</td>';
			$this->salida .= '</tr>';
			$this->salida .= '</table>';
		}
		else
		{
               $this->salida .= "<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= '<tr>';
               $this->salida .= '<td align="center"><label class=label_mark>NO HAY MAS MEDICAMENTOS/INSUMOS POR RECIBIR</label>';
               $this->salida .= '</td>';
               $this->salida.="</tr>";
               $this->salida .= '</table>';
               $o = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
               $this->salida .= "<div class='normal_10' align='center'><br><a href='".$o."'>VOLVER</a><br>";
		}	
		
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

     //funcion que confirma si se va a cancelar la solicitud
     function ConfirmarCancelSolicitudMed()
     {
          $bodega = $_REQUEST['bodega'];
          $SWITCHE = $_REQUEST['switche'];
          $datos_estacion = $_REQUEST['datos_estacion'];
          $datosPaciente = $_REQUEST['datosPaciente'];
          $op = $_REQUEST['opcion'];
          $spy = $_REQUEST['spia']; //variable q determina a donde me dirigo cuando se cancele una solicitud.
          $ingreso = $_REQUEST['ingreso'];
          $medic=$_SESSION['ESTACION']['VECTOR_SOL'][$ingreso];
          if(sizeof($medic) AND sizeof($op))
          {
               unset($matriz);
               for($h=0;$h<sizeof($op);$h++)
               {
                    $dat_op=explode(",",$op[$h]);
                    $matriz[$h]=$dat_op[0];
               }
               $this->salida .= ThemeAbrirTabla('CANCELAR SOLICITUD DE MEDICAMENTOS');
               $f = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CancelSolicitudMedicametos',array("spia"=>$spy,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"matriz"=>$matriz,"switche"=>$SWITCHE));
               $this->salida .= "<form name='conf' action='".$f."' method='POST'><br>\n";
     
               $this->salida .= "	<table align=\"center\" width=\"85%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class='modulo_list_table'\n>";
     
               $this->salida .= "		<tr class=\"modulo_table_title\">\n";
               $this->salida .= "			<td colspan='7'  align=\"center\">MEDICAMENTOS SOLICITADOS</td>\n";
               $this->salida .= "		</tr>\n";
     
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td width=\"5%\" >SOLICITUD</td>\n";
               $this->salida .= "			<td width=\"17%\" >BODEGA</td>\n";
               $this->salida .= "			<td width=\"10%\" >CODIGO</td>\n";
               $this->salida .= "			<td width=\"25%\" >PRODUCTO</td>\n";
               $this->salida .= "			<td width=\"25%\"  >PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "			<td width=\"5%\" >CANT</td>\n";
               $this->salida .= "			<td width=\"5%\" ></td>\n";
               $this->salida .= "		</tr>\n";
     
               for($i=0;$i<sizeof($medic);$i++)
               {
                    if($i % 2)  $estilo = "class=modulo_list_claro";  else  $estilo = "class=modulo_list_oscuro";
                    if(in_array($medic[$i][solicitud_id],$matriz)==TRUE)
                    {
                         if($medic[$i][solicitud_id]!= $medic[$i-1][solicitud_id])
                         {
                              $this->salida .= "<tr $estilo>\n";
                              $this->salida .= "<td colspan = 1 width=\"5%\" align=\"center\" class=modulo_list_claro width=\"10%\">".$medic[$i][solicitud_id]."</td>\n";
                              $solicitud=$medic[$i][solicitud_id];
                              $this->salida .= "<td colspan = 5 width=\"65%\">";
                              $this->salida .= "	<table align=\"center\" width=\"100%\" border=\"0\" class='modulo_list_table'\n>";
                         }

                         $nom_bodega=$this->TraerNombreBodega($estacion,$medic[$i][bodega]);
                         $this->salida .= "<tr $estilo>\n";
                         $this->salida .= "<td $estilo width=\"20%\"><label class='label_mark'>$nom_bodega</label></td>\n";
                         $this->salida .= "<td $estilo width=\"12%\">".$medic[$i][codigo_producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][producto]."</td>\n";
                         $this->salida .= "<td $estilo width=\"30%\">".$medic[$i][principio_activo]."</td>\n";
                         $this->salida .= "<td $estilo align=\"center\" width=\"7%\">".floor($medic[$i][cant_solicitada])."</td>\n";
                         $this->salida.=" </tr>";
                         if($medic[$i][solicitud_id] != $medic[$i+1][solicitud_id])
                         {
                              $this->salida .= "</table>";
                              $this->salida .= "</td>";
                              $this->salida.="  <td colspan = 1 $estilo width=\"5%\" align=\"center\"><img src=\"". GetThemePath() ."/images/checkS.gif\" border='0'></td>";
                              $this->salida .= "</tr>";
                         }
                    }
               }
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"modulo_list_claro\">";
               $this->salida .= "<td  colspan='2' align='right' width=\"35%\"  >JUSTIFICACION :</td>";
               $this->salida .= "<td colspan='5'  align=\"left\"><TEXTAREA name=obs cols=100 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";

               $this->salida.="</tr></table><br>";
               $this->salida.="<br><table border=\"0\" align=\"center\" width=\"40%\">";
               $this->salida.=" <tr>";
               $this->salida.=" <td align=\"center\">";
               $this->salida.=" <input name=\"Confirmar\" type=\"submit\" class=\"input-submit\"  value=\"CONFIRMAR\"></form>";
               $this->salida.=" </td>";

               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          else
          {
               $this->salida .= ThemeAbrirTabla('CANCELACION DE MEDICAMENTOS SOLICITADOS A BODEGA',"50%");
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "		<tr >\n";
               $this->salida .= "			<td align=\"center\"><label class='label_mark'>NO SE SELECCIONO NINGUNA CASILLA !</label></td>\n";
               $this->salida.="</tr></table>";
	          $this->salida.="<br><table border=\"0\" align=\"center\" width=\"10%\">";
               $this->salida.=" <tr>";
               if($spy==1)
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallMedicamentosIns_X_Recibir',array("datos_estacion"=>$datos_estacion,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
               else
               {
                    $href = ModuloGetURL('app','EE_AdministracionMedicamentos','user','CallFrmMedicamentos',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$bodega,"switche"=>$SWITCHE));
               }
     
               $this->salida .="<form name=forma action=".$href." method=post>";
               $this->salida .="<td><input type=\"submit\" align=\"center\" name=\"Cancelar\" value=\"Volver\" class=\"input-submit\"></form></td>";
               $this->salida.=" </tr>";
               $this->salida.=" </table>";
               $this->salida .= ThemeCerrarTabla();
          }
          return true;
     }

     
     /*
     * Control_Suministro - Funcion la cual me permite realizar el suministro de 
     * los medicamentos recetados.
     *
     * Adaptacion: Tizziano Perea
     */
     function Control_Suministro($datos_estacion,$datosPaciente,$vect,$opciones)
     {
		unset($_SESSION['ESTACION_ENF_MED_VECT']['DATA']);
          if(!$datos_estacion)
		{
			$datosPaciente = $_REQUEST['datosPaciente'];
			$datos_estacion = $_REQUEST['datos_estacion'];
		}
		
          if(empty($vect))
          	$vect = $_SESSION['MEDICINAS'];//arreglo q contiene los productos seleccionados.
               
          if(empty($opciones))
          	$opciones = $_REQUEST['op'];
               
          if(empty($opciones)) 
          {         
               $url = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','CallFrmMedicamentos',array('datosPaciente'=>$datosPaciente,'datos_estacion'=>$datos_estacion));
               $titulo = 'ALERTA';
               $mensaje = 'DEBE ESCOGER UN MEDICAMENTO A SUMINISTRAR.';
               $link = 'VOLVER';
               $this->frmMSG($url,$titulo,$mensaje,$link);
               return true;
     	}
                    
          if(empty($_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']))
		{
			$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR']=$vect;
		}
		else
		{
			$vect=$_SESSION['ESTACION_ENF_MED_VECT']['VECTOR'];
		}
		
          $this->salida = ThemeAbrirTabla('CONTROL DE SUMINISTRO DEL MEDICAMENTO - CIRUGIA');
 
		$this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "			<td>INGRESO</td>\n";
		$this->salida .= "			<td>ESTACION</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td>".$datosPaciente[nombre_completo]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[numerodecuenta]."</td>\n";
		$this->salida .= "			<td>".$datosPaciente[ingreso]."</td>\n";
		$this->salida .= "			<td>".$datos_estacion[estacion_descripcion]."</td>\n";
		$this->salida.="</tr></table><br><br>";

          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida.="</table>";

		$accion = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','InsertarSuministroPaciente',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"op"=>$opciones));//'ConfirmarSuministros',array("tipo_solicitud"=>$tipo_solicitud,"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
          $this->salida .= "<form name=\"formades\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"left\" colspan=\"7\">CONTROL DEL MEDICAMENTO:</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"center\" width=\"7%\">CODIGO</td>";
		$this->salida.="  <td align=\"center\" width=\"30%\">PRODUCTO</td>";
		$this->salida.="  <td align=\"center\" width=\"29%\">PRINCIPIO ACTIVO</td>";
		$this->salida.="  <td align=\"center\" colspan= 4 width=\"14%\">CANTIDAD</td>";
		$this->salida.="</tr>";

          for($j=0; $j<sizeof($opciones); $j++)
          {
          	$V_Medicamentos = explode(",",$opciones[$j]);
               $this->salida.="<tr class='modulo_list_claro'>";
               $this->salida.="  <td align=\"center\" width=\"7%\">".$V_Medicamentos[0]."</td>";
               $this->salida.="  <td align=\"left\" width=\"30%\">".urldecode($V_Medicamentos[2])."</td>";
               $this->salida.="  <td align=\"left\" width=\"29%\">".urldecode($V_Medicamentos[1])."</td>";
               $this->salida.="  <td align=\"left\" colspan= 4 width=\"14%\"><input type='text' class='input-text' size='4' maxlength='4' name='cantidad_suministro[]' value=''>&nbsp;&nbsp;&nbsp;".urldecode($V_Medicamentos[3])."".urldecode($V_Medicamentos[4])."</td>";
               $this->salida.="<input type=\"hidden\" name=\"Cantidad_Real[]\" value=\"".$V_Medicamentos[5]."\">";
               $this->salida.="</tr>";
     	}
		$this->salida.="</table><br>";
		
          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
               
          $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $this->salida.="<input type=\"hidden\" name=\"total_suministro\" value=\"$total_suministro\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"left\" colspan=\"3\">INGRESAR SUMINISTRO</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class='modulo_list_claro'>";
          $this->salida.="<td align=\"center\" width=\"40%\">HORA DE ADMINISTRACION:</td>";
          $this->salida.="<td align=\"left\" width=\"40%\">";
     
          //**************** OJO CAMBIAR
          //EL SELECT DE LA HORA DE ARLEY
          $hora_inicio_turno = ModuloGetVar('app','EstacionEnfermeria','HoraInicioTurnoControles');
          $rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
          //**************** OJO CAMBIAR
          
          if(date("H:i:s") >= $hora_inicio_turno)
          {
               list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
               list($h,$m,$s)=explode(":",$hora_control);
          }
          else
          {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
               list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
               list($h,$m,$s)=explode(":",$hora_control);
          }

          $i=0;
          $this->salida .= "<select name=\"selectHora$pfj\" class=\"select\">\n";
          for($j=0; $j<$rango_turno; $j++)
          {
               list($anno, $mes, $dia)=explode("-",$fecha_control);
               if ($i==23)
               {
                    list($h,$m,$s)=explode(":",$hora_inicio_turno);
                    $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                    $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                    $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
               }
               else
               {
                    list($h,$m,$s)=explode(":",$hora_inicio_turno);
                    $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                    $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                    $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
               }
               if(empty($selectHora)){
                    if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
               }
               else
               {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                    list($A,$B) = explode(" ",$selectHora);
                    if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
               }
               #################################################
               list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
               if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                    $show = "Hoy a las";
               }
               elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                    $show = "Mañana a las";
               }
               elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                    $show = "Ayer a las";
               }
               else{
                    $show = $fecha_control;
               }
               ###########################
               $this->salida .="<option value='".$fecha_control." ".$i."' $selected>".$show." ".$i."</option>\n";
          }//fin for
          $this->salida .= "</select>:&nbsp;\n";
          $this->salida .= "<select name=\"selectMinutos$pfj\" class=\"select\">\n";

          for($j=0; $j<=59; $j++)
          {
               if(empty($selectMinutos)){
                    if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
               }
               else
               {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                    list($A,$B) = explode(" ",$selectMinutos);
                    if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
               }
               if ($j<10){
                    $this->salida .= "<option value='0$j:00' $selected>0$j</option>\n";
               }
                    else{
                    $this->salida .= "<option value='$j:00' $selected>$j</option>\n";
               }
          }
          $this->salida .= "</select>\n";
          $this->salida.="</td>";
          $profesionales = $this->ReconocerProfesional();
          $this->salida.="<td>PROFESIONAL QUE ORDENO:";
          $this->salida.="<select name=\"ordeno\" class=\"select\">";
          $this->salida.="<option value=\"-1\" selected>-- SELECCIONE --</option>";
          foreach($profesionales as $k => $v)
          {
               if($v[usuario_id] == $_REQUEST['ordeno'])
                    $this->salida.="<option value=\"".$v[usuario_id]."\" selected>".$v[nombre]."</option>";
               else
                    $this->salida.="<option value=\"".$v[usuario_id]."\">".$v[nombre]."</option>";               
          }
          $this->salida.="</select>";
          $this->salida.="</td>";
          $this->salida.="<input type='hidden' name='dosis' value='".$dosis."'>";
          
          $this->salida.="</tr>";
          $this->salida.="<input type=\"hidden\" name=\"sumatoria\" value=\"$SUMATORIA\">";
          $this->salida.="<input type=\"hidden\" name=\"totalitario\" value=\"$totalitario\">";

          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td align=\"center\" width=\"15%\">OBSERVACION DE ADMINISTRACION</td>";
          $this->salida.="<td colspan=\"2\" width=\"65%\" align='center'><textarea class='textarea' name = 'observacion_suministro' cols = 100 rows = 7>".$_REQUEST['observacion_suministro']."</textarea></td>" ;
          $this->salida.="</tr>";
          
          $this->salida.="<tr class=\"$estilo\">";
          $this->salida.="<td align=\"center\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"GUARDAR\"></td>";
          $this->salida.="</tr>";
          $this->salida.="</table><br>";
          $this->salida.="</form>";

          //BOTON DEVOLVER
          $href = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','CallFrmMedicamentos',array("datosPaciente"=>$datosPaciente,"datos_estacion"=>$datos_estacion));
          $this->salida .= "<form name=\"forma\" action=\"$href\" method=\"post\">";
          $this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
          $this->salida .= themeCerrarTabla();
          return true;
     }

     
     function GetHtmlParametrosDevolucion($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[parametro_devolucion_id]==$TipoId){
                    $this->salida .=" <option align=\"center\" value=\"$titulo[parametro_devolucion_id]\" selected>$titulo[descripcion]</option>";
               }else{
                    $this->salida .=" <option align=\"center\" value=\"$titulo[parametro_devolucion_id]\">$titulo[descripcion]</option>";
               }
          }
     }
     
     
     /*
     * Funcion que muestras la forma para imprimir las formulas...
     */
     function FrmImpresionMedicamentos($estacion,$datos_estacion)
     {
          unset ($_SESSION['DATOS_M'.$pfj]['PLAN_TERAPEUTICO']);
          unset ($_SESSION['MEDICAMENTOS'.$pfj]);
          unset ($_SESSION['POSOLOGIA4'.$pfj]);
          unset ($_SESSION['DIAGNOSTICOS'.$pfj]);
          unset ($_SESSION['JUSTIFICACION'.$pfj]);
          unset ($_SESSION['MODIFICANDO'.$pfj]);
          unset ($_SESSION['DIAGNOSTICOSM'.$pfj]);
          unset ($_SESSION['MEDICAMENTOSM'.$pfj]);

          if(empty($estacion))
          {
               $estacion=$_REQUEST['estacion'];
               $datos_estacion=$_REQUEST['datos_estacion'];
          }
     

          //preguntamos si la estacion esta asociada con una bodega.
          //$bodega_estacion=$this->GetEstacionBodega($estacion);

          $this->salida .= ThemeAbrirTabla($datos_estacion['control_descripcion']);
          $href = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','ReporteFormulaMedica',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion,"bodega"=>$bodega_estacion));			
          $this->salida .= "<form name='med' action='".$href."' method='POST'><br>\n";
          $this->salida .= "<SCRIPT>";
          $this->salida .= "function chequeoTotal(frm,x){";
          $this->salida .= "  if(x==true){";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=true";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }else{";
          $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
          $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
          $this->salida .= "        frm.elements[i].checked=false";
          $this->salida .= "      }";
          $this->salida .= "    }";
          $this->salida .= "  }";
          $this->salida .= "}";
          $this->salida .= "</SCRIPT>";
          $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list_title\">\n";
          $this->salida .= "		<tr class=\"modulo_table_title\">\n";
          $this->salida .= "			<td>PACIENTE</td>\n";
          $this->salida .= "			<td>CUENTA</td>\n";
          $this->salida .= "			<td>INGRESO</td>\n";
          $this->salida .= "			<td>ESTACION</td>\n";
          $this->salida .= "		</tr>\n";
          $this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$datos_estacion['nombre_completo']."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[numerodecuenta]."</td>\n";
          $this->salida .= "			<td>".$datos_estacion[ingreso]."</td>\n";
          $this->salida .= "			<td>".$estacion['estacion_descripcion']."</td>\n";
          $this->salida.="</tr></table><br>";
     
          $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
          $vector1=$this->Consulta_Solicitud_Medicamentos($datos_estacion[ingreso]);
          $m = 0;
          if($vector1)
          {
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida .= $this->SetStyle("MensajeError");
               $this->salida.="</table>";

               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"6\">PLAN TERAPEUTICO - MEDICAMENTOS FORMULADOS </td>";
               $this->salida.="<td align=\"center\" colspan=\"1\">SELECCION</td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="  <td width=\"7%\">CODIGO</td>";
               $this->salida.="  <td width=\"30%\">PRODUCTO</td>";
               $this->salida.="  <td width=\"29%\">PRINCIPIO ACTIVO</td>";
               $this->salida.="  <td width=\"14%\" colspan=\"3\">OPCIONES</td>";//colspan=\"3"\
               $this->salida.="  <td  width=\"5%\">SEL. TODOS<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
               $this->salida.="</tr>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($vector1);$i++)
               {
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    if($vector1[$i][item] == 'NO POS')
                    {
                         if ($vectorMSH)
                         {
                              $this->salida.="  <td ROWSPAN = 5 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                    	}
                         else
                         {
						$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."<BR>NO_POS</td>";
                         }
                    }
                    else
                    {
                         if($vectorMSH)
                         {
	                         $this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                         }
                         else
                         {
     		               $this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">".$vector1[$i][codigo_producto]."</td>";
                         }
                    }
                    
                    //LINEA ALTERADA para ver la evolucion
                    $this->salida.="  <td align=\"center\" width=\"30%\">".$vector1[$i][producto]."-".$vector1[$i][evolucion_id]."</td>";
                    $this->salida.="  <td align=\"left\" width=\"29%\">".$vector1[$i][principio_activo]."</td>";

                    //*lo que inserte de control de suministro
                    if($vector1[$i]['sw_estado'] == '1')
                    {
                         $this->salida .= "		<td align='center' width=\"8%\" colspan=\"3\">Registro Administración Medicamentos</font></td>\n";
                    }
                    else
                    {
		               $this->salida .= "		<td align='center' width=\"3%\">&nbsp;</td>\n";
                    }
                    
                    $this->salida.="  <td ROWSPAN =3  width=\"5%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vector1[$i][codigo_producto].",".$vector1[$i][evolucion_id]."></td>";
	               //fin del validador
                    $this->salida.="</tr>";


                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan = 5>";
                    $this->salida.="<table>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td colspan = 3 align=\"left\" width=\"9%\">Via de Administracion: ".$vector1[$i][via]."</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Dosis:</td>";
                    $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                    if($e==1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".floor($vector1[$i][dosis])."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"14%\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                    }

                    $vector_posologia= $this->Consulta_Solicitud_Medicamentos_Posologia($vector1[$i][codigo_producto], $vector1[$i][tipo_opcion_posologia_id], $vector1[$i][evolucion_id]);

			//pintar formula para opcion 1
                    if($vector1[$i][tipo_opcion_posologia_id]== 1)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">cada ".$vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo]."</td>";
                    }

			//pintar formula para opcion 2
                    if($vector1[$i][tipo_opcion_posologia_id]== 2)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][descripcion]."</td>";
                    }

			//pintar formula para opcion 3
                    if($vector1[$i][tipo_opcion_posologia_id]== 3)
                    {
                         $momento = '';
                         if($vector_posologia[0][sw_estado_momento]== '1')
                         {
                              $momento = 'antes de ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '2')
                              {
                                   $momento = 'durante ';
                              }
                              else
                              {
                                   if($vector_posologia[0][sw_estado_momento]== '3')
                                   {
                                        $momento = 'despues de ';
                                   }
                              }
                         }
                         $Cen = $Alm = $Des= '';
                         $cont= 0;
                         $conector = '  ';
                         $conector1 = '  ';
                         if($vector_posologia[0][sw_estado_desayuno]== '1')
                         {
                              $Des = $momento.'el Desayuno';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_almuerzo]== '1')
                         {
                              $Alm = $momento.'el Almuerzo';
                              $cont++;
                         }
                         if($vector_posologia[0][sw_estado_cena]== '1')
                         {
                              $Cen = $momento.'la Cena';
                              $cont++;
                         }
                         if ($cont== 2)
                         {
                              $conector = ' y ';
                              $conector1 = '  ';
                         }
                         if ($cont== 1)
                         {
                              $conector = '  ';
                              $conector1 = '  ';
                         }
                         if ($cont== 3)
                         {
                              $conector = ' , ';
                              $conector1 = ' y ';
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$Des."".$conector."".$Alm."".$conector1."".$Cen."</td>";
                    }

			//pintar formula para opcion 4
                    if($vector1[$i][tipo_opcion_posologia_id]== 4)
                    {
                         $conector = '  ';
                         $frecuencia='';
                         $j=0;
                         foreach ($vector_posologia as $k => $v)
                         {
                              if ($j+1 ==sizeof($vector_posologia))
                              {
                                   $conector = '  ';
                              }
                              else
                              {
                                        if ($j+2 ==sizeof($vector_posologia))
                                             {
                                                  $conector = ' y ';
                                             }
                                        else
                                             {
                                                  $conector = ' - ';
                                             }
                              }
                              $frecuencia = $frecuencia.$k.$conector;
                              $j++;
                         }
                         $this->salida.="  <td align=\"left\" width=\"50%\">a la(s): $frecuencia</td>";
                    }

               //pintar formula para opcion 5
                    if($vector1[$i][tipo_opcion_posologia_id]== 5)
                    {
                         $this->salida.="  <td align=\"left\" width=\"50%\">".$vector_posologia[0][frecuencia_suministro]."</td>";
                    }
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"9%\">Cantidad:</td>";
                    $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                    if ($vector1[$i][contenido_unidad_venta])
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                         }
                    }
                    else
                    {
                         if($e==1)
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".floor($vector1[$i][cantidad])." ".$vector1[$i][descripcion]."</td>";
                         }
                         else
                         {
                              $this->salida.="  <td colspan = 2 align=\"left\" width=\"64%\">".$vector1[$i][cantidad]." ".$vector1[$i][descripcion]."</td>";
                         }
                    }
                    $this->salida.="</tr>";

                    $this->salida.="</table>";
                    $this->salida.="</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td colspan =5 class=\"$estilo\">";
                    $this->salida.="<table>";
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"left\" width=\"4%\">Observacion:</td>";

                    $this->salida.="  <td align=\"left\" width=\"69%\">".$vector1[$i][observacion]."</td>";
                    $this->salida.="<tr class=\"$estilo\">";

                    if($vector1[$i][sw_uso_controlado]==1)
                    {
                         $this->salida.="<tr class=\"$estilo\">";
                         $this->salida.="  <td align=\"left\" colspan = 2 width=\"73%\">MEDICAMENTO DE USO CONTROLADO</td>";
                         $this->salida.="<tr class=\"$estilo\">";
	               }
     		     $this->salida.="</table>";
	               $this->salida.="</td>";
                    $this->salida.="</tr>";

                    $this->salida.="<tr class=\"$estilo\">";

               //fin del for muy importante
               }
               
               //<duvan>  --> el link de solicitud de mediamentos.
               $this->salida.="<tr class=\"$estilo\">";
               $accion1 = ModuloGetURL('app','EE_AdministracionMedicamentosQX','user','CallFrmMedicamentos',array("datosPaciente"=>$datos_estacion,"datos_estacion"=>$estacion));
               $this->salida.="  <td colspan = 7 align=\"center\" width=\"63%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/anterior.png\" border='0'> REGRESAR A MEDICAMENTOS</a></td>";
               $this->salida.="</tr>";

               $this->salida.="<tr class=modulo_table_title>";
               //if(is_array($bodega_estacion) OR !empty($_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']))
               //{
                    $this->salida.="  <td class=\"modulo_table_button\" colspan = 6 align=\"center\" width=\"80%\"><input type=submit class='input-submit' name='mandarpos' value='IMPRIMIR POS'> </td>";
                    $this->salida.="  <td class=\"modulo_table_button\" colspan = 1 align=\"center\" width=\"80%\"><input type=submit class='input-submit' name='mandarpdf' value='IMPRIMIR PDF'> </td>";
               //}
               /*else
               {
                    $this->salida.="  <td colspan = 9 align=\"center\" width=\"80%\"><font color='white'>LA ESTACION ".$_SESSION['ESTACION_ENFERMERIA']['NOM']." &nbsp;NO TIENE BODEGAS ASOCIADAS</font></td>";
               }*/
               $this->salida.="</form></tr>";
               $this->salida.="</table><br>";
          }
          else
          {
               $this->salida .= "	<table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
               $this->salida.="<tr  align=\"center\"><td><label class='label_mark'>EL PACIENTE NO TIENE MEDICAMENTOS SOLICITADOS";
               $this->salida.="</tr></td></label>";
               $this->salida.="</table><br>";
          }
          //fin de mediacamentos finalizadops
          $this->salida .= "</form>";

          if ($_SESSION['PROFESIONAL'.$pfj]!=1)
          {
               if($m==2)
               {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td align=\"center\" width=\"7%\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="</table><br>";
               }
          }
     
          $this->salida .= ThemeCerrarTablaSubModulo();
          return true;
	}

     
          
     //funciones para generar la barra de segmentos en el buscador
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

	 function RetornarBarra($filtro,$uno){
          if($this->limit>=$this->conteo){
               return '';
		}
		//if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		//de busqueda...
	  	$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		
          $datos_estacion = $_REQUEST["datos_estacion"];
		$datosPaciente = $_REQUEST["datosPaciente"];
          if($uno == 1)
          {
			$accion=ModuloGetURL('app','EE_AdministracionMedicamentos','user','SolSuministros_x_estacion',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$_REQUEST['bodega']));
          }
          else
          {
               $accion=ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,"bodega"=>$_REQUEST['bodega']));
          }
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='20%' class='label' bgcolor=\"#D3DCE3\">Páginas</td>";
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
				
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td></tr></table>";
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
			$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td></tr></table>";
		}
	}
	//fin de las fujnciones para la barra de segnentacion
     
     

}//fin de la clase

?>

