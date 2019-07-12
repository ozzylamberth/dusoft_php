<?php

$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';


if (!IncludeFile("classes/ContabilidadGeneral/ContabilizarFacturas.class.php"))
{
    die(MsgOut("Error al incluir archivo","El Archivo 'classes/ContabilidadGeneral/ContabilizarFacturas.class.php' NO SE ENCUENTRA"));
}

if(!class_exists('ContabilizarFacturas'))
{
    die(MsgOut("NO SE CARGO LA CLASE","ContabilizarFacturas - NO EXISTE"));
}

$a= new ContabilizarFacturas;

if($a->ContabilizarFactura('01','FS',1,true))
{
    echo "OK";
}
else
{
    die(MsgOut($a->Err(),$a->ErrMsg()));
}


?>
