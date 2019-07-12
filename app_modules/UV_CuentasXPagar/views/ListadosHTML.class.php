<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListadosHTML.class.php,v 1.1 2009/01/14 22:22:50 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReintegrosHTML
  * Clase en la que se crean las formas para el modulo de cuentas por pagar
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class ListadosHTML
  {
    /**
    * Constructosr de la clase
    */
    function ListadosHTML(){}
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param string $funcion Funcion a la que se llama cuando se hace submit sobre la forma
    * @param int $tmn Tamaño que tendra la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 350)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 5;\n";
			$html .= "	function OcultarSpan()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function OcultarSpanGrande()\n";
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
 			$html .= "			e = xGetElementById('ContenedorP');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
			$html .= "	function MostrarSpanGrande()\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
      $html .= "			e = xGetElementById('Contenedor');\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  IniciarGrande();\n";
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
			$html .= "		contenedor = 'ContenedorP';\n";
			$html .= "		titulo = 'tituloP';\n";
      $html .= "		xGetElementById('error_p').innerHTNL = '';\n";
      $html .= "		ele = xGetElementById('ContenidoP');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrarP');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";

      $html .= "	function IniciarGrande()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
			$html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,800, 380);\n";			
      $html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,800, 'auto');\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,780, 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,780, 0);\n";
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
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpanGrande()\" title=\"Cerrar\" style=\"font-size:9px;\"><img src=\"".GetThemePath()."/images/cerrarnuevo.png\" border=\"0\" ></a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
 			$html .= "    <form name=\"formabuscar\" id=\"formabuscar\" method=\"post\">\n";
			$html .= "	    <div id=\"capa_buscador\"></div>\n";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";
			$html .= "</div>\n";
			$html .= "<div id='ContenedorP' class='d2Container' style=\"display:none;z-index:5\">\n";
			$html .= "	<div id='tituloP' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrarP' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\"><img src=\"".GetThemePath()."/images/cerrarnuevo.png\" border=\"0\" ></a></div><br><br>\n";
			$html .= "	<div id='ContenidoP' class='d2Content'>\n";
			$html .= "	  <form name=\"oculta\" id=\"oculta\" method=\"post\">\n";
 			$html .= "		  <div id=\"ventana\"></div>\n";
			$html .= "	    <div id='error_p' style=\"text-align:center\" class=\"label_error\"></div>\n";
			$html .= "	  </form>\n";
			$html .= "	</div>\n";			
      $html .= "</div>\n";
			return $html;
		}
    /**
    * Funcion donde se crea la forma que muestra el buscador y la lista de los afiliados
    *
    * @return String $html
    */
    function FormaListadoRadicaciones($action,$tiposdocumentos,$request,$lista,$pagina,$conteo,$msgError)
    {
      //echo "<pre>".print_r($request,true)."</pre>";
      $ctl = AutoCarga::factory("ClaseUtil"); 
      $html  = $ctl->AcceptNum();
      $html .= $ctl->AcceptDate("/");
      $html .= $ctl->LimpiarCampos();
      $html .= $ctl->IsNumeric();
      $html .= $ctl->RollOverFilas();
      $html .= $ctl->TrimScript();
      $html .= ThemeAbrirTabla('MANEJO DE RADICACION DE FACTURAS');
			$html .= "	<center>\n";
			$html .= "		<fieldset style=\"width:80%\" class=\"fieldset\"><legend class=\"label\">CRITERIOS DE BUSQUEDA</legend>\n";
 			$html .= "    <form name=\"formabuscar\" id=\"formabuscar\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "    	<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$html .= "        <tr>\n";
			$html .= "        	<td width=\"25%\" class=\"normal_10AN\">NUMERO RADICACION:</td>\n";
			$html .= "          <td colspan=\"3\">\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"cxp_radicacion_id\" style=\"width:25%\" onclick=\"return acceptNum(event)\" value=\"".$request['cxp_radicacion_id']."\">\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
      $html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\" width=\"18%\">TIPO DOCUMENTO: </td>\n";
			$html .= "          <td >\n";
			$html .= "          	<select name=\"tipo_id_tercero\" class=\"select\">\n";
			$html .= "            	<option value=\"-1\">-------SELECCIONE-------</option>";
			$slt = "";
			foreach($tiposdocumentos as $key => $ids)
			{
				($request['tipo_id_tercero'] == $ids['tipo_id_tercero'])? $slt= "selected":$slt = "";
				$html .= "            	<option value=\"".$ids['tipo_id_tercero']."\" $slt>".$ids['descripcion']."</option>";
			}
			$html .= "            </select>\n";
			$html .= "          </td>\n";
			$html .= "          <td width=\"18%\" class=\"normal_10AN\">DOCUMENTO: </td>\n";
			$html .= "          <td>\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"tercero_id\" maxlength=\"32\" value=\"".$request['tercero_id']."\">\n";
			$html .= "          </td>\n";
			$html .= "				</tr>\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\">NOMBRE TERCERO:</td>\n";
			$html .= "          <td colspan=\"3\">\n";
			$html .= "          	<input type=\"text\" class=\"input-text\" name=\"nombre_tercero\" style=\"width:94%\" maxlength=\"64\" value=\"".$request['nombre_tercero']."\">\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "        <tr>\n";
			$html .= "        	<td class=\"normal_10AN\">FECHA RADICACION</td>\n";
      $html .= "          <td >\n";
      $html .= "            <input size=\"12\" type=\"text\" name=\"fecha_radicacion\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_radicacion']."\">\n";
      $html .= "            ".ReturnOpenCalendario('formabuscar','fecha_radicacion','/')."\n";
      $html .= "          </td>\n";			
      $html .= "        	<td class=\"normal_10AN\">FECHA REGISTRO</td>\n";
      $html .= "          <td >\n";
      $html .= "            <input size=\"12\" type=\"text\" name=\"fecha_registro\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_registro']."\" >\n";
      $html .= "            ".ReturnOpenCalendario('formabuscar','fecha_registro','/')."\n";
      $html .= "          </td>\n";
			$html .= "        <tr>\n";
			$html .= "         	<td colspan = '4' align=\"center\" >\n";
			$html .= "          	<table width=\"70%\">\n";
			$html .= "             	<tr align=\"center\">\n";
			$html .= "               	<td >\n";
			$html .= "                 	<input class=\"input-submit\" type=\"submit\" name=\"buscar\" value=\"Buscar\">\n";
			$html .= "                </td>\n";
			$html .= "                <td>\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "                </td>\n";
			$html .= "            	</tr>\n";
			$html .= "           	</table>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "    	</table>\n";
			$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
			$html .= "		  </form>\n";
			$html .= "		</fieldset>\n";
			$html .= "	</center><br>\n";
      
      if(!empty($lista))
      {        
        $pghtml = AutoCarga::factory("ClaseHTML");

        $csv = Autocarga::factory("ReportesCsv");
        $html .= $csv->GetJavacriptReporte('app','UV_CuentasXPagar','Listados',$request,'tabs');
				$fncn  = $csv->GetJavaFunction();
				$html .= "	<center>\n";
				$html .= "	  <a href=\"javascript:".$fncn."\" class=\"label_error\">\n";
				$html .= "  	  <img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE CSV\">REPORTE CSV\n";
				$html .= "	  </a>\n";
				$html .= "	</center><br>\n";
        
        $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "	  <tr class=\"formulacion_table_list\">\n";
				$html .= "			<td width=\"7%\">Nº RADIC.</td>\n";
				$html .= "			<td width=\"8%\">F. RADIC.</td>\n";
				$html .= "			<td width=\"8%\">F. REGIS.</td>\n";
				$html .= "			<td width=\"16%\" colspan=\"2\">PERIODO COBERTURA</td>\n";
				$html .= "		  <td width=\"28%\">TERCERO</td>\n";
				$html .= "		  <td width=\"%\">OBSERVACION</td>\n";
				$html .= "			<td width=\"5%\">CANT.</td>\n";
				$html .= "			<td width=\"1%\">OP</td>\n";
				$html .= "		</tr>\n";

        $est = "modulo_list_oscuro";
        $bck = "#CCCCCC";
				foreach($lista as $key => $dtl)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          
					$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "		  <td >".$dtl['cxp_radicacion_id']."</td>\n";
					$html .= "		  <td align=\"center\">".$dtl['fecha_radicacion']."</td>\n";
					$html .= "		  <td align=\"center\">".$dtl['fecha_registro']."</td>\n";
					$html .= "		  <td width=\"8%\" align=\"center\">".$dtl['fecha_inicial']."</td>\n";
					$html .= "		  <td width=\"8%\" align=\"center\">".$dtl['fecha_final']."</td>\n";
					
          if($dtl['tipo_id_tercero'])
          {
            $html .= "		  <td ><b>".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']."</b> ".$dtl['nombre_tercero']."</td>\n";
					}
          else
            $html .= "		  <td >".$dtl['descripcion_tercero_asociado']."</td>\n";
          
          $html .= "		  <td >".$dtl['observacion']."</td>\n";
					$html .= "		  <td align=\"right\">".$dtl['numero_cuentas']."</td>\n";
          $html .= "		  <td align=\"center\">\n";
          if($dtl['sw_rips'] == '1' && $dtl['numero_cuentas'] == 0)
          {
            $rq = array();
            $rq['radicacion_id'] = $dtl['cxp_radicacion_id'];
            $rq['numero_digitos'] = $dtl['digitos_prefijo'];
            $rq['tipo_cuenta'] = $dtl['tipo_cxp'];
            
            $html .= "	      <a class=\"label_error\" title=\"SUBIR RIPS\" href=\"".$action['rips'].URLRequest($rq)."\">\n";
            $html .= "		      <img src=\"".GetThemePath()."/images/arriba.png\" border=\"0\">\n";
            $html .= "	      </a>\n";
          }
          $html .= "		  </td>\n";
          $html .= "		</tr>\n";
				}
				$html .= "		</table>\n";
        
        $html .= "		".$pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
      }
      else if($request['buscar'])
      {
        $html .= "		<center>\n";
        $html .= "		  <label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
        $html .= "		</center>\n";
      }
 			$html .= "<form name=\"form_volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\"  name=\"volver\" value=\"Volver\">\n";
			$html .= "		  </td>\n";
			$html .= "	  </tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
      $html .= ThemeCerrarTabla();	
      return $html;
    }
    /**
    * Funcion 
    *
		*/
		function FormaBuscarTerceros($action,$request,$tipos_terceros,$terceros = array(),$pagina,$conteo,$msgError)
		{
			$html  = ThemeAbrirTabla("TERCEROS","98%");
			$html .= "	<fieldset class=\"fieldset\"><legend class=\"field\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		<table width=\"80%\" align=\"center\">\n";
			$html .= "			<tr>\n";
      $html .= "        <td class=\"normal_10AN\" width=\"35%\">TIPO DOCUMENTO CLIENTE</td>\n";
			$html .= "				<td>\n";
			$html .= "					<select name=\"buscadortercero[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "						<option value='-1'>-----SELECCIONAR-----</option>\n";
      $sel = "";
			foreach($tipos_terceros as $key => $dtl)
			{
				($dtl['tipo_id_tercero'] == $request['tipo_id_tercero'])? $sel = "selected": $sel = "";
			
				$html .= "						<option value='".$dtl['tipo_id_tercero']."' $sel>".ucwords(strtolower($dtl['descripcion']))."</option>\n";
			}
			
			$html .= "					</select>\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";	
			$html .= "			<tr>\n";
			$html .= "				<td class=\"normal_10AN\">DOCUMENTO</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"buscadortercero[tercero_id]\" size=\"30\" maxlength=\"32\" value=\"".$this->TerceroDocumento."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
			$html .= "			<tr>\n";
			$html .= "				<td class=\"normal_10AN\">NOMBRE</td>\n";
			$html .= "				<td>\n";
			$html .= "					<input type=\"text\" class=\"input-text\" name=\"buscadortercero[nombre_tercero]\" size=\"30\" maxlength=\"100\" value=\"".$this->TerceroNombre."\">\n";
			$html .= "				</td>\n";
			$html .= "			</tr>\n";
      $html .= "        <tr>\n";
			$html .= "         	<td colspan = '2' align=\"center\" >\n";
			$html .= "          	<table width=\"70%\">\n";
			$html .= "             	<tr align=\"center\">\n";
			$html .= "               	<td >\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"".$action['buscar']."\" name=\"Buscar\" value=\"Buscar\">\n";
			$html .= "                </td>\n";
			$html .= "                <td>\n";
			$html .= "                 	<input class=\"input-submit\" type=\"button\" onclick=\"LimpiarCampos(document.formabuscar)\" name=\"Limpiar\" value=\"Limpiar Campos\">\n";
			$html .= "                </td>\n";
			$html .= "            	</tr>\n";
			$html .= "           	</table>\n";
			$html .= "          </td>\n";
			$html .= "        </tr>\n";
			$html .= "    	</table>\n";
			$html .= "      <div class=\"label_error\">".$msgError."</div>\n";
			$html .= "	</fieldset>\n";
			
			if(sizeof($terceros) > 0)
			{
        $bck = "#DDDDDD";
        $est = "modulo_list_claro";
        
        $pghtml = AutoCarga::factory("ClaseHTML");
        $html .= $pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);        

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"modulo_table_list_title\">\n";
        $html .= "    <td colspan=\"2\" width=\"60%\">TERCERO</td>\n";
        $html .= "    <td width=\"20%\">DIRECCION</td>\n";
        $html .= "    <td width=\"16%\">TELEFONO</td>\n";
        $html .= "    <td>OP</td>\n";
        $html .= "  </tr>\n";
				foreach($terceros as $key => $dtl)
				{
					($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro'; 
					($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	
					$html .= "  <tr class=\"".$est."\" height=\"21\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
          $html .= "    <td width=\"20%\">".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']." </td>\n";
          $html .= "    <td>".$dtl['nombre_tercero']."</td>\n";
          $html .= "    <td>".$dtl['direccion']."</td>\n";
          $html .= "    <td>".$dtl['telefono']."</td>\n";
          $html .= "    <td>\n";
          $html .= "	    <a class=\"label_error\" title=\"SELECCIONAR\" href=\"#\" onclick=\"return xajax_AsignarTercero('".$dtl['nombre_tercero']."','".$dtl['codigo_proveedor_id']."')\">\n";
					$html .= "		    <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
					$html .= "	    </a>\n";
          $html .= "		</td>\n";
					$html .= "	</tr>\n";
				}
				$html .= "</table><br>\n";
									
        $html .= "		".$pghtml->ObtenerPaginadoXajax($conteo,$pagina,$action['paginador']);
    		$html .= "		<br>\n";
			}
		
			$html .= "	<table width=\"60%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"".$action['cerrar']."\" >\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
  }  
?>