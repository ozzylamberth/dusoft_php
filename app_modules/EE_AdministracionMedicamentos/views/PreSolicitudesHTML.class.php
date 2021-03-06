<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: PreSolicitudesHTML.class.php,v 1.3 2011/04/26 15:14:17 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: MensajesModuloHTML
  * Clase encargada de crear las formasd para el manejo de las presolicitudes 
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class PreSolicitudesHTML	
	{
		/**
		* Constructor de la clase
		*/
		function PreSolicitudesHTML(){}
		/**
		* Crea un menu principal para el modulo
		*
		* @param array $action Vector que continen los link de la aplicacion
		* @param array $medicamentos Vector con los datos de los medicamentos
    * @param array $insumos Vector con los datos de los insumos seleccionados
    * @param array $bodegas Vector con los datos de las bodegas
    * @paran string $bodega_default Identificador de la bodega por defecto
    * @param array $fac_conversion Vecxtor con los datos del factor de conversion de cada formulacion
    *
		* @return string
		*/
	function FormaListaPresolicitudes($action,$medicamentos,$insumos,$bodegas,$bodega_default,$fac_conversion,$coninv1)
	{
      $ctl = AutoCarga::factory('ClaseUtil');
      $html .= $ctl->AcceptNum();
      $html .= $ctl->IsNumeric();

      $html .= "<SCRIPT>";
      $html .= "function ValidarEntregaMa(CanDes, CanAsi, CanEnSol, namea){";
      $html .= "  var res;";
//      $html .= "        alert(CanDes + ' - ' + CanAsi + ' - ' + CanEnSol);";
      $html .= "  res = eval(CanDes) + eval(CanEnSol);";
      $html .= "  if(CanDes == 0){";
      $html .= "        brake;";
      $html .= "  }";
      $html .= "  if(CanDes < 0){";
      $html .= "        document.getElementById(namea).value = CanDes;";
      $html .= "        brake;";
      $html .= "  }";
      $html .= "  if(res < 0){";
      $html .= "        document.getElementById(namea).value = CanDes;";
      $html .= "        brake;";
      $html .= "  }";      
      $html .= "  if((CanDes + CanEnSol) > CanAsi){";
      $html .= "        alert('La cantidad a despachar no pude ser superior a la formulada');";
      $html .= "        res = CanAsi - CanEnSol;";
      $html .= "        if (res <= 0)";
      $html .= "        	document.getElementById(namea).value = CanDes;";
      $html .= "        else";
      $html .= "        	document.getElementById(namea).value = res;";
      $html .= "  }";
      $html .= "}";
      $html .= "</script><br>\n";

      $html .= "<script>\n";
      $html .= "  function SeleccionarItemsPS(key,valor)\n";
      $html .= "  {\n";
      $html .= "    datos = document.getElementsByName('opcion_presolicitud['+key+'][]')\n";
      $html .= "    for(i=0; i<datos.length; i++)\n";
      $html .= "    {\n";
      $html .= "      datos[i].checked = valor;\n";
      $html .= "    }\n";
      $html .= "  }\n";      
      $html .= "  function EvaluarDatosPS(frm)\n";
      $html .= "  {\n";
      $html .= "    flag1 = true;\n";
      $html .= "    if(frm.bodega_presolicitud.value == '-1')\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errores_ps').innerHTML = 'NO SE HA SELECCIONADO UNA BODEGA';\n";
      $html .= "      return;\n";
      $html .= "    }\n";
      $html .= "    for(i=0; i< frm.length ; i++)\n";
      $html .= "    {\n";
      $html .= "      if(frm[i].type == 'checkbox' && frm[i].checked)\n";
      $html .= "      {\n";
      $html .= "        flag1 = false;\n";
      $html .= "        try\n";
      $html .= "        {\n";
      $html .= "          if(!IsNumeric(frm[i-2].value))\n";
      $html .= "          {\n";
      $html .= "            document.getElementById('errores_ps').innerHTML = 'EL FORMATO DE LA CANTIDAD DEL MEDICAMENTO O INSUMO CON CODIGO '+frm[i].value+', NO ES VALIDA';\n";
      $html .= "            return;\n";
      $html .= "          }\n";
      $html .= "        }\n";
      $html .= "        catch(error){}\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    if(flag1)\n";
      $html .= "    {\n";
      $html .= "      document.getElementById('errores_ps').innerHTML = 'NO SE HA SELECCIONADO NINGUN MEDICAMENTO NI INSUMO PARA SER INCLUIDO EN LA SOLICITUD';\n";
      $html .= "      return;\n";
      $html .= "    }\n";      
      $html .= "    document.getElementById('errores_ps').innerHTML = '';\n";
      $html .= "    frm.action = \"".$action['crear']."\";\n";
//      $html .= "    alert(frm.action = \"".$action['crear']."\");\n";
      $html .= "    frm.submit();\n";
      $html .= "  }\n";
      $html .= "</script>\n";
			//$html .= "<form name=\"presolicitudes\" id=\"presolicitudes\" action=\"".$action['crear']."\" method=\"post\">\n";
			$html .= "<form name=\"presolicitudes\" id=\"presolicitudes\" action=\"javascript:EvaluarDatosPS(document.presolicitudes)\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "	  <tr>\n";
      $html .= "		  <td width=\"30%\" class=\"formulacion_table_list\">SELECCIONAR BODEGA:</td>\n";
      $html .= "		  <td class=\"modulo_list_claro\">\n";
      $html .= "        <select name=\"bodega_presolicitud\" class='select'>\n";
      $html .= "          <option value=\"-1\" >--SELECCIONE--</option>\n";

      foreach($bodegas as $k => $vlr)
      {
        ($bodega_default == $vlr['bodega'])? $sel = "selected": $sel = "";
        $html .= "          <option value=\"".$vlr['bodega']."\" ".$sel.">".$vlr['descripcion']."</option>\n";
      } 
      $html .= "          <option value=\"-2\" >SOLICITUD PACIENTE</option>\n";
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table><br>\n";
      $i = -1;
//	  $html .= print_r($medicamentos);
      foreach($medicamentos as $key => $dtl)
      {
        $i = $i + 1;
      //ARCHIVO NUEVO
        $ena = "";
        if ($dtl['stock'] > 0 and $coninv1 == 0)
          $ena = " disabled=true ";
          
        $cantidad = $dtl['cantidad'];
        if($dtl['intensidad'] == "Hora(s)")
          $cantidad = $dtl['cantidad'] * 24/$dtl['intensidad_cantidad'];
        else if($dtl['intensidad'] == "Minuto(s)")
          $cantidad = $dtl['cantidad'] * 24/($dtl['intensidad_cantidad']/60);

/*          
        if($fac_conversion[$dtl['codigo_medicamento']]['sw_unidad_minima']){
            $cantidad = $cantidad/$fac_conversion[$dtl['codigo_medicamento']]['sw_unidad_minima'];
        }else{
            if($fac_conversion[$dtl['codigo_medicamento']]['factor_conversion'])
              $cantidad = $cantidad/$fac_conversion[$dtl['codigo_medicamento']]['factor_conversion'];
        }
*/        
//		$html .= $fac_conversion[$dtl['codigo_medicamento']]['factor_conversion'];
        if($fac_conversion[$dtl['codigo_medicamento']]['factor_conversion'])
          $cantidad = $cantidad/$fac_conversion[$dtl['codigo_medicamento']]['factor_conversion'];
        
        $valor = round($cantidad);
        if($valor < $cantidad) $valor++;

        $cantidad = $valor;
        
        $html .= "  <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
        $html .= "	  <tr>\n";
        $html .= "		  <td class=\"modulo_list_claro\" colspan=\"6\">\n";
        $html .= "        <a href=\"".$action['cancelar'].URLRequest(array("solicitud_tratamiento_id"=>$dtl['solicitud_tratamiento_id']))."\" class=\"label_error\" onclick=\"return confirm('?ESTA SEGURO QUE DESEA CANCELAR LA PRESOLICITUD?')\">\n";
        $html .= "          <img src=\"".GetThemePath()."/images/elimina.png\" border='0'> CANCELAR PRESOLICITUD ".$dtl['solicitud_tratamiento_id']."\n";
        $html .= "        </a> ";
        $html .= "      </td>\n";
        $html .= "	  </tr>\n";
        $html .= "	  <tr class=\"formulacion_table_list\">\n";
        $html .= "		  <td width=\"10%\">SOLICITUD</td>\n";
        $html .= "		  <td width=\"10%\">CODIGO</td>\n";
        $html .= "		  <td width=\"45%\">PRODUCTO</td>\n";
        $html .= "		  <td width=\"%\">PRINCIPIO ACTIVO</td>\n";
        $html .= "		  <td width=\"7%\">CANT</td>\n";
        $html .= "		  <td width=\"1%\">\n";
//        $html .= "		  <input type=\"checkbox\" name=\"todos_".$key."\" onclick=\"SeleccionarItemsPS('".$dtl['solicitud_tratamiento_id']."',this.checked); ValidarEntregaMa(eval(CantidadAcb$i.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), CantidadAcb.id) ;\"".$ena.">\n";
//        $html .= "		  <input type=\"checkbox\" name=\"todos_".$key."\" onclick=\"SeleccionarItemsPS('".$dtl['solicitud_tratamiento_id']."',this.checked); ValidarEntregaMa(eval(CantidadAcb$i.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), CantidadAcb$i.id) ;\"".$ena.">\n";
        if ($coninv1==0){
          $html .= "		  <input type=\"checkbox\" name=\"todos_".$key."\" onclick=\"SeleccionarItemsPS('".$dtl['solicitud_tratamiento_id']."',this.checked); ValidarEntregaMa(eval(CantidadAcb$i.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), CantidadAcb$i.id) ;\"".$ena.">\n";
        }else{
          $html .= "		  <input type=\"checkbox\" name=\"todos_".$key."\" onclick=\"SeleccionarItemsPS('".$dtl['solicitud_tratamiento_id']."',this.checked); \"".$ena.">\n";
        }
        $html .= "      </td>\n";
        $html .= "    </tr>\n";

        if ($dtl['stock'] > 0){
            $html .= "	  <tr bgcolor='#A5A7AB'>\n";
        }else{
          $html .= "	  <tr class=\"modulo_list_claro\">\n";
        }

        $html .= "		  <td >".$dtl['fecha_siguiente_solictud']."</td>\n";
        $html .= "		  <td >".$dtl['codigo_medicamento']."</td>\n";
        $html .= "		  <td >".$dtl['producto']."</td>\n";
        $html .= "		  <td >".$dtl['principio_activo']."</td>\n";
        $html .= "		  <td >\n";
//        $html .= "		  <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" value=\"".$cantidad."\"".$ena.">\n";
//        $html .= "		  <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" value=\"".$cantidad."\" readonly>\n";
//        $html .= "		  <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" value=\"".$cantidad."\"  onblur = \"if(eval(this.value) > eval(cantidadvala$i.value)) alert('La cantidad no puede ser superior a la solicitada'); if(eval(this.value) > eval(cantidadvala$i.value)) this.value = cantidadvala$i.value;\"><input type=\"hidden\" id=\"cantidadvala$i\" value='".$cantidad."'><input type=\"hidden\" id=\"solicitadavala$i\" value='".$dtl[solicitadoval]."'>\n";
//        $html .= "		  <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" id=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" value=\"".$cantidad."\"  onblur = \" ValidarEntregaMa(eval(this.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), this.id) ;\"><input type=\"hidden\" id=\"cantidadvala$i\" value='".$cantidad."'><input type=\"hidden\" id=\"solicitadavala$i\" value='".$dtl[solicitadoval]."'>\n";
//        $html .= "		  <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" id=\"CantidadAcb$i\" value=\"".$cantidad."\"  onblur = \" ValidarEntregaMa(eval(this.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), this.id) ;\"><input type=\"hidden\" id=\"cantidadvala$i\" value='".$cantidad."'><input type=\"hidden\" id=\"solicitadavala$i\" value='".$dtl[solicitadoval]."'>\n";
        if ($coninv1==0){
          $html .= "		 <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" id=\"CantidadAcb$i\" value=\"".$cantidad."\"  onblur = \" ValidarEntregaMa(eval(this.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), this.id) ;\"><input type=\"hidden\" id=\"cantidadvala$i\" value='".$cantidad."'><input type=\"hidden\" id=\"solicitadavala$i\" value='".$dtl[solicitadoval]."'>\n";
        }else{
          $html .= "		 <input type=\"text\" class=\"input-text\" onkeypress=\"return acceptNum(event);\" style=\"width:100%\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][cantidad]\" id=\"CantidadAcb$i\" value=\"".$cantidad."\"  ><input type=\"hidden\" id=\"cantidadvala$i\" value='".$cantidad."'><input type=\"hidden\" id=\"solicitadavala$i\" value='".$dtl[solicitadoval]."'>\n";
        }
        $html .= "		    <input type=\"hidden\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][solicitud]\" value=\"".$dtl['solicitud_tratamiento_id']."\">\n";
        $html .= "		    <input type=\"hidden\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$key."][insumo]\" value=\"0\">\n";
        $html .= "      </td>\n";
        $html .= "		  <td>\n";

//        $html .= "<input type=\"checkbox\" name=\"opcion_presolicitud[".$dtl['solicitud_tratamiento_id']."][]\" value=\"".$key."\"".$ena." onclick = \"ValidarEntregaMa(eval(CantidadAcb$i.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), CantidadAcb$i.id);\">\n";
        if ($coninv1==0){
          $html .= "<input type=\"checkbox\" name=\"opcion_presolicitud[".$dtl['solicitud_tratamiento_id']."][]\" value=\"".$key."\"".$ena." onclick = \"ValidarEntregaMa(eval(CantidadAcb$i.value), eval(cantidadvala$i.value),  eval(solicitadavala$i.value), CantidadAcb$i.id);\">\n";
        }else{
          $html .= "<input type=\"checkbox\" name=\"opcion_presolicitud[".$dtl['solicitud_tratamiento_id']."][]\" value=\"".$key."\"".$ena." >\n";
        }

        $html .= "      </td>\n";
				$html .= "    </tr>\n";
        if(!empty($insumos[$key]))
        {
          $html .= "	  <tr class=\"formulacion_table_list\">\n";
          $html .= "	    <td colspan=\"6\">PRODUCTOS ASOCIADOS AL MEDICAMENTO</td>\n";
          $html .= "	  </tr>\n";
          foreach($insumos[$key] AS $k1 => $dtll)
          {
            $html .= "	  <tr class=\"modulo_list_claro\">\n";
            $html .= "		  <td >&nbsp;</td>\n";
            $html .= "		  <td >".$dtll['codigo_producto']."</td>\n";
            $html .= "		  <td >".$dtll['producto']."</td>\n";
            $html .= "		  <td >".$dtll['principio_activo']."</td>\n";
            $html .= "		  <td >\n";
            $html .= "		    <input type=\"text\" style=\"width:100%\" onkeypress=\"return acceptNum(event);\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$dtll['codigo_producto']."][cantidad]\" value=\"".intval($dtll['cantidad']*$cantidad)."\">\n";
            $html .= "		    <input type=\"hidden\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$dtll['codigo_producto']."][solicitud]\" value=\"".$dtl['solicitud_tratamiento_id']."\">\n";
            $html .= "		    <input type=\"hidden\" name=\"presolicitud[".$dtl['solicitud_tratamiento_id']."][".$dtll['codigo_producto']."][insumo]\" value=\"".$dtll['insumo']."\">\n";
            $html .= "      </td>\n";
            $html .= "		  <td>\n";
            $html .= "		    <input type=\"checkbox\" name=\"opcion_presolicitud[".$dtl['solicitud_tratamiento_id']."][]\" value=\"".$dtll['codigo_producto']."\">\n";
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
          }
        }
				$html .= "  </table>\n";
				$html .= "  </br>\n";
			}

			$html .= "  <center >\n";
			$html .= "    <div id=\"errores_ps\" class=\"label_error\"></div>\n";
			$html .= "  </center>\n";
			$html .= "  <table width=\"60%\" align=\"center\" >\n";
			$html .= "	  <tr>\n";
			$html .= "		  <td align=\"center\">\n";
			$html .= "		    <input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Crear Solicitud\">\n";
			$html .= "      </td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n";
			$html .= "</form>\n";
			return $html;
		}
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "	<tr>\n";
			$html .= "		<td align=\"center\"><br>\n";
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}
	}
    function eliminarCaracteresEspeciales($cadena){

        $aux_cadena = "";
        $aux_acentos = "??????????????????????????????";
        $aux_validos = "aeiouAEIOUaeiouAEIOUaeiouAEIOU";
    
        $aux_cadena = trim($cadena);
        $aux_cadena = eregi_replace("\r]", ' ', $aux_cadena);
        $aux_cadena = str_replace("''", '', $aux_cadena);
        $aux_cadena = strtr($aux_cadena, $aux_acentos, $aux_validos);

        // para el control de caracteres especiales como la ?,?; es necesario codificar la cadena en UTF8 (para la correcta escritura en excel)
        //if(strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?') || strstr($aux_cadena , '?'))    
        $aux_cadena = @utf8_encode($aux_cadena);

        return $aux_cadena;

    } // fin-function eliminarCaracteresEspeciales
?>