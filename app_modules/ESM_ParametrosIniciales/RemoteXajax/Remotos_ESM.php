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
    
  function Listado_Terceros($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
  {
	$objResponse = new xajaxResponse();
	$sql = AutoCarga::factory("Consultas_ESM","classes","app","ESM_ParametrosIniciales");

	$Terceros=$sql->Listar_Terceros($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset);

	$action['paginador'] = "Paginador('".$tipo_id_tercero."','".$tercero_id."','".$nombre_tercero."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:80%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">TERCEROS</legend>\n";

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
	$html .= "        <a href=\"#\" onclick=\"xajax_Asignar_TerceroESM('".$ED['tipo_id_tercero']."','".$ED['tercero_id']."')\">\n";
	$html .= "          <img title=\"ASIGNAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
	// Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
	$html .= "        </a>\n";
	$html .= "      </td>\n";

	$html .= "    </tr>\n";
	}
	$html .= "<center>";



	$html .= "    </table>\n";
	$html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado_Terceros","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  function Asignar_TerceroESM($tipo_id_tercero,$tercero_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Insertar_TerceroESM($tipo_id_tercero,$tercero_id);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_Terceros(document.getElementById('tipo_id_tercero').value,document.getElementById('tercero_id').value,document.getElementById('nombre_tercero').value,'1');");
  $objResponse->script("xajax_Listado_ESM(document.getElementById('esm_tipo_id_tercero').value,document.getElementById('esm_tercero_id').value,document.getElementById('esm_nombre_tercero').value,'1');");
  $objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
   function Borrar_TerceroESM($tipo_id_tercero,$tercero_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ESM","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Borrar_TerceroESM($tipo_id_tercero,$tercero_id);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_Terceros(document.getElementById('tipo_id_tercero').value,document.getElementById('tercero_id').value,document.getElementById('nombre_tercero').value,'1');");
  $objResponse->script("xajax_Listado_ESM(document.getElementById('esm_tipo_id_tercero').value,document.getElementById('esm_tercero_id').value,document.getElementById('esm_nombre_tercero').value,'1');");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_ESM($tipo_id_tercero,$tercero_id,$nombre_tercero,$offset)
  {
	$objResponse = new xajaxResponse();
	$sql = AutoCarga::factory("Consultas_ESM","classes","app","ESM_ParametrosIniciales");

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
