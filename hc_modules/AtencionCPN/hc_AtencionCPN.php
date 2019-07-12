<?php

/**
* Submodulo de InscripcionPYP.
* $Id: hc_AtencionCPN.php,v 1.5 2007/02/01 20:43:56 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("AtencionCP",null,"hc","AtencionCPN");
IncludeClass("AtencionCP_HTML","html","hc","AtencionCPN");
IncludeClass("Inscripcion",null,"hc","InscripcionCPN");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
IncludeClass("InscripcionesPYP",null,"hc","InscripcionPYP");

include_once "hc_modules/AntecedentesGinecoObstetricos/hc_AntecedentesGinecoObstetricos.php";
include_once "hc_modules/RiesgoBiopsicosocial/hc_RiesgoBiopsicosocial.php";
include_once "hc_modules/RegistroEvolucionGestacion/hc_RegistroEvolucionGestacion.php";
include_once "hc_modules/CierredeCaso/hc_CierredeCaso.php";
include_once "hc_modules/DatosRecienNacidos/hc_DatosRecienNacidos.php";
include_once "hc_modules/GraficasSeguimientoCPN/hc_GraficasSeguimientoCPN.php";
include_once "hc_modules/CronogramaCitasyProcedimientos/hc_CronogramaCitasyProcedimientos.php";
include_once "hc_modules/AyudasEducativas/hc_AyudasEducativas.php";
include_once "hc_modules/ProtocolosAtencion/hc_ProtocolosAtencion.php";

class AtencionCPN extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/

	function AtencionCPN()
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
		$programa=ModuloGetVar('hc_submodulo','AtencionCPN','cpn');
		$aten=new InscripcionesPYP();
		$inscripcion=$aten->ObtenerInscripcion($evolucion,$programa);
		$consulta="";
		if(!empty($inscripcion))
		{
			SessionSetVar("Evolucion",$evolucion);
			SessionSetVar("Programa",$programa);
			SessionSetVar("Inscripcion_$programa",$inscripcion);
			SessionSetVar("DatosPaciente",$this->datosPaciente);
			
			$riesgo=new RiesgoBiopsicosocial();
			$registro=new RegistroEvolucionGestacion();
			$consulta.=$riesgo->GetConsulta();
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
		$programa=ModuloGetVar('hc_submodulo','AtencionCPN','cpn');
		$aten=new InscripcionesPYP();
		$inscripcion=$aten->ObtenerInscripcion($evolucion,$programa);
		$imprimir="";
		if(!empty($inscripcion))
		{
			SessionSetVar("Evolucion",$evolucion);
			SessionSetVar("Programa",$programa);
			SessionSetVar("Inscripcion_$programa",$inscripcion);
			SessionSetVar("DatosPaciente",$this->datosPaciente);
			
			$riesgo=new RiesgoBiopsicosocial();
			$registro=new RegistroEvolucionGestacion();
			$imprimir.=$riesgo->GetReporte_Html();
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
		$aten=new AtencionCP();
		$aten_html=new AtencionCP_HTML();	
		$inspyp=new InscripcionesPYP();
		$riesgo=new RiesgoBS();
		$ins=new Inscripcion();
		$registro=new RegistroEG();
		
		SessionSetVar("cpn",true);
		SessionDelVar("plan_fliar");

		//$paso=$inspyp->GetPaso("AtencionCPN");
		$programa=ModuloGetVar('hc_submodulo','AtencionCPN','cpn');
		SessionSetVar("Paso",SessionGetVar("Paso")+$programa);
		SessionSetVar("Prefijo","frm_AtencionCPN");

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
		$evolucion=SessionGetVar("Evolucion");
		$datosPaciente=SessionGetVar("DatosPaciente");
		
		$programa=ModuloGetVar('hc_submodulo','AtencionCPN','cpn');
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
			
			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
			
			SessionSetVar("semana_gestante",$semana_gestante);
			SessionSetVar("fcp",$fcp);

			if($dato_estado!='4')
			{
				$semanas=$registro->GetSemanasCronograma($programa);
							
				for($i=0;sizeof($semanas);$i++)
				{
					if($semana_gestante>=$semanas[$i][rango_inicio] and $semana_gestante<=$semanas[$i][rango_fin])
					{
						$indice=$i;
						break;
					}
				}
	
				$fecha=$registro->CalcularProximaCita($semana_gestante,$semanas,$indice);
				
				if($dato_evolucion!=$evolucion)
				{
					if(!$inspyp->InsertarEvoProcesos('3',$evolucion,$inscripcion,$fecha))
						$aten_html->frmError["MensajeError"]=$inspyp->ErrorDB();
				}
				else
				{
					if($dato_estado!='2' and $dato_estado!='3' OR empty($ins_evo))
					{
						if(!$inspyp->UpdateEvoProcesos('2',$evolucion,$inscripcion,$fecha))
							$aten_html->frmError["MensajeError"]=$inspyp->ErrorDB();
					}
				}
			}
			
			if($inspyp->GetCierre($inscripcion))
			{
				SessionSetVar("rn",1);
				SessionSetVar("cierre_caso_$programa",1);
			}
			else
			{
				SessionDelVar("rn");
				SessionDelVar("cierre_caso_$programa");
			}
				
			if(($_REQUEST['accion'.$pfj]=='AntecedentesGinecos') OR !empty($_REQUEST['Iniciar']) OR empty($_REQUEST['accion'.$pfj])){
				$antecedente=new AntecedentesGinecoObstetricos();
				$this->salida.="".$antecedente->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='RiesgoBiopsicosocial')
			{
				$riesgo=new RiesgoBiopsicosocial();
				$this->salida.="".$riesgo->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='RegistroEvolucionGestacion')
			{
				$registro=new RegistroEvolucionGestacion();
				$this->salida.="".$registro->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='CierredeCaso')
			{
				$cierre=new CierredeCaso();
				$this->salida.="".$cierre->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='DatosRecienNacidos')
			{
				$nacidos=new DatosRecienNacidos();
				$this->salida.="".$nacidos->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='GraficasSeguimientoCPN')
			{
				$graficas=new GraficasSeguimientoCPN();
				$this->salida.="".$graficas->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='CronogramaCitasyProcedimientos')
			{
				$cronograma=new CronogramaCitasyProcedimientos();
				$this->salida.="".$cronograma->GetForma();
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