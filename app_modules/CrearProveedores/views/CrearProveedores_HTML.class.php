<?php
  /**************************************************************************************
  * $Id: CrearProveedores_HTML.php,v 1.10 2009/10/20 19:36:40 mauricio Exp $
  *
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS-FI
  *
  * $Revision: 1.10 $
  *
  * @autor Jaime G�ez
  ***************************************************************************************/

  IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");
  class CrearProveedores_HTML
  {
     /**
    * Metodo constructor
    * acceso a SelectEmpresa
    * @access public
    */
    function CrearProveedores_HTML(){ }
  
/**
  * Metodo para establecer el menu de creacion de terceros opcion 1 GESTION tercero, opcion 2 GESTION proveedores.
  * @return string $html forma del menu
  * @access public
  */
  function Menu($action)
    {

      $html .= ThemeAbrirTabla("CREACION DE TERCEROS Y PROVEEDORES");
      $html .= " <form name=\"menu_docu\"action=\"".$Menu."\"method=\"post\">\n";
      $html .= "       <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "         <tr class=\"modulo_table_list_title\">\n";
      $html .= "           <td align=\"center\">\n";
      $html .= "             OPCIONES TERCEROS";
      $html .= "           </td>\n";
      $html .= "         </tr>\n";
      $html .= "         <tr class=\"modulo_list_claro\">\n";
      $html .= "           <td  align=\"center\" class=\"normal_10AN\">\n";
      $html .= "              <a title=\"GESTION DE TERCEROS\" class=\"label_error\" href=\"".$action['terceros']."\">GESTION DE TERCEROS</a>\n";
      $html .= "           </td>";
      $html .= "         </tr>";
      $html .= "         <tr class=\"modulo_list_claro\">\n";
      $html .= "           <td   align=\"center\" class=\"normal_10AN\">\n";
      $html .= "              <a title=\"GESTION DE PORVEEDORES\" class=\"label_error\" href=\"".$action['proveedores']."\">GESTION DE PROVEEDORES</a>\n";
      $html .= "           </td>";
      $html .= "         </tr>";
      $html .= "       </table>";
      $html .= " </form>";

      $html .= " <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
      $html .= "  <table align=\"center\" width=\"50%\">\n";
      $html .= "    <tr>\n";
      $html .= "       <td align=\"center\" colspan='7'>\n";
      $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $html .= "       </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= " </form>\n";
      $html .= ThemeCerrarTabla();
      return $html;
 }


/**
  * Metodo para establecer el menu de gestion de terceros.
  * @return string $html forma del menu
  * @access public
  */


  function Terceros($action,$tipos_id,$request,$datos,$conteo, $pagina)
  {
	$ctl = AutoCarga::factory("ClaseUtil");
	$html  = $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptDate('/');
	$html .= ThemeAbrirTabla("ADMINISTRADOR DE TERCEROS");
    $html .= "            <form name=\"Buscador\" id=\"Buscador\" action=\"".$action['buscar']."\" method=\"post\">\n";
    $html .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "                    <tr class=\"modulo_table_list_title\">\n";
    $html .= "                       <td colspan=\"7\" align=\"center\">\n";
    $html .= "                          BUSCADOR DE TERCEROS";
    $html .= "                       </td>\n";
    $html .= "                    </tr>\n";
    $html .= "                    <tr class=\"modulo_list_claro\">\n";
    $html .= "                       <td align=\"center\">\n";
    $html .= "                          TIPO IDENTIFICACION";
    $html .= "                       </td>\n";
    $html .= "                       <td align=\"center\">\n";
	if(!empty($tipos_id))
    {
      $html.= "                            <select id=\"tipo_id_tercero\" name=\"buscador[tipo_id_tercero]\" class=\"select\" >";
      $html.="                                 <option value=\"\">-- TODOS --</option> \n";
	  for($i=0;$i<count($tipos_id);$i++)
      {
        $html.="                                 <option value=\"".$tipos_id[$i]['tipo_id_tercero']."\">".$tipos_id[$i]['tipo_id_tercero']."</option> \n";
      }
      $html.= "                             </select>\n";
    }
    $html .= "                       </td>\n";
    $html .= "                       <td id=\"aux\" align=\"center\">\n";
    $html .= "                             IDENTIFICACION";
    $html .= "                       </td>\n";
	$html .= "                       <td id=\"aux\" align=\"center\">\n";
    $html .= "                             <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"buscador[tercero_id]\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "                       </td>\n";
    $html .= "                       <td id=\"aux\" align=\"center\">\n";
    $html .= "                             NOMBRE";
    $html .= "                       </td>\n";
	$html .= "                       <td id=\"aux\" align=\"center\">\n";
    $html .= "                             <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"buscador[nombre_tercero]\"value=\"\">";
    $html .= "                       </td>\n";
    $html .= "                       <td id=\"busqueda\" align=\"center\">\n";
    $html .= "                         <input type=\"hidden\" name=\"offset\" id=\"offset\" value=\"\">\n";
    $html .= "                         <input type=\"submit\" class=\"input-submit\" value=\"BUSCAR\">\n";
    $html .= "                       </td>\n";
    $html .= "                    </tr>\n";
    $html .= "                  </table>";
    $html .= "                  <br>\n";
	
    $html .= "                   <table align=\"center\" BORDER='0' width=\"60%\">\n";
    $html .= "                    <tr>\n";
    $html .= "                      <td align=\"center\">\n";
    $html .= "                          <a  title=\"CREAR NUEVO TERCERO\" class=\"label_error\" href=\"".$action['FormaTerceros']."&opc=1\">CREAR NUEVO TERCERO</a>\n";
    $html .= "                      </td>\n";
    $html .= "                    </tr>\n";
    $html .= "                   </table>\n";
    $html .= "           </form>";
    $pgn = AutoCarga::factory("ClaseHTML");
	$html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
	$html .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "                    <tr class=\"modulo_table_list_title\">\n";
	$html .= "                      <td align=\"center\" width=\"4%\">\n";
	$html .= "                        <a title='TIPO DE DOCUMENTO'>TIPO ID </a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"8%\">\n";
	$html .= "                        <a title='NUMERO DE IDENTIFICACION'>NUMERO</a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"23%\">\n";
	$html .= "                        <a title='NOMBRE TERCERO'>NOMBRE";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"20%\">\n";
	$html .= "                        <a title='LOCALIZACION GEOGRAFICA'>LOCALIZACION<a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"10%\">\n";
	$html .= "                        <a title='direccionCION'>DIR<a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"4%\">\n";
	$html .= "                        <a title='TELEFONO'>TEL<a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"3%\">\n";
	$html .= "                        <a title='MODIFICAR'>MOD<a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"3%\">\n";
	$html .= "                        <a title='ASIGNAR BANCO'>BANCO<a>";
	$html .= "                      </td>\n";
	$html .= "                      <td align=\"center\" width=\"3%\">\n";
	$html .= "                        <a title='ESTADO/BLOQUEO'>BL<a>";
	$html .= "                      </td>\n";
	$html .= "                    </tr>\n";
	foreach($datos as $key => $valor)
             {
	 $html .= "                  <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
	 $html .= "						<td>".$valor['tipo_id_tercero']."</td>";
	 $html .= "						<td>".$valor['tercero_id']."</td>";
	 $html .= "						<td>".$valor['nombre_tercero']."-<b class=\"label_error\">(".$valor['cliente'].")(".$valor['proveedor'].")</b></td>";
	 $html .= "						<td>".$valor['pais']."-".$valor['departamento']."-".$valor['municipio']."</td>";
	 $html .= "						<td>".$valor['direccion']."</td>";
	 $html .= "						<td>".$valor['telefono']."</td>";
	 $html .= "						<td>";
	 $html .= "					<center><a href=\"".$action['FormaTerceros']."&tipo_id_tercero=".$valor['tipo_id_tercero']."&tercero_id=".$valor['tercero_id']."\" class=\"label_error\"  title=\"MODIFICAR TERCERO\"><img src=\"".GetThemePath()."/images/modificar.png\" border='0' ></a></center>\n";
	 $html .= "						</td>";
	 $html .= "						<td>";
	 $html .= "					<center><a href=\"".$action['FormaTercerosBancos']."&tipo_id_tercero=".$valor['tipo_id_tercero']."&tercero_id=".$valor['tercero_id']."\" class=\"label_error\"  title=\"BANCOS TERCERO\"><img src=\"".GetThemePath()."/images/banco.png\" border='0' ></a></center>\n";
	 $html .= "						</td>";
	 $html .= "						<td>";
	 $estado=explode("@",$valor['bloqueo']);
	 $html .= "					<center><a class=\"label_error\"  title=\"".$estado[0]."\" onclick=\"xajax_EstadosTercero('".$valor['tipo_id_tercero']."','".$valor['tercero_id']."','".$valor['tipo_bloqueo_id']."');\"><img src=\"".GetThemePath()."/images/".$estado[1]."\" border='0'></a></center>\n";
	 $html .= "						</td>";
	 $html .= "                   </tr>\n";
               }
    //print_r($_REQUEST);
    $html .= "    <div id=\"volvercen_cos\">";
    $html .= "     <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "      <table align=\"center\" width=\"50%\">\n";
    $html .= "       <tr>\n";
    $html .= "        <td align=\"center\" colspan='7'>\n";
    $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $html .= "        </td>\n";
    $html .= "       </tr>\n";
    $html .= "      </table>\n";
    $html .= "     </form>";
    $html .= "    </div>";
	$html .= ThemeCerrarTabla();
	$html .= $this->CrearVentana(480,"ESTADOS - TERCERO");
    return $html;
    }
	
	/**
	* Metodo para establecer el menu de gestion de terceros.
	* @return string $html forma del menu
	* @access public
	*/


  function FormaTerceros($action,$tipos_id,$paises,$request,$datos_session,$tercero,$UnidadesNegocio,$GrupoActividades,$mensaje,$TiposClientes)
  {
	$ctl = AutoCarga::factory("ClaseUtil");
	$html  = $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptDate('/');
	$html .= $ctl->AcceptNum(true);
	
	/*Funciones Javascript*/
	$html .="	<script>";
	$html .="	function Validar_Campos(Formulario)";
	$html .="	{";
	$html .="	var mensaje=\"\";";
	$html .="	if(Formulario.tercero_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Identificador Del Tercero No Puede Estar Vacío <br> ';	";
	$html .="	if(Formulario.nombre_tercero.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Nombre Del Tercero No Puede Estar Vacío <br> ';	";
	$html .="	if(Formulario.tipo_pais_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Pais Del Tercero No Puede Estar Vacío <br>';	";
	$html .="	if(Formulario.tipo_dpto_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Departamento Del Tercero No Puede Estar Vacío <br>';	";
	$html .="	if(Formulario.tipo_mpio_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Municipio Del Tercero No Puede Estar Vacío <br>';	";
	$html .="	if(Formulario.direccion.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'La Direccion Del Tercero No Puede Estar Vacía <br>';	";
	$html .="	if(Formulario.tipo_id_tercero.value==\"NIT\")";
	$html .="	if(Formulario.dv.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Digito de Verificacion del Tercero No Puede Estar Vacío <br>';	";
	
	/*Validacion Si Se Escoge cliente*/
	
	$html .="   if(Formulario.tercero_cliente[0].checked==true)";
	$html .="	{";
	/*$html .="	alert(parseFloat(Formulario.porcentaje_cree.value));";*/
	
	$html .="	if(Formulario.sw_rtf[0].checked==true)";
	$html .="	if(Formulario.porcentaje_rtf.value==\"\" || parseInt(Formulario.porcentaje_rtf.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de Retencion en la Fuente Debe ser Mayor a Cero - CLIENTE <br>';	";
	
	$html .="	if(Formulario.sw_ica[0].checked==true)";
	$html .="	if(Formulario.porcentaje_ica.value==\"\" || parseInt(Formulario.porcentaje_ica.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de ICA Debe ser Mayor a Cero - CLIENTE <br>';	";
	
	$html .="	if(Formulario.sw_reteiva[0].checked==true)";
	$html .="	if(Formulario.porcentaje_reteiva.value==\"\" || parseInt(Formulario.porcentaje_reteiva.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de RETE-IVA Debe ser Mayor a Cero - CLIENTE <br>';	";

	$html .="	if(Formulario.sw_cree[0].checked==true)";
	$html .="		if(Formulario.porcentaje_cree.value==\"\" || parseFloat(Formulario.porcentaje_cree.value)<=0)";
	$html .="			mensaje += 'El Porcentaje de Impuesto CREE Debe ser Mayor a Cero - CLIENTE <br>';	";

	$html .="	}";	
	
	/*Validacion Si Se Escoge Proveedor*/
	
	$html .="   if(Formulario.tercero_proveedor[0].checked==true)";
	$html .="	{";
	/*$html .="	alert(parseInt(Formulario.porcentaje_rtf.value));";*/
	
	$html .="	if(Formulario.prov_sw_rtf[0].checked==true)";
	$html .="	if(Formulario.prov_porcentaje_rtf.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.prov_porcentaje_rtf.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de Retencion en la Fuente Debe ser Mayor a Cero - PROVEEDOR <br>';	";
	
	$html .="	if(Formulario.prov_sw_ica[0].checked==true)";
	$html .="	if(Formulario.prov_porcentaje_ica.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.prov_porcentaje_ica.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de ICA Debe ser Mayor a Cero - PROVEEDOR <br>';	";

	$html .="	if(Formulario.prov_sw_cree[0].checked==true)";
	$html .="		if(Formulario.prov_porcentaje_cree.value==\"\" || parseFloat(Formulario.prov_porcentaje_cree.value)<=0)";
	$html .="			mensaje += 'El Porcentaje de Impuesto CREE Debe ser Mayor a Cero - PROVEEDOR <br>';	";
	
	$html .="	if(Formulario.prov_sw_reteiva[0].checked==true)";
	$html .="	if(Formulario.prov_porcentaje_reteiva.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.prov_porcentaje_reteiva.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de RETE-IVA Debe ser Mayor a Cero - PROVEEDOR <br>';	";
	
	$html .="	if(Formulario.grupo_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'Debe Seleccionar El Grupo De Actividad del Proveedor <br>';	";
	
	$html .="	if(Formulario.actividad_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'Debe Seleccionar La Actividad del Proveedor <br>';	";
	
	$html .="	if(Formulario.dias_gracia.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.dias_gracia.value)<0)";
	$html .="	mensaje += 'Dias Gracia del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	$html .="	if(Formulario.dias_credito.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.dias_credito.value)<0)";
	$html .="	mensaje += 'Dias Credito del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	$html .="	if(Formulario.tiempo_entrega.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.tiempo_entrega.value)<0)";
	$html .="	mensaje += 'Tiempo Entrega del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	$html .="	if(Formulario.descuento_por_contado.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.descuento_por_contado.value)<0)";
	$html .="	mensaje += 'Descuento Por Contado del Proveedor debe ser mayor o igual a Cero <br>';	";
		
	$html .="	if(Formulario.descuento_por_contado.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.descuento_por_contado.value)<0)";
	$html .="	mensaje += 'Descuento Por Contado del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	
	
	$html .="	}";
	
	
	$html .="	document.getElementById('error').innerHTML = mensaje; ";
	$html .="	if(mensaje.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="	Formulario.submit();";
	$html .="	else";
	$html .="	return false;	";
	
	$html .="	}";
	$html .="	</script>";
	
	$html .="	<script>";
	$html .="	function Ocultar(tipo_id_tercero)";
	$html .="	{";
	$html .="	if(tipo_id_tercero!='NIT')";
	$html .="	{";
	$html .="	document.getElementById('dv').disabled=true;";
	$html .="	document.getElementById('dv').value='';";
	$html .="	}";
	$html .="	else";
	$html .="			{";
	$html .="			document.getElementById('dv').disabled=false;";
	/*$html .="			document.getElementById('dv').value='';";*/
	$html .="			}";
	$html .="	";
	$html .="	}";
	$html .="	</script>";
	
	$html .="	<script>";
	$html .="	function Ocultar_Condicionado(valor_radio,id_campo)";
	$html .="	{";
	$html .="	if(valor_radio == '1')";
	$html .="	{";
	$html .="	document.getElementById(id_campo).readOnly=false;";
	$html .="	}";
	$html .="	else";
	$html .="		{";
	$html .="		document.getElementById(id_campo).readOnly=true;";
	$html .="		document.getElementById(id_campo).value='0';";
	$html .="		}";
	$html .="	}";
	$html .="	</script>";

	/*FIN Funciones Javascript*/

	//print_r($paises);
	$selected = "";
	$select_pais = "<select class=\"select\" name=\"tipo_pais_id\" id=\"tipo_pais_id\" onchange=\"xajax_Buscar_Departamento(this.value);\">";
	foreach($paises as $key=>$v)
	{
	if(trim($v['tipo_pais_id'])==trim($tercero['tipo_pais_id']))
	$selected = " SELECTED ";
	else
		$selected = "";
	$select_pais .= "<option  value=\"".$v['tipo_pais_id']."\" ".$selected.">".$v['pais']."</option>";
	}
	$select_pais .= "</select>";
	
	$selected = "";
	
	$select_tipoid .= " <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"Ocultar(this.value);\">";
    foreach($tipos_id as $k=>$ti)
	{
	if(trim($ti['tipo_id_tercero'])==trim($tercero['tipo_id_tercero']))
	$selected = " SELECTED ";
	else
		$selected = "";
		
	$select_tipoid .=" <option ".$selected." value=\"".$ti['tipo_id_tercero']."\"  >".$ti['tipo_id_tercero']."</option> \n";
	}
    $select_tipoid .= "                       </select>\n";
	
	$selected = "";
	
	$select_uneg .= " <select id=\"codigo_unidad_negocio\" name=\"codigo_unidad_negocio\" class=\"select\">";
    $select_uneg .=" <option value=\"\"  >-- NINGUNO --</option> \n";
	foreach($UnidadesNegocio as $r=>$un)
	{
	if(trim($un['codigo_unidad_negocio'])==trim($tercero['codigo_unidad_negocio']))
	$selected = " SELECTED ";
	else
		$selected = "";
	$select_uneg .=" <option ".$selected." value=\"".$un['codigo_unidad_negocio']."\"  >".$un['descripcion']."</option> \n";
	}
    $select_uneg .= "                       </select>\n";
	
	$selected = "";
	
	$select_ga .= " <select id=\"grupo_id\" name=\"grupo_id\" class=\"select\" onchange=\"xajax_Buscar_Actividad(this.value);\">";
    $select_ga .=" <option value=\"\"  >-- NINGUNO --</option> \n";
	foreach($GrupoActividades as $kga=>$ga)
	{
	if(trim($ga['grupo_id'])==trim($tercero['grupo_id']))
	$selected = " SELECTED ";
	else
		$selected = "";
	$select_ga .=" <option ".$selected." value=\"".$ga['grupo_id']."\"  >".$ga['grupo_id']."-".$ga['descripcion']."</option> \n";
	}
    $select_ga .= "                       </select>\n";
    
	
	$select_tc .= " <select id=\"tipo_cliente\" name=\"tipo_cliente\" class=\"select\" >";
    $select_tc .=" <option value=\"\"  >-- NINGUNO --</option> \n";
	foreach($TiposClientes as $ka=>$tc)
	{
	if(trim($tc['tipo_cliente'])==trim($tercero['tipo_cliente']))
	$selected = " SELECTED ";
	else
		$selected = "";
	$select_tc .=" <option ".$selected." value=\"".$tc['tipo_cliente']."\"  >".$tc['tipo_cliente']."-".$tc['descripcion']."</option> \n";
	}
    $select_tc .= "                       </select>\n";
    
		
	//print_r($select_pais);
	
	$html .= ThemeAbrirTabla("FORMULARIO DE CREACION DE TERCEROS");
    $html .= "                  <div id='error' class='label_error' style=\"text-transform: uppercase; text-align:center;\">".$mensaje[0]."</div>\n";
	$html .= "            <form name=\"Formulario\" action=\"".$action['guardar']."\" method=\"post\" onSubmit=\"Validar_Campos(document.Formulario); return false;\">\n";
    $html .= "                   <table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "                    <tr class=\"modulo_table_list_title\">\n";
      $html .= "                      <td  align=\"center\" colspan='2'>\n";
      $html .= "                         CREAR TERCERO";
      $html .= "                      </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        TIPO ID TERCERO";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\" align=\"left\" >\n";
      $html .= "						".$select_tipoid;    
      $html .= "                        &nbsp; TERCERO ID";
      $html .= "                         <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"".$tercero['tercero_id']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "                        -";
      $html .= "						<input type=\"hidden\" value=\"3\">";
	  $html .= "                         <input type=\"text\" class=\"input-text\" id=\"dv\" name=\"dv\" maxlength=\"1\" size=\"3\" value=\"".$tercero['dv']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        NOMBRE";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "                         <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\" size=\"50\"  value=\"".$tercero['nombre_tercero']."\" onkeypress=\"\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        PAIS";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "".$select_pais;
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        DEPARTAMENTO";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\" id=\"depart\">\n";
      $html .= "                       <select id=\"tipo_dpto_id\" name=\"tipo_dpto_id\" class=\"select\" onchange=\"xajax_Buscar_Municipio(document.getElementById('tipo_pais_id').value,this.value)\">";
      $html .= "                          <option value=\"\">SELECCIONAR</option> \n";
      $html .= "                       </select>\n";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        MUNICIPIO";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\" id=\"muni\">\n";
      $html .= "                       <select id=\"tipo_mpio_id\" name=\"tipo_mpio_id\" class=\"select\" >";
      $html .= "                          <option value=\"\">SELECCIONAR</option> \n";
      $html .= "                       </select>\n";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        DIRECCION";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "                         <input type=\"text\" class=\"input-text\" name=\"direccion\" id=\"direccion\" maxlength=\"50\" size=\"50\" value=\"".$tercero['direccion']."\" onkeypress=\"\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        TELEFONO";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "                         <input type=\"text\" class=\"input-text\" id=\"telefono\" name=\"telefono\" maxlength=\"30\" size=\"30\" value=\"".$tercero['telefono']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        FAX";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "                         <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"15\" size=\"30\" value=\"".$tercero['fax']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        E-MAIL";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "                         <input type=\"text\" class=\"input-text\" id=\"email\" name=\"email\" maxlength=\"50\" size=\"50\" value=\"".$tercero['email']."\" onkeypress=\"\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td width=\"30%\"  align=\"center\">\n";
      $html .= "                        CELULAR";
      $html .= "                       </td>\n";
      $html .= "                       <td width=\"70%\"  align=\"left\">\n";
      $html .= "                         <input type=\"text\" class=\"input-text\" id=\"celular\" name=\"celular\" maxlength=\"15\" size=\"30\" value=\"".$tercero['celular']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td >\n";
	  $html .= "                       </td >\n";
	  $html .= "                       <td >\n";
      $html .= "                          PERSONA NATURAL";
      $html .= "                          <input type=\"radio\" class=\"input-text\" id=\"sw_persona_juridica\" name=\"sw_persona_juridica\" value=\"0\" checked>\n";
      $html .= "                          PERSONA JURIDICA";
      $html .= "                          <input type=\"radio\" class=\"input-text\" id=\"sw_persona_juridica\" name=\"sw_persona_juridica\" value=\"1\" >\n";
      $html .= "                       </td>\n";
      $html .= "                    </tr>\n";
	  /*
	   Datos Cliente, 
	   Espacio Destinado para llenar solo los datos del Cliente.
	    */
      $html .= "                    <tr class=\"modulo_table_list_title\">\n";
      $html .= "                       <td align=\"center\">INGRESAR COMO CLIENTE\n";
      $html .= "                       </td >\n";
      $html .= "                       <td >\n";
      $html .= "                         <input type=\"hidden\" value=\"".trim($datos_session['empresa_id'])."\" name=\"empresa_id\" id=\"empresa_id\">\n";
      $html .= "                         SI <input type=\"radio\" value=\"1\" name=\"tercero_cliente\" id=\"tercero_cliente\">\n";
      $html .= "                         NO <input type=\"radio\" value=\"0\" name=\"tercero_cliente\" id=\"tercero_cliente\" checked>\n";
	  $html .= "						-<B class=\"label_error\">(".$tercero['cliente'].")</B>";
      $html .= "                       </td>\n";
	  $html .= "                    </tr>\n";      
	  $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td align=\"center\">DATOS CLIENTE\n";
      $html .= "                       </td >\n";
	  $html .= "                       <td >\n";
	  $html .= "								<table class=\"modulo_table_list\" width=\"100%\">";	
	  $html .= "									<tr>";
	  $html .= "										<td>GRAN CONTRIBUYENTE";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"sw_gran_contribuyente\" name=\"sw_gran_contribuyente\" value=\"1\" >\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"sw_gran_contribuyente\" name=\"sw_gran_contribuyente\" value=\"0\" checked>\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>REGIMEN COMUN";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"sw_regimen_comun\" name=\"sw_regimen_comun\" value=\"1\" >\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"sw_regimen_comun\" name=\"sw_regimen_comun\" value=\"0\" checked>\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>RETENCION EN LA FUENTE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"sw_rtf\" name=\"sw_rtf\" value=\"1\" onclick=\"Ocultar_Condicionado(this.value,'porcentaje_rtf');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"sw_rtf\" name=\"sw_rtf\" value=\"0\" checked onclick=\"Ocultar_Condicionado(this.value,'porcentaje_rtf');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"3\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"porcentaje_rtf\" name=\"porcentaje_rtf\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>ICA ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"sw_ica\" name=\"sw_ica\" value=\"1\" onclick=\"Ocultar_Condicionado(this.value,'porcentaje_ica');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"sw_ica\" name=\"sw_ica\" value=\"0\" checked onclick=\"Ocultar_Condicionado(this.value,'porcentaje_ica');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"3\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"porcentaje_ica\" name=\"porcentaje_ica\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>RETE. IVA ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"sw_reteiva\" name=\"sw_reteiva\" value=\"1\"  onclick=\"Ocultar_Condicionado(this.value,'porcentaje_reteiva');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"sw_reteiva\" name=\"sw_reteiva\" value=\"0\" checked  onclick=\"Ocultar_Condicionado(this.value,'porcentaje_reteiva');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"3\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"porcentaje_reteiva\" name=\"porcentaje_reteiva\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>IMPTO. CREE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"sw_cree\" name=\"sw_cree\" value=\"1\"  onclick=\"Ocultar_Condicionado(this.value,'porcentaje_cree');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"sw_cree\" name=\"sw_cree\" value=\"0\" checked  onclick=\"Ocultar_Condicionado(this.value,'porcentaje_cree');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"4\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"porcentaje_cree\" name=\"porcentaje_cree\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>OBSERVACIONES ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				<TEXTAREA name=\"observacion\" id=\"observacion\" class=\"textarea\" style=\"width:100%\">".$tercero['observacion']."</TEXTAREA>";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>UNIDAD DE NEGOCIO ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "										".$select_uneg;
	  $html .= "										</td>";
	  $html .= "									</tr>";      
	  $html .= "									<tr>";
	  $html .= "										<td>TIPO CLIENTE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "										".$select_tc;
	  $html .= "										</td>";
	  $html .= "									</tr>";      
	  $html .= "									<tr>";
	  $html .= "										<td>CUENTA CONTABLE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                                                                                <input type=\"text\" class=\"input-text\" id=\"cuenta_contable_cliente\" name=\"cuenta_contable_cliente\" value='{$tercero['cuenta_contable']}' onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";      
	  $html .= "								</table>";	
	  $html .= "                       </td >\n";	  
	  $html .= "                    </tr>\n";            
	  /*
	  Fin Datos Cliente
	    */
	  /*
	    * Datos Proveedor
	   *  Espacio Destinado para llenar solo los datos del Proveedor.	
	    */
	  $html .= "                    <tr class=\"modulo_table_list_title\">\n";
      $html .= "                       <td align=\"center\">INGRESAR COMO PROVEEDOR\n";
      $html .= "                       </td >\n";
      $html .= "                       <td >\n";
      $html .= "                         SI <input type=\"radio\" value=\"1\" name=\"tercero_proveedor\" id=\"tercero_proveedor\">\n";
      $html .= "                         NO <input type=\"radio\" value=\"0\" name=\"tercero_proveedor\" id=\"tercero_proveedor\" checked>\n";
	  $html .= "						-<B class=\"label_error\">(".$tercero['proveedor'].")</B>";
      $html .= "                       </td>\n";
	  $html .= "                    </tr>\n";      
	  $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td align=\"center\">DATOS PROVEEDOR\n";
      $html .= "                       </td >\n";
	  $html .= "                       <td >\n";
	  $html .= "								<table class=\"modulo_table_list\" width=\"100%\">";
	  $html .= "      				<tr class=\"modulo_list_claro\">\n";
      $html .= "        				<td class=\"formulacion_table_list\">GERENTE</td>\n";
      $html .= "        				<td align=\"left\">\n";
      $html .= "           					<input type=\"text\" class=\"input-text\" name=\"nombre_gerente\" id=\"nombre_gerente\" maxlength=\"60\" value=\"".$tercero['nombre_gerente']."\" style=\"width:100%\">";
      $html .= "        				</td>\n";
      $html .= "      				</tr>\n";
      $html .= "     				<tr class=\"modulo_list_claro\">\n";
      $html .= "        				<td class=\"formulacion_table_list\" >TELEFONO GERENTE</td>\n";
      $html .= "        				<td align=\"left\">\n";
      $html .= "           					<input maxlength=\"15\" style=\"width:100%\" type=\"text\" class=\"input-text\" name=\"telefono_gerente\" id=\"telefono_gerente\" value=\"".$tercero['telefono_gerente']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "        				</td>\n";
      $html .= "      				</tr>\n";
      $html .= "      				<tr class=\"modulo_list_claro\">\n";
      $html .= "        				<td class=\"formulacion_table_list\">REPRESENTANTE DE VENTAS</td>\n";
      $html .= "        				<td align=\"left\">\n";
      $html .= "          					<input type=\"text\" class=\"input-text\" id=\"representante_ventas\" name=\"representante_ventas\" maxlength=\"40\"  style=\"width:100%\" value=\"".$tercero['representante_ventas']."\">";
      $html .= "        				</td>\n";
      $html .= "      				</tr>\n";
      $html .= "      				<tr class=\"modulo_list_claro\">\n";
      $html .= "        				<td class=\"formulacion_table_list\">TELEFONO REPRESENTANTE DE VENTAS</td>\n";
      $html .= "        				<td align=\"left\">\n";
      $html .= "          					<input maxlength=\"15\" style=\"width:100%\" type=\"text\" class=\"input-text\" id=\"telefono_representante_ventas\" name=\"telefono_representante_ventas\" value=\"".$tercero['telefono_representante_ventas']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "        			</td>\n";
      $html .= "      				</tr>\n";
	  
	  $html .= "      			<tr class=\"modulo_list_claro\">\n";
	  $html .= "						<td colspan=\"2\">";
	  /*
		*Sub Tabla Para Campos del Proveedor
		* 1) Dias Gracia
		* 2) Dias Credito
		* 3) Tiempo Entrega
		* 4) Descuento Contado
		*/
	  $html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
      $html .= "        						<tr>";
      $html .= "        								<td class=\"formulacion_table_list\">DIAS DE GRACIA :</td>\n";
      $html .= "										<td>";
	  $html .= "          									<input type=\"text\" class=\"input-text\" name=\"dias_gracia\" id=\"dias_gracia\" maxlength=\"3\" size=\"3\" value=\"".$tercero['dias_gracia']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "        								</td>\n";
      $html .= "        								<td class=\"formulacion_table_list\">DIAS CREDITO :</td>\n";
      $html .= "										<td>";
	  $html .= "          									<input type=\"text\" class=\"input-text\" name=\"dias_credito\" id=\"dias_credito\" maxlength=\"3\" size=\"3\" value=\"".$tercero['dias_credito']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "        								</td>\n";
      $html .= "      							</tr>\n";
	  $html .= "      							<tr class=\"modulo_list_claro\">\n";
      $html .= "        								<td class=\"formulacion_table_list\">TIEMPO ENTREGA :\n</td>";
      $html .= "										<td>";
	  $html .= "          									<input type=\"text\" class=\"input-text\" name=\"tiempo_entrega\" id=\"tiempo_entrega\" maxlength=\"3\" size=\"3\" value=\"".$tercero['tiempo_entrega']."\" onkeypress=\"return acceptNum(event)\">";
      $html .= "        								</td>\n";
      $html .= "        								<td class=\"formulacion_table_list\">DESCUENTO CONTADO :</td>\n";
      $html .= "										<td>";
	  $html .= "          									<input type=\"text\" class=\"input-text\" name=\"descuento_por_contado\" id=\"descuento_por_contado\" maxlength=\"3\" size=\"3\" value=\"".$tercero['descuento_por_contado']."\" onkeypress=\"return acceptNum(event)\">%";
      $html .= "        								</td>\n";
	  $html .= "      							</tr>\n";
	  $html .= "							</table>";
	  /*
	  * Fin Sub- Tabla 
	  */
	  $html .= "						 </td>";
	  $html .= "      			</tr>\n";
	  $html .= "      			<tr class=\"modulo_list_claro\">\n";
	  $html .= "						<td colspan=\"2\">";
	  /*
		*Sub Tabla Para Campos del Proveedor
		* 1) Formas de Pago
		*/
	  $html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
      $html .= "        						<tr>";
      $html .= "        								<td>ABONO A CUENTA:\n";
      $html .= "          									<input type=\"checkbox\" class=\"input-checkbox\" name=\"sw_pago_abono_cta\" id=\"sw_pago_abono_cta\" value=\"1\">";
      $html .= "        								</td>\n";
      $html .= "        								<td>CHEQUE:\n";
      $html .= "          									<input type=\"checkbox\" class=\"input-checkbox\" name=\"sw_pago_cheque\" id=\"sw_pago_cheque\" value=\"1\">";
      $html .= "        								</td>\n";
      $html .= "        								<td>EFECTIVO:\n";
      $html .= "          									<input type=\"checkbox\" class=\"input-checkbox\" name=\"sw_pago_efectivo\" id=\"sw_pago_efectivo\" value=\"1\">";
      $html .= "        								</td>\n";
      $html .= "      							</tr>\n";
	  $html .= "							</table>";
	  /*
	  * Fin Sub- Tabla 
	  */
	  $html .= "						 </td>";
	  $html .= "      			</tr>\n";
	  $html .= "      			<tr class=\"modulo_list_claro\">\n";
	  $html .= "						<td colspan=\"2\">";
	  /*
		*Sub Tabla Para Campos del Proveedor
		* 1) Grupo Actividad
		* 2) Actividad
		*/
	  $html .= "							<table class=\"modulo_table_list\" width=\"100%\">";
      $html .= "        						<tr>";
      $html .= "        								<td class=\"formulacion_table_list\">GRUPO DE ACTIVIDAD</td>\n";
      $html .= "										<td>";
	  $html .= "          									".$select_ga;
      $html .= "        								</td>\n";
      $html .= "      							</tr>\n";
	  $html .= "      							<tr class=\"modulo_list_claro\">\n";
      $html .= "        								<td class=\"formulacion_table_list\">ACTIVIDAD\n</td>";
      $html .= "										<td>";
	  $html .= "          									<select id=\"actividad_id\" name=\"actividad_id\" class=\"select\">";
	  $html .= "          									<option value=\"\">-- SELECCIONAR --</select>";
	  $html .= "          									</select>";
      $html .= "        								</td>\n";
      $html .= "      							</tr>\n";
	  $html .= "							</table>";
	  /*
	  * Fin Sub- Tabla 
	  */
	  $html .= "						 </td>";
	  $html .= "      			</tr>\n";
	  $html .= "									<tr>";
	  $html .= "										<td>GRAN CONTRIBUYENTE";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"prov_sw_gran_contribuyente\" name=\"prov_sw_gran_contribuyente\" value=\"1\" >\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"prov_sw_gran_contribuyente\" name=\"prov_sw_gran_contribuyente\" value=\"0\" checked>\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>REGIMEN COMUN";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"prov_sw_regimen_comun\" name=\"prov_sw_regimen_comun\" value=\"1\" >\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"prov_sw_regimen_comun\" name=\"prov_sw_regimen_comun\" value=\"0\" checked>\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>RETENCION EN LA FUENTE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"prov_sw_rtf\" name=\"prov_sw_rtf\" value=\"1\" onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_rtf');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"prov_sw_rtf\" name=\"prov_sw_rtf\" value=\"0\" checked onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_rtf');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"3\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"prov_porcentaje_rtf\" name=\"prov_porcentaje_rtf\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>ICA ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"prov_sw_ica\" name=\"prov_sw_ica\" value=\"1\" onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_ica');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"prov_sw_ica\" name=\"prov_sw_ica\" value=\"0\" checked onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_ica');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"3\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"prov_porcentaje_ica\" name=\"prov_porcentaje_ica\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>RETE. IVA ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"prov_sw_reteiva\" name=\"prov_sw_reteiva\" value=\"1\"  onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_reteiva');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"prov_sw_reteiva\" name=\"prov_sw_reteiva\" value=\"0\" checked  onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_reteiva');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"3\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"prov_porcentaje_reteiva\" name=\"prov_porcentaje_reteiva\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
	  $html .= "									<tr>";
	  $html .= "										<td>IMPTO. CREE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                          				SI <input type=\"radio\" class=\"input-text\" id=\"prov_sw_cree\" name=\"prov_sw_cree\" value=\"1\"  onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_cree');\">\n";
	  $html .= "                          				NO <input type=\"radio\" class=\"input-text\" id=\"prov_sw_cree\" name=\"prov_sw_cree\" value=\"0\" checked  onclick=\"Ocultar_Condicionado(this.value,'prov_porcentaje_cree');\">\n";
	  $html .= "										<input type=\"hidden\" value=\"4\">";
	  $html .= "                          				% <input type=\"text\" class=\"input-text\" id=\"prov_porcentaje_cree\" name=\"prov_porcentaje_cree\" value=\"0\" readOnly maxlength=\"3\" onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";
          $html .= "									<tr>";
	  $html .= "										<td>CUENTA CONTABLE ";
	  $html .= "										</td>";
	  $html .= "										<td>";
	  $html .= "                                                                                <input type=\"text\" class=\"input-text\" id=\"cuenta_contable_proveedor\" name=\"cuenta_contable_proveedor\" readOnly value='{$tercero['cxp_proveedor']}' onkeypress=\"return acceptNum(event)\">\n";
	  $html .= "										</td>";
	  $html .= "									</tr>";      
	  $html .= "								</table>";	
	  $html .= "                       </td >\n";	  
	  $html .= "                    </tr>\n";      
      $html .= "                    <tr class=\"modulo_list_claro\">\n";
      $html .= "                       <td colspan='2'  align=\"center\">\n";
	  /*
	     Para Definir que Tipo de Consulta Realizar al momento de dar clic en el Boton Registrar
		 1- Insercion
		 0- Modificacion
	    */
	  if(empty($tercero))
	  {
	  $html .= "						<input type=\"hidden\" name=\"consulta\" id=\"consulta\" value=\"1\">";
	  
	  }
		else
			{
			$html .= "						<input type=\"hidden\" name=\"consulta\" id=\"consulta\" value=\"0\">";
			$html .= "						<input type=\"hidden\" name=\"tipo_id_tercero_old\" id=\"tipo_id_tercero_old\" value=\"".$tercero['tipo_id_tercero']."\">";
			$html .= "						<input type=\"hidden\" name=\"tercero_id_old\" id=\"tercero_id_old\" value=\"".$tercero['tercero_id']."\">";
			}
	$html .= "						 <input type=\"hidden\" name=\"es_cliente\" id=\"es_cliente\" value=\"".$tercero['tercero_cliente']."\">";
	$html .= "						 <input type=\"hidden\" name=\"es_proveedor\" id=\"es_proveedor\" value=\"".$tercero['tercero_proveedor']."\">";
	$html .= "                         <input type=\"submit\" class=\"input-submit\" value=\"Registrar\">\n";
	$html .= "                       </td>\n";
	$html .= "                    </tr>\n";
	$html .= "                 </table>\n";
    $html .= "           </form>";
    
    $html .= "     <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "      <table align=\"center\" width=\"50%\">\n";
    $html .= "       <tr>\n";
    $html .= "        <td align=\"center\" colspan='7'>\n";
    $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $html .= "        </td>\n";
    $html .= "       </tr>\n";
    $html .= "      </table>\n";
    $html .= "     </form>";
    $html .= "    </div>";
    $html .= ThemeCerrarTabla();
	/*
	Precarga de algunos datos Del Cliente que requieren Xajax y Javascript
	*/
	if(!empty($tercero))
	  {

	$html .= " <script>";
	/*Cliente*/
	$html .= "	xajax_Buscar_Departamento('".$tercero['tipo_pais_id']."','".$tercero['tipo_dpto_id']."');";
	$html .= "	xajax_Buscar_Municipio('".$tercero['tipo_pais_id']."','".$tercero['tipo_dpto_id']."','".$tercero['tipo_mpio_id']."');";
	$html .= "	xajax_Buscar_Actividad('".$tercero['grupo_id']."','".$tercero['actividad_id']."');";
	$html .= "	Ocultar('".$tercero['tipo_id_tercero']."');";
	$html .= "	for (i=0;i<document.Formulario.sw_gran_contribuyente.length;i++){
					if (document.Formulario.sw_gran_contribuyente[i].value=='".$tercero['sw_gran_contribuyente']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_gran_contribuyente[i].checked=true;";
	$html .= "		}
					}";
	$html .= "	for (i=0;i<document.Formulario.sw_regimen_comun.length;i++){
					if (document.Formulario.sw_regimen_comun[i].value=='".$tercero['sw_regimen_comun']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_regimen_comun[i].checked=true;";
	$html .= "		}
					}";
	$html .= "	for (i=0;i<document.Formulario.sw_rtf.length;i++){
					if (document.Formulario.sw_rtf[i].value=='".$tercero['sw_rtf']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_rtf[i].checked=true;";
	$html .= "		}
					}";
					
	$html .= "	for (i=0;i<document.Formulario.sw_ica.length;i++){
					if (document.Formulario.sw_ica[i].value=='".$tercero['sw_ica']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_ica[i].checked=true;";
	$html .= "		}
					}";	

	$html .= "	for (i=0;i<document.Formulario.sw_cree.length;i++){
					if (document.Formulario.sw_cree[i].value=='".$tercero['sw_cree']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_cree[i].checked=true;";
	$html .= "		}
					}";					
					
	$html .= "	for (i=0;i<document.Formulario.sw_reteiva.length;i++){
					if (document.Formulario.sw_reteiva[i].value=='".$tercero['sw_reteiva']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_reteiva[i].checked=true;";
	$html .= "		}
					}";	
	
	$html .= "	for (i=0;i<document.Formulario.tercero_cliente.length;i++){
					if (document.Formulario.tercero_cliente[i].value=='".$tercero['tercero_cliente']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.tercero_cliente[i].checked=true;";
	$html .= "		}} ";
	
					
	$html .= "	for (i=0;i<document.Formulario.sw_persona_juridica.length;i++){
					if (document.Formulario.sw_persona_juridica[i].value=='".$tercero['sw_persona_juridica']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.sw_persona_juridica[i].checked=true;";
	$html .= "		}
					}";		
	/*
	* Cliente
	*/
	$html .= "		Ocultar_Condicionado('".$tercero['sw_rtf']."','porcentaje_rtf');";
	$html .= "		document.Formulario.porcentaje_rtf.value='".$tercero['porcentaje_rtf']."';";
	$html .= "		Ocultar_Condicionado('".$tercero['sw_ica']."','porcentaje_ica');";
	$html .= "		document.Formulario.porcentaje_ica.value='".$tercero['porcentaje_ica']."';";

	$html .= "		Ocultar_Condicionado('".$tercero['sw_cree']."','porcentaje_cree');";
	$html .= "		document.Formulario.porcentaje_cree.value='".$tercero['porcentaje_cree']."';";

	$html .= "		Ocultar_Condicionado('".$tercero['sw_reteiva']."','porcentaje_reteiva');";
	$html .= "		document.Formulario.porcentaje_reteiva.value='".$tercero['porcentaje_reteiva']."';";	
	/*
	* Proveedor
	*/
	$html .= "		Ocultar_Condicionado('".$tercero['prov_sw_rtf']."','prov_porcentaje_rtf');";
	$html .= "		document.Formulario.prov_porcentaje_rtf.value='".$tercero['prov_porcentaje_rtf']."';";
	$html .= "		Ocultar_Condicionado('".$tercero['prov_sw_ica']."','prov_porcentaje_ica');";
	$html .= "		document.Formulario.prov_porcentaje_ica.value='".$tercero['prov_porcentaje_ica']."';";

	$html .= "		Ocultar_Condicionado('".$tercero['prov_sw_cree']."','prov_porcentaje_cree');";
	$html .= "		document.Formulario.prov_porcentaje_cree.value='".$tercero['prov_porcentaje_cree']."';";	

	$html .= "		Ocultar_Condicionado('".$tercero['prov_sw_reteiva']."','prov_porcentaje_reteiva');";
	$html .= "		document.Formulario.prov_porcentaje_reteiva.value='".$tercero['prov_porcentaje_reteiva']."';";
	if($tercero['sw_pago_abono_cta']=='1')
	$html .= "		document.getElementById('sw_pago_abono_cta').checked=true;";
	if($tercero['sw_pago_cheque']=='1')
	$html .= "		document.getElementById('sw_pago_cheque').checked=true;";
	if($tercero['sw_pago_efectivo']=='1')
	$html .= "		document.getElementById('sw_pago_efectivo').checked=true;";
	$html .= " </script>";
	/*
	* Proveedor
	*/
	$html .= " <script>";
	$html .= "	for (i=0;i<document.Formulario.prov_sw_gran_contribuyente.length;i++){
					if (document.Formulario.prov_sw_gran_contribuyente[i].value=='".$tercero['prov_sw_gran_contribuyente']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.prov_sw_gran_contribuyente[i].checked=true;";
	$html .= "		}
					}";
	$html .= "	for (i=0;i<document.Formulario.prov_sw_regimen_comun.length;i++){
					if (document.Formulario.prov_sw_regimen_comun[i].value=='".$tercero['prov_sw_regimen_comun']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.prov_sw_regimen_comun[i].checked=true;";
	$html .= "		}
					}";
	$html .= "	for (i=0;i<document.Formulario.prov_sw_rtf.length;i++){
					if (document.Formulario.prov_sw_rtf[i].value=='".$tercero['prov_sw_rtf']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.prov_sw_rtf[i].checked=true;";
	$html .= "		}
					}";
					
	$html .= "	for (i=0;i<document.Formulario.prov_sw_ica.length;i++){
					if (document.Formulario.prov_sw_ica[i].value=='".$tercero['prov_sw_ica']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.prov_sw_ica[i].checked=true;";
	$html .= "		}
					}";					

	$html .= "	for (i=0;i<document.Formulario.prov_sw_cree.length;i++){
					if (document.Formulario.prov_sw_cree[i].value=='".$tercero['prov_sw_cree']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.prov_sw_cree[i].checked=true;";
	$html .= "		}
					}";					
					
	$html .= "	for (i=0;i<document.Formulario.prov_sw_reteiva.length;i++){
					if (document.Formulario.prov_sw_reteiva[i].value=='".$tercero['prov_sw_reteiva']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.prov_sw_reteiva[i].checked=true;";
	$html .= "		}
					}";	
	
	$html .= "	for (i=0;i<document.Formulario.tercero_proveedor.length;i++){
					if (document.Formulario.tercero_proveedor[i].value=='".$tercero['tercero_proveedor']."') ";
	$html .= "		{";
	$html .= "		document.Formulario.tercero_proveedor[i].checked=true;";
	$html .= "		}} ";
	
	$html .= " </script>";	
		}
		else
		{
	$html .= " <script>";
	$html .= "	Ocultar('NIT');";
	$html .= "		Ocultar_Condicionado('0','porcentaje_rtf');";
	$html .= "		Ocultar_Condicionado('0','porcentaje_ica');";
	$html .= "		Ocultar_Condicionado('0','porcentaje_reteiva');";
	$html .= " </script>";
		}
	 /*
	        *Mensajes de Notificacion
	        */
			//print_r($mensaje);
	    
	
	/*
	FIN Precarga de algunos datos que requieren Xajax y Javascript
	*/
	
    return $html;
    }


/**
	* Metodo para establecer el menu de gestion de terceros.
	* @return string $html forma del menu
	* @access public
	*/


  function FormaTercerosBancos($action,$tercero,$bancos,$bancos_tercero,$tipos_cuentas,$bancos_cuentas)
  {
	$ctl = AutoCarga::factory("ClaseUtil");
	$html  = $ctl->LimpiarCampos();
	$html .= $ctl->RollOverFilas();
	$html .= $ctl->AcceptDate('/');
	$html .= $ctl->AcceptNum(true);
	
	/*Funciones Javascript*/
	$html .="	<script>";
	$html .="	function Validar_Campos(Formulario)";
	$html .="	{";
	$html .="	var mensaje=\"\";";
	$html .="	if(Formulario.tercero_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Identificador Del Tercero No Puede Estar Vacío <br> ';	";
	$html .="	if(Formulario.nombre_tercero.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Nombre Del Tercero No Puede Estar Vacío <br> ';	";
	$html .="	if(Formulario.tipo_pais_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Pais Del Tercero No Puede Estar Vacío <br>';	";
	$html .="	if(Formulario.tipo_dpto_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Departamento Del Tercero No Puede Estar Vacío <br>';	";
	$html .="	if(Formulario.tipo_mpio_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Municipio Del Tercero No Puede Estar Vacío <br>';	";
	$html .="	if(Formulario.direccion.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'La Direccion Del Tercero No Puede Estar Vacía <br>';	";
	$html .="	if(Formulario.tipo_id_tercero.value==\"NIT\")";
	$html .="	if(Formulario.dv.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'El Digito de Verificacion del Tercero No Puede Estar Vacío <br>';	";
	
	/*Validacion Si Se Escoge cliente*/
	
	$html .="   if(Formulario.tercero_cliente[0].checked==true)";
	$html .="	{";
	/*$html .="	alert(parseInt(Formulario.porcentaje_rtf.value));";*/
	
	$html .="	if(Formulario.sw_rtf[0].checked==true)";
	$html .="	if(Formulario.porcentaje_rtf.value==\"\" || parseInt(Formulario.porcentaje_rtf.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de Retencion en la Fuente Debe ser Mayor a Cero - CLIENTE <br>';	";
	
	$html .="	if(Formulario.sw_ica[0].checked==true)";
	$html .="	if(Formulario.porcentaje_ica.value==\"\" || parseInt(Formulario.porcentaje_ica.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de ICA Debe ser Mayor a Cero - CLIENTE <br>';	";
	
	$html .="	if(Formulario.sw_reteiva[0].checked==true)";
	$html .="	if(Formulario.porcentaje_reteiva.value==\"\" || parseInt(Formulario.porcentaje_reteiva.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de RETE-IVA Debe ser Mayor a Cero - CLIENTE <br>';	";
	$html .="	}";	

	
	/*Validacion Si Se Escoge Proveedor*/
	
	$html .="   if(Formulario.tercero_proveedor[0].checked==true)";
	$html .="	{";
	/*$html .="	alert(parseInt(Formulario.porcentaje_rtf.value));";*/
	
	$html .="	if(Formulario.prov_sw_rtf[0].checked==true)";
	$html .="	if(Formulario.prov_porcentaje_rtf.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.prov_porcentaje_rtf.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de Retencion en la Fuente Debe ser Mayor a Cero - PROVEEDOR <br>';	";
	
	$html .="	if(Formulario.prov_sw_ica[0].checked==true)";
	$html .="	if(Formulario.prov_porcentaje_ica.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.prov_porcentaje_ica.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de ICA Debe ser Mayor a Cero - PROVEEDOR <br>';	";
	
	$html .="	if(Formulario.prov_sw_reteiva[0].checked==true)";
	$html .="	if(Formulario.prov_porcentaje_reteiva.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.prov_porcentaje_reteiva.value)<=0)";
	$html .="	mensaje += 'El Porcentaje de RETE-IVA Debe ser Mayor a Cero - PROVEEDOR <br>';	";
	
	$html .="	if(Formulario.grupo_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'Debe Seleccionar El Grupo De Actividad del Proveedor <br>';	";
	
	$html .="	if(Formulario.actividad_id.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\")";
	$html .="	mensaje += 'Debe Seleccionar La Actividad del Proveedor <br>';	";
	
	$html .="	if(Formulario.dias_gracia.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.dias_gracia.value)<0)";
	$html .="	mensaje += 'Dias Gracia del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	$html .="	if(Formulario.dias_credito.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.dias_credito.value)<0)";
	$html .="	mensaje += 'Dias Credito del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	$html .="	if(Formulario.tiempo_entrega.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.tiempo_entrega.value)<0)";
	$html .="	mensaje += 'Tiempo Entrega del Proveedor debe ser mayor o igual a Cero <br>';	";
	
	$html .="	if(Formulario.descuento_por_contado.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.descuento_por_contado.value)<0)";
	$html .="	mensaje += 'Descuento Por Contado del Proveedor debe ser mayor o igual a Cero <br>';	";
		
	$html .="	if(Formulario.descuento_por_contado.value.replace(/^\s+/g,'').replace(/\s+$/g,'') == \"\" || parseInt(Formulario.descuento_por_contado.value)<0)";
	$html .="	mensaje += 'Descuento Por Contado del Proveedor debe ser mayor o igual a Cero <br>';	";
	$html .="	}";
	
	
	$html .="	document.getElementById('error').innerHTML = mensaje; ";
	$html .="	if(mensaje.replace(/^\s+/g,'').replace(/\s+$/g,'')==\"\")";
	$html .="	Formulario.submit();";
	$html .="	else";
	$html .="	return false;	";
	
	$html .="	}";
	$html .="	</script>";
	/*FIN Funciones Javascript*/

	//print_r($paises);
	$selected = "";
	$select_bancos = "<select class=\"select\" name=\"banco\" id=\"banco\" style=\"width:100%\">";
	foreach($bancos as $key=>$v)
	{
	$select_bancos .= "<option  value=\"".$v['banco']."\" ".$selected.">".$v['descripcion']."</option>";
	}
	$select_bancos .= "</select>";
	
	
	foreach($tipos_cuentas as $ky=>$vl)
	{
	$option_tipos_cuentas .= "<option  value=\"".$vl['tipo_de_cuenta_id']."\" ".$selected.">".$vl['descripcion']."</option>";
	}
	
	
	$html .= ThemeAbrirTabla("BANCOS DEL TERCERO: 	&#39;&#40;".$tercero['tipo_id_tercero']."-".$tercero['tercero_id']."&#41;".$tercero['nombre_tercero']."&#39;");
    $html .= "                  <div id='error' class='label_error' style=\"text-transform: uppercase; text-align:center;\">".$mensaje[0]."</div>\n";
	$html .= "            <form name=\"Formulario\" action=\"".$action['guardar']."\" method=\"post\" >\n"; /*onSubmit=\"Validar_Campos(document.Formulario); return false;\"*/
    $html .= "                   <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "                    	<tr class=\"modulo_table_list_title\">\n";
	$html .= "							<td>";
	$html .= "								BANCOS:";
	$html .= "							</td>";
	$html .= "							<td>";
	$html .= "								".$select_bancos;
	$html .= "							</td>";
	$html .= "							<td>";
	$html .= "								<input type=\"hidden\" value=\"".$tercero['tipo_id_tercero']."\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" >";
	$html .= "								<input type=\"hidden\" value=\"".$tercero['tercero_id']."\" name=\"tercero_id\" id=\"tercero_id\" >";
	$html .= "								<input type=\"hidden\" value=\"1\" name=\"consulta_bancos\" id=\"consulta_bancos\" >";
	$html .= "								<input type=\"submit\" value=\"REGISTRAR BANCO AL TERCERO\" class=\"input-submit\">";
	$html .= "							</td>";
	$html .= "						</tr>";
	$html .= "                 </table>\n";
    $html .= "           </form>";
    $html .= "<br>";
	$html .= "            <form name=\"Formulario\" action=\"".$action['guardar']."\" method=\"post\" >";
	$html .= "                   <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "                    	<tr class=\"modulo_table_list_title\">\n";
	$html .= "							<td colspan=\"2\">";
	$html .= "								BANCOS DEL TERCERO";
	$html .= "								<input type=\"submit\" value=\"GUARDAR\" class=\"input-submit\">";
	$html .= "							</td>";
	$html .= "						</tr>";
	$i=0;
	foreach($bancos_tercero as $k=>$valor)
	{
	$html .= "                    	<tr class=\"modulo_list_claro\">\n";
	$html .= "							<td align=\"center\">";
	$html .= "								<b class=\"label_error\">".$valor['descripcion']."";
	$html .= "								<a href=\"".$action['guardar']."&tipo_id_tercero=".$valor['tipo_id_tercero']."&tercero_id=".$valor['tercero_id']."&banco=".$valor['banco']."&borrar_banco=1\" class=\"label_error\"  title=\"MODIFICAR TERCERO\"><img src=\"".GetThemePath()."/images/delete.gif\" border='0' ></a></b>\n";
	$html .= "                   				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "										<tr>";
	$html .= "											<td>";
	$html .= "												NUMERO DE CUENTA";
	$html .= "											</td>";
	$html .= "											<td>";
	$html .= "												<input type=\"text\" name=\"numero_cuenta[]\" id=\"numero_cuenta[]\" value=\"\" class=\"input-text\" style=\"width:100%\"> ";
	$html .= "											</td>";
	$html .= "										</tr>";
	$html .= "										<tr>";
	$html .= "											<td>";
	$html .= "												TIPO DE CUENTA";
	$html .= "											</td>";
	$html .= "											<td>";
	$html .= "												<input type=\"hidden\" name=\"banco[]\" id=\"banco[]\" value=\"".$valor['banco']."\">";
	$html .= "												<select class=\"select\" name=\"tipo_de_cuenta_id[]\" id=\"tipo_de_cuenta_id[]\" style=\"width:100%\">";
	$html .= "												".$option_tipos_cuentas;
	$html .= "												</select>";
	$html .= "											</td>";
	$html .= "										</tr>";
	$html .= "                   				</table>\n";
	$html .= "							</td>";
	/*CUENTAS POR BANCO*/
	$html .= "							<td>";
		foreach($bancos_cuentas[$valor['banco']] as $llave => $value)
		{
	$html .= "                   				<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "										<tr>";
	$html .= "											<td width=\"50%\">";
	$html .= "									<li>";
	$html .= "									<b>Numero de Cuenta:</b> ".$value['numero_cuenta'];
	$html .= "									<br>";
	$html .= "									<b>Tipo de Cuenta:</b> ".$value['descripcion'];
	$html .= "									</li>";
	$html .= "											</td>";
	$html .= "											<td  width=\"50%\">";
	$html .= "												<a href=\"".$action['guardar']."&tipo_id_tercero=".$tercero['tipo_id_tercero']."&tercero_id=".$tercero['tercero_id']."&numero_cuenta=".$value['numero_cuenta']."&banco=".$value['banco']."&borrar_bancocuenta=1\" class=\"label_error\"  title=\"MODIFICAR TERCERO\"><img src=\"".GetThemePath()."/images/delete2.gif\" border='0' ></a>\n";
	$html .= "											</td>";
	$html .= "										</tr>";
	$html .= "                   				</table>\n";
		}
	$html .= "							</td>";
	/*FIN CUENTAS POR BANCO*/
	$html .= "						</tr>";
	$i++;
	}
	$html .= "                    	<tr class=\"modulo_table_list_title\">\n";
	$html .= "							<td colspan=\"2\">";
	$html .= "								<input type=\"hidden\" value=\"".$tercero['tipo_id_tercero']."\" name=\"tipo_id_tercero\" id=\"tipo_id_tercero\" >";
	$html .= "								<input type=\"hidden\" value=\"".$tercero['tercero_id']."\" name=\"tercero_id\" id=\"tercero_id\" >";
	$html .= "								<input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">";
	$html .= "								<input type=\"hidden\" value=\"1\" name=\"consulta_cuentas\" id=\"consulta_cuentas\" >";
	$html .= "								<input type=\"submit\" value=\"GUARDAR\" class=\"input-submit\">";
	$html .= "							</td>";
	$html .= "						</tr>";
	$html .= "                 </table>\n";
	$html .= "           </form>";
	
	
    $html .= "     <form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
    $html .= "      <table align=\"center\" width=\"50%\">\n";
    $html .= "       <tr>\n";
    $html .= "        <td align=\"center\" colspan='7'>\n";
    $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $html .= "        </td>\n";
    $html .= "       </tr>\n";
    $html .= "      </table>\n";
    $html .= "     </form>";
    $html .= "    </div>";
    $html .= ThemeCerrarTabla();
	
	
    return $html;
    }



/**
  * Metodo para establecer el menu de gestion de proveedores.
  * @return string $html forma del menu
  * @access public
  */
  function Proveedores()
  {
      $this->CrearElementos();
      $file ='app_modules/CrearProveedores/RemoteXajax/definirProv.php';
      $this->SetXajax(array("Municipios9","Departamento29","Municipios9","Actividades_sgrupo9",
                            "UpProveedor","Guardar_DYM2","Modificar_pro","switch_proveedor",
                            "Guardar_DYM1","GuardarProveedor","Actividades_sgrupo","CrearUSA1",
                            "GetProveedores","Guardar_DYM","GuardarPersona","Municipios",
                            "Departamento2","CrearUSA","GetTercerinos","TipoBusqueda1",
                            "GetTercerinosOrderBy","asignacion_bancos","InsertarBancoProveedor",
                            "ListadoCuentasProveedor","Borrar","ModPrioridad","AsignarFormasPago",
                            "ListadoBancoFormasPago","InsertarFormaPagoBanco","BorrarFormaPagoBanco"),$file);
      IncludeClass("ClaseHTML");
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");
      $path = SessionGetVar("rutaImagenes");
      $consulta=new CrearSQL();
      $this->IncludeJS('RemoteXajax/definirProv.js', $contenedor='app', $modulo='CrearProveedores');

    $html .="<script>";
    $html .= "  function Paginador_1(CodigoProveedor,offset)\n";
    $html .= "  {";
    $html .= "    xajax_ListadoCuentasProveedor(CodigoProveedor,offset);\n";
    $html .= "  }\n";   
    $html .="</script>";
    
    $html .="<script>";
    $html .= "  function validar(FormularioBanco)";
    $html .= "  {";
    $html .= "    var band=1;
                  if(FormularioBanco.numero_cuenta==\"\")";
    $html .= "       band=0;";
    
    $html .= "     if(band==1)";
    $html .= "        xajax_InsertarBancoProveedor(FormularioBanco);     ";
    $html .= "     else";
    $html .= "   {";
    $html .= "    alert('Error, El Numero de Cuenta se encuentra vacio!!!');";
    $html .= "    return(false);";
    $html .= "   }
                 }";
    $html .="</script>";
    
    $html .= $html;
    $html .= ThemeAbrirTabla("ADMINISTRADOR DE PROVEEDORES");
    $html .= "            <form name=\"prov\" action=\"javascript:Buscar_Proveedores();\" method=\"post\">\n";
    $html .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "                    <tr class=\"modulo_table_list_title\">\n";
    $html .= "                       <td colspan=\"5\" align=\"center\">\n";
    $html .= "                          BUSCADOR DE TERCEROS";
    $html .= "                       </td>\n";
    $html .= "                    </tr>\n";
    $html .= "                    <tr class=\"modulo_list_claro\">\n";
    $html .= "                       <td align=\"center\">\n";
    $html .= "                          TIPO DE BUSQUEDA";
    $html .= "                       </td>\n";
    $html .= "                       <td align=\"center\">\n";
    $html .= "                         <select name=\"tip_bus\" id=\"tipos_bus\" class=\"select\" onchange=\"TipodeBusqueda1(this.value);\">";
    $html .= "                           <option value=\"1\" selected>IDENTIFICACION</option> \n";
    $html .= "                           <option value=\"2\">NOMBRE</option> \n";
    $html .= "                           <option value=\"2\">RAZON SOCIAL</option> \n";
    $html .= "                           <option value=\"0\">TODOS</option> \n";
    $html .= "                         </select>\n";
    $html .= "                       </td>\n";
    $html .= "                       <td id=\"aux\" align=\"center\">\n";
    $html .= "                          TIPO DE DOCUMENTO";
    $tipos_id=$consulta->Terceros_id();
    if(!empty($tipos_id))
    {
      $html.= "                            <select id=\"tipos_id\" name=\"tipos_id\" class=\"select\" onchange=\"Tachar1(this.value);\">";
      for($i=0;$i<count($tipos_id);$i++)
      {
        $html.="                                 <option value=\"".$tipos_id[$i]['tipo_id_tercero']."\">".$tipos_id[$i]['tipo_id_tercero']."</option> \n";
      }
      $html.= "                             </select>\n";
    }
    $html .= "                             &nbsp; TERCERO ID";
    $html .= "                             <input type=\"text\" class=\"input-text\" id=\"tercero_id1\" name=\"tercero_id1\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTeclab(event);\" onclick=\"limpiar()\">";
    $html .= "                             &nbsp; - &nbsp;";
    $html .= "                             <input type=\"text\" class=\"input-text\" id=\"dv1\" name=\"dv1\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\" onkeydown=\"recogerTeclab(event);\" onclick=\"limpiar()\">";
    $html .= "                       </td>\n";
    $html .= "                       <td id=\"busqueda\" align=\"center\">\n";
    $html .= "                         <input type=\"button\" class=\"input-submit\" value=\"BUSCAR\" onclick=\"BotonBuscarProv();\">\n";
    $html .= "                       </td>\n";
    $html .= "                    </tr>\n";
    $html .= "                  </table>";
    $html .= "                  <br>\n";
    $html .= "                   <table align=\"center\" BORDER='0' width=\"60%\">\n";
    $html .= "                    <tr>\n";
    $html .= "                      <td align=\"center\">\n";
    //$nuevocen = "javascript:MostrarCapa('ContenedorCent');CrearProveedor();IniciarCent('CREAR NUEVO PROVEEDOR');";
    $nuevocen = "javascript:CrearProveedor();";
    $html .= "                          <a  title=\"CREAR NUEVO PROVEEDOR\" class=\"label_error\" href=\"".$nuevocen."\">CREAR NUEVO PROVEEDOR</a>\n";
    $html .= "                      </td>\n";
    $html .= "                    </tr>\n";
    $html .= "                   </table>\n";
    $html .= "                   <br>\n";
    $html .= "                   <div id='error_ter' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $html .= "                   <div id=\"lista_prov\">";

    //Despliega la Lista arrojada por XAJAX de Proveedores Creados


    $html .= "                   </div>\n";
    $html .= "           </form>";
    $html.="<script language=\"javaScript\">
              var ban=0;
              function mOvr(src,clrOver)
                {
                  src.style.background = clrOver;
                }

              function mOut(src,clrIn)
                {
                  src.style.background = clrIn;
                }

             </script>";

     $Menu=ModuloGetURL('app','CrearProveedores','user','Menu');

     $var="&Empresas[empresa_id]=".$_REQUEST['Empresas']['empresa_id']."";
    $html .= "    <div id=\"volvercen_cos\">";
    $html .= "     <form name=\"volver\" action=\"".$Menu."".$var."\" method=\"post\">\n";
    $html .= "      <table align=\"center\" width=\"50%\">\n";
    $html .= "       <tr>\n";
    $html .= "        <td align=\"center\" colspan='7'>\n";
    $html .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
    $html .= "        </td>\n";
    $html .= "       </tr>\n";
    $html .= "      </table>\n";
    $html .= "     </form>";
    $html .= "    </div>";
    $html .=$this->CrearVentana(640,"PROVEEDORES");
    $html .= ThemeCerrarTabla();
    
    return true;
    }

    
    
    
    
    
    
    
  //CREA LA VENTANITA O CAPA A SER INVOCADA.
  function CrearVentana($tmn,$titulo)
    {

    $html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
    $html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$titulo."</div>\n";
    $html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
    $html .= "  <div id='Contenido' class='d2Content'>\n";
     
      $html .= "  </div>\n";
      $html .= "</div>\n";

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
     
     
     
      return ($html);
    }



}

?>