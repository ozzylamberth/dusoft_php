<?php
/**
  * @package DUANA
  * @version $Id: RemotosCrearRutasDeViajes.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author 
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *

  */
    
 function Listado_ConceptoEspecifico($codigo_concepto_general,$i)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");

      $datos =$sql->Buscar_GlosasConceptoEspecifico(trim($codigo_concepto_general));
	 // print_r($datos);
		    $html = "          <option value=\"\">-- SELECCIONAR --</option>";
		foreach($datos as $key=>$valor)
		{
			$html .= "			<option value=\"".$valor['codigo_concepto_especifico']."\">".$valor['codigo_concepto_especifico']."-".$valor['descripcion_concepto_especifico']."</option>";
		}
	  
	 $objResponse->assign("codigo_concepto_especifico".$i."","innerHTML",$html);
	 $objResponse->script("document.getElementById('codigo_concepto_especifico".$i."').style.width=50+'%';");
		
      return $objResponse;
		}
  
 
  
  function AnularNotaFactura($TipoIdTercero,$Tercero_Id,$Empresa_Id,$Numero_factura,$Prefijo,$Numeracion)
		{
    		$objResponse = new xajaxResponse();
			//print_r($Formulario);
						
							$html .= "<center>\n";
						    $html .= "  <label class=\"label_error\">ADVERTENCIA:!DESPUES DE ANULAR LA NOTA EN LA FACTURA '".$Numero_factura."', NO ES POSIBLE CAMBIAR EL ESTADO¡</label>\n";
						    $html .= "</center>\n";
							
							$html .= "<center>\n";
						    $html .= "  <div class=\"label_error\" id=\"error\"></div>\n";
						    $html .= "</center>\n";
							
							$html .= "					<form name=\"FormaAntesAnular\" id=\"FormaAntesAnular\" method=\"post\">";
			
							$html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
							//print_r($request);
					        $html .= "		<tr class=\"formulacion_table_list\" >\n";
							$html .= "			<td colspan=\"2\">ANULAR NOTA '".$Prefijo."-".$Numeracion."' A LA FACTURA: '".$Numero_factura."'</td>\n";
					  		$html .= "			<input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$TipoIdTercero."\">";
					  		$html .= "			<input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$Tercero_Id."\">";
					  		$html .= "			<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$Empresa_Id."\">";
					  		$html .= "			<input type=\"hidden\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$Numero_factura."\">";
					  		$html .= "			<input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$Prefijo."\">";
					  		$html .= "			<input type=\"hidden\" name=\"numeracion\" id=\"numeracion\" value=\"".$Numeracion."\">";
					  		$html .= "		</tr>\n";
							
							
							
							$html .= "		<tr >\n";
					  		$html .= "			<td width=\"50%\"><b>JUSTIFICACION</b></td><td width=\"60%\"><textarea style=\"width:100%;height:100%\" name=\"justificacion\"></textarea></td>\n";
							$html .= "		</tr >\n";
							$html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
							$html .= "			<td align=\"center\" colspan=\"2\">\n";
							
							$html .= "			<input type=\"button\" value=\"ANULAR\" class=\"modulo_table_list\" onclick=\"Validar(xajax.getFormValues('FormaAntesAnular'));\">\n";
							$html .= "			</td>";
							$html .= "		</tr>\n";
											
					  		
											
					        $html .= "		</table>\n";
							$html .= "					 </form>";
							
							$html .= "		<br>\n";
							
					      
		    
			$objResponse->assign("Contenido","innerHTML",$html);
			$objResponse->call("MostrarSpan");
			
			return $objResponse;
		}

		
function AplicarAnulacionNota($Formulario)
  {
  $objResponse = new xajaxResponse();
 // print_r($Formulario);
  $sql = AutoCarga::factory("CrearNotasFacturasProveedores","classes","app","Inv_NotasFacturasProveedor");
  $Token=$sql->AnularNota($Formulario);
  
		  if($Token)
		  {
			$objResponse->script("OcultarSpan();");
			$objResponse->script("alert('Nota Anulada, con Exito');");
		  }
  
  return $objResponse;
  }

function VerDetalleCalificacion($CodigoProveedorId,$Empresa_Id,$NumeroFactura)
		{
    		$objResponse = new xajaxResponse();
        
        $sql = AutoCarga::factory("FacturacionProveedorSQL", "classes", "app", "Inv_AuditoriaFacturasProveedor");
				$FacturaProveedorCabecera=$sql->FacturaProveedorCabecera($Empresa_Id,$CodigoProveedorId,$NumeroFactura);
        $UsuarioVerificador=$sql->ObtenerInformacionUsuario($FacturaProveedorCabecera[0]['usuario_id_verificador']);
					if($FacturaProveedorCabecera[0]['calificacion_verificacion']=='1')
				$Calificacion = "<b><u><font color=\"green\">BIEN</font></u></b> ";
				else
					$Calificacion = "<b><u><font color=\"red\">MAL</font></u></b> ";
	  
	$html .= "                <fieldset class=\"fieldset\" style=\"width:80%\">\n";
  $html .= "                  <legend class=\"normal_10AN\">\n";
  $html .= "                    <img src=\"".GetThemePath()."/images/informacion.png\">DOCUMENTO VERIFICADO\n";
	$html .= "                  </legend>\n";
     
	$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";  
	$html .= "		<tr class=\"formulacion_table_list\" >\n";
	$html .= "			<td width=\"20%\" colspan=\"7\">Resultados De La Verificacion</td>\n";
	$html .= "		</tr>\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>CALIFICACION VERIFICACION</b></td><td width=\"60%\">".$Calificacion."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>OBSERVACIONES</b></td><td width=\"60%\">".$FacturaProveedorCabecera[0]['observacion_verificacion']."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>RESPONSABLE VERIFICACION</b></td><td width=\"60%\">".$UsuarioVerificador['nombre']."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr >\n";
	$html .= "			<td width=\"40%\"><b>FECHA VERIFICACION</b></td><td width=\"60%\">".$FacturaProveedorCabecera[0]['fecha_verificacion']."</td>\n";
	$html .= "		</tr >\n";
	$html .= "		<tr ".$clase." onmouseout=mOut(this,\"".$bck."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
	$html .= "			<td align=\"center\" colspan=\"2\">\n";
	$html .= "			</td>";
	$html .= "		</tr>\n";
	$html .= "	</table>";
	$html .= "                  </fieldset>\n";
		    
			$objResponse->assign("Contenido","innerHTML",$html);
			$objResponse->call("MostrarSpan");
			
			return $objResponse;
		}
  

	/******************************************************************************************
	* Remotos actas tecnicas
	******************************************************************************************/
    //function FormaActa($item_id='',$doc_tmp_id='',$bodegas_doc_id='',$orden_pedido_id='')
    function FormaActa($fac,$lot,$fvc,$desc,$cod,$orden,$cant,$emp,$Cu,$Bod,$pref,$num,$prov)
	{
		$objResponse=new xajaxResponse();
		$cls = AutoCarga::factory("AdicionalesActas","classes","app","Inv_ActasTecnicas");
		//$sql=new doc_bodegas_I002;
		$producto=$cls->BuscarItem(trim($cod));
		//$Datos=$sql->BuscarResgistroActa($doc_tmp_id,UserGetUID(),$item_id);
		//print_r($producto);
	
		$html .= "<center>";
		$html .= "	<div id=\"MensajeDeError\" class=\"label_error\"></div>";
		$html .= "</center>";
		$html .= " <form name=\"FormularioActaTecnica\" id=\"FormularioActaTecnica\"> ";
		$html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
		 $html .= " <tr class=\"modulo_table_list_title\">";
		 $html .= "   <td align=\"center\" colspan=\"5\" >";
		 $html .= "    REGISTRO DE INSPECCION DE MEDICAMENTOS";
		 $html .= "   </td>";
		 $html .= " </tr>";
		 
		 $html .= " <tr class=\"modulo_table_list_title\">";
		 $html .= "   <td align=\"center\" colspan=\"5\">";
		 $html .= "  INFORMACION BASICA";
		 $html .= "   </td>";
		 $html .= " </tr>";
		 $html .= " <tr>";
		 $html .= " <td class=\"modulo_table_list_title\">";
		 $html .= "  NOMBRE COMERCIAL";
		 $html .= " </td>";
		 $html .= " <td>";
		 $html .= "  ".$desc;
		 $html .= " </td>";
		 $html .= " <td class=\"modulo_table_list_title\" colspan=\"1\" rowspan=\"2\">";
		 $html .= "  PRESENTACION";
		 $html .= " </td>";
		 $html .= " <td rowspan=\"2\">";
		// $html .= "  ".$producto['presentacioncomercial_id']." X ".$producto['precantidad'];

		 $html .= " ".$producto['prescom']." X ".$producto['precantidad'];
		 $html .= " </td>";
		 $html .= " </tr>";
		 $html .= " <tr>";
		 $html .= " <td class=\"modulo_table_list_title\">";
		 $html .= "  NOMBRE GENERICO";
		 $html .= " </td>";
		 $html .= " <td  colspan=\"2\">";
		 $html .= "  ".$desc;
		 $html .= " </td>";
		 $html .= " </tr>";
		 $html .= " </table>";
		 
		 $html .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
		 $html .= " <tr>";
		 $html .= "     <td >";
		 $html .= "       <B>LOTE:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"lote\" id=\"lote\" value=\"".$lot."\" style=\"width:100%\">";
		 $html .= "       </td>";
		 
		 if($Datos['c_nc_lote']=='1')
			$checked_1=" checked ";
			else
				if($Datos['c_nc_lote']=='0')
				$checked_2=" checked ";	
		
		 $html .= "     <td >";
		 $html .= "       *<input $checked_1 class=\"input-radio\" type=\"radio\" name=\"c_nc_lote\" id=\"c_nc_lote\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"c_nc_lote\" id=\"c_nc_lote\" value=\"0\"><B>/NC</B>";
		 $html .= "     </td>";
		 
		 $html .= "     <td >";
		 $html .= "       <B>VENCIMIENTO:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"fecha_vencimiento\" id=\"fecha_vencimiento\" value=\"".$fvc."\" style=\"width:100%\">";
		 $html .= "      </td>";
		 
		 $checked_1=""; 
		 $checked_2="";
			 if($Datos['c_nc_vencimiento']=='1')
			$checked_1=" checked ";
			else
				if($Datos['c_nc_vencimiento']=='0')
				$checked_2=" checked ";


		 
		 $html .= "    <td colspan=\"2\">";
		 $html .= "       *<input $checked_1 class=\"input-radio\" type=\"radio\" name=\"c_nc_vencimiento\" id=\"c_nc_vencimiento\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"c_nc_vencimiento\" id=\"c_nc_vencimiento\" value=\"0\"><B>/NC</B>";
		 $html .= "     </td>";
		 
			   
		 $html .= " </tr>";
		 
		 
		 // if($Datos['registro_sanitario']=="")
		 // {
		 $registro_sanitario = $producto['codinv'];
		 // }
			// else
				// {
				// $registro_sanitario = $Datos['registro_sanitario'];
				// }
			
		 $html .= " <tr>";
		 $html .= "     <td  class=\"modulo_table_list_title\">";
		 $html .= "         *REGISTRO SANITARIO";
		 $html .= "     </td>";
		 $html .= "     <td colspan=\"2\">";
		 $html .= "         <input type=\"text\" class=\"input-text\" name=\"registro_sanitario\" id=\"registro_sanitario\" value=\"".$registro_sanitario."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "     FABRICANTE:";
		 $html .= "     </td>";
		 $html .= "     <td colspan=\"2\">";
		 $html .= "       ".$producto['fabricante'];
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " </table>";
		 $html .= " <br>";
		 $html .= "<center><u><b>INFORMACION SOBRE MUESTREO</b></u></center>";
		 $html .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
		 $html .= " <tr>";
		 $html .= "    <td class=\"modulo_table_list_title\">";
		 $html .= "       NUMERO DE ORDEN DE COMPRA: ";
		 $html .= "    </td>";
		 $html .= "    <td >";
		 $html .= "         <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"orden_pedido_id\" id=\"orden_pedido_id\" value=\"".$orden."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "       NUMERO DE REMISION:";
		 $html .= "     </td>";
		 $html .= "    <td >";
		 $html .= "         <input type=\"text\" class=\"input-text\"  name=\"numero_remision\" id=\"numero_remision\" value=\"".$Datos['numero_remision']."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "       # FACTURA:";
		 $html .= "     </td>";
		 $html .= "    <td >";
		 $html .= "      <input type=\"text\" class=\"input-text\" name=\"numero_factura\" id=\"numero_factura\" readonly=\"true\" value=\"".$fac."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 
		 $html .= " <tr>";
		 $html .= "     <td  class=\"modulo_table_list_title\" >";
		 $html .= "         CANTIDAD RECIBIDA";
		 $html .= "     </td>";
		 $html .= "     <td colspan=\"3\">";
		 $html .= "         <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"cantidad\" id=\"cantidad\" value=\"".$cant."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "     TOTAL CORRUGADAS:";
		 $html .= "     </td>";
		 $html .= "     <td colspan=\"2\">";
		 $html .= "       <input type=\"text\" value=\"".$Datos['total_corrugadas']."\" class=\"input-text\" name=\"total_corrugadas\" id=\"total_corrugadas\" value=\"\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 
		 $html .= " <tr>";
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "       UN/CORRUGADA:";
		 $html .= "       </td>";
		 $html .= "    <td >";
		 $html .= "       <input type=\"text\" class=\"input-text\" value=\"".$Datos['unidad_corrugadas']."\" name=\"unidad_corrugadas\" id=\"unidad_corrugadas\" style=\"width:100%\">";
		 $html .= "     </td>";
		 
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "       CORRUGADAS A MUESTREAR:";
		 $html .= "       </td>";
		 $html .= "    <td >";
		 $html .= "       <input type=\"text\" class=\"input-text\" name=\"corrugadas_a_muestrear\" id=\"corrugadas_a_muestrear\" value=\"".$Datos['corrugadas_a_muestrear']."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "      UN/CORRUGADA A MUESTREAR";
		 $html .= "       </td>";
		 $html .= "    <td >";
		 $html .= "      <input type=\"text\" class=\"input-text\" name=\"unidad_corrugadas_a_muestrear\" id=\"unidad_corrugadas_a_muestrear\" value=\"".$Datos['unidad_corrugadas_a_muestrear']."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 
		 $html .= " <tr>";
		 $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
		 $html .= "     ARGUMENTACION POR DOBLE MUESTREO";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " <tr>";
		 $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
		 $html .= "     <textarea name=\"argumentacion_doble_muestreo\" id=\"argumentacion_doble_muestreo\" class=\"textarea\" style=\"width:100%;\">".$Datos['argumentacion_doble_muestreo']."</textarea>";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " </table>";
		 
		 $Listar_EvaluacionesVisuales=$cls->Listar_EvaluacionesVisuales();
		 
		 $html .= " <br>";
		 $html .= "<center><u><b>EVALUACION VISUAL REALIZADA (EMBALAJE)</b></u></center>";
		 $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
		 $i=0;
		 foreach($Listar_EvaluacionesVisuales as $k=>$valor)
		 {
		 //$Datos_Visual=$sql->BuscarItem_EVisual($doc_tmp_id,UserGetUID(),$item_id,$valor['evaluacion_visual_id']);
		   $checked_1=""; 
		   $checked_2="";
			 // if($Datos_Visual['sw_cumple']=='1')
			// $checked_1=" checked ";
			// else
				// if($Datos_Visual['sw_cumple']=='0')
				// $checked_2=" checked ";
		 // /*print_r($Datos_Visual); ESTABA COMENTADO*/
		 
		  $html .= " <tr>";
		  $html .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
		  $html .= "       ".$valor['descripcion'];
		  $html .= "     </td>";
			  
		  $html .= "     <td >";
		  $html .= "       <input $checked_1 checked class=\"input-radio\" type=\"radio\" name=\"".$valor['evaluacion_visual_id']."\" id=\"".$valor['evaluacion_visual_id']."\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"".$valor['evaluacion_visual_id']."\" id=\"".$valor['evaluacion_visual_id']."\" value=\"0\"><B>/NC</B>";
		 /*$html .= "       <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";ESTABA COMENTADO*/
		  $html .= "     </td>";
		  $html .= " </tr>";
		  $i++;
		 }
		 
		 $html .= " <tr>";
		 $html .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
		 $html .= "      OTRO:";
		 $html .= "     </td>";
			  
		 $html .= "     <td>";
		 $html .= "     <textarea name=\"evaluacion_final_otro\" id=\"evaluacion_final_otro\" class=\"textarea\" style=\"width:100%;\">".$Datos_Visual['observaciones']."</textarea>";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " </table>";
		
		 $select .= " <select class=\"select\" name=\"sw_concepto_calidad\" id=\"sw_concepto_calidad\">";
		 $select .= " <option value=\"\">-- SELECCIONAR -- </option>";
		 $select .= " <option value=\"1\">APROBADO</option>";
		 $select .= " <option value=\"2\">RECHAZADO</option>";
		 $select .= " <option value=\"3\">RETENIDO EN CUARENTENA</option>";
		 $select .= " </select>";
		 $html .= " <br>";
		 $html .= "<center><u><b>OBSERVACIONES GENERALES</b></u></center>";
		 $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
		 $html .= " <tr>";
		 $html .= "    <td align=\"center\" class=\"modulo_table_list_title\">";
		 $html .= "     *CONCEPTO DE CALIDAD";
		 $html .= "     </td>";
		 $html .= "    <td>";
		 $html .= "     ".$select;
		 $html .= "    </td>";
		 $html .= " </tr>";
		 $html .= " <tr>";
		 $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
		 $html .= "     DILIGENCIAR (CONDICIONES DE TRANSPORTE, EMBALAJE, MATERIAL DE EMPAQUE Y ENVASE, CONDICIONES ADMINISTRATIVAS, TECNICAS DE NEGOCIACION)";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " <tr>";
		 $html .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
		 $html .= "     <textarea name=\"observacion\" id=\"observacion\" class=\"textarea\" style=\"width:100%;\">".$Datos['observacion']."</textarea>";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " </table>";
		
		 $html .= " <br>";
		 $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
		 $html .= " <tr>";
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "       *RESPONSABLE (NOMBRE REALIZA)";
		 $html .= "     </td>";
		 $html .= "    <td >";
		 $html .= "       <input type=\"text\" class=\"input-text\" name=\"responsable_realiza\" id=\"responsable_realiza\" value=\"".$Datos['responsable_realiza']."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " <tr>";
		 $html .= "     <td class=\"modulo_table_list_title\">";
		 $html .= "       *RESPONSABLE (NOMBRE VERIFICA)";
		 $html .= "     </td>";
		 $html .= "    <td>";
		 $html .= "       <input type=\"text\" class=\"input-text\" name=\"responsable_verifica\" id=\"responsable_verifica\" value=\"".$Datos['responsable_verifica']."\" style=\"width:100%\">";
		 $html .= "     </td>";
		 $html .= " </tr>";
		 $html .= " </table>";
		
		  $html .= " <br>";
		  $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
		  $html .= " <tr>";
		  $html .= "     <td align=\"center\">";
		  if(!empty($producto))
		  {
		  $html .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"0\">";
		  }
		  // else
				// {
		  // $html .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"0\">";
				// }
		  $html .= "      <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$cod."\">";
		  $html .= "      <input type=\"hidden\" name=\"usuario_id\" id=\"usuario_id\" value=\"".UserGetUID()."\">";
		  $html .= "      <input type=\"hidden\" name=\"doc_tmp_id\" id=\"doc_tmp_id\" value=\"".$doc_tmp_id."\">";
		  $html .= "      <input type=\"hidden\" name=\"bodegas_doc_id\" id=\"bodegas_doc_id\" value=\"".$bodegas_doc_id."\">";
		  $html .= "      <input type=\"hidden\" name=\"item_id\" id=\"item_id\" value=\"".$item_id."\">";
		  $html .= "      <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$emp."\">";
		  $html .= "      <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$Cu."\">";
		  $html .= "      <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$Bod."\">";
		  $html .= "      <input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$pref."\">";
		  $html .= "      <input type=\"hidden\" name=\"numero\" id=\"numero\" value=\"".$num."\">";
		  $html .= "      <input type=\"hidden\" name=\"proveedor_id\" id=\"proveedor_id\" value=\"".$prov."\">";
		  $html .= "      <input type=\"button\" value=\"REGISTRAR\" class=\"input-submit\" onclick=\"xajax_RegistrarActaTecnica(xajax.getFormValues('FormularioActaTecnica'));\">";
		  $html .= "     </td>";
		  $html .= " </tr>";
		  $html .= "  </table>";
		  $html .= "</form>";

		$objResponse->script("Iniciar3('ACTA TECNICA DE RECEPCION DE PRODUCTOS');");
		$objResponse->script("MostrarSpan('d2Container2');");
		$objResponse->assign("d2Contents2","innerHTML",$html);
		//$objResponse->assign("d2Contents2","innerHTML",$objResponse->setTildes($html));
		// $objResponse->script(" for(i=0;i<document.FormularioActaTecnica.sw_concepto_calidad.options.length;i++)
									// if(document.FormularioActaTecnica.sw_concepto_calidad.options[i].value == '".$Datos['sw_concepto_calidad']."')
									  // document.FormularioActaTecnica.sw_concepto_calidad.options[i].selected=true;
									// ");
		
		return $objResponse;
	
	}
	
	/*function TestingAjax(){
		$html = "";
		$objResponse=new xajaxResponse();
		
		
		for($i=0; $i < 10; $i++){
			$html .= "<tr><td>{$i}</td></tr>";
		
		}
		
		$objResponse->assign("table_test","innerHTML",$html);
		
		return $objResponse;
	
	}*/
	
	function TestingAjax($id){
		if(is_null($id)){
		
			$id = "table_test";
		}
	
		$html = "";
		$objResponse=new xajaxResponse();
		
		$sql = AutoCarga::factory("CrearNotasFacturasProveedores","classes","app","prueba1");
		
		$data = $sql->GetTestingData();
		
		//echo print_r($data);
		
		
		foreach($data as $d => $value){
			$html .= "<tr>
						<td>{$value['codigo_concepto_general']}</td>
						<td>{$value['descripcion_concepto_general']}</td>
					</tr>";
		
		}
		
		$objResponse->assign($id,"innerHTML",$html);
		
		return $objResponse;
	
	}

   /***********************************************************
   * Funcion para registrar el acta tecnica del producto
   ************************************************************/
	
    function RegistrarActaTecnica($Formulario)
	{	
		$objResponse=new xajaxResponse();
		//$sql = new doc_bodegas_I002;
	    $sql = AutoCarga::factory("AdicionalesActas","classes","app","Inv_ActasTecnicas");

		$empresa = $Formulario['empresa_id'];
		$factura = $Formulario['numero_factura'];
		$IdProv = $Formulario['proveedor_id'];
		
		$Evisual = $sql->Listar_EvaluacionesVisuales();
		
		if($Formulario['c_nc_lote']=="" || $Formulario['c_nc_vencimiento']=="" || $Formulario['sw_concepto_calidad']== "" 
		  || $Formulario['responsable_realiza']== "" || $Formulario['responsable_verifica']== "" )
		{
				$mensaje_ =" POR FAVOR, DILIGENCIAR LOS CAMPOS (*) OBLIGATORIOS ";
				$objResponse->assign("MensajeDeError","innerHTML",$mensaje_);
		}
		else
		{
					 if($Formulario['modificar']=='0')
						{
							$token = $sql->Insertar_Acta($Formulario,$Evisual);


							if($token)	
							 {
							  $objResponse->Alert("Ingreso Exitoso!!");
							  $objResponse->script("Cerrar('d2Container2');");
							  $objResponse->script("Reload();");
							  //$prev = ModuloGetURL('app','Inv_ActasTecnicas','controller','Det_factura_prov')."&empresa_id=".$Formulario['empresa_id']."&numero_factura=".$Formulario['numero_factura']."&codigo_proveedor_id=".$Formulario['proveedor_id'];
							  //$objResponse->redirect($prev);

							 }
						     else 
							   {
							     $objResponse->alert('Error en el ingreso');
							   }	 
						}

		}

		return $objResponse;
	}


	
	
	

  
?>