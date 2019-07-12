<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

?>
<html>
<head>
<title>Relacion de Cuentas</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">

</head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();
$usuario_id = $_SESSION["usuario_id"];
$cod_menu = $_REQUEST["cod_menu"];

procesar_entrada("GET", "pagina", "relacion_id", "usuario", "desdefecha", "hastafecha", "departamento_E", "departamento_R", "estado", "order", "orientation", "color");
$pagina = $_GET["pagina"];


$relacion_id = get_value($_GET["relacion_id"], "N");
$usuario = get_value($_GET["usuario"], "C");
$desdefecha = get_value($_GET["desdefecha"], "C");
$hastafecha = get_value($_GET["hastafecha"], "C");
$departamento_E = get_value($_GET["departamento_E"], "C");
$departamento_R = get_value($_GET["departamento_R"], "C");
$estado = get_value($_GET["estado"], "C");

if($desdefecha){
$desdefecha = $desdefecha.' 00:00:00';
}
if($hastafecha){
$hastafecha = $hastafecha.' 23:59:59';
}

$query = "SELECT a.relacion_id, a.fecha_registro, a.estado, b.usuario_id AS usuario_entrega, b.nombre, c.departamento, c.descripcion, a.departamento_recibe
 FROM relacion_cuentas a INNER JOIN departamentos c ON a.departamento_entrega=c.departamento INNER JOIN system_usuarios b ON a.usuario_entrega=b.usuario_id";
$query_records = "SELECT COUNT(*) AS numreg FROM  relacion_cuentas a INNER JOIN departamentos c ON a.departamento_entrega=c.departamento INNER JOIN system_usuarios b ON a.usuario_entrega=b.usuario_id";

$where = build_where("a.relacion_id", $relacion_id, "C",
    "b.usuario", $usuario, "C",
    "a.departamento_entrega", $departamento_E, "C",
    "a.departamento_recibe", $departamento_R, "C",
	"a.estado", $estado, "C");
    

$filtrofecha = build_beetwen("a.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "5")
    $order = $_GET["order"];
else
    $order = "1";
    
    $grupo="";

require("includes/consulta.php");



?>

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>

<link href="Estilos.css" rel="stylesheet" type="text/css">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<form name="tstest">
<form name="buscarrelacion" action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <table width="53%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="2" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
    <tr> 
      <td width="35%" bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; Relacion</font></td>
      <td width="65%"><input name="relacion_id" type="text" id="relacion_id" maxlength="10" value="<?=$relacion_id?>" class="textbox"></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Origen</font></td>
      <td><select name="departamento_E" id="departamento_E">
          <option value=''>Todas</option>
<?php
$result_departamento_E = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_cuenta = '1') ORDER BY 5");
while (($row_E = fetch_object($result_departamento_E))) {
    echo "<option value='" . $row_E->departamento . "'";
    if ($row_E->departamento == $departamento_E) echo " selected ";
    echo ">" . $row_E->descripcion . "</option>";
} 
free_result($result_departamento_E);

?>
        </select></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Departamento Destino</font></td>
      <td><select name="departamento_R" id="departamento_R">
          <option value=''>Todas</option>
<?php
$result_departamento_R = execute_query($dbh, "SELECT * FROM departamentos WHERE departamentos.departamento IN (SELECT departamento FROM departamentos_relacion_cuentas WHERE sw_relacion_cuenta = '1') ORDER BY 5");
while (($row_R = fetch_object($result_departamento_R))) {
    echo "<option value='" . $row_R->departamento . "'";
    if ($row_R->departamento == $departamento_R) echo " selected ";
    echo ">" . $row_R->descripcion . "</option>";
} 
free_result($result_departamento_R);

?>
        </select></td>
    </tr>
    
    <tr> 
      <td bgcolor="#EEEEEE"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></td>
      <td><input name="usuario" type="text" id="usuario" value="<?=$usuario?>" size="10" maxlength="25" class="textbox"></td>
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
          <option value="R" <?php echo ($estado == "R")?"selected":""?>>Relacionada</option>
          <option value="E" <?php echo($estado == "E")?"selected":""?>>Recibida</option>
        </select></td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE">
	  <?php
      $permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "insert", $dbh);
		
		if($permiso <> ""){	?>
		<input type="button" name="Submit" value="Nueva Relacion" OnClick="javascript: window.open('relaciones.php?cod_menu=<?php echo $cod_menu;?>', 'Relaciones');">
		<?php }
		?>
		</td>
      <td bgcolor="#EEEEEE">
	  <?php
      $permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "select", $dbh);
		
		if($permiso <> ""){	?>
		<input type="submit" name="buscar" value="Buscar">
		<?php }
		?>
		</td>
    </tr>
  </table>
  
  <?php
$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "select", $dbh);

	if($permiso <> ""){
	
?>
<br>
<table width="100%" border="0" cellspacing="0">
  <tr> 
    <td width="5%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "1");?>"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; Relacion</font></strong></a> 
<?php
if ($order == 1)
    setOrientation($orientation);
?>    </td>
    <td width="18%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "2");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Registro </font></strong></a> 
<?php
if ($order == 2)
    setOrientation($orientation);
?>    </td>
    <td width="29%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "3");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Usuario</font></strong></a> 
<?php
if ($order == 3)
    setOrientation($orientation);
?>    </td>
    <td width="20%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "4");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento Origen</font></strong></a> 
<?php
if ($order == 4)
    setOrientation($orientation);
?>    </td>
    <td width="20%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "5");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento Recibe</font></strong></a> 
<?php
if ($order == 5)
    setOrientation($orientation);
?>    </td>
    <td width="13%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Exp</font></strong></a></td>
    </tr>
<?php
$i = 1;


while (($i <= $records_per_page) && ($row = fetch_object($result))) {
 
 $consulta2 = "SELECT descripcion FROM departamentos 
	 			WHERE departamento = '$row->departamento_recibe'";
    			$resultado2 = execute_query($dbh, $consulta2);
       			$dpto2 = pg_fetch_row($resultado2);
       			$departamentoR = $dpto2[0];
      			free_result($resultado2);
 
 if ($row->estado == "R"){
	$color = "red";
										}
else{
	$color = "black";
}
 
?>
  <tr bgcolor="#EEEEEE" onMouseOver="javascript: this.style.backgroundColor = '#CC9900';" onMouseOut="javascript: this.style.backgroundColor = '#EEEEEE';"> 
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php
      $permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "print", $dbh);
		
		if($permiso <> ""){	?>
		<a href="imprimir_relacion_tercero1.php?relacion_id=<?php echo $row->relacion_id;?>" target="_blank">
		<?php }
		?><?php echo $row->relacion_id?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->fecha_registro?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->nombre?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->descripcion?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $departamentoR?></font></td>
    <td><a href="xls_relacion.php?relacion_id=<?php echo $row->relacion_id?>"><img src="imagenes/acceso.gif" width="11" height="11" border="0" align="absmiddle"><a/>
	<?php 
	$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "update", $dbh);
	if($row->estado == "R" AND $usuario_id == $row->usuario_entrega AND $permiso <> "")
	{?>
		<a href="relaciones.php?relacion_id=<?php echo $row->relacion_id?>&amp;cod_menu=<?php echo $cod_menu?>" target="_blank"><img src="imagenes/edit.gif"  width="75%" border="0" align="absmiddle"><a/>
	<?php } ?></td>
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
</form>


<?php
free_result($result);
?>
<br>
<?php
      $permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "insert", $dbh);
		
		if($permiso <> ""){	?>
		<input type="button" name="Submit" value="Nueva Relacion" OnClick="javascript: window.open('relaciones.php?cod_menu=<?php echo $cod_menu;?>', 'Relaciones');">
		<?php }
		}
		?>
<p>&nbsp;</p>
</body>
</html>
