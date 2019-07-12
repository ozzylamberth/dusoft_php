<?php
class ResumenHC_HTML extends ResumenHC
{

	function ResumenHC_HTML($evolucion)
	{
		$this->ResumenHC();
		$this->evolucion=$evolucion;
		return true;
	}

	function Cabecera()
	{
		$Responsable = $this->GetDatosResponsable();
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
		$this->salida.="<br><table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table_list\">";
		$this->salida.="<tr class=\"modulo_table_title\">\n";
		$this->salida.="<td ALIGN=\"JUSTIFY\">PACIENTE:&nbsp;&nbsp;".$this->datosPaciente['tipo_id_paciente']." - ".$this->datosPaciente['paciente_id']."&nbsp;&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</td>\n";
		$this->salida.="<td ALIGN=\"JUSTIFY\">EDAD EN LA ATENCIÓN:&nbsp;&nbsp;";
		$this->salida.=$edad['anos'].'&nbsp;Años';
		$this->salida.="</td>\n";
		$this->salida .="<td ALIGN=\"JUSTIFY\">SEXO:&nbsp;&nbsp;";
		$this->salida.= $this->datosPaciente['sexo_id'];
		$this->salida .="<td ALIGN=\"JUSTIFY\">HC:&nbsp;&nbsp;";
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

		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\" class=\"modulo_table_list\">";//modulo_table
		$this->salida.= "<TR class=\"modulo_table_title\">";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"33%\">CLIENTE:&nbsp;&nbsp;".$Responsable[8]."</td>\n";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"33%\">PLAN:&nbsp;&nbsp;".$Responsable[4]."</td>\n";
		$this->salida .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"34%\">TIPO AFILIADO:&nbsp;&nbsp;".$Responsable[9]."</td>\n";
		$this->salida .= "</tr>\n";
		$this->salida.= "</table>";
		$this->salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"1\" class=\"modulo_table_list\">";
		$this->salida .= "<tr class=\"modulo_table_title\">\n";
		$this->salida .= "<td>DIRECCION :";
		$this->salida .= $this->datosPaciente['residencia_direccion']."\n";
		if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$this->salida.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$this->salida.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
		$this->salida.="</td>\n";
		$this->salida.= "</table>";
		return true;
	}


	function CabeceraImprimir()
	{
		$dato=$this->GetInformacionEmpresa($this->datosEvolucion['departamento']);
		$Responsable = $this->GetDatosResponsable();
          $this->Datos_Ingreso();
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
		$this->imprimir.="<CENTER><IMG SRC='images/logocliente.png'></CENTER>";
		$this->imprimir .= "<TABLE BORDER='1' WIDTH=\"100%\">";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH=\"100\" ALIGN='CENTER' COLSPAN=\"4\"><B><FONT SIZE='3' FACE='arial'>HISTORIA CLINICA</FONT></B></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='2' FACE='arial'>RAZON SOCIAL: ".$dato['razon_social']."</FONT></TD>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"15%\"><FONT SIZE='2' FACE='arial'>NIT: ".$dato['id']."</FONT></TD>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><FONT SIZE='2' FACE='arial'>DIR: ".$dato['direccion']."</FONT></TD>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>TEL: ".$dato['telefonos']."</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"2\" WIDTH=\"50%\" ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>NOMBRE:&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</FONT></TD>";
		$this->imprimir .="<TD WIDTH=\"25%\" ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>ID.:&nbsp;".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</FONT></TD>";
		$this->imprimir .= "<TD WIDTH=\"25%\"ALIGN=\"CENTER\"><FONT SIZE='2' FACE='arial'>".$edad['anos'].'&nbsp;Años';
		$this->imprimir .= "</FONT></TD>";
		$this->imprimir .= "</TR>";
          
          $this->imprimir.= "<TR>";
		foreach($this->DatosIngreso_Paciente as $k => $vector)
		{
			$FechaI = $this->FechaStamp($vector[0]);
               $HoraI = $this->HoraStamp($vector[0]);
			$FechaS = $this->FechaStamp($vector[1]);
               $HoraS = $this->HoraStamp($vector[1]);
			$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA INGRESO:&nbsp;&nbsp;".$FechaI." - ".$HoraI."</FONT></TD>";
			$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA EGRESO:&nbsp;&nbsp;".$FechaS." - ".$HoraS."</FONT></TD>";
		}
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
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>".$direccion."</FONT></TD>";
		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>".$Responsable[8].' - '.$Responsable[4]."</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE><BR>";
		return true;
	}



	function PiePaginaImprimir()
	{
		$this->imprimir .= "<TABLE BORDER='0'>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='200'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['nombre_tercero']."</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='200'>__________________________________</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='80'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['tipo_id_tercero'].' - '.$this->datosProfesional['tercero_id']."</FONT></TD>";
		$this->imprimir .= "</TR>";
		if(!empty($this->datosProfesional['tarjeta_profesional']))
		{
			$this->imprimir .= "<TR>";
			$this->imprimir .= "<TD WIDTH='80'><FONT SIZE='2' FACE='arial'>TARJETA PROFESIONAL NO.: ".$this->datosProfesional['tarjeta_profesional']."</FONT></TD>";
			$this->imprimir .= "</TR>";
		}
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='230'><FONT SIZE='2' FACE='arial'>FECHA DE IMPRESIÓN: ".date("Y-m-d H:i")."</FONT></TD>";
		$servicio=$this->GetServicio($this->datosEvolucion['departamento']);
		$departamento=$this->GetDepartamento($this->datosEvolucion['departamento']);
		$this->imprimir .= "<TD WIDTH='250'><FONT SIZE='2' FACE='arial'>SERVICIO: ".$servicio."</FONT></TD>";
		$this->imprimir .= "<TD WIDTH='250'><FONT SIZE='2' FACE='arial'>DEPARTAMENTO: ".$departamento."</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE>";
		return true;
	}
     
     function Vista_NotaMedica()
     {
		$nota = $this->Consulta_NotasMedicas();
		if (empty ($nota))
		{
			$salida2 .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida2 .="<div class='label_mark' align='center'><BR>ESTA HISTORIA AUN NO PRESENTA NOTAS DE OBSERVACIONES SOBRE HC<br><br>";
			$salida2 .="</tr>";
			$salida2 .="</table>";
		}
		if (!empty ($nota))
  		{
			$salida2 .="<br><table width=\"100%\" border=\"0\" align=\"center\" class=\"hc_table_submodulo_list\">";
			$salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida2 .="<td align=\"center\"colspan=\"2\">NOTAS DE OBSERVACIONES SOBRE HC</td>";
			$salida2 .="</tr>";

			$salida2 .="<tr class=\"hc_table_submodulo_list_title\">";
			$salida2 .="<td>FECHA</td>";
			$salida2 .="<td align=\"center\">NOTA</td>";
			$salida2 .="</tr>";

			$spy=0;
			foreach($nota as $k=>$v)
			{
				if($spy==0)
				{
					$salida2.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$salida2.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}

				$salida2 .="<td width='10%' align='center'>$k</td>";


				$salida2 .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector){

					$salida2 .="<tr class=\"hc_submodulo_list_oscuro\">";
					$salida2 .="<td><b>$vector[hora]</b></td>";
					$salida2 .="<td><b>";
					$salida2 .=$vector[usuario].' - '.$vector[nombre];
					$salida2 .="</b></td>";

					$salida2 .="</tr>";
					$salida2 .="<tr class=\"hc_submodulo_list_claro\">";
					$salida2 .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$salida2 .="<td width='100%'>".$vector[nota_medica]."</td>";
					$salida2 .="</tr>";
					$salida2 .="<tr>";

				}
				$salida2 .="</table>";
				$salida2 .="</td>";
				$salida2 .="</tr>";
			}

			$salida2.="</table><br>";
		}
          return $salida2;
     }




}//fin clase
?>
