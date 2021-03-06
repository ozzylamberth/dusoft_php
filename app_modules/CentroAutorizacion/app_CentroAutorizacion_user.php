<?php

/**
 * $Id: app_CentroAutorizacion_user.php,v 1.3 2009/11/10 14:56:39 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
 * Contiene los metodos para realizar las autorizaciones.
 */
class app_CentroAutorizacion_user extends classModulo {

//  var $DatosRetono=array();
    var $color;
    var $limit;
    var $conteo;

//{
    function app_CentroAutorizacion_user() {
        $this->limit = GetLimitBrowser();
        $this->color = '#4D6EAB';
        return true;
    }

    /**
     * La funcion main es la principal y donde se llama FormaBuscar de la clase
     * app_Triage_user_HTML que muestra la forma para buscar al paciente
     */
    function main() {
        list($dbconn) = GetDBconn();
        if (!empty($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'])
                AND !empty($_SESSION['CENTROAUTORIZACION']['paciente_id'])
                AND !empty($_SESSION['CENTROAUTORIZACION']['PLAN'])) {
            $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=" . UserGetUID() . "";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }


        unset($_SESSION['CENTROAUTORIZACION']);
        unset($_SESSION['SEGURIDAD']['CENTROAUTORIZACION']);
        $SystemId = UserGetUID();
        /* if(!empty($_SESSION['SEGURIDAD']['CENTROAUTORIZACION']))
          {
          $this->salida.= gui_theme_menu_acceso('CENTRO AUTORIZACION',$_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['arreglo'],$_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['centro'],$_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['url'],ModuloGetURL('system','Menu'));
          return true;
          } */
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $query = "SELECT distinct a.empresa_id, a.nivel_autorizador_id, c.plan_id, b.razon_social as descripcion1, d.nombre_tercero, c.plan_descripcion,
                                case when CantidadSolicitudesPorPlan(c.plan_id)=0 then '' else ' [' || CantidadSolicitudesPorPlan(c.plan_id) || '] - ' end || d.nombre_tercero||' '|| c.plan_descripcion  as descripcion2
                                FROM userpermisos_centro_autorizacion as a, empresas as b, planes as c, terceros as d
                                WHERE a.usuario_id=$SystemId and a.empresa_id=b.empresa_id and c.tipo_tercero_id=d.tipo_id_tercero and c.tercero_id=d.tercero_id
                                and c.fecha_final >= now() and c.fecha_inicio <= now() and c.estado='1' and (a.plan_id=c.plan_id or a.sw_todos_planes=1)
                                and c.empresa_id=a.empresa_id";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $resulta = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while ($data = $resulta->FetchRow()) {
            $centro[$data['descripcion1']][$data['descripcion2']] = $data;
            $seguridad[$data['empresa_id']][$data['plan_id']] = 1;
        }
        $url[0] = 'app';
        $url[1] = 'CentroAutorizacion';
        $url[2] = 'user';
        $url[3] = 'TiposPlanes';
        $arreglo[0] = 'EMPRESA';
        $arreglo[1] = 'CENTRO AUTORIZACION';

        $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['arreglo'] = $arreglo;
        $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['centro'] = $centro;
        $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['url'] = $url;
        $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['puntos'] = $seguridad;
        $this->salida.= gui_theme_menu_acceso('CENTRO AUTORIZACION', $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['arreglo'], $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['centro'], $_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['url'], ModuloGetURL('system', 'Menu'));
        return true;
    }

    /**
     *
     */
    function LlamarBuscar() {
        unset($_SESSION['SPY']);
        list($dbconn) = GetDBconn();
        $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=" . UserGetUID() . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        unset($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE']);
        unset($_SESSION['CENTROAUTORIZACION']['paciente_id']);
        unset($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']);
        unset($_SESSION['CENTROAUTORIZACION']['nombre_paciente']);

        $this->FormaMetodoBuscar($Busqueda, $arr, $f);
        return true;
    }

    /**
     *
     */
    /* function main2()
      {
      unset($_SESSION['CENTROAUTORIZACION']);

      $x=$this->responsables();
      if(empty($x))
      {
      $Mensaje = 'NO HAY PLANES ACTIVOS PARA LA EMPRESA.';
      $accion=ModuloGetURL('system','Menu','user','main');
      if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
      return false;
      }
      return true;
      }

      list($dbconn) = GetDBconn();
      $query = "SELECT nivel_autorizador_id, empresa_id FROM userpermisos_centro_autorizacion
      WHERE usuario_id=".UserGetUID()."";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      $_SESSION['CENTROAUTORIZACION']['NIVEL']=$result->fields[0];
      $_SESSION['CENTROAUTORIZACION']['EMPRESA']=$result->fields[1];
      $_SESSION['CENTROAUTORIZACION']['TODOS']=true;
      $this->FormaMenus();
      //$this->FormaBuscarTodos();
      return true;
      } */

    function main2() {
        unset($_SESSION['CENTROAUTORIZACION']);

        $x = $this->responsables();
        if (empty($x)) {
            $Mensaje = 'NO HAY PLANES ACTIVOS PARA LA EMPRESA.';
            $accion = ModuloGetURL('system', 'Menu', 'user', 'main');
            if (!$this->FormaMensaje($Mensaje, 'CENTRO AUTORIZACIONES', $accion, '')) {
                return false;
            }
            return true;
        }

        list($dbconn) = GetDBconn();
        $query = "	SELECT b.razon_social, b.empresa_id, a.nivel_autorizador_id 
						FROM userpermisos_centro_autorizacion a,
						     empresas b
                      WHERE a.usuario_id=" . UserGetUID() . "
					  AND   a.empresa_id = b.empresa_id";

        $rst = $dbconn->Execute($query);

        while (!$rst->EOF) {
            $empresas[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
//        $this->main5();
        
        $url[0] = 'app';                                        //contenedor
        $url[1] = 'CentroAutorizacion';         //m?ulo
        $url[2] = 'user';                                       //clase
        $url[3] = 'MenuDos'; //m?odo
        $url[4] = 'permiso';
        $titulo[0] = 'EMPRESAS';

        $action = ModuloGetURL('system', 'Menu');

        $this->salida .= gui_theme_menu_acceso('CENTRAL DE AUTORIZACION', $titulo, $empresas, $url, $action);
 

        return true;
    }

    function MenuDos() {
        if (empty($_SESSION['CENTROAUTORIZACION']['EMPRESA'])) {
            $_SESSION['CENTROAUTORIZACION']['NIVEL'] = $_REQUEST['permiso']['nivel_autorizador_id'];
            $_SESSION['CENTROAUTORIZACION']['EMPRESA'] = $_REQUEST['permiso']['empresa_id'];
            $_SESSION['CENTROAUTORIZACION']['TODOS'] = true;
        }

//        $this->FormaMenus();
        $this->main5();
        return true;
    }

    function main5() {
        if (!$this->BuscarPermisosUser()) {
            return false;
        }
        return true;
    }

    function BuscarPermisosUser() {
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        //b.usuario_id=" . UserGetUID() . "
        
        $query = "SELECT c.departamento,c.descripcion as dpto, d.descripcion as
                    centro,e.empresa_id,e.razon_social as emp,d.centro_utilidad,
                    b.usuario_id, b.sw_farmacia
                  FROM userpermisos_central b, departamentos c, centros_utilidad d,empresas e
                  WHERE b.usuario_id=" . UserGetUID() . " and 
                    c.departamento=b.departamento
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
        $url[1] = 'CentroAutorizacion';
        $url[2] = 'user';
        $url[3] = 'MenFormaMenus';
        $url[4] = 'permiso';
        
        $arreglo[0] = 'EMPRESA';
        $arreglo[1] = 'CENTRO UTILIDAD';
        $arreglo[2] = 'CENTRAL DE AUTORIZACIONES';
        //$action = ModuloGetURL('system', 'Menu', 'user', 'main2');
        
        $action = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'main2');
        //$accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'main');
        $this->salida.= gui_theme_menu_acceso('CENTRAL DE IMPRESI?N', $arreglo, $laboratorio, $url, $action);
        
/*        
        $url[0] = 'app';                                        //contenedor
        $url[1] = 'CentroAutorizacion';         //m?ulo
        $url[2] = 'user';                                       //clase
        $url[3] = 'Menu2'; //m?odo
        $url[4] = 'permiso';
        $titulo[0] = 'EMPRESAS';

        $action = ModuloGetURL('system', 'Menu');

        $this->salida .= gui_theme_menu_acceso('CENTRAL DE AUTORIZACION', $titulo, $empresas, $url, $action);
 * 
 */
        return true;
    }
    
    function MenFormaMenus(){
        $this->FormaMenus();
        return true;
    }
    
    /**
     *
     */
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
											order by a.plan_descripcion";
                $results = $dbconn->Execute($query);
                if (!$result->EOF) {
                    $var[] = $results->GetRowAssoc($ToUpper = false);
                }
            }
            //  $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
            //          FROM planes as a
            //          WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()";
        } else {
            $query = "SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                      FROM planes as a, userpermisos_centro_autorizacion as b
                      WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
                      and b.usuario_id=" . UserGetUID() . "
                      and b.plan_id=a.plan_id
											order by a.plan_descripcion";
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

    /**
     * Elige la accion segun el plan elegido
     * @access public
     * @return boolean
     */
    function TiposPlanes() {
        if (empty($_SESSION['CENTROAUTORIZACION']['EMPRESA'])) {
            /* if(empty($_SESSION['SEGURIDAD']['CENTROAUTORIZACION']['puntos'][$_REQUEST['Centro']['empresa_id']][$_REQUEST['Centro']['plan_id']]))
              {
              $this->error = "Error de Seguridad.";
              $this->mensajeDeError = "Violaci?n a la Seguridad.";
              return false;
              } */
            $_SESSION['CENTROAUTORIZACION']['EMPRESA'] = $_REQUEST['Centro']['empresa_id'];
            $_SESSION['CENTROAUTORIZACION']['PLAN'] = $_REQUEST['Centro']['plan_id'];
            $_SESSION['CENTROAUTORIZACION']['NIVEL'] = $_REQUEST['Centro']['nivel_autorizador_id'];
            $_SESSION['CENTROAUTORIZACION']['PLANDES'] = $_REQUEST['Centro']['plan_descripcion'];
            $_SESSION['CENTROAUTORIZACION']['RESPONSABLE'] = $_REQUEST['Centro']['nombre_tercero'];
        }

        $this->FormaMenus();
        //$this->FormaMetodoBuscar($Busqueda,$arr,$f);
        return true;
    }

    /**
     * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
     * @ access public
     * @ return boolean
     */
    function ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, $arreglo, $c, $m, $me, $me2) {
        if (empty($Titulo)) {
            $arreglo = $_REQUEST['arreglo'];
            $Cuenta = $_REQUEST['Cuenta'];
            $c = $_REQUEST['c'];
            $m = $_REQUEST['m'];
            $me = $_REQUEST['me'];
            $me2 = $_REQUEST['me2'];
            $mensaje = $_REQUEST['mensaje'];
            $Titulo = $_REQUEST['titulo'];
            $boton1 = $_REQUEST['boton1'];
            $boton2 = $_REQUEST['boton2'];
        }

        $this->salida = ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, array($c, $m, 'user', $me, $arreglo), array($c, $m, 'user', $me2, $arreglo));
        return true;
    }

    /**
     * Llama la forma del menu de facuracion
     * @access public
     * @return boolean
     */
    function DatosEncabezado() {
        list($dbconn) = GetDBconn();
        $query = "select b.razon_social, a.plan_descripcion from empresas as b, planes as a
                                where b.empresa_id='" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'
                                and a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['PLAN'] . "";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $resulta->GetRowAssoc($ToUpper = false);
        return $var;
    }

    /**
     * Busca los diferentes tipos de identificacion de los paciente
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
                $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
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
     *
     */
    /* function ListadoOrdenes()
      {
      $NUM=$_REQUEST['Of'];
      if(!$NUM)
      {   $NUM='0';   }
      $limit=$this->limit;

      list($dbconn) = GetDBconn();
      $query = "select distinct tipo_id_paciente, paciente_id, nombres, usuario_id
      from
      (
      select tipo_id_paciente, paciente_id, nombres,sw_prioridad, usuario_id
      from
      (
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a, hc_evoluciones as i,
      ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN'].") ,
      pacientes as k, departamentos as l, servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and a.evolucion_id is not null
      and a.evolucion_id=i.evolucion_id
      and i.ingreso=j.ingreso
      and j.departamento_actual=l.departamento
      and l.servicio=m.servicio
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      union
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a,
      hc_os_solicitudes_manuales as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."),
      pacientes as k,
      servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and j.hc_os_solicitud_id=a.hc_os_solicitud_id
      and a.evolucion_id is null
      and m.servicio=j.servicio
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      ) as a order by sw_prioridad desc, tipo_id_paciente, paciente_id
      ) as a LIMIT $limit OFFSET $NUM";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      while (!$result->EOF)
      {
      $vars[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();
      return $vars;
      }

      function RecordSearch()
      {
      list($dbconn) = GetDBconn();
      $query = "select distinct tipo_id_paciente, paciente_id, nombres, usuario_id
      from
      (
      select tipo_id_paciente, paciente_id, nombres,sw_prioridad, usuario_id
      from
      (
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a, hc_evoluciones as i,
      ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN'].") ,
      pacientes as k, departamentos as l, servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and a.evolucion_id is not null
      and a.evolucion_id=i.evolucion_id
      and i.ingreso=j.ingreso
      and j.departamento_actual=l.departamento
      and l.servicio=m.servicio
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      union
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a,
      hc_os_solicitudes_manuales as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."),
      pacientes as k,
      servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and j.hc_os_solicitud_id=a.hc_os_solicitud_id
      and a.evolucion_id is null
      and m.servicio=j.servicio
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      ) as a order by sw_prioridad desc, tipo_id_paciente, paciente_id
      ) as a";
      if(!empty($query))
      {
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      $vars=$result->RecordCount();
      $result->Close();
      }
      return $vars;
      }
     */
    /**
     *
     * @access public
     * @return boolean
     */
    /*  function Buscar()
      {
      list($dbconn) = GetDBconn();
      $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=".UserGetUID()."";
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      unset($_SESSION['CENTROAUTORIZACION']['DATOS']);
      unset($_SESSION['CENTROAUTORIZACION']['paciente_id']);
      unset($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']);

      $tipo_documento=$_REQUEST['TipoDocumento'];
      $documento=$_REQUEST['Documento'];
      $nombres = strtolower($_REQUEST['Nombres']);
      $servicio=$_REQUEST['Servicio'];
      $tipo=$_REQUEST['Tipo'];

      $filtroTipoDocumento = '';
      $filtroDocumento='';
      $filtroNombres='';
      $filtroServicio1='';
      $filtroServicio2='';
      $filtroTipo='';
      $filtroSolicitud='';

      if(!empty($_REQUEST['Solicitud']))
      {   $filtroSolicitud=" AND a.hc_os_solicitud_id = '".$_REQUEST['Solicitud']."'";   }


      if(!empty($tipo_documento))
      {   $filtroTipoDocumento=" AND j.tipo_id_paciente = '$tipo_documento'";   }

      if ($documento != '')
      {   $filtroDocumento =" AND j.paciente_id LIKE '$documento%'";   }

      if ($nombres != '')
      {
      $a=explode(' ',$nombres);
      foreach($a as $k=>$v)
      {
      if(!empty($v))
      {
      $filtroNombres.=" and (upper(k.primer_nombre||' '||k.segundo_nombre||' '||
      k.primer_apellido||' '||k.segundo_apellido) like '%".strtoupper($v)."%')";
      }
      }
      }

      if ($servicio != -1)
      {
      $filtroServicio1 ="and l.servicio='$servicio'";
      $filtroServicio2 ="and m.servicio='$servicio'";
      }
      if ($tipo != -1)
      {   $filtroTipo ="and  a.os_tipo_solicitud_id='$tipo'";   }

      if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

      if(empty($_REQUEST['paso']))
      {
      $query = "select distinct tipo_id_paciente, paciente_id, nombres, usuario_id
      from
      (
      select tipo_id_paciente, paciente_id, nombres,sw_prioridad, usuario_id
      from
      (
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a, hc_evoluciones as i,
      ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN'].") ,
      pacientes as k, departamentos as l, servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and a.evolucion_id is not null
      and a.evolucion_id=i.evolucion_id
      and i.ingreso=j.ingreso
      and j.departamento_actual=l.departamento
      and l.servicio=m.servicio
      $filtroTipo
      $filtroServicio2
      $filtroTipoDocumento $filtroDocumento
      $filtroNombres
      $filtroSolicitud
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      union
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a,
      hc_os_solicitudes_manuales as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."),
      pacientes as k,
      servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and a.hc_os_solicitud_id=j.hc_os_solicitud_id
      and a.evolucion_id is null
      and m.servicio=j.servicio
      $filtroTipo
      $filtroServicio2
      $filtroTipoDocumento $filtroDocumento
      $filtroNombres
      $filtroSolicitud
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      ) as a order by sw_prioridad desc, tipo_id_paciente, paciente_id
      ) as a";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al buscar";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if(!$result->EOF)
      {
      $_SESSION['SPY2']=$result->RecordCount();
      }
      $result->Close();
      }

      $query = "select distinct tipo_id_paciente, paciente_id, nombres, usuario_id
      from
      (
      select tipo_id_paciente, paciente_id, nombres,sw_prioridad, usuario_id
      from
      (
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a, hc_evoluciones as i,
      ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN'].") ,
      pacientes as k, departamentos as l, servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and a.evolucion_id is not null
      and a.evolucion_id=i.evolucion_id
      and i.ingreso=j.ingreso
      and j.departamento_actual=l.departamento
      and l.servicio=m.servicio
      $filtroTipo
      $filtroServicio2
      $filtroTipoDocumento $filtroDocumento
      $filtroNombres
      $filtroSolicitud
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      union
      (
      select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id
      from (select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres
      from hc_os_solicitudes as a,
      hc_os_solicitudes_manuales as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."),
      pacientes as k,
      servicios as m
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and a.sw_estado='1'
      and a.hc_os_solicitud_id=j.hc_os_solicitud_id
      and a.evolucion_id is null
      and m.servicio=j.servicio
      $filtroTipo
      $filtroServicio2
      $filtroTipoDocumento $filtroDocumento
      $filtroNombres
      $filtroSolicitud
      and j.tipo_id_paciente=k.tipo_id_paciente
      and j.paciente_id=k.paciente_id
      order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
      ) as a
      )
      ) as a order by sw_prioridad desc, tipo_id_paciente, paciente_id
      ) as a LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al buscar";
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
      } */

    /**
     *
     */
    function TiposSolicitud() {
        list($dbconn) = GetDBconn();
        $query = "select os_tipo_solicitud_id, descripcion from os_tipos_solicitudes";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    /**
     *
     */
    function TiposServicios() {
        list($dbconn) = GetDBconn();
        $query = "select servicio, descripcion from servicios where sw_asistencial=1";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$result->EOF) {
            $vars[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $vars;
    }

    /**
     *
     */
    /* function DetalleSolicitud()
      {
      unset($_SESSION['CENTROAUTORIZACION']['ARREGLO']);
      if(!empty($_REQUEST['plan']))
      {   $_SESSION['CENTROAUTORIZACION']['PLAN']=$_REQUEST['plan'];   }
      if(empty($_SESSION['CENTROAUTORIZACION']['paciente_id'])
      OR empty($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']))
      {
      $_SESSION['CENTROAUTORIZACION']['paciente_id']=$_REQUEST['paciente'];
      $_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']=$_REQUEST['tipoid'];
      $_SESSION['CENTROAUTORIZACION']['nombre_paciente']=$_REQUEST['nombre'];
      }
      list($dbconn) = GetDBconn();
      //OJO REVISAR AQUI
      $query = "select a.* from(
      (select i.ingreso, a.cantidad,a.hc_os_solicitud_id,    a.cargo as cargos,
      p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente,
      j.paciente_id,
      k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
      a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
      a.sw_estado,

      case a.sw_ambulatorio when 1 then '3' else l.servicio end as servicio,
      --l.servicio,
      p.descripcion,
      case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
      --m.descripcion as desserv,
      case a.sw_ambulatorio when 1 then '' else l.descripcion end as despto,
      --l.descripcion as despto,


      g.descripcion as desos,
      i.fecha,
      NULL as profesional,NULL as prestador,NULL as observaciones,
      p.nivel_autorizador_id as nivel
      from hc_os_solicitudes as a, planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
      ingresos as j,
      pacientes as k, departamentos as l, servicios as m,
      cups as p
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']." and p.cargo=a.cargo  and a.plan_id=f.plan_id
      and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null and a.evolucion_id=i.evolucion_id
      and i.ingreso=j.ingreso  and i.fecha_cierre is not null
      and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
      and a.sw_estado='1'
      and j.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      and j.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."' and i.departamento=l.departamento and l.servicio=m.servicio
      order by m.servicio, j.tipo_id_paciente, j.paciente_id, a.os_tipo_solicitud_id
      )
      union
      (
      select NULL, a.cantidad,a.hc_os_solicitud_id,a.cargo as cargos,
      p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente,
      b.paciente_id,k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
      a.plan_id,f.plan_descripcion,
      a.os_tipo_solicitud_id,a.sw_estado,
      b.servicio,p.descripcion,
      m.descripcion as desserv,NULL,
      g.descripcion as desos, b.fecha,b.profesional,
      b.prestador,b.observaciones, p.nivel_autorizador_id as nivel
      from hc_os_solicitudes as a,
      planes as f, os_tipos_solicitudes as g, pacientes as k, servicios as m,
      cups as p,
      hc_os_solicitudes_manuales as b
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']." and p.cargo=a.cargo
      and a.plan_id=f.plan_id and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is null
      and b.tipo_id_paciente=k.tipo_id_paciente and b.paciente_id=k.paciente_id
      and b.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      and a.sw_estado='1'
      and b.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."' and b.servicio=m.servicio
      and a.hc_os_solicitud_id=b.hc_os_solicitud_id
      order by m.servicio, b.tipo_id_paciente, b.paciente_id, a.os_tipo_solicitud_id
      )
      ) as a order by a.plan_id, a.servicio";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      while(!$result->EOF)
      {
      $vars[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();
      //detalle de los otros planes
      //OJO REVISAR AQUI
      $query = "(select i.ingreso, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente, j.paciente_id,
      k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion,a.os_tipo_solicitud_id, a.sw_estado,n.cargo,n.tarifario_id, h.descripcion,l.servicio, m.descripcion as desserv, g.descripcion as desos, i.fecha,r.grupo_tarifario_id as grupoc, r.subgrupo_tarifario_id as subgrupoc,
      NULL as profesional,NULL as prestador,NULL as observaciones, l.descripcion as despto, p.nivel_autorizador_id as nivel
      from hc_os_solicitudes as a left join tarifarios_equivalencias as n on(n.cargo_base=a.cargo) left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id)
      left join plan_tarifario as r on (r.plan_id<>".$_SESSION['CENTROAUTORIZACION']['PLAN']." and h.grupo_tarifario_id=r.grupo_tarifario_id and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id),
      planes as f, os_tipos_solicitudes as g, hc_evoluciones as i, ingresos as j, pacientes as k, departamentos as l, servicios as m, cups as p,
      userpermisos_centro_autorizacion as q
      where q.usuario_id=".UserGetUID()." and (a.plan_id=q.plan_id or q.sw_todos_planes=1)
      and a.plan_id <>".$_SESSION['CENTROAUTORIZACION']['PLAN']." and p.cargo=a.cargo and a.sw_estado='1' and a.plan_id=f.plan_id and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null
      and a.evolucion_id=i.evolucion_id and i.ingreso=j.ingreso and j.estado=1 and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
      and j.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      and j.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."' and i.departamento=l.departamento and l.servicio=m.servicio
      order by a.plan_id, m.servicio
      )
      UNION
      ( select NULL,a.hc_os_solicitud_id,a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente, b.paciente_id,
      k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,a.sw_estado, n.cargo,n.tarifario_id,h.descripcion, b.servicio,m.descripcion as desserv,g.descripcion as desos, b.fecha,r.grupo_tarifario_id as grupoc, r.subgrupo_tarifario_id as subgrupoc,
      b.profesional, b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel
      from hc_os_solicitudes as a left join tarifarios_equivalencias as n on(n.cargo_base=a.cargo) left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id) left join plan_tarifario as r on (r.plan_id<>2 and h.grupo_tarifario_id=r.grupo_tarifario_id and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id),
      planes as f, os_tipos_solicitudes as g, pacientes as k, servicios as m, cups as p, hc_os_solicitudes_manuales as b, userpermisos_centro_autorizacion as q
      where q.usuario_id=".UserGetUID()." and (a.plan_id=q.plan_id  or q.sw_todos_planes=1)
      and a.plan_id <>".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      and p.cargo=a.cargo and a.sw_estado='1' and a.plan_id=f.plan_id and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is null
      and b.tipo_id_paciente=k.tipo_id_paciente and b.paciente_id=k.paciente_id
      and b.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      and b.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'
      and b.servicio=m.servicio and a.hc_os_solicitud_id=b.hc_os_solicitud_id
      order by a.plan_id, m.servicio)";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      while(!$result->EOF)
      {
      $vars2[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();
      //autorizaciones
      $query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, e.numero_orden_id,
      case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
      e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
      k.nombre as autorizador,
      p.plan_descripcion,j.sw_estado
      from os_ordenes_servicios as a,
      os_maestro as e,
      tipos_afiliado as b, servicios as c, cups as f, autorizaciones as j, system_usuarios as k,
      planes as p, hc_os_solicitudes as z
      where 	a.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'
      and a.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      and a.orden_servicio_id=e.orden_servicio_id
      and a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
      and a.plan_id=p.plan_id
      and z.hc_os_solicitud_id=e.hc_os_solicitud_id
      and z.os_tipo_solicitud_id<>'CIT'
      and e.cargo_cups=f.cargo
      and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
      and j.usuario_id=k.usuario_id
      and e.sw_estado in('1','2','3','7')
      order by a.orden_servicio_id desc";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Guardar en la Tabal2 autorizaiones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      while(!$result->EOF)
      {
      $vars3[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();

      //no autorizaciones
      $query = " (
      select a.evolucion_id,h.observaciones, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
      p.nivel_autorizador_id, p.sw_pos, k.tipo_id_paciente, k.paciente_id,
      k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion,
      a.os_tipo_solicitud_id, a.sw_estado, l.servicio, p.descripcion,
      m.descripcion as desserv, g.descripcion as desos, i.fecha,
      l.descripcion as despto, q.nombre_tercero, a.cantidad
      from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
      planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
      pacientes as k, departamentos as l, servicios as m, cups as p, terceros as q,
      ingresos as r
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']." and
      f.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']." and
      k.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."' and
      k.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'
      and  r.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."' and
      r.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'
      and i.ingreso=r.ingreso
      and h.sw_estado='1'
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
      where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']." and
      f.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']." and
      b.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."' and
      b.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."' and
      k.tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."' and
      k.paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."' and
      a.hc_os_solicitud_id=e.hc_os_solicitud_id and
      (e.autorizacion_int=h.autorizacion or e.autorizacion_ext=h.autorizacion) and
      a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and
      a.evolucion_id is null
      and a.hc_os_solicitud_id=b.hc_os_solicitud_id
      and a.plan_id=f.plan_id
      and h.sw_estado='1 '       and
      b.servicio=m.servicio and
      p.cargo=a.cargo and
      f.tipo_tercero_id=q.tipo_id_tercero and
      f.tercero_id=q.tercero_id
      )";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Guardar en la Tabal autorizaiones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      while(!$result->EOF)
      {
      $vars4[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();

      $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE']=$vars;
      $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE2']=$vars2;
      $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE3']=$vars3;
      $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE4']=$vars4;

      $query = "SELECT * FROM hc_os_autorizaciones_proceso
      WHERE tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      AND paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'
      AND plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      AND usuario_id=".UserGetUID()."";
      $result = $dbconn->Execute($query);
      if($result->EOF)
      {
      $query = "INSERT INTO hc_os_autorizaciones_proceso(
      tipo_id_paciente,
      paciente_id,
      usuario_id,
      plan_id,
      fecha_registro,
      sw_estado)
      VALUES('".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."','".$_SESSION['CENTROAUTORIZACION']['paciente_id']."',".UserGetUID().",'".$_SESSION['CENTROAUTORIZACION']['PLAN']."','now()','1')";
      $results=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      $results->Close();
      }
      $result->Close();
      $this->FormaDetalle();
      return true;
      }
     */

    //---------------------------------AUTORIZACION----------------------------------------

    /**
     *
     */
    /* function PedirAutorizacion()
      {
      //valida si eligieron algun cargo
      $f=0;
      foreach($_REQUEST as $k => $v)
      {
      if(substr_count($k,'Auto'))
      {
      $f=1;
      }
      }
      if($f==0)
      {
      $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir alguna Solicitud para Autorizar.";
      $this->FormaDetalle();
      return true;
      }

      unset($_SESSION['AUTORIZACIONES']);
      foreach($_REQUEST as $k => $v)
      {
      if(substr_count($k,'Auto'))
      {
      $arr=explode(',',$v);
      //4 solicitu_id, 0 cargo, 1 tarifario, 3 servicio, 2 descr 5 cups
      if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']))
      {   $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']=$_REQUEST['ingreso'];   }
      $_SESSION['CENTROAUTORIZACION']['SERVICIO']=$arr[3];
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'][$arr[4]][$arr[0]][$arr[1]][$arr[3]][$arr[5]]=$arr[2];
      }
      }

      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['CENTROAUTORIZACION']['paciente_id'];
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'];
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CENTROAUTORIZACION';
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['CENTROAUTORIZACION']['PLAN'];
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
      $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
      $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='CentroAutorizacion';
      $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
      $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

      $this->ValidarCentroAutorizacion();
      return true;
      } */

    /*
      function RetornoAutorizacion()
      {
      $_SESSION['CENTROAUTORIZACION']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
      $_SESSION['CENTROAUTORIZACION']['rango']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
      $_SESSION['CENTROAUTORIZACION']['semanas']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
      $Mensaje=$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
      $_SESSION['CENTROAUTORIZACION']['ext']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
      $_SESSION['CENTROAUTORIZACION']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
      $_SESSION['CENTROAUTORIZACION']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
      $_SESSION['CENTROAUTORIZACION']['observacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['ObservacionesOS'];

      list($dbconn) = GetDBconn();
      $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE
      tipo_id_paciente='".$_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']."'
      AND paciente_id='".$_SESSION['CENTROAUTORIZACION']['paciente_id']."'
      AND plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      AND usuario_id=".UserGetUID()."";
      $dbconn->Execute($query);

      if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['TRAMITE']))
      {
      $Mensaje = 'La toma de requerimientos se realizo.';
      $accion=ModuloGetURL('app','CentroAutorizacion','user','DetalleSolicitud');
      if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
      return false;
      }
      return true;
      }

      //si no fue autorizada
      if(!empty($_SESSION['AUTORIZACIONES']['NOAUTO']))
      {  $noauto=", sw_no_autorizado='1' ";  }

      //	unset($_SESSION['AUTORIZACIONES']);

      if(empty($_SESSION['CENTROAUTORIZACION']['Autorizacion'])
      AND empty($_SESSION['CENTROAUTORIZACION']['NumAutorizacion']))
      {
      if(empty($_SESSION['CENTROAUTORIZACION']['NumAutorizacion']))
      {   $Mensaje = 'No se pudo realizar la Autorizaci?n para la Orden.';   }
      $accion=ModuloGetURL('app','CentroAutorizacion','user','DetalleSolicitud');
      if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
      return false;
      }
      return true;
      }

      $query = "select a.hc_os_solicitud_id
      from hc_os_autorizaciones as a
      where (a.autorizacion_int=".$_SESSION['CENTROAUTORIZACION']['Autorizacion']." OR
      a.autorizacion_ext=".$_SESSION['CENTROAUTORIZACION']['Autorizacion'].")";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error select ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      while(!$result->EOF)
      {
      $query = "UPDATE hc_os_solicitudes SET sw_estado='0' $noauto
      WHERE hc_os_solicitud_id=".$result->fields[0]."";
      $results=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error UPDATE  hc_os_solicitudes ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
      }
      $result->MoveNext();
      $results->Close();
      }
      $result->Close();

      if(!empty($_SESSION['CENTROAUTORIZACION']['Autorizacion'])
      AND empty($_SESSION['CENTROAUTORIZACION']['NumAutorizacion']))
      {
      $Mensaje = 'No se Autorizo la Orden.';
      $accion=ModuloGetURL('app','CentroAutorizacion','user','FormaMetodoBuscar');
      if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
      return false;
      }
      return true;
      }

      $query = "(
      SELECT a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargo as cargos,
      a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
      h.descripcion, r.descripcion as descar, q.servicio
      FROM
      (
      SELECT e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, NULL as servicio, e.departamento, b.cargo as cargo_base
      FROM
      hc_os_autorizaciones as a,
      hc_os_solicitudes as b,
      hc_evoluciones as e,
      tarifarios_equivalencias as n
      WHERE
      a.autorizacion_int = ".$_SESSION['CENTROAUTORIZACION']['Autorizacion']."
      AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
      AND b.plan_id = ".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      AND e.evolucion_id = b.evolucion_id
      AND n.cargo_base = b.cargo
      ) AS a,
      cups as r,
      departamentos as q,
      tarifarios_detalle as h,
      plan_tarifario as z
      WHERE
      r.cargo = a.cargo_base
      AND q.departamento = a.departamento
      AND (h.tarifario_id = a.tarifario_id AND h.cargo = a.cargo)
      AND z.plan_id = ".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      AND z.grupo_tarifario_id = h.grupo_tarifario_id
      AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
      AND h.tarifario_id = z.tarifario_id
      )
      UNION
      (
      SELECT  a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargo as cargos,
      a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
      h.descripcion, r.descripcion as descar, a.servicio
      FROM
      (
      SELECT e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, e.servicio, e.departamento, b.cargo as cargo_base
      FROM
      hc_os_autorizaciones as a,
      hc_os_solicitudes as b,
      hc_os_solicitudes_manuales as e,
      tarifarios_equivalencias as n
      WHERE
      a.autorizacion_int = ".$_SESSION['CENTROAUTORIZACION']['Autorizacion']."
      AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
      AND b.plan_id = ".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      AND e.hc_os_solicitud_id = b.hc_os_solicitud_id
      AND n.cargo_base = b.cargo
      ) AS a LEFT JOIN departamentos as q ON (q.departamento=a.departamento),
      cups as r,
      tarifarios_detalle as h,
      plan_tarifario as z
      WHERE
      r.cargo = a.cargo_base
      AND (h.tarifario_id = a.tarifario_id AND h.cargo = a.cargo)
      AND z.plan_id = ".$_SESSION['CENTROAUTORIZACION']['PLAN']."
      AND z.grupo_tarifario_id = h.grupo_tarifario_id
      AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
      AND h.tarifario_id = z.tarifario_id
      )";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error select ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      while(!$result->EOF)
      {
      $var[]=$result->GetRowAssoc($ToUpper = false);
      $result->MoveNext();
      }
      $result->Close();
      $this->FormaListadoCargos($var);
      return true;
      } */

    /* Esta funcion retorna la unidad funcional asociada al departamento de donde se solicita el apoyo "Nicolas CAballero" <nicolas.caballero@duanaltda.com> */
    function ObtenerUnidadFuncional($solicitud_id) {

        list($dbconn) = GetDBconn();

        $query = "SELECT hcos.hc_os_solicitud_id,
                                hcos.evolucion_id,
				hce.departamento,
				dep.empresa_id,
				dep.centro_utilidad,
				dep.unidad_funcional
			FROM public.hc_os_solicitudes AS hcos
				INNER JOIN public.hc_evoluciones AS hce
				    ON hcos.evolucion_id=hce.evolucion_id
				INNER JOIN public.departamentos AS dep
				    ON hce.departamento=dep.departamento
			WHERE hcos.hc_os_solicitud_id=$solicitud_id;";

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
    
    function ObtenerUnidadFuncionalManual($solicitud_id) {

        list($dbconn) = GetDBconn();

        $query = "SELECT hcos.hc_os_solicitud_id, hcos.evolucion_id, hce.departamento,dep.empresa_id,dep.centro_utilidad,	dep.unidad_funcional
                  FROM public.hc_os_solicitudes AS hcos
                            INNER JOIN public.hc_os_solicitudes_manuales AS hce
                                ON hcos.hc_os_solicitud_id=hce.hc_os_solicitud_id
                            INNER JOIN public.departamentos AS dep
                                ON hce.departamento=dep.departamento
                  WHERE hcos.hc_os_solicitud_id=$solicitud_id;";

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

    /*
     * Esta funcion devuelve el combo con los proveedores realcionados con los cargos a autorizar.
     * MODIFICACION: Se agrego un parametro adicional para la funcion, con la finalidad de filtrar el proveedor por la unidad funcional asociada al departamento "Nicolas CAballero" <nicolas.caballero@duanaltda.com>
     */

    function ComboProveedor($Cargo, $solicitud_id=null) {
        //25309335
        $x = " and a.empresa_id='" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'";
        
        //Campo agregado con la nueva modificacion
        if (empty($solicitud_id)) {
            
        } else {
            $datos_filtro = $this->ObtenerUnidadFuncional($solicitud_id);
        }
        if (count($datos_filtro)==0){
            $datos_filtro = $this->ObtenerUnidadFuncionalManual($solicitud_id);
        }
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        /* $query = "SELECT a.tipo_id_tercero, a.tercero_id, a.cargo,  c.plan_proveedor_id, c.empresa_id,
          c.plan_descripcion
          FROM terceros_proveedores_cargos as a, planes_proveedores as c,terceros_proveedores_servicios_salud as b
          WHERE b.empresa_id='".$_SESSION['CENTROAUTORIZACION']['EMPRESA']."'
          and b.tipo_id_tercero=a.tipo_id_tercero
          and b.tercero_id=a.tercero_id
          and b.estado='1'
          and a.empresa_id=b.empresa_id
          and a.sw_estado='1'
          and a.cargo='$Cargo'
          and c.tipo_id_tercero=a.tipo_id_tercero
          and c.tercero_id=a.tercero_id"; */

        //Este query2 es el original antes de la modificacion de la central "Nicolas CABallero" <nicolas.caballero@duanaltda.com>								
        $query2 = "select 	a.tipo_id_tercero, 
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
							and 	a.empresa_id='" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'
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

        //Nuevo query para el combo de proveedor con la modificacion de la central "Nicolas CABallero" <nicolas.caballero@duanaltda.com>
        //departamentos_cargos                    AS depcar ,
        //departamentos                           AS dep ,
        /*
          AND ppuf.centro_utilidad=dep.centro_utilidad
          AND ppuf.unidad_funcional=dep.unidad_funcional
          AND ppuf.empresa_id=dep.empresa_id
          AND ppuf.empresa_id=dep.empresa_id
          AND a.cargo=depcar.cargo
         */
        //unif.unidad_funcional,
        //unif.descripcion,
        $query = "
					SELECT
    					           a.tipo_id_tercero,
						    a.tercero_id,
						    a.cargo,
						    c.plan_proveedor_id,
						    c.empresa_id,
						    unif.centro_utilidad,
						    
						    c.plan_descripcion,
						    
						    ter.nombre_tercero,
						    ppuf.direccion,
						    ppuf.telefono,
						    MIN (ROUND((e.precio + (e.precio * f.porcentaje) / 100),0)) AS valor_cargo
					FROM
						    terceros_proveedores_cargos          AS a,
						    planes_proveedores                   AS c,
						    terceros_proveedores_servicios_salud AS b,
						    tarifarios_equivalencias d,
						    tarifarios_detalle e,
						    plan_tarifario_proveedores f ,
						    planes_proveedores_unidades_funcionales AS ppuf ,
						    
						    terceros                                AS ter,
						    unidades_funcionales                    AS unif
					WHERE
					    a.cargo='$Cargo'
						and 	a.empresa_id='" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'";

        if (empty($solicitud_id)) {
            
        } else {
            if(isset($_SESSION['CENTROAUTORIZACION']['CENTROUTILIDAD'])){
                if(!(empty($_SESSION['CENTROAUTORIZACION']['CENTROUTILIDAD']))){
                    $query .= "		AND unif.centro_utilidad='" .$_SESSION['CENTROAUTORIZACION']['CENTROUTILIDAD']. "'";
                }
            }else{
                $query .= "		AND unif.centro_utilidad='" . $datos_filtro[0][centro_utilidad] . "'
    									AND unif.unidad_funcional='" . $datos_filtro[0][unidad_funcional] . "' ";
            }
        }



        $query .= "
					AND ppuf.empresa_id=unif.empresa_id
					AND ppuf.centro_utilidad=unif.centro_utilidad
					AND ppuf.unidad_funcional=unif.unidad_funcional					
					
					AND a.tercero_id=ter.tercero_id
					AND a.tipo_id_tercero=ter.tipo_id_tercero
					
					AND ppuf.plan_proveedor_id=c.plan_proveedor_id
					
					AND c.tipo_id_tercero=a.tipo_id_tercero
					AND c.tercero_id=a.tercero_id
					AND b.empresa_id=a.empresa_id
					AND b.tipo_id_tercero=a.tipo_id_tercero
					AND b.tercero_id=a.tercero_id
					AND b.estado='1'
					AND a.sw_estado='1'
					AND d.cargo_base = a.cargo
					AND e.cargo = d.cargo
					AND e.tarifario_id = d.tarifario_id
					AND f.tarifario_id = e.tarifario_id
					AND f.grupo_tarifario_id = e.grupo_tarifario_id
					AND f.subgrupo_tarifario_id = e.subgrupo_tarifario_id
					AND f.plan_proveedor_id = c.plan_proveedor_id
						GROUP BY
						    1,2,3,4,5,6,7,8,9,10
						ORDER BY
						    valor_cargo
			"; //AND a.empresa_id='".$_SESSION['CENTROAUTORIZACION']['EMPRESA']."'

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

    /* unidades funcionales Naydu */

    function ComboDepartamento($Cargo, $solicitud) {//OJO REVISAR AQUI
        
        IncludeLib('malla_validadora');
        $x = " and b.empresa_id='" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'";

        list($dbconn) = GetDBconn();
        $dat = '';
        $dat = DatosSolicitud($solicitud);
        $filtro = ModuloGetVar('app', 'CentroAutorizacion', 'filtro_os');

        //es una solicitud manual y no eligieron el depto y se hace igual como si fuera por empresa
        if (empty($dat) OR $filtro == 'empresa') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo'
											and b.departamento=a.departamento $x";
        } elseif ($filtro == 'centro') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo' 
											and b.departamento=a.departamento $x";
            //and b.centro_utilidad='".$dat[centro_utilidad]."'			
        } elseif ($filtro == 'unidad') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo' 
											and b.departamento=a.departamento $x";
            //and b.unidad_funcional='".$dat[unidad_funcional]."'	
        } elseif ($filtro == 'departamento') {
            $query = "select a.departamento, a.cargo, b.descripcion
											from departamentos_cargos as a, departamentos as b
											where a.cargo='$Cargo' 
											and b.departamento=a.departamento $x";
            //and b.departamento='".$dat[departamento]."'					
        }
        /* list($dbconn) = GetDBconn();
          $query = "select a.departamento, a.cargo, b.descripcion
          from departamentos_cargos as a, departamentos as b
          where a.cargo='$Cargo'
          and b.departamento=a.departamento
          $x"; */
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

    /**
     *
     */
    function CrearOrdenServicio() {

        list($dbconn) = GetDBconn();
        $datos = $_SESSION['CENTRO_AUTORIZACION']['DATOS'];
        unset($_SESSION['CENTRO_AUTORIZACION']['DATOS']);
        //$dbconn->debug=true;

        if (!empty($_REQUEST['cancelar'])) {
            if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
                $auto = $_SESSION['CENTROAUTORIZACION']['Autorizacion'];
            } else {
                $auto = $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'];
            }

            $dbconn->BeginTrans();
            $query = "select a.hc_os_solicitud_id from hc_os_autorizaciones as a
                              where (a.autorizacion_int=" . $auto . " OR
                              a.autorizacion_ext=" . $auto . ")";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error select ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while (!$result->EOF) {
                /*
                  $query = "UPDATE hc_os_solicitudes SET
                  sw_estado = 1
                  WHERE hc_os_solicitud_id=" . $result->fields[0] . "";
                  $dbconn->Execute($query);
                  if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error UPDATE  hc_os_solicitudes ";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $dbconn->RollbackTrans();
                  return false;
                  }
                 */
                $query = "DELETE FROM hc_os_autorizaciones
                                  WHERE hc_os_solicitud_id=" . $result->fields[0] . "";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM hc_os_licitudes ";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                if ($result->RecordCount() > 0) {
                    $result->MoveNext();
                }
            }
            $result->Close();
            $dbconn->CommitTrans();
            if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
                $this->DetalleSolicitud();
            } else {
                $this->DetalleSolicituTodos();
            }
            return true;
        }

        //va hacer la transcripcion
        if (!empty($_REQUEST['Transcripcion'])) {

            $this->CrearTranscripcion();
            return true;
        }

        if (!empty($_REQUEST['Trans'])) {
            $this->frmError["MensajeError"] = "ERROR: Debe Hacer Primero la Transcripci?n.";
            $this->FormaListadoCargos($datos);
            return true;
        }
        /* validacion punto de tomado naydu */

        if (!empty($_REQUEST['punto_tomado']) && $_REQUEST['opc_unidad_funcional'] == -1) {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe selecionar el Punto de Tomado.";
            $this->FormaListadoCargos($datos);
            return true;
        }


        $f = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Combo')) {
                if ($v == -1) {
                    $f = 1;
                }
            }
        }
        if ($f == 1) {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir algun Departamento o Proveedor del Cargo.";
            $this->FormaListadoCargos($datos);
            return true;
        }


        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Combo')) {
                if ($v != -1) {    //0 hc_os_solicitud_id
                    $arr = explode(',', $v);
                    $d = 0;
                    foreach ($_REQUEST as $ke => $va) {
                        if (substr_count($ke, 'Op')) {    // 0 solicitud_id
                            $var = explode(',', $va);
                            if ($var[0] == $arr[0]) {
                                $d = 1;
                            }
                        }
                    }
                    if ($d == 0) {
                        $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir algun Cargo.";
                        $this->FormaListadoCargos($datos);
                        return true;
                    }
                }
            }
        }

        $auto = $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'];
        $plan = $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'];
        $rango = $_SESSION['CENTROAUTORIZACION']['TODO']['rango'];
        $empresa = $_SESSION['CENTROAUTORIZACION']['EMPRESA'];
        $afiliado = $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_afiliado_id'];
        $semana = $_SESSION['CENTROAUTORIZACION']['TODO']['semanas'];
        $paciente = $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'];
        $tipo = $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'];
        $msg = $_SESSION['CENTROAUTORIZACION']['TODO']['observacion'];
        $servicio = $_SESSION['CENTROAUTORIZACION']['TODO']['SERVICIO'];
        if (empty($_SESSION['CENTROAUTORIZACION']['TODO']['ext'])) {
            $ext = 'NULL';
        } else {
            $ext = $auto;
        }
        $var_unidadfuncional = array();
        if (!empty($_REQUEST['punto_tomado'])) {
            $var_unidadfuncional = explode('|', $_REQUEST['opc_unidad_funcional']);
            $var_unidadfuncional[0] = "'" . $var_unidadfuncional[0] . "'";
            $var_unidadfuncional[1] = "'" . $var_unidadfuncional[1] . "'";
            $var_unidadfuncional[2] = "'" . $var_unidadfuncional[2] . "'";
        } else {
            $var_unidadfuncional[0] = 'null';
            $var_unidadfuncional[1] = 'null';
            $var_unidadfuncional[2] = 'null';
        }

        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Combo')) {      //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad (10 direccion 11 telefono)
                $arr = explode(',', $v);
                //lo cambio lorena porque no se sabe porque se concateno eso asi, y lo hizo alex 
                //$arreglo[$arr[1]][]=$v;
                $arreglo[$arr[1] . '-' . $arr[9]][] = $v;
                $eventos_soat[$arr[1] . '-' . $arr[9]] = $arr[9];

                //Modificado por "Nicolas CAballero" <nicolas.caballero@duanaltda.com>
                $direccion[$arr[1] . '-' . $arr[10]] = $arr[10]; //Se agrega la direccion de la unidad funcional del proveedor
                $telefono[$arr[1] . '-' . $arr[11]] = $arr[11];  //Se agrega el telefono de la unidad funcional del proveedor											
            }
        }

        /* validacion punto de tomado JONIER */

        foreach ($arreglo as $key => $value) {
            $cargostem = array();
            $cargostem = explode(',', $value[0]);

            if (!empty($_REQUEST['punto_tomado' . $cargostem[5]]) && $_REQUEST['opc_unidad_funcional' . $cargostem[5]] == -1) {
                $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe selecionar el Punto de Tomado.";
                $this->FormaListadoCargos($datos);
                return true;
            }
        }

        foreach ($arreglo as $key => $value) {

            $cargostem = array();
            $cargostem = explode(',', $value[0]);

            $var_unidadfuncional = array();
            if (!empty($_REQUEST['punto_tomado' . $cargostem[5]])) {
                $var_unidadfuncional = explode('|', $_REQUEST['opc_unidad_funcional' . $cargostem[5]]);
                $var_unidadfuncional[0] = "'" . $var_unidadfuncional[0] . "'";
                $var_unidadfuncional[1] = "'" . $var_unidadfuncional[1] . "'";
                $var_unidadfuncional[2] = "'" . $var_unidadfuncional[2] . "'";
            } else {
                $var_unidadfuncional[0] = 'null';
                $var_unidadfuncional[1] = 'null';
                $var_unidadfuncional[2] = 'null';
            }


            if (is_numeric($eventos_soat[$key])) {
                $evento_soat = $eventos_soat[$key];
            } else {
                $evento_soat = 'NULL';
            }

            //AQUI INSERTO LA ORDEN

            $query = "SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
            $result = $dbconn->Execute($query);
            $orden = $result->fields[0];
            //evento soat se inserto para verificar si el paciente viene por algun evento
            /*
              $query = "INSERT INTO os_ordenes_servicios
              (orden_servicio_id,autorizacion_int,autorizacion_ext,plan_id,
              tipo_afiliado_id,rango,semanas_cotizadas,
              servicio,tipo_id_paciente,paciente_id,
              usuario_id,fecha_registro,observacion,
              evento_soat,direccion,telefono)
              VALUES($orden,".$auto.",$ext,".$plan.",
              '".$afiliado."','".$rango."',".$semana.",
              '".$servicio."','".$tipo."','".$paciente."',
              ".UserGetUID().",'now()','".$msg."',
              $evento_soat,'".$arr[10]."','".$arr[11]."')";
             */

            /** punto de tomado Naydu* */
            $query = "INSERT INTO os_ordenes_servicios(
                                orden_servicio_id,autorizacion_int,autorizacion_ext,plan_id,
                                tipo_afiliado_id,rango,semanas_cotizadas,
                                servicio,tipo_id_paciente,paciente_id,
                                usuario_id,fecha_registro,observacion,
                                evento_soat,empresa_id,centro_utilidad,
                                unidad_funcional)
                          VALUES($orden," . $auto . ",$ext," . $plan . ",
                                '" . $afiliado . "','" . $rango . "'," . $semana . ",
                                '" . $servicio . "','" . $tipo . "','" . $paciente . "',
                                " . UserGetUID() . ",'now()','" . $msg . "',
                                $evento_soat," . $var_unidadfuncional[0] . "," . $var_unidadfuncional[1] . ",
                                " . $var_unidadfuncional[2] . ")";
            $dbconn->BeginTrans();
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO os_ordenes_servicios";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $ordenes[] = $orden;
            //DATOS PARA OS_MAESTRO
            for ($i = 0; $i < sizeof($value); $i++) {
                //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                $vect = explode(',', $value[$i]);
                foreach ($_REQUEST as $k => $v) {
                    if (substr_count($k, 'Combo')) {    //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                        //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                        $arr = explode(',', $v);
                        if ($vect[0] == $arr[0]) {
                            //este era un for con la cantidad y nose para q
                            //for($j=0; $j<$arr[8]; $j++)
                            // {
                            $query = "select * from os_tipos_periodos_planes
                                                        where plan_id=" . $plan . "
                                                        and cargo='$arr[5]'";
                            $result = $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error os_tipos_periodos_planes";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            if (!$result->EOF) {
                                $var = $result->GetRowAssoc($ToUpper = false);
                                $Fecha = $this->FechaStamp($arr[6]);
                                $infoCadena = explode('/', $Fecha);
                                $intervalo = $this->HoraStamp($arr[6]);
                                $infoCadena1 = explode(':', $intervalo);
                                $fechaAct = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_tramite_os]), $infoCadena[2]));
                                if ($fechaAct < date("Y-m-d H:i:s")) {
                                    $fechaAct = date("Y-m-d H:i:s");
                                }
                                $Fecha = $this->FechaStamp($fechaAct);
                                $infoCadena = explode('/', $Fecha);
                                $intervalo = $this->HoraStamp($fechaAct);
                                $infoCadena1 = explode(':', $intervalo);
                                $venc = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_vigencia]), $infoCadena[2]));
                                //fecha refrendar
                                $Fecha = $this->FechaStamp($venc);
                                $infoCadena = explode('/', $Fecha);
                                $intervalo = $this->HoraStamp($venc);
                                $infoCadena1 = explode(':', $intervalo);
                                $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_refrendar]), $infoCadena[2]));
                            } else {
                                $query = "select * from os_tipos_periodos_tramites
                                                                where cargo='$arr[5]'";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error os_tipos_periodos_tramites";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }
                                if (!$result->EOF) {
                                    $var = $result->GetRowAssoc($ToUpper = false);
                                    $Fecha = $this->FechaStamp($arr[6]);
                                    $infoCadena = explode('/', $Fecha);
                                    $intervalo = $this->HoraStamp($arr[6]);
                                    $infoCadena1 = explode(':', $intervalo);
                                    $fechaAct = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_tramite_os]), $infoCadena[2]));
                                    if ($fechaAct < date("Y-m-d H:i:s")) {
                                        $fechaAct = date("Y-m-d H:i:s");
                                    }
                                    $Fecha = $this->FechaStamp($fechaAct);
                                    $infoCadena = explode('/', $Fecha);
                                    $intervalo = $this->HoraStamp($fechaAct);
                                    $infoCadena1 = explode(':', $intervalo);
                                    $venc = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_vigencia]), $infoCadena[2]));
                                    //fecha refrendar
                                    $Fecha = $this->FechaStamp($venc);
                                    $infoCadena = explode('/', $Fecha);
                                    $intervalo = $this->HoraStamp($venc);
                                    $infoCadena1 = explode(':', $intervalo);
                                    $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_refrendar]), $infoCadena[2]));
                                } else {
                                    $tramite = ModuloGetVar('app', 'CentroAutorizacion', 'dias_tramite_os');
                                    $vigencia = ModuloGetVar('app', 'CentroAutorizacion', 'dias_vigencia');
                                    $var = $result->GetRowAssoc($ToUpper = false);
                                    $Fecha = $this->FechaStamp($arr[6]);
                                    $infoCadena = explode('/', $Fecha);
                                    $intervalo = $this->HoraStamp($arr[6]);
                                    $infoCadena1 = explode(':', $intervalo);
                                    $fechaAct = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $tramite), $infoCadena[2]));
                                    if ($fechaAct < date("Y-m-d H:i:s")) {
                                        $fechaAct = date("Y-m-d H:i:s");
                                    }
                                    $Fecha = $this->FechaStamp($fechaAct);
                                    $infoCadena = explode('/', $Fecha);
                                    $intervalo = $this->HoraStamp($fechaAct);
                                    $infoCadena1 = explode(':', $intervalo);
                                    $venc = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $vigencia), $infoCadena[2]));
                                    //fecha refrendar
                                    $Fecha = $this->FechaStamp($venc);
                                    $infoCadena = explode('/', $Fecha);
                                    $intervalo = $this->HoraStamp($venc);
                                    $infoCadena1 = explode(':', $intervalo);
                                    $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_refrendar]), $infoCadena[2]));
                                }
                            }//fin else

                            $query = "SELECT nextval('os_maestro_numero_orden_id_seq')";
                            $result = $dbconn->Execute($query);
                            $numorden = $result->fields[0];

                            $query = "INSERT INTO os_maestro
                                                            (numero_orden_id,
                                                            orden_servicio_id,
                                                            sw_estado,
                                                            fecha_vencimiento,
                                                            hc_os_solicitud_id,
                                                            fecha_activacion,
                                                            cantidad,
                                                            cargo_cups,
                                                            fecha_refrendar)
                                    VALUES($numorden,$orden,1,'$venc',$arr[0],'$fechaAct',$arr[8],'$arr[5]','$refrendar')";
                            $dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error INSERT INTO os_maestro";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            } else {
                                foreach ($_REQUEST as $ke => $va) {
                                    if (substr_count($ke, 'Op')) {    // 0 solicitud_id 1 cargo 2 tarifario
                                        $var = explode(',', $va);
                                        if ($var[0] == $arr[0]) {
                                            $query = "INSERT INTO os_maestro_cargos
                                                                                (numero_orden_id,
                                                                                tarifario_id,
                                                                                cargo)
                                                        VALUES($numorden,'$var[2]','$var[1]')";
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error INSERT INTO os_maestro_cargos";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                            }
                                        }
                                    }
                                }

                                //si es interna
                                if ($arr[2] == 'dpto') {
                                    $query = "INSERT INTO os_internas
                                                                                            (numero_orden_id,
                                                                                            cargo,
                                                                                            departamento)
                                                                            VALUES($numorden,'$arr[5]','$arr[1]')";
                                } else {
                                    $query = "INSERT INTO os_externas
                                                                                            (numero_orden_id,
                                                                                            empresa_id,
                                                                                            tipo_id_tercero,
                                                                                            tercero_id,
                                                                                            cargo,
                                                                                            plan_proveedor_id)
                                                                            VALUES($numorden,'" . $empresa . "','$arr[2]','$arr[1]','$arr[5]',$arr[7])";
                                }
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error INTO os_externas o  os_internas";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                            }//else
                            //}//fin for cantidad
                        }
                    }
                }//fin foreach
            }//fin for
        }


        $query = "(SELECT a.autorizacion_int,a.hc_os_solicitud_id
                       FROM hc_os_autorizaciones as a
                       WHERE (a.autorizacion_int=" . $auto . " OR a.autorizacion_ext=" . $auto . "))";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error select ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        //JONIERM
        /*
          while(!$result->EOF)
          {
          $query = "UPDATE hc_os_solicitudes SET
          sw_estado=0
          WHERE hc_os_solicitud_id=".$result->fields[1]."";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error UPDATE  hc_os_solicitudes ";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
          }
          $result->MoveNext();
          }
          $result->Close();
         */
        $dbconn->CommitTrans();
        for ($i = 0; $i < sizeof($ordenes); $i++) {
            $x.=$ordenes[$i];
            if ($i != sizeof($ordenes)) {
                $x.=' - ';
            }
        }
        if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
            $Mensaje = 'La Orden de Servicio No. ' . $x . ' Fue Generada.';
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicitud');
            if (!$this->FormaMensaje($Mensaje, 'ORDENES DE SERVICIO', $accion, '')) {
                return false;
            }
            return true;
        } else {
            $_SESSION['CENTROAUTORIZACION']['TODO']['CambiarEstado'] = '1';
            $Mensaje = 'La Orden de Servicio No DetalleSolicituTodos.... ' . $x . ' Fue Generada.';
			$this->actualizar_departamento();
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicituTodos');
            if (!$this->FormaMensaje($Mensaje, 'ORDENES DE SERVICIO', $accion, '')) {
                return false;
            }
            return true;
        }
    }

    /**
     *
     */
    function DetalleSolicituTodos() {

        unset($_SESSION['CENTROAUTORIZACION']['ARREGLO']);
        list($dbconn) = GetDBconn();


        if ($_SESSION['CENTROAUTORIZACION']['TODO']['CambiarEstado']) {
            $auto = $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'];
            if (empty($_SESSION['CENTROAUTORIZACION']['TODO']['ext'])) {
                $ext = 'NULL';
            } else {
                $ext = $auto;
            }
            $query = "(SELECT a.autorizacion_int,a.hc_os_solicitud_id
                           FROM hc_os_autorizaciones as a
                           WHERE (a.autorizacion_int=" . $auto . " OR a.autorizacion_ext=" . $auto . ")
                           )";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error select ";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while (!$result->EOF) {
                $query = "UPDATE hc_os_solicitudes SET sw_estado=0 WHERE hc_os_solicitud_id=" . $result->fields[1] . "";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error UPDATE  hc_os_solicitudes ";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                $result->MoveNext();
            }
            $result->Close();
            $dbconn->CommitTrans();
            $_SESSION['CENTROAUTORIZACION']['TODO']['CambiarEstado'] = '0';
            $auto = "";
        }

        if (empty($_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id']) AND empty($_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'])) {
            $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] = $_REQUEST['paciente'];
            $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] = $_REQUEST['tipoid'];
            $_SESSION['CENTROAUTORIZACION']['TODO']['nombre_paciente'] = $_REQUEST['nombre'];
        }

        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1 AND !empty($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'])) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }
        //$dbconn->debug=true;
        $query = "SELECT a.cantidad,
                            a.hc_os_solicitud_id,
                            a.cargo as cargos,
                            p.descripcion,
                            p.nivel_autorizador_id as nivel,
                            p.sw_pos,
                            a.plan_id,
                            f.plan_descripcion,
                            f.sw_afiliados,
                            a.os_tipo_solicitud_id,
                            g.descripcion as desos,
                            a.fecha,
                            a.profesional,
                            a.prestador,
                            a.observaciones,
                            case a.sw_ambulatorio when 1 then '3' else a.servicio end as servicio,
                            case a.sw_ambulatorio when 1 then 'AMBULATORIO' else m.descripcion end as desserv,
                            case a.sw_ambulatorio when 1 then '' else a.despto end as despto,
                            a.desc_especialidad
                    FROM (
                            (
                                SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, i.departamento,
                                    l.servicio, l.descripcion as despto, i.fecha, NULL as profesional, NULL as prestador, NULL as observaciones, INT.descripcion as desc_especialidad
                                FROM hc_os_solicitudes a LEFT JOIN ( SELECT   a.hc_os_solicitud_id, b.descripcion
                                                                     FROM hc_os_solicitudes_interconsultas a, especialidades b
                                                                     WHERE	a.especialidad = b.especialidad
                                                                    ) AS INT ON (INT.hc_os_solicitud_id = a.hc_os_solicitud_id),
                                            hc_evoluciones as i, ingresos as j, departamentos as l 
                                WHERE a.evolucion_id IS NOT NULL
                                    AND (a.sw_estado='1'
                                    OR (a.sw_estado = '0' AND a.hc_os_solicitud_id NOT IN ( SELECT  b.hc_os_solicitud_id
                                                                                            FROM    hc_os_solicitudes a, os_maestro b
                                                                                            WHERE   a.hc_os_solicitud_id = b.hc_os_solicitud_id
                                                                                                AND     a.tipo_id_paciente = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
                                                                                                AND     a.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
                                                                                          )))
                                        AND i.evolucion_id = a.evolucion_id
                                        AND j.ingreso=i.ingreso
                                        AND j.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
                                        AND j.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
                                        AND l.departamento=i.departamento
                            )
                            UNION
                            (
                                SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, j.departamento,
                                    j.servicio, l.descripcion as despto, j.fecha, j.profesional, j.prestador, j.observaciones, INT.descripcion as desc_especialidad
                                FROM hc_os_solicitudes a LEFT JOIN (SELECT   a.hc_os_solicitud_id,b.descripcion
                                                                    FROM hc_os_solicitudes_interconsultas a, especialidades b
                                                                    WHERE	a.especialidad = b.especialidad
                                                                    ) AS INT ON (INT.hc_os_solicitud_id = a.hc_os_solicitud_id), hc_os_solicitudes_manuales as j 
                                     LEFT JOIN departamentos as l ON (l.departamento=j.departamento)
                                WHERE a.evolucion_id IS NULL
                                    AND (a.sw_estado='1'
                                    OR (a.sw_estado = '0' 
                                    AND a.hc_os_solicitud_id NOT IN ( SELECT b.hc_os_solicitud_id
                                                                      FROM hc_os_solicitudes a, os_maestro b
                                                                      WHERE   a.hc_os_solicitud_id = b.hc_os_solicitud_id
                                                                        AND     a.tipo_id_paciente = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
                                                                        AND     a.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
                                                                    )))
                                    AND j.hc_os_solicitud_id = a.hc_os_solicitud_id
                                    AND j.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
                                    AND j.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
                            )
                        ) AS a, planes as f, os_tipos_solicitudes as g, cups as p, servicios as m								
                    WHERE
                        f.plan_id = a.plan_id
                        AND f.empresa_id = '" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'
                        $filtroPlan
                        AND g.os_tipo_solicitud_id = a.os_tipo_solicitud_id
                        AND p.cargo=a.cargo
                        AND m.servicio=a.servicio								
                        ORDER BY a.plan_id, a.servicio, a.fecha DESC";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo (select 1)";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            if ($result->RecordCount() > 1) {
                while (!$result->EOF) {
                    $vars[] = $result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            } else {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
            }
        }

        //autorizaciones
        $query = "  SELECT *
                    FROM
                        (
                            SELECT
                                    a.*,
                                    b.tipo_afiliado_nombre,
                                    c.descripcion as desserv,
                                    f.descripcion,
                                    k.nombre as autorizador,
                                    p.plan_descripcion
                            FROM
                                (
                                    SELECT
                                        a.*,
                                        e.numero_orden_id,
                                        case    when e.sw_estado=1 then 'ACTIVO'
                                        when e.sw_estado=2 then 'PAGADO'
                                        when e.sw_estado=3 then 'PARA ATENCION'
                                        when e.sw_estado=7 then 'TRASCRIPCION'
                                        when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'
                                        when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA'
                                        end as estado,
                                        e.sw_estado,
                                        e.fecha_vencimiento,
                                        e.cantidad,
                                        e.hc_os_solicitud_id,
                                        e.fecha_activacion,
                                        e.fecha_refrendar,
                                        e.cargo_cups
                                    FROM
                                        os_ordenes_servicios as a,
                                        os_maestro as e

                                    WHERE
                                        a.paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
                                        AND a.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
                                        AND e.orden_servicio_id=a.orden_servicio_id
                                        AND e.sw_estado IN('1','2','3','7')
                                ) AS a,
                                tipos_afiliado as b,
                                servicios as c,
                                cups as f,
                                autorizaciones as j,
                                system_usuarios as k,
                                planes as p

                            WHERE
                                b.tipo_afiliado_id = a.tipo_afiliado_id
                                AND c.servicio = a.servicio
                                AND f.cargo = a.cargo_cups
                                AND j.autorizacion = a.autorizacion_int
                                AND k.usuario_id = j.usuario_id
                                AND p.plan_id = a.plan_id
                                AND p.empresa_id = '" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'
                        ) AS a,
                        hc_os_solicitudes as b
                        LEFT JOIN (
                                    SELECT   a.hc_os_solicitud_id,
                                        b.descripcion as desc_especialidad
                                    FROM		hc_os_solicitudes_interconsultas a,
                                        especialidades b
                                    WHERE	a.especialidad = b.especialidad) AS INT
                                        ON (INT.hc_os_solicitud_id = b.hc_os_solicitud_id
                                    )
                    WHERE
                        b.hc_os_solicitud_id = a.hc_os_solicitud_id
                        $filtroPlan
                        AND b.os_tipo_solicitud_id <> 'CIT'
                    ORDER BY  a.orden_servicio_id DESC";
        
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones1 (select2)";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            if ($result->RecordCount() > 1) {
                while (!$result->EOF) {
                    $vars3[] = $result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            } else {
                $vars3[] = $result->GetRowAssoc($ToUpper = false);
            }
        }
        $result->Close();
        //no autorizaciones
        $query = "SELECT	h.observaciones,
															a.hc_os_solicitud_id,
															a.cargo as cargos,
															p.descripcion as descar,
															f.plan_descripcion,
															g.descripcion as desos,
															a.fecha,
															q.nombre_tercero,
															a.cantidad,
															a.profesional,
															a.evolucion_id									
											FROM
													(
															(
																	SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, i.departamento,
																	l.servicio, l.descripcion as despto, i.fecha, NULL as profesional, NULL as prestador, NULL as observaciones, a.evolucion_id
											
																	FROM hc_os_solicitudes a,
																	hc_evoluciones as i,
																	ingresos as j,
																	departamentos as l
											
																	WHERE a.evolucion_id IS NOT NULL
																	AND a.sw_estado='0'
																	AND a.sw_no_autorizado='1'
																	AND i.evolucion_id = a.evolucion_id
																	AND j.ingreso=i.ingreso
																	AND j.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
																	AND j.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																	AND l.departamento=i.departamento
															)
															UNION
															(
																	SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, j.departamento,
																	j.servicio, l.descripcion as despto, j.fecha, j.profesional, j.prestador, j.observaciones, NULL as evolucion_id
																	FROM hc_os_solicitudes a,
																	hc_os_solicitudes_manuales as j LEFT JOIN departamentos as l ON (l.departamento=j.departamento)
																	WHERE a.evolucion_id IS NULL
																	AND a.sw_estado='0'
																	AND a.sw_no_autorizado='1'
																	AND j.hc_os_solicitud_id = a.hc_os_solicitud_id
																	AND j.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																	AND j.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
															)
													) AS a,
													hc_os_autorizaciones as e,
													autorizaciones as h,
													planes as f,
													os_tipos_solicitudes as g,
													cups as p,
													terceros as q
											
											WHERE
													e.hc_os_solicitud_id = a.hc_os_solicitud_id
													AND f.empresa_id = '" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'
													$filtroPlan
													AND h.autorizacion = e.autorizacion_int
													AND f.plan_id = a.plan_id
													AND g.os_tipo_solicitud_id = a.os_tipo_solicitud_id
													AND p.cargo = a.cargo
													AND q.tipo_id_tercero = f.tipo_tercero_id
													AND q.tercero_id = f.tercero_id";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones2 (select3)";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$resulta->EOF) {
            $vars4[] = $resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $resulta->Close();

        $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE'] = $vars;
        $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE3'] = $vars3;
        $_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE4'] = $vars4;

        $query = "SELECT * FROM hc_os_autorizaciones_proceso
											WHERE tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
											AND paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
											AND usuario_id=" . UserGetUID() . "";
        $result = $dbconn->Execute($query);
        if ($result->EOF) {
            $query = "INSERT INTO hc_os_autorizaciones_proceso(
                                          tipo_id_paciente,
                                          paciente_id,
                                          usuario_id,
                                          fecha_registro,
                                          sw_estado)
                                            VALUES('" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "','" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'," . UserGetUID() . ",'now()','1')";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo (insert hc_os_autorizaciones_proceso)";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        $this->FormaDetalleSolicitud();

        return true;
    }

    /**
     *
     */
    function CrearTranscripcion() {

        $arr = explode(',', $_REQUEST['dat']);
        $d = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Op')) {    // 0 solicitud_id 1 cargo 2 tarifario
                $var = explode(',', $v);
                if ($var[0] == $_REQUEST['solicitud']) {
                    $d++;
                }
            }
        }

        if ($d == 0) {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir algun Cargo para la Transcripci?n.";
            $this->FormaListadoCargos($_REQUEST['datos']);
            return true;
        }


        if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
            $auto = $_SESSION['CENTROAUTORIZACION']['Autorizacion'];
            $plan = $_SESSION['CENTROAUTORIZACION']['PLAN'];
            $rango = $_SESSION['CENTROAUTORIZACION']['rango'];
            $empresa = $_SESSION['CENTROAUTORIZACION']['EMPRESA'];
            $afiliado = $_SESSION['CENTROAUTORIZACION']['tipo_afiliado_id'];
            $semana = $_SESSION['CENTROAUTORIZACION']['semanas'];
            $paciente = $_SESSION['CENTROAUTORIZACION']['paciente_id'];
            $tipo = $_SESSION['CENTROAUTORIZACION']['tipo_id_paciente'];
            $msg = $_SESSION['CENTROAUTORIZACION']['observacion'];
            $servicio = $_SESSION['CENTROAUTORIZACION']['SERVICIO'];
        } else {
            $auto = $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'];
            $plan = $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'];
            $rango = $_SESSION['CENTROAUTORIZACION']['TODO']['rango'];
            $empresa = $_SESSION['CENTROAUTORIZACION']['TODO']['EMPRESA'];
            $afiliado = $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_afiliado_id'];
            $semana = $_SESSION['CENTROAUTORIZACION']['TODO']['semanas'];
            $paciente = $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'];
            $tipo = $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'];
            $msg = $_SESSION['CENTROAUTORIZACION']['TODO']['observacion'];
            $servicio = $_SESSION['CENTROAUTORIZACION']['TODO']['SERVICIO'];
        }

        list($dbconn) = GetDBconn();
        $query = "(
											SELECT c.evento as evento_soat
											FROM hc_os_solicitudes a, hc_evoluciones b, ingresos_soat c
											WHERE a.hc_os_solicitud_id = " . $_REQUEST['solicitud'] . "
												AND a.evolucion_id IS NOT NULL
												AND a.evolucion_id = b.evolucion_id
												AND b.ingreso = c.ingreso
											)	
											UNION 
											(
											SELECT b.evento_soat 
											FROM hc_os_solicitudes a, hc_os_solicitudes_manuales b
											WHERE a.hc_os_solicitud_id =  " . $_REQUEST['solicitud'] . "
												AND a.evolucion_id IS NULL
												AND a.hc_os_solicitud_id = b.hc_os_solicitud_id
											)";

        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "SQL ERROR";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        if (!$result->EOF) {
            list($evento_soat) = $result->FetchRow();
        } else {
            $evento_soat = 'NULL';
        }
        //hice esta validacion porque algunas veces trae registros pero lo trae con valor NULL
        //entonces coloca vacion en el valor del evento y saca error en el insert
        //es reduntante pero funciona
        if (empty($evento_soat)) {
            $evento_soat = 'NULL';
        }
        //fin validacion            
        $result->Close();

        $dbconn->BeginTrans();
        $query = "SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
        $result = $dbconn->Execute($query);
        $orden = $result->fields[0];
        //evento soat se inserto para verificar si el paciente viene por algun evento
        echo $query = "INSERT INTO os_ordenes_servicios
                                                (orden_servicio_id,
                                                autorizacion_int,
                                                autorizacion_ext,
                                                plan_id,
                                                tipo_afiliado_id,
                                                rango,
                                                semanas_cotizadas,
                                                servicio,
                                                tipo_id_paciente,
                                                paciente_id,
                                                usuario_id,
                                                fecha_registro,
                                                observacion,
																								evento_soat)
            VALUES($orden," . $auto . ",NULL," . $plan . ",'" . $afiliado . "',
            '" . $rango . "'," . $semana . ",'" . $servicio . "','" . $tipo . "','" . $paciente . "'," . UserGetUID() . ",'now()','" . $msg . "',$evento_soat)";


        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error INSERT INTO os_ordenes_servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        } else {
            echo $query = "select * from os_tipos_periodos_planes
                                      where plan_id=" . $plan . "
                                      and cargo='" . $arr[5] . "'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error os_tipos_periodos_planes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $var = $result->GetRowAssoc($ToUpper = false);
                $Fecha = $this->FechaStamp($arr[fecha]);
                $infoCadena = explode('/', $Fecha);
                $intervalo = $this->HoraStamp($arr[fecha]);
                $infoCadena1 = explode(':', $intervalo);
                $fechaAct = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_tramite_os]), $infoCadena[2]));
                if ($fechaAct < date("Y-m-d H:i:s")) {
                    $fechaAct = date("Y-m-d H:i:s");
                }
                $Fecha = $this->FechaStamp($fechaAct);
                $infoCadena = explode('/', $Fecha);
                $intervalo = $this->HoraStamp($fechaAct);
                $infoCadena1 = explode(':', $intervalo);
                $venc = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_vigencia]), $infoCadena[2]));
                //fecha refrendar
                $Fecha = $this->FechaStamp($venc);
                $infoCadena = explode('/', $Fecha);
                $intervalo = $this->HoraStamp($venc);
                $infoCadena1 = explode(':', $intervalo);
                $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_refrendar]), $infoCadena[2]));
            } else {
                echo $query = "select * from os_tipos_periodos_tramites
                                              where cargo='" . $arr[5] . "'";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error os_tipos_periodos_tramites";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if (!$result->EOF) {
                    $var = $result->GetRowAssoc($ToUpper = false);
                    $Fecha = $this->FechaStamp($arr[6]);
                    $infoCadena = explode('/', $Fecha);
                    $intervalo = $this->HoraStamp($arr[6]);
                    $infoCadena1 = explode(':', $intervalo);
                    $fechaAct = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_tramite_os]), $infoCadena[2]));
                    if ($fechaAct < date("Y-m-d H:i:s")) {
                        $fechaAct = date("Y-m-d H:i:s");
                    }
                    $Fecha = $this->FechaStamp($fechaAct);
                    $infoCadena = explode('/', $Fecha);
                    $intervalo = $this->HoraStamp($fechaAct);
                    $infoCadena1 = explode(':', $intervalo);
                    $venc = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_vigencia]), $infoCadena[2]));
                    //fecha refrendar
                    $Fecha = $this->FechaStamp($venc);
                    $infoCadena = explode('/', $Fecha);
                    $intervalo = $this->HoraStamp($venc);
                    $infoCadena1 = explode(':', $intervalo);
                    $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_refrendar]), $infoCadena[2]));
                } else {
                    $tramite = ModuloGetVar('app', 'CentroAutorizacion', 'dias_tramite_os');
                    $vigencia = ModuloGetVar('app', 'CentroAutorizacion', 'dias_vigencia');
                    $var = $result->GetRowAssoc($ToUpper = false);
                    $Fecha = $this->FechaStamp($arr[6]);
                    $infoCadena = explode('/', $Fecha);
                    $intervalo = $this->HoraStamp($arr[6]);
                    $infoCadena1 = explode(':', $intervalo);
                    $fechaAct = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $tramite), $infoCadena[2]));
                    if ($fechaAct < date("Y-m-d H:i:s")) {
                        $fechaAct = date("Y-m-d H:i:s");
                    }
                    $Fecha = $this->FechaStamp($fechaAct);
                    $infoCadena = explode('/', $Fecha);
                    $intervalo = $this->HoraStamp($fechaAct);
                    $infoCadena1 = explode(':', $intervalo);
                    $venc = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $vigencia), $infoCadena[2]));
                    //fecha refrendar
                    $Fecha = $this->FechaStamp($venc);
                    $infoCadena = explode('/', $Fecha);
                    $intervalo = $this->HoraStamp($venc);
                    $infoCadena1 = explode(':', $intervalo);
                    $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $var[dias_refrendar]), $infoCadena[2]));
                }
            }
            $query = "SELECT nextval('os_maestro_numero_orden_id_seq')";
            $result = $dbconn->Execute($query);
            $numorden = $result->fields[0];

            echo $query = "INSERT INTO os_maestro
                                          (numero_orden_id,
                                          orden_servicio_id,
                                          sw_estado,
                                          fecha_vencimiento,
                                          hc_os_solicitud_id,
                                          fecha_activacion,
                                          cantidad,
                                          cargo_cups,
                                          fecha_refrendar)
                  VALUES($numorden,$orden,7,'$venc'," . $_REQUEST['solicitud'] . ",'$fechaAct',1,'" . $arr[5] . "','$refrendar')";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error INSERT INTO os_maestro";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            foreach ($_REQUEST as $ke => $va) {
                if (substr_count($ke, 'Op')) {    // 0 solicitud_id 1 cargo 2 tarifario
                    $var = explode(',', $va);
                    if ($var[0] == $_REQUEST['solicitud']) {
                        $query = "INSERT INTO os_maestro_cargos
                                                      (numero_orden_id,
                                                      tarifario_id,
                                                      cargo)
                              VALUES($numorden,'$var[2]','$var[1]')";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error INSERT INTO os_maestro_cargos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
        }

        //insertar en transcripcion
        $query = "INSERT INTO os_ordenes_servicios_transcripcion
                      VALUES($orden," . $plan . ",'$tipo','$paciente','now()'," . UserGetUID() . ")";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error INSERT INTO os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        echo $query = "UPDATE hc_os_solicitudes SET  sw_estado=0
                      WHERE hc_os_solicitud_id=" . $_REQUEST['solicitud'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error UPDATE  hc_os_solicitudes ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $result->MoveNext();


        $dbconn->CommitTrans();
        $query = "(select distinct  e.fecha,a.hc_os_solicitud_id, b.cantidad,b.cargo as cargos,b.plan_id,
                        b.os_tipo_solicitud_id, n.cargo,
                        n.tarifario_id,h.descripcion, r.descripcion as descar,soat.evento as evento_soat
                        from hc_os_autorizaciones as a,hc_os_solicitudes as b
                        join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
                        join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
                        hc_evoluciones as e
												left join ingresos_soat soat on (e.ingreso=soat.ingreso)
												, cups as r
                        where (a.autorizacion_int=" . $auto . "
                        OR a.autorizacion_ext=" . $auto . ")
                        and b.cargo=r.cargo
                        and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                        and b.evolucion_id is not null
                        and e.evolucion_id=b.evolucion_id
                        and b.hc_os_solicitud_id not in(select hc_os_solicitud_id from os_maestro)
                      )
                      union
                      (select distinct e.fecha,a.hc_os_solicitud_id, b.cantidad,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
                        n.cargo,n.tarifario_id,h.descripcion, r.descripcion as descar,e.evento_soat
                        from hc_os_autorizaciones as a,hc_os_solicitudes as b
                        join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
                        join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
                        hc_os_solicitudes_manuales as e, cups as r
                        where (a.autorizacion_int=" . $auto . " OR
                        a.autorizacion_ext=" . $auto . ")
                        and b.cargo=r.cargo
                        and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                        and a.hc_os_solicitud_id=e.hc_os_solicitud_id
                        and b.hc_os_solicitud_id not in(select hc_os_solicitud_id from os_maestro)
                      )";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error select ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $this->frmError["MensajeError"] = "La Transcripcion Fue Realizada. Orden generada $numorden.";
            $this->FormaListadoCargos($vars);
            return true;
        } else {
            $Mensaje = 'La Transcripcion Fue Realizada.';
            if (empty($_SESSION['CENTROAUTORIZACION']['TODO'])) {
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'LlamarBuscar');
            } else {
                $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaBuscarTodos');
            }
            if (!$this->FormaMensaje($Mensaje, 'CENTRO AUTORIZACIONES', $accion, '')) {
                return false;
            }
            return true;
        }
    }

//------------------------------------AUTORIZACION----------------------------------
    /*
     *
     *
      function ValidarCentroAutorizacion()
      {
      IncludeLib('funciones_facturacion');
      $d=0;
      list($dbconn) = GetDBconn();

      $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd, sw_afiliados
      FROM planes
      WHERE estado='1' and plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
      and fecha_final >= now() and fecha_inicio <= now()";

      $results = $dbconn->Execute($query);

      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      $results->Close();

      if ($dbconn->EOF) {
      $this->RetornarAutorizacion(false,'','El plan no existe, no tiene vigencia, o no esta activo',0);
      return true;
      }

      list($TipoPlan,$swAfiliados,$Protocolos,$swAutoSinBD, $swAfiliados1)=$results->FetchRow();

      $query = " SELECT a.plan_id
      FROM planes_auditores_int as a
      WHERE a.plan_id=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."
      and a.usuario_id=".UserGetUID()."";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Guardar en la Base de Datos";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if(!$result->EOF)
      {
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR']=$result->fields[0];
      }
      $result->Close();

      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']=$Protocolos;
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']=$TipoPlan;

      if(($TipoPlan==0 AND $swAfiliados==1) OR ($swAfiliados==1))
      {        //funcion que busca en las bases de afiliados (en caso de plan cliente o capitado)

      $PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
      $TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
      $Plan=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];

      if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php"))
      {
      $this->error = "Error";
      $this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
      return false;
      }
      if(!class_exists('BDAfiliados'))
      {
      $this->error="Error";
      $this->mensajeDeError="no existe BDAfiliados";
      return false;
      }

      $class= New BDAfiliados($TipoId,$PacienteId,$Plan);
      if($class->GetDatosAfiliado()==false)
      {
      $this->frmError["MensajeError"]=$class->mensajeDeError;
      }

      if(!empty($class->salida))
      {
      $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$class->salida;
      }


      }

      if($swAfiliados1 == 1)
      {
      $PacienteId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
      $TipoId=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
      $Plan=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
      $query = " SELECT a.tipo_afiliado_atencion, a.rango_afiliado_atencion, b.descripcion_estado
      FROM eps_afiliados as a,
      eps_afiliados_estados b
      WHERE a.plan_atencion=".$Plan."
      AND   a.afiliado_tipo_id='".$TipoId."'
      AND   a.afiliado_id = '".$PacienteId."'
      AND   a.estado_afiliado_id = b.estado_afiliado_id";

      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Guardar en la Base de Datos";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if(!$result->EOF)
      {
      $_SESSION['AUTORIZACIONES']['AFILIADO']=$result->fields[0];
      $_SESSION['AUTORIZACIONES']['RANGO'] = $result->fields[1];
      $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] = $result->fields[2];
      $_SESSION['AUTORIZACIONES']['SEMANAS'] = 0;

      }
      $result->Close();
      }

      $d=0;
      if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']))
      {
      if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']))
      {  $Ingreso='NULL';  }
      else
      {  $Ingreso=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'];  }
      list($dbconn) = GetDBconn();

      $query="SELECT nextval('autorizaciones_autorizacion_seq')";
      $result=$dbconn->Execute($query);
      $Autorizacion=$result->fields[0];

      $query = "INSERT INTO autorizaciones
      (
      autorizacion,
      fecha_autorizacion,
      observaciones,
      usuario_id,
      fecha_registro,
      sw_estado,
      ingreso
      )
      VALUES ($Autorizacion,'now()','',".UserGetUID().",'now()',0,$Ingreso)";
      $dbconn->BeginTrans();
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
      $this->error = "Error INSERT INTO autorizaciones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
      }
      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']=$Autorizacion;
      }

      foreach($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'] as $k => $v)
      {        //$k es hc_os_solicitud_id
      foreach($v as $cargo => $tarifario)
      { //$cargo es cargo
      foreach($tarifario as $tari => $servicio)
      {  //$tari es tarifario
      foreach($servicio as $serv => $x)
      {  //$serv es servicio
      $var = '';
      $var=ValdiarEquivalencias($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'],$cargo);
      $x=0;
      for($i=0; $i<sizeof($var); $i++)
      {
      if($x==0)
      {
      $sql = "SELECT * FROM hc_os_autorizaciones WHERE hc_os_solicitud_id = ".$k." ";
      $rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0)
      {
      $this->error = "Error INSERT INTO hc_os_autorizaciones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
      }

      $hc_os_auto = $rst->GetRowAssoc($ToUpper = false);

      $rst->Close();
      $query = "";
      if(empty($hc_os_auto['hc_os_solicitud_id']))
      {
      $query = "INSERT INTO hc_os_autorizaciones
      (
      autorizacion_int,
      autorizacion_ext,
      hc_os_solicitud_id
      )
      VALUES (".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",$k)";
      }
      else
      {
      $query = "UPDATE hc_os_autorizaciones
      SET     autorizacion_int = ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",
      autorizacion_ext = ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
      WHERE   hc_os_solicitud_id = ".$k." ";
      }
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
      $this->error = "Error INSERT INTO hc_os_autorizaciones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
      }
      $result->Close();
      $x=1;
      }
      $query = "select count(*) from autorizaciones_ingreso_cargos
      where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
      and tarifario_id='".$var[$i][tarifario_id]."'
      and cargo='".$var[$i][cargo]."'";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error select ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if($result->fields[0]==0)
      {
      $query = "INSERT INTO  autorizaciones_ingreso_cargos
      (autorizacion,
      tarifario_id,
      cargo,
      servicio,
      cantidad,
      hc_os_solicitud_id)
      VALUES(".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].",'".$var[$i][tarifario_id]."','".$var[$i][cargo]."','$serv',1,$k)";
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error INSERT INTO  autorizaciones_ingreso_cargos ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
      }
      }
      else
      {
      $cant=($result->fields[0])+1;
      $query = "UPDATE autorizaciones_ingreso_cargos SET
      cantidad=$cant
      WHERE autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."
      and tarifario_id='".$var[$i][tarifario_id]."'
      and cargo='".$var[$i][cargo]."'";
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0)
      {
      $this->error = "Error UPDATE  autorizaciones_ingreso_cargos ";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      $dbconn->RollbackTrans();
      return false;
      }
      }
      }//fin for
      }
      }
      }
      }
      $dbconn->CommitTrans();
      //si es ambulatoria para que pida la autorizacion con tipo de autorizacion, escrita o telefonica etc..
      echo $_SESSION['CENTRALHOSP']['SERVICIO'];
      if($_SESSION['CENTRALHOSP']['SERVICIO']==3)
      {
      $this->FormaAutorizacion();
      }
      else
      {
      $this->FormaAfiliado();
      }
      return true;
      } */
    /*
     *
     */
    function ValidarCentroAutorizacion() {
        IncludeLib('funciones_facturacion');
        $d = 0;
        list($dbconn) = GetDBconn();

        $query = "SELECT  sw_tipo_plan, 
                          sw_afiliacion, 
                          protocolos, 
                          sw_autoriza_sin_bd, 
                          sw_afiliados
                  FROM    planes
                  WHERE   estado='1' 
                    and     plan_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "'
                    and     fecha_final >= now() 
                    and     fecha_inicio <= now()";

        $results = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $results->Close();

        if ($dbconn->EOF) {
            $this->RetornarAutorizacion(false, '', 'El plan no existe, no tiene vigencia, o no esta activo', 0);
            return true;
        }

        list($TipoPlan, $swAfiliados, $Protocolos, $swAutoSinBD, $swAfiliados1) = $results->FetchRow();

        $query = " SELECT a.plan_id
                   FROM   planes_auditores_int as a
                   WHERE  a.plan_id=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "
                     and    a.usuario_id=" . UserGetUID() . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR'] = $result->fields[0];
        }
        $result->Close();

        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo'] = $Protocolos;
        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan'] = $TipoPlan;

        if (($TipoPlan == 0 AND $swAfiliados == 1) OR ($swAfiliados == 1)) {
            //funcion que busca en las bases de afiliados (en caso de plan cliente o capitado)
            $PacienteId = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
            $TipoId = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
            $Plan = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];

            $inp = AutoCarga::factory('InformacionPacientes');

            $datosPaciente = $inp->ValidarInformacion($_SESSION['AUTORIZACIONES']['AUTORIZAR']);

            if (!is_array($datosPaciente)) {
                if (is_numeric($datosPaciente))
                    $this->frmError["MensajeError"] = $inp->ObtenerClasificacionErrores($datosPaciente);

                if ($datosPaciente == 3) {
                    $sla = AutoCarga::factory("InformacionAfiliados", "", "app", "AgendaMedica");
                    $datosPaciente = $sla->ObtenerDatosAfiliados($this->request);

                    if ($datosPaciente === false)
                        $this->frmError["MensajeError"] = $sla->ErrMsg();
                }
            }

            if (!empty($datosPaciente)) {
                $this->datos['afiliados'] = $datosPaciente;
                if (!$this->datos['afiliados']['sexo_id'])
                    $this->datos['afiliados']['sexo_id'] = $datosPaciente['tipo_sexo_id'];
                if (!$this->datos['afiliados']['tipoafiliado'])
                    $this->datos['afiliados']['tipoafiliado'] = $datosPaciente['tipo_afiliado_atencion'];
                if (!$this->datos['afiliados']['rango'])
                    $this->datos['afiliados']['rango'] = $datosPaciente['rango_afiliado_atencion'];
                if (!$this->datos['afiliados']['residencia_telefono'])
                    $this->datos['afiliados']['residencia_telefono'] = $datosPaciente['telefono_residencia'];
                if (!$this->datos['afiliados']['residencia_direccion'])
                    $this->datos['afiliados']['residencia_direccion'] = $datosPaciente['direccion_residencia'];

                $this->datos['afiliados']['afiliacion_activa'] = '1';
            }
        }

        if ($swAfiliados1 == 1) {
            $PacienteId = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
            $TipoId = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
            $Plan = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
            $query = " SELECT a.tipo_afiliado_atencion, a.rango_afiliado_atencion, b.descripcion_estado
                       FROM eps_afiliados as a,
                            eps_afiliados_estados b
                       WHERE a.plan_atencion=" . $Plan . "
                           AND   a.afiliado_tipo_id='" . $TipoId . "'
                           AND   a.afiliado_id = '" . $PacienteId . "'
                           AND   a.estado_afiliado_id = b.estado_afiliado_id ";
//                       AND   a.estado_afiliado_id = 'AC'";
            $result = $dbconn->Execute($query);


            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $_SESSION['AUTORIZACIONES']['AFILIADO'] = $result->fields[0];
                $_SESSION['AUTORIZACIONES']['RANGO'] = $result->fields[1];
                $_SESSION['AUTORIZACIONES']['ESTADO_AFILIACION'] = $result->fields[2];
                $_SESSION['AUTORIZACIONES']['SEMANAS'] = 0;

                $this->datos['afiliados']['tipoafiliado'] = $result->fields[0];
                $this->datos['afiliados']['rango'] = $result->fields[1];
                $this->datos['afiliados']['Semanas'] = 0;
            }
            $result->Close();
        }

        if (empty($this->datos['afiliados']['tipoafiliado'])) {
            $semana = ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['Semanas']) ? $_SESSION['AUTORIZACIONES']['AUTORIZAR']['Semanas'] : "0";
            $_SESSION['AUTORIZACIONES']['AFILIADO'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipoafiliado'];
            $_SESSION['AUTORIZACIONES']['RANGO'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango'];
            $_SESSION['AUTORIZACIONES']['SEMANAS'] = $semana;

            $this->datos['afiliados']['tipoafiliado'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipoafiliado'];
            $this->datos['afiliados']['rango'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango'];
            $this->datos['afiliados']['Semanas'] = $semana;
        }

        if ($_SESSION['CENTRALHOSP']['SERVICIO'] == 3) {
            $this->AutorizarPaciente();
        } else {
            $d = 0;
            if (empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'])) {
                if (empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'])) {
                    $Ingreso = 'NULL';
                } else {
                    $Ingreso = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'];
                }
                list($dbconn) = GetDBconn();

                $query = "SELECT nextval('autorizaciones_autorizacion_seq')";
                $result = $dbconn->Execute($query);
                $Autorizacion = $result->fields[0];

                $query = "INSERT INTO autorizaciones
                                (
                                  autorizacion,
                                  fecha_autorizacion,
                                  observaciones,
                                  usuario_id,
                                  fecha_registro,
                                  sw_estado,
                                  ingreso
                                )
                        VALUES ($Autorizacion,'now()',''," . UserGetUID() . ",'now()',0,$Ingreso)";
                $dbconn->BeginTrans();
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error INSERT INTO autorizaciones";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] = $Autorizacion;
            }

            foreach ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'] as $k => $v) {        //$k es hc_os_solicitud_id
                foreach ($v as $cargo => $tarifario) { //$cargo es cargo
                    foreach ($tarifario as $tari => $servicio) {  //$tari es tarifario
                        foreach ($servicio as $serv => $x) {  //$serv es servicio
                            $var = '';
                            $var = ValdiarEquivalencias($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'], $cargo);
                            $x = 0;
                            for ($i = 0; $i < sizeof($var); $i++) {
                                if ($x == 0) {
                                    $sql = "SELECT * FROM hc_os_autorizaciones WHERE hc_os_solicitud_id = " . $k . " ";
                                    $rst = $dbconn->Execute($sql);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO hc_os_autorizaciones";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }

                                    $hc_os_auto = $rst->GetRowAssoc($ToUpper = false);

                                    $rst->Close();
                                    $query = "";
                                    if (empty($hc_os_auto['hc_os_solicitud_id'])) {
                                        $query = "INSERT INTO hc_os_autorizaciones
                                                (
                                                  autorizacion_int,
                                                  autorizacion_ext,
                                                  hc_os_solicitud_id
                                                )
                                                VALUES (" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "," . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . ",$k)";
                                    } else {
                                        $query = "UPDATE hc_os_autorizaciones
                                                SET     autorizacion_int = " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . ",
                                                        autorizacion_ext = " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                                WHERE   hc_os_solicitud_id = " . $k . " ";
                                    }
									//echo $query;
                                    $result = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO hc_os_autorizaciones";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                    $result->Close();
                                    $x = 1;
                                }
                                $query = "select count(*) from autorizaciones_ingreso_cargos
                                          where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            and tarifario_id='" . $var[$i][tarifario_id] . "'
                                            and cargo='" . $var[$i][cargo] . "'";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error select ";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                                }
                                if ($result->fields[0] == 0) {
                                    $query = "INSERT INTO  autorizaciones_ingreso_cargos
                                                    (autorizacion,
                                                    tarifario_id,
                                                    cargo,
                                                    servicio,
                                                    cantidad,
                                                    hc_os_solicitud_id)
                                              VALUES(" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . ",'" . $var[$i][tarifario_id] . "','" . $var[$i][cargo] . "','$serv',1,$k)";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO  autorizaciones_ingreso_cargos ";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                } else {
                                    $cant = ($result->fields[0]) + 1;
                                    $query = "UPDATE autorizaciones_ingreso_cargos SET
                                                cantidad=$cant
                                              WHERE autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                                and tarifario_id='" . $var[$i][tarifario_id] . "'
                                                and cargo='" . $var[$i][cargo] . "'";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error UPDATE  autorizaciones_ingreso_cargos ";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                }
                            }//fin for
                        }
                    }
                }
            }
            $dbconn->CommitTrans();
            //si es ambulatoria para que pida la autorizacion con tipo de autorizacion, escrita o telefonica etc..
            $this->FormaAfiliado();
        }
        return true;
    }

    /**
     * Llama el modulo de autorizaciones
     *
     * @param string tipo de documento
     * @param int numero de documento
     * @param int plan_id
     *
     * @return boolean		
     */
    function AutorizarPaciente($td = null, $doc= null, $plan= null) {
        $datos['idp'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
        $datos['tipoid'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
        $datos['plan_id'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
        $datos['afiliado'] = $this->datos['afiliados'];

        IncludeClass('Autorizaciones', '', 'app', 'NCAutorizaciones');

        $aut = new Autorizaciones();
        $planes = $aut->ObtenerTiposPlanes($datos['plan_id']);
        $Autoriza = $this->ReturnModuloExterno('app', 'NCAutorizaciones', 'user');

        if ($planes['sw_tipo_plan'] == '0' || $planes['sw_tipo_plan'] == '1' || $planes['sw_tipo_plan'] == '2' || $planes['sw_tipo_plan'] == '3') {
            $retorno = $_SESSION['AUTORIZACIONES']['RETORNO'];
            $retorno['ARGUMENTOS'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR'];

            $action['aceptar'] = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'RetornarAutorizacion');
            $action['volver'] = ModuloGetURL($retorno['contenedor'], $retorno['modulo'], $retorno['tipo'], $retorno['metodo'], $retorno['ARGUMENTOS']);

            $Autoriza->SetActionVolver($action['volver']);
            $Autoriza->SetActionAceptar($action['aceptar']);

            if (!$Autoriza->SetClaseAutorizacion('OS')) {
                $this->FormaMensaje($Autoriza->frmError['mensajeError'], 'AUTORIZACIONES');
                return true;
            }

            $Autoriza->FormaValidarAutoAdmisionHospitalizacion($datos);
            $this->salida = $Autoriza->salida;
        } else {
            $mensaje = "EL TIPO DE PLAN: " . $planes['sw_tipo_plan'] . ", NO ES VALIDO, FAVOR REVISAR LA INTEGRIDAD DE LA BASE DE DATOS";

            $this->FormaMensaje($mensaje, 'AUTORIZACIONES', $action1);
        }

        return true;
    }

    /**
     *
     */
    function DatosPlanUnico($plan) {
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_afiliado_id, rango
                      FROM planes_rangos
                      WHERE plan_id='$plan'";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var = $results->GetRowAssoc($ToUpper = false);
        return $var;
    }

    /**
     *
     *
      function RetornarAutorizacion($Autorizacion=false,$Codigo='',$Mensaje='',$NumAutorizacion=0)
      {
      //$dbconn->debug=true;
      if(empty($Autorizacion) AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'])
      AND empty($_SESSION['AUTORIZACIONES']['NOAUTO']) AND $Autorizacion!=1 AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']!=1)
      {
      list($dbconn) = GetDBconn();
      $query = "delete from autorizaciones
      where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "delete from autorizaciones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }

      $query = "select * from auditoria_cambio_datos_bdafiliados
      where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
      $result=$dbconn->Execute($query);
      if(!$result->EOF)
      {
      $query = "delete from auditoria_cambio_datos_bdafiliados
      where autorizacion=".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']."";
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "delete autorizaciones_solicitudes_cargos";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      }
      }

      $_SESSION['AUTORIZACIONES']['RETORNO']['ext']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']=$Autorizacion;
      $_SESSION['AUTORIZACIONES']['RETORNO']['Codigo']=$Codigo;
      $_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje']=$Mensaje;
      $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion']=$NumAutorizacion;
      $_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['plan_id']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id']=$_SESSION['AUTORIZACIONES']['AFILIADO'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['rango']=$_SESSION['AUTORIZACIONES']['RANGO'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['semanas']=$_SESSION['AUTORIZACIONES']['SEMANAS'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso']=$_SESSION['AUTORIZACIONES']['observacion_ingreso'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_SERVICIO']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];
      $_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_AUTORIZACION']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'];

      $Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
      $Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
      $Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
      $Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
      $argu=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'];

      if(empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo))
      {
      $this->error = "AUTORIZACION ";
      $this->mensajeDeError = "Los datos de retorno de la Autorizaci?n no son correctos.";
      return false;
      }

      unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']);

      if($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'] && $_SESSION['AUTORIZACIONES']['RETORNO']['Codigo']!='SOAT')
      {
      $accion=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
      $mensaje='El N?mero de Autorizacion es '.$NumAutorizacion;
      $this->FormaMensaje($mensaje,'AUTORIZACION',$accion);
      return true;
      }
      else
      {
      $this->ReturnMetodoExterno($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
      return true;
      }
      }
     */

    /**
     *
     */
    function RetornarAutorizacion($Autorizacion=false, $Codigo='', $Mensaje='', $NumAutorizacion=0) {
        $request = $_REQUEST;
        $Contenedor = $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
        $Modulo = $_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
        $Tipo = $_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
        $Metodo = $_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
        $argu = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'];

        $_SESSION['AUTORIZACIONES']['RETORNO']['paciente_id'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_id_paciente'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'] = $_SESSION['AUTORIZACIONES']['AFILIADO'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['rango'] = $_SESSION['AUTORIZACIONES']['RANGO'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['semanas'] = $_SESSION['AUTORIZACIONES']['SEMANAS'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['observacion_ingreso'] = $_SESSION['AUTORIZACIONES']['observacion_ingreso'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_SERVICIO'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['TIPO_AUTORIZACION'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_AUTORIZACION'];

        if (empty($Contenedor) || empty($Modulo) || empty($Tipo) || empty($Metodo)) {
            $this->error = "AUTORIZACION ";
            $this->mensajeDeError = "Los datos de retorno de la Autorizaci?n no son correctos.";
            return false;
        }

        if ($request['autorizacion']) {
            IncludeLib('funciones_facturacion');
            $_SESSION['AUTORIZACIONES']['RETORNO']['ext'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT'];
            $_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'] = $request['autorizacion']['numero_autorizacion'];
            $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'] = $request['autorizacion']['numero_autorizacion'];
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'] = $request['autorizacion']['tipoafiliado'];
            $_SESSION['AUTORIZACIONES']['RETORNO']['rango'] = $request['autorizacion']['rango'];
            $_SESSION['AUTORIZACIONES']['RETORNO']['semanas'] = $request['autorizacion']['semanas'];

            $cxn = new ConexionBD();
            //$cxn->debug = true;
            $cxn->ConexionTransaccion();
            foreach ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'] as $k => $v) {        //$k es hc_os_solicitud_id
                foreach ($v as $cargo => $tarifario) { //$cargo es cargo
                    foreach ($tarifario as $tari => $servicio) {  //$tari es tarifario
                        foreach ($servicio as $serv => $x) {  //$serv es servicio
                            $var = '';
                            $var = ValdiarEquivalencias($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'], $cargo);
                            $x = 0;
                            for ($i = 0; $i < sizeof($var); $i++) {
                                if ($x == 0) {
                                    $sql = "SELECT * ";
                                    $sql .= "FROM   hc_os_autorizaciones ";
                                    $sql .= "WHERE  hc_os_solicitud_id = " . $k . " ";

                                    if (!$rst = $cxn->ConexionTransaccion($sql)) {
                                        $this->error = "Error INSERT INTO hc_os_autorizaciones";
                                        $this->mensajeDeError = $cxn->mensajeDeError;
                                        return false;
                                    }

                                    $hc_os_auto = $rst->GetRowAssoc($ToUpper = false);

                                    $rst->Close();
                                    $query = "";
                                    if (empty($hc_os_auto['hc_os_solicitud_id'])) {
                                        $sql = "INSERT INTO hc_os_autorizaciones ";
                                        $sql .= " ( ";
                                        $sql .= "   autorizacion_int,";
                                        $sql .= "   autorizacion_ext,";
                                        $sql .= "   hc_os_solicitud_id";
                                        $sql .= "   )";
                                        $sql .= "VALUES";
                                        $sql .= "   (";
                                        $sql .= "     " . $request['autorizacion']['numero_autorizacion'] . ",";
                                        $sql .= "     " . $request['autorizacion']['numero_autorizacion'] . ",";
                                        $sql .= "     " . $k . "";
                                        $sql .= "   )";
                                    } else {
                                        $sql = "UPDATE hc_os_autorizaciones ";
                                        $sql .= "SET    autorizacion_int = " . $request['autorizacion']['numero_autorizacion'] . ",";
                                        $sql .= "       autorizacion_ext = " . $request['autorizacion']['numero_autorizacion'] . " ";
                                        $sql .= "WHERE  hc_os_solicitud_id = " . $k . " ";
                                    }

                                    if (!$rst = $cxn->ConexionTransaccion($sql)) {
                                        $this->error = "Error INSERT INTO hc_os_autorizaciones";
                                        $this->mensajeDeError = $cxn->mensajeDeError;
                                        return false;
                                    }
                                    $rst->Close();

                                    $x = 1;
                                }
                                $sql = "SELECT COUNT(*) ";
                                $sql .= "FROM   autorizaciones_ingreso_cargos ";
                                $sql .= "WHERE  autorizacion = " . $request['autorizacion']['numero_autorizacion'] . " ";
                                $sql .= "and tarifario_id = '" . $var[$i][tarifario_id] . "' ";
                                $sql .= "and cargo = '" . $var[$i][cargo] . "' ";

                                if (!$rst = $cxn->ConexionTransaccion($sql)) {
                                    $this->error = "Error select ";
                                    $this->mensajeDeError = $cxn->mensajeDeError;
                                    return false;
                                }

                                if ($rst->fields[0] == 0) {
                                    $sql = "INSERT INTO autorizaciones_ingreso_cargos ";
                                    $sql .= "   ( ";
                                    $sql .= "     autorizacion,";
                                    $sql .= "     tarifario_id,";
                                    $sql .= "     cargo,";
                                    $sql .= "     servicio,";
                                    $sql .= "     cantidad,";
                                    $sql .= "     hc_os_solicitud_id";
                                    $sql .= "   )";
                                    $sql .= "VALUES";
                                    $sql .= "   (";
                                    $sql .= "      " . $request['autorizacion']['numero_autorizacion'] . ",";
                                    $sql .= "     '" . $var[$i][tarifario_id] . "',";
                                    $sql .= "     '" . $var[$i][cargo] . "',";
                                    $sql .= "     '" . $serv . "',";
                                    $sql .= "      1,";
                                    $sql .= "      " . $k . "";
                                    $sql .= "   )";

                                    if (!$rst = $cxn->ConexionTransaccion($sql)) {
                                        $this->error = "Error INSERT INTO  autorizaciones_ingreso_cargos ";
                                        $this->mensajeDeError = $cxn->mensajeDeError;
                                        return false;
                                    }
                                } else {
                                    $cant = ($result->fields[0]) + 1;
                                    $sql = "UPDATE autorizaciones_ingreso_cargos ";
                                    $sql .= "SET    cantidad = " . $cant . " ";
                                    $sql .= "WHERE  autorizacion = " . $request['autorizacion']['numero_autorizacion'] . " ";
                                    $sql .= "AND    tarifario_id = '" . $var[$i][tarifario_id] . "' ";
                                    $sql .= "AND    cargo = '" . $var[$i][cargo] . "'";

                                    if (!$rst = $cxn->ConexionTransaccion($sql)) {
                                        $this->error = "Error UPDATE  autorizaciones_ingreso_cargos ";
                                        $this->mensajeDeError = $cxn->mensajeDeError;
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $cxn->Commit();
            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']);

            $this->ReturnMetodoExterno($Contenedor, $Modulo, $Tipo, $Metodo, $argu);
        } else {
            $_SESSION['AUTORIZACIONES']['RETORNO']['ext'] = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT'];
            $_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'] = $Autorizacion;
            $_SESSION['AUTORIZACIONES']['RETORNO']['Codigo'] = $Codigo;
            $_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'] = $Mensaje;
            $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'] = $NumAutorizacion;

            unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']);

            if (empty($Autorizacion) AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'])
                    AND empty($_SESSION['AUTORIZACIONES']['NOAUTO']) AND $Autorizacion != 1 AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] != 1) {
                list($dbconn) = GetDBconn();

                $query = "DELETE FROM autorizaciones
										WHERE autorizacion = " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "delete from autorizaciones";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                $query = "SELECT  * 
                    FROM    auditoria_cambio_datos_bdafiliados
                    WHERE   autorizacion = " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
                $result = $dbconn->Execute($query);
                if (!$result->EOF) {
                    $query = "DELETE FROM auditoria_cambio_datos_bdafiliados
                      WHERE autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "delete autorizaciones_solicitudes_cargos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
            }
            if ($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'] && $_SESSION['AUTORIZACIONES']['RETORNO']['Codigo'] != 'SOAT') {
                $accion = ModuloGetURL($Contenedor, $Modulo, $Tipo, $Metodo, $argu);
                $mensaje = 'El N?mero de Autorizacion es ' . $NumAutorizacion;
                $this->FormaMensaje($mensaje, 'AUTORIZACION', $accion);
            } else {
                $this->ReturnMetodoExterno($Contenedor, $Modulo, $Tipo, $Metodo, $argu);
            }
        }
        return true;
    }

    /**
     *
     */
    function CargosSolicitadosAutorizacion() {
        list($dbconn) = GetDBconn();
        // $dbconn->debug = true;
        $query = "select a.*, f.cantidad,r.cargo as cargoc,r.descripcion, b.precio, c.hc_os_solicitud_id, f.evolucion_id,
                      g.usuario_id, h.nombre_tercero, j.descripcion as descpro, k.descripcion as descserv, f.hc_os_solicitud_id, b.descripcion as desc
                      from  tarifarios_detalle as b, cups as r,
                      autorizaciones_ingreso_cargos as a
											join hc_os_solicitudes as f on(a.hc_os_solicitud_id=f.hc_os_solicitud_id)
                      left join hc_evoluciones as g on(f.evolucion_id=g.evolucion_id)
                      left join profesionales_usuarios as n on(g.usuario_id=n.usuario_id)
                      left join terceros as h on(h.tipo_id_tercero=n.tipo_tercero_id and h.tercero_id=n.tercero_id)
                      left join profesionales as i on(h.tipo_id_tercero=i.tipo_id_tercero and h.tercero_id=i.tercero_id)
                      left join tipos_profesionales as j on(i.tipo_profesional=j.tipo_profesional), servicios as k,
                      hc_os_autorizaciones as c
                      where a.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                      and a.tarifario_id=b.tarifario_id
                      and a.cargo=b.cargo
                      and (a.autorizacion=c.autorizacion_int or autorizacion=c.autorizacion_ext)
                      and c.hc_os_solicitud_id=f.hc_os_solicitud_id and a.servicio=k.servicio and
                      f.cargo=r.cargo and
                      a.hc_os_solicitud_id=c.hc_os_solicitud_id order by a.cargo ";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            while (!$result->EOF) {
                $cargos[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        $this->FormaSolicitud($cargos);
        return true;
    }

    /**
     *
     */
    function BuscarMedico($id) {
        list($dbconn) = GetDBconn();
        $query = "select profesional from hc_os_solicitudes_manuales
                      where hc_os_solicitud_id=$id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $var = $result->fields[0];
        }
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function BuscarDiagnosticoSin($evo) {
        list($dbconn) = GetDBconn();
        $query = "select m.diagnostico_nombre
                  from hc_diagnosticos_ingreso as d left join diagnosticos as m on(d.tipo_diagnostico_id=m.diagnostico_id)
                  where d.evolucion_id=$evo";
        $result = $dbconn->Execute($query);
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $resul->Close();
        return $var;
    }

    /**
     *
     */
    function BuscarDiagnostico2($id) {
        list($dbconn) = GetDBconn();
        $query = "select m.diagnostico_nombre
                  from hc_os_solicitudes_diagnosticos as d left join diagnosticos as m on(d.diagnostico_id=m.diagnostico_id)
                  where d.hc_os_solicitud_id=$id";
        $result = $dbconn->Execute($query);
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function BuscarDiagnostico($evo) {
        list($dbconn) = GetDBconn();
        $query = "select b.diagnostico_nombre
                    from hc_diagnosticos_ingreso as a, diagnosticos as b
                    where a.evolucion_id=$evo and a.tipo_diagnostico_id=b.diagnostico_id";
        $result = $dbconn->Execute($query);
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        return $var;
    }

    /**
     *
     */
    function LlamarFormaCambiarCantidad() {
        $this->FormaCambiarCantidad($_REQUEST['ant'], $_REQUEST['solicitud'], $_REQUEST['cargo'], $_REQUEST['tarifario']);
        return true;
    }

    /**
     *
     */
    function CambiarCantidad() {
        if ($_REQUEST['ant'] == $_REQUEST['Cantidad']) {
            $this->frmError["MensajeError"] = "La cantidad autorizada debe ser diferente a la actual.";
            if (!$this->FormaCambiarCantidad($_REQUEST['ant'], $_REQUEST['solicitud'], $_REQUEST['cargo'], $_REQUEST['tarifario'])) {
                return false;
            }
            return true;
        }

        if (empty($_REQUEST['Observacion']) || empty($_REQUEST['Cantidad'])) {
            if (empty($_REQUEST['Observacion'])) {
                $this->frmError["Observacion"] = 1;
            }
            if (empty($_REQUEST['Cantidad'])) {
                $this->frmError["Cantidad"] = 1;
            }
            $this->frmError["MensajeError"] = "Debe escribir el motivo del cambio de cantidad y la cantidad autorizada.";
            if (!$this->FormaCambiarCantidad($_REQUEST['ant'], $_REQUEST['solicitud'], $_REQUEST['cargo'], $_REQUEST['tarifario'])) {
                return false;
            }
            return true;
        }

        list($dbconn) = GetDBconn();

        $query = "select * from auditoria_cambio_cantidad_os_solicitud
                  where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                  AND tarifario_id='" . $_REQUEST['tarifario'] . "'
                  AND cargo='" . $_REQUEST['cargo'] . "'
                  AND hc_os_solicitud_id=" . $_REQUEST['solicitud'] . "";
        $result = $dbconn->Execute($query);
        if (!$result->EOF) {
            $query = "UPDATE auditoria_cambio_cantidad_os_solicitud
                  SET cantidad_autorizada=" . $_REQUEST['Cantidad'] . "
                  where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                  AND tarifario_id='" . $_REQUEST['tarifario'] . "'
                  AND cargo='" . $_REQUEST['cargo'] . "'
                  AND hc_os_solicitud_id=" . $_REQUEST['solicitud'] . "";
            $dbconn->BeginTrans();
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        } else {
            $query = "INSERT INTO auditoria_cambio_cantidad_os_solicitud
                      VALUES(" . $_REQUEST['solicitud'] . "," . $_REQUEST['ant'] . "," . $_REQUEST['Cantidad'] . ",
                      " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . ",'" . $_REQUEST['Observacion'] . "',
                      " . UserGetUID() . ",'now()','" . $_REQUEST['tarifario'] . "','" . $_REQUEST['cargo'] . "')";
            $dbconn->BeginTrans();
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $query = "UPDATE autorizaciones_ingreso_cargos SET cantidad=" . $_REQUEST['Cantidad'] . "
                  WHERE autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                  AND tarifario_id='" . $_REQUEST['tarifario'] . "'
                  AND cargo='" . $_REQUEST['cargo'] . "'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();
        $this->frmError["MensajeError"] = "La cantidad fue Cambiada.";
        $this->LlamarFormaAutorizacion();
        return true;
    }

    /**
     * Busca los diferentes tipos de afiliados
     * @access public
     * @return array
     */
    function NombreAfiliado($Tipo) {
        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                                    FROM tipos_afiliado as a, planes_rangos as b
                                    WHERE b.plan_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "'
                                    and a.tipo_afiliado_id='$Tipo'";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $vars = $resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $vars;
    }

    /**
     * Busca los diferentes tipos de afiliados
     * @access public
     * @return array
     */
    function Tipo_Afiliado() {
        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                                    FROM tipos_afiliado as a, planes_rangos as b
                                    WHERE b.plan_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "'
                                    and b.tipo_afiliado_id=a.tipo_afiliado_id";
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

    /**
     * Busca los niveles del plan del responsable del paciente
     * @access public
     * @return array
     * @param string plan_id
     */
    function Niveles() {
        list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT rango
                                FROM planes_rangos
                                WHERE plan_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "'";
        $result = $dbconn->Execute($query);
        while (!$result->EOF) {
            $niveles[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $niveles;
    }

    /**
     *
     */
    function Observaciones() {
        list($dbconn) = GetDBconn();
        $query = "select observaciones from autorizaciones_telefonicas
                                    where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $obs.=$result->fields[0] . " ";
            $result->MoveNext();
        }
        $query = "select observaciones from autorizaciones_escritas
                                    where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $obs.=$result->fields[0] . " ";
            $result->MoveNext();
        }
        $query = "select observaciones from autorizaciones_electronicas
                                    where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $obs.=$result->fields[0] . " ";
            $result->MoveNext();
        }
        $query = "select observaciones from autorizaciones_por_sistema
                                    where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $obs.=$result->fields[0] . " ";
            $result->MoveNext();
        }
        $query = "select observaciones from autorizaciones_certificados
                                    where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $obs.=$result->fields[0] . " ";
            $result->MoveNext();
        }
        return $obs;
    }

    /**
     *
     */
    function BuscarUsuarios($PlanId) {
        list($dbconn) = GetDBconn();
        $query = " SELECT b.nombre, b.usuario_id
                                    FROM planes_auditores_int as a, system_usuarios as b
                                    WHERE a.plan_id='$PlanId' and a.usuario_id=b.usuario_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        return $var;
    }

    /**
     *
     */
    function BuscarAutorizaciones($tabla) {
        list($dbconn) = GetDBconn();
        if ($tabla == 'autorizaciones_por_sistema') {
            $query = "select  b.nombre, a.* from $tabla as a, system_usuarios as b
                                    where a.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                    and a.usuario_id=b.usuario_id";
        } else {
            $query = "select * from $tabla
                                    where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        }
        $result = $dbconn->Execute($query);

        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
    }

    /**
     *
     */
    function InsertarTipoAutorizacion() {
        $Tipo = $_REQUEST['Tipo'];
        $CodAuto = $_REQUEST['CodAuto'];
        $Responsable = $_REQUEST['Responsable'];
        $Validez = $_REQUEST['Validez'];
        $Registro = $_REQUEST['Registro'];
        $Observaciones = $_REQUEST['Observaciones'];
        IF (!empty($Registro)) {
            $f = explode('/', $Registro);
            $Registro = $f[2] . '-' . $f[1] . '-' . $f[0];
        }
        if (!empty($Validez)) {
            $f = explode('/', $Validez);
            $Validez = $f[2] . '-' . $f[1] . '-' . $f[0];
        }

        $Validar = $this->ValidarAutorizacion($Tipo, $CodAuto, $Responsable, $Validez);
        if ($Validar) {
            $Autorizacion = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'];
            $SystemId = UserGetUID();
            list($dbconn) = GetDBconn();
            if ($Tipo == '1') {
                $query = "INSERT INTO autorizaciones_telefonicas(
                                                                                            autorizacion,
                                                                                            responsable,
                                                                                            codigo_autorizacion,
                                                                                            observaciones)
                                                        VALUES ($Autorizacion,'$Responsable','$CodAuto','$Observaciones')";
            }
            if ($Tipo == '2') {
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['EXT'] = TRUE;
                $query = "INSERT INTO autorizaciones_escritas(
                                                                                            autorizacion,
                                                                                            validez,
                                                                                            codigo_autorizacion,
                                                                                            observaciones)
                                                        VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
            }
            if ($Tipo == '3') {
                $query = "INSERT INTO autorizaciones_bd(
                                                                                            autorizacion,
                                                                                            registro)
                                                        VALUES ($Autorizacion,'$Registro')";
            }
            if ($Tipo == '4') {
                if ($Responsable != UserGetUID()) {
                    $sw = 1;
                } else {
                    $sw = 0;
                }
                $query = "INSERT INTO autorizaciones_por_sistema(
                                                                                                autorizacion,
                                                                                                usuario_id,
                                                                                                solicitud,
                                                                                                fecha_confirmacion,
                                                                                                observaciones,
                                                                                                sw_confirmacion)
                                                        VALUES ($Autorizacion,'$Responsable','$Solicitud',NULL,'$Observaciones',$sw)";
            }
            if ($Tipo == '5') {
                $query = "INSERT INTO autorizaciones_electronicas(
                                                                                            autorizacion,
                                                                                            validez,
                                                                                            codigo_autorizacion,
                                                                                            observaciones)
                                                        VALUES ($Autorizacion,'$Validez','$CodAuto','$Observaciones')";
            }
            if ($Tipo == '6') {
                $query = "INSERT INTO autorizaciones_certificados(
																																										autorizacion,
																																										responsable,
																																										codigo_autorizacion,
																																										observaciones,
																																										fecha_terminacion)
                                                        VALUES ($Autorizacion,'$Responsable','$CodAuto','$Observaciones','$Validez')";
            }
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al guardar en autorizaciones";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            } else {
                $this->frmError["MensajeError"] = "La Autorizaci?n se guardo correctamente.";
                $this->FormaAutorizacion();
                return true;
            }
        } else {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Faltan datos obligatorios.";
            $this->FormaAutorizacionTipo($Tipo);
            return true;
        }
    }

    /**
     *
     */
    function TiposAuto() {
        list($dbconn) = GetDBconn();
        $query = " SELECT tipo_autorizacion,descripcion FROM tipos_autorizacion
                                     WHERE tipo_autorizacion not in(3)";
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

    /**
     *
     */
    function InsertarAutorizacion() {

        $_SESSION['AUTORIZACIONES']['RETORNO']['ObservacionesOS'] = $_REQUEST['ObservacionesOS'];
        $Tipo = $_REQUEST['Tipo'];
        $FechaAuto = $_REQUEST['FechaAuto'];
        $HoraAuto = $_REQUEST['HoraAuto'];
        $MinAuto = $_REQUEST['MinAuto'];
        $Observaciones = $_REQUEST['ObservacionesA'];
        $ObservacionesT = $_REQUEST['ObservacionesT'];
        $ObservacionesI = $_REQUEST['ObservacionesI'];
        $_SESSION['AUTORIZACIONES']['RETORNO']['OBSERVACION'] = $_REQUEST['Observacionesos'];
        $f = explode('/', $FechaAuto);
        $FechaAuto = $f[2] . '-' . $f[1] . '-' . $f[0];
        $Fecha = $FechaAuto . " " . $HoraAuto . ":" . $MinAuto;
        $Ingreso = 'NULL';
        $_SESSION['AUTORIZACIONES']['ObservacionesI'] = $_REQUEST['ObservacionesI'];
        $_SESSION['AUTORIZACIONES']['ObservacionesA'] = $_REQUEST['ObservacionesA'];
        //en caso que los datos de afiliado no existan y los pide
        if ($_REQUEST['Si'] == 1) {
            $_SESSION['AUTORIZACIONES']['AFILIADO'] = $_REQUEST['TipoAfiliado'];
            $_SESSION['AUTORIZACIONES']['RANGO'] = $_REQUEST['Nivel'];
            $_SESSION['AUTORIZACIONES']['SEMANAS'] = $_REQUEST['Semanas'];
            //if(!empty($_REQUEST['Semanas']) OR $_REQUEST['Semanas']===0)
            if ($_REQUEST['Semanas'] != '') {
                if (is_numeric($_REQUEST['Semanas']) == 0) {
                    $this->frmError["Semanas"] = 1;
                    $this->frmError["MensajeError"] = "Las semanas deben ser enteras.";
                    $this->FormaAutorizacion();
                    return true;
                }
            }
            //(!$_REQUEST['Semanas'] AND $_REQUEST['Semanas']===0)
            if (($_REQUEST['Semanas'] == '') || $_REQUEST['TipoAfiliado'] == -1 || $_REQUEST['Nivel'] == -1) {
                if ($_REQUEST['Semanas'] == '') {
                    $this->frmError["Semanas"] = 1;
                }
                if ($_REQUEST['TipoAfiliado'] == -1) {
                    $this->frmError["TipoAfiliado"] = 1;
                }
                if ($_REQUEST['Nivel'] == -1) {
                    $this->frmError["Nivel"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
                $this->FormaAutorizacion();
                return true;
            }
        }
        //en caso que los datos de afiliado existan
        if (!empty($_REQUEST['Cambiar'])) {
            $this->LlamarFormaCambiar($_REQUEST['TipoAfiliado'], $_REQUEST['Nivel'], $_REQUEST['Semanas']);
            return true;
        }
        //valida si elegio el tipo de autorizacion
        if (!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion'] == -1) {
            $this->frmError["MensajeError"] = "Debe elegir el Tipo de Autorizaci?n.";
            $this->FormaAutorizacion();
            return true;
        } elseif (!empty($_REQUEST['Aceptar']) && $_REQUEST['TipoAutorizacion'] != -1) {
            $this->FormaAutorizacionTipo($_REQUEST['TipoAutorizacion']);
            return true;
        }

        if (!$FechaAuto || !$HoraAuto || !$MinAuto) {
            if (!$FechaAuto) {
                $this->frmError["FechaAuto"] = 1;
            }
            if (!$HoraAuto) {
                $this->frmError["HoraAuto"] = 1;
            }
            if (!$MinAuto) {
                $this->frmError["HoraAuto"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan Datos Obligatorios.";
            $this->FormaAutorizacion();
            return true;
        }

        if (!empty($_REQUEST['NoAutorizar'])) {
            $sw = 1;
        } else {
            $sw = 0;
        }

        if (empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo'])
                AND empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR'])
                AND empty($_REQUEST['NoAutorizar'])) {
            list($dbconn) = GetDBconn();
            $query = "select count(*)
                                            from autorizaciones_escritas as a 
																						full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion) 
																						full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion) 
																						full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
																						full join autorizaciones_certificados as e on (c.autorizacion=e.autorizacion)
                                            where a.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            or b.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            or c.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            or d.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
																						or e.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error select count(*)";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if ($results->fields[0] == 0) {
                $this->frmError["MensajeError"] = "Debe Realizar algun tipo de autorizacion.";
                $this->FormaAutorizacion();
                return true;
            }
            $results->Close();
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        if (!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUDITOR'])) {
            list($dbconn) = GetDBconn();
            $query = "select count(*)
                                            from autorizaciones_escritas as a full join autorizaciones_telefonicas as b on (a.autorizacion=b.autorizacion) full join autorizaciones_por_sistema as c on (b.autorizacion=c.autorizacion) full join autorizaciones_electronicas as d on (c.autorizacion=d.autorizacion)
                                            where a.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            or b.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            or c.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                                            or d.autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
            $results = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error select count(*)";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if ($results->fields[0] == 0) {
                $query = "select count(*) from autorizaciones_por_sistema
                                            where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
                $results = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error select count(*)";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if ($results->fields[0] == 0) {
                    $query = "INSERT INTO autorizaciones_por_sistema(
                                                                    autorizacion,
                                                                    usuario_id,
                                                                    solicitud,
                                                                    fecha_confirmacion,
                                                                    observaciones,
                                                                    sw_confirmacion)
                                                  VALUES (" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "," . UserGetUID() . ",'','now()','',0)";
                    $results = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error INTO autorizaciones_por_sistema";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
            }
            $results->Close();
        }
        //actualiza la autorizacion inicial
        $t = $o = '';
        if (!empty($ObservacionesT) AND $ObservacionesT != ' ') {
            $t = "OBSERVACIONES DE LAS AUTORIZACIONES: " . $ObservacionesT;
        }
        if (!empty($Observaciones)) {
            $o = " OBSERVACIONES DE LA AUTORIZACION: " . $Observaciones;
        }
        $obs = $t . $o;

        $query = "UPDATE autorizaciones SET
                                      fecha_autorizacion='$Fecha',
                                      observaciones='$obs',
                                      observacion_ingreso='$ObservacionesI',
                                      sw_estado=$sw
                                    WHERE autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error UPDATE autorizaciones ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        } else {
            $dbconn->CommitTrans();
            $_SESSION['AUTORIZACIONES']['observacion_ingreso'] = $ObservacionesI;
            $auto = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'];
            //unset($_SESSION['SOLICITUDAUTORIZACION']['VECTOR']);
            //unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']);

            if ($auto) {
                if (!empty($_REQUEST['NoAutorizar'])) {
                    $this->FormaJustificar($auto);
                    return true;
                } else {
                    unset($_SESSION['AUTORIZACIONES']['REQSEM']);
                    foreach ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['SEMANAS'] AS $k => $v) {
                        foreach ($v AS $k2 => $v2) {
                            $i = 0;
                            if ($_REQUEST['Semanas'] < $k2) {
                                $var.= '- ' . $k . ' NECESITA ' . $k2 . ' SEMANAS<br>';
                                $arr[$i]['descripcion'] = $des;
                                $arr[$i]['req'] = $req;
                                $i++;
                            }
                        }
                    }

                    if (empty($arr)) {//las semanas le alcanzan
                        $this->RetornarAutorizacion(true, 'ADMITIR', '', $auto);
                        return true;
                    } else {//hay alguna solicitud que necesita mas semanas que las que tiene
                        $mensaje = 'Alguna Solicitud(es) Requieren m?s Semanas que las Cotizadas por el Paciente (' . $_REQUEST['Semanas'] . ' semanas). Esta seguro de Realizar la Autorizaci?n : <br>';
                        $arreglo = array();
                        $c = 'app';
                        $m = 'CentroAutorizacion';
                        $me = 'LlamarRetornarAutorizacion';
                        $me2 = 'RetornarAutorizacion';
                        $Titulo = 'VALIDACION DE SEMANAS';
                        $boton1 = 'ACEPTAR';
                        $boton2 = 'CANCELAR';
                        $this->ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, $arreglo, $c, $m, $me, $me2);
                        return true;
                    }
                }
            } else {
                $this->RetornarAutorizacion(false, 'ADMITIR', 'Plan', $auto);
                return true;
            }
        }
    }

    /**
     *
     */
    function LlamarRetornarAutorizacion() {
        $this->RetornarAutorizacion(true, 'ADMITIR', '', $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']);
        return true;
    }

    /**
     *
     */
    function LlamarFormaAutorizacion() {
        unset($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR']);
        if (!$this->FormaAutorizacion()) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    function LlamarFormaCambiar($tipo, $rango, $semanas) {
        $this->FormaCambiar($tipo, $rango, $semanas);
        return true;
    }

    /**
     *
     */
    function GuardarCambiosAfiliado() {
        if (($_REQUEST['Semanas'] == '') || $_REQUEST['TipoAfiliado'] == -1 || $_REQUEST['Nivel'] == -1) {
            if ($_REQUEST['Semanas'] == '') {
                $this->frmError["Semanas"] = 1;
            }
            if ($_REQUEST['TipoAfiliado'] == -1) {
                $this->frmError["TipoAfiliado"] = 1;
            }
            if ($_REQUEST['Nivel'] == -1) {
                $this->frmError["Nivel"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
            $this->FormaCambiar($_REQUEST['TipoAfiliado'], $_REQUEST['Nivel'], $_REQUEST['Semanas']);
            return true;
        }

        list($dbconn) = GetDBconn();
        $query = "INSERT INTO auditoria_cambio_datos_bdafiliados
                          VALUES('" . $_SESSION['AUTORIZACIONES']['AFILIADO'] . "','" . $_SESSION['AUTORIZACIONES']['RANGO'] . "',
                          " . $_SESSION['AUTORIZACIONES']['SEMANAS'] . ",
                          '" . $_REQUEST['TipoAfiliado'] . "','" . $_REQUEST['Nivel'] . "',
                          " . $_REQUEST['Semanas'] . ",'" . $_REQUEST['Observacion'] . "'," . UserGetUID() . ",'now()',
                          " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . ")";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al eliminar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $_SESSION['AUTORIZACIONES']['AFILIADO'] = $_REQUEST['TipoAfiliado'];
        $_SESSION['AUTORIZACIONES']['RANGO'] = $_REQUEST['Nivel'];
        $_SESSION['AUTORIZACIONES']['SEMANAS'] = $_REQUEST['Semanas'];

        $this->LlamarFormaAutorizacion();
        return true;
    }

    /**
     *
     */
    function GuardarAfiliado() {//(!$_REQUEST['Semanas']  AND $_REQUEST['Semanas']===0)
        //$dbconn->debug=true;
        if (!empty($_REQUEST['NoAutorizar'])) {
            $this->FormaJustificar($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']);
            return true;
        }

        if (($_REQUEST['Semanas'] == '') || $_REQUEST['TipoAfiliado'] == -1 || $_REQUEST['Nivel'] == -1) {
            if ($_REQUEST['Semanas'] == '') {
                $this->frmError["Semanas"] = 1;
            }
            if ($_REQUEST['TipoAfiliado'] == -1) {
                $this->frmError["TipoAfiliado"] = 1;
            }
            if ($_REQUEST['Nivel'] == -1) {
                $this->frmError["Nivel"] = 1;
            }
            $this->frmError["MensajeError"] = "Faltan datos obligatorios.";
            $this->FormaAfiliado($_REQUEST['TipoAfiliado'], $_REQUEST['Nivel'], $_REQUEST['Semanas']);
            return true;
        }

        $_SESSION['AUTORIZACIONES']['AFILIADO'] = $_REQUEST['TipoAfiliado'];
        $_SESSION['AUTORIZACIONES']['RANGO'] = $_REQUEST['Nivel'];
        $_SESSION['AUTORIZACIONES']['SEMANAS'] = $_REQUEST['Semanas'];

        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO'])) {
            if ($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'] != $_REQUEST['TipoAfiliado']
                    AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'] != $_REQUEST['Nivel']
                    AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'] != $_REQUEST['Semanas']) {
                if (empty($_REQUEST['Observacion'])) {
                    $this->frmError["Observacion"] = 1;
                    $this->frmError["MensajeError"] = "Debe digitar la justificacion del cambio.";
                    $this->FormaDatosAfiliado($_REQUEST['TipoAfiliado'], $_REQUEST['Nivel'], $_REQUEST['Semanas']);
                    return true;
                }

                list($dbconn) = GetDBconn();
                $query = "INSERT INTO auditoria_cambio_datos_bdafiliados
                              VALUES('" . $_SESSION['AUTORIZACIONES']['AFILIADO'] . "','" . $_SESSION['AUTORIZACIONES']['RANGO'] . "',
                              " . $_SESSION['AUTORIZACIONES']['SEMANAS'] . ",
                              '" . $_REQUEST['TipoAfiliado'] . "','" . $_REQUEST['Nivel'] . "',
                              " . $_REQUEST['Semanas'] . ",'" . $_REQUEST['Observacion'] . "'," . UserGetUID() . ",'now()',
                              " . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . ")";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al eliminar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }

        unset($_SESSION['AUTORIZACIONES']['REQSEM']);
        foreach ($_SESSION['AUTORIZACIONES']['AUTORIZAR']['SEMANAS'] AS $k => $v) {
            foreach ($v AS $k2 => $v2) {
                $i = 0;
                if ($_REQUEST['Semanas'] < $k2) {
                    $var.= '- ' . $k . ' NECESITA ' . $k2 . ' SEMANAS<br>';
                    $arr[$i]['descripcion'] = $des;
                    $arr[$i]['req'] = $req;
                    $i++;
                }
            }
        }

        if (empty($arr)) {//las semanas le alcanzan
            $this->RetornarAutorizacion(true, 'ADMITIR', '', $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']);
            return true;
        } else {//hay alguna solicitud que necesita mas semanas que las que tiene
            $mensaje = 'Alguna Solicitud(es) Requieren m?s Semanas que las Cotizadas por el Paciente (' . $_REQUEST['Semanas'] . ' semanas). Esta seguro de Realizar la Autorizaci?n : <br>' . $var;
            $arreglo = array();
            $c = 'app';
            $m = 'CentroAutorizacion';
            $me = 'LlamarRetornarAutorizacion';
            $me2 = 'RetornarAutorizacion';
            $Titulo = 'VALIDACION DE SEMANAS';
            $boton1 = 'ACEPTAR';
            $boton2 = 'CANCELAR';
            $this->ConfirmarAccion($Titulo, $mensaje, $boton1, $boton2, $arreglo, $c, $m, $me, $me2);
            return true;
        }

        //$this->RetornarAutorizacion(true,'ADMITIR','',$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']);
        //return true;
    }

    /**
     *
     */
    function ValidarAutorizacion($Tipo, $CodAuto, $Responsable, $Validez) {
        if ($Tipo == '1') {
            if (!$Responsable) {
                if (!$Responsable) {
                    $this->frmError["Responsable"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan Datos Obligatorios.";
                return false;
            }
            return true;
        }

        if ($Tipo == '2') {
            if (!$Validez) {
                if (!$Validez) {
                    $this->frmError["Validez"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan Datos Obligatorios.";
                return false;
            } else {
                $paso = $this->ValidarFecha($Validez);
                if (empty($paso)) {
                    $this->frmError["Validez"] = 1;
                    $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
                    return false;
                }
            }
            return true;
        }

        if ($Tipo == '4') {
            if (!$Responsable) {
                if (!$Responsable) {
                    $this->frmError["Responsable"] = 1;
                }
                $this->frmError["MensajeError"] = "Debe Elegir el Usuario.";
                return false;
            }
            return true;
        }

        if ($Tipo == '5') {
            if (!$Validez) {
                if (!$Validez) {
                    $this->frmError["Validez"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan Datos Obligatorios.";
                return false;
            }
            return true;
        }
        if ($Tipo == '6') {
            if (!$Validez) {
                if (!$Validez) {
                    $this->frmError["Validez"] = 1;
                }
                $this->frmError["MensajeError"] = "Faltan Datos Obligatorios.";
                return false;
            } else {
                $paso = $this->ValidarFecha($Validez);
                if (empty($paso)) {
                    $this->frmError["Validez"] = 1;
                    $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto.";
                    return false;
                }
            }
            if (!$Responsable) {
                if (!$Responsable) {
                    $this->frmError["Responsable"] = 1;
                }
                $this->frmError["MensajeError"] = "Debe escribir el nombre del Usuario.";
                return false;
            }
            if (!$CodAuto) {
                if (!$CodAuto) {
                    $this->frmError["CodAuto"] = 1;
                }
                $this->frmError["MensajeError"] = "Debe digitar el N?mero de autorizaci?n del Certificado.";
                return false;
            }
            return true;
        }
    }

    /**
     *
     */
    function ValidarFecha($fecha) {
        $x = explode("-", $fecha);
        if (strlen($x[0]) != 4 OR is_numeric($x[0]) == 0) {
            $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto ";
            return false;
        }
        if (strlen($x[1]) > 2 OR is_numeric($x[1]) == 0 OR $x[1] == 0) {
            $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto ";
            return false;
        }
        if (strlen($x[2]) > 2 OR is_numeric($x[2]) == 0 OR $x[1] == 0) {
            $this->frmError["MensajeError"] = "Formato de Fecha Incorrecto ";
            return false;
        }
        return true;
    }

    /**
     *
     */
    function JustificarNoAutorizacion() {
        if (empty($_REQUEST['Observaciones'])) {
            $this->frmError["MensajeError"] = "Debe Elegir o Digitar la justificaci?n de la No Autorizaci?n.";
            $this->FormaJustificar($_REQUEST['auto']);
            return true;
        }

        list($dbconn) = GetDBconn();
        $query = "select observaciones from autorizaciones where autorizacion=" . $_REQUEST['auto'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $obs = $result->fields[0];
        $obs .=$_REQUEST['Observaciones'];

        $query = "UPDATE autorizaciones SET
                                      observaciones='$obs',
                                      sw_estado=1
                                    WHERE autorizacion=" . $_REQUEST['auto'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $_SESSION['AUTORIZACIONES']['observacion_ingreso'] = $obs;
        $_SESSION['AUTORIZACIONES']['NOAUTO'] = TRUE;
        $this->RetornarAutorizacion(false, 'ADMITIR', '', $_REQUEST['auto']);
        return true;
    }

//-----------------------ORDENES SERVICIO-------------------------------------------

    /**
     *
     */
    function LlamarBuscarOS() {
        unset($_SESSION['SPY3']);
        $this->FormaMetodoBuscarOS();
        return true;
    }

    /**
     *
     */
    /*  function BuscarOrden()
      {
      unset($_SESSION['CENTROAUTORIZACION']['TODO']);
      $tipo_documento=$_REQUEST['TipoDocumento'];
      $documento=$_REQUEST['Documento'];
      $nombres = strtolower($_REQUEST['Nombres']);
      $fecha = $_REQUEST['Fecha'];
      $orden = $_REQUEST['Orden'];

      //ELIGIO UN PLAN
      $filtroPlan='';
      if ($_REQUEST['plan']!= -1)
      {   $filtroPlan ="and  a.plan_id=".$_REQUEST['plan']."";   }
      $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']=$_REQUEST['plan'];

      if($_REQUEST['Fecha']=='TODAS LAS FECHAS')
      {   $fecha = '';   }

      list($dbconn) = GetDBconn();
      $query = "select a.orden_servicio_id, e.numero_orden_id,e.fecha_refrendar
      from os_ordenes_servicios as a
      join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
      where e.sw_estado in('1') and e.fecha_vencimiento < now()
      and e.fecha_refrendar < now()";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if(!$result->EOF){  $cont=$result->RecordCount(); }
      while(!$result->EOF)
      {
      $query = "update os_maestro set sw_estado=8
      where orden_servicio_id = ".$result->fields[0]."
      and numero_orden_id = ".$result->fields[1]."";
      $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if($cont > 1)
      {      $result->MoveNext();  }
      }
      $result->Close();

      $filtroTipoDocumento = '';
      $filtroDocumento='';
      $filtroNombres='';
      $filtroFecha='';
      $filtroOrden='';

      if(!empty($orden))
      {  $filtroOrden=" AND a.orden_servicio_id=$orden";  }

      if(!empty($tipo_documento))
      {   $filtroTipoDocumento=" AND a.tipo_id_paciente = '$tipo_documento'";   }

      if ($documento != '')
      {   $filtroDocumento =" AND a.paciente_id LIKE '$documento%'";   }

      if ($nombres != '')
      {
      $a=explode(' ',$nombres);
      foreach($a as $k=>$v)
      {
      if(!empty($v))
      {
      $filtroNombres.=" and (upper(c.primer_nombre||' '||c.segundo_nombre||' '||
      c.primer_apellido||' '||c.segundo_apellido) like '%".strtoupper($v)."%')";
      }
      }
      }

      if ($fecha != '')
      {   $filtroFecha ="AND date(b.fecha_activacion) = date('$fecha')";   }

      if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

      if(empty($_REQUEST['pasoOS']))
      {
      $query = "select distinct a.tipo_id_paciente, a.paciente_id,
      c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
      from os_ordenes_servicios as a join os_maestro as b on (b.orden_servicio_id=a.orden_servicio_id),
      pacientes as c
      where a.paciente_id=c.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente
      $filtroTipoDocumento $filtroDocumento $filtroNombres
      $filtroFecha
      $filtroOrden
      and a.plan_id=".$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']."
      and b.sw_estado in ('1','2','3','7','8','9')";
      $result=$dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Guardar en la Tabal autorizaiones";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
      }
      if(!$result->EOF)
      {
      $_SESSION['SPY3']=$result->RecordCount();
      }
      $result->Close();
      }

      $query = "select distinct a.tipo_id_paciente, a.paciente_id,
      c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
      from os_ordenes_servicios as a join os_maestro as b on (b.orden_servicio_id=a.orden_servicio_id),
      pacientes as c
      where a.paciente_id=c.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente
      $filtroTipoDocumento $filtroDocumento $filtroNombres
      $filtroFecha
      $filtroOrden
      and a.plan_id=".$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']."
      and b.sw_estado in ('1','2','3','7','8','9') LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
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
      $this->FormaMetodoBuscarOS($var);
      return true;
      } */

    /**
     *
     */
    function DetalleOS($tipo, $paciente, $datos='', $control='') {
        if (empty($tipo)) {
            $tipo = $_REQUEST['tipoid'];
            $paciente = $_REQUEST['pacienteid'];
            $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] = $_REQUEST['tipoid'];
            $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] = $_REQUEST['pacienteid'];
        }
        $cont = 0;

        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1 AND !empty($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'])) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }

        list($dbconn) = GetDBconn();

        $query = "SELECT
													a.*,
													b.plan_descripcion as planpro,
													c.descripcion as desserv,
													d.descripcion,
													e.plan_descripcion,
													g.nombre as autorizador,
													h.primer_nombre||' '||h.segundo_nombre||' '||h.primer_apellido||' '||h.segundo_nombre as nombre
											
											FROM
											(
													(
															---------------------------------------------
															-- SOLICITUDES DESDE HC
															---------------------------------------------
															(
																	-- INTERNAS
																	SELECT
																			a.*,
																			b.tipo_afiliado_nombre,
																			e.cargo, e.departamento, f.descripcion as desdpto, -- departamento, cargo y descripcion del dpto al que se le dirige la OS
																			NULL as cargoext,
																			NULL as plan_proveedor_id,
																			d.fecha as fecha_solicitud,
																			INT.descripcion as desc_especialidad
											
																	FROM
																			(
																					SELECT
																							a.*,
																							e.numero_orden_id,
																							case    when e.sw_estado=1 then 'ACTIVO'
																											when e.sw_estado=2 then 'PAGADO'
																											when e.sw_estado=3 then 'PARA ATENCION'
																											when e.sw_estado=7 then 'TRASCRIPCION'
																											when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'
																											when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA'
																							end as estado,
																							e.sw_estado,
																							e.fecha_vencimiento,
																							e.cantidad,
																							e.hc_os_solicitud_id,
																							e.fecha_activacion,
																							e.fecha_refrendar,
																							e.cargo_cups
																					FROM
																					os_ordenes_servicios as a,
																					os_maestro as e
											
																					WHERE
																					a.paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																					AND a.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
																					AND e.orden_servicio_id=a.orden_servicio_id
																					AND e.sw_estado IN('1','2','3','7')
																			) AS a,
																			tipos_afiliado as b,
																			hc_os_solicitudes as c
																			LEFT JOIN (SELECT   a.hc_os_solicitud_id,
																								b.descripcion
																					   FROM		hc_os_solicitudes_interconsultas a,
																								especialidades b
																						WHERE	a.especialidad = b.especialidad) AS INT
																					ON (INT.hc_os_solicitud_id = c.hc_os_solicitud_id),
																			hc_evoluciones as d,
																			os_internas e,
																			departamentos f
																	WHERE
																			b.tipo_afiliado_id = a.tipo_afiliado_id
																			AND c.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND c.os_tipo_solicitud_id <> 'CIT'
																			AND c.evolucion_id IS NOT NULL
																			AND d.evolucion_id = c.evolucion_id
																			AND e.numero_orden_id = a.numero_orden_id
																			AND f.departamento=e.departamento
															)
															UNION
															(
																	-- EXTERNAS
											
																	SELECT
																			a.*,
																			b.tipo_afiliado_nombre,
																			NULL as cargo, NULL as departamento, NULL as desdpto, -- departamento, cargo y descripcion del dpto al que se le dirige la OS
																			e.cargo as cargoext,
																			e.plan_proveedor_id,
																			d.fecha as fecha_solicitud,
																			INT.descripcion as desc_especialidad
											
																	FROM
																			(
																					SELECT
																							a.*,
																							e.numero_orden_id,
																							case    when e.sw_estado=1 then 'ACTIVO'
																											when e.sw_estado=2 then 'PAGADO'
																											when e.sw_estado=3 then 'PARA ATENCION'
																											when e.sw_estado=7 then 'TRASCRIPCION'
																											when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'
																											when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA'
																							end as estado,
																							e.sw_estado,
																							e.fecha_vencimiento,
																							e.cantidad,
																							e.hc_os_solicitud_id,
																							e.fecha_activacion,
																							e.fecha_refrendar,
																							e.cargo_cups
																					FROM
																					os_ordenes_servicios as a,
																					os_maestro as e
											
																					WHERE
																					a.paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																					AND a.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
																					AND e.orden_servicio_id=a.orden_servicio_id
																					AND e.sw_estado IN('1','2','3','7')
																			) AS a,
																			tipos_afiliado as b,
																			hc_os_solicitudes as c
																			LEFT JOIN (SELECT   a.hc_os_solicitud_id,
																								b.descripcion
																					   FROM		hc_os_solicitudes_interconsultas a,
																								especialidades b
																						WHERE	a.especialidad = b.especialidad) AS INT
																					ON (INT.hc_os_solicitud_id = c.hc_os_solicitud_id),
																			hc_evoluciones as d,
																			os_externas e
																	WHERE
																			b.tipo_afiliado_id = a.tipo_afiliado_id
																			AND c.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND c.os_tipo_solicitud_id <> 'CIT'
																			AND c.evolucion_id IS NOT NULL
																			AND d.evolucion_id = c.evolucion_id
																			AND e.numero_orden_id = a.numero_orden_id
															)
											
													)
													UNION
													(
											
															---------------------------------------------
															-- SOLICITUDES MANUALES
															---------------------------------------------
															(
																	-- INTERNAS
											
																	SELECT
																			a.*,
																			b.tipo_afiliado_nombre,
																			e.cargo, e.departamento, f.descripcion as desdpto, -- departamento, cargo y descripcion del dpto al que se le dirige la OS
																			NULL as cargoext,
																			NULL as plan_proveedor_id,
																			d.fecha as fecha_solicitud,
																			INT.descripcion as desc_especialidad
											
																	FROM
																			(
																					SELECT
																							a.*,
																							e.numero_orden_id,
																							case    when e.sw_estado=1 then 'ACTIVO'
																											when e.sw_estado=2 then 'PAGADO'
																											when e.sw_estado=3 then 'PARA ATENCION'
																											when e.sw_estado=7 then 'TRASCRIPCION'
																											when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'
																											when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA'
																							end as estado,
																							e.sw_estado,
																							e.fecha_vencimiento,
																							e.cantidad,
																							e.hc_os_solicitud_id,
																							e.fecha_activacion,
																							e.fecha_refrendar,
																							e.cargo_cups
																					FROM
																					os_ordenes_servicios as a,
																					os_maestro as e
											
																					WHERE
																					a.paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																					AND a.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
																					AND e.orden_servicio_id=a.orden_servicio_id
																					AND e.sw_estado IN('1','2','3','7')
																			) AS a,
																			tipos_afiliado as b,
																			hc_os_solicitudes as c
																			LEFT JOIN (SELECT   a.hc_os_solicitud_id,
																								b.descripcion
																					   FROM		hc_os_solicitudes_interconsultas a,
																								especialidades b
																						WHERE	a.especialidad = b.especialidad) AS INT
																					ON (INT.hc_os_solicitud_id = c.hc_os_solicitud_id),
																			hc_os_solicitudes_manuales as d,
																			os_internas e,
																			departamentos f
																	WHERE
																			b.tipo_afiliado_id = a.tipo_afiliado_id
																			AND c.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND c.os_tipo_solicitud_id <> 'CIT'
																			AND c.evolucion_id IS NULL
																			AND d.hc_os_solicitud_id = c.hc_os_solicitud_id
																			AND e.numero_orden_id = a.numero_orden_id
																			AND f.departamento=e.departamento
															)
															UNION
															(
																	-- EXTERNAS
											
																	SELECT
																			a.*,
																			b.tipo_afiliado_nombre,
																			NULL as cargo, NULL as departamento, NULL as desdpto, -- departamento, cargo y descripcion del dpto al que se le dirige la OS
																			e.cargo as cargoext,
																			e.plan_proveedor_id,
																			d.fecha as fecha_solicitud,
																			INT.descripcion as desc_especialidad
											
																	FROM
																			(
																					SELECT
																							a.*,
																							e.numero_orden_id,
																							case    when e.sw_estado=1 then 'ACTIVO'
																											when e.sw_estado=2 then 'PAGADO'
																											when e.sw_estado=3 then 'PARA ATENCION'
																											when e.sw_estado=7 then 'TRASCRIPCION'
																											when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'
																											when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA'
																							end as estado,
																							e.sw_estado,
																							e.fecha_vencimiento,
																							e.cantidad,
																							e.hc_os_solicitud_id,
																							e.fecha_activacion,
																							e.fecha_refrendar,
																							e.cargo_cups
																					FROM
																					os_ordenes_servicios as a,
																					os_maestro as e
											
																					WHERE
																					a.paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																					AND a.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
																					AND e.orden_servicio_id=a.orden_servicio_id
																					AND e.sw_estado IN('1','2','3','7')
																			) AS a,
																			tipos_afiliado as b,
																			hc_os_solicitudes as c
																			LEFT JOIN (SELECT   a.hc_os_solicitud_id,
																								b.descripcion
																					   FROM		hc_os_solicitudes_interconsultas a,
																								especialidades b
																						WHERE	a.especialidad = b.especialidad) AS INT
																					ON (INT.hc_os_solicitud_id = c.hc_os_solicitud_id),
																			hc_os_solicitudes_manuales as d,
																			os_externas e
																	WHERE
																			b.tipo_afiliado_id = a.tipo_afiliado_id
																			AND c.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND c.os_tipo_solicitud_id <> 'CIT'
																			AND c.evolucion_id IS NULL
																			AND d.hc_os_solicitud_id = c.hc_os_solicitud_id
																			AND e.numero_orden_id = a.numero_orden_id
															)
											
													)
											) AS a LEFT JOIN planes_proveedores as b ON (b.plan_proveedor_id = a.plan_proveedor_id),
											servicios as c,
											cups as d,
											planes as e,
											autorizaciones as f,
											system_usuarios g,
											pacientes h
											
											WHERE
											c.servicio = a.servicio
											AND d.cargo = a.cargo_cups
											$filtroPlan
											AND e.plan_id = a.plan_id
											AND f.autorizacion = a.autorizacion_int
											AND g.usuario_id = a.usuario_id
											AND h.paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
											AND h.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'";
        
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
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

        //PARA BUSCAR LAS SOLICITUDES NO AUTORIZADAS
        $query = "SELECT	h.observaciones,
															a.hc_os_solicitud_id,
															a.cargo as cargos,
															p.descripcion as descar,
															f.plan_descripcion,
															g.descripcion as desos,
															a.fecha,
															q.nombre_tercero,
															a.cantidad,
															a.profesional,
															a.evolucion_id									
											FROM
													(
															(
																	SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, i.departamento,
																	l.servicio, l.descripcion as despto, i.fecha, NULL as profesional, NULL as prestador, NULL as observaciones, a.evolucion_id
																	FROM hc_os_solicitudes a,
																	hc_evoluciones as i,
																	ingresos as j,
																	departamentos as l
																	WHERE a.evolucion_id IS NOT NULL
																	AND a.sw_estado='0'
																	AND a.sw_no_autorizado='1'
																	AND i.evolucion_id = a.evolucion_id
																	AND j.ingreso=i.ingreso
																	AND j.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
																	AND j.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																	AND l.departamento=i.departamento
															)
															UNION
															(
																	SELECT a.sw_ambulatorio, a.cantidad, a.cargo, a.hc_os_solicitud_id, a.plan_id, a.os_tipo_solicitud_id, j.departamento,
																	j.servicio, l.descripcion as despto, j.fecha, j.profesional, j.prestador, j.observaciones, NULL as evolucion_id
																	FROM hc_os_solicitudes a,
																	hc_os_solicitudes_manuales as j LEFT JOIN departamentos as l ON (l.departamento=j.departamento)
																	WHERE a.evolucion_id IS NULL
																	AND a.sw_estado='0'
																	AND a.sw_no_autorizado='1'
																	AND j.hc_os_solicitud_id = a.hc_os_solicitud_id
																	AND j.paciente_id = '" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
																	AND j.tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
															)
													) AS a,
													hc_os_autorizaciones as e,
													autorizaciones as h,
													planes as f,
													os_tipos_solicitudes as g,
													cups as p,
													terceros as q											
											WHERE
													e.hc_os_solicitud_id = a.hc_os_solicitud_id
													$filtroPlan
													AND h.autorizacion = e.autorizacion_int
													AND f.plan_id = a.plan_id
													AND g.os_tipo_solicitud_id = a.os_tipo_solicitud_id
													AND p.cargo = a.cargo
													AND q.tipo_id_tercero = f.tipo_tercero_id
												AND q.tercero_id = f.tercero_id";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error no autor";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$results->EOF) {
            while (!$results->EOF) {
                $vars[] = $results->GetRowAssoc($ToUpper = false);
                $results->MoveNext();
            }
        }
        $results->Close();

        //para ver si tiene algo
        if (empty($var) AND empty($vars)) {
            $var[0][tipo_id_paciente] = $tipo;
            $var[0][paciente_id] = $paciente;
            //$this->LlamarBuscarOS();
            //return true;
        }

        $this->FormaDetalleOS($var, $vars, $datos, $control);
        return true;
    }

    /**
     *
     */
    function OsVencidas($tipo, $paciente) {
        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1 AND !empty($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'])) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }

        list($dbconn) = GetDBconn();
        /* if(empty($_SESSION['CENTROAUTORIZACION']['TODOS']))
          {
          $query = "select a.orden_servicio_id, e.numero_orden_id, e.cantidad, f.descripcion, g.cargo
          from os_ordenes_servicios as a
          join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
          left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
          left join os_externas as h on (e.numero_orden_id=h.numero_orden_id),
          cups as f
          where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
          and a.tipo_id_paciente='".$tipo."' and a.paciente_id='".$paciente."'
          and (e.cargo_cups=f.cargo)
          and e.sw_estado=8 order by a.plan_id";
          }
          else
          { */
        $query = "select a.orden_servicio_id, e.numero_orden_id, e.cantidad,
                          f.descripcion, g.cargo, r.plan_id, r.plan_descripcion
                          from os_ordenes_servicios as a
                          join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                          left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                          left join os_externas as h on (e.numero_orden_id=h.numero_orden_id),                          
                          cups as f, planes as r
                          where a.tipo_id_paciente='" . $tipo . "' 
													and a.paciente_id='" . $paciente . "'
													$filtroPlan
                          and r.plan_id=a.plan_id
                          and (e.cargo_cups=f.cargo)
                          and e.sw_estado='8' order by a.plan_id";
        //}
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
    }

    /**
     *
     */
    function OsAnuladas($tipo, $paciente) {
        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1 AND !empty($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'])) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }

        list($dbconn) = GetDBconn();
        /* if(empty($_SESSION['CENTROAUTORIZACION']['TODOS']))
          {
          $query = "select a.orden_servicio_id, e.numero_orden_id, e.cantidad, f.descripcion, g.cargo
          from os_ordenes_servicios as a
          join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
          left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
          left join os_externas as h on (e.numero_orden_id=h.numero_orden_id),
          cups as f
          where a.plan_id=".$_SESSION['CENTROAUTORIZACION']['PLAN']."
          and a.tipo_id_paciente='".$tipo."' and a.paciente_id='".$paciente."'
          and (e.cargo_cups=f.cargo)
          and e.sw_estado=9 order by a.plan_id";
          }
          else
          { */
        $query = "select a.orden_servicio_id, e.numero_orden_id, e.cantidad,
                          f.descripcion, g.cargo, r.plan_id, r.plan_descripcion
                          from os_ordenes_servicios as a
                          join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                          left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                          left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                          left join userpermisos_centro_autorizacion as p on(p.usuario_id=" . UserGetUID() . " and a.plan_id=p.plan_id),
                          cups as f, planes as r
                          where a.tipo_id_paciente='" . $tipo . "' 
													and a.paciente_id='" . $paciente . "'
													$filtroPlan
                          and r.plan_id=a.plan_id
                          and (e.cargo_cups=f.cargo)
                          and e.sw_estado='9' order by a.plan_id";
        // }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
    }

    /**
     *
     */
    function OsOtrosPlanes($tipo, $paciente) {
        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1 AND !empty($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'])) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }

        list($dbconn) = GetDBconn();
        $query = "select a.plan_id,a.orden_servicio_id, e.numero_orden_id,
											case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' else 'PARA ATENCION' end as estado,
											e.fecha_vencimiento, e.cantidad, e.fecha_activacion, e.fecha_refrendar, f.descripcion, g.cargo, g.departamento,
											i.plan_descripcion
											from os_ordenes_servicios as a join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
											left join os_internas as g on (e.numero_orden_id=g.numero_orden_id) left join os_externas as h on (e.numero_orden_id=h.numero_orden_id),
											cups as f, planes as i
											where a.plan_id!=" . $_SESSION['CENTROAUTORIZACION']['PLAN'] . "
											and a.tipo_id_paciente='" . $tipo . "' 
											and a.paciente_id='" . $paciente . "' 
											$filtroPlan
											and e.cargo_cups=f.cargo
											and e.sw_estado in('1','2','3') and a.plan_id=i.plan_id
											order by a.plan_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
    }

    /**
     *
     */
    function Refrendar() {

        list($dbconn) = GetDBconn();
        $query = "update os_maestro set fecha_vencimiento='" . $_REQUEST['fecha'] . "'
                                where orden_servicio_id=" . $_REQUEST['orden'] . "
                                and    numero_orden_id=" . $_REQUEST['numor'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->frmError["MensajeError"] = "La Oden Fue Refrendada.";
        $this->DetalleOS($_REQUEST['tipoid'], $_REQUEST['pacienteid']);
        return true;
    }

    /**
     *
     */
    function LlamarFormaCambiarFecha() {
        if ($_REQUEST['AgruOrdenes'] == 0)
            $_REQUEST['AgruOrdenes'] = $_REQUEST['orden'] . "//" . $_REQUEST['numor'];

        $this->FormaCambiarFecha($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['numor'], $_REQUEST['fecha'], $_REQUEST['fechaV'], $_REQUEST['fechaA'], $_REQUEST['plan'], $_REQUEST['AgruOrdenes']);
        return true;
    }

    /**
     *
     */
    function CambiarFecha() {

        $diasf = $this->ObtenerDiasFuncionales();
        $diasref = $diasf[0]['valor'];

        $Fecha = $this->FechaStamp(date("Y-m-d"));
        $infoCadena = explode('/', $Fecha);
        $intervalo = "00:00:00";
        $infoCadena1 = explode(':', $intervalo); //$diasref
        $refrendar = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + $diasref), $infoCadena[2]));

        $infoCadena = explode('/', $_REQUEST['Refrendar']);
        $intervalo = "00:00:00";
        $infoCadena1 = explode(':', $intervalo); //$diasref
        $refrendara = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + 0), $infoCadena[2]));
        $refrendara = strtotime($refrendara);

        $Fecha = $this->FechaStamp(date("Y-m-d"));
        $infoCadena = explode('/', $Fecha);
        $intervalo = "00:00:00";
        $infoCadena1 = explode(':', $intervalo); //$diasref
        $hoy = date("Y-m-d H:i:s", mktime($infoCadena1[0], $infoCadena1[1], 0, $infoCadena[1], ($infoCadena[0] + 0), $infoCadena[2]));
        $hoy = strtotime($hoy);

        $camfe = explode(" ", $refrendar);
        $camfe = explode("-", $camfe[0]);


        $camfe = $camfe[2] . "/" . $camfe[1] . "/" . $camfe[0];

        if ($refrendara < $hoy) {
            $_REQUEST['Refrendar'] = $camfe;
            $_REQUEST['Vencimiento'] = $camfe;
        }

        if (!$_REQUEST['Refrendar'] || !$_REQUEST['Vencimiento'] || !$_REQUEST['Activacion']) {
            if (!$_REQUEST['Refrendar']) {
                $this->frmError["Refrendar"] = 1;
            }
            if (!$_REQUEST['Vencimiento']) {
                $this->frmError["Vencimiento"] = 1;
            }
            if (!$_REQUEST['Activacion']) {
                $this->frmError["Activacion"] = 1;
            }
            $this->frmError["MensajeError"] = "Todas Las Fechas Son Obligatorias.";
            $this->FormaCambiarFecha($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['numor'], $_REQUEST['fecha'], $_REQUEST['fechaV'], $_REQUEST['fechaA'], $_REQUEST['plan']);
            return true;
        }
        /* if($_REQUEST['Activacion'] > $_REQUEST['Vencimiento'])
          {
          $this->frmError["MensajeError"]="La Fecha de Activaci?n debe ser Menor o Igual a la de Vencimiento.";
          $this->FormaCambiarFecha($_REQUEST['tipoid'],$_REQUEST['pacienteid'],$_REQUEST['orden'],$_REQUEST['numor'],$_REQUEST['fecha'],$_REQUEST['fechaV'],$_REQUEST['fechaA'],$_REQUEST['plan']);
          return true;
          } */

        $f = explode('/', $_REQUEST['Refrendar']);
        $_REQUEST['Refrendar'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        $paso = $this->ValidarFecha($_REQUEST['Refrendar']);
        if (empty($paso)) {
            $this->frmError["Refrendar"] = 1;
            $this->FormaCambiarFecha($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['numor'], $_REQUEST['fecha'], $_REQUEST['fechaV'], $_REQUEST['fechaA'], $_REQUEST['plan']);
            return true;
        }

        $f = explode('/', $_REQUEST['Vencimiento']);
        $_REQUEST['Vencimiento'] = $f[2] . '-' . $f[1] . '-' . $f[0];
        $f = explode('/', $_REQUEST['Activacion']);
        $_REQUEST['Activacion'] = $f[2] . '-' . $f[1] . '-' . $f[0];

        $canFeRef = explode(",", $_REQUEST['AgruOrdenes']);
        for ($da = 0; $da < count($canFeRef); $da++) {
            $numOrdenIdS = explode("//", $canFeRef[$da]);
            $_REQUEST['orden'] = $numOrdenIdS[0];
            $_REQUEST['numor'] = $numOrdenIdS[1];

            list($dbconn) = GetDBconn();

            $query = "update os_maestro set
                                    fecha_vencimiento='" . $_REQUEST['Vencimiento'] . "',
                                    fecha_refrendar='" . $_REQUEST['Refrendar'] . "',
                                    fecha_activacion='" . $_REQUEST['Activacion'] . "'
                                    where orden_servicio_id=" . $_REQUEST['orden'] . "
                                    and    numero_orden_id=" . $_REQUEST['numor'] . "";

            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error update os_maestro";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }

        $this->frmError["MensajeError"] = "Las Fechas Fueron Modificadas.";
        $this->DetalleOS($_REQUEST['tipoid'], $_REQUEST['pacienteid']);
        return true;
    }

    function ValidacionCambioProveedor() {
        $tipo = $_REQUEST['tipoid'];
        $paciente = $_REQUEST['pacienteid'];
        $orden = $_REQUEST['orden'];
        $num = $_REQUEST['numor'];
        $cargo = $_REQUEST['cargo'];
        $proveedor = $_REQUEST['proveedor'];
        $tipop = $_REQUEST['tipop'];
        $plan = $_REQUEST['plan'];

        list($dbconn) = GetDBconn();
        if (!empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $query = "SELECT sw_cambio_proveedor FROM userpermisos_centro_autorizacion
											WHERE usuario_id=" . UserGetUID() . " AND plan_id=" . $_REQUEST['plan'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error SELECT sw_cambio_proveedor1";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            if ($result->EOF) {
                $result = '';
                $query = "SELECT sw_cambio_proveedor FROM userpermisos_centro_autorizacion
													WHERE usuario_id=" . UserGetUID() . " AND sw_todos_planes='1'";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error SELECT sw_cambio_proveedor2";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        } else {
            $query = "SELECT sw_cambio_proveedor FROM userpermisos_centro_autorizacion
											WHERE usuario_id=" . UserGetUID() . " AND plan_id=" . $_REQUEST['plan'] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error SELECT sw_cambio_proveedor3";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }

        //si tiene permiso de cambio de proveedor
        if ($result->fields[0] == 1) {
            $this->FormaCambiarProveedor($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['numor'], $_REQUEST['cargo'], $_REQUEST['proveedor'], $_REQUEST['tipop'], $_REQUEST['solicitud']);
            return true;
        } else {
            $Mensaje = 'USTED NO TIENE PERMISO PARA CAMBIAR ESTE PROVEEDOR.';
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleOS', array('tipoid' => $_REQUEST['tipoid'], 'pacienteid' => $_REQUEST['pacienteid']));
            if (!$this->FormaMensaje($Mensaje, 'CENTRO AUTORIZACIONES', $accion, '')) {
                return false;
            }
            return true;
        }


        //$this->FormaCambiarProveedor($_REQUEST['tipoid'],$_REQUEST['pacienteid'],$_REQUEST['orden'],$_REQUEST['numor'],$_REQUEST['cargo'],$_REQUEST['proveedor'],$_REQUEST['tipop']);
        //return true;
    }

    function CambiarProveedor() {
        if ($_REQUEST['Combo'] == -1) {
            $this->frmError["combo"] = 1;
            $this->frmError["MensajeError"] = "Debe Elegir El Proveedor.";
            $this->FormaCambiarProveedor($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['numor'], $_REQUEST['cargo'], $_REQUEST['proveedor'], $_REQUEST['tipop']);
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
        } else {//el proveedor anterior era interno
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
        $this->DetalleOS($_REQUEST['tipoid'], $_REQUEST['pacienteid']);
        return true;
    }

    /**
     *
     */
    function LlamarFormaInformacionOs() {
        list($dbconn) = GetDBconn();
        $query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                                case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO' else 'PARA ATENCION' end as estado,e.sw_estado,
                                 e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
                                g.cargo, g.departamento, l.descripcion as desdpto,
                                h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion, k.nombre as autorizador,
                                m.plan_descripcion, x.observacion as obsanulada, z.descripcion as desanulada
                                from os_ordenes_servicios as a
                                join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id and e.numero_orden_id=" . $_REQUEST['num'] . ")
                                left join os_internas as g on (e.numero_orden_id=g.numero_orden_id
                                and g.numero_orden_id=" . $_REQUEST['num'] . ") left join departamentos as l on(g.departamento=l.departamento)
                                left join os_externas as h on (e.numero_orden_id=h.numero_orden_id
                                and h.numero_orden_id=" . $_REQUEST['num'] . ")
                                left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                                left join os_anuladas as x on(a.orden_servicio_id=x.orden_servicio_id and e.numero_orden_id=x.numero_orden_id)
                                left join os_anuladas_justificacion as z on(z.os_anulada_justificicacion_id=x.os_anulada_justificicacion_id),
                                tipos_afiliado as b, servicios as c, pacientes as d, cups as f, autorizaciones as j, system_usuarios as k, planes as m
                                where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
                                and a.orden_servicio_id=" . $_REQUEST['orden'] . "
                                and a.tipo_id_paciente='" . $_REQUEST['tipoid'] . "'
                                and a.paciente_id='" . $_REQUEST['pacienteid'] . "'
                                and a.plan_id=m.plan_id
                                and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id and (e.cargo_cups=f.cargo)
                                and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                                and j.usuario_id=k.usuario_id
                                order by a.orden_servicio_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $result->GetRowAssoc($ToUpper = false);

        $this->FormaInformacionOs($var);
        return true;
    }

    /*
     *
     */

    function LlamarFormaAnular() {
        $this->FormaAnular($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['num'], $_REQUEST['plan']);
        return true;
    }

    /**
     *
     */
    function AnularOS() {
        if (empty($_REQUEST['Opcion'])) {
            $this->frmError["MensajeError"] = "Debe Elegir el Tipo de Anulaci?n.";
            $this->FormaAnular($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['num'], $_REQUEST['plan']);
            return true;
        }

        if ($_REQUEST['CJ'] == -1) {
            $this->frmError["CJ"] = 1;
            $this->frmError["MensajeError"] = "Debe Elegir Alguna Justificaci?n.";
            $this->FormaAnular($_REQUEST['tipoid'], $_REQUEST['pacienteid'], $_REQUEST['orden'], $_REQUEST['num'], $_REQUEST['plan']);
            return true;
        }

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "update os_maestro set sw_estado=9
                                where orden_servicio_id=" . $_REQUEST['orden'] . "
                                and    numero_orden_id=" . $_REQUEST['num'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $result->Close();

        $query = "insert into os_anuladas
                                values(" . $_REQUEST['orden'] . "," . $_REQUEST['num'] . ",'" . $_REQUEST['Justificacion'] . "',
                                " . UserGetUID() . ",'now()'," . $_REQUEST['CJ'] . ")";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error insert into os_anuladas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $result->Close();
        //liberar la solicitud
        if ($_REQUEST['Opcion'] == 2) {
            $query = "select hc_os_solicitud_id from os_maestro
                          where orden_servicio_id=" . $_REQUEST['orden'] . "
                          and    numero_orden_id=" . $_REQUEST['num'] . "";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error update os_maestro";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            $query = "update hc_os_solicitudes set sw_estado=1
                                    where hc_os_solicitud_id=" . $resulta->fields[0] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error update os_maestro";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }

            $query = "delete from hc_os_autorizaciones
                                    where hc_os_solicitud_id=" . $resulta->fields[0] . "";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error update os_maestro";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $dbconn->CommitTrans();
        $this->frmError["MensajeError"] = "La Orden fue Anulada.";
        $this->DetalleOS($_REQUEST['tipoid'], $_REQUEST['pacienteid']);
        return true;
    }

    /**
     *
     */
    function ActivarOS() {
        list($dbconn) = GetDBconn();
        $query = "update os_maestro set
                                fecha_vencimiento=now() + '1 days',
                                sw_estado=1
                                where orden_servicio_id=" . $_REQUEST['orden'] . "
                                and numero_orden_id=" . $_REQUEST['numorden'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->frmError["MensajeError"] = "La Orden fue Activada.";
        $this->DetalleOS($_REQUEST['tipoid'], $_REQUEST['pacienteid']);
        return true;
    }

    /**
     *
     */
    function ComboJustificarAnuladas() {
        list($dbconn) = GetDBconn();
        $query = "select * from os_anuladas_justificacion";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        return $var;
    }

    /**
     *
     */
    function DatosPlan() {
        list($dbconn) = GetDBconn();
        $query = "SELECT a.plan_descripcion, b.nombre_tercero
                      FROM planes as a, terceros as b
                      WHERE a.plan_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "'
                      and a.tipo_tercero_id=b.tipo_id_tercero
                      and a.tercero_id=b.tercero_id";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $vars = $resulta->GetRowAssoc($ToUpper = false);
        return $vars;
    }

    /**
     * Busca el nombre del paciente
     * @access public
     * @return array
     * @param string tipo de documento
     * @param int numero de documento
     */
    function NombrePaciente($TipoDocumento, $Documento) {
        list($dbconn) = GetDBconn();
        $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre,
                      tipo_id_paciente, paciente_id
                                FROM pacientes
                                WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $vars = $resulta->GetRowAssoc($ToUpper = false);
        return $vars;
    }

    /**
     *
     */
    function Tramite() {
        if (empty($_REQUEST['nombre']) AND empty($_REQUEST['personal'])) {
            if (empty($_REQUEST['nombre'])) {
                $this->frmError["nombre"] = 1;
            }
            $this->frmError["MensajeError"] = "Debe escribir el nombre de quien recibe la informaci?n.";
            $this->FormaTramite();
            return true;
        }

        if (empty($_REQUEST['observacion'])) {
            $this->frmError["observacion"] = 1;
            $this->frmError["MensajeError"] = "Debe escribir la peticion dada al paciente.";
            $this->FormaTramite();
            return true;
        }
        $sw = 0;
        if (!empty($_REQUEST['personal'])) {
            $sw = 1;
        }
        list($dbconn) = GetDBconn();
        $query = "select * from hc_os_autorizaciones
                  where autorizacion_int=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "
                  and autorizacion_ext=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error update os_maestro";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $dbconn->BeginTrans();
        $query = " SELECT nextval('autorizaciones_os_solicitudes_autorizaciones_os_solicitudes_seq')";
        $result = $dbconn->Execute($query);
        $id = $result->fields[0];

        if (empty($_REQUEST['tele'])) {
            $_REQUEST['tele'] = 0;
        }

        $query = "INSERT INTO autorizaciones_os_solicitudes_requerimientos
                  VALUES($id,'" . $_REQUEST['nombre'] . "',$sw," . UserGetUID() . ",'now()','" . $_REQUEST['observacion'] . "','" . $_REQUEST['observacionp'] . "','" . $_REQUEST['tele'] . "')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "INSERT INTO autorizaciones_os_solicitudes_requerimientos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        for ($i = 0; $i < sizeof($var); $i++) {
            $query = "INSERT INTO autorizaciones_os_solicitudes_requerimientos_det
                      VALUES($id,'" . $var[$i]['hc_os_solicitud_id'] . "')";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "delete autorizaciones_solicitudes_cargos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }

        $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE
              tipo_id_paciente='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'] . "'
              AND paciente_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'] . "'
              AND usuario_id=" . UserGetUID() . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $query = "delete from autorizaciones
                  where autorizacion=" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "delete autorizaciones_solicitudes_cargos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();
        $_SESSION['AUTORIZACIONES']['RETORNO']['TRAMITE'] = true;
        $Contenedor = $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
        $Modulo = $_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
        $Tipo = $_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
        $Metodo = $_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
        $argu = $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'];
        $this->ReturnMetodoExterno($Contenedor, $Modulo, $Tipo, $Metodo, $argu);
        return true;
    }

//----------------------  BUSCAR TODOS---------------------------------------------
    /**
     *
     */
    function BuscarSolicitud() {
        list($dbconn) = GetDBconn();
        $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=" . UserGetUID() . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        //unset($_SESSION['CENTROAUTORIZACION']['TODO']);
        unset($_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id']);
        unset($_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente']);
        unset($_SESSION['CENTROAUTORIZACION']['TODO']['nombre_paciente']);
        unset($_SESSION['CENTROAUTORIZACION']['TODO']);

        $tipo_documento = $_REQUEST['TipoDocumento'];
        $documento = $_REQUEST['Documento'];
        $nombres = strtolower($_REQUEST['Nombres']);
        $servicio = $_REQUEST['Servicio'];
        $tipo = $_REQUEST['Tipo'];

        $filtroTipoDocumento = '';
        $filtroDocumento = '';
        $filtroNombres = '';
        $filtroServicio1 = '';
        $filtroServicio2 = '';
        $filtroTipo = '';
        $filtroSolicitud = '';
        $filtroEstado = '';

        if (!empty($_REQUEST['Solicitud'])) {
            $filtroSolicitud = " AND a.hc_os_solicitud_id = '" . $_REQUEST['Solicitud'] . "'";
        }

        if (!empty($tipo_documento)) {
            $filtroTipoDocumento = " AND j.tipo_id_paciente = '$tipo_documento'";
        }

        if ($documento != '') {
            $filtroDocumento = " AND j.paciente_id = '$documento'";
            $filtroEstado = " OR (a.sw_estado = '0' 
                                        AND a.hc_os_solicitud_id NOT IN ( SELECT  b.hc_os_solicitud_id
                                                                          FROM    hc_os_solicitudes a,
                                                                                  os_maestro b
                                                                          WHERE   a.hc_os_solicitud_id = b.hc_os_solicitud_id
                                                                          AND     a.tipo_id_paciente = '" . $tipo_documento . "'
                                                                          AND     a.paciente_id = '" . $documento . "'
                                                                        )
                                    ) ";
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

        if ($servicio != -1) {
            $filtroServicio1 = "and l.servicio='$servicio'";
            $filtroServicio2 = "and j.servicio='$servicio'";
        }
        if ($tipo != -1) {
            $filtroTipo = "and  a.os_tipo_solicitud_id='$tipo'";
        }
        //ELIGIO UN PLAN
        if ($_REQUEST['plan'] != -1) {
            $filtroPlan = "and  a.plan_id=" . $_REQUEST['plan'] . "";
        }

        //buscamos el nivel de autorizacion
        $filtroTodos = " AND sw_todos_planes='1'";
        if ($_REQUEST['plan'] != -1) {
            $filtroTodos = " AND plan_id=" . $_REQUEST['plan'] . "";
        }

        $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] = $_REQUEST['plan'];
        unset($_SESSION['CENTROAUTORIZACION']['TODO']['nivel']);

        $query = "SELECT nivel_autorizador_id
												FROM userpermisos_centro_autorizacion WHERE usuario_id=" . UserGetUID() . "
												$filtroTodos";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $_SESSION['CENTROAUTORIZACION']['TODO']['nivel'] = $result->fields[0];
        $result->Close();
        //---------------------------------------------------			
        if (empty($_REQUEST['Of'])) {
            $_REQUEST['Of'] = 0;
        }

        if (empty($_REQUEST['paso'])) {
            $query = "SELECT X.*,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombres
													FROM
													(
															SELECT DISTINCT *
															FROM		
															(	
																(			SELECT j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, a.plan_id  
																			FROM hc_os_solicitudes a,
																			hc_evoluciones as i, 		
																			ingresos as j,
																			departamentos as l, 
																			servicios as m 					
																			WHERE a.evolucion_id IS NOT NULL
																			and (a.sw_estado='1' $filtroEstado )																			 
																			$filtroSolicitud
																			$filtroPlan
																			$filtroTipo
																			and i.evolucion_id =a.evolucion_id
																			and j.ingreso=i.ingreso 
																			$filtroTipoDocumento
																			$filtroDocumento
																			and i.departamento=l.departamento
																			$filtroServicio1
																			and l.servicio=m.servicio
																)
																UNION
																(			SELECT j.tipo_id_paciente, j.paciente_id, m.sw_prioridad, a.plan_id  
																			FROM hc_os_solicitudes a,
																			hc_os_solicitudes_manuales as j,
																			servicios as m		
																			WHERE a.evolucion_id IS NULL
																			and (a.sw_estado='1' $filtroEstado )
																			$filtroSolicitud
																			$filtroPlan
																			$filtroTipo
																			and j.hc_os_solicitud_id = a.hc_os_solicitud_id
																			$filtroDocumento
																			$filtroTipoDocumento
																			$filtroServicio2
																			and j.servicio=m.servicio
																)
															) AS A ORDER BY A.sw_prioridad desc
													) AS X, pacientes b													
													WHERE b.paciente_id = X.paciente_id 
													AND b.tipo_id_paciente = X.tipo_id_paciente";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al buscar";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $_SESSION['SPY2'] = $result->RecordCount();
            }
            $result->Close();
        }

        $query = "SELECT  X.*, ";
        $query.= "        j.usuario_id, ";
        $query.= "        j.nombre_usuario, ";
        $query.= "        b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombres ";
        $query.= "FROM    ";
        $query.= "			  ( ";
        $query.= "							SELECT DISTINCT * ";
        $query.= "							FROM	";
        $query.= "							( ";
        $query.= "								(			SELECT  j.tipo_id_paciente, j.paciente_id, m.sw_prioridad ";
        $query.= "											FROM    hc_os_solicitudes a, ";
        $query.= "											        hc_evoluciones as i, ";
        $query.= "											        ingresos as j, ";
        $query.= "											        departamentos as l, ";
        $query.= "											        servicios as m 		";
        $query.= "											WHERE a.evolucion_id IS NOT NULL ";
        $query.= "											and (a.sw_estado='1' ";
        $query.= "                          " . $filtroEstado . ")";
        $query.= "											" . $filtroSolicitud . " ";
        $query.= "											" . $filtroPlan . " ";
        $query.= "											" . $filtroTipo . " ";
        $query.= "											and i.evolucion_id =a.evolucion_id ";
        $query.= "											and j.ingreso=i.ingreso ";
        $query.= "											" . $filtroTipoDocumento . " ";
        $query.= "											" . $filtroDocumento . " ";
        $query.= "											and i.departamento=l.departamento ";
        $query.= "											" . $filtroServicio1 . " ";
        $query.= "											and l.servicio=m.servicio ";
        $query.= "								) ";
        $query.= "								UNION ";
        $query.= "								(			SELECT  j.tipo_id_paciente, j.paciente_id, m.sw_prioridad  ";
        $query.= "											FROM    hc_os_solicitudes a, ";
        $query.= "											        hc_os_solicitudes_manuales as j, ";
        $query.= "											        servicios as m		";
        $query.= "											WHERE a.evolucion_id IS NULL ";
        $query.= "											and (a.sw_estado='1' ";
        $query.= "                          " . $filtroEstado . ")";
        $query.= "											" . $filtroSolicitud . " ";
        $query.= "											" . $filtroPlan . " ";
        $query.= "											" . $filtroTipo . " ";
        $query.= "											and j.hc_os_solicitud_id = a.hc_os_solicitud_id ";
        $query.= "											" . $filtroDocumento . " ";
        $query.= "											" . $filtroTipoDocumento . " ";
        $query.= "											" . $filtroServicio2 . " ";
        $query.= "											and j.servicio=m.servicio ";
        $query.= "								) ";
        $query.= "							) AS A ORDER BY A.sw_prioridad desc ";
        $query.= "					) AS X, pacientes b	";
        $query.= "          LEFT JOIN ";
        $query.= "          ( SELECT  OP.usuario_id, ";
        $query.= "                    OP.tipo_id_paciente, ";
        $query.= "                    OP.paciente_id, ";
        $query.= "                    SU.nombre AS nombre_usuario ";
        $query.= "            FROM    hc_os_autorizaciones_proceso OP, ";
        $query.= "                    system_usuarios SU ";
        $query.= "            WHERE   SU.usuario_id = OP.usuario_id ";
        $query.= "          ) j ";
        $query.= "					on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id)			";
        $query.= "					WHERE b.paciente_id = X.paciente_id  ";
        $query.= "					AND b.tipo_id_paciente = X.tipo_id_paciente ";
        $query.= "					LIMIT " . $this->limit . " OFFSET " . $_REQUEST['Of'] . " ";

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
        $this->FormaBuscarTodos($var);
        return true;
    }

    function BuscarProveedorOrden($numero) {
        list($dbconn) = GetDBconn();
        $query = "SELECT g.departamento, l.descripcion as desdpto, 
									i.plan_proveedor_id, i.plan_descripcion as planpro							
									FROM os_maestro as e left join os_internas as g on (e.numero_orden_id=g.numero_orden_id) 
									left join departamentos as l on(g.departamento=l.departamento) 
									left join os_externas as h on (e.numero_orden_id=h.numero_orden_id) 
									left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
									WHERE e.numero_orden_id=$numero";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }

    function LlamarBuscarSolicitud() {
        list($dbconn) = GetDBconn();
        $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=" . UserGetUID() . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        unset($_SESSION['CENTROAUTORIZACION']['ARREGLO']['DETALLE']);
        unset($_SESSION['CENTROAUTORIZACION']['paciente_id']);
        unset($_SESSION['CENTROAUTORIZACION']['tipo_id_paciente']);
        unset($_SESSION['CENTROAUTORIZACION']['nombre_paciente']);

        $this->FormaBuscarTodos($Busqueda, $arr, $f);
        return true;
    }

    /**
     *
     */
    function ValidarEquivalencias($cargo) {
        list($dbconn) = GetDBconn();
        $query = "select count(a.cargo)
                  from tarifarios_equivalencias as a
                  join tarifarios_detalle as h
                  on (a.cargo_base='$cargo' and h.cargo=a.cargo and h.tarifario_id=a.tarifario_id)
                  where a.cargo_base='$cargo'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $var = $result->fields[0];
        }

        return $var;
    }

    /**
     *
     */
    function ValidarContrato($cargo, $plan) {
        //$dbconn->debug=true;
        list($dbconn) = GetDBconn();
        $query = "( 	select r.plan_id
												from tarifarios_equivalencias as a, tarifarios_detalle as h,
												plan_tarifario as r
												where a.cargo_base='$cargo' and h.cargo=a.cargo
												and h.tarifario_id=a.tarifario_id
												and r.plan_id=$plan and h.grupo_tarifario_id=r.grupo_tarifario_id
												and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
												and h.tarifario_id=r.tarifario_id
												and excepciones(r.plan_id,r.tarifario_id,h.cargo)=0
										)
										UNION
										(
												SELECT b.plan_id
												FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e,
												tarifarios_equivalencias as c
												WHERE c.cargo_base='$cargo'
												and a.cargo=c.cargo
												and a.tarifario_id=c.tarifario_id
												and b.plan_id = $plan AND
												b.tarifario_id = a.tarifario_id AND
												b.sw_no_contratado = 0 AND
												b.cargo = a.cargo AND
												e.grupo_tarifario_id = a.grupo_tarifario_id AND
												e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if (!$result->EOF) {
            $var = $result->RecordCount();
        }

        return $var;
    }

    /**
     *
     */
    function ValidarContratoEqui($tarifario, $cargo, $plan) {
        list($dbconn) = GetDBconn();
        $query = "( 	select r.plan_id
												from tarifarios_detalle as h, plan_tarifario as r
												where h.cargo='$cargo' and h.tarifario_id='$tarifario'
												and r.plan_id=$plan and h.grupo_tarifario_id=r.grupo_tarifario_id
												and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
												and h.tarifario_id=r.tarifario_id
												and excepciones(r.plan_id,h.tarifario_id,h.cargo)=0
										)
										UNION
										(
												SELECT b.plan_id
												FROM tarifarios_detalle a, excepciones b, subgrupos_tarifarios e
												WHERE a.cargo='$cargo' and a.tarifario_id='$tarifario'
												and b.plan_id = $plan AND
												b.tarifario_id = a.tarifario_id AND
												b.sw_no_contratado = 0 AND
												b.cargo = a.cargo AND
												e.grupo_tarifario_id = a.grupo_tarifario_id AND
												e.subgrupo_tarifario_id = a.subgrupo_tarifario_id
                    )";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if (!$result->EOF) {
            $var = $result->RecordCount();
        }

        return $var;
    }

    /**
     *
     */
    function BuscarOrdenTodos() {
        $tipo_documento = $_REQUEST['TipoDocumento'];
        $documento = $_REQUEST['Documento'];
        $nombres = strtolower($_REQUEST['Nombres']);
        $fecha = $_REQUEST['Fecha'];
        $orden = $_REQUEST['Orden'];
        if ($_REQUEST['Fecha'] == 'TODAS LAS FECHAS') {
            $fecha = '';
        }

        //ELIGIO UN PLAN
        $filtroPlan = '';
        if ($_REQUEST['plan'] != -1) {
            $filtroPlan = "and  a.plan_id=" . $_REQUEST['plan'] . "";
        }
        $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] = $_REQUEST['plan'];

        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }


        list($dbconn) = GetDBconn();
        $query = "select a.orden_servicio_id, e.numero_orden_id,e.fecha_refrendar
											from os_ordenes_servicios as a
											join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)                            
											where e.sw_estado in('1')
											and e.fecha_vencimiento < now()
											and e.fecha_refrendar < now()";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error1";
            $this->fileError = __FILE__;
            $this->lineError = __LINE__;
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $cont = $result->RecordCount();
        }
        while (!$result->EOF) {
            $query = "update os_maestro set sw_estado='8'
                                                where orden_servicio_id = " . $result->fields[0] . "
                                                and numero_orden_id = " . $result->fields[1] . "";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error2";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if ($cont > 1) {
                $result->MoveNext();
            }
        }
        $result->Close();

        $filtroTipoDocumento = '';
        $filtroDocumento = '';
        $filtroNombres = '';
        $filtroFecha = '';
        $filtroOrden = '';

        if (!empty($orden)) {
            $filtroOrden = " AND a.orden_servicio_id=$orden";
        }

        if (!empty($tipo_documento)) {
            $filtroTipoDocumento = " AND a.tipo_id_paciente = '$tipo_documento'";
        }

        if ($documento != '') {
            $filtroDocumento = " AND a.paciente_id = '$documento'";
        }

        if ($nombres != '') {
            $a = explode(' ', $nombres);
            foreach ($a as $k => $v) {
                if (!empty($v)) {
                    $filtroNombres.=" and (upper(c.primer_nombre||' '||c.segundo_nombre||' '||
                                                                c.primer_apellido||' '||c.segundo_apellido) like '%" . strtoupper($v) . "%')";
                }
            }
        }

        if ($fecha != '') {
            $filtroFecha = "AND date(b.fecha_activacion) = date('$fecha')";
        }

        if (empty($_REQUEST['Of'])) {
            $_REQUEST['Of'] = 0;
        }

        //REVISAMOS SI ENTRO POR VARIOS PLANES O POR UNO ESPECIFICO
        if ($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] != -1 AND !empty($_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'])) {
            $filtroPlan = " AND a.plan_id=" . $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] . "";
        }

        if (empty($_REQUEST['pasoOS'])) {
            $query = "SELECT DISTINCT a.tipo_id_paciente, a.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
													FROM
															(
																	SELECT
																			a.tipo_id_paciente, a.paciente_id,a.tipo_afiliado_id, a.plan_id
																	FROM
																			os_ordenes_servicios as a,
																			os_maestro as b
																	WHERE
																			b.orden_servicio_id = a.orden_servicio_id
																			AND b.sw_estado IN ('1','2','3','7','8','9')
																			$filtroTipoDocumento $filtroDocumento $filtroNombres
																			$filtroOrden
																			$filtroFecha													
															) AS a,
															tipos_afiliado as b,
															pacientes as c													
													WHERE
													b.tipo_afiliado_id = a.tipo_afiliado_id
													$filtroPlan
													and a.paciente_id = c.paciente_id
													and a.tipo_id_paciente = c.tipo_id_paciente
													$filtroNombres";
            /* $query = "select distinct a.tipo_id_paciente, a.paciente_id,
              c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
              from os_ordenes_servicios as a
              join os_maestro as b on (b.orden_servicio_id=a.orden_servicio_id)
              join userpermisos_centro_autorizacion as p on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id
              OR p.sw_todos_planes=1),
              pacientes as c
              where a.paciente_id=c.paciente_id and a.tipo_id_paciente=c.tipo_id_paciente
              and b.sw_estado in ('1','2','3','7','8','9')
              $filtroTipoDocumento $filtroDocumento $filtroNombres
              $filtroOrden
              $filtroPlan
              $filtroFecha"; */
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Tabal autorizaiones";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if (!$result->EOF) {
                $_SESSION['SPY3'] = $result->RecordCount();
            }
            $result->Close();
        }

        $query = "SELECT DISTINCT a.tipo_id_paciente, a.paciente_id, c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre
												FROM
														(
																SELECT
																		a.tipo_id_paciente, a.paciente_id, a.tipo_afiliado_id, a.plan_id
																FROM
																		os_ordenes_servicios as a,
																		os_maestro as b
																WHERE
																		b.orden_servicio_id = a.orden_servicio_id
																		AND b.sw_estado IN ('1','2','3','7','8','9')
																		$filtroTipoDocumento $filtroDocumento $filtroNombres
																		$filtroOrden
																		$filtroFecha													
														) AS a,
														tipos_afiliado as b,
														pacientes as c												
												WHERE
												b.tipo_afiliado_id = a.tipo_afiliado_id
												$filtroPlan
												and a.paciente_id = c.paciente_id
												and a.tipo_id_paciente = c.tipo_id_paciente
												$filtroNombres LIMIT " . $this->limit . " OFFSET " . $_REQUEST['Of'] . "";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Tabal autorizaiones";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }
        $result->Close();
        $this->FormaMetodoBuscarOS($var);
        return true;
    }

//------------------AUTORIZACIONES-----------------------------------------
    /**
     *
     */
    function PedirAutorizacionTodos() {
        //valida si eligieron algun cargo
        $f = 0;
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Auto')) {
                $f = 1;
            }
        }
        if ($f == 0) {
            $this->frmError["MensajeError"] = "ERROR DATOS VACIOS: Debe elegir alguna Solicitud para Autorizar.";
            $this->DetalleSolicituTodos();
            return true;
        }

        list($dbconn) = GetDBconn();
        unset($_SESSION['AUTORIZACIONES']);
        foreach ($_REQUEST as $k => $v) {
            if (substr_count($k, 'Auto')) {
                $arr = explode(',', $v);

                if (empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'])) {
                    $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'] = $arr[2];
                }
                $_SESSION['CENTROAUTORIZACION']['SERVICIO'] = $arr[3];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'][$arr[4]][$arr[0]][$arr[1]][$arr[3]][$arr[5]] = $arr[2];
            }
        }

        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'] = $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'];
        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'] = $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'];
        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO'] = 'CENTROAUTORIZACION';
        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] = $_REQUEST['plan'];
        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'] = array();
        $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'] = 'app';
        $_SESSION['AUTORIZACIONES']['RETORNO']['modulo'] = 'CentroAutorizacion';
        $_SESSION['AUTORIZACIONES']['RETORNO']['tipo'] = 'user';
        $_SESSION['AUTORIZACIONES']['RETORNO']['metodo'] = 'RetornoAutorizacionTodos';

        $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] = $_REQUEST['plan'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['SERVICIO'] = $_REQUEST['servicio'];

        if (!$this->ValidarCentroAutorizacion())
            return false;
        return true;
    }

    /**
     * Llama el modulo de autorizaciones
     * @access public
     * @return boolean
     */
    function RetornoAutorizacionTodos() {
        //$dbconn->debug=true;
        $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_afiliado_id'] = $_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['rango'] = $_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['semanas'] = $_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
        $Mensaje = $_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['ext'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'] = $_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'] = $_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['observacion'] = $_SESSION['AUTORIZACIONES']['RETORNO']['ObservacionesOS'];
        $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] = $_SESSION['AUTORIZACIONES']['RETORNO']['plan_id'];

        //Se coloca esta validacion porque mas adelante esta un query que
        //hace referencia a estas variables de session 'centarlhosp'
        //Ademas esta el query111, que asumimos que estaba bien.
        //analizar
        //MauroB, Lorena
        unset($_SESSION['CENTRALHOSP']['Autorizacion']);
        unset($_SESSION['CENTRALHOSP']['PLAN']);
        if (empty($_SESSION['CENTRALHOSP']['Autorizacion'])) {
            $_SESSION['CENTRALHOSP']['Autorizacion'] = $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'];
        }
        if (empty($_SESSION['CENTRALHOSP']['PLAN'])) {
            $_SESSION['CENTRALHOSP']['PLAN'] = $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'];
        }
        //Fin MauroB, Lorena
        list($dbconn) = GetDBconn();
        $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE
          tipo_id_paciente='" . $_SESSION['CENTROAUTORIZACION']['TODO']['tipo_id_paciente'] . "'
          AND paciente_id='" . $_SESSION['CENTROAUTORIZACION']['TODO']['paciente_id'] . "'
          AND usuario_id=" . UserGetUID() . "";
        $dbconn->Execute($query);

        /* if(!empty($_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'])
          AND  empty($_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion']))
          {
          $this->FormaBuscarTodos();
          return true;
          } */

        if (!empty($_SESSION['AUTORIZACIONES']['RETORNO']['TRAMITE'])) {
            $Mensaje = 'La toma de requerimientos se realizo.';
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicituTodos');
            if (!$this->FormaMensaje($Mensaje, 'CENTRO AUTORIZACIONES', $accion, '')) {
                return false;
            }
            return true;
        }
        //si no fue autorizada
        if (!empty($_SESSION['AUTORIZACIONES']['NOAUTO'])) {
            $noauto = ", sw_no_autorizado='1' ";
        }

        unset($_SESSION['AUTORIZACIONES']);

        if (empty($_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'])
                AND empty($_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'])) {
            //if(empty($_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion']))
            $Mensaje = 'No se pudo realizar la Autorizaci?n para la Orden.';
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'DetalleSolicituTodos');
            if (!$this->FormaMensaje($Mensaje, 'CENTRO AUTORIZACIONES', $accion, '')) {
                return false;
            }
            return true;
        }

        $query = "select a.hc_os_solicitud_id
                    from hc_os_autorizaciones as a
                    where a.autorizacion_int=" . $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'] . "";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error select hc_os_solicitud_id";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        /*
          while(!$result->EOF)
          {
          $query = "UPDATE hc_os_solicitudes SET sw_estado=0 $noauto
          WHERE hc_os_solicitud_id=".$result->fields[0]."";
          $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error UPDATE  hc_os_solicitudes ";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
          }
          $result->MoveNext();
          }
         */
        if (!empty($_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'])
                AND empty($_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'])) {
            $Mensaje = 'No se Autorizo la Orden.';
            $accion = ModuloGetURL('app', 'CentroAutorizacion', 'user', 'FormaBuscarTodos');
            if (!$this->FormaMensaje($Mensaje, 'CENTRO AUTORIZACIONES', $accion, '')) {
                return false;
            }
            return true;
        }
        $query = "
										SELECT x.*, h.descripcion, r.descripcion as descar
										FROM
										((							
													SELECT a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
															a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
															q.servicio,a.evento_soat, a.des_especilidad
													FROM
															(
																	SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, NULL as servicio, e.departamento, b.cargo as cargo_base,soat.evento as evento_soat, INT.descripcion as des_especilidad
																	FROM
																			hc_os_autorizaciones as a,
																			hc_os_solicitudes as b
																			LEFT JOIN (SELECT   a.hc_os_solicitud_id,
																								b.descripcion
																					   FROM		hc_os_solicitudes_interconsultas a,
																								especialidades b
																						WHERE	a.especialidad = b.especialidad) AS INT
																					ON (INT.hc_os_solicitud_id = b.hc_os_solicitud_id),
																			hc_evoluciones as e
																			left join ingresos_soat soat on (e.ingreso=soat.ingreso),
																			tarifarios_equivalencias as n
																	WHERE
																			a.autorizacion_int = " . $_SESSION['CENTRALHOSP']['Autorizacion'] . "
																			AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND b.plan_id = " . $_SESSION['CENTRALHOSP']['PLAN'] . "
																			AND e.evolucion_id = b.evolucion_id
																			AND n.cargo_base = b.cargo
															) AS a,
															departamentos as q
													WHERE q.departamento = a.departamento
											)
											UNION
											(
													SELECT  a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
																	a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
																	a.servicio,a.evento_soat, a.des_especilidad
													FROM
															(
																	SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, e.servicio, e.departamento, b.cargo as cargo_base,e.evento_soat, INT.descripcion as des_especilidad
																	FROM
																			hc_os_autorizaciones as a,
																			hc_os_solicitudes as b
																			LEFT JOIN (SELECT   a.hc_os_solicitud_id,
																								b.descripcion
																					   FROM		hc_os_solicitudes_interconsultas a,
																								especialidades b
																						WHERE	a.especialidad = b.especialidad) AS INT
																					ON (INT.hc_os_solicitud_id = b.hc_os_solicitud_id),
																			hc_os_solicitudes_manuales as e,
																			tarifarios_equivalencias as n
																	WHERE
																			a.autorizacion_int = " . $_SESSION['CENTRALHOSP']['Autorizacion'] . "
																			AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
																			AND b.plan_id = " . $_SESSION['CENTRALHOSP']['PLAN'] . "
																			AND e.hc_os_solicitud_id = b.hc_os_solicitud_id
																			AND n.cargo_base = b.cargo
															) AS a LEFT JOIN departamentos as q ON (q.departamento=a.departamento)
											)) as x,
												cups as r,
												tarifarios_detalle as h,
												plan_tarifario as z	
										WHERE
												r.cargo = x.cargos
												AND (h.tarifario_id = x.tarifario_id AND h.cargo = x.cargo)
												AND z.plan_id = " . $_SESSION['CENTRALHOSP']['PLAN'] . "
												AND z.grupo_tarifario_id = h.grupo_tarifario_id
												AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
												AND h.tarifario_id = z.tarifario_id
												ORDER BY x.hc_os_solicitud_id						
										";

        /* $query111 = "(
          SELECT a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
          a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
          h.descripcion, r.descripcion as descar, q.servicio
          FROM
          (
          SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, NULL as servicio, e.departamento, b.cargo as cargo_base
          FROM
          hc_os_autorizaciones as a,
          hc_os_solicitudes as b,
          hc_evoluciones as e,
          tarifarios_equivalencias as n
          WHERE
          a.autorizacion_int = ".$_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion']."
          AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
          AND b.plan_id = ".$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']."
          AND e.evolucion_id = b.evolucion_id
          AND n.cargo_base = b.cargo
          ) AS a,
          cups as r,
          departamentos as q,
          tarifarios_detalle as h,
          plan_tarifario as z
          WHERE
          r.cargo = a.cargo_base
          AND q.departamento = a.departamento
          AND (h.tarifario_id = a.tarifario_id AND h.cargo = a.cargo)
          AND z.plan_id = ".$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']."
          AND z.grupo_tarifario_id = h.grupo_tarifario_id
          AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
          AND h.tarifario_id = z.tarifario_id
          ORDER BY a.hc_os_solicitud_id
          )
          UNION
          (
          SELECT  a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
          a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
          h.descripcion, r.descripcion as descar, a.servicio
          FROM
          (
          SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, e.servicio, e.departamento, b.cargo as cargo_base
          FROM
          hc_os_autorizaciones as a,
          hc_os_solicitudes as b,
          hc_os_solicitudes_manuales as e,
          tarifarios_equivalencias as n
          WHERE
          a.autorizacion_int = ".$_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion']."
          AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
          AND b.plan_id = ".$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']."
          AND e.hc_os_solicitud_id = b.hc_os_solicitud_id
          AND n.cargo_base = b.cargo
          ) AS a LEFT JOIN departamentos as q ON (q.departamento=a.departamento),
          cups as r,
          tarifarios_detalle as h,
          plan_tarifario as z
          WHERE
          r.cargo = a.cargo_base
          AND (h.tarifario_id = a.tarifario_id AND h.cargo = a.cargo)
          AND z.plan_id = ".$_SESSION['CENTROAUTORIZACION']['TODO']['PLAN']."
          AND z.grupo_tarifario_id = h.grupo_tarifario_id
          AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
          AND h.tarifario_id = z.tarifario_id
          ORDER BY a.hc_os_solicitud_id
          )"; */
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error select ";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while (!$result->EOF) {
            $var[] = $result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
        }

        $this->FormaListadoCargos($var);
        return true;
    }

    /**
     *
     */
    function DatosTramite($id) {
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

        $cont = $result->RecordCount();
        if (!$result->EOF) {
            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $result->Close();
        return $var;
    }

    /**
     *
     */
    function MultiplesBD() {
        list($dbconn) = GetDBconn();
        $query = "select meses_consulta_base_datos from planes
                        where plan_id='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'] . "'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        return $result->fields[0];
    }

    /**
     *
     */
    function BuscarEvolucion() {
        $var = '';
        list($dbconn) = GetDBconn();
        $query = "select b.evolucion_id from hc_evoluciones as b
                  where b.ingreso='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'] . "'
                  and b.fecha_cierre=(select max(fecha_cierre)
                  from hc_evoluciones  where ingreso='" . $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso'] . "')";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $var = $result->fields[0];
        }

        return $var;
    }

    /**
     *
     */
    function BuscarSwHc() {
        list($dbconn) = GetDBconn();
        $query = "select sw_hc from autorizaciones_niveles_autorizador
                  where nivel_autorizador_id='" . $_SESSION['CENTROAUTORIZACION']['NIVEL'] . "'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if (!$result->EOF) {
            $var = $result->fields[0];
        }

        return $var;
    }

    /**
     *
     */
    function CantidadMeses($plan) {
        list($dbconn) = GetDBconn();
        $sql = "select meses_consulta_base_datos from planes where plan_id=$plan;";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $result->Close();
        return $result->fields[0];
    }

    /**
     *
     */
    function DatosBD($TipoId, $PacienteId, $Plan) {
        if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php")) {
            $this->error = "Error";
            $this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
            return false;
        }
        if (!class_exists('BDAfiliados')) {
            $this->error = "Error";
            $this->mensajeDeError = "no existe BDAfiliados";
            return false;
        }

        $class = New BDAfiliados($TipoId, $PacienteId, $Plan);
        if ($class->GetDatosAfiliado() == false) {
            $this->frmError["MensajeError"] = $class->mensajeDeError;
        }

        if (!empty($class->salida)) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    function ClasificarPlan($plan) {
        list($dbconn) = GetDBconn();
        $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
                    FROM planes
                    WHERE plan_id='$plan'";
        $results = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var = $results->GetRowAssoc($ToUpper = false);
        $results->Close();
        return $var;
    }

    /**
     *
     */
    function Planes() {
        list($dbconn) = GetDBconn();
        $query = "select empresa_id,sw_todos_planes from userpermisos_centro_autorizacion
                  where usuario_id=" . UserGetUID() . " and sw_todos_planes='1'";
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
                $query = "SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id, a.sw_tipo_plan, a.sw_afiliacion, a.meses_consulta_base_datos, 1 as todos
                      FROM planes as a
                      WHERE a.fecha_final >= now() and a.estado='1'
                      and a.fecha_inicio <= now()
                      and empresa_id='" . $k . "'";
                $results = $dbconn->Execute($query);
                if (!$results->EOF) {
                    while (!$results->EOF) {
                        $var[] = $results->GetRowAssoc($ToUpper = false);
                        $results->MoveNext();
                    }
                }
            }
        } else {
            $query = "SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id, a.sw_tipo_plan, a.sw_afiliacion, a.meses_consulta_base_datos
                      FROM planes as a, userpermisos_centro_autorizacion as b
                      WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
                      and b.usuario_id=" . UserGetUID() . "
                      and b.plan_id=a.plan_id";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }

        $result->Close();
        return $var;
    }

//--------------------------REPORTE--------------------------------------------------

    /**
     *
     */
    function EncabezadoReporte($orden, $tipo, $paciente, $afiliado, $plan) {
        list($dbconn) = GetDBconn();
        $query = "( select b.tipo_afiliado_nombre, s.rango,
                  d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_apellido as nombre,
                  n.nombre as usuario, n.usuario_id, u.departamento, v.municipio, t.tipo_id_tercero, t.id,
                  t.razon_social, t.direccion, t.telefonos,w.nombre_tercero, p.plan_descripcion,
                   p.nombre_cuota_moderadora, p.nombre_copago, a.tipo_id_paciente,a.paciente_id,
                  a.tipo_afiliado_id, a.plan_id, a.rango, a.semanas_cotizadas, a.servicio
                  from os_ordenes_servicios as a
                  join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                  left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
                  planes as p
                  left join terceros as w on(w.tipo_id_tercero=p.tipo_tercero_id and w.tercero_id=p.tercero_id),
                  hc_evoluciones as r left join cuentas as s on(s.ingreso=r.ingreso)
                  left join empresas as t on(s.empresa_id=t.empresa_id),
                  tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  tipos_afiliado as b, pacientes as d
                  where a.orden_servicio_id=" . $orden . "
                  and n.usuario_id=" . UserGetUID() . "
                  and a.tipo_afiliado_id='" . $afiliado . "'
                  and a.tipo_afiliado_id=b.tipo_afiliado_id
                  and a.plan_id='" . $plan . "'
                  and a.plan_id=p.plan_id
                  and q.evolucion_id is not null
                  and a.tipo_id_paciente='" . $tipo . "'
                  and a.paciente_id='" . $paciente . "'
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
                  a.tipo_afiliado_id, a.plan_id, a.rango, a.semanas_cotizadas, a.servicio
                  from os_ordenes_servicios as a
                  join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                  left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id),
                  planes as p, hc_os_solicitudes_manuales as x,
                  terceros as w,empresas as t,
                  tipo_dptos as u, tipo_mpios as v, system_usuarios as n,
                  tipos_afiliado as b, pacientes as d
                  where a.orden_servicio_id=" . $orden . "
                  and n.usuario_id=" . UserGetUID() . "
                  and a.tipo_afiliado_id='" . $afiliado . "'
                  and a.tipo_afiliado_id=b.tipo_afiliado_id
                  and a.plan_id='" . $plan . "'
                  and e.hc_os_solicitud_id=x.hc_os_solicitud_id
                  and a.plan_id=p.plan_id
                  and w.tipo_id_tercero=p.tipo_tercero_id
                  and w.tercero_id=p.tercero_id
                  and q.evolucion_id is null
                  and a.tipo_id_paciente='" . $tipo . "'
                  and a.paciente_id='" . $paciente . "'
                  and a.tipo_id_paciente=d.tipo_id_paciente
                  and a.paciente_id=d.paciente_id
                  and x.empresa_id=t.empresa_id
                  and t.tipo_pais_id=u.tipo_pais_id and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                )  ";
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
    function EncabezadoReporteSolicitud($solicitud, $tipo, $paciente) {
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
                  where q.hc_os_solicitud_id=" . $solicitud . "
                  and q.evolucion_id is not null
                  and n.usuario_id=" . UserGetUID() . "
                  and s.tipo_afiliado_id=b.tipo_afiliado_id
                  and q.plan_id=p.plan_id
                  and d.tipo_id_paciente='" . $tipo . "'
                  and d.paciente_id='" . $paciente . "'
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
                  where q.hc_os_solicitud_id=" . $solicitud . "
                  and q.evolucion_id is null
                  and x.tipo_afiliado_id=b.tipo_afiliado_id
                  and x.hc_os_solicitud_id=" . $solicitud . "
                  and n.usuario_id=" . UserGetUID() . " and q.plan_id=p.plan_id
                  and d.tipo_id_paciente='" . $tipo . "'
                  and d.paciente_id='" . $paciente . "'
                  and x.empresa_id=t.empresa_id and
                  t.tipo_pais_id=u.tipo_pais_id and t.tipo_dpto_id=u.tipo_dpto_id
                  and t.tipo_pais_id=v.tipo_pais_id and t.tipo_dpto_id=v.tipo_dpto_id
                  and t.tipo_mpio_id=v.tipo_mpio_id
                )";
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

        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $empresa = $_SESSION['CENTROAUTORIZACION']['EMPRESA'];
        } else {
            $empresa = $_SESSION['CENTROAUTORIZACION']['TODO']['EMPRESA'];
        }

        $var[0] = $this->EncabezadoReporte($_REQUEST['orden'], $_REQUEST['tipoid'], $_REQUEST['paciente'], $_REQUEST['afiliado'], $_REQUEST['plan']);

        list($dbconn) = GetDBconn();
        $query = "(SELECT 
            
                        (SELECT PPU.direccion
                        FROM departamentos cq INNER JOIN planes_proveedores_unidades_funcionales PPU
                                ON (PPU.empresa_id = cq.empresa_id 
                                        AND PPU.	centro_utilidad = cq.centro_utilidad 
                                        AND PPU.unidad_funcional = cq.unidad_funcional)
                        WHERE cq.departamento=l.DEPARTAMENTO AND PPU.plan_proveedor_id = i.plan_proveedor_id)AS dirpro,
                        (SELECT PPU.telefono
                        FROM departamentos cq INNER JOIN planes_proveedores_unidades_funcionales PPU
                                ON (PPU.empresa_id = cq.empresa_id 
                                        AND PPU.	centro_utilidad = cq.centro_utilidad 
                                        AND PPU.unidad_funcional = cq.unidad_funcional)
                        WHERE cq.departamento=l.DEPARTAMENTO AND PPU.plan_proveedor_id = i.plan_proveedor_id
                        )AS telpro,
            
                        a.*,
                        e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                        e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                        f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                        h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
                        z.tarifario_id, z.cargo, y.requisitos,
                        i.plan_descripcion as nompro, x.direccion  as dirprodea, x.telefono as telprodea,
                        s.descripcion as descar, NULL as profesional,q.evolucion_id, n.observacion as obsapoyo,
                        o.observacion as obsinter, o.especialidad, a.observacion
                        ,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
                        ,HH.observacion as obsqx
                  FROM os_ordenes_servicios as a
                        join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id and e.sw_estado in('1','2','3','7'))
                        left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                        left join departamentos as l on(g.departamento=l.departamento)
                        left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                        left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                        join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_interconsultas as o on(o.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_acto_qx as HH on(HH.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join especialidades as AB on(AB.especialidad=o.especialidad )
                        join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
                        join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                        left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                        join cups as f on(e.cargo_cups=f.cargo)
                        left join hc_apoyod_requisitos as y on(f.cargo=y.cargo)
                  WHERE a.orden_servicio_id=" . $_REQUEST['orden'] . "
                      and a.tipo_afiliado_id='" . $_REQUEST['afiliado'] . "'
                      and a.plan_id='" . $_REQUEST['plan'] . "'
                      and q.evolucion_id is not null
                      and a.tipo_id_paciente='" . $_REQUEST['tipoid'] . "'
                      and a.paciente_id='" . $_REQUEST['paciente'] . "'
                  ORDER BY q.evolucion_id
                  )
                  UNION
                  (SELECT 
                  
                        (SELECT PPU.direccion
                        FROM departamentos cq INNER JOIN planes_proveedores_unidades_funcionales PPU
                                ON (PPU.empresa_id = cq.empresa_id 
                                        AND PPU.	centro_utilidad = cq.centro_utilidad 
                                        AND PPU.unidad_funcional = cq.unidad_funcional)
                        WHERE cq.departamento=m.DEPARTAMENTO AND PPU.plan_proveedor_id = i.plan_proveedor_id)AS dirpro,
                        (SELECT PPU.telefono
                        FROM departamentos cq INNER JOIN planes_proveedores_unidades_funcionales PPU
                                ON (PPU.empresa_id = cq.empresa_id 
                                        AND PPU.	centro_utilidad = cq.centro_utilidad 
                                        AND PPU.unidad_funcional = cq.unidad_funcional)
                        WHERE cq.departamento=m.DEPARTAMENTO AND PPU.plan_proveedor_id = i.plan_proveedor_id)AS telpro,

                        a.*,
                        e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                        e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                        f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                        h.cargo as cargoext,  i.plan_proveedor_id, i.plan_descripcion as planpro,
                        z.tarifario_id, z.cargo, y.requisitos,
                        i.plan_descripcion as nompro, x.direccion  as dirprodea, x.telefono as telprodea,
                        s.descripcion as descar, m.profesional, NULL, n.observacion as obsapoyo,
                        o.observacion as obsinter, o.especialidad, a.observacion
                        ,AB.descripcion as especialidad_nombre, BB.observacion as obsnoqx
                        ,HH.observacion as obsqx
                  FROM os_ordenes_servicios as a
                        join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id and e.sw_estado in('1','2','3','7'))
                        left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                        left join departamentos as l on(g.departamento=l.departamento)
                        left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                        left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                        join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_interconsultas as o on(o.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_no_quirurgicos as BB on(BB.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join hc_os_solicitudes_acto_qx as HH on(HH.hc_os_solicitud_id=q.hc_os_solicitud_id)
                        left join especialidades as AB on(AB.especialidad=o.especialidad )
                        join os_maestro_cargos as z on(z.numero_orden_id=e.numero_orden_id)
                        join tarifarios_detalle as s on(s.cargo=z.cargo and s.tarifario_id=z.tarifario_id)
                        left join terceros as x on(x.tipo_id_tercero=i.tipo_id_tercero and x.tercero_id=i.tercero_id)
                        join cups as f on(e.cargo_cups=f.cargo)
                        left join hc_apoyod_requisitos as y on(f.cargo=y.cargo),
                        hc_os_solicitudes_manuales as m
                  WHERE a.orden_servicio_id=" . $_REQUEST['orden'] . "
                  and a.tipo_afiliado_id='" . $_REQUEST['afiliado'] . "'
                  and q.evolucion_id is null
                  and a.plan_id='" . $_REQUEST['plan'] . "'
                  and a.tipo_id_paciente='" . $_REQUEST['tipoid'] . "'
                  and a.paciente_id='" . $_REQUEST['paciente'] . "'
                  and q.hc_os_solicitud_id=m.hc_os_solicitud_id
                  order by m.profesional
                  )";
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

            if (!empty($_REQUEST['regreso'])) {//cuando es la impresion desde la autorizacion
                $this->$_REQUEST['regreso']();
            }
            if (!empty($_REQUEST['regreso2'])) {//cuando es la impresion es desde listadoo
                $this->$_REQUEST['regreso2']($_REQUEST['tipoid'], $_REQUEST['paciente']);
            }
            return true;
        } else {
            if ($_REQUEST['parametro_retorno'] == '1') {
                IncludeLib("reportes/ordenservicio");
                GenerarOrden($var);
                if (is_array($var)) {
                    $RUTA = $_ROOT . "cache/ordenservicio" . $_REQUEST['orden'] . ".pdf";
                    $mostrar = "\n<script language='javascript'>\n";
                    $mostrar.="var rem=\"\";\n";
                    $mostrar.="  function abreVentana(){\n";
                    $mostrar.="    var nombre=\"\"\n";
                    $mostrar.="    var url2=\"\"\n";
                    $mostrar.="    var str=\"\"\n";
                    $mostrar.="    var ALTO=screen.height\n";
                    $mostrar.="    var ANCHO=screen.width\n";
                    $mostrar.="    var nombre=\"REPORTE\";\n";
                    $mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
                    $mostrar.="    var url2 ='$RUTA';\n";
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
                if (!empty($_REQUEST['regreso'])) {//cuando es la impresion desde la autorizacion
                    $this->$_REQUEST['regreso']($vector, 3);
                }
                if (!empty($_REQUEST['regreso2'])) {//cuando es la impresion es desde listadoo
                    $this->$_REQUEST['regreso2']($_REQUEST['tipoid'], $_REQUEST['paciente'], $vector, 3);
                }
                return true;
            }
        }
    }

    /**
     *
     */
    function ReporteSolicitudesNoAuto() {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

        $var[0] = $this->EncabezadoReporteSolicitud($_REQUEST['solicitud'], $_REQUEST['tipoid'], $_REQUEST['paciente']);
        $var[1] = $_REQUEST['datos'];


        $classReport = new reports;

        if ($_REQUEST['pos'] == 1) {
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pos');
            $reporte = $classReport->PrintReport('pos', 'app', 'CentroAutorizacion', 'solicitudesnoautorizadas', $var, $impresora, $orientacion = '', $unidades = '', $formato = '', $html = 1);
        } else {
            $impresora = $classReport->GetImpresoraPredeterminada($tipo_reporte = 'pdf');
            $reporte = $classReport->PrintReport($tipo_reporte = 'pdf', $tipo_modulo = 'app', $modulo = 'CentralImpresionHospitalizacion', $reporte_name = 'solicitudesnoautorizadasPDF', $var, $impresora, $orientacion = 'P', $unidades = 'mm', $formato = 'letter', $html = 1);
        }

        //$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        //$reporte=$classReport->PrintReport('pos','app','CentroAutorizacion','solicitudesnoautorizadas',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);

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

        if ($_REQUEST['regreso'] == 'FormaDetalleSolicitud') {
            $this->$_REQUEST['regreso']();
        } else {
            $this->$_REQUEST['regreso']($_REQUEST['tipoid'], $_REQUEST['paciente']);
        }
        return true;
    }

    function AnularSolicitud() {
        if (empty($_REQUEST['observacion'])) {
            $this->frmError["Observacion"] = 1;
            $this->frmError["MensajeError"] = "Debe digitar la Justificaci?n";
            $this->FormaAnularSolicitud();
            return true;
        }

        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query = "UPDATE hc_os_solicitudes SET sw_estado=2 WHERE hc_os_solicitud_id=" . $_REQUEST['solicitud'] . "";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error UPDATE hc_os_solicitudes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $query = "INSERT INTO auditoria_anular_solicitudes
									VALUES(" . $_REQUEST['solicitud'] . "," . UserGetUID() . ",'now()','" . $_REQUEST['observacion'] . "')";
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error INSERT INTO auditoria_anular_solicitudes";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }

        $dbconn->CommitTrans();
        $this->frmError["MensajeError"] = "La Solicitud Fue Anulada";
        if (empty($_SESSION['CENTROAUTORIZACION']['TODOS'])) {
            $this->DetalleSolicitud();
        } else {
            $this->DetalleSolicituTodos();
        }
        return true;
    }

//---------------------ACTUALIZACION FECHAS----------------------------------------

    /**
     *
     */
    /* function  ActualizarFechas($plan,$orden)
      {
      list($dbconn) = GetDBconn();
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
      return false;
      }
      }

      return true;
      } */
//------------------FUNCIONES PARA LOS CAMPOS DE MOSTRAR BD--------------

    function PlantilaBD($plan) {
        list($dbconn) = GetDBconn();
        $sql = "SELECT plantilla_bd_id FROM plantillas_planes WHERE plan_id=$plan";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        if (!$result->EOF) {
            $var = $result->fields[0];
        }

        $result->Close();
        return $var;
    }

    function CamposMostrarBD($campo, $plantilla) {
        list($dbconn) = GetDBconn();
        $sql = "SELECT nombre_mostrar,sw_mostrar FROM plantillas_detalles
							WHERE descripcion_campo='$campo' AND plantilla_bd_id=$plantilla";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }

//------------------------------------------------------------------------------------
    
    function BuscarInfoPlan($planid, $tipo, $id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT plan_id,sw_tipo_plan, sw_afiliacion,protocolos, plan_descripcion 
                  FROM planes
                  WHERE estado='1' and plan_id=$planid
			and fecha_final >= now() and fecha_inicio <= now()";
        
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la informacion de plan";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $plan = $result->GetRowAssoc($ToUpper = false);
        $result->Close();


        if (($plan[sw_tipo_plan] == 0 AND $plan[sw_afiliacion] == 1) OR ($plan[sw_afiliacion] == 1)) {
            if (!IncludeFile("classes/BDAfiliados/BDAfiliados.class.php")) {
                $this->error = "Error";
                $this->mensajeDeError = "No se pudo incluir : classes/notas_enfermeria/revision_sistemas.class.php";
                return false;
            }
            if (!class_exists('BDAfiliados')) {
                $this->error = "Error";
                $this->mensajeDeError = "no existe BDAfiliados";
                return false;
            }

            $class = New BDAfiliados($tipo, $id, $planid);
            if ($class->GetDatosAfiliado() == false) {
                $this->error = $class->error;
                $this->mensajeDeError = $class->mensajeDeError;
                return false;
            }

            if (!empty($class->salida)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------

    function PorPlanes() {
        $query = "SELECT distinct a.empresa_id, a.nivel_autorizador_id, c.plan_id, b.razon_social as descripcion1, d.nombre_tercero, c.plan_descripcion
											FROM userpermisos_centro_autorizacion as a, empresas as b, planes as c, terceros as d
											WHERE a.usuario_id=" . UserGetUID . " and a.empresa_id=b.empresa_id and c.tipo_tercero_id=d.tipo_id_tercero and c.tercero_id=d.tercero_id
											and c.fecha_final >= now() and c.fecha_inicio <= now() and c.estado='1' and (a.plan_id=c.plan_id or a.sw_todos_planes=1)
											and c.empresa_id=a.empresa_id";
    }

    function InformacionAfiliado($tipo_id_paciente, $paciente_id, $plan_id) {
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $sql = "SELECT PL.plan_id,";
        $sql .= " 	    PL.rango, ";
        $sql .= " 	    TA.tipo_afiliado_id,";
        $sql .= " 	    TA.tipo_afiliado_nombre, ";
        $sql .= " 	    EP.eps_punto_atencion_id, ";
        $sql .= " 	    EP.eps_punto_atencion_nombre ";
        $sql .= "FROM   eps_afiliados AF ";
        $sql .= "LEFT JOIN eps_puntos_atencion EP ";
        $sql .= "ON (AF.eps_punto_atencion_id = EP.eps_punto_atencion_id), ";
        $sql .= "       planes_rangos PL, ";
        $sql .= "       tipos_afiliado TA ";
        $sql .= "WHERE  AF.afiliado_tipo_id = '" . $tipo_id_paciente . "' ";
        $sql .= "AND    AF.afiliado_id = '" . $paciente_id . "' ";
        $sql .= "AND    AF.plan_atencion = '" . $plan_id . "' ";
        //$sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";
        $sql .= "AND    AF.tipo_afiliado_atencion = PL.tipo_afiliado_id ";
        $sql .= "AND    AF.rango_afiliado_atencion = PL.rango ";
        $sql .= "AND    TA.tipo_afiliado_id = PL.tipo_afiliado_id ";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var = $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }

    function ObtenerObservacionSolicitud($hc_os_solicitud_id, $tabla) {
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM " . $tabla . " WHERE hc_os_solicitud_id = " . $hc_os_solicitud_id;


        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->Close();

        return $var;
    }

    function ObtenerTipoSolicitud($hc_os_solicitud_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT os_tipo_solicitud_id FROM hc_os_solicitudes WHERE hc_os_solicitud_id = " . $hc_os_solicitud_id;
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->Close();

        return $var;
    }

    function ObtenerHcOsId($numero_orden_id) {
        list($dbconn) = GetDBconn();
        $query = "SELECT hc_os_solicitud_id FROM os_maestro WHERE numero_orden_id = " . $numero_orden_id;
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var[] = $result->GetRowAssoc($ToUpper = false);
        $result->Close();

        return $var;
    }

    function BuscarPlanTem1($Plan, $TipoId, $PacienteId) {
        list($dbconn) = GetDBconn();
        $query = " SELECT a.tipo_afiliado_atencion, a.rango_afiliado_atencion, b.descripcion_estado
                   FROM eps_afiliados as a,
                        eps_afiliados_estados b
                   WHERE a.plan_atencion=" . $Plan . "
                       AND   a.afiliado_tipo_id='" . $TipoId . "'
                       AND   a.afiliado_id = '" . $PacienteId . "'
                       AND   a.estado_afiliado_id = b.estado_afiliado_id 
                       AND   a.estado_afiliado_id = 'AC'";
        $result = $dbconn->Execute($query);
        if (!$result->EOF) {

            while (!$result->EOF) {
                $var[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $var;
    }

    function BuscarPlanTem($Plan, $TipoId, $PacienteId) {
        if (empty($Plan))
            $Plan = 'null';

        $vars = Array();
        list($dbconn) = GetDBconn();
        $query = " SELECT a.tipo_afiliado_atencion, a.rango_afiliado_atencion, b.descripcion_estado
                       FROM eps_afiliados as a,
                            eps_afiliados_estados b
                       WHERE a.plan_atencion=" . $Plan . "
                           AND   a.afiliado_tipo_id='" . $TipoId . "'
                           AND   a.afiliado_id = '" . $PacienteId . "'
                           AND   a.estado_afiliado_id = b.estado_afiliado_id 
                           AND   a.estado_afiliado_id = 'AC'";



        $result = $dbconn->Execute($query);
        
        
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar SQL";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg() . "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        } else {
            while (!$result->EOF) {
                $vars[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
        }
        $dbconn->CommitTrans();
        return $vars;
    }

    function ValidarCargosLB_y_LP($cargos) {
        list($dbconn) = GetDBconn();
        $query = "SELECT c.grupo_tarifario_id
                  FROM grupos_tarifarios gt INNER JOIN cups c ON c.grupo_tarifario_id = gt.grupo_tarifario_id
                  WHERE c.cargo = '" . $cargos . "' AND (c.grupo_tarifario_id = 'LB' OR c.grupo_tarifario_id = 'LP')";

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

    function ComboUnidadFuncionalCargos($cargos) {
        list($dbconn) = GetDBconn();

        $x = $_SESSION['CENTROAUTORIZACION']['EMPRESA'];
        $query = "SELECT uf.empresa_id||'|'||uf.centro_utilidad||'|'||uf.unidad_funcional as codigo, uf.descripcion,
                        uf.ubicacion, uf.telefono
                  FROM unidades_funcionales uf INNER JOIN unidades_funcionales_cargos uc 
                        ON (uc.empresa_id = uf.empresa_id AND uc.centro_utilidad = uf.centro_utilidad AND uc.unidad_funcional = uf.unidad_funcional)
                  WHERE uc.cargo = '$cargos' AND uf.empresa_id = '$x'";


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

    function ComboUnidadFuncional() {
        $x = " empresa_id='" . $_SESSION['CENTROAUTORIZACION']['EMPRESA'] . "'";
        list($dbconn) = GetDBconn();

        $query = "SELECT empresa_id||'|'||centro_utilidad||'|'||unidad_funcional as codigo
                            ,descripcion, ubicacion, telefono
                  FROM unidades_funcionales 
                  WHERE " . $x;

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

    function ObtenerDiasFuncionales() {
        list($dbconn) = GetDBconn();

        $query = "SELECT valor FROM system_modulos_variables WHERE variable = 'dias_refrendar'";

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
    
    function TraerPlanD($hc_os_solicitud_id) {
        list($dbconn) = GetDBconn();
        /*
        $query = "SELECT dd.*
                  FROM os_maestro om INNER JOIN os_maestro_cargos cmc ON (cmc.numero_orden_id = om.numero_orden_id)
                        INNER JOIN tarifarios_detalle dd ON (dd.tarifario_id = cmc.tarifario_id AND dd.cargo = cmc.cargo)
                  WHERE om.numero_orden_id = ".$numero_orden_id;
        */
        $query = " SELECT st.subgrupo_tarifario_id, st.subgrupo_tarifario_descripcion, 
                          gt.grupo_tarifario_id, gt.grupo_tarifario_descripcion
                   FROM hc_os_solicitudes hc INNER JOIN cups c ON (c.cargo = hc.cargo)
                          INNER JOIN subgrupos_tarifarios st ON (st.grupo_tarifario_id = c.grupo_tarifario_id AND st.subgrupo_tarifario_id = c.subgrupo_tarifario_id)
                          INNER JOIN grupos_tarifarios gt ON (gt.grupo_tarifario_id = st.grupo_tarifario_id)
                   WHERE hc.hc_os_solicitud_id = ".$hc_os_solicitud_id;
                
                
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la informacion de plan";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $plan = $result->GetRowAssoc($ToUpper = false);
        $result->Close();

        return $plan;
    }
    
    function Traerhc_os_solicitud_id($orden_servicio_id) {
        list($dbconn) = GetDBconn();
        /*
        $query = "SELECT dd.*
                  FROM os_maestro om INNER JOIN os_maestro_cargos cmc ON (cmc.numero_orden_id = om.numero_orden_id)
                        INNER JOIN tarifarios_detalle dd ON (dd.tarifario_id = cmc.tarifario_id AND dd.cargo = cmc.cargo)
                  WHERE om.numero_orden_id = ".$numero_orden_id;
        */
        $query = " SELECT *
                   FROM os_maestro
                   WHERE orden_servicio_id = ".$orden_servicio_id;
                
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer la informacion de plan";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $plan = $result->GetRowAssoc($ToUpper = false);
        $result->Close();

        return $plan;
    }
	function actualizar_departamento($auto){
	
			$auto = $_SESSION['CENTROAUTORIZACION']['TODO']['Autorizacion'];
			//echo 'Autorizacion: '.$auto.'<br>';
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query = "(select a.hc_os_solicitud_id, b.departamento
                    from hc_os_autorizaciones as a, os_ordenes_servicios b
                    where (a.autorizacion_int=" . $auto . " OR
                    a.autorizacion_ext=" . $auto . ")
					and (b.autorizacion_int=" . $auto . " OR
                    b.autorizacion_ext=" . $auto . ")
                    )
                    union
                    (select a.hc_os_solicitud_id, b.departamento
                    from hc_os_autorizaciones as a, os_ordenes_servicios b
                    where (a.autorizacion_int=" . $auto . " OR
                    a.autorizacion_ext=" . $auto . ")
					and (b.autorizacion_int=" . $auto . " OR
                    b.autorizacion_ext=" . $auto . ")
                    )";
					
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error select hc_os_solicitud_id";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while (!$result->EOF) {
                $query = "UPDATE hc_os_solicitudes SET
                sw_departamento=1, departamento='".$result->fields[1]."'
                WHERE hc_os_solicitud_id=" . $result->fields[0] . "";
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error UPDATE  hc_os_solicitudes ";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
				if ($result->RecordCount() > 0) {
                    $result->MoveNext();
                }
		}	
			$result->Close();
            $dbconn->CommitTrans();
	
	}
}
//fin clase user
?>