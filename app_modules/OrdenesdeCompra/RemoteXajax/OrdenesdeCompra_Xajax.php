<?php
	function ListadoProductosRequisicion($requisicion)
	{
		$objResponse=new xajaxResponse();
		
		$objClass=new app_OrdenesdeCompra_user;
		$datosList=$objClass->ListaCotizarCompra($_SESSION['OC']['empresa_id'],$requisicion);
		$salida=ListadoProductosRequisicion_HTML($datosList,$requisicion);
		$objResponse->assign("d2Contents","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	}
	
	function ListadoProductosRequisicion_HTML($listadoPro,$requisicion=null)
	{
		$objClass=new app_OrdenesdeCompra_user;

		$salida = "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td colspan=\"6\">PRODUCTOS DE LA REQUISICION  $requisicion</td>";
		$salida .= "	</tr>";
		$salida .= "	<tr class=\"modulo_table_list_title\">";
		$salida .= "		<td width=\"8%\" >CÓDIGO</td>";
		$salida .= "		<td width=\"56%\">DESCRIPCIÓN</td>";
		$salida .= "		<td width=\"10%\">UNIDAD</td>";
		$salida .= "		<td width=\"16%\">CONTENIDO PRESENTACIÓN</td>";
		$salida .= "		<td width=\"10%\">CANTIDAD</td>";
		if($listadoPro)
		{
			$i=0;
			foreach($listadoPro as $key=>$valor)
			{
				if($i%2==0)
					$estilo="modulo_list_claro";
				else
					$estilo="modulo_list_oscuro";	
			
				$salida .= "<tr class=\"$estilo\" id=\"capa1$i\">";
				$salida .= "	<td>".$valor['codigo_producto']."</td>";
				$salida .= "	<td>".$valor['descripcion']."</td>";
				$salida .= "	<td>".$valor['desunidad']."</td>";
				$salida .= "	<td>".$valor['contenido_unidad_venta']."</td>";
				$salida .= "	<td>".$valor['cantidad']."</td>";
				$salida .= "</tr>";
			}
		}
		else
		{
			$salida .= "	<tr class=\"label_error\">";
			$salida .= "		<td width=\"100%\" colspan=\"6\">NO HAY PRODUCTOS SELECCIONADOS</td>";
			$salida .= "	</tr>";
		}
		
		$salida .= "</table><br>";
		
		return $salida;
	}
	
	function OrdenesCompra($proveedor)
	{
		$objResponse=new 	xajaxResponse();
		
		$objClass=new app_OrdenesdeCompra_user;
		
		$datos=$objClass->GetOrdenesCompra($proveedor);
		
		$salida = OrdenesCompra_HTML($datos);
		
		$objResponse->assign("ordenI","innerHTML",$salida);
		
		return $objResponse;
	}
	
	function OrdenesCompra_HTML($datos)
	{
		$salida="";
		$salida.= "<option value=\"\">--SELECCIONE # ORDEN DE COMPRA--</option>";
		foreach($datos as $key=>$valor)
		{
			$salida.= "<option value=\"".$valor['orden_pedido_id']."\">ORDEN COMPRA N. ".$valor['orden_pedido_id']."</option>";
		}
		
		return $salida;
	}
	
	function GetOrdenes($pagina,$proveedor,$orden)
	{
		$objResponse=new 	xajaxResponse();
		
		$objClass=new app_OrdenesdeCompra_user;
		
		if($datos = $objClass->BuscarOrdenes($pagina,$proveedor,$orden))
		{	
			$conteo=$objClass->conteo;
			$limite=15;
			$salida = GetOrdenes_HTML($datos,$pagina,$conteo,$limite,$proveedor,$orden);
			
			$objResponse->assign("ListadoOrdenes","innerHTML",$objResponse->setTildes($salida));
		}	
		return $objResponse;
	}
	
	
	function ConfirmEnviarOrden($proveedor,$orden,$capaEst,$capaEnv)
	{
		$objResponse=new 	xajaxResponse();
		
		$objClass=new app_OrdenesdeCompra_user;
		
		$datos = $objClass->EnviarOrdenPedidoProCompra($proveedor,$orden);

		$objResponse->assign($capaEst,"innerHTML","ENVIADA");
		$objResponse->assign($capaEnv,"innerHTML","");
		
		return $objResponse;
	}
	
	function EnviarOrden($proveedor,$orden,$capaEst,$capaEnv)
	{
		$objResponse=new xajaxResponse();
		
		$salida.="<table align=\"center\" width=\"100%\">";
		$salida.="	<tr>";
		$salida.="		<td colspan=\"2\" align=\"center\" class=\"label_error\">";
		$salida.="			CONFIRMA EL ENVIO DEL LA ORDEN DE COMPRA N. ".$orden." ?<br>";
		$salida.="		</td>";
		$salida.="	</tr>";
		$salida.="	<tr align=\"center\">";
		$salida.="		<td>";
		$salida.="			<input type=\"button\" name=\"elimin\" class=\"input-submit\" value=\"ENVIAR\" onclick=\"xajax_ConfirmEnviarOrden('$proveedor','$orden','$capaEst','$capaEnv');Cerrar('d2Container');\">";
		$salida.="		</td>";
		$salida.="		<td>";
		$salida.="			<input type=\"button\" name=\"cancel\" class=\"input-submit\" value=\"CANCELAR\" onclick=\"Cerrar('d2Container');\">";
		$salida.="		</td>";
		$salida.="	</tr>";
		$salida.="</table>";
		
		$objResponse->assign("d2Contents","innerHTML",$objResponse->setTildes($salida));
		
		return $objResponse;
	
	}
	
	function GetOrdenes_HTML($datos,$pagina,$conteo,$limite,$proveedor,$orden)
	{
		$salida.= "	<br><table width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$salida.= "		<tr class=\"modulo_table_list_title\">";
		$salida.= "			<td width=\"10%\">ORDEN #</td>";
		$salida.= "			<td width=\"30%\">PROVEEDOR</td>";
		$salida.= "			<td width=\"10%\">FECHA</td>";
		$salida.= "			<td width=\"30%\">USUARIO</td>";
		//$salida.= "			<td width=\"10%\">ESTADO</td>";
		$salida.= "			<td width=\"10%\">TOTAL</td>";
		//$salida.= "			<td width=\"10%\">ENVIAR ORDEN</td>";
		$salida.= "			<td width=\"5%\">IMPRESION</td>";
		$salida.= "		</tr>";
		$i=0;
		foreach($datos as $valor)
		{
			if($i%2==0)
			{
				$estilo="modulo_list_claro";
			}
			else
			{
				$estilo="modulo_list_oscuro";
			}
			
			$salida.= "	<tr class=\"$estilo\">";
			$salida.= "		<td>".$valor['orden_pedido_id']."</td>";
			$salida.= "		<td>".$valor['nombre_proveedor']."</td>";
			$salida.= "		<td align=\"center\">".$valor['fecha_orden']."</td>";
			$salida.= "		<td>".$valor['nombre_usuario']."</td>";
			//$salida.= "		<td id=\"estado".$valor['orden_pedido_id']."\">".$valor['estado']."</td>";
			$salida.= "		<td align=\"right\"> $ ".FormatoValor($valor['total_compra'])."</td>";
			/*if($valor['estado']!='ENVIADA')
				$salida.= "		<td align=\"center\" id=\"envio".$valor['orden_pedido_id']."\"><a href=\"javascript:Iniciar('CONFIRMACION ENVIO ORDEN DE COMPRA');MostrarSpan('d2Container');EnviarOrden('".$valor['codigo_proveedor_id']."','".$valor['orden_pedido_id']."','estado".$valor['orden_pedido_id']."','envio".$valor['orden_pedido_id']."');\"><img src=\"".GetThemePath()."/images/producto.png\" border=\"0\" title=\"ENVIAR ORDEN DE COMPRA\"></a></td>";
			else
				$salida.= "		<td align=\"center\">&nbsp;</td>";*/
			$direccion="app_modules/OrdenesdeCompra/reports/html/OrdenesdeCompra_html.report.php?orden_pedido_id=".$valor['orden_pedido_id']."&proveedor=".$valor['codigo_proveedor_id']."&fecha_orden=".$valor['fecha_orden']."&usuario=".$valor['nombre_usuario'];

			$salida.= "		<td align=\"center\"><a href=\"javascript:AbrirVentanaImpresion('$direccion');\"><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" title=\"IMPRESION DE LA ORDEN DE COMPRA\"></a></td>";
			$salida.= "	</tr>";
			$i++;
		}
		$salida.= "	</table>";
		
		$salida.= ObtenerPaginado($pagina,GetThemePath(),$conteo,1,$proveedor,$orden,$limite);
		
		return $salida;
	}
	
	function ObtenerPaginado($pagina,$path,$slc,$op,$proveedor,$orden,$limite)
	{
		$TotalRegistros = $slc;
		$TablaPaginado = "";
			
		if($limite == null)
		{
			$uid = UserGetUID();
				$LimitRow = intval(GetLimitBrowser());
		}
		else
		{
			$LimitRow = $limite;
		}
		if ($TotalRegistros > 0)
		{
			$columnas = 1;
			$NumeroPaginas = intval($TotalRegistros/$LimitRow);
			
				if($TotalRegistros%$LimitRow > 0)
			{
				$NumeroPaginas++;
			}
					
			$Inicio = $pagina;
			if($NumeroPaginas - $pagina < 9 )
			{
				$Inicio = $NumeroPaginas - 9;
			}
			elseif($pagina > 1)
			{
				$Inicio = $pagina - 1;
			}
			
			if($Inicio <= 0)
			{
				$Inicio = 1;
			}
				
			$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 
	
			$TablaPaginado .= "<tr>\n";
			if($NumeroPaginas > 1)
			{
				$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
				if($pagina > 1)
				{
					$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
																																															//     na,criterio1,criterio2,criterio,div,forma
					$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusquedaOrden('1','".$proveedor."','".$orden."')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusquedaOrden('".($pagina-1)."','".$proveedor."','".$orden."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "   </td>\n";
					$columnas +=2;
				}
				$Fin = $NumeroPaginas + 1;
				if($NumeroPaginas > 10)
				{
					$Fin = 10 + $Inicio;
				}
					
				for($i=$Inicio; $i< $Fin ; $i++)
				{
					if ($i == $pagina )
					{
						$TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
					}
					else
					{
						$TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BusquedaOrden('".$i."','".$proveedor."','".$orden."')\">".$i."</a></td>\n";
					}
					$columnas++;
				}
			}
			if($pagina <  $NumeroPaginas )
			{
				$TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
				$TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusquedaOrden('".($pagina+1)."','".$proveedor."','".$orden."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
				$TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
				$TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BusquedaOrden('".$NumeroPaginas."','".$proveedor."','".$orden."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
				$TablaPaginado .= "   </td>\n";
				$columnas +=2;
			}
			$aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
			$aviso .= "     Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
			$aviso .= "   </tr>\n";
			
			if($op == 2)
			{
				$TablaPaginado .= $aviso;
			}
			else
			{
				$TablaPaginado = $aviso.$TablaPaginado;
			}
		}
		$Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
		$Tabla .= $TablaPaginado;
		$Tabla .= "</table>";
		
	
		return $Tabla;
	}
?>