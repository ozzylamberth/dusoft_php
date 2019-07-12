<?php

/**
 * $Id: app_EE_FuncionalidadesQX_userclasses_HTML.php,v 1.9 2007/02/08 14:05:17 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
 * @package IPSOFT-SIIS
 */

class app_EE_FuncionalidadesQX_userclasses_HTML extends app_EE_FuncionalidadesQX_user
{
     /**
     * Constructor
     *
     * @return boolean
     */
     function app_EE_FuncionalidadesQX_user_HTML()
     {
          $this->app_EE_FuncionalidadesQX_user();
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
     * Forma para el ingreso de un paciente a la estacion.
     *
     * @return boolean True si se ejecuto correctamente
     * @access public
     */
     function FrmAccionPacienteQX()
     {
          $datosPaciente = $_REQUEST['datosPaciente'];
          
          if($datosPaciente===false)
          {
               if(empty($this->error))
               {
                    $this->error = "FrmAccionPacienteQX - FrmAccionPacienteQX - 01";
                    $this->mensajeDeError = "El metodo GetDatosPacientePorIngresar() retorno false.";
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

          $datos_estacion = &$this->GetdatosEstacion();
     
          //VALIDACION DE PERMISOS
          if(!is_array($datos_estacion))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo = "VALIDACION DE PERMISOS";
               $this->frmMSG($url,$titulo);
               return true;
          }
          
          if($_REQUEST['conducta'])
          	$conducta = $_REQUEST['conducta'];
			
          switch($_REQUEST['accionPacienteQX'])
          {
               case '11':
               $this->FrmHospitalizacionRemisionCirugia($datosPaciente,$datos_estacion,$conducta);        
   			return true;                         
               break;
               
               case '07':
               $this->FrmAltaPacienteCirugia($datosPaciente,$datos_estacion,$conducta);        
   			return true;                         
               break;
               
               case '99':
               $this->FrmAltaPacienteCirugia($datosPaciente,$datos_estacion,$conducta);        
   			return true;                         
               break;
               
               case '12':
               $this->FrmRemitirCuidadosIntensivos($datosPaciente,$datos_estacion,$conducta);        
   			return true;                         
               break;
          }
          //DATOS DEL PIE DE PAGINA
          $this->FrmPieDePagina();
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
          $this->salida .= "    <table class='modulo_table_title' border='0' width='90%'>\n";
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
     
     
     /**
     * Forma para mostrar la informacion del paciente, justo antes de realizar la
     * asignacion de la cama
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmHospitalizacionRemisionCirugia($datosPaciente,$datos_estacion,$conducta)
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('04'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Trasladar al Paciente - Traslado de Estacion y/o Departamento [04]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          //CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
          $this->FrmDatosEstacion(&$datos_estacion);
		
          if(is_array($datosPaciente))
		{
			$this->salida .= "<br><br><table align='center' width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=modulo_table_list>\n";
			$this->salida .= "	<tr class=\"modulo_table_title\">\n";
			$this->salida .= "		<td colspan=\"6\">PACIENTE PENDIENTE POR RESMISION A HOSPITALIZACION</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td width=\"40%\" colspan=\"2\">PACIENTE</td>\n";
			$this->salida .= "		<td width=\"30%\">TIPO EGRESO</td>\n";
			$this->salida .= "		<td width=\"10%\">INGRESO</td>\n";
			$this->salida .= "		<td width=\"10%\">CUENTA</td>\n";
			$this->salida .= "		<td width=\"25%\">RESUMEN HC</td>\n";
			$this->salida .= "	</tr>\n";
			$i=0;
			$reporte= new GetReports();
               if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "<tr class=\"$estilo\">\n";
               $this->salida .= "<td nowrap width=\"5%\">\n";
               $this->salida .= "	<img src='".GetThemePath()."/images/atencion_citas.png' width=18 height=18 align='middle' border=0>\n";
               $this->salida .= "	</td>\n";
               $this->salida .= "	<td nowrap width=\"35%\" align=\"center\"><b>".$datosPaciente['nombre_completo']."</b><br><label class='label_mark'>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</label></td>\n";
               $this->salida .= "	<td align=\"center\">".$conducta['descripcion']."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente['ingreso']."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente['numerodecuenta']."</td>\n";
               
               $this->salida .= "<td align=\"center\">\n";
               $this->salida.=$reporte->GetJavaReport_HC($datosPaciente['ingreso'],array());
               $funcion=$reporte->GetJavaFunction();
               $this->salida .= "	<a href=\"javascript:$funcion\">RESUMEN HC</a>\n";
               $this->salida .= "	</td>\n";
               $this->salida .= "</tr>\n";
               $i++;
               
               $conteo_evolucion=$this->BuscarEvolucion_Pac($datosPaciente['ingreso']);//revisemos si tiene evoluciones abiertas.


			if($conteo_evolucion < 1)
			{
                    $linksalida = ModuloGetURL('app','EE_FuncionalidadesQX','user','DarSalida',array("cuenta"=>$datosPaciente['numerodecuenta'],"ingreso"=>$datosPaciente['ingreso'],"tipo_id"=>$datosPaciente['tipo_id_paciente'],"pac"=>$datosPaciente['paciente_id'],'conducta'=>$conducta,'estacion_origen'=>$datosPaciente['estacion_origen']));
                    $this->salida .= "<form name=forma method=\"POST\" action=$linksalida>";
                    $this->salida .= "<tr>\n";
                    $this->salida .= "<td class='$estilo' colspan=\"4\"><b>NOTA FINAL</b><BR><center><textarea name='obs' cols=60 rows=6>".$_REQUEST['obs']."</textarea></center><BR>";
                    $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"2\"><input  class='input-submit' type=submit name='remitir_estacion' value='REMITIR A ESTACION'>\n";
                    $this->salida .= "</td>\n";
                    $this->salida .= "</tr>\n";
                    $this->salida .= "</form>\n";
               }
               else
               {
                    $this->salida .= "<tr align='center'><td class='$estilo' colspan='6'><label class='label_mark'>NO SE PUEDE SACAR EL PACIENTE DEBIDO A QUE TIENE EVOLUCIONES ABIERTAS !</label>\n";
                    $this->salida .= "</td></tr>\n";
               }

	          $this->salida .= "</table><br>\n";
               
               if($conteo_evolucion >= 1)
               {
                    $datos=$this->BuscarEvolucion_Pac($datosPaciente['ingreso'],1);//revisemos si tiene evoluciones abiertas.
                    $this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
                    $this->salida.="<tr class=\"modulo_table_list_title \">";
                    $this->salida.="  <td colspan=\"5\">INFORMACION DE EVOLUCIONES ABIERTAS</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"modulo_table_list_title \">";
                    $this->salida.="  <td>No.EVOLUCION</td>";
                    $this->salida.="  <td>ESPECIALIDAD</td>";
                    $this->salida.="  <td>PROFESIONAL</td>";
                    $this->salida.="  <td>FECHA</td>";
                    $this->salida.="  <td>CERRAR</td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($datos);$i++)
                    {
                         $rcaja=$datos[$i][recibo_caja];
                         $empresa=$datos[$i][empresa_id];
                         $centro=$datos[$i][centro_utilidad];
                         $fech=$datos[$i][fecha_registro];
                                        
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
                         $this->salida.="<td>".$datos[$i][evolucion_id]."</td>";
                         $this->salida.="  <td>".$datos[$i][descripcion]."</td>";
                         $this->salida.="  <td>".$datos[$i][nombre]."</td>";
                         $this->salida.="  <td>".$this->FormateoFechaLocal($datos[$i][fecha])."</td>";
                         $AccionCerrar = ModuloGetURL('app','EE_FuncionalidadesQX','user','CerrarEvolucionesAbiertas', array('evolucion'=>$datos[$i][evolucion_id], "datos_estacion"=>$datos_estacion, "datosPaciente"=>$datosPaciente, 'conducta'=>$conducta));;
                         $this->salida.="  <td><a href=\"$AccionCerrar\"><b>Cerrar<b></a></td>";
                         $this->salida.="</tr>";
                    }
                    $this->salida.="</table>";
               }
          }
          $this->FrmPieDePagina();
          return true;
     }

     /**
     * Forma para mostrar la informacion del paciente, justo antes de realizar la
     * asignacion de la cama
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmAltaPacienteCirugia($datosPaciente,$datos_estacion,$conducta)
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('56'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Trasladar al Paciente - Traslado de Estacion y/o Departamento [04]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          //CABECERA - DATOS DE LA ESTACION DE ENFERMERIA
          $this->FrmDatosEstacion(&$datos_estacion);
		if(is_array($datosPaciente))
		{
			$this->salida .= "<br><br><table align='center' width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=modulo_table_list>\n";
			$this->salida .= "	<tr class=\"modulo_table_title\">\n";
			$this->salida .= "		<td colspan=\"6\">PACIENTE PENDIENTE POR SALIDA DE ESTACION</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "		<td width=\"40%\" colspan=\"2\">PACIENTE</td>\n";
			$this->salida .= "		<td width=\"30%\">TIPO EGRESO</td>\n";
			$this->salida .= "		<td width=\"10%\">INGRESO</td>\n";
			$this->salida .= "		<td width=\"10%\">CUENTA</td>\n";
			$this->salida .= "		<td width=\"25%\">RESUMEN HC</td>\n";
			$this->salida .= "	</tr>\n";
			$i=0;
			$reporte= new GetReports();
               if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "<tr class=\"$estilo\">\n";
               $this->salida .= "<td nowrap width=\"5%\">\n";
               $this->salida .= "	<img src='".GetThemePath()."/images/atencion_citas.png' width=18 height=18 align='middle' border=0>\n";
               $this->salida .= "	</td>\n";
               $this->salida .= "	<td nowrap width=\"35%\" align=\"center\"><b>".$datosPaciente['nombre_completo']."</b><br><label class='label_mark'>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</label></td>\n";
               $this->salida .= "	<td align=\"center\">".$conducta['descripcion']."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente['ingreso']."</td>\n";
               $this->salida .= "	<td align=\"center\">".$datosPaciente['numerodecuenta']."</td>\n";
               
               $this->salida .= "<td align=\"center\">\n";
               $this->salida.=$reporte->GetJavaReport_HC($datosPaciente['ingreso'],array());
               $funcion=$reporte->GetJavaFunction();
               $this->salida .= "	<a href=\"javascript:$funcion\">RESUMEN HC</a>\n";
               $this->salida .= "	</td>\n";
               $this->salida .= "</tr>\n";
               $i++;
               
               $conteo_evolucion=$this->BuscarEvolucion_Pac($datosPaciente['ingreso']);//revisemos si tiene evoluciones abiertas.

			if($conteo_evolucion < 1)
			{
                    $vistos_ok = $this->BusquedaVistos_ok_salida($conducta);

                    if(empty($vistos_ok['01']['ingreso']))
                    {
	                    $linkvisto = ModuloGetURL('app','EE_FuncionalidadesQX','user','Insertar_Vistobueno',array("datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente,'conducta'=>$conducta));
                         $this->salida .= "<form name=forma method=\"POST\" action=$linkvisto>";
                         $this->salida .= "<tr>\n";
                         $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"6\"><input  class='input-submit' type=submit name='visto_bueno' value='VISTO BUENO'>\n";
                         $this->salida .= "</td>\n";
                         $this->salida .= "</tr>\n";
                         $this->salida .= "</form>\n";

                    }
                    else
                    {
                         $ok=true;
                         foreach($vistos_ok as $k=>$v)
                         {
                              if(empty($v['ingreso'])) $ok=false;
                         }
                         
                         if($ok)
                         {
                         	$conteo_cuentas = $this->GetInfoCuentasActivas($datosPaciente['ingreso']); //revisa si tiene cuentas abiertas. 
                              
                              if($conteo_cuentas == '1')
                              {
                                   $this->salida .= "<tr>\n";
                                   $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"6\"><label class='label_error'>EL PACIENTE TIENE CUENTAS ACTIVAS.<br>FALTA EL VISTO BUENO DE FACTURACION!!!!.</label>\n";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                              }
                              else
                              {
                                   $linksalida = ModuloGetURL('app','EE_FuncionalidadesQX','user','DarSalidaInstitucion',array("cuenta"=>$datosPaciente['numerodecuenta'],"ingreso"=>$datosPaciente['ingreso'],"tipo_id"=>$datosPaciente['tipo_id_paciente'],"pac"=>$datosPaciente['paciente_id'],'conducta'=>$conducta, 'dpto_egreso'=>$datos_estacion['departamento']));
                                   $this->salida .= "<form name=forma method=\"POST\" action=$linksalida>";
                                   $this->salida .= "<tr>\n";
                                   $this->salida .= "<td class='$estilo' colspan=\"4\"><b>NOTA FINAL</b><BR><center><textarea name='obs' cols=60 rows=6>".$_REQUEST['obs']."</textarea></center><BR>";
                                   $this->salida .= "<td align=\"center\" class='$estilo' colspan=\"2\"><input  class='input-submit' type=submit name='remitir_estacion' value='DAR SALIDA'>\n";
                                   $this->salida .= "</td>\n";
                                   $this->salida .= "</tr>\n";
                                   $this->salida .= "</form>\n";
                              }
                         }                   
                    }
               }
               else
               {
                    $this->salida .= "<tr align='center'><td class='$estilo' colspan='6'><label class='label_mark'>NO SE PUEDE SACAR EL PACIENTE DEBIDO A QUE TIENE EVOLUCIONES ABIERTAS !</label>\n";
                    $this->salida .= "</td></tr>\n";
               }

	          $this->salida .= "</table><br>\n";
               
               if($conteo_evolucion >= 1)
			{
				$datos=$this->BuscarEvolucion_Pac($datosPaciente['ingreso'],1);//revisemos si tiene evoluciones abiertas.
				$this->salida.="<table  align=\"center\" border=\"0\" width=\"90%\">";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td colspan=\"5\">INFORMACION DE EVOLUCIONES ABIERTAS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_table_list_title \">";
				$this->salida.="  <td>No.EVOLUCION</td>";
				$this->salida.="  <td>ESPECIALIDAD</td>";
				$this->salida.="  <td>PROFESIONAL</td>";
				$this->salida.="  <td>FECHA</td>";
                    $this->salida.="  <td>CERRAR</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($datos);$i++)
				{
                         $rcaja=$datos[$i][recibo_caja];
                         $empresa=$datos[$i][empresa_id];
                         $centro=$datos[$i][centro_utilidad];
                         $fech=$datos[$i][fecha_registro];
								
                         if( $i % 2){ $estilo='modulo_list_claro';}
                         else {$estilo='modulo_list_oscuro';}
                         $this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
                         $this->salida.="<td>".$datos[$i][evolucion_id]."</td>";
                         $this->salida.="  <td>".$datos[$i][descripcion]."</td>";
                         $this->salida.="  <td>".$datos[$i][nombre]."</td>";
                         $this->salida.="  <td>".$this->FormateoFechaLocal($datos[$i][fecha])."</td>";
                         $AccionCerrar = ModuloGetURL('app','EE_FuncionalidadesQX','user','CerrarEvolucionesAbiertas', array('evolucion'=>$datos[$i][evolucion_id], "datos_estacion"=>$datos_estacion, "datosPaciente"=>$datosPaciente, 'conducta'=>$conducta));;
                         $this->salida.="  <td><a href=\"$AccionCerrar\"><b>Cerrar<b></a></td>";
                         $this->salida.="</tr>";
				}
				$this->salida.="</table>";
			}
          }
          $this->FrmPieDePagina();
          return true;
     
     }

	function FrmRemitirCuidadosIntensivos($datosPaciente,$datos_estacion,$conducta)
     {
          //Validar si el usuario esta logueado y si tiene permisos.
          if(!$this->GetUserPermisos('04'))
          {
               $url= ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
               $titulo='VALIDACION DE PERMISOS';
               $mensaje='El usuario no tiene permiso para : Trasladar al Paciente - Traslado de Estacion y/o Departamento [04]';
               $this->frmMSG($url, $titulo, $mensaje);
               return true;
          }
          
          $estaciones = $this->GetEstacionesDpto($datos_estacion);
		if(!$estaciones){
			return false;
		}
		if($estaciones === "ShowMensaje")
		{
			$mensaje = "NO SE ENCONTRARON ESTACIONES EN EL DEPARTAMENTO";
			$titulo = "TRASLADO DE ESTACION";
			$url = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
			$link = "Panel Enfermeria";
			$this->frmMSG($url, $titulo, $mensaje, $link);
			return true;
		}

		$this->salida .= ThemeAbrirTabla('TRASLADO DE ESTACION')."<br>";
          
          $RUTA = "app_modules/EE_FuncionalidadesQX/descriptivo.php?sign=";
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="var rem=\"\";\n";
          $mostrar.=" function Descriptivo(valor_ee,desc_ee){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"width=350,height=250,resizable=no,location=no, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA'+valor_ee+','+desc_ee;\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
          
          $mostrar.="</script>\n";
          $this->salida .="$mostrar";

		$this->salida .= "	<table align=\"center\" width=\"70%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "		<tr class=\"modulo_table_title\">\n";
		$this->salida .= "			<td>PACIENTE</td>\n";
		$this->salida .= "			<td>IDENTIFICACION</td>\n";
		$this->salida .= "			<td>CUENTA</td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "		<tr align='center' class='modulo_list_oscuro'>\n";
		$this->salida .= "			<td><b>".$datosPaciente[nombre_completo]."</b></td>\n";
		$this->salida .= "			<td><b>".$datosPaciente[tipo_id_paciente]." ".$datosPaciente[paciente_id]."</b></td>\n";
		$this->salida .= "			<td><b>".$datosPaciente[numerodecuenta]."</b></td>\n";
		$this->salida .= "		</tr>\n";
		$this->salida .= "	</table><br>\n";

		$this->salida .= "<table width=\"70%\" cellpadding=\"2\" border=\"0\" class=\"modulo_table_list\" align=\"center\">\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "		<td width=\"50%\">ESTACION</td>\n";
		$this->salida .= "		<td width=\"30%\">ACCIONES</td>\n";
          $this->salida .= "		<td width=\"20%\">PROTOCOLO</td>\n";
		$this->salida .= "	</tr>\n";
		
          for ($i=0; $i<sizeof($estaciones); $i++)
		{
          	if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
               $this->salida .= "	<tr class=\"$estilo\">\n";
               $this->salida .= "		<td><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;<label class='label_mark'>".$estaciones[$i][1]."</label></td>\n";
               
               $linkRemitir = ModuloGetURL('app','EE_FuncionalidadesQX','user','UpdateTrasladoEstacion',array("datosPaciente"=>$datosPaciente,"estacionDestino"=>$estaciones[$i][0],"datos_estacion"=>$datos_estacion,'conducta'=>$conducta,'Prox_Dpto'=>$estaciones[$i][2]));
               
               $this->salida .= "		<td align=\"center\"><img src=\"".GetThemePath()."/images/uf.png\"  width='14' height='14'>&nbsp;<a href=\"$linkRemitir\">REMITIR</a></td>\n";
               $estaciones_id = $estaciones[$i][0];
               $descripcion_ee = $estaciones[$i][1];
               $this->salida .= "		<td align=\"center\"><a href=\"javascript:Descriptivo($estaciones_id,'$descripcion_ee')\">Descripción EE.</a></td>\n";
               $this->salida .= "	</tr>\n";
		}
		$this->salida .= "</table><br>\n";
          
          $href = ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>PANEL CIRUGIA</a><br>";
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

}//fin de la clase

?>

