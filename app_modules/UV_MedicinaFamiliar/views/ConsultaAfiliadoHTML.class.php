<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ConsultaAfiliadoHTML.class.php,v 1.7 2007/11/08 22:53:48 jgomez Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author JAIME GOMEZ
  */

  /**
  * Clase Vista: ConsultaAfiliadoHTML 
  * Clase contiene metodos para la consulta de afiliados del sistema
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.7 $
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
        *
        * @return String
		*/
		function FormaConsultaAfiliado($action,$tipos_identificacion,$tipos_afiliados,$estados_afiliados,$dependencias,$estamentos,$tipos_aportantes)
		{

           $html  = ThemeAbrirTabla('CONSULTA DE GRUPOS FAMILIARES');
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
           $javaC .= "       xResizeTo(Capa, 350, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoMed');\n";
           $javaC .= "       xResizeTo(Capx, 350, 300);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+10);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 330, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarMed');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 330, 0);\n";
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
			
			$html .= "	<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td colspan=\"6\">BUSQUEDA AVANZADA</td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td width=\"15%\" style=\"text-align:left;text-indent:8pt\">TIPO DOCUMENTO: </td>\n";
			$html .= "			<td width=\"20%\" align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "				<select name=\"afiliado_tipo_id\" id='afiliado_tipo_id' class=\"select\" onchange=\"HabilitarTipoId(this.value)\">\n";
			$html .= "					<option value=\"0\">---Seleccionar---</option>\n";
			foreach($tipos_identificacion as $key => $datos)
            $html .= "					<option value=\"".$datos['tipo_id_paciente']."\" >".$datos['descripcion']."</option>\n";
            $html .= "				</select>\n";
			$html .= "			</td>\n";
            $html .= "          <td width=\"13%\" style=\"text-align:left;text-indent:8pt\" >NRO DOCUMENTO: </td>\n";
            $html .= "          <td width=\"52%\" colspan='3' align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" name=\"afiliado_id\" id=\"afiliado_id\" value=\"\" size=\"25\" disabled>\n";
            $html .= "          </td>\n";
			$html .= "		</tr>\n";
            $html .= "      <tr class=\"modulo_table_list_title\">\n";
            $html .= "          <td  style=\"text-align:left;text-indent:8pt\" >PRIMER APELLIDO: </td>\n";
            $html .= "          <td  align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" name=\"primer_apellido\" id=\"primer_apellido\" value=\"\" size=\"25\" >\n";
            $html .= "          </td>\n";
            $html .= "          <td class=\"modulo_table_list_title\" style=\"text-align:left;text-indent:8pt\" >NOMBRES: </td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" id=\"nombre_afiliado\" name=\"nombre_afiliado\" value=\"\" size=\"25\">\n";
            $html .= "          </td>\n";
            $html .= "          <td style=\"text-align:left;text-indent:8pt\" >SEGUNDO APELLIDO: </td>\n";
            $html .= "          <td align=\"left\" class=\"modulo_list_claro\">\n";
            $html .= "              <input type=\"text\" class=\"input-text\" id=\"segundo_apellido\" name=\"segundo_apellido\" value=\"\" size=\"25\" >\n";
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr class=\"modulo_list_claro\">\n";
            $html .= "        <td align=\"center\" COLSPAN='6'>\n";
            $html .= "          <input class=\"input-submit\" type=\"button\" name=\"buscar\" value=\"Buscar Afiliado(s)\" onclick=\"xajax_BuscarDatos((xajax.getFormValues('consulta_afiliacion')),1,0);\">";
            $html .= "        </td>";
            $html .= "      </tr>\n";
            $html .= "	  </table>\n";
            $html .= " </form>";
            $html .= " <br>\n";
            $html .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= " <div id='tabla_afiliados'>\n";
            $html .= "  </div>\n";
            $html .= "  <br>\n";
            $html .= "  <div id='reporte_consulta' style=\"display:none\">\n";
//             $html .= "    <table border=\"0\" width=\"95%\" align=\"center\" >\n";
//             $html .= "      <tr>\n";
//             $html .= "        <td align=\"center\">";
//             $reporte = new GetReports();
//             $mostrar = $reporte->GetJavaReport('app','UV_Afiliaciones','ReportePorConsulta',array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
//             $funcion1 = $reporte->GetJavaFunction();
//             $html .= $mostrar;
//             //$reporte = "app_modules/UV-Afiliaciones/reports/html/ReportePorUsuario.report.php?".URLRequest($fecha_ini);
//             $html .= "              <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"CREAR REPORTE CONSULTA\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\" title=\"CREAR REPORTE CONSULTA\">CREAR REPORTE CONSULTA</a>\n";
//             $html .= "         </td>";
//             $html .= "       </tr>";
//             $html .= "    </table>";
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
            $html .= ThemeCerrarTabla();
            return $html;
		}



        
        
        /**
        * funcion que lista los medicos y la cantidad de grupos familiares asignados
        * @param array $action Vector de links de la aplicaion
        * @return String
        */
        function FormaListarMedicos($action,$vector_medicos)
        {
           $path = SessionGetVar("rutaImagenes");
           $html  = ThemeAbrirTabla('MEDICOS FAMILIARES');
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
           $javaC .= "       xResizeTo(Capa, 350, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoMed');\n";
           $javaC .= "       xResizeTo(Capx, 350, 300);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/2, xScrollTop()+10);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 330, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarMed');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 330, 0);\n";
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
            $html .= "   <center><div class=\"label_error\" id=\"error\"></div></center>\n";
            $html .= " <div id='tabla_afiliados'>\n";
            $html .= "  </div>\n";
            if(!empty($vector_medicos))
            {
                $html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
                $html .= "                    <tr class=\"modulo_table_list_title\">\n";
                $html .= "                       <td width=\"20%\" align=\"center\">\n";
                $html .= "                       <a title='IDENTIFICACION DEL MEDICO'>";
                $html .= "                        IDENTIFICACION";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"15%\" align=\"center\">\n";
                $html .= "                        USUARIO";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"50%\" align=\"center\">\n";
                $html .= "                       <a title='IDENTIFICACION DEL AFILIADO'>";
                $html .= "                         NOMBRE";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                       <td width=\"15%\" align=\"center\">\n";
                $html .= "                       <a title='CANTIDAD DE GRUPOS FAMILIARES'>";
                $html .= "                          GRUPOS FAMILIARES";
                $html .= "                       </a>";
                $html .= "                       </td>\n";
                $html .= "                    </tr>\n";
                for($i=0;$i<count($vector_medicos);$i++)
                {   
                    $td="medico".$i;
                    $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                    $html .= "                       <td class=\"normal_10AN\" align=\"left\">\n";
                    $html .= "                       ".$vector_medicos[$i]['tipo_id_tercero']." - ".$vector_medicos[$i]['tercero_id'];
                    $html .= "                       </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $html .= "                       ".$vector_medicos[$i]['usuario_profesional'];
                    $html .= "                      </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $html .= "                       ".$vector_medicos[$i]['nombre'];
                    $html .= "                      </td>\n";
                    $html .= "                      <td align=\"left\">\n";
                    $beneficiario = "javascript:MostrarCapa('ContenedorGrup');ListarGrupos('".$vector_medicos[$i]['tipo_id_tercero']."','".$vector_medicos[$i]['tercero_id']."','".$vector_medicos[$i]['nombre']."');Iniciar2('GRUPOS FAMILIARES DEL MEDICO ".$vector_medicos[$i]['nombre']."');\"";
                    $html .="                         <a title='CONSULTAR BENEFICIARIO (GRUPO FAMILIAR)' href=\"".$beneficiario."\">";
                    $html .="                          <sub><img src=\"".$path."/images/mvto_errado.png\" border=\"0\" width=\"21\" height=\"21\"></sub>\n";//usuarios.png
                    $html .="                         </a>\n";
                    $html .= "                       ".$vector_medicos[$i]['grupos_de_familia'];
                    
                    $html .= "                      </td>\n";
                    $html .= "                    </tr>";
                }   
                $html .= "  </table>";
            }
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
            $html .= ThemeCerrarTabla();
            return $html;
        }
	}
?>