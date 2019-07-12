<?php
/**
 * $Id: download.php,v 1.2 2006/02/07 13:28:22 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Script para descargar archivos, espera las variables "archivo" y "comprimir"
 * en el $_REQUEST
 */
if(empty($_REQUEST['archivo']))
{
	echo "<h1>No hay un Archivo para descargar</h1>";
	exit();
}
else
	$archivo = $_REQUEST['archivo'];
if(file_exists($archivo))
{
	if(!is_readable($archivo))
	{
		echo "<h1>Acceso denegado al Archivo $archivo </h1>";
		exit();
	}
}
else
{
	echo "<h1>El Archivo $archivo no existe</h1>";
	exit();
}
if(isset($_REQUEST['comprimir']))
{
	$info = pathinfo($archivo);
	$tmpname = "/tmp/".$info['basename'].".tar.gz";
	if(!comprimirTarGz($archivo,$tmpname))
	{
		echo "<h1>Error al comprimir el archivo</h1>";
		exit();
	}
	$archivo = $tmpname;
}
$info = pathinfo($archivo);
$nombre = $info['basename'];
$fp = fopen($archivo,"r");
$filedata=fread($fp,filesize($archivo));
fclose($fp);
header('Pragma: private');
header('Cache-control: private, must-revalidate');
header("Content-type: text/x-ms-iqy");
header("Content-Disposition: attachment; filename=$nombre");
print $filedata; 

/**
 * Comprime un archivo en TarGz
 *
 * @param string $archivo
 * @param string $destino
 */
function comprimirTarGz($archivo,$destino)
{
	include_once "Archive/Tar.php";
	if(!file_exists($archivo))
		return false;
	//$info = pathinfo($archivo);
	$tar_object = new Archive_Tar($destino, true);
	$info = pathinfo($archivo);
	chdir ( $info['dirname'] );
	if(is_dir($archivo))
		$v_list[0]=$info['basename']."/";
	else
		$v_list[0]=$info['basename'];
	if(!$tar_object->create($v_list))
	{
		return false;
	}
	return true;
}//Fin compirmirTarGz
?>