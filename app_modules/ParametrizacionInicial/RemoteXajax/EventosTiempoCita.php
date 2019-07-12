<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
  /**
  * Funcion que permite mostrar el tiempo de cita
  *
  * @param  array $form arreglo de la forma 
  * @return Object $objResponse objeto de respuesta al formulario  
  */
  function MostrarDCitas($form)
  {
    $objResponse = new xajaxResponse();
    
    $mdl = AutoCarga::factory("ParametrizacionInicialSQL","","app","ParametrizacionInicial");
    $buscar_afiliados = $mdl->ConsultarAfiliados($form['planes']);
   
    $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
    $html .= "    <tr>\n";
    $html .= "    <td colspan=\"2\" align=\"left\">";
    $html .= "    <td class=\"formulacion_table_list\" align=\"left\" width=\"25%\">Dias\n";
    $html .= "    </td>\n";
    
    $html .= "    <td class=\"modulo_list_claro\" width=\"75%\">";
    $html .= "       <input type=\"text\" class=\"input-text\" name=\"NDias\" value=\"\"size=\"5\"onkeypress=\"return acceptNum(event)\">\n";
    $html .= "       <a href=\"javascript:Asignar(formBuscarPlanes)\" onclick=\"\">ASIGNAR A TODOS</a>";
    $html .= "    </td>";
    $html .= "    </td>\n";
    $html .= "    </tr>\n";
    $html .= "  </table><br> ";
    $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td  align=\"center\" width=\"50%\">TIPO AFILIADO\n";
    $html .= "      </td>\n";
    $html .= "      <td  align=\"center\"width=\"10%\">RANGO\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\center\"width=\"10%\">DIAS DE CITA\n";
    $html .= "      </td>";
    $html .= "    </tr>\n";
    foreach ($buscar_afiliados as $indice=>$valor)
    {
      ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
      ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
						
			$html .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
      $html .= "      <td>".$valor['tipo_afiliado_nombre']."</td>";
      $html .= "      <td>".$valor['rango']."</td>";
    
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"DiaCita[]\" value=\"".$valor['tiempo_cita']."\" onkeypress=\"return acceptNum(event)\" size=\"5\">\n";
      $html .= "      <input type=\"hidden\" class=\"input-text\" name=\"insert[]\" value=\"".$valor['tiempo_cita']."\" >\n";
      $html .= "      <input type=\"hidden\" name=\"tipo_af[]\" value=\"".$valor['tipo_afiliado_id']."\"size=\"5\">\n";
      $html .= "      <input type=\"hidden\" name=\"rango[]\" value=\"".$valor['rango']."\"size=\"5\">\n";
      
      $html .= "  </tr>";
    }
    
    $html .= " <table align=\"center\">\n";
    $html .= "    <tr>\n";
    $html .= "      <td>\n";
    $html .= "         <input class=\"input-submit\" type=\"button\" value=\"Guardar\" onclick=\"ValidarDias()\">\n";
    $html .= "      </td>\n";
    $html .= "     </tr>\n";
    $html .= " </table>\n";
    $html .= "  </table>";
    
    $objResponse->assign("tiempocita","innerHTML",$html);
    return $objResponse;
  }
  
  /**
  * Funcion que permite mostrar la prioridad del cargo
  *
  * @param  array $form arreglo de la forma 
  * @return Object $objResponse objeto de respuesta al formulario  
  */
  function MostrarCargo($form)
  {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("ParametrizacionInicialSQL","","app","ParametrizacionInicial");
    $buscar_cargos = $mdl->ConsultarTipoCargos($form['cargos'],$form['empresa']);
    
    $html .= "  <br>\n";
    $html .= "  <table class=\"modulo_table_list\" align=\"center\" width=\"70%\">\n";
    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td align=\"center\" width=\"20%\">CODIGO\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\" width=\"50%\">CARGO\n";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\" width=\"20%\">PRIORIDAD\n";
    $html .= "      </td/>";
    $html .= "      </td>\n";
    $html .= "      <td align=\"center\" width=\"30%\">TIEMPO\n";
    $html .= "      </td/>";
    $html .= "    </tr>\n";
    foreach ($buscar_cargos as $indice=>$valor)
    {
      ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
      ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
						
			$html .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
      $html .= "      <td align=\"center\">".$valor['cargo_cita']."</td>";
      $html .= "      <td>".$valor['descripcion']."</td>";
      $html .= "  <td>\n";
      $html .= "    <select width=\"50%\" class=\"select\" name=\"Prioridad[]\" id=\"Prioridad\">\n";
      $html .= "    <option value=\"-1\">--</option>\n";
      $s =   "";
      for ($i = 1; $i<= sizeof($buscar_cargos); $i++)
      {
       ($i == $valor['prioridad'])? $s = "selected": $s = "";
       $html .= "    <option value=\"".$i."\" $s>".$i."</option>\n";
      }
      $html .= "    </select>\n";
      $html .= "    <input type=\"text\" class=\"input-text\" name=\"cargostiemp[]\" value=\"".$valor['tiempo_cargo']."\" onkeypress=\"return acceptNum(event)\" size=\"5\">\n";
      
	  $html .= "    <input type=\"hidden\" class=\"input-text\" name=\"insert[]\" value=\"".$valor['tiempo_cargo']."\" onkeypress=\"return acceptNum(event)\" size=\"5\" >\n";
      $html .= "</td>";
      $html .= "<td>";
        
      $html .= "      <input type=\"hidden\" name=\"cargocups[]\" value=\"".$valor['cargo_cita']."\"size=\"5\">\n";
      $html .= "      <input type=\"hidden\" name=\"descripcargo[]\" value=\"".$valor['descripcion']."\"size=\"5\">\n";
      //$html .= "      <input type=\"hidden\" name=\"empresa\" value=\"".$form['empresa']."\"size=\"5\">\n";
      $html .= "      <input type=\"hidden\" name=\"tipo_consulta[]\" value=\"".$valor['tipo_consulta_id']."\"size=\"5\">\n"; 
      $html .= "  </td>";
      $html .= "  </tr>";
         
    }
      
    $html .= " <table align=\"center\">\n";
    $html .= "    <tr>\n";
    $html .= "      <td>\n";
    $html .= "         <input class=\"input-submit\" type=\"button\" value=\"Guardar\" onclick=\"ValidarPrioridades()\">\n";
    $html .= "      </td>\n";
    $html .= "     </tr>\n";
    $html .= " </table>\n";
    $html .= " </table>";
    
    $objResponse->assign("tipocargo","innerHTML",$html);
    return $objResponse;
  }
  /**
  * Funcion donde se activa o inactiva la modificacion de la fecha de incapacida
  * 
  * @param string $estado Nuevo valor del estado
  * @param string $servicio Identificador del servicio
  *
  * @return $object
  */
  function ActivarFechaIncapacidad($estado,$servicio)
  {
    $objResponse = new xajaxResponse();
    
    $srv = AutoCarga::factory("Servicios","classes","app","ParametrizacionInicial");

    $rst = $srv->ActualizarIncapacidadServicios($estado,$servicio);
    if(!$rst)
    {
      $objResponse->alert($srv->mensajeDeError);
    }
    else
    {
      $letra = ($estado == "1")? "SI": "NO";
      $link = ($estado == "1")? "INACTIVAR": "ACTIVAR";
      
      $html  = "        <a href=\"javascript:ActivarFechaIncapacidad('".(($estado == "1")? "0":"1")."','".$dtl['servicio']."')\" class=\"label_error\">\n";
      $html .= "          <img src=\"".GetThemePath()."/images/".(($estado == "1")? "checksi":"checkno").".png\" border=\"0\">".$link."\n";
      $html .= "        </a>\n";
      
      $objResponse->assign("servicio_letra_".$servicio,"innerHTML",$letra);
      $objResponse->assign("servicio_link_".$servicio,"innerHTML",$html);
    }
    return $objResponse;
  }
?>