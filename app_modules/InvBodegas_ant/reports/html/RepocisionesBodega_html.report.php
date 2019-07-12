<?php

/**
 * $Id: RepocisionesBodega_html.report.php,v 1.5 2005/12/29 14:45:42 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class RepocisionesBodega_html_report
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
    function RepocisionesBodega_html_report($datos=array())
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

        (list($centroUtilidad,$Bodega,$TipoReposicion)=explode('/',$this->datos['DatosBodega']));				
        $vector = $this->ProductosParaRepocision($centroUtilidad,$Bodega);
	      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td colspan=\"2\" class=\"normal_10N\" align=\"center\" width=\"100%\">CENTRO DE UTILIDAD :&nbsp&nbsp&nbsp; ".$_SESSION['BODEGAS']['NombreCU']."</td>";
				$Salida.="</tr>";
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['BODEGAS']['BodegaId']." - ".$_SESSION['BODEGAS']['NombreBodega']."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";

				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="  <td colspan=\"2\" class=\"normal_10N\" align=\"center\" width=\"100%\">BODEGA DESTINO DE LA TRANSFERENCIA :&nbsp&nbsp&nbsp; ".$this->datos['NombreBodega']." ( ".$this->datos['NombreCentro']." )</td>";
				$Salida.="</tr>";
				$Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">FECHA ULTIMA TRANSFERENCIA :&nbsp&nbsp&nbsp; ".$this->datos['UltimaTransferencia']."</td>";
				$Salida.="</tr>";
				if($TipoReposicion=='MIN'){
					$Salida.="<tr>";
					$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">REPOSICION SOBRE EXISTENCIAS MINIMAS</td>";
					$Salida.="</tr>";
				}else{
          $Salida.="<tr>";
					$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">REPOSICION SOBRE EXISTENCIAS MAXIMAS</td>";
					$Salida.="</tr>";
				}
				$Salida.="</table><BR>";
				if($vector){
				  $Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"90%\">";
          $Salida.="<tr>";
					$Salida.="<td align=\"center\" class=\"label\">CODIGO</td>";
					$Salida.="<td align=\"center\" class=\"label\">DESCRIPCION</td>";
					$Salida.="<td align=\"center\" class=\"label\">EXISTENCIAS</td>";
					$Salida.="<td align=\"center\" class=\"label\">CANTIDAD REQUERIDA</td>";
					$Salida.="<td align=\"center\" class=\"label\">CANTIDAD A DESPACHAR</td>";
					$Salida.="</tr>";
				  for($i=0;$i<sizeof($vector);$i++){
						$Salida.="<tr>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$vector[$i]['codigo_producto']."</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\">".$vector[$i]['descripcion']."</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">".$vector[$i]['existencia']."</td>";

						if($TipoReposicion=='MIN'){
							$cantidad=$vector[$i]['existencia_minima']-$vector[$i]['existencia'];
							if($cantidad > 0 && $cantidad<=$vector[$i]['exisobodega']){
								$defecto=$cantidad;
							}elseif($cantidad > 0 && $vector[$i]['exisobodega']>0){
								$defecto=$vector[$i]['exisobodega'];
							}else{
								$defecto=0;
							}
						}else{
							$cantidad=$vector[$i]['existencia_maxima']-$vector[$i]['existencia'];
							if($cantidad > 0 && $cantidad<=$vector[$i]['exisobodega']){
								$defecto=$cantidad;
							}elseif($cantidad > 0 && $vector[$i]['exisobodega']>0){
								$defecto=$vector[$i]['exisobodega'];
							}else{
								$defecto=0;
							}
						}
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">$defecto</td>";
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"15%\">&nbsp;</td>";
						$Salida.="</tr>";
				  }
  	      $Salida.="</table>";
				}
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	function ProductosParaRepocision($centroUtilidad,$Bodega){
    list($dbconn) = GetDBconn();
    $query="SELECT x.codigo_producto,z.descripcion,x.existencia,x.existencia_minima,x.existencia_maxima,a.existencia as exisobodega,a.existencia_minima as exismin FROM
		existencias_bodegas x JOIN existencias_bodegas a on (a.codigo_producto=x.codigo_producto and a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' and a.bodega='".$_SESSION['BODEGAS']['BodegaId']."'),
		inventarios y,inventarios_productos as z WHERE
		x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x.centro_utilidad='$centroUtilidad' AND x.bodega='$Bodega'
		AND x.estado=1 AND y.empresa_id=x.empresa_id AND y.codigo_producto=x.codigo_producto AND
		z.codigo_producto=x.codigo_producto AND x.existencia <= x.existencia_minima
		ORDER BY z.descripcion";
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
		$result->Close();
		return $vars;
	}



    //---------------------------------------
}

?>
