<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: DietasHTML.class.php,v 1.1 2009/02/02 16:32:31 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: DietasHTML
  * Clase en la que se crean las formas para el modulo de cuentas por pagar
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class DietasHTML
  {
    /**
    * Constructosr de la clase
    */
    function DietasHTML(){}
    /**
    *
    */
    function FormaRegistroDietas($action,$control,$nada,$caract,$viaOral)
    {
      $ctl = AutoCarga::factory("ClaseUtil");
      $checks = array();
      
      if($caract['abreviatura'] == 'NVO' && $nada['hc_dieta_id'] == $caract['hc_dieta_id'])
        $checks[0] = "checked";
        
      if($caract['sw_fraccionada'] == 1)
        $checks[1] = "checked";
        
      if($caract['sw_ayuno'] == 1)
        $checks[2] = "checked";

      $html  = $ctl->LimpiarCampos();
      $html .= "<script>\n";
      $html .= "  function deshabilitarElementos(obj,valor)\n";
      $html .= "  {\n";
      $html .= "    obj.fraccionada.disabled = valor;\n";
      $html .= "    obj.ctlAyuno.disabled = valor;\n";
      $html .= "    obj.tipodieta.disabled = valor;\n";
      $html .= "    obj.ctlDietasObs.disabled = valor;\n";
      $html .= "    ele = document.getElementsByName('caracteristica_dieta[]');\n";
      $html .= "    for(i=0; i<ele.length; i++)\n";
      $html .= "    {\n";
      $html .= "      ele[i].disabled = valor;\n";
      $html .= "    }\n";
      $html .= "  }\n";
      $html .= "  function EvaluarDatos(obj)\n";
      $html .= "  {\n";
      $html .= "    ele = document.getElementsByName('caracteristica_dieta[]');\n";
      $html .= "    for(i=0; i<ele.length; i++)\n";
      $html .= "    {\n";
      $html .= "      if(ele[i].checked )\n";
      $html .= "      {\n";
      $html .= "        obj.submit();\n";
      $html .= "        return true;\n";
      $html .= "      }\n";
      $html .= "    }\n";
      $html .= "    document.getElementById('error').innerHTML = 'POR FAVOR SELECCIONAR ALMENOS UN ITEM ASOCIADO AL TIPO DE DIETA';\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      $html .= ThemeAbrirTabla("DIETAS DEL PACIENTE");
      $html .= "<form name=\"dietas\" id=\"dietas\" action=\"".$action['guardar']."\" method=\"post\">\n";
      $html .= "  <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td width=\"100%\" colspan=\"3\">REGISTRO DE DIETAS</td>\n";
      $html .= "   </tr>\n";
      $html .= "   <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td class=\"formulacion_table_list\" width=\"1%\">\n";
      $html .= "        <input type=\"checkbox\" name=\"nada_oral\" value=\"".$nada['hc_dieta_id']."\" onclick=\"deshabilitarElementos(document.dietas,this.checked)\" ".$checks[0].">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" colspan=\"2\">NADA VIA ORAL</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n"; 
      $html .= "      <td class=\"formulacion_table_list\" >\n";
      $html .= "        <input type=\"checkbox\" name=\"fraccionada\" value=\"1\" ".$checks[1].">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" colspan=\"2\">FRACCIONADA (6 Porciones)</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n"; 
      $html .= "      <td class=\"formulacion_table_list\" >\n";
      $html .= "        <input type=\"checkbox\" value=\"1\" name=\"ctlAyuno\" ".$checks[2].">\n";
      $html .= "      </td>\n";
      $html .= "      <td class=\"normal_10AN\" colspan=\"2\">AYUNO</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n"; 
      $html .= "      <td class=\"formulacion_table_list\" ></td>\n";
      $html .= "      <td class=\"normal_10AN\">TIPOS DE DIETAS:</td>\n";
      $html .= "      <td class=\"normal_10AN\">\n";
      $html .= "        <select name=\"tipodieta\" class=\"select\" onchange=\"xajax_SeleccionarTipoDieta(xajax.getFormValues('dietas'))\" >\n";
      
      $sel = "";
      foreach($control as $key => $dtl)
      {
        ($dtl['hc_dieta_id'] == $caract['hc_dieta_id'])? $sel = "selected": $sel = "";
        $html .= "          <option value=\"".$dtl['hc_dieta_id']."\" $sel>".$dtl['descripcion']."</option>\n";
      }
      $html .= "        </select>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_oscuro\">\n";
      $html .= "      <td colspan=\"3\">\n";
      $html .= "        <div id=\"opciones_tipo_dieta\" style=\"display:none\"></div>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td colspan=\"3\">OSERVACION GENERAL DE LA DIETA</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td colspan=\"3\">\n";
      $html .= "        <textarea class=\"textarea\" name=\"ctlDietasObs\" style=\"width:100%\" rows=\"3\">".$caract['observaciones']."</textarea>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "  <center>\n";
      $html .= "    <div id=\"error\" class=\"label_error\"></div>\n";
      $html .= "  </center>\n";
      $html .= "  <table width=\"60%\" align=\"center\">\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"aceptar\" value=\"Guardar\" onclick=\"EvaluarDatos(document.dietas)\">\n";
      $html .= "      </td>\n";          
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" onclick=\"LimpiarCampos(document.dietas);deshabilitarElementos(document.dietas,false)\">\n";
      $html .= "      </td>\n";
      
      if($action['cancelar'])
      {
        $html .= "    </form>\n";
        $html .= "    <form name=\"cancel\" action=\"".$action['cancelar']."\" method=\"post\">\n";
        $html .= "      <td align=\"center\">\n";
        $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"cancelar\" value=\"Cancelar\">\n";
        $html .= "      </td>\n";
      }
      
      $html .= "    </tr>\n";
      $html .= "	</table>\n";
      $html .= "</form>\n";
      $html .= "<script>\n";
      $html .= "  xajax_SeleccionarTipoDieta(xajax.getFormValues('dietas'),'".$caract['evolucion_id']."','".$checks[0]."','".$caract['tipo_dieta']."');\n";
      if($checks[0] == "checked")
        $html .= "  deshabilitarElementos(document.dietas,true);\n";
      $html .= "</script>\n";
      $html .= ThemeCerrarTabla();
      
      return $html;
    }
    /**
    *
    */
    function FormaDetalleDietas($action,$dieta,$detalle,$estado,$profesional)
    {
      $html  = ThemeAbrirTabla("DIETAS DEL PACIENTE");
      $html .= "  <table width=\"60%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td width=\"100%\" colspan=\"2\">DETALLE DE LA DIETA</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n"; 
      
      if($dieta['sw_fraccionada'] == '1')
      {
        $html .= "    <tr class=\"modulo_list_claro\">\n"; 
        $html .= "      <td colspan=\"2\" class=\"normal_10AN\">FRACCIONADA (6 Porciones)</td>\n";
        $html .= "    </tr>\n";
      }
      if($dieta['sw_ayuno'] == '1')
      {
        $html .= "    <tr class=\"modulo_list_claro\">\n"; 
        $html .= "      <td colspan=\"2\" class=\"normal_10AN\">AYUNO</td>\n";
        $html .= "    </tr>\n";
      }

      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"25%\" align=\"left\">TIPO DE DIETA</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">".$dieta['tipo_dieta']."</td>\n";
      $html .= "    </tr>\n";
      
      if(!empty($detalle))
      {
        foreach($detalle as $k1 => $dtll)
        {
          foreach($dtll as $k2 => $dtl)
          {
            $html .= "    <tr class=\"formulacion_table_list\">\n";
            $html .= "      <td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">";
            $html .= "        <ul>\n";
            $html .= "          <li>\n";
            $html .= "            ".$dtl['descripcion']." ";
            if($dtl['descripcion_agrupamiento']) $html .= "- ".$dtl['descripcion_agrupamiento']." ";
          
            $html .= "          </li>\n";
            $html .= "        </ul>\n";
            $html .= "      </td>\n";
            $html .= "    </tr>\n";
          }
        }
      }
      
      if($dieta['observaciones'])
      {
        $html .= "    <tr class=\"formulacion_table_list\" >\n";
        $html .= "      <td colspan=\"2\">OSERVACION GENERAL DE LA DIETA</td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr class=\"modulo_list_claro\">\n";
        $html .= "      <td colspan=\"2\" aling=\"justify\">".$dieta['observaciones']."</td>\n";
        $html .= "    </tr>\n";
      }
      
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"25%\" align=\"left\">REGISTRÓ</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">".$dieta['nombre']."</td>\n";
      $html .= "    </tr>\n";      
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"25%\" align=\"left\">FECHA</td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">".$dieta['fecha_registro']."</td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      if ($estado)
      {
        if ($profesional == 1 || $profesional == 2) 
        {
          $html .= "  <table width=\"60%\" align=\"center\">\n";
          $html .= "    <tr>\n";
					$html .= "		  <td align=\"center\">\n";
					$html .= "		    <a href=\"".$action['editar']."\" class=\"label_error\">\n";
          $html .= "          <img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">EDITAR DIETA\n";
          $html .= "        </a>\n";
          $html .= "      </td>\n";
          $html .= "		  <td align=\"center\">\n";
					$html .= "		    <a href=\"".$action['eliminar']."\" class=\"label_error\" onclick=\"return confirm('Esta seguro que desea eliminar el registro de la dieta ?');\">\n";
					$html .= "			    <img src = \"".GetThemePath()."/images/elimina.png\" border=\"0\">ELIMINAR REGISTRO\n";
          $html .= "        </a>\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "  </table>\n";
				}
			}
      $html .= ThemeCerrarTabla();
      return $html;
    }
    /**
    *
    */
    function FormaConsulta($dieta,$detalle)
    {
      $html .= "  <table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "	    <td width=\"100%\" colspan=\"4\">DIETA DEL PACIENTE</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n"; 
      $html .= "	    <td >DETALLE</td>\n";
      $html .= "	    <td >TIPO DE DIETA</td>\n";
      $html .= "	    <td >REGISTRO</td>\n";
      $html .= "	    <td >FECHA</td>\n";
      $html .= "	  </tr>\n";
      $html .= "    <tr class=\"modulo_list_claro\">\n";
      $html .= "      <td class=\"normal_10AN\">\n";
      
      if($dieta['sw_fraccionada'] == '1')
        $html .= "        FRACCIONADA (6 Porciones)<br>\n";
      
      if($dieta['sw_ayuno'] == '1')
        $html .= "        AYUNO\n";
      
      $html .= "	    </td>\n";
      $html .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $html .= "        ".$dieta['tipo_dieta']."";
      
      if(!empty($detalle))
      {
        $html .= "        <ul>\n";
        foreach($detalle as $k1 => $dtll)
        {
          foreach($dtll as $k2 => $dtl)
          {
            $html .= "          <li>\n";
            $html .= "            ".$dtl['descripcion']." ";
            if($dtl['descripcion_agrupamiento']) $html .= "- ".$dtl['descripcion_agrupamiento']." ";
          
            $html .= "          </li>\n";
          }
        }
        $html .= "        </ul>\n";
      }
      $html .= "      <td >".$dieta['nombre']."</td>\n";
      $html .= "      <td align=\"center\">".$dieta['fecha_registro']."</td>\n";
      $html .= "    </tr>\n";

      if($dieta['observaciones'])
      {
        $html .= "    <tr class=\"modulo_list_claro\" >\n";
        $html .= "      <td colspan=\"4\" aling=\"justify\"><b>OSERVACION:</b> ".$dieta['observaciones']."</td>\n";
        $html .= "    </tr>\n";
      }
      
      $html .= "  </table>\n";
      return $html;
    }    
    /**
    *
    */
    function FormaHistoria($dieta,$detalle)
    {
      $html .= "  <table width=\"100%\" align=\"center\" border=\"1\" class=\"normal_10\" cellpading=\"0\" cellspacing=\"0\" rules=\"all\">\n";
      $html .= "    <tr class=\"label\">\n";
      $html .= "	    <td colspan=\"4\" align=\"center\">DIETA DEL PACIENTE</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"label\">\n"; 
      $html .= "	    <td align=\"center\">DETALLE</td>\n";
      $html .= "	    <td align=\"center\">TIPO DE DIETA</td>\n";
      $html .= "	    <td align=\"center\">REGISTRO</td>\n";
      $html .= "	    <td align=\"center\">FECHA</td>\n";
      $html .= "	  </tr>\n";
      $html .= "    <tr class=\"normal_10\">\n";
      $html .= "      <td >\n";
      
      if($dieta['sw_fraccionada'] == '1')
        $html .= "        FRACCIONADA (6 Porciones)<br>\n";
      
      if($dieta['sw_ayuno'] == '1')
        $html .= "        AYUNO\n";
      
      $html .= "	    </td>\n";
      $html .= "      <td >\n";
      $html .= "        ".$dieta['tipo_dieta']."";
      
      if(!empty($detalle))
      {
        $html .= "        <ul>\n";
        foreach($detalle as $k1 => $dtll)
        {
          foreach($dtll as $k2 => $dtl)
          {
            $html .= "          <li>\n";
            $html .= "            ".$dtl['descripcion']." ";
            if($dtl['descripcion_agrupamiento']) $html .= "- ".$dtl['descripcion_agrupamiento']." ";
          
            $html .= "          </li>\n";
          }
        }
        $html .= "        </ul>\n";
      }
      $html .= "      <td >".$dieta['nombre']."</td>\n";
      $html .= "      <td align=\"center\">".$dieta['fecha_registro']."</td>\n";
      $html .= "    </tr>\n";

      if($dieta['observaciones'])
      {
        $html .= "    <tr class=\"normal_10\">\n";
        $html .= "      <td colspan=\"4\" aling=\"justify\"><b>OSERVACION:</b> ".$dieta['observaciones']."</td>\n";
        $html .= "    </tr>\n";
      }
      
      $html .= "  </table>\n";
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
?>