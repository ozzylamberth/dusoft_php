<?php

/**
* Submodulo de InscripcionPYP.
* $Id: hc_AtencionPlanFliar.php,v 1.2 2007/02/01 20:44:02 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("AtencionPF",null,"hc","AtencionPlanFliar");
IncludeClass("AtencionPF_HTML","html","hc","AtencionPlanFliar");
IncludeClass("InscripcionPF",null,"hc","InscripcionPlanFamiliar");
IncludeClass("InscripcionesPYP",null,"hc","InscripcionPYP");

include_once "hc_modules/AntecedentesGinecoObstetricos/hc_AntecedentesGinecoObstetricos.php";
include_once "hc_modules/RegistroEvolucionPFliar/hc_RegistroEvolucionPFliar.php";
include_once "hc_modules/GraficasSeguimientoPFliar/hc_GraficasSeguimientoPFliar.php";
include_once "hc_modules/AyudasEducativas/hc_AyudasEducativas.php";
include_once "hc_modules/ProtocolosAtencion/hc_ProtocolosAtencion.php";

include_once "hc_modules/Interconsulta/hc_Interconsulta.php";


class AtencionPlanFliar extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/

	function AtencionPlanFliar()
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
		$programa=ModuloGetVar('hc_submodulo','AtencionPlanFliar','PF');
		$aten=new InscripcionesPYP();
		$inscripcion=$aten->ObtenerInscripcion($evolucion,$programa);
		$consulta="";
		if(!empty($inscripcion))
		{
			SessionSetVar("Evolucion",$evolucion);
			SessionSetVar("Programa",$programa);
			SessionSetVar("Inscripcion_$programa",$inscripcion);
			SessionSetVar("DatosPaciente",$this->datosPaciente);
			
			$registro=new RegistroEvolucionPFliar();
			$consulta=$registro->GetConsulta();
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
		$programa=ModuloGetVar('hc_submodulo','AtencionPlanFliar','PF');
		$aten=new InscripcionesPYP();
		$inscripcion=$aten->ObtenerInscripcion($evolucion,$programa);
		$imprimir="";
		if(!empty($inscripcion))
		{
			SessionSetVar("Evolucion",$evolucion);
			SessionSetVar("Programa",$programa);
			SessionSetVar("Inscripcion_$programa",$inscripcion);
			SessionSetVar("DatosPaciente",$this->datosPaciente);
			
			$registro=new RegistroEvolucionPFliar();
			$imprimir=$registro->GetReporte_Html();
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
		//echo "<br><br><br>";
		$aten=new AtencionPF();
		$aten_html=new AtencionPF_HTML();
		$ins=new InscripcionPF();
		$inspyp=new InscripcionesPYP();

		SessionDelVar("cpn");
		SessionSetVar("plan_fliar",true);
		
		//$paso=$inspyp->GetPaso("AtencionPlanFliar");
		$programa=ModuloGetVar('hc_submodulo','AtencionPlanFliar','PF');
		SessionSetVar("Paso",SessionGetVar("Paso")+$programa);
		SessionSetVar("Prefijo","frm_AtencionPlanFliar");
	
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
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			SessionSetVar("Inscripcion_$programa",$validacion[0][inscripcion_id]);
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
			if(($_REQUEST['accion'.$pfj]=='AntecedentesGinecos') OR !empty($_REQUEST['Iniciar']) OR empty($_REQUEST['accion'.$pfj]))
			{
				if($datosPaciente['sexo_id']=='F')
				{
					$antecedente=new AntecedentesGinecoObstetricos();
					$this->salida.="".$antecedente->GetForma();
				}
				elseif($datosPaciente['sexo_id']=='M')
				{
					$registro=new RegistroEvolucionPFliar();
					$this->salida.="".$registro->GetForma();
				}
			}
			elseif($_REQUEST['accion'.$pfj]=='RegistroEvolucionPFliar')
			{
				$registro=new RegistroEvolucionPFliar();
				$this->salida.="".$registro->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='GraficasSeguimientoPFliar')
			{
				$graficas=new GraficasSeguimientoPFliar();
				$this->salida.="".$graficas->GetForma();
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