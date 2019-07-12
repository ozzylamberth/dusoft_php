<?php
	
?><?php

if (!session_id()){
  session_start();
}


require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

open_database();

$self = $_SERVER["PHP_SELF"]; 
$usuario_id = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];


procesar_entrada("GET", "id", "action");

procesar_entrada("POST", "cuenta_liquidacion_qx_id", "cod_menu");

if($_REQUEST["cod_menu"]){
	$cod_menu = $_REQUEST["cod_menu"];
	}
	if($_POST["cod_menu"]){
	$cod_menu = $_POST["cod_menu"];
	}
	if($_GET["cod_menu"]){
	$cod_menu = $_GET["cod_menu"];
	}
	

$cuenta_liquidacion_qx_id = get_value($_POST["cuenta_liquidacion_qx_id"], "N");
$action = get_value($_GET["action"], "C");


$fecha_registro = date("Y-m-d H:i:s");

$sess = session_id();
function Productos_liq_qx($cuenta_liquidacion_qx_id, $dbh)
		{
			
			
			$query = "SELECT 	a.transaccion,
								d.codigo_producto, 
       							a.cantidad, 
       							d.descripcion, 
       							a.valor_nocubierto, 
       							a.valor_cubierto,
       							a.facturado,
       							a.valor_cargo
       							
					  FROM 		cuentas_detalle a, 
     							cuentas_codigos_agrupamiento b, 
     							bodegas_documentos_d c, 
     							inventarios_productos d,
     							cuentas e
     							
					  WHERE 	b.cuenta_liquidacion_qx_id = $cuenta_liquidacion_qx_id
						AND   	b.codigo_agrupamiento_id = a.codigo_agrupamiento_id
						AND   	a.consecutivo = c.consecutivo
						AND   	c.codigo_producto = d.codigo_producto
						AND 	a.numerodecuenta = e.numerodecuenta
						AND 	e.estado IN('1','2')
						
						ORDER BY a.transaccion";
						
			$result=execute_query($dbh, $query);
			return $result;
			
		}
		function Inserta_audi_cuentas_detalle($transaccion, $usuario_id, $justificacion, $dbh)
		{
			
			
			$query = "INSERT INTO audit_cuentas_detalle (
       										transaccion,
       										empresa_id,
       										centro_utilidad,
       										numerodecuenta,
       										departamento,
									       tarifario_id,
									       cargo,
									       cantidad,
									       precio,
									       porcentaje_descuento_empresa,
									       valor_cargo,
									       valor_nocubierto,
									       valor_cubierto,
									       facturado,
									       fecha_cargo,
									       usuario_id,
									       fecha_registro,
									       sw_liq_manual,
									       valor_descuento_empresa,
									       valor_descuento_paciente,
									       porcentaje_descuento_paciente,
									       servicio_cargo,
									       autorizacion_int,
									       autorizacion_ext,
									       porcentaje_gravamen,
									       sw_cuota_paciente,
									       sw_cuota_moderadora,
									       codigo_agrupamiento_id,
									       consecutivo,
									       usuario_id_act,
									       fecha_registro_act,
									       sw_actualizacion,
									       sw_cargue,
									       cargo_cups,
									       justificacion,
									       paquete_codigo_id,
									       sw_paquete_facturado)
									(SELECT a.transaccion,
									       a.empresa_id,
									       a.centro_utilidad,
									       a.numerodecuenta,
									       a.departamento,
									       a.tarifario_id,
									       a.cargo,
									       a.cantidad,
									       a.precio,
									       a.porcentaje_descuento_empresa,
									       a.valor_cargo,
									       a.valor_nocubierto,
									       a.valor_cubierto,
									       a.facturado,
									       a.fecha_cargo,
									       a.usuario_id,
									       a.fecha_registro,
									       a.sw_liq_manual,
									       a.valor_descuento_empresa,
									       a.valor_descuento_paciente,
									       a.porcentaje_descuento_paciente,
									       a.servicio_cargo,
									       a.autorizacion_int,
									       a.autorizacion_ext,
									       a.porcentaje_gravamen,
									       a.sw_cuota_paciente,
									       a.sw_cuota_moderadora,
									       a.codigo_agrupamiento_id,
									       a.consecutivo,
									       $usuario_id,
									       now(),
									       0,
									       a.sw_cargue,
									       a.cargo_cups,
									       '$justificacion',
									       a.paquete_codigo_id,
									       a.sw_paquete_facturado
									FROM cuentas_detalle a
									WHERE a.transaccion = $transaccion);";
						
			$result=execute_query($dbh, $query);
			
			
		}
		function Consulta_justificacion($transaccion, $dbh, $requisito)
		{
			
			
			$inf_just = "SELECT a.transaccion, a.justificacion, count(*) as cant_just
							FROM prosi.tmp_cambio_valores a
							WHERE a.transaccion = $transaccion
							GROUP BY a.transaccion, a.justificacion
							ORDER BY a.transaccion";
			$rows_just = execute_query($dbh, $inf_just);
			$reg_just = pg_fetch_row($rows_just);
			$justificacion = $reg_just[1];
			$cant_just = $reg_just[2];
			if($requisito == "num_reg")
			{
				return $cant_just;
			}
			else
			{
				return $justificacion;
			}
			
		}
		function imprime_necesita_justificacion($transaccion, $dbh)
		{
			
			
			$query_imprime = execute_query($dbh, "SELECT 	a.transaccion,
								d.codigo_producto, 
       							a.cantidad, 
       							d.descripcion, 
       							a.valor_nocubierto, 
       							a.valor_cubierto,
       							a.facturado,
       							a.valor_cargo
       							
					  FROM 		cuentas_detalle a, 
     							bodegas_documentos_d c, 
     							inventarios_productos d
     							
					  WHERE    	a.transaccion = $transaccion
					  	AND 	a.consecutivo = c.consecutivo
						AND   	c.codigo_producto = d.codigo_producto
						
						ORDER BY a.transaccion");
						
			$row = fetch_object($query_imprime);
			if ($row) 
			{		
			 	$codigo_producto = $row->codigo_producto;
				$descripcion = $row->descripcion;		
			}
			
			$message .= 
			 "<tr>
			 <td bgcolor=#EEEEEE>
			 <font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>
			 El producto ".$codigo_producto." ".$descripcion." necesita justificacion</strong></font>
			 </td>
			 </tr>"; 
			return $message;
		}
		
?>
<html>
<head>
<script>
function abrirpopup(nombre,ancho,alto) {
 Xpos=(screen.width/2)-300;
 Ypos=(screen.height/2)-200; 

dat = 'width=' + ancho + ',height=' + alto + ',left='+Xpos+',top = '+Ypos+',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,titlebar=no';
window.open(nombre,'',dat)

}

</script>
<title>CAMBIO VALORES QX</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
</head>

<body background="imagenes/fondo_bloque.gif">


<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="buscarliquidacion" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<table align="CENTER%" width="100%" border="1" bordercolor="blue">
<tr>
<td><center><img src="imagenes/logo_clinica.bmp" align="CENTER%" width="250" height="150">  
	
	</td>
  </tr>
  <?php
	
      $permiso_select = PerfilOpcionUsuario($usuario_id, $cod_menu, "select", $dbh);
		
		if($permiso_select <> ""){	?>
<tr>
<td>
  <table align="CENTER%" width="100%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">PRODUCTOS LIQ QX</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">&nbsp;</td>
      <td bgcolor="#EEEEEE">&nbsp;</td>
          
    </tr>
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>?action=buscar" method="post" name="busqueda" target="_self">


<tr>
	   
      <td colspan="4" bgcolor="#EEEEEE"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b><u>ACTO QUIRURGICO No.</u></b></font>
	  <input name="cuenta_liquidacion_qx_id" type="text" id="cuenta_liquidacion_qx_id" maxlength="10" value="<?php echo $cuenta_liquidacion_qx_id;?>" class="textbox"><input name="Buscar" type="submit" value="Buscar"></div></td>
    </tr>
<input type="hidden" name="cod_menu" value="<?php echo $cod_menu;?>">
</form>    
    
<?php

 switch ($action)
 		{
			case "update":
				$permiso_update = PerfilOpcionUsuario($usuario_id, $cod_menu, "update", $dbh);
				
				if($permiso_update == ""){
				$mensaje_insert = "<font size=2 color=RED face=Verdana, Arial, Helvetica, sans-serif><strong>ERROR: Usuario no tiene permiso para ejecutar esta accion.</strong></font>";
				echo $mensaje_insert;
				break;
				
				}
				$i = 0;
				foreach($_POST["cobertura"] as $cobertura) 
					{
					 
					 $cobertura = explode(" ", $cobertura);
					 $transaccion = $cobertura[0];
					 $valor = $cobertura[1];
					 $lado = $cobertura[2];
					 $cambio_valor = $cobertura[3];
					 
					 $facturacargo = $_POST['facturacargo'];
					 $arr_facturado = explode(" ", $facturacargo[$i]);
					 $estado_facturado = $arr_facturado[0];
					 $cambio_facturado = $arr_facturado[1];
					 
					 if($cambio_valor == 1 OR $cambio_facturado == 1)
					 {
					 $cant_reg = Consulta_justificacion($transaccion, $dbh, "num_reg");
					 $just_reg = Consulta_justificacion($transaccion, $dbh, "just_reg");
					 
					 
					 if ($cant_reg == 0)
					 {
						$mensaje_justificacion = imprime_necesita_justificacion($transaccion, $dbh);
						echo $mensaje_justificacion;
					} 
					else{
					 	Inserta_audi_cuentas_detalle($transaccion, $usuario_id, $just_reg, $dbh);	
					 if($lado == 1)
						{
						
							$query1="UPDATE cuentas_detalle
							SET valor_cubierto = ".$valor.",
								valor_nocubierto = 0,
								sw_liq_manual = '1',
								facturado = '".$estado_facturado."'
							WHERE transaccion = ".$transaccion.";";
							
							$resulta1=execute_query($dbh, $query1);
						}
						else if($lado == 0)
						{
							$query1="UPDATE cuentas_detalle
							SET valor_nocubierto = ".$valor.",
								valor_cubierto = 0,
								sw_liq_manual = '1',
								facturado = '".$estado_facturado."'
							WHERE transaccion = ".$transaccion.";";
							
							$resulta1=execute_query($dbh, $query1);
						}
						$del_justi = "DELETE FROM prosi.tmp_cambio_valores WHERE transaccion = " . $transaccion . "";
						execute_query($dbh, $del_justi);
					 }
					}
					 $i++;
					 }
					
					 
					 
			break;
			
			default:
			$result_prod_qx=Productos_liq_qx($cuenta_liquidacion_qx_id, $dbh);
		}
	
		
?>
  
<br>
<table width="100%" border="0" cellspacing="0">
  <tr> 
    <td width="15%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Codigo</font></strong></a>   </td>
    <td width="10%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Cantidad</font></strong></a>   </td>
    <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Producto</font></strong></td>
    <td colspan = "1" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Valor no cubierto</font></strong>
	  </td>
	  
    <td colspan = "1" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Valor cubierto</font></strong>
	</td>
	<td colspan = "3" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif"><p align="center">Factura</p></font></strong>
	<tr>
		<td colspan = "5" background="imagenes/cellpic1.gif"></td>
		<td width="2%" background="imagenes/cellpic1.gif"> 
      	<strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Si</font></strong>
	  	</td>
	  	<td width="2%" background="imagenes/cellpic1.gif"> 
      	<strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">No</font></strong>
	  	</td>
	  	<td width="2%" background="imagenes/cellpic1.gif"> 
      	<strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Just</font></strong>
	  	</td>
	</tr>  	
	</td>
	
    </tr>
<?php
$result_prod_qx=Productos_liq_qx($cuenta_liquidacion_qx_id, $dbh);
$total_envio = 0;
$a = 0;
$suma_cubiertos = 0;
$suma_nocubiertos = 0;
while ($detallado = fetch_object($result_prod_qx)) {
 
 	

	if($detallado->valor_cubierto>0 and $detallado->valor_nocubierto == 0
	OR $detallado->valor_cubierto<0 and $detallado->valor_nocubierto == 0)
	{
	 	$color = "black";
		$chequeo_cubierto="<input type=\"radio\" name=\"cobertura[$a]\" value=\"$detallado->transaccion"." "."$detallado->valor_cargo"." "."1"." "."0\" checked>";
		$chequeo_nocubierto="<input type=\"radio\" name=\"cobertura[$a]\" value=\"$detallado->transaccion"." "."$detallado->valor_cargo"." "."0"." "."1\">";
	}
	else
	{
	 	$color = "red";
		$chequeo_cubierto="<input type=\"radio\" name=\"cobertura[$a]\" value=\"$detallado->transaccion"." "."$detallado->valor_cargo"." "."1"." "."1\">";
		$chequeo_nocubierto="<input type=\"radio\" name=\"cobertura[$a]\" value=\"$detallado->transaccion"." "."$detallado->valor_cargo"." "."0"." "."0\" checked>";
	}
	
	if($detallado->facturado == '1')
	{
		$chequeo_facturado = "<input type=\"radio\" name=\"facturacargo[$a]\" value=\"1"." "."0\" checked>";
		$chequeo_nofacturado = "<input type=\"radio\" name=\"facturacargo[$a]\" value=\"0"." "."1\">";
		$suma_cubiertos = $suma_cubiertos + $detallado->valor_cubierto;
		$suma_nocubiertos = $suma_nocubiertos + $detallado->valor_nocubierto;
	}
	else
	{
		$chequeo_nofacturado = "<input type=\"radio\" name=\"facturacargo[$a]\" value=\"0"." "."0\" checked>";
		$chequeo_facturado = "<input type=\"radio\" name=\"facturacargo[$a]\" value=\"1"." "."1\">";
		
	}
		
  		
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>?action=update&amp;cuenta_liquidacion_qx_id=<?php echo $cuenta_liquidacion_qx_id;?>&amp;cod_menu=<?php echo $cod_menu;?>" method="post" name="actualiza" target="_self">
<input type="hidden" name="cuenta_liquidacion_qx_id" value="<?php echo $cuenta_liquidacion_qx_id;?>">
<input type="hidden" name="cod_menu" value="<?php echo $cod_menu;?>">
  <tr bgcolor="#EEEEEE" backgroundColor = '#EEEEEE'> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->codigo_producto?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->cantidad?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->descripcion?></font></td>
    <td><div align="justify"><?php echo $chequeo_nocubierto;?><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"> <?php echo number_format($detallado->valor_nocubierto)?></font>
    </div></td>
    
    <td><div align="justify"><?php echo $chequeo_cubierto;?> <font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo number_format($detallado->valor_cubierto)?></font>
    </div></td>
    
	<td>
	<?php echo $chequeo_facturado;?>
	</td>
	<td>
	<?php echo $chequeo_nofacturado;?>
	</td>
	<td>
	<a href="#" onClick="abrirpopup('obs_cambio_valores_qx.php?transaccion=<?php echo $detallado->transaccion?>',500,150);">Just </a>
	</td>
	</tr>
  <?php
     	
$a++;  		
}

		?>
<tr> 
		<td colspan = "2" background="imagenes/cellpic1.gif"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
		<td background="imagenes/cellpic1.gif"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Totales</b></td>
		<td background="imagenes/cellpic1.gif"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b><?php echo number_format($suma_nocubiertos)?></b></td>
		<td background="imagenes/cellpic1.gif"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b><?php echo number_format($suma_cubiertos)?></b></td>
		<td colspan = "3" background="imagenes/cellpic1.gif"></td>
	</tr>	
</table>
<?php if($a > 0)
{
	?>
<table width="100%" border="0" cellspacing="0">
			
  <tr> <td width="8%" background="imagenes/cellpic1.gif" align="center">
    
		<input type="submit" name="Submit" value="Actualizar">
        </td>
        
  </tr>
</table>
<?php } ?>
</form>   
<?php


?>


</td>
</tr>
<?php 
		}
	else{
			require("noacceso.html");
    		exit;
		}
		?>

</body>
</html>
