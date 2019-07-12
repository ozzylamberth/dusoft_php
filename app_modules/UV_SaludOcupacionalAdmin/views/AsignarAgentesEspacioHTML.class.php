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

	class AsignarAgentesEspacioHTML
	{
		/**
		* Constructor de la clase
		*/
		function AsignarAgentesEspacioHTML(){}
		/**
	    * @param array $action Vector de links de la aplicaion
		* @param array $ocupaciones 
		* @return String menu de opciones
		*/
		function FormaAgentesPorEspacio($action)
		{
           $path=SessionGetVar("rutaImagenes"); 
           $html  = ThemeAbrirTabla('ASIGNACION DE AGENTES DE RIESGOS A ESPACIOS');
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
           $javaC .= "       xResizeTo(Capa, 500, 'auto');\n";
           $javaC.= "        Capx = xGetElementById('ContenidoGrup');\n";
           $javaC .= "       xResizeTo(Capx, 500, 150);\n";
           $javaC .= "       xMoveTo(Capa, xClientWidth()/5, xScrollTop()+30);\n";
           $javaC .= "       ele = xGetElementById(titulo1);\n";
           $javaC .= "       xResizeTo(ele, 480, 20);\n";
           $javaC .= "       xMoveTo(ele, 0, 0);\n";
           $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
           $javaC .= "       ele = xGetElementById('cerrarGrup');\n";
           $javaC .= "       xResizeTo(ele, 20, 20);\n";
           $javaC .= "       xMoveTo(ele, 480, 0);\n";
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

            $html .= "               <div align='center' id=\"agentes_x_espacio\">";
            $html .= "               </div>";
            $html .= "               <div align='center' id=\"agentes_x_espacio1\">";
            $html .= "               </div>";
            $html .= "  <br>\n";
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

                        TablaAgentesXEspacio();
                    </script>";
            $html .= ThemeCerrarTabla();
            return $html;
		}



        
        

	}
?>