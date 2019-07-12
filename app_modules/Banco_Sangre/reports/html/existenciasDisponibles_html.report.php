<?php

/**
 * $Id: existenciasDisponibles_html.report.php,v 1.4 2005/06/02 19:57:30 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class existenciasDisponibles_html_report
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
    function existenciasDisponibles_html_report($datos=array())
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
	      $Salida.="<table  class=\"normal_10\" align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td colspan=\"2\" class=\"normal_10N\" align=\"center\" width=\"100%\">SOLICITUD DE COMPONENTES SANGUINEOS</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
        $Salida.="<table  class=\"normal_10\" align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
        $FechaConver1=mktime(0,0,0,date('m'),date('d'),date('Y'));
				$Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"30%\">FECHA : ".ucwords(strftime("%b %d de %Y ",$FechaConver1))."</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"left\">HORA : ".date("H:i")."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
        $Salida.="<table  class=\"normal_10\" align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"5%\">VIA</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"20%\">FAX: _____</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"20%\">URG: _____</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"30%\">CORRIENTE: _____</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
				$Salida.="<table  class=\"normal_10\" align=\"center\" border=\"1\"  width=\"100%\">";
        $Salida.="<tr>";
        $Salida.="<td class=\"normal_10N\" align=\"center\">COMPONENTE</td>";
        $grupos=$this->GruposSanguineos();
				for($i=0;$i<sizeof($grupos);$i++){
				  $Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">".$grupos[$i]['grupo_sanguineo']." / ".$grupos[$i]['rh']."</td>";
				}
				$Salida.="</tr>";
				$componentes=$this->Componentes();
				for($i=0;$i<sizeof($componentes);$i++){
          $Salida.="<tr>";
          $Salida.="<td class=\"normal_10N\">".$componentes[$i]['componente']."</td>";
					for($j=0;$j<sizeof($grupos);$j++){
					  $cantidad=$this->CantidadASolicitar($componentes[$i]['hc_tipo_componente'],$grupos[$j]['grupo_sanguineo'],$grupos[$j]['rh']);
					  $Salida.="<td align=\"center\" width=\"10%\">".$cantidad."</td>";
					}
          $Salida.="</tr>";
				}
				$Salida.="</table><BR>";
        $componentes=$this->ComponentesTopesMinimos();
				$Salida.="  <table class=\"normal_10\" border=\"1\" width=\"100%\" align=\"center\" cellpadding=\"0\" cellspadding=\"0\">";
				$Salida.="  <tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\">COMPONENTE</td>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\">&nbsp;</td>";
				foreach($componentes as $componente=>$vector){
					foreach($vector as $grupo=>$vector1){
						$Salida.="  <td class=\"normal_10N\" align=\"center\">$grupo</td>";
					}
					break;
				}
				$Salida.="  </tr>";
				$y=0;
				foreach($componentes as $componente=>$vector){
					$Salida.="	<tr>";
					$Salida.="	<td class=\"normal_10N\" width=\"5%\">$componente</td>";
					$Salida.="	<td class=\"label\" width=\"10%\">";
					$Salida.="  <table class=\"normal_10\" border=\"1\" width=\"100%\" align=\"center\" cellpadding=\"0\" cellspadding=\"0\">";
					$Salida.="	<tr class=\"$estilo1\">";
					$Salida.="	<td>Disponible</td>";
					$Salida.="	</tr>";
					$Salida.="	<tr class=\"$estilo1\">";
					$Salida.="	<td>Reserva</td>";
					$Salida.="	</tr>";
					$Salida.="  </table>";
					$Salida.="	</td>";
					foreach($vector as $grupo=>$vector1){
						//$this->salida .= "	<td width=\"5%\"><noBR>$grupo</noBR></td>";
						$Salida.="	<td>";
						$Salida.="  <table class=\"normal_10\" border=\"1\" width=\"100%\" align=\"center\" cellpadding=\"0\" cellspadding=\"0\">";
						foreach($vector1 as $indice=>$valor){
							if(($vector1['cantidaddisponible']+$vector1['cantidadnodisponible']) < $vector1['existencia_minima']){
								$estilo1='nivel4_claro';
							}
							if($indice=='cantidaddisponible'){
								$Salida.="	<tr>";
								$Salida.="	<td align=\"center\">$valor</td>";
								$Salida.="	</tr>";
							}elseif($indice=='cantidadnodisponible'){
								$Salida.="	<tr>";
								$Salida.="	<td align=\"center\">$valor</td>";
								$Salida.="	</tr>";
							}
						}
						$Salida.="  </table>";
						$Salida.="	</td>";
					}
					$Salida.="	</tr>";
					$y++;
				}
				$Salida.="  </table><BR>";
  	    return $Salida;
//*****************************************fin de termino
 }

  function CantidadASolicitar($TipoComponente,$GrupoSanguineo,$rh){
    list($dbconn) = GetDBconn();
		 $query="SELECT c.existencia_minima,
    (SELECT count(*)
    FROM banco_sangre_albaranes f,banco_sangre_bolsas d,banco_sangre_bolsas_alicuotas e
    WHERE c.tipo_componente=d.tipo_componente AND c.grupo_sanguineo=d.grupo_sanguineo AND c.rh=d.rh AND
		d.registro_albaran_id=f.registro_albaran_id AND d.ingreso_bolsa_id=e.ingreso_bolsa_id AND e.sw_estado='1') as cantidad
		FROM banco_sangre_componentes_existencias c
		WHERE c.tipo_componente='".$TipoComponente."' AND c.grupo_sanguineo='".$GrupoSanguineo."' AND c.rh='".$rh."'";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->RecordCount()>0){
				if($result->fields[1]<$result->fields[0]){
          return $result->fields[0]-$result->fields[1];
				}
			}
		}
		return 0;
	}
    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function ComponentesTopesMinimos(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.componente,b.grupo_sanguineo,b.rh,
		(SELECT count(*)
		FROM banco_sangre_albaranes f,banco_sangre_bolsas d,banco_sangre_bolsas_alicuotas e
		WHERE a.hc_tipo_componente=d.tipo_componente AND b.grupo_sanguineo=d.grupo_sanguineo AND b.rh=d.rh AND d.cruzada='0' AND
		d.registro_albaran_id=f.registro_albaran_id AND d.ingreso_bolsa_id=e.ingreso_bolsa_id AND e.sw_estado='1') as cantidaddisponible,
		(SELECT count(*)
		FROM banco_sangre_albaranes f,banco_sangre_bolsas d,banco_sangre_bolsas_alicuotas e
		WHERE a.hc_tipo_componente=d.tipo_componente AND b.grupo_sanguineo=d.grupo_sanguineo AND b.rh=d.rh AND d.cruzada='1' AND
		d.registro_albaran_id=f.registro_albaran_id AND d.ingreso_bolsa_id=e.ingreso_bolsa_id AND e.sw_estado='1') as cantidadnodisponible,c.existencia_minima
		FROM hc_tipos_componentes a,hc_tipos_sanguineos b,
		banco_sangre_componentes_existencias c
		WHERE a.hc_tipo_componente=c.tipo_componente AND b.grupo_sanguineo=c.grupo_sanguineo AND b.rh=c.rh ORDER BY a.componente,b.grupo_sanguineo,b.rh";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($result->RecordCount()>0){
				while(!$result->EOF){
					$vars[$result->fields[0]][$result->fields[1].' / '.$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

	function Componentes(){

		list($dbconn) = GetDBconn();
		$query="SELECT componente,hc_tipo_componente FROM hc_tipos_componentes ORDER BY componente";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			while(!$result->EOF){
				$vector[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		return $vector;
	}

	function GruposSanguineos(){

		list($dbconn) = GetDBconn();
		$query="SELECT grupo_sanguineo,rh FROM hc_tipos_sanguineos ORDER BY grupo_sanguineo,rh";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
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
