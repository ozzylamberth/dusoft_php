<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos_Afiliado_FUERZAS.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja
  */
 
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_ProfesionalesSinEsm($tipo_fuerza,$nombre,$apellido,$identificacion,$tipo,$offset)
  {
	$objResponse = new xajaxResponse();
  
	$sql = AutoCarga::factory("Consultas_Afiliados_FUERZAS","classes","app","ESM_ParametrosIniciales");

	$Afiliados=$sql->Listado_ProfesionalesSinEsm($tipo_fuerza,$nombre,$apellido,$identificacion,$tipo,$offset);
   // print_r($Afiliados);
	$action['paginador'] = "Paginador('".$tipo_fuerza."','".$nombre."','".$apellido."','".$identificacion."','".$tipo."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:60%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">AFILIADOS NO ASIGNADOS A UNA FUERZA SELECCIONADA</legend>\n";

	$html .= "  <table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
	$html .= "      <td width=\"50%\">NOMBRE</td>\n";
    $html .= "      <td width=\"7%\">SELECCIONAR</td>\n";


	$html .= "    </tr>\n";

	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
	foreach($Afiliados as $key => $ED)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

	$html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
	$html .= "      <td>".$ED['identificacion']."</td>\n";
	$html .= "      <td>".$ED['nombre']." </td>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <a href=\"#\" onclick=\"xajax_Asignar_ProfesionalESM('".$tipo_fuerza."','".$ED['afiliado_tipo_id']."','".$ED['afiliado_id']."')\">\n";
	$html .= "          <img title=\"NO PERTENECE A LA FUERZA\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
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
    
  function Listado_ProfesionalesEnEsm($tipo_fuerza,$nombre,$apellido,$identificacion,$tipo,$offset)
  {
	$objResponse = new xajaxResponse();
 
	$sql = AutoCarga::factory("Consultas_Afiliados_FUERZAS","classes","app","ESM_ParametrosIniciales");

	$afiliados=$sql->Listado_ProfesionalesEnEsm($tipo_fuerza,$nombre,$apellido,$identificacion,$tipo,$offset);
   
	$action['paginador'] = "Paginador_('".$tipo_fuerza."','".$nombre."','".$apellido."','".$identificacion."','".$tipo."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:80%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">AFILIADOS ASIGNADOS A LA ESM SELECCIONADA</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"15%\">IDENTIFICACION</td>\n";
	$html .= "      <td width=\"50%\">NOMBRE</td>\n";
	$html .= "      <td width=\"7%\">SELECCIONAR</td>\n";


	$html .= "    </tr>\n";

	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
	foreach($afiliados as $key => $ED)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

	$html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
	$html .= "      <td>".$ED['identificacion']."</td>\n";
	$html .= "      <td>".$ED['nombre']." </td>\n";
	$html .= "      <td align=\"center\">\n";
	$html .= "        <a href=\"#\" onclick=\"xajax_Borrar_ProfesionalESM('".$tipo_fuerza."','".$ED['afiliado_tipo_id']."','".$ED['afiliado_id']."')\">\n";
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
  
  function Asignar_ProfesionalESM($tipo_fuerza,$tipo_id_tercero,$tercero_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_Afiliados_FUERZAS","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Insertar_ProfesionalESM($tipo_fuerza,$tipo_id_tercero,$tercero_id);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
  $objResponse->script("xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,document.getElementById('apellidos').value,document.getElementById('identificacion').value,document.getElementById('tipo').value,'1');");
  $objResponse->script("xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,document.getElementById('apellidos_').value,document.getElementById('identificacion_').value,document.getElementById('tipo_').value,'1');");
  $objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
  
   function Borrar_ProfesionalESM($tipo_fuerza,$tipo_id_tercero,$tercero_id)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_Afiliados_FUERZAS","classes","app","ESM_ParametrosIniciales");
  
  $token=$sql->Borrar_ProfesionalESM($tipo_fuerza,$tipo_id_tercero,$tercero_id);
  
  if($token)
  {
  $objResponse->script("Cerrar('Contenedor');");
   $objResponse->script("xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,document.getElementById('apellidos').value,document.getElementById('identificacion').value,document.getElementById('tipo').value,'1');");
  $objResponse->script("xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,document.getElementById('apellidos_').value,document.getElementById('identificacion_').value,document.getElementById('tipo_').value,'1');");
  $objResponse->alert("Se Elimino Correctamente!!");
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
