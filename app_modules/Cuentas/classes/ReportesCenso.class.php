<?php
  /******************************************************************************
  * $Id: ReportesCenso.class.php,v 1.8 2011/06/24 16:36:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.8 $ 
  *
  ********************************************************************************/
	class ReportesCenso
	{
		var $offset = 0;
		
		function ReportesCenso(){}
		
		function ListadoHospitalizados()
		{
			$NORMAL=1;//CAMA NORMAL
			$VIRTUAL=2;//CAMA VIRTUAL
			
			list($dbconn) = GetDBconn();
			
			$query = "SELECT 
						i.departamento,
						i.descripcion as desc_departamento,
						h.estacion_id,
						h.descripcion as desc_estacion,
						b.pieza,
						a.cama,
						c.numerodecuenta,
						c.gravamen_valor_cubierto,
						c.valor_cubierto,
						d.ingreso,
						d.fecha_ingreso,
						d.tipo_id_paciente,
						d.paciente_id,
						e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
						f.plan_id,
						f.plan_descripcion,
						f.tipo_tercero_id,
						f.tercero_id,
						g.nombre_tercero,
						j.tipo_afiliado_nombre,
						k.rango
					FROM
						movimientos_habitacion a,
						camas b,
						cuentas c
						LEFT JOIN tipos_afiliado j
						ON
						(
							c.tipo_afiliado_id=j.tipo_afiliado_id
						)
						JOIN planes_rangos k
						ON
						(
							k.tipo_afiliado_id=c.tipo_afiliado_id
							AND k.plan_id=c.plan_id
							AND k.rango=c.rango
						),
						ingresos d,
						pacientes e,
						planes f,
						terceros g,
						estaciones_enfermeria h,
						departamentos i
					WHERE
						a.fecha_egreso IS NULL
						AND b.cama = a.cama
						AND b.sw_virtual IN('$NORMAL','$VIRTUAL')
						AND c.numerodecuenta = a.numerodecuenta
						AND d.ingreso = a.ingreso
						AND e.paciente_id = d.paciente_id
						AND e.tipo_id_paciente = d.tipo_id_paciente
						AND f.plan_id = c.plan_id
						AND f.estado='1'
						AND g.tercero_id = f.tercero_id
						AND g.tipo_id_tercero = f.tipo_tercero_id
						AND h.estacion_id = a.estacion_id
						AND h.departamento =  i.departamento	
						AND i.empresa_id='".SessionGetVar("CensoEmpresaId")."'
					ORDER BY e.primer_nombre , e.segundo_nombre , e.primer_apellido";

				if(!$result = $this->ConexionBaseDatos($query))
					return false;
	
					if($result->RecordCount()>0)
					{
						while(!$result->EOF)
						{
							$vars[$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
			
			return $vars;
		}

		function ListadoObservacionUrgencias()
    {
			list($dbconn) = GetDBconn();
			
			$query = "  SELECT a.*
									FROM
											(
													SELECT 
															i.departamento,
															i.descripcion as nom_departamento,
															h.estacion_id, 
															h.descripcion as nom_estacion,
															(SELECT verificacionpaciente_ecirugia(a.numerodecuenta)) as paciente_cirugia,
															a.movimiento_id,
															a.numerodecuenta,
															c.gravamen_valor_cubierto,
															c.valor_cubierto,
															a.fecha_ingreso AS fecha_hospitalizacion,
															b.pieza,
															a.cama,
															d.ingreso,
															d.fecha_ingreso,
															TO_CHAR(d.fecha_ingreso,'YYYY-MM-DD HH:MI') AS fecha_ingreso1,
															d.paciente_id,
															d.tipo_id_paciente,
															e.primer_nombre,
															e.segundo_nombre,
															e.primer_apellido,
															e.segundo_apellido,
															e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
															f.plan_id,
															f.plan_descripcion,
															f.tercero_id,
															f.tipo_tercero_id,
															g.nombre_tercero,
															j.tipo_afiliado_nombre,
															k.rango
													FROM
															movimientos_habitacion a,
															camas b,
															cuentas c
															LEFT JOIN tipos_afiliado j
															ON
															(
																c.tipo_afiliado_id=j.tipo_afiliado_id
															)
															JOIN planes_rangos k
															ON
															(
																k.tipo_afiliado_id=c.tipo_afiliado_id
																AND k.plan_id=c.plan_id
																AND k.rango=c.rango
															),
															ingresos d,
															pacientes e,
															planes f,
															terceros g,
															estaciones_enfermeria h,
															departamentos i
													WHERE
															a.fecha_egreso IS NULL
															AND a.estacion_id = h.estacion_id
															AND h.sw_observacion_urgencia='1'
															AND b.cama = a.cama
															AND c.numerodecuenta = a.numerodecuenta
															AND d.ingreso = a.ingreso
															AND e.paciente_id = d.paciente_id
															AND e.tipo_id_paciente = d.tipo_id_paciente
															AND f.plan_id = c.plan_id
															AND f.estado='1'
															AND g.tercero_id = f.tercero_id
															AND g.tipo_id_tercero = f.tipo_tercero_id
															AND h.departamento=i.departamento
															AND c.empresa_id = '".SessionGetVar("CensoEmpresaId")."'
											) AS a 
									ORDER BY a.ingreso,a.cama, a.pieza;";              
									
				if(!$result = $this->ConexionBaseDatos($query))
					return false;

				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			
			return $vars;
		}

		function ReportesCuentas($opcion)
    {
			switch($opcion)
			{
				case 1:
					$sqlestado="WHERE A.estado='1'";
				break;
				
				case 2:
					$sqlestado="WHERE A.estado='2'";
				break;
				case 3:
					$sqlestado="WHERE A.estado IN('1','2')";
				break;
			}
			
			list($dbconn) = GetDBconn();
			$query = "  SELECT A.*
											FROM
											(
													SELECT 
															i.departamento,
															i.descripcion as nom_departamento,
															h.estacion_id, 
															h.descripcion as nom_estacion,
															a.numerodecuenta,
															c.gravamen_valor_cubierto,
															c.valor_cubierto,
															c.estado,
															CASE c.estado 
															WHEN '1' THEN 'ACTIVA'
															WHEN '2' THEN 'INACTIVA'
															END as estado_cuenta,
															a.ingreso,
															b.pieza,
															a.cama,
															d.fecha_ingreso,
															d.paciente_id,
															d.tipo_id_paciente,
															e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo,
															f.plan_id,
															f.plan_descripcion,
															f.tercero_id,
															f.tipo_tercero_id,
															g.nombre_tercero,
															j.tipo_afiliado_nombre,
															k.rango
													FROM
															movimientos_habitacion a,
															camas b,
															cuentas c
															LEFT JOIN tipos_afiliado j
															ON
															(
																c.tipo_afiliado_id=j.tipo_afiliado_id
															)
															JOIN planes_rangos k
															ON
															(
																k.tipo_afiliado_id=c.tipo_afiliado_id
																AND k.plan_id=c.plan_id
																AND k.rango=c.rango
															),
															ingresos d,
															pacientes e,
															planes f,
															terceros g,
															estaciones_enfermeria h,
															departamentos i
													WHERE
															a.estacion_id = h.estacion_id
															AND b.cama = a.cama
															AND c.numerodecuenta = a.numerodecuenta
															AND d.ingreso = a.ingreso
															AND e.paciente_id = d.paciente_id
															AND e.tipo_id_paciente = d.tipo_id_paciente
															AND f.plan_id = c.plan_id
															AND f.estado='1'
															AND g.tercero_id = f.tercero_id
															AND g.tipo_id_tercero = f.tipo_tercero_id
															AND h.departamento=i.departamento
															AND c.empresa_id = '".SessionGetVar("CensoEmpresaId")."'
											) AS A
									$sqlestado 
									ORDER BY A.numerodecuenta;";              
								
				if(!$result = $this->ConexionBaseDatos($query))
					return false;

				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[$result->fields[1]][$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			
			return $vars;
		}

		function GetEstancia($cuenta,$fecha_ini=null,$fecha_fin=null)
		{
			static $liq;
			
			if(!is_object($liq))
			{
				if(IncludeClass("LiquidacionHabitaciones")===false) 
					echo "Error al Incluir Clase LiquidacionHabitaciones";
				$liq=new LiquidacionHabitaciones;
			}
			
			$retorno=$liq->LiquidarCargosInternacion($cuenta,false,$fecha_ini,$fecha_fin);
			$valor=0;
			foreach($retorno as $k=>$ret)
			{
				$valor+=$ret['valor_cubierto'];
			}
			unset($liq);
			return $valor;
		}

		function GetCamas($estacion,$estado)
		{
			list($dbconn) = GetDBconn();
			
			$query="SELECT count(DISTINCT b.cama)
							FROM piezas as a
							JOIN camas as b
							ON
							(
								a.pieza=b.pieza
							)
							WHERE a.estacion_id='$estacion'
							AND b.estado='$estado'";
			
			if(!$result = $this->ConexionBaseDatos($query))
				return false;

			$num_camas=$result->fields[0];
			
			return $num_camas;
		}

		/**
		**/
		function GetPlanes($estado=null)
		{
			list($dbconn) = GetDBconn();
			
			if(is_null($estado))
				$d_estado="WHERE estado='1'";
			else
			{
				switch($estado)
				{
					case 1:
						$d_estado="WHERE estado='1'";
					break;
					case 2:
						$d_estado="WHERE estado!='1'";
					break;
					case 3:
						$d_estado="";
					break;
				}
			}
			
			$query = "  SELECT DISTINCT plan_id,plan_descripcion,estado
									FROM planes
									$d_estado
									ORDER BY plan_descripcion";              
			if(!$result = $this->ConexionBaseDatos($query))
				return false;
								
				if($result->RecordCount()>0)
				{
					while(!$result->EOF)
					{
						$vars[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			
			return $vars;
		}

		function ListadoPacientesAtendidos($fecha_ini,$fecha_fin,$tplan,$tipo)
		{
			if(!empty($fecha_ini) AND !empty($fecha_fin))
			{
				$fechas="	AND date(c.fecha_cargo)>='$fecha_ini' 
									AND date(c.fecha_cargo)<='$fecha_fin'";
			}
		
			if(!empty($tplan))
			{
				$plan="AND e.plan_id=$tplan";
			}
			
			list($dbconn) = GetDBconn();

			switch($tipo)
			{
				case 1:
					 $query="	SELECT 
											a.numerodecuenta, 
										 	p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo,
											p.tipo_id_paciente, 
											p.paciente_id,
											a.valor_cuota_paciente,
											sum(c.valor_cubierto) as valor_cubierto
										FROM 
											cuentas a,  
											ingresos i, 
											pacientes p, 
											cuentas_detalle c, 
											departamentos d, 
											planes e
										WHERE 
											a.numerodecuenta = c.numerodecuenta
											and c.facturado = '1'
											and a.ingreso = i.ingreso
											and (i.tipo_id_paciente = p.tipo_id_paciente
											and i.paciente_id = p.paciente_id)
											and c.departamento_al_cargar = d.departamento
											and d.servicio in ('1','2','6')
											and a.plan_id = e.plan_id
											$fechas
											$plan
										group by 1,2,3,4,5
										order by 1,2
					 				";
					break;
					case 2:
							
								$query=" 
											SELECT 
												a.numerodecuenta, 
												p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo,
												p.tipo_id_paciente, 
												p.paciente_id,
												a.valor_cuota_paciente,
												sum(c.valor_cubierto) as valor_cubierto
											FROM 
												cuentas a,  
												ingresos i, 
												pacientes p, 
												cuentas_detalle c, 
												planes e
											WHERE c.departamento_al_cargar= '021501'
												and c.facturado = '1'
												and c.numerodecuenta = a.numerodecuenta
												and a.plan_id = e.plan_id
												and a.ingreso = i.ingreso
												and (i.tipo_id_paciente = p.tipo_id_paciente
												and i.paciente_id = p.paciente_id)
												$fechas
												$plan
										group by 1,2,3,4,5
										order by 1,2;
									";       
					break;
					case 3:
							$query="
									SELECT 
										a.numerodecuenta, 
										p.primer_nombre || ' ' || p.segundo_nombre || ' ' || p.primer_apellido || ' ' || p.segundo_apellido as nombre_completo,
										p.tipo_id_paciente, 
										p.paciente_id,
										a.valor_cuota_paciente,
										sum(c.valor_cubierto) as valor_cubierto
									FROM 
										cuentas a,  
										ingresos i, 
										pacientes p, 
										cuentas_detalle c, 
										planes e,
										os_maestro g,
										os_ordenes_servicios h
									WHERE 
										c.numerodecuenta = a.numerodecuenta
										and c.facturado = '1'
										and a.plan_id = e.plan_id
										and a.numerodecuenta  = g.numerodecuenta
										and a.ingreso = i.ingreso
										and (i.tipo_id_paciente = p.tipo_id_paciente
										and i.paciente_id = p.paciente_id)
										and h.orden_servicio_id = g.orden_servicio_id
										and h.servicio = '3'
										$fechas
										$plan
										group by 1,2,3,4,5
										order by 1,2;
							";
					break;
			} 

			if(!$result = $this->ConexionBaseDatos($query))
				return false;

			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
			
			return $vars;
		}
		/*
		* Llama la forma del menu de facuracion
		* @access public
		* @return boolean
		* @param int caja_id
		*/
		function DatosEncabezadoEmpresa()
		{
			IncludeClass('Facturacion','','app','Facturacion');
			$Ctu = SessionGetVar("DatosEmpresaId");
			$Emp = SessionGetVar("DatosCentroUtilidadId");

			$fct = new Facturacion();
			$datos = array();
			$datos = $fct->ObtenerDatosEmpresa($Emp,$Ctu,SessionGetVar("DepartamentoCuentas"),1);

					return $datos;
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		*
    * @access public  
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}    
   
	}
?>