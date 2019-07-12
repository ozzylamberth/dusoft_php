<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: SalidasProductosHTML.class.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  /**
  * Clase Vista: SalidasProductosHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
  
 class SalidasProductosHTML
 {
   
   
   
   /**
         * Constructor de la clase
        */
   function SalidasProductosHTML(){}
    
   /**
        * Funcion donde se crea la forma para el menu de Parametrizacion tiempo de cita
        *
        * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
        */
   function formaMenu($action)
   {
      
   
      $html  = ThemeAbrirTabla('PARAMETRIZAR SALIDAS DE PRODUCTOS');
      $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_title\">\n";
      $html .= "    <td align=\"center\">MENU\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"modulo_list_oscuro\">\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['parametrizar_listadoproductos']."\" class=\"label_error\">LISTA DE PRODUCTOS</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      
      $html .= "</table>\n";
      $html .= "<br>\n";
      $html .= "<table align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
     
      
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
    $html .= "    xResizeTo(ele,".$tmn.", 600);\n";
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
     * Funcion donde se crea la forma para mostrar los productos realizar los documentos de salida de productos
     * 
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $$empresa_id vector que contiene los datos
     * @param array $datos vector que contiene los datos de las torres
     * @return string $html retorna la cadena con el codigo html de la pagina
     */ 
  function formaListaProductos($action,$empresa_id,$conteo,$pagina,$datos)
  {
    $html  = ThemeAbrirTabla('SALIDA DE PRODUCTOS');
    $mdl = AutoCarga::factory("ConsultasParamTorresP","","app","Inv_ParametrosIniciales");
    $pghtml = AutoCarga::factory('ClaseHTML');
    $html .= "<form name=\"formTorresProd\" id=\"formTorresProd\" method=\"post\" action=\"\">\n";
   
    $html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "         <a href=\"#\" onclick=\"xajax_GuardarTmp('".$datos."')\" class=\"label_error\">SALIDAS DE PRODUCTOS</a>\n";
    $reporte = new GetReports();
	  $mostrar = $reporte->GetJavaReport('app','SalidasProductos','ReporteSalidaProductos',
																							array("doc_id"=>$doc_id),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
	  $funcion = $reporte->GetJavaFunction();
    //print_r($_REQUEST);
    if($_REQUEST['prefijo']!= "")
    {
      $html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      //$html .= "         <a href=\"#\" onclick=\"xajax_GuardarTmp('".$datos."')\" class=\"label_error\">SALIDAS DE PRODUCTOS</a>\n";
      $reporte = new GetReports();
      $mostrar = $reporte->GetJavaReport('app','SalidasProductos','ReporteSalidaProductos',
																							array("doc_id"=>$_REQUEST['documento_id'],"numero"=>$_REQUEST['numero'],"prefijo"=>$_REQUEST['prefijo']),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
      $action['subir_imagen']= ModuloGetURL("app", "SalidasProductos", "controller", "Subirimagen",array("doc_id"=>$_REQUEST['documento_id'],"numero"=>$_REQUEST['numero'],"prefijo"=>$_REQUEST['prefijo']));                                         
      $funcion = $reporte->GetJavaFunction();
      $html .= "				".$mostrar."\n"; 
      $html .= "</table>\n";
        //$BorrarSal=$mdl->Borrarpara_docs($doc_id);
      $html .= "<table>";
      $html .= "  <tr>";
      $html .= " <td align=\"center\">";
      $html .= "	<a href=\"javascript:WindowPrinter0001()\" class=\"label_error\">GENERAR REPORTE DE SALIDA DE PRODUCTOS  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE SALIDA DE PRODUCTOS \"></sub>&nbsp;</a>\n";
      $html .= " </td>\n";
      $html .= "  </tr>";
      $html .= "  <tr>";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['subir_imagen']."\" class=\"label_error\">SUBIR IMAGEN</a>\n";
      $html .= "    </td>\n";
      $html .= "  <tr>";
      $html .= "</table>";
    }
    $html .= "				".$mostrar."\n";   
    $html .= "  <div class=\"label_error\" id=\"productos\"></div>"; 
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= " </table>\n";
    
    $html .= "<table align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
   
    $html .= "</form>\n";
    $html .= $this->CrearVentana(700,"MENSAJE");
    $html .= "<script>\n";
    $html .= "function paginador(a,offset)";
    $html .= "{";
    $html .= " xajax_ProductosListas(a,offset);";
    $html .= "}";
    $html .= "</script>\n";
   

    
    $html .= ThemeCerrarTabla();    
    return $html;
  }
  /**
     * Funcion donde se subi una imagen
     *
     * @return string $html retorna la cadena con el codigo html de la pagina
     */ 
  function FormaSubir($action,$tipood,$Noid)
  {       
		$html  = ThemeAbrirTabla(" SUBIR IMAGENES"); 
		$html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$action['imagensu']."\"  >";
		$html .= "<fieldset class=\"fieldset\">\n";
		$html .= "  <legend class=\"normal_30AN\" align=\"center\">S E L E C C I O N A R  -  I M A G E N  </legend>\n";
		$html .= "<table  width=\"70%\" align=\"center\" border=\"1\" >\n";
		$html .= "  <tr  class=\"modulo_list_claro\" align=\"center\" >\n";
		$html .= "      <td  colspan=\"10\" > <b>DOCUMENTO :   ".$tipood."-".$Noid." </b> </td>\n";
		$html .= "  <br>";
		$html .= "  </tr>\n";
		$html .= "</table>\n"; 
		$html .= " <br>";
		$html .= " <table width=\"100\" border=\"1\" align=\"center\"  >";
		$html .= "  <tr class=\"modulo_list_claro\" > ";
		$html .= "   <td class=\"modulo_list_claro\"  ><B>IMAGEN </B> ";
		$html .= "   </td>\n";
		$html .= "   <td>\n";
		$html .= "    <input type=\"file\" name=\"archivo\" size=\"30\" style=\"border: 1px solid #7F9DB7;\" >";
		$html .= "   </td>\n";
		$html .= "  </tr>  ";
		$html .= " </table>\n"; 
		$html .= " <table width=\"50\" border=\"0\" align=\"center\" class=\"modulo_list_title\" >";
		$html .= "	<tr>\n";
		$html .= "		<td align=\center\" >\n";
		$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"Guardar Imagen\">\n";
		$html .= "	  </td>\n";
		$html .= "		</tr>\n";  
		$html .= "</fieldset><br>\n";
		$html .= " </table>\n";
		$html .= "</form>";
		$html .= "<table align=\"center\" width=\"50%\">\n";
		$html .= "  <tr>\n";
		$html .= "    <td align=\"center\">\n";
		$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
		$html .= "        VOLVER\n";
		$html .= "      </a>\n";
		$html .= "    </td>\n";
		$html .= "  </tr>\n";
		$html .= "</table>\n";
		$html .= ThemeCerrarTabla();
		return $html;
  }
  
  /**
       * Funcion donde se crea la forma de mensaje
       *
       * @param array $action vector que contiene los link de la aplicacion
       * @param var $msg1 variable que contiene el mensaje
        * @param var $msg1 variable que contiene el mensaje
        * @param array $datos vector que contiene los datos
       * @return string $html retorna la cadena con el codigo html de la pagina
       */  
  function FormaMensajeIngresocartas($action, $msg1=null,$msg1=null,$datos)
	{
    $html  = ThemeAbrirTabla("INFORMACIÒN DEL PROCESO");
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">M E N S A J E </legend>\n";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
    $html .= "	<tr>\n";
    $html .= "		<td>\n";
    $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
    $html .= "		    <tr class=\"normal_10AN\">\n";
    $html .= "		      <td align=\"center\">\n".$msg1."</td>\n";
    $html .= "		    </tr>\n";
    $html .= "		  </table>\n";
    $html .= "		</td>\n";
    $html .= "	</tr>\n";
    $html .= "</table>";
    $html .= "<table align=\"center\" width=\"50%\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
    $html .= "        Volver\n";
    $html .= "      </a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    $html .= "</fieldset><br>\n";
    $html .= ThemeCerrarTabla();
    return $html;
  }   
}  
?>