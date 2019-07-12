<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

$prefijo = $_GET["prefijo"];
$factura_fiscal = $_GET["factura_fiscal"];

open_database();


?>

<html>
<head>
<body background="#EEEEEE">
<title>Reportes de Auditoria Medica</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>


<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="t_terceros" action="index.php" method='POST'>
<table width="100%" border="0" cellspacing="0">
<tr>
<td align="center"><img src="img/logo_clinica.bmp" WIDTH="200" HEIGHT="140">
</td>
</tr>
<tr>
<td align="center">REPORTES DE AUDITORIA MEDICA</td>
</tr>
  <table width="53%" border="0" cellspacing="0" align="center">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
			<tr>
      <td bgcolor="#EEEEEE">Prefijo:<br>
      <input type="Text" name="prefijo" value="<?php echo $_REQUEST['prefijo'];?>">
				</td>
				<td bgcolor="#EEEEEE">
				Factura:<br>
				<input type="Text" name="factura_fiscal" value="<?php echo $_REQUEST['factura_fiscal'];?>">
				</tr>
			
			
<tr> 
<td bgcolor="#EEEEEE"><input type="submit" name="buscar" value="Buscar"></td>
<td bgcolor="#EEEEEE">&nbsp;</td>
</tr>
</table>
</form>
			
<?php

if ($prefijo <> '' AND $factura_fiscal <> '')
{
?>
<br>
<table width="100%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="13" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Notas Generadas para la Factura <?php echo $_REQUEST["prefijo"];?> <?php echo $_REQUEST["factura_fiscal"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="3%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Ingreso</font></strong></a></td>
  <td width="3%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cuenta</font></strong></a></td>
  <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong></a></td>
  <td width="20%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Plan</font></strong></a></td>
  <td width="8%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></strong></a></td>
  <td width="6%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Total Factura</font></strong></a></td>
  <td width="6%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Saldo</font></strong></a></td>
  <td width="25%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Responsable</font></strong></a></td>
  
  </tr>
  			

 <?PHP
 			
$result_grupo=execute_query($dbh, "SELECT ingresos.ingreso, cuentas.numerodecuenta, pacientes.tipo_id_paciente, pacientes.paciente_id, pacientes.primer_apellido, pacientes.segundo_apellido, pacientes.primer_nombre, pacientes.segundo_nombre, fac_facturas.prefijo, fac_facturas.factura_fiscal, fac_facturas.total_factura, fac_facturas.saldo, fac_facturas_cuentas.sw_tipo, planes.plan_descripcion, terceros.tipo_id_tercero, terceros.tercero_id, terceros.nombre_tercero
 FROM   (((((public.fac_facturas_cuentas fac_facturas_cuentas INNER JOIN public.cuentas cuentas ON fac_facturas_cuentas.numerodecuenta=cuentas.numerodecuenta) INNER JOIN public.fac_facturas fac_facturas ON ((fac_facturas_cuentas.empresa_id=fac_facturas.empresa_id) AND (fac_facturas_cuentas.prefijo=fac_facturas.prefijo)) AND (fac_facturas_cuentas.factura_fiscal=fac_facturas.factura_fiscal)) INNER JOIN public.ingresos ingresos ON cuentas.ingreso=ingresos.ingreso) INNER JOIN public.planes planes ON cuentas.plan_id=planes.plan_id) INNER JOIN public.pacientes pacientes ON (ingresos.paciente_id=pacientes.paciente_id) AND (ingresos.tipo_id_paciente=pacientes.tipo_id_paciente)) INNER JOIN public.terceros terceros ON (planes.tipo_tercero_id=terceros.tipo_id_tercero) AND (planes.tercero_id=terceros.tercero_id)
 WHERE  fac_facturas.prefijo='$prefijo' AND fac_facturas.factura_fiscal=$factura_fiscal"); 

if (!$result_grupo) {
    echo "Error en Consulta1.\n";
    exit;
}
?>
 <?php

while($fetch_grupo = pg_fetch_row($result_grupo)) {
?>
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[0]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[2]." ".$fetch_grupo[3]." ".$fetch_grupo[6]." ".$fetch_grupo[7]." ".$fetch_grupo[4]." ".$fetch_grupo[5]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[13]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[8]." ".$fetch_grupo[9]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo $fetch_grupo[10]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?php echo $fetch_grupo[11]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_grupo[14]." ".$fetch_grupo[15]." ".$fetch_grupo[16]?></a></font></td>
    
  </tr>
  </table>	
<?php
																														
														}
														
?>
<br>
<table width="100%" border="0" cellspacing="0">
<TR>
<TD width="50%">
<table width="94%" border="0" cellspacing="0" align="left">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Notas Credito</font></strong></div></td>
    </tr>
  <tr> 
  <td width="12%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota</font></strong></a></td>
  <td width="18%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a></td>
  <td width="28%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fec Registro</font></strong></a></td>
  <td width="42%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a></td>
 </tr>
<?PHP
 			
$result_NC=execute_query($dbh, "SELECT a.prefijo, a.nota_credito_id, a.valor_nota, a.fecha_registro, b.nombre 
FROM notas_credito a, system_usuarios b
WHERE a.usuario_id = b.usuario_id AND a.prefijo_factura = '$prefijo' AND a.factura_fiscal = $factura_fiscal"); 

if (!$result_NC) {
    echo "Error en Consulta1.\n";
    exit;
}
?>
 <?php

while($fetch_NC = pg_fetch_row($result_NC)) {
?>
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_NC[0]." ".$fetch_NC[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_NC[2]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_NC[3]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_NC[4]?></a></font></td>
    </tr>
  	
<?php
																														
														}
														
?>
</table>
</TD>
<TD width="50%">
<table width="94%" border="0" cellspacing="0" align="right">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Notas Debito</font></strong></div></td>
    </tr>
  <tr> 
  <td width="12%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota</font></strong></a></td>
  <td width="18%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a></td>
  <td width="28%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fec Registro</font></strong></a></td>
  <td width="42%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a></td>
 </tr>
<?PHP
 			
$result_ND=execute_query($dbh, "SELECT a.prefijo, a.nota_debito_id, a.valor_nota, a.fecha_registro, b.nombre 
FROM notas_debito a, system_usuarios b
WHERE a.usuario_id = b.usuario_id AND a.prefijo_factura = '$prefijo' AND a.factura_fiscal = $factura_fiscal"); 

if (!$result_ND) {
    echo "Error en Consulta1.\n";
    exit;
}
?>
 <?php

while($fetch_ND = pg_fetch_row($result_ND)) {
?>
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ND[0]." ".$fetch_ND[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ND[2]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ND[3]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_ND[4]?></a></font></td>
    </tr>

<?php
																														
														}
														
?>
</table>	
</TD>
</TR>


<TR>
<TD width="50%">
<table width="94%" border="0" cellspacing="0" align="left">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="6" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Glosas</font></strong></div></td>
    </tr>
  <tr> 
  <td width="12%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Glosa</font></strong></a></td>
  <td width="16%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a></td>
  <td width="22%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fec Registro</font></strong></a></td>
  <td width="21%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a></td>
  <td width="21%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota Glosa</font></strong></a></td>
  <td width="21%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor Aceptado</font></strong></a></td>
  
 </tr>
<?PHP
 			
$result_G=execute_query($dbh, "SELECT 'G', glosas.glosa_id, glosas.valor_glosa, glosas.fecha_glosa, notas_credito_glosas.prefijo, notas_credito_glosas.numero, notas_credito_glosas.valor_aceptado, notas_credito_glosas.valor_no_aceptado, glosas.prefijo, glosas.factura_fiscal, glosas.usuario_id
 FROM   public.notas_credito_glosas notas_credito_glosas RIGHT OUTER JOIN public.glosas glosas ON notas_credito_glosas.glosa_id=glosas.glosa_id
 WHERE  glosas.prefijo='$prefijo' AND glosas.factura_fiscal=$factura_fiscal"); 

if (!$result_G) {
    echo "Error en Consulta1.\n";
    exit;
}
?>
 <?php

while($fetch_G = pg_fetch_row($result_G)) {
 
 $usuario_G=execute_query($dbh, "SELECT a.nombre
 FROM   system_usuarios a
 WHERE  a.usuario_id = $fetch_G[10]");
 
 $Rc_vector=pg_fetch_array($usuario_G);
?>
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_G[0]." ".$fetch_G[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_G[2]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_G[3]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $Rc_vector["0"]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_G[4]." ".$fetch_G[5]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_G[6]?></a></font></td>
    <td></td>
    </tr>
    
 
<?php
														}
														
?>
</table>
</TD>
<TD width="50%">
<table width="94%" border="0" cellspacing="0" align="right">
<tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">Pagos Efectuados</font></strong></div></td>
    </tr>
  <tr> 
  <td width="32%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Recibo Caja</font></strong></a></td>
  <td width="32%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha Registro</font></strong></a></td>
  <td width="32%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor Abonado</font></strong></a></td>
  </tr>
<?PHP
 			
$result_pagos=execute_query($dbh, "SELECT a.prefijo, a.recibo_caja, a.valor_abonado, b.fecha_registro
FROM rc_detalle_tesoreria_facturas a, recibos_caja b
WHERE a.prefijo_factura = '$prefijo' AND a.factura_fiscal = $factura_fiscal AND a.sw_estado = '1'
AND a.prefijo = b.prefijo AND a.recibo_caja = b.recibo_caja"); 

if (!$result_pagos) {
    echo "Error en Consulta1.\n";
    exit;
}
?>
 <?php

while($fetch_pagos = pg_fetch_row($result_pagos)) {
?>
<tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_pagos[0]." ".$fetch_pagos[1]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_pagos[3]?></a></font></td>
    <td bgcolor="<?php echo $color; ?>"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $fetch_pagos[2]?></a></font></td>
    </tr>

<?php
																														
														}
														
?>
</table>	
</TD>
</TR>																													
</table>
<?php

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
