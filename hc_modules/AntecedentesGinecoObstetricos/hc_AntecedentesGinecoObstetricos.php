<?php

/**
* Submodulo de Antecedentes Ginecobstetricos.
*
* Submodulo para manejar los antecedentes ginecobstetricos de un paciente en una evolucion y las diferentes
* evoluciones que se necesiten.
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesGinecoObstetricos.php,v 1.9 2007/02/01 20:43:16 luis Exp $
*/


/**
* AntecedentesGinecoObstetricos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de antecedentes ginecobstetricos.
*/

IncludeClass("AntecedentesGO",null,"hc","AntecedentesGinecoObstetricos");
IncludeClass("AntecedentesGO_HTML","html","hc","AntecedentesGinecoObstetricos");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
IncludeClass("InscripcionesPYP",null,"hc","InscripcionPYP");

class AntecedentesGinecoObstetricos extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/
	function AntecedentesGinecoObstetricos()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	/**
	* Esta función retorna los datos de concernientes a la version del submodulo
	* @access private
	*/

	function GetVersion()
	{
		$informacion=array(
		'version'=>'1',
		'subversion'=>'0',
		'revision'=>'0',
		'fecha'=>'01/27/2005',
		'autor'=>'LUIS ALEJANDRO VARGAS',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}
	
	/**
	* Esta función retorna los datos de la impresión de la consulta del submodulo.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/
	function GetConsulta()
	{
		$evolucion=SessionGetVar("Evolucion");
		$inscripcion=SessionGetVar("Inscripcion");
		
		$ante=new AntecedentesGO();
		$ante_html=new AntecedentesGO_HTML();
		$tipo_ant=$ante->BusquedaAntecedentesConsulta();
		$tipo_ant_cpn=$ante->BusquedaAntecedentesCPN($this->evolucion);
		$puntaje=$ante->ObtenerPuntajeAsociado($evolucion,$inscripcion);
		$datosIns=$ante->DatosInscripcionPacientes($inscripcion);
		
		$antecedentes_pfliar=$ante->GetDatosHistorialEmbarazos($evolucion);
		$consegeria=$ante->GetConsegeria($inscripcion);
		
		$consulta=$ante_html->frmConsulta($tipo_ant,$tipo_ant_cpn,$datosIns,$puntaje,$antecedentes_pfliar);
		if($consulta==false)
			return "";
		
		return $consulta;
	}
	
	/**
	* Esta metodo captura los datos de la impresión de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		$evolucion=SessionGetVar("Evolucion");
		$inscripcion=SessionGetVar("Inscripcion");
		
		$ante=new AntecedentesGO();
		$ante_html=new AntecedentesGO_HTML();
		$tipo_ant=$ante->BusquedaAntecedentesConsulta();
		$tipo_ant_cpn=$ante->BusquedaAntecedentesCPN($this->evolucion);
		$puntaje=$ante->ObtenerPuntajeAsociado($evolucion,$inscripcion);
		$datosIns=$ante->DatosInscripcionPacientes($inscripcion);
		
		$antecedentes_pfliar=$ante->GetDatosHistorialEmbarazos($evolucion);
		$consegeria=$ante->GetConsegeria($inscripcion);
		
		$imprimir=$ante_html->frmHistoria($tipo_ant,$tipo_ant_cpn,$datosIns,$puntaje,$antecedentes_pfliar);
		if($imprimir==false)
			return "";
			
		return $imprimir;
	}
	
	/**
	* Esta función retorna la presentación del submodulo (consulta o inserción).
	*
	* @access public
	* @return text Datos HTML de la pantalla.
	* @param text Determina la acción a realizar.
	*/
	
	function GetForma()
	{
		$ante=new AntecedentesGO();
		$ante_html=new AntecedentesGO_HTML();
		$riesgo=new RiesgoBS();
		if($this->evolucion)
		{
			$evolucion=$this->evolucion;
			$pfj=$this->frmPrefijo;
			
			SessionSetVar("Evolucion",$this->evolucion);
			SessionSetVar("Prefijo",$this->frmPrefijo);
			SessionSetVar("Paso",$this->paso);
			SessionSetVar("Ingreso",$this->ingreso);
			SessionSetvar("DatosPaciente",$this->datosPaciente);
			
			SessionDelVar("cpn");
			SessionDelVar("plan_fliar");
		}
		else
		{
			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetvar("Programa");
			$inscripcion=SessionGetvar("Inscripcion_$programa");
			$pfj=SessionGetVar("Prefijo");
			$datosPaciente=SessiongetVar("DatosPaciente");
		}
		
		$tipo_ant=$ante->BusquedaAntecedentes($evolucion);
		
		if(SessionGetVar("cpn"))
		{
			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
			
			$antecedentes_pfliar=$ante->GetDatosHistorialEmbarazos($evolucion);
			$consegeria=$ante->GetConsegeria($inscripcion);
			
			$antecedentes_cpn=$ante->BusquedaAntecedentesCPN($evolucion);
			$puntaje=$ante->ObtenerPuntajeAsociado($evolucion,$inscripcion);
			$datosIns=$ante->DatosInscripcionPacientes($inscripcion);
			
			return $ante_html->frmForma($tipo_ant,$antecedentes_cpn,$antecedentes_pfliar,$datosIns,$puntaje,$semana_gestante,$fcp,$consegeria);
		}
		
		if(SessionGetVar("plan_fliar") AND $datosPaciente['sexo_id']=='F')
		{
			$antecedentes_pfliar=$ante->GetDatosHistorialEmbarazos($evolucion);
			$consegeria=$ante->GetConsegeria($inscripcion);
			
			return $ante_html->frmForma($tipo_ant,null,$antecedentes_pfliar,null,0,0,null,$consegeria);
		}
		
		$this->salida.="".$ante_html->frmForma($tipo_ant);
		return true;
	}


	/**
	* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetEstado()
	{
		$pfj=$this->frmPrefijo;
    list($dbconn) = GetDBconn();
		
		$query="SELECT count(*)
			FROM hc_antecedentes_ginecos
			WHERE evolucion_id=".$this->evolucion.";";
		
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while(!$resulta->EOF)
		{
			$estado=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$i++;
		}
		
		if ($estado[count] == 0)
		{
			return false;
		}
		else
		{
		 	return true;
		}
	}
}
?>