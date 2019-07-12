<?php
	$_ROOT='../../';
	$VISTA='HTML';
	include $_ROOT.'includes/enviroment.inc.php';
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	$limit=20;
	$offset=$_REQUEST['Of'];
	IncludeFile($fileName);
	print(ReturnHeader('DATOS DE OCUPACIONES'));
	print(ReturnBody());
	echo "<script>\n";
	echo "function funcioncerrar(ocupacion_id,descripcion)\n";
	echo "{\n";
	//echo "alert('".$_REQUEST['forma']."');\n";
	//echo "alert(descripcion);\n";
	echo "window.opener.document.".$_REQUEST['forma'].".ocupacion_id".$_REQUEST['prefijo'].".value=ocupacion_id;\n";
	echo "window.opener.document.".$_REQUEST['forma'].".descripcion_ocupacion".$_REQUEST['prefijo'].".value=descripcion;\n";
	echo "close();";
	echo "}\n";
	echo "</script>\n";
	echo ThemeAbrirTabla('BUSQUEDA DE OCUPACIONES');
	$_REQUEST['desOcupa']=strtoupper($_REQUEST['desOcupa']);
	echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
	echo "<form name=\"formabuscar\" action=\"\" method=\"post\">";
	echo "<tr>";
	echo "<td class=\"label\">";
	echo "OCUPACIÓN: ";
	echo "</td>";
	echo "<td>";
	echo "<input type=\"text\" class=\"input-text\" name=\"desOcupa\" size=\"60\" value=\"".$_REQUEST['desOcupa']."\">";
	echo "</td>";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\">";
	echo "</td>";
	echo "</form>";
	echo "</tr>";
	echo "</table>";
	$sql=1;
	list($dbconn) = GetDBconn();
	if(!($_REQUEST['desOcupa']===''))
	{
		$sql1="select count(*) from ocupaciones where upper(ocupacion_descripcion) LIKE '%".$_REQUEST['desOcupa']."%';";
		$result = $dbconn->Execute($sql1);
		$conteo=$result->fields[0];
		$result->close();
		if(!empty($offset))
		{
			$offset='0';
		}
		if(!empty($_REQUEST['Buscar']))
		{
			$offset='0';
		}
		$sql="select ocupacion_id, ocupacion_descripcion from ocupaciones where upper(ocupacion_descripcion) LIKE '%".$_REQUEST['desOcupa']."%' order by indice_de_orden LIMIT $limit OFFSET $offset;";
	}
	else
	{
		if(!empty($_REQUEST['Buscar']))
		{
			$sql1="select count(*) from ocupaciones;";
			$result = $dbconn->Execute($sql1);
			$conteo=$result->fields[0];
			$result->close();
			if(empty($offset))
			{
				$offset='0';
			}
			if(!empty($_REQUEST['Buscar']))
			{
				$offset='0';
			}
			$sql="select ocupacion_id, ocupacion_descripcion from ocupaciones order by indice_de_orden LIMIT $limit OFFSET $offset;";
		}
		if(!is_null($_REQUEST['Of']))
		{
			$sql1="select count(*) from ocupaciones;";
			$result = $dbconn->Execute($sql1);
			$conteo=$result->fields[0];
			$result->close();
			if(empty($offset))
			{
				$offset='0';
			}
			if(!empty($_REQUEST['Buscar']))
			{
				$offset='0';
			}
			$sql="select ocupacion_id, ocupacion_descripcion from ocupaciones order by indice_de_orden LIMIT $limit OFFSET $offset;";
		}
	}
	if($sql!=1)
	{
		$result = $dbconn->Execute($sql);
		$i=false;
		while(!$result->EOF)
		{
			$ocupacion[$result->fields[0]]=$result->GetRowAssoc(false);
			$i=true;
			$result->MoveNext();
		}
		$result->close();
		echo "<br>";
		if($i==true)
		{
			echo "<form name=\"formabuscar\" action=\"\" method=\"post\">";
			echo "<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			echo "<tr class=\"modulo_table_list_title\">";
			echo "<td width=\"90%\">";
			echo "DESCRIPCIÓN";
			echo "</td>";
			echo "<td>";
			echo "ACCIÓN";
			echo "</td>";
			echo "</tr>";
			foreach($ocupacion as $k=>$v)
			{
				if($spy==0)
				{
					echo "				       <tr class=\"modulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					echo "				       <tr class=\"modulo_list_claro\">";
					$spy=0;
				}
				echo "<td>";
				echo $v[ocupacion_descripcion];
				echo "</td>";
				echo "<td>";
				echo "<input type=\"radio\" name=\"ocupacion_id\" value=\"".$v[ocupacion_id].",".$v[ocupacion_descripcion]."\" onclick=\"funcioncerrar('".$v['ocupacion_id']."','".$v['ocupacion_descripcion']."')\">";
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</form>";
			RetornarBarra($conteo,$_REQUEST['desOcupa']);
		}
		else
		{
			echo "<table border=\"0\" width=\"100%\" align=\"center\">";
			echo "<tr align=\"center\">";
			echo "<td width=\"90%\">";
			echo "<label class=\"label_error\">NO EXISTE NINGUNA OCUPACIÓN: ".$_REQUEST['desOcupa']."</label>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
		}
	 }
	echo ThemeCerrarTabla();
	print(ReturnFooter());



	function CalcularNumeroPasos($conteo){
		GLOBAL $limit;
		$numpaso=ceil($conteo/$limit);
		return $numpaso;
	}



	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}



	function CalcularOffset($paso){
		GLOBAL $limit;
		$offset=($paso*$limit)-$limit;
		return $offset;
	}



function RetornarBarra($conteo,$busca){
		GLOBAL $limit;
    if($limit>=$conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
      $paso=1;
		}
		$accion="ocupaciones.php?conteo=$conteo&desOcupa=$busca&forma=".$_REQUEST['forma']."&prefijo=".$_REQUEST['prefijo'];
		$barra=CalcularBarra($paso);
		$numpasos=CalcularNumeroPasos($conteo);
		$colspan=1;

		$salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
    //  $salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<=0)
			{$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
				$colspan++;
			}else{
       // $salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
       // $salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		//$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\" colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
    	if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		echo $salida;
	}
	?>
