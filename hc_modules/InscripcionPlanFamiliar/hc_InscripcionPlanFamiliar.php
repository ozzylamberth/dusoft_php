<?php

/***
* Submodulo de Renoproteccion.
* $Id: hc_InscripcionPlanFamiliar.php,v 1.3 2007/02/01 20:54:43 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("InscripcionPF",null,"hc","InscripcionPlanFamiliar");
IncludeClass("InscripcionPF_HTML","html","hc","InscripcionPlanFamiliar");


class InscripcionPlanFamiliar extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/

	function InscripcionPlanFamiliar()
	{
		$this->ins=new InscripcionPF();
		$this->ins_html=new InscripcionPF_HTML();
		
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
		'fecha'=>'25/09/2006',
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
		$ins=new InscripcionPF();
		$ins_html=new InscripcionPF_HTML();
		$consulta=$ins_html->frmConsulta();
		if($consulta==false)
		{
			return "";
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
		$ins=new InscripcionPF();
		$ins_html=new InscripcionPF_HTML();
		$imprimir=$ins_html->frmConsulta();
		if($imprimir==false)
		{
			return "";
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
	*/
	
	function GetForma()
	{
		$ins=$this->ins;
		$ins_html=$this->ins_html;
		SessionDelVar("cpn");
		SessionSetVar("plan_fliar",true);
		
		$pfj=SessionGetVar("Prefijo");
		
		if($_REQUEST["programa".$pfj])
			SessionSetVar("Programa",$_REQUEST["programa".$pfj]);
		
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$datosPaciente=SessionGetVar("DatosPaciente");
		SessionSetVar("RutaImg",GetThemePath());
		
		if($_REQUEST['Inscribir'.$pfj])
			$ins_html->frmError["MensajeError"]=$this->Realizar_Inscripcion($_REQUEST);

		$validacion=$ins->ValidaInscripcionPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id'],$programa);
		
		if($validacion)
			SessionSetVar("Inscripcion_$programa",$validacion[0][inscripcion_id]);
		else
			SessionSetVar("Inscripcion_$programa","");
			
		$signos=$ins->GetDatosSignos();
		$metodos=$ins->GetMetodosPF();
		$motivos_susp=$ins->GetMotivosSuspencionPF();
		
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		
		$registros_mpf=$ins->GetDatosHistorialMetodosPF($inscripcion);
		$apoyosd=$ins->GetApoyosInicales($programa);

		$consulta_solicitud=$ins->ConsultaSolicitudes($evolucion,$inscripcion);
		
		return $ins_html->frmForma($signos,$validacion,$metodos,$motivos_susp,$registros_mpf,$consulta_solicitud,$apoyosd);
	}
	
	function Realizar_Inscripcion($datos)
	{
		$ins=$this->ins;
		$ins_html=$this->ins_html;
		
		$pfj=SessionGetVar("Prefijo");
		
		if((empty($datos['num_hijos_vivos'.$pfj]) OR !is_numeric($datos['num_hijos_vivos'.$pfj])) AND $datos['num_hijos_vivos'.$pfj]!=0)
		{
			$ins_html->frmError["num_hijos_vivos"]=1;
			return "INGRESE NUMERO DE HIJOS VIVOS";
		}
		
		if((empty($datos['ta_alta'.$pfj]) OR empty($datos['ta_baja'.$pfj])) OR (!is_numeric($datos['ta_alta'.$pfj]) OR !is_numeric($datos['ta_baja'.$pfj])))
		{
			$ins_html->frmError["ta"]=1;
			return "INGRESE LA PRESION ARTERIAL";
		}
		
		if(empty($datos['peso'.$pfj]) OR !is_numeric($datos['peso'.$pfj]))
		{
			$ins_html->frmError["peso"]=1;
			return "INGRESE EL PESO";
		}
		
		if($ins->InscribirPF($datos))
			return "INSCRIPCION REALIZADA EXISTOSAMENTE";
		else
			return $ins->ErrorDB();
	}
	
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			
			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
	
}
?>