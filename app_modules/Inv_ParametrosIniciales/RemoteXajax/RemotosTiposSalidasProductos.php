<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosTiposSalidasProductos.php,v 1.2 2009/11/20 13:05:51 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
 
    /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  */  
  function TiposSalidasProductosT($offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTiposSalidasProductos","classes","app","Inv_ParametrosIniciales");
  
  
	$TiposSalidasProductos=$sql->ListadoTiposSalidasProductos($offset);
  
     
    $action['paginador'] = "paginador(";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
  
  
  
    $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
   $html .= "  <legend class=\"normal_10AN\">TIPOS DE SALIDA DE PRODUCTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">MOD</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($TiposSalidasProductos as $key => $tsp)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$tsp['codigo']."</td><td>".$tsp['descripcion']." </td>\n";
                          
					$html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarTipoSalidaProducto('".$tsp['codigo']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
          
          
          
          if($tsp['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoTipoSalidaProducto('inv_tipos_salidas_productos','estado','0','".$tsp['codigo']."','tipo_salida_producto_id')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoTipoSalidaProducto('inv_tipos_salidas_productos','estado','1','".$tsp['codigo']."','tipo_salida_producto_id')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
          $html .= "      </td>\n";
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
          $objResponse->assign("ListadoTiposSalidas","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
  }
  
 
  
   /*
  * Realiza las busqueda de Molecula por Nombre... utilizado por el Buscador
  */
    function BusquedaNovedadesDevolucion($Nombre,$Codigo,$Sw_Medicamento,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
  
  $Moleculas=$sql->BuscarMoleculaNombre($Nombre,$Codigo,$Sw_Medicamento,$offset);
  
  
  $action['paginador'] = "PaginadorBusquedas('".$Nombre."','".$Codigo."','".$Sw_Medicamento."'";
  
  
  
  $pghtml = AutoCarga::factory("ClaseHTML");
  $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']); 
  
  $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">TIPO DE INSUMOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"7%\">CODIGO</td>\n";
        $html .= "      <td width=\"25%\">NOMBRE</td>\n";
        $html .= "      <td width=\"10%\">CONCENTRACION</td>\n";
		$html .= "      <td width=\"10%\">U. MEDIDA</td>\n";
		$html .= "      <td width=\"3%\">MOD</td>\n";
		$html .= "      <td width=\"3%\">OP</td>\n";
		
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Moleculas as $key => $mol)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$mol['molecula_id']."</td><td>".$mol['molecula']." </td>\n";
          $html .= "      <td >".$mol['concentracion']."</td><td> ".$mol['unidad']."</td>";
			
			$html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarMolecula('".$mol['molecula_id']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
      
           
      
      if($mol['estado']==1)
				{
				$html .= "<td align=\"center\">
						  <a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','0','".$mol['molecula_id']."','molecula_id','".$Sw_Medicamento."')\">\n";
				$html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
				}
				else
					{
					$html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','1','".$mol['molecula_id']."','molecula_id','".$Sw_Medicamento."')\">\n";
					$html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
					}
										
		  
					
        }
        
        
        
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $objResponse->assign("Listado","innerHTML",$objResponse->setTildes($html));
        return $objResponse;
  }
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstadoTipoSalidaProducto($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->call("xajax_TiposSalidasProductosT");
    return $objResponse;	
	}
 
 
 
/*
  Funcion Xajax para Modificar Laboratorios
  */
  function GuardarModTipoSalidaProducto($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTiposSalidasProductos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarTipoSalidaProducto($datos);
  if($token)
  {
  $objResponse->call("xajax_TiposSalidasProductosT");
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
  return $objResponse;
  }





 
  /*
  Funcion Xajax para Modificar la informacion de una Molecula con un formulario
  cargado en un Xajax
  */
  function ModificarTipoSalidaProducto($CodigoTipoSalida)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTiposSalidasProductos","classes","app","Inv_ParametrosIniciales");
  
  $TipoSalidaProducto=$sql->BuscarTipoSalidaProducto($CodigoTipoSalida);
  
  //action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioTipoSalidaProducto\" id=\"FormularioTipoSalidaProducto\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      MODIFICAR DE TIPOS DE SALIDAS - PRODUCTOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$TipoSalidaProducto[0]['codigo'].'" class="input-text" maxlength="10" type="Text" name="tipo_salida_producto_id" onkeyup="this.value=this.value.toUpperCase()">';
    $html .= '      <input type="hidden" name="tipo_salida_producto_id_old" value="'.$TipoSalidaProducto[0]['codigo'].'">'; //esto es para definir si es Update o Insert
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$TipoSalidaProducto[0]['descripcion'].'" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Validar(xajax.getFormValues('FormularioTipoSalidaProducto'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  
 
 function InsertarTipoSalidaProducto($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTiposSalidasProductos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarTipoSalidaProducto($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->call("xajax_TiposSalidasProductosT");
  $objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  
  
  return $objResponse;
  }


  function IngresoTipoSalidaProducto()
  {
  $objResponse = new xajaxResponse();
  
		
		
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioTipoSalidaProducto\" id=\"FormularioTipoSalidaProducto\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE TIPOS DE SALIDAS - PRODUCTOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" maxlength="10" type="Text" name="tipo_salida_producto_id" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Validar(xajax.getFormValues('FormularioTipoSalidaProducto'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
?>
