<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MenuCartasHTML.class.php,v 1.1.1.1 2009/09/11 20:36:58 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */
  /**
  * Clase Vista: ConsultaAfiliadoCotizanteHTML 
  * Clase contiene la forma para de los datos del usuario
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1.1.1 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */
	class MenuCartasHTML
	{
		/**
		* Constructor de la clase
		*/
		function MenuCartasHTML(){}
		/**
    * @param array $action Vector de links de la aplicaion
		* @param array $datos_cotizante Vector de tipos de identificacion
		* @param array $tipos_afiliados Vector de tipos de afiliados
    * @param array $salida Vector de estados de afiliados
    * @param string $cuantos Vector de dependencias de la U.V.
    *
    * @return String
		*/
		function FormaCartaConvenio($action,$datos_cotizante,$departamentos)
		{
             //VAR_DUMP($departamentos);
            $html  = ThemeAbrirTabla('CONSULTA DE AFILIADOS');
            $vector_permiso=SessionGetVar("permisosAfiliaciones");
            $usuario=UserGetUID();    
            if($vector_permiso[$usuario]['perfil_id']=='C')//|| $vector_permiso[$usuario]['perfil_id']=='I'
            {    
                    $html .= "<form name=\"solicitud_conv\" id=\"solicitud_conv\" action=\"".$action['registrar']."\" method=\"post\">";
                    $html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
                    $html .= "	<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
                    $html .= "	  <tr class=\"modulo_table_list_title\">\n";
                    $html .= "	    <td colspan=\"2\">DATOS SOLICITUD</td>\n";
                    $html .= "    </tr>\n";
                    $html .= "    <tr class=\"modulo_table_list_title\">\n";
                    $html .= "      <td width='40%' style=\"text-align:left;text-indent:8pt\">";
                    $html .= "        DIRIGIDO A:";
                    $html .= "      </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "      <select id=\"dirigido\" name=\"dirigido\" class=\"select\" onchange=\"\">";
                    $html .= "        <option value=\"Doctor\">DOCTOR</option>\n";
                    $html .= "        <option value=\"Se&#241;or\">SE&#209;OR</option>\n";
                    $html .= "        <option value=\"Se&#241;ores\">SE&#209;ORES</option>\n";
                    $html .= "      </select>\n";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";
                    $html .= "    <tr class=\"modulo_table_list_title\">\n";
                    $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
                    $html .= "        NOMBRE DESTINATARIO:";
                    $html .= "      </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "            <input type=\"text\" class=\"input-text\" id=\"destinatario\" name=\"destinatario\" maxlength=\"40\" size=\"40\" value=\"\">";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
                    $html .= "        CARGO DESTINATARIO:";
                    $html .= "      </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "            <input type=\"text\" class=\"input-text\" id=\"cargo\" name=\"cargo\" maxlength=\"40\" size=\"40\" value=\"\">";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "      <td style=\"text-align:left;text-indent:8pt\">";
                    $html .= "        ENTIDAD:";
                    $html .= "      </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "            <input type=\"text\" class=\"input-text\" id=\"entidad\" name=\"entidad\" maxlength=\"40\" size=\"40\" value=\"\">";
                    $html .= "      </td>\n";
                    $html .= "    </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td style=\"text-align:left;text-indent:8pt\" align=\"center\">\n";
                    $html .= "         DEPARTAMENTO";
                    $html .= "       </td>\n";
                    $html .= "       <td class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "         <select class=\"select\" name=\"depto\" id=\"depto\" onchange=\"xajax_Llamar_ciudades(this.value);\">";
                    $html .= "           <option value=\"-1\">SELECCIONAR</option> \n";
                    for($i=0;$i<count($departamentos);$i++)
                    {                 
                        $html .= "       <option value=\"".$departamentos[$i]['tipo_dpto_id']."-".$departamentos[$i]['departamento']."\">".$departamentos[$i]['departamento']."</option> \n";
                    }
                    $html .= "         </select>\n";
                    $html .= "       </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td style=\"text-align:left;text-indent:8pt\" align=\"center\">\n";
                    $html .= "         CIUDAD";
                    $html .= "       </td>\n";
                    $html .= "       <td class=\"modulo_list_claro\" align=\"left\">\n";
                    $html .= "         <select class=\"select\" name=\"ciudades\" id=\"ciudades\" onchange=\"\" disabled>";
                    $html .= "           <option value=\"-1\" selected>SELECCIONAR</option> \n";
                    $html .= "         </select>\n";
                    $html .= "       </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         FECHA DE INICIO CONVENIO ";
                    $html .= "       </td>\n";
                    $html .= "       <td  width=\"17%\" align=\"left\" class=\"modulo_list_claro\"> \n";
                    $html .= "         <input type=\"text\" class=\"input-text\" name=\"fecha_ini\" id=\"fecha_ini\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
                    $html .= "         <sub>".ReturnOpenCalendario("solicitud_conv","fecha_ini","-")."</sub>";
                    $html .= "       </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         FECHA FIN CONVENIO ";
                    $html .= "       </td>\n";
                    $html .= "       <td  width=\"17%\" align=\"left\" class=\"modulo_list_claro\"> \n";
                    $html .= "         <input type=\"text\" class=\"input-text\" name=\"fecha_fin\" id=\"fecha_fin\"  size=\"12\" onkeypress=\"return acceptNum(event)\">\n";
                    $html .= "         <sub>".ReturnOpenCalendario("solicitud_conv","fecha_fin","-")."</sub>";
                    $html .= "       </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_list_claro\"> \n";
                    $html .= "       <td colspan='2'>\n";
                    $html .= "         &nbsp;";
                    $html .= "       </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td colspan='2' class=\"modulo_table_list_title\">";
                    $html .= "         COBERTURAS";
                    $html .= "       </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         CONSULTA ESPECIALISTA";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "          <select id=\"consulta_especialista\" name=\"consulta_especialista\" class=\"select\" onchange=\"\">";
                    for($i=0;$i<=100;$i=$i+5)
                    {
                        $html .= "          <option value=\"".$i."\">".$i."</option>\n";
                    }
                    $html .= "          </select> %\n";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                                        $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         HONORARIOS MEDICOS";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "          <select id=\"honorarios_med\" name=\"honorarios_med\" class=\"select\" onchange=\"\">";
                    for($i=0;$i<=100;$i=$i+5)
                    {
                        $html .= "          <option value=\"".$i."\">".$i."</option>\n";
                    }
                    $html .= "          </select> %\n";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                                        $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         GASTOS HOSPITALARIOS";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "          <select id=\"gasto_hosp\" name=\"gasto_hosp\" class=\"select\" onchange=\"\">";
                    for($i=0;$i<=100;$i=$i+5)
                    {
                        $html .= "          <option value=\"".$i."\">".$i."</option>\n";
                    }
                    $html .= "          </select> %\n";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         MEDICAMENTOS GENERICOS";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "          <select id=\"medicamentos_genricos\" name=\"medicamentos_genricos\" class=\"select\" onchange=\"\">";
                    for($i=0;$i<=100;$i=$i+5)
                    {
                        $html .= "          <option value=\"".$i."\">".$i."</option>\n";
                    }
                    $html .= "          </select> %\n";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                                        $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         RAYOS X E IMAGENOLOGIA";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "          <select id=\"rayos\" name=\"rayos\" class=\"select\" onchange=\"\">";
                    for($i=0;$i<=100;$i=$i+5)
                    {
                        $html .= "          <option value=\"".$i."\">".$i."</option>\n";
                    }
                    $html .= "          </select> %\n";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "         EXAMENES CLINICOS Y DE LABORATORIO";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "          <select id=\"examenes\" name=\"examenes\" class=\"select\" onchange=\"\">";
                    for($i=0;$i<=100;$i=$i+5)
                    {
                        $html .= "          <option value=\"".$i."\">".$i."</option>\n";
                    }
                    $html .= "          </select> %\n";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_table_list_title\">\n";
                    $html .= "       <td  style=\"text-align:left;text-indent:8pt\">";
                    $html .= "        PERSONA QUE FIRMARA LA CARTA";
                    $html .= "       </td>\n";
                    $html .= "      <td class=\"modulo_list_claro\" style=\"text-align:left;text-indent:8pt\">";
                    $html .= "            <input type=\"text\" class=\"input-text\" id=\"firma\" name=\"firma\" maxlength=\"40\" size=\"40\" value=\"\">";
                    $html .= "      </td>\n";
                    $html .= "     </tr>\n";
                    $html .= "     <tr class=\"modulo_list_claro\">\n";
                    $html .= "       <td colspan='2' align=\"center\">\n";
                    //$reporte = new GetReports();
                    //$html .= " <td align=\"center\">";
                    //$mostrar = $reporte->GetJavaReport('app','UV_Afiliaciones','Solicitud_Convenio',array("datos"=>$datos_cotizante),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                    //$funcion1 = $reporte->GetJavaFunction();
                    //$html .= $mostrar;
                    //$html .= " <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE USUARIO\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">GENERAR CERTIFICADO DE AFILIACION</a>\n";
                    $html .= "         <input class=\"input-submit\" type=\"submit\" name=\"crear\" value=\"Crear Solicitud\" >";//onclick=\"xajax_RegistroCarta(xajax.getFormValues('solicitud_conv'));\",'".$_REQUEST['afiliado_tipo_id']."','".$_REQUEST['afiliado_id']."'
                    $html .= "       </td>";
                    $html .= "      </tr>";
                    $html .= "  </table>";
                    $html .= "        </form>";
                    $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
                    $html .= "      <tr>\n";
                    $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
                    $html .= "              <td align=\"center\"><br>\n";
                    $html .= "                  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";//VolverTablaDatos
                    $html .= "              </td>";
                    $html .= "          </form>";
                    $html .= "      </tr>";
                    $html .= "  </table>";
                    $html .= ThemeCerrarTabla();
                    return $html;
              }      
		}
    /**
    * @param array $action Vector de links de la aplicaion
    * @param array $datos_cotizante Vector de tipos de identificacion
    * @param array $tipos_afiliados Vector de tipos de afiliados
    * @param array $salida Vector de estados de afiliados
    * @param string $cuantos Vector de dependencias de la U.V.
    *
    * @return String
    */
    function ImpresionCarta($action,$datos_carta,$datos_cotizante)
    {
        require "app_modules/UV_Afiliaciones/reports/pdf/carnet.php";
         //VAR_DUMP($departamentos);
        $html  = ThemeAbrirTabla('IMPRESION DE CARTA');
        $vector_permiso=SessionGetVar("permisosAfiliaciones");
        $usuario=UserGetUID();    
        if($vector_permiso[$usuario]['perfil_id']=='C')//|| $vector_permiso[$usuario]['perfil_id']=='I'
        {    
          $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
          $html .= "     <tr class=\"modulo_list_claro\">\n";
          $html .= "       <td colspan='2' align=\"center\">\n";
          $reporte = new GetReports();
          $html .= " <td align=\"center\">";
          $mostrar = $reporte->GetJavaReport('app','UV_Afiliaciones','Solicitud_Convenio',array("datos"=>array('carta'=>$datos_carta,'cotizante'=>$datos_cotizante)),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $funcion1 = $reporte->GetJavaFunction();
          $html .= $mostrar;
          $html .= " <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE USUARIO\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">GENERAR CERTIFICADO DE AFILIACION</a>\n";
          //$html .= "         <input class=\"input-submit\" type=\"button\" name=\"crear\" value=\"Crear Solicitud\" onclick=\"xajax_RegistroCarta(xajax.getFormValues('solicitud_conv'));".$funcion1."\">";//,'".$_REQUEST['afiliado_tipo_id']."','".$_REQUEST['afiliado_id']."'
          $html .= "       </td>";
          $html .= "      </tr>";
          $html .= "  </table>";
          $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
          $html .= "      <tr>\n";
          $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
          $html .= "              <td align=\"center\"><br>\n";
          $html .= "                  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";//VolverTablaDatos
          $html .= "              </td>";
          $html .= "          </form>";
          $html .= "      </tr>";
          $html .= "  </table>";
              
          $html .= ThemeCerrarTabla();
          $RUTA = "cache/carnet_jaime.pdf";
          $mostrar.="<script>\n";
          $mostrar.="  var rem=\"\";\n";
          $mostrar.="  function abreVentanaHT(){\n";
          $mostrar.="    var nombre=\"\"\n";
          $mostrar.="    var url2=\"\"\n";
          $mostrar.="    var str=\"\"\n";
          $mostrar.="    var ALTO=screen.height\n";
          $mostrar.="    var ANCHO=screen.width\n";
          $mostrar.="    var nombre=\"REPORTE\";\n";
          $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
          $mostrar.="    var url2 ='$RUTA';\n";
          $mostrar.="    rem = window.open(url2, nombre, str)};\n";
          $mostrar.="</script>\n";
          $html .=$mostrar;
          return $html;
        }      
    }
    /**
    * @param array $action Vector de links de la aplicaion
    * @param array $datos_cotizante Vector de tipos de identificacion
    * @param array $tipos_afiliados Vector de tipos de afiliados
    * @param array $salida Vector de estados de afiliados
    * @param string $cuantos Vector de dependencias de la U.V.
    *
    * @return String
    */
    function ImpresionCarnets($action,$datos_carta,$datos_cotizante)
    {
        require "app_modules/UV_Afiliaciones/reports/pdf/carnet.php";
         //VAR_DUMP($departamentos);
        $html  = ThemeAbrirTabla('CONSULTA DE AFILIADOS');
        $vector_permiso=SessionGetVar("permisosAfiliaciones");
        $usuario=UserGetUID();    
        if($vector_permiso[$usuario]['perfil_id']=='C')//|| $vector_permiso[$usuario]['perfil_id']=='I'
        {    

                $html .= "     <tr class=\"modulo_list_claro\">\n";
                $html .= "       <td colspan='2' align=\"center\">\n";
                $reporte = new GetReports();
                $html .= " <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE USUARIO\">&nbsp;<a href=\"javascript:abreVentanaHT();\" class=\"label_error\">CARNET</a>\n";
                //$html .= "         <input class=\"input-submit\" type=\"button\" name=\"crear\" value=\"Crear Solicitud\" onclick=\"xajax_RegistroCarta(xajax.getFormValues('solicitud_conv'));".$funcion1."\">";//,'".$_REQUEST['afiliado_tipo_id']."','".$_REQUEST['afiliado_id']."'
                $html .= "       </td>";
                $html .= "      </tr>";
                $html .= "  </table>";
                $html .= "        </form>";
                $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
                $html .= "      <tr>\n";
                $html .= "          <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
                $html .= "              <td align=\"center\"><br>\n";
                $html .= "                  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";//VolverTablaDatos
                $html .= "              </td>";
                $html .= "          </form>";
                $html .= "      </tr>";
                $html .= "  </table>";
                    
                $html .= ThemeCerrarTabla();
                $RUTA = "cache/carnet_jaime.pdf";
                $mostrar.="<script>\n";
                $mostrar.="  var rem=\"\";\n";
                $mostrar.="  function abreVentanaHT(){\n";
                $mostrar.="    var nombre=\"\"\n";
                $mostrar.="    var url2=\"\"\n";
                $mostrar.="    var str=\"\"\n";
                $mostrar.="    var ALTO=screen.height\n";
                $mostrar.="    var ANCHO=screen.width\n";
                $mostrar.="    var nombre=\"REPORTE\";\n";
                $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                $mostrar.="    var url2 ='$RUTA';\n";
                $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                $mostrar.="</script>\n";
                $html .=$mostrar;
                return $html;
        }      
    }
	}
?>