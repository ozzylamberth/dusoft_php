<?php
  /**
  * $Id: zipArchiveDownload.php,v 1.1 2009/02/03 16:04:40 hugo Exp $
  *
  * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
  * @package IPSOFT-SIIS
  * @author Hugo F  Manrique
  *
  * Script para descargar archivos, espera en la variable id, 
  * el nombre de la carpeta que se va a comprimir y en la variable ubicacion
  * la carpeta contenedora
  */
  $VISTA = 'HTML';
  $_ROOT = '../../';
  include $_ROOT.'includes/enviroment.inc.php';

  $request = $_REQUEST;

  IncludeClass("AutoCarga");
  $zip = AutoCarga::factory("zipArchive");
  $dir = GetVarConfigAplication('DIR_SIIS').$request['ubicacion']."/".$request['id'];
  $destino = ($request['destino'])? $request['destino']:"rips";
  $nombre_arch = ($request['nombre_arch'])? $request['nombre_arch']:"RIPS_".str_pad($request['id'],6,0,STR_PAD_LEFT);
  //print_r($dir);
  
  $directorio = opendir($dir);
  while ($archivo = readdir($directorio)) 
  {
    if(!is_dir("$dir/$archivo"))
      $zip->addFile($dir.'/'.$archivo, $archivo);
  }
  closedir($directorio);
 
  $pathSave = GetVarConfigAplication('DIR_SIIS').$destino"/".$nombre_arch.".zip";
  print_r($pathSave);
  $zip->saveZip($pathSave);
  $zip->downloadZip($pathSave);
?>