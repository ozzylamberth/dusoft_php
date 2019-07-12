	/**
	* Calcula el tiempo de espera para ser atendido un paciente en urgencias.
	* @access private
	* @return atring
	* @param date hora de ingreso
	* @param date fecha de ingreso
	*/
 function TiempoDeEspera($Horas,$Fechas)
 {
			$FechaHoy=date("d/m/Y");
			$Fech=$FechaHoy-$Fechas;
			//$dias=$Fech*24;

			$Hora=date("H:i:s");
			$fech = strtok ($Hora,":");
			for($l=0;$l<3;$l++)
			{$hoy[$l]=$fech;
				$fech = strtok (":");
			}

			$fech = strtok ($Horas,":");
			for($l=0;$l<3;$l++)
			{$time[$l]=$fech;
				$fech = strtok (":");
			}
		return abs($hoy[0]-$time[0]).":".abs($hoy[1]-$time[1]).":".abs($hoy[2]-$time[2]);
 }
