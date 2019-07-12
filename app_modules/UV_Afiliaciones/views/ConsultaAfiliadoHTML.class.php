<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultaAfiliadoHTML.class.php,v 1.2 2009/09/23 21:42:42 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: ConsultaAfiliadoHTML 
  * Clase contiene metodos para la consulta de afiliados del sistema
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Jaime Gomez
  */

	class ConsultaAfiliadoHTML
	{
		/**
		* Constructor de la clase
		*/
		function ConsultaAfiliadoHTML(){}
		/**
	  * @param array $action Vector de links de la aplicaion
		* @param array $tipos_identificacion Vector de tipos de identificacion
		* @param array $tipos_afiliados Vector de tipos de afiliados
    * @param array $estados_afiliados Vector de estados de afiliados
    * @param array $dependencias Vector de dependencias de la U.V.
    * @param array $estamentos Vector de estamentos
    * @param array $tipos_aportantes Vector de tipos aportantes
    * @param array $convenios Arreglo de datos con la entidad convenio
    * @param array $planes Arreglo de datos con los planes
    *
    * @return String
		*/
		function FormaConsultaAfiliado($action,$tipos_identificacion,$tipos_afiliados,$estados_afiliados,$dependencias,$estamentos,$tipos_aportantes,$convenios,$planes)
		{
      $ctl = AutoCarga::factory('ClaseUtil');
            
      $html  = ThemeAbrirTabla('CONSULTA DE AFILIADOS');
      $html .= $ctl->LimpiarCampos();
      $html .= "<script>\n";
			$html .= "	function ValidarDatos(forma)\n";
			$html .= "	{\n";
      $html .= "    xajax_BuscarDatos((xajax.getFormValues('consulta_afiliacion')),1,0);\n";
			$html .= "	}\n";
			$html .= "	function HabilitarEntidad()\n";
			$html .= "	{\n";
			$html .= "	  document.getElementById('entidad_convenio').disabled=false;\n";
			$html .= "	}\n";			
      $html .= "	function InhabilitarEntidad()\n";
			$html .= "	{\n";
			$html .= "	  document.getElementById('entidad_convenio').disabled=true;\n";
			$html .= "	}\n";
      $html .= "	function LimpiarCapas()\n";
			$html .= "	{\n";
			$html .= "		document.getElementById('tipo_afiliado_div').innerHTML = ''\n";
			$html .= "		document.getElementById('rango_afiliado_div').innerHTML = ''\n";
			$html .= "	}\n";      
      $html .= "	function Habilitar(valor)\n";
			$html .= "	{\n";
			$html .= "		if(valor == '6')\n";
			$html .= "		  document.getElementById('edad_maxima').style.display = 'block'\n";
			$html .= "		else\n";
			$html .= "		  document.getElementById('edad_maxima').style.display = 'none'\n";
			$html .= "	}\n";
      $html .= "</script>\n";
			$html .= "<form name=\"consulta_afiliacion\" id=\"consulta_afiliacion\" action=\"javascript:ValidarDatos(document.registrar_afiliacion)\" method=\"post\">";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"formulacion_table_list\">\n";
			$html .= "			<td colspan=\"6\">CONSULTA DE AFILIADOS</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
			$html .= "			<td width=\"18%\" style=\"text-align:left;text-indent:4pt\">TIPO DOCUMENTO: </td>\n";
			$html .= "			<td width=\"40%\" align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
			$html .= "				<select name=\"afiliado_tipo_id\" id='afiliado_tipo_id' class=\"select\" onchange=\"HabilitarTipoId(this.value)\">\n";
			$html .= "					<option value=\"0\">---Seleccionar---</option>\n";
			
			foreach($tipos_identificacion as $key => $datos)
        $html .= "					<option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";
      
      $html .= "				</select>\n";
			$html .= "			</td>\n";
      $html .= "      <td width=\"15%\" style=\"text-align:left;text-indent:4pt\" >PRIMER APELLIDO: </td>\n";
      $html .= "      <td width=\"27%\" align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"primer_apellido\" id=\"primer_apellido\" value=\"\" size=\"25\" >\n";
      $html .= "      </td>\n";
			$html .= "		</tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td style=\"text-align:left;text-indent:4pt\" >NRO DOCUMENTO: </td>\n";
      $html .= "      <td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name=\"afiliado_id\" id=\"afiliado_id\" value=\"\" size=\"25\" disabled>\n";
      $html .= "      </td>\n";
      $html .= "      <td style=\"text-align:left;text-indent:4pt\" >SEGUNDO APELLIDO: </td>\n";
      $html .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" id=\"segundo_apellido\" name=\"segundo_apellido\" value=\"\" size=\"25\" >\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
 			$html .= "        <td style=\"text-align:left;text-indent:4pt\" >EDAD: </td>\n";
			$html .= "        <td class=\"modulo_list_claro\" width=\"20%\">\n";
			$html .= "        	<select name=\"edad_signo\" class=\"select\" onChange=\"Habilitar(this.value)\">\n";
      $html .= "          	<option value=\"1\" > = </option>\n";
      $html .= "           	<option value=\"2\" > > </option>\n";
      $html .= "           	<option value=\"3\" > >=</option>\n";
      $html .= "           	<option value=\"4\" > < </option>\n";      
      $html .= "           	<option value=\"5\" > <=</option>\n";
      $html .= "           	<option value=\"6\" >entre</option>\n";
			$html .= "          </select>\n";
 			$html .= "         	<input type=\"text\" class=\"input-text\" name=\"edad\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
 			$html .= "        </td>\n";
 			$html .= "        <td class=\"modulo_list_claro\" width=\"20%\">\n";
      $html .= "          <div id=\"edad_maxima\" style=\"display:none\">\n";
      $html .= "        	   Y <input type=\"text\" class=\"input-text\" name=\"edad_maxima\" style=\"width:50%\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
			$html .= "          </div>\n";
			$html .= "        </td>\n";
      $html .= "        <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:4pt\" >NOMBRES: </td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "          <input type=\"text\" class=\"input-text\" id=\"nombre_afiliado\" name=\"nombre_afiliado\" value=\"\" size=\"25\">\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "          <td align=\"left\" style=\"text-align:left;text-indent:4pt\" class=\"formulacion_table_list\">\n";
      $html .= "            FECHA AFILIACION";
      $html .= "          </td>\n";
      $html .= "          <td colspan='4' class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "            &nbsp;ENTRE&nbsp; <input type=\"hidden\"name=\"f1_mod\" id=\"f1_mod\" value=\"".$fecha_inicial."\">\n";
      $html .= "            <input type=\"text\" class=\"input-text\" name=\"fecha1\" id=\"fecha1\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
      $html .= "            <sub>".ReturnOpenCalendario("consulta_afiliacion","fecha1","-")."</sub>";
      $html .= "            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Y&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<input type=\"hidden\"name=\"f1_mod\" id=\"f1_mod\" value=\"".$fecha_inicial."\">\n";
      $html .= "            <input type=\"text\" class=\"input-text\" name=\"fecha2\" id=\"fecha2\" size=\"12\" onkeypress=\"return acceptNum(event)\" value=\"\">\n";
      $html .= "            <sub>".ReturnOpenCalendario("consulta_afiliacion","fecha2","-")."</sub>";
      $html .= "          </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td id=\"estamentox\" class=\"formulacion_table_list\" style=\"text-align:left;text-indent:4pt;\" >ESTAMENTO</td>\n";
      $html .= "        <td align='left' class=\"modulo_list_claro\" colspan=\"2\">";
      $html .= "          <select name=\"estamento_id\" id=\"estamento_id\" class=\"select\" onChange=\"xajax_EntidadesConvenios(this.value)\">\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      foreach($estamentos as $key => $datos)
        $html .= "        <option value=\"".$datos['estamento_id']."\" >".$datos['descripcion_estamento']."</option>\n";
      
      $html .= "          </select>\n";
      $html .= "      </td>\n";

      $html .= "			<td style=\"text-align:left;text-indent:4pt\">ENTIDAD CONVENIO</td>\n";
			$html .= "			<td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"entidad_convenio\" id='entidad_convenio' class=\"select\" disabled=\"false\">\n";
			$html .= "					<option value=\"-1\">---Seleccionar---</option>\n";
			
			foreach($convenios as $key => $datos)
        $html .= "					<option value=\"".$datos['tipo_id_tercero']." ".$datos['tercero_id']."\" >".$datos['nombre_tercero']."</option>\n";
      
      $html .= "				</select>\n";
			$html .= "			</td>\n";
      
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td  align=\"left\" style=\"text-align:left;text-indent:4pt\" class=\"formulacion_table_list\">\n";
      $html .= "          TIPOS DE AFILIADO";
      $html .= "        </td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\">\n";
      $html .= "          <select name=\"eps_tipo_afiliado_id\" id=\"eps_tipo_afiliado_id\" class=\"select\" onchange='PintarGris(this.value);'>\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      foreach($tipos_afiliados as $key => $datos)
      {
          $html .= "        <option value=\"".$datos['eps_tipo_afiliado_id']."\" >".$datos['descripcion_eps_tipo_afiliado']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "        <td id=\"estamentox\" class=\"formulacion_table_list\" style=\"text-align:left;text-indent:4pt;\" >TIPO DE SEXO</td>\n";
      $html .= "        <td align='left' class=\"modulo_list_claro\">";
      $html .= "          <select name=\"tipo_sexo_id\" id=\"tipo_sexo_id\" class=\"select\">\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      $html .= "            <option value=\"M\">MASCULINO</option>\n";
      $html .= "            <option value=\"F\">FEMENINO</option>\n";
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td style=\"text-align:left;text-indent:4pt\" id=\"tipo_aportantex\" class=\"modulo_list_oscuro\"  >TIPO APORTANTE </td>\n";
      $html .= "        <td colspan=\"2\" align='left' class=\"modulo_list_claro\">";
      $html .= "          <select name=\"tipo_aportante_id\" id=\"tipo_aportante_id\" class=\"select\" disabled>\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      foreach($tipos_aportantes as $key => $datos)
      {   
          $html .= "        <option value=\"".$datos['tipo_aportante_id']."\" >".$datos['descripcion_tipo_aportante']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      
      $html .= "        <td style=\"text-align:left;text-indent:4pt\">COPAGO</td>\n";
			$html .= "			  <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				  <select name=\"copago\" id='copago' class=\"select\" >\n";
			$html .= "					  <option value=\"-1\">--</option>\n";
			$html .= "					  <option value=\"1\" >SI</option>\n";
			$html .= "					  <option value=\"2\" >NO</option>\n";
      $html .= "				  </select>\n";
			$html .= "			  </td>\n";
      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td  style=\"text-align:left;text-indent:4pt\" id=\"dependenciasx\" class=\"modulo_list_oscuro\" >DEPENDENCIA </td>\n";
      $html .= "        <td align='left' class=\"modulo_list_claro\" colspan=\"4\">";
      $html .= "          <select name=\"codigo_dependencia_id\" id=\"codigo_dependencia_id\" class=\"select\" disabled>\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      foreach($dependencias as $key => $datos)
      {   
          $html .= "        <option value=\"".$datos['codigo_dependencia_id']."\" >".$datos['descripcion_dependencia']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </td>\n";

      $html .= "      </tr>\n";
      $html .= "      <tr class=\"formulacion_table_list\">\n";
      $html .= "        <td style=\"text-align:left;text-indent:4pt\" class=\"formulacion_table_list\">\n";
      $html .= "          ESTADO";
      $html .= "        </td>\n";
      $html .= "        <td align=\"left\" class=\"modulo_list_claro\" colspan=\"2\" >\n";
      $html .= "          <select name=\"estado_afiliado_id\" id=\"estado_afiliado_id\" class=\"select\" onchange=\"ObtenerSubestados(this.value);\">\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      foreach($estados_afiliados as $key => $datos)
      {   
          $html .= "        <option value=\"".$datos['estado_afiliado_id']."\" >".$datos['descripcion_estado']."</option>\n";
      }
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "        <td align=\"left\" style=\"text-align:left;text-indent:4pt\" class=\"formulacion_table_list\">\n";
      $html .= "          SUBESTADO";
      $html .= "        </td>\n";
      $html .= "        <td colspan='2' align=\"left\" class=\"modulo_list_claro\">\n";
      $html .= "          <select name=\"subestado_afiliado_id\" id='subestado_afiliado_id' class=\"select\">\n";
      $html .= "            <option value=\"0\">---Seleccionar---</option>\n";
      $html .= "          </select>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      
      $html .= "			<tr class=\"formulacion_table_list\">\n";
			$html .= "				<td style=\"text-align:left;text-indent:4pt\"  >PLAN DE ATENCION</td>\n";
			$html .= "				<td colspan=\"4\" class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "					<select name=\"plan_atencion\" class=\"select\" onchange=\"LimpiarCapas();xajax_MostrarInformacionPlan(xajax.getFormValues('consulta_afiliacion'))\">\n";
			$html .= "						<option value=\"-1\">-SELECCIONAR-</option>\n";
			
			foreach($planes as $key => $dtl)
      {
				($afiliado['plan_atencion'] == $key)? $s1 = "selected": $s1 = ""; 
        $html .= "							<option value=\"".$key."\" $s1>".$dtl['plan_descripcion']."</option>\n";
			}
			$html .= "					</select>\n";
			$html .= "				</td>\n";
      $html .= "			</tr>\n";
      $html .= "			<tr class=\"formulacion_table_list\">\n";
      $html .= "			  <td style=\"text-align:left;text-indent:4pt\" >TIPO AFILIADO PLAN</td>\n";
      $html .= "			  <td colspan=\"2\" class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "          <div id=\"tipo_afiliado_div\"></div>\n";
      $html .= "        </td>\n";
      $html .= "			  <td style=\"text-align:left;text-indent:4pt\"  >RANGO</td>\n";
      $html .= "			  <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "          <div id=\"rango_afiliado_div\">\n";
      $html .= "          </div>\n";
      $html .= "        </td>\n";
      $html .= "			</tr>\n";
      
      $html .= "      <tr class=\"modulo_list_claro\">\n";
      $html .= "        <td colspan=\"5\">\n";
      $html .= "          <table width=\"50%\" align=\"center\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\" width=\"50%\">\n";
      $html .= "                <input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar Afiliado(s)\">\n";
      $html .= "              </td>";      
      $html .= "              <td align=\"center\" width=\"50%\">\n";
      $html .= "                <input type=\"button\" class=\"input-submit\" name=\"Limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.consulta_afiliacion);PintarGris();Habilitar(1);LimpiarCapas()\">";
      $html .= "              </td>";
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
      $html .= "        </td>\n";
      $html .= "      </tr>\n";
      
      $html .= "	  </table>\n";
      $html .= " </form>";
      $html .= " <br>\n";
      $html .= " <div id='tabla_afiliados'>\n";
      $html .= "  </div>\n";
      $html .= "  <br>\n";
      $html .= "  <div id='reporte_consulta' style=\"display:none\">\n";
      $html .= "    <table border=\"0\" width=\"95%\" align=\"center\" >\n";
      $html .= "      <tr>\n";
      $html .= "        <td align=\"center\">";
      $rpt = new GetReports();
      $mostrar = $rpt->GetJavaReport('app','UV_Afiliaciones','ReportePorConsulta',array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $funcion1 = $rpt->GetJavaFunction();
      $html .= $mostrar;
      //$reporte = "app_modules/UV-Afiliaciones/reports/html/ReportePorUsuario.report.php?".URLRequest($fecha_ini);
      $html .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"CREAR REPORTE CONSULTA\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\" title=\"CREAR REPORTE CONSULTA\">CREAR REPORTE CONSULTA</a>\n";
      $html .= "        </td>\n";
      
      //$rpt = new GetReports();
      $mst = $rpt->GetJavaReport('app','UV_Afiliaciones','ReporteCotizantes',array(),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $fnc = $rpt->GetJavaFunction();
      
      $html .= "        <td align=\"center\">\n";
      $html .= $mst;
      $html .= "          <div id='cotizantes_beneficiarios' style=\"display:none\">\n";
      $html .= "            <a href=\"javascript:".$fnc."\" class=\"label_error\" title=\"REPORTE COTIZANTES Y BENEFICIARIOS\">\n";
      $html .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>COTIZANTES Y BENEFICIARIOS \n";
      $html .= "            </a>\n";
      $html .= "          </div>\n";
      $html .= "        </td>\n";
      $html .= "       </tr>\n";
      $html .= "    </table>\n";
      $html .= "  </div>\n";
      
      $html .= "    <table border=\"0\" width=\"95%\" align=\"center\" >\n";
      $html .= "      <tr>\n";

      $html .= "      </tr>";
      $html .= "    </table>";
      $html .= "  </div>\n";
      
      $html .= "	<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "	  <tr>\n";
			$html .= "	    <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "        <td align=\"center\"><br>\n";
			$html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "        </td>";
			$html .= "      </form>";
			$html .= "    </tr>";
			$html .= "  </table>";
      $html .= "<script language=\"javaScript\">\n";
      $html .= "  function mOvr(src,clrOver) \n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn) \n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  \n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
		}
        /**
        * @param array $action Vector de links de la aplicaion
        * @param array $tipos_identificacion Vector de tipos de identificacion
        * @param array $tipos_afiliados Vector de tipos de afiliados
        * @param array $estados_afiliados Vector de estados de afiliados
        * @param array $dependencias Vector de dependencias de la U.V.
        * @param array $estamentos Vector de estamentos
        * @param array $tipos_aportantes Vector de tipos aportantes
        *
        * @return String
        */
        function FormaImpresionCarnets($action,$tipos_identificacion,$tipos_afiliados)
        {

           $html  = ThemeAbrirTabla('IMPRESION DE CARNETS');
           $javaC = "<script>\n";
           $javaC .= "   var contenedor1=''\n";
           $javaC .= "   var titulo1=''\n";
           $javaC .= "   var hiZ = 2;\n";
           $javaC .= "   var DatosFactor = new Array();\n";
           $javaC .= "   var EnvioFactor = new Array();\n";
           $javaC .= "   function Iniciar2(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorGrup';\n";
           $javaC .= "       titulo1 = 'tituloGrup';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n";
           $javaC .= "       xResizeTo(Capa, 550, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoGrup');\n";
           $javaC .= "       xResizeTo(Capx, 550, 320);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 530, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarGrup');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 530, 0);\n";
           $javaC .= "   }\n";
           $javaC .= "   function Iniciar3(tit)\n";
           $javaC .= "   {\n";
           $javaC .= "       contenedor1 = 'ContenedorMed';\n";
           $javaC .= "       titulo1 = 'tituloMed';\n";
           $javaC .= "       document.getElementById(titulo1).innerHTML = '<center>'+tit+'</center>';\n";
           $javaC.= "        Capa = xGetElementById(contenedor1);\n";
           $javaC .= "       xResizeTo(Capa, 290, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoMed');\n";
           $javaC .= "       xResizeTo(Capx, 290, 325);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+10);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 270, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarMed');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 270, 0);\n";
           $javaC .= "   }\n";
           $javaC .="</script>\n";
           $html.= $javaC;
           $javaC1.= "<script>\n";
           $javaC1 .= "   function myOnDragStart(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     window.status = '';\n";
           $javaC1 .= "     if (ele.id == titulo1) xZIndex(contenedor1, hiZ++);\n";
           $javaC1 .= "     else xZIndex(ele, hiZ++);\n";
           $javaC1 .= "     ele.myTotalMX = 0;\n";
           $javaC1 .= "     ele.myTotalMY = 0;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "   function myOnDrag(ele, mdx, mdy)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "     if (ele.id == titulo1) {\n";
           $javaC1 .= "       xMoveTo(contenedor1, xLeft(contenedor1) + mdx, xTop(contenedor1) + mdy);\n";
           $javaC1 .= "     }\n";
           $javaC1 .= "     else {\n";
           $javaC1 .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
           $javaC1 .= "     }  \n";
           $javaC1 .= "     ele.myTotalMX += mdx;\n";
           $javaC1 .= "     ele.myTotalMY += mdy;\n";
           $javaC1 .= "   }\n";
           $javaC1 .= "   function myOnDragEnd(ele, mx, my)\n";
           $javaC1 .= "   {\n";
           $javaC1 .= "   }\n";
           $javaC1.= "function MostrarCapa(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";
           $javaC1.= "    capita.style.display = \"\";\n";
           $javaC1.= "}\n";
           $javaC1.= "function Cerrar(Elemento)\n";
           $javaC1.= "{\n";
           $javaC1.= "    capita = xGetElementById(Elemento);\n";          
           $javaC1.= "    capita.style.display = \"none\";\n";          
           $javaC1.= "}\n";

           $javaC1.= "</script>\n";
           $html.= $javaC1;        
            /*******************************************************************************
            *Ventana para crear tercero
            **********************************************************************************/
            $html .="<div id='ContenedorGrup' class='d2Container' style=\"display:none\">";
            $html .= "    <div id='tituloGrup' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarGrup' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorGrup');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorGrup' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoGrup' class='d2Content'>\n";
            $html .= "    </div>\n";
            $html .="</div>";
            /*******************************************************************************
            *fin Ventana para crear tercero
            **********************************************************************************/
            /*******************************************************************************
            *Ventana para crear tercero
            **********************************************************************************/
            $html .="<div id='ContenedorMed' class='d2Container' style=\"display:none\">";
            $html .= "    <div id='tituloMed' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
            $html .= "    <div id='cerrarMed' class='draggable'> <a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorMed');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
            $html .= "    <div id='errorMed' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
            $html .= "    <div id='ContenidoMed' class='d2Content'>\n";
            $html .= "    </div>\n";
            $html .="</div>";
            /*******************************************************************************
            *fin Ventana para crear tercero
            **********************************************************************************/
            $html .= "<form name=\"consulta_afiliacion\" id=\"consulta_afiliacion\" action=\"#\" method=\"post\">";
            $html .= "  <table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td colspan=\"6\">BUSQUEDA AVANZADA</td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td width=\"15%\" style=\"text-align:left;text-indent:4pt\">TIPO DOCUMENTO: </td>\n";
            $html .= "          <td width=\"20%\" align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <select name=\"afiliado_tipo_id\" id='afiliado_tipo_id' class=\"select\" onchange=\"HabilitarTipoId(this.value)\">\n";
            $html .= "                  <option value=\"0\">---Seleccionar---</option>\n";
            foreach($tipos_identificacion as $key => $datos)
            $html .= "                  <option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";
            $html .= "              </select>\n";
            $html .= "          </td>\n";
            $html .= "          <td width=\"13%\" style=\"text-align:left;text-indent:4pt\" >NRO DOCUMENTO: </td>\n";
            $html .= "          <td width=\"52%\" colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" name=\"afiliado_id\" id=\"afiliado_id\" value=\"\" size=\"25\" disabled>\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:4pt\" >NOMBRES: </td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" id=\"nombre_afiliado\" name=\"nombre_afiliado\" value=\"\" size=\"25\">\n";
            $html .= "          </td>\n";            
            $html .= "          <td  style=\"text-align:left;text-indent:4pt\" >PRIMER APELLIDO: </td>\n";
            $html .= "          <td  align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" name=\"primer_apellido\" id=\"primer_apellido\" value=\"\" size=\"25\" >\n";
            $html .= "          </td>\n";
            $html .= "          <td style=\"text-align:left;text-indent:4pt\" >SEGUNDO APELLIDO: </td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" id=\"segundo_apellido\" name=\"segundo_apellido\" value=\"\" size=\"25\" >\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"modulo_list_claro\">\n";
            $html .= "        <td align=\"center\" COLSPAN='6'>\n";
            $html .= "          <input class=\"input-submit\" type=\"button\" name=\"buscar\" value=\"Buscar Afiliado(s)\" onclick=\"xajax_BuscarDatosCarnet((xajax.getFormValues('consulta_afiliacion')),1,0);\">";
            $html .= "        </td>";
            $html .= "      </tr>\n";
            $html .= "    </table>\n";
            $html .= " </form>";
            $html .= " <br>\n";
            $html .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= " <div id='tabla_afiliados'>\n";
            $html .= "  </div>\n";
            $html .= "  <br>\n";
            $html .= "  <div id='reporte_consulta' style=\"display:none\">\n";
            $html .= "  </div>\n";
            $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
            $html .= "    <tr>\n";
            $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
            $html .= "        <td align=\"center\"><br>\n";
            $html .= "          <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
            $html .= "        </td>";
            $html .= "      </form>";
            $html .= "    </tr>";
            $html .= "  </table>";
            $html .="<script language=\"javaScript\">
                        function mOvr(src,clrOver) 
                        {
                        src.style.background = clrOver;
                        }
        
                        function mOut(src,clrIn) 
                        {
                        src.style.background = clrIn;
                        }
                    </script>";
             $RUTA = "cache/carnet_univalle.pdf";
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
            $html .= ThemeCerrarTabla();
            return $html;
        }
	}
?>