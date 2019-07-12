<?php
// ConfigAplication.php  05/08/2002
// ----------------------------------------------------------------------

// Copyright (C) 2002 Alexander Giraldo
// Emai: alexgiraldo777@yahoo.com PC1

// ----------------------------------------------------------------------
// Autor: Alexander Giraldo
// Proposito del Archivo: Configuraciones Generales de la Aplicacion.
// ----------------------------------------------------------------------

// Este archivo puede ser temporal y emplear un modulo de configuraciones
// Por el momento las configuraciones se guardaran en el array global $ConfigAplicacion
$diaApp= dirname(__FILE__);
$ConfigAplication['DIR_SIIS'] = "$diaApp/";  // ruta absoluta del siis
$ConfigAplication['SessionName'] = 'SIIS';  // nombre de la sesion.
$ConfigAplication['InactivarSesion'] = 10000;  // Numero de Minutos align="left" en los cuales se inactiva la sesion.
$ConfigAplication['ModuloInicial']   = 'EJEMPLO'; // Nombre del modulo inicial solo del contenedor app.
$ConfigAplication['DefaultTheme']    = 'AzulXp'; // Nombre del Theme por defecto.
$ConfigAplication['DefaultMenu']     = ''; // Nombre de un menu por defecto.
$ConfigAplication['StyleFrames']     = true; // Estilo por defecto del Frame Work false=NO FRAMES true=FRAMES
$ConfigAplication['IPStyleFrames']   = true; // Seleccionar el Estilo del Frame Work de acuerdo al almacenado con la IP false=NO true=SI
$ConfigAplication['MaximosIntentosDeLoguin'] = 1000000;  //Limite de intentos de logueaese fallidos antes de bloquear el host.
$ConfigAplication['DefaultPais'] ='CO';
$ConfigAplication['DefaultDpto'] ='76';
$ConfigAplication['DefaultMpio'] ='001';
$ConfigAplication['DefaultZona'] ='U';
$ConfigAplication['TipoRedondeo'] ='CENTENAS'; // VALORES POSIBLES CENTAVOS,UNIDADES,DECENAS,CENTENAS,MILES DEFAULT CENTENAS
$ConfigAplication['MinutosCerradoEvoluciones']=15;
$ConfigAplication['Cliente'] ='CLINICA DE OCCIDENTE S.A.'; // VALORES POSIBLES CLINICA VALLE DEL LILI,S.O.S,CLINICA DE OCCIDENTE CALI.
//$ConfigAplication['Cliente'] ='HOSPITAL EL TUNAL E.S.E';
$ConfigAplication['DirSpool'] ="$diaApp/spool"; // Directorio Temporal de Impresion.
$ConfigAplication['DirGeneracionRips'] ="$diaApp/rips"; // Directorio Temporal de Impresion.
//Temporal de Impresion.
$ConfigAplication['DirCache'] ='cache';
//$ConfigAplication['DirCache'] ='/var/www/html/SIIS/cache';
//$ConfigAplication['DirGeneracionRips'] ='/var/www/html/SIIS/cache'; //
$ConfigAplication['DirCacheRelativo'] ='cache';
//$ConfigAplication['CaducidadReset'] ='1'; // numero de dias en que caduca una contraseña 'RESET'...
$ConfigAplication['DirSpoolRelativo'] ='spool';
$ConfigAplication['MostrarInfoFileLineErrores'] = true; //Si muestra la informacion de archivo-linea de error en los mensajes de error.
$ConfigAplication['MostrarInfoEnviromentErrores'] = true; //Si muestra la informacion de los vectores $_REQUEST Y $_SESSION  en los mensajes de error.
$ConfigAplication['ActivarDepuracionDeModulos'] = false;
$ConfigAplication['EnDepuracionMostrarVariablesEntorno'] = true;//Activa depuracion mostrando el estado de las variables de entorno al final de cada modulo.
//esta direccion es solo para la creacion de reportes en printer.php
$ConfigAplication['DirPrinter'] ="$diaApp/";
$ConfigAplication['FrecuenciaGarbage']=10; //porcentaje de frecuencia para la ejecucion del garbage numero entre 0 y 100 recomendado 5
$ConfigAplication['ServerMige'] = '192.168.1.28';//Ip del servidor donde se encuentra ubicado el MIGE-RAS
$ConfigAplication['PuertoMige'] = '8086';//Puerto del servidor donde se encuentra ubicado el MIGE-RAS

?>
