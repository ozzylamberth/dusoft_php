<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: 
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: CrearNotasHTML
  * Clase Contiene La Interfaz para la creacion de Notas Deb
  *
  * @package IPSOFT-SIIS
  * @version $Revision:
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class CrearNotasHTML
	{
		/**
		* Constructor de la clase
		*/
		function CrearNotasHTML(){}
		 
     
		//function main($action,$request,$SelectTipoId,$documentos)
		function main($action,$request,$TiposIdTerceros,$datos,$conteo, $pagina)
		{
    $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
    /*
    Seccion de Funciones Javascript
    */
    $html .="<script>";
    $html .="function Paginador(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset)";
    $html .="{";
    $html .="xajax_Listar_TercerosProveedores(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset);";
    $html .="}";
    $html .="</script>";
	
	$html .="<script>";
	$html .="function Validar(Formulario)";
	$html .="{";
	$html .="			if (Formulario.justificacion ==\"\")";
	$html .="			{";
	$html .="			alert('Es Necesario Justificar La Anulacion de La Nota');";
	$html .="			return false;";
	$html .="			}";
	$html .="";
	$html .="			xajax_AplicarAnulacionNota(Formulario);";
	$html .="}";
	$html .="</script>";
	    
    /*
    Fin de Funciones JavaScript
    */
	      $SelectTipoId ="<select class=\"select\" id=\"tipo_id_tercero\" name=\"buscador[tipo_id_tercero]\">";
        $SelectTipoId.="<option value=\"\"></option>";
        foreach($TiposIdTerceros as $key=>$tit)
        {
          if($request['tipo_id_tercero']==$tit['tipo_id_tercero'])
            $selected = " selected ";
            else
            $selected = " ";
          $SelectTipoId.="<option ".$selected." value=\"".$tit['tipo_id_tercero']."\">";
          $SelectTipoId.="".$tit['tipo_id_tercero'];
          $html.="</option>";
        }
        $SelectTipoId.="</select>";
  
    $accion=$action['volver'];
		$html .= ThemeAbrirTabla('NOTAS DEBITO - CREDITO: FACTURAS DE PROVEEDORES');
		$html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "    <form name=\"buscador_facturas\" action=\"".$action['buscar']."\" method=\"post\">";
    $html .= "      <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"6\">";
		$html .= "      BUSCAR PROVEEDORES";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Tipo Id";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$SelectTipoId;
		$html .= "      </td>";
		
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Tercero Id";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"buscador[tercero_id]\" value=\"".$request['tercero_id']."\">";
		$html .= "      </td>";
		
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Nombre Tercero";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"buscador[nombre_tercero]\" value=\"".$request['nombre_tercero']."\">";
    $html .= "      <input type=\"hidden\" name=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\" id=\"empresa_id\"> ";
		$html .= "      </td>";
		$html .= "      </tr>";
	
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" colspan=\"3\">";
		$html .= "      #- FACTURA";
		$html .= "      </td>";
    $html .= "      <td colspan=\"3\">";
    $html .= "        <input type=\"text\" class=\"input-text\" name=\"buscador[numero_factura]\" id=\"numero_factura\" value=\"".$request['numero_factura']."\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    
  
    $html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"6\">";
		$html .= "      <input type=\"submit\" value=\"BUSCAR\" class=\"input-submit\">";
		$html .= "      </td>";
		$html .= "      </tr>";
    
		$html .= "      </table>";
    $html .= "      </form>";
    $html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
    
    if(!empty($datos))
    {
     $pgn = AutoCarga::factory("ClaseHTML");
		 $html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
     $html .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td>";
     $html .= "         #-FACTURA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         TIPO ID";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         TERCERO ID";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         NOMBRE";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         OBSERVACIONES";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         FECHA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         VALOR FACTURA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         SALDO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         OP";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         NOTAS";
     $html .= "         </td>";
     $html .= "      </tr>";
     foreach($datos as $key=>$dtl)
      {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td >".$dtl['numero_factura']."</td>\n";
          $html .= "			<td >".$dtl['tipo_id_tercero']."</td>";
          $html .= "      <td >".$dtl['tercero_id']."</td>\n";
          $html .= "			<td >".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td >".$dtl['observaciones']."</td>\n";
          $html .= "			<td >".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td >$".FormatoValor($dtl['valor_factura'],2)."</td>\n";
          $html .= "			<td >$".FormatoValor($dtl['saldo'],2)."</td>\n";
          $html .= "      <td >";
          $html .= "      <a href=\"".$action['crear_nota']."&numero_factura=".$dtl['numero_factura']."&codigo_proveedor_id=".$dtl['codigo_proveedor_id']."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."\">";
          $html .= "          <img title=\"CREAR NOTA A LA FACTURA\" src=\"".GetThemePath()."/images/folder_vacio.png\" border=\"0\">\n";
          $html .= "      </a>";
          $html .= "      </td >";
          $html .= "      <td >";
          $html .= "      <a href=\"".$action['ver_notas']."&numero_factura=".$dtl['numero_factura']."&codigo_proveedor_id=".$dtl['codigo_proveedor_id']."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."\">";
          $html .= "          <img title=\"CREAR NOTA A LA FACTURA\" src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "      </a>";
          $html .= "      </td >";
          $html .= "		</tr>\n";
     }
     $html .= "      </table>";
    
    }
    else
        {
        $html .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr class=\"label_error\">";
        $html .= "      <td align=\"center\">";
        $html .= "      NO HAY DATOS PARA VISUALIZAR";
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      </table>";
        }
    
		$html .= ThemeCerrarTabla();
		
  $html .=$this->CrearVentana(700,"NOTAS DEBITO - CREDITO: FACTURAS DE PROVEEDORES");
	SessionSetVar("credito",$documentos[0]['documento_id_credito']);
	SessionSetVar("debito",$documentos[0]['documento_id_debito']);
	
	//print_r($_REQUEST);
		return $html;
	
		}
    
    
    function NotasFacturasProveedor($action,$request,$Tercero,$Documento,$Factura,$doc_nota_tmp_id)
		{
    //$request=$_REQUEST;
   //print_r($request);
    
	/*
    Seccion de Funciones Javascript
    */
    $html .="<script>";
    $html .="function Paginador(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset)";
    $html .="{";
    $html .="xajax_Listar_TercerosProveedores(TipoIdTercero,TerceroId,Descripcion,Empresa_Id,offset);";
    $html .="}";
    $html .="</script>";
    
	$html .="<script>";
	$html .="function acceptNum(evt)";
	$html .="{ ";
	$html .="var nav4 = window.Event ? true : false;";
	$html .="var key = nav4 ? evt.which : evt.keyCode;";
	$html .="return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);";
	$html .="}";
    $html .="</script>";
	
	$html .="<script>";
    $html .="function ProductoSeleccionado(CodigoProducto,Descripcion,Lote)";
    $html .="{";
    $html .="document.getElementById('NombreProducto').innerHTML=Descripcion;";
	$html .="document.getElementById('codigo_producto').value=CodigoProducto;";
	$html .="document.getElementById('lote').value=Lote;";
	$html .="OcultarSpan();";
    $html .="}";
    $html .="</script>";
	
	$html .="<script>";
    $html .="function QuitarProductoSeleccionado()";
    $html .="{";
    $html .="document.getElementById('NombreProducto').innerHTML='';";
	$html .="document.getElementById('codigo_producto').value='';";
	$html .="document.getElementById('lote').value='';";
	$html .="OcultarSpan();";
    $html .="}";
    $html .="</script>";
	
	$html .="<script>";
    $html .="function CrearDocumento(EmpresaId,Prefijo,Numeracion,Opc)";
    $html .="{";
	$html .="xajax_CrearDocumento(EmpresaId,Prefijo,Numeracion,Opc);";
    //$html .="alert(\"vaya\");";
    $html .="}";
    $html .="</script>";
    /*
    Fin de Funciones JavaScript
    */
	
    $accion=$action['volver'];
		$html .= ThemeAbrirTabla($Documento[0]['descripcion']);
    
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$html .= "  <tr><td>";
		$html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"12\">";
		$html .= "      APLICAR NOTA A FACTURA #".$request['numero_factura'];
		$html .= "      </td>";
		$html .= "      </tr>";
    
    if($Tercero[0]['tipo_id_tercero']=='NIT')
        {
        $dv="-".$Tercero[0]['dv'];
        }
        else
          {
          $dv="";
          }
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      TIPO ID";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['tipo_id_tercero'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      TERCERO ID";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['tercero_id']."".$dv;
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      NOMBRE";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['nombre_tercero'];
		$html .= "      </td>";
    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      TELEFONO";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['telefono'];
		$html .= "      </td>";
    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      DIRECCION";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['direccion'];
		$html .= "      </td>";
    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      PAIS";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Tercero[0]['pais'];
		$html .= "      </td>";
		$html .= "      </tr>";
	
  /*
  * Segundo Renglon
  */
    $html .= "      <tr>";
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      DOCUMENTO";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"3\">";
		$html .= "      ".$Documento[0]['descripcion'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      DOC. TEMP ID";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$doc_nota_tmp_id;
		$html .= "      </td>";
		
	    
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      VALOR NOTA:";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      <div id=\"valor_notica\"></div>";
		$html .= "      </td>";
    
    
		$html .= "      </tr>";
    
  /*
  * Tercer Renglon
  */
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      FACTURA #";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\" >";
		$html .= "      ".$Factura[0]['numero_factura'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      FECHA FACTURA";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      ".$Factura[0]['fecha_registro'];
		$html .= "      </td>";
		
		$html .= "      <td class=\"modulo_table_list_title\" align=\"center\">";
		$html .= "      OBSERVACIONES";
		$html .= "      </td>";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"3\">";
		$html .= "      ".$Factura[0]['observaciones'];
		$html .= "      </td>";
     
       
    
		$html .= "      </tr>";
	
    $html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"12\">";
		$html .= "		<input type=\"hidden\" id=\"tipo_nota\" value=\"".$request['tipo_nota']."\">";
		$html .= "		<input type=\"hidden\" id=\"numero_factura\" value=\"".$request['numero_factura']."\">";
		$html .= "		<input type=\"hidden\" id=\"valor_nota\" value=\"\" >";
		$html .= "      </td>";                                                                                                                   // $TipoIdTercero,$TerceroId,$ValorNota,$NumeroFactura,$DocumentoId,$Prefijo,$Numeracion,$EmpresaId
    $html .= "      </tr>";
    
    
		$html .= "      </table>";
    
     
		$html .= "  </td></tr>";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\">";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="VOLVER"><input class="input-submit" type="button" id="crear_documento" value="CREAR DOCUMENTO" onclick="xajax_CrearDocumento(\''.$request['datos']['empresa_id'].'\',\''.$doc_nota_tmp_id.'\',document.getElementById(\'valor_nota\').value);">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
				
		$html .= "<div id=\"DocumentoCreado\">";
		$html .= "	<center>";
		$html .= "	<a href=\"#\" class=\"label_error\" onclick=\"xajax_FormDetalleNota('".$request['datos']['empresa_id']."','".$doc_nota_tmp_id."');\">Adicionar Concepto Nota</a>";
		$html .= "  </center>";
		$html .= "<div id=\"NotaCreada\"></div>";
		$html .= "<div id=\"DetallesNota\"></div>";
		$html .= "</div>";
		$html .= ThemeCerrarTabla();
		$html .=$this->CrearVentana(700,"PRODUCTOS");
		
		
		$html .="<script>";
	    $html .="xajax_NotaDetalles('".$_REQUEST['datos']['empresa_id']."','".$doc_nota_tmp_id."');";
	    $html .="</script>";
		return $html;
		
	
		}
    
    
     function Creacion_Nota($action,$request,$Temporal,$Detalle,$glosas_concepto_general,$Temporal_Nota,$Parametros,$parametros_retencion,$conteo, $pagina)
		{
    $Select_GlosaConceptoGeneral .= "	<option value=\"\">-- SELECCIONAR --</option>";
    foreach($glosas_concepto_general as $key => $valor)
    $Select_GlosaConceptoGeneral .= "	<option value=\"".$valor['codigo_concepto_general']."\">".$valor['codigo_concepto_general']." - ".$valor['descripcion_concepto_general']."</option>";
    $Select_GlosaConceptoGeneral .= "	</select>";
    
    
  $ctl = AutoCarga::factory("ClaseUtil");
  $html .= $ctl->RollOverFilas();
  $html .="<script>";
	$html .="function acceptNum(evt)";
	$html .="{ ";
	$html .="var nav4 = window.Event ? true : false;";
	$html .="var key = nav4 ? evt.which : evt.keyCode;";
	$html .="return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);";
	$html .="}";
  $html .="</script>";
  
  $html .="<script>";
  $html .= "	function ValidarCantidad(campo,valor,cant_sol,capa,check)\n";
	$html .= "	{\n";
	$html .= "		document.getElementById(campo).style.background='';\n";
	$html .= "		document.getElementById(capa).innerHTML='';\n";
	$html .= "		document.getElementById(check).disabled=false;\n";
	$html .= "		if(isNaN(valor) || parseFloat(valor)<=0 || valor=='')\n";
	//$html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
	$html .= "		{\n";
	$html .= "			document.getElementById(campo).value='';\n";
  $html .= "		document.getElementById(check).disabled=true;\n";
	$html .= "			document.getElementById(campo).style.background='#ff9595';\n";
	$html .= "			document.getElementById(capa).innerHTML='<center>VALOR NO VALIDO</center>';\n";
  $html .= "		}\n";
	$html .= "	}\n";
  $html .="</script>";
  
  $html .= "<script>";
  $html .= "  function Confirmar()";
  $html .= "  {";
  $html .= "  var x=confirm(\"Confirma Crear El Documento?\"); ";
  $html .= "  if(x) ";
  $html .= "      document.TemporalesNota.submit();";
  $html .= "  else ";
  $html .= "      return false;";
  $html .= "  }";
  $html .= "</script>";
    /*
    Fin de Funciones JavaScript
    */
	
    $accion=$action['volver'];
		$html .= ThemeAbrirTabla("CREACION DE NOTA A LA FACTURA:<u><i>".$_REQUEST['numero_factura']."</i></u>");
    
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\">";
		$html .= "  <tr><td>";
	
    $html .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "        <tr>";
    $html .= "            <td class=\"modulo_table_list_title\">";
    $html .= "             PROVEEDOR";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             ".$Temporal['tipo_id_tercero']." ".$Temporal['tercero_id']."-".$Temporal['nombre_tercero'];
    $html .= "            </td>";
    $html .= "            <td class=\"modulo_table_list_title\">";
    $html .= "             #- FACTURA";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             ".$Temporal['factura_proveedor'];
    $html .= "            </td>";
    $html .= "            <td class=\"modulo_table_list_title\">";
    $html .= "             USUARIO";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             ".$Temporal['usuario'];
    $html .= "            </td>";
    $html .= "        </tr>";
    $html .= "        <tr>";
    $html .= "            <td colspan=\"6\">";
    if($parametros_retencion['sw_rtf']=='2' || $parametros_retencion['sw_rtf']=='3')
					if($Temporal['subtotal'] >= $parametros_retencion['base_rtf'])
					$retencion_fuente = $Temporal['subtotal']*($Temporal['porc_rtf']/100);
					
				if($parametros_retencion['sw_ica']=='2' || $parametros_retencion['sw_ica']=='3')
					if($Temporal['subtotal'] >= $parametros_retencion['base_ica'])
					$retencion_ica = $Temporal['subtotal']*($Temporal['porc_ica']/1000);
					
				if($parametros_retencion['sw_reteiva']=='2' ||$parametros_retencion['sw_reteiva']=='3')
					if($Temporal['subtotal'] >= $parametros_retencion['base_reteiva'])
						$retencion_iva = $Temporal['iva_total']*($Temporal['porc_rtiva']/100);
						
	$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "					<tr align=\"center\" class=\"label\">";
	$html .= "						<td>";
	$html .= "							<u>SUBTOTAL</u>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<u>IVA</u>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<u>RET-FTE</u>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<u>RETE-ICA</u>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<u>RETE-IVA</u>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<u>DESCUENTO</u>";
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							<u>VALOR TOTAL</u>";
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "				<tr align=\"center\" >";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($Temporal['subtotal'],2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($Temporal['iva_total'],2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($retencion_fuente,2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($retencion_ica,2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($retencion_iva,2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($Temporal['valor_descuento'],2);
	$html .= "						</td>";
	$html .= "						<td>";
	$total = ((((($Temporal['total'])-$retencion_fuente)-$retencion_ica)-$retencion_iva)-$Temporal['valor_descuento']);
	$html .= "						$".FormatoValor($total,2);
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "			</table>";
	/*$html .= "             DESCUENTO";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             $".FormatoValor($Temporal['valor_descuento'],4);
    $html .= "            </td>";
    $html .= "            <td class=\"modulo_table_list_title\">";
    $html .= "             VALOR FACTURA(SIN DESCUENTO)";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             $".FormatoValor($Temporal['valor_factura'],4);
    $html .= "            </td>";
    $html .= "            <td class=\"modulo_table_list_title\">";
    $html .= "             SALDO";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             $".FormatoValor($Temporal['saldo'],4);*/
    $html .= "            </td>";
    $html .= "        </tr>";
    $html .= "        <tr>";
    $html .= "            <td >";
    $html .= "            </td >";
    $html .= "            <td >";
    $html .= "            </td >";
    $html .= "            <td >";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "            </td>";
    $html .= "            <td class=\"modulo_table_list_title\">";
    $html .= "             SALDO";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             $".FormatoValor(($Temporal['saldo']-$Temporal['valor_descuento']),2);
    $html .= "            </td>";
    $html .= "        </tr>";
		$html .= "        </table>";
     
		$html .= "  </td></tr>";
    
    $html .= "  <tr><td>";
    
    $html .= "        <form name=\"Buscador\" action=\"".$action['guardar']."\" method=\"post\">";
    $html .= "        <table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "        <tr>";
    $html .= "          <td class=\"modulo_table_list_title\" colspan=\"4\">";
    $html .= "          BUSCADOR DE PRODUCTOS";
    $html .= "          </td >";
    $html .= "        </tr>";
    $html .= "        <tr>";
    $html .= "          <td class=\"modulo_table_list_title\">";
    $html .= "          CODIGO PRODUCTO";
    $html .= "          </td >";
    $html .= "          <td >";
    $html .= "          <input type=\"text\" name=\"buscador[codigo_producto]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['codigo_producto']."\">";
    $html .= "          </td >";
    $html .= "          <td class=\"modulo_table_list_title\">";
    $html .= "          NOMBRE";
    $html .= "          </td >";
    $html .= "          <td >";
    $html .= "          <input type=\"text\" name=\"buscador[descripcion]\" class=\"input-text\" style=\"width:100%\" value=\"".$request['descripcion']."\">";
    $html .= "          </td >";
    $html .= "        </tr>";
    $html .= "        <tr class=\"modulo_list_oscuro\">";
    $html .= "          <td colspan=\"4\" align=\"center\">";
    $html .= "           <input type=\"hidden\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$_REQUEST['numero_factura']."\">";
    $html .= "           <input type=\"hidden\" name=\"codigo_proveedor_id\" id=\"codigo_proveedor_id\" value=\"".$_REQUEST['codigo_proveedor_id']."\">";
    $html .= "           <input type=\"hidden\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">";
    $html .= "           <input type=\"submit\" value=\"BUSCAR PRODUCTO-FACTURA\" class=\"input-submit\">";
    $html .= "          </td>";
    $html .= "        </tr>";
    $html .= "        </table>";
    $html .= "        </form>";
    
    
    
    
    $pgn = AutoCarga::factory("ClaseHTML");
	  $html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
    //Apertura de Formulario
    $html .= "        <form name=\"Producto\" action=\"".$action['guardar']."\" method=\"post\">";
    $html .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "        <tr>";
    $html .= "            <td class=\"modulo_table_list_title\" colspan=\"7\">";
    $html .= "             ITEMS AGRUPADOS DE LA FACTURA-(Los Valores, Tienen el Iva Incluido)";
    $html .= "            </td>";
    $html .= "        </tr>";
    $html .= "        <tr class=\"modulo_table_list_title\">";
    $html .= "            <td >";
    $html .= "             CODIGO PRODUCTO";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             DESCRIPCION";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             CANTIDAD";
    $html .= "            <td >";
    $html .= "             VALOR/UNITARIO";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             %IVA";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             VALOR/TOTAL";
    $html .= "            </td>";
    $html .= "            <td >";
    $html .= "             OP";
    $html .= "            </td>";
    
    $html .= "        </tr>";
    $i=0;
    foreach($Detalle as $key=>$dtl)
      {
      $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
      $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
      $porc_iva = 0;
      if($dtl['porc_iva']>0)
      $porc_iva = $dtl['porc_iva']/100;
      
      $valor_iva = ($dtl['valor']*($dtl['cantidad']-$dtl['cantidad_devuelta']))*$porc_iva;
      $valor_Unitario_Iva = $dtl['valor']*$porc_iva;
      
      $acum = $acum + (($dtl['valor']*$dtl['cantidad'])+$valor_iva);
      $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
      $html .= "			<td >".$dtl['codigo_producto']."</td>\n";
      $html .= "			<td >".$dtl['descripcion']."</td>";
      $html .= "      		<td >".FormatoValor($dtl['cantidad']-$dtl['cantidad_devuelta'])."<input type=\"hidden\" name=\"cantidad".$i."\" id=\"cantidad".$i."\" value=\"".($dtl['cantidad']-$dtl['cantidad_devuelta'])."\"></td>\n";
      $html .= "      		<td class=\"normal_10AN\">$".FormatoValor(($dtl['valor']),2)."</td>\n";
      $html .= "      		<td class=\"normal_10AN\">";
	  $html .= "				<input type=\"hidden\" name=\"porc_iva".$i."\" id=\"porc_iva".$i."\" value=\"".$dtl['porc_iva']."\" >";
	  $html .= "				".FormatoValor(($dtl['porc_iva']),2)."% ";
	  $html .= "	  		</td>\n";
      $html .= "      		<td class=\"normal_10AN\">$".FormatoValor(($dtl['valor']*($dtl['cantidad']-$dtl['cantidad_devuelta'])),2)."</td>\n";
      $html .= "      		<td rowspan=\"2\"><input disabled=\"true\" ".$dtl['checkbox']." title=\"Seleccionar Item para La Nota\" type=\"checkbox\" name=\"$i\" id=\"$i\" value=\"".$dtl['codigo_producto']."\"class=\"input-checkbox\"></td>\n";
      $html .= "		</tr >";
      $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
      $html .= "			<td colspan=\"6\">";
      
      //Aqui va una Subtabla para Los Items A Glosar
      $html .= "      <div class=\"label_error\" id=\"hell".$i."\"></div>";
      $html .= "      <table width=\"100%\" class=\"modulo_table_list\">";
      $html .= "          <tr class=\"normal_10AN\">";
      $html .= "              <td>";
      $html .= "                  CONCEPTO GENERAL:";
      $html .= "              </td>";
      $html .= "              <td>";
      $html .= "						<select class=\"select\" name=\"codigo_concepto_general".$i."\"name=\"codigo_concepto_general".$i."\" style=\"width:50%\" onchange=\"xajax_Listado_ConceptoEspecifico(this.value,'".$i."');\">";
      $html .= "						".$Select_GlosaConceptoGeneral;
      $html .= "						</select>";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             VALOR CONCEPTO:";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             <input type=\"hidden\" name=\"valor".$i."\" id=\"valor".$i."\"  value=\"".$dtl['valor']."\">";
      $html .= "             <input onkeypress=\"return acceptNum(event);\" type=\"text\" ".$dtl['checkbox']." class=\"input-text\" name=\"valor_concepto".$i."\" id=\"valor_concepto".$i."\" onkeyup=\"ValidarCantidad('valor_concepto".$i."',document.getElementById('valor_concepto".$i."').value,'".(($dtl['valor']*($dtl['cantidad']-$dtl['cantidad_devuelta'])))."','hell".$i."','".$i."')\">";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             OBSERVACION:";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             <textarea name=\"observacion".$i."\" style=\"width:100%\" class=\"textarea\"></textarea>";
      $html .= "              </td>";
      $html .= "          </tr>";
      $html .= "          <tr class=\"normal_10AN\">";
      $html .= "              <td>";
      $html .= "                  CONCEPTO ESPECIFICO:";
      $html .= "              </td>";
      $html .= "              <td>";
      $html .= "          			<select name=\"codigo_concepto_especifico".$i."\" id=\"codigo_concepto_especifico".$i."\" style=\"width:50%\" class=\"select\">";
    	$html .= "          			<option value=\"\">-- SELECCIONAR --</option>";
      $html .= "          			</select>";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             NOTA MAYOR VALOR:";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             <input title=\"Define Si La Nota A Aplicar es Baja Costo o Sube-Costo\" type=\"checkbox\" checked class=\"input-text\" name=\"nota_mayor_valor".$i."\" id=\"nota_mayor_valor".$i."\" value=\"1\">";
      $html .= "              </td>";
	  $html .= "              <td >";
      $html .= "             APLICAR NOTA BAJA/SUBE COSTO?:";
      $html .= "              </td>";
      $html .= "              <td >";
      $html .= "             <input title=\"Define Si La Nota A Aplicar es Baja Costo o Sube-Costo\" type=\"checkbox\" checked class=\"input-text\" name=\"sube_baja_costo".$i."\" id=\"sube_baja_costo".$i."\" value=\"1\">";
      $html .= "              </td>";
      $html .= "          </tr>";
      $html .= "      </table>";
      //Cierre SubTabla
      
      $html .= "      </td>\n";
      $html .= "			</tr >";
      $i++;
      }
    $html .= "        <tr>";
    $html .= "            <td colspan=\"5\" align=\"center\">";
    $html .= "        <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
    $html .= "        <input type=\"hidden\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$_REQUEST['numero_factura']."\">";
    $html .= "        <input type=\"hidden\" name=\"codigo_proveedor_id\" id=\"codigo_proveedor_id\" value=\"".$_REQUEST['codigo_proveedor_id']."\">";
    $html .= "        <input type=\"hidden\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">";
    $html .= "        <input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\">";
    $html .= "            </td>";
    $html .= "        </tr>";
    $html .= "        </table>";
    $html .= "        </form>";
    //Cierre de Formulario
    
    $html .= "<br>";
    if(!empty($Temporal_Nota))
    {
    $html .= "        <form name=\"TemporalesNota\" action=\"".$action['CrearDocumento']."\" method=\"post\" onSubmit=\"return Confirmar();\">";
    $html .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "        <tr>";
    $html .= "          <td class=\"modulo_table_list_title\" colspan=\"4\">";
    $html .= "          ITEMS EN EL DOCUMENTO TEMPORAL";
    $html .= "          </td >";
    $html .= "        </tr>";
    foreach($Temporal_Nota as $k=>$val)
      {
      $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
      $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
     
     $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
     $html .= "			<td >";
     $html .= "         <table class=\"modulo_table_list\" width=\"100%\" >";
     $html .= "           <tr>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 PRODUCTO:";
     $html .= "             </td>";
     $html .= "             <td colspan=\"3\">";
     $html .= "                 ".$val['codigo_producto']." - ".$val['descripcion'];
	 $html .= "					  <label class=\"label_error\">&#60;\"".$val['operacion']."\"&#62;</label>";
     $html .= "             </td>";
     $html .= "             <td rowspan=\"4\">";
     $html .= "                   <a href=\"".$action['EliminarItems']."&codigo_producto=".$val['codigo_producto']."&op=1\" >";
     $html .= "                    <img title=\"Eliminar Item Del Temporal\" src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
     $html .= "                   </a>";
     $html .= "             </td>";
     $html .= "           </tr>";
     $html .= "           <tr>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 VALOR TOTAL CONCEPTO:";
     $html .= "             </td>";
     $html .= "             <td width=\"20%\">";
     $html .= "                 $".FormatoValor($val['valor_concepto'],2);
     $html .= "             </td>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 VALOR UNIDAD CONCEPTO:";
     $html .= "             </td>";
     $html .= "             <td >";
     $html .= "                 $".FormatoValor($val['valor_concepto']/$val['cantidad'],2);
     $html .= "             </td>";
     $html .= "           </tr>";
     $html .= "           <tr>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 CONCEPTO GENERAL:";
     $html .= "             </td>";
     $html .= "             <td width=\"20%\">";
     $html .= "                 ".$val['descripcion_concepto_general'];
     $html .= "             </td>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 CONCEPTO ESPECIFICO: ";
     $html .= "             </td>";
     $html .= "             <td >";
     $html .= "                 ".$val['descripcion_concepto_especifico'];
     $html .= "             </td>";
     $html .= "           </tr>";
     $html .= "           <tr>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 TIPO DE NOTA:";
     $html .= "             </td>";
     $html .= "             <td >";
     $html .= "                 ".$val['tipo_nota'];
     $html .= "             </td>";
     $html .= "             <td class=\"normal_10AN\" width=\"20%\">";
     $html .= "                 OBSERVACION:";
     $html .= "             </td>";
     $html .= "             <td colspan=\"3\">";
     $html .= "                 ".$val['observacion'];
     $html .= "             </td>";
     $html .= "           </tr>";
	 $html .= "				<tr>";
	 $html .= "					<td colspan=\"5\">";
	 
	 if($parametros_retencion['sw_rtf']=='2' || $parametros_retencion['sw_rtf']=='3')
					if($Temporal['subtotal'] >= $parametros_retencion['base_rtf'])
					$ret_fuente = $val['valor_concepto']*($Temporal['porc_rtf']/100);
					
				if($parametros_retencion['sw_ica']=='2' || $parametros_retencion['sw_ica']=='3')
					if($Temporal['subtotal'] >= $parametros_retencion['base_ica'])
					$ret_ica = $val['valor_concepto']*($Temporal['porc_ica']/1000);
					
				if($parametros_retencion['sw_reteiva']=='2' ||$parametros_retencion['sw_reteiva']=='3')
					if($Temporal['subtotal'] >= $parametros_retencion['base_reteiva'])
						$ret_iva = $val['iva']*($Temporal['porc_rtiva']/100);
						
	$html .= "				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" rules=\"all\">";
	$html .= "					<tr align=\"center\" class=\"label\">";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RET-FTE</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RETE-ICA</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>IVA</u>";
	$html .= "						</td>";
	$html .= "						<td width=\"25%\">";
	$html .= "							<u>RETE-IVA</u>";
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "				<tr align=\"center\" >";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($ret_fuente,2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($ret_ica,2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($val['iva'],2);
	$html .= "						</td>";
	$html .= "						<td>";
	$html .= "							$".FormatoValor($ret_iva,2);
	$html .= "						</td>";
	$html .= "				</tr>";
	$html .= "			</table>";
	 
	 
	 $html .= "						";
	 $html .= "					</td>";
	 $html .= "				</tr>";
     $html .= "         </table>";
     $html .= "     </td>\n";
     $html .= "		</tr >";
     
     if($val['nota_mayor_valor']=='1')
      {
      $debito = $debito + $val['valor_concepto'];
      }
      else
          $credito = $credito + $val['valor_concepto'];
          
    }
    $html .= "        <tr class=\"modulo_list_oscuro\">";
    $html .= "          <td >";
    $html .= "          TOTAL/DEBITO: <b>$".FormatoValor($debito,2)."</b>";
    $html .= "          </td>";
    $html .= "        </tr>";
    $html .= "        <tr class=\"modulo_list_oscuro\">";
    $html .= "          <td >";
    $html .= "          TOTAL/CREDITO: <b>$".FormatoValor($credito,2)."</b>";
    $html .= "          </td>";
    $html .= "        </tr>";
    
    $disabled ="";
    $mensaje ="";
    if(empty($Parametros))
    {
    $disabled = " disabled ";
    $mensaje =" <i class=\"label_error\">!!!NO HAY DOCUMENTOS PARAMETRIZADOS PARA ESTE MODULO???</i> ";
    }
    
    $html .= "        <tr class=\"modulo_list_oscuro\">";
    $html .= "          <td colspan=\"4\" align=\"center\">";
    $html .= "           <input type=\"hidden\" name=\"crear_nota\" id=\"crear_nota\" value=\"1\">";
    $html .= "           <input type=\"hidden\" name=\"valor_debito\" id=\"valor_debito\" value=\"".$debito."\">";
    $html .= "           <input type=\"hidden\" name=\"valor_credito\" id=\"valor_credito\" value=\"".$credito."\">";
    $html .= "           <input type=\"hidden\" name=\"numero_factura\" id=\"numero_factura\" value=\"".$_REQUEST['numero_factura']."\">";
    $html .= "           <input type=\"hidden\" name=\"codigo_proveedor_id\" id=\"codigo_proveedor_id\" value=\"".$_REQUEST['codigo_proveedor_id']."\">";
    $html .= "           <input type=\"hidden\" name=\"datos[empresa_id]\" id=\"datos[empresa_id]\" value=\"".$_REQUEST['datos']['empresa_id']."\">";
    $html .= "           <input ".$disabled." type=\"submit\" value=\"CREAR DOCUMENTO\" class=\"input-submit\">".$mensaje;
    $html .= "          </td>";
    $html .= "        </tr>";
    $html .= "        </table>";
    $html .= "        </form>";
    }
    
		$html .= "  </td></tr>";
    
    
    
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\">";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="VOLVER">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";

		$html .= ThemeCerrarTabla();
		$html .=$this->CrearVentana(700,"PRODUCTOS");
		
		
		return $html;
		
	
		}
 
 
 function Documentos_Nota($action,$NotasCredito,$NotasDebito,$NotasDevolucion,$conteo, $pagina)
		{
    $ctl = AutoCarga::factory("ClaseUtil");
      
 			$html  = $ctl->LimpiarCampos();
 			$html .= $ctl->RollOverFilas();
 			$html .= $ctl->AcceptDate('/');
    $rpt  = new GetReports();
  
    $accion=$action['volver'];
		
    $html .= "<script>";
      $html .= " function Imprimir(direccion,empresa_id,prefijo,numero)  ";
      $html .= "  { ";
      $html .= " var url=direccion+'?empresa_id='+empresa_id+'&prefijo='+prefijo+'&numero='+numero; ";
      $html .= " window.open(url,'','width=800,height=600,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes'); ";
      $html .= "  }";
      $html .= "</script>";
    
    $html .= ThemeAbrirTabla('NOTAS DEBITO - CREDITO: FACTURAS DE PROVEEDORES');

    
    if(!empty($NotasCredito))
    {
     $pgn = AutoCarga::factory("ClaseHTML");
		 $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td colspan=\"8\">";
     $html .= "             NOTAS CREDITO - PROVEEDOR";
     $html .= "         </td>";
     $html .= "      </tr>";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td>";
     $html .= "         #-DOCUMENTO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         DOCUMENTO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         PROVEEDOR";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         #-FACTURA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         FECHA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         USUARIO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         VALOR NOTA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         OP";
     $html .= "         </td>";
     $html .= "      </tr>";
     foreach($NotasCredito as $key=>$dtl)
      {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td >".$dtl['prefijo']."-".$dtl['numero']."</td>\n";
          $html .= "			<td >".$dtl['documento']."</td>";
          $html .= "      <td >".$dtl['tipo_id_tercero']."-".$dtl['tercero_id'].": ".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td >".$dtl['numero_factura']."</td>\n";
          $html .= "			<td >".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td >".$dtl['usuario']."</td>\n";
          $html .= "			<td >$".FormatoValor($dtl['valor_nota'],2)."</td>\n";
          $html .= "      <td >";
          $html .= $rpt->GetJavaReport('app','Inv_NotasFacturasProveedor','Notas',$dtl,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc  = $rpt->GetJavaFunction();

          $html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "			  </a>\n";
          $html .= "      </td >";
          $html .= "		</tr>\n";
     }
     $html .= "      </table>";
    
    }
    else
        {
        $html .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr class=\"label_error\">";
        $html .= "      <td align=\"center\">";
        $html .= "      NO HAY NOTAS CREDITO";
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      </table>";
        }
     $html .= "<br>"; 
       if(!empty($NotasDebito))
    {
     $pgn = AutoCarga::factory("ClaseHTML");
		 $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td colspan=\"8\">";
     $html .= "             NOTAS DEBITO - PROVEEDOR";
     $html .= "         </td>";
     $html .= "      </tr>";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td>";
     $html .= "         #-DOCUMENTO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         DOCUMENTO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         PROVEEDOR";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         #-FACTURA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         FECHA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         USUARIO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         VALOR NOTA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         OP";
     $html .= "         </td>";
     $html .= "      </tr>";
     foreach($NotasDebito as $key=>$dtl)
      {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td >".$dtl['prefijo']."-".$dtl['numero']."</td>\n";
          $html .= "			<td >".$dtl['documento']."</td>";
          $html .= "      <td >".$dtl['tipo_id_tercero']."-".$dtl['tercero_id'].": ".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td >".$dtl['numero_factura']."</td>\n";
          $html .= "			<td >".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td >".$dtl['usuario']."</td>\n";
          $html .= "			<td >$".FormatoValor($dtl['valor_nota'],2)."</td>\n";
          $html .= "      <td >";
          $html .= $rpt->GetJavaReport('app','Inv_NotasFacturasProveedor','Notas',$dtl,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc  = $rpt->GetJavaFunction();

          $html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "			  </a>\n";
          $html .= "      </td >";
          $html .= "		</tr>\n";
     }
     $html .= "      </table>";
    
    }
    else
        {
        $html .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr class=\"label_error\">";
        $html .= "      <td align=\"center\">";
        $html .= "      NO HAY NOTAS DEBITO";
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      </table>";
        }
        
        $html .= "<br>"; 
       if(!empty($NotasDevolucion))
    {
     $pgn = AutoCarga::factory("ClaseHTML");
		 $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td colspan=\"8\">";
     $html .= "             NOTAS DEVOLUCION - PROVEEDOR";
     $html .= "         </td>";
     $html .= "      </tr>";
     $html .= "      <tr class=\"modulo_table_list_title\">";
     $html .= "         <td>";
     $html .= "         #-DOCUMENTO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         DOCUMENTO";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         PROVEEDOR";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         #-FACTURA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         FECHA";
     $html .= "         </td>";
     $html .= "         <td>";
     $html .= "         USUARIO";
     $html .= "         </td>";
     /*$html .= "         <td>";
     $html .= "         VALOR NOTA";
     $html .= "         </td>";*/
     $html .= "         <td>";
     $html .= "         OP";
     $html .= "         </td>";
     $html .= "      </tr>";
     foreach($NotasDevolucion as $key=>$dtl)
      {
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
         
          $html .= "		<tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
          $html .= "			<td >".$dtl['prefijo']."-".$dtl['numero']."</td>\n";
          $html .= "			<td >".$dtl['documento']."</td>";
          $html .= "      <td >".$dtl['tipo_id_tercero']."-".$dtl['tercero_id'].": ".$dtl['nombre_tercero']."</td>\n";
          $html .= "			<td >".$dtl['numero_factura']."</td>\n";
          $html .= "			<td >".$dtl['fecha_registro']."</td>\n";
          $html .= "			<td >".$dtl['usuario']."</td>\n";
          //$html .= "			<td >$".FormatoValor($dtl['valor_nota'],4)."</td>\n";
          $html .= "      <td align=\"center\">\n";
          $path = GetThemePath();
                   $direccion="app_modules/Inv_MovimientosBodegas/Doc_Mov_Bodegas/E012/imprimir/imprimir_docE012.php";
                   $imagen = $path."/images/imprimir.png";
                   $alt="IMPRIMIR DOCUMENTO";
                   $x=$this->RetornarImpresionDoc($direccion,$alt,$imagen,$dtl['empresa_id'],$dtl['prefijo'],$dtl['numero']);
          $html.= "                     ".$x."";
          $html .= "      </td >";
          $html .= "		</tr>\n";
     }
     $html .= "      </table>";
    
    }
    else
        {
        $html .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr class=\"label_error\">";
        $html .= "      <td align=\"center\">";
        $html .= "      NO HAY NOTAS DEVOLUCION AL PROVEEDOR";
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      </table>";
        }
		$html .= "<br>";
		
		/*$html .= "      <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"label_error\">";
		$html .= "      <td align=\"center\">";
		$html .= "      PROVEEDOR DEBE: ";
		$html .= $rpt->GetJavaReport('app','Inv_NotasFacturasProveedor','ProveedorDebe',$_REQUEST,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $fnc  = $rpt->GetJavaFunction();

          $html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
          $html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
          $html .= "			  </a>\n";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      </table>";*/
    
    $html .= "  <table border=\"0\" width=\"60%\" align=\"center\">";
		$html .= ' 	<form name="forma" action="'.$action['volver'].'" method="post">';
		$html .= "  <tr>";
		$html .= "  <td align=\"center\"><br>";
		$html .= '  <input class="input-submit" type="submit" name="volver" value="Volver">';
		$html .= "  </td>";
		$html .= "  </form>";
		$html .= "  </tr>";
		$html .= "  </table>";
		$html .= ThemeCerrarTabla();
		
    $html .=$this->CrearVentana(700,"NOTAS");
		return $html;
	
		}
   
   /********************************
*pop up para imprimir
***********************************/
    function RetornarImpresionDoc($direccion,$alt,$imagen,$empresa_id,$prefijo,$numero)
    {    
    global $VISTA;
    $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";
    $salida1 ="<a title='".$alt."' href=javascript:Imprimir('$direccion','$empresa_id','$prefijo','$numero')>".$imagen1."</a>";
    return $salida1;
    }
   
     // CREAR LA CAPITA
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
    //Mostrar Span
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
    //En ese espacio se visualiza la informacion extraida de la base de datos.
    $html .= "  </div>\n";
    $html .= "</div>\n";


  
    return $html;
  } 
   
 
	}
?>