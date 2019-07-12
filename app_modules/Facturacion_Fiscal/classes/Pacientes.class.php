<?php

/* * ****************************************************************************
 * $Id: Glosas.class.php,v 1.1 2009/09/02 13:02:28 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * $Revision: 1.1 $ 
 * 
 * @autor Hugo F  Manrique 
 * ****************************************************************************** */

class Pacientes {

    function Pacientes() {
        
    }

    /*     * *************************************************************************************
     *
     * ************************************************************************************** */


    function ObtenerEstadoEPSAfiliados($Tipo, $paciente_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT ea.*, et.descripcion_eps_tipo_afiliado, pr.rango, pr.tipo_afiliado_id
                  FROM eps_afiliados ea INNER JOIN eps_tipos_afiliados et ON et.eps_tipo_afiliado_id = ea.eps_tipo_afiliado_id
                                        INNER JOIN planes_rangos pr ON (pr.tipo_afiliado_id = ea.tipo_afiliado_atencion 
                        AND pr.plan_id = ea.plan_atencion AND pr.rango = ea.rango_afiliado_atencion)
                  WHERE ea.afiliado_tipo_id = '$Tipo' AND ea.afiliado_id = '$paciente_id'
                  ORDER BY ea.eps_afiliacion_id DESC
                  LIMIT 1";
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

    function PacientesOrdenesServicios($Tipo, $paciente_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT OS.plan_id, OS.semanas_cotizadas, OS.tipo_afiliado_id, OS.rango
                  FROM os_ordenes_servicios OS INNER JOIN planes_rangos PR 
                    ON (PR.plan_id = OS.plan_id AND PR.rango = OS.rango AND PR.tipo_afiliado_id = OS.tipo_afiliado_id)
                             INNER JOIN tipos_afiliado TA ON (TA.tipo_afiliado_id = PR.tipo_afiliado_id)
                  WHERE OS.tipo_id_paciente = '$Tipo' AND OS.paciente_id = '$paciente_id'
                  ORDER BY OS.orden_servicio_id DESC
                  LIMIT 1";
//        return $query;
        
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

    function ObtenerEstadoPaciente($Tipo, $paciente_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM pacientes p WHERE p.tipo_id_paciente = '$Tipo' AND p.paciente_id = '$paciente_id'";
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

    function ObtenerEstadoPlan($plan_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT p.sw_afiliados, p.plan_id, p.plan_descripcion FROM planes p WHERE p.plan_id = ".$plan_id;
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

    function responsables() {
        list($dbconn) = GetDBconn();
        $query = "select empresa_id,sw_todos_planes from userpermisos_centro_autorizacion
                                    where usuario_id=" . UserGetUID() . " and sw_todos_planes=1";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $vars[$result->fields[0]] = $result->fields[0];
                $result->MoveNext();
            }
            foreach ($vars as $k => $v) {
                $query = "SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                          FROM planes as a
                          WHERE a.fecha_final >= now() and a.estado=1
                            and a.fecha_inicio <= now()
                            and empresa_id='" . $k . "'
                          ORDER BY 2 ";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al obtener los datos del plan";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if (!$result->EOF) {
                    $var[] = $results->GetRowAssoc($ToUpper = false);
                }
            }
            //echo    $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
            //                    FROM planes as a
            //                    WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()";
        } else {
            $query = "SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                                            FROM planes as a, userpermisos_centro_autorizacion as b
                                            WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
                                            and b.usuario_id=" . UserGetUID() . "
                                            and b.plan_id=a.plan_id
                                      ORDER BY 2 ";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $result->Close();
        return $var;
    }

    function Tipo_AfiliadoS($plan=null) {
        list($dbconn) = GetDBconn();
        $where = "";
        if(!(empty($plan))){
            $where = " WHERE b.plan_id='" . $plan . "'";
        }
        
        $query  = " SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id ";
        $query .= " FROM tipos_afiliado as a INNER JOIN planes_rangos as b ON (b.tipo_afiliado_id=a.tipo_afiliado_id) ";
        $query .= $where;
        
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
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

    function NivelesS($plan=null) {
        list($dbconn) = GetDBconn();
        $where = "";
        if(!empty($plan)){
            $where = " WHERE plan_id=" . $plan;
        }
        $query = "SELECT DISTINCT rango FROM planes_rangos ".$where;
        $result = $dbconn->Execute($query);
        while (!$result->EOF) {
            $niveles[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $niveles;
    }
}

?>