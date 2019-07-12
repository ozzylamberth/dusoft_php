
<?php

/**
 * Clase Vista: InvTrasladosHTML
 * @package IPSOFT-SIIS
 *  @version $Revision: 1.1 $
 * @version $Id: InvTrasladosHTML.class.php,v 1.1 2012/01/24 08:19:24
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Manuel Saenz Grijalba
 */
class InvTrasladosHTML {
    /*
     * Constructor de la clase
     */

    function InvTrasladosHTML()
    {
        
    }

    /**
     * Funcion donde se crea la forma para el menu de Traslado De Saldos
     *
     * @param array $action vector que contiene los link de la aplicacion
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function formaBodega($action, $bodegas)
    {
        $html = ThemeAbrirTabla('MODIFICACION DE EXISTENCIAS X LOTE');
        $html .="<script>\n";
        $html .="function AsignaBodega(bod,cu){\n";
        $html .="   document.getElementById('codBod').value=bod;\n";
        $html .="   document.getElementById('cu').value=cu;\n";
        $html .="   document.formaCargarBodega.submit();\n";
        $html .="}\n";
        $html .="</script>\n";
        $html .= "<form name=\"formaCargarBodega\" id=\"formaCargarBodega\" method=\"post\" action=\"" . $action['busqueda'] . "\">\n";
        $html .= "<table width=\"40%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td align=\"center\">BODEGAS\n";
        $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codBod\" id=\"codBod\" value=\"\" >\n";
        $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"cu\" id=\"cu\" value=\"\" >\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        if ($bodegas > 0)
        {
            foreach ($bodegas as $key => $detalle)
            {
                $html .= "  <tr class=\"modulo_list_claro\">\n";
                $html .= "    <td align=\"center\">\n";
                $html .= "      <a href=\"#\" onclick=\"AsignaBodega('" . $detalle['bodega'] . "','" . $detalle['centro_utilidad'] . "')\" class=\"label_error\">" . $detalle['descripcion'] . " - " . $detalle['descbodega'] . "</a>\n";
                $html .= "    </td>\n";
                $html .= "  </tr>\n";
            }
        }
        else
        {
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "    <td align=\"center\">No Tiene Bodegas Asignadas Para Esta Empresa\n";
            $html .= "  </tr>\n";
        }
        $html .= "</table>\n";
        $html .= "</form>\n";
        $html .= "<br>\n";
        $html .= "<table align=\"center\">\n";
        $html .= "  <tr>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"" . $action['volver'] . "\" class=\"label_error\">VOLVER</a>\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
        $html .= ThemeCerrarTabla();

        return $html;
    }

    /**
     * Funcion donde se crea la forma para la busqueda del medicamento
     *
     * @param array $action vector que contiene los link de la aplicacion
     * @param String $empresa_id variable que contiene la empresa
     * @param array $medicamentos vector que contiene los datos del medicamento buscado
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function BuscarMedicamentos($action, $empresa_id, $medicamentos = null, $conteo = null, $pagina = null, $request, $sw)
    {

        // $pct = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        // $buscarMedicamento = $pct->BuscarMedicamento('FB',null,'1O92A0050007',null,'10','10');
        // print_r($buscarMedicamento);

        $html = ThemeAbrirTabla("Traslado De Existencias [Consulta]");
        $html .="<script>\n";
        $html .="function ValidaCampos(){\n";
        $html .="   nomProd=document.getElementById('nomProd').value;\n";
        $html .="   id=document.getElementById('codigo').value;\n";
        $html .="   if(nomProd=='' && id==''){\n";
        $html .="       alert('Debe llenar uno de los campos De busqueda');\n";
        $html .="   }\n";
        $html .="   else{\n";
        $html .="       document.formaBuscarCargos.submit();\n";
        $html .="   }\n";
        $html .="}\n";
        $html .="</script>\n";
        $html .= "<form name=\"formaBuscarCargos\" id=\"formBuscarCargos\" method=\"post\" action=\"" . $action['parametrizar_busqueda'] . "\">\n";
        $html .= "<table class=\"modulo_table_list\" align=\"center\" width=\"25%\" border=\"0\" cellpadding=\"4\">\n";
        $html .= "  <tr class=\"modulo_table_title\">\n";
        $html .= "    <td colspan=\"2\" align=\"left\">Busqueda\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
        $html .= "  <tr>\n";
        $html .= "  <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">Producto</td>\n";
        $html .= "  <td class=\"modulo_list_claro\"> <input type=\"text\" name=\"nomProd\" id=\"nomProd\" class=\"input-text\" value=\"" . $request['nomProd'] . "\" /> </td>\n";
        $html .= "   </tr>\n";
        $html .= "  <tr>\n";
        $html .= "  <td class=\"formulacion_table_list\"align=\"left\" width=\"25%\">Codigo </td>\n";
        $html .= "  <td class=\"modulo_list_claro\"> <input type=\"text\" name=\"codigo\" id=\"codigo\" class=\"input-text\" value=\"" . $request['codigo'] . "\" /> </td>\n";
        $html .= "   </tr>\n";

        $html .= "   <tr> <td colspan=\"2\" align=\"center\"> <input type=\"button\" value=\"Buscar\" name=\"buscar\" class=\"input-submit\" onclick=\"ValidaCampos();\"/> </td></tr>\n";
        $html .= "      <input type=\"hidden\" class=\"input-text\" name=\"empresa\" value=\"" . $empresa_id . "\" >\n";
        $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codBod\" id=\"codBod\" value=\"" . $request['codBod'] . "\" >\n";
        $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"cu\" id=\"cu\" value=\"" . $request['cu'] . "\" >\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        if (sizeof($medicamentos) > 0)
        {
            $html .= "<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
            $html .= "  <tr class=\"formulacion_table_list\">\n";
            $html .= "    <td width=\"5%\">Modificar</td>\n";
            $html .= "    <td width=\"10%\">Codigo</td>\n";
            $html .= "    <td width=\"40%\">Producto</td>\n";
            $html .= "    <td width=\"5%\">Cantidad</td>\n";
            $html .= "  </tr>\n";

            foreach ($medicamentos as $key => $detalle)
            {
                $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";
                $html .= "<form name=\"formaBuscarPorCodigo$key\" id=\"formaBuscarPorCodigo$key\" method=\"post\" action=\"" . $action['buscarporid'] . "\">\n";
                $html .= "  <tr class=\"" . $est . "\">\n";
                $html .= "    <td width=\"5%\" align=\"center\">\n";
                $html .= "      <a href=\"#\" onclick=\"document.formaBuscarPorCodigo$key.submit();\" \">\n";
                $html .= "        <img id=\"div_" . $empresa_id . $detalle['codigo_producto'] . "\" src=\"" . GetThemePath() . "/images/checkN.gif\" border=\"0\" >\n";
                $html .= "      </a>\n";
                $html .="     </td>\n";
                $html .= "    <td width=\"10%\" align=\"center\">" . $detalle['codigo_producto'] . "</td>\n";
                $html .= "    <td width=\"40%\">" . $detalle['nombre'] . "</td>\n";
                $html .= "    <td width=\"5%\" align=\"center\">" . $detalle['cantidad'] . "\n";
                $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codPro\" value=\"" . $detalle['codigo_producto'] . "\" >\n";
                $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codBod\" id=\"codBode\" value=\"" . $request['codBod'] . "\" >\n";
                $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"cu\" id=\"cut\" value=\"" . $request['cu'] . "\" >\n";
                $html .= "</td>\n";
                $html .= "  </tr>\n";
                $html .= "</form>\n";
            }
            $html .= "  </table>\n";
            $html .="<center>";
            $chtml = AutoCarga::factory('ClaseHTML');
            $html .= "" . $chtml->ObtenerPaginado($conteo, $pagina, $action['paginador']);
            $html .="</center>";
        }
        elseif ($sw == 0 && sizeof($medicamentos) == 0)
        {
            $html .= "<table width=\"40%\" align=\"center\" class=\"modulo_list_claro\">\n";
            $html .= "  <tr class=\"modulo_list_claro\">\n";
            $html .= "    <td width=\"100%\" align=\"center\">No Hay Datos</td>\n";
            $html .= "  </tr>\n";
            $html .= " </table>\n";
        }
        //$html .= "  </table>\n";
        $html .= "<center>\n";
        $html .= "  <a href=\"" . $action['volver'] . "\" class=\"label\">VOLVER</a>\n";
        $html .= "</center>\n";

        $html .= ThemeCerrarTabla();
        return $html;
    }

    /**
     * Funcion donde se crea la forma para la busqueda del medicamento
     *
     * @param array $action vector que contiene los link de la aplicacion
     * @param array $request vector que contiene los valores de la busqueda
     * @param array $medicamento vector que contiene los datos del medicamento buscado
     * @return string $html retorna la cadena con el codigo html de la pagina
     */
    function ModificacionSaldos($action, $request, $medicamento, $existencia_gen)
    {

        $cls = AutoCarga::factory('InvTrasladosSQL', 'classes', 'app', 'InvLotes');
        $html = ThemeAbrirTabla("Traslado De Existencias [Modificacion]");
        $html .="<script>\n";
        $html .="function CalculaCantidad(sw){\n";
        $html .="   cantidades=document.getElementsByName('cantidad[]');\n";
        $html .="   tamCantidades=document.getElementsByName('cantidad[]').length;\n";
        $html .="   total=document.getElementById('total').value;\n";                 //total en lotes_fv
        $html .="   totalgen=document.getElementById('totalgeneral').value;\n"; //total en existencias_bodegas
        $html .="   mensaje=document.getElementById('mensaje');\n";
        $html .="   totalMod=0;\n";
        $html .="   for(var i=0;i<tamCantidades;i++){\n";
        $html .="        if(cantidades[i].value==''){\n";
        $html .="            cantidades[i].value=0\n";
        $html .="        }\n";
        $html .="       totalMod+=parseInt(cantidades[i].value);\n";
        $html .="   }\n ";
        // $html .="   if(totalMod>total || totalMod<total ){\n";
        // $html .="    mensaje.innerHTML='<b>Cantidad Invalida</b>'\n";
        // $html .="   }\n"; 
        $html .="   if(totalMod < totalgen || totalMod > totalgen)\n";
        $html .="   {\n";
        $html .="     mensaje.innerHTML='<b>Cantidad Invalida</b>'\n";
        $html .="   }\n";
        $html .="   else{\n";
        $html .="    if(sw==1){\n";
        $html .="        document.formaModSaldos.submit()\n";
        $html .="    }\n";
        // $html .="    mensaje.innerHTML='<b>'+totalMod+'</b>' \n";
        $html .="    mensaje.innerHTML='<b>'+(Math.round(totalgen*100)/100)+' [Inv Gral.]&nbsp;&nbsp;&nbsp;&nbsp;vs.</b>' \n";
        $html .="   }\n";
        $html .="}\n";
        
        $html .="function HabilitarEdicionFecha(id){\n";
        $html .= "  label = document.getElementById('label_fecha_vencimiento_'+id);";
        $html .= "  label.style.display = \"none\";";
        $html .= "  campo = document.getElementById('edicion_fecha_vencimiento_'+id);";
        $html .= "  campo.style.display = \"block\";";
        $html .="}\n";
        
        $html .="function EditarFecha(e, id) {\n";
        $html .="   tecla = (document.all) ? e.keyCode : e.which;\n";
        $html .="   if (tecla==13) {\n";
        $html .= "  empresa_id = document.getElementById('empresa_id_'+id).value;";
        $html .= "  centro_utilidad = document.getElementById('centro_utilidad_'+id).value;";
        $html .= "  codigo_producto = document.getElementById('codigo_producto_'+id).value;";
        $html .= "  bodega = document.getElementById('bodega_'+id).value;";
        $html .= "  fecha_vencimiento = document.getElementById('fecha_vencimiento_'+id).value;";
        $html .= "  lote = document.getElementById('lote_'+id).value;";
        $html .= "  campo_fecha_vencimiento = document.getElementById('campo_fecha_vencimiento_'+id).value;";
        $html .= "      xajax_EditarFecha(empresa_id, centro_utilidad, codigo_producto, bodega, fecha_vencimiento, lote, campo_fecha_vencimiento);";
        $html .="       alert ('La fecha de vencimiento ha sido actualizada');\n";
        $html .= "      label = document.getElementById('label_fecha_vencimiento_'+id);";
        $html .= "      label.innerHTML = campo_fecha_vencimiento;";
        $html .= "      label.style.display = \"block\";";
        $html .= "      document.getElementById('fecha_vencimiento_'+id).value = campo_fecha_vencimiento;";
        $html .= "      campo = document.getElementById('edicion_fecha_vencimiento_'+id);";
        $html .= "      campo.style.display = \"none\";";
        $html .="   }\n";
        $html .="}\n";
        
        $html .= "  function ValidarFormaNuevaExistencia(forma)\n";
        $html .= "  {\n";
        $html .= "      objeto = document.getElementById('error_nueva_existencia');\n";
        $html .= "      if(forma.nuevo_lote.value == \"\")\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"SE DEBE DIGITAR EL LOTE\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        $html .= "      if(forma.nueva_fecha_vencimiento.value == \"\")\n";
        $html .= "      {\n";
        $html .= "          objeto.innerHTML = \"SE DEBE DIGITAR LA FECHA DE VENCIMIENTO\";\n";
        $html .= "          return;\n";
        $html .= "      }\n";
        //$html .= "    GuardarModeloContable();\n";
        $html .= "      document.nuevaExistencia.action = \"".$action['guardarnuevaexistenciabodega']."\"; \n";
        $html .= "      document.nuevaExistencia.submit();\n";
        $html .= "  }\n";
        
        $html .="</script>\n";
                
        $html .= " <form name=\"formaModSaldos\" id=\"formaModSaldos\" method=\"post\" action=\"" . $action['insertarcambios'] . "\">\n";
        $html .= " <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "  <tr class=\"formulacion_table_list\">\n";
        $html .= "    <td width=\"10%\">Codigo</td>\n";
        $html .= "    <td width=\"25%\">Producto</td>\n";
        $html .= "    <td width=\"5%\">Lote original</td>\n";
        $html .= "    <td width=\"7%\">Nuevo Lote</td>\n"; //added
        $html .= "    <td width=\"25%\">Fecha Vencimiento</td>\n";
        $html .= "    <td width=\"7%\">Nueva Cantidad</td>\n";
        $html .= "    <td width=\"5%\">Cantidad Original</td>\n";
        $html .= "  </tr>\n";
        $sumTotal = 0;
        $i = 0;
        foreach ($medicamento as $key => $detalle)
        {
            $est = ($est == "modulo_list_claro") ? "modulo_list_oscuro" : "modulo_list_claro";

            $html .= "<tr class=\"" . $est . "\">\n";
            $html .= "    <td width=\"10%\" align=\"center\">" . $detalle['codigo_producto'] . "</td>\n";
            $html .= "    <td width=\"25%\">   " . $detalle['nombre'] . "</td>\n";
            $html .= "    <td width=\"5%\" align=\"center\">" . $detalle['lote'] . "</td>\n";
            $html .= "    <td align=\"center\"><input type=\"text\" name=\"lote[]\" id=\"lote\" size=\"7\" class=\"input-text\" value=\"" . $detalle['lote'] . "\" /></td>"; //added
            $html .= "    <td width=\"15%\" align=\"center\">\n";
            //$html .= "        " . $detalle['fecha_vencimiento'] . "\n";
            $html .= "      <span id=\"label_fecha_vencimiento_".$i."\" ondblclick=\"HabilitarEdicionFecha(".$i.")\">" . $detalle['fecha_vencimiento'] . "</span>\n";
            $html .= "      <span id=\"edicion_fecha_vencimiento_".$i."\" style=\"display:none\">\n";
            $html .= "          <input type=\"text\" class=\"input-text\" name=\"fecha_vencimiento[]\" id=\"campo_fecha_vencimiento_".$i."\" onkeypress=\"EditarFecha(event, ".$i.")\" value=\"" . $detalle['fecha_vencimiento'] . "\" >\n";
            $html .= "          " . ReturnOpenCalendario('formaModSaldos', 'campo_fecha_vencimiento_'.$i, '-') . "\n";
            $html .= "      </span>\n";
            $html .= "    </td>\n";

            $html .= "    <td width=\"5%\" align=\"center\"><input type=\"text\" name=\"cantidad[]\" id=\"cantidad\" size=\"4\" class=\"input-text\" onblur=\"CalculaCantidad(0);\" value=\"" . $detalle['cantidad'] . "\" />\n";
            $html .= "</td>\n";
            $html .= "    <td width=\"5%\" align=\"center\">" . $detalle['cantidad'] . "\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codPro[]\" id=\"codigo_producto_".$i."\" value=\"" . $detalle['codigo_producto'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"empresa[]\" id=\"empresa_id_".$i."\" value=\"" . $detalle['empresa_id'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"cut[]\" id=\"centro_utilidad_".$i."\" value=\"" . $detalle['centro_utilidad'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"bodega[]\" id=\"bodega_".$i."\" value=\"" . $detalle['bodega'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"fVenc[]\" id=\"fecha_vencimiento_".$i."\" value=\"" . $detalle['fecha_vencimiento'] . "\" >\n";
            //$html .= "    <input type=\"hidden\" class=\"input-text\" name=\"lote[]\" value=\"" .$detalle['lote']. "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"loteOld[]\" id=\"lote_".$i."\" value=\"" . $detalle['lote'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"cantidadOld[]\" value=\"" . $detalle['cantidad'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codBod\" id=\"codBod\" value=\"" . $request['codBod'] . "\" >\n";
            $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"cu\" id=\"cu\" value=\"" . $request['cu'] . "\" >\n";
            $html .="</td>\n";
            $html .= "</tr>\n";

            $sumTotal = $sumTotal + $detalle['cantidad'];
            $i++;
        }
        
        $html .="<tr>\n";
        $html .="<td colspan=\"5\"><input type=\"hidden\" class=\"input-text\" name=\"total\" id=\"total\" value=\"" . $sumTotal . "\" ></td>\n";
        $html .="<td colspan=\"5\"><input type=\"hidden\" class=\"input-text\" name=\"totalgeneral\" id=\"totalgeneral\" value=\"" . $existencia_gen . "\" ></td>\n";
        $html .="</tr>\n";
        $html .="<tr>\n";
        $html .="<td colspan=\"3\">&nbsp</td>\n";
        $html .="<td align=\"center\">TOTAL</td>\n";
        $html .="<td  align=\"center\"><div id=\"mensaje\"></div></td>\n";
        $html .="<td  align=\"center\" colspan=\"2\"><b>$sumTotal&nbsp;&nbsp;[x Lotes]</b></td>\n";
        $html .="</tr>\n";
        $html .= "   <tr> <td colspan=\"6\" align=\"center\"> <input type=\"button\" value=\"Guardar Cambio\" name=\"enviar\" class=\"input-submit\" onclick=\"CalculaCantidad(1);\"/></td></tr>\n";
        $html .= "  </table>\n";
        $html .= "</form><br>\n";
        
        $html .= "<form name=\"nuevaExistencia\" id=\"nuevaExistencia\" action=\"javascript:ValidarFormaNuevaExistencia(document.nuevaExistencia)\" method=\"post\">";
        $html .= "<center><div class=\"label_error\" id=\"error_nueva_existencia\"></div></center>\n";
        $html .= "  <table width=\"30%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "      <tr class=\"formulacion_table_list\">\n";
        $html .= "          <td width=\"33%\">Lote</td>\n";
        $html .= "          <td width=\"66%\">Fecha Vencimiento</td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr class=\"modulo_list_claro\">\n";
        $html .= "          <td align=\"center\">";
        $html .= "              <input type=\"text\" name=\"nuevo_lote\" id=\"nuevo_lote\" size=\"14\" class=\"input-text\" value=\"\" />";
        $html .= "          </td>";
        $html .= "          <td align=\"center\">";
        $html .= "              <input type=\"text\" class=\"input-text\" name=\"nueva_fecha_vencimiento\" id=\"nueva_fecha_vencimiento\" value=\"\" >\n";
        $html .= "              " . ReturnOpenCalendario('nuevaExistencia', 'nueva_fecha_vencimiento', '-') . "\n";
        $html .= "          </td>";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td colspan=\"2\" align=\"center\">\n";
        $html .= "              <input type=\"submit\" value=\"Guardar Nueva Existencia\" name=\"guardar\" class=\"input-submit\"/>\n";
        $html .= "              <input type=\"hidden\" value=\"".$request['codPro']."\" name=\"cod_producto\"/>\n";
        $html .= "              <input type=\"hidden\" value=\"".$request['codBod']."\" name=\"cod_bodega\">\n";
        $html .= "              <input type=\"hidden\" value=\"".$request['cu']."\" name=\"cod_centro_utilidad\">\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        
        $html .= "<center><br>\n";
        $html .= "  <a href=\"" . $action['volver'] . "\" class=\"label\">VOLVER</a>\n";
        $html .= "</center>\n";

        $html .= ThemeCerrarTabla();
        return $html;
    }

    function MensajeExito($action, $codigo_medicamento)
    {
        $html = ThemeAbrirTabla("Traslado De Saldos [Mensaje]");
        $html .= " <form name=\"formaMenSaldos\" id=\"formaMenSaldos\" method=\"post\" action=\"" . $action['buscarporid'] . "\">\n";
        $html .= " <table width=\"40%\" align=\"center\" border=\"0\"\n";
        $html .= "  <tr >\n";
        $html .= "    <td width=\"100%\" align=\"center\"><b>Cambios Guardados Satisfactoriamente</b>\n";
        $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"codPro\" value=\"" . $codigo_medicamento . "\" >\n";
        $html .= "</td>\n";
        $html .= "  </tr>\n";
        $html .= "  </table>\n";
        $html .= "</form>\n";
        $html .= "<center><br>\n";
        $html .= "  <a href=\"#\" onclick=\"document.formaMenSaldos.submit();\" class=\"label\">VOLVER</a>\n";
        $html .= "</center>\n";
        $html .= ThemeCerrarTabla();
        return $html;
    }

}

?>
