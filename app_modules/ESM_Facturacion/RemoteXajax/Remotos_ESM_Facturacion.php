<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos_ESM_Facturacion.php
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
 
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
  $VISTA = "HTML";  
    
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
		
      return $objResponse;
		}
 
 function GuardarGlosa($Formulario)
		{
		$objResponse = new xajaxResponse();
		$url = ModuloGetURL("app","ESM_Facturacion","controller","Glosar")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
		$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
		$k=0;
		$j=0;
		if($Formulario['motivo_glosa_id']!="")
		{
			for($i=0;$i<$Formulario['registros'];$i++)
			{
			if($Formulario[$i]!="")
			{
				if($Formulario['valor_glosa'.$i]!="" && $Formulario['valor_glosa'.$i]>0 && $Formulario['codigo_concepto_general'.$i]!="" && $Formulario['codigo_concepto_especifico'.$i]!="")
				{
				$token=$sql->Insertar_GlosaDetalle($Formulario['motivo_glosa_id'],$Formulario['valor_glosa'.$i],trim($Formulario['codigo_concepto_general'.$i]),trim($Formulario['codigo_concepto_especifico'.$i]),$Formulario['observacion'.$i],$Formulario[$i],$Formulario['esm_glosa_id']);
				$k++;
				}
					else
					{
					$j++;
					}
			}
			}
		}
		else
			{
			$objResponse->alert("Debe Seleccionar El Motivo de La Glosa");
			}
				
		if($j>0)
		{
		$objResponse->alert("Error En la Diligencia de #".$j." Registros");
		}
		if($k>0)
		{
		$objResponse->alert("Exito en Diligencia de #".$k." Registros");
		$objResponse->script("window.location=\"".$url."\";");
		}
		
		return $objResponse;
		}
 
 
  
   function Borrar_Item($orden_requisicion_tmp_id,$codigo_producto)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  
  $token=$sql->Borrar_Item($orden_requisicion_tmp_id,$codigo_producto);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'),'1');");
  $objResponse->script("xajax_Listado_Productos_TMP('".$orden_requisicion_tmp_id."');");
  }
  else
  $objResponse->alert("Error en el Borrado...!!");
  
  return $objResponse;
  }
  
 
 
   function Guardar_Cambios($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $k=0;
  for($i=0;$i<=$Formulario['registros'];$i++)
  {
    if($Formulario[$i]!="")
    {
          $token=$sql->Modificar_ProductoTemporal($Formulario['orden_requisicion_tmp_id'],$Formulario[$i],$Formulario['cantidad_solicitada'.$i]);
          $k++;
         if($token)
              $objResponse->script("document.getElementById('yes".$i."').style.backgroundColor='green';");
            else
            {
            $objResponse->script("document.getElementById('yes".$i."').style.backgroundColor='red';");
            }
    }
  }
  
  if($k>0)
  {
  //$objResponse->script("xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'),'1');");
  $objResponse->script("xajax_Listado_Productos_TMP('".$Formulario['orden_requisicion_tmp_id']."');");
  }
  
  if($token)
  {
  //$objResponse->script("Cerrar('Contenedor');");
 // $objResponse->script("xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,'1');");
 // $objResponse->script("xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,'1');");
  //$objResponse->alert("Proce Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  function CrearDocumento($orden_requisicion_tmp_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $DocTemporal=$sql->Obtener_InfoDocTemporal($orden_requisicion_tmp_id,$_REQUEST['datos']['empresa_id']);
  $DocTemporal_Detalle =$sql->Listado_ProductosTemporales($orden_requisicion_tmp_id);
  
  $token = $sql->Insertar_Documento($DocTemporal);
  if(!empty($token))
  {
    foreach($DocTemporal_Detalle as $key =>$valor)
    {
    $sql->Insertar_ProductoDocumento($token,$valor);
    }
  }
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Crear_OrdenesRequisicion")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    
    
    $script = "window.location=\"".$url."\";";
    
  
  if($token)
  {
  $direccion="app_modules/ESM_Facturacion/Imprimir/imprimir_producto.php";
  //$codigo = $VECTOR[$i]['codigo_producto'];//"themes/$VISTA/" . GetTheme() ."/images//imprimir.png";
  //$alt="VER INFORMACION DEL PRODUCTO";
  //$x=RetornarImpresionDoc1($direccion,$alt,$empresa_id,l_id);
  //              $salida .= "                       ".$x."";
  $objResponse->script("Imprimir('".$direccion."','".$_REQUEST['datos']['empresa_id']."','".$token['orden_requisicion_id']."');");
  $sql->BorrarTemporal($orden_requisicion_tmp_id);
  $objResponse->script("alert(\"Exito al Crear El Documento!! Numero: #".$token['orden_requisicion_id']."\");");
  $objResponse->script($script);
  }
  else
    {
    $objResponse->script("alert(\"Error al Crear El Documento!!\");");
    }
  
  
  return $objResponse;
  }
  
  function RetornarImpresionDoc($direccion,$alt,$empresa_id,$centro_id,$bodega,$codigo,$fecha_inicio_lapso,$fecha_final_lapso,$tipo_movimiento,$tipo_doc_general_id)
    {    
        global $VISTA;
        $salida1 ="<a title='".$alt."' href=javascript:Imprimir1('$direccion','$empresa_id','$centro_id','$bodega','$codigo','$fecha_inicio_lapso','$fecha_final_lapso','$tipo_movimiento','$tipo_doc_general_id')>".$codigo."</a>";
        return $salida1;
    }
    
		function VerGlosa($esm_glosa_detalle_id,$esm_glosa_id)
		{
		$objResponse = new xajaxResponse();
		$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
		$DetalleGlosa=$sql->Seleccionar_DetalleGlosa($esm_glosa_detalle_id,$esm_glosa_id);
    //print_r($DetalleGlosa);
		
    $html .= "<form name=\"forma_glosa\" id=\"forma_glosa\" method=\"post\">";
		$html .= "<table width=\"95%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
		$html .= "  <tr class=\"modulo_table_list_title\">";
		$html .= "		<td>PRODUCTO</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['producto'];
		$html .= "	  </td>";
		$html .= "		<td>MOTIVO GLOSA</td>";
		$html .= "	  <td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['motivo_glosa_descripcion'];
		$html .= "		</td>";
		$html .= "	</tr>";
    $html .= "  <tr class=\"modulo_table_list_title\">";
		$html .= "		<td>CONCEPTO GENERAL</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['descripcion_concepto_general'];
		$html .= "	  </td>";
		$html .= "		<td>CONCEPTO ESPECIFICO</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['descripcion_concepto_especifico'];
		$html .= "	  </td>";
		$html .= "	</tr>";
		$html .= "	<tr class=\"modulo_table_list_title\">";
		$html .= "	  <td colspan=\"2\">VALOR GLOSA</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">";
		$html .= "			$".FormatoValor($DetalleGlosa['valor_glosa'],2);
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
    
    $html .= "	      <fieldset class=\"fieldset\">\n";
    $html .= "          <legend class=\"normal_10AN\">GLOSA</legend>\n";
    $html .= "            <table width=\"95%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
    $html .= "             <tr class=\"modulo_table_list_title\">";
		$html .= "		          <td>VALOR GLOSA</td>";
    $html .= "		          <td></td>";
    $html .= "		          <td>VALOR ACEPTADO</td>";
    $html .= "		          <td></td>";
    $html .= "		          <td>VALOR NO ACEPTADO</td>";
    $html .= "            </tr>";
    $html .= "             <tr class=\"modulo_list_claro\" align=\"center\">";
    $html .= "		          <td>$".FormatoValor($DetalleGlosa['valor_glosa'],2)."</td>";
    $html .= "		          <td>";
    $html .= "			          <a onclick=\"Asignar('valor_aceptado','".$DetalleGlosa['valor_glosa']."');\" >";
    $html .= "			          <img title=\"Valor Glosa\" src=\"".GetThemePath()."/images/hcright.png\" border=\"0\">";
    $html .= "			          </a>";
    $html .= "              </td>";
    $html .= "		          <td><input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"valor_aceptado\" id=\"valor_aceptado\" value=\"".$DetalleGlosa['valor_aceptado']."\" onkeypress=\"return acceptNum(event);\"></td>";
    $html .= "		          <td>";
    $html .= "			          <a onclick=\"CalculoNoAceptado('valor_no_aceptado','".$DetalleGlosa['valor_glosa']."',document.getElementById('valor_aceptado').value);\" >";
    $html .= "			          <img title=\"Valor Glosa\" src=\"".GetThemePath()."/images/hcright.png\" border=\"0\">";
    $html .= "			          </a>";
    $html .= "              </td>";
    $html .= "		          <td><input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"valor_no_aceptado\" id=\"valor_no_aceptado\" value=\"".$DetalleGlosa['valor_no_aceptado']."\" onkeypress=\"return acceptNum(event);\">";
    $html .= "              <input type=\"hidden\" value=\"".$esm_glosa_detalle_id."\" name=\"esm_glosa_detalle_id\" id=\"esm_glosa_detalle_id\">";
    $html .= "              <input type=\"hidden\" value=\"".$esm_glosa_id."\" name=\"esm_glosa_id\" id=\"esm_glosa_id\">";
    $html .= "              </td>";
    $html .= "             </tr>";
    $html .= "             <tr class=\"modulo_list_claro\" align=\"center\">";
    $html .= "                <td colspan=\"5\">";
    if($DetalleGlosa['sw_estado']!='3')
    $html .= "                  <input type=\"button\" value=\"ACEPTAR GLOSA\" onclick=\"Validar_AceptarGlosa(xajax.getFormValues('forma_glosa'));\" class=\"input-submit\">";
    $html .= "                  <input type=\"button\" value=\"ANULAR GLOSA\" onclick=\"Validar_AnularGlosa(xajax.getFormValues('forma_glosa'));\" class=\"input-submit\">";
    $html .= "                  <input type=\"hidden\" value=\"0\" name=\"sw_glosa_total_factura\" id=\"sw_glosa_total_factura\">";
    $html .= "                </td>";
    $html .= "             </tr>";
    $html .= "          </table>";
    $html .= "        </fieldset>";
    
		$html .= "</form>";
						
		$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
		$objResponse->script("MostrarSpan();");


		return $objResponse;
		}
  
  function VerGlosa_Total($esm_glosa_id)
		{
		$objResponse = new xajaxResponse();
		$sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
		$DetalleGlosa=$sql->Buscar_GlosaActiva($esm_glosa_id);
    //print_r($DetalleGlosa);
		
    $html .= "<form name=\"forma_glosa\" id=\"forma_glosa\" method=\"post\">";
		$html .= "<table width=\"95%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
		$html .= "  <tr class=\"modulo_table_list_title\">";
		$html .= "		<td colspan=\"2\">Glosa Total</td>";
		$html .= "		<td>MOTIVO GLOSA</td>";
		$html .= "	  <td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['motivo_glosa_descripcion'];
		$html .= "		</td>";
		$html .= "	</tr>";
    $html .= "  <tr class=\"modulo_table_list_title\">";
		$html .= "		<td>CONCEPTO GENERAL</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['descripcion_concepto_general'];
		$html .= "	  </td>";
		$html .= "		<td>CONCEPTO ESPECIFICO</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\">";
		$html .= "			".$DetalleGlosa['descripcion_concepto_especifico'];
		$html .= "	  </td>";
		$html .= "	</tr>";
		$html .= "	<tr class=\"modulo_table_list_title\">";
		$html .= "	  <td colspan=\"2\">VALOR GLOSA</td>";
		$html .= "		<td class=\"modulo_list_claro\" align=\"left\" colspan=\"2\">";
		$html .= "			$".FormatoValor($DetalleGlosa['valor_glosa'],2);
		$html .= "		</td>";
		$html .= "	</tr>";
		$html .= "</table>";
    
    $html .= "	      <fieldset class=\"fieldset\">\n";
    $html .= "          <legend class=\"normal_10AN\">GLOSA</legend>\n";
    $html .= "            <table width=\"95%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
    $html .= "             <tr class=\"modulo_table_list_title\">";
		$html .= "		          <td>VALOR GLOSA</td>";
    $html .= "		          <td></td>";
    $html .= "		          <td>VALOR ACEPTADO</td>";
    $html .= "		          <td></td>";
    $html .= "		          <td>VALOR NO ACEPTADO</td>";
    $html .= "            </tr>";
    $html .= "             <tr class=\"modulo_list_claro\" align=\"center\">";
    $html .= "		          <td>$".FormatoValor($DetalleGlosa['valor_glosa'],2)."</td>";
    $html .= "		          <td>";
    $html .= "			          <a onclick=\"Asignar('valor_aceptado','".$DetalleGlosa['valor_glosa']."');\" >";
    $html .= "			          <img title=\"Valor Glosa\" src=\"".GetThemePath()."/images/hcright.png\" border=\"0\">";
    $html .= "			          </a>";
    $html .= "              </td>";
    $html .= "		          <td><input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"valor_aceptado\" id=\"valor_aceptado\" value=\"".$DetalleGlosa['valor_aceptado']."\" onkeypress=\"return acceptNum(event);\"></td>";
    $html .= "		          <td>";
    $html .= "			          <a onclick=\"CalculoNoAceptado('valor_no_aceptado','".$DetalleGlosa['valor_glosa']."',document.getElementById('valor_aceptado').value);\" >";
    $html .= "			          <img title=\"Valor Glosa\" src=\"".GetThemePath()."/images/hcright.png\" border=\"0\">";
    $html .= "			          </a>";
    $html .= "              </td>";
    $html .= "		          <td><input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"valor_no_aceptado\" id=\"valor_no_aceptado\" value=\"".$DetalleGlosa['valor_no_aceptado']."\" onkeypress=\"return acceptNum(event);\">";
    $html .= "              <input type=\"hidden\" value=\"".$esm_glosa_detalle_id."\" name=\"esm_glosa_detalle_id\" id=\"esm_glosa_detalle_id\">";
    $html .= "              <input type=\"hidden\" value=\"".$esm_glosa_id."\" name=\"esm_glosa_id\" id=\"esm_glosa_id\">";
    $html .= "              <input type=\"hidden\" value=\"1\" name=\"sw_glosa_total_factura\" id=\"sw_glosa_total_factura\">";
    $html .= "              </td>";
    $html .= "             </tr>";
    $html .= "             <tr class=\"modulo_list_claro\" align=\"center\">";
    $html .= "                <td colspan=\"5\">";
    if($DetalleGlosa['sw_estado']!='3')
    $html .= "                  <input type=\"button\" value=\"ACEPTAR GLOSA\" onclick=\"Validar_AceptarGlosa(xajax.getFormValues('forma_glosa'));\" class=\"input-submit\">";
    //$html .= "                  <input type=\"button\" value=\"ANULAR GLOSA\" onclick=\"Validar_AnularGlosa(xajax.getFormValues('forma_glosa'));\" class=\"input-submit\">";
    $html .= "                </td>";
    $html .= "             </tr>";
    $html .= "          </table>";
    $html .= "        </fieldset>";
    
		$html .= "</form>";
						
						
		$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
		$objResponse->script("MostrarSpan();");


		return $objResponse;
		}
  
  function AceptarGlosaDetalle($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
  $token = $sql->AceptarGlosa($Formulario);
  if($token)
    {
    $objResponse->alert("Se Ha Aceptado la Glosa!!");
		$objResponse->script("window.location=\"".$url."\";");
    }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  function AnularGlosaDetalle($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
  $token = $sql->AnularGlosaDetalle($Formulario);
  if($token)
    {
    $objResponse->alert("Se Ha Anulado la Glosa!!");
		$objResponse->script("window.location=\"".$url."\";");
    }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  function Cambiar_TipoGlosa($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
  $token = $sql->Cambiar_TipoGlosa($Formulario);
  if($token)
    {
    $objResponse->alert("Se Ha Anulado la Glosa!!");
		$objResponse->script("window.location=\"".$url."\";");
    }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
    function AceptarGlosaTotal($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Modificar_Glosa")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
  $token = $sql->Aceptar_GlosaTotal($Formulario);
  if($token)
    {
    $objResponse->alert("Se Ha Aceptado la Glosa!!");
		$objResponse->script("window.location=\"".$url."\";");
    }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  function AplicarGlosaGeneral($esm_glosa_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $DetalleGlosa=$sql->Buscar_GlosaActiva($esm_glosa_id);
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Glosas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
  //print_r($DetalleGlosa);
  if($DetalleGlosa['valor_glosa']>0)
	{
		if($DetalleGlosa['valor_aceptado']>0 || $DetalleGlosa['valor_no_aceptado']>0)
		{
			if($DetalleGlosa['sw_mayor_valor']=='1') //Aplicar Nota Credito
			{
			$DocumentoNota=$sql->DocumentoNota($_REQUEST['datos']['empresa_id'],'1');
			$tabla = "esm_notas_credito_glosas";
			$signo= "-";
			}
				else
				{
				$DocumentoNota=$sql->DocumentoNota($_REQUEST['datos']['empresa_id'],'0');
				$tabla = "esm_notas_debito_glosas";
				$signo= "+";
				}
			if(!empty($DocumentoNota))
			{
			$token = $sql->GuardarTransaccion($DetalleGlosa,$DocumentoNota,$tabla,$signo);
			}	
	
		}
	}
  
  if($token)
  {
  $objResponse->alert("Se Ha Creado La Glosa!!");
  $objResponse->script("window.location=\"".$url."\";");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");

  return $objResponse;
  }
  
  
  function AnularGlosa($esm_glosa_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM_Facturacion","classes","app","ESM_Facturacion");
  $url = ModuloGetURL("app","ESM_Facturacion","controller","Glosas")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."&esm_glosa_id=".$_REQUEST['esm_glosa_id']."";
  
  $token=$sql->AnularGlosa($esm_glosa_id);
  
  if($token)
  {
  $objResponse->alert("Se Ha Anulado La Glosa!!");
  $objResponse->script("window.location=\"".$url."\";");
  }
  else
  $objResponse->alert("Error!!");

  return $objResponse;
  }
  
?>
