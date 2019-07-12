<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: VacunacionHTML.class.php,v 1.1 2009/12/03 14:59:04 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  /**
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma
  */
  class VacunacionHTML 
  {
    /**
    * Constructot de la clase
    */
    function VacunacionHTML(){}
    
    /*
    *Funcion que muestra el historial de vacunacion, y las posibles vacunas a aplicar al paciente.
    *recive 5 parametros:
    *$datos:los datos de la vacuna
    *$datosPaciente:los datos del paciente
    *$action:action[aplicar]
    *$evolucion:trae la evolucion
    *$historial:trae el historial de las vacunas del paciente
    */
    function FormaMostrarVacunas($datos, $datosPaciente, $action, $evolucion, $historial)
    {  
      $ctl = AutoCarga::factory("ClaseUtil");
      $html = $ctl->AcceptDate("/"); 
      $mostrarOcultar='0';
      $html.=ThemeAbrirTabla("VACUNACION");
      $html.="<table width=\"90%\" align=\"center\">\n";
			$html.="    <tr>\n";
			$html.="		    <td>\n";     
      $html.="		        <div class=\"tab-pane\" id=\"Vacunacion\">\n";
      $html.="                  <script>	tabPane = new WebFXTabPane( document.getElementById( \"Vacunacion\" ), false ); </script>\n";
      $html.="			            <div class=\"tab-page\" id=\"Historial\">\n";
      $html.="				                <h2 class=\"tab\">HISTORIAL DE VACUNACION</h2>\n";
      $html.="					              <script>	tabPane.addTabPage( document.getElementById(\"Historial\")); </script>\n";   

      if(empty($datos))
      {  
        $html.="<center>\n";
        $html.="  <label class=\"label_error\">NO HAY HISTORIAL DE VACUNAS PARA ESTA PERSONA</label>\n";
        $html.="</center>\n";
      }
      else
      {
        $html.="                       <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">";
        $html.="                            <tr class=\"formulacion_table_list\">";
        $html.="                                <td colspan=\"8\">HISTORIAL</td>";
        $html.="                            </tr>";
        $html.="                            <tr class=\"formulacion_table_list\">";
        $html.="                                <td width=\"20%\">Enfermedad</td>";
        $html.="                                <td width=\"35%\">Vacuna</td>";
        $html.="                                <td width=\"35%\">Lugar de Aplicacion</td>";
        $html.="                                <td width=\"35%\">Observacion</td>";
        $html.="                                <td width=\"5%\">Dosis Aplicadas</td>";
        $html.="                                <td width=\"5%\">Fecha</td>";
        $html.="                                <td width=\"5%\">Usuario</td>";
        $html.="                            </tr>";
        
        foreach($historial as $key => $detalle)
        {
            ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
            ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
        
            $html.="                        <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
            $html.="                            <td>".$detalle['enfermedad']."</td>";
            $html.="                            <td>".$detalle['descripcion']."</td>";
            $html.="                            <td>".$detalle['lugar_aplicacion']."</td>";
            $html.="                            <td>".$detalle['observaciones']."</td>";
            $html.="                            <td>".$detalle['numero_dosis']."</td>";
            $html.="                            <td>".$detalle['fecha_aplicacion']."</td>";
            $html.="                            <td>".$detalle['nombre']."</td>";
            $html.="                        </tr>";
        }  
        $html.="                       </table>";
      }
      $html.="                 </div>";  
      
      $html.="			           <div class=\"tab-page\" id=\"tabla_dosis\">\n";
      $html.="				            <h2 class=\"tab\">POSIBLES VACUNAS PARA APLICAR</h2>\n";  
      $html.="					          <script>	tabPane.addTabPage( document.getElementById(\"AplicacionDosis\")); </script>\n";
      
      if(empty($datos))
      {  
        $html.="<center>\n";
        $html.="  <label class=\"label_error\">LA PERSONA NO TIENE VACUNAS POR APLICAR</label>\n";
        $html.="</center>\n";
      }
      else
      {
        $html.="                  <form name=\"registro_dosis\" id=\"registro_dosis\" action=\"javascript:validarDosis(document.registro_dosis)\" method=\"post\">\n";     
        $html.="                      <table align=\"center\" width=\"100%\" class=\"modulo_table_list\">";
        $html.="                            <tr class=\"formulacion_table_list\">";
        $html.="                                <td colspan=\"8\">POSIBLES VACUNAS </td>";
        $html.="                            </tr>";
        $html.="                            <tr class=\"formulacion_table_list\">";
        $html.="                                <td width=\"20%\">Enfermedad</td>";
        $html.="                                <td width=\"35%\">Vacuna</td>";
        $html.="                                <td width=\"5%\">Dosis</td>";
        $html.="                                <td width=\"7%\">Edad Minima</td>";
        $html.="                                <td width=\"7%\">Edad Maxima</td>";
        $html.="                                <td width=\"15%\">Via de Aplicacion</td>";
        $html.="                                <td width=\"5%\">Refuerzos</td>";
        $html.="                                <td width=\"%\">Ver</td>";
        $html.="                            </tr>";
        
        foreach($datos as $key => $detalle)
        {
          ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
          ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
          
          $html.="                         <tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html.="                              <td>".$detalle['enfermedad']."</td>";
          $html.="                              <td>".$detalle['descripcion']."</td>";
          $html.="                              <td>".$detalle['dosis']."</td>";
          $html.="                              <td>".$detalle['edad_minima']." ".$detalle['nom_unidad_min']."</td>";
          $html.="                              <td>".$detalle['edad_maxima']." ".$detalle['nom_unidad_max']."</td>";
          $html.="                              <td>".$detalle['nombre']."</td>";
          $html.="                              <td>".$detalle['refuerzos']."</td>";
          $html.="                              <td>\n";
          $html.="                                  <div id=\"link_".$detalle['cargo']."\">\n";
          $html.="                                      <a  href=\"#\" onclick=\"xajax_verDosisVacunas('".$detalle['cargo']."', '".$datosPaciente['edad_paciente']['edad_rips']."',1)\" class=\"label_error\" >\n";
          $html.="                                          <img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\">VER\n";
          $html.="                                      </a>\n";
          $html.="                                  </div>\n";
          $html.="                              </td>\n";
          $html.="                         </tr>";
          $html.="                         <tr>";
          $html.="                              <td colspan=\"8\"><div id=\"tabla_dosis_".$detalle['cargo']."\" style=\"display:none\"></div></td>";
          $html.="                         </tr>";
        }  
        $html.="                      </table>";
        $html.="                  </form>";
      }
      $html.="                </div>";
      $html.="            </div>";
      $html.="        </td>";  
      $html.="        <tr class=\"modulo_list_claro\">";
      $html.="            <td colspan=\"8\">\n";
      $html.="              <div id=\"mensaje\"></div>\n";
      $html.="            </td>\n";
      $html.="        </tr>";
      $html.="    </tr>";
      $html.="</table>";
        
      $html.="<script>";
      $html.="    function validarDosis(objeto)\n";
      $html.="    {\n";
      $html.="        xajax_GuardarAplicacion(xajax.getFormValues('registro_aplicacion'));\n";
      $html.="        objeto.action = \"".$action["aplicar"]."\";\n";
      $html.="        objeto.submit();";
      $html.="    }";
      $html.="</script>";    
         
      $html.= $this->CrearVentana(610,280);
      $html.= ReturnOpenCalendarioScript('registro_aplicacion','fecha_aplicacion','/',1);
      $html.= ThemeCerrarTabla();
      return $html;
    }
             
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño en x que tendra la ventana
    * @param int $tmny Tamaño en y que tendra la ventana
    * @param int $contenido Contenido a mostrar en la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 370, $tmny = "'auto'",$contenido)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";

			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
      $html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";
      
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	  <form name=\"registro_aplicacion\" id=\"registro_aplicacion\" action=\"javascript:validarDosis()\" method=\"post\">\n";
			$html .= "	    <div id=\"aplicar\">".$contenido."</div>";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
  }
?>