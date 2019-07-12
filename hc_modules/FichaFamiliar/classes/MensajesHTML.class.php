<?
class MensajesHTML{
  //Construcctor de la clase 
  function MensajesHTML(){
  }

  
  /**
  *		Metodo que permite ajustar el nombre del paciente  
  */
  function ValidarCosas(){

    $html .= " "; 
  
  	return $html;
  }
  
  
  /**
  *		Funcion que crea la ventana en la cual se ajusta el nombre completo del paciente
  */
  function CrearVentana($tmn = 640, $titulo="Titulo")
  {
    $html .= "<script>\n";
    $html .= "  var contenedor = 'Contenedor';\n";
    $html .= "  var titulo = 'titulo';\n";
    $html .= "  var hiZ = 5;\n";
    $html .= "  function OcultarSpan()\n";
    $html .= "  { \n";
    $html .= "    try\n";
    $html .= "    {\n";
    //$html .= "      xGetElementById('capaFondo1').style.display = \"none\";\n";
    $html .= "      e = xGetElementById('Contenedor');\n";
    $html .= "      e.style.display = \"none\";\n";
    $html .= "    }\n";
    $html .= "    catch(error){}\n";
    $html .= "  }\n";
    $html .= "  function MostrarSpan()\n";
    $html .= "  { \n";
    $html .= "    try\n";
    $html .= "    {\n";
    //$html .= "      xGetElementById('capaFondo1').style.display = \"block\";\n";
    $html .= "      e = xGetElementById('Contenedor');\n";
    $html .= "      e.style.display = \"block\";\n";
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
    $html .= "    ele = xGetElementById(contenedor);\n";
    $html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
    $html .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+20);\n";
    $html .= "    ele = xGetElementById(titulo);\n";
    $html .= "    ele.innerHTML = '".$titulo."';\n";
    $html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
    $html .= "    xMoveTo(ele, 0, 0);\n";
    $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $html .= "    ele = xGetElementById('cerrar');\n";
    $html .= "    xResizeTo(ele,20, 20);\n";
    $html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
    $html .= "  }\n";

    $html .= "  function IniciarGrande()\n";
    $html .= "  {\n";
    $html .= "    contenedor = 'Contenedor';\n";
    $html .= "    titulo = 'titulo';\n";
    $html .= "    ele = xGetElementById(contenedor);\n";
    $html .= "    xResizeTo(ele,800, 'auto');\n";
    $html .= "    xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
    $html .= "    ele = xGetElementById(titulo);\n";
    $html .= "    ele.innerHTML = 'LISTADO DE CARGOS';\n";
    $html .= "    xResizeTo(ele,780, 20);\n";
    $html .= "    xMoveTo(ele, 0, 0);\n";
    $html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $html .= "    ele = xGetElementById('cerrar');\n";
    $html .= "    xResizeTo(ele,20, 20);\n";
    $html .= "    xMoveTo(ele,780, 0);\n";
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
    $html .= "</script>\n";
    $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:5\">\n";
    $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">CONFIRMACI�</div>\n";
    $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
    $html .= "  <div id='Contenido' class='d2Content'>\n";
    $html .= "  <div id=\"ventana\" ></div>\n";
    $html .= "  <div id=\"erroro\" class=\"label_error\" style=\"text-align:center\"></div>\n";
    $html .= "  </div>\n";
    $html .= "</div>\n";

    return $html;
  }
  
  /**
  * Forma que Muestra en el mensaje de exito o fracaso en la creacion de una Ficha Familiar 
  */
  function fmrMsjIngrFichaFamiliar($action, $mensaje){
  
      //$action['volver'] = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false, array('accion'.$pfj=>''));
      
      $html  = ThemeAbrirTabla('MENSAJE');
      $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "  <tr>\n";
      $html .= "    <td>\n";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "        <tr class=\"normal_10AN\">\n";
      $html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
      $html .= "        </tr>\n";
      $html .= "      </table>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\"><br>\n";
      $html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"btnVolver\" value=\"Volver\">";
      $html .= "      </form>";
      $html .= "    </td>";
      $html .= "  </tr>";
      $html .= "</table>";
      $html .= ThemeCerrarTabla();      
      return $html; 
  }   
}
?>