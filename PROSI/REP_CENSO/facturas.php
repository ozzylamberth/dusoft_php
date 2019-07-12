<html>
<head>
<title>Albaranes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="Estilos.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<body background="imagenes/fondo_bloque.gif">

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="buscarfactura" action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <table width="53%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">N&ordm; Factura</td>
      <td><input name="numfactura" type="text" class="textbox" id="numfactura" maxlength="3" value="<?=$numfactura?>"></td>
    </tr>
    <tr> 
      <td width="35%" bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; 
        Albar&aacute;n</font></td>
      <td width="65%"><input name="numalbaran" type="text" class="textbox" id="numalbaran" maxlength="3" value="<?=$numalbaran?>"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre 
        Cliente</font></td>
      <td><input name="nombre" type="text" class="textbox" id="nombre" value="<?=$nombre?>" size="40" maxlength="50"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Serie</font></td>
      <td><select name="serie" id="serie">
          <option value=''>Todas</option>

        </select></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">Desde fecha</td>
      <td><input name="desdefecha" type="text" class="textbox" id="desdefecha" maxlength="10" value="<?=$desdefecha?>">
        <input type="button" name="Submit2" value="..." onClick="javascript: show_calendar('buscarfactura.desdefecha');"></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE">Hasta fecha</td>
      <td><input name="hastafecha" type="text" class="textbox" id="hastafecha" maxlength="10" value="<?=$hastafecha?>">
        <input type="button" name="Submit22" value="..." onClick="javascript: show_calendar('buscarfactura.hastafecha');"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">&nbsp;</td>
      <td><input type="submit" name="buscar" value="Buscar"></td>
    </tr>
  </table>
<br>
<table width="100%" border="0" cellspacing="0">
  <tr> 
  <td width="13%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "1");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">N&ordm;
          Factura</font></strong></a> 
<?php
if ($order == 1)
    setOrientation($orientation);
?>
	</td>
    <td width="12%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "2");?>"> 
      <strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">N&uacute;m.
      Alb.</font></strong></a> 
<?php
if ($order == 2)
    setOrientation($orientation);
?>
    </td>
    <td width="11%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "3");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Serie</font></strong></a> 
<?php
if ($order == 3)
    setOrientation($orientation);
?>
	</td>
    <td width="11%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "4");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha</font></strong></a> 
<?php
if ($order == 4)
    setOrientation($orientation);
?>
	</td>
    <td width="53%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "5");?>"><strong><font color="#003366" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cliente</font></strong></a> 
<?php
if ($order == 5)
    setOrientation($orientation);
?>
    </td>
  </tr>

  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="editaralbaran.php?id=<?php echo $row->IDALBARAN;?>" target="_blank"><?php echo $row->NUMFACTURA?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">1</a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">2</font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">3</font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">4</font></td>
  </tr>

</table>

 <input type="hidden" name="order" value="<?=$order?>">
 <input type="hidden" name="orientation" value="<?=$orientation?>">
</form>
<?php
free_result($result);
?>
<br>
<input type="button" name="Submit" value="Nuevo albar&aacute;n" OnClick="javascript: window.open('editaralbaran.php', 'Cliente');">
<p>&nbsp;</p>
</body>
</html>
