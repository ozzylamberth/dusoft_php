<?php
	/***********************************************************************************
	* $Id: app_Cartera_userclasses_HTML.php,v 1.25 2007/08/09 19:44:11 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* Clase de la vista HTML del modulo de cartera
	*
	* @author Hugo Freddy Manrique
	* Codigo toamdo de otra version de Cartera
	* 		@author Carlos Henao
	* 		@author Ehudes Fernan Garcia <efgarcia@ipsoft-sa.com>
	* @package IPSOFT-SIIS-FI-CARTERA
	*************************************************************************************/ 
	IncludeClass("ClaseHTML");
 	class app_Cartera_userclasses_HTML extends app_Cartera_user
	{
		var $meses = array();
		function app_Cartera_userclasses_HTML()
		{	
			$this->meses['01'] = "ENERO";
			$this->meses['02'] = "FEBRERO";
			$this->meses['03'] = "MARZO";
			$this->meses['04'] = "ABRIL";
			$this->meses['05'] = "MAYO";
			$this->meses['06'] = "JUNIO";
			$this->meses['07'] = "JULIO";
			$this->meses['08'] = "AGOSTO";
			$this->meses['09'] = "SEPTIEMBRE";
			$this->meses['10'] = "OCTUBRE";
			$this->meses['11'] = "NOVIEMBRE";
			$this->meses['12'] = "DICIEMBRE";
		}
		/********************************************************************************
		* Muestra el menú principal de cartera.
		*
		* @access public
		*********************************************************************************/
		function FormaMostrarMenuPrincipalCartera()
		{
			$this->salida  = ThemeAbrirTabla('CARTERA - OPCIONES');
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENÚ</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action3."\"><b>CARTERA CLIENTES</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action4."\"><b>FACTURAS SIN ENVIAR</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action6."\"><b>CARTERA POR PLANES - ENVIADA</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action7."\"><b>CARTERA POR PLANES - NO ENVIADA</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action5."\"><b>MOVIMIENTOS DE CARTERA</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action8."\"><b>CONSULTAS DE CARTERA Y FACTURACION</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************
		* Funcion donde se realiza la forma donde se muestra la cartera de los clientes
		* y un buscador de clientes y rangos
		*********************************************************************************/
		function FormaMostrarCarteraClientes()
		{
			$this->salida  = ThemeAbrirTabla('CARTERA DE CLIENTES');
			$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td width=\"15%\" class=\"modulo_table_list_title\">EMPRESA:</td>\n";
			$this->salida .= "			<td width=\"%\" class=\"label\" style=\"text-indent:11pt\">".$this->RazonSocial."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"500\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			
			IncludeClass('CarteraHtml','','app','Cartera');
			$this->salida .= CarteraHtml::FormaBuscadorTerceros($this->request,$_SESSION['cartera']['Enviados'],$this->action2);

			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			
			$Clientes = $this->Arreglo; 
			if(sizeof($Clientes) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$this->salida .= "			<td class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center\" width=\"50%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"  width=\"50%\"><b>CORRIENTE</b></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";

				$reporte = new GetReports();
				$mostrar = $reporte->GetJavaReport('app','Cartera','carteraenviada',array("tercero"=>$this->request['nombre_tercero'],"ordenar_por"=>$this->request['ordenar_por'],"enviado"=>'1'),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion1 = $reporte->GetJavaFunction();
				
				$mostrar .= $reporte->GetJavaReport('app','Cartera','carteraresumida',array("tercero"=>$this->request['nombre_tercero'],"enviado"=>'1'),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion2 = $reporte->GetJavaFunction();
				
				$mostrar .= $reporte->GetJavaReport('app','Cartera','carteratipoentidad',array("enviado"=>'1'),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion3 = $reporte->GetJavaFunction();
				
				$this->salida .= $mostrar;
				$this->salida .= "  <table border=\"0\" align=\"center\" cellspacing=\"10\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "  			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE CARTERA\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">REPORTE CARTERA</a>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "  			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE RESUMIDO\">&nbsp;<a href=\"javascript:$funcion2\" class=\"label_error\">REPORTE RESUMIDO</a>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "  			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE RESUMIDO\">&nbsp;<a href=\"javascript:$funcion3\" class=\"label_error\">REPORTE TIPO ENTIDAD</a>\n";
				$this->salida .= "			</td>\n";				
				$this->salida .= "		</tr>\n";
				$this->salida .= "  </table>\n";

				$estilo1 = "class=\"hc_table_submodulo_title\" style=\"text-align:center;font-size:10px;text-indent: 0pt\"";
				$estilo2 = "class=\"modulo_table_title\" style=\"text-align:center;text-indent: 0pt\" ";
				
				$this->salida .= "	<table border=\"0\" width=\"53%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
			
				for($i = 0; $i< 15; $i++)
				{
					if($this->Intervalos[$i] != "")
					{
						if($i > 0 )
						{
							$es = $estilo1;
							$this->salida .= "			<td width=\"%\" $estilo1 colspan=\"3\">".$this->Intervalos[$i]."</td>\n";
						}
						else
							{
								$es = " class=\"modulo_table_list_title\" ";
								$this->salida .= "			<td width=\"%\" colspan=\"3\" class=\"modulo_table_list_title\">".$this->Intervalos[$i]."</td>\n";
							}
					
						$columnas .= "			<td $es>SALDO</td>\n";
						$columnas .= "			<td $es>PENDI</td>\n";
						$columnas .= "			<td $es>DIFER</td>\n";
					}
				}
			
				$this->salida .= "			<td colspan=\"3\" class=\"modulo_table_list_title\">ANTICIPOS(-)</td>\n";
				$this->salida .= "			<td rowspan=\"2\" class=\"modulo_table_list_title\">PORCENT</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">CLIENTE</td>\n";
				$this->salida .= "		".$columnas;
				$this->salida .= "			<td class=\"modulo_table_list_title\">SALDO</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">PENDI</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">DIFER</td>\n";
				$this->salida .= "		</tr>\n";
				
				$saldoT = array();
				$pendienteT = array();
				$diferenciaT = array();
				$vanticipos = 0;
				
				$longitud = sizeof($Clientes);
				//for ($i=0; $i<$longitud; $i++)
				$rqst['datos_cliente']['periodo'] = $this->request['periodo'];
				$rqst['datos_cliente']['nombre_tercero'] = $this->request['nombre_tercero'];
				$rqst['datos_cliente']['ordenar_por'] = $this->request['ordenar_por'];
				
				$i =0;
				foreach($Clientes as $key => $cartera)
				{
					if($cartera['empresa'] != "")
					{
						$adicional = "";
						$saldo = 0;
						$pendiente = 0;
						$diferencia = 0;
						
						$rqst['datos_cliente']['cliente_id'] = $cartera['id'];
						$rqst['datos_cliente']['cliente'] = $cartera['empresa'];
						
						$this->salida .= "		<tr height=\"23\">\n";
						$this->salida .= "			<td rowspan=\"2\" class=\"modulo_list_claro\" width=\"150\" >";
						$this->salida .= "				<a href=\"".$this->action2['envios'].URLRequest($rqst)."\">";
						$this->salida .= $cartera['empresa'];
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
						
						for($j = 0; $j<15; $j++)
						{
							if($this->Intervalos[$j] != "")
							{
								($j == 0)? $es = " class=\"modulo_list_claro\" ":$es = " class=\"modulo_list_oscuro\" ";
								
								$difer = $cartera['periodos'][$j]['saldo'] -$cartera['periodos'][$j]['valor_pendiente'];
								$saldo1 = $cartera['periodos'][$j]['saldo'];
								$pendiente1 = $cartera['periodos'][$j]['valor_pendiente'];
								
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($saldo1)."</td>\n";
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($pendiente1)."</td>\n";
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($difer)."</td>\n";
								
								$saldoT[$j] += $saldo1;
								$pendienteT[$j] += $pendiente1;
								$diferenciaT[$j] += $difer;
								
								$saldo += $saldo1;
								$pendiente += $pendiente1;
								$diferencia += $difer;
								
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($saldo)."</b></td>\n";
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($pendiente)."</b></td>\n";
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($diferencia)."</b></td>\n";
							}
						}
						
						$this->salida .= "			<td align=\"right\" class=\"modulo_list_claro\"><b style=\"color:#750000\">".FormatoValor($this->anticipos[$cartera['id']]['saldo'])."</b></td>\n";
						$this->salida .= "			<td align=\"right\" class=\"modulo_list_claro\"><label class=\"normal_10AN\">0</label></td>\n";
						$this->salida .= "			<td align=\"right\" class=\"modulo_list_claro\"><label class=\"normal_10AN\">0</label></td>\n";
						
						$porcentaje = (($cartera['saldo']-$this->anticipos[$cartera['id']]['saldo'])/$this->TotalCartera)*100;
						$vanticipos += $this->anticipos[$cartera['id']]['saldo'];
						
						$sa = $saldo - $this->anticipos[$cartera['id']]['saldo'];
						$dif = $sa - $pendiente;
						
						$this->salida .= "			<td rowspan=\"2\" align=\"right\" class=\"modulo_table_list_title\">".number_format($porcentaje,2,',','.')."%</td>";
						$this->salida .= "		</tr>\n";	
						$this->salida .= "		<tr height=\"23\">\n";
						$this->salida .= "		".$adicional;	
						$this->salida .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($sa)."</b></td>\n";
						$this->salida .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($pendiente)."</b></td>\n";
						$this->salida .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($dif)."</b></td>\n";
						
						$this->salida .= "		</tr>\n";
					}
					$i++;
				}
				$this->salida .= "		<tr height=\"23\">\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">TOTALES</td>\n";
					
				$saldo = $pendiente = $diferencia = 0;	
				for($j = 0; $j<15; $j++)
				{
					if($this->Intervalos[$j] != "")
					{
						$es = "class=\"modulo_table_list_title\" style=\"text-align:right;\" ";
							
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($saldoT[$j])."</td>\n";
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($pendienteT[$j])."</td>\n";
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($diferenciaT[$j])."</td>\n";
	
						$saldo += $saldoT[$j];
						$pendiente += $pendienteT[$j];
						$diferencia += $diferenciaT[$j];
							
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($saldo)."</td>\n";
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($pendiente)."</td>\n";
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($diferencia)."</td>\n";
						
					}		
				}
				$this->salida .= "			<td align=\"right\" $es>".formatoValor($vanticipos)."</td>\n";
				$this->salida .= "			<td align=\"right\" $es>0</td>\n";
				$this->salida .= "			<td align=\"right\" $es>0</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
				$this->salida .= "		</tr>\n";
				
				$this->salida .= "		<tr height=\"23\">\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">ACUMULADO</td>\n";
				$this->salida .= "			".$acumulado;
				$this->salida .= "			<td align=\"right\" $es>".formatoValor($saldo-$vanticipos)."</td>\n";
				$this->salida .= "			<td align=\"right\" $es>".formatoValor($pendiente)."</td>\n";
				$this->salida .= "			<td align=\"right\" $es>".formatoValor($saldo-$vanticipos-$pendiente)."</td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
			}
			else
			{
				$this->salida .= "			<center><br><b class=\"label_error\">NO SE ENCONTRO NINGÚN CLIENTE PARA ESTA EMPRESA</b><br></center>\n";
			}
			
			$this->salida .= "	<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************
		* Funcion que realiza una forma para mostrar los clientes con facturas sin radicar,
		* aqui se evalua que si la cantidad  de registros excede una variable del modulo, 
		* se muestra solo los rangos y los totales de cada rango.
		*********************************************************************************/
		function FormaMostrarCarteraClienteSel()
		{
			$this->salida  = ThemeAbrirTabla('CARTERA DEL CLIENTE '.$this->NombreCliente);
			$Facturas = $this->ConsultarFacturasClienteNoEnviadas();
			if(sizeof($Facturas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"87%\" align=\"center\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$this->salida .= "			<td class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center\" width=\"33%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"  width=\"34%\"><b>FACTURAS DE ESTE MES</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_title\" style=\"text-align:center\" width=\"33%\"><b>FACTURAS POR VENCER</b></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				
				$nombre = "";
				$saldo = $glosa = $aceptado = $pendiente = $noaceptado = $total = $recibos = $notas = 0;
				/*******************************************************************************************
				* Se evalua si la cantidad de registros encontrados excede el total de registros permitidos
				********************************************************************************************/
				if($this->conteo <= $this->Registros)
				{
					$this->IncludeJS("CrossBrowser");
					$this->IncludeJS("CrossBrowserEvent");
					$this->IncludeJS("CrossBrowserDrag");
					$this->SetXajax(array("InformacionFactura","InformacionCuenta","InformacionGlosa","InformacionGlosaDetalle","InformacionFacturaExterna"),"app_modules/Cartera/RemoteXajax/DetalleFacturas.php");

					$this->salida .= "<script language=\"javascript\">\n";
					$this->salida .= "	function mOvr(src,clrOver)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		src.style.background = clrOver;\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function mOut(src,clrIn)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		src.style.background = clrIn;\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalleFactura(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionFactura(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarInformacionFacturaExterna(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionFacturaExterna(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarInformacionCuenta(cuenta,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionCuenta(cuenta,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalleGlosa(prefijo,factura,empresa,sistema)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionGlosa(prefijo,factura,empresa,sistema);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarGlosa(glosa,empresa,sistema)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionGlosaDetalle(empresa,glosa,sistema);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalle()\n";
					$this->salida .= "	{\n";
					$this->salida .= "		Iniciar();\n";
					$this->salida .= "		MostrarSpan('Facturas');\n";
					$this->salida .= "	}\n";
					$this->salida .= "</script>\n";
					IncludeClass('CarteraDetalle','','app','Cartera');
					IncludeClass('CarteraFacturacionHTML','','app','Cartera');
					$this->salida .= CarteraFacturacionHTML::VentanaDetalle();
				
					$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
					$this->salida .= "			<td width=\"9%\">PERIODO</td>\n";
					$this->salida .= "			<td width=\"9%\">FACTURA</td>\n";
					$this->salida .= "			<td width=\"9%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"9%\">SALDO</td>\n";
					$this->salida .= "			<td width=\"9%\">V. GLOSA</td>\n";
					$this->salida .= "			<td width=\"9%\">V. ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"9%\">V. NO ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"9%\">V. PENDIENTE</td>\n";
					$this->salida .= "			<td width=\"9%\">V. RECIBOS</td>\n";
					$this->salida .= "			<td width=\"9%\">V. NOTAS DE AJUSTE</td>\n";
					$this->salida .= "			<td width=\"10%\" colspan=\"2\" width=\"%\">OPCIONES</td>\n";
					$this->salida .= "		</tr>\n";
					$j = 0;
					for($i= 0; $i<sizeof($Facturas); $i++)
					{
						$Celdas = $Facturas[$i];
						
						$es = " class=\"modulo_list_oscuro\" ";
						if($Celdas['diferencia'] == 0)
						{
							$es = " class=\"modulo_list_claro\" ";
							$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 7pt\" ";
						}
						else
						{
							if($Celdas['diferencia'] < 0)
								$estilo = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 7pt\"";
							else
								$estilo = "class=\"modulo_table_title\" style=\"text-indent: 7pt\" ";
						}
						
						$total += $Celdas['total'];
						$saldo += $Celdas['saldo'];
						$glosa += $Celdas['valor_glosa'];
						$notas += $Celdas['valor_abonado_na'];
						$recibos += $Celdas['valor_abonado_rc'];
						$aceptado += $Celdas['valor_aceptado'];
						$pendiente += $Celdas['valor_pendiente'];
						$noaceptado += $Celdas['valor_no_aceptado'];
						
						$this->salida .= "		<tr $es height=\"18\">\n";
						if($nombre != $Celdas['nombre'])
						{
							$row = sizeof($this->Facturas2[$Celdas['nombre']])+1;
							$this->salida .= "			<td $estilo rowspan= \"".$row."\"><b>".$Celdas['nombre']."</b></td>\n";
						}
						
						$nombre = $Celdas['nombre'];
						
						$this->salida .= "			<td >&nbsp;".$Celdas['prefijo']." ".$Celdas['factura_fiscal']."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['total'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['saldo'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_glosa'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_aceptado'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_no_aceptado'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_pendiente'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_abonado_rc'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_abonado_na'])."</td>\n";
						$this->salida .= "			<td align=\"center\" width=\"5%\">\n";
						$this->salida .= "				<a href=\"javascript:MostrarDetalleFactura('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td align=\"center\" width=\"5%\" >\n";
						if($Celdas['valor_glosa'] > 0)
						{
							$this->salida .= "				<a href=\"javascript:MostrarDetalleGlosa('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."','SIIS')\" title=\"INFORMACIÓN GLOSAS FACTURA\">\n";
							$this->salida .= "					<img src=\"".GetThemePath()."/images/Listado.png\" border=\"0\">\n";
							$this->salida .= "				</a>\n";
						}
						$this->salida .= "			</td>\n";	
						$this->salida .= "		</tr>\n";
						
						$j++;
						if($j == sizeof($this->Facturas2[$Celdas['nombre']]))
						{
							$est = " class=\"tabla_menu\" ";
							
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td $est >&nbsp;</td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($total)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($saldo)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($glosa)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($aceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($noaceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($pendiente)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($recibos)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($notas)."</b></td>\n";
							$this->salida .= "			<td $est colspan=\"2\"></td>\n";
							$this->salida .= "		</tr>\n";
							
							$totalT += $total;
							$saldoT += $saldo;
							$glosaT += $glosa;
							$notasT += $notas;
							$recibosT +=  $recibos;
							$aceptadoT += $aceptado;
							$pendienteT +=  $pendiente;
							$noaceptadoT += $noaceptado;

							
							$j = $saldo = $glosa = $aceptado = $pendiente = $noaceptado = $total = $recibos = $notas = 0;
						}	
					}
					
					$Pglosa = number_format((($glosaT/$totalT)*100),3,',','.');
					$Pnotas = number_format((($notasT/$totalT)*100),3,',','.');
					$Psaldo = number_format((($saldoT/$totalT)*100),3,',','.');
					$Precibos = number_format((($recibosT/$totalT)*100),3,',','.');
					$Paceptado = number_format((($aceptadoT/$totalT)*100),3,',','.');
					$Ppendiente = number_format((($pendienteT/$totalT)*100),3,',','.');
					$Pnoaceptado = number_format((($noaceptadoT/$totalT)*100),3,',','.');
					
					$est = "style=\"text-align:left;font-size:10px;text-indent: 6pt\"";
					
					$this->salida .= "</table><br>";
					$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\" >\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\" >\n";
					$this->salida .= "			<td $est width=\"18%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($totalT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($saldoT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($glosaT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($aceptadoT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($noaceptadoT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($pendienteT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($recibosT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($notas)."</td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\"></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"23\" >\n";
					$this->salida .= "			<td $est>PORCENTAJES</td>\n";
					$this->salida .= "			<td align=\"right\"><b>100 %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Psaldo." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Pglosa." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Paceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Pnoaceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Ppendiente." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Precibos." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Pnotas." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"></td>\n";
					$this->salida .= "		</tr>\n";

					$this->salida .= "	</table><br>\n";
				}
				else
				{
					$this->salida .= "	<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
					$this->salida .= "			<td width=\"10%\">PERIODO</td>\n";
					$this->salida .= "			<td width=\"10%\">FACTURA</td>\n";
					$this->salida .= "			<td width=\"10%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"10%\">SALDO</td>\n";
					$this->salida .= "			<td width=\"10%\">V. GLOSA</td>\n";
					$this->salida .= "			<td width=\"10%\">V. ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"10%\">V. NO ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"10%\">V. PENDIENTE</td>\n";
					$this->salida .= "			<td width=\"10%\">V. RECIBOS</td>\n";
					$this->salida .= "			<td width=\"10%\">V. NOTAS DE AJUSTE</td>\n";
					$this->salida .= "		</tr>\n";
					$j = 0;
					for($i= 0; $i<sizeof($Facturas); $i++)
					{
						$Celdas = $Facturas[$i];
						
						if($Celdas['diferencia'] == 0)
						{
							$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 7pt\" ";
						}
						else
						{
							if($Celdas['diferencia'] < 0)
								$estilo = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 7pt\"";
							else
								$estilo = "class=\"modulo_table_title\" style=\"text-indent: 7pt\" ";
						}

						$total += $Celdas['total'];
						$saldo += $Celdas['saldo'];
						$glosa += $Celdas['valor_glosa'];
						$notas += $Celdas['valor_abonado_na'];
						$recibos += $Celdas['valor_abonado_rc'];
						$aceptado += $Celdas['valor_aceptado'];
						$pendiente += $Celdas['valor_pendiente'];
						$noaceptado += $Celdas['valor_no_aceptado'];
												
						if($nombre != $Celdas['nombre'])
						{
							$this->salida .= "		<tr height=\"18\">\n";
							$this->salida .= "			<td $estilo ><b>".$Celdas['nombre']."</b></td>\n";
						}
						
						$j++;
						$nombre = $Celdas['nombre'];
						$dif = $Celdas['diferencia'];
						if($j == sizeof($this->Facturas2[$Celdas['nombre']]))
						{
							$est = " class=\"modulo_list_claro\" ";
							$action3 = ModuloGetURL('app','Cartera','user','MostrarFacturasRango',
															   			 array("cliente_id"=>$_REQUEST['cliente_id'],"periodo"=>$_REQUEST['periodo'],"rango"=>$Celdas['nombre'],
																   			 	   "intervalo"=>$Celdas['intervalo'],"direccion"=>$Celdas['direccion'],"retorno1"=>$this->MetodoR,"diferencia"=>$dif));
										   			 
							$this->salida .= "			<td $est align=\"center\"><a href=\"".$action3."\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" title=\"VER FACTURAS\"></a></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($total)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($saldo)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($glosa)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($aceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($noaceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($pendiente)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($recibos)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($notas)." </b></td>\n";
							$this->salida .= "		</tr>\n";
							
							$totalT += $total;
							$saldoT += $saldo;
							$glosaT += $glosa;
							$notasT += $notas;
							$recibosT +=  $recibos;
							$aceptadoT += $aceptado;
							$pendienteT +=  $pendiente;
							$noaceptadoT += $noaceptado;
														
							$j = $saldo = $glosa = $aceptado = $pendiente = $noaceptado = $total = $notas = $recibos = 0;
						}
						
					}
					
					$Pglosa = ($glosaT/$totalT)*100;
					$Pnotas = ($notasT/$totalT)*100;
					$Psaldo = ($saldoT/$totalT)*100;
					$Precibos = ($recibosT/$totalT)*100;
					$Paceptado = ($aceptadoT/$totalT)*100;
					$Ppendiente = ($pendienteT/$totalT)*100;
					$Pnoaceptado = ($noaceptadoT/$totalT)*100;
					
					$Pglosa = number_format($Pglosa,3,',','.');
					$Pnotas = number_format($Pnotas,3,',','.');
					$Psaldo = number_format($Psaldo,3,',','.');  
					$Precibos = number_format($Precibos,3,',','.');
					$Paceptado = number_format($Paceptado,3,',','.');
					$Ppendiente = number_format($Ppendiente,3,',','.');
					$Pnoaceptado = number_format($Pnoaceptado,3,',','.');
										
					$this->salida .= "</table><br>";
					$this->salida .= "	<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
					$this->salida .= "			<td width=\"20%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($totalT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($saldoT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($glosaT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($aceptadoT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($noaceptadoT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($pendienteT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($recibosT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($notasT)."</b></td>\n";					
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
					$this->salida .= "			<td >PORCENTAJES</td>\n";
					$this->salida .= "			<td align=\"right\" ><b>100 %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Psaldo." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Pglosa." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Paceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Pnoaceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Ppendiente." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Precibos." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Pnotas." %</b></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "	</table><br>\n";
				}
			}
			
			$this->salida .= "<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();		
		}
		/********************************************************************************
		* Funcion donde se construye la forma donde se muestran las facturas que 
		* pertenecen a un rango seleccionado
		*********************************************************************************/
		function FormaMostrarFacturasRango()
		{
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->SetXajax(array("InformacionFactura","InformacionCuenta","InformacionGlosa","InformacionGlosaDetalle","InformacionFacturaExterna"),"app_modules/Cartera/RemoteXajax/DetalleFacturas.php");

			IncludeClass('CarteraFacturacionHTML','','app','Cartera');
			$chtml = new CarteraFacturacionHTML();
			$this->salida = $chtml->FormaMostrarFacturasPorRango($this->Facturas[$this->datos['rango']],$this->datos,$this->Totales,$this->action);
			
		}
		/********************************************************************************
		* 
		*********************************************************************************/
		function FormaMostrarCarteraClientesNoEnviada()
		{
			$this->salida  = ThemeAbrirTabla('CARTERA NO ENVIADA');
			$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td width=\"15%\" class=\"modulo_table_list_title\">EMPRESA:</td>\n";
			$this->salida .= "			<td width=\"%\" class=\"label\" style=\"text-indent:11pt\">".$this->RazonSocial."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "	<table width=\"500\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			IncludeClass('CarteraHtml','','app','Cartera');
			$this->salida .= CarteraHtml::FormaBuscadorTerceros($this->request,$_SESSION['cartera']['NoEnviados'],$this->action2);

			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			
			$Clientes = $this->Arreglo;
			
			if(sizeof($Clientes) > 0)
			{
				$estilo1 = "class=\"hc_table_submodulo_title\" style=\"text-align:center;font-size:10px;text-indent: 0pt\"";
				$estilo2 = "class=\"modulo_table_title\" style=\"text-align:center;text-indent: 0pt\" ";
				
				/*$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$this->salida .= "			<td class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center\" width=\"33%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"  width=\"34%\"><b>FACTURAS DE ESTE MES</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_title\" style=\"text-align:center\" width=\"33%\"><b>FACTURAS POR VENCER</b></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";*/
				
				$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$this->salida .= "			<td class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center\" width=\"50%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"  width=\"50%\"><b>CORRIENTE</b></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				
				$reporte = new GetReports();
				$mostrar = $reporte->GetJavaReport('app','Cartera','carteranoenviada',array("tercero"=>$this->Cliente,"ordenar_por"=>$this->request['ordenar_por']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion1 = $reporte->GetJavaFunction();				
				
				$mostrar .= $reporte->GetJavaReport('app','Cartera','carteraresumida',array("tercero"=>$this->Cliente,"enviado"=>'0'),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion2 = $reporte->GetJavaFunction();
				
				$mostrar .= $reporte->GetJavaReport('app','Cartera','carteratipoentidad',array("enviado"=>'0'),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion3 = $reporte->GetJavaFunction();
				
				$this->salida .= $mostrar;
				$this->salida .= "  <table border=\"0\" align=\"center\" cellspacing=\"10\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "  			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">REPORTE CARTERA</a>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "  			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE RESUMIDO\">&nbsp;<a href=\"javascript:$funcion2\" class=\"label_error\">REPORTE RESUMIDO</a>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "  			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE RESUMIDO\">&nbsp;<a href=\"javascript:$funcion3\" class=\"label_error\">REPORTE TIPO ENTIDAD</a>\n";
				$this->salida .= "			</td>\n";				
				$this->salida .= "		</tr>\n";
				$this->salida .= "  </table>\n";
				$this->salida .= "	<table border=\"0\" width=\"53%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
				for($i = 0; $i< 15; $i++)
				{
					if($this->Intervalos[$i] != "")
					{
						if($i > 0 )
						{
							$es = $estilo1;
							$this->salida .= "			<td width=\"%\" $estilo1 colspan=\"3\">".$this->Intervalos[$i]."</td>\n";
						}
						else if($i == 0)
							{
								$es = " class=\"modulo_table_list_title\" ";
								$this->salida .= "			<td width=\"%\" colspan=\"3\" class=\"modulo_table_list_title\">".$this->Intervalos[$i]."</td>\n";
							}
						$columna .= "			<td $es>SALDO</td>\n";
						$columna .= "			<td $es>PENDI</td>\n";
						$columna .= "			<td $es>DIFER</td>\n";
					}
				}
				
				$this->salida .= "			<td rowspan=\"2\" class=\"modulo_table_list_title\">PORCENT</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">CLIENTE</td>\n";
				$this->salida .= "			".$columna;
				$this->salida .= "		</tr>\n";
				
				$saldoT = array();
				$pendienteT = array();
				$diferenciaT = array();
				$i=0;
				
				foreach($Clientes as $key => $cartera)
				{
					if($cartera['empresa'] != "")
					{	
						$action11 = ModuloGetURL('app','Cartera','user','MostrarCarteraClienteNOEnviadaSel',
												 							array("cliente_id"=>$cartera['id'],"periodo"=>$this->PeriodoSeleccionado,
												 	   								"nombre_tercero"=>$this->ClienteNombre,"cliente"=>$cartera['empresa']));
												 	   
						$this->salida .= "		<tr height=\"23\">\n";
						$this->salida .= "			<td rowspan=\"2\" class=\"modulo_list_claro\" width=\"150\" >";
						$this->salida .= "				<a href=\"".$action11."\">".$cartera['empresa']."</a>\n";
						$this->salida .= "			</td>\n";
						
						$adicional = "";
						$saldo = 0;
						$pendiente = 0;
						$diferencia = 0;
						
						for($j = 0; $j<15; $j++)
						{
							if($this->Intervalos[$j] != "")
							{			
								($j == 7)? $es = " class=\"modulo_list_claro\" ":$es = " class=\"modulo_list_oscuro\" ";
								
								$difer = $cartera['periodos'][$j]['saldo'] - $cartera['periodos'][$j]['valor_pendiente'];
								$saldoT[$j] += $cartera['periodos'][$j]['saldo'];
								$pendienteT[$j] += $cartera['periodos'][$j]['valor_pendiente'];
								$diferenciaT[$j] += $difer;
								
								$saldo += $cartera['periodos'][$j]['saldo'];
								$pendiente += $cartera['periodos'][$j]['valor_pendiente'];
								$diferencia += $difer;
								
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($saldo)."</b></td>\n";
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($pendiente)."</b></td>\n";
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($diferencia)."</b></td>\n";
	
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($cartera['periodos'][$j]['saldo'])."</td>\n";
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($cartera['periodos'][$j]['valor_pendiente'])."</td>\n";
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($difer)."</td>\n";														
							}
						}
						
						$porcentaje = ($cartera['saldo']/$this->TotalCartera)*100;
						$this->salida .= "			<td rowspan=\"2\" align=\"right\" class=\"modulo_table_list_title\">".number_format($porcentaje,2,',','.')."%</td>";
						$this->salida .= "		</tr>\n";	
						$this->salida .= "		<tr height=\"23\">\n";
						$this->salida .= "		".$adicional;					
						$this->salida .= "		</tr>\n";
					}
					$i++;
				}
		
				$this->salida .= "		<tr height=\"23\">\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">TOTALES</td>\n";
				
				$saldo = $pendiente = $diferencia = 0;	
				
				for($j = 0; $j<15; $j++)
				{
					if($this->Intervalos[$j] != "")
					{
						$es = "class=\"modulo_table_list_title\" style=\"text-align:right;\" ";
							
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($saldoT[$j])."</td>\n";
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($pendienteT[$j])."</td>\n";
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($diferenciaT[$j])."</td>\n";
						
						$saldo += $saldoT[$j];
						$pendiente += $pendienteT[$j];
						$diferencia += $diferenciaT[$j];
							
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($saldo)."</td>\n";
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($pendiente)."</td>\n";
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($diferencia)."</td>\n";
					}		
				}
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr height=\"23\">\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">ACUMULADO</td>\n";
				$this->salida .= "			".$acumulado;	
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";				
			}
			else
			{
				$this->salida .= "			<center><br><b class=\"label_error\">NO SE ENCONTRO NINGÚN CLIENTE PARA ESTA EMPRESA</b><br></center>\n";
			}
			
			$this->salida .= "	<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
		}
		/**********************************************************************************
		* Funcion donde se muestra la forma que permite crear las notas de ajuste
		* 
		* @return boolean
		***********************************************************************************/
		function FormaSubMenuMovimientos()
		{
			$this->salida  = ThemeAbrirTabla('MOVIMIENTOS DE CARTERA');
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MOVIMIENTOS</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action2."\"><b>BUSCAR FACTURAS</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_claro\" ></td></tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action3."\"><b>BUSCAR RECIBOS CAJA</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action4."\"><b>BUSCAR NOTAS DE AJUSTE</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action5."\"><b>MOSTRAR TODAS LAS FACTURAS</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			
			$this->salida .= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();

			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FormaMostrarInformacionRecibosCaja()
		{
			IncludeClass('CarteraHtml','','app','Cartera');
			$cth = new CarteraHtml();
			
			$this->salida .= ThemeAbrirTabla("CONSULTAR CLIENTES - RECIBOS CAJA");
			$this->salida .= "		<script language=\"javascript\">\n";
			$this->salida .= "			function mOvr(src,clrOver)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				src.style.background = clrOver;\n";
			$this->salida .= "			}\n";
			$this->salida .= "			function mOut(src,clrIn)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				src.style.background = clrIn;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		</script>\n";
			$this->salida .= $cth->FormaBuscadorTercerosRecibos($this->request,$this->actionL);
			
			$Clientes = $this->ObtenerClientesRecibos();
				
			if(sizeof($Clientes) > 0)
			{
				$this->salida .= "	<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "			<tr class=\"modulo_table_list_title\" height=\"19\">\n";
				$this->salida .= "				<td width=\"20%\">DOCUMENTO</b></td>\n";
				$this->salida .= "				<td width=\"%\"><b>NOMBRE CLIENTE</b></td>\n";
				$this->salida .= "				<td width=\"20%\"><b>OPCIONES</b></td>\n";
				$this->salida .= "			</tr>";
				for($i=0; $i< sizeof($Clientes); $i++ )
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$Celdas = $Clientes[$i];
					
					$accion = ModuloGetURL('app','Cartera','user','MostrarRecibosCaja',
																	array("pagina"=>$this->paginaActual,"tercero_id"=>$Celdas['tercero_id'],
																				"tipo_id_tercero"=>$Celdas['tipo_id_tercero'],"tercero_nombre"=>$Celdas['nombre_tercero']));
					
					$opcion  = "	<a class=\"label_error\" href=\"".$accion."\" title=\"VER RECIBOS DE CAJA CERRADOS\">\n";
					$opcion .= "	<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">";
					$opcion .= "	<b>VER RECIBOS</b></a>\n";
										
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"left\"   >".$Celdas['tipo_id_tercero']." ".$Celdas['tercero_id']."</td>\n";
					$this->salida .= "				<td align=\"justify\">".$Celdas['nombre_tercero']."</td>\n";
					$this->salida .= "				<td align=\"center\" >".$opcion."</td>\n";						
					$this->salida .= "			</tr>\n";
				}
				$this->salida .= "	</table><br>\n";
									
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionL['paginador']);
				$this->salida .= "		<br>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
			}
	
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;		
		}
		/*************************************************************************************
		* Funcion donde se realiza la forma de generar un recibo de caja y de ver los recibos 
		* en estado pendiente 
		**************************************************************************************/
		function FormaMostrarRecibosCaja()
		{
			$this->MostrarRecibosCaja();
			
			$this->salida .= ThemeAbrirTabla("CONSULTA DE RECIBOS DE CAJA");
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<center>\n";
			$this->salida .= "	<div class=\"label_error\">".$this->frmError["MensajeError"]."</div>\n";
			$this->salida .= "</center>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptDate(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Asignar(frm,valor)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		if(valor != '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			trcr = valor.split('*');\n";
			$this->salida .= "			frm.tercero_id.value = trcr[0];\n";
			$this->salida .= "			frm.tipo_id_tercero.value = trcr[1];\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function LimpiarCampos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			switch(frm[i].type)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 'text': frm[i].value = ''; break;\n";
			$this->salida .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		Asignar(frm,'');\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<center>\n";
			$this->salida .= "<form name=\"buscador\" action=\"".$this->action[2]."\" method=\"post\">\n";
			$this->salida .= "	<fieldset style=\"width:50%\" class=\"fieldset\"><legend>BUSCADOR AVANZADO</legend>\n";
			$this->salida .= "		<table width=\"100%\" align=\"50%\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">RECIBO Nº</td>\n";
			$this->salida .= "				<td colspan=\"2\">\n";
			$this->salida .= "					<select name=\"prefijo\" class=\"select\">\n";
			$this->salida .= "						<option value=\"\">--</option>\n";
			$sel = "";
			foreach($this->Prefijos as $key => $dtl)
			{
				($this->request['prefijo'] == $dtl['prefijo'] )? $sel = "selected": $sel = "";
				
				$this->salida .= "						<option value=\"".$dtl['prefijo']."\" $sel >".$dtl['prefijo']."</option>\n";
			}
			$this->salida .= "					</select>\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"numero\" size=\"12\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$this->request['numero']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">TERCERO</td>\n";
			$this->salida .= "				<td colspan=\"2\">\n";
			$this->salida .= "					<select name=\"tercero\" class=\"select\" onchange=\"Asignar(document.buscador,this.value)\">\n";
			$this->salida .= "						<option value=\"\">---- SELECCIONAR ----</option>\n";
			$sel = "";
			foreach($this->terceros as $key => $dtl)
			{
				($this->request['tercero'] == $dtl['tercero_id']."*".$dtl['tipo_id_tercero'] )? $sel = "selected": $sel = "";
				
				$this->salida .= "						<option value=\"".$dtl['tercero_id']."*".$dtl['tipo_id_tercero']."\" $sel >".$dtl['nombre_tercero']."</option>\n";
			}
			$this->salida .= "					</select>\n";
			$this->salida .= "					<input type=\"hidden\" name=\"tipo_id_tercero\" value=\"".$this->request['tipo_id_tercero']."\">\n";
			$this->salida .= "					<input type=\"hidden\" name=\"tercero_id\" value=\"".$this->request['tercero_id']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">FECHA INICIO</td>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->request['fecha_inicio']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td class=\"label\">".ReturnOpenCalendario('buscador','fecha_inicio','/')."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">FECHA FIN</td>\n";			
			$this->salida .= "				<td>\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->request['fecha_fin']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td class=\"label\">".ReturnOpenCalendario('buscador','fecha_fin','/')."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\" align=\"center\" colspan=\"3\"><br>\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</fieldset>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "</center><br>\n";
						
			if(sizeof($this->Recibos) > 0)
			{
				if($this->request['fecha_inicio'] && $this->request['fecha_fin'])
				{
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','Cartera','reciboscaja',$this->datos,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = $reporte->GetJavaFunction();
					$this->salida .= "	".$mostrar."\n";
					$this->salida .= " 	<center>\n";
					$this->salida .= " 		<a href=\"javascript:$funcion\" class=\"label_error\">\n";
					$this->salida .= "			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
					$this->salida .= " 			<b>REPORTE DE RECIBOS\n";
					$this->salida .= "		</a>\n";
					$this->salida .= "	</center>\n";
				}
				
				$this->salida .= "	<table border=\"0\" width=\"100%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">RECIBOS DE CAJA CREADOS</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<td width=\"7%\" >Nº DOC</td>\n";
				$this->salida .= "							<td width=\"8%\" >FECHA</td>\n";
				$this->salida .= "							<td width=\"9%\" >V DOC</td>\n";
				$this->salida .= "							<td width=\"9%\" >V FINAL</td>\n";
				$this->salida .= "							<td width=\"25%\">FORMA PAGO</td>\n";
				$this->salida .= "							<td width=\"20%\">TERCERO</td>\n";
				$this->salida .= "							<td width=\"%\"  >RESPONSABLE</td>\n";
				$this->salida .= "							<td width=\"3%\" >OPC</td>\n";
				$this->salida .= "						</tr>\n";

				$background = "#CCCCCC";
				$estilo = 'modulo_list_oscuro'; 
				$i = 0;
				foreach($this->Recibos as $key => $detalle )
				{
					if($estilo == "modulo_list_oscuro" )
					{
						$estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					else
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','RecibosCaja','reciboscaja',$detalle,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = "ReciboCaja".$i.$reporte->GetJavaFunction();
					$mostrar = str_replace("function W","function ReciboCaja".$i."W",$mostrar);
					$i++;
					$this->salida .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
					$this->salida .= "							<td align=\"left\"  >".$detalle['prefijo']." ".$detalle['recibo_caja']."</td>\n";
					$this->salida .= "							<td align=\"center\">".$detalle['fecha_ingcaja']."</td>\n";
					$this->salida .= "							<td align=\"right\" >$".FormatoValor($detalle['total_abono'])."</td>\n";
					$this->salida .= "							<td align=\"right\" >$".FormatoValor($detalle['valor_final'])."</td>\n";
					$this->salida .= "							<td align=\"justify\"><menu><b class= \"label_mark\">".$detalle['forma_pago']."</b></menu></td>\n";
					$this->salida .= "							<td align=\"justify\">".$detalle['nombre_tercero']."</td>\n";
					$this->salida .= "							<td align=\"justify\">".$detalle['nombre']."</td>\n";
					$this->salida .= "							<td align=\"center\">\n";
					$this->salida .= "								".$mostrar."\n";
					$this->salida .= " 								<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"DETALLE DEL RECIBO DE CAJA - REPORTE\">\n";
					$this->salida .= "									<img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
					$this->salida .= " 								</a>\n";
					$this->salida .= "							</td>\n";
					$this->salida .= "						</tr>\n";
				}
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";

			}
			else
			{
					$this->salida .= "<br><center><b class=\"label_error\">LA BUSQUEDAD NO ARROJO NINGÚN RESULTADO</b></center><br><br>\n";
			}
			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action[3]);
			$this->salida .= "		<br>\n";

			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action[1]."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FormaMostrarDetalleReciboCaja()
		{
			$this->salida .= ThemeAbrirTabla("DETALLE DEL RECIBO DE CAJA Nº ".$this->Prefijo." ".$this->ReciboId);
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<table border=\"0\" width=\"55%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "	<tr class=\"formulacion_table_list\">\n";
			$this->salida .= "		<td colspan=\"2\" width=\"25%\">ID</td>\n";
			$this->salida .= "		<td >CLIENTE</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr class=\"normal_10AN\" align=\"center\">\n";
			$this->salida .= "		<td>".$this->request['tipo_id_tercero']."</td>\n";
			$this->salida .= "		<td>".$this->request['tercero_id']."</td>\n";
			$this->salida .= "		<td>".$this->request['tercero_nombre']."</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			$this->salida .= "	<center>\n";
			$this->salida .= "		<div class=\"label_error\">".$this->frmError["MensajeError"]."</div>\n";
			$this->salida .= "	</center>\n";
			
			$ConceptosV = $this->ObtenerValorConceptos();
			$Facturas = $this->ObtenerFacturasCruzadasRC();
			
			$this->salida .= "	<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">CONCEPTOS DEL RECIBO DE CAJA</legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\" height=\"16\">\n";
			$this->salida .= "							<td width=\"%\"><b>CONCEPTO</b></td>\n";
			$this->salida .= "							<td width=\"30%\"><b>DEPARTAMENTO</b></td>\n";
			$this->salida .= "							<td width=\"10%\"><b>DEBITO</b></td>\n";
			$this->salida .= "							<td width=\"10%\"><b>CREDITO</b></td>\n";
			$this->salida .= "						</tr>\n";				
			
			$c = $d = 0;
			$this->salida .= "						<tr height=\"19\">\n";
			$this->salida .= "							<td class=\"modulo_list_oscuro\" colspan=\"2\"><b>VALOR RECIBO</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".formatoValor($this->TotalRecibos)."</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
			$this->salida .= "						</tr>\n";
			
			$d += $this->TotalRecibos;
			
			if(sizeof($ConceptosV) > 0)
			{				

				for($i=0; $i<sizeof($ConceptosV); $i++)
				{
					$Celdas = $ConceptosV[$i];
					$credito = $debito = "0";
					switch($Celdas['naturaleza'])
					{
						case 'C':	$credito = formatoValor($Celdas['valor']); $c += $Celdas['valor']; break;
						case 'D':	$debito = formatoValor($Celdas['valor']);  $d += $Celdas['valor'];	break;
					}
					
					$this->salida .= "						<tr height=\"19\">\n";
					$this->salida .= "							<td class=\"modulo_list_oscuro\"><b>".$Celdas['descripcion']."</b></td>\n";
					$this->salida .= "							<td class=\"modulo_list_oscuro\"><b>".$Celdas['departamento']."</b></td>\n";
					$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".$debito."</b></td>\n";
					$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".$credito."</b></td>\n";
					$this->salida .= "						</tr>\n";
				}
			}
			
			$c += $this->TotalFactura;
			
			$this->salida .= "						<tr height=\"19\">\n";
			$this->salida .= "							<td class=\"modulo_list_oscuro\"  colspan=\"2\"><b>TOTAL ABONO FACTURAS</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".formatoValor($this->TotalFactura)."</b></td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"formulacion_table_list\" height=\"19\">\n";
			$this->salida .= "							<td colspan=\"2\" >TOTAL</td>\n";
			$this->salida .= "							<td align=\"right\"><b>".formatoValor($d)."</b></td>\n";
			$this->salida .= "							<td align=\"right\"><b>".formatoValor($c)."</b></td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			
			if(sizeof($Facturas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"75%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">FACTURAS DEL RECIBO DE CAJA</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<td width=\"16%\"><b>FACTURA</b></td>\n";
				$this->salida .= "							<td width=\"16%\"><b>FECHA</b></td>\n";
				$this->salida .= "							<td width=\"17%\"><b>TOTAL</b></td>\n";
				$this->salida .= "							<td width=\"17%\"><b>SALDO</b></td>\n";
				$this->salida .= "							<td width=\"17%\"><b>ABONO</b></td>\n";
				$this->salida .= "							<td width=\"17%\"><b>ESTADO</b></td>\n";
				$this->salida .= "						</tr>";
				
				for($i=0; $i<sizeof($Facturas); $i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
										
					($Facturas[$i]['estado'] == "1")? $estado = "CERRADA": $estado = "ABIERTA";	
					($this->Estado == '1')? $saldo = $Facturas[$i]['saldo']: $saldo = $Facturas[$i]['saldo'] - $Facturas[$i]['abono'];
					
					$this->salida .= "						<tr height=\"18\" class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";					
					$this->salida .= "							<td aling=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['factura_fiscal']."</td>\n";
					$this->salida .= "							<td align=\"center\">".$Facturas[$i]['registro']."</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($Facturas[$i]['total_factura'])."&nbsp;</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($saldo)."&nbsp;</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($Facturas[$i]['abono'])."&nbsp;</td>\n";
					$this->salida .= "							<td align=\"center\" ><b class=\"label_mark\">".$estado."</b></td>\n";
					$this->salida .= "						</tr>";
				}
				
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action3);
				$this->salida .= "		<br>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO FACTURAS CRUZADAS</b></center><br><br>\n";
			}
			
			$this->salida .= "	<table width=\"75%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();	
		}
		/*************************************************************************************
		* Funcion donde se realiza la forma de generar un recibo de caja y de ver los recibos 
		* en estado pendiente 
		**************************************************************************************/
		function FormaInformacionNotasAjuste()
		{
			$this->InformacionNotasAjuste();
			
			$this->salida .= ThemeAbrirTabla("CONSULTA DE NOTAS DE AJUSTE");
			$this->salida .= "<script>\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptDate(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 45);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function LimpiarCampos(frm)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			switch(frm[i].type)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 'text': frm[i].value = ''; break;\n";
			$this->salida .= "				case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<center>\n";
			$this->salida .= "<form name=\"buscador\" action=\"".$this->action[2]."\" method=\"post\">\n";
			$this->salida .= "	<fieldset style=\"width:50%\" class=\"fieldset\"><legend>BUSCADOR AVANZADO</legend>\n";
			$this->salida .= "		<table width=\"100%\" align=\"50%\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">NOTA Nº</td>\n";
			$this->salida .= "				<td colspan=\"2\">\n";
			$this->salida .= "					<select name=\"prefijo\" class=\"select\">\n";
			$this->salida .= "						<option value=\"\">--</option>\n";
			$sel = "";
			foreach($this->Prefijos as $key => $dtl)
			{
				($this->request['prefijo'] == $key )? $sel = "selected": $sel = "";
				
				$this->salida .= "						<option value=\"".$key."\" $sel >".$key."</option>\n";
			}
			$this->salida .= "					</select>\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"numero\" size=\"12\" maxlength=\"32\" onkeypress=\"return acceptNum(event)\" value=\"".$this->request['numero']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">FECHA INICIO</td>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_inicio\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->request['fecha_inicio']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td class=\"label\">".ReturnOpenCalendario('buscador','fecha_inicio','/')."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">FECHA FIN</td>\n";			
			$this->salida .= "				<td>\n";
			$this->salida .= "					<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->request['fecha_fin']."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td class=\"label\">".ReturnOpenCalendario('buscador','fecha_fin','/')."</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\">TIPO NOTA</td>\n";
			$this->salida .= "				<td colspan=\"2\">\n";
			
			$na = $nc = $nd = $ng = "";
			switch($this->request['tipo_nota'])
			{
				case 'NA':	$na = 'selected'; break;
				case 'NC':	$nc = 'selected'; break;
				case 'ND':	$nd = 'selected'; break;
				case 'NG':	$ng = 'selected'; break;
			}
			$this->salida .= "					<select name=\"tipo_nota\" class=\"select\">\n";
			$this->salida .= "						<option value=\"\">--TODAS--</option>\n";
			$this->salida .= "						<option value=\"NA\" $na>NOTAS DE AJUSTE</option>\n";
			$this->salida .= "						<option value=\"NC\" $nc>NOTAS CREDITO</option>\n";
			$this->salida .= "						<option value=\"NG\" $ng>NOTAS CREDITO GLOSAS</option>\n";
			$this->salida .= "						<option value=\"ND\" $nd>NOTAS DEBITO</option>\n";
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"label\" align=\"center\" colspan=\"3\"><br>\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "					<input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.buscador)\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</fieldset>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "</center><br>\n";
					
			if(sizeof($this->Notas) > 0)
			{
				if($this->request['tipo_nota'] && $this->request['fecha_inicio'] && $this->request['fecha_fin'])
				{
					$reporte = new GetReports();
					$mostrar = $reporte->GetJavaReport('app','Cartera','gruponotas',$this->datos,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
					$funcion = $reporte->GetJavaFunction();
					$this->salida .= "	".$mostrar."\n";
					$this->salida .= " 	<center>\n";
					$this->salida .= " 		<a href=\"javascript:$funcion\" class=\"label_error\">\n";
					$this->salida .= "			<img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
					$this->salida .= " 			<b>REPORTE DE NOTAS\n";
					$this->salida .= "		</a>\n";
					$this->salida .= "	</center>\n";
				}
				
				$this->salida .= "	<table border=\"0\" width=\"90%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">NOTAS CREADAS:</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<td width=\"8%\" >Nº NOTA</td>\n";
				$this->salida .= "							<td width=\"9%\">VALOR</td>\n";
				$this->salida .= "							<td width=\"9%\">REGISTRO</td>\n";
				$this->salida .= "							<td width=\"15%\">TIPO</td>\n";
				$this->salida .= "							<td width=\"%\"  >RESPONSABLE</td>\n";
				$this->salida .= "							<td width=\"15%\">OPCIONES</td>\n";
				$this->salida .= "						</tr>\n";
				
				$i = 0;
				$background = "#CCCCCC";
				$estilo = 'modulo_list_oscuro'; 
				
				foreach($this->Notas as $key => $notas )
				{
					foreach ($notas as $keyI => $detalle)
					{
						if($estilo == "modulo_list_oscuro" )
						{
							$estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
						else
						{
							$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						
						$dts = array();
						$reporte = new GetReports();
						switch($detalle['abrv'])
						{
							case 'NA':
								$dts = $detalle;
								$dts['empresa'] = $this->Empresa;
								$mostrar = $reporte->GetJavaReport('app','Cartera','notasajuste',$dts,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							break;
							case 'NC':
								$dts = $detalle;
								$dts['empresa'] = $this->Empresa;
								$dts['numero_nota'] = $detalle['nota_credito_ajuste'];
								
								$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notascredito',$dts,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							break;
							case 'ND':
								$dts = $detalle;
								$dts['empresa'] = $this->Empresa;
								$dts['numero_nota'] = $detalle['nota_credito_ajuste'];
								
								$mostrar = $reporte->GetJavaReport('app','FacturacionNotaCreditoAjuste','notasdebito',$dts,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							break;
							case 'NG':
								$dts = $detalle;
								$dts['empresa'] = $this->Empresa;
								$mostrar = $reporte->GetJavaReport('app','Cartera','notacreditoglosa',$dts,array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
							break;
						}
						$funcion = "NotaAjuste$i".$reporte->GetJavaFunction();
						$mostrar = str_replace("function W","function NotaAjuste".($i++)."W",$mostrar);
					
						$this->salida .= "						<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
						$this->salida .= "							<td align=\"left\" class=\"label\">".$detalle['prefijo_nota']." ".$detalle['nota_credito_ajuste']."</td>\n";
						$this->salida .= "							<td align=\"right\" class=\"label\">$".FormatoValor($detalle['abono'])."</td>\n";
						$this->salida .= "							<td align=\"center\">".$detalle['fecha_registro']."</td>\n";
						$this->salida .= "							<td align=\"center\"><b class=\"label_mark\">".$detalle['tipo']."</b></td>\n";
						$this->salida .= "							<td align=\"left\"  >".$detalle['nombre']."</td>\n";
						$this->salida .= "							<td align=\"center\">\n";
						$this->salida .= "								".$mostrar."\n";
						$this->salida .= " 								<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"DETALLE DEL RECIBO DE CAJA - REPORTE\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>\n";
						$this->salida .= " 								<b>VER REPORTE</b></a>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "						</tr>\n";
					}
				}
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">LA BUSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br><br>\n";
			}

			$Paginador = new ClaseHTML();
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action[4]);
			$this->salida .= "		<br>\n";
			$this->salida .= "	<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action[1]."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************
		* Funcion donde se crea la forma donde se muestra la información de las notas 
		* credito seleccionada 
		*********************************************************************************/
		function FormaMostrarDetalleNotaCredito()
		{
			$this->MostrarDetalleNotaCredito();
			
			$this->salida .= ThemeAbrirTabla("DETALLE DE LA NOTA DE AJUSTE Nº ".$this->Datos['prefijo']." ".$this->Datos['nota_credito_ajuste']);
			$this->salida .= "	<center>\n";
			$this->salida .= "		<div class=\"label_error\">".$this->frmError["MensajeError"]."</div>\n";
			$this->salida .= "	</center>\n";
			
			if($this->Notas['nombre_tercero'])
			{
				$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "		<td colspan=\"2\"><b>NOTA DE AJUSTE PARA LA EMPRESA:</b></td>\n";			
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td align=\"center\"><b>".$this->Notas['tipo_id_tercero']." ".$this->Notas['tercero_id']."</b></td>\n";			
				$this->salida .= "		<td align=\"left\"><b>".$this->Notas['nombre_tercero']."</b></td>\n";
				$this->salida .= "	</tr>\n";
				if($this->Notas['observacion'])
				{
					$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "		<td colspan=\"2\"><b>OBSERVACIÓN</b></td>\n";			
					$this->salida .= "	</tr>\n";
					$this->salida .= "	<tr>\n";
					$this->salida .= "		<td colspan=\"2\" align=\"justify\"><b>".$this->Notas['observacion']."</b></td>\n";			
					$this->salida .= "	</tr>\n";
				}
				
				$this->salida .= "</table><br>\n";			
			}

			$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">CONCEPTOS DE LA NOTA DE AJUSTE</legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\" height=\"16\">\n";
			$this->salida .= "							<td width=\"45%\"><b>CONCEPTO</b></td>\n";
			$this->salida .= "							<td width=\"35%\"><b>DEPARTAMENTO/TERCERO</b></td>\n";
			$this->salida .= "							<td width=\"10%\"><b>DEBITO</b></td>\n";
			$this->salida .= "							<td width=\"10%\"><b>CREDITO</b></td>\n";
			$this->salida .= "						</tr>\n";				
			$c = $d = 0;
				
			if(sizeof($this->ConceptosV) > 0)
			{
				for($i=0; $i<sizeof($this->ConceptosV); $i++)
				{
					$credito = $debito = 0;
					$Celdas = $this->ConceptosV[$i];
					switch($Celdas['naturaleza'])
					{
						case 'C':	$credito = formatoValor($Celdas['valor']); $c += $Celdas['valor'];	break;
						case 'D':	$debito = formatoValor($Celdas['valor']);	 $d += $Celdas['valor'];	break;
					}
					
					$this->salida .= "						<tr height=\"19\">\n";
					$this->salida .= "							<td class=\"modulo_list_oscuro\"><b>".$Celdas['descripcion']."</b></td>\n";
					$this->salida .= "							<td class=\"modulo_list_oscuro\"><b>".$Celdas['departamento']."</b></td>\n";
					$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".$debito."</b></td>\n";
					$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".$credito."</b></td>\n";
					$this->salida .= "						</tr>\n";
				}
			}

			$c += $this->Facturas[0]['total'];
			
			$this->salida .= "						<tr height=\"19\">\n";
			$this->salida .= "							<td class=\"modulo_list_oscuro\"><b>TOTAL ABONO FACTURAS</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_oscuro\"><b>&nbsp;</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>0</b></td>\n";
			$this->salida .= "							<td class=\"modulo_list_claro\" align=\"right\"><b>".formatoValor($this->Facturas[0]['total'])."</b></td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "						<tr class=\"modulo_table_list_title\" height=\"19\">\n";
			$this->salida .= "							<td colspan=\"2\" align=\"left\">&nbsp;&nbsp;TOTAL</td>\n";
			$this->salida .= "							<td align=\"right\"><b>".formatoValor($d)."</b></td>\n";
			$this->salida .= "							<td align=\"right\"><b>".formatoValor($c)."</b></td>\n";
			$this->salida .= "						</tr>\n";
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			
			$this->salida .= "	</table><br>\n";
						
			if(sizeof($this->Facturas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td>\n";
				$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">FACTURAS CRUZADAS EN LA NOTA DE AJUSTE</legend>\n";
				$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
				$this->salida .= "							<td width=\"20%\"><b>FACTURA</b></td>\n";
				$this->salida .= "							<td width=\"20%\"><b>FECHA</b></td>\n";
				$this->salida .= "							<td width=\"20%\"><b>TOTAL</b></td>\n";
				$this->salida .= "							<td width=\"20%\"><b>SALDO</b></td>\n";
				$this->salida .= "							<td width=\"20%\"><b>ABONO</b></td>\n";
				$this->salida .= "						</tr>";
				
				for($i=0; $i<sizeof($this->Facturas); $i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
										
					($this->Datos['estado'] == '1')? $saldo = $this->Facturas[$i]['saldo']: $saldo = $this->Facturas[$i]['saldo'] - $Facturas[$i]['abono'];
										
					$this->salida .= "						<tr height=\"18\" class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";					
					$this->salida .= "							<td aling=\"left\"  >".$this->Facturas[$i]['prefijo']." ".$this->Facturas[$i]['factura_fiscal']."</td>\n";
					$this->salida .= "							<td align=\"center\">".$this->Facturas[$i]['registro']."</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($this->Facturas[$i]['total_factura'])."&nbsp;</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($saldo)."&nbsp;</td>\n";
					$this->salida .= "							<td align=\"right\" >".formatoValor($this->Facturas[$i]['abono'])."&nbsp;</td>\n";
					$this->salida .= "						</tr>";
				}
				
				$this->salida .= "					</table>\n";
				$this->salida .= "				</fieldset>\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
			}
			else
			{
				$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO FACTURAS CRUZADAS</b></center><br><br>\n";
			}
			
			$this->salida .= "	<table width=\"75%\" align=\"center\">\n";
			$this->salida .= "		<tr><td align=\"center\">\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/******************************************************************************************
		* Funcion donde se realiza la forma que muestra la informacion de todas las facturas que 
		* hay o de las que se buscaron 
		* 
		* @return boolean 
		*******************************************************************************************/
		function FormaInformacionFactura()
		{
			IncludeClass('CarteraHtml','','app','Cartera');
			$cth = new CarteraHtml();
			
			$this->salida .= ThemeAbrirTabla("CONSULTA FACTURAS");
			$this->salida .= "<script language=\"javascript\">\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= $cth->FormaBuscadorFacturas($this->request,$this->action,$this->empresa);	
			
			if($this->Factura)
			{
				if(sizeof($this->Factura) > 0)
				{
					$this->IncludeJS("CrossBrowser");
					$this->IncludeJS("CrossBrowserEvent");
					$this->IncludeJS("CrossBrowserDrag");
					IncludeClass('CarteraFacturacionHTML','','app','Cartera');
					$this->SetXajax(array("InformacionFactura","InformacionCuenta","InformacionGlosa","InformacionGlosaDetalle","InformacionFacturaExterna","InformacionRecibo","InformacionNota"),"app_modules/Cartera/RemoteXajax/DetalleFacturas.php");

					$this->salida .= "<script language=\"javascript\">\n";
					$this->salida .= "	function mOvr(src,clrOver)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		src.style.background = clrOver;\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function mOut(src,clrIn)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		src.style.background = clrIn;\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalleFactura(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionFactura(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarInformacionFacturaExterna(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionFacturaExterna(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarInformacionCuenta(cuenta,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionCuenta(cuenta,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalleGlosa(prefijo,factura,empresa,sistema)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionGlosa(prefijo,factura,empresa,sistema);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarGlosa(glosa,empresa,sistema)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionGlosaDetalle(empresa,glosa,sistema);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalle()\n";
					$this->salida .= "	{\n";
					$this->salida .= "		Iniciar();\n";
					$this->salida .= "		MostrarSpan('Facturas');\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarRecibo(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionRecibo(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarNota(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionNota(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "</script>\n";
					$this->salida .= CarteraFacturacionHTML::VentanaDetalle();

					$this->salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";		
					$this->salida .= "			<tr class=\"formulacion_table_list\" height=\"21\">\n";
					$this->salida .= "				<td width=\"7%\"><b>FACTURA</b></td>\n";
					$this->salida .= "				<td width=\"7%\"><b>FECHA</b></td>\n";
					$this->salida .= "				<td width=\"9%\"><b>V. FACTURA</b></td>\n";
					$this->salida .= "				<td width=\"9%\"><b>RETENCION</b></td>\n";
					$this->salida .= "				<td width=\"9%\"><b>SALDO</b></td>\n";
					$this->salida .= "				<td width=\"%\"><b>CLIENTE</b></td>\n";
					$this->salida .= "				<td width=\"9%\"><b>ESTADO</b></td>\n";
					$this->salida .= "				<td width=\"23%\" colspan=\"4\"><b>OPCIONES</b></td>\n";
					$this->salida .= "			</tr>";
					
					$estilo='modulo_list_oscuro'; 
					$background = "#CCCCCC";
					
					foreach($this->Factura as $key => $Celdas )
					{
						($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
						($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
						($Celdas['estado'] == '1')? $estado = "PAGADA":$estado = "ACTIVA";
										
						$this->salida .= "			<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$this->salida .= "				<td align=\"left\"   >".$Celdas['prefijo']." ".$Celdas['factura_fiscal']."</td>\n";
						$this->salida .= "				<td align=\"center\" >".$Celdas['registro']."</td>\n";
						$this->salida .= "				<td align=\"right\"  >".FormatoValor($Celdas['total_factura'])."</td>\n";
						$this->salida .= "				<td align=\"right\"  >".FormatoValor($Celdas['retencion_fuente'])."</td>\n";
						$this->salida .= "				<td align=\"right\"  >".FormatoValor($Celdas['saldo'])."</td>\n";
						$this->salida .= "				<td align=\"justify\">".$Celdas['nombre_tercero']."</td>\n";
						$this->salida .= "				<td align=\"center\" ><b class='normal_10AN'>".$estado."</b></td>\n";
						$this->salida .= "				<td align=\"center\" width=\"5%\">\n";
						if($Celdas['sistema'] == "SIIS")
							$this->salida .= "					<a href=\"javascript:MostrarDetalleFactura('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
						else
							$this->salida .= "					<a href=\"javascript:MostrarInformacionFacturaExterna('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
															
						$this->salida .= "						<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
						$this->salida .= "					</a>\n";
						$this->salida .= "				</td>\n";
						$this->salida .= "				<td align=\"center\" width=\"5%\" >\n";
						if($Celdas['num_glosas'] > 0)
						{
							$this->salida .= "				<a href=\"javascript:MostrarDetalleGlosa('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."','".$Celdas['sistema']."')\" title=\"INFORMACIÓN GLOSAS FACTURA\">\n";
							$this->salida .= "					<img src=\"".GetThemePath()."/images/Listado.png\" border=\"0\">\n";
							$this->salida .= "				</a>\n";
						}
						else
						{
							$this->salida .= "					<b class=\"label_mark\">GLOSAS</b>\n";
						}
						$this->salida .= "				</td>\n";
						
						$this->salida .= "				<td align=\"center\" width=\"6%\">\n";
						if($Celdas['num_recibos'])
						{
							$this->salida .= "				<a href=\"javascript:MostrarRecibo('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."')\" title=\"INFORMACIÓN RECIBOS\">\n";
							$this->salida .= "						<img src=\"".GetThemePath()."/images/cargar.png\" border=\"0\"></a>\n";
						}
						else
						{
							$this->salida .= "					<b class=\"label_mark\">RECIBOS</b>\n";
						}
						$this->salida .= "				</td>\n";

						$this->salida .= "				<td align=\"center\" >$nota\n";
						if($Celdas['num_notas'] > 0)
						{
							$this->salida .= "					<a href=\"javascript:MostrarNota('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."')\" title=\"INFORMACIÓN NOTAS\">\n";
							$this->salida .= "						<img src=\"".GetThemePath()."/images/panulado.png\" border=\"0\"></a>\n";
						}
						else
						{
							$this->salida .= "					<b class=\"label_mark\">NOTAS</b>\n";
						}
						$this->salida .= "				</td>\n";
						
						$this->salida .= "			</tr>\n";
					}
					$this->salida .= "	</table><br>\n";
					
					$Paginador = new ClaseHTML();
					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->action2);
					$this->salida .= "		<br>\n";
				}
				else
				{
					$this->salida .= "<br><center><b class=\"label_error\">NO SE ENCONTRO NINGUN REGISTRO</b></center><br><br>\n";
				}
			}
			$this->salida .= "		<table width=\"90%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function FormaMostrarInformacionFacturaRecibo()
		{
			$this->salida .= ThemeAbrirTabla("RECIBOS DE CAJA ASOCIADOS A LA FACTURA Nº ".$this->Factura);
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			
			$Facturas = $this->ObtenerInfoRecibosFactura();

			$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">INFORMACIÓN RECIBOS DE CAJA</legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td width=\"15%\"><b>RECIBO</b></td>\n";
			$this->salida .= "							<td width=\"15%\"><b>F. REGISTRO</b></td>\n";
			$this->salida .= "							<td width=\"15%\"><b>ABONO</b></td>\n";
			$this->salida .= "							<td width=\"15%\"><b>ESTADO</b></td>\n";
			$this->salida .= "							<td width=\"%\"  ><b>RESPONSABLE</b></td>\n";
			$this->salida .= "						</tr>";
			
			$estilo='modulo_list_oscuro'; 
			$background = "#CCCCCC";
			
			for($i=0; $i<sizeof($Facturas); $i++)
			{
				($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
				($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
													
				$this->salida .= "						<tr height=\"18\" class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";					
				$this->salida .= "							<td aling=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['recibo']."</td>\n";
				$this->salida .= "							<td align=\"center\">".$Facturas[$i]['registro']."</td>\n";
				$this->salida .= "							<td align=\"right\" >".formatoValor($Facturas[$i]['valor_abonado'])."&nbsp;</td>\n";
				$this->salida .= "							<td align=\"center\"><b class=\"label_mark\">".$Facturas[$i]['estado']."</b></td>\n";
				$this->salida .= "							<td align=\"left\"  >".$Facturas[$i]['nombre']."</td>\n";
				$this->salida .= "						</tr>";
			}
			
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "		<table width=\"60%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FormaMostrarInformacionFacturaNotas()
		{
			$this->salida .= ThemeAbrirTabla("NOTAS DE AJUSTE ASOCIADAS A LA FACTURA Nº ".$this->Factura);
			$this->salida .= "<script>\n";
			$this->salida .= "	function acceptNum(evt)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var nav4 = window.Event ? true : false;\n";
			$this->salida .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			
			$Facturas = $this->ObtenerInfoNotasAjuste();

			$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			$this->salida .= "				<fieldset class=\"fliedset\"><legend class=\"normal_10AN\">INFORMACIÓN NOTAS DE AJUSTE</legend>\n";
			$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "						<tr height=\"21\" class=\"modulo_table_list_title\">\n";
			$this->salida .= "							<td width=\"15%\"><b>NOTA</b></td>\n";
			$this->salida .= "							<td width=\"15%\"><b>F. REGISTRO</b></td>\n";
			$this->salida .= "							<td width=\"15%\"><b>ABONO</b></td>\n";
			$this->salida .= "							<td width=\"15%\"><b>ESTADO</b></td>\n";
			$this->salida .= "							<td width=\"%\"  ><b>RESPONSABLE</b></td>\n";
			$this->salida .= "						</tr>";
			
			$estilo='modulo_list_oscuro'; 
			$background = "#CCCCCC";

			for($i=0; $i<sizeof($Facturas); $i++)
			{
				($estilo == 'modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; ;  
				($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
													
				$this->salida .= "						<tr height=\"18\" class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";					
				$this->salida .= "							<td aling=\"left\"  >".$Facturas[$i]['prefijo']." ".$Facturas[$i]['nota']."</td>\n";
				$this->salida .= "							<td align=\"center\">".$Facturas[$i]['registro']."</td>\n";
				$this->salida .= "							<td align=\"right\" >".formatoValor($Facturas[$i]['valor_abonado'])."&nbsp;</td>\n";
				$this->salida .= "							<td align=\"center\"><b class=\"label_mark\">".$Facturas[$i]['estado']."</b></td>\n";
				$this->salida .= "							<td align=\"left\"  >".$Facturas[$i]['nombre']."</td>\n";
				$this->salida .= "						</tr>";
			}
			
			$this->salida .= "					</table>\n";
			$this->salida .= "				</fieldset>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
			$this->salida .= "		<table width=\"60%\" align=\"center\"><tr><td align=\"center\" id='lll'>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************
		* Funcion donde se crea la forma para mostrar la cartera, existente por planes
		*		
		*********************************************************************************/
		function FormaMostrarCarteraPlanes()
		{
			($this->envio == '0')? $ttl = "FACTURACION NO RADICADA": $ttl = "FACTURACION RADICADA";
			$this->salida  = ThemeAbrirTabla('CARTERA POR PLANES - '.$ttl);
			$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td width=\"15%\" class=\"modulo_table_list_title\">EMPRESA:</td>\n";
			$this->salida .= "			<td width=\"%\" class=\"label\" style=\"text-indent:11pt\">".$this->RazonSocial."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";
/* 			$this->salida .= "	<table width=\"500\" align=\"center\" >\n";		
			$this->salida .= "		<tr>\n";
			$this->salida .= "			<td>\n";
			//$this->salida .= $this->BuscadorTerceros(1);
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n"; */
			
			if(sizeof($this->Arreglo) > 0)
			{
				$estilo1 = "class=\"hc_table_submodulo_title\" style=\"text-align:center;font-size:10px;text-indent: 0pt\"";
				$estilo2 = "class=\"modulo_table_title\" style=\"text-align:center;text-indent: 0pt\" ";
				
				$this->salida .= "	<table border=\"0\" width=\"500\" align=\"center\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$this->salida .= "			<td class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center\" width=\"50%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"  width=\"50%\"><b>CORRIENTE</b></td>\n";
				//$this->salida .= "			<td class=\"modulo_table_title\" style=\"text-align:center\" width=\"33%\"><b>FACTURAS POR VENCER</b></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				
				$reporte = new GetReports();
				$mostrar = $reporte->GetJavaReport('app','Cartera','carteraplan',array("envio"=>$this->envio),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion = $reporte->GetJavaFunction();

				$this->salida .= $mostrar;
				$this->salida .= "  <br><center><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion\" class=\"label_error\">REPORTE CARTERA</a></center><br>\n";				
				$this->salida .= "	<table border=\"0\" width=\"53%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"></td>\n";
				for($i = 0; $i< 15; $i++)
				{
					if($this->Intervalos[$i] != "")
					{
						$nombreI = $this->Intervalos[$i];
						if($i > 0 )
						{
							$es = $estilo1;
						}
						else if($i == 0)
							{
								$es = " class=\"modulo_table_list_title\" ";
								//$nombreI = "ESTE MES";
							}
						
						$this->salida .= "			<td width=\"%\" $es colspan=\"3\">".$nombreI."</td>\n";
						$columna .= "			<td $es>SALDO</td>\n";
						$columna .= "			<td $es>PENDI</td>\n";
						$columna .= "			<td $es>DIFER</td>\n";
					}
				}
				
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">PLAN</td>\n";
				$this->salida .= "			".$columna;
				$this->salida .= "		</tr>\n";
				
				$saldoT = array();
				$pendienteT = array();
				$diferenciaT = array();
				
				$i =0;
				foreach($this->Arreglo as $key => $cartera)
				{
					if($cartera['plan'] != "")
					{	
						$datos = array("plan_id"=>$cartera['id'],"periodo"=>$this->PeriodoSeleccionado,
												 	 "nombre_tercero"=>$this->ClienteNombre,"nombre_plan"=>$cartera['plan']);
						$action11 = ModuloGetURL('app','Cartera','user','FormaMostrarFacturasPlan',$datos);
												 	   
						$this->salida .= "		<tr height=\"23\">\n";
						$this->salida .= "			<td rowspan=\"2\" class=\"modulo_list_claro\" width=\"150\" >";
						$this->salida .= "				<a href=\"$action11\">".$cartera['plan']."</a>";
						$this->salida .= "			</td>\n";
						
						$adicional = "";
						$saldo = 0;
						$pendiente = 0;
						$diferencia = 0;
						
						for($j = 0; $j<15; $j++)
						{
							if($this->Intervalos[$j] != "")
							{			
								($j == 0)? $es = " class=\"modulo_list_claro\" ":$es = " class=\"modulo_list_oscuro\" ";
								
								$difer = $cartera['periodos'][$j]['saldo'] - $cartera['periodos'][$j]['valor_pendiente'];
								$saldoT[$j] += $cartera['periodos'][$j]['saldo'];
								$pendienteT[$j] += $cartera['periodos'][$j]['valor_pendiente'];
								$diferenciaT[$j] += $difer;
								
								$saldo += $cartera['periodos'][$j]['saldo'];
								$pendiente += $cartera['periodos'][$j]['valor_pendiente'];
								$diferencia += $difer;
								
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($saldo)."</b></td>\n";
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($pendiente)."</b></td>\n";
								$adicional .= "			<td align=\"right\" class=\"tabla_submenu\" ><b>".formatoValor($diferencia)."</b></td>\n";
	
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($cartera['periodos'][$j]['saldo'])."</td>\n";
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($cartera['periodos'][$j]['valor_pendiente'])."</td>\n";
								$this->salida .= "			<td align=\"right\" $es>".formatoValor($difer)."</td>\n";														
							}
						}
	
						$this->salida .= "		</tr>\n";	
						$this->salida .= "		<tr height=\"23\">\n";
						$this->salida .= "		".$adicional;					
						$this->salida .= "		</tr>\n";
					}
					$i++;
				}
		
				$this->salida .= "		<tr height=\"23\">\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">TOTALES</td>\n";
				
				$saldo = $pendiente = $diferencia = 0;	
				
				for($j = 0; $j<15; $j++)
				{
					if($this->Intervalos[$j] != "")
					{
						$es = "class=\"modulo_table_list_title\" style=\"text-align:right;\" ";
							
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($saldoT[$j])."</td>\n";
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($pendienteT[$j])."</td>\n";
						$this->salida .= "			<td align=\"right\" $es>".formatoValor($diferenciaT[$j])."</td>\n";
						
						$saldo += $saldoT[$j];
						$pendiente += $pendienteT[$j];
						$diferencia += $diferenciaT[$j];
							
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($saldo)."</td>\n";
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($pendiente)."</td>\n";
						$acumulado .= "			<td align=\"right\" $es>".formatoValor($diferencia)."</td>\n";
						
					}		
				}
				
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr height=\"23\">\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\">ACUMULADO</td>\n";
				$this->salida .= "			".$acumulado;					
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";				
			}
			else
			{
				$this->salida .= "			<center><br><b class=\"label_error\">NO SE ENCONTRARON REGISTROS DISPONIBLES PARA LA CARTERA DE ESTA EMPRESA</b><br></center>\n";
			}
			
			$this->salida .= "	<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
		}
		/********************************************************************************
		* 
		*********************************************************************************/
		function FormaMostrarFacturasPlan() 
		{
			$this->MostrarFacturasPlan();
			$this->salida  = ThemeAbrirTabla('CARTERA DETALLADA DEL PLAN '.$this->PlanNombre);
			
			if(sizeof($this->Facturas) > 0)
			{
				$this->salida .= "	<table border=\"0\" width=\"87%\" align=\"center\">\n";
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"22\">\n";
				$this->salida .= "			<td class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-align:center\" width=\"33%\"><b>FACTURAS VENCIDAS</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_list_title\"  width=\"34%\"><b>FACTURAS DE ESTE MES</b></td>\n";
				$this->salida .= "			<td class=\"modulo_table_title\" style=\"text-align:center\" width=\"33%\"><b>FACTURAS POR VENCER</b></td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table><br>\n";
				
				$nombre = "";
				$saldo = $glosa = $aceptado = $pendiente = $noaceptado = $total = $recibos = $notas = 0;
				/*******************************************************************************************
				* Se evalua si la cantidad de registros encontrados excede el total de registros permitidos
				********************************************************************************************/
				if($this->conteo <= $this->Registros)
				{
					$this->IncludeJS("CrossBrowser");
					$this->IncludeJS("CrossBrowserEvent");
					$this->IncludeJS("CrossBrowserDrag");
					$this->SetXajax(array("InformacionFactura","InformacionCuenta","InformacionGlosa","InformacionGlosaDetalle","InformacionFacturaExterna"),"app_modules/Cartera/RemoteXajax/DetalleFacturas.php");

					$this->salida .= "<script language=\"javascript\">\n";
					$this->salida .= "	function mOvr(src,clrOver)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		src.style.background = clrOver;\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function mOut(src,clrIn)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		src.style.background = clrIn;\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalleFactura(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionFactura(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarInformacionFacturaExterna(prefijo,factura,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionFacturaExterna(prefijo,factura,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarInformacionCuenta(cuenta,empresa)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionCuenta(cuenta,empresa);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalleGlosa(prefijo,factura,empresa,sistema)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionGlosa(prefijo,factura,empresa,sistema);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarGlosa(glosa,empresa,sistema)\n";
					$this->salida .= "	{\n";
					$this->salida .= "		xajax_InformacionGlosaDetalle(empresa,glosa,sistema);\n";
					$this->salida .= "	}\n";
					$this->salida .= "	function MostrarDetalle()\n";
					$this->salida .= "	{\n";
					$this->salida .= "		Iniciar();\n";
					$this->salida .= "		MostrarSpan('Facturas');\n";
					$this->salida .= "	}\n";
					$this->salida .= "</script>\n";
					IncludeClass('CarteraDetalle','','app','Cartera');
					IncludeClass('CarteraFacturacionHTML','','app','Cartera');
					$this->salida .= CarteraFacturacionHTML::VentanaDetalle();
					
					$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
					$this->salida .= "			<td width=\"9%\">PERIODO</td>\n";
					$this->salida .= "			<td width=\"9%\">FACTURA</td>\n";
					$this->salida .= "			<td width=\"9%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"9%\">SALDO</td>\n";
					$this->salida .= "			<td width=\"9%\">V. GLOSA</td>\n";
					$this->salida .= "			<td width=\"9%\">V. ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"9%\">V. NO ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"9%\">V. PENDIENTE</td>\n";
					$this->salida .= "			<td width=\"9%\">V. RECIBOS</td>\n";
					$this->salida .= "			<td width=\"9%\">V. NOTAS DE AJUSTE</td>\n";
					$this->salida .= "			<td width=\"10%\" colspan=\"2\" width=\"%\">OPCIONES</td>\n";
					$this->salida .= "		</tr>\n";
					$i = 0;
					$j = 0;
					foreach($this->Facturas as $key => $Celdas)
					{
						$es = " class=\"modulo_list_oscuro\" ";
						if($Celdas['diferencia'] == 0)
						{
							$es = " class=\"modulo_list_claro\" ";
							$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 7pt\" ";
						}
						else
						{
							if($Celdas['diferencia'] < 0)
								$estilo = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 7pt\"";
							else
								$estilo = "class=\"modulo_table_title\" style=\"text-indent: 7pt\" ";
						}
						
						
						$total += $Celdas['total'];
						$saldo += $Celdas['saldo'];
						$glosa += $Celdas['valor_glosa'];
						$notas += $Celdas['valor_abonado_na'];
						$recibos += $Celdas['valor_abonado_rc'];
						$aceptado += $Celdas['valor_aceptado'];
						$pendiente += $Celdas['valor_pendiente'];
						$noaceptado += $Celdas['valor_no_aceptado'];
						
						$this->salida .= "		<tr $es height=\"18\">\n";
						if($nombre != $Celdas['nombre'])
							$this->salida .= "			<td $estilo rowspan= \"".(sizeof($this->Facturas2[$Celdas['nombre']])+1)."\"><b>".$Celdas['nombre']."</b></td>\n";
						
						$nombre = $Celdas['nombre'];
											
						$this->salida .= "			<td >&nbsp;".$Celdas['prefijo']." ".$Celdas['factura_fiscal']."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['total'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['saldo'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_glosa'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_aceptado'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_no_aceptado'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_pendiente'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_abonado_rc'])."</td>\n";
						$this->salida .= "			<td align=\"right\" >".formatoValor($Celdas['valor_abonado_na'])."</td>\n";
						$this->salida .= "			<td align=\"center\" width=\"5%\">\n";
						$this->salida .= "				<a href=\"javascript:MostrarDetalleFactura('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."')\" title=\"INFORMACION DE LA FACTURA\">\n";
						$this->salida .= "					<img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\">\n";
						$this->salida .= "				</a>\n";
						$this->salida .= "			</td>\n";
						$this->salida .= "			<td align=\"center\" width=\"5%\" >\n";
						if($Celdas['valor_glosa'] > 0)
						{
							$this->salida .= "				<a href=\"javascript:MostrarDetalleGlosa('".$Celdas['prefijo']."','".$Celdas['factura_fiscal']."','".$_SESSION['cartera']['empresa_id']."','SIIS')\" title=\"INFORMACIÓN GLOSAS FACTURA\">\n";
							$this->salida .= "					<img src=\"".GetThemePath()."/images/Listado.png\" border=\"0\">\n";
							$this->salida .= "				</a>\n";
						}
						$this->salida .= "			</td>\n";	
						$this->salida .= "		</tr>\n";
						
						$j++;
						if($j == sizeof($this->Facturas2[$Celdas['nombre']]))
						{
							$est = " class=\"tabla_menu\" ";
							
							$this->salida .= "		<tr>\n";
							$this->salida .= "			<td $est >&nbsp;</td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($total)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($saldo)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($glosa)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($aceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($noaceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($pendiente)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($recibos)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($notas)."</b></td>\n";
							$this->salida .= "			<td $est colspan=\"2\"></td>\n";
							$this->salida .= "		</tr>\n";
							
							$totalT += $total;
							$saldoT += $saldo;
							$glosaT += $glosa;
							$notasT += $notas;
							$recibosT +=  $recibos;
							$aceptadoT += $aceptado;
							$pendienteT +=  $pendiente;
							$noaceptadoT += $noaceptado;
							
							$j = $saldo = $glosa = $aceptado = $pendiente = $noaceptado = $total = $recibos = $notas = 0;
						}
						$i++;
					}
					
					$Pglosa = number_format((($glosaT/$totalT)*100),3,',','.');
					$Pnotas = number_format((($notasT/$totalT)*100),3,',','.');
					$Psaldo = number_format((($saldoT/$totalT)*100),3,',','.');
					$Precibos = number_format((($recibosT/$totalT)*100),3,',','.');
					$Paceptado = number_format((($aceptadoT/$totalT)*100),3,',','.');
					$Ppendiente = number_format((($pendienteT/$totalT)*100),3,',','.');
					$Pnoaceptado = number_format((($noaceptadoT/$totalT)*100),3,',','.');
					
					$est = "style=\"text-align:left;font-size:10px;text-indent: 6pt\"";
					
					$this->salida .= "</table><br>";
					$this->salida .= "	<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\" >\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\" >\n";
					$this->salida .= "			<td $est width=\"18%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($totalT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($saldoT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($glosaT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($aceptadoT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($noaceptadoT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($pendienteT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($recibosT)."</td>\n";
					$this->salida .= "			<td width=\"9%\" align=\"right\" >".formatoValor($notas)."</td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\"></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"23\" >\n";
					$this->salida .= "			<td $est>PORCENTAJES</td>\n";
					$this->salida .= "			<td align=\"right\"><b>100 %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Psaldo." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Pglosa." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Paceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Pnoaceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Ppendiente." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Precibos." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"><b>".$Pnotas." %</b></td>\n";
					$this->salida .= "			<td align=\"right\"></td>\n";
					$this->salida .= "		</tr>\n";

					$this->salida .= "	</table><br>\n";
				}
				else
				{
					$this->salida .= "	<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"18\">\n";
					$this->salida .= "			<td width=\"10%\">PERIODO</td>\n";
					$this->salida .= "			<td width=\"10%\">FACTURA</td>\n";
					$this->salida .= "			<td width=\"10%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"10%\">SALDO</td>\n";
					$this->salida .= "			<td width=\"10%\">V. GLOSA</td>\n";
					$this->salida .= "			<td width=\"10%\">V. ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"10%\">V. NO ACEPTADO</td>\n";
					$this->salida .= "			<td width=\"10%\">V. PENDIENTE</td>\n";
					$this->salida .= "			<td width=\"10%\">V. RECIBOS</td>\n";
					$this->salida .= "			<td width=\"10%\">V. NOTAS DE AJUSTE</td>\n";
					$this->salida .= "		</tr>\n";
					$j = 0;
					for($i= 0; $i<sizeof($this->Facturas); $i++)
					{
						$Celdas = $this->Facturas[$i];
						
						if($Celdas['diferencia'] == 0)
						{
							$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 7pt\" ";
						}
						else
						{
							if($Celdas['diferencia'] < 0)
								$estilo = "class=\"hc_table_submodulo_title\" style=\"font-size:10px;text-indent: 7pt\"";
							else
								$estilo = "class=\"modulo_table_title\" style=\"text-indent: 7pt\" ";
						}

						$total += $Celdas['total'];
						$saldo += $Celdas['saldo'];
						$glosa += $Celdas['valor_glosa'];
						$notas += $Celdas['valor_abonado_na'];
						$recibos += $Celdas['valor_abonado_rc'];
						$aceptado += $Celdas['valor_aceptado'];
						$pendiente += $Celdas['valor_pendiente'];
						$noaceptado += $Celdas['valor_no_aceptado'];
												
						if($nombre != $Celdas['nombre'])
						{
							$this->salida .= "		<tr height=\"18\">\n";
							$this->salida .= "			<td $estilo ><b>".$Celdas['nombre']."</b></td>\n";
						}
						
						$j++;
						$nombre = $Celdas['nombre'];
						$dif = $Celdas['diferencia'];
						
						if($j == sizeof($this->Facturas2[$Celdas['nombre']]))
						{
							$est = " class=\"modulo_list_claro\" ";
							$action3 = ModuloGetURL('app','Cartera','user','FormaMostrarFacturasPlanRango',
															   			 array("periodo"=>$_REQUEST['periodo'],"rango"=>$Celdas['nombre'],"diferencia"=>$Celdas['diferencia'],
																   			 	   "intervalo"=>$Celdas['intervalo'],"direccion"=>$Celdas['direccion'],"diferencia"=>$dif));
										   			 
							$this->salida .= "			<td $est align=\"center\"><a href=\"".$action3."\"><img src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\" title=\"VER FACTURAS\"></a></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($total)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($saldo)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($glosa)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($aceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($noaceptado)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($pendiente)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($recibos)."</b></td>\n";
							$this->salida .= "			<td $est align=\"right\" ><b>".formatoValor($notas)." </b></td>\n";
							$this->salida .= "		</tr>\n";
							
							$totalT += $total;
							$saldoT += $saldo;
							$glosaT += $glosa;
							$notasT += $notas;
							$recibosT +=  $recibos;
							$aceptadoT += $aceptado;
							$pendienteT +=  $pendiente;
							$noaceptadoT += $noaceptado;
														
							$j = $saldo = $glosa = $aceptado = $pendiente = $noaceptado = $total = $notas = $recibos = 0;
						}
						
					}
					
					$Pglosa = number_format((($glosaT/$totalT)*100),3,',','.');
					$Pnotas = number_format((($notasT/$totalT)*100),3,',','.');
					$Psaldo = number_format((($saldoT/$totalT)*100),3,',','.');
					$Precibos = number_format((($recibosT/$totalT)*100),3,',','.');
					$Paceptado = number_format((($aceptadoT/$totalT)*100),3,',','.');
					$Ppendiente = number_format((($pendienteT/$totalT)*100),3,',','.');
					$Pnoaceptado = number_format((($noaceptadoT/$totalT)*100),3,',','.');
													
					$this->salida .= "</table><br>";
					$this->salida .= "	<table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
					$this->salida .= "			<td width=\"20%\">TOTAL</td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($totalT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($saldoT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($glosaT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($aceptadoT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($noaceptadoT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($pendienteT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($recibosT)."</b></td>\n";
					$this->salida .= "			<td width=\"10%\" align=\"right\" ><b>".formatoValor($notasT)."</b></td>\n";					
					$this->salida .= "		</tr>\n";
					$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"21\">\n";
					$this->salida .= "			<td >PORCENTAJES</td>\n";
					$this->salida .= "			<td align=\"right\" ><b>100 %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Psaldo." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Pglosa." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Paceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Pnoaceptado." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Ppendiente." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Precibos." %</b></td>\n";
					$this->salida .= "			<td align=\"right\" ><b>".$Pnotas." %</b></td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "	</table><br>\n";
				}
			}
			$this->salida .= "<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FormaMostrarFacturasPlanRango()
		{
			$this->MostrarFacturasPlanRango();
			
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->SetXajax(array("InformacionFactura","InformacionCuenta","InformacionGlosa","InformacionGlosaDetalle","InformacionFacturaExterna"),"app_modules/Cartera/RemoteXajax/DetalleFacturas.php");

			IncludeClass('CarteraFacturacionHTML','','app','Cartera');
			$chtml = new CarteraFacturacionHTML();
			$this->salida = $chtml->FormaMostrarFacturasPorRango($this->Facturas[$this->rqst['rango']],$this->rqst,$this->Totales,$this->action);
			
			return true;
		}
		/**********************************************************************************
		* Function donde se muestra las opciones de consulta de la cartera que tiene 
		* posibiliadad de ver el usuario
		* 
		* @return boolean
		***********************************************************************************/
		function FormaMostrarConsultaTodo()
		{
			$this->MostrarConsultaTodo();
			
			$this->salida  = ThemeAbrirTabla('REPORTE DE CARTERA Y FACTURACIÓN');
			$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">REPORTES</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action2."\"><b>CARTERA (FACTURACIÓN RADICADA)</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action3."\"><b>FACTURACIÓN SIN RADICAR</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_claro\"></td></tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_list_oscuro\" align=\"center\">\n";
			$this->salida .= "						<a href=\"".$this->action4."\"><b>CUENTAS SIN FACTURAR</b></a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr><td class=\"modulo_list_oscuro\"label\"></td></tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();

			return true;
		}
		/********************************************************************************
		* Funcion donde se muestran las opciones de reportes para la cartera enviada
		*********************************************************************************/
		function FormaReportesCarteraEnviada()
		{
			$this->ReportesCarteraEnviada();
			$estilo1 = "style=\"text-align:left;text-indent: 5pt\"";
			$TiposTerceros = $_SESSION['cartera']['Enviados'];
			
			switch($this->Periodo)
			{
				case '0': $cero = "selected"; break;
				case '1': $uno  = "selected"; break;
				case '2': $dos  = "selected"; break;
				case '3': $tres = "selected"; break;
				case '4': $cuat = "selected"; break;
				case '5': $cinc = "selected"; break;
				case '6': $seis = "selected"; break;
				case '7': $siet = "selected"; break;
			}
			
			$reporte = new GetReports();
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteraenviada',array("tercero"=>$this->Cliente,"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion1 = $reporte->GetJavaFunction();				
			
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteraresumida',array("tercero"=>$this->Cliente,"enviado"=>'1',"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion2 = $reporte->GetJavaFunction();
			
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteratipoentidad',array("enviado"=>'1',"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion3 = $reporte->GetJavaFunction();
			
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteraplan',array("envio"=>'1',"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion4 = $reporte->GetJavaFunction();
			
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteravencimientos',array($this->Arreglo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion5 = $reporte->GetJavaFunction();
		
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carterac',array("fecha"=>$this->request['fecha']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion6 = $reporte->GetJavaFunction();

			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteraresumen',array("mes"=>$this->request['meses']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion7 = $reporte->GetJavaFunction();
			
			$this->salida .= $reporte->GetJavaReport('app','Cartera','carterafacturado',array("mes"=>$this->request['meses_factura'],"empresa"=>$_SESSION['cartera']['empresa_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion8 = $reporte->GetJavaFunction();

			$this->salida .= $reporte->GetJavaReport('app','Cartera','carteraanulado',array("mes"=>$this->request['meses_anulado'],"empresa"=>$_SESSION['cartera']['empresa_id']),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion9 = $reporte->GetJavaFunction();
			
			$this->salida .= ThemeAbrirTabla('REPORTE DE CARTERA Y FACTURACIÓN RADICADA');
			$this->salida .= "	<script>\n";
			$this->salida .= "		function acceptDate(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 ||(key >= 47 && key <= 57));\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<center><div id=\"error\" class=\"label_error\">".$this->frmError['MensajeError']."</div></center>\n";
			$this->salida .= "	<form name=\"reportes\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"30%\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"modulo_table_list_title\">PERIODO:</td>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<select name=\"periodo\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "						<option value='X'>-----SELECCIONAR-----</option>\n";			
			$this->salida .= "						<option value='7' $siet>ESTE MES</option>\n";			
			$this->salida .= "						<option value='6' $seis>A 30 DÍAS</option>\n";			
			$this->salida .= "						<option value='5' $cinc>A 60 DÍAS</option>\n";			
			$this->salida .= "						<option value='4' $cuat>A 90 DÍAS</option>\n";			
			$this->salida .= "						<option value='3' $tres>A 120 DÍAS</option>\n";			
			$this->salida .= "						<option value='2' $dos >A 150 DÍAS</option>\n";			
			$this->salida .= "						<option value='1' $uno >A 180 DÍAS</option>\n";			
			$this->salida .= "						<option value='0' $cero>A MAS DE 180</option>\n";						
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";		
			$this->salida .= "		<table align=\"center\" width=\"80%\" border=\"0\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">REPORTES</legend>\n";
			$this->salida .= "						<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"30%\">REPORTE POR TIPO DE ENTIDAD</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" width=\"60%\">Reporte donde se resume por tipo de entidad la cartera enviada</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion3\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE RESUMIDO CARTERA</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >Reporte donde esta resunida toda la cartera radicada, por periodos de tiempo</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion2\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE CARTERA X CLIENTE</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "									<select name=\"nombre_tercero\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "										<option value='0'>-----TODOS LOS CLIENTES-----</option>\n";

			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$opciones = $TiposTerceros[$i];
				($this->Cliente == $opciones['tipo_id_tercero']."/".$opciones['tercero_id'])? $selected = " selected ":$selected = "";
				
				$this->salida .= "										<option value='".$opciones['tipo_id_tercero']."/".$opciones['tercero_id']."' $selected >".$opciones['nombre_tercero']."</option>\n";			
			}
		
			$this->salida .= "									</select>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE VENCIMIENTOS</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "									<select name=\"nombre_tercero1\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "										<option value='0'>-----TODOS LOS CLIENTES-----</option>\n";

			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$opciones2 = $TiposTerceros[$i];
				($this->Cliente2 == $opciones2['tipo_id_tercero']."ç".$opciones2['tercero_id']."ç".$opciones2['nombre_tercero'])? $selected = " selected ":$selected = "";
				
				$this->salida .= "										<option value='".$opciones2['tipo_id_tercero']."ç".$opciones2['tercero_id']."ç".$opciones2['nombre_tercero']."' $selected >".$opciones2['nombre_tercero']."</option>\n";			
			}
		
			$this->salida .= "									</select>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			
			if(sizeof($this->Arreglo) > 0)
				$this->salida .= "  								<a href=\"javascript:$funcion5\" class=\"label_error\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;VER</a>\n";
			
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE POR PLANES</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >Reporte de la cartera resumido por planes para un periodo de tiempo</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion4\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE POR FECHA DE CORTE</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "									<input type=\"text\" class=\"input-text\" name=\"fecha\" size=\"12\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"".$this->request['fecha']."\">\n";
			$this->salida .= "									".ReturnOpenCalendario('reportes','fecha','/')."\n";			
			$this->salida .= "									<input type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"Aceptar\">\n";

			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			if($this->mst)
				$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion6\" class=\"label_error\">VER</a>\n";
			
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE POR MES</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "									<select name=\"meses\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "										<option value='0'>--SELECCIONAR--</option>\n";

			foreach($this->meses as $key => $mes)
			{
				($key == $this->request['meses'])? $sel = " selected ":$sel = "";
				$this->salida .= "										<option value='".$key."' $sel >".$mes."</option>\n";			
			}
		
			$this->salida .= "									</select>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			if($this->request['meses'])
				$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion7\" class=\"label_error\">VER</a>\n";
			
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE FACTURADO POR MES</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "									<select name=\"meses_factura\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "										<option value='0'>--SELECCIONAR--</option>\n";

			foreach($this->meses as $key => $mes)
			{
				($key == $this->request['meses_factura'])? $sel = " selected ":$sel = "";
				$this->salida .= "										<option value='".$key."' $sel >".$mes."</option>\n";			
			}
		
			$this->salida .= "									</select>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			if($this->request['meses_factura'])
				$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion8\" class=\"label_error\">VER</a>\n";
			
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE ANULADO POR MES</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >\n";
			$this->salida .= "									<select name=\"meses_anulado\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "										<option value='0'>--SELECCIONAR--</option>\n";

			foreach($this->meses as $key => $mes)
			{
				($key == $this->request['meses_anulado'])? $sel = " selected ":$sel = "";
				$this->salida .= "										<option value='".$key."' $sel >".$mes."</option>\n";			
			}
		
			$this->salida .= "									</select>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			if($this->request['meses_anulado'])
				$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion9\" class=\"label_error\">VER</a>\n";
			
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			
			$this->salida .= "						</table>\n";
			$this->salida .= "					</fieldset>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</form>\n";
			$this->salida .= "<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************
		* Funcion donde se muestran las opciones de reportes para la cartera no enviada
		*********************************************************************************/
		function FormaReportesCarteraNoEnviada()
		{
			$this->ReportesCarteraNoEnviada();
			$estilo1 = "style=\"text-align:left;text-indent: 5pt\"";
			$TiposTerceros = $_SESSION['cartera']['NoEnviados'];
			
			switch($this->Periodo)
			{
				case '0': $cero = "selected"; break;
				case '1': $uno  = "selected"; break;
				case '2': $dos  = "selected"; break;
				case '3': $tres = "selected"; break;
				case '4': $cuat = "selected"; break;
				case '5': $cinc = "selected"; break;
				case '6': $seis = "selected"; break;
				case '7': $siet = "selected"; break;
			}
			
			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','Cartera','carteranoenviada',array("tercero"=>$this->Cliente,"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion1 = $reporte->GetJavaFunction();				
			
			$mostrar .= $reporte->GetJavaReport('app','Cartera','carteraresumida',array("tercero"=>$this->Cliente,"enviado"=>'0',"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion2 = $reporte->GetJavaFunction();
			
			$mostrar .= $reporte->GetJavaReport('app','Cartera','carteratipoentidad',array("enviado"=>'0',"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion3 = $reporte->GetJavaFunction();
			
			$mostrar .= $reporte->GetJavaReport('app','Cartera','carteraplan',array("envio"=>'0',"periodo"=>$this->Periodo),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion4 = $reporte->GetJavaFunction();
			
			$this->salida  = ThemeAbrirTabla('REPORTE DE CARTERA Y FACTURACIÓN SIN RADICAR');
			$this->salida .= "	<form name=\"reportes\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"30%\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"modulo_table_list_title\">PERIODO:</td>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<select name=\"periodo\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "						<option value='X'>-----SELECCIONAR-----</option>\n";			
			$this->salida .= "						<option value='7' $siet>ESTE MES</option>\n";			
			$this->salida .= "						<option value='6' $seis>A 30 DÍAS</option>\n";			
			$this->salida .= "						<option value='5' $cinc>A 60 DÍAS</option>\n";			
			$this->salida .= "						<option value='4' $cuat>A 90 DÍAS</option>\n";			
			$this->salida .= "						<option value='3' $tres>A 120 DÍAS</option>\n";			
			$this->salida .= "						<option value='2' $dos >A 150 DÍAS</option>\n";			
			$this->salida .= "						<option value='1' $uno >A 180 DÍAS</option>\n";			
			$this->salida .= "						<option value='0' $cero>A MAS DE 180</option>\n";						
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";
			
			$this->salida .= $mostrar;			
			$this->salida .= "		<table align=\"center\" width=\"80%\" border=\"0\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">REPORTES</legend>\n";
			$this->salida .= "						<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"30%\">REPORTE POR TIPO DE ENTIDAD</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" width=\"60%\">Reporte donde se resume por tipo de entidad la cartera sin radicar</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion3\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE RESUMIDO CARTERA</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >Reporte donde esta resunida toda la cartera sin radicar, por periodos de tiempo</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion2\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE CARTERA X CLIENTE</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "									<select name=\"nombre_tercero\" class=\"select\" onchange=\"submit()\">\n";
			$this->salida .= "										<option value='0'>-----TODOS LOS CLIENTES-----</option>\n";

			for($i=0; $i<sizeof($TiposTerceros); $i++)
			{
				$opciones = $TiposTerceros[$i];
				($this->Cliente == $opciones['tipo_id_tercero']."/".$opciones['tercero_id'])? $selected = " selected ":$selected = "";
				
				$this->salida .= "										<option value='".$opciones['tipo_id_tercero']."/".$opciones['tercero_id']."' $selected >".$opciones['nombre_tercero']."</option>\n";			
			}
		
			$this->salida .= "									</select>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"33%\">REPORTE POR PLANES</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" >Reporte de la cartera resumido por planes para un periodo de tiempo</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion4\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";
			$this->salida .= "					</fieldset>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</form>\n";
			$this->salida .= "<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function FormaReportesCarteraCuentas()
		{
			$this->ReportesCarteraCuentas();
			$estilo1 = "style=\"text-align:left;text-indent: 5pt\"";
			
			$reporte = new GetReports();
			$mostrar = $reporte->GetJavaReport('app','Cartera','carteracuentas',array("estado"=>$this->EstadoC,"deptno"=>$this->DepartamentoSel),array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcion1 = $reporte->GetJavaFunction();				
			
			$uno = $dos = $tre = "";
			switch($this->EstadoC)
			{
				case '1': $uno = "selected"; break;
				case '2': $dos = "selected"; break;
				case '3': $tre = "selected"; break;
			}			
			
			$this->salida  = ThemeAbrirTabla('REPORTE DE CARTERA Y FACTURACIÓN');
			$this->salida .= "	<form name=\"reportes\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"55%\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $estilo1 width=\"25%\">ESTADO:</td>\n";
			$this->salida .= "				<td $estilo1 class=\"modulo_list_claro\">\n";
			$this->salida .= "					<select name=\"estado\" class=\"select\">\n";
			$this->salida .= "						<option value='0'>---SELECCIONAR---</option>\n";			
			$this->salida .= "						<option value='1' $uno>ACTIVA</option>\n";			
			$this->salida .= "						<option value='3' $tre>CUADRADA</option>\n";			
			$this->salida .= "						<option value='2' $dos>INACTIVA</option>\n";			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td $estilo1>DEPARTAMENTO</td>\n";
			$this->salida .= "				<td $estilo1 class=\"modulo_list_claro\">\n";
			$this->salida .= "					<select name=\"departamento\" class=\"select\">\n";
			$this->salida .= "						<option value='0'>---SELECCIONAR---</option>\n";			
			for($i=0; $i<sizeof($this->Departamentos); $i++)
			{
				$opciones = $this->Departamentos[$i];
				($this->DepartamentoSel == $opciones['departamento'])? $sel = " selected ":$sel = "";
				
				$this->salida .= "										<option value='".$opciones['departamento']."' $sel >".$opciones['descripcion']."</option>\n";			
			}			
			$this->salida .= "					</select>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td class=\"modulo_list_claro\" align=\"center\" colspan=\"2\">\n";
			$this->salida .= "					<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table><br>\n";
			$this->salida .= $mostrar;			
			$this->salida .= "		<table align=\"center\" width=\"80%\" border=\"0\">\n";
			$this->salida .= "			<tr>\n";
			$this->salida .= "				<td>\n";
			$this->salida .= "					<fieldset class=\"fieldset\"><legend class=\"normal_10AN\">REPORTES</legend>\n";
			$this->salida .= "						<table align=\"center\" width=\"100%\" class=\"modulo_table_list\" border=\"0\">\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td $estilo1 width=\"30%\">REPORTE CUENTAS SIN FACTURAR</td>\n";
			$this->salida .= "								<td align=\"justify\" class=\"modulo_list_claro\" width=\"60%\">Reporte en el que se muestran las cuentas que no han sido facturadas y a que departamento pertenecen </td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "  								<img src=\"".GetThemePath()."/images/imprimir.png\" border='0' title=\"REPORTE\">&nbsp;<a href=\"javascript:$funcion1\" class=\"label_error\">VER</a>\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";
			$this->salida .= "					</fieldset>\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</form>\n";
			$this->salida .= "<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"forma\" action=\"".$this->action1."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************** 
		* 
		* @return String cadena con el mensaje 
		***********************************************************************************/
		function FormaMostrarEnviosCliente()
		{
			$this->MostrarEnviosCliente();
			
			IncludeClass('CarteraHtml','','app','Cartera');
			$chtml = new CarteraHtml();
			$this->salida = $chtml->FormaEnviosCliente($this->datos,$this->request['datos_cliente'],$this->action);
			
			return true;
		}
		/********************************************************************************** 
		* Funcion que retorna el mensaje que se desea desplegar en la forma 
		* 
		* @return String cadena con el mensaje 
		***********************************************************************************/
		function FormaMostrarFacturasEnvio()
		{
			$this->MostrarFacturasEnvio();
			
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$this->SetXajax(array("InformacionFactura","InformacionCuenta","InformacionGlosa","InformacionGlosaDetalle","InformacionFacturaExterna"),"app_modules/Cartera/RemoteXajax/DetalleFacturas.php");

			IncludeClass('CarteraFacturacionHTML','','app','Cartera');
			$chtml = new CarteraFacturacionHTML();
			$this->salida = $chtml->FormaFacturasEnvio($this->datos,$this->request['datos_cliente'],$this->action);
			
			return true;
		}
		/********************************************************************************** 
		* Funcion que retorna el mensaje que se desea desplegar en la forma 
		* 
		* @return String cadena con el mensaje 
		***********************************************************************************/
		function FormaMostrarTodasFacturas()
		{
			$this->MostrarTodasFacturas();
			
			IncludeClass('CarteraFacturacionHTML','','app','Cartera');
			$chtml = new CarteraFacturacionHTML();
			$this->salida = $chtml->FormaMostrarTodasFacturas($this->request,$this->action,$this->facturas,$this->envios,$this->meses,$this->datos,$this->prefijos);
			
			return true;
		}
	}
?>
