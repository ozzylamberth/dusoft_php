<?php

function listarDocumento($desde,$hasta){
    $respuesta = new xajaxResponse();
    $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "DescargaVentaPublico");
    $objHtml = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "DescargaVentaPublico");
    $datos=$objSql->ConsultarProductosVentasPublico($desde,$hasta);
    $html=$objHtml->VisualizarVentasPublico($datos);
    $respuesta->assign('lista',"innerHTML",$html);
    return $respuesta;
}

?>