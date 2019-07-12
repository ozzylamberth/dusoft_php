<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: ESM_Planos_HTML.class.php
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: Parametrizar_Medico_ESM_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ESM_Planos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function ESM_Planos_HTML(){}
		
    
  
  // CREAR LA CAPITA
	function CrearVentana($tmn,$Titulo)
    {
      $html .= "<script>\n";
      $html .= "  var contenedor = 'Contenedor';\n";
      $html .= "  var titulo = 'titulo';\n";
      $html .= "  var hiZ = 4;\n";
      $html .= "  function OcultarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"none\";\n";
      $html .= "    }\n";
      $html .= "    catch(error){}\n";
      $html .= "  }\n";
      //Mostrar Span
	  $html .= "  function MostrarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar();\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(error)}\n";
      $html .= "  }\n";

      $html .= "  function MostrarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xShow(Seccion);\n";
      $html .= "  }\n";
      $html .= "  function OcultarTitle(Seccion)\n";
      $html .= "  {\n";
      $html .= "    xHide(Seccion);\n";
      $html .= "  }\n";

      $html .= "  function Iniciar()\n";
      $html .= "  {\n";
      $html .= "    contenedor = 'Contenedor';\n";
      $html .= "    titulo = 'titulo';\n";
      $html .= "    ele = xGetElementById('Contenido');\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    ele = xGetElementById(contenedor);\n";
      $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
      $html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
      $html .= "    ele = xGetElementById(titulo);\n";
      $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
      $html .= "    xMoveTo(ele, 0, 0);\n";
      $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
      $html .= "    ele = xGetElementById('cerrar');\n";
      $html .= "    xResizeTo(ele,20, 20);\n";
      $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
      $html .= "  }\n";

      $html .= "  function myOnDragStart(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "    window.status = '';\n";
      $html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
      $html .= "    else xZIndex(ele, hiZ++);\n";
      $html .= "    ele.myTotalMX = 0;\n";
      $html .= "    ele.myTotalMY = 0;\n";
      $html .= "  }\n";
      $html .= "  function myOnDrag(ele, mdx, mdy)\n";
      $html .= "  {\n";
      $html .= "    if (ele.id == titulo) {\n";
      $html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
      $html .= "    }\n";
      $html .= "    else {\n";
      $html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
      $html .= "    }  \n";
      $html .= "    ele.myTotalMX += mdx;\n";
      $html .= "    ele.myTotalMY += mdy;\n";
      $html .= "  }\n";
      $html .= "  function myOnDragEnd(ele, mx, my)\n";
      $html .= "  {\n";
      $html .= "  }\n";
      
      
      $html.= "function Cerrar(Elemento)\n";
           $html.= "{\n";
           $html.= "    capita = xGetElementById(Elemento);\n";
           $html.= "    capita.style.display = \"none\";\n";
           $html.= "}\n";
      
      
      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";


    
      return $html;
    }    
  /* DESCARGA DE ARCHIVOS */
    function Vista_Formulario_Descargas($action,$buscador,$DATOS)
    {
  
	$ctl = AutoCarga::factory("ClaseUtil");

	$html .= $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();

	
     $csv = Autocarga::factory("ReportesCsv");
     
	 $html.= $csv->GetJavacriptReporte ('app', 'ESM_Planos', 'ESM_Planos', $_REQUEST,$_REQUEST['buscador']['separador']);
	 $fncn  = $csv->GetJavaFunction();

	 
	 //print_r($_REQUEST);
	 
		$html .= ThemeAbrirTabla('DESCARGAR  PLANOS');
		$html .= "<form name=\"productos\"  id=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_inicio']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
      $html .= "            </tr>\n";
			
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$_REQUEST['buscador']['fecha_final']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
      $html .= "            </tr>\n";
			$html .= "			      <tr>\n";
      $html .= "              <td class=\"normal_10AN\">SEPARADOR</td>\n";
      $html .= "               <td class=\"normal_10AN\">";
      $html .= "                <input type=\"radio\" class=\"input-radio\" name=\"buscador[separador]\" id=\"separador\" checked value=\"comas\">COMAS";
      $html .= "                <input type=\"radio\" class=\"input-radio\" name=\"buscador[separador]\" id=\"separador\" value=\"tabs\">TABS";
      $html .= "                <input type=\"radio\" class=\"input-radio\" name=\"buscador[separador]\" id=\"separador\" value=\"@\">@-ARROBA";
      $html .= "                <input type=\"radio\" class=\"input-radio\" name=\"buscador[separador]\" id=\"separador\" value=\";\">;-PUNTO Y COMA";
      $html .= "               </td>";
      $html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FORMATO FECHA</td>\n";
      $html .= "               <td class=\"normal_10AN\">";
      $html .= "                <input type=\"radio\" class=\"input-radio\" name=\"buscador[formato_fecha]\" id=\"formato_fecha\" checked value=\"DD-MM-YYYY\">DD/MM/YYYY";
      $html .= "                <input type=\"radio\" class=\"input-radio\" name=\"buscador[formato_fecha]\" id=\"formato_fecha\" value=\"YYYY-MM-DD\">YYYY/MM/DD";
      $html .= "               </td>";
      $html .= "			      </tr>\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
//print_r($_REQUEST);
      if($_REQUEST['buscador']['fecha_inicio']!='' && $_REQUEST['buscador']['fecha_final']!='')
      {
      $html .= "  <center>";
      $html .= "  <table border=\"0\" width=\"60%\" align=\"center\"  class=\"modulo_table_list\" >\n";
      $html .= "    <tr  >";
      $html .= "          <td align=\"center\"><B>DESCARGAR ARCHIVO PLANO (Separador Seleccionado \"<U>".$_REQUEST['buscador']['separador']."</U>\")  : </B>\n";
      $html .= "	           <a href=\"javascript:".$fncn."\" class=\"label_error\">\n";
      $html .= "        <img title=\"DESCARGAR ARCHIVO PLANO\" src=\"".GetThemePath()."/images/uf.png\" border=\"0\"> ";
      $html .= "            </a>\n";
      $html .= "          </td>\n";
      $html .= "		</tr>";
      $html .= "	</table>";
      }

		$html .= "  <table border=\"0\" width=\"30%\" align=\"center\">";
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </form>";
		$html .= "  </td>";

		$html .= "  </tr>";
		$html .= "  </table>";
     
    $html .= ThemeCerrarTabla();

    return $html;
    }
    
    
  
  }
?>