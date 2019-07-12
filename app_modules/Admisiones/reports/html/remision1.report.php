<?php

/**
 * $Id: remision1.report.php,v 1.1 2009/01/08 20:50:28 claudia Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class remision_report
{ 
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function remision_report($datos=array())
	{
			$this->datos=$datos;
			return true;
	}

	
	function GetMembrete()
	{
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
												'subtitulo'=>'',
												'logo'=>'logocliente.png','align'=>'left'));
			return $Membrete;
	}			
    //FUNCION CrearReporte()
	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
	function CrearReporte()
	{
			$arr = $this->DatosRemision($this->datos[ingreso]);
			$dat = $this->DatosPaciente($this->datos[ingreso]);
			$Salida .= "				          <p align=\"center\" class=\"titulo2\">REMISION PACIENTE</p>";
			$Salida .= "       <table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
			$Salida .= "				       <tr><td align=\"LEFT\" class=\"normal_10\" width=\"15%\">IDENTIFICACION: </td><td class=\"normal_10\" width=\"25%\">".$dat['tipo_id_paciente']." ".$dat['paciente_id']."</td>";
			$Salida .= "				       <td align=\"LEFT\" class=\"normal_10\"width=\"10%\">PACIENTE: </td><td class=\"normal_10\" width=\"25%\">".$dat['nombre']."</td>";
			$EdadArr=CalcularEdad($dat['fecha_nacimiento'],'');
			$Edad=$EdadArr['edad_aprox'];
			$Salida .= "				       <td align=\"LEFT\" class=\"normal_10\" width=\"5%\">EDAD: </td><td class=\"normal_10\" width=\"6%\">$Edad</td></tr>";
			$Salida .= "          <tr>";
			$Salida .= "          <td class=\"normal_10\">INSTITUCION QUE REMITE: </td><td class=\"normal_10\" colspan=\"5\">".$this->datos[empresa]."</td>";
			$Salida .= "           </tr>";
			if(!empty($arr[0][traslado_ambulancia]))
			{  $msg='<B> - SE SOLICITA AMBULANCIA</B>';  }
			$Salida .= "          <tr>";
			$Salida .= "          <td width=\"23%\" class=\"normal_10\">TIPO REMISION: </td><td class=\"normal_10\" width=\"27%\">".$arr[0][descripcion]." $msg</td>";
			$Salida .= "          <td width=\"10%\" class=\"normal_10\">NIVEL REMISION: </td><td class=\"normal_10\" width=\"6%\" align=\"left\" colspan=\"3\">&nbsp;".$arr[0][nivel_centro_remision]." &nbsp;&nbsp;".$this->NombreColorTriage($arr[0][nivel_centro_remision])."</td>";
			$Salida .= "           </tr>";
			$Salida .= "          <tr>";
			$Salida .= "          <td class=\"normal_10\">DESCRIPCION MOTIVO: </td><td class=\"normal_10\" colspan=\"5\">".$arr[0][descripcion_otro_motivo]."</td>";
			$Salida .= "           </tr>";
			$Salida .= "          <tr>";
			$Salida .= "          <td class=\"normal_10\">OBSERVACIONES: </td><td class=\"normal_10\" colspan=\"5\">".$arr[0][observaciones]."</td>";
			$Salida .= "           </tr>";
			$Salida .= "          <tr>";
			$Salida .= "          <td class=\"normal_10\">MOTIVOS REMISION: </td>";
			$Salida .= "          <td class=\"normal_10\" colspan=\"5\">";
			$Salida .= "       <table border=\"0\" width=\"100%\"align=\"left\" class=\"normal_10\">";
			for($i=0; $i<sizeof($arr); $i++)
			{
					if(!empty($arr[$i][motivo]))
					{  $Salida .= "          <tr class=\"normal_10\"><td>".$arr[$i][motivo]."</td></tr>";  }
			}
			$Salida .= "		   	 </table>";
			$Salida .= "          </td>";
			$Salida .= "           </tr>";
			$Salida .= "          <tr>";
			$Salida .= "          <td class=\"normal_10\">CENTROS REMISION: </td>";
			$Salida .= "          <td class=\"normal_10\" colspan=\"5\">";
			for($i=0; $i<sizeof($arr); $i++)
			{
					if(!empty($arr[$i][centro]))
					{
							$centros[$arr[$i][centro]]=array('centro'=>$arr[$i][centro],'nivel'=>$arr[$i][nivel],'direccion'=>$arr[$i][direccion],'telefono'=>$arr[$i][telefono]);
					}
			}
			if(!empty($centros))
			{
					$Salida .= "       <table border=\"1\" width=\"100%\"align=\"left\">";
					$Salida .= "           <tr class=\"normal_10\" align=\"center\">";
					$Salida .= "          <td width=\"90%\">CENTRO</td>";
					$Salida .= "          <td width=\"10%\">NIVEL</td>";
					$Salida .= "           </tr>";
					foreach($centros as $k => $v)
					{
									$Salida .= "          <tr class=\"normal_10\">";
									$Salida .= "          <td>".$v[centro]." ".$v[direccion]." ".$v[telefono]."</td>";
									$Salida .= "          <td align=\"center\">".$v[nivel]."</td>";
									$Salida .= "          </tr>";
					}
					$Salida .= "		   	 </table>";
			}
			$Salida .= "          </td>";
			$Salida .= "           </tr>";
			$Salida .= "          <tr>";
			$Salida .= "          <td class=\"normal_10\">OBSERVACION REMISION: </td>";
			$Salida .= "          <td class=\"normal_10\" colspan=\"5\">".$arr[0][observacion_remision]."</td>";
			$Salida .= "           </tr>";
			$Salida .= "		   	 </table><br>";
			$Salida .= "\n";
			return $Salida;
	}
   
	/**
	*
	*/
	function DatosRemision($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.traslado_ambulancia, a.observaciones, a.descripcion_otro_motivo,
								a.nivel_centro_remision, a.tipo_remision, b.descripcion, a.sw_remision,
								d.descripcion as motivo, e.centro_remision, f.descripcion as centro,
								f.direccion, f.telefono, f.nivel, a.ingreso, a.evolucion_id, a.observacion_remision
								FROM hc_conducta_remision as a
								left join hc_conducta_remision_motivos as c  on (a.ingreso=c.ingreso and a.evolucion_id=c.evolucion_id)
								left join hc_motivos_remision as d on(c.motivo_remision_id=d.motivo_remision_id)
								left join hc_conducta_remision_centros as e on(a.ingreso=e.ingreso and a.evolucion_id=e.evolucion_id)
								left join centros_remision as f on(e.centro_remision=f.centro_remision),
								hc_tipo_remision as b
								WHERE a.ingreso=$ingreso and a.tipo_remision=b.tipo_remision_id";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			if(!$result->EOF)
			{
					while (!$result->EOF) {
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
					}
			}

			$result->Close();
			return $var;
	}


	/***
	*
	*/
	function DatosPaciente($ingreso)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.tipo_id_paciente,a.paciente_id, b.fecha_nacimiento,
								b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre
								FROM ingresos as a, pacientes as b
								WHERE a.ingreso=$ingreso
								and a.tipo_id_paciente=b.tipo_id_paciente
								and a.paciente_id=b.paciente_id";
			$result=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Guardar en la Tabal autorizaiones";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}

			$var=$result->GetRowAssoc($ToUpper = false);
			$result->Close();
			return $var;
	}

	/**
	*
	*/
	function NombreColorTriage($nivel)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.color FROM niveles_triages as a WHERE a.nivel_triage_id=$nivel";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			$var=$result->fields[0];
			$result->Close();
			return $var;
	}
}

?>
