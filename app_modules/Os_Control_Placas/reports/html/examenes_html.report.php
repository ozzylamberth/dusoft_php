<?php

/**
 *
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class examenes_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;

	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function examenes_html_report($datos=array())
    {
		    $this->datos=$datos;
        return true;
    }

// 	//METODO PRIVADO NO MODIFICAR
// 	function GetParametrosReport()
// 	{
// 		$parametros = array('title' => $this->title,'author' => $this->author,'sizepage' => $this->sizepage,'Orientation'=> $this->Orientation,'grayScale' => $this->grayScale,'headers' => $this->headers,'footers' =>$this->footers )
// 		return $parametros;
// 	}
//
//

	//FUNCION GetMembrete() - SI NO VA UTILIZAR MEMBRETE EXTERNO PUEDE BORRAR ESTE METODO
	//RETORNA EL MEMBRETE DEL DOCUMENTO
	//
	// SI RETORNA FALSO SIGNIFICA EL REPORTE NO UTILIZA MEMBRETE EXTERNO AL MISMO REPORTE.
	// SI RETORNA ARRAY HAY DOS OPCIONES:
	//
	// 1. SI $file='NombreMembrete' EL REPORTE UTILIZARA UN MEMBRETE UBICADO EN
	//    reports/HTML/MEMBRETES/NombreMembrete y el arraglo $datos_membrete
	//    seran los parametros especificos de este membrete.
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>'NombreMembrete','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE','subtitulo'=>'SUBTITULO'));
	// 				return $Membrete;
	// 			}
	//
	// 2. SI $file=false  SIGNIFICA QUE UTILIZA UN MEMBRETE GENERICO QUE CONCISTE EN UN
	//    LOGO (SI LO HAY), UN TITULO, UN SUBTITULO Y UNA POSICION DEL LOGO (IZQUIERDA,DERECHA O CENTRO)
	//    LOS PARAMETROS DEL VECTOR datos_membrete DEBN SER:
	//    titulo    : TITULO DE REPORTE
	//    subtitulo : SUBTITULO DEL REPORTE
	//    logo      : LA RUTA DE UN LOGO DENTRO DEL DIRECTORIO images (EN EL RAIZ)
	//    align     : POSICION DEL LOGO (left,center,right)
	//
	//	  EJEMPLO:
	//
	// 			function GetMembrete()
	// 			{
	// 				$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
	// 																		'subtitulo'=>'subtitulo'
	// 																		'logo'=>'logocliente.png'
	// 																		'align'=>'left'));
	// 				return $Membrete;
	// 			}

// 	function GetMembrete()
// 	{
// 		$Membrete = array('file'=>'MembreteDePrueba','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
// 																'subtitulo'=>'subtitulo',
// 																'logo'=>'logocliente.png',
// 																'align'=>'left'));
// 		return $Membrete;
// 	}
	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
		return $Membrete;
	}

	//FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
			
			$vector = $this->ReporteResultadoApoyod();
			$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">\n";
			$Salida.="	<tr>\n";
			$Salida.="  	<td class=\"label2_error\" align=\"center\"  >REPORTE DE PLACAS EN OTROS DEPARTAMENTOS</td>\n";
			$Salida.="	</tr>\n";
			$Salida.="</table >\n";
			$Salida.="	<br><br><br>\n";
			
			$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">\n";
			$Salida.="	<tr class=\"Normal_10N\">\n";
			$Salida.="	<td align=\"center\"  width=\"20%\">UBICACION PACIENTE</td>\n";
			$Salida.="	<td align=\"center\"  width=\"25%\">NOMBRE PACIENTE</td>\n";
			$Salida.="	<td align=\"center\"  width=\"10%\">CUMPLIMIENTO</td>\n";
			$Salida.="	<td align=\"center\"  width=\"45%\">DESCRIPCION PROCEDIMIENTO</td>\n";
			$Salida.="	</tr>\n";
			$Salida.="</table >\n";
			$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">\n";
			foreach($vector as $vec){
				$Salida.="	<tr class=\"Normal_10N\">\n";
				$Salida.="		<td align=\"center\" width=\"20%\">".$vec['descripcion']."</td>\n";
				$Salida.="		<td width=\"80%\">\n";
				foreach($vec as $vec_ingreso){
					if(!$vec_ingreso['descripcion']){
						$Salida.="		<table  align=\"center\" border=\"0\"  width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
						$Salida.="			<tr class=\"Normal_10\">\n";
						$Salida.="				<td align=\"center\" width=\"30%\">".$vec_ingreso['nombre_pac']."</td>\n";
						$Salida.="				<td width=\"70%\">\n";
						$Salida.="					<table  align=\"center\" border=\"1\"  width=\"100%\">\n";
						foreach($vec_ingreso as $vec_cargos){
							if(!$vec_cargos['nombre_pac']){
								$cumplimiento=$this->ConvierteCumplimiento($vec_cargos['fecha_cumplimiento'],$vec_cargos['numero_cumplimiento'],$_SESSION['LTRABAJO']['DPTO']);
								$Salida.="					<tr class=\"Normal_10\">\n";
								$Salida.="						<td width=\"20%\" align=\"left\" >".$cumplimiento."</td>\n";
								$Salida.="						<td width=\"75%\" align=\"left\" >".$vec_cargos['descripcion']."</td>\n";
								$Salida.="						<td width=\"5%\">&nbsp;</td>\n";
								$Salida.="					</tr>\n";
							}
						}
						$Salida.="					</table >\n";
						$Salida.="				</td>\n";
						$Salida.="			</tr>\n";
						$Salida.="		</table >\n";
					}
				}
				$Salida.="		</td>";
				$Salida.="	</tr>";
			}
			$Salida.="</table >";
			return $Salida;
	}


	//AQUI TODOS LOS METODOS QUE USTED QUIERA
	function ReporteResultadoApoyod($resultado_id)
	{
		$query="	SELECT	DISTINCT a.ingreso, 
															a.numero_cumplimiento, 
															a.fecha_cumplimiento, 
															c.primer_nombre || ' ' || c.segundo_nombre ||  ' ' || c.primer_apellido ||  ' ' || c.segundo_apellido AS nombre,
															d.descripcion AS ubicacion_paciente,
															f.cargo,
															f.descripcion,
															d.departamento,
															a.departamento_actual AS ubicacion_placa
							FROM		os_imagenes_control_placas AS a ,
											ingresos AS b,
											pacientes AS c,
											departamentos AS d,
											os_maestro AS e,
											cups AS f
							WHERE		a.ingreso = b.ingreso  AND
											b.tipo_id_paciente = c.tipo_id_paciente AND
											b.paciente_id = c.paciente_id AND
											b.departamento_actual = d.departamento AND
											a.numero_orden_id = e.numero_orden_id AND
											f.cargo = e.cargo_cups AND
											a.sw_placa_perdida != '1' AND
											b.departamento_actual != '".$_SESSION['LTRABAJO']['DPTO']."'
							ORDER BY ubicacion_paciente, nombre
							
							";
							
		list($dbconn) = GetDBconn();
		
		global $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		unset($datos_pac);
		if ($dbconn->ErrorNo() != 0)
		{
				$this->error = "Error al Consultar pacientes";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}
		else
		{
			$I=0;
			while(!$resulta->EOF)
			{
				$datos=$resulta->FetchRow();
				$datos_pac[$datos['departamento']][$datos['ingreso']][$I]=$datos;
				$datos_pac[$datos['departamento']]['descripcion']=$datos['ubicacion_paciente'];
				$datos_pac[$datos['departamento']][$datos['ingreso']]['nombre_pac']=$datos['nombre'];
				$datos_pac[$datos['departamento']][$datos['ingreso']]['ubicacion_placa']=$datos['ubicacion_placa'];
				$I++;
				//$resulta->MoveNext();
			}
		}
		
		return $datos_pac;
	}

/**
		* Concatena el numero_cumplimiento y la fecha_cumplimiento para crear le numero
		*  de cumplimiento que debe ser visto por el medico y que servira de guia para
		* el controld e los examenes, en el formato que se configuro en la tabla departamentos
		*  para cada uno d elos departamentos existentes en la empresa
		* @param $fecha_cumplimiento
		* @param $numero_cumplimiento
		* @param $departamento
		* @return $cumplimiento
		*/
		function ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento){
			list($dbconn) = GetDBconn();
			$query="SELECT	formato_cumplimiento
							FROM		departamentos
							WHERE		departamento = '$departamento'";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					echo "<br>Error BD " . $dbconn->ErrorMsg();
					return false;
			}
			$res=$result->fields[0];
			$result->Close();
			if($res=='0'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2);
			}elseif($res=='1'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-2);
			}elseif($res=='2'){
				$fecha=substr(str_replace("-","",$fecha_cumplimiento),2,-4);
			}
// 			echo "<br>-->".$fecha;exit;
			$cumplimiento=$fecha."-".$numero_cumplimiento;
			return $cumplimiento;
		}//fin ConvierteCumplimiento

function FechaStampMostrar($fecha)
     {
            if($fecha){
                    $fech = strtok ($fecha,"-");
                    for($l=0;$l<3;$l++)
                    {
                        $date[$l]=$fech;
                        $fech = strtok ("-");
                    }
                    $mes = str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT);
                    $dia = str_pad(ceil($date[2]), 2, 0, STR_PAD_LEFT);
                    return  ceil($date[0])."-".$mes."-".$dia;
            }
    }

    /**
    * Separa la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
    */
    function HoraStamp($hora)
    {
            $hor = strtok ($hora," ");
            for($l=0;$l<4;$l++)
            {
                $time[$l]=$hor;
                $hor = strtok (":");
            }

            $x = explode (".",$time[3]);
            return  $time[1].":".$time[2].":".$x[0];
    }
    //---------------------------------------




function ConsultaNombreUsuario($usuario_id)
{
	list($dbconnect) = GetDBconn();
	$query= "SELECT usuario FROM system_usuarios
	WHERE  usuario_id= ".$usuario_id."";

	$result = $dbconnect->Execute($query);
	if ($dbconnect->ErrorNo() != 0)
	{
			$this->error = "Error al Consultar el nombre del usuario";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
	}
	$a=$result->GetRowAssoc($ToUpper = false);
	return $a;
}
}

?>
