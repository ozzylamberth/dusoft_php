<?

 /**
 * $Id: app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML.php,v 1.11 2006/06/23 16:55:58 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Estacion de Enfermeria de para el ajuste de los Insumos y Medicamentos despachados al usuario para el paciente
 */



/**
*		class app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML
*
*		Clase que maneja todas las funciones de vistas y consultas a la base de datos
*		relacionadas a la estaci&oacute;n de Enfermer&iacute;a de Insumos y Medicamentos despachados a un usuario para los pacientes
*		ubicacion => app_modules/EstacionEnfermeria_IYM_Usuarios/userclasses/app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML.php
*		fecha creaci&oacute;n => 04/05/2004 10:35 am
*
*		@Author Lorena Aragón G.
*		@version =>
*		@package SIIS
*/
class app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML extends app_EstacionEnfermeria_IYM_Usuarios_user
{

	/**
	*		app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML()
	*
	*		constructor
	*
	*		@Author Lorena Aragón G.
	*		@access Private
	*		@return boolean
	*/
	function app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML(){
	  $this->app_EstacionEnfermeria_IYM_Usuarios_user(); //Constructor del padre 'modulo'
		$this->salida = "";
		return true;
	}
     
     /**
          *		app_EstacionEnfermeria_IYM_Usuarios_userclasses_HTML()
          *
          *		FomaMyIDespachosPendientes
          *
     *   Forma que visializa los despachos realizados al usuario y estan pendientes
          *		@Author Lorena Aragón G.
          *		@access Private
          *		@return boolean
          */
     function FomaMyIDespachosPendientes(){
          $this->salida  = ThemeAbrirTabla('DESPACHOS PENDIENTES POR ASIGNAR A LOS PACIENTES');
          $this->salida .="<script language='javascript'>";
          $this->salida .= 'function mOvr(src,clrOver){';
          $this->salida .= '  src.style.background = clrOver;';
          $this->salida .= '}';
          $this->salida .= 'function mOut(src,clrIn){';
          $this->salida .= '  src.style.background = clrIn;';
          $this->salida .= '}';
          $this->salida .= '</script>';
          $this->salida .= "		  <table class='modulo_table_title' border='0' width='100%'>\n";
          $this->salida .= "			<tr class='modulo_table_title'>\n";
          $this->salida .= "			<td>Empresa</td>\n";
          $this->salida .= "			<td>Centro Utilidad</td>\n";
          $this->salida .= "			<td>Unidad Funcional</td>\n";
          $this->salida .= "			<td>Departamento</td>\n";
          $this->salida .= "			</tr>\n";
          $this->salida .= "			<tr class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['empresa_descripcion']."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['centro_utilidad_descripcion']."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['unidad_funcional_descripcion']."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['departamento_descripcion']."</td>\n";
          $this->salida .= "			</tr>\n";
          $this->salida .= "			</table><BR>\n";
          $this->salida .= "    <table width=\"100%\" border=\"0\" align=\"center\">";
          $this->salida .= "    <tr><td align=\"center\">";
          $this->salida .=      $this->SetStyle("MensajeError");
          $this->salida .= "    </td></tr>";
          $this->salida .= "	  </table><BR>\n";
          $solicitudes=$this->SolicitudesPendientesIyM();
          $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
          if($solicitudes){
               $VectorSel=$_REQUEST['Seleccion'];
               $VectorCan=$_REQUEST['Cantidad'];
               $i=1;
               foreach($solicitudes[0] as $BodegaId=>$Vector){
               $name='';
               $name='name'.$i;
               $name=ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','BusquedaPaciente',array("empresa_id"=>$solicitudes[1][$BodegaId][0],"centro_utilidad"=>$solicitudes[1][$BodegaId][1],"BodegaId"=>$BodegaId,"nom_Bodega"=>$solicitudes[1][$BodegaId][3]));
                    $this->salida .= "     <form name=\"forma$i\" action=\"$name\" method=\"post\">";
               $this->salida .= "		  <table border='0' width='100%' class=\"modulo_table_list_title\">\n";
               $this->salida .= "			<tr class='modulo_table_title'>\n";
               $this->salida .= "			<td colspan=\"8\">BODEGA => ".$solicitudes[1][$BodegaId][3]."</td>\n";
               $this->salida .= "			</tr>\n";
               $this->salida .= "			<tr class='modulo_table_title'>\n";
               $this->salida .= "			<td width='15%'>SOLICITUD</td>\n";
               $this->salida .= "			<td width='10%'>USUARIO</td>\n";
               $this->salida .= "			<td width='10%'>FECHA</td>\n";
               $this->salida .= "			<td>PRODUCTO</td>\n";
               $this->salida .= "			<td width='5%'>CANTIDAD DESPACHADA</td>\n";
               $this->salida .= "			<td width='5%'>CANTIDAD AJUSTADA</td>\n";
               $this->salida .= "			<td width='5%'>CANTIDAD A AJUSTAR</td>\n";
               $this->salida .= "			<td width='5%'>&nbsp;</td>\n";
               $this->salida .= "			</tr>\n";
               foreach($Vector as $Solicitud=>$Vector1){
                    foreach($Vector1 as $Consecutivo=>$Datos){
                    if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                    $this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
                    if($Solicitud!=$SolicitudAnt){
                    $this->salida .= "			<td align=\"center\" class=\"label\" rowspan=\"".sizeof($Vector1)."\">$Solicitud</td>\n";
                    $SolicitudAnt=$Solicitud;
                    }
                    $this->salida .= "			<td align=\"left\">".$Datos['usuario_bodega']."</td>\n";
                    $this->salida .= "			<td align=\"center\">".$Datos['fecha']."</td>\n";
                    $this->salida .= "			<td align=\"left\">".$Datos['codigo_producto']." => ".$Datos['descripcion']."</td>\n";
                    $this->salida .= "			<td align=\"left\">".$Datos['cantidad']."</td>\n";
                    $this->salida .= "			<td align=\"left\">".$Datos['cantidad_ajustada']."</td>\n";
                    if($VectorCan[$Datos['consecutivo']]){$cant=$VectorCan[$Datos['consecutivo']];}else{$cant=($Datos['cantidad']-$Datos['cantidad_ajustada']);}
                    $this->salida .= "			<td><input type=\"text\" class=\"input-text\" size=\"2\" name=\"Cantidad[".$Datos['consecutivo']."]\" value=\"$cant\"></td>\n";
                    $che='';
                    if($VectorSel[$Datos['consecutivo']]){$che='checked';}
                    $this->salida .= "			<td><input type=\"checkbox\" name=\"Seleccion[".$Datos['consecutivo']."]\" value=\"1\" $che></td>\n";
                    $this->salida .= "			<input type=\"hidden\" name=\"Limites[".$Datos['consecutivo']."]\" value=\"".($Datos['cantidad']-$Datos['cantidad_ajustada'])."\">";
                    $this->salida .= "			</tr>\n";
                    $y++;
                    }
               }
               $this->salida .= "		  <tr class='modulo_table_title'><td colspan=\"8\" align=\"right\"><input type=\"submit\" name=\"ASIGNAR\" class=\"input-sumit\" value=\"ASIGNAR PACIENTE\"></td></tr>";
               $this->salida .= "		  </table><BR><BR>";
               $this->salida .="    </form>";
               $i++;
               }
          }
          $this->salida .= "		  <table border='0' width='100%' class=\"normal_10\">\n";
          $action=ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $action1=ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','ConsultaMyIDespachosPendientes',array("estacion"=>$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']));
          $this->salida .= "		  <tr><td align=\"center\"><a href=\"$action\">VOLVER A EE</a>  <b>-</b> <a href=\"$action1\">REFRESCAR</a></td></tr>";
          $this->salida .= "		  </table>";
          $this->salida .= ThemeCerrarTabla();
          return true;
     }

     /*funcion que debe estar en el mod estacione_controlpaciente*/
     /*
     *
     *
     *		@Author Jairo Duvan Diaz Martinez
     *		@access Private
     *		@return bool
     */
     function ListRevisionPorSistemas($empresa_id,$centro_utilidad,$BodegaId,$nom_Bodega,$VectorSel,$VectorCan){
          $vec[0]=$empresa_id;
          $vec[1]=$centro_utilidad;
          $vec[2]=$BodegaId;
          $vec[3]=$nom_Bodega;
          $vec[4]=$VectorSel;
          $vec[5]=$VectorCan;
          $mostrar ="\n<script language='javascript'>\n";
          $mostrar.="function mOvr(src,clrOver) {;\n";
          $mostrar.="src.style.background = clrOver;\n";
          $mostrar.="}\n";
          
          $mostrar.="function mOut(src,clrIn) {\n";
          $mostrar.="src.style.background = clrIn;\n";
          $mostrar.="}\n";
          $mostrar.="</script>\n";
          $this->salida .="$mostrar";
          $this->salida  = ThemeAbrirTabla('PACIENTES EN LA ESTACION');
          $this->salida .= "		  <table align=\"center\" class='modulo_table_title' border='0' width='90%'>\n";
          $this->salida .= "			<tr class='modulo_table_title'>\n";
          $this->salida .= "			<td>Empresa</td>\n";
          $this->salida .= "			<td>Centro Utilidad</td>\n";
          $this->salida .= "			<td>Unidad Funcional</td>\n";
          $this->salida .= "			<td>Departamento</td>\n";
          $this->salida .= "			</tr>\n";
          $this->salida .= "			<tr class='modulo_list_oscuro'>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['empresa_descripcion']."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['centro_utilidad_descripcion']."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['unidad_funcional_descripcion']."</td>\n";
          $this->salida .= "			<td>".$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']['departamento_descripcion']."</td>\n";
          $this->salida .= "			</tr>\n";
          $this->salida .= "			</table><BR>\n";
          $this->salida .= "    <table width=\"90%\" border=\"0\" align=\"center\">";
          $this->salida .= "    <tr><td align=\"center\">";
          $this->salida .=      $this->SetStyle("MensajeError");
          $this->salida .= "    </td></tr>";
          $this->salida .= "		</table>\n";
          //Muestra los Productos y las Cantidades Elegidas para ajustar
          $Productos=$this->DatosConsecutivosSeleccionados($VectorSel);
          $this->salida .= "		  <table border='0' width='90%' align=\"center\">\n";
          $this->salida .= "			<tr class='modulo_table_title'>\n";
          $this->salida .= "			<td colspan=\"6\">PRODUCTOS SELECCIONADOS</td>\n";
          $this->salida .= "			</tr>\n";
          $this->salida .= "			<tr class='modulo_table_title'>\n";
          $this->salida .= "			<td colspan=\"6\">BODEGA => ".$nom_Bodega."</td>\n";
          $this->salida .= "			</tr>\n";
          $this->salida .= "			<tr class='modulo_table_title'>\n";
          $this->salida .= "			<td width='15%'>SOLICITUD</td>\n";
          $this->salida .= "			<td width='10%'>USUARIO</td>\n";
          $this->salida .= "			<td width='10%'>FECHA</td>\n";
          $this->salida .= "			<td>PRODUCTO</td>\n";
          $this->salida .= "			<td width='5%'>CANTIDAD DESPACHADA</td>\n";
          $this->salida .= "			<td width='5%'>CANTIDAD A AJUSTAR</td>\n";
          $this->salida .= "			</tr>\n";
          foreach($Productos as $Solicitud=>$Vector1){
               foreach($Vector1 as $Consecutivo=>$Datos){
               if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
               $this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
               if($Solicitud!=$SolicitudAnt){
                    $this->salida .= "			<td align=\"center\" class=\"label\" rowspan=\"".sizeof($Vector1)."\">$Solicitud</td>\n";
                    $SolicitudAnt=$Solicitud;
               }
               $this->salida .= "			<td align=\"left\">".$Datos['usuario_bodega']."</td>\n";
               $this->salida .= "			<td align=\"center\">".$Datos['fecha']."</td>\n";
               $this->salida .= "			<td align=\"left\">".$Datos['codigo_producto']." => ".$Datos['descripcion']."</td>\n";
               $this->salida .= "			<td align=\"left\">".$Datos['cantidad']."</td>\n";
               $this->salida .= "			<td align=\"left\">".$VectorCan[$Consecutivo]."</td>\n";
               $this->salida .= "			</tr>\n";
               $y++;
               }
          }
          $this->salida .= "		  </table><BR>";
          //fin muestra
          $action=ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','AsignarCuentaPaciente',array("empresa_id"=>$empresa_id,"centro_utilidad"=>$centro_utilidad,"BodegaId"=>$BodegaId,"nom_Bodega"=>$nom_Bodega,"VectorSel"=>$VectorSel,"VectorCan"=>$VectorCan));
          $this->salida .= "<form name=\"forma\" action=\"$action\" method=\"post\">";
          
          $this->FrmListadoPacientesEstacion();
          $this->FrmListadoPacientesConsultaUrgencias();
          
          $this->salida .= "		  <table border='0' width='80%' class=\"normal_10\" align=\"center\">\n";
          $this->salida .= "		  <tr><td align=\"right\"><input type=\"submit\" value=\"CARGAR A LA CUENTA\" name=\"Cargar\" class=\"input-text\"></td></tr>";
          $this->salida .= "		  </table><BR>";
          $this->salida .= "</form>";

          $this->salida .= "		  <table border='0' width='100%' class=\"normal_10\">\n";
          $action=ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','ConsultaMyIDespachosPendientes',array("estacion"=>$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS'],"Seleccion"=>$VectorSel,"Cantidad"=>$VectorCan));
          $this->salida .= "		  <tr><td align=\"center\"><a href=\"$action\">REGRESAR</a></td></tr>";
          $this->salida .= "		  </table>";
          $this->salida .= "		  <table border='0' width='100%' class=\"normal_10\">\n";
          $action=ModuloGetURL('app','EE_PanelEnfermeria','user','FrmPanelEstacion');
          $this->salida .= "		  <tr><td align=\"center\"><a href=\"$action\">VOLVER A EE</a></td></tr>";
          $this->salida .= "		  </table>";
          $this->salida.=ThemeCerrarTabla();
          return true;
     }/*funcion que debe estar en el mod estacione_controlpaciente*/

     
     /**
     * Forma para mostrar el listado de pacientes en la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesEstacion()
     {
          $listadoPacientes = $this->GetPacientesInternados();
          if($listadoPacientes)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"90%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='6' height='30'>PACIENTES INTERNADOS EN LA ESTACION</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">HAB.</td>\n";
               $this->salida .= "      <td align=\"center\">CAMA</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
                    
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes as $k => $filaPacinte)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte[nombre_completo]</a>";
                    $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/hospitalizacion.png\" border=0 title='Paciente Hospitalizado.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td>$filaPacinte[pieza]</td>\n";
                    $this->salida .= "      <td>$filaPacinte[cama]</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte['fecha_ingreso']) . "</td>\n";
	               $this->salida .= "	<td align=\"center\"><input type=\"radio\" name=\"seleccionIn\" value=\"".$filaPacinte['ingreso'].",".$filaPacinte['numerodecuenta'].",".$filaPacinte['plan_id']."\"></td>\n";
                    $this->salida .= "</tr>\n";
               }
               $this->salida .= "  </table>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION NO CUENTA CON PACIENTES HOSPITALIZADOS</div>";
          }
     }

     /**
     * Forma para mostrar el listado de pacientes en consulta de urgencias de la Estacion de Enfermeria
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmListadoPacientesConsultaUrgencias()
     {
          $listadoPacientes2 = $this->GetPacientesConsultaUrgencias();
          if($listadoPacientes2)
          {
               $this->IncludeJS('javascripts/funciones.js', $contenedor='app', $modulo='EE_PanelEnfermeria');
          
               $this->salida .= "<br>\n";
               $this->salida .= "<table align=\"center\" width=\"90%\"  border=\"0\" >\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td colspan='4' height='30'>PACIENTES EN CONSULTA DE URGENCIAS</td>\n";
               $this->salida .= "  </tr>\n";
               $this->salida .= "  <tr class=\"modulo_table_title\">\n";
               $this->salida .= "      <td width=\"15\">&nbsp;</td>\n";
               $this->salida .= "      <td align=\"center\">NOMBRE DEL PACIENTE</td>\n";
               $this->salida .= "      <td align=\"center\">TIEMPO HOSP.</td>\n";
               $this->salida .= "      <td align=\"center\">&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
          
               $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
               
               foreach($listadoPacientes2 as $k2 => $filaPacinte2)
               {
                    if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
                    $url_info_paciente = ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','MostrarDatosIngreso',array('ingreso'=>$filaPacinte2['ingreso']));
                    $nombre_paciente = "<a href='$url_info_paciente'>$filaPacinte2[nombre_completo]</a>";
                    $this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
                    $imagenPaciente = "<img src=\"".GetThemePath()."/images/consulta_ur.png\" border=0 title='Ingresar y asignar cama al paciente.'>";
                    $this->salida .= "      <td>$imagenPaciente</td>\n";
                    $this->salida .= "      <td>$nombre_paciente</td>\n";
                    $this->salida .= "      <td align=\"right\">" . $this->GetDiasHospitalizacion($filaPacinte2['fecha_ingreso']) . "</td>\n";
	               $this->salida .= "	<td align=\"center\"><input type=\"radio\" name=\"seleccionIn\" value=\"".$filaPacinte2['ingreso'].",".$filaPacinte2['numerodecuenta'].",".$filaPacinte2['plan_id']."\"></td>\n";
                    $this->salida .= "  </tr>\n";
               }
               $this->salida .= "  </table><br>\n";
          }
          else
          {
          	$this->salida .= "<br><div align=\"center\" class=\"label_mark\">LA ESTACION NO CUENTA CON PACIENTES EN CONSULTA DE URGENCIAS</div><br>";
          }
     }
     
     /**
     * Forma para mostrar los datos de ingreso del paciente.
     *
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function MostrarDatosIngreso()
     {
          if(!$_REQUEST['ingreso'])
          {
               $url     = ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','LlamaListRevisionPorSistemas',array('datos_estacion'=>$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']));
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          if(!$datosPaciente = $this->GetDatosPaciente($_REQUEST['ingreso']))
          {
     
               $url     = ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','LlamaListRevisionPorSistemas',array('datos_estacion'=>$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']));
               $titulo  = "DATOS DEL PACIENTE";
               $mensaje = "Error : El metodo ";
     
               $this->FrmMSG($url, $titulo, $mensaje, $url_titulo);
     
               return true;
          }
     
          $ContactosPaciente = $this->GetContactosPaciente($_REQUEST['ingreso']);
     
          $this->salida .= ThemeAbrirTabla('INFORMACI&Oacute;N DEL PACIENTE','60%');//[ '.$datos_estacion[descripcion5].' ] -
          $this->salida .= "<br><table align=\"center\"  width=70% cellpadding=\"2\" cellspacing=\"2\" border=\"1\" class=\"modulo_table\">\n";
     
          $this->salida .= "  <tr class=\"modulo_table\">\n";
          $this->salida .= "      <td class=\"label\">RESPONSABLE</td><td class=\"modulo_list_claro\">".$datosPaciente['nombre_tercero']."</td >\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table\">\n";
          $this->salida .= "      <td class=\"label\">PLAN</td><td class=\"modulo_list_claro\">".$datosPaciente['plan_descripcion']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_table\">\n";
          $this->salida .= "      <td class=\"label\">TIPO AFILIADO</td><td class=\"modulo_list_claro\">".$datosPaciente['tipo_afiliado_nombre']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "</table>\n";
     
          $this->salida .= "<br><table width=70% align=\"center\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">PACIENTE</td><td class=\"modulo_list_claro\"><b>".strtoupper($datosPaciente['primer_nombre'])." ".strtoupper($datosPaciente['segundo_nombre'])." ".strtoupper($datosPaciente['primer_apellido'])." ".strtoupper($datosPaciente['segundo_apellido'])."</b></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">IDENTIFICACION</td><td class=\"modulo_list_claro\"><b>".$datosPaciente['tipo_id_paciente']." ".$datosPaciente['paciente_id']."</b></td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\" >\n";
          $this->salida .= "      <td class=\"label\">HISTORIA CLINICA</td><td class=\"modulo_list_claro\">".$datosPaciente['historia_prefijo']." ".$datosPaciente['historia_numero']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">SEXO</td><td class=\"modulo_list_claro\">".$datosPaciente['sexo_id']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\">FECHA NACIMIENTO</td><td class=\"modulo_list_claro\">".$datosPaciente['fecha_nacimiento']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\" nowrap=\"yes\">DIRECCION RESIDENCIA</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$datosPaciente['residencia_direccion'].". ".$datosPaciente['municipio'].", ".$datosPaciente['departamento'].", ".$datosPaciente['pais']."</td>\n";
          $this->salida .= "  </tr>\n";
          $this->salida .= "  <tr class=\"modulo_list_oscuro\">\n";
          $this->salida .= "      <td class=\"label\" nowrap=\"yes\">TELEFONO RESIDENCIA</td><td class=\"modulo_list_claro\" nowrap=\"yes\">".$datosPaciente['residencia_telefono']."</td>\n";
          $this->salida .= "  </tr>\n";
          if($ContactosPaciente && $ContactosPaciente != "ShowMensaje")
          for($i=0; $i<sizeof($ContactosPaciente); $i++)
          {
               $this->salida .= "<tr valign=\"top\">\n";
               $this->salida .= "  <td class=\"label\">ACUDIENTE ".($i+1)."</td>\n";
               $this->salida .= "  <td>".strtoupper($ContactosPaciente[$i][nombre_completo])."\n";
               if($ContactosPaciente[$i][parentesco]){
                    $this->salida .= "          <br> PARENTESCO: ".$ContactosPaciente[$i][parentesco]."\n";
               }
               if($ContactosPaciente[$i][telefono]){
                    $this->salida .= "          <br> TELEFONO: ".$ContactosPaciente[$i][telefono]."\n";
               }
               if($ContactosPaciente[$i][direccion]){
                    $this->salida .= "          <br> DIRECCI&Oacute;N: ".$ContactosPaciente[$i][direccion]."\n";
               }
               if($i>0){
                    $this->salida .= "      <br>";
               }
               $this->salida .= "      </td><td>&nbsp;</td>\n";
               $this->salida .= "  </tr>\n";
          }
     
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\">&nbsp;</td></tr>\n";
          $link=ModuloGetURL('app','EstacionEnfermeria_IYM_Usuarios','user','LlamaListRevisionPorSistemas',array('datos_estacion'=>$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS']));
          
          $this->salida .= "<tr><td align=\"center\" colspan=\"2\"><a href=\"$link\"><b><sub>REGRESAR</sub></b></a></td></tr>\n";
          $this->salida .= "</table><br>\n";
          $this->salida .= themeCerrarTabla();
          return true;
     }// fin MostrarDatosIngreso

     /**
     * Forma para mostrar mensaje
     *
     * @param string $url opcional url de retorno
     * @param string $titulo opcional titulo de la ventana
     * @param string $mensaje opcional mensaje a mostrar
     * @param string $url_titulo opcional
     * @return boolean True si se ejecuto correctamente
     * @access private
     */
     function FrmMSG($url='', $titulo='', $mensaje='', $url_titulo='VOLVER')
     {
          if(empty($titulo))  $titulo  = $this->titulo;
          if(empty($mensaje)) $mensaje = "EL USUARIO NO TIENE PERMISOS EN ESTE MODULO.";
     
          $this->salida  = themeAbrirTabla($titulo);
          $this->salida .= "<div class='titulo3' align='center'><br><br><b>$mensaje</b>";
     
          if($url)
          {
               $this->salida.="  <table width='100%' border='0' cellspacing=\"0\" cellpadding=\"8\">\n";
               $this->salida.="    <tr>\n";
               $this->salida.="        <td align='center' class=\"label_error\">\n";
               $this->salida.="            <a href='$url'>$url_titulo</a>\n";
               $this->salida.="        </td>\n";
               $this->salida.="    </tr>\n";
               $this->salida.="  </table>\n";
     
          }
     
          $this->salida .= "<br><br></div>";
          $this->salida .= themeCerrarTabla();
          return true;
     }

     /**
     * Funcion que se encarga de visualizar un error en un campo
     * @return string
     */
	function SetStyle($campo){
		if ($this->frmError[$campo] || $campo=="MensajeError"){
		  if ($campo=="MensajeError"){
				return ("<tr><td colspan=\"3\" class='label_error' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

  


}//fin class
?>
