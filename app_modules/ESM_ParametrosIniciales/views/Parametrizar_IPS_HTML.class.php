<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: Parametrizar_IPS_HTML.class.php
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @authorSandra Viviana Pantoja
  */

  /**
  * Clase Vista: Parametrizar_IPS_HTML.class.php
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja
  */

	class Parametrizar_IPS_HTML
	{
		/**
		* Constructor de la clase
		*/
		function Parametrizar_IPS_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$TipoIdTercero,$request)
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
              
           if(Formulario.descripcion=="")
          {
          cadena.push("No Haz Ingresado Una Descripcion\n");
          band=1;
          }
		  
		  if(Formulario.codigo_fuerza=="")
          {
          cadena.push("No Haz Ingresado Un Codigo FF.MM\n");
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
                               xajax_Insertar_TipoFuerza(Formulario);
                               }
                                  else
                                      {
                                      //alert("Modificacion");
                                      xajax_Modificar_TipoFuerza(Formulario);
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
    $html .= "  function Paginador(tipo_id_tercero,tercero_id,nombre_tercero,offset)\n";
    $html .= "  {";
    $html .= "    xajax_Listado_Terceros(tipo_id_tercero,tercero_id,nombre_tercero,offset);\n";
    $html .= "  }\n";   
    $html .= "  function PaginadorESM(tipo_id_tercero,tercero_id,nombre_tercero,offset)\n";
    $html .= "  {";
    $html .= "    xajax_Listado_ESM(tipo_id_tercero,tercero_id,nombre_tercero,offset);\n";
    $html .= "  }\n"; 
    $html .="</script>";
    
  
    $html .= ThemeAbrirTabla('PARAMETRIZACION DE IPS');
    
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
      
      //$html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_Ingreso_TipoFuerza()\">[::CREAR - NUEVO TIPO DE FUERZA MILITAR::]</a><BR></CENTER>";
       
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
			$html .= "									<h2 class=\"tab\">TERCEROS (NO IPS)</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_estadosdocumentos\")); </script>\n";
       
      $select .= "<select class=\"select\" style=\"width:100%;\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\">";      $select .= "<option value=\"\">SELECCIONAR</option>";
      foreach($TipoIdTercero as $key=>$ti)
      {
      $select .= "<option value=\"".$ti['tipo_id_tercero']."\">".$ti['descripcion']."</option>";
      }
      $select .= "</select>";
      
      $html .= "<center>";
      $html .= "  <table border=\"0\" width=\"80%\" align=\"center\" >\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td colspan=\"7\" align=\"center\">";
      $html .= "     BUSCADOR";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          TIPO ID";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "          ".$select;
      $html .= "          </td>\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          IDENTIFICACION";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"tercero_id\" id=\"tercero_id\">\n";
      $html .= "          </td>\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          NOMBRE";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"nombre_tercero\" id=\"nombre_tercero\">\n";
      $html .= "          </td>\n";
      $html .= "          <td>";
      $html .= "        <input type=\"button\" value=\"BUSCAR\" onclick=\"xajax_Listado_Terceros(document.getElementById('tipo_id_tercero').value,document.getElementById('tercero_id').value,document.getElementById('nombre_tercero').value,'1');\" style=\"width:100%\" class=\"input-submit\">";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      
      $html .= "  </table>";
      $html .= "</center>";
      $html .= "<br>";
      
      $html .= "  <div id=\"Listado_Terceros\">";
      $html .= "  </div>";
      $html .= "								</div>\n"; //CIERRA PRIMER TAB
       
       $html .= "								<div class=\"tab-page\" id=\"asociar_estadosdocumentos\">\n";
       $html .= "									<h2 class=\"tab\">CONSULTAR IPS</h2>\n";
       $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asociar_estadosdocumentos\")); </script>\n";

       $select = "<select class=\"select\" style=\"width:100%;\" name=\"esm_tipo_id_tercero\" id=\"esm_tipo_id_tercero\">";      $select .= "<option value=\"\">SELECCIONAR</option>";
      foreach($TipoIdTercero as $key=>$ti)
      {
      $select .= "<option value=\"".$ti['tipo_id_tercero']."\">".$ti['descripcion']."</option>";
      }
      $select .= "</select>";
      $html .= "<br>";
      $html .= "<center>";
      $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" >\n";
      $html .= "    <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td colspan=\"7\" align=\"center\">";
      $html .= "     BUSCADOR";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          TIPO ID";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "          ".$select;
      $html .= "          </td>\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          IDENTIFICACION";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"esm_tercero_id\" id=\"esm_tercero_id\">\n";
      $html .= "          </td>\n";
      $html .= "          <td class=\"modulo_table_list_title\">";
      $html .= "          NOMBRE";
      $html .= "          </td>";
      $html .= "          <td align=\"center\">\n";
      $html .= "            <input class=\"input-text\" type=\"text\" style=\"width:100%\" name=\"esm_nombre_tercero\" id=\"esm_nombre_tercero\">\n";
      $html .= "          </td>\n";
      $html .= "          <td>";
      $html .= "        <input type=\"button\" value=\"BUSCAR\" onclick=\"xajax_Listado_IPS(document.getElementById('esm_tipo_id_tercero').value,document.getElementById('esm_tercero_id').value,document.getElementById('esm_nombre_tercero').value,'1');\" style=\"width:100%\" class=\"input-submit\">";
      $html .= "    </td>";
      $html .= "    </tr>\n";
      $html .= "  </table>";
      $html .= "</center>";
      $html .= "<br>";
       
      $html .= "  <div id=\"Listado_ESM\">";
      $html .= "  </div>";
      $html .= "								</div>\n"; //CIERRO SEGUNDO TAB
      $html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n"; //CIERRO TODOS LOS TABS
      
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      
      $html .= "   <script>";
      $html .= "    xajax_Listado_Terceros('','','','1');";
      $html .= "    xajax_Listado_ESM('','','','1');";
      $html .= "   </script>";
      
    $html .= $this->CrearVentana(600,"CREACION DE TIPOS DE FUERZAS");
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