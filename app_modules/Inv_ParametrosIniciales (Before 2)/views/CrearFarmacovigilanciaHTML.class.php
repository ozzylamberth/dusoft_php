<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

  /**
  * Clase Vista: CrearFarmacovigilanciaHTML
  * Clase Contiene Metodos para el Ingreso de Parametros de FarmacoVigilancia
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
 class CrearFarmacovigilanciaHTML
 {
		/**
		* Constructor de la clase
		*/
		function CrearFarmacovigilanciaHTML(){}
    
   /**
        * Funcion donde se crea la forma para mostrar el menu de farmacovigilancia
        * 
        * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
        */
   function formaMenuFarmacovigilancia($action)
   {
    $html  = ThemeAbrirTabla('PARAMETRIZAR FARMACOVIGILANCIA');
    $html .= "<form name=\"formMenuFarmacovigilancia\" id=\"formMenuFarmacovigilancia\" method=\"post\" action=\"\">\n";
    $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\">MENU\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['registrar_pacientes']."\" class=\"label_error\">REGISTRAR PACIENTES</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['bloquear_productos']."\" class=\"label_error\">BLOQUEAR PRODUCTOS POR LOTE</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $html .= "<table align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $html .= "</form>\n";
    $html .= ThemeCerrarTabla();
      
    return $html;
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
   
   /**
        * Funcion donde se crea la forma para mostrar los pacientes y seleccionar al que se le va hacer el registro
        * 
        * @param array $action vector que contiene los link de la aplicacion
        * @param var   $tipo_documento contiene el tipo de documento
        * @param array $request vector que contiene los datos
        * @return string $html retorna la cadena con el codigo html de la pagina
        */
   function formaRegistrarPacientes($action,$tipo_documento,$request,$conteo,$pagina,$datos)
   {
    $html  = ThemeAbrirTabla('PARAMETRIZAR FARMACOVIGILANCIA');
    $html .= "<form name=\"formMenuFarmacovigilancia\" id=\"formMenuFarmacovigilancia\" method=\"post\" action=\"".$action['buscar_usuarios']."\">\n";
    $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td colspan=\"2\" align=\"center\">REGISTRAR PACIENTES\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td align=\"center\">TIPO DOCUMENTO\n";
    $html .= "    </td>\n";
    $html .= "    <td align=\"center\" class=\"modulo_list_claro\"><select width=\"100%\" class=\"select\" name=\"buscar_usuarios[tipo_documento]\" id=\"tipo_documento\" onchange=\"\">\n";
    $html .= "     <option value=\"-1\">-- Seleccionar --</option>\n";
    
    foreach ($tipo_documento as $indice=>$valor)
    { 
     if($valor['tipo_id_paciente']==$request['tipo_documento'])
      $sel = "selected";
     else   $sel = "";
      $html .= "  <option value=\"".$valor['tipo_id_paciente']."\" ".$sel.">".$valor['descripcion']."</option>\n";
    }
    
    $html .= "    </select>\n";
    $html .= "   </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"modulo_list_claro\">\n";
    $html .= "    <td align=\"center\">DOCUMENTO\n";
    $html .= "    </td>\n";
    $html .= "    <td class=\"modulo_list_claro\" width=\"10%\">\n";
    $html .= "      <input type=\"text\" class=\"input-text\" name=\"buscar_usuarios[documento]\" maxlength=\"20\" value=\"".$request['documento']."\">";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "	  <td align='center'>\n";
	  $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\">\n";
	  $html .= "	  </td>\n"; 
    $html .= "</form>\n";
    $html .= "  <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
	  $html .= "		<td align='center' >\n";
    $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Volver\">\n";
		$html .= "		</td>\n";
		$html .= "  </form>\n";
    $html .= "</table>\n";
    
    $html .= "<br>";
    
    if(!empty($datos))
		{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "  <table width=\"95%\" class=\"modulo_table_list_title\" border=\"1\"  align=\"center\">";
				$html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td width=\"15%\">TIPO DOCUMENTO </td>\n";
				$html .= "      <td width=\"25%\">DOCUMENTO </td>\n";
				$html .= "      <td  width=\"25%\" >NOMBRE PACIENTE </td>\n";
				$html .= "      <td width=\"18%\">REGISTRAR </td>\n";
				$html .= "  </tr>\n";
        
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $valor)
				{
         
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\">".$valor['tipo_id_paciente']."</td>\n";
					$html .= "      <td align=\"center\">".$valor['paciente_id']."</td>\n";
					$html .= "      <td align=\"left\">".$valor['primer_nombre']." ".$valor['segundo_nombre']." ".$valor['primer_apellido']." ".$valor['segundo_apellido']."</td>\n";
					$html .= "      <td align=\"center\">\n";
					$html .= "         <a href=\"#\" onclick=\"xajax_RegistraPaciente('".$valor['tipo_id_paciente']."','".$valor['paciente_id']."','".$valor['primer_nombre']."','".$valor['segundo_nombre']."','".$valor['primer_apellido']."','".$valor['segundo_apellido']."','')\" class=\"label_error\"><img src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\" title=\"Registrar Paciente\"></a>\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
				}
				$html .= "	</table><br>\n";
        
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				$html .= "	<br>\n";
	  }
		else
		{
		 if($request)
		 $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
		}
    $html .= "<script>\n";
    $html .= "function Paginador(a,b,c,d,e,f,offset)";
    $html .= "{";
    $html .= " xajax_BuscarProducto(a,b,c,d,e,f,offset);";
    $html .= "}";
    $html .= "</script>\n";
    $html .= $this->CrearVentana(600,"MENSAJE");
    $html .= ThemeCerrarTabla();
      
      return $html;
   }
   
  /**
     * Funcion donde se crea la forma para mostrar los pacientes y seleccionar al que se le va hacer el registro
     * 
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $datos vector que contiene los datos
     * @return string $html retorna la cadena con el codigo html de la pagina
     */ 
  function formaBloquearProductosLote($action,$Laboratorios,$Moleculas)
  {
    $html  = ThemeAbrirTabla('PARAMETRIZAR FARMACOVIGILANCIA');
    
    
    $SelectLaboratorios = "<select name=\"clase_id\" id=\"clase_id\" class=\"select\" style=\"width:70%;height:70%\">";
          $SelectLaboratorios .= "<option value=\"\">TODOS</option>";
          foreach($Laboratorios as $key=>$valor)
                {
          $SelectLaboratorios .= "<option value=\"".$valor['laboratorio_id']."\">";
          $SelectLaboratorios .= $valor['descripcion'];
          $SelectLaboratorios .= "</option>";
                }
          $SelectLaboratorios .= "</select>";
          
          $SelectMoleculas = "<select name=\"subclase_id\" id=\"subclase_id\" class=\"select\" style=\"width:60%;height:60%\">";
          $SelectMoleculas .= "<option value=\"\">TODOS</option>";
          foreach($Moleculas as $key=>$mol)
                {
          $SelectMoleculas .= "<option value=\"".$mol['molecula_id']."\">";
          $SelectMoleculas .= $mol['descripcion'];
          $SelectMoleculas .= "</option>";
                }
          $SelectMoleculas .= "</select>";
          
          
          $html .= "<table width=\"50%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
					$html .= "		<tr class=\"modulo_table_list_title\">";
					$html .= "			<td>";
					$html .= "			CODIGO";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"codigo_producto_b\" class=\"input-text\">";
					$html .= "			</td>";
					
					$html .= "			<td>";
					$html .= "			DESCRIPCION";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"descripcion_b\" class=\"input-text\">";
					$html .= "			</td>";
					
          
          
					$html .= "			<td>";
					$html .= "			CONCENTRACION";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"contenido_unidad_venta_b\" class=\"input-text\">";
					$html .= "			</td>";
          $html .= "		</tr>";
          
          $html .= "		<tr class=\"modulo_table_list_title\">";
          $html .= "			<td>";
					$html .= "			LABORATORIO";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			".$SelectLaboratorios;
					$html .= "			</td>";
				  
          
          $html .= "			<td>";
					$html .= "			MOLECULA";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			".$SelectMoleculas;
					$html .= "			</td>";
          
          $html .= "			<td>";
					$html .= "			LOTE";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"lote_b\" class=\"input-text\">";
					$html .= "			</td>";
          
					$html .= "		</tr>";
					
					$html .= "		<tr class=\"modulo_table_list_title\">";
					$html .= "			<td colspan=\"6\" align=\"center\">";
                                                                                  //($codigo_producto,$nombre_producto,$concentracion,$clase_id,$subclase_id,$offset)
					$html .= "			<input type=\"button\" onclick=\"xajax_ListarProductosLote(document.getElementById('codigo_producto_b').value,document.getElementById('descripcion_b').value,document.getElementById('contenido_unidad_venta_b').value,document.getElementById('clase_id').value,document.getElementById('subclase_id').value,document.getElementById('lote_b').value,'1');\"  class=\"modulo_table_list\" value=\"buscar\">";
					$html .= "			</td>";
					$html .= "		</tr>";
					$html .= "</table>";
    
          $html .= "<br>";
    
    $html .= "<div id=\"ListadoProd\"></div>";
    
    $html .= ThemeCerrarTabla();    
	$html .= $this->CrearVentana("850","PRODUCTOS A BLOQUEAR");
    //JavaScripts
	$html .= "<script>";
	$html .= "xajax_ListarProductosLote('','','','','','','1');";
	$html .= "</script>";
	
	$html .= "<script>";
	$html .= "function paginador(codigo_producto,nombre_producto,concentracion,clase_id,subclase_id,lote,offset)";
	$html .= "{";
	$html .= "xajax_ListarProductosLote(codigo_producto,nombre_producto,concentracion,clase_id,subclase_id,lote,offset);";
	$html .= "}";
	$html .= "</script>";
	
	return $html;
  }
 }
?>