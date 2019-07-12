<?php

$t0=microtime();

$VISTA='HTML';
$_ROOT = '';

include $_ROOT . 'includes/enviroment.inc.php';

if (!IncludeClass("ArchivoVersiones","versiones"))
{
    die(MsgOut("NO SE PUDO INCLUIR LA CLASE"));
}

$a = new ArchivoVersiones;
echo "<pre>".print_r($a->CrearArchivoDeVersiones(),true)."</pre>";


$t1=microtime();

$a0 = split(' ',$t0);
$a0 = (float)$a0[1]+(float)$a0[0];

$a1 = split(' ',$t1);
$a1 = (float)$a1[1]+(float)$a1[0];

$time = $a1 - $a0;
$time = number_format($time,3,'.','');
echo "<br><b>TIME EXEC : $time</b><br><br>";

?>
