<?php
$VISTA='HTML';
include 'includes/enviroment.inc.php';
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");



?>

<html>
<head>
<title>Eventos del Sitema del Sistema</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1"></head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();

procesar_entrada("GET", "pagina", "action", "numerodecuenta", "prefijo", "factura_fiscal", "desdefecha", "hastafecha", "departamento_entrega", "departamento_recibe", "tercero", "order", "orientation");

$pagina = $_GET["pagina"];

$action = get_value($_GET["action"], "C");

$numerodecuenta = get_value($_GET["numerodecuenta"], "C");
$prefijo = get_value($_GET["prefijo"], "C");
$factura_fiscal = get_value($_GET["factura_fiscal"], "C");
$desdefecha = get_value($_GET["desdefecha"], "C");
$hastafecha = get_value($_GET["hastafecha"], "C");
$departamento_entrega = get_value($_GET["departamento_entrega"], "C");
$departamento_recibe = get_value($_GET["departamento_recibe"], "C");
$tercero = get_value($_GET["tercero"], "C");
$color = "";
$colorfila ="";


if($desdefecha){
$desdefecha = $desdefecha.' 00:00:00';
}
if($hastafecha){
$hastafecha = $hastafecha.' 23:59:59';
}


$id_tercero = explode(":", $tercero);
$tipo_id_tercero = $id_tercero[0];
$tercero_id = $id_tercero[1];


				function duracion_cuenta ($fecha_inicial, $fecha_recibe, $estado)
				{
					
					if($estado == "N"){
						return ("No aceptada");
					}
					else{
						if($fecha_recibe){
						
							$duracion = floor((strtotime($fecha_recibe, 0)-strtotime($fecha_inicial, 0))/86400)." dia(s)";
								if ($duracion == 0){
									$dia_inicial = strftime("%d",strtotime($fecha_incial));
									$mes_inicial = strftime("%m",strtotime($fecha_inicial));
									$dia_final = strftime("%d",strtotime($fecha_recibe));
									$mes_final = strftime("%m",strtotime($fecha_recibe));
										if(($dia_inicial < $dia_final AND $mes_inicial == $mes_final)OR
											($dia_inicial > $dia_final AND $mes_inicial < $mes_final)){
											$duracion = $duracion + 1 ." dia(s)";
										}
									}
							return ($duracion);
						}
						else{
							return("Sin confirmar");
						}
					}			
				}
				
				function duracion_relacion ($fecha_relacion, $fecha_recibe, $estado)
				{
					
					if($estado == "N"){
						return ("No aceptada");
					}
					else{
						if($fecha_recibe){
						
							$duracion = floor((strtotime($fecha_recibe, 0)-strtotime($fecha_relacion, 0))/86400)." dia(s)";
							if ($duracion == 0){
								$dia_relacion = strftime("%d",strtotime($fecha_relacion));
								$mes_relacion = strftime("%m",strtotime($fecha_relacion));
								$dia_recibe = strftime("%d",strtotime($fecha_recibe));
								$mes_recibe = strftime("%m",strtotime($fecha_recibe));
									if(($dia_relacion < $dia_recibe AND $mes_relacion == $mes_recibe)OR
										($dia_relacion > $dia_recibe AND $mes_relacion < $mes_recibe)){
										$duracion = $duracion + 1 ." dia(s)";
									}
							}
							return ($duracion);
						}
						else{
							return("Sin confirmar");
						}
					}			
				}

if($action!=""){

$query = "SELECT a.numerodecuenta, b.prefijo, b.factura_fiscal, c.plan_id, c.tipo_tercero_id, c.tercero_id
FROM cuentas a INNER JOIN relacion_cuentas_detalle b ON a.numerodecuenta = b.numerodecuenta
INNER JOIN planes c ON a.plan_id = c.plan_id INNER JOIN relacion_cuentas d ON b.relacion_id = d.relacion_id";
$query_records = "SELECT COUNT(*) AS numreg 
FROM cuentas a INNER JOIN relacion_cuentas_detalle b ON a.numerodecuenta = b.numerodecuenta
INNER JOIN planes c ON a.plan_id = c.plan_id INNER JOIN relacion_cuentas d ON b.relacion_id = d.relacion_id";

$where = build_where("a.numerodecuenta", $numerodecuenta, "C",
					"b.prefijo", $prefijo, "C",
					"b.factura_fiscal", $factura_fiscal, "C",
					"c.tipo_tercero_id", $tipo_id_tercero, "C",
					"c.tercero_id", $tercero_id, "C",
					"d.departamento_entrega", $departamento_entrega, "C",
					"d.departamento_recibe", $departamento_recibe, "C");
    

$filtrofecha = build_beetwen("d.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

$agrupamiento = "a.numerodecuenta, b.prefijo, b.factura_fiscal, c.plan_id, c.tipo_tercero_id, c.tercero_id";
$grupo = "";
$grupo .= $agrupamiento;
$_GET["orientation"] = 2;
$order ="a.numerodecuenta ASC, b.prefijo||b.factura_fiscal";

require("includes/consulta.php");

$result1 = execute_query($dbh, $query_records);
$num_records = pg_num_rows($result1);
}
?>

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="buscarfalla" action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <table width="53%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; Cuenta</font></td>
      <td bgcolor="#EEEEEE"><input name="numerodecuenta" type="text" id="nuemrodecuenta" maxlength="10" value="<?=$numerodecuenta?>" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Factura Fiscal</font></td>
      <td bgcolor="#EEEEEE"><select name="prefijo" id="prefijo">
          <option value=''>Todas</option>
<?php
$result_prefijo = execute_query($dbh, "SELECT * FROM documentos WHERE tipo_doc_general_id = 'FV01' ORDER BY prefijo");
while (($row = fetch_object($result_prefijo))) {
    echo "<option value='" . $row->prefijo . "'";
    if ($row->prefijo == $prefijo) echo " selected ";
    echo ">" . $row->prefijo . "</option>";
} 
free_result($result_prefijo);

?>
        </select>
        <input name="factura_fiscal" type="texto" id="factura_fiscal" value="<?=$factura_fiscal?>" size="10" maxlength="8" class="textbox"></td>
    </tr>
    
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Tercero</font></td>
      <td bgcolor="#EEEEEE"><select name="tercero" id="tercero">
          <option value=''>Todas</option>
<?php
$result_tercero = execute_query($dbh, "SELECT terceros.nombre_tercero, terceros_clientes.tipo_id_tercero, terceros_clientes.tercero_id, terceros_clientes.tipo_id_tercero||':'||terceros_clientes.tercero_id AS terc
 FROM   public.terceros_clientes terceros_clientes INNER JOIN public.terceros terceros ON (terceros_clientes.tipo_id_tercero=terceros.tipo_id_tercero) AND (terceros_clientes.tercero_id=terceros.tercero_id)
 ORDER BY terceros.nombre_tercero
");
while (($row = fetch_object($result_tercero))) {
    echo "<option value='".$row->terc."'";
    if ($row->tipo_id_tercero == $tipo_id_tercero
		AND $row->tercero_id == $tercero_id) echo " selected ";
    echo ">" . $row->nombre_tercero . "</option>";
} 
free_result($result_tercero);

?>
        </select></td>
    </tr>
    
    <tr> 
      <td bgcolor="#EEEEEE">Desde fecha</td>
      <td bgcolor="#EEEEEE"><input name="desdefecha" type="text" class="textbox" id="desdefecha" maxlength="10" value="<?=$desdefecha?>">
	  <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></td>
    </tr>
    
    <tr>
      <td bgcolor="#EEEEEE">Hasta fecha</td>
      <td bgcolor="#EEEEEE"><input name="hastafecha" type="text" class="textbox" id="hastafecha" maxlength="10" value="<?=$hastafecha?>">
	  <a href="javascript:cal10.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></td>
    </tr>
    <script language="JavaScript">
			<!-- // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				

				var cal9 = new calendar3(document.forms['tstest'].elements['desdefecha']);
				cal9.year_scroll = true;
				cal9.time_comp = false;
				var cal10 = new calendar3(document.forms['tstest'].elements['hastafecha']);
				cal10.year_scroll = true;
				cal10.time_comp = false;
				
			//-->
			</script>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Envia</font></td>
      <td bgcolor="#EEEEEE"><select name="departamento_entrega" id="departamento_entrega">
          <option value=''>Todas</option>
<?php
$result_departamento_entrega = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas) ORDER BY 5");
while (($row = fetch_object($result_departamento_entrega))) {
    echo "<option value='" . $row->departamento . "'";
    if ($row->departamento == $departamento_entrega) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result_departamento_entrega);

?>
        </select></td>
    </tr>
	<tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Recibe</font></td>
      <td bgcolor="#EEEEEE"><select name="departamento_recibe" id="departamento_recibe">
          <option value=''>Todas</option>
<?php
$result_departamento_recibe = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas) ORDER BY 5");
while (($row = fetch_object($result_departamento_recibe))) {
    echo "<option value='" . $row->departamento . "'";
    if ($row->departamento == $departamento_recibe) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result_departamento_recibe);

?>
        </select></td>
    </tr>
    
    <tr> 
      <td>
      <input type="hidden" name="action" value="Buscar">
	  <input type="submit" name="buscar" value="Buscar"><input type="button" name="Submit3" value="Imprimir Listado" onClick="javascript: window.open('rep_relaciones_maestro.php?numerodecuenta=<?=$numerodecuenta?>&amp;prefijo=<?=$prefijo?>&amp;factura_fiscal=<?=$factura_fiscal?>&amp;tipo_id_tercero=<?=$tipo_id_tercero?>&amp;tercero_id=<?=$tercero_id?>&amp;departamento_entrega=<?=$departamento_entrega?>&amp;departamento_recibe=<?=$departamento_recibe?>&amp;desdefecha=<?=$desdefecha?>&amp;hastafecha=<?=$hastafecha?>&amp;orientation=<?=$orientation?>&amp;order=<?=$order?>', 'imprimirlistado');" ></td>
    </tr>
  </table>
  
<?php
if($num_records > 0){  
 ?>
<br>
<table width="200%" border="0" cellspacing="0" align="center">

  
  <tr>
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Tercero</font></strong></a></td> 
  <td width="6%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Ingreso</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Egreso</font></strong></a></td>
  <td width="9%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Factura</font></strong></a></td>
  <td colspan="7" background="imagenes/cellpic1.gif"><div align="center"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">MOVIMIENTO DOCUMENTO</font></strong></a></div></td>
  <td width="6%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Envio</font></strong></a></td>
  <td width="6%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Radicacion </font></strong></td>
  <td colspan="2" background="imagenes/cellpic1.gif"><div align="center"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Glosa</font></strong></div></td>
  <td width="3%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Pago</font></strong></td>
  </tr>
<?php
}
?>  

<?php

$i = 1;


while (($i <= $records_per_page) && ($row_relacion = fetch_object($result))) { 
 	
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
					if($fecha_ingreso <> ""){
					$fecha_ingreso = strftime('%d/%m/%y',strtotime($fecha_ingreso));
					}
					$quitar_decimal_egreso = explode(" ", $row_ingreso[3]);
					$fecha_egreso = $quitar_decimal_egreso[0];
					if($fecha_egreso <> ""){
					$fecha_egreso = strftime('%d/%m/%y',strtotime($fecha_egreso));
					}
					
 
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
												if($F <> ""){
													$F = strftime('%d/%m/%y',strtotime($F));
												}
    $tercero = "SELECT a.nombre_tercero FROM terceros a
	 											WHERE a.tipo_id_tercero = '$row_relacion->tipo_tercero_id'
												AND a.tercero_id = $row_relacion->tercero_id";
	 											
    											$resultado_tercero = execute_query($dbh, $tercero);
       											$id_tercero = pg_fetch_row($resultado_tercero);
												free_result($resultado_tercero);
    
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';">
	<td bgcolor="<?php echo $color; ?>"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $id_tercero[0]?></a></font></div></td> 
    <td bgcolor="<?php echo $color; ?>"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $row_relacion->numerodecuenta?></a></font></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $fecha_ingreso?></a></font></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $fecha_egreso?></font></div></td>
	<td bgcolor="<?php echo $color; ?>"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	  <?php echo $row_relacion->prefijo." ".$row_relacion->factura_fiscal?></font></div></td>
	<td bgcolor="<?php echo $color; ?>"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	  <?php echo $F?></font></div></td>
	<td colspan="7" bgcolor="<?php echo $color; ?>">
	  <div align="justify">
	    <table width="436" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000">
	      <tr>
		  	<td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Fecha Ref</font></strong></a></font></div></td> 
	        <td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Origen</font></strong></a></font></div></td>
		      <td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Destino</font></strong></font></div></td>
		      <td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Fec Relacion </font></strong></a></font></div></td>
		      <td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Fec Recibe </font></strong></a></font></div></td>
			  <td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Duracion Relacion</font></strong></a></font></div></td>
			  <td width="10%" background="imagenes/cellpic1.gif"><div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#003366">Duracion Cuenta</font></strong></a></font></div></td>
	        </tr>
	      <?php
	
	
		if ($row_relacion->prefijo=="" AND $row_relacion->factura_fiscal ==""){
		
		
		$result_relacion_detalle=execute_query($dbh, "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe, a.usuario_entrega, a.usuario_recibe, b.estado, b.fecha_inicial, b.obs_fecha_inicial
									FROM relacion_cuentas a, relacion_cuentas_detalle b
									WHERE a.relacion_id = b.relacion_id AND 
									b.numerodecuenta = $row_relacion->numerodecuenta
									AND (b.prefijo IS NULL AND b.factura_fiscal IS NULL)
									ORDER BY b.relacion_id");
									
									
									
									while($row_relacion_detalle = fetch_object($result_relacion_detalle)){
									 
									 			$departamento_E = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_entrega'";
	 											
    											$resultado_E = execute_query($dbh, $departamento_E);
       											$dpto_E = pg_fetch_row($resultado_E);
									       		$E = $dpto_E[0];
									      		free_result($resultado_E);
									      		
									      		$usuario_E = "SELECT usuario, nombre FROM system_usuarios 
	 											WHERE usuario_id = $row_relacion_detalle->usuario_entrega";
	 											
    											$usuario_resultado_E = execute_query($dbh, $usuario_E);
       											$user_E = pg_fetch_row($usuario_resultado_E);
									       		$UE = $user_E[0];
									      		free_result($usuario_resultado_E);
									      		
									      		$departamento_R = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_recibe'";
	 											
    											$resultado_R = execute_query($dbh, $departamento_R);
       											$dpto_R = pg_fetch_row($resultado_R);
									       		$R = $dpto_R[0];
									      		free_result($resultado_E);
									      		
									      		$usuario_R = "SELECT usuario, nombre FROM system_usuarios 
	 											WHERE usuario_id = $row_relacion_detalle->usuario_recibe";
	 											
    											$usuario_resultado_R = execute_query($dbh, $usuario_R);
       											$user_R = pg_fetch_row($usuario_resultado_R);
									       		$UR = $user_R[0];
									      		free_result($usuario_resultado_R);
									      		
									?>
	      
	      <tr title="Relacion # <?php echo $row_relacion_detalle->relacion_id; ?>">
		  	<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_inicial;?></font></div>
			  <div align="center"><font size="1" color="#8588FC" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->obs_fecha_inicial;?></font></div></td>	
	        <td bgcolor="<?php echo $color; ?>" >
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $E;echo "<br>";?></font></div>
			  <div align="center"><font size="1" color="#8588FC" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $UE;?></font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $R;echo "<br>";?></font></div>
			  <div align="center"><font size="1" color="#8588FC" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $UR;?></font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_registro;?>									</font></div></td>
		      <?php
              
              if($row_relacion_detalle->estado =='N'){
			  	$fecha_recibe = "No Aceptada";
			  }
			  else{
			  		$fecha_recibe = $row_relacion_detalle->fecha_recibe;
				}
				?>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fecha_recibe;?></font></div></td>
              <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo duracion_relacion($row_relacion_detalle->fecha_registro, $row_relacion_detalle->fecha_recibe, $row_relacion_detalle->estado);?></font></div></td>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo duracion_cuenta($row_relacion_detalle->fecha_inicial, $row_relacion_detalle->fecha_recibe, $row_relacion_detalle->estado);?></font></div></td>
	        </tr>
	      
	      <?php	
							$duracion = 0;		}
		}
		else{
		
				/*if($departamento_entrega <> '' AND $departamento_recibe <> ''){
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
				}*/
		
					$query = "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe, a.usuario_entrega, a.usuario_recibe, b.estado, b.fecha_inicial, b.obs_fecha_inicial
									FROM relacion_cuentas a INNER JOIN relacion_cuentas_detalle b ON a.relacion_id = b.relacion_id";
					$where = build_where("b.prefijo", $row_relacion->prefijo, "C",
					"b.factura_fiscal", $row_relacion->factura_fiscal, "C",
					"b.numerodecuenta", $row_relacion->numerodecuenta, "C",
					"a.departamento_entrega", $departamento_entrega, "C",
					"a.departamento_recibe", $departamento_recibe, "C");
    

$filtrofecha = build_beetwen("a.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;


$_GET["orientation"] = 2;
$order ="a.relacion_id";
$grupo="";

if ($where) {
    $query .= " WHERE " . $where;
    $query_records .= " WHERE " . $where;
} 


if ($grupo){
	$query .= " GROUP BY " .$grupo;
	$query_records .= " GROUP BY " .$grupo;
}

if ($_GET["orientation"])
    $orientation = $_GET["orientation"];
else
    $orientation = 1;

$query .= " ORDER BY " . $order;
			
$result_relacion_detalle = execute_query($dbh, $query);				
					
									
									
									while($row_relacion_detalle = fetch_object($result_relacion_detalle)){
									 
									 			$departamento_E = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_entrega'";
	 											
    											$resultado_E = execute_query($dbh, $departamento_E);
       											$dpto_E = pg_fetch_row($resultado_E);
									       		$E = $dpto_E[0];
									      		free_result($resultado_E);
									      		
									      		$usuario_E = "SELECT usuario, nombre FROM system_usuarios 
	 											WHERE usuario_id = $row_relacion_detalle->usuario_entrega";
	 											
    											$usuario_resultado_E = execute_query($dbh, $usuario_E);
       											$user_E = pg_fetch_row($usuario_resultado_E);
									       		$UE = $user_E[0];
									      		free_result($usuario_resultado_E);
									      		
									      		$departamento_R = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_recibe'";
	 											
    											$resultado_R = execute_query($dbh, $departamento_R);
       											$dpto_R = pg_fetch_row($resultado_R);
									       		$R = $dpto_R[0];
									      		free_result($resultado_R);
									      		
									      		$usuario_R = "SELECT usuario, nombre FROM system_usuarios 
	 											WHERE usuario_id = $row_relacion_detalle->usuario_recibe";
	 											
    											$usuario_resultado_R = execute_query($dbh, $usuario_R);
       											$user_R = pg_fetch_row($usuario_resultado_R);
									       		$UR = $user_R[0];
									      		free_result($usuario_resultado_R);
									      		
									?>
	      
	      <tr title="Relacion # <?php echo $row_relacion_detalle->relacion_id; ?>">
		  	<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_inicial;?></font></div>
			  <div align="center"><font size="1" color="#8588FC" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->obs_fecha_inicial;?></font></div></td>	
	        <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $E;echo "<br>";?></font></div>
			  <div align="center"><font size="1" color="#8588FC" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $UE;?></font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $R;echo "<br>";?></font></div>
			  <div align="center"><font size="1" color="#8588FC" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $UR;?></font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_registro;?>									</font></div></td>
              <?php
              
              if($row_relacion_detalle->estado =='N'){
			  	$fecha_recibe = "No Aceptada";
			  }
			  else{
			  		$fecha_recibe = $row_relacion_detalle->fecha_recibe;
				}
				?>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fecha_recibe;?></font></div></td>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo duracion_relacion($row_relacion_detalle->fecha_registro, $row_relacion_detalle->fecha_recibe, $row_relacion_detalle->estado);?></font></div></td>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo duracion_cuenta($row_relacion_detalle->fecha_inicial, $row_relacion_detalle->fecha_recibe, $row_relacion_detalle->estado);?></font></div></td>
	        </tr>
	      
	      <?php	
					$duracion = 0;				}
									
								
		}
	 	?>
          </table>
      </div></td>
	<?php 
	if ($row_relacion->prefijo=="" AND $row_relacion->factura_fiscal ==""){
	?>  
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
	<?php 
	}
	else{
	
	
	?>
	
	
	<?PHP 
	$result_relacion_envio=execute_query($dbh, "SELECT fac_facturas.prefijo, fac_facturas.factura_fiscal, envios.envio_id, envios.fecha_registro, envios.fecha_radicacion, envios.sw_estado
 FROM   (public.envios_detalle envios_detalle RIGHT OUTER JOIN public.envios envios ON envios_detalle.envio_id=envios.envio_id) RIGHT OUTER JOIN public.fac_facturas fac_facturas ON ((envios_detalle.empresa_id=fac_facturas.empresa_id) AND (envios_detalle.factura_fiscal=fac_facturas.factura_fiscal)) AND (envios_detalle.prefijo=fac_facturas.prefijo)
 WHERE  fac_facturas.prefijo='$row_relacion->prefijo' AND fac_facturas.factura_fiscal=$row_relacion->factura_fiscal
 ORDER BY envios.envio_id");
									
									
									
									while($row_relacion_envio = fetch_object($result_relacion_envio)){
									
										if($row_relacion_envio->sw_estado <> '2'){
											$fec_envio = $row_relacion_envio->fecha_registro;
											$quitar_decimal_envio = explode(" ", $fec_envio);
											$fecha_envio = $quitar_decimal_envio[0];
											if($fecha_envio <> ""){
												$fecha_envio = strftime('%d/%m/%y',strtotime($fecha_envio));
											}
											
											$fec_radicacion = $row_relacion_envio->fecha_radicacion;
											$quitar_decimal_radicacion = explode(" ", $fec_radicacion);
											$fecha_radicacion = $quitar_decimal_radicacion[0];
											if($fecha_radicacion <> ""){
												$fecha_radicacion = strftime('%d/%m/%y',strtotime($fecha_radicacion));
											}
											?>
											<td bgcolor="<?php echo $color; ?>">
											<div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
											<?php echo $row_relacion_envio->envio_id." ".$fecha_envio?></font></div></td>
											<td bgcolor="<?php echo $color; ?>">
											<div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
											<?php echo $fecha_radicacion?></font></div></td>
											<?php
										}	
									
									}
	?>
	<td colspan="2" bgcolor="<?php echo $color; ?>">
	 <div align="justify">
	   <table  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
	   
<?php								
	$result_relacion_glosas=execute_query($dbh, "SELECT fac_facturas.prefijo, fac_facturas.factura_fiscal, glosas.glosa_id, glosas.fecha_glosa, glosas.sw_estado
 FROM   public.glosas glosas RIGHT OUTER JOIN public.fac_facturas fac_facturas ON ((glosas.empresa_id=fac_facturas.empresa_id) AND (glosas.prefijo=fac_facturas.prefijo)) AND (glosas.factura_fiscal=fac_facturas.factura_fiscal)
 WHERE  fac_facturas.prefijo='$row_relacion->prefijo' AND fac_facturas.factura_fiscal=$row_relacion->factura_fiscal
 ORDER BY glosas.glosa_id");
									
									
									
									while($row_relacion_glosas = fetch_object($result_relacion_glosas)){
									
										if($row_relacion_glosas->sw_estado <> '0'){
											$fec_glosa = $row_relacion_glosas->fecha_glosa;
											$quitar_decimal_glosa = explode(" ", $fec_glosa);
											$fecha_glosa = $quitar_decimal_glosa[0];
											if($fecha_glosa <> ""){
												$fecha_glosa = strftime('%d/%m/%y',strtotime($fecha_glosa));
											}
											
											?>
											
											
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>">
	<div align="justify">
	<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $row_relacion_glosas->glosa_id?></font></div></td>
	<td bgcolor="<?php echo $color; ?>">
	<div align="justify">
	<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $fecha_glosa?></font></div></td>
											
			</tr>
	     
											
											
											<?php
										}	
									
									}								
									?>
    </table></div></td>
    
    <td colspan="2" bgcolor="<?php echo $color; ?>">
	 <div align="justify">
	   <table  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
	   
<?php								
	$result_relacion_recibos=execute_query($dbh, "SELECT recibos_caja.prefijo, recibos_caja.recibo_caja, recibos_caja.fecha_registro, rc_detalle_tesoreria_facturas.sw_estado
 FROM   (public.rc_detalle_tesoreria_facturas rc_detalle_tesoreria_facturas RIGHT OUTER JOIN public.fac_facturas fac_facturas ON ((rc_detalle_tesoreria_facturas.empresa_id=fac_facturas.empresa_id) AND (rc_detalle_tesoreria_facturas.factura_fiscal=fac_facturas.factura_fiscal)) AND (rc_detalle_tesoreria_facturas.prefijo_factura=fac_facturas.prefijo)) LEFT OUTER JOIN public.recibos_caja recibos_caja ON (rc_detalle_tesoreria_facturas.prefijo=recibos_caja.prefijo) AND (rc_detalle_tesoreria_facturas.recibo_caja=recibos_caja.recibo_caja)
 WHERE  fac_facturas.prefijo='$row_relacion->prefijo' AND fac_facturas.factura_fiscal=$row_relacion->factura_fiscal
 ORDER BY recibos_caja.prefijo, recibos_caja.recibo_caja");
									
									
									
									while($row_relacion_recibos = fetch_object($result_relacion_recibos)){
									
										if($row_relacion_recibos->sw_estado <> '1'){
											$fec_recibo = $row_relacion_recibos->fecha_registro;
											$quitar_decimal_recibo = explode(" ", $fec_recibo);
											$fecha_recibo = $quitar_decimal_recibo[0];
											if($fecha_recibo <> ""){
												$fecha_recibo = strftime('%d/%m/%y',strtotime($fecha_recibo));
											}
											
											?>
											
											
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>">
	<div align="justify">
	<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $row_relacion_recibos->prefijo." ".$row_relacion_recibos->recibo_caja?></font></div></td>
	<td bgcolor="<?php echo $color; ?>">
	<div align="justify">
	<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $fecha_recibo?></font></div></td>
											
			</tr>
	     
											
											
											<?php
										}	
									
									}								
									?>
    </table></div></td>
	<?php
	}
	?>
    </tr>
<?php
   $i ++;
}
?>
</table>
<?php


set_numpages1($num_records, $pagina);
?>
 
</form>


<?php
free_result($result);
?>
<br>

</body>
</html>