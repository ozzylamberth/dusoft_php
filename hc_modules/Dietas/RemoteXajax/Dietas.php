<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Dietas.php,v 1.1 2009/02/02 16:32:31 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  *
  - @return object
  */
  function SeleccionarTipoDieta($form,$evolucion,$check,$tipo_dieta)
  {
    $objResponse = new xajaxResponse();
    $dts = AutoCarga::factory("DietasSql","classes","hc1","Dietas");
    $dieta_d = array();
    
    if($evolucion)
      $dieta_d = $dts->ObtenerDietaspacienteDetalle($evolucion);

    $caractI = $dts->ObtenerDietasCaracteristicasI($form['tipodieta']);
    $caractII = $dts->ObtenerDietasCaracteristicasII($form['tipodieta']);
    
    $disable = "";
    if($check == "checked") $disable = "disabled";
    
    $porc = "50%";
    if((!empty($caractI) && empty($caractII)) || (empty($caractI) && !empty($caractII)) )
      $porc = "100%";
    
    $chk = "";
    if(!empty($caractI) || !empty($caractII))
    {
      $html .= "          <table width=\"100%\">\n";
      $html .= "            <tr>\n";

      if(!empty($caractI))
      {
        $html .= "              <td width=\"".$porc."\" valign=\"top\">\n";
        $html .= "                <table width=\"100%\" class=\"modulo_table_list\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">\n";
        foreach($caractI as $k1 => $dtl)
        {
          (!empty($dieta_d[$dtl['caracteristica_id']][utf8_decode($tipo_dieta)]))? $chk = "checked": $chk = "";
            
          $html .= "                  <tr class=\"modulo_list_claro\">\n";
          $html .= "                    <td width=\"1%\" class=\"formulacion_table_list\">\n";
          $html .= "                      <input type=\"checkbox\" name=\"caracteristica_dieta[]\" value=\"".$dtl['caracteristica_id']."\" ".$disable." ".$chk.">\n";
          $html .= "                    </td>\n";
          $html .= "                    <td class=\"normal_10AN\">".$dtl['descripcion']."</td>\n";
          $html .= "                  </tr>\n";
        }
        $html .= "                </table>\n";
        $html .= "              </td>\n";
      }

      if(!empty($caractII))
      {
        $codAnterior = "";
        $html .= "              <td width=\"".$porc."\" valign=\"top\">\n";
        $html .= "                <table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"modulo_table_list\">\n";
        foreach($caractII as $k2 => $dtl)
        {              
          if(!empty($dtl['codigo_agrupamiento']))
          {
            if($dtl['descripcion'] != $codAnterior)
            {
              $html .= "                  <tr class=\"modulo_list_claro\">\n";
              $html .= "                    <td class=\"normal_10AN\" colspan=\"2\">".$dtl['descripcion']."</td>\n";
              $html .= "                  </tr>\n";
            }
            $codAnterior = $dtl['descripcion'];
            (!empty($dieta_d[$dtl['caracteristica_id']][utf8_decode($tipo_dieta)]))? $chk = "selected": $chk = "";
            
            $html .= "                  <tr class=\"modulo_list_claro\">\n";
            $html .= "                    <td width=\"1%\" class=\"modulo_table_list_title\">\n";
            $html .= "                      <input type=\"radio\" id=\"radio\" name=\"caracteristica_dieta[]\" value=\"".$dtl['caracteristica_id']."\" ".$disable." ".$chk.">\n";
            $html .= "                    </td>\n";
            $html .= "                    <td class=\"normal_10AN\">".$dtl['descripcion_agrupamiento']."</td>\n";
            $html .= "                  </tr>\n";
          }
          else
          {
            (!empty($dieta_d[$dtl['caracteristica_id']][utf8_decode($tipo_dieta)]))? $chk = "checked": $chk = "";

            $html .= "                  <tr class=\"modulo_list_claro\">\n";
            $html .= "                    <td width=\"1%\" class=\"formulacion_table_list\" >\n";
            $html .= "                      <input type=\"checkbox\" id=\"radio\" name=\"caracteristica_dieta[]\" value=\"".$dtl['caracteristica_id']."\" ".$disable." ".$chk.">\n";
            $html .= "                    </td>\n";
            $html .= "                    <td class=\"normal_10AN\" >".$dtl['descripcion']."</td>\n";
            $html .= "                  </tr>\n";
          }
        }
        $html .= "                </table>\n";
        $html .= "              </td>\n";
      }
      $html .= "            </tr>\n";
      $html .= "          </table>\n";
      $objResponse->assign("opciones_tipo_dieta","style.display","block");
      $objResponse->assign("opciones_tipo_dieta","innerHTML",utf8_encode($html));
    }
    else
    {
      $objResponse->assign("opciones_tipo_dieta","style.display","none");
      $objResponse->assign("opciones_tipo_dieta","innerHTML","");
    }
    return $objResponse;
  }