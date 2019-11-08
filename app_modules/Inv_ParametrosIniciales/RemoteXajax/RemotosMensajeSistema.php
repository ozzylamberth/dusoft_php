<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosMensajeSistema.php,v 1.1 2009/10/23 19:20:17 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
 
    /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  */  
  function MensajesSistemaT($offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasMensajesSistema","classes","app","Inv_ParametrosIniciales");
  
  
	$MensajesSistema=$sql->ListadoMensajesSistema($offset);
  
     
    $action['paginador'] = "paginador(";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
  
  
  
    $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
   $html .= "  <legend class=\"normal_10AN\">MENSAJES DEL SISTEMA</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">MOD</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($MensajesSistema as $key => $ms)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$ms['codigo']."</td><td>".$ms['descripcion']." </td>\n";
                          
					$html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarMensajeSistema('".$ms['codigo']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
          
          
          
          if($ms['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoMensajeSistema('inv_mensajes_producto','estado','0','".$ms['codigo']."','mensaje_id')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoMensajeSistema('inv_mensajes_producto','estado','1','".$ms['codigo']."','mensaje_id')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
          $html .= "      </td>\n";
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
        //$objResponse->alert("SI?");
          $objResponse->assign("ListadoMensajes","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
  }
  
 
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstadoMensajeSistema($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->call("xajax_MensajesSistemaT");
    return $objResponse;	
	}
 
 
 
/*
  Funcion Xajax para Modificar Laboratorios
  */
  function GuardarModMensajeSistema($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasMensajesSistema","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarMensajeSistema($datos);
  if($token)
  {
  $objResponse->call("xajax_MensajesSistemaT");
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
        $objResponse->assign("Listado","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  return $objResponse;
  }





 
  /*
  Funcion Xajax para Modificar la informacion de una Molecula con un formulario
  cargado en un Xajax
  */
  function ModificarMensajeSistema($CodigoMensaje)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasMensajesSistema","classes","app","Inv_ParametrosIniciales");
  
  $MensajeSistema=$sql->BuscarMensajeSistema($CodigoMensaje);
  
  //action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioNovedadDevolucion\" id=\"FormularioNovedadDevolucion\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      Creacion De Novedades Por Devolucion";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO DEL MENSAJE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" value="'.$MensajeSistema[0]['codigo'].'" type="Text" name="mensaje_sistema_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    $html .= '      <input type="hidden" name="mensaje_sistema_id_old" value="'.$MensajeSistema[0]['codigo'].'">'; //esto es para definir si es Update o Insert

		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" value="'.$MensajeSistema[0]['descripcion'].'" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Validar(xajax.getFormValues('FormularioNovedadDevolucion'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  
 
 function InsertarMensajeSistema($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasMensajesSistema","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarMensajeSistema($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->call("xajax_MensajesSistemaT");
  $objResponse->alert("Ingreso Exitoso!!");
  $objResponse->assign("Listado","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  }
  else
  $objResponse->alert("Error en el Ingreso... !!");
  
  
  
  return $objResponse;
  }


  function IngresoMensajeSistema()
  {
  $objResponse = new xajaxResponse();
  
		
		
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioMensajeSistema\" id=\"FormularioMensajeSistema\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE MENSAJES DEL SISTEMA";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO DEL MENSAJE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="mensaje_sistema_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="70" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Validar(xajax.getFormValues('FormularioMensajeSistema'))\">";
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
