<?php
//Reporte de prueba formato HTML

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class reporteEntregas_html_report
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
    function reporteEntregas_html_report($datos=array())
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
//*******************************************termino
	      $vector = $this->ResultadosSinEntregar($this->datos['dia']);
				if($vector){
				$Fecha=$this->datos['dia'];
				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr><td align=\"center\">SOLICITUDES PATOLOGICAS</td></tr>";
        if(empty($Fecha)){$Fecha = date("Y-m-d");}elseif($Fecha=='TODAS LAS FECHAS'){$Fecha = '';	}
				if($Fecha){
				(list($ano,$mes,$dia)=explode('-',$Fecha));
				$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
        $Salida.="<tr><td align=\"center\">".strtoupper(strftime("%B %d DE %Y",$FechaConver))."</td></tr>";
				}
        $Salida.="</table><BR><BR>";
	      $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td>No. RESULTADO</td>";
				$Salida.="  <td>PACIENTE</td>";
				$Salida.="  <td>HISTORIA</td>";
				$Salida.="  <td>TEJIDOS</td>";
				$Salida.="  <td>FECHA ENTREGA</td>";
				$Salida.="  <td>RECIBIDO</td>";
				$Salida.="</tr>";
				$prefijoAnt='-1';
				$informeAnt='-1';
				foreach($vector as $prefijo=>$vector1){
          foreach($vector1 as $informe=>$vector2){
					  $informeAnt='-1';
					  foreach($vector2 as $tejido=>$datos){
						  if($prefijo.$informe !=$prefijoAnt.$informeAnt){
                $Salida.="<tr>";
								$Salida.="  <td rowspan=\"".sizeof($vector2)."\">$prefijo $informe</td>";
								$Salida.="  <td rowspan=\"".sizeof($vector2)."\">".$datos['tipo_id_paciente']." ".$datos['paciente_id']." ".$datos['nombre']."</td>";
								$Salida.="  <td rowspan=\"".sizeof($vector2)."\">".$datos['historia_prefijo']." ".$datos['historia_numero']."</td>";
								$Salida.="  <td>".$datos['nomtejido']."</td>";
							}else{
							  $Salida.="<tr>";
                $Salida.="  <td>".$datos['nomtejido']."</td>";
								$Salida.="</tr>";
							}
							if($prefijo.$informe !=$prefijoAnt.$informeAnt){
							  $Salida.="  <td rowspan=\"".sizeof($vector2)."\" valign=\"bottom\">_____________________</td>";
								$Salida.="  <td rowspan=\"".sizeof($vector2)."\" valign=\"bottom\">_____________________</td>";
								$Salida.="</tr>";
								$prefijoAnt=$prefijo;
								$informeAnt=$informe;
							}
						}
					}
				}
  	    $Salida.="</table>";
				}
  	  return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function ResultadosSinEntregar($Fecha){
    if(empty($Fecha)){
			$Fecha = date("Y-m-d");
		}elseif($Fecha=='TODAS LAS FECHAS'){
			$Fecha = '';
		}
		list($dbconnect) = GetDBconn();
		$query="SELECT c.tipo_id_paciente,c.paciente_id,b.prefijo,b.resultado_informe_id,b.tejido_id,
		btrim(d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido|| ' '||d.segundo_apellido,'') as nombre,
    hc.historia_numero,hc.historia_prefijo,e.descripcion as nomtejido
		FROM patologias_solicitudes_detalle_informes b,patologias_solicitudes c,pacientes d
		LEFT JOIN  historias_clinicas hc ON (d.tipo_id_paciente=hc.tipo_id_paciente AND d.paciente_id=hc.paciente_id)
		,tipos_tejidos e
		WHERE b.patologia_solicitud_id=c.patologia_solicitud_id AND
		c.tipo_id_paciente=d.tipo_id_paciente AND c.paciente_id=d.paciente_id AND
		b.tejido_id=e.tejido_id";
		if($Fecha){
      $query.=" AND date(c.fecha_registro)='$Fecha'";
		}
		$query.=" ORDER BY b.resultado_informe_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vector[$result->fields[2]][$result->fields[3]][$result->fields[4]]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vector;
  }

    //---------------------------------------
}

?>
