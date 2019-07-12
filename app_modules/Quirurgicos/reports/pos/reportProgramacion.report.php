<?php

/**
 * $Id: reportProgramacion.report.php,v 1.2 2005/06/03 19:03:29 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba para impresora pos
 */

class reportProgramacion_report extends pos_reports_class
{

    //constructor por default
    function reportProgramacion_report(){
      $this->pos_reports_class();
      return true;
    }

    function CrearReporte(){
      echo 'eeeeeeeeeeeeeeeeeeeeeeee';
			$reporte=&$this->driver; //obtener el driver
			$datosProgram=&$this->datos; //obtener los datos enviados al reporte.
			$reporte->PrintFTexto($text="$datosProgram[empresa]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->PrintFTexto($text="$datosProgram[dpto]",$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$reporte->PrintFTexto($text="PROGRAMACION QX No.".$_SESSION['CIRUGIAS']['PROGRAMACION']['CODIGO'],$bold=true,$align='center',$redColor=false,$FuenteGrande=true);
			$reporte->SaltoDeLinea();
			$datosPrin=$datosProgram[datosPrincipal];
			$reporte->PrintFTexto($text="Id Pacie : $datosPrin[tipo_id_paciente] $datosPrin[paciente_id]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$cadenaPac=substr($datosPrin[nombrepac],0,31);
			$reporte->PrintFTexto($text="Paciente : $cadenaPac",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			if($datosPrin[tipo_id_cirujano] && $datosPrin[cirujano_id]){
			$reporte->PrintFTexto($text="Id Ciruj : $datosPrin[tipo_id_cirujano] $datosPrin[cirujano_id]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$cadenaCir=substr($datosPrin[nombre],0,31);
			$reporte->PrintFTexto($text="Cirujano : $cadenaCir",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			if($datosPrin[tipo_id_anest] && $datosPrin[anest_id]){
			$reporte->PrintFTexto($text="Id Anest : $datosPrin[tipo_id_anest] $datosPrin[anest_id]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$cadenaAnes=substr($datosPrin[nombreanest],0,31);
			$reporte->PrintFTexto($text="Anestesi : $cadenaAnes",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			if($datosPrin[diagnostico_nombre]){
			$reporte->PrintFTexto($text="Diag Pri : $datosPrin[diagnostico_nombre]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
      $cadenaDiag=substr($datosPrin[usuario_id].' - '.$datosPrin[nombreusu],0,31);
			$reporte->PrintFTexto($text="Programa : $cadenaDiag",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->SaltoDeLinea();
			$datosQuiro=$datosProgram[datosQuiro];
			$cadenaQuiro=substr($datosQuiro[abreviatura].' '.$datosQuiro[quiro],0,31);
			$FechaIn=$this->FechaStamp($datosQuiro[hora_inicio]);
			$cadenaFechaIn = explode ('/', $FechaIn);
			$HoraDefIn=$this->HoraStamp($datosQuiro[hora_inicio]);
			$cadenaHoraIn = explode (':',$HoraDefIn);
			$Fecha=$this->FechaStamp($datosQuiro[hora_fin]);
			$cadenaFechaFn = explode ('/', $Fecha);
			$HoraDef=$this->HoraStamp($datosQuiro[hora_fin]);
			$cadenaHoraFn = explode (':',$HoraDef);
			$DuracionMin=(mktime($cadenaHoraFn[0],$cadenaHoraFn[1],0,$cadenaFechaFn[1],$cadenaFechaFn[0],$cadenaFechaFn[2])-mktime($cadenaHoraIn[0],$cadenaHoraIn[1],0,$cadenaFechaIn[1],$cadenaFechaIn[0],$cadenaFechaIn[2]))/60;
			$HorasDura=(int)($DuracionMin/60);
			$HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
      $MinutosDura=($DuracionMin%60);
			$MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
			$Duracion=$HorasDura.':'.$MinutosDura;
			$cadenausur=substr($datosQuiro[usuario_id].' - '.$datosQuiro[nombre],0,31);
      $reporte->PrintFTexto($text="Quirofan : $cadenaQuiro",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Hora Ini : $FechaIn $HoraDefIn",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Duracion : $Duracion horas",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->PrintFTexto($text="Reserva  : $cadenausur",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			$reporte->SaltoDeLinea();
      if($datosPrin[viaacceso]){
			$reporte->PrintFTexto($text="Via Acce : $datosPrin[viaacceso]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			if($datosPrin[tipoidcirugia]){
			$reporte->PrintFTexto($text="Tipo Cir : $datosPrin[tipoidcirugia]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			if($datosPrin[ambito]){
			$reporte->PrintFTexto($text="Ambito   : $datosPrin[ambito]",$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
			}
			$reporte->SaltoDeLinea();
			$procedimientos=$datosProgram['Procedimientos'];
			if($procedimientos){
			$reporte->PrintFTextoValor($text='PROCEDIMIENTO',$valor='',$decimales=0,$signoMoneda=false,$posiciones=0,$text_bold=true,$align_text='left');
			$reporte->SaltoDeLinea();
			for($i=0;$i<sizeof($procedimientos);$i++)//por cada medicamento de la solicitud
			{
			  $reporte->PrintFTexto($procedimientos[$i]['procedimiento_qx'].'  '.$procedimientos[$i]['procedimiento'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Id Ciruj :".$procedimientos[$i]['tipo_id_cirujano'].' '.$procedimientos[$i]['cirujano_id'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$cadcirpro=substr($procedimientos[$i]['nomcir'],0,31);
				$reporte->PrintFTexto($text="Cirujano :".$cadcirpro,$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Id Ayuda :".$procedimientos[$i]['tipo_id_ayudante'].' '.$procedimientos[$i]['ayudante_id'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$cadayupro=substr($procedimientos[$i]['nomay'],0,31);
				$reporte->PrintFTexto($text="Ayudante :".$cadayupro,$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->PrintFTexto($text="Plan     :".$procedimientos[$i]['plan'],$bold=false,$align='left',$redColor=false,$FuenteGrande=false);
				$reporte->SaltoDeLinea();
			}
			$reporte->SaltoDeLinea();
			}
			$reporte->PrintEnd();
			$reporte->OpenCajaMonedera();
			$reporte->PrintCutPaper();
			return true;
    }

		/**
		* Funcion que se encarga de separar la fecha del formato timestamp
		* @return array
		*/
		function FechaStamp($fecha){

			if($fecha){
				$fech = strtok ($fecha,"-");
				for($l=0;$l<3;$l++)
				{
					$date[$l]=$fech;
					$fech = strtok ("-");
				}
				return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
		}
		/**
		* Funcion que se encarga de separar la hora del formato timestamp
		* @return array
		*/
		function HoraStamp($hora){
			$hor = strtok ($hora," ");
			for($l=0;$l<4;$l++){

				$time[$l]=$hor;
				$hor = strtok (":");

			}
			return  $time[1].":".$time[2].":".$time[3];
	  }
	}
?>
