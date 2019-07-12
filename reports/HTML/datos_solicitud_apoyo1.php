<?php
		$_ROOT='../../';
		$VISTA='HTML';
		$SESSION_ID_EMERGENTE='APOYOS';
		include $_ROOT.'includes/enviroment.inc.php';
		$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	
		IncludeFile($fileName);
	
		print(ReturnHeader('DATOS DE LA SOLICITUD DEL APOYO'));
		print(ReturnBody());

		$hc_os_solicitud_id=$_REQUEST['solicitud'];
		$tipo_id_paciente=$_REQUEST['tipoid'];
		$paciente_id=$_REQUEST['pacienteid'];
		$nombre=$_REQUEST['nombre'];
		$cargo=$_REQUEST['cargo'];
		$titulo=$_REQUEST['titulo'];

		function Get_Datos_Solicitud($hc_os_solicitud_id)
		{
				list($dbconn) = GetDBconn();
				//cargando la observacion de la solicitud del apoyo
				$query="SELECT observacion FROM hc_os_solicitudes_apoyod
				WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Consultar los datos de la solicitud del apoyo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$a=$result->GetRowAssoc($ToUpper = false);

				//cargando los diagnosticos del apoyo
				$query="SELECT a.hc_os_solicitud_id, b.diagnostico_id, c.diagnostico_nombre
				FROM hc_os_solicitudes as a, hc_os_solicitudes_diagnosticos as b,
				diagnosticos as c  WHERE a.hc_os_solicitud_id = b.hc_os_solicitud_id
				AND b.diagnostico_id = c.diagnostico_id AND a.hc_os_solicitud_id = ".$hc_os_solicitud_id."";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al consultar los diagnosticos del apoyo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				else
				{
						while (!$result->EOF)
						{
								$vector[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
				}
				$a[diagnosticos_apoyos]=$vector;
				//fin de los diagnosticos de apoyo

				//cargando los diagnosticos de ingreso
				$query="SELECT a.hc_os_solicitud_id, b.tipo_diagnostico_id, c.diagnostico_nombre
				FROM hc_os_solicitudes as a, hc_diagnosticos_ingreso as b, diagnosticos as c
				WHERE a.evolucion_id = b.evolucion_id AND b.tipo_diagnostico_id =
				c.diagnostico_id AND a.hc_os_solicitud_id = ".$hc_os_solicitud_id."";

				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al consultar los resultados de los examenes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				else
				{
						while (!$result->EOF)
						{
								$vector1[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
						}
				}
				$a[diagnosticos_ingreso]=$vector1;
				//fin de los diagnosticos de ingreso
				return $a;
		}

		echo "<br><br><table  align=\"center\" border=\"0\"  width=\"80%\">";
		echo "<tr class=\"modulo_table_title\">";
		echo "<td align=\"center\">ID DEL PACIENTE</td>";
		echo "<td align=\"center\">NOMBRE DEL PACIENTE</td>";
		echo "</tr>";
		echo "<tr class=\"modulo_table_title\">";
		echo "<td align=\"center\">".$tipo_id_paciente.": ".$paciente_id."</td>";
		echo "<td align=\"center\">".$nombre."</td>";
		echo "</tr>";
		echo "</table><br>";

		$datos_solicitud = Get_Datos_Solicitud($hc_os_solicitud_id);
    $cont = 0;
		echo "<table  align=\"center\" border=\"0\"  width=\"80%\">";
		echo "<tr class=\"modulo_table_title\">";
		echo "<td colspan = \"2\" width=\"20%\" align=\"center\">".$cargo."</td>";
		echo "<td colspan = \"2\" width=\"80%\" align=\"center\">".strtoupper($titulo)."</td>";
		echo "</tr>";

		if( $i % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}
		if (!empty($datos_solicitud[observacion]))
		{
				echo "<tr class=\"$estilo\">";
				echo "<td align=\"left\" colspan = \"2\" class=\"hc_table_submodulo_list_title\" width=\"20%\">OBSERVACION DEL APOYO</td>";
				echo "<td colspan = \"2\">";
				echo "<table>";
						echo "<tr class=\"$estilo\">";
						echo "<td colspan = \"2\" width=\"80%\" align=\"center\">".$datos_solicitud[observacion]."</td>";
						echo "</tr>";
				echo "</table>";
				echo "</td>";
				echo "</tr>";
				$cont++;
		}
		//los diagnosticos de los apoyos
		if(sizeof($datos_solicitud[diagnosticos_apoyos])>=1)
		{
				echo "<tr class=\"$estilo\">";
				echo "<td align=\"left\" colspan = \"2\" class=\"hc_table_submodulo_list_title\" width=\"20%\">DIAGNOSTICO(S) DEL APOYO</td>";
				echo "<td colspan = \"2\">";
				echo "<table>";
				for($i=0;$i<sizeof($datos_solicitud[diagnosticos_apoyos]);$i++)
				{
						echo "<tr class=\"$estilo\">";
						echo "<td colspan = \"2\" width=\"80%\" valign=\"center\">".$datos_solicitud[diagnosticos_apoyos][$i][diagnostico_id]." - ".$datos_solicitud[diagnosticos_apoyos][$i][diagnostico_nombre]."</td>";
						echo "</tr>";
				}
				echo "</table>";
				echo "</td>";
				echo "</tr>";
				$cont++;
		}
		//fin de los diagnosticos de los apoyos

		//los diagnosticos de ingreso
		if(sizeof($datos_solicitud[diagnosticos_ingreso])>=1)
		{
				echo "<tr class=\"$estilo\">";
				echo "<td align=\"left\" colspan = \"2\" class=\"hc_table_submodulo_list_title\" width=\"20%\">DIAGNOSTICO(S) DE INGRESO</td>";
				echo "<td colspan = \"2\">";
				echo "<table>";
				for($i=0;$i<sizeof($datos_solicitud[diagnosticos_ingreso]);$i++)
				{
						echo "<tr class=\"$estilo\">";
						echo "<td colspan = \"2\" width=\"80%\" valign=\"center\">".$datos_solicitud[diagnosticos_ingreso][$i][tipo_diagnostico_id]." - ".$datos_solicitud[diagnosticos_ingreso][$i][diagnostico_nombre]."</td>";
						echo "</tr>";
				}
				echo "</table>";
				echo "</td>";
				echo "</tr>";
				$cont++;
		}
		//fin de los diagnosticos de ingreso


		if($cont == 0)
		{
				echo "<tr class=\"$estilo\">";
				echo "<td align=\"left\" colspan = \"2\" class=\"hc_table_submodulo_list_title\" width=\"20%\">NOTA</td>";
				echo "<td colspan = \"2\">";
				echo "<table>";
						echo "<tr class=\"$estilo\">";
						echo "<td colspan = \"2\" width=\"80%\" align=\"center\">No se registró ninguna observación ni diagnostico al solicitar de este apoyo.</td>";
						echo "</tr>";
				echo "</table>";
				echo "</td>";
				echo "</tr>";
		}

		echo "</table>";
		print(ReturnFooter());
?>

