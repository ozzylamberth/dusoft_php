<?php

/**
 * $Id: SolicitudesDevolucionesDpto_html.report.php,v 1.1.1.1 2009/09/11 20:36:49 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class SolicitudesDevolucionesDpto_html_report
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
    function SolicitudesDevolucionesDpto_html_report($datos=array())
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
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">SOLICITUDES DE DEVOLUCION DE MEDICAMENTOS E INSUMOS</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">DEPARTAMENTO :&nbsp&nbsp&nbsp; ".$this->datos['descripcionDpto']."</td>";
				$Salida.="</tr>";
        $Salida.="<tr>";
				$Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"100%\">BODEGA :&nbsp&nbsp&nbsp; ".$_SESSION['BODEGAS']['BodegaId']." - ".$_SESSION['BODEGAS']['NombreBodega']."</td>";
				$Salida.="</tr>";
				$Salida.="</table><BR>";
        $marca=0;
        $vector=$this->DatosDevolucionesDepartamento($this->datos['departamento']);
				if($vector){
          foreach($vector as $paciente=>$vector){
            $pacienteAnt=-1;
            foreach($vector as $devolucionId=>$vector1){
              $devolucionIdAnt=-1;
              foreach($vector1 as $consecutivoId=>$datos){
                if($paciente!=$pacienteAnt){
                  $pacienteAnt=$paciente;
                  if($marca==1){
                    $Salida.="<BR><BR>";
                  }
                  $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
                  $Salida.="<tr>";
                  $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">PACIENTE</td>";
                  $Salida.="  <td class=\"normal_10\"  align=\"left\" colspan=\"3\">$paciente ".$datos['nombrepac']."</td>";
                  $Salida.="</tr>";
                  $Salida.="<tr>";
                  $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">PLAN</td>";
                  $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"30%\">".$datos['plan_descripcion']."</td>";
                  $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">PIEZA Y CAMA</td>";
                  $Salida.="  <td class=\"normal_10\"  align=\"left\">".$datos['pieza']." ".$datos['cama']."</td>";
                  $Salida.="</tr>";
                  $Salida.="<tr>";
                  $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">TIPO AFILIADO</td>";
                  $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"30%\">".$datos['tipo_afiliado_id']."</td>";
                  $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">RANGO</td>";
                  $Salida.="  <td class=\"normal_10\"  align=\"left\">".$datos['rango']."</td>";
                  $Salida.="</tr>";
                  $Salida.="</table>";
                  $marca=1;
                  if($devolucionId!=$devolucionIdAnt){
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">No. DEVOLUCION</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"30%\">$devolucionId</td>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">FECHA</td>";
                    $Salida.="  <td width=\"20%\"><label class=\"label\">FECHA</td>";
                    (list($fecha,$HoraTot)=explode(' ',$datos['fecha_registro']));
                    (list($ano,$mes,$dia)=explode('-',$fecha));
                    (list($hora,$min)=explode(':',$HoraTot));                  
                    $Salida.="  <td class=\"normal_10\"  align=\"left\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";
                    $Salida.="</tr>";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">ESTACION</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"30%\">".$datos['nomestacion']."</td>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">USUARIO QUE DEVUELVE</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\">".$datos['usuario_id'].' - '.$datos['usuarioestacion']."</td>";
                    $Salida.="</tr>";
                    $Salida.="</table>";
                    $devolucionIdAnt=$devolucionId;
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"15%\">CODIGO</td>";
                    $Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"60%\">MEDICAMENTO</td>";
                    $Salida.="  <td class=\"normal_10N\"  align=\"center\" width=\"25%\">CANTIDAD</td>";
                    $Salida.="</tr>";
                    $Salida.="</table>";
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"25%\">".$datos['cantidad']." ".$datos['abreviatura']."</td>";
                    $Salida.="</tr>";
                    if($datos['ubicacion']){
                    $Salida.="<tr>";
                    $Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$datos['ubicacion']."</td>";
                    $Salida.="</tr>";
                    }
                    $Salida.="</table>";
                  }else{
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"25%\">".$datos['cantidad']." ".$datos['abreviatura']."</td>";
                    $Salida.="</tr>";
                    if($datos['ubicacion']){
                    $Salida.="<tr>";
                    $Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$datos['ubicacion']."</td>";
                    $Salida.="</tr>";
                    }
                    $Salida.="</table>";
                  }
                }else{
                  if($devolucionId!=$devolucionIdAnt){
                    $Salida.="<BR><table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">No. DEVOLUCION</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"30%\">$devolucionId</td>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">FECHA</td>";
                    (list($fecha,$HoraTot)=explode(' ',$datos['fecha_registro']));
                    (list($ano,$mes,$dia)=explode('-',$fecha));
                    (list($hora,$min)=explode(':',$HoraTot));                  
                    $Salida.="  <td class=\"normal_10\"  align=\"left\">".strftime('%b %d de %Y  %H:%M',mktime($hora,$min,0,$mes,$dia,$ano))."</td>";                    
                    $Salida.="</tr>";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">ESTACION</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"30%\">".$datos['nomestacion']."</td>";
                    $Salida.="  <td class=\"normal_10N\" align=\"left\" width=\"15%\">USUARIO QUE DEVUELVE</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\">".$datos['usuario_id'].' - '.$datos['usuarioestacion']."</td>";
                    $Salida.="</tr>";
                    $Salida.="</table>";
                    $devolucionIdAnt=$devolucionId;
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"15%\">CODIGO</td>";
                    $Salida.="  <td class=\"normal_10N\" align=\"center\" width=\"60%\">MEDICAMENTO</td>";
                    $Salida.="  <td class=\"normal_10N\"  align=\"center\" width=\"25%\">CANTIDAD</td>";
                    $Salida.="</tr>";
                    $Salida.="</table>";
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"25%\">".$datos['cantidad']." ".$datos['abreviatura']."</td>";
                    $Salida.="</tr>";
                    if($datos['ubicacion']){
                    $Salida.="<tr>";
                    $Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$datos['ubicacion']."</td>";
                    $Salida.="</tr>";
                    }
                    $Salida.="</table>";
                  }else{
                    $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"15%\">".$datos['codigo_producto']."</td>";
                    $Salida.="  <td class=\"normal_10\" align=\"left\" width=\"60%\">".$datos['desmed']."</td>";
                    $Salida.="  <td class=\"normal_10\"  align=\"left\" width=\"25%\">".$datos['cantidad']." ".$datos['abreviatura']."</td>";
                    $Salida.="</tr>";
                    if($datos['ubicacion']){
                    $Salida.="<tr>";
                    $Salida.="  <td colspan=\"3\" class=\"Normal_10\" align=\"left\">".$datos['ubicacion']."</td>";
                    $Salida.="</tr>";
                    }
                    $Salida.="</table>";
                  }
                }
              }
            }
          }
				}
  	    return $Salida;
//*****************************************fin de termino
 }

    //AQUI TODOS LOS METODOS QUE USTED QUIERA

	function DatosDevolucionesDepartamento($departamento){

		list($dbconn) = GetDBconn();
    $query = "(SELECT i.tipo_id_paciente||' '||i.paciente_id,a.documento,det.consecutivo,a.estacion_id,a.fecha_registro,a.ingreso,d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
		e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,
		l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
		j.cama,j.pieza,b.descripcion as nomestacion,
		det.codigo_producto,invp.descripcion as desmed,det.cantidad,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
		FROM inv_solicitudes_devolucion a,estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e
    LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta AND f.fecha_egreso is NULL)
		LEFT JOIN camas j ON(f.cama=j.cama)
		,planes h,ingresos i,tipos_afiliado k,pacientes l,inv_solicitudes_devolucion_d det
		,inventarios_productos invp
		LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
		LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
		,unidades u
		WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
		a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
		AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
		AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id AND
		a.documento=det.documento AND det.estado='0' AND
		det.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id
		ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha)";

		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) {
					$vars[$result->fields[0]][$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		$result->Close();
		return $vars;

	}

}

?>
