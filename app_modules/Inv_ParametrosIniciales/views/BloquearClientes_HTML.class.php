<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: BloquearClientes_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class BloquearClientes_HTML
	{
		/**
		* Constructor de la clase
		*/
		function BloquearClientes_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
    
   
   
   function Menu($request,$action)
		{
		$accion=$action['volver'];
		$html  = ThemeAbrirTabla('BLOQUEO DE CLIENTES');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\">";
		$html .= "      MENÚ";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"#\" onclick=\"xajax_MenuDeEmpresas()\">BLOQUEO DE CLIENTES - TERCEROS</a>";
		$html .= "      </td>";
		$html .= "      </tr>";
		
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <a href=\"". ModuloGetURL('app','Inv_ParametrosIniciales','controller','BloquearPacientes') ."\">BLOQUEO DE CLIENTES - PACIENTES</a>";
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
    $html .= $this->CrearVentana(640,"SELECCIONE EMPRESA...");
		$html .= ThemeCerrarTabla();
		
		return $html;
	
		}
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
    
		function BloquearTerceros($request,$action,$TiposId)
    {
    $accion=$action['volver'];
			  
    $select .="<select class=\"select\" name=\"tipo_id\" style=\"width: 60%; height: 60%;\" onChange=\"validar(this.value)\">";
    foreach($TiposId as $key => $ti)
        {
    $select .="<option value='".$ti['tipo_id_tercero']."' >";
    $select .= $ti['tipo_id_tercero']." - ".$ti['descripcion'];
    $select .="</option>";
        }
    $select .="";
    
    
    
    
    $html .='
  
  <script>
  
  function validar(valor)
  {
  if(valor=="NIT")
    {
    document.buscador.dv.disabled=false;
    document.buscador.dv.value="";
    }
          else
              {
              document.buscador.dv.disabled=true;
              document.buscador.dv.value="0";
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
    
    
    
    
    
    
    
    
    
    $html .= ThemeAbrirTabla('BLOQUEAR CLIENTES - TERCEROS');
    
    $html .= "<script>\n";
      
      
      
      
      //5) Paso LLamado a funcion: Crea un intermediario para el llamado a la funcion xajax
    $html .= "  function Buscador(tipo_id,tercero_id,dv,nombre_tercero,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BuscarTercero(tipo_id,tercero_id,dv,nombre_tercero,offset);\n";
    $html .= "  }\n";
    
    
    
    $html .= "  function paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_TercerosT(offset);\n";
    $html .= "  }\n";
    
    $html .= "  function Paginador(offset,TerceroId,TipoBloqueoId,NombreTercero,offset1)\n";
    $html .= "  {";
    $html .= "    xajax_TiposBloqueos(offset,TerceroId,TipoBloqueoId,NombreTercero,offset1);\n";
    $html .= "  }\n";
    
    $html .= "  function PaginadorBusqueda(tipo_id,tercero_id,dv,nombre_tercero,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BuscarTercero(tipo_id,tercero_id,dv,nombre_tercero,offset);\n";
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
    $html .= "Tipo Id :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= $select;
    $html .= "</td>";
    $html .= "</tr>";
        
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "ID :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"tercero_id\" size=\"20\" maxlength=\"10\">";
    $html .= " - <input class=\"input-text\" value=\"0\" type=\"text\" name=\"dv\" size=\"2\" maxlength=\"1\">";
    $html .= "</td>";
    $html .= "</tr>";
        
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"nombre_tercero\" style=\"width:60%;height:60%;\" maxlength=\"40\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"enviar\" onclick=\"Buscador(document.buscador.tipo_id.value,document.buscador.tercero_id.value,document.buscador.dv.value,document.buscador.nombre_tercero.value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      
      
      
      
	  $html .= "<div id=\"ListadoTerceros\">\n"; //DIV PARA EL LISTADO DE DISPENSACIÓN
 		$html .= "</div>"; //CIERRA DIV DE LISTADO DE DISPENSACION
    
    
    
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
       
    $html .= $this->CrearVentana(600,"BLOQUEOS");
    $html .= ThemeCerrarTabla();
    $html .= "<script>";
    $html .= "xajax_BuscarTercero('','','','','1');";
    $html .= "</script>";
    
    
    return($html);
    }
    
	
  
  
  
  
  function BloquearPacientes($request,$action,$TiposId)
    {
    $accion=$action['volver'];
			  
    $select .="<select class=\"select\" name=\"tipo_id\" style=\"width: 60%; height: 60%;\">";
    $select .="<option value=\"\" >";
    $select .= "Todos";
    $select .="</option>";
     foreach($TiposId as $key => $ti)
        {
    $select .="<option value='".$ti['tipo_id_paciente']."' >";
    $select .= $ti['tipo_id_paciente']." - ".$ti['descripcion'];
    $select .="</option>";
        }
    $select .="";
    
    
    
    
    $html .='
  
  <script>
  
  function validar(valor)
  {
  if(valor=="NIT")
    {
    document.buscador.dv.disabled=false;
    document.buscador.dv.value="";
    }
          else
              {
              document.buscador.dv.disabled=true;
              document.buscador.dv.value="0";
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
    
     
    
    $html .= ThemeAbrirTabla('BLOQUEAR CLIENTES - PACIENTES');
    
    $html .= "<script>\n";
      
      
     // document.buscador.tipo_id.value,document.buscador.paciente_id.value,document.buscador.primer_nombre.value,document.buscador.primer_apellido.value
      
      //5) Paso LLamado a funcion: Crea un intermediario para el llamado a la funcion xajax
    $html .= "  function Buscador(tipo_id,paciente_id,primero_nombre,primer_apellido,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BuscarPaciente(tipo_id,paciente_id,primero_nombre,primer_apellido,offset);\n";
    $html .= "  }\n";
    
    
    
    $html .= "  function paginador(offset)\n";
    $html .= "  {";
    $html .= "    xajax_PacientesT(offset);\n";
    $html .= "  }\n";
    
    $html .= "  function Paginador(offset,TerceroId,TipoBloqueoId,NombreTercero,offset1)\n";
    $html .= "  {";
    $html .= "    xajax_TiposBloqueos(offset,TerceroId,TipoBloqueoId,NombreTercero,offset1);\n";
    $html .= "  }\n";
    
    $html .= "  function PaginadorBusqueda(tipo_id,paciente_id,primero_nombre,primer_apellido,offset)\n";
    $html .= "  {";
    $html .= "    xajax_BuscarPaciente(tipo_id,paciente_id,primero_nombre,primer_apellido,offset);\n";
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
    $html .= "Tipo Id :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= $select;
    $html .= "</td>";
    $html .= "</tr>";
        
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "ID :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"paciente_id\" size=\"20\" style=\"width:60%;height:60%;\">";
    $html .= "</td>";
    $html .= "</tr>";
        
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"primer_nombre\" style=\"width:60%;height:60%;\" >";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Apellido :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"primer_apellido\" style=\"width:60%;height:60%;\" >";
    $html .= "</td>";
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"2\" align=\"center\">";
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"enviar\" onclick=\"Buscador(document.buscador.tipo_id.value,document.buscador.paciente_id.value,document.buscador.primer_nombre.value,document.buscador.primer_apellido.value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      
      
      
      
	  $html .= "<div id=\"ListadoPacientes\">\n"; //DIV PARA EL LISTADO DE DISPENSACIÓN
 		$html .= "</div>"; //CIERRA DIV DE LISTADO DE DISPENSACION
    
    
    
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
       
    $html .= $this->CrearVentana(600,"BLOQUEOS");
    $html .= ThemeCerrarTabla();
    $html .= "<script>";
    $html .= "xajax_PacientesT();";
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

 
      return $html;
    }    
    
    
  
  }
?>