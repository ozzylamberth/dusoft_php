<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: CodificacionRemotos.php,v 1.14 2009/12/23 18:50:08 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.14 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */
/*
 * Funcion Que Refrescará el listado de Laboratorios a desplegar en la pagina.
 */
function GruposT() {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $Grupos = $sql->ListadoGrupos();


    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">CLASIFICACION DE PRODUCTOS</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"5%\">CODIGO</td>\n";
    $html .= "      <td width=\"50%\">NOMBRE DEL GRUPO</td>\n";
    $html .= "      <td width=\"2%\">MEDICAMENTOS</td>\n";
    $html .= "      <td width=\"3%\">CLASES/SUBCLASES</td>\n";
    $html .= "      <td width=\"3%\">MOD</td>\n";
    $html .= "      <td width=\"3%\">SUPR</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($Grupos as $key => $grp) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $grp['grupo_id'] . "</td><td>" . $grp['descripcion'] . " </td>\n";
        $html .= "      </td>";

        if ($grp['sw_medicamento'] == 1)
            $html .= "<td align=\"center\"><img title=\"GRUPO PARA MEDICAMENTOS\" src=\"" . GetThemePath() . "/images/si.png\" border=\"0\"></td>\n";
        else
            $html .= "<td align=\"center\"><img title=\"GRUPO DIFERENTE A MEDICAMENTOS\" src=\"" . GetThemePath() . "/images/no.png\" border=\"0\"></td>\n";


        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ClasesSubclases('" . $grp['grupo_id'] . "','" . $grp['descripcion'] . "','" . $grp['sw_medicamento'] . "')\">\n";
        $html .= "          <img title=\"Ver Clases/SubClases\" src=\"" . GetThemePath() . "/images/asignacion_citas.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";


        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ModificarGrupo('" . $grp['grupo_id'] . "')\">\n";
        $html .= "          <img title=\"MODIFICAR\" src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrar('inv_grupos_inventarios','" . $grp['grupo_id'] . "','grupo_id','" . $grp['descripcion'] . "','" . $grp['descripcion'] . "')\">\n";
        $html .= "          <img title=\"BORRAR\" src=\"" . GetThemePath() . "/images/delete.gif\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset><br>\n";

    $objResponse->assign("Listado", "innerHTML", $html);
    return $objResponse;
}

/*
 * Funcion que depliega la capita para ver/adicionar las clases y subclases
 * a un Grupo en particular
 * @param String $CodigoGrupo
 * @return String
 */

function Subclases($CodigoGrupo, $CodigoClase, $NombreClase, $NombreGrupo, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    $SubClasesSinClases = $sql->ListadoSubClasesSinClase($CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset);

    /*
     * Select para que el Usuario Pueda elegir la clase y/o laboratorio que desee
     */
    $select .= "<select name=\"molecula_id\" id=\"molecula_id\" class=\"select\" style=\"width:100%;height:100%\">";
    $select .= "<option value=\"\">";
    $select .= "</option>";
    foreach ($SubClasesSinClases as $key => $subsin) {
        $select .= "<option value=\"" . $subsin['molecula_id'] . "\" onclick=\"AsignarCampoMolecula('" . $subsin['molecula_id'] . "','" . $subsin['descripcion'] . "');\" >";
        $select .= $subsin['descripcion'] . "-" . $subsin['molecula_id'];
        $select .= "</option>";
    }
    $select .= "</select>";


    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">CLASE: " . $NombreClase . " - GRUPO: " . $NombreGrupo . "</legend>\n";
    /*
     * Formulario para el Ingreso de Subclases a un Grupo y Clase
     */

    $html .= "<form name=\"form_clase\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";

    $html .= "<tr>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo SubClase :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"subclase\" maxlength=\"7\" >";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Descripcion :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion_subclase\" maxlength=\"80\" >";
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Seleccione Molecula :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "" . $select; // document.getElementById('descripcion_clase').value document.getElementById('sw_medicamento').value
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" colspan=\"2\">"; //                                                    $Grupo_id,       $Clase_id,                  $NombreClase,       $NombreGrupo,     $SubClase_id,                 $DescripcionSubClase,$MoleculaId,$Sw_Medicamento,$offset
    //                $CodigoGrupo,$CodigoClase,$NombreClase,$NombreGrupo,$Sw_Medicamento
    $html .= "<input type=\"button\" class=\"modulo_table_list\" value=\"Guardar\" onclick=\"xajax_AsignarSubclaseAClase('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "',document.getElementById('subclase').value,document.getElementById('descripcion_subclase').value,document.getElementById('molecula_id').value,'" . $Sw_Medicamento . "');\">";
    $html .= "</td>";
    $html .= "</tr>";

    $html .="</table>
            </form>";

    $html .= "</fieldset>\n";


    $html .= "     <center><a href=\"#\" class=\"label_error\" onclick=\"xajax_AsignarClases('" . $CodigoGrupo . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')\">\n";
    $html .= "          <-- ATRAS::]\n";
    // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
    $html .= "        </a></center>\n";


    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"4\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion\" onkeyup=\"this.value=this.value.toUpperCase(),BusquedaL();\">";
    $html .= "</td>";

    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"subclase_id\" maxlength=\"4\" onkeyup=\"this.value=this.value.toUpperCase(),BusquedaL();\" >";
    $html .= "<input type=\"hidden\" id=\"GrupoId\" value='" . $CodigoGrupo . "'>";
    $html .= "<input type=\"hidden\" id=\"NombreGrupo\" value='" . $NombreGrupo . "'>";
    $html .= "<input type=\"hidden\" id=\"sw_medicamento\" value='" . $Sw_Medicamento . "'>";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" colspan=\"4\">";                                                     //$CodigoGrupo,     $CodigoClase,                               $NombreClase,                                 $Sw_Medicamento,      $NombreGrupo,$offset
    $html .= "<input type=\"button\" class=\"modulo_table_list\" value=\"Buscar\" onclick=\"xajax_BusquedaSubClasesAsignadas('" . $NombreGrupo . "','" . $NombreClase . "','" . $CodigoGrupo . "','" . $CodigoClase . "',document.getElementById('descripcion').value,document.getElementById('subclase_id').value,'" . $Sw_Medicamento . "');\">";
    $html .= "</td>";
    $html .= "</tr>";
    $html .="</table>";

    $html .= "<div id=\"listado_SubclasesAsignadas\">";
    $html .= "</div>";


    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    //$NombreGrupo,$NombreClase,$CodigoGrupo,$CodigoClase,'','',$Sw_Medicamento,'1'
    $objResponse->script("xajax_BusquedaSubClasesAsignadas('" . $NombreGrupo . "','" . $NombreClase . "','" . $CodigoGrupo . "','" . $CodigoClase . "','','','" . $Sw_Medicamento . "','1');");
    return $objResponse;
}

/*
 * Funcion que Adiciona mediante Ajax, Clases
 * a un Grupo en particular
 * @param String $Formulario
 * @return String
 */

function AsignarSubclaseAClase($Grupo_id, $Clase_id, $NombreClase, $NombreGrupo, $SubClase_id, $DescripcionSubClase, $MoleculaId, $Sw_Medicamento, $offset) {
    $CodigoGrupo = $Grupo_id;
    $CodigoClase = $Clase_id;
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");


    if ($SubClase_id != "" && $DescripcionSubClase != "") {
        $Token = $sql->Insertar_SubClaseAClase($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId);
        
        $Token_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId);
        $token_1_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, '1');
        $token_2_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, '2');
        $token_3_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, '3');
        $token_4_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, '4');
		$token_5_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, '5');
		$token_6_ws = $sql->Insertar_SubClaseAClase_WS($Grupo_id, $Clase_id, $SubClase_id, $DescripcionSubClase, $MoleculaId, '6');
    }
    else
        $Token = 0;


    if ($Token) {//                                   $CodigoGrupo,$CodigoClase,$NombreClase,$NombreGrupo,$Sw_Medicamento
        $objResponse->script("xajax_Subclases('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')");
    }
    else
        $objResponse->alert("ERROR AL INGRESAR!!!");


    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaSubClasesAsignadas($NombreGrupo, $NombreClase, $CodigoGrupo, $CodigoClase, $Nombre, $Codigo, $Sw_Medicamento, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    $SubClasesConClases = $sql->BuscarSubClasesConClase($Nombre, $Codigo, $CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset);
    $action['paginador'] = "paginador_busquedasAsing('" . $NombreGrupo . "','" . $NombreClase . "','" . $CodigoGrupo . "','" . $CodigoClase . "','" . $Nombre . "','" . $Codigo . "','" . $Sw_Medicamento . "'";

    //PARA ASIGNAR A SELECTCLASES
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">SUBCLASES ASIGNADAS A " . $NombreClase . " del Grupo " . $NombreGrupo . "</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"3%\">CODIGO</td>\n";
    $html .= "      <td width=\"15%\">NOMBRE DE LA SUBCLASE</td>\n";
    $html .= "      <td width=\"3%\">SUPR</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($SubClasesConClases as $key => $subclases) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $subclases['subclase_id'] . "</td><td>" . $subclases['descripcion'] . " </td>\n";
        $html .= "      </td>";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrarSubClase('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "','" . $subclases['subclase_id'] . "','" . $subclases['descripcion'] . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"BORRAR\" src=\"" . GetThemePath() . "/images/delete.gif\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset>\n";



    $objResponse->assign("listado_SubclasesAsignadas", "innerHTML", $html);
    return $objResponse;
}

/*
  Funcion Xajax para borrar Clases
 */

function BorrarSubClases($Grupo_id, $Clase_id, $NombreClase, $NombreGrupo, $SubClase_id, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();
    $CodigoGrupo = $Grupo_id;
    $CodigoClase = $Clase_id;


    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $Token = $sql->Borrar_SubClase($Grupo_id, $Clase_id, $SubClase_id);

    $token_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id);
    $token_1_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id, '1');
    $token_2_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id, '2');
    $token_3_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id, '3');
    $token_4_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id, '4');
	$token_5_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id, '5');
	$token_6_ws = $sql->Borrar_SubClase_WS($Grupo_id, $Clase_id, $SubClase_id, '6');
    //$objResponse->alert($SubClase_id." y ".$Clase_id);

    if ($Token) {
         
        $objResponse->script("xajax_Subclases('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')");
    }
    else
        $objResponse->alert("ERROR: Revisa si la Sub Clase No esta asociado a \n 1) (Al Menos) Un Producto");

    return $objResponse;
}

/*
 * Funcion que Adiciona mediante Ajax, Clases
 * a un Grupo en particular
 * @param String $Formulario
 * @return String
 */

function AsignarClases($CodigoGrupo, $NombreGrupo, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    $ClasesSinGrupo = $sql->ListadoClasesSinGrupo($CodigoGrupo);

    /*
     * Select para que el Usuario Pueda elegir la clase y/o laboratorio que desee
     */
    $select .= "<select name=\"laboratorio_id\" id=\"laboratorio_id\" class=\"select\" style=\"width:100%;height:100%\">";
    foreach ($ClasesSinGrupo as $key => $cla) {
        $select .= "<option value=\"" . $cla['laboratorio_id'] . "\" onclick=\"Asignar('" . $cla['laboratorio_id'] . "','" . $cla['descripcion'] . "');\" >";
        $select .= $cla['descripcion'] . "-" . $cla['laboratorio_id'];
        $select .= "</option>";
    }
    $select .= "</select>";


    //SessionGetVar("sw_tipo_empresa")
    /*
     * Formulario para el Ingreso de Clases a un grupo Seleccionado anteriormente
     */
    $html .= "<form name=\"form_clase\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" colspan=\"2\" align=\"center\">";
    $html .= "CREAR CLASE PARA " . $NombreGrupo;
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo Clase :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"clase\" maxlength=\"7\" >";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Descripcion :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion_clase\" maxlength=\"80\" >";
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr>";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Seleccione Laboratorio :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "" . $select; // document.getElementById('descripcion_clase').value document.getElementById('sw_medicamento').value
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" colspan=\"2\">";                                                 //$Grupo_id,      $ClaseId,                               $Descripcion,                               $LaboratorioId,                                       $SW_MEdicamento
    $html .= "<input type=\"button\" class=\"modulo_table_list\" value=\"Guardar\" onclick=\"xajax_AsignarClaseAGrupo('" . $CodigoGrupo . "',document.getElementById('clase').value,document.getElementById('descripcion_clase').value,document.getElementById('laboratorio_id').value,document.getElementById('sw_medicamento').value,document.getElementById('NombreGrupo').value);\">";
    $html .= "</td>";
    $html .= "</tr>";

    $html .="</table>
            </form>";


    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"4\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion\" onkeyup=\"this.value=this.value.toUpperCase(),BusquedaL();\">";
    $html .= "</td>";

    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"clase_id\" maxlength=\"4\" onkeyup=\"this.value=this.value.toUpperCase(),BusquedaL();\" >";
    $html .= "<input type=\"hidden\" id=\"GrupoId\" value='" . $CodigoGrupo . "'>";
    $html .= "<input type=\"hidden\" id=\"NombreGrupo\" value='" . $NombreGrupo . "'>";
    $html .= "<input type=\"hidden\" id=\"sw_medicamento\" value='" . $Sw_Medicamento . "'>";
    $html .= "</td>";
    $html .= "</tr>";
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" colspan=\"4\">";                                                     //$CodigoGrupo,     $CodigoClase,                               $NombreClase,                                 $Sw_Medicamento,      $NombreGrupo,$offset
    $html .= "<input type=\"button\" class=\"modulo_table_list\" value=\"Buscar\" onclick=\"xajax_BusquedaClasesAsignadas('" . $CodigoGrupo . "',document.getElementById('clase_id').value,document.getElementById('descripcion').value,'" . $Sw_Medicamento . "','" . $NombreGrupo . "');\">";
    $html .= "</td>";
    $html .= "</tr>";
    $html .="</table>";

    $html .= "<div id=\"listado_clases_asignadas\">";
    $html .= "</div>";
    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    $objResponse->script("xajax_BusquedaClasesAsignadas('" . $CodigoGrupo . "','','','" . $Sw_Medicamento . "','" . $NombreGrupo . "','1');");
    return $objResponse;
}

/*
 * Funcion que Adiciona mediante Ajax, Clases
 * a un Grupo en particular
 * @param String $Formulario
 * @return String
 */

function AsignarClaseAGrupo($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, $Sw_Medicamento, $NombreGrupo) {
    $CodigoGrupo = $Grupo_id;
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    if ($ClaseId != "" && $Descripcion != "") {
        $Token = $sql->Insertar_ClasesAGrupo($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId);

        $token_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId);
        $token_1_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, '1');
        $token_2_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, '2');
        $token_3_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, '3');
        $token_4_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, '4');
		$token_5_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, '5');
		$token_6_ws = $sql->Insertar_ClasesAGrupo_WS($Grupo_id, $ClaseId, $Descripcion, $LaboratorioId, '6');
        //$objResponse->alert($ClaseId);
    } else {
        $Token = 0;
    }


    if ($Token) {
        $objResponse->script("xajax_AsignarClases('" . $Grupo_id . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "');");
    }
    else
        $objResponse->alert("ERROR AL INGRESAR!!!");



    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaClasesAsignadas($CodigoGrupo, $CodigoClase, $NombreClase, $Sw_Medicamento, $NombreGrupo, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $ClasesxGrupo = $sql->BuscarClasesAsignadas($CodigoGrupo, $CodigoClase, $NombreClase, $offset);

    $action['paginador'] = "Buscador__('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "'";

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    //PARA ASIGNAR A LISTADO CLASES

    $html .= "  <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"5%\">CODIGO</td>\n";
    $html .= "      <td width=\"50%\">NOMBRE DE LA CLASE</td>\n";
    $html .= "      <td width=\"3%\">SUBCLASES</td>\n";
    $html .= "      <td width=\"3%\">SUPR</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($ClasesxGrupo as $key => $clases) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $clases['clase_id'] . "</td><td>" . $clases['descripcion'] . " </td>\n";
        $html .= "      </td>";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_Subclases('" . $CodigoGrupo . "','" . $clases['clase_id'] . "','" . $clases['descripcion'] . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"Ver SubClases\" src=\"" . GetThemePath() . "/images/asignacion_citas.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrarClase('" . $CodigoGrupo . "','" . $clases['clase_id'] . "','" . $clases['descripcion'] . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"BORRAR\" src=\"" . GetThemePath() . "/images/delete.gif\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }
    //print_r($ClasesxGrupo);


    $html .= "    </table>\n";

    $objResponse->assign("listado_clases_asignadas", "innerHTML", $html);
    return $objResponse;
}

/*
  Funcion Xajax para borrar Clases
 */

function BorrarClases($Grupo_id, $Clase_Id, $descripcion, $NombreGrupo, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    $CodigoGrupo = $Grupo_id;
    $Token = $sql->Borrar_Clase($Grupo_id, $Clase_Id);

    $token_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id);
    $token_1_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id, '1');
    $token_2_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id, '2');
    $token_3_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id, '3');
    $token_4_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id, '4');
	$token_5_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id, '5');
	$token_6_WS = $sql->Borrar_Clase_WS($Grupo_id, $Clase_Id, '6');

    if ($Token) {//AsignarClases($CodigoGrupo,$NombreGrupo,$Sw_Medicamento)
        $objResponse->script("xajax_AsignarClases('" . $Grupo_id . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "');");
    }
    else
        $objResponse->alert("ERROR: Revisa si la Clase No esta asociado a \n 1) (Al Menos) Una Subclase \n 2) A Productos");

    return $objResponse;
}

/*
 * Funcion que depliega la capita para ver/adicionar las clases y subclases
 * a un Grupo en particular
 * @param String $CodigoGrupo
 * @return String
 */

function ClasesSubclases($CodigoGrupo, $NombreGrupo, $Sw_Medicamento, $offset) {


    $objResponse = new xajaxResponse();


    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"4\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Nombre :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"descripcion\" onkeyup=\"this.value=this.value.toUpperCase(),Busqueda();\">";

    $html .= "</td>";

    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"clase_id\" maxlength=\"7\" onkeyup=\"this.value=this.value.toUpperCase(),Busqueda();\" >";
    $html .= "</td>";
    $html .= "</tr>";

    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\" colspan=\"2\">";
    $html .= "Buscar en :";
    $html .= "</td>";
    $html .= "<td colspan=\"2\">";
    $html .= "<input type=\"hidden\" name=\"GrupoId\" value='" . $CodigoGrupo . "'>";
    $html .= "<input type=\"hidden\" name=\"NombreGrupo\" value='" . $NombreGrupo . "'>";
    $html .= "<input type=\"hidden\" name=\"sw_medicamento\" value='" . $Sw_Medicamento . "'>";
    $html .= "<SELECT NAME=\"buscar_en\" SIZE=\"1\" class=\"input-text\">";
    $html .= "<option value=\"1\">Clases Asignadas</option>";
    // $html .= "<option value=\"2\">Clases Sin Asignar</option>";
    $html .= "</SELECT>";
    $html .= "</td>";
    $html .= "</tr>";


    $html .="</table>
            </form>
            ";

    //FIN BUSCADOR


    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");




    //Antes de Implementar el Paginador
    //$ClasesSinGrupo=$sql->ListadoClasesSinGrupo($CodigoGrupo);

    $ClasesxGrupo = $sql->ListadoClasesxGrupo($CodigoGrupo, $offset);

    //Listado de Clases asignadas a un grupo

    $html .= "<div id=\"ListadoClases\">";
    //4) Paso Paginador: Definir en el REQUEST el llamado al Paginador
    $action['paginador'] = "Buscador('" . $CodigoGrupo . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "'";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    $html .= "<fieldset class=\"fieldset\">\n";

    $html .= "  <legend class=\"normal_10AN\">CLASES ASIGNADAS A " . $NombreGrupo . "</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"5%\">CODIGO</td>\n";
    $html .= "      <td width=\"50%\">NOMBRE DE LA CLASE</td>\n";
    $html .= "      <td width=\"3%\">SUBCLASES</td>\n";
    $html .= "      <td width=\"3%\">SUPR</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($ClasesxGrupo as $key => $clases) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $clases['laboratorio_id'] . "</td><td>" . $clases['descripcion'] . " </td>\n";
        $html .= "      </td>";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_Subclases('" . $CodigoGrupo . "','" . $clases['laboratorio_id'] . "','" . $clases['descripcion'] . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "',' ')\">\n";
        $html .= "          <img title=\"Ver SubClases\" src=\"" . GetThemePath() . "/images/asignacion_citas.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrarClase('" . $CodigoGrupo . "','" . $clases['laboratorio_id'] . "','" . $clases['descripcion'] . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"BORRAR\" src=\"" . GetThemePath() . "/images/delete.gif\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset>\n";
    $html .= "</div>"; //FIN DIV LISTADO!!!








    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

/*
  Funcion Xajax para borrar Grupos
 */

function BorrarGrupo($tabla, $id, $campo_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.

    $sql_classes = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");


    $token = $sql->Borrar_Registro($tabla, $id, $campo_id);

    $token_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id);
    $token_1_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id, '1');
    $token_2_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id, '2');
    $token_3_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id, '3');
    $token_4_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id, '4');
	$token_5_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id, '5');
	$token_6_ws = $sql_classes->Borrar_Registro_WS($tabla, $id, $campo_id, '6');
	
    if ($token_ws) {
        $mensaje = "\nBorrado exitoso en Cosmitet.";
    } else {
        $mensaje = "\nError al Borrar en Cosmitet.";
    }

    if ($token_1_ws) {
        $mensaje .= "\nBorrado exitoso en Dumian.";
    } else {
        $mensaje .= "\nError al Borrar en Dumian.";
    }

    if ($token_2_ws) {
        $mensaje .= "\nBorrado exitoso en CSSP.";
    } else {
        $mensaje .= "\nError al Borrar en CSSP.";
    }
    
    if ($token_3_ws) {
        $mensaje .= "\nBorrado exitoso en MD.";
    } else {
        $mensaje .= "\nError al Borrar en MD.";
    }
    
    if ($token_4_ws) {
        $mensaje .= "\nBorrado exitoso en CMS.";
    } else {
        $mensaje .= "\nError al Borrar en CMS.";
    }
	
	if ($token_5_ws) {
        $mensaje .= "\nBorrado exitoso en PENITAS.";
    } else {
        $mensaje .= "\nError al Borrar en PENITAS.";
    }
	
	if ($token_6_ws) {
        $mensaje .= "\nBorrado exitoso en CARTAGENA.";
    } else {
        $mensaje .= "\nError al Borrar en CARTAGENA.";
    }

    $objResponse->call("xajax_GruposT");
    if ($token)
        $objResponse->alert("Borrado Exitoso" . $mensaje);
    else
        $objResponse->alert("ERROR: Revisa si el grupo No esta asociado a \n 1) Una Clase \n 2) Una Subclase \n 3) A Productos");



    return $objResponse;
}

/*
  Funcion Xajax para Modificar Laboratorios
 */

function GuardarModGrupo($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Modificar_Grupo($datos);

    $token_ws = $sql->Modificar_Grupo_WS($datos);
    $token_1_ws = $sql->Modificar_Grupo_WS($datos, '1');
    $token_2_ws = $sql->Modificar_Grupo_WS($datos, '2');
    $token_3_ws = $sql->Modificar_Grupo_WS($datos, '3');
    $token_4_ws = $sql->Modificar_Grupo_WS($datos, '4');
	$token_5_ws = $sql->Modificar_Grupo_WS($datos, '5');
	$token_6_ws = $sql->Modificar_Grupo_WS($datos, '6');
	
    if ($token_ws) {
        $mensaje = "\nModificacion Exitosa en Cosmitet.";
    } else {
        $mensaje = "\nError al modificar en Cosmitet.";
    }

    if ($token_1_ws) {
        $mensaje .= "\nModificacion Exitosa en Dumian.";
    } else {
        $mensaje .= "\nError al modificar en Dumian.";
    }
    
    if ($token_2_ws) {
        $mensaje .= "\nModificacion Exitosa en CSSP.";
    } else {
        $mensaje .= "\nError al modificar en CSSP.";
    }
    
    if ($token_3_ws) {
        $mensaje .= "\nModificacion Exitosa en MD.";
    } else {
        $mensaje .= "\nError al modificar en MD.";
    }
    
    if ($token_4_ws) {
        $mensaje .= "\nModificacion Exitosa en CMS.";
    } else {
        $mensaje .= "\nError al modificar en CMS.";
    }
	
	if ($token_5_ws) {
        $mensaje .= "\nModificacion Exitosa en PENITAS.";
    } else {
        $mensaje .= "\nError al modificar en PENITAS.";
    }
	
	if ($token_6_ws) {
        $mensaje .= "\nModificacion Exitosa en CARTAGENA.";
    } else {
        $mensaje .= "\nError al modificar en CARTAGENA.";
    }
	
    if ($token) {
        $objResponse->call("xajax_GruposT()");
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->alert("Modificacion Exitosa!!" . $mensaje);
    }
    else
        $objResponse->alert("Error al Modificar!!!");
    //$objResponse->call("xajax_ModificarLaboratorio(".$datos['laboratorio_id'].")");
    $objResponse->assign("Listado", "innerHTML", $html);  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;
}

/*
 * Capita del Formulario de Modificacion de Laboratorios
 */

function ModificarGrupo($CodigoGrupo) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $Grupo = $sql->BuscarGrupo($CodigoGrupo);

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioGrupo\" id=\"FormularioGrupo\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Creacion de Grupo";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo del Grupo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-text" type="Text" name="grupo_id" readOnly="true" maxlength="2" size="2" value="' . $Grupo[0]['grupo_id'] . '">';
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Nombre del Grupo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-text" type="Text" name="descripcion" value="' . $Grupo[0]['descripcion'] . '" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';


    /*
     * te campo, es para que cuando se va a crear un grupo, 
     * defina si son medicamentos los que estaran asociados
     * a ese grupo específico o es cualquier Insumo (como 
     * dispositivos medicos etc...) que no posee en su codigo, 
     * una molécula, unica en los medicamentos.
     */
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Productos Tipo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";

    if ($Grupo[0]['sw_medicamento'] == '1') {
        $html .= '      <input class="input-radio" type="radio" name="sw_medicamento" checked value="1" disabled="true"> Medicamentos <br>
                    <input class="input-radio" type="radio" name="sw_medicamento" value="0" disabled="true"> Insumos';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_medicamento" value="1" disabled="true"> Medicamentos <br>
                    <input class="input-radio" type="radio" name="sw_medicamento" checked value="0" disabled="true"> Insumos';
    }
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" maxlength="20" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"ValidarIngresoGrupo(xajax.getFormValues('FormularioGrupo'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";



    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

/*
 * Funcion que permite el Ingreso de Grupos a la base de datos
 * @param Array
 * @return array.
 */

function InsertarGrupo($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Insertar_Grupo($datos);

    $token_ws = $sql->Insertar_Grupo_WS($datos);
    $token_1_ws = $sql->Insertar_Grupo_WS($datos, '1');
    $token_2_ws = $sql->Insertar_Grupo_WS($datos, '2');
    $token_3_ws = $sql->Insertar_Grupo_WS($datos, '3');
    $token_4_ws = $sql->Insertar_Grupo_WS($datos, '4');
	$token_5_ws = $sql->Insertar_Grupo_WS($datos, '5');
	$token_6_ws = $sql->Insertar_Grupo_WS($datos, '6');

    if ($token_ws) {
        $mensaje = "\nIngreso Existoso en Cosmitet.";
    } else {
        $mensaje = "\nError en el ingreso en Cosmitet.";
    }

    if ($token_1_ws) {
        $mensaje .= "\nIngreso Existoso en Dumian.";
    } else {
        $mensaje .= "\nError en el ingreso en Dumian.";
    }

    if ($token_2_ws) {
        $mensaje .= "\nIngreso Existoso en CSSP.";
    } else {
        $mensaje .= "\nError en el ingreso en CSSP.";
    }

    if ($token_3_ws) {
        $mensaje .= "\nIngreso Existoso en MD.";
    } else {
        $mensaje .= "\nError en el ingreso en MD.";
    }
    
    if ($token_4_ws) {
        $mensaje .= "\nIngreso Existoso en CMS.";
    } else {
        $mensaje .= "\nError en el ingreso en CMS.";
    }
	
	if ($token_5_ws) {
        $mensaje .= "\nIngreso Existoso en PENITAS.";
    } else {
        $mensaje .= "\nError en el ingreso en PENITAS.";
    }
	
	if ($token_6_ws) {
        $mensaje .= "\nIngreso Existoso en CARTAGENA.";
    } else {
        $mensaje .= "\nError en el ingreso en CARTAGENA.";
    }

    //$objResponse->alert("SQL: ".$token);

    if ($token) {
        $url = ModuloGetURL('app', 'Inv_CodificacionProductos', 'controller', 'Clasificacion_Productos') . "&datos[sw_tipo_empresa]=" . $_REQUEST['datos']['sw_tipo_empresa'] . "&datos[empresa_id]=" . $_REQUEST['datos']['empresa_id'] . "";
        $objResponse->script('
					 window.location="' . $url . '";
								');
        //$objResponse->call("xajax_GruposT()");
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->alert("Ingreso Exitoso!!" . $mensaje);
        $objResponse->assign("Listado", "innerHTML", $html);  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    }
    else
        $objResponse->alert("Error en el Ingreso... Revisa que no exista el Grupo!!");

    return $objResponse;
}

/*
 * Capita del Formulario de Ingreso de Grupos
 */

function IngresoGrupo() {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");


    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioGrupo\" id=\"FormularioGrupo\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Creacion de Grupo";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo del Grupo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-text" type="Text" name="grupo_id" maxlength="2" size="2" onkeyup="this.value=this.value.toUpperCase()">';
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Nombre del Grupo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';


    /*
     * te campo, es para que cuando se va a crear un grupo, 
     * defina si son medicamentos los que estaran asociados
     * a ese grupo específico o es cualquier Insumo (como 
     * dispositivos medicos etc...) que no posee en su codigo, 
     * una molécula, unica en los medicamentos.
     */
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Productos Tipo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_medicamento" value="1"> Medicamentos <br>
                    <input class="input-radio" type="radio" name="sw_medicamento" value="0"> Insumos </td>';

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" maxlength="20" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"ValidarIngresoGrupo(xajax.getFormValues('FormularioGrupo'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";



    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

?>
