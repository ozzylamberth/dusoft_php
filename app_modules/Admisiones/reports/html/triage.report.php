<?php

/**
 * $Id: triage.report.php,v 1.11 2005/07/08 19:18:22 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */


class triage_report
{ 
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function triage_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}


	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'',
												'logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}
    //FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
    //   print_r($this->datos);
			IncludeLib("funciones_admision");
			$dat=DatosTriage($this->datos['triage_id']);
      if(empty($this->datos['nombre']))
      {
        $this->datos['nombre']=$dat['nombre_paciente'];
      }
      
     //print_r($dat);
			$Salida .= "				          <p align=\"center\" class=\"titulo2\">HOJA TRIAGE</p>";
   		$Salida .= "				          <p align=\"center\" class=\"normal_10N\">DEPARTAMENTO DE SERVICIOS DE ".$dat['descripcion']."</p>";
			$Salida .= "			      <table width=\"100%\" border=\"1\" align=\"center\">";
			/*$Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" width=\"13%\" class=\"normal_10\">Institución que Remite: </td>";
			$Salida .= "				          <td colspan=\"3\" width=\"75%\" class=\"normal_10\">".$this->datos['empresa']."</td>";
			$Salida .= "				       </tr>";*/
			$Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\" width=\"13%\">Identificación: </td>";
			$Salida .= "				          <td width=\"17%\" class=\"normal_10\">".$dat['tipo_id_paciente']." ".$dat['paciente_id']."</td>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\" width=\"10%\">Paciente: </td>";
		  $Salida .= "				          <td class=\"normal_10\" width=\"30%\">".$this->datos['nombre']."</td>";
     
    //  $Salida .= "				          <td class=\"normal_10\" width=\"30%\">".$dat['primer_nombre']."".$dat['segundo_nombre']."".$dat['primer_apellido']."".$dat['segundo_apellido']."</td>";

      
      
			$EdadArr=CalcularEdad($dat['fecha_nacimiento'],'');
			$Edad=$EdadArr['edad_aprox'];
			$Salida .= "				          <td align=\"left\" class=\"normal_10\" width=\"5%\">Edad: </td>";
			$Salida .= "				          <td class=\"normal_10\" width=\"10%\">".$Edad."</td>";
			$Salida .= "				       </tr>";
      
      $Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\" width=\"13%\">Plan: </td>";
			$Salida .= "				          <td width=\"17%\" class=\"normal_10\">".$dat['plan_descripcion']."</td>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\" width=\"10%\">Tipo Afiliacion: </td>";
		  $Salida .= "				          <td class=\"normal_10\" width=\"30%\">".$dat['tipo_afiliado_nombre']."</td>";
     
      $Salida .= "				          <td align=\"left\" class=\"normal_10\" width=\"5%\">Rango: </td>";
			$Salida .= "				          <td class=\"normal_10\" width=\"10%\">".$dat['rango']."</td>";
			$Salida .= "				       </tr>";
      
      
			$Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" width=\"13%\" class=\"normal_10\">Profesional: </td>";
			$Salida .= "				          <td colspan=\"5\" width=\"75%\" class=\"normal_10\">".$dat['nombre']."</td>";
			$Salida .= "				       </tr>";

			$Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Clasificación: </td>";
			$Salida .= "				          <td class=\"normal_10\">Nivel ".$dat[nivel_triage_id]." &nbsp;&nbsp;".$this->NombreColorTriage($dat[nivel_triage_id])."</td>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Fecha: </td>";
			$Salida .= "				          <td class=\"normal_10\" colspan=\"3\">".FechaStamp($dat[hora_llegada])." ".HoraStamp($dat[hora_llegada])."</td>";
			$Salida .= "				       </tr>";
			$Salida .= "				       <tr >";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Causas Probables: </td>";
			$Salida .= "				          <td class=\"normal_10\" colspan=\"5\">";
			//$Salida .= "				         <tr align=\"center\" class=\"normal_10\">";
			//$Salida .= "                   <td width=\"10%\">NIVEL</td>";
			//$Salida .= "                   <td width=\"90%\" colspan=\"2\">CAUSAS PROBABLES</td>";
			//$Salida .= "				         </tr>";
			$causas=BuscarCausas($this->datos['triage_id']);
			for($i=0; $i<sizeof($causas);)
			{
					$Salida .= "			      	 <table width=\"100%\" border=\"1\" align=\"center\">";
					$Salida .= "				         <tr>";
					$Salida .= "                   <td width=\"15%\" class=\"normal_10\" align=\"center\">Nivel ".$causas[$i][nivel_triage_id]."</td>";
					$Salida .= "                   <td width=\"75%\">";
					$Salida .= "			      	 			 <table width=\"100%\" border=\"1\" align=\"center\">";
					$d=$i;
					while($causas[$i][nivel_triage_id]==$causas[$d][nivel_triage_id])
					{
							$Salida .= "				         			 <tr  class=\"normal_10\">";
							$Salida .= "                  			 <td >".$causas[$d][descripcion]."</td>";
							$Salida .= "				         			 </tr>";
							$d++;
					}
					$i=$d;
					$Salida .= "			   			       </table>";
					$Salida .= "                   </td>";
					$Salida .= "				         </tr>";
					$Salida .= "			   			 </table>";
			}
			$Salida .= "              </td>";
			$Salida .= "				       </tr>";
			$Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Motivo Consulta: </td>";
			$Salida .= "				          <td class=\"normal_10\" colspan=\"5\">".$dat[motivo_consulta]."</td>";
			$Salida .= "				       </tr>";
			$Salida .= "				       <tr>";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Signos Vitales: </td>";
			$Salida .= "				          <td colspan=\"5\">";
			$sig=BuscarSignosVitalesTriage($this->datos['triage_id']);
			$glas=$sig[respuesta_motora_id] + $sig[respuesta_verbal_id]+ $sig[apertura_ocular_id];
			if(empty($glas)){   $glas='--';  }
			$Salida .= "			      	 <table width=\"100%\" border=\"1\" align=\"center\"";
			$Salida .= "				         <tr class=\"normal_10\">";
			$Salida .= "				         <td align=\"center\">F.C.</td>";
			$Salida .= "				         <td align=\"center\">F.R.</td>";
			$Salida .= "				         <td align=\"center\">PESO(Kg)</td>";
			$Salida .= "				         <td align=\"center\">T.A.</td>";
			$Salida .= "				         <td align=\"center\">TEMP.</td>";
			$Salida .= "				         <td align=\"center\">EVA.</td>";
			$Salida .= "				         <td align=\"center\">GLASGOW</td>";
			$Salida .= "				         <td align=\"center\">SAT02</td>";
			$Salida .= "				         </tr>";
			$Salida .= "				         <tr>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$sig[signos_vitales_fc]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$sig[signos_vitales_fr]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"15%\" align=\"center\">".$sig[signos_vitales_peso]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"15%\" align=\"center\">".$sig[signos_vitales_taalta]." / ".$sig[signos_vitales_tabaja]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$sig[signos_vitales_temperatura]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$sig[evaluacion_dolor]."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$glas."</td>";
			$Salida .= "				           <td class=\"normal_10\" width=\"10%\" align=\"center\">".$sig[sato2]."</td>";			
			$Salida .= "				         </tr>";
			$Salida .= "			   			 </table>";
			$Salida .= "									</td>";
			$Salida .= "				       </tr>";
			$Salida .= "				       <tr >";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Observación: </td>";
			$Salida .= "				          <td class=\"normal_10\" colspan=\"5\">".$dat[observacion_medico]."</td>";
			$Salida .= "				       </tr>";
			$Salida .= "				       <tr >";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Impresión Diagnostica: </td>";
			$Salida .= "				          <td class=\"normal_10\" colspan=\"5\">".$dat[impresion_diagnostica]."</td>";
			$Salida .= "				       </tr>";
			$Salida .= "				       <tr >";
			$Salida .= "				          <td align=\"left\" class=\"normal_10\">Diagnostico: </td>";
			$Salida .= "				          <td class=\"normal_10\" colspan=\"5\">";
			$diaTriage=BuscarDiagnosticoTriage($this->datos['triage_id']);
			if(!empty($diaTriage))
			{
					$Salida.="<br><table  align=\"center\" border=\"1\"  width=\"100%\">";
					$Salida.="<tr class=\"normal_10\" align=\"center\">";
					$Salida.="  <td width=\"15%\">CODIGO</td>";
					$Salida.="  <td width=\"85%\">DESCRIPCION</td>";
					$Salida.="</tr>";
					for($k=0; $k<sizeof($diaTriage); $k++)
					{
							$Salida.="<tr class=\"normal_10\">";
							$Salida.="  <td align=\"center\">".$diaTriage[$k][diagnostico_id]."</td>";
							$Salida.="  <td>".$diaTriage[$k][diagnostico_nombre]."</td>";
							$Salida.="</tr>";
					}
					$Salida.="</table><br>";
			}
			$Salida .= "			     </table>";
			$Salida .= "			    <table align=\"left\" border=\"0\"  width=\"100%\" class=\"normal_10\">";
			$Salida .= "				     <tr align=\"left\">";	
			$Salida .= "				       <td>&nbsp;<br><br><br></td>";				
			$Salida .= "				     </tr>";					
			$Salida .= "				     <tr align=\"left\">";			
			$Salida .= "				       <td>Profesional: ".$dat['nombre']."</td>";
			$Salida .= "				     </tr>";
			$Salida .= "				     <tr align=\"left\">";			
			$Salida .= "				       <td>".$dat['tipo_tercero_id']." ".$dat['tercero_id']." ";
			if($dat['tarjeta_profesional'])
			{
					$Salida .= "  T.P. ".$dat['tarjeta_profesional']."";
			}
			$Salida .= "				       </td>";
			$Salida .= "				     </tr>";
			if(!empty($dat['especialidad']))
			{			
					$Salida .= "<TR align=\"left\">";
					$Salida .= "<TD WIDTH='20%'>Especialidad:  ".$dat['especialidad']."</TD>";
					$Salida .= "</TR>";
			}
			else		
			{	
					$Salida .= "				     <tr align=\"left\">";			
					$Salida .= "				       <td>".$dat['tipo_profesional']."</td>";
					$Salida .= "				     </tr>";
			}								
			$Salida .= "			     </table>";			
			return $Salida;
	}

	/**
	*
	*/
	function NombreColorTriage($nivel)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.color FROM niveles_triages as a WHERE a.nivel_triage_id=$nivel";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$var=$result->fields[0];
			$result->Close();
			return $var;
	}
  
  
  

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
    //---------------------------------------
}

?>
