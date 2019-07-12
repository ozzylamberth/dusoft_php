<?php

/**
 * $Id: informe_gestion_html.report.php,v 1.1 2005/08/31 16:43:34 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class informe_gestion_html_report
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
    function informe_gestion_html_report($datos=array())
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
	      $vector = $this->ReporteResultado($this->datos['FechaInicial'],$this->datos['FechaFinal'],$this->datos['TipoGrupoCargo'],$this->datos['Patologo']);
	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td colspan=\"2\" class=\"normal_10N\" align=\"center\" width=\"100%\">INFORME DE GESTION RESULTADOS DE PATOLOGIA</td>";
				$Salida.="</tr>";
				if($this->datos['FechaInicial']){
				(list($fecha)=explode(' ',$this->datos['FechaInicial']));
				(list($dia,$mes,$ano)=explode('/',$fecha));
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">FECHA INICIO : ".ucwords(strftime("%b %d de %Y",mktime(0,0,0,$mes,$dia,$ano)))."</td>";
				$Salida.="</tr>";
				}
				if($this->datos['FechaFinal']){
				(list($fecha)=explode(' ',$this->datos['FechaFinal']));
				(list($dia,$mes,$ano)=explode('/',$fecha));
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">FECHA FIN : ".ucwords(strftime("%b %d de %Y",mktime(0,0,0,$mes,$dia,$ano)))."</td>";
				$Salida.="</tr>";
				}
				if(!empty($this->datos['Patologo']) && $this->datos['Patologo']!=-1){
				(list($tipoIdPatologo,$PatologoId)=explode('||//',$this->datos['Patologo']));
				$nom=$this->nombreTercero($tipoIdPatologo,$PatologoId);
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">PROFESIONAL : ".$nom."</td>";
				$Salida.="</tr>";
				}
				$Salida.="</table><BR>";
				if($vector){
					$Salida.="     <table align=\"center\" border=\"1\"  width=\"80%\">";
					$Salida.= "	   <tr>";
					$Salida.= "	   <td align=\"center\">TIPO RESULTADO</td>";
					$Salida.= "	   <td align=\"center\">CANTIDAD</td>";
					$Salida.= "	   </tr>";
					for($i=0;$i<sizeof($vector);$i++){
						$Salida.= "    <tr>";
						$Salida.= "	   <td>".$vector[$i]['descripcion']."</td>";
						$Salida.= "	   <td>".$vector[$i]['total']."</td>";
						$Salida.= "    </tr>";
					}
					$Salida.= "    </table><BR>";
				}
				$Salida.="  <BR><table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="  <tr class=\"Normal_10N\"><td align=\"left\" class=\"label\" width=\"100%\">&nbsp;</td></tr>";
				$Salida.="  <tr class=\"Normal_10N\"><td align=\"left\" class=\"label\" width=\"100%\">&nbsp;</td></tr>";
				$nom=$this->nombreUsuario($this->datos['usuarioImprime']);
				$Salida.="  <tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" class=\"label\" width=\"100%\">USUARIO: ".$nom."</td>";
				$Salida.="  </tr>";
				$Salida.="  <tr class=\"Normal_10N\">";
				$Salida.="  <td align=\"left\" class=\"label\" width=\"100%\">".ucwords(strftime("%b %d de %Y %H:%M",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"))))."</td>";
				$Salida.="  </tr>";
				$Salida.="  </table>";
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function ReporteResultado($FechaInicial,$FechaFinal,$TipoGrupoCargo,$Patologo){

		list($dbconn) = GetDBconn();
		$query="SELECT a.tipo_cargo,a.grupo_tipo_cargo,a.prefijo,count(*) as total,
		(SELECT descripcion FROM tipos_cargos WHERE a.tipo_cargo=tipo_cargo AND a.grupo_tipo_cargo=grupo_tipo_cargo) as descripcion
    FROM patologias_resultados_solicitudes a,patologias_tipos_cargos b
		WHERE a.tipo_cargo=b.tipo_cargo AND a.grupo_tipo_cargo=b.grupo_tipo_cargo
		AND a.prefijo=b.prefijo";
		if($FechaInicial && $FechaFinal){
		  (list($dia,$mes,$ano)=explode('/',$FechaInicial));
			(list($dia1,$mes1,$ano1)=explode('/',$FechaFinal));
      $query.=" AND date(a.fecha_registro) BETWEEN '".$ano."-".$mes."-".$dia."' AND '".$ano1."-".$mes1."-".$dia1."'";
		}
		if(!empty($TipoGrupoCargo) && $TipoGrupoCargo!=-1){
		  (list($tipoCargo,$grupoCargo,$prefijo)=explode('||//',$TipoGrupoCargo));
      $query.=" AND a.tipo_cargo='".$tipoCargo."' AND a.grupo_tipo_cargo='".$grupoCargo."' AND a.prefijo='".$prefijo."'";
		}
		if(!empty($Patologo) && $Patologo!=-1){
		  (list($tipoIdPatologo,$PatologoId)=explode('||//',$Patologo));
      $query.=" AND a.tipo_id_tercero='".$tipoIdPatologo."' AND a.tercero_id='".$PatologoId."'";
		}
		$query.=" GROUP BY a.tipo_cargo,a.grupo_tipo_cargo,a.prefijo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
 		return $vars;
  }

	function nombreUsuario($usuarioImprime){
    list($dbconnect) = GetDBconn();
		$query="SELECT nombre
		FROM system_usuarios
		WHERE usuario_id='".$usuarioImprime."'";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
      return $result->fields[0];
		}
	}

	function nombreTercero($TerceroId,$Tercero){
    list($dbconnect) = GetDBconn();
		$query="SELECT c.nombre_tercero
		FROM terceros c
		WHERE c.tipo_id_tercero='".$TerceroId."' AND c.tercero_id='".$Tercero."'";
		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0){
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}else{
      return $result->fields[0];
		}
	}




    //---------------------------------------
}

?>
