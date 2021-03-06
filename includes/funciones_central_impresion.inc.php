<?php

/**
 * $Id: funciones_central_impresion.inc.php,v 1.27 2008/07/18 21:28:52 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * ESTE QUERY TRAEN TODAS LAS INCAPACIDADES GENERADAS EN UN INGRESO
 * ESTA CONSULTA ARMA EL HTML.
 */

	function Consulta_Incapacidades_GeneradasIngreso($ingreso)
	{
          list($dbconnect) = GetDBconn();
          $query= "	SELECT a.evolucion_id, a.tipo_incapacidad_id, a.fecha_inicio,
                              c.descripcion as tipo_incapacidad_descripcion, 
                              a.observacion_incapacidad,
                              a.dias_de_incapacidad, b.fecha

                    FROM hc_incapacidades as a,	hc_evoluciones as b,
                    hc_tipos_incapacidad as c

                    WHERE a.evolucion_id = b.evolucion_id	and b.ingreso = ".$ingreso."
                    and a.tipo_incapacidad_id = c.tipo_incapacidad_id";
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de solictud de apoyos";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                         $vector[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
               }
          }
          $result->Close();
          return $vector;
	}

	/**
	*ok revisada por claudia es invocada desde la central de autorizaciones - consulta externa
	*/
	function Consulta_Incapacidades_GeneradasEvolucion($evolucion)
	{
          list($dbconnect) = GetDBconn();
          $query= " SELECT a.evolucion_id, a.tipo_incapacidad_id,	
                              c.descripcion as tipo_incapacidad_descripcion, a.observacion_incapacidad,
                              a.dias_de_incapacidad, b.fecha, a.fecha_inicio,
	  			d.nombre, f.descripcion as especialidad

                    FROM hc_incapacidades as a,	
                         hc_evoluciones as b,
                         hc_tipos_incapacidad as c,
			 profesionales d, profesionales_especialidades es, especialidades f
               

                    WHERE a.evolucion_id = ".$evolucion."	and a.evolucion_id = b.evolucion_id
                    and a.tipo_incapacidad_id = c.tipo_incapacidad_id
		    AND b.usuario_id = d.usuario_id 
		    AND d.tipo_id_tercero = es.tipo_id_tercero
		    AND d.tercero_id = es.tercero_id
		    AND es.especialidad = f.especialidad";

          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de solictud de apoyos";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                         $vector[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
               }
          }
          $result->Close();
          return $vector;
	}


	/**
	*	Funcion de consulta de medicamentos formulados por consulta externa, tambien
     *	como formulacion hospitalaria de medicamentos ambulatorios.
     *
     *	Adaptacion: Tizziano Perea.
	*/
	function GetMedicamentosAmb($evolucion,$sw_farmacia)
	{
		list($dbconn) = GetDBconn();
		if(!empty($sw_farmacia))
		{
               $sql="SELECT a.evolucion_id, k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
		                  else 'NO POS' end as item,	a.sw_paciente_no_pos, a.codigo_producto,
                            h.descripcion as producto,	c.descripcion as principio_activo, m.nombre as via,
                            a.dosis, a.unidad_dosificacion,	a.tipo_opcion_posologia_id, a.cantidad,
                            l.descripcion, h.contenido_unidad_venta, a.observacion
			    --, a.dias_tratamiento
     
                    FROM hc_medicamentos_recetados_amb as a left join hc_vias_administracion as m
                         on (a.via_administracion_id = m.via_administracion_id),
                         inv_med_cod_principios_activos as c, inventarios_productos as h,
                         medicamentos as k, unidades as l,	hc_evoluciones as y, cuentas as z, planes as x
     
                    WHERE a.evolucion_id = ".$evolucion."
                    AND a.evolucion_id=y.evolucion_id
                    AND y.numerodecuenta=z.numerodecuenta
                    AND z.plan_id=x.plan_id and x.sw_tipo_plan=3
                    AND k.cod_principio_activo = c.cod_principio_activo
                    AND k.sw_pos = 1
                    AND h.codigo_producto = k.codigo_medicamento and a.codigo_producto = h.codigo_producto
                    AND h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                    ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
		}
		else
		{
                   $sql="SELECT a.evolucion_id, k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                            else 'NO POS' end as item, a.sw_paciente_no_pos, a.codigo_producto,
                            h.descripcion as producto, c.descripcion as principio_activo, m.nombre as via,
                            a.dosis, a.unidad_dosificacion, a.tipo_opcion_posologia_id, a.cantidad,
                            l.descripcion, l.descripcion as unidad, h.contenido_unidad_venta, a.observacion,
                            'M' AS tipo_solicitud, hc.usuario_id
			    --, a.dias_tratamiento

                    FROM hc_medicamentos_recetados_amb as a left join hc_vias_administracion as m
                         on (a.via_administracion_id = m.via_administracion_id),
                         inv_med_cod_principios_activos as c, inventarios_productos as h,
                         medicamentos as k, unidades as l, hc_evoluciones as hc

                    WHERE a.evolucion_id = ".$evolucion."
                    AND k.cod_principio_activo = c.cod_principio_activo
                    AND h.codigo_producto = k.codigo_medicamento 
                    AND a.codigo_producto = h.codigo_producto
                    AND h.codigo_producto = a.codigo_producto 
                    AND h.unidad_id = l.unidad_id
                    AND hc.evolucion_id = a.evolucion_id
                    ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
          }
          $result = $dbconn->Execute($sql);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
		return $var;
     }

	/*
     *	GetMedicamentos()
     *	Consulta de formulacion de medicamentos recetados desde el departamento de 
     *	Hospitalizacion o Urgencias.
     *
     *	Adaptacion: Tizziano Perea.
     */
     function GetMedicamentos($evolucion)
     {
          list($dbconn) = GetDBconn();
          $sql="SELECT B.evolucion_id,
          		   A.sw_estado, A.codigo_producto, A.cantidad, A.dosis, A.frecuencia,
                       A.unidad_dosificacion, A.observacion, A.justificacion_no_pos_id,
                       B.fecha_registro, B.usuario_id,
                       H.descripcion as producto, 
                       C.descripcion as principio_activo,
                       K.sw_uso_controlado, 
                       CASE WHEN K.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
                       M.nombre AS via,
                       L.descripcion AS unidad,
                       'M' AS tipo_solicitud
		       --,
                       --B.dias_tratamiento
          	 
                FROM   hc_formulacion_medicamentos AS A
                	   LEFT JOIN hc_vias_administracion AS M ON (A.via_administracion_id = M.via_administracion_id),
                	   hc_formulacion_medicamentos_eventos AS B,
                       inventarios_productos AS H,
                       medicamentos AS K,
                       inv_med_cod_principios_activos AS C,                       
                       unidades AS L,
                       hc_evoluciones N
                
                WHERE B.evolucion_id = ".$evolucion."
                AND   B.evolucion_id =  N.evolucion_id
                AND   N.estado = '0'
                AND   A.ingreso = N.ingreso
                AND   A.codigo_producto = B.codigo_producto
                AND   A.num_reg_formulacion = B.num_reg
                AND   A.sw_estado = '1'
                AND   H.codigo_producto = A.codigo_producto
                AND   K.codigo_medicamento = A.codigo_producto
                AND   K.cod_principio_activo = C.cod_principio_activo
                AND   H.unidad_id = L.unidad_id
                ORDER BY K.sw_pos, A.codigo_producto, B.evolucion_id;";                 
          $result = $dbconn->Execute($sql);
          $i=0;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          return $var;
     }
     
	
     /*
     *	GetSoluciones()
     *	Consulta de formulacion de medicamentos recetados desde el departamento de 
     *	Hospitalizacion o Urgencias.
     *
     *	Adaptacion: Tizziano Perea.
     */
     function GetSoluciones($evolucion)
     {
          list($dbconn) = GetDBconn();
          $sql="SELECT DISTINCT A.num_mezcla,
          		   B.evolucion_id,
          		   A.sw_estado, DET.codigo_producto, A.cantidad,
                       A.volumen_infusion, A.unidad_volumen, B.usuario_id,
                       A.observacion, DET.cantidad AS cantidad_producto, 
                       DET.unidad_dosificacion AS unidad_suministro,
                       DET.dosis,
                       H.descripcion as producto, 
                       C.descripcion as principio_activo,
                       K.sw_uso_controlado, 
                       CASE WHEN K.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
                       L.descripcion AS unidad,
                       'S' AS tipo_solicitud
          	 
                FROM   hc_formulacion_mezclas AS A,
                	   hc_formulacion_mezclas_eventos AS B,
                       hc_formulacion_mezclas_detalle AS DET,
                       inventarios_productos AS H,
                       medicamentos AS K,
                       inv_med_cod_principios_activos AS C,                       
                       unidades AS L,
                       hc_evoluciones N
                
                WHERE B.evolucion_id = ".$evolucion."
                AND   B.evolucion_id =  N.evolucion_id
                AND   N.estado = '0'
                AND   A.num_mezcla = B.num_mezcla
                AND   A.num_mezcla = DET.num_mezcla
                AND   A.sw_estado = '1'
                AND   H.codigo_producto = DET.codigo_producto
                AND   K.codigo_medicamento = DET.codigo_producto
                AND   K.cod_principio_activo = C.cod_principio_activo
                AND   H.unidad_id = L.unidad_id
                ORDER BY A.num_mezcla DESC;";                 
          $result = $dbconn->Execute($sql);
          $i=0;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          return $var;
     }     

	//METODO PARA CONSULTAR LOS MEDICAMENTOS RECETADOS A PACIENTES EN HOSPITALIZACION
	//Y EN ATENCION DE URGENCIAS - ES IGUAL A GetMedicamentos SOLO QUE ESTE BUSCA POR INGRESO
	///OK REVISADO POR CLAUDIA
  function GetMedicamentosIngreso($ingreso)
  {
			list($dbconn) = GetDBconn();
			$sql="  SELECT a.evolucion_id, a.sw_estado,	k.sw_uso_controlado,
			        case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
							a.codigo_producto, a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
							a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
							h.descripcion as producto, c.descripcion as principio_activo,
							h.contenido_unidad_venta, l.descripcion, a.evolucion_id

							FROM hc_medicamentos_recetados_hosp as a left join hc_vias_administracion as m
							on (a.via_administracion_id = m.via_administracion_id),
							inv_med_cod_principios_activos as c, inventarios_productos as h,
							medicamentos as k, unidades as l,hc_evoluciones n

							WHERE n.ingreso = ".$ingreso."
							and n.estado = '0'
							and a.evolucion_id = n.evolucion_id and
							a.sw_estado = '1'
							and	k.cod_principio_activo = c.cod_principio_activo and
							h.codigo_producto = k.codigo_medicamento and
							a.codigo_producto = h.codigo_producto and
							h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
							order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id";
			$result = $dbconn->Execute($sql);
			$i=0;
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
						while (!$result->EOF)
						{
							$var[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}

			}
			$result->Close();
			return $var;
  }


	//ok revisada por claudia es invocada desde la central de autorizaciones - consulta externa
     function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia, $evolucion_id)
     {
		$pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
		$query == '';
		if ($tipo_posologia == 1)
		{
               $query= "select periocidad_id, tiempo from hc_posologia_horario_op1 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 2)
		{
               $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2 as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
		}
		if ($tipo_posologia == 3)
		{
			$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 4)
		{
			$query= "select hora_especifica from hc_posologia_horario_op4 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}
		if ($tipo_posologia == 5)
		{
			$query= "select frecuencia_suministro from hc_posologia_horario_op5 where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
		}

		if ($query!='')
		{
               $result = $dbconnect->Execute($query);
               if ($dbconnect->ErrorNo() != 0)
               {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
               }
               else
               {
                    if ($tipo_posologia != 4)
                    {
                         while (!$result->EOF)
                         {
                              $vector[]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
                    else
                    {
                         while (!$result->EOF)
                         {
                              $vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
               }
		}
     	return $vector;
	}


//REVISADO CLAUDIA
//Consulta_Solicitud_Medicamentos_PosologiaHosp
function Consulta_Solicitud_Medicamentos_Posologia_Hosp($codigo_producto, $tipo_posologia, $evolucion_id)
{
		$pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();
		$query == '';
		if ($tipo_posologia == 1)
		{
				$query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '".$codigo_producto."'";
		}
		if ($tipo_posologia == 2)
		{
				$query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '".$codigo_producto."' and a.duracion_id = b.duracion_id";
		}
		if ($tipo_posologia == 3)
		{
    		$query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '".$codigo_producto."'";
		}
		if ($tipo_posologia == 4)
		{
    		$query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '".$codigo_producto."'";
		}
		if ($tipo_posologia == 5)
		{
    		$query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '".$codigo_producto."'";
		}


		if ($query!='')
		{
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
					$this->error = "Error al buscar en la consulta de medicamentos recetados";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
				}
				else
				{
					if ($tipo_posologia != 4)
					{
						while (!$result->EOF)
						{
							$vector[]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
					else
					{
						while (!$result->EOF)
						{
							$vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
							$result->MoveNext();
						}
					}
				}
				//$result->Close();
		}
	  return $vector;
}

//----------------FIN FUNCIONES CLAUDIA

//*****************************************DARLING-****************************
	/**
	*
	*/
  function BuscarSolicitudesEvolucion($evolucion)
  {									
     list($dbconn) = GetDBconn();
     $query="SELECT i.ingreso, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
                    p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente, j.paciente_id,
                    k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                    a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
                    
                    case a.sw_ambulatorio when 1 then '3' else l.servicio end as servicio,
                    --l.servicio, 							
                    case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
                    --m.descripcion as desserv, 
                    case a.sw_ambulatorio when 1 then '' else l.descripcion end as despto,
                    --l.descripcion as despto,							
                    
                    g.descripcion as desos, i.fecha, a.evolucion_id,
                    p.nivel_autorizador_id as nivel, a.cantidad,
                    x.dias_tramite_os as trap, z.dias_tramite_os as tra,
                    y.tipo_afiliado_id, y.rango, y.semanas_cotizadas, a.sw_ambulatorio,
                    n.observacion as obsapoyo, b.observacion as obsinter, BB.observacion as obsnoqx
          FROM hc_os_solicitudes as a
               left join os_tipos_periodos_planes as x on(x.cargo=a.cargo and a.plan_id=x.plan_id)
               left join os_tipos_periodos_tramites as z on(z.cargo=a.cargo)
               left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=a.hc_os_solicitud_id)
               left join hc_os_solicitudes_interconsultas as b on(b.hc_os_solicitud_id=a.hc_os_solicitud_id)
               left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=a.hc_os_solicitud_id),
               planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
               ingresos as j, pacientes as k, departamentos as l, servicios as m,
               cups as p, cuentas as y
          WHERE a.evolucion_id=$evolucion
               and p.cargo=a.cargo
               and a.sw_estado=1
               and a.plan_id=f.plan_id
               and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
               and a.evolucion_id is not null
               and a.evolucion_id=i.evolucion_id
               and i.numerodecuenta=y.numerodecuenta
               and i.ingreso=j.ingreso
               and j.tipo_id_paciente=k.tipo_id_paciente
               and j.paciente_id=k.paciente_id
               and  i.departamento=l.departamento
               and l.servicio=m.servicio
               order by servicio, j.tipo_id_paciente, j.paciente_id, a.os_tipo_solicitud_id";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {
        while (!$result->EOF)
        {
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
     function BuscarOrdenesSEvolucion($evolucion)
     {
          list($dbconn) = GetDBconn();
          // Edited By. Tizziano Perea
          $query = "SELECT SUB_b.*,
                           b.tipo_afiliado_nombre,
                           c.descripcion AS desserv,
                           f.descripcion,
                           p.plan_descripcion,
                           d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido AS nombre
                    FROM
                    (
                         SELECT SUB.*,
                                g.cargo, g.departamento,
                                l.descripcion AS desdpto, 
                                h.cargo AS cargoext,
                                i.plan_proveedor_id,
                                i.plan_descripcion as planpro
                         FROM
                              (
                                   SELECT q.evolucion_id,
                                          e.numero_orden_id, 
                                          CASE WHEN e.sw_estado=1 THEN 'ACTIVO' 
                                          WHEN e.sw_estado=2 THEN 'PAGADO' 
                                          WHEN e.sw_estado=3 THEN 'PARA ATENCION' 
                                          WHEN e.sw_estado=8 THEN 'ANULADA POR VENCIMIENTO' 
                                          WHEN e.sw_estado=0 THEN 'ATENDIDA' ELSE 'ANULADA' END AS estado, 
                                          e.sw_estado, e.fecha_vencimiento, e.cantidad, 
                                          e.hc_os_solicitud_id, e.fecha_activacion, 
                                          e.fecha_refrendar, e.cargo_cups, 
                                          a.*
                                   FROM   hc_os_solicitudes AS q
                                          RIGHT JOIN os_maestro AS e ON (q.hc_os_solicitud_id = e.hc_os_solicitud_id),
                                          os_ordenes_servicios AS a
                                   WHERE  q.evolucion_id = ".$evolucion."
                                   AND    a.orden_servicio_id = e.orden_servicio_id
                                   AND    e.sw_estado in('1','2')
                              ) AS SUB
                              LEFT JOIN os_internas AS g ON (SUB.numero_orden_id = g.numero_orden_id)
                              LEFT JOIN departamentos AS l ON (g.departamento = l.departamento)
                              LEFT JOIN os_externas AS h ON (SUB.numero_orden_id = h.numero_orden_id)
                              LEFT JOIN planes_proveedores AS i ON (h.plan_proveedor_id = i.plan_proveedor_id)
                    ) AS SUB_b,
                    tipos_afiliado AS b,
                    servicios AS c,
                    cups AS f,
                    planes as p,
                    pacientes as d
                    
                    WHERE b.tipo_afiliado_id = SUB_b.tipo_afiliado_id
                    AND   c.servicio = SUB_b.servicio 
                    AND   f.cargo = SUB_b.cargo_cups 
                    AND   p.plan_id = SUB_b.plan_id
                    AND   d.tipo_id_paciente = SUB_b.tipo_id_paciente 
                    AND   d.paciente_id = SUB_b.paciente_id 
                    
                    ORDER BY SUB_b.orden_servicio_id, SUB_b.plan_id";
          
         /*$query = " select q.evolucion_id, a.*, b.tipo_afiliado_nombre, c.descripcion as desserv,
                    d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                    case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO' when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,
                    e.sw_estado, e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion, g.cargo,
                    g.departamento, l.descripcion as desdpto, h.cargo as cargoext,
                    i.plan_proveedor_id, i.plan_descripcion as planpro, p.plan_descripcion
                    from os_ordenes_servicios as a join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                    left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                    left join departamentos as l on(g.departamento=l.departamento)
                    left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                    left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                    left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
                    tipos_afiliado as b, servicios as c, pacientes as d, cups as f, planes as p
                    where q.evolucion_id=$evolucion and a.tipo_afiliado_id=b.tipo_afiliado_id
                    and a.servicio=c.servicio and a.plan_id=p.plan_id and a.tipo_id_paciente=d.tipo_id_paciente
                    and a.paciente_id=d.paciente_id and e.cargo_cups=f.cargo
                    and e.sw_estado in('1','2')
                    order by a.orden_servicio_id,a.plan_id";*/
                    
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
          }
          else
          {
          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          }
          $result->Close();
          return $var;
     }


	/**
	*
	*/
	function BuscarSolicitudesIngreso($ingreso,$departamento=null)
	{
          list($dbconn) = GetDBconn();
          if(!empty($departamento))
          {
               $sql_filtro=" AND Q.departamento='$departamento'";
          }
          else
          {
               $sql_filtro="";
          }
          
          // Edited By. Tizziano Perea
          $query ="
          SELECT    tabla.evolucion_id, tabla.ingreso,
                    tabla.cantidad, tabla.hc_os_solicitud_id, tabla.cargos,
                    tabla.descar, tabla.nivel_autorizador_id, tabla.sw_pos,
                    tabla.tipo_id_paciente, tabla.paciente_id, tabla.nombres,
                    tabla.plan_id, tabla.plan_descripcion, tabla.os_tipo_solicitud_id,
                    tabla.sw_estado, tabla.desserv, tabla.despto,
                    tabla.servicio, tabla.descripcion, tabla.desos,
                    tabla.fecha, tabla.profesional,
                    tabla.prestador, tabla.observaciones,
                    tabla.nivel, 
                    Q.departamento,
                    tabla.tipo_id_tercero, tabla.sw_ambulatorio
               
               FROM
               (
                    SELECT DISTINCT 
                    	A.*,
                         P.primer_nombre || ' ' || P.segundo_nombre || ' ' || P.primer_apellido || ' ' || P.segundo_apellido AS nombres,
                         G.descripcion as desos,
                         F.plan_descripcion, 
                         NULL AS profesional,
                         NULL AS prestador,
                         NULL AS observaciones,
                         D.nivel_autorizador_id as nivel,
                         D.descripcion as descar,
                         D.nivel_autorizador_id,
                         D.sw_pos,
                         D.descripcion,
                         L.servicio,
                         CASE A.sw_ambulatorio WHEN 1 THEN '' ELSE L.descripcion END AS despto,
                         CASE A.sw_ambulatorio WHEN 1 THEN 'AMBULATORIO' ELSE M.descripcion END AS desserv,
                         r.tipo_id_tercero
                    FROM (
                         SELECT 
                              A.cantidad,
                              A.hc_os_solicitud_id,
                              A.cargo as cargos,
                              A.plan_id,
                              A.os_tipo_solicitud_id,
                              A.sw_estado,
                              A.sw_ambulatorio,
                              I.fecha,
                              I.evolucion_id,
                              I.ingreso,
                              I.departamento AS dpto_evolucion,
                              J.tipo_id_paciente,
                              J.paciente_id
                              
                         FROM ingresos AS J,
                              hc_evoluciones AS I,
                              hc_os_solicitudes AS A
                              
                         WHERE J.ingreso = ".$ingreso."
                         AND   J.ingreso = I.ingreso
                         AND   A.evolucion_id = I.evolucion_id
                         AND   A.evolucion_id IS NOT NULL
                         AND   A.sw_ambulatorio='0' 
                         AND   A.sw_estado=1 
                         ) AS A
                         LEFT JOIN terceros_proveedores_cargos AS R ON (R.cargo = A.cargos),
                         pacientes AS P,
                         os_tipos_solicitudes AS G,
                         planes AS F,
                         cups AS D,
                         departamentos as L,
                         servicios AS M
                         
                    WHERE P.paciente_id = A.paciente_id
                    AND   P.tipo_id_paciente = A.tipo_id_paciente
                    AND   A.os_tipo_solicitud_id = G.os_tipo_solicitud_id
                    AND   A.plan_id = F.plan_id
                    AND   D.cargo = A.cargos
                    AND   L.departamento = A.dpto_evolucion
                    AND   L.servicio = M.servicio
               ) AS tabla
               LEFT JOIN departamentos_cargos AS Q ON (Q.cargo = tabla.cargos $sql_filtro)
               ORDER BY tabla.evolucion_id ASC;";
                    
                    /*"SELECT distinct 
					i.evolucion_id,
					i.ingreso,
					a.cantidad,
					a.hc_os_solicitud_id,
					a.cargo as cargos,
					p.descripcion as descar,
					p.nivel_autorizador_id,
					p.sw_pos,
					j.tipo_id_paciente,
					j.paciente_id,
					k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
					a.plan_id,f.plan_descripcion,
					a.os_tipo_solicitud_id,
					a.sw_estado,
					case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
					--m.descripcion as desserv, 
					case a.sw_ambulatorio when 1 then '' else l.descripcion end as despto,
					--l.descripcion as despto,
					l.servicio,
					p.descripcion,
					g.descripcion as desos,
					i.fecha,
					NULL as profesional,
					NULL as prestador,
					NULL as observaciones,
					p.nivel_autorizador_id as nivel,
					q.departamento,
					r.tipo_id_tercero,
					a.sw_ambulatorio
				FROM 
					hc_os_solicitudes as a, 
					planes as f, 
					os_tipos_solicitudes as g, 
					hc_evoluciones as i,
					ingresos as j,
					pacientes as k,
					departamentos as l,
					servicios as m,
					cups as p left join departamentos_cargos as q on(p.cargo=q.cargo $sql_filtro)
					left join terceros_proveedores_cargos as r on(p.cargo=r.cargo)
				WHERE 
					j.ingreso=$ingreso and 
					p.cargo=a.cargo and 
					a.plan_id=f.plan_id and
					a.sw_ambulatorio='0' and 
					a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and 
					a.evolucion_id is not null and 
					a.evolucion_id=i.evolucion_id and 
					i.ingreso=j.ingreso and 
					j.tipo_id_paciente=k.tipo_id_paciente and 
					j.paciente_id=k.paciente_id and 
					a.sw_estado=1 and 
					i.departamento=l.departamento and 
					l.servicio=m.servicio";*/
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer las solicitudes";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          $result->Close();
          return $var;
	}

	/**
	*
	*/
	function BuscarOrdenesIngreso($ingreso)
	{
          list($dbconn) = GetDBconn();
          $query = "SELECT SOLICITUDES.orden_servicio_id,
                    SOLICITUDES.autorizacion_int, SOLICITUDES.autorizacion_ext,
                    SOLICITUDES.plan_id, SOLICITUDES.tipo_afiliado_id,
                    SOLICITUDES.semanas_cotizadas, SOLICITUDES.servicio,
                    SOLICITUDES.tipo_id_paciente, SOLICITUDES.paciente_id,
                    SOLICITUDES.usuario_id, SOLICITUDES.fecha_registro,
                    SOLICITUDES.observacion, SOLICITUDES.rango,
                    SOLICITUDES.evento_soat,
                    TA.tipo_afiliado_nombre, 
                    SER.descripcion AS desserv,
                    P.primer_nombre || ' ' || P.segundo_nombre || ' ' || P.primer_apellido || ' ' || P.segundo_apellido AS nombre,              
                    SOLICITUDES.numero_orden_id, SOLICITUDES.estado,
                    SOLICITUDES.sw_estado, SOLICITUDES.fecha_vencimiento, 
                    SOLICITUDES.cantidad, SOLICITUDES.hc_os_solicitud_id, 
                    SOLICITUDES.fecha_activacion, SOLICITUDES.fecha_refrendar, 
                    SOLICITUDES.cargo_cups,
                    CU.descripcion,
                    OSI.cargo, 
                    OSI.departamento,
                    L.descripcion AS desdpto,
                    H.cargo as cargoext,
                    I.plan_proveedor_id, 
                    I.plan_descripcion as planpro,
                    PL.plan_descripcion,
                    SOLICITUDES.autorizacion_int,
                    SOLICITUDES.autorizacion_ext
               
               FROM
               (
                    SELECT OSS.*,
                         OS.numero_orden_id,
                         CASE WHEN OS.sw_estado = 1 THEN 'ACTIVO' 
                              WHEN OS.sw_estado = 2 THEN 'PAGADO' 
                              WHEN OS.sw_estado = 3 THEN 'PARA ATENCION' 
                              WHEN OS.sw_estado = 7 THEN 'TRASCRIPCION' 
                              WHEN OS.sw_estado = 8 THEN 'ANULADA POR VENCIMIENTO'  
                              WHEN OS.sw_estado = 0 THEN 'ATENDIDA' 
                              ELSE 'ANULADA' END AS estado,
                         OS.sw_estado,
                         OS.fecha_vencimiento,
                         OS.cantidad, 
                         OS.hc_os_solicitud_id, 
                         OS.fecha_activacion, 
                         OS.fecha_refrendar, 
                         OS.cargo_cups
                    
                    FROM      
                         (SELECT EVO.evolucion_id,
                              SOL.hc_os_solicitud_id
                         
                         FROM hc_evoluciones AS EVO,
                              hc_os_solicitudes AS SOL
                         
                         WHERE EVO.ingreso = ".$ingreso."
                         AND   EVO.evolucion_id = SOL.evolucion_id
                         AND   SOL.os_tipo_solicitud_id  != 'CIT'
                         AND   SOL.sw_ambulatorio = '0') AS ORDENES,
                         
                         os_maestro AS OS,
                         os_ordenes_servicios AS OSS
                         
                    WHERE OS.hc_os_solicitud_id = ORDENES.hc_os_solicitud_id
                    AND   OSS.orden_servicio_id = OS.orden_servicio_id 
                    AND   OS.sw_estado IN ('1','2')
                    
               ) AS SOLICITUDES
               LEFT JOIN os_internas AS OSI ON (OSI.numero_orden_id = SOLICITUDES.numero_orden_id)
               LEFT JOIN departamentos AS L ON (L.departamento = OSI.departamento)
               LEFT JOIN os_externas AS H ON (H.numero_orden_id = OSI.numero_orden_id)
               LEFT JOIN planes_proveedores AS I ON (I.plan_proveedor_id = H.plan_proveedor_id),
               tipos_afiliado AS TA,
               pacientes AS P,
               servicios as SER,
               cups AS CU,
               planes AS PL
               
               WHERE TA.tipo_afiliado_id = SOLICITUDES.tipo_afiliado_id
               AND   P.tipo_id_paciente = SOLICITUDES.tipo_id_paciente
               AND   P.paciente_id = SOLICITUDES.paciente_id
               AND   SER.servicio = SOLICITUDES.servicio   
               AND   PL.plan_id = SOLICITUDES.plan_id
               AND   CU.cargo = SOLICITUDES.cargo_cups
               
               ORDER BY SOLICITUDES.orden_servicio_id DESC;";
          
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "Error al Guardar en la Tabal2 autorizaiones";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }

          while(!$result->EOF)
          {
               $vars2[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          $result->Close();
          return $vars2;                    

	  /*"select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
          case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
          e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
          g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro,
          p.plan_descripcion, a.autorizacion_int, a.autorizacion_ext
          from os_ordenes_servicios as a
          join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
          left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
          left join departamentos as l on(g.departamento=l.departamento)
          left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
          left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
          tipos_afiliado as b, servicios as c, pacientes as d, cups as f,
          planes as p, hc_os_solicitudes as z, hc_evoluciones as x
          where x.ingreso=$ingreso and x.evolucion_id=z.evolucion_id
          and z.os_tipo_solicitud_id<>'CIT'
          and z.sw_ambulatorio='0'
          and z.hc_os_solicitud_id=e.hc_os_solicitud_id
          and a.orden_servicio_id=e.orden_servicio_id
          and a.tipo_afiliado_id=b.tipo_afiliado_id
          and a.servicio=c.servicio
          and a.plan_id=p.plan_id
          and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id
          and e.cargo_cups=f.cargo
          and e.sw_estado in('1','2')
          order by a.orden_servicio_id desc";*/
	}


     /**
    *
    */
    function EncabezadoReporteIngreso($ingreso,$tipo,$paciente)
    {
        list($dbconn) = GetDBconn();
        $query = "select  b.tipo_id_paciente, b.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
                  t.tipo_id_tercero, t.id, t.razon_social, t.direccion, t.telefonos, u.departamento,
                  v.municipio, p.plan_descripcion, p.nombre_cuota_moderadora, p.nombre_copago,
                  w.nombre_tercero, d.tipo_afiliado_nombre, c.rango,
                  f.nombre as usuario, f.usuario_id, c.plan_id,d.tipo_afiliado_id,
									b.fecha_nacimiento, b.sexo_id, x.fecha_ingreso as fechaingreso
									from pacientes as b, cuentas as c,
                  empresas as t,   tipo_dptos as u, tipo_mpios as v, planes as p, terceros as w,
                  tipos_afiliado as d, system_usuarios as f, ingresos as x
									where c.ingreso=".$ingreso."
									and c.ingreso=x.ingreso
                  and c.empresa_id=t.empresa_id
                  and c.tipo_afiliado_id=d.tipo_afiliado_id
                  and f.usuario_id=".UserGetUID()."
                  and b.tipo_id_paciente='".$tipo."'
                  and c.plan_id=p.plan_id
                  and b.paciente_id='".$paciente."'
                  and t.tipo_pais_id=u.tipo_pais_id
                  and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id
                  and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
              $var=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }


    /**
    *
    */
    function EncabezadoReporteEvolucion($evolucion,$tipo,$paciente)
    {
        list($dbconn) = GetDBconn();
        $query = "select a.fecha, b.tipo_id_paciente, b.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
                  t.tipo_id_tercero, t.id, t.razon_social, t.direccion, t.telefonos, u.departamento,
                  v.municipio, p.plan_descripcion, p.nombre_cuota_moderadora, p.nombre_copago,
                  w.nombre_tercero, d.tipo_afiliado_nombre, c.rango, e.descripcion,e.servicio,
                  f.nombre as usuario, f.usuario_id, a.evolucion_id,c.plan_id,d.tipo_afiliado_id,
									b.fecha_nacimiento, b.sexo_id, x.fecha_ingreso as fechaingreso
									from hc_evoluciones as a, pacientes as b, cuentas as c,
                  empresas as t,   tipo_dptos as u, tipo_mpios as v, planes as p, terceros as w,
                  tipos_afiliado as d, departamentos as e, system_usuarios as f,
									ingresos as x
                  where a.evolucion_id=".$evolucion."
									and a.ingreso=x.ingreso
                  and f.usuario_id=".UserGetUID()."
                  and b.tipo_id_paciente='".$tipo."'
                  and c.plan_id=p.plan_id
                  and b.paciente_id='".$paciente."'
                  and a.numerodecuenta=c.numerodecuenta
                  and c.tipo_afiliado_id=d.tipo_afiliado_id
                  and a.departamento=e.departamento
                  and c.empresa_id=t.empresa_id
                  and t.tipo_pais_id=u.tipo_pais_id
                  and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id
                  and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
              $var=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }

//----------------------ORDEN-----------------------------------------------

    /**
    *
    */
    function EncabezadoReporteOrden($orden)
    {
				//         and q.evolucion_id is not null
        list($dbconn) = GetDBconn();
        $query = "( select b.tipo_afiliado_nombre, s.rango,
									d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
									n.nombre as usuario, n.usuario_id, u.departamento, v.municipio, t.tipo_id_tercero, t.id,
									t.razon_social, t.direccion, t.telefonos,w.nombre_tercero, p.plan_descripcion,
									p.nombre_cuota_moderadora, p.nombre_copago, a.tipo_id_paciente,a.paciente_id,
									a.tipo_afiliado_id, a.plan_id, a.rango, a.semanas_cotizadas, a.servicio, d.fecha_nacimiento,
									d.sexo_id, r.fecha as fechasolicitud, x.fecha_ingreso as fechaingreso,
									x.ingreso, NULL
									from os_ordenes_servicios as a
									left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
									left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
									planes as p
									left join terceros as w on(w.tipo_id_tercero=p.tipo_tercero_id and w.tercero_id=p.tercero_id),
									hc_evoluciones as r left join cuentas as s on(s.ingreso=r.ingreso)
									left join empresas as t on(s.empresa_id=t.empresa_id),
									tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
									tipos_afiliado as b, pacientes as d, ingresos as x
									where a.orden_servicio_id=".$orden."
									and n.usuario_id=".UserGetUID()."
									and r.ingreso=x.ingreso
									and a.tipo_afiliado_id=b.tipo_afiliado_id
									and a.plan_id=p.plan_id
									and a.tipo_id_paciente=d.tipo_id_paciente
									and a.paciente_id=d.paciente_id
									and q.evolucion_id=r.evolucion_id
									and t.tipo_pais_id=u.tipo_pais_id and t.tipo_dpto_id=u.tipo_dpto_id
									and t.tipo_pais_id=v.tipo_pais_id and t.tipo_dpto_id=v.tipo_dpto_id
									and t.tipo_mpio_id=v.tipo_mpio_id
                )
                UNION
                ( select b.tipo_afiliado_nombre, a.rango,
                  d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
                  n.nombre as usuario, n.usuario_id, u.departamento, v.municipio, t.tipo_id_tercero, t.id,
                  t.razon_social, t.direccion, t.telefonos,w.nombre_tercero, p.plan_descripcion,
                  p.nombre_cuota_moderadora, p.nombre_copago, a.tipo_id_paciente,a.paciente_id,
                  a.tipo_afiliado_id, a.plan_id, a.rango, a.semanas_cotizadas, a.servicio, d.fecha_nacimiento,
									d.sexo_id, x.fecha_resgistro as fechasolicitud,
									x.fecha_resgistro as fechaingreso, NULL, x.hc_os_solicitud_id
                  from os_ordenes_servicios as a
                  left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                  left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
                  planes as p, hc_os_solicitudes_manuales as x,
                  terceros as w,empresas as t,
                  tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  tipos_afiliado as b, pacientes as d
                  where a.orden_servicio_id=".$orden."
                  and n.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id=b.tipo_afiliado_id
                  and e.hc_os_solicitud_id=x.hc_os_solicitud_id
                  and a.plan_id=p.plan_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id
                  and q.evolucion_id is null
                  and a.tipo_id_paciente=d.tipo_id_paciente
                  and a.paciente_id=d.paciente_id
                  and x.empresa_id=t.empresa_id
                  and t.tipo_pais_id=u.tipo_pais_id and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                )  ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
              $var=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }


	/**
	*
	*/
	function ReporteOrdenServicio($orden)
	{
			list($dbconn) = GetDBconn();
			//								and q.evolucion_id is not null
			$query = "select a.*,
								e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
								e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
								f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
								h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
								j.sw_estado, a.observacion, l.ubicacion as ubidpto, l.telefono as teldpto,
								z.tarifario_id, z.cargo, y.requisitos,
								x.nombre_tercero as nompro, x.direccion  as dirpro, x.telefono as telpro,
								s.descripcion as descar, q.evolucion_id, a.semanas_cotizadas, a.plan_id,
								a.servicio, a.rango, n.observacion as obsapoyo, m.observacion as obsinter,
								m.especialidad,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
								from os_ordenes_servicios as a
								join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
								join cups as f  on(e.cargo_cups=f.cargo)
								left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
								left join departamentos as l on(g.departamento=l.departamento)
								left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
								left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
								left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
								left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
								left join hc_os_solicitudes_interconsultas as m on(m.hc_os_solicitud_id=q.hc_os_solicitud_id)
								left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)
								left join especialidades as AB on(AB.especialidad=m.especialidad )
								left join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
								join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
								left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
								left join hc_apoyod_requisitos as y on(f.cargo=y.cargo),
								autorizaciones as j
								where a.orden_servicio_id=".$orden."
								and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
								and j.sw_estado=0
								order by e.numero_orden_id";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
					while (!$result->EOF)
					{
						$var[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
					}
			}
			$result->Close();
			return $var;
	}

//--------------------------REPORTE SOLICITUDES--------------------------------
   /**
    *
    */
    function EncabezadoReporteSolicitud($solicitud,$tipo,$paciente)
    {
        list($dbconn) = GetDBconn();
        $query = "( select b.tipo_afiliado_nombre, s.rango,
                  d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
                  n.nombre as usuario, n.usuario_id, u.departamento, v.municipio, t.tipo_id_tercero, t.id,
                  t.razon_social, t.direccion, t.telefonos,w.nombre_tercero,
                  p.plan_descripcion,d.tipo_id_paciente,d.paciente_id
                  from hc_os_solicitudes as q, planes as p
                  left join terceros as w on(w.tipo_id_tercero=p.tipo_tercero_id and w.tercero_id=p.tercero_id),
                  hc_evoluciones as r left join cuentas as s on(s.ingreso=r.ingreso)
                  left join empresas as t on(s.empresa_id=t.empresa_id),
                  tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  tipos_afiliado as b, pacientes as d
                  where q.hc_os_solicitud_id=".$solicitud."
                  and q.evolucion_id is not null
                  and n.usuario_id=".UserGetUID()."
                  and s.tipo_afiliado_id=b.tipo_afiliado_id
                  and q.plan_id=p.plan_id
                  and d.tipo_id_paciente='".$tipo."'
                  and d.paciente_id='".$paciente."'
                  and q.evolucion_id=r.evolucion_id
                  and t.tipo_pais_id=u.tipo_pais_id and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                )
                UNION
                (
                  select b.tipo_afiliado_nombre, x.rango, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
                  n.nombre as usuario, n.usuario_id, u.departamento, v.municipio, t.tipo_id_tercero,
                  t.id, t.razon_social, t.direccion, t.telefonos,w.nombre_tercero,
                  p.plan_descripcion,d.tipo_id_paciente,d.paciente_id
                  from hc_os_solicitudes as q, hc_os_solicitudes_manuales as x,
                  planes as p left join terceros as w on(w.tipo_id_tercero=p.tipo_tercero_id and w.tercero_id=p.tercero_id),
                  empresas as t, tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  pacientes as d, tipos_afiliado as b
                  where q.hc_os_solicitud_id=".$solicitud."
                  and q.evolucion_id is null
                  and x.tipo_afiliado_id=b.tipo_afiliado_id
                  and x.hc_os_solicitud_id=".$solicitud."
                  and n.usuario_id=".UserGetUID()." and q.plan_id=p.plan_id
                  and d.tipo_id_paciente='".$tipo."'
                  and d.paciente_id='".$paciente."'
                  and x.empresa_id=t.empresa_id and
                  t.tipo_pais_id=u.tipo_pais_id and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                )";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }
        else
        {
              $var=$result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }

		/**
		*
		*/
		function ReporteSolicitudNoAuto($solicitud)
		{
        list($dbconn) = GetDBconn();
				$query = "
									(   select a.evolucion_id,h.observaciones, a.hc_os_solicitud_id,
											a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id,
											p.sw_pos, k.tipo_id_paciente, k.paciente_id,
											k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
											a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id, a.sw_estado,
											l.servicio, p.descripcion, m.descripcion as desserv, g.descripcion as desos,
											i.fecha, l.descripcion as despto, q.nombre_tercero, a.cantidad
											from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
											planes as f, os_tipos_solicitudes as g, hc_evoluciones as i, pacientes as k,
											departamentos as l, servicios as m, cups as p, terceros as q, ingresos as r
											where a.hc_os_solicitud_id=".$solicitud."
											and a.hc_os_solicitud_id=e.hc_os_solicitud_id
											and (e.autorizacion_int=h.autorizacion or e.autorizacion_ext=h.autorizacion)
											and a.plan_id=f.plan_id
											and i.ingreso=r.ingreso and h.sw_estado=1
											and a.evolucion_id=i.evolucion_id
											and r.tipo_id_paciente=k.tipo_id_paciente
											and r.paciente_id=k.paciente_id
											and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null
											and i.departamento=l.departamento and l.servicio=m.servicio
											and p.cargo=a.cargo
											and f.tipo_tercero_id=q.tipo_id_tercero and f.tercero_id=q.tercero_id
									)
									UNION
									(
											select a.evolucion_id,h.observaciones, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
											p.nivel_autorizador_id, p.sw_pos, k.tipo_id_paciente, k.paciente_id,
											k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion,
											a.os_tipo_solicitud_id, a.sw_estado, b.servicio, p.descripcion,
											m.descripcion as desserv, g.descripcion as desos, b.fecha,
											NULL as despto, q.nombre_tercero, a.cantidad
											from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
											planes as f, os_tipos_solicitudes as g,
											pacientes as k, servicios as m, cups as p, terceros as q,
											hc_os_solicitudes_manuales as b
											where a.hc_os_solicitud_id=".$solicitud."
											and a.plan_id=f.plan_id
											and b.tipo_id_paciente=k.tipo_id_paciente
											and b.paciente_id=k.paciente_id
											and a.hc_os_solicitud_id=e.hc_os_solicitud_id and
											(e.autorizacion_int=h.autorizacion or e.autorizacion_ext=h.autorizacion) and
											a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and
											a.evolucion_id is null
											and a.hc_os_solicitud_id=b.hc_os_solicitud_id
											and a.plan_id=f.plan_id
											and h.sw_estado=1        and
											b.servicio=m.servicio and
											p.cargo=a.cargo and
											f.tipo_tercero_id=q.tipo_id_tercero and
											f.tercero_id=q.tercero_id
									)";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
							$var=$result->GetRowAssoc($ToUpper = false);
				}
				$result->Close();
				return $var;
		}
//------------------------------------------------------------------------------

	function Diagnostico($evolucion)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT tipo_diagnostico_id
								FROM hc_diagnosticos_ingreso
								WHERE evolucion_id=$evolucion ORDER BY sw_principal desc";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

			$i=0;
			while(!$result->EOF)
			{
					if($i==0)
					{  $var.=$result->fields[0];  $i++;}
					else
					{  $var.='  '.'-'.'  '.$result->fields[0];  }
					$result->MoveNext();
			}

			$result->Close();
			return $var;
	}

	
	function DiagnosticoSolicitud($solicitud)
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT diagnostico_id
								FROM  hc_os_solicitudes_diagnosticos
								WHERE hc_os_solicitud_id=$solicitud";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}			
			if(!$result->EOF)
			{
					while(!$result->EOF)
					{
							if($i==0)
							{  $var.=$result->fields[0];  $i++;}
							else
							{  $var.='  '.'-'.'  '.$result->fields[0];  }
							$result->MoveNext();
					}
					$result->Close();
					return $var;					
			}
			return false;
	}
		
	/**
	*
	*/
	function BuscarAutorizador($int,$ext)
	{
				if(empty($ext)){  $ext='NULL';  }
				list($dbconn) = GetDBconn();
				$query = "select b.nombre as autorizador
									from autorizaciones as a, system_usuarios as b
									where (a.autorizacion=$int OR a.autorizacion=$ext)
									and a.usuario_id=b.usuario_id";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
						$var=$result->fields[0];
				}
				$result->Close();
				return $var;
	}


     /*
     *	GetMedicamentosHospitalariosAmbulatorios()
     *	Consulta de formulacion de medicamentos recetados desde el departamento de 
     *	Hospitalizacion o Urgencias y que son ambulatorios.
     *
     *	Adaptacion: Tizziano Perea.
     */
     function GetMedicamentosHospitalariosAmbulatorios($ingreso,$sw_farmacia)
     {
          list($dbconn) = GetDBconn();
          if(empty($sw_farmacia))
          {
               $sql="SELECT a.evolucion_id, k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                         else 'NO POS' end as item, a.sw_paciente_no_pos, a.codigo_producto,
                         h.descripcion as producto, c.descripcion as principio_activo, m.nombre as via,
                         a.dosis, a.unidad_dosificacion, a.tipo_opcion_posologia_id, a.cantidad,
                         l.descripcion, l.descripcion as unidad, h.contenido_unidad_venta, a.observacion,
                         'M' AS tipo_solicitud, hc.usuario_id
     
               FROM hc_medicamentos_recetados_amb as a left join hc_vias_administracion as m
                    on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h,
                    medicamentos as k, unidades as l, hc_evoluciones as hc
     
               WHERE hc.ingreso = ".$ingreso."
               AND hc.evolucion_id = a.evolucion_id
               AND k.cod_principio_activo = c.cod_principio_activo
               AND h.codigo_producto = k.codigo_medicamento 
               AND a.codigo_producto = h.codigo_producto
               AND h.codigo_producto = a.codigo_producto 
               AND h.unidad_id = l.unidad_id
               AND hc.evolucion_id = a.evolucion_id
               ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
          }
          else
          {
               $sql="SELECT a.evolucion_id, k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                              else 'NO POS' end as item, a.sw_paciente_no_pos, a.codigo_producto,
                              h.descripcion as producto, c.descripcion as principio_activo, m.nombre as via,
                              a.dosis, a.unidad_dosificacion, a.tipo_opcion_posologia_id, a.cantidad,
                              l.descripcion, l.descripcion as unidad, h.contenido_unidad_venta, a.observacion,
                              'M' AS tipo_solicitud, hc.usuario_id
     
                    FROM hc_medicamentos_recetados_amb as a left join hc_vias_administracion as m
                         on (a.via_administracion_id = m.via_administracion_id),
                         inv_med_cod_principios_activos as c, inventarios_productos as h,
                         medicamentos as k, unidades as l, hc_evoluciones as hc
     
                    WHERE hc.ingreso = ".$ingreso."
                    AND hc.evolucion_id = a.evolucion_id
                    AND k.cod_principio_activo = c.cod_principio_activo
                    AND h.codigo_producto = k.codigo_medicamento 
                    AND a.codigo_producto = h.codigo_producto
                    AND h.codigo_producto = a.codigo_producto 
                    AND h.unidad_id = l.unidad_id
                    AND k.sw_pos = 1
                    AND hc.evolucion_id = a.evolucion_id
                    ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
          }
          $result = $dbconn->Execute($sql);
          $i=0;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          return $var;
	}

	//fin dar
	//--------------------------------------------------------	
	function BuscarSolicitudesHospitalariasAmbulatorias($ingreso)
	{
          // Cambio Realizado por Tizziano Perea.
          list($dbconn) = GetDBconn();
     	$query = " SELECT   tabla.evolucion_id, tabla.ingreso,
                              tabla.cantidad, tabla.hc_os_solicitud_id, tabla.cargos,
                              tabla.descar, tabla.nivel_autorizador_id, tabla.sw_pos,
                              tabla.tipo_id_paciente, tabla.paciente_id, tabla.nombres,
                              tabla.plan_id, tabla.plan_descripcion, tabla.os_tipo_solicitud_id,
                              tabla.sw_estado, tabla.desserv, tabla.despto,
                              tabla.servicio, tabla.descripcion, tabla.desos,
                              tabla.fecha, tabla.profesional,
                              tabla.prestador, tabla.observaciones,
                              tabla.nivel, 
                              Q.departamento, tabla.sw_ambulatorio
                         
                         FROM
                         (
                              SELECT DISTINCT A.*,
                                   P.primer_nombre || ' ' || P.segundo_nombre || ' ' || P.primer_apellido || ' ' || P.segundo_apellido AS nombres,
                                   G.descripcion as desos,
                                   F.plan_descripcion, 
                                   NULL AS profesional,
                                   NULL AS prestador,
                                   NULL AS observaciones,
                                   D.nivel_autorizador_id as nivel,
                                   D.descripcion as descar,
                                   D.nivel_autorizador_id,
                                   D.sw_pos,
                                   D.descripcion,
                                   L.servicio,
                                   CASE A.sw_ambulatorio WHEN 1 THEN '' ELSE L.descripcion END AS despto,
                                   CASE A.sw_ambulatorio WHEN 1 THEN 'AMBULATORIO' ELSE M.descripcion END AS desserv
                              FROM (
                                   SELECT 
                                        A.cantidad,
                                        A.hc_os_solicitud_id,
                                        A.cargo as cargos,
                                        A.plan_id,
                                        A.os_tipo_solicitud_id,
                                        A.sw_estado,
                                        A.sw_ambulatorio,
                                        I.fecha,
                                        I.evolucion_id,
                                        I.ingreso,
                                        I.departamento AS dpto_evolucion,
                                        J.tipo_id_paciente,
                                        J.paciente_id
                                        
                                   FROM ingresos AS J,
                                        hc_evoluciones AS I,
                                        hc_os_solicitudes AS A
                                        
                                   WHERE J.ingreso = '".$ingreso."'
                                   AND   J.ingreso = I.ingreso
                                   AND   A.evolucion_id = I.evolucion_id
                                   AND   A.evolucion_id IS NOT NULL
                                   AND   A.sw_ambulatorio='1' 
                                   AND   A.sw_estado= '1' 
                                   ) AS A,
                                   pacientes AS P,
                                   os_tipos_solicitudes AS G,
                                   planes AS F,
                                   cups AS D,
                                   departamentos as L,
                                   servicios AS M
                                   
                              WHERE P.paciente_id = A.paciente_id
                              AND   P.tipo_id_paciente = A.tipo_id_paciente
                              AND   A.os_tipo_solicitud_id = G.os_tipo_solicitud_id
                              AND   A.plan_id = F.plan_id
                              AND   D.cargo = A.cargos
                              AND   L.departamento = A.dpto_evolucion
                              AND   L.servicio = M.servicio
                         ) AS tabla
                         LEFT JOIN departamentos_cargos AS Q ON (Q.cargo = tabla.cargos)
                         ORDER BY tabla.evolucion_id ASC;";
     
     	/*$query = "SELECT distinct i.evolucion_id, i.ingreso, a.cantidad,a.hc_os_solicitud_id,    a.cargo as cargos,
                    p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente,
                    j.paciente_id,
                    k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                    a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
                    a.sw_estado,
                    l.servicio, p.descripcion,
                    
                    case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
                    --m.descripcion as desserv, 
                    case a.sw_ambulatorio when 1 then '' else l.descripcion end as despto,
                    --l.descripcion as despto,
                                                                                                                   
                    q.departamento,									
                    g.descripcion as desos,i.fecha,
                    NULL as profesional,NULL as prestador,NULL as observaciones,
                    p.nivel_autorizador_id as nivel,
                    r.tipo_id_tercero, a.sw_ambulatorio
                    FROM hc_os_solicitudes as a, planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
                    ingresos as j,
                    pacientes as k, departamentos as l, servicios as m,
                    cups as p left join departamentos_cargos as q on(p.cargo=q.cargo)
                    left join terceros_proveedores_cargos as r on(p.cargo=r.cargo)
                    WHERE j.ingreso=$ingreso and a.sw_ambulatorio='1'
                    and p.cargo=a.cargo  and a.plan_id=f.plan_id
                    and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null and a.evolucion_id=i.evolucion_id
                    and i.ingreso=j.ingreso  and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
                    and a.sw_estado=1
                    and i.departamento=l.departamento and l.servicio=m.servicio";*/
                    
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al traer las solicitudes";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while (!$result->EOF)
          {
               $var[]=$result->GetRowAssoc($ToUpper = false);
               $result->MoveNext();
          }
          $result->Close();
          return $var;
	}

	
	function BuscarOrdenesHospitalariasAmbulatorias($ingreso)
	{
				list($dbconn) = GetDBconn();
				$query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
									case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
									e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
									g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro,
									p.plan_descripcion, a.autorizacion_int, a.autorizacion_ext, z.sw_ambulatorio
									from os_ordenes_servicios as a
									join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
									left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
									left join departamentos as l on(g.departamento=l.departamento)
									left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
									left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
									tipos_afiliado as b, servicios as c, pacientes as d, cups as f,
									planes as p, hc_os_solicitudes as z, hc_evoluciones as x
									where x.ingreso=$ingreso and x.evolucion_id=z.evolucion_id
									and z.os_tipo_solicitud_id<>'CIT'
									and z.hc_os_solicitud_id=e.hc_os_solicitud_id
									and z.sw_ambulatorio='1'
									and a.orden_servicio_id=e.orden_servicio_id
									and a.tipo_afiliado_id=b.tipo_afiliado_id
									and a.servicio=c.servicio
									and a.plan_id=p.plan_id
									and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id
									and e.cargo_cups=f.cargo
									and e.sw_estado in('1','2')
									order by a.orden_servicio_id desc";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal2 autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
				}

				while(!$result->EOF)
				{
								$vars2[]=$result->GetRowAssoc($ToUpper = false);
								$result->MoveNext();
				}
				$result->Close();
				return $vars2;
	}

	
	//MAuroB
		function BuscarSolicitudesPaciente($pac_id,$pac_tipo_id,$depto='')
	{
				$filtro_depto = '';
				if(!empty($depto)){
					$filtro_depto = " AND b.departamento='".$depto."'";
				}

				list($dbconn) = GetDBconn();
			$query = "
											SELECT
													a.cantidad,
													a.hc_os_solicitud_id,
													a.cargo as cargos,
													p.primer_nombre||' '||p.segundo_nombre||' '||p.primer_apellido||' '||p.segundo_apellido as nombres,
													p.paciente_id,
													p.tipo_id_paciente,
													a.plan_id,
													f.plan_descripcion,
													a.os_tipo_solicitud_id,
													g.descripcion as desos,
													a.fecha,
													a.profesional,
													a.prestador,
													a.observaciones,
													c.descripcion,
													c.nivel_autorizador_id,
													c.sw_pos,
													a.sw_estado,
													case a.sw_ambulatorio when 1 then '3' else a.servicio end as servicio,
													case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
													case a.sw_ambulatorio when 1 then '' else a.despto end as despto
											
											FROM
													(
															(
																	SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, i.departamento,
																	l.servicio, l.descripcion as despto, i.fecha, NULL as profesional, NULL as prestador, NULL as observaciones, i.evolucion_id,
																	a.sw_estado
											
																	FROM hc_os_solicitudes a,
																	hc_evoluciones as i,
																	ingresos as j,
																	departamentos as l
											
																	WHERE a.evolucion_id IS NOT NULL
																	AND a.sw_estado='1'
																	AND i.evolucion_id = a.evolucion_id
																	AND j.ingreso=i.ingreso
																	AND j.tipo_id_paciente='$pac_tipo_id'
																	AND j.paciente_id = '$pac_id'
																	AND l.departamento=i.departamento
															)
															UNION
															(
																	SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, j.departamento,
																	j.servicio, l.descripcion as despto, j.fecha, j.profesional, j.prestador, j.observaciones, NULL as evolucion_id,
																	a.sw_estado
																	FROM hc_os_solicitudes a,
																	hc_os_solicitudes_manuales as j LEFT JOIN departamentos as l ON (l.departamento=j.departamento)
																	WHERE a.evolucion_id IS NULL
																	AND a.sw_estado='1'
																	AND j.hc_os_solicitud_id = a.hc_os_solicitud_id
																	AND j.paciente_id = '$pac_id'
																	AND j.tipo_id_paciente='$pac_tipo_id'
															)
													) AS a,
													planes as f,
													os_tipos_solicitudes as g,
													pacientes as p,
													servicios as m,
													departamentos_cargos as b,
													cups as c
											
											WHERE
													f.plan_id = a.plan_id
													AND g.os_tipo_solicitud_id = a.os_tipo_solicitud_id
													AND p.paciente_id= '$pac_id'
													AND p.tipo_id_paciente='$pac_tipo_id'
													AND m.servicio=a.servicio
													AND a.cargo=b.cargo
													--AND b.departamento='010601'
													$filtro_depto
													AND a.cargo=c.cargo
											
											ORDER BY a.plan_id, a.servicio, a.os_tipo_solicitud_id
";

				
/*				 $query = "select distinct i.evolucion_id, i.ingreso, a.cantidad,a.hc_os_solicitud_id,    a.cargo as cargos,
									p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente,
									j.paciente_id,
									k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
									a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
									a.sw_estado,

									case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
									--m.descripcion as desserv, 
									case a.sw_ambulatorio when 1 then '' else l.descripcion end as despto,
									--l.descripcion as despto,																			
									
									l.servicio, p.descripcion,
									g.descripcion as desos,i.fecha,
									NULL as profesional,NULL as prestador,NULL as observaciones,
									p.nivel_autorizador_id as nivel,
									q.departamento, r.tipo_id_tercero, a.sw_ambulatorio
									
									from hc_os_solicitudes as a, 
									planes as f, 
									os_tipos_solicitudes as g, 
									hc_evoluciones as i,
									ingresos as j,
									pacientes as k, 
									departamentos as l, 
									servicios as m,
									$filtro_depto
									left join terceros_proveedores_cargos as r on(p.cargo=r.cargo)
									
									where j.tipo_id_paciente= '".$pac_tipo_id."' 
									and j.paciente_id= '".$pac_id."'
									and p.cargo=a.cargo  
									and a.plan_id=f.plan_id
									and a.sw_ambulatorio='0'
									and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null and a.evolucion_id=i.evolucion_id
									and i.ingreso=j.ingreso  and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
									and a.sw_estado=1
									and i.departamento=l.departamento and l.servicio=m.servicio";*/
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al traer las solicitudes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				while (!$result->EOF)
				{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
				return $var;
	}

	//finMauroB	
//----------------------------------------------------------

?>
