<?php

   /*********************************************************
  * @package DUANA & CIA
  * @version 1.0 $Id: PlanillaDespachoPedidoHTML.class
  * @copyright DUANA & CIA 03-DIC-2012
  * @author L.G.T.L
  **********************************************************/

  /***********************************************************
  * Clase Vista: PlanillaDespachoPedidoHTML
  * Clase Contiene menus de modulo 
  ************************************************************/

	class PlanillaDespachoPedidoHTML
	{
		/********************************************************
		* Constructor de la clase
		********************************************************/
		function PlanillaDespachoPedidoHTML(){}


		
		/********************************************************
		* Menu de Bodegas
		********************************************************/
		function MenuBodegas($action, $datos, $empresa_id)
		{
			$bodegas = $datos['bodegas'];
			$html  = ThemeAbrirTabla('PLANILLAS DE DESPACHOS - BODEGAS','50%');
			
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";

			$html .= "</table>\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\" colspan=\"3\">BODEGAS\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i=0; $i < count($bodegas); $i++) 
			{
			  $BODEGA = ModuloGetURL("app", "PlanillaDespachoPedido", "controller", "MenuDetallesDespachos")."&datos[empresa_id]=".$empresa_id;
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
		function MenuDetallesDespachos($action, $datos, $pedido_id, $empresa_id, $planilla_id, $planillas_despachos)
		{
			$detalles_despachos = $datos['detalles_despachos'];
			$html  = "<script>\n"; 
			$html .= "	function ValidarCampos(forma)\n";
			$html .= "	{\n";
			$html .= "		objeto = document.getElementById('error');\n";
			$html .= "    document.planilla_despachos.action =\"".$action['GenerarPlanillaDespachos']."\"; \n";
			$html .= "    document.planilla_despachos.submit();\n";
			$html .= "	}\n";
		    $html .= "</script>\n";
			$html .= ThemeAbrirTabla('PLANILLAS DE DESPACHOS','50%');
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "</table>\n";
			$html .= "<form name=\"planilla_despachos\" id=\"planilla_despachos\" action=\"javascript:ValidarCampos(document.planilla_despachos)\" method=\"post\">\n";
			$html .= "	<input type=\"hidden\" name=\"pedido_id\" id=\"pedido_id\" value=\"".$pedido_id."\">\n";
			//$html .= "	<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$empresa_id."\">\n";
			$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "     <td align=\"center\">DESPACHO (DETALE)\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">PEDIDO\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">FECHA DESPACHO\n";
			$html .= "     </td>\n";
			$html .= "     <td align=\"center\">\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			for ($i = 0; $i < count($detalles_despachos); $i++) 
			{
	          $html .= "  </tr>\n";
	          $html .= "  <tr class=\"modulo_list_claro\">\n";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        ".$detalles_despachos[$i]['detalle_despacho_id_label']."\n";
	          $html .= "     </td>";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        ".$detalles_despachos[$i]['pedido_id']."\n";
	          $html .= "     </td>";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          $html .= "        ".$detalles_despachos[$i]['fecha_registro']."\n";
	          $html .= "     </td>";
	          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
	          if($detalles_despachos[$i]['planilla_despacho_id'] == NULL)
	          {
	          	$html .= "        <input type=\"checkbox\" name=\"detalle_despachos[]\" value=\"".$detalles_despachos[$i]['detalle_despacho_id']." - ".$detalles_despachos[$i]['destinatario']."\">\n";
	          }
	          else
	          {
	          	$html .= "        <input type=\"checkbox\" name=\"detalle_despachos[]\" value=\"".$detalles_despachos[$i]['detalle_despacho_id']."\" checked=\"checked\" disabled=\"disabled\">\n";
	          }
	          $html .= "     </td>";
	          $html .= "  </tr>";
	       	}
	       	$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "		<td align=\"center\" colspan=\"4\">\n";
	        $html .= "			<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Generar Planilla de Despachos\">\n";
	        $html .= "		</td>\n";
	        $html .= "	</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>\n";

			$html .= "<form name=\"planilla\" id=\"planilla\" action=\"".$action['MenuPlanillas']."\" method=\"post\">\n";
			$html .= "	<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
			$html .= "  	<tr class=\"modulo_table_list_title\">\n";
			$html .= "     		<td align=\"center\" colspan=\"3\">PLANILLA DE DESPACHO NUMERO <input type=\"text\" class=\"input-text\" name=\"planilla_id\" value=\"".$planilla_id."\" style=\"width:20%\"> <input type=\"hidden\" class=\"input-text\" name=\"empresa_id\" value=\"".$empresa_id."\" style=\"width:20%\"> <input type=\"submit\" class=\"input-submit\" value=\"Buscar\">\n";
			$html .= "     		</td>\n";
			$html .= "  	</tr>\n";
			$html .= "	</table>\n";
			$html .= "</form>\n";

			if(count($planillas_despachos) > 0) {
				$html .= "<table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
				$html .= "  <tr class=\"modulo_table_list_title\">\n";
				$html .= "     <td align=\"center\" colspan=\"3\">PLANILLA DE DESPACHO NUMERO\n";
				$html .= "     </td>\n";
				$html .= "  </tr>\n";
				for ($i=0; $i < count($planillas_despachos); $i++) 
				{
				  $PLANILLA = ModuloGetURL("app", "PlanillaDespachoPedido", "controller", "VisualizarPlanillaDespachos")."&planilla_despachos_id=".$planillas_despachos[$i]['planilla_despacho_id'];
		          $html .= "  </tr>\n";
		          $html .= "  <tr class=\"modulo_list_claro\">\n";
		          $html .= "     <td align=\"center\" class=\"normal_10AN\">\n";
		          //$html .= "        <a title=\"".$planillas_despachos[$i]['planilla_despacho_id']."\" class=\"label_error\" href=\"".$PLANILLA."\">".$planillas_despachos[$i]['planilla_despacho_id']."</a>\n";
		          $html .= "<a href=\"".$PLANILLA."\" target=\"_blank\" onClick=\"window.open(this.href, this.target, 'width=1000, height=400, scrollbars=yes'); return false;\">".$planillas_despachos[$i]['planilla_despacho_id']." - Visualizar Planilla</a>";
		          $html .= "     </td>";
		          $html .= "  </tr>";
		       	}
				$html .= "</table>\n";
			}

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

		/***********************************************************************
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		* @param array $action vector que contine los link de la aplicacion
		* @param string $mensaje Cadena con el texto del mensaje a mostrar en pantalla
		* @return string
		************************************************************************/
		function FormaMensajeModulo($action,$mensaje,$idUltimoPlanillaDespachos)
		{
			$html  = ThemeAbrirTabla('ADMINISTRACION DE PEDIDOS');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td colspan=\"2\">\n";
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
			$html .= "		<td align=\"center\"><br>\n";
			
			$PLANILLA_DESPACHOS = ModuloGetURL("app", "PlanillaDespachoPedido", "controller", "VisualizarPlanillaDespachos")."&planilla_despachos_id=".$idUltimoPlanillaDespachos;

			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"".$PLANILLA_DESPACHOS."\">";
			
			$html .= "				<input class=\"input-submit\" type=\"button\" name=\"visualizar\" value=\"Visualizar\" onClick=\"window.open('".$PLANILLA_DESPACHOS."', 'hol', 'width=1000, height=400, scrollbars=yes')\">";
			
			//$PLANILLA_DESPACHOS = ModuloGetURL("app", "PlanillaDespachoPedido", "controller", "VisualizarPlanillaDespachos")."&planilla_despachos_id=".'28';
			
			//$html .= "<a href=\"".$PLANILLA_DESPACHOS."\" target=\"_blank\" onClick=\"window.open(this.href, this.target, 'width=1000, height=400, scrollbars=yes'); return false;\">Visualizar Planilla</a>";

			$html .= "			</form>";

			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();			
			return $html;
		}

		/***********************************************************************
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		* @param array $action vector que contine los link de la aplicacion
		* @param string $mensaje Cadena con el texto del mensaje a mostrar en pantalla
		* @return string
		************************************************************************/
		function PlanillaDespachos($planilla_despachos_id,$detalles_despachos,$efc_detalle_despacho)
		{
			$total_detalles_despachos = count($detalles_despachos);
			$numero_paginas = ceil(($total_detalles_despachos / 2)) + 1;

			$html = "<table border=\"0\" align=\"center\" width=\"100%\">";
			$html .= "	<tr>";
			$html .= "		<td align=\"center\" class=\"label\">";
			$html .= "			<img width=\"121\" border=\"0\" height=\"70\" src=\"themes/HTML/AzulXp/images/logotipo1.png\">";
			$html .= "		</td>";
			$html .= "		<td align=\"center\" class=\"label\">";
			$html .= "			<span style=\"font-size: 25px; font-weight: bold;\">";
			$html .= "				PLANILLA DE DESPACHOS DE MERCANCIA";
			$html .= "			</span>";
			$html .= "		</td>";
			$html .= "		<td align=\"center\" class=\"label\">";
			$html .= "			<span style=\"font-size: 10px;\">";
			$html .= "				Pagina 1 de ".$numero_paginas."";
			$html .= "			</span>";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "</table><br>";

			$total_cajas = 0;
			$total_neveras = 0;

			for($i = 0; $i < $total_detalles_despachos; $i++){

				$total_cajas = $total_cajas + $detalles_despachos[$i]['cantidad_cajas'];
				$total_neveras = $total_neveras + $detalles_despachos[$i]['cantidad_neveras'];
				
				$html .= "<table border=\"0\" align=\"center\" width=\"100%\">";
				$html .= "	<tr>";
				$html .= "		<td class=\"label\">";
				$html .= "			".$detalles_despachos[$i]['transportadora']."";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			".$detalles_despachos[$i]['placa_vehiculo']."";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			Guia Nro";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			".$detalles_despachos[$i]['numero_guia']."";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "	<tr>";
				$html .= "		<td class=\"label\">";
				$html .= "			Fecha de Salida";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			".date("d-m-Y")."";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "</table>";
				
				$html .= "<table border=\"0\" align=\"center\" width=\"100%\">";
				$html .= "	<tr>";
				$html .= "		<td width=\"20%\" class=\"label\">";
				$html .= "			";
				$html .= "		</td>";
				$html .= "		<td width=\"20%\" class=\"label\">";
				$html .= "			";
				$html .= "		</td>";
				$html .= "		<td width=\"20%\" class=\"label\">";
				//$html .= "			Ciudad de despacho:";
				$html .= "		</td>";
				/*$html .= "		<td>";
				//$html .= "			".$detalles_despachos[$i]['ciudad']."";
				$html .= "		</td>";*/

				$html .= "		<td width=\"40%\" class=\"label\">";
				$html .= "			Ciudad de despacho: ".$detalles_despachos[$i]['ciudad']."";
				$html .= "		</td>";


				$html .= "	</tr>";
				$html .= "	<tr>";
				$html .= "		<td colspan=\"3\" class=\"label\">";
				$html .= "			".$detalles_despachos[$i]['empresa']."<br>";
				$html .= "			".$detalles_despachos[$i]['direccion']."";
				$html .= "		</td>";
				/*$html .= "		<td>";
				$html .= "			";
				$html .= "		</td>";
				$html .= "		<td>";
				$html .= "			";
				$html .= "		</td>";*/
				/*$html .= "		<td>";
				$html .= "			";
				$html .= "		</td>";*/
				$html .= "		<td rowspan=\"2\" class=\"label\">";
				$html .= "			Comentario del Despacho<br>";
				$html .= "			<textarea cols=\"30\" rows=\"4\" name=\"comentario\" id=\"comentario\">";
				$html .= "			</textarea>\n";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "	<tr>";
				$html .= "		<td class=\"label\">";
				$html .= "			Documento Cruce";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				//$html .= "			Nro de Unidades";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				//$html .= "			Nro de Neveras";
				$html .= "		</td>";
				//$html .= "		<td>";
				//$html .= "			Temperatura";
				//$html .= "		</td>";
				$html .= "	</tr>";

				$cantidad_documentos = 0;

				for($k = 0; $k < count($efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']]); $k++) {

					$cantidad_documentos = $cantidad_documentos + 1;
					
					/*$html .= "	<tr>";

					$html .= "		<td>";
					$html .= "		".$efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']][$k]['numero']."<br>\n";
					$html .= "		</td>";

					if(($k + 1) % 2 == 0 && (($k + 1) / 2) % 2 == 0 && ($k + 1) % 3 != 0) {
						$html .= "		<td>";
						$html .= "		</td>";
					}

					if(($k + 1) % 3 == 0) {
						$html .= "		<td>";
						$html .= "		hola<br>\n";
						$html .= "		</td>";
					}*/

					/*if(($k + 1) % 2 != 0 && (($k + 1) / 2) % 2 != 0 && ($k + 1) % 3 != 0) {
						$html .= "		<td>";
						$html .= "		".$efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']][$k]['numero']."<br>\n";
						$html .= "		</td>";
					}

					if(($k + 1) % 2 == 0 && (($k + 1) / 2) % 2 == 0) {
						$html .= "		<td>";
						$html .= "		".$efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']][$k]['numero']."<br>\n";
						$html .= "		</td>";
					}

					if(($k + 1) % 3 == 0) {
						$html .= "		<td>";
						$html .= "		".$efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']][$k]['numero']."<br>\n";
						$html .= "		</td>";
					}*/

					//$html .= "	</tr>";
				}
				
				$documentos_efc = $efc_detalle_despacho[$detalles_despachos[$i]['pedido_id']];

				if(array_key_exists(0, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[0]['destinatario']) {
				    $html .= "	<tr>";
				    $html .= "		<td class=\"label\">";
				    $html .= "		".$documentos_efc[0]['numero']."\n";
				    $html .= "		</td>";

				    if(array_key_exists(1, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[1]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[1]['numero']."\n";
					    $html .= "		</td>";
				    }

				    if(array_key_exists(2, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[2]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[2]['numero']."\n";
					    $html .= "		</td>";
				    }

				    $html .= "	</tr>";
				}

				if(array_key_exists(3, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[3]['destinatario']) {
				    $html .= "	<tr>";
				    $html .= "		<td class=\"label\">";
				    $html .= "		".$documentos_efc[3]['numero']."\n";
				    $html .= "		</td>";

				    if(array_key_exists(4, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[4]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[4]['numero']."\n";
					    $html .= "		</td>";
				    }

				    if(array_key_exists(5, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[5]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[5]['numero']."\n";
					    $html .= "		</td>";
				    }

				    $html .= "	</tr>";
				}

				if(array_key_exists(6, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[6]['destinatario']) {
				    $html .= "	<tr>";
				    $html .= "		<td class=\"label\">";
				    $html .= "		".$documentos_efc[6]['numero']."\n";
				    $html .= "		</td>";

				    if(array_key_exists(7, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[7]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[7]['numero']."\n";
					    $html .= "		</td>";
				    }

				    if(array_key_exists(8, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[8]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[8]['numero']."\n";
					    $html .= "		</td>";
				    }

				    $html .= "	</tr>";
				}

				if(array_key_exists(9, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[9]['destinatario']) {
				    $html .= "	<tr>";
				    $html .= "		<td class=\"label\">";
				    $html .= "		".$documentos_efc[9]['numero']."\n";
				    $html .= "		</td>";

				    if(array_key_exists(10, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[10]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[10]['numero']."\n";
					    $html .= "		</td>";
				    }

				    if(array_key_exists(11, $documentos_efc) && $detalles_despachos[$i]['destinatario'] == $documentos_efc[11]['destinatario']) {
				    	$html .= "		<td class=\"label\">";
					    $html .= "		".$documentos_efc[11]['numero']."\n";
					    $html .= "		</td>";
				    }

				    $html .= "	</tr>";
				}

				$html .= "	<tr>";
				$html .= "		<td colspan=\"3\" class=\"label\">";
				$html .= "			Se despacha a ".$detalles_despachos[$i]['empresa']." (".$cantidad_documentos." remisiones de despacho)";
				$html .= "		</td>";
				$html .= "		<td rowspan=\"2\" class=\"label\">";
				$html .= "			<table border=\"1\" width=\"100%\">";
				$html .= "				<tr>";
				$html .= "					<td width=\"50%\" class=\"label\">";
				$html .= "						________________________<br>";
				$html .= "						Nombre Completo y Cedula";
				$html .= "					</td>";
				$html .= "					<td class=\"label\">";
				$html .= "						Recibe<br>";
				$html .= "						<table border=\"1\" width=\"100%\">";
				$html .= "							<tr>";
				$html .= "								<td class=\"label\">&nbsp;";
				$html .= "								</td>";
				$html .= "								<td class=\"label\">&nbsp;";
				$html .= "								</td>";
				$html .= "								<td class=\"label\">&nbsp;";
				$html .= "								</td>";
				$html .= "							</tr>";
				$html .= "							<tr>";
				$html .= "								<td colspan=\"3\" class=\"label\">&nbsp;";
				$html .= "								</td>";
				$html .= "							</tr>";
				$html .= "						</table>";
				$html .= "					</td>";
				$html .= "				</tr>";
				$html .= "			</table>";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "	<tr>";
				$html .= "		<td class=\"label\">";
				$html .= "			Con un total de";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			".$detalles_despachos[$i]['cantidad_cajas']." Cajas";
				$html .= "		</td>";
				$html .= "		<td class=\"label\">";
				$html .= "			".$detalles_despachos[$i]['cantidad_neveras']." Neveras - ".$detalles_despachos[$i]['temperatura_neveras']." Grados C";
				$html .= "		</td>";
				/*$html .= "		<td>";
				$html .= "			Con ".$detalles_despachos[$i]['temperatura_neveras']." Grados C";
				$html .= "		</td>";*/
				$html .= "	</tr>";
				$html .= "</table><hr>";

				if(($i + 1) % 2 == 0) {
					$html .= "<div class=\"SaltoDePagina\"></div>";

					$html .= "<table border=\"0\" align=\"center\" width=\"100%\">";
					$html .= "	<tr>";
					$html .= "		<td align=\"center\" class=\"label\">";
					$html .= "			<img width=\"121\" border=\"0\" height=\"70\" src=\"themes/HTML/AzulXp/images/logotipo1.png\">";
					$html .= "		</td>";
					$html .= "		<td align=\"center\" class=\"label\">";

					$html .= "			<span style=\"font-size: 25px; font-weight: bold;\">";
					$html .= "				PLANILLA DE DESPACHOS DE MERCANCIA";
					$html .= "			</span>";

					$html .= "		</td>";

					$html .= "		<td align=\"center\" class=\"label\">";
					$html .= "			<span style=\"font-size: 10px;\">";
					//$numero_paginas = ceil(($total_detalles_despachos / 2)) + 1;
					$html .= "				Pagina ".ceil(($i + 2) / 2)." de ".$numero_paginas."";
					$html .= "			</span>";
					$html .= "		</td>";


					$html .= "	</tr>";
					$html .= "</table><br>";
				}
			}

			if(($i) % 2 != 0) {
				$html .= "<div class=\"SaltoDePagina\"></div>";

				$html .= "<table border=\"0\" align=\"center\" width=\"100%\">";
				$html .= "	<tr>";
				$html .= "		<td align=\"center\" class=\"label\">";
				$html .= "			<img width=\"121\" border=\"0\" height=\"70\" src=\"themes/HTML/AzulXp/images/logotipo1.png\">";
				$html .= "		</td>";
				$html .= "		<td align=\"center\" class=\"label\">";
				$html .= "			<span style=\"font-size: 25px; font-weight: bold;\">";
				$html .= "				PLANILLA DE DESPACHOS DE MERCANCIA";
				$html .= "			</span>";
				$html .= "		</td>";

				$html .= "		<td align=\"center\" class=\"label\">";
				$html .= "			<span style=\"font-size: 10px;\">";
				//$numero_paginas = ceil(($total_detalles_despachos / 2)) + 1;
				$html .= "				Pagina ".$numero_paginas." de ".$numero_paginas."";
				$html .= "			</span>";
				$html .= "		</td>";

				$html .= "	</tr>";
				$html .= "</table><br>";
				$html .= "<table border=\"0\" align=\"center\" width=\"100%\">";
				$html .= "	<tr>";
				$html .= "		<td colspan=\"2\" class=\"label\">";
				$html .= "			Como mecanismo para definir responsabilidades el TRANSPORTISTA firmara la presente planilla entre tanto devuelva la misma con las respectivas firmas de recibido de cada sede donde se entregue la mercancia. Por lo tanto se le hace la entrega total de ".$total_cajas." caja(s) y ".$total_neveras." nevera(s).<br><br><br>";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "	<tr>";
				$html .= "		<td width=\"80%\" class=\"label\">";
				$html .= "			<span align=\"right\">";
				$html .= "				_________________________________<br>";
				$html .= "				AUX BODEGA A CARGO DEL DESPACHO";
				$html .= "			</span>";
				$html .= "		</td>";
				$html .= "		<td width=\"20%\" class=\"label\">";
				$html .= "			<span align=\"right\">";
				$html .= "				________________________<br>";
				$html .= "				RECIBIDO TRANSPORTADOR";
				$html .= "			</span>";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "</table>";
			} else {
				$html .= "<table border=\"0\" align=\"center\" width=\"100%\">";
				$html .= "	<tr>";
				$html .= "		<td colspan=\"2\" class=\"label\">";
				$html .= "			Como mecanismo para definir responsabilidades el TRANSPORTISTA firmara la presente planilla entre tanto devuelva la misma con las respectivas firmas de recibido de cada sede donde se entregue la mercancia. Por lo tanto se le hace la entrega total de ".$total_cajas." caja(s) y ".$total_neveras." nevera(s).<br><br><br>";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "	<tr>";
				$html .= "		<td width=\"80%\" class=\"label\">";
				$html .= "			<span align=\"right\">";
				$html .= "				_________________________________<br>";
				$html .= "				AUX BODEGA A CARGO DEL DESPACHO";
				$html .= "			</span>";
				$html .= "		</td>";
				$html .= "		<td width=\"20%\" class=\"label\">";
				$html .= "			<span align=\"right\">";
				$html .= "				________________________<br>";
				$html .= "				RECIBIDO TRANSPORTADOR";
				$html .= "			</span>";
				$html .= "		</td>";
				$html .= "	</tr>";
				$html .= "</table>";
			}

			return $html;
		}
	}
?>