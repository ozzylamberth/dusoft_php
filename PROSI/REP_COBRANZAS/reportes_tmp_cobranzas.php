<?php

require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

open_database();

$input9 = $_REQUEST["input9"];
$input10 = $_REQUEST["input10"];



?>
<html>
<head>

<title>Reporte de Cobranzas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>
<script LANGUAGE="JavaScript">
<?php




$result_tmp=execute_query($dbh, "SELECT a.envio_id, a.fecha_envio, b.nombre_tercero, a.fecha_radicacion, a.prefijo, a.factura_fiscal, c.nombre, a.prefijo_nota, a.nota_id, a.vlr_nota, a.prefijo_ng, a.ng_id, a.vlr_ng_aceptado, a.vlr_ng_noaceptado, fecha_factura, total_factura
 FROM   tmp_reporte_cobranzas a, terceros b, system_usuarios c
 WHERE a.tercero_id = b.tercero_id AND a.tipo_tercero_id = b.tipo_id_tercero AND a.usuario_id = c.usuario_id
 ORDER BY 1"); 

if (!$result_tmp) {
    echo "Error en Consulta1.\n";
    exit;
}
	
	
?>
<br>
<table width="100%" border="0" cellspacing="0" align="center">
<tr> 
      <td  background="WHITE" colspan="13" bgcolor="#EEEEEE"><div align="center"><strong><font color="BLACK" size="2">Reporte de Facturacion Radicada desde <?php echo $_REQUEST["input9"];?> hasta <?php echo $_REQUEST["input10"];?></font></strong></div></td>
    </tr>
  <tr> 
  <td width="4%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Envio</font></strong></a></td>
  <td width="8%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fec Envio</font></strong></a></td>
  <td width="14%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Responsable</font></strong></a></td>
  <td width="9%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Radicacion</font></strong></a></td>
  <td width="7%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Factura</font></strong></a></td>
  <td width="8%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Valor</font></strong></a></td>
  <td width="7%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha</font></strong></a></td>
  <td width="9%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a></td>
  <td width="5%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota</font></strong></a></td>
  <td width="8%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Vlr Nota</font></strong></a></td>
  <td width="5%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nota Glosa</font></strong></a></td>
  <td width="8%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Vlr Acept</font></strong></a></td>
  <td width="8%" background="WHITE"><strong><font color="BLACK" size="2" face="Verdana, Arial, Helvetica, sans-serif">Vlr no Acept</font></strong></a></td>
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
?>