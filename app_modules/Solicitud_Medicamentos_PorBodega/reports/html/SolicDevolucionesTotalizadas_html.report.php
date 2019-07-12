<?php

/**
 * $Id: SolicDevolucionesTotalizadas_html.report.php,v 1.3 2007/06/26 21:09:11 luis Exp $
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

	      $style= "style=\"font-size:12px; font-weight:bold;\"";
				$style1= "style=\"font-size:12px\"";
				
				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="	<tr>";
				$Salida.="		<td><img src=\"../../../../images/logocliente.png\" border=\"0\"></td>";
				$Salida.="	</tr>";
				$Salida.="</table><br>";
				
				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
        $Salida.="<tr>";
				$Salida.="  <td $style align=\"center\" width=\"100%\">TOTAL DE MEDICAMENTOS E INSUMOS DEVUELTOS</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td $style align=\"center\" width=\"100%\">ESTACION :&nbsp&nbsp&nbsp; ".$this->datos['estacion']."</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td $style align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['Bodegas']['bodega']." - ".$_SESSION['Bodegas']['bodega_desc']."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
        $vector=$this->DatosTotalesDevolucionesDpto($this->datos['estacion_id']);
				if($vector){
          $Salida.="<BR><table  align=\"center\" border=\"1\"  width=\"100%\">";
          $Salida.="<tr>";
          $Salida.="<td align=\"center\" $style>CODIGO</td>";
          $Salida.="<td align=\"center\" $style>DESCRIPCION PRODUCTO</td>";
          $Salida.="<td align=\"center\" $style>CANTIDAD</td>";
          $Salida.="</tr>";
          for($i=0;$i<sizeof($vector);$i++){
            $Salida.="<tr>";
            $Salida.="  <td $style1 align=\"left\" width=\"15%\">".$vector[$i]['codigo_producto']."</td>";
            $Salida.="  <td $style1 align=\"left\" width=\"60%\">".$vector[$i]['desmed']."</td>";
            $Salida.="  <td $style1 align=\"left\" width=\"25%\">".$vector[$i]['cantidad']." ".$vector[$i]['abreviatura']."</td>";
            $Salida.="</tr>";
            if($vector[$i]['ubicacion']){
            $Salida.="<tr>";
            $Salida.="  <td colspan=\"3\" $style1 align=\"left\">".$vector[$i]['ubicacion']."</td>";
            $Salida.="</tr>";
            }
          }
          $Salida.="</table><BR>";
        }
  	   $usuario=$this->GetInfoUsuario();
		
		$Salida.= "<br><div align=\"right\"><label style=\"font-size:10px\"> Imprimió: ".$usuario[usuario_id]." - ".$usuario[nombre]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Impresión:&nbsp;&nbsp;".date("Y-m-d , g:i a")."</label></div>";
		
		//$solicitudes=explode(".-.",$this->datos['solicitudes']);
		
		//$this->UpdateImpreso($solicitudes);
		
		echo $Salida;
	}
	
	
	function UpdateImpreso($solicitudes)
	{
		list($dbconn) = GetDBconn();
		
		
		$query1="	SELECT max(sw_impreso)
							FROM inv_solicitudes_devolucion";
			
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
								FROM inv_solicitudes_devolucion
								WHERE documento=$soli;";
				
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
				$query="	UPDATE inv_solicitudes_devolucion
									SET sw_impreso=".($num+1)."
									WHERE documento=$soli;";
				
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

	function DatosTotalesDevolucionesDpto($estacion_id)
	{
		list($dbconn) = GetDBconn();
		$query ="(SELECT a.codigo_producto,a.cantidad,invp.descripcion as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		          FROM
		          (SELECT det.codigo_producto,sum(det.cantidad) as cantidad
              FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['Bodegas']['bodega']."' AND a.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND
							a.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND a.estado='0' AND
							a.documento=det.documento AND det.estado='0' AND a.estacion_id=b.estacion_id AND b.estacion_id='".$estacion_id."'
              GROUP BY det.codigo_producto) as a,
							inventarios_productos invp
							LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['Bodegas']['empresa_id']."' AND exis.centro_utilidad='".$_SESSION['Bodegas']['centro_id']."' AND exis.bodega='".$_SESSION['Bodegas']['bodega']."')
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
$VISTA = "HTML";
$_ROOT = "../../../../";
include	 $_ROOT."includes/enviroment.inc.php";
$filename="themes/".$VISTA."/".GetTheme()."/module_theme.php";
IncludeFile($filename);


$obj=new SolicDevolucionesTotalizadas_html_report($_REQUEST);
$obj->CrearReporte();

?>
