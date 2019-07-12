<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: Bancos_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: Bancos_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class CrearBancos_HTML
	{
		/**
		* Constructor de la clase
		*/
		function CrearBancos_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($request,$action)
    {
    $accion=$action['volver'];
			  
    
    
    $html .='
  
  <script>
  
  
  function Validar(formulario)
  {
  var band=0;
  var cadena = [];
  //alert("Codigo esta Vacío");
  
  if(formulario.banco=="")
  {
  cadena.push(" Codigo esta Vacío\n");
  //alert("");
  band=1;
  }
  
  if(formulario.descripcion=="")
  {
  cadena.push(" la Descripcion, está Vacía\n");
  //alert("la Descripcion, está Vacía");
  band=1;
  }
  
  if(formulario.tipo_depto_id=="")
  {
  cadena.push(" No haz seleccionado un Departamento\n");
  //alert("No haz seleccionado un Departamento");
  band=1;
  }
  
  if(formulario.tipo_mpio_id=="")
  {
  cadena.push("No haz seleccionado un Municipio\n");
  //alert("No haz seleccionado un Departamento");
  band=1;
  }
  
  if(formulario.telefono=="")
  {
  cadena.push("Campo de Telefono, Vacío\n");
  band=1;
  }
  
  if(formulario.direccion=="")
  {
  cadena.push("Campo de direccion, Vacío\n");
  band=1;
  }
    
  if(band==1)
          {
          alert(cadena);
          alert("Por Favor, llenar los Campos Vacíos!!!");
          }
          
          else
          {
              //alert("Formulario bien diligenciado!!!");
              var entrar = confirm("Confirmar Envio de datos?")
              if (entrar) 
              {
                //alert("Haz dado en Aceptar");
               if(formulario.token=="1") //Si es Ingreso de Laboratorio
                  {
                  xajax_InsertarBanco(formulario);
                  //alert("ingreso");
                  }
                       else
                          {
                          xajax_GuardarModBanco(formulario);
                         // alert("Modi");
                          }
               
                
              }
                else
                {
                  alert("Haz Cancelado");
                                   
                }
          }
  
  }
  
  
  
  function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
  
  
  </script>
  ';
    
    
    
    
    
    
    
    
    
    $html .= ThemeAbrirTabla('CREAR BANCOS');
    
      
    $html .= "<BR><BR><CENTER><a class=\"label_error\" href=\"#\" onclick=\"xajax_IngresoBanco()\">[::CREAR UN NUEVO BANCO::]</a><BR></CENTER>";
	  
	  $html .= "<script>\n";
      
      
      
      
      //5) Paso LLamado a funcion: Crea un intermediario para el llamado a la funcion xajax
    $html .= "  function PaginadorBusquedas(Banco,Descripcion,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BusquedaBancos(Banco,Descripcion,offset);\n";
    $html .= "  }\n";
    
    
    $html .= "  function paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_BancosT(offset);\n";
    $html .= "  }\n";
    
    
      
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
    $html .= "<table border=\"0\" width=\"30%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "CODIGO BANCO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"banco\" size=\"20\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    $html .= "</tr>";
        
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "NOMBRE :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"descripcion\" style=\"width:60%;height:60%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"enviar\" onclick=\"PaginadorBusquedas(document.buscador.banco.value,document.buscador.descripcion.value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      
      
      
      
      
      
	  $html .= "<div id=\"ListadoBancos\">\n"; //DIV PARA EL LISTADO DE BANCOS CREADOS
 
		$html .= "</div>"; //CIERRA DIV DE LISTADO DE NOVEDADES CREADAS
    
    
    
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\"><br>\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
	  
	  $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
       
    $html .= $this->CrearVentana(600,"CREAR BANCOS");
    $html .= ThemeCerrarTabla();
    $html .= "<script>";
    $html .= "xajax_BancosT();";
    $html .= "</script>";
    
    
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