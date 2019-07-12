<?php
// Contenido.php  05/08/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// ----------------------------------------------------------------------

//Definir el tipo de vista a usar
$t0=microtime();

$VISTA='HTML';
include 'includes/enviroment.inc.php';
//IncludeLib('datospaciente');
//print_r(GetDatosPaciente($pacienteId='66729536',$tipoIdPaciente='CC',$ingreso='',$evolucion=''));
//print_r(GetDatosPaciente($pacienteId='',$tipoIdPaciente='',$ingreso='500',$evolucion=''));
//print_r(GetDatosPaciente($pacienteId='',$tipoIdPaciente='',$ingreso='',$evolucion='800'));


IncludeLib('malla_validadora');
$a = MallaValidadoraValidarCargo($cargo_base='873121',$plan_id=319,$Servicio='3');
if(is_array($a))
{
    ECHO "MALLA VALIDADORA OK:<BR><BR>";
    ECHO "<PRE>".PRINT_R($a,TRUE)."</PRE><BR><BR>";
}
else
{
    ECHO "LA MALLA VALIDADORA RETORNO:<BR><BR>";
    ECHO $a;
    ECHO "<BR>";
}

$t1=microtime();

$a0 = split(' ',$t0);
$a0 = (float)$a0[1]+(float)$a0[0];

$a1 = split(' ',$t1);
$a1 = (float)$a1[1]+(float)$a1[0];

$time = $a1 - $a0;
$time = number_format($time,3,'.','');
echo "<br><b>TIME EXEC : $time</b><br>";


?>
