<?php

/**
 * $Id: StickerQX_html.report.php,v 1.2 2007/04/18 22:05:07 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class StickerQX_html_report
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
    function StickerQX_html_report($datos=array())
    {
		    
		    $this->datos=$datos;
        return true;
    }
	
// 	function GetMembrete()
// 	{
// 		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
// 																'subtitulo'=>'',
// 																'logo'=>'logocliente.png',
// 																'align'=>'left'));
// 		return $Membrete;
// 	}

//FUNCION CrearReporte()
//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
function CrearReporte()
{
//*******************************************termino
		$vector = $this->DatosProgramacionQX($this->datos['programacion']);

		$style= "style=\"font-size:12pt; font-weight:bold;\"";

		for ($i=0; $i<10; $i++)
		{
				$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$Salida.="<tr  aling=\"center\">";
				$Salida.="<td $style class=\"normal_10\" colspan=\"3\">&nbsp&nbsp&nbsp;".$vector['tipo_id_paciente']." ".$vector['paciente_id']."</td>";
				$Salida.="</tr>";
				$Salida.="<tr aling=\"center\" font size=\"20\">";
				$Salida.="  <td $style class=\"normal_10\" colspan=\"3\">&nbsp&nbsp&nbsp;".$vector['primer_apellido']." ".$vector['segundo_apellido']."</td>";
				$Salida.="</tr>";
				$Salida.="<tr>";
				$Salida.="  <td $style class=\"normal_10\" colspan=\"3\">&nbsp&nbsp&nbsp;".$vector['primer_nombre']." ".$vector['segundo_nombre']."</td>";
				$Salida.="</tr>";
				$Salida.="</table>";
		}
		return $Salida;
}
//*****************************************fin de termino

    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function DatosProgramacionQX($programacionId){

		list($dbconnect) = GetDBconn();
		$query = "SELECT 
    a.tipo_id_paciente,a.paciente_id,b.primer_nombre,b.segundo_nombre,b.primer_apellido,b.segundo_apellido 
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
    //---------------------------------------
}

?>
