<?php

/**
 * $Id: SolicDespachosTotalizadas_html.report.php,v 1.4 2007/06/28 21:41:41 luis Exp $
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

	    $style= "style=\"font-size:12px; font-weight:bold;\"";
			$style1= "style=\"font-size:12px\"";
			
			$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$Salida.="	<tr>";
			$Salida.="		<td><img src=\"../../../../images/logocliente.png\" border=\"0\"></td>";
			$Salida.="	</tr>";
			$Salida.="</table><br>";
			
			$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$Salida.="<tr>";
			$Salida.="  <td $style align=\"center\" width=\"100%\">TOTAL DE MEDICAMENTOS E INSUMOS SOLICITADOS</td>";
			$Salida.="</tr>";
			$Salida.="<tr>";
			$Salida.="  <td $style align=\"center\" width=\"100%\">ESTACION :&nbsp&nbsp&nbsp; ".$this->datos['estacion']."</td>";
			$Salida.="</tr>";
			$Salida.="<tr>";
			$Salida.="  <td $style align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['Bodegas']['bodega']." - ".$_SESSION['Bodegas']['bodega_desc']."</td>";
			$Salida.="</tr>";
			$Salida.="</table><BR>";
			$vector=$this->DatosTotalesSolicitudesDpto($this->datos['estacion_id'],$this->datos['sw'],$this->datos['sw_imp']);        
			if($vector[2]){
				$Salida.="<BR><table  align=\"center\" border=\"1\"  width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="<td width=\"15%\" align=\"center\" $style>SOLICITUDES<br>MEDICAMENTOS</td>";
				$Salida.="<td $style1 align=\"left\">";
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
				$Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="<td align=\"center\" colspan=\"2\" class=\"label\" $style>MEDICAMENTO</td>";
				$Salida.="<td align=\"center\" class=\"label\" $style>CANTIDAD</td>";
				$Salida.="</tr>";
				for($i=0;$i<sizeof($vector[0]);$i++){
					$Salida.="<tr>";
					$Salida.="  <td $style1 align=\"left\" width=\"15%\">".$vector[0][$i]['codigo_producto']."</td>";
					$Salida.="  <td $style1 align=\"left\" width=\"60%\">".$vector[0][$i]['desmed']."</td>";
					$Salida.="  <td $style1 align=\"left\" width=\"25%\">".FormatoValor($vector[0][$i]['cant_solicitada'])." ".$vector[0][$i]['abreviatura']."</td>";
					$Salida.="</tr>";
					if($vector[1][$i]['ubicacion']){
					$Salida.="<tr>";
					$Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$vector[0][$i]['ubicacion']."</td>";
					$Salida.="</tr>";
					}
				}
				$Salida.="</table><BR>";
			}
			if($vector[3]){
				$Salida.="<BR><table  align=\"center\" border=\"1\" width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="<td width=\"15%\" align=\"center\" class=\"label\" $style>SOLICITUDES<br>INSUMOS</td>";
				$Salida.="<td $style1 align=\"left\">";
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
				$Salida.="<BR><table  align=\"center\" border=\"1\"  class=\"modulo_table_list\" width=\"100%\">";
				$Salida.="<tr>";
				$Salida.="<td align=\"center\" colspan=\"2\" class=\"label\" $style>INSUMO</td>";
				$Salida.="<td align=\"center\" class=\"label\" $style>CANTIDAD</td>";
				$Salida.="</tr>";
				for($i=0;$i<sizeof($vector[1]);$i++){
					$Salida.="<tr>";
					$Salida.="  <td $style1 align=\"left\" width=\"15%\">".$vector[1][$i]['codigo_producto']."</td>";
					$Salida.="  <td $style1 align=\"left\" width=\"60%\">".$vector[1][$i]['desmed']."</td>";
					$Salida.="  <td $style1 align=\"left\" width=\"25%\">".FormatoValor($vector[1][$i]['cant_solicitada'])." ".$vector[1][$i]['abreviatura']."</td>";
					$Salida.="</tr>";
					if($vector[1][$i]['ubicacion']){
					$Salida.="<tr>";
					$Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$vector[1][$i]['ubicacion']."</td>";
					$Salida.="</tr>";
					}
				}
				$Salida.="</table>";
			}
		$usuario=$this->GetInfoUsuario();
		
		$Salida.= "<br><div align=\"right\"><label style=\"font-size:10px\"> Imprimió: ".$usuario[usuario_id]." - ".$usuario[nombre]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Impresión:&nbsp;&nbsp;".date("Y-m-d , g:i a")."</label></div>";
		
		$solicitudes=explode(".-.",$this->datos['solicitudes']);
		
		$this->UpdateImpreso($solicitudes);
		
		echo $Salida;
	}
	
	function UpdateImpreso($solicitudes)
	{
		list($dbconn) = GetDBconn();
		
		
		$query1="	SELECT max(sw_impreso)
							FROM hc_solicitudes_medicamentos";
			
		$result = $dbconn->Execute($query1);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		$num=$result->fields[0];
		
		foreach($solicitudes as $soli)
		{
			$query1="	SELECT sw_impreso
								FROM hc_solicitudes_medicamentos
								WHERE solicitud_id=$soli;";
				
			$result1 = $dbconn->Execute($query1);
			
			if($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$sw_impreso=$result1->fields[0];
			
			if($sw_impreso==0)
			{
				$query="	UPDATE hc_solicitudes_medicamentos
									SET sw_impreso=".($num+1).",
									usuario_imp=".UserGetUID()."
									WHERE solicitud_id=$soli;";
				
				$result = $dbconn->Execute($query);
				
				if($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					echo $dbconn->ErrorMsg();
					return false;
				}
			}
			else break;
		}
		return true;
	}
	
	
	function GetInfoUsuario($usuario_id)
	{
		
		if(!$usuario_id)
			$usuario_id=UserGetUID();
			
		list($dbconn) = GetDBconn();
		
		$query="SELECT *
						FROM system_usuarios
						WHERE usuario_id=$usuario_id";
		
		$result = $dbconn->Execute($query);
		
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$datos=$result->RecordCount();
			if($datos)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	function DatosTotalesSolicitudesDpto($estacion_id,$sw,$sw_imp){

		list($dbconn) = GetDBconn();
    
		if(!$sw_imp)
			$imp="AND a.sw_impreso=0";
		else
			$imp="AND a.sw_impreso=".$sw_imp;
		
		$query = "
						(	SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
							FROM
							(
								SELECT det.medicamento_id as codigo_producto,sum(det.cant_solicitada) as cant_solicitada
								FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d det,estaciones_enfermeria b
								WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
								a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.sw_estado='$sw' AND
								a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id 
								AND b.estacion_id='".$estacion_id."'
								$imp
								GROUP BY det.medicamento_id
							) as a,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
							LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
							,unidades u
							WHERE a.codigo_producto=invp.codigo_producto 
							AND invp.unidad_id=u.unidad_id 
							
							ORDER BY invp.descripcion_abreviada
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
		$query ="(
							SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		          FROM
		          (
								SELECT det.codigo_producto,sum(det.cantidad) as cant_solicitada
              	FROM hc_solicitudes_medicamentos a,hc_solicitudes_insumos_d det,estaciones_enfermeria b
              	WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
								a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.sw_estado='$sw' AND
								a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.estacion_id='".$estacion_id."'
              	$imp
								GROUP BY det.codigo_producto
							) as a,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
							LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
							,unidades u
						 WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id 
						 ORDER BY invp.descripcion_abreviada)
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
    WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
    a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.sw_estado='$sw' AND
    a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.estacion_id='".$estacion_id."'
    AND det.medicamento_id=invp.codigo_producto
		$imp";
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
    WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
    a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.sw_estado='$sw' AND
    a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.estacion_id='".$estacion_id."'              
    AND det.codigo_producto=invp.codigo_producto
		$imp";
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
$VISTA = "HTML";
$_ROOT = "../../../../";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);


$obj=new SolicDespachosTotalizadas_html_report($_REQUEST);
$obj->CrearReporte();

?>
