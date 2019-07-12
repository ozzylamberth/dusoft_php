<?php
// $Id: Contenido.php,v 1.4 2007/12/20 13:32:48 hugo Exp $
// ----------------------------------------------------------------------
// Copyright (C) 2006 IPSOFT S.A.
// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// ----------------------------------------------------------------------

$t0=microtime();
//Definir el tipo de vista a usar
$VISTA='HTML';

include 'includes/enviroment.inc.php';

if($FrameWork != 'SIIS' && $contenedor)
{
	require "v2/index.php";
	exit;
}

echo PrintModulo();

if($ConfigAplication['ActivarDepuracionSQL'])
{
    list($dbconn) = GetDBconn();
    $dbconn->LogSQL(false);
}

if($ConfigAplication['ActivarTiempoDeEjecucion'])
{
    $t1=microtime();

    $a0 = split(' ',$t0);
    $a0 = (float)$a0[1]+(float)$a0[0];

    $a1 = split(' ',$t1);
    $a1 = (float)$a1[1]+(float)$a1[0];

    $time = $a1 - $a0;
    $time = number_format($time,3,'.','');
    echo "<br><CENTER><b>TIME EXEC : $time</b></CENTER><br>".$_REQUEST{'modulo'}." ".$_REQUEST{'metodo'}."";
}

?>
