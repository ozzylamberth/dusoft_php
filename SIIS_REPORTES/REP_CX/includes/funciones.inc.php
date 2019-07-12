<?php

/**
 * $Id: funciones.inc.php,v 1.4 2006/12/18 20:17:46 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Libreriar para control de limpiezas en la bd.
 */

function CalcularEdad($FechaInicio,$FechaFin)
{
	if (empty($FechaFin))
	{
	  $FechaFin=date("Y-m-d");
	}
		$FechaInicio=str_replace("/","-",$FechaInicio);
		$fech = strtok ($FechaInicio,"-");
		for($l=0;$l<3;$l++)
		{$date[$l]=$fech;
			$fech = strtok ("-");
		}
		$a=explode(" ",$date[2]);
		$date[2]=$a[0];
		$dia=$date[2];
		$mes=$date[1];
		$ano=$date[0];
		$a=explode(':',$a[1]);
		$hora=$a[0];
		$minutos=$a[1];
		$segundos=$a[2];
		if(!checkdate($mes,$dia,$ano))
		{
		  return false;
		}
		$FechaFin=str_replace("/","-",$FechaFin);
		$fech = strtok ($FechaFin,"-");
		for($l=0;$l<3;$l++)
		{$date[$l]=$fech;
			$fech = strtok ("-");
		}
		$a=explode(" ",$date[2]);
		$date[2]=$a[0];
		$a=explode(':',$a[1]);
		$hora1=$a[0];
		$minutos1=$a[1];
		$segundos1=$a[2];
    if(!checkdate($date[1],$date[2],$date[0]))
		{
		  return false;
		}
      $edad=(ceil($date[0])-$ano);
      $meses=$date[1]-$mes;
      $dias=$date[2]-$dia;
			$hora=$hora1-$hora;
			$minutos=$minutos1-$minutos;
			$segundos=$segundos1-$segundos;
      $total=($edad*365)+($meses*30)+$dias;
      $edad=floor($total/365);
      $meses=floor(($total%365)/30);
      $dias=floor(($total%365)%30);
      $edad_aprox=floor($total/365);
			if($edad_aprox>0)
			{
				$edad_aprox.=' Años';
				$edad_rips=$edad;
				$unidad_rips=1;
			}
			else
			{
				if($meses>0)
				{
					$edad_aprox=$meses.' Meses';
					$edad_rips=$meses;
					$unidad_rips=2;
				}
				else
				{
					$edad_aprox=$dias.' Dias';
					$edad_rips=$dias;
					$unidad_rips=3;
				}
			}
		$edad_en_dias=$dias + ($meses * 30) + ($edad * 365);
     	return array('anos'=>$edad,'meses'=>$meses,'dias'=>$dias,'edad_aprox'=>$edad_aprox,'edad_rips'=>$edad_rips,'unidad_rips'=>$unidad_rips, 'edad_en_dias'=>$edad_en_dias);
	}

	//calcular la fecha de nacimiento

	function CalcularFecha($edad)
	{
	  if (!empty($edad))
		{
	    $edad1=round($edad);
		  if (($edad1-$edad)<>0)
		  {
		    $meses=($edad1-$edad)*-1;
			  $meses=floor($meses*12);
		  }
		  else
		  {
		    $meses=date("m");
		  }
		  $fech = date("Y");
		  $edad2=$fech-$edad1;
		  $dias=date("d");
		  return array('anos'=>$edad2,'meses'=>$meses,'dias'=>$dias);
		}
		return false;
	}

	function CalcularFechaNacimiento($valor,$tipo)
	{					
			switch($tipo)
			{
					case 'dias':
							$nueva = mktime(0,0,0, date('m'),date('d')- $valor,date('Y'));							
							$nuevafecha=date("d/m/Y",$nueva);
							return $nuevafecha;
					break;
					case 'meses':
							$nueva = mktime(0,0,0, date('m')- $valor,date('d'),date('Y'));							
							$nuevafecha=date("d/m/Y",$nueva);
							return $nuevafecha;					
					break;
					
					default:
							$y=date('Y')-$valor;
							return date('d/m').'/'.$y;
					
			}
			return false;
	}

/**
 * Calculates the Difference between two timestamps
 *
 * @param integer $start_timestamp
 * @param integer $end_timestamp
 * @param integer $unit (default 0)
 * @return string
 * @access public
 */
function DiferenciaDias($start_timestamp,$end_timestamp,$unit= 0){

//echo "==>>fi".$start_timestamp;
//echo "==>>ff".$end_timestamp;

	$days_seconds_star= (23 * 56 * 60) + 4.091; // Star Day
  $days_seconds_sun= 24 * 60 * 60; // Sun Day
  $difference_seconds= $end_timestamp - $start_timestamp;
  switch($unit){
   case 3: // Days
     $difference_days= round(($difference_seconds / $days_seconds_sun),2);
     return $difference_hours;
   case 2: // Hours
     $difference_hours= round(($difference_seconds / 3600),2);
     return $difference_hours;
   break;
   case 1: // Minutes
     $difference_minutes= round(($difference_seconds / 60),2);
     return $difference_minutes;
   break;
   default: // Seconds
     if($difference_seconds > 1){
       return $difference_seconds;
     }
     else{
       return $difference_seconds;
     }
  }
}
?>