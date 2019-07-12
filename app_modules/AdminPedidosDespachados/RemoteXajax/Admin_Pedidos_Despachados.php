<?php
/**
  * @package DUANA
  * @version 1.0 $Id: Remotos_repositorio.php
  * @copyright (C) 2012 DUANA & CIA 
  * @author L.G.T.L
  */
 /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
 */
 
 
 	/************************************************
	* funcion para buscar documentos
	*************************************************/

	function ObtenerDatosDespacho($despacho_id)
	{
		$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

		$datos_despacho = $sql->ObtenerDatosDespacho($despacho_id);

		$detalles_despacho = $sql->ObtenerDetallesDespacho($despacho_id);

		$objResponse = new xajaxResponse();

		//$script .="document.getElementById('transportadora_id').options.add(new Option('abc','efg'));";
		
		$script ="for(var i=0 ;i<document.getElementById('transportadora_id').length;i++)";
	    $script .="{";
        $script .="	if(document.getElementById('transportadora_id').options[i].value==".$datos_despacho[0]['transportadora_id']." )";
        $script .="	{";
	    $script .="		document.getElementById('transportadora_id').selectedIndex =i;";
	    $script .="	}";
	    $script .="}";
	    //$script .="document.getElementById('transportadora_id').disabled=true;";
		$script .="document.getElementById('placa_vehiculo').value=".$datos_despacho[0]['placa_vehiculo'].";";
		//$script .="document.getElementById('placa_vehiculo').disabled=true;";
		$script .="document.getElementById('numero_guia').value=".$datos_despacho[0]['numero_guia'].";";
		//$script .="document.getElementById('numero_guia').disabled=true;";
		$script .="document.getElementById('nombre_conductor').value=".$datos_despacho[0]['nombre_conductor'].";";
		//$script .="document.getElementById('nombre_conductor').disabled=true;";

		$script .="document.getElementById('codigo_detalle_despacho').value=".$detalles_despacho[0]['detalle_despacho_id'].";";
		//$script .="document.getElementById('codigo_detalle_despacho').disabled=false;";

		$script .="document.getElementById('cantidad_cajas_despacho').value=".$detalles_despacho[0]['cantidad_cajas'].";";
		//$script .="document.getElementById('cantidad_cajas_despacho').disabled=true;";
		$script .="document.getElementById('cantidad_neveras_despacho').value=".$detalles_despacho[0]['cantidad_neveras'].";";
		//$script .="document.getElementById('cantidad_neveras_despacho').disabled=true;";
		$script .="document.getElementById('temperatura_neveras_despacho').value=".$detalles_despacho[0]['temperatura_neveras'].";";
		//$script .="document.getElementById('temperatura_neveras_despacho').disabled=true;";
		$script .="document.getElementById('peso_despacho').value=".$detalles_despacho[0]['peso'].";";
		//$script .="document.getElementById('peso_despacho').disabled=true;";

	    $objResponse->script($script);
	    return $objResponse;
	}



	function BuscarDocumento($destinatario_despacho,$pedido_id)
	{
		$request = $_REQUEST;
		$empresa_id = $request['datos']['empresa_id'];

	    $objResponse = new xajaxResponse();

		$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

		if(isset($pedido_id) && $destinatario_despacho == 0) {
	 		$pedidos = $sql->ObtenerDocumentosPedidoCliente($pedido_id);
	 		$tipo_destinatario_despacho = "cliente";
	 	} elseif(isset($pedido_id) && $destinatario_despacho == 1) {
	 		$pedidos = $sql->ObtenerDocumentosPedidoFarmacia($empresa_id,$pedido_id);
	 		$tipo_destinatario_despacho = "farmacia";
	 	}
	 	
	 	$salida = "";
	   	
	    //if($criterio != "0" && $criterio != "")
	    if(/*$pedido_id != "0" && */$pedido_id != "")
	    {
	         //$busqueda=$consulta->BuscarProducto($empresa_id,$bodega,$aumento,$aumento2,$offset);
	         if(!empty($pedidos))
	         {
	                 $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
	                 $salida .= "                 </div>\n";
	                 $salida .= "                 <form name=\"adicionar\">\n";
	                 $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
	                 $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
	                 $salida .= "                      <td align=\"center\" width=\"5%\">\n";
	                 $salida .= "                      INCLUIR EN DESPACHO\n";
	                 $salida .= "                      </td>\n";
	                 $salida .= "                      <td align=\"center\" width=\"15%\">\n";
	                 $salida .= "                        PEDIDO";
	                 $salida .= "                      </td>\n";
	                 $salida .= "                      <td align=\"center\" width=\"15%\">\n";
	                 $salida .= "                        DOCUMENTO";
	                 $salida .= "                      </td>\n";
	                 $salida .= "                      <td align=\"center\" width=\"35%\">\n";
	                 $salida .= "                        CLIENTE/FARMACIA ";
	                 $salida .= "                      </td>\n";
	                 $salida .= "                      <td align=\"center\" width=\"5%\">\n";
	                 $salida .= "                        <a title='REGISTRAR DETALLE DOCUMENTO'>REGISTRAR DETALLE DOCUMENTO</a>";
	                 $salida .= "                      </td>\n";
	                 $salida .= "                    </tr>\n";
	                  for($i=0;$i<count($pedidos);$i++)
	                  {
	                    $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
	                    $salida .= "                      <td align=\"center\" style=\"cursor:pointer;\">\n";
	                    $salida .= "                         <a title='INCLUIR EN DESPACHO' href=\"javascript:IncluirDocumentoDespacho('".$empresa_id."','EFC','".$pedidos[$i]['numero']."','".$pedidos[$i]['nombre']."','".$pedido_id."','','".$tipo_destinatario_despacho."');Cerrar('containerBus');\">\n";
	                    $salida .= "                          <sub><img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
	                    $salida .= "                         </a>\n";
	                    $salida .= "                      </td>\n";
	                    $salida .= "                      <td align=\"left\">\n";
	                    $salida .= "                        ".$pedidos[$i]['pedido_id'];
	                    $salida .= "                      </td>\n";
	                    $salida .= "                      <td align=\"left\">\n";
	                    $salida .= "                        ".$pedidos[$i]['numero'];
	                    $salida .= "                      </td>\n";
	                    $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
	                    $salida .= "                        ".$pedidos[$i]['nombre'];
	                    $salida .= "                      </td>\n";
	                    $salida .= "                      <td align=\"center\" style=\"cursor:pointer;\" onclick=\"AsignarDocumento('".$empresa_id."','EFC','".$pedidos[$i]['numero']."','".$pedidos[$i]['nombre']."','".$tipo_destinatario_despacho."');\">\n";
	                    $salida .= "                         <a title='REGISTRAR DETALLE DOCUMENTO' href=\"javascript:MostrarCapa('containerBus2');Iniciar5('REGISTRAR DETALLE DOCUMENTO');\">\n";
	                    $salida .= "                          <sub><img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
	                    $salida .= "                         </a>\n";
	                    $salida .= "                      </td>\n";
	                    $salida .= "                    </tr>\n";
	                  }
	                    $salida .= "                </table>\n";
	              //$action = "Bus_Pro('".$empresa_id."','".$bodega."','".$tip_bus."','".$criterio."' ";
	              //$ctl = AutoCarga::factory("ClaseHTML");
	              //$salida .= $ctl->ObtenerPaginadoXajax($consulta->conteo,$consulta->paginaActual,$action,"0",10);
	         }                        
	         else
	         {
	                 $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
	                 $salida .= "                    <tr>\n";
	                 $salida .= "                      <td align=\"center\">\n";
	                 $salida .= "                         <label ALIGN='center' class='label_error'>NO SE ENCONTRARON RESULTADOS</label>";
	                 $salida .= "                      </td>\n";
	                 $salida .= "                    </tr>\n";
	                 $salida .= "                    </table>\n";
	         }
	    }
	    else
	    {
	      $salida .= "  <table width=\"95%\" align=\"center\">\n";         
	      $salida .= "    <tr>\n";
	      $salida .= "      <td align=\"center\" class=\"normal_10AN\">\n";
	      $salida .= "        INGRESE UN NUMERO DE PEDIDO PARA REALIZAR LA BUSQUEDA";
	      $salida .= "      </td>\n";
	      $salida .= "    </tr>\n";
	      $salida .= "  </table>\n";
	    }

	    $objResponse->assign("tabla_bus","innerHTML",$salida);
	    return $objResponse;
	}



	function GuardarDetalleDocumentoPedidoFarmacia()
	{
		$datos = $_REQUEST['xjxargs'];
		
		$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

		$objResponse = new xajaxResponse();
		
		if($datos[7] == "cliente") {
			$sql->GuardarDetalleDocumentoPedidoCliente($datos);
		} elseif($datos[7] == "farmacia") {
			$sql->GuardarDetalleDocumentoPedidoFarmacia($datos);
		}

		$script ="IncluirDocumentoDespacho(empresa_id,prefijo,numero,nombre,pedido_id,ultimo_detalle_documento_id,tipo_destinatario_despacho);";

	    $objResponse->script($script);
	    return $objResponse;
	}



	function ObtenerIdUltimoDetalleDocumentoPedidoFarmacia($pedido_id,$numero,$nombre,$tipo_destinatario_despacho)
	{
		$sql = Autocarga::factory("AdminPedidosDespachadosSQL", "", "app","AdminPedidosDespachados");

		if($tipo_destinatario_despacho == "cliente") {
			$idUltimoDetalleDocumentoPedidoCliente = $sql->ObtenerIdUltimoDetalleDocumentoPedidoCliente();

			$idUltimoDetalleDocumento = $idUltimoDetalleDocumentoPedidoCliente[0]['max'];
		} elseif($tipo_destinatario_despacho == "farmacia") {
			$idUltimoDetalleDocumentoPedidoFarmacia = $sql->ObtenerIdUltimoDetalleDocumentoPedidoFarmacia();

			$idUltimoDetalleDocumento = $idUltimoDetalleDocumentoPedidoFarmacia[0]['max'];
		}			
		
		$objResponse = new xajaxResponse();

		$html = "<td align='center' class='normal_10AN'>";
        $html .= "	".$pedido_id."";
      	$html .= "</td>";
      	$html .= "<td align='center' class='normal_10AN'>";
      	$html .= "	".$numero."";
      	$html .= "</td>";
      	$html .= "<td align='center' class='normal_10AN'>";
      	$html .= "	".$nombre."";
      	$html .= "</td>";
      	$html .= "<td align='center' class='normal_10AN'>";
      	$html .= "	<input type='checkbox' checked='checked' name='detalle_individual_documento[]' value='".$idUltimoDetalleDocumento." - ".$tipo_destinatario_despacho."'>";
      	$html .= "</td>";

      	$script = "	var tbody = document.getElementById('detalle_agregado');";
		$script .= "var tr = tbody.lastChild;";
		$script .= "tr.innerHTML = \"".$html."\";";//\"

	    $objResponse->script($script);
	    return $objResponse;
	}
     
?>