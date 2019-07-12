<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: AdminPedidosEstadosHTML.class
  * @copyright DUANA & CIA NOV-2012
  * @author L.G.T.L
  **********************************************************/

  /***********************************************************
  * Clase Vista: AdminPedidosEstadosHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class AdminPedidosEstadosHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function AdminPedidosEstadosHTML(){}


		
		/********************************************************
		* Menu de Bodegas
		********************************************************/
		function MenuBodegas($action, $datos, $empresa_id)
		{
			$bodegas = $datos['bodegas'];
			$html  = ThemeAbrirTabla('ADMINISTRACION DE ESTADOS DE PEDIDOS','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

			$html .= "</table>\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">BODEGAS\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($bodegas); $i++) 
			{
			  $BODEGA = ModuloGetURL("app", "AdminPedidosEstados", "controller", "MenuPedidos")."&datos[empresa_id]=".$empresa_id."&datos[centro_utilidad]=".$bodegas[$i]['centro_utilidad']."&datos[bodega]=".$bodegas[$i]['bodega'];
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
		function MenuPedidos($action, $datos, $pedido_id, $empresa_id, $centro_utilidad, $bodega)
		{
			$pedidos = $datos['pedidos'];

			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.destinatario_pedido.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"DEBE SELECCIONAR EL DESTINATARIO DEL PEDIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.pedido.action =\"".$action['MenuPedidos']."\"; \n";
			$html .= "    document.pedido.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";

			$html .= ThemeAbrirTabla('ADMINISTRACION DE ESTADOS DE PEDIDOS','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
						
			$html .= "</table>\n";

			//$html .= "<form name=\"pedido\" id=\"pedido\" action=\"".$action['MenuPedidos']."\" method=\"post\">\n";
			$html .= "<form name=\"pedido\" id=\"pedido\" action=\"javascript:ValidarCampos(document.pedido)\" method=\"post\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "	<table width=\"90%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  	<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"3\">\n";
			$html .= "     			DESTINATARIO PEDIDO\n";
			$html .= "				<select id=\"destinatario_pedido\" name=\"destinatario_pedido\">\n";
			$html .= "					<option value=\"".$datos['destinatario_pedido']."\">".$datos['destinatario_pedido_label']."</option>\n";
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

			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">PEDIDOS\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($pedidos); $i++) 
			{
			  $PEDIDO = ModuloGetURL("app", "AdminPedidosEstados", "controller", "ResponsablesEstadosPedido")."&datos[pedido_id]=".$pedidos[$i]['pedido']."&datos[estado]=".$pedidos[$i]['estado']."&datos[destinatario_pedido]=".$datos['destinatario_pedido']."&empresa_id=".$empresa_id."&centro_utilidad=".$centro_utilidad."&bodega=".$bodega;
	          $html .= "  </tr>\n";
	          $html .= "  <tr class=\"modulo_list_claro\">\n";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        <a title=\"".$pedidos[$i]['pedido']."\" class=\"label_error\" href=\"".$PEDIDO."\">".$pedidos[$i]['pedido']." - ".$pedidos[$i]['estado']."</a>\n";
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

		/*******************************************************************************************************
		* Formulario para cambio de estado del pedido y asignar responsable del estado
		********************************************************************************************************/
		function ResponsablesEstadosPedido($action, $datos, $pedido_id, $estado, $siguiente_estado, $empresa_id, $centro_utilidad, $bodega)
		{
			$usuarios = $datos['usuarios'];
			$responsablesEstadosPedido = $datos['responsablesEstadosPedido'];
			
			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.responsable_separar.value == \"\" && forma.responsable_separar.disabled != true)\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL RESPONSABLE DE SEPARAR EL PEDIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.responsable_auditar.value == \"\" && forma.responsable_auditar.disabled != true)\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL RESPONSABLE DE AUDITAR EL PEDIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.responsable_despacho.value == \"\" && forma.responsable_despacho.disabled != true)\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL RESPONSABLE DE COLOCAR EL PEDIDO EN DESPACHO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "		if(forma.responsable_despachar.value == \"\" && forma.responsable_despachar.disabled != true)\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR EL RESPONSABLE DE DESPACHAR EL PEDIDO\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.responsables_estados_pedido.action =\"".$action['AsignarResponsablesEstadosPedido']."\"; \n";
			$html .= "    document.responsables_estados_pedido.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";
			$html .= ThemeAbrirTabla('ADMINISTRACION DE ESTADOS DE PEDIDOS DE '.$datos['destinatario_pedido_label'],'50%');
			$html .= "<form name=\"responsables_estados_pedido\" id=\"responsables_estados_pedido\" action=\"javascript:ValidarCampos(document.responsables_estados_pedido)\" method=\"post\">\n";
			$html .= "<input type=\"hidden\" name=\"pedido_id\" id=\"pedido_id\" value=\"".$pedido_id."\">\n";
			$html .= "<input type=\"hidden\" name=\"destinatario_pedido\" value=\"".$datos['destinatario_pedido']."\">\n";
			$html .= "<input type=\"hidden\" name=\"empresa_id\" value=\"".$empresa_id."\">\n";
			$html .= "<input type=\"hidden\" name=\"centro_utilidad\" value=\"".$centro_utilidad."\">\n";
			$html .= "<input type=\"hidden\" name=\"bodega\" value=\"".$bodega."\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"4\">ESTADO: ".$estado." - PEDIDO NUMERO ".$pedido_id."\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "<tr class=\"modulo_list_claro\">\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        $html .= "Responsable de Separar\n";
	        $html .= "</td>\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        $html .= "Responsable de Auditar\n";
	        $html .= "</td>\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        $html .= "Responsable de la Zona de Despacho\n";
	        $html .= "</td>\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        $html .= "Responsable de Despachar\n";
	        $html .= "</td>\n";
	        $html .= "</tr>\n";
	        $html .= "<tr class=\"modulo_list_claro\">\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        if($estado != "REGISTRADO") {
	        	$html .= "<select name=\"responsable_separar\" disabled=\"disabled\">\n";
	        } else {
	        	$html .= "<select name=\"responsable_separar\">\n";
	        }
	        $html .= "<option value=\"".$responsablesEstadosPedido[0]['responsable_id']."\">".$responsablesEstadosPedido[0]['nombre']."</option>\n";
	        for($i = 0; $i < count($usuarios); $i++)
	        {
	        	$html .= "<option value=\"".$usuarios[$i]['operario_id']."\">".$usuarios[$i]['nombre']."</option>\n";
	    	}
	        $html .= "</select>\n";
	        $html .= "</td>\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        if($estado != "SEPARADO") {
	        	$html .= "<select name=\"responsable_auditar\" disabled=\"disabled\">\n";
	        } else {
	        	$html .= "<select name=\"responsable_auditar\">\n";
	        }
	        $html .= "<option value=\"".$responsablesEstadosPedido[1]['responsable_id']."\">".$responsablesEstadosPedido[1]['nombre']."</option>\n";
	        for($i = 0; $i < count($usuarios); $i++)
	        {
	        	$html .= "<option value=\"".$usuarios[$i]['operario_id']."\">".$usuarios[$i]['nombre']."</option>\n";
	    	}
	        $html .= "</select>\n";
	        $html .= "</td>\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        if($estado != "AUDITADO") {
	        	$html .= "<select name=\"responsable_despacho\" disabled=\"disabled\">\n";
	        } else {
	        	$html .= "<select name=\"responsable_despacho\">\n";
	        }
	        $html .= "<option value=\"".$responsablesEstadosPedido[2]['responsable_id']."\">".$responsablesEstadosPedido[2]['nombre']."</option>\n";
	        for($i = 0; $i < count($usuarios); $i++)
	        {
	        	$html .= "<option value=\"".$usuarios[$i]['operario_id']."\">".$usuarios[$i]['nombre']."</option>\n";
	    	}
	        $html .= "</select>\n";
	        $html .= "</td>\n";
	        $html .= "<td align=\"center\" class=\"normal_10AN\">\n";
	        if($estado != "EN DESPACHO") {
	        	$html .= "<select name=\"responsable_despachar\" disabled=\"disabled\">\n";
	        } else {
	        	$html .= "<select name=\"responsable_despachar\">\n";
	        }
	        $html .= "<option value=\"".$responsablesEstadosPedido[3]['responsable_id']."\">".$responsablesEstadosPedido[3]['nombre']."</option>\n";
	        for($i = 0; $i < count($usuarios); $i++)
	        {
	        	$html .= "<option value=\"".$usuarios[$i]['operario_id']."\">".$usuarios[$i]['nombre']."</option>\n";
	    	}
	        $html .= "</select>\n";
	        $html .= "</td>\n";
	        $html .= "</tr>\n";
	        $html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td align=\"center\" colspan=\"4\">\n";
			if($datos['destinatario_pedido'] == 0) {
		        if(array_key_exists("estado", $datos) && $datos['estado'] == 1) {
		        	$html .= "			EL PEDIDO AUN NO HA SIDO AUDITADO";
		        } else {
		        	$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Enviar a ".$siguiente_estado." y Asignar Responsable\">\n";
		        }
	    	} elseif($datos['destinatario_pedido'] == 1) {
		        if(array_key_exists("sw_despacho", $datos) && $datos['sw_despacho'] == 0) {
		        	$html .= "			EL PEDIDO AUN NO HA SIDO AUDITADO";
		        } else {
		        	$html .= "			<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Enviar a ".$siguiente_estado." y Asignar Responsable\">\n";
		        }
	    	}
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
		function FormaMensajeModulo($action,$mensaje,$destinatario_pedido_label,$empresa_id,$centro_utilidad,$bodega)
		{
			$html  = ThemeAbrirTabla('ADMINISTRACION DE ESTADOS DE PEDIDOS DE '.$destinatario_pedido_label);
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
			$html .= "				<input type=\"hidden\" name=\"datos[empresa_id]\" value=\"".$empresa_id."\">\n";
			$html .= "				<input type=\"hidden\" name=\"datos[centro_utilidad]\" value=\"".$centro_utilidad."\">\n";
			$html .= "				<input type=\"hidden\" name=\"datos[bodega]\" value=\"".$bodega."\">\n";
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