<?php

/**
* Submodulo Consulta del Triage.
*
* Submodulo para manejar la impresion en pantalla de los reportes del Triage.
* @author Tizziano Perea O. <tperea@ipsoft-sa.com>
* @version 1.0
* @package SIIS
* $Id: hc_Consulta_Triage_HTML.php,v 1.9 2005/08/04 13:40:30 tizziano Exp $
*/


/**
* Consulta_Triage
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de Consulta Triage.
*/

class Consulta_Triage_HTML extends Consulta_Triage
{

	function Consulta_Triage_HTML()
	{
	    $this->Consulta_Triage();//constructor del padre
       	return true;
	}


	function frmConsulta()
	{
          $pfj=$this->frmPrefijo;
		IncludeLib('funciones_admision');
		$Pacientes_Remitidos = $this->Pacientes_Remitidos();
		$Datos_Triage = $this->Datos_Triage();
          
          $Triage_ID = $Datos_Triage[0][triage_id];

		if (!empty($Pacientes_Remitidos))
		{
          	$this->salida .= "<br>";
			$this->salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td colspan =\"5\">CENTRO DE REMISION</td>";
			$this->salida .= "<td>CODIGO REMISION</td>";
			$this->salida .= "<td>FECHA REMISION</td>";
			$this->salida .= "<td>HORA REMISION</td>";
			$this->salida .= "<td>DIAGNOSTICO</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr class='modulo_list_claro'>";
			$this->salida .= "<td align=\"center\" colspan =\"5\">".$Pacientes_Remitidos[0][descripcion]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][numero_remision]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][fecha_remision]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][hora_remision]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][diagnostico_nombre]."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr>";
			$this->salida .= "<td width=\"15%\" class=\"modulo_table_list_title\" align=\"center\">OBSERVACION</td>";
			$this->salida .= "<td width=\"85%\" align=\"justify\" class=\"modulo_list_oscuro\">".$Pacientes_Remitidos[0][observacion]."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";

			$this->salida .= "<br>";
			$this->salida .= "<br>";
		}

		if(!empty($Datos_Triage[0]))
		{
          	$this->salida .= "<br>";
			$this->salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			
               $this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"6\" width=\"50%\" align=\"center\" class=\"modulo_table_list_title\">REPORTE DE TRIAGE</td>";
			$this->salida .= "</tr>";

			list($fecha,$hora) = explode(" ",$Datos_Triage[0][hora_llegada]);
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$hora=$hora.":".$min;
			if($fecha == date("Y-m-d"))
			{
				$fecha = "HOY";
			}
			elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
			{
				$fecha = "AYER";
			}
			else
			{
				$fecha = $fecha;
			}

			$this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"5\" width=\"50%\" align=\"center\" class=\"modulo_table_list_title\">FECHA DE LLEGADA</td>";
			$this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_table_list_title\">HORA DE LLEGADA</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"5\" width=\"50%\" align=\"center\" class=\"modulo_list_claro\">".$fecha."</td>";
			$this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">".$hora."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"5\" width=\"50%\" class=\"modulo_table_list_title\">DEPARTAMENTO</td>";
			$this->salida .= "<td width=\"50%\" class=\"modulo_table_list_title\">CLASIFICACION</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$estilo=ColorTriage($Datos_Triage[0][nivel_triage_id]);
			$this->salida .= "<td  align=\"center\" colspan =\"5\" width=\"50%\" class=\"modulo_list_oscuro\">".$Datos_Triage[0][descripcion]."</td>";
			$this->salida .= "<td  align=\"center\" width=\"50%\" class=\"$estilo\">NIVEL".' - '.$Datos_Triage[0][nivel_triage_id]."</td>";
			$this->salida .= "</tr>";

			if(!empty($Datos_Triage[0][motivo_consulta]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">MOTIVO DE CONSULTA</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][motivo_consulta]."</td>";
				$this->salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][observacion_medico]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">OBSERVACION MEDICO</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_oscuro\">".$Datos_Triage[0][observacion_medico]."</td>";
				$this->salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][observacion_enfermera]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">OBSERVACION ENFERMERA</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][observacion_enfermera]."</td>";
				$this->salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][impresion_diagnostica]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">IMPRESION DIAGNOSTICA</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][impresion_diagnostica]."</td>";
				$this->salida .= "</tr>";
			}

			$this->salida .= "</table>";

			if (!empty($Datos_Triage[0][diagnostico_id]))
			{
				$this->salida.="<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
				$this->salida.="<td class=\"modulo_list_oscuro\" width=\"65%\">";
				$this->salida.="<table width=\"100%\">";
				for($i=0;$i<sizeof($Datos_Triage);$i++)
				{
					if($i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"left\" width=\"15%\">".$Datos_Triage[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\" width=\"85%\">".$Datos_Triage[$i][diagnostico_nombre]."</td>";
					$this->salida.="<tr>";
				}
 				$this->salida.="</table>";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}

			$causas = $this->BuscarCausas($Triage_ID);
			if (!empty ($causas))
			{
				$this->salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">CAUSA PROBABLE</td>";
				$this->salida .= "</tr>";
				for($i=0; $i<sizeof($causas);)
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$estilo=ColorTriage($causas[$i][nivel_triage_id]);
					$this->salida .= "<td class=\"$estilo\" width=\"15%\" align=\"center\">NIVEL ".$causas[$i][nivel_triage_id]."</td>";
					$this->salida .= "<td width=\"75%\">";
					$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
					$d=$i;
					while($causas[$i][nivel_triage_id]==$causas[$d][nivel_triage_id])
					{
						$estiloClaro=ColorTriageClaro($causas[$i][nivel_triage_id]);
						$this->salida .= "<tr class=\"modulo_list_claro\">";
						$this->salida .= "<td class=\"$estiloClaro\">".$causas[$d][descripcion]."</td>";
						$this->salida .= "</tr>";
						$d++;
					}
						$i=$d;
						$this->salida .= "</table>";
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
				}
				$this->salida .= "</table>";
			}

			$sig=$this->BuscarSignosVitales($Triage_ID);
			if(!empty($sig))
			{
				$this->salida .= "<table width=\"100%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "<tr >";
				$this->salida .= "<td align=\"center\" colspan=\"8\" class=\"hc_table_submodulo_list_title\">SIGNOS VITALES: </td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "<td>F.C.</td>";
				$this->salida .= "<td>F.R.</td>";
				$this->salida .= "<td>PESO(Kg)</td>";
				$this->salida .= "<td>T.A.</td>";
				$this->salida .= "<td>TEMP.</td>";
				$this->salida .= "<td>EVA.</td>";
				$this->salida .= "<td>SAT O<sub>2</sub></td>";
				$this->salida .= "<td>GLASGOW</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr align=\"center\">";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fc]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fr]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_peso]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_temperatura]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[evaluacion_dolor]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[sato2]."</td>";
				$glasgow = ($sig[respuesta_motora_id] + $sig[respuesta_verbal_id] + $sig[apertura_ocular_id]);
				if (empty($glasgow))
				{
					$glasgow = '--';
				}
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$glasgow."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
               $this->salida .= "<br>";
		}
		else
		{
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTE DE TRIAGE<BR>";
		}
		return true;
	}

     function frmHistoria()
	{
          $pfj=$this->frmPrefijo;
		IncludeLib('funciones_admision');
		$Pacientes_Remitidos = $this->Pacientes_Remitidos();
		$Datos_Triage = $this->Datos_Triage();
          
          $Triage_ID = $Datos_Triage[0][triage_id];

		/*if (!empty($Pacientes_Remitidos))
		{
               $salida .= "<br>";
			$salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$salida .= "<tr class=\"modulo_table_list_title\">";
			$salida .= "<td colspan =\"5\">CENTRO DE REMISION</td>";
			$salida .= "<td>CODIGO REMISION</td>";
			$salida .= "<td>FECHA REMISION</td>";
			$salida .= "<td>HORA REMISION</td>";
			$salida .= "<td>DIAGNOSTICO</td>";
			$salida .= "</tr>";
			$salida .= "<tr class='modulo_list_claro'>";
			$salida .= "<td align=\"center\" colspan =\"5\">".$Pacientes_Remitidos[0][descripcion]."</td>";
			$salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][numero_remision]."</td>";
			$salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][fecha_remision]."</td>";
			$salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][hora_remision]."</td>";
			$salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][diagnostico_nombre]."</td>";
			$salida .= "</tr>";
			$salida .= "</table>";
			$salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$salida .= "<tr>";
			$salida .= "<td width=\"15%\" class=\"modulo_table_list_title\" align=\"center\">OBSERVACION</td>";
			$salida .= "<td width=\"85%\" align=\"justify\" class=\"modulo_list_oscuro\">".$Pacientes_Remitidos[0][observacion]."</td>";
			$salida .= "</tr>";
			$salida .= "</table>";

			$salida .= "<br>";
		}

		if(!empty($Datos_Triage[0]))
		{
               $salida .= "<br>";
			$salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
               
               $salida .= "<tr>";
			$salida .= "<td colspan =\"6\" width=\"50%\" align=\"center\" class=\"modulo_table_list_title\">REPORTE DE TRIAGE</td>";
			$salida .= "</tr>";

			list($fecha,$hora) = explode(" ",$Datos_Triage[0][hora_llegada]);
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$hora=$hora.":".$min;
			if($fecha == date("Y-m-d"))
			{
				$fecha = "HOY";
			}
			elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
			{
				$fecha = "AYER";
			}
			else
			{
				$fecha = $fecha;
			}

			$salida .= "<tr>";
			$salida .= "<td colspan =\"5\" width=\"50%\" align=\"center\" class=\"modulo_table_list_title\">FECHA DE LLEGADA</td>";
			$salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_table_list_title\">HORA DE LLEGADA</td>";
			$salida .= "</tr>";
			$salida .= "<tr>";
			$salida .= "<td colspan =\"5\" width=\"50%\" align=\"center\" class=\"modulo_list_claro\">".$fecha."</td>";
			$salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">".$hora."</td>";
			$salida .= "</tr>";
			$salida .= "<tr>";
			$salida .= "<td colspan =\"5\" width=\"50%\" class=\"modulo_table_list_title\">DEPARTAMENTO</td>";
			$salida .= "<td width=\"50%\" class=\"modulo_table_list_title\">CLASIFICACION</td>";
			$salida .= "</tr>";
			$salida .= "<tr>";
			$estilo=ColorTriage($Datos_Triage[0][nivel_triage_id]);
			$salida .= "<td  align=\"center\" colspan =\"5\" width=\"50%\" class=\"modulo_list_oscuro\">".$Datos_Triage[0][descripcion]."</td>";
			$salida .= "<td  align=\"center\" width=\"50%\" class=\"$estilo\">NIVEL".' - '.$Datos_Triage[0][nivel_triage_id]."</td>";
			$salida .= "</tr>";

			if(!empty($Datos_Triage[0][motivo_consulta]))
			{
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">MOTIVO DE CONSULTA</td>";
				$salida .= "</tr>";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][motivo_consulta]."</td>";
				$salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][observacion_medico]))
			{
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">OBSERVACION MEDICO</td>";
				$salida .= "</tr>";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_oscuro\">".$Datos_Triage[0][observacion_medico]."</td>";
				$salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][observacion_enfermera]))
			{
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">OBSERVACION ENFERMERA</td>";
				$salida .= "</tr>";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][observacion_enfermera]."</td>";				$salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][impresion_diagnostica]))
			{
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">IMPRESION DIAGNOSTICA</td>";
				$salida .= "</tr>";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][impresion_diagnostica]."</td>";
				$salida .= "</tr>";
			}

			$salida .= "</table>";

			if (!empty($Datos_Triage[0][diagnostico_id]))
			{
				$salida.="<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
				$salida.="<tr>";
				$salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
				$salida.="<td class=\"modulo_list_oscuro\" width=\"65%\">";
				$salida.="<table width=\"100%\">";
				for($i=0;$i<sizeof($Datos_Triage);$i++)
				{
					if($i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$salida.="<tr class=\"$estilo\">";
					$salida.="<td align=\"left\" width=\"15%\">".$Datos_Triage[$i][diagnostico_id]."</td>";
					$salida.="<td align=\"left\" width=\"85%\">".$Datos_Triage[$i][diagnostico_nombre]."</td>";
					$salida.="<tr>";
				}
 				$salida.="</table>";
				$salida .= "</td>";
				$salida .= "</tr>";
				$salida .= "</table>";
			}

			$causas = $this->BuscarCausas($Triage_ID);
			if (!empty ($causas))
			{
				$salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
				$salida .= "<tr>";
				$salida .= "<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">CAUSA PROBABLE</td>";
				$salida .= "</tr>";
				for($i=0; $i<sizeof($causas);)
				{
					$salida .= "<tr class=\"modulo_list_oscuro\">";
					$estilo=ColorTriage($causas[$i][nivel_triage_id]);
					$salida .= "<td class=\"$estilo\" width=\"15%\" align=\"center\">NIVEL ".$causas[$i][nivel_triage_id]."</td>";
					$salida .= "<td width=\"75%\">";
					$salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
					$d=$i;
					while($causas[$i][nivel_triage_id]==$causas[$d][nivel_triage_id])
					{
						$estiloClaro=ColorTriageClaro($causas[$i][nivel_triage_id]);
						$salida .= "<tr class=\"modulo_list_claro\">";
						$salida .= "<td class=\"$estiloClaro\">".$causas[$d][descripcion]."</td>";
						$salida .= "</tr>";
						$d++;
					}
						$i=$d;
						$salida .= "</table>";
						$salida .= "</td>";
						$salida .= "</tr>";
				}
				$salida .= "</table>";
			}*/

			$sig=$this->BuscarSignosVitales($Triage_ID);
			if(!empty($sig))
			{
				$salida .= "<table width=\"100%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$salida .= "<tr >";
				$salida .= "<td align=\"center\" colspan=\"8\" class=\"hc_table_submodulo_list_title\">REPORTE DE SIGNOS VITALES TRIAGE</td>";
				$salida .= "</tr>";
				$salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
				$salida .= "<td>F.C.</td>";
				$salida .= "<td>F.R.</td>";
				$salida .= "<td>PESO(Kg)</td>";
				$salida .= "<td>T.A.</td>";
				$salida .= "<td>TEMP.</td>";
				$salida .= "<td>EVA.</td>";
				$salida .= "<td>SAT O<sub>2</sub></td>";
				$salida .= "<td>GLASGOW</td>";
				$salida .= "</tr>";
				$salida .= "<tr align=\"center\">";
				$salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fc]."</td>";
				$salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fr]."</td>";
				$salida .= "<td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_peso]."</td>";
				$salida .= "<td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
				$salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_temperatura]."</td>";
				$salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[evaluacion_dolor]."</td>";
				$salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[sato2]."</td>";
				$glasgow = ($sig[respuesta_motora_id] + $sig[respuesta_verbal_id] + $sig[apertura_ocular_id]);
				if (empty($glasgow))
				{
					$glasgow = '--';
				}
				$salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$glasgow."</td>";
				$salida .= "</tr>";
				$salida .= "</table>";
			}
               $salida .= "<br>";
		//}FIN if(!empty($Datos_Triage[0]))
		return $salida;
	}

     
     function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align='center' class=\"label_error\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}


	function frmForma()
	{
  		$pfj=$this->frmPrefijo;
		IncludeLib('funciones_admision');
		$Pacientes_Remitidos = $this->Pacientes_Remitidos();
		$Datos_Triage = $this->Datos_Triage();

		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('REPORTE DE TRIAGE');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		
          $Triage_ID = $Datos_Triage[0][triage_id];

		if (!empty($Pacientes_Remitidos))
		{
			$this->salida .= "<table align=\"center\" width=\"100%\" border=\"1\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td colspan =\"5\">CENTRO DE REMISION</td>";
			$this->salida .= "<td>CODIGO REMISION</td>";
			$this->salida .= "<td>FECHA REMISION</td>";
			$this->salida .= "<td>HORA REMISION</td>";
			$this->salida .= "<td>DIAGNOSTICO</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr class='modulo_list_claro'>";
			$this->salida .= "<td align=\"center\" colspan =\"5\">".$Pacientes_Remitidos[0][descripcion]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][numero_remision]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][fecha_remision]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][hora_remision]."</td>";
			$this->salida .= "<td align=\"center\">".$Pacientes_Remitidos[0][diagnostico_nombre]."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "<table align=\"center\" width=\"100%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "<tr>";
			$this->salida .= "<td width=\"15%\" class=\"modulo_table_list_title\" align=\"center\">OBSERVACION</td>";
			$this->salida .= "<td width=\"85%\" align=\"justify\" class=\"modulo_list_oscuro\">".$Pacientes_Remitidos[0][observacion]."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";

			$this->salida .= "<br>";
			$this->salida .= "<br>";
		}

		if(!empty($Datos_Triage[0]))
		{
			$this->salida .= "<table align=\"center\" width=\"80%\" border=\"1\" class=\"modulo_table_list\">\n";

			list($fecha,$hora) = explode(" ",$Datos_Triage[0][hora_llegada]);
			list($ano,$mes,$dia) = explode("-",$fecha);
			list($hora,$min) = explode(":",$hora);
			$hora=$hora.":".$min;
			if($fecha == date("Y-m-d"))
			{
				$fecha = "HOY";
			}
			elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y"))))
			{
				$fecha = "AYER";
			}
			else
			{
				$fecha = $fecha;
			}

			$this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"5\" width=\"50%\" align=\"center\" class=\"modulo_table_list_title\">FECHA DE LLEGADA</td>";
			$this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_table_list_title\">HORA DE LLEGADA</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"5\" width=\"50%\" align=\"center\" class=\"modulo_list_claro\">".$fecha."</td>";
			$this->salida .= "<td align=\"center\" width=\"50%\" class=\"modulo_list_claro\">".$hora."</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan =\"5\" width=\"50%\" class=\"modulo_table_list_title\">DEPARTAMENTO</td>";
			$this->salida .= "<td width=\"50%\" class=\"modulo_table_list_title\">CLASIFICACION</td>";
			$this->salida .= "</tr>";
			$this->salida .= "<tr>";
			$estilo=ColorTriage($Datos_Triage[0][nivel_triage_id]);
			$this->salida .= "<td  align=\"center\" colspan =\"5\" width=\"50%\" class=\"modulo_list_oscuro\">".$Datos_Triage[0][descripcion]."</td>";
			$this->salida .= "<td  align=\"center\" width=\"50%\" class=\"$estilo\">NIVEL".' - '.$Datos_Triage[0][nivel_triage_id]."</td>";
			$this->salida .= "</tr>";

			if(!empty($Datos_Triage[0][motivo_consulta]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">MOTIVO DE CONSULTA</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][motivo_consulta]."</td>";
				$this->salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][observacion_medico]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">OBSERVACION MEDICO</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_oscuro\">".$Datos_Triage[0][observacion_medico]."</td>";
				$this->salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][observacion_enfermera]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">OBSERVACION ENFERMERA</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][observacion_enfermera]."</td>";
				$this->salida .= "</tr>";
			}

			if(!empty($Datos_Triage[0][impresion_diagnostica]))
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"center\" class=\"modulo_table_list_title\">IMPRESION DIAGNOSTICA</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"6\"  align=\"left\" class=\"modulo_list_claro\">".$Datos_Triage[0][impresion_diagnostica]."</td>";
				$this->salida .= "</tr>";
			}

			$this->salida .= "</table>";

			if (!empty($Datos_Triage[0][diagnostico_id]))
			{
				$this->salida.="<table align=\"center\" width=\"80%\" border=\"1\" class=\"modulo_table_list\">\n";
				$this->salida.="<tr>";
				$this->salida.="<td class=\"modulo_table_list_title\" align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
				$this->salida.="<td class=\"modulo_list_oscuro\" width=\"65%\">";
				$this->salida.="<table width=\"100%\">";
				for($i=0;$i<sizeof($Datos_Triage);$i++)
				{
					if($i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"left\" width=\"15%\">".$Datos_Triage[$i][diagnostico_id]."</td>";
					$this->salida.="<td align=\"left\" width=\"85%\">".$Datos_Triage[$i][diagnostico_nombre]."</td>";
					$this->salida.="<tr>";
				}
 				$this->salida.="</table>";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}

			$causas = $this->BuscarCausas($Triage_ID);
			if (!empty ($causas))
			{
				$this->salida .= "<table align=\"center\" width=\"80%\" border=\"1\" class=\"modulo_table_list\">\n";
				$this->salida .= "<tr>";
				$this->salida .= "<td colspan=\"2\" class=\"hc_table_submodulo_list_title\">CAUSA PROBABLE</td>";
				$this->salida .= "</tr>";
				for($i=0; $i<sizeof($causas);)
				{
					$this->salida .= "<tr class=\"modulo_list_oscuro\">";
					$estilo=ColorTriage($causas[$i][nivel_triage_id]);
					$this->salida .= "<td class=\"$estilo\" width=\"15%\" align=\"center\">NIVEL ".$causas[$i][nivel_triage_id]."</td>";
					$this->salida .= "<td width=\"75%\">";
					$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
					$d=$i;
					while($causas[$i][nivel_triage_id]==$causas[$d][nivel_triage_id])
					{
						$estiloClaro=ColorTriageClaro($causas[$i][nivel_triage_id]);
						$this->salida .= "<tr class=\"modulo_list_claro\">";
						$this->salida .= "<td class=\"$estiloClaro\">".$causas[$d][descripcion]."</td>";
						$this->salida .= "</tr>";
						$d++;
					}
						$i=$d;
						$this->salida .= "</table>";
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
				}
				$this->salida .= "</table>";
			}

			$sig=$this->BuscarSignosVitales($Triage_ID);
			if(!empty($sig))
			{
				$this->salida .= "<table width=\"80%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "<tr >";
				$this->salida .= "<td align=\"center\" colspan=\"8\" class=\"hc_table_submodulo_list_title\">SIGNOS VITALES: </td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "<td>F.C.</td>";
				$this->salida .= "<td>F.R.</td>";
				$this->salida .= "<td>PESO(Kg)</td>";
				$this->salida .= "<td>T.A.</td>";
				$this->salida .= "<td>TEMP.</td>";
				$this->salida .= "<td>EVA.</td>";
				$this->salida .= "<td>SAT O<sub>2</sub></td>";
				$this->salida .= "<td>GLASGOW</td>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr align=\"center\">";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fc]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_fr]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_peso]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"15%\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[signos_vitales_temperatura]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[evaluacion_dolor]."</td>";
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$sig[sato2]."</td>";
				$glasgow = ($sig[respuesta_motora_id] + $sig[respuesta_verbal_id] + $sig[apertura_ocular_id]);
				if (empty($glasgow))
				{
					$glasgow = '--';
				}
				$this->salida .= "<td class=\"modulo_list_claro\" width=\"10%\">".$glasgow."</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
			}
		}
		else
		{
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTE DE TRIAGE<BR>";
		}$this->frmHistoria();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
}

?>
