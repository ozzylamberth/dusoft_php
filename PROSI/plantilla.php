<?PHP
$numerodecuenta = $_GET["numerodecuenta"];
$prefijo = $_GET["prefijo"];
$factura_fiscal = $_GET["factura_fiscal"];
$departamento_entrega = $_GET["departamento_entrega"];
$departamento_recibe = $_GET["departamento_recibe"];
$desdefecha = $_GET["desdefecha"];
$hastafecha = $_GET["hastafecha"];

$VISTA='HTML';
include 'includes/enviroment.inc.php';
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1252">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 12">
<link rel=File-List href="file:///C|/Documents and Settings/usuario/Escritorio/plantilla1_archivos/filelist.xml">
<style id="plantilla_24666_Styles">
<!--table
	{mso-displayed-decimal-separator:"\,";
	mso-displayed-thousand-separator:"\.";}
.xl1524666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6524666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:9.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl6624666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:9.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border:.5pt solid #538ED5;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:nowrap;}
.xl6724666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:9.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #538ED5;
	border-right:.5pt solid #538ED5;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:nowrap;}
.xl6824666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:9.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #538ED5;
	border-right:.5pt solid #538ED5;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:normal;}
.xl6924666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:9.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:none;
	border-right:.5pt solid #538ED5;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:nowrap;}
.xl7024666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:9.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:underline;
	text-underline-style:single;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:none;
	border-right:.5pt solid #538ED5;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:normal;}
.xl7124666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	mso-background-source:auto;
	mso-pattern:auto;
	white-space:nowrap;}
.xl7224666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:nowrap;}
.xl7324666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:nowrap;}
.xl7424666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\;\@";
	text-align:general;
	vertical-align:bottom;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:nowrap;}
.xl7524666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #538ED5;
	border-right:.5pt solid white;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:normal;}
.xl7624666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\\ hh\:mm\:ss";
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #538ED5;
	border-right:.5pt solid white;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:nowrap;
	mso-text-control:shrinktofit;}
.xl7724666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\\ hh\:mm\:ss";
	text-align:center;
	vertical-align:bottom;
	border:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:nowrap;
	mso-text-control:shrinktofit;}
.xl7824666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\;\@";
	text-align:general;
	vertical-align:bottom;
	border-top:.5pt solid #538ED5;
	border-right:.5pt solid white;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:normal;}
.xl7924666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\;\@";
	text-align:general;
	vertical-align:bottom;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:normal;}
.xl8024666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\;\@";
	text-align:general;
	vertical-align:bottom;
	border-top:none;
	border-right:.5pt solid #538ED5;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:normal;}
.xl8124666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:none;
	border-right:.5pt solid white;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:normal;}
.xl8224666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:8.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:"yyyy\\-mm\\-dd\\ hh\:mm\:ss";
	text-align:center;
	vertical-align:bottom;
	border-top:none;
	border-right:.5pt solid white;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #538ED5;
	background:#DBE5F1;
	mso-pattern:#DBE5F1 none;
	white-space:nowrap;
	mso-text-control:shrinktofit;}
.xl8324666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:10.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #95B3D7;
	border-right:none;
	border-bottom:.5pt solid #538ED5;
	border-left:.5pt solid #95B3D7;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:nowrap;}
.xl8424666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:10.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #95B3D7;
	border-right:none;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:nowrap;}
.xl8524666
	{padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:white;
	font-size:10.0pt;
	font-weight:700;
	font-style:normal;
	text-decoration:none;
	font-family:"Arial Narrow", sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:center;
	vertical-align:bottom;
	border-top:.5pt solid #95B3D7;
	border-right:.5pt solid #95B3D7;
	border-bottom:.5pt solid #538ED5;
	border-left:none;
	background:#4F81BD;
	mso-pattern:#4F81BD none;
	white-space:nowrap;}
-->
</style>
</head>

<body>
<!--[if !excel]>&nbsp;&nbsp;<![endif]-->
<!--La siguiente información se generó mediante la característica Publicar como
página Web de Microsoft Office Excel.-->
<!--Si se vuelve a publicar el mismo elemento desde Excel, se reemplazará toda
la información comprendida entre las etiquetas DIV.-->
<!----------------------------->
<!--INICIO DE LOS RESULTADOS DEL ASISTENTE PARA PUBLICAR COMO PÁGINA WEB DE
EXCEL -->
<!----------------------------->

<div id="plantilla_24666" align=center x:publishsource="Excel"><!--La siguiente información se generó mediante la característica Publicar como
página Web de Microsoft Office Excel.--><!--Si se vuelve a publicar el mismo elemento desde Excel, se reemplazará toda
la información comprendida entre las etiquetas DIV.--><!-----------------------------><!--INICIO DE LOS RESULTADOS DEL ASISTENTE PARA PUBLICAR COMO PÁGINA WEB DE
EXCEL --><!----------------------------->

<table border=0 cellpadding=0 cellspacing=0 width=927 style='border-collapse:
 collapse;table-layout:fixed;width:696pt'>
 <col width=48 style='mso-width-source:userset;mso-width-alt:1755;width:36pt'>
 <col width=55 style='mso-width-source:userset;mso-width-alt:2011;width:41pt'>
 <col width=58 style='mso-width-source:userset;mso-width-alt:2121;width:44pt'>
 <col width=63 style='mso-width-source:userset;mso-width-alt:2304;width:47pt'>
 <col width=60 style='mso-width-source:userset;mso-width-alt:2194;width:45pt'>
 <col width=75 style='mso-width-source:userset;mso-width-alt:2742;width:56pt'>
 <col width=74 style='mso-width-source:userset;mso-width-alt:2706;width:56pt'>
 <col width=83 style='mso-width-source:userset;mso-width-alt:3035;width:62pt'>
 <col width=84 style='mso-width-source:userset;mso-width-alt:3072;width:63pt'>
 <col width=98 style='mso-width-source:userset;mso-width-alt:3584;width:74pt'>
 <col width=64 style='mso-width-source:userset;mso-width-alt:2340;width:48pt'>
 <col width=94 style='mso-width-source:userset;mso-width-alt:3437;width:71pt'>
 <col width=71 style='mso-width-source:userset;mso-width-alt:2596;width:53pt'>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1524666 width=48 style='height:15.0pt;width:36pt'></td>
  <td class=xl1524666 width=55 style='width:41pt'></td>
  <td class=xl1524666 width=58 style='width:44pt'></td>
  <td class=xl1524666 width=63 style='width:47pt'></td>
  <td class=xl1524666 width=60 style='width:45pt'></td>
  <td class=xl1524666 width=75 style='width:56pt'></td>
  <td class=xl1524666 width=74 style='width:56pt'></td>
  <td class=xl1524666 width=83 style='width:62pt'></td>
  <td class=xl1524666 width=84 style='width:63pt'></td>
  <td class=xl1524666 width=98 style='width:74pt'></td>
  <td class=xl1524666 width=64 style='width:48pt'></td>
  <td class=xl1524666 width=94 style='width:71pt'></td>
  <td class=xl1524666 width=71 style='width:53pt'></td>
 </tr>
 <tr height=20 style='mso-height-source:userset;height:15.0pt'>
  <td height=20 class=xl1524666 style='height:15.0pt'></td>
  <td class=xl1524666></td>
  <td class=xl1524666></td>
  <td class=xl1524666></td>
  <td class=xl1524666></td>
  <td colspan=4 class=xl8324666 style='border-right:.5pt solid #95B3D7'>MOVIMIENTO
  DOCUMENTO</td>
  <td class=xl1524666></td>
  <td class=xl1524666></td>
  <td class=xl1524666></td>
  <td class=xl1524666></td>
 </tr>
 <tr class=xl6524666 height=36 style='mso-height-source:userset;height:27.0pt'>
  <td height=36 class=xl6624666 style='height:27.0pt'>CUENTA</td>
  <td class=xl6824666 width=58 style='width:44pt'>FEC INGRESO</td>
  <td class=xl6824666 width=63 style='width:47pt'>FEC EGRESO</td>
  <td class=xl6724666>FACTURA</td>
  <td class=xl6824666 width=60 style='width:45pt'>FEC FACTURA</td>
  <td class=xl6924666>ORIGEN</td>
  <td class=xl6924666>DESTINO</td>
  <td class=xl7024666 width=83 style='width:62pt'>FEC RELACION</td>
  <td class=xl7024666 width=84 style='width:63pt'>FEC RECIBE</td>
  <td class=xl6724666>ENVIO</td>
  <td class=xl6724666>RADICACION</td>
  <td class=xl6724666>GLOSA</td>
  <td class=xl6724666>PAGO</td>
 </tr>
 
 <?PHP
 open_database();

$query = "SELECT a.numerodecuenta, b.prefijo, b.factura_fiscal, c.plan_id
FROM cuentas a INNER JOIN relacion_cuentas_detalle b ON a.numerodecuenta = b.numerodecuenta
INNER JOIN planes c ON a.plan_id = c.plan_id INNER JOIN relacion_cuentas d ON b.relacion_id = d.relacion_id";
$query_records = "SELECT COUNT(*) AS numreg 
FROM cuentas a INNER JOIN relacion_cuentas_detalle b ON a.numerodecuenta = b.numerodecuenta
INNER JOIN planes c ON a.plan_id = c.plan_id INNER JOIN relacion_cuentas d ON b.relacion_id = d.relacion_id";

$where = build_where("a.numerodecuenta", $numerodecuenta, "C",
					"b.prefijo", $prefijo, "C",
					"b.factura_fiscal", $factura_fiscal, "C",
					"d.departamento_entrega", $departamento_entrega, "C",
					"d.departamento_recibe", $departamento_recibe, "C");
    

$filtrofecha = build_beetwen("d.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

$agrupamiento = "a.numerodecuenta, b.prefijo, b.factura_fiscal, c.plan_id";
$grupo .= $agrupamiento;
$_GET["orientation"] = 2;
$order ="a.numerodecuenta ASC, b.prefijo||b.factura_fiscal";
$_GET["imprimir"] = "SI";
require("includes/consulta.php");

$i = 1;


while ($row_relacion = fetch_object($result)) { 
 	
 	$datos_ingreso = "SELECT cuentas.numerodecuenta, ingresos.ingreso, ingresos.fecha_ingreso, 
	 					ingresos_salidas.fecha_registro
 						FROM   public.cuentas cuentas INNER JOIN (public.ingresos_salidas ingresos_salidas 
						RIGHT OUTER JOIN public.ingresos ingresos ON ingresos_salidas.ingreso=ingresos.ingreso) 
						ON cuentas.ingreso=ingresos.ingreso
 						WHERE  cuentas.numerodecuenta=$row_relacion->numerodecuenta
";
	 											
    				$resultado_ingreso = execute_query($dbh, $datos_ingreso);
       				$row_ingreso = pg_fetch_row($resultado_ingreso);
					free_result($resultado_ingreso);
					$quitar_decimal_ingreso = explode(" ", $row_ingreso[2]);
					$fecha_ingreso = $quitar_decimal_ingreso[0];
					$quitar_decimal_egreso = explode(" ", $row_ingreso[3]);
					$fecha_egreso = $quitar_decimal_egreso[0];
 
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
	
	$fecha_factura = "SELECT a.fecha_registro FROM fac_facturas a
	 											WHERE a.prefijo = '$row_relacion->prefijo'
												AND a.factura_fiscal = $row_relacion->factura_fiscal";
	 											
    											$resultado_FECHA = execute_query($dbh, $fecha_factura);
       											$fec_factura = pg_fetch_row($resultado_FECHA);
												free_result($resultado_FECHA);
												$quitar_decimal_factura = explode(" ", $fec_factura[0]);
												$F = $quitar_decimal_factura[0];
    
?>
 
 <tr class=xl7124666 height=32 style='mso-height-source:userset;height:24.0pt'>
  <td height=32 class=xl7224666 align=right style='height:24.0pt'><?php echo $row_relacion->numerodecuenta?></td>
  <td class=xl7424666 align=right><?php echo $fecha_ingreso?></td>
  <td class=xl7424666 align=right><?php echo $fecha_egreso?></td>
  <td class=xl7324666><?php echo $row_relacion->prefijo." ".$row_relacion->factura_fiscal?></td>
  <td class=xl7424666 align=right><?php echo $F?></td>
  <?php
	
	
		if ($row_relacion->prefijo=="" AND $row_relacion->factura_fiscal ==""){
		?>
		<td class=xl7524666 colspan="4"><table width="300" border="1" cellspacing="0" cellpadding="1">
          <tr>
            <th width="60" scope="col">&nbsp;</th>
            <th width="70" scope="col">&nbsp;</th>
            <th width="79" scope="col">&nbsp;</th>
            <th width="73" scope="col">&nbsp;</th>
          </tr>
        </table></td>
  <?php
		}
		?>
		
		<?php
		if ($row_relacion->prefijo<>"" AND $row_relacion->factura_fiscal<>""){
		
		if($departamento_entrega <> '' AND $departamento_recibe <> ''){
				$result_relacion_detalle=execute_query($dbh, "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe
									FROM relacion_cuentas a, relacion_cuentas_detalle b
									WHERE a.relacion_id = b.relacion_id AND 
									b.numerodecuenta = $row_relacion->numerodecuenta
									AND (b.prefijo = '$row_relacion->prefijo' AND b.factura_fiscal = $row_relacion->factura_fiscal)
									AND a.departamento_entrega = '$departamento_entrega' 
									AND a.departamento_recibe = '$departamento_recibe'
									ORDER BY a.relacion_id");
				}
				else if ($departamento_entrega <> '' AND $departamento_recibe == ''){
				$result_relacion_detalle=execute_query($dbh, "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe
									FROM relacion_cuentas a, relacion_cuentas_detalle b
									WHERE a.relacion_id = b.relacion_id AND 
									b.numerodecuenta = $row_relacion->numerodecuenta
									AND (b.prefijo = '$row_relacion->prefijo' AND b.factura_fiscal = $row_relacion->factura_fiscal)
									AND a.departamento_entrega = '$departamento_entrega'
									ORDER BY a.relacion_id");
				}
				else if ($departamento_entrega == '' AND $departamento_recibe <> ''){
				$result_relacion_detalle=execute_query($dbh, "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe
									FROM relacion_cuentas a, relacion_cuentas_detalle b
									WHERE a.relacion_id = b.relacion_id AND 
									b.numerodecuenta = $row_relacion->numerodecuenta
									AND (b.prefijo = '$row_relacion->prefijo' AND b.factura_fiscal = $row_relacion->factura_fiscal)
									AND a.departamento_recibe = '$departamento_recibe'
									ORDER BY a.relacion_id");
				}
				else {
				$result_relacion_detalle=execute_query($dbh, "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe
									FROM relacion_cuentas a, relacion_cuentas_detalle b
									WHERE a.relacion_id = b.relacion_id AND 
									b.numerodecuenta = $row_relacion->numerodecuenta
									AND (b.prefijo = '$row_relacion->prefijo' AND b.factura_fiscal = $row_relacion->factura_fiscal)
									ORDER BY a.relacion_id");
				}
				
				while($row_relacion_detalle = fetch_object($result_relacion_detalle)){
									 
									 			$departamento_E = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_entrega'";
	 											
    											$resultado_E = execute_query($dbh, $departamento_E);
       											$dpto_E = pg_fetch_row($resultado_E);
									       		$E = $dpto_E[0];
									      		free_result($resultado_E);
									      		
									      		$departamento_R = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_recibe'";
	 											
    											$resultado_R = execute_query($dbh, $departamento_R);
       											$dpto_R = pg_fetch_row($resultado_R);
									       		$R = $dpto_R[0];
									      		free_result($resultado_E);
												
		
		
		?>
		
  <td class=xl7524666 colspan="4"><table width="300" border="1" cellspacing="0" cellpadding="1">
          <tr>
            <th width="60" scope="col">&nbsp;</th>
            <th width="70" scope="col">&nbsp;</th>
            <th width="79" scope="col">&nbsp;</th>
            <th width="73" scope="col">&nbsp;</th>
          </tr>
        </table></td>
  <?php
  }
		
		}
		
		
		?>
 
  <td class=xl7824666 width=98 style='border-top:none;border-left:none;
  width:74pt'># 111148 - 2008-03-01</td>
  <td class=xl7424666 align=right>2008-03-05</td>
  <td class=xl7924666 width=94 style='width:71pt'># 97821 - 2008-04-01</td>
  <td class=xl8024666 width=71 style='width:53pt'>8R 111111 - 2008-03-30<!-----------------------------><!--FINAL DE LOS RESULTADOS DEL ASISTENTE PARA PUBLICAR COMO PÁGINA WEB DE
EXCEL--><!-----------------------------></td>
 </tr>
 
 <![if supportMisalignedColumns]>
 <tr height=0 style='display:none'>
  <td width=48 style='width:36pt'></td>
  <td width=55 style='width:41pt'></td>
  <td width=58 style='width:44pt'></td>
  <td width=63 style='width:47pt'></td>
  <td width=60 style='width:45pt'></td>
  <td width=75 style='width:56pt'></td>
  <td width=74 style='width:56pt'></td>
  <td width=83 style='width:62pt'></td>
  <td width=84 style='width:63pt'></td>
  <td width=98 style='width:74pt'></td>
  <td width=64 style='width:48pt'></td>
  <td width=94 style='width:71pt'></td>
  <td width=71 style='width:53pt'></td>
 </tr>
 <![endif]>
 <?php
   $i ++;
}
?>
</table>

</div>


<!----------------------------->
<!--FINAL DE LOS RESULTADOS DEL ASISTENTE PARA PUBLICAR COMO PÁGINA WEB DE
EXCEL-->
<!----------------------------->
</body>

</html>
