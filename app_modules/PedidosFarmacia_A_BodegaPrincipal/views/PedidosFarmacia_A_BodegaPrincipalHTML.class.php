<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: PedidosFarmacia_A_BodegaPrincipalHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");

	class PedidosFarmacia_A_BodegaPrincipalHTML
	{
	/**
		* Constructor de la clase
	*/

	function  PedidosFarmacia_A_BodegaPrincipalHTML()
	{}
	/*
		* Funcion donde se crea la forma para el menu de Consulta de Documentos o Parametrizacion
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenu($action,$farmacia,$bodega,$aux)
		{
		    $farmacia_id=$farmacia['empresa_id'];
			$centro=$farmacia['centro_utilidad'];
	
			$html  = ThemeAbrirTabla('PEDIDOS');
			
			$html .= "<table width=\"40%\"  class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_list_claro\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\"  align=\"center\">\n";
		    $html .= "     FARMACIA : ".$farmacia['descripcion1']."-".$farmacia['descripcion2'].": ".$farmacia['descripcion3']." \n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			
			$html .= "</table><br>\n";
			
			
			
			$html .="<script >\n";
			$html .= "  function validarinfo(frms)\n";
			$html .= "  {\n";
			$html .= "    if(frms.empresa.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA EMPRESA';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.centro.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL CENTRO DE UTILIDAD';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.bodega.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA BODEGA ';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    xajax_ValidarInformacion_EmpresaDestino(frms.empresa.value,frms.centro.value,frms.bodega.value,'".$farmacia_id."','".$centro."','".$bodega."');\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			$html .= "<table width=\"40%\"  class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
			$html .= "  <tr  >\n";
			$html .= "     <td  class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU PRINCIPAL</td>\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			if(empty($aux))
			{	
				$html .= "         <a href=\"#\" onclick=\"xajax_EmpresaDestino_()\"  class=\"label_error\">SOLICITUD DE PRODUCTOS A BODEGA PRINCIPAL</a>\n";
            }else
			{
			    $html .= "        <a href=\"".$action['SolPB']."\">SOLICITUD DE PRODUCTOS A BODEGA PRINCIPAL</a>\n";
			}
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td  class=\"label\"  align=\"center\">\n";
		    $html .= "        <a href=\"".$action['consulatadoc']."\">CONSULTAR DOCUMENTOS DE PEDIDOS</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			
			$html .= "</table>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(400,"EMPRESA DESTINO");
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/*
		*  Funcion donde se crea la forma para el menude los tipos de productos que existen
        * @param array $action vector que contiene los link de la aplicacion
		* @param String $tipo  variable que contiene valor del tipo de producto
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaMenu2($action,$tipo,$farmacia,$centro,$bod,$aux)
		{
		$emp = SessionGetVar("DatosEmpresaAFS");
		
		    $html  ="  <script>\n";
			$html .= "	function TransD(frms){";
			$html .= "   if(frms.empresa_destino.value==\"-1\"){ ";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR LA EMPRESA DESTINO  ';\n";
			$html .= "      return;\n";
			$html .= "    }else\n";
			$html .= "   { ";
			$html .= "	 if(frms.tipo_pedido.checked == true){" ;
			$html .= "	   frms.tipo_pedido.value = 1;";
			$html .= "   }";
			$html .= "   else { ";
			$html .= "	   frms.tipo_pedido.value = 0;";
			$html .= "   }";
			$html .= " 	xajax_TransVariablesP(frms.observar.value,frms.empresa_destino.value,frms.tipo_pedido.value);";
			$html .= "   submit();\n";
			$html .= "   }";
			$html .= "	}";
			$html .="  </script>\n";
			$html  .= ThemeAbrirTabla('PEDIDOS');
			
			$html .= "<table width=\"40%\"  class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_list_claro\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\"  align=\"center\">\n";
			$html .= "     FARMACIA : ".$emp['descripcion1']."-".$emp['descripcion2']." - ".$emp['descripcion3']."   \n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";

			$html .= "</table><br>\n";
			
			
			
			$html .= "<table width=\"40%\" class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
            $html .= "  <tr>\n";
			$html .= "     <td width=\"30%\"  align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">EMPRESA DESTINO\n";
			$html .= "      <td  width=\"50%\"   class=\"modulo_list_oscuro\" class=\"label\"  align=\"LEFT\"><b>".$aux[0]['razon_social']."</b>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr>\n";
			$html .= "     <td width=\"30%\"  align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">CENTRO UTILIDAD\n";
			$html .= "      <td  width=\"50%\"   class=\"modulo_list_oscuro\" class=\"label\"  align=\"LEFT\"><b>".$aux[0]['centro']."</b>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr>\n";
			$html .= "     <td width=\"30%\"  align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">BODEGA DESTINO\n";
			$html .= "      <td  width=\"50%\"   class=\"modulo_list_oscuro\" class=\"label\"  align=\"LEFT\"><b>".$aux[0]['bodega']."</b>\n";
			$html .= "  </tr>\n";
		
    		$html .= "</table>\n";
			$html .= " <br> <table width=\"40%\" class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "     <td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$i=0;
			foreach($tipo as $key => $dtl)
				{
					$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
					$html .= "      <td  class=\"label\"  align=\"center\">\n";
					$html .= "       <a href=\"".$action['SolPB'].URLRequest(array( "tipo_producto_id"=>$dtl['tipo_producto_id']))."\">\n";
					$html .= "        PEDIDO POR MEDICAMENTOS  (".$dtl['descripcion'].")</a>\n";
					$html .= "        <input type=\"hidden\" name=\"tipoPr".$i."\" id=\"tipoPr".$i."\" value=\"".$dtl['tipo_producto_id']."\">";
					$html .= "      </td>\n";
						$html .= "  </tr>\n";
				
				
				 $i++;
				}
				$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
				$html .= "      <td align=\"center\">\n";
				$html .= "         <a href=\"#\" onclick=\"xajax_ValidarSelec('".$farmacia."','".$centro."','".$bod."')\" class=\"label_error\">GENERAR DOCUMENTO DE PEDIDO</a>\n";
				$html .= "      </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(780,"MENSAJE");
			$html .= ThemeCerrarTabla();
		    return $html;
		}
		
	/*
		* Funcion donde se crea la forma para el menude los tipos de productos que existen
        * @param array $action vector que contiene los link de la aplicacion.
		* @param array $Pactivo vector que contiene la informacion del principio activo.
		* @param array $PTera vector que contiene la informacion del perfil terapeutico.
		* @param array $datos vector que contiene la informacion de los productos que tiene la bodega de la farmacia.
		* @param String $empresa  variable que contiene id de la farmacia.
		* @param String $Centrid  variable que contiene id del centro de utilidad de la farmacia seleccionada.
		* @param String $bod  variable que contiene id de la bodega de la farmacia.
		* @param String $tipo_producto_id  variable que contiene id del tipo de producto.
		* @param array $ConTmp vector que contiene la informacion de los documentos seleccionados para generar el documento de pedido.
	    * @return string $html retorna la cadena con el codigo html de la pagina
	*/

		function FormaBuscarHacerPedido($action,$Pactivo,$PTera,$datos,$conteo,$pagina,$request,$empresa,$Centrid,$bod,$tipo_producto_id,$ConTmp,$empresa_destino,$centro_destino,$bogega_destino)
        {
			$ctl   = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->LimpiarCampos();
			$emp = SessionGetVar("DatosEmpresaAFS");
			$html  ="  <script>\n";
			$html .= "	function TransFerirDatos(frms){";
			$html .= "	 if(frms.tipo_pedido.checked == true){" ;
			$html .= "	   frms.tipo_pedido.value = 1;";
			$html .= "   }";
			$html .= "   else { ";
			$html .= "	   frms.tipo_pedido.value = 0;";
			$html .= "   }";
			$html .= " 	xajax_TransVariablesP(frms.observar.value,'".$tipo_producto_id."',frms.tipo_pedido.value);";
			$html .= "   submit();\n";
			$html .= "	}";
			$html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .="  </script>\n";
			$html  .= ThemeAbrirTabla('PEDIDOS DE FARMACIA');
			$html .= "<table width=\"40%\"  class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_list_claro\" >\n";
			$html .= "      <td  class=\"formulacion_table_list\"  align=\"center\">\n";
			$html .= "     FARMACIA : ".$emp['descripcion1']."-".$emp['descripcion2']."-".$emp['descripcion3']."   \n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";

			$html .= "</table><br>\n";

			$html .= "<fieldset style=\"width:50%\" class=\"fieldset\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"LEFT\">CONVENCIONES...</legend>\n";			
			$html .= "		<table  width=\"100%\"   align=\"LEFT\">\n";
			$html .= "  		<tr>\n";
			$html .= " 				<td class=\"label\" width=\"70%\" ><b>EL PRODUCTO NO SE ENCUENTRA EN EL INVENTARIO DE LA FARMACIA </b></td>\n";
			$html .= "  			<td width=\"30%\" style=\"background:#5FB404;width:100%\" > </td> ";
			$html .= "  		</tr>\n";
			$html .= "		</table>\n";
			$html .= "</fieldset>";
			$html .= "<br>";
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "  <table width=\"100%\" class=\"modulo_table_list\"   align=\"center\">";
				$html .= "		<tr>";
				$html .= "			<td colspan=\"7\" align=\"center\">";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				$html .= "			</td>";
				$html .= "		</tr>";
				$html .= "	  <tr  class=\"formulacion_table_list\" align=\"CENTER\" >\n";
				$html .= "      <td width=\"20%\">CODIGO</td>\n";
				$html .= "      <td width=\"55%\">PRODUCTO</td>\n";
				$html .= "      <td  width=\"5%\" >FARMACIA</td>\n";
				$html .= "      <td  width=\"5%\" >BODEGA</td>\n";
				$html .= "      <td  width=\"5%\" >DISP.BODEGA</td>\n";
				$html .= "      <td  width=\"5%\" >CANTIDAD</td>\n";
				$html .= "	      <td width=\"3%\">";
				$html .= "      OP  </td>\n";
				$html .= "  </tr>\n";
        
				$mgl = AutoCarga::factory('PedidosFarmacia_A_BodegaPrincipalSQL', '', 'app', 'PedidosFarmacia_A_BodegaPrincipal');
				$pendientes = $mgl->ObtenerSolicitudes($empresa_destino);
				$despachos = $mgl->ObtenerSolicitudesDespachadas($empresa_destino);
				$clientes = $mgl->BuscarTotalPedidosEmpresa($empresa_destino);

				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
					$checked = "";
					if(!empty($ConTmp[$dtl['codigo_producto']]))
					$checked = "checked";
					$html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";  	
        	        $html .= "      <td   align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "        <input type=\"hidden\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
					$html .= "      <td  align=\"left\"><B>".$dtl['producto']."</b></td>\n";
            
					$exis = $mgl->ConsultarExistencias_Actuales($empresa,$Centrid,$bod,$dtl['codigo_producto']);
					if(!empty($exis))
					{
					    $color= "  ";
						$existenciaFarmacia=round($exis[0]['existencia']);
						  $colorban=0;
					}else
					{
					    $color= " style=\"background:#5FB404;width:100%\" ";
					    $existenciaFarmacia="  ";
						$colorban=1;
					}
					
					if($dtl['existencia']==0)
					{
					 
							$existenciabodega=0;
					      
					}else
					{
					    	//$disabled="  ";
					        $existenciabodega= round($dtl['existencia']);
					}
					//$solicitudes=$mgl->ConsultarId_solicitudPendientes($empresa_destino);
				  $cantidad_p=$pendientes[$dtl['codigo_producto']]['cantidad'];
						
				    $solicitudes_1=$mgl->ConsultarId_solicitudPendientes_1($empresa_destino);
						$cantidad_pdes=0;
					
						$cantidad_pdes = $despachos[$dtl['codigo_producto']]['cantidad_pendiente'];
						$total=$clientes[$dtl['codigo_producto']]['total'];
						  
						$cantidad_total=$cantidad_pdes+$cantidad_p+$total;
						$dispon=$dtl['existencia']-$cantidad_total;
						  if($dispon<0)
							{
							  $total_dispon=0;
							  
							}else
							{
								$total_dispon=$dispon;
							
							}
						
						
						if($total_dispon  > 0  and  $colorban==0 )
						{
						  $disabled=" ";
						}else
						{
						$disabled=" disabled=true ";
						}

					$html .="        <td  aling=\"left\">";
				    $html .="  <input $color readonly=true;  class=\"input-text\"  type=\"text\" name=\"txtcantidad\"    value=\"".$existenciaFarmacia."\"   style=\"width:100%\" size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" ></td> ";
					$html .="        <td  aling=\"left\">";
				    $html .="  <input  readonly=true;  class=\"input-text\"  type=\"text\" name=\"txtexistenciasduana\"    value=\"".round($dtl['existencia'])."\"   style=\"width:100%\" size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" ></td> ";
										
					$html .="        <td  aling=\"left\">";
				    $html .="  <input  readonly=true;  class=\"input-text\"  type=\"text\" name=\"txtexistenciasduana\"    value=\"".$total_dispon."\"   style=\"width:100%\" size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" ></td> ";
				    $html .= "<input type=\"hidden\" name=\"disponible".$dtl['codigo_producto']."\" value=\"".$total_dispon."\" id=\"disponible".$dtl['codigo_producto']."\" > ";
					$html .="        <td  aling=\"left\">";
					$html .="  <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtcantidad".$dtl['codigo_producto']."\"  id=\"txtcantidad".$dtl['codigo_producto']."\"  ".$disabled."  value=\"\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" ></td> ";
					$html .=" <td> <input type=\"checkbox\" name=\"checkseleccionar\" id=\"checkseleccionar".$dtl['codigo_producto']."\"  ".$disabled." value=\"".$dtl['codigo_producto']."\" ".$checked." onClick=\"xajax_ValidarDatosProducto(this.value,'".$empresa."','".$Centrid."','".$bod."','".$tipo_producto_id."','".$dtl['codigo_producto']."');\" > ";       
					if($dtl['sw_requiereautorizacion_despachospedidos']=='1')
					$html .= " <img title=\"EL PRODUCTO REQUIERE AUTORIZACION PARA SER DESPACHADO\" src=\"".GetThemePath()."/images/alarma.gif\" border='0' >	";
					$html .= " </td>\n";
					$html .= "  </tr>\n";
				}
				$html .= "	</table>\n";
                $html .= "  <table width=\"15%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
				$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"CONTINUAR\"  onClick=\"xajax_ValidarSel('".$empresa."','".$Centrid."','".$bod."');\" >\n";
				$html .= "		          	</td>\n";
				$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
				$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"CANCELAR\"  onClick=\"xajax_CancelarCreacion('".$tipo_producto_id."','".$empresa."','".$Centrid."','".$bod."');\" >\n";
				$html .= "		          	</td>\n";
				$html .= "		<tr>\n";
				$html .= "	</table><br>\n";
        		
			

			}
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			
			/* ******************************
			* BUSCADOR
			****************************** */				
			$html .= "<br>";
			$html .= "<center>\n";
			$html .= "<fieldset width=\"95%\" class=\"fieldset\" style=\"width:95%\">\n";
			$html .= "  <legend class=\"normal_10AN\" align=\"LEFT\">SOLICITUDES DESDE LA FARMACIA</legend>\n";
			$html .= "<form name=\"Forma22\" id=\"Forma22\" method=\"post\"  action=\"".$action['buscador']."\">\n";
			$html .= "			<table   width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table_list\"    >";
			$html .= "   <tr   align=\"LEFT\"  class=\"formulacion_table_list\"> \n";
			$html .= "			<td  align=\"LEFT\" >  PERFIL TERAPEUTICO:</td>\n";
			$html .= "			<td     class=\"modulo_list_oscuro\" >\n";
			$html .= "					<select name=\"buscador[cod_anatomofarmacologico]\" class=\"select\">\n";
			$html .= "             	<option value = ''>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($PTera as $indice => $v)
			{
				if($v['cod_anatomofarmacologico']==$request['cod_anatomofarmacologico'])
				$sel = "selected";
				else   $sel = "";
				$html .= "  <option value=\"".$v['cod_anatomofarmacologico']."\" ".$sel.">".$v['descripcion']."</option>\n";
			}
			$html .= "                </select>\n";
			$html .= "						  </td>\n";
			$html .= "	 </tr>\n";
			$html .= "   <tr   align=\"LEFT\"  class=\"formulacion_table_list\"> \n";
			$html .= "			<td  width=\"30%\" align=\"LEFT\"  >PRINCIPIO ACTIVO :</td>\n";
			$html .= "		           <td  colspan=\"5\" class=\"modulo_list_oscuro\" align=\"left\"><input type=\"text\"   class=\"input-text\" name=\"buscador[molecula_id]\" maxlength=\"250\" size=\"75\" value=".$request['molecula_id']."></td>\n";
  		
			$html .= "	 </tr>\n";
			$html .= "         <tr class=\"formulacion_table_list\">\n";
			$html .= "		          	<td  width=\"40%\"  align=\"left\" >CODIGO:</td>\n";
			$html .= "	                <td class=\"modulo_list_oscuro\" colspan=\"5\" align=\"left\">\n";
			$html .= "                        <input type=\"text\" class=\"input-text\" name=\"buscador[codigo_producto]\" maxlength=\"70\" size=\"75\" value=".$request['codigo_producto']."></td>\n";
			$html .= "	 </tr>\n";
			$html .= "         <tr class=\"formulacion_table_list\">\n";
			$html .= "		           	<td width=\"40%\"  align=\"left\">DESCRIPCION:</td>\n";
			$html .= "		           <td  colspan=\"5\" class=\"modulo_list_oscuro\" align=\"left\"><input type=\"text\"   class=\"input-text\" name=\"buscador[descripcion]\" maxlength=\"100\" size=\"75\" value=".$request['descripcion']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "</table>\n";
			$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "  <tr>\n";
			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR PRODUCTO\"  >\n";
			$html .= "		          	</td>\n";
            $html .= "			<td  colspan=\"10\" align='center' >\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma22)\" value=\"LIMPIAR CAMPOS\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table>\n";
			$html .= "</fieldset><br>\n";
			$html .= "</center>\n";
			/* ******************************
			* FIN BUSCADOR
			****************************** */	
			
			$html .= "<table  width=\"100%\"   align=\"center\">\n";
			$html .= "  <tr>\n";
			$html .= "      <td colspan=\"15\"><div id=\"productos\"></div></td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "<br> ";
			$html .= "      <script>\n";
			$html .= "        xajax_ProductosSeleccionados('".$empresa."','".$Centrid."','".$bod."','".$tipo_producto_id."');\n";
			$html .= "      </script>\n";
			
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "  </form>\n";
			$html .= $this->CrearVentana(450,"MENSAJE");
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/*
		* Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
		* en pantalla
		* @param int $tmn Tamaño que tendra la ventana
		* @return string
    */
		function CrearVentana($tmn,$Titulo)
		{
			$html .= "<script>\n";
			$html .= "  var contenedor = 'Contenedor';\n";
			$html .= "  var titulo = 'titulo';\n";
			$html .= "  var hiZ = 4;\n";
			$html .= "  function OcultarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"none\";\n";
			$html .= "    }\n";
			$html .= "    catch(error){}\n";
			$html .= "  }\n";
			$html .= "  function MostrarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"\";\n";
			$html .= "      Iniciar();\n";
			$html .= "    }\n";
			$html .= "    catch(error){alert(error)}\n";
			$html .= "  }\n";
			$html .= "  function MostrarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xShow(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function OcultarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xHide(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function Iniciar()\n";
			$html .= "  {\n";
			$html .= "    contenedor = 'Contenedor';\n";
			$html .= "    titulo = 'titulo';\n";
			$html .= "    ele = xGetElementById('Contenido');\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    ele = xGetElementById(contenedor);\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "    ele = xGetElementById(titulo);\n";
			$html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "    xMoveTo(ele, 0, 0);\n";
			$html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "    ele = xGetElementById('cerrar');\n";
			$html .= "    xResizeTo(ele,20, 20);\n";
			$html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "  }\n";
			$html .= "  function myOnDragStart(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "    window.status = '';\n";
			$html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "    else xZIndex(ele, hiZ++);\n";
			$html .= "    ele.myTotalMX = 0;\n";
			$html .= "    ele.myTotalMY = 0;\n";
			$html .= "  }\n";
			$html .= "  function myOnDrag(ele, mdx, mdy)\n";
			$html .= "  {\n";
			$html .= "    if (ele.id == titulo) {\n";
			$html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "    }\n";
			$html .= "    else {\n";
			$html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "    }  \n";
			$html .= "    ele.myTotalMX += mdx;\n";
			$html .= "    ele.myTotalMY += mdy;\n";
			$html .= "  }\n";
			$html .= "  function myOnDragEnd(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "  }\n";
			$html.= "function Cerrar(Elemento)\n";
			$html.= "{\n";
			$html.= "    capita = xGetElementById(Elemento);\n";
			$html.= "    capita.style.display = \"none\";\n";
			$html.= "}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido2' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
            return $html;
        }    
		
	/*
		* Funcion donde se crea el bloqueo al usuario mientras un documento de pedido este en proceso.
        * @param array $action vector que contiene los link de la aplicacion.
		* @return string $html retorna la cadena con el codigo html de la pagina
	*/

		function FormaBloqueo($action)
		{
			$html  = ThemeAbrirTabla("PRODUCTOS DEL DOCUMENTO DE DESPACHO PENDIENTES POR VERIFICAR");
			$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
			$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "     EXISTE UN DOCUMENTO DE SOLICITUD  EN PROCESO  ";
			$html .= "      </td>\n";

			$html .= "	</table>\n";
			$html .= "  </form>\n";

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
	/*
		* Funcion donde se crea la forma para mostrar numero de documento de pedido  generado.
        * @param array $action vector que contiene los link de la aplicacion.
		* @param String $solici_prod_a_bod_ppal_id  variable que contiene id del documento de pedido generado.
		* @return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaMostrarDocumentoGenerado($action,$solici_prod_a_bod_ppal_id,$empresa,$bodega,$centro_utilidad)
		{
				$html  = ThemeAbrirTabla("PRODUCTOS DEL DOCUMENTO DE DESPACHO PENDIENTES POR VERIFICAR");
				$reporte = new GetReports();
				       
        
				$mostrar = $reporte->GetJavaReport('app','PedidosFarmacia_A_BodegaPrincipal','Pedido',
																							array("solicitud_prod_a_bod_ppal_id"=>$solici_prod_a_bod_ppal_id,"empresa_id"=>$empresa,"bodega"=>$bodega,"centroU"=>$centro_utilidad),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion = $reporte->GetJavaFunction();
        
				$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
				$html .= "  <table width=\"70%\"  border=\"0\" class=\"modulo_table_list\" align=\"center\">";
				$html .= "      <tr class=\"formulacion_table_list\"> \n";
				$html .= "      <td align=\"center\" >\n";
				$html .= "     EL DOCUMENTO DE PEDIDO QUE SE GENERO ES EL NUMERO: ".$solici_prod_a_bod_ppal_id."   ";
				$html .= "      </td>\n";
				$html .= "	    </tr>\n";
        
				$html .= "      <tr class=\"formulacion_table_list\"> \n";
				$html .= "				<td align=\"center\"  class=\"modulo_list_claro\">\n";
				$html .= "				".$mostrar."\n";
				$html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
				$html .= "					[ IMPRIMIR PEDIDO]</a></center>\n";
				$html .= "			</td>\n";	
				$html .= "	</table>\n";
				$html .= "<table align=\"center\">\n";
				$html .= "<br>";
				$html .= "  <tr>\n";
				$html .= "      <td align=\"center\" class=\"label_error\">\n";
				$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
				$html .= "      </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= "  </form>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
	/*
		* Funcion donde se crea la forma para Buscar los documentos de pedido generado
        * @param array $action vector que contiene los link de la aplicacion.
		* @param array $datos vector que contiene la informacion general del documento de pedido generado.
		* @param String $bodegades  variable que contiene la descripcion de la bodega seleccionada.
		* @return string $html retorna la cadena con el codigo html de la pagina
	*/
	
		function FormaBuscarDocumento($action,$datos,$conteo,$pagina,$request,$emp,$bod)
		{
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->LimpiarCampos();
			$bodegades = SessionGetVar("bodegaDesc");
			$html  ="  <script>\n";
			$html .= "	  function LimpiarCampos(frm)\n";
			$html .= "	  {\n";
			$html .= "		  for(i=0; i<frm.length; i++)\n";
			$html .= "		  {\n";
			$html .= "			  switch(frm[i].type)\n";
			$html .= "			  {\n";
			$html .= "				  case 'text': frm[i].value = ''; break;\n";
			$html .= "				  case 'select-one': frm[i].selectedIndex = 0; break;\n";
			$html .= "			  }\n";
			$html .= "		  }\n";
			$html .= "	  }\n";
			$html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
            $html .="  </script>\n";
			$html  .= ThemeAbrirTabla('CONSULTAR DOCUMENTOS');
			$html .= "<form name=\"FormaConsultar\" id=\"FormaConsultar\" action=\"".$action['buscador']."\"  method=\"post\" >\n";
			$html .= "<table  width=\"45%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "		<td width=\"30%\" align=\"left\" >FECHA INICIO:</td>\n";
			$html .= "		<td width=\"15%\" class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "		  <input type=\"text\" class=\"input-text\" name=\"buscador[fecha_inicio]\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
			$html .= "		</td>\n";
			$html .= "    <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormaConsultar','fecha_inicio','-')."\n";
			$html .= "		</td>\n";
			$html .= "  </tr >\n";
			$html .= "  <tr class=\"formulacion_table_list\">\n";
			$html .= "		<td align=\"left\" >FECHA FINAL:</td>\n";
			$html .= "		<td class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "		    <input type=\"text\" class=\"input-text\"  name=\"buscador[fecha_final]\"  id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" READONLY value=\"\" \n";
			$html .= "		</td>\n";
			$html .= "    <td  class=\"modulo_list_claro\" >\n";
			$html .= "				".ReturnOpenCalendario('FormaConsultar','fecha_final','-')."\n";
			$html .= "		</td>\n";
			$html .= "  </tr >\n";
			$html .= "  <tr  colspan=\"5\" class=\"formulacion_table_list\" >\n";
			$html .= "      <td align=\"left\">NUMERO DOCUMENTO DE PEDIDO:</td>\n";
			$html .= "      <td colspan=\"5\" align=\"left\"  class=\"modulo_list_claro\">  <input class=\"input-text\" type=\"text\"  name=\"buscador[pedido]\"  id=\"txtncontrato\"   value=\"\" size=\"30%\" maxlength=\"30\" >\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "  <tr>\n";
			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
			$html .= "		          	</td>\n";
			$html .= "			<td  colspan=\"10\" align='center' >\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.FormaConsultar)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";
							
			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "  <table width=\"75%\"   class=\"modulo_table_list\"  align=\"center\">";
						
				$html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\" >\n";
				$html .= "      <td    width=\"15%\"><b>NUMERO DOC.</b></td>\n";
				$html .= "      <td   width=\"30%\"><b>OBSERVACION</b></td>\n";
				$html .= "      <td  width=\"30%\"><b>USUARIO</b></td>\n";
				$html .= "      <td   width=\"15%\"><b>FECHA REGISTRO</b></td>\n";
				$html .= "	      <td   width=\"2%\">";
				$html .= "      <b>OP </b></td>\n";
				$html .= "  </tr>\n";
							
                $est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
	 				$html .= "	  <tr  align=\"CENTER\" class=\"".$est."\" >\n";
     				$html .= "      <td   align=\"center\"><B>".$dtl['solicitud_prod_a_bod_ppal_id']."</B></td>\n";
					$html .= "        <input type=\"hidden\" name=\"solicitud_prod_a_bod_ppal_id".$i."\" id=\"solicitud_prod_a_bod_ppal_id".$i."\" value=\"".$dtl['codigo_producto']."\">";
					$html .= "      <td align=\"left\"><B>".$dtl['observacion']."</B></td>\n";
					$html .= "      <td align=\"left\"><B>".$dtl['nombre']."</B></td>\n";
					$html .= "      <td align=\"left\"><B>".$dtl['fecha_registro']."</B></td>\n";
					$html .= "      <td align=\"center\">\n";
					$html .= "      <a href=\"".$action['s_id'].URLRequest(array("solicitud_prod_a_bod_ppal_id"=>$dtl['solicitud_prod_a_bod_ppal_id'],"bodega"=>$bod))."\">\n";
					$html .= "      <img src=\"".GetThemePath()."/images/infor.png\" border='0' >\n";
					$html .= "    </a>\n";
					$html .= "			</td>\n";
					
					$html .= "			</tr>\n";
											
				}
				$html .= "	</table><br>\n";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "</form>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodegades"=>$bodegades))."\"  class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/*
		* Funcion donde se crea la forma para Buscar los documentos de pedido generado
        * @param array $action vector que contiene los link de la aplicacion.
		* @param array $datos vector que contiene la informacion detallada  del documento de pedido generado.
		* @return string $html retorna la cadena con el codigo html de la pagina
	*/
	
		function FormaDetalleDocumentoPedido($action,$datos,$conteo,$pagina,$solicitud_prod_a_bod_ppal_id,$empresa,$Centrid,$bod)
		{
			$html  .= ThemeAbrirTabla('DETALLE DEL DOCUMENTO DE PEDIDO');
			$html .="  <script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";
			$html .= "	function validardatos(actual,modi,solic,producto)\n";
			$html .= "	{\n";
			$html .= "		if((actual < modi)==true){\n";
			$html .= "           document.getElementById('error').innerHTML = 'LA CANTIDAD A MODIFICAR NO DEBE SER SUPERIOR A LA ACTUAL';\n";
			$html .= "      return;\n";
			$html .= "        } else {" ;
			$html .= " 	xajax_TransVariables(solic,producto,modi,'".$bod."');";
		    $html .= "      return;\n";
		    $html .= "	}\n";
		    $html .= "	}\n";
			$html .="  </script>\n";
			$html .= "<form name=\"FormaDetalle\" id=\"FormaDetalle\"  method=\"post\" >\n";

			if(!empty($datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				
				$reporte = new GetReports();
				
				$mostrar = $reporte->GetJavaReport('app','PedidosFarmacia_A_BodegaPrincipal','Pedido',
																							array("solicitud_prod_a_bod_ppal_id"=>$solicitud_prod_a_bod_ppal_id,"empresa_id"=>$empresa,"bodega"=>$bod,"centroU"=>$Centrid),
																							array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion = $reporte->GetJavaFunction();
		
				
				
				$html .= "  <table width=\"80%\"   class=\"modulo_table_list\"  align=\"center\">";
				$html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\" >\n";
				//$html .= "      <td width=\"20%\"><b>PRINCIPIO ACTIVO</b></td>\n";
				//$html .= "      <td width=\"10%\"><b>LOCALIZACION</b></td>\n";
				$html .= "      <td width=\"15%\"><b>CODIGO</b></td>\n";
				$html .= "      <td width=\"60%\"><b>DESCRIPCION</b></td>\n";
				$html .= "      <td width=\"10%\"><b>CANTIDA</b></td>\n";
				$html .= "      <td width=\"20%\"><b>TIPO PRODUCTO</B></td>\n";
				$html .= "      <td width=\"5%\"><b>OP</B></td>\n";
				$html .= "  </tr>\n";

				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
					$html .= "	  <tr  align=\"CENTER\"    class=\"modulo_list_claro\" >\n";  					
					//$html .= "      <td  align=\"left\">".$dtl['molecula']."</td>\n";
					//$html .= "      <td align=\"left\">".$dtl['localiza']."</td>\n";
					$html .= "      <td align=\"left\"><B>".$dtl['codigo_producto']."</B></td>\n";
					$html .= "      <td align=\"left\"><B>".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</B></td>\n";
					$html .= "      <td align=\"left\"><B>".number_format($dtl['cantidad_solic'])."</B></td>\n";
					$html .= "      <td align=\"left\"><B>".$dtl['tipo']."</B></td>\n";
          $html .= "      <td align=\"center\">\n";
					$html .= "      <a href=\"#\" onclick=\"xajax_CambiarCantidad('".$dtl['solicitud_prod_a_bod_ppal_id']."','".$dtl['cantidad_solic']."','".$dtl['codigo_producto']."')\">\n";
					$html .= "      <img title=\"Modificar Cantidad\" src=\"".GetThemePath()."/images/modificar.png\" border='0' >\n";
					$html .= "    </a>\n";
					$html .= "			</td>\n";
					$html .= "  </tr>\n";
				}
				$html .= "	</table><br>\n";
				//$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
			else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			
			$html .= "</form>";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "				<td align=\"center\" >\n";
			$html .= "				".$mostrar."\n";
			$html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"REPORTE DEL PEDIDO\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
			$html .= "					[ IMPRIMIR PEDIDO]</a></center>\n";
			$html .= "			</td>\n";	
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= " <br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver'].URLRequest(array( "bodega"=>$bod))."\"  class=\"label_error\">\n";
			$html .= "       VOLVER \n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(400,"MODIFICAR CANTIDAD");
			$html .= ThemeCerrarTabla();
			return $html;
		}
	
	}
?>