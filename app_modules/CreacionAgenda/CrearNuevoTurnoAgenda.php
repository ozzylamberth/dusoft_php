<?php

/**
 * $Id: CrearNuevoTurnoAgenda.php,v 1.4 2005/06/02 18:39:13 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: realizar la busqueda de los paises de origen de los pacientes
 * y permite adicionar departamentos y municipios.
 */

?>
  <?php
	    $VISTA='HTML';
			$_ROOT='../../';
			include_once $_ROOT.'includes/enviroment.inc.php';
			include_once $_ROOT.'includes/modules.inc.php';
			include_once $_ROOT.'includes/api.inc.php';
			$filename="themes/$VISTA/" . GetTheme() . "/module_theme.php";
			IncludeFile($filename);
			(ReturnHeader('NUEVO TURNO'));
			(ReturnBody());
	if($_REQUEST['Hora']!=-1 && !empty($_REQUEST['Hora']) && $_REQUEST['Minutos']!=-1 && !empty($_REQUEST['Minutos']) && empty($_REQUEST['Confirmar'])){
	  $action='CrearNuevoTurnoAgenda.php?insertar='.$_REQUEST['insertar'].'&Hora='.$_REQUEST['Hora'].'&Minutos='.$_REQUEST['Minutos'].'&vec='.$_REQUEST['vec'];
  ?>
  <form name=formaUno method=POST action="<?php echo $action ?>" >
	<?php
	  list($dbconn) = GetDBconn();
		$query="SELECT min(hora),max(hora) FROM agenda_citas
		WHERE agenda_turno_id='".$_REQUEST['AgendaId']."'";
		$result = $dbconn->Execute($query);
    $minimo=$result->fields[0];
		$maximo=$result->fields[1];
		if((($_REQUEST['Hora'].':'.$_REQUEST['Minutos'])<$minimo)||(($_REQUEST['Hora'].':'.$_REQUEST['Minutos'])>$maximo)){
      ?>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
			<tr><td class="label_error" align=center>Verique, el turno nuevo se encuentra fuera de los limites de la agenda del profesional </td></tr>
			</table><br>
			<?php
		}
	?>
  <table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
	<tr><td class="label" align=center>POR FAVOR CONFIRME EL TURNO QUE VA A CREAR</td></tr>
	<tr><td class="normal_10" align=center>hh:mm <?php echo $_REQUEST['Hora'] ?> : <?php echo $_REQUEST['Minutos'] ?></td></tr>
	</tr>
	</table><br>
	<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=50%>
	<tr><td align="center">
	<input type="submit" name="Confirmar" value="CONFIRMAR" class="input-submit">
	<input type="hidden" name="AgendaId" value="<?php echo $_REQUEST['AgendaId']?>">
	</td></tr>
	</table>
	</form>
	<?php
	}elseif(!$_REQUEST['insertar'] || $_REQUEST['Hora']==-1 || $_REQUEST['Minutos']==-1){
	    $action='CrearNuevoTurnoAgenda.php?vec='.$_REQUEST['vec'];
	?>
			<form name=forma method=POST action="<?php echo $action?>">
			<BR>
			<?php
			if($_REQUEST['Hora']==-1 || $_REQUEST['Minutos']==-1){
			?>
        <table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
        <tr><td class="label_error" align=center>SELECCIONE LA HORA Y LOS MINUTOS</td></tr>
        </table><br>
			<?php
			}
			?>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=80% class="modulo_list_oscuro">
      <tr><td class="label">
      HORA TURNO
			</td>
			<td>
			<select name=Hora  class="select">
			  <option value = -1>Hora</option>";
        <?php
          for($j=0;$j<=23; $j++){
            $j=str_pad($j,2,0,STR_PAD_LEFT);
				    if($_REQUEST['Hora']==$j){
				?>
				    <option selected value = "<?php echo $j ?>" ><? echo $j ?></option>
				<?php
				    }else{
				?>
				      <option value = "<?php echo $j ?>" ><? echo $j ?></option>
				<?php
				    }
			    }
				?>
			</select>
      </td>
      <td>
      <select name=Minutos  class="select">
        <option value = -1>Minutos</option>";
			  <?php
		      for ($j=0;$j<=59; $j++){
            $j=str_pad($j,2,0,STR_PAD_LEFT);
				    if($_REQUEST['Minutos']==$j){
				?>
					    <option selected value = "<?echo $j ?>" ><? echo $j ?></option>
				<?php
				    }else{
				?>
					    <option value= "<? echo $j ?>" ><? echo $j ?></option>
				<?php
				    }
					}
        ?>
			</select>
			</td>
      <table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=50%>
      <tr><td align="center">
			<input type="submit" name="insertar" value="INSERTAR" class="input-submit">
			<input type="hidden" name="AgendaId" value="<?php echo $_REQUEST['AgendaId']?>">
      </td></tr>
			</table>
			</form>
      <?php
	}else{
		list($dbconn) = GetDBconn();
		$query="SELECT nextval('agenda_citas_agenda_cita_id_seq')";
		$result = $dbconn->Execute($query);
		$turnoId=$result->fields[0];
		$query="INSERT INTO agenda_citas(agenda_cita_id,hora,agenda_turno_id,sw_cantidad_pacientes_asignados,sw_estado)
		VALUES('$turnoId','".$_REQUEST['Hora'].":".$_REQUEST['Minutos']."','".$_REQUEST['AgendaId']."',0,0)";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)	{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		echo "<script languaje=\"javascript\">";
		echo "window.opener.xxx();";
		echo "window.close();";
		echo "</script>";
	}
	?>
	</html>
