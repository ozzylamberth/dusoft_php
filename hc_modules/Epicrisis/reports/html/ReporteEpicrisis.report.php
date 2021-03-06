<?php

/**
 * $Id: ReporteEpicrisis.report.php,v 1.9 2007/06/07 16:35:44 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

IncludeClass("GeneracionEpicrisis",null,"hc","Epicrisis");



 
class ReporteEpicrisis_report
{
	var $datos;
	
	function ReporteEpicrisis_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}
	 
	function CrearReporte()
	{
		if(!$this->datos)
		{
			$ingreso=SessionGetVar("Ingreso");
			$evolucion=SessionGetVar("Evolucion");
			$tipoidpaciente=SessionGetVar("tipoidpaciente");
			$paciente=SessionGetVar("paciente");
		}
		else
		{
			$ingreso=$this->datos['ingreso'];
			$evolucion=$this->datos['evolucion'];
		}
		$this->ingreso=$ingreso;
		$this->CargarVariables();
		$tipoidpaciente=$this->datosPaciente["tipo_id_paciente"];
		$paciente=$this->datosPaciente["paciente_id"];
		
		$epicrisis=new GeneracionEpicrisis();
		
		$epi=$epicrisis->GetDatosEpicrisis($ingreso);
		
		$motivo_consulta=$epicrisis->GetDatosMotivosConsulta($ingreso);
	
		$enfermedad=$epicrisis->GetDatosEnfermedad($ingreso);

		$sw=1;
		if(!$this->datos)
			$sw=0;
		$antecedentes=$epicrisis->GetDatosAntecedentesPersonales($ingreso,$evolucion,$paciente,$tipoidpaciente,$sw,0);
		
		$ex_fisico=$epicrisis->GetDatosExamenFisico($ingreso);
		
		$ex_fisico_hallazgo=$epicrisis->GetDatosExamenFisicoHallazgos($ingreso);
	
		$apoyod=$epicrisis->GetDatosApoyosD($ingreso,$tipoidpaciente,$paciente);
		
		$diagI=$epicrisis->GetDiagnosticos($ingreso,"ingreso",1);
		
		$datos_evolucion=$epicrisis->GetDatosEvolucion($ingreso);
		
		$medicamentos=$epicrisis->GetMedicamentosPacientes($ingreso);
			
		$plan_seg=$epicrisis->GetDatosPlanSeguimiento($ingreso);
		
		$diagE=$epicrisis->GetDiagnosticos($ingreso,"egreso",1);

		$datos_salida=$epicrisis->GetDatosCausaSalida($ingreso);
		
		$font1="<font size='1' face='arial'>";
		$font2="<font size='2' face='arial'>";

		$HTML_WEB_PAGE .="<HTML><BODY>";         
		
		$HTML_WEB_PAGE.=$this->CabeceraImprimir();
		
		$HTML_WEB_PAGE.="<br>$font2 <b>DATOS DEL INGRESO</b></font>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>MOTIVO CONSULTA</b> </font><br>";
		foreach($motivo_consulta as $mc)
			$mot_con="$font1".strtoupper($mc['descripcion1'])."</font>";	
		$HTML_WEB_PAGE.="		$mot_con";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>ESTADO GENERAL Y ENFERMEDAD ACTUAL ACTUAL</b> </font><br>";
		$l=0;
		foreach($enfermedad as $enfer)
		{
			$c=",  ";
			if(sizeof($enfermedad)==$l+1)
				$c="";
				
			$enf.="$font1".strtoupper($enfer['enfermedadactual'])."$c</font>";	
			$l++;
		}
		$HTML_WEB_PAGE.="		$enf";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>ANTECEDENTES PERSONALES</b> </font> <br>";
		$m=0;
		$HTML_WEB_PAGE.="		$font1";

		foreach($antecedentes as $key1=>$nivel1)
		{
			$n=0;
			$html1="";
			foreach($nivel1 as $key2=>$nivel2)
			{
				$tabla1="";
				$l=0;
				foreach($nivel2 as $key3=>$nivel3)
				{
					$c=", ";
					if(sizeof($nivel2)==$l+1)
						$c="";
					
					if($nivel3['sw_riesgo']=='1')
						$estado="Si";
					else
						$estado="No";
					
					if($nivel3['detalle']=='&nbsp;&nbsp;')
						$nivel3['detalle']="";
					
					$tabla1.="$estado - ".strtoupper($nivel3['detalle'])."$c";
					$l++;
				}
				$html1.="	".strtoupper($key2)." : ";
				$html1.="	$tabla1 <br>";

				$n++;
			}
			$salida.="	$key1 <br>";
			$salida.="	$html1";
			$m++;
		}
		
		$HTML_WEB_PAGE.="		$salida";
		$HTML_WEB_PAGE.="	</font>";
		$HTML_WEB_PAGE.="</LI>";
		$HTML_WEB_PAGE.="</UL>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>EXAMEN FISICO</b> </font><br>";
		$salida="";
		$HTML_WEB_PAGE.="	$font1";
		foreach($ex_fisico as $key1=>$nivel1)
		{
			$estado="";
			$l=0;
			foreach($nivel1 as $nivel2)
			{
				$c=",  ";
				if(sizeof($nivel1)==$l+1)
					$c="";
					
				if($nivel2['sw_normal']=='N')
					$estado.="NORMAL$c";
				else
					$estado.="ANORMAL$c";
				
				$l++;
			}
			$salida.=strtoupper($key1)." : ";
			$salida.="$estado<br>";
		}
		$l=0;
		
		$salida.=" HALLAZGOS: ";
		
		foreach($ex_fisico_hallazgo as $nivel1)
		{
			$c=",  ";
			if(sizeof($ex_fisico_hallazgo)==$l+1)
				$c="";
				
			$salida.="".strtoupper($nivel1['hallazgo'])."$c";
				
			$l++;
		}
		$HTML_WEB_PAGE.="		$salida";
		$HTML_WEB_PAGE.="	</font>";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";

		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>APOYOS DIAGNOSTICOS</b> </font><br>";
		foreach($apoyod as $apoyo)
			$apd="$font1".strtoupper($apoyo['descripcion1'])."</font>";	
		$HTML_WEB_PAGE.="		$apd";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		if($diagI)
		{
			$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
			$HTML_WEB_PAGE.="		<LI>";
			$HTML_WEB_PAGE.="		 <b>$font1 DIAGNOSTICOS DE INGRESO</b></font><br>";
			$HTML_WEB_PAGE.="		";
			$salida="";

			$salida.="<table align='center' border='0' width='100%' class='modulo_table_list' cellspacing='0' cellpadding='0'>";
			$salida.="<tr>";
			$salida.="	<td width='10%'>$font1 CODIGO</font></td>";
			$salida.="	<td width='25%'>$font1 DIAGNOSTICO</font></td>";
			$salida.="	<td width='10%'>$font1 TIPO DIAGNOSTICO</font></td>";
			$salida.="	<td width='5%'>$font1 PRIMARIO</font></td>";
			$salida.="</tr>";
			foreach($diagI as $nivel1)
			{
				$salida.="<tr>";
				$salida.="	<td>$font1 ".$nivel1['diagnostico_id']."</font></td>";
				$salida.="	<td>$font1 ".$nivel1['diagnostico_nombre']."</font></td>";
				$salida.="	<td>$font1 ".$nivel1['tipo_diag']."</font></td>";
				$p="";
				if($nivel1['sw_principal'])
					$p=" P ";
				
				$salida.="	<td>$font1 $p </font></td>";
				$salida.="</tr>";
			}
			$salida.="</table>";
		
			$HTML_WEB_PAGE.="		$salida";
			$HTML_WEB_PAGE.="		</LI>";
			$HTML_WEB_PAGE.="		</font>";
			$HTML_WEB_PAGE.="	</UL>";
		}
		
		$HTML_WEB_PAGE.="$font2 <b>DATOS DE LA EVOLUCION</b></font>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>DATOS DE LA EVOLUCION</b> </font><br>";
		foreach($datos_evolucion as $de)
			$datos_e.="$font1".strtoupper($de['descripcion_evolucion'])."</font>";	
		$HTML_WEB_PAGE.="		$datos_e";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>MEDICAMENTOS</b> </font><br>";
		foreach($medicamentos as $med)
			$medic="$font1".strtoupper($med['descripcion1'])."</font>";	
		$HTML_WEB_PAGE.="		$medic";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		$HTML_WEB_PAGE.="$font2 <b>DATOS DEL EGRESO</b></font>";
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>PLAN DE SEGUIMIENTO</b> </font><br>";
		foreach($plan_seg as $plan)
			$plan_s.="$font1".strtoupper($plan['plan_seguimiento'])."</font>";	
		$HTML_WEB_PAGE.="		$plan_s";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		if($diagE)
		{
			$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
			$HTML_WEB_PAGE.="		<LI>";
			$HTML_WEB_PAGE.="		 <b>$font1 DIAGNOSTICOS DE EGRESO</b></font> <br>";
			$salida="";

			$salida.="<table align='center' border='0' width='100%' class='modulo_table_list' cellspacing='0' cellpadding='0'>";
			$salida.="<tr>";
			$salida.="	<td width='10%'>$font1 CODIGO</font></td>";
			$salida.="	<td width='25%'>$font1 DIAGNOSTICO</font></td>";
			$salida.="	<td width='10%'>$font1 TIPO DIAGNOSTICO</font></td>";
			$salida.="	<td width='5%'>$font1 PRIMARIO</font></td>";
			$salida.="</tr>";
			foreach($diagE as $nivel1)
			{
				$salida.="<tr>";
				$salida.="	<td>$font1 ".$nivel1['diagnostico_id']."</font></td>";
				$salida.="	<td>$font1 ".$nivel1['diagnostico_nombre']."</font></td>";
				$salida.="	<td>$font1 ".$nivel1['tipo_diag']."</font></td>";
				$p="";
				if($nivel1['sw_principal'])
					$p=" P ";
				
				$salida.="	<td>$font1 $p </font></td>";
				$salida.="</tr>";
			}
			$salida.="</table>";
		
			$HTML_WEB_PAGE.="		$salida";
			$HTML_WEB_PAGE.="		</LI>";
			$HTML_WEB_PAGE.="	</UL>";
		}
		
		$HTML_WEB_PAGE.="	<UL TYPE='SQUARE'>";
		$HTML_WEB_PAGE.="		<LI>";
		$HTML_WEB_PAGE.="		$font1 <b>CAUSA DE SALIDA</b> </font><br>";
		$HTML_WEB_PAGE.="		$font1 ";
		foreach($datos_salida as $nivel1)
		{
			$causa.=$nivel1['causa'];
			$remision.=$nivel1['descripcion_remision'];
		}
		$HTML_WEB_PAGE.="TIPO CAUSA : ".$causa."<br>";
				
		if(!empty($remision))
			$HTML_WEB_PAGE.="  REMITIDO A : ".$remision."";
		
		$HTML_WEB_PAGE.="		</font>";
		$HTML_WEB_PAGE.="		</LI>";
		$HTML_WEB_PAGE.="	</UL>";
		
		$HTML_WEB_PAGE.= "<br>".$this->PiePaginaImprimir();
		
		$HTML_WEB_PAGE.="</BODY></HTML>";
		
		return $HTML_WEB_PAGE;
	}
	
	
	function CabeceraImprimir()
	{
	
		$edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
		
		
		$this->imprimir="<br>";
		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$this->imprimir.="<tr>\n";
    $this->imprimir.="<TD ALIGN=\"CENTER\" width=\"60%\"><FONT SIZE='5' FACE='arial'>RESUMEN EGRESO<br></FONT>";
		$this->imprimir.="<br></TD>\n";
		$this->imprimir.="</TR>\n";
    $this->imprimir.="</table>";
 		$this->imprimir.="<table width=\"100%\" align=\"center\" border=\"1\" class=\"modulo_table\">";
		$this->imprimir.="<TR>\n";
		
		$size=1;
		$this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\" COLSPAN=\"2\"><FONT SIZE='$size' FACE='arial'><B>PACIENTE:</B>&nbsp;".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</FONT></TD>\n";
    $this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='$size' FACE='arial'><B>IDENTIFICACION:</B>&nbsp;".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</FONT></TD>\n";
		$this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\" COLSPAN=\"2\"><FONT SIZE='$size' FACE='arial'><B>HC:</B>&nbsp;";
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
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><FONT SIZE='$size' FACE='arial'><B>FECHA DE NACIMIENTO:</B> ".$FechaNacimiento."</FONT></TD>";
		$this->imprimir.="<TD ALIGN=\"CENTER\" WIDTH=\"20%\"><FONT SIZE='$size' FACE='arial'><B>EDAD:</B>&nbsp;";
		$this->imprimir.=$edad['anos'].'&nbsp;A?os';
		$this->imprimir.="</FONT></TD>\n";
          
		$this->imprimir .="<TD ALIGN=\"CENTER\" WIDTH=\"20%\"><FONT SIZE='$size' FACE='arial'><B>SEXO:</B>&nbsp;";
		$this->imprimir.= $this->datosPaciente['sexo_id'];
		$this->imprimir.="</FONT></TD>\n";

		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><FONT SIZE='$size' FACE='arial'><B>TIPO AFILIADO:</B>&nbsp;&nbsp;".$this->Responsable[9]."</td>\n";
		
		$this->imprimir .= "</table>";
		$this->imprimir .= "</TD>";
		$this->imprimir .= "</TR>";          
		
		
		$this->imprimir .= "<TR>";
		$res = $this->datosPaciente['residencia_direccion'];
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\" COLSPAN=\"2\"><FONT SIZE='$size' FACE='arial'><B>RESIDENCIA:</B> ".$res."</FONT></TD>";

		if($this->datosPaciente['pais']=="COLOMBIA")
		{
			$direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
		}
		else
		{
			$direccion.= " - ".$this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
		}
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='$size' FACE='arial'>".$direccion."</FONT></TD>";

		$tel = $this->datosPaciente['residencia_telefono'];
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\" COLSPAN=\"2\"><FONT SIZE='$size' FACE='arial'><B>TELEFONO: </B>".$tel."</FONT></TD>";
		$this->imprimir .= "</TR>";

          
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='$size' FACE='arial'><B>NOMBRE ACOMPA?ANTE: </B></FONT></TD>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='$size' FACE='arial'><B>PARENTESCO: </B></FONT></TD>";
		$this->imprimir .= "<TD ALIGN=\"JUSTIFY\" COLSPAN=\"2\" WIDTH=\"25%\"><FONT SIZE='$size' FACE='arial'><B>TELEFONO: </B></FONT></TD>";
		$this->imprimir .= "</TR>";
          

    $this->imprimir.= "<TR>";
		$this->BuscarCamaActiva($this->ingreso);

		$this->GetFechaEgreso();
		
		$FechaI = $this->FechaStamp($this->DatosIngreso_Paciente['fecha_registro']);
		$HoraI = $this->HoraStamp($this->DatosIngreso_Paciente['fecha_registro']);
		
		
		$fe="";
		if (!empty($this->datosFE['fecha_registro']))
		{
			$FechaS = $this->FechaStamp($this->datosFE['fecha_registro']);
			$HoraS = $this->HoraStamp($this->datosFE['fecha_registro']);
			$fe=$FechaS." - ".$HoraS;
		}
		$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='$size' FACE='arial'><B>FECHA INGRESO:</B>&nbsp;&nbsp;".$FechaI." - ".$HoraI."</FONT></TD>";
		$this->imprimir.= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><FONT SIZE='$size' FACE='arial'><B>FECHA EGRESO:</B>&nbsp;&nbsp;".$fe."</FONT></TD>";
		$this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><FONT SIZE='$size' FACE='arial'><B>CAMA:</B>&nbsp;&nbsp;".$this->DatosCama['cama']."</FONT></TD>";
		$this->imprimir.= "</TR>";
		
          
		$servicio=$this->GetServicio($this->DatosIngreso_Paciente['departamento_actual']);
		$this->imprimir.= "<TR>";
		$this->imprimir .= "<td COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='$size' FACE='arial'><B>DEPARTAMENTO:</B>&nbsp;&nbsp;".$this->DatosIngreso_Paciente['departamento_actual']."  -  ".$this->DatosIngreso_Paciente['descripcion']."</FONT></TD>\n";
		$this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"60%\"><FONT SIZE='$size' FACE='arial'><B>SERVICIO:</B>&nbsp;&nbsp;".$servicio."</FONT></TD>\n";
		$this->imprimir .= "</tr>\n";
		
		
		$this->imprimir.= "<TR>";
		$this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><FONT SIZE='$size' FACE='arial'><B>CLIENTE:</B>&nbsp;&nbsp;".$this->Responsable[8]."</FONT></TD>\n";
		$this->imprimir .= "<TD COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"60%\"><FONT SIZE='$size' FACE='arial'><B>PLAN:</B>&nbsp;&nbsp;".$this->Responsable[4]."</FONT></TD>\n";
		$this->imprimir .= "</TR>\n";
		$this->imprimir.= "</table>";
		return $this->imprimir;
	}
	
	function ImpresionHistoria()
	{
		$this->salida='';
		$this->error='';
		$this->mensajeDeError='';
		return true;
	}
     
	function Error()
	{
		return $this->error;
	}

	function ErrorMsg()
	{
		return $this->mensajeDeError;
	}

	function GetSalida()
	{
		return $this->salida;
	}

	function GetImpresion()
	{
		if($this->realimprimir==2)
		{
      		return $this->imprimir;
		}
		else
		{
			return false;
		}
	}

	function GetInformacionEmpresa($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.tipo_id_tercero, b.id, b.razon_social, b.direccion, b.telefonos from departamentos as a, empresas as b where a.departamento='$departamento' and a.empresa_id=b.empresa_id;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0) {
               return false;
          }
		while(!$result->EOF)
		{
			$dato=$result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $dato;
	}
     
	function CargarVariables()
  {
     	$this->realimprimir=1;
          list($dbconn) = GetDBconn();
		$query = "SELECT MAX(A.evolucion_id) 
                    FROM hc_evoluciones AS A,
                         profesionales AS B 
                    WHERE A.ingreso='".$this->ingreso."'
                    AND A.usuario_id = B.usuario_id
                    AND B.tipo_profesional IN ('1','2');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
 		list($evolucion) = $result->FetchRow();
          $this->evolucion = $evolucion;
          
          if(!$evolucion)
          {
               $query = "SELECT MAX(evolucion_id)
                         FROM hc_evoluciones
                         WHERE ingreso='".$this->ingreso."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               list($evolucion) = $result->FetchRow();
               $this->evolucion = $evolucion;
          }

		if(!IncludeLib('datospaciente'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
      		return false;
          }
          
		if(!IncludeLib('historia_clinica'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
			return false;
          }

		if(!IncludeFile('classes/modules/hc_classmodules.class.php',true))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
			return false;
          }

          $this->Datosingreso = GetDatosIngreso($this->evolucion,$this->ingreso);

          $this->datosPaciente = GetDatosPaciente("","",$this->ingreso);

     	$this->EvolucionGeneral = GetDatosEvolucion($this->evolucion);

          $this->tipo_profesional = $this->GetTipoProfesional();
          
		$this->Datos_Ingreso();
 
          $this->GetDatosResponsable();
          
          $this->GetDatosProfesional();
          
          $this->User = $this->GetDatosUsuarioSistema(UserGetUID());
          
          return true;
     }
		 
	function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
         
          $sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
               FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
               WHERE
               a.plan_id = b.plan_id
               AND b.tercero_id = c.tercero_id
               AND b.tercero_id = c.tercero_id
               AND a.numerodecuenta = ".$this->EvolucionGeneral['numerodecuenta'].";";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql.$dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
		if(!$resultado->EOF)
		{
			$this->Responsable = $resultado->FetchRow();
		}
		return $this->Responsable;
	}

     function GetTipoProfesional()
     {
          list($dbconn) = GetDBconn();
          $query = "SELECT tipo_profesional
               FROM profesionales_usuarios a, profesionales b
               WHERE a.tipo_tercero_id=b.tipo_id_tercero and
               a.tercero_id=b.tercero_id and
               a.usuario_id=".UserGetUID()."";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
          list($this->tipo_profesional) = $result->FetchRow();
          return $this->tipo_profesional;
     }

	function GetServicio($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.descripcion from departamentos as a, servicios as b where a.servicio=b.servicio and a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}

	function GetDepartamento($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select a.descripcion from departamentos as a where a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
	}

	function GetDatosProfesional()
	{
          list($dbconn) = GetDBconn();
          $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion
               FROM profesionales AS A,
               	profesionales_usuarios AS E
               LEFT JOIN profesionales_especialidades AS B
               ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
               LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
               WHERE A.usuario_id =".$this->EvolucionGeneral['usuario_id']."
               AND A.usuario_id = E.usuario_id
               AND E.tercero_id = A.tercero_id
               AND E.tipo_tercero_id = A.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
	}

     function Consulta_NotasMedicas()
	{
		list($dbconn) = GetDBconn();
		$query= "SELECT A.ingreso,
					 A.fecha_registro, A.evolucion_id,
					 A.nota_medica, B.nombre, B.usuario
				FROM notas_medicas AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$this->ingreso."'
				AND B.usuario_id=A.usuario_id
				ORDER BY fecha_registro DESC;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$resulta->EOF)
			{
				$datosfila=$resulta->GetRowAssoc($ToUpper = false);
				list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$datosfila[hora]=$hora.":".$min;
				$fecha = $fecha;
				$notas[$fecha][]=$datosfila;
				$resulta->MoveNext();
			}
		}
		return $notas;
	}

     
	function Datos_Ingreso()
	{
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT A.fecha_registro, A.departamento, A.departamento_actual,
                           A.fecha_cierre, B.fecha, B.fecha_cierre AS cierre_evolucion,
                           C.descripcion 
                    FROM ingresos AS A
                    LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
                    LEFT JOIN departamentos AS C ON (A.departamento_actual = C.departamento)
                    WHERE A.ingreso='".$this->ingreso."'
                    AND B.evolucion_id = (SELECT MAX(evolucion_id) 
                    				  FROM hc_evoluciones 
                                          WHERE ingreso = '".$this->ingreso."' 
                                          AND fecha_cierre IS NOT NULL);";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosIngreso_Paciente = $result->FetchRow();
			return $this->DatosIngreso_Paciente;
		}
	}
     
     function BuscarCamaActiva($ingreso)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT fecha_egreso, cama
          		FROM movimientos_habitacion
				WHERE ingreso='".$this->ingreso."';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosCama = $result->FetchRow();
			return true;
		}
     }
     
		 function GetFechaEgreso()
     {
			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;
			
			$query = "SELECT fecha_registro
								FROM ingresos_salidas
								WHERE ingreso=".$this->ingreso.";";
			
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->datosFE = $result->FetchRow();
				return true;
			}
     }
		 
		 
     /*		GetDatosUsuarioSistema
     *
     *		Obtiene el nombre de usuario del sistema
     *
     *		@Author Rosa Maria Angel
     *		@access Public
     *		@return bool
     *		@param integer => usuario_id
     */
     function GetDatosUsuarioSistema($usuario)
     {
          $pfj=$this->frmPrefijo;
          $query = "SELECT usuario,
                    nombre
                    FROM system_usuarios
                    WHERE usuario_id = $usuario";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurri? un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $DatosUser[] = $data;
                    }
                    return $DatosUser;
               }
          }
     }/// GetDatosUsuarioSistema


	function IniciarImprimir()
	{
		$this->CabeceraImprimir();
          $vista_Nota = $this->Vista_NotaMedica();
          list($dbconn) = GetDBconn();
		$query = "select distinct submodulo, paso from 
				(SELECT  a.submodulo, a.secuencia, a.paso
				FROM historias_clinicas_templates a, hc_evoluciones as b, system_hc_submodulos as c
				WHERE b.ingreso =".$this->EvolucionGeneral['ingreso']."
				and b.hc_modulo=a.hc_modulo and a.submodulo=c.submodulo and c.sw_imprime='1'
				ORDER BY a.paso,a.secuencia) as a ORDER BY a.paso;";	
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
		}

		if ($result->EOF) 
		{
			$this->imprimir .= "NO HAY DATOS VALIDOS PARA IMPRESION";
			return true;
		}
		else
		{
               while(!$result->EOF)
               {
                         $var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();					
               }
					
               foreach($var as $k => $v)
               {						
                    $submodulo_obj=IncluirSubModuloHC($v[submodulo]);
                    if(!is_object($submodulo_obj)){
                         $this->error = "Error al cargar el submodulo";
                         $this->mensajeDeError = $submodulo_obj;
                    }
                    else
                    {
                         $submodulo_obj->InicializarSubmodulo($this->EvolucionGeneral,'',$this->datosPaciente,'','','',$this->paso,'frm_'.$v[submodulo],'','','','');
                         if(method_exists($submodulo_obj,'GetReporte_Html'))
                         {
                              $dato1=$submodulo_obj->GetReporte_Html();
                              if($dato1!=1)
                              {
                                   $this->realimprimir=2;
                                   $this->imprimir.=$dato1;
                              }
                         }
                    }
                    unset($dato1);
                    unset($submodulo_obj);
               }
               $result->close();
               $this->imprimir.=$vista_Nota;
               if($this->realimprimir==2)
               {
                    $this->PiePaginaImprimir();
               }
		}
		$this->GetImpresion();
		return true;
     }

	function FechaStamp($fecha)
	{
		if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
		}
	}
	
	function PiePaginaImprimir()
	{
		$this->imprimir = "<BR><TABLE BORDER='0'>";
		$largo = strlen($this->datosProfesional['nombre']);
		if($largo < '5')
		{
			$largo = $largo + '12';
		}
    
		$largo = $largo + '16';
		for ($l=0; $l<$largo; $l++)
		{
			$cad = $cad.'_';
		}
          
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>$cad</FONT></TD>";
		$this->imprimir .= "</TR>";
		$this->imprimir .= "<TR>";
		$this->imprimir .= "<TD WIDTH='20%'><FONT SIZE='2' FACE='arial'>PROFESIONAL:&nbsp;&nbsp;&nbsp;".$this->datosProfesional['nombre']."</FONT></TD>";
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
    $this->imprimir .= "<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimi?:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
		$this->imprimir .= "<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresi?n :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$this->imprimir .= "</TR>";
		$this->imprimir.= "</table>";
		
		return $this->imprimir;
	}

	function HoraStamp($hora)
	{
		$hor = strtok ($hora," ");
		for($l=0;$l<4;$l++)
		{
				$time[$l]=$hor;
				$hor = strtok (":");
		}

		$x = explode (".",$time[3]);
		return  $time[1].":".$time[2].":".$x[0];
	}
     
  function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}
	
}
?>
