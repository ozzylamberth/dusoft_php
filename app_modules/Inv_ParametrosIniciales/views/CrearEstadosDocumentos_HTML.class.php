<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: CrearEstadosDocumentos_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: CrearEstadosDocumentos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class CrearEstadosDocumentos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function CrearEstadosDocumentos_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$request)
    {
    $accion=$action['volver'];
			  
    
    $html .='
  
  
  
  <script languaje="javascript">
  
  
  
 function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  </script>
  ';
  
     
  
  
  //Confirmar 2 es para validar el formulario de los medicamento
  
  $html .="<script>";
    $html .='function Confirmar(Formulario)
    {
    var band=0;
    var cadena = [];
    var temp;
    
          
          if(Formulario.abreviatura=="")
          {
          cadena.push("No Haz Ingresado una Abreviatura\n");
          band=1;
          }
          
           if(Formulario.descripcion=="")
          {
          cadena.push("No Haz Ingresado Una Descripcion\n");
          band=1;
          }
          
            
        var entrar = confirm("Continuar?")

        if (entrar) 
              {
                  
                  
                 if(band==1)
                  {
                  alert(cadena);
                  alert("¡Por favor, Diligenciar todos los Datos!");
                   } 
                      else
                      {
                               if(Formulario.token=="1")
                               {
                               //alert("Ingreso");
                               xajax_InsertarEstadoDocumento(Formulario);
                               }
                                  else
                                      {
                                      //alert("Modificacion");
                                      xajax_ModEstadoDocumento(Formulario);
                                      }
                      }
                  
              
              }
                else
                {
                  return(false);
                }
                   
    }';
    $html .="</script>";
  
 
    
    
    $html .="<script>";
    $html .= "  function Paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_EstadosDocumentosT(offset);\n";
    $html .= "  }\n";   
    $html .="</script>";
    
  
    $html .= ThemeAbrirTabla('CREACION DE ESTADOS DE DOCUMENTOS');
    
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= "<center>";
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"90%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"creacion_asociacion_estadosdocumentos\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"creacion_asociacion_estadosdocumentos\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"crear_estadosdocumentos\">\n";
			$html .= "									<h2 class=\"tab\">CREAR ESTADOS DOCUMENTOS DE BODEGA</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_estadosdocumentos\")); </script>\n";
      
      $html .="         <center>";
      $html .="                 <a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoEstadoDocumento()\">";
      $html .="                 [::INGRESAR NUEVO ESTADO DE DOCUMENTO DE BODEGA::]";
      $html .="                 </a>";
      $html .="         </center><br>";
      
    /*  $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"30%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"descripcion\" onkeyup=\"this.value=this.value.toUpperCase(),Busqueda_();\">";
    
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Abreviatura :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"molecula_id\" size=\"5\" maxlength=\"5\" onkeyup=\"this.value=this.value.toUpperCase(),Busqueda_();\" >";
    $html .= "</td>";
    $html .= "</tr>";
    $html .="</table></form>";
    $html .= "      <br>\n";*/
      
      $html .= "<div id=\"ListadoEstadosCreados\">\n"; 
      
        
        $html .= "								 </div>\n";
        $html .= "								</div>\n";
        
        //Otro Tab.
        $html .= "								<div class=\"tab-page\" id=\"asociar_estadosdocumentos\">\n";
        $html .= "									<h2 class=\"tab\">FLUJO DE ESTADOS</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asociar_estadosdocumentos\")); </script>\n";

        
    
        $html .="<div id=\"cambio_estadosdocumentos\">";
        $html .="</div>";
                  
        $html .= "								</div>\n";
        
   
     
    $html .="<script>";
    $html .= "xajax_EstadosDocumentosT();";
    $html .="</script>";
    
    
    $html .="<script>";
    $html .= "xajax_EstadosDocumentosCambiosT();";
    $html .="</script>";
    
 
			$html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n";
      
      
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
    $html .= $this->CrearVentana(600,"CREACION DE ESTADOS DE DOCUMENTOS DE BODEGA");
    $html .= ThemeCerrarTabla();
    
    
    
    
    
    return($html);
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