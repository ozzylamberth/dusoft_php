<?php

/**
* Submodulo de Antecedentes Ginecobstetricos.
*
* Submodulo para manejar los antecedentes ginecobstetricos de un paciente en una evolucion y las diferentes
* evoluciones que se necesiten.
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesGinecoObstetricos_1.php,v 1.0 2005/05/12 23:37:49 tizziano Exp $
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

class AntecedentesGinecoObstetricos extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $obj=null;
	
	function AntecedentesGinecoObstetricos($objeto=null)
	{
		if($objeto==null)
			$objeto=$this;
			
		$this->obj=$objeto;
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
		'autor'=>'JAIME ANDRES VALENCIA',
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
		if($this->frmConsulta()==false)
		{
			return true;
		}
		return $this->salida;
	}


/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

	function GetReporte_Html()
	{
		$imprimir=$this->frmHistoria();
		if($imprimir==false)
		{
			return true;
		}
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
		$obj=$this->obj;
		$var=0;
		if($obj==null)
		{
			$obj=$this;
			$var=1;
		}
		
		$pfj=$obj->frmPrefijo;
		
		$ante=new AntecedentesGO($obj);
		$ante_html=new AntecedentesGO_HTML($obj);
		
		$evolucion=$obj->evolucion;
		
		if($obj->cpn)
		{
			$riesgo=new RiesgoBS($obj);
			
			$inscripcion=SessionGetVar("inscripcion");

			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
			
			$_SESSION['semana_gestante']=$semana_gestante;
			$_SESSION['fcp']=$fcp;

			$cpn_antecedentes=$ante->BusquedaAntecedentesCPN($evolucion);
			$puntaje=$ante->ObtenerPuntajeAsociado($evolucion,$inscripcion);
			$datosIns=$ante->DatosInscripcionPacientes($inscripcion);
		}
		
		$tipo_ant=$ante->BusquedaAntecedentes($evolucion);
		
		if($var)
		{
			$this->salida.="".$ante_html->frmForma($tipo_ant,$cpn_antecedentes='',$datosIns='',$puntaje=0,$semana_gestante=0,$fcp='');
			return true;
		}
		else
		{
			return $ante_html->frmForma($tipo_ant,$cpn_antecedentes,$datosIns,$puntaje,$semana_gestante,$fcp);
		}
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
