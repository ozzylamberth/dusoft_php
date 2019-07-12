<?php

/**
 * Archivo Xajax
 * Tiene como responsabilidad hacer el manejo de las funciones
 * que son invocadas por medio de xajax
 *
 * @package IPSOFT-SIIS
 * @version 1.0 $
 * @copyright (C) 2013 IPSOFT - SA (www.ipsoft-sa.com)
 * @author Luis Guillermo Trejo López
 */
IncludeClass("ClaseHTML");
/*
 * Funcion que  permite modificar la fecha de vencimiento
 */

function EditarFecha($empresa_id, $centro_utilidad, $codigo_producto, $bodega, $fecha_vencimiento, $lote, $campo_fecha_vencimiento) {    
    $sql = AutoCarga::factory("InvTrasladosSQL", "classes", "app", "InvLotes");
    $sql->EditarFechaVencimiento($empresa_id, $centro_utilidad, $codigo_producto, $bodega, $fecha_vencimiento, $lote, $campo_fecha_vencimiento);
}

?>