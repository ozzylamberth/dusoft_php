<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: AdminPedidosDespachadosHTML.class
  * @copyright DUANA & CIA NOV-2012
  * @author L.G.T.L
  **********************************************************/

  /***********************************************************
  * Clase Vista: AdminPedidosDespachadosHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class AdminPedidosDespachadosHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function AdminPedidosDespachadosHTML(){}


		
		/********************************************************
		* Menu de Bodegas
		********************************************************/
		function MenuBodegas($action, $datos, $empresa_id)
		{
			$bodegas = $datos['bodegas'];
			$html  = ThemeAbrirTabla('DESPACHOS - BODEGAS','50%');
			
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
			  $BODEGA = ModuloGetURL("app", "AdminPedidosDespachados", "controller", "MenuPedidos")."&datos[empresa_id]=".$empresa_id;
			  //$BODEGA = ModuloGetURL("app", "AdminPedidosDespachados", "controller", "MenuFarmacias")."&datos[empresa_id]=".$empresa_id;
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
			
			$html  = "<script>\n"; 
			$html .= "	function ValidarCamposBuscador(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.destinatario_despacho.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE SELECCIONAR EL DESTINATARIO DEL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.pedido.action =\"".$action['MenuPedidos']."\"; \n";
			$html .= "    document.pedido.submit();\n";
			$html .= "	}\n";

		    $html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			/*$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.destinatario_despacho.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE SELECCIONAR EL DESTINATARIO DEL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";*/
			$html .= "    document.despachos_pedido.action =\"".$action['AdministrarDespachosPedido']."\"; \n";
			$html .= "    document.despachos_pedido.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";

			$html .= ThemeAbrirTabla('PEDIDO - DESPACHOS','50%');
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "</table>\n";
			
			//$html .= "<form name=\"pedido\" id=\"pedido\" action=\"".$action['MenuPedidos']."\" method=\"post\">\n";

			$html .= "<form name=\"pedido\" id=\"pedido\" action=\"javascript:ValidarCamposBuscador(document.pedido)\" method=\"post\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";

			$html .= "	<table width=\"90%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  	<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"3\">\n";
			$html .= "     			DESTINATARIO DESPACHO\n";
			$html .= "				<select id=\"destinatario_despacho\" name=\"destinatario_despacho\">\n";
			$html .= "					<option value=\"".$datos['destinatario_despacho']."\">".$datos['destinatario_despacho_label']."</option>\n";
	        $html .= "					<option value=\"0\">Cliente</option>\n";
        	$html .= "					<option value=\"1\">Farmacia</option>\n";
	        $html .= "				</select>\n";
			$html .= "     			PEDIDO NUMERO\n";
			$html .= "     			<input type=\"text\" class=\"input-text\" name=\"pedido_id\" value=\"".$pedido_id."\" style=\"width:20%\">\n";
			$html .= "     			<input type=\"submit\" class=\"input-submit\" value=\"Buscar\">\n";
			$html .= "     		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form><br>\n";
			$html .= "<form name=\"despachos_pedido\" id=\"despachos_pedido\" action=\"javascript:ValidarCampos(document.despachos_pedido)\" method=\"post\">\n";
			$html .= "	<input type=\"hidden\" name=\"pedido_id\" id=\"pedido_id\" value=\"".$pedido_id."\">\n";
			$html .= "	<input type=\"hidden\" name=\"destinatario_despacho\" id=\"destinatario_despacho\" value=\"".$datos['destinatario_despacho']."\">\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">PEDIDO\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">DOCUMENTO\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">CLIENTE/FARMACIA\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($pedidos); $i++) 
			{
	          //$BODEGA=ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('nom_bodegax'=>$vector[$i]['nom_bodega'],'utility'=>$vector[$i]['centro_utilidad'],'bodegax'=>$vector[$i]['bodega']));
			  //$PEDIDO = ModuloGetURL("app", "AdminPedidosDespachados", "controller", "AdministrarPedidoDespachado")."&datos[pedido_id]=".$pedidos[$i]['solicitud_prod_a_bod_ppal_id'];
	          $html .= "  </tr>\n";
	          $html .= "  <tr class=\"modulo_list_claro\">\n";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        ".$pedidos[$i]['pedido_id']."\n";
	          $html .= "     </td>";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        ".$pedidos[$i]['numero']."\n";
	          $html .= "     </td>";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          //$html .= "        ".$pedidos[$i]['razon_social']." ::: ".$pedidos[$i]['descripcion']."\n";
	          $html .= "        ".$pedidos[$i]['nombre']."\n";
	          $html .= "     </td>";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          if($pedidos[$i]['detalle_despacho_id'] == NULL)
	          {
	          	$html .= "        <input type=\"checkbox\" name=\"despacho[]\" value=\"".$pedidos[$i]['numero']."\">\n";
	          }
	          else
	          {
	          	$html .= "        <input type=\"checkbox\" name=\"despacho[]\" value=\"".$pedidos[$i]['numero']."\" checked=\"checked\" disabled=\"disabled\">\n";
	          }
	          $html .= "     </td>";
	          $html .= "  </tr>";
	       	}
	       	$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td align=\"center\" colspan=\"4\">\n";
	        $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">\n";
	        $html .= "		</td>\n";
	        $html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";
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

		/***************************************************************************
		* Función que visualiza formulario para diligenciar el detalle del despacho
		***************************************************************************/
		function AdministrarDespachosPedido($action, $datos, $pedido_id, $despacho, $destinatario_despacho)
		{
			$transportadoras = $datos['transportadoras'];

			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.cantidad_cajas.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA CANTIDAD DE CAJAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.cantidad_cajas.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA CANTIDAD DE CAJAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.cantidad_neveras.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA CANTIDAD DE NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.cantidad_neveras.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA CANTIDAD DE NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.temperatura.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR TEMPERATURA DE LAS NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.temperatura.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN LA TEMPERATURA DE LAS NEVERAS QUE CONTIENE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.peso.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR EL PESO TOTAL DE TODO LO QUE SE ENVIA EN EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(isNaN(forma.peso.value))\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR UN VALOR NUMERICO EN EL PESO TOTAL DE TODO LO QUE SE ENVIA EN EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.transportadora_id.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA TRANSPORTADORA A TRAVES DE LA CUAL SE ENVIA EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.placa_vehiculo.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR LA PLACA DEL VEHICULO POR MEDIO DEL CUAL SE ENVIA EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.placa_vehiculo.value.length != \"6\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"LA PLACA DEBE CONTENER SEIS DIGITOS\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.numero_guia.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR EL NUMERO DE LA GUIA CON LA CUAL SE HACE EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.nombre_conductor.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE DIGITAR EL NOMBRE DEL CONDUCTOR QUE MANEJA EL VEHICULO POR MEDIO DEL CUAL SE NEVIA EL DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.detalles_despacho_pedido.action =\"".$action['GuardarDetalleDespachosPedido']."\"; \n";
			$html .= "    document.detalles_despacho_pedido.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";

			$html .= ThemeAbrirTabla('PEDIDO - DESPACHOS','50%');

			$html .= "<form name=\"detalles_despacho_pedido\" id=\"detalles_despacho_pedido\" action=\"javascript:ValidarCampos(document.detalles_despacho_pedido)\" method=\"post\">\n";
			$html .= "	<input type=\"hidden\" name=\"pedido_id\" id=\"pedido_id\" value=\"".$pedido_id."\">\n";
			$html .= "	<input type=\"hidden\" name=\"destinatario_despacho\" id=\"destinatario_despacho\" value=\"".$destinatario_despacho."\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"4\">PEDIDO NUMERO ".$pedido_id."\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"2\"><strong>DESPACHOS</strong>\n";
			$html .= "  		</td>\n";
			$html .= "     		<td align=\"left\" colspan=\"2\">".$despacho[0]."\n";
			$html .= "			<input type=\"hidden\" name=\"despacho[]\" value=\"".$despacho[0]."\">\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			for($i = 1; $i < count($despacho); $i++)
	        {
	        	$html .= "		<tr class=\"modulo_list_claro\">\n";
				$html .= "     		<td align=\"left\" colspan=\"2\"> \n";
				$html .= "  		</td>\n";
				$html .= "     		<td align=\"left\" colspan=\"2\">".$despacho[$i]."\n";
				$html .= "			<input type=\"hidden\" name=\"despacho[]\" value=\"".$despacho[$i]."\">\n";
				$html .= "  		</td>\n";
				$html .= "  	</tr>\n";
	    	}
			$html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">MEDICAMENTOS SIN NEVERA\n";
			$html .= "  		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				Cantidad de Cajas\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"4\">\n";
	        $html .= "				<input type=\"text\" name=\"cantidad_cajas\" id=\"cantidad_cajas\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";
	        $html .= "		<tr class=\"modulo_list_claro\">\n";
			$html .= "     		<td align=\"left\" colspan=\"4\">MEDICAMENTOS CON NEVERA\n";
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
	        $html .= "				<input type=\"text\" name=\"cantidad_neveras\" id=\"cantidad_neveras\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				<input type=\"text\" name=\"temperatura\" id=\"temperatura\" value=\"\">\n";
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
	        $html .= "				<input type=\"text\" name=\"peso\" id=\"peso\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				<select name=\"transportadora_id\">\n";
	        $html .= "					<option value=\"\"></option>\n";
	        for($i = 0; $i < count($transportadoras); $i++)
	        {
	        	$html .= "<option value=\"".$transportadoras[$i]['transportadora_id']."\">".$transportadoras[$i]['descripcion']."</option>\n";
	    	}
	        $html .= "				</select>\n";	        
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
	        $html .= "				<input type=\"text\" name=\"placa_vehiculo\" id=\"placa_vehiculo\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\">\n";
	        $html .= "				<input type=\"text\" name=\"numero_guia\" id=\"numero_guia\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "			<td align=\"left\" class=\"normal_10AN\" colspan=\"2\">\n";
	        $html .= "				<input type=\"text\" name=\"nombre_conductor\" id=\"nombre_conductor\" value=\"\">\n";
	        $html .= "			</td>\n";
	        $html .= "		</tr>\n";			
	        $html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td align=\"center\" colspan=\"4\">\n";
	        $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">\n";
	        $html .= "		</td>\n";
	        $html .= "	</tr>\n";
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