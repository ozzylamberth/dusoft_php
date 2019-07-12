<?php

/**
 * $Id: funciones_liquidacion_cargos.inc.php,v 1.5 2005/06/07 19:03:54 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Libreriar para control de liquidación de cargos de hab.
 */

   	/*
		* funcion demo.
		*/
		function GetDatosDias_X_Cargos($ingreso,$facturado=false)
	{
				if(empty($facturado))
				{  $filtro=' AND a.transaccion is null';   }

				list($dbconn) = GetDBconn();
				$query = "SELECT a.cargo,a.fecha_ingreso,a.fecha_egreso,a.precio,a.cama,b.pieza,b.ubicacion,
									c.tipo_clase_cama_id, a.movimiento_id, e.departamento
									FROM movimientos_habitacion a,camas b, tipos_camas as c, ingresos_departamento as e
									WHERE a.ingreso=$ingreso $filtro
									AND a.cama=b.cama AND a.tipo_cama_id=c.tipo_cama_id
									AND a.ingreso_dpto_id=e.ingreso_dpto_id
									ORDER BY a.fecha_ingreso,c.tipo_clase_cama_id;";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al traer los cargos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}
				while(!$result->EOF)
				{
								$var[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				$k=0;
				for($i=0; $i<sizeof($var); $i++)
				{
						$arr_dias_hosp[$i][cargo]=$var[$i][cargo];
						$arr_dias_hosp[$i][fecha_ingreso]=$var[$i][fecha_ingreso];
						$arr_dias_hosp[$i][fecha_egreso]=$var[$i][fecha_egreso];
						$arr_dias_hosp[$i][precio]=$var[$i][precio];
						$arr_dias_hosp[$i][cama]=$var[$i][cama];
						$arr_dias_hosp[$i][pieza]=$var[$i][pieza];
						$arr_dias_hosp[$i][ubicacion]=$var[$i][ubicacion];
						$arr_dias_hosp[$i][tipo_clase_cama_id]=$var[$i][tipo_clase_cama_id];
						$arr_dias_hosp[$i][movimiento_id]=$var[$i][movimiento_id];
						$arr_dias_hosp[$i][departamento]=$var[$i][departamento];
					/*$fecha_ingreso=explode(" ",$var[$i][fecha_ingreso]);
					$fecha_ingreso_anterior=explode(" ",$var[$i-1][fecha_ingreso]);
					if($i==0)
					{
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][cargo]=$var[$i][cargo];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][fecha_ingreso]=$var[$i][fecha_ingreso];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][fecha_egreso]=$var[$i][fecha_egreso];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][precio]=$var[$i][precio];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][cama]=$var[$i][cama];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][pieza]=$var[$i][pieza];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][ubicacion]=$var[$i][ubicacion];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][tipo_clase_cama_id]=$var[$i][tipo_clase_cama_id];
					}
					else
					{
							if(strtotime($fecha_ingreso[0])==strtotime($fecha_ingreso_anterior[0]))
							{   $k++;  }
							else
							{   $k=0;  }
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][cargo]=$var[$i][cargo];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][fecha_ingreso]=$var[$i][fecha_ingreso];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][fecha_egreso]=$var[$i][fecha_egreso];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][precio]=$var[$i][precio];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][cama]=$var[$i][cama];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][pieza]=$var[$i][pieza];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][ubicacion]=$var[$i][ubicacion];
							$arr_dias_hosp[$var[$i][tipo_clase_cama_id]][$var[$i][cargo]][$fecha_ingreso[0]][$k][tipo_clase_cama_id]=$var[$i][tipo_clase_cama_id];
					}
					unset($fecha_ingreso);
					unset($fecha_ingreso_anterior);*/

				}
				return $arr_dias_hosp;

	}


	function LiquidarCamas($arreglo,$plan,$cuenta=0,$servicio)
	{
			IncludeLib('funciones_facturacion');
			IncludeLib('tarifario_cargos');

			$movimientos=array();

			$f=0;
			for($i=0; $i<sizeof($arreglo); $i++)
			{
					$fecha_ingreso=$fecha_egreso='';
					$movimientos=array();
					$movimientos=$arreglo[$i][movimiento_id];
					/*$d=$i;
					while($arreglo[$i][cargo_cups]==$arreglo[$d][cargo_cups]
						AND $arreglo[$i][tipo_clase_cama_id]==$arreglo[$d][tipo_clase_cama_id])
					{
							if(empty($fecha_ingreso))
							{   $fecha_ingreso = $arreglo[$d][fecha_ingreso];  }

							$fecha_egreso = $arreglo[$d][fecha_egreso];
							$movimientos[]=$arreglo[$d][movimiento_id];
							$d++;
					}*/

					$tipo_liq = BuscarLiqHabitacion($arreglo[$i][tipo_clase_cama_id],$plan);
					echo "<br>tipo liq=>".$tipo_liq[tipo_liq_cama_id];
					switch($tipo_liq[tipo_liq_cama_id])
					{
        			case 1:		//UCI rangos tiempos
												break;

        			case 2:	//OBSERVACION rangos tiempos
											echo "<br>horas=>".	$horas = HorasHospitalizacion($arreglo[$i][fecha_egreso],$arreglo[$i][fecha_ingreso]);

											//se cobra el cargo normal
											if($horas <= $tipo_liq[rango_minimo])
											{
													$cargo = $arreglo[$i][cargo];
													$tarifario = $arreglo[$i][tarifario_id];
													$precio = $arreglo[$i][precio];
													$cups = $arreglo[$i][cargo_cups];
											}
											elseif($horas <= $tipo_liq[rango_limite1])
											{
											}
											elseif($horas <= $tipo_liq[rango_limite2])
											{
											}
											elseif($horas <= $tipo_liq[rango_limite3])
											{
											}
											elseif($horas <= $tipo_liq[rango_limite4])
											{
											}

											echo "<br>car=>".$cargo;
											echo "<br>tarifa=>".$tarifario;
											$servicio = BuscarServicio($arreglo[$i][departamento]);
											$cargo_liquidado[$f] = LiquidarObservacionRangosTiempo($cuenta,$plan,$tarifario,$cargo,$servicio,$precio,$cups);
											$cargo_liquidado[$f][movimientos] = $movimientos;
											$cargo_liquidado[$f][servicio] = $servicio;
											$cargo_liquidado[$f][cargo_cups] = $cups;
											$cargo_liquidado[$f][departamento] = $arreglo[$i][departamento];
											break;

        			case 3:	//HOSPITALIZACION egreso no ingreso
											break;

        			case 4:	//HOSPITALIZACION ingreso no egreso
											break;

        			case 5:	//HOSPITALIZACION hora de corte
											break;
					}
					$f++;
					//$i=$d;
			}

			return $cargo_liquidado;
	}


	function LiquidarObservacionRangosTiempo($cuenta,$plan,$tarifario,$cargo,$servicio,$precio,$cups)
	{
			//esta funcion esta en el include tarifario_cargos
			$valor = LiquidarCargoCuenta($cuenta ,$tarifario ,$cargo ,$cantidad=1 ,0 ,0 ,true ,true, $precio, $plan, $servicio, $semanas_cotizacion='', '', '');

			return $valor;
	}



	function GetValorTotalEstancia($ingreso)
	{
		return true;
	}



	function GetCargosEstancia($ingreso)
	{
			return true;
	}


	function CargarEstancia()
	{
			return true;
	}


	function ModificarCargosEstancia()
	{
			return true;
	}




//--------------------------------------------------------
?>
