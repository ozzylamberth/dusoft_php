<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Proveedor.php,v 1.3 2009/02/04 14:19:51 hugo Exp $
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
  * Funcion para la adicion de cargos
  *
  * @param array $form Vector con los datos de la forma
  * @param string $cups Identificador del cargos cups
  * @param string $equival Identificador del cargo equivalente
  * @param string $proveedor Identificador del proveedor
  *
  * @return object
  */
  function AdicionarCargos($form,$cups,$equival,$proveedor,$solicitud)
  {
    $objResponse = new xajaxResponse();
    $cargos_add = SessionGetVar("CargosProveedores");

    foreach($cargos_add as $key => $dtl)
    {
      unset($cargos_add[$key][$cups]);
      if(empty($cargos_add[$solicitud][$key])) unset($cargos_add[$solicitud][$key]);
      if(empty($cargos_add[$solicitud])) unset($cargos_add[$solicitud]);
    }
    $cargos_add[$proveedor][$cups] = $proveedor;
    SessionSetVar("CargosProveedores",$cargos_add);
    
    $cargos = SessionGetVar("Equivalencias");
    $cargos[$solicitud][$cups][$equival] = $form['cargos'][$solicitud][$cups][$equival];
    SessionSetVar("Equivalencias",$cargos);
    
    $html  = "       <a title=\"SELECCIONAR\" href=\"javascript:EliminarCargo('".$cups."','".$equival."')\">\n";
    $html .= "         <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
    $html .= "       </a>\n";
    
    $objResponse->assign("imagen_".$equival,"innerHTML",$html);
    
    $html  = " window.opener.document.getElementsByName('cargos[".$solicitud."][".$cups."][proveedor]')[0].value = '".$proveedor."';\n";
    $html .= " window.opener.document.getElementsByName('cargos[".$solicitud."][".$cups."][cargo]')[0].checked = true;\n";
    $objResponse->script($html);
    
    return $objResponse;
  }
  /**
  * Funcion para la eliminacion de cargos
  *
  * @param string $cups Identificador del cargos cups
  * @param string $equival Identificador del cargo equivalente
  *
  * @return object
  */
  function EliminarCargos($cups,$equival,$solicitud)
  {
    $objResponse = new xajaxResponse();
    
    $cargos = SessionGetVar("Equivalencias");
    unset($cargos[$solicitud][$cups][$equival]);
    if(empty($cargos[$solicitud][$cups])) unset($cargos[$solicitud][$cups]);
    if(empty($cargos[$solicitud])) unset($cargos[$solicitud]);
    
    SessionSetVar("Equivalencias",$cargos);
    
    $html  = "       <a title=\"SELECCIONAR\" href=\"javascript:AdicionarCargo('".$cups."','".$equival."')\">\n";
    $html .= "         <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
    $html .= "       </a>\n";
    
    $objResponse->assign("imagen_".$equival,"innerHTML",$html);
    $html  = " window.opener.document.getElementsByName('cargos[".$solicitud."][".$cups."][proveedor]')[0].value = '';\n";
    $html .= " window.opener.document.getElementsByName('cargos[".$solicitud."][".$cups."][cargo]')[0].checked = false;\n";
    $objResponse->script($html);
    
    return $objResponse;
  }
  /**
  * Funcion para la adicion de medicamentos
  *
  * @param array $form Vector con los datos de la forma
  * @param string $producto Identificador del producto
  * @param string $proveedor Identificador del proveedor
  *
  * @return object
  */
  function AdicionarMedicamentos($form,$producto,$proveedor)
  {
    $objResponse = new xajaxResponse();
    $medica_add = SessionGetVar("MedicamentosProveedores");

    foreach($medica_add as $key => $dtl)
    {
      unset($medica_add[$key][$producto]);
      if(empty($medica_add[$key])) unset($medica_add[$key]);
    }
    $medica_add[$proveedor][$producto] = $proveedor;
    SessionSetVar("MedicamentosProveedores",$medica_add);
    
    $seleccionados = SessionGetVar("Medicamentos");
    $seleccionados[$producto] = $form['medicamento'][$producto];
    SessionSetVar("Medicamentos",$seleccionados);
    
    $html  = "       <a title=\"SELECCIONAR\" href=\"javascript:EliminarMedicamento('".$producto."')\">\n";
    $html .= "         <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
    $html .= "       </a>\n";
    
    $objResponse->assign("imagen_".$producto,"innerHTML",$html);
    $html  = " window.opener.document.getElementsByName('medicamento[".$producto."][proveedor]')[0].value = '".$proveedor."';\n";
    $html .= " window.opener.document.getElementsByName('medicamento[".$producto."][producto]')[0].checked = true;\n";
    $objResponse->script($html);
    
    return $objResponse;
  }
  /**
  * Funcion para la eliminacion de medicamentos
  *
  * @param string $producto Identificador del producto
  *
  * @return object
  */
  function EliminarMedicamentos($producto)
  {
    $objResponse = new xajaxResponse();
    
    $medica_add = SessionGetVar("Medicamentos");
    unset($medica_add[$producto]);
    
    SessionSetVar("Medicamentos",$medica_add);
    
    $html  = "       <a title=\"SELECCIONAR\" href=\"javascript:AdicionarMedicamento('".$producto."')\">\n";
    $html .= "         <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
    $html .= "       </a>\n";
    
    $objResponse->assign("imagen_".$producto,"innerHTML",$html);
    $html  = " window.opener.document.getElementsByName('medicamento[".$producto."][proveedor]')[0].value = '';\n";
    $html .= " window.opener.document.getElementsByName('medicamento[".$producto."][producto]')[0].checked = false;\n";

    $objResponse->script($html);
    
    return $objResponse;
  }
?>