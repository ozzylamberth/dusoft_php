<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: InfoProductosDisponiblesPplHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
	IncludeClass("ClaseUtil");

	class InfoProductosDisponiblesPplHTML
	{
	/**
		* Constructor de la clase
	*/

	function  InfoProductosDisponiblesPplHTML()
	{}
	/*
		  * Funcion donde se crea la forma para el MENU principal
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenu($action)
		{
			$html  = ThemeAbrirTabla('INFORMACION ');
			$html .= "		<form name=\"formabuscarE\"  method=\"post\">";
			$html .= "<table width=\"40%\"  class=\"modulo_table_list\"  border=\"0\" align=\"center\">\n";
			$html .= "  <tr class=\"modulo_table_list_title\" >\n";
			$html .= "     <td   align=\"center\"><B>MENU</B>\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"modulo_list_oscuro\">\n";
			$html .= "      <td   class=\"label\" align=\"center\">\n";
			$html .= "         <a href=\"".$action['continuar']."\" class=\"label_error\">PRODUCTOS DISPONIBLES/PENDIENTES</a>\n";
        	$html .= "      </td>\n";
			$html .= "  </tr>\n";
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
		  * Funcion donde se crea la forma para Buscar la empresa principal
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaEmpresa($action,$rst,$request)
		{
			
			$html  = ThemeAbrirTabla('INFORMACION ');
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
			$html .= "    xajax_InformacionFinal(frms.empresa.value,frms.centro.value,frms.bodega.value);\n";
			$html .= "    }\n";   
			$html .="</script>\n";
			$html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['continuarinf']."\"method=\"post\"     >";
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .= "			<table   width=\"55%\" align=\"center\" border=\"0\"   >";
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
			
      $html .= "			<table   width=\"70%\" align=\"center\" border=\"0\"   >";
      $html .= "  <tr >\n";
      $html .= "      <td colspan=\"12\"><div id=\"continuar\"></div></td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
			
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "		   </form>\n";
			$html .= ThemeCerrarTabla();
		     return $html;
		}
		/*/*
		  * Funcion donde se crea la forma para Buscar el producto
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaProducto($action,$request,$datos,$conteo,$pagina,$empresa,$centro,$bodega)
		{
			  
			
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->RollOverFilas();
			$html  .= ThemeAbrirTabla('BUSCAR PRODUCTO ');
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .= " <script>\n";	 
			$html .= " function  validarDatos(frms){" ;
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
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
			$html .="  </script>\n";
			$html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['buscador']."\" method=\"post\"     >";
			$html .= "			<table   width=\"55%\" align=\"center\" border=\"0\"   >";
			$html .= "         <tr class=\"modulo_table_list_title\">\n";
			$html .= "		          	<td  width=\"40%\" class=\"modulo_table_list_title\">CODIGO:</td>\n";
			$html .= "	               <td class=\"modulo_list_claro\" align=\"left\" >\n";
			$html .= "                        <input type=\"text\" class=\"input-text\" name=\"buscador[codigo_producto]\" maxlength=\"60\" size=\"40\" value=".$request['codigo_producto']."></td>\n";
			$html .= "	 </tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "		           	<td class=\"modulo_table_list_title\"  align=\"left\" >DESCRIPCION:</td>\n";
			$html .= "		           <td  align=\"left\"  class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[descripcion]\" id=\"descripcion\"  maxlength=\"250\" size=\"40\"  value=".$request['descripcion']."></td>\n";
			
			$html .=" <input type=\"hidden\" name=\"buscador[empresa]\" value=\"".$empresa."\"> ";
			$html .=" <input type=\"hidden\" name=\"buscador[centro]\" value=\"".$centro."\"> ";
			$html .=" <input type=\"hidden\" name=\"buscador[bodega]\" value=\"".$bodega."\"> ";
			$html .= "		</tr>\n";
      $html .= "</table>\n";
      $html .= "			<table   width=\"20%\" align=\"center\" border=\"0\"   >";

			$html .= "		<tr>\n";
			$html .= "	             	<td   align='center'>\n";
			$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"BUSCAR\"   onclick=\"validarDatos(document.formita)\">\n";
			$html .= "		          	</td>\n";
			$html .= "			<td   align='center' >\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.formita)\" value=\"LIMPIAR CAMPOS\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";
			$sel = AutoCarga::factory("InfoProductosDisponiblesPplSQL", "", "app", "InfoProductosDisponiblesPpl");

			if(!empty($datos))
			{
					$pghtml = AutoCarga::factory('ClaseHTML');
					$html .= "  <table width=\"85%\" class=\"modulo_table_list\" align=\"center\">";
					$html .= "	  <tr align=\"center\"   class=\"modulo_table_list_title\" >\n";
					$html .= "      <td width=\"15%\">CODIGO</td>\n";
					$html .= "      <td width=\"65%\">DESCRIPCION</td>\n";
					$html .= "      <td width=\"15%\">PEND POR DESPACHAR</td>\n";
					$html .= "      <td width=\"15%\">DISPONIBLE</td>\n";
					//$html .= "      <td width=\"5%\">OP</td>\n";
					$html .= "  </tr>\n";
					
					foreach($datos as $key => $dtl)
					{    
						$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
						$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                    
						$html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";

						$html .= "      <td align=\"left\">".$dtl['codigo_producto']."</td>\n";
						$html .= "      <td align=\"left\">".$dtl['nombre_producto']."</td>\n";
						
                       $solicitudes=$sel->ConsultarId_solicitudPendientes($request['empresa']);
				       $cantidad_p=0;
						foreach($solicitudes as $k3y => $dt3)
					   {
						
					     $Id_solicitud=$dt3['solicitud_prod_a_bod_ppal_id'];
					      $info_cantidad=$sel->Consultarid_solicitud($Id_solicitud,$dtl['codigo_producto']);
                        	
								foreach($info_cantidad as $k4y => $dt4)
								{
							       $cantidad=$dt4['cantidad_solic'];
								   $cantidad_p+=$cantidad;
								}
						}	

                        $solicitudes_1=$sel->ConsultarId_solicitudPendientes_1($request['empresa']);
						$cantidad_pdes=0;
						 foreach($solicitudes_1 as $k5y => $dt5)
						 {
						    $Id_solicitud_1=$dt5['solicitud_prod_a_bod_ppal_id'];
						
					        $info_cantidad_1=$sel->Consultarid_solicitud_det_id($Id_solicitud_1,$dtl['codigo_producto']);
                          
						   if(!empty($info_cantidad_1))
							{
						        foreach($info_cantidad_1 as $k6y => $dt6)
								{
								   $solicitud_det_id=$dt6['solicitud_prod_a_bod_ppal_det_id'];
								
								   $cantidad_despacho1=$sel->cantidad_pendiente_inv_mto($solicitud_det_id);
                                     foreach($cantidad_despacho1 as $k7y => $dt7)
						             {
								        $cant_pdes=$dt7['cantidad_pendiente'];
										$cantidad_pdes+=$cant_pdes;					 
								      }
								}
						    }
						 
						 }	
						$clientes=$sel->BuscarTotalPedidosEmpresa($request['empresa'],$dtl['codigo_producto']);
						
						  foreach($clientes as $k8y => $dt8)
						  {
						      $total=$dt8['total'];
						  }
						   $cantidad_total=$cantidad_pdes+$cantidad_p+$total;						 
						
						$html .= "      <td align=\"center\">".round($cantidad_total)."</td>\n";
						$dispon=$dtl['existencia']-$cantidad_total;
						    if($dispon<0)
							{
							  $total_dispon=0;
							  
							}else
							{
								$total_dispon=$dispon;
							
							}
						$html .= "      <td align=\"center\">".round($total_dispon)."</td>\n";
	
						
						
				    //*$datos2=$sel->ConsultarInformacionPendientes($request['empresa'],$dtl['codigo_producto']);

           // $html .= "      <td align=\"center\">".round($datos2[0]['pendiente'])."</td>\n";
         //   $html .= "      <td align=\"center\">\n";
          //  $html .= "         <a href=\"#".$dtl['codigo_producto']."\" onclick=\"xajax_DetalleInformacion('".$dtl['codigo_producto']."', '".$dtl['empresa_id']."','".$dtl['centro_utilidad']."','".$dtl['bodega']."','".$datos2[0]['pendiente']."')\" class=\"label_error\"><img src=\"".GetThemePath()."/images/abajo.png\" border=\"0\" title=\"disponibles\"></a>\n";
           // $html .= "      </td>\n";
            $html .= "  </tr>\n";


         
					}	
	
					$html .= "	</table><br>\n";
					$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
					$html .= "	<br>\n";
				} else
				{
				if($request)
					$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
				}
				$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
				$html .= "		   </form>\n";
				$html .="</div>";
                $html .= ThemeCerrarTabla();
				return $html;	
		
		}
		function GenerarDocumento($action,$datos,$disponibles,$producto)
		{
	
				$html  = ThemeAbrirTabla('REALIZAR SOLICITUD DE PRODUCTO ');
				$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
				$html .="  <script>\n";
				$html .= "	function VerficarDatos(frms){";
				$html .= "  {\n";
				$html .= "    if(frms.txtcantidad.value==\"\")\n";
				$html .= "    {\n";
				$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UNA CANTIDAD A SOLICITAR';\n";
				$html .= "      return;\n";
				$html .= "    }\n";
				$html .= "    if(frms.txtcantidad.value > ".$disponibles.")\n";
				$html .= "    {\n";
				$html .= "      document.getElementById('error').innerHTML = 'LA CANTIDAD SOLICITADA NO PUEDE SER MAYOR A LA DISPONIBLE';\n";
				$html .= "      return;\n";
				$html .= "    }\n";
				$html .= "    frms.submit();\n";
				$html .= "    }\n";   
							
				$html .= " 	xajax_TransVariablesP(frms.observar.value,'".$tipo_producto_id."');";
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
				$html .= " <form name=\"Forma13\" id=\"Forma13\"  action=\"".$action['continuar']."\" method=\"post\" >\n";
				
				$html .= "  <table width=\"70%\" class=\"modulo_table_list_title\"  border=\"0\" align=\"center\">";
				$html .= "	  <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td width=\"15%\"><b>CODIGO</b></td>\n";
				$html .= "      <td width=\"45%\"<b>DESCRIPCION</b></td>\n";
				$html .= "      <td width=\"5%\"><b>DISPO</b></td>\n";
				$html .= "      <td width=\"5%\"><b>SOLI</b></td>\n";
				$html .= "  </tr>\n";
				$html .= "  <tr class=\"modulo_list_claro\" >\n";
				$html .= "  <td >".$datos[0]['codigo_producto']."</td>\n";
				$html .= "  <td >".$datos[0]['descripcion']." ".$datos[0]['contenido_unidad_venta']." ".$datos[0]['unidad']." ".$datos[0]['laboratorio']."</td>\n";
				$html .= "  <td >".$disponibles."</td>\n";
				$html .="  <td> <input type=\"text\" name=\"txtcantidad\" id=\"txtcantidad\"  value=\"\"  size=\"3\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" ></td> ";
                $html .= "  </tr>\n";
				$html .= "<input type=\"hidden\" name=\"tipo\" value=\"".$datos[0]['tipo_producto_id']."\" ";
				
				$html .= "	</table><BR>\n";
				$html  .= "  <table class=\"modulo_table_list\" border=\"0\" align=\"center\" width=\"50%\">\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td width=\"10%\" align=\"center\"> OBSERVACIÒN\n";
				$html .= "      </td>\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "    </tr>\n";
				$html .= "      <td colspan=\"5\"  align=\"center\" class=\"modulo_list_claro\">\n";
				$html .= "        <textarea onkeypress=\"return max(event)\"  name=\"observar\" rows=\"2\" style=\"width:100%\"></textarea>\n";
				$html .= "      </td>\n";
				$html .= "    </tr>\n";
				$html .= "  </table>\n";
				$html .= "  <table width=\"45%\"  border=\"0\"  align=\"center\" >";
				$html .= "		<tr>\n";
				$html .= "       <td align=\"center\" class=\"normal_10AN\" >\n";
				$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CONTINUAR\" onclick=\"VerficarDatos(document.Forma13);\">\n";
				$html .= "		</tr>\n";
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
				$html .= "</fieldset><br>\n";
		    $html .= ThemeCerrarTabla();
				return $html;
		
		}
		function FormaMostrarDocumentoGenerado($action,$solici_prod_a_bod_ppal_id)
		{
				$html  = ThemeAbrirTabla("MENSAJE");
				$html .= " <form name=\"Forma13\" id=\"Forma13\" method=\"post\" >\n";
				$html .= "  <table width=\"55%\"  border=\"0\" class=\"modulo_table_list\" align=\"center\">";
				$html .= "      <td  class=\"modulo_table_list_title\" align=\"center\" >\n";
				$html .= "     SE GENERO LA SOLICITUD : ".$solici_prod_a_bod_ppal_id."  ";
				$html .= "      </td>\n";
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
	}
?>