<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");


?>
<html>
<head>
<title>Editar Evento</title>
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>
<script language="Javascript">
  function set_panel(obj){
    if (obj.id == "datossolucion")
	{
	  datosproblema.style.visibility = "hidden";
	  datossolucion.style.visibility = "visible";
	  document.getElementById("dg").background = "imagenes/cellpic1.gif";
	  document.getElementById("de").background = "imagenes/cellpic2.gif";
	} else
	{
	  datosproblema.style.visibility = "visible";
	  datossolucion.style.visibility = "hidden";
	  document.getElementById("dg").background = "imagenes/cellpic2.gif";
	  document.getElementById("de").background = "imagenes/cellpic1.gif";
	}
	 
  }
  
  
  
  function estadoBotones(){
  var estado = document.getElementById("estado");
    if (estado.selectedIndex == 0){ // Estado Pendiente
    selsolucion.disabled = true;
  } else {
  	selsolucion.disabled = false;
  }
}
  
 function validar(){
	if (document.getElementById("action").value == "3"){
		if (!confirm("¿Desea realmente eliminar el registro?"))
			return false
	}
	
	return true;
} 
</script>
<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 
$usuario_id = $_SESSION["usuario_id"];
$administrador = $_SESSION["administrador"];

procesar_entrada("GET", "id", "action", "registro_id", "tipo_falla_id", "descripcion", "fecha_registro", "fecha_ocurrio", "departamento",
	"solucion", "estado", "fecha_solucion", "informante", "cod_menu");

// Variables de control
$id = get_value($_GET["id"], "C");
$action = get_value($_GET["action"], "C"); 
// Datos del formulario
$registro_id = get_value($_GET["registro_id"], "C");
$tipo_falla_id = get_value($_GET["tipo_falla_id"], "C");
$descripcion = get_value($_GET["descripcion"], "C");
$fecha_registro = get_value($_GET["fecha_registro"], "C");
$fecha_ocurrio = get_value($_GET["fecha_ocurrio"], "C");
$departamento = get_value($_GET["departamento"], "C");
$solucion = get_value($_GET["solucion"], "C");
$fecha_solucion = get_value($_GET["fecha_solucion"], "C");
$informante = get_value($_GET["informante"], "C");
$estado = get_value($_GET["estado"], "C");
$cod_menu = get_value($_GET["cod_menu"], "N");
$mensaje = "";


switch ($action) {
	case "1": // Insertar
		$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "insert", $dbh);
		if($permiso == ""){
			$mensaje = "ERROR: Usuario no tiene permiso para ejecutar esta accion.";
			break;
		}
		if (empty($fecha_ocurrio)){
			$mensaje = "ERROR: Por favor, introduzca una fecha válida.";
			$next_action = "1";
			break;
		}
				// Comprueba que el código no éste dado de alta
		if (existe_valor("registros_fallas_siis", "registro_id", $registro_id, "C")){
			$mensaje = "ERROR: El Código elegido ya está dado de alta";
			$next_action = "1";
			break;
		}
		
		if ($estado == "1"){
			$fecha_solucion = "";
		}

		$registro_id = get_next_val("registros_fallas_siis", "registro_id");
		$sql = "INSERT INTO registros_fallas_siis(registro_id"
		 . ",tipo_falla_id"
		 . ",descripcion"
		 . ",usuario_id"
		 . ",fecha_registro"
		 . ",fecha_ocurrio"
		 . ",departamento"
		 . ",solucion"
		 . ",estado"
		 . ",fecha_solucion"
		 . ",informante"
		 . ") VALUES ("
		 . "" . $registro_id . ""
		 . "," . $tipo_falla_id . ""
		 . ",'" . $descripcion . "'"
		 . "," . $usuario_id . ""
		 . ",'" . $fecha_registro . "'"
		 . ",'" . $fecha_ocurrio . "'"
		 . ",'" . $departamento . "'"
		 . ",'" . $solucion . "'"
		 . ",'" . $estado . "'"
		 . ",'" . $fecha_solucion . "'"
		 . ",'" . $informante
		 . "')";
		if (execute_query($dbh, $sql)){
			$self = $self . "?id=" . $registro_id;
			$next_action = "2";
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
		break;

	case "2": // Modificar
	
		$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "update", $dbh);
		if($permiso == ""){
			$mensaje = "ERROR: Usuario no tiene permiso para ejecutar esta accion.";
			break;
		}
		if (empty($registro_id)) {
			$mensaje = "ERROR: Por favor, introduzca un Código válido.";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		} 

		if (empty($fecha_ocurrio)){
			$mensaje = "ERROR: Por favor, introduzca una fecha válida.";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		}
				
		// Comprueba que el código no éste dado de alta
		if ($id != $registro_id && existe_valor("registros_fallas_siis", "registro_id", $registro_id, "C")){
			$mensaje = "ERROR: El Código elegido ya está dado de alta";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		}
		
		
		if ($estado == "1"){
			$fecha_solucion = "";
		}
		$sql = "UPDATE registros_fallas_siis SET "
		 . "registro_id = " . $registro_id . ""
		 . ",tipo_falla_id = " . $tipo_falla_id . ""
		 . ",descripcion = '" . $descripcion . "'"
		 . ",fecha_ocurrio = '" . $fecha_ocurrio . "'"
		 . ",departamento = '" . $departamento . "'"
		 . ",solucion = '" . $solucion . "'"
		 . ",estado = '" . $estado . "'"
		 . ",fecha_solucion = '" . $fecha_solucion . "'"
		 . ",informante = '" . $informante
		 . "' WHERE registro_id = " . $id . "";
		 
		if (execute_query($dbh, $sql)){
			$next_action = "2";
			$self = $self . "?id=" . $registro_id;
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
		break;

	case "3": // Eliminar
	
		$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "delete", $dbh);
		if($permiso == ""){
			$mensaje = "ERROR: Usuario no tiene permiso para ejecutar esta accion.";
			break;
		}
		$idregistro = $id;
		$sql = "DELETE FROM registros_fallas_siis WHERE registro_id = " . $idregistro . "";
		execute_query($dbh, $sql);
		echo "<script language='Javascript'> self.close(); </script>";
		exit;

	default:
		if ($id != "") {
			$result = execute_query($dbh, "SELECT a.registro_id, b.tipo_falla_id, a.descripcion as problema, c.usuario_id, a.fecha_registro, a.fecha_ocurrio, d.departamento, a.solucion, e.estado, e.descripcion as descripcion_estado, a.fecha_solucion, a.informante FROM registros_fallas_siis a INNER JOIN tipos_fallas_sistema b ON a.tipo_falla_id = b.tipo_falla_id INNER JOIN system_usuarios c ON a.usuario_id = c.usuario_id INNER JOIN departamentos d ON a.departamento = d.departamento INNER JOIN fallas_estado e ON a.estado = e.estado WHERE registro_id = " . $id . "");
			$row = fetch_object($result);

			if ($row) {
				$registro_id = $row->registro_id;
				$tipo_falla_id = $row->tipo_falla_id;
				$descripcion = $row->problema;
				$usuario_id = $row->usuario_id;
				$fecha_registro = $row->fecha_registro;
				$fecha_ocurrio = $row->fecha_ocurrio;
				$fecha_solucion = $row->fecha_solucion;
				$departamento = $row->departamento;
				$solucion = $row->solucion;
				$informante = $row->informante;
				$estado = $row->estado;
				

				$self = $self . "?id=" . $id;
				$next_action = "2";
				
			} else
				$next_action = "1";

			free_result($result);
		} else {
			$next_action = "1";
			$fecha_ocurrio = date("Y-m-d H:i:s");
			$fecha_registro = date("Y-m-d H:i:s");
			$fecha_solucion = date("Y-m-d H:i:s");
			
		} 
} // switch
?>

<body background="imagenes/fondo_bloque.gif" width="1000">

<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript" src="calendar3.js"></script>
<form name="tstest">
<form name="falla" action="<?=$self?>" method="GET" onSubmit="javascript: return validar();">
<div id="datossolucion" style="position:absolute; width:478px; height:272px; z-index:2; left: 12px; top: 43px; background-color: #EEEEEE; layer-background-color: #EEEEEE; border: 1px none #000000; visibility: hidden;">
<?php
echo"
<input type=hidden name='id' value='$id'>
<input type=hidden name='fecha_registro' value='$fecha_registro'>
<input type=hidden name='usuario_id' value='$usuario_id'>
<input type=hidden name='cod_menu' value='$cod_menu'>";

?>         
  <table width="145%" border="0">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr width="100%">
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Estado</font></strong></div></td>
      <td><select name="estado" id="estado">
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
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha 
            Solucion</font></strong></div></td>
        <td><input name="fecha_solucion" type="text" class="textbox" id="fecha_solucion" maxlength="25" value="<?=$fecha_solucion?>">
	  <a href="javascript:cal9.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></td>
        <script language="JavaScript">
			<!-- // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				

				var cal9 = new calendar3(document.forms['tstest'].elements['fecha_solucion']);
				cal9.year_scroll = true;
				cal9.time_comp = true;
				
			//-->
			</script>
      </tr>
    </table>
    <table width="145%" border="0" cellspacing="0">
      <tr> 
        <td width="9%"><div align="left"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Solucion</font></strong></div></td>
        <td width="91%"><textarea name="solucion" cols="70" rows="10" id="solucion"><?=$solucion?>
    </textarea></td>
    <td width="0%"></td>
        
      </tr>
    </table>
  </div>
<div id="datosproblema" style="position:absolute; width:694px; height:272px; z-index:2; left: 11px; top: 44px; overflow: visible; background-color: #EEEEEE; layer-background-color: #EEEEEE; border: 1px none #000000; visibility: visible;"> 
    <table width="100%" border="0" cellspacing="0">
      <tr bgcolor="#CCCCCC"> 
        <td colspan="4"><?=$mensaje?></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td width="17%"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Registro</font></strong></div></td>
        <td width="38%"><input name="registro_id" type="hidden" class="textbox" id="registro_id" style="background: #6699CC; text-color: #ffff00;" value="<?=$registro_id?>" size="12" maxlength="4"><?=$registro_id?></td>
        <td width="9%"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Tipo de Evento</font></strong></div></td>
      <td width="35%"><select name="tipo_falla_id" id="tipo_falla_id">
	  	  <option value="">---</option>
<?php
$result = execute_query($dbh, "SELECT * FROM tipos_fallas_sistema WHERE estado = '1' ORDER BY 2");
while (($row = fetch_object($result))) {
    echo "<option value='" . $row->tipo_falla_id . "'";
    if ($row->tipo_falla_id == $tipo_falla_id) echo " selected ";
    echo ">" . $row->tipo_falla . "</option>";
} 
free_result($result);

?>
        </select></td>
        <td width="1%">&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento</font></strong></div></td>
      <td><select name="departamento" id="departamento">
	  	  <option value="">---</option>
<?php
$result = execute_query($dbh, "SELECT * FROM departamentos ORDER BY descripcion");
while (($row = fetch_object($result))) {
    echo "<option value='" . $row->departamento . "'";
    if ($row->departamento == $departamento) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result);

?>
        </select></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Informante</font></strong></div></td>
        <td><input name="informante" type="text" class="textbox" id="informante" value="<?=$informante?>" size="30" maxlength="50"></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha 
            Registro</font></strong></div></td>
        <td><?php echo $fecha_registro ?></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha 
            Evento</font></strong></div></td>
        <td><input name="fecha_ocurrio" type="text" class="textbox" id="fecha_ocurrio" maxlength="25" value="<?=$fecha_ocurrio?>">
	  <a href="javascript:cal10.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></td>
        <script language="JavaScript">
			<!-- // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application
				

				var cal10 = new calendar3(document.forms['tstest'].elements['fecha_ocurrio']);
				cal10.year_scroll = true;
				cal10.time_comp = true;
				
			//-->
			</script>
        <td>&nbsp;</td>
      </tr>
      
    </table>
      <table width="100%" border="0" cellspacing="0">
      <tr> 
        <td width="10%"><div align="left"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Problema</font></strong></div></td>
        <?php
        
        if ($next_action == "1"){
echo"<td width='89%'><textarea name='descripcion' cols='70' rows='10' id='descripcion'>$descripcion</textarea></td>";
		}
		else{
        if ($estado != "1"){?>
<td width="89%"><input type=hidden name="descripcion" value="<?=$descripcion?>"><?=$descripcion?></td>
<?php }
else { ?>
<td width="89%"><textarea name="descripcion" cols="70" rows="10" id="descripcion"><?=$descripcion?></textarea></td>
<?php }
}?>
<td width="1%"></td>
        
      </tr>
    </table>
  <p> 
    <input name="aceptar" type="submit" id="aceptar" value="Aceptar" onClick="javascript: document.getElementById('action').value = '<?=$next_action?>';">
    <input type="button" name="Submit2" value="Cancelar" onClick="javascript: self.close();">
<?php
if ($next_action == "2") {
 if ($administrador == "1"){
?>
	<input name="borrar" type="submit" id="borrar" value="Borrar" onClick="javascript: document.getElementById('action').value = '3';">
<?php
}
} 
?>
</p>
</div>
<table width="700" border="0" cellspacing="2">
  <tr> 
    <td id="dg" width="350" height="29" background= "imagenes/cellpic2.gif"><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="javascript:;" onClick="javascript: set_panel(datosproblema); ">Datos 
        del Evento</a></font></div></td>
        <?php 
        if ($administrador == 1)
        {?>
    <td id="de" width="350" background="imagenes/cellpic1.gif"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:;" onClick="javascript: set_panel(datossolucion); "> 
        Datos de la Solucion</a></font></div></td>
        <?php } else {?>
        <td></td>
        <?php } ?>
  </tr>
</table>
 <input type="hidden" name="action" id="action" value="<?=$next_action ?>">
</form>
</body>
</html>
