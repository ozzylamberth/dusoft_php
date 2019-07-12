<?php
require("includes/session.php");
require("includes/config.php");
require("includes/database.php");
require("includes/funciones.php");
?>
<html>
<head>
<title>Editar Cliente</title>
<link href="Estilos.css" rel="stylesheet" type="text/css">
</head>
<script language="Javascript">
  function set_panel(obj){
    if (obj.id == "datoseconomicos")
	{
	  datosgenerales.style.visibility = "hidden";
	  datoseconomicos.style.visibility = "visible";
	  document.getElementById("dg").background = "imagenes/cellpic1.gif";
	  document.getElementById("de").background = "imagenes/cellpic2.gif";
	} else
	{
	  datosgenerales.style.visibility = "visible";
	  datoseconomicos.style.visibility = "hidden";
	  document.getElementById("dg").background = "imagenes/cellpic2.gif";
	  document.getElementById("de").background = "imagenes/cellpic1.gif";
	}
	 
  }
  
  function set_clienomcom(){
    if (!document.cliente.clienomcom == ""){
		document.cliente.clienomcom.value = document.cliente.clienombre.value;
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

procesar_entrada("GET", "id");
procesar_entrada("POST", "action", "cliecodigo", "clienombre", "clienomcom", "clienit", "cliefecalta", "cliedireccion", "clieciudad",
	"clienumero", "cliepiso", "cliecp", "cliedpto", "clietel1", "clietel2", "clietel3", "cliemovil", "cliefax", "clieemail", "clieweb", "clieiva", "clieinc", "cliedto", "formapago");

// Variables de control
$id = get_value($_GET["id"], "C");
$action = get_value($_POST["action"], "C"); 

// Datos del formulario
$cliecodigo = get_value($_POST["cliecodigo"], "C");
$clienombre = get_value($_POST["clienombre"], "C");
$clienomcom = get_value($_POST["clienomcom"], "C");
$clienit = get_value($_POST["clienit"], "C");
$cliefecalta = get_value($_POST["cliefecalta"], "D");
$cliedireccion = get_value($_POST["cliedireccion"], "C");
$clienumero = get_value($_POST["clienumero"], "C");
$cliepiso = get_value($_POST["cliepiso"], "C");
$clieciudad = get_value($_POST["clieciudad"], "C");
$cliecp = get_value($_POST["cliecp"], "C");
$cliedpto = get_value($_POST["cliedpto"], "C");
$clietel1 = get_value($_POST["clietel1"], "C");
$clietel2 = get_value($_POST["clietel2"], "C");
$clietel3 = get_value($_POST["clietel3"], "C");
$cliemovil = get_value($_POST["cliemovil"], "C");
$cliefax = get_value($_POST["cliefax"], "C");
$clieemail = get_value($_POST["clieemail"], "C");
$clieweb = get_value($_POST["clieweb"], "C");
$formapago = get_value($_POST["formapago"], "C");
$clieinc = get_value($_POST["clieinc"], "N");
$cliedto = get_value($_POST["cliedto"], "N");
$clieiva = get_value($_POST["clieiva"], "N");
$mensaje = "";

switch ($action) {
	case "1": // Insertar
		if (empty($cliecodigo)) {
			$mensaje = "ERROR: Por favor, introduzca un Código válido.";
			$next_action = "1";
			break;
		} 
		
		if (empty($cliefecalta)){
			$mensaje = "ERROR: Por favor, introduzca una fecha válida.";
			$next_action = "1";
			break;
		}
				// Comprueba que el código no éste dado de alta
		if (existe_valor("CLIENTES", "IDCLIENTE", $cliecodigo, "C")){
			$mensaje = "ERROR: El Código elegido ya está dado de alta";
			$next_action = "1";
			break;
		}


		$sql = "INSERT INTO CLIENTES(IDCLIENTE"
		 . ",CLIENOMBRE"
		 . ",CLIENOMCOM"
		 . ",CLIENIT"
		 . ",CLIEFECALTA"
		 . ",CLIEDIRECCION"
		 . ",CLIENUMERO"
		 . ",CLIEPISO"
		 . ",CLIECIUDAD"
		 . ",CLIECP"
		 . ",CLIEDPTO"
		 . ",CLIETEL1"
		 . ",CLIETEL2"
		 . ",CLIETEL3"
		 . ",CLIEFAX"
		 . ",CLIEMOVIL"
		 . ",CLIEWEB"
		 . ",CLIEEMAIL"
		 . ",CLIEINC"
		 . ",CLIEDTO"
		 . ",IDFORMAPAGO"
		 . ",CLIEIVA"
		 . ") VALUES ("
		 . "'" . $cliecodigo . "'"
		 . ",'" . $clienombre . "'"
		 . ",'" . $clienomcom . "'"
		 . ",'" . $clienit . "'"
		 . ",'" . formatdate($cliefecalta) . "'"
		 . ",'" . $cliedireccion . "'"
		 . ",'" . $clienumero . "'"
		 . ",'" . $cliepiso . "'"
		 . ",'" . $clieciudad . "'"
		 . ",'" . $cliecp . "'"
		 . ",'" . $cliedpto . "'"
		 . ",'" . $clietel1 . "'"
		 . ",'" . $clietel2 . "'"
		 . ",'" . $clietel3 . "'"
		 . ",'" . $cliefax . "'"
		 . ",'" . $cliemovil . "'"
		 . ",'" . $clieweb . "'"
		 . ",'" . $clieemail . "'"
		 . "," . $clieinc
		 . "," . $cliedto
		 . ",'" . $formapago . "'"
		 . "," . $clieiva
		 . ")";

		if (execute_query($dbh, $sql)){
			$self = $self . "?id=" . $cliecodigo;
			$next_action = "2";
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
		break;

	case "2": // Modificar
		if (empty($cliecodigo)) {
			$mensaje = "ERROR: Por favor, introduzca un Código válido.";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		} 

		if (empty($cliefecalta)){
			$mensaje = "ERROR: Por favor, introduzca una fecha válida.";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		}
				
		// Comprueba que el código no éste dado de alta
		if ($id != $cliecodigo && existe_valor("CLIENTES", "IDCLIENTE", $cliecodigo, "C")){
			$mensaje = "ERROR: El Código elegido ya está dado de alta";
			$self = $self . "?id=" . $id;
			$next_action = "2";
			break;
		} 

		$sql = "UPDATE CLIENTES SET "
		 . "IDCLIENTE = '" . $cliecodigo . "'"
		 . ",CLIENOMBRE = '" . $clienombre . "'"
		 . ",CLIENOMCOM = '" . $clienomcom . "'"
		 . ",CLIENIT = '" . $clienit . "'"
		 . ",CLIEFECALTA = '" . formatdate($cliefecalta) . "'"
		 . ",CLIEDIRECCION = '" . $cliedireccion . "'"
		 . ",CLIENUMERO = '" . $clienumero . "'"
		 . ",CLIEPISO = '" . $cliepiso . "'"
		 . ",CLIECIUDAD = '" . $clieciudad . "'"
		 . ",CLIECP = '" . $cliecp . "'"
		 . ",CLIEDPTO = '" . $cliedpto . "'"
		 . ",CLIETEL1 = '" . $clietel1 . "'"
		 . ",CLIETEL2 = '" . $clietel2 . "'"
		 . ",CLIETEL3 = '" . $clietel3 . "'"
		 . ",CLIEFAX = '" . $cliefax . "'"
		 . ",CLIEMOVIL = '" . $cliemovil . "'"
		 . ",CLIEWEB = '" . $clieweb . "'"
		 . ",CLIEEMAIL = '" . $clieemail . "'"
		 . ",CLIEINC = " . $clieinc
		 . ",CLIEDTO = " . $cliedto
		 . ",IDFORMAPAGO = '" . $formapago . "'"
		 . ",CLIEIVA = " . $clieiva
		 . " WHERE IDCLIENTE = '" . $id . "'";

		if (execute_query($dbh, $sql)){
			$next_action = "2";
			$self = $self . "?id=" . $cliecodigo;
		} else {
            $mensaje = "ERROR: No se pudo ejecutar la consulta.";
			$next_action = $action;
		}
		break;

	case "3": // Eliminar
		$idcliente = $id;
		$sql = "DELETE FROM CLIENTES WHERE IDCLIENTE = '" . $idcliente . "'";
		execute_query($dbh, $sql);
		echo "<script language='Javascript'> self.close(); </script>";
		exit;

	default:
		if ($id != "") {
			$result = execute_query($dbh, "SELECT * FROM CLIENTES WHERE IDCLIENTE = '" . $id . "'");
			$row = fetch_object($result);

			if ($row) {
				$cliecodigo = $row->IDCLIENTE;
				$clienombre = $row->CLIENOMBRE;
				$clienomcom = $row->CLIENOMCOM;
				$clienit = $row->CLIENIT;
				$cliefecalta = $row->CLIEFECALTA;
				$chunks = explode("-", $cliefecalta);
                $cliefecalta = "$chunks[2]/$chunks[1]/$chunks[0]";
				$cliedireccion = $row->CLIEDIRECCION;
				$clienumero = $row->CLIENUMERO;
				$cliepiso = $row->CLIEPISO;
				$clieciudad = $row->CLIECIUDAD;
				$cliecp = $row->CLIECP;
				$cliedpto = $row->CLIEDPTO;
				$clietel1 = $row->CLIETEL1;
				$clietel2 = $row->CLIETEL2;
				$clietel3 = $row->CLIETEL3;
				$cliefax = $row->CLIEFAX;
				$cliemovil = $row->CLIEMOVIL;
				$clieemail = $row->CLIEEMAIL;
				$clieweb = $row->CLIEWEB;
				$formapago = $row->IDFORMAPAGO;
				$clieiva = $row->CLIEIVA;
				$clieinc= $row->CLIEINC;
				$cliedto = $row->CLIEDTO;
				

				$self = $self . "?id=" . $id;
				$next_action = "2";
				
			} else
				$next_action = "1";

			free_result($result);
		} else {
			$next_action = "1";
			$cliefecalta = date("d/m/Y");
			$cliecodigo = rellenar_ceros(get_next_val("CLIENTES", "IDCLIENTE"), 4);
		} 
} // switch
?>

<body background="imagenes/fondo_bloque.gif">
<form name="cliente" action="<?=$self?>" method="post" onSubmit="javascript: return validar();">
<div id="datoseconomicos" style="position:absolute; width:548px; height:272px; z-index:2; left: 12px; top: 43px; background-color: #EEEEEE; layer-background-color: #EEEEEE; border: 1px none #000000; visibility: hidden;">         
  <table width="100%" border="0">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Forma 
          pago</font></strong></div></td>
      <td><select name="formapago" id="formapago">
	  	  <option value="">---</option>
<?php
$result = execute_query($dbh, "SELECT * FROM FORMASPAGO");
while (($row = fetch_object($result))) {
    echo "<option value='" . $row->IDFORMAPAGO . "'";
    if ($row->IDFORMAPAGO == $formapago) echo " selected ";
    echo ">" . $row->FORMAPAGO . "</option>";
} 
free_result($result);

?>
        </select></td>
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">IVA</font></strong></div></td>
      <td><input name="clieiva" type="text" class="textbox" id="clieiva" value="<?=($action == 2)?$clieiva:16?>" size="10"></td>
    </tr>
    <tr> 
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Incremento</font></strong></div></td>
      <td><input name="clieinc" type="text" class="textbox" id="clieinc" value="<?=$clieinc?>" size="10"></td>
      <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Descuento</font></strong></div></td>
      <td><input name="cliedto" type="text" class="textbox" id="cliedto" value="<?=$cliedto?>" size="10"></td>
    </tr>
    
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </div>
<div id="datosgenerales" style="position:absolute; width:656px; height:272px; z-index:2; left: 11px; top: 44px; overflow: visible; background-color: #EEEEEE; layer-background-color: #EEEEEE; border: 1px none #000000; visibility: visible;"> 
    <table width="100%" border="0" cellspacing="0">
      <tr bgcolor="#CCCCCC"> 
        <td colspan="4"><?=$mensaje?></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td width="17%"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Codigo</font></strong></div></td>
        <td width="38%"><input name="cliecodigo" type="text" class="textbox" id="cliecodigo" style="background: #6699CC; text-color: #ffff00;" value="<?=$cliecodigo?>" size="12" maxlength="4"></td>
        <td width="12%"><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nombre</font></strong></div></td>
        <td width="30%"><input name="clienombre" type="text" class="textbox" id="clienombre" onChange="javascript: set_nomcom();" value="<?=$clienombre?>" size="40" maxlength="50"></td>
        <td width="3%">&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Nom. 
            Comercial</font></strong></div></td>
        <td><input name="clienomcom" type="text" class="textbox" id="clienomcom" value="<?=$clienomcom?>" size="40" maxlength="50"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">NIT</font></strong></div></td>
        <td><input name="clienit" type="text" class="textbox" id="clienit" value="<?=$clienit?>" size="15" maxlength="10"></td>
        <td>&nbsp;</td>
      </tr>
      <tr bgcolor="#CCCCCC"> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fecha 
            alta</font></strong></div></td>
        <td><input name="cliefecalta" type="text" class="textbox" id="cliefecalta" value="<?=$cliefecalta?>" size="15" maxlength="10"></td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Direccion</font></strong></div></td>
        <td><input name="cliedireccion" type="text" class="textbox" id="cliedireccion" value="<?=$cliedireccion?>" size="50" maxlength="50"></td>
        
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Piso</font></strong></div></td>
        <td><input name="cliepiso" type="text" class="textbox" id="cliepiso" value="<?=$cliepiso?>" size="10" maxlength="5"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Ciudad</font></strong></div></td>
        <td><input name="clieciudad" type="text" class="textbox" id="clieciudad" value="<?=$clieciudad?>" size="25" maxlength="50"></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">C&oacute;digo 
            Postal </font></strong></div></td>
        <td><input name="cliecp" type="text" class="textbox" id="cliecp" value="<?=$cliecp?>" size="10" maxlength="5"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Departamento</font></strong></div></td>
        <td><input name="cliedpto" type="text" class="textbox" id="cliedpto" value="<?=$cliedpto?>" size="25" maxlength="50"></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Telefono 
            1</font></strong></div></td>
        <td><input name="clietel1" type="text" class="textbox" id="clietel1" value="<?=$clietel1?>" size="15" maxlength="9"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Telefono 
            2</font></strong></div></td>
        <td><input name="clietel2" type="text" class="textbox" id="clietel2" value="<?=$clietel2?>" size="15" maxlength="9"></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Telefono 
            3</font></strong></div></td>
        <td><input name="clietel3" type="text" class="textbox" id="clietel3" value="<?=$clietel3?>" size="15" maxlength="9"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Fax</font></strong></div></td>
        <td><input name="cliefax" type="text" class="textbox" id="cliefax" value="<?=$cliefax?>" size="15" maxlength="9"></td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Celular</font></strong></div></td>
        <td><input name="cliemovil" type="text" class="textbox" id="cliemovil" value="<?=$cliemovil?>" size="15" maxlength="10"></td>
       
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td><div align="right"></div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">E-Mail</font></strong></div></td>
        <td><input name="clieemail" type="text" class="textbox" id="clieemail" value="<?=$clieemail?>" maxlength="50"></td>
        <td><div align="right"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Web</font></strong></div></td>
        <td><input name="clieweb" type="text" class="textbox" id="clieweb" value="<?=$clieweb?>" size="30" maxlength="100"></td>
        <td>&nbsp;</td>
      </tr>
    </table>
  <p> 
    <input name="aceptar" type="submit" id="aceptar" value="Aceptar" onClick="javascript: document.getElementById('action').value = '<?=$next_action?>';">
    <input type="button" name="Submit2" value="Cancelar" onClick="javascript: self.close();">
<?php
if ($next_action == "2") {
?>
    <input type="submit" name="borrar" value="Borrar" onClick="javascript: document.getElementById('action').value='3';">
<?php
} 
?>
</p>
</div>
<table width="553" border="0" cellspacing="2">
  <tr> 
    <td id="dg" width="263" height="29" background= "imagenes/cellpic2.gif"><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="javascript:;" onClick="javascript: set_panel(datosgenerales); ">Datos 
        Generales</a></font></div></td>
    <td id="de" width="263" background="imagenes/cellpic1.gif"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:;" onClick="javascript: set_panel(datoseconomicos); "> 
        Datos Econ&oacute;micos</a></font></div></td>
  </tr>
</table>
 <input type="hidden" name="action" value="<?=$next_action ?>">
</form>
</body>
</html>
