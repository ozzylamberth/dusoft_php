<?php

$numerodecuenta = $_GET["numerodecuenta"];
$prefijo = $_GET["prefijo"];
$factura_fiscal = $_GET["factura_fiscal"];
$tipo_id_tercero = $_GET["tipo_id_tercero"];
$tercero_id = $_GET["tercero_id"];
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


<html>
<head>
<title>MOVIMIENTO DE DOCUMENTOS</title>
</head>

<body>

<br>
<table width="100%" border="0" cellspacing="0">
<tr>
<td align="center"><img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
</td>
</tr>
<table width="100%" border="1" cellspacing="0" align="left" cellpadding="0">

  
  <tr>
  <td width="30%" bgcolor="#CCCCCC" ><strong><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Tercero</font></strong></a></td> 
  <td width="5%" bgcolor="#CCCCCC" ><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></u></font></td>
  <td width="7%" bgcolor="#CCCCCC"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Ingreso</font></strong></a></u></font></td>
  <td width="8%" bgcolor="#CCCCCC"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fec Egreso</font></strong></a></u></font></td>
  <td width="9%" bgcolor="#CCCCCC"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></strong></a></u></font></td>
  <td width="7%" bgcolor="#CCCCCC"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Factura</font></strong></a></u></font></td>
  <td colspan="4" bgcolor="#CCCCCC"><div align="center"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">MOVIMIENTO DOCUMENTO</font></strong></a></u></font></div></td>
  <td width="6%" bgcolor="#CCCCCC"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Envio</font></strong></a></u></font></td>
  <td width="6%" bgcolor="#CCCCCC"><u><strong><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Radicacion </font></strong></u></td>
  <td colspan="2" bgcolor="#CCCCCC"><div align="center"><font color="#000000"><u><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Glosa</font></strong></u></font></div></td>
  <td width="3%" bgcolor="#CCCCCC"><u><strong><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Pago</font></strong></u></td>
  </tr>
  

<?php

open_database();

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
    <td bgcolor="<?php echo $color; ?>"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $row_relacion->numerodecuenta?></a></font></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $fecha_ingreso?></a></font></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?php echo $fecha_egreso?></font></div></td>
	<td bgcolor="<?php echo $color; ?>"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	  <?php echo $row_relacion->prefijo." ".$row_relacion->factura_fiscal?></font></div></td>
	<td bgcolor="<?php echo $color; ?>"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	  <?php echo $F?></font></div></td>
	<td colspan="4" bgcolor="<?php echo $color; ?>">
	  <div align="justify">
	    <table width="436" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000">
	      <tr> 
	        <td width="10%" bgcolor="#CCCCCC"><div align="justify"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Origen</strong></a></font></div></td>
		      <td width="10%" bgcolor="#CCCCCC"><div align="justify"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Destino</strong></font></div></td>
		      <td width="10%" bgcolor="#CCCCCC"><div align="justify"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fec Relacion </strong></a></font></div></td>
		      <td width="10%" bgcolor="#CCCCCC"><div align="justify"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fec Recibe </strong></a></font></div></td>
          </tr>
	      <?php
	
	
		if ($row_relacion->prefijo=="" AND $row_relacion->factura_fiscal ==""){
		
		
		$result_relacion_detalle=execute_query($dbh, "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe, b.estado
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
									      		
									      		$departamento_R = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_recibe'";
	 											
    											$resultado_R = execute_query($dbh, $departamento_R);
       											$dpto_R = pg_fetch_row($resultado_R);
									       		$R = $dpto_R[0];
									      		free_result($resultado_E);
									      		
									?>
	      
	      <tr>	
	        <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $E;?>									</font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $R;?>									</font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_registro;?>									</font></div></td>
		      <?php
              
              if($row_relacion_detalle->estado =='N'){
				?>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">No Aceptada</font></div></td>
              <?php
			}
			else{
			?>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_recibe;?></font></div></td>
              <?php 
              }
            ?>
          </tr>
	      
	      <?php	
									}
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
				
				$query = "SELECT a.relacion_id, a.departamento_entrega, a.departamento_recibe, a.fecha_registro, a.fecha_recibe, b.estado
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
									      		
									      		$departamento_R = "SELECT descripcion FROM departamentos 
	 											WHERE departamento = '$row_relacion_detalle->departamento_recibe'";
	 											
    											$resultado_R = execute_query($dbh, $departamento_R);
       											$dpto_R = pg_fetch_row($resultado_R);
									       		$R = $dpto_R[0];
									      		free_result($resultado_E);
									      		
									?>
	      
	      <tr>	
	        <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $E;?>									</font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $R;?>									</font></div></td>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_registro;?>									</font></div></td>
		      <?php
              
              if($row_relacion_detalle->estado =='N'){
				?>
				<td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">No Aceptada</font></div></td>
              <?php
			}
			else{
			?>
		      <td bgcolor="<?php echo $color; ?>">
	            <div align="justify"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
              <?php echo $row_relacion_detalle->fecha_recibe;?></font></div></td>
              <?php 
              }
            ?>
          </tr>
	      
	      <?php	
									}
									
								
		}
	 	?>
        </table>
      </div></td>
	<?php 
	if ($row_relacion->prefijo=="" AND $row_relacion->factura_fiscal ==""){
	?>  
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td width="1%" bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
    <td width="1%" bgcolor="<?php echo $color; ?>"><div align="justify"></div></td>
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
											<td width="1%" bgcolor="<?php echo $color; ?>">
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

    
    <td width="3%" colspan="2" bgcolor="<?php echo $color; ?>">
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
</table>
</body>
</html>
