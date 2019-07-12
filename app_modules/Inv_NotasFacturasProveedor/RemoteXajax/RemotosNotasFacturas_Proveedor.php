<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosCrearRutasDeViajes.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
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
  
		

  
?>