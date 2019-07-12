<?php

/**
 * $Id: AsignarTurnoAgenda.php,v 1.5 2005/06/02 18:39:13 leydi Exp $
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
		(ReturnHeader('TURNOS DISPONIBLES'));
		(ReturnBody());
		//echo $_REQUEST['AgendaId'];Agennda Uno
		//echo $_REQUEST['TunoId'];Agennda Uno
  if(!$_REQUEST['Seleccionar']){
		list($dbconn) = GetDBconn();
		(list($TipoIdProf,$IdProf,$NombreProf)=explode(',',$_REQUEST['profesional']));
		$query = "SELECT b.hora,b.agenda_cita_id,b.agenda_turno_id
		FROM agenda_turnos a,agenda_citas b
		WHERE a.profesional_id='$IdProf' AND a.tipo_id_profesional='$TipoIdProf' AND a.fecha_turno='".$_REQUEST['fecha']."' AND
		a.agenda_turno_id=b.agenda_turno_id AND (b.sw_estado=0 OR b.sw_estado=1 OR b.sw_estado=3)  AND b.sw_cantidad_pacientes_asignados < a.cantidad_pacientes AND
		a.tipo_consulta_id='".$_SESSION['BorrarAgenda']['Cita']."' AND a.sw_estado_cancelacion=0 ORDER BY hora";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		$Profesional=urlencode($_REQUEST['profesional']);
		$identificacion=urlencode($_REQUEST['identificacion']);
		$nombre_completo=urlencode($_REQUEST['nombrePaciente']);
		$accion='AsignarTurnoAgenda.php?AgendaId='.$_REQUEST['AgendaId'].'&TunoId='.$_REQUEST['TunoId'].'&Profesional='.$Profesional.'&fecha='.$_REQUEST['fecha'].'&identificacion='.$identificacion.'&nombrePaciente='.$nombre_completo.'&justificacion='.$_REQUEST['justificacion'].'&citaAsignada='.$_REQUEST['citaAsignada'];
		?>
		  <form name=forma method=POST action="<?php echo $accion ?>">
			<BR><table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
			<tr class="modulo_table_title">
			<td align="center">Empresa</td>
			<td align="center">Departamento</td>
			<td align="center">Tipo de Cita</td>
			</tr>
      <tr class="modulo_list_claro">
			<td align="center"><?php echo $_SESSION['BorrarAgenda']['nomemp'] ?></td>
			<td align="center"><?php echo $_SESSION['BorrarAgenda']['nomdep'] ?></td>
			<td align="center"><?php echo $_SESSION['BorrarAgenda']['nombre'] ?></td>
			</tr>
			</table>
			<?php
			(list($ano,$mes,$dia)=explode('-',$_REQUEST['fecha']));
			$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
      ?>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
			<tr class="modulo_table_title">
			<td align="center">Profesional</td>
			<td align="center">Fecha Agenda</td>
      </tr>
			<tr class="modulo_list_claro">
			<td align="center"><?php echo $NombreProf ?></td>
			<td align="center"><?php echo strftime("%A %d de  %B de %Y",$FechaConver) ?></td>
      </tr>
			</table>
			<table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=90%>
			<tr class="modulo_table_title">
			<td align="center">Paciente</td>
      </tr>
			<tr class="modulo_list_claro">
			<td align="center"><?php echo $_REQUEST['nombrePaciente'].'  -  '.$_REQUEST['identificacion'] ?></td>
      </tr>
			</table>
      <table border=0 cellspacing=1 align=center cellpadding=1 valign=bottom width=70%>
			<tr>
			  <td colspan="2" width=100%>
        <table border=1 align=center valign=bottom width=90% class="modulo_table">
    <?php
		  $horaAnt=-1;
			$contadorMaximo=0;
			$i=0;
			while($i<sizeof($vars)){
				$Fecha=explode(":",$vars[$i]['hora']);
				if($Fecha[0]!=$horaAnt){
					$contador=1;
					$horaAnt=$Fecha[0];
				}else{
				  $contador++;
				  $contadorMax=$contador;
          if($contadorMax>$contadorMaximo){
						$contadorMaximo=$contadorMax;
					}
				}
				$i++;
			}
		?>
		  <tr class="modulo_table_title"><td colspan="<?php echo $contadorMaximo+1?>" width=60%>SELECCION TURNO</td></tr>
      <tr class="modulo_table_title"><td>Hora</td><td colspan="<?php echo $contadorMaximo?>">Minutos</td></tr>
		<?php

		  $i=0;
		  $ban=0;
			$horaAnt=-1;
      while($i<sizeof($vars)){
			 $vector=explode(':',$vars[$i]['hora']);
        if($vector[0]!=$horaAnt){
				  if($ban==1){
     ?>
						</tr>
		<?php
					}
    ?>
          <tr>
					<td bgcolor="#F3F3E9" width=15%><?php echo $vector[0] ?></td>
					<td><input type="checkbox" name="seleccion[]" value="<?php echo $vars[$i]['agenda_cita_id'].'/'.$vars[$i]['agenda_turno_id'].'/'.$vars[$i]['hora']?>">&nbsp&nbsp&nbsp;<?php echo $vector[1] ?></td>
		<?php
          $horaAnt=$vector[0];
					$ban=1;
				}else{
     ?>
          <td><input type="checkbox" name="seleccion[]" value="<?php echo $vars[$i]['agenda_cita_id'].'/'.$vars[$i]['agenda_turno_id'].'/'.$vars[$i]['hora']?>">&nbsp&nbsp&nbsp;<?php echo $vector[1] ?></td>
		 <?php
				}
			  $i++;
			}
		?>
		    </table>
        </td>
		  </tr>

			<tr>
			<td class="label" colspan="2" width=60%>&nbsp;</td>
			</tr>

			<tr>
			<td valign="top" class="label" width=60%>OBSERVACIONES</td>
      <td align=center>
      <textarea rows="4" cols="60" class="input-text" name="observaciones"></textarea>
      </td>
			</tr>
      </table>
      <table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=40%>
      <tr><td align=center>
      <input type=submit name=Seleccionar value=SELECCIONAR class=input-submit>
      </td></tr>
			</form>
	<?php
	}elseif(!empty($_REQUEST['Seleccionar']) && empty($_REQUEST['Confirmar'])){

		$Profesional=urlencode($_REQUEST['Profesional']);
		$selecciones=$_REQUEST['seleccion'];
	  $action='AsignarTurnoAgenda.php?profesional='.$Profesional.'&turnoHora='.$_REQUEST['turnoHora'].'&observaciones='.$_REQUEST['observaciones'].'&citaAsignada='.$_REQUEST['citaAsignada'].'&AgendaId='.$_REQUEST['AgendaId'].'&TunoId='.$_REQUEST['TunoId'].'&justificacion='.$_REQUEST['justificacion'].'&Seleccionar='.$_REQUEST['Seleccionar'];
    ?>
	  <form name=forma method=POST action="<?php echo $action ?>">
		<BR><BR><table border=0 cellspacing=3 align=center cellpadding=3 valign=bottom width=80% class='normal_10'>
    <?php
		(list($TipoIdProf,$IdProf,$NombreProf)=explode(',',$_REQUEST['Profesional']));
		(list($turnoid,$agendaid,$hora)=explode('/',$_REQUEST['turnoHora']));
		(list($ano,$mes,$dia)=explode('-',$_REQUEST['fecha']));
		$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
		?>
		<tr class="modulo_table_title"><td colspan='2'>CONFIRMACION DE CAMBIO DE ASIGNACION DE CITA</td></tr>
		<tr class="modulo_list_claro">
		<td class='label'>PACIENTE</td>
		<td><?php echo $_REQUEST['nombrePaciente'].'  -  '.$_REQUEST['identificacion'] ?></td>
		</tr>
		<tr class="modulo_list_claro">
		<td class='label'>DIA</td>
		<td><?php echo strftime("%A %d de  %B de %Y",$FechaConver)?></td>
    </tr>

    <tr class="modulo_list_claro">
		<td valign="middle" rowspan="<?php echo sizeof($selecciones) ?>" class='label'>TURNO</td>
    <td><?php $vec=explode('/',$selecciones[0]);echo $vec[2];?></td>
		<input type="hidden" name="selecciones[]" value="<?php echo $vec[0].'/'.$vec[1].'/'.$vec[2]?>">
		</tr>
    <?php
      for($i=1;$i<sizeof($selecciones);$i++){
		?>
		  <tr class="modulo_list_claro"><td>
		<?php
        $vec=explode('/',$selecciones[$i]);
        echo $vec[2];
		?>
      </td></tr>
			<input type="hidden" name="selecciones[]" value="<?php echo $vec[0].'/'.$vec[1].'/'.$vec[2]?>">
		<?php
			}
		?>
		</tr>
		<tr class="modulo_list_claro">
		<td class='label'>PROFESIONAL</td>
		<td><?php echo $NombreProf ?></td>
		</tr>
    <tr><td align=center colspan=2>
    <input type=submit name=Confirmar value=CONFIRMAR class=input-submit>
    </td></tr>
		</form>
	<?php
	}else{
	  $selecciones=$_REQUEST['selecciones'];
		$Cont=0;
	  (list($TipoIdProf,$IdProf,$NombreProf)=explode(',',$_REQUEST['profesional']));
		(list($turnoid,$agendaid)=explode('/',$_REQUEST['turnoHora']));
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $sql="SELECT b.agenda_cita_asignada_id,b.agenda_cita_id,a.agenda_cita_id_padre
		FROM agenda_citas_asignadas b,(SELECT agenda_cita_id_padre FROM agenda_citas_asignadas WHERE agenda_cita_asignada_id='".$_REQUEST['citaAsignada']."') as a
		WHERE a.agenda_cita_id_padre=b.agenda_cita_id_padre;";
		$result=$dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			while(!$result->EOF){
				$CitasAsignadas[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		for($i=0;$i<sizeof($CitasAsignadas);$i++){
		  if($Cont<sizeof($selecciones)){
				$vec=explode('/',$selecciones[$Cont]);
				if($Cont==0){
					$CitaIdPadre=$vec[0];
				}
				$sql="UPDATE agenda_citas_asignadas
				SET agenda_cita_id='".$vec[0]."',agenda_cita_id_padre='".$CitaIdPadre."'
				WHERE agenda_cita_asignada_id='".$CitasAsignadas[$i]['agenda_cita_asignada_id']."'";
				$result=$dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
					$sql="UPDATE agenda_citas SET sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados+1 WHERE agenda_cita_id='".$vec[0]."' AND agenda_turno_id='".$vec[1]."'";
					$result=$dbconn->Execute($sql);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
						$sql="UPDATE agenda_citas SET sw_estado='1' WHERE sw_cantidad_pacientes_asignados=
						(SELECT cantidad_pacientes FROM agenda_turnos WHERE agenda_turno_id='".$vec[1]."') AND
						agenda_turno_id='".$vec[1]."' AND agenda_cita_id='".$vec[0]."'";
						$result=$dbconn->Execute($sql);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
					}
				}
			}
			//Estado igual a null es porque no esta asignada
			//Estado igual a 1 es porque esta asignada
			//Estado igual a 2 es porque esta Paga
			$sql="UPDATE agenda_citas SET sw_estado='3' WHERE agenda_cita_id='".$CitasAsignadas[$i]['agenda_cita_id']."'";
			$result=$dbconn->Execute($sql);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$sql="INSERT INTO agenda_citas_canceladas(agenda_cita_id, agenda_tipo_justificacion_id,observaciones,usuario_id)
				VALUES('".$CitasAsignadas[$i]['agenda_cita_id']."', '".$_REQUEST['justificacion']."','".$_REQUEST['observaciones']."','".UserGetUID()."');";
				$result=$dbconn->Execute($sql);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$Cont++;
		}
		while($Cont<sizeof($selecciones)){
		  $vec=explode('/',$selecciones[$Cont]);
      $sql="INSERT INTO agenda_citas_asignadas(agenda_cita_id,
				                                        paciente_id,
				                                        tipo_id_paciente,
																								tipo_cita,
																								plan_id,
																								cargo_cita,
																								observacion,
																								sw_atencion,
																								usuario_id,
																								sw_historia,
																								agenda_cita_id_padre)
						SELECT '".$vec[0]."',paciente_id,tipo_id_paciente,
						tipo_cita,plan_id,cargo_cita,'','0',usuario_id,sw_historia,'$CitaIdPadre'
						FROM agenda_citas_asignadas
						WHERE agenda_cita_asignada_id='".$CitasAsignadas[0]['agenda_cita_asignada_id']."'";
			$result=$dbconn->Execute($sql);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
			$Cont++;
		}
		$dbconn->CommitTrans();
		echo "<script languaje=\"javascript\">";
		echo "window.opener.xxx();";
		echo "window.close();";
		echo "</script>";
	}
  ?>
	</html>
