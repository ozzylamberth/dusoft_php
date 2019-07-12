<?php

/**
 * @package IPSOFT-SIIS
 * @version $Id: Remotos.php,v 1.15 2010/01/19 13:23:00 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Adrian Medina Santacruz
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version $Revision: 1.15 $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Mauricio Medina Santacruz
 */
/*
 * Funcion Que Refrescará el listado de Laboratorios a desplegar en la pagina.
 */
function LaboratoriosT($offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $Laboratorios = $sql->Listar_Laboratorios($offset);

    $action['paginador'] = "Paginador(";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">LABORATORIOS</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"7%\">ID</td>\n";
    $html .= "      <td width=\"25%\">LABORATORIO</td>\n";
    $html .= "      <td width=\"20%\">DIRECCION</td>\n";
    $html .= "      <td width=\"10%\">TELEFONO</td>\n";
    $html .= "      <td width=\"20%\">PAIS</td>\n";
    $html .= "      <td width=\"3%\">OP</td>\n";
    $html .= "      <td width=\"3%\">MOD</td>\n";
    $html .= "      <td width=\"3%\">PROV</td>\n";
    $html .= "      <td width=\"3%\">TITU</td>\n";
    $html .= "      <td width=\"3%\">FABR</td>\n";
    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($Laboratorios as $key => $lab) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $lab['laboratorio_id'] . "</td><td>" . $lab['descripcion'] . " </td>\n";
        $html .= "      <td >" . $lab['direccion'] . "</td><td> " . $lab['telefono'] . "</td><td> " . $lab['pais'] . "</td>";

        if ($lab['estado'] == 1) {
            $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('inv_laboratorios','estado','0','" . $lab['laboratorio_id'] . "','laboratorio_id')\">\n";
            $html .="<img title=\"INACTIVAR\" src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\"></a></td>\n";
        } else {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('inv_laboratorios','estado','1','" . $lab['laboratorio_id'] . "','laboratorio_id')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a></td>\n";
        }

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ModificarLaboratorio('" . $lab['laboratorio_id'] . "')\">\n";
        $html .= "          <img title=\"MODIFICAR\" src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_LaboratorioProveedor('" . $lab['laboratorio_id'] . "')\">\n";
        $html .= "          <img title=\"Agregar Como Proveedor\" src=\"" . GetThemePath() . "/images/proveedor.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"titular('" . $lab['laboratorio_id'] . "','" . $lab['descripcion'] . "','" . $lab['tipo_pais_id'] . "')\">\n";
        $html .= "          <img title=\"Agregar Como Titular\" src=\"" . GetThemePath() . "/images/resumen.gif\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"fabricante('" . $lab['laboratorio_id'] . "','" . $lab['descripcion'] . "')\">\n";
        $html .= "          <img title=\"Agregar Como Fabricante\" src=\"" . GetThemePath() . "/images/fabricante.png\" border=\"0\">\n";
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
 * Funcion Que Refrescará el listado de Laboratorios a desplegar en la pagina.
 */

function MoleculasT($Sw_Medicamento, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");


    $Moleculas = $sql->Listar_Moleculas($Sw_Medicamento, $offset);
    //4) Paso Paginador: Definir en el REQUEST el llamado al Paginador
    //$CodigoGrupo,$CodigoClase,$NombreClase,$NombreGrupo,$Sw_Medicamento,$offset


    $action['paginador'] = "Paginador(";


    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador

    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);
    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">MOLECULAS</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"7%\">CODIGO</td>\n";
    $html .= "      <td width=\"25%\">NOMBRE</td>\n";
    /* $html .= "      <td width=\"10%\">CONCENTRACION</td>\n";
      $html .= "      <td width=\"10%\">U. MEDIDA</td>\n"; */
    $html .= "      <td width=\"3%\">MOD</td>\n";
    $html .= "      <td width=\"3%\">OP</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($Moleculas as $key => $mol) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $mol['molecula_id'] . "</td><td>" . $mol['molecula'] . " </td>\n";
        //$html .= "      <td >".$mol['concentracion']."</td><td> ".$mol['unidad']."</td>";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ModificarMolecula('" . $mol['molecula_id'] . "')\">\n";
        $html .= "          <img title=\"MODIFICAR\" src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";



        if ($mol['estado'] == 1) {
            $html .= "<td align=\"center\">
						  <a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','0','" . $mol['molecula_id'] . "','molecula_id','" . $Sw_Medicamento . "')\">\n";
            $html .="<img title=\"INACTIVAR\" src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\"></a></td>\n";
        } else {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','1','" . $mol['molecula_id'] . "','molecula_id','" . $Sw_Medicamento . "')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a></td>\n";
        }
    }



    $html .= "    </table>\n";
    $html .= "</fieldset><br>\n";

    $objResponse->assign("Listado", "innerHTML", $html);
    return $objResponse;
}

/*
 * Realiza las busqueda de laboratorio por codigo... utilizado por el Buscador
 */

function BusquedaLaboratorio_Nombre($Nombre, $Codigo, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $Laboratorios = $sql->BuscarLaboratorioNombre($Nombre, $Codigo, $offset);

    $action['paginador'] = "PaginadorBusquedas('" . $Nombre . "','" . $Codigo . "'";



    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">LABORATORIOS</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"7%\">ID</td>\n";
    $html .= "      <td width=\"25%\">LABORATORIO</td>\n";
    $html .= "      <td width=\"20%\">DIRECCION</td>\n";
    $html .= "      <td width=\"10%\">TELEFONO</td>\n";
    $html .= "      <td width=\"20%\">PAIS</td>\n";
    $html .= "      <td width=\"3%\">OP</td>\n";
    $html .= "      <td width=\"3%\">MOD</td>\n";
    $html .= "      <td width=\"3%\">PROV</td>\n";
    $html .= "      <td width=\"3%\">TITU</td>\n";
    $html .= "      <td width=\"3%\">FABR</td>\n";
    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($Laboratorios as $key => $lab) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $lab['laboratorio_id'] . "</td><td>" . $lab['descripcion'] . " </td>\n";
        $html .= "      <td >" . $lab['direccion'] . "</td><td> " . $lab['telefono'] . "</td><td> " . $lab['pais'] . "</td>";

        if ($lab['estado'] == 1) {
            $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('inv_laboratorios','estado','0','" . $lab['laboratorio_id'] . "','laboratorio_id')\">\n";
            $html .="<img title=\"INACTIVAR\" src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\"></a></td>\n";
        } else {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('inv_laboratorios','estado','1','" . $lab['laboratorio_id'] . "','laboratorio_id')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a></td>\n";
        }

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ModificarLaboratorio('" . $lab['laboratorio_id'] . "')\">\n";
        $html .= "          <img title=\"MODIFICAR\" src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_LaboratorioProveedor('" . $lab['laboratorio_id'] . "')\">\n";
        $html .= "          <img title=\"Agregar Como Proveedor\" src=\"" . GetThemePath() . "/images/proveedor.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"titular('" . $lab['laboratorio_id'] . "','" . $lab['descripcion'] . "','" . $lab['tipo_pais_id'] . "')\">\n";
        $html .= "          <img title=\"Agregar Como Titular\" src=\"" . GetThemePath() . "/images/resumen.gif\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"fabricante('" . $lab['laboratorio_id'] . "','" . $lab['descripcion'] . "')\">\n";
        $html .= "          <img title=\"Agregar Como Fabricante\" src=\"" . GetThemePath() . "/images/fabricante.png\" border=\"0\">\n";
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
 * Realiza las busqueda de Molecula por Nombre... utilizado por el Buscador
 */

function BusquedaMolecula_Nombre($Nombre, $Codigo, $Sw_Medicamento, $offset) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $Moleculas = $sql->BuscarMoleculaNombre($Nombre, $Codigo, $Sw_Medicamento, $offset);


    $action['paginador'] = "PaginadorBusquedas('" . $Nombre . "','" . $Codigo . "','" . $Sw_Medicamento . "'";



    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo, $sql->pagina, $action['paginador']);

    $html .= "<fieldset class=\"fieldset\">\n";
    $html .= "  <legend class=\"normal_10AN\">MOLECULAS</legend>\n";

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

    $html .= "    <tr class=\"formulacion_table_list\">\n";
    $html .= "      <td width=\"7%\">CODIGO</td>\n";
    $html .= "      <td width=\"25%\">NOMBRE</td>\n";
    /* $html .= "      <td width=\"10%\">CONCENTRACION</td>\n";
      $html .= "      <td width=\"10%\">U. MEDIDA</td>\n"; */
    $html .= "      <td width=\"3%\">MOD</td>\n";
    $html .= "      <td width=\"3%\">OP</td>\n";

    $html .= "    </tr>\n";

    $est = "modulo_list_claro";
    $bck = "#DDDDDD";
    foreach ($Moleculas as $key => $mol) {
        ($est == "modulo_list_claro") ? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
        ($bck == "#CCCCCC") ? $bck = "#DDDDDD" : $bck = "#CCCCCC";

        $html .= "    <tr class=\"" . $est . "\" onmouseout=mOut(this,\"" . $bck . "\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
        $html .= "      <td >" . $mol['molecula_id'] . "</td><td>" . $mol['molecula'] . " </td>\n";
        //$html .= "      <td >".$mol['concentracion']."</td><td> ".$mol['unidad']."</td>";

        $html .= "      <td align=\"center\">\n";
        $html .= "        <a href=\"#\" onclick=\"xajax_ModificarMolecula('" . $mol['molecula_id'] . "')\">\n";
        $html .= "          <img title=\"MODIFICAR\" src=\"" . GetThemePath() . "/images/modificar.png\" border=\"0\">\n";
        // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
        $html .= "        </a>\n";
        $html .= "      </td>\n";



        if ($mol['estado'] == 1) {
            $html .= "<td align=\"center\">
						  <a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','0','" . $mol['molecula_id'] . "','molecula_id','" . $Sw_Medicamento . "')\">\n";
            $html .="<img title=\"INACTIVAR\" src=\"" . GetThemePath() . "/images/checksi.png\" border=\"0\"></a></td>\n";
        } else {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','1','" . $mol['molecula_id'] . "','molecula_id','" . $Sw_Medicamento . "')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"" . GetThemePath() . "/images/checkno.png\" border=\"0\"></a></td>\n";
        }
    }



    $html .= "    </table>\n";
    $html .= "</fieldset><br>\n";
    $objResponse->assign("Listado", "innerHTML", $html);
    return $objResponse;
}

/*
 * Realiza la Insercion de datos de un laboratorio como fabricante.
 */

function GuardarLaboratorioFabricante($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $FabricanteNombreExiste = $sql->ValidarFabricanteNombre($datos['descripcion']);


    if (empty($FabricanteNombreExiste)) {
        $REGISTRAR = $sql->IngresoLaboratorio_Fabricante($datos);

        if ($REGISTRAR) {
            $objResponse->script("LaboratoriosT();Cerrar('Contenedor')");
            $objResponse->alert('Ingreso Exitoso!!');
        }
    }
    else
        $objResponse->alert('Ya existe un fabricante con el nombre ' . $datos['descripcion']);

    return $objResponse;
}

/*
 * Capita del Formulario de Ingreso de Laboratorios
 */

function IngresoLaboratorioFabricante($CodigoLaboratorio, $NombreLaboratorio) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    $datos = $sql->BuscarPaises(NULL);

    $FabricanteNombreExiste = $sql->ValidarFabricanteNombre($NombreLaboratorio);
    if (!empty($FabricanteNombreExiste)) {
        $objResponse->alert('Ya existe un fabricante con el nombre ' . $NombreLaboratorio);
        return $objResponse;
    }

    $fabricante_id = $sql->ProximoValorIdFabricante();

    $Arreglo = '<SELECT NAME="pais" SIZE="1" class="input-text">';
    $i = 0;
    foreach ($datos as $key => $paises) {
        $Arreglo .= '<OPTION VALUE="' . $datos[$i]['tipo_pais_id'] . '">' . $datos[$i]['pais'] . '</OPTION>';
        $i = $i + 1;
    }
    $Arreglo .='</SELECT>';

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioLaboratorioFabricante\" id=\"FormularioLaboratorioFabricante\" method=\"post\">";

    $html .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"formulacion_table_list\">";
    $html .= "        <td align=\"center\" colspan=\"2\">";
    $html .= "          Creacion de Fabricante";
    $html .= "        </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"formulacion_table_list\" >";
    $html .= "        <td width=\"30%\">Codigo del Fabricante :</td>";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\" width=\"10%\">";
    $html .= '          <input class="input-text" type="Text" name="fabricante_id" maxlength="4" ReadOnly="true" value="' . $fabricante_id[0]['nextval'] . '">';
    $html .= "        </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td>Nombre del Fabricante :</td>";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\" width=\"10%\">";
    $html .= '          <input class="input-text" type="Text" name="descripcion" maxlength="30" ReadOnly="true" value="' . $NombreLaboratorio . '">';
    $html .= "        </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"formulacion_table_list\">";
    $html .= "        <td>Registro Invima :</td>";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\" width=\"10%\">";
    $html .= '          <input class="input-text" type="Text" name="registro_invima" maxlength="30" size="30" onkeyup="this.value=this.value.toUpperCase()">';
    $html .= "        </td>";
    $html .= "      </tr>";
    $html .= "      <tr class=\"formulacion_table_list\">";
    $html .= "        <td >Pais :</td>";
    $html .= "        <td class=\"modulo_list_claro\" align=\"left\" width=\"10%\">";
    $html .= $Arreglo;
    $html .= "        </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" maxlength="20" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"ValidarIngreso(xajax.getFormValues('FormularioLaboratorioFabricante'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";



    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->script("MostrarSpan('FABRICANTE')");
    return $objResponse;
}

function IngresoTitular($CodigoLaboratorio, $Nombre, $tipo_pais_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");


    $REGISTRAR = $sql->IngresoLaboratorioTitularInvima($CodigoLaboratorio, $Nombre, $tipo_pais_id);

    if ($REGISTRAR) {
        $objResponse->call('LaboratoriosT()');
        $objResponse->alert('Ingreso Exitoso!!');
    }
    else
        $objResponse->alert('Error en el Envio de los datos! Compruebe que el Titular no existe!!');

    return $objResponse;
}

/*
 * Funcion Ajax que lista los subgrupos de actividades
 */

function GuardarProveedor($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ManejoTerceros");


    $REGISTRAR = $sql->NewTerceroProveedor($datos);

    if ($REGISTRAR) {
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->alert('Registro Exitoso');
    }
    else
        $objResponse->alert('Error en el Envio de los datos! Compruebe que el Proveedor no existe!!');


    return $objResponse;
}

/*
 * Funcion Ajax que lista los subgrupos de actividades
 */

function Actividades_sgrupo($grupo) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    //$consulta=new CrearSQL();
    //$actividades=$consulta->ListaActividades($grupo);
    $actividades = $sql->ListaActividades($grupo);
    //var_dump($actividades);
    $salida .= "    <option value=\"0\">----SELECCIONAR----</option> \n";
    if (!empty($actividades)) {
        for ($i = 0; $i < count($actividades); $i++) {
            $salida.="<option value=\"" . $actividades[$i]['actividad_id'] . "\">" . substr($actividades[$i]['descripcion'], 0, 60) . "</option>\n";
        }
    }

    $objResponse->assign("actividades", "innerHTML", $salida);
    return $objResponse;
}

/*
 * Funcion Ajax que Crea la Capita para Adicionar un Laboratorio como Proveedor
 */

function LaboratorioProveedor($CodigoProveedorId) {
    $objResponse = new xajaxResponse();

    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $tipos_id_ter3 = $sql->Terceros_id();
    $Laboratorio = $sql->Buscar_Laboratorio($CodigoProveedorId);

    $html .= "    <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $html .= "  <form name=\"crearproveedor\" id=\"crearproveedor\" method=\"post\">";
    $html .= "    <table width=\"94%\" align=\"center\" class=\"modulo_table_list\">\n";
    $html .= "      <tr class=\"modulo_table_list_title\">\n";
    $html .= "        <td  align=\"center\" colspan='4'>CREAR PROVEEDOR</td>\n";
    $html .= "      </tr>\n";

    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td width=\"18%\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
    $html .= "        <td align=\"left\" colspan=\"3\">\n";

    $html .= "          <select id=\"tipo_id_tercero\" name=\"tipo_id_tercero\" class=\"select\" onchange=\"Tachar(this.value);\">";
    //$tipos_id_ter3=$consulta->Terceros_id();
    foreach ($tipos_id_ter3 as $k => $dtl)
        $html .="                           <option value=\"" . $dtl['tipo_id_tercero'] . "\">" . $dtl['tipo_id_tercero'] . "</option> \n";

    $html .= "          </select>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"tercero_id\" name=\"tercero_id\" maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"dv\" name=\"dv\" maxlength=\"1\" size=\"1\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";

    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">NOMBRE</td>\n";
    $html .= "        <td colspan='3'  align=\"left\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"nombre_tercero\" name=\"nombre_tercero\" size=\"60\" value='" . $Laboratorio[0]['descripcion'] . "'  ReadOnly=\"true\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";

    $url1 = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=" . $pais . "&dept=" . $dpto . "&mpio=" . $mpio . "&forma=crearproveedor";
    $html .= "      <tr class=\"formulacion_table_list\">\n";
    $html .= "        <td>LOCALIZACION</td>\n";
    $html .= "        <td class=\"modulo_list_claro\" colspan='2' >\n";
    $html .= "          <input type=\"hidden\" name=\"pais\" >\n";
    $html .= "          <input type=\"hidden\" name=\"tipo_pais_id\" >\n";
    $html .= "          <input type=\"hidden\" name=\"dpto\" >\n";
    $html .= "          <input type=\"hidden\" name=\"tipo_dpto_id\" >\n";
    $html .= "          <input type=\"hidden\" name=\"mpio\" >\n";
    $html .= "          <input type=\"hidden\" name=\"tipo_mpio_id\" >\n";
    $html .= "          <input type=\"hidden\" name=\"empresa_id\" value='" . $_REQUEST['datos']['empresa_id'] . "' >\n";
    $html .= "          <label id=\"ubicacion\">" . $NomPais . " - " . $NomDpto . " - " . $NomMpio . "</label>\n";
    $html .= "        </td>\n";
    $html .= "        <td class=\"modulo_list_claro\" >\n";
    $html .= "          <input type=\"button\" class=\"input-submit\" name=\"cPrecedencia\" value=\"Ubicacion\" target=\"localidad\" onclick=\"window.open('" . $url1 . "', 'localidad', 'toolbar=no,width=500,heigth=350,resizable=no,scrollbars=yes').focus(); return false;\">\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">DIRECCION</td>\n";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "           <input type=\"text\" class=\"input-text\" name=\"direccion\" id=\"direccion\" maxlength=\"60\" size=\"60\" value=\"\" >";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    /*
     * Nuevos Campos para el Nuevo Proyecto
     * fecha 3-septiembre-2009-> Duana
     */
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">GERENTE</td>\n";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "           <input type=\"text\" class=\"input-text\" name=\"nombre_gerente\" id=\"nombre_gerente\" maxlength=\"60\" size=\"60\" value=\"\" >";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\" width=\"25%\">TELEFONO GERENTE</td>\n";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "           <input maxlength=\"15\" style=\"width:26%\" type=\"text\" class=\"input-text\" name=\"telefono_gerente\" id=\"telefono_gerente\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">REPRESENTANTE DE VENTAS</td>\n";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"representante_ventas\" name=\"representante_ventas\" maxlength=\"20\" style=\"width:80%\" value=\"\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\" width=\"25%\">TELEFONO REPRESENTANTE DE VENTAS</td>\n";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "          <input maxlength=\"15\" style=\"width:26%\" type=\"text\" class=\"input-text\" id=\"telefono_representante_ventas\" name=\"telefono_representante_ventas\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    /*
     * Fin Nuevos Campos para el Nuevo Proyecto
     * fecha 3-septiembre-2009-> Duana
     */

    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">TELEFONO EMPRESA</td>\n";
    $html .= "        <td width=\"25%\" align=\"left\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"telefono\" name=\"telefono\" maxlength=\"20\" style=\"width:80%\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>";
    $html .= "        <td width=\"25%\" class=\"formulacion_table_list\">CELULAR</td>";
    $html .= "        <td width=\"25%\" align=\"left\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"celular\" name=\"celular\" maxlength=\"15\" style=\"width:80%\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">FAX</td>";
    $html .= "        <td align=\"left\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"fax\" name=\"fax\" maxlength=\"15\" style=\"width:80%\" value=\"\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "        <td class=\"formulacion_table_list\">E-MAIL</td>\n";
    $html .= "        <td align=\"left\">\n";
    $html .= "          <input type=\"text\" class=\"input-text\" id=\"email\" name=\"email\" maxlength=\"45\" style=\"width:80%\" value=\"\" onkeypress=\"\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td colspan='2' class=\"formulacion_table_list\"> TIPO CONSTITUCION</td>\n";
    $html .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_persona_juridica\" value=\"0\"> PERSONA NATURAL</td>\n";
    $html .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_persona_juridica\" value=\"1\" > PERSONA JURIDICA</td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">DIAS DE GRACIA</td>\n";
    $html .= "        <td>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"dias_gracia\" id=\"dias_gracia\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "        <td class=\"formulacion_table_list\">DIAS CREDITO</td>\n";
    $html .= "        <td>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"dias_credito\" id=\"dias_credito\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">TIEMPO ENTREGA</td>\n";
    $html .= "        <td>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"tiempo_entrega\" id=\"tiempo_entrega\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">";
    $html .= "        </td>\n";
    $html .= "        <td class=\"formulacion_table_list\">DESCUENTO CONTADO</td>\n";
    $html .= "        <td>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"descuento_por_contado\" id=\"descuento_por_contado\" maxlength=\"3\" size=\"3\" value=\"0\" onkeypress=\"return acceptNum(event)\">%";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td colspan=\"2\" class=\"formulacion_table_list\">REGIMEN</td>\n";
    $html .= "        <td><input type=\"radio\" name=\"sw_regimen_comun\" value=\"1\" > COMUN</td>\n";
    $html .= "        <td><input type=\"radio\" name=\"sw_regimen_comun\" value=\"0\" > SIMPLIFICADO</td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td colspan=\"2\" class=\"formulacion_table_list\">GRAN CONTRIBUYENTE</td>\n";
    $html .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_gran_contribuyente\" value=\"1\" >SI</td>\n";
    $html .= "        <td><input type=\"radio\" class=\"input-text\" name=\"sw_gran_contribuyente\" value=\"0\">NO</td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">PORCENTAJE RTF</td>\n";
    $html .= "        <td>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"porcentaje_rtf\" id=\"porcentaje_rtf\" maxlength=\"3\" size=\"3\" value=\"\" onkeypress=\"return acceptNum(event)\">%\n";
    $html .= "        </td>\n";
    $html .= "        <td class=\"formulacion_table_list\">PORCENTAJE ICA</td>\n";
    $html .= "        <td>\n";
    $html .= "          <input type=\"text\" class=\"input-text\" name=\"porcentaje_ica\" id=\"porcentaje_ica\" maxlength=\"3\" size=\"3\" value=\"\" onkeypress=\"return acceptNum(event)\">%";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">GRUPO DE ACTIVIDAD</td>\n";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "          <select id=\"grupos\" name=\"grupos\" class=\"select\" onchange=\"xajax_Actividades_sgrupo(this.value);\">";
    $html .= "            <option value=\"0\">----SELECCIONAR----</option> \n";

    $Grupos = $sql->ListaGruposActividades();

    if (!empty($Grupos)) {
        for ($i = 0; $i < count($Grupos); $i++)
            $html .="                           <option value=\"" . $Grupos[$i]['grupo_id'] . "\">" . substr($Grupos[$i]['descripcion'], 0, 65) . "</option> \n";
    }
    $html .= "          </select>\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">ACTIVIDAD</td>";
    $html .= "        <td colspan='3' align=\"left\">\n";
    $html .= "          <select id=\"actividades\" name=\"actividad_id\" class=\"select\" onchange=\"\">";
    $html .= "            <option value=\"0\">----SELECCIONAR----</option> \n";
    $html .= "          </select>\n";
    $html .= "        </td>\n";
    $html .= "      </tr>\n";
    /*
     * Nuevos Campos para el Nuevo Proyecto
     * fecha 3-septiembre-2009-> Duana
     */
    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td class=\"formulacion_table_list\">PRIORIDAD DE COMPRA</td>\n";
    $html .= "        <td><input type=\"radio\" name=\"prioridad_compra\" value=\"1\" > Alta</td>\n";
    $html .= "        <td><input type=\"radio\" name=\"prioridad_compra\" value=\"2\" checked> Media</td>\n";
    $html .= "        <td><input type=\"radio\" name=\"prioridad_compra\" value=\"3\" > Baja</td>\n";
    $html .= "      </tr>\n";
    /*
     * Fin Nuevos Campos para el Nuevo Proyecto
     * fecha 3-septiembre-2009-> Duana
     */



    $html .= "      <tr class=\"modulo_list_claro\">\n";
    $html .= "        <td colspan='4'  align=\"center\">\n";
    $html .= "          <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero1(document.crearproveedor)\" value=\"Registrar\">"; //xajax.getFormValues('crearproveedor'))\" value=\"Registrar\">\n";

    $html .= "      </tr>\n";
    $html .= "    </table>\n";
    $html .= "        </form></td>\n"; //Cierre de Form
    $html = $objResponse->setTildes($html);
    $objResponse->assign("Contenido", "innerHTML", $html);
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

/*
  Funcion Xajax para Cambiar el estado de un registro
 */

function CambioEstado($tabla, $campo, $valor, $id, $campo_id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.

    $sql_classes = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $sql->Cambio_Estado($tabla, $campo, $valor, $id, $campo_id);

    $token_0_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id);
    $token_1_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '1');
    $token_2_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '2');
    $token_3_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '3');
    $token_4_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '4');
	$token_5_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '5');
	$token_6_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '6');

    $objResponse->call("xajax_LaboratoriosT");
    return $objResponse;
}

/*
  Funcion Xajax para Cambiar el estado de un registro
 */

function CambioEstadoMolecula($tabla, $campo, $valor, $id, $campo_id, $Sw_Medicamento) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.

    $sql_classes = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $sql->Cambio_Estado($tabla, $campo, $valor, $id, $campo_id);

    $token_0_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id);
    $token_1_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '1');
    $token_2_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '2');
    $token_3_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '3');
    $token_4_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '4');
	$token_5_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '5');
    $token_6_WS = $sql_classes->Cambio_Estado_WS($tabla, $campo, $valor, $id, $campo_id, '6');

    $objResponse->call("xajax_MoleculasT('$Sw_Medicamento')");
    return $objResponse;
}

/*
  Funcion Xajax para Modificar Laboratorios
 */

function GuardarModLaboratorio($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Modificar_Laboratorio($datos);

    $token_ws = $sql->Modificar_Laboratorio_WS($datos);
    $token_1_ws = $sql->Modificar_Laboratorio_WS($datos,'1');
    $token_2_ws = $sql->Modificar_Laboratorio_WS($datos,'2');
    $token_3_ws = $sql->Modificar_Laboratorio_WS($datos,'3');
    $token_4_ws = $sql->Modificar_Laboratorio_WS($datos,'4');
	$token_5_ws = $sql->Modificar_Laboratorio_WS($datos,'5');
	$token_6_ws = $sql->Modificar_Laboratorio_WS($datos,'6');

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

    //$objResponse->alert("SQL: ".$token);
    if ($token) {
        $objResponse->call("xajax_LaboratoriosT()");
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
  Funcion Xajax para Modificar la informacion de un laboratorio con un formulario
  cargado en un Xajax
 */

function ModificarLaboratorio($CodigoLaboratorio_Id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $datos = $sql->BuscarPaises(NULL);

    $Laboratorio = $sql->Buscar_laboratorio($CodigoLaboratorio_Id);

    //Scripts Javascripts

    foreach ($Laboratorio as $key => $lab) {
        
    }
    $Arreglo = '<SELECT NAME="pais" SIZE="1" class="input-text" style="width:100%;height:100%">';
    $i = 0;
    foreach ($datos as $key => $paises) {
        $Arreglo .="<OPTION ";

        if ($Laboratorio[0]['tipo_pais_id'] == $datos[$i]['tipo_pais_id'])
            $Arreglo .=" selected ";




        $Arreglo.=' VALUE="' . $datos[$i]['tipo_pais_id'] . '">' . $datos[$i]['pais'] . '</OPTION>';
        $i = $i + 1;
    }
    //$Arreglo .='<OPTION selected VALUE="'.$Laboratorio[0]['tipo_pais_id'].'">'.$Laboratorio[0]['pais'].'</OPTION>';
    $Arreglo .='</SELECT>';

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioLaboratorio\" id=\"FormularioLaboratorio\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Modificar - Laboratorio";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo del Laboratorio :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"10%\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" value="' . $Laboratorio[0]['laboratorio_id'] . '" name="id" maxlength="4" readonly=\"true\">';
    $html .= '      <input type="hidden" value="' . $Laboratorio[0]['laboratorio_id'] . '" name="laboratorio_id" >';
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Nombre del Laboratorio :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input class="input-text" style="width:100%;height:100%" type="Text" name="descripcion" maxlength="30" value="' . $Laboratorio[0]['descripcion'] . '" onkeyup="this.value=this.value.toUpperCase()" >';

    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "
						<tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">";
    $html .= "      Direccion :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= '      <input class="input-text" type="Text" name="direccion" style="width:100%;height:100%" maxlength="30" value="' . $Laboratorio[0]['direccion'] . '" onkeyup="this.value=this.value.toUpperCase()">';
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">";
    $html .= "      Telefono :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="telefono" maxlength="20" value="' . $Laboratorio[0]['telefono'] . '" onkeypress="return acceptNum(event)">';
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">";
    $html .= "      Pais :";
    $html .= "      </td>";
    $html .= "      <td  align=\"center\">";
    $html .= $Arreglo;
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" maxlength="20" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioLaboratorio'))\">"; //onclick=\"xajax_InsertarLaboratorio(xajax.getFormValues('FormularioLaboratorio'))
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
  Funcion Xajax para Modificar Laboratorios
 */

function GuardarModMolecula($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Modificar_Molecula($datos);
    $token_ws = $sql->Modificar_Molecula_WS($datos);
    $token_1_ws = $sql->Modificar_Molecula_WS($datos,'1');
    $token_2_ws = $sql->Modificar_Molecula_WS($datos,'2');
    $token_3_ws = $sql->Modificar_Molecula_WS($datos,'3');
    $token_4_ws = $sql->Modificar_Molecula_WS($datos,'4');
	$token_5_ws = $sql->Modificar_Molecula_WS($datos,'5');
    $token_6_ws = $sql->Modificar_Molecula_WS($datos,'6');

    if ($token_ws) {
        $mensaje = "\nModificacion Exitosa en Cosmitet.";
    } else {
        $mensaje = "\Error al modificar en Cosmitet.";
    }
    
    if ($token_1_ws) {
        $mensaje .= "\nModificacion Exitosa en Dumian.";
    } else {
        $mensaje .= "\Error al modificar en Dumian.";
    }
    
    if ($token_2_ws) {
        $mensaje .= "\nModificacion Exitosa en CSSP.";
    } else {
        $mensaje .= "\Error al modificar en CSSP.";
    }
    
    if ($token_3_ws) {
        $mensaje .= "\nModificacion Exitosa en MD.";
    } else {
        $mensaje .= "\Error al modificar en MD.";
    }
    if ($token_4_ws) {
        $mensaje .= "\nModificacion Exitosa en CMS.";
    } else {
        $mensaje .= "\Error al modificar en CMS.";
    }
	if ($token_5_ws) {
        $mensaje .= "\nModificacion Exitosa en PENITAS.";
    } else {
        $mensaje .= "\Error al modificar en PENITAS.";
    }
	if ($token_6_ws) {
        $mensaje .= "\nModificacion Exitosa en CARTAGENA.";
    } else {
        $mensaje .= "\Error al modificar en CARTAGENA.";
    }

    if ($token) {
        $objResponse->call("xajax_MoleculasT('" . $datos['sw_medicamento'] . "')");
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
  Funcion Xajax para Modificar la informacion de una Molecula con un formulario
  cargado en un Xajax
 */

function ModificarMolecula($CodigoMolecula_Id) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $Molecula = $sql->Buscar_Molecula($CodigoMolecula_Id);

    //Scripts Javascripts

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioMolecula\" id=\"FormularioMolecula\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Modificacion de Moleculas";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo de la Molecula :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input class="input-text" type="Text" value="' . $Molecula[0]['molecula_id'] . '" name="molecula_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()" style="width:100%;height:100%">';
    $html .= '		  <input type="hidden" name="token1" value="0">';
    $html .= '		  <input type="hidden" name="molecula_id_old" value="' . $CodigoMolecula_Id . '">';
    $html .= '		  <input type="hidden" name="sw_medicamento" value="' . $Molecula[0]['sw_medicamento'] . '">';
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
    $html .= "      Nombre De La Molecula :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input class="input-text" type="Text" value="' . $Molecula[0]['molecula'] . '" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()" style="width:100%;height:100%">';
    
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";
     

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioMolecula'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";



    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

function BuscarMolecula($molecula_id) {

    //Crea Objeto XAJAX para la carga de datos en HTML.
    $objResponse = new xajaxResponse();
    //Crea una instancia a la clase permisos, donde existe un metodo BuscarMolecula y este pueda ser descargado en una tabla.
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    //$sql->debug = true;
    //Recibe datos del query de la clase permisos.
    $datos = $sql->Buscar_Molecula($molecula_id);

    //Concatena datos para mostrar en HTML.
    //$datos[0]['molecula_id'] acceder a un datos

    if (empty($datos)) {
        $html = '<b>Molecula No Existe!!!</b>';
        $objResponse->script("document.formulario.token1.value='1';
			document.formulario.descripcion.value = '';
			desactivar();
			");
    } else {
        $html = '<b>Molecula Existe!!!</b>';
        $objResponse->script('
			document.formulario.token1.value="0";
			document.formulario.descripcion.value = "";
			document.formulario.descripcion.value="' . $datos[0]['descripcion'] . '";
			desactivar();
			');
    }
    //Indica que se va a cargar un contenido en XAJAX, 
    //assign(DIV: ID=(sitio donde descargar la info),propiedad para incluir codigo html,y el contenido con codigo html).
    $objResponse->assign("Contenido", "innerHTML", $html);

    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    return $objResponse;
}

function InsertarLaboratorio($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Insertar_Laboratorio($datos);

    $token_ws = $sql->Insertar_Laboratorio_WS($datos);
    $token_1_ws = $sql->Insertar_Laboratorio_WS($datos, '1');
    $token_2_ws = $sql->Insertar_Laboratorio_WS($datos, '2');
    $token_3_ws = $sql->Insertar_Laboratorio_WS($datos, '3');
    $token_4_ws = $sql->Insertar_Laboratorio_WS($datos, '4');
	$token_5_ws = $sql->Insertar_Laboratorio_WS($datos, '5');
	$token_6_ws = $sql->Insertar_Laboratorio_WS($datos, '6');

    //$objResponse->alert("WS SQL: ".$token_ws);

    if ($token_ws) {
        $mensaje = "\nIngreso Exitoso en Cosmitet.";
    } else {
        $mensaje = "\nError en el ingreo en Cosmitet.";
    }

    if ($token_1_ws) {
        $mensaje .= "\nIngreso Exitoso en Dumian.";
    } else {
        $mensaje .= "\nError en el ingreo en Dumian.";
    }

    if ($token_2_ws) {
        $mensaje .= "\nIngreso Exitoso en CSSP.";
    } else {
        $mensaje .= "\nError en el ingreo en CSSP.";
    }

    if ($token_3_ws) {
        $mensaje .= "\nIngreso Exitoso en MD.";
    } else {
        $mensaje .= "\nError en el ingreo en MD.";
    }
    
    if ($token_4_ws) {
        $mensaje .= "\nIngreso Exitoso en CMS.";
    } else {
        $mensaje .= "\nError en el ingreo en CMS.";
    }
	
	 if ($token_5_ws) {
        $mensaje .= "\nIngreso Exitoso en PENITAS.";
    } else {
        $mensaje .= "\nError en el ingreo en PENITAS.";
    }
	
	if ($token_6_ws) {
        $mensaje .= "\nIngreso Exitoso en CARTAGENA.";
    } else {
        $mensaje .= "\nError en el ingreo en CARTAGENA.";
    }

    if ($token) {
        $objResponse->script("xajax_LaboratoriosT();Cerrar('Contenedor');");
        $objResponse->alert("Ingreso Exitoso!!" . $mensaje);
        $objResponse->assign("Listado", "innerHTML", $html);  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    }
    else
        $objResponse->alert("Error en el Ingreso... Revisa que no exista El Laboratorio!!");

    return $objResponse;
}

function IngresoLaboratorio($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    $datos = $sql->BuscarPaises(NULL);

    $Arreglo = '<SELECT NAME="pais" SIZE="1" class="input-text" style="width:100%;height:100%">';
    $Arreglo .= ' <option value="-1">--SELECCIONAR--</option>';

    $i = 0;
    foreach ($datos as $key => $paises) {
        $Arreglo .= '<OPTION VALUE="' . $datos[$i]['tipo_pais_id'] . '">' . $datos[$i]['pais'] . '</OPTION>';
        $i = $i + 1;
    }
    $Arreglo .='</SELECT>';

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioLaboratorio\" id=\"FormularioLaboratorio\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Creacion de Laboratorios";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo del Laboratorio :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="laboratorio_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()">';
    $html .= '		<input type="hidden" name="token1" value="0">';
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\" width=\"30%\">";
    $html .= "      Nombre del Laboratorio :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
    /*
      $html .= "      <br><div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n"; */
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
						<tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">";
    $html .= "      Direccion :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="direccion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
    $html .= "      </td>";
    $html .= "      </tr>";


    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">";
    $html .= "      Telefono :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="telefono" maxlength="20" onkeypress="return acceptNum(event)">';
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"formulacion_table_list\" align=\"center\">";
    $html .= "      Pais :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\">";
    $html .= $Arreglo;
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" maxlength="20" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioLaboratorio'))\">";
    $html .= "      </td>";
    $html .= "      </tr>";
    $html .= "		</form>";
    $html .= "      </table>";



    $objResponse->assign("Contenido", "innerHTML", $html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
}

function InsertarMolecula($datos) {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");

    $token = $sql->Insertar_Molecula($datos);

    $token_ws = $sql->Insertar_Molecula_WS($datos);
    $token_1_ws = $sql->Insertar_Molecula_WS($datos, '1');
    $token_2_ws = $sql->Insertar_Molecula_WS($datos, '2');
    $token_3_ws = $sql->Insertar_Molecula_WS($datos, '3');
    $token_4_ws = $sql->Insertar_Molecula_WS($datos, '4');
	$token_5_ws = $sql->Insertar_Molecula_WS($datos, '5');
    $token_6_ws = $sql->Insertar_Molecula_WS($datos, '6');

    if ($token_ws) {
        $mensaje = "\nIngreso Exitoso en Cosmitet.";
    } else {
        $mensaje = "\nError en el ingreso en Cosmitet.";
    }
    
    if ($token_1_ws) {
        $mensaje .= "\nIngreso Exitoso en Dumian.";
    } else {
        $mensaje .= "\nError en el ingreso en Dumian.";
    }
    
    if ($token_2_ws) {
        $mensaje .= "\nIngreso Exitoso en CSSP.";
    } else {
        $mensaje .= "\nError en el ingreso en CSSP.";
    }
    
    if ($token_3_ws) {
        $mensaje .= "\nIngreso Exitoso en MD.";
    } else {
        $mensaje .= "\nError en el ingreso en MD.";
    }
    
    if ($token_4_ws) {
        $mensaje .= "\nIngreso Exitoso en CMS.";
    } else {
        $mensaje .= "\nError en el ingreso en CMS.";
    }
	
	if ($token_5_ws) {
        $mensaje .= "\nIngreso Exitoso en PENITAS.";
    } else {
        $mensaje .= "\nError en el ingreso en PENITAS.";
    }

	
	if ($token_6_ws) {
        $mensaje .= "\nIngreso Exitoso en CARTAGENA.";
    } else {
        $mensaje .= "\nError en el ingreso en CARTAGENA.";
    }


    if ($token) {
        $objResponse->call("Cerrar('Contenedor')");
        $objResponse->call("xajax_MoleculasT('" . $datos['sw_medicamento'] . "')");
        $objResponse->alert("Ingreso Exitoso!!" . $mensaje);
        $objResponse->assign("Listado", "innerHTML", $html);  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    }
    else
        $objResponse->alert("Error en el Ingreso... Revisa que no exista La Molécula!!");



    return $objResponse;
}

function IngresoMolecula() {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas", "classes", "app", "Inv_CodificacionProductos");
    $datos = $sql->Listar_Unidades_Medida_Medicamento();

    $Arreglo = '<SELECT style="width:100%;height:100%" NAME="unidad_medida_medicamento_id" SIZE="1" class="input-text">';
    $i = 0;
    foreach ($datos as $key => $unidad_medida) {
        $Arreglo .= '<OPTION VALUE="' . $unidad_medida['unidad_medida_medicamento_id'] . '">' . $unidad_medida['descripcion'] . '</OPTION>';
        $i = $i + 1;
    }
    $Arreglo .='</SELECT>';

    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "  <tr><td>";
    //action del formulario= Donde van los datos del formulario.
    $html .= "  <form name=\"FormularioMolecula\" id=\"FormularioMolecula\" method=\"post\">";

    $html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "      <tr class=\"modulo_table_list_title\">";
    $html .= "      <td align=\"center\" colspan=\"2\">";
    $html .= "      Creacion de Moleculas";
    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
    $html .= "      Codigo de la Molecula :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input class="input-text" type="Text" name="molecula_id" maxlength="10" style="width:100%;height:100%">';
    $html .= '		  <input type="hidden" name="token1" value="0">';
    $html .= '		  <input type="hidden" name="sw_medicamento" value="1">';
    $html .= "      </td>";
    $html .= "      </tr>";



    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
    $html .= "      Nombre De La Molecula :";
    $html .= "      </td>";
    $html .= "      <td align=\"center\" width=\"20%\">";
    $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';


    $html .= "      </td>";
    $html .= "      </tr>";

    $html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">";


    /*
      $html .="       <tr class=\"modulo_list_claro\">";
      $html .= "      <td class=\"label\" align=\"center\">";
      $html .= "      Concentracion :";
      $html .= "      </td>";
      $html .= "      <td align=\"center\">";
      $html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="concentracion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
      $html .= "      </td>";
      $html .= "      </tr>";


      $html .= "      <tr class=\"modulo_list_claro\">";
      $html .= "      <td class=\"label\" align=\"center\">";
      $html .= "      Unidad de Medida :";
      $html .= "      </td>";
      $html .= "      <td align=\"center\">";
      $html .=       $Arreglo;
      $html .= "      </td>";
      $html .= "      </tr>"; */

    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
    $html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioMolecula'))\">";
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
