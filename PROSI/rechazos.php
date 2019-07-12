<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");

?>
<html>
<head>
<title>Rechazos de Pacientes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">

</head>

<body background="imagenes/fondo_bloque.gif">

<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_log = $_SESSION["usuario_id"];



procesar_entrada("GET", "pagina", "rechazo_id", "tipo_id_paciente", "paciente_id", 
"nombre", "apellido", "servicio_solicitado", "entidad_solicita", "causa_rechazo", 
"profesional", "entidad_paciente", "desdefecha", "hastafecha", "order", "orientation", "color", "cod_menu");
$pagina = $_GET["pagina"];


$rechazo_id = get_value($_GET["rechazo_id"], "N");
$tipo_id_paciente = get_value($_GET["tipo_id_paciente"], "C");
$paciente_id = get_value($_GET["paciente_id"], "C");
$nombre = get_value($_GET["nombre"], "C");
$apellido = get_value($_GET["apellido"], "C");
$servicio_solicitado = get_value($_GET["servicio_solicitado"], "C");
$entidad_solicita = get_value($_GET["entidad_solicita"], "C");
$causa_rechazo = get_value($_GET["causa_rechazo"], "C");
$profesional = explode("//", get_value($_GET["profesional"], "C"));
$tipo_id_tercero = $profesional[0];
$tercero_id = $profesional[1];
$entidad_paciente = explode("//", get_value($_GET["entidad_paciente"], "C"));
$entidad_tipo_id_tercero = $entidad_paciente[0];
$entidad_tercero_id = $entidad_paciente[1];
$desdefecha = get_value($_GET["desdefecha"], "C");
$hastafecha = get_value($_GET["hastafecha"], "C");
$color = get_value($_GET["color"], "C");
$cod_menu = get_value($_GET["cod_menu"], "N");

if($desdefecha){
$desdefecha = $desdefecha.' 00:00:00';
}
if($hastafecha){
$hastafecha = $hastafecha.' 23:59:59';
}

$query = "SELECT a.rechazo_id,
				a.edad,
				e.descripcion as servicio_solicitado,
				b.descripcion as causa_rechazo,
				d.nombre,
				TO_CHAR(a.fecha_registro, 'YYYY-MM-DD') as fecha_registro, 
				a.tipo_id_paciente, 
				a.paciente_id, 
				a.primer_nombre, 
				a.segundo_nombre, 
				a.primer_apellido, 
				a.segundo_apellido
 		FROM (((prosi_rechazos a INNER JOIN prosi_motivos_rechazo b ON a.causa_rechazo=b.causa_rechazo) 
	INNER JOIN centros_remision c ON a.entidad_solicita=c.centro_remision) 
	INNER JOIN profesionales d ON (a.tipo_id_tercero=d.tipo_id_tercero) AND (a.tercero_id=d.tercero_id))
	INNER JOIN terceros f ON (a.entidad_tipo_id_tercero=f.tipo_id_tercero) AND (a.entidad_tercero_id=f.tercero_id) 
	INNER JOIN departamentos e ON a.servicio_solicitado=e.departamento";
$query_records = "SELECT COUNT(*) AS numreg 
				FROM (((prosi_rechazos a INNER JOIN prosi_motivos_rechazo b ON a.causa_rechazo=b.causa_rechazo) 
	INNER JOIN centros_remision c ON a.entidad_solicita=c.centro_remision) 
	INNER JOIN profesionales d ON (a.tipo_id_tercero=d.tipo_id_tercero) AND (a.tercero_id=d.tercero_id))
	INNER JOIN terceros f ON (a.entidad_tipo_id_tercero=f.tipo_id_tercero) AND (a.entidad_tercero_id=f.tercero_id) 
	INNER JOIN departamentos e ON a.servicio_solicitado=e.departamento";

$where = build_where("a.rechazo_id", $rechazo_id, "N",
    "a.tipo_id_paciente", $tipo_id_paciente, "C",
    "a.paciente_id", $paciente_id, "C",
    "a.primer_nombre||a.segundo_nombre", $nombre, "C",
    "a.primer_apellido||a.segundo_apellido", $apellido, "C",
    "e.departamento", $servicio_solicitado, "C",
    "c.centro_remision", $entidad_solicita, "C",
    "b.causa_rechazo", $causa_rechazo, "C",
    "d.tipo_id_tercero", $tipo_id_tercero, "C",
    "d.tercero_id", $tercero_id, "C",
	"f.tipo_id_tercero", $entidad_tipo_id_tercero, "C",
	"f.tercero_id", $entidad_tercero_id, "C");
    

$filtrofecha = build_beetwen("a.fecha_registro", formatdate($desdefecha), formatdate($hastafecha), "C");

if ($where && $filtrofecha) 
	$where .= " AND ";
$where .= $filtrofecha;

if ($_GET["order"] >= "1" && $_GET["order"] <= "6")
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
<form name="buscarrechazo" action="<?=$_SERVER['PHP_SELF']?>" method="get">
  <table width="53%" border="0" cellspacing="0">
    <tr> 
      <td  background="imagenes/cellpic1.gif" colspan="4" bgcolor="#EEEEEE"><div align="center"><strong><font color="#003366" size="2">B&uacute;squeda</font></strong></div></td>
    </tr>
    <tr> 
      <td width="35%" bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">N&ordm; Rechazo</font></strong></div></td>
      <td width="65%" bgcolor="#EEEEEE"><input name="rechazo_id" type="text" id="rechazo_id" maxlength="10" value="<?=$rechazo_id?>" class="textbox"></td>
      <td bgcolor="#EEEEEE">&nbsp;</td>
      <td bgcolor="#EEEEEE">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Tipo Identificacion</font></strong></div></td>
        <td bgcolor="#EEEEEE"><select name="tipo_id_paciente" id="tipo_id_paciente">
	  	  <option value="">---</option>
<?php
$result_tipo_paciente = execute_query($dbh, "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden");
while (($row = fetch_object($result_tipo_paciente))) {
    echo "<option value='" . $row->tipo_id_paciente . "'";
    if ($row->tipo_id_paciente == $tipo_id_paciente) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result_tipo_paciente);

?>
        </select></td>
		<td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Numero Identificacion</font></strong></div></td>
		<td bgcolor="#EEEEEE"><input name="paciente_id" type="text" class="textbox" id="paciente_id"  value="<?=$paciente_id?>" size="30" maxlength="32">
		</td>
    </tr>
    <tr> 
    <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></strong></div></td>
		<td bgcolor="#EEEEEE"><input name="nombre" type="text" class="textbox" id="nombre"  value="<?=$nombre?>" size="20" maxlength="20">
		</td>
	<td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Apellido</font></strong></div></td>
		<td bgcolor="#EEEEEE"><input name="apellido" type="text" class="textbox" id="apellido"  value="<?=$apellido?>" size="20" maxlength="30">
		</td>	
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Servicio Solicitado</font></strong></div></td>
        <td bgcolor="#EEEEEE"><select name="servicio_solicitado" id="servicio_solicitado">
	  	  <option value="">---</option>
		<?php
		$result_servicio = execute_query($dbh, "SELECT a.departamento, a.descripcion 
								FROM departamentos a 
								WHERE  a.sw_internacion = '1'
								ORDER BY a.descripcion ASC ");
		while (($row = fetch_object($result_servicio))) {
		    echo "<option value='" . $row->departamento . "'";
		    if ($row->departamento == $servicio_solicitado) echo " selected ";
		    echo ">" . $row->descripcion . "</option>";
		} 
		free_result($result_servicio);

		?>
        </select></td>
    	<td bgcolor="#EEEEEE">&nbsp;</td>
      	<td bgcolor="#EEEEEE">&nbsp;</td>
    </tr>
    <tr>
        <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Clinica</font></strong></div></td>
        <td colspan = "3" bgcolor="#EEEEEE"><select name="entidad_solicita" id="entidad_solicita">
	  	  <option value="">---</option>
		<?php
		$result_entidad = execute_query($dbh, "SELECT a.centro_remision, a.descripcion 
								FROM centros_remision a 
								ORDER BY a.descripcion ASC ");
		while (($row = fetch_object($result_entidad))) {
		    echo "<option value='" . $row->centro_remision . "'";
		    if ($row->centro_remision == $entidad_solicita) echo " selected ";
		    echo ">" . $row->descripcion . "</option>";
		} 
		free_result($result_entidad);

		?>
        </select></td>
        
    </tr>
    <tr>
        <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">EPS</font></strong></div></td>
        <td colspan = "3" bgcolor="#EEEEEE"><select name="entidad_paciente" id="entidad_paciente">
	  	  <option value="">---</option>
			<?php
			$result_entidad_paciente = execute_query($dbh, "SELECT a.tipo_id_tercero, a.tercero_id, a.nombre_tercero
											FROM terceros a, terceros_clientes b
											WHERE (a.tipo_id_tercero = b.tipo_id_tercero
											AND a.tercero_id = b.tercero_id)
											ORDER BY nombre_tercero");
			while (($row = fetch_object($result_entidad_paciente))) {
			    echo "<option value=$row->tipo_id_tercero"."//"."$row->tercero_id";
			    if ($row->tipo_id_tercero == $entidad_tipo_id_tercero
					AND $row->tercero_id == $entidad_tercero_id) echo " selected ";
			    echo ">" . $row->nombre_tercero. "</option>";
			} 
			free_result($result_entidad_paciente);
			
			?>
        </select></td>
        
    </tr>
    <tr>
      <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Causa de Rechazo</font></strong></div></td>
      <td bgcolor="#EEEEEE"><select name="causa_rechazo" id="causa_rechazo">
	  	  <option value="">---</option>
			<?php
			$result_rechazo = execute_query($dbh, "SELECT a.causa_rechazo, a.descripcion 
											FROM prosi_motivos_rechazo a");
			while (($row = fetch_object($result_rechazo))) {
			    echo "<option value='" . $row->causa_rechazo . "'";
			    if ($row->causa_rechazo == $causa_rechazo) echo " selected ";
			    echo ">" . $row->descripcion . "</option>";
			} 
			free_result($result_rechazo);
			
			?>
        </select></td>
        <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Profesional</font></strong></div></td>
      <td bgcolor="#EEEEEE"><select name="profesional" id="profesional">
	  	  <option value="">---</option>
			<?php
			$result_profesional = execute_query($dbh, "SELECT a.tipo_id_tercero, 
												       a.tercero_id, 
												       a.nombre 
												FROM profesionales a,
												     system_usuarios_departamentos b,
												     departamentos c,
												     system_usuarios d
												WHERE a.tipo_profesional IN ('1','2')
												AND   a.estado = '1'
												AND   a.usuario_id = b.usuario_id
												AND   b.departamento = c.departamento
												AND   c.sw_internacion = '1'
												AND   a.usuario_id = d.usuario_id
												AND   d.activo = '1'
												GROUP BY 1,2,3
												ORDER BY nombre");
			while (($row = fetch_object($result_profesional))) {
			    echo "<option value=$row->tipo_id_tercero"."//"."$row->tercero_id";
			    if ($row->tipo_id_tercero == $tipo_id_tercero
					AND $row->tercero_id == $tercero_id) echo " selected ";
			    echo ">" . $row->nombre . "</option>";
			} 
			free_result($result_profesional);
			
			?>
        </select></td>
        
    </tr>
    <tr> 
      <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Desde fecha</font></strong></div></td>
      <td bgcolor="#EEEEEE"><input name="desdefecha" type="text" class="textbox" id="desdefecha" maxlength="10" value="<?=$desdefecha?>">
	  <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></td>
    <td bgcolor="#EEEEEE"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Hasta fecha</font></strong></div></td>
      <td bgcolor="#EEEEEE"><input name="hastafecha" type="text" class="textbox" id="hastafecha" maxlength="10" value="<?=$hastafecha?>">
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
      <td bgcolor="#EEEEEE">
	  <?php
      $permiso = PerfilOpcionUsuario($usuario_log, $cod_menu, "insert", $dbh);
		
		if($permiso <> ""){	?>
		<input type="button" name="Submit" value="Nuevo Rechazo" OnClick="javascript: window.open('editarrechazo.php?cod_menu=<?php echo $cod_menu;?>', 'Rechazo');">
		<?php }
		?>
		</td>
      <td bgcolor="#EEEEEE"><input type="submit" name="buscar" value="Buscar">
      <input type="button" name="Submit" value="Imprimir" OnClick="javascript: window.open('imprimir_consulta_rechazos.php?rechazo_id=<?php echo $rechazo_id;?>&amp;tipo_id_paciente=<?php echo $tipo_id_paciente;?>&amp;paciente_id=<?php echo $paciente_id;?>&amp;nombre=<?php echo $nombre;?>&amp;apellido=<?php echo $apellido;?>&amp;servicio_solicitado=<?php echo $servicio_solicitado;?>&amp;entidad_solicita=<?php echo $entidad_solicita;?>&amp;causa_rechazo=<?php echo $causa_rechazo;?>&amp;tipo_id_tercero=<?php echo $tipo_id_tercero;?>&amp;tercero_id=<?php echo $tercero_id;?>&amp;entidad_tipo_id_tercero=<?php echo $entidad_tipo_id_tercero;?>&amp;entidad_tercero_id=<?php echo $entidad_tercero_id;?>&amp;desdefecha=<?php echo $desdefecha;?>&amp;hastafecha=<?php echo $hastafecha;?>', 'Imprimir_rechazo');">
	  </td>
	  <td colspan="2" bgcolor="#EEEEEE">
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
    <td width="2%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "1");?>"> 
      <strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">N&ordm</font></strong></a> 
<?php
if ($order == 1)
    setOrientation($orientation);
?>    </td>
    <td width="30%" background="imagenes/cellpic1.gif"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Paciente</font></strong> 
</td>
<td width="5%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "2");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Edad</font></strong></a> 
<?php
if ($order == 2)
    setOrientation($orientation);
?>    </td>
    <td width="15%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "3");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Servicio Solicitado</font></strong></a> 
<?php
if ($order == 3)
    setOrientation($orientation);
?>    </td>
    <td width="18%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "4");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Causa Rechazo</font></strong></a> 
<?php
if ($order == 4)
    setOrientation($orientation);
?>    </td>
    <td width="25%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "5");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Profesional</font></strong></a> 
<?php
if ($order == 5)
    setOrientation($orientation);
?>    </td>
	<td width="10%" background="imagenes/cellpic1.gif"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . querystring_changeval("order", "6");?>"><strong><font color="#003366" size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha Registro</font></strong></a> 
<?php
if ($order == 6)
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
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="editarrechazo.php?id=<?php echo $row->rechazo_id;?>&amp;cod_menu=<?php echo $cod_menu;?>" target="_blank"><?php echo $row->rechazo_id?></a></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->tipo_id_paciente." ".$row->paciente_id." ".$row->primer_nombre." ".$row->segundo_nombre." ".$row->primer_apellido." ".$row->segundo_apellido?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->edad?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->servicio_solicitado?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->causa_rechazo?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->nombre?></font></td>
    <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif" font color = "<?php echo $color;?>"><?php echo $row->fecha_registro?></font></td>
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
<input type="button" name="Submit" value="Nuevo Rechazo" OnClick="javascript: window.open('editarrechazo.php?cod_menu=<?php echo $cod_menu;?>', 'Rechazo');">
<?php 
		}
	}	
		?>
<p>&nbsp;</p>
</body>
</html>
