<?php


class Listado_Pacientes_report
{

	//CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
	function Listado_Pacientes_report($datos=array())
	{
	$this->datos=$datos;
			return true;
	}

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


	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$this->datos['empresa'],
																'subtitulo'=>$this->datos['nombre'],
																'logo'=>'logocliente.png',
																'align'=>'center'));
		return $Membrete;
	}

    /**
    *
    */
    function CrearReporte()
    {
					$arr=$this->ReportePacientesEstacion($this->datos['estacion']);


					/***** generamos el html ********/

					$salida.="<table width='100%' border=1>";
					$salida.="  <TR><font size='3'><b>";
					$salida.="<TD  WIDTH='70'>PIEZA</TD>";
					$salida.="<TD  WIDTH='70'>CAMA</TD>";
					$salida.="<TD  WIDTH='70'>NOMBRE</TD>";
					$salida.="<TD  WIDTH='70'>FECHA INGRESO</TD>";
					$salida.="<TD  WIDTH='70'>PLAN</TD>";
					$salida.="<TD  WIDTH='70'>CUENTA</TD>";
					$salida.="  </b></font></TR>";
					$vector_ingresos=array();//reiniciamos el vector q va a comparar los ingresos.

					for($i=0;$i<sizeof($arr);$i++)
					{
						$conteo_salida=$this->VerificarSalida($arr[$i][ing_dpto]);
						if($conteo_salida==='0')
						{


								if(in_array($arr[$i][ingreso], $vector_ingresos)==FALSE)
								{
										if( $i % 2){ $estilo2='#CCCCCC';}
										else {$estilo2='#DDDDDD';}
										$va=$this->Habitacion($arr[$i][cuenta]);
										$salida.="<TR>";
										if(empty($va[0][pieza])){$pieza="---";}else{$pieza=$va[0][pieza];}
										if(empty($va[0][cama])){$cama="---";}else{$cama=$va[0][cama];}
										$salida.="  <TD  WIDTH='70'><font size='1'>".$pieza."</font></TD>";
										$salida.="  <TD  WIDTH='70'><font size='1'>".$cama."</font></TD>";
										$d=" ";
										$nombre =$arr[$i][primer_nombre].$d.$arr[$i][segundo_nombre].$d.$arr[$i][primer_apellido].$d.$arr[$i][segundo_apellido];
										//$nombre =$arr[$i][primer_nombre].$d.$arr[$i][primer_apellido];

										$salida.="  <TD  WIDTH='260'><font size='1'>".$nombre."</font></TD>";
										$salida.="  <TD WIDTH='100'><font size='1'>".$arr[$i][fec_ing]."</font></TD>";
										$nombre_plan=$this->Plan($arr[$i][plan]);
										$salida.="  <TD WIDTH='155'><font size='1'>".$nombre_plan."</font></TD>";
										$salida.="  <TD WIDTH='75'><font size='1'>".$arr[$i][cuenta]."</font></TD>";
										$salida.="</TR>";
										$vector_ingresos[$i]=$arr[$i][ingreso];
								}

						}
					}
						$salida.="</table>";

						//$HTML_WEB_PAGE .=Close_Tags_Html();


        return $salida;
    }



		function Plan($plan)
		{
				list($dbconn) = GetDBconn();
				$querys = " SELECT plan_descripcion
													FROM 	planes where plan_id=$plan";
				$result = $dbconn->Execute($querys);
				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}

			return $result->fields[0];
		}



		function Habitacion($cuenta)
		{
			list($dbconn) = GetDBconn();
			$querys = " SELECT C.cama, C.pieza
												FROM camas C, movimientos_habitacion MH
												WHERE C.cama =  MH.cama AND
															MH.numerodecuenta =$cuenta
															AND MH.fecha_egreso IS NULL";
			$result = $dbconn->Execute($querys);
			if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
			$var[]=$result->GetRowAssoc($ToUpper = false);
			return $var;
		}



	function VerificarSalida($ingreso_dpto)
	{

		list($dbconn) = GetDBconn();
		$query = "SELECT count(*)
			 		   FROM egresos_departamento a,movimientos_habitacion b,
						 egresos_departamento_cuentas_x_liquidar c
					   WHERE
						 b.ingreso_dpto_id=a.ingreso_dpto_id
						 AND b.ingreso_dpto_id='$ingreso_dpto'
						 AND b.fecha_egreso IS NOT NULL
						 AND a.estado='2'
						 AND c.egreso_dpto_id =a.egreso_dpto_id;";

			$resulta = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			if($resulta->fields[0] >0)
			{
					return '1';
			}
			else
			{
					return '0';
			}

	}





	function ReportePacientesEstacion($estacion)
	{
				$query = "SELECT Z.tipo_id_paciente,
												Z.paciente_id,
												primer_apellido,
												segundo_apellido,
												primer_nombre,
												segundo_nombre,
												Z.cuenta,
												Z.plan,
												Z.ing,
												Z.ing_dpto,
												Z.fec_ing,
												Z.orden_hosp,
												Z.ingreso
									FROM pacientes,
											( SELECT 	IC.ingreso,
																tipo_id_paciente,
																paciente_id,
																IC.plan_id as plan,
																IC.cuenta,
																IC.ing_dpto,
																IC.fec_ing,
																IC.ingreso as ing,
																IC.orden_hospitalizacion_id as orden_hosp
												FROM ingresos,
														(SELECT 	C.plan_id,
																			C.ingreso,
																			I.numerodecuenta as cuenta,
																			I.ingreso_dpto_id as ing_dpto,
																			I.fecha_ingreso as fec_ing,
																			I.orden_hospitalizacion_id
															FROM ingresos_departamento I, cuentas C
															WHERE I.estacion_id = '".$estacion."' AND
																		I.numerodecuenta = C.numerodecuenta AND
																		C.estado = '1'
															)	AS IC
												WHERE ingresos.ingreso=IC.ingreso
											)	AS Z
									WHERE pacientes.paciente_id = Z.paciente_id AND
												pacientes.tipo_id_paciente = Z.tipo_id_paciente
									ORDER BY primer_nombre, segundo_nombre, primer_apellido, segundo_apellido
									";//echo $query;
				list($dbconn) = GetDBconn();
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{//echo "<br>".$query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
					$this->error = "Error al ejecutar la conexion";
					$this->mensajeDeError = "Erroa al intentar obtener los pacientes de la estacion<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
					return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$arr[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
						$i++;
				}

	 return $arr;

}

}
?>

