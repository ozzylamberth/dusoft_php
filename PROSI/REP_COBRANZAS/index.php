<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

open_database();



$input9 = $_GET["input9"];
$input10 = $_GET["input10"];



$query = "DELETE FROM tmp_reporte_cobranzas"; 
$result = execute_query($dbh, $query); 
if (!$result) { 
    printf ("ERROR"); 
    $errormessage = pg_errormessage($db); 
    echo $errormessage; 
    exit(); 
} 

?>

<html>
<head>
<body background="#EEEEEE">
<title>Reportes de Cobranzas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="t_terceros" action="index.php" method='POST'>
<table width="100%" border="0" cellspacing="0">
<tr>
<td align="center"><img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
</td>
</tr>
<tr>
<td align="center">REPORTES DE FACTURACION RADICADA</td>
</tr>
  <table width="53%" border="0" cellspacing="0" align="center">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
			<tr>
      <td bgcolor="#EEEEEE">Fecha Desde:<br>
      <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a>
				<input type="Text" name="input9" value="<?php echo $_REQUEST['input9'];?>">
				</td>
				<td bgcolor="#EEEEEE">
				Fecha Hasta:<br>
				<input type="Text" name="input10" value="<?php echo $_REQUEST['input10'];?>">
				<a href="javascript:cal10.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a><br></td>
			</tr>
			
			
<tr> 
<td bgcolor="#EEEEEE"><input type="submit" name="buscar" value="Buscar"></td>
<td bgcolor="#EEEEEE">&nbsp;</td>
</tr>
</table>
</form>
			
<script language="JavaScript">
			<!-- // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				

				var cal9 = new calendar3(document.forms['tstest'].elements['input9']);
				cal9.year_scroll = true;
				cal9.time_comp = false;
				var cal10 = new calendar3(document.forms['tstest'].elements['input10']);
				cal10.year_scroll = true;
				cal10.time_comp = false;
				
			//-->
			</script>
				
<?php

if ($input9 <> '' AND $input10 <> '')
{
$input9 = $input9.' 00:00:00';
$input10 = $input10.' 23:59:59'; 
 			
$result_grupo=execute_query($dbh, "SELECT envios.fecha_radicacion, fac_facturas.factura_fiscal, fac_facturas.prefijo, envios.envio_id, envios.fecha_registro, planes.plan_id, planes.plan_descripcion, system_usuarios.nombre, terceros.nombre_tercero, terceros.tercero_id, system_usuarios.usuario_id, fac_facturas.fecha_registro, fac_facturas.total_factura, terceros.tipo_id_tercero
 FROM   ((((public.envios_detalle envios_detalle INNER JOIN public.fac_facturas fac_facturas ON ((envios_detalle.empresa_id=fac_facturas.empresa_id) AND (envios_detalle.factura_fiscal=fac_facturas.factura_fiscal)) AND (envios_detalle.prefijo=fac_facturas.prefijo)) INNER JOIN public.envios envios ON envios_detalle.envio_id=envios.envio_id) INNER JOIN public.planes planes ON fac_facturas.plan_id=planes.plan_id) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)) INNER JOIN public.system_usuarios system_usuarios ON envios.usuario_id=system_usuarios.usuario_id
 WHERE  (envios.fecha_radicacion>='$input9' AND envios.fecha_radicacion<='$input10')
 ORDER BY envios.envio_id"); 


?>
 <?php

while($fetch_grupo = pg_fetch_row($result_grupo)) {
 
//INGRESA NOTAS DEBITO ASOCIADAS AL NUMERO DE FACTURA//
$result_ND=execute_query($dbh, "SELECT a.prefijo, a.nota_debito_id, a.valor_nota FROM notas_debito a 
WHERE a.prefijo_factura = '$fetch_grupo[2]' AND a.factura_fiscal = $fetch_grupo[1]"); 


 		while($fetch_ND = pg_fetch_array($result_ND)) {
		
		$vlr_ng_aceptado = 0;
		$vlr_ng_noaceptado = 0;
		$insert_ND="insert into tmp_reporte_cobranzas(envio_id,
		 											fecha_envio,
													tercero_id,
													fecha_radicacion,
													prefijo,
													factura_fiscal,
													usuario_id,
													prefijo_nota,
													nota_id,
													vlr_nota,
													vlr_ng_aceptado,
													vlr_ng_noaceptado,
													fecha_factura,
													total_factura,
													tipo_tercero_id) 
											VALUES($fetch_grupo[3],
											'$fetch_grupo[4]',
											'$fetch_grupo[9]',
											'$fetch_grupo[0]',
											'$fetch_grupo[2]',
											$fetch_grupo[1],
											$fetch_grupo[10],
											'$fetch_ND[0]',
											$fetch_ND[1],
											$fetch_ND[2],
											$vlr_ng_aceptado,
											$vlr_ng_noaceptado,
											'$fetch_grupo[11]',
											$fetch_grupo[12],
											'$fetch_grupo[13]');";
									execute_query($dbh,$insert_ND);
															}
//INGRESA NOTAS CREDITO ASOCIADAS AL NUMERO DE FACTURA//
$result_NC=execute_query($dbh, "SELECT a.prefijo, a.nota_credito_id, a.valor_nota FROM notas_credito a 
WHERE a.prefijo_factura = '$fetch_grupo[2]' AND a.factura_fiscal = $fetch_grupo[1]"); 


 		while($fetch_NC = pg_fetch_array($result_NC)) {
		
		
		$vlr_ng_aceptado = 0;
		$vlr_ng_noaceptado = 0;
		$insert_NC="insert into tmp_reporte_cobranzas(envio_id,
		 											fecha_envio,
													tercero_id,
													fecha_radicacion,
													prefijo,
													factura_fiscal,
													usuario_id,
													prefijo_nota,
													nota_id,
													vlr_nota,
													vlr_ng_aceptado,
													vlr_ng_noaceptado,
													fecha_factura,
													total_factura,
													tipo_tercero_id) 
											VALUES($fetch_grupo[3],
											'$fetch_grupo[4]',
											'$fetch_grupo[9]',
											'$fetch_grupo[0]',
											'$fetch_grupo[2]',
											$fetch_grupo[1],
											$fetch_grupo[10],
											'$fetch_NC[0]',
											$fetch_NC[1],
											$fetch_NC[2],
											$vlr_ng_aceptado,
											$vlr_ng_noaceptado,
											'$fetch_grupo[11]',
											$fetch_grupo[12],
											'$fetch_grupo[13]');";
											execute_query($dbh,$insert_NC);
															}	
															
//INGRESA GLOSAS NOTAS CREDITO ASOCIADAS AL NUMERO DE FACTURA//																													
$result_GL=execute_query($dbh, " SELECT glosas.glosa_id, glosas.valor_glosa, notas_credito_glosas.prefijo, notas_credito_glosas.numero, notas_credito_glosas.valor_aceptado, notas_credito_glosas.valor_no_aceptado, glosas.prefijo, glosas.factura_fiscal
 FROM   public.notas_credito_glosas notas_credito_glosas RIGHT OUTER JOIN public.glosas glosas ON notas_credito_glosas.glosa_id=glosas.glosa_id
 WHERE  glosas.prefijo='$fetch_grupo[2]' AND glosas.factura_fiscal=$fetch_grupo[1]"); 


 		while($fetch_GL = pg_fetch_array($result_GL)) {
 		$prefijo_nota = 'G';
 		 
		$insert_GL="insert into tmp_reporte_cobranzas(envio_id,
		 											fecha_envio,
													tercero_id,
													fecha_radicacion,
													prefijo,
													factura_fiscal,
													usuario_id,
													prefijo_nota,
													nota_id,
													vlr_nota,
													vlr_ng_aceptado,
													vlr_ng_noaceptado,
													prefijo_ng,
													ng_id,
													fecha_factura,
													total_factura,
													tipo_tercero_id) 
											VALUES($fetch_grupo[3],
											'$fetch_grupo[4]',
											'$fetch_grupo[9]',
											'$fetch_grupo[0]',
											'$fetch_grupo[2]',
											$fetch_grupo[1],
											$fetch_grupo[10],
											'$prefijo_nota',
											$fetch_GL[0],
											$fetch_GL[1],
											'$fetch_GL[4]',
											$fetch_GL[5],
											'$fetch_GL[2]',
											$fetch_GL[3],
											'$fetch_grupo[11]',
											$fetch_grupo[12],
											'$fetch_grupo[13]');";
											execute_query($dbh,$insert_GL);
															}
															
																														
														}
														
//INGRESA LAS FACTURAS QUE NO POSEEN NOTAS DE NINGUNA INDOLE														
$result_FA=execute_query($dbh, "SELECT envios.fecha_radicacion, fac_facturas.factura_fiscal, fac_facturas.prefijo, envios.envio_id, envios.fecha_registro, planes.plan_id, planes.plan_descripcion, system_usuarios.nombre, terceros.nombre_tercero, terceros.tercero_id, system_usuarios.usuario_id, fac_facturas.fecha_registro, fac_facturas.total_factura, terceros.tipo_id_tercero
 FROM   ((((public.envios_detalle envios_detalle INNER JOIN public.fac_facturas fac_facturas ON ((envios_detalle.empresa_id=fac_facturas.empresa_id) AND (envios_detalle.factura_fiscal=fac_facturas.factura_fiscal)) AND (envios_detalle.prefijo=fac_facturas.prefijo)) INNER JOIN public.envios envios ON envios_detalle.envio_id=envios.envio_id) INNER JOIN public.planes planes ON fac_facturas.plan_id=planes.plan_id) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)) INNER JOIN public.system_usuarios system_usuarios ON envios.usuario_id=system_usuarios.usuario_id
 WHERE  (envios.fecha_radicacion>='$input9' AND envios.fecha_radicacion<='$input10') 
 AND fac_facturas.prefijo||fac_facturas.factura_fiscal NOT IN (SELECT prefijo_factura||factura_fiscal FROM notas_debito)
 AND fac_facturas.prefijo||fac_facturas.factura_fiscal NOT IN (SELECT prefijo_factura||factura_fiscal FROM notas_credito)
 AND fac_facturas.prefijo||fac_facturas.factura_fiscal NOT IN (SELECT prefijo||factura_fiscal FROM glosas)
 ORDER BY envios.envio_id"); 


while($fetch_FA = pg_fetch_row($result_FA)) { 
 		 
 		$vlr_nota = 0; 
 		$vlr_ng_aceptado = 0;
		$vlr_ng_noaceptado = 0; 
		$insert_FA="insert into tmp_reporte_cobranzas(envio_id,
		 											fecha_envio,
													tercero_id,
													fecha_radicacion,
													prefijo,
													factura_fiscal,
													usuario_id,
													vlr_nota,
													vlr_ng_aceptado,
													vlr_ng_noaceptado,
													fecha_factura,
													total_factura,
													tipo_tercero_id) 
											VALUES($fetch_FA[3],
											'$fetch_FA[4]',
											'$fetch_FA[9]',
											'$fetch_FA[0]',
											'$fetch_FA[2]',
											$fetch_FA[1],
											$fetch_FA[10],
											$vlr_nota,
											$vlr_ng_aceptado,
											$vlr_ng_noaceptado,
											'$fetch_FA[11]',
											$fetch_FA[12],
											'$fetch_FA[13]');";
											execute_query($dbh,$insert_FA);
														}
														
																												
$result_tmp=execute_query($dbh, "SELECT a.envio_id, a.fecha_envio, b.nombre_tercero, a.fecha_radicacion, a.prefijo, a.factura_fiscal, c.nombre, a.prefijo_nota, a.nota_id, a.vlr_nota, a.prefijo_ng, a.ng_id, a.vlr_ng_aceptado, a.vlr_ng_noaceptado, fecha_factura, total_factura
 FROM   tmp_reporte_cobranzas a, terceros b, system_usuarios c
 WHERE a.tercero_id = b.tercero_id AND a.tipo_tercero_id = b.tipo_id_tercero AND a.usuario_id = c.usuario_id
 ORDER BY 1"); 


?>
<br>
<table width="100%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="13" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Reporte de Facturacion Radicada desde <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="4%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Envio</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fec Envio</font></strong></a></td>
  <td width="14%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Responsable</font></strong></a></td>
  <td width="9%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Radicacion</font></strong></a></td>
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a></td>
  <td width="7%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha</font></strong></a></td>
  <td width="9%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Vlr Nota</font></strong></a></td>
  <td width="5%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota Glosa</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Vlr Acept</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Vlr no Acept</font></strong></a></td>
  </tr>
<?php

while($fetch_tmp = pg_fetch_row($result_tmp)) {
 
 if ($colorfila==0){
       $color= "#F0F0F0";
       $colorfila=1;
    }else{
       $color="white";
       $colorfila=0;
    }
    
?>

<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[2]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[3]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[4]." ".$fetch_tmp[5]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo $fetch_tmp[15]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[14]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[6]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[7]." ".$fetch_tmp[8]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo $fetch_tmp[9]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_tmp[10]." ".$fetch_tmp[11]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo $fetch_tmp[12]?></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo $fetch_tmp[13]?></font></td>
    
  </tr>														
<?php														
}


}														
?>

<?php
echo "</table>";






if(isset ($result_tmp)){
?>
<table width="100%" border="0" cellspacing="0" align="center">
<FORM ACTION='reportes_tmp_cobranzas.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>";
?>
<tr>
<td background="imagenes/cellpic1.gif" align="left">
<INPUT TYPE="SUBMIT" VALUE="IMPRIMIR">
</TD>
</FORM>
<FORM ACTION='reportes_tmp_cobranzas1.php' METHOD='POST' >
<?php
echo"
<input type=hidden name='input9' value='$input9'>
<input type=hidden name='input10' value='$input10'>";
?>
<td background="imagenes/cellpic1.gif" align="left">
<INPUT TYPE="SUBMIT" VALUE="EXPORTAR A EXCEL">
</TD>
</FORM>

</TR>
</TABLE>
<?php
}
?>
</TABLE>
