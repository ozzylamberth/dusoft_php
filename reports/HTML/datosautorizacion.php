<?php
		$_ROOT='../../';
		$VISTA='HTML';
		include $_ROOT.'includes/enviroment.inc.php';
		$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	
		IncludeFile($fileName);
	
		print(ReturnHeader('AUTORIZACIONES - DATOS AUTORIZACION'));
		print(ReturnBody());
	
		$int=$_REQUEST['autorizacion_int'];
		$ext=$_REQUEST['autorizacion_ext'];
	
		list($dbconn) = GetDBconn();	
		$query = "select  b.*, g.nombre
							from autorizaciones as b, system_usuarios as g
							where (b.autorizacion=".$int." or b.autorizacion=".$ext.")
							and b.usuario_id=g.usuario_id";
		$result=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}

		while(!$result->EOF)
		{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
		}
		$result->Close();	
		
		function DatosAutorizaciones($autorizacion,$tabla)
		{
				list($dbconn) = GetDBconn();
				if($tabla=='autorizaciones_por_sistema')
				{
								$query = "select  b.nombre, a.* from $tabla as a, system_usuarios as b
													where a.autorizacion=$autorizacion and a.usuario_id=b.usuario_id";
				}
				else
				{
							$query = "select * from $tabla where autorizacion=$autorizacion";
				}
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al traer los cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				if(!$result->EOF)
				{
						while(!$result->EOF)
						{
										$var[]=$result->GetRowAssoc($ToUpper = false);
										$result->MoveNext();
						}
				}
				return $var;
		}
	
		echo "	<br><table width=\"70%\" cellspacing=\"3\" border=\"0\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
		echo "		<tr class=\"modulo_list_claro\">";
		echo "		  <td width=\"18%\" class=\"label\">FECHA REGISTRO: </td>";
		echo "		  <td>".$vars[0][fecha_registro]."</td>";
		echo "		  <td width=\"12%\" class=\"label\">USUARIO: </td>";
		echo "		  <td width=\"40%\">".$vars[0][nombre]."</td>";
		echo "		</tr>";
		echo "		<tr class=\"modulo_list_claro\">";
		echo "		  <td class=\"label\">OBSERVACIONES: </td>";
		echo "		  <td colspan=\"3\">".$vars[0][observaciones]."</td>";
		echo "		</tr>";
		echo "</table><br>";	
	
		
		//certificado
		$cart=DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_certificados');
		if(!empty($cart))
		{
				echo "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
				echo "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES POR CERTIFICADO DE CARTERA</td></tr>";
				echo "		<tr class=\"modulo_list_oscuro\"><td>";
				echo "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
				echo "  <tr class=\"modulo_table_list_title\">";
				echo "  <td width=\"6%\">COD. AUTO.</td>";
				echo "  <td width=\"26%\">RESPONSABLE</td>";
				echo "  <td width=\"6%\">TERMINACION</td>";
				echo "  <td>OBSERVACIONES</td>";
				echo "  </tr>";
				for($i=0; $i<sizeof($cart); $i++)
				{
						$fecha = explode(' ',$cart[$i][fecha_terminacion]);
						$class='';
						if(strtotime(date("Y-m-d")) > strtotime($fecha[0]))
						{   $class='label_error';   }
						echo "  <tr class=\"modulo_list_claro\">";
						echo "  <td align=\"center\" width=\"10%\">".$cart[$i][codigo_autorizacion]."</td>";
						echo "  <td align=\"center\">".$cart[$i][responsable]."</td>";
						echo "  <td align=\"center\" class=\"$class\">".$fecha[0]."</td>";
						echo "  <td>".$cart[$i][observaciones]."</td>";
						echo "  </tr>";
				}
				echo "</table>";
				echo "  </td></tr>";
				echo "</table>";
		}
			//escrita
			$escrita=DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_escritas');
			if(!empty($escrita))
			{
					echo "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES ESCRITAS</td></tr>";
					echo  "		<tr class=\"modulo_list_oscuro\"><td>";
					echo "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo "  <tr class=\"modulo_table_list_title\">";
					echo "  <td width=\"10%\">COD. AUTO.</td>";
					echo "  <td width=\"10%\">TERMINACION</td>";
					echo "  <td>OBSERVACIONES</td>";
					echo "  </tr>";
					for($i=0; $i<sizeof($escrita); $i++)
					{
							$fecha = explode(' ',$escrita[$i][validez]);
							$class='';
							if(strtotime(date("Y-m-d")) > strtotime($fecha[0]))
							{   $class='label_error';   }
							echo "  <tr class=\"modulo_list_claro\">";
							echo "  <td align=\"center\" width=\"10%\">".$escrita[$i][codigo_autorizacion]."</td>";
							echo "  <td align=\"center\" class=\"$class\">".$fecha[0]."</td>";
							echo "  <td>".$escrita[$i][observaciones]."</td>";
							echo "  </tr>";
					}
					echo  "</table>";
					echo "  </td></tr>";
					echo  "</table>";
			}
			//INTERNAS
			$sistema=DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_por_sistema');
			if(!empty($sistema))
			{
					echo "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES INTERNAS</td></tr>";
					echo  "		<tr class=\"modulo_list_oscuro\"><td>";
					echo "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo "  <tr class=\"modulo_table_list_title\">";
					echo "  <td width=\"10%\">COD. AUTO.</td>";
					echo "  <td width=\"33%\">USUARIO AUTORIZADOR</td>";
					echo "  <td>OBSERVACIONES</td>";
					echo "  </tr>";
					for($i=0; $i<sizeof($sistema); $i++)
					{
							echo "  <tr class=\"modulo_list_claro\">";
							echo "  <td align=\"center\" width=\"10%\">".$sistema[$i][autorizacion_por_sistema_id]."</td>";
							echo "  <td align=\"center\" class=\"$class\">".$sistema[$i][nombre]."</td>";
							echo "  <td>".$sistema[$i][observaciones]."</td>";
							echo "  </tr>";
					}
					echo  "</table>";
					echo "  </td></tr>";
					echo  "</table>";
			}
			//TELEFONICAS
			$tele=DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_telefonicas');
			if(!empty($tele))
			{
					echo "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES INTERNAS</td></tr>";
					echo  "		<tr class=\"modulo_list_oscuro\"><td>";
					echo "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo "  <tr class=\"modulo_table_list_title\">";
					echo "  <td width=\"10%\">COD. AUTO.</td>";
					echo "  <td width=\"30%\">RESPONSABLE</td>";
					echo "  <td>OBSERVACIONES</td>";
					echo "  </tr>";
					for($i=0; $i<sizeof($tele); $i++)
					{
							echo "  <tr class=\"modulo_list_claro\">";
							echo "  <td align=\"center\" width=\"10%\">".$tele[$i][codigo_autorizacion]."</td>";
							echo "  <td align=\"center\" class=\"$class\">".$tele[$i][responsable]."</td>";
							echo "  <td>".$tele[$i][observaciones]."</td>";
							echo "  </tr>";
					}
					echo  "</table>";
					echo "  </td></tr>";
					echo  "</table>";
			}
			//electronica
			$elec=DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_electronicas');
			if(!empty($elec))
			{
					echo "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES INTERNAS</td></tr>";
					echo  "		<tr class=\"modulo_list_oscuro\"><td>";
					echo "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					echo "  <tr class=\"modulo_table_list_title\">";
					echo "  <td width=\"10%\">COD. AUTO.</td>";
					echo "  <td width=\"30%\">VALIDEZ</td>";
					echo "  <td>OBSERVACIONES</td>";
					echo "  </tr>";
					for($i=0; $i<sizeof($elec); $i++)
					{
							$fecha = explode(' ',$elec[$i][validez]);
							$class='';
							if(strtotime(date("Y-m-d")) > strtotime($fecha[0]))
							{   $class='label_error';   }
							echo "  <tr class=\"modulo_list_claro\">";
							echo "  <td align=\"center\" width=\"10%\">".$elec[$i][codigo_autorizacion]."</td>";
							echo "  <td align=\"center\" class=\"$class\">".$fecha[0]."</td>";
							echo "  <td>".$elec[$i][observaciones]."</td>";
							echo "  </tr>";
					}
					echo  "</table>";
					echo "  </td></tr>";
					echo  "</table>";
			}		
	/*

	list($dbconn) = GetDBconn();
	$query = "select b.observaciones, b.sw_estado, b.fecha_registro, g.nombre, f.autorizacion as sistema, h.autorizacion as escrita, i.autorizacion as tele, j.autorizacion as elec, k.autorizacion as bd
						from  autorizaciones as b left join autorizaciones_por_sistema as f on(b.autorizacion=f.autorizacion)
						left join autorizaciones_escritas as h on(b.autorizacion=h.autorizacion)
						left join autorizaciones_telefonicas as i on(b.autorizacion=i.autorizacion)
						left join autorizaciones_electronicas as j on(b.autorizacion=j.autorizacion)
						left join autorizaciones_bd as k on(b.autorizacion=k.autorizacion),
						system_usuarios as g
						where (b.autorizacion=$int or
						b.autorizacion=$ext ) and b.usuario_id=g.usuario_id";
	$result = $dbconn->Execute($query);
	$var=$result->GetRowAssoc($ToUpper = false);

	echo "<br><br>";
	echo ThemeAbrirTabla('DATOS AUTORIZACION No.'.$int);
	echo "	<table width=\"100%\" cellspacing=\"3\" border=\"0\" cellpadding=\"3\" align=\"center\" class=\"normal_10\">";
	echo "		<tr>";
	if(!empty($var[sistema])){   $nom='Por Sistema'; }
	if(!empty($var[escrita])){   $nom1='Escrita';     }
	if(!empty($var[tele])){      $nom2='Telefonica';  }
	if(!empty($var[elec])){      $nom3='Electronica'; }
	if(!empty($var[bdc])){       $nom4='Base Datos';  }
	echo "			<td>TIPO AUTORIZACION: </td>";
	echo "			<td class=\"label\">";
	if($nom)
	{  echo "$nom <br>";   }
	if($nom1)
	{  echo "$nom1 <br>";  }
	if($nom2)
	{  echo "$nom2 <br>";  }
	if($nom3)
	{  echo "$nom3 <br>";  }
	if($nom4)
	{  echo "$nom4 <br>";  }
	echo "			</td>";
	echo "			</tr>";
	echo "		<tr>";
	if($var[sw_estado]==1){ $x='NO AUTORIZADO'; }
	else{ $x='AUTORIZADO'; }
	echo "			<td>ESTADO: </td><td class=\"label\">".$x."</td>";
	echo "			</tr>";
	echo "		<tr>";
	echo "			<td>AUTORIZADOR: </td><td class=\"label\">".$var[nombre]."</td>";
	echo "			</tr>";
	echo "		<tr>";
	echo "			<td>FECHA: </td><td class=\"label\">".$var[fecha_registro]."</td>";
	echo "			</tr>";
	echo "		<tr>";
	echo "			<td>OBSERVACIONES: </td><td class=\"label\">".$var[observaciones]."</td>";
	echo "			</tr>";
	echo "</table>";
	echo ThemeCerrarTabla();*/
	print(ReturnFooter());
?>

