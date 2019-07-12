<?php

/***
* Submodulo de Renoproteccion.
* $Id: hc_InscripcionReno.php,v 1.2 2007/02/01 20:50:16 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("Renopro",null,"hc","InscripcionReno");
IncludeClass("Renopro_HTML","html","hc","InscripcionReno");

class InscripcionReno extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/

	function InscripcionReno()
	{
		$this->ins=new Renopro();
		$this->ins_html=new Renopro_HTML();
		
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
		$ins=new Renopro();
		$ins_html=new Renopro_HTML();
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
		$ins=new Renopro();
		$ins_html=new Renopro_HTML();
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
		SessionDelVar("plan_fliar");
		$pfj=SessionGetVar("Prefijo");
		
		if($_REQUEST["programa".$pfj])
			SessionSetVar("Programa",$_REQUEST["programa".$pfj]);
		
		$programa=SessionGetVar("Programa");
		
		$datosPaciente=SessionGetVar("DatosPaciente");
		$paciente_id=$datosPaciente['paciente_id'];
		$tipo_id_paciente=$datosPaciente['tipo_id_paciente'];

		if($_REQUEST['Inscribir'.$pfj])
			$ins_html->frmError["MensajeError"]=$this->Realizar_Inscripcion($_REQUEST);

		$validacion=$ins->ValidaInscripcionPaciente($tipo_id_paciente,$paciente_id,$programa);
		$apoyosI=$ins->GetApoyosInicales($programa);
		$signos=$ins->GetDatosSignos();
		
		if($validacion)
			SessionSetVar("Inscripcion_$programa",$validacion[0][inscripcion_id]);
		else
			SessionSetVar("Inscripcion_$programa","");
			
		if($_REQUEST['solicitar'.$pfj])
			$ins_html->frmError["MensajeError"]=$this->Solicitar_Examenes($_REQUEST['apoyos'.$pfj],$validacion);

		$consulta=$ins->ConsultaSolicitudes();
		
		return $ins_html->frmRenoproteccion($apoyosI,$signos,$validacion,$consulta);
	}
	
	function Realizar_Inscripcion($datos)
	{
		$ins=$this->ins;
		$ins_html=$this->ins_html;
		$pfj=SessionGetVar("Prefijo");
		
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
		
		if($ins->InscribirReno($datos))
			return "INSCRIPCION REALIZADA EXISTOSAMENTE";
		else
			return $ins->ErrorDB();
	}
	
	function Solicitar_Examenes($datos,$validacion)
	{
		$apd=new APD_Solicitudes();
		$pfj=SessionGetVar("Prefijo");
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		
		if($validacion)
		{
			if(!empty($datos))
			{
				if($apd->Insertar_Varias_Solicitudes($datos,$evolucion,$inscripcion,$programa))
					return "SOLICITDES GUARDADAS SATISFACTORIAMENTE";
				else
					return $apd->ErrorDB();
			}
			else
				return "DEBE SELECCIONAR ALGUN EXAMEN";
		}
		else
			return "EL PACIENTE DEBE ESTAR INSCRITO PARA SOLICITAR EXAMENES";
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