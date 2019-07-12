<?php
require("includes/session.php");
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
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

procesar_entrada("GET", "pagina", "cliecodigo", "clienombre", "clienomcom", "telefono1", "movil", "desdefecha", "hastafecha", "order", "orientation");
$pagina = $_GET["pagina"];

$cliecodigo = get_value($_GET["cliecodigo"], "C");
$clienombre = get_value($_GET["clienombre"], "C");
$clienomcom = get_value($_GET["clienomcom"], "C");
$telefono1 = get_value($_GET["telefono1"], "C");
$desdefecha = get_value($_GET["desdefecha"], "D");
$hastafecha = get_value($_GET["hastafecha"], "D");
$movil = get_value($_GET["movil"], "C");

$query = "SELECT IDCLIENTE, CLIENOMBRE, CLIENOMCOM, CLIETEL1, CLIEMOVIL FROM CLIENTES ";
$query_records = "SELECT COUNT(*) AS NUMREG FROM CLIENTES ";

$where = build_where("IDCLIENTE", $cliecodigo, "C",
    "CLIENOMBRE", $clienombre, "C",
    "CLIENOMCOM", $clienomcom, "C",
    "CLIETEL1", $telefono1, "C",
    "CLIEMOVIL", $movil, "C");
    

$filtrofecha = build_beetwen("CLIEFECALTA", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "2")
    $order = $_GET["order"];
else
    $order = "1";

require("includes/consulta.php");

?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="buscarcliente" action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <table width="53%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
    <tr> 
      <td width="35%" bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">C&oacute;digo</font></td>
      <td width="65%"><input name="cliecodigo" type="text" id="cliecodigo" maxlength="10" value="<?=$cliecodigo?>" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></td>
      <td><input name="clienombre" type="text" id="clienombre" value="<?=$clienombre?>" size="40" maxlength="50" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre 
        Comercial </font></td>
      <td><input name="clienomcom" type="text" id="clienomcom" value="<?=$clienomcom?>" size="40" maxlength="50" class="textbox"></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Telefono 1</font></td>
      <td><input name="telefono1" type="text" id="telefono1" value="<?=$telefono1?>" size="40" maxlength="50" class="textbox"></td>
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
    <tr> 
      <td bgcolor="#EEEEEE">&nbsp;</td>
      <td><input type="submit" name="buscar" value="Buscar"><input type="button" name="Submit3" value="Imprimir Listado" onClick="javascript: window.open('imprimir_listado_clientes.php?cliecodigo=<?=$cliecodigo?>&amp;clienombre=<?=$clienombre?>&amp;clienomcom=<?=$clienomcom?>&amp;clietel1=<?=$telefono1?>&amp;cliemovil=<?=$movil?>&amp;desdefecha=<?=$desdefecha?>&amp;hastafecha=<?=$hastafecha?>&amp;orientation=<?=$orientation?>&amp;order=<?=$order?>', 'imprimirlistado');" ></td>
    </tr>
  </table>
<br>
<table width="53%" border="0" cellspacing="0">
  <tr> 
    <td width="23%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "1");?>"> 
      <strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">C&oacute;digo</font></strong></a> 
<?php
if ($order == 1)
    setOrientation($orientation);
?>
    </td>
    <td width="77%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "2");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></strong></a> 
<?php
if ($order == 2)
    setOrientation($orientation);
?>
    </td>
  </tr>
<?php
$i = 1;
while (($i <= $records_per_page) && ($row = fetch_object($result))) {
?>
  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="editarcliente.php?id=<?php echo $row->IDCLIENTE;?>" target="_blank"><?php echo $row->IDCLIENTE?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row->CLIENOMBRE?></font></td>
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
<input type="button" name="Submit" value="Nuevo cliente" OnClick="javascript: window.open('editarcliente.php', 'Cliente');">
<p>&nbsp;</p>
</body>
</html>
