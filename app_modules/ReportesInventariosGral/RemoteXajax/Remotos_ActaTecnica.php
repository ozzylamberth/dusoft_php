<?php
  
  function VerDocumentoCreado($empresa_id,$prefijo,$numero)
	{
	  $objResponse=new xajaxResponse();
    $consulta=AutoCarga::factory("MovBodegasSQL", "classes", "app", "Inv_MovimientosBodegas");
    $sql=AutoCarga::factory("ActaTecnicaSQL", "classes", "app", "AdminFarmacia");    
	  $resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
    $Tercero = $consulta->ObtenerTerceroDocumentoIngresoPrestamo($prefijo,$numero);
	 
   $html .= "                 <table width=\"90%\" border='1' align=\"center\" rules=\"all\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"35%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a title='RAZON SOCIAL DE LA EMPRESA'>";
         $html .= "                        EMPRESA";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $nombre=$consulta->ColocarEmpresa($resultado['empresa_id']); 
         $html .= "                       <td width=\"65%\" align=\"left\">\n";
         $html .= "                          ".$nombre[0]['razon_social'];
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"8%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a title='CENTRO DE UTILIDAD'>";
         $html .= "                        CENTRO DE UTILIDAD";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
     
         $centro=$consulta->ColocarCentro($resultado['centro_utilidad']);
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$centro[0]['descripcion'];
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"5%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a title='BODEGA'>";
         $html .= "                        BODEGA";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $bodega=$consulta->bodegasname($resultado['bodega']);
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$bodega[0]['descripcion'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                   </table>\n";
         $html .= "                   <br>\n";   



         $html .= "                 <table width=\"90%\" border='1' align=\"center\" rules=\"all\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"35%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a>";
         $html .= "                        TIPO MOVIMIENTO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                       <td align=\"left\">\n";
         $html .= "                          ".$resultado['tipo_movimiento'];
         $html .= "                       </td>\n";
         $html .= "                       <td width=\"25%\"align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a TITLE='TIPO DOCUMENTO BODEGA ID'>";
         $html .= "                        DOC BOD ID";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$resultado['tipo_doc_bodega_id'];
         $html .= "                        </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td width=\"35%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a>";
         $html .= "                            DESCRIPCION";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                       <td COLSPAN='3' align=\"left\">\n";
         $html .= "                          ".$resultado['descripcion'];
         $html .= "                       </td>\n";
         $html .= "                      </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td width=\"8%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a title='PREFIJO - NUMERO'>";
         $html .= "                        NUMERO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".$resultado['prefijo']."-".$resultado['numero'];
         $html .= "                         </td>\n";
         $html .= "                       <td width=\"8%\" align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a title='FECHA DE REGISTRO'>";
         $html .= "                        FECHA";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $html .= "                        <td align=\"left\">\n";
         $html .= "                          ".substr($resultado['fecha_registro'],0,10);
         $html .= "                         </td>\n";
         $html .= "                       </tr>\n";
         $html .= "                    <tr>\n";
         $html .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                        OBSERVACIONES";
         $html .= "                       </td>\n";
         $html .= "                        <td COLSPAN='3' align=\"left\">\n";
         $html .= "                          ".$resultado['observacion'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";

         $html .= "                    <tr>\n";
         $html .= "                       <td align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                       <a title='USUARIO QUE ELABORO EL RECIBO'>";
         $html .= "                        USUARIO";
         $html .= "                       </a>";
         $html .= "                       </td>\n";
         $USUARIO=$consulta->NombreUsu($resultado['usuario_id']);
         $html .= "                        <td COLSPAN='3' align=\"left\">\n";
         $html .= "                          ".$resultado['usuario_id']."-".$USUARIO[0]['nombre'];
         $html .= "                         </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                   </table>\n";
                   
         $html .= "                   <br>\n"; 
         $html .= "                 <table rules=\"all\" width=\"90%\" border='1' align=\"center\" class=\"modulo_table_list\">\n";
         $html .= "                    <tr>\n";
         $html .= "                        <td COLSPAN='6' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                         <a>";
         $html .= "                           PRODUCTOS QUE CONTIENE ESTE DOCUMENTO";
         $html .= "                         </a>";
         $html .= "                        </td>\n";
         $html .= "                    </tr>\n";
         $html .= "                    <tr >\n";
         $html .= "                        <td WIDTH='15%' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a TITLE='CODIGO DEL PRODUCTO'>";
         $html .= "                            CODIGO";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='35%' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a TITLE='DESCRIPCION DEL PRODUCTO'>";
         $html .= "                            DESCRIPCION";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='15%' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a TITLE='FECHA VENCIMIENTO'>";
         $html .= "                            FECHA VENCIMIENTO";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='15%' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a TITLE='LOTE'>";
         $html .= "                            LOTE";
         $html .= "                          </a>";
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='5%' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a TITLE='UNIDAD DEL PRODUCTO'>";
         $html .= "                            CANTIDAD";
         $html .= "                          </a>";  
         $html .= "                        </td>\n";
         $html .= "                        <td WIDTH='5%' align=\"center\" class=\"modulo_table_list_title\">\n";
         $html .= "                          <a TITLE='OP'>";
         $html .= "                            SELECCIONAR";
         $html .= "                          </a>";  
         $html .= "                        </td>\n";
         $valor_unitario= ModuloGetVar('app','Inv_MovimientosBodegas','documentos_valorunitario_'.$resultado['empresa_id']);
         $valor_un=explode(",",$valor_unitario);
        $contar=count($valor_un);
        
          $html .= "                       </tr>\n";
         $valorTotal=0;
         $k=0;
    
      foreach($resultado['DETALLE'] as $doc_val=>$valor)
       {
                 $html .= "                    <tr class=\"modulo_list_claro\">\n";
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       <a>";
                 $html .= "                       ".$valor['codigo_producto'];
                 $html .= "                      </td>\n";
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       ".$valor['nombre']."";
                 $html .= "                      </td>\n";
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       <a>";
                 $html .= "                       ".$valor['fecha_vencimiento'];
                 $html .= "                      </td>\n";
                 $html .= "                      <td align=\"left\">\n";
                 $html .= "                       <a>";
                 $html .= "                       ".$valor['lote'];
                 $html .= "                      </td>\n";
                 
                 $html .= "                      <td align=\"left\">\n";
                 list($entero,$decimal) = explode(".",$valor['cantidad']);
                 if($decimal>0)
                  {
                   $html .= "                       ".$valor['cantidad'];
                  }
                  else
                  {
                   $html .= "                       ".$entero;
                  } 
                
                $Datos=$sql->BuscarResgistroActa($empresa_id,$prefijo,$numero,$valor['movimiento_id']);
                if(empty($Datos))
                $imagen = "folder_vacio.png";
                else
                $imagen = "folder_lleno.png";
                
                $html .= "                      <td align=\"center\">\n";
                 $html .= "                       <a onclick=\"xajax_FormaActa('".$empresa_id."','".$prefijo."','".$numero."','".$valor['movimiento_id']."');\">";
                 $html .= "			    <image src=\"".GetThemePath()."/images/".$imagen."\" border=\"0\">\n";
                 $html .= "                       </a>";
                 $html .= "                      </td>\n";
                
                 $html .= "                    </tr>\n";
                 
                 
                 $k++;
      }
                
         $html .= "                   </table>\n";
	  $objResponse->assign("Documento","innerHTML",$objResponse->setTildes($html));
     $objResponse->script("tabPane.setSelectedIndex(1);");
		return $objResponse;
	
	}
  
  function FormaActa($empresa_id,$prefijo,$numero,$movimiento_id)
	{
	$objResponse=new xajaxResponse();
  
    $sql=AutoCarga::factory("ActaTecnicaSQL", "classes", "app", "AdminFarmacia");
    $producto=$sql->BuscarItem($empresa_id,$prefijo,$numero,$movimiento_id);
    $Datos=$sql->BuscarResgistroActa($empresa_id,$prefijo,$numero,$movimiento_id);
    
	//print_r($Datos_Visual);
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
     $html .= "  ".$producto['descripcion_producto'];
     $html .= " </td>";
     $html .= " <td class=\"modulo_table_list_title\" colspan=\"2\" rowspan=\"2\">";
     $html .= "  PRESENTACION";
     $html .= " </td >";
     $html .= " <td rowspan=\"2\">";
     $html .= "  ".$producto['presentacioncomercial_id']." X ".$producto['precantidad'];
     $html .= " </td>";
     $html .= " </tr>";
     $html .= " <tr>";
     $html .= " <td class=\"modulo_table_list_title\">";
     $html .= "  NOMBRE GENERICO";
     $html .= " </td>";
     $html .= " <td  colspan=\"3\">";
     $html .= "  ".$producto['descripcion_producto'];
     $html .= " </td>";
     $html .= " </tr>";
     $html .= " </table>";
     
     $html .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
     $html .= " <tr>";
     $html .= "     <td >";
     $html .= "       <B>LOTE:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"lote\" id=\"lote\" value=\"".$producto['lote']."\" style=\"width:100%\">";
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
     $html .= "       <B>VENCIMIENTO:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"fecha_vencimiento\" id=\"fecha_vencimiento\" value=\"".$producto['fecha_vencimiento']."\" style=\"width:100%\">";
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
     
	 
	 
	 
     if($Datos['registro_sanitario']=="")
	 {
		$registro_sanitario = $producto['codigo_invima'];
	 }
		else
			{
			$registro_sanitario = $Datos['registro_sanitario'];
			}
		
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
     $html .= "     <td class=\"modulo_table_list_title\">";
     $html .= "       NUMERO DE ORDEN DE COMPRA:";
     $html .= "       </td>";
     $html .= "    <td >";
     $html .= "         <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"orden_pedido_id\" id=\"orden_pedido_id\" value=\"".$orden_pedido_id."\" style=\"width:100%\">";
     $html .= "     </td>";
     
     $html .= "     <td class=\"modulo_table_list_title\">";
     $html .= "       NUMERO DE REMISION:";
     $html .= "       </td>";
     $html .= "    <td >";
     $html .= "         <input type=\"text\" class=\"input-text\"  name=\"numero_remision\" id=\"numero_remision\" value=\"".$Datos['numero_remision']."\" style=\"width:100%\">";
     $html .= "     </td>";
     
     $html .= "     <td class=\"modulo_table_list_title\">";
     $html .= "       # FACTURA:";
     $html .= "       </td>";
     $html .= "    <td >";
     $html .= "      <input type=\"text\" class=\"input-text\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$Datos['numero_factura']."\" style=\"width:100%\">";
     $html .= "     </td>";
     $html .= " </tr>";
     
     $html .= " <tr>";
     $html .= "     <td  class=\"modulo_table_list_title\" >";
     $html .= "         CANTIDAD RECIBIDA";
     $html .= "     </td>";
     $html .= "     <td colspan=\"3\">";
     $html .= "         <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"cantidad\" id=\"cantidad\" value=\"".$producto['cantidad']."\" style=\"width:100%\">";
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
     
     $Listar_EvaluacionesVisuales=$sql->Listar_EvaluacionesVisuales();
     
     $html .= " <br>";
     $html .= "<center><u><b>EVALUACION VISUAL REALIZADA (EMBALAJE)</b></u></center>";
     $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
     $i=0;
     foreach($Listar_EvaluacionesVisuales as $k=>$valor)
     {
     $Datos_Visual=$sql->BuscarItem_EVisual($Datos['acta_tecnica_id'],$valor['evaluacion_visual_id']);
	 $checked_1=""; 
	 $checked_2="";
	 	 if($Datos_Visual['sw_cumple']=='1')
		$checked_1=" checked ";
		else
			if($Datos_Visual['sw_cumple']=='0')
			$checked_2=" checked ";
	 //print_r($Datos_Visual);
	 
	 $html .= " <tr >";
     $html .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
     $html .= "       ".$valor['descripcion'];
     $html .= "       </td>";
          
     $html .= "     <td >";
     $html .= "       <input $checked_1 checked class=\"input-radio\" type=\"radio\" name=\"".$valor['evaluacion_visual_id']."\" id=\"".$valor['evaluacion_visual_id']."\" value=\"1\"><B>C</B><input $checked_2 class=\"input-radio\" type=\"radio\" name=\"".$valor['evaluacion_visual_id']."\" id=\"".$valor['evaluacion_visual_id']."\" value=\"0\"><B>/NC</B>";
     //$html .= "       <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
     $html .= "     </td>";
     $html .= " </tr>";
     $i++;
     }
     
     $html .= " <tr >";
     $html .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
     $html .= "      OTRO:";
     $html .= "       </td>";
          
     $html .= "     <td >";
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
     $html .= "     OBSERVACION (CONDICIONES DE TRANSPORTE, EMBALAJE, MATERIAL DE EMPAQUE Y ENVASE, CONDICIONES ADMINISTRATIVAS, TECNICAS DE NEGOCIACION)";
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
     $html .= "       </td>";
     $html .= "    <td >";
     $html .= "       <input type=\"text\" class=\"input-text\" name=\"responsable_realiza\" id=\"responsable_realiza\" value=\"".$Datos['responsable_realiza']."\" style=\"width:100%\">";
     $html .= "     </td>";
     $html .= " </tr>";
     $html .= " <tr>";
     $html .= "     <td class=\"modulo_table_list_title\">";
     $html .= "       *RESPONSABLE (NOMBRE VERIFICA)";
     $html .= "       </td>";
     $html .= "    <td >";
     $html .= "       <input type=\"text\" class=\"input-text\" name=\"responsable_verifica\" id=\"responsable_verifica\" value=\"".$Datos['responsable_verifica']."\" style=\"width:100%\">";
     $html .= "     </td>";
     $html .= " </tr>";
     $html .= " </table>";
    
      $html .= " <br>";
      $html .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
      $html .= " <tr>";
      $html .= "     <td align=\"center\">";
      if(!empty($Datos))
	  {
	  $html .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"1\">";
	  }
	  else
			{
	  $html .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"0\">";
			}
	  $html .= "      <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$producto['codigo_producto']."\">";
      $html .= "      <input type=\"hidden\" name=\"usuario_id\" id=\"usuario_id\" value=\"".UserGetUID()."\">";
      $html .= "      <input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$prefijo."\">";
      $html .= "      <input type=\"hidden\" name=\"numero\" id=\"numero\" value=\"".$numero."\">";
      $html .= "      <input type=\"hidden\" name=\"acta_tecnica_id\" id=\"movimiento_id\" value=\"".$Datos['acta_tecnica_id']."\">";
      $html .= "      <input type=\"hidden\" name=\"movimiento_id\" id=\"movimiento_id\" value=\"".$movimiento_id."\">";
      $html .= "      <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$producto['empresa_id']."\">";
      $html .= "      <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$producto['centro_utilidad']."\">";
      $html .= "      <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$producto['bodega']."\">";
      $html .= "      <input type=\"button\" value=\"REGISTRAR\" class=\"input-submit\" onclick=\"xajax_RegistrarActaTecnica(xajax.getFormValues('FormularioActaTecnica'));\">";
      $html .= "     </td>";
      $html .= " </tr>";
      $html .= "  </table>";
    $html .= "</form>";
	$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
	$objResponse->call("MostrarSpan");
	$objResponse->script(" for(i=0;i<document.FormularioActaTecnica.sw_concepto_calidad.options.length;i++)
                                if(document.FormularioActaTecnica.sw_concepto_calidad.options[i].value == '".$Datos['sw_concepto_calidad']."')
                                  document.FormularioActaTecnica.sw_concepto_calidad.options[i].selected=true;
                                ");
	
		return $objResponse;
	
	}
  
  
  function RegistrarActaTecnica($Formulario)
	{	
    $objResponse=new xajaxResponse();
    $sql=AutoCarga::factory("ActaTecnicaSQL", "classes", "app", "AdminFarmacia");
 
	//print_r($query);
	
	if($Formulario['c_nc_lote']=="" || $Formulario['c_nc_vencimiento']=="" || $Formulario['sw_concepto_calidad']== "" 
	  || $Formulario['responsable_realiza']== ""|| $Formulario['responsable_verifica']== "" )
			{
			$mensaje_ =" POR FAVOR, DILIGENCIAR LOS CAMPOS (*) OBLIGATORIOS ";
			//$objResponse->assign("MensajeDeError","innerHTML",$mensaje_);
			}
			else
				{
				if($Formulario['modificar']=='0')
					{
					$token=$sql->Insertar_ActaTmp($Formulario,$query);
					}
				  else
					{
					foreach($Formulario as $key => $valor)
									{
									    if(is_numeric($key))
										{
                    $query .= "	UPDATE esm_acta_tecnica_evaluacion_visual ";
                    $query .= "	SET ";
                    $query .= "	sw_cumple = '".$valor."', ";
                    $query .= "	observaciones = '".$Formulario['evaluacion_final_otro']."' ";
                    $query .= " WHERE ";
                    $query .= "       acta_tecnica_id = ".$Formulario['acta_tecnica_id']."
                                      and   evaluacion_visual_id = ".$key." ;"; 
										}
									}
					$token=$sql->Modificar_ActaTmp($Formulario,$query);
					}
				}
	if($token!='1')
	{
	$mensaje=$sql->frmError;
	$objResponse->assign("MensajeDeError","innerHTML",$mensaje_."<br>".$mensaje['MensajeError']);
	}else
		{
		$objResponse->Alert("Ingreso Exitoso!!");
		$objResponse->script("OcultarSpan();");
		//$objResponse->script("xajax_GetItems('".$Formulario['doc_tmp_id']."','".$Formulario['bodegas_doc_id']."');");
    }
    //print_r($query);
    return $objResponse;
	}
  
  function CrearDocumento($doc_tmp_id,$bodegas_doc_id)
	{
 		$objResponse=new xajaxResponse();
		$consulta=new MovBodegasSQL(); 
		$objClass=new doc_bodegas_I002;
		$sql = AutoCarga::factory("MovDocI002","classes","app","Inv_MovimientosBodegas");
		$ComprasTemporal=$sql->DocumentoTempIngresoCompras($doc_tmp_id);
		$DetalleTemporal=$consulta->ConsultaTmp(UserGetUID(),$doc_tmp_id);
    $CodigoProveedor=$sql->ConsultaProveedorOC($ComprasTemporal[0]['orden_pedido_id'],$ComprasTemporal[0]['empresa_id']);
    
    $ProductosAutorizados=$objClass->IngresosAutorizados($ComprasTemporal,$doc_tmp_id);
    $ActaTecnicas_Productos=$objClass->ActasTecnicas_Temporales(UserGetUID(),$doc_tmp_id);
    $EvaluacionesVisuales_Productos=$objClass->EvaluacionesVisuales_Temporales(UserGetUID(),$doc_tmp_id);
    
       
    //print_r($ActaTecnicas_Productos);
		if($docs = $objClass->CrearDocumento($doc_tmp_id,$bodegas_doc_id));
		{
      
			$salida = Documentos_HTML($docs,$CodigoProveedor[0]['codigo_proveedor_id']);
			$IngresoRecepcionParcialCabecera=$sql->InsertarRecepcionParcialCabecera($ComprasTemporal,$docs);
			//cantidad,codigo_producto,fecha_vencimiento,lote,porc_iva,recepcion_parcial_id,valor
			foreach($DetalleTemporal as $key=>$dt)
			{
				$ValorUnitario=$dt['total_costo']/$dt['cantidad'];
				$IngresoRecepcionParcialDetalle=$sql->InsertarRecepcionParcialDetalle($dt['cantidad'],$dt['codigo_producto'],$dt['fecha_vencimiento'],$dt['lote'],$dt['porcentaje_gravamen'],$IngresoRecepcionParcialCabecera[0]['recepcion_parcial_id'],$ValorUnitario);
			}
      
      foreach($ProductosAutorizados as $key=>$pa)
			{
				$IngresoAutorizaciones=$objClass->IngresoAutorizacion($docs,$pa['orden_pedido_id'],$pa['codigo_producto'],$pa['justificacion_ingreso'],
                                                                    $pa['usuario_id_autorizador'],$pa['usuario_id_autorizador_2'],$pa['observacion_autorizacion'],
                                                                    $pa['lote'],$pa['fecha_vencimiento'],$pa['cantidad'],
                                                                    $pa['fecha_ingreso'],$pa['porcentaje_gravamen'],$pa['valor_unitario_compra'],
                                                                    $pa['valor_unitario_factura'],$pa['total_costo'],$pa['empresa_id']);
			}
      $token = $objClass->Insertar_Acta($ActaTecnicas_Productos,$EvaluacionesVisuales_Productos,$docs);
			//$borrarpara=$consulta->Borrarpara_docg($tipo_doc_bodega_id,$doc_tmp_id);
			//$salida = VentanaConfirmacionCrearDoc($docs);
			//$objResponse->call("RegresarCrear");
			$objResponse->assign("ventanauno","innerHTML",$objResponse->setTildes($salida));
			$objResponse->assign("ProductosFueraOrdenCompra","innerHTML","");
      $objResponse->assign("productos_ordenCompra","innerHTML","");
			$objResponse->script("         var link=document.getElementById('link_foc');
                                            link.style.display = 'none';"); 
			$objResponse->assign("ventanados","style.display","none");
			$objResponse->assign("listadoP","style.display","none");
			$objResponse->assign("elimnDoc","style.display","none");
			$objResponse->assign("crearDoc","style.display","none");
		}
		return $objResponse;
	}
	
   function ActasTecnicas($Formulario,$offset)
	{
	$objResponse=new xajaxResponse();
    $sql=AutoCarga::factory("ActaTecnicaSQL", "classes", "app", "ReportesInventariosGral");
    $datos=$sql->Buscar_ActasTecnicas($Formulario,$offset);
   
    //print_r($datos);
    if(!empty($datos))
    {
         $action['paginador'] = " Paginador('0'";
         $pghtml = AutoCarga::factory("ClaseHTML");
          $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
        $html .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    		$html .= "		<tr class=\"modulo_table_list_title\">\n";
        $html .= "      <td>";
        $html .= "        #";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        PREFIJO";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        NUMERO";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        PRODUCTO";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        USUARIO";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        FECHA";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        IMP";
        $html .= "      </td>";
        $html .= "    </tr>";
        foreach($datos as $key=>$valor)
        {
        $html .= "		<tr class=\"modulo_list_claro\">\n";
        $html .= "      <td>";
        $html .= "        ".$valor['acta_tecnica_id']."";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        ".$valor['prefijo']."";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        ".$valor['numero']."";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        ".$valor['producto']."";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        ".$valor['nombre']."";
        $html .= "      </td>";
        $html .= "      <td>";
        $html .= "        ".$valor['fecha_registro']."";
        $html .= "      </td>";
        $html .= "                      <td >\n";
    			$direccion="  app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I002/imprimir/imprimir_ActaTecnica.php";
    			$javas = "javascript:Imprimir('$direccion','".$valor['empresa_id']."','".$valor['prefijo']."','".$valor['numero']."@".$valor['acta_tecnica_id']."');";
    		$html .= "                        <a title='IMPRIMIR' href=\"".$javas."\">\n";
    		$html .= "                          <sub><img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    		$html .= "                         </a>\n";
    		$html .= "                      </td>\n";
        $html .= "    </tr>";
        }
    }
       else
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
	$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
	//$objResponse->call("MostrarSpan");
	
		return $objResponse;
	
	}
?>