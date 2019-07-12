<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: NovedadesHTML.class.php,v 1.8 2008/09/01 20:42:45 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: NovedadesHTML
  * Clase encargada de crear las formas para el registro de novedades
  *
  * @package IPSOFT-SIIS
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class PlantillasHTML
  {
    /**
    * constructor de la clase
    */
    function PlantillasHTML(){}
    /**
    * Funcion para la forma de las conversiones
    */
    function FormaConvensiones()
    {
      $html .= "<table border=\"0\" align=\"center\"  width=\"80%\" class=\"modulo_table_list\">";
			$html .= "	<tr class=\"formulacion_table_list\">";
			$html .= "	  <td  align=\"center\" colspan=\"8\" >CONVENCIONES</td>";
			$html .= "	</tr>";
			$html .= "	<tr>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"formulacion_table_list\">NOMBRE PACIENTE</td>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[PACIENTE]</td>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"formulacion_table_list\">CARGO CUPS</td>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[CARGO]</td>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"formulacion_table_list\">EXAMEN</td>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[EXAMEN]</td>";
			$html .= "		<td width=\"10%\" align=\"left\" class=\"formulacion_table_list\">DESCRIPCION TECNICA</td>";
			$html .= "	  <td width=\"10%\" align=\"left\" class=\"modulo_list_claro\">[TECNICA]</td>";
			$html .= "  </tr>";
			$html .= "</table>";
      
      return $html;
    }
    /**
    * Funcion para los Subexamenes de una plantilla
    */
    function ListaSubexamenes($datos,$opciones)
    {
    $html .= "	<input type=hidden name='caso' value='2'>";
		$html .= "	<input type=hidden name='cargo' value='".$cargo_apd."'>";
		$html .= "	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
    $html .= "	  <tr class=\"formulacion_table_list\" align=\"center\">";
		$html .= "		  <td width=\"15%\" align=\"center\">OPCION EXAMEN</td>";
		$html .= "		  <td width=\"15%\" align=\"center\">EXAMEN</td>";
		$html .= "		  <td width=\"15%\" align=\"center\">CARGO</td>";
		$html .= "		  <td width=\"15%\" align=\"center\">TECNICA</td>";
		$html .= "		  <td width=\"15%\" align=\"center\">OPCION</td>";
		$html .= "		  <td width=\"15%\" align=\"center\">UNIDADES</td>";
    $html .= "		  <td width=\"15%\" align=\"center\">NORMALIDADES</td>";
    $html .= "		  <td width=\"15%\" align=\"center\">EDITAR</td>";
    $html .= " 	</tr>";
    
    
    foreach($datos as $key => $valor)
    {
			if( $key % 2){ $estilo='modulo_list_claro';}
			else {$estilo='modulo_list_oscuro';}
  		$html .= "	  <tr class='$estilo' align=\"center\">";
  		$html .= "		  <td width=\"15%\" align=\"center\">".$valor['lab_examen_opcion_id']."</td>";
  		$html .= "		  <td width=\"15%\" align=\"center\">".$valor['lab_examen_id']."</td>";
  		$html .= "		  <td width=\"15%\" align=\"left\">".$valor['cargo']."</td>";
  		$html .= "		  <td width=\"15%\" align=\"center\">".$valor['tecnica_id']."</td>";
      $html .= "		  <td width=\"15%\" align=\"left\">\n";
      $vec_opcion = $opciones[$valor['cargo']][$valor['lab_examen_id']][$valor['lab_examen_opcion_id']][$valor['tecnica_id']];
      if(!empty($vec_opcion))
      {
        $html .= "      <ul>\n";
        foreach($vec_opcion as $kI => $dtl)
          $html .= "        <li>".$dtl['descripcion']."</li>\n";
        $html .= "      </ul>\n";
      }
      $html .= "      </td>\n";
      $html .= "		  <td width=\"15%\" align=\"center\">".$valor['unidades']."</td>";
  		$html .= "		  <td width=\"15%\" align=\"left\">".$valor['normalidades']."</td>";
      $html .= "		      	<td>\n";
      $html .= "		      	  <a href=\"javascript:Prueba('".$valor['cargo']."','".$valor['lab_examen_id']."','".$valor['lab_examen_opcion_id']."','".$valor['tecnica_id']."')\" class=\"label_error\">\n";
      $html .= "		        	  <img src=\"".GetThemePath()."/images/editar.png\" border='0'>\n";
      $html .= "		      	  </a>\n";
      $html .= "		      	</td>\n";
      $html .= "	  </tr>";
		}
		$html .= "	</table>";
    $html .= "<script>\n";
    $html .= "  function Prueba(cargo,lab_examen_id,lab_examen_opcion_id,tecnica_id)\n";
    $html .= "  {\n";
    $html .= "    xajax_PruebaF(cargo,lab_examen_id,lab_examen_opcion_id,tecnica_id);\n";
    $html .= "  }\n";
    
    $html .= "  function Eliminar(opcion_id,cargo,lab_examen_id,lab_examen_opcion_id,tecnica_id)\n";
    $html .= "  {\n";
    $html .= "    xajax_EliminarOpciones(opcion_id, cargo,lab_examen_id,lab_examen_opcion_id,tecnica_id);\n";
    $html .= "  }\n";
    
    $html .= "  function ActualizarPlan (cargo, lab_examen_id, lab_examen_opcion_id,tecnica_id)\n";
    $html .= "  {\n";
    $html .= "    xajax_ActualizarP(cargo, lab_examen_id, lab_examen_opcion_id,tecnica_id,xajax.getFormValues('forma'));\n";
    $html .= "  }\n";
    
    $html .= "		function Actualizar()\n";
		$html .= "		{\n";
		$html .= "			document.location.href=\"\";\n";
		$html .= "		}\n";
    
    $html .= "</script>\n";
    $html .= $this->CrearVentana(600,300);
    return $html;
    }
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param string $funcion Funcion a la que se llama cuando se hace submit sobre la forma
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 350,$alt = 380)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 5;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"none\";\n";
			
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";	
      
			$html .= "	function MostrarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
      $html .= "			e = xGetElementById('Contenedor');\n";
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
			$html .= "	  xResizeTo(ele,".$tmn.", ".$alt.");\n";			
      $html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn-20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn-20).", 0);\n";
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
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:5\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	    <div id=\"capa_buscador\"></div>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
    /**
    * Funcion para los Editar la plantilla
    */
    function EditarDatos($valor,$rst2)
    {
      $html  = "	<input type=hidden name='caso' value='2'>";
  		$html .= "	<input type=hidden name='cargo' value='".$cargo_apd."'>";
  		$html .= "	<table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
      $html .= "	  <tr class=\"formulacion_table_list\" align=\"center\">";
  		$html .= "		  <td width=\"15%\" align=\"center\">OPCION EXAMEN</td>";
  		$html .= "		  <td width=\"15%\" align=\"center\">EXAMEN</td>";
  		$html .= "		  <td width=\"15%\" align=\"center\">CARGO</td>";
  		$html .= "		  <td width=\"15%\" align=\"center\">TECNICA</td>";
  		$html .= "		</tr>\n";
      $html .= "	  <tr class='modulo_list_claro' align=\"center\">";
      $html .= "		  <td width=\"15%\" align=\"center\">".$valor['lab_examen_opcion_id']."</td>";
      $html .= "		  <td width=\"15%\" align=\"center\">".$valor['lab_examen_id']."</td>";
      $html .= "		  <td width=\"15%\" align=\"left\">".$valor['cargo']."</td>";
      $html .= "		  <td width=\"15%\" align=\"center\">".$valor['tecnica_id']."</td>";
      $html .= "		</tr>\n";
      $html .= "	  <tr align=\"center\">";
      $html .= "		  <td class=\"formulacion_table_list\" width=\"15%\" align=\"center\">UNIDADES</td>";
      $html .= "		  <td width=\"15%\" align=\"center\">\n";
      $html .= "        <input type=\"text\" class=\"input-text\" name='datos[unidades]' \" id=\"unidades\" maxlength=\"25\">".$valor['unidades']."</td>";
      $html .= "			</td>";
      $html .= "	  </tr>\n";
      $html .= "		<tr align=\"center\" class=\"formulacion_table_list\">";
      $html .= "			<td colspan=\"4\" >TEXTO DE NORMALIDADES</td>";
      $html .= "		</tr>\n";
      $html .= "		<tr align=\"center\" class=\"modulo_list_claro\">";
      $html .= "			<td colspan=\"4\">";
      $html .= "				<textarea name=\"datos[normalidades]\" rows=\"2\" style=\"width:100%\">".$valor['normalidades']."</textarea>"; 
      $html .= "	    </td>\n";
      $html .= "	  </tr>";
      $html .= "	  <tr>\n";
      $html .= "			<td colspan=\"4\">\n";
      $html .= "	      <table border=\"0\" align=\"center\"  width=\"100%\" class=\"modulo_table_list\">";
      $html .= "		      <tr align=\"center\" class=\"formulacion_table_list\">";
      $html .= "		        <td width=\"100%\" align=\"center\">OPCIONES</td>";
      $html .= "		        <td width=\"15%\" align=\"center\">ELIMINAR</td>";
      $html .= "		      </tr>\n"; 
          
      if(!empty($rst2))
      {
        foreach($rst2 as $kI => $dtl)
        {
           $html .= "	  <tr class='modulo_list_claro' >";
           $html .= "		  <td width=\"15%\">".$dtl['descripcion']."</td>";
           $html .= "		      	<td>\n";
           $html .= "		      	  <a href=\"javascript:Eliminar('".$dtl['opcion_id']."','".$valor['cargo']."','".$valor['lab_examen_id']."','".$valor['lab_examen_opcion_id']."','".$valor['tecnica_id']."')\" class=\"label_error\">\n";
           $html .= "		        	  <img src=\"".GetThemePath()."/images/elimina.png\" border='0'>\n";
           $html .= "		      	  </a>\n";
           $html .= "		      	</td>\n"; 
           $html .= "	  </tr>";
        }
      }
      $html .= "		      <tr class=\"formulacion_table_list\">";
      $html .= "		        <td align=\"left\" colspan=\"2\">\n";
      $html .= "              <input type=\"text\" class=\"input-text\" name='datos[opcion_des]' id=\"opcion_descripcion\" maxlength=\"30\">";
      $html .= "              <input class=\"input-submit\" name=\"adicionar\" type=\"button\" value=\"ADICIONAR\" onclick=\"ActualizarPlan('".$valor['cargo']."','".$valor['lab_examen_id']."','".$valor['lab_examen_opcion_id']."','".$valor['tecnica_id']."')\">\n";
      $html .= "	          </td>\n";
      $html .= "	        </tr>\n";
      $html .= "	      </table>";
      $html .= "	    </td>\n";
      $html .= "	  </tr>";
      $html .= "	</table>";

      return $html;
    }
}
?>