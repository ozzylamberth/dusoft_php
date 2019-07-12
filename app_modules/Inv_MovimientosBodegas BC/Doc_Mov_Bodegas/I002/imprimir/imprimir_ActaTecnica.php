<?php
  $_ROOT='../../../../../';
  $VISTA='HTML';
  include $_ROOT.'includes/enviroment.inc.php';
  IncludeClass('MovBodegasSQL',null,'app','Inv_MovimientosBodegas');
  IncludeClass('Consultas_Impresion_Acta',null,'app','AdminFarmacia');

  $fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
  if (!IncludeClass('BodegasDocumentos'))
  {
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
  }
  IncludeFile($fileName);
  //print_r($_REQUEST);
  $empresa_id=$_REQUEST['empresa_id'];
  SessionSetVar("EMPRESA",$_REQUEST['empresa_id']);
  $prefijo=$_REQUEST['prefijo'];
  list($numero,$acta_tecnica_id) = explode("@", $_REQUEST['numero']);
  //$numero=$_REQUEST['numero'];
  //$esm_acta_id=$_REQUEST['numero'];
  $consulta=new MovBodegasSQL();
  $sql=new Consultas_Impresion_Acta();
  //$resultado=$consulta->SacarDocumento($empresa_id,$prefijo,$numero);
  //$autorizaciones=$consulta->ConsultarAutorizacionesIngreso($empresa_id,$prefijo,$numero);

 // print_r($autorizaciones);
  
  $TITLE="DETALLE DEL DOCUMENTO";
  print(ReturnHeader($TITLE));
  print(ReturnBody());
  $path = SessionGetVar("rutaImagenes");
  
   // $sql=AutoCarga::factory("ActaTecnicaSQL", "classes", "app", "AdminFarmacia");
    $producto=$sql->BuscarItem($empresa_id,$prefijo,$numero,$acta_tecnica_id);
    $Datos=$sql->BuscarResgistroActa($empresa_id,$prefijo,$numero,$acta_tecnica_id);
    //print_r($_REQUEST);
//($Datos);
	//print_r($Datos_Visual);
	$salida .= "<center>";
	$salida .= "	<div id=\"MensajeDeError\" class=\"label_error\"></div>";
	$salida .= "</center>";
	$salida .= " <form name=\"FormularioActaTecnica\" id=\"FormularioActaTecnica\"> ";
	$salida .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
     $salida .= " <tr class=\"modulo_table_list_title\">";
     $salida .= "   <td align=\"center\" colspan=\"5\" >";
     $salida .= "    REGISTRO DE INSPECCION DE MEDICAMENTOS";
     $salida .= "   </td>";
     $salida .= " </tr>";
     $salida .= " <tr class=\"modulo_table_list_title\">";
     $salida .= "   <td align=\"center\" colspan=\"5\">";
     $salida .= "  INFORMACION BASICA";
     $salida .= "   </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= " <td class=\"modulo_table_list_title\">";
     $salida .= "  NOMBRE COMERCIAL";
     $salida .= " </td>";
     $salida .= " <td>";
     $salida .= "  ".$producto['descripcion_producto'];
     $salida .= " </td>";
     $salida .= " <td class=\"modulo_table_list_title\" colspan=\"2\" rowspan=\"2\">";
     $salida .= "  PRESENTACION";
     $salida .= " </td >";
     $salida .= " <td rowspan=\"2\">";
     $salida .= "  ".$producto['presentacioncomercial_id']." X ".$producto['precantidad'];
     $salida .= " </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= " <td class=\"modulo_table_list_title\">";
     $salida .= "  NOMBRE GENERICO";
     $salida .= " </td>";
     $salida .= " <td  colspan=\"3\">";
     $salida .= "  ".$producto['descripcion_producto'];
     $salida .= " </td>";
     $salida .= " </tr>";
     $salida .= " </table>";
     
     $salida .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
     $salida .= " <tr>";
     $salida .= "     <td >";
     $salida .= "       <B>LOTE:</B> <input readonly=\"true\" type=\"text\" class=\"input-text\" readonly=\"true\" name=\"lote\" id=\"lote\" value=\"".$producto['lote']."\" style=\"width:100%\">";
     $salida .= "       </td>";
     //print_r($Datos);
	 if($Datos['c_nc_lote']=='1')
		{
    $checked_1=" checked ";
    $disabled_2 = "disabled ";
    }
		else
			if($Datos['c_nc_lote']=='0')
			{
      $checked_2=" checked ";	
      $disabled_1 = "disabled ";
      }
	
     $salida .= "     <td >";
     $salida .= "       *<input $checked_1 $disabled_1 class=\"input-radio\" type=\"radio\" name=\"c_nc_lote\" id=\"c_nc_lote\" value=\"1\"><B>C</B><input $checked_2 $disabled_2 class=\"input-radio\" type=\"radio\" name=\"c_nc_lote\" id=\"c_nc_lote\" value=\"0\"><B>/NC</B>";
     $salida .= "     </td>";
     
     $salida .= "     <td >";
     $salida .= "       <B>VENCIMIENTO:</B> <input type=\"text\" class=\"input-text\" readonly=\"true\" name=\"fecha_vencimiento\" id=\"fecha_vencimiento\" value=\"".$producto['fecha_vencimiento']."\" style=\"width:100%\">";
     $salida .= "      </td>";
	 
    $checked_1=""; 
    $checked_2="";
    $disabled_2="";
    $disabled_1="";
	 	 if($Datos['c_nc_vencimiento']=='1')
		{
    $checked_1=" checked ";
    $disabled_2 = "disabled ";
    }
		else
			if($Datos['c_nc_vencimiento']=='0')
			{
      $checked_2=" checked ";
      $disabled_1 = "disabled ";
      }


	 
     $salida .= "    <td colspan=\"2\">";
     $salida .= "       *<input $checked_1 $disabled_1 class=\"input-radio\" type=\"radio\" name=\"c_nc_vencimiento\" id=\"c_nc_vencimiento\" value=\"1\" onClick=\"this.checked= !this.checked\"><B>C</B><input $checked_2 $disabled_2 class=\"input-radio\" type=\"radio\" name=\"c_nc_vencimiento\" id=\"c_nc_vencimiento\" value=\"0\"><B>/NC</B>";
     $salida .= "     </td>";
     
           
     $salida .= " </tr>";
     
	 
	 
	 
     if($Datos['registro_sanitario']=="")
	 {
		$registro_sanitario = $producto['codigo_invima'];
	 }
		else
			{
			$registro_sanitario = $Datos['registro_sanitario'];
			}
		
	 $salida .= " <tr>";
     $salida .= "     <td  class=\"modulo_table_list_title\">";
     $salida .= "         *REGISTRO SANITARIO";
     $salida .= "     </td>";
     $salida .= "     <td colspan=\"2\">";
     $salida .= "         <input readonly=\"true\" type=\"text\" class=\"input-text\" name=\"registro_sanitario\" id=\"registro_sanitario\" value=\"".$registro_sanitario."\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "     FABRICANTE:";
     $salida .= "     </td>";
     $salida .= "     <td colspan=\"2\">";
     $salida .= "       ".$producto['fabricante'];
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " </table>";
     $salida .= " <br>";
     $salida .= "<center><u><b>INFORMACION SOBRE MUESTREO</b></u></center>";
     $salida .= "	<table width=\"100%\" rules=\"all\" align=\"center\" class=\"modulo_table_list\">\n";
     $salida .= " <tr>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       NUMERO DE ORDEN DE COMPRA:";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "         <input readonly=\"true\" type=\"text\" class=\"input-text\" readonly=\"true\" name=\"orden_pedido_id\" id=\"orden_pedido_id\" value=\"".$Datos['orden_pedido_id']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       NUMERO DE REMISION:";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "         <input readonly=\"true\" type=\"text\" class=\"input-text\"  name=\"numero_remision\" id=\"numero_remision\" value=\"".$Datos['numero_remision']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       # FACTURA:";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "      <input type=\"text\" readonly=\"true\" class=\"input-text\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$Datos['numero_factura']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= " </tr>";
     
     $salida .= " <tr>";
     $salida .= "     <td  class=\"modulo_table_list_title\" >";
     $salida .= "         CANTIDAD RECIBIDA";
     $salida .= "     </td>";
     $salida .= "     <td colspan=\"3\">";
     $salida .= "         <input type=\"text\" readonly=\"true\" class=\"input-text\" readonly=\"true\" name=\"cantidad\" id=\"cantidad\" value=\"".$producto['cantidad']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "     TOTAL CORRUGADAS:";
     $salida .= "     </td>";
     $salida .= "     <td colspan=\"2\">";
     $salida .= "       <input readonly=\"true\" type=\"text\" value=\"".$Datos['total_corrugadas']."\" class=\"input-text\" name=\"total_corrugadas\" id=\"total_corrugadas\" value=\"\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= " </tr>";
     
     $salida .= " <tr>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       UN/CORRUGADA:";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "       <input  readonly=\"true\" type=\"text\" class=\"input-text\" value=\"".$Datos['unidad_corrugadas']."\" name=\"unidad_corrugadas\" id=\"unidad_corrugadas\" style=\"width:100%\">";
     $salida .= "     </td>";
     
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       CORRUGADAS A MUESTREAR:";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "       <input readonly=\"true\" type=\"text\" class=\"input-text\" name=\"corrugadas_a_muestrear\" id=\"corrugadas_a_muestrear\" value=\"".$Datos['corrugadas_a_muestrear']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "      UN/CORRUGADA A MUESTREAR";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "      <input readonly=\"true\" type=\"text\" class=\"input-text\" name=\"unidad_corrugadas_a_muestrear\" id=\"unidad_corrugadas_a_muestrear\" value=\"".$Datos['unidad_corrugadas_a_muestrear']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= " </tr>";
     
     $salida .= " <tr>";
     $salida .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
     $salida .= "     ARGUMENTACION POR DOBLE MUESTREO";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
     $salida .= "     <textarea readonly=\"true\" name=\"argumentacion_doble_muestreo\" id=\"argumentacion_doble_muestreo\" class=\"textarea\" style=\"width:100%;\">".$Datos['argumentacion_doble_muestreo']."</textarea>";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " </table>";
     
     $Listar_EvaluacionesVisuales=$sql->Listar_EvaluacionesVisuales();
     
     $salida .= " <br>";
     $salida .= "<center><u><b>EVALUACION VISUAL REALIZADA (EMBALAJE)</b></u></center>";
     $salida .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
     $i=0;
     foreach($Listar_EvaluacionesVisuales as $k=>$valor)
     {
     $Datos_Visual=$sql->BuscarItem_EVisual($Datos['acta_tecnica_id'],$valor['evaluacion_visual_id']);
	 $checked_1=""; 
	 $checked_2="";
   $disabled_2="";
   $disabled_1="";
	 	 if($Datos_Visual['sw_cumple']=='1')
      {
      $checked_1=" checked ";
      $disabled_2 =" disabled ";
      }
		else
			if($Datos_Visual['sw_cumple']=='0')
			{
      $checked_2=" checked ";
      $disabled_1 =" disabled";
      }
	 //print_r($Datos_Visual);
	 
	 $salida .= " <tr >";
     $salida .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
     $salida .= "       ".$valor['descripcion'];
     $salida .= "       </td>";
          
     $salida .= "     <td >";
     $salida .= "       <input $checked_1 $disabled_1 checked class=\"input-radio\" type=\"radio\" name=\"".$valor['evaluacion_visual_id']."\" id=\"".$valor['evaluacion_visual_id']."\" value=\"1\"><B>C</B><input $checked_2 $disabled_2 class=\"input-radio\" type=\"radio\" name=\"".$valor['evaluacion_visual_id']."\" id=\"".$valor['evaluacion_visual_id']."\" value=\"0\"><B>/NC</B>";
     //$salida .= "       <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $i++;
     }
     
     $salida .= " <tr >";
     $salida .= "     <td class=\"modulo_table_list_title\" style=\"align:left\" width=\"60%\">";
     $salida .= "      OTRO:";
     $salida .= "       </td>";
          
     $salida .= "     <td >";
     $salida .= "     <textarea readonly=\"true\" name=\"evaluacion_final_otro\" id=\"evaluacion_final_otro\" class=\"textarea\" style=\"width:100%;\">".$Datos_Visual['observaciones']."</textarea>";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " </table>";
    
     $select .= " <select class=\"select\" disabled name=\"sw_concepto_calidad\" id=\"sw_concepto_calidad\">";
     $select .= " <option value=\"\">-- SELECCIONAR -- </option>";
     $select .= " <option value=\"1\">APROBADO</option>";
     $select .= " <option value=\"2\">RECHAZADO</option>";
     $select .= " <option value=\"3\">RETENIDO EN CUARENTENA</option>";
     $select .= " </select>";
     $salida .= " <br>";
     $salida .= "<center><u><b>OBSERVACIONES GENERALES</b></u></center>";
     $salida .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
     $salida .= " <tr>";
     $salida .= "    <td align=\"center\" class=\"modulo_table_list_title\">";
     $salida .= "     *CONCEPTO DE CALIDAD";
     $salida .= "     </td>";
     $salida .= "    <td>";
     $salida .= "     ".$select;
     $salida .= "    </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
     $salida .= "    OBSERVACION (CONDICIONES DE TRANSPORTE, EMBALAJE, MATERIAL DE EMPAQUE Y ENVASE, CONDICIONES ADMINISTRATIVAS, TECNICAS DE NEGOCIACION)";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= "    <td colspan=\"6\" align=\"center\" class=\"modulo_table_list_title\">";
     $salida .= "     <textarea readonly=\"true\" name=\"observacion\" id=\"observacion\" class=\"textarea\" style=\"width:100%;\">".$Datos['observacion']."</textarea>";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " </table>";
    
     $salida .= " <br>";
     $salida .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
     $salida .= " <tr>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       *RESPONSABLE (NOMBRE REALIZA)";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "       <input readonly=\"true\" type=\"text\" class=\"input-text\" name=\"responsable_realiza\" id=\"responsable_realiza\" value=\"".$Datos['responsable_realiza']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       *RESPONSABLE (NOMBRE VERIFICA)";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "       <input readonly=\"true\" type=\"text\" class=\"input-text\" name=\"responsable_verifica\" id=\"responsable_verifica\" value=\"".$Datos['responsable_verifica']."\" style=\"width:100%\">";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " <tr>";
     $salida .= "     <td class=\"modulo_table_list_title\">";
     $salida .= "       SITIO DE RECEPCION";
     $salida .= "       </td>";
     $salida .= "    <td >";
     $salida .= "       ".$Datos['razon_social']."";
     $salida .= "     </td>";
     $salida .= " </tr>";
     $salida .= " </table>";
    
      $salida .= " <br>";
      $salida .= "	<table width=\"100%\" rules=\"all\" class=\"modulo_table_list\">\n";
      $salida .= " <tr>";
      $salida .= "     <td align=\"center\">";
      if(!empty($Datos))
	  {
	  $salida .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"1\">";
	  }
	  else
			{
	  $salida .= "      <input type=\"hidden\" name=\"modificar\" id=\"modificar\" value=\"0\">";
			}
	  $salida .= "      <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"".$producto['codigo_producto']."\">";
      $salida .= "      <input type=\"hidden\" name=\"usuario_id\" id=\"usuario_id\" value=\"".UserGetUID()."\">";
      $salida .= "      <input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$prefijo."\">";
      $salida .= "      <input type=\"hidden\" name=\"numero\" id=\"numero\" value=\"".$numero."\">";
      $salida .= "      <input type=\"hidden\" name=\"acta_tecnica_id\" id=\"movimiento_id\" value=\"".$Datos['acta_tecnica_id']."\">";
      $salida .= "      <input type=\"hidden\" name=\"movimiento_id\" id=\"movimiento_id\" value=\"".$movimiento_id."\">";
      $salida .= "      <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$producto['empresa_id']."\">";
      $salida .= "      <input type=\"hidden\" name=\"centro_utilidad\" id=\"centro_utilidad\" value=\"".$producto['centro_utilidad']."\">";
      $salida .= "      <input type=\"hidden\" name=\"bodega\" id=\"bodega\" value=\"".$producto['bodega']."\">";
      //$salida .= "      <input type=\"button\" value=\"REGISTRAR\" class=\"input-submit\" onclick=\"xajax_RegistrarActaTecnica(xajax.getFormValues('FormularioActaTecnica'));\">";
      $salida .= "     </td>";
      $salida .= " </tr>";
      $salida .= "  </table>";
    $salida .= "</form>";
  $salida .= "<br>\n";

  $salida .= "<br>\n";
  $salida .= "<br>\n";
  $salida .= "<br>\n";
  $salida .= "<table align=\"center\" width=\"50%\" class=\"modulo_list_claro\">\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"left\">\n";
  $salida .= "     Revisado Por :";
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      <hr width=\"100%\">";
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "</table>\n";
  
  
  $salida .= "<table width=\"90%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      FECHA DE IMPRESION";
  $salida .= "    </td>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      USUARIO IMPRESION";
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "  <tr>\n";
  $salida .= "    <td align=\"center\">\n";
  $salida .= "      ".date("Y-m-d");
  $salida .= "    </td>\n";
  $salida .= "    <td align=\"center\">\n";
  $USUARIO=$consulta->NombreUsu(UserGetUID());
  $salida .= "      ".UserGetUID()."-".$USUARIO[0]['nombre'];
  $salida .= "    </td>\n";
  $salida .= "  </tr>\n";
  $salida .= "</table>\n";
  $salida .= "<script>";
  $salida .= "  for(i=0;i<document.FormularioActaTecnica.sw_concepto_calidad.options.length;i++)
                                if(document.FormularioActaTecnica.sw_concepto_calidad.options[i].value == '".$Datos['sw_concepto_calidad']."')
                                  document.FormularioActaTecnica.sw_concepto_calidad.options[i].selected=true;";
  $salida .= "</script>";
  echo $salida;
	print(ReturnFooter());
?>