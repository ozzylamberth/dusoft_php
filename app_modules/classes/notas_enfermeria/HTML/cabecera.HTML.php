<?php

function NE_Cabecera($datos_fecha,$url,$datos_paciente)
{
	$salida="";
	$salida.="	<div align='center'>\n";
	$salida.="	<table border='0' align='center' width='100%'>\n";
	$salida.="		<tr>\n";
	$salida.="			<td width='100%' class='Titulo1' align='center'><br><br>NOTAS DE ENFERMERIA<br></td>\n";
	$salida.="		</tr>\n";
	$salida.="		<tr>\n";
	$salida.="			<td align='center'>\n";
	$salida.= "				<select class='select' name='DiasNotasEnfermeria' onchange=\"CargarPagina('$url',this.options[selectedIndex].value);\">\n";

	foreach($datos_fecha as $key => $value){
		if ($_REQUEST[$pfj."select_fecha"]==$value['fechas']){
			if ($value['fechas']==date("Y-m-d")){
				$salida.= "		<option value='".$value['fechas']."' selected>Hoy</option>\n";
			}
			elseif ($value['fechas']==date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")))){
				$salida.= "		<option value='".$value['fechas']."' selected>Ayer</option>\n";
			}
			else{
				$salida.= "		<option value='".$value['fechas']."' selected>".$value['fechas']."</option>\n";
			}
		}
		else{
			if ($value['fechas']==date("Y-m-d")){
				$salida.= "		<option value='".$value['fechas']."'>Hoy</option>\n";
			}
			elseif ($value['fechas']==date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")))){
				$salida.= "		<option value='".$value['fechas']."'>Ayer</option>\n";
			}
			else{
				$salida.= "		<option value='".$value['fechas']."'>".$value['fechas']."</option>\n";
			}
		}//End else
	}//End foreach

	$salida.= "				</select>\n";
	$salida.="			</td>\n";
	$salida.="		</tr>\n";
	$salida.="		<tr>\n";
	$salida.="			<td width='100%' align='center'><br>\n";
	$salida.="				<table border=\"0\" width='100%' class=\"modulo_table_list\">\n";
	$salida.="					<tr class='modulo_table_list_title'>\n";
	$salida.="						<td width='70%'>PACIENTE</td>\n";
	$salida.="						<td width='10%'>INGRESO</td>\n";
	$salida.="						<td width='10%'>PIEZA</td>\n";
	$salida.="						<td width='10%'>CAMA</td>\n";
	$salida.="					</tr>\n";
	$salida.="					<tr class='label'>\n";
	$salida.="						<td width='70%'>".$datos_paciente['primer_nombre']." ".$datos_paciente['segundo_nombre']." ".$datos_paciente['primer_apellido']." ".$datos_paciente['segundo_apellido']."</td>\n";
	$salida.="						<td width='10%' align='center'>".$datos_paciente['ingreso']."</td>\n";
	$salida.="						<td width='10%' align='center'>".$datos_paciente['pieza']."</td>\n";
	$salida.="						<td width='10%' align='center'>".$datos_paciente['cama']."</td>\n";
	$salida.="					</tr>\n";
	$salida.="				</table>\n";
	$salida.="			</td>\n";
	$salida.="		</tr>\n";
	$salida.="	</table>\n</div><br><br>\n";
	return $salida;
}

function NE_CabeceraControl($TituloControl,$Fecha,$DatosUsuario)
{
	$salida="";
	$salida .= "	<div align='center'>";
  $salida .= "	<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
	$salida .= "		<tr class='table_title_notas_enfermeria'>\n";
	$salida .= "			<td width='20%'>$Fecha</td>";
	$salida .= "			<td width='50%'>$TituloControl</td>";
	$salida .= "			<td width='30%'>".$DatosUsuario['nombre']." (".$DatosUsuario['usuario'].")</td>";
	$salida .= "		</tr>\n";
	$salida .= "	</table></div>\n";
  return $salida;
}
?>
