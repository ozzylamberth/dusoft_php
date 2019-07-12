
<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: AdminPedidosRecibidosHTML.class
  * @copyright DUANA & CIA NOV-2012
  * @author L.G.T.L
  **********************************************************/

  /***********************************************************
  * Clase Vista: AdminPedidosRecibidosHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class AdminPedidosRecibidosHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function AdminPedidosRecibidosHTML(){}


		
		/********************************************************
		* Menu de Bodegas
		********************************************************/
		function MenuBodegas($action, $datos, $empresa_id)
		{
			$bodegas = $datos['bodegas'];
			$html  = ThemeAbrirTabla('PEDIDOS RECIBIDOS - BODEGAS','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

			$html .= "</table>\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">BODEGAS\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($bodegas); $i++) 
			{                                                                                 //empresa_id centro_utilidad bodega  usuario_id  nom_bodega
	          //$BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$vector[$i]['nom_bodega'],'utility'=>$vector[$i]['centro_utilidad'],'bodegax'=>$vector[$i]['bodega']));
			  $BODEGA = ModuloGetURL("app", "AdminPedidosRecibidos", "controller", "MenuPedidos")."&datos[empresa_id]=".$empresa_id;
			  //$BODEGA = ModuloGetURL("app", "AdminPedidosRecibidos", "controller", "MenuFarmacias")."&datos[empresa_id]=".$empresa_id;
	          $html .= "</tr>\n";
	          $html .= "<tr class=\"modulo_list_claro\">\n";
	          $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "<a title=\"".$bodegas[$i]['nom_bodega']."\" class=\"label_error\" href=\"".$BODEGA."\">".$bodegas[$i]['nom_bodega']."</a>\n";
	          $html .= "</td>";
	          $html .= "</tr>";
	       	}
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}

		

		/********************************************************
		* Menu de Pedidos
		********************************************************/
		function MenuPedidos($action, $datos, $pedido_id)
		{
			$pedidos = $datos['pedidos'];
			$html  = ThemeAbrirTabla('PEDIDO RECIBIDO','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

			$html .= "</table>\n";

			$html .= "<form name=\"pedido\" id=\"pedido\" action=\"".$action['MenuPedidos']."\" method=\"post\">\n";
			$html .= "	<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  	<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"3\">PEDIDO NUMERO <input type=\"text\" class=\"input-text\" name=\"pedido_id\" value=\"".$pedido_id."\" style=\"width:50%\"> <input type=\"submit\" class=\"input-submit\" value=\"Buscar\">\n";
			$html .= "     		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form><br>\n";

			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">PEDIDO - NUMERO DE GUIA\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($pedidos); $i++) 
			{
	          //$BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$vector[$i]['nom_bodega'],'utility'=>$vector[$i]['centro_utilidad'],'bodegax'=>$vector[$i]['bodega']));
			  $PEDIDO = ModuloGetURL("app", "AdminPedidosRecibidos", "controller", "AdministrarPedidoRecibido")."&pedido_id=".$pedidos[$i]['solicitud_prod_a_bod_ppal_id']."&solicitud_prod_a_bod_ppal_det_des_id=".$pedidos[$i]['solicitud_prod_a_bod_ppal_det_des_id'];
	          $html .= "  </tr>\n";
	          $html .= "  <tr class=\"modulo_list_claro\">\n";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        <a title=\"".$pedidos[$i]['solicitud_prod_a_bod_ppal_id']."\" class=\"label_error\" href=\"".$PEDIDO."\">".$pedidos[$i]['solicitud_prod_a_bod_ppal_id']." - ".$pedidos[$i]['numero_guia']."</a>\n";
	          $html .= "     </td>";
	          $html .= "  </tr>";
	       	}
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}



		function AdministrarPedidoRecibido($action, $detalle_despacho_pedido, $pedido_id)
		{
			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.cantidad_cajas_recibidas.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA CANTIDAD DE CAJAS RECIBIDAS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.cantidad_cajas_recibidas.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA CANTIDAD DE CAJAS RECIBIDAS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.cantidad_neveras_recibidas.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA CANTIDAD DE NEVERAS RECIBIDAS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.cantidad_neveras_recibidas.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA CANTIDAD DE NEVERAS RECIBIDAS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.temperatura_recibida.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR TEMPERATURA DE LAS NEVERAS RECIBIDAS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.temperatura_recibida.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA TEMPERATURA DE LAS NEVERAS RECIBIDAS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(((parseInt(forma.cantidad_neveras_despachadas.value) != parseInt(forma.cantidad_neveras_recibidas.value)) || (parseInt(forma.temperatura_despachada.value) != parseInt(forma.temperatura_recibida.value)) || (parseInt(forma.cantidad_cajas_despachadas.value) != parseInt(forma.cantidad_cajas_recibidas.value))) && (forma.observacion.value == \"\"))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE DIGITAR UNA OBSERVACION EN RELACION A LA DISPARIDAD DE PRODUCTOS (Y SU ESTADO) RECIBIDOS FRENTE A LOS PRODUCTOS DESPACHADOS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(((parseInt(forma.cantidad_neveras_despachadas.value) != parseInt(forma.cantidad_neveras_recibidas.value)) || (parseInt(forma.temperatura_despachada.value) != parseInt(forma.temperatura_recibida.value)) || (parseInt(forma.cantidad_cajas_despachadas.value) != parseInt(forma.cantidad_cajas_recibidas.value))) && (forma.observacion.value.length < 20))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"LA OBSERVACION DEBE TENER COMO MINIMO 20 CARACTERES\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.detalles_pedido_recibido.action =\"".$action['GuardarDetallesPedidoRecibido']."\"; \n";
			$html .= "    document.detalles_pedido_recibido.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";
			$html .= ThemeAbrirTabla('PEDIDO RECIBIDO','50%');
			$html .= "<form name=\"detalles_pedido_recibido\" id=\"detalles_pedido_recibido\" action=\"javascript:ValidarCampos(document.detalles_pedido_recibido)\" method=\"post\">\n";
			//$html .= "	<input type=\"hidden\" name=\"pedido_id\" id=\"pedido_id\" value=\"".$pedido_id."\">\n";
			$html .= "	<input type=\"hidden\" name=\"detalle_despacho_id\" id=\"detalle_despacho_id\" value=\"".$detalle_despacho_pedido[0]['solicitud_prod_a_bod_ppal_det_des_id']."\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"4\">PEDIDO NUMERO ".$pedido_id."\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";			
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">MEDICAMENTOS SIN NEVERA - DESPACHADOS\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				Cantidad de Cajas\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['cantidad_cajas']."<input type=\"hidden\" name=\"cantidad_cajas_despachadas\" id=\"cantidad_cajas_despachadas\" value=\"".$detalle_despacho_pedido[0]['cantidad_cajas']."\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">MEDICAMENTOS CON NEVERA - DESPACHADOS\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Cantidad de Neveras\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Temperatura (en grados centigrados)\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['cantidad_neveras']."<input type=\"hidden\" name=\"cantidad_neveras_despachadas\" id=\"cantidad_neveras_despachadas\" value=\"".$detalle_despacho_pedido[0]['cantidad_neveras']."\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['temperatura_neveras']."<input type=\"hidden\" name=\"temperatura_despachada\" id=\"temperatura_despachada\" value=\"".$detalle_despacho_pedido[0]['temperatura_neveras']."\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">INFORMACION GENERAL DEL PEDIDO\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Peso (en Kg)\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Transportadora\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['peso']."\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['descripcion']."\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				Placa Vehiculo\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				Numero de Guia\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Nombre Conductor\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['placa_vehiculo']."\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['numero_guia']."\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				".$detalle_despacho_pedido[0]['nombre_conductor']."\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">MEDICAMENTOS SIN NEVERA - RECIBIDOS\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				Cantidad de Cajas\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				<input type=\"text\" name=\"cantidad_cajas_recibidas\" id=\"cantidad_cajas_recibidas\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">MEDICAMENTOS CON NEVERA - RECIBIDOS\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Cantidad de Neveras\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				Temperatura (en grados centigrados)\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				<input type=\"text\" name=\"cantidad_neveras_recibidas\" id=\"cantidad_neveras_recibidas\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				<input type=\"text\" name=\"temperatura_recibida\" id=\"temperatura_recibida\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				Descripcion\n";
			$html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				<textarea cols=\"50\" rows=\"4\" name=\"observacion\" id=\"observacion\">";
			$html .= "</textarea>\n";
			$html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "			<td align=\"center\" colspan=\"4\">\n";
	        $html .= "				<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>\n";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}



		/***********************************************************************
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		* @param array $action vector que contine los link de la aplicacion
		* @param string $mensaje Cadena con el texto del mensaje a mostrar en pantalla
		* @return string
		************************************************************************/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('ADMINISTRACION DE PEDIDOS');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
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
			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
			$html .= "			</form>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}

			
	}
?>