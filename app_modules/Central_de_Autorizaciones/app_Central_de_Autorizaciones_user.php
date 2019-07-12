<?php

/**
 * $Id: app_Central_de_Autorizaciones_user.php,v 1.4 2009/12/01 13:43:06 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
class app_Central_de_Autorizaciones_user extends classModulo {

    var $limit;
    var $conteo; //para saber cuantos registros encontr�

    function app_Central_de_Autorizaciones_user() {
        $this->limit = GetLimitBrowser();
        return true;
    }

    /**
     * La funcion main es la principal y donde se llama FormaPrincipal
     * que muestra los diferentes tipos de busqueda de una cuenta para hospitalizaci�n.
     * @access public
     * @return boolean
     */
    function main() {
        if (!$this->BuscarPermisosUser()) {
            return false;
        }
        return true;
    }

    function BuscarPermisosUser() {
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $query = "SELECT c.departamento,c.descripcion as dpto, d.descripcion as
                    centro,e.empresa_id,e.razon_social as emp,d.centro_utilidad,
                    b.usuario_id, b.sw_farmacia
                  FROM userpermisos_central b, departamentos c, centros_utilidad d,empresas e
                  WHERE b.usuario_id=" . UserGetUID() . "
                    AND c.departamento=b.departamento
                    AND d.centro_utilidad=c.centro_utilidad
                    AND e.empresa_id=d.empresa_id
                    AND e.empresa_id=c.empresa_id
                  ORDER BY centro";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resulta = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error en la funcion BuscarPermisosUser()";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while ($data = $resulta->FetchRow()) {
            $laboratorio[$data['emp']][$data['centro']][$data['dpto']] = $data;
        }

        $url[0] = 'app';
        $url[1] = 'Central_de_Autorizaciones';
        $url[2] = 'user';
        $url[3] = 'MenuAtencion';
        $url[4] = 'centro';

        $arreglo[0] = 'EMPRESA';
        $arreglo[1] = 'CENTRO UTILIDAD';
        $arreglo[2] = 'CENTRAL DE AUTORIZACIONES';

        $this->salida.= gui_theme_menu_acceso('CENTRAL DE IMPRESI�N', $arreglo, $laboratorio, $url, ModuloGetUrl('system', 'Menu', 'user', 'main'));
        return true;
    }

    function MenuAtencion() {

        $_SESSION['CENTRO']['EMPRESA_ID'] = $_REQUEST['centro']['empresa_id'];
        $_SESSION['CENTRO']['CENTROUTILIDAD'] = $_REQUEST['centro']['centro_utilidad'];
        $_SESSION['CENTRO']['NOM_CENTRO'] = $_REQUEST['centro']['centro'];
        $_SESSION['CENTRO']['NOM_EMP'] = $_REQUEST['centro']['emp'];
        $_SESSION['CENTRO']['NOM_DPTO'] = $_REQUEST['centro']['dpto'];
        $_SESSION['CENTRO']['DPTO'] = $_REQUEST['centro']['departamento'];
        $_SESSION['CENTRO']['SW_FARMACIA'] = $_REQUEST['centro']['sw_farmacia'];

        $this->BuscarServiciosAmb();
        return true;
    }

    function BuscarServiciosAmb() {
        unset($_SESSION['CENTRAL']['TIPO_CONSULTA']);
        list($dbconn) = GetDBconn();
        $query = "SELECT a.descripcion,b.tipo_consulta_id
        FROM tipos_servicios_ambulatorios a,tipos_consulta b
        WHERE      b.departamento='" . $_SESSION['CENTRO']['DPTO'] . "'
        AND   a.tipo_servicio_amb_id=b.tipo_consulta_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las 0rdenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }


        $result->Close();

        if (empty($var)) {
            $this->FormaMetodoBuscar();
        } else {
            $this->FormaServicioAmb($var);
        }
        return true;
    }

    function TraerProfesionales() {
        list($dbconn) = GetDBconn();
        $query = "SELECT distinct a.nombre_tercero as nombre, a.descripcion, a.tipo_consultorio,
              a.profesional_id, a.tipo_id_profesional
              FROM (SELECT z.nombre_tercero, d.descripcion, c.tipo_consultorio, a.profesional_id,
              a.tipo_id_profesional, e.estado
              FROM agenda_turnos as a
              left join profesionales_estado as e on
              (a.profesional_id=e.tercero_id and a.tipo_id_profesional=e.tipo_id_tercero
              and e.empresa_id='" . $_SESSION['CENTRO']['EMPRESA_ID'] . "'
              and e.departamento='" . $_SESSION['CENTRO']['DPTO'] . "')
              left join consultorios as c on (a.consultorio_id=c.consultorio)
              left join tipos_consultorios as d on(c.tipo_consultorio=d.tipo_consultorio),
              profesionales as b, terceros as z
              where a.empresa_id='" . $_SESSION['CENTRO']['EMPRESA_ID'] . "'
              and a.tipo_consulta_id=" . $_SESSION['CENTRAL']['TIPO_CONSULTA'] . "
              and a.profesional_id=b.tercero_id
              and a.tipo_id_profesional=b.tipo_id_tercero
              and date(a.fecha_turno)=date(now())
              and b.tercero_id=z.tercero_id
              and b.tipo_id_tercero=z.tipo_id_tercero
              ) as a
              WHERE a.estado is null or a.estado=1 order by a.nombre_tercero;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las 0rdenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
    }

    function ListadoCitasAtender() {
        list($dbconn) = GetDBconn();
        $sql = "select a.fecha_turno || ' ' || b.hora AS fechacom, c.tipo_id_paciente, c.paciente_id, e.primer_nombre || ' ' || e.segundo_nombre || ' ' || e.primer_apellido || ' ' || e.segundo_apellido as nombre_completo, k.numerodecuenta,
		 i.evolucion_id, i.estado, j.plan_id
		 from agenda_turnos as a, agenda_citas as b join agenda_citas_asignadas as c on (b.agenda_cita_id=c.agenda_cita_id)
		 join pacientes as e on (c.tipo_id_paciente=e.tipo_id_paciente and c.paciente_id=e.paciente_id)
		 join os_cruce_citas as h on(c.agenda_cita_asignada_id=h.agenda_cita_asignada_id)
		 join os_maestro as k on(h.numero_orden_id=k.numero_orden_id)
		 join cuentas as j on(k.numerodecuenta=j.numerodecuenta)
		 join hc_evoluciones as i on (j.ingreso=i.ingreso)
		 where a.profesional_id='" . $_SESSION['CENTRAL']['PROFESIONAL']['profesional_id'] . "'
		 and a.tipo_id_profesional='" . $_SESSION['CENTRAL']['PROFESIONAL']['tipoid'] . "'
		 and a.empresa_id='" . $_SESSION['CENTRO']['EMPRESA_ID'] . "'
		 and a.tipo_consulta_id=" . $_SESSION['CENTRAL']['TIPO_CONSULTA'] . "
		 and date(a.fecha_turno)=date('" . date("Y-m-d") . "')
		 and a.agenda_turno_id=b.agenda_turno_id order by b.hora;";
        $result = $dbconn->Execute($sql);
        $i = 0;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        return $var;
    }

    function Revisarsolicitudes($evol) {
        list($dbconn) = GetDBconn();
        $query = "select revisar_solicitud_paciente($evol);";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al llamar la funcion revisar_solicitud_paciente()";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result->fields[0];
    }

    function RevisarsolicitudesAmb($ingreso) {
        list($dbconn) = GetDBconn();
        $query = "select revisar_solicitud_paciente_amb($ingreso);";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al llamar la funcion revisar_solicitud_paciente()";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result->fields[0];
    }

    /* function BuscarSolicitudes($evolucion)
      {
      list($dbconn) = GetDBconn();
      $query="select i.ingreso, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
      p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente, j.paciente_id,
      k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
      a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,l.servicio, m.descripcion as desserv,
      g.descripcion as desos, i.fecha, a.evolucion_id,
      l.descripcion as despto, p.nivel_autorizador_id as nivel, a.cantidad,
      x.dias_tramite_os as trap, z.dias_tramite_os as tra,
      y.tipo_afiliado_id, y.rango, y.semanas_cotizadas
      from hc_os_solicitudes as a
      left join os_tipos_periodos_planes as x on(x.cargo=a.cargo and a.plan_id=x.plan_id)
      left join os_tipos_periodos_tramites as z on(z.cargo=a.cargo),
      planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
      ingresos as j, pacientes as k, departamentos as l, servicios as m,
      cups as p, cuentas as y
      where a.evolucion_id=$evolucion
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
      order by m.servicio, j.tipo_id_paciente, j.paciente_id, a.os_tipo_solicitud_id";
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

      function BuscarOrdenesS($evolucion)
      {
      list($dbconn) = GetDBconn();
      $query = " select q.evolucion_id, a.*, b.tipo_afiliado_nombre, c.descripcion as desserv,
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
      and e.sw_estado in(1,2,3)
      order by a.orden_servicio_id,a.plan_id";
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
      } */

    function BuscarAutorizador($int, $ext) {
        list($dbconn) = GetDBconn();
        $query = "select b.nombre as autorizador
                from autorizaciones as a, system_usuarios as b
                where (a.autorizacion=$int OR a.autorizacion=$ext)
                and a.usuario_id=b.usuario_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            $var = $result->fields[0];
        }
        $result->Close();
        return $var;
    }

    function DatosPlan($plan) {
        list($dbconn) = GetDBconn();
        $query = "select a.plan_descripcion, b.nombre_tercero
								from planes as a, terceros as b
								WHERE a.plan_id=$plan and a.tercero_id=b.tercero_id
								and a.tipo_tercero_id=b.tipo_id_tercero";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al 2Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
    }

    function CambiarProveedor() {
        if ($_REQUEST['Combo'] == -1) {
            $this->frmError["combo"] = 1;
            $this->frmError["MensajeError"] = "Debe Elegir El Proveedor.";
            $this->FormaCambiarProveedor($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['numor'], $_REQUEST['cargo'], $_REQUEST['proveedor']);
            return true;
        }

        if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
            $empresa = $_SESSION['CENTROAUTORIZACION']['EMPRESA'];
        } else {
            $empresa = $_SESSION['CENTROAUTORIZACION']['TODOS']['EMPRESA'];
        }

        list($dbconn) = GetDBconn();

        //si el proveedor anterior era externo
        $dptoant = $terceroant = $tipoant = $plan = 'NULL';
        if ($_REQUEST['tipop'] == 'e') {
            $query = "SELECT tipo_id_tercero, tercero_id, plan_proveedor_id
								         FROM planes_proveedores
								  		   WHERE plan_proveedor_id=" . $_REQUEST['proveedor'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al 2Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $tipoant = "'" . $result->fields[0] . "'";
            $terceroant = "'" . $result->fields[1] . "'";
            $plan = "'" . $result->fields[2] . "'";
        } else {//el proveedor anterios era interno
            $dptoant = "'" . $_REQUEST['proveedor'] . "'";
        }

        $dbconn->BeginTrans();
        $query = "delete from os_internas where numero_orden_id=" . $_REQUEST['numor'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al 2Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $query = "delete from os_externas where numero_orden_id=" . $_REQUEST['numor'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al 2Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        //0 tipo_id_tercero o departamento(interna) 1 tercero_id o 'dpto'
        //2 plan_proveedor 3 empredsa
        $arr = explode(',', $_REQUEST['Combo']);
        //si es interna
        if ($arr[1] == 'dpto') {
            $query = "INSERT INTO os_internas
                                                            (numero_orden_id,
                                                            cargo,
                                                            departamento)
                                            VALUES(" . $_REQUEST['numor'] . ",'" . $_REQUEST['cargo'] . "','$arr[0]')";
        } else {
            $query = "INSERT INTO os_externas
                                                            (numero_orden_id,
                                                            empresa_id,
                                                            tipo_id_tercero,
                                                            tercero_id,
                                                            cargo,
                                                            plan_proveedor_id)
                                            VALUES(" . $_REQUEST['numor'] . ",'" . $arr[3] . "','$arr[1]','$arr[0]','" . $_REQUEST['cargo'] . "',$arr[2])";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        //para la auditoria
        if ($arr[1] == 'dpto') {
            $query = "INSERT INTO auditoria_cambio_proveedor(
																														numero_orden_id,
																														fecha_registro,
																														usuario_id,
																														tipo_id_tercero_ant,
																														tercero_id_ant,
																														plan_proveedor_id_ant,
																														departamento_ant,
                                                            departamento_act)
																	VALUES(" . $_REQUEST['numor'] . ",'now()'," . UserGetUID() . ",$tipoant,$terceroant," . $plan . ",$dptoant,'$arr[0]')";
        } else {
            $query = "INSERT INTO auditoria_cambio_proveedor(
																														numero_orden_id,
																														fecha_registro,
																														usuario_id,
																														tipo_id_tercero_ant,
																														tercero_id_ant,
																														plan_proveedor_id_ant,
																														departamento_ant,
                                                            tipo_id_tercero_act,
																														tercero_id_act,
																														plan_proveedor_id_act)
																	VALUES(" . $_REQUEST['numor'] . ",'now()'," . UserGetUID() . ",$tipoant,$terceroant," . $plan . ",$dptoant,'$arr[1]','$arr[0]',$arr[2])";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error auditoria";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $this->frmError["MensajeError"] = "Se Cambio el Proveedor.";
        $dbconn->CommitTrans();
        $this->ListadoPacientesEvolucionCerrada();
        return true;
    }

    function ComboProveedor($Cargo) {
        $x = " and a.empresa_id='" . $_SESSION['CENTRO']['EMPRESA_ID'] . "'";
        list($dbconn) = GetDBconn();
        /* $query = "select a.tipo_id_tercero, a.tercero_id, a.cargo,  c.plan_proveedor_id, c.empresa_id,
          c.plan_descripcion
          from terceros_proveedores_cargos as a, planes_proveedores as c
          where a.cargo='$Cargo'
          $x
          and c.tipo_id_tercero=a.tipo_id_tercero and c.tercero_id=a.tercero_id "; */

        $query = "select 	a.tipo_id_tercero, 
									a.tercero_id, 
									a.cargo,  
									c.plan_proveedor_id, 
									c.empresa_id,
									c.plan_descripcion,
									MIN (round((e.precio + (e.precio * f.porcentaje) / 100),0)) as valor_cargo
							from 	terceros_proveedores_cargos as a, 
									planes_proveedores as c,
									terceros_proveedores_servicios_salud as b,
									tarifarios_equivalencias d,
									tarifarios_detalle e,
									plan_tarifario_proveedores f
							where 	a.cargo='$Cargo'
							and 	a.empresa_id='" . $_SESSION['CENTRO']['EMPRESA_ID'] . "'
							and 	c.tipo_id_tercero=a.tipo_id_tercero 
							and 	c.tercero_id=a.tercero_id
							and 	b.empresa_id=a.empresa_id
							and 	b.tipo_id_tercero=a.tipo_id_tercero
							and 	b.tercero_id=a.tercero_id 
							and 	b.estado='1'
							and 	a.sw_estado='1'
							and     d.cargo_base = a.cargo
							and     e.cargo = d.cargo
							and     e.tarifario_id = d.tarifario_id
							and     f.tarifario_id = e.tarifario_id
							and     f.grupo_tarifario_id = e.grupo_tarifario_id
							and     f.subgrupo_tarifario_id = e.subgrupo_tarifario_id
							and     f.plan_proveedor_id = c.plan_proveedor_id
							GROUP BY 1,2,3,4,5,6
							ORDER BY valor_cargo";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$resulta->EOF) {
            $vars[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $resulta->Close();
        return $vars;
    }

    function ComboDepartamento($Cargo, $solicitud) {
        IncludeLib('malla_validadora');
        $x = " and b.empresa_id='" . $_SESSION['CENTRO']['EMPRESA_ID'] . "'";
        list($dbconn) = GetDBconn();
        $dat = '';
        $dat = DatosSolicitud($solicitud);
        $filtro = ModuloGetVar('app', 'CentroAutorizacion', 'filtro_os');

        /* $query = "    select a.departamento, a.cargo, b.descripcion
          from departamentos_cargos as a, departamentos as b
          where a.cargo='$Cargo'
          and b.departamento=a.departamento"; */

        if ($filtro == 'empresa') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo'
											and b.departamento=a.departamento $x";
        } elseif ($filtro == 'centro') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo' 
											and b.departamento=a.departamento $x
											and b.centro_utilidad='" . $dat[centro_utilidad] . "'";
        } elseif ($filtro == 'unidad') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo' 
											and b.departamento=a.departamento $x
											and b.unidad_funcional='" . $dat[unidad_funcional] . "'";
        } elseif ($filtro == 'departamento') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo' 
											and b.departamento=a.departamento $x
											and b.departamento='" . $dat[departamento] . "'";
        }

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$resulta->EOF) {
            $vars[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $resulta->Close();
        return $vars;
    }

    function BuscarSolicitud($numero_orden_id) {
        list($dbconn) = GetDBconn();
        $query = "select a.hc_os_solicitud_id
					  from os_maestro a
					  WHERE a.numero_orden_id = " . $numero_orden_id . "";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al 2Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
    }

    /**
     * La funcion tipo_id_paciente se encarga de obtener de la base de datos
     * los diferentes tipos de identificacion de los paciente.
     * @access public
     * @return array
     */
    function tipo_id_paciente() {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            if ($result->EOF) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'tipos_id_pacientes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $vars[$result->fields[0]] = $result->fields[1];
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    /**
     * Realiza la busqueda general de los pacientes que tienen ordenes de servicios pendientes
     * @access private
     * @return array
     */
    function BusquedaCompleta() {

        $NUM = $_REQUEST['Of'];
        if (!$NUM) {
            $NUM = '0';
        }
        $limit = $this->limit;
        list($dbconn) = GetDBconn();
        if (!empty($_SESSION['SPY'])) {
            $x = " LIMIT " . $this->limit . " OFFSET $NUM";
        } else {
            $x = '';
        }

        $sql = "select c.fecha_turno || ' ' || b.hora as fechacom, d.nombre, 
                            k.tipo_id_paciente, k.paciente_id, k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
                            h.evolucion_id, h.estado, i.plan_id
                            from agenda_citas_asignadas as a 
                            join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id) 
                            join os_maestro as f on (g.numero_orden_id=f.numero_orden_id) 
                            join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id) 
                            join cuentas as i on(f.numerodecuenta=i.numerodecuenta)
                            join hc_evoluciones as h on(i.ingreso=h.ingreso) 
                            join pacientes as k on(a.tipo_id_paciente=k.tipo_id_paciente and a.paciente_id=k.paciente_id), 
                            agenda_citas as b, agenda_turnos as c, profesionales as d
                            where a.agenda_cita_id=b.agenda_cita_id and date(c.fecha_turno)=date(now())  
                            and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id 
                            and c.tipo_id_profesional=d.tipo_id_tercero and c.tipo_consulta_id=1 
                            and a.sw_atencion!=1 
                            order by (c.fecha_turno || ' ' || b.hora); $x";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!empty($_SESSION['SPY'])) {
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        } else {
            $vars = $result->RecordCount();
            $_SESSION['SPY'] = $vars;
        }
        $result->Close();

        return $vars;
    }

    /**
     *
     */
    function BuscarOrdenes() {
        if ($_REQUEST['allmedicos'] == 'on') {
            $datos = $this->TraerProfesionales();
            $this->FormaMetodoBuscar($datos, '2');
            return true;
        }
        list($dbconn) = GetDBconn();
        $tipo_documento = $_REQUEST['TipoDocumento'];
        $documento = $_REQUEST['Documento'];
        $nombres = strtolower($_REQUEST['Nombres']);
        $fecha = $_REQUEST['Fecha'];

        if ($_REQUEST['Fecha'] == 'TODAS LAS FECHAS') {
            $fecha = '';
        }
        $filtroTipoDocumento = '';
        $filtroDocumento = '';
        $filtroNombres = '';
        $filtroFecha = '';

        if (!empty($tipo_documento)) {
            $filtroTipoDocumento = " AND a.tipo_id_paciente = '$tipo_documento'";
        }

        if ($documento != '') {
            $filtroDocumento = " AND a.paciente_id LIKE '$documento%'";
        }

        if ($nombres != '') {
            $a = explode(' ', $nombres);
            foreach ($a as $k => $v) {
                if (!empty($v)) {
                    $filtroNombres.=" and (upper(k.primer_nombre||' '||k.segundo_nombre||' '||
                                                                k.primer_apellido||' '||k.segundo_apellido) like '%" . strtoupper($v) . "%')";
                }
            }
        }

        //es consulta
        if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
            if ($fecha != '') {
                $filtroFecha = "AND date(c.fecha_turno) = date('$fecha')";
            }
        } else {
            if ($fecha != '') {
                $filtroFecha = "AND date(c.fecha_egreso) = date('$fecha')";
            }
        }

        if (empty($_REQUEST['Of'])) {
            $_REQUEST['Of'] = 0;
        }

        if (empty($_REQUEST['paso'])) {
            list($dbconn) = GetDBconn();
            //es consulta
            // $dbconn->debug=true;
            if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
                $sql = "select c.fecha_turno || ' ' || b.hora as fechacom, z.nombre_tercero as nombre, k.tipo_id_paciente, k.paciente_id,
                            k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
                            h.evolucion_id, h.estado, i.plan_id
                        from agenda_citas_asignadas as a
                            join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
                            join os_maestro as f on (g.numero_orden_id=f.numero_orden_id)
                            join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id)
                            join cuentas as i on(f.numerodecuenta=i.numerodecuenta)
                            join hc_evoluciones as h on(i.ingreso=h.ingreso)
                            join pacientes as k on(a.tipo_id_paciente=k.tipo_id_paciente and a.paciente_id=k.paciente_id),
                            agenda_citas as b, agenda_turnos as c, profesionales as d, terceros as z
                        where a.agenda_cita_id=b.agenda_cita_id $filtroTipoDocumento $filtroDocumento
                            $filtroFecha $filtroNombres and b.agenda_turno_id=c.agenda_turno_id
                            and c.profesional_id=d.tercero_id and c.tipo_id_profesional=d.tipo_id_tercero
                            and d.tipo_id_tercero=z.tipo_id_tercero and d.tercero_id=z.tercero_id
                            and a.sw_atencion!='1'";
            } else {
                $sql = "(   SELECT DISTINCT  a.fecha_registro as fechacom, k.tipo_id_paciente, k.paciente_id,
                                k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
                                a.ingreso, i.plan_id
                                --b.estado, z.nombre_tercero as nombre, k.tipo_id_paciente, k.paciente_id,
                            FROM ingresos as a, pacientes as k,
                                -- hc_evoluciones as b, profesionales_usuarios as c, terceros as z,
                                cuentas as i, pacientes_urgencias as e
                                --, hc_medicamentos_recetados_hosp as f
                            WHERE a.departamento='" . $_SESSION['CENTRO']['DPTO'] . "'
                                $filtroTipoDocumento $filtroDocumento $filtroNombres
                                AND a.ingreso=e.ingreso AND e.sw_estado='4'
                                AND a.ingreso=i.ingreso
                                AND a.tipo_id_paciente=k.tipo_id_paciente
                                AND a.paciente_id=k.paciente_id
                                --AND a.ingreso=b.ingreso
                                --AND b.usuario_id=c.usuario_id
                                --AND c.tipo_tercero_id=z.tipo_id_tercero and c.tercero_id=z.tercero_id
                                --AND f.evolucion_id=b.evolucion_id
                                --AND f.sw_estado = '1' and f.sw_ambulatorio = '1'
                        )
                        UNION
                        (   SELECT DISTINCT  a.fecha_registro as fechacom, k.tipo_id_paciente, k.paciente_id,
                                k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
                                a.ingreso, i.plan_id
                                --b.estado, z.nombre_tercero as nombre, 

                            FROM egresos_departamento as c, ingresos_departamento as l,
                                ingresos as a, pacientes as k, cuentas as i
                                -- hc_evoluciones as b,profesionales_usuarios as j, terceros as z

                            WHERE l.departamento='" . $_SESSION['CENTRO']['DPTO'] . "'
                                and l.ingreso_dpto_id=c.ingreso_dpto_id and c.estado='2'
                                $filtroFecha and l.ingreso=a.ingreso
                                AND a.ingreso=i.ingreso
                                $filtroTipoDocumento $filtroDocumento $filtroNombres
                                AND a.tipo_id_paciente=k.tipo_id_paciente
                                AND a.paciente_id=k.paciente_id
                                --AND c.evolucion_id=b.evolucion_id
                                --AND b.usuario_id=j.usuario_id
                                --AND j.tipo_tercero_id=z.tipo_id_tercero and j.tercero_id=z.tercero_id
                        );";
            }
            $result = $dbconn->Execute($sql);
            $i = 0;
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $_SESSION['SPY2'] = $result->RecordCount();
            }
            $result->Close();
        }

        //es consulta
        if (!empty($_SESSION['CENTRAL']['TIPO_CONSULTA'])) {
            $query = "select c.fecha_turno || ' ' || b.hora as fechacom, z.nombre_tercero as nombre,
													k.tipo_id_paciente, k.paciente_id,
													k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
													h.evolucion_id, h.estado, h.ingreso, i.plan_id
													from agenda_citas_asignadas as a
													join os_cruce_citas as g on (a.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
													join os_maestro as f on (g.numero_orden_id=f.numero_orden_id)
													join os_ordenes_servicios as j on(f.orden_servicio_id=j.orden_servicio_id)
													join cuentas as i on(f.numerodecuenta=i.numerodecuenta)
													join hc_evoluciones as h on(i.ingreso=h.ingreso)
													join pacientes as k on(a.tipo_id_paciente=k.tipo_id_paciente and a.paciente_id=k.paciente_id),
													agenda_citas as b, agenda_turnos as c, profesionales as d, terceros as z
													where a.agenda_cita_id=b.agenda_cita_id $filtroTipoDocumento $filtroDocumento
													$filtroFecha $filtroNombres and b.agenda_turno_id=c.agenda_turno_id and c.profesional_id=d.tercero_id
													and c.tipo_id_profesional=d.tipo_id_tercero
													and d.tipo_id_tercero=z.tipo_id_tercero and d.tercero_id=z.tercero_id
													and    a.sw_atencion!='1'
													order by (c.fecha_turno || ' ' || b.hora)
													LIMIT " . $this->limit . " OFFSET " . $_REQUEST['Of'] . ";";
        } else {
            $query = "select * from(( SELECT DISTINCT a.fecha_registro as fechacom, k.tipo_id_paciente, k.paciente_id,
													k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
													a.ingreso,  i.plan_id
													--b.evolucion_id, b.estado, z.nombre_tercero as nombre,
													FROM ingresos as a, pacientes as k, 
													cuentas as i, pacientes_urgencias as e
													--profesionales_usuarios as c, terceros as z,
													--, hc_medicamentos_recetados_hosp as f,hc_evoluciones as b,
													WHERE a.departamento='" . $_SESSION['CENTRO']['DPTO'] . "'
													$filtroTipoDocumento $filtroDocumento $filtroNombres
													AND a.ingreso=e.ingreso AND e.sw_estado='4'
													AND a.ingreso=i.ingreso
													AND a.tipo_id_paciente=k.tipo_id_paciente
													AND a.paciente_id=k.paciente_id
													--AND a.ingreso=b.ingreso
													--AND b.usuario_id=c.usuario_id
													--AND c.tipo_tercero_id=z.tipo_id_tercero and c.tercero_id=z.tercero_id
													--AND f.evolucion_id=b.evolucion_id
													--AND f.sw_estado = '1' and f.sw_ambulatorio = '1'
													order by a.fecha_registro
												)
												UNION
												(	SELECT DISTINCT  a.fecha_registro as fechacom,  k.tipo_id_paciente, k.paciente_id,
													k.primer_nombre || ' ' || k.segundo_nombre || ' ' || k.primer_apellido || ' ' || k.segundo_apellido as nombre_paciente,
													a.ingreso, i.plan_id

													FROM egresos_departamento as c, ingresos_departamento as l,
													ingresos as a, pacientes as k,
													cuentas as i
													-- b.estado,, b.evolucion_id z.nombre_tercero as nombre,hc_evoluciones as b,profesionales_usuarios as j, terceros as z

													WHERE l.departamento='" . $_SESSION['CENTRO']['DPTO'] . "'
													and l.ingreso_dpto_id=c.ingreso_dpto_id and c.estado='2'
													$filtroFecha and l.ingreso=a.ingreso
													AND a.ingreso=i.ingreso
													$filtroTipoDocumento $filtroDocumento $filtroNombres
													AND a.tipo_id_paciente=k.tipo_id_paciente
													AND a.paciente_id=k.paciente_id
													--AND c.evolucion_id=b.evolucion_id
													--AND b.usuario_id=j.usuario_id
													--AND j.tipo_tercero_id=z.tipo_id_tercero and j.tercero_id=z.tercero_id
												)) as a
												LIMIT " . $this->limit . " OFFSET " . $_REQUEST['Of'] . ";";
        }

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al buscar";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $this->FormaMetodoBuscar($var, '1');
        return true;
    }

//------------------------------REPORTES---------------------------------------------

    /**
     *
     */
    function EncabezadoReporte() {
        unset($_SESSION['CENTRAL']['DATOS']);
        list($dbconn) = GetDBconn();
        $query = "select a.fecha, b.tipo_id_paciente, b.paciente_id, b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre,
                  t.tipo_id_tercero, t.id, t.razon_social, t.direccion, t.telefonos, u.departamento,
                  v.municipio, p.plan_descripcion, p.nombre_cuota_moderadora, p.nombre_copago,
                  w.nombre_tercero, d.tipo_afiliado_nombre, c.rango, e.descripcion,e.servicio,
                  f.nombre as usuario, f.usuario_id, a.evolucion_id,c.plan_id,d.tipo_afiliado_id
                  from hc_evoluciones as a, pacientes as b, cuentas as c,
                  empresas as t,   tipo_dptos as u, tipo_mpios as v, planes as p, terceros as w,
                  tipos_afiliado as d, departamentos as e, system_usuarios as f
                  where a.evolucion_id=" . $_SESSION['CENTRAL']['PACIENTE']['evolucion_id'] . "
                  and f.usuario_id=" . UserGetUID() . "
                  and b.tipo_id_paciente='" . $_SESSION['CENTRAL']['PACIENTE']['tipo_id'] . "'
                  and c.plan_id=p.plan_id
                  and b.paciente_id='" . $_SESSION['CENTRAL']['PACIENTE']['paciente_id'] . "'
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
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            $var = $result->GetRowAssoc($ToUpper = false);
        }
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function ReporteOrdenServicio() {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $var[0] = $_SESSION['CENTRAL']['DATOS'];
        list($dbconn) = GetDBconn();
        $query = "select a.*,
								e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
								e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
								f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
								h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
								j.sw_estado, a.observacion,
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
								where a.orden_servicio_id=" . $_REQUEST['orden'] . "
								and a.tipo_afiliado_id='" . $_REQUEST['afiliado'] . "'
								and a.plan_id='" . $_REQUEST['plan'] . "'
								and a.tipo_id_paciente='" . $_REQUEST['tipoid'] . "'
								and a.paciente_id='" . $_REQUEST['paciente'] . "'
								and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
								and j.sw_estado=0
								and q.evolucion_id=" . $_REQUEST['evolucion'] . "
								order by e.numero_orden_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();

        $classReport = new reports;

        if ($_REQUEST['pos'] == 1) {
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport('pos', 'app', 'CentroAutorizacion', 'ordenservicio', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
            unset($classReport);


            if (!empty($resultado[codigo])) {
                "El PrintReport retorno : " . $resultado[codigo] . "<br>";
            }
            $this->ListadoPacientesEvolucionCerrada();
            return true;
        } else {
            if ($_REQUEST['parametro_retorno'] == '1') {
                IncludeLib("reportes/ordenservicio");
                GenerarOrden($var);
                if (is_array($var)) {
                    $RUTA = $_ROOT . "cache/ordenservicio" . $var['orden'] . ".pdf";
                    $DIR = "printer.php?ruta=$RUTA";
                    $RUTA1 = GetBaseURL() . $DIR;
                    $mostrar = "\n<script language='javascript'>\n";
                    $mostrar.="var rem=\"\";\n";
                    $mostrar.="  function abreVentana(){\n";
                    $mostrar.="    var nombre=\"\"\n";
                    $mostrar.="    var url2=\"\"\n";
                    $mostrar.="    var str=\"\"\n";
                    $mostrar.="    var width=\"400\"\n";
                    $mostrar.="    var height=\"300\"\n";
                    $mostrar.="    var nombre=\"REPORTE\";\n";
                    $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                    $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                    $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                    $mostrar.="    var url2 ='$RUTA1';\n";
                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                    $mostrar.="</script>\n";
                    $this->salida.="$mostrar";
                    $this->salida.="<BODY onload=abreVentana();>";
                }
                $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
            } else {
                IncludeLib("reportes/ordenservicio");
                $vector['orden'] = $_REQUEST['orden'];
                GenerarOrden($vector);
                $this->ListadoPacientesEvolucionCerrada($vector, 3);
                return true;
            }
        }
    }

    /**
     *
     */
    function Reportesolicitudes() {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $var[0] = $_SESSION['CENTRAL']['DATOS'];
        for ($i = 0; $i < sizeof($_SESSION['CENTRAL']['ARR_SOLICITUDES']); $i++) {

            $var[$i + 1] = $_SESSION['CENTRAL']['ARR_SOLICITUDES'][$i];
        }
        $classReport = new reports;

        if ($_REQUEST['pos'] == 1) {
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport('pos', 'app', 'Central_de_Autorizaciones', 'solicitudes', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
            unset($classReport);


            if (!empty($resultado[codigo])) {
                "El PrintReport retorno : " . $resultado[codigo] . "<br>";
            }
            $this->ListadoPacientesEvolucionCerrada();
            return true;
        } else {
            if ($_REQUEST['parametro_retorno'] == '1') {
                IncludeLib("reportes/solicitudes");
                GenerarSolicitud($var);
                if (is_array($var)) {
                    $RUTA = $_ROOT . "cache/solicitudes" . UserGetUID() . ".pdf";
                    $DIR = "printer.php?ruta=$RUTA";
                    $RUTA1 = GetBaseURL() . $DIR;
                    $mostrar = "\n<script language='javascript'>\n";
                    $mostrar.="var rem=\"\";\n";
                    $mostrar.="  function abreVentana(){\n";
                    $mostrar.="    var nombre=\"\"\n";
                    $mostrar.="    var url2=\"\"\n";
                    $mostrar.="    var width=\"400\"\n";
                    $mostrar.="    var height=\"300\"\n";
                    $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                    $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                    $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                    $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                    $mostrar.="    var url2 ='$RUTA1';\n";
                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                    $mostrar.="</script>\n";
                    $this->salida.="$mostrar";
                    $this->salida.="<BODY onload=abreVentana();>";
                }
                $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
            } else {
                IncludeLib("reportes/solicitudes");
                $vector['evolucion'] = $_SESSION['CENTRAL']['PACIENTE']['evolucion_id'];
                $vector['ingreso'] = '';
                $vector['TipoDocumento'] = $_SESSION['CENTRAL']['PACIENTE']['tipo_id'];
                $vector['Documento'] = $_SESSION['CENTRAL']['PACIENTE']['paciente_id'];
                $vector['Nombres'] = $_SESSION['CENTRAL']['PACIENTE']['nom'];
                GenerarSolicitud($vector);
                $this->ListadoPacientesEvolucionCerrada($vector, 2);
                return true;
            }
        }
    }

    /*
     * 	ReporteFormulaMedica()
     * 	Adaptacion Tizziano Perea
     */

    function ReporteFormulaMedica() {



        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $criterio == '';
        $uso_controlado = 0;
        if (($_REQUEST['sw_paciente_no_pos'] === '0') OR ($_REQUEST['sw_paciente_no_pos'] == 1)) {
            $criterio = "AND k.sw_pos = '" . $_REQUEST['sw_pos'] . "' AND a.sw_paciente_no_pos = '" . $_REQUEST['sw_paciente_no_pos'] . "'";
        } elseif ($_REQUEST['sw_pos'] == '1') {
            $criterio = "AND k.sw_pos = '" . $_REQUEST['sw_pos'] . "'";
        }
        if ($criterio == '' AND $_REQUEST['sw_uso_controlado'] == '1') {
            $criterio = "AND k.sw_uso_controlado = '" . $_REQUEST['sw_uso_controlado'] . "'";
            $uso_controlado = 1;
        }


        //cargando criterios cuando sea invocado desde otro lado.
        if ($_SESSION['CENTRAL']['PACIENTE']['paciente_id'] == '') {
            $criterio_paciente = $_REQUEST['paciente_id'];
        } else {
            $criterio_paciente = $_SESSION['CENTRAL']['PACIENTE']['paciente_id'];
        }

        if ($_SESSION['CENTRAL']['PACIENTE']['tipo_id'] == '') {
            $criterio_tipo_id = $_REQUEST['tipo_id_paciente'];
        } else {
            $criterio_tipo_id = $_SESSION['CENTRAL']['PACIENTE']['tipo_id'];
        }

        if ($_SESSION['CENTRAL']['PACIENTE']['evolucion_id'] == '') {
            $criterio_evolucion_id = $_REQUEST['evolucion_id'];
        } else {
            $criterio_evolucion_id = $_SESSION['CENTRAL']['PACIENTE']['evolucion_id'];

            if (!empty($_REQUEST['evolucion_id'])) {
                $criterio_evolucion_id = $_REQUEST['evolucion_id'];
            }
        }

        if ($_REQUEST['modulo_invoca'] != 'Admisiones') {
            $criterio_ID = "a.evolucion_id = " . $criterio_evolucion_id . "";
        } else {
            $criterio_ID = "n.ingreso = " . $_REQUEST['ingreso'] . " and n.evolucion_id = a.evolucion_id";
        }
        //fin de criterios

        if ($_REQUEST['modulo_invoca'] == 'Admisiones' OR $_REQUEST['modulo_invoca'] == 'impresionhc') {
            //Medicamentos Parametrizables
            if ($_REQUEST['rango'] == 'uso_controlado') {
                $parametros = "AND k.sw_uso_controlado = '1' AND a.codigo_producto = '" . $_REQUEST['codigo_producto'] . "'";
                $uso_controlado = '1';
            }

            /* elseif($_REQUEST['rango'] == 'no_pos')
              {
              $parametros = "AND k.sw_pos = '0' AND a.codigo_producto = '".$_REQUEST['codigo_producto']."'";
              }else
              {
              $parametros = "AND k.sw_uso_controlado = '0' AND k.sw_pos = '1'";
              }
             */

            /* Es lo mismo que lo anterior sin el     k.sw_pos  por que en este caso el  k.sw_pos no se va a tener encuenta */ elseif ($_REQUEST['rango'] == 'no_pos') {
                $parametros = " AND a.codigo_producto = '" . $_REQUEST['codigo_producto'] . "'";
            } else {
                $parametros = "AND k.sw_uso_controlado = '0'";
            }
        }
        //Medicamentos Parametrizables
        list($dbconn) = GetDBconn();
        //$dbconn->debug = true;
        $query = "SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||
                         w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
                         w.tipo_id_paciente, 
                         w.paciente_id, 
                         w.sexo_id, 
                         w.fecha_nacimiento,
                         x.historia_numero, 
                         x.historia_prefijo, 
                         n.fecha_cierre, 
                         st.municipio,
                         z.detalle as atencion, 
                         cd.nombre as empleador,
                         n.fecha, 
                         w.residencia_direccion, 
                         w.residencia_telefono,
                         v.tipo_afiliado_id, 
                         t.plan_id, 
                         sw_tipo_plan, 
                         s.rango,
                         v.tipo_afiliado_nombre, 
                         p.nombre_tercero,	
                         u.nombre_tercero as cliente,
                         r.descripcion as tipo_profesional, 
                         p.tipo_id_tercero as tipo_id_medico,
                         p.tercero_id as	medico_id, 
                         q.tarjeta_profesional,
                         q.firma,						 
                         t.plan_descripcion,
                         a.evolucion_id, 
                         case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
                         a.sw_paciente_no_pos, 
                         a.codigo_producto,  
                         h.descripcion as producto,
                         c.descripcion as principio_activo, 
                         m.nombre as via, a.dosis,
                         a.unidad_dosificacion, 
                         a.tipo_opcion_posologia_id, 
                         CASE WHEN  a.cantidadperiocidad  IS NOT NULL THEN  a.cantidadperiocidad 
                           ELSE a.cantidad END AS cantidad, 
                         l.descripcion,	
                         h.contenido_unidad_venta,	
                         a.observacion,
						 a.dias_tratamiento,
                         k.concentracion_forma_farmacologica as concentracion,
                         forma.descripcion AS  forma
                         
               
                  FROM    hc_medicamentos_recetados_amb as a
                          JOIN hc_evoluciones as n 
                          on (a.evolucion_id= n.evolucion_id)
                          LEFT JOIN hc_vias_administracion as m 
                          on (a.via_administracion_id = m.via_administracion_id)
                          LEFT JOIN hc_atencion y 
                          on (n.evolucion_id= y.evolucion_id) 
                          LEFT JOIN hc_tipos_atencion z 
                          on (y.tipo_atencion_id = z.tipo_atencion_id)
                          LEFT JOIN ingresos_empleadores ab 
                          on (n.ingreso = ab.ingreso) 
                          LEFT JOIN empleadores cd 
                          on (ab.empleador_id = cd.empleador_id and
                              ab.tipo_id_empleador = cd.tipo_id_empleador)
                          LEFT JOIN	profesionales_usuarios as o 
                          on (n.usuario_id = o.usuario_id) 
                          LEFT JOIN terceros as p	
                          on (o.tipo_tercero_id = p.tipo_id_tercero AND
                              o.tercero_id = p.tercero_id) 
                          LEFT JOIN	profesionales as q 
                          on (o.tipo_tercero_id = q.tipo_id_tercero AND 
                              o.tercero_id = q.tercero_id)
                          LEFT JOIN tipos_profesionales as r 
                          on (q.tipo_profesional = r.tipo_profesional)
                          LEFT JOIN cuentas as s 
                          on (n.numerodecuenta = s.numerodecuenta) 
                          LEFT JOIN planes as t	
                          on (s.plan_id = t.plan_id) 
                          LEFT JOIN terceros as u 
                          on (t.tipo_tercero_id = u.tipo_id_tercero AND 
                              t.tercero_id	= u.tercero_id)
                          LEFT JOIN tipos_afiliado as v 
                          on (s.tipo_afiliado_id = v.tipo_afiliado_id)
                          JOIN pacientes as w 
                          on (w.paciente_id= '" . $criterio_paciente . "'
                              and w.tipo_id_paciente = '" . $criterio_tipo_id . "')
     
                         join historias_clinicas x on ((w.paciente_id= x.paciente_id
                         and w.tipo_id_paciente = x.tipo_id_paciente)),
                         inv_med_cod_principios_activos as c, 
                         inventarios_productos as h,
                         medicamentos as k, 
                         unidades as l,
                         centros_utilidad as da, 
                         tipo_mpios as st,
                        inv_med_cod_forma_farmacologica forma
     
                         WHERE $criterio_ID
                         and k.cod_principio_activo = c.cod_principio_activo
                         and h.codigo_producto = k.codigo_medicamento 
                         and a.codigo_producto = h.codigo_producto
                         $parametros
                         and h.codigo_producto = a.codigo_producto 
                         and h.unidad_id = l.unidad_id
                         and s.centro_utilidad=da.centro_utilidad 
                         and s.empresa_id=da.empresa_id
                         and da.tipo_pais_id=st.tipo_pais_id
                         and da.tipo_dpto_id=st.tipo_dpto_id 
                         and da.tipo_mpio_id=st.tipo_mpio_id
                         and k.cod_forma_farmacologica=forma.cod_forma_farmacologica
                         " . $criterio . " order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $var[0][uso_controlado] = $uso_controlado;
        $var[0][razon_social] = $_SESSION['CENTRO']['NOM_EMP'];

        if ($_REQUEST['sw_pos'] == '1' AND ($var[0][sw_tipo_plan] != 1 AND $var[0][sw_tipo_plan] != 2)) {
            if ((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND
                    (!empty($var[0][tipo_afiliado_id]))) {
                $query = "select cuota_moderadora from planes_rangos
                    where plan_id = " . $var[0][plan_id] . "
                    AND tipo_afiliado_id = '" . $var[0][tipo_afiliado_id] . "'
                    AND rango = '" . $var[0][rango] . "';";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    $cuotam = $result->GetRowAssoc($ToUpper = false);
                }
                $var[0][cuota_moderadora] = $cuotam;
            }
        }

        for ($i = 0; $i < sizeof($var); $i++) {
            $query == '';
            unset($vector);
            if ($var[$i][tipo_opcion_posologia_id] == 1) {
                $query = "select periocidad_id, tiempo from hc_posologia_horario_op1 where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 2) {
                $query = "select a.duracion_id, b.descripcion from hc_posologia_horario_op2 as a, hc_horario as b where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "' and a.duracion_id = b.duracion_id";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 3) {
                $query = "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3 where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 4) {
                $query = "select hora_especifica from hc_posologia_horario_op4 where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 5) {
                $query = "select frecuencia_suministro from hc_posologia_horario_op5 where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }

            if ($query != '') {
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    if ($var[$i][tipo_opcion_posologia_id] != 4) {
                        while (!$result->EOF) {
                            $vector[] = $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                    } else {
                        while (!$result->EOF) {
                            $vector[$result->fields[0]] = $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                    }
                }
            }
            $var[$i][posologia] = $vector;
            unset($vector);
        }

        //parte de los diagnosticos
        $query = "select b.diagnostico_id, b.diagnostico_nombre
                    FROM hc_diagnosticos_ingreso as a, diagnosticos as b
                    WHERE a.tipo_diagnostico_id = b.diagnostico_id and
                    a.evolucion_id=" . $criterio_evolucion_id . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar los diagnosticos de ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $dingreso[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $var[0][diagnostico_ingreso] = $dingreso;
        unset($dingreso);

        $query = "select b.diagnostico_id, b.diagnostico_nombre
                    FROM hc_diagnosticos_egreso as a, diagnosticos as b
                    WHERE a.tipo_diagnostico_id = b.diagnostico_id and
                    a.evolucion_id=" . $criterio_evolucion_id . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al consultar los diagnosticos de egreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $degreso[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $var[0][diagnostico_egreso] = $degreso;
        unset($degreso);
        //fin de los diagnosticos

        if ($_REQUEST['impresion_pos'] == '1') {
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport($tipo_reporte = 'pos', $tipo_modulo = 'app', $modulo = 'Central_de_Autorizaciones', $reporte_name = 'formulamedica', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }
            $resultado = $classReport->GetExecResultado();
            unset($classReport);

            if (!empty($resultado[codigo])) {
                "El PrintReport retorno : " . $resultado[codigo] . "<br>";
            }

            if ($_REQUEST['modulo_invoca'] == 'impresionhc') {
                if ($_REQUEST['parametro_retorno'] == '1') {
                    $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
                }
            } elseif ($_REQUEST['modulo_invoca'] == 'Admisiones') {
                $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'FormaImpresionSolicitudes');
            } else {
                $this->ListadoPacientesEvolucionCerrada();
            }
        } else {
            if ($_REQUEST['parametro_retorno'] == '1') {
                IncludeLib("reportes/formula_ambulatoria");
                GenerarFormula($var);
                if (is_array($var)) {
                    $RUTA = $_ROOT . "cache/formula_medica_amb" . UserGetUID() . ".pdf";
                    $DIR = "printer.php?ruta=$RUTA";
                    $RUTA1 = GetBaseURL() . $DIR;
                    $mostrar = "\n<script language='javascript'>\n";
                    $mostrar.="var rem=\"\";\n";
                    $mostrar.="  function abreVentana(){\n";
                    $mostrar.="    var nombre=\"\"\n";
                    $mostrar.="    var url2=\"\"\n";
                    $mostrar.="    var width=\"400\"\n";
                    $mostrar.="    var height=\"300\"\n";
                    $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                    $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                    $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                    $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                    $mostrar.="    var url2 ='$RUTA1';\n";
                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                    $mostrar.="</script>\n";
                    $this->salida.="$mostrar";
                    $this->salida.="<BODY onload=abreVentana();>";
                }
                if ($_REQUEST['modulo_invoca'] == 'impresionhc') {
                    $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
                } else {
                    $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'FormaImpresionSolicitudes');
                }
            } else {
                IncludeLib("reportes/formula_ambulatoria");
                GenerarFormula($var);
                $this->ListadoPacientesEvolucionCerrada($var);
            }
        }
        return true;
    }

    function ReporteFormulaMedicaHosp() {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $criterio == '';
        $uso_controlado = 0;
        if (($_REQUEST['sw_paciente_no_pos'] === '0') OR ($_REQUEST['sw_paciente_no_pos'] == 1)) {
            $criterio = "AND k.sw_pos = '" . $_REQUEST['sw_pos'] . "' AND a.sw_paciente_no_pos = '" . $_REQUEST['sw_paciente_no_pos'] . "'";
        } elseif ($_REQUEST['sw_pos'] == '1') {
            $criterio = "AND k.sw_pos = '" . $_REQUEST['sw_pos'] . "'";
        }
        if ($criterio == '' AND $_REQUEST['sw_uso_controlado'] == '1') {
            $criterio = "AND k.sw_uso_controlado = '" . $_REQUEST['sw_uso_controlado'] . "'";
            $uso_controlado = 1;
        }

        //cargando criterios cuando sea invocado desde otro lado.
        if ($_SESSION['CENTRAL']['PACIENTE']['paciente_id'] == '') {
            $criterio_paciente = $_REQUEST['paciente_id'];
        } else {
            $criterio_paciente = $_SESSION['CENTRAL']['PACIENTE']['paciente_id'];
        }
        if ($_SESSION['CENTRAL']['PACIENTE']['tipo_id'] == '') {
            $criterio_tipo_id = $_REQUEST['tipo_id_paciente'];
        } else {
            $criterio_tipo_id = $_SESSION['CENTRAL']['PACIENTE']['tipo_id'];
        }
        if ($_SESSION['CENTRAL']['PACIENTE']['ingreso'] == '') {
            $criterio_ingreso = $_REQUEST['ingreso'];
        } else {
            $criterio_ingreso = $_SESSION['CENTRAL']['PACIENTE']['ingreso'];
        }
        //fin de criterios


        list($dbconn) = GetDBconn();

        $query = "SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||
                         w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
                         w.tipo_id_paciente, 
                         w.paciente_id, 
                         w.sexo_id, 
                         w.fecha_nacimiento,
                         x.historia_numero, 
                         x.historia_prefijo, 
                         n.fecha_cierre,
						 ing.fecha_ingreso,
						 ing.ingreso,
                         st.municipio,
                         z.detalle as atencion, 
                         cd.nombre as empleador,
                         n.fecha, 
                         w.residencia_direccion, 
                         w.residencia_telefono,
                         v.tipo_afiliado_id, 
                         t.plan_id, 
                         sw_tipo_plan, 
                         s.rango,
                         v.tipo_afiliado_nombre, 
                         p.nombre_tercero,	
                         u.nombre_tercero as cliente,
                         r.descripcion as tipo_profesional, 
                         p.tipo_id_tercero as tipo_id_medico,
                         p.tercero_id as	medico_id, 
                         q.tarjeta_profesional,
                         q.firma,						 
                         t.plan_descripcion,
                         a.evolucion_id, 
                         case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
                         a.sw_paciente_no_pos, 
                         a.codigo_producto,  
                         h.descripcion as producto,
                         c.descripcion as principio_activo, 
                         m.nombre as via, a.dosis,
                         a.unidad_dosificacion, 
                         a.tipo_opcion_posologia_id, 
                         CASE WHEN  a.cantidadperiocidad  IS NOT NULL THEN  a.cantidadperiocidad 
                           ELSE a.cantidad END AS cantidad, 
                         l.descripcion,	
                         h.contenido_unidad_venta,	
                         a.observacion,
						 a.dias_tratamiento,
                         k.concentracion_forma_farmacologica as concentracion,
                         forma.descripcion AS  forma
                         
               
                  FROM    hc_medicamentos_recetados_amb as a
                          JOIN hc_evoluciones as n 
                          on (a.evolucion_id= n.evolucion_id)
						  JOIN ingresos as ing 
						  on (n.ingreso = ing.ingreso)
                          LEFT JOIN hc_vias_administracion as m 
                          on (a.via_administracion_id = m.via_administracion_id)
                          LEFT JOIN hc_atencion y 
                          on (n.evolucion_id= y.evolucion_id) 
                          LEFT JOIN hc_tipos_atencion z 
                          on (y.tipo_atencion_id = z.tipo_atencion_id)
                          LEFT JOIN ingresos_empleadores ab 
                          on (n.ingreso = ab.ingreso) 
                          LEFT JOIN empleadores cd 
                          on (ab.empleador_id = cd.empleador_id and
                              ab.tipo_id_empleador = cd.tipo_id_empleador)
                          LEFT JOIN	profesionales_usuarios as o 
                          on (n.usuario_id = o.usuario_id) 
                          LEFT JOIN terceros as p	
                          on (o.tipo_tercero_id = p.tipo_id_tercero AND
                              o.tercero_id = p.tercero_id) 
                          LEFT JOIN	profesionales as q 
                          on (o.tipo_tercero_id = q.tipo_id_tercero AND 
                              o.tercero_id = q.tercero_id)
                          LEFT JOIN tipos_profesionales as r 
                          on (q.tipo_profesional = r.tipo_profesional)
                          LEFT JOIN cuentas as s 
                          on (n.numerodecuenta = s.numerodecuenta) 
                          LEFT JOIN planes as t	
                          on (s.plan_id = t.plan_id) 
                          LEFT JOIN terceros as u 
                          on (t.tipo_tercero_id = u.tipo_id_tercero AND 
                              t.tercero_id	= u.tercero_id)
                          LEFT JOIN tipos_afiliado as v 
                          on (s.tipo_afiliado_id = v.tipo_afiliado_id)
                          JOIN pacientes as w 
                          on (w.paciente_id= '" . $criterio_paciente . "'
                              and w.tipo_id_paciente = '" . $criterio_tipo_id . "')
     
                         join historias_clinicas x on ((w.paciente_id= x.paciente_id
                         and w.tipo_id_paciente = x.tipo_id_paciente)),
                         inv_med_cod_principios_activos as c, 
                         inventarios_productos as h,
                         medicamentos as k, 
                         unidades as l,
                         centros_utilidad as da, 
                         tipo_mpios as st,
                        inv_med_cod_forma_farmacologica forma
     
                         WHERE k.cod_principio_activo = c.cod_principio_activo
                         and h.codigo_producto = k.codigo_medicamento 
                         and a.codigo_producto = h.codigo_producto
                         $parametros
                         and h.codigo_producto = a.codigo_producto 
                         and h.unidad_id = l.unidad_id
                         and s.centro_utilidad=da.centro_utilidad 
                         and s.empresa_id=da.empresa_id
                         and da.tipo_pais_id=st.tipo_pais_id
                         and da.tipo_dpto_id=st.tipo_dpto_id 
                         and da.tipo_mpio_id=st.tipo_mpio_id
						 and ing.ingreso = " . $criterio_ingreso . "
                         and k.cod_forma_farmacologica=forma.cod_forma_farmacologica
						 
                         " . $criterio . " order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
        
//$criterio $filtro_evolucion        
        
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $var[0][uso_controlado] = $uso_controlado;
        $var[0][razon_social] = $_SESSION['CENTRO']['NOM_EMP'];

        //obteniendo la cuota moderadora solo para cuando el plan es = 3 y sw_pos = 1
        if ($_REQUEST['sw_pos'] == '1' AND $var[0][sw_tipo_plan] == 3) {
            if ((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND
                    (!empty($var[0][tipo_afiliado_id]))) {
                $query = "select cuota_moderadora from planes_rangos
                    where plan_id = " . $var[0][plan_id] . "
                    AND tipo_afiliado_id = '" . $var[0][tipo_afiliado_id] . "'
                    AND rango = '" . $var[0][rango] . "';";

                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    $cuotam = $result->GetRowAssoc($ToUpper = false);
                }
                $var[0][cuota_moderadora] = $cuotam;
            }
        }

        //obteniendo la posologia para cada medicamento desde la estacion para imprimir en la formula medica.
        for ($i = 0; $i < sizeof($var); $i++) {
            $query == '';
            unset($vector);
            if ($var[$i][tipo_opcion_posologia_id] == 1) {
                $query = "select periocidad_id, tiempo from hc_posologia_horario_op1 where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 2) {
                $query = "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "' and a.duracion_id = b.duracion_id";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 3) {
                $query = "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 4) {
                $query = "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }
            if ($var[$i][tipo_opcion_posologia_id] == 5) {
                $query = "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = " . $var[$i][evolucion_id] . " and codigo_producto = '" . $var[$i][codigo_producto] . "'";
            }

            if ($query != '') {
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    if ($var[$i][tipo_opcion_posologia_id] != 4) {
                        while (!$result->EOF) {
                            $vector[] = $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                    } else {
                        while (!$result->EOF) {
                            $vector[$result->fields[0]] = $result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                        }
                    }
                }
            }
            $var[$i][posologia] = $vector;
            unset($vector);
        }

        if ($_REQUEST['impresion_pos'] == '1') {
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport($tipo_reporte = 'pos', $tipo_modulo = 'app', $modulo = 'CentralImpresionHospitalizacion', $reporte_name = 'formulamedica', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
            unset($classReport);

            if (!empty($resultado[codigo])) {
                "El PrintReport retorno : " . $resultado[codigo] . "<br>";
            }
            if ($_REQUEST['parametro_retorno'] == '1') {
                if ($_REQUEST['modulo_invoca'] == 'impresionhc') {
                    $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
                } elseif ($_REQUEST['modulo_invoca'] == 'salida_pacientes') {
                    $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'FormaImpresionSolicitudes');
                }
            } else {
                $this->ListadoPacientesEvolucionCerrada();
            }
        } else {
            if ($_REQUEST['parametro_retorno'] == '1') {
                IncludeLib("reportes/formula_hospitalaria");
                GenerarFormula($var);
                if (is_array($var)) {
                    $RUTA = $_ROOT . "cache/formula_medica_hos.pdf";
                    $DIR = "printer.php?ruta=$RUTA";
                    $RUTA1 = GetBaseURL() . $DIR;
                    $mostrar = "\n<script language='javascript'>\n";
                    $mostrar.="var rem=\"\";\n";
                    $mostrar.="  function abreVentana(){\n";
                    $mostrar.="    var nombre=\"\"\n";
                    $mostrar.="    var url2=\"\"\n";
                    $mostrar.="    var str=\"\"\n";
                    $mostrar.="    var width=\"400\"\n";
                    $mostrar.="    var height=\"300\"\n";
                    $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                    $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                    $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                    $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                    $mostrar.="    var url2 ='$RUTA1';\n";
                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                    $mostrar.="</script>\n";
                    $this->salida.="$mostrar";
                    $this->salida.="<BODY onload=abreVentana();>";
                }
                if ($_REQUEST['modulo_invoca'] == 'impresionhc') {
                    $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
                } elseif ($_REQUEST['modulo_invoca'] == 'salida_pacientes') {
                    $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'FormaImpresionSolicitudes');
                }
            } else {
                IncludeLib("reportes/formula_ambulatoria");
                GenerarFormula($var);
                $this->ListadoPacientesEvolucionCerrada($var);
            }
        }
        return true;
    }

//esta funcion es invocada localmente y desde los modulos externos IMPRESION_HC,
//CENTRAL_IMPRESION_HOSPITALIZACION, SALIDA DE PACIENTES
//para generar reportes en impresora pos y reportes en media carta.
    function ReporteIncapacidadMedica() {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }
        list($dbconn) = GetDBconn();

        if ($_SESSION['CENTRAL']['PACIENTE']['evolucion_id'] == '') {
            $criterio_evolucion = $_REQUEST['evolucion'];
        } else {
            $criterio_evolucion = $_SESSION['CENTRAL']['PACIENTE']['evolucion_id'];
        }
        $query = "SELECT	btrim(y.primer_nombre||' '||y.segundo_nombre||' '||
                		y.primer_apellido||' '||y.segundo_apellido,'') as paciente,
                		z.tipo_id_paciente, 
                    z.paciente_id, 
                    b.ingreso,
                    y.primer_nombre,
                    y.segundo_nombre,
                		y.primer_apellido,
                    y.segundo_apellido, 
                    DA.descripcion as ips,
                		b.fecha_cierre, 
                    z.fecha_ingreso, 
                    p.cama, 
                    y.sexo_id, 
                    y.fecha_nacimiento,
                		q.historia_numero,	
                    q.historia_prefijo, 
                    a.sw_prorroga, 
                    v.descripcion as clase,
                		a.diagnostico_id,
                		m.descripcion as servicio, 
                    g.rango, 
                    k.tipo_afiliado_nombre,
                		d.nombre_tercero, 
                    d.tipo_id_tercero as tipo_id_medico, 
                    d.tercero_id as
                		medico_id, 
                    e.tarjeta_profesional,
					e.firma,
                    f.descripcion as tipo_profesional,
                		j.nombre_tercero as cliente,  
                    h.plan_descripcion, 
                    a.evolucion_id,
                		a.tipo_incapacidad_id, 
                    n.descripcion as tipo_incapacidad_descripcion,
                		a.observacion_incapacidad, 
                    a.dias_de_incapacidad, w.municipio,
                		b.fecha,
                        b.usuario_id,						
                    (date(a.fecha_inicio) + (a.dias_de_incapacidad - 1)) as fecha_terminacion, DD.diagnostico_nombre,
                    a.fecha_inicio,
                    UD.descripcion_dependencia,
                    a.ciudad_laboral
            FROM    hc_incapacidades as a
                    LEFT JOIN uv_dependencias UD
                    ON(UD.codigo_dependencia_id = a.codigo_dependencia_id )
                    join hc_evoluciones as b on
                    (a.evolucion_id= b.evolucion_id)
		
		left join diagnosticos as DD on(a.diagnostico_id=DD.diagnostico_id)

		join	ingresos z on (b.ingreso=z.ingreso)
		join cuentas as g on (b.numerodecuenta = g.numerodecuenta)
		join planes as h on (g.plan_id = h.plan_id)
		join terceros as j on (h.tipo_tercero_id = j.tipo_id_tercero AND h.tercero_id = j.tercero_id)
		join tipos_afiliado as k on (g.tipo_afiliado_id = k.tipo_afiliado_id)
		join	departamentos as l on (l.departamento = z.departamento)
		join servicios as m on  (l.servicio = m.servicio)

		left join movimientos_habitacion p
		on (z.ingreso = p.ingreso and p.fecha_egreso ISNULL)

		left join hc_tipos_atencion_incapacidad as v on(a.tipo_atencion_incapacidad_id=v.tipo_atencion_incapacidad_id)

		left join pacientes y on
		(z.tipo_id_paciente = y.tipo_id_paciente and  z.paciente_id = y.paciente_id)

		join historias_clinicas q on ((y.paciente_id= q.paciente_id
		and y.tipo_id_paciente = q.tipo_id_paciente))

		left join profesionales_usuarios as c on
		(b.usuario_id = c.usuario_id) left join terceros as d on
		(c.tipo_tercero_id = d.tipo_id_tercero AND c.tercero_id = d.tercero_id)
		left join profesionales as e on (c.tipo_tercero_id = e.tipo_id_tercero
		AND c.tercero_id = e.tercero_id) left join tipos_profesionales as f on
		(e.tipo_profesional = f.tipo_profesional) 
		join hc_tipos_incapacidad as n on( a.tipo_incapacidad_id = n.tipo_incapacidad_id), centros_utilidad as x, tipo_mpios as w,
		unidades_funcionales as DA

		WHERE a.evolucion_id = " . $criterio_evolucion . "
		
		and l.centro_utilidad=x.centro_utilidad and x.tipo_pais_id=w.tipo_pais_id
		and x.tipo_dpto_id=w.tipo_dpto_id and x.tipo_mpio_id=w.tipo_mpio_id
		and l.unidad_funcional=DA.unidad_funcional";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo EE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $var[0][razon_social] = $_SESSION['CENTRO']['NOM_EMP'];

        if ($var[0][fecha]) {
            $fech = strtok($var[0][fecha], "-");
            for ($l = 0; $l < 3; $l++) {
                $date[$l] = $fech;
                $fech = strtok("-");
            }
            $var[0][fecha] = ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
        }

        if ($var[0][fecha_terminacion]) {
            $fech = strtok($var[0][fecha_terminacion], "-");
            for ($l = 0; $l < 3; $l++) {
                $date[$l] = $fech;
                $fech = strtok("-");
            }
            $var[0][fecha_terminacion] = ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
        }

        if (!empty($var[0][diagnostico_nombre])) {
            $var[0][diagnostico_ingreso][0] = array('diagnostico_id' => $var[0][diagnostico_id], 'diagnostico_nombre' => $var[0][diagnostico_nombre]);
        }
        unset($dingreso);

        if (empty($var[0][diagnostico_ingreso])) {
            GLOBAL $ADODB_FETCH_MODE;
            $query = "select b.diagnostico_id, b.diagnostico_nombre
               FROM hc_diagnosticos_ingreso as a, diagnosticos as b, hc_evoluciones as hc
               WHERE a.tipo_diagnostico_id = b.diagnostico_id 
               and a.evolucion_id = hc.evolucion_id
               and hc.ingreso = " . $var[0][ingreso] . "
               and a.sw_principal='1'";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al buscar en la consulta de medicamentos recetados";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            } else {
                $dingreso = $result->FetchRow();
            }

            $var[0][diagnostico_ingreso][0] = array('diagnostico_id' => $dingreso[diagnostico_id], 'diagnostico_nombre' => $dingreso[diagnostico_nombre]);
            unset($dingreso);

            if (empty($var[0][diagnostico_ingreso][0][diagnostico_id])) {
                $query0 = "select b.diagnostico_id, b.diagnostico_nombre
                    FROM hc_diagnosticos_egreso as a, diagnosticos as b, hc_evoluciones as hc
                    WHERE a.tipo_diagnostico_id = b.diagnostico_id
                    and a.evolucion_id = hc.evolucion_id
                    and hc.ingreso = " . $var[0][ingreso] . "
                    and a.sw_principal='1'";
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query0);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                } else {
                    $degreso = $result->FetchRow();
                }

                $var[0][diagnostico_egreso][0] = array('diagnostico_id' => $degreso[diagnostico_id], 'diagnostico_nombre' => $degreso[diagnostico_nombre]);
                unset($degreso);
            }
        }

        if ($_REQUEST['impresion_pos'] == 1) {
            $classReport = new reports;
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport($tipo_reporte = 'pos', $tipo_modulo = 'app', $modulo = 'Central_de_Autorizaciones', $reporte_name = 'incapacidad', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
            if (!$reporte) {
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
            }

            $resultado = $classReport->GetExecResultado();
            unset($classReport);

            if (!empty($resultado[codigo])) {
                "El PrintReport retorno : " . $resultado[codigo] . "<br>";
            }
            if ($_REQUEST['parametro_retorno'] == '1') {
                if ($_REQUEST['modulo_invoca'] == 'impresionhc') {
                    $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
                } elseif ($_REQUEST['modulo_invoca'] == 'impresion_hospitalizacion') {
                    $this->ReturnMetodoExterno('app', 'CentralImpresionHospitalizacion', 'user', 'FormaDetalleImpresion');
                } elseif ($_REQUEST['modulo_invoca'] == 'salida_pacientes') {
                    $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'FormaImpresionSolicitudes');
                }
            } else {
                $this->ListadoPacientesEvolucionCerrada();
            }
        } else {
            if ($_REQUEST['parametro_retorno'] == '1') {
                IncludeLib("reportes/incapacidad");
                GenerarIncapacidad($var);
                if (is_array($var)) {
                    $RUTA = $_ROOT . "cache/incapacidad_medica" . UserGetUID() . ".pdf";
                    $DIR = "printer.php?ruta=$RUTA";
                    $RUTA1 = GetBaseURL() . $DIR;
                    $mostrar = "\n<script language='javascript'>\n";
                    $mostrar.="var rem=\"\";\n";
                    $mostrar.="  function abreVentana(){\n";
                    $mostrar.="    var url2=\"\"\n";
                    $mostrar.="    var width=\"400\"\n";
                    $mostrar.="    var height=\"300\"\n";
                    $mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
                    $mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
                    $mostrar.="    var nombre=\"Printer_Mananger\";\n";
                    $mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
                    $mostrar.="    var url2 ='$RUTA1';\n";
                    $mostrar.="    rem = window.open(url2, nombre, str)};\n";
                    $mostrar.="</script>\n";
                    $this->salida.="$mostrar";
                    $this->salida.="<BODY onload=abreVentana();>";
                }
                if ($_REQUEST['modulo_invoca'] == 'impresionhc') {
                    $this->ReturnMetodoExterno('app', 'ImpresionHC', 'user', 'FormaImpresionSolicitudes');
                } elseif ($_REQUEST['modulo_invoca'] == 'impresion_hospitalizacion') {
                    $this->ReturnMetodoExterno('app', 'CentralImpresionHospitalizacion', 'user', 'FormaDetalleImpresion');
                } elseif ($_REQUEST['modulo_invoca'] == 'salida_pacientes') {
                    $this->ReturnMetodoExterno('app', 'Admisiones', 'user', 'FormaImpresionSolicitudes');
                }
            } else {
                IncludeLib("reportes/incapacidad");
                GenerarIncapacidad($var);
                $this->ListadoPacientesEvolucionCerrada($var, 1);
            }
        }
        return true;
    }

    //----------------FIN REPORTES CLAUDIA
    function ObtenerObservacionSolicitud($hc_os_solicitud_id, $tabla) {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM " . $tabla . " WHERE hc_os_solicitud_id = " . $hc_os_solicitud_id;


        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo EE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        return $var;
    }

    function ObtenerTipoSolicitud($hc_os_solicitud_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT os_tipo_solicitud_id FROM hc_os_solicitudes WHERE hc_os_solicitud_id = " . $hc_os_solicitud_id;

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo EE";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            return false;
        } else {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        return $var;
    }

}

?>
