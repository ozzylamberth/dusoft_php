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
  * @version $Revision: 1.1 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class AsignarEstadosUsuariosBodega_HTML
	{
		/**
		* Constructor de la clase
		*/
		function AsignarEstadosUsuariosBodega_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$request,$Empresas)
    {
    $accion=$action['volver'];
			  
    
       
  
    $html .= ThemeAbrirTabla('ASIGNAR ESTADOS A USUARIOS DE DOCUMENTOS DE BODEGA');
    
    $html .= $Empresas;
    $html .= ThemeCerrarTabla();
    
    return($html);
    }
    
    
    
    function DesplegarListadoUsuarios($action,$Empresas)
    {
    $accion=$action['volver'];
		$EmpresaId = $_REQUEST['arreglo']['empresa_id'];	   
  
    $html .= ThemeAbrirTabla('ASIGNAR ESTADOS A USUARIOS DE DOCUMENTOS DE BODEGA');
    
    $html .=    "<script>";
    $html .= "  function Paginador(empresa,offset)\n";
    $html .= "  {";
    $html .= "    xajax_UsuariosDocumentosBodegasT(empresa,offset);\n";
    $html .= "  }\n";
    
    $html .= "  function paginador(usuario,empresa,offset)\n";
    $html .= "  {";
    $html .= "    xajax_ListadoEstadosUsuarios(usuario,empresa,offset);\n";
    $html .= "  }\n";
    
    
    
    
    $html .=    "</script>";
    
    
    
    
    
    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\" id=\"buscador\">";
    $html .= "<table border=\"0\" width=\"30%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "BUSCADOR USUARIOS";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Identificador del Usuario :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"usuario_id\" maxlength=\"6\" >";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre del Usuario :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"nombre\" maxlength=\"30\" >";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "<input value='".$EmpresaId."' type=\"hidden\" name=\"empresa\">";
    $html .= "<input value=\"Buscar\" class=\"input-submit\" type=\"button\" onclick=\"xajax_BuscarUsuario(xajax.getFormValues('buscador'));\">";
    
    $html .= "</td>";
    $html .= "</tr>";
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
    
    
    
    
    $html .= "<div id=\"ListadoUsuarios\">\n"; //DIV PARA EL LISTADO DE USUARIOS CON PERMISOS DE DOCUMENTOS DE BODEGA
        
		$html .= "</div>"; //CIERRA DIV
    
    
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
    
    
    $html .= "<script>";
    $html .= "xajax_UsuariosDocumentosBodegasT('".$EmpresaId."','0');";
    $html .= "</script>";
    
    
    
    
    
    $html .= ThemeCerrarTabla();
    
    $html .= $this->CrearVentana(600,"ASIGNACION DE ESTADOS");
    
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



      
      $html .= "</script>\n";
      $html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
      $html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
      $html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
      $html .= "  <div id='Contenido2' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";
		
      
    
    
    
      return $html;
    }    
  
  
  
  
  
    
  
  }
?>