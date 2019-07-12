<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");
include "../../../app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/DocBodegaGeneral/doc_Bodegas_DocBodegaGeneral.class.php";      
 
 /**
  * Funcion que permite mostrar el tiempo de cita
  *
  * @param  array $datos 
  * @return Object $objResponse objeto de respuesta al formulario  
  */
 function GuardarTmp($datos)
 {
    $objResponse = new xajaxResponse();
    $empresa_id = SessionGetVar("empresa_id");
    $mdl = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
    $doc_tmp_id=$mdl->doc_tmp_id();
    $estasalidastmp=$mdl->BuscarDoc();
    if(empty($estasalidastmp))
    {
      $GuardarTmp=$mdl->CrearDoc($doc_tmp_id['?column?'],$empresa_id);
    }
    else
    {
      $html .=" <a href=\"#\" onclick=\"xajax_GuardarOtroTmp('".$doc_tmp_id['?column?']."')\">CREAR DOC</a>\n";
    }
    $ListarProductos=$mdl->BuscarDoc();
    // $html .= "<pre>".print_r($ListarProductos,true)."</pre>"; 
    foreach($ListarProductos as $key => $valor)
		{ 
      $productos_tmp=$mdl->ProductosTMP($valor['doc_tmp_id']);
      $contar=count($productos_tmp);
      $html .= " <table width=\"90%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td  align=\"center\"width=\"20%\">DOC TMP\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\"colspan=\"2\">DESCRIPCION\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\"colspan=\"1\">PRODUCTOS\n";
      $html .= "    </td>\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\"colspan=\"1\">GUARDAR\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
     //$html .= "<pre>".print_r($valor,true)."</pre>";
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['doc_tmp_id']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\"colspan=\"2\">EGRESO POR SALIDA DE PRODUCTOS\n";
      $html .= "    </td>\n";
      $html .=     "<td align=\"center\">";
      
      $entm_d=$mdl->Doc_tmp($valor['doc_tmp_id']);
     
      //print_r($productos_tmp);
      $html .="     <a href=\"#\" onclick=\"xajax_ProductosListas('".$valor['doc_tmp_id']."')\">\n";
      $html .="      <img title=\"PRODUCTOS\" src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>\n";
      $html .= "    </td>\n";
      $html .= "    <td>\n";
      $html .="    <a href=\"#\" onclick=\"xajax_GuardarDocumentoReal('".$valor['doc_tmp_id']."','".$valor['codigo_producto']."','".$valor['existencia_actual']."','','".$valor['costo']."','".$valor['fecha_vencimiento']."','".$valor['lote']."')\">\n";
      $html .="    <img title=\"GUARDAR\" src=\"".GetThemePath()."/images/guarda.png\" border=\"0\"></a>\n";
      $html .="   <a name=\"mitad\">";
      $html .= "    </td>\n";
      //}
      $html .= "  </td>\n";
      $html .= " </tr>\n";
    
    //$html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    if($productos_tmp)
    {
      $html .= "  <tr class=\"modulo_table_list_title\">\n";
      $html .= "    <td  align=\"center\"width=\"20%\">CODIGO PRODUCTO\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\">DESCRIPCION\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\">FECHA VENC\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\">LOTE\n";
      $html .= "    </td>\n";
      $html .= "    <td  align=\"center\"width=\"20%\">CANTIDAD\n";
      $html .= "    </td>\n";
      $html .= " </tr>\n";
    }
    for($i=0;$i<$contar;$i++)
    {
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$productos_tmp[$i]['codigo_producto']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$productos_tmp[$i]['descripcion']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$productos_tmp[$i]['fecha_vencimiento']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$productos_tmp[$i]['lote']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$productos_tmp[$i]['cantidad']."\n";
      $html .= "    </td>\n";
      $html .= " </tr>\n";
 
     }
    }
    //$html .= " </table>\n";
    $html .= " </table>\n";
   
    $objResponse->assign("productos","innerHTML",$html);
    return $objResponse;
  }
  
  function GuardarOtroTmp($datos)
  {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
    $empresa_id = SessionGetVar("empresa_id");
    $GuardarTmp=$mdl->CrearDoc($datos,$empresa_id);
    /*$url=ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos",array("prefijo"=>$GuardarTmp['prefijo'],"numero"=>$GuardarTmp['numero'],"doc_id"=>$GuardarTmp['documento_id']));
    $objResponse->script('window.location="'.$url.'";');*/
    $objResponse->assign("productos","innerHTML",$html);
    return $objResponse;
  }
 /**
  * Funcion que permite mostrar el tiempo de cita
  *
  * @param  array $datos 
  * @return Object $objResponse objeto de respuesta al formulario  
  */
 function ProductosListas($doc_tmp_id,$offset)
 {
    $objResponse = new xajaxResponse();
    $empresa_id = SessionGetVar("empresa_id");
     
    $mdl = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
    
    $ListarProductos=$mdl->ListarProductos($empresa_id,$offset);
    $action['paginador'] = "paginador('".$doc_tmp_id."'";
    $html  = "<BR>\n";
    $html .= " <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "   <tr class=\"label_error\">\n";
    $html .= "     <td width=\"50%\" style=\"background:#FF0000;color:#FFFFFF\"></td>\n";
    $html .= "     <td width=\"50%\" align=\"center\">PRODUCTOS PROXIMOS A VENCER</td>\n";
    $html .= "   </tr>\n";
    $html .= " </table><br>\n";
    
    $html .= "<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td  align=\"center\"width=\"20%\">CODIGO PRODUCTO\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\"width=\"20%\">DESCRIPCION\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\"width=\"10%\">BODEGA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"15%\">FECHA VENCIMIENTO\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"5%\">LOTE\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"5%\">EXISTENCIA\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\" width=\"2%\">SEL\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    
    $pghtml = AutoCarga::factory('ClaseHTML');
    $i=1;
    $k=1;
  
    foreach($ListarProductos as $key => $valor)
		{ 
      $fech_vencmodulo=ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.trim($empresa_id).'');
      $fecha_actual=date("m/d/Y");
      /*
                * Para Sacar los numeros de días entre fechas
                */      
     $fecha =$valor['fecha_vencimiento'];  //esta es la que viene de la DB
      list( $ano, $mes, $dia ) = split( '[/.-]', $fecha );
      $fecha = $mes."/".$dia."/".$ano;
      
      //Mes/Dia/Año  "02/02/2010
      $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
	  
	  $bck = ($int_nodias<=$fech_vencmodulo)? "style=\"background:#FF0000;color:#FFFFFF\"":"";
     // $objResponse->alert("Distacia: ".$int_nodias);  
      //$objResponse->alert("Actual: ".$fech_vencmodulo);  
      
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td ".$bck." align=\"center\">".$valor['codigo_producto']."\n";
      $html .= "    </td>\n";
      $html .= "    <td ".$bck." align=\"center\">".$valor['descripcion_prod']."\n";
      $html .= "    </td>\n";
      $html .= "    <td ".$bck." align=\"center\">".$valor['bodega']."\n";
      $html .= "    </td>\n";
      
      
      $html .= "    <td ".$bck." align=\"center\">".$valor['fecha_vencimiento']."\n";
      $html .= "    </td>\n";
      $html .= "    <td ".$bck." align=\"center\">".$valor['lote']."\n";
      $html .= "    </td>\n";
      $html .= "    <td ".$bck." align=\"center\">".$valor['existencia_actual']."\n";
      $html .= "    </td>\n";
       
      $html .= "<td ".$bck." align=\"center\">
             <a href=\"#mitad\" onclick=\"xajax_GuardarTmpD('".$doc_tmp_id."','".$valor['codigo_producto']."','".$valor['existencia_actual']."','','".$valor['costo']."','".$valor['fecha_vencimiento']."','".$valor['lote']."')\">\n";
      $html .="<img title=\"GUARDAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
      
      $html .= "  </tr>\n";
      $i++;
      $k++;
      
    }
    
    $html .= "</table>\n";
    $html .= $pghtml->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action['paginador']);
    $html .= "<BR>\n";
    $html .= " <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "   <tr class=\"label_error\">\n";
    $html .= "     <td width=\"50%\" style=\"background:#FF0000;color:#FFFFFF\"></td>\n";
    $html .= "     <td width=\"50%\" align=\"center\">PRODUCTOS PROXIMOS A VENCER</td>\n";
    $html .= "   </tr>\n";
    $html .= " </table><br>\n";
    
    $objResponse->assign("Contenido","innerHTML",$html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
 /**
   * Funcion que donde crea el documento temporal
   *
   * @param  array $datos 
   * @return Object $objResponse objeto de respuesta al formulario  
  */
 function GuardarTmpD($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$fecha_venc,$lotec)
 {
    $objResponse = new xajaxResponse();
    $empresa_id = SessionGetVar("empresa_id");
    $mdl = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
    /*print_r($doc_tmp_id."DOCUMENTO ID   ");
    print_r($codigo_producto."CODIGO PRODUCTO    ");
    print_r($cantidad."CANTIDAD    ");
    print_r($porcentaje_gravamen."PORCENTAJE   ");
    print_r($total_costo."TOTAL COSTO   ");
    print_r($fecha_venc."FECHA VENC   ");
    print_r($lotec."LOTE   ");*/
    $EnTmp=$mdl->ProductosTemporal($doc_tmp_id,$empresa_id,$codigo_producto);
    //print_r($EnTmp);
    if(empty($EnTmp))
    {
      $GuardarTmp=$mdl->CrearDocTmp($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$fecha_venc,$lotec,$empresa_id);
    }
    else
    {  
      $objResponse->script("alert('ESTE PRODUCTO SE ENCUENTRA EN EL DOCUMENTO TEMPORAL');");
    }  
    $objResponse->script("xajax_GuardarTmp();");
    return $objResponse;
 }

 function GuardarDocumentoReal($doc_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$fecha_venc,$lotec)
 {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory('SalidasProductosSQL', '', 'app', 'SalidasProductos');
    $action['subir_imagen']= ModuloGetURL("app", "SalidasProductos", "controller", "Subirimagen");
    //print_r($doc_id);
    $valida_jefes=$mdl->validar_jefes($doc_id);
    //print_r($valida_jefes);
    $empresa_id = SessionGetVar("empresa_id");
    if($valida_jefes['sw_jefebodega']==1 and $valida_jefes['sw_jefecontroli']==1)
    {
      $GuardarTmp=$mdl->CrearDocReal($doc_id,$empresa_id);
      
      //print_r($GuardarTmp);
      
      if(!empty($GuardarTmp))
      {
        $BorrarSal=$mdl->Borrarpara_docs($doc_id);
        $url=ModuloGetURL("app", "SalidasProductos", "controller", "ListadoProductos",array("prefijo"=>$GuardarTmp['prefijo'],"numero"=>$GuardarTmp['numero'],"doc_id"=>$GuardarTmp['documento_id']));
        $objResponse->script('
					 window.location="'.$url.'";
								');
       
      
      
        /*
        $html .= " <td align=\"center\">";
        $html .= "	<a href=\"javascript:WindowPrinter0001()\" class=\"label_error\">GENERAR REPORTE DE SALIDA DE PRODUCTOS  <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\" title=\"GENERAR REPORTE DE LOS PRODUCTOS NO CUADRADOS EN CONTEO 1 \"></sub>&nbsp;</a>\n";
        $html .= " </td>\n";
        $html .= "    <td align=\"center\">\n";
        $html .= "      <a href=\"".$action['subir_imagen']."\" class=\"label_error\">SUBIR IMAGEN</a>\n";
        $html .= "    </td>\n";*/
      }
      else
      {
        $objResponse->alert($mdl->mensajeDeError);
        return $objResponse;
      }
    }
    else
    {
      $html .= "    <td align=\"center\">NO SE PUEDE GENERAR EL DOCUMENTO FALTA VALIDACIONES\n";
      $html .= "    </td>\n";
    }  
      
     
    $objResponse->assign("productos","innerHTML",$html);
    return $objResponse;
 }
?>