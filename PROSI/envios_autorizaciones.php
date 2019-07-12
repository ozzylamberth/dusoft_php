<?php

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


procesar_entrada("GET", "id", "action", "envio", "cod_menu");




$envio = get_value($_GET["envio"], "N");

	if($_REQUEST["cod_menu"]){
	$cod_menu = $_REQUEST["cod_menu"];
	}
	if($_POST["cod_menu"]){
	$cod_menu = get_value($_POST["cod_menu"]);
	}
	if($_GET["cod_menu"]){
	$cod_menu = get_value($_GET["cod_menu"], "N");
	}
 
$action = get_value($_GET["action"], "C");


$fecha_registro = date("Y-m-d H:i:s");

$sess = session_id();
function DetalleEnvio($envio, $dbh)
		{
			
			
			$query = "SELECT 	a.*, 
										b.*, 
										c.numerodecuenta, 
										d.total_factura,
										d.valor_cuota_paciente, 
										d.plan_id, 
										e.plan_descripcion, 
										g.tipo_id_paciente,
										g.paciente_id, 
										h.primer_nombre||' '||h.segundo_nombre||' '||h.primer_apellido||' '||h.segundo_apellido as nombre,
										i.nombre_tercero, 
										e.tipo_tercero_id, 
										e.tercero_id, 
										a.fecha_registro,
										j.usuario_id,
										j.nombre as nomusuario,
										i.direccion,
										i.telefono,
										k.municipio,
										g.departamento_actual as departamento,
										g.ingreso,
										f.tipo_afiliado_id,
										f.semanas_cotizadas,
										f.rango
						FROM 		envios as a, 
										envios_detalle as b, 
										fac_facturas_cuentas as c,
										fac_facturas as d, 
										planes as e, 
										cuentas as f, 
										ingresos as g, 
										pacientes as h, 
										terceros as i, 
										system_usuarios j,
										tipo_mpios k
						WHERE 	a.envio_id=".$envio."
										AND a.envio_id=b.envio_id 
										AND b.prefijo=c.prefijo
										AND b.factura_fiscal=c.factura_fiscal 
										AND d.prefijo=c.prefijo
										AND d.factura_fiscal=c.factura_fiscal 
										AND d.plan_id=e.plan_id
										AND c.numerodecuenta=f.numerodecuenta 
										AND f.ingreso=g.ingreso 
										AND g.tipo_id_paciente=h.tipo_id_paciente 
										AND g.paciente_id=h.paciente_id
										AND e.tipo_tercero_id=i.tipo_id_tercero 
										AND e.tercero_id=i.tercero_id
										AND a.usuario_id=j.usuario_id
										AND i.tipo_pais_id = k.tipo_pais_id 
										AND i.tipo_dpto_id = k.tipo_dpto_id
										AND i.tipo_mpio_id = k.tipo_mpio_id
						ORDER BY b.prefijo, b.factura_fiscal";
						
			$result=execute_query($dbh, $query);
			return $result;
			
		}
		function ConsultaAutorizacion($numerodecuenta, $dbh)
		{
		$query = "(
								select b.autorizacion, c.codigo_autorizacion
								from 	cuentas a,
											autorizaciones b,
											autorizaciones_escritas c
								
								where
											a.numerodecuenta= '".$numerodecuenta."'
											and a.ingreso = b.ingreso
											and b.autorizacion = c.autorizacion
								)
								UNION
								(
								select b.autorizacion, c.codigo_autorizacion
								from 	cuentas a,
											autorizaciones b,
											autorizaciones_telefonicas c
								
								where
											a.numerodecuenta= '".$numerodecuenta."'
											and a.ingreso = b.ingreso
											and b.autorizacion = c.autorizacion
								)
								";
								
			$result_autorizacion=execute_query($dbh, $query);
			while($autorizacion = fetch_object($result_autorizacion))
			{
						$var1=$autorizacion->autorizacion;
						$var2=$autorizacion->codigo_autorizacion;
			}
			return $var1." ".$var2;
		}
		
		function ConsultaAutorizacionIngreso($ingreso, $dbh)
		{
			if($ingreso)
			{
				
				$query = "SELECT   autorizacion, codigo_autorizacion
				FROM    autorizaciones
				WHERE ingreso = $ingreso ";
				$result=execute_query($dbh, $query);
			
				$autorizacion = pg_fetch_row($result);
				return $autorizacion[0]." ".$autorizacion[1];
			}
		return true;
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
<title>RELACION DE CUENTAS SIIS</title>
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
<form name="buscarenvio" action="<?=$_SERVER['PHP_SELF']?>" method="GET">
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
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">AUTORIZACIONES FACTURAS ENVIOS</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">&nbsp;</td>
      <td bgcolor="#EEEEEE">&nbsp;</td>
          
    </tr>
</form>




<tr>
	   
      <td colspan="4" bgcolor="#EEEEEE"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Envio</font>
      <input name="envio" type="text" id="envio" maxlength="10" value="<?php echo $envio;?>" class="textbox">
      <input type="hidden" name="cod_menu" value="<?php echo $cod_menu;?>">
	  <input name="Buscar" type="submit" value="Buscar">
	  </td>
    </tr>

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
				foreach($_POST["enviolinea"] as $auto_ingreso) 
					{
					 
					 $registro_autorizacion = explode(" ", $auto_ingreso);
					 $autorizacion = $registro_autorizacion[0];
					 $numerodecuenta = $registro_autorizacion[1];
					 $ingreso = $registro_autorizacion[2];
					 $plan_id = $registro_autorizacion[3];
					 $tipo_afiliado_id = $registro_autorizacion[4];
					 $semanas_cotizadas = $registro_autorizacion[5];
					 $rango = $registro_autorizacion[6];
					 $codigo_autorizacion = $_POST['codigo_autorizacion'];
					 $codigo_autorizacion= $codigo_autorizacion[$i];
					 
					 $cont_auto = pg_query($dbh, "SELECT a.*
 																FROM  autorizaciones a
																WHERE a.ingreso = $ingreso");

					$num_auto = pg_num_rows($cont_auto);
										 
					 if($num_auto >=1){
					  
					  		$query1="UPDATE autorizaciones
							SET codigo_autorizacion = '".$codigo_autorizacion."'
							WHERE autorizacion = ".$autorizacion.";";
							$resulta1=execute_query($dbh, $query1);
			
						} else {
            					$inserta_auto = "INSERT INTO autorizaciones(fecha_autorizacion"
					 		 		. ",observaciones"
									. ",usuario_id"
									. ",fecha_registro"
									. ",sw_estado"
									. ",ingreso"
									. ",observacion_ingreso"
									. ",hc_os_solicitud_id"
									. ",clase_autorizacion"
									. ",tipo_autorizacion"
									. ",tipo_autorizador"
									. ",codigo_autorizacion"
									. ",codigo_autorizacion_generador"
									. ",descripcion_autorizacion"
									. ",plan_id"
									. ",tipo_afiliado_id"
									. ",semanas_cotizadas"
									. ",rango"
							 		. ") VALUES ("
							 		. "now()"
							 		. ",'Autorizaciones automaticas por usuario SIIS por no habersen digitado en admisiones'"
							 		. "," . $usuario_id . ""
							 		. ",now()"
							 		. ",'1'"
							 		. "," . $ingreso . ""
							 		. ",'**'"
							 		. ",0"
							 		. ",'**'"
							 		. ",'**'"
							 		. ",'I'"
							 		. ",'" . $codigo_autorizacion . "'"
							 		. ",'**'"
							 		. ",'**'"
							 		. ",".$plan_id.""
							 		. ",'".$tipo_afiliado_id."'"
							 		. ",".$semanas_cotizadas.""
							 		. ",'".$rango."');";
							 		
									execute_query($dbh, $inserta_auto);
			
								}
					 	
					 $i++;
					 }
					
					 
					 
			break;
			
			default:
				$permiso_select = PerfilOpcionUsuario($usuario_id, $cod_menu, "select", $dbh);
				
				if($permiso_select <> ""){
 					$result_envio=DetalleEnvio($envio, $dbh);
				}
				
		}
	
		
?>



<br>
<table width="100%" border="0" cellspacing="0">
  <tr> 
    <td width="7%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></strong></a>   </td>
    <td width="9%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a>   </td>
    <td width="13%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Identificacion</font></strong></td>
    <td width="28%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a>   </td>
    <td width="17%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Autorizacion</font></strong></td>
    <td width="24%" background="imagenes/cellpic1.gif"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong>   </td>
 </tr>
<?php

$permiso_select = PerfilOpcionUsuario($usuario_id, $cod_menu, "select", $dbh);
		
if($permiso_select <> ""){
 	$result_envio=DetalleEnvio($envio, $dbh);
}

$total_envio = 0;
while ($detallado = fetch_object($result_envio)) {

$autorizacion=ConsultaAutorizacionIngreso($detallado->ingreso, $dbh);
$registro = explode(" ", $autorizacion);

$total_envio = $total_envio + $detallado->total_factura;

			
  		
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>?action=update&amp;envio=<?php echo $envio;?>&amp;cod_menu=<?php echo $cod_menu;?>" method="post" name="actualiza" target="_self">
<input type="hidden" name="envio" value="<?php echo $envio;?>">
<input type="hidden" name="cod_menu" value="<?php echo $cod_menu;?>">
  <tr bgcolor="#EEEEEE"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->prefijo." ".$detallado->factura_fiscal?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>">$<?php echo number_format ($detallado->total_factura)?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->tipo_id_paciente." ".$detallado->paciente_id?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->nombre?></font></td>
    <td><input name="codigo_autorizacion[]" type="text" maxlength="40" value="<?php echo $registro[1];?>" class="textbox"></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $detallado->plan_descripcion?></font></td>
    </tr>
  <?php
      	
  		echo "<input type=\"hidden\" name=\"enviolinea[]\" value=\"$registro[0]"." "."$detallado->numerodecuenta"." "."$detallado->ingreso"." "."$detallado->plan_id"." "."$detallado->tipo_afiliado_id"." "."$detallado->semanas_cotizadas"." "."$detallado->rango\">";
  		
  		
  		
}

		?>
	<tr bgcolor="#EEEEEE"> 
    <td><div align="center" class="Estilo1"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><u>Total Envio:</u></font></div></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><strong>$<?php echo number_format ($total_envio)?></strong></font></td>
	<td colspan="4"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"></font></strong></td>
	</tr>	
</table>

<table width="100%" border="0" cellspacing="0">
  <tr> <td width="8%" background="imagenes/cellpic1.gif" align="center">
    
		<input type="submit" name="Submit" value="Actualizar" >
        </td>
                
  </tr>
  <tr> <td width="8%" background="imagenes/cellpic1.gif" align="center">
    
		<u><b>Formato Nueva EPS:</b></u>
		<a href="txt.rips.php?envio_id=<?php echo $envio?>">Descargar txt
		<a/>
		<a href="xls_rips.php?envio_id=<?php echo $envio?>">Descargar Excel
		<a/>
        </td>
                
  </tr>
</table>
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
