<?php

	function GetRevisionSistemaInico($datos_sistemas,$datos_paciente,$url,$url_origen)
	{
		$ulr_href="";
		$salida="";
   	$salida.= ThemeAbrirTabla('REVISION DE SISTEMAS');
		$salida.="	<div align='center'>\n";
		$salida.="		<table border=\"0\" width='100%' class=\"modulo_table_list\">\n";
		$salida.="			<tr class='modulo_table_list_title'>\n";
		$salida.="				<td width='70%'>PACIENTE</td>\n";
		$salida.="				<td width='10%'>INGRESO</td>\n";
		$salida.="				<td width='10%'>PIEZA</td>\n";
		$salida.="				<td width='10%'>CAMA</td>\n";
		$salida.="			</tr>\n";
		$salida.="			<tr class='modulo_list_oscuro'>\n";
		$salida.="				<td width='70%'>".$datos_paciente['primer_nombre']." ".$datos_paciente['segundo_nombre']." ".$datos_paciente['primer_apellido']." ".$datos_paciente['segundo_apellido']."</td>\n";
		$salida.="				<td width='10%' align='center'>".$datos_paciente['ingreso']."</td>\n";
		$salida.="				<td width='10%' align='center'>".$datos_paciente['pieza']."</td>\n";
		$salida.="				<td width='10%' align='center'>".$datos_paciente['cama']."</td>\n";
		$salida.="			</tr>\n";
		$salida.="		</table><br><br><br>\n";
		$salida.="		<table border=\"0\" width='100%' class=\"modulo_table_list\">\n";
		$salida.="			<tr>\n";
		$salida.="				<td width='100%' class='modulo_table_title'>SISTEMAS</td>\n";
		$salida.="			</tr>\n";

		foreach($datos_sistemas as $key => $value){
			$ulr_href.="&vector_sistema[$key]=".$value['hc_ne_sistema_id'];
		}

		foreach($datos_sistemas as $key => $value){
			$href = $url.$ulr_href."&sistema=".$value['hc_ne_sistema_id']."&sistema_n=".$value['hc_ne_sistema_id']."&inicio_sistemas=1";
			$salida.="			<tr ".Lista($key).">\n";
			$salida.="				<td width='100%'><a href=\"$href\">".strtoupper($value['descripcion'])."</a></td>\n";
			$salida.="			</tr>\n";
		}
		$salida.="		</table>\n";
		$salida.="	</div>\n";
		$salida .= "<div align='center' class=\"normal_10\"><br><a href=\"$url_origen\">Volver Listado de Pacientes</a><br></div>";
		$salida.= ThemeCerrarTabla();
		return $salida;
	}


	/*
	* function Lista($numero)
	* $numero es el numero para imprimir la clase de la lista, si el numero es par imprime la clase list_claro
	* de lo contrario imprime list_oscuro
	* retorna la cadena con la clase a utilizar
	*/
	function Lista($numero)
	{
		if ($numero%2)
			return ("class=\"modulo_list_oscuro\"");
		return ("class=\"modulo_list_claro\"");
	}//End function


	function GetRevisionSistemas($datos_sistemas,$datos_paciente,$vector_sistemas,$sistema,$sistema_n,$url,$url_origen)
	{
		$ulr_href="";
		$href="";

		$salida="";
		$salida.= ThemeAbrirTabla("REVISION DE SISTEMAS - [ ".strtoupper($datos_sistemas[0]['sistema'])." ]");
		$salida.="<form name='Frm_Revision_Sistemas' action='' method='POST'>";
		$salida.="<script>\n";
		$salida.="function ir(url){\n";
		$salida.="	document.Frm_Revision_Sistemas.action=url;\n";
		$salida.="}\n";
		$salida.="</script>\n";
		$salida.="	<div align='center'>\n";
		$salida.="		<table border=\"0\" width='100%' class=\"modulo_table_list\">\n";
		$salida.="			<tr class='modulo_table_list_title'>\n";
		$salida.="				<td width='70%'>PACIENTE</td>\n";
		$salida.="				<td width='10%'>INGRESO</td>\n";
		$salida.="				<td width='10%'>PIEZA</td>\n";
		$salida.="				<td width='10%'>CAMA</td>\n";
		$salida.="			</tr>\n";
		$salida.="			<tr class='modulo_list_oscuro'>\n";
		$salida.="				<td width='70%'>".$datos_paciente['primer_nombre']." ".$datos_paciente['segundo_nombre']." ".$datos_paciente['primer_apellido']." ".$datos_paciente['segundo_apellido']."</td>\n";
		$salida.="				<td width='10%' align='center'>".$datos_paciente['ingreso']."</td>\n";
		$salida.="				<td width='10%' align='center'>".$datos_paciente['pieza']."</td>\n";
		$salida.="				<td width='10%' align='center'>".$datos_paciente['cama']."</td>\n";
		$salida.="			</tr>\n";
		$salida.="		</table><br><br><br>\n";

		foreach($datos_sistemas as $key => $value){
			$categoria[$value['categoria']][]=array(0=>$value['opcion'],1=>$value['sw_complemento'],2=>$value['hc_ne_detalle_id']);
		}

		foreach($categoria as $key => $value){
			$salida.="		<table width='100%' border=\"0\" class=\"modulo_table\">\n";
			$salida.="			<tr class='modulo_table_title'>\n";
			$salida.="				<td width='100%' colspan='3'>".strtoupper($key)."</td>\n";
			$salida.="			</tr>\n";
			foreach($value as $k1 => $valor){
				$salida.="			<tr>\n";
				if ($_SESSION['REVISION_SISTEMAS'][$datos_paciente['ingreso']][$sistema][$valor[2]]===$valor[0]){
					$salida.="				<td width='3%' align='center'><input type='checkbox' name='opc_seleccion[]' value='".$valor[2]."' checked></td>\n";
				}
				else{
					$salida.="				<td width='3%' align='center'><input type='checkbox' name='opc_seleccion[]' value='".$valor[2]."'></td>\n";
				}
				if ($valor[1]){
					$salida.="				<td width='52%' align='justify'>".$valor[0]."</td>\n";
					if(!empty($_SESSION['REVISION_SISTEMAS_TXT'][$datos_paciente['ingreso']][$sistema][$valor[2]])){
						$salida.="				<td width='45%' align='justify'><textarea style=width:100% class='textarea' name='opc_complemento[".$valor[2]."]' cols='40' rows='4'>".$_SESSION['REVISION_SISTEMAS_TXT'][$datos_paciente['ingreso']][$sistema][$valor[2]]."</textarea></td>\n";
					}
					else{
						$salida.="				<td width='45%' align='justify'><textarea style=width:100% class='textarea' name='opc_complemento[".$valor[2]."]' cols='40' rows='4'></textarea></td>\n";
					}
				}
				else{
					$salida.="				<td width='62%' align='justify' colspan='2'>".$valor[0]."</td>\n";
				}
				$salida.="			</tr>\n";
			}
			$salida.="		</table><br><br>\n";
		}

		foreach($vector_sistemas as $key => $value){
			$ulr_href.="&vector_sistema[$key]=".$value['hc_ne_sistema_id'];
		}

		list($resultado)=array_keys($vector_sistemas,$sistema_n);
		$salida.="	<br>";
		$salida.="		<div>";
		$salida.="			<table width='20%' border='0' class='label'>\n";
		$salida.="				<tr valign='middle'>\n";
		if (!$resultado && !empty($vector_sistemas[$resultado+1])){
			$salida.="					<td width='40%' align='center'><a href=\"$url\">INICIO</a></td>\n";
			$href="&resumen=1&sistema=$sistema";
			$salida.="					<td width='40%' align='center'><input type='image' src='".GetThemePath()."/images/resumen.gif' width='25' heigth='25' alt='Resumen Sistemas' onClick=\"ir('$url$href');\"></td>\n";
			$href=$ulr_href."&sistema=$sistema&sistema_n=".$vector_sistemas[$resultado+1];
//echo $url.$href;
			$salida.="					<td width='20%' align='justify'>&nbsp;<input type='image' src='".GetThemePath()."/images/flecha_der.gif' onClick=\"ir('$url$href');\"></td>\n";
		}
		elseif ($resultado==(sizeof($vector_sistemas)-1) && !empty($vector_sistemas[$resultado-1])){
			$href=$ulr_href."&sistema=$sistema&sistema_n=".$vector_sistemas[$resultado-1];
			$salida.="					<td width='20%' align='justify'><input type='image' src='".GetThemePath()."/images/flecha_izq.gif' onClick=\"ir('$url$href');\"></td>\n";
			$salida.="					<td width='40%' align='center'>&nbsp;<a href=\"$url\">INICIO</a></td>\n";
			$href="&resumen=1&sistema=$sistema";
			$salida.="					<td width='40%' align='center'><input type='image' src='".GetThemePath()."/images/resumen.gif' width='25' heigth='25' alt='Resumen Sistemas' onClick=\"ir('$url$href');\"></td>\n";
		}
		elseif ($resultado==(sizeof($vector_sistemas)-1) && sizeof($vector_sistemas)==1){
			$href="&resumen=1&sistema=$sistema";
			$salida.="					<td width='100%' align='center'><input type='image' src='".GetThemePath()."/images/resumen.gif' width='25' heigth='25' alt='Resumen Sistemas' onClick=\"ir('$url$href');\"></td>\n";
		}
		else{
			$href=$ulr_href."&sistema=$sistema&sistema_n=".$vector_sistemas[$resultado-1];
			$salida.="					<td width='20%' align='justify'><input type='image' src='".GetThemePath()."/images/flecha_izq.gif' onClick=\"ir('$url$href');\"></td>\n";
			$salida.="					<td width='30%' align='center'>&nbsp;<a href=\"$url\">INICIO</a></td>\n";
			$href="&resumen=1&sistema=$sistema";
			$salida.="					<td width='30%' align='center'><input type='image' src='".GetThemePath()."/images/resumen.gif' width='25' heigth='25' alt='Resumen Sistemas' onClick=\"ir('$url$href');\"></td>\n";
			$href=$ulr_href."&sistema=$sistema&sistema_n=".$vector_sistemas[$resultado+1];
			$salida.="					<td width='20%' align='justify'>&nbsp;<input type='image' src='".GetThemePath()."/images/flecha_der.gif' onClick=\"ir('$url$href');\"></td>\n";
		}
		$salida.="				</tr>\n";
		$salida.="			</table>\n";
		$salida.="		</div>";
		$salida.="	</div>\n";

		$salida.= "<div align='center' class=\"normal_10\"><br><a href=\"$url_origen\">Volver Listado de Pacientes</a><br></div>";
		$salida.= "</form>";
		$salida.= ThemeCerrarTabla();

		return $salida;
	}


	function SetResumenSistemas($ingreso,$info_sistema,$info_usuario,$categoria)
	{

		$salida="";
		if (!empty($_SESSION['REVISION_SISTEMAS'][$ingreso])){
			$salida.="<div align='center'>\n";
			foreach($_SESSION['REVISION_SISTEMAS'][$ingreso] as $key => $value){
				$salida.="		<table width='100%' border=\"1\">\n";
				$salida.="			<tr class=\"modulo_table_title\" valign='middle'>\n";
				$salida.="				<td width='100%' align='center' colspan='3'><font size='2'><b>REVISION DE SISTEMAS - [".$info_sistema[$key]['descripcion']."]</b></font></td>";
				$salida.="			</tr>\n";
				$salida.="			<tr>\n";
				$salida.="				<td width='25%' class=\"modulo_list_oscuro\" align='center'><font size='2'><b>CATEGORIA</b></font></td>";
				$salida.="				<td width='40%' class=\"modulo_list_oscuro\" align='center'><font size='2'><b>OPCIÓN</b></font></td>";
				$salida.="				<td width='35%' class=\"modulo_list_oscuro\" align='center'><font size='2'><b>COMPLEMENTO</b></font></td>";
				$salida.="			</tr>\n";
				foreach($categoria[$key] as $k1 => $valor){
					$cont=0;
					$salida.="			<tr class=\"modulo_list_claro\">\n";
					$salida.="				<td align='center' rowspan='".sizeof($valor)."'><font size='2'><b>".$k1."</b></font></td>\n";
					foreach($valor as $k2 => $valor2){
						if ($cont){
							$salida.="			<tr class=\"modulo_list_claro\" valign='middle'>\n";
						}
						if (!empty($_SESSION['REVISION_SISTEMAS_TXT'][$ingreso][$key][$k2])){
							$salida.="				<td width='40%'><font size='1'>".$value[$k2]."</font></td>\n";
							$salida.="				<td width='35%'><font size='1'>".$_SESSION['REVISION_SISTEMAS_TXT'][$ingreso][$key][$k2]."</font></td>\n";
						}
						else{
							$salida.="				<td width='75%' colspan='2'><font size='1'>".$value[$k2]."</font></td>\n";
						}
						$salida.="			</tr>\n";
						$cont++;
					}
					$salida.="			</tr>\n";
				}
				$salida.="			</table><br><br>\n";
			}
			$salida.="</div>";

		}
		$_SESSION['RXS']['TABLA_MUESTRA']=$salida;
  	$_SESSION['RXS']['TABLA']=str_replace("class=\"modulo_list_claro\"","",$salida);//esta variable recibe los datos de las notas de enfermeria.
		$_SESSION['RXS']['TABLA']=str_replace("class=\"modulo_list_oscuro\"","",$_SESSION['RXS']['TABLA']);//esta variable recibe los datos de las notas de enfermeria.
		$_SESSION['RXS']['TABLA']=str_replace("class=\"modulo_table_title\"","",$_SESSION['RXS']['TABLA']);//esta variable recibe los datos de las notas de enfermeria.

//	echo "tablaMuestra->".$_SESSION['RXS']['TABLA_MUESTRA']."<br>";
		//echo "tablaInser->".$_SESSION['RXS']['TABLA']."<br>";
//exit;
		//return $salida;

	}

	function Cerrar($url,$url_origen,$error=false)
	{
		$salida="";
		unset($_SESSION['RXS']['TABLA_MUESTRA']);//tabla que se muestra cuando se inserta la nota.
		unset($_SESSION['RXS']['TABLA']);//tabla que se muestra en el resumen de la historia clinica.
		$salida.= ThemeAbrirTabla("REVISION DE SISTEMAS");
		$salida.="<table width='100%' border='0' class='label'>\n";
		if ($error){
			$salida.="	<tr>\n";
			$salida.="		<td width='100%' align='center' valign='middle'><br><br><br><br>LA REVISION DE SISTEMAS NO TIENE DATOS PARA INSERTAR.<br><br><br><br></td>";
			$salida.="	</tr>\n";
			$salida.="	<tr>\n";
			$salida.="		<td width='100%' align='center'>\n";
			$salida.= "			<div align='center'><br><a href=\"$url\">Inicio Revisión de Sistemas</a><br><br><a href=\"$url_origen\">Volver Listado de Pacientes</a><br></div>";
			$salida.= "		</td>";
			$salida.="	</tr>\n";
		}
		else{
			$salida.="	<tr>\n";
			$salida.="		<td width='100%' align='center'>LA REVISION DE SISTEMAS SE HA INSERTADO CORRECTAMENTE.</td>";
			$salida.="	</tr>\n";
			$salida.="	<tr>\n";
			$salida.="		<td width='100%' align='center'>\n";
			$salida.= "			<div align='center'><br><a href=\"$url_origen\">Volver Listado de Pacientes</a><br></div>";
			$salida.= "		</td>";
			$salida.="	</tr>\n";
		}
		$salida.= "</table>\n";
		$salida.= ThemeCerrarTabla();

		return $salida;
	}

	function ConfirmarCerrar($url)
	{
	//echo $url;
		$salida="";
		$salida.= ThemeAbrirTabla("REVISION DE SISTEMAS - [ Confirmación de Sistemas ]");

		$_SESSION['RXS']['TABLA_MUESTRA']=str_replace("border=\"1\"","border=\"0\"",$_SESSION['RXS']['TABLA_MUESTRA']);//esta variable recibe los datos de las notas de enfermeria.
		$salida.=$_SESSION['RXS']['TABLA_MUESTRA'];

		$salida.="<table width='100%' border='0' class='label'>\n";
		$salida.="	<tr>\n";
		$salida.="		<td class='label_mark' width='100%' align='center' valign='middle'><br><br>DESEA GUARDAR EL RESUMEN ANTERIOR ?.<br><br></td>";
		$salida.="	</tr>\n";
		$salida.="	<tr>\n";
		$salida.="		<td width='100%' align='center'>\n";
		$salida.="			<table width='40%' border='0' class='label'>\n";
		$salida.="				<tr>\n";
		$salida.="					<td align='center' width='50%'><a href=\"$url\">CANCELAR</a></td>\n";
		$url1=$url."&resumen=1&finalizar=1";
		$salida.="					<td align='center' width='50%'><a href=\"$url1\">GUARDAR</a></td>\n";
		$salida.="				</tr>\n";
		$salida.="			</table>\n";
		$salida.= "		</td>";
		$salida.="	</tr>\n";
		$salida.= "</table>\n";
		$salida.= ThemeCerrarTabla();
		return $salida;
	}

?>
