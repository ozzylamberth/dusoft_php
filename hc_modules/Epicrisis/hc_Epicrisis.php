<?php
/**
* Submodulo de Epicrisis
*
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_Epicrisis.php,v 1.8 2007/03/22 16:35:12 luis Exp $
*/

IncludeClass("GeneracionEpicrisis",null,"hc","Epicrisis");
IncludeClass("GeneracionEpicrisis_HTML","html","hc","Epicrisis");

class Epicrisis extends hc_classModules
{
	function Epicrisis()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	/**
	* Esta funci?n retorna los datos de concernientes a la version del submodulo
	* @access private
	*/
	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'14/12/2006',
		'autor'=>'LUIS ALEJANDRO VARGAS',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}


	/**
	* Esta funci?n retorna los datos de la impresi?n de la consulta del submodulo.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/
	function GetConsulta()
	{
		$epicrisis_html=new GeneracionEpicrisis_HTML();
		return $epicrisis_html->frmConsulta();
	}
     
	/**
	* Esta metodo captura los datos de la impresi?n de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		$epicrisis_html=new GeneracionEpicrisis_HTML();
		return $epicrisis_html->frmHistoria();
		
	}

	/**
	* Esta funci?n verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetEstado()
	{
		
		list($dbconn) = GetDBconn();
		
		$query="SELECT count(*)
						FROM hc_epicrisis
						WHERE ingreso=".$this->ingreso.";";
		
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el SubModulo Epicrisis - GetEstado()";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$resulta->EOF)
			{
				$estado=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
			
		if($estado['count']==0 OR SessionGetVar("listo_".$this->ingreso)<2)
		{
			return false;
		}
		else
		{
			return true;
		}
		
	}
	
	function GetForma()
	{
		$epicrisis=new GeneracionEpicrisis();
		$epicrisis_html=new GeneracionEpicrisis_HTML();
		unset($_SESSION['EPICRISIS_OK']["$ingreso"]);
		//SessionDelVar("listo_".$this->ingreso);
		
		SessionSetVar("Ingreso",$this->ingreso);
		SessionSetVar("Evolucion",$this->evolucion);
		SessionSetVar("Paso",$this->paso);
		SessionSetVar("DatosPaciente",$this->datosPaciente);
		
		SessionSetVar("tipoidpaciente",$this->tipoidpaciente);
		SessionSetVar("paciente",$this->paciente);
		
		SessionSetVar("RutaImg",GetThemePath());
		
		$vector=array	(
										"DATOS DEL INGRESO"=>array
																					(
																						"MOTIVO CONSULTA",
																						"ESTADO GENERAL Y ENFERMEDAD ACTUAL",
																						"ANTECEDENTES PERSONALES",
																						"EXAMEN FISICO",
																						"APOYOS DIAGNOSTICOS",
																						"DIAGNOSTICOS DE INGRESO"
																					),
										"DATOS DE LA EVOLUCION"=>array
																					(
																						"DATOS DE LA EVOLUCION",
																						"MEDICAMENTOS"
																					),
										"DATOS DEL EGRESO"=>array
																					(
																						"PLAN DE SEGUIMIENTO",
																						"DIAGNOSTICOS DE EGRESO",
																						"CAUSA DE SALIDA"
																					)
									);
		
		SessionSetVar("Vector",11);
		
		//echo "<br><br><br>";
		
		$epi=$epicrisis->GetDatosEpicrisis($this->ingreso);
		if(!$epi)
			$epi=$epicrisis->InsertEpicrisis($this->ingreso);
		
		$motivo_consulta=$epicrisis->GetDatosMotivosConsulta($this->ingreso);

		$enfermedad=$epicrisis->GetDatosEnfermedad($this->ingreso);
			
		$antecedentes=$epicrisis->GetDatosAntecedentesPersonales($this->ingreso,$this->evolucion,$this->paciente,$this->tipoidpaciente);
		
		$ex_fisico=$epicrisis->GetDatosExamenFisico($this->ingreso);
		if(!$ex_fisico)
			$ex_fisico=$epicrisis->GetDatosExamenFisico($this->ingreso,1);
		
		$ex_fisico_hallazgo=$epicrisis->GetDatosExamenFisicoHallazgos($this->ingreso);
		if(!$ex_fisico_hallazgo)
			$ex_fisico_hallazgo=$epicrisis->GetDatosExamenFisicoHallazgos($this->ingreso,1);

		$apoyod=$epicrisis->GetDatosApoyosD($this->ingreso,$this->tipoidpaciente,$this->paciente);
		
		$diagI=$epicrisis->GetDiagnosticos($this->ingreso,"ingreso");
		
		$datos_evolucion=$epicrisis->GetDatosEvolucion($this->ingreso);
		
		$medicamentos=$epicrisis->GetMedicamentosPacientes($this->ingreso);
			
		$plan_seg=$epicrisis->GetDatosPlanSeguimiento($this->ingreso);
		
		$diagE=$epicrisis->GetDiagnosticos($this->ingreso,"egreso");
		
		$tipos_salida=$epicrisis->GetTiposCausaSalida();
		$datos_salida=$epicrisis->GetDatosCausaSalida($this->ingreso);
		
		$this->salida=$epicrisis_html->frmForma($vector,$motivo_consulta,$enfermedad,$antecedentes,$ex_fisico,$ex_fisico_hallazgo,$apoyod,$diagI,$datos_evolucion,$medicamentos,$plan_seg,$diagE,$tipos_salida,$datos_salida);
		
		return true;
	}
}
?>