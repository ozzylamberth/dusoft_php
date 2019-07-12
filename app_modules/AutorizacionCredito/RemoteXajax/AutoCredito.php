<?php
  /**
  * Funcion que permite calcular y mostras las cuotas de pago de la autorizacion de credito
  * @param array $form vector con la informacion de los campos ingresados en el formulario
  * @return string $objResponse objeto de respuesta al formulario
  */
  function CalcularFormaPago($form)
  {
    $objResponse = new xajaxResponse();
    $plazo = $form['plazo'];
    $fecha = $form['fechaInicio'];
    $interes = $form['interes'];
    $plazoDesc = $form['plazoDesc'];
        
    $f = explode("/", $fecha);
    $dia_estimado = $f[0];
    $cuota = ($form['deuda']-$form['deposito'])/$form['noCuotas'];
    $cargo_interes = $cuota*$interes/100;
    $total = $cuota + $cargo_interes;
    $html  = "<table class=\"modulo_table_list\" width=\"40%\" align=\"center\">\n";
    $html .= "  <tr class=\"formulacion_table_list\">\n";
    $html .= "    <td colspan=\"4\">FORMA DE PAGO\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
    $html .= "    <td>FECHA PROGRAMADA\n";
    $html .= "    </td>\n";
    $html .= "    <td>CUOTA\n";
    $html .= "    </td>\n";
    $html .= "    <td>CARGO POR INTERES\n";
    $html .= "    </td>\n";
    $html .= "    <td>TOTAL CUOTA\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "  <tr class=\"normal_10AN\">\n";
    $html .= "    <td align=\"center\">".$fecha."\n";
    $html .= "    </td>\n";
    $html .= "    <td align=\"center\">".formatoValor($cuota)."\n";
    $html .= "    </td>\n";
    $html .= "    <td align=\"center\">".formatoValor($cargo_interes)."\n";
    $html .= "    </td>\n";
    $html .= "    <td align=\"center\">".formatoValor($total)."\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $fp = "<input type=\"hidden\" name=\"fecha0\" value=\"$fecha\">\n";
    $fp  .= "<input type=\"hidden\" name=\"cuota0\" value=\"".$cuota."\">\n";
    $fp  .= "<input type=\"hidden\" name=\"cargo0\" value=\"".$cargo_interes."\">\n";
    //if ($plazo==5)//"15 y 30")
    if ($plazoDesc=="15 y 30")
    {
      $pago_total = $total;
      for($i=1; $i<$form['noCuotas']; $i++)
      {
        if($dia_estimado==30)
          $f[1] = $f[1]+1;
        if($dia_estimado==31 || $dia_estimado>=1 && $dia_estimado<=14)
        {
          $dia_estimado=15;
        }else if($dia_estimado>=16 && $dia_estimado<=29)
        {
          $dia_estimado=30;
        }else
        {  
          ($dia_estimado==15)? $dia_estimado=30:$dia_estimado=15;
        }
        
        if($f[1]==2 && $dia_estimado==30)
        {
          $fecha = date("d/m/Y", mktime(0,0,0,$f[1], 28, $f[2]));
        }
        else
        {
          $fecha = date("d/m/Y", mktime(0,0,0,$f[1], $dia_estimado, $f[2]));
        }
        $html .= "<tr class=\"normal_10AN\">\n";  
        //$html .= "  <td>".$dia_estimado."/".$f[1]."/".$f[2]."\n";
        $html .= "  <td align=\"center\">".$fecha."\n";
        $html .= "  </td>\n";
        $f = explode("/",$fecha);
        $html .= "  <td align=\"center\">".formatoValor($cuota)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cargo_interes)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($total)."\n";
        $html .= "  <td>\n";
        $html .= "</tr>\n";
        $fp  .= "<input type=\"hidden\" name=\""."fecha".$i."\" value=\"".$fecha."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cuota".$i."\" value=\"".$cuota."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cargo".$i."\" value=\"".$cargo_interes."\">\n";
        $pago_total = $pago_total + $total;              
      }
      $html .= "<tr>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"right\" colspan=\"3\">PAGO TOTAL:\n";
      $html .= "  </td>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"center\">".formatoValor($pago_total)."\n";
      $html .= "  </td>\n";
      $html .= "</tr>\n";
      $html .= "</table>\n"; 
      $cm  = "<td class=\"modulo_list_claro\">".formatoValor($cuota)."\n";  
      $cm .= "</td>\n";
      $objResponse->assign("formaPago", "innerHTML", $html);      
    }

    //if ($plazo==1)//"15 dias")
    if ($plazoDesc=="15 dias")
    {
      $pago_total = $total;
      for($i=1; $i<$form['noCuotas'];$i++)
      {
        $f[0] = $f[0]+15;
        $fecha = date("d/m/Y", mktime(0,0,0,$f[1],$f[0],$f[2]));
        $html .= "<tr class=\"normal_10AN\">\n";
        $f = explode("/",$fecha);
        $html .= "  <td align=\"center\">".$fecha."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cuota)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cargo_interes)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($total)."\n";
        $html .= "  </td>";
        $html .= "</tr>\n";
        $fp  .= "<input type=\"hidden\" name=\""."fecha".$i."\" value=\"".$fecha."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cuota".$i."\" value=\"".$cuota."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cargo".$i."\" value=\"".$cargo_interes."\">\n";
        $pago_total = $pago_total + $total;
      }
      $html .= "<tr>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"right\" colspan=\"3\">PAGO TOTAL:\n";
      $html .= "  </td>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"center\">".formatoValor($pago_total)."\n";
      $html .= "  </td>\n";
      $html .= "</tr>\n";
      $html .= "</table>\n";
      $cm  = "<td class=\"modulo_list_claro\">".formatoValor($cuota)."\n";
      $cm .= "</td>\n";
      $objResponse->assign("formaPago", "innerHTML", $html);      
    }
    
    //if ($plazo==3)//"30 dias")
    if($plazoDesc=="30 dias")
    {
      $pago_total = $total;
      for($i=1; $i<$form['noCuotas'];$i++)
      {
        $f[0] = $f[0]+30;
        $fecha = date("d/m/Y", mktime(0,0,0,$f[1],$f[0],$f[2]));
        $html .= "<tr class=\"normal_10AN\">\n";
        $f = explode("/",$fecha);
        $html .= "  <td align=\"center\">".$fecha."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cuota)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cargo_interes)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($total)."\n";
        $html .= "  </td>\n";
        $html .= "</tr>\n";
        $fp  .= "<input type=\"hidden\" name=\""."fecha".$i."\" value=\"".$fecha."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cuota".$i."\" value=\"".$cuota."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cargo".$i."\" value=\"".$cargo_interes."\">\n";
        $pago_total = $pago_total + $total;
      }
      $html .= "<tr>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"right\" colspan=\"3\">PAGO TOTAL:\n";
      $html .= "  </td>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"center\">".formatoValor($pago_total)."\n";
      $html .= "  </td>\n";
      $html .= "</tr>\n";
      $html .= "</table>\n";
      $cm  = "<td class=\"modulo_list_claro\">".formatoValor($cuota)."\n";
      $cm .= "</td>\n";
      $objResponse->assign("formaPago", "innerHTML", $html);      
    }
    
    //if ($plazo==4)//"45 dias")
    if($plazoDesc=="45 dias")
    {
      $pago_total = $total;
      for($i=1; $i<$form['noCuotas'];$i++)
      {
        $f[0] = $f[0]+45;
        $fecha = date("d/m/Y", mktime(0,0,0,$f[1],$f[0],$f[2]));
        $html .= "<tr class=\"normal_10AN\">\n";
        $f = explode("/",$fecha);
        $html .= "  <td align=\"center\">".$fecha."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cuota)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($cargo_interes)."\n";
        $html .= "  </td>\n";
        $html .= "  <td align=\"center\">".formatoValor($total)."\n";
        $html .= "  </td>\n";
        $html .= "</tr>\n";
        $fp  .= "<input type=\"hidden\" name=\""."fecha".$i."\" value=\"".$fecha."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cuota".$i."\" value=\"".$cuota."\">\n";
        $fp  .= "<input type=\"hidden\" name=\""."cargo".$i."\" value=\"".$cargo_interes."\">\n";
        $pago_total = $pago_total + $total;
      }
      $html .= "<tr>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"right\" colspan=\"3\">PAGO TOTAL:\n";
      $html .= "  </td>\n";
      $html .= "  <td class=\"modulo_table_list\" align=\"center\">".formatoValor($pago_total)."\n";
      $html .= "  </td>\n";
      $html .= "</tr>\n";
      $html .= "</table>\n";
      $cm  = "<td class=\"modulo_list_claro\">".formatoValor($cuota)."\n";
      $cm .= "</td>\n";
      $objResponse->assign("formaPago", "innerHTML", $html);      
    }
    $pt = "<input type=\"hidden\" name=\"pago_total\" value=\"".$pago_total."\">\n";
    $objResponse->assign("fPago", "innerHTML", $fp);
    $objResponse->assign("pagoTotal", "innerHTML", $pt);
    $objResponse->assign("cuotaMensual", "innerHTML", formatoValor($total));
    $objResponse->assign("formaPago", "style.display", "block");
    return $objResponse;
  }
?>