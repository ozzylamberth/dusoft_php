<?php
//Reporte de prueba formato HTML

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
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
//*******************************************termino
	      $vector = $this->ReporteResultado($this->datos['cadaverId'],$this->datos['informe_id'],$this->datos['prefijo']);
	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\">RESULTADO DE PATOLOGIA ".$vector[cargo]."</td>";
				$Salida.="<tr>";
				$Salida.="</tr>";
				$Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\">INFORME No. : ".$vector[prefijo]." - ".$vector[resultado_informe_id]."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";

				$Salida .="<table border='0' width='100%' align='center'>";
				$Salida.="<tr>";
				$Salida.="<td  align='left'  class=\"normal_10\" width='25%'>Identificación: ".$vector[tipo_id_paciente]." ".$vector[paciente_id]."";
				$Salida.="</td>";
				$Salida.="<td align='left' class=\"normal_10\" width='35%'>Paciente : ".strtoupper($vector[nombre])."";
				$Salida.="</td>";
				$edad_paciente = CalcularEdad($vector[fecha_nacimiento],date("Y-m-d"));
				$Salida.="<td  align='left' class=\"normal_10\" width='20%'>Edad : ".$edad_paciente[edad_aprox]."&nbsp;&nbsp; Sexo :".$vector[sexo_id]."";
				$Salida.="</td>";
				if(empty($vector[historia_prefijo]) AND empty($vector[historia_numero])){
				  $vector[historia_prefijo]=$vector[tipo_id_paciente];
					$vector[historia_numero]=$vector[paciente_id];
				}
				$Salida.="<td  align='left' class=\"normal_10\" width='25%'>HC : ".$vector[historia_prefijo]." ".$vector[historia_numero]."";
				$Salida.="</td>";
				$Salida.="</tr>";
				$Salida.="</table>";
				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				(list($ano,$mes,$dia)=explode('-',$vector[fecha]));
		    $FechaConver=mktime(0,0,0,$mes,$dia,$ano);
				$Salida.="  <td class=\"normal_10\" align=\"left\" width=\"60%\">Responsable Solicitud : ".$vector[responsable_solicitud]."</td>";
				$Salida.="  <td class=\"normal_10\" align=\"left\" width=\"40%\">Fecha de Realizacion : ".strtoupper(strftime("%B %d DE %Y",$FechaConver))."</td>";
				$Salida.="</tr>";
				$Salida.="<tr>";
				if(empty($vector[departamento])){
          $vector[departamento]=$vector[origen_solicitud];
				}
				$Salida.="  <td class=\"normal_10\" align=\"left\" width=\"60%\">Origen Solicitud : ".$vector[departamento]."</td>";
        $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"40%\">Servicio : ".$vector[servicio]."</td>";
				$Salida.="</tr>";
  	    $Salida.="</table><BR>";
				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" width=\"100%\">OBSERVACIONES RESULTADO: </td>";
				$Salida.="</tr>";
				$Salida.="</table>";
				$vector[observaciones]=str_replace("\x0a","<p></p>",$vector[observaciones]);
				$Salida.="<BLOCKQUOTE>";
				$Salida.="<div align=\"justify\" width=\"100%\" class=\"Normal_10\">".$vector[observaciones]."</div>";
				$Salida.="</BLOCKQUOTE>";
				$observaciones = $this->ReporteObservaiconesAdic($this->datos['informe_id'],$this->datos['prefijo']);
				if($observaciones){
        $Salida.="<BR><table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr class=\"Normal_10N\">";
				$Salida.="  <td colspan=\"3\" align=\"left\" width=\"100%\">OBSERVACIONES ADICIONALES: </td>";
				$Salida.="</tr>";
				$Salida.="<tr class=\"Normal_10N\">";
        $Salida.="  <td align=\"left\" nowrap width=\"16%\">FECHA </td>";
				$Salida.="  <td align=\"left\" nowrap width=\"34%\">PROFESIONAL </td>";
				$Salida.="  <td align=\"left\">OBSERVACION </td>";
				$Salida.="</tr>";
				for($i=0;$i<sizeof($observaciones);$i++){
          $Salida.="<tr class=\"Normal_10\">";
					(list($fecha,$hora)=explode(' ',$observaciones[$i]['fecha_registro']));
					(list($ano,$mes,$dia)=explode('-',$fecha));
					(list($horas,$minutos)=explode(':',$hora));
					$FechaConver=mktime($horas,$minutos,0,$mes,$dia,$ano);
					$Salida.="  <td width=\"16%\" nowrap>".strftime("%b %d de %Y %H:%M",$FechaConver)."</td>";
					$Salida.="  <td align=\"left\" nowrap width=\"34%\">".$observaciones[$i]['nombre']."</td>";
					$Salida.="  <td align=\"left\">".$observaciones[$i]['observaciones_adicionales']."</td>";
					$Salida.="</tr>";
				}
				$Salida.="</table>";
				//$Salida.="<BLOCKQUOTE>";
				//$Salida.="<div align=\"justify\" width=\"100%\" class=\"Normal_10\">".$vector[observaciones_adicionales]."</div>";
				//$Salida.="</BLOCKQUOTE>";
				}

				/*$procedimientos=$this->ReporteResultadoProcedimientos($this->datos['informe_id'],$this->datos['prefijo']);
				if($procedimientos){
				  $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"100%\">";
          $Salida.="<tr><td colspan=\"2\" align=\"center\" class=\"label\">PROCEDIMIENTOS</td></td>";
				  for($i=0;$i<sizeof($procedimientos);$i++){
						$Salida.="<tr>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$procedimientos[$i]['cargo']."</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\">".$procedimientos[$i]['descripcion']."</td>";
						$Salida.="</tr>";
				  }
  	      $Salida.="</table>";
				}*/

        $diagnosticos=$this->ReporteResultadoDiagnosticos($this->datos['informe_id'],$this->datos['prefijo']);
				if($diagnosticos){
				  $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"100%\">";
          $Salida.="<tr><td colspan=\"2\" align=\"center\" class=\"label\">DIAGNOSTICOS</td></td>";
				  for($i=0;$i<sizeof($diagnosticos);$i++){
						$Salida.="<tr>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$diagnosticos[$i]['diagnostico_id']."</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\">".$diagnosticos[$i]['diagnostico_nombre']."</td>";
						$Salida.="</tr>";
				  }
  	      $Salida.="</table>";
				}

				$Salida.="<BR><table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr class=\"Normal_10N\"><td align=\"left\" class=\"label\" width=\"100%\">&nbsp;</td></tr>";
				$Salida.="<tr class=\"Normal_10N\"><td align=\"left\" class=\"label\" width=\"100%\">&nbsp;</td></tr>";
				$Salida.="<tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" class=\"label\" width=\"100%\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;___________________________</td>";
        $Salida.="</tr>";
				$Salida.="<tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" class=\"label\" width=\"100%\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;".$vector[nombreprof]."</td>";
				$Salida.="</tr>";
				$Salida.="<tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" class=\"label\" width=\"100%\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;".$vector[especialidad]."</td>";
				$Salida.="</tr>";
				$Salida.="<tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" class=\"label\" width=\"100%\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;R.P. ".$vector[tprofesional]."</td>";
				$Salida.="</tr>";
				$Salida.="</table>";
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function ReporteResultado($cadaver,$resultado_id,$prefijo){

		list($dbconnect) = GetDBconn();
		$query="SELECT a.resultado_informe_id,a.prefijo,b.tipo_id_paciente,b.paciente_id,
		c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,c.fecha_nacimiento,
		date(c.fecha_registro) as fecha,a.observaciones,e.nombre as nombreprof,dpto.descripcion as departamento,
		f.descripcion as cargo,serv.descripcion as servicio,hc.historia_numero,hc.historia_prefijo,e.tarjeta_profesional as tprofesional,
    esp.descripcion as especialidad,b.responsable_solicitud,b.origen_solicitud,c.sexo_id
		FROM cadaveres_informes a,cadaveres_recepcion b
		LEFT JOIN departamentos dpto ON (b.departamento=dpto.departamento)
		LEFT JOIN servicios serv ON (dpto.servicio=serv.servicio)
    LEFT JOIN  historias_clinicas hc ON (b.tipo_id_paciente=hc.tipo_id_paciente AND b.paciente_id=hc.paciente_id),
		pacientes c,profesionales_usuarios d,profesionales e,tipos_cargos f,profesionales_especialidades l,especialidades esp
		WHERE a.resultado_informe_id='$resultado_id' AND a.prefijo='$prefijo' AND a.cadaver_id='$cadaver' AND
		a.cadaver_id=b.cadaver_id AND
		b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND a.usuario_id_firma=d.usuario_id AND d.tipo_tercero_id=e.tipo_id_tercero AND d.tercero_id=e.tercero_id AND
		f.tipo_cargo=a.tipo_cargo AND f.grupo_tipo_cargo=a.grupo_tipo_cargo AND e.tipo_id_tercero=l.tipo_id_tercero AND e.tercero_id=l.tercero_id AND l.especialidad=esp.especialidad";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			$vector=$result->GetRowAssoc($ToUpper = false);
		}
		return $vector;
  }

	function ReporteObservaiconesAdic($resultado_id,$prefijo){
    list($dbconnect) = GetDBconn();
		$query="SELECT f.observaciones_adicionales,f.fecha_registro,h.nombre
		FROM cadaveres_informes_observaciones_adicionales f,profesionales_usuarios g,profesionales h
		WHERE f.resultado_informe_id='$resultado_id' AND f.prefijo='$prefijo' AND
		f.usuario_id=g.usuario_id AND g.tipo_tercero_id=h.tipo_id_tercero AND g.tercero_id=h.tercero_id
		ORDER BY fecha_registro DESC";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$varsObserv[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $varsObserv;
	}

	function ReporteResultadoProcedimientos($resultado_id,$prefijo){
    list($dbconnect) = GetDBconn();
		$query="SELECT b.cargo,b.descripcion
		FROM cadaveres_informes_procedimientos a,cups b
		WHERE a.resultado_informe_id='$resultado_id' AND a.prefijo='$prefijo' AND
		a.procedimiento=b.cargo";
		$result = $dbconnect->Execute($query);
		if($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vector;
	}

	function ReporteResultadoDiagnosticos($resultado_id,$prefijo){
    list($dbconnect) = GetDBconn();
		$query="SELECT b.diagnostico_id,b.diagnostico_nombre
		FROM cadaveres_informes_diagnosticos a,diagnosticos b
		WHERE a.resultado_informe_id='$resultado_id' AND a.prefijo='$prefijo' AND
		a.diagnostico_id=b.diagnostico_id";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vector;
	}

    //---------------------------------------
}

?>
