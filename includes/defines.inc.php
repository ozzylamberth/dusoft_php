<?php

/**
 * $Id: defines.inc.php,v 1.2 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Listado de definiciones globales
 */

// Resumir las Definiciones de Version
define('_SIIS_APLICATION_TITLE',_SIIS_VERSION_ID.' v.'._SIIS_VERSION_NUM.'-'._SIIS_VERSION_SUB);

if(_SIIS_DEVELOPER_URL != ''){
	define('_SIIS_DEVELOPER_LINK',"<a href=\""._SIIS_DEVELOPER_URL."\">"._SIIS_DEVELOPER_BY."</a>");
}elseif(_SIIS_DEVELOPER_EMAIL != ''){
	define('_SIIS_DEVELOPER_LINK',"<a href=\"mailto:"._SIIS_DEVELOPER_EMAIL."\">"._SIIS_DEVELOPER_BY."</a>");
}else{
	define('_SIIS_DEVELOPER_LINK',_SIIS_DEVELOPER_BY);
}
setlocale ("LC_TIME", "es_ES");
// $h=4444424.12555;
// echo number_format($h,2,'.','');
// exit;

//DEFINIR LOS GRUPOS_TIPOS_CARGOS DE IMAGENOLOGIA Y LABORATORIO
define('SIIS_IMAGENOLOGIAS','RX');
define('SIIS_LABORATORIOS','LB');
?>
