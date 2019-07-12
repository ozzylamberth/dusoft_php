<?php

/**
* Submodulo de InscripcionPYP.
* $Id: hc_InscripcionPYP.php,v 1.7 2007/03/09 21:58:27 lorena Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("InscripcionesPYP",null,"hc","InscripcionPYP");
IncludeClass("InscripcionesPYP_HTML","html","hc","InscripcionPYP");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");

include_once "hc_modules/InscripcionCPN/hc_InscripcionCPN.php";
include_once "hc_modules/AtencionCPN/hc_AtencionCPN.php";

include_once "hc_modules/InscripcionReno/hc_InscripcionReno.php";
include_once "hc_modules/AtencionReno/hc_AtencionReno.php";

include_once "hc_modules/InscripcionPlanFamiliar/hc_InscripcionPlanFamiliar.php";
include_once "hc_modules/AtencionPlanFliar/hc_AtencionPlanFliar.php";
include_once "hc_modules/InscripcionPacEspeciales/hc_InscripcionPacEspeciales.php";


class InscripcionPYP extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/

	function InscripcionPYP()
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
		'fecha'=>'19/04/2006',
		'autor'=>'LUIS ALEJANDRO VARGAS',
		'descripcion_cambio' => '',
		'requiere_sql' => false,
		'requerimientos_adicionales' => '',
		'version_kernel' => '1.0'
		);
		return $informacion;
	}

	
	function retornaObjeto()
	{
		return $this;
	}

	/**
	* Esta función retorna los datos de la impresión de la consulta del submodulo.
	*
	* @access private
	* @return text Datos HTML de la pantalla.
	*/
	function GetConsulta()
	{
		$inspyp_html=new InscripcionesPYP_HTML();
		if($inspyp_html->frmConsulta()==false)
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
		$inspyp_html=new InscripcionesPYP_HTML();
		$imprimir=$inspyp_html->frmHistoria();
		if($imprimir==false)
		{
			return true;
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
		$inspyp=new InscripcionesPYP();
		$inspyp_html=new InscripcionesPYP_HTML();
		$riesgo=new RiesgoBS();
		
		SessionSetVar("Evolucion",$this->evolucion);
		SessionSetVar("Ingreso",$this->ingreso);
		SessionSetVar("Paso",$this->paso);
		SessionSetVar("Plan",$this->plan);
		SessionSetVar("DatosPaciente",$this->datosPaciente);
		SessionSetVar("Prefijo",$this->frmPrefijo);
		
		$pfj=SessionGetVar("Prefijo");
		$evolucion=SessionGetVar("Evolucion");
		
		if($_REQUEST['programa'.$pfj])
			SessionSetVar("Programa",$_REQUEST['programa'.$pfj]);
		
		if(empty($_REQUEST['accion'.$pfj]))
		{
			$programas_ins=$inspyp->getProgramasPacienteInscrito();
			$programas_can=$inspyp->getProgramasPacienteCandidato();
			
			if(empty($programas_can) AND empty($programas_ins))
				$this->salida.=$inspyp_html->frmMensaje("EL PACIENTE NO TIENE PROGRAMAS ASOCIADOS");
			else
				$this->salida.=$inspyp_html->frmForma($programas_ins,$programas_can);	
		}
		else
		{
			if($_REQUEST['accion'.$pfj]=='InscripcionCPN')
			{
				$inscpn=new InscripcionCPN($this);
				$this->salida.="".$inscpn->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='AtencionCPN')
			{
				$aten_cpn=new AtencionCPN();
				$this->salida.="".$aten_cpn->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='InscripcionReno')
			{
				$ins=new InscripcionReno();
				$this->salida.="".$ins->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='AtencionReno')
			{
				$atenR=new AtencionReno();
				$this->salida.="".$atenR->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='InscripcionPlanFamiliar')
			{
				$ins=new InscripcionPlanFamiliar();
				$this->salida.="".$ins->GetForma();
			}
			elseif($_REQUEST['accion'.$pfj]=='AtencionPlanFliar')
			{
				$atenPF=new AtencionPlanFliar();
				$this->salida.="".$atenPF->GetForma();
			}
      elseif($_REQUEST['accion'.$pfj]=='InscripcionPacEspeciales')
      {
        $atenPF=new InscripcionPacEspeciales();
        $this->salida.="".$atenPF->GetForma();
      }
		}
		
		return true;
	}
}
?>