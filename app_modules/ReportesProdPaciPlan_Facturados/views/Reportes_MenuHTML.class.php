<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: Reportes_MenuHTML
  * Clase Contiene Links para los diferentes metodos Para el preporte
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");
	IncludeClass("CalendarioHtml");
	
	
	class Reportes_MenuHTML
	{
		/**
		* Constructor de la clase
		*/
		function Reportes_MenuHTML(){}
	   
    
    function Menu($action)
		{
  		$html  = ThemeAbrirTabla('REPORTES');
  		$html .= "<table border=\"0\" width=\"60%\" align=\"center\">";
  		$html .= "  <tr>\n";
      $html .= "    <td>";
  		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
  		$html .= "      <tr class=\"modulo_table_list_title\">";
  		$html .= "      <td align=\"center\">";
  		$html .= "      MENÚ";
  		$html .= "      </td>";
  		$html .= "      </tr>";
  		$html .= "      <tr class=\"modulo_list_claro\">";
  		$html .= "      <td class=\"label\" align=\"center\">";
  		$html .= "      <a href=\"". ModuloGetURL('app','ReportesProdPaciPlan_Facturados','controller','CrearReporte')."\">REPORTES DE PRODUCTOS PACIENTES Y PLANES FACTURADOS</a>";
  		$html .= "      </td>";
  		$html .= "      </tr>";
      $html .= "      </table>";
  		$html .= "  </td></tr>";
  		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
  		$html .= "  <tr>";
  		$html .= "  <td align=\"center\"><br>";
  		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
  		$html .= "  </td>";
  		$html .= "  </form>";
  		$html .= "  </tr>";
  		$html .= "  </table>";
  		$html .= ThemeCerrarTabla();
  		
  		return $html;
		}
   
   
    function CrearReportes($action,$request,$reportes)
		{
  		/*
  		* Funciones JavaScript, para validaciones y llamados a otras funciones que pueden
  		* ser xajax
  		*/
      $html .= "<script>";
      $html .= "  function Validar(formulario)";
      $html .= "  {";
      $html .= "	  if(formulario.fecha_inicial.value == \"\")";
      $html .= "      document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE ESTAR VACÍA';\n";
      $html .= "		else if(formulario.fecha_final.value == \"\")";
      $html .= "      document.getElementById('error').innerHTML = 'LA FECHA FINAL NO PUEDE ESTAR VACÍA';\n";
      $html .= "      else\n";
      $html .= "      {\n";
      $html .= "	      f = formulario.fecha_inicial.value.split('-')\n";
      $html .= "	      f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
      $html .= "	      f = formulario.fecha_final.value.split('-')\n";
      $html .= "	      f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
      $html .= "	      if(f1 > f2 )\n";
      $html .= "	      {\n";
      $html .= "          document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE SER MAYOR A LA FECHA FINAL ';\n";
      $html .= "          return;\n";
      $html .= "        } \n";
      $html .= "        else";
      $html .= "	      {";
      $html .= "          formulario.action = \"".$action['buscar']."\";\n";
      $html .= "          formulario.submit();\n";
      $html .= "        }";
      $html .= "      }\n";//Cierra else de primera validacion de campos
      $html .= "  }\n";
      $html .= "</script>";
  		/*
  		* Interfaz de la Opcion Genera Reporte
  		*/
  		$html .= ThemeAbrirTabla('REPORTES');
  		$html .= "<table border=\"0\" width=\"80%\" align=\"center\">";
  		$html .= "  <tr>\n";
      $html .= "    <td align=\"center\">";
  		$html .= "	    <div class=\"label_error\" id=\"error\"></div>";
  		$html .= "	  </td>\n";
      $html .= "  </tr>";
  		$html .= "  <tr>\n";
      $html .= "    <td>\n";
  		$html .= "      <form name=\"generador_reportes\" method=\"post\" action=\"javascript:Validar(document.generador_reportes)\" id=\"generador_reportes\">\n";
  		$html .= "        <table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
  		$html .= "          <tr class=\"modulo_table_list_title\">";
  		$html .= "            <td align=\"center\" colspan=\"4\">";
  		$html .= "              GENERADOR DE REPORTES (Fechas De Ingreso)";
  		$html .= "            </td>\n";
  		$html .= "          </tr>\n";
  		$html .= "          <tr class=\"modulo_list_claro\">";
  		$html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
  		$html .= "              Fecha Inicial";
  		$html .= "            </td>";
  		$html .= "            <td class=\"label\" align=\"center\">";
  		$html .= "              <input size=\"12\" type=\"text\" class=\"input-text\" id=\"fecha_inicial\" name=\"fecha_inicial\" readonly=\"true\" value=\"".$request['fecha_inicial']."\">";
  		$html .= "		          ".ReturnOpenCalendario('generador_reportes','fecha_inicial','-');
  		$html .= "            </td>";
  		$html .= "            <td width=\"18%\" class=\"formulacion_table_list\" align=\"center\">";
  		$html .= "              Fecha Final";
  		$html .= "            </td>";
  		$html .= "            <td class=\"label\" align=\"center\">";
  		$html .= "              <input size=\"12\" type=\"text\" class=\"input-text\" id=\"fecha_final\" name=\"fecha_final\" readonly=\"true\" value=\"".$request['fecha_final']."\">";
  		$html .= "		          ".ReturnOpenCalendario('generador_reportes','fecha_final','-');
  		$html .= "            </td>";
  		$html .= "          </tr>";
  		$html .= "          <tr class=\"modulo_table_list_title\">";
  		$html .= "            <td align=\"center\" colspan=\"2\">";
  		$html .= "              <input type=\"submit\" class=\"input-submit\" value=\"Generar Reporte\">";
  		$html .= "            </td>";
  		$html .= "            <td align=\"center\" colspan=\"2\">";
  		$html .= "              <input type=\"reset\" class=\"input-submit\" value=\"Limpiar\"\">";
  		$html .= "            </td>";
  		$html .= "          </tr>";
  		$html .= "        </table>";
  		$html .= "		  </form>";
  		$html .= "    </td>\n";
      $html .= "  </tr>";
      $html .= "</table>";
      $html .= "<br>\n";
      
      if(!empty($reportes))
      {
        $csv = Autocarga::factory("ReportesCsv");
        
        $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" class=\"formulacion_table_list\">\n";
        $html .= "	  <tr>\n";
        $html .= "		  <td align=\"center\" class=\"formulacion_table_list\" >REPORTES</td>\n";
        $html .= "		</tr>\n";
        foreach($reportes as $key => $dtl)
        {
          $dtl['fecha_inicial'] = $request['fecha_inicial'];
          $dtl['fecha_final'] = $request['fecha_final'];
          
          $html .= $csv->GetJavacriptReporte('app','ReportesProdPaciPlan_Facturados','SabanaVariables',$dtl,'tabs',array("interface"=>1,"cabecera"=>1,"nombre"=>$dtl['nombre_archivo'],"extension"=>"csv"));
          $fncn1  = $csv->GetJavaFunction();      
                
          $html .= "        <tr>\n";
          $html .= "          <td class=\"modulo_list_claro\" align=\"center\">\n";
          $html .= "	          <a href=\"javascript:".$fncn1."\" class=\"label_error\">\n";
          $html .= "              <b>".strtoupper($dtl['titulo_reporte'])."</b>\n";
          $html .= "            </a>\n";
          $html .= "          </td>\n";
          $html .= "        </tr>\n";      
        }
        $html .= "	</table>\n";
      }
      
  		$html .= "<form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">";
  		$html .= "  <table width=\"50%\" align=\"center\">\n";
  		$html .= "    <tr>";
  		$html .= "      <td align=\"center\"><br>";
  		$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
  		$html .= "      </td>";
  		$html .= "    </tr>";
  		$html .= "  </table>";
  		$html .= "</form>";
  		
  		$html .= ThemeCerrarTabla();  		
  		return $html;
		}
 
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
	}
?>