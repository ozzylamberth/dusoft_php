<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosTopesDispensacionFarmacias.php
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
    
  function TiposDispensacionT($CodigoEmpresa,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTopesDispensacion","classes","app","Inv_ParametrosIniciales");
  
  $TiposDispensacion=$sql->Listar_TiposDispensacionSinAsignar($CodigoEmpresa,$offset);
  
  $action['paginador'] = "Paginador(";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    
    $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">TIPOS DE DISPENSACION</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">ID</td>\n";
      $html .= "      <td width=\"25%\">DISPENSACION</td>\n";
      $html .= "      <td width=\"20%\">ASIGNAR</td>\n";
            
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($TiposDispensacion as $key => $td)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$td['tipo_dispensacion_id']."</td><td>".$td['descripcion']." </td>\n";
                    
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_IngresoTopeDispensacion('".$CodigoEmpresa."','".$td['tipo_dispensacion_id']."','".$td['descripcion']."')\">\n";
            $html .="<img title=\"ASIGNAR\" src=\"".GetThemePath()."/images/ok.png\" border=\"0\"></a></td>\n";
                          
            
          }
          
          
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("ListadoTiposDispensacion","innerHTML",$objResponse->setTildes($html));
          $objResponse->script("xajax_TiposDispensacionAsignadas('".$CodigoEmpresa."');");
          return $objResponse;
          
  }
  
  
  
  
  
  
    /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function TiposDispensacionAsignadas($CodigoEmpresa,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTopesDispensacion","classes","app","Inv_ParametrosIniciales");
  
  $TiposDispensacionFarmacia=$sql->Listar_TiposDispensacionAsignados($CodigoEmpresa,$offset);
  
  $action['paginador'] = "paginador(";
  //$objResponse->alert("'".$TiposDispensacionFarmacia[0]['tope']."'");      
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    
    $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">TIPOS DE DISPENSACION</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">ID</td>\n";
      $html .= "      <td width=\"25%\">DISPENSACION</td>\n";
      $html .= "      <td width=\"25%\">TOPE</td>\n";
      $html .= "      <td width=\"20%\">MODIFICAR</td>\n";
      $html .= "      <td width=\"20%\">ESTADO</td>\n";
            
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($TiposDispensacionFarmacia as $key => $tdf)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$tdf['tipo_dispensacion_id']."</td><td>".$tdf['descripcion']." </td><td>".$tdf['tope']." </td>\n";
                    
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_ModificarTopeDispensacion('".$CodigoEmpresa."','".$tdf['farmacia_dispensacion_id']."','".$tdf['descripcion']."')\">\n";
            $html .="<img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\"></a></td>\n";
            
            if($tdf['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('inv_farmacias_x_tipodispensacion','estado','0','".$tdf['farmacia_dispensacion_id']."','farmacia_dispensacion_id','".$CodigoEmpresa."')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\">
            <a href=\"#\" onclick=\"xajax_CambioEstado('inv_farmacias_x_tipodispensacion','estado','1','".$tdf['farmacia_dispensacion_id']."','farmacia_dispensacion_id','".$CodigoEmpresa."')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
                          
            
          }
          
          
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("tipos_dispensacion_asignados","innerHTML",$objResponse->setTildes($html));
          //$objResponse->script("xajax_TiposDispensacionT('".$CodigoEmpresa."');");
          return $objResponse;
          
  }
 
    

  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstado($tabla,$campo,$valor,$id,$campo_id,$CodigoEmpresa)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_TiposDispensacionT('".$CodigoEmpresa."');");
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
        
    $objResponse->script("xajax_TiposDispensacionAsignadas('".$CodigoEmpresa."');");
    return $objResponse;	
	}
  
  
 
 /*
  Funcion Xajax para Modificar un Estado Documento
  */
  function ModTopeDispensacion($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTopesDispensacion","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarTopeDispensacion($datos);
  if($token)
  {
  $objResponse->script("xajax_TiposDispensacionAsignadas('".$datos['empresa_id']."');");
  $objResponse->script("Cerrar('Contenedor');");
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
  function ModificarTopeDispensacion($CodigoEmpresa,$Identificador,$Dispensacion)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTopesDispensacion","classes","app","Inv_ParametrosIniciales");
  
  $TiposDispensacionxFarmacia=$sql->Buscar_TiposDispensacionxFarmacia($Identificador);
  
  //Scripts Javascripts
  
  	
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioTopes\" id=\"FormularioTopes\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      MODIFICAR TOPE DE DISPENSACION PARA: '".$Dispensacion."'";
		$html .= "      </td>";
		$html .= "      </tr>";
		
				
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"10%\">";
		$html .= "      TOPE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$TiposDispensacionxFarmacia[0]['tope'].'" class="input-text" type="Text" name="tope" maxlength="10" onkeypress="return acceptNum(event)">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="empresa_dispensacion_id" value="'.$TiposDispensacionxFarmacia[0]['farmacia_dispensacion_id'].'">';
    $html .= '      <input type="hidden" name="empresa_id" value="'.$CodigoEmpresa.'">';
		$html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioTopes'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  

function InsertarAsignarDispensacionTope($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasTopesDispensacion","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarAsignarDispensacionTope($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->script("xajax_TiposDispensacionT('".$datos['empresa_id']."');");
  $objResponse->assign("ListadoEstadosCreados","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  $objResponse->alert("Ingreso Exitoso!!");
  $objResponse->script("tabPane.setSelectedIndex('0');");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa que no exista El Estado!!");
  
  
  
  return $objResponse;
  }


  function IngresoTopeDispensacion($CodigoEmpresa,$TipoDispensacion_Id,$Dispensacion)
  {
  $objResponse = new xajaxResponse();
  		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioTopes\" id=\"FormularioTopes\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      ASIGNAR TOPES DE DISPENSACION PARA: '".$Dispensacion."'";
		$html .= "      </td>";
		$html .= "      </tr>";
		
				
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"10%\">";
		$html .= "      TOPE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="tope" maxlength="10" onkeypress="return acceptNum(event)">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $EmpresaTipoDispensacion = $CodigoEmpresa."".$TipoDispensacion_Id;
    $html .= '      <input type="hidden" name="empresa_dispensacion_id" value="'.$EmpresaTipoDispensacion.'">';
		$html .= '      <input type="hidden" name="empresa_id" value="'.$CodigoEmpresa.'">'; //esto es para definir si es Update o Insert
    $html .= '      <input type="hidden" name="tipo_dispensacion_id" value="'.$TipoDispensacion_Id.'">'; //esto es para definir si es Update o Insert
    $html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioTopes'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    $objResponse->script("TiposDispensacionT('".$CodigoEmpresa."');");
    return $objResponse;
  }
  
  
  
?>
