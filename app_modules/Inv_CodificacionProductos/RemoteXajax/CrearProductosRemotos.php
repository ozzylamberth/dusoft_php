<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: CrearProductosRemotos.php,v 1.12 2010/01/26 18:17:44 sandra Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.12 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */
/*
 * Funcion Que Refrescar� el listado de Laboratorios a desplegar en la pagina.
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

function Productos_Creados($param, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $productos = $sql->Lista_Productos_Creados($offset);

    $action['paginador'] = "Paginador_6('" . $param . "'";

    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">PRODUCTOS</legend>\n";

    $html .= "  <br>";
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "      <tr>";
    $html .= "          <td style='background-color:#52955C'>";
    $html .= "          </td>";
    $html .= "          <td> = El Producto posee Factor de Conversion";
    $html .= "          </td>";
    $html .= "      </tr>";
    $html .= "  </table>";
    $html .= "  <br>";


    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"10%\">CODIGO-PRODUCTO</td>\n";
    $html .= "      <td width=\"5%\">GRUPO</td>\n";
    $html .= "      <td width=\"10%\">CLASE</td>\n";
    $html .= "      <td width=\"30%\">DESCRIPCION</td>\n";
    $html .= "      <td width=\"10%\">Princ.Activo/SubClase</td>\n";
    $html .= "      <td width=\"10%\">IVA</td>\n";
    $html .= "      <td width=\"10%\">MDTO</td>\n";
    $html .= "      <td width=\"10%\">REGULADO</td>\n";
    $html .= "      <td width=\"10%\">MODIFICAR</td>\n";
    $html .= "      <td width=\"10%\">FACTOR DE CONVERSI�N</td>\n";
    $html .= "      <td width=\"10%\">ESTADO</td>\n";
    //$html .= "      <td width=\"10%\">BORRAR</td>\n";


    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($productos as $key => $prod) {

        $factor_conversion = $sql->Consulta_Factor_Conversion($prod['codigo_producto']);

        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td>" . $prod['codigo_producto'] . " </td>";
        $html .= "      <td >" . $prod['grupo'] . "</td><td>" . $prod['clase'] . " </td>\n";


        // $html .= "      <td>".$prod['descripcion']." ".$prod['presentacion']." </td>";
        $html .= "      <td>" . $prod['descripcion'] . " " . $prod['presentacion'] . " | " . $prod['presentacion_comercial'] . " </td>";
        $html .= "      <td>" . $prod['subclase'] . " </td>";
        $html .= "      <td>" . $prod['iva'] . " </td>";
        if ($prod['sw_medicamento'] == 1)
            $html .= "<td align=\"center\"><img title=\"MEDICAMENTO\" src=\"" . GetThemePath() . "/images/si.png\" border=\"0\"></td>\n";
        else
            $html .= "<td align=\"center\"><img title=\"INSUMO\" src=\"" . GetThemePath() . "/images/no.png\" border=\"0\"></td>\n";


        $html .= "<td> {$prod['producto_regulado']} </td>";
        $html .= "  <td align=\"center\">\n";
        $html .= "      <a href=\"#\" onclick=\"xajax_ModProducto('" . $prod['codigo_producto'] . "','" . $prod['sw_medicamento'] . "')\">\n";
        $html .= "        <img title=\"Modificar\" src=\"" . GetThemePath() . "/images/asignacion_citas.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "      </a>\n";
        $html .= "  </td>\n";



        $css_color_fondo = $est;
        //$tiene_factor = "";   
        if ($factor_conversion) {
            $css_color_fondo = "#52955C";
            //$tiene_factor = "<img title=\"Tiene Factor\" src=\"".GetThemePath()."/images/si.png\" border=\"0\">";
        }

        //<Inicia aqui>
        $html .= "  <td align=\"center\" style='background-color:$css_color_fondo'>\n";
        $javaAccionSuministros = " MostrarCapa('ContenedorFactorConversion'); IniciarCapaSum('CREAR FACTOR DE CONVERSI�N','ContenedorFactorConversion');CargarContenedor('ContenedorFactorConversion'); xajax_MostrarMedicamentosF('$prod[codigo_producto]');";
        $html .="  <input type = 'button' class=\"input-submit\" value = '...' onclick = \" $javaAccionSuministros;\">";
        $html .= "      </a>\n";
        //$html .= "    $tiene_factor";
        $html .= "  </td>\n";
        //<Termina aqui>



        if ($prod['estado'] == 1) {
            $html .= "<td align=\"center\">
          <a href=\"#\" onclick=\"xajax_CambioEstadoProducto('inventarios_productos','estado','0','" . $prod['codigo_producto'] . "','codigo_producto')\">\n";
            $html .="<img title=\"INACTIVAR\" src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\"></a></td>\n";
        } else {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoProducto('inventarios_productos','estado','1','" . $prod['codigo_producto'] . "','codigo_producto')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a></td>\n";
        }

        /* $html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrar('inv_grupos_inventarios','".$grp['grupo_id']."','grupo_id','".$grp['descripcion']."')\">\n";
          $html .= "          <img title=\"BORRAR\" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\">\n";
          // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n"; */
    }

    $html .= "    </table>\n";

    //<Inicia aqui>
    $html .= "<div id='ContenedorFactorConversion' class='d2Container' style=\"display:none\"><br>";
    $html .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $html .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorFactorConversion');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $html .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
    $html .= "    <div id='DataMedicine' class='d2Content' style=\"height:330\">\n";
//  Aqui trae el contenido de la funci�n MostrarMedicamentosF
    $html .= "    </div>\n";
    $html .= "</div>\n";
    //<Termina aqui>

   

    $html .= "</fieldset><br>\n";
    $objResponse->assign("Listado_Productos", "innerHTML", $html);
    return $objResponse;
}

/*
  Funcion Xajax para Cambiar el estado de un registro
 */

function CambioEstadoProducto($tabla, $campo, $valor, $id, $campo_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla, $campo, $valor, $id, $campo_id);

    $consulta = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
    $consulta->Guardar_AuditoriaEstadoProducto($id, $valor);

    $objResponse->call("xajax_Productos_Creados");
    return $objResponse;
}

function borrar($Tabla, $Valor, $CodigoProducto, $campos) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->borrar($Tabla, $Valor, $CodigoProducto, $campos);
    return $objResponse;
}

/**
*+Descripcion: Metodo encargado de asignar los niveles de atencion invocando el la funcion asignar de
			   la clase ConsultasCrearProductos que ejecutara el WS
*
*/
function asignar($Tabla, $Valor, $CodigoProducto, $campos, $metodo) {
    $objResponse = new xajaxResponse();
	
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
	//"actualizarNivelesAtencion"
    $token = $sql->asignar($Tabla, $Valor, $CodigoProducto, $campos, $metodo);
    return $objResponse;
}

//Funcion xajax para buscar productos con codigo de barras
function Buscar_ProductoConCodigoBarras($CodigoBarras) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
    $Producto = $sql->Buscar_ProductoBarras($CodigoBarras);

    if (!empty($Producto))
        $html = "Codigo de Barras ya est� Asignado";
    else
        $html = "Codigo de Barras no Asignado";



    $objResponse->assign("MensajeBarras", "innerHTML", $html);
    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaSubClasesAsignadas($NombreGrupo, $NombreClase, $CodigoGrupo, $CodigoClase, $Nombre, $Codigo, $Sw_Medicamento, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");

    $SubClasesConClases = $sql->BuscarSubClasesConClase($Nombre, $Codigo, $CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset);
    //$Nombre,$Codigo,$CodigoGrupo,$CodigoClase,$Sw_Medicamento,$offset
    //PARA ASIGNAR A LISTADO CLASES
    $html .="<center>";
    $action['paginador'] = "Paginador_4('" . $NombreGrupo . "','" . $NombreClase . "','" . $CodigoGrupo . "','" . $CodigoClase . "','" . $Nombre . "','" . $Codigo . "','" . $Sw_Medicamento . "'";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\" style=\"width:50%\">\n";
    $html .= "  <legend class=\"normal_10AN\">SUBCLASES ASIGNADAS A " . $NombreClase . " del Grupo " . $NombreGrupo . "</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"3%\">CODIGO</td>\n";
    $html .= "      <td width=\"15%\">NOMBRE DE LA SUBCLASE</td>\n";
    /* $html .= "      <td width=\"3%\">CONCENTRACION</td>\n";
      $html .= "      <td width=\"5%\">U. DE MEDIDA</td>\n"; */
    $html .= "      <td width=\"3%\">CONTINUAR...</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($SubClasesConClases as $key => $subclases) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $subclases['subclase_id'] . "</td><td>" . $subclases['descripcion'] . " </td>\n";
        $html .= "      </td>";

        /* $html .= "      <td align=\"center\">".$subclases['concentracion']."</td>\n";
          $html .= "      <td align=\"center\">".$subclases['descripcion']."</td>\n"; */
        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_IngresoProducto('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "','" . $subclases['subclase_id'] . "','" . $subclases['descripcion'] . " " . $subclases['concentracion'] . " " . $subclases['unidad'] . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"CONTINUAR, CREAR PRODUCTO...\" src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset>\n";
    $html .="</center>";



    $objResponse->assign("ListadoSubClases", "innerHTML", $html);
    return $objResponse;
}

/*
 * Funcion que depliega la capita para ver/adicionar las clases y subclases
 * a un Grupo en particular
 * @param String $CodigoGrupo
 * @return String
 */

function Continuar_Con_SubClases($CodigoGrupo, $CodigoClase, $NombreClase, $NombreGrupo, $Sw_Medicamento, $offset) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    $SubClasesConClases = $sql->ListadoSubClasesConClase($CodigoGrupo, $CodigoClase, $Sw_Medicamento, $offset);

    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador_\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
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
    $html .= "<input style=\"width:100%;height:100%\"  class=\"input-text\" type=\"text\" name=\"descripcion\" onkeyup=\"Busqueda_();\">";

    $html .= "</td>";

    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\"  class=\"input-text\" type=\"text\" name=\"clase_id\" maxlength=\"10\" onkeyup=\"Busqueda_();\" >";
    $html .= "<input type=\"hidden\" name=\"GrupoId\" value='" . $CodigoGrupo . "'>";
    $html .= "<input type=\"hidden\" name=\"ClaseId\" value='" . $CodigoClase . "'>";
    $html .= "<input type=\"hidden\" name=\"NombreClase\" value='" . $NombreClase . "'>";
    $html .= "<input type=\"hidden\" name=\"NombreGrupo\" value='" . $NombreGrupo . "'>";
    $html .= "<input type=\"hidden\" name=\"Sw_Medicamento\" value='" . $Sw_Medicamento . "'>";
    $html .= "</td>";
    $html .= "</tr>";

    $html .="</table>
            </form>";

    //Listado de SubClases asignadas a una Clase

    $html .= "<div id=\"ListadoSubClases\">";
    $html .="<center>";
    //$CodigoGrupo,$CodigoClase,$NombreClase,$NombreGrupo,$Sw_Medicamento,$offset
    $action['paginador'] = "Paginador_3('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "'";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\" style=\"width:50%\">\n";
    $html .= "  <legend class=\"normal_10AN\">SUBCLASES ASIGNADAS A " . $NombreClase . " del Grupo " . $NombreGrupo . "</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"3%\">CODIGO</td>\n";
    $html .= "      <td width=\"15%\">NOMBRE DE LA SUBCLASE</td>\n";
    //$html .= "      <td width=\"3%\">CONCENTRACION</td>\n";
    //$html .= "      <td width=\"5%\">U. DE MEDIDA</td>\n";
    $html .= "      <td width=\"3%\">CONTINUAR...</td>\n";

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
        $html .= "        <a href=\"#\" onclick=\"xajax_IngresoProducto('" . $CodigoGrupo . "','" . $CodigoClase . "','" . $NombreClase . "','" . $NombreGrupo . "','" . $subclases['subclase_id'] . "','" . $subclases['descripcion'] . " " . $subclases['concentracion'] . " " . $subclases['unidad'] . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"CONTINUAR, CREAR PRODUCTO...\" src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset>\n";
    $html .="</center>";
    $html .= "</div>"; //FIN DIV LISTADO!!!


    $objResponse->assign("subclases", "innerHTML", $html);
    $objResponse->script("tabPane.setSelectedIndex(2);");
    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

//nombregrupo,grupo,nombre,codigo,sw_medicamento
function BusquedaClasesAsignadas($NombreGrupo, $Grupo, $Nombre, $Codigo, $Sw_Medicamento, $offset) {
    $CodigoGrupo = $Grupo;
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    //BuscarClasesAsignadas($CodigoGrupo,$CodigoClase,$NombreClase,$offset)
    $ClasesxGrupo = $sql->BuscarClasesAsignadas($Grupo, $Codigo, $Nombre, $offset);

    $html .= "<center>";


    $action['paginador'] = "Paginador_2('" . $NombreGrupo . "','" . $CodigoGrupo . "','" . $Nombre . "','" . $Codigo . "','" . $Sw_Medicamento . "'";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);


    $html .= "<fieldset class=\"fieldset\" style=\"width:40%\">\n";
    $html .= "  <legend class=\"normal_10AN\">CLASES ASIGNADAS A " . $NombreGrupo . ", SELECCIONE UNA...</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"10%\">CODIGO</td>\n";
    $html .= "      <td width=\"50%\">NOMBRE DE LA CLASE</td>\n";
    $html .= "      <td width=\"30%\">CONTINUAR...</td>\n";

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
        $html .= "        <a href=\"#\" onclick=\"xajax_Continuar_Con_SubClases('" . $CodigoGrupo . "','" . $clases['clase_id'] . "','" . $clases['descripcion'] . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"CONTINUAR...\" src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
        $html .= "      </tr>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset>\n";
    $html .= "</center>";


    $objResponse->assign("ListadoClases", "innerHTML", $html);
    return $objResponse;
}

/*
 * Funcion que depliega la capita para ver/adicionar las clases y subclases
 * a un Grupo en particular
 * @param String $CodigoGrupo
 * @return String
 */

function Continuar_Con_Clases($CodigoGrupo, $NombreGrupo, $Sw_Medicamento, $offset) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasClasificacion", "classes", "app", "Inv_CodificacionProductos");
    $ClasesxGrupo = $sql->ListadoClasesxGrupo($CodigoGrupo, $offset);

    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
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
    $html .= "<input style=\"width:100%;height:100%\"  class=\"input-text\" type=\"text\" name=\"descripcion\" onkeyup=\"Busqueda();\">";

    $html .= "</td>";

    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "Codigo :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\"  class=\"input-text\" type=\"text\" name=\"clase_id\" maxlength=\"4\" onkeyup=\"Busqueda();\" >";
    $html .= "</td>";

    $html .= "<td>";
    $html .= "<input type=\"hidden\" name=\"GrupoId\" value='" . $CodigoGrupo . "'>";
    $html .= "<input type=\"hidden\" name=\"NombreGrupo\" value='" . $NombreGrupo . "'>";
    $html .= "<input type=\"hidden\" name=\"sw_medicamento\" value='" . $Sw_Medicamento . "'>";
    $html .= "</td>";
    $html .= "</tr>";

    $html .="</table>
            </form>
            ";

    //FIN BUSCADOR
    //Listado de Clases asignadas a un grupo

    $html .= "<div id=\"ListadoClases\">";
    $html .= "<center>";

    $html .="<div id=\"SelectClasesNoAsignadas\">";



    $action['paginador'] = "Paginador_1('" . $CodigoGrupo . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "'";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\" style=\"width:40%\">\n";
    $html .= "  <legend class=\"normal_10AN\">CLASES ASIGNADAS A " . $NombreGrupo . ", SELECCIONE UNA...</legend>\n";
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"10%\">CODIGO</td>\n";
    $html .= "      <td width=\"50%\">NOMBRE DE LA CLASE</td>\n";
    $html .= "      <td width=\"30%\">CONTINUAR...</td>\n";

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
        $html .= "        <a href=\"#\" onclick=\"xajax_Continuar_Con_SubClases('" . $CodigoGrupo . "','" . $clases['clase_id'] . "','" . $clases['descripcion'] . "','" . $NombreGrupo . "','" . $Sw_Medicamento . "')\">\n";
        $html .= "          <img title=\"CONTINUAR...\" src=\"" . GetThemePath() . "/images/flecha.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";
    }



    $html .= "    </table>\n";
    $html .= "</fieldset>\n";
    $html .= "</center>";
    $html .= "</div>"; //FIN DIV LISTADO!!!

    $vacio = "&nbsp;&nbsp;";
    $objResponse->assign("subclases", "innerHTML", $vacio);
    $objResponse->assign("clases", "innerHTML", $html);
    $objResponse->script("tabPane.setSelectedIndex(1);");

    return $objResponse;
}

/*
 * Funcion que permite el Modificar de Productos/Insumos a la base de datos
 * @param Array
 * @return array.
 */

function ModificarProductoMedicamento($datos) {
    $objResponse = new xajaxResponse();
	//$objResponse->alert("PARAMETROS MODIFICAR PRODUCTO MEDICAMENTOS ----->");
	
	
	/*echo "<pre> INSUMO ---->";
          print_r($datos);
          echo "</pre>";
          exit();*/
	 
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
		
    $token=$sql->Modificar_ProductoInsumo($datos); 
    $sql->GuardarAuditoria_Medicamento($datos['codigo_producto'], "Original");
    $token_1 = $sql->Modificar_ProductoMedicamento($datos);
	 
    //HACER VALIDACION ACA CON EL METODO existenciaProducto SI DEVUELVE 0 LLAMAR insertar_productomedicamento::
    $sql->GuardarAuditoria_Medicamento($datos['codigo_producto'], "Modificado");
	
    $token_1_ws = $sql->Modificar_ProductoMedicamento_WS($datos);
    $token_2_ws = $sql->Modificar_ProductoMedicamento_WS($datos, "1");
    $token_3_ws = $sql->Modificar_ProductoMedicamento_WS($datos, "2");
    $token_4_ws = $sql->Modificar_ProductoMedicamento_WS($datos, "3");
    $token_5_ws = $sql->Modificar_ProductoMedicamento_WS($datos, "4");
	$token_6_ws = $sql->Modificar_ProductoMedicamento_WS($datos, "5");
	$token_7_ws = $sql->Modificar_ProductoMedicamento_WS($datos, "6");
	
	
	//echo "token_2_ws:: ". $token_2_ws;
	//echo "token_3_ws:: ". $token_3_ws;
    if ($token_1_ws == true) {
        $mensaje = "\nModificacion Exitosa en Cosmitet.";
    } else {
        $mensaje = "\nError en la Modificacion en Cosmitet.";
    }

    if ($token_2_ws == true) {
        $mensaje .= "\nModificacion Exitosa en Dumian.";
    } else {
        $mensaje .= "\nError en la Modificacion en Dumian.";
    }

    if ($token_3_ws == true) {
        $mensaje .= "\nModificacion Exitosa en CSSP. ";
    } else {
        $mensaje .= "\nError en la Modificacion en CSSP.";
    }
    
    if ($token_4_ws == true) {
        $mensaje .= "\nModificacion Exitosa en MD.";
    } else {
        $mensaje .= "\nError en la Modificacion en MD.";
    }
    
    if ($token_5_ws == true) {
        $mensaje .= "\nModificacion Exitosa en CMS.";
    } else {
        $mensaje .= "\nError en la Modificacion en CMS.";
    }
	
	if ($token_6_ws == true) {
        $mensaje .= "\nModificacion Exitosa en PENITAS.";
    } else {
        $mensaje .= "\nError en la Modificacion en PENITAS.";
    }
	
	if ($token_7_ws == true) {
        $mensaje .= "\nModificacion Exitosa en CARTAGENA.";
    } else {
        $mensaje .= "\nError en la Modificacion en CARTAGENA.";
    }
	
    if ($token_1 == true) {
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->script("xajax_Productos_Creados();");
        $objResponse->alert("Modificacion Exitosa!!" . $mensaje);
        $objResponse->script("tabPane.setSelectedIndex(4);");
        $objResponse->assign("formulario_ingreso", "innerHTML", "<center>Armar la Codificacion: Grupo-Clase-SubClase</center>");
    }
    else
	
        $objResponse->alert("Error en el Ingreso..." . $sql->mensajeDeError);
	
    return $objResponse;
}

/*
 * Funcion que permite el Modificar de Productos/Insumos a la base de datos
 * @param Array
 * @return array.
 */

function ModificarProductoInsumo($datos, $parametro) {   
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $sql->GuardarAuditoria_ProductoInsumo($datos['codigo_producto'], "Original");
    $token = $sql->Modificar_ProductoInsumo($datos);
    $sql->GuardarAuditoria_ProductoInsumo($datos['codigo_producto'], "Modificado");
    
    //HACER VALIDACION ACA CON EL METODO existenciaProducto SI DEVUELVE 0 LLAMAR insertar_productoinsumo:
    $token_ws = $sql->Modificar_ProductoInsumo_WS($datos,"0",$parametro);
    $token_2_ws = $sql->Modificar_ProductoInsumo_WS($datos, "1");
    $token_3_ws = $sql->Modificar_ProductoInsumo_WS($datos, "2", $parametro);
    $token_4_ws = $sql->Modificar_ProductoInsumo_WS($datos, "3", $parametro); 
    $token_6_ws = $sql->Modificar_ProductoInsumo_WS($datos, "5", $parametro); 
    $token_7_ws = $sql->Modificar_ProductoInsumo_WS($datos, "6", $parametro); 
    $token_5_ws = $sql->Modificar_ProductoInsumo_WS($datos, "4");
	
    
	      /*echo "<pre> POR A QUI VER QUE LLEGA parametro ---->";
          print_r($parametro);
		   
          echo "</pre>";
          exit();*/
		
			
    if ($token_ws == '1') {
       // $mensaje = "\nModificacion Exitosa en Cosmitet.".$token."  token_ws ---->".$token_ws;
	    $mensaje = "\nModificacion Exitosa en Cosmitet";
    } else {
        $mensaje = "\nError en la Modificacion en Cosmitets.  2token_ws ---->".$token_ws;
    }

    if ($token_2_ws == '1') {
        $mensaje .= "\nModificacion Exitosa en Dumian.";
    } else {
        $mensaje .= "\nError en la Modificacion en Dumian.";
    }

    if ($token_3_ws == '1') {
        $mensaje .= "\nModificacion Exitosa en CSSP.";
    } else {
        $mensaje .= "\nError en la Modificacion en CSSP.";
    }

    if ($token_4_ws == '1') {
        $mensaje .= "\nModificacion Exitosa en MD.";
    } else {
        $mensaje .= "\nError en la Modificacion en MD.";
    }
    
    if ($token_5_ws == '1') {
        $mensaje .= "\nModificacion Exitosa en CMS.";
    } else {
        $mensaje .= "\nError en la Modificacion en CMS.";
    }
	
	if ($token_6_ws == '1') {
        $mensaje .= "\nModificacion Exitosa en PENITAS.";
    } else {
        $mensaje .= "\nError en la Modificacion en PENITAS.";
    }
	
	if ($token_7_ws == '1') {
        $mensaje .= "\nModificacion Exitosa en CARTAGENA.";
    } else {
        $mensaje .= "\nError en la Modificacion en CARTAGENA.";
    }
	
    if ($parametro == '1') {
        if ($token) {
		
            $objResponse->call("Cerrar('Contenedor')");
            $objResponse->script("xajax_Productos_Creados();");
            $objResponse->alert("Modificacion Exitosa!!" . $mensaje);
            $objResponse->script("tabPane.setSelectedIndex(4);");
            $objResponse->assign("formulario_ingreso", "innerHTML", "<center>Armar la Codificacion: Grupo-Clase-SubClase</center>");
        }
        else
            $objResponse->alert("Error en el Ingreso >>>..." . $sql->mensajeDeError);
    }
    else {
        if ($token)
            $objResponse->alert("Modificacion Exitosa!!" . $mensaje);
			
        else
            $objResponse->alert("Error en el Ingreso..." . $sql->mensajeDeError);
			
    }
    return $objResponse;
}

/*
 * Funcion que permite el Ingreso de Productos/Insumos a la base de datos
 * @param Array
 * @return array.
 */

function InsertarProductoInsumo($datos, $parametro) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Insertar_ProductoInsumo($datos);

    $token_ws =   $sql->Insertar_ProductoInsumo_WS($datos,'0',$parametro); 
    //$token_2_ws = $sql->Insertar_ProductoInsumo_WS($datos, '1');
    $token_3_ws = $sql->Insertar_ProductoInsumo_WS($datos,'2',$parametro);
    $token_4_ws = $sql->Insertar_ProductoInsumo_WS($datos,'3',$parametro);
	$token_6_ws = $sql->Insertar_ProductoInsumo_WS($datos,'5',$parametro);
	$token_7_ws = $sql->Insertar_ProductoInsumo_WS($datos,'6',$parametro);
    //$token_5_ws = $sql->Insertar_ProductoInsumo_WS($datos, '4');
    //$objResponse->alert("SQL: ".$token);

    if ($token_ws) {
        $mensaje = "\nIngreso Exitoso en Cosmitet.";
    } else {
        $mensaje = "\nError en el Ingreso en Cosmitet.";
    }

    if ($token_2_ws == true) {
        $mensaje .= "\nIngreso Exitoso en Dumian.";
    } else {
        $mensaje .= "\nError en el Ingreso en Dumian.";
    }

    if ($token_3_ws == true) {
        $mensaje .= "\nIngreso Exitoso en CSSP.";
    } else {
        $mensaje .= "\nError en el Ingreso en CSSP.";
    }

    if ($token_4_ws == true) {
        $mensaje .= "\nIngreso Exitoso en MD.";
    } else {
        $mensaje .= "\nError en el Ingreso en MD.";
    }

    if ($token_5_ws == true) {
        $mensaje .= "\nIngreso Exitoso en CMS.";
    } else {
        $mensaje .= "\nError en el Ingreso en CMS.";
    }
	
	if ($token_6_ws == true) {
        $mensaje .= "\nIngreso Exitoso en PENITAS.";
    } else {
        $mensaje .= "\nError en el Ingreso en PENITAS.";
    }
	
	if ($token_7_ws == true) {
        $mensaje .= "\nModificacion Exitosa en CARTAGENA.";
    } else {
        $mensaje .= "\nError en la Modificacion en CARTAGENA.";
    }

    if ($token) {
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->script("xajax_Productos_Creados();");
        $objResponse->alert("Ingreso Exitoso!!" . $mensaje);
        $objResponse->script("tabPane.setSelectedIndex(4);");
        $objResponse->assign("formulario_ingreso", "innerHTML", "<center>Armar la Codificacion: Grupo-Clase-SubClase</center>");
    }
    else
        $objResponse->alert("Error en el Ingreso..." . $sql->mensajeDeError);

    return $objResponse;
}

//Funcion Xajax_ para que situe al usuario en el tab que se envie como parametro
function Volver($tab) {
    $objResponse = new xajaxResponse();

    $objResponse->script("tabPane.setSelectedIndex(" . $tab . ");");

    return $objResponse;
}

/*
 * Funcion que permite el Ingreso de Productos/Insumos a la base de datos
 * @param Array
 * @return array.
 */

function InsertarProductoMedicamento($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $token_1 = $sql->Insertar_ProductoMedicamento($datos);
    $token_1_WS = $sql->Insertar_ProductoMedicamento_WS($datos);
    $token_2_WS = $sql->Insertar_ProductoMedicamento_WS($datos, '1');
    $token_3_WS = $sql->Insertar_ProductoMedicamento_WS($datos, '2');
    $token_4_WS = $sql->Insertar_ProductoMedicamento_WS($datos, '3');
    $token_5_WS = $sql->Insertar_ProductoMedicamento_WS($datos, '4');
	$token_6_WS = $sql->Insertar_ProductoMedicamento_WS($datos, '5');
	$token_7_WS = $sql->Insertar_ProductoMedicamento_WS($datos, '6');
	
    if ($token_1_WS == true) {
        $mensaje = "\nIngreso Exitoso en Cosmitet.";
    } else {
        $mensaje = "\nError en el Ingreso en Cosmitet.";
    }


    if ($token_2_WS == true) {
        $mensaje .= "\nIngreso Exitoso en Dumian.";
    } else {
        $mensaje .= "\nError en el Ingreso en Dumian.";
    }

    if ($token_3_WS == true) {
        $mensaje .= "\nIngreso Exitoso en CSSP.";
    } else {
        $mensaje .= "\nError en el Ingreso en CSSP.";
    }

    if ($token_4_WS == true) {
        $mensaje .= "\nIngreso Exitoso en MD.";
    } else {
        $mensaje .= "\nError en el Ingreso en MD.";
    }

    if ($token_5_WS == true) {
        $mensaje .= "\nIngreso Exitoso en CM.";
    } else {
        $mensaje .= "\nError en el Ingreso en CM.";
    }
	
	if ($token_6_WS == true) {
        $mensaje .= "\nIngreso Exitoso en PENITAS.";
    } else {
        $mensaje .= "\nError en el Ingreso en PENITAS.";
    }
	
	if ($token_7_WS == true) {
        $mensaje .= "\nIngreso Exitoso en CARTAGENA.";
    } else {
        $mensaje .= "\nError en el Ingreso en CARTAGENA.";
    }
	

    if ($token_1 == true) {
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->script("xajax_Productos_Creados();");
        $objResponse->alert("Ingreso Exitoso!!" . $mensaje);
        $objResponse->assign("formulario_ingreso", "innerHTML", "<center>Armar la Codificacion: Grupo-Clase-SubClase</center>");
        $objResponse->script("tabPane.setSelectedIndex(4);");
    } else {
        $sql1 = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
        $token = $sql1->Borrar_Registro("inventarios_productos", $datos['codigo_producto'], "codigo_producto");
        $objResponse->alert("Error en el Ingreso..." . $sql->mensajeDeError);
    }

    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaFabricante($Nombre) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Fabricante = $sql->BusquedaFabricante($Nombre);


    $SelectFabricante = '<SELECT NAME="fabricante_id" SIZE="1" class="select" style="width:40%;height:40%">';
    foreach ($Fabricante as $key => $Fabricante_) {
        $SelectFabricante .= '<OPTION VALUE="' . $Fabricante_['codigo'] . '">' . $Fabricante_['descripcion'] . '</OPTION>';
    }
    $SelectFabricante .='</SELECT> -- Seleccionar';

    $objResponse->assign("fabricante", "innerHTML", $SelectFabricante);
    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaTitular($Nombre) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Titular = $sql->BusquedaTitular($Nombre);


    $SelectTitular = '<SELECT NAME="titular_reginvima_id" id= "titular_reginvima_id" SIZE="1" class="select" style="width:40%;height:40%">';
    foreach ($Titular as $key => $Titular_) {
        $SelectTitular .= '<OPTION VALUE="' . $Titular_['codigo'] . '">' . $Titular_['descripcion'] . '</OPTION>';
    }
    $SelectTitular .='</SELECT> -- Seleccionar';

	

	
    $objResponse->assign("titular", "innerHTML", $SelectTitular);
    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaAcuerdo228($Codigo) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Acuerdo228 = $sql->BuscarAcuerdo228($Codigo);

    if (empty($Acuerdo228)) {
        $html = "!No Existe Codigo�";
        $objResponse->script("
                              document.FormularioProducto.cod_acuerdo228_id.value='" . $Codigo . "';
                              ");
    } else {
        $html = $Acuerdo228[0]['descripcion'];
        $objResponse->script("
                              document.FormularioProducto.cod_acuerdo228_id.value='" . $Acuerdo228[0]['codigo'] . "';
                              ");
    }

    $objResponse->assign("acuerdo_228", "innerHTML", $html);
    return $objResponse;
}

/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */

function BusquedaMensajeProducto($Codigo) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $MensajeProducto = $sql->BuscarMensaje($Codigo);

    if (empty($MensajeProducto)) {
        $html = "!No Existe Codigo�";
        $objResponse->script("
                              document.FormularioProducto.mensaje_id.value='';
                              ");
    } else {
        $html = $MensajeProducto[0]['descripcion'];
        $objResponse->script("
                              document.FormularioProducto.mensaje_id.value='" . $MensajeProducto[0]['codigo'] . "';
                              ");
    }

    $objResponse->assign("MensajeProducto", "innerHTML", $html);
    return $objResponse;
}

function InsertarEspxProd($Especialidad, $CodigoProducto) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->InsertarEspxProd_($Especialidad, $CodigoProducto);

    if ($token) {
        $objResponse->script("xajax_ListaEspecialidades('" . $CodigoProducto . "');");
        $objResponse->script("xajax_ListaEspecialidadxProducto('" . $CodigoProducto . "');");
    }
    else
        $objResponse->alert("Error: Revisa que no haya sido Asignado!!");


    return $objResponse;
}

/*
  Funcion Xajax para borrar Grupos
 */

function BorrarEspxProd($tabla, $id, $campo_id, $CodigoProducto) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $token = $sql->Borrar_Registro($tabla, $id, $campo_id);

    if ($token) {
        $objResponse->script("xajax_ListaEspecialidades('" . $CodigoProducto . "');");
        $objResponse->script("xajax_ListaEspecialidadxProducto('" . $CodigoProducto . "');");
    }
    else
        $objResponse->alert("Error al Borrar!!");


    return $objResponse;
}


/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */
function ListaEspecialidades($CodigoProducto) {
    $objResponse = new xajaxResponse();


    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Especialidades = $sql->Listar_Especialidades($CodigoProducto);

    $SelectEspecialidades = '<SELECT NAME="sin_asignar" SIZE="4" class="select" style="width:100%;height:100%">';
    foreach ($Especialidades as $key => $esp) {
        $SelectEspecialidades .= "<OPTION VALUE='" . $esp['especialidad'] . "' ondblclick=\"xajax_InsertarEspxProd('" . $esp['especialidad'] . "','" . $CodigoProducto . "')\">" . $esp['descripcion'] . "</OPTION>";
    }
    $SelectEspecialidades .='</SELECT>';

    $objResponse->assign("especialidad", "innerHTML", $SelectEspecialidades);
    return $objResponse;
}


/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */
function ListaEspecialidadxProducto($CodigoProducto) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Especialidades = $sql->Listar_EspecialidadesxProducto($CodigoProducto);

    $SelectEspecialidades = '<SELECT NAME="especialidad" SIZE="4" class="select" style="width:100%;height:100%">';
    foreach ($Especialidades as $key => $esp) {
        $SelectEspecialidades .= "<OPTION VALUE='" . $esp['codigo'] . "' ondblclick=\"xajax_BorrarEspxProd('inv_especialidad_x_producto','" . $esp['codigo'] . "','especialidad_x_producto_id','" . $CodigoProducto . "')\">" . $esp['descripcion'] . "</OPTION>";
    }
    $SelectEspecialidades .='</SELECT>';

    //Con el Fin de que al menos un medicamento debe pertenecer a una especialidad
    if (!$Especialidades)
        $SelectEspecialidades .= '  <input type="hidden" name="sin_items" value="0">';
    else
        $SelectEspecialidades .= '  <input type="hidden" name="sin_items" value="1">';

    $objResponse->assign("especialidades_asignadas", "innerHTML", $SelectEspecialidades);

    return $objResponse;
}

function ListaViasAdministracion($CodigoProducto) {
    $objResponse = new xajaxResponse();


    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $ViasAdministracion = $sql->Listar_ViasAdministracion($CodigoProducto);

    $SelectViasAdministracion = '<SELECT NAME="sin_asignarvias" SIZE="4" class="select" style="width:100%;height:100%">';
	
    foreach ($ViasAdministracion as $key => $viad) {
        $SelectViasAdministracion.= "<OPTION ondblclick=\"xajax_InsertarViadxProd('" . $viad['via_administracion_id'] . "','" . $CodigoProducto . "')\">" . $viad['nombre'] . "</OPTION>";
    }
    $SelectViasAdministracion .='</SELECT>';

    $objResponse->assign("viasadministracion", "innerHTML", $SelectViasAdministracion);
    return $objResponse;
}


/*
 * Realiza las busqueda de Clases Asignadas por Codigo y Descripcion... utilizado por el Buscador
 */
function ListaViasAdministracionxProducto($CodigoProducto) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $ViasAdministracionX = $sql->Listar_ViasAdministracionxProducto($CodigoProducto);

    $SelectViasAdministracionX = '<SELECT id="idViaAdministracionId" NAME="viasadministracion" SIZE="4" class="select" style="width:100%;height:100%">';
	
    foreach ($ViasAdministracionX as $key => $viadx) {
        $SelectViasAdministracionX .= "<OPTION VALUE='" . $viadx['via_administracion_id'] . "' ondblclick=\"xajax_BorrarViadxProd('inv_medicamentos_vias_administracion','" . $viadx['via_administracion_id'] . "','via_administracion_id','and codigo_medicamento','" . $CodigoProducto . "')\">" . $viadx['nombre'] . "</OPTION>";
    }
    $SelectViasAdministracionX .='</SELECT>';
	
    //Con el Fin de que al menos un medicamento debe pertenecer a una especialidad
    if (!$ViasAdministracionX)
        $SelectViasAdministracionX .= '  <input type="hidden" name="sin_itemsv" value="0">';
    else
        $SelectViasAdministracionX .= '  <input type="hidden" name="sin_itemsv" value="1">';

    $objResponse->assign("viasadministracion_asignadas", "innerHTML", $SelectViasAdministracionX);

    return $objResponse;
}

/*
 * Capita del Formulario para la modificacion de Productos
 */

function ModProducto($CodigoProducto, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $paises = $sql->Listar_Paises();
    $PerfilesTerapeuticos = $sql->Listar_Perfiles_Terapeuticos();
    $TratamientosProductos = $sql->Listar_TratamientosProductos();
    $Producto_Id = $sql->Consecutivo_Producto($CodigoGrupo, $CodigoClase, $CodSubClase);
    $UnidadesMedida = $sql->Listar_Unidades_Medida();
    $ViasDeAdministracion = $sql->Listar_Vias_De_Administracion();
    $tipo_producto = $sql->Listar_Tipos_Productos();
    $producto = $sql->Buscar_Producto($CodigoProducto);
    /*echo "<pre>";
	print_r($producto);*/
	$objResponse->script("enfocarCampoDescripcion()");
	$html .= "<form name=\"FormularioProducto\" id=\"FormularioProducto\" method=\"post\">";
	
	$estadosInvima = $sql->consultarEstadosInvima();
	
    foreach ($producto as $key => $prod) {
        //Presentaciones Comerciales
        $presentacioncomercial = $sql->Listar_PresentacionesComerciales();
        $selectPresentacionComercial .="<select class=\"select\" name=\"presentacioncomercial_id\" id=\"presentacioncomercial_id\" style=\"width:40%;height:40%\">";
        foreach ($presentacioncomercial as $key => $pc) {
            $selectPresentacionComercial .="<option value='" . $pc['codigo'] . "' ";

            if (strcmp(trim($pc['codigo']), trim($prod['presentacioncomercial_id'])) == 0)
                $selectPresentacionComercial .=" selected ";

            $selectPresentacionComercial .=">" . $pc['descripcion'];
            $selectPresentacionComercial .="</option>";
			
        }
        $selectPresentacionComercial .="</select>";
	

	//Lista presentacion comercial farmacologica
		$presentacionComercialFarmacologia = $sql->Listar_Perfiles_Terapeuticos();
         $selectPresentacionComercialFarmaco = '<SELECT NAME="cod_comerfarmacologico" id="cod_comerfarmacologico" SIZE="1" class="select" style="width:100%;height:100%" >';//onChange="descripcionComercialFarmaco()"
        foreach ($presentacionComercialFarmacologia as $ky => $PreComercialFarmaco) {
            $selectPresentacionComercialFarmaco .= '<OPTION VALUE="' . $PreComercialFarmaco['codigo'] . '"';
            if ($PreComercialFarmaco['codigo'] == $prod['cod_comerfarmacologico'])
                $selectPresentacionComercialFarmaco .=" selected ";

            $selectPresentacionComercialFarmaco .='>' . $PreComercialFarmaco['descripcion'] . '-' . $PreComercialFarmaco['codigo'] . '</OPTION>';
        }
        $selectPresentacionComercialFarmaco .='</SELECT>';
	
	
	  $html .= "	<input type=\"hidden\" name=\"comercialFarmacoDescripcion\" id=\"comercialFarmacoDescripcion\">";
	
	
        $SelectPerfilesTerapeuticos = '<SELECT NAME="cod_anatofarmacologico" id="cod_anatofarmacologico" SIZE="1" class="select" style="width:100%;height:100%" >';//onChange="descripcionAnatoFarmacologico()"
        foreach ($PerfilesTerapeuticos as $ky => $PerfilesTerapeuticos) {
            $SelectPerfilesTerapeuticos .= '<OPTION VALUE="' . $PerfilesTerapeuticos['codigo'] . '"';
            if ($PerfilesTerapeuticos['codigo'] == $prod['cod_anatofarmacologico'])
                $SelectPerfilesTerapeuticos .=" selected ";

            $SelectPerfilesTerapeuticos .='>' . $PerfilesTerapeuticos['descripcion'] . '-' . $PerfilesTerapeuticos['codigo'] . '</OPTION>';
        }
        $SelectPerfilesTerapeuticos .='</SELECT>';
		
		$html .= "	<input type=\"hidden\" name=\"anatoFarmacologicoDescripcion\" id=\"anatoFarmacologicoDescripcion\" >";
		
		
		
		
        //Adicion: select para rango codigo administrativo presentacion 
        $Selectpresenta = '<SELECT NAME="cod_presenta" SIZE="1" class="select" style="width:40%;height:40%">';
        $array = array(" ", "1", "2", "3", "4");
        foreach ($array as $valor) {
            $Selectpresenta .= '<OPTION VALUE="' . $valor . '"';
            if ($valor == $prod['cod_adm_presenta'])
                $Selectpresenta .=" selected ";

            $Selectpresenta .='>' . $valor . '</OPTION>';
        }
        $Selectpresenta .='</SELECT>';
        //fin adicion

        $SelectUnidadesMedida = '<SELECT NAME="unidad_id" id="unidad_id" SIZE="1" class="select" style="width:40%;height:40%"   >';//onChange="descripcionUnidadMedida()"
        foreach ($UnidadesMedida as $ke => $Unidades) {
            $SelectUnidadesMedida .= '<OPTION VALUE="' . $Unidades['codigo'] . '"';
            if ($Unidades['codigo'] == $prod['unidad_id'])
                $SelectUnidadesMedida .=" selected ";
			
			
            $SelectUnidadesMedida .='>' . $Unidades['descripcion'] . '-' . $Unidades['codigo'] . '</OPTION>';
			
		
        }
        $SelectUnidadesMedida .='</SELECT>';

		$html .= "	<input type=\"hidden\" name=\"unidadMedidaDescripcion\" id=\"unidadMedidaDescripcion\">";
		
        $SelectTipo_producto = '<SELECT NAME="tipo_producto_id" SIZE="1" class="select" style="width:40%;height:40%">';
        $SelectTipo_producto .= '<OPTION VALUE=""></OPTION>';
        foreach ($tipo_producto as $key => $tp) {
            $SelectTipo_producto .= '<OPTION VALUE="' . $tp['codigo'] . '"';

            if ($tp['codigo'] == $prod['tipo_producto_id'])
                $SelectTipo_producto .=" selected ";

            $SelectTipo_producto.='>' . $tp['descripcion'] . '</OPTION>';
        }
        $SelectTipo_producto .='</SELECT>';


        $selectpais = "<select class=\"select\" name=\"tipo_pais_id\">";
        $selectpais .="<option value=\"\">";
        $selectpais .="--Seleccionar--" . $prod['tipo_pais_id'];
        $selectpais .="</option>";
        foreach ($paises as $k => $pais) {

            $selectpais .="<option value='" . $pais['codigo'] . "' ";

            // if($pais['codigo'] == $prod['tipo_pais_id'])
            if (strcmp(trim($pais['codigo']), trim($prod['tipo_pais_id'])) == 0)
                $selectpais .=" selected ";
            $selectpais .=">" . $pais['pais'];
            $selectpais .="</option>";
        }
        $selectpais .="</select>";

        $selectedT = "";
        $selectT = "<select name=\"tratamiento_id\" id=\"tratamiento_id\" class=\"select\" style=\"width:40%;height:40%\"> ";
        $selectT .= "		<option value=\"\">-- NO SE USA EN TRATAMIENTOS ESPECIALES --</option>";
        foreach ($TratamientosProductos as $key => $tt) {
            if (strcmp(trim($tt['tratamiento_id']), trim($prod['tratamiento_id'])) == 0)
                $selectedT = "	selected ";
            else
                $selectedT = "	";
            $selectT .= "		<option value=\"" . $tt['tratamiento_id'] . "\" " . $selectedT . ">";
            $selectT .= "			" . $tt['descripcion'];
            $selectT .= "		</option>";
        }
        $selectT .= "</select>";
		
		$html .= "	<input type=\"hidden\" name=\"tratamientosEspecialesDescripcion\" id=\"tratamientosEspecialesDescripcion\">";
		
		
        $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "  <tr><td>";
        //action del formulario= Donde van los datos del formulario.
        //$html .= "  <form name=\"FormularioProducto\" id=\"FormularioProducto\" method=\"post\">";
        $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr class=\"modulo_table_list_title\">";
        $html .= "      <td align=\"center\" colspan=\"2\">";
        $html .= "      Creacion de Producto ***//";
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Grupo :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= $prod['grupo'];
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Clase :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= $prod['clase'];
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      SubClase :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "<div id='clase_producto'>".$prod['subclase']."</div>";
        $html .= "		<input type=\"hidden\" name=\"subclase_id\" id=\"subclase_id\" value=\"" . $prod['subclase_id'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"clase_id\" value=\"" . $prod['clase_id_producto'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"grupo_id\" value=\"" . $prod['grupo_id_producto'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"descripcion_grupo\" value=\"" . $prod['descripciongrupo'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"descripcion_clase\" value=\"" . $prod['descripcionclase'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"descripcion_subclase\" value=\"" . $prod['descripcionsubclase'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"sw_tipo_empresa\" value=\"" . $prod['sw_tipo_empresa'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"sw_medicamento\" value=\"" . $prod['sw_medicamento'] . "\">";
		$html .= "		<input type=\"hidden\" name=\"molecula_id\" value=\"" . $prod['molecula_id'] . "\">";
		
		

        $html .= "    <a onclick=\"xajax_Modificar_ClasificacionProducto('" . $CodigoProducto . "','" . $Sw_Medicamento . "');\" class=\"label_error\">";
        $html .= "    &#60;&#60;CAMBIAR CODIFICACION&#62;&#62;</pre>";
        $html .= "    </a>";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Consecutivo :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['producto_id'] . "' name=\"producto_id\" size=\"10\" disabled=\"true\">";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Codigo Producto :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $CodigoProducto . "' name=\"codigo_producto\" size=\"20\" readOnly=\"true\">";
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Descripcion :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" name=\"descripcion\" id=\"descripcionIdModiProducto\" size=\"30\" value='" . $prod['descripcion'] . "' onkeyup=\"this.value=this.value.toUpperCase()\">";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Descripcion Abreviada :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" name=\"descripcion_abreviada\" size=\"30\" value='" . $prod['descripcion_abreviada'] . "'\">";
        $html .= "      </td>";
        $html .= "      </tr>";

        //adicion: codigo DCI - Denominacion Comun Internacional
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Denominacion Comun Internacional (DCI) :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        $html .= "       <input class=\"input-text\" type=\"text\" name=\"dci\" value='" . $prod['dci_id'] . "' size=\"50\" >";
        $html .= "      </tr>";
        //fin adicion		

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Codigo CUM :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_cum\" size=\"30\" value='" . $prod['codigo_cum'] . "' onkeyup=\"this.value=this.value.toUpperCase()\" >";
        $html .= "      </td>";
        $html .= "      </tr>";

       
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Codigo Barras :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_barras\" size=\"30\"  value='" . $prod['codigo_barras'] . "' onkeyup=\"getKey(event,this.value);\">";
        $html .= "      <div id=\"MensajeBarras\"></div>";
        $html .= "      </td>";
        $html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Codigo IUM :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_alterno\" value='" . $prod['codigo_alterno'] . "' size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
        $html .= "      </td>";
        $html .= "      </tr>";



        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Fabricante :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['fabricante'] . "' name=\"codigo_3\" style=\"width:70%\" onkeyup=\"xajax_BusquedaFabricante(this.value),this.value=this.value.toUpperCase()\"> -- Buscar";
        $html .= "      <div id=\"fabricante\"></div>";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Pais de Fabricacion :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= $selectpais;
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Producto POS :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        if ($prod['sw_pos'] == 1) {
            $html .= '      <input class="input-radio" type="radio" name="sw_pos" value="1" checked onclick="VerificarPos(true)">Si 
		<input class="radio" type="radio" name="sw_pos" value="0" onclick="VerificarPos(false)">No </td>';
        } else {
            $html .= '      <input class="input-radio" type="radio" name="sw_pos" value="1" onclick="VerificarPos(true)">Si 
		<input class="radio" type="radio" name="sw_pos" value="0"  checked onclick="VerificarPos(false)">No </td>';
        }
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        //$html .= "      Codigo. Acuerdo 228 :";
        $html .= "      Acuerdo 029 POS :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        //$html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo\" id=\"codigo\" size=\"20\" value='" . $prod['cod_acuerdo228_id'] . "' onkeyup=\"xajax_BusquedaAcuerdo228(this.value),this.value=this.value.toUpperCase()\">";
        if ($prod['sw_pos'] == 1) {
            $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo\" id=\"codigo\" size=\"20\" value='" . $prod['cod_acuerdo228_id'] . "' onkeyup=\"xajax_BusquedaAcuerdo228(this.value),this.value=this.value.toUpperCase()\">";
        } else {
            $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo\" id=\"codigo\" size=\"20\" value='' onkeyup=\"xajax_BusquedaAcuerdo228(this.value),this.value=this.value.toUpperCase()\" disabled=\"disabled\">";
        }
        $html .= "      <div id=\"acuerdo_228\"></div>";
        //$html .= "      <input type=\"hidden\" name=\"cod_acuerdo228_id\" id=\"cod_acuerdo228_id\" value=''>";
        if ($prod['sw_pos'] == 1) {
            $html .= "      <input type=\"hidden\" name=\"cod_acuerdo228_id\" id=\"cod_acuerdo228_id\" value=''>";
        } else {
            $html .= "      <input type=\"hidden\" name=\"cod_acuerdo228_id\" id=\"cod_acuerdo228_id\" value='' disabled=\"disabled\">";
        }
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      RIPS No POS :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        //$html .= "      <input class=\"input-text\" type=\"text\" name=\"rips_no_pos\" id=\"rips_no_pos\" size=\"20\" value='" . $prod['rips_no_pos'] . "'>";
        if ($prod['sw_pos'] != 1) {
            $html .= "      <input class=\"input-text\" type=\"text\" name=\"rips_no_pos\" id=\"rips_no_pos\" size=\"20\" value='" . $prod['rips_no_pos'] . "'>";
        } else {
            $html .= "      <input class=\"input-text\" type=\"text\" name=\"rips_no_pos\" id=\"rips_no_pos\" size=\"20\" value='' disabled=\"disabled\">";
        }
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Unidad Medida X Cantidad (Ej: Tableta Por 500MG) :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= $SelectUnidadesMedida;
        $html .= "  POR   <input class=\"input-text\" type=\"text\" value='" . $prod['cantidad'] . "' name=\"cantidad\" size=\"15\" > ";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Presentacion Comercial (Ej: \"Caja por 3\" unidades) :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "        " . $selectPresentacionComercial;
        $html .= "  POR   <input class=\"input-text\" type=\"text\" name=\"cantidad_p\" value=\"" . $prod['cantidad_p'] . "\" size=\"15\" > ";
        //$html .= "  POR   <input class=\"input-text\" type=\"text\" name=\"cantidad_p\" value=\"".$prod['cantidad_p']."\" size=\"15\" onkeypress=\"return acceptNum(event)\"> ";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Perfil Terapeutico :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= $SelectPerfilesTerapeuticos;
        $html .= "      </td>";
        $html .= "      </tr>";
		 
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Codigo Mensaje :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['mensaje_id'] . "' name=\"codigo_1\" size=\"20\" onkeyup=\"xajax_BusquedaMensajeProducto(this.value),this.value=this.value.toUpperCase()\">";
        $html .= "      <div id=\"MensajeProducto\"></div>";
        $html .= "      <input type=\"hidden\" name=\"mensaje_id\" value=''>";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Codigo Min. Defensa :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['codigo_mindefensa'] . "' name=\"codigo_mindefensa\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        //$html .= "      Codigo Invima :";
        $html .= "      Registro Invima :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['codigo_invima'] . "' name=\"codigo_invima\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
        $html .= "      </td>";
        $html .= "      </tr>";
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        //$html .= "      Codigo Invima :";
        $html .= "      Estado Invima :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
		
		
        $html .= "      <select class='select' name='estado_invima'>";
	$html .= "<option value='0'>&nbsp;</option>";		
		$seleccionado = "";
		foreach($estadosInvima as $estadoInvima){
			
			if($prod["estado_invima"] == $estadoInvima["id"]){
				$seleccionado = "selected";
			} else {
				$seleccionado = "";
			}

			$html .= "<option ".$seleccionado." value='".$estadoInvima["id"]."'>".$estadoInvima["estado"]."</option>";
		}
								
		$html .= "		</select>";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Fecha Vencimiento R. Invima :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";

        $html .= "<input class=\"input-text\" id=\"calendar-field\" value='" . $prod['vencimiento_codigo_invima'] . "' name=\"vencimiento_codigo_invima\" readOnly=\"true\"><input class=\"input-submit\"value=\"...\" type=\"button\" id=\"calendar-trigger\" onclick=\"calendario()\">";

        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      Titular Reg. Invima :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['titular'] . "' name=\"codigo_2\" size=\"10\" onkeyup=\"xajax_BusquedaTitular(this.value),this.value=this.value.toUpperCase()\"> -- Buscar";
        $html .= "		<input type=\"hidden\" name=\"descripcion_titular_reginvima\" id =\"descripcion_titular_reginvima\" >";
		$html .= "      <div id=\"titular\"></div>";
        $html .= "      </td>";
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
        $html .= "      %Iva :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"30%\">";
        $html .= "      <input class=\"input-text\" type=\"text\" value='" . $prod['porc_iva'] . "' name=\"porc_iva\" size=\"2\" onkeypress=\"return acceptNum(event)\" >%";
        $html .= "      </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Generico :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        if ($prod['sw_generico'] == 1) {
            $html .= '      <input class="input-radio" type="radio" name="sw_generico" value="1" checked> Si 
		<input class="radio" type="radio" name="sw_generico" value="0"> No </td>';
        } else {
            $html .= '      <input class="input-radio" type="radio" name="sw_generico" value="1"> Si 
		<input class="radio" type="radio" name="sw_generico" value="0"  checked> No </td>';
        }
        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Venta Directa :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        if ($prod['sw_venta_directa'] == 1) {
            $html .= '      <input class="input-radio" type="radio" name="sw_venta_directa" value="1" checked> Si 
		<input class="radio" type="radio" name="sw_venta_directa" value="0"> No </td>';
        } else {
            $html .= '      <input class="input-radio" type="radio" name="sw_venta_directa" value="1"> Si 
		<input class="radio" type="radio" name="sw_venta_directa" value="0" checked> No </td>';
        }

        $html .= "      </tr>";


        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Tipo de Producto :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        $html .= $SelectTipo_producto;
        $html .= "      </tr>";

        //adicion: codigo administrativo presentacion
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Codigo Administrativo Presentacion :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        $html .= $Selectpresenta;
        $html .= "      </tr>";
        //fin adicion

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      TRATAMIENTOS ESPECIALES :";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        $html .= $selectT;
        $html .= "      </tr>";
 
        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "      Regulado :.";
        $html .= "      </td>";
        $html .= "      <td align=\"left\" width=\"10%\">";
        if ($prod['sw_regulado'] == 1) {
            $html .= '      <input class="input-radio" type="radio" name="sw_regulado" value="1" checked> Si 
		<input class="radio" type="radio" name="sw_regulado" value="0"> No </td>';
        } else {
            $html .= '      <input class="input-radio" type="radio" name="sw_regulado" value="1"> Si 
		<input class="radio" type="radio" name="sw_regulado" value="0" checked> No </td>';
        }

        $html .= "      </tr>";

        $lista_tipos_riesgos = $sql->consultar_tipos_riesgos();



        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "          <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "              Tipo de Riesgo :";
        $html .= "          </td>";
        $html .= "          <td align=\"left\" width=\"10%\">";
        $html .= "              <select class='select' id='tipo_riesgo' name = 'tipo_riesgo' onchange='mostrar_descripcion_tipo_riesgo()'>";
        $html .= "                  <option value=''>-- Seleccione --</option>";
        
      
        
        foreach ($lista_tipos_riesgos as $key => $value) {
            $selected = "";
            if($value['id']==$prod['tipo_riesgo_id']){
                $selected = " selected ";
            }
            $html .= "                  <option value='{$value['id']}' {$selected} >{$value['codigo']}</option>";
        }
        $html .= "              </select>";

        
        foreach ($lista_tipos_riesgos as $key => $value) {
            $display = "display:none";
            if($value['id']==$prod['tipo_riesgo_id']){
                $display = "display:block";
            }
            
            $html .= "              <div id='descripcion_tipo_riesgo_{$value['id']}' name='descripcion_tipo_riesgo' style='{$display}'>
                                        {$value['descripcion']}";
            $html .= "              </div>";
        }
        $html .= "          </td>";
        $html .= "      </tr>";

        $empcosmitet = ModuloGetVar('app', 'Inv_CodificacionProductos', 'empresa_Cosmitet');

        if ($_SESSION['empresa_id'] == $empcosmitet) {

            $html .= "      <tr class=\"modulo_list_claro\">";
            $html .= "          <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
            $html .= "              FORMULACION CLINICA :";
            $html .= "          </td>";
            $html .= "          <td align=\"left\" width=\"10%\">";
            $html .= '              <input type="text" name="estado_unico" id="estado_unico" value="' . $prod['estado_unico'] . '">';
            $html .= "          </td>";
            $html .= "      </tr>";

            $sel_0 = "";
            $sel_1 = "";

            if ($prod['estado_unico'] == '1') {
                $sel_1 = "selected";
            } else {
                $sel_0 = "selected";
            }

            $html .= "      <tr class=\"modulo_list_claro\">";
            $html .= "          <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
            $html .= "              SOLICITA AUTORIZACION :";
            $html .= "          </td>";
            $html .= "          <td align=\"left\" width=\"10%\">";
            $html .= '              <select name="sw_solicita_autorizacion" id="sw_solicita_autorizacion">';
            $html .= "                  <option $sel_1 value='1'>1</option>";
            $html .= "                  <option $sel_0 value='0'>0</option>";
            $html .= "              </select>";
            $html .= "          </td>";
            $html .= "      </tr>";
        }

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
        $html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert  
        $html .= '      <input type="hidden" name="sw_medicamento" value="' . $Sw_Medicamento . '">';
        $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"mostrar_descripcion_titular(),descripcionTratamientoEspecial(),descripcionUnidadMedida(),descripcionAnatoFarmacologico(),descripcionComercialFarmaco(), setTimeout('Confirmar_1(xajax.getFormValues(\'FormularioProducto\'))', 1000)\">";
																									//onclick=\"setTimeout('window.alert(\'Hello!\')', 2000)\" >"; //
																								   //                      
        $html .= "      </td>";
        $html .= "      </tr>";
        $html .= "		</form>";
        $html .= "      </table>";


        $objResponse->assign("formulario_ingreso", "innerHTML", $html);
        $objResponse->script("tabPane.setSelectedIndex(3);");
        $objResponse->script("xajax_BusquedaFabricante('" . $prod['fabricante'] . "');");
        $objResponse->script("xajax_BusquedaAcuerdo228('" . $prod['cod_acuerdo228_id'] . "');");
        $objResponse->script("xajax_BusquedaMensajeProducto('" . $prod['mensaje_id'] . "');");
        $objResponse->script("xajax_BusquedaTitular('" . $prod['titular'] . "');");
    }
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;
}

function Modificar_ClasificacionProducto($CodigoProducto, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
    $Obj_Consultas = AutoCarga::factory("ConsultasClasificacion", "", "app", "Inv_CodificacionProductos");
    $producto = $sql->Buscar_Producto($CodigoProducto);
    // print_r($producto);
    $Grupos = $Obj_Consultas->ListadoGrupos();
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    $html .= "  <form name=\"ModificarClasificacion\" id=\"ModificarClasificacion\" method=\"post\">";
    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      CAMBIAR CLASIFICACION DE: " . $producto[0]['descripcion'] . "";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"left\" width=\"30%\">";
    $html .= "      GRUPO :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "      <select class=\"select\" style=\"width:100%\" name=\"select_grupo_id\" id=\"select_grupo_id\" onchange=\"xajax_Clase(this.value);\">";
    $html .= "      <option value=\"\">-- SELECCIONAR --</option>";
    foreach ($Grupos as $key => $valor) {
        if (trim($valor['grupo_id']) == trim($producto[0]['grupo_id_producto']))
            $selected = "selected ";
        else
            $selected = "";
        $html .= "    <option $selected value=\"" . $valor['grupo_id'] . "\">" . $valor['grupo_id'] . "-" . $valor['descripcion'] . "</option>";
    }
    $html .= "      </select>";
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"left\" width=\"30%\">";
    $html .= "      CLASE :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"70%\">";
    $html .= "      <select class=\"select\"  name=\"select_clase_id\" id=\"select_clase_id\" style=\"width:100%\" onchange=\"xajax_SubClase(document.getElementById('select_grupo_id').value,this.value);\">";
    $html .= "      <option value=\"\">-- SELECCIONAR --</option>";
    $html .= "      </select>";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"left\">";
    $html .= "      SUBCLASE :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= "      <select class=\"select\"  name=\"select_subclase_id\" id=\"select_subclase_id\" style=\"width:100%\" >";
    $html .= "      <option value=\"\">-- SELECCIONAR --</option>";
    $html .= "      </select>";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= "      <input type=\"hidden\" name=\"sw_medicamento\" id=\"sw_medicamento\" value=\"" . $Sw_Medicamento . "\">";
    $html .= "      <input type=\"hidden\" name=\"codigo_producto\" id=\"codigo_producto\" value=\"" . $CodigoProducto . "\">";
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"CAMBIAR CODIFICACION\" name=\"boton\" onclick=\"Confirmar_Modificar(xajax.getFormValues('ModificarClasificacion'),'" . $producto[0]['descripcion'] . "');\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";

    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->call("MostrarSpan");
    $objResponse->script("xajax_Clase('" . $producto[0]['grupo_id_producto'] . "','" . $producto[0]['clase_id_producto'] . "');");
    $objResponse->script("xajax_SubClase('" . $producto[0]['grupo_id_producto'] . "','" . $producto[0]['clase_id_producto'] . "','" . $producto[0]['subclase_id_producto'] . "');");
    return $objResponse;
}

function Clase($CodigoGrupo, $Parametro) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Clases = $sql->ListadoClasesxGrupo($CodigoGrupo);

    $SelectClases .= "<option value=\"\">-- SELECCIONAR --</option>";
    foreach ($Clases as $key => $cla) {
        if (trim($cla['clase_id']) == trim($Parametro))
            $selected = "selected ";
        else
            $selected = "";

        $SelectClases .= "<option $selected value=\"" . $cla['clase_id'] . "\">" . $cla['descripcion'] . "-" . $cla['clase_id'] . "</option>";
    }
    $html = $SelectClases;

    $objResponse->assign("select_clase_id", "innerHTML", $html);

    return $objResponse;
}

function SubClase($CodigoGrupo, $CodigoClase, $Parametro) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $SubClases = $sql->ListadoSubClasesConClase($CodigoGrupo, $CodigoClase);

    $SelectSubClases .= "<option value=\"\">-- SELECCIONAR --</option>";
    foreach ($SubClases as $key => $sub) {
        if (trim($sub['subclase_id']) == trim($Parametro))
            $selected = "selected ";
        else
            $selected = "";

        $SelectSubClases .= "<option $selected value='" . $sub['subclase_id'] . "'>" . $sub['descripcion'] . "-" . $sub['subclase_id'] . "</option>";
    }
    $SelectSubClases .="</select>";

    $html = $SelectSubClases;
    $objResponse->assign("select_subclase_id", "innerHTML", $html);

    return $objResponse;
}

function Guardar_NuevaClasificacion($Formulario) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos"); //cargo Funcion General para cambiar de estado un registro.

    $CodigoNuevo = $sql->Modificar_ClasificacionProducto($Formulario);
 
    if ($CodigoNuevo != false) {
        $objResponse->alert("Modificado Correctamente!");
        $sData=$Formulario['select_subclase_id'];
        $objResponse->assign("clase_producto","innerHTML", $sData);
        $objResponse->script("xajax_ModProducto('" . $CodigoNuevo['codigo_producto'] . "','" . $Formulario['sw_medicamento'] . "');");
        $objResponse->script("OcultarSpan();");
    }
    else
        $objResponse->alert("Error Al Modificar!!");


    return $objResponse;
}

/*
 * Capita del Formulario de Ingreso de Grupos
 */

function IngresoProducto($CodigoGrupo, $CodigoClase, $NombreClase, $NombreGrupo, $CodSubClase, $NombreSubClase, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");


    $PerfilesTerapeuticos = $sql->Listar_Perfiles_Terapeuticos();
    $TratamientosProductos = $sql->Listar_TratamientosProductos();

    $Producto_Id = $sql->Consecutivo_Producto($CodigoGrupo, $CodigoClase, $CodSubClase);
    $UnidadesMedida = $sql->Listar_Unidades_Medida();
	
	$estadosInvima = $sql->consultarEstadosInvima();
	
    //$ViasDeAdministracion=$sql->Listar_Vias_De_Administracion();
    $tipo_producto = $sql->Listar_Tipos_Productos();
    $paises = $sql->Listar_Paises();
    $selectpais .="<select class=\"select\" name=\"tipo_pais_id\">";
    $selectpais .="<option value=\"\">";
    $selectpais .="--Seleccionar--";
    $selectpais .="</option>";
    foreach ($paises as $key => $pais) {
        $selectpais .="<option value='" . $pais['codigo'] . "'>";
        $selectpais .=$pais['pais'];
        $selectpais .="</option>";
    }
    $selectpais .="</select>";

    //Adicion: select para rango codigo administrativo presentacion - nuevo codigo
    $Selectpresenta = '<SELECT NAME="cod_presenta" SIZE="1" class="select" style="width:40%;height:40%">';
    $Selectpresenta .="<OPTION VALUE=\"\">";
    $Selectpresenta .="--Seleccionar--";
    $Selectpresenta .="</OPTION>";
    $array = array("1", "2", "3", "4");
    foreach ($array as $valor) {
        $Selectpresenta .= '<OPTION VALUE="' . $valor . '">' . $valor . '</OPTION>';
    }
    $Selectpresenta .='</SELECT>';
    //fin adicion


    $CodigoProducto = $CodigoGrupo . "" . $CodigoClase . "" . $CodSubClase . "" . $Producto_Id[0]['producto_id'];
 
	 $SelectPerfilesTerapeuticos = '<SELECT NAME="cod_anatofarmacologico" id="cod_anatofarmacologico" SIZE="1" class="select" style="width:100%;height:100%" >';//onChange="descripcionAnatoFarmacologico()"
        foreach ($PerfilesTerapeuticos as $ky => $PerfilesTerapeuticos) {
            $SelectPerfilesTerapeuticos .= '<OPTION VALUE="' . $PerfilesTerapeuticos['codigo'] . '"';
            if ($PerfilesTerapeuticos['codigo'] == $prod['cod_anatofarmacologico'])
                $SelectPerfilesTerapeuticos .=" selected ";

            $SelectPerfilesTerapeuticos .='>' . $PerfilesTerapeuticos['descripcion'] . '-' . $PerfilesTerapeuticos['codigo'] . '</OPTION>';
        }
        $SelectPerfilesTerapeuticos .='</SELECT>';
		 
	$SelectUnidadesMedida = '<SELECT NAME="unidad_id" id="unidad_id" SIZE="1" class="select" style="width:40%;height:40%"   >';//onChange="descripcionUnidadMedida()"
        foreach ($UnidadesMedida as $ke => $Unidades) {
            $SelectUnidadesMedida .= '<OPTION VALUE="' . $Unidades['codigo'] . '"';
            if ($Unidades['codigo'] == $prod['unidad_id'])
                $SelectUnidadesMedida .=" selected ";
			
			
            $SelectUnidadesMedida .='>' . $Unidades['descripcion'] . '-' . $Unidades['codigo'] . '</OPTION>';
			
		
        }
        $SelectUnidadesMedida .='</SELECT>';
 
	$selectT = "<select name=\"tratamiento_id\" id=\"tratamiento_id\" class=\"select\" style=\"width:40%;height:40%\"> ";
        $selectT .= "		<option value=\"\">-- NO SE USA EN TRATAMIENTOS ESPECIALES --</option>";
        foreach ($TratamientosProductos as $key => $tt) {
            if (strcmp(trim($tt['tratamiento_id']), trim($prod['tratamiento_id'])) == 0)
                $selectedT = "	selected ";
            else
                $selectedT = "	";
            $selectT .= "		<option value=\"" . $tt['tratamiento_id'] . "\" " . $selectedT . ">";
            $selectT .= "			" . $tt['descripcion'];
            $selectT .= "		</option>";
        }
        $selectT .= "</select>";
		
		 
	/*Nuevo #4*/
	//Lista presentacion comercial farmacologica
		$presentacionComercialFarmacologia = $sql->Listar_Perfiles_Terapeuticos();
         $selectPresentacionComercialFarmaco = '<SELECT NAME="cod_comerfarmacologico" id="cod_comerfarmacologico" SIZE="1" class="select" style="width:100%;height:100%" >';//onChange="descripcionComercialFarmaco()"
        foreach ($presentacionComercialFarmacologia as $ky => $PreComercialFarmaco) {
            $selectPresentacionComercialFarmaco .= '<OPTION VALUE="' . $PreComercialFarmaco['codigo'] . '"';
            if ($PreComercialFarmaco['codigo'] == $prod['cod_comerfarmacologico'])
                $selectPresentacionComercialFarmaco .=" selected ";

            $selectPresentacionComercialFarmaco .='>' . $PreComercialFarmaco['descripcion'] . '-' . $PreComercialFarmaco['codigo'] . '</OPTION>';
        }
        $selectPresentacionComercialFarmaco .='</SELECT>';
	
	
	  

    $SelectTipo_producto = '<SELECT NAME="tipo_producto_id" SIZE="1" class="select" style="width:40%;height:40%">';
    $SelectTipo_producto .= '<OPTION VALUE=""></OPTION>';
    foreach ($tipo_producto as $key => $tp) {
        $SelectTipo_producto .= '<OPTION VALUE="' . $tp['codigo'] . '">' . $tp['descripcion'] . '</OPTION>';
    }
    $SelectTipo_producto .='</SELECT>';

    //Presentaciones Comerciales
    $presentacioncomercial = $sql->Listar_PresentacionesComerciales();

    $selectPresentacionComercial .="<select class=\"select\" name=\"presentacioncomercial_id\" style=\"width:40%;height:40%\">";

    foreach ($presentacioncomercial as $key => $pc) {
        $selectPresentacionComercial .="<option value='" . $pc['codigo'] . "'>";
        $selectPresentacionComercial .=$pc['descripcion'];
        $selectPresentacionComercial .="</option>";
    }
    $selectPresentacionComercial .="</select>";

   


    $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioProducto\" id=\"FormularioProducto\" method=\"post\">";
	
    $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Creacion de Producto Nuevo ";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Grupo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $CodigoGrupo . " - " . $NombreGrupo;
    $html .= "      <input type=\"hidden\" name=\"grupo_id\" value='" . $CodigoGrupo . "'>";
    $html .= "      <input type=\"hidden\" name=\"sw_medicamento\" value=\"" . $Sw_Medicamento . "\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Clase :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $CodigoClase . " - " . $NombreClase;
    $html .= "      <input type=\"hidden\" name=\"clase_id\" value='" . $CodigoClase . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      SubClase :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $CodSubClase . " - " . $NombreSubClase;
    $html .= "      <input type=\"hidden\" name=\"subclase_id\" value='" . $CodSubClase . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Consecutivo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" value='" . $Producto_Id[0]['producto_id'] . "' name=\"producto_id\" size=\"10\" readOnly=\"true\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Producto :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" value='" . $CodigoProducto . "' name=\"codigo_producto\" size=\"20\" readOnly=\"true\">";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Descripcion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"descripcion\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Descripcion Abreviada (Para Impresion en POS) :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"descripcion_abreviada\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "      </td>";
    $html .= "      </tr>";

    //adicion : Codigo DIC - Denominacion Comun Internacional 
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Denominacion Comun Internacional (DCI) :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= "       <input class=\"input-text\" type=\"text\" name=\"dci\" size=\"50\" value=\"0\">  ";
    $html .= "      </tr>";
    //fin adicion cod DCI.			

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo CUM :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_cum\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    
	
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Barras :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_barras\" size=\"30\"  onkeyup=\"getKey(event,this.value);\">";
    $html .= "      <div id=\"MensajeBarras\"></div>";
    $html .= "      </td>";
    $html .= "      </tr>";
	
	$html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo IUM :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_alterno\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "      </td>";
    $html .= "      </tr>";
	
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Fabricante :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_3\" style=\"width:70%\" onkeyup=\"xajax_BusquedaFabricante(this.value),this.value=this.value.toUpperCase()\"> -- Buscar";
    $html .= "      <div id=\"fabricante\"></div>";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Pais de Fabricacion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $selectpais;
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Producto POS :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_pos" value="1" onclick="VerificarPos(true)"> Si 
			<input class="radio" type="radio" name="sw_pos" value="0" onclick="VerificarPos(false)"> No </td>';
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    //$html .= "      Codigo. Acuerdo 228 :";
    $html .= "      Acuerdo 029 POS :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo\" id=\"codigo\" size=\"20\" disabled = \"disabled\" onkeyup=\"xajax_BusquedaAcuerdo228(this.value),this.value=this.value.toUpperCase()\">";
    $html .= "      <div id=\"acuerdo_228\"></div>";
    $html .= "      <input type=\"hidden\" name=\"cod_acuerdo228_id\" id=\"cod_acuerdo228_id\"  value='' disabled='disabled'>";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      RIPS No POS :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"rips_no_pos\" id=\"rips_no_pos\" size=\"20\" disabled = \"disabled\">";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Unidad Medida X Cantidad (Ej: Tableta Por 500MG) :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $SelectUnidadesMedida;
    $html .= "  POR   <input class=\"input-text\" type=\"text\" name=\"cantidad\" size=\"15\" onkeyup=\"this.value=this.value.toUpperCase()\" > ";
	$html .= "	<input type=\"hidden\" name=\"unidadMedidaDescripcion\" id=\"unidadMedidaDescripcion\">";
    $html .= "      </td>";
    $html .= "      </tr>";

	
	
	
	
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Presentacion Comercial (Ej: Caja por 3) :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "        " . $selectPresentacionComercial;
    $html .= "  POR   <input class=\"input-text\" type=\"text\" name=\"cantidad_p\" size=\"15\" onkeypress=\"return acceptNum(event)\"> ";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Perfil Terapeutico :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $SelectPerfilesTerapeuticos;
	$html .= "	<input type=\"hidden\" name=\"anatoFarmacologicoDescripcion\" id=\"anatoFarmacologicoDescripcion\" >";
    $html .= "      </td>";
    $html .= "      </tr>";
    
	


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Mensaje :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_1\" size=\"20\" onkeyup=\"xajax_BusquedaMensajeProducto(this.value),this.value=this.value.toUpperCase()\">";
    $html .= "      <div id=\"MensajeProducto\"></div>";
    $html .= "      <input type=\"hidden\" name=\"mensaje_id\" value=''>";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Min. Defensa :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_mindefensa\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    //$html .= "      Codigo Invima :";
    $html .= "      Registro Invima :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_invima\" size=\"30\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "      </td>";
    $html .= "      </tr>";
	
	$html .= "      <tr class=\"modulo_list_claro\">";
	$html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
	//$html .= "      Codigo Invima :";
	$html .= "      Estado Invima :";
	$html .= "      </td>";
	$html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <select class='select' name='estado_invima'>";
		
	foreach($estadosInvima as $estadoInvima){
		$html .= "<option value='".$estadoInvima["id"]."'>".$estadoInvima["estado"]."</option>";
	}
								
    $html .= "		</select>";
	$html .= "      </td>";
	$html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Fecha Vencimiento R. Invima :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";

    $html .= "<input class=\"input-text\" id=\"calendar-field\" name=\"vencimiento_codigo_invima\" readOnly=\"true\"><input class=\"input-submit\"value=\"...\" type=\"button\" id=\"calendar-trigger\" onclick=\"calendario()\">";

    $html .= "      </td>";
    $html .= "      </tr>";
	 
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
		$html .= "      Titular Reg. Invima :";
		$html .= "      </td>";
		$html .= "      <td align=\"left\" width=\"30%\">";
		$html .= "      <input class=\"input-text\" type=\"text\" name=\"codigo_2\" size=\"10\" onkeyup=\"xajax_BusquedaTitular(this.value),this.value=this.value.toUpperCase()\"> -- Buscar";
		$html .= "		<input type=\"hidden\" name=\"descripcion_titular_reginvima\" id =\"descripcion_titular_reginvima\" >"; 
		$html .= "      <div id=\"titular\"></div>";
		$html .= "      </td>";
		$html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      %Iva :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"porc_iva\" size=\"2\" onkeypress=\"return acceptNum(event)\" >%";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Generico :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_generico" value="1"> Si 
			<input class="radio" type="radio" name="sw_generico" value="0"> No </td>';
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Venta Directa :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_venta_directa" value="1"> Si 
			<input class="radio" type="radio" name="sw_venta_directa" value="0"> No </td>';
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Tipo de Producto :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= $SelectTipo_producto;
    $html .= "      </tr>";

    //adicion : codigo administrativo presentacion
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo Administrativo Presentacion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= $Selectpresenta;
    $html .= "      </tr>";
    //fin adicion cod.
 
	/*Nuevo 2*/
	$html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      TRATAMIENTOS ESPECIALES :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $selectT;
    $html .= "	<input type=\"hidden\" name=\"tratamientosEspecialesDescripcion\" id=\"tratamientosEspecialesDescripcion\">";
    $html .= "      </td>";
    $html .= "      </tr>";
 

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Regulado :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_regulado" value="1"> Si 
			<input class="radio" type="radio" name="sw_regulado" value="0"> No </td>';
    $html .= "      </tr>";

    $lista_tipos_riesgos = $sql->consultar_tipos_riesgos();



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "          <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "              Tipo de Riesgo :";
    $html .= "          </td>";
    $html .= "          <td align=\"left\" width=\"10%\">";
    $html .= "              <select class='select' id='tipo_riesgo' name = 'tipo_riesgo' onchange='mostrar_descripcion_tipo_riesgo()'>";
    $html .= "                  <option value=''>-- Seleccione --</option>";
    foreach ($lista_tipos_riesgos as $key => $value) {
        $html .= "                  <option value='{$value['id']}'>{$value['codigo']}</option>";
    }
    $html .= "              </select>";

    foreach ($lista_tipos_riesgos as $key => $value) {
        $html .= "              <div id='descripcion_tipo_riesgo_{$value['id']}' name='descripcion_tipo_riesgo' style='display:none'>
                                        {$value['descripcion']}";
        $html .= "              </div>";
    }
    $html .= "          </td>";
    $html .= "      </tr>";


    $empcosmitet = ModuloGetVar('app', 'Inv_CodificacionProductos', 'empresa_Cosmitet');

    if ($_SESSION['empresa_id'] == $empcosmitet) {

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "          <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "              FORMULACION CLINICA :";
        $html .= "          </td>";
        $html .= "          <td align=\"left\" width=\"10%\">";
        $html .= '              <input type="text" name="estado_unico" id="estado_unico" value="0">';
        $html .= "          </td>";
        $html .= "      </tr>";

        $html .= "      <tr class=\"modulo_list_claro\">";
        $html .= "          <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
        $html .= "              SOLICITA AUTORIZACION :";
        $html .= "          </td>";
        $html .= "          <td align=\"left\" width=\"10%\">";
        $html .= '              <select name="sw_solicita_autorizacion" id="sw_solicita_autorizacion">';
        $html .= "                  <option value='1'>1</option>";
        $html .= "                  <option value='0'>0</option>";
        $html .= "              </select>";
        $html .= "          </td>";
        $html .= "      </tr>";
    }
	
	
	
	
	

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" maxlength="20" value="1">'; //esto es para definir si es Update o Insert  
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"mostrar_descripcion_titular(),descripcionUnidadMedida(),descripcionTratamientoEspecial(),descripcionAnatoFarmacologico(),setTimeout('Confirmar_1(xajax.getFormValues(\'FormularioProducto\'))', 1000)\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";



    //$objResponse->assign("IngresoProductos","innerHTML",$html);
    //$objResponse->assign("Contenido","innerHTML",$html);
    $objResponse->assign("formulario_ingreso", "innerHTML", $html);
    $objResponse->script("tabPane.setSelectedIndex(3);");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->script("xajax_BusquedaFabricante();");
    $objResponse->script("xajax_BusquedaTitular();");

    //$objResponse->call("MostrarSpan");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;
}

/*
 * Capita del Formulario de Ingreso de Medicamentos
 */

function ModProductoMedicamento($Formulario_Productos) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");
    $medicamento = $sql->Buscar_Medicamento($Formulario_Productos['codigo_producto']);
    $numero = count($medicamento);
		
    $concentracion_forma_farmacologica = $medicamento[0]['concentracion_forma_farmacologica'];
    if($numero==0 || $medicamento[0]['concentracion_forma_farmacologica']==""){
       $concentracion_forma_farmacologica = $Formulario_Productos['cantidad'];
    }

    $NivelUsoMedicamento = $sql->Medicamento_NivelUso_Atencion("inv_producto_x_nivel_de_uso", $Formulario_Productos['codigo_producto'], "nivel_de_uso_id");
    $NivelAtencionMedicamento = $sql->Medicamento_NivelUso_Atencion("inv_producto_x_nivel_atencion", $Formulario_Productos['codigo_producto'], "nivel");

    $PresentacionComercial = $sql->Listar_Unidades_Medida();

    $SelectPresentacionComercial = '<SELECT NAME="cod_forma_farmacologica" SIZE="1" class="select" style="width:60%;height:60%">';
    foreach ($PresentacionComercial as $key => $PresentacionComercial) {
        $SelectPresentacionComercial .= '<OPTION VALUE="' . $PresentacionComercial['codigo'] . '"';
       
        if ($PresentacionComercial['codigo'] == $Formulario_Productos['unidad_id']) {
          
            $selected = "selected";
        } else {
            $selected = "";
        }
        $SelectPresentacionComercial .= $selected . '>' . $PresentacionComercial['codigo'] . " " . $PresentacionComercial['descripcion'] . '</OPTION>';
    }
    $SelectPresentacionComercial .='</SELECT>';
	
	
	//Listar_Unidades_MedidaMedicamentos
	$descripcionMedidaMedicamento = $sql->Listar_Unidades_Medida();

    $SelectDesMedidaMedicamento = '<SELECT NAME="cod_medida_medicamento" SIZE="1" class="select" style="width:60%;height:60%">';
    foreach ($descripcionMedidaMedicamento as $key => $descripcionMedidaMedicamento) {
        $SelectDesMedidaMedicamento .= '<OPTION VALUE="' . $descripcionMedidaMedicamento['descripcion'] . '"';
       
        if ($descripcionMedidaMedicamento['codigo'] == $Formulario_Productos['unidad_id']) {
          
            $selected = "selected";
        } else {
            $selected = "";
        }
        $SelectDesMedidaMedicamento .= $selected . '>' . $descripcionMedidaMedicamento['codigo'] . " " . $descripcionMedidaMedicamento['descripcion'] . '</OPTION>';
    }
    $SelectDesMedidaMedicamento .='</SELECT>';
	
    $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioProductoMedicamento\" id=\"FormularioProductoMedicamento\" method=\"post\">";
    $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      *SS1* Creacion de Producto -> Medicamento";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Producto :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo_producto\" readOnly=\"true\" value='" . $Formulario_Productos['codigo_producto'] . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Descripcion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"descripcion\" readOnly=\"true\" value='" . $Formulario_Productos['descripcion'] . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Presentacion Comercial :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $SelectPresentacionComercial;
    $html .= "      </td>";
    $html .= "      </tr>";
	
	/********************************************************************************************/
	//NUEVO CAMPO 31/12/2015
		/*$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
		$html .= "      Descripcion medida medicamento :";
		$html .= "      </td>";
		$html .= "      <td align=\"left\" width=\"30%\">";
		$html .= $SelectDesMedidaMedicamento;
		$html .= "      </td>";
		$html .= "      </tr>";*/
	
		$html .= "<input type=\"hidden\" name=\"anatoFarmacologicoDescripcionM\" id=\"anatoFarmacologicoDescripcionM\" value='" . $Formulario_Productos['cod_anatofarmacologico'] . "'>";
		$html .= "<input type=\"hidden\" name=\"codigo_cumM\" id=\"codigo_cumM\" value='" . $Formulario_Productos['codigo_cum'] . "'>";
		$html .= "<input type=\"hidden\" name=\"comercialFarmacoDescripcionM\" id=\"comercialFarmacoDescripcionM\" value='" . $Formulario_Productos['comercialFarmacoDescripcion'] . "'>";
		$html .= "<input type=\"hidden\" name=\"cod_comerfarmacologicoM\" id=\"cod_comerfarmacologicoM\" value='" . $Formulario_Productos['presentacioncomercial_id'] . "'>";
		
	
	/********************************************************************************************/
	
	
    $PrincipiosActivos = $sql->Listar_PrincipiosActivos();
	
    
//	echo "<pre>1) ".$Formulario_Productos['subclase_id']; 
//	echo "<pre>2) ".$Formulario_Productos['codigo_producto']; 
	//echo "<pre>3) ".$pa['cod_principio_activo']; 
//	print_r($medicamento);	
    $SelectPrincipiosActivos = '<SELECT ' . $disabled . ' NAME="cod_principio_activo" id="cod_principio_activo" SIZE="1" class="select" style="width:60%;height:60%" onChange="descripcionPrincipioActivo()">';
    foreach ($PrincipiosActivos as $key => $pa) {
	//echo "2!!!!!!!!!!!!! ".$pa['cod_principio_activo'];
        if($Formulario_Productos['subclase_id']==$pa['cod_principio_activo'])
       // if ($medicamento[0]['cod_principio_activo'] == $pa['cod_principio_activo'])
            $selected = "selected";
        else
            $selected = "";
        $SelectPrincipiosActivos .= '<OPTION VALUE="' . $pa['cod_principio_activo'] . '" ' . $selected . '>' . $pa['cod_principio_activo'] . ' - ' . $pa['descripcion'] . '  </OPTION>';
    }
    $SelectPrincipiosActivos .='</SELECT>';
	
	$html .= "	<input type=\"hidden\" name=\"principioActivoDescripcion\" id=\"principioActivoDescripcion\">";
	
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Principio Activo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      " . $SelectPrincipiosActivos;
    $html .= $hidden;
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Concentracion (ej 500 MG):";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"concentracion\" value='" . $concentracion_forma_farmacologica . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";




    $CodigosConcentracion = $sql->Listar_CodigosConcentracion();
    $selectcodconcentracion .="<select class=\"select\" name=\"cod_concentracion\">";
    $selectcodconcentracion .="<option value=\"\">";
    $selectcodconcentracion .="--Seleccionar--";
    $selectcodconcentracion .="</option>";
    $selected = "";
    foreach ($CodigosConcentracion as $key => $cc) {
        if ($cc['codigo'] == $medicamento[0]['cod_concentracion'])
            $selected = "selected";
        else
            $selected = "";
        $selectcodconcentracion .="<option value='" . $cc['codigo'] . "' " . $selected . ">";
        $selectcodconcentracion .=$cc['codigo'] . " ";
        //$selectcodconcentracion .=$cc['codigo']." ".$cc['descripcion'];
        $selectcodconcentracion .="</option>";
    }
    $selectcodconcentracion .="</select>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Concentracion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $selectcodconcentracion;
    $html .= "      </td>";
    $html .= "      </tr>";

   
    //Campos del anterior Formulario para anexarlo con el nuevo formulario y enviarlo a la BD
    $html .= "      <input type=\"hidden\" name=\"grupo_id\" value='" . $Formulario_Productos['grupo_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"clase_id\" value='" . $Formulario_Productos['clase_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"subclase_id\" value='" . $Formulario_Productos['subclase_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"producto_id\" value='" . $Formulario_Productos['producto_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"descripcion_abreviada\" value='" . $Formulario_Productos['descripcion_abreviada'] . "'";
    $html .= "      <input type=\"hidden\" name=\"codigo_cum\" value='" . $Formulario_Productos['codigo_cum'] . "'";
    $html .= "      <input type=\"hidden\" name=\"codigo_alterno\" value='" . $Formulario_Productos['codigo_alterno'] . "'";
    $html .= "      <input type=\"hidden\" name=\"codigo_barras\" value='" . $Formulario_Productos['codigo_barras'] . "'";
    $html .= "      <input type=\"hidden\" name=\"fabricante_id\" value='" . $Formulario_Productos['fabricante_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"sw_pos\" value='" . $Formulario_Productos['sw_pos'] . "'";
    $html .= "      <input type=\"hidden\" name=\"cod_acuerdo228_id\" value='" . $Formulario_Productos['cod_acuerdo228_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"unidad_id\" value='" . $Formulario_Productos['unidad_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"cantidad\" value='" . $Formulario_Productos['cantidad'] . "'";
    $html .= "      <input type=\"hidden\" name=\"cod_anatofarmacologico\" value='" . $Formulario_Productos['cod_anatofarmacologico'] . "'";
    $html .= "      <input type=\"hidden\" name=\"mensaje_id\" value='" . $Formulario_Productos['mensaje_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"codigo_mindefensa\" value='" . $Formulario_Productos['codigo_mindefensa'] . "'";
    $html .= "      <input type=\"hidden\" name=\"codigo_invima\" value='" . $Formulario_Productos['codigo_invima'] . "'";
    $html .= "      <input type=\"hidden\" name=\"vencimiento_codigo_invima\" value='" . $Formulario_Productos['vencimiento_codigo_invima'] . "'";
    $html .= "      <input type=\"hidden\" name=\"titular_reginvima_id\" value='" . $Formulario_Productos['titular_reginvima_id'] . "'";
	$html .= "      <input type=\"hidden\" name=\"descripcion_titular_reginvima_id\" id=\"descripcion_titular_reginvima_id\" value='" . $Formulario_Productos['descripcion_titular_reginvima'] . "'";
    $html .= "      <input type=\"hidden\" name=\"porc_iva\" value='" . $Formulario_Productos['porc_iva'] . "'";
    $html .= "      <input type=\"hidden\" name=\"sw_generico\" value='" . $Formulario_Productos['sw_generico'] . "'";
    $html .= "      <input type=\"hidden\" name=\"sw_venta_directa\" value='" . $Formulario_Productos['sw_venta_directa'] . "'";
    $html .= "      <input type=\"hidden\" name=\"tipo_pais_id\" value='" . $Formulario_Productos['tipo_pais_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"tipo_producto_id\" value='" . $Formulario_Productos['tipo_producto_id'] . "'";
    $html .= "      <input type=\"hidden\" name=\"cantidad_p\" value='" . $Formulario_Productos['cantidad_p'] . "'";
    $html .= "      <input type=\"hidden\" name=\"presentacioncomercial_id\" value='" . $Formulario_Productos['presentacioncomercial_id'] . "'";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Manejo de Luz :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_fotosensible'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_manejo_luz" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_manejo_luz" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_manejo_luz" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_manejo_luz" value="0" checked> No </td>';
    }
    $html .= "      </tr>";




    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Liquidos Electrolitos :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_liquidos_electrolitos'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_liquidos_electrolitos" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_liquidos_electrolitos" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_liquidos_electrolitos" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_liquidos_electrolitos" value="0" checked> No </td>';
    }
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Uso Controlado    :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_uso_controlado'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_uso_controlado" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_uso_controlado" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_uso_controlado" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_uso_controlado" value="0" checked> No </td>';
    }
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      *s1* Antibiotico    :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_antibiotico'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_antibiotico" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_antibiotico" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_antibiotico" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_antibiotico" value="0" checked> No </td>';
    }
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Refrigerado    :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_refrigerado'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_refrigerado" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_refrigerado" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_refrigerado" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_refrigerado" value="0" checked> No </td>';
    }
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Alimento Parenteral   :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_alimento_parenteral'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_alimento_parenteral" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_alimento_parenteral" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_alimento_parenteral" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_alimento_parenteral" value="0" checked> No </td>';
    }
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Alimento Enteral   :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    if ($medicamento[0]['sw_alimento_enteral'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_alimento_enteral" value="1" checked> Si 
	<input class="input-radio" type="radio" name="sw_alimento_enteral" value="0"> No </td>';
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_alimento_enteral" value="1"> Si 
	<input class="input-radio" type="radio" name="sw_alimento_enteral" value="0" checked> No </td>';
    }
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "     Dias Previos al Vencimiento   :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"dias_previos_vencimiento\" value='" . $medicamento[0]['dias_previos_vencimiento'] . "' onkeypress=\"return acceptNum(event)\">";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Farmacovigilancia :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";

    if ($medicamento[0]['sw_farmacovigilancia'] == "1") {
        $html .= '      <input class="input-radio" type="radio" name="sw_farmacovigilancia" value="1" onclick="mostrardiv();" checked> Si 
	<input class="input-radio" type="radio" name="sw_farmacovigilancia" value="0" onclick="cerrar();" > No';
        $html .= "      <div id=\"FarmacoVigilancia\" style=\"display:\"\";\"><center>Descripcion de Farmacovigilancia<br><TEXTAREA class=\"textarea\" COLS=30 ROWS=5 NAME=\"descripcion_alerta\" onkeyup=\"this.value=this.value.toUpperCase()\">" . $medicamento[0]['descripcion_alerta'] . "</TEXTAREA> </center></div>
	</td>";
    } else {
        $html .= '      <input class="input-radio" type="radio" name="sw_farmacovigilancia" value="1" onclick="mostrardiv();"> Si 
	<input class="input-radio" type="radio" name="sw_farmacovigilancia" value="0" onclick="cerrar();"  checked> No';
        $html .= "      <div id=\"FarmacoVigilancia\" style=\"display:none;\"><center>Descripcion de Farmacovigilancia<br><TEXTAREA class=\"textarea\" COLS=30 ROWS=5 NAME=\"descripcion_alerta\" onkeyup=\"this.value=this.value.toUpperCase()\"></TEXTAREA> </center></div>
	</td>";
    }

    $html .= "      </tr>";

	/**
	* +Descripcion: Niveles de uso al momento de actualizar el medicamento
	* @nombre: Cristian Ardila
	* fecha: 04/01/2016
	**/
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Niveles de Atencion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";

    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAI\" value=\"1\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion' )\" ";
    if ($NivelAtencionMedicamento[0]['nivel'] == "1" || $NivelAtencionMedicamento[1]['nivel'] == "1" || $NivelAtencionMedicamento[2]['nivel'] == "1" || $NivelAtencionMedicamento[3]['nivel'] == "1") {
        $html .= ' checked ';
    }
    $html .= '      > NIVEL I<br>';

	
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAII\" value=\"2\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\" ";
    if ($NivelAtencionMedicamento[0]['nivel'] == "2" || $NivelAtencionMedicamento[1]['nivel'] == "2" || $NivelAtencionMedicamento[2]['nivel'] == "2" || $NivelAtencionMedicamento[3]['nivel'] == "2") {
        $html .= ' checked ';
    }
    $html .= '      > NIVEL II<br>';
	
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAIII\" value=\"3\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\" ";
    if ($NivelAtencionMedicamento[0]['nivel'] == "3" || $NivelAtencionMedicamento[1]['nivel'] == "3" || $NivelAtencionMedicamento[2]['nivel'] == "3" || $NivelAtencionMedicamento[3]['nivel'] == "3") {
        $html .= ' checked ';
    }
    $html .= '      > NIVEL III<br>';
	
	
      $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAIV\"  value=\"4\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\" ";
    if ($NivelAtencionMedicamento[0]['nivel'] == "4" || $NivelAtencionMedicamento[1]['nivel'] == "4" || $NivelAtencionMedicamento[2]['nivel'] == "4" || $NivelAtencionMedicamento[3]['nivel'] == "4") {
        $html .= ' checked ';
    }
    $html .= '      > NIVEL IV<br>';
    $html .= '      <input type="hidden" name="nivel_i" value="">';
    $html .= '      <input type="hidden" name="nivel_ii" value="" >';
    $html .= '      <input type="hidden" name="nivel_iii" value="">';
    $html .= '      <input type="hidden" name="nivel_iv" value="">';

    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Niveles de Uso :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";

    //$html .= $NivelUsoMedicamento[0]['nivel_de_uso_id']."-".$NivelUsoMedicamento[1]['nivel_de_uso_id'];
	
	  $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUH\" value=\"01\" onclick=\"incluir(this.value, 'actualizarNivelesUso' )\" ";
    //$html .= '      <input class=\"input-checkbox\" type="checkbox"   name="NUH"   value="01"   onclick="incluir(this.value)"';
    if ($NivelUsoMedicamento[0]['nivel_de_uso_id'] == "01" || $NivelUsoMedicamento[1]['nivel_de_uso_id'] == "01" || $NivelUsoMedicamento[2]['nivel_de_uso_id'] == "01" || $NivelUsoMedicamento[3]['nivel_de_uso_id'] == "01") {
        $html .= ' checked ';
    }
    $html .= '        > HOSPITALARIO<br>';
	
      $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUE\" value=\"02\" onclick=\"incluir(this.value, 'actualizarNivelesUso' )\" ";
    if ($NivelUsoMedicamento[0]['nivel_de_uso_id'] == "02" || $NivelUsoMedicamento[1]['nivel_de_uso_id'] == "02" || $NivelUsoMedicamento[2]['nivel_de_uso_id'] == "02" || $NivelUsoMedicamento[3]['nivel_de_uso_id'] == "02")
        $html .= ' checked ';
    $html .= '      > ESPECIAL<br>';
	
      $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUG\" value=\"03\" onclick=\"incluir(this.value, 'actualizarNivelesUso' )\" ";
    if ($NivelUsoMedicamento[0]['nivel_de_uso_id'] == "03" || $NivelUsoMedicamento[1]['nivel_de_uso_id'] == "03" || $NivelUsoMedicamento[2]['nivel_de_uso_id'] == "03" || $NivelUsoMedicamento[3]['nivel_de_uso_id'] == "03")
        $html .= ' checked ';
    $html .= '       > GENERAL<br>';
	
	  $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUC\" value=\"04\" onclick=\"incluir(this.value, 'actualizarNivelesUso' )\" ";
    if ($NivelUsoMedicamento[0]['nivel_de_uso_id'] == "04" || $NivelUsoMedicamento[1]['nivel_de_uso_id'] == "04" || $NivelUsoMedicamento[2]['nivel_de_uso_id'] == "04" || $NivelUsoMedicamento[3]['nivel_de_uso_id'] == "04")
        $html .= ' checked ';
    $html .= '      > CONTROL<br>';

    $html .= '      <input type="hidden" name="nivelu_h" value="">';
    $html .= '      <input type="hidden" name="nivelu_e" value="">';
    $html .= '      <input type="hidden" name="nivelu_g" value="">';
    $html .= '      <input type="hidden" name="nivelu_c" value="">';
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Vias Administracion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= "      Vias de Administracion a Asignar (Doble Click para Asignar)";
    $html .='        <div id="viasadministracion"></div>';
    $html .= "      Vias de Administracion Asignadas (Doble Click para Borrar)";
    $html .='        <div id="viasadministracion_asignadas"></div>';
    $html .= '      <input type="hidden" name="esp_asign" value="">';
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= '      <input type="hidden" name="medicamento_vacio" value="' . $numero . '">'; //esto es para definir si es Update o Insert
    $html .= "     <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar_2(xajax.getFormValues('FormularioProductoMedicamento'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";
	
	
    $objResponse->assign("formulario_ingreso", "innerHTML", $html);
    $objResponse->script("tabPane.setSelectedIndex(3);");
    $objResponse->script("xajax_ListaViasAdministracion('" . $Formulario_Productos['codigo_producto'] . "');");
    $objResponse->script("xajax_ListaViasAdministracionxProducto('" . $Formulario_Productos['codigo_producto'] . "');");
    //$objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    //$objResponse->script("xajax_ListaEspecialidades('".$Formulario_Productos['codigo_producto']."');");
    //$objResponse->script("xajax_ListaEspecialidadxProducto('".$Formulario_Productos['codigo_producto']."');");
    $objResponse->script("incluir(0,'actualizarNivelesUso');");
    $objResponse->script("asignar(0,'actualizarNivelesAtencion');");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;
}

function GuardarNivelesDeAtencion($nivelesAtencion, $codigoProducto, $metodo, $nivel) {
	
	//print_r($nivelesAtencion . "- " . $codigoProducto . " - " . $metodo . " - " . $nivel);
    $objResponse = new xajaxResponse();
	
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo);
	
	
	$token_1_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "0");
    $token_2_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "1");
    $token_3_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "2");
    $token_4_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "3");
    $token_5_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "4");
	$token_6_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "5");
	$token_7_ws = $sql->InsertarMedicamentoNivelUsoAtencion($nivelesAtencion,(string)$codigoProducto, $metodo, "6");
    if ($token_1_ws == true) {
        $mensaje = "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en Cosmitet.";
    } else {
        $mensaje = "\nError en la Modificacion en Cosmitet.";
    }

    if ($token_2_ws == true) {
        $mensaje .= "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en Dumian.";
    } else {
        $mensaje .= "\nError en la Modificacion en Dumian.";
    }

    if ($token_3_ws == true) {
        $mensaje .= "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en CSSP.";
    } else {
        $mensaje .= "\nError en la Modificacion en CSSP.";
    }
    
    if ($token_4_ws == true) {
        $mensaje .= "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en MD.";
    } else {
        $mensaje .= "\nError en la Modificacion en MD.";
    }
    
    if ($token_5_ws == true) {
        $mensaje .= "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en CMS.";
    } else {
        $mensaje .= "\nError en la Modificacion en CMS.";
    }
	
	if ($token_6_ws == true) {
        $mensaje .= "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en PENITAS.";
    } else {
        $mensaje .= "\nError en la Modificacion en PENITAS.";
    }
	
	if ($token_7_ws == true) {
        $mensaje .= "\nSe almacenan los niveles de ".$nivel." satisfactoriamente en CARTAGENA.";
    } else {
        $mensaje .= "\nError en la Modificacion en CARTAGENA.";
    }

	if ($token_1 == true) {
       // $objResponse->call("Cerrar('Contenedor')");
       // $objResponse->script("xajax_Productos_Creados();");
        $objResponse->alert($mensaje);
       
    }
	
    else
        $objResponse->alert($mensaje);


    return $objResponse;
}

/*
 * Capita del Formulario de Ingreso de Medicamentos
 */

function IngresoProductoMedicamento($Formulario_Productos) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");


    $html .= "  <table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioProductoMedicamento\" id=\"FormularioProductoMedicamento\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      *SS2* Creacion de Producto -> Medicamento";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Producto :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo_producto\" readOnly=\"true\" value='" . $Formulario_Productos['codigo_producto'] . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Descripcion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"descripcion\" readOnly=\"true\" value='" . $Formulario_Productos['descripcion'] . "'>";
    $html .= "      </td>";
    $html .= "      </tr>";


    $selected = "";
    $unidades_medicamentos = $sql->Listar_Unidades_Medida();
    $SelectPresentacionComercial = '<SELECT NAME="cod_forma_farmacologica" SIZE="1" class="select" style="width:60%;height:60%">';
    foreach ($unidades_medicamentos as $key => $PresentacionComercial) {
        if ($PresentacionComercial['codigo'] == $Formulario_Productos['unidad_id']) {
            $selected = " selected ";
        } else {
            $selected = "";
        }

        $SelectPresentacionComercial .= '<OPTION ' . $selected . ' VALUE="' . $PresentacionComercial['codigo'] . '">' . $PresentacionComercial['codigo'] . ' ' . $PresentacionComercial['descripcion'] . '</OPTION>';
    }
    $SelectPresentacionComercial .='</SELECT>';


    $PrincipiosActivos = $sql->Listar_PrincipiosActivos();


    $SelectPrincipiosActivos = '<SELECT ' . $disabled . ' NAME="cod_principio_activo" SIZE="1" class="select" style="width:60%;height:60%">';
    foreach ($PrincipiosActivos as $key => $pa) {
        if ($Formulario_Productos['subclase_id'] == $pa['cod_principio_activo'])
            $selected = "selected";
        else
            $selected = "";
        $SelectPrincipiosActivos .= '<OPTION VALUE="' . $pa['cod_principio_activo'] . '"   ' . $selected . '>' . $pa['cod_principio_activo'] . ' - ' . $pa['descripcion'] . '</OPTION>';
    }
    $SelectPrincipiosActivos .='</SELECT>';

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Presentacion Comercial :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $SelectPresentacionComercial;
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Principio Activo :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      " . $SelectPrincipiosActivos;
    $html .= "		" . $hidden;
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Concentracion (ej 500 MG) 2:";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"concentracion\" value=\"" . $Formulario_Productos['cantidad'] . "\" >";
    $html .= "      </td>";
    $html .= "      </tr>";

    $CodigosConcentracion = $sql->Listar_CodigosConcentracion();
    $selectcodconcentracion .="<select class=\"select\" name=\"cod_concentracion\">";
    $selectcodconcentracion .="<option value=\"\">";
    $selectcodconcentracion .="--Seleccionar--";
    $selectcodconcentracion .="</option>";
    foreach ($CodigosConcentracion as $key => $cc) {
        $selectcodconcentracion .="<option value='" . $cc['codigo'] . "'>";
        $selectcodconcentracion .=$cc['codigo'] . " " . $cc['descripcion'];
        $selectcodconcentracion .="</option>";
    }
    $selectcodconcentracion .="</select>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"10%\">";
    $html .= "      Codigo Concentracion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"30%\">";
    $html .= $selectcodconcentracion;
    $html .= "      </td>";
    $html .= "      </tr>";

    //Campos del anterior Formulario para anexarlo con el nuevo formulario y enviarlo a la BD
    $html .= "      <input type=\"hidden\" name=\"grupo_id\" value='" . $Formulario_Productos['grupo_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"clase_id\" value='" . $Formulario_Productos['clase_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"subclase_id\" value='" . $Formulario_Productos['subclase_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"producto_id\" value='" . $Formulario_Productos['producto_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"codigo_cum\" value='" . $Formulario_Productos['codigo_cum'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"descripcion_abreviada\" value='" . $Formulario_Productos['descripcion_abreviada'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"codigo_alterno\" value='" . $Formulario_Productos['codigo_alterno'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"codigo_barras\" value='" . $Formulario_Productos['codigo_barras'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"fabricante_id\" value='" . $Formulario_Productos['fabricante_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"sw_pos\" value='" . $Formulario_Productos['sw_pos'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"cod_acuerdo228_id\" value='" . $Formulario_Productos['cod_acuerdo228_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"unidad_id\" value='" . $Formulario_Productos['unidad_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"cantidad\" value='" . $Formulario_Productos['cantidad'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"cod_anatofarmacologico\" value='" . $Formulario_Productos['cod_anatofarmacologico'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"mensaje_id\" value='" . $Formulario_Productos['mensaje_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"codigo_mindefensa\" value='" . $Formulario_Productos['codigo_mindefensa'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"codigo_invima\" value='" . $Formulario_Productos['codigo_invima'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"vencimiento_codigo_invima\" value='" . $Formulario_Productos['vencimiento_codigo_invima'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"titular_reginvima_id\" value='" . $Formulario_Productos['titular_reginvima_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"porc_iva\" value='" . $Formulario_Productos['porc_iva'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"sw_generico\" value='" . $Formulario_Productos['sw_generico'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"sw_venta_directa\" value='" . $Formulario_Productos['sw_venta_directa'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"tipo_producto_id\" value='" . $Formulario_Productos['tipo_producto_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"tipo_pais_id\" value='" . $Formulario_Productos['tipo_pais_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"presentacioncomercial_id\" value='" . $Formulario_Productos['presentacioncomercial_id'] . "'>";
    $html .= "      <input type=\"hidden\" name=\"cantidad_p\" value='" . $Formulario_Productos['cantidad_p'] . "'>";




    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Manejo de Luz :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_manejo_luz" value="1"> Si 
                    <input class="input-radio" type="radio" name="sw_manejo_luz" value="0"> No </td>';
    $html .= "      </tr>";




    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Liquidos Electrolitos :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";

    $html .= '      <input class="input-radio" type="radio" name="sw_liquidos_electrolitos" value="1" checked> Si 
                    <input class="input-radio" type="radio" name="sw_liquidos_electrolitos" value="0"> No </td>';
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Uso Controlado    :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_uso_controlado" value="1"> Si 
                    <input class="input-radio" type="radio" name="sw_uso_controlado" value="0" checked> No </td>';
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      *s2* Antibiotico    :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_antibiotico" value="1"> Si 
                    <input class="input-radio" type="radio" name="sw_antibiotico" value="0" checked> No </td>';

    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Refrigerado    :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_refrigerado" value="1"> Si 
                        <input class="input-radio" type="radio" name="sw_refrigerado" value="0" checked> No </td>';

    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Alimento Parenteral   :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";

    $html .= '      <input class="input-radio" type="radio" name="sw_alimento_parenteral" value="1"> Si 
                    <input class="input-radio" type="radio" name="sw_alimento_parenteral" value="0" checked> No </td>';

    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Alimento Enteral   :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_alimento_enteral" value="1"> Si 
                    <input class="input-radio" type="radio" name="sw_alimento_enteral" value="0" checked> No </td>';

    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "     Dias Previos al Vencimiento   :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= "      <input class=\"input-text\" type=\"text\" name=\"dias_previos_vencimiento\" onkeypress=\"return acceptNum(event)\">";
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Farmacovigilancia :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= '      <input class="input-radio" type="radio" name="sw_farmacovigilancia" value="1" onclick="mostrardiv();"> Si 
                    <input class="input-radio" type="radio" name="sw_farmacovigilancia" value="0" onclick="cerrar();" > No';

    $html .= "      <div id=\"FarmacoVigilancia\" style=\"display:none;\"><center>Descripcion de Farmacovigilancia<br><TEXTAREA class=\"textarea\" COLS=30 ROWS=5 NAME=\"descripcion_alerta\" onkeyup=\"this.value=this.value.toUpperCase()\"></TEXTAREA> </center></div>
    
    
    </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Niveles de Atencion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
	/**
	* +Descripcion: Niveles de uso al momento de crear desde cero el medicamento
	* @nombre: Cristian Ardila
	* fecha: 04/01/2016
	**/
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAI\"   value=\"1\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\"> NIVEL I<br>";
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAII\"  value=\"2\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\"> NIVEL II <br>";
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAIII\" value=\"3\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\"> NIVEL III<br>";
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NAIV\"  value=\"4\" onclick=\"asignar(this.value, 'actualizarNivelesAtencion')\"> NIVEL IV<br>";
	
    $html .= '      <input type="hidden" name="nivel_i" value=""   >';
    $html .= '      <input type="hidden" name="nivel_ii" value=""  >';
    $html .= '      <input type="hidden" name="nivel_iii" value="" >';
    $html .= '      <input type="hidden" name="nivel_iv" value="" >';

    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Niveles de Uso :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUH\"   value=\"01\" onclick=\"incluir(this.value, 'actualizarNivelesUso')\"> HOSPITALARIO<br>";
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUE\"   value=\"02\" onclick=\"incluir(this.value, 'actualizarNivelesUso')\"> ESPECIAL<br>";
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUG\"   value=\"03\" onclick=\"incluir(this.value, 'actualizarNivelesUso')\"> GENERAL<br>";
    $html .= "      <input class=\"input-checkbox\" type=\"checkbox\" name=\"NUC\"   value=\"04\" onclick=\"incluir(this.value, 'actualizarNivelesUso')\"> CONTROL<br>";
	
    $html .= '      <input type="hidden" name="nivelu_h" value="">';
    $html .= '      <input type="hidden" name="nivelu_e" value="">';
    $html .= '      <input type="hidden" name="nivelu_g" value="">';
    $html .= '      <input type="hidden" name="nivelu_c" value="">';
    $html .= "      </td>";
    $html .= "      </tr>";

    

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Vias Administracion :";
    $html .= "      </td>";
    $html .= "      <td align=\"left\" width=\"10%\">";
    $html .= "      Vias de Administracion a Asignar (Doble Click para Asignar)";
    $html .='        <div id="viasadministracion"></div>';
    $html .= "      Vias de Administracion Asignadas (Doble Click para Borrar)";
    $html .='        <div id="viasadministracion_asignadas"></div>';
    $html .= '      <input type="hidden" name="esp_asign" value="">';
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "     <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar_2(xajax.getFormValues('FormularioProductoMedicamento'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";


    //$objResponse->assign("IngresoProductos","innerHTML",$html);

    $objResponse->assign("formulario_ingreso", "innerHTML", $html);
    $objResponse->script("tabPane.setSelectedIndex(3);");
    //$objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    //$objResponse->script("xajax_ListaEspecialidades('".$Formulario_Productos['codigo_producto']."');");
    //$objResponse->script("xajax_ListaEspecialidadxProducto('".$Formulario_Productos['codigo_producto']."');");
    $objResponse->script("xajax_ListaViasAdministracion('" . $Formulario_Productos['codigo_producto'] . "');");
    $objResponse->script("xajax_ListaViasAdministracionxProducto('" . $Formulario_Productos['codigo_producto'] . "');");
    $objResponse->script("incluir(0,'actualizarNivelesUso');");
    $objResponse->script("asignar(0,'actualizarNivelesAtencion');");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;  
}




/*
 * Herramientas para el Buscador
 * Consiste en Funciones para alimentar el buscador de productos creados.
 * Carga Clases asignadas a un grupo escogido
 * Sub Clases Asignadas a una clase y Grupo.
 * 
 */

function buscar_clases_grupo($CodigoGrupo) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $Clases = $sql->ListadoClasesxGrupo($CodigoGrupo);


    $SelectClases = '<SELECT NAME="clase_id" SIZE="1" class="select" style="width:100%;height:100%">';
    $SelectClases .= '<OPTION VALUE="" onclick="xajax_buscar_subclases_clase_grupo(\'' . $CodigoGrupo . '\',this.value);"></OPTION>';
    foreach ($Clases as $key => $cla) {
        $SelectClases .= '<OPTION VALUE="' . $cla['clase_id'] . '" onclick="xajax_buscar_subclases_clase_grupo(\'' . $CodigoGrupo . '\',this.value);">' . $cla['clase_id'] . ' - ' . $cla['descripcion'] . '</OPTION>';
    }
    $SelectClases .='</SELECT>';

    $html = $SelectClases;
    $mensaje = "Seleccione Grupo y Clase...";
    $objResponse->assign("select_clases", "innerHTML", $html);
    $objResponse->assign("select_subclases", "innerHTML", $mensaje);

    return $objResponse;
}

function buscar_subclases_clase_grupo($CodigoGrupo, $CodigoClase) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $SubClases = $sql->ListadoSubClasesConClase($CodigoGrupo, $CodigoClase);


    $SelectSubClases = '<SELECT NAME="subclase_id" SIZE="1" class="select" style="width:100%;height:100%">';
    $SelectSubClases .= '<OPTION VALUE=""></OPTION>';
    foreach ($SubClases as $key => $sub) {
        $SelectSubClases .= '<OPTION VALUE="' . $sub['subclase_id'] . '">' . $sub['subclase_id'] . '-' . $sub['descripcion'] . '</OPTION>';
    }
    $SelectSubClases .='</SELECT>';

    $html = $SelectSubClases;
    $objResponse->assign("select_subclases", "innerHTML", $html);

    return $objResponse;
}

function Productos_CreadosBuscados($Grupo_Id, $Clase_Id, $SubClase_Id, $Descripcion, $CodAnatofarmacologico, $CodigoBarras, $codigo_producto, $tipo_producto, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $productos = $sql->Lista_Productos_CreadosBuscados($Grupo_Id, $Clase_Id, $SubClase_Id, $Descripcion, $CodAnatofarmacologico, $CodigoBarras, $codigo_producto, $tipo_producto, $offset);


    $action['paginador'] = "Paginador_7('" . $Grupo_Id . "','" . $Clase_Id . "','" . $SubClase_Id . "','" . $Descripcion . "','" . $CodAnatofarmacologico . "','" . $CodigoBarras . "','" . $codigo_producto . "'  ";

    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">PRODUCTOS</legend>\n";


    $html .= "  <br>";
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "      <tr>";
    $html .= "          <td style='background-color:#52955C'>";
    $html .= "          </td>";
    $html .= "          <td> = El Producto posee Factor de Conversion";
    $html .= "          </td>";
    $html .= "      </tr>";
    $html .= "  </table>";
    $html .= "  <br>";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"10%\">CODIGO-PRODUCTO</td>\n";
    $html .= "      <td width=\"5%\">GRUPO</td>\n";
    $html .= "      <td width=\"10%\">CLASE</td>\n";

    $html .= "      <td width=\"30%\">DESCRIPCION</td>\n";
    $html .= "      <td width=\"10%\">Princ.Activo/SubClase</td>\n";
    $html .= "      <td width=\"10%\">IVA</td>\n";
    $html .= "      <td width=\"10%\">MDTO</td>\n";
    $html .= "      <td width=\"10%\">REGULADO</td>\n";
    $html .= "      <td width=\"10%\">MODIFICAR</td>\n";
    $html .= "      <td width=\"10%\">FACTOR DE CONVERSI�N</td>\n";
    $html .= "      <td width=\"10%\">ESTADO</td>\n";
    //$html .= "      <td width=\"10%\">BORRAR</td>\n";


    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($productos as $key => $prod) {

        $factor_conversion = $sql->Consulta_Factor_Conversion($prod['codigo_producto']);

        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td>" . $prod['codigo_producto'] . " </td>";
        $html .= "      <td >" . $prod['grupo'] . "</td><td>" . $prod['clase'] . " </td>\n";

        $html .= "      <td>" . $prod['descripcion'] . " " . $prod['presentacion'] . " | " . $prod['presentacion_comercial'] . " </td>";
        $html .= "      <td>" . $prod['subclase'] . " </td>";
        $html .= "      <td>" . $prod['iva'] . " </td>";
        if ($prod['sw_medicamento'] == 1)
            $html .= "<td align=\"center\"><img title=\"MEDICAMENTO\" src=\"" . GetThemePath() . "/images/si.png\" border=\"0\"></td>\n";
        else
            $html .= "<td align=\"center\"><img title=\"INSUMO\" src=\"" . GetThemePath() . "/images/no.png\" border=\"0\"></td>\n";
        $html .= "<td>{$prod['producto_regulado'] }</td>";
        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ModProducto('" . $prod['codigo_producto'] . "','" . $prod['sw_medicamento'] . "')\">\n";
        $html .= "          *SI1*<img title=\"Modificar\" src=\"" . GetThemePath() . "/images/asignacion_citas.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $css_color_fondo = $est;
        if ($factor_conversion) {
            $css_color_fondo = "#52955C";
        }

        //<Inicia aqui>
        //**$html .= "  <td align=\"center\">\n";
        $html .= "  <td align=\"center\" style='background-color:$css_color_fondo'>\n";
        $javaAccionSuministros = " MostrarCapa('ContenedorFactorConversion'); IniciarCapaSum('CREAR FACTOR DE CONVERSI�N','ContenedorFactorConversion');CargarContenedor('ContenedorFactorConversion'); xajax_MostrarMedicamentosF('$prod[codigo_producto]');";
        $html .="  <input type = 'button' class=\"input-submit\" value = '...' onclick = \" $javaAccionSuministros;\">";
        $html .= "      </a>\n";
        $html .= "  </td>\n";
        //<Termina aqui>


        if ($prod['estado'] == 1) {
            $html .= "<td align=\"center\">
                                              <a href=\"#\" onclick=\"xajax_CambioEstadoProducto('inventarios_productos','estado','0','" . $prod['codigo_producto'] . "','codigo_producto')\">\n";
            $html .="<img title=\"INACTIVAR\" src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\"></a></td>\n";
        } else {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoProducto('inventarios_productos','estado','1','" . $prod['codigo_producto'] . "','codigo_producto')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a></td>\n";
        }

    }

    $html .= "    </table>\n";
    //<Inicia aqui>
    $html .= "<div id='ContenedorFactorConversion' class='d2Container' style=\"display:none\"><br>";
    $html .= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
    $html .= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorFactorConversion');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $html .= "    <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
    $html .= "    <div id='DataMedicine' class='d2Content' style=\"height:330\">\n";
//  Aqui trae el contenido de la funci�n MostrarMedicamentosF
    $html .= "    </div>\n";
    $html .= "</div>\n";
    //<Termina aqui>

    $html .= "</fieldset><br>\n";

    $objResponse->assign("Listado_Productos", "innerHTML", $html);
    return $objResponse;
}

function InsertarViadxProd($ViaAdministracion, $CodigoProducto) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->InsertarViadxProd($ViaAdministracion, $CodigoProducto);

    if ($token) {
        $objResponse->script("xajax_ListaViasAdministracion('" . $CodigoProducto . "');");
        $objResponse->script("xajax_ListaViasAdministracionxProducto('" . $CodigoProducto . "');");
    }
    else
        $objResponse->alert("Error: Revisa que no haya sido Asignado!!");


    return $objResponse;
}

function BorrarViadxProd($tabla, $id, $campo_id, $Anex, $CodigoProducto) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasCrearProductos", "classes", "app", "Inv_CodificacionProductos"); //cargo Funcion General para cambiar de estado un registro.
    $token = $sql->BorrarViadxProd($tabla, $id, $campo_id, $Anex, $CodigoProducto);

    if ($token) {
        $objResponse->script("xajax_ListaViasAdministracion('" . $CodigoProducto . "');");
        $objResponse->script("xajax_ListaViasAdministracionxProducto('" . $CodigoProducto . "');");
    }
    else
        $objResponse->alert("Error al Borrar!!");


    return $objResponse;
}

/*
 * @Autor: Jonier Murillo Hurtado
 * @Fecha: Julio 15 de 2011
 * @Observaciones: 
 *  Conjunto de Funciones necesarias para el ingreso del Factor de Conversi�n por parte de DUANA
 *  <Inicia aqui>
 */

function TraerSelecUnidadesDosificadas($Dosificacion) {

    $htmla = "                           <select name='UniDos' id='UniDos'>";
    foreach ($Dosificacion as $key => $Dos) {
        $htmla .= "                            <option value='" . $Dos[unidad_dosificacion] . "'>" . $Dos[unidad_dosificacion] . "</option>";
    }
    $htmla .= "                          </select>";

    return $htmla;
}

function InsertarFacCon($CodUni, $CodMed, $UniDos, $CanFacCon) {

    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    $Grupos = $sql->InsertFacConversion($CodUni, $CodMed, $UniDos, $CanFacCon);
//      $objResponse->alert($Grupos);
    return $objResponse;
}

function MostrarMedicamentosF($CodMedicamento) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    $Grupos = $sql->RegistrosFactorConversion($CodMedicamento);
    $Dosificacion = $sql->SelectDosificacion();


    $html = "";
    $html .= "            <table>\n";
    $i = 0;

    if (count($Grupos) > 0) {

        $html .= "                <tr class=\"formulacion_table_list\">\n";
        $html .= "                  <table>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td><input type='hidden' name='FacUniFac' id='FacUniFac' value='" . $Grupos[0][unidad_id] . "'></td>\n";
        $html .= "                        <td><input type='hidden' name='FacCodPro' id='FacCodPro' value='" . $Grupos[0][codigo_producto] . "'></td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '20%' class=\"formulacion_table_list\" align = 'left'>C�DIGO</td>\n";
        $html .= "                        <td width= '50%' align = 'left'>" . $Grupos[0][codigo_producto] . "</td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '20%' class=\"formulacion_table_list\" align = 'left'>DESCRIPCI�N</td>\n";
        $html .= "                        <td width= '50%' align = 'left'>" . $Grupos[0][despro] . "</td>\n";
        $html .= "                    </tr>\n\n\n\n\n\n";
        $html .= "                  </table>\n";
        $html .= "                </tr>\n";

        $html .= "                <td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n";

        $html .= "                <tr class=\"formulacion_table_list\">\n";
        $html .= "                  <table>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '120%' class=\"formulacion_table_list\" align = 'center' colspan = 3>INSERTAR FACTOR DE CONVERSI�N</td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '40%' class=\"formulacion_table_list\" align = 'center'>Seleccione Unidad de Dosificaci�n</td>\n";
        $html .= "                        <td width= '40%' class=\"formulacion_table_list\" align = 'center'>Ingrese el Factor de Conversi�n</td>\n";
        $html .= "                        <td width= '40%' class=\"formulacion_table_list\" align = 'center'>Insertar</td>\n";
        $html .= "                    </tr>\n";

        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '40%' align = 'center'>";

        $html .= TraerSelecUnidadesDosificadas($Dosificacion);

        $html .= "                        </td>\n";
        $html .= "                        <td width= '40%' align = 'center'><input type = 'text' name = 'CanFacCon' id = 'CanFacCon'></td>\n";
//      $html .= "                        <td width= '40%' align = 'center'><input type='button' name='InsFac' value='Insertar' onclick=xajax_InsertarFacCon(".$Grupos[0][unidad_id].",'".$Grupos[0][codigo_producto]."');></td>\n";
//      $html .= "                        <td width= '40%' align = 'center'><input type='button' name='InsFac' value='Insertar' onclick=xajax_InsertarFacCon(".$Grupos[0][unidad_id].",'".$Grupos[0][codigo_producto]."');></td>\n";
//      $html .= "                        <td width= '40%' align = 'center'><input type='button' class='input-submit' name='InsFac' value='Insertar' onclick=\"xajax_InsertarFacCon(document.getElementById('FacUniFac').value, document.getElementById('FacCodPro').value, document.getElementById('UniDos').value, document.getElementById('CanFacCon').value);xajax_MostrarMedicamentosF(document.getElementById('FacCodPro').value);\"></td>\n";
//document.getElementById('UniDos').value
        $html .= "                        <td width= '40%' align = 'center'><input type='button' class='input-submit' name='InsFac' value='Guardar' onclick=\"var cadena=''; if (document.getElementById('UniDos').value == '') cadena = '* Debe selecionar unidad de dosificaci�n \\n'; if((document.getElementById('CanFacCon').value == '') || (document.getElementById('CanFacCon').value == '0')) cadena += '* Debe ingresar unidad minima de conversi�n'; if (cadena != '') alert(cadena); if (cadena == '') xajax_InsertarFacCon(document.getElementById('FacUniFac').value, document.getElementById('FacCodPro').value, document.getElementById('UniDos').value, document.getElementById('CanFacCon').value);if (cadena == '') xajax_MostrarMedicamentosF(document.getElementById('FacCodPro').value);\"></td>\n";
        $html .= "                    </tr>\n\n\n\n\n\n";

        $html .= "                  </table>\n";
        $html .= "                </tr>\n";

        $html .= "                <td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n";

        $html .= "                <tr class=\"modulo_table_list_title\">\n";
        $html .= "                  <table>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '190' class=\"formulacion_table_list\" colspan = 4>DETALLE FACTOR DE CONVERSION</td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '190' class=\"formulacion_table_list\">Unidad Pro.</td>\n";
        $html .= "                        <td width= '190' class=\"formulacion_table_list\">Unidad Dosificaci�n</td>\n";
        $html .= "                        <td width= '130' class=\"formulacion_table_list\">Factor de Conversi�n</td>\n";
        $html .= "                        <td width= '70' class=\"formulacion_table_list\">Eliminar</td>\n";
        $html .= "                    </tr>\n";

        foreach ($Grupos as $key => $prod) {
            $html .= "                <tr class=\"modulo_table_list_title\">\n";
            $html .= "                    <td width= '190'>" . $prod[desuni] . "</td>\n";
            $html .= "                    <td width= '190'>" . $prod[unidad_dosificacion] . "</td>\n";

            if ($prod[sw_unidad_minima] == null) {
                $html .= "                    <td width= '110'>0</td>\n";
            } else {
                $html .= "                    <td  width= '110'>" . $prod[sw_unidad_minima] . "</td>\n";
            }
            $html .= "                    <td width= '70'><input type = 'Button'  class='input-submit' Value = 'Eliminar' onclick = \"xajax_EliminarFacCon('$prod[unidad_id]','$prod[codigo_producto]','$prod[unidad_dosificacion]'); xajax_MostrarMedicamentosF('$prod[codigo_producto]');\"></td>\n";
            $html .= "                </tr>\n";
            $i += 1;
        }
        $html .= "                  </table>\n";
        $html .= "                </tr>\n";
    } else {


        $Grupos = $sql->SelectMedicamento($CodMedicamento);
        $Dosificacion = $sql->SelectDosificacion();


        $html .= "                <tr class=\"formulacion_table_list\">\n";
        $html .= "                  <table>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td><input type='hidden' name='FacUniFac' id='FacUniFac' value='" . $Grupos[0][unidad_id] . "'></td>\n";
        $html .= "                        <td><input type='hidden' name='FacCodPro' id='FacCodPro' value='" . $Grupos[0][codigo_producto] . "'></td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '20%' class=\"formulacion_table_list\" align = 'left'>C�DIGO</td>\n";
        $html .= "                        <td width= '50%' align = 'left'>" . $CodMedicamento . "</td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '20%' class=\"formulacion_table_list\" align = 'left'>DESCRIPCI�N</td>\n";
        $html .= "                        <td width= '50%' align = 'left'>" . $Grupos[0][despro] . "</td>\n";
        $html .= "                    </tr>\n\n\n\n\n\n";
        $html .= "                  </table>\n";
        $html .= "                </tr>\n";


        $html .= "                <td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n<td></tr>\n";

        $html .= "                <tr class=\"formulacion_table_list\">\n";
        $html .= "                  <table>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '120%' class=\"formulacion_table_list\" align = 'center' colspan = 3>INSERTAR FACTOR DE CONVERSI�N</td>\n";
        $html .= "                    </tr>\n";
        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '40%' class=\"formulacion_table_list\" align = 'center'>Seleccione Unidad de Dosificaci�n</td>\n";
        $html .= "                        <td width= '40%' class=\"formulacion_table_list\" align = 'center'>Ingrese el Factor de Conversi�n</td>\n";
        $html .= "                        <td width= '40%' class=\"formulacion_table_list\" align = 'center'>Insertar</td>\n";
        $html .= "                    </tr>\n";

        $html .= "                    <tr class=\"modulo_table_list_title\">\n";
        $html .= "                        <td width= '40%' align = 'center'>";

        $html .= TraerSelecUnidadesDosificadas($Dosificacion);

        $html .= "                        </td>\n";
        $html .= "                        <td width= '40%' align = 'center'><input type = 'text' name = 'CanFacCon' id = 'CanFacCon'></td>\n";
//      $html .= "                        <td width= '40%' align = 'center'><input type='button' class='input-submit' name='InsFac' value='Insertar' onclick=\"xajax_InsertarFacCon(document.getElementById('FacUniFac').value, document.getElementById('FacCodPro').value, document.getElementById('UniDos').value, document.getElementById('CanFacCon').value);xajax_MostrarMedicamentosF(document.getElementById('FacCodPro').value);\"></td>\n";
        $html .= "                        <td width= '40%' align = 'center'><input type='button' class='input-submit' name='InsFac' value='Insertar' onclick=\"var cadena=''; if (document.getElementById('UniDos').value == '') cadena = '* Debe selecionar unidad de dosificaci�n \\n'; if((document.getElementById('CanFacCon').value == '') || (document.getElementById('CanFacCon').value == '0')) cadena += '* Debe ingresar unidad minima de conversi�n'; if (cadena != '') alert(cadena); if (cadena == '') xajax_InsertarFacCon(document.getElementById('FacUniFac').value, document.getElementById('FacCodPro').value, document.getElementById('UniDos').value, document.getElementById('CanFacCon').value);if (cadena == '') xajax_MostrarMedicamentosF(document.getElementById('FacCodPro').value);\"></td>\n";
        $html .= "                    </tr>\n\n\n\n\n\n";

        $html .= "                  </table>\n";
        $html .= "                </tr>\n";
    }
    $html .= "            </table>\n";
    $objResponse->assign("DataMedicine", "innerHTML", $html);
    return $objResponse;
}

function EliminarFacCon($CodUni, $CodMed, $UniDos) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    $Grupos = $sql->EliminarFacCon($CodUni, $CodMed, $UniDos);
    if ($Grupos == true) {
//        $objResponse->alert("Registro Eliminado");
    }
    return $objResponse;
}

/*
 * @Autor: Jonier Murillo Hurtado
 * @Fecha: Julio 15 de 2011
 * @Observaciones: 
 *  Conjunto de Funciones necesarias para el ingreso del Factor de Conversi�n por parte de DUANA
 *  <Termina aqui>
 */

/*
  Funcion Xajax para borrar Grupos
 */
?>