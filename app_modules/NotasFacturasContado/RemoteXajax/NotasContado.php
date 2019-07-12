<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: NotasContado.php,v 1.1 2010/03/09 13:40:54 hugo Exp $
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
  * Funcion donde se evalua los datos adicionales solicitados por el concepto
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function ActivarSeleccion($form)
  {
    $objResponse = new xajaxResponse();
    $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
    
    $scpt = "";
    if($form['concepto'] != '-1')
    {
      $detalle = $cls->ObtenerInformacionConcepto($form['concepto']);
      if($detalle['sw_centro_costo'] == '1')
      	$scpt  = "	document.adicionarconcepto.departamento.disabled = false;\n";
      if($detalle['sw_tercero'] == '1')
        $scpt .= "	document.adicionarconcepto.boton_tercero.disabled = false;\n";
    }
    else
    {
    	$scpt  = "	document.adicionarconcepto.departamento.disabled = true;\n";
      $scpt .= "	document.adicionarconcepto.boton_tercero.disabled = true;\n";
    }
    $objResponse->script($scpt);
    return $objResponse;
  }
  /**
  * Funcion donde se evalua la informacion ingresada para el concepto
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function AdicionarConcepto($form)
  {
    $objResponse = new xajaxResponse();
    $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
    
    $mensaje = "";
    if(!is_numeric($form['valor_concepto']))
      $mensaje = "<label class=\"label_error\">EL VALOR INGRESADO DEL CONCEPTO ES NULO O POSEE UN FORMATO INVALIDO</label>";
    
    $detalle = $cls->ObtenerInformacionConcepto($form['concepto']);
    if($detalle['sw_centro_costo'] == '1' && $form['departamento'] == '-1')
      $mensaje = "<label class=\"label_error\">NO SE HA SELECCIONADO DEPARTAMENTO ASOCIADO</label>";

    if($detalle['sw_tercero'] == '1' && $form['tercero_id'] == "")
      $mensaje = "<label class=\"label_error\">NO SE HA SELECCIONADO TERCERO ASOCIADO</label>";
    
    $form['mensaje'] = $mensaje;
    if(!$mensaje)
    {
      $form['naturaleza_concepto'] = $detalle['naturaleza'];
      $form['usuario_id'] = UserGetUID();
      $rst = $cls->IngresarTemporalNota($form);
      if(!$rst)
        $mensaje = "<label class=\"label_error\">".$cls->mensajeDeError."</label>";
      else
      {
        if(!$form['tmp_nota_id'])
        {
          $objResponse->assign("tmp_nota_id_a","value",$cls->tmp_id);
          $objResponse->assign("tmp_nota_id_b","value",$cls->tmp_id);
          $objResponse->assign("tmp_nota_id_c","value",$cls->tmp_id);
          $form['tmp_nota_id'] = $cls->tmp_id;
        }
       	
        $conceptosadd = $cls->ObtenerConceptosTmp($form);
        
        $mdl = AutoCarga::factory("FacturasHTML","views","app","NotasFacturasContado");
        $html = $mdl->CrearListaConceptos($conceptosadd);
        
        $mensaje ="<label class=\"normal_10AN\">EL CONCEPTO SE HA AGREHADO CORRECTAMENTE</label>";
        $objResponse->assign("lista_conceptos","innerHTML",$html);
        
        $scpt  = "	document.adicionarconcepto.departamento.selectedIndex = 0;\n";
        $scpt .= "	document.adicionarconcepto.concepto.selectedIndex = 0;\n";
        $scpt .= "	document.adicionarconcepto.departamento.disabled = true;\n";
        $scpt .= "	document.adicionarconcepto.boton_tercero.disabled = true;\n";
				$scpt .= "	document.adicionarconcepto.nombre_tercero.value='';\n";
				$scpt .= "	document.adicionarconcepto.tipo_id_tercero.value='';\n";					
				$scpt .= "	document.adicionarconcepto.tercero_id.value='';\n";					
				$scpt .= "	document.adicionarconcepto.valor_concepto.value='';\n";					
        
        $objResponse->script($scpt);
      }
    }
    $objResponse->assign("error","innerHTML",$mensaje);
    return $objResponse;
  } 
  /**
  * Funcion donde se elimina un concepto
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function EliminarConcepto($form,$concepto)
  {
    $objResponse = new xajaxResponse();
    $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
    
    $rst = $cls->EliminarConceptoTmp($concepto);
    if(!$rst)
      $mensaje = "<label class=\"label_error\">".$cls->mensajeDeError."</label>";
    else
    {
      $conceptosadd = $cls->ObtenerConceptosTmp($form);
        
      $mdl = AutoCarga::factory("FacturasHTML","views","app","NotasFacturasContado");
      $html = $mdl->CrearListaConceptos($conceptosadd);
      $objResponse->assign("lista_conceptos","innerHTML",$html);
      
      $mensaje ="<label class=\"normal_10AN\">EL CONCEPTO SE HA ELIMINADO CORRECTAMENTE</label>";
    }
    $objResponse->assign("error","innerHTML",$mensaje);
    return $objResponse;
  }   
  /**
  * Funcion donde se actualiza la informacion de la nota
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function InformacionNota($form)
  {
    $objResponse = new xajaxResponse();
    $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
    
    $form['observa'] = utf8_decode($form['observa']);
    $form['usuario_id'] = UserGetUID();
    $objResponse->alert(print_r($form,true));
    
    $rst = $cls->RegistrarTemporalNota($form);
    if(!$rst)
      $mensaje = "<label class=\"label_error\">".$cls->mensajeDeError."</label>";
    else
    {
      if(!$form['tmp_nota_id'])
      {
        $objResponse->assign("tmp_nota_id_a","value",$cls->tmp_id);
        $objResponse->assign("tmp_nota_id_b","value",$cls->tmp_id);
        $objResponse->assign("tmp_nota_id_c","value",$cls->tmp_id);
        $form['tmp_nota_id'] = $cls->tmp_id;
      }
      $mensaje ="<label class=\"normal_10AN\">LA INFORMACION DE LA NOTA HA SIDO ACTUALIZADA CORRECTAMENTE</label>";
    }
    $objResponse->assign("error","innerHTML",$mensaje);
    $objResponse->script("OcultarSpan('Contenedor');");
    return $objResponse;
  }  
  /**
  * Funcion donde se listan los conceptos adicionados
  *
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function ListarConceptos($form)
  {
    $objResponse = new xajaxResponse();
    $cls = AutoCarga::factory("NotasFacturas","classes","app","NotasFacturasContado");
    $mdl = AutoCarga::factory("FacturasHTML","views","app","NotasFacturasContado");
    
    $conceptosadd = $cls->ObtenerConceptosTmp($form);
    $html = $mdl->CrearListaConceptos($conceptosadd);
    $objResponse->assign("lista_conceptos","innerHTML",$html);

    return $objResponse;
  }
?>