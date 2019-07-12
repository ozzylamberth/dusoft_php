<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Tarifarios.php,v 1.3 2008/07/14 15:27:43 hugo Exp $
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
  * Funcion donde se llena el select de subgrupos tarifario
  *
  * @param string $grupotarifario Identificador del grupo tarifario
  * @param string $tarifario Identificador del tarifario
  *
  * @return object
  */
  function SeleccionarSubGrupos($grupotarifario,$tarifario)
  {
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $subgrupo = $cxp->ObtenerSubgruposTarifarios($grupotarifario,$tarifario);

    $html  = "document.formaseleccionar.subgrupo_tarifario_id.options.length = 0 ;\n";
    $html .= "document.formaseleccionar.subgrupo_tarifario_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($subgrupo as $key => $dtl)
    {
      $html .= "document.formaseleccionar.subgrupo_tarifario_id.options[".$i."] = new Option('".utf8_encode(substr($dtl['subgrupo_tarifario_descripcion'],0,40))."','".$dtl['subgrupo_tarifario_id']."',false, false);\n";
      $html .= "document.formaseleccionar.subgrupo_tarifario_id.options[".($i++)."].title = '".utf8_encode($dtl['subgrupo_tarifario_descripcion'])."';\n";
    }
    $objResponse = new xajaxResponse();
    $objResponse->script($html);

    return $objResponse;
  }
  /**
  * Funcion donde se llena el select de tarifarios
  *
  * @param string $tipotarifario Identificador del tipo tarifario
  *
  * @return object
  */
  function SeleccionarTarifario($tipotarifario)
  {
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $tarifario = $cxp->ObtenerTarifarios($tipotarifario);

    $html  = "document.formaseleccionar.grupo_tarifario_id.options.length = 0 ;\n";
    $html .= "document.formaseleccionar.grupo_tarifario_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $html .= "document.formaseleccionar.subgrupo_tarifario_id.options.length = 0 ;\n";
    $html .= "document.formaseleccionar.subgrupo_tarifario_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $html .= "document.formaseleccionar.tarifario_id.options.length = 0 ;\n";
    $html .= "document.formaseleccionar.tarifario_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($tarifario as $key => $dtl)
    {
      $html .= "document.formaseleccionar.tarifario_id.options[".$i."] = new Option('".utf8_encode(substr($dtl['descripcion'],0,40))."','".$dtl['tarifario_id']."',false, false);\n";
      $html .= "document.formaseleccionar.tarifario_id.options[".($i++)."].title = '".utf8_encode($dtl['descripcion'])."';\n";
    }
    $objResponse = new xajaxResponse();
    $objResponse->script($html);

    return $objResponse;
  }  
  /**
  * Funcion donde se llena el select de grupos tarifarios
  *
  * @param string $tarifario identificador del tarifario
  *
  * @return object
  */
  function SeleccionarGrupos($tarifario)
  {
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $grupo = $cxp->ObtenerGruposTarifarios($tarifario);

    $objResponse = new xajaxResponse();
    if($grupo === false)
      $objResponse->alert($cxp->mensajeDeError);
      
    $html  = "document.formaseleccionar.grupo_tarifario_id.options.length = 0 ;\n";
    $html .= "document.formaseleccionar.grupo_tarifario_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
    $html .= "document.formaseleccionar.subgrupo_tarifario_id.options.length = 0 ;\n";
    $html .= "document.formaseleccionar.subgrupo_tarifario_id.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($grupo as $key => $dtl)
    {
      $html .= "document.formaseleccionar.grupo_tarifario_id.options[".$i."] = new Option('".utf8_encode(substr($dtl['grupo_tarifario_descripcion'],0,40))."','".$dtl['grupo_tarifario_id']."',false, false);\n";
      $html .= "document.formaseleccionar.grupo_tarifario_id.options[".($i++)."].title = '".utf8_encode($dtl['grupo_tarifario_descripcion'])."';\n";
    }
    $objResponse->script($html);

    return $objResponse;
  }
  /**
  * Funcion donde se hace la busqueda de los cargos y se muestran en pantalle
  * 
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function BuscarCargos($form)
  {
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $mdl = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
    
    $lista = $cxp->ObtenerCargos($form);
    $html = $mdl->ListaCargos($lista,$form['tarifario_id']);
    
    $html = utf8_encode($html);
    
    $objResponse = new xajaxResponse();
    $objResponse->assign("lista_precios","innerHTML",$html);

    return $objResponse;
  }  
  /**
  * Funcion por la cual se vincula a un proveedor con una lista
  * 
  * @param int $form Arreglo de datos con la informacion de la forma
  * @param int $lista Identificador de una lista
  *
  * @return object  
  */
  function VincularProveedor($form,$lista)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $mdl = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
    
    $rst = $cxp->VincularProveedor($form,$lista);
    if($rst === false)
      $objResponse->alert($cxp->mensajeDeError);
    
    $listado = $cxp->ObtenerProveedoresLista($lista);
    $proveedores = $cxp->ObtenerProveedores($lista);
    
    $scp .= "document.buscador.codigo_proveedor.options.length = 0 ;\n";
    $scp .= "document.buscador.codigo_proveedor.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($proveedores as $key => $dtl)
      $scp .= "document.buscador.codigo_proveedor.options[".($i++)."] = new Option('".utf8_encode($dtl['nombre_tercero'])."','".$dtl['codigo_proveedor_id']."',false, false);\n";
   
    $html = $mdl->ListadoProveedores($listado);
    $html = utf8_encode($html);
    
    
    $objResponse->script($scp);
    $objResponse->assign("numero_contrato","value","");
    $objResponse->assign("proveedores_asociados","innerHTML",$html);

    return $objResponse;
  }  
  /**
  * Funcion por la cual se desvincula a un proveedor con una lista
  * 
  * @param int $codigo_proveedor Identificador del proveedor
  * @param int $lista Identificador de una lista
  *
  * @return object  
  */
  function DesvincularProveedor($codigo_proveedor,$lista)
  {
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $mdl = AutoCarga::factory('ListaPreciosHTML','views','app','ListasPreciosCargos');
    
    $rst = $cxp->DesvincularProveedor($codigo_proveedor,$lista);
    
    $listado = $cxp->ObtenerProveedoresLista($lista);
    $proveedores = $cxp->ObtenerProveedores($lista);

    $scp .= "document.buscador.codigo_proveedor.options.length = 0 ;\n";
    $scp .= "document.buscador.codigo_proveedor.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";

    $i = 1;
    foreach($proveedores as $key => $dtl)
      $scp .= "document.buscador.codigo_proveedor.options[".($i++)."] = new Option('".utf8_encode($dtl['nombre_tercero'])."','".$dtl['codigo_proveedor_id']."',false, false);\n";
   
    $html = $mdl->ListadoProveedores($listado);
    $html = utf8_encode($html);
    
    $objResponse = new xajaxResponse();
    $objResponse->script($scp);
    $objResponse->assign("proveedores_asociados","innerHTML",$html);

    return $objResponse;
  }
  /**
  * Funcion mediante la cual se vinculan los cargos seleccionados a una lista
  * 
  * @param array $form Arreglo de datos de la forma
  *
  * @return object
  */
  function AdicionarCargos($form)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $rst = $cxp->RegistrarCargosLista($form['cargos'],$form['lista_codigo'],$form['tarifario_id']);
    
    if($rst === false)
      $mensaje = "<label class=\"label_error\">HA OCURRIDO EL SIGUIENTE ERROR :".$cxp->mensajeDeError."<label>";
    else
      $mensaje = "<label class=\"normal_10AN\">LOS CARGOS SELECCIONADOS SE HAN ADICIONADO A LA LISTA Nº: ".$form['lista_codigo']."</label>";
    
    $objResponse->assign("lista_precios","innerHTML",$mensaje);
    return $objResponse;
  }
  /**
  * Funcion mediante la cual se actualiza el valor de un cargo en la lista
  *
  * @param int $lista_codigo Identificador de una lista
  * @param string $tarifario_id Identificador del tarifario
  * @param string $cargo Identificador del cargo
  * @param float $precio Precio del cargo
  * @param float $porcentaje Porcentaje de modificacion
  *
  * @return boolean
  */
  function ModificarValor($lista_codigo,$tarifario_id,$cargo,$precio,$porcentaje,$i)
  {
    $objResponse = new xajaxResponse();
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $rst = $cxp->ActualizarValor($lista_codigo,$tarifario_id,$cargo,$precio,$porcentaje);
        
    if($rst === false)
    {
      $mensaje = "HA OCURRIDO EL SIGUIENTE ERROR :".$cxp->mensajeDeError."";
      $objResponse->alert($mensaje);
    }
    else
    {
      $valor = formatovalor($precio + ($precio * $porcentaje/100));
      $objResponse->assign("div_".$i,"innerHTML","$".$valor);
    }
    return $objResponse;
  }
  /**
  *
  */
  function MostrarTiposAfiliados($plan_id,$lista)
  {
    $objResponse = new xajaxResponse();
    
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    $tipos = $cxp->ObtenerTiposAfiliados();
    $cobertura = $cxp->ObtenerPorcentajeCobertura($plan_id,$lista);
    $html = FormaTiposAfiliados($plan_id,$lista,$tipos,$cobertura);
    
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("erroro","innerHTML","");
    $objResponse->call("MostrarSpan");
    
    return $objResponse;
  }  
  /**
  *
  */
  function RegistrarCobertura($form,$plan_id,$lista,$opcion)
  {
    $objResponse = new xajaxResponse();
    
    foreach($form['tipo_afiliado'] as $key => $dtl)
    {
      if($dtl == "")
      {
        $objResponse->assign("erroro","innerHTML","POR FAVOR INGRESAR LOS VALORES DEL PORCENTAJE DE COBERTURA");
        return $objResponse;
      }
      if(!is_numeric($dtl))
      {
        $objResponse->assign("erroro","innerHTML","EL FORMATO DE UNO DE LOS VALORES INGRESADOS NO ES CORRECTO");
        return $objResponse;
      }
    }
    
    $cxp = AutoCarga::factory('ListaPrecios','','app','ListasPreciosCargos');
    if($opcion > 0)
      $rst = $cxp->ActualizarPorcentajeCobertura($form,$plan_id,$lista);
    else
      $rst = $cxp->IngresarPorcentajeCobertura($form,$plan_id,$lista);
    
    $mensaje = "LOS PORCENTAJES DE COBERTURA FUERON INGRESADOS SATISFACTORIAMENTE";
    
    if(!$rst)
      $mensaje =  "HA OCURRIDO UN ERROR: ".$cxp->mensajeDeError;
    
    $html = FormaMensaje($mensaje);
    $objResponse->assign("ventana","innerHTML",$html);
    $objResponse->assign("erroro","innerHTML","");
    
    return $objResponse;
  }
  /**
  * 
  *
  * @return string
  */
  function FormaTiposAfiliados($plan_id,$lista,$tipos,$cobertura)
  {
    $html  = "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"label\">INGRESO PORCENTAJES DE COBERTURA</legend>\n";
    $html .= "	<table class=\"modulo_table_list\" width=\"100%\" align=\"center\">\n";
    foreach($tipos as $key => $dtl)
    {
      $html .= "	  <tr>\n";
      $html .= "	    <td class=\"formulacion_table_list\">".$dtl['descripcion_eps_tipo_afiliado']."</td>\n";
      $html .= "			<td >\n";
      $html .= "		    <input type=\"text\" class=\"input-text\" name=\"tipo_afiliado[".$key."]\" style=\"width:70%\" value=\"".$cobertura[$key]['porcentaje_cobertura']."\" onkeyPress=\"return acceptNum(event)\">\n";
      $html .= "			</td>\n";
      $html .= "	  </tr>\n";
    }
    $opcion = sizeof($cobertura);
    
    $html .= "	</table>\n";
    $html .= "</fieldset>\n";
    $html .= "<table align=\"center\" >\n";
    $html .= "	<tr>\n";
    $html .= "	  <td align=\"center\" >\n";
    $html .= "		  <input  type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\" onclick=\"xajax_RegistrarCobertura(xajax.getFormValues('oculta'),'".$plan_id."','".$lista."','".$opcion."')\">\n";
    $html .= "		</td>\n";
    $html .= "		<td align=\"center\" >\n";
    $html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Cancelar\" onclick=\"OcultarSpan()\" >\n";
    $html .= "		</td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    return $html;
  }
  /**
  * Funcion donde se crea un mensaje, que sera mostrado en una capa
  *
  * @param string $mensaje Texto que se mostrara en pantalla
  *
  * @param string
  */
  function FormaMensaje($mensaje)
  {
    $html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
    $html .= "	<tr>\n";
    $html .= "		<td>\n";
    $html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
    $html .= "		    <tr class=\"normal_10AN\">\n";
    $html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
    $html .= "		    </tr>\n";
    $html .= "		  </table>\n";
    $html .= "		</td>\n";
    $html .= "	</tr>\n";
    $html .= "	<tr>\n";
    $html .= "		<td align=\"center\"><br>\n";
    $html .= "			<input  type=\"button\" class=\"input-submit\" name=\"cancelar\" value=\"Aceptar\" onclick=\"OcultarSpan()\" >\n";
    $html .= "		</td>";
    $html .= "	</tr>";
    $html .= "</table>";
    return $html;
  }
?>