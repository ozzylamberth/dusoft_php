<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version 1.0 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	 IncludeClass("ClaseHTML");
	function InformacionOrdenComp($proveed,$preorden_id,$empresa,$empresac)
	{
	        $objResponse = new xajaxResponse();
					
			$sel = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
			$rst =$sel->SeleccionarInformacionDetalle($preorden_id,$proveed);
			$dat=$sel->insertarOrden_Pedido($proveed,$empresa,$empresac);
			$inf=$sel->SeleccionarMaxcompras_ordenes_pedidos($proveed,$empresa);
			$orden_pedido_id=$inf['0']['numero'];
			print_r($orden_pedido_id);
			$infd=$sel->Ingresarcompras_ordenes_pedidos_detalle($rst,$orden_pedido_id);
			$dtos=$sel->ActuEstado($preorden_id,$proveed);
			
			/*$objResponse->script('
            if("'.$dtos.'"==true)
			{
			xajax_AsiganarCondiciones("'.$preorden_id.'","'.$orden_pedido_id.'","'.$empresa.'");
            }
			else
			alert(" ERROR AL HACER LA ACTUALIZACION" );
			
			
			
			');
			/*$url=ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden",array("preorden_id"=>$preorden_id));
			$objResponse->script('
						 window.location="'.$url.'";
								');*/
			 
			return $objResponse;
	}
	
	       function AsiganarCondiciones($preorden_id,$orden_pedido_id,$empresa)
		   {
			    $objResponse = new xajaxResponse();
							
				$html  = "<fieldset class=\"fieldset\">\n";
				$html .= "  <legend class=\"normal_10AN\" align=\"center\">CONDICIONES DE COMPRAS DEPRODUCTOS</legend>\n";
				$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
				$html  .= "  <table class=\"modulo_table_list_title\" border=\"1\" align=\"center\" width=\"80%\">\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td width=\"10%\" align=\"center\">* CONDICIONES\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
				$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
				$html .= "  </table>\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"O.K\" onclick=\"ValidarDtos(document.Forma13,'".$preorden_id."','".$orden_pedido_id."','".$empresa."');\">\n";
				$html .= " </td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "  </form>\n";
				$html .= "</fieldset><br>\n";
				$objResponse->assign("Contenido","innerHTML",$html);
				$objResponse->call("MostrarSpan");
				return $objResponse;
			  	   
		   }
		   
		   function TrasferirInformacion($observa,$preorden_id,$orden_pedido_id,$empresa)
		   {
			     $objResponse = new xajaxResponse();
				
				$sel = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
				$rst =$sel->insertarCondicionesOrden_Pedido($empresa,$orden_pedido_id,$observa);
				$url=ModuloGetURL("app", "Compras_Orden_Compras", "controller", "DetallePreorden",array("preorden_id"=>$preorden_id));
				$objResponse->script('
							 window.location="'.$url.'";
									');
				
				return $objResponse;
		   	   
		   }
		   
		   function TrasferirCondicion($observa,$orden_pedido_id,$empresa)
		    {
				$objResponse = new xajaxResponse();
				
				$sel = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
				$rst =$sel->insertarCondicionesOrden_Pedido($empresa,$orden_pedido_id,$observa);
				$url=ModuloGetURL("app", "Compras_Orden_Compras", "controller", "ConsultarOrdenes");
				$objResponse->script('
							 window.location="'.$url.'";
									');
									
				
				return $objResponse;
		   
		   	   
		   }
		   
		   function EmpresaOrdenPedido()
		   {
		    		$objResponse = new xajaxResponse();
					$mdl = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
					$Empresa=$mdl->EmpresasOrden_Pedido();  
					$html = "<fieldset class=\"fieldset\">\n";
					$html .= "  <legend class=\"normal_10AN\" align=\"center\">SELECCION DE LA EMPRESA  </legend>\n";
					$html .= "<form name=\"Forma14\" id=\"Forma14\" method=\"post\" >\n";
					$html  .= "  <table class=\"modulo_list_oscuro\" border=\"0\" align=\"center\" width=\"80%\">\n";
					$html .= "    <tr class=\"modulo_table_list_title\">\n";
					$html .= "      <td width=\"10%\" align=\"center\">EMPRESAS:\n";
					$html .= "      </td>\n";
					$html .= "		<td  class=\"modulo_list_claro\" colspan=\"4\">\n";
					$html .= "				<select name=\"empresas\" class=\"select\">\n";
					$html .= "             	<option value = '-1'>--  SELECCIONE --</option>\n";
					$csk = "";
					foreach($Empresa as $indice => $valor)
					{
					if($valor[0]['empresa_id']==$request['empresa_id'])
					$sel = "selected";
					else   $sel = "";
					$html .= "  <option value=\"".$valor['empresa_id']."\" ".$sel.">".$valor['razon_social']."</option>\n";
					}
					$html .= "                </select>\n";
					$html .= "						  </td>\n";
					$html .= "    </tr>\n";
					$html .= "	</table>\n";
					$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
					$html .= "		<tr>\n";
					$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
					$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"BUSCAR\" onclick=\"xajax_Proveedores(document.Forma14.empresas.value,'".$valor['razon_social']."');\">\n";
					$html .= " </td>\n";
					$html .= "      <td align=\"center\">\n";
					$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
					$html .= " </td>\n";
					$html .= "		</tr>\n";
					$html .= "	</table>\n";
										
					$html  .= "  <table  border=\"0\" align=\"center\" width=\"80%\">\n";
					$html .= "  <tr  >\n";
					$html .= "      <td colspan=\"12\"><a> <div id=\"Proveedor\"></div></td>\n";
					$html .= "  </tr>\n";
					$html .= "	</table>\n";
					$html .= "  </form>\n";
					$html .= "</fieldset><br>\n";
					$objResponse->assign("Contenido","innerHTML",$html);
					$objResponse->call("MostrarSpan");
					return $objResponse;
			}
			
			  function Proveedores($empresa,$razon_social,$offset)
			  {
			    $objResponse = new xajaxResponse();
				$action['paginador'] = "paginador('".$empresa."'";
				$mdl = AutoCarga::factory("Compras_orden_ComprasSQL", "classes", "app", "Compras_Orden_Compras");
				$prov=$mdl->ConsultarProveedoresOrden_Pedido($empresa);  
				$html .= "<form name=\"Proveedor\" id=\"Proveedor\" method=\"post\" >\n";
				$html .= "  <table width=\"85%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
				$html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td width=\"35%\">IDENTIFICACION.</td>\n";
				$html .= "      <td  width=\"55%\">PROVEEDOR.</td>\n";
				$html .= "      <td   width=\"15%\">OP.</td>\n";

				$html .= "  </tr>\n";
				$pghtml = AutoCarga::factory('ClaseHTML');
				foreach($prov as $llave => $proveedor)
				{
				$html .= "  <tr class=\"modulo_list_claro\">\n";
				$html .= "      <td  align=\"center\">".$proveedor['tipo_id_tercero']." ".$proveedor['tercero_id']." </td>\n";
				$html .= "      <td align=\"left\">".$proveedor['nombre_tercero']."</td>\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_PasarVariablesOrden('".$empresa."','".$proveedor['codigo_proveedor_id']."','".$proveedor['nombre_tercero']."','".$proveedor['tipo_id_tercero']."','".$proveedor['tercero_id']."','".$razon_social."')\" class=\"label_error\">UNIFICAR</a>\n";
				$html .= "      </td>\n";
				$html .= "  </tr>\n";
				}
				$html .= "	</table>\n";
				$html .= $pghtml->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action['paginador']);
				$html .= "  </form>\n";

			    $objResponse->assign("Proveedor".$codigoproducto,"innerHTML",$html);
				
				return $objResponse;
			  }
			  
			  function  PasarVariablesOrden($empresa,$cod_proveedor,$nombre_tercero,$tipo_id_tercero,$tercero_id,$razon_social)
			  {
				  $objResponse = new xajaxResponse();
				  
				  $url=ModuloGetURL("app", "Compras_Orden_Compras", "controller", "UnificacionOrdePedidoxProveedor",array("empresa"=>$empresa,"proveedor"=>$cod_proveedor,"nombre_tercero"=>$nombre_tercero,"tipo_id_tercero"=>$tipo_id_tercero,"tercero_id"=>$tercero_id,"razon_social"=>$razon_social));
				$objResponse->script('
							 window.location="'.$url.'";
									');
				  return $objResponse;
			  }
			  function TransfeOrdenPedido($observa)
			  {
			  
			     $objResponse = new xajaxResponse();
				 
				 $url=ModuloGetURL("app", "Compras_Orden_Compras", "controller", "CrearDocumentoYUnificar",array("observa"=>$observa,"proveedor"=>$cod_proveedor,"nombre_tercero"=>$nombre_tercero,"tipo_id_tercero"=>$tipo_id_tercero,"tercero_id"=>$tercero_id,"razon_social"=>$razon_social));
				$objResponse->script('
							 window.location="'.$url.'";
									');
				  return $objResponse;
			  
			  
			  
			  }
			
			
		
?>