<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase Vista: ReportesHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
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
     			$html .= $rpt->GetJavaReport('app','ESM_PlaneacionRequerimientos','proveedores',$dtl,
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
     			$html .= $rpt->GetJavaReport('app','ESM_PlaneacionRequerimientos','conformes',$dtl,
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
        $html .= $rpt->GetJavaReport('app','ESM_PlaneacionRequerimientos','movimientos',$request,
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
        $html .= $rpt->GetJavaReport('app','ESM_PlaneacionRequerimientos','vencimientos',$request,
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
  			$html .= "			<td width=\"15%\">MOLECULA</td>\n";
  			$html .= "			<td width=\"15%\">LABORATORIO</td>\n";
  			$html .= "			<td width=\"5%\" >TIPO</td>\n";
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
  				$html .= "			<td >".($ctl->NombreProducto($dtl,$this->datos['empresa_id']))."</td>\n";
  				$html .= "			<td >".$dtl['molecula']."</td>\n";
  				$html .= "			<td >".$dtl['laboratorio']."</td>\n";
  				$html .= "			<td align=\"center\" class=\"label\">".$tipo_producto."</td>\n";
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
  }
?>