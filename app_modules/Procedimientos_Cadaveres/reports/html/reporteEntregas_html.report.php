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
				$Salida.="  <td>No. CADAVER</td>";
				$Salida.="  <td>FECHA ENTREGA</td>";
				$Salida.="  <td>RECIBIDO</td>";
				$Salida.="</tr>";
				for($i=0;$i<sizeof($vector);$i++){
          $Salida.="<tr>";
					$Salida.="  <td>".$vector[$i]['prefijo']." ".$vector[$i]['resultado_informe_id']."</td>";
					$Salida.="  <td>".$vector[$i]['tipo_id_paciente']." ".$vector[$i]['paciente_id']." ".$vector[$i]['nombre']."</td>";
					$Salida.="  <td>".$vector[$i]['historia_prefijo']." ".$vector[$i]['historia_numero']."</td>";
					$Salida.="  <td>".$vector[$i]['cadaver_id']."</td>";
					$Salida.="  <td valign=\"bottom\">_____________________</td>";
					$Salida.="  <td valign=\"bottom\">_____________________</td>";
          $Salida.="<tr>";
				}
        $Salida.="</table>";
  	    return $Salida;
			}
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
		$query="SELECT a.cadaver_id,b.tipo_id_paciente,b.paciente_id,a.prefijo,a.resultado_informe_id,
		btrim(c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido|| ' '||c.segundo_apellido,'') as nombre,
    hc.historia_numero,hc.historia_prefijo
		FROM cadaveres_informes a,cadaveres_recepcion b,pacientes c
		LEFT JOIN  historias_clinicas hc ON (c.tipo_id_paciente=hc.tipo_id_paciente AND c.paciente_id=hc.paciente_id)
		WHERE a.cadaver_id=b.cadaver_id AND b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id";
		if($Fecha){
      $query.=" AND date(b.fecha_registro)='$Fecha'";
		}
		$query.=" ORDER BY b.tipo_id_paciente,b.paciente_id";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vector[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vector;
  }

    //---------------------------------------
}

?>
