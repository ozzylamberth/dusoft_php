<?php

/**
 * $Id: app_CentroAutorizacionQx_user.php,v 1.4 2005/09/26 18:24:28 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_CentroAutorizacionQx_user extends classModulo
{
		var $color;
		var $limit;
		var $conteo;

    function app_CentroAutorizacionQx_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }


    /**
    * menu principal
    */
    function main()
    {
            list($dbconn) = GetDBconn();
            unset($_SESSION['CentroAutorizacionQx']);
            unset($_SESSION['SEGURIDAD']['CentroAutorizacionQx']);
            $SystemId=UserGetUID();
           /* if(!empty($_SESSION['SEGURIDAD']['CentroAutorizacionQx']))
            {
                        $this->salida.= gui_theme_menu_acceso('CENTRO AUTORIZACION',$_SESSION['SEGURIDAD']['CentroAutorizacionQx']['arreglo'],$_SESSION['SEGURIDAD']['CentroAutorizacionQx']['centro'],$_SESSION['SEGURIDAD']['CentroAutorizacionQx']['url'],ModuloGetURL('system','Menu'));
                        return true;
            }*/
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;
            $query = "SELECT distinct a.*, c.plan_id, b.empresa_id, b.razon_social as descripcion1, d.nombre_tercero, c.plan_descripcion,
											case when CantidadSolicitudesPorPlan(c.plan_id)=0 then '' else ' [' || CantidadSolicitudesPorPlan(c.plan_id) || '] - ' end || d.nombre_tercero||' '|| c.plan_descripcion  as descripcion2
											FROM userpermisos_autorizacion_qx as a, empresas as b, planes as c, terceros as d
											WHERE a.usuario_id=$SystemId and a.empresa_id=b.empresa_id and c.tipo_tercero_id=d.tipo_id_tercero and c.tercero_id=d.tercero_id
											and c.fecha_final >= now() and c.fecha_inicio <= now() and c.estado='1' and (a.plan_id=c.plan_id or a.sw_todos_planes=1)
											and c.empresa_id=a.empresa_id";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $resulta=$dbconn->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
                    return false;
            }
            while ($data = $resulta->FetchRow()) {
                $centro[$data['descripcion1']][$data['descripcion2']]=$data;
                $seguridad[$data['empresa_id']][$data['plan_id']]=1;
            }
            $url[0]='app';
            $url[1]='CentroAutorizacionQx';
            $url[2]='user';
            $url[3]='TiposPlanes';
            $url[4]='Centro';
            $arreglo[0]='EMPRESA';
            $arreglo[1]='CENTRO AUTORIZACION PROCEDIMIENTOS QX';

            $_SESSION['SEGURIDAD']['CentroAutorizacionQx']['arreglo']=$arreglo;
            $_SESSION['SEGURIDAD']['CentroAutorizacionQx']['centro']=$centro;
            $_SESSION['SEGURIDAD']['CentroAutorizacionQx']['url']=$url;
            $_SESSION['SEGURIDAD']['CentroAutorizacionQx']['puntos']=$seguridad;
            $this->salida.= gui_theme_menu_acceso('CENTRO AUTORIZACION PROCEDIMIENTOS QX',$_SESSION['SEGURIDAD']['CentroAutorizacionQx']['arreglo'],$_SESSION['SEGURIDAD']['CentroAutorizacionQx']['centro'],$_SESSION['SEGURIDAD']['CentroAutorizacionQx']['url'],ModuloGetURL('system','Menu'));
         return true;
    }

   /**
    * Elige la accion segun el plan elegido
    * @access public
    * @return boolean
    */
    function TiposPlanes()
    {
            if(empty($_SESSION['CentroAutorizacionQx']['EMPRESA']))
            {
                   /*if(empty($_SESSION['SEGURIDAD']['CentroAutorizacionQx']['puntos'][$_REQUEST['Centro']['empresa_id']][$_REQUEST['Centro']['plan_id']]))
                    {
                            $this->error = "Error de Seguridad.";
                            $this->mensajeDeError = "Violación a la Seguridad.";
                            return false;
                    }*/
              echo "==>".      $_SESSION['CentroAutorizacionQx']['EMPRESA']=$_REQUEST['Centro']['empresa_id'];
                    $_SESSION['CentroAutorizacionQx']['PLAN']=$_REQUEST['Centro']['plan_id'];
                    $_SESSION['CentroAutorizacionQx']['NIVEL']=$_REQUEST['Centro']['nivel_autorizador_id'];
                    $_SESSION['CentroAutorizacionQx']['PLANDES']=$_REQUEST['Centro']['plan_descripcion'];
                    $_SESSION['CentroAutorizacionQx']['RESPONSABLE']=$_REQUEST['Centro']['nombre_tercero'];
            }

            $this->FormaMenus();
            return true;
    }

    /**
    *
    */
    function LlamarBuscar()
    {
            unset($_SESSION['SPY']);
            list($dbconn) = GetDBconn();
            $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=".UserGetUID()."";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
                    return false;
            }

            unset($_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE']);
            unset($_SESSION['CentroAutorizacionQx']['paciente_id']);
            unset($_SESSION['CentroAutorizacionQx']['tipo_id_paciente']);
            unset($_SESSION['CentroAutorizacionQx']['nombre_paciente']);

            $this->FormaMetodoBuscar($Busqueda,$arr,$f);
            return true;
    }



    /**
    *
    */
    function main2()
    {
            unset($_SESSION['CentroAutorizacionQx']);

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
            $query = "SELECT nivel_autorizador_id, empresa_id FROM userpermisos_autorizacion_qx
                      WHERE usuario_id=".UserGetUID()."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
                    return false;
            }

            $_SESSION['CentroAutorizacionQx']['NIVEL']=$result->fields[0];
            $_SESSION['CentroAutorizacionQx']['EMPRESA']=$result->fields[1];
            $_SESSION['CentroAutorizacionQx']['TODOS']=true;
            $this->FormaMenus();
            return true;
    }
    /**
    *
    */
    function BuscarSolicitud()
    {
            list($dbconn) = GetDBconn();
            $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=".UserGetUID()."";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
                    return false;
            }

            //unset($_SESSION['CentroAutorizacionQx']['TODO']);
            unset($_SESSION['CentroAutorizacionQx']['TODO']['paciente_id']);
            unset($_SESSION['CentroAutorizacionQx']['TODO']['tipo_id_paciente']);
            unset($_SESSION['CentroAutorizacionQx']['TODO']['nombre_paciente']);

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
            //if ($tipo != -1)
            //{   $filtroTipo ="and  a.os_tipo_solicitud_id='$tipo'";   }

            if(empty($_REQUEST['Of'])){ $_REQUEST['Of']=0; }

            if(empty($_REQUEST['paso']))
            {
                $query = "select tipo_id_paciente, paciente_id, nombres, usuario_id, apellido, completo, nombre
                          from
                            (
                                  (
                                    select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id, apellido, completo, nombre
                                    from (
                                            select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad,
                                            k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                                            k.primer_nombre||' '||k.segundo_nombre as nombre, k.primer_apellido||' '||k.segundo_apellido as apellido,
                                            k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as completo
                                            from hc_evoluciones as i, hc_os_solicitudes as a
                                            left join  userpermisos_autorizacion_qx as p
                                            on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id or p.sw_todos_planes='1'),
                                            ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id) ,
                                            pacientes as k, departamentos as l, servicios as m
                                            where a.os_tipo_solicitud_id='QX' and p.usuario_id=".UserGetUID()." and a.sw_estado=1 and a.evolucion_id is not null
                                            and a.evolucion_id=i.evolucion_id
                                            and i.ingreso=j.ingreso
                                            and j.departamento_actual=l.departamento
                                            $filtroServicio1
                                            and l.servicio=m.servicio
                                            $filtroTipoDocumento $filtroDocumento
                                            $filtroNombres
                                            $filtroSolicitud
                                            and j.tipo_id_paciente=k.tipo_id_paciente
                                            and j.paciente_id=k.paciente_id
                                            order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
                                          ) as a
                                  )
                                  UNION
                                  (
                                    select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id, apellido, completo, nombre
                                    from (  select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad,
                                            k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
																						k.primer_nombre||' '||k.segundo_nombre as nombre,
                                            k.primer_apellido||' '||k.segundo_apellido as apellido,
                                            k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as completo
                                            from hc_os_solicitudes as a
                                            left join  userpermisos_autorizacion_qx as p
                                            on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id or p.sw_todos_planes=1),
                                            hc_os_solicitudes_manuales as j  left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id),                                                             pacientes as k,servicios as m
                                            where a.os_tipo_solicitud_id='QX' and p.usuario_id=".UserGetUID()." and a.sw_estado='1'
                                            and a.evolucion_id is null
                                            $filtroServicio2
                                            and m.servicio=j.servicio
                                            $filtroTipoDocumento $filtroDocumento
                                            $filtroNombres
                                            $filtroSolicitud
                                            and j.tipo_id_paciente=k.tipo_id_paciente
                                            and j.paciente_id=k.paciente_id
                                            and a.hc_os_solicitud_id=j.hc_os_solicitud_id
                                            order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
                                          ) as a
                                  )
                        ) as a order by sw_prioridad desc, tipo_id_paciente, paciente_id";
                $result=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al buscar";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$this->fileError = __FILE__;
												$this->lineError = __LINE__;
                        return false;
                }
                if(!$result->EOF)
                {
                    $_SESSION['SPY2']=$result->RecordCount();
                }
                $result->Close();
            }

            $query = "select distinct * from (select tipo_id_paciente, paciente_id, nombres, usuario_id, apellido, completo, nombre
                      from
                        (
                              (
                                select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id, apellido, completo, nombre
                                from (
                                        select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad,
                                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                                        k.primer_nombre||' '||k.segundo_nombre as nombre, k.primer_apellido||' '||k.segundo_apellido as apellido,
                                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as completo
                                        from hc_evoluciones as i, hc_os_solicitudes as a
                                        left join  userpermisos_autorizacion_qx as p
                                        on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id or p.sw_todos_planes=1),
                                        ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id) ,
                                        pacientes as k, departamentos as l, servicios as m
                                        where a.os_tipo_solicitud_id='QX' AND p.usuario_id=".UserGetUID()." and a.sw_estado='1' and a.evolucion_id is not null
                                        and a.evolucion_id=i.evolucion_id
                                        and i.ingreso=j.ingreso
                                        and j.departamento_actual=l.departamento
                                        $filtroServicio1
                                        and l.servicio=m.servicio
                                        $filtroTipoDocumento $filtroDocumento
                                        $filtroNombres
                                        $filtroSolicitud
                                        and j.tipo_id_paciente=k.tipo_id_paciente
                                        and j.paciente_id=k.paciente_id
                                        order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
                                      ) as a
                              )
                              UNION
                              (
                                select distinct tipo_id_paciente, paciente_id, nombres, sw_prioridad, usuario_id, apellido, completo, nombre
                                from (    select a.evolucion_id, b.usuario_id, j.tipo_id_paciente, j.paciente_id, m.sw_prioridad,
                                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, k.primer_nombre||' '||k.segundo_nombre as nombre,
                                        k.primer_apellido||' '||k.segundo_apellido as apellido,
                                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as completo
                                        from hc_os_solicitudes as a left join  userpermisos_autorizacion_qx as p
                                        on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id or p.sw_todos_planes='1'),
                                        hc_os_solicitudes_manuales as j  left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id),                                                             pacientes as k,servicios as m
                                        where a.os_tipo_solicitud_id='QX' AND p.usuario_id=".UserGetUID()." and a.sw_estado='1'
                                        and a.evolucion_id is null
                                        $filtroServicio2
                                        and m.servicio=j.servicio
                                        $filtroTipoDocumento $filtroDocumento
                                        $filtroNombres
                                        $filtroSolicitud
                                        and a.hc_os_solicitud_id=j.hc_os_solicitud_id
                                        and j.tipo_id_paciente=k.tipo_id_paciente
                                        and j.paciente_id=k.paciente_id
                                        order by m.sw_prioridad desc, j.tipo_id_paciente, j.paciente_id
                                      ) as a
                              )
                    ) as a order by tipo_id_paciente, paciente_id
                    ) as a LIMIT ".$this->limit." OFFSET ".$_REQUEST['Of']."";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al buscar";
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
            $this->FormaMetodoBuscar($var);
            return true;
    }



  /**
  *
  * @access public
  * @return boolean
  */
  function Buscar()
  {
            list($dbconn) = GetDBconn();
            $query = "DELETE FROM hc_os_autorizaciones_proceso WHERE usuario_id=".UserGetUID()."";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error DELETE FROM hc_os_autorizaciones_proceso";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
                    return false;
            }

            unset($_SESSION['CentroAutorizacionQx']['DATOS']);
            unset($_SESSION['CentroAutorizacionQx']['paciente_id']);
            unset($_SESSION['CentroAutorizacionQx']['tipo_id_paciente']);

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
            //if ($tipo != -1)
            //{   $filtroTipo ="and  a.os_tipo_solicitud_id='$tipo'";   }

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
																									ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN'].") ,
																									pacientes as k, departamentos as l, servicios as m
																									where a.os_tipo_solicitud_id='QX' AND a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."
																									and a.sw_estado='1'
																									and a.evolucion_id is not null
																									and a.evolucion_id=i.evolucion_id
																									and i.ingreso=j.ingreso
																									and j.departamento_actual=l.departamento
																									and l.servicio=m.servicio
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
																									hc_os_solicitudes_manuales as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."),
																									pacientes as k,
																									servicios as m
																									where a.os_tipo_solicitud_id='QX' AND a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."
																									and a.sw_estado='1'
																									and a.hc_os_solicitud_id=j.hc_os_solicitud_id
																									and a.evolucion_id is null
																									and m.servicio=j.servicio
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
												$this->fileError = __FILE__;
												$this->lineError = __LINE__;
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
                                                            ingresos as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN'].") ,
                                                            pacientes as k, departamentos as l, servicios as m
                                                            where a.os_tipo_solicitud_id='QX' and a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."
                                                            and a.sw_estado='1'
                                                            and a.evolucion_id is not null
                                                            and a.evolucion_id=i.evolucion_id
                                                            and i.ingreso=j.ingreso
                                                            and j.departamento_actual=l.departamento
                                                            and l.servicio=m.servicio
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
                                                            hc_os_solicitudes_manuales as j left join hc_os_autorizaciones_proceso as b on(j.tipo_id_paciente=b.tipo_id_paciente and j.paciente_id=b.paciente_id and b.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."),
                                                            pacientes as k,
                                                            servicios as m
                                                            where a.os_tipo_solicitud_id='QX' and a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."
                                                            and a.sw_estado='1'
                                                            and a.hc_os_solicitud_id=j.hc_os_solicitud_id
                                                            and a.evolucion_id is null
                                                            and m.servicio=j.servicio
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
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
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
     * Llama la forma ConfirmarAccion (forma de mensaje de dos botones).
   * @ access public
     * @ return boolean
     */
    function ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,$arreglo,$c,$m,$me,$me2)
    {
            if(empty($Titulo))
            {
                $arreglo=$_REQUEST['arreglo'];
                $Cuenta=$_REQUEST['Cuenta'];
                $c=$_REQUEST['c'];
                $m=$_REQUEST['m'];
                $me=$_REQUEST['me'];
                $me2=$_REQUEST['me2'];
                $mensaje=$_REQUEST['mensaje'];
                $Titulo=$_REQUEST['titulo'];
                $boton1=$_REQUEST['boton1'];
                $boton2=$_REQUEST['boton2'];
            }

                $this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
                return true;
    }

    /**
    * Llama la forma del menu de facuracion
    * @access public
    * @return boolean
    */
    function DatosEncabezado()
    {
            list($dbconn) = GetDBconn();
            $query = "select b.razon_social, a.plan_descripcion from empresas as b, planes as a
											where b.empresa_id='".$_SESSION['CentroAutorizacionQx']['EMPRESA']."'
											and a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
                    return false;
            }
            $var=$resulta->GetRowAssoc($ToUpper = false);
            return $var;
    }


    /**
    *
    */
    function responsables()
    {
        list($dbconn) = GetDBconn();
        $query = "select empresa_id,sw_todos_planes from userpermisos_autorizacion_qx
                  where usuario_id=".UserGetUID()." and sw_todos_planes=1";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
            return false;
        }
        if(!$result->EOF)
        {
            while(!$result->EOF)
            {
                $vars[$result->fields[0]]=$result->fields[0];
                $result->MoveNext();
            }
            foreach($vars as $k =>$v)
            {
              $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                      FROM planes as a
                      WHERE a.fecha_final >= now() and a.estado=1
                      and a.fecha_inicio <= now()
                      and empresa_id='".$k."'
											order by a.plan_descripcion";
                  $results = $dbconn->Execute($query);
                  if(!$result->EOF)
                  {
                      $var[]=$results->GetRowAssoc($ToUpper = false);
                  }
            }
        }
        else
        {
               $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                      FROM planes as a, userpermisos_autorizacion_qx as b
                      WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
                      and b.usuario_id=".UserGetUID()."
                      and b.plan_id=a.plan_id
											order by a.plan_descripcion";
        }
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
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

   /**
    *
    */
    function DetalleSolicitud()
    {
            unset($_SESSION['CentroAutorizacionQx']['ARREGLO']);
            if(!empty($_REQUEST['plan']))
            {   $_SESSION['CentroAutorizacionQx']['PLAN']=$_REQUEST['plan'];   }
            if(empty($_SESSION['CentroAutorizacionQx']['paciente_id'])
             OR empty($_SESSION['CentroAutorizacionQx']['tipo_id_paciente']))
            {
                    $_SESSION['CentroAutorizacionQx']['paciente_id']=$_REQUEST['paciente'];
                    $_SESSION['CentroAutorizacionQx']['tipo_id_paciente']=$_REQUEST['tipoid'];
                    $_SESSION['CentroAutorizacionQx']['nombre_paciente']=$_REQUEST['nombre'];
            }
            list($dbconn) = GetDBconn();
            $query = "select a.* from(
                      (select i.ingreso, a.cantidad,a.hc_os_solicitud_id,    a.cargo as cargos,
                        p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente,
                        j.paciente_id,
                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                        a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
                        a.sw_estado,
                        l.servicio, p.descripcion,
                        m.descripcion as desserv, g.descripcion as desos,
                        i.fecha,
                        NULL as profesional,NULL as prestador,NULL as observaciones,
                        l.descripcion as despto, p.nivel_autorizador_id as nivel, z.descripcion, i.usuario_id
                       -- ,q.departamento, r.tipo_id_tercero
                        from hc_os_solicitudes as a, planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
												ingresos as j,
                        pacientes as k, departamentos as l, servicios as m,
                        cups as p, hc_os_solicitudes_datos_acto_qx as x
												left join hc_os_solicitudes_niveles_autorizacion as z on( x.nivel_autorizacion=z.nivel)
												--left join departamentos_cargos as q on(p.cargo=q.cargo)
                        --left join terceros_proveedores_cargos as r on(p.cargo=r.cargo)
                        where a.os_tipo_solicitud_id='QX' and a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']." and p.cargo=a.cargo  and a.plan_id=f.plan_id
                        and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null and a.evolucion_id=i.evolucion_id
                        and i.ingreso=j.ingreso  and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
                        and a.sw_estado='1'
                        and j.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                        and j.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."' and i.departamento=l.departamento and l.servicio=m.servicio
												and a.hc_os_solicitud_id=x.hc_os_solicitud_id
                        order by m.servicio, j.tipo_id_paciente, j.paciente_id, a.os_tipo_solicitud_id
                      )
                      union
                      (
                        select NULL, a.cantidad,a.hc_os_solicitud_id,a.cargo as cargos,
                        p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente,
                        b.paciente_id,k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                        a.plan_id,f.plan_descripcion,
                        a.os_tipo_solicitud_id,a.sw_estado,
                        b.servicio,p.descripcion,m.descripcion as desserv,g.descripcion as desos,
                        b.fecha,b.profesional,
                        b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel, z.descripcion, NULL
                        --,q.departamento, r.tipo_id_tercero
                        from hc_os_solicitudes as a,
                        planes as f, os_tipos_solicitudes as g, pacientes as k, servicios as m,
                        cups as p, hc_os_solicitudes_datos_acto_qx as x
												left join hc_os_solicitudes_niveles_autorizacion as z on( x.nivel_autorizacion=z.nivel),
												--left join departamentos_cargos as q on(p.cargo=q.cargo)
                        --left join terceros_proveedores_cargos as r on(p.cargo=r.cargo),
                        hc_os_solicitudes_manuales as b
                        where a.os_tipo_solicitud_id='QX' and a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']." and p.cargo=a.cargo
                        and a.plan_id=f.plan_id and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is null
                        and b.tipo_id_paciente=k.tipo_id_paciente and b.paciente_id=k.paciente_id
                        and b.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                        and a.sw_estado='1'
                        and b.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."' and b.servicio=m.servicio
                        and a.hc_os_solicitud_id=b.hc_os_solicitud_id
												and a.hc_os_solicitud_id=x.hc_os_solicitud_id
                        order by m.servicio, b.tipo_id_paciente, b.paciente_id, a.os_tipo_solicitud_id
                      )
                      ) as a order by a.plan_id, a.servicio";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
                return false;
            }

            while(!$result->EOF)
            {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
            $result->Close();
            //detalle de los otros planes
            /*$query = "(select i.ingreso, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, j.tipo_id_paciente, j.paciente_id,
                                    k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion,a.os_tipo_solicitud_id, a.sw_estado,n.cargo,n.tarifario_id, h.descripcion,l.servicio, m.descripcion as desserv, g.descripcion as desos, i.fecha,r.grupo_tarifario_id as grupoc, r.subgrupo_tarifario_id as subgrupoc,
                                    NULL as profesional,NULL as prestador,NULL as observaciones, l.descripcion as despto, p.nivel_autorizador_id as nivel
                                    from hc_os_solicitudes as a left join tarifarios_equivalencias as n on(n.cargo_base=a.cargo) left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id)
                                    left join plan_tarifario as r on (r.plan_id<>".$_SESSION['CentroAutorizacionQx']['PLAN']." and h.grupo_tarifario_id=r.grupo_tarifario_id and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id),
                                    planes as f, os_tipos_solicitudes as g, hc_evoluciones as i, ingresos as j, pacientes as k, departamentos as l, servicios as m, cups as p,
                                    userpermisos_autorizacion_qx as q
                                    where a.os_tipo_solicitud_id='QX' and q.usuario_id=".UserGetUID()." and (a.plan_id=q.plan_id or q.sw_todos_planes=1)
                                    and a.plan_id <>".$_SESSION['CentroAutorizacionQx']['PLAN']." and p.cargo=a.cargo and a.sw_estado='1' and a.plan_id=f.plan_id and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is not null
                                    and a.evolucion_id=i.evolucion_id and i.ingreso=j.ingreso and j.estado=1 and j.tipo_id_paciente=k.tipo_id_paciente and j.paciente_id=k.paciente_id
                                    and j.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                                    and j.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."' and i.departamento=l.departamento and l.servicio=m.servicio
                                    order by a.plan_id, m.servicio
                                    )
                                    UNION
                                    ( select NULL,a.hc_os_solicitud_id,a.cargo as cargos, p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente, b.paciente_id,
                                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres, a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,a.sw_estado, n.cargo,n.tarifario_id,h.descripcion, b.servicio,m.descripcion as desserv,g.descripcion as desos, b.fecha,r.grupo_tarifario_id as grupoc, r.subgrupo_tarifario_id as subgrupoc,
                                        b.profesional, b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel
                                        from hc_os_solicitudes as a left join tarifarios_equivalencias as n on(n.cargo_base=a.cargo) left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id) left join plan_tarifario as r on (r.plan_id<>2 and h.grupo_tarifario_id=r.grupo_tarifario_id and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id),
                                        planes as f, os_tipos_solicitudes as g, pacientes as k, servicios as m, cups as p, hc_os_solicitudes_manuales as b, userpermisos_autorizacion_qx as q
                                        where a.os_tipo_solicitud_id='QX' and q.usuario_id=".UserGetUID()." and (a.plan_id=q.plan_id  or q.sw_todos_planes=1)
                                        and a.plan_id <>".$_SESSION['CentroAutorizacionQx']['PLAN']."
                                        and p.cargo=a.cargo and a.sw_estado='1' and a.plan_id=f.plan_id and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id and a.evolucion_id is null
                                        and b.tipo_id_paciente=k.tipo_id_paciente and b.paciente_id=k.paciente_id
                                        and b.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                                        and b.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
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
            $query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                      case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
                      e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
                      g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
                      p.plan_descripcion,j.sw_estado
                      from os_ordenes_servicios as a
                      join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                      left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                      left join departamentos as l on(g.departamento=l.departamento)
                      left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                      left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
                      tipos_afiliado as b, servicios as c, pacientes as d, cups as f, autorizaciones as j, system_usuarios as k,
                      planes as p, hc_os_solicitudes as z
                      where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
                      and a.plan_id=p.plan_id
                      and z.os_tipo_solicitud_id<>'CIT'
                      and z.hc_os_solicitud_id=e.hc_os_solicitud_id
                      and a.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                      and a.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
                      and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id
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
                        where a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']." and
                        f.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']." and
                        k.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."' and
                        k.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
                        and  r.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."' and
                        r.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
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
                          where a.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']." and
                          f.plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']." and
                          b.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."' and
                          b.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."' and
                          k.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."' and
                          k.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."' and
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
            $result->Close();*/

            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE']=$vars;
            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE2']=$vars2;
            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE3']=$vars3;
            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE4']=$vars4;

            $query = "SELECT * FROM hc_os_autorizaciones_proceso
                                WHERE tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                                AND paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
                                AND plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."
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
                                            VALUES('".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."','".$_SESSION['CentroAutorizacionQx']['paciente_id']."',".UserGetUID().",'".$_SESSION['CentroAutorizacionQx']['PLAN']."','now()','1')";
                        $results=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$this->fileError = __FILE__;
														$this->lineError = __LINE__;
                            return false;
                        }
                        $results->Close();
            }
            $result->Close();
						if(empty($_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE']))
						{  $this->FormaMetodoBuscar();  }
						else
						{  $this->FormaDetalleSolicitud();  }
            return true;
    }
   /**
    *
    */
    function DetalleSolicituTodos()
    {
          unset($_SESSION['CentroAutorizacionQx']['ARREGLO']);
          if(empty($_SESSION['CentroAutorizacionQx']['paciente_id'])
            AND empty($_SESSION['CentroAutorizacionQx']['tipo_id_paciente']))
          {
              $_SESSION['CentroAutorizacionQx']['paciente_id']=$_REQUEST['paciente'];
              $_SESSION['CentroAutorizacionQx']['tipo_id_paciente']=$_REQUEST['tipoid'];
              $_SESSION['CentroAutorizacionQx']['nombre_paciente']=$_REQUEST['nombre'];
          }

          list($dbconn) = GetDBconn();
        	$query = "select a.* from(
                        (
                        select i.ingreso, a.cantidad,a.hc_os_solicitud_id, a.cargo as cargos,
                        p.descripcion as descar, p.nivel_autorizador_id as nivel, p.sw_pos, j.tipo_id_paciente,
                        j.paciente_id,
                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                        a.plan_id,f.plan_descripcion, a.os_tipo_solicitud_id,
                        a.sw_estado,
                        l.servicio, m.descripcion as desserv, g.descripcion as desos,
                        i.fecha,
                        NULL as profesional,NULL as prestador,NULL as observaciones,
                        l.descripcion as despto, p.nivel_autorizador_id as nivel,
                        NULL, z.descripcion, i.usuario_id
                        from  hc_os_solicitudes as a
                        join hc_evoluciones as i on(i.evolucion_id=a.evolucion_id)
                        join ingresos as j on(i.ingreso=j.ingreso and
                        j.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                        and j.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'),
                        planes as f, os_tipos_solicitudes as g,
                        pacientes as k, departamentos as l, servicios as m,
                        cups as p, hc_os_solicitudes_datos_acto_qx as x
												left join hc_os_solicitudes_niveles_autorizacion as z on( x.nivel_autorizacion=z.nivel)
                        where a.os_tipo_solicitud_id='QX' and
                        p.cargo=a.cargo and a.sw_estado='1'
                        and a.plan_id=f.plan_id
                        and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                        and a.evolucion_id is not null
                        and j.tipo_id_paciente=k.tipo_id_paciente
                        and j.paciente_id=k.paciente_id
                        and i.departamento=l.departamento and l.servicio=m.servicio
												and a.hc_os_solicitud_id=x.hc_os_solicitud_id
                        order by a.plan_id, m.servicio, j.tipo_id_paciente, j.paciente_id, a.os_tipo_solicitud_id
                      )
                      union
                      (
                        select NULL, a.cantidad,a.hc_os_solicitud_id,a.cargo as cargos,
                        p.descripcion as descar, p.nivel_autorizador_id as nivel, p.sw_pos, b.tipo_id_paciente,
                        b.paciente_id,k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                        a.plan_id,f.plan_descripcion,
                        a.os_tipo_solicitud_id,a.sw_estado,
                        b.servicio,
                        m.descripcion as desserv,g.descripcion as desos,
                        b.fecha,NULL,
                        b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel,
                        b.profesional, z.descripcion, NULL
                        from  hc_os_solicitudes as a,
                        planes as f, os_tipos_solicitudes as g,
                        pacientes as k, servicios as m,
                        cups as p, hc_os_solicitudes_manuales as b,
												hc_os_solicitudes_datos_acto_qx as x
												left join hc_os_solicitudes_niveles_autorizacion as z on( x.nivel_autorizacion=z.nivel)
                        where a.os_tipo_solicitud_id='QX' and
                        p.cargo=a.cargo and a.sw_estado='1'
                        and a.plan_id=f.plan_id
                        and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                        and a.evolucion_id is null
                        and b.servicio=m.servicio
                        and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                        and b.tipo_id_paciente=k.tipo_id_paciente
                        and b.paciente_id=k.paciente_id
                        and b.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                        and b.paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
												and a.hc_os_solicitud_id=x.hc_os_solicitud_id
                        order by a.plan_id, m.servicio, b.tipo_id_paciente, b.paciente_id, a.os_tipo_solicitud_id
                      )
                    )as a   order by a.plan_id, a.servicio";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
                return false;
            }
      if(!$result->EOF)
      {
          if($result->RecordCount()>1)
          {
              while(!$result->EOF)
              {
                      $vars[]=$result->GetRowAssoc($ToUpper = false);
                      $result->MoveNext();
              }
          }
          else
          {
                      $vars[]=$result->GetRowAssoc($ToUpper = false);
          }
      }

      //autorizaciones
      /*$query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
            case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
            e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
            g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
            p.plan_descripcion,j.sw_estado
            from os_ordenes_servicios as a
            join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
            left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
            left join departamentos as l on(g.departamento=l.departamento)
            left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
            left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
            tipos_afiliado as b, servicios as c, pacientes as d, cups as f, autorizaciones as j, system_usuarios as k,
            planes as p, hc_os_solicitudes as z
            where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
            and a.plan_id=p.plan_id
            and z.os_tipo_solicitud_id<>'CIT'
            and z.hc_os_solicitud_id=e.hc_os_solicitud_id
            and a.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['TODO']['tipo_id_paciente']."'
            and a.paciente_id='".$_SESSION['CentroAutorizacionQx']['TODO']['paciente_id']."'
            and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id
            and e.cargo_cups=f.cargo
            and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
            and j.usuario_id=k.usuario_id
            and e.sw_estado in('1','2','3','7')
            order by a.orden_servicio_id desc";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Tabal autorizaiones1";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            if(!$result->EOF)
            {
                if($result->RecordCount()>1)
                {
                    while(!$result->EOF)
                    {
                            $vars3[]=$result->GetRowAssoc($ToUpper = false);
                            $result->MoveNext();
                    }
                }
                else
                {
                            $vars3[]=$result->GetRowAssoc($ToUpper = false);
                }
            }
                $result->Close();
            //no autorizaciones
              $query = " (
                        select h.observaciones, a.hc_os_solicitud_id, a.cargo as cargos, p.descripcion as descar,
                        p.nivel_autorizador_id, p.sw_pos, k.tipo_id_paciente, k.paciente_id,
                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                        a.plan_id,f.plan_descripcion,
                        a.os_tipo_solicitud_id, a.sw_estado, l.servicio, p.descripcion,
                        m.descripcion as desserv, g.descripcion as desos, i.fecha,
                        l.descripcion as despto, q.nombre_tercero, a.cantidad,NULL as profesional,a.evolucion_id
                        from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
                        planes as f, os_tipos_solicitudes as g, hc_evoluciones as i,
                        pacientes as k, departamentos as l, servicios as m, cups as p, terceros as q,
                        ingresos as r
                        where k.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['TODO']['tipo_id_paciente']."' and
                        k.paciente_id='".$_SESSION['CentroAutorizacionQx']['TODO']['paciente_id']."'
                        and r.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['TODO']['tipo_id_paciente']."' and
                        r.paciente_id='".$_SESSION['CentroAutorizacionQx']['TODO']['paciente_id']."'
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
                        k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                        a.plan_id,f.plan_descripcion,
                        a.os_tipo_solicitud_id, a.sw_estado, b.servicio, p.descripcion,
                        m.descripcion as desserv, g.descripcion as desos, b.fecha,
                        NULL as despto, q.nombre_tercero, a.cantidad, b.profesional,NULL
                        from hc_os_solicitudes as a, hc_os_autorizaciones as e, autorizaciones as h,
                        planes as f, os_tipos_solicitudes as g,
                        pacientes as k, servicios as m, cups as p, terceros as q,
                        hc_os_solicitudes_manuales as b
                        where   b.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['TODO']['tipo_id_paciente']."' and
                        b.paciente_id='".$_SESSION['CentroAutorizacionQx']['TODO']['paciente_id']."' and
                        k.tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['TODO']['tipo_id_paciente']."' and
                        k.paciente_id='".$_SESSION['CentroAutorizacionQx']['TODO']['paciente_id']."' and
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
             $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Tabal autorizaiones2";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            while(!$resulta->EOF)
            {
                    $vars4[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
            }
            $resulta->Close();*/

            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE']=$vars;
            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE3']=$vars3;
            $_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE4']=$vars4;

            $query = "SELECT * FROM hc_os_autorizaciones_proceso
                                WHERE tipo_id_paciente='".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."'
                                AND paciente_id='".$_SESSION['CentroAutorizacionQx']['paciente_id']."'
                                AND plan_id=".$_SESSION['CentroAutorizacionQx']['PLAN']."
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
                                            VALUES('".$_SESSION['CentroAutorizacionQx']['tipo_id_paciente']."','".$_SESSION['CentroAutorizacionQx']['paciente_id']."',".UserGetUID().",'".$_SESSION['CentroAutorizacionQx']['PLAN']."','now()','1')";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
														$this->fileError = __FILE__;
														$this->lineError = __LINE__;
                            return false;
                        }
            }
            $this->FormaDetalleSolicitud();
            return true;
    }

    /**
    *
    */
    function Planes()
    {
        list($dbconn) = GetDBconn();
         $query = "select empresa_id,sw_todos_planes from userpermisos_autorizacion_qx
                  where usuario_id=".UserGetUID()." and sw_todos_planes='1'";
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
                $vars[$result->fields[0]]=$result->fields[0];
                $result->MoveNext();
            }
            foreach($vars as $k =>$v)
            {
                  $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                      FROM planes as a
                      WHERE a.fecha_final >= now() and a.estado=1
                      and a.fecha_inicio <= now() and empresa_id='".$k."'";
                  $results = $dbconn->Execute($query);
                  if(!$results->EOF)
                  {
                     while(!$results->EOF)
                     {
                          $var[]=$results->GetRowAssoc($ToUpper = false);
                          $results->MoveNext();
                      }
                  }

            }
        }
        else
        {
              $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                      FROM planes as a, userpermisos_autorizacion_qx as b
                      WHERE a.fecha_final >= now() and a.estado='1' and a.fecha_inicio <= now()
                      and b.usuario_id=".UserGetUID()."
                      and b.plan_id=a.plan_id";
              $result = $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
                  return false;
              }

              while (!$result->EOF) {
                      $var[]=$result->GetRowAssoc($ToUpper = false);
                      $result->MoveNext();
              }
        }

        $result->Close();
        return $var;
    }

    function ClasificarPlan($plan)
    {
          list($dbconn) = GetDBconn();
          $query = "SELECT sw_tipo_plan, sw_afiliacion, protocolos, sw_autoriza_sin_bd
                    FROM planes  WHERE plan_id='$plan'";
          $results = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->fileError = __FILE__;
							$this->lineError = __LINE__;
              return false;
          }
          $var=$results->GetRowAssoc($ToUpper = false);
          $results->Close();
          return $var;
    }

    function DatosBD($TipoId,$PacienteId,$Plan)
    {
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
          {    return true;  }

          return false;
    }

    /**
    *
    */
    function BuscarSwHc()
    {
        list($dbconn) = GetDBconn();
        $query = "select sw_hc from autorizaciones_niveles_autorizador
                  where nivel_autorizador_id='".$_SESSION['CentroAutorizacionQx']['NIVEL']."'";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }

    /**
    *
    */
    function CantidadMeses($plan)
    {
        list($dbconn) = GetDBconn();
        $sql="select meses_consulta_base_datos from planes where plan_id=$plan;";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
          $dbconn->RollbackTrans();
          return false;
        }
        $result->Close();
        return $result->fields[0];
    }

    /**
    *
    */
    function BuscarEvolucion()
    {    $var='';
        list($dbconn) = GetDBconn();
        $query = "select b.evolucion_id from hc_evoluciones as b
                  where b.ingreso='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']."'
                  and b.fecha_cierre=(select max(fecha_cierre)
                  from hc_evoluciones  where ingreso='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']."')";
        $result=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
                return false;
        }
        if(!$result->EOF)
        {  $var=$result->fields[0];  }

        return $var;
    }
   function ValidarEquivalencias($cargo)
    {
          list($dbconn) = GetDBconn();
          $query = "select count(a.cargo)
                  from tarifarios_equivalencias as a
                  left join tarifarios_detalle as h
                  on (a.cargo_base='$cargo' and h.cargo=a.cargo and h.tarifario_id=a.tarifario_id)
                  where a.cargo_base='$cargo'";
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabal autorizaiones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
                  return false;
          }
          if(!$result->EOF)
          {   $var=$result->fields[0];  }

          return $var;
    }

    /**
    *
    */
    function ValidarContrato($cargo,$plan)
    {
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
          $result=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
                  $this->error = "Error al Guardar en la Tabal autorizaiones";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									$this->fileError = __FILE__;
									$this->lineError = __LINE__;
                  return false;
          }

          if(!$result->EOF)
          {  $var=$result->RecordCount();  }

          return $var;
    }


    /**
    *
    */
    function ValidarContratoEqui($tarifario,$cargo,$plan)
    {
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
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Tabal autorizaiones";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$this->fileError = __FILE__;
								$this->lineError = __LINE__;
								return false;
				}

				if(!$result->EOF)
				{   $var=$result->RecordCount();  }

				return $var;
    }
    /**
    *
    */
    function ComboDepartamento($Cargo)
    {
                if(!empty($_SESSION['CentroAutorizacionQx']['EMPRESA']))
                {  $x=" and b.empresa_id='".$_SESSION['CentroAutorizacionQx']['EMPRESA']."'";  }
                else
                {  $x=" and b.empresa_id='".$_SESSION['CentroAutorizacionQx']['TODO']['EMPRESA']."'";  }

                list($dbconn) = GetDBconn();
                $query = "select a.departamento, a.cargo, b.descripcion
                          from departamentos_cargos as a, departamentos as b
                          where a.cargo='$Cargo'
                          and b.departamento=a.departamento
                          $x";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$this->fileError = __FILE__;
												$this->lineError = __LINE__;
                        return false;
                }
                while(!$resulta->EOF)
                {
                        $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                }
                $resulta->Close();
                return $vars;
    }

    /**
    *
    */
    function ComboProveedor($Cargo)
    {
              if(!empty($_SESSION['CentroAutorizacionQx']['EMPRESA']))
              {  $x=" and a.empresa_id='".$_SESSION['CentroAutorizacionQx']['EMPRESA']."'";  }
              else
              {  $x=" and a.empresa_id='".$_SESSION['CentroAutorizacionQx']['TODO']['EMPRESA']."'";  }
              list($dbconn) = GetDBconn();
              $query = "select a.tipo_id_tercero, a.tercero_id, a.cargo,  c.plan_proveedor_id, c.empresa_id,
                        c.plan_descripcion
                        from terceros_proveedores_cargos as a, planes_proveedores as c
                        where a.cargo='$Cargo'
                        $x
                        and c.tipo_id_tercero=a.tipo_id_tercero and c.tercero_id=a.tercero_id ";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												$this->fileError = __FILE__;
												$this->lineError = __LINE__;
                        return false;
                }
                while(!$resulta->EOF)
                {
                        $vars[]=$resulta->GetRowAssoc($ToUpper = false);
                        $resulta->MoveNext();
                }
                $resulta->Close();
                return $vars;
    }

		function AnularSolicitud()
		{
				if(empty($_REQUEST['observacion']))
				{
						$this->frmError["Observacion"]=1;
						$this->frmError["MensajeError"]="Debe digitar la Justificación";
						$this->FormaAnularSolicitud();
						return true;
				}

        list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$query = "UPDATE hc_os_solicitudes SET sw_estado=2 WHERE hc_os_solicitud_id=".$_REQUEST['solicitud']."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error UPDATE hc_os_solicitudes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
						$dbconn->RollbackTrans();
						return false;
				}

				$query = "INSERT INTO auditoria_anular_solicitudes
									VALUES(".$_REQUEST['solicitud'].",".UserGetUID().",'now()','".$_REQUEST['observacion']."')";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error INSERT INTO auditoria_anular_solicitudes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
						$dbconn->RollbackTrans();
						return false;
				}

				$dbconn->CommitTrans();
				$this->frmError["MensajeError"]="La Solicitud Fue Anulada";
				if(empty($_SESSION['CentroAutorizacionQx']['TODOS']))
				{ $this->DetalleSolicitud();  }
				else
				{ $this->DetalleSolicituTodos();  }
				return true;
		}

		function BuscarUsuario($usuario)
		{
				list($dbconn) = GetDBconn();
				$query = "SELECT nombre FROM system_usuarios WHERE usuario_id=$usuario";
				$result=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error UPDATE hc_os_solicitudes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$this->fileError = __FILE__;
						$this->lineError = __LINE__;
						return false;
				}

				$var=$result->fields[0];
				$result->Close();
				return $var;
		}

//----------------------------------AUTORIZACIONES-----------------------------
    /**
    *
    */
    function PedirAutorizacion()
    {
				//valida si eligieron algun cargo
				$f=0;
				if(empty($_REQUEST['Auto']))
				{
						$this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir alguna Solicitud para Autorizar.";
						$this->FormaDetalleSolicitud();
						return true;
				}
				unset($_SESSION['AUTORIZACIONES']);

				$arr=explode(',',$_REQUEST['Auto']);
				//4 solicitu_id, 0 cargo, 1 tarifario, 3 servicio, 2 descr 5 cups
				//6nombre 7 plan 8 usuario(hc) 9 profecional(manual)

				if(!empty($arr[8]))
				{		//es desde la hc
						$_SESSION['AUTORIZACIONES']['AUTORIZAR']['profesional']=$this->BuscarUsuario($arr[8]);
				}
				else
				{		//es manual
						$_SESSION['AUTORIZACIONES']['AUTORIZAR']['profesional']=$arr[9];
				}

				//$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']=$_REQUEST['ingreso'];
				//$_SESSION['CentroAutorizacionQx']['SERVICIO']=$arr[3];

				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['nombre_paciente']=$arr[6];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']=$arr[4];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['CentroAutorizacionQx']['paciente_id'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['CentroAutorizacionQx']['tipo_id_paciente'];
				//$_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CentroAutorizacionQx';
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$arr[7];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['empresa']=$_SESSION['CentroAutorizacionQx']['EMPRESA'];
				$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
				$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
				$_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='CentroAutorizacionQx';
				$_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
				$_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

				$this->ReturnMetodoExterno('app','AutorizacionQx','user','AutorizarSolicitud');
				return true;
    }

		function RetornoAutorizacion()
		{
				if($_SESSION['AUTORIZACIONES']['VALIDACION']==3)
				{
						$this->frmError["MensajeError"]="SE CANCELO EL PROCESO DE AUTORIZACION.";
						$this->FormaDetalleSolicitud();
						return true;
				}

		}


//--------------------------------FIN AUTORIZACIONES---------------------------

//-----------------------------------------------------------------------------
}//fin clase user

?>

