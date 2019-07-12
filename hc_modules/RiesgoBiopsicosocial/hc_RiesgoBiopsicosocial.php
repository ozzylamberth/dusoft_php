<?php
	/********************************************************************************* 
 	* $Id: hc_RiesgoBiopsicosocial.php,v 1.3 2007/02/01 20:51:09 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RiesgoBiopsicosocial
	* 
 	**********************************************************************************/
	
	IncludeClass("RiesgoBS_HTML","html","hc","RiesgoBiopsicosocial");
	IncludeClass("RiesgoBS",null,"hc","RiesgoBiopsicosocial");
	
	class RiesgoBiopsicosocial extends hc_classModules
	{
		function RiesgoBiopsicosocial()
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
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			$riesgo=new RiesgoBS();
			$riesgo_html=new RiesgoBS_HTML();
			$riesgosbp=$riesgo->ConsultaRiesgoBiopsicosocial($inscripcion,$evolucion,$programa);
			
			$consulta=$riesgo_html->frmConsulta($riesgosbp);
			
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
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$evolucion=SessionGetVar("Evolucion");
			
			$riesgo=new RiesgoBS();
			$riesgo_html=new RiesgoBS_HTML();
			$riesgosbp=$riesgo->ConsultaRiesgoBiopsicosocial($inscripcion,$evolucion,$programa);
			
			$imprimir=$riesgo_html->frmHistoria($riesgosbp);
			
			if($imprimir==false)
				return "";
				
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
			
		function GetForma()
		{
			$riesgo=new RiesgoBS();
			$riesgo_html=new RiesgoBS_HTML();
			
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$pfj=SessionGetVar("Prefijo");
			
			$fechas=$riesgo->GetDatofum($inscripcion);
			$fum=$fechas[0][fecha_ultimo_periodo];
			$fcp=substr($fechas[0][fecha_calulada_parto],0,10);
			$semana_gestante=intval($riesgo->CalcularSemanasGestante($fum));
			
			$rango_inicio=array(0,28,33);
			$rango_fin=array(27,32,42);
			
			if($_REQUEST['guardar'.$pfj])
			{
				$semanas=$riesgo->ConteoSemana($inscripcion_id,$evolucion);
				$cont_semanas=sizeof($semanas);
				
				$registro_riesgo=$riesgo->GetDatosRegistrosRiegos();
				$x=0;
				for($j=0;$j<=$cont_semanas;$j++)
				{
					$nombre_riesgo=$_REQUEST['nombre_riesgo'.$pfj];
					
					for($i=0;$i<sizeof($nombre_riesgo);$i++)
					{
						$riesgo_id[$i]=substr($nombre_riesgo[$i],1,strlen($nombre_riesgo[$i]));
						$valores[$i]=substr($nombre_riesgo[$i],0,1);
					}
				}
				
				if($riesgo->guardarRegistros_Biopsicosocial($inscripcion,$riesgo_id,$valores,$semana_gestante,$cont_semanas,$evolucion,$rango_inicio,$rango_fin)==false)
				{
					$riesgo_html->frmError["MensajeError"]=$riesgo->ErrorDB();
					$riesgo_html->ban=1;
				}else
				{
					$riesgo_html->frmError["MensajeError"]="REGISTROS GUARDADOS EXITOSAMENTE";
					$riesgo_html->ban=1;
				}
			}
			
			$semanas=$riesgo->ConteoSemana($inscripcion,$evolucion);
			$cont_semanas=sizeof($semanas);
			$puntaje_gineco=$_SESSION['puntaje_gineco'];	
			
			$riesgos_bp=$riesgo->GetDatosRiesgoBiopsicosocial($programa);
			$registro_riesgo=$riesgo->GetDatosRegistrosRiegos($evolucion,$inscripcion);
			$grupo_riesgo=$riesgo->GetDatosGruposRiesgos();
			
			unset($_SESSION['puntajeBS']);
			unset($_SESSION['puntajeT']);
			$ban=0;
			for($i=0;$i<3;$i++)
			{
				for($a=0;$a<sizeof($registro_riesgo);$a++)
				{
					if($registro_riesgo[$a][semana]>=$rango_inicio[$i] AND $registro_riesgo[$a][semana]<=$rango_fin[$i])
					{
						$puntaje_riesgo=$riesgo->ObtenerPuntaje_Riesgos($inscripcion,$evolucion,$registro_riesgo[$a][semana]);
						$puntaje[$i]=$riesgo->CalculoPuntajeTotal($puntaje_gineco,$puntaje_riesgo);
						$_SESSION['puntajeBS']=$puntaje_riesgo;
						$_SESSION['puntajeT']=$puntaje[$i];
						$ban=1;
						break;
					}
				}
				if($ban==0)
				{
					$puntaje_riesgo=$puntaje_gineco;
					$puntaje[$i]=$puntaje_gineco;
					$_SESSION['puntajeBS']=$puntaje_gineco;
					$_SESSION['puntajeT']=$puntaje_gineco;
				}
			}
			
			$_REQUEST['guardar'.$pfj]="";

			return $riesgo_html->frmForma($riesgos_bp,$registro_riesgo,$grupo_riesgo,$cont_semanas,$puntaje,$rango_inicio,$rango_fin,$semana_gestante,$fcp,$evolucion);
		}
	}
?>