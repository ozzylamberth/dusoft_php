<?php

/**
 * $Id: app_CentroAutorizacionSolicitud_user.php,v 1.21 2007/05/29 22:21:47 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo logico de las autorizaciones.
 */

/**
*Contiene los metodos para realizar las autorizaciones.
*/

class app_CentroAutorizacionSolicitud_user extends classModulo
{

    var $limit;
    var $conteo;

    function app_CentroAutorizacionSolicitud_user()
    {
            $this->limit=GetLimitBrowser();
            return true;
    }

    /**
    *La funcion main es la principal y donde se llama FormaBuscar de la clase
    * que muestra la forma para buscar al paciente
    */
    function main()
    {

        }

        /**
        *
        */
        function Menu()
        {
                unset($_SESSION['SOLICITUD']);
                unset($_SESSION['ARREGLO']['DATOS']);
                unset($_SESSION['ARREGLO']['MALLA']);
                $this->FormaBuscar();
                return true;
        }


        /**
        *
        */
        function responsables()
        {
                list($dbconn) = GetDBconn();
                $query = "select empresa_id,sw_todos_planes from userpermisos_centro_autorizacion
                                    where usuario_id=".UserGetUID()." and sw_todos_planes=1";
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
                                            and a.fecha_inicio <= now()
                                            and empresa_id='".$k."'
                                    ORDER BY 2 ";
                                    $results = $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error al obtener los datos del plan";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                                    }                                    
                                    if(!$result->EOF)
                                    {
                                            $var[]=$results->GetRowAssoc($ToUpper = false);
                                    }
                        }
                        //echo    $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                        //                    FROM planes as a
                        //                    WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()";
                }
                else
                {
                             $query="SELECT a.plan_id,a.plan_descripcion,a.tercero_id,a.tipo_tercero_id
                                            FROM planes as a, userpermisos_centro_autorizacion as b
                                            WHERE a.fecha_final >= now() and a.estado=1 and a.fecha_inicio <= now()
                                            and b.usuario_id=".UserGetUID()."
                                            and b.plan_id=a.plan_id
                                      ORDER BY 2 ";
                }
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
        function BuscarPaciente()
        {
                if(!$_REQUEST['Tipo'] || !$_REQUEST['Documento'] || $_REQUEST['plan']==-1){
                                if(!!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
                                if(!$_REQUEST['Tipo']){ $this->frmError["Tipo"]=1; }
                                if($Plan==-1){ $this->frmError["plan"]=1; }
                                $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                $this->FormaBuscar();
                                return true;
                }

                $_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$_REQUEST['Documento'];
                $_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$_REQUEST['Tipo'];
                $_SESSION['PACIENTES']['PACIENTE']['plan_id']=$_REQUEST['plan'];
                $_SESSION['PACIENTES']['RETORNO']['argumentos']=array();
                $_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
                $_SESSION['PACIENTES']['RETORNO']['modulo']='CentroAutorizacionSolicitud';
                $_SESSION['PACIENTES']['RETORNO']['tipo']='user';
                $_SESSION['PACIENTES']['RETORNO']['metodo']='RetornoPaciente';

                $this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
                return true;
        }

        /**
        *
        */
        function RetornoPaciente()
        {
                    unset($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
                    $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']=$_SESSION['PACIENTES']['PACIENTE']['ARREGLO'];
                    //si se cancelo en proceso de tomar datos del paciente
                    if(empty($_SESSION['PACIENTES']['RETORNO']['PASO']))
                    {
                            unset($_SESSION['PACIENTES']);
                            $this->FormaBuscar();
                            return true;
                    }
                    else
                    {
                                $_SESSION['SOLICITUD']['PACIENTE']['paciente_id']=$_SESSION['PACIENTES']['PACIENTE']['paciente_id'];
                                $_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']=$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente'];
                                $_SESSION['SOLICITUD']['PACIENTE']['plan_id']=$_SESSION['PACIENTES']['PACIENTE']['plan_id'];
                                unset($_SESSION['PACIENTES']);
                                list($dbconn) = GetDBconn();
                                
                                $query = "SELECT plan_descripcion,sw_tipo_plan, sw_afiliados     FROM planes
                                WHERE plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
                                $result=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al Guardar en la Base de Datos";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                                }
                                 $_SESSION['SOLICITUD']['PACIENTE']['plan_descripcion']=$result->fields[0];

                                if($result->fields[1]==2
                                    OR $result->fields[1]==1)
                                {
                                            $dat=$this->DatosPlanUnico($_SESSION['SOLICITUD']['PACIENTE']['plan_id']);
                                            $_SESSION['SOLICITUD']['PACIENTE']['SEMANAS']=0;
                                            $_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']=$dat[tipo_afiliado_id];
                                            $_SESSION['SOLICITUD']['PACIENTE']['RANGO']=$dat[rango];

                                            $this->FormaDatosSolicitud();
                                            return true;
                                }
                                else
                                {
                                            if($result->fields[2]==1)
											{
												$PacienteId=$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'];
												$TipoId=$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'];
												$Plan=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
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
													$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']=$result->fields[0];
													$_SESSION['SOLICITUD']['PACIENTE']['RANGO'] = $result->fields[1];
													$_SESSION['SOLICITUD']['PACIENTE']['ESTADO_AFILIADO'] = $result->fields[2];
													$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'] = 0;
													
												}
												$result->Close();
											}
											$this->FormaAfiliado();
                                            return true;
                                }
                    }
        }

				function DatosPlanUnico($plan)
				{
                list($dbconn) = GetDBconn();
                $query = "SELECT tipo_afiliado_id, rango
													FROM planes_rangos 
													WHERE plan_id='".$plan."'";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
								$vars=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->Close();
                return $vars;				
				}
  /**
  * Busca los diferentes tipos de afiliados
    * @access public
    * @return array
    */
        function Tipo_Afiliado($plan)
        {
                list($dbconn) = GetDBconn();
                $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                                    FROM tipos_afiliado as a, planes_rangos as b
                                    WHERE b.plan_id='".$plan."'
                                    and b.tipo_afiliado_id=a.tipo_afiliado_id";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
    function ValidarContratoEqui($tarifario,$cargo,$plan)
    {
          list($dbconn) = GetDBconn();
          $query = "(     select r.plan_id
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
                  return false;
          }

          if(!$result->EOF)
          {   $var=$result->RecordCount();  }

          return $var;
    }

    /**
    * Busca los niveles del plan del responsable del paciente
    * @access public
    * @return array
    * @param string plan_id
    */
     function Niveles($plan)
     {
                list($dbconn) = GetDBconn();
                 $query="SELECT DISTINCT rango
                                FROM planes_rangos
                                WHERE plan_id='".$plan."'";
                $result=$dbconn->Execute($query);
                while(!$result->EOF){
                    $niveles[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                }
            return $niveles;
     }


    /**
    *
    */
    function GuardarAfiliado()
    {
                        if(($_REQUEST['Semanas']=='') || $_REQUEST['TipoAfiliado']==-1 || $_REQUEST['Nivel']==-1){
                                        if($_REQUEST['Semanas']==''){ $this->frmError["Semanas"]=1; }
                                        if($_REQUEST['TipoAfiliado']==-1){ $this->frmError["TipoAfiliado"]=1; }
                                        if($_REQUEST['Nivel']==-1){ $this->frmError["Nivel"]=1; }
                                        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                                        $this->FormaAfiliado($_REQUEST['TipoAfiliado'],$_REQUEST['Nivel'],$_REQUEST['Semanas']);
                                        return true;
                        }

                        $_SESSION['AUTORIZACIONES']['AFILIADO']=$_REQUEST['TipoAfiliado'];
                        $_SESSION['AUTORIZACIONES']['RANGO']=$_REQUEST['Nivel'];
                        $_SESSION['AUTORIZACIONES']['SEMANAS']=$_REQUEST['Semanas'];

                        if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
                        {
                                if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado']!=$_REQUEST['TipoAfiliado']
                                  AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel']!=$_REQUEST['Nivel']
                                    AND $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas']!=$_REQUEST['Semanas'])
                                {
                                        if(empty($_REQUEST['Observacion']))
                                        {
                                                $this->frmError["Observacion"]=1;
                                                $this->frmError["MensajeError"]="Debe digitar la justificacion del cambio.";
                                                $this->FormaDatosAfiliado($_REQUEST['TipoAfiliado'],$_REQUEST['Nivel'],$_REQUEST['Semanas']);
                                                return true;
                                        }

                                        list($dbconn) = GetDBconn();
                                        $query = "INSERT INTO auditoria_cambio_datos_bdafiliados
                                                            VALUES('".$_SESSION['AUTORIZACIONES']['AFILIADO']."','".$_SESSION['AUTORIZACIONES']['RANGO']."',
                                                            ".$_SESSION['AUTORIZACIONES']['SEMANAS'].",
                                                            '".$_REQUEST['TipoAfiliado']."','".$_REQUEST['Nivel']."',
                                                            ".$_REQUEST['Semanas'].",'".$_REQUEST['Observacion']."',".UserGetUID().",'now()',
                                                            ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'].")";
                                        $result=$dbconn->Execute($query);
                                        if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error al eliminar en la Base de Datos";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                                        }
                                }
                        }

                        $_SESSION['SOLICITUD']['PACIENTE']['SEMANAS']=$_SESSION['AUTORIZACIONES']['SEMANAS'];
                        $_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']=$_SESSION['AUTORIZACIONES']['AFILIADO'];
                        $_SESSION['SOLICITUD']['PACIENTE']['RANGO']=$_SESSION['AUTORIZACIONES']['RANGO'];
                        unset($_SESSION['AUTORIZACIONES']);

                        $this->FormaDatosSolicitud();
                        return true;
    }

        /**
        *
        */
        function Malla()
        {
          
                IncludeLib("malla_validadora");
                $cargos=MallaValidadoraSolicitudesManuales($_SESSION['SOLICITUD']['PACIENTE']['plan_id'],$_SESSION['SOLICITUD']['DATOS']['SERVICIO'],$_SESSION['ARREGLO']['MALLA']);
        				list($dbconn) = GetDBconn();
                
								if(!empty($cargos))
                { 
                    
                        foreach($cargos as $k=>$v)
                        { 			
																foreach($v as $ke=>$va){
																	$Solicitud=$va[hc_os_solicitud_id];
																	unset($_SESSION['ARREGLO']['MALLA'][$va[hc_os_solicitud_id]]);    
																}
																//evento soat se inserto para verificar si el paciente viene por algun evento		
																$query="(
																SELECT c.evento as evento_soat
																FROM hc_os_solicitudes a, hc_evoluciones b, ingresos_soat c
																WHERE a.hc_os_solicitud_id = ".$Solicitud."
																	AND a.evolucion_id IS NOT NULL
																	AND a.evolucion_id = b.evolucion_id
																	AND b.ingreso = c.ingreso
																)	
																UNION 
																(
																SELECT b.evento_soat 
																FROM hc_os_solicitudes a, hc_os_solicitudes_manuales b
																WHERE a.hc_os_solicitud_id =  ".$Solicitud."
																	AND a.evolucion_id IS NULL
																	AND a.hc_os_solicitud_id = b.hc_os_solicitud_id
																)";
																
																$result=$dbconn->Execute($query);		
																			
																if ($dbconn->ErrorNo() != 0) {
																				$this->error = "SQL ERROR";
																				$this->mensajeDeError =  $dbconn->ErrorMsg();
																				return false;
																}	
																
																if(!$result->EOF)
																{
																	list($evento_soat)= $result->FetchRow();
																}
																else
																{
																	$evento_soat = 'NULL';
																}
																$result->Close();
                                MallaValidadoraGenerarOS($v,
																				$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'],
																				$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'],
																				$_SESSION['SOLICITUD']['PACIENTE']['plan_id'],
																				$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO'],
																				$_SESSION['SOLICITUD']['PACIENTE']['RANGO'],
																				$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'],
																				$_SESSION['SOLICITUD']['DATOS']['SERVICIO'],
																				date('Y-m-d h:m:s'),
                                        '',
                                        '',
                                        $evento_soat);
                                foreach($v as $ke=>$va)
                                {  unset($_SESSION['ARREGLO']['MALLA'][$va[hc_os_solicitud_id]]);    }
                        }
                }

                //ORDENES GENERADAS
                if(!empty($cargos))
                {
                        $query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                                            case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' else 'PARA ATENCION' end as estado,e.sw_estado,
                                            e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
                                            g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
                                            a.plan_id, z.plan_descripcion, v.fecha as fechamanu, v.profesional
                                            from os_ordenes_servicios as a left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                                            left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                                            left join departamentos as l on(g.departamento=l.departamento)
                                            left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                                            left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                                            left join hc_os_solicitudes as x on(e.hc_os_solicitud_id=x.hc_os_solicitud_id)
                                            left join userpermisos_centro_autorizacion as p 
                                            on(p.usuario_id=".UserGetUID()." and a.plan_id=p.plan_id or p.sw_todos_planes=1 and x.sw_estado=1)
                                            left join hc_os_solicitudes_manuales as v on(e.hc_os_solicitud_id=v.hc_os_solicitud_id and v.usuario_id=".UserGetUID()."),
                                            tipos_afiliado as b, servicios as c, pacientes as d, cups as f, autorizaciones as j, system_usuarios as k,
                                            planes as z
                                            where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
                                            and a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                            and z.plan_id=a.plan_id
                                            and date(v.fecha_resgistro) = date(now())
                                            and a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."' 
                                            and a.tipo_id_paciente=d.tipo_id_paciente
                                            and a.paciente_id=d.paciente_id and (g.cargo=f.cargo or h.cargo=f.cargo)
                                            and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                                            and j.usuario_id=k.usuario_id and e.sw_estado=1
                                            order by a.plan_id";
            $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error orde1";
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
                        $_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']=$var;
                }

                //SOLICITUDES
                if(!empty($_SESSION['ARREGLO']['MALLA']))
                {
                            $query = "select distinct t.nivel_autorizador_id,NULL, a.cantidad,a.hc_os_solicitud_id,a.cargo as cargos,
                                                p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente,
                                                b.paciente_id,k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                                                a.plan_id,f.plan_descripcion,
                                                a.os_tipo_solicitud_id,a.sw_estado,
                                                b.servicio,p.descripcion,
                                                m.descripcion as desserv,g.descripcion as desos,
                                                b.fecha,NULL,
                                                b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel,
                                                t.empresa_id
                                                from    userpermisos_centro_autorizacion as t
                                                left join    hc_os_solicitudes as a on(t.usuario_id=".UserGetUID()."
                                                and a.plan_id=t.plan_id or t.sw_todos_planes=1 and a.sw_estado=1),
                                                planes as f, os_tipos_solicitudes as g,
                                                pacientes as k, servicios as m,
                                                cups as p,
                                                hc_os_solicitudes_manuales as b
                                                where
                                                p.cargo=a.cargo and a.sw_estado=1
                                                and a.plan_id=f.plan_id
                                                and date(b.fecha_resgistro) = date(now())
                                                and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                                                and a.evolucion_id is null
                                                and t.usuario_id=".UserGetUID()."
                                                and b.servicio=m.servicio
                                                and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                                                and b.tipo_id_paciente=k.tipo_id_paciente
                                                and b.paciente_id=k.paciente_id
                                                and b.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                                and b.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error sol2";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
                        if(!$result->EOF)
                        {
                                        while(!$result->EOF)
                                        {
                                                        $vars[]=$result->GetRowAssoc($ToUpper = false);
                                                        $result->MoveNext();
                                        }
                        }
                        $result->Close();
                        $_SESSION['SOLICITUD']['ARREGLO']['DETALLE2']=$vars;
                }

                $this->frmError["MensajeError"]="La Solicitud Fue Terminada Satisfactoriamente.";
                $this->FormaFinSolicitud();
                //$this->Menu();
                return true;
        }

        /**
        *
        */
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
                    $query = "select count(r.grupo_tarifario_id)
                                        from tarifarios_equivalencias as a, tarifarios_detalle as h,
                                        plan_tarifario as r
                                        where a.cargo_base='$cargo' and h.cargo=a.cargo
                                        and h.tarifario_id=a.tarifario_id
                                        and r.plan_id=$plan and h.grupo_tarifario_id=r.grupo_tarifario_id
                                        and h.subgrupo_tarifario_id=r.subgrupo_tarifario_id
                                        and h.tarifario_id=r.tarifario_id";
                    $result=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Tabal autorizaiones";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                    }

                    if(!$result->EOF)
                    {   $var=$result->fields[0];  }

                    return $var;
        }


    /**
    * Busca el nombre del paciente
    * @access public
    * @return array
    * @param string tipo de documento
    * @param int numero de documento
    */
    function NombrePaciente($TipoDocumento,$Documento)
    {
            list($dbconn) = GetDBconn();
            $query = "SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre
                                            FROM pacientes
                                            WHERE paciente_id='$Documento' AND tipo_id_paciente ='$TipoDocumento'";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $vars=$resulta->GetRowAssoc($ToUpper = false);
            return $vars;
    }


    /**
    *
    */
    function TiposServicios()
    {
            list($dbconn) = GetDBconn();
            $query = "select servicio, descripcion from servicios where sw_asistencial=1";
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
            return $vars;
    }

		/**
		*
		*/
		function GuardarDatosSolicitud()
		{
						if((!$_REQUEST['Origen'] AND !$_REQUEST['Origen1'] ) || !$_REQUEST['Medico'] || $_REQUEST['Serv']==-1 || !$_REQUEST['Fecha'])
						{
									if((!$_REQUEST['Origen'] AND !$_REQUEST['Origen1'] ))
									{
											if(!$_REQUEST['Origen']){ $this->frmError["Origen"]=1; }
											if(!$_REQUEST['Origen1']){ $this->frmError["Origen1"]=1; }
									}
									if(!$_REQUEST['Medico'] ){ $this->frmError["Medico"]=1; }
									if($_REQUEST['Serv']==-1){ $this->frmError["Serv"]=1; }
									if(!$_REQUEST['Fecha']){ $this->frmError["Fecha"]=1; }
									$this->frmError["MensajeError"]="Faltan datos obligatorios.";
									if(!$this->FormaDatosSolicitud()){
													return false;
									}
									return true;
						}

						if(!$_REQUEST['Origen'] AND !$_REQUEST['Origen1'] )
						{
								if(!$_REQUEST['Origen']){ $this->frmError["Origen"]=1; }
								if(!$_REQUEST['Origen1']){ $this->frmError["Origen1"]=1; }
						}

						if(!empty($_REQUEST['Origen1']))
						{   $_REQUEST['Origen']=$_REQUEST['Origen1'];   }

						if($_REQUEST['Fecha'] > date('d/m/Y'))
						{
										$this->frmError["Fecha"]=1;
										$this->frmError["MensajeError"]="La Fecha debe ser anterior a la actual.";
										if(!$this->FormaDatosSolicitud()){
														return false;
										}
										return true;
						}

						$_SESSION['SOLICITUD']['DATOS']['MEDICO']=$_REQUEST['Medico'];
						$f=explode('/',$_REQUEST['Fecha']);
						$Fecha=$f[2].'-'.$f[1].'-'.$f[0];
						$_SESSION['SOLICITUD']['DATOS']['FECHA']=$Fecha;
						$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']=$_REQUEST['Origen'];
						$_SESSION['SOLICITUD']['DATOS']['SERVICIO']=$_REQUEST['Serv'];
						$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']=$_REQUEST['Observacion'];
						$_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']=$_REQUEST['departamento'];						

						$this->FormaTiposCargos();
						return true;
		}


//-------------------APOYOS DIAGNOSTICOS-------------------------------------------

        /**
        *
        */
        function Apoyos()
        {
                    $this->frmForma();
                    return true;
        }

        /*
        *
        */
        function Consulta_Solicitud_Apoyod($k)
        {
                list($dbconnect) = GetDBconn();
                $query= "SELECT a.*, b.cargo, b.plan_id, b.os_tipo_solicitud_id, e.observacion,
                                    c.descripcion, d.descripcion as tipo,
                                    informacion_cargo('".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."',b.cargo,'')
                                    FROM hc_os_solicitudes_manuales as a, hc_os_solicitudes as b
                                    left join hc_os_solicitudes_apoyod e on (b.hc_os_solicitud_id = e.hc_os_solicitud_id),
                                    cups c, apoyod_tipos d
                                    WHERE a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                    and a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
                                    and a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.sw_estado=1
                                    and a.hc_os_solicitud_id=$k
                                    and b.cargo=c.cargo and e.apoyod_tipo_id=d.apoyod_tipo_id
                                    order by a.hc_os_solicitud_id";
                $result = $dbconnect->Execute($query);

                if ($dbconnect->ErrorNo() != 0)
                {
                    $this->error = "Error al buscar en la consulta de solictud de apoyos";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
                }
                else
                {
                        $vector=$result->GetRowAssoc($ToUpper = false);
                }
                return $vector;
        }

        /**
        *
        */
        function Diagnosticos_Solicitados($hc_os_solicitud_id)
        {
                list($dbconnect) = GetDBconn();
                $query= "select a.diagnostico_id, a.diagnostico_nombre
                FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
                WHERE b.hc_os_solicitud_id = $hc_os_solicitud_id AND a.diagnostico_id = b.diagnostico_id";
                $result = $dbconnect->Execute($query);
                if ($dbconnect->ErrorNo() != 0)
                {
                    $this->error = "Error al buscar en la tabla apoyod_tipos";
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
                return $vector;
        }

        /**
        *
        */
        function tipos()
        {
                list($dbconnect) = GetDBconn();
                $query= "SELECT apoyod_tipo_id, descripcion
                                FROM apoyod_tipos";
                $result = $dbconnect->Execute($query);
                if ($dbconnect->ErrorNo() != 0)
                {
                    $this->error = "Error al buscar en la tabla apoyod_tipos";
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
                return $vector;
        }

        /**
        *
        */
        function tiposqx()
        {
                list($dbconnect) = GetDBconn();
         				 $query= "SELECT a.tipo_cargo, a.descripcion
                         FROM tipos_cargos a, qx_grupos_tipo_cargo b
                         WHERE a.grupo_tipo_cargo = b.grupo_tipo_cargo ";
	               $result = $dbconnect->Execute($query);
                if ($dbconnect->ErrorNo() != 0)
                {
                    $this->error = "Error al buscar en la tabla apoyod_tipos";
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
                return $vector;
        }

        /**
        *
        */
        function GetForma()
        {
                if($_REQUEST['accionapoyo']=='Busqueda_Avanzada')
                {
                            $vectorA= $this->Busqueda_Avanzada();
                            $this-> frmForma_Seleccion_Apoyos($vectorA);
                            return true;
                }

                if($_REQUEST['accionapoyo']=='insertar_varias')
                {
                        $this->Insertar_Varias_Solicitudes();
                        return true;
                }

                if($_REQUEST['accionapoyo']=='eliminar')
                {
                        $this->Eliminar_Apoyod_Solicitado($_REQUEST['hc_os_solicitud_idapoyo']);
                        return true;
                }

                if($_REQUEST['accionapoyo']=='observacion')
                {
                        $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo']);
                        return true;
                }

                if($_REQUEST['accionapoyo']=='modificar')
                {
                            $this->Modificar_Apoyod_Solicitado($_REQUEST['hc_os_solicitud_idapoyo']);
                            return true;
                }

                if($_REQUEST['accionapoyo']=='Busqueda_Avanzada_Diagnosticos')
                {
                            $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                            $this-> frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo'],$vectorD);
                            return true;
                }

                if($_REQUEST['accionapoyo']=='insertar_varios_diagnosticos')
                {
                        $this->Insertar_Varios_Diagnosticos();
                        $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo']);
                        return true;
                }

                if($_REQUEST['accionapoyo']=='eliminar_diagnostico')
                {
                        $this->Eliminar_Diagnostico_Solicitado($_REQUEST['hc_os_solicitud_idapoyo'], $_REQUEST['codigoapoyo']);
                        $this->frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo']);
                        return true;
                }
    }

    function Eliminar_Diagnostico_Solicitado($hc_os_solicitud_id, $codigo)
    {
                list($dbconn) = GetDBconn();
                $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                WHERE hc_os_solicitud_id = $hc_os_solicitud_id
                                AND diagnostico_id = '$codigo'";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                        return false;
                }
                else
                {
                            $this->frmError["MensajeError"]="DIAGNOSTICO ELIMINADO.";
                }
            return true;
    }

        /**
        *
        */
        function Insertar_Varios_Diagnosticos()
        {
                list($dbconn) = GetDBconn();
                
                foreach($_REQUEST['opDapoyo'] as $index=>$codigo)
                {
										$arreglo=explode(",",$codigo);			
										//BUSQUEDA DE DX REPETIDO EN SOLICITUD
										$query="SELECT count(*) 
														FROM hc_os_solicitudes_diagnosticos
														WHERE hc_os_solicitud_id = '".$arreglo[0]."'
														AND diagnostico_id = '".$arreglo[1]."';";										
										$resulta=$dbconn->Execute($query);
										if ($resulta->fields[0]==0)
										{ 
													//BUSQUEDA DE DX PRINCIPAL EN SOLICITUD
													$sql="SELECT count(*) 
																	FROM hc_os_solicitudes_diagnosticos
																	WHERE hc_os_solicitud_id = '".$arreglo[0]."'
																	AND sw_principal = '1';";
													$resulta=$dbconn->Execute($sql);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
															return false;
													}
													
													//INSERCION DE 1 DX PRINCIPAL
													if($resulta->fields[0]==0)
													{
															$query="INSERT INTO hc_os_solicitudes_diagnosticos
																							(hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
																			VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '1', '1');";
													}
													//INSERCION DE LOS DEMAS DX'S (NO PRINCIPALES)
													else
													{
															$query="INSERT INTO hc_os_solicitudes_diagnosticos
																							(hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
																			VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '1', '0');";
													}
													$resulta=$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
													}
										}               
										//FIN BUSQUEDA DE DX REPETIDO EN INGRESO
										else
										{
														$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
 										}												
 						}
            return true;
        }

    /**
    *
    */
    function Busqueda_Avanzada_Diagnosticos()
    {
                list($dbconn) = GetDBconn();
                $codigo       = STRTOUPPER ($_REQUEST['codigoapoyo']);
                $diagnostico  =STRTOUPPER($_REQUEST['diagnosticoapoyo']);

                $busqueda1 = '';
                $busqueda2 = '';

                if ($codigo != '')
                {
                    $busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
                }

                if (($diagnostico != '') AND ($codigo != ''))
                {
                    $busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
                }

                if (($diagnostico != '') AND ($codigo == ''))
                {
                    $busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
                }

                if(empty($_REQUEST['conteoapoyo']))
                {
                    $query = "SELECT count(*)
                                FROM diagnosticos
                                $busqueda1 $busqueda2";
                    $resulta = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    list($this->conteo)=$resulta->fetchRow();
                }
                else
                {
                    $this->conteo=$_REQUEST['conteoapoyo'];
                }
                if(!$_REQUEST['Ofapoyo'])
                {
                    $Of='0';
                }
                else
                {
                    $Of=$_REQUEST['Ofapoyo'];
                    if($Of > $this->conteo)
                {
                    $Of=0;
                    $_REQUEST['Ofapoyo']=0;
                    $_REQUEST['paso1apoyo']=1;
                }
            }
                    $query = "
                            SELECT diagnostico_id, diagnostico_nombre
                            FROM diagnosticos
                            $busqueda1 $busqueda2 order by diagnostico_id
                            LIMIT ".$this->limit." OFFSET $Of;";
                            
            $resulta = $dbconn->Execute($query);
            //$this->conteo=$resulta->RecordCount();
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i=0;
            while(!$resulta->EOF)
            {
                $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }

            if($this->conteo==='0')
                {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                return false;
                }

                //$this-> frmForma_Modificar_Observacion($_REQUEST['hc_os_solicitud_idapoyo'],$_REQUEST['cargoapoyo'],$_REQUEST['descripcionapoyo'], $_REQUEST['observacionapoyo'],$var);
                //return true;
                return $var;
    }


        function Busqueda_Avanzada()
        {
                list($dbconn) = GetDBconn();
                $opcion      = ($_REQUEST['criterio1apoyo']);
                $cargo       = ($_REQUEST['cargoapoyo']);
                $descripcion =STRTOUPPER($_REQUEST['descripcionapoyo']);

                $filtroTipoCargo = '';
                $busqueda1 = '';
                $busqueda2 = '';

                    if($opcion != '001' && !empty($opcion) && $opcion != '002')
                    {
                        $filtroTipoCargo=" AND a.grupo_tipo_cargo = '$opcion'";
                    }

                    if ($cargo != '')
                    {
                        $busqueda1 =" AND a.cargo LIKE '$cargo%'";
                    }

                    if ($descripcion != '')
                    {
                        $busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
                    }

                    if($opcion == '002')
                    {

                        $dpto = '';
                        $espe = '';
                        if ($this->departamento != '' )
                            {
                                $dpto = "AND a.departamento = '".$this->departamento."'";
                            }
                        if ($this->especialidad != '' )
                            {
                                $espe = "AND a.especialidad = '".$this->especialidad."'";
                            }
                        if ($dpto == '' AND $espe == '')
                            {
                                return false;
                            }
                    }


                if(empty($_REQUEST['conteoapoyo']))
                {
                    if($opcion == '002')
                        {
                            $query= "SELECT count(*)
                            FROM apoyod_solicitud_frecuencia a, cups b,
                            apoyod_tipos c
                            WHERE a.cargo = b.cargo
                            AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                            $dpto $espe $busqueda1 $busqueda2";
                        }
                    else
                        {
                            $query = "SELECT count(*)
                            FROM cups a,apoyod_tipos b
                            WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                            $filtroTipoCargo    $busqueda1 $busqueda2";
                        }

                    $resulta = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    list($this->conteo)=$resulta->fetchRow();
                }
                else
                {
                    $this->conteo=$_REQUEST['conteoapoyo'];
                }
                if(!$_REQUEST['Ofapoyo'])
                {
                    $Of='0';
                }
                else
                {
                    $Of=$_REQUEST['Ofapoyo'];
                    if($Of > $this->conteo)
                    {
                        $Of=0;
                        $_REQUEST['Ofapoyo']=0;
                        $_REQUEST['paso1apoyo']=1;
                    }
                }
                    if($opcion == '002')
                        {
                            $query= "SELECT DISTINCT a.cargo, b.descripcion, c.apoyod_tipo_id,
                            c.descripcion as tipo
                            FROM apoyod_solicitud_frecuencia a, cups b,
                            apoyod_tipos c
                            WHERE a.cargo = b.cargo
                            AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                            $dpto $espe $busqueda1 $busqueda2
                            order by c.descripcion, a.cargo
                            LIMIT ".$this->limit." OFFSET $Of;";
                        }
                    else
                        {
                        $query = "
                                SELECT a.cargo, a.descripcion, b.apoyod_tipo_id,
                                b.descripcion as tipo
                                FROM cups a,apoyod_tipos b
                                WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                                $filtroTipoCargo    $busqueda1 $busqueda2 order by b.apoyod_tipo_id, a.cargo
                                LIMIT ".$this->limit." OFFSET $Of;";
                        }
                $resulta = $dbconn->Execute($query);
                //$this->conteo=$resulta->RecordCount();
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $i=0;
                while(!$resulta->EOF)
                {
                    $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $i++;
                }

                if($this->conteo==='0')
                    {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                    return false;
                    }
                return $var;
        }

        /**
        *
        */
        function Insertar_Varias_Solicitudes()
        {
            list($dbconn) = GetDBconn();
            
            $query = "select empresa_id from planes	where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
            
            $resultado=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al insertar en hc_os_solicitudes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $empresa=$resultado->fields[0];
						$evento = "NULL"; 
						if(!empty($_SESSION['SolitudManual']['evento'])) $evento = $_SESSION['SolitudManual']['evento'];
						
            $dbconn->BeginTrans();
            foreach($_REQUEST['opapoyo'] as $index=>$codigo)
            {
               $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
               $result=$dbconn->Execute($query1);
               $hc_os_solicitud_id=$result->fields[0];

               $arreglo=explode(",",$codigo);

               $query2="INSERT INTO hc_os_solicitudes
                               (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
                        VALUES ($hc_os_solicitud_id,
                        		  NULL,
                                '".$arreglo[0]."', 
                                'APD',
                                ".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].",
                                '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                                '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."')";
	             $resulta=$dbconn->Execute($query2);
	             if ($dbconn->ErrorNo() != 0)
	             {
                  $this->error = "Error al insertar en hc_os_solicitudes";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $dbconn->RollbackTrans();
                  return false;
	             }
               else
               {
	                $resulta->Close();
	                $query3="INSERT INTO hc_os_solicitudes_apoyod
	                                (hc_os_solicitud_id, apoyod_tipo_id)
	                         VALUES($hc_os_solicitud_id, '".$arreglo[1]."');";
	                
	                $resulta1=$dbconn->Execute($query3);
	                if ($dbconn->ErrorNo() != 0)
	                {
	                   $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
	                   $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	                   $dbconn->RollbackTrans();
	                	 return false;
	                }
	                else
	                {
	                  $resulta1->Close();
										if(empty($_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']))
										{   $dpto='NULL';   }
										else
										{   $dpto="'".$_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']."'";   }	
											                  
	                  $query3="INSERT INTO hc_os_solicitudes_manuales(
	                  								hc_os_solicitud_id,
																		fecha,servicio,
																		profesional,
																		prestador,
																		observaciones,
																		tipo_id_paciente,
																		paciente_id,
																		fecha_resgistro,
																		usuario_id,
																		empresa_id,
																		tipo_afiliado_id,
																		rango,
																		semanas_cotizadas,
																		departamento,
																		evento_soat)
                             VALUES($hc_os_solicitud_id, 
                             				'".$_SESSION['SOLICITUD']['DATOS']['FECHA']."',
                             				'".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."',
                             				'".$_SESSION['SOLICITUD']['DATOS']['MEDICO']."',
                              			'".$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']."',
                              			'".$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']."',
                              			'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."',
                              			'".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                              			'now()',
                              			".UserGetUID().",
                              			'$empresa',
                              			'".$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']."',
                              			'".$_SESSION['SOLICITUD']['PACIENTE']['RANGO']."',
                              			".$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'].",
                              			$dpto,
                              			".$evento.");";
	                    $resulta1=$dbconn->Execute($query3);
	                    if ($dbconn->ErrorNo() != 0)
	                    {
	                        $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
	                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	                        $dbconn->RollbackTrans();
	                        return false;
	                    }
	                    $resulta1->Close();
                    }
               }
               $_SESSION['ARREGLO']['DATOS']['APOYOS'][$hc_os_solicitud_id]=$hc_os_solicitud_id;
               $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['hc_os_solicitud_id']=$hc_os_solicitud_id;
               $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cargo']=$arreglo[0];
               $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cantidad']=1;
            }
            $dbconn->CommitTrans();
            $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
            $this->Apoyos();
            return true;
        }

        function Eliminar_Apoyod_Solicitado($hc_os_solicitud_id)
        {
                    list($dbconn) = GetDBconn();
                    $dbconn->BeginTrans();
                    $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0){
                            $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        else
                        {
                                    $query1="DELETE FROM hc_os_solicitudes_apoyod
                                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                    $resulta1=$dbconn->Execute($query1);
                                    if ($dbconn->ErrorNo() != 0)
                                    {
                                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                    else
                                    {
                                            $query2="DELETE FROM hc_os_solicitudes_manuales
                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                            $resulta1=$dbconn->Execute($query2);
                                            if ($dbconn->ErrorNo() != 0)
                                            {
                                                $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                $dbconn->RollbackTrans();
                                                return false;
                                            }

                                            $query2="DELETE FROM hc_os_solicitudes
                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                            $resulta1=$dbconn->Execute($query2);
                                            if ($dbconn->ErrorNo() != 0)
                                            {
                                                $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                $dbconn->RollbackTrans();
                                                return false;
                                            }
                                            else
                                            {
                                                    $dbconn->CommitTrans();
                                                    unset($_SESSION['ARREGLO']['DATOS']['APOYOS'][$hc_os_solicitud_id]);
                                                    unset($_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]);
                                                    $this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
                                            }
                                    }
                        }
                        $this->Apoyos();
                        return true;
        }

        /**
        *
        */
        function Modificar_Apoyod_Solicitado($hc_os_solicitud_id)
        {
                    list($dbconn) = GetDBconn();
                    $obs = $_REQUEST['obsapoyo'];
                    $query= "UPDATE hc_os_solicitudes_apoyod SET observacion = '$obs'
                                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al actualizar la observacion en hc_os_solicitudes_apoyod";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $this->Apoyos();
                    return true;
        }


//------------------------------INTERCONSULTA-----------------------------------
        /**
        *
        */
        function Inter()
        {
                    $this->frmFormaInter();
                    return true;
        }

        /**
        *
        */
        function GetFormaInter()
        {
                if($_REQUEST['accioninter']=='Busqueda_Avanzada_Especialidad')
                {
                            $vectorA= $this->Busqueda_Avanzada_Especialidad();
                            $this-> frmForma_Seleccion_Especialidades($vectorA);
                }

                if($_REQUEST['accioninter']=='insertar_varias_especialidades')
                {
                        $this->Insertar_Varias_Especialidades();
                        $this->frmFormaInter();
                }

                if($_REQUEST['accioninter']=='eliminar')
                {
                        $this->Eliminar_Interconsulta_Solicitada($_REQUEST['hc_os_solicitud_idinter']);
                        $this->frmFormaInter();
                }

                if($_REQUEST['accioninter']=='observacion')
                {
                    $this->frmForma_Modificar_ObservacionInter($_REQUEST['hc_os_solicitud_idinter'],$_REQUEST['codigo_espinter'],$_REQUEST['descripcioninter'], $_REQUEST['observacioninter'], $_REQUEST['sw_cantidadinter'], $_REQUEST['cantidadinter']);
                }

                if($_REQUEST['accioninter']=='modificar')
                {
                        $this->Modificar_Interconsulta_Solicitada($_REQUEST['hc_os_solicitud_idinter']);
                        $this->frmFormaInter();
                }
                if($_REQUEST['accioninter']=='Busqueda_Avanzada_Diagnosticos')
                {
                            $vectorD= $this->Busqueda_Avanzada_DiagnosticosInter();
                            $this-> frmForma_Modificar_ObservacionInter($_REQUEST['hc_os_solicitud_idinter'],$_REQUEST['codigo_espinter'],$_REQUEST['descripcioninter'], $_REQUEST['observacioninter'], $_REQUEST['sw_cantidadinter'], $_REQUEST['cantidadinter'],$vectorD);
                }

                if($_REQUEST['accioninter']=='insertar_varios_diagnosticos')
                {
                        $this->Insertar_Varios_DiagnosticosInter();
                        $this->frmForma_Modificar_ObservacionInter($_REQUEST['hc_os_solicitud_idinter'],$_REQUEST['codigo_espinter'],$_REQUEST['descripcioninter'], $_REQUEST['observacioninter'], $_REQUEST['sw_cantidadinter'], $_REQUEST['cantidadinter']);
                }

                if($_REQUEST['accioninter']=='eliminar_diagnostico')
                {
                        $this->Eliminar_Diagnostico_SolicitadoInter($_REQUEST['hc_os_solicitud_idinter'], $_REQUEST['codigointer']);
                        $this->frmForma_Modificar_ObservacionInter($_REQUEST['hc_os_solicitud_idinter'],$_REQUEST['codigo_espinter'],$_REQUEST['descripcioninter'], $_REQUEST['observacioninter'], $_REQUEST['sw_cantidadinter'], $_REQUEST['cantidadinter']);
                }

                return true;
    }

    function Eliminar_Diagnostico_SolicitadoInter($hc_os_solicitud_id, $codigo)
    {
                list($dbconn) = GetDBconn();
                $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."
                                AND diagnostico_id = '".$codigo."'";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                        return false;
                        }
                else
                        {
                            $this->frmError["MensajeError"]="DIAGNOSTICO ELIMINADO.";
                        }
            return true;
    }
    /**
    *
    */
    function Consulta_Solicitud_Interconsulta($k)
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT a.hc_os_solicitud_id, g.sw_cantidad, a.cantidad, a.cargo, b.descripcion, e.especialidad, e.observacion,
                                informacion_cargo('".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."',a.cargo,'')
                                from hc_os_solicitudes a left join hc_os_solicitudes_manuales as p on(p.hc_os_solicitud_id=a.hc_os_solicitud_id)
                                left join hc_os_solicitudes_interconsultas e on (a.hc_os_solicitud_id = e.hc_os_solicitud_id)
                                left join cups as g    on (a.cargo = g.cargo),
                                especialidades b
                                where p.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                and p.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
                                and a.sw_estado=1
                                and a.hc_os_solicitud_id=$k
                                AND b.especialidad = e.especialidad order by a.hc_os_solicitud_id";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la consulta de solicitud de interconsultas";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
            }
            else
            {
                    $vector=$result->GetRowAssoc($ToUpper = false);
            }
            return $vector;
    }

    function Busqueda_Avanzada_Especialidad()
    {
            list($dbconn) = GetDBconn();
            $opcion      = ($_REQUEST['criterio1inter']);
            $codigo_esp       = ($_REQUEST['codigo_espinter']);
            $especialidad =STRTOUPPER($_REQUEST['especialidadinter']);

            $conector   = '';
            $busqueda1  = '';
            $busqueda2  = '';

                if ($codigo_esp != '')
                {
                    $conector = "WHERE ";
                    $busqueda1 =" a.especialidad LIKE '$codigo_esp%'";
                }

                if ($especialidad != '')
                {
                    if ($conector  == '')
                    {
                            $conector = "WHERE ";
                            $busqueda2 ="a.descripcion LIKE '%$especialidad%'";
                    }
                    else
                    {
                            $busqueda2 ="AND a.descripcion LIKE '%$especialidad%'";
                    }
                }

                if(empty($_REQUEST['conteointer']))
                {
                            $query = "SELECT count(*)
                                                FROM especialidades as a join especialidades_cargos as b
                                                on (a.especialidad = b.especialidad)
                                                left join tipos_consulta as c on (a.especialidad = c.especialidad)
                                    left join cups as d on (b.cargo = d.cargo)
                                          $conector $busqueda1 $busqueda2";

                    $resulta = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    list($this->conteo)=$resulta->fetchRow();
                }
                else
                {
                    $this->conteo=$_REQUEST['conteointer'];
                }
                if(!$_REQUEST['Ofinter'])
                {
                    $Of='0';
                }
                else
                {
                    $Of=$_REQUEST['Ofinter'];
                    if($Of > $this->conteo)
                    {
                        $Of=0;
                        $_REQUEST['Ofinter']=0;
                        $_REQUEST['paso1inter']=1;
                    }
                }

                $query = "SELECT a.especialidad, d.sw_cantidad, a.descripcion, b.cargo, c.tipo_consulta_id
                        FROM especialidades as a join especialidades_cargos as b
                        on (a.especialidad = b.especialidad)
                        left join tipos_consulta as c on (a.especialidad = c.especialidad)
            left join cups as d on (b.cargo = d.cargo)
                  $conector $busqueda1 $busqueda2 order by a.especialidad
                        LIMIT ".$this->limit." OFFSET $Of;";

                $resulta = $dbconn->Execute($query);
                //$this->conteo=$resulta->RecordCount();
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $i=0;
                while(!$resulta->EOF)
                {
                    $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                    $i++;
                }

                if($this->conteo==='0')
                    {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                    return false;
                    }
                return $var;
        }


        /**
        *
        */
        function Insertar_Varias_Especialidades()
        {
	        list($dbconn) = GetDBconn();
	        $query = "select empresa_id from planes
	        where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
	        $resultado=$dbconn->Execute($query);
	        if ($dbconn->ErrorNo() != 0)
	        {
	            $this->error = "Error al insertar en hc_os_solicitudes";
	            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	            return false;
	        }
	        $empresa=$resultado->fields[0];
					$evento = "NULL"; 
					if(!empty($_SESSION['SolitudManual']['evento'])) $evento = $_SESSION['SolitudManual']['evento'];
	
	        $dbconn->BeginTrans();
	        foreach($_REQUEST['opEinter'] as $index=>$codigo)
	        {
            $arreglo=explode(",",$codigo);
            if(!empty($_REQUEST['cantidadinter'.$arreglo[1]]))
            {
	            $cantidad =$_REQUEST['cantidadinter'.$arreglo[1]];
	            if (is_numeric($cantidad)==0)
	            {
	                $this->frmError["MensajeError"]="DIGITE CANTIDADES VALIDAS.";
	                return false;
	            }
            }
            else
            {
                $cantidad =1;
            }

            $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
            $result=$dbconn->Execute($query1);
            $hc_os_solicitud_id=$result->fields[0];

            $query2="INSERT INTO hc_os_solicitudes
                                (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, cantidad, paciente_id, tipo_id_paciente)
                     VALUES($hc_os_solicitud_id,Null,
                            '".$arreglo[0]."', 'INT',
                            ".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].", 
                            ".$cantidad.",
                            '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                            '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."')";
            $resulta=$dbconn->Execute($query2);
            if ($dbconn->ErrorNo() != 0)
            {
	            $this->error = "Error al insertar en hc_os_solicitudes";
	            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
	            $dbconn->RollbackTrans();
	            return false;
            }
            else
            {
              $resulta->Close();
							if(empty($_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']))
							{   $dpto='NULL';   }
							else
							{   $dpto="'".$_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']."'";   }	
																								
              $query3="INSERT INTO hc_os_solicitudes_manuales(
              								hc_os_solicitud_id,
															fecha,servicio,
															profesional,
															prestador,
															observaciones,
															tipo_id_paciente,
															paciente_id,
															fecha_resgistro,
															usuario_id,
															empresa_id,
															tipo_afiliado_id,
															rango,
															semanas_cotizadas,
															departamento,
															evento_soat)
                       VALUES($hc_os_solicitud_id, 
                       				'".$_SESSION['SOLICITUD']['DATOS']['FECHA']."',
                              '".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."',
                              '".$_SESSION['SOLICITUD']['DATOS']['MEDICO']."',
                              '".$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']."',
                              '".$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']."',
                              '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."',
                              '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                              'now()',
                              ".UserGetUID().",
                              '$empresa',
                              '".$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']."',
                              '".$_SESSION['SOLICITUD']['PACIENTE']['RANGO']."',
                              ".$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'].",
                              $dpto,
                              ".$evento.");";
                $resulta1=$dbconn->Execute($query3);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                $resulta1->Close();

                $query3="INSERT INTO  hc_os_solicitudes_interconsultas
                (hc_os_solicitud_id, especialidad)
                VALUES  ($hc_os_solicitud_id, '".$arreglo[1]."');";
                $resulta1=$dbconn->Execute($query3);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al insertar en hc_os_solicitudes_interconsultas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                else
                {
                    if($arreglo[2]!=NULL )
                    {
                        $query4="INSERT INTO hc_os_solicitudes_citas
                            (hc_os_solicitud_id, tipo_consulta_id)
                            VALUES  ($hc_os_solicitud_id, '".$arreglo[2]."');";

                        $result=$dbconn->Execute($query4);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al insertar en hc_os_solicitudes_citas";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                    $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                }
              }
              $_SESSION['ARREGLO']['DATOS']['INTER'][$hc_os_solicitud_id]=$hc_os_solicitud_id;
              $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['hc_os_solicitud_id']=$hc_os_solicitud_id;
              $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cargo']=$arreglo[0];
              $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cantidad']=$cantidad;
          }
          $dbconn->CommitTrans();
          return true;
        }

        /**
        *
        */
        function Eliminar_Interconsulta_Solicitada($hc_os_solicitud_id)
        {
                    list($dbconn) = GetDBconn();
                    $dbconn->BeginTrans();
                    $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                            $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                            $dbconn->RollbackTrans();
                            return false;
                    }
                    else
                    {
                                $query="DELETE FROM hc_os_solicitudes_citas
                                WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                $resulta=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                    $dbconn->RollbackTrans();
                                    return false;
                                }

                                $query1="DELETE FROM hc_os_solicitudes_interconsultas
                                WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                $resulta1=$dbconn->Execute($query1);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                                else
                                {
                                        $query2="DELETE FROM hc_os_solicitudes_manuales
                                        WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                        $resulta1=$dbconn->Execute($query2);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }

                                        $query2="DELETE FROM hc_os_solicitudes
                                        WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                        $resulta1=$dbconn->Execute($query2);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }
                                        else
                                        {
                                            $dbconn->CommitTrans();
                                            UNSET($_SESSION['ARREGLO']['DATOS']['INTER'][$hc_os_solicitud_id]);
                                            unset($_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]);
                                            $this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
                                        }
                                }
                    }
                    return true;
        }

        /**
        *
        */
        function Modificar_Interconsulta_Solicitada($hc_os_solicitud_id)
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                if(!empty($_REQUEST['cantidadinter']))
                {
                    $cantidad = $_REQUEST['cantidadinter'];
                    if (is_numeric($cantidad)==0)
                    {
                        $this->frmError["MensajeError"]="DIGITE CANTIDADES VALIDAS.";
                        return false;
                    }
                }
                else
                {
                    $cantidad =1;
                }

                $query= "UPDATE hc_os_solicitudes_interconsultas SET observacion = '".$_REQUEST['obsinter']."'
                                WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";

                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al actualizar la observacion en hc_os_solicitudes_interconsultas";
                    $this->frmError["MensajeError"]="NO SE LOGRO ACTUALIZAR LA INTERCONSULTA";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                else
                {
                        $query= "UPDATE hc_os_solicitudes SET cantidad = ".$cantidad."
                                        WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al actualizar la cantidad en hc_os_solicitudes";
                            $this->frmError["MensajeError"]="NO SE LOGRO ACTUALIZAR LA INTERCONSULTA";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="INTERCONSULTA MODIFICADA SATISFACTORIAMENTE.";
                return true;
        }

        /**
        *
        */
        function Busqueda_Avanzada_DiagnosticosInter()
        {
                list($dbconn) = GetDBconn();
                $codigo       = STRTOUPPER ($_REQUEST['codigointer']);
                $diagnostico  =STRTOUPPER($_REQUEST['diagnosticointer']);
                $busqueda1 = '';
                $busqueda2 = '';
                if ($codigo != '')
                {
                    $busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
                }
                if (($diagnostico != '') AND ($codigo != ''))
                {
                    $busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
                }

                if (($diagnostico != '') AND ($codigo == ''))
                {
                    $busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
                }
                if(empty($_REQUEST['conteointer']))
                {
                    $query = "SELECT count(*)
                                FROM diagnosticos
                                $busqueda1 $busqueda2";

                    $resulta = $dbconn->Execute($query);

                    if ($dbconn->ErrorNo() != 0)
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    list($this->conteo)=$resulta->fetchRow();
                }
                else
                {
                    $this->conteo=$_REQUEST['conteointer'];
                }
                if(!$_REQUEST['Ofinter'])
                {
                    $Of='0';
                }
                else
                {
                    $Of=$_REQUEST['Ofinter'];
                    if($Of > $this->conteo)
                    {
                        $Of=0;
                        $_REQUEST['Ofinter']=0;
                        $_REQUEST['paso1inter']=1;
                    }
                }
                        $query = "
                                SELECT diagnostico_id, diagnostico_nombre
                                FROM diagnosticos
                                $busqueda1 $busqueda2 order by diagnostico_id
                                LIMIT ".$this->limit." OFFSET $Of;";
                $resulta = $dbconn->Execute($query);
                //$this->conteo=$resulta->RecordCount();
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }

                while(!$resulta->EOF)
                {
                    $var[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }

                if($this->conteo==='0')
                    {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                    return false;
                    }
                return $var;
        }

        /**
        *
        */
        function Insertar_Varios_DiagnosticosInter()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                foreach($_REQUEST['opDinter'] as $index=>$codigo)
                {
										$arreglo=explode(",",$codigo);			
										//BUSQUEDA DE DX REPETIDO EN SOLICITUD
										$query="SELECT count(*) 
														FROM hc_os_solicitudes_diagnosticos
														WHERE hc_os_solicitud_id = '".$arreglo[0]."'
														AND diagnostico_id = '".$arreglo[1]."';";										
										$resulta=$dbconn->Execute($query);
										if ($resulta->fields[0]==0)
										{ 
													//BUSQUEDA DE DX PRINCIPAL EN SOLICITUD
													$sql="SELECT count(*) 
																	FROM hc_os_solicitudes_diagnosticos
																	WHERE hc_os_solicitud_id = '".$arreglo[0]."'
																	AND sw_principal = '1';";
													$resulta=$dbconn->Execute($sql);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
															return false;
													}
													
													//INSERCION DE 1 DX PRINCIPAL
													if($resulta->fields[0]==0)
													{
															$query="INSERT INTO hc_os_solicitudes_diagnosticos
																							(hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
																			VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '1', '1');";
													}
													//INSERCION DE LOS DEMAS DX'S (NO PRINCIPALES)
													else
													{
															$query="INSERT INTO hc_os_solicitudes_diagnosticos
																							(hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
																			VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '1', '0');";
													}
													$resulta=$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0)
													{
															$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
															return false;
													}
													else
													{
															$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
													}
										}               
										//FIN BUSQUEDA DE DX REPETIDO EN INGRESO
										else
										{
														$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
 										}		
                }
                $dbconn->CommitTrans();
                return true;
        }



//----------------------------PROCEDIMIENTOS QX---------------------------------


        function Qx()
        {
                        unset ($_SESSION['APOYOSqx']);
                        unset ($_SESSION['PROCEDIMIENTOqx']);
                        unset ($_SESSION['MODIFICANDOqx']);
                        unset($_SESSION['PASOqx']);
                        unset($_SESSION['PASO1qx']);
                        unset ($_SESSION['DIAGNOSTICOSqx']);
                        $this->frmFormaqx();
                        return true;
        }

        function GetFormaqx()
        {
                        if(empty($_REQUEST['accionqx']))
                        {
                            unset ($_SESSION['DIAGNOSTICOSqx']);
                            $this->frmFormaqx();
                        }
                        else
                        {
                            //-lo nuevo
                            if($_REQUEST['accionqx']=='Busqueda_Avanzada_Apoyos')
                            {
                                    if($_SESSION['PROCEDIMIENTOqx']=='')
                                    {
                                            $_SESSION['PROCEDIMIENTOqx'][hc_os_solicitud_id]    =    $_REQUEST['hc_os_solicitud_idqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][tipo]                =    $_REQUEST['tipoqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][cargos]            =    $_REQUEST['cargosqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][procedimiento]    =    $_REQUEST['procedimientoqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][observacion]    =    $_REQUEST['observacionqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][cirugia]            =    $_REQUEST['cirugiaqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][ambito]            =    $_REQUEST['ambitoqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][finalidad]        =    $_REQUEST['finalidadqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][movil]                =    $_REQUEST['movilqx'];
                                            $_SESSION['PROCEDIMIENTOqx'][fijo]                =    $_REQUEST['fijoqx'];
                                    }
                                    $vectorAPD= $this->Busqueda_Avanzada_Apoyos();
                                    $this-> frmForma_Seleccion_ApoyosQx($vectorAPD);
                            }
                            if($_REQUEST['accionqx']=='eliminarapoyo')
                            {
                                unset ($_SESSION['APOYOSqx'][$_REQUEST['apoyoqx']]);
                                if($_SESSION['MODIFICANDOqx']==1)
                                {
                                        $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                }
                                else
                                {
                                        $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['procedimientoqx']);
                                }
                            }
                            if($_REQUEST['accionqx']=='volver_de_solicitud_de_apoyos')
                            {
                                $_REQUEST['hc_os_solicitud_idqx'] = $_SESSION['PROCEDIMIENTOqx'][hc_os_solicitud_id];
                                $_REQUEST['tipoqx']                    = $_SESSION['PROCEDIMIENTOqx'][tipo];
                                $_REQUEST['cargosqx']             = $_SESSION['PROCEDIMIENTOqx'][cargos];
                                $_REQUEST['procedimientoqx']     = $_SESSION['PROCEDIMIENTOqx'][procedimiento];
                                $_REQUEST['observacionqx']     = $_SESSION['PROCEDIMIENTOqx'][observacion];
                                $_REQUEST['cirugiaqx']             = $_SESSION['PROCEDIMIENTOqx'][cirugia];
                                $_REQUEST['ambitoqx']             = $_SESSION['PROCEDIMIENTOqx'][ambito];
                                $_REQUEST['finalidadqx']         = $_SESSION['PROCEDIMIENTOqx'][finalidad];
                                $_REQUEST['movilqx']                 = $_SESSION['PROCEDIMIENTOqx'][movil];
                                $_REQUEST['fijoqx']                 = $_SESSION['PROCEDIMIENTOqx'][fijo];
                                unset($_SESSION['PROCEDIMIENTOqx']);
                                if($_SESSION['MODIFICANDOqx']==1)
                                {
                                        $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                }
                                else
                                {
                                        $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['procedimientoqx']);
                                }
                            }
                            if($_REQUEST['accionqx']=='insertar_varias')
                            {
                                $this->Insertar_Varias_SolicitudesQx();
                                $_REQUEST['hc_os_solicitud_idqx'] = $_SESSION['PROCEDIMIENTOqx'][hc_os_solicitud_id];
                                $_REQUEST['tipoqx']                    = $_SESSION['PROCEDIMIENTOqx'][tipo];
                                $_REQUEST['cargosqx']             = $_SESSION['PROCEDIMIENTOqx'][cargos];
                                $_REQUEST['procedimientoqx']     = $_SESSION['PROCEDIMIENTOqx'][procedimiento];
                                $_REQUEST['observacionqx']     = $_SESSION['PROCEDIMIENTOqx'][observacion];
                                $_REQUEST['cirugiaqx']             = $_SESSION['PROCEDIMIENTOqx'][cirugia];
                                $_REQUEST['ambitoqx']             = $_SESSION['PROCEDIMIENTOqx'][ambito];
                                $_REQUEST['finalidadqx']         = $_SESSION['PROCEDIMIENTOqx'][finalidad];
                                $_REQUEST['movilqx']                 = $_SESSION['PROCEDIMIENTOqx'][movil];
                                $_REQUEST['fijoqx']                 = $_SESSION['PROCEDIMIENTOqx'][fijo];
                                unset($_SESSION['PROCEDIMIENTOqx']);
                                if($_SESSION['MODIFICANDOqx']==1)
                                {
                                        $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                }
                                else
                                {
                                        $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['procedimientoqx']);
                                }
                            }
                            if($_REQUEST['accionqx']=='eliminarapoyo')
                            {
                                unset ($_SESSION['APOYOSqx'][$_REQUEST['apoyoqx']]);
                                if($_SESSION['MODIFICANDOqx']==1)
                                {
                                        $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                }
                                else
                                {
                                        $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['procedimientoqx']);
                                }
                            }
                            //------fin nuevo

                            if(!empty($_REQUEST['eliminardiagnosticobdqx']))
                            {
                                $this->EliminarDiagnosticoBD($_REQUEST['hc_os_solicitud_idqx'], $_REQUEST['tqx']);
                                $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                            }

                            if($_REQUEST['accionqx']=='Buscar')
                            {
                                if(empty($_REQUEST['busquedaqx']))
                                    {
                                        $this-> frmForma_Seleccion_Avanzada();
                                    }
                                else
                                    {
                                        $vector= $this->Buscar();
                                        if ($this->conteo == 1)
                                                {
                                                    $tipo            = $vector[0][tipo];
                                                    $cargos       = $vector[0][cargo];
                                                    $descripcion     = $vector[0][descripcion];
                                                    $this->Llenar_Procedimiento($tipo, $cargos, $descripcion);
                                                }
                                        else
                                                {
                                                    $this->frmFormaqx($vector);
                                                }
                                    }
                                }
                                if($_REQUEST['accionqx']=='Busqueda_Avanzada')
                                {
                                    unset ($_SESSION['DIAGNOSTICOSqx']);
                                    $vectorA= $this->Busqueda_AvanzadaQx();
                                    $this-> frmForma_Seleccion_Avanzada($vectorA);
                                }

                            if($_REQUEST['accionqx']=='OpcionesProcedimiento')
                            {
                                            if(!empty($_REQUEST['BuscarDiagqx']) OR !empty($_REQUEST['opcqx']))
                                                {
                                                    $vectorD= $this->Busqueda_Avanzada_DiagnosticosQx();
                                                    $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['descripcionqx'], $vectorD);
                                                }

                                            if(!empty($_REQUEST['guardarprocedimientoqx']))
                                                {
                                                    if($this->Insertar_Solicitud_Procedimiento($_REQUEST['cargosqx']) == true)
                                                    {
                                                        $this->frmFormaqx();
                                                    }
                                                }

                                            if(!empty($_REQUEST['guardarDiagqx']))
                                                {
                                                            $this->Insertar_Varios_Diagnosticosqx();
                                                            $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['descripcionqx'], $vectorD);
                                                }

                                            if(!empty($_REQUEST['eliminardiagnosticoqx']))
                                                {
                                                        unset ($_SESSION['DIAGNOSTICOSqx'][$_REQUEST['kqx']]);
                                                        $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['descripcionqx'],$vectorD);
                                                }
                                }
                                /*if($_REQUEST['accionqx']=='FormaAvanzada')
                                {
                                    $this-> frmForma_Seleccion_Avanzada($vectorA);
                                }*/

                                if($_REQUEST['accionqx']=='llenarprocedimiento')
                                {
                                        unset ($_SESSION['DIAGNOSTICOSqx']);
                                        $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['descripcionqx']);
                                }

                                if($_REQUEST['accionqx']=='modificarprocedimiento')
                                {
                                        unset ($_SESSION['DIAGNOSTICOSqx']);
                                        unset ($_SESSION['APOYOSqx']);
                                        $_SESSION['MODIFICANDOqx']=1;
                                        $apoyos =$this->Apoyos_Del_Procedimiento($_REQUEST['hc_os_solicitud_idqx']);
                                        if ($apoyos)
                                        {
                                            for($j=0;$j<sizeof($apoyos);$j++)
                                            {
                                                $_SESSION['APOYOSqx'][$apoyos[$j][cargo]]= $apoyos[$j][descripcion];
                                            }
                                        }
                                        $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                }

                                /*if($_REQUEST['accionqx']=='guardarmodificaciones')
                                {
                                        $this->Modificar_Procedimiento($_REQUEST['hc_os_solicitud_idqx']);
                                        $this->frmFormaqx();
                                }*/

                                if($_REQUEST['accionqx']=='eliminarprocedimiento')
                                {
                                        $this->Eliminar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                        $this->frmFormaQx();
                                }

                            if($_REQUEST['accionqx']=='OpcionesModificacionProcedimiento')
                            {
                                        if(!empty($_REQUEST['guardarmodificacionprocedimientoqx']))
                                        {
                                                $this->Modificar_Procedimiento($_REQUEST['hc_os_solicitud_idqx']);
                                                $this->frmFormaQx();
                                        }

                                        if(!empty($_REQUEST['eliminardiagnosticoqx']))
                                        {
                                            unset ($_SESSION['DIAGNOSTICOSqx'][$_REQUEST['kqx']]);
                                            //$this->EliminarDiagnosticoBD($_REQUEST['hc_os_solicitud_idqx'], $_REQUEST['tqx']);
                                            $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                        }

                                        if(!empty($_REQUEST['BuscarDiagqx']) OR !empty($_REQUEST['opcqx']))
                                                {
                                                    $vectorD=$this->Busqueda_Avanzada_DiagnosticosQx();
                                                    $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx'],$vectorD);
                                                }
                                        if(!empty($_REQUEST['minsertardiagnosticoqx']))
                                        {
                                                $this->Insertar_Varios_Diagnosticosqx();
                                                $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                                        }
                            }
                        }
                        return $this->salida;
        }


    function Insertar_Varias_SolicitudesQx()
    {
            foreach($_REQUEST['opqx'] as $index=>$codigo)
            {
                    $arreglo=explode(",",$codigo);
                    $_SESSION['APOYOSqx'][$arreglo[0]]=$arreglo[1];
            }
    }

    function Insertar_Varios_DiagnosticosQx()
    {
            foreach($_REQUEST['opDqx'] as $index=>$codigo)
            {
                    $arreglo=explode(",",$codigo);
                    $_SESSION['DIAGNOSTICOSqx'][$arreglo[0]]=$arreglo[1];
                    $i++;
            }
    }


    function Busqueda_AvanzadaQx()
    {
            list($dbconn) = GetDBconn();
            $opcion      = ($_REQUEST['criterio1qx']);
            $cargos       = ($_REQUEST['cargosqx']);
            $descripcion =STRTOUPPER($_REQUEST['descripcionqx']);

            $filtroTipoCargo = '';
            $busqueda1 = '';
            $busqueda2 = '';

            if($opcion != '-1' && !empty($opcion))
            {
                $filtroTipoCargo=" AND a.tipo_cargo = '$opcion'";
            }

            if ($cargos != '')
            {
                $busqueda1 =" AND a.cargo LIKE '$cargos%'";
            }

            if ($descripcion != '')
            {
                $busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
            }
						

            if(empty($_REQUEST['conteoqx']))
            {
												 $query = "SELECT count(*) FROM (SELECT DISTINCT a.cargo,
												a.descripcion, a.grupo_tipo_cargo, 
												c.descripcion as tipo, d.tipo_cargo
                        FROM cups a, qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
												tipos_cargos d					
                        WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo AND
                        b.grupo_tipo_cargo = c.grupo_tipo_cargo
												AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
                        $filtroTipoCargo$busqueda1$busqueda2)as a";														

                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                list($this->conteo)=$resulta->fetchRow();
            }
            else
            {
                $this->conteo=$_REQUEST['conteoqx'];
            }
            if(!$_REQUEST['Ofqx'])
            {
                $Of='0';
            }
            else
            {
                $Of=$_REQUEST['Ofqx'];
                if($Of > $this->conteo)
                {
                    $Of=0;
                    $_REQUEST['Ofqx']=0;
                    $_REQUEST['paso1qx']=1;
                }
            }
						 $query = "SELECT DISTINCT a.cargo, a.descripcion, a.grupo_tipo_cargo, 
									c.descripcion as tipo, d.tipo_cargo
                  FROM cups a, qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
									tipos_cargos d			
                  WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo AND
                  b.grupo_tipo_cargo = c.grupo_tipo_cargo
									AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
                  $filtroTipoCargo$busqueda1$busqueda2
                  LIMIT ".$this->limit." OFFSET $Of;";
						
            $resulta = $dbconn->Execute($query);
            //$this->conteo=$resulta->RecordCount();
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }

            if($this->conteo==='0')
                {
                                $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                return false;
                }
            $resulta->Close();
            return $var;
    }

    function EliminarDiagnosticoBD($hc_os_solicitud_id, $diagnostico_id)
    {
        list($dbconn) = GetDBconn();
        $query="DELETE FROM hc_os_solicitudes_diagnosticos
                        WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."
                        AND diagnostico_id = '".$diagnostico_id."'";

        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
            return false;
        }
        return true;
    }

    function tipocirugia()
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT * FROM qx_tipos_cirugia";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla qx_tipos_cirugia";
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
            return $vector;
    }

    function tipoambito()
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT * FROM qx_ambitos_cirugias";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla qx_ambitos_cirugias";
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
            return $vector;
    }


    function tipofinalidad()
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT * FROM qx_finalidades_procedimientos";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla qx_finalidades_procedimientos";
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
            return $vector;
    }


    function tipoequipofijo()
    {
            list($dbconnect) = GetDBconn();
        		$query= "SELECT * FROM qx_tipo_equipo_fijo order by tipo_equipo_fijo_id";						
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla qx_tipo_equipo_fijo";
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
            return $vector;
    }


    function tipoequipomovil()
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT * FROM qx_tipo_equipo_movil";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla qx_tipo_equipo_movil";
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
            return $vector;
    }


    function Consulta_Modificar_Procedimiento($hc_os_solicitud_id)
    {
            $pfj=$this->frmPrefijo;
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconnect) = GetDBconn();
            //--left join qx_tipos_cirugia j on (b.tipo_cirugia_id = j.tipo_cirugia_id) 
            //qx_ambitos_cirugias k, qx_finalidades_procedimientos l,
            //AND b.ambito_cirugia_id = k.ambito_cirugia_id
            //AND b.finalidad_procedimiento_id = l.finalidad_procedimiento_id
            $query= "SELECT a.hc_os_solicitud_id, a.evolucion_id, a.cargo, x.observacion,
            c.diagnostico_id, d.tipo_equipo_fijo_id, e.tipo_equipo_id, g.diagnostico_nombre, h.descripcion as fijo,
            i.descripcion as movil, m.descripcion, n.descripcion as tipo
            FROM hc_os_solicitudes a                     
            left join hc_os_solicitudes_diagnosticos c on (a.hc_os_solicitud_id = c.hc_os_solicitud_id)  
            left join diagnosticos g on (c.diagnostico_id = g.diagnostico_id), 
            hc_os_solicitudes_acto_qx x
            left join hc_os_solicitudes_requerimientos_equipo_quirofano d on
            (x.acto_qx_id = d.acto_qx_id) 
            left join qx_tipo_equipo_fijo h on (d.tipo_equipo_fijo_id = h.tipo_equipo_fijo_id)
            left join hc_os_solicitudes_requerimientos_equipos_moviles e on (x.acto_qx_id = e.acto_qx_id) 
            left join qx_tipo_equipo_movil i on (e.tipo_equipo_id = i.tipo_equipo_id), 
            cups m, grupos_tipos_cargo n
            WHERE a.hc_os_solicitud_id=$hc_os_solicitud_id 
            AND a.hc_os_solicitud_id = x.hc_os_solicitud_id            
            AND a.cargo  = m.cargo  
            AND m.grupo_tipo_cargo = n.grupo_tipo_cargo
            order by a.hc_os_solicitud_id";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconnect->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error en la consulta de la solicitud";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
            }
            else
            { $i=0;
                while ($arr=$result->FetchRow())
                {
                    $vector0[$arr['hc_os_solicitud_id']]=$arr;
                    if(!empty($arr['diagnostico_id']))
                    {
                        $vector1[$arr['hc_os_solicitud_id']][$arr['diagnostico_id']]=$arr['diagnostico_nombre'];
                        $_SESSION['DIAGNOSTICOSqx'][$arr['diagnostico_id']]=$arr['diagnostico_nombre'];
                    }
                    $vector2[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_fijo_id']]=$arr['fijo'];
                    $vector3[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_id']]=$arr['movil'];
                }
            }
            $vector[0]=$vector0;
            if(!empty($vector1))
            {
                $vector[1]=$vector1;
            }
            $vector[2]=$vector2;
            $vector[3]=$vector3;

            $result->Close();
            return $vector;
    }

        function Modificar_Procedimiento($hc_os_solicitud_id)
        {
                    list($dbconn) = GetDBconn();
                    $observacion = $_REQUEST['observacionqx'];
                    /*if($_REQUEST['cirugiaqx'] == -1 OR $_REQUEST['ambitoqx'] == -1
                    OR $_REQUEST['finalidadqx'] == -1)
                    {
                        if($_REQUEST['cirugiaqx'] == -1)
                        {
                                $this->frmError['cirugiaqx']=1;
                        }
                        if($_REQUEST['ambitoqx'] == -1)
                        {
                                $this->frmError['ambitoqx']=1;
                        }
                        if($_REQUEST['finalidadqx'] == -1)
                        {
                                $this->frmError['finalidadqx']=1;
                        }

                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                        $this->Modificar_Procedimiento_Solicitado($_REQUEST['hc_os_solicitud_idqx']);
                        return false;
                    }*/

                    $dbconn->BeginTrans();
                    $query= "SELECT acto_qx_id
                    FROM  hc_os_solicitudes_acto_qx  
                    WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al modificar";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                    $actoQX=$resulta->fields[0];
                    //,tipo_cirugia_id = '".$_REQUEST['cirugiaqx']."',
                    //ambito_cirugia_id = '".$_REQUEST['ambitoqx']."',
                    //finalidad_procedimiento_id = '".$_REQUEST['finalidadqx']."'
                    $query= "UPDATE hc_os_solicitudes_procedimientos
                    SET observacion = '".$observacion."'                    
                    WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al modificar";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                        $query= "UPDATE hc_os_solicitudes_acto_qx
                        SET observacion = '".$observacion."'                    
                        WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id." 
                        AND acto_qx_id='".$actoQX."'";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al modificar";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }else{
                          $query1="DELETE FROM hc_os_solicitudes_requerimientos_equipo_quirofano
                          WHERE acto_qx_id = '".$actoQX."'";
                          
                          $resulta=$dbconn->Execute($query1);
                          if ($dbconn->ErrorNo() != 0){
                                  $this->error = "Error al modificar";
                                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                  $dbconn->RollbackTrans();
                                  return false;
                          }else{
                                foreach ($_REQUEST['fijoqx'] as $index=>$equipo){
                                    $arreglo=explode(",",$equipo);
                                    $query2="INSERT INTO hc_os_solicitudes_requerimientos_equipo_quirofano
                                            (acto_qx_id, tipo_equipo_fijo_id,cantidad)
                                            VALUES  ('".$actoQX."', '".$arreglo[0]."','1');";
                                    $resulta2=$dbconn->Execute($query2);
                                    if ($dbconn->ErrorNo() != 0)
                                    {
                                    $this->error = "Error al modificar";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                    }
                                }
                                $query3="DELETE FROM hc_os_solicitudes_requerimientos_equipos_moviles
                                WHERE acto_qx_id = '".$actoQX."'";
                                $resulta=$dbconn->Execute($query3);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                        $dbconn->RollbackTrans();
                                        return false;
                                }
                                else
                                {
                                            foreach ($_REQUEST['movilqx'] as $index=>$equipo)
                                            {
                                                $arreglo=explode(",",$equipo);
                                                $query4="INSERT INTO hc_os_solicitudes_requerimientos_equipos_moviles
                                                (acto_qx_id, tipo_equipo_id , cantidad)
                                                VALUES  ('".$actoQX."', '".$arreglo[0]."','1');";
                                                $resulta2=$dbconn->Execute($query4);
                                                if ($dbconn->ErrorNo() != 0)
                                                    {
                                                        $this->error = "Error al modificar";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                                    }
                                            }
                                            $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                            WHERE hc_os_solicitud_id = '".$hc_os_solicitud_id."'";
                                            $resulta=$dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0)
                                            {
                                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                            if($_SESSION['DIAGNOSTICOSqx'])
                                            {
                                                $query4='';
                                                foreach ($_SESSION['DIAGNOSTICOSqx'] as $k=>$v)
                                                {
                                                        $query4.="INSERT INTO hc_os_solicitudes_diagnosticos
                                                        (hc_os_solicitud_id, diagnostico_id,tipo_diagnostico,sw_principal)
                                                        VALUES  (".$hc_os_solicitud_id.", '".$k."','1','0');";
                                                        
                
                                                }      
                                                  $resulta2=$dbconn->Execute($query4);
                                                  if ($dbconn->ErrorNo() != 0)
                                                  {
                                                      $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                      $dbconn->RollbackTrans();
                                                      return false;
                                                  }
                                            }
                                            /*$query5="DELETE FROM hc_os_solicitudes_procedimientos_apoyos
                                            WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
                                            $resulta=$dbconn->Execute($query5);
                                            if ($dbconn->ErrorNo() != 0)
                                            {
                                                $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                $dbconn->RollbackTrans();
                                                return false;
                                            }
                                            else
                                            {
                                                    if($_SESSION['APOYOSqx'])
                                                    {
                                                        foreach ($_SESSION['APOYOSqx'] as $k=>$v)
                                                        {
                                                                $query6="INSERT INTO hc_os_solicitudes_procedimientos_apoyos
                                                                (hc_os_solicitud_id, cargo)
                                                                VALUES  (".$hc_os_solicitud_id.", '".$k."');";

                                                                $resulta2=$dbconn->Execute($query6);
                                                                if ($dbconn->ErrorNo() != 0)
                                                                {
                                                                    $this->error = "Error al insertar en hc_os_solicitudes_procedimientos_apoyos";
                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    $dbconn->RollbackTrans();
                                                                    return false;
                                                                }
                                                        }
                                                    }
                                            }*/
                                }
                        }
                     }   
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                return true;
        }

    function Busqueda_Avanzada_Apoyos()
    {
                list($dbconn) = GetDBconn();
                $opcion      = ($_REQUEST['criterio1qx']);
                $cargo       = ($_REQUEST['cargoqx']);
                $descripcion =STRTOUPPER($_REQUEST['descripcionqx']);
                $filtroTipoCargo = '';
                $busqueda1 = '';
                $busqueda2 = '';

                if($opcion != '001' && !empty($opcion) && $opcion != '002')
                {
                    $filtroTipoCargo=" AND a.grupo_tipo_cargo = '$opcion'";
                }

                if ($cargo != '')
                {
                    $busqueda1 =" AND a.cargo LIKE '$cargo%'";
                }

                if ($descripcion != '')
                {
                    $busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
                }

                if($opcion == '002')
                {

                    $dpto = '';
                    $espe = '';
                    if ($this->departamento != '' )
                        {
                            $dpto = "AND a.departamento = '".$this->departamento."'";
                        }
                    if ($this->especialidad != '' )
                        {
                            $espe = "AND a.especialidad = '".$this->especialidad."'";
                        }
                    if ($dpto == '' AND $espe == '')
                        {
                            return false;
                        }
                }

            if(empty($_REQUEST['conteoqx']))
            {
                if($opcion == '002')
                    {
                        $query= "SELECT count(*)
                        FROM apoyod_solicitud_frecuencia a, cups b,
                        apoyod_tipos c
                        WHERE a.cargo = b.cargo
                        AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                        $dpto $espe $busqueda1 $busqueda2";
                    }
                else
                    {
                        $query = "SELECT count(*)
                        FROM cups a,apoyod_tipos b
                        WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                        $filtroTipoCargo    $busqueda1 $busqueda2";
                    }

                $resulta = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                list($this->conteo)=$resulta->fetchRow();
            }
            else
            {
                $this->conteo=$_REQUEST['conteoqx'];
            }
            if(!$_REQUEST['Ofqx'])
            {
                $Of='0';
            }
            else
            {
                $Of=$_REQUEST['Ofqx'];
                if($Of > $this->conteo)
                {
                    $Of=0;
                    $_REQUEST['Ofqx']=0;
                    $_REQUEST['paso1qx']=1;
                }
            }
                if($opcion == '002')
                    {
                        $query= "SELECT DISTINCT a.cargo, b.descripcion, c.apoyod_tipo_id,
                        c.descripcion as tipo
                        FROM apoyod_solicitud_frecuencia a, cups b,
                        apoyod_tipos c
                        WHERE a.cargo = b.cargo
                        AND b.grupo_tipo_cargo = c.apoyod_tipo_id
                        $dpto $espe $busqueda1 $busqueda2
                        order by c.descripcion, a.cargo
                        LIMIT ".$this->limit." OFFSET $Of;";
                    }
                else
                    {
                    $query = "
                            SELECT a.cargo, a.descripcion, b.apoyod_tipo_id,
                            b.descripcion as tipo
                            FROM cups a,apoyod_tipos b
                            WHERE a.grupo_tipo_cargo = b.apoyod_tipo_id
                            $filtroTipoCargo    $busqueda1 $busqueda2 order by b.apoyod_tipo_id, a.cargo
                            LIMIT ".$this->limit." OFFSET $Of;";
                    }
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

            while(!$resulta->EOF)
            {
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }

            if($this->conteo==='0')
                {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                return false;
                }
            $resulta->Close();
            return $var;
    }


    function Busqueda_Avanzada_DiagnosticosQx()
    {
            list($dbconn) = GetDBconn();
            $codigo       = STRTOUPPER ($_REQUEST['codigoqx']);
            $diagnostico  =STRTOUPPER($_REQUEST['diagnosticoqx']);

            $busqueda1 = '';
            $busqueda2 = '';

            if ($codigo != '')
            {
                $busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
            }

            if (($diagnostico != '') AND ($codigo != ''))
            {
                $busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
            }

            if (($diagnostico != '') AND ($codigo == ''))
            {
                $busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
            }

            if(empty($_REQUEST['conteoqx']))
            {
                $query = "SELECT count(*)
                            FROM diagnosticos
                            $busqueda1 $busqueda2";

                $resulta = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                list($this->conteo)=$resulta->fetchRow();
            }
            else
            {
                $this->conteo=$_REQUEST['conteoqx'];
            }
            if(!$_REQUEST['Ofqx'])
            {
                $Of='0';
            }
            else
            {
                $Of=$_REQUEST['Ofqx'];
                if($Of > $this->conteo)
                {
                    $Of=0;
                    $_REQUEST['Ofqx']=0;
                    $_REQUEST['paso1qx']=1;
                }
            }
                    $query = "
                            SELECT diagnostico_id, diagnostico_nombre
                            FROM diagnosticos
                            $busqueda1 $busqueda2 order by diagnostico_id
                            LIMIT ".$this->limit." OFFSET $Of;";
            $resulta = $dbconn->Execute($query);
            //$this->conteo=$resulta->RecordCount();
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $i=0;
            while(!$resulta->EOF)
            {
                $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }

            if($this->conteo==='0')
                {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
                                return false;
                }
            $resulta->Close();
            return $var;
    }

    function Insertar_Solicitud_Procedimiento($cargos)
    {
            /*if($_REQUEST['cirugiaqx'] == -1 OR $_REQUEST['ambitoqx'] == -1
                OR $_REQUEST['finalidadqx'] == -1)
                {
                    if($_REQUEST['cirugiaqx'] == -1)
                    {
                            $this->frmError['cirugia']=1;
                    }
                    if($_REQUEST['ambitoqx'] == -1)
                    {
                            $this->frmError['ambito']=1;
                    }
                    if($_REQUEST['finalidadqx'] == -1)
                    {
                            $this->frmError['finalidad']=1;
                    }

                    $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                    $this->Llenar_Procedimiento($_REQUEST['tipoqx'], $_REQUEST['cargosqx'], $_REQUEST['procedimientoqx']);
                    return false;
                }*/

            list($dbconn) = GetDBconn();
            $query = "select empresa_id from planes
            where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
            $resultado=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al insertar en hc_os_solicitudes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $empresa=$resultado->fields[0];
						$evento = "NULL"; 
						if(!empty($_SESSION['SolitudManual']['evento'])) $evento = $_SESSION['SolitudManual']['evento'];

            $dbconn->BeginTrans();              
            //realiza el id manual de la tabla
            $query="SELECT nextval('hc_os_solicitudes_datos_acto_qx_acto_qx_id_seq')";
            $result=$dbconn->Execute($query);
            $actoQX=$result->fields[0];            
            $query="INSERT INTO hc_os_solicitudes_datos_acto_qx(nivel_autorizacion,fecha_tentativa_cirugia,
                                acto_qx_id,evolucion_id)VALUES(NULL,NULL,'".$actoQX."',NULL)";
                             
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al insertar en hc_os_solicitudes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            
            $query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
            $result=$dbconn->Execute($query1);
            $hc_os_solicitud_id=$result->fields[0];
            //fin de la operacion
            $query2="INSERT INTO hc_os_solicitudes
                            (hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, paciente_id, tipo_id_paciente)
                            VALUES  (".$hc_os_solicitud_id.", NULL, '".$cargos."',
                                     'QX', ".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].",
                                     '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                                     '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."');";
                                  
            $resulta=$dbconn->Execute($query2);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al insertar en hc_os_solicitudes";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            else
                {
                    //, tipo_cirugia_id,
                    //ambito_cirugia_id, finalidad_procedimiento_id
                    //,'".$_REQUEST['cirugiaqx']."','".$_REQUEST['ambitoqx']."',
                    //        '".$_REQUEST['finalidadqx']."'
                            
                    $query3="INSERT INTO hc_os_solicitudes_procedimientos
                            (hc_os_solicitud_id, observacion)
                            VALUES  (".$hc_os_solicitud_id.", '".$_REQUEST['observacionqx']."');";
                    
                    $resulta1=$dbconn->Execute($query3);
                    if ($dbconn->ErrorNo() != 0)
                        {
                        $this->error = "Error al insertar en hc_os_solicitudes_procedimientos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                        }
                    else
                        {
                        if($_SESSION['DIAGNOSTICOSqx'])
                            {
                                $query4='';
                                foreach ($_SESSION['DIAGNOSTICOSqx'] as $k=>$v)
                                {
                                        $query4.="INSERT INTO hc_os_solicitudes_diagnosticos
                                        (hc_os_solicitud_id, diagnostico_id,tipo_diagnostico,sw_principal)
                                        VALUES  (".$hc_os_solicitud_id.", '".$k."','1','0');";
                                        

                                 }      
                                  $resulta2=$dbconn->Execute($query4);
                                  if ($dbconn->ErrorNo() != 0)
                                  {
                                      $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                      $dbconn->RollbackTrans();
                                      return false;
                                  }
                            }          
                                  
                                            
                            $query5="INSERT INTO hc_os_solicitudes_acto_qx  
                            (hc_os_solicitud_id, observacion,acto_qx_id)
                            VALUES  (".$hc_os_solicitud_id.",'".$_REQUEST['observacionqx']."', '".$actoQX."');";
                            
                            $resulta2=$dbconn->Execute($query5);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }else{
                              $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";                                              
                            }                            
                            /*if($_SESSION['APOYOSqx'])
                            {
                                foreach ($_SESSION['APOYOSqx'] as $k=>$v)
                                    {
                                        $query4="INSERT INTO hc_os_solicitudes_procedimientos_apoyos
                                        (hc_os_solicitud_id, cargo)
                                        VALUES  (".$hc_os_solicitud_id.", '".$k."');";

                                        $resulta2=$dbconn->Execute($query4);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->error = "Error al insertar en hc_os_solicitudes_procedimientos_apoyos";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }
                                        else
                                            {
                                            $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                                            }
                                        }
                                }*/

                        if($_REQUEST['fijoqx'])
                            {

                                foreach ($_REQUEST['fijoqx'] as $index=>$equipo)
                                    {
                                        $arreglo=explode(",",$equipo);
                                        $query4="INSERT INTO hc_os_solicitudes_requerimientos_equipo_quirofano
                                        (tipo_equipo_fijo_id, cantidad,acto_qx_id)
                                        VALUES  ('".$arreglo[0]."','1','".$actoQX."');";
                                        
                                        $resulta2=$dbconn->Execute($query4);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipo_quirofano";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }
                                        else
                                            {
                                            $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                                            }
                                        }
                                }
                        if($_REQUEST['movilqx'])
                            {

                                foreach ($_REQUEST['movilqx'] as $index=>$equipo)
                                    {
                                        $arreglo=explode(",",$equipo);
                                        $query4="INSERT INTO hc_os_solicitudes_requerimientos_equipos_moviles
                                        (tipo_equipo_id,cantidad,acto_qx_id)
                                        VALUES  ('".$arreglo[0]."','1','".$actoQX."');";
                                        
                                        $resulta2=$dbconn->Execute($query4);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->error = "Error al insertar en hc_os_solicitudes_requerimientos_equipos_moviles";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }
                                        else
                                            {
                                            $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                                            }
                                        }
                            }
														if(empty($_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']))
														{   $dpto='NULL';   }
														else
														{   $dpto="'".$_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']."'";   }	
				
                            $query3="INSERT INTO hc_os_solicitudes_manuales(
																						hc_os_solicitud_id,
																						fecha,servicio,
																						profesional,
																						prestador,
																						observaciones,
																						tipo_id_paciente,
																						paciente_id,
																						fecha_resgistro,
																						usuario_id,
																						empresa_id,
																						tipo_afiliado_id,
																						rango,
																						semanas_cotizadas,
																						departamento,
																						evento_soat)
                                     VALUES($hc_os_solicitud_id, 
                                     				'".$_SESSION['SOLICITUD']['DATOS']['FECHA']."',
                                            '".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."',
                                            '".$_SESSION['SOLICITUD']['DATOS']['MEDICO']."',
                                            '".$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']."',
                                            '".$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']."',
                                            '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."',
                                            '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                                            'now()',
                                            ".UserGetUID().",
                                            '$empresa',
                                            '".$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']."',
                                            '".$_SESSION['SOLICITUD']['PACIENTE']['RANGO']."',
                                            ".$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'].",
                                            $dpto,
                                            ".$evento.");";
                        $resulta1=$dbconn->Execute($query3);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al insertar en hc_os_solicitudes_apoyod";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $resulta1->Close();
                        $_SESSION['ARREGLO']['DATOS']['QX'][$hc_os_solicitud_id]=$hc_os_solicitud_id;
                        $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['hc_os_solicitud_id']=$hc_os_solicitud_id;
                        $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cargo']=$cargos;
                        $_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cantidad']=1;
                        $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                        }
                        }
                        $dbconn->CommitTrans();
                        unset ($_SESSION['APOYOSqx']);
                        $_REQUEST['cargosqx']= '';
                        return true;
    }

    function Consulta_Procedimientos_Solicitados($k)
    { 
            //left join qx_tipos_cirugia j on (b.tipo_cirugia_id = j.tipo_cirugia_id) 
            //qx_ambitos_cirugias k,
            //qx_finalidades_procedimientos l,
            //AND b.ambito_cirugia_id = k.ambito_cirugia_id
            //AND b.finalidad_procedimiento_id = l.finalidad_procedimiento_id
            GLOBAL $ADODB_FETCH_MODE;
            list($dbconnect) = GetDBconn();
            $query= "SELECT a.hc_os_solicitud_id, a.evolucion_id, a.cargo, x.observacion,
            c.diagnostico_id,d.tipo_equipo_fijo_id, e.tipo_equipo_id, g.diagnostico_nombre, h.descripcion as fijo,
            i.descripcion as movil, m.descripcion, n.descripcion as tipo,
            informacion_cargo('".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."',a.cargo,'')
            FROM hc_os_solicitudes a                                 
            left join hc_os_solicitudes_diagnosticos c on (a.hc_os_solicitud_id = c.hc_os_solicitud_id)  
            left join diagnosticos g on (c.diagnostico_id = g.diagnostico_id), 
            hc_os_solicitudes_acto_qx x 
            left join hc_os_solicitudes_requerimientos_equipo_quirofano d on (x.acto_qx_id = d.acto_qx_id) 
            left join qx_tipo_equipo_fijo h on (d.tipo_equipo_fijo_id = h.tipo_equipo_fijo_id)
            left join hc_os_solicitudes_requerimientos_equipos_moviles e on
            (x.acto_qx_id = e.acto_qx_id) 
            left join qx_tipo_equipo_movil i on (e.tipo_equipo_id = i.tipo_equipo_id), 
            cups m, grupos_tipos_cargo n
            WHERE a.hc_os_solicitud_id=$k 
            AND a.hc_os_solicitud_id=x.hc_os_solicitud_id              
            AND a.cargo  = m.cargo  
            AND m.grupo_tipo_cargo = n.grupo_tipo_cargo 
            ORDER BY a.hc_os_solicitud_id";
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconnect->Execute($query);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al ejecutar la Consulta";
                $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                return false;
            }
            else
            {
            if($result->RecordCount()<1)
            {
                return $vector;
            }

            $i=0;
                while ($arr=$result->FetchRow())
                {
                    $vector0[$arr['hc_os_solicitud_id']]=$arr;
                    $vector1[$arr['hc_os_solicitud_id']][$arr['diagnostico_id']]=$arr['diagnostico_nombre'];
                    $vector2[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_fijo_id']]=$arr['fijo'];
                    $vector3[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_id']]=$arr['movil'];
                }
            }
            $vector[]=$vector0;
            $vector[]=$vector1;
            $vector[]=$vector2;
            $vector[]=$vector3;
            $result->Close();
            return $vector;
    }

    function Apoyos_Del_Procedimiento($hc_os_solicitud_id)
    {
            list($dbconnect) = GetDBconn();
            $query= "SELECT a.cargo, b.descripcion FROM hc_os_solicitudes_procedimientos_apoyos as a, cups as b WHERE a.hc_os_solicitud_id = ".$hc_os_solicitud_id." AND a.cargo = b.cargo";
            $result = $dbconnect->Execute($query);
            if ($dbconnect->ErrorNo() != 0)
            {
                $this->error = "Error al buscar en la tabla hc_os_solicitudes_procedimientos_apoyos";
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

    function Eliminar_Procedimiento_Solicitado($hc_os_solicitud_id)
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT a.acto_qx_id,count(*) as total_solicitudes
        FROM 
              (SELECT acto_qx_id
              FROM  hc_os_solicitudes_acto_qx
              WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id.") as a,
              hc_os_solicitudes_acto_qx b
        WHERE a.acto_qx_id=b.acto_qx_id
        GROUP BY a.acto_qx_id";
        $resulta=$dbconn->Execute($query);
        $actoQX=$resulta->fields[0];
        $numeroSolicitudes=$resulta->fields[1];
        
        
        $query="DELETE FROM hc_os_solicitudes_diagnosticos
        WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
        $resulta=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->frmError["MensajeError"]="3NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
            $dbconn->RollbackTrans();
            return false;
        }else{
          $query="DELETE FROM hc_os_solicitudes_procedimientos
          WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
          $resulta=$dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0){
              $this->frmError["MensajeError"]="5NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
              $dbconn->RollbackTrans();
              return false;
          }else{
            $query2="DELETE FROM hc_os_solicitudes_manuales
            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
            $resulta1=$dbconn->Execute($query2);
            if($dbconn->ErrorNo() != 0){
              $this->frmError["MensajeError"]="7NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
              $dbconn->RollbackTrans();
              return false;
            }else{
              $query="DELETE FROM hc_os_solicitudes_acto_qx
              WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
              $resulta=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0){
                $this->frmError["MensajeError"]="6NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                $dbconn->RollbackTrans();
                return false;
              }else{
                $query="DELETE FROM hc_os_solicitudes
                WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                  $this->frmError["MensajeError"]="6NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                  $dbconn->RollbackTrans();
                  return false;
                }else{
                  if($numeroSolicitudes==1){
                    $query="DELETE FROM hc_os_solicitudes_requerimientos_equipos_moviles
                    WHERE acto_qx_id = '".$actoQX."'";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                        $this->frmError["MensajeError"]="1NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                      $query="DELETE FROM hc_os_solicitudes_requerimientos_equipo_quirofano
                      WHERE acto_qx_id = '".$actoQX."'";
                      $resulta=$dbconn->Execute($query);
                      if($dbconn->ErrorNo() != 0){
                        $this->frmError["MensajeError"]="2NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                        $dbconn->RollbackTrans();
                        return false;
                      }else{
                        $query="DELETE FROM hc_os_solicitudes_datos_acto_qx
                        WHERE acto_qx_id = ".$actoQX."";
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0){
                          $this->frmError["MensajeError"]="6NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                          $dbconn->RollbackTrans();
                          return false;
                        }  
                      }
                    }
                  }
                  $dbconn->CommitTrans();
                  unset($_SESSION['ARREGLO']['DATOS']['QX'][$hc_os_solicitud_id]);
                  unset($_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]);
                  $this->frmError["MensajeError"]="SOLICITUD ELIMINADA.";
                  return true;                
                }
              }
            }            
          }
        }
        return false;                                         
                    
    }


/*
        function Consulta_Procedimientos_Solicitados($k)
        {
                GLOBAL $ADODB_FETCH_MODE;
                list($dbconnect) = GetDBconn();
                $query= "SELECT a.hc_os_solicitud_id, a.cargo, b.observacion,
                                b.tipo_cirugia_id, b.ambito_cirugia_id, b.finalidad_procedimiento_id, c.diagnostico_id,
                                d.tipo_equipo_fijo_id, e.tipo_equipo_id, g.diagnostico_nombre, h.descripcion as fijo,
                                i.descripcion as movil, j.descripcion as cirugia, k.descripcion as ambito,
                                l.descripcion as finalidad, m.descripcion, n.descripcion as tipo
                                FROM hc_os_solicitudes_manuales as x,hc_os_solicitudes a,
                                hc_os_solicitudes_procedimientos b
                                left join qx_tipos_cirugia j on
                                (b.tipo_cirugia_id = j.tipo_cirugia_id)
                                left join hc_os_solicitudes_diagnosticos c on
                                (b.hc_os_solicitud_id = c.hc_os_solicitud_id)
                                left join diagnosticos g
                                on (c.diagnostico_id = g.diagnostico_id)
                                left join    hc_os_solicitudes_requerimientos_equipo_quirofano d on
                                (b.hc_os_solicitud_id = d.hc_os_solicitud_id)
                                left join qx_tipo_equipo_fijo h on
                                (d.tipo_equipo_fijo_id = h.tipo_equipo_fijo_id)
                                left join hc_os_solicitudes_requerimientos_equipos_moviles e on
                                (b.hc_os_solicitud_id = e.hc_os_solicitud_id)
                                left join qx_tipo_equipo_movil i on
                                (e.tipo_equipo_id = i.tipo_equipo_id),
                                qx_ambitos_cirugias k,
                                qx_finalidades_procedimientos l,
                                cups m, grupos_tipos_cargo n
                                WHERE x.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                and x.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
                                and a.sw_estado=1
                                and x.hc_os_solicitud_id=$k
                                and a.hc_os_solicitud_id=x.hc_os_solicitud_id
                                and a.hc_os_solicitud_id = b.hc_os_solicitud_id
                                AND b.ambito_cirugia_id = k.ambito_cirugia_id
                                AND b.finalidad_procedimiento_id = l.finalidad_procedimiento_id
                                AND a.cargo  = m.cargo
                                AND m.grupo_tipo_cargo = n.grupo_tipo_cargo ";
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconnect->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if ($dbconnect->ErrorNo() != 0)
                {
                    $this->error = "Error al ejecutar la Consulta";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
                }
                else
                {
                if($result->RecordCount()<1)
                {
                    return $vector;
                }

                        $arr=$result->FetchRow();

                    //while ($arr=$result->FetchRow())
                    //{
                        $vector0[$arr['hc_os_solicitud_id']]=$arr;
                        $vector1[$arr['hc_os_solicitud_id']][$arr['diagnostico_id']]=$arr['diagnostico_nombre'];
                        $vector2[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_fijo_id']]=$arr['fijo'];
                        $vector3[$arr['hc_os_solicitud_id']][$arr['tipo_equipo_id']]=$arr['movil'];
                    //}
                }
                $vector[]=$vector0;
                $vector[]=$vector1;
                $vector[]=$vector2;
                $vector[]=$vector3;
                return $vector;
        }
*/

//----------------------ACCION CANCELAR--------------------------

    /**
    *
    */
    function Cancelar()
    {
                list($dbconn) = GetDBconn();
                if(!empty($_SESSION['ARREGLO']['DATOS']['APOYOS']))
                {
                        foreach($_SESSION['ARREGLO']['DATOS']['APOYOS'] as $k => $v)
                        {
                                $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                            WHERE hc_os_solicitud_id = $k";
                                $resulta=$dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0){
                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                                else
                                {
                                            $query1="DELETE FROM hc_os_solicitudes_apoyod
                                            WHERE hc_os_solicitud_id = $k";
                                            $resulta1=$dbconn->Execute($query1);
                                            if ($dbconn->ErrorNo() != 0)
                                            {
                                                $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                $dbconn->RollbackTrans();
                                                return false;
                                            }
                                            else
                                            {
                                                    $query2="DELETE FROM hc_os_solicitudes_manuales
                                                    WHERE hc_os_solicitud_id = $k";
                                                    $resulta1=$dbconn->Execute($query2);
                                                    if ($dbconn->ErrorNo() != 0)
                                                    {
                                                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                                    }

                                                    $query2="DELETE FROM hc_os_solicitudes
                                                    WHERE hc_os_solicitud_id = $k";
                                                    $resulta1=$dbconn->Execute($query2);
                                                    if ($dbconn->ErrorNo() != 0)
                                                    {
                                                        $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                        $dbconn->RollbackTrans();
                                                        return false;
                                                    }
                                            }
                                }
                    }
            }
            unset($_SESSION['ARREGLO']['DATOS']['APOYOS']);

            if(!empty($_SESSION['ARREGLO']['DATOS']['INTER']))
            {
                    foreach($_SESSION['ARREGLO']['DATOS']['INTER'] as $hc_os_solicitud_id => $v)
                    {
                            $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                            $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0){
                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                    $dbconn->RollbackTrans();
                                    return false;
                            }
                            else
                            {
                                        $query1="DELETE FROM hc_os_solicitudes_interconsultas
                                        WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                        $resulta1=$dbconn->Execute($query1);
                                        if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }
                                        else
                                        {
                                                $query2="DELETE FROM hc_os_solicitudes_manuales
                                                WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                                $resulta1=$dbconn->Execute($query2);
                                                if ($dbconn->ErrorNo() != 0)
                                                {
                                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                                }

                                                $query2="DELETE FROM hc_os_solicitudes_citas
                                                WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                                $resulta1=$dbconn->Execute($query2);
                                                if ($dbconn->ErrorNo() != 0)
                                                {
                                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                                }

                                                $query2="DELETE FROM hc_os_solicitudes
                                                WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                                $resulta1=$dbconn->Execute($query2);
                                                if ($dbconn->ErrorNo() != 0)
                                                {
                                                    $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                                }
                                        }
                            }
                    }
            }
            unset($_SESSION['ARREGLO']['DATOS']['INTER']);

            if(!empty($_SESSION['ARREGLO']['DATOS']['QX']))
            {
                    foreach($_SESSION['ARREGLO']['DATOS']['QX'] as $hc_os_solicitud_id => $v)
                    {
                            $query="DELETE FROM hc_os_solicitudes_requerimientos_equipos_moviles
                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                            $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->frmError["MensajeError"]="NO1 SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                $dbconn->RollbackTrans();
                                return false;
                            }
                            else
                            {
                                    $query="DELETE FROM hc_os_solicitudes_requerimientos_equipo_quirofano
                                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                    $resulta=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0)
                                        {
                                            $this->frmError["MensajeError"]="NO2 SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                            $dbconn->RollbackTrans();
                                            return false;
                                        }
                                    else
                                        {
                                            $query="DELETE FROM hc_os_solicitudes_diagnosticos
                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                            $resulta=$dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0)
                                                {
                                                    $this->frmError["MensajeError"]="NO3 SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                                }
                                            else
                                                {
                                                    $query="DELETE FROM hc_os_solicitudes_procedimientos
                                                    WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                                    $resulta=$dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0)
                                                        {
                                                            $this->frmError["MensajeError"]="NO4 SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                            $dbconn->RollbackTrans();
                                                            return false;
                                                        }
                                                    else
                                                        {
                                                            $query2="DELETE FROM hc_os_solicitudes_manuales
                                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                                            $resulta1=$dbconn->Execute($query2);
                                                            if ($dbconn->ErrorNo() != 0)
                                                            {
                                                                $this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                            }

                                                            $query="DELETE FROM hc_os_solicitudes
                                                            WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
                                                            $resulta=$dbconn->Execute($query);
                                                            if ($dbconn->ErrorNo() != 0)
                                                                {
                                                                    $this->frmError["MensajeError"]="NO5 SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
                                                                    $dbconn->RollbackTrans();
                                                                    return false;
                                                                }

                                                        }
                                                }
                                        }
                                }
                    }
            }
            unset($_SESSION['ARREGLO']);
            $dbconn->CommitTrans();
            $this->frmError["MensajeError"]="El Proceso fue Cancelado.";
            $this->Menu();
            return true;
    }

//---------------------------------AUTORIZACIONES--------------------------------------

        /**
        *
        */
        function SolicitarAutorizacion()
        {
                //4 id, 0 cargo, 1 tarifario, 3 servicio, 2 descripcion
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']='NULL';
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'][4][0][1][$_SESSION['SOLICITUD']['DATOS']['SERVICIO']]=2;

                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CENTROAUTORIZACION';
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
                $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
                $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
                $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='CentroAutorizacionSolicitud';
                $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
                $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

                $this->ReturnMetodoExterno('app','CentroAutorizacion','user','ValidarCentroAutorizacion');
                return true;
        }

        /**
        *
        */
    function RetornoAutorizacion()
    {
    //$dbconn->debug=true;
								$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo_afiliado_id'];
								$_SESSION['SOLICITUD']['PACIENTE']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['rango'];
								$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['semanas'];
								$Mensaje=$_SESSION['AUTORIZACIONES']['RETORNO']['Mensaje'];
								$_SESSION['SOLICITUD']['ext']=$_SESSION['AUTORIZACIONES']['RETORNO']['ext'];
								$_SESSION['SOLICITUD']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];
								$_SESSION['SOLICITUD']['NumAutorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['Autorizacion'];
								$_SESSION['SOLICITUD']['observacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['ObservacionesOS'];
            //print_r($_SESSION);
								list($dbconn) = GetDBconn();
print_r($_SESSION['AUTORIZACIONES']['RETORNO']['TRAMITE']);
								if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['TRAMITE']))
								{
														$Mensaje = 'La toma de requerimientos se realizo.';
														$accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaFinSolicitud');
														if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
																		return false;
														}
														return true;
								} 
								
								//si no fue autorizada
								if(!empty($_SESSION['AUTORIZACIONES']['NOAUTO']))
								{  $noauto=", sw_no_autorizado='1' ";  }    
								unset($_SESSION['AUTORIZACIONES']);

                if(empty($_SESSION['SOLICITUD']['Autorizacion'])
                                        AND empty($_SESSION['SOLICITUD']['NumAutorizacion']))
                {    
                            if(empty($_SESSION['SOLICITUD']['NumAutorizacion']))
                            {   $Mensaje = 'No se pudo realizar la Autorización para la Orden.';   }
                            $accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaFinSolicitud');
                            if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                            return false;
                            }
                            return true;
                }
                //$dbconn->debug=true;
                 $query = "    (select a.hc_os_solicitud_id
																from hc_os_autorizaciones as a
																where (a.autorizacion_int=".$_SESSION['SOLICITUD']['Autorizacion']." OR
																a.autorizacion_ext=".$_SESSION['SOLICITUD']['Autorizacion']."))";
                $result = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error select ";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                while(!$result->EOF)
                {
                //$dbconn->debug=true;
                         $query = "UPDATE hc_os_solicitudes SET sw_estado=0 $noauto
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

                if(!empty($_SESSION['SOLICITUD']['Autorizacion'])
                AND empty($_SESSION['SOLICITUD']['NumAutorizacion']))
                {
                            $Mensaje = 'No se Autorizo la Orden.';
                            $accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaFinSolicitud');
                            if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                            return false;
                            }
                            return true;
                }
								//$dbconn->debug=true;
								$query = "(
															SELECT a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
																	a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
																	h.descripcion, r.descripcion as descar, q.servicio,a.evento as evento_soat
															FROM
																	(
																			SELECT b.cargo as cargos, e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, NULL as servicio, e.departamento, b.cargo as cargo_base,soat.evento
																			FROM
																					hc_os_autorizaciones as a,
																					hc_os_solicitudes as b,
																					hc_evoluciones as e
																					left join ingresos_soat soat on(e.ingreso=soat.ingreso),
																					tarifarios_equivalencias as n
																			WHERE
																					a.autorizacion_int = ".$_SESSION['SOLICITUD']['Autorizacion']."
																					AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
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
																	AND z.grupo_tarifario_id = h.grupo_tarifario_id
																	AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
																	AND h.tarifario_id = z.tarifario_id
													)
													UNION
													(
															SELECT  a.fecha, a.hc_os_solicitud_id, a.cantidad, a.cargos,
																			a.plan_id, a.os_tipo_solicitud_id, a.tarifario_id, a.cargo,
																			h.descripcion, r.descripcion as descar, a.servicio,a.evento_soat
															FROM
																	(
																			 SELECT b.cargo as cargos,e.fecha, b.hc_os_solicitud_id, b.cantidad, b.plan_id, b.os_tipo_solicitud_id, n.tarifario_id, n.cargo, e.servicio, e.departamento, b.cargo as cargo_base,e.evento_soat
																			FROM
																					hc_os_autorizaciones as a,
																					hc_os_solicitudes as b,
																					hc_os_solicitudes_manuales as e,
																					tarifarios_equivalencias as n
																			WHERE
																					a.autorizacion_int = ".$_SESSION['SOLICITUD']['Autorizacion']."
																					AND b.hc_os_solicitud_id = a.hc_os_solicitud_id
																					AND e.hc_os_solicitud_id = b.hc_os_solicitud_id
																					AND n.cargo_base = b.cargo
																	) AS a LEFT JOIN departamentos as q ON (q.departamento=a.departamento),
																	cups as r,
																	tarifarios_detalle as h,
																	plan_tarifario as z
															WHERE
																	r.cargo = a.cargo_base
																	AND (h.tarifario_id = a.tarifario_id AND h.cargo = a.cargo)
																	AND z.grupo_tarifario_id = h.grupo_tarifario_id
																	AND z.subgrupo_tarifario_id = h.subgrupo_tarifario_id
																	AND h.tarifario_id = z.tarifario_id
													)";

             /*  $query = "(select e.fecha,a.hc_os_solicitud_id, b.cantidad,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
                                                                n.cargo,n.tarifario_id,h.descripcion, r.descripcion as descar, e.servicio
                                                                from hc_os_autorizaciones as a,hc_os_solicitudes as b left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
                                                                left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
                                                                hc_os_solicitudes_manuales as e, cups as r
                                                                where (a.autorizacion_int=".$_SESSION['SOLICITUD']['Autorizacion']." OR
                                                                a.autorizacion_ext=".$_SESSION['SOLICITUD']['Autorizacion'].") and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                                                                and a.hc_os_solicitud_id=e.hc_os_solicitud_id
                                                                and r.cargo=b.cargo
                                                            )";*/
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
                                /*$_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']=$var;
                                if(!empty($_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']))
                                {
                                        $this->FormaFinSolicitud();
                                        return true;
                                }
                                else
                                {
                                        $this->Menu();
                                        return true;                                
                                }*/
    }
        /*function RetornoAutorizacion()
        {
          $_SESSION['SOLICITUD']['Autorizacion']=$_SESSION['AUTORIZACIONES']['RETORNO']['NumAutorizacion'];

          if(empty($_SESSION['SOLICITUD']['Autorizacion']))
                    {
                              $Mensaje = 'No se pudo realizar la Autorización para la Solicitud Manual.';
                                $accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','');
                                if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                                return false;
                                }
                                return true;
                    }

          list($dbconn) = GetDBconn();
                    $query = "    (select a.hc_os_solicitud_id
                                                    from hc_os_autorizaciones as a
                                                    where (a.autorizacion_int=".$_SESSION['SOLICITUD']['Autorizacion']." OR
                                                    a.autorizacion_ext=".$_SESSION['SOLICITUD']['Autorizacion']."))
                                                    union
                                                    (select a.hc_os_solicitud_id
                                                    from hc_os_autorizaciones as a
                                                    where (a.autorizacion_int=".$_SESSION['SOLICITUD']['Autorizacion']." OR
                                                    a.autorizacion_ext=".$_SESSION['SOLICITUD']['Autorizacion']."))";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error select ";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    return false;
                    }
                    while(!$result->EOF)
                    {
                                    $query = "UPDATE hc_os_solicitudes SET
                                                                                                                                    sw_estado=0
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
                    $result->Close();

                    $query = "(    select e.fecha,a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
                                            n.cargo,n.tarifario_id,h.descripcion, r.descripcion as descar
                                            from hc_os_autorizaciones as a,hc_os_solicitudes as b left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
                                            left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
                                            hc_os_solicitudes_manuales as e, cups as r
                                            where (a.autorizacion_int=".$_SESSION['CENTROAUTORIZACION']['Autorizacion']." OR
                                            a.autorizacion_ext=".$_SESSION['CENTROAUTORIZACION']['Autorizacion'].") and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                                            and a.hc_os_solicitud_id=e.hc_os_solicitud_id
                                            and r.cargo=b.cargo
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

                    $this->FormaListadoCargos($var);
                    return true;
        }*/



    /**
    *
    */
    function PedirAutorizacion()
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
                    $this->FormaFinSolicitud();
                    return true;
            }

            unset($_SESSION['AUTORIZACIONES']);
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Auto'))
                {
                        $arr=explode(',',$v);
                        //4 solicitu_id, 0 cargo, 1 tarifario, 3 servicio, 2 descr
                        if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']))
                        {   $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']=$_REQUEST['ingreso'];   }
                        $_SESSION['CENTROAUTORIZACION']['SERVICIO']=$arr[3];
                        $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARREGLO'][$arr[4]][$arr[0]][$arr[1]][$arr[3]]=$arr[2];
                }
            }

            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']='CENTROAUTORIZACION';
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
            $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']=array();
            $_SESSION['AUTORIZACIONES']['RETORNO']['contenedor']='app';
            $_SESSION['AUTORIZACIONES']['RETORNO']['modulo']='CentroAutorizacionSolicitud';
            $_SESSION['AUTORIZACIONES']['RETORNO']['tipo']='user';
            $_SESSION['AUTORIZACIONES']['RETORNO']['metodo']='RetornoAutorizacion';

            $this->ReturnMetodoExterno('app','CentroAutorizacion','user','ValidarCentroAutorizacion');
            return true;
    }


    /**
    *
    */
    function ComboProveedor($Cargo)
    {    // and a.empresa_id='".$_SESSION['CENTROAUTORIZACION']['EMPRESA']."' 
                            $x=" and a.empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'";  
              list($dbconn) = GetDBconn();
              /*$query = "SELECT a.tipo_id_tercero, a.tercero_id, a.cargo,  c.plan_proveedor_id, c.empresa_id,
                        c.plan_descripcion
                        FROM terceros_proveedores_cargos as a, planes_proveedores as c,terceros_proveedores_servicios_salud as b
                        WHERE b.empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'
                        and b.tipo_id_tercero=a.tipo_id_tercero and b.tercero_id=a.tercero_id 
												and b.estado='1'
												and a.empresa_id=b.empresa_id
                        and a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id
												and a.sw_estado='1'
												and a.cargo='$Cargo'";*/
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
							and 	a.empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'
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
				
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
    function ComboDepartamento($Cargo)
    {//OJO REVISAR AQUI
					$x=" and b.empresa_id='".$_SESSION['SOLICITUD']['EMPRESA']."'";  
																
					IncludeLib('malla_validadora');
		
					list($dbconn) = GetDBconn();	
					
					$dat='';
					$dat = DatosSolicitud($solicitud);
					$filtro=ModuloGetVar('app','CentroAutorizacion','filtro_os');
					
					//es una solicitud manual y no eligieron el depto y se hace igual como si fuera por empresa
					if(empty($dat) OR $filtro=='empresa')
					{
							$query = "select a.departamento, a.cargo, b.descripcion
												from departamentos_cargos as a, departamentos as b
												where a.cargo='$Cargo'
												and b.departamento=a.departamento $x";															
					}					
					elseif($filtro=='centro')
					{
							$query = "select a.departamento, a.cargo, b.descripcion
												from departamentos_cargos as a, departamentos as b
												where a.cargo='$Cargo' 
												and b.departamento=a.departamento $x
												and b.centro_utilidad='".$dat[centro_utilidad]."'";			
					}
					elseif($filtro=='unidad')
					{
							$query = "select a.departamento, a.cargo, b.descripcion
												from departamentos_cargos as a, departamentos as b
												where a.cargo='$Cargo' 
												and b.departamento=a.departamento $x
												and b.unidad_funcional='".$dat[unidad_funcional]."'";	
					}						
					elseif($filtro=='departamento')
					{
							$query = "select a.departamento, a.cargo, b.descripcion
												from departamentos_cargos as a, departamentos as b
												where a.cargo='$Cargo' 
												and b.departamento=a.departamento $x
												and b.departamento='".$dat[departamento]."'";					
					}			
																
              /*  list($dbconn) = GetDBconn();
               $query = "select a.departamento, a.cargo, b.descripcion
                                                    from departamentos_cargos as a, departamentos as b
                                                    where a.cargo='$Cargo'
                                                    and b.departamento=a.departamento
                                                    $x";*/
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Guardar en la Base de Datos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
        function BuscarEmpresa($plan)
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT empresa_id
                                            FROM planes
                                            WHERE plan_id=$plan";
            $resulta=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $resulta->Close();
            return $resulta->fields[0];        
        }

//----------------------------ORDENES-----------------------------------------------------------

    /**
    *
    */
            function CrearOrdenServicio()
    {
                        if(!empty($_REQUEST['cancelar']))
                        { 
                                $auto=$_SESSION['SOLICITUD']['Autorizacion']; 

                                list($dbconn) = GetDBconn();
                                //$dbconn->debug=true;
                                $dbconn->BeginTrans();
                                $query = "select a.hc_os_solicitud_id from hc_os_autorizaciones as a
                                                            where (a.autorizacion_int=".$auto." OR
                                                            a.autorizacion_ext=".$auto.")";
                                $result = $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error select ";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                                }
																$hc_os_solicitud_id=$result->fields[0];
																
                                while(!$result->EOF)
                                {
                                                $query = "UPDATE hc_os_solicitudes SET
                                                                                                sw_estado=1
                                                                                        WHERE hc_os_solicitud_id=".$result->fields[0]."";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error UPDATE  hc_os_solicitudes ";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }
                                                $query = "DELETE FROM hc_os_autorizaciones
                                                                    WHERE hc_os_solicitud_id=".$result->fields[0]."";
                                                $dbconn->Execute($query);
                                                if ($dbconn->ErrorNo() != 0) {
                                                                $this->error = "Error DELETE FROM hc_os_licitudes ";
                                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                $dbconn->RollbackTrans();
                                                                return false;
                                                }
                                                if($result->RecordCount()>0)
                                                { $result->MoveNext();  }
                                }
                                $result->Close();
                                $dbconn->CommitTrans();
                                $this->FormaFinSolicitud();
                                return true;
                        }

            //va hacer la transcripcion
                        if(!empty($_REQUEST['Transcripcion']))
            { 
                            $this->CrearTranscripcion();
                    return true;
            }

                        if(!empty($_REQUEST['Trans']))
            {
                    $this->frmError["MensajeError"]="ERROR: Debe Hacer Primero la Transcripción.";
                    $this->FormaListadoCargos($_SESSION['SOLICITUD']['LISTADO']);
                    return true;
            }


            $f=0;
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {
                                            if($v==-1)
                                            {    $f=1; }
                   }
            }
            if($f==1)
            {
                    $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Departamento o Proveedor del Cargo.";
                    $this->FormaListadoCargos($_SESSION['SOLICITUD']['LISTADO']);
                    return true;
           }


            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {
                                            if($v!=-1)
                                            {        //0 hc_os_solicitud_id
                                                    $arr=explode(',',$v);
                                                    $d=0;
                                                    foreach($_REQUEST as $ke => $va)
                                                    {
                                                            if(substr_count($ke,'Op'))
                                                            {        // 0 solicitud_id
                                                                    $var=explode(',',$va);
                                                                    if($var[0]==$arr[0])
                                                                    {  $d=1;  }
                                                            }
                                                    }
                                                    if($d==0)
                                                    {
                                                                    $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Cargo.";
                                                                    $this->FormaListadoCargos($_SESSION['SOLICITUD']['LISTADO']);
                                                                    return true;
                                                    }
                                            }
                }
            }

                        list($dbconn) = GetDBconn();
                        //$dbconn->debug=true;
                        $query = "select empresa_id from planes
                        where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
                        $resultado=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al insertar en hc_os_solicitudes";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        $empresa=$resultado->fields[0];

                        $auto=$_SESSION['SOLICITUD']['Autorizacion'];
                        $plan=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
                        $rango=$_SESSION['SOLICITUD']['PACIENTE']['RANGO'];
                        $afiliado=$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO'];
                        $semana=$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'];
                        $paciente=$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'];
                        $tipo=$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'];
                        $msg=$_SESSION['SOLICITUD']['observacion'];
                        $servicio=$_SESSION['SOLICITUD']['DATOS']['SERVICIO'];
                        if(empty($_SESSION['SOLICITUD']['ext']))
                        {  $ext='NULL';  }
                        else
                        {  $ext=$auto;  }

            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
								{     //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
											//3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
											$arr=explode(',',$v);
											$arreglo[$arr[1].'-'.$arr[9]][]=$v;
											$eventos_soat[$arr[1].'-'.$arr[9]]=$arr[9];
								}
            }

                        foreach($arreglo as $key => $value)
                        {
												 	
				
																
																if(is_numeric($eventos_soat[$key]))
																{
																	$evento_soat=$eventos_soat[$key];
																}
																else																
																{
																	$evento_soat = 'NULL';
																}
																
																
																
                                //AQUI INSERTO LA ORDEN
                                //$dbconn->debug=true;
                                $query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
                                $result=$dbconn->Execute($query);
                                $orden=$result->fields[0];
																//evento soat se inserto para verificar si el paciente viene por algun evento
                                $query = "INSERT INTO os_ordenes_servicios
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
                                VALUES($orden,".$auto.",$ext,".$plan.",'".$afiliado."',
                                '".$rango."',".$semana.",'".$servicio."','".$tipo."','".$paciente."',".UserGetUID().",'now()','".$msg."',$evento_soat)";
																
                                $dbconn->BeginTrans();
                                $dbconn->Execute($query);
                                if ($dbconn->ErrorNo() != 0) {
                                                $this->error = "Error INSERT INTO os_ordenes_servicios";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                $dbconn->RollbackTrans();
                                                return false;
                                }
                                $ordenes[]=$orden;
                                //DATOS PARA OS_MAESTRO
                                for($i=0; $i<sizeof($value); $i++)
                                {
                                        //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                                        //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                                        $vect=explode(',',$value[$i]);
                                        foreach($_REQUEST as $k => $v)
                                        {
                                                if(substr_count($k,'Combo'))
                                                {        //0 hc_os_solicitud_id 2 tipo_id_tercero o dpto 1 tercero_id o departo si es departamento(interna)
                                                        //3 tarifario 4 cargo 5 cargocups 6 fecha 7 plan_proveedor 8 cantidad
                                                        $arr=explode(',',$v);
                                                        if($vect[0]==$arr[0])
                                                        {
                                                                for($j=0; $j<$arr[8]; $j++)
                                                                {
                                            $query = "select * from os_tipos_periodos_planes
                                                        where plan_id=".$plan."
                                                        and cargo='$arr[5]'";
                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error os_tipos_periodos_planes";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            return false;
                                    }
                                    if(!$result->EOF)
                                    {
                                            $var=$result->GetRowAssoc($ToUpper = false);
                                            $Fecha=$this->FechaStamp($arr[6]);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($arr[6]);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                            if($fechaAct < date("Y-m-d H:i:s"))
                                            {  $fechaAct=date("Y-m-d H:i:s");  }
                                            $Fecha=$this->FechaStamp($fechaAct);
                                            $infoCadena = explode ('/',$Fecha);
                                            $intervalo=$this->HoraStamp($fechaAct);
                                            $infoCadena1 = explode (':', $intervalo);
                                            $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
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
                                                                where cargo='$arr[5]'";
                                            $result=$dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error os_tipos_periodos_tramites";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                            }
                                            if(!$result->EOF)
                                            {
                                                        $var=$result->GetRowAssoc($ToUpper = false);
                                                        $Fecha=$this->FechaStamp($arr[6]);
                                                        $infoCadena = explode ('/',$Fecha);
                                                        $intervalo=$this->HoraStamp($arr[6]);
                                                        $infoCadena1 = explode (':', $intervalo);
                                                        $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                                        if($fechaAct < date("Y-m-d H:i:s"))
                                                        {  $fechaAct=date("Y-m-d H:i:s");  }
                                                        $Fecha=$this->FechaStamp($fechaAct);
                                                        $infoCadena = explode ('/',$Fecha);
                                                        $intervalo=$this->HoraStamp($fechaAct);
                                                        $infoCadena1 = explode (':', $intervalo);
                                                        $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
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
                                                        $Fecha=$this->FechaStamp($arr[6]);
                                                        $infoCadena = explode ('/',$Fecha);
                                                        $intervalo=$this->HoraStamp($arr[6]);
                                                        $infoCadena1 = explode (':', $intervalo);
                                                        $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
                                                        if($fechaAct < date("Y-m-d H:i:s"))
                                                        {  $fechaAct=date("Y-m-d H:i:s");  }
                                                        $Fecha=$this->FechaStamp($fechaAct);
                                                        $infoCadena = explode ('/',$Fecha);
                                                        $intervalo=$this->HoraStamp($fechaAct);
                                                        $infoCadena1 = explode (':', $intervalo);
                                                        $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
                                                        //fecha refrendar
                                                        $Fecha=$this->FechaStamp($venc);
                                                        $infoCadena = explode ('/',$Fecha);
                                                        $intervalo=$this->HoraStamp($venc);
                                                        $infoCadena1 = explode (':', $intervalo);
                                                        $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                            }
                                    }//fin else

                                    $query="SELECT nextval('os_maestro_numero_orden_id_seq')";
                                    $result=$dbconn->Execute($query);
                                    $numorden=$result->fields[0];

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
                                    VALUES($numorden,$orden,1,'$venc',$arr[0],'$fechaAct',1,'$arr[5]','$refrendar')";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                            $this->error = "Error INSERT INTO os_maestro";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $dbconn->RollbackTrans();
                                            return false;
                                    }
                                    else
                                    {
                                                                                        foreach($_REQUEST as $ke => $va)
                                                                                        {
                                                                                                if(substr_count($ke,'Op'))
                                                                                                {        // 0 solicitud_id 1 cargo 2 tarifario
                                                                                                        $var=explode(',',$va);
                                                                                                        if($var[0]==$arr[0])
                                                                                                        {
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
                                            if($arr[2]=='dpto')
                                            {
                                                        $query = "INSERT INTO os_internas
                                                                                            (numero_orden_id,
                                                                                            cargo,
                                                                                            departamento)
                                                                            VALUES($numorden,'$arr[5]','$arr[1]')";
                                            }
                                            else
                                            {
                                                            $query = "INSERT INTO os_externas
                                                                                            (numero_orden_id,
                                                                                            empresa_id,
                                                                                            tipo_id_tercero,
                                                                                            tercero_id,
                                                                                            cargo,
                                                                                            plan_proveedor_id)
                                                                            VALUES($numorden,'".$empresa."','$arr[2]','$arr[1]','$arr[5]',$arr[7])";
                                            }
                                            $dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error INTO os_externas o  os_internas";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                            }
                                    }//else
                                                                }//fin for cantidad
                                                        }
                                                }
                                        }//fin foreach
                                }//fin for
                        }                        

                    		//ARRANQUE
												/*echo     '1'.$query = "    (select e.fecha,a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
																																									n.cargo,n.tarifario_id,h.descripcion
																																									from hc_os_autorizaciones as a,hc_os_solicitudes as b left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
																																									left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id), hc_evoluciones as e
																																									where (a.autorizacion_int=".$auto." OR
																																									a.autorizacion_ext=".$auto.") and a.hc_os_solicitud_id=b.hc_os_solicitud_id and e.evolucion_id=b.evolucion_id)
																																									union
																																									(select e.fecha,a.hc_os_solicitud_id,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
																																									n.cargo,n.tarifario_id,h.descripcion
																																									from hc_os_autorizaciones as a,hc_os_solicitudes as b left join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
																																									left join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id), hc_os_solicitudes_manuales as e
																																									where (a.autorizacion_int=".$auto." OR
																																									a.autorizacion_ext=".$auto.") and a.hc_os_solicitud_id=b.hc_os_solicitud_id and a.hc_os_solicitud_id=e.hc_os_solicitud_id)";
												exit;    */

												$query = "    SELECT a.*,
																n.cargo,
																n.tarifario_id,
																h.descripcion
												
												FROM
												(
													(
														SELECT
														e.fecha, 
														a.hc_os_solicitud_id,														
														b.cargo as cargos,
														b.plan_id,
														b.os_tipo_solicitud_id
													
														FROM 
														hc_os_autorizaciones as a,
														hc_os_solicitudes as b, 
														hc_evoluciones as e 
														
														WHERE (a.autorizacion_int = $auto OR a.autorizacion_ext=$auto) 
														AND a.hc_os_solicitud_id = b.hc_os_solicitud_id 
														AND e.evolucion_id = b.evolucion_id
													
													)
													UNION
													(
														SELECT 
														e.fecha,
														a.hc_os_solicitud_id,														
														b.cargo as cargos,
														b.plan_id,
														b.os_tipo_solicitud_id
													
														FROM 
														hc_os_autorizaciones as a,
														hc_os_solicitudes as b,
														hc_os_solicitudes_manuales as e 
													
														WHERE (a.autorizacion_int = $auto OR a.autorizacion_ext=$auto) 
														AND a.hc_os_solicitud_id = b.hc_os_solicitud_id 
														AND a.hc_os_solicitud_id = e.hc_os_solicitud_id
													)
												) as a LEFT JOIN tarifarios_equivalencias as n on(n.cargo_base = a.cargos) 
															LEFT JOIN tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id)";

                        $result = $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error select ";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
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

                        //SOLICITUDES
                        $query = "    select t.nivel_autorizador_id,NULL, a.cantidad,a.hc_os_solicitud_id,a.cargo as cargos,
                                                p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente,
                                                b.paciente_id,k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                                                a.plan_id,f.plan_descripcion,
                                                a.os_tipo_solicitud_id,a.sw_estado,
                                                b.servicio,p.descripcion,
                                                m.descripcion as desserv,g.descripcion as desos,
                                                b.fecha,NULL,
                                                b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel,
                                                t.empresa_id
                                                from    userpermisos_centro_autorizacion as t
                                                left join    hc_os_solicitudes as a on(t.usuario_id=".UserGetUID()."
                                                and a.plan_id=t.plan_id or t.sw_todos_planes=1),
                                                planes as f, os_tipos_solicitudes as g,
                                                pacientes as k, servicios as m,
                                                cups as p,
                                                hc_os_solicitudes_manuales as b
                                                where
                                                p.cargo=a.cargo and a.sw_estado=1
                                                and a.plan_id=f.plan_id
                                                and date(b.fecha_resgistro) = date(now())
                                                and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                                                and a.evolucion_id is null
                                                and t.usuario_id=".UserGetUID()."
                                                and b.servicio=m.servicio
                                                and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                                                and b.tipo_id_paciente=k.tipo_id_paciente
                                                and b.paciente_id=k.paciente_id
                                                and b.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                                and b.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
                                                order by t.plan_id, m.servicio, b.tipo_id_paciente,
                                                b.paciente_id, a.os_tipo_solicitud_id";
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
                                                        $vars1[]=$result->GetRowAssoc($ToUpper = false);
                                                        $result->MoveNext();
                                        }
                        }
                        $result->Close();
                        unset($_SESSION['SOLICITUD']['ARREGLO']['DETALLE2']);
                        $_SESSION['SOLICITUD']['ARREGLO']['DETALLE2']=$vars1;

                        //LAS ORDENES
                         $query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                                                        case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
                                                        e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
                                                        g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
                                                        p.plan_descripcion,j.sw_estado
                                                        from os_ordenes_servicios as a
                                                        left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                                                        left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                                                        left join departamentos as l on(g.departamento=l.departamento)
                                                        left join os_externas as h on (e.numero_orden_id=h.numero_orden_id) 
                                                        left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
                                                        tipos_afiliado as b, servicios as c, pacientes as d, cups as f, autorizaciones as j, system_usuarios as k,
                                                        planes as p, hc_os_solicitudes as z, hc_os_solicitudes_manuales as v
                                                        where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
                                                        and a.plan_id=p.plan_id
                                                        and z.hc_os_solicitud_id=v.hc_os_solicitud_id
                                                        and v.usuario_id=".UserGetUID()."
                                                        and date(a.fecha_registro) = date(now())
                                                        and z.hc_os_solicitud_id=e.hc_os_solicitud_id
                                                        and a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                                        and a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."' 
                                                        and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id 
                                                        and e.cargo_cups=f.cargo
                                                        and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                                                        and j.usuario_id=k.usuario_id
                                                        and e.sw_estado in('1','2','3','7')
                                                        order by a.orden_servicio_id desc";        
						            $result=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error al Guardar en la Tabal autorizaiones";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        return false;
                        }
                        if (!$result->EOF)
                        {
                                        while(!$result->EOF)
                                        {
                                                        $vars2[]=$result->GetRowAssoc($ToUpper = false);
                                                        $result->MoveNext();
                                        }
                        }
                        $result->Close();
                        unset($_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']);
                        $_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']=$vars2;
                        $dbconn->CommitTrans();
                        for($i=0; $i<sizeof($ordenes); $i++)
                        {
                                $x.=$ordenes[$i];
                                if($i!=sizeof($ordenes))
                                { $x.=' - ';}
                        }

                        $Mensaje = 'La Orden de Servicio No. '.$x.' Fue Generada.';
                        $accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaFinSolicitud');
                        if(!$this-> FormaMensaje($Mensaje,'ORDENES DE SERVICIO',$accion,'')){
                        return false;
                        }
                        return true;
    }


        /**
        *
        */
        function CrearTranscripcion()
        {
           /* $f=0;
            foreach($_REQUEST as $k => $v)
            {
                if(substr_count($k,'Combo'))
                {
                                            if($v!=-1)
                                            {        //0 hc_os_solicitud_id
                                                    $arr=explode(',',$v);
                                                    $d=0;
                                                    foreach($_REQUEST as $ke => $va)
                                                    {
                                                            if(substr_count($ke,'Op'))
                                                            {        // 0 solicitud_id
                                                                    $var=explode(',',$va);
                                                                    if($var[0]==$arr[0])
                                                                    {  $d=1;  }
                                                            }
                                                    }
                                                    if($d==0)
                                                    {
                                                                    $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Cargo.";
                                                                    $this->FormaListadoCargos($_REQUEST['datos']);
                                                                    return true;
                                                    }
                                            }
                }
            }*/
            //$dbconn->debug=true;
            $arr=explode(',',$_REQUEST['dat']);
            $d=0;
            foreach($_REQUEST as $k => $v)
            {
                  if(substr_count($k,'Op'))
                  {        // 0 solicitud_id 1 cargo 2 tarifario
                      $var=explode(',',$v);
                      if($var[0]==$_REQUEST['solicitud'])
                      { $d++; }
                  }
             }

              if($d==0)
              {
                      $this->frmError["MensajeError"]="ERROR DATOS VACIOS: Debe elegir algun Cargo para la Transcripción.";
                      $this->FormaListadoCargos($_SESSION['SOLICITUD']['LISTADO']);
                      return true;
              }

                        list($dbconn) = GetDBconn();
                        $query = "select empresa_id from planes
                        where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
                        $resultado=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al insertar en hc_os_solicitudes";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        $empresa=$resultado->fields[0];

                        $auto=$_SESSION['SOLICITUD']['Autorizacion'];
                        $plan=$_SESSION['SOLICITUD']['PACIENTE']['plan_id'];
                        $rango=$_SESSION['SOLICITUD']['PACIENTE']['RANGO'];
                        $afiliado=$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO'];
                        $semana=$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'];
                        $paciente=$_SESSION['SOLICITUD']['PACIENTE']['paciente_id'];
                        $tipo=$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente'];
                        $msg=$_SESSION['SOLICITUD']['observacion'];
                        $servicio=$_SESSION['SOLICITUD']['DATOS']['SERVICIO'];
                        if(empty($_SESSION['SOLICITUD']['ext']))
                        {  $ext='NULL';  }
                        else
                        {  $ext=$auto;  }

            $dbconn->BeginTrans();
						$query="(
											SELECT c.evento as evento_soat
											FROM hc_os_solicitudes a, hc_evoluciones b, ingresos_soat c
											WHERE a.hc_os_solicitud_id = ".$_REQUEST['solicitud']."
												AND a.evolucion_id IS NOT NULL
												AND a.evolucion_id = b.evolucion_id
												AND b.ingreso = c.ingreso
											)	
											UNION 
											(
											SELECT b.evento_soat 
											FROM hc_os_solicitudes a, hc_os_solicitudes_manuales b
											WHERE a.hc_os_solicitud_id =  ".$_REQUEST['solicitud']."
												AND a.evolucion_id IS NULL
												AND a.hc_os_solicitud_id = b.hc_os_solicitud_id
											)";
											
						$result=$dbconn->Execute($query);		
									
						if ($dbconn->ErrorNo() != 0) {
										$this->error = "SQL ERROR";
										$this->mensajeDeError =  $dbconn->ErrorMsg();
										return false;
						}	
						
						if(!$result->EOF)
						{
							list($evento_soat)= $result->FetchRow();
						}
						else
						{
							$evento_soat = 'NULL';
						}
						$result->Close();
						
						
            $query="SELECT nextval('os_ordenes_servicios_orden_servicio_id_seq')";
            $result=$dbconn->Execute($query);
            $orden=$result->fields[0];
						//evento soat se inserto para verificar si el paciente viene por algun evento
            $query = "INSERT INTO os_ordenes_servicios
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
            VALUES($orden,".$auto.",NULL,".$plan.",'".$afiliado."',
            '".$rango."',".$semana.",'".$servicio."','".$tipo."','".$paciente."',".UserGetUID().",'now()','".$msg."',$evento_soat)";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error INSERT INTO os_ordenes_servicios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }
            else
            {
                                    $query = "select * from os_tipos_periodos_planes
                                                                            where plan_id=".$plan."
                                                                            and cargo='".$arr[5]."'";
                                    $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error os_tipos_periodos_planes";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                    }
                                    if(!$result->EOF)
                                    {
                                                    $var=$result->GetRowAssoc($ToUpper = false);
                                                    $Fecha=$this->FechaStamp($arr[fecha]);
                                                    $infoCadena = explode ('/',$Fecha);
                                                    $intervalo=$this->HoraStamp($arr[fecha]);
                                                    $infoCadena1 = explode (':', $intervalo);
                                                    $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                                    if($fechaAct < date("Y-m-d H:i:s"))
                                                    {  $fechaAct=date("Y-m-d H:i:s");  }
                                                    $Fecha=$this->FechaStamp($fechaAct);
                                                    $infoCadena = explode ('/',$Fecha);
                                                    $intervalo=$this->HoraStamp($fechaAct);
                                                    $infoCadena1 = explode (':', $intervalo);
                                                    $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
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
                                                                                            where cargo='".$arr[5]."'";
                                                    $result=$dbconn->Execute($query);
                                                    if ($dbconn->ErrorNo() != 0) {
                                                                    $this->error = "Error os_tipos_periodos_tramites";
                                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                                    return false;
                                                    }
                                                    if(!$result->EOF)
                                                    {
                                                                            $var=$result->GetRowAssoc($ToUpper = false);
                                                                            $Fecha=$this->FechaStamp($arr[6]);
                                                                            $infoCadena = explode ('/',$Fecha);
                                                                            $intervalo=$this->HoraStamp($arr[6]);
                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                            $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_tramite_os]),$infoCadena[2]));
                                                                            if($fechaAct < date("Y-m-d H:i:s"))
                                                                            {  $fechaAct=date("Y-m-d H:i:s");  }
                                                                            $Fecha=$this->FechaStamp($fechaAct);
                                                                            $infoCadena = explode ('/',$Fecha);
                                                                            $intervalo=$this->HoraStamp($fechaAct);
                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                            $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_vigencia]),$infoCadena[2]));
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
                                                                            $Fecha=$this->FechaStamp($arr[6]);
                                                                            $infoCadena = explode ('/',$Fecha);
                                                                            $intervalo=$this->HoraStamp($arr[6]);
                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                            $fechaAct=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$tramite),$infoCadena[2]));
                                                                            if($fechaAct < date("Y-m-d H:i:s"))
                                                                            {  $fechaAct=date("Y-m-d H:i:s");  }
                                                                            $Fecha=$this->FechaStamp($fechaAct);
                                                                            $infoCadena = explode ('/',$Fecha);
                                                                            $intervalo=$this->HoraStamp($fechaAct);
                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                            $venc=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$vigencia),$infoCadena[2]));
                                                                            //fecha refrendar
                                                                            $Fecha=$this->FechaStamp($venc);
                                                                            $infoCadena = explode ('/',$Fecha);
                                                                            $intervalo=$this->HoraStamp($venc);
                                                                            $infoCadena1 = explode (':', $intervalo);
                                                                            $refrendar=date("Y-m-d H:i:s",mktime($infoCadena1[0],$infoCadena1[1],0,$infoCadena[1],($infoCadena[0]+$var[dias_refrendar]),$infoCadena[2]));
                                                    }
                                    }
                                    $query="SELECT nextval('os_maestro_numero_orden_id_seq')";
                                    $result=$dbconn->Execute($query);
                                    $numorden=$result->fields[0];

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
                                    VALUES($numorden,$orden,7,'$venc',".$_REQUEST['solicitud'].",'$fechaAct',1,'".$arr[5]."','$refrendar')";
                                    $dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error INSERT INTO os_maestro";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    $dbconn->RollbackTrans();
                                                    return false;
                                    }

                  foreach($_REQUEST as $ke => $va)
                  {
                      if(substr_count($ke,'Op'))
                      {        // 0 solicitud_id 1 cargo 2 tarifario
                          $var=explode(',',$va);
                          if($var[0]==$_REQUEST['solicitud'])
                          {
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
                                            VALUES($orden,".$plan.",'$tipo','$paciente','now()',".UserGetUID().")";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                                        $this->error = "Error INSERT INTO os_maestro";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                        }

           $query = "UPDATE hc_os_solicitudes SET    sw_estado=0
                      WHERE hc_os_solicitud_id=".$_REQUEST['solicitud']."";
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error UPDATE  hc_os_solicitudes ";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
            }
            $result->MoveNext();


                        $dbconn->CommitTrans();
                         $query = "(select distinct e.fecha,a.hc_os_solicitud_id, b.cantidad,b.cargo as cargos,b.plan_id,b.os_tipo_solicitud_id,
                                                n.cargo,n.tarifario_id,h.descripcion, r.descripcion as descar
                                                from hc_os_autorizaciones as a,hc_os_solicitudes as b
                        join tarifarios_equivalencias as n on(n.cargo_base=b.cargo)
                                                join tarifarios_detalle as h on (h.cargo=n.cargo and h.tarifario_id=n.tarifario_id),
                                                hc_os_solicitudes_manuales as e, cups as r
                                                where (a.autorizacion_int=".$auto." OR
                                                a.autorizacion_ext=".$auto.")
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
                        if(!$result->EOF)
                        {
                                    while(!$result->EOF)
                                    {
                                                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                                                    $result->MoveNext();
                                    }
                                    $this->frmError["MensajeError"]="La Transcripcion Fue Realizada. Orden generada $numorden.";
                                    $this->FormaListadoCargos($vars);
                                    return true;
                        }
                        else
                        {

                                    //SOLICITUDES
                                    $query = "    select t.nivel_autorizador_id,NULL, a.cantidad,a.hc_os_solicitud_id,a.cargo as cargos,
                                                            p.descripcion as descar, p.nivel_autorizador_id, p.sw_pos, b.tipo_id_paciente,
                                                            b.paciente_id,k.primer_nombre||' '||k.segundo_nombre||' '||k.primer_apellido||' '||k.segundo_apellido as nombres,
                                                            a.plan_id,f.plan_descripcion,
                                                            a.os_tipo_solicitud_id,a.sw_estado,
                                                            b.servicio,p.descripcion,
                                                            m.descripcion as desserv,g.descripcion as desos,
                                                            b.fecha,NULL,
                                                            b.prestador,b.observaciones,NULL, p.nivel_autorizador_id as nivel,
                                                            t.empresa_id
                                                            from    userpermisos_centro_autorizacion as t
                                                            left join    hc_os_solicitudes as a on(t.usuario_id=".UserGetUID()."
                                                            and a.plan_id=t.plan_id or t.sw_todos_planes=1),
                                                            planes as f, os_tipos_solicitudes as g,
                                                            pacientes as k, servicios as m,
                                                            cups as p,
                                                            hc_os_solicitudes_manuales as b
                                                            where
                                                            p.cargo=a.cargo and a.sw_estado=1
                                                            and a.plan_id=f.plan_id
                                                            and date(b.fecha_resgistro) = date(now())
                                                            and a.os_tipo_solicitud_id=g.os_tipo_solicitud_id
                                                            and a.evolucion_id is null
                                                            and t.usuario_id=".UserGetUID()."
                                                            and b.servicio=m.servicio
                                                            and a.hc_os_solicitud_id=b.hc_os_solicitud_id
                                                            and b.tipo_id_paciente=k.tipo_id_paciente
                                                            and b.paciente_id=k.paciente_id
                                                            and b.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                                            and b.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
                                                            order by t.plan_id, m.servicio, b.tipo_id_paciente,
                                                            b.paciente_id, a.os_tipo_solicitud_id";
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
                                                                    $vars1[]=$result->GetRowAssoc($ToUpper = false);
                                                                    $result->MoveNext();
                                                    }
                                    }
                                    $result->Close();
                                    unset($_SESSION['SOLICITUD']['ARREGLO']['DETALLE2']);
                                    $_SESSION['SOLICITUD']['ARREGLO']['DETALLE2']=$vars1;
            
                                    //LAS ORDENES
																		$query = "select a.*, b.tipo_afiliado_nombre, c.descripcion as desserv, d.primer_nombre||' '||d.segundo_nombre||' '||d.primer_apellido||' '||d.segundo_nombre as nombre, e.numero_orden_id,
                                                        case when e.sw_estado=1 then 'ACTIVO' when e.sw_estado=2 then 'PAGADO' when e.sw_estado=3 then 'PARA ATENCION' when e.sw_estado=7 then 'TRASCRIPCION' when e.sw_estado=8 then 'ANULADA POR VENCIMIENTO'  when e.sw_estado=0 then 'ATENDIDA' else 'ANULADA' end as estado,e.sw_estado,
                                                        e.fecha_vencimiento, e.cantidad, e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups, f.descripcion,
                                                        g.cargo, g.departamento, l.descripcion as desdpto, h.cargo as cargoext,i.plan_proveedor_id, i.plan_descripcion as planpro, k.nombre as autorizador,
                                                        p.plan_descripcion,j.sw_estado
                                                        from os_ordenes_servicios as a
                                                        left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                                                        left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                                                        left join departamentos as l on(g.departamento=l.departamento)
                                                        left join os_externas as h on (e.numero_orden_id=h.numero_orden_id) 
                                                        left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id),
                                                        tipos_afiliado as b, servicios as c, pacientes as d, cups as f, autorizaciones as j, system_usuarios as k,
                                                        planes as p, hc_os_solicitudes as z, hc_os_solicitudes_manuales as v
                                                        where a.tipo_afiliado_id=b.tipo_afiliado_id and a.servicio=c.servicio
                                                        and a.plan_id=p.plan_id
                                                        and z.hc_os_solicitud_id=v.hc_os_solicitud_id
                                                        and v.usuario_id=".UserGetUID()."
                                                        and date(a.fecha_registro) = date(now())
                                                        and z.hc_os_solicitud_id=e.hc_os_solicitud_id
                                                        and a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
                                                        and a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."' 
                                                        and a.tipo_id_paciente=d.tipo_id_paciente and a.paciente_id=d.paciente_id 
                                                        and e.cargo_cups=f.cargo
                                                        and (a.autorizacion_int=j.autorizacion or a.autorizacion_ext=j.autorizacion)
                                                        and j.usuario_id=k.usuario_id
                                                        and e.sw_estado in(1,2,3,7)
                                                        order by a.orden_servicio_id desc";                                 
																	  $result=$dbconn->Execute($query);
                                    if ($dbconn->ErrorNo() != 0) {
                                                    $this->error = "Error al Guardar en la Tabal autorizaiones";
                                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                    return false;
                                    }
                                    if (!$result->EOF)
                                    {
                                                    while(!$result->EOF)
                                                    {
                                                                    $vars2[]=$result->GetRowAssoc($ToUpper = false);
                                                                    $result->MoveNext();
                                                    }
                                    }
                                    $result->Close();
                                    unset($_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']);
                                    $_SESSION['SOLICITUD']['ARREGLO']['DETALLE1']=$vars2;

                                    $Mensaje = 'La Transcripcion Fue Realizada.';
                                    $accion=ModuloGetURL('app','CentroAutorizacionSolicitud','user','FormaFinSolicitud');
                                    if(!$this-> FormaMensaje($Mensaje,'CENTRO AUTORIZACIONES',$accion,'')){
                                            return false;
                                    }
                                    return true;
                        }
        }


//--------------------------REPORTE------------------------------------------

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
                     								p.nombre_cuota_moderadora, p.nombre_copago, a.tipo_id_paciente,
                                    a.paciente_id, a.tipo_afiliado_id, a.semanas_cotizadas, a.plan_id
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
                                    a.tipo_afiliado_id, a.semanas_cotizadas, a.plan_id
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
        function ReporteOrdenServicio()
        {
        if (!IncludeFile("classes/reports/reports.class.php")) {
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
        }

                $empresa=$_SESSION['SOLICITUD']['EMPRESA']; 
        				$var[0]=$this->EncabezadoReporte($_REQUEST['orden'],$_REQUEST['tipoid'],$_REQUEST['paciente'],$_REQUEST['afiliado'],$_REQUEST['plan']);
                list($dbconn) = GetDBconn();
                 $query = "(select a.*,
                                    e.numero_orden_id,e.sw_estado, e.fecha_vencimiento, e.cantidad,
                                    e.hc_os_solicitud_id, e.fecha_activacion, e.fecha_refrendar, e.cargo_cups,
                                    f.descripcion, g.cargo, g.departamento, l.descripcion as desdpto,
                                    h.cargo as cargoext,    i.plan_proveedor_id, i.plan_descripcion as planpro,
                                    z.tarifario_id, z.cargo, y.requisitos,
                                    x.nombre_tercero as nompro, x.direccion  as dirpro, x.telefono as telpro,
                                    s.descripcion as descar, m.profesional, NULL, n.observacion as obsapoyo, 
                                    o.observacion as obsinter, o.especialidad, a.observacion                                    
                                    ,AB.descripcion as especialidad_nombre                                    
                                    from os_ordenes_servicios as a
                                    left join os_maestro as e on (a.orden_servicio_id=e.orden_servicio_id)
                                    left join os_internas as g on (e.numero_orden_id=g.numero_orden_id)
                                    left join departamentos as l on(g.departamento=l.departamento)
                                    left join os_externas as h on (e.numero_orden_id=h.numero_orden_id)
                                    left join planes_proveedores as i on(h.plan_proveedor_id=i.plan_proveedor_id)
                                    left join hc_os_solicitudes as q on(e.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                    left join hc_os_solicitudes_apoyod as n on(n.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                    left join hc_os_solicitudes_interconsultas as o on(o.hc_os_solicitud_id=q.hc_os_solicitud_id)
                                    left join especialidades as AB on(AB.especialidad=o.especialidad )
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

				if($_REQUEST['pos']==1)
				{
						$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
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
				else
				{
					if ($_REQUEST['parametro_retorno'] == '1')
					{
							IncludeLib("reportes/ordenservicio");
							GenerarOrden($var);
							if(is_array($var))
							{
									$RUTA = $_ROOT ."cache/ordenservicio.pdf";
									$mostrar ="\n<script language='javascript'>\n";
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
							$this->ReturnMetodoExterno('app','ImpresionHC','user','FormaImpresionSolicitudes');
					}
					else
					{
							IncludeLib("reportes/ordenservicio");
							$vector['orden']=$_REQUEST['orden'];
							GenerarOrden($vector);
							if(!empty($_REQUEST['regreso']))//cuando es la impresion desde la autorizacion
							{  $this->$_REQUEST['regreso']($vector,3);  }
							if(!empty($_REQUEST['regreso2']))//cuando es la impresion es desde listadoo
							{  $this->$_REQUEST['regreso2']($_REQUEST['tipoid'],$_REQUEST['paciente'],$vector,3);  }
							return true;
					}
				}

		}

//----------------------------PROCEDIMIENTOS NO QX---------------------------------

	function NoQx()
	{
									unset ($_SESSION['APOYOSnqx']);
									unset ($_SESSION['PROCEDIMIENTOnqx']);
									unset ($_SESSION['MODIFICANDOnqx']);
									unset($_SESSION['PASOnqx']);
									unset($_SESSION['PASO1nqx']);
									unset ($_SESSION['DIAGNOSTICOSnqx']);
									$this->frmFormanoqx();
									return true;
	}

	function GetFormaNoQx()
	{
					if(empty($_REQUEST['accionnqx']))
					{
						$this->frmForma();
					}
					else
					{
						if($_REQUEST['accionnqx']=='Busqueda_Avanzada_NO_Quirurgicos')
							{
										$vectorA= $this->Busqueda_Avanzada_NO_Quirurgicos();
										$this-> frmForma_Seleccion_No_Qx($vectorA);
							}
	
						if($_REQUEST['accionnqx']=='Busqueda_Avanzada_Diagnosticos')
							{
										$vectorD= $this->Busqueda_Avanzada_DiagnosticosNoQx();
										$this-> frmForma_Modificar_ObservacionNoQx($_REQUEST['hc_os_solicitud_idnqx'],$_REQUEST['cargonqx'],$_REQUEST['descripcionnqx'], $_REQUEST['observacionnqx'],$vectorD,$_REQUEST['cantidadnqx'],$_REQUEST['sw_cantidadnqx']);
							}
	
							if($_REQUEST['accionnqx']=='eliminar')
							{
									$this->Eliminar_No_Qx_Solicitado($_REQUEST['hc_os_solicitud_idnqx']);
									$this->frmFormanoqx();
							}

						if($_REQUEST['accionnqx']=='observacion')
							{//cambio dar el ultimo parametro
								$this->frmForma_Modificar_ObservacionNoQx($_REQUEST['hc_os_solicitud_idnqx'],$_REQUEST['cargonqx'],$_REQUEST['descripcionnqx'], $_REQUEST['observacionnqx'],'',$_REQUEST['cantidadnqx'],$_REQUEST['sw_cantidadnqx']);
							}

						if($_REQUEST['accionnqx']=='modificar')
							{
									$this->Modificar_NO_Qx_Solicitado($_REQUEST['hc_os_solicitud_idnqx']);
									$this->frmFormanoqx();
							}
	
						if($_REQUEST['accionnqx']=='insertar_variasNoQx')
							{
									$this->Insertar_Varias_Solicitudes_NoQx();
									$this->frmFormanoqx();
							}
						if($_REQUEST['accionnqx']=='insertar_varios_diagnosticos')
							{
									$this->Insertar_Varios_DiagnosticosNoQx();
									$this->frmForma_Modificar_ObservacionNoQx($_REQUEST['hc_os_solicitud_idnqx'],$_REQUEST['cargonqx'],$_REQUEST['descripcionnqx'], $_REQUEST['observacionnqx'],'',$_REQUEST['cantidadnqx'],$_REQUEST['sw_cantidadnqx']);
							}
							if($_REQUEST['accionnqx']=='eliminar_diagnostico')
							{
									$this->Eliminar_Diagnostico_Solicitado_noQx($_REQUEST['hc_os_solicitud_idnqx'], $_REQUEST['codigonqx']);
									$this->frmForma_Modificar_ObservacionNoQx($_REQUEST['hc_os_solicitud_idnqx'],$_REQUEST['cargonqx'],$_REQUEST['descripcionnqx'], $_REQUEST['observacionnqx'],'',$_REQUEST['cantidadnqx'],$_REQUEST['sw_cantidadnqx']);
							}
	
					}
					return $this->salida;
	}
	
				
	function Consulta_Solicitud_No_Qx($k)
	{		//cambio dar b.cantidad,c.sw_cantidad
			list($dbconnect) = GetDBconn();
			 $query= "SELECT a.*, b.cargo, b.plan_id, b.os_tipo_solicitud_id, e.observacion,b.cantidad,
													c.descripcion, h.descripcion as tipo,c.sw_cantidad,
													informacion_cargo('".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."',b.cargo,'')
													FROM hc_os_solicitudes_manuales as a, hc_os_solicitudes as b
													left join hc_os_solicitudes_no_quirurgicos e on (b.hc_os_solicitud_id = e.hc_os_solicitud_id),
													cups c, grupos_tipos_cargo h
													WHERE a.tipo_id_paciente='".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."'
													and a.paciente_id='".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."'
													and a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.sw_estado=1
													AND c.grupo_tipo_cargo = h.grupo_tipo_cargo 
													and a.hc_os_solicitud_id=$k
													and b.cargo=c.cargo 
													order by a.hc_os_solicitud_id";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
					$this->error = "Error al buscar en la consulta de solictud de apoyos";
					$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
					return false;
			}
			else
			{
								$vector=$result->GetRowAssoc($ToUpper = false);				
			}
			return $vector;
	}		
	
	function Busqueda_Avanzada_NO_Quirurgicos()
	{   
				list($dbconn) = GetDBconn();
				$opcion      = ($_REQUEST['criterio1nqx']);
				$cargo       = ($_REQUEST['cargonqx']);
				$descripcion =STRTOUPPER($_REQUEST['descripcionnqx']);
		
				$filtroTipoCargo = '';
				$busqueda1 = '';
				$busqueda2 = '';	
		
				if($opcion != '-1' && !empty($opcion))
				{
					$filtroTipoCargo=" AND a.tipo_cargo = '$opcion'";
				}
	
				if ($cargo != '')
				{
					$busqueda1 =" AND a.cargo LIKE '$cargo%'";
				}
	
				if ($descripcion != '')
				{
					$busqueda2 ="AND a.descripcion LIKE '%$descripcion%'";
				}
	
			if(empty($_REQUEST['conteonqx']))
			{													
					$query = "SELECT count(*)	FROM (SELECT DISTINCT a.cargo, 
										a.descripcion, a.grupo_tipo_cargo, a.sw_cantidad,
										c.descripcion as tipo, d.tipo_cargo
										FROM cups a, no_qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
										tipos_cargos d					
										WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo AND
										b.grupo_tipo_cargo = c.grupo_tipo_cargo 
										AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
										$filtroTipoCargo$busqueda1$busqueda2) as a";			
				$resulta = $dbconn->Execute($query);	
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				list($this->conteo)=$resulta->fetchRow();
			}
			else
			{
				$this->conteo=$_REQUEST['conteonqx'];
			}
			if(!$_REQUEST['Ofnqx'])
			{
				$Of='0';
			}
			else
			{
				$Of=$_REQUEST['Ofnqx'];
				if($Of > $this->conteo)
				{
					$Of=0;
					$_REQUEST['Ofnqx']=0;
					$_REQUEST['paso1nqx']=1;
				}
			}
	
			//SI QUIERO EL NOMBRE DEL TIPO CARGO CAMBIO c.descripcion as tipo POR d.descripcion as tipo
			$query = "SELECT DISTINCT a.cargo, a.descripcion, a.grupo_tipo_cargo, 
										c.descripcion as tipo, d.tipo_cargo, a.sw_cantidad
										FROM cups a, no_qx_grupos_tipo_cargo b, grupos_tipos_cargo c,
										tipos_cargos d			
										WHERE a.grupo_tipo_cargo = c.grupo_tipo_cargo AND
										b.grupo_tipo_cargo = c.grupo_tipo_cargo
										AND a.tipo_cargo = d.tipo_cargo AND c.grupo_tipo_cargo = d.grupo_tipo_cargo
										$filtroTipoCargo$busqueda1$busqueda2
										LIMIT ".$this->limit." OFFSET $Of;";	
			$resulta = $dbconn->Execute($query);
			//$this->conteo=$resulta->RecordCount();
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
	
			if($this->conteo==='0')
				{       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
								return false;
				}
			$resulta->Close();
			return $var;
	}		
	
	function Insertar_Varias_Solicitudes_NoQx()
	{
			list($dbconn) = GetDBconn();
			$query = "select empresa_id from planes
			where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id']."";
			$resultado=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
					$this->error = "Error al insertar en hc_os_solicitudes";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$empresa=$resultado->fields[0];
			$evento = "NULL"; 
			if(!empty($_SESSION['SolitudManual']['evento'])) $evento = $_SESSION['SolitudManual']['evento'];
											
			$dbconn->BeginTrans();
			foreach($_REQUEST['opnqx'] as $index=>$codigo)
			{
					//cambio dar
					$cant=$_REQUEST['cantidadnqx'.$index];
					//fin cambio dar
					//realiza el id manual de la tabla
					$query1="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
					$result=$dbconn->Execute($query1);
					$hc_os_solicitud_id=$result->fields[0];
					$query2="INSERT INTO hc_os_solicitudes
										(hc_os_solicitud_id, evolucion_id, cargo, os_tipo_solicitud_id, plan_id, cantidad, paciente_id, tipo_id_paciente)
										VALUES
										($hc_os_solicitud_id,NULL,
										 '".$codigo."', 'PNQ',
										 ".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].",
										 $cant,
                                				 '".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
                                				 '".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."')";
					$resulta=$dbconn->Execute($query2);	
					if ($dbconn->ErrorNo() != 0)
					{
						$this->error = "Error al insertar en hc_os_solicitudes";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
					else
					{
						$query3="INSERT INTO hc_os_solicitudes_no_quirurgicos
						(hc_os_solicitud_id)
						VALUES  ($hc_os_solicitud_id);";

						$resulta1=$dbconn->Execute($query3);
						if ($dbconn->ErrorNo() != 0)
							{
								$this->error = "Error al insertar en hc_os_solicitudes_no_quirurgicos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						else
							{							
									$resulta1->Close();
									if(empty($_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']))
									{   $dpto='NULL';   }
									else
									{   $dpto="'".$_SESSION['SOLICITUD']['DATOS']['IDDEPARTAMENTO']."'";   }	
													
									$query3="INSERT INTO hc_os_solicitudes_manuales(
																	hc_os_solicitud_id,
																	fecha,
																	servicio,
																	profesional,
																	prestador,
																	observaciones,
																	tipo_id_paciente,
																	paciente_id,
																	fecha_resgistro,
																	usuario_id,
																	empresa_id,
																	tipo_afiliado_id,
																	rango,
																	semanas_cotizadas,
																	departamento,
																	evento_soat)
													VALUES(	$hc_os_solicitud_id, 
																	'".$_SESSION['SOLICITUD']['DATOS']['FECHA']."',
																	'".$_SESSION['SOLICITUD']['DATOS']['SERVICIO']."',
																	'".$_SESSION['SOLICITUD']['DATOS']['MEDICO']."',
																	'".$_SESSION['SOLICITUD']['DATOS']['ENTIDAD']."',
																	'".$_SESSION['SOLICITUD']['DATOS']['OBSERVACION']."',
																	'".$_SESSION['SOLICITUD']['PACIENTE']['tipo_id_paciente']."',
																	'".$_SESSION['SOLICITUD']['PACIENTE']['paciente_id']."',
																	'now()',
																	".UserGetUID().",
																	'$empresa',
																	'".$_SESSION['SOLICITUD']['PACIENTE']['AFILIADO']."',
																	'".$_SESSION['SOLICITUD']['PACIENTE']['RANGO']."',
																	".$_SESSION['SOLICITUD']['PACIENTE']['SEMANAS'].",
																	$dpto,
																	".$evento.");";
											$resulta1=$dbconn->Execute($query3);
											if ($dbconn->ErrorNo() != 0)
											{
													$this->error = "Error al insertar en hc_os_solicitudes_apoyod";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													$dbconn->RollbackTrans();
													return false;
											}
											$resulta1->Close();							
							}
					}
					$_SESSION['ARREGLO']['DATOS']['NOQX'][$hc_os_solicitud_id]=$hc_os_solicitud_id;
					$_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['hc_os_solicitud_id']=$hc_os_solicitud_id;
					$_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cargo']=$codigo;
					$_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]['cantidad']=$cant;
			}
			
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";	
			$this->NoQx();		
			return true;
	}
	
	
	function Eliminar_No_Qx_Solicitado($hc_os_solicitud_id)
	{
				list($dbconn) = GetDBconn();
				$dbconn->BeginTrans();
				$query="DELETE FROM hc_os_solicitudes_diagnosticos
								WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
						$dbconn->RollbackTrans();
						return false;
				}
				else
				{
							$query1="DELETE FROM hc_os_solicitudes_no_quirurgicos
							WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
							$resulta1=$dbconn->Execute($query1);
							if ($dbconn->ErrorNo() != 0)
							{
								$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
								$dbconn->RollbackTrans();
								return false;
							}
							else
							{
                  $query2="DELETE FROM hc_os_solicitudes_manuales
									WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
									$resulta1=$dbconn->Execute($query2);
									if ($dbconn->ErrorNo() != 0)
									{
											$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
											$dbconn->RollbackTrans();
											return false;
									}			
													
									$query2="DELETE FROM hc_os_solicitudes
									WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
									$resulta1=$dbconn->Execute($query2);
									if ($dbconn->ErrorNo() != 0)
									{
										$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
										$dbconn->RollbackTrans();
										return false;
									}
									else
									{
										$dbconn->CommitTrans();
										unset($_SESSION['ARREGLO']['DATOS']['NOQX'][$hc_os_solicitud_id]);
										unset($_SESSION['ARREGLO']['MALLA'][$hc_os_solicitud_id]);
										$this->frmError["MensajeError"]="SOLICITUD ELIMINADA SATISFACTORIAMENTE.";
										
									}
							}
				}
				$this->NoQx();
				return true;
	}
	
	function Busqueda_Avanzada_DiagnosticosNoQx()
	{
			list($dbconn) = GetDBconn();
			$codigo       = STRTOUPPER ($_REQUEST['codigonqx']);
			$diagnostico  =STRTOUPPER($_REQUEST['diagnosticonqx']);
	
			$busqueda1 = '';
			$busqueda2 = '';
	
			if ($codigo != '')
			{
				$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
			}
	
			if (($diagnostico != '') AND ($codigo != ''))
			{
				$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
			}
	
			if (($diagnostico != '') AND ($codigo == ''))
			{
				$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
			}
	
			if(empty($_REQUEST['conteonqx']))
			{
				$query = "SELECT count(*)
							FROM diagnosticos
							$busqueda1 $busqueda2";
	
				$resulta = $dbconn->Execute($query);
	
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				list($this->conteo)=$resulta->fetchRow();
			}
			else
			{
				$this->conteo=$_REQUEST['conteonqx'];
			}
			if(!$_REQUEST['Ofnqx'])
			{
				$Of='0';
			}
			else
			{
				$Of=$_REQUEST['Ofnqx'];
				if($Of > $this->conteo)
				{
					$Of=0;
					$_REQUEST['Ofnqx']=0;
					$_REQUEST['paso1nqx']=1;
				}
			}
					$query = "
							SELECT diagnostico_id, diagnostico_nombre
							FROM diagnosticos
							$busqueda1 $busqueda2 order by diagnostico_id
							LIMIT ".$this->limit." OFFSET $Of;";
			$resulta = $dbconn->Execute($query);
			//$this->conteo=$resulta->RecordCount();
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$i=0;
			while(!$resulta->EOF)
			{
				$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
				$i++;
			}
	
			if($this->conteo==='0')
				{       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
								return false;
				}
			$resulta->Close();
			return $var;
	}	
	
	function Modificar_NO_Qx_Solicitado($hc_os_solicitud_id)
	{
				list($dbconn) = GetDBconn();
				//cambio dar
				if(!empty($_REQUEST['cantidadnqx']))
				{
						$cantidad = $_REQUEST['cantidadnqx'];
						if (is_numeric($cantidad)==0)
						{
								$this->frmError["MensajeError"]="DIGITE CANTIDADES VALIDAS.";
								return false;
						}
				}
				else
				{
						$cantidad =1;
				}
				//fin cambi dar
				$obs = $_REQUEST['obsnqx'];
				$query= "UPDATE hc_os_solicitudes_no_quirurgicos SET observacion = '$obs'
								WHERE hc_os_solicitud_id = $hc_os_solicitud_id";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al actualizar la observacion en hc_os_solicitudes_no_quirurgicos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
				//cambio dar
				$query= "UPDATE hc_os_solicitudes SET cantidad = ".$cantidad."
												WHERE hc_os_solicitud_id = ".$hc_os_solicitud_id."";
				$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al actualizar la cantidad en hc_os_solicitudes";
						$this->frmError["MensajeError"]="NO SE LOGRO ACTUALIZAR LA INTERCONSULTA";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
				}
				//fin cambi dar

				$dbconn->CommitTrans();

				$this->frmError["MensajeError"]="DATOS MODIFICADOS SATISFACTORIAMENTE";
				return true;
	}


	function tiposNoQx()
	{
			list($dbconnect) = GetDBconn();
			$query= "SELECT a.tipo_cargo, a.descripcion
							FROM tipos_cargos a, no_qx_grupos_tipo_cargo b
							WHERE a.grupo_tipo_cargo = b.grupo_tipo_cargo";	
			$result = $dbconnect->Execute($query);	
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al cargar los tipos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{ $i=0;
				while (!$result->EOF)
				{
						$vector[$i]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$i++;
				}
			}
			$result->Close();
			return $vector;
	}


	function Diagnosticos_SolicitadosNoQx($hc_os_solicitud_id)
	{
			list($dbconnect) = GetDBconn();
			$query= "select a.diagnostico_id, a.diagnostico_nombre
			FROM diagnosticos a, hc_os_solicitudes_diagnosticos b
			WHERE b.hc_os_solicitud_id = $hc_os_solicitud_id AND a.diagnostico_id = b.diagnostico_id";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al buscar en la tabla apoyod_tipos";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				return false;
			}
			else
			{ $i=0;
				while (!$result->EOF)
				{
						$vector[$i]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$i++;
				}
			}
			$result->Close();
			return $vector;
	}


	function Insertar_Varios_DiagnosticosNoQx()
	{
			list($dbconn) = GetDBconn();
			foreach($_REQUEST['opDnqx'] as $index=>$codigo)
			{
						$arreglo=explode(",",$codigo);			
						//BUSQUEDA DE DX REPETIDO EN SOLICITUD
					$query="SELECT count(*) 
										FROM hc_os_solicitudes_diagnosticos
										WHERE hc_os_solicitud_id = '".$arreglo[0]."'
										AND diagnostico_id = '".$arreglo[1]."';";										
						$resulta=$dbconn->Execute($query);
						if ($resulta->fields[0]==0)
						{
									//BUSQUEDA DE DX PRINCIPAL EN SOLICITUD
									$sql="SELECT count(*) 
													FROM hc_os_solicitudes_diagnosticos
													WHERE hc_os_solicitud_id = '".$arreglo[0]."'
													AND sw_principal = '1';";
									$resulta=$dbconn->Execute($sql);
									if ($dbconn->ErrorNo() != 0)
									{
											$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
											return false;
									}
									
									//INSERCION DE 1 DX PRINCIPAL
									if($resulta->fields[0]==0)
									{
											$query="INSERT INTO hc_os_solicitudes_diagnosticos
																			(hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
															VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '1', '1');";
									}
									//INSERCION DE LOS DEMAS DX'S (NO PRINCIPALES)
									else
									{
											$query="INSERT INTO hc_os_solicitudes_diagnosticos
																			(hc_os_solicitud_id, diagnostico_id, tipo_diagnostico, sw_principal)
															VALUES ('".$arreglo[0]."', '".$arreglo[1]."', '1', '0');";
									}
									$resulta=$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0)
									{
											$this->error = "Error al insertar en hc_os_solicitudes_diagnosticos";
											$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
											$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
											return false;
									}
									else
									{
											$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
									}
					}		
					//FIN BUSQUEDA DE DX REPETIDO EN INGRESO
					else
					{
							$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[1]." YA FUE ASIGNADO.";
					}							
			}
			return true;
	}


	function Eliminar_Diagnostico_Solicitado_noQx($hc_os_solicitud_id, $codigo)
	{
				list($dbconn) = GetDBconn();
				$query="DELETE FROM hc_os_solicitudes_diagnosticos
								WHERE hc_os_solicitud_id = $hc_os_solicitud_id
								AND diagnostico_id = '$codigo'";
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
						$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR PORQUE YA FUE AUTORIZADO";
						return false;
				}
				else
				{
					$this->frmError["MensajeError"]="DIAGNOSTICO ELIMINADO.";
				}
				return true;
	}	
	

    /**
  * Busca los diferentes tipos de afiliados
    * @access public
    * @return array
    */
    function NombreAfiliado($Tipo)
    {
                list($dbconn) = GetDBconn();
                $query = "SELECT DISTINCT a.tipo_afiliado_nombre, a.tipo_afiliado_id
                                    FROM tipos_afiliado as a, planes_rangos as b
                                    WHERE b.plan_id='".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."'
                                    and a.tipo_afiliado_id='$Tipo'";
                $resulta = $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $vars=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->Close();
                return $vars;
    }

		function PlanesProveedores()
		{
			list($dbconnect) = GetDBconn();
			$query= "select plan_descripcion
							FROM planes_proveedores order by plan_descripcion";
			$result = $dbconnect->Execute($query);
			if ($dbconnect->ErrorNo() != 0)
			{
				$this->error = "Error al buscar en la tabla apoyod_tipos";
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
	*
	*/
	function BuscarDepartamento()
	{
					list($dbconn) = GetDBconn();
					$query = "SELECT a.* FROM departamentos as a, servicios as b
										WHERE a.empresa_id=(SELECT empresa_id FROM planes where plan_id=".$_SESSION['SOLICITUD']['PACIENTE']['plan_id'].")
										and a.servicio=b.servicio and b.sw_asistencial=1
										ORDER BY descripcion";
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
											$vars[]=$result->GetRowAssoc($ToUpper = false);;
											$result->MoveNext();
									}
					}
					$result->Close();
          return $vars;
	}		
	/***********************************************************************************
	* Funcion que permite mostrar la forma para selecionar el evento SOAT si el plan 
	* es tipo soat, si no se continua el procedimiento normal
	*
	* @return boolean
	************************************************************************************/
	function EventoSoat()
	{
		unset($_SESSION['SolitudManual']['evento']);
		$this->Documento = $_REQUEST['Documento'];
		$this->TipoDoc = $_REQUEST['Tipo'];
		$this->Plan = $_REQUEST['plan'];
		
		if(!$this->TipoDoc || !$this->Documento || $this->Plan==-1)
		{
      if($Plan==-1){ $this->frmError["plan"]=1; }
      if(!$_REQUEST['Tipo']){ $this->frmError["Tipo"]=1; }
    	if(!$_REQUEST['Documento']){ $this->frmError["Documento"]=1; }
      
      $this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $this->FormaBuscar();
      return true;
		}
		$swPlan = $this->BuscarEventoSoat($_REQUEST['plan']);
		
		if($swPlan == 1)
		{
			$arreglo = array('Documento'=>$_REQUEST['Documento'],'Tipo'=>$_REQUEST['Tipo'],'plan'=>$_REQUEST['plan']);
			$this->action1 = ModuloGetURL('app','CentroAutorizacionSolicitud','user','Menu');
			$this->action2 = ModuloGetURL('app','CentroAutorizacionSolicitud','user','ValidarEventoSoat',$arreglo);
			$this->MostrarEventosSoat();
		}
		else
		{
			$this->BuscarPaciente();
		}
		return true;
	}
	/**********************************************************************************
	* Funcion donde se averigua, si el plan es tipo soat o no
	* @param $plan int plan id
	*
	* @return string
	***********************************************************************************/
  function BuscarEventoSoat($plan)
  {
		$sql = "SELECT  sw_tipo_plan FROM planes WHERE plan_id= ".$plan." ";
		list($dbconn) = GetDBconn();		
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		return $rst->fields[0];
  }
  /***********************************************************************************
  * Funcion donde se buscan los eventos soat del paciente
  * @params tipo del documento
  *					documento del paciente
  *
  * @return array
  ************************************************************************************/
  function BuscarEventoSoatPaciente($TipoDo,$Docume)
	{
		$sql = "SELECT	DISTINCT A.evento,
										A.poliza,
										A.condicion_accidentado,
										A.saldo,
										A.codigo_eps,
										A.accidente_id,
										A.asegurado,
										A.empresa_id,
										C.nombre_tercero,
										E.razon_social,
										TO_CHAR(D.fecha_accidente,'DD/MM/YYYY') AS fecha_accidente,
										TO_CHAR(D.fecha_accidente,'HH:MI AM') AS hora_accidente
										--F.ingreso
							FROM 	soat_eventos AS A
										LEFT JOIN soat_accidente AS D 
										ON (A.accidente_id=D.accidente_id),
										--LEFT JOIN ingresos_soat AS F
										--ON (A.evento=F.evento),
										soat_polizas AS B,
										terceros AS C,
										empresas AS E
							WHERE A.tipo_id_paciente='".$TipoDo."'
							AND 	A.paciente_id='".$Docume."'
							AND 	A.poliza=B.poliza
							AND 	B.tipo_id_tercero=C.tipo_id_tercero
							AND 	B.tercero_id=C.tercero_id
							AND 	A.empresa_id=E.empresa_id
							ORDER BY poliza;";

		list($dbconn) = GetDBconn();
		$rst = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		while(!$rst->EOF)
		{
			$eventos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		return $eventos;
	}
	/***********************************************************************************
	* Funcion donde se averiguan los datos del paciente
  * @params tipo del documento
  *					documento del paciente
  *
  * @return array
	************************************************************************************/
	function BuscarNombrePaci($TipoDo,$Doc)
	{
		
		$sql = "SELECT	primer_apellido||' '||segundo_apellido AS apellidos,
										primer_nombre||' '||segundo_nombre AS nombres
						FROM 		pacientes
						WHERE 	tipo_id_paciente='".$TipoDo."'
						AND 		paciente_id='".$Doc."';";
		
		list($dbconn) = GetDBconn();
		$rst = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$rst->EOF)
		{
			$paciente = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		return $paciente;
	}
	/***********************************************************************************
	* Funcion donde se valida si se selecciono un evento soat
	*
	* @return boolean 
	************************************************************************************/
	function ValidarEventoSoat()
	{
		$valor = $_REQUEST['eligevento'];
		if(!$valor)
		{
			$this->frmError['MensajeError'] = "SE DEBE SELECCIONAR UN ENVENTO SOAT PARA CONTINUAR CON LA ADMISION";
			$this->EventoSoat();
		}
		else
		{
			$_SESSION['SolitudManual']['evento'] = $valor;
			$this->BuscarPaciente();
		}
		return true;
	}
	
	function InformacionAfiliado($tipo_id_paciente,$paciente_id, $plan_id)
	{
		list($dbconn) = GetDBconn();
		//$dbconn->debug=true;
		$sql  = "SELECT PL.plan_id,";
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
        $sql .= "WHERE  AF.afiliado_tipo_id = '".$tipo_id_paciente."' ";
        $sql .= "AND    AF.afiliado_id = '".$paciente_id."' ";
        $sql .= "AND    AF.plan_atencion = '".$plan_id."' ";
        //$sql .= "AND    AF.estado_afiliado_id IN ('AC') ";
        $sql .= "AND    AF.plan_atencion = PL.plan_id ";
        $sql .= "AND    AF.tipo_afiliado_atencion = PL.tipo_afiliado_id ";
        $sql .= "AND    AF.rango_afiliado_atencion = PL.rango ";
        $sql .= "AND    TA.tipo_afiliado_id = PL.tipo_afiliado_id ";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		$var=$result->GetRowAssoc($ToUpper = false);
		$result->Close();
		return $var;
	}
//------------------------------------------------------------------------------------

}//fin clase user

?>

