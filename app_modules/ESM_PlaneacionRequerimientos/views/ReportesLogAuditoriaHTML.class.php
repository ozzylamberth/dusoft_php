<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: ReportesLogAuditoriaHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: ReportesLogAuditoriaHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class ReportesLogAuditoriaHTML	
	{
    /**
    * Constructor de la clase
    */
    function ReportesLogAuditoriaHTML(){}
    /**
    *
    * @return string
    */
    function Forma($action,$request,$lista,$esm_empresas,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      //print_r($_REQUEST);
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptNum(false);
      $html .= ThemeAbrirTabla('PLANEACION DE REQUERIMIENTOS - DISTRIBUCION/SUMINISTRO');
      $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      
      $html .= "            <tr>\n";
      $html .= "              <td class=\"normal_10AN\">ESM</td>\n";
      $html .= "              <td colspan=\"2\">\n";
      $html .= "                <select name=\"buscador[esm_empresa]\" id=\"esm_empresa\" class=\"select\" style=\"width:80%\">\n";
      $html .= "              <option value=\"\">--- TODOS ---</option>";
                      foreach($esm_empresas as $key=>$valor)
                        {
      if($request['esm_empresa'] == $valor['tipo_id_tercero']."@".$valor['tercero_id'])
      $selected = " selected ";
      else
      $selected = "  ";
      $html .= "              <option ".$selected." value=\"".$valor['tipo_id_tercero']."@".$valor['tercero_id']."\">".$valor['nombre_tercero']."-[".$valor['tercero_id']."]</option>";
                        }
      $html .= "                </select>";
      $html .= "              </td>\n";
 			$html .= "            </tr>\n";
      
      $html .= "            <tr>\n";
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
      $selected="";
     // print_r($request);
      if(strcmp(trim($request['radio']),"inv_bodegas_movimiento_despacho_campania")==0)
      {
      $selected_r1 = " checked ";
      $selected_r2 = " ";
      }
      else
          if(strcmp(trim($request['radio']),"inv_bodegas_movimiento_traslados_esm")==0)
          {
          $selected_r2 = " checked ";
          $selected_r1 = " ";
          }
      $html .= "            <tr>";
      $html .= "              <td class=\"normal_10AN\">DESPACHO POR</td>\n";
      $html .= "              <td class=\"normal_10AN\">DISTRIBUCION <input  ".$selected_r1." type=\"radio\" name=\"buscador[radio]\" value=\"inv_bodegas_movimiento_despacho_campania\" class=\"input-radio\">\n";
      $html .= "              SUMINISTRO <input ".$selected_r2." type=\"radio\" name=\"buscador[radio]\" value=\"inv_bodegas_movimiento_traslados_esm\" class=\"input-radio\"></td>\n";
      $html .= "			      </tr>\n";
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
      $html .= "<br>";
      $html .= "<form name=\"productos\" action=\"".$action['preordenes']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td>\n";
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN\">CONTINUAR - PRE-ORDEN REQUISICION</legend>\n";
			$html .= "		      <table width=\"100%\">\n";
      $html .= "            <tr>\n";
      $html .= "              <td align=\"center\">";
      $html .= "                <input type=\"submit\" class=\"input-submit\" value=\"PRE- ORDENES DE REQUISICION\" >";
      $html .= "              </td>";
      $html .= "            </tr>\n";
      $html .= "          </table>";
      $html .= "        </fieldset>";
      $html .= "	    </td>\n";
			$html .= "	  </tr>\n";
			$html .= "	</table>\n";
      $html .= "  </form>";
      if(!empty($lista))
      {
        $rpt  = new GetReports();
        $html .= $rpt->GetJavaReport('app','ESM_PlaneacionRequerimientos','reporte_general_auditoria',$request,
                                  array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
        $fnc  = $rpt->GetJavaFunction();
        $html .= "<center>\n";
        $html .= "	<a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
        $html .= "	  <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">IMPRIMIR REPORTE BUSQUEDA \n";
        $html .= "  </a>\n";
        $html .= "</center>\n";
        $html .= "<br>\n";
        
        $html .= "<form name=\"pedidos_temporales\" action=\"".$action['guardar_tmp']."\" method=\"post\">\n";
        /*Elementos del Buscador, Para que cuando se recargue el marco, haga la busqueda anterior*/
        $html .= "  <input type=\"hidden\" name=\"buscador[esm_empresa]\" value=\"".$request['esm_empresa']."\">";
        $html .= "  <input type=\"hidden\" name=\"buscador[fecha_inicio]\" value=\"".$request['fecha_inicio']."\">";
        $html .= "  <input type=\"hidden\" name=\"buscador[fecha_final]\" value=\"".$request['fecha_final']."\">";
        $html .= "  <input type=\"hidden\" name=\"buscador[radio]\" value=\"".$request['radio']."\">";
                
        $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
        $html .= "		<tr class=\"formulacion_table_list\" >\n";
  			$html .= "			<td width=\"5%\">CODIGO</td>\n";
  			$html .= "			<td width=\"25%\">ESM</td>\n";
        $html .= "			<td width=\"35%\">PRODUCTO</td>\n";
  			$html .= "			<td width=\"5%\">CANTIDAD</td>\n";
  			$html .= "			<td width=\"5%\">PROMEDIO MENSUAL</td>\n";
  			$html .= "			<td width=\"5%\">PEDIDO</td>\n";
  			$html .= "			<td width=\"2%\">OP</td>\n";
  			/*$html .= "			<td width=\"15%\">FECHA DE REGISTRO</td>\n";
  			$html .= "			<td width=\"5%\" >USUARIO</td>\n";*/
        $html .= "		</tr>\n";
        $i=0;
        foreach($lista as $k1 => $dtl)
        {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

          $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
  				if($dtl['campo_serial']!="")
          {
          $disabled_selected =" disabled checked ";
          }
          else
            $disabled_selected ="  ";
          $html .= "			<td >".$dtl['tercero_id']."</td>\n";
  				$html .= "			<td >".$dtl['nombre_tercero']."</td>\n";
  				$html .= "			<td >".$dtl['producto']."</td>\n";
  				$html .= "			<td >".FormatoValor($dtl['total'])."</td>\n";
  				$html .= "			<td >".FormatoValor($dtl['prom'],2)."</td>\n";
  				$html .= "			<td >";
          $html .= "      <input type=\"hidden\" name=\"tipo_id_tercero[".$i."]\" value=\"".$dtl['tipo_id_tercero']."\">";
          $html .= "      <input type=\"hidden\" name=\"tercero_id[".$i."]\" value=\"".$dtl['tercero_id']."\">";
          $html .= "      <input type=\"hidden\" name=\"codigo_producto[".$i."]\" value=\"".$dtl['codigo_producto']."\">";
          //$html .= "      <input type=\"hidden\" name=\"tipo_fuerza_id[".$i."]\" value=\"".$dtl['tipo_fuerza_id']."\">";
          $html .= "      <input type=\"hidden\" name=\"empresa_id[".$i."]\" value=\"".$dtl['empresa_id']."\">";
          $html .= "      <input type=\"hidden\" name=\"centro_utilidad_destino[".$i."]\" value=\"".$dtl['centro_utilidad_destino']."\">";
          $html .= "      <input type=\"hidden\" name=\"bodega_destino[".$i."]\" value=\"".$dtl['bodega_destino']."\">";
          $html .= "      <input type=\"text\" class=\"input-text\" name=\"pedido[".$i."]\" value=\"".round($dtl['prom'])."\" id=\"pedido[".$i."]\" onkeypress=\"return acceptNum(event)\"></td>\n";
  				$html .= "			<td align=\"center\"><input ".$disabled_selected." type=\"checkbox\" class=\"input-checkbox\" name=\"op[".$i."]\" id=\"".$i."\" value=\"".$i."\"></td>\n";
          $html .= "		</tr>\n";
          $i++;
        }
        $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
        $html .= "			<td colspan=\"7\" align=\"center\">";
        $html .= "      <input type=\"hidden\" name=\"cantidad_registros\" value=\"".$i."\">";
        $html .= "      <input type=\"submit\" value=\"GUARDAR\" class=\"input-submit\">";
        $html .= "      </td>\n";
        $html .= "    </tr>";
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
    function Forma_PreOrdenesRequisicion($action,$request,$lista,$conteo, $pagina)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      $sql = AutoCarga::factory("ListaReportes","classes","app","ESM_PlaneacionRequerimientos");
      $TiposFuerzas=$sql->Tipos_Fuerzas();
      
      $select_fuerzas .= "<select name=\"tipo_fuerza_id\" class=\"select\">";
      foreach($TiposFuerzas as $krt=>$vrt)
      {
      $select_fuerzas .= "<option value=\"".$vrt['tipo_fuerza_id']."\">".$vrt['descripcion']."</option>";
      }
      $select_fuerzas .= "</select>";
      $TiposRequisicionD=$sql->Tipos_Requisicion('D');
      $TiposRequisicionT=$sql->Tipos_Requisicion('T');
      
      $select_trequisicion .="<select name=\"tipo_orden_requisicion\" class=\"select\">";
      foreach($TiposRequisicionD as $kto=>$vto)
      {
      $select_trequisicion .= "<option value=\"".$vto['tipo_orden_requisicion']."\">".$vto['descripcion_orden_requisicion']."</option>";
      }
      $select_trequisicion .= "</select>";
      
      $select_trequisicion_ .="<select name=\"tipo_orden_requisicion\" class=\"select\">";
      foreach($TiposRequisicionT as $kto=>$vto)
      {
      $select_trequisicion_ .= "<option value=\"".$vto['tipo_orden_requisicion']."\">".$vto['descripcion_orden_requisicion']."</option>";
      }
      $select_trequisicion_ .= "</select>";
      
      
      //print_r($_REQUEST);
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptNum(false);
      $html .= ThemeAbrirTabla('PRE- ORDENES DE REQUISICION');
      if(!empty($lista))
      {
      foreach($lista as $key =>$valor)
      {
          $ESM_D = $sql->ESM_PorFarmacia_Distribucion($valor['empresa_id_registro']);
          $ESM_S = $sql->ESM_PorFarmacia_Suministro($valor['empresa_id_registro']);
//          print_r($ESM);
          
          $html .= "  <table width=\"80%\" align=\"center\" >\n";
          $html .= "    <tr>\n";
          $html .= "      <td>\n";
          $html .= "	      <fieldset class=\"fieldset\">\n";
          $html .= "          <legend class=\"normal_10AN\">".$valor['razon_social']."</legend>\n";
          
          $html .= "	      <fieldset class=\"fieldset\">\n";
          $html .= "          <legend class=\"normal_10AN\">DISTRIBUCION</legend>\n";
          foreach($ESM_D as $k =>$v)
          {
          $ESM_D_PROD=$sql->ESM_ProductosDistribucion($valor['empresa_id_registro'],$v['tipo_id_tercero'],$v['tercero_id']);
          //print_r($ESM_D_PROD);
          $html .= "<form name=\"formulario_principal".$valor['empresa_id_registro']."".$v['tipo_id_tercero']."".$v['tercero_id']."\" action=\"".$action['crear_requisicion_tmp']."\" method=\"post\">\n";
          $html .= "		      <table width=\"100%\" class=\"modulo_table_list\">\n";
          $html .= "            <tr>\n";
          $html .= "              <td align=\"center\" colspan=\"4\" class=\"label_error\">";
          $html .= "                <b>ESM - ESTABLECIMIENTO DE SANIDAD MILITAR: ".$v['tercero_id']."-".$v['nombre_tercero']."</b>";
          $html .= "              </td>";
          $html .= "            </tr>\n";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>TIPO FUERZA</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"2\">";
          $html .= "                ".$select_fuerzas;
          $html .= "              </td>";
          $html .= "            </tr>\n";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>TIPO DE REQUISICION</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"2\">";
          $html .= "                ".$select_trequisicion;
          $html .= "              </td>";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>OBSERVACION</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"3\">";
          $html .= "                <textarea name=\"observacion\" style=\"width:100%;heigth:100%\" class=\"textarea\"></textarea>";
          $html .= "              </td>";
          $html .= "            </tr>\n";
                    
          $html .= "		<tr class=\"formulacion_table_list\"  >\n";
          $html .= "    <td width=\"15%\">CODIGO PRODUCTO</td><td width=\"35%\">NOMBRE</td><td width=\"5%\">CANTIDAD</td><td width=\"2%\">OP</td>";
          $html .= "    </tr>";
          $i=0;
              foreach($ESM_D_PROD as $ll =>$llv)
              {
              $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
              $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
              $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
              $html .= "    <td>".$llv['codigo_mindefensa']."-".$llv['codigo_producto']."</td>";
              $html .= "    <td>".$llv['descripcion']."</td>";
              $html .= "    <td>".$llv['cantidad_solicitada']."</td>";
              $html .= "    <td align=\"center\">";
              $html .= "       <a href=\"".$action['eliminar']."&ssiidd=".$llv['campo_serial']."\">";
              $html .= "			 <img title=\"ELIMINAR ITEM\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\"></a>";
              $html .= "    </td>";
              $html .= "    </tr>";
              $html .= "    <input type=\"hidden\" name=\"campo_serial[$i]\" value=\"".$llv['campo_serial']."\">   ";
              $html .= "    <input type=\"hidden\" name=\"codigo_producto[$i]\" value=\"".$llv['codigo_producto']."\">   ";
              $html .= "    <input type=\"hidden\" name=\"cantidad_solicitada[$i]\" value=\"".$llv['cantidad_solicitada']."\">   ";
              $i++;
              }
          $html .= "		<tr class=\"formulacion_table_list\"  >\n";
          $html .= "              <td align=\"center\" colspan=\"4\">";
          $html .= "    <input type=\"hidden\" name=\"registros\" value=\"".$i."\">   ";
          $html .= "    <input type=\"hidden\" name=\"empresa_id_registro\" value=\"".$valor['empresa_id_registro']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"empresa_id\" value=\"".$v['empresa_id']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$v['centro_utilidad']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"bodega\" value=\"".$v['bodega']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" value=\"".$v['tipo_id_tercero']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"tercero_id\" value=\"".$v['tercero_id']."\">   ";
          $html .= "    <input type=\"submit\" value=\"CREAR ORDEN REQUISICION TEMPORAL\" class=\"input-submit\" >";
          $html .= "              </td>";
          $html .= "		</tr>\n";
          $html .= "          </table>";
          
          $html .= "  </form>";
          }
          $html .= "        </fieldset>";
          $html .= "  <br>";
          
          
          /*Por SUMINISTRO*/
          
          
          
          $html .= "	      <fieldset class=\"fieldset\">\n";
          $html .= "          <legend class=\"normal_10AN\">SUMINISTRO</legend>\n";
          foreach($ESM_S as $k =>$v)
          {
          $ESM_S_PROD=$sql->ESM_ProductosSuministro($valor['empresa_id_registro'],$v['tipo_id_tercero'],$v['tercero_id'],$v['centro_utilidad'],$v['bodega']);
          //print_r($ESM_D_PROD);
          $html .= "<form name=\"formulario_principal".$valor['empresa_id_registro']."".$v['tipo_id_tercero']."".$v['tercero_id']."\" action=\"".$action['crear_requisicion_tmp']."\" method=\"post\">\n";
          $html .= "		      <table width=\"100%\" class=\"modulo_table_list\">\n";
          $html .= "            <tr>\n";
          $html .= "              <td align=\"center\" colspan=\"4\" class=\"label_error\">";
          $html .= "                <b>ESM - ESTABLECIMIENTO DE SANIDAD MILITAR: ".$v['tercero_id']."-".$v['nombre_tercero']."</b>";
          $html .= "              </td>";
          $html .= "            </tr>\n";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>BODEGA SATELITE</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"2\">";
          $html .= "                ".$v['descripcion'];
          $html .= "              </td>";
          $html .= "            </tr>\n";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>TIPO FUERZA</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"2\">";
          $html .= "                ".$select_fuerzas;
          $html .= "              </td>";
          $html .= "            </tr>\n";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>TIPO DE REQUISICION</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"2\">";
          $html .= "                ".$select_trequisicion_;
          $html .= "              </td>";
          $html .= "            </tr>\n";
          $html .= "            <tr >\n";
          $html .= "              <td align=\"center\" class=\"formulacion_table_list\">";
          $html .= "                <b>OBSERVACION</b>";
          $html .= "              </td>";
          $html .= "              <td align=\"LEFT\" colspan=\"3\">";
          $html .= "                <textarea name=\"observacion\" style=\"width:100%;heigth:100%\" class=\"textarea\"></textarea>";
          $html .= "              </td>";
          $html .= "            </tr>\n";
                    
          $html .= "		<tr class=\"formulacion_table_list\"  >\n";
          $html .= "    <td width=\"15%\">CODIGO PRODUCTO</td><td width=\"35%\">NOMBRE</td><td width=\"5%\">CANTIDAD</td><td width=\"2%\">OP</td>";
          $html .= "    </tr>";
          $i=0;
              foreach($ESM_S_PROD as $ll =>$llv)
              {
              $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
              $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
              $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
              $html .= "    <td>".$llv['codigo_mindefensa']."-".$llv['codigo_producto']."</td>";
              $html .= "    <td>".$llv['descripcion']."</td>";
              $html .= "    <td>".$llv['cantidad_solicitada']."</td>";
              $html .= "    <td align=\"center\">";
              $html .= "       <a href=\"".$action['eliminar']."&ssiidd=".$llv['campo_serial']."\">";
              $html .= "			 <img title=\"ELIMINAR ITEM\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\"></a>";
              $html .= "    </td>";
              $html .= "    </tr>";
              $html .= "    <input type=\"hidden\" name=\"campo_serial[$i]\" value=\"".$llv['campo_serial']."\">   ";
              $html .= "    <input type=\"hidden\" name=\"codigo_producto[$i]\" value=\"".$llv['codigo_producto']."\">   ";
              $html .= "    <input type=\"hidden\" name=\"cantidad_solicitada[$i]\" value=\"".$llv['cantidad_solicitada']."\">   ";
              $html .= "    ";
              $i++;
              }
          $html .= "		<tr class=\"formulacion_table_list\"  >\n";
          $html .= "              <td align=\"center\" colspan=\"4\">";
          $html .= "    <input type=\"hidden\" name=\"registros\" value=\"".$i."\">   ";
          $html .= "    <input type=\"hidden\" name=\"empresa_id_registro\" value=\"".$valor['empresa_id_registro']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"empresa_id\" value=\"".$v['empresa_id']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$v['centro_utilidad']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"bodega\" value=\"".$v['bodega']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"tipo_id_tercero\" value=\"".$v['tipo_id_tercero']."\">   ";
          $html .= "    <input type=\"hidden\" name=\"tercero_id\" value=\"".$v['tercero_id']."\">   ";
          $html .= "    <input type=\"submit\" value=\"CREAR ORDEN REQUISICION TEMPORAL\" class=\"input-submit\" >";
          $html .= "              </td>";
          $html .= "		</tr>\n";
          $html .= "          </table>";
          
          $html .= "  </form>";
          }
          $html .= "        </fieldset>";
          
          
          
          
          
          $html .= "        </fieldset>";
          $html .= "	    </td>\n";
          $html .= "	  </tr>\n";
          $html .= "	</table>\n";
         
      }
      
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