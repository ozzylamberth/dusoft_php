<?php

/**
 * @package DUANA
 * @version 1.0 $Id: Remotos_pqrs.php
 * @copyright (C) JUN-2012 DUANA  CIA 
 * @author R.O.M.A
 */

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 */
//util
//$objResponse->script("alert('Nota Anulada, con Exito');");


/* * ****************************************************************************************
 * Remotos: Select listar usuarios de farmacia
 * @param: $empresa_id, $bodega
 * **************************************************************************************** */

function GetUserFarm($bodega, $empresa)
{ 
    $objResponse = new xajaxResponse();
    //$objResponse->alert('mensje');
    $sql = AutoCarga::factory("Permisos", "classes", "app", "ESM_AdminPqrs");
    $usuarios = $sql->BuscarUsuarioFarm($empresa, $bodega);

    $html = " <select name=\"resp_caso\" id=\"resp_caso\" class=\"select\">  ";
    $html .= "  <option value=\"0\">---SELECCIONAR---</option>\n";
    foreach ($usuarios as $key => $valor)
    {
        $html .= " <option value=\"" . $valor['usuario_id'] . "\">" . strtoupper($valor['nombre']) . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("resp_farm", "innerHTML", $html);

    return $objResponse;
}

/* * ****************************************************************************************
 * Remotos: Select listar usuarios de farmacia
 * @param: $empresa_id, $bodega
 * **************************************************************************************** */

function UsuariosFarmacia($bodega)
{
    $objResponse = new xajaxResponse();

    $emp = SessionGetVar("empresa_permiso");

    $sql = AutoCarga::factory("Permisos", "classes", "app", "ESM_AdminPqrs");
    $users = $sql->BuscarUsuarioFarm($emp, $bodega);
    /* $objResponse->alert("users: ".$users); */

    $html = " <select name=\"resp_caso\" id=\"resp_caso\" class=\"select\">\n";
    $html .= "  <option value=\"0\">---SELECCIONAR---</option>\n";
    foreach ($users as $key => $value)
    {
        $html .= " <option value=\"" . $value['usuario_id'] . "\">" . strtoupper($value['nombre']) . "</option>\n";
    }
    $html .= " </select>\n";

    $objResponse->assign("resp_farm", "innerHTML", $html);

    return $objResponse;
}

?>