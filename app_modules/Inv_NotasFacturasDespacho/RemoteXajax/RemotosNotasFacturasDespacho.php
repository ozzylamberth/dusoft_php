<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosNotasFacturasDespacho.php
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
    
  function Listar_TercerosProveedores($TipoIdTercero,$TerceroId,$Descripcion,$Empresa_Id,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $TercerosProveedores=$sql->Listar_TercerosProveedores($TipoIdTercero,$TerceroId,$Descripcion,$Empresa_Id,$offset);
  
  $action['paginador'] = "Paginador('".$TipoIdTercero."','".$TerceroId."','".$Descripcion."','".$Empresa_Id."'";
          
      $pghtml = AutoCarga::factory("ClaseHTML");
      $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">TERCEROS CLIENTES</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">TIPO ID</td>\n";
      $html .= "      <td width=\"25%\">TERCERO ID</td>\n";
      $html .= "      <td width=\"20%\">NOMBRE TERCERO</td>\n";
      $html .= "      <td width=\"20%\">DIRECCION</td>\n";
      $html .= "      <td width=\"20%\">TELEFONO</td>\n";
      $html .= "      <td width=\"20%\">PAIS</td>\n";
      $html .= "      <td width=\"20%\">FACT.</td>\n";
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($TercerosProveedores as $key => $tp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            if($tp['tipo_id_tercero']=='NIT')
            $dv="-".$tp['dv'];
            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$tp['tipo_id_tercero']."</td><td>".$tp['tercero_id']."".$dv." </td>\n";
            $html .= "      <td >".$tp['nombre_tercero']."</td><td>".$tp['direccion']." </td>\n";
            $html .= "      <td >".$tp['telefono']."</td><td>".$tp['pais']." </td>\n";
                    
     
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a onclick=\"xajax_FacturasProveedor('".$tp['codigo_proveedor_id']."','".$Empresa_Id."','".$tp['tipo_id_tercero']."','".$tp['tercero_id']."')\">\n";
            $html .= "          <img title=\"VER FACTURAS DEL PROVEEDOR\" src=\"".GetThemePath()."/images/show.gif\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            
            
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $html .= "<div id=\"FacturasProveedor\"></div>";
		  $html = $objResponse->setTildes($html);
          $objResponse->assign("ListadoTercerosProveedores","innerHTML",$html);
          return $objResponse;
        }
  
 function FacturasProveedor($CodigoProveedorId,$Empresa_Id,$TipoIdTercero,$Tercero_Id)
  {
  $objResponse = new xajaxResponse();
     $html .= "<fieldset class=\"fieldset\">\n";
     $html .= "  <legend class=\"normal_10AN\">FACTURAS PROVEEDOR</legend>\n";
		$html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"4\">";
		$html .= "      BUSCAR FACTURA";
		$html .= "      </td>";
		$html .= "      </tr>";
   
		
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      NUMERO DE FACTURA";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <input type=\"text\" class=\"input-text\" id=\"numero_factura_\">";
		$html .= "      </td>";
		
		$html .= "      <td align=\"center\" >";
		$html .= "      <input type=\"button\" value=\"Buscar Factura\" onclick=\"xajax_Listar_FacturasProveedor('".$CodigoProveedorId."','".$Empresa_Id."',document.getElementById('numero_factura_').value,'".$TipoIdTercero."','".$Tercero_Id."','1');\" class=\"modulo_table_list\">";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      </table>";
    
     $html .= "      <div id=\"ListadoFacturasProveedor\"></div>";
     $html .= "</fieldset><br>\n";
     $html = $objResponse->setTildes($html);
     $objResponse->assign("FacturasProveedor","innerHTML",$html);
     $objResponse->script("xajax_Listar_FacturasProveedor('".$CodigoProveedorId."','".$Empresa_Id."','".$NumeroFactura."','".$TipoIdTercero."','".$Tercero_Id."','1');");
     return $objResponse;
          
  }

  
  
  function Listar_FacturasProveedor($CodigoProveedorId,$Empresa_Id,$NumeroFactura,$TipoIdTercero,$Tercero_Id,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $FacturasProveedor=$sql->Listar_FacturasProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$NumeroFactura,$offset);
  
  $action['paginador'] = "Paginador_2('".$CodigoProveedorId."','".$Empresa_Id."','".$NumeroFactura."','".$TipoIdTercero."','".$Tercero_Id."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
      
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">#-FACTURA</td>\n";
      $html .= "      <td width=\"25%\">FECHA</td>\n";
      //$html .= "      <td width=\"20%\">VALOR</td>\n";
      $html .= "      <td width=\"20%\">DETALLE</td>\n";
      $html .= "      <td width=\"20%\">NOTAS</td>\n";
      $html .= "      <td width=\"40%\">Ad.NOT</td>\n";
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($FacturasProveedor as $key => $fp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
            
            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$fp['prefijo']."-".$fp['numero']."</td><td>".$fp['fecha_registro']."".$dv." </td>\n";
           // $html .= "      <td >".$fp['valor_total']."</td>\n";
                        
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a onclick=\"xajax_VerDetalleFacturaProveedor('".$fp['prefijo']."','".$fp['numero']."','".$Empresa_Id."')\">\n";
            $html .= "          <img title=\"VER DETALLE FACTURA DEL PROVEEDOR\" src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            
            
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a onclick=\"xajax_VerNotasFacturaProveedor('".$TipoIdTercero."','".$Tercero_Id."','".$Empresa_Id."','".$fp['prefijo']."','".$fp['numero']."','1')\">\n";
            $html .= "          <img title=\"VER NOTAS FACTURAS DEL PROVEEDOR\" src=\"".GetThemePath()."/images/folder_lleno.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            
            $html .= "      <td align=\"center\">\n";
            $html .= "      <div id=\"ad_nota".$fp['numero_factura']."\">";//                                                                           documento_id,prefijo,numero        opcion
														//$documento_id,$TipoIdTercero,$Tercero_Id,$numero_factura,$Empresa_Id,$opcion
			$html .= "        <a onclick=\"xajax_Notas('','".$TipoIdTercero."','".$Tercero_Id."','".$fp['prefijo']."','".$fp['numero']."','".$Empresa_Id."','1')\">\n";
            $html .= "          <img title=\"AGREGAR NOTA A LA FACTURA\" src=\"".GetThemePath()."/images/folder_vacio.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </div>";
            $html .= "      </td>\n";
            $html .= "      </tr>\n";
         }
          
          $html .= "    </table>\n";
		  $html = $objResponse->setTildes($html);
                
          $objResponse->assign("ListadoFacturasProveedor","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  function VerDetalleFacturaProveedor($prefijo,$numero,$EmpresaId,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $DetalleFacturaProveedor=$sql->Detalle_FacturaProveedor($prefijo,$numero,$EmpresaId,$offset);

  
		$pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    $action['paginador'] = "Paginador_3('".$NumeroFactura."','".$CodigoProveedorId."','".$EmpresaId."','".$offset."'";
    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" colspan=\"8\">";
	$html .= "      DETALLE DE LA FACTURA #".$NumeroFactura;
	$html .= "      </td>";
	$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\">";
	$html .= "      CODIGO PRODUCTO";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      NOMBRE";
	$html .= "      </td>";
    
    $html .= "      <td align=\"center\">";
	$html .= "      CANTIDAD";
	$html .= "      </td>";
  $html .= "      <td align=\"center\">";
	$html .= "      LOTE";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      FECHA V.";
	$html .= "      </td>";
  
  /*$html .= "      <td align=\"center\">";
	$html .= "      IVA";
	$html .= "      </td>";*/
  $html .= "      <td align=\"center\">";
	$html .= "      VALOR";
	$html .= "      </td>";
 /* $html .= "      <td align=\"center\">";
	$html .= "      SUBTOTAL";
	$html .= "      </td>";*/
  
  $html .= "      </tr>";
		foreach($DetalleFacturaProveedor as $key => $dfp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$dfp['codigo_producto']."</td><td>".$dfp['descripcion']." </td>\n";
            $html .= "      <td >".$dfp['numero_unidades']."</td>";
            $html .= "      <td >".$dfp['lote']."</td>";
            $html .= "      <td >".$dfp['fecha_vencimiento']."</td>";
            //$html .= "      <td >".$dfp['porc_iva']."</td>";
            //$html .= "      <td>$".number_format($dfp['valor'])." </td>\n";
            $html .= "      <td>$".number_format($dfp['valor'])." </td>\n";
            $Iva = $Iva + (($dfp['valor']*$dfp['numero_unidades'])*($dfp['porc_iva']/100));
            $Subtotal = $Subtotal + ($dfp['valor']);
            $html .= "    </tr>";
         }
		//$html .= "    <tr class=\"modulo_list_oscuro\">";
           //   $html .= "        <td colspan=\"2\"><B>IVA:</B> $".number_format($Iva)."</td>";
         //     $html .= "    </tr>";
              $html .= "    <tr class=\"modulo_list_claro\">";
              $html .= "        <td colspan=\"2\"><B>TOTAL:</B> $".number_format($Subtotal)."</td>";
              
              $html .= "    </tr>";		
    $html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  

  function VerNotasFacturaProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$prefijo,$numero)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $NotasFacturaProveedor=$sql->NotasFacturaProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$prefijo,$numero);
   
    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" colspan=\"7\">";
	$html .= "      NOTAS DE LA FACTURA #".$prefijo."-".$numero;
	$html .= "      </td>";
	$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\">";
	$html .= "      NOTA";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      VALOR NOTA";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      DOCUMENTO";
	$html .= "      </td>";
        
    $html .= "      <td align=\"center\">";
	$html .= "      DETALLE";
	$html .= "      </td>";
    
	$html .= "      <td align=\"center\">";
	$html .= "      OP";
	$html .= "      </td>";
	$html .= "      </tr>";
	
	
		foreach($NotasFacturaProveedor as $key => $nfp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$nfp['descripcion']."</td><td>".$nfp['valor_nota']." </td>\n";
            $html .= "      <td >".$nfp['prefijo']." - ".$nfp['numero']." </td>\n";
            $html .= "		<td>";
			$html .= "		<a class=\"label_error\" onclick=\"xajax_VerDetallesNotaFacturaProveedor('".$TipoIdTercero."','".$Tercero_Id."','".$Empresa_Id."','".$prefijo."','".$numero."','".$nfp['prefijo']."','".$nfp['numero']."');\">Ver Detalle</a>";
           $html .= "		</td>";
		   $html .= "		<td>";
			$html .= "		<a class=\"label_error\" onclick=\"xajax_AnularNotaFactura('".$TipoIdTercero."','".$Tercero_Id."','".$Empresa_Id."','".$prefijo."','".$numero."','".$nfp['prefijo']."','".$nfp['numero']."');\">";
           $html .= "          <img title=\"ANULAR FACTURA\" src=\"".GetThemePath()."/images/error_digitacion.png\" border=\"0\">\n";
		   $html .= "      </a>";
		   $html .= "		</td>";
            $html .= "    </tr>";
         }
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  function AnularNotaFactura($TipoIdTercero,$Tercero_Id,$Empresa_Id,$prefijo_factura,$numero_factura,$Prefijo,$Numeracion)
		{
    		$objResponse = new xajaxResponse();
			//print_r($Formulario);
						
							$html .= "<center>\n";
						    $html .= "  <label class=\"label_error\">ADVERTENCIA:!DESPUES DE ANULAR LA NOTA EN LA FACTURA '".$prefijo_factura."-".$numero_factura."', NO ES POSIBLE CAMBIAR EL ESTADO¡</label>\n";
						    $html .= "</center>\n";
							
							$html .= "<center>\n";
						    $html .= "  <div class=\"label_error\" id=\"error\"></div>\n";
						    $html .= "</center>\n";
							
							$html .= "					<form name=\"FormaAntesAnular\" id=\"FormaAntesAnular\" method=\"post\">";
			
							$html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
							//print_r($request);
					        $html .= "		<tr class=\"formulacion_table_list\" >\n";
							$html .= "			<td colspan=\"2\">ANULAR NOTA '".$Prefijo."-".$Numeracion."' A LA FACTURA: '".$prefijo_factura."-".$numero_factura."'</td>\n";
					  		$html .= "			<input type=\"hidden\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" value=\"".$TipoIdTercero."\">";
					  		$html .= "			<input type=\"hidden\" name=\"tercero_id\" id=\"tercero_id\" value=\"".$Tercero_Id."\">";
					  		$html .= "			<input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$Empresa_Id."\">";
					  		$html .= "			<input type=\"hidden\" name=\"prefijo_factura\" id=\"prefijo_factura\" value=\"".$prefijo_factura."\">";
					  		$html .= "			<input type=\"hidden\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$numero_factura."\">";
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
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $Token=$sql->AnularNota($Formulario);
  
		  if($Token)
		  {
			$objResponse->script("OcultarSpan();");
			$objResponse->script("alert('Nota Anulada, con Exito');");
		  }
  
  return $objResponse;
  }
		
		
function VerDetallesNotaFacturaProveedor($TipoIdTercero,$Tercero_Id,$Empresa_Id,$prefijo_factura,$numero_factura,$Prefijo,$Numeracion)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $DetallesNotaFacturaProveedor=$sql->DetallesNotaFacturaProveedor($Empresa_Id,$Prefijo,$Numeracion);
   
    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" colspan=\"7\">";
	$html .= "      DETALLES DE LA NOTA A LA FACTURA ".$Numero_factura;
	$html .= "      </td>";
	$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\">";
	$html .= "      CONCEPTO";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      VALOR CONCEPTO";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      CODIGO PRODUCTO";
	$html .= "      </td>";
	
	$html .= "      <td align=\"center\">";
	$html .= "      LOTE";
	$html .= "      </td>";
        
    
    $html .= "      </tr>";
		foreach($DetallesNotaFacturaProveedor as $key => $dnfp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$dnfp['concepto']."</td><td>".$dnfp['valor_concepto']." </td>\n";
            $html .= "      <td >".$dnfp['codigo_producto']."</td><td>".$dnfp['lote']." </td>\n";
            $html .= "    </tr>";
         }
		$html .= "      </table>";
	$html .="<center><a class=\"label_error\" onclick=\"xajax_VerNotasFacturaProveedor('".$TipoIdTercero."','".$Tercero_Id."','".$Empresa_Id."','".$prefijo_factura."','".$numero_factura."');\" class=\"label_error\">VOLVER</a></center>";
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }

  
  //                                                documento_id,prefijo,numero        opcion
   function Notas($documento_id,$TipoIdTercero,$Tercero_Id,$prefijo,$numero,$Empresa_Id,$opcion)
  {
  $objResponse = new xajaxResponse();
   
  if($opcion=='1')
  {
      $html .="<select class=\"select\" id=\"tipos_doc_generales\" >";
      $html .="<option value=\"".SessionGetVar("debito")."\" onclick=\"xajax_Notas(this.value,'".$TipoIdTercero."','".$Tercero_Id."','".$prefijo."','".$numero."','".$Empresa_Id."','2');\">";
      $html .="NOTAS DEBITO";
      $html .="</option>";
      $html .="<option value=\"".SessionGetVar("credito")."\" onclick=\"xajax_Notas(this.value,'".$TipoIdTercero."','".$Tercero_Id."','".$prefijo."','".$numero."','".$Empresa_Id."','2');\">";
      $html .="NOTAS CREDITO";
      $html .="</option>";
      $html .="</select>";
	  $html = $objResponse->setTildes($html);
      $objResponse->assign("ad_nota".$numero_factura,"innerHTML",$html);
    }
	
  
  if($opcion=='2')
  {
  $url =ModuloGetURL("app","Inv_NotasFacturasDespacho","controller","Crear_NotasFacturasProveedor");
  $sql=AutoCarga::factory("CrearNotasFacturasDespachos", "", "app","Inv_NotasFacturasDespacho");
  $doc_nota_tmp_id=$sql->DocTemporalId();
  $doc_nota_tmp_id_=$doc_nota_tmp_id[0]['doc_nota_tmp_id']+1;
  
  $html .= "        <a href=\"".$url."&tipo_id_tercero=".$TipoIdTercero."&tercero_id=".$Tercero_Id."&prefijo=".$prefijo."&numero=".$numero."&documento_id=".$documento_id."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&opcion_documento=1&doc_nota_tmp_id=".$doc_nota_tmp_id_."\" >\n";
  $html .= "          <img title=\"Continuar...\" src=\"".GetThemePath()."/images/es_continue.gif\" border=\"0\">\n";
  $html .= "        </a>\n";
  $html = $objResponse->setTildes($html);
  $objResponse->assign("ad_nota".$numero_factura,"innerHTML",$html);
  }
  
    
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  

  function FormDetalleNota($EmpresaId,$doc_nota_tmp_id)
  {
  $objResponse = new xajaxResponse();
  
	$html .= "      <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" colspan=\"7\">";
	$html .= "      INGRESO DE CONCEPTOS A LA NOTA";
	$html .= "      </td>";
	$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
	$html .= "      <td align=\"center\" class=\"modulo_table_list_title\">";
	$html .= "      CONCEPTO";
	$html .= "      </td>";
	$html .= "      <td align=\"center\">";
	$html .= "      <input type=\"text\" class=\"input-text\" id=\"concepto\">";
	$html .= "      </td>";
	$html .= "      </tr>";
	
	    
	$html .= "      <tr class=\"modulo_list_claro\">";
	$html .= "      <td align=\"center\" class=\"modulo_table_list_title\">";
	$html .= "      VALOR CONCEPTO";
	$html .= "      </td>";
	$html .= "      <td align=\"center\">";
	$html .= "      <input type=\"text\" class=\"input-text\" id=\"valor_concepto\" onkeypress=\"return acceptNum(event)\">";
	$html .= "      </td>";
	$html .= "      </tr>";
	
	$html .= "      <tr class=\"modulo_list_claro\">";
	$html .= "      <td align=\"center\" class=\"modulo_table_list_title\">";
	$html .= "      PRODUCTO ASOCIADO(opcional)";
	$html .= "      </td>";
	$html .= "      <td align=\"center\">";
	$html .= "      <div id=\"NombreProducto\"></div> <a href=\"#ACA\" onclick=\"xajax_ListarProductosFactura(document.getElementById('prefijo').value,document.getElementById('numero').value,'".$EmpresaId."')\" title=\"Buscar Producto Asociado\">Buscar Producto</a>";
	$html .= "		<a name=\"#ACA\"></a>";
	$html .= "      <input type=\"hidden\" value=\"\" id=\"codigo_producto\">";
	$html .= "      <input type=\"hidden\" value=\"\" id=\"lote\">";
	$html .= "      </td>";
	$html .= "		</tr>";
	
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" colspan=\"2\">";
	$html .= "      <input type=\"button\" onclick=\"xajax_AdicionarConceptoANota('".$EmpresaId."','".$doc_nota_tmp_id."',document.getElementById('concepto').value,document.getElementById('valor_concepto').value,document.getElementById('codigo_producto').value,document.getElementById('lote').value);\" value=\"Adicionar Concepto\" class=\"modulo_table_list\">";
	$html .= "      </td>";                                                                                                                   // $TipoIdTercero,$TerceroId,$ValorNota,$NumeroFactura,$DocumentoId,$Prefijo,$Numeracion,$EmpresaId
    $html .= "      </tr>";
	
	$html .= "		</table>";
  
  $objResponse->assign("NotaCreada","innerHTML",$html);
  return $objResponse;
  }

function AdicionarConceptoANota($EmpresaId,$doc_nota_tmp_id,$Concepto,$ValorConcepto,$Codigo_Producto,$Lote)
  {
  $objResponse = new xajaxResponse();
  
  if($Concepto == "" || $ValorConcepto == "" )
	{
	$objResponse->alert("Faltan Diligeciar Datos Obligatorios!!!");
	}
	else
	{
			  //$objResponse->alert("$EmpresaId,$Prefijo,$Numeracion,$Concepto,$ValorConcepto,$Codigo_Producto,$Lote");
			  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
			  $token=$sql->InsertarDetalleNota($EmpresaId,$doc_nota_tmp_id,$Concepto,$ValorConcepto,$Codigo_Producto,$Lote);
			  
			  if($token)
			  {
			  //$objResponse->alert("BIEN!!...");
			  $objResponse->script("xajax_NotaDetalles('".$EmpresaId."','".$doc_nota_tmp_id."');");
			  $objResponse->script("document.getElementById('NombreProducto').innerHTML='';");
			  $objResponse->script("document.getElementById('concepto').value='';");
			  $objResponse->script("document.getElementById('valor_concepto').value='';");
			  $objResponse->script("document.getElementById('codigo_producto').value='';");
			  $objResponse->script("document.getElementById('lote').value='';");
			  $objResponse->script("");
			  //$objResponse->script("document.getElementById('BotonNota').value='Nota Creada';");
			  //$objResponse->script("xajax_FormDetalleNota('".$EmpresaId."','".$Prefijo."','".$Numeracion."');");
			  }
			  else
			  $objResponse->alert("Error en el Ingreso...");
	}
  return $objResponse;
  }
  
  
   function ListarProductosFactura($prefijo,$numero,$EmpresaId,$Offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $DetalleFacturaProveedor=$sql->Detalle_FacturaProveedor($prefijo,$numero,$EmpresaId,$Offset);

  
	$pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    $action['paginador'] = "PaginadorFactura('".$NumeroFactura."','".$OrdenPedidoId."','".$CodigoProveedorId."','".$EmpresaId."','".$offset."'";
    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" colspan=\"8\">";
	$html .= "      DETALLE DE LA FACTURA #".$NumeroFactura;
	$html .= "      </td>";
	$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\">";
	$html .= "      CODIGO PRODUCTO";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      NOMBRE";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      PRESENTACION";
	$html .= "      </td>";
        
    $html .= "      <td align=\"center\">";
	$html .= "      CANTIDAD";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      LOTE";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      FECHA V.";
	$html .= "      </td>";
    $html .= "      <td align=\"center\">";
	$html .= "      VALOR";
	$html .= "      </td>";
	$html .= "      <td align=\"center\">";
	$html .= "      VALOR";
	$html .= "      </td>";
	$html .= "      </tr>";
		foreach($DetalleFacturaProveedor as $key => $dfp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$dfp['codigo_producto']."</td><td>".$dfp['descripcion']." </td>\n";
            $html .= "      <td >".$dfp['unidad']." - ".$dfp['contenido_unidad_venta']." </td>\n";
            $html .= "      <td >".$dfp['numero_unidades']."</td><td>".$dfp['lote']." </td>\n";
            $html .= "      <td >".$dfp['fecha_vencimiento']."</td><td>$".number_format($dfp['valor'])." </td>\n";
			$html .= "		<a href=\"#\" onclick=\"ProductoSeleccionado('".$dfp['codigo_producto']."','".$dfp['descripcion']." ".$dfp['contenido_unidad_venta']."-".$dfp['unidad']." Lote:".$dfp['lote']."','".$dfp['lote']."');\">Seleccionar</a>";
            $html .= "    </tr>";
         }
	
	$html .= "      <tr class=\"modulo_list_oscuro\">";
	$html .= "      <td align=\"center\" colspan=\"8\">";
	$html .= "      <a href=\"#\" onclick=\"QuitarProductoSeleccionado();\">Quitar Seleccion de Productos</a>";
	$html .= "      </td>";
	$html .= "      </tr>";
	$html .= "      </table>";
 
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  function NotaDetalles($Empresa_Id,$doc_nota_tmp_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  $NotaDetalles=$sql->NotaDetalles($Empresa_Id,$doc_nota_tmp_id);
  
  if(empty($NotaDetalles))
	$objResponse->script("document.getElementById('crear_documento').disabled=true;");
	else
	$objResponse->script("document.getElementById('crear_documento').disabled=false;");
  
  $action['paginador'] = "PaginadorNotaDetalles('".$Empresa_Id."','".$Prefijo."','".$Numeracion."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">DETALLES DE LA NOTA</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"25%\">CONCEPTO</td>\n";
      $html .= "      <td width=\"15%\">VALOR</td>\n";
      $html .= "      <td width=\"20%\">PRODUCTO</td>\n";
      $html .= "      <td width=\"10%\">LOTE</td>\n";
      $html .= "      <td width=\"5%\">ACCION</td>\n";
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($NotaDetalles as $key => $nd)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

             $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$nd['concepto']."</td><td>".$nd['valor_concepto']."</td>\n";
            $html .= "      <td >".$nd['codigo_producto']."</td><td>".$nd['lote']." </td>\n";
          
            $html .= "      <td align=\"center\">\n";
															//					$tabla,$id,$campo_id,$Empresa_Id,$Prefijo,$Numeracion
            $html .= "        <a href=\"#\" onclick=\"xajax_BorrarDetalleNota('inv_notas_facturas_despacho_d_tmp','".$nd['detalle']."','item_id','".$Empresa_Id."','".$doc_nota_tmp_id."')\">\n";
            $html .= "          <img title=\"ELIMINAR\" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
			$valor_nota=$valor_nota+$nd['valor_concepto'];
			
            
            
          }
          
          
          
          $html .= "<tr class=\"formulacion_table_list\">";
		  $html .= "<td>";
		  $html .= "Valor Total:";
		  $html .= "</td>";
		  $html .= "<td>";
		  $html .= "$".$valor_nota;
		  $html .= "</td>";
		  $html .= "</tr>";
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          SessionSetVar("valor_nota",$valor_nota);      
		  $objResponse->script("document.getElementById('valor_nota').value='".$valor_nota."';");
		  $objResponse->script("document.getElementById('valor_notica').innerHTML='$".SessionGetVar("valor_nota")."';");
          $objResponse->assign("DetallesNota","innerHTML",$html);
          return $objResponse;
          
  }
  
  function BorrarDetalleNota($tabla,$id,$campo_id,$Empresa_Id,$doc_nota_tmp_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token=$sql->Borrar_Registro($tabla,$id,$campo_id);
    
    if($token)
	{
    $objResponse->script("xajax_NotaDetalles('".$Empresa_Id."','".$doc_nota_tmp_id."');");
	}
      else
      $objResponse->alert("ERROR");
      
    
    
    return $objResponse;	
	}
	
	
	function CrearDocumento($EmpresaId,$doc_nota_tmp_id,$ValorNota)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  
  $DetalleDocTmp=$sql->NotaDetalles($EmpresaId,$doc_nota_tmp_id);
  $CabeceraDocTmp=$sql->ObtenerDocumentoTemporal($EmpresaId,$doc_nota_tmp_id);
  $DocumentoId=$sql->BuscarDocumento($CabeceraDocTmp[0]['documento_id']);
  $Prefijo=$DocumentoId[0]['prefijo'];
  $Numero=$DocumentoId[0]['numeracion'];
  
  $url = ModuloGetURL("app","Inv_NotasFacturasDespacho","controller","CrearNotas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."";
  //print_r($DetalleDocTmp);
  
  $Token=$sql->CrearDocumento($CabeceraDocTmp,$DocumentoId,$ValorNota);
	  if($Token)
	  {
		  $sql->ActualizarDocumento($EmpresaId,$CabeceraDocTmp[0]['documento_id'],$Numero);
		  
		  foreach($DetalleDocTmp as $key => $ddt)
		  {
		  $sql->InsertarDocumentoDetalle($EmpresaId,$ddt['concepto'],$ddt['valor_concepto'],$ddt['codigo_producto'],$ddt['lote'],$Prefijo,$Numero);
		  
      }
      $objResponse->Alert("Nota Creada!!");
								$objResponse->script("
								var pagina='".$url."';
								document.location.href=pagina;");
	  }
  

  
  return $objResponse;
  }
  
  function BorrarDocumento($EmpresaId,$Prefijo,$Numeracion,$opc)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("CrearNotasFacturasDespachos","classes","app","Inv_NotasFacturasDespacho");
  
  $conteo=$sql->NotaDetalles($EmpresaId,$Prefijo,$Numeracion);
  $num=count($conteo);
  
  if($num==0)
  {
  $objResponse->alert("No se puede crear un documento sin un detalle");
  }
  else
		{
		$html = "<CENTER><B>SE HA CREADO EL DOCUMENTO</CENTER><B>";
		$token=$sql->CrearDocumento($EmpresaId,$Prefijo,$Numeracion,SessionGetVar("valor_nota"));
		  if($token)
		     $objResponse->assign("DocumentoCreado","innerHTML",$html);
			 else
				 $objResponse->alert("No se ha creado el Documento!!!");
		  
		//$objResponse->alert("Bueno, hagale".SessionGetVar("valor_nota"));
		}
  
  
  
  return $objResponse;
  }
  
?>