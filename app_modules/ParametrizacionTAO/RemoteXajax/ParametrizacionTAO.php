<?php

  /**
  * Funcion que es llaamdo por xajas para aignar el esatdo de un medicamento en tao.
  * @param string $empresa_id, id de la empresa.
  * @param string $codigo_medicamento, codigo del medicamento a selecionar
  * @return object $objResponse, objecto del xajas.
  */
  
  function AsignarTao($empresa_id,$codigo_medicamento)
  {
    $objResponse = new xajaxResponse();
    
    $pct = AutoCarga::factory("ParametrizacionTAOSQL", "classes", "app", "ParametrizacionTAO");
    $bool = $pct->AsignarMedicamentoTao($empresa_id,$codigo_medicamento);
    if($bool)
    {
      $objResponse->assign("div_".$empresa_id.$codigo_medicamento,"src",GetThemePath()."/images/checkS.gif");
    }else{
      $objResponse->assign("div_".$empresa_id.$codigo_medicamento,"src",GetThemePath()."/images/checkN.gif");
    }

    return $objResponse;
  }

?>