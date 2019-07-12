<?php
class ResumenHC_HTML extends ResumenHC
{

	function ResumenHC_HTML($evolucion)
	{
		$this->ResumenHC();
		$this->evolucion=$evolucion;
    $this->CargarVariables();
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


// 	function CabeceraImprimir()
// 	{
// 		$dato=$this->GetInformacionEmpresa($this->datosEvolucion['departamento']);
// 		$Responsable = $this->GetDatosResponsable();
//           $this->Datos_Ingreso();
// 		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->datosEvolucion['fecha']);
// 		$this->imprimir.="<CENTER><IMG SRC='images/logocliente.png'></CENTER>";
// 		$this->imprimir .= "<TABLE BORDER='1' WIDTH=\"100%\">";
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD WIDTH=\"100\" ALIGN='CENTER' COLSPAN=\"4\"><B><FONT SIZE='3' FACE='arial'>HISTORIA CLINICA</FONT></B></TD>";
// 		$this->imprimir .= "</TR>";
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='2' FACE='arial'>RAZON SOCIAL: ".$dato['razon_social']."</FONT></TD>";
// 		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"15%\"><FONT SIZE='2' FACE='arial'>NIT: ".$dato['id']."</FONT></TD>";
// 		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><FONT SIZE='2' FACE='arial'>DIR: ".$dato['direccion']."</FONT></TD>";
// 		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>TEL: ".$dato['telefonos']."</FONT></TD>";
// 		$this->imprimir .= "</TR>";
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD COLSPAN=\"2\" WIDTH=\"50%\" ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>[NOMBRE]:&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</FONT></TD>";
// 		$this->imprimir .="<TD WIDTH=\"25%\" ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>ID.:&nbsp;".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</FONT></TD>";
// 		$this->imprimir .= "<TD WIDTH=\"25%\"ALIGN=\"CENTER\"><FONT SIZE='2' FACE='arial'>".$edad['anos'].'&nbsp;Años';
// 		$this->imprimir .= "</FONT></TD>";
// 		$this->imprimir .= "</TR>";
//           
//           $this->imprimir.= "<TR>";
// 		foreach($this->DatosIngreso_Paciente as $k => $vector)
// 		{
// 			$FechaI = $this->FechaStamp($vector[0]);
//                $HoraI = $this->HoraStamp($vector[0]);
// 			$FechaS = $this->FechaStamp($vector[1]);
//                $HoraS = $this->HoraStamp($vector[1]);
// 			$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA INGRESO:&nbsp;&nbsp;".$FechaI." - ".$HoraI."</FONT></TD>";
// 			$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA EGRESO:&nbsp;&nbsp;".$FechaS." - ".$HoraS."</FONT></TD>";
// 		}
// 		$this->imprimir.= "</TR>";
// 
// 		$direccion .= $this->datosPaciente['residencia_direccion']." ";
// 		if($this->datosPaciente['pais']=="COLOMBIA")
// 		{
// 			$direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
// 		}
// 		else
// 		{
// 			$direccion.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
// 		}
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>".$direccion."</FONT></TD>";
// 		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"CENTER\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>".$Responsable[8].' - '.$Responsable[4]."</FONT></TD>";
// 		$this->imprimir .= "</TR>";
// 		$this->imprimir .= "</TABLE><BR>";
// 		return true;
// 	}

	function CabeceraImprimir()
	{
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
		$this->imprimir.="<br><br>";
		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$this->imprimir.="<tr>\n";
		$this->imprimir.="<td ALIGN=\"LEFT\" width=\"40%\"><IMG SRC='images/logo_SOS.png'></td>";
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
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>ZONA RESIDENCIAL: ".$this->datos_adicionales[zona_r]."</FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>ESTADO CIVIL: ".$this->datos_adicionales[estado_civil]."</FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" COLSPAN=\"3\"><FONT SIZE='2' FACE='arial'>OCUPACION: ".$this->datos_adicionales[ocupacion_descripcion]."</FONT></TD>";
          $this->imprimir .= "</TR>";

 		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>DIR. TRABAJO: ".$this->datos_adicionales[direccion_trabajo]."</FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\"><FONT SIZE='2' FACE='arial'>TEL. TRABAJO: ".$this->datos_adicionales[telefono_trabajo]."</FONT></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" COLSPAN=\"3\"><FONT SIZE='2' FACE='arial'>GRUPO SANGUINEO:  ".$this->datos_adicionales[grupo_sanguineo]."&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;FACTOR RH: ".$this->datos_adicionales[rh]."</FONT></TD>";
          $this->imprimir .= "</TR>";
         		
          $this->imprimir.= "<TR>";
		IncludeLib('funciones_facturacion');
		$Cama='';
		$Cama=BuscarCamaActiva($this->ingreso);

		foreach($this->DatosIngreso_Paciente as $k => $vector)
		{
			$FechaI = $this->FechaStamp($vector[0]);
               $HoraI = $this->HoraStamp($vector[0]);
			$FechaS = $this->FechaStamp($vector[3]);
               $HoraS = $this->HoraStamp($vector[3]);
			$this->imprimir.= "<TD COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA INGRESO:&nbsp;&nbsp;".$FechaI." - ".$HoraI."</FONT></TD>";
			$this->imprimir.= "<TD COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>FECHA EGRESO:&nbsp;&nbsp;".$FechaS." - ".$HoraS."</FONT></TD>";
			$this->imprimir.= "<TD COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='2' FACE='arial'>CAMA:&nbsp;&nbsp;".$Cama."</FONT></TD>";
		}
		$this->imprimir.= "</TR>";

		// SI SE SOLICITA LA VISUALIZACION DEL DEPARTAMENTO Y EL SERVICIO
          /*$servicio=$this->GetServicio($vector[2]);
		$this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>DEPARTAMENTO:&nbsp;&nbsp;".$vector[2]."  -  ".$vector[5]."</td>\n";
          $this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>SERVICIO:&nbsp;&nbsp;".$servicio."</td>\n";
		$this->imprimir .= "</tr>\n";*/

          $this->imprimir.= "<TR>";
		$this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>DEPARTAMENTO:&nbsp;&nbsp;".$vector[2]."  -  ".$vector[5]."</td>\n";
          $this->imprimir .= "<td COLSPAN=\"4\" ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='2' FACE='arial'>EN CASO DE ACCIDENTE AVISAR A: ".$this->datos_adicionales[nombre_aviso]."&nbsp;&nbsp;TEL.: ".$this->datos_adicionales[telefono_aviso]."</td>\n";
		$this->imprimir .= "</tr>\n";

          
          $this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>CLIENTE:&nbsp;&nbsp;".$this->Responsable[8]."</td>\n";
		$this->imprimir .= "<td COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>PLAN:&nbsp;&nbsp;".$this->Responsable[4]."</td>\n";
		$this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"34%\"><FONT SIZE='2' FACE='arial'>TIPO AFILIADO:&nbsp;&nbsp;".$this->Responsable[9]."</td>\n";
		$this->imprimir .= "</tr>\n";
          
          $Dir_Ips = $this->Direccion_IPS($vector[6]);
          $this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>IPS:&nbsp;&nbsp;".$Dir_Ips[descripcion]."</td>\n";
		$this->imprimir .= "<td COLSPAN=\"1\" ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='2' FACE='arial'>DIRECCION:&nbsp;&nbsp;".$Dir_Ips[ubicacion]."</td>\n";
		$this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"34%\"><FONT SIZE='2' FACE='arial'>TELEFONO IPS:&nbsp;&nbsp;".$Dir_Ips[telefono]."</td>\n";
		$this->imprimir .= "</tr>\n";
         
		$this->imprimir.= "</table>";
		return true;
	}

// 	function PiePaginaImprimir()
// 	{
// 		$this->imprimir .= "<TABLE BORDER='0'>";
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD WIDTH='200'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['nombre_tercero']."</FONT></TD>";
// 		$this->imprimir .= "</TR>";
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD WIDTH='200'>__________________________________</FONT></TD>";
// 		$this->imprimir .= "</TR>";
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD WIDTH='80'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['tipo_id_tercero'].' - '.$this->datosProfesional['tercero_id']."</FONT></TD>";
// 		$this->imprimir .= "</TR>";
// 		if(!empty($this->datosProfesional['tarjeta_profesional']))
// 		{
// 			$this->imprimir .= "<TR>";
// 			$this->imprimir .= "<TD WIDTH='80'><FONT SIZE='2' FACE='arial'>TARJETA PROFESIONAL NO.: ".$this->datosProfesional['tarjeta_profesional']."</FONT></TD>";
// 			$this->imprimir .= "</TR>";
// 		}
// 		$this->imprimir .= "<TR>";
// 		$this->imprimir .= "<TD WIDTH='230'><FONT SIZE='2' FACE='arial'>FECHA DE IMPRESIÓN: ".date("Y-m-d H:i")."</FONT></TD>";
// 		$servicio=$this->GetServicio($this->datosEvolucion['departamento']);
// 		$departamento=$this->GetDepartamento($this->datosEvolucion['departamento']);
// 		$this->imprimir .= "<TD WIDTH='250'><FONT SIZE='2' FACE='arial'>SERVICIO: ".$servicio."</FONT></TD>";
// 		$this->imprimir .= "<TD WIDTH='250'><FONT SIZE='2' FACE='arial'>DEPARTAMENTO: ".$departamento."</FONT></TD>";
// 		$this->imprimir .= "</TR>";
// 		$this->imprimir .= "</TABLE>";
// 		return true;
// 	}
     
	function PiePaginaImprimir()
	{
		$this->imprimir .= "<BR><TABLE BORDER='0'>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['nombre']."</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir.="<TD ALIGN=\"LEFT\" ><IMG SRC='images/firmas_profesionales/".$this->datosProfesional['firma']."'></td>";
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
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>".$this->datosProfesional['descripcion']."</FONT></TD>";
          $this->imprimir .= "</TR>";
		$this->imprimir .= "</TABLE><br>";
          
          $fechita = date("d-m-Y H:i:s");
          $FechaImprime = $this->FechaStamp($fechita);
          $HoraImprime = $this->HoraStamp($fechita);
          
          $this->imprimir .= "<TABLE BORDER='0' WIDTH=\"100%\">";
		$this->imprimir .= "<TR>";
          $this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
          //$this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$datos_usuario[nombre]." - ".$datos_usuario[usuario]."</FONT></td>\n";
		$this->imprimir .= "<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
          //$this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"33%\"><FONT SIZE='1' FACE='arial'>FECHA:&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$this->imprimir .= "</TR>";
		$this->imprimir.= "</table>";
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
			$salida2 .="<br><table width=\"100%\" border=\"1\" align=\"center\" class=\"hc_table_submodulo_list\">";
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
