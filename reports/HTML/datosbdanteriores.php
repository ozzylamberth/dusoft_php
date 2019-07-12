<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";

	IncludeFile($fileName);

	print(ReturnHeader('DATOS AFILIADO'));
	print(ReturnBody());

	$PacienteId=$_REQUEST['paciente'];
	$TipoId=$_REQUEST['tipoid'];
	$Plan=$_REQUEST['plan'];
	$cantidad=$_REQUEST['cantidad'];

	if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
	{
			$this->error = "Error";
			$this->mensajeDeError = "No se pudo incluir : classes/BDAfiliados/BDAfiliados.class.php";
			return false;
	}
	if(!class_exists('BDAfiliados'))
	{
			$this->error="Error";
			$this->mensajeDeError="no existe BDAfiliados";
			return false;
	}

	$class= New BDAfiliados($TipoId,$PacienteId,$Plan);
	if($class->GetDatosAfiliadoMultiple($cantidad)==false)
	{
			$this->error=$class->error;
			$this->mensajeDeError=$class->mensajeDeError;
			return false;
	}
	$a=$class->salida;
	echo ThemeAbrirTabla('DATOS PACIENTE EN BASE DE DATOS');
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
	echo "	<tr>";
	echo "	<td colspan=\"2\">";
	foreach($a as $p=>$s)
	{
		$t=ImplodeArrayAssoc($s);
		if($s=='No Esta')
		{
			echo "			      <table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			echo "				       <tr>";
			echo "				          <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">EL AFILIADO NO ESTA EN LA BASE DE DATOS DE $p</td>";
			echo "				       </tr>";
			echo "</table>";
		}
		if(!empty($t))
		{
			echo "<br>";
			echo "			      <table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			echo "				       <tr>";
			echo "				          <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD $p</td>";
			echo "				       </tr>";
			$arreglon=ExplodeArrayAssoc($t);
			$i=0;
			foreach($arreglon as $k => $v)
			{
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					echo "				 <tr class=\"$estilo\">";
					echo "				    <td align=\"center\">$k</td>";
					echo "				    <td align=\"center\">$v</td>";
					echo "			  </tr>";
					$i++;
			}
			echo "			     </table>";
		}
	}
	echo "				       </td>";
	echo "				       </tr>";
	echo "			     </table><BR>";

	echo ThemeCerrarTabla();
	print(ReturnFooter());
?>

