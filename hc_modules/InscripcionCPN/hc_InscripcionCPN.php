<?php

/**
* Submodulo de InscripcionCPN.
* $Id: hc_InscripcionCPN.php,v 1.3 2007/02/01 20:55:52 luis Exp $
* @author luis alejandro vargas
* @version 1.0
* @package SIIS
* 
*/

IncludeClass("Inscripcion",null,"hc","InscripcionCPN");
IncludeClass("Inscripcion_HTML","html","hc","InscripcionCPN");
IncludeClass("APD_Solicitudes",null,"hc","Apoyos_Diagnosticos_Solicitud");
IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
IncludeClass("RegistroEG",null,"hc","RegistroEvolucionGestacion");
IncludeClass("AntecedentesGO",null,"hc","AntecedentesGinecoObstetricos");

class InscripcionCPN extends hc_classModules
{
	/**
	* Esta función Inicializa las variable de la clase
	*
	* @access public
	* @return boolean Para identificar que se realizo.
	*/
	
	function InscripcionCPN()
	{
		$this->ins=new Inscripcion();
		$this->ins_html=new Inscripcion_HTML();
		$this->apd=new APD_Solicitudes();
		$this->riesgo=new RiesgoBS();
		$this->registro=new RegistroEG();
		$this->ante=new AntecedentesGO();
		
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
		$ins_html=new Inscripcion_HTML();
		if($ins_html->frmConsulta()==false)
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
		$ins_html=new Inscripcion_HTML();
		$imprimir=$ins_html->frmHistoria();
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
	*/
	
	function GetForma()
	{
		$ins=$this->ins;
		$ins_html=$this->ins_html;
		$riesgo=$this->riesgo;
		$registro=$this->registro;
		
		SessionSetVar("cpn",true);
		SessionDelVar("plan_fliar");
		
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$datosPaciente=SessionGetVar("DatosPaciente");
		$pfj=SessionGetvar("Prefijo");

		$apoyosI=$ins->GetApoyosInicales($programa);

		$apoyos=$_REQUEST['apoyos'.$pfj];
		
		if($_REQUEST['Inscribir'.$pfj])
			$ins_html->frmError["MensajeError"]=$this->Realizar_Inscripcion($_REQUEST);
		
		$validacion=$ins->ValidaInscripcionPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id'],$programa);
		
		if($validacion)
			SessionSetVar("Inscripcion_$programa",$validacion[0][inscripcion_id]);
		else
			SessionSetVar("Inscripcion_$programa","");
			
		$inscripcion=SessionGetVar("Inscripcion_$programa");
			
		$fechas=$riesgo->GetDatofum($inscripcion);
		$fum=$fechas[0][fecha_ultimo_periodo];
		$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
		$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
		
		if($_REQUEST['solicitar'.$pfj])
			$ins_html->frmError["MensajeError"]=$this->Solicitar_Examenes($_REQUEST['apoyos'.$pfj],$validacion,$semana_gestante);
		
		$consulta=$ins->ConsultaSolicitudes($evolucion,$inscripcion);
		
		return $ins_html->frmForma($apoyosI,$programa,$validacion,$semana_gestante,$fcp,$consulta);
	}
	
	
	function Realizar_Inscripcion($datos)
	{
			$ins=$this->ins;
			$ins_html=$this->ins_html;
			$riesgo=$this->riesgo;
			$registro=$this->registro;
			$ante=$this->ante;
			
			$evolucion=SessionGetvar("Evolucion");
			$programa=SessionGetvar("Programa");
			$pfj=SessionGetVar("Prefijo");
			
			$a=0;
			$b=0;
			
			$fup=$datos['fup'.$pfj];
			$fum=$datos['fum'.$pfj];
			$fpp=$datos['fpp'.$pfj];
			$num_previos=$datos['num_previos'.$pfj];
			
			$fum=$this->FechaStamp($fum);
			
			if(empty($num_previos))
				$num_previos=0;
			
			if(!empty($fup))
			{
				$fup=$this->FechaStamp($fup);
				//valida la fecha del ultimo parto con la fecha actual
				if(strtotime($fup) > strtotime(date("Y-m-d")))
				{
					$ins_html->frmError["fpp"]=1;
					$ins_html->frmError["fup"]=1;
					return "LA FECHA ULTIMO PARTO DEBE SER MAYOR QUE LA FECHA PRIMER PARTO";
				}
				$a=1;
			}
			else
				$fup='NULL';

			if(!empty($fpp))
			{
				$fpp=$this->FechaStamp($fpp);
				
				//valida la fecha del primer parto con la fecha actual
				if(strtotime($fpp) > strtotime(date("Y-m-d")))
				{
					$ins_html->frmError["fpp"]=1;
					$ins_html->frmError["fup"]=1;
					return "LA FECHA ULTIMO PARTO DEBE SER MENOR QUE LA FECHA ACTUAL";
				}
				$b=1;
			}
			else
				$fpp='NULL';
	
			if(empty($fum))
			{
				$ins_html->frmError["fum"]=1;
				return "EL CAMPO FECHA ULTIMA MESTRUACION NO DEBE SER VACIO";
			}
			
			//valida la fecha del ultimo periodo con la fecha actual
			if(strtotime($fum)> strtotime(date("Y-m-d")))
			{
				$ins_html->frmError["fum"]=1;
				return "LA FECHA ULTIMO MESTRUACION DEBE SER MENOR QUE LA FECHA ACTUAL";
			}
			
			//valida la fecha ultimo parto sea mayor que la fecha del primer parto
			if($a==1 && $b==1)
			{
				if(strtotime($fup) < strtotime($fpp))
				{
					$ins_html->frmError["fpp"]=1;
					$ins_html->frmError["fup"]=1;
					return "LA FECHA ULTIMO PARTO DEBE SER MAYOR QUE LA FECHA PRIMER PARTO";
				}
			}

			$fcp=$this->CacularFechaParto($fum);	
			$semanas=$registro->GetSemanasCronograma($programa);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
				
			for($i=0;sizeof($semanas);$i++)
			{
				if($semana_gestante>=$semanas[$i][rango_inicio] and $semana_gestante<=$semanas[$i][rango_fin])
				{
					$indice=$i;
					break;
				}
			}
				
			$fecha=$registro->CalcularProximaCita($semana_gestante,$semanas,$indice);
				
			if($ins->InscribirCPN($fum,$fup,$fpp,$fcp,$num_previos,$programa,$fecha))
			{
				$datosAnte=$ante->ConsultaAntecedentesPyp();
				
				for($i=0;$i<sizeof($datosAnte);$i++)
				{
					$param=array();
					switch($i+1)
					{
						case 1:
							$param[0]=$datosAnte[$i][htd];
							$param[1]=$datosAnte[$i][htg];
							$param[5]=1;
							$param[6]=$fum;
							$param[7]=1;
						break;
						case 2:
							if($fup!='NULL')
							{
								$param[0]=$datosAnte[$i][htd];
								$param[1]=$datosAnte[$i][htg];
								$param[5]=1;
								$param[6]=$fup;
								$param[7]=1;
							}
						break;
						case 3:
							if(!empty($num_previos))
							{
								$param[0]=$datosAnte[$i][htd];
								$param[1]=$datosAnte[$i][htg];
								$param[5]=1;
								$param[6]="G     P  $num_previos  A     C  ";
								$param[7]=1;
							}
						break;
					}
					if($param!=null)
					{
						if($ante->InsertDatos($evolucion,$param)==false)
							return $ante->ErrorDB;
					}
				}
				return "INSCRIPCION REALIZADA EXISTOSAMENTE";
			}
			else
				return $ins->ErrorDB();
	}
	
	function Solicitar_Examenes($apoyos,$validacion,$semana_gestante)
	{
		$pfj=SessionGetVar("Prefijo");
		$evolucion=SessionGetVar("Evolucion");
		$programa=SessionGetVar("Programa");
		$inscripcion=SessionGetVar("Inscripcion_$programa");
		$datosPaciente=SessionGetVar("DatosPaciente");
		$pfj=SessionGetvar("Prefijo");
		
		$ins=$this->ins;
		$ins_html=$this->ins_html;
		$riesgo=$this->riesgo;
		$registro=$this->registro;
		$apd=$this->apd;
		
		if($validacion)
		{
			if(!empty($apoyos))
			{
				$semanas=$registro->GetSemanasCronograma($programa);
				
				for($i=0;$i<sizeof($semanas);$i++)
				{
					if($semanas[$i][rango_inicio]<=$semana_gestante and $semanas[$i][rango_fin]>=$semana_gestante)
					{
						$periodo_id=$semanas[$i][periodo_id];
						$periodo=$i;
						break;	
					}
				}
				
				if($apd->Insertar_Varias_Solicitudes($apoyos,$evolucion,$inscripcion,$programa,$periodo_id))
					return "SOLICITUDES GUARDADAS EXITOSAMENTE";
				else
					return $apd->ErrorDB();
			}
		}
		else
			return "EL PACIENTE DEBE ESTAR INSCRITO PARA SOLICITAR EXAMENES";

		return true;
	}
	
	function CacularFechaParto($fum)
	{
		$tiempo1=time() - strtotime($fum);
		$tiempo2=time() + (279*24*60*60);
		$tiempo=$tiempo2 - $tiempo1;
		$fcp=date("Y-m-d",$tiempo);
		
		return $fcp;
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
