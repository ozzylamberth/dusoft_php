<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosEstadosDocumentos.php
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
    
  function EstadosDocumentosT($offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
  
  $EstadosDocumentos=$sql->Listar_EstadosDocumentos($offset);
  
  $action['paginador'] = "Paginador(";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    
    $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">ESTADOS DE DOCUMENTOS DE BODEGA</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">ABREVIATURA</td>\n";
      $html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
      $html .= "      <td width=\"20%\">ESTADO</td>\n";
      $html .= "      <td width=\"20%\">MODIFICAR</td>\n";
      
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($EstadosDocumentos as $key => $ED)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$ED['abreviatura']."</td><td>".$ED['descripcion']." </td>\n";
                    
        if($ED['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoEstadoDocumento('inv_estados_documentos','estado','0','".$ED['abreviatura']."','abreviatura')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoEstadoDocumento('inv_estados_documentos','estado','1','".$ED['abreviatura']."','abreviatura')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
                      
        $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_ModificarEstadoDocumento('".$ED['abreviatura']."')\">\n";
            $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
            
            
          }
          
          
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("ListadoEstadosCreados","innerHTML",$objResponse->setTildes($html));
          $objResponse->call("xajax_EstadosDocumentosCambiosT");
          return $objResponse;
          
  }
  
  
  
  
  /*
  * Funcion Que Refrescará Los lisyado de estados que pueden cambiar de uno a otro.
  */  
    
  function EstadosDocumentosCambiosT()
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
  
  $EDocumentos=$sql->Listar_EstadosDocumentos_();
  
  $Select .= "<Select name=\"EstadosDocumentos\" class=\"select\" size=\"5\" style=\"width:100%;height:100%\" >";
        foreach($EDocumentos as $key => $ED_)
        {
        $Select .="<option value='".$ED_['abreviatura']."' onclick=\"xajax_CambiosNoAsignadosXEstado(this.value,'".$ED_['descripcion']."')\">";
        $Select .=$ED_['abreviatura']." - ".$ED_['descripcion'];
        }
  
  $Select .= "</Select>";
  
  $Select1 .= "<Select name=\"cambio_a\" class=\"select\" size=\"5\" style=\"width:100%;height:100%\" >";
        $Select1 .="<option value=''>";
        $Select1 .="</option>";
  
  $Select1 .= "</Select>";
    
      $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
      $html .= "  <legend class=\"normal_10AN\">CAMBIO DE ESTADO A OTRO ESTADO</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"3\">SECUENCIA DE ESTADOS</td>\n";
      $html .= "    </tr>\n";
      
      
      $html .= "    <tr class=\"modulo_list_claro\" align=\"center\">\n";
      $html .= "      <td width=\"45%\">ESTADO</td>\n";
      $html .= "      <td width=\"1%\">PUEDE CAMBIAR A</td>\n";
      $html .= "      <td width=\"45%\">ESTADOS DISPONIBLES<br>(Doble Click para Asignar...)</td>\n";
      
      
      $html .= "    <tr class=\"modulo_list_claro\" align=\"center\" >\n";
      $html .= "      <td >".$Select."</td>\n";
      $html .= "      <td >==></td>\n";
      $html .= "      <td ><div id=\"segundo_select\">".$Select1."</div></td>\n";
         
      
      $html .= "    </tr>\n";
      $html .= "    </table>\n";
      $html .= "</fieldset><br>\n";
      $html .= "</center>";
      
      $html .="<div id=\"resultadoxestado\">";
      $html .="</div>";
      
          $objResponse->assign("cambio_estadosdocumentos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  

  
  
    
    
    /*
  Funcion Xajax para borrar Grupos
  */
  function BorrarEstadosAsignados($tabla,$id,$campo_id,$Abreviatura,$NombreEstadoSeleccionado)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token=$sql->Borrar_Registro($tabla,$id,$campo_id);
    
    
    
    
    
    if($token)
    {
    $objResponse->script("xajax_CambiosNoAsignadosXEstado('".$Abreviatura."','".$NombreEstadoSeleccionado."');"); 
    //$objResponse->call("xajax_EstadosDocumentosT");
    }
 else
    $objResponse->alert("Error al Borrar!!"); 
    
    
    return $objResponse;	
	}
    
  function AsignarCambiosXEstado($Abreviatura,$EstadoHijo,$NombreEstadoSeleccionado)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarCambioXEstadoDocumento($Abreviatura,$EstadoHijo);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  //$objResponse->call("xajax_EstadosDocumentosT");
  //$objResponse->assign("ListadoEstadosCreados","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  $objResponse->script("xajax_CambiosNoAsignadosXEstado('".$Abreviatura."','".$NombreEstadoSeleccionado."');");
  //$objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa que no exista El Estado!!");
  
  
  
  return $objResponse;
  }
  
  
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstado($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->call("xajax_LaboratoriosT");
    return $objResponse;	
	}
  
 
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambiosNoAsignadosXEstado($Abreviatura,$NombreEstadoSeleccionado)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
    
    $CNAXE=$sql->Listar_CambiosNoAsignadosXEstado($Abreviatura);
    
  
  $Select .= "<Select name=\"cambio_a\" class=\"select\" size=\"5\" style=\"width:100%;height:100%\" >";
        foreach($CNAXE as $key => $ED)
        {
        $Select .="<option value='".$ED['abreviatura']."' ondblclick=\"xajax_AsignarCambiosXEstado('".$Abreviatura."',this.value,'".$NombreEstadoSeleccionado."')\">";
        $Select .=$ED['abreviatura']." - ".$ED['descripcion'];
        }
  
  $Select .= "</Select>";
  
  $html = $Select;
    
    
  $CAXE=$sql->Listar_CambiosAsignadosXEstado($Abreviatura);
  
    $resultado = "<div id=\"resultado_porestado\">";
    $resultado .="El estado ".$NombreEstadoSeleccionado." Puede cambiar a: <br>";
    $resultado .="<ul>";
    
          foreach($CAXE as $key => $CED)
          {
          $resultado .="<li>".$CED['descripcion'];
          $resultado .= "<a href=\"#\" onclick=\"xajax_BorrarEstadosAsignados('inv_cambio_estados_documentos','".$CED['codigo']."','cambio_estado_documento_id','".$Abreviatura."','".$NombreEstadoSeleccionado."')\">";
          $resultado .= "          <img title=\"Eliminar...\" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\">";
          $resultado .= "        </a>";
          $resultado .= "</li>";
          
          }
    $resultado .="</ul>";
    $resultado .="</div>";
    
    $objResponse->assign("segundo_select","innerHTML",$objResponse->setTildes($html));
    
    $objResponse->assign("resultadoxestado","innerHTML",$resultado);
    
    return $objResponse;	
	}
 
 
 
 
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstadoEstadoDocumento($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->call("xajax_EstadosDocumentosT");
    return $objResponse;	
	}
  
  
 
 /*
  Funcion Xajax para Modificar un Estado Documento
  */
  function ModEstadoDocumento($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarEstadoDocumento($datos);
  if($token)
  {
  $objResponse->call("xajax_EstadosDocumentosT");
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
  function ModificarEstadoDocumento($Abreviatura)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
  
  $EstadoDocumento=$sql->Buscar_EstadoDocumento($Abreviatura);
  
  //Scripts Javascripts
  
  $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionEstadosDocumentos\" id=\"FormularioCreacionEstadosDocumentos\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE ESTADOS DE DOCUMENTOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      ABREVIATURA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" value="'.$EstadoDocumento[0]['abreviatura'].'" type="Text" name="abreviatura" maxlength="5" onkeyup="this.value=this.value.toUpperCase()" >';
    $html .= '      <input class="input-text" value="'.$EstadoDocumento[0]['abreviatura'].'" type="hidden" name="abreviatura_old">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$EstadoDocumento[0]['descripcion'].'" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionEstadosDocumentos'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  

function InsertarEstadoDocumento($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasEstadosDocumentos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarEstadoDocumento($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->call("xajax_EstadosDocumentosT");
  $objResponse->assign("ListadoEstadosCreados","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  $objResponse->alert("Ingreso Exitoso!!");
  $objResponse->script("tabPane.setSelectedIndex('0');");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa que no exista El Estado!!");
  
  
  
  return $objResponse;
  }


  function IngresoEstadoDocumento()
  {
  $objResponse = new xajaxResponse();
  		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionEstadosDocumentos\" id=\"FormularioCreacionEstadosDocumentos\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE ESTADOS DE DOCUMENTOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      ABREVIATURA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="abreviatura" maxlength="5" onkeyup="this.value=this.value.toUpperCase()" >';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionEstadosDocumentos'))\">";
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
