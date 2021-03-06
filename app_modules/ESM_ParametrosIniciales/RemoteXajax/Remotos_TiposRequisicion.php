<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos_TiposRequisicion.php
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
    
  function Listado_TiposRequisicion($cod_anatomofarmacologico,$descripcion,$offset)
  {
	$objResponse = new xajaxResponse();
	$sql = AutoCarga::factory("Consultas_TiposRequisicion","classes","app","ESM_ParametrosIniciales");

	$PerfilesTerapeuticos=$sql->Listado_TiposRequisicion($cod_anatomofarmacologico,$descripcion,$offset);

	$action['paginador'] = "Paginador('".$cod_anatomofarmacologico."','".$descripcion."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:65%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">TIPOS DE REQUISICIONES</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	//$html .= "      <td width=\"7%\">CODIGO</td>\n";
	$html .= "      <td width=\"50%\">DESCRIPCION</td>\n";
	$html .= "      <td width=\"20%\">MOVIMIENTO</td>\n";
	$html .= "      <td width=\"7%\">MODIFICAR</td>\n";
	$html .= "      <td width=\"7%\">ESTADO</td>\n";
/*
Listado_TiposRequisicion","Ingreso_TipoRequisicion","Insertar_TipoRequision",
                          "Modificacion_TipoRequision","Modificar_TipoRequision","CambioEstado"
*/

	$html .= "    </tr>\n";

	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
	foreach($PerfilesTerapeuticos as $key => $ED)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

	$html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
	$html .= "      <td>".$ED['descripcion_orden_requisicion']." </td>\n";
	$html .= "      <td>".$ED['movimiento']." </td>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <a href=\"#\" onclick=\"xajax_Modificacion_TipoRequision('".$ED['tipo_orden_requisicion']."')\">\n";
	$html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
	// Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
	$html .= "        </a>\n";
	$html .= "      </td>\n";
							  //$tabla,$id,$campo_id,$CodigoUsuario,$CodigoEmpresa,$offset
	if($ED['sw_estado']==1)
	{
	$html .= "<td align=\"center\">
	<a href=\"#\" onclick=\"xajax_CambioEstado('esm_tipos_ordenes_requisicion','sw_estado','0','".$ED['tipo_orden_requisicion']."','tipo_orden_requisicion')\">\n";
	$html .="<img title=\"ACTIVO\" src=\"".GetThemePath()."/images/checksi.png \" border=\"0\"></a></td>\n";
	}
	else
	{
	$html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('esm_tipos_ordenes_requisicion','sw_estado','1','".$ED['tipo_orden_requisicion']."','tipo_orden_requisicion')\">\n";
	$html .="<img title=\"INACTIVO\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
	}

	$html .= "    </tr>\n";
	}
	$html .= "<center>";



	$html .= "    </table>\n";
	$html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado_TiposRequisicion","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  function Insertar_TipoRequision($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_TiposRequisicion","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Insertar_TipoRequision($datos);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_TiposRequisicion('','','1');");
  $objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  function Ingreso_TipoRequisicion()
  {
  $objResponse = new xajaxResponse();
  		
		$select .="<select name=\"movimiento\" id=\"movimiento\" class=\"select\" style=\"width:100%\">";
		$select .="<option value=\"\">SELECCIONAR</option>";
		$select .="<option value=\"T\">TRASLADO A BODEGAS SATELITES</option>";
		$select .="<option value=\"D\">DESPACHOS POR DISTRIBUCION</option>";
		$select .="</select>";
		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionTiposEventos\" id=\"FormularioCreacionTiposEventos\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE TIPOS DE REQUISICION";
		$html .= "      </td>";
		$html .= "      </tr>";
				
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" width=\"40%\">";
		$html .= "      DESCRIPCION:";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"60%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      TIPO DE MOVIMIENTO:";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" >";
		$html .= "		".$select;
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionTiposEventos'))\">";
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
  function Modificacion_TipoRequision($tipo_orden_requisicion)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_TiposRequisicion","classes","app","ESM_ParametrosIniciales");
  
  $tipo_fuerza_id=$sql->Buscar_TipoRequisicion($tipo_orden_requisicion);
  
  //Scripts Javascripts
    $select .="<select name=\"movimiento\" id=\"movimiento\" class=\"select\" style=\"width:100%\">";
		$select .="<option value=\"\">SELECCIONAR</option>";
		$select .="<option value=\"T\">TRASLADO A BODEGAS SATELITES</option>";
		$select .="<option value=\"D\">DESPACHOS POR DISTRIBUCION</option>";
		$select .="</select>";
  
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioCreacionTiposFuerzas\" id=\"FormularioCreacionTiposFuerzas\" method=\"post\">";

		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      MODIFICAR TIPO REQUISICION";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" width=\"40%\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"60%\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$tipo_fuerza_id[0]['descripcion_orden_requisicion'].'" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      TIPO DE MOVIMIENTO:";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" >";
		$html .= "		".$select;
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">';    //esto es para definir si es Update o Insert
    $html .= '      <input class="input-text" value="'.$tipo_fuerza_id[0]['tipo_orden_requisicion'].'" type="hidden" name="tipo_orden_requisicion">';
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionTiposFuerzas'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    $objResponse->script(" for(i=0;i<document.FormularioCreacionTiposFuerzas.movimiento.options.length;i++)
                                if(document.FormularioCreacionTiposFuerzas.movimiento.options[i].value == '".$tipo_fuerza_id[0]['movimiento']."')
                                  document.FormularioCreacionTiposFuerzas.movimiento.options[i].selected=true;
                                ");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  
  
 /*
  Funcion Xajax para Modificar un Estado Documento
  */
  function Modificar_TipoRequision($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_TiposRequisicion","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Modificar_TipoRequision($datos);
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_TiposRequisicion('','','1');");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
    
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
        
    $objResponse->script("xajax_Listado_TiposRequisicion('','','1');");
    return $objResponse;	
	}
  
  
?>
