<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	list($dbconn) = GetDBconn();
	IncludeFile($fileName);

	print(ReturnHeader('DATOS AFILIADO'));
	print(ReturnBody());

	$PacienteId=$_REQUEST['paciente'];
	$TipoId=$_REQUEST['tipoid'];
	$Plan=$_REQUEST['plan'];
	if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
	{
			$this->error = "Error";
			$this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
			return false;
	}
	if(!class_exists('BDAfiliados'))
	{
			$this->error="Error";
			$this->mensajeDeError="no existe BDAfiliados";
			return false;
	}

	$class= New BDAfiliados($TipoId,$PacienteId,$Plan);
	if($class->GetDatosAfiliado()==false)
	{
			$this->error=$class->error;
			$this->mensajeDeError=$class->mensajeDeError;
			return false;
	}

	$x=$class->salida;
	$sql="select b.descripcion_campo, b.nombre_mostrar from informacion_bd as a, plantillas_detalles as b where a.plan_id=$Plan and a.sw_estado='1' and a.plantilla_bd_id=b.plantilla_bd_id and b.sw_mostrar='1';";
	$result=$dbconn->execute($sql);
	if ($dbconn->ErrorNo() != 0)
	{
		echo $this->error = "<label class=label_error>Error al Cargar la pantalla</label>";
		echo $this->mensajeDeError = "<label class=label_error>Error DB : " . $dbconn->ErrorMsg().'</label>';
		return false;
	}
	if($result->RecordCount()!=0)
	{
		$i=0;
		foreach($x as $p=>$h)
		{
			$t[$p]=array('pos'=>$i,'valor'=>$h,'nombrereal'=>$p);
			$i++;
		}
		while(!$result->EOF)
		{
			if(!empty($result->fields[1]))
			{
				$a=$t[$result->fields[0]];
				unset($t[$result->fields[0]]);
				if(!empty($a))
				{
					$t[$result->fields[1]]=$a;
				}
			}
			$result->MoveNext();
		}
		unset($p);
		foreach($t as $k=>$v)
		{
			$p[]=array('pos'=>$v['pos'],'valor'=>$v['valor'],'nombrereal'=>$v['nombrereal'],'nombre_mostrar'=>$k);
		}
		//print_r($t);
		unset($t);
		for($i=1;$i<sizeof($p);$i++)
		{
			for($j=0;$j<sizeof($p)-1;$j++)
			{
				if($p[$j]['pos']>$p[$j+1]['pos'])
				{
					$temp=$p[$j];
					$p[$j]=$p[$j+1];
					$p[$j+1]=$temp;
				}
			}
		}
		unset($x);
		foreach($p as $k=>$v)
		{
			$x[$v['nombre_mostrar']]=$v['valor'];
		}
	}

		function PlantilaBD($plan)
		{
				list($dbconn) = GetDBconn();
				$sql="SELECT plantilla_bd_id FROM plantillas_planes WHERE plan_id=$plan";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				if(!$result->EOF)
				{  $var=$result->fields[0];  }

				$result->Close();
				return $var;
		}


		function CamposMostrarBD($campo,$plantilla)
		{
				list($dbconn) = GetDBconn();
			 	$sql="SELECT nombre_mostrar,sw_mostrar FROM plantillas_detalles
							WHERE (descripcion_campo='$campo' OR nombre_mostrar='$campo')
							AND plantilla_bd_id=$plantilla";
				$result=$dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				$var=$result->GetRowAssoc($ToUpper = false);
				$result->Close();
				return $var;
		}

	$plantilla=PlantilaBD($Plan);

	$a=ImplodeArrayAssoc($x);
	echo ThemeAbrirTabla('DATOS PACIENTE EN BASE DE DATOS');
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
	echo "<tr>";
	echo "<td colspan=\"2\">";
	echo "<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
	echo "<tr>";
	echo "<td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
	echo "</tr>";
	$arreglon=ExplodeArrayAssoc($a);
	$i=0;
	foreach($arreglon as $k => $v)
	{
			$mostrar='';
			$mostrar=CamposMostrarBD($k,$plantilla);
			if(!empty($mostrar[sw_mostrar]))
			{
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					echo "         <tr class=\"$estilo\">";
					if(!empty($mostrar[nombre_mostrar]))
					{  $k=$mostrar[nombre_mostrar];}
					echo "            <td align=\"center\">$k</td>";
					echo "            <td align=\"center\">$v</td>";
					echo "        </tr>";
					$i++;
			}
	}
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=\"label\" colspan='2' align='center'>";
	echo "<input type='button' name='CERRAR' value='CERRAR' class='input-submit' onclick='window.close()'>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo ThemeCerrarTabla();
	print(ReturnFooter());
	function ComparararArray($a,$b)
	{
		echo 'A';print_r($a);echo '<br>';
		echo 'B';print_r($b);echo '<br>';
		if($a['pos']<=$b['pos'])
		{
			return $b;
		}
		else
		{
			return $a;
		}
	}
?>

