<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */

 /**
    * Funcion registra los pacientes
    *
    * @param  var $tipo_id_paciente contiene el tipo de documento paciente
    * @param  var $paciente_id contiene el numero del documento del paciente
    * @param  var $primer_nombre contiene el primer nombre del paciente 
    * @param  var $segundo_nombre contiene el segundo nombre del paciente 
    * @param  var $primer_apellido contiene el primer apellido del paciente 
    * @param  var $segundo_apellido contiene el segundo apellido del paciente 
    * @param  var $codigo_producto contiene el codigo del producto 
    * @return Object $objResponse objeto de respuesta al formulario  
    */
 function RegistraPaciente($tipo_id_paciente,$paciente_id,$primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$codigo_producto)
 {
   $objResponse = new xajaxResponse();
   $html .= "<form name=\"formRegistrarPaciente\" id=\"formRegistrarPaciente\" method=\"post\" action=\"\">\n";
   $html .= "<table width=\"80%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
   $html .= "  <tr class=\"modulo_table_list_title\">\n";
   $html .= "    <td align=\"center\" colspan=\"2\">REGISTRAR PACIENTES\n";
   $html .= "    </td>\n";
   $html .= "  </tr>\n";
   $html .= "  <tr class=\"modulo_list_claro\">\n";
   $html .= "    <td width=\"30%\" align=\"left\">TIPO DE DOCUMENTO\n";
   $html .= "    </td>\n";
   $html .= "    <td align=\"left\">".$tipo_id_paciente."-EMPRESA".$empresa."\n";
   $html .= "    </td>\n";
   $html .= "  </tr>\n";
   $html .= "  <tr class=\"modulo_list_claro\">\n";
   $html .= "    <td width=\"20%\" align=\"left\">DOCUMENTO\n";
   $html .= "    </td>\n";
   $html .= "    <td align=\"left\">".$paciente_id."\n";
   $html .= "    </td>\n";
   $html .= "  </tr>\n";
   $html .= "  <tr class=\"modulo_list_claro\">\n";
   $html .= "    <td width=\"20%\" align=\"left\">NOMBRE\n";
   $html .= "    </td>\n";
   $html .= "    <td align=\"left\"width=\"60%\">".$primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido."\n";
   $html .= "    </td>\n";
   $html .= "  </tr>\n";
   $html .= "  <tr class=\"modulo_list_claro\">\n";
   $html .= "    <td width=\"20%\align=\"left\">DESCRIPCION\n";
   $html .= "    </td>\n";
   $html .= "    <td class=\"modulo_list_claro\" width=\"60%\">\n";
   $html .= "      <input type=\"text\" class=\"input-text\" id=\"descripcion\" style=\"width:100%;height:100%\" value=\"\">";
   $html .= "    </td>\n";
   $html .= "  </tr>\n";
   $html .= "  <tr class=\"modulo_list_claro\">\n";
   $html .= "    <td width=\"20%\align=\"left\">CODIGO\n";
   $html .= "    </td>\n";
   $html .= "    <td class=\"modulo_list_claro\" width=\"60%\">\n";
   $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo\" maxlength=\"100\" value=\"".$codigo_producto."\">";
   $html .= "    </td>\n";
   $html .= "  </tr>\n";
   $html .= "  <tr class=\"modulo_list_claro\">\n";
   $html .= "      <td align=\"center\"colspan=\"2\">\n";
   $html .= "         <a href=\"#\" onclick=\"xajax_BuscarProducto('".$tipo_id_paciente."','".$paciente_id."','".$primer_nombre."','".$segundo_nombre."','".$primer_apellido."','".$segundo_apellido."')\" class=\"label_error\"><border=\"0\" title=\"Buscar Productos\">BUSCAR PRODUCTO</a>\n";
   
   $html .= "      </td>\n";
   $html .= "  </tr>\n";
   $html .= "</table>\n";
   
   $html .= "<table align=\"center\">\n";
   $html .= "   <tr>"; 
   $html .= "    <td colspan=\"2\" align=\"center\"><br>";
   $html .= "      <input class=\"input-submit\" type=\"button\" name=\"guardar\" value=\"Guardar\" onClick=\"xajax_GuardarFarmacovigilancia('".$tipo_id_paciente."','".$paciente_id."',document.getElementById('descripcion').value,'".$codigo_producto."','".$empresa."');\">";
   $html .= "    </td>";
   $html .= "   </tr>";
   $html .= "</table>\n";
   $html .= "</form>";
   $html .= "<div id=\"ProductosMarcados\"></div>";
   $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
   $objResponse->call("MostrarSpan");
   return $objResponse;
 }

 /**
    * Funcion guarda los registros de pacientes
    *
    * @param  var $tipo_id_paciente contiene el tipo de documento paciente
    * @param  var $paciente_id contiene el numero del documento del paciente
    * @param  var $$descripcion contiene la descripcion de los efectos del medicamento
    * @param  var $codigo_producto contiene el codigo del producto 
    * @return Object $objResponse objeto de respuesta al formulario  
    */
 function GuardarFarmacovigilancia($tipo_id_paciente,$paciente_id,$descripcion,$codigo_producto)
 {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
  
    $token=$mdl->AgregarFarmacovigilancia($paciente_id,$tipo_id_paciente,$descripcion,$codigo_producto);
    if($token)
        {
        $objResponse->script("OcultarSpan();");
        $objResponse->alert("Registro Exitoso");
        }
        else
          {
          $objResponse->alert("Error al Registrar");
          }
    
    return $objResponse;
 }
 
 /**
    * Funcion guarda los registros de pacientes
    *
    * @param  var $codigo_producto contiene el codigo del producto 
    * @param  var $lote contiene el lote del producto
    * @param  var $id_producto_bloqueadoxlote contiene el id del producto q se bloqueo
    * @param  var $sw_bloqueado contiene el sw si esta bloqueado
    * @return Object $objResponse objeto de respuesta al formulario  
    */   
 
 function ListarProductosLote($codigo_producto,$nombre_producto,$concentracion,$clase_id,$subclase_id,$lote,$offset)
 {
   $objResponse = new xajaxResponse();
     
  $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
	$datos=$mdl->ListarProductosXLote($codigo_producto,$nombre_producto,$concentracion,$clase_id,$subclase_id,$lote,$offset);
   
  $html .= "<form name=\"formBloquearProductosLote\" id=\"formBloquearProductosLote\" method=\"post\" action=\"\">\n";
    $html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\">BLOQUEAR PRODUCTOS POR LOTE\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= " </table><br>\n";
    $action['paginador'] = "paginador('".$codigo_producto."','".$nombre_producto."','".$concentracion."','".$clase_id."','".$subclase_id."','".$lote."'";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action['paginador']);  
	$html .= " <table width=\"70%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td  align=\"center\">CODIGO\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">NOMBRE DEL PRODUCTO\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">LOTE\n";
    $html .= "    </td>\n";
    $html .= "    <td  align=\"center\">BLOQUEADO\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    
    
    
    foreach($datos as $key => $valor)
		{ 
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['codigo_producto']."\n";
      $html .= "    </td>\n";
      
      $html .= "    <td align=\"center\">".$valor['descripcion']."-".$valor['contenido_unidad_venta']."-".$valor['unidad_id']."\n";
      $html .= "    </td>\n";
      $html .= "    <td align=\"center\">".$valor['lote']."\n";
      $html .= "    </td>\n";
            
      if($valor['estado']=="1")
      {
        $html .= "<td align=\"center\">
                   <a onclick=\"xajax_ListarProductosABloquear('".$valor['codigo_producto']."','".$valor['lote']."','0','".$offset."')\">\n";
        $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
      }
      else
      {
        $html .= "<td align=\"center\">
                  <a onclick=\"xajax_ListarProductosABloquear('".$valor['codigo_producto']."','".$valor['lote']."','1','".$offset."')\">\n";
        $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
      }
      $html .= "  </tr>\n";
      $i++;
    }
    $action['volver'] = ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "Farmacovigilancia");       
    $html .= "<table align=\"center\">\n";
    $html .= "  <tr>\n";
    $html .= "    <td align=\"center\">\n";
    $html .= "      <a href=\"".$action['volver']."\" class=\"label_error\">VOLVER</a>\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "</table>\n";
    
    $html .= "</form>\n";
  
   
	$objResponse->assign("ListadoProd","innerHTML",$objResponse->setTildes($html));
    return $objResponse;
  }
 
 
 
 
 
 function ListarProductosABloquear($codigo_producto,$lote,$estado,$offset)
 {
   $objResponse = new xajaxResponse();
   $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
  
  $ProductosLoteEmpresas=$mdl->BuscarProductoLoteEmpresas($codigo_producto,$lote);
  $m=count($ProductosLoteEmpresas);
  
  if($m<=0)
	$disabled="disabled";
	else
	$disabled="";
  
  if($estado =="1")
    $mensaje = "  DESBLOQUEARAN   ";
    else
          $mensaje = "  BLOQUEARAN   ";
  
	$html .= "<table width=\"100%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\" colspan=\"7\">SE ".$mensaje." LOS SIGUIENTES PRODUCTOS :\n".$m;
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
	
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\">";
    $html .= "    CODIGO PRODUCTO\n";
    $html .= "    </td>\n";
	$html .= "    <td align=\"center\">";
    $html .= "    CLASE\n";
    $html .= "    </td>\n";
	$html .= "    <td align=\"center\">";
    $html .= "    NOMBRE\n";
    $html .= "    </td>\n";
    $html .= "    <td align=\"center\">";
    $html .= "    UBICACION\n";
    $html .= "    </td>\n";
	$html .= "    <td align=\"center\">";
    $html .= "    LOTE\n";
    $html .= "    </td>\n";
	
	$html .= "    <td align=\"center\">";
    $html .= "    FECHA/VENCIMIENTO\n";
    $html .= "    </td>\n";
	
	$html .= "    <td align=\"center\">";
    $html .= "    EXISTENCIA ACTUAL\n";
    $html .= "    </td>\n";
	$html .= "  </tr>\n";
    
    
    foreach($ProductosLoteEmpresas as $key => $valor)
		{
      
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      
	  $html .= "    <td align=\"center\">".$valor['codigo_producto']."\n";
      $html .= "    </td>\n";
	  
	  $html .= "    <td align=\"center\">".$valor['clase']."\n";
      $html .= "    </td>\n";
      
	  $html .= "    <td align=\"center\">".$valor['descripcion']." ".$valor['contenido_unidad_venta']." ".$valor['unidad']."\n";
      $html .= "    </td>\n";
	  
	  $html .= "    <td align=\"center\">".$valor['empresa_descripcion']." ".$valor['centro_descripcion']." ".$valor['bodega_descripcion']."\n";
      $html .= "    </td>\n";
	  
	  $html .= "    <td align=\"center\">".$valor['lote']."\n";
      $html .= "    </td>\n";
	  
      $html .= "    <td align=\"center\">".$valor['fecha_vencimiento']."\n";
      $html .= "    </td>\n";
	  
	  $html .= "    <td align=\"center\">".$valor['existencia_actual']."\n";
      $html .= "    </td>\n";
      
      $html .= "  </tr>\n";
    }
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\" colspan=\"7\">";
    $html .= "	<input ".$disabled." type=\"button\" value=\"Bloquear\" class=\"modulo_table_list\" onclick=\"xajax_GuardarProductoBloq('".$codigo_producto."','".$lote."','".$estado."','".$offset."');\">";
    $html .= "    </td>\n";
	$html .= "  </tr>\n";
	
	
    $html .= "</table>\n";
	
   //$BuscarProductos=$mdl->AgregarProductoBloq($codigo_producto,$lote,$sw_bloqueado,$id_producto_bloqueadoxlote);
	$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
   return $objResponse;
  }
 
 
	/*
	* 1) Insertar en la tabla de bloqueos los productos que se van a Bloquear (existencias 0).
	* 2) En existencias bodegas fv... segun producto, lote y fecha de vencimiento, la existencia actual debe establecerse en 0.
	*/
 function GuardarProductoBloq($codigo_producto,$lote,$estado,$offset)
 {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
    $ProductosLoteEmpresas=$mdl->BuscarProductoLoteEmpresas($codigo_producto,$lote);
    

	 foreach($ProductosLoteEmpresas as $key => $valor)
		{
		$mdl->ModificarExistenciaBloquear($valor['bodega'],$valor['centro_utilidad'],$valor['codigo_producto'],$valor['empresa_id'],$valor['fecha_vencimiento'],$valor['lote'],UserGetUID(),$estado);
    }
    
		$objResponse->script("xajax_ListarProductosLote(document.getElementById('codigo_producto_b').value,document.getElementById('descripcion_b').value,document.getElementById('contenido_unidad_venta_b').value,document.getElementById('clase_id').value,document.getElementById('subclase_id').value,document.getElementById('lote_b').value,'".$offset."');");
		$objResponse->call("OcultarSpan");
  
 	  return $objResponse;
 }
 
 function BuscarProductoLoteEmpresas($CodigoProducto,$Lote)
 {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
    $BuscarProductos=$mdl->ListarProductos($offset);
    $action['paginador'] = "Paginador('".$tipo_id_paciente."','".$paciente_id."','".$primer_nombre."','".$segundo_nombre."','".$primer_apellido."','".$segundo_apellido."'";
    $pghtml = AutoCarga::factory("ClaseHTML");
     
    $html .= "<form name=\"formBuscarProducto\" id=\"formBuscarProducto\" method=\"post\">\n";
    $html .= "<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\" colspan=\"4\">BUSCAR PRODUCTOS\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "<br>\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td width=\"30%\" align=\"center\">CODIGO\n";
    $html .= "    </td>\n";
    $html .= "    <td width=\"30%\" align=\"center\">NOMBRE\n";
    $html .= "    </td>\n";
    $html .= "    <td width=\"10%\" align=\"center\">SEL\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $i=0;
    $m=count($BuscarProductos);
    
    foreach($BuscarProductos as $key => $valor)
		{
      
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['codigo_producto']."\n";
      $html .= "    </td>\n";
      
      $html .= "    <td align=\"center\">".$valor['descripcion']."\n";
      $html .= "    </td>\n";
      //
      
      $html .= "    <td align=\"center\">\n";
      $html .= "       <input type=\"checkbox\" id=\"seleccionado".$i."\" value=\"\" onclick=\"xajax_SeleccionarProducto('".$BuscarProductos."','".$tipo_id_paciente."','".$paciente_id."','".$primer_nombre."','".$segundo_nombre."','".$primer_apellido."','".$segundo_apellido."','".$i."')\"> \n";
      $html .= "    </td>\n";
      $html .= "      <input type=\"hidden\" class=\"input-text\" id=\"codigo_producto".$i."\" value=\"".$valor['codigo_producto']."\" >\n";
      //$html .= "      <input type=\"hidden\" class=\"input-text\" name=\"codigo_producto.".$i."\" value=\"".$valor['codigo_producto']."\" >\n";
      $html .= "  </tr>\n";
      $i++;
    }
    
    $html .= "</table>\n";
    $html .= "</form>";
    $html .= $pghtml->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action['paginador']);
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
 
 /**
    * Funcion registra los pacientes
    *
    * @param  var $tipo_id_paciente contiene el tipo de documento paciente
    * @param  var $paciente_id contiene el numero del documento del paciente
    * @param  var $primer_nombre contiene el primer nombre del paciente 
    * @param  var $segundo_nombre contiene el segundo nombre del paciente 
    * @param  var $primer_apellido contiene el primer apellido del paciente 
    * @param  var $segundo_apellido contiene el segundo apellido del paciente 
    * @return Object $objResponse objeto de respuesta al formulario  
    */ 
 function BuscarProducto($tipo_id_paciente,$paciente_id,$primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$offset)
 {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("ConsultasParamFarmacovigilancia","","app","Inv_ParametrosIniciales");
    $BuscarProductos=$mdl->ListarProductos($offset);
    $action['paginador'] = "Paginador('".$tipo_id_paciente."','".$paciente_id."','".$primer_nombre."','".$segundo_nombre."','".$primer_apellido."','".$segundo_apellido."'";
    $pghtml = AutoCarga::factory("ClaseHTML");
     
    $html .= "<form name=\"formBuscarProducto\" id=\"formBuscarProducto\" method=\"post\">\n";
    $html .= "<table width=\"60%\" class=\"modulo_table_list\" border=\"0\" align=\"center\">\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td align=\"center\" colspan=\"4\">BUCAR PRODUCTOS\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $html .= "<br>\n";
    $html .= "  <tr class=\"modulo_table_list_title\">\n";
    $html .= "    <td width=\"30%\" align=\"center\">CODIGO\n";
    $html .= "    </td>\n";
    $html .= "    <td width=\"30%\" align=\"center\">NOMBRE\n";
    $html .= "    </td>\n";
    $html .= "    <td width=\"10%\" align=\"center\">SEL\n";
    $html .= "    </td>\n";
    $html .= "  </tr>\n";
    $i=0;
    $m=count($BuscarProductos);
    
    foreach($BuscarProductos as $key => $valor)
		{
      
      $html .= "  <tr class=\"modulo_list_claro\">\n";
      $html .= "    <td align=\"center\">".$valor['codigo_producto']."\n";
      $html .= "    </td>\n";
      
      $html .= "    <td align=\"center\">".$valor['descripcion']."\n";
      $html .= "    </td>\n";
      //
      
      $html .= "    <td align=\"center\">\n";
      $html .= "       <input type=\"checkbox\" id=\"seleccionado".$i."\" value=\"\" onclick=\"xajax_SeleccionarProducto('".$BuscarProductos."','".$tipo_id_paciente."','".$paciente_id."','".$primer_nombre."','".$segundo_nombre."','".$primer_apellido."','".$segundo_apellido."','".$i."')\"> \n";
      $html .= "    </td>\n";
      $html .= "      <input type=\"hidden\" class=\"input-text\" id=\"codigo_producto".$i."\" value=\"".$valor['codigo_producto']."\" >\n";
      //$html .= "      <input type=\"hidden\" class=\"input-text\" name=\"codigo_producto.".$i."\" value=\"".$valor['codigo_producto']."\" >\n";
      $html .= "  </tr>\n";
      $i++;
    }
    
    $html .= "</table>\n";
    $html .= "</form>";
    $html .= $pghtml->ObtenerPaginadoXajax($mdl->conteo,$mdl->pagina,$action['paginador']);
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
   /**
    * Funcion registra los pacientes
    *
    * @param  array $datos arreglo que contiene los datos
    * @param  var $tipo_id_paciente contiene el tipo de documento paciente
    * @param  var $paciente_id contiene el numero del documento del paciente
    * @param  var $primer_nombre contiene el primer nombre del paciente 
    * @param  var $segundo_nombre contiene el segundo nombre del paciente 
    * @param  var $primer_apellido contiene el primer apellido del paciente 
    * @param  var $segundo_apellido contiene el segundo apellido del paciente 
    * @param  var $i contiene la posicion del vector
    * @return Object $objResponse objeto de respuesta al formulario  
    */ 
  function SeleccionarProducto($datos,$tipo_id_paciente,$paciente_id,$primer_nombre,$segundo_nombre,$primer_apellido,$segundo_apellido,$i)
  {
    $objResponse = new xajaxResponse();
    $num=count($datos);
     $objResponse->script('
                          codigo_produ=document.getElementById(\'codigo_producto'.$i.'\').value;
                          alert(codigo_produ);
                           if(document.getElementById(\'seleccionado'.$i.'\').checked==true)
                           {
                            alert("hola");
                        //   codigo_produ=document.getElementById(\'codigo_producto'.$k.'\').value;
                           xajax_RegistraPaciente(\''.$tipo_id_paciente.'\',\''.$paciente_id.'\',\''.$primer_nombre.'\',\''.$segundo_nombre.'\',\''.$primer_apellido.'\',\''.$segundo_apellido.'\',codigo_produ);
                           alert(codigo_produ);
                          }
             //alert(codigo_producto);
  			');
    
     return $objResponse;
   }
?>