<?php
$fecha =  "2006-04-21";
$timestamp = strtotime($fecha);
$fecha_expira = $timestamp + (60*60*24*3);
echo "Fecha de expiración: ".date("Y-m-d",$fecha_expira);
?> 