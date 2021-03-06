<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: CrearEstadosDocumentos_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: CrearEstadosDocumentos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del M?dulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class AsignarTopesDispensacionFarmacias_HTML
	{
		/**
		* Constructor de la clase
		*/
		function AsignarTopesDispensacionFarmacias_HTML(){}
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
    
    
    
    
    
    function pantalla_2($action,$EmpresaId)
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
    
          
          if(Formulario.tope=="")
          {
          cadena.push("No Haz Ingresado un Tope!!\n");
          band=1;
          }
          
              
        var entrar = confirm("Continuar?")

        if (entrar) 
              {
                  
                  
                 if(band==1)
                  {
                  alert(cadena);
                  alert("?Por favor, Diligenciar todos los Datos!");
                   } 
                      else
                      {
                               if(Formulario.token=="1")
                               {
                               //alert("Ingreso");
                               xajax_InsertarAsignarDispensacionTope(Formulario);
                               }
                                  else
                                      {
                                      //alert("Modificacion");
                                      xajax_ModTopeDispensacion(Formulario);
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
    $html .= "    xajax_TiposDispensacionT('".$EmpresaId."',offset);\n";
    $html .= "  }\n";   
    $html .="</script>";
    
    $html .="<script>";
    $html .= "  function paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_TiposDispensacionAsignadas('".$EmpresaId."',offset);\n";
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
			$html .= "							<div class=\"tab-pane\" id=\"TopesDispensacion\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"TopesDispensacion\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"asignar_topes\">\n";
			$html .= "									<h2 class=\"tab\">ASIGNAR TIPOS DE DISPENSACION</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asignar_topes\")); </script>\n";
      
      $html .= "<div id=\"ListadoTiposDispensacion\">\n"; 
      $html .= "								 </div>\n";
      
      $html .= "								</div>\n";
        
        //Otro Tab.
        $html .= "								<div class=\"tab-page\" id=\"\">\n";
        $html .= "									<h2 class=\"tab\">TIPOS DE DISPENSACION ASIGNADO</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asociar_estadosdocumentos\")); </script>\n";

        
    
        $html .="<div id=\"tipos_dispensacion_asignados\">";
        $html .="</div>";
                  
        $html .= "								</div>\n";
        
    
     
     
    
    
    
 
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
    
    $html .="<script>";
    $html .= "    xajax_TiposDispensacionT('".$EmpresaId."');\n";
    $html .="</script>";
    
    
    $html .="<script>";
    $html .= "    xajax_TiposDispensacionAsignadas('".$EmpresaId."');\n";
    $html .="</script>";
    
    
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