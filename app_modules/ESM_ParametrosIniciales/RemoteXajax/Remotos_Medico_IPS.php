<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos_TipoEvento.php
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
    
  function Listado_ProfesionalesSinEsm($esm_empresas,$nombre,$offset)
  {
	$objResponse = new xajaxResponse();
  list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$esm_empresas);
	$sql = AutoCarga::factory("Consultas_Medico_IPS","classes","app","ESM_ParametrosIniciales");

	$Terceros=$sql->Listado_ProfesionalesSinEsm($esm_tipo_id_tercero,$esm_tercero_id,$nombre,$offset);

	$action['paginador'] = "Paginador('".$esm_empresas."','".$nombre."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:80%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">PROFESIONALES NO ASIGNADOS A UNA IPS</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
	$html .= "      <td width=\"50%\">NOMBRE</td>\n";
	$html .= "      <td width=\"25%\">PROFESIONAL</td>\n";
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
	$html .= "      <td>".$ED['nombre']." </td>\n";
	$html .= "      <td>".$ED['descripcion']." </td>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <a href=\"#\" onclick=\"xajax_Asignar_ProfesionalESM('".$esm_empresas."','".$ED['tipo_id_tercero']."','".$ED['tercero_id']."')\">\n";
	$html .= "          <img title=\"NO PERTENECE AL ESM\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
	// Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
	$html .= "        </a>\n";
	$html .= "      </td>\n";

	$html .= "    </tr>\n";
	}
	$html .= "<center>";



	$html .= "    </table>\n";
	$html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado_Profesionales","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_ProfesionalesEnEsm($esm_empresas,$nombre,$offset)
  {
	$objResponse = new xajaxResponse();
  list($esm_tipo_id_tercero,$esm_tercero_id) = explode("@",$esm_empresas);
	$sql = AutoCarga::factory("Consultas_Medico_IPS","classes","app","ESM_ParametrosIniciales");

	$Terceros=$sql->Listado_ProfesionalesEnEsm($esm_tipo_id_tercero,$esm_tercero_id,$nombre,$offset);

	$action['paginador'] = "Paginador_('".$esm_empresas."','".$nombre."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:80%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">PROFESIONALES ASIGNADOS A UNA IPS SELECCIONADA</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
	$html .= "      <td width=\"50%\">NOMBRE</td>\n";
	$html .= "      <td width=\"25%\">PROFESIONAL</td>\n";
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
	$html .= "      <td>".$ED['nombre']." </td>\n";
	$html .= "      <td>".$ED['descripcion']." </td>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <a href=\"#\" onclick=\"xajax_Borrar_ProfesionalESM('".$esm_empresas."','".$ED['tipo_id_tercero']."','".$ED['tercero_id']."')\">\n";
	$html .= "          <img title=\"ASIGNAR\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">\n";
	// Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
	$html .= "        </a>\n";
	$html .= "      </td>\n";

	$html .= "    </tr>\n";
	}
	$html .= "<center>";



	$html .= "    </table>\n";
	$html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado_ProfesionalesEnEsm","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  function Asignar_ProfesionalESM($esm_empresas,$tipo_id_tercero,$tercero_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_Medico_IPS","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Insertar_ProfesionalESM($esm_empresas,$tipo_id_tercero,$tercero_id);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,'1');");
  $objResponse->script("xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,'1');");
  $objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
   function Borrar_ProfesionalESM($esm_empresas,$tipo_id_tercero,$tercero_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_Medico_IPS","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Borrar_ProfesionalESM($esm_empresas,$tipo_id_tercero,$tercero_id);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,'1');");
  $objResponse->script("xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,'1');");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
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
        
    $objResponse->script("xajax_Listado_TiposFuerzas('','','1');");
    return $objResponse;	
	}
  
  
?>
