<?php
  /**
  * Funcion que permite mostrar las opciones de simbologias y abreviaturas, teniendo en 
  * cuenta que opciones fueron seleccionadas en tipos persona y el sexo
  * @param array $form vector con la informacion de los campos del formulario
  * @return string $objResponse objeto de respuesta hacia el formulario
  */
  
  function MostrarFormaFamiliograma($form)
  {
    $objResponse = new xajaxResponse();
    
    $mdl = AutoCarga::factory('FamiliogramaSQL', '', 'hc1', 'Familiograma');
    $simbologias = $mdl->ConsultarSimbologias($form);
    $abreviaturas = $mdl->ConsultarAbreviaturas($form);
    
    $html  = "<table width=\"100%\" align=\"center\">\n";
    $html .= "  <tr class=\"formulacion_table_list\">\n";
    $html .= "    <td colspan=\"2\">SIMBOLOGIAS\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    
    $cont = 1;
    $cont1 = 0;
    $i=0;
    foreach($simbologias as $indice => $valor)
    {
      if ($cont==1)
      {
        $html .= "  <tr>\n";
      }
      
      $html .= "      <td width=\"50%\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"checkbox\" id=\"check_".($i++)."\" name=\""."checks".$cont1."\" value=\"".$valor['simbologia_id']."\">".$valor['simbolo']."\n";
      $html .= "      </td>\n";
       
      if ($cont==2)
      {
        $html .= "  </tr>\n";
        $cont = 0;
      }
      $cont = $cont + 1;
      $cont1 = $cont1 + 1;
    }    
    $chk = "<input type=\"hidden\" name=\"cont_simb\" value=\"".$cont1."\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td class=\"formulacion_table_list\" colspan=\"2\">ABREVIATURAS\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $cont2 = 0;
    $cont3 = 1;
    foreach($abreviaturas as $indice => $valor)
    {
      if($cont3==1)
        $html .= "    <tr>\n";
        
      $html .= "        <td class=\"modulo_list_claro\">\n";
      $html .= "          <input type=\"checkbox\" name=\""."checka".$cont2."\" value=\"".$valor['abreviatura_id']."\">".$valor['desc_abreviatura']."\n";
      $html .= "        </td>\n";
      
      if($cont3==2)
      {
        $cont3 = "    </tr>\n";
        $cont3 = 0;
      }
      $cont2 = $cont2 + 1;
      $cont3 = $cont3 + 1;
      
    }
    $chk .= "<input type=\"hidden\" name=\"cont_abre\" value=\"".$cont2."\">\n";
    $html .= "</table>\n";
    
    $objResponse->assign("divCheck", "innerHTML", $chk);
    $objResponse->assign("divFamiliograma", "innerHTML", $html);
    $objResponse->assign("divFamiliograma", "style.display", "block");
    
    return $objResponse;
  }
?>