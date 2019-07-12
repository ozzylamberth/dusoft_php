<?php
		IncludeClass('NotasCredito','','app','FacturacionNotaCreditoAjuste');
		IncludeClass('NotasDebitoHTML','','app','FacturacionNotaCreditoAjuste');
		IncludeClass('ClaseHTML');
		function Buscarfacturas($prefijo,$factura,$empresa,$offset)
		{
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
			$facturas = $nc->ObtenerFacturasExternas($prefijo,$factura,$empresa,$offset);
			$html = CrearListadoNotasCredito($facturas,$nc);
			$html = utf8_encode( $html );

			$objResponse->assign("error","innerHTML",utf8_encode($nc->frmError['MensajeError']));
			$objResponse->assign("resultado","innerHTML",$html);
			return $objResponse;
		}
		/**
		*
		**/
		function AdicionarConceptos($concepto,$deptno,$tercer,$valor,$empresa,$tmp_id)
		{
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
			
			$tercero = array("tipo_id_tercero"=>"NULL","tercero_id"=>"NULL");
			
			(!$deptno)? $deptno = "NULL":$deptno = "'".$deptno."'";
			if($tercer)
			{
				$aux = explode("*",$tercer);
				$tercero['tipo_id_tercero'] = "'".$aux[0]."'";
				$tercero['tercero_id'] = "'".$aux[1]."'";
			}
					
			$rst = $nc->AddConceptosExternos($tmp_id,$concepto,$deptno,$tercero,$empresa,$valor);
			if(!$rst)
				$objResponse->assign("error","innerHTML",utf8_encode($nc->frmError['MensajeError']));
			else
			{
				$lista = $nc->ObtenerConceptosExternosAdicionados($tmp_id,$empresa);
				$html = NotasDebitoHTML::CrearListaConceptosExternos($lista);
				$html = utf8_encode($html);
				$objResponse->assign("lista_conceptos","innerHTML",$html);
				$objResponse->call("Finalizar");
			}
			return $objResponse;
		}
		/**
		*
		**/
		function EliminarConcepto($concepto_id,$tmp_concepto_id,$tmp_id,$empresa)
		{
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
			$rst = $nc->EliminarConceptosExternos($tmp_concepto_id,$concepto_id);
			
			if(!$rst)
				$objResponse->assign("error","innerHTML",utf8_encode($nc->frmError['MensajeError']));
			else
			{
				$lista = $nc->ObtenerConceptosExternosAdicionados($tmp_id,$empresa);
				$html = NotasDebitoHTML::CrearListaConceptosExternos($lista);
				$html = utf8_encode($html);
				$objResponse->assign("lista_conceptos","innerHTML",$html);
			}
			return $objResponse;
		}
		/**
		*
		**/
		function ActualizarInformacion($tmp_id,$observacion)
		{
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
							
			$rst = $nc->ModificarObservacion($tmp_id,$observacion);
			if(!$rst)
				$objResponse->assign("error","innerHTML",utf8_encode($nc->frmError['MensajeError']));

			return $objResponse;
		}
		/**
		*
		**/
		function EliminarNota($tmp_id,$empresa)
		{
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
			
			$html = "<label class=\"normal_10AN\">LA NOTA DE AJUSTE FUE ELIMINADA</label>";
			$rst = $nc->EliminarNotaAjusteExterna($tmp_id);
			if(!$rst)
				$objResponse->assign("error","innerHTML",utf8_encode($nc->frmError['MensajeError']));
			else
			{
				$objResponse->assign("error","innerHTML",utf8_encode($html));
				$nd = new NotasDebitoHTML();
				$notas = $nc->ObtenerNotasDeAjuste($empresa);
				$html = $nd->CrearListadoNotasAjuste($notas);
				$html = utf8_encode( $html );
				$objResponse->assign("notasCredito","innerHTML",$html);
			}
			return $objResponse;
		}
		/**
		*
		**/
		function CerrarNota($tmp_id,$empresa)
		{
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
			
			$html = "";
			$rst = $nc->CerrarNotaAjusteExterna($tmp_id,$empresa);
			if(!$rst)
				$objResponse->assign("error","innerHTML",utf8_encode($nc->frmError['MensajeError']));
			else
			{
				SessionSetVar("PrefijoNotaCredito",$rst['prefijo']);
				SessionSetVar("NumeroNotaCredito",$rst['numeracion']);
			
				$funcion = SessionGetVar("FuncionImprimir");
			
				$html1 .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
				$html1 .= "	<tr>\n";
				$html1 .= "			<td align=\"center\" class=\"normal_10AN\">\n";
				$html1 .= "				SE GENERO LA NOTA CREDITO Nº ".$rst['prefijo']." ".$rst['numeracion']."\n";;
				$html1 .= "			</td>\n";
				$html1 .= "	</tr>\n";
				$html1 .= "	<tr>\n";
				$html1 .= "			<td align=\"center\">\n";
				$html1 .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Nota Credito\" onclick=\"$funcion\">\n";
				$html1 .= "			</td>\n";
				$html1 .= "	</tr>\n";
				$html1 .= "</table><br>\n";
			
				$objResponse->assign("error","innerHTML",utf8_encode($html));
				$nd = new NotasDebitoHTML();
				$notas = $nc->ObtenerNotasDeAjuste($empresa);
				$html = $nd->CrearListadoNotasAjuste($notas);
				$html = utf8_encode( $html );
				$html1 = utf8_encode( $html1 );
				$objResponse->assign("notasCredito","innerHTML",$html);
				$objResponse->assign("confirmacion","innerHTML",$html1);
			}
			return $objResponse;
		}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function CrearNota($prefijo,$factura,$empresa,$observacion,$auditor)
		{
			if($auditor == '0' || !$auditor) $auditor = "NULL";
			
			$nc = new NotasCredito();
			$objResponse = new xajaxResponse();
			$facturas = $nc->ObtenerFacturasExternas($prefijo,$factura,$empresa);
			
			//$objResponse->alert(print_r($facturas,true));
			//$objResponse->alert($nc->frmError['MensajeError']);
			
			$rst = $nc->CrearNotaAjusteBD($facturas[0],$empresa,$observacion,$auditor);
			
			$error = "";
			if(!$rst)
			{			
				$error = $nc->frmError['MensajeError'];
				$objResponse->assign("error","innerHTML",utf8_encode($error));
			}
			else
			{
				$nd = new NotasDebitoHTML();
				$notas = $nc->ObtenerNotasDeAjuste($empresa);
				$html = $nd->CrearListadoNotasAjuste($notas);
				$html = utf8_encode( $html );
				$objResponse->assign("notasCredito","innerHTML",$html);
				$objResponse->call("LimpiarCampos");
			}
			
			$html  = "							<table align=\"center\">\n";
			$html .= "								<tr><td height=\"25\"><a href=\"javascript:OcultarSpan('FacturasB')\" class=\"label_error\">CERRAR</a></td></tr>\n";
			$html .= "							</table>\n";
			
			$html = utf8_encode( $html );
			$objResponse->assign("resultado","innerHTML",$html);
			
			return $objResponse;
		}
		/**********************************************************************************
		* Funcion donde se buscan las Notas De Ajuste temporales 
		* 
		* @return array 
		***********************************************************************************/
		function CrearListadoNotasCredito($facturas,$nc)
		{			
			$action = "CrearVariables(document.buscadorfacturas";
			$html  = "<br><center><b class=\"label_error\">NO SE ENCONTRO NOTAS CREADAS</b></center><br><br>\n";
			
			if(sizeof($facturas) > 0)
			{
				$html  = "<br>\n";
				$html .= ClaseHTML::ObtenerPaginadoXajax($nc->conteo,$nc->paginaActual,$action);
				
				$html .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$html .= "						<tr class=\"formulacion_table_list\">\n";
				$html .= "							<td style=\"text-align:center\" width=\"10%\">FAC.</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"15%\">REGISTRO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"15%\">T. FACTURA</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"15%\">SALDO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"40%\">TERCERO</td>\n";
				$html .= "							<td style=\"text-align:center\" width=\"%\" ></td>\n";
				$html .= "						</tr>\n";
				
				$estilo='modulo_list_oscuro';
				foreach($facturas as $key => $detalle)
				{
					if($estilo == 'modulo_list_claro')
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					else
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					
					$html .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$html .= "							<td class=\"normal_10AN\">".$detalle['prefijo']." ".$detalle['factura_fiscal']."</td>\n";
					$html .= "							<td align=\"center\">".$detalle['fecha']."</td>\n";
					$html .= "							<td align=\"right\" >$".FormatoValor($detalle['total_factura'])."</td>\n";
					$html .= "							<td align=\"right\" >$".FormatoValor($detalle['saldo'])."</td>\n";
					$html .= "							<td >".$detalle['nombre_tercero']."</td>\n";
					$html .= "							<td width=\"$tx\">\n";
					$html .= "								<input type=\"radio\" name=\"sel_factura\" onclick=\"AdicionarFactura('".$detalle['prefijo']."','".$detalle['factura_fiscal']."')\">\n";
					$html .= "							</td>\n";
					$html .= "						</tr>\n";
				}
				$html .= "					</table><br>\n";
				$html .= ClaseHTML::ObtenerPaginadoXajax($nc->conteo,$nc->paginaActual,$action,true);
				$html .= "					<table align=\"center\">\n";
				$html .= "						<tr><td height=\"25\"><a href=\"javascript:OcultarSpan('FacturasB')\" class=\"label_error\">CERRAR</a></td></tr>\n";
				$html .= "					</table>\n";			
			}
			
			return $html;
		}
?>