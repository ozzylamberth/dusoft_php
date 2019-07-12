<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos_ESM_OrdenesRequisicion.php
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
   function Buscar_OrdenRequisicion($orden_requisicion_id)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
      $datos =$sql->Obtener_OrdenesRequisicion($orden_requisicion_id);
      //print_r($datos);
    if(!empty($datos))
      {       
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"5%\">#</td>\n";
      $html .= "			<td width=\"40%\" >ESM</td>\n";
      $html .= "			<td width=\"40%\" >FUERZA</td>\n";
      $html .= "			<td width=\"5%\">SEL</td>\n";
      $html .= "		</tr>\n";
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
	         
      $html .= "		<tr class=\"".$est."\" align=\"center\" >\n";
      $html .= "			<td align=\"center\" class=\"label_error\"><u><i>".$datos['orden_requisicion_id']."</u></i></td>\n";
      $html .= "			<td >".$datos['nombre_tercero']."</td>\n";
      $html .= "			<td >".$datos['descripcion']."</td>\n";
      $html .= "      <td align=\"center\">";
      if($datos['oaux']<0)
      {
        $direccion="app_modules/ESM_OrdenesRequisicion/Imprimir/imprimir_producto.php";
        $script = "Imprimir('".$direccion."','".$_REQUEST['datos']['empresa_id']."','".$datos['orden_requisicion_id']."');";
      }
      else
          {
          $direccion="app_modules/ESM_OrdenesRequisicion/Imprimir/imprimir_producto_suministro.php";
          $script = "Imprimir('".$direccion."','".$_REQUEST['datos']['empresa_id']."','".$datos['orden_requisicion_id']."');";
          }
      $html .= "       <a onclick=\"".$script."\">";
      $html .= "			 <img title=\"VER ORDEN DE REQUISICION\" src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
      $html .= "      </td>\n";
      
      $html .= "		</tr>\n";
      $html .= "    </table>";
      }
      
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      
      $objResponse->assign("listado","innerHTML",$html);
      return $objResponse;
		}
  
  
  function Listado_Temporales($offset)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
      $accion['opcion']=ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Modificar_Temporal");
      $datos =$sql->Obtener_DocsTemporales($offset);
     
      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
      $action['paginador'] = "Paginador(";
      $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
      $html .= "   <form id=\"ProductosEnLista\" name=\"ProductosEnLista\"> ";
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">ESM SOLICITANTE</td>\n";
      $html .= "			<td width=\"10%\" >FUERZA</td>\n";
      $html .= "			<td width=\"10%\" >TIPO REQUISICION</td>\n";
      $html .= "			<td width=\"10%\">FARMACIA</td>\n";
      $html .= "			<td width=\"10%\">USUARIO</td>\n";
      $html .= "			<td width=\"8%\">FECHA</td>\n";
      $html .= "			<td width=\"2%\">MOD</td>\n";
      $html .= "		</tr>\n";
            
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      foreach($datos as $k1 => $dtl)
      {
	  
	       $datos_x_pac =$sql->Consultar_Registros_tmp_suministro_pac($dtl['orden_requisicion_tmp_id']);
	  if(empty($datos_x_pac))
	  {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      
      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      $html .= "			<td align=\"center\"><b>".$dtl['nombre_tercero']."</b></td>\n";
      $html .= "			<td align=\"left\">".$dtl['tipo_fuerza']."</td>\n";
      $html .= "			<td align=\"left\">".$dtl['descripcion_orden_requisicion']."</td>\n";
      $html .= "			<td align=\"left\">".$dtl['razon_social']."</td>\n";
      $html .= "			<td align=\"left\">".$dtl['nombre']."</td>\n";
      $html .= "			<td align=\"left\">".$dtl['fecha_registro']."</td>\n";
      $html .= "      <td align=\"center\">";
      $html .= "      <a href=\"".$accion['opcion']."&datos[empresa_id]=".$dtl['empresa_id_registro']."&orden_requisicion_tmp_id=".$dtl['orden_requisicion_tmp_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\" >";
      $html .= "			 <img title=\"MODIFICAR LA ORDEN DE SUMINSTRO\" src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
      $html .= "      </td>\n";
      
      $html .= "		</tr>\n";
      }
	  }
      $html .= "    </table>";
    //  $boton= "<input type=\"button\" class=\"input-submit\" value=\"GUARDAR CAMBIOS\" onclick=\"xajax_Guardar_Registros(xajax.getFormValues('Productos'));\">";
      } 
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      
      $objResponse->assign("Temporales","innerHTML",$html);
      return $objResponse;
		}
 
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_Productos($Formulario,$offset)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
      $porcentaje = ModuloGetVar("","","ESM_PorcentajeIntermediacion");
      /*$datos_empresa = SessionGetVar("DatosEmpresaAF"); 
      $empresa_id=$datos_empresa['empresa_id'];*/
      $lista= $sql->ObtenerContratoId($_REQUEST['datos']['empresa_id']);
      $datos =$sql->ConsultarListaDetalle($Formulario,$lista,$_REQUEST['datos']['empresa_id'],$offset);
    
      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
      $action['paginador'] = "Paginador(xajax.getFormValues('FormularioBuscador')";
      $paginador = $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
	  
	      
      $html .= "   <form id=\"ProductosEnLista\" name=\"ProductosEnLista\"> ";
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
      $html .= "			<td width=\"30%\" >DESCRIPCION</td>\n";
      $html .= "			<td width=\"30%\" >INFORMACION</td>\n";
      $html .= "			<td width=\"8%\">CANTIDAD</td>\n";
      $html .= "			<td width=\"5%\">SELECCIONAR</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"center\" colspan=\"5\">PRODUCTOS</td>";
      $html .= "		</tr>\n";
      
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      $i=0;
      foreach($datos as $k1 => $dtl)
      {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      
      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      $html .= "			<td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
      $html .= "			<td align=\"left\">".$dtl['descripcion']."</td>\n";
      if($dtl['resultado']=='0')
        {
        $class=" class=\"label_error\"" ;
        $mensaje=" PRODUCTO NO PACTADO " ;
        $porcentaje_intermediacion = $porcentaje;
        }
        else
          {
          $class=" " ;
          $mensaje=" PRODUCTO PACTADO " ;
          $porcentaje_intermediacion = "0";
          }
      $html .= "			<td align=\"center\" ".$class.">".$mensaje."</td>\n";
	  
      $html .= "			<td align=\"center\" ><input   type=\"text\" name=\"cantidad_solicitada".$i."\" id=\"cantidad_solicitada".$i."\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\"  ></td>\n";
     
      $html .= "";  $html .= "      <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$_REQUEST['datos']['empresa_id']."\">
                                          <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$Formulario['orden_requisicion_tmp_id']."\">
                                          <input type=\"hidden\" name=\"valor".$i."\" id=\"valor".$i."\" value=\"".$dtl['precio']."\">
                                          <input type=\"hidden\" name=\"porc_iva".$i."\" id=\"porc_iva".$i."\" value=\"".$dtl['porc_iva']."\">
                                          <input type=\"hidden\" name=\"sw_pactado".$i."\" id=\"sw_pactado".$i."\" value=\"".$dtl['resultado']."\">
                                          <input type=\"hidden\" name=\"porcentaje_intermediacion".$i."\" id=\"porcentaje_intermediacion".$i."\" value=\"".$porcentaje_intermediacion."\">
                                          <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">
                                          </td>\n";
      $html .= "			<td align=\"center\" id=\"ok".$i."\"><input type=\"checkbox\" value=\"".$dtl['codigo_producto']."\" class=\"checkbox\" name=\"".$i."\" id=\"".$i."\" onclick=\"if(document.getElementById('".$i."').checked==true) xajax_Buscar_pacientes_s(this.value,'".$dtl['codigo_producto']."',document.getElementById('cantidad_solicitada".$i."').value,'".$dtl['descripcion']."',document.getElementById('valor".$i."').value,document.getElementById('porc_iva".$i."').value,document.getElementById('sw_pactado".$i."').value,document.getElementById('porcentaje_intermediacion".$i."').value,document.getElementById('orden_requisicion_tmp_id').value);\">\n";
    
      //$html .= "			<td align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" id=\"\"></td>\n";
      $html .= "		</tr>\n";
      $i++;
      }
      $html .= "    </table>";
      $boton= "<input type=\"button\" class=\"input-submit\" value=\"GUARDAR CAMBIOS\" onclick=\"xajax_Guardar_Registros(xajax.getFormValues('Productos'));\">";
     
     
	  
	  } 
	 
	  
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      $objResponse->assign("paginador","innerHTML",$paginador);
      $objResponse->assign("Boton_ListaProductos","innerHTML",$boton);
      $objResponse->assign("ListaProductos","innerHTML",$html);
      $objResponse->script("ListaProductos.style.display=\"\";");
     // $objResponse->script("ListaProductos('".$Formulario['codigo_lista']."');");
      return $objResponse;
		}
  /*  PACIENTES */
  
  
  
  function Buscar_pacientes_s($value,$producto,$cantidad,$descripcion,$valor_,$porc_iva,$sw_pactado,$porcentaje_intermedia,$orden_id,$Formulario)
  {
    $objResponse = new xajaxResponse();
  
	 $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
    if($cantidad!="")
   {
   
        $total=$sql->Cantidad_ProductoTemporal($orden_id,$producto);
		$html .= "   <form id=\"PacientesEnLista\" name=\"PacientesEnLista\"> ";
		$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
		$html .= "			<td width=\"30%\" >DESCRIPCION</td>\n";
		$html .= "			<td width=\"8%\">CANTIDAD</td>\n";
		$html .= "			<td width=\"8%\">PENDIENTE</td>\n";
	    $html .= "		</tr>\n";
		$html .= "		<tr class=\"modulo_list_claro\" >\n";
		$html .= "			<td width=\"10%\">".$producto."</td>\n";
		$html .= "			<td width=\"30%\" >".$descripcion."</td>\n";
		$html .= "			<td width=\"8%\">".$cantidad."</td>\n";
		$html .= " <td width=\"8%\" > <input readonly=\"true\" type=\"input-text\" name=\"cantidad_pendiente\" id=\"cantidad_pendiente\" value=\"".($cantidad-$total['total'])."\" class=\"input-text\"></td>\n";
		$html .= "		</tr>\n";
		$html .= "	</table><BR>";
		
		
		  $tmporales =$sql->Consultar_Registros_tmp_suministro($orden_id,$producto);
	  if(!empty($tmporales))
	  {
			$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";

			$html .= "		<tr class=\"formulacion_table_list\" >\n";
			$html .= "			<td colspan=\"10\">PACIENTES INGRESADOS</td>\n";
			$html .= "		</tr>\n";
			
			$html .= "		<tr class=\"formulacion_table_list\" >\n";
			$html .= "			<td width=\"10%\">IDENTIFICACION</td>\n";
			$html .= "			<td width=\"30%\" >NOMBRE</td>\n";
			$html .= "			<td width=\"8%\">CANTIDAD</td>\n";
			$html .= "			<td width=\"5%\">OP</td>\n";
			$html .= "		</tr>\n";
			$est = "modulo_list_claro";
			$bck = "#DDDDDD";
			
			foreach($tmporales as $k1 => $dtll)
			{
		      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
		      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
				$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
				$html .= "			<td align=\"center\"><b>".$dtll['tipo_id_paciente']." ".$dtll['paciente_id']."</b></td>\n";
				$html .= "			<td align=\"left\">".$dtll['nombre_completo']."</td>\n";
				$html .= "			<td align=\"left\">".$dtll['cantidad']."</td>\n";
				$html .= "      <td align=\"center\">";
				$html .= "      <a onclick=\"xajax_Borrar_Item_suminstr('".$value."','".$producto."','".$cantidad."','".$descripcion."','".$valor_."','".$porc_iva."','".$sw_pactado."','".$porcentaje_intermedia."','".$orden_id."','".$dtll['tipo_id_paciente']."','".$dtll['paciente_id']."','".$producto."');\">";
				$html .= "			 <img title=\"ELIMINAR ITEM DE LA ORDEN DE SUMINISTRO\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">";
				$html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
				$html .= "      </td>\n";
				$html .= "		</tr>\n";

             }
	         $html .= "    </table>";
			
			 if($total['total']==$cantidad)
			 {
					$html .= "	<table align=\"RIGHT\" border=\"0\" width=\"10%\" >\n";

					$html .= "		<tr  align=\"RIGHT\">";
					$html .= "      <td  >";
					$html .= "      <input type=\"button\" class=\"input-submit\" value=\"GENERAR\" style=\"width:100%\" onclick=\"xajax_Regresar_Buscardor_Item('".$value."','".$producto."','".$cantidad."','".$descripcion."','".$valor_."','".$porc_iva."','".$sw_pactado."','".$porcentaje_intermedia."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));\" >";
					$html .= "      </td>";
									
					
					$html .= "		</tr>\n";
					$html .= "    </table>";
			}
			 
			 
			 
	     }
	  	
		$Tipo=$sql->ConsultarTipoId();
     
    	$html .= "	<br><table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			<td colspan=\"10\">BUSCADOR DE PACIENTES</td>\n";
		$html .= "		</tr>\n";
		 
		$html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "			  <td >TIPO DOCUMENTO:</td>\n";
		$html .= "			  <td align=\"left\" class=\"modulo_list_claro\" colspan=\"4\">\n";
		$html .= "				  <select name=\"tipo_id_paciente\" class=\"select\">\n";
		$html .= "            <option value = '-1'>--  SELECCIONE --</option>\n";
		$csk = "";
		foreach($Tipo as $indice => $valor)
		{
				$sel = ($valor['tipo_id_tercero']==$request['tipo_id_paciente'])? "selected":"";
				$html .= "  <option value=\"".$valor['tipo_id_tercero']."\" ".$sel.">".$valor['descripcion']."</option>\n";
		}
		$html .= "				  </select>\n";
		$html .= "				</td>\n";
		$html .= "      <td >";
		$html .= "      IDENTIFICACION ";
		$html .= "      </td>";
		$html .= "      <td class=\"modulo_list_claro\" >";
		$html .= "      <input type=\"text\" class=\"input-text\" name=\"identificacion\" id=\"identificacion\" style=\"width:100%\">";
		$html .= "      </td>";
	    $html .= "		</tr>\n";
	    $html .= "		<tr class=\"formulacion_table_list\" >\n";
		$html .= "      <td colspan=\"1\" class=\"formulacion_table_list\" >";
		$html .= "      NOMBRE COMPLETO";
		$html .= "      </td>";
		$html .= "      <td class=\"modulo_list_claro\" >";
		$html .= "      <input type=\"text\" class=\"input-text\" name=\"nombre\" id=\"nombre\" style=\"width:100%\">";
		$html .= "      <td  class=\"modulo_list_claro\">";
	    $html .= "      <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$_REQUEST['orden_requisicion_tmp_id']."\" >";
	    $html .= "      <input type=\"button\" class=\"input-submit\" value=\"buscar\" style=\"width:100%\" onclick=\"xajax_Buscar_pacientes_s('".$value."','".$producto."','".$cantidad."','".$descripcion."','".$valor_."','".$porc_iva."','".$sw_pactado."','".$porcentaje_intermedia."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));\" >";
	    $html .= "      </td>";
	    $html .= "		</tr>\n";
	    $html .= "  </table><br>\n";
				
	  $datos =$sql->Pacientes_esm($Formulario,$orden_id,$producto,$offset);
     
      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      { 
	  
	
	  	$html .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
		$html .= "                 </div>\n";
		$html .= "                                    <div id=\"error\" class='label_error'></div>";
		       
	  
	 
    //   $html .= " $paginador";
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">IDENTIFICACION</td>\n";
      $html .= "			<td width=\"30%\" >NOMBRE</td>\n";
       $html .= "			<td width=\"8%\">CANTIDAD</td>\n";
	   
      $html .= "			<td width=\"5%\">SELECCIONAR</td>\n";
      $html .= "		</tr>\n";
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      $i=0;
      foreach($datos as $k1 => $dtl)
      {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

		$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
		$html .= "			<td align=\"center\"><b>".$dtl['tipo_id_paciente']." ".$dtl['paciente_id']."</b></td>\n";
		$html .= "			<td align=\"left\">".$dtl['nombre_completo']."</td>\n";

		$html .= "      <input type=\"hidden\" name=\"tipo_paciente".$i."\" id=\"tipo_paciente".$i."\" value=\"".$dtl['tipo_id_paciente']."\">
		<input type=\"hidden\" name=\"paciente".$i."\" id=\"paciente".$i."\" value=\"".$dtl['paciente_id']."\"> ";
		$html .= "                                              <td>";
		$html .= "                                                <input style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"cantidad_solicitada".$i."\" id=\"cantidad_solicitada_".$i."\"   onkeypress=\"return acceptNum(event);\" onkeyup=\"ValidarCantidad('cantidad_solicitada".$i."',document.getElementById('cantidad_solicitada_".$i."').value,'".$cantidad."','hell$i');\">";
		$html .= "                                             </td>";


		$html .= "";
		$html .= "                                                <input ".$habilitar." style=\"width:100%\" type=\"checkbox\" class=\"input-text\" name=\"".$i."\" id=\"".$i."\" value=\"".$i."\" >";
		$html .= "                                             </td>";

		$html .= "		</tr>\n";
      $i++;
      }
	   $html .= "    </table>";
	   
	   
	    $html .= "	<table align=\"RIGHT\" border=\"0\" width=\"15%\" >\n";
     
	     $html .= "		<tr  align=\"RIGHT\">";
	    $html .= "      <td  >";
		  $html .= "                                               <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\" >";
	
	  //  $html .= "      <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$_REQUEST['orden_requisicion_tmp_id']."\" >";
	    $html .= "      <input type=\"button\" class=\"input-submit\" value=\"GUARDAR\" style=\"width:100%\" onclick=\"xajax_GuardarPT('".$value."','".$producto."','".$cantidad."','".$descripcion."','".$valor_."','".$porc_iva."','".$sw_pactado."','".$porcentaje_intermedia."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));\" >";
	    $html .= "      </td>";
	    $html .= "		</tr>\n";
      $html .= "    </table>";
		
		}
		
     $html .= "  </form>";
	}else
	{
		$html .= "	<table align=\"center\" border=\"0\" width=\"50%\" class=\"modulo_table_list\">\n";
		$html .= "		<tr  align=\"center\" class=\"label_error\" >\n";
		$html .= "			<td width=\"100%\">INGRESE UNA CANTIDAD A SOLICITAR</td>\n";
		$html .= "		</tr>\n";
		$html .= "	</table><BR><BR>";
	
	}
//$objResponse->assign("paginador_r","innerHTML",$paginador);
	   $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
	  $objResponse->call("MostrarSpan");
      return $objResponse;
  }

  /*  GUARDAR SUMINISTRO TMP */
  
  
  function GuardarPT($value,$producto,$cantidad_d,$descripcion,$valor_,$porc_iva,$sw_pactado,$porcentaje_intermedia,$orden_id,$Formulario)
	  {
	    $objResponse = new xajaxResponse();
		
	
	    $obje = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
		         
	    $k=0;
	    for($i=0;$i<=$Formulario['registros'];$i++)
		{
		
		
			if($Formulario[$i]!="")
			{
				$cantidad = $obje->Cantidad_ProductoTemporal($orden_id,$producto);
			
			//$objResponse->alert($cantidad['total']);
				if(($cantidad['total']+$Formulario['cantidad_solicitada'.$i])<=$cantidad_d)
				{
							    
						if($Formulario['cantidad_solicitada'.$i] == "")
					    {
					      $objResponse->assign('error_doc',"innerHTML","NO HA DILIGENCIADO UNA CANTIDAD A INGRESAR");
					    }
					$Retorno = $obje->GuardarTemporal($orden_id,$producto,$Formulario['cantidad_solicitada'.$i],$Formulario['tipo_paciente'.$i],$Formulario['paciente'.$i]);
				//	$objResponse->assign("".$Formulario['formula_id']."@".$Formulario['codigo_producto']."","innerHTML",$consulta->mensajeDeError);
					
					
					if($Retorno)
						$k++;
				}
			}
	    }
	    
		if($k!=0)
		{
			//$objResponse->script(" Recargar_informacion('".$empresa['bodega']."');");

			$objResponse->script("xajax_Buscar_pacientes_s('".$value."','".$producto."','".$cantidad_d."','".$descripcion."','".$valor_."','".$porc_iva."','".$sw_pactado."','".$porcentaje_intermedia."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));");
		//	$objResponse->script("xajax_MostrarProductox('".$Formulario['formula_id']."');");
		}
	    if($Retorno === false)
	    {
	      $objResponse->assign('error_doc','innerHTML',$obje->mensajeDeError);
	    }
 
  
  
   return $objResponse;
  }
  	/* ELIMINAR BORRADORRES TMP DE PACIENTES */
   function Borrar_Item_suminstr($value,$producto,$cantidad_d,$descripcion,$valor_,$porc_iva,$sw_pactado,$porcentaje_intermedia,$orden_id,$tipo_id_paciente,$paciente_id)
  {
      $objResponse = new xajaxResponse();
	  
      $obje = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
  
       $token=$obje->Borrar_Item_suministro($orden_id,$producto,$tipo_id_paciente,$paciente_id);
  
	if($token)
	{
  
		$objResponse->script("xajax_Buscar_pacientes_s('".$value."','".$producto."','".$cantidad_d."','".$descripcion."','".$valor_."','".$porc_iva."','".$sw_pactado."','".$porcentaje_intermedia."','".$orden_id."',xajax.getFormValues('PacientesEnLista'));");

		}
  else
  $objResponse->alert("Error en el Borrado...!!");
  
  return $objResponse;
  }
  
  /* PRODUCTOS REGISTRAR TEMPORAL  DE LA ORDEN */
  
  
  function Regresar_Buscardor_Item($value,$producto,$cantidad_d,$descripcion,$valor_,$porc_iva,$sw_pactado,$porcentaje_intermedia,$orden_id,$Formulario)
  {
		  $objResponse = new xajaxResponse();
		
		  $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
		  $k=0;
		
			$token=$sql->Insertar_ProductoTemporal($orden_id,$producto,$cantidad_d,$valor_,$porc_iva,$sw_pactado,$porcentaje_intermedia);
		    $k++;
				  
		  if($k>0)
		  {
		  $objResponse->script("xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'),'1');");
		  $objResponse->script("xajax_Listado_Productos_TMP_s('".$orden_id."');");
		  $objResponse->call("OcultarSpan");
		  
		  }
		  
		
		  else
		  $objResponse->alert("Error en el Ingreso...!!");
		  
		  return $objResponse;
  }
  
  /* LISTA DE PRODUCTOS TEMPORALES */
  
    function Listado_Productos_TMP_s($orden_id)
	{
      $objResponse = new xajaxResponse();
      $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
      $datos =$sql->Listado_ProductosTemporales($orden_id);
     
      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
	  	//$html .= "   <form id=\"Pacientestemporales_s\" name=\"Pacientestemporales_s\"> ";
      $html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
      $html .= "			<td width=\"30%\" >DESCRIPCION</td>\n";
      $html .= "			<td width=\"30%\" >INFORMACION</td>\n";
      $html .= "			<td width=\"8%\">CANTIDAD</td>\n";
      $html .= "			<td width=\"4%\">OP</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"center\" colspan=\"6\">PRODUCTOS REGISTRADOS</td>";
      $html .= "		</tr>\n";
      
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      $i=0;
      if($_REQUEST['datos']['ssiid']=='0')
      {
        $readonly=" readonly=\"true\" ";
      }else
          {
            $readonly=" ";
          }
      $i=0;
	  $c=0;	
      foreach($datos as $k1 => $dtl)
      {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      $datos_x =$sql->Consultar_Registros_tmp_suministro($orden_id,$dtl['codigo_producto']);
	  $tamano_=count($datos_x);
      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      $html .= "			<td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
      $html .= "			<td align=\"left\">".$dtl['descripcion']."</td>\n";
      if($dtl['sw_pactado']=='0')
        {
        $class=" class=\"label_error\"" ;
        $mensaje=" PRODUCTO NO PACTADO " ;
        }
        else
          {
          $class=" " ;
          $mensaje=" PRODUCTO PACTADO " ;
          }
      $html .= "			<td align=\"center\" ".$class.">".$mensaje."</td>\n";
      $html .= "			<td align=\"center\"><input type=\"text\" readonly=\"true\"  value=\"".$dtl['cantidad_solicitada']."\" name=\"cantidad_solicitada".$i."\" id=\"cantidad_solicitada".$i."\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\"></td>\n";
        	
      $html .= "      <td align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"".$i."\" id=\"".$i."\" value=\"".$dtl['codigo_producto']."\"  >\n";
	 // $html .= "      <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$orden_requisicion_tmp_id."\"> ";
	  $html .= "      <a onclick=\"xajax_Borrar_Item_suminstro_Tmp('".$orden_id."','".$dtl['codigo_producto']."');\">";
	  $html .= "			 <img title=\"ELIMINAR ITEM DE LA ORDEN DE SUMINISTRO\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">";
	  //$html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
	  $html .= "      </td>\n";
	
     if($_REQUEST['datos']['ssiid']=='1')
      {	
	  
	  
	    $html .= "		<tr   colspan=\"10\">\n";
		$html .= "      <td class=\"modulo_list_oscuro\" colspan=\"10\" align=\"center\">";
		$html .= "	<table align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list\">\n";
		
		$datos_x =$sql->Consultar_Registros_tmp_suministro($orden_id,$dtl['codigo_producto']);
		
		foreach($datos_x as $k1 => $deta)
		{
		($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      
		$html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
		$html .= "			<td  colspan=\"1\"align=\"center\"><b>".$deta['tipo_id_paciente']." ".$deta['paciente_id']."</b></td>\n";
		$html .= "			<td  colspan=\"6\" align=\"left\">".$deta['nombre_completo']."</td>\n";
		$html .= "			<td colspan=\"1\" align=\"center\"><input type=\"text\" value=\"".$deta['cantidad']."\" name=\"".$dtl['codigo_producto']."@".$deta['tipo_id_paciente']."@".$deta['paciente_id']."\" id=\"".$dtl['codigo_producto']."@".$deta['tipo_id_paciente']."@".$deta['paciente_id']."\" class=\"input-text\" style=\"width:60%\" onkeypress=\"return acceptNum(event)\"></td>\n";
       
		$html .= "		</tr>\n";
		$c++;
		 // $html .= "      <input type=\"hidden\" name=\"subregistros\" id=\"subregistros\" value=\"".$c."\">";
		
		}
	
		$html .= "    </table>";
		$html .= "      </td>\n";
		$html .= "		</tr>\n";
     
	 	 
      }
	  
	  
      $html .= "		</tr>\n";
      $i++;
      }
	  
	    $html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
	  
	  $html .= "    </table>";
        

		if($_REQUEST['datos']['ssiid']=='1')
            {
			
			
			 $html .= "	<table align=\"center\" border=\"0\" width=\"98%\" >\n";
             $html .= "    <tr align=\"center\" >";
             $html .= "      <td colspan=\"2\">";
            $html .= "          <input type=\"button\" class=\"input-submit\" value=\"MODIFICAR ORDEN DE SUMINISTRO\" onclick=\"xajax_Modificar_Informacion_por_pac(xajax.getFormValues('FormularioProductosTemporal_'),'".$orden_id."');\"> ";
            $html .= "      </td>";
            $html .= "    </tr>";
            }
            $html .= "    </table>";
			$html .= " </form>";
      } 
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      $objResponse->assign("ProductosEnTemporal_s","innerHTML",$html);
      return $objResponse;
    }
  
  /* ELIMINAR PRODUCTOS TMP */
  
    function Borrar_Item_suminstro_Tmp($orden_id,$producto)
  {
      $objResponse = new xajaxResponse();
	  
      $obje = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
  
       $token=$obje->Borrar_Item($orden_id,$producto);
	   $token_2=$obje->Borrar_Item_s($orden_id,$producto);
  
	if($token)
	{
  
	$objResponse->script("Cerrar('Contenedor');");
	$objResponse->script("xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'),'1');");
	$objResponse->script("xajax_Listado_Productos_TMP_s('".$orden_id."');");
		}
  else
  $objResponse->alert("Error en el Borrado...!!");
  
  return $objResponse;
  }
  
  
  /*  MODIFICAR PRODUCTOS POR PACIENTES */
	
	 function Modificar_Informacion_por_pac($forma,$orden_id)
	  {
	  $objResponse = new xajaxResponse();
	
    
	 $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
	
    	$k=0;
	
   $sum=0;
	for($i=0;$i<=$forma['registros'];$i++)
		{
		
	  	if($forma[$i]!="")
			{
			
			foreach($forma as $key=>$valor)
			{		 
				list($producto,$tipo,$paciente) = explode("@",$key);
				if($forma[$i]==$producto)
				{
					$upd_x_pac=$sql->Modificar_Producto_X_PAC($orden_id,$producto,$forma[$key],$tipo,$paciente);
                  
			    }
				  
			}
			
				
				 
		   }
		 
		 
		 }
		 
		$datos_x =$sql->Listado_ProductosTemporales($orden_id);
		foreach($datos_x as $ky=>$val)
		{		 
		    $cantidad =$sql->consultar_moficacion_x_prodc($orden_id,$val['codigo_producto']);
		    $upd_x_prod=$sql->Modificar_Producto_X_producto($orden_id,$val['codigo_producto'],$cantidad['total']);
		
		
		}
		 
	
	  $objResponse->script("Cerrar('Contenedor');");
	  $objResponse->script("xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'),'1');");
	  $objResponse->script("xajax_Listado_Productos_TMP_s('".$orden_id."');");
	  
	
	  return $objResponse;
	  }


  
  
  
  
  
  
  
  
  
  
  
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_Productos_TMP($orden_requisicion_tmp_id)
		{
      $objResponse = new xajaxResponse();
      $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
      $datos =$sql->Listado_ProductosTemporales($orden_requisicion_tmp_id);
    
      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
      $html .= "			<td width=\"30%\" >DESCRIPCION</td>\n";
      $html .= "			<td width=\"30%\" >INFORMACION</td>\n";
      $html .= "			<td width=\"8%\">CANTIDAD</td>\n";
      $html .= "			<td width=\"4%\">OP</td>\n";
      $html .= "			<td width=\"4%\">SEL</td>\n";
       //$html .= "			<td width=\"30%\">ADICIONAR</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"center\" colspan=\"6\">PRODUCTOS REGISTRADOS</td>";
      $html .= "		</tr>\n";
      
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      $i=0;
      if($_REQUEST['datos']['ssiid']=='0')
      {
        $readonly=" readonly=\"true\" ";
      }else
          {
            $readonly=" ";
          }
      $i=0;
      foreach($datos as $k1 => $dtl)
      {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      
      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      $html .= "			<td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
      $html .= "			<td align=\"left\">".$dtl['descripcion']."</td>\n";
      if($dtl['sw_pactado']=='0')
        {
        $class=" class=\"label_error\"" ;
        $mensaje=" PRODUCTO NO PACTADO " ;
        }
        else
          {
          $class=" " ;
          $mensaje=" PRODUCTO PACTADO " ;
          }
      $html .= "			<td align=\"center\" ".$class.">".$mensaje."</td>\n";
      $html .= "			<td align=\"center\"><input type=\"text\" ".$readonly." value=\"".$dtl['cantidad_solicitada']."\" name=\"cantidad_solicitada".$i."\" id=\"cantidad_solicitada".$i."\" class=\"input-text\" style=\"width:100%\" onkeypress=\"return acceptNum(event)\"></td>\n";
             
      $html .= "      <td align=\"center\">";
      $html .= "      <input type=\"hidden\" name=\"orden_requisicion_tmp_id\" id=\"orden_requisicion_tmp_id\" value=\"".$orden_requisicion_tmp_id."\"> ";
      $html .= "      <a onclick=\"xajax_Borrar_Item('".$orden_requisicion_tmp_id."','".$dtl['codigo_producto']."');\">";
      $html .= "			 <img title=\"ELIMINAR ITEM DE LA ORDEN DE REQUISICION\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">";
      $html .= "      <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
      $html .= "      </td>\n";
      if($_REQUEST['datos']['ssiid']=='1')
      {
      $html .= "			<td align=\"center\" id=\"yes".$i."\"><input type=\"checkbox\" class=\"checkbox\" name=\"".$i."\" id=\"".$i."\" value=\"".$dtl['codigo_producto']."\"></td>\n";
      }
      $html .= "		</tr>\n";
      $i++;
      }
            if($_REQUEST['datos']['ssiid']=='1')
            {
            $html .= "    <tr class=\"formulacion_table_list\">";
            $html .= "      <td colspan=\"4\">";
            $html .= "      </td>";
            $html .= "      <td colspan=\"2\">";
            $html .= "          <input type=\"button\" class=\"input-submit\" value=\"MODIFICAR ORDEN DE REQUISICION\" onclick=\"xajax_Guardar_Cambios(xajax.getFormValues('FormularioProductosTemporal'));\"> ";
            $html .= "      </td>";
            $html .= "    </tr>";
            }
            $html .= "    </table>";
      } 
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      $objResponse->assign("ProductosEnTemporal","innerHTML",$html);
      return $objResponse;
    }
  
  function Guardar_Registros($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
  $k=0;
  for($i=0;$i<=$Formulario['registros'];$i++)
  {
    if($Formulario[$i]!="")
    {
      if($Formulario['cantidad_solicitada'.$i]!="")
        {
          $token=$sql->Insertar_ProductoTemporal($Formulario['orden_requisicion_tmp_id'],$Formulario[$i],$Formulario['cantidad_solicitada'.$i],$Formulario['valor'.$i],$Formulario['porc_iva'.$i],$Formulario['sw_pactado'.$i],$Formulario['porcentaje_intermediacion'.$i]);
          $k++;
         if($token)
              $objResponse->script("document.getElementById('ok".$i."').style.backgroundColor='green';");
              else
                $objResponse->script("document.getElementById('ok".$i."').style.backgroundColor='red';");
        }
        else
            {
            $objResponse->script("document.getElementById('ok".$i."').style.backgroundColor='red';");
            }
    }
  }
  
  if($k>0)
  {
  $objResponse->script("xajax_Listado_Productos(xajax.getFormValues('FormularioBuscador'),'1');");
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
  
   function Borrar_Item($orden_requisicion_tmp_id,$codigo_producto)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
  
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
  
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_ESM($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
  {
	$objResponse = new xajaxResponse();
	$sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");

	$Terceros=$sql->Listar_ESM($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset);

	$action['paginador'] = "PaginadorESM('".$tipo_id_tercero."','".$tercero_id."','".$nombre_tercero."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:80%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">ESM - ESTABLECIMIENTOS DE SANIDAD MILITAR</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
	$html .= "      <td width=\"50%\">NOMBRE</td>\n";
	$html .= "      <td width=\"25%\">UBICACION</td>\n";
	$html .= "      <td width=\"7%\">SELECCIONAR</td>\n";


	$html .= "    </tr>\n";

	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
	foreach($Terceros as $key => $ED)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

	$html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
	$html .= "      <td>".$ED['identificacion']."</td>\n";
	$html .= "      <td>".$ED['nombre_tercero']." </td>\n";
	$html .= "      <td>".$ED['ubicacion']." </td>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <a href=\"#\" onclick=\"xajax_Borrar_TerceroESM('".$ED['tipo_id_tercero']."','".$ED['tercero_id']."')\">\n";
	$html .= "          <img title=\"QUITAR DE LA LISTA\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">\n";
	// Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
	$html .= "        </a>\n";
	$html .= "      </td>\n";

	$html .= "    </tr>\n";
	}
	$html .= "<center>";



	$html .= "    </table>\n";
	$html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado_ESM","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
 
   function Guardar_Cambios($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
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
  $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
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
  $url = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_OrdenesRequisicion")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
    
    
    $script = "window.location=\"".$url."\";";
    
  
  if($token)
  {
  $direccion="app_modules/ESM_OrdenesRequisicion/Imprimir/imprimir_producto.php";
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
    
    function Listado_Bodegas($empresa_id,$centro_utilidad)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Consultas_OrdenesRequisicion","classes","app","ESM_OrdenesRequisicion");
      $bodegas=$sql->Listado_Bodegas($empresa_id,$centro_utilidad);
      
      $html .= "<option value=\"\">-- SELECCIONAR --</option>";
      foreach($bodegas as $key => $valor)
      {
      $html .= "<option value=\"".$valor['bodega']."\">".$valor['descripcion']."</option>";
      }
      
      $objResponse->assign("bodega","innerHTML",$html);
      return $objResponse;
		}
/* listado de temporales de SUMINISTRO */

	function Listado_Temporales_Suministro($offset)
	{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
      $accion['opcion']=ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Modificar_Temporal_Suministro");
      $datos =$sql->Obtener_DocsTemporales($offset);
  
      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
      $action['paginador'] = "Paginador(";
      $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
      $html .= "   <form id=\"ProductosEnLista\" name=\"ProductosEnLista\"> ";
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">ESM SOLICITANTE</td>\n";
      $html .= "			<td width=\"10%\" >FUERZA</td>\n";
      $html .= "			<td width=\"10%\" >TIPO REQUISICION</td>\n";
      $html .= "			<td width=\"10%\">FARMACIA</td>\n";
      $html .= "			<td width=\"10%\">USUARIO</td>\n";
      $html .= "			<td width=\"8%\">FECHA</td>\n";
      $html .= "			<td width=\"2%\">MOD</td>\n";
      $html .= "		</tr>\n";
            
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
	  
      foreach($datos as $k1 => $dtl)
      {
	  
       $datos_x_pac =$sql->Consultar_Registros_tmp_suministro_pac($dtl['orden_requisicion_tmp_id']);
	  
       if(!empty($datos_x_pac))
	   {  
	  
	      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
	      
	      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
	     
	   
	   	  $html .= "			<td align=\"center\"><b>".$dtl['nombre_tercero']."</b></td>\n";
	      $html .= "			<td align=\"left\">".$dtl['tipo_fuerza']."</td>\n";
	      $html .= "			<td align=\"left\">".$dtl['descripcion_orden_requisicion']."</td>\n";
	      $html .= "			<td align=\"left\">".$dtl['razon_social']."</td>\n";
	      $html .= "			<td align=\"left\">".$dtl['nombre']."</td>\n";
	      $html .= "			<td align=\"left\">".$dtl['fecha_registro']."</td>\n";
	      $html .= "      <td align=\"center\">";
	      $html .= "      <a href=\"".$accion['opcion']."&datos[empresa_id]=".$dtl['empresa_id_registro']."&orden_requisicion_tmp_id=".$dtl['orden_requisicion_tmp_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."\" >";
	      $html .= "			 <img title=\"MODIFICAR LA ORDEN DE REQUISICION\" src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>";
	      $html .= "      </td>\n";
	      
	      $html .= "		</tr>\n";
        }
	  }
      $html .= "    </table>";
      } 
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      
      $objResponse->assign("Temporales_s","innerHTML",$html);
      return $objResponse;
		}

/*  CREAR DOCUMENTO DE ORDEN DE SUMINISTRO */

	function CrearDocumento_suministro($orden_requisicion_tmp_id)
	{
	  $objResponse = new xajaxResponse();
	  
	  $sql = AutoCarga::factory("Consultas_OrdenesSuministro","classes","app","ESM_OrdenesRequisicion");
	   
	  $DocTemporal=$sql->Obtener_InfoDocTemporal($orden_requisicion_tmp_id,$_REQUEST['datos']['empresa_id']);
	  $DocTemporal_Detalle =$sql->Listado_ProductosTemporales($orden_requisicion_tmp_id);
	   $DocTemporal_Detalle_paciente =$sql->Consultar_Registros_tmp_suministro_por_pac($orden_requisicion_tmp_id);
	   
		  $token = $sql->Insertar_Documento($DocTemporal);
	 
	    foreach($DocTemporal_Detalle as $key =>$valor)
	    {
			$sql->Insertar_ProductoDocumento($token,$valor);
	    }
		
		
		foreach($DocTemporal_Detalle_paciente as $ky =>$val)
	    {
				$sql->Insertar_ProductoDocumento_D_x_paciente($token,$val);
	    }
				

	  $url = ModuloGetURL("app","ESM_OrdenesRequisicion","controller","Crear_OrdenesSuministro")."&datos[empresa_id]=".$_REQUEST['datos']['empresa_id']."&datos[ssiid]=".$_REQUEST['datos']['ssiid']."";
	   
	    
	   $script = "window.location=\"".$url."\";";
	    
	  
	  if($token)
	  {
	  $direccion="app_modules/ESM_OrdenesRequisicion/Imprimir/imprimir_producto_suministro.php";
	
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

?>