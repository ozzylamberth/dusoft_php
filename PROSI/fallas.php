<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

?>
<html>
<head>
<title>Eventos del Sistema</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">

</head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_log = $_SESSION["usuario_id"];



procesar_entrada("GET", "pagina", "registro_id", "tipo_falla_id", "usuario_id", "desdefecha", "hastafecha", "departamento", "estado", "order", "orientation", "color", "cod_menu");
$pagina = $_GET["pagina"];


$registro_id = get_value($_GET["registro_id"], "C");
$tipo_falla_id = get_value($_GET["tipo_falla_id"], "C");
$usuario_id = get_value($_GET["usuario_id"], "C");
$desdefecha = get_value($_GET["desdefecha"], "C");
$hastafecha = get_value($_GET["hastafecha"], "C");
$departamento = get_value($_GET["departamento"], "C");
$estado = get_value($_GET["estado"], "C");
$color = get_value($_GET["color"], "C");
$cod_menu = get_value($_GET["cod_menu"], "N");

if($desdefecha){
$desdefecha = $desdefecha.' 00:00:00';
}
if($hastafecha){
$hastafecha = $hastafecha.' 23:59:59';
}

$query = "SELECT a.registro_id, b.tipo_falla, d.descripcion as departamento, a.descripcion as problema, a.fecha_registro, c.nombre as nombre_usuario, e.descripcion as descripcion_estado, a.solucion, a.fecha_ocurrio, e.estado FROM registros_fallas_siis a LEFT JOIN tipos_fallas_sistema b ON a.tipo_falla_id = b.tipo_falla_id INNER JOIN system_usuarios c ON a.usuario_id = c.usuario_id INNER JOIN departamentos d ON a.departamento = d.departamento INNER JOIN fallas_estado e ON a.estado = e.estado";
$query_records = "SELECT COUNT(*) AS numreg FROM registros_fallas_siis a LEFT JOIN tipos_fallas_sistema b ON a.tipo_falla_id = b.tipo_falla_id INNER JOIN system_usuarios c ON a.usuario_id = c.usuario_id INNER JOIN departamentos d ON a.departamento = d.departamento INNER JOIN fallas_estado e ON a.estado = e.estado";

$where = build_where("a.registro_id", $registro_id, "C",
    "b.tipo_falla_id", $tipo_falla_id, "C",
    "c.usuario_id", $usuario_id, "C",
    "d.departamento", $departamento, "C",
	"e.estado", $estado, "C");
    

$filtrofecha = build_beetwen("a.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "8")
    $order = $_GET["order"];
else
    $order = "1";
    
$grupo = "";    

require("includes/consulta.php");

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
      <td width="35%" bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; Evento</font></td>
      <td width="65%"><input name="registro_id" type="text" id="registro_id" maxlength="10" value="<?=$registro_id?>" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Tipo de Evento</font></td>
      <td><select name="tipo_falla_id" id="tipo_falla_id">
          <option value=''>Todas</option>
<?php
$result_tipfalla = execute_query($dbh, "SELECT * FROM tipos_fallas_sistema ORDER BY 2");
while (($row = fetch_object($result_tipfalla))) {
    echo "<option value='" . $row->tipo_falla_id . "'";
    if ($row->tipo_falla_id == $tipo_falla_id) echo " selected ";
    echo ">" . $row->tipo_falla . "</option>";
} 
free_result($result_tipfalla);

?>
        </select></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento</font></td>
      <td><select name="departamento" id="departamento">
          <option value=''>Todas</option>
<?php
$result_departamento = execute_query($dbh, "SELECT * FROM departamentos ORDER BY 5");
while (($row = fetch_object($result_departamento))) {
    echo "<option value='" . $row->departamento . "'";
    if ($row->departamento == $departamento) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result_departamento);

?>
        </select></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></td>
      <td><input name="usuario_id" type="text" id="usuario_id" value="<?=$usuario_id?>" size="5" maxlength="4" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">Desde fecha</td>
      <td><input name="desdefecha" type="text" class="textbox" id="desdefecha" maxlength="10" value="<?=$desdefecha?>">
	  <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></td>
    </tr>
    
    <tr>
      <td bgcolor="#EEEEEE">Hasta fecha</td>
      <td><input name="hastafecha" type="text" class="textbox" id="hastafecha" maxlength="10" value="<?=$hastafecha?>">
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
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Estado</font></td>
      <td><select name="estado" id="estado">
          <option value=''>Todas</option>
<?php
$result_estado = execute_query($dbh, "SELECT * FROM fallas_estado");
while (($row = fetch_object($result_estado))) {
    echo "<option value='" . $row->estado . "'";
    if ($row->estado == $estado) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result_estado);

?>
        </select></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">
	  <?php
      $permiso = PerfilOpcionUsuario($usuario_log, $cod_menu, "insert", $dbh);
		
		if($permiso <> ""){	?>
		<input type="button" name="Submit" value="Nuevo Evento" OnClick="javascript: window.open('editarfalla.php?cod_menu=<?php echo $cod_menu;?>', 'Falla');">
		<?php }
		?>
		</td>
      <td><input type="submit" name="buscar" value="Buscar">
	  </td>
    </tr>
  </table>
  
  <?php
$permiso = PerfilOpcionUsuario($usuario_log, $cod_menu, "select", $dbh);

	if($permiso <> ""){
	
?>

<br>
<table width="100%" border="0" cellspacing="0">
  <tr> 
    <td width="5%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "1");?>"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; Evento</font></strong></a> 
<?php
if ($order == 1)
    setOrientation($orientation);
?>    </td>
    <td width="10%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "2");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Tipo de Evento </font></strong></a> 
<?php
if ($order == 2)
    setOrientation($orientation);
?>    </td>
    <td width="15%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "3");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento</font></strong></a> 
<?php
if ($order == 3)
    setOrientation($orientation);
?>    </td>
    <td width="20%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "4");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Evento</font></strong></a> 
<?php
if ($order == 4)
    setOrientation($orientation);
?>    </td>
    <td width="15%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "5");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Registro</font></strong></a> 
<?php
if ($order == 5)
    setOrientation($orientation);
?>    </td>
    <td width="10%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "6");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a> 
<?php
if ($order == 6)
    setOrientation($orientation);
?>    </td>
    <td width="10%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "7");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Estado</font></strong></a> 
<?php
if ($order == 7)
    setOrientation($orientation);
?>    </td>
    <td width="20%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "8");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Solucion</font></strong></a> 
<?php
if ($order == 8)
    setOrientation($orientation);
?>    </td>
    </tr>
<?php
$i = 1;


while (($i <= $records_per_page) && ($row = fetch_object($result))) {
 
 if ($row->estado == "1"){
	$color = "red";
										}
else{
	$color = "black";
}
 
?>
  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="editarfalla.php?id=<?php echo $row->registro_id;?>&amp;cod_menu=<?php echo $cod_menu;?>" target="_blank"><?php echo $row->registro_id?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->tipo_falla?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->departamento?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->problema?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->fecha_registro?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->nombre_usuario?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->descripcion_estado?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->solucion?></font></td>
  </tr>
  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';">
  </tr>
<?php
    $i ++;
} 
?>
</table>
<table width="100%" border="0" cellspacing="0">
<tr>
<td>
<?php
set_numpages1($num_records, $pagina);
?>
</td>
</tr>
</table>

 <input type="hidden" name="order" value="<?=$order?>">
 <input type="hidden" name="orientation" value="<?=$orientation?>">
 <input type="hidden" name="color" value="<?=$color?>">
 <input type="hidden" name="cod_menu" value="<?=$cod_menu?>">
</form>


<?php
free_result($result);
?>
<br>
<?php
      $permiso = PerfilOpcionUsuario($usuario_log, $cod_menu, "insert", $dbh);

		if($permiso <> ""){	?>
<input type="button" name="Submit" value="Nuevo Evento" OnClick="javascript: window.open('editarfalla.php?cod_menu=<?php echo $cod_menu;?>', 'Falla');">
<?php 
		}
	}	
		?>
<p>&nbsp;</p>
</body>
</html>
