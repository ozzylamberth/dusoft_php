<?php

/**
 * $Id: programacionQX_html.report.php,v 1.5 2007/01/19 19:06:39 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class programacionQX_html_report
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
    function programacionQX_html_report($datos=array())
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
	      $vector = $this->DatosProgramacionQX($this->datos['programacion']);
	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td colspan=\"2\" class=\"normal_10N\" align=\"center\" width=\"100%\">No. PROGRAMACION ".$this->datos['programacion']."</td>";
				$Salida.="</tr>";
        $Salida.="</table></BR>";
        $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
        if($vector['cirujano']){
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" width=\"20%\">CIRUJANO PRINCIPAL</td>";
        $Salida.="  <td class=\"normal_10\" colspan=\"3\">".$vector['cirujano']."</td>";
				$Salida.="</tr>";
        }
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" width=\"20%\">PACIENTE</td>";
        $Salida.="  <td class=\"normal_10\" colspan=\"3\">".$vector['tipo_id_paciente']." ".$vector['paciente_id']." - ".$vector['nombre_pac']."</td>";
				$Salida.="</tr>";
        if($vector['plan_descripcion']){
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" width=\"20%\">PLAN</td>";
        $Salida.="  <td class=\"normal_10\">".$vector['plan_descripcion']."</td>";
        $Salida.="  <td class=\"normal_10N\" width=\"20%\">RESPONSABLE</td>";
        $Salida.="  <td class=\"normal_10\">".$vector['tercero_plan']."</td>";
				$Salida.="</tr>";
        }
        if($vector['diagnostico_nombre']){
        $Salida.=" <tr>";
				$Salida.="  <td class=\"normal_10N\" width=\"20%\">DIAGNOSTICO</td>";
        $Salida.="  <td class=\"normal_10\" colspan=\"3\">".$vector['diagnostico_nombre']."</td>";
				$Salida.="</tr>";
        }
				$Salida.="</table><BR>";
        if(!empty($vector['hora_inicio'])){
          $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
          $Salida.="<tr>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">QUIROFANO</td>";
          $Salida.="  <td class=\"normal_10\">".$vector['quirofano']."</td>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">FECHA</td>";
          (list($fecha,$hora)=explode(' ',$vector['hora_inicio']));
          (list($ano,$mes,$dia)=explode('-',$fecha));
          (list($hh,$mm)=explode(':',$hora));
          $Salida.="  <td class=\"normal_10\">".ucfirst(strftime("%b %d de %Y",mktime(0,0,0,$mes,$dia,$ano)))."</td>";
          $Salida.="</tr>";
          $Salida.="<tr>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">HORA INICIO (HH:mm)</td>";
          $Salida.="  <td class=\"normal_10\">".$hh.":".$mm."</td>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">DURACION (HH:mm)</td>";
          (list($fechaF,$horaF)=explode(' ',$vector['hora_fin']));
          (list($hhF,$mmF)=explode(':',$horaF));
          $minutos=((mktime($hhF,$mmF,0,$mes,$dia,$ano))-mktime($hh,$mm,0,$mes,$dia,$ano))/60;
          $horas=(int)($minutos/60);
          $min=($minutos%60);
          $Salida.="  <td class=\"normal_10\">".str_pad($horas,2,0, STR_PAD_LEFT).":".str_pad($min,2,0, STR_PAD_LEFT)."</td>";
          $Salida.="</tr>";
          $Salida.="</table><BR>";
        }
        $vector3 = $this->DatosProgramacionEquiposQX($vector['qx_quirofano_programacion_id']);
        if($vector3){
          $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
          $Salida.="<tr><td class=\"normal_10N\" width=\"50%\">DEPARTAMENTO</td><td class=\"normal_10N\" width=\"50%\">EQUIPO</td></tr>";
          for($i=0;$i<sizeof($vector3);$i++){
            $Salida.="<tr><td class=\"normal_10\">".$vector3[$i]['depart']."</td><td class=\"normal_10\">".$vector3[$i]['descripcion']."</td></tr>";
          }
          $Salida.="</table><BR>";
        }
        $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
        if($vector['anestesiologo'] || $vector['ayudante']){
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" width=\"20%\">ANESTESIOLOGO</td>";
        $Salida.="  <td class=\"normal_10\">".$vector['anestesiologo']."</td>";
        $Salida.="  <td class=\"normal_10N\" width=\"20%\">AYUDANTE</td>";
        $Salida.="  <td class=\"normal_10\">".$vector['ayudante']."</td>";
				$Salida.="</tr>";
        }
        if($vector['instrumentador'] || $vector['circulante']){
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" width=\"20%\">INSTRUMENTADOR</td>";
        $Salida.="  <td class=\"normal_10\">".$vector['instrumentador']."</td>";
        $Salida.="  <td class=\"normal_10N\" width=\"20%\">CIRCULANTE</td>";
        $Salida.="  <td class=\"normal_10\">".$vector['circulante']."</td>";
				$Salida.="</tr>";
        }
				$Salida.="</table><BR>";
        $vector1 = $this->DatosProgramacionRipsQX($this->datos['programacion']);
        if($vector1){
          $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
          $Salida.="<tr>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">VIA ACCESO</td>";
          $Salida.="  <td class=\"normal_10\">".$vector1['via']."</td>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">TIPO CIRUGIA</td>";
          $Salida.="  <td class=\"normal_10\">".$vector1['tipo']."</td>";
          $Salida.="</tr>";
          $Salida.="<tr>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">AMBITO CIRUGIA</td>";
          $Salida.="  <td class=\"normal_10\">".$vector1['ambito']."</td>";
          $Salida.="  <td class=\"normal_10N\" width=\"20%\">FINALIDAD CIRUGIA</td>";
          $Salida.="  <td class=\"normal_10\">".$vector1['finalidad']."</td>";
          $Salida.="</tr>";
          $Salida.="</table><BR>";
        }
        $vector2 = $this->DatosProgramacionProcedimientosQX($this->datos['programacion']);
        if($vector2){
          $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
          $Salida.="<tr><td class=\"normal_10N\" align=\"center\">PROCEDIMIENTOS</td></tr>";
          for($i=0;$i<sizeof($vector2);$i++){
            $Salida.="<tr><td>";
            $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
            $Salida.="<tr>";
            $Salida.="<td class=\"normal_10N\" width=\"20%\">PROCEDIMIENTO</td>";
            $Salida.="<td class=\"normal_10\">".$vector2[$i]['procedimiento_qx']." ".$vector2[$i]['descripcion']."</td>";
            $Salida.="</tr>";
            
            $Salida.="         <tr class=\"modulo_list_claro\"><td colspan=\"2\">";
            $procedimientosOpc=$this->BuscarProcedimientosInsertados($this->datos['programacion'],$vector2[$i]['procedimiento_qx']);
            if($procedimientosOpc){
              $Salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
              $Salida.="<tr class=\"normal_10N\">";
              $Salida.="<td width=\"10%\">CODIGO</td>";
              $Salida.="<td>PROCEDIMIENTO</td>";      
              $Salida.="</tr>";        
              for($m=0;$m<sizeof($procedimientosOpc);$m++){
                $Salida.="<tr class=\"normal_10\">";
                $Salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
                $Salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";        
                $Salida.="</tr>";
              }        
              $Salida.="</table>";
            }
            $Salida.="         </td></tr>";    
            
            $Salida.="<tr>";
            $Salida.="<td class=\"normal_10N\" width=\"20%\">CIRUJANO</td>";
            $Salida.="<td class=\"normal_10\">".$vector2[$i]['cirujano']."</td>";
            $Salida.="</tr>";
            $Salida.="<tr>";
            $Salida.="<td class=\"normal_10N\" width=\"20%\">OBSERVACIONES</td>";
            $Salida.="<td class=\"normal_10\">".$vector2[$i]['observaciones']."</td>";
            $Salida.="</tr>";
            $Salida.="</table><BR>";
            $Salida.="</td></tr>";
          }
          $Salida.="</table><BR>";
        }
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function DatosProgramacionQX($programacionId){

		list($dbconnect) = GetDBconn();
		$query = "SELECT a.programacion_id,ter.nombre_tercero as cirujano,
    a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_pac,
    f.nombre_tercero as anestesiologo,f.tipo_id_tercero as tipo_id_tercero_aneste,f.tercero_id as tercero_id_aneste,
    g.nombre_tercero as instrumentador,g.tipo_id_tercero as tipo_id_tercero_instru,g.tercero_id as tercero_id_instru,
    h.nombre_tercero as circulante,h.tipo_id_tercero as tipo_id_tercero_circu ,h.tercero_id as tercero_id_circu,
    i.nombre_tercero as ayudante,i.tipo_id_tercero as tipo_id_tercero_ayud,i.tercero_id as tercero_id_ayud,
    c.hora_inicio,c.hora_fin,d.descripcion as quirofano,pl.plan_descripcion,terpl.nombre_tercero as tercero_plan,diag.diagnostico_nombre,
    c.qx_quirofano_programacion_id
    FROM qx_programaciones a
    LEFT JOIN terceros ter ON (a.cirujano_id=ter.tercero_id AND a.tipo_id_cirujano=ter.tipo_id_tercero)
    LEFT JOIN qx_anestesiologo_programacion e ON(a.programacion_id=e.programacion_id)
    LEFT JOIN terceros f ON(e.tipo_id_tercero=f.tipo_id_tercero AND e.tercero_id=f.tercero_id)
    LEFT JOIN terceros g ON(e.tipo_id_instrumentista=g.tipo_id_tercero AND e.instrumentista_id=g.tercero_id)
    LEFT JOIN terceros h ON(e.tipo_id_circulante=h.tipo_id_tercero AND e.circulante_id=h.tercero_id)
    LEFT JOIN terceros i ON(e.tipo_id_ayudante=i.tipo_id_tercero AND e.ayudante_id=i.tercero_id)
    LEFT JOIN planes pl ON(a.plan_id=pl.plan_id)
    LEFT JOIN terceros terpl ON(pl.tipo_tercero_id=terpl.tipo_id_tercero AND pl.tercero_id=terpl.tercero_id)
    LEFT JOIN diagnosticos diag ON(a.diagnostico_id=diag.diagnostico_id)
    LEFT JOIN qx_quirofanos_programacion c ON(a.programacion_id=c.programacion_id AND c.qx_tipo_reserva_quirofano_id='3')
    LEFT JOIN qx_quirofanos d ON(c.quirofano_id=d.quirofano),
    pacientes b
    WHERE a.programacion_id='".$programacionId."' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id";
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

  function DatosProgramacionRipsQX($programacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.tipo_cirugia,b.descripcion as tipo,a.ambito_cirugia,c.descripcion as ambito,
    a.via_acceso,d.descripcion as via,a.finalidad_procedimiento_id,e.descripcion as finalidad
    FROM qx_datos_procedimientos_cirugias a
    LEFT JOIN qx_tipos_cirugia b ON (a.tipo_cirugia=b.tipo_cirugia_id)
    LEFT JOIN qx_ambitos_cirugias c ON(a.ambito_cirugia=c.ambito_cirugia_id)
    LEFT JOIN qx_vias_acceso d ON(a.via_acceso=d.via_acceso)
    LEFT JOIN qx_finalidades_procedimientos e ON(a.finalidad_procedimiento_id=e.finalidad_procedimiento_id)
    WHERE a.programacion_id='".$programacionId."'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  function DatosProgramacionProcedimientosQX($programacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.procedimiento_qx,b.descripcion,a.tipo_id_cirujano||' '||a.cirujano_id as cirujano_id,ter.nombre_tercero as cirujano,a.observaciones
    FROM qx_procedimientos_programacion a
    LEFT JOIN terceros ter ON (ter.tipo_id_tercero=a.tipo_id_cirujano AND ter.tercero_id=a.cirujano_id),
    cups b
    WHERE a.programacion_id='".$programacionId."' AND a.procedimiento_qx=b.cargo
    ORDER BY a.tipo_id_cirujano,a.cirujano_id";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vector[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vector;
  }

  function DatosProgramacionEquiposQX($QuirofanoProgram){

    list($dbconn) = GetDBconn();
    $query = "SELECT b.descripcion,dpto.descripcion as depart
    FROM qx_equipos_programacion a,qx_equipos_moviles b,departamentos dpto
    WHERE a.qx_quirofano_programacion_id='$QuirofanoProgram' AND
    a.equipo_id=b.equipo_id AND b.departamento=dpto.departamento";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vector[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vector;
  }

  function BuscarProcedimientosInsertados($programacion_qx,$cargo){          
      
      list($dbconn) = GetDBconn();
      $query = "SELECT b.procedimiento_opcion,b.descripcion
                FROM qx_cups_opc_procedimientos_programacion a,qx_cups_opciones_procedimientos b 
                WHERE a.programacion_id='".$programacion_qx."' 
                AND a.procedimiento_qx='".$cargo."' 
                AND a.procedimiento_qx=b.cargo AND a.procedimiento_opcion=b.procedimiento_opcion";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while (!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }                 
      return $vars;
      
  }


    //---------------------------------------
}

?>
