<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Cuentas.php,v 1.1 2011/07/25 20:37:18 hugo Exp $
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
  * Funcion donde se realiza el ingreso de los valores de descuento
  * sobre los grupos de cargos
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function EvaluarDatosDescuento($form)
  {
    $objResponse = new xajaxResponse();
    $trf = AutoCarga::factory("OpcionesCuentas","classes","app","Cuentas");
    $msj = "";

    if(empty($form['grupo']))
      $msj = "<label class=\"label_error\">NO SE HA SELECCIONADO NINGUN GRUPO PARA REALIZAR EEL DESCUENTO</label>";
    else
    {
      foreach($form['grupo'] as $key => $dtl)
      {
        $f0 = $f1 = false;
        if(!empty($form['descuento_empresa'][$key]))
        {
          if($form['descuento_empresa'][$key] != "")
          {
            if(!is_numeric($form['descuento_empresa'][$key]))
            {
              $msj = "<label class=\"label_error\">EL VALOR INGRESADO DEL DESCUENTO EMPRESA<br>PARA EL GRUPO ".$form['descripcion'][$key].", POSEE UN FORMATO INCORRECTO</label>";
              break;
            }
            $f0 = true;
          }
        }
        if(!empty($form['descuento_paciente'][$key]))
        {
          if($form['descuento_paciente'][$key] != "")
          {
            $f1 = true;
            if(!is_numeric($form['descuento_paciente'][$key]))
            {
              $msj = "<label class=\"label_error\">EL VALOR INGRESADO DEL DESCUENTO DE PACIUENTE<br>PARA EL GRUPO ".$form['descripcion'][$key].", POSEE UN FORMATO INCORRECTO</label>";
              break;
            }
          }
        }
        if(!$f0 && !$f1)
          $msj = "<label class=\"label_error\">NO SE HAN INGRESADO VALORES DE DESACUENTO<br>PARA EL GRUPO ".$form['descripcion'][$key]." </label>";
      }
    }

    if($msj == "")
    { 
      $msj = "<label class=\"normal_10AN\">DATOS INGRESADOS CORRECTAMENTE</label>\n";
      $rst = $trf->IngresarDescuentos($form);
      if(!$rst)
        $msj = "<label class=\"label_error\">".$trf->mensajeDeError."</label>";
    }
    $objResponse->assign("error","innerHTML",$msj);
    return $objResponse;
  }
?>