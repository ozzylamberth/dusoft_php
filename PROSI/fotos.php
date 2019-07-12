<?php
require("includes/session.php");
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
$ImageDir ="imagenes/fotos/";

$ImageThumb = $ImageDir . "thumbs/";

$ImageName = $ImageDir . $image_tempname;

$mensaje = "";
?>
<html>
<head>
<title>Clientes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();

procesar_entrada("GET", "pagina", "cod_foto", "des_foto", "desdefecha", "hastafecha", "order", "orientation");
$pagina = $_GET["pagina"];

$cod_foto = get_value($_GET["cod_foto"], "C");
$des_foto = get_value($_GET["des_foto"], "C");
$desdefecha = get_value($_GET["desdefecha"], "D");
$hastafecha = get_value($_GET["hastafecha"], "D");

$query = "SELECT COD_FOTO, DES_FOTO, FEC_FOTO FROM FOTOS ";
$query_records = "SELECT COUNT(*) AS NUMREG FROM FOTOS ";

$where = build_where("COD_FOTO", $cod_foto, "C",
    "DES_FOTO", $des_foto, "C");
    

$filtrofecha = build_beetwen("FEC_FOTO", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "3")
    $order = $_GET["order"];
else
    $order = "1";

require("includes/consulta.php");

?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="buscarfoto" action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <table width="65%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
    <tr> 
      <td width="35%" bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">C&oacute;digo</font></td>
      <td width="65%"><input name="cliecodigo" type="text" id="cliecodigo" maxlength="10" value="<?=$cod_foto?>" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre de la foto</font></td>
      <td><input name="clienombre" type="text" id="clienombre" value="<?=$des_foto?>" size="40" maxlength="50" class="textbox"></td>
    </tr>
    
     <tr> 
      <td bgcolor="#EEEEEE">Desde fecha</td>
      <td><input name="desdefecha" type="text" class="textbox" id="desdefecha" maxlength="10" value="<?=$desdefecha?>">
        <input type="button" name="Submit2" value="..." onClick="javascript: show_calendar('buscarcliente.desdefecha');"></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE">Hasta fecha</td>
      <td><input name="hastafecha" type="text" class="textbox" id="hastafecha" maxlength="10" value="<?=$hastafecha?>">
        <input type="button" name="Submit22" value="..." onClick="javascript: show_calendar('buscarcliente.hastafecha');"></td>
    </tr>
    
  </table>
<br>
<table width="65%" border="0" cellspacing="0">
  <tr> 
    <td width="23%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "1");?>"> 
      <strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">C&oacute;digo</font></strong></a> 
<?php
if ($order == 1)
    setOrientation($orientation);
?>
    </td>
    <td width="50%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "2");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre de la foto</font></strong></a> 
<?php
if ($order == 2)
    setOrientation($orientation);
?>
    </td>
	<td width="26%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "3");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha de ingreso</font></strong></a> 
<?php
if ($order == 3)
    setOrientation($orientation);
?>
    </td>
	
  </tr>
<?php
$i = 1;
while (($i <= $records_per_page) && ($row = fetch_object($result))) {
?>
  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="editarfoto.php?id=<?php echo $row->COD_FOTO;?>" target="_blank"><?php echo $row->COD_FOTO?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->DES_FOTO?></font></td>
	 <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->FEC_FOTO?></font></td>
	<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo "<a href=\"".$ImageDir . $row->COD_FOTO . ".jpg\">";
echo "<img src=\"" . $ImageThumb . $row->COD_FOTO . ".jpg\" width='60' height='40' border=\"0\">";?></font></td>
  </tr>
<?php
    $i ++;
} 
?>
</table>
<?php
set_numpages($num_records, $pagina);
?>
 <input type="hidden" name="order" value="<?=$order?>">
 <input type="hidden" name="orientation" value="<?=$orientation?>">
</form>
<?php
free_result($result);
?>
<br>
<input type="button" name="Submit" value="Nueva Foto" OnClick="javascript: window.open('editarfoto.php', 'Foto');">
<p>&nbsp;</p>
</body>
</html>
