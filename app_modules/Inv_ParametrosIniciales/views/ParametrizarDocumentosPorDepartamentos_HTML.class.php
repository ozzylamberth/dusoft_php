<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: AsignarDocumentosABodegas_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: AsignarDocumentosABodegas_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class ParametrizarDocumentosPorDepartamentos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function ParametrizarDocumentosPorDepartamentos_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$request,$Empresas)
    {
    $accion=$action['volver'];
    $url=$request['url_destino'];
	  
    //print_r($request);
    
      $html .= ThemeAbrirTabla($request['nombre_opcion']);
    
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
     
     
      $html .= "<center>\n";
      $html .= "<fieldset class=\"fieldset\" style=\"width:40%\">\n";
      $html .= "  <legend class=\"normal_10AN\">SELECCIONE LA EMPRESA</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      
      foreach($Empresas as $key => $Em)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "    <td class=\"normal_10AN\">";
            $html .= "<a href=\"#\" onclick=\"xajax_CentrosDeUtilidad('".$Em['empresa_id']."','CentroUtilidadEmp".$Em['empresa_id']."','".$url."','".$Em['empresa']."')\">\n";
            $html .= $Em['empresa'];
            $html .="<div id=\"CentroUtilidadEmp".$Em['empresa_id']."\"></div>";
            $html .= "</td>";
            $html .= "</tr>";
            
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset>\n";
      
    
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "</center>\n";
      $html .= ThemeCerrarTabla();
      $html .= $this->CrearVentana(600,"Seleccione, la Unidad Funcional");
    return($html);
    }
  
    
    
    
    function AsignarDocumentosADepartamentos($action,$request)
    {
    $accion=$action['volver'];
			  
    //print_r($request);
    $EmpresaId=$request['empresa_id'];
    $CentroUtilidad=$request['centro_utilidad'];
    $Unidad_Funcional=$request['unidad_funcional'];
   
   
    $html .="<script>";
    $html .="function paginador(empresa_id,centro_utilidad,unidadfuncional,departamento_id,descripcion,offset)
              {
              xajax_DepartamentosT(empresa_id,centro_utilidad,unidadfuncional,departamento_id,descripcion,offset);
              }";
    $html .="</script>";
    
    
    $html .="<script>";
    $html .="function paginador_(departamento,TDocumentoId,Descripcion,TMovimiento,offset)
              {
              xajax_ListadoTiposDocumentosSinAsignar(departamento,TDocumentoId,Descripcion,TMovimiento,offset);
              }";
    $html .="</script>";
    
    $html .="<script>";
    
    $html .="function PaginadorTDBuscados(departamento,TDocumentoId,Descripcion,TMovimiento,offset)
              {
              xajax_ListadoTiposDocumentosAsignados(departamento,TDocumentoId,Descripcion,TMovimiento,offset);
              }";
    $html .="</script>";
    
    
    
    $html .= ThemeAbrirTabla('ASIGNAR DOCUMENTOS A BODEGAS');
    
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
      
      //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "IDENTIFICADOR. DEPARTAMENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"departamento_id\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
   
    
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                       
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"paginador('".$EmpresaId."','".$CentroUtilidad."','".$Unidad_Funcional."',document.getElementById('departamento_id').value,document.getElementById('descripcion').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
    $html .= "<div id=\"Departamentos\">";    
    $html .= "</div>";    


    $html .= "<div id=\"TiposDocumentosSinAsignar\">";    
    $html .= "</div>";  
    
    $html .="<script>";
    $html .= "xajax_DepartamentosT('".$EmpresaId."','".$CentroUtilidad."','".$Unidad_Funcional."');";
    $html .="</script>";
        
    $html .="<script>";
    $html .= "xajax_DocumentosAsignadoABodegaT('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."');";
    $html .="</script>";
    
    	
      
      
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
      
      $html .= $this->CrearVentana(1000,"DOCUMENTOS POR DEPARTAMENTO");
    
    
    
    
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