<?php
class ResumenEpicrisis_HTML extends ResumenEpicrisis
{

	function ResumenEpicrisis_HTML($ingreso)
	{
		$this->ResumenEpicrisis();
		$this->ingreso=$ingreso;
		return true;
	}

	/*function Cabecera()
	{
		$reg=$this->Datos_Ingreso();
		$dato=$this->GetInformacionEmpresa($this->datosEvolucion['departamento']);
		$this->datosPaciente=GetDatosPaciente("","",$this->ingreso);
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
		//$html="".$this->image('images/logocliente.png',170,9,18)."";
		$this->salida.= "<TABLE BORDER='1'>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\">";
		$this->salida.= "<TR>";
		$this->salida.= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><IMG SRC='images/logocliente.png' WIDTH=100 HEIGHT=100>";
		$this->salida.= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><FONT SIZE='20' FACE='arial'>EPICRISIS";
		$this->salida.= "</TD>";
		$this->salida.= "</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "</TABLE>";
		$this->salida.= "<TR>";
		$this->salida.= "<FONT SIZE='7' FACE='arial'>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD WIDTH='100'><UBICACION X=10 Y=27>".$dato['id']."</TD>";
		$this->salida.= "<TD WIDTH='200'>".$dato['razon_social']."</TD>";
		$this->salida.= "<TD WIDTH='200'>".$dato['direccion']."</TD>";
		$this->salida.= "<TD WIDTH='200'>".$dato['telefonos']."</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD WIDTH='430'>".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</TD>";
		$this->salida.="<TD WIDTH='170'>".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</TD>";
		if($edad['a?os']!=""){
			$edades.=$edad['a?os'].' a?os, ';
		}
		if($edad['meses']!=""){
			$edades.=$edad['meses'].' meses, ';
		}
		if($edad['dias']!=""){
			$edades.=$edad['dias'].' dias.';
		}
		$this->salida.= "<TD WIDTH='150'>$edades";
		$this->salida.= "</TD>";
		$this->salida.= "</TR>";
		$direccion .= $this->datosPaciente['residencia_direccion']." ";
		if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$direccion.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
		$this->salida.= "<TR>";
		$this->salida.="<TD WIDTH='375'>$direccion</TD>";
		$this->salida.= "<TD WIDTH='375'>".$this->datosPaciente['nombre_tercero'].' - '.$this->datosPaciente['plan_descripcion']."</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\">";
		$this->salida.= "<TR>";
		$this->salida.= "<TD COLSPAN=\"4\" ALIGN=\"CENTER\">DATOS INGRESO";
		$this->salida.= "</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">SERVICIO DE INGRESO</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">FECHA Y HORA DE INGRESO</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">SERVICIO DE EGRESO</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">FECHA Y HORA DE EGRESO</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][departamento]."</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][fecha_registro]."</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][departamento_actual]."</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][fecha_cierre]."</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "</TABLE>";
		$this->salida.= "</TABLE>";
		$this->salida;
		return true;
	}*/
	
	function Cabecera()
	{	
		$reg=$this->Datos_Ingreso();
		$dato=$this->GetInformacionEmpresa($this->datosEvolucion['departamento']);
		$this->datosPaciente=GetDatosPaciente("","",$this->ingreso);
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
		$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		$this->salida.= "<tr class=\"modulo_table_title\">";
		$this->salida.= "<td>";
		$this->salida.= "<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$this->salida.= "<tr>";
		$this->salida.= "<td colspan=\"3\" align=\"left\" width=\"35%\"><IMG SRC='images/logocliente.png' WIDTH=120 HEIGHT=110>";
		$this->salida.= "</td>";
		$this->salida.= "<td colspan=\"3\" align=\"left\" width=\"55%\"><FONT SIZE='20' FACE='arial'>EPICRISIS";
		$this->salida.= "</td>";
		$this->salida.= "<td colspan=\"3\" align=\"right\" width=\"5%\">";
		$url=ModuloGetURL($_SESSION['EPICRISIS']['RETORNO']['contenedor'],
		$_SESSION['EPICRISIS']['RETORNO']['modulo'],
		$_SESSION['EPICRISIS']['RETORNO']['tipo'],
		$_SESSION['EPICRISIS']['RETORNO']['metodo']);
		$this->salida.="<A href=\"$url\">VOLVER</A><br>";/*
		$url=ModuloGetURL('app','EJEMPLO','user','mainpdf');
		$this->salida.="<A href=\"$url\">Volver</A><br>";*/
		$this->salida.= "</td>";
		$this->salida.= "</tr>";
		
		$this->salida.= "</table>";
		$this->salida.= "</td>";
		$this->salida.= "</tr>";
		
		$this->salida.= "<br>";
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->salida.= "<br>";
		$this->salida.= "<table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table\">";
		$this->salida .= "<tr class=\"modulo_table_title\">\n";
		$this->salida .= "<td>PACIENTE: ".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</td>\n";
		$this->salida .= "<td>IDENTIFICACION: ".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</td>\n";
		$this->salida .= "<td>EDAD EN LA ATENCI?N: ";
		if($edad['a?os']!=""){
			$this->salida.=$edad['a?os'].' a?os, ';
		}
		if($edad['meses']!=""){
			$this->salida.=$edad['meses'].' meses, ';
		}
		if($edad['dias']!=""){
			$this->salida.=$edad['dias'].' dias.';
		}
		$this->salida.="</td>\n";
		$this->salida .="<td>HISTORIA :";
		if($this->datosPaciente['historia_numero']!="")
		{
			if($this->datosPaciente['historia_prefijo']!="")
			{
				$this->salida .= $this->datosPaciente['historia_numero']." - ". $this->datosPaciente['historia_prefijo'];
			}
			else
			{
				$this->salida .= $this->datosPaciente['paciente_id']." - ".$this->datosPaciente['historia_prefijo'];
			}
		}
		else
		{
			$this->salida.= $this->datosPaciente['paciente_id']." - ".$this->datosPaciente['tipo_id_paciente'];
		}
		$this->salida.="</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida .= "<tr class=\"modulo_table_title\">\n";
		$this->salida .= "<td colspan='2'>DIRECCION :";
		$this->salida .= $this->datosPaciente['residencia_direccion']."\n";
		if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$this->salida.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$this->salida.= "- ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
		$this->salida.="</td>\n";
		$this->salida .= "<td>RESPONSABLE : ".$this->datosPaciente['nombre_tercero'].' - '.$this->datosPaciente['plan_descripcion']."</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida.= "</table>";
		$this->salida.= "</td>";
		$this->salida.= "</tr>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD>";
		$this->salida.= "<br>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\" class=\"modulo_table\">";
		$this->salida.= "<TR class=\"modulo_table_title\">";
		$this->salida.= "<TD COLSPAN=\"4\" ALIGN=\"CENTER\">DATOS INGRESO";
		$this->salida.= "</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">SERVICIO DE INGRESO</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">FECHA Y HORA DE INGRESO</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">SERVICIO DE EGRESO</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">FECHA Y HORA DE EGRESO</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "<TR>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">".$reg[0][departamento]."</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">".$reg[0][fecha_registro]."</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">".$reg[0][departamento_actual]."</TD>";
		$this->salida.= "<TD ALIGN=\"CENTER\" WIDTH=\"25%\">".$reg[0][fecha_cierre]."</TD>";
		$this->salida.= "</TR>";
		$this->salida.= "</TABLE>";
		/*$this->salida.= "</td>";
		$this->salida.= "</tr>";
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$accion=ModuloGetURL($_SESSION['EPICRISIS']['RETORNO']['contenedor'],
		$_SESSION['EPICRISIS']['RETORNO']['modulo'],
		$_SESSION['EPICRISIS']['RETORNO']['tipo'],
		$_SESSION['EPICRISIS']['RETORNO']['metodo']);
		$this->salida.= "<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		$this->salida.='<form name="epicrisis" action="'.$accion.'" method="post">';
		$this->salida.= "<tr>";
		$this->salida.= "<td><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></td>";
		$this->salida.= "</tr>";
		$this->salida.='</form>';
		$this->salida.= "</TABLE>";
		$this->salida.= "</td>";
		$this->salida.= "</tr>";
*/
		$this->salida .= "</table>\n";
		$this->salida;
		return true;
	}

	function CabeceraImprimir()
	{
		
		$reg=$this->Datos_Ingreso();
		$dato=$this->GetInformacionEmpresa($this->datosEvolucion['departamento']);
		$this->datosPaciente=GetDatosPaciente("","",$this->ingreso);
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
		//$html="".$this->image('images/logocliente.png',170,9,18)."";
		$this->salida .= "<table align=\"center\" width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->imprimir.= "<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">";
		$this->imprimir.= "<TR class=\"modulo_table_title\">";
		$this->imprimir.= "<TD>";
		$this->imprimir.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"80%\" BORDER=\"1\">";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><IMG SRC='images/logocliente.png' WIDTH=100 HEIGHT=100>";
		$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><FONT SIZE='20' FACE='arial'>EPICRISIS";
		$this->imprimir.= "</TD>";
		$this->imprimir.= "</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "</TABLE>";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<FONT SIZE='7' FACE='arial'>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD WIDTH='100'><UBICACION X=10 Y=27>".$dato['id']."</TD>";
		$this->imprimir.= "<TD WIDTH='200'>".$dato['razon_social']."</TD>";
		$this->imprimir.= "<TD WIDTH='200'>".$dato['direccion']."</TD>";
		$this->imprimir.= "<TD WIDTH='200'>".$dato['telefonos']."</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD WIDTH='430'>".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</TD>";
		$this->imprimir.="<TD WIDTH='170'>".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</TD>";
		if($edad['a?os']!=""){
			$edades.=$edad['a?os'].' a?os, ';
		}
		if($edad['meses']!=""){
			$edades.=$edad['meses'].' meses, ';
		}
		if($edad['dias']!=""){
			$edades.=$edad['dias'].' dias.';
		}
		$this->imprimir.= "<TD WIDTH='150'>$edades";
		$this->imprimir.= "</TD>";
		$this->imprimir.= "</TR>";
		$direccion .= $this->datosPaciente['residencia_direccion']." ";
		if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$direccion.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
		$this->imprimir.= "<TR>";
		$this->imprimir.="<TD WIDTH='375'>$direccion</TD>";
		$this->imprimir.= "<TD WIDTH='375'>".$this->datosPaciente['nombre_tercero'].' - '.$this->datosPaciente['plan_descripcion']."</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD>";
		$this->imprimir.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\">";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD COLSPAN=\"4\" ALIGN=\"CENTER\">DATOS INGRESO";
		$this->imprimir.= "</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">SERVICIO DE INGRESO</TD>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">FECHA Y HORA DE INGRESO</TD>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">SERVICIO DE EGRESO</TD>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">FECHA Y HORA DE EGRESO</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "<TR>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][departamento]."</TD>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][fecha_registro]."</TD>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][departamento_actual]."</TD>";
		$this->imprimir.= "<TD ALIGN=\"CENTER\" WIDTH=\"60%\">".$reg[0][fecha_cierre]."</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "</TABLE>";
		$this->imprimir.= "</TABLE>";
		//$this->imprimir.= "</TD>";
		//$this->imprimir.= "</TR>";
		//$this->imprimir.= "</TABLE>";
		$this->imprimir;
		return true;
	}


	function PiePaginaImprimir()
	{
		$this->salida .= "<table class=\"modulo_table_list_claro\" align=\"center\" width=\"80%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
		$this->salida.= "<tr>";
		$this->salida.= "<td>";
		$this->imprimir .= "<TABLE BORDER='0'>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='100%'>".$this->datosProfesional['nombre_tercero']."</TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='100%'>__________________________________</TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='80'>".$this->datosProfesional['tipo_id_tercero'].' - '.$this->datosProfesional['tercero_id']."</TD>";
		$this->imprimir .= "</TR>";
		if(!empty($this->datosProfesional['tarjeta_profesional']))
		{
			$this->imprimir .= "<TR>";
			$this->imprimir .= "<TD WIDTH='80'>TARJETA PROFESIONAL NO.: ".$this->datosProfesional['tarjeta_profesional']."</TD>";
			$this->imprimir .= "</TR>";
		}
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='230'>FECHA DE IMPRESI?N: ".date("Y-m-d H:i")."</TD>";
		$servicio=$this->GetServicio($this->datosEvolucion['departamento']);
		$departamento=$this->GetDepartamento($this->datosEvolucion['departamento']);
		$this->imprimir .= "<TD WIDTH='200'>SERVICIO: ".$servicio."</TD>";
		$this->imprimir .= "<TD WIDTH='100'>DEPARTAMENTO: ".$departamento."</TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE>";
		$this->imprimir.= "</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "</TABLE>";
		$this->imprimir.= "</TD>";
		$this->imprimir.= "</TR>";
		$this->imprimir.= "</TABLE>";
		$this->imprimir;
		return true;
	}

}//fin clase
?>
