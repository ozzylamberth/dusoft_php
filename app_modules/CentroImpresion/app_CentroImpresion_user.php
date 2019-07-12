<?php

/**
* app_CentroImpresion_user.php  17/01/2003
*
* Proposito del Archivo: Manejo logico de las autorizaciones.
* Copyright (C) 2003 InterSoftware Ltda.
* Email: intersof@telesat.com.co
* @autor: Darling Liliana Dorado y Jairo Duvan Diaz
* @version SIIS v 0.1
* @package SIIS
*/


/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_CentroImpresion_user extends classModulo
{
    var $limit;
    var $conteo;

    function app_CentroImpresion_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }


    /**
    *
    */
    function main()
    {
        list($dbconn) = GetDBconn();
				$query = "select count(*) from userpermisos_centro_impresion as p 
									where p.usuario_id=".UserGetUID()."";
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				if($result->fields[0]==0)
				{
						$Mensaje = 'USTED NO TIENE PERMISOS PARA ESTE MODULO.';
						$accion=ModuloGetURL('system','Menu','user','main');
						if(!$this-> FormaMensaje($Mensaje,'CENTRO IMPRESION',$accion,'')){
								return false;
						}
						return true;
				}

        $this->FormaMetodoBuscar();
        return true;
    }


    /**
    * Busca los diferentes tipos de identificacion de los paciente
    * @access public
    * @return array
    */
    function tipo_id_paciente()
  {
                list($dbconn) = GetDBconn();
                $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                else{
                    if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
                        return false;
                    }
                    while (!$result->EOF) {
                        $vars[$result->fields[0]]=$result->fields[1];
                        $result->MoveNext();
                    }
                }
                $result->Close();
          return $vars;
    }


  /**
  *
  * @access public
  * @return boolean
  */
  function Buscar()
  {
            list($dbconn) = GetDBconn();

            $tipo_documento=$_REQUEST['TipoDocumento'];
            $documento=$_REQUEST['Documento'];
            $nombres = strtolower($_REQUEST['Nombres']);
            $orden=$_REQUEST['Orden'];

            $filtroTipoDocumento = '';
            $filtroDocumento='';
            $filtroNombres='';
            $filtroOrden='';
            $filtroSolicitud='';

            if(!empty($_REQUEST['Solicitud']))
            {   $filtroSolicitud=" AND d.hc_os_solicitud_id = '".$_REQUEST['Solicitud']."'";   }


            if(!empty($tipo_documento))
            {   $filtroTipoDocumento=" and x.tipo_id_paciente = '$tipo_documento'";   }

            if ($documento != '')
            {   $filtroDocumento =" AND x.paciente_id LIKE '$documento%'";   }

            if ($nombres != '')
            {
                $a=explode(' ',$nombres);
                foreach($a as $k=>$v)
                {
                    if(!empty($v))
                        {
                            $filtroNombres.=" and (upper(x.primer_nombre||' '||x.segundo_nombre||' '||
                                                                x.primer_apellido||' '||x.segundo_apellido) like '%".strtoupper($v)."%')";
                        }
                }
            }

            if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

            if(empty($_REQUEST['paso']))
            {
              $query = "select distinct a.* from (( select distinct a.tipo_id_paciente, a.paciente_id,
                      x.primer_nombre||' '||x.segundo_nombre||' '||x.primer_apellido||' '||x.segundo_apellido as nombre
                      from  hc_os_solicitudes as d
                      left join os_maestro as b  on(d.hc_os_solicitud_id=b.hc_os_solicitud_id and b.sw_estado in (1,2,3,7))
                      left join os_ordenes_servicios as a on (b.orden_servicio_id=a.orden_servicio_id),
                      hc_evoluciones as e, pacientes as x
                      where d.evolucion_id is not null and e.evolucion_id=d.evolucion_id
                      and a.tipo_id_paciente=x.tipo_id_paciente and a.paciente_id=x.paciente_id
                      $filtroTipoDocumento $filtroDocumento $filtroNombres
                      $filtroSolicitud
                      $filtroOrdentud
                      $filtroOrden
                    )
                    UNION
                    ( select distinct c.tipo_id_paciente, c.paciente_id,
                      x.primer_nombre||' '||x.segundo_nombre||' '||x.primer_apellido||' '||x.segundo_apellido as nombre
                      from hc_os_solicitudes as d
                      left join os_maestro as b on(b.hc_os_solicitud_id=d.hc_os_solicitud_id and b.sw_estado in (1,2,3,7))
                      left join os_ordenes_servicios as a on (b.orden_servicio_id=a.orden_servicio_id),
                      hc_os_solicitudes_manuales as c, pacientes as x
                      where d.evolucion_id is null and d.hc_os_solicitud_id=c.hc_os_solicitud_id
                      and x.tipo_id_paciente=c.tipo_id_paciente
                      and x.paciente_id =c.paciente_id
                      $filtroTipoDocumento $filtroDocumento $filtroNombres
                      $filtroSolicitud
                      $filtroOrden
                    ) ) as a";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Tabal autorizaiones";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                if(!$result->EOF)
                {
                    $_SESSION['SPY2']=$result->RecordCount();
                }
                $result->Close();
            }

            $query = "select distinct a.* from (( select distinct a.tipo_id_paciente, a.paciente_id,
                      x.primer_nombre||' '||x.segundo_nombre||' '||x.primer_apellido||' '||x.segundo_apellido as nombre
                      from  hc_os_solicitudes as d
                      left join os_maestro as b  on(d.hc_os_solicitud_id=b.hc_os_solicitud_id and b.sw_estado in (1,2,3,7))
                      left join os_ordenes_servicios as a on (b.orden_servicio_id=a.orden_servicio_id),
                      hc_evoluciones as e, pacientes as x
                      where d.evolucion_id is not null and e.evolucion_id=d.evolucion_id
                      and a.tipo_id_paciente=x.tipo_id_paciente and a.paciente_id=x.paciente_id
                      $filtroTipoDocumento $filtroDocumento $filtroNombres
                      $filtroSolicitud
                      $filtroOrdentud
                      $filtroOrden
                    )
                    UNION
                    ( select distinct c.tipo_id_paciente, c.paciente_id,
                      x.primer_nombre||' '||x.segundo_nombre||' '||x.primer_apellido||' '||x.segundo_apellido as nombre
                      from hc_os_solicitudes as d
                      left join os_maestro as b on(b.hc_os_solicitud_id=d.hc_os_solicitud_id and b.sw_estado in (1,2,3,7))
                      left join os_ordenes_servicios as a on (b.orden_servicio_id=a.orden_servicio_id),
                      hc_os_solicitudes_manuales as c, pacientes as x
                      where d.evolucion_id is null and d.hc_os_solicitud_id=c.hc_os_solicitud_id
                      and x.tipo_id_paciente=c.tipo_id_paciente
                      and x.paciente_id =c.paciente_id
                      $filtroTipoDocumento $filtroDocumento $filtroNombres
                      $filtroSolicitud
                      $filtroOrden
                    ) ) as a  LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Tabal autorizaiones";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }


            while(!$result->EOF)
            {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $this->FormaMetodoBuscar($var);
            return true;
    }

    /**
    *
    */
    function Detalle()
    {
          $tipo=$_REQUEST['tipoid'];
          $paciente=$_REQUEST['paciente'];
          unset($_SESSION['CENTRO']['IMPRESION']['ARREGLOS']);
          unset($_SESSION['CENTRO']['IMPRESION']['PACIENTE']);

          $_SESSION['CENTRO']['IMPRESION']['PACIENTE']['tipo_id_paciente']=$tipo;
          $_SESSION['CENTRO']['IMPRESION']['PACIENTE']['paciente_id']=$paciente;
          $_SESSION['CENTRO']['IMPRESION']['PACIENTE']['nombre']=$_REQUEST['nombre'];

          list($dbconn) = GetDBconn();
          //TRAE LAS ORDENES EN ESTADO 1,2,3,7
          $query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                    case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=7 then 'TRANSCRIPCION' else 'PARA ATENCION' end as estado,e.sw_estado,
                    e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
                    g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
                    a.plan_id, z.plan_descripcion, w.fecha as fechaevo, v.fecha as fechamanu
                    from os_ordenes_servicios as a
                    join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                    left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                    left join departamentos as l on(g.departamento=l.departamento)
                    left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                    left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                    left join userpermisos_centro_impresion as p on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id)
                    left join hc_os_solicitudes as x on(e.hc_os_solicitud_id=x.hc_os_solicitud_id)
                    left join hc_evoluciones as w on(x.evolucion_id=w.evolucion_id)
                    left join hc_os_solicitudes_manuales as v on(e.hc_os_solicitud_id=v.hc_os_solicitud_id)
                    join cups as f on(e.cargo_cups=f.cargo),
                    tipos_afiliado as b, servicios as c, pacientes as d, autorizaciones as j, system_usuarios as k,
                    planes as z
                    where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
                    and a.tipo_id_paciente='".$tipo."'
                    and z.plan_id=a.plan_id
                    and a.paciente_id='".$paciente."' and a.tipo_id_paciente=d.tipo_id_paciente
                    and a.paciente_id=d.paciente_id
                    and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                    and j.usuario_id=k.usuario_id and e.sw_estado in(1,2,3,7)
                    order by a.plan_id";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabla autorizaciones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }
          if (!$result->EOF)
          {
                  while(!$result->EOF)
                  {
                          $var[]=$result->GetRowAssoc($ToUpper = false);
                          $result->MoveNext();
                  }
          }
          $result->Close();
          $_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['ORDENES']=$var;

          //SOLICITUDES
         $query="(select i.ingreso, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente, j.paciente_id, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,l.servicio, m.descripcion as desserv, g.descripcion as desos, i.fecha,
                        l.descripcion as despto, p.nivel_autorizador_id as nivel, a.cantidad,
                        x.dias_tramite_os as trap, z.dias_tramite_os as tra
                        from hc_os_solicitudes as a
                        left join os_tipos_periodos_planes as x on(x.cargo=a.cargo and a.plan_id=x.plan_id)
                        left join os_tipos_periodos_tramites as z on(z.cargo=a.cargo),
                        planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
                        ingresos as j, pacientes as k, departamentos as l, servicios as m,
                        cups as p
                        where
                        p.cargo=a.cargo
                        and a.sw_estado=1
                        and a.plan_id=f.plan_id
                        and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                        and a.evolucion_id is not null
                        and a.evolucion_id=i.evolucion_id
                        and i.ingreso=j.ingreso
                        and j.tipo_id_paciente=k.tipo_id_paciente
                        and j.tipo_id_paciente='$tipo'
                        and j.paciente_id='$paciente'
                        and j.paciente_id=k.paciente_id
                        and  i.departamento=l.departamento
                        and l.servicio=m.servicio
                        order by m.servicio, j.tipo_id_paciente, j.paciente_id, a.os_tipo_solicitud_id
                      )
                      UNION
                      (select null, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente, b.paciente_id, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
                        b.servicio, m.descripcion as desserv, g.descripcion as desos, b.fecha,
                        null, p.nivel_autorizador_id as nivel, a.cantidad,
                        x.dias_tramite_os as trap, z.dias_tramite_os as tra
                        from hc_os_solicitudes as a
                        left join os_tipos_periodos_planes as x on(x.cargo=a.cargo and a.plan_id=x.plan_id)
                        left join os_tipos_periodos_tramites as z on(z.cargo=a.cargo),
                        planes as f, os_tipos_solicitudes as g,
                        pacientes as k, servicios as m,
                        cups as p, hc_os_solicitudes_manuales as b
                        where
                        p.cargo=a.cargo
                        and a.sw_estado=1
                        and a.plan_id=f.plan_id
                        and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                        and a.evolucion_id is null
                        and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                        and b.tipo_id_paciente='$tipo'
                        and b.paciente_id='$paciente'
                        and k.tipo_id_paciente='$tipo'
                        and k.paciente_id='$paciente'
                        and b.servicio=m.servicio
                        order by m.servicio, a.os_tipo_solicitud_id
                      )  ";
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
                      $var2[]=$result->GetRowAssoc($ToUpper = false);
                      $result->MoveNext();
              }
          }
          $result->Close();
          $_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['SOLICITUDES']=$var2;
          //SOLICITUDES NO AUTORIZADAS
          $query = " (
                  select h.observaciones, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
                  p.nivel_autorizador_id, p.sw_pos, k.tipo_id_paciente, k.paciente_id,
                  k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion,
                  a.os_tipo_solicitud_id, a.sw_estado, l.servicio, p.descripcion,
                  m.descripcion as desserv, g.descripcion as desos, i.fecha,
                  l.descripcion as despto, q.nombre_tercero, a.cantidad, NULL as profesional, a.evolucion_id
                  from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
                  planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
                  pacientes as k, departamentos as l, servicios as m, cups as p, terceros as q,
                  ingresos as r
                  where k.tipo_id_paciente='".$tipo."' and
                  k.paciente_id='".$paciente."'
                  and r.tipo_id_paciente='".$tipo."' and
                  r.paciente_id='".$paciente."'
                  and r.ingreso=i.ingreso
                  and h.sw_estado=1
                  and a.plan_id=f.plan_id
                  and a.hc_os_solicitud_id=e.hc_os_solicitud_id and
                  (e.autorizacion_int=h.autorizacion or e.autorizacion_ext=h.autorizacion) and
                  a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and
                  a.evolucion_id is not null and
                  a.evolucion_id=i.evolucion_id and
                  i.departamento=l.departamento and
                  l.servicio=m.servicio and
                  p.cargo=a.cargo and
                  f.tipo_tercero_id=q.tipo_id_tercero and
                  f.tercero_id=q.tercero_id
              )
              UNION
              (
                  select h.observaciones, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
                  p.nivel_autorizador_id, p.sw_pos, k.tipo_id_paciente, k.paciente_id,
                  k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion,
                  a.os_tipo_solicitud_id, a.sw_estado, b.servicio, p.descripcion,
                  m.descripcion as desserv, g.descripcion as desos, b.fecha,
                  NULL as despto, q.nombre_tercero, a.cantidad, b.profesional, NULL
                  from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
                  planes as f, os_tipos_solicitudes as g,
                  pacientes as k, servicios as m, cups as p, terceros as q,
                  hc_os_solicitudes_manuales as b
                  where   b.tipo_id_paciente='".$tipo."' and
                  b.paciente_id='".$paciente."' and
                  k.tipo_id_paciente='".$tipo."' and
                  k.paciente_id='".$paciente."' and
                  a.hc_os_solicitud_id=e.hc_os_solicitud_id and
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
          $results=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al traer informacion de las solicitudes";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }
          if (!$results->EOF)
          {
                  while(!$results->EOF)
                  {
                          $var3[]=$results->GetRowAssoc($ToUpper = false);
                          $results->MoveNext();
                  }
          }
          $results->Close();
          $_SESSION['CENTRO']['IMPRESION']['ARREGLOS']['SOLICITUDESNOAUTO']=$var3;

          $this->FormaDetalle();
          return true;
    }


    /**
    *
    */
    /*function DatosTramite($id)
    {
          list($dbconn) = GetDBconn();
          $query = "select a.hc_os_solicitud_id,b.*, c.nombre as usuario
                    from autorizaciones_os_solicitudes_requerimientos_det as a,
                    autorizaciones_os_solicitudes_requerimientos as b, system_usuarios as c
                    where a.hc_os_solicitud_id=$id
                    and a.autorizaciones_os_solicitudes_id=b.autorizaciones_os_solicitudes_id
                    and b.usuario_id=c.usuario_id";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error select ";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }

          $cont=$result->RecordCount();
          if(!$result->EOF)
          {
                while(!$result->EOF)
                {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
          }

          $result->Close();
          return $var;
    }*/
//--------------------------REPORTE--------------------------------------------------

    /**
    *
    */
    function EncabezadoReporte($orden,$tipo,$paciente,$afiliado,$plan)
    {
        list($dbconn) = GetDBconn();
        $query = "( select b.tipo_afiliado_nombre, s.rango,
                  d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
                  n.nombre as usuario, n.usuario_id, u.departamento, v.municipio, t.tipo_id_tercero, t.id,
                  t.razon_social, t.direccion, t.telefonos,w.nombre_tercero, p.plan_descripcion,
                  p.nombre_cuota_moderadora, p.nombre_copago, a.tipo_id_paciente,a.paciente_id,
                  a.rango,a.semanas_cotizadas,a.tipo_afiliado_id,a.servicio, a.plan_id
                  from os_ordenes_servicios as a
                  left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                  left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
                  planes as p
                  left join terceros as w on(w.tipo_id_tercero=p.tipo_tercero_id and w.tercero_id=p.tercero_id),
                  hc_evoluciones as r left join cuentas as s on(s.ingreso=r.ingreso)
                  left join empresas as t on(s.empresa_id=t.empresa_id),
                  tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  tipos_afiliado as b, pacientes as d
                  where a.orden_servicio_id=".$orden."
                  and n.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id='".$afiliado."'
                  and a.tipo_afiliado_id=b.tipo_afiliado_id
                  and a.plan_id='".$plan."'
                  and a.plan_id=p.plan_id
                  and q.evolucion_id is not null
                  and a.tipo_id_paciente='".$tipo."'
                  and a.paciente_id='".$paciente."'
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
                  a.rango,a.semanas_cotizadas,a.tipo_afiliado_id,a.servicio, a.plan_id
                  from os_ordenes_servicios as a
                  left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                  left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
                  planes as p, hc_os_solicitudes_manuales as x,
                  terceros as w,empresas as t,
                  tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  tipos_afiliado as b, pacientes as d
                  where a.orden_servicio_id=".$orden."
                  and n.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id='".$afiliado."'
                  and a.tipo_afiliado_id=b.tipo_afiliado_id
                  and a.plan_id='".$plan."'
                  and e.hc_os_solicitud_id=x.hc_os_solicitud_id
                  and a.plan_id=p.plan_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id
                  and q.evolucion_id is null
                  and a.tipo_id_paciente='".$tipo."'
                  and a.paciente_id='".$paciente."'
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
 * funcion que revisa si ya se ha realizado una impresion de la orden de servicio
 *
 */
    function RevisarRecepcionOrden($orden_servicio)
    {
      list($dbconn) = GetDBconn();
      $query="SELECT a.*, b.nombre as nom
              FROM recepciones_ordenes as a, system_usuarios as b
              WHERE  a.orden_servicio_id='$orden_servicio'
              and a.usuario_id=b.usuario_id";

          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error buscar en recepciones_ordenes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
          }

          while(!$resulta->EOF)
          {
              $var[]=$resulta->GetRowAssoc($ToUpper = false);
              $resulta->MoveNext();
          }
          return $var;
    }

    /**
    *
    */
    function ReporteOrdenServicio()
    {
        if(empty($_REQUEST['sw_personal']) and empty($_REQUEST['nom']))
        {
            $_REQUEST['v']='LLENE UNA DE LAS DOS CASILLAS';
            $this->FormaDetalle($_REQUEST['tipoid'],$_REQUEST['paciente']);
            return true;
        }
  
        $x=date("Y-m-d h:i:s");
  
        list($dbconn) = GetDBconn();
  
        if(empty($_REQUEST['sw_personal']))
        {$sw_per='0';}else{$sw_per='1';}
  
        if(!empty($_REQUEST['sw_personal']) AND !empty($_REQUEST['nom']) )
        {  $_REQUEST['nom']=''; }

        $query="INSERT INTO recepciones_ordenes
                  (orden_servicio_id,
                  nombre,
                  usuario_id,
                  fecha_registro,
                  sw_personalmente
              )VALUES(".$_REQUEST['orden'].",'".$_REQUEST['nom']."',".UserGetUID().",'".$x."','".$sw_per."');";

        $resulta=$dbconn->execute($query);
        if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al insertar en recepciones_ordenes";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
        }
        //supuestamente aqui se hace la actualizacion:
        //la fecha de vencimietno se actualiza con el dia de la entrega de la impresion de la os igual que refrendar
        //$this->ActualizarFechas($_REQUEST['plan'],$_REQUEST['orden']);

        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        if(empty($_SESSION['CENTROAUTORIZACION']['TODOS']))
        {  $empresa=$_SESSION['CENTROAUTORIZACION']['EMPRESA'];  }
        else
        {  $empresa=$_SESSION['CENTROAUTORIZACION']['TODO']['EMPRESA'];  }

        $var[0]=$this->EncabezadoReporte($_REQUEST['orden'],$_REQUEST['tipoid'],$_REQUEST['paciente'],$_REQUEST['afiliado'],$_REQUEST['plan']);

        list($dbconn) = GetDBconn();
         $query = "(select a.*,
                  e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                  e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                  f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                  h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
                  z.tarifario_id, z.cargo, y.requisitos,
                  x.nombre_tercero as nompro, x.direccion  as dirpro, x.telefono as telpro,
                  s.descripcion as descar,q.evolucion_id,NULL as profesional,
									n.observacion as obsapoyo, m.observacion as obsinter, m.especialidad
									,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
                  from os_ordenes_servicios as a
                  left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
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
                  left join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                  left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                  left join cups as f on(e.cargo_cups=f.cargo)
                  left join hc_apoyod_requisitos as y on(f.cargo=y.cargo)
                  where a.orden_servicio_id=".$_REQUEST['orden']."
                  and a.tipo_afiliado_id='".$_REQUEST['afiliado']."'
                  and a.plan_id='".$_REQUEST['plan']."'
                  and q.evolucion_id is not null
                  and a.tipo_id_paciente='".$_REQUEST['tipoid']."'
                  and a.paciente_id='".$_REQUEST['paciente']."'
                  order by e.numero_orden_id
                  )
                  UNION
                  (select a.*,
                  e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                  e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                  f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                  h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
                  z.tarifario_id, z.cargo, y.requisitos,
                  x.nombre_tercero as nompro, x.direccion  as dirpro, x.telefono as telpro,
                  s.descripcion as descar, NULL,m.profesional,
									n.observacion as obsapoyo, k.observacion as obsinter, k.especialidad 
									,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
                  from os_ordenes_servicios as a
                  left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                  left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                  left join departamentos as l on(g.departamento=l.departamento)
                  left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                  left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                  left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
									left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
									left join hc_os_solicitudes_interconsultas as k on(k.hc_os_solicitud_id=q.hc_os_solicitud_id)
									left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)
									left join especialidades as AB on(AB.especialidad=k.especialidad )									
                  left join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
                  left join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                  left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                  left join cups as f on(e.cargo_cups=f.cargo)
                  left join hc_apoyod_requisitos as y on(f.cargo=y.cargo),
                  hc_os_solicitudes_manuales as m
                  where a.orden_servicio_id=".$_REQUEST['orden']."
                  and a.tipo_afiliado_id='".$_REQUEST['afiliado']."'
                  and q.evolucion_id is null
                  and a.plan_id='".$_REQUEST['plan']."'
                  and a.tipo_id_paciente='".$_REQUEST['tipoid']."'
                  and a.paciente_id='".$_REQUEST['paciente']."'
                  and q.hc_os_solicitud_id=m.hc_os_solicitud_id
                  order by e.numero_orden_id
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
            while (!$result->EOF)
            {
              $var[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
            }
        }
        $result->Close();

        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        
        $this->ActualizarFechas($_REQUEST['plan'],$_REQUEST['orden']);
        $reporte=$classReport->PrintReport('pos','app','CentroAutorizacion','ordenservicio',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
             $this->error = $classReport->GetError();
             $this->mensajeDeError = $classReport->MensajeDeError();
             unset($classReport);
             return false;
        }

        $resultado=$classReport->GetExecResultado();
        unset($classReport);


        if(!empty($resultado[codigo])){
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }

        if(!empty($_REQUEST['regreso']))//cuando es la impresion desde la autorizacion
        {  $this->$_REQUEST['regreso']();  }
        if(!empty($_REQUEST['regreso2']))//cuando es la impresion es desde listadoo
        {  $this->$_REQUEST['regreso2']($_REQUEST['tipoid'],$_REQUEST['paciente']);  }
        return true;
    }


    /**
    *
    */
    function DatosTramite($id)
    {
          list($dbconn) = GetDBconn();
          $query = "select a.hc_os_solicitud_id,b.*, c.nombre as usuario
                    from autorizaciones_os_solicitudes_requerimientos_det as a,
                    autorizaciones_os_solicitudes_requerimientos as b, system_usuarios as c
                    where a.hc_os_solicitud_id=$id
                    and a.autorizaciones_os_solicitudes_id=b.autorizaciones_os_solicitudes_id
                    and b.usuario_id=c.usuario_id";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error select ";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  return false;
          }

          $cont=$result->RecordCount();
          if(!$result->EOF)
          {
                while(!$result->EOF)
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
    function ReporteSolicitudesNoAuto()
    {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $var[0]=$this->EncabezadoReporteSolicitud($_REQUEST['solicitud'],$_REQUEST['tipoid'],$_REQUEST['paciente']);
        $var[1]=$_REQUEST['datos'];

        $reporte=$classReport->PrintReport('pos','app','CentroImpresion','solicitudesnoautorizadas',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
             $this->error = $classReport->GetError();
             $this->mensajeDeError = $classReport->MensajeDeError();
             unset($classReport);
             return false;
        }

        $resultado=$classReport->GetExecResultado();
        unset($classReport);


        if(!empty($resultado[codigo])){
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }

//  FormaDetalle
//  FormaDetalleSolicitud
//DetalleSolicitud();

//DetalleSolicituTodos();
        $this->$_REQUEST['regreso']($_REQUEST['tipoid'],$_REQUEST['paciente']);
        return true;
    }

      /**
    *
    */
    function Reportesolicitudes()
    {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $var[0]=$this->EncabezadoReporteSolicitud($_REQUEST['solicitud'],$_REQUEST['tipoid'],$_REQUEST['paciente']);

        for($i=0; $i<sizeof($_SESSION['CENTRAL']['ARR_SOLICITUDES']);$i++)
        {

            $var[$i+1]=$_SESSION['CENTRAL']['ARR_SOLICITUDES'][$i];

        }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport('pos','app','CentroImpresion','solicitudes',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
             $this->error = $classReport->GetError();
             $this->mensajeDeError = $classReport->MensajeDeError();
             unset($classReport);
             return false;
        }

        $resultado=$classReport->GetExecResultado();
        unset($classReport);


        if(!empty($resultado[codigo])){
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }

        $this->$_REQUEST['regreso']($_REQUEST['tipoid'],$_REQUEST['paciente']);
        return true;
    }

//---------------------ACTUALIZACION FECHAS----------------------------------------
    
    /**
    *
    */
    function  ActualizarFechas($plan,$orden)
    {    
        list($dbconn) = GetDBconn();  
        $dbconn->BeginTrans();  
        $query = "select b.numero_orden_id,b.cargo_cups
                  from os_ordenes_servicios as a, os_maestro as b
                  where a.orden_servicio_id=$orden and a.orden_servicio_id=b.orden_servicio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error select ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        if(!$result->EOF)
        {
              while(!$result->EOF)
              {
                  $var[]=$result->GetRowAssoc($ToUpper = false);
                  $result->MoveNext();
              }
        }
        $result->Close();
        
        for($i=0; $i<sizeof($var); $i++)
        {
            $numero=$var[$i][numero_orden_id];
            $cargo=$var[$i][cargo_cups];
            
            $query = "select * from os_tipos_periodos_planes
                                where plan_id=".$plan."
                                and cargo='$cargo'";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error os_tipos_periodos_planes";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {
                    $var=$result->GetRowAssoc($ToUpper = false);
                    //fecha vencimiento
                    $venc=date("Y-m-d H:i:s",mktime(date("H"),date("m"),0,$infoCadena[1],(date("d")+$var[dias_vigencia]),date("m")));
                    //fecha refrendar
                    $Fecha=$this->FechaStamp($venc);
                    $infoCadena = explode ('/',$Fecha);
                    $intervalo=$this->HoraStamp($venc);
                    $infoCadena1 = explode (':', $intervalo);
                    $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
            }
            else
            {
                    $query = "select * from os_tipos_periodos_tramites
                                        where cargo='$cargo'";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error os_tipos_periodos_tramites";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }
                    if(!$result->EOF)
                    {
                                $var=$result->GetRowAssoc($ToUpper = false);
                                //fecha vencimiento
                                $venc=date("Y-m-d H:i:s",mktime(date("H"),date("m"),0,$infoCadena[1],(date("d")+$var[dias_vigencia]),date("m")));
                                //fecha refrendar
                                $Fecha=$this->FechaStamp($venc);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($venc);
                                $infoCadena1 = explode (':', $intervalo);
                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                    }
                    else
                    {
                                $tramite=ModuloGetVar('app','CentroAutorizacion','dias_tramite_os');
                                $vigencia=ModuloGetVar('app','CentroAutorizacion','dias_vigencia');
                                $var=$result->GetRowAssoc($ToUpper = false);
                                //fecha vencimiento
                                $venc=date("Y-m-d H:i:s",mktime(date("H"),date("m"),0,$infoCadena[1],(date("d")+$var[dias_vigencia]),date("m")));
                                //fecha refrendar
                                $Fecha=$this->FechaStamp($venc);
                                $infoCadena = explode ('/',$Fecha);
                                $intervalo=$this->HoraStamp($venc);
                                $infoCadena1 = explode (':', $intervalo);
                                $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                    }
            }//fin else  
            
            $query = "UPDATE os_maestro SET fecha_vencimiento='$venc' ,fecha_refrendar='$refrendar'
                      WHERE numero_orden_id=$numero";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Erro UPDATE os_maestro";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }      
        }    
        
        return true;            
    }

//------------------------------------------------------------------------------------

}//fin clase user

?>

