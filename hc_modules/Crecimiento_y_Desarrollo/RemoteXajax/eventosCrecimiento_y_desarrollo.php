<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: eventosCrecimiento_y_desarrollo.php,v 1.1 2010/02/05 21:20:05 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  /**
  * Clase: eventosCrecimiento_y_desarrollo.php
  *
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  
  /*
  *Funcion que me muestra el puntaje de las escalas abreviadas del desarrollo
  *@param array $form: trae los datos del formulario de la FormaMostrarPuntajes
  *
  * @return Object
  */
  function MostrarPuntaje($form)
  {
    $objResponse = new xajaxResponse();  
    //$objResponse->alert(print_r($form,true));
    $arreglo = array();
    foreach($form['escalas'] as $key => $dtl)
    {
      $mayor = 0;
      foreach($dtl as $k1 => $dt)
      {
        if($dt > $mayor)
        $mayor  = $dt;
        $total = $mayor + $mayor;
      }
      $arreglo[$key] = $mayor;  
    }
    $html.="  <table class=\"modulo_table_list\" width=\"90%\" align=\"center\">\n";
    $html.="      <tr class=\"formulacion_table_list\">";
    $html.="          <td colspan=\"2\">PUNTAJES</td>";
    $html.="      </tr>";      
      
    foreach($form['nombre_escala'] as $key => $dtl)
    {
      $html.="    <tr >\n"; 
      $html.="        <td>".$dtl."</td>";
      $html.="        <td>".$arreglo[$key]."</td>";
      $html.="    </tr>";
    }
    $html.="    <tr>";
    $html.="    <td>TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$total."</td>";
    $html.="    <tr>";
    $html.="    <tr class=\"formulacion_table_list\"><td colspan=\"2\">";
    $html.="            <input type=\"button\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\" onclick=\"xajax_GuardarPuntuacion(xajax.getFormValues('puntaje'))\">";  
    $html.="    </tr>";
    $html.="  </table>\n";
    $objResponse->assign("calcular_puntaje","innerHTML",$html);
    $objResponse->script("MostrarSpan('Contenedor');Iniciar()");
    return $objResponse;
  }
  
  /*
  *Funcion que guarda la puntuacion de las escalas
  *@param array $form: trae los datos del formulario de la FormaMostrarPuntajes
  *
  * @return Object
  */
  function GuardarPuntuacion($form)
  {
    $objResponse = new xajaxResponse();  
    //$objResponse->alert(print_r($form,true));
    $mdl = AutoCarga::factory('Crecimiento_y_desarrolloSQL', 'classes', 'hc1', 'Crecimiento_y_Desarrollo');

    $insertar_puntaje=$mdl->insertarPuntajes($form);
    if($insertar_puntaje)
        $mensaje='Los datos se han guardados satisfactoriamente';
    else
        $mensaje='Error no se han podido guardar los datos '.$mdl->mensajeDeError;

    $objResponse->assign("mensaje","innerHTML",$mensaje);
    $objResponse->script("OcultarSpan('Contenedor')");
    return $objResponse;
  }
?>