<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosAutorizacionesIngreso.php
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
    
  function Listar_ProductosPorAutorizar($NombreProducto,$EmpresaId,$OrdenCompra,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAutorizacionesIngreso","classes","app","Inv_AutorizacionesIngreso");
  $ProductosPorAutorizar=$sql->Listar_ProductosPorAutorizar($NombreProducto,$EmpresaId,$OrdenCompra,$offset);
  
  $action['paginador'] = "Paginador('".$NombreProducto."','".$EmpresaId."','".$OrdenCompra."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">PRODUCTOS POR AUTORIZAR INGRESO A DOCUMENTOS</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"20%\">FECHA INGRESO</td>\n";
      $html .= "      <td width=\"5%\">#PEDIDO</td>\n";
      $html .= "      <td width=\"20%\">CODIGO</td>\n";
      $html .= "      <td width=\"20%\">NOMBRE PRODUCTO</td>\n";
      $html .= "      <td width=\"5%\">LOTE</td>\n";
      $html .= "      <td width=\"20%\">FECHA VENCIMIENTO</td>\n";
      $html .= "      <td width=\"20%\">CANTIDAD</td>\n";
      
	  $html .= "      <td width=\"20%\">VALOR COMPRA</td>\n";
	  $html .= "      <td width=\"20%\">VALOR FACTURA</td>\n";
	  
	  $html .= "      <td width=\"20%\">TOTAL</td>\n";
      $html .= "      <td width=\"20%\">LOCALIZACION</td>\n";
      $html .= "      <td width=\"20%\">USUARIO SOLICITANTE</td>\n";
      $html .= "      <td width=\"20%\">JUSTIFICACION</td>\n";
      //$html .= "      <td width=\"10%\">ESTADO JUSTIFICACION</td>\n";
      $html .= "      <td width=\"10%\">AUTORIZAR</td>\n";
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($ProductosPorAutorizar as $key => $ppa)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

           
            $html .= "    <tr class=\"".$est."\" );>\n";
            $html .= "      <td >".$ppa['fecha_ingreso']."</td><td>".$ppa['orden_pedido_id']." </td>\n";
            $html .= "      <td >".$ppa['codigo_producto']."</td><td>".$ppa['producto']." </td>\n";
            $html .= "      <td >".$ppa['lote']."</td><td>".$ppa['fecha_vencimiento']." </td>\n";
			$html .= "      <td >".$ppa['cantidad']."</td><td>$".$ppa['valor_unitario_compra']." </td>\n";
			
			
			$html .= "      <td >".$ppa['valor_unitario_factura']."</td><td>$".$ppa['total_costo']." </td>\n";
			
			
			
			$html .= "      <td >".$ppa['local_prod']."</td><td>".$ppa['nombre']." </td>\n";
			$html .= "      <td >".$ppa['justificacion_ingreso']."</td>\n";
                    
     
            /*$html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_VerEstadoAutorizacion('".$tp['codigo_proveedor_id']."','".$Empresa_Id."','".$tp['tipo_id_tercero']."','".$tp['tercero_id']."')\">\n";
            $html .= "          <img title=\"VER ESTADOS DE AUTORIZACION DEL PRODUCTO A INGRESAR\" src=\"".GetThemePath()."/images/informacion.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";*/
			
			$html .= "      <td align=\"center\">\n";
            $producto = str_replace('"'," ", $ppa['producto']);
			$param="'".$ppa['doc_tmp_id']."','".$ppa['codigo_producto']."','".$ppa['cantidad']."','".$ppa['porcentaje_gravamen']."','".$ppa['total_costo']."','".$ppa['usuario_id']."','".$ppa['fecha_vencimiento']."','".$ppa['lote']."','".$ppa['local_prod']."'";
            $html .= "        <a href=\"#\" onclick=\"xajax_Autorizar(".$param.",'".UserGetUID()."','".$producto."','".$ppa['item_id']."','".$ppa['orden_pedido_id']."')\">\n";
            $html .= "          <img title=\"AUTORIZAR\" src=\"".GetThemePath()."/images/autorizado.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
			$html .= "<input type=\"hidden\" id=\"descripcion_producto\" value=\"".$ppa['producto']."\">";
			$html .= "<input type=\"hidden\" id=\"orden_pedido_id\" value=\"".$ppa['orden_pedido_id']."\">";
			
			$html .= "<input type=\"hidden\" id=\"empresa_id\" value=\"".$ppa['empresa_id']."\">";
			$html .= "<input type=\"hidden\" id=\"centro_utilidad\" value=\"".$ppa['centro_utilidad']."\">";
			$html .= "<input type=\"hidden\" id=\"bodega\" value=\"".$ppa['bodega']."\">";
			
            $html .= "      </td>\n";
            
			}
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
           $html=$objResponse->setTildes($html);
          $objResponse->assign("Listado","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  
  function Autorizar($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id,$fecha_venc,$lotec,$localizacion,$UsuarioAutorizador,$DescripcionProducto,$ItemId,$OrdenPedidoId)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAutorizacionesIngreso","classes","app","Inv_AutorizacionesIngreso");
    
  $DatosUsuario=$sql->ConsultarUsuario($UsuarioAutorizador);
   
	
	$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
	$html .= "      <tr class=\"modulo_table_list_title\">";
	$html .= "      <td align=\"center\" >";
	$html .= "      AUTORIZACION";
	$html .= "      </td>";
	$html .= "      </tr>";
    
    $html .= "      <tr>";
	$html .= "      <td align=\"center\">";
	$html .= "      YO ".$DatosUsuario[0]['nombre']." - ".$DatosUsuario[0]['descripcion'].",<br>";
	$html .= "      Autorizo el Ingreso de: <br><b>".$DescripcionProducto."</b><br>";
	$html .= "		<b>Lote:</b>".$lotec." <b>Fecha Vencimiento:</b>".$fecha_venc;
	$html .= "      </td>";
	$html .= "    </tr>";
	$html .= "    <tr>";
    $html .= "      <td align=\"center\">";
	$html .= "      <b>Observacion</b><br>";
	$html .= "      <textarea id=\"justificacion_autorizacion\"></textarea>";
	$html .= "      </td>";
    $html .= "    </tr>";
    $html .= "		<tr>";
	$html .= "		<td align=\"center\">";
$param="'".$doc_tmp_id."','".$codigo_producto."','".$cantidad."','".$porcentaje_gravamen."','".$total_costo."','".$usuario_id."','".$fecha_venc."','".$lotec."','".$localizacion."','".$UsuarioAutorizador."'";
	$html .= "		<input type=\"button\" class=\"modulo_table_list\" value=\"Autorizar\" onclick=\"xajax_RegistrarAutorizacion(".$param.",document.getElementById('justificacion_autorizacion').value,'".$OrdenPedidoId."',document.getElementById('empresa_id').value,document.getElementById('centro_utilidad').value,document.getElementById('bodega').value,'".$ItemId."');\">";
	$html .= "		</td>";
	$html .= "		</tr>";
	$html .= "      </table>";
		  
  $html=$objResponse->setTildes($html);
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
  function RegistrarAutorizacion($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id,$fecha_venc,$lotec,$localizacion,$UsuarioAutorizador,$Justificacion_Autorizacion,$OrdenPedidoId,$EmpresaId,$CentroUtilidad,$Bodega,$ItemId)
  {
  $objResponse = new xajaxResponse();
  $Primero = "0";
  $Segundo = "0";
  
  $sql = AutoCarga::factory("ConsultasAutorizacionesIngreso","classes","app","Inv_AutorizacionesIngreso");
   
	if(empty($Justificacion_Autorizacion))
	{
	$objResponse->alert("Es Necesario Diligenciar La Observacion!!!");
	}
	else
	 {
		$Autorizaciones=$sql->ConsultarPrimerAutorizador($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id);
		
		//En caso de que tenga una Justificacion en la BD
		$Justificacion_BD = $Autorizaciones[0]['observacion_autorizacion'];
		
		if($Autorizaciones[0]['usuario_id_autorizador']=="")
		{
		$Primero ="0";
		}
			else
				{
				$Primero ="1";
				$Usuario_1=$Autorizaciones[0]['usuario_id_autorizador'];
				}
		
		if($Autorizaciones[0]['usuario_id_autorizador_2']=="")
		{
		$Segundo ="0";
		}
			else
				{
				$Segundo ="1";
				$Usuario_2=$Autorizaciones[0]['usuario_id_autorizador'];
				}
	 
	 //=
	
	
	 if($Primero=="0")
	 {
	 $Param=" usuario_id_autorizador =".$UsuarioAutorizador." ";
	 $Justificacion_Final=" Autorizador 1: ".$Justificacion_Autorizacion;
	 $Token=$sql->Autorizar($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id,$Param,$Justificacion_Final);
	 }
	 else
		if($Segundo=="0")
		{
		 $Justificacion_Final= $Justificacion_BD."... Autorizador 2: ".$Justificacion_Autorizacion;
		 $Param=" usuario_id_autorizador_2 =".$UsuarioAutorizador." ";
		 $Token=$sql->Autorizar($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id,$Param,$Justificacion_Final);
			if($Token)
			{
			$ItemId_=$sql->ObtenerItemId();
			$html ="REALIZADO CON EXITO!!!";
			$Token_=$sql->AgregarItemADocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id,$fecha_venc,$lotec,$localizacion,$UsuarioAutorizador,$OrdenPedidoId,$EmpresaId,$CentroUtilidad,$Bodega,$ItemId_[0]['item'],$ItemId);
			//$Token__=$sql->ItemAgregadoCompras($codigo_producto,$cantidad,$lotec,$fecha_venc,$ItemId);
			$Token__=$sql->CambioEstadoAutorizacion($EmpresaId,$CentroUtilidad,$Bodega,$OrdenPedidoId,$codigo_producto,$fecha_venc,$lotec,$doc_tmp_id);
      $objResponse->script("xajax_Listar_ProductosPorAutorizar('','".$EmpresaId."','','1');");
			}
			else
				$html ="FALLÓ!!!";
		 }
	 
	 

	 $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
	 }
	
    
   // $objResponse->call("MostrarSpan");
    return $objResponse;
  }
 
  
?>