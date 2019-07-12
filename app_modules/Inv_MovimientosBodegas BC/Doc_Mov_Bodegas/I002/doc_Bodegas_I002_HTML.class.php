<?php
	/**************************************************************************************  
	* $Id: doc_Bodegas_I002_HTML.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/

include "app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I002/doc_Bodegas_I002.class.php";

class doc_bodegas_I002_HTML
{
	function doc_bodegas_I002_HTML(){}
	
	/*********************************************************************************
	Cabecera
	*********************************************************************************/ 
	function FormaDocumento($DATOS)
	{
		$obj=new classmodules();
		
    $file ="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/I002/RemoteXajax/definirBodegas_I002.php";
		$obj->SetXajax(array("OrdenesCompra","ListadoProductos","FormaConfirm",
    "CrearDocumento","FormaElimDocTemporal","NewDocumentoTmp",
    "AgregarItem","EliminarItem","GetItems",
    "EliminarDocTemporal","Ingreso_factura","AdicionarNuevoLote","InsertarProductoOrdenCompra",
    "IngresarProductosFueraOrdenCompra","BuscarProductos","FormaAdicionarProducto","AgregarItemFOC",
	"Actualizartmp","MarcarINC","FormaActa","RegistrarActaTecnica","ActasTecnicas"),$file,"ISO-8859-1");

    //print_r($_REQUEST);
		switch($DATOS['accion'])
		{
			case 'NUEVO_TMP':
				return $this->FormaDocNuevo($DATOS);
			break;
		
			case 'EDITAR':
				return $this->FormaDocEditar($DATOS);
			break;
		}
		
		return $salida;
	}
	
	function FormaDocNuevo($DATOS)
	{
		$consulta = new doc_bodegas_I002();
		$datosCabecera = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
		$datosProveedor = $consulta->GetProveedores();
		
		$salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO");
		$salida .= $this->frmError['MensajeError'];
		$salida .= $this->Cabecera($datosCabecera,"N");
		$salida .= "<br>".$this->SeleccionProveedorOrden($datosProveedor,$DATOS);
		$salida .= "<br>".$this->FormaVolver($DATOS);
		$salida .= ThemeCerrarTabla();

		return $salida;
	}
	
	function FormaDocEditar($DATOS)
	{
	
		$consulta = new doc_bodegas_I002();
		$datosCabecera = $consulta->TraerInfoDocTmp($DATOS['bodegas_doc_id'],$DATOS['doc_tmp_id']);
		$datosCabecera['ADIC'] = $consulta->TraerGetDocTemporal($DATOS['bodegas_doc_id'],$DATOS['doc_tmp_id']);
    $datos = $consulta->TraerDatos($DATOS['bodegas_doc_id']);
    //print_r($datos);
		$consulta1 = new MovBodegasSQL();
      /*$si_esta=$consulta1->ConsultaPardocg($DATOS['tipo_doc_bodega_id'],$DATOS['doc_tmp_id']);
      $param_estados=$consulta1->ConsultaEstadosPermisos($DATOS['tipo_doc_bodega_id']);
      if(empty($si_esta))
      {
        foreach ($param_estados as $indice=>$valor)
        { 
          $guadarpar=$consulta1->GuardarParGrabar($DATOS['tipo_doc_bodega_id'],$valor['abreviatura'],$DATOS['doc_tmp_id']);
        }
      }*/
		$salida .= ThemeAbrirTabla("TIPO DE DOCUMENTO");
		$salida .= $this->frmError['MensajeError'];
		$salida .= $this->Cabecera($datosCabecera,"E");
		$salida .= "<br>".$this->SeleccionProductos($datos,$DATOS);
		$salida .= "<br>".$this->FormaCrearDocumento($DATOS,$datos);
		$salida .= ThemeCerrarTabla();
		
		return $salida;
	}
	
	function FormaVolver($DATOS)
	{
		$accion= ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('bodegax'=>$DATOS['bodega'],'nom_bodegax'=> $DATOS['nom_bodega'],'utility'=>$DATOS['utility']));
		SessionSetVar("accion",$accion);
		$salida .= "<form action=\"$accion\" name=\"formaV\" method=\"post\">";
		$salida .= "	<table width=\"100%\" align=\"center\">";
		$salida .= "		<tr align=\"center\">";
		$salida .= "			<td><input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
		$salida .= "		</tr>";
		$salida .= "	</table>";
		$salida .= "</form>";
		return $salida;
	}

	function Cabecera($datosCabecera,$sw)
	{	
	SessionSetVar("Empresa_id",$datosCabecera['empresa_id']);
	SessionSetVar("centro_utilidad",$datosCabecera['centro_utilidad']);
	SessionSetVar("bodega",$datosCabecera['bodega']);
    /*$sql = AutoCarga::factory("MovDocI002","classes","app","Inv_MovimientosBodegas");	*/
		
    
    /*print_r($datosCabecera);*/
    if($sw=="N")
		{
			$consulta = new MovBodegasSQL();
			$salida .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				EMPRESA";
			$salida .= "			</td>";
			$nombreempresa=$consulta->ColocarEmpresa($datosCabecera['empresa_id']);
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "				".$nombreempresa[0]['razon_social']."";
			$salida .= "			</td>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$nombrebodega=$consulta->bodegasname($datosCabecera['bodega']);
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				BODEGA";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['bodega']."  -  ".$nombrebodega[0]['descripcion'];
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				TIPO CLASE DE DOCUMENTO";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['tipo_clase_documento']."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				TIPO DE MOVIMIENTO";
			$salida .= "			</td>";
			if($datosCabecera['tipo_movimiento']=='I')
				$tipo="ENTRADA DE ALMACEN";
			else
				$tipo="SALIDA DE ALMACEN";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "				$tipo";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				PEFIJO";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['prefijo']."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "	</table>";
		}
		elseif($sw=="E")
		{
			$salida .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				DOC TMP ID";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "				".$datosCabecera['doc_tmp_id']."";
			$salida .= "			</td>";
      
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				BODEGA DOC ID";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['bodegas_doc_id']."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				TIPO CLASE DE DOCUMENTO";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['tipo_clase_documento']."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				OBSERVACION";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "				".$datosCabecera['observacion']."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				FECHA REGISTRO";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".substr($datosCabecera['fecha_registro'],0,10)."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				PREFIJO";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['prefijo']."";
			$salida .= "			</td>";
			$salida .= "		</tr>";
			/*$OrdenDeCompra = $sql->DocumentoTempIngresoCompras($datosCabecera['doc_tmp_id']);
			$CodigoProveerdorId = $sql->ConsultaProveedorOC($OrdenDeCompra[0]['orden_pedido_id'],$datosCabecera['empresa_id']);
			$Proveedor = $sql->TerceroProveedor($CodigoProveerdorId[0]['codigo_proveedor_id']);*/
			//print_r($Tercero);
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				PROVEEDOR";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['ADIC']['tipo_id_tercero']."-".$datosCabecera['ADIC']['tercero_id']."::-".$datosCabecera['ADIC']['nombre_tercero'];
			$salida .= "			</td>";
			$salida .= "		</tr>";
			$salida .= "		<tr class=\"modulo_list_claro\">\n";
			$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$salida .= "				ORDEN DE COMPRA";
			$salida .= "			</td>";
			$salida .= "			<td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
			$salida .= "			".$datosCabecera['ADIC']['orden_pedido_id'];
			$salida .= "			</td>";
			$salida .= "		</tr>";
     		$salida .= "	</table>";
			//print_r($datosCabecera);
			$salida .= "  ";
			$salida .= "	<input type=\"hidden\" id=\"observacion\" value=\"".$datosCabecera['observacion']."\">";
			$salida .= "	<input type=\"hidden\" id=\"orden\"  value=\"".$datosCabecera['ADIC']['orden_pedido_id']."\">";
			$salida .= "	<input type=\"hidden\" id=\"doc_tmp_id\"  value=\"".$datosCabecera['doc_tmp_id']."\">";
			$salida .= "	<input type=\"hidden\" id=\"bodegas_doc_id\"  value=\"".$datosCabecera['bodegas_doc_id']."\">";
      	}
	
		return $salida;
	}
	
	function SeleccionProveedorOrden($datosProveedor,$DATOS)
	{
    
		$accion= ModuloGetURL('app','Inv_MovimientosBodegas','user','DirectorDocumentos');
		$salida .= "<div id=\"mensaje_error\" class=\"label_error\" align=\"center\"></div>\n";
		
		$salida .= "<form action=\"$accion\" name=\"forma1\" method=\"post\">";
		$salida .= "	<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";
		$salida .= "		<tr class=\"modulo_list_claro\">\n";
		$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
		$salida .= "				PORVEEDOR";
		$salida .= "			</td>";
		$salida .= "			<td width=\"20%\" align=\"left\" class=\"normal_10AN\">\n";
		$salida .= "				<select id=\"proveedor\" name=\"proveedor\" class=\"select\" onchange=\"xajax_OrdenesCompra(xGetElementById('proveedor').value);\">";
		$salida .= "					<option value=\"\">---SELECCIONE PROVEEDOR---</option>";
		foreach($datosProveedor as $key=>$valor)
			$salida .= "					<option value=\"".$valor['codigo_proveedor_id']."\">".$valor['nombre_tercero']."</option>";
		$salida .= "				</select>";
		$salida .= "			</td>";
		$salida .= "			<td width=\"20%\" rowspan=\"2\" align=\"center\" class=\"normal_10AN\">\n";
		$salida .= "				<fieldset>";
		$salida .= "					<legend>OBSERVACION </legend>";
		$salida .= "					<textarea id=\"observacion\" name=\"observacion\" rows=\"3\" cols=\"70\" class=\"textarea\"></textarea>";
		$salida .= "				</fieldset>";
		$salida .= "			</td>";
		$salida .= "		</tr>";
		$salida .= "		<tr class=\"modulo_list_claro\">\n";
		$salida .= "			<td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
		$salida .= "				ORDEN DE COMPRA";
		$salida .= "			</td>";
		$salida .= "			<td width=\"20%\" align=\"left\" class=\"normal_10AN\">\n";
		$salida .= "				<select id=\"orden\" name=\"orden_compra\" class=\"select\">";
		$salida .= "					<option value=\"\">---SELECCIONE NUMERO DE ORDEN---</option>";
		$salida .= "				</select>";
		$salida .= "			</td>";
		$salida .= "		</tr>";
		$salida .= "		<tr class=\"modulo_list_claro\">\n";
		$salida .= "			<td width=\"15%\" colspan=\"3\" align=\"center\" class=\"modulo_list_claro\">\n";
		$salida .= "				<input type=\"button\" name=\"guardartmp\" class=\"input-submit\" value=\"GRABAR DOCUMENTO\" onclick=\"NewDocumentoTmp(xGetElementById('observacion').value,xGetElementById('orden').value,'".$DATOS['bodegas_doc_id']."','".$DATOS['tipo_doc_bodega_id']."');\">";
		$salida .= "			</td>";
		$salida .= "		</tr>";
		$salida .= "	</table>";
		$salida .= "	<input type=\"hidden\" id=\"bodegas_doc_id\">";
		$salida .= "	<input type=\"hidden\" id=\"tipo_doc_bodega_id\">";
		$salida .= "	<input type=\"hidden\" id=\"doc_tmp_id\">";
		$salida .= "</form>";
		
		return $salida;
	}
	
	function SeleccionProductos($datos,$DATOS)
	{
    $consulta = new MovBodegasSQL();
	  IncludeClass("CalendarioHtml");
    $salida .= "<script>";
    $salida .= "function Desaparecer()";
    $salida .= "{";
    $salida .= "document.getElementById('productos_ordenCompra').innerHTML = \"\";";
    $salida .= "}";
    $salida .= "</script>";
    for($i=0;$i<200;$i++)
    $salida .= ReturnOpenCalendarioScript("forma_checks","fecha_vencimiento$i",'-')."\n";
    
    
    for($i=1001;$i<1150;$i++)
    $salida .= ReturnOpenCalendarioScript("Formafecha_vencimiento$i","fecha_vencimiento$i",'-')."\n";
    
                         
    $salida .= "<div id=\"ventanauno\">";
		$salida .= "</div>";
		    
    
    
    
		$salida .= "<div id=\"ventanados\">";
		$salida .= "<table width=\"90%\" align=\"center\">";
		$salida .= "	<tr>";
		$salida .= "			<td width=\"90%\" align=\"left\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
		//$java = "javascript:MostrarSpan('d2Container');Iniciar('BUSCAR PRODUCTO');BuscarProductos('1',xGetElementById('orden').value,'0','0',xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);\"";
		$java = "javascript:BuscarProductos('1',xGetElementById('orden').value,'0','0',xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);\"";
		$salida .= "				<a title=\"BUSCADOR PRODUCTO\" class=\"label_error\" href=\"".$java."\">\n";
		$salida .= "					BUSCAR PRODUCTO\n";
		$salida .= "				</a>\n";
		$salida .= "			</td>\n";
		$salida .= "	</tr>";

		$salida .= "</table>";

		$salida .= "	<div id=\"listadoP\">\n";
		$salida .= "		<script>\n";
		$salida .= "			xajax_GetItems(xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);\n";
		$salida .= "		</script>\n";
		$salida .= "	</div>";
		$salida .= "</div>";
		
		$salida.="<div id=\"d2Container\" class=\"d2Container\" style=\"display:none\">";
		$salida .= "    <div id=\"titulo\" class=\"draggable\" style=\"text-transform: uppercase;\"></div>\n";
		$salida .= "    <div id=\"cerrar\" class=\"draggable\"> <a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container'),Desaparecer('');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$salida .= "    <div id=\"error\" class=\"label_error\" style=\"text-transform: uppercase; text-align:center;\"></div>\n";
		$salida .= "    <div id=\"d2Contents\">\n";
    
    
		$salida .= "			<input type=\"hidden\" id=\"empresa_idz\" value=\"".$datos['empresa_id']."\">\n";
		$salida .= "			<input type=\"hidden\" id=\"centro_utilidadz\" value=\"".$datos['centro_utilidad']."\">\n";
		$salida .= "			<input type=\"hidden\" id=\"bodegaz\" value=\"".$datos['bodega']."\">\n";
		$salida .= "			<input type=\"hidden\" id=\"pagina\" value=\"1\">\n";
		$salida .= "			<form name=\"jukilo\" action=\"".$accion1."\" method=\"post\">\n";
		
    $salida .= "				<table width=\"90%\" align=\"center\" class=\"modulo_table_list\">\n";         
		$salida .= "					<tr class=\"modulo_table_list_title\">\n";
		$salida .= "						<td COLSPAN=\"2\" align=\"center\">\n";
		$salida .= "							BUSCADOR DE PRODUCTOS";
		$salida .= "						</td>\n";
		$salida .= "					</tr>\n";
		$salida .= "					<tr class=\"modulo_list_claro\">\n";
		$salida .= "						<td width=\"35%\" align=\"center\">\n";
		$salida .= "							TIPO DE BUSQUEDA";
		$salida .= "							<select id=\"tip_bus\" name=\"tip_bus\" class=\"select\" onchange=\"Aplicar(this.value)\">";
		$salida .= "								<option value=\"1\" SELECTED>DESCRIPCION</option> \n";
		$salida .= "                <option value=\"2\"># CODIGO</option> \n";
		$salida .= "              </select>\n";
		$salida .= "						</td>\n";
		$salida .= "						<td width=\"55%\" align=\"left\" id=\"ventanatabla\">\n";
		$salida .= "							DESCRIPCION";                                                                                                             
		$salida .= "							<input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"50\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
		$salida .= "						</td>\n";
		$salida .= "					</tr>\n";
		$salida .= "				</table>\n";
		$salida .= "			</form>\n";
		$salida .= "			<br>\n";
		//$salida .= "<div id=\"cabecera_facturaventa\"></div>";
		$salida .= "      <div id=\"listado_pro\">";
		$salida .= "      </div>\n";
		$salida .= "   </div>\n";     
		$salida .= "</div>";
    
		$salida .= "<div id=\"d2Container2\" class=\"d2Container\" style=\"display:none\">";
		$salida .= "    <div id=\"titulo2\" class=\"draggable\" style=\"text-transform: uppercase;\"></div>\n";
		$salida .= "    <div id=\"cerrar2\" class=\"draggable\"> <a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container2');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$salida .= "    <div id=\"error2\" class=\"label_error\" style=\"text-transform: uppercase; text-align:center;\"></div>\n";
		$salida .= "    <div id=\"d2Contents2\">\n";
		$salida .= "    </div>\n";
		$salida .= "</div>\n";
		
		$salida .= "<script>\n";
		$salida .= "	 	var contenedor = '';\n";
		$salida .= "		var titulo = '';\n";
		$salida .= "	 	var hiZ=2;\n";
		
		$salida .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
		$salida .= "	{\n";
		$salida .= "		document.getElementById(campo).style.background='';\n";
		$salida .= "		document.getElementById('error').innerHTML='';\n";
		$salida .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
		$salida .= "		{\n";
		$salida .= "			document.getElementById(campo).value='';\n";
		$salida .= "			document.getElementById(campo).style.background='#ff9595';\n";
		$salida .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
		$salida .= "			document.getElementById(capa).style.display=\"none\"\n";
		$salida .= "		}\n";
		$salida .= "		else{\n";
		$salida .= "			document.getElementById(capa).style.display=\"\"\n";
		$salida .= "		}\n";
		$salida .= "	}\n";
		$tmn = "600";
    $tmny = "700";
    //$salida .= "    ele = xGetElementById('d2Contents2');\n";
    
		$salida .= "	function Iniciar(tit)\n";
		$salida .= "	{\n";
		$salida .= "	 	contenedor = 'd2Container';\n";
		$salida .= "		titulo = 'titulo';\n";
		$salida .= "		document.getElementById('error').innerHTML = '';\n";
		$salida .= "		document.getElementById('criterio').value = '';\n";
		$salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$salida .= "		ele = xGetElementById(contenedor);\n";
		$salida .= "	  xMoveTo(ele, xClientWidth()/8, xScrollTop()+50);\n";
	//$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
  	$salida .= "	  xResizeTo(ele,700,'auto');\n";
		$salida .= "		ele = xGetElementById('d2Contents');\n";
		//$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
    $salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		$salida .= "	  xResizeTo(ele,800, 'auto');\n";
		$salida .= "		ele = xGetElementById(titulo);\n";
		$salida .= "	  xResizeTo(ele,700, 20);\n";
		$salida .= "		xMoveTo(ele, 0, 0);\n";
		$salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$salida .= "		ele = xGetElementById('cerrar');\n";
		$salida .= "	  xResizeTo(ele,20, 20);\n";
		//$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
    $salida .= "		xMoveTo(ele, 680, 0);\n";
		$salida .= "ele = xGetElementById('d2Contents2');\n";
    //$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
    $salida .= "	}\n";
		
		
    
    $salida .= "	function Iniciar2(tit)\n";
		$salida .= "	{\n";
		$salida .= "	 	contenedor = 'd2Container2';\n";
		$salida .= "		titulo = 'titulo2';\n";
		$salida .= "		document.getElementById('error2').innerHTML = '';\n";
		$salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$salida .= "		ele = xGetElementById(contenedor);\n";
		$salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+250);\n";
		$salida .= "	  xResizeTo(ele,200,'auto');\n";
    //$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
		    
    $salida .= "		ele = xGetElementById('d2Contents2');\n";
		
    $salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		
    $salida .= "	  xResizeTo(ele,200, 'auto');\n";
    //$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
		
    $salida .= "		ele = xGetElementById(titulo);\n";
		$salida .= "	  xResizeTo(ele,180, 20);\n";
		$salida .= "		xMoveTo(ele, 0, 0);\n";
		$salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$salida .= "		ele = xGetElementById('cerrar2');\n";
		//$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
    $salida .= "	  xResizeTo(ele,20, 20);\n";
		$salida .= "		xMoveTo(ele, 180, 0);\n";
		
    $salida .= "	}\n";
    
    
    $salida .= "	function Iniciar3(tit)\n";
		$salida .= "	{\n";
		$salida .= "	 	contenedor = 'd2Container2';\n";
		$salida .= "		titulo = 'titulo2';\n";
		$salida .= "		document.getElementById('error2').innerHTML = '';\n";
		$salida .= "		document.getElementById(titulo).innerHTML = tit;\n";
		$salida .= "		ele = xGetElementById(contenedor);\n";
		$salida .= "	  xMoveTo(ele, xClientWidth()/6, xScrollTop()+5);\n";
		$salida .= "	  xResizeTo(ele,900,'auto');\n";
    //$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
		    
    $salida .= "		ele = xGetElementById('d2Contents2');\n";
		
    $salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop());\n";
		
    $salida .= "	  xResizeTo(ele,900, 'auto');\n";
    //$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
		
    //Titulo
    $salida .= "		ele = xGetElementById(titulo);\n";
		$salida .= "	  xResizeTo(ele,850, 20);\n";
		$salida .= "		xMoveTo(ele, 0, 0);\n";
		$salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$salida .= "		ele = xGetElementById('cerrar2');\n";
		//$salida .= "          xResizeTo(ele,".$tmn.", ".($tmny-25).");\n";
    $salida .= "	  xResizeTo(ele,20, 20);\n";
		$salida .= "		xMoveTo(ele, 850 , 0);\n";
		
    $salida .= "	}\n";

		$salida .= "	function myOnDragStart(ele, mx, my)\n";
		$salida .= "	{\n";
		$salida .= "	  window.status = '';\n";
		$salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$salida .= "	  else xZIndex(ele, hiZ++);\n";
		$salida .= "	  ele.myTotalMX = 0;\n";
		$salida .= "	  ele.myTotalMY = 0;\n";
		$salida .= "	}\n";
		
		$salida .= "	function myOnDrag(ele, mdx, mdy)\n";
		$salida .= "	{\n";
		$salida .= "	  if (ele.id == titulo) {\n";
		$salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$salida .= "	  }\n";
		$salida .= "	  else {\n";
		$salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$salida .= "	  }  \n";
		$salida .= "	  ele.myTotalMX += mdx;\n";
		$salida .= "	  ele.myTotalMY += mdy;\n";
		$salida .= "	}\n";
		$salida .= "	function myOnDragEnd(ele, mx, my)\n";
		$salida .= "	{}\n";
		
		$salida .= "	function MostrarSpan(Seccion)\n";
		$salida .= "	{\n";
		$salida .= "		e = xGetElementById(Seccion);\n";
		$salida .= "		e.style.display = \"\";\n";
		$salida .= "	}\n";
		$salida .= "	function Cerrar(Seccion)\n";
		$salida .= "	{ \n";
		$salida .= "		e = xGetElementById(Seccion);\n";
		$salida .= "		e.style.display = \"none\";\n";
		$salida .= "	}\n";

		$salida .= "</script>\n";
		
    $salida .= "      <div id=\"productos_ordenCompra\">";
		$salida .= "      </div>\n";
    
     /*
    * Para Productos que no están en una orden de compra
    * Bajo un Esquema xajax.
    */
    $salida .= "<table width=\"90%\" align=\"center\">";
		$salida .= "	<tr>";
		$salida .= "			<td width=\"90%\" align=\"left\" class=\"modulo_list_claro\" class=\"normal_10AN\" >\n";
		$salida .= "<input type=\"hidden\" id=\"proveedor\">";
		$salida .= "<a name=\"#ProductosNoRelacionados\"></a>";
    $java1 = "xajax_IngresarProductosFueraOrdenCompra('1',xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value,xGetElementById('proveedor').value,xGetElementById('orden').value);";
		$salida .= "				<a title=\"BUSCADOR PRODUCTO\" id=\"link_foc\" class=\"label_error\" href=\"#ProductosNoRelacionados\" onclick=\"".$java1."\">\n";
		$salida .= "					INGRESAR PRODUCTOS NO PRESENTES EN LA ORDEN DE COMPRA\n";
		$salida .= "				</a>\n";
		$salida .= "			</td>\n";
		$salida .= "	</tr>";
		$salida .= "</table>";
    $salida .= "<div id=\"ProductosFueraOrdenCompra\"></div>";
    
    
    
    
		return $salida;
	}
	
	function FormaCrearDocumento($DATOS,$datos)
	{
		$accion = SessionGetVar("accion");
	  $accion1 = ModuloGetURL('app','Inv_MovimientosBodegas','user','DocumentosBodega',array('bodegax'=>$datos['bodega'],'nom_bodegax'=> "Bodega",'utility'=>$datos['centro_utilidad']));
    //print_r($DATOS);
    
		$salida .= "<table width=\"40%\" align=\"center\">";
		$salida .= "	<tr>";
		$salida .= "				<td width=\"33%\" id=\"elimnDoc\" align=\"center\" class=\"normal_10AN\" >\n";
		$salida .= "					<input type=\"button\" name=\"eliminar\" value=\"ELIMINAR DOCUMENTO\" class=\"input-submit\" onclick=\"Iniciar2('CONFIRMACION DE ELIMINAR');MostrarSpan('d2Container2');xajax_FormaElimDocTemporal(xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value,'$accion');\">\n";
		$salida .= "				</td>\n";
		$salida .= "			  <form name=\"forma_volver\" action=\"".$accion1."\" method=\"post\">\n";
		$salida .= "				  <td width=\"33%\" align=\"center\" class=\"normal_10AN\" >\n";
		$salida .= "					  <input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\">\n";
		$salida .= "				  </td>\n";
		$salida .= "			  </form>\n";
		$salida .= "			  <form name=\"formaC\" action=\"$accion\" method=\"post\">\n";
		$salida .= "				  <td width=\"34%\" id=\"crearDoc\" align=\"center\" class=\"normal_10AN\" style=\"display:none\">\n";
		$salida .= "				    <input type=\"button\" name=\"crear\" value=\"CREAR DOCUMENTO\" class=\"input-submit\" onclick=\"xajax_CrearDocumento(xGetElementById('doc_tmp_id').value,xGetElementById('bodegas_doc_id').value);\">\n";
		$salida .= "				  </td>\n";
		$salida .= "			  </form>\n";
		$salida .= "	</tr>";
		$salida .= "</table>";
		
		return $salida;
	}
}
?>