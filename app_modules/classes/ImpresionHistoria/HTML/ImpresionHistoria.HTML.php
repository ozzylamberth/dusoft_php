<?php
class ImpresionHistoria_HTML extends ImpresionHistoria
{

	function ImpresionHistoria_HTML($ingreso)
	{
		$this->ImpresionHistoria();
		$this->ingreso=$ingreso;
          $this->CargarVariables();
		return true;
	}


/*	function Cabecera()
	{
//$this->DatosIngreso_Paciente=$this->Datos_Ingreso();
		//$this->Responsable = $this->GetDatosResponsable();
//		$this->datosPaciente=GetDatosPaciente("","",$this->ingreso);
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
		$this->salida.="<table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table\">";
		$this->salida.="<tr>\n";
		$this->salida.="<td ALIGN=\"CENTER\" COLSPAN=\"4\"><FONT SIZE='4' FACE='arial'>HISTORIA CLINICA";
		$this->salida.="</td>";
		$this->salida.="</tr>\n";
		$this->salida.="<tr>\n";
		$this->salida.="<td ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>PACIENTE:&nbsp;&nbsp;".$this->datosPaciente['tipo_id_paciente']." - ".$this->datosPaciente['paciente_id']."&nbsp;&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</td>\n";
		$this->salida.="<td ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>EDAD ATENCIÓN:&nbsp;&nbsp;";
		$this->salida.=$edad['anos'].'&nbsp;Años';
		$this->salida.="</td>\n";
		$this->salida .="<td ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>SEXO:&nbsp;&nbsp;";
		$this->salida.= $this->datosPaciente['sexo_id'];
		$this->salida .="<td ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>HC:&nbsp;&nbsp;";
		if($this->datosPaciente['historia_numero']!="")
		{
			if($this->datosPaciente['historia_prefijo']!="")
			{
				$this->salida .= $this->datosPaciente['historia_numero']." - ". $this->datosPaciente['historia_prefijo'];
			}
			else
			{
				$this->salida .= $this->datosPaciente['historia_numero']." - ".$this->datosPaciente['tipo_id_paciente'];
			}
		}
		else
		{
			$this->salida.= $this->datosPaciente['paciente_id']." - ".$this->datosPaciente['tipo_id_paciente'];
		}
		$this->salida.="</td>\n";
		$this->salida.="</tr>\n";
		$this->salida.="</table>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\" class=\"modulo_table\">";
		$this->salida.= "<TR>";
		foreach($this->DatosIngreso_Paciente as $k => $vector)
		{
			$FechaS = $this->FechaStamp($vector[4]);
			$FechaI = $this->FechaStamp($vector[0]);
			$this->salida.= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA SOLICITUD:&nbsp;&nbsp;".$FechaS."</FONT></TD>";
			$this->salida.= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA INGRESO:&nbsp;&nbsp;".$FechaI."</FONT></TD>";
			$this->salida.= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>CAMA:&nbsp;&nbsp;</FONT></TD>";
		}
		$this->salida.= "</TR>";
		$this->salida.= "</TABLE>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\" class=\"modulo_table\">";
		$this->salida.= "<TR>";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>CLIENTE:&nbsp;&nbsp;".$this->Responsable[8]."</td>\n";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>PLAN:&nbsp;&nbsp;".$this->Responsable[4]."</td>\n";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"34%\"><FONT SIZE='2' FACE='arial'>TIPO AFILIADO:&nbsp;&nbsp;".$this->Responsable[9]."</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida.= "</table>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\" class=\"modulo_table\">";
		$servicio=$this->GetServicio($vector[2]);
		$this->salida.= "<TR>";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>SERVICIO:&nbsp;&nbsp;".$servicio."</td>\n";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>DEPARTAMENTO:&nbsp;&nbsp;".$vector[2]."  -  ".$vector[5]."</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida.= "</table>";
		return true;
	}*/

	function CabeceraImprimir()
	{
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
		$this->imprimir.="<br><br>";
		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$this->imprimir.="<tr>\n";
		$this->imprimir.="<td ALIGN=\"LEFT\" width=\"40%\"><IMG SRC='images/logocliente.png'></td>";
          $this->imprimir.="<td ALIGN=\"CENTER\" width=\"60%\"><FONT SIZE='5' FACE='arial'>HISTORIA CLINICA</FONT>";
		$this->imprimir.="</td>\n";
		$this->imprimir.="</tr>\n";
          $this->imprimir.="</table>";
 		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table\">";
		$this->imprimir.="<tr>\n";
		$this->imprimir.="<td ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'>NOMBRE:&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</td>\n";
          $this->imprimir.="<td ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>IDENTIFICACION:&nbsp;".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</td>\n";
		$this->imprimir.="<td ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>HC:&nbsp;";
		if($this->datosPaciente['historia_numero']!="")
		{
			if($this->datosPaciente['historia_prefijo']!="")
			{
				$this->imprimir .= $this->datosPaciente['historia_numero']." - ". $this->datosPaciente['historia_prefijo'];
			}
			else
			{
				$this->imprimir .= $this->datosPaciente['historia_numero']." - ".$this->datosPaciente['tipo_id_paciente'];
			}
		}
		else
		{
			$this->imprimir.= $this->datosPaciente['paciente_id']." - ".$this->datosPaciente['tipo_id_paciente'];
		}
		$this->imprimir.="</td>\n";
		$this->imprimir.="<td ALIGN=\"JUSTIFY\" WIDTH=\"6%\"><FONT SIZE='2' FACE='arial'>EDAD:&nbsp;";
		$this->imprimir.=$edad['anos'].'&nbsp;Años';
		$this->imprimir.="</td>\n";
		$this->imprimir .="<td ALIGN=\"JUSTIFY\" WIDTH=\"4%\"><FONT SIZE='2' FACE='arial'>SEXO:&nbsp;";
		$this->imprimir.= $this->datosPaciente['sexo_id'];
		$this->imprimir.="</td>\n";
		$this->imprimir.="</tr>\n";
          
          $FechaNacimiento = $this->FechaStamp($this->datosPaciente['fecha_nacimiento']);
          $this->imprimir .= "<TR>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'>FECHA DE NACIMIENTO: ".$FechaNacimiento."</FONT></TD>";
          
          $res = $this->datosPaciente['residencia_direccion'];
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'>RESIDENCIA: ".$res."</FONT></TD>";
          
          $tel = $this->datosPaciente['residencia_telefono'];
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'>TELEFONO: ".$tel."</FONT></TD>";
          
          if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$direccion.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
          $this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'>".$direccion."</FONT></TD>";
          $this->imprimir .= "</TR>";

		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"1\" ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>PARENTESCO ACOMPAÑANTE: </FONT></TD>";
          $this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>NOMBRE ACOMPAÑANTE: </FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" COLSPAN=\"2\"><FONT SIZE='2' FACE='arial'>TELEFONO: </FONT></TD>";
          $this->imprimir .= "</TR>";
		
          $this->imprimir.= "<TR>";
		IncludeLib('funciones_facturacion');
		$Cama='';
		$Cama=BuscarCamaActiva($this->ingreso);

		foreach($this->DatosIngreso_Paciente as $k => $vector)
		{
			$FechaI = $this->FechaStamp($vector[0]);
               $HoraI = $this->HoraStamp($vector[0]);
			$FechaS = $this->FechaStamp($vector[4]);
               $HoraS = $this->HoraStamp($vector[4]);
			$this->imprimir.= "<TD COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA INGRESO:&nbsp;&nbsp;".$FechaI." - ".$HoraI."</FONT></TD>";
			$this->imprimir.= "<TD COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA EGRESO:&nbsp;&nbsp;".$FechaS." - ".$HoraS."</FONT></TD>";
			$this->imprimir.= "<TD COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>CAMA:&nbsp;&nbsp;".$Cama."</FONT></TD>";
		}
		$this->imprimir.= "</TR>";

		$servicio=$this->GetServicio($vector[2]);
		$this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>DEPARTAMENTO:&nbsp;&nbsp;".$vector[2]."  -  ".$vector[5]."</td>\n";
          $this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>SERVICIO:&nbsp;&nbsp;".$servicio."</td>\n";
		$this->imprimir .= "</tr>\n";

		$this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>CLIENTE:&nbsp;&nbsp;".$this->Responsable[8]."</td>\n";
		$this->imprimir .= "<td COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>PLAN:&nbsp;&nbsp;".$this->Responsable[4]."</td>\n";
		$this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"34%\"><FONT SIZE='2' FACE='arial'>TIPO AFILIADO:&nbsp;&nbsp;".$this->Responsable[9]."</td>\n";
		$this->imprimir .= "</tr>\n";
		$this->imprimir.= "</table>";
		return true;
	}


	function PiePaginaImprimir()
	{
		$this->imprimir .= "<BR><TABLE BORDER='0'>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>MEDICO:&nbsp;&nbsp;&nbsp;".$this->datosProfesional['nombre']."</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>_________________________________________</FONT></TD>";
		$this->imprimir .= "</TR>";
		if(!empty($this->datosProfesional['tarjeta_profesional']))
		{
			$this->imprimir .= "<TR>";
			$this->imprimir .= "<TD WIDTH='50%'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['tipo_id_tercero'].' - '.$this->datosProfesional['tercero_id']."&nbsp;&nbsp;-&nbsp;&nbsp;T.P&nbsp;&nbsp;".$this->datosProfesional['tarjeta_profesional']."</FONT></TD>";
			$this->imprimir .= "</TR>";
		}
		else
		{
			$this->imprimir .= "<TR>";
			$this->imprimir .= "<TD WIDTH='50%'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['tipo_id_tercero'].' - '.$this->datosProfesional['tercero_id']."</FONT></TD>";
			$this->imprimir .= "</TR>";
		}
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>ESPECIALIDAD -&nbsp;&nbsp; ".$this->datosProfesional['descripcion']."</FONT></TD>";
          $this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE><br>";
          
          $fechita = date("d-m-Y H:i:s");
          $FechaImprime = $this->FechaStamp($fechita);
          $HoraImprime = $this->HoraStamp($fechita);
          
          $this->imprimir .= "<TABLE BORDER='0' WIDTH=\"100%\">";
		$this->imprimir .= "<TR>";
          $this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
		$this->imprimir .= "<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
          //$this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='1' FACE='arial'>FECHA:&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$this->imprimir .= "</TR>";
		$this->imprimir.= "</table>";
		return true;
	}

}//fin clase
?>
