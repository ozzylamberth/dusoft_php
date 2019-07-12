<?php

/**
 * $Id: SolicDespachosTotalizadas_html.report.php,v 1.2 2006/06/06 20:34:23 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class SolicDespachosTotalizadas_html_report
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
    function SolicDespachosTotalizadas_html_report($datos=array())
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

	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">TOTAL DE MEDICAMENTOS E INSUMOS SOLICITADOS</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">DEPARTAMENTO :&nbsp&nbsp&nbsp; ".$this->datos['descripcionDpto']."</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['BODEGAS']['BodegaId']." - ".$_SESSION['BODEGAS']['NombreBodega']."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
        $vector=$this->DatosTotalesSolicitudesDpto($this->datos['departamento']);        
        if($vector[2]){
          $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"90%\">";
          $Salida.="<tr>";
          $Salida.="<td width=\"15%\" align=\"center\" class=\"label\">SOLICITUDES<br>MEDICAMENTOS</td>";
          $Salida.="<td class=\"Normal_10\" align=\"left\">";
          for($l=0;$l<sizeof($vector[2]);$l++){            
            if($l==sizeof($vector[2])-1){                
              $Salida.="".$vector[2][$l]['solicitud_id']."";             
            }else{
              $Salida.="".$vector[2][$l]['solicitud_id'].",";             
            }            
          }
          $Salida.="</td>";
          $Salida.="</tr>";
          $Salida.="</table><BR>";
        }           
        
				if($vector[0]){
          $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"90%\">";
          $Salida.="<tr>";
          $Salida.="<td align=\"center\" colspan=\"2\" class=\"label\">MEDICAMENTO</td>";
          $Salida.="<td align=\"center\" class=\"label\">CANTIDAD</td>";
          $Salida.="</tr>";
          for($i=0;$i<sizeof($vector[0]);$i++){
            $Salida.="<tr>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$vector[0][$i]['codigo_producto']."</td>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"60%\">".$vector[0][$i]['desmed']."</td>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\">".$vector[0][$i]['cant_solicitada']." ".$vector[0][$i]['abreviatura']."</td>";
            $Salida.="</tr>";
            if($vector[1][$i]['ubicacion']){
            $Salida.="<tr>";
            $Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$vector[0][$i]['ubicacion']."</td>";
            $Salida.="</tr>";
            }
          }
          $Salida.="</table><BR>";
        }
        if($vector[3]){
          $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"90%\">";
          $Salida.="<tr>";
          $Salida.="<td width=\"15%\" align=\"center\" class=\"label\">SOLICITUDES<br>INSUMOS</td>";
          $Salida.="<td class=\"Normal_10\" align=\"left\">";
          for($l=0;$l<sizeof($vector[3]);$l++){            
            if($l==sizeof($vector[3])-1){                
              $Salida.="".$vector[3][$l]['solicitud_id']."";             
            }else{
              $Salida.="".$vector[3][$l]['solicitud_id'].",";             
            }            
          }
          $Salida.="</td>";
          $Salida.="</tr>";
          $Salida.="</table><BR>";
        }      
          
        if($vector[1]){
          $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"90%\">";
          $Salida.="<tr>";
					$Salida.="<td align=\"center\" colspan=\"2\" class=\"label\">INSUMO</td>";
					$Salida.="<td align=\"center\" class=\"label\">CANTIDAD</td>";
					$Salida.="</tr>";
				  for($i=0;$i<sizeof($vector[1]);$i++){
						$Salida.="<tr>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$vector[1][$i]['codigo_producto']."</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"60%\">".$vector[1][$i]['desmed']."</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\">".$vector[1][$i]['cant_solicitada']." ".$vector[1][$i]['abreviatura']."</td>";
						$Salida.="</tr>";
            if($vector[1][$i]['ubicacion']){
            $Salida.="<tr>";
						$Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$vector[1][$i]['ubicacion']."</td>";
						$Salida.="</tr>";
            }
				  }
  	      $Salida.="</table>";
				}
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	function DatosTotalesSolicitudesDpto($departamento){

		list($dbconn) = GetDBconn();
    $query = "
             (SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		          FROM
		          (SELECT det.medicamento_id as codigo_producto,sum(det.cant_solicitada) as cant_solicitada
              FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
							a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND
							a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
              GROUP BY det.medicamento_id) as a,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
							LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
							,unidades u
						  WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada
						 )";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$query ="(SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		          FROM
		          (SELECT det.codigo_producto,sum(det.cantidad) as cant_solicitada
              FROM hc_solicitudes_medicamentos a,hc_solicitudes_insumos_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
							a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND
							a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
              GROUP BY det.codigo_producto) as a,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
							LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
							,unidades u
						 WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada)
						 ";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars1[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
    $query ="SELECT DISTINCT a.solicitud_id, 'M' as tipo
    FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d det,estaciones_enfermeria b,inventarios_productos invp
    WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
    a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND
    a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
    AND det.medicamento_id=invp.codigo_producto";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        while(!$result->EOF) {
          $vars2[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
      
    $query ="SELECT DISTINCT a.solicitud_id, 'I' as tipo 
    FROM hc_solicitudes_medicamentos a,hc_solicitudes_insumos_d det,estaciones_enfermeria b,inventarios_productos invp
    WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
    a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND
    a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'              
    AND det.codigo_producto=invp.codigo_producto";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        while(!$result->EOF) {
          $vars3[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
		$result->Close();
		$vector[0]=$vars;
		$vector[1]=$vars1;
    $vector[2]=$vars2;
    $vector[3]=$vars3;
		return $vector;
	}



    //---------------------------------------
}

?>
