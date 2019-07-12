<?php

/**
* Submodulo de InscripcionPYP.
* $Id: hc_AtencionReno.php,v 1.2 2007/02/01 20:44:08 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("AtencionR",null,"hc","AtencionReno");
IncludeClass("AtencionR_HTML","html","hc","AtencionReno");
IncludeClass("Renopro",null,"hc","InscripcionReno");
IncludeClass("InscripcionesPYP",null,"hc","InscripcionPYP");

include_once "hc_modules/GrupoRiesgoRenoproteccion/hc_GrupoRiesgoRenoproteccion.php";
include_once "hc_modules/RegistroEvolucionReno/hc_RegistroEvolucionReno.php";
include_once "hc_modules/GraficasSeguimientoReno/hc_GraficasSeguimientoReno.php";
include_once "hc_modules/PruebasLaboratorioReno/hc_PruebasLaboratorioReno.php";
include_once "hc_modules/AyudasEducativas/hc_AyudasEducativas.php";
include_once "hc_modules/ProtocolosAtencion/hc_ProtocolosAtencion.php";



class AtencionReno extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/

	function AtencionReno()
	{
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
		'fecha'=>'30/06/2006',
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
		$evolucion=$this->evolucion;
		$programa=ModuloGetVar('hc_submodulo','AtencionReno','Renoproteccion');
		$aten=new InscripcionesPYP();
		$inscripcion=$aten->ObtenerInscripcion($evolucion,$programa);
		$consulta="";
		if(!empty($inscripcion))
		{
			SessionSetvar("Evolucion",$evolucion);
			SessionSetvar("Programa",$programa);
			SessionSetvar("Inscripcion_$programa",$inscripcion);
			
			$riesgo=new GrupoRiesgoRenoproteccion();
			$consulta.=$riesgo->GetConsulta();
			
			$registro=new RegistroEvolucionReno();
			$consulta.=$registro->GetConsulta();
		}
		
		return $consulta;
	}
		
	/**
	* Esta metodo captura los datos de la impresión de la Historia Clinica.
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetReporte_Html()
	{
		$evolucion=$this->evolucion;
		$programa=ModuloGetVar('hc_submodulo','AtencionReno','Renoproteccion');
		$aten=new InscripcionesPYP();
		$inscripcion=$aten->ObtenerInscripcion($evolucion,$programa);
		$imprimir="";
		if(!empty($inscripcion))
		{
			SessionSetVar("Evolucion",$evolucion);
			SessionSetVar("Programa",$programa);
			SessionSetVar("Inscripcion_$programa",$inscripcion);
			
			$riesgo=new GrupoRiesgoRenoproteccion();
			$imprimir.=$riesgo->GetReporte_Html();
			
			$registro=new RegistroEvolucionReno();
			$imprimir.=$registro->GetReporte_Html();
		}
			
		return $imprimir;
	}

	/**
	* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/

	function GetEstado()
	{
		return true;
	}
	
	/**
	* Esta función retorna la presentación del submodulo (consulta o inserción).
	*
	* @access public
	* @return text Datos HTML de la pantalla.
	* @param text Determina la acción a realizar.
	**/
	
	function GetForma()
	{
		$aten=new AtencionR();
		$aten_html=new AtencionR_HTML();
		$ins=new Renopro();
		$inspyp=new InscripcionesPYP();

		SessionDelVar("cpn");
		SessionDelVar("plan_fliar");
		
		//$paso=$inspyp->GetPaso("AtencionReno");
		$programa=ModuloGetVar('hc_submodulo','AtencionReno','Renoproteccion');
		SessionSetVar("Paso",SessionGetVar("Paso")+$programa);
		
		SessionSetVar("Prefijo","frm_AtencionReno");
		
		if(!$_REQUEST['Iniciar'])
		{
			SessionSetVar("Evolucion",$this->evolucion);
			SessionSetVar("Ingreso",$this->ingreso);
			SessionSetVar("Plan",$this->plan);
			SessionSetVar("DatosPaciente",$this->datosPaciente);
			SessionSetVar("Paso",$this->paso);
			SessionSetVar("Prefijo",$this->frmPrefijo);
		}
		
		$pfj=SessionGetVar("Prefijo");
		$datosPaciente=SessionGetVar("DatosPaciente");
		SessionSetVar("Programa",$programa);
		
		$validacion=$ins->ValidaInscripcionPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id'],$programa);
		
		if($validacion)
		{
			$programa=SessionGetVar("Programa");
			SessionSetVar("Inscripcion_$programa",$validacion[0][inscripcion_id]);
			
			$evolucion=SessionGetVar("Evolucion");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			
			$ins_evo=$inspyp->GetDatos();
			$dato_inscripcion=$ins_evo[0][inscripcion_id];
			$dato_evolucion=$ins_evo[0][evolucion_id];
			$dato_estado=$ins_evo[0][sw_estado];
			
			if($dato_estado!='4')
			{
				if($dato_evolucion!=$evolucion)
				{
					if(!$inspyp->InsertarEvoProcesos('3',$evolucion,$inscripcion))
						$aten_html->frmError["MensajeError"]=$inspyp->ErrorDB();
				}
				else
				{
					if($dato_estado!='2' and $dato_estado!='3' OR empty($ins_evo))
					{
						if(!$inspyp->UpdateEvoProcesos('2',$evolucion,$inscripcion))
							$aten_html->frmError["MensajeError"]=$inspyp->ErrorDB();
					}
				}
			}
			
			if(($_REQUEST['accion'.$pfj]=='GrupoRiesgoRenoproteccion') OR !empty($_REQUEST['Iniciar']) OR empty($_REQUEST['accion'.$pfj]))
			{
				$gpReno=new GrupoRiesgoRenoproteccion();
				$this->salida.="".$gpReno->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='RegistroEvolucionReno')
			{
				$registro=new RegistroEvolucionReno();
				$this->salida.="".$registro->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='GraficasSeguimientoReno')
			{
				$graficas=new GraficasSeguimientoReno();
				$this->salida.="".$graficas->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='PruebasLaboratorioReno')
			{
				$pruebas=new PruebasLaboratorioReno();
				$this->salida.="".$pruebas->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='AyudasEducativas')
			{
				$ayudas=new AyudasEducativas();
				$this->salida.="".$ayudas->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='ProtocolosAtencion')
			{
				$protocolos=new ProtocolosAtencion();
				$this->salida.="".$protocolos->GetForma();
			}
		}
		else
		{
			$this->salida.="".$aten_html->frmAlerta();
		}
		
		return $this->salida;
	}
}
?>