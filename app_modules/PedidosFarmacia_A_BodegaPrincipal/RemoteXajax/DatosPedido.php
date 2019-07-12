<?php
	/**
	* Archivo Xajax
	* Tiene como responsabilidad hacer el manejo de las funciones
	* que son invocadas por medio de xajax
	*
	* @package IPSOFT-SIIS
	* @version $Revision: 1.2 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	
	/*
		* Forma que permite hacer las validaciones cuando un producto se ha seleccionado para generar el documento de pedido
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function ValidarDatosProducto($value,$farmacia,$centro,$bod,$tipoprod,$codigo_producto)
		{                   

			$objResponse = new xajaxResponse();

			$objResponse->script('

			if( document.getElementById(\'checkseleccionar'.$codigo_producto.'\').checked==true && document.getElementById(\'txtcantidad'.$codigo_producto.'\').value=="")
			{
			alert(" INGRESE LA CANTIDAD A SOLICITAR ");
			}
			
			if(document.getElementById(\'checkseleccionar'.$codigo_producto.'\').checked==true  && document.getElementById(\'txtcantidad'.$codigo_producto.'\').value!="" &&  !isNaN(document.getElementById(\'txtcantidad'.$codigo_producto.'\').value)){
			var cantidadSoli=parseInt(document.getElementById(\'txtcantidad'.$codigo_producto.'\').value);
			var disponible=parseInt(document.getElementById(\'disponible'.$codigo_producto.'\').value);
		
			if(cantidadSoli > disponible)
			{
			  	alert(" LA CANTIDAD A SOLICITAR NO PUEDE SER  MAYOR A LA CANTIDAD DISPONIBLE "); 
			}else
			{
			xajax_InsertarDatosTmp("'.$farmacia.'", "'.$centro.'", "'.$bod.'","'.$codigo_producto.'",cantidadSoli,"'.$tipoprod.'");
			}
			}

			if(document.getElementById(\'checkseleccionar'.$codigo_producto.'\').checked==false){
			xajax_EliminarTmp("'.$farmacia.'", "'.$centro.'", "'.$bod.'","'.$codigo_producto.'","'.$tipoprod.'");
			}

			');
			return $objResponse;
		}
	/*
		* Forma que permite Ingresar los datos de los productos seleccionados 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	   
		function InsertarDatosTmp($far,$Centrid,$bod,$codigo_producto,$cantidad,$tipoprod)
		{	
  	   $objResponse = new xajaxResponse();
		   $sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
		   $datos =$sel->IngresarDatosSolicitud_pro_a_bod_prpal_tmp($far,$Centrid,$bod,$codigo_producto,$cantidad,$tipoprod);
       if(!$datos)
       {
        $cade=$far."".$Centrid."".$codigo_producto;
        $Usuario=$sel->BuscarUsuario_Bloqueo($far,$Centrid,$bod,$codigo_producto);
        $objResponse->alert("El Usuario : (".$Usuario['usuario_id'].")-".$Usuario['nombre'].", TIENE BLOQUEADO EL PRODUCTO.");
       }
		   $rst =$sel->consultarDatosSeleccionados($far,$Centrid,$bod,$tipoprod);
		   if(!empty($rst))
	       {
	        $html .= "<fieldset class=\"fieldset\">\n";
	        $html .= "  <legend class=\"normal_10AN\"   align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
	        $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
			$html .= "	  <tr  class=\"formulacion_table_list\" align=\"CENTER\" >\n";
	        $html .= "      <td width=\"15%\">CODIGO</td>\n";
	        $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
	        $html .= "      <td width=\"5%\"> CANTIDAD</td>\n";
	        $html .= "      <td width=\"5%\">OP</td>\n";
	        $html .= "  </tr>\n";
	        $est = "modulo_list_claro"; $back = "#DDDDDD";
	        foreach($rst as $key => $dtl)
	        {
					$ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
					$html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
					$html .= "      <td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "      <td align=\"center\"><b>".$dtl['producto']."</b></td>\n";
					$html .= "      <td align=\"center\"><b>".round($dtl['cantidad'])."</b></td>\n";
					$html .= "      <td  align=\"center\" width=\"1%\">\n";
					$html .= "       <a href=\"#\" onclick=\"xajax_EliminarTmp('".$far."','".$Centrid."','".$bod."','".$dtl['codigo_producto']."','".$tipoprod."');\">";
					$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
					$html .= "        </a>\n";
					$html .= "      </td>\n";
					$html .= "    </tr>\n";
			
	        }
			$html .= "</table><br>\n";	
	        $html .= "</fieldset><br>\n";		
			}	
			$objResponse->assign("productos","innerHTML",$html);
		    return $objResponse;
        }
	/*
		* Forma que permite eliminar el producto que sea desmarcado
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		
		function EliminarTmp($far,$Centrid,$bod,$codigo_producto,$tipoprod)
		{
			$objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$rst =$sel->Eliminar_DatosSolicitud_pro_a_bod_prpal_tmp($far,$Centrid,$bod,$codigo_producto);
		     $rst =$sel->consultarDatosSeleccionados($far,$Centrid,$bod,$tipoprod);
		   if(!empty($rst))
	       {
	        $html .= "<fieldset class=\"fieldset\">\n";
	        $html .= "  <legend class=\"normal_10AN\"   align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
	        $html .= "  <table width=\"85%\" class=\"modulo_table_list\"   align=\"center\">";
			$html .= "	  <tr  class=\"formulacion_table_list\" align=\"CENTER\" >\n";
	        $html .= "      <td width=\"15%\">CODIGO</td>\n";
	        $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
	        $html .= "      <td width=\"5%\"> CANTIDAD</td>\n";
	        $html .= "      <td width=\"5%\">OP</td>\n";
	        $html .= "  </tr>\n";
	        $est = "modulo_list_claro"; $back = "#DDDDDD";
	        foreach($rst as $key => $dtl)
	        {
					$ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
					$html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
					$html .= "      <td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "      <td align=\"center\"><b>".$dtl['producto']."</b></td>\n";
					$html .= "      <td align=\"center\"><b>".round($dtl['cantidad'])."</b></td>\n";
					$html .= "      <td  align=\"center\" width=\"1%\">\n";
					$html .= "       <a href=\"#\" onclick=\"xajax_EliminarTmp('".$far."','".$Centrid."','".$bod."','".$dtl['codigo_producto']."','".$tipoprod."');\">";
					$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
					$html .= "        </a>\n";
					$html .= "      </td>\n";
					$html .= "    </tr>\n";
			
	        }
			$html .= "</table><br>\n";	
	        $html .= "</fieldset><br>\n";		
			}	
			$objResponse->assign("productos","innerHTML",$html);
			return $objResponse;
		
		}
	/*
		* Forma que permite validar lo seleccionado.
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		
		function ValidarSel($farmacia,$centro,$bod)
		{
		
			$objResponse = new xajaxResponse();
			
			$sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$rst =$sel->ConsultarSolicitud_pro_a_bod_prpal_tmp($farmacia,$centro,$bod);
			if(empty($rst)){
				 $objResponse->script('
				 xajax_MostrarMensaje();
				');
				}
				else
				{
				 $objResponse->script('
				 xajax_MostrarFormaCompleta();
				');
				}
			return $objResponse;
		}
	/*
		* Forma que permite Mostrar un mensaje cuando no se ha seleccionado ningun producto
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function MostrarMensaje()
		{
				$objResponse = new xajaxResponse();
				$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
				$html .= "  <table width=\"55%\"  border=\"0\"  align=\"center\"  onmousedown =\"OcultarSpan();\">";
				$html .= "		<tr class=\"modulo_table_list\" >\n";
				$html .= "      <td  align=\"center\"><b>NO SE HA REALIZADO NINGUNA SOLICITUD<b></td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "  </form>\n";
				$objResponse->assign("Contenido","innerHTML",$html);
				$objResponse->call("MostrarSpan");
        		return $objResponse;
		}
	/*
		* Forma que permite Mostrar la forma completa al señalar los productos
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function MostrarFormaCompleta()
		{
				$objResponse = new xajaxResponse();
				$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\" >";
				$html .= "		<tr class=\"modulo_table_list\" >\n";
				$html .= "      <td  align=\"center\"><b>LOS PRODUCTOS SEÑALADOS SERAN INCLUIDOS  AL GENERARSE EL DOCUMENTO<b></td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "  <table width=\"45%\"  border=\"0\"  align=\"center\" >";
				$html .= "		<tr>\n";
				$html .= "       <td align=\"center\" class=\"normal_10AN\" >\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CONTINUAR\" onclick=\"xajax_TransUrl();\">\n";
				$html .= " </td>\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
				$html .= " </td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "  </form>\n";
				$html .= "</fieldset><br>\n";
			
				$objResponse->assign("Contenido","innerHTML",$html);
				$objResponse->call("MostrarSpan");
				return $objResponse;
			
		}
		
	/*
		* Forma que permite ir a un url
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	
	 function TransUrl()
	{
	 
	    $objResponse = new xajaxResponse();
		$url=ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Pedidos_Productos_Bodega_Principal");
		$objResponse->script('
						 window.location="'.$url.'";
							');
 		return $objResponse;
	}
   /*
		* Forma que permite realizar las validaciones de los productos seleccionados
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	
		function ValidarSelec($farmacia,$centro,$bod)
		{
		
			$objResponse = new xajaxResponse();
			
			$sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$rst =$sel->ConsultarSolicitudPorUsuario($farmacia,$centro,$bod);
			if(empty($rst)){
				 $objResponse->script('
				 xajax_MostrarMensaje();
				');
				}
				else
				{
				 $objResponse->script('
				 xajax_MostrarFormaGenerarPedidoCompleta("'.$farmacia.'","'.$centro.'","'.$bod.'");
				');
				}
			return $objResponse;
		}
		
		
	/*
		* Forma que permite Mostrar un mensaje cuando  se ha seleccionado un o varios  productos 
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function MostrarFormaGenerarPedidoCompleta($farmacia,$centro,$bod)
		{
				$objResponse = new xajaxResponse();
				$mdl = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
				$empresa_destino= $mdl->ConsultarEmpresas();
           		$datos= $mdl->Consultar_Empresa_aux($farmacia,$centro,$bod);
				$html = "<fieldset class=\"fieldset\">\n";
				$html .= "  <legend class=\"normal_10AN\" align=\"center\">LOS PRODUCTOS SELECCIONADOS SERAN INCLUIDOS  AL GENERARSE EL DOCUMENTO DE PEDIDO </legend>\n";
				$html .= "<form name=\"Forma23\" id=\"Forma23\" method=\"post\" >\n";
				$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
				$html .= "<input type=\"hidden\" name=\"empresa_destino\" value=\"".$datos[0]['empresa_destino']."\"> ";
				$html  .= "  <table class=\"modulo_table_list\"  align=\"center\" width=\"80%\">\n";
				$html .= "    <tr class=\"formulacion_table_list\">\n";
				$html .= "      <td width=\"10%\" align=\"center\"> OBSERVACIÒN\n";
				$html .= "      </td>\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "    </tr>\n";
				$html .= "      <td colspan=\"5\"  align=\"left\" class=\"modulo_list_claro\">\n";
				$html .= "        Pedido General<input type=\"checkbox\" name=\"tipo_pedido\" value=\"1\">\n";
				$html .= "      </td>\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "    </tr>\n";				
				$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
				$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
				$html .= "  </table>\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "      <td align=\"center\" class=\"normal_10AN\" >\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GENERAR DOCMENTO\" onclick=\"TransD(document.Forma23);\">\n";
				$html .= " </td>\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CANCELAR\" onclick=\"OcultarSpan();\">   \n";
				$html .= " </td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table>\n";
				$html .= "  </form>\n";
				$html .= "</fieldset><br>\n";
			
				$objResponse->assign("Contenido","innerHTML",$html);
				$objResponse->call("MostrarSpan");
				return $objResponse;
			
		}
	/*
		* Forma que permite transferir las variables para generar el documento de pedido
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function TransVariablesP($observa,$empresa_destino,$tipo_pedido)
		{
			$objResponse = new xajaxResponse();
			$url=ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "GenerarDocumentoPedido", array("observacion"=>$observa,"empresa_destino"=>$empresa_destino,"tipo_pedido"=>$tipo_pedido));
			$objResponse->script('
						 window.location="'.$url.'";
							');
 			return $objResponse;
		
		}
	/*
		* Forma que permite cancelar la creacion del documento
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function CancelarCreacion($tipo_producto_id,$empresa,$Centrid,$bod)
		{
		   $objResponse = new xajaxResponse();
			$sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$rst =$sel->EliminarSolicitud_pro_a_bod_prpal_tmp($tipo_producto_id,$empresa,$Centrid,$bod);
			$url=ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Pedidos_Productos_Bodega_Principal");
			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		
		
		}
	/*
		* Forma que permite cambiar la cantidad por producto
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		function  CambiarCantidad($soli,$cantidad,$producto)
		{
		$objResponse = new xajaxResponse();
		$html .= "<form name=\"Formaactualm\" id=\"Formaactualm\" method=\"post\" >\n";
		$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
		$html.= "<table  class=\"modulo_table_list\" align=\"center\"  width=\"65%\">";
		$html.= "<tr class=\"formulacion_table_list\" >";
		$html.= "<td  align=\"center\"  width=\"20%\"  >CANTIDAD ACTUAL";
		$html.= "</td>";
		$html.= "<td align=\"center\"   width=\"10%\" >CANTIDAD MODI";
		$html.= "</td>";
		$html.= "</tr>";
		$html.= "<tr class=\"formulacion_table_list\" >";
		$html.= "<td align=\"center\"   width=\"5%\" class=\"modulo_list_claro\">";
		$html .= " ".$cantidad." ";
		$html.= "</td>";
		$html.= "<td align=\"center\"  class=\"modulo_list_claro\"  width=\"10%\" onkeypress=\"return acceptNum(event)\">";
		$html .= "	<input type=\"text\" class=\"input-text\" name=\"cantidad_mod\" id=\"cantidad_mod\"  size=\"5\" maxlength=\"5\" value=\"\" ></td>\n";
        $html.= "</td>";
		$html.= "</tr>";
		$html.= "</table>";
		
		$html.= "<table  align=\"center\"  width=\"65%\">";
		$html.= "<tr >";
		$html.= "<td align=\"center\" colspan=\"2\" >";
		$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"Guardar Modificacion\"  onclick=\"validardatos(".$cantidad.",document.Formaactualm.cantidad_mod.value,".$soli.",'".$producto."');\" >\n";
		$html.= "</td>";
		$html.= "</tr>";
		$html.= "</table>";
		$html .= "  </form>\n";
      
        $objResponse->assign("Contenido","innerHTML",$html);
		$objResponse->call("MostrarSpan");
		return $objResponse;
		}
		
	/*
		* Forma que permite transferir variables
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		
		function TransVariables($sol,$producto,$mod,$bod)
		{
		  $objResponse = new xajaxResponse();
		  $sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
		  $rst =$sel->ActualizarCantidades($sol,$producto,$mod);
		  $url=ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "DocumentoPedidoDetallado",array("solicitud_prod_a_bod_ppal_id"=>$sol,"bodega"=>$bod));

			$objResponse->script('
						 window.location="'.$url.'";
							');
		    return $objResponse;
		 
		}
		/*
		* Forma que permite Mostrar la forma completa de los productos seleccionados
		* @return object $objResponse objeto de respuesta al formulario.
	*/
    function ProductosSeleccionados($farmacia,$centro,$bodega,$tipo)
    {
    	$objResponse = new xajaxResponse();
      
       $sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
       
       $rst =$sel->consultarDatosSeleccionados($farmacia,$centro,$bodega,$tipo);
	   if(!empty($rst))
       {
        $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\"   align=\"left\"><b>PRODUCTOS SELECCIONADOS</b></legend>\n";
        $html .= "  <table width=\"100%\" class=\"modulo_table_list\"   align=\"center\">";
		$html .= "	  <tr  class=\"formulacion_table_list\" align=\"CENTER\" >\n";
        $html .= "      <td width=\"15%\">CODIGO</td>\n";
        $html .= "      <td width=\"45%\">PRODUCTO</td>\n";
        $html .= "      <td width=\"5%\"> CANTIDAD</td>\n";
        $html .= "      <td width=\"30%\"> OBS</td>\n";
        $html .= "      <td width=\"5%\">OP</td>\n";
        $html .= "  </tr>\n";
        $est = "modulo_list_claro"; $back = "#DDDDDD";
        foreach($rst as $key => $dtl)
        {
				$ent = ($valor > $dtl['cantidad'])? $dtl['cantidad']: $valor;
				$html .= "    <tr ".(($dtl['sw_generico']=='1')? "style=\"background:#CFE7FA\" ":"")." onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" class=\"".$est."\" >\n";
				$html .= "      <td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
				$html .= "      <td align=\"left\"><b>".$dtl['producto']."</b></td>\n";
				$html .= "      <td align=\"center\"><b>".round($dtl['cantidad'])."</b></td>\n";
				$html .= "      <td align=\"left\"><b>".$dtl['observacion']."</b></td>\n";
				$html .= "      <td  align=\"center\" width=\"1%\">\n"; 
				$html .= "       <a href=\"#\" onclick=\"xajax_EliminarTmp('".$farmacia."','".$centro."','".$bodega."','".$dtl['codigo_producto']."','".$tipo."');\">";
				$html .= "      	  <img src=\"".GetThemePath()."/images/elimina.png\"  border=\"0\">\n";
				$html .= "        </a>\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
		
        }
		$html .= "</table><br>\n";	
        $html .= "</fieldset><br>\n";		
      }	
        $objResponse->assign("productos","innerHTML",$html);
  
      return $objResponse;
  }
  /**/
  
  Function EmpresaDestino_()
  {
		$objResponse = new xajaxResponse();
		$sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
		$rst =$sel->ListaEmpresas();
		$html .= "		<form name=\"formita\" id=\"formita\"   method=\"post\"     >";
		$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
		$html .= "			<table   width=\"75%\" align=\"center\" border=\"0\"   >";
		$html .= "         <tr class=\"modulo_table_list_title\">\n";
		$html .= "		          	<td    align=\"center\" width=\"40%\" >EMPRESA:</td>\n";
		$html .= "			            <td   align=\"left\"  class=\"modulo_list_claro\" >\n";
		$html .= "					            <select name=\"empresa\" class=\"select\" onchange=\"xajax_MostrarCentroUtilidad(xajax.getFormValues('formita'))\">\n";
		$html .= "                        	<option value = '-1'>--  SELECCIONE --</option>\n";
		$csk = "";
		foreach($rst as $indice => $valor)
		{
		if($valor['empresa_id']==$request['empresa_id'])
		$sel = "selected";
		else   $sel = "";
		$html .= "  <option value=\"".$valor['empresa_id']."\" ".$sel.">".$valor['razon_social']."</option>\n";
		}
		$html .= "                </select>\n";
		$html .= "					  	  </td>\n";
		$html .= "	 </tr>\n";
		$html .= "  <tr class=\"modulo_table_list_title\">\n";
		$html .= "		           	<td  width=\"40%\" >CENTRO UTILIDAD:</td>\n";
		$html .= "		            	<td  class=\"modulo_list_claro\" align=\"left\">\n";
		$html .= "					           <select name=\"centro\" class=\"select\" onChange=\"xajax_MostrarBodegas(xajax.getFormValues('formita'))\">\n";
		$html .= "                     	<option value = '-1'>--  SELECCIONE --</option>\n";
		$csk = "";
		$html .= "                </select>\n";
		$html .= "						     </td>\n";
		$html .= "		</tr>\n";
		$html .= "  <tr class=\"modulo_table_list_title\">\n";
		$html .= "			         	<td width=\"40%\"  >BODEGAS:</td>\n";
		$html .= "			        	<td class=\"modulo_list_claro\" align=\"left\">\n";
		$html .= "			         		<select name=\"bodega\" class=\"select\"  >\n";
		$html .= "					       	<option value=\"-1\">-SELECCIONAR-</option>\n";
		$html .= "				        	</select>\n";			
		$html .= "			          	</td>\n";		
		$html .= "		</tr>\n";
		$html .= "		<tr>\n";
		$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
		$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"continuar\" value=\"CONTINUAR\" onclick=\"validarinfo(document.formita);\"  >\n";
		$html .= "		          	</td>\n";
		$html .= "		</tr>\n";
		$html .= "</table><br>\n";
		$objResponse->assign("Contenido","innerHTML",$html);
		$objResponse->call("MostrarSpan");
		return $objResponse;
  
  
  }
  /*
		* Forma que permite Mostrar los centros de utilidades de la empresa
		* @return object $objResponse objeto de respuesta al formulario.
	*/
	
		function MostrarCentroUtilidad($form)
		{
		    $sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$centro = $sel->ListarCentroUtilidad($form['empresa']);
			$html  = "document.formita.centro.options.length = 0 ;\n";
			$html .= "document.formita.centro.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
			$i = 1;
			foreach($centro as $key => $dtl)
			{
				$html .= "document.formita.centro.options[".($i++)."] = new Option('".$dtl['descripcion']."','".$dtl['centro_utilidad']."',false, false);\n";
			}
			$objResponse = new xajaxResponse();
			$objResponse->script($html);
			return $objResponse;
		}
			/*
		* Forma que permite Mostrar las bodegas
		* @return object $objResponse objeto de respuesta al formulario.
	*/
		Function MostrarBodegas($form)
		{
		    $sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
			$centro = $sel->ListarBodegaEmp($form['empresa'],$form['centro']);
			$html  = "document.formita.bodega.options.length = 0 ;\n";
			$html .= "document.formita.bodega.options[0] = new Option('--SELECCIONAR--','-1',false, false);\n";
			$i = 1;
			foreach($centro as $key => $dtl)
			{
				$html .= "document.formita.bodega.options[".($i++)."] = new Option('".$dtl['descripcion']."','".$dtl['bodega']."',false, false);\n";
			}
			$objResponse = new xajaxResponse();
			$objResponse->script($html);
			return $objResponse;
		}
	/*
	* Forma  que permite validar la informacion de las empresas destino
       * @return object $objResponse objeto de respuesta al formulario. */
		function ValidarInformacion_EmpresaDestino($empresa_d,$centro_d,$bodega_d,$farmacia,$centro,$bodega)
		{
			$objResponse = new xajaxResponse();
		
			$sel = AutoCarga::factory("PedidosFarmacia_A_BodegaPrincipalSQL", "", "app", "PedidosFarmacia_A_BodegaPrincipal");
            $datos = $sel->Ingresar_Empresas_destino($farmacia,$centro,$bodega,$empresa_d,$centro_d,$bodega_d);
	        if($datos==true)
			{
			 	$url=ModuloGetURL("app", "PedidosFarmacia_A_BodegaPrincipal", "controller", "Pedidos_Productos_Bodega_Principal");
				$objResponse->script('
					 window.location="'.$url.'";
							');
			}
		
	    return $objResponse;			
		}
	   
		
	
?>
