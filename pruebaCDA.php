<?php
// index.php  15/09/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Pagina Inicial
// ----------------------------------------------------------------------
$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';



if (!IncludeFile("classes/ImpresionHistoria/Extenciones_CDA_HC.class.php")) 
{
    die(MsgOut("Error al incluir archivo","El Archivo 'classes/ImpresionHistoria/Extenciones_CDA_HC.class.php' NO SE ENCUENTRA"));
}

if(!class_exists('Extenciones_CDA_HC'))
{
    die(MsgOut("NO SE CARGO LA CLASE","Extenciones_CDA_HC - NO EXISTE"));
}

//Edite AQUI
$subModulo = 'MotivoConsulta';
$FILE = "hc_modules/$subModulo/hc_".$subModulo ."_CDA.php";
if (!IncludeFile($FILE)) 
{
    die(MsgOut("Error al incluir archivo",$FILE));
}

$NameClase= $subModulo."_CDA";

$a= new $NameClase;
$salida = $a->GetXML();
if($salida===false)
{
    die(MsgOut("El submodulo Retorno FALSE",""));
}
else
{
    echo $salida;
}
?>
