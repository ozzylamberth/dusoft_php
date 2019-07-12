<?php

/**
 * $Id: SolicDevolucionesTotalizadas_html.report.php,v 1.1.1.1 2009/09/11 20:36:49 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class SolicDevolucionesTotalizadas_html_report
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
    function SolicDevolucionesTotalizadas_html_report($datos=array())
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
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">TOTAL DE MEDICAMENTOS E INSUMOS DEVUELTOS</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">DEPARTAMENTO :&nbsp&nbsp&nbsp; ".$this->datos['descripcionDpto']."</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['BODEGAS']['BodegaId']." - ".$_SESSION['BODEGAS']['NombreBodega']."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
        $vector=$this->DatosTotalesDevolucionesDpto($this->datos['departamento']);
				if($vector){
          $Salida.="<BR><table  align=\"center\" border=\"1\"  width=\"90%\">";
          $Salida.="<tr>";
          $Salida.="<td align=\"center\" class=\"normal_10N\">CODIGO</td>";
          $Salida.="<td align=\"center\" class=\"normal_10N\">DESCRIPCION PRODUCTO</td>";
          $Salida.="<td align=\"center\" class=\"normal_10N\">CANTIDAD</td>";
          $Salida.="</tr>";
          for($i=0;$i<sizeof($vector);$i++){
            $Salida.="<tr>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$vector[$i]['codigo_producto']."</td>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"60%\">".$vector[$i]['desmed']."</td>";
            $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\">".$vector[$i]['cantidad']." ".$vector[$i]['abreviatura']."</td>";
            $Salida.="</tr>";
            if($vector[$i]['ubicacion']){
            $Salida.="<tr>";
            $Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$vector[$i]['ubicacion']."</td>";
            $Salida.="</tr>";
            }
          }
          $Salida.="</table><BR>";
        }
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	function DatosTotalesDevolucionesDpto($departamento){

		list($dbconn) = GetDBconn();
		$query ="(SELECT a.codigo_producto,a.cantidad,invp.descripcion as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		          FROM
		          (SELECT det.codigo_producto,sum(det.cantidad) as cantidad
              FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
							a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.estado='0' AND
							a.documento=det.documento AND det.estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
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
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;
	}


    //---------------------------------------
}

?>
