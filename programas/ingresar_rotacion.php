<?php  /**  * @package IPSOFT-SIIS  * @version $Id:  $  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)  * @author Hugo F  Manrique  */  /**  * Archivo Programa  * Programa donde se realiza la copia de los movimientos de las rotaciones.  *   * Para que se ejecute es necesario poner este programa en el cron de linux  * Instruccion cron  * crontab -e  * "0 0 * * * <ruta del php> <ruta de la aplicacion>/programas/ingresar_rotacion.php [> <archiovo de salida>]"  *  * @package IPSOFT-SIIS  * @version $Revision: 1.2 $  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)  * @author Hugo F  Manrique  */  $VISTA = 'HTML';  $dir = dirname(__FILE__);  $_ROOT = str_replace("programas","",$dir);    include $_ROOT.'/includes/enviroment.inc.php';  IncludeClass("ConexionBD");  IncludeClass("AutoCarga");    $rotacion= AutoCarga::factory('RotacionGerenciaSQL', '', 'app', 'RotacionGerencia');    $fecha = date("d-m-Y");  $rst = $rotacion->IngresardatosRotacion($fecha,null,true);  if($rst)    ModuloSetVar('', '', "fecha_ultima_rotacion",$fecha);?>