<?php
require("includes/session.php");
/*require("includes/config.php");*/
require("includes/database.php");
require("includes/funciones.php");
?>
<html>
<head>
<title>Editar Rechazo</title>
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>
<script language="Javascript">
  function set_panel(obj){
    if (obj.id == "datosrechazo")
	{
	  datospaciente.style.visibility = "hidden";
	  datosrechazo.style.visibility = "visible";
	  document.getElementById("dg").background = "imagenes/cellpic1.gif";
	  document.getElementById("de").background = "imagenes/cellpic2.gif";
	} else
	{
	  datospaciente.style.visibility = "visible";
	  datosrechazo.style.visibility = "hidden";
	  document.getElementById("dg").background = "imagenes/cellpic2.gif";
	  document.getElementById("de").background = "imagenes/cellpic1.gif";
	}
	 
  }
  
   
 function validar(){
	if (document.getElementById("action").value == "3"){
		if (!confirm("¿Desea realmente eliminar el cliente?"))
			return false
	}
	
	return true;
} 
</script>



<?php
open_database();
$self = $_SERVER["PHP_SELF"]; 

$usuario_id = $_SESSION["usuario_id"];


procesar_entrada("GET", "id");
procesar_entrada("POST", "action", "rechazo_id", "tipo_id_paciente", "paciente_id", "primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido",
	"descripcion_diagnostico", "edad", "servicio_solicitado", "entidad_solicita", "causa_rechazo", "profesional", "observacion", "entidad_paciente", "funcionario_externo");

// Variables de control
$id = get_value($_GET["id"], "C");
$action = get_value($_POST["action"], "C"); 

// Datos del formulario
$rechazo_id = get_value($_POST["rechazo_id"], "N");
$tipo_id_paciente = get_value($_POST["tipo_id_paciente"], "C");
$paciente_id = get_value($_POST["paciente_id"], "C");
$primer_nombre = get_value($_POST["primer_nombre"], "C");
$segundo_nombre = get_value($_POST["segundo_nombre"], "C");
$primer_apellido = get_value($_POST["primer_apellido"], "C");
$segundo_apellido = get_value($_POST["segundo_apellido"], "C");
$descripcion_diagnostico = get_value($_POST["descripcion_diagnostico"], "C");
$edad = get_value($_POST["edad"], "N");
$servicio_solicitado = get_value($_POST["servicio_solicitado"], "C");
$entidad_solicita = get_value($_POST["entidad_solicita"], "C");
$causa_rechazo = get_value($_POST["causa_rechazo"], "C");
$profesional = explode("//", get_value($_POST["profesional"], "C"));
$tipo_id_tercero = $profesional[0];
$tercero_id = $profesional[1];
$entidad_paciente = explode("//", get_value($_POST["entidad_paciente"], "C"));
$entidad_tipo_id_tercero = $entidad_paciente[0];
$entidad_tercero_id = $entidad_paciente[1];
$observacion = get_value($_POST["observacion"], "C");
$funcionario_externo = get_value($_POST["funcionario_externo"], "C");
$mensaje = "";


			

switch ($action) {
	case "1": // Insertar
		$permiso = PerfilOpcionUsuario($usuario_id, $cod_menu, "insert", $dbh);
		if($permiso == ""){
			$mensaje = "ERROR: Usuario no tiene permiso para ejecutar esta accion.";
			break;
		}
		$sql = "INSERT INTO prosi_rechazos(rechazo_id"
				 . ",tipo_id_paciente"
				 . ",paciente_id"
				 . ",primer_nombre"
				 . ",segundo_nombre"
				 . ",primer_apellido"
				 . ",segundo_apellido"
				 . ",descripcion_diagnostico"
				 . ",edad"
				 . ",servicio_solicitado"
				 . ",entidad_solicita"
				 . ",causa_rechazo"
				 . ",tipo_id_tercero"
				 . ",tercero_id"
				 . ",observacion"
				 . ",usuario_id"
				 . ",entidad_tipo_id_tercero"
				 . ",entidad_tercero_id"
				 . ",funcionario_externo"
				 . ") VALUES ("
				 . "" . $rechazo_id . ""
				 . ",'" . $tipo_id_paciente . "'"
				 . ",'" . $paciente_id . "'"
				 . ",'" . $primer_nombre . "'"
				 . ",'" . $segundo_nombre . "'"
				 . ",'" . $primer_apellido . "'"
				 . ",'" . $segundo_apellido . "'"
				 . ",'" . $descripcion_diagnostico . "'"
				 . "," . $edad . ""
				 . ",'" . $servicio_solicitado . "'"
				 . ",'" . $entidad_solicita . "'"
				 . ",'" . $causa_rechazo . "'"
				 . ",'" . $tipo_id_tercero . "'"
				 . ",'" . $tercero_id . "'"
				 . ",'" . $observacion . "'"
				 . "," . $usuario_id .""
				 . ",'" . $entidad_tipo_id_tercero . "'"
				 . ",'" . $entidad_tercero_id . "'"
				 . ",'" . $funcionario_externo
				 . "')";
		
		if (execute_query($dbh, $sql)){
			$self = $self . "?id=" . $rechazo_id;
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
		$sql = "UPDATE prosi_rechazos SET "
		 . "tipo_id_paciente = '" . $tipo_id_paciente . "'"
		 . ",paciente_id = '" . $paciente_id . "'"
		 . ",primer_nombre = '" . $primer_nombre . "'"
		 . ",segundo_nombre = '" . $segundo_nombre . "'"
		 . ",primer_apellido = '" . $primer_apellido . "'"
		 . ",segundo_apellido = '" . $segundo_apellido . "'"
		 . ",descripcion_diagnostico = '" . $descripcion_diagnostico . "'"
		 . ",edad = " . $edad . ""
		 . ",servicio_solicitado = '" . $servicio_solicitado . "'"
		 . ",entidad_solicita = '" . $entidad_solicita . "'"
		 . ",causa_rechazo = '" . $causa_rechazo . "'"
		 . ",tipo_id_tercero = '" . $tipo_id_tercero . "'"
		 . ",tercero_id = '" . $tercero_id . "'"
		 . ",entidad_tipo_id_tercero = '" . $entidad_tipo_id_tercero . "'"
		 . ",entidad_tercero_id = '" . $entidad_tercero_id . "'"
		 . ",funcionario_externo = '" . $funcionario_externo . "'"
		 . ",observacion = '" . $observacion 
		 . "' WHERE rechazo_id = '" . $id . "'";

		if (execute_query($dbh, $sql)){
			$next_action = "2";
			$self = $self . "?id=" . $rechazo_id;
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
		$idrechazo = $id;
		$sql = "DELETE FROM prosi_rechazos WHERE rechazo_id = " . $idrechazo . "";
		execute_query($dbh, $sql);
		echo "<script language='Javascript'> self.close(); </script>";
		exit;

	default:
		if ($id != "") {
			$result = execute_query($dbh, "SELECT * FROM prosi_rechazos WHERE rechazo_id = '" . $id . "'");
			$row = fetch_object($result);

			if ($row) {
				$rechazo_id = $row->rechazo_id;
				$tipo_id_paciente = $row->tipo_id_paciente;
				$paciente_id = $row->paciente_id;
				$primer_nombre = $row->primer_nombre;
				$segundo_nombre = $row->segundo_nombre;
				$primer_apellido = $row->primer_apellido;
                $segundo_apellido = $row->segundo_apellido;
				$descripcion_diagnostico = $row->descripcion_diagnostico;
				$edad = $row->edad;
				$servicio_solicitado = $row->servicio_solicitado;
				$entidad_solicita = $row->entidad_solicita;
				$causa_rechazo = $row->causa_rechazo;
				$tipo_id_tercero = $row->tipo_id_tercero;
				$tercero_id = $row->tercero_id;
				$entidad_tipo_id_tercero = $row->entidad_tipo_id_tercero;
				$entidad_tercero_id = $row->entidad_tercero_id;
				$observacion = $row->observacion;
				$funcionario_externo = $row->funcionario_externo;
				

				$self = $self . "?id=" . $id;
				$next_action = "2";
				
			} else
				$next_action = "1";

			free_result($result);
		} else {
			$next_action = "1";
			$rechazo_id = get_next_val("prosi_rechazos", "rechazo_id");
		} 
} // switch
?>

<body background="imagenes/fondo_bloque.gif">
<form name="rechazo" action="<?=$self?>" method="post" onSubmit="javascript: return validar();">
<div id="datosrechazo" style="position:absolute; width:548px; height:272px; z-index:2; left: 12px; top: 43px; background-color: #EEEEEE; layer-background-color: #EEEEEE; border: 1px none #000000; visibility: hidden;">         
  <table width="100%" border="0">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Causa de Rechazo</font></strong></div></td>
      <td><select name="causa_rechazo" id="causa_rechazo">
	  	  <option value="">---</option>
			<?php
			$result = execute_query($dbh, "SELECT a.causa_rechazo, a.descripcion 
											FROM prosi_motivos_rechazo a");
			while (($row = fetch_object($result))) {
			    echo "<option value='" . $row->causa_rechazo . "'";
			    if ($row->causa_rechazo == $causa_rechazo) echo " selected ";
			    echo ">" . $row->descripcion . "</option>";
			} 
			free_result($result);
			
			?>
        </select></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Profesional</font></strong></div></td>
      <td><select name="profesional" id="profesional">
	  	  <option value="">---</option>
			<?php
			$result = execute_query($dbh, "SELECT a.tipo_id_tercero, 
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
			while (($row = fetch_object($result))) {
			    echo "<option value=$row->tipo_id_tercero"."//"."$row->tercero_id";
			    if ($row->tipo_id_tercero == $tipo_id_tercero
					AND $row->tercero_id == $tercero_id) echo " selected ";
			    echo ">" . $row->nombre . "</option>";
			} 
			free_result($result);
			
			?>
        </select></td>
        
    </tr>
    
    <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Observacion</font></strong></div></td>
        <td colspan="3">
			<textarea name="observacion" cols="70" rows="10" id="observacion">
			<?=$observacion?></textarea></td>
    </tr>
    
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </div>
<div id="datospaciente" style="position:absolute; width:694px; height:272px; z-index:2; left: 11px; top: 44px; overflow: visible; background-color: #EEEEEE; layer-background-color: #EEEEEE; border: 1px none #000000; visibility: visible;"> 
    <table width="100%" border="0" cellspacing="0">
      <tr bgcolor="#CCCCCC"> 
        <td colspan="4"><?=$mensaje?></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td width="17%"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">No Rechazo</font></strong></div></td>
        <td width="25%"><input name="rechazo_id" type="text" class="textbox" id="rechazo_id" style="background: #6699CC; text-color: #ffff00;" value="<?=$rechazo_id?>" size="12" maxlength="10"></td>
        <td width="16%"></td>
        <td width="17%"></td>
        <td width="25%"></td>
      </tr>
	  <tr bgcolor="#CCCCCC">  
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Tipo Identificacion</font></strong></div></td>
        <td><select name="tipo_id_paciente" id="tipo_id_paciente">
	  	  <option value="">---</option>
<?php
$result = execute_query($dbh, "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden");
while (($row = fetch_object($result))) {
    echo "<option value='" . $row->tipo_id_paciente . "'";
    if ($row->tipo_id_paciente == $tipo_id_paciente) echo " selected ";
    echo ">" . $row->descripcion . "</option>";
} 
free_result($result);

?>
        </select></td>
		<td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Numero Identificacion</font></strong></div></td>
		<td><input name="paciente_id" type="text" class="textbox" id="paciente_id"  value="<?=$paciente_id?>" size="30" maxlength="32">
		</td>
		<td></td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Primer Nombre</font></strong></div></td>
        <td><input name="primer_nombre" type="text" class="textbox" id="primer_nombre" value="<?=$primer_nombre?>" size="20" maxlength="20"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Segundo Nombre</font></strong></div></td>
        <td><input name="segundo_nombre" type="text" class="textbox" id="segundo_nombre" value="<?=$segundo_nombre?>" size="20" maxlength="20"></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Primer Apellido</font></strong></div></td>
        <td><input name="primer_apellido" type="text" class="textbox" id="primer_apellido" value="<?=$primer_apellido?>" size="20" maxlength="30"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Segundo Apellido</font></strong></div></td>
        <td><input name="segundo_apellido" type="text" class="textbox" id="segundo_apellido" value="<?=$segundo_apellido?>" size="20" maxlength="30"></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">EPS</font></strong></div></td>
        <td colspan = "4"><select name="entidad_paciente" id="entidad_paciente">
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
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Edad</font></strong></div></td>
        <td><input name="edad" type="text" class="textbox" id="edad" value="<?=$edad?>" size="3" maxlength="3"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Diagnostico</font></strong></div></td>
        <td><input name="descripcion_diagnostico" type="text" class="textbox" id="descripcion_diagnostico" value="<?=$descripcion_diagnostico?>" size="30" maxlength="60"></td></td>
      </tr>
    <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Servicio Solicitado</font></strong></div></td>
        <td><select name="servicio_solicitado" id="servicio_solicitado">
	  	  <option value="">---</option>
		<?php
		$result = execute_query($dbh, "SELECT a.departamento, a.descripcion 
								FROM departamentos a 
								WHERE  a.sw_internacion = '1'
								ORDER BY a.descripcion ASC ");
		while (($row = fetch_object($result))) {
		    echo "<option value='" . $row->departamento . "'";
		    if ($row->departamento == $servicio_solicitado) echo " selected ";
		    echo ">" . $row->descripcion . "</option>";
		} 
		free_result($result);

		?>
        </select></td>
    </tr>
    <tr>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Clinica</font></strong></div></td>
        <td colspan = "4"><select name="entidad_solicita" id="entidad_solicita">
	  	  <option value="">---</option>
		<?php
		$result = execute_query($dbh, "SELECT a.centro_remision, a.descripcion 
								FROM centros_remision a 
								ORDER BY a.descripcion ASC ");
		while (($row = fetch_object($result))) {
		    echo "<option value='" . $row->centro_remision . "'";
		    if ($row->centro_remision == $entidad_solicita) echo " selected ";
		    echo ">" . $row->descripcion . "</option>";
		} 
		free_result($result);

		?>
        </select></td>
        
    </tr>
    <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Funcionario Externo</font></strong></div></td>
        <td colspan = "4"><input name="funcionario_externo" type="text" class="textbox" id="funcionario_externo" value="<?=$funcionario_externo?>" size="60" maxlength="60"></td>
    </tr>    
    
      
    </table>
  <p> 
    <input name="aceptar" type="submit" id="aceptar" value="Aceptar" onClick="javascript: document.getElementById('action').value = '<?=$next_action?>';">
    <input type="button" name="Submit2" value="Cancelar" onClick="javascript: self.close();">
<?php
if ($next_action == "2") {
?>
    <input type="submit" name="borrar" value="Borrar" onClick="javascript: document.getElementById('action').value='3';">
    <input type="button" name="Submit" value="Imprimir" OnClick="javascript: window.open('imprimir_rechazo.php?rechazo_id=<?php echo $rechazo_id;?>', 'Imprimir_rechazo');">
<?php
} 
?>
</p>
</div>
<table width="553" border="0" cellspacing="2">
  <tr> 
    <td id="dg" width="263" height="29" background= "imagenes/cellpic2.gif"><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="javascript:;" onClick="javascript: set_panel(datospaciente); ">Datos 
        Paciente</a></font></div></td>
    <td id="de" width="263" background="imagenes/cellpic1.gif"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:;" onClick="javascript: set_panel(datosrechazo); "> 
        Datos Rechazo</a></font></div></td>
  </tr>
</table>
 <input type="hidden" name="action" id="action" value="<?=$next_action ?>">
</form>
</body>
</html>
