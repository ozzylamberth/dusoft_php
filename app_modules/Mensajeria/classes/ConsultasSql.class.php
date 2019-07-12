<?php

class ConsultasSql {

    //tabla actualizaciones la cual contiene los registros de Actualizaciones del Sistema
    function IngrActualizacion($usuario_id, $asunto, $descripcion, $fecha_fin) {
        $hora = '23:59:59.59';
        $sql = "INSERT INTO actualizaciones (usuario_id,asunto,descripcion,fecha_ini,fecha_fin)
                VALUES (
                '" . $usuario_id . "',
                    '" . utf8_decode($asunto) . "',
                        '" . $descripcion . "',
                            now(),
                                '" . $fecha_fin . ' ' . $hora . "')";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function Ingrcontrolar_x_perfil($actualizacion_id, $cargo_id, $obligatorio) {
        $sql = "INSERT INTO controlar_x_perfil (actualizacion_id,perfil_id,obligatorio,	fecha_registro)
                VALUES (
                '" . $actualizacion_id . "',
                    '" . $cargo_id . "',
                        '" . $obligatorio . "',
                            now()
                            )";
        if (!$rst = $this->ConexionBaseDatos($sql)) {
            $sql = "UPDATE controlar_x_perfil SET 	
                   perfil_id ='" . $cargo_id . "',
                   obligatorio= '" . $obligatorio . "',
                   fecha_registro= now()
                   WHERE    actualizacion_id  = '" . $actualizacion_id . "' ;";

            if (!$rst = $this->ConexionBaseDatos($sql)) {

                return false;
            }
        }
        $rst->Close();
        return true;
    }

    function Consultarcontrolar_x_perfil($id) {
        $sql = "SELECT cx.perfil_id,cx.obligatorio,ca.descripcion
                FROM controlar_x_perfil as cx
                LEFT join system_perfiles as ca on cx.perfil_id=ca.perfil_id
                where cx.actualizacion_id ='" . $id . "' ";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars; //SELECT MAX(id) FROM tabla;
    }

    function ConsultarIDActulizacion() {
        $sql = "SELECT MAX(actualizacion_id) FROM actualizaciones";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars; 
    }

    function Deletecontrolar_x_perfil($actualizacion_id) {
        $sql = "Delete from controlar_x_perfil 
            where actualizacion_id= '" . $actualizacion_id . "'";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function UpdateActualizacion($usuario_id, $asunto, $descripcion, $fecha_fin, $actualizacion_id) {
        $hora = '23:59:59.59';
        $sql = "UPDATE actualizaciones SET
                usuario_id= '" . $usuario_id . "',
                asunto= '" . utf8_decode($asunto) . "',
                descripcion='" . $descripcion . "',
                fecha_actu=now(),
                fecha_fin='" . $fecha_fin . ' ' . $hora . "'
                where  actualizacion_id= '" . $actualizacion_id . "'; ";
        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function IngrControl($tercero_id, $actualiza_id, $sw) {
        $sql.="INSERT INTO permiso_actualiza (tercero_id,actualiza_id,sw)
                VALUES (
                '" . $tercero_id . "',
                    '" . $actualiza_id . "',
                        '" . $sw . "')";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function Ingrlectura($actualiza_id, $sw) {
        $usuario_id = UserGetUID();
        $sql = "INSERT INTO controlar_lectura (actualizacion_id,usuario_id,sw,fecha_lectura)
                VALUES ($actualiza_id,$usuario_id,$sw,now())";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function ConsultarMensajeria() {

        $sql.="select 
                    actualizacion_id, 
                    usuario_id, 
                    asunto, 
                    descripcion, 
                    to_char(fecha_ini, 'dd-mm-yy hh:mi am') as fecha_inia,
                    to_char(fecha_fin, 'dd-mm-yy hh:mi am') as fecha_fina,
                    to_char(fecha_actu, 'dd-mm-yy hh:mi am') as fecha_actua,
                    fecha_ini,
                    fecha_fin,
                    fecha_actu
                from 
                    actualizaciones 
                        ORDER BY fecha_ini DESC ;";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function ConsulActulizacionWhere($actualizacion_id) {
        $sql.="select * from actualizaciones where actualizacion_id ='$actualizacion_id'";

        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function Consultar() {
        $usuario_id = UserGetUID();
        $sql = "select permiso_id from permisos_actual where usuario_id= $usuario_id";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function ConsultarPermisos() {
        $usuario_id = UserGetUID();
        $sql = "select * from permisos_actual where usuario_id= $usuario_id";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function ConsultarControl($usuario_id) {
        $sql = "select 
                DISTINCT a.actualizacion_id,
                a.asunto,
                a.descripcion,
                a.fecha_fin,
                cl.fecha_lectura,
                cl.sw,c.obligatorio,
                (select nombre from system_usuarios where usuario_id=a.usuario_id) as nombre 
                from system_usuarios_perfiles as s 
                inner join controlar_x_perfil as c on (c.perfil_id = s.perfil_id or c.perfil_id=-1) 
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id 
                inner join system_usuarios as su on (s.usuario_id=su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id' 
                where a.fecha_fin >=now() and 
                s.usuario_id= '$usuario_id' order  by c.obligatorio desc,cl.sw asc,a.fecha_fin asc;";

        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

//fecha_lectura sw actualizacion_id
    function ConsultarControlObligatorio($usuario_id) {
        $sql = "select 
                a.actualizacion_id,
                a.asunto,
                a.descripcion,
                a.fecha_fin,
                cl.sw,
                cl.fecha_lectura,
                c.obligatorio,
                (select nombre from system_usuarios where usuario_id=a.usuario_id) as nombre
                from system_usuarios_perfiles as s
                inner join controlar_x_perfil as c on c.perfil_id = s.perfil_id or c.perfil_id=-1 and c.obligatorio=1
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id
                inner join system_usuarios as su on (s.usuario_id=su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id'
                where a.fecha_fin >=now() and 
                s.usuario_id='$usuario_id' 
                order by cl.sw ;";

        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function TipoCargos() {
        $sql = "SELECT perfil_id,descripcion
        FROM system_perfiles ORDER BY perfil_id asc";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function IngrCargo($actualiza_id, $cargos_id) {
        $sql = "INSERT INTO controlar_x_perfil (actualizacion_id,perfil_id)
                VALUES ($actualiza_id,$cargos_id)";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;
        $rst->Close();
        return true;
    }

    function ConexionBaseDatos($sql) {
        list($dbconn) = GetDBConn();
        //$dbconn->debug=true;
        $rst = $dbconn->Execute($sql);

        if ($dbconn->ErrorNo() != 0) {
            $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
            echo "<b class=\"label\">" . $this->frmError['MensajeError'] . "<br> ERROR: " . $sql . "</b>";
            return false;
        }

        return $rst;
    }

    function ConsultarMensajes() {
        $usuario_id = UserGetUID();
        $sql.="select count(fecha_fin) as todas,count (cl.sw) as leidas
                from system_usuarios_perfiles as s
                inner join controlar_x_perfil as c on (c.perfil_id = s.perfil_id  or c.perfil_id=-1)
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id
                inner join system_usuarios as su on (s.usuario_id=su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id'
                where a.fecha_fin >=now() and s.usuario_id='$usuario_id'";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function filtroEnvioMensajes($fecha1, $fecha2, $usuario) {
        $usuario_id = UserGetUID();
        if ($fecha2 != '') {
            $where = " AND  a.fecha_ini between '$fecha1' AND '$fecha2' ";
        } elseif ($fecha1 != '') {
            $where = " AND  a.fecha_ini = $fecha1 ";
        }
        if ($usuario != '') {
            $where .= "AND a.usuario_id='$usuario'";
        }
        $sql = "select 
               a.actualizacion_id,
                a.asunto,
                a.descripcion,
                a.fecha_fin,
                cl.sw,
                cl.fecha_lectura,
                c.obligatorio,
                (select nombre from system_usuarios where usuario_id=a.usuario_id) as nombre
                from system_usuarios_perfiles as s
                inner join controlar_x_perfil as c on (c.perfil_id = s.perfil_id  or c.perfil_id=-1)
                inner join actualizaciones as a on a.actualizacion_id = c.actualizacion_id
                inner join system_usuarios as su on (s.usuario_id=su.usuario_id)
                left join controlar_lectura as cl on cl.actualizacion_id = a.actualizacion_id and cl.usuario_id='$usuario_id'
                where s.usuario_id='$usuario_id' $where ;";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function UsuariosEnvioMensajes() {
        $sql = " select pa.usuario_id,nombre 
                from permisos_actual pa
                inner join system_usuarios as su on (pa.usuario_id=su.usuario_id)
                where sw='1';";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

    function ConsultarLecturasMensajes($actualizacion_id) {
        $sql.="select nombre,
                     to_char(fecha_lectura, 'DD-MM-YYYY HH12:MI:SS AM')  as fecha
                from 
                controlar_lectura as cl
                inner join system_usuarios su on (cl.usuario_id=su.usuario_id)
                where 	actualizacion_id='$actualizacion_id';";
        if (!$results = $this->ConexionBaseDatos($sql))
            return false;
        while (!$results->EOF) {
            $vars[] = $results->GetRowAssoc($ToUpper = false);
            $results->MoveNext();
        }
        $results->Close();
        return $vars;
    }

}

?>