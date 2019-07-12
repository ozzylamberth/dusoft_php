<?php

/**
 * $Id: programacion_qx.inc.php,v 1.2 2005/06/07 18:40:58 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

  function GenerarReporteProgramacionQX($arr)
	{
			$primerArreglo=$arr[0];
			$segundoArreglo=$arr[1];
			$TercerArreglo=$arr[2];
			$Dir="cache/programacion_qx.pdf";
			require("classes/fpdf/html_class.php");
			define('FPDF_FONTPATH','font/');
			$pdf=new PDF('P','mm','letter2');//legal
			$pdf->AddPage();
			$pdf->SetFont('Arial','',7);

			$html.="<TABLE BORDER='0' WIDTH='760'>";
			/**/
			$html.="<br><br>";
			$html.="<tr><td WIDTH='760' HEIGHT=25 ALIGN='CENTER><b>PROGRAMACION QUIRURGICA No. ".$primerArreglo['programacion_id']."</b><BR><BR></td></tr>";
			$html.="</TABLE>";
			/**/
			$html.="<TABLE BORDER='1' WIDTH='760'>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>TIPO IDENTIFICACION:</b></td><td WIDTH='310' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['tipo_id_paciente']."</td>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>No. IDENTIFICACION:</b></td><td WIDTH='150' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['paciente_id']."</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>NOMBRE:</b></td><td WIDTH='310' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['nombrepac']."</td>";
			$EdadArr=CalcularEdad($primerArreglo['fecha_nacimiento'],$FechaFin);
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>EDAD:</b></td><td WIDTH='150' HEIGHT=25 ALIGN='LEFT'> ".$EdadArr['edad_aprox']."</td>";
			$html.="</tr>";
			$html.="<tr>";
			if($primerArreglo['cirujano']){
				$varCirP=$primerArreglo['cirujano'];
			}else{
				$varCirP="&nbsp;";
			}
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>CIRUJANO PRINCIPAL:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'>$varCirP</td>";
			$html.="</tr>";
			$html.="<tr>";
			if($primerArreglo['diagnostico_nombre']){
				$varDiagP=$primerArreglo['diagnostico_nombre'];
			}else{
				$varDiagP="&nbsp;";
			}
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>DIAGNOSTICO PRINCIPAL:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'>$varDiagP</td>";
			$html.="</tr>";
			/**/
			$html.="<br>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>FECHA INICIO CIRUGIA:</b></td><td WIDTH='310' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['hora_inicio']."</td>";
			$Fecha=FechaStamp($primerArreglo['hora_inicio']);
			$CadenaFechaIn = explode ('/', $Fecha);
			$HoraDef=HoraStamp($primerArreglo['hora_inicio']);
			$CadenaHoraIn = explode (':',$HoraDef);
			$Fecha=FechaStamp($primerArreglo['hora_fin']);
			$CadenaFechaFn = explode ('/', $Fecha);
			$HoraDef=HoraStamp($primerArreglo['hora_fin']);
			$CadenaHoraFn = explode (':',$HoraDef);
      $DuracionMin=(mktime($CadenaHoraFn[0],$CadenaHoraFn[1],0,$CadenaFechaFn[1],$CadenaFechaFn[0],$CadenaFechaFn[2])-mktime($CadenaHoraIn[0],$CadenaHoraIn[1],0,$CadenaFechaIn[1],$CadenaFechaIn[0],$CadenaFechaIn[2]))/60;
			$HorasDura=(int)($DuracionMin/60);
			$HorasDura=str_pad($HorasDura,2,0,STR_PAD_LEFT);
      $MinutosDura=($DuracionMin%60);
			$MinutosDura=str_pad($MinutosDura,2,0,STR_PAD_LEFT);
			$Duracion=$HorasDura.':'.$MinutosDura;
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>DURACION (H:mm):</b></td><td WIDTH='150' HEIGHT=25 ALIGN='LEFT'> $Duracion</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>QUIROFANO:</b></td><td WIDTH='310' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['nomquirofano']."</td>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>TIPO RESERVA:</b></td><td WIDTH='150' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['nomreserva']."</td>";
			$html.="</tr>";
			$html.="<br>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>ANESTESIOLOGO:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['anestesiologo']."</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>INSTRUMENTADOR(A):</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'>"."'&nbsp;'"."".$primerArreglo['instrumentista']."</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>CIRCULANTE:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['circulante']."</td>";
			$html.="</tr>";
			$html.="<br>";
			/**/
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>VIA ACCESO:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'>&nbsp;".$primerArreglo['nomvia']."</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>AMBITO:</b></td><td WIDTH='310' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['nomtipcirugia']."</td>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>TIPO:</b></td><td WIDTH='150' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['nomambito']."</td>";
			$html.="</tr>";
			$html.="<br><br>";


			if($segundoArreglo){
			for($i=0;$i<sizeof($segundoArreglo);$i++){
				$html.="<tr>";
				$html.="<td WIDTH='100' HEIGHT=25 ALIGN='LEFT'><b>CODIGO</td>";
				$html.="<td WIDTH='410' HEIGHT=25 ALIGN='LEFT'>PROCEDIMIENTO</td>";
				$html.="<td WIDTH='250' HEIGHT=25 ALIGN='LEFT'>CIRUJANO</b></td>";
				$html.="</tr>";
				$html.="<tr>";
				$html.="<td WIDTH='100' HEIGHT=25 ALIGN='LEFT'> ".$segundoArreglo[$i]['procedimiento_qx']."</td>";
				$html.="<td WIDTH='410' HEIGHT=25 ALIGN='LEFT'> ".$segundoArreglo[$i]['nomcups']."</td>";
				$html.="<td WIDTH='250' HEIGHT=25 ALIGN='LEFT'> ".$segundoArreglo[$i]['cirujano']."</td>";
				$html.="</tr>";
				$html.="<tr>";
				$html.="<td WIDTH='100' HEIGHT=25 ALIGN='LEFT'><b>AYUDANTE:</b></td><td WIDTH='660' HEIGHT=25 ALIGN='LEFT'> ".$segundoArreglo[$i]['ayudante']."</td>";
				$html.="</tr>";
				$html.="<tr>";
				$html.="<td WIDTH='100' HEIGHT=25 ALIGN='LEFT'><b>PEDIATRA:</b></td><td WIDTH='660' HEIGHT=25 ALIGN='LEFT'> ".$segundoArreglo[$i]['pediatra']."</td>";
				$html.="</tr>";
				$html.="<br>";
			}
			}
			$html.="<br>";
			if($TercerArreglo){
			$html.="<tr>";
			$html.="<td WIDTH='510' HEIGHT=25 ALIGN='LEFT'><b>NOMBRE PAQUETE</td>";
			$html.="<td WIDTH='250' HEIGHT=25 ALIGN='LEFT'>CANTIDAD</b></td>";
			$html.="</tr>";
			for($i=0;$i<sizeof($TercerArreglo);$i++){
				$html.="<tr>";
				$html.="<td WIDTH='510' HEIGHT=25 ALIGN='LEFT'> ".$TercerArreglo[$i]['paquete']."</td>";
				$html.="<td WIDTH='250' HEIGHT=25 ALIGN='LEFT'> ".$TercerArreglo[$i]['cantidad']."</td>";
				$html.="</tr>";
			}
			}
			$html.="</TABLE>";
			$html.="<br><br>";
			$html.="<TABLE BORDER='0' WIDTH='760'>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>USUARIO:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['nomusuario']."</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="<td WIDTH='150' HEIGHT=25 ALIGN='LEFT'><b>FECHA REGISTRO:</b></td><td WIDTH='610' HEIGHT=25 ALIGN='LEFT'> ".$primerArreglo['fecha_registro']."</td>";
			$html.="</tr>";
			$html.="</table>";
			$pdf->WriteHTML($html);
			$pdf->SetLineWidth(0.3);
			$pdf->Output($Dir,'F');
			return true;
	}
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
  function HoraStamp($hora){

   $hor = strtok ($hora," ");
   for($l=0;$l<4;$l++){

		 $time[$l]=$hor;
     $hor = strtok (":");

	 }
   return  $time[1].":".$time[2].":".$time[3];
 }
?>
