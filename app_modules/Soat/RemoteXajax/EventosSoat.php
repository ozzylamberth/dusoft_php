<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CuentasXPagarManual.php,v 1.3 2008/10/23 22:09:23 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.3 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  
  /**
  * Funcion para permitir agregar un cargo
  *
  * @param array $form Arreglo de datos con la informacion de la forma
  * @param string $off Cadena con el offset del paginador
  *
  * @return Object  
  */
  function SeleccionarIngreso($evento,$tipo_doc,$documento,$link1)
  {
    $objResponse = new xajaxResponse();
    $evt = AutoCarga::factory("EventosSoat","classes","app","Soat");

    $ingresos = $evt->ObtenerIngresosEvento($evento,$tipo_doc,$documento);
    if(sizeof($ingresos) == 1)
    {
      $link1 .= $link1."&ingreso_soat=".$ingresos[0]['ingreso'];
      $scpt = "	location.href = \"".$link1."\"\n";
      $objResponse->script($scpt);
    }
    else
    {
      $html  = "<table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "  <tr class=\"formulacion_table_list\">\n";
      $html .= "    <td colspan=\"2\">INGRESOS ASOCIADOS AL EVENTO ".$evento."</td>\n";
      $html .= "  </tr>\n";
      foreach($ingresos as $key => $val)
      {
        $scpt = "	location.href = '".$link1."&ingreso_soat=".$val['ingreso']."'\n";
        $html .= "  <tr class=\"modulo_list_claro\">\n";
        $html .= "    <td width=\"80%\">INGRESO ".$val['ingreso']."</td>\n";
        $html .= "    <td>\n";
        $html .= "      <input type=\"radio\" name=\"ingreso_soat\" value=\"".$val['ingreso']."\" onclick=\"".$scpt."\">\n";
        $html .= "    </td>\n";
        $html .= "  </tr>\n";
      }
      $html .= "</table>\n";
      $objResponse->assign("Contenido","innerHTML",$html);
      $objResponse->call("MostrarSpan");
    }     
    return $objResponse;
  }
?>