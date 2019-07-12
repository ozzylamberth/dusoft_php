<?php

/**
 * $Id: hospitalizados_laboratorio.report.php,v 1.2 2005/06/07 13:32:29 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de facturapaciente para impresion en PDF
 */

class hospitalizados_laboratorio_report
//extends pos_reports_class
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
    function hospitalizados_laboratorio_report($datos=array())
    {
		$this->datos=$datos;
		return true;
    }


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
		$arr = $this->Buscar_PacientesHospitalizadosLab();
		$salida = $this->Encabezado();
		if(!empty($arr))
		{
			$salida.= "<table  width=\"100%\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				if($arr[$i][servicio] != $arr[$i-1][servicio])
				{
						$salida .= "<tr >";
						$salida .= "<td colspan = \"3\">&nbsp;</td>";
						$salida .= "</tr>";
						$salida.="<tr align=\"center\" class=\"modulo_table_list_title\">";
						$salida .= "<td colspan = \"3\">SERVICIO: ".$arr[$i][servicio_descripcion]."</td>";
						$salida .= "</tr>";
						if($arr[$i][departamento] != $arr[$i-1][departamento])
						{
								$salida .= "<tr align=\"left\" class=\"modulo_table_title\">";
								$salida .= "<td colspan = \"3\">DEPARTAMENTO: ".$arr[$i][dpto]."</td>";
								$salida .= "</tr>";
								$salida.="<tr align=\"center\" class=\"hc_table_submodulo_list_title\">";
								$salida .= "<td width=\"15%\">IDENTIFICACION</td>";
								$salida .= "<td width=\"45%\">NOMBRE DEL PACIENTE</td>";
								$salida .= "<td width=\"40%\">OPCION</td>";
								$salida .= "</tr>";
						}
					}
					else
					{
						if($arr[$i][departamento] != $arr[$i-1][departamento])
						{
							$salida .= "<tr align=\"left\" class=\"modulo_table_title\">";
							$salida .= "<td colspan = \"3\">DEPARTAMENTO: ".$arr[$i][dpto]."</td>";
							$salida .= "</tr>";
							$salida.="<tr align=\"center\" class=\"hc_table_submodulo_list_title\">";
							$salida .= "<td width=\"15%\">IDENTIFICACION</td>";
							$salida .= "<td width=\"45%\">NOMBRE DEL PACIENTE</td>";
							$salida .= "<td width=\"40%\">OPCION</td>";
							$salida .= "</tr>";
						}
					}
				//Edad
				$edad_paciente = CalcularEdad($arr[$i][fecha_nacimiento],date("Y-m-d"));
				$salida.="<tr class='$estilo' align='center'>";
				$salida.="<td width=\"15%\"><font face=\"Arial\" size=\"2\">".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</font></td>";
				$salida.="<td width=\"45%\"><font face=\"Arial\" size=\"2\">".$arr[$i][nombre]."</font></td>";
				$salida.="<td width=\"40%\"><font face=\"Arial\" size=\"2\">".$arr[$i][examen]."</font></td></tr>";
			}
			$salida.="</tr>";
		}
		$salida.="</table>";
		return $salida;
	}



// for ($x=0; $x<=sizeof($arr[$i][nombre]); $x++)
// {
//
// 					if ($arr[$i][nombre] == $arr[$i-1][nombre])
// 					{
//
// 						$salida .= "<td width=\"40%\"><table>";
//
//
// 					//$salida.="<td width=\"45%\"><font face=\"Arial\" size=\"2\">".$arr[$i][nombre]."</font></td>";
//
//
// 						//$salida.="</td>";
// 						$salida.="<tr>";
//
// 						$salida .="</table></td>";
// 					}
// 					else
// 					{
// 						$salida.="<tr>";
// 						$salida.="<td width=\"40%\"><font face=\"Arial\" size=\"2\">".$arr[$i][examen]."</font></td></tr>";
// 					}


		function Encabezado()
		{
				$salida1 .= "<br><table  width=\"100%\" border=\"1\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
				$salida1 .= " <tr class=\"modulo_table_title\">";
				$salida1 .= " <td align=\"center\">EMPRESA</td>";
				$salida1 .= " <td align=\"center\">CENTRO UTILIDAD</td>";
				$salida1 .= " <td align=\"center\">DEPARTAMENTO</td>";
				$salida1 .= " </tr>";
				$salida1 .= " <tr align=\"center\">";
				$salida1 .= " <td class=\"modulo_list_claro\" >".$this->datos[empresa]."</td>";
				$salida1 .= " <td class=\"modulo_list_claro\">".$this->datos[centro]."</td>";
				$salida1 .= " <td class=\"modulo_list_claro\" >".$this->datos[dpto]."</td>";
				$salida1 .= " </tr>";
				$salida1 .= " </table><br>";
				return $salida1;
		}

    /**
    *
    */
    function Buscar_PacientesHospitalizadosLab()
    {
		list($dbconn)=GetDBconn();
		if ($this->datos[datalab]==1)
		{
			$filtro="AND (c.sw_estado='1')";
		}
		else
		{
			$filtro="AND (c.sw_estado='1' OR c.sw_estado='3')";
		}

		$query = "SELECT DISTINCT
						btrim(b.primer_nombre||' '||b.segundo_nombre||' ' ||
						b.primer_apellido||' '||b.segundo_apellido,'') as nombre,
						b.tipo_id_paciente,b.paciente_id, b.fecha_nacimiento, a.plan_id,
						g.sw_internacion,	h.sw_prioridad, h.servicio, h.descripcion as servicio_descripcion,
						f.departamento, g.descripcion as dpto, x.cargo, x.descripcion as examen
				FROM
						pacientes as b,os_ordenes_servicios a, os_maestro c, os_internas d left join cups as x on (d.cargo = x.cargo),
						hc_os_solicitudes e, hc_evoluciones f, departamentos g, servicios h
				WHERE
					c.numero_orden_id=d.numero_orden_id
					AND a.orden_servicio_id=c.orden_servicio_id
					AND d.departamento='".$this->datos[departamento]."'
					$filtro
					AND DATE(c.fecha_activacion) <= NOW()
					AND a.tipo_id_paciente=b.tipo_id_paciente
					AND a.paciente_id=b.paciente_id
					AND c.hc_os_solicitud_id = e.hc_os_solicitud_id
					AND e.evolucion_id = f.evolucion_id
					AND f.departamento = g.departamento
					AND g.servicio = h.servicio
					AND g.sw_internacion = '1'
				ORDER BY h.sw_prioridad, h.descripcion, f.departamento";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
			return $var;
		}
}
?>
