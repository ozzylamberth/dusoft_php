<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: AdminPedidosConsultasHTML.class
  * @copyright DUANA & CIA 29-NOV-2012
  * @author L.G.T.L
  **********************************************************/

  /***********************************************************
  * Clase Vista: AdminPedidosConsultasHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class AdminPedidosConsultasHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function AdminPedidosConsultasHTML(){}


		/********************************************************
		* Listado de Despachos
		********************************************************/
		function ListadoDespachos($action, $filtro, $despachos_pedidos, $conteo, $pagina)
		{
			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "		if(forma.fecha_inicial.value == \"\")\n";
			$html .= "		{\n";
			$html .= "			objeto.innerHTML = \"SE DEBE SELECCIONAR LA FECHA INICIAL\";\n";
			$html .= "			return;\n";
			$html .= "		}\n";
			$html .= "    document.pedido.action =\"".$action['ListadoDespachos']."\"; \n";
			$html .= "    document.pedido.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";

			$html .= ThemeAbrirTabla('CONSULTA DE PEDIDOS - DESPACHOS','80%');
			$pgn = AutoCarga::factory("ClaseHTML");
			$html .= "<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "</table>\n";
				//$html .= "<form name=\"pedido\" id=\"pedido\" action=\"\" method=\"post\">\n";
				$html .= "<form name=\"pedido\" id=\"pedido\" action=\"javascript:ValidarCampos(document.pedido)\" method=\"post\">\n";
			$html .= "	<center><div class=\"label_error\" id=\"error\"></div></center>\n";
				$html .= "	<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
				$html .= "  	<tr class=\"modulo_table_list_title\">\n";
				$html .= "     		<td align=\"center\">Destinatario\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">Numero Pedido\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">Fecha Inicial\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">Fecha Final\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">Departamento\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">Municipio\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">Tipo Pedido\n";
				$html .= "     		</td>\n";
				$html .= "  	</tr>\n";
				$html .= "  	<tr class=\"modulo_table_list_title\">\n";
				$html .= "     		<td align=\"center\">\n";
				$html .= "				<select name=\"destinatario\">\n";
		        $html .= "					<option value=\"".$filtro['destinatario']."\">".$filtro['label_destinatario']."</option>\n";
	        	$html .= "					<option value=\"1\">Cliente</option>\n";
	        	$html .= "					<option value=\"2\">Farmacia</option>\n";
		        $html .= "				</select>\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"numero_pedido\" value=\"".$filtro['numero_pedido']."\" style=\"width:100%\">\n";
				$html .= "     		</td>\n";
				$html .= "          <td class=\"modulo_list_claro\">\n";
		        $html .= "             <input readonly=\"true\" type=\"text\" name=\"fecha_inicial\" id=\"fecha_inicial\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$filtro['fecha_inicial_vista']."\">\n";
		        $html .= "		       ".ReturnOpenCalendario('buscador','fecha_inicial','/',1)."\n";
		        $html .= "          </td>\n";
				$html .= "          <td class=\"modulo_list_claro\">\n";
		        $html .= "             <input readonly=\"true\" type=\"text\" name=\"fecha_final\" id=\"fecha_final\" class=\"input-text\" onkeypress=\"return acceptDate(event)\" value=\"".$filtro['fecha_final_vista']."\">\n";
		        $html .= "		       ".ReturnOpenCalendario('buscador','fecha_final','/',1)."\n";
		        $html .= "          </td>\n";
				$html .= "     		<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"departamento\" value=\"".$filtro['departamento']."\" style=\"width:100%\">\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"municipio\" value=\"".$filtro['municipio']."\" style=\"width:100%\">\n";
				$html .= "     		</td>\n";
				$html .= "     		<td align=\"center\">\n";
				$html .= "				<select name=\"tipo_pedido\">\n";
		        $html .= "					<option value=\"".$filtro['tipo_pedido']."\">".$filtro['label_tipo_pedido']."</option>\n";
	        	$html .= "					<option value=\"0\">Normal</option>\n";
	        	$html .= "					<option value=\"1\">General</option>\n";
		        $html .= "				</select>\n";
				$html .= "     		</td>\n";
				$html .= "  	</tr>\n";
				$html .= "  	<tr class=\"modulo_table_list_title\">\n";
				$html .= "     		<td align=\"center\" colspan=\"7\"><input type=\"submit\" class=\"input-submit\" value=\"Buscar\">\n";
				$html .= "     		</td>\n";
				$html .= "  	</tr>\n";
				$html .= "	</table>\n";
				$html .= "</form><br>\n";
			$html .= "<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"17\">CONSULTA DE PEDIDOS - DESPACHOS\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">Pedido\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Estado\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Responsable Estado\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Fecha Registro\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Despacho\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Fecha y Hora Despacho\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Transportadora\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Conductor\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Guia\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">No Cajas Despachadas\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">No Cajas Recibidas\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">No Neveras Despachadas\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">No Neveras Recibidas\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Observacion\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Fecha y Hora Entrega\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Farmacia/Cliente\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">Fecha y Hora Ingreso Inventario\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($despachos_pedidos); $i++) 
			{
				if($despachos_pedidos[$i]['diferencia_fechas'] >= 2) {
			        $html .= "  <tr class=\"modulo_list_claro\">\n";
			        $html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['pedido']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['estado']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['responsable_estado']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['fecha_registro_pedido']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['numero']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['fecha_despacho']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['transportadora']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['nombre_conductor']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['numero_guia']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['cantidad_cajas_despachadas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['cantidad_cajas_recibidas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['cantidad_neveras_despachadas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['cantidad_neveras_recibidas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['observacion']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['fecha_recibido']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['farmacia']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['fecha_registro_ingreso_inventario']."\n";
					$html .= "     </td>\n";
					$html .= "  </tr>\n";
				} else {
			        $html .= "  <tr class=\"modulo_list_claro\">\n";
			        $html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['pedido']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['estado']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['responsable_estado']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['fecha_registro_pedido']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['numero']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['fecha_despacho']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['transportadora']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['nombre_conductor']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\" style=\"color:#FF0000\">".$despachos_pedidos[$i]['numero_guia']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['cantidad_cajas_despachadas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['cantidad_cajas_recibidas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['cantidad_neveras_despachadas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['cantidad_neveras_recibidas']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['observacion']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['fecha_recibido']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['farmacia']."\n";
					$html .= "     </td>\n";
					$html .= "	   <td align=\"center\" class=\"normal_10AN\">".$despachos_pedidos[$i]['fecha_registro_ingreso_inventario']."\n";
					$html .= "     </td>\n";
					$html .= "  </tr>\n";
				}
	       	}
	       	$html .= "</table>\n";
			$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
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
	}
?>