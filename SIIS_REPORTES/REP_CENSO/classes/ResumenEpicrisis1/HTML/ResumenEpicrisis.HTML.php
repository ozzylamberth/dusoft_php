<?php
class ResumenEpicrisis_HTML extends ResumenEpicrisis
{

     /************************************************************************/
     /* SE DEBE REALIZAR UN TIPO CIERRE (sw_epicrisis), EL CUAL VERIFIQUE LA  
     /* EXISTENCIA DE LA INFO VALIDA PARA LA EPICRISIS.
     /*
     /* GENERAR EN LOS SUBMODULOS UN METODO Q RETORNE LOS DATOS PARA LA IMPRESION
     /* DE LA EPICRISIS EJ. GETDATOSEPICRISIS($TIPO='') PARECIDO A IMP. HISTORIA
     /* SE DEBE SWITCHEAR LA VARIABLE TIPO PARA VALIDAR LA MANERA DE RETORNO
     /* YA Q HABRA UN TIPO DE IMPRESION DE EPICRISIS DE SOAT, Y POR LO TANTO LOS
     /* DATOS SE MUESTRAN DE MANERA DIFERENTE.
     /************************************************************************/

     
	function ResumenEpicrisis_HTML($ingreso)
	{
		$this->ResumenEpicrisis();
		$this->ingreso=$ingreso;
          $this->CargarVariables();
		return true;
	}


	function CabeceraImprimir()
	{
          $edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
		$this->imprimir.="<br><br>";
		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$this->imprimir.="<tr>\n";
		$this->imprimir.="<TD ALIGN=\"LEFT\" width=\"40%\"><IMG SRC='images/logocliente.png'></td>";
          $this->imprimir.="<TD ALIGN=\"CENTER\" width=\"60%\"><FONT SIZE='5' FACE='arial'>RESUMEN EPICRISIS</FONT>";
		$this->imprimir.="</TD>\n";
		$this->imprimir.="</TR>\n";
          $this->imprimir.="</table>";
 		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table\">";
		$this->imprimir.="<TR>\n";
		$this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\" COLSPAN=\"2\"><FONT SIZE='2' FACE='arial'><B>PACIENTE:</B>&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</FONT></TD>\n";
          $this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='2' FACE='arial'><B>IDENTIFICACION:</B>&nbsp;".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</FONT></TD>\n";
		$this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\" COLSPAN=\"2\"><FONT SIZE='2' FACE='arial'><B>HC:</B>&nbsp;";
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
		$this->imprimir.="</FONT></TD>\n";
		$this->imprimir.="</TR>\n";
          
          $this->imprimir .= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"5\">";
          $this->imprimir .= "<table border=\"0\" width=\"100%\">";
          
          $FechaNacimiento = $this->FechaStamp($this->datosPaciente['fecha_nacimiento']);          
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><FONT SIZE='2' FACE='arial'><B>FECHA DE NACIMIENTO:</B> ".$FechaNacimiento."</FONT></TD>";
		
          $this->imprimir.="<TD ALIGN=\"CENTER\" WIDTH=\"20%\"><FONT SIZE='2' FACE='arial'><B>EDAD:</B>&nbsp;";
		$this->imprimir.=$edad['anos'].'&nbsp;Años';
		$this->imprimir.="</FONT></TD>\n";
          
		$this->imprimir .="<TD ALIGN=\"CENTER\" WIDTH=\"20%\"><FONT SIZE='2' FACE='arial'><B>SEXO:</B>&nbsp;";
		$this->imprimir.= $this->datosPaciente['sexo_id'];
		$this->imprimir.="</FONT></TD>\n";
          
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><FONT SIZE='2' FACE='arial'><B>TIPO AFILIADO:</B>&nbsp;&nbsp;".$this->Responsable[9]."</td>\n";
          
          $this->imprimir .= "</table>";
          $this->imprimir .= "</TD>";
          $this->imprimir .= "</TR>";          
          
          
          $this->imprimir .= "<TR>";
          $res = $this->datosPaciente['residencia_direccion'];
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\" COLSPAN=\"2\"><FONT SIZE='2' FACE='arial'><B>RESIDENCIA:</B> ".$res."</FONT></TD>";

          if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$direccion.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='2' FACE='arial'>".$direccion."</FONT></TD>";

          $tel = $this->datosPaciente['residencia_telefono'];
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\" COLSPAN=\"2\"><FONT SIZE='2' FACE='arial'><B>TELEFONO: </B>".$tel."</FONT></TD>";
          $this->imprimir .= "</TR>";

          
		$this->imprimir .= "<TR>";
          $this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'><B>NOMBRE ACOMPAÑANTE: </B></FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='2' FACE='arial'><B>PARENTESCO: </B></FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" COLSPAN=\"2\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'><B>TELEFONO: </B></FONT></TD>";
          $this->imprimir .= "</TR>";
          

          $this->imprimir.= "<TR>";
		$this->BuscarCamaActiva($this->ingreso);

          $FechaI = $this->FechaStamp($this->DatosIngreso_Paciente['fecha_registro']);
          $HoraI = $this->HoraStamp($this->DatosIngreso_Paciente['fecha_registro']);
          
          if (empty($this->DatosCama['fecha_egreso']))
          {
               $FechaS = $this->FechaStamp($this->DatosIngreso_Paciente['cierre_evolucion']);
               $HoraS = $this->HoraStamp($this->DatosIngreso_Paciente['cierre_evolucion']);
          }
          else
          {
               $FechaS = $this->FechaStamp($this->DatosCama['fecha_egreso']);
               $HoraS = $this->HoraStamp($this->DatosCama['fecha_egreso']);
          }
          $this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'><B>FECHA INGRESO:</B>&nbsp;&nbsp;".$FechaI." - ".$HoraI."</FONT></TD>";
          $this->imprimir.= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='2' FACE='arial'><B>FECHA EGRESO:</B>&nbsp;&nbsp;".$FechaS." - ".$HoraS."</FONT></TD>";
          $this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'><B>CAMA:</B>&nbsp;&nbsp;".$this->DatosCama['cama']."</FONT></TD>";
		$this->imprimir.= "</TR>";
		
          
          $servicio=$this->GetServicio($this->DatosIngreso_Paciente['departamento_actual']);
		$this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'><B>DEPARTAMENTO:</B>&nbsp;&nbsp;".$this->DatosIngreso_Paciente['departamento_actual']."  -  ".$this->DatosIngreso_Paciente['descripcion']."</FONT></TD>\n";
          $this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"60%\"><FONT SIZE='2' FACE='arial'><B>SERVICIO:</B>&nbsp;&nbsp;".$servicio."</FONT></TD>\n";
		$this->imprimir .= "</tr>\n";

		
          $this->imprimir.= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='2' FACE='arial'><B>CLIENTE:</B>&nbsp;&nbsp;".$this->Responsable[8]."</FONT></TD>\n";
		$this->imprimir .= "<TD COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"60%\"><FONT SIZE='2' FACE='arial'><B>PLAN:</B>&nbsp;&nbsp;".$this->Responsable[4]."</FONT></TD>\n";
		$this->imprimir .= "</TR>\n";
		$this->imprimir.= "</table>";
		return true;          
	}


	function PiePaginaImprimir()
	{
		$this->imprimir .= "<BR><TABLE BORDER='0'>";
          
          $largo = strlen($this->datosProfesional['nombre']);
		if($largo < '5')
          {$largo = $largo + '12'; }
          $largo = $largo + '16';
		for ($l=0; $l<$largo; $l++)
		{
      		$cad = $cad.'_';
    		}

          $this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>$cad</FONT></TD>";
		$this->imprimir .= "</TR>";
          $this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'><b>PROFESIONAL:</b>&nbsp;&nbsp;&nbsp;".$this->datosProfesional['nombre']."</FONT></TD>";
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
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'><b>ESPECIALIDAD</b> -&nbsp;&nbsp; ".$this->datosProfesional['descripcion']."</FONT></TD>";
          $this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE><br>";
          
          $fechita = date("d-m-Y H:i:s");
          $FechaImprime = $this->FechaStamp($fechita);
          $HoraImprime = $this->HoraStamp($fechita);
          
          $this->imprimir .= "<TABLE BORDER='0' WIDTH=\"100%\">";
		$this->imprimir .= "<TR>";
          $this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
		$this->imprimir .= "<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$this->imprimir .= "</TR>";
		$this->imprimir.= "</table>";
		return true;
	}

}//fin clase
?>
