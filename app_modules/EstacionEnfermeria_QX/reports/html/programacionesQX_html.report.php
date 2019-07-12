<?php

/**
 * $Id: programacionesQX_html.report.php,v 1.7 2006/02/16 19:52:54 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class programacionesQX_html_report
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
    function programacionesQX_html_report($datos=array())
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
	      //$vector = $this->ReporteResultado($this->datos['fechaConsulta']);
				$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";				
				$Salida.="<tr>";						
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"25%\">EMPRESA</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"25%\">CENTRO UTILIDAD</td>";
				if($this->datos['UnidadFuc']){
					$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"25%\">UNIDAD FUNCIONAL</td>";
				}
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"25%\">DEPARTAMENTO</td>";
				$Salida.="</tr>";				
				$Salida.="<tr>";						
				$Salida.="  <td class=\"normal_10N\" align=\"center\">".$this->datos['Empresa']."</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\">".$this->datos['CentroUtilidad']."</td>";
				if($this->datos['UnidadFuc']){
					$Salida.="  <td class=\"normal_10N\" align=\"center\">".$this->datos['UnidadFuc']."</td>";
				}	
				$Salida.="  <td class=\"normal_10N\" align=\"center\">".$this->datos['dpto']."</td>";
				$Salida.="</tr>";				
				$Salida.="</table></BR>";
				
	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";				
				$Salida.="<tr>";	
				(list($ano,$mes,$dia)=explode('-',$this->datos['fechaConsulta']));			
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">PROGRAMACIONES DEL DIA ".strtoupper(strftime("%A %d de  %B de %Y",mktime(0,0,0,$mes,$dia,$ano)))."</td>";
				$Salida.="</tr>";				
				$Salida.="</table></BR>";	
				
				$rango=ModuloGetVar('app', 'Quirurgicos','RangoTurnosQuirofano');
				$SalasCirugia=$this->SeleccionQuirofanosDpto($this->datos['departamento'],$this->datos['FiltroQuirofanos']);
				$sizeof=sizeof($SalasCirugia);
				$ciclos=(int)($sizeof/2);
				if(($sizeof % 2) > 0){
					$ciclos+=1;
				}
				$inicio=0;
				if($sizeof<2){
					$fin=$sizeof;
				}else{
					$fin=2;
				}				
				if($SalasCirugia){
					for($cil=0;$cil<$ciclos;$cil++){
						$colspan=($fin-$inicio)*2;
						$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";					
						$Salida.="<tr>";
						for($i=$inicio;$i<$fin;$i++){
							$Quiro=$SalasCirugia[$i]['quirofano'];
							$abreviatura=$SalasCirugia[$i]['abreviatura'];
							$Salida.="   <td class=\"normal_10N\" align=\"center\" colspan=\"2\">$abreviatura</td>";
						}
						$Salida.="</tr>";
						if($tipoHorario=='Completo'){
							$HoraInincio='0';
							$MinutosInicio='0';
							(list($ano,$mes,$dia)=explode('-',$this->datos['fechaConsulta']));							
							$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
							$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+24),$MinutosInicio,0,$mes,$dia,$ano));
							$SumaHora=$SumaInicio;
						}else{
							$rangoInicio=ModuloGetVar('app', 'Quirurgicos','RangoInicioTurnoQuirofano');
							$rangoDuracion=ModuloGetVar('app', 'Quirurgicos','RangoDuracionTurnoQuirofano');
							$cadena=explode(':',$rangoInicio);
							$HoraInincio=$cadena[0];
							$MinutosInicio=$cadena[1];
							(list($ano,$mes,$dia)=explode('-',$this->datos['fechaConsulta']));								
							$SumaInicio=date("Y-m-d H:i:s",mktime($HoraInincio,$MinutosInicio,0,$mes,$dia,$ano));
							$SumaFinal=date('Y-m-d H:i:s',mktime(($HoraInincio+$rangoDuracion),$MinutosInicio,0,$mes,$dia,$ano));
							$SumaHora=$SumaInicio;
						}
						while($SumaHora<$SumaFinal){							
							(list($Fecha,$HoraMosDef)=explode(' ',$SumaHora));
							(list($HoraMos,$MinutosMos)=explode(':',$HoraMosDef));
							$Salida.="   <tr>";
							for($i=$inicio;$i<$fin;$i++){								
								$Quiro=$SalasCirugia[$i]['quirofano'];
								$abreviatura=$SalasCirugia[$i]['abreviatura'];
								$comprobacion=$this->ComprobarExisReserva($Quiro,$SumaHora,$rango,'0','0','0','0');						
								if($comprobacion==1){												
									$programacion=$this->consultaProgramacion($Quiro,$SumaHora,$rango);
									if($programacion[0]){										
										$Salida.=" <td class=\"normal_10N\" width=\"5%\" nowrap align=\"left\">$HoraMos : $MinutosMos</td>\n";
										$Salida.=" <td class=\"normal_10\" width=\"45%\" nowrap align=\"left\">";										
										$Salida.="		<table class=\"normal_10\" align=\"center\" border=\"0\"  width=\"100%\">";					
										if($programacion[0]['cirujano']){
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">CIRU:</td>";
											$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['cirujano']."</td>";
											$Salida.=" </tr>";
										}else{
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">CIRU:</td>";
											$Salida.=" <td width=\"95%\" nowrap>SIN ASIGNAR</td>";
											$Salida.=" </tr>";											
										}
										$Salida.=" <tr>";
										$Salida.=" <td width=\"5%\" nowrap width=\"5%\" class=\"label\">PAC:</td>";
										$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['nombre_pac']."</td>";	
										$Salida.=" </tr>";
										$EdadArr=CalcularEdad($programacion[0]['fecha_nacimiento'],$FechaFin);
										$Salida.=" <tr>";
										$Salida.=" <td width=\"5%\" nowrap class=\"label\">EDAD:</td>";
										$Salida.=" <td width=\"95%\" nowrap>".$EdadArr['edad_aprox']."</td>";	
										$Salida.=" </tr>";
										$Salida.=" <tr>";
										$Salida.=" <td width=\"5%\" nowrap class=\"label\">PLAN:</td>";
										//$Salida.=" <td width=\"95%\" nowrap>".substr($programacion[0]['plan_descripcion'],0,25)."</td>";	
										$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['plan_descripcion']."</td>";	
										$Salida.=" </tr>";										
										for($c=0;$c<sizeof($programacion[1]);$c++){
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">PRO".($c+1).":</td>";
											//$Salida.=" <td width=\"95%\" nowrap>".substr($programacion[1][$c]['descripcion'],0,25)."</td>";
											$Salida.=" <td width=\"95%\" nowrap>".$programacion[1][$c]['descripcion']."</td>";
											$Salida.=" </tr>";
										}
										if($programacion[0]['anestesiologo']){
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">ANES:</td>";
											$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['anestesiologo']."</td>";
											$Salida.=" </tr>";
										}else{
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">ANES:</td>";
											$Salida.=" <td width=\"95%\" nowrap>SIN ASIGNAR</td>";
											$Salida.=" </tr>";											
										}	
										if($programacion[0]['ayudante']){
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">AYU:</td>";
											$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['ayudante']."</td>";
											$Salida.=" </tr>";
										}else{
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">AYU:</td>";
											$Salida.=" <td width=\"95%\" nowrap>SIN ASIGNAR</td>";
											$Salida.=" </tr>";											
										}	
										if($programacion[0]['instrumentador']){
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">INST:</td>";
											$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['instrumentador']."</td>";
											$Salida.=" </tr>";
										}else{
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">INST:</td>";
											$Salida.=" <td width=\"95%\" nowrap>SIN ASIGNAR</td>";
											$Salida.=" </tr>";											
										}	
										if($programacion[0]['circulante']){
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">CIRCU:</td>";
											$Salida.=" <td width=\"95%\" nowrap>".$programacion[0]['circulante']."</td>";
											$Salida.=" </tr>";
										}else{
											$Salida.=" <tr>";
											$Salida.=" <td width=\"5%\" nowrap class=\"label\">CIRCU:</td>";
											$Salida.=" <td width=\"95%\" nowrap>SIN ASIGNAR</td>";
											$Salida.=" </tr>";											
										}								
										$Salida.="		</table>";																			
										$Salida.=" </td>\n";
									}else{
										$programacion=$this->consultaProgramacionCliente($Quiro,$SumaHora,$rango);
										if($programacion){											
											$Salida.="  <td class=\"normal_10N\" width=\"10%\" nowrap align=\"left\">$HoraMos : $MinutosMos</td>\n";
											$Salida.="  <td class=\"normal_10N\" width=\"40%\" nowrap align=\"left\">";												
											//$Salida.="  ".substr($programacion['nombre_tercero'],0,25)."";
											$Salida.="  ".$programacion['nombre_tercero']."";
											$Salida.="  </td>\n";
										}else{
											$programacion=$this->consultaProgramacionPlan($Quiro,$SumaHora,$rango);
											if($programacion){												
												$Salida.="  <td class=\"normal_10N\" width=\"10%\" nowrap align=\"left\">$HoraMos : $MinutosMos</td>\n";
												$Salida.="  <td class=\"normal_10N\" width=\"40%\" nowrap align=\"left\">";												
												//$Salida.="  ".substr($programacion['plan_descripcion'],0,25)."";
												$Salida.="  ".$programacion['plan_descripcion']."";
												$Salida.="  </td>\n";
											}
										}	
									}
								}else{
									$Salida.=" <td class=\"normal_10N\" width=\"10%\" nowrap align=\"left\">$HoraMos : $MinutosMos</td>\n";
									$Salida.=" <td class=\"normal_10N\" width=\"40%\" nowrap align=\"left\">&nbsp;</td>\n";
								}	
							}
							$this->salida .= "   </tr>";
							(list($Fecha,$HoraDef)=explode(' ',$SumaHora));
							(list($ano,$mes,$dia)=explode('-',$Fecha));
							(list($Hora,$Minutos)=explode(':',$HoraDef));
							$SumaHora=date('Y-m-d H:i:s',mktime($Hora,($Minutos+$rango),0,$mes,$dia,$ano));	
						}						
						$Salida.="</table></BR>";	
						$inicio+=2;
						$resta=($sizeof-$fin);
						if($resta<2){
							$fin+=$resta;
						}else{
							$fin+=2;
						}
					}
				}				
  	    return $Salida;
//*****************************************fin de termino
 }  
	
	/**
  *		SeleccionQuirofanosDpto
  *
  *		Funcion trae de la base de datos los deferentes quirofanos que pertenecen al departamento en donde el usuario se encuentra logueado
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool
  */

	function SeleccionQuirofanosDpto($departamento,$FiltroQuirofanos){

		list($dbconn) = GetDBconn();
		$query = "SELECT a.quirofano,a.descripcion,a.abreviatura
    FROM qx_quirofanos a
    WHERE a.departamento='".$departamento."' AND estado='1'";
    if(sizeof($FiltroQuirofanos)>0){
      $i=1;
      $query.=" AND (";
      foreach($FiltroQuirofanos as $quiro=>$indice){
        if($i==sizeof($FiltroQuirofanos)){
          $query.=" a.quirofano='".$quiro."'";
        }else{
          $query.=" a.quirofano='".$quiro."' OR ";
        }
        $i++;
      }
      $query.=" )";
    }			
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        while (!$result->EOF) {
				  $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
 		return $vars;
	}
	
	 /**
  *   ComprobarExisReserva
  *
  *   Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return bool

  */
	function ComprobarExisReserva($Quiro,$SumaHora,$rango,$plan,$empresa,$IdTercero,$TerceroId){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query ="SELECT revisar_rango_reserva_quirofano('$Quiro','$SumaHora','$time','$plan','$empresa', '$IdTercero','$TerceroId')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $respuesta=$result->fields[0];
      if($respuesta=='t'){
        $vars=1;
			}else{
        $vars=0;
			}
		}
		$result->Close();
 		return $vars;
	}
	
	/**
	* Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra programacion
	* @return array
	* @param integer numero que identifica al quirofano
	* @param date fecha que se va a evasluar en la reserva
	* @param time rango de tiempo minimo para realizar una reserva
	*/
	function consultaProgramacion($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
		$query = "SELECT consulta_programacion('$Quiro','$SumaHora','$time')";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $respuesta=$result->fields[0];
      if($respuesta=='0'){
        $vars=0;
			}else{
        $vars=$respuesta;
        $datos=$this->DatosProgramacionQX($vars);
        $vector[0]=$datos[0];
        $vector[1]=$datos[1];
      }
		}
		$result->Close();
 		return $vector;
	}
	
	/**
  *		consultaProgramacionCliente
  *
  * Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra un cliente
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */


  function consultaProgramacionCliente($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
    $query = "SELECT a.qx_quirofano_programacion_id,ter.nombre_tercero
    FROM  qx_quirofanos_programacion a,qx_reservas_quirofanos_clientes b,terceros ter
    WHERE a.qx_quirofano_programacion_id=b.qx_quirofano_programacion_id AND a.quirofano_id='$Quiro' AND
    a.qx_tipo_reserva_quirofano_id<>'0' AND '$SumaHora' >= a.hora_inicio AND '$SumaHora' < a.hora_fin + '$rango' AND
    ter.tipo_id_tercero=b.tipo_id_tercero AND ter.tercero_id=b.tercero_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
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
	
	/**
  *		consultaProgramacionCliente
  *
  * Funcion que comprueba si esta fecha que llega por paramentro se encuentra reservada por otra un plan
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */


  function consultaProgramacionPlan($Quiro,$SumaHora,$rango){
    list($dbconn) = GetDBconn();
		$time=date("H:i:s",mktime(0,(0+$rango),0,date('m'),date('d'),date('Y')));
    $query = "SELECT a.qx_quirofano_programacion_id,pl.plan_descripcion
    FROM  qx_quirofanos_programacion a,qx_reservas_quirofanos_planes b,planes pl
    WHERE a.qx_quirofano_programacion_id=b.qx_quirofano_programacion_id AND a.quirofano_id='$Quiro' AND
    a.qx_tipo_reserva_quirofano_id<>'0' AND '$SumaHora' >= a.hora_inicio AND '$SumaHora' < a.hora_fin + '$rango' AND
    pl.plan_id=b.plan_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
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
	
	/**
  *		DatosProgramacionQX
  *
  *		Funcion que busca en los los dtos de la Programacion
  *
  *		@Author Lorena Aragón G.
  *		@access Public
  *		@return boolean
  */

  function DatosProgramacionQX($programacion){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.programacion_id,ter.nombre_tercero as cirujano,
    a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_pac,
    f.nombre_tercero as anestesiologo,f.tipo_id_tercero as tipo_id_tercero_aneste,f.tercero_id as tercero_id_aneste,
    g.nombre_tercero as instrumentador,g.tipo_id_tercero as tipo_id_tercero_instru,g.tercero_id as tercero_id_instru,
    h.nombre_tercero as circulante,h.tipo_id_tercero as tipo_id_tercero_circu ,h.tercero_id as tercero_id_circu,
    i.nombre_tercero as ayudante,i.tipo_id_tercero as tipo_id_tercero_ayud,i.tercero_id as tercero_id_ayud,
    c.hora_inicio,c.hora_fin,d.descripcion as quirofano,pl.plan_descripcion,terpl.nombre_tercero as tercero_plan,diag.diagnostico_nombre,
    c.qx_quirofano_programacion_id,b.fecha_nacimiento
    FROM qx_programaciones a
    LEFT JOIN terceros ter ON (a.cirujano_id=ter.tercero_id AND a.tipo_id_cirujano=ter.tipo_id_tercero)
    LEFT JOIN qx_anestesiologo_programacion e ON(a.programacion_id=e.programacion_id)
    LEFT JOIN terceros f ON(e.tipo_id_tercero=f.tipo_id_tercero AND e.tercero_id=f.tercero_id)
    LEFT JOIN terceros g ON(e.tipo_id_instrumentista=g.tipo_id_tercero AND e.instrumentista_id=g.tercero_id)
    LEFT JOIN terceros h ON(e.tipo_id_circulante=h.tipo_id_tercero AND e.circulante_id=h.tercero_id)
    LEFT JOIN terceros i ON(e.tipo_id_ayudante=i.tipo_id_tercero AND e.ayudante_id=i.tercero_id)
    LEFT JOIN planes pl ON(a.plan_id=pl.plan_id)
    LEFT JOIN terceros terpl ON(pl.tipo_tercero_id=terpl.tipo_id_tercero AND pl.tercero_id=terpl.tercero_id)
    LEFT JOIN diagnosticos diag ON(a.diagnostico_id=diag.diagnostico_id),
    pacientes b,qx_quirofanos_programacion c,qx_quirofanos d
    WHERE a.programacion_id='".$programacion."' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
    a.programacion_id=c.programacion_id AND c.quirofano_id=d.quirofano AND c.qx_tipo_reserva_quirofano_id='3'";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector[0]=$result->GetRowAssoc($toUpper=false);
        $query = "SELECT a.procedimiento_qx,b.descripcion,a.tipo_id_cirujano||' '||a.cirujano_id as cirujano_id,ter.nombre_tercero as cirujano,a.observaciones
        FROM qx_procedimientos_programacion a
        LEFT JOIN terceros ter ON (ter.tipo_id_tercero=a.tipo_id_cirujano AND ter.tercero_id=a.cirujano_id),
        cups b
        WHERE a.programacion_id='".$programacion."' AND a.procedimiento_qx=b.cargo
        ORDER BY a.tipo_id_cirujano,a.cirujano_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          $datos=$result->RecordCount();
          if($datos){
            while(!$result->EOF){
              $vector[1][]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
        }
      }
    }		
    return $vector;
  }
	

	

    //---------------------------------------
}

?>
