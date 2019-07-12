<?
		/*
		*		CrearTurnos
		*
		*		@Author Arley Velásquez
		*		@access Private
		*/
	//$this->CrearTurnos(fecha,dia[boolean],horario[boolean],hora_turno[integer],rango[integer],rangoTurno[integer]);
		function CrearTurnos($url,$fecha='',$dia=false,$horario=false,$hora_turno,$rango,$turno,$horas='',$resaltar_turno=false,$turnos_prgmar='',$hora_inicio_turno)
		{
			$cadena="";
			$show_horario="";

			$cadena .= "	<script>\n";
			$cadena .= "	function buscaCampos(campo,forma) {\n";
			$cadena .= "		var i=0; var j=0;";
			$cadena .= "		while (!i) { if (forma.elements[j].name!=campo) j++; else return(j); } \n";
			$cadena .= "		return (-1);\n";
			$cadena .= "	}\n\n";

			$cadena .= "	function CargarPagina(href,valor) {\n";
			$cadena .= "		var url=href;\n";
			$cadena .= "		location.href=url+'&rango='+valor;\n";
			$cadena .= "	}\n\n";

			$cadena .= "	function SeleccionarTodo(forma) {\n";
			$cadena .= "		var nombre_check='hora[]';\n";
			$cadena .= "		var pos=buscaCampos('hora[]',forma);\n";
			$cadena .= "		var horas_horario=0;\n";
			$cadena .= "		var val_checked=forma.CheckTodos.checked;\n";
			if (!empty($hora_turno)){
				$cadena .= "		horas_horario=forma.horario.options[forma.horario.selectedIndex].value;\n";
			}
			$cadena .= "		switch (horas_horario) {\n";
			$cadena .= "			case '1' : \n";
			$cadena .= "							for (i=pos;i<parseInt(".$turno.",10);i++) {\n";
			$cadena .= "								forma.elements[i].checked=val_checked;\n\n";
			$cadena .= "							}\n";
			$cadena .= "			case '15' : \n";
			$cadena .= "							for (i=pos;i<parseInt(".($turno*4).",10)+pos;i++) {\n";
			$cadena .= "								forma.elements[i].checked=val_checked;\n\n";
			$cadena .= "							}\n";
			$cadena .= "			case '30' : \n";
			$cadena .= "							for (i=pos;i<parseInt(".($turno*2).",10)+pos;i++) {\n";
			$cadena .= "								forma.elements[i].checked=val_checked;\n\n";
			$cadena .= "							}\n";
			$cadena .= "			default : \n";
			$cadena .= "							for (i=pos;i<parseInt(".$turno.",10)+pos;i++) {\n";
			$cadena .= "								forma.elements[i].checked=val_checked;\n\n";
			$cadena .= "							}\n";
			$cadena .= "		}\n\n";
			$cadena .= "	}\n\n";

			$cadena .= "	</script>\n\n";

			if ($horario) {
				$show_horario .= "<select name='horario' class='select' onchange=\"CargarPagina('$url',this.options[selectedIndex].value);\">\n";
				if ($rango==15)
					$show_horario .= "	<option value='15' selected>15 Minutos</option>\n";
				else
					$show_horario .= "	<option value='15'>15 Minutos</option>\n";
				if ($rango==30)
					$show_horario .= "	<option value='30' selected>30 Minutos</option>\n";
				else
					$show_horario .= "	<option value='30'>30 Minutos</option>\n";
				if ($rango==1 || empty($rango))
					$show_horario .= "	<option value='1' selected>1 Hora</option>\n";
				else
					$show_horario .= "	<option value='1'>1 Hora</option>\n";
				$show_horario .= "</select>\n";
				$show_horario .= "\n";
			}

			$cadena .= "<div align='center' valing='middle'>".$show_horario."&nbsp;&nbsp;Seleccionar Todos<input type='checkbox' name='CheckTodos' value='0' onClick='SeleccionarTodo(this.form);'></div>\n";
			$cadena .= "	<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" border=\"1\">";
			$cadena .= SetStyle("MensajeError",2);
			$cadena .= "		<tr class=\"modulo_table_title\">\n";
			$cadena .= "			<td>HORA DEL DÍA</td>\n";
			$cadena .= "			<td>SELECCIONAR</td>\n";
			$cadena .= "		</tr>\n";

			$i=0;

			list($h,$m,$s)=explode(":",$hora_turno);
			list($h_a,$m_a,$s_a)=explode(":",$hora_inicio_turno);
			$dif_horas=date("G:i:s",mktime(($h_a-$h),($m_a-$m),($s_a-$s),$mes,$dia,$anno));
			list($h_d,$m_d,$s_d)=explode(":",$dif_horas);
			$nuevo_rango=$h_d;

			if (empty($nuevo_rango) || empty($hora_turno)){
				$nuevo_rango=$turno;
			}

			unset($h);
			unset($m);
			unset($s);
			unset($anno);
			unset($mes);
			unset($dia);

			for ($j=0;$j<$nuevo_rango;$j++){
				list($anno, $mes, $dia)=explode("-",$fecha);
				if ($i==23){
					list($h,$m,$s)=explode(":",$hora_turno);
					$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
					if ($dia) {
						$cadena .= "		<tr ".Lista($j).">\n";
						$cadena .= "			<td height='10' colspan='2' class='bckground'>MAÑANA</td>\n";
						$cadena .= "		</tr>\n";
					}
					$fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
					$fecha=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
				}
				else{
					list($h,$m,$s)=explode(":",$hora_turno);
					$i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
					$fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
					$fecha=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
					if ($hora_turno==$i && $dia) {
						$cadena .= "		<tr ".Lista($j).">\n";
						$cadena .= "			<td height='10' colspan='2' class='bckground'>HOY</td>\n";
						$cadena .= "		</tr>\n";
					}
				}

				$cadena .= "		<tr ".Lista($j).">\n";
				if (in_array($i,$horas) && $resaltar_turno) {
					$cadena .= "			<td class='resalte' align='center'>&nbsp;&nbsp;".$i.":00</td>\n";
					$cadena .= "			<td class='resalte' align='center'><input type='checkbox' name='hora[]' value='".$fecha2."' checked></td>\n";
				}
				elseif (in_array($i,$horas) || in_array($i.":00:00",$turnos_prgmar)) {
					$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":00</td>\n";
					$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."' checked></td>\n";
				}
				else{
					$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":00</td>\n";
					$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."'></td>\n";
				}
				$cadena .= "		</tr>\n";

				if ($rango==15) {
					$fecha2=date("Y-m-d H:i:s",mktime($i,15,0,$mes,$dia,$anno));
					$cadena .= "		<tr ".Lista($i).">\n";
					if (in_array($i.":15:00",$turnos_prgmar)) {
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":15</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."' checked></td>\n";
					}
					else{
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":15</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."'></td>\n";
					}
					$cadena .= "		</tr>\n";
					$fecha2=date("Y-m-d H:i:s",mktime($i,30,0,$mes,$dia,$anno));
					$cadena .= "		<tr ".Lista($i).">\n";
					if (in_array($i.":30:00",$turnos_prgmar)) {
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":30</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."' checked></td>\n";
					}
					else{
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":30</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."'></td>\n";
					}
					$cadena .= "		</tr>\n";
					$fecha2=date("Y-m-d H:i:s",mktime($i,45,0,$mes,$dia,$anno));
					$cadena .= "		<tr ".Lista($i).">\n";
					if (in_array($i.":45:00",$turnos_prgmar)) {
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":45</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."' checked></td>\n";
					}
					else{
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":45</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."'></td>\n";
					}
					$cadena .= "		</tr>\n";
				}
				if ($rango==30) {
					$fecha2=date("Y-m-d H:i:s",mktime($i,30,0,$mes,$dia,$anno));
					$cadena .= "		<tr ".Lista($i).">\n";
					if (in_array($i.":30:00",$turnos_prgmar)) {
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":30</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."' checked></td>\n";
					}
					else{
						$cadena .= "			<td align='center'>&nbsp;&nbsp;".$i.":30</td>\n";
						$cadena .= "			<td align='center'><input type='checkbox' name='hora[]' value='".$fecha2."'></td>\n";
					}
					$cadena .= "		</tr>\n";
				}
			}
			$cadena .= "	</table>\n";
			return $cadena;
		}

		/*
		*		function Lista($numero)
		*		$numero es el numero para imprimir la clase de la lista, si el numero es par imprime la clase list_claro
		*		de lo contrario imprime list_oscuro
		*		retorna la cadena con la clase a utilizar
		*
		*		@Author Arley Velásquez
		*		@access Private
		*/
		function Lista($numero)
		{
			if ($numero%2)
				return ("class='hc_list_oscuro'");
			return ("class='hc_list_claro'");
		}//End function

	/**
	*		function SetStyle => Muestra mensajes
	*
	*		crea una fila para poner el mensaje de "Faltan campos por llenar" cambiando a color rojo
	*		el label del campo "obligatorio" sin llenar
	*
	*		@Author Alexander Giraldo
	*		@access Private
	*		@return string
	*		@param string => nombre del input y estilo que quedó vacio
	*		@param integer => colspan del requerido
	*/
	function SetStyle($campo,$colum)//CHANGE
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
							return ("<tr><td class='label_error' colspan='$colum' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}
?>
