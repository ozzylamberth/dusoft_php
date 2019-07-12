<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportesHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportesHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportesHTML(){}
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    function FormaBuscarProveedor($action,$request,$tipos_terceros,$lista,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      $tipos_productos = array();
      $tipos_productos['-1'] = "--SELECCIONAR--";
      $tipos_productos['I'] = "INSUMOS";
      $tipos_productos['M'] = "MEDICAMENTOS";
      
 			$html  = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('BUSCAR PROVEEDOR');
      $html .= "<form name=\"facturas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">TIPO ID PROVEDOR</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "						      <option value='-1'>--SELECCIONAR--</option>\n";
			foreach($tipos_terceros as $k => $dtl)
			{
				$sel = ($request['tipo_id_tercero'] == $dtl['tipo_id_tercero'])?  "selected": "";
				$html .= "						<option value='".$dtl['tipo_id_tercero']."' ".$sel.">".$dtl['descripcion']."</option>\n";
			}
			$html .= "				        </select>\n";
			$html .= "				      </td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">PROVEDOR ID</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" size=\"25\" maxlength=\"10\" value=\"".$request['tercero_id']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">NOMBRE PROVEEDOR</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" size=\"25\" maxlength=\"10\" value=\"".$request['nombre_tercero']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";

			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" width=\"25%\">TIPO PRODUCTO</td>\n";
			$html .= "				      <td colspan=\"2\">\n";
			$html .= "					      <select name=\"buscador[tipos_productos]\" class=\"select\">\n";
			
			foreach($tipos_productos as $key => $dtl)
			{
				$sel = ($request['tipos_productos'] == $key)? "selected": "";
				$html .= "						    <option value='".$key."' ".$sel.">".$dtl."</option>\n";
			}
			$html .= "					      </select>\n";
			$html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.facturas)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
      {
        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"15%\" >Nº CONTRATO</td>\n";
        $html .= "			<td width=\"45%\" colspan=\"2\">PROVEEDOR</td>\n";
        $html .= "			<td width=\"24%\" colspan=\"2\">VIGENCIA</td>\n";
        $html .= "			<td width=\"%\" >OPCIONES</td>\n";
        $html .= "		</tr>\n";
        
        $rpt  = new GetReports();
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
          
          $dtl['tipos_productos'] = $request['tipos_productos'];
          $dtl['usuario_id'] = $request['usuario_id'];
          $dtl['empresa_id'] = $request['empresa_id'];
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td>".$dtl['no_contrato']."</td>\n";
          $html .= "			<td width=\"12%\">".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']."</td>\n";
          $html .= "			<td >".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td align=\"center\" width=\"12%\">".$dtl['fecha_inicio']."</td>\n";
          $html .= "			<td align=\"center\" width=\"12%\">".$dtl['fecha_vencimiento']."</td>\n";
          $html .= "			<td align=\"center\" >\n";
     			$html .= $rpt->GetJavaReport('app','ReportesInventariosGral','proveedores',$dtl,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc  = $rpt->GetJavaFunction();

          $html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "			  </a>\n";
          $html .= "      </td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }    
    /**
    * Funcion donde se crea el $html de las factuars
    *
    * @return string
    */
    function FormaBuscarProveedorNoConforme($action,$request,$tipos_terceros,$lista,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
      $html .= ThemeAbrirTabla('BUSCAR PROVEEDOR');
      $html .= "<form name=\"facturas\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">TIPO ID PROVEDOR</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <select name=\"buscador[tipo_id_tercero]\" class=\"select\">\n";
			$html .= "						      <option value='-1'>--SELECCIONAR--</option>\n";
			foreach($tipos_terceros as $k => $dtl)
			{
				$sel = ($request['tipo_id_tercero'] == $dtl['tipo_id_tercero'])?  "selected": "";
				$html .= "						<option value='".$dtl['tipo_id_tercero']."' ".$sel.">".$dtl['descripcion']."</option>\n";
			}
			$html .= "				        </select>\n";
			$html .= "				      </td>\n";
      $html .= "			      </tr>\n";
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">PROVEDOR ID</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[tercero_id]\" size=\"25\" maxlength=\"10\" value=\"".$request['tercero_id']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";      
      $html .= "			      <tr>\n";
      $html .= "				      <td class=\"label\">NOMBRE PROVEEDOR</td>\n";
			$html .= "			        <td>\n";
			$html .= "				        <input type=\"text\" class=\"input-text\" name=\"buscador[nombre_tercero]\" size=\"25\" maxlength=\"10\" value=\"".$request['nombre_tercero']."\">\n";
			$html .= "			        </td>\n";
			$html .= "			      </tr>\n";
      /*$html .= "    <tr>\n";
      $html .= "      <td class=\"modulo_table_list_title\">FECHA REGISTRO</td>\n";
      $html .= "      <td>\n";
      $html .= "        <input type=\"text\" name=\"fecha_buscador\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_buscador']."\">\n";
      $html .= "      </td>\n";
 			$html .= "		  <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('buscador','fecha_buscador','/')."</td>\n";
      $html .= "    </tr>\n";*/
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.facturas)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";
			$html .= "		      </table>\n";
			$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
      {
        $html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
        $html .= "			<td width=\"55%\" colspan=\"2\">PROVEEDOR</td>\n";
        $html .= "			<td width=\"15%\" >NO CONFORMES</td>\n";
        $html .= "			<td width=\"12%\" >FECHA</td>\n";
        $html .= "			<td width=\"%\" >OPCIONES</td>\n";
        $html .= "		</tr>\n";
        
        $rpt  = new GetReports();
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
          
          $dtl['tipos_productos'] = $request['tipos_productos'];
          $dtl['usuario_id'] = $request['usuario_id'];
          $dtl['empresa_id'] = $request['empresa_id'];
          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td width=\"18%\">".$dtl['tipo_id_tercero']." ".$dtl['tercero_id']."</td>\n";
          $html .= "			<td >".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td >".$dtl['cantidad']." productos</td>\n";
          $html .= "			<td align=\"center\">".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td align=\"center\" >\n";
     			$html .= $rpt->GetJavaReport('app','ReportesInventariosGral','conformes',$dtl,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc  = $rpt->GetJavaFunction();

          $html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "			  </a>\n";
          $html .= "      </td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
    /**
    *
    * @return string
    */
    function FormaBuscarProductosMovimiento($action,$request,$lista,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
		$html .= ThemeAbrirTabla('BUSCAR PRODUCTOS');
		$html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
		$html .= "  <table width=\"60%\" align=\"center\">\n";
		$html .= "    <tr>\n";
		$html .= "      <td>\n";
		$html .= "	      <fieldset class=\"fieldset\">\n";
		$html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
		$html .= "		      <table width=\"100%\">\n";
		$html .= "            <tr>\n";
		$html .= "              <td class=\"normal_10AN\">FECHA ULTIMO MOVIMIENTO</td>\n";
		$html .= "              <td>\n";
		$html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
		$html .= "              </td>\n";
		$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
		$html .= "            </tr>\n";
		$html .= "			      <tr>\n";
		$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
		$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
		$html .= "				      </td>\n";
		$html .= "			      </tr>\n";
		$html .= "		      </table>\n";
		$html .= "	      </fieldset>\n";
		$html .= "	    </td>\n";
		$html .= "	  </tr>\n";
		$html .= "	</table>\n";
		$html .= "</form>\n";
      
      if(!empty($lista))
      {
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','movimientos',$request,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $html .= "<center>\n";
        $html .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "	  <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR REPORTE BUSQUEDA\n";
        $html .= "  </a>\n";
        $html .= "</center>\n";
        $html .= "<br>\n";
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"10%\">CODIGO</td>\n";
  			$html .= "			<td width=\"30%\">DESCRIPCION</td>\n";
  			$html .= "			<td width=\"15%\">MOLECULA</td>\n";
  			$html .= "			<td width=\"15%\">LABORATORIO</td>\n";
  			$html .= "			<td width=\"5%\" >TIPO</td>\n";
  			$html .= "			<td width=\"10%\">FECHA MOVIMIENTO</td>\n";
  			$html .= "			<td width=\"10%\">EXISTENCIA ACTUAL</td>\n";
        $html .= "		</tr>\n";

        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

          $tipo_producto = "";
          if($dtl['sw_insumos'] == '1')
            $tipo_producto = "INS";
          else if($dtl['sw_medicamento'] == '1')
            $tipo_producto = "MED";

			$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
			$html .= "			<td >".$dtl['codigo_producto']."</td>\n";
			$html .= "			<td >".($ctl->NombreProducto($dtl,$this->datos['empresa_id']))."</td>\n";
			$html .= "			<td >".$dtl['molecula']."</td>\n";
			$html .= "			<td >".$dtl['laboratorio']."</td>\n";
			$html .= "			<td align=\"center\" class=\"label\">".$tipo_producto."</td>\n";
			$html .= "			<td >".$dtl['fecha_movimiento']."</td>\n";
			$html .= "			<td align=\"right\">".$dtl['existencia']."</td>\n";
			$html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
		$html .= "		<br>\n";
		$pgn = AutoCarga::factory("ClaseHTML");
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
		}
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
    /**
    *
    * @return string
    */
    function FormaBuscarProductosVencimiento($action,$request,$lista,$conteo, $pagina,$dias_vence,$colores)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
      $html .= ThemeAbrirTabla('BUSCAR PRODUCTOS PROXIMOS A VENCER');
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      //$html .= "	      <fieldset class=\"fieldset\">\n";
      //$html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\">\n";
      $html .= "                <fieldset class=\"fieldset\" style=\"width:98%\">\n";
      $html .= "                  <legend class=\"normal_10AN\">\n";
      $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">NOTA\n";
      $html .= "                  </legend>\n";
      $html .= "                  <center>\n";
      $html .= "                    <label class=\"normal_10AN\">DÍAS VENCIMIENTO PARAMETRIZADO: ".$dias_vence." días</label>\n";
      $html .= "                  </center>\n";
      $html .= "                </fieldset><br>\n";
 			//$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Refrescar\">\n";
      $html .= "              </td>\n";
      $html .= "            </tr>\n";
      /*$html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA INICIAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_inicio']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_inicio','/',1)."</td>\n";
      $html .= "            </tr>\n";
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">FECHA FINAL</td>\n";
      $html .= "              <td>\n";
      $html .= "                <input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['fecha_final']."\">\n";
      $html .= "              </td>\n";
 			$html .= "		          <td align=\"left\" class=\"label\" >".ReturnOpenCalendario('productos','fecha_final','/',1)."</td>\n";
      $html .= "            </tr>\n";
			$html .= "			      <tr>\n";
			$html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
			$html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
   		$html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
      $html .= "				      </td>\n";
			$html .= "			      </tr>\n";*/
			$html .= "		      </table>\n";
			//$html .= "	      </fieldset>\n";
			$html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";
      
      if(!empty($lista))
      {
        $fecha = $ctl->sumaDia(date("d/m/Y"),$dias_vence,"/");
        $request['dias_vencimiento'] = $dias_vence;
        $request['fecha_proxima_vencimiento'] = $fecha;
        $request['colores'] = $colores;
        
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ReportesInventariosGral','vencimientos',$request,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $html .= "<center>\n";
        $html .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "	  <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR REPORTE\n";
        $html .= "  </a>\n";
        $html .= "</center>\n";
        $html .= "<br>\n";
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"label\" >\n";
  			$html .= "			<td width=\"25%\" style=\"background:".$colores['VN']."\" >&nbsp;</td>\n";
  			$html .= "			<td width=\"25%\" >VENCIDO</td>\n";
  			$html .= "			<td width=\"25%\" style=\"background:".$colores['PV']."\">&nbsp;</td>\n";
  			$html .= "			<td width=\"25%\" >EN PERIODO DE VENCIMIENTO</td>\n";
  			$html .= "		</tr>\n";
  			$html .= "  </table>\n";
  			$html .= "  <br>\n";
        
        $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"10%\">CODIGO</td>\n";
  			$html .= "			<td width=\"30%\">DESCRIPCION</td>\n";
  			/*$html .= "			<td width=\"15%\">MOLECULA</td>\n";
  			$html .= "			<td width=\"15%\">LABORATORIO</td>\n";
  			$html .= "			<td width=\"5%\" >TIPO</td>\n";*/
  			$html .= "			<td width=\"10%\">FECHA VENCIMIENTO</td>\n";
  			$html .= "			<td width=\"10%\">LOTE</td>\n";
  			$html .= "			<td width=\"5%\">EXISTENCIA</td>\n";
        $html .= "		</tr>\n";
        
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

          $tipo_producto = "";
          if($dtl['sw_insumos'] == '1')
            $tipo_producto = "INS";
          else if($dtl['sw_medicamento'] == '1')
            $tipo_producto = "MED";
          
          $color = "";
          if($ctl->CompararFechas($dtl['fecha_vencimiento'],date("d/m/Y")) < 0)
            $color = "VN";
          else if($ctl->CompararFechas($dtl['fecha_vencimiento'],$fecha) < 0)
            $color = "PV";
          
          $clase = "class=\"".$est."\"";
          if($color != "")
          {
            $bck = $colores[$color];
            $clase = "style=\"background:".$colores[$color]."\" ";
          }
          
          $html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				$html .= "			<td >".$dtl['codigo_producto']."</td>\n";
  				$html .= "			<td >".$dtl['descripcion']."</td>\n";
  				/*$html .= "			<td >".($ctl->NombreProducto($dtl,$this->datos['empresa_id']))."</td>\n";
  				$html .= "			<td >".$dtl['molecula']."</td>\n";
  				$html .= "			<td >".$dtl['laboratorio']."</td>\n";
  				$html .= "			<td align=\"center\" class=\"label\">".$tipo_producto."</td>\n";*/
  				$html .= "			<td >".$dtl['fecha_vencimiento']."</td>\n";
  				$html .= "			<td >".$dtl['lote']."</td>\n";
  				$html .= "			<td align=\"right\">".$dtl['existencia']."</td>\n";
          $html .= "		</tr>\n";
        }
        $html .= "		</table>\n";
				$html .= "		<br>\n";
        $pgn = AutoCarga::factory("ClaseHTML");
				$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
	
	
	   /**
    *
    * @return string
    */
    function FormaSelectivo($action,$request)
    {
		$ctl = AutoCarga::factory("ClaseUtil");

		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('PRODUCTOS CONTEO - DIARIO');
		$rpt  = new GetReports();
		$html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_selectivo',$request,
		array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$fnc  = $rpt->GetJavaFunction();
		
		if($request['selectivo']=="")
		$checked_total = " checked ";
		
		$html .= "<center>";
		$html .= "	<fieldset style=\"width:70%;text-align:center\">";
		$html .= "		<legend class=\"normal_10AN\">PARAMETROS PARA EL CONTEO DIARIO</legend>";
		$html .= "			<form name=\"FomaParametros\" id=\"FomaParametros\" method=\"POST\" action=\"".$action['buscar']."\">";
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr>";
		$html .= "						<td>";
		$html .= "							CANTIDAD PRODUCTOS A CONTAR";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<input type=\"text\" name=\"forma[cantidad_conteo]\" id=\"cantidad_conteo\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['forma']['cantidad_conteo']."\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td>";
		$html .= "							SELECCION";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "								<tr>";
		$html .= "									<td width=\"90%\">";
		$html .= "										RANDOM: MAYOR ROTACION <label class=\"normal_10AN\">(Son Necesarias Las Fechas)</label>";
		$html .= "									</td>";
		$html .= "									<td>";
		$checked = "";
		if($request['forma']['opcion']=='1')
		$checked = " checked ";
		$html .= "										<input $checked type=\"radio\" class=\"input-radio\" name=\"forma[opcion]\" id=\"opcion\" value=\"1\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td colspan=\"2\">";
		$html .= "										<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "            								<tr>\n";
		$html .= "              								<td class=\"normal_10AN\">FECHA I</td>\n";
		$html .= "              								<td>\n";
		$html .= "                									<input type=\"text\" name=\"forma[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['forma']['fecha_inicio']."\" style=\"width:60%\">\n";
		$html .= "              								</td>\n";
		$html .= "		          								<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('FomaParametros','fecha_inicio','/',1)."</td>\n";
		$html .= "            								</tr>\n";
		$html .= "            								<tr>\n";
		$html .= "              								<td class=\"normal_10AN\">FECHA F</td>\n";
		$html .= "              								<td>\n";
		$html .= "                									<input type=\"text\" name=\"forma[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['forma']['fecha_final']."\" style=\"width:60%\">\n";
		$html .= "              								</td>\n";
		$html .= "		          								<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('FomaParametros','fecha_final','/',1)."</td>\n";
		$html .= "            								</tr>\n";
		$html .= "										</table>";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "										RANDOM: MAYOR COSTO";
		$html .= "									</td>";
		$html .= "									<td>";
		$checked = "";
		if($request['forma']['opcion']=='2')
		$checked = " checked ";
		$html .= "										<input $checked type=\"radio\" class=\"input-radio\" name=\"forma[opcion]\" id=\"opcion\" value=\"2\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "										RANDOM: PRODUCTOS";
		$html .= "									</td>";
		$html .= "									<td>";
		$checked = "";
		if($request['forma']['opcion']=='3')
		$checked = " checked ";
		$html .= "										<input $checked type=\"radio\" class=\"input-radio\" name=\"forma[opcion]\" id=\"opcion\" value=\"3\" $checked_total>";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "							</table>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td>";
		$html .= "							TIPO PRODUCTO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
		$html .= "								<tr>";
		$html .= "									<td width=\"90%\">";
		$html .= "										MEDICAMENTOS";
		$html .= "									</td>";
		$html .= "									<td>";
		$checked = "";
		if($request['forma']['tipo_producto']=="1")
		$checked = " checked ";
		$html .= "										<input $checked type=\"radio\" class=\"input-radio\" name=\"forma[tipo_producto]\" id=\"tipo_producto\" value=\"1\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "										INSUMOS";
		$html .= "									</td>";
		$html .= "									<td>";
		$checked = "";
		if($request['forma']['tipo_producto']=="0")
		$checked = " checked ";
		$html .= "										<input $checked type=\"radio\" class=\"input-radio\" name=\"forma[tipo_producto]\" id=\"tipo_producto\" value=\"0\">";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr>";
		$html .= "									<td>";
		$html .= "										TODOS";
		$html .= "									</td>";
		$html .= "									<td>";
		$checked = "";
		if($request['forma']['tipo_producto']=="1,0")
		$checked = " checked ";
		$html .= "										<input $checked type=\"radio\" class=\"input-radio\" name=\"forma[tipo_producto]\" id=\"tipo_producto\" value=\"1,0\" $checked_total>";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "							</table>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td align=\"center\" colspan=\"2\">";
		$html .= "							<input type=\"hidden\" name=\"selectivo\" id=\"selectivo\" value=\"1\">";
		$html .= "							<input type=\"submit\" class=\"input-submit\" value=\"GENERAR LISTADO - SELECTIVO\">";
		$html .= "							<input type=\"reset\" class=\"input-submit\" value=\"LIMPIAR FORMULARIO\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td align=\"center\" colspan=\"2\">";
		$html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "								<tr align=\"center\">";
		$html .= "									<td class=\"label_error\">";
		if($request['selectivo']=='1' && $request['forma']['cantidad_conteo']>0)
		{
		$html .= "			  								IMPRIMIR - REPORTE SELECTIVO: ";
		$html .= "											<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
		$html .= "			    							<image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
		$html .= "			  								</a>\n";
		}
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "							</table>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "				</table>";
		$html .= "			</form>";
		$html .= "	</fieldset>";
		$html .= "</center>";
		
		
		$html .= "	<table width=\"90%\" align=\"center\">\n";
		$html .= "		<tr><td align=\"center\">\n";
		$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
		$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
		$html .= "			</form>\n";
		$html .= "		</td></tr>\n";
		$html .= "	</table>\n";
		$html .= ThemeCerrarTabla();

		return $html;
    }	
	   /**
    *
    * @return string
    */
    function FormaDespachosIngresados($action,$request,$prefijos,$datos,$conteo, $pagina)
    {
		$ctl = AutoCarga::factory("ClaseUtil");
		$pgn = AutoCarga::factory("ClaseHTML");
		$html  = $ctl->LimpiarCampos();
		$html .= $ctl->RollOverFilas();
		$html .= $ctl->AcceptDate('/');
		$html .= $ctl->AcceptNum(false);
		$html .= ThemeAbrirTabla('DESPACHOS A FARMACIA Y DOCS. DE INGRESOS');
		$rpt  = new GetReports();
		$html .= $rpt->GetJavaReport('app','ReportesInventariosGral','reporte_despachos_ingresos',$request,
		array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
		$fnc  = $rpt->GetJavaFunction();
		
		if($request['selectivo']=="")
		$checked_total = " checked ";
		
		$select = "<select name=\"buscador[prefijo]\" id=\"prefijo\" class=\"select\" style=\"10%\">";
		$select .= "<option value=\"\">";
		$select .= "-- TODOS --";
		$select .= "</option>";
		foreach($prefijos as $llave => $value)
		{
		$select .= "	<option value=\"".$value['prefijo']."\">";
		$select .= "		".$value['prefijo'];
		$select .= "	</option>";
		}
		$select .= "</select>";
		
		
		$html .= "<center>";
		$html .= "	<fieldset style=\"width:70%;text-align:center\">";
		$html .= "		<legend class=\"normal_10AN\">BUSCADOR</legend>";
		$html .= "			<form name=\"FomaParametros\" id=\"FomaParametros\" method=\"POST\" action=\"".$action['buscar']."\">";
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr>";
		$html .= "						<td class=\"normal_10AN\">";
		$html .= "							FARMACIA";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<input type=\"text\" name=\"buscador[farmacia]\" id=\"farmacia\" class=\"input-text\" style=\"width:100%\" value=\"".$request['buscador']['farmacia']."\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td class=\"normal_10AN\">";
		$html .= "							NUMERO PEDIDO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<input type=\"text\" name=\"buscador[solicitud_prod_a_bod_ppal_id]\" id=\"solicitud_prod_a_bod_ppal_id\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\" value=\"".$request['buscador']['solicitud_prod_a_bod_ppal_id']."\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td class=\"normal_10AN\">";
		$html .= "							DOC. DESPACHO";
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							".$select;
		$html .= "							<input type=\"text\" name=\"buscador[numero]\" id=\"numero\" style=\"width:40%\" class=\"input-text\" value=\"".$request['buscador']['numero']."\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td colspan=\"2\">";
		$html .= "		      				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" >\n";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA INICIO - INGRESOS</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_inicio]\" id=\"fecha_inicio\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_inicio']."\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('FomaParametros','fecha_inicio','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "            					<tr>\n";
		$html .= "              					<td class=\"normal_10AN\">FECHA FINAL - INGRESOS</td>\n";
		$html .= "              					<td>\n";
		$html .= "                						<input type=\"text\" name=\"buscador[fecha_final]\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$request['buscador']['fecha_final']."\">\n";
		$html .= "              					</td>\n";
		$html .= "		          					<td align=\"left\" class=\"label\" >".ReturnOpenCalendario('FomaParametros','fecha_final','/',1)."</td>\n";
		$html .= "            					</tr>\n";
		$html .= "		      				</table>\n";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "					<tr>";
		$html .= "						<td colspan=\"2\" align=\"center\">";
		$html .= "							<input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "						<td align=\"center\" colspan=\"2\">";
		$html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "								<tr align=\"center\">";
		$html .= "									<td class=\"label_error\">";
		if(!empty($datos))
		{
		$html .= "			  								IMPRIMIR - REPORTE: ";
		$html .= "											<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
		$html .= "			    							<image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
		$html .= "			  								</a>\n";
		}
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "							</table>";
		$html .= "						</td>";
		$html .= "					</tr>";
		$html .= "				</table>";
		$html .= "			</form>";
		$html .= "	</fieldset>";
		$html .= "</center>";
		
		$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
		$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "					<tr class=\"formulacion_table_list\">";
		$html .= "						<td width=\"5%\">";
		$html .= "							#PED";
		$html .= "						</td>";
		$html .= "						<td  width=\"45%\">";
		$html .= "							FARMACIA";
		$html .= "						</td>";
		$html .= "						<td  width=\"50%\">";
		$html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "								<tr class=\"modulo_table_list_title\">";
		$html .= "									<td  width=\"30%\" rowspan=\"2\">";
		$html .= "										DESPACHO";
		$html .= "									</td>";
		$html .= "									<td  width=\"70%\">";
		$html .= "										INGRESO";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "								<tr class=\"modulo_table_list_title\">";
		/*$html .= "									<td class=\"formulacion_table_list\">";
		$html .= "									</td>";*/
		$html .= "									<td>";
		$html .= "										<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "											<tr class=\"formulacion_table_list\">";
		$html .= "												<td width=\"33.33%\">";
		$html .= "														DOC.";
		$html .= "												</td>";
		$html .= "												<td width=\"33.33%\">";
		$html .= "														FECHA";
		$html .= "												</td>";
		$html .= "												<td width=\"33.33%\">";
		$html .= "														USUARIO";
		$html .= "												</td>";
		$html .= "											</tr>";
		$html .= "										</table>";
		$html .= "									</td>";
		$html .= "								</tr>";
		$html .= "							</table>";
		$html .= "						</td>";
		$html .= "					</tr>";
		foreach($datos as $key => $dtl)
			{    
		$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
		$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
        $html .= "  				<tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
		$html .= "						<td>";
		$html .= "								".$key;
		$html .= "						</td>";
		foreach($dtl as $k => $d)
				{
		$html .= "						<td>";
		$html .= "								".$k;
		$html .= "						</td>";
		$html .= "						<td>";
		$html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
		foreach($d as $k1 => $d1)
					{
		$html .= "								<tr>";
		$html .= "									<td width=\"30%\">";
		$html .= "										".$k1;
		$html .= "									</td>";
		$html .= "									<td  width=\"70%\">";
		$html .= "										<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
		foreach($d1 as $k2 => $d2)
						{
		$html .= "											<tr>";
		$html .= "												<td>";
		$html .= "													<table table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
		$html .= "														<tr>";
		$html .= "															<td width=\"33.33%\">";
		$html .= "																".$d2['documento_ingreso'];
		$html .= "															</td>";
		$html .= "															<td width=\"33.33%\">";
		$html .= "																".$d2['fecha_ingreso'];
		$html .= "															</td>";
		$html .= "															<td width=\"33.33%\">";
		$html .= "																	".$d2['usuario_id']."-".$d2['nombre'];
		$html .= "															</td>";
		$html .= "														</tr>";
		$html .= "													</table>";
		$html .= "												</td>";
		$html .= "											</tr>";
						}
		$html .= "										</table>";
		$html .= "									</td>";
		$html .= "								</tr>";
					}
		$html .= "							</table>";
		$html .= "						</td>";
				}
		$html .= "					</tr>";
			}
		
		$html .= "				</table>";
		
		
		$html .= "	<table width=\"90%\" align=\"center\">\n";
		$html .= "		<tr><td align=\"center\">\n";
		$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
		$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
		$html .= "			</form>\n";
		$html .= "		</td></tr>\n";
		$html .= "	</table>\n";
		$html .= ThemeCerrarTabla();

		return $html;
    }
  }
?>