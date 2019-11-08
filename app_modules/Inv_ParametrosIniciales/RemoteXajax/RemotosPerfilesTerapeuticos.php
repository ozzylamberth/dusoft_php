<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosPerfilesTerapeuticos.php
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
    
  function Listado_PerfilesTerapeuticos($cod_anatomofarmacologico,$descripcion,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasPerfilesTerapeuticos","classes","app","Inv_ParametrosIniciales");
  
  $PerfilesTerapeuticos=$sql->Listar_PerfilesTerapeuticos($cod_anatomofarmacologico,$descripcion,$offset);
  
  $action['paginador'] = "Paginador('".$cod_anatomofarmacologico."','".$descripcion."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    $html .= "<center>";
    $html .= "<fieldset style=\"width:65%\" class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">PERFILES TERAPEUTICOS</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"7%\">CODIGO</td>\n";
    $html .= "      <td width=\"50%\">DESCRIPCION</td>\n";
    $html .= "      <td width=\"7%\">MODIFICAR</td>\n";
    $html .= "      <td width=\"7%\">ELIMINAR</td>\n";


    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach($PerfilesTerapeuticos as $key => $ED)
    {
    ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
    ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

    $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
    $html .= "      <td >".$ED['cod_anatomofarmacologico']."</td><td>".$ED['descripcion']." </td>\n";


    $html .= "      <td align=\"center\">\n";
    $html .= "        <a href=\"#\" onclick=\"xajax_Modificacion_PerfilTerapeutico('".$ED['cod_anatomofarmacologico']."')\">\n";
    $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
    // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
    $html .= "        </a>\n";
    $html .= "      </td>\n";
                                                                      //$tabla,$id,$campo_id,$CodigoUsuario,$CodigoEmpresa,$offset
    $html .= "      <td align=\"center\">\n";
    $html .= "        <a href=\"#\" onclick=\"xajax_Borrar_PerfilTerapeutico('inv_med_cod_anatofarmacologico','".$ED['cod_anatomofarmacologico']."','cod_anatomofarmacologico','".$offset."');\">\n";
    $html .= "          <img title=\"ELIMINAR\" src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
    // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
    $html .= "        </a>\n";
    $html .= "      </td>\n";
    
    $html .= "    </tr>\n";
    }
    $html .= "<center>";
          
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado_PerfilesTerapeuticos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  function Insertar_PerfilTerapeutico($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasPerfilesTerapeuticos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->Insertar_PerfilTerapeutico($datos);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_PerfilesTerapeuticos('','','1');");
  $objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  function Ingreso_PerfilTerapeutico()
  {
  $objResponse = new xajaxResponse();
  		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionPerfilesTerapeuticos\" id=\"FormularioCreacionPerfilesTerapeuticos\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE PERFILES TERAPEUTICOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" width=\"40%\">";
		$html .= "      CODIGO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"60%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="cod_anatomofarmacologico" maxlength="6" onkeyup="this.value=this.value.toUpperCase()" >';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" width=\"40%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"60%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionPerfilesTerapeuticos'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
   /*
  Funcion Xajax para Modificar la informacion de una Molecula con un formulario
  cargado en un Xajax
  */
  function Modificacion_PerfilTerapeutico($cod_anatomofarmacologico)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasPerfilesTerapeuticos","classes","app","Inv_ParametrosIniciales");
  
  $PerfilTerapeutico=$sql->Buscar_PerfilTerapeutico($cod_anatomofarmacologico);
  
  //Scripts Javascripts
  
  $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionEstadosDocumentos\" id=\"FormularioCreacionEstadosDocumentos\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE PERFILES TERAPEUTICOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" width=\"40%\">";
		$html .= "      CODIGO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"60%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" value="'.$PerfilTerapeutico[0]['cod_anatomofarmacologico'].'" type="Text" name="cod_anatomofarmacologico" maxlength="6" onkeyup="this.value=this.value.toUpperCase()" >';
    $html .= '      <input class="input-text" value="'.$PerfilTerapeutico[0]['cod_anatomofarmacologico'].'" type="hidden" name="cod_anatomofarmacologico_old">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" width=\"40%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"60%\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$PerfilTerapeutico[0]['descripcion'].'" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
    $html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">';    //esto es para definir si es Update o Insert
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
  
  
 /*
  Funcion Xajax para Modificar un Estado Documento
  */
  function Modificar_PerfilTerapeutico($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasPerfilesTerapeuticos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->Modificar_PerfilTerapeutico($datos);
  if($token)
  {
  $objResponse->script("xajax_Listado_PerfilesTerapeuticos('','','1');");
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
    
  return $objResponse;
  }

  
     /*
  Funcion Xajax para borrar Grupos
  */
  function Borrar_PerfilTerapeutico($tabla,$id,$campo_id,$offset)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token=$sql->Borrar_Registro($tabla,$id,$campo_id);
    
    if($token)
    {
    $objResponse->script("xajax_Listado_PerfilesTerapeuticos('','','".$offset."');"); 
    }
 else
    $objResponse->alert("Error al Borrar!!"); 
    
    
    return $objResponse;	
	}
  
  
?>
