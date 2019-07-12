<?php

/**
 * $Id: app_Reportes_Consulta_Externa_user.php,v 1.5 2009/12/11 14:50:43 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para los reportes de las agendas médicas de Consulta Externa
 */

/**
* Modulo de Reportes de Consulta Externa (PHP).
*
*
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_Reportes_Consulta_Externa_user.php
*
* Clase que establece los métodos de acceso y búsqueda de información de los turnos
* de las agendas médicas para la atención de pacientes en consulta externa
**/

class app_Reportes_Consulta_Externa_user extends classModulo
{   
    var $archivodat;
    
    function app_Reportes_Consulta_Externa_user()
    {
        return true;
    }

    function main()
    {		unset($_SESSION['recoex']['empresa']);
				unset($_SESSION['recoex']['razonso']);
				unset($_SESSION['recoex']['auditor']);
        $this->SeleccionDepartamentoUnificado();
        return true;
    }

    function UsuariosRepconsultaExterna()//Función de permisos
    {
			list($dbconn) = GetDBconn();
			$usuario=UserGetUID();
			$query = "SELECT DISTINCT A.empresa_id,
							B.razon_social AS descripcion1,auditor
							FROM userpermisos_repconsultaExterna AS A,
							empresas AS B
							WHERE A.usuario_id=".$usuario."
							AND A.empresa_id=B.empresa_id
							ORDER BY descripcion1;";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			while(!$resulta->EOF){
				$var1[$resulta->fields[1]]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			$mtz[0]='EMPRESAS';
			//$mtz[1]='CENTRO UTILIDAD';
			//$mtz[3]='UNIDAD FUNCIONAL';
			$url[0]='app';
			$url[1]='Reportes_Consulta_Externa';
			$url[2]='user';
			$url[3]='SeleccionDepartamentoUnificado';
			$url[4]='permisoreconex';
			$this->salida .=gui_theme_menu_acceso('REPORTES CONSULTA EXTERNA', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
			return true;
    }

    function GuardarSeleccionDepartamentoUnificado()
    {
      $request = $_REQUEST;
			if($request['Volver'])
      {				
				unset($_SESSION['recoex']['empresa']);
				unset($_SESSION['recoex']['razonso']);
				unset($_SESSION['recoex']['auditor']);				
				$this->SeleccionDepartamentoUnificado();
				return true;
			}
      
      if($request['centroUDescripcion']) $_SESSION['recoex']['descentro'] = $request['centroUDescripcion'];
      if($request['unidadFDescripcion']) $_SESSION['recoex']['desunidadfun'] = $request['unidadFDescripcion'];
      
			$this->PantallaInicial($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
			return true;			
		}
		
    function LlamaFormaSeleccion()
    {
        $this->FormaSeleccion($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);				
        return true;
    }

	function LlamaReporteCancelacionCitas()
    {
        $this->FormaReportesCancelacionCitas($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }
		
		function LlamaReporteHCAbiertasCerradas()
    {		
        $this->FormaReporteHCAbiertasCerradas($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }
    /**
    * Funcion que hace el llamdo para hacer la creacion del buscador del reporte
    *
    * @return boolean
    */
		function LlamaReporteCancelacionCitasConsolidado()
    {
      $pln = AutoCarga::factory('Afiliados','classes','app','Reportes_Consulta_Externa');
      $planes = $pln->ObtenerPlanes();
      
      $this->FormaReportesCancelacionCitasConsolidado($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel'],$planes);
      return true;
    }
    /**
    * Funcion que hace el llamdo para hacer la creacion del buscador del reporte
    *
    * @return boolean
    */
		function LlamaReporteCancelacionCitasConsolidadoEntidad()
    {
      $pln = AutoCarga::factory('Afiliados','classes','app','Reportes_Consulta_Externa');
      $planes = $pln->ObtenerPlanes();

      $this->FormaReportesCancelacionCitasConsolidadoEntidad($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel'],$planes);
      return true;
    }

    function LlamaReporteEstadisticasCausasTipo()
    {
        $this->FormaReporteEstadisticasCausasTipo($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }

    function LlamaReporteCausasCitasMedicas()
    {
        $this->FormaReporteCausasCitasMedicas($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }
    
    function LlamaReporteEstadisticoCaracteristicasPacientes()
    {
        $this->FormaReporteCaracteristicasPacientes($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }

    function LlamaReporteEstadisticoOrdenesServicio()
    {		
        $this->FormaReporteEstadisticoOrdenesServicio($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }

    function LlamaReporteEstadisticoRendimientoProf()
    {
      $this->ReporteEstadisticoRendimientoProf($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
      return true;
    }

    function LlamaReporteEstadisticoRendimientoPersonal()
    {
      $pln = AutoCarga::factory('Afiliados','classes','app','Reportes_Consulta_Externa');
      $planes = $pln->ObtenerPlanes();

      $this->ReporteEstadisticoRendimientoPersonal($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel'],$planes);
      return true;
    }

    function LlamaReporteEstadisticoOportunidadCE()
    {
      $this->ReporteEstadisticoOportunidadCE($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
      return true;
    }
    
    function LlamaReporteEstadisticoCitasTratamientoOdontologico()
    {
        $this->FormaReporteCitasTratamientoOdontologico($_REQUEST['centroutilidad'],$_REQUEST['centroU'],$_REQUEST['unidadfunc'],$_REQUEST['unidadF'],$_REQUEST['departamento'],$_REQUEST['DptoSel']);
        return true;
    }

    function BuscarDepartamento()//Esta ligado solo a Consulta Externa
    {
        if($_SESSION['recoex']['centroutil']){
          $centro=" AND A.centro_utilidad='".$_SESSION['recoex']['centroutil']."'";
				}
				if($_SESSION['recoex']['unidadfun']){
          $unifun=" AND A.unidad_funcional='".$_SESSION['recoex']['unidadfun']."'";
				}
        list($dbconn) = GetDBconn();
        $query = "SELECT A.departamento,
        A.descripcion
        FROM departamentos AS A
        WHERE A.empresa_id='".$_SESSION['recoex']['empresa']."' $centro $unifun
        ORDER BY A.descripcion;";//A.servicio='3' AND
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $dpt[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $dpt;
    }
	function ObtenerSWCargosDepto($dpto)
    {
	  list($dbconn) = GetDBconn();
      $query  = "SELECT sw_cargos_adicionales 
                 FROM   departamentos 
                 WHERE  departamento = '".$dpto."'";
            
      $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $dpt[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $dpt;
    }
	function CargosCitasAdicionales ($cita_id){
	
	list($dbconn) = GetDBconn();
	
	$query ="   select CA.cargo, CU.descripcion
				from cargos_adicionales_citas CA LEFT JOIN cups CU 
				ON(CU.cargo= CA.cargo)
				where agenda_cita_asignada_id =".$cita_id.";";
				
				$result = $dbconn->Execute($query);
				
				
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$i=0;
		while (!$result->EOF)
		{
			$datos[0][$i]=$result->fields[0];
			$datos[1][$i]=$result->fields[1];
			$i++;
			$result->MoveNext();
		}
		if($i<>0)
		{
			return $datos;
		}
		else
		{
			return false;
		}
	}

    function BuscarTipoConsultas($depto)
    {
		    if($depto && $depto!=-1){
				  $valor=explode(',',$depto);
          $dpto=" WHERE a.departamento='".$valor[0]."'";
				}
        list($dbconn) = GetDBconn();
        $query = "SELECT a.tipo_consulta_id,
        a.departamento,
        a.especialidad,
        a.descripcion
        FROM tipos_consulta a $dpto
        ORDER BY a.descripcion;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $Tipo_con;
    }

    function BuscarTipoCitas()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT *  
        		   FROM tipos_cita
                  ORDER BY tipo_cita ASC;";
        
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $Tipo_con;
    }   
    
    function BuscarProf($depto,$tipoconsul)
    {
        if($depto && $depto!=-1){
				  $valor=explode(',',$depto);
          $consulfrom=",profesionales_departamentos C";
          $consulwhere=" AND C.departamento='".$valor[0]."' AND C.tipo_id_tercero=B.tipo_id_tercero AND C.tercero_id=B.tercero_id";
				}
				if($tipoconsul && $tipoconsul!=-1){
				  $valor=explode(',',$tipoconsul);
          $consulfrom=",tipos_consulta D,profesionales_especialidades E";
					$consulwhere=" AND D.tipo_consulta_id='".$valor[0]."' AND D.especialidad=E.especialidad AND E.tipo_id_tercero=B.tipo_id_tercero AND E.tercero_id=B.tercero_id";
          if($depto && $depto!=-1){
					$valor=explode(',',$depto);
					$consulfrom.=",profesionales_departamentos C";
          $consulwhere.=" AND C.departamento='".$valor[0]."' AND D.departamento=C.departamento  AND C.tipo_id_tercero=B.tipo_id_tercero AND C.tercero_id=B.tercero_id";
					}
				}
				
				if($_SESSION['recoex']['auditor']!=1){
					$fil=" AND X.usuario_id='".UserGetUID()."'";
				}
        list($dbconn) = GetDBconn();
        $query = "SELECT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero, B.usuario_id
        FROM profesionales_empresas AS A,
        terceros AS B, profesionales_usuarios X $consulfrom
        WHERE A.tercero_id=B.tercero_id
        AND A.tipo_id_tercero=B.tipo_id_tercero 
        AND A.empresa_id='".$_SESSION['recoex']['empresa']."' 
				AND A.tipo_id_tercero=X.tipo_tercero_id
				AND A.tercero_id=X.tercero_id $fil 
				$consulwhere
        ORDER BY B.nombre_tercero;";
        
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $Tipo_con;
    }


		function LlamaReportesAgendaMedica()
    {
     $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['filtro']=$_REQUEST['filtro'];
			$_SESSION['recoex']['razonso']=$_REQUEST['DesEmpresa'];
			$_SESSION['recoex']['empresa']=$_REQUEST['Empresa'];
      if($_REQUEST['filtro']==1){
				if($_REQUEST['deptoDisabled']==-1){$_SESSION['recoex']['disabled']['depto']=1;}
				if($_REQUEST['tipoconsulDisabled']==-1){$_SESSION['recoex']['disabled']['tipoconsul']=1;}
        if($_REQUEST['profesionalDisabled']==-1){$_SESSION['recoex']['disabled']['profesional']=1;}
        $this->FormaSeleccion();
				return true;
			}else{
			  $_REQUEST['depto']=$_REQUEST['depto'];
				$_REQUEST['tipoconsul']=$_REQUEST['tipoconsul'];
				$_REQUEST['profesional']=$_REQUEST['profesional'];
				$_REQUEST['feinictra']=$_REQUEST['feinictra'];
				$_REQUEST['fefinctra']=$_REQUEST['fefinctra'];
        $this->LlamaFormaAgendaMedica();
				return true;
			}
		}


    function LlamaFormaAgendaMedica()
    {
		if(!empty($_REQUEST['centroU'])){
               $sql_centro = " AND dpto.centro_utilidad = '".$_REQUEST['centroU']."'";
          }
          if(!empty($_REQUEST['unidadF'])){
               $sql_unidad = " AND dpto.unidad_funcional = '".$_REQUEST['unidadF']."'";
          }
          if(!empty($_REQUEST['depto'])){
              $dpto=explode(',',$_REQUEST['depto']);
              $sql_dpto = " AND dpto.departamento = '".$dpto[0]."'";
          }		
        if(!empty($_REQUEST['feinictra']))
        {
            $fechas=explode('/',$_REQUEST['feinictra']);
            $day=$fechas[0];
            $mon=$fechas[1];
            $yea=$fechas[2];
            if(!(checkdate($mon, $day, $yea)==0))
            {
                //$fech=date ("Y-m-d");
                //if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                //{
                    $_SESSION['recone']['fechadesde']=$yea.'-'.$mon.'-'.$day;
                //}
                //else
                //{
                    //$_POST['feinictra']='';
                    //$this->frmError["feinictra"]=1;
                //}
            }
            else
            {
                $_REQUEST['feinictra']='';
                $this->frmError["feinictra"]=1;
            }
        }
        else
        {
            $this->frmError["feinictra"]=1;
        }
        if(!empty($_REQUEST['fefinctra']))
        {
            $fechas=explode('/',$_REQUEST['fefinctra']);
            $day=$fechas[0];
            $mon=$fechas[1];
            $yea=$fechas[2];
            if(!(checkdate($mon, $day, $yea)==0))
            {
                $fech=date ("Y-m-d");
                //if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                //{
                    if($_SESSION['recone']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_SESSION['recone']['fechahasta']=$yea.'-'.$mon.'-'.$day;
                    }
                    else
                    {
                        $_REQUEST['fefinctra']='';
                        $this->frmError["fefinctra"]=1;
                    }
                //}
                //else
                //{
                    //$_POST['fefinctra']='';
                    //$this->frmError["fefinctra"]=1;
                //}
            }
            else
            {
                $_REQUEST['fefinctra']='';
                $this->frmError["fefinctra"]=1;
            }
        }
        else
        {
            $this->frmError["fefinctra"]=1;
        }
        if($this->frmError["feinictra"]==1)// OR $this->frmError["fefinctra"]==1
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
            $this->FormaSeleccion();
            return true;
        }
        /*if(!empty($_POST['depto']) && $_POST['depto']!=-1)
        {
            $a=explode(',',$_POST['depto']);
            $busqueda1="JOIN profesionales_departamentos AS D ON
            (A.tipo_id_tercero=D.tipo_id_tercero
            AND A.tercero_id=D.tercero_id
            AND D.departamento='".$a[0]."')";
            $_SESSION['recone']['codigodepa']=$a[0];
            $_SESSION['recone']['descridepa']=$a[1];
        }*/
        if(!empty($_REQUEST['tipoconsul']) && $_REQUEST['tipoconsul']!=-1)
        {
            $a=explode(',',$_REQUEST['tipoconsul']);
            $_SESSION['recone']['codigotico']=$a[0];
            $_SESSION['recone']['descritico']=$a[1];
            $busqueda3="AND X.tipo_consulta_id='".$_SESSION['recone']['codigotico']."'";
        }
        if(!empty($_REQUEST['profesional']) && $_REQUEST['profesional']!=-1)
        {
            $a=explode(',',$_REQUEST['profesional']);
            $busqueda2="AND A.tipo_id_tercero='".$a[0]."'
            AND A.tercero_id='".$a[1]."'";
            $_SESSION['recone']['tipodocume']=$a[0];
            $_SESSION['recone']['documentos']=$a[1];
            $_SESSION['recone']['nombreprof']=$a[2];
        }
        if($_SESSION['recone']['fechadesde']<>NULL)
        {
            $busqueda4="AND X.fecha_turno>='".$_SESSION['recone']['fechadesde']."'";
        }
        if($_SESSION['recone']['fechahasta']<>NULL)
        {
            $busqueda5="AND X.fecha_turno<='".$_SESSION['recone']['fechahasta']."'";
        }
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $query = "SELECT  a.*
                  FROM    (
                            SELECT DISTINCT A.tipo_id_tercero,
                                    A.tercero_id,
                                    B.nombre_tercero,
                                    G.estado,
                                    X.agenda_turno_id,
                                    X.fecha_turno,
                                    X.duracion,
                                    X.tipo_consulta_id,
                                    X.consultorio_id,
                                    Y.descripcion,
                                    dpto.empresa_id,
                                    dpto.centro_utilidad,
                                    dpto.unidad_funcional,
                                    dpto.departamento
                            FROM    profesionales AS A,        
                                    terceros AS B,
                                    profesionales_estado AS G,
                                    agenda_turnos AS X,
                                    tipos_consulta AS Y,
                                    profesionales_departamentos  AS C,
                                    departamentos AS dpto
                            WHERE   A.tipo_id_tercero=B.tipo_id_tercero
                            AND     A.tercero_id=B.tercero_id
                            AND     A.tipo_id_tercero=G.tipo_id_tercero
                            AND     A.tercero_id=G.tercero_id
                            AND     A.tipo_id_tercero=X.tipo_id_profesional
                            AND     A.tercero_id=X.profesional_id
                            AND     X.empresa_id='".$_SESSION['recoex']['empresa']."'
                    				AND     X.empresa_id=G.empresa_id
                    				AND     X.tipo_consulta_id=Y.tipo_consulta_id
                    				AND     X.sw_estado_cancelacion='0' 
                            AND     A.tipo_id_tercero=C.tipo_id_tercero
                            AND     A.tercero_id=C.tercero_id
                            AND     C.departamento=dpto.departamento
                            AND     G.departamento=dpto.departamento
                            
        $busqueda2
				$busqueda3
        $busqueda4
        $busqueda5
        $sql_centro
        $sql_unidad
        $sql_dpto
        ORDER BY X.fecha_turno,A.tipo_id_tercero, A.tercero_id) as a,userpermisos_repconsultaexterna rep   
				WHERE 
				a.empresa_id=rep.empresa_id
				AND a.centro_utilidad=rep.centro_utilidad
				AND a.unidad_funcional=rep.unidad_funcional
				AND a.departamento=rep.departamento
				AND rep.usuario_id='".UserGetUID()."'
				;"; 			    
        $resulta = $dbconn->Execute($query);        
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $_SESSION['recone']['razonso']=$_SESSION['recoex']['razonso'];
				$_SESSION['recone']['empresa']=$_SESSION['recoex']['empresa'];
        $_SESSION['recon1']['datos']=$Tipo_con;
        $this->FormaAgendaMedica();
        return true;
    }

    function BuscarFormaDetalleAgenda($turno)
    {
        list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
        $query = "SELECT  C.hora,
                  		  C.sw_estado as sw_agenda_citas,
                          D.tipo_id_paciente,
                          D.paciente_id,
                          E.primer_apellido ||' '|| E.segundo_apellido ||' '|| E.primer_nombre ||' '|| E.segundo_nombre AS nombre,
                  		  E.residencia_telefono,
                          CASE WHEN E.fecha_nacimiento IS NOT NULL THEN edad_completa(E.fecha_nacimiento)
                               ELSE '' END AS edad_paciente,
                          plan.plan_descripcion,
                          plan.plan_id,
                          D.sw_atencion,
                          os_maes.sw_estado,
                          D.agenda_cita_id_padre,
                  				extract(HOUR FROM evol.fecha)||':'||extract(MINUTE FROM evol.fecha) as fecha_abre,
                  				extract(HOUR FROM evol.fecha_cierre)||':'||extract(MINUTE FROM evol.fecha_cierre) as fecha_cierre,
                  				extract(HOUR FROM (evol.fecha_cierre-evol.fecha))||':'||extract(MINUTE FROM (evol.fecha_cierre-evol.fecha)) as fecha_duracion,
                  				tipocancel.descripcion as tipocancel,
                          cancel.observacion as obsercancel,
                          cancel.fecha_registro as fechacancel,
						  D.agenda_cita_asignada_id,
                          usu.nombre as nombre_usuario
                          --,ctas.numerodecuenta,ctas.ingreso,evol.*
                  FROM		(
                            SELECT  x.agenda_cita_id,
                                    x.hora,
                                    x.sw_estado 
                            FROM    agenda_citas x 
                            WHERE   x.agenda_turno_id='".$turno."'
                          ) AS C
                          LEFT JOIN agenda_citas_asignadas AS D 
                          ON (C.agenda_cita_id=D.agenda_cita_id)
                  				LEFT JOIN agenda_citas_asignadas_cancelacion AS cancel 
                          ON (D.agenda_cita_asignada_id=cancel.agenda_cita_asignada_id)
                          LEFT JOIN system_usuarios AS usu 
                          ON (usu.usuario_id=cancel.usuario_id)
                  				LEFT JOIN tipos_cancelacion AS tipocancel 
                          ON (tipocancel.tipo_cancelacion_id=cancel.tipo_cancelacion_id)
                  				LEFT JOIN planes AS plan 
                          ON(D.plan_id=plan.plan_id)
                  				LEFT JOIN os_cruce_citas AS os_cruz 
                          ON(D.agenda_cita_asignada_id=os_cruz.agenda_cita_asignada_id)
                  				LEFT JOIN os_maestro AS os_maes 
                          ON(os_cruz.numero_orden_id=os_maes.numero_orden_id)
                  				LEFT JOIN cuentas AS ctas 
                          ON(os_maes.numerodecuenta=ctas.numerodecuenta)
                  				LEFT JOIN hc_evoluciones AS evol ON(ctas.ingreso=evol.ingreso)
                          LEFT JOIN pacientes AS E 
                          ON(D.tipo_id_paciente=E.tipo_id_paciente AND D.paciente_id=E.paciente_id)        
                    ORDER BY C.hora;";
				
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
        }
        while(!$resulta->EOF){
					$Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
        }
        return $Tipo_con;
    }

	function BusquedaIngresoPaciente($tipoid,$paciente){
		list($dbconn) = GetDBconn();
		$sql="select b.evolucion_id from ingresos as a, hc_evoluciones as b where a.ingreso=b.ingreso and a.tipo_id_paciente='$tipoid' and a.paciente_id='$paciente' and b.estado='0' limit 1 offset 0;";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}if($result->EOF){
			return 'Historia Vacia';
		}else{
			return $result->fields[0];
		}
	}

	function LlamaVerInfoCancelacion(){
    $this->VerInfoCancelacion($_REQUEST['tipocancel'],$_REQUEST['obsercancel'],$_REQUEST['fechacancel'],$_REQUEST['hora'],
		$_REQUEST['tipoIdPaciente'],$_REQUEST['PacienteId'],$_REQUEST['NombrePaciente'],$_REQUEST['fechaTurno'],$_REQUEST['nombreUsuario']);
		return true;
	}

	//Insercion de funciones para la imprimir resumen

	function FuncionParaImprimir()
	{
		$var = $_SESSION['BusquedaAgenda']['datos_impresion'];

		if(!IncludeFile("classes/reports/reports.class.php")){
			$this->error = "No se pudo inicializar la Clase de Reportes";
			$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
			return false;
    }
		$classReport = new reports;
		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
		$reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='AgendaBusqueda',$reporte_name='impresion_agenda',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
		if(!$reporte){
			$this->error = $classReport->GetError();
			$this->mensajeDeError = $classReport->MensajeDeError();
			unset($classReport);
			return false;
		}
		$resultado=$classReport->GetExecResultado();
		unset($classReport);
		if(!empty($resultado[codigo]))
		{
			"El PrintReport retorno : " . $resultado[codigo] . "<br>";
		}
		$this->FormaAgendaMedica();
		return true;
	}

	function CitasDia()
	{
		list($dbconn) = GetDBconn();
		$sql="SELECT a.*,c.paciente_id, c.tipo_id_paciente,
								 d.primer_nombre || ' ' || d.segundo_nombre || ' ' || d.primer_apellido || ' ' || d.segundo_apellido as nombre_completo,	
								 e.plan_id, e.plan_descripcion,c.cargo_cita, c.sw_atencion, h.sw_estado
					FROM
						(SELECT a.agenda_cita_id,a.hora, b.fecha_turno, a.agenda_turno_id,										
										b.tipo_consulta_id, f.descripcion, 
										i.tipo_id_tercero, i.tercero_id, i.nombre_tercero
						FROM agenda_citas as a,agenda_turnos as b ,tipos_servicios_ambulatorios as f, terceros as i
						WHERE b.profesional_id=i.tercero_id and b.tipo_id_profesional=i.tipo_id_tercero
						and a.agenda_turno_id=b.agenda_turno_id and b.profesional_id='".$_SESSION['recone']['documentos']."' and b.tipo_id_profesional='".$_SESSION['recone']['tipodocume']."'
						and date(b.fecha_turno)=date('".$_REQUEST['DiaEspe']."')
						and b.empresa_id='".$_SESSION['recoex']['empresa']."'
						and b.tipo_consulta_id=f.tipo_servicio_amb_id
						and a.sw_estado_cancelacion=0 order by a.hora, b.tipo_consulta_id) as a					
					LEFT JOIN agenda_citas_asignadas as c on (a.agenda_cita_id=c.agenda_cita_id)
					LEFT JOIN pacientes as d on(c.paciente_id=d.paciente_id and c.tipo_id_paciente=d.tipo_id_paciente)
					LEFT JOIN planes as e on(c.plan_id=e.plan_id)
					LEFT JOIN os_cruce_citas as g on(c.agenda_cita_asignada_id=g.agenda_cita_asignada_id)
					LEFT JOIN os_maestro as h on(g.numero_orden_id=h.numero_orden_id);";
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
				$cita[$i]=$result->GetRowAssoc(false);
				$i++;
				$result->MoveNext();
			}
		}
		if($i<>0)
		{
			return $cita;
		}
		else
		{
			return false;
		}
	}

	function LlamaReporteCitasCanceladas(){
		if(!empty($_REQUEST['centroU'])){
               $sql_centro = " AND dpto.centro_utilidad = '".$_REQUEST['centroU']."'";
          }
          if(!empty($_REQUEST['unidadF'])){
               $sql_unidad = " AND dpto.unidad_funcional = '".$_REQUEST['unidadF']."'";
          }
          if(!empty($_REQUEST['DptoSel'])){
               $sql_dpto = " AND dpto.departamento = '".$_REQUEST['DptoSel']."'";
          }	
		if(!empty($_REQUEST['feinictra'])){
			$fechas=explode('/',$_REQUEST['feinictra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
			  $_SESSION['reconecc']['fechadesde']=$yea.'-'.$mon.'-'.$day;
		  }else{
			  $_REQUEST['feinictra']='';
				$this->frmError["feinictra"]=1;
		  }
		}else{
			$this->frmError["feinictra"]=1;
		}
		if(!empty($_REQUEST['fefinctra'])){
			$fechas=explode('/',$_REQUEST['fefinctra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
				$fech=date ("Y-m-d");
				if($_SESSION['reconecc']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea))){
				  $_SESSION['reconecc']['fechahasta']=$yea.'-'.$mon.'-'.$day;
				}else{
					$_REQUEST['fefinctra']='';
					$this->frmError["fefinctra"]=1;
				}
			}else{
				$_REQUEST['fefinctra']='';
				$this->frmError["fefinctra"]=1;
			}
		}else{
			$this->frmError["fefinctra"]=1;
		}
		if($this->frmError["feinictra"]==1){
		  $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
			$this->FormaReportesCancelacionCitas();
			return true;
		}		
		if(!empty($_REQUEST['justificacion']) && $_REQUEST['justificacion']!=-1){
			$a=explode(',',$_REQUEST['justificacion']);
			$_SESSION['reconecc']['justificacionId']=$a[0];
			$_SESSION['reconecc']['justificacion']=$a[1];
			$justifiFiltro=" AND a.tipo_cancelacion_id='".$a[0]."'";
    }
    if(!empty($_REQUEST['tipoconsul']) && $_REQUEST['tipoconsul']!=-1){
			$a=explode(',',$_REQUEST['tipoconsul']);
			$_SESSION['reconecc']['codigotico']=$a[0];
			$_SESSION['reconecc']['descritico']=$a[1];
			$TipoConsulFiltro=" AND e.tipo_consulta_id='".$_SESSION['reconecc']['codigotico']."'";
    }
    if(!empty($_REQUEST['profesional']) && $_REQUEST['profesional']!=-1){
			$a=explode(',',$_REQUEST['profesional']);
			$_SESSION['reconecc']['tipodocume']=$a[0];
			$_SESSION['reconecc']['documentos']=$a[1];
			$_SESSION['reconecc']['nombreprof']=$a[2];
			$ProfeFiltro=" AND e.tipo_id_profesional='".$_SESSION['reconecc']['tipodocume']."' AND e.profesional_id='".$_SESSION['reconecc']['documentos']."'";
    }
		if($_SESSION['reconecc']['fechadesde']<>NULL){
		  $fechaInFiltro="AND e.fecha_turno>='".$_SESSION['reconecc']['fechadesde']."'";
		}
		if($_SESSION['reconecc']['fechahasta']<>NULL){
		  $fechaFnFiltro="AND e.fecha_turno<='".$_SESSION['reconecc']['fechahasta']."'";
		}
		$_SESSION['reconecc']['razonso']=$_SESSION['recoex']['razonso'];
		$_SESSION['reconecc']['empresa']=$_SESSION['recoex']['empresa'];
    list($dbconn) = GetDBconn();
		$query="SELECT a.*
		FROM (SELECT DISTINCT g.tipo_id_tercero||'-'||g.tercero_id as identificacionprof,a.tipo_cancelacion_id,c.tipo_id_paciente||'-'||c.paciente_id as identificacionpac,d.agenda_cita_id,g.nombre_tercero,h.estado,i.descripcion as especialidad,
			e.fecha_turno,e.duracion,e.consultorio_id,d.hora,
			pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre,b.descripcion as tipojustificacion,
			a.fecha_registro as fechacancelacion,dpto.empresa_id,dpto.centro_utilidad,dpto.unidad_funcional,dpto.departamento
			FROM agenda_citas_asignadas_cancelacion a,tipos_cancelacion b,
			agenda_citas_asignadas c,agenda_citas d,
			agenda_turnos e,terceros g,profesionales_estado h,tipos_consulta i,
			pacientes pac, profesionales_departamentos f,departamentos dpto
			WHERE a.tipo_cancelacion_id=b.tipo_cancelacion_id AND
			a.agenda_cita_asignada_id=c.agenda_cita_asignada_id AND
			c.agenda_cita_id=d.agenda_cita_id AND d.agenda_turno_id=e.agenda_turno_id AND
			g.tipo_id_tercero=e.tipo_id_profesional AND g.tercero_id=e.profesional_id AND
			g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
			e.tipo_consulta_id=i.tipo_consulta_id AND pac.paciente_id=c.paciente_id AND
			pac.tipo_id_paciente=c.tipo_id_paciente AND
			e.tipo_id_profesional=f.tipo_id_tercero AND e.profesional_id=f.tercero_id AND
			f.departamento=dpto.departamento AND h.departamento=f.departamento
			AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'	
			$TipoConsulFiltro
			$ProfeFiltro
			$fechaInFiltro
			$fechaFnFiltro
			$justifiFiltro          
						$sql_centro
						$sql_unidad
						$sql_dpto) as a,userpermisos_repconsultaexterna rep 	
			WHERE			
			a.empresa_id=rep.empresa_id			
			AND a.centro_utilidad=rep.centro_utilidad
			AND a.unidad_funcional=rep.unidad_funcional
			AND a.departamento=rep.departamento
			AND rep.usuario_id='".UserGetUID()."'
					";   
		
		
				
		
		
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($resulta->RecordCount()>0){
				while(!$resulta->EOF){
					$Tipo_con[$resulta->fields[0]][$resulta->fields[1]][$resulta->fields[2]][$resulta->fields[3]]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
		}		
		$_SESSION['CITAS_CANCELADAS']['DATOS']=$Tipo_con;
    $this->ReporteCitasCanceladas();
		return true;
	}
	
	function LlamaReporteHCAbiertasyCerradas(){
		if(!empty($_REQUEST['centroU'])){
               $sql_centro = " AND dpto.centro_utilidad = '".$_REQUEST['centroU']."'";
          }
          if(!empty($_REQUEST['unidadF'])){
               $sql_unidad = " AND dpto.unidad_funcional = '".$_REQUEST['unidadF']."'";
          }
          if(!empty($_REQUEST['DptoSel'])){
               $sql_dpto = " AND dpto.departamento = '".$_REQUEST['DptoSel']."'";
          }	
		if(!empty($_REQUEST['feinictra'])){
			$fechas=explode('/',$_REQUEST['feinictra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
			  $_SESSION['reconecc']['fechadesde']=$yea.'-'.$mon.'-'.$day;
		  }else{
			  $_REQUEST['feinictra']='';
				$this->frmError["feinictra"]=1;
		  }
		}else{
			$this->frmError["feinictra"]=1;
		}
		if(!empty($_REQUEST['fefinctra'])){
			$fechas=explode('/',$_REQUEST['fefinctra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
				$fech=date ("Y-m-d");
				if($_SESSION['reconecc']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea))){
				  $_SESSION['reconecc']['fechahasta']=$yea.'-'.$mon.'-'.$day;
				}else{
					$_REQUEST['fefinctra']='';
					$this->frmError["fefinctra"]=1;
				}
			}else{
				$_REQUEST['fefinctra']='';
				$this->frmError["fefinctra"]=1;
			}
		}else{
			$this->frmError["fefinctra"]=1;
		}
		if($this->frmError["feinictra"]==1){
		  $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
			$this->FormaReporteHCAbiertasCerradas();
			return true;
		}	
		
    if(!empty($_REQUEST['tipoconsul']) && $_REQUEST['tipoconsul']!=-1){
			$a=explode(',',$_REQUEST['tipoconsul']);
			$_SESSION['reconecc']['codigotico']=$a[0];
			$_SESSION['reconecc']['descritico']=$a[1];
			$TipoConsulFiltro=" AND e.tipo_consulta_id='".$_SESSION['reconecc']['codigotico']."'";
    }
    if(!empty($_REQUEST['profesional']) && $_REQUEST['profesional']!=-1){
			$a=explode(',',$_REQUEST['profesional']);
			$_SESSION['reconecc']['tipodocume']=$a[0];
			$_SESSION['reconecc']['documentos']=$a[1];
			$_SESSION['reconecc']['nombreprof']=$a[2];
			$ProfeFiltro=" AND e.tipo_id_profesional='".$_SESSION['reconecc']['tipodocume']."' AND e.profesional_id='".$_SESSION['reconecc']['documentos']."'";
    }
		if($_SESSION['reconecc']['fechadesde']<>NULL){
		  $fechaInFiltro=" AND e.fecha_turno>='".$_SESSION['reconecc']['fechadesde']."'";
		}
		if($_SESSION['reconecc']['fechahasta']<>NULL){
		  $fechaFnFiltro="AND e.fecha_turno<='".$_SESSION['reconecc']['fechahasta']."'";
		}
		$_SESSION['reconecc']['razonso']=$_SESSION['recoex']['razonso'];
		$_SESSION['reconecc']['empresa']=$_SESSION['recoex']['empresa'];
    list($dbconn) = GetDBconn();		
		
		/*$query="SELECT a.*
		FROM (SELECT a.identificacionprof,g.nombre_tercero,h.estado,i.descripcion as especialidad,
		a.hc_abiertass,a.hc_cerradass,dpto.empresa_id,dpto.centro_utilidad,dpto.unidad_funcional,dpto.departamento
		
		FROM (SELECT a.identificacionprof,a.tipo_consulta_id,sum(a.hc_abiertass) as hc_abiertass,sum(a.hc_cerradass) as hc_cerradass
		FROM (SELECT e.tipo_id_profesional||'-'||e.profesional_id as identificacionprof,e.tipo_consulta_id,		
							(SELECT count(*)
							FROM agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc
							WHERE x.agenda_turno_id=e.agenda_turno_id AND 
							x.agenda_cita_id=y.agenda_cita_id AND y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
							z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
							hc.estado='1'  
							) as hc_abiertass,
							(SELECT count(*)
							FROM agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc
							WHERE x.agenda_turno_id=e.agenda_turno_id AND 
							x.agenda_cita_id=y.agenda_cita_id AND y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
							z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
							hc.estado='0'  
							) as hc_cerradass	
		FROM agenda_turnos e		
		WHERE e.sw_estado_cancelacion='0'
		$ProfeFiltro
		$fechaInFiltro
    $fechaFnFiltro		       		
		) as a
		WHERE a.hc_abiertass > 0 OR a.hc_cerradass > 0
		GROUP BY a.identificacionprof,a.tipo_consulta_id) as a,
		terceros g,profesionales_estado h,tipos_consulta i,
		profesionales_departamentos f,departamentos dpto		
		WHERE a.identificacionprof=g.tipo_id_tercero||'-'||g.tercero_id AND 
		a.identificacionprof=h.tipo_id_tercero||'-'||h.tercero_id AND
		a.tipo_consulta_id=i.tipo_consulta_id AND 
		a.identificacionprof=f.tipo_id_tercero||'-'||f.tercero_id AND
    f.departamento=dpto.departamento AND h.departamento=f.departamento
		AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
		$sql_centro
		$sql_unidad
		$sql_dpto) as a,userpermisos_repconsultaexterna rep
		WHERE
		a.empresa_id=rep.empresa_id
		AND a.centro_utilidad=rep.centro_utilidad
		AND a.unidad_funcional=rep.unidad_funcional
		AND a.departamento=rep.departamento
		AND rep.usuario_id='".UserGetUID()."'";	
		*/
		
		$query="		
			SELECT prof.tipo_id_profesional||'-'||prof.profesional_id as identificacionprof,
			prof.nombre_tercero,hc_abiertas.cantidad as hc_abiertass,hc_cerradas.cantidad as hc_cerradass,
			prof.estado,prof.especialidad 
			FROM
				(SELECT e.tipo_id_profesional,e.profesional_id,g.nombre_tercero,h.estado,i.descripcion as especialidad
				
				FROM agenda_turnos e,agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc,
				terceros g,profesionales_estado h,tipos_consulta i,profesionales_departamentos f,departamentos dpto
				
				
				WHERE e.agenda_turno_id=x.agenda_turno_id AND x.agenda_cita_id=y.agenda_cita_id AND 
				y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
				z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
				e.tipo_id_profesional=g.tipo_id_tercero AND e.profesional_id=g.tercero_id AND 
				g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
				e.tipo_consulta_id=i.tipo_consulta_id AND
				h.tipo_id_tercero=f.tipo_id_tercero AND h.tercero_id=f.tercero_id AND
				f.departamento=dpto.departamento AND h.departamento=f.departamento
				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
				$ProfeFiltro
				$fechaInFiltro
				$fechaFnFiltro
				
				$sql_centro
				$sql_unidad
				$sql_dpto 
				GROUP BY e.tipo_id_profesional,e.profesional_id,g.nombre_tercero,h.estado,i.descripcion
				
				) as prof
			 	LEFT JOIN  
				(SELECT e.tipo_id_profesional,e.profesional_id,count(*) as cantidad
				
				FROM agenda_turnos e,agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc,
				terceros g,profesionales_estado h,tipos_consulta i,profesionales_departamentos f,departamentos dpto
				
				WHERE e.agenda_turno_id=x.agenda_turno_id AND x.agenda_cita_id=y.agenda_cita_id AND 
				y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
				z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
				hc.estado='1' AND e.tipo_id_profesional=g.tipo_id_tercero AND e.profesional_id=g.tercero_id AND 
				g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
				e.tipo_consulta_id=i.tipo_consulta_id AND 
				h.tipo_id_tercero=f.tipo_id_tercero AND h.tercero_id=f.tercero_id AND
				f.departamento=dpto.departamento AND h.departamento=f.departamento
				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
				$ProfeFiltro
				$fechaInFiltro
				$fechaFnFiltro
				
				$sql_centro
				$sql_unidad
				$sql_dpto 
				GROUP BY e.tipo_id_profesional,e.profesional_id
				
				) as hc_abiertas ON (prof.tipo_id_profesional=hc_abiertas.tipo_id_profesional AND prof.profesional_id=hc_abiertas.profesional_id)
				LEFT JOIN
				(SELECT e.tipo_id_profesional,e.profesional_id,count(*) as cantidad
				
				FROM agenda_turnos e,agenda_citas x,agenda_citas_asignadas y,os_cruce_citas z,os_maestro os,hc_evoluciones hc,
				terceros g,profesionales_estado h,tipos_consulta i,profesionales_departamentos f,departamentos dpto
				
				WHERE e.agenda_turno_id=x.agenda_turno_id AND x.agenda_cita_id=y.agenda_cita_id AND 
				y.agenda_cita_asignada_id=z.agenda_cita_asignada_id AND
				z.numero_orden_id=os.numero_orden_id AND os.numerodecuenta=hc.numerodecuenta AND
				hc.estado='0' AND e.tipo_id_profesional=g.tipo_id_tercero AND e.profesional_id=g.tercero_id AND 
				g.tipo_id_tercero=h.tipo_id_tercero AND g.tercero_id=h.tercero_id AND
				e.tipo_consulta_id=i.tipo_consulta_id AND 
				h.tipo_id_tercero=f.tipo_id_tercero AND h.tercero_id=f.tercero_id AND
				f.departamento=dpto.departamento AND h.departamento=f.departamento
				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'		
				$ProfeFiltro
				$fechaInFiltro
				$fechaFnFiltro
				
				$sql_centro
				$sql_unidad
				$sql_dpto 
				GROUP BY e.tipo_id_profesional,e.profesional_id
				
				) as hc_cerradas ON (prof.tipo_id_profesional=hc_cerradas.tipo_id_profesional AND prof.profesional_id=hc_cerradas.profesional_id)
			WHERE (hc_abiertas.cantidad > 0 OR hc_cerradas.cantidad > 0)";	
		
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      if($resulta->RecordCount()>0){
				while(!$resulta->EOF){
					$Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
			}
		}		
		$_SESSION['CITAS_CANCELADAS']['DATOS']=$Tipo_con;
    $this->ReporteHCAbiertasCerradas();
		return true;
	}
	
  	function LlamaReporteCitasCanceladasConsolidado()
    {
  		if(!empty($_REQUEST['centroU']))
        $sql_centro = " AND dpto.centro_utilidad = '".$_REQUEST['centroU']."'";
      
      if(!empty($_REQUEST['unidadF']))
        $sql_unidad = " AND dpto.unidad_funcional = '".$_REQUEST['unidadF']."'";
      
      if(!empty($_REQUEST['DptoSel']))
        $sql_dpto = " AND dpto.departamento = '".$_REQUEST['DptoSel']."'";
  		
      if(!empty($_REQUEST['feinictra']))
      {
  			$fechas=explode('/',$_REQUEST['feinictra']);
  			$day=$fechas[0];
  			$mon=$fechas[1];
  			$yea=$fechas[2];
  			if(!(checkdate($mon, $day, $yea)==0))
        {
  			  $_SESSION['reconecc']['fechadesde']=$yea.'-'.$mon.'-'.$day;
  		  }
        else
        {
  			  $_REQUEST['feinictra']='';
  				$this->frmError["feinictra"]=1;
  		  }
  		}
      else
      {
  			$this->frmError["feinictra"]=1;
  		}
  		if(!empty($_REQUEST['fefinctra']))
      {
  			$fechas=explode('/',$_REQUEST['fefinctra']);
  			$day=$fechas[0];
  			$mon=$fechas[1];
  			$yea=$fechas[2];
  			if(!(checkdate($mon, $day, $yea)==0)){
  				$fech=date ("Y-m-d");
  				if($_SESSION['reconecc']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea))){
  				  $_SESSION['reconecc']['fechahasta']=$yea.'-'.$mon.'-'.$day;
  				}else{
  					$_REQUEST['fefinctra']='';
  					$this->frmError["fefinctra"]=1;
  				}
  			}else{
  				$_REQUEST['fefinctra']='';
  				$this->frmError["fefinctra"]=1;
  			}
  		}
      else
      {
  			$this->frmError["fefinctra"]=1;
  		}
  		if($this->frmError["feinictra"]==1)
      {
  		  $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
  			$this->FormaReportesCancelacionCitasConsolidado();
  			return true;
  		}		
  		if(!empty($_REQUEST['justificacion']) && $_REQUEST['justificacion']!=-1)
      {
  			$a=explode(',',$_REQUEST['justificacion']);
  			$_SESSION['reconecc']['justificacionId']=$a[0];
  			$_SESSION['reconecc']['justificacion']=$a[1];
  			$justifiFiltro=" AND a.tipo_cancelacion_id='".$a[0]."'";
      }
      if(!empty($_REQUEST['tipoconsul']) && $_REQUEST['tipoconsul']!=-1){
  			$a=explode(',',$_REQUEST['tipoconsul']);
  			$_SESSION['reconecc']['codigotico']=$a[0];
  			$_SESSION['reconecc']['descritico']=$a[1];
  			$TipoConsulFiltro=" AND e.tipo_consulta_id='".$_SESSION['reconecc']['codigotico']."'";
      }
      if(!empty($_REQUEST['profesional']) && $_REQUEST['profesional']!=-1){
  			$a=explode(',',$_REQUEST['profesional']);
  			$_SESSION['reconecc']['tipodocume']=$a[0];
  			$_SESSION['reconecc']['documentos']=$a[1];
  			$_SESSION['reconecc']['nombreprof']=$a[2];
  			$ProfeFiltro=" AND e.tipo_id_profesional='".$_SESSION['reconecc']['tipodocume']."' AND e.profesional_id='".$_SESSION['reconecc']['documentos']."'";
      }
  		if($_SESSION['reconecc']['fechadesde']<>NULL){
  		  $fechaInFiltro="AND e.fecha_turno>='".$_SESSION['reconecc']['fechadesde']."'";
  		}
  		if($_SESSION['reconecc']['fechahasta']<>NULL){
  		  $fechaFnFiltro="AND e.fecha_turno<='".$_SESSION['reconecc']['fechahasta']."'";
  		}
  		$_SESSION['reconecc']['razonso']=$_SESSION['recoex']['razonso'];
  		$_SESSION['reconecc']['empresa']=$_SESSION['recoex']['empresa'];
      
  		list($dbconn) = GetDBconn();
      
  		GLOBAL $ADODB_FETCH_MODE;					
      $query  = "SELECT DISTINCT a.*,";
      $query .= "       ter.nombre_tercero,";
      $query .= "       i.descripcion as especialidad,";
      $query .= "       h.estado,";
      $query .= "       b.descripcion as tipojustificacion ";
      $query .= "FROM		(";
      $query .= "          SELECT e.tipo_id_profesional,";
      $query .= "                 e.profesional_id,";
      $query .= "                 e.tipo_consulta_id,";
      $query .= "                 a.tipo_cancelacion_id,";
      $query .= "                 count(*) as cantidad	";
      $query .= "          FROM   agenda_citas_asignadas_cancelacion a,";
      $query .= "                 tipos_cancelacion b,";
      $query .= "                 agenda_citas_asignadas c,  ";
      $query .= "                 agenda_citas d, ";
      $query .= "                 agenda_turnos e   ";  			
      $query .= "          WHERE  a.tipo_cancelacion_id=b.tipo_cancelacion_id  ";
      $query .= "          AND  	a.agenda_cita_asignada_id=c.agenda_cita_asignada_id  ";
      $query .= "          AND  	c.agenda_cita_id=d.agenda_cita_id  ";
      $query .= "          AND    d.agenda_turno_id=e.agenda_turno_id    ";
      if($_REQUEST['plan_afiliacion'] != '-1')
      {
        $query .= "               AND   c.plan_id = ".$_REQUEST['plan_afiliacion']." ";
        $_SESSION['reconecc']['plan_afiliacion'] = $_REQUEST['plan_afiliacion'];
        $_SESSION['reconecc']['descripcion_plan'] = $_REQUEST['descripcion_plan'];
      }
      $query .= "          ".$TipoConsulFiltro;
      $query .= "          ".$ProfeFiltro;
      $query .= "          ".$fechaInFiltro;
      $query .= "          ".$fechaFnFiltro;
      $query .= "          ".$justifiFiltro ;         		 
      $query .= "          GROUP BY e.tipo_id_profesional,e.profesional_id,e.tipo_consulta_id,a.tipo_cancelacion_id ";
      $query .= "       ) a, ";
      $query .= "       terceros ter, ";
      $query .= "       tipos_consulta i, ";
      $query .= "       profesionales_estado h, ";
      $query .= "       profesionales_departamentos f, ";
      $query .= "       departamentos dpto, ";
      $query .= "       userpermisos_repconsultaexterna rep, ";
      $query .= "       tipos_cancelacion b ";
      $query .= "WHERE  ter.tipo_id_tercero=a.tipo_id_profesional "; 
      $query .= "AND    ter.tercero_id=a.profesional_id ";
      $query .= "AND  	a.tipo_consulta_id=i.tipo_consulta_id ";
      $query .= "AND  	ter.tipo_id_tercero=h.tipo_id_tercero "; 
      $query .= "AND    ter.tercero_id=h.tercero_id ";
      $query .= "AND  	a.tipo_id_profesional=f.tipo_id_tercero ";
      $query .= "AND    a.profesional_id=f.tercero_id ";
      $query .= "AND    f.departamento=dpto.departamento ";
      $query .= "AND    h.departamento=f.departamento  ";
      $query .= "AND 		a.tipo_cancelacion_id=b.tipo_cancelacion_id ";
      $query .= "AND    dpto.empresa_id='".$_SESSION['recoex']['empresa']."' ";
      $query .= "AND    dpto.empresa_id=rep.empresa_id ";
      $query .= "AND    dpto.centro_utilidad=rep.centro_utilidad ";
      $query .= "AND    dpto.unidad_funcional=rep.unidad_funcional ";
      $query .= "AND    dpto.departamento=rep.departamento ";
      $query .= "AND    rep.usuario_id='".UserGetUID()."' ";
      $query .= " ".$sql_centro;
      $query .= " ".$sql_unidad;
      $query .= " ".$sql_dpto;	
  		
      
  		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $resulta = $dbconn->Execute($query);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
  		if($dbconn->ErrorNo() != 0){
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}else{      
  			while($datos=$resulta->FetchRow()){
  				$vector[$datos['tipo_id_profesional']][$datos['profesional_id']][$datos['nombre_tercero']][$datos['especialidad']][$datos['tipo_cancelacion_id']]=$datos;				
  			}			
  		}		

  		$_SESSION['CITAS_CANCELADAS_CONSOLIDADO']['DATOS']=$vector;
      $this->ReporteCitasCanceladasConsolidado();
  		return true;
  	}
	
    function LlamaReporteCitasCanceladasConsolidadoEntidad()
    {
      if(!empty($_REQUEST['centroU']))
      {
        $sql_centro = " AND dpto.centro_utilidad = '".$_REQUEST['centroU']."'";
      }
      if(!empty($_REQUEST['unidadF']))
      {
        $sql_unidad = " AND dpto.unidad_funcional = '".$_REQUEST['unidadF']."'";
      }
      if(!empty($_REQUEST['DptoSel']))
      {
        $sql_dpto = " AND dpto.departamento = '".$_REQUEST['DptoSel']."'";
      }	
      if(!empty($_REQUEST['feinictra']))
      {
  			$fechas=explode('/',$_REQUEST['feinictra']);
  			$day=$fechas[0];
  			$mon=$fechas[1];
  			$yea=$fechas[2];
  			if(!(checkdate($mon, $day, $yea)==0)){
  			  $_SESSION['reconecc']['fechadesde']=$yea.'-'.$mon.'-'.$day;
  		  }else{
  			  $_REQUEST['feinictra']='';
  				$this->frmError["feinictra"]=1;
  		  }
  		}
      else
      {
        $this->frmError["feinictra"]=1;
      }
  		if(!empty($_REQUEST['fefinctra']))
      {
  			$fechas=explode('/',$_REQUEST['fefinctra']);
  			$day=$fechas[0];
  			$mon=$fechas[1];
  			$yea=$fechas[2];
  			if(!(checkdate($mon, $day, $yea)==0)){
  				$fech=date ("Y-m-d");
  				if($_SESSION['reconecc']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea))){
  				  $_SESSION['reconecc']['fechahasta']=$yea.'-'.$mon.'-'.$day;
  				}else{
  					$_REQUEST['fefinctra']='';
  					$this->frmError["fefinctra"]=1;
  				}
  			}
        else
        {
  				$_REQUEST['fefinctra']='';
  				$this->frmError["fefinctra"]=1;
  			}
  		}
      else
      {
  			$this->frmError["fefinctra"]=1;
  		}
  		if($this->frmError["feinictra"]==1){
  		  $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
  			$this->FormaReportesCancelacionCitasConsolidadoEntidad();
  			return true;
  		}		
  		if(!empty($_REQUEST['justificacion']) && $_REQUEST['justificacion']!=-1){
  			$a=explode(',',$_REQUEST['justificacion']);
  			$_SESSION['reconecc']['justificacionId']=$a[0];
  			$_SESSION['reconecc']['justificacion']=$a[1];
  			$justifiFiltro=" AND a.tipo_cancelacion_id='".$a[0]."'";
      }
      if(!empty($_REQUEST['tipoconsul']) && $_REQUEST['tipoconsul']!=-1){
  			$a=explode(',',$_REQUEST['tipoconsul']);
  			$_SESSION['reconecc']['codigotico']=$a[0];
  			$_SESSION['reconecc']['descritico']=$a[1];
  			$TipoConsulFiltro=" AND e.tipo_consulta_id='".$_SESSION['reconecc']['codigotico']."'";
      }
      if(!empty($_REQUEST['profesional']) && $_REQUEST['profesional']!=-1){
  			$a=explode(',',$_REQUEST['profesional']);
  			$_SESSION['reconecc']['tipodocume']=$a[0];
  			$_SESSION['reconecc']['documentos']=$a[1];
  			$_SESSION['reconecc']['nombreprof']=$a[2];
  			$ProfeFiltro=" AND e.tipo_id_profesional='".$_SESSION['reconecc']['tipodocume']."' AND e.profesional_id='".$_SESSION['reconecc']['documentos']."'";
      }
  		if($_SESSION['reconecc']['fechadesde']<>NULL){
  		  $fechaInFiltro="AND e.fecha_turno>='".$_SESSION['reconecc']['fechadesde']."'";
  		}
  		if($_SESSION['reconecc']['fechahasta']<>NULL){
  		  $fechaFnFiltro="AND e.fecha_turno<='".$_SESSION['reconecc']['fechahasta']."'";
  		}
      
      $fplan = "";
      if($_REQUEST['plan_afiliacion'] != '-1' && $_REQUEST['plan_afiliacion'])
      {
        $fplan = "  AND     c.plan_id = ".$_REQUEST['plan_afiliacion']." ";
        $_SESSION['reconecc']['plan_afiliacion'] = $_REQUEST['plan_afiliacion'];
        $_SESSION['reconecc']['descripcion_plan'] = $_REQUEST['descripcion_plan'];
      }
      
  		$_SESSION['reconecc']['razonso']=$_SESSION['recoex']['razonso'];
  		$_SESSION['reconecc']['empresa']=$_SESSION['recoex']['empresa'];
      
  		list($dbconn) = GetDBconn();
  		GLOBAL $ADODB_FETCH_MODE;				
   
			$query = "  SELECT a.*,
                        b.descripcion as tipojustificacion
            			FROM  (
                          SELECT  a.tipo_cancelacion_id,
                                  count(*) as cantidad
                          FROM    agenda_citas_asignadas_cancelacion a,
                                  agenda_citas_asignadas c,
                                  agenda_citas d,
                                  agenda_turnos e,
                                  tipos_consulta x, 
                                  departamentos dpto, 
                                  userpermisos_repconsultaexterna rep
                          WHERE		a.agenda_cita_asignada_id=c.agenda_cita_asignada_id 
                          AND			c.agenda_cita_id=d.agenda_cita_id 
                          AND     d.agenda_turno_id=e.agenda_turno_id				
                  				$TipoConsulFiltro
                  				$ProfeFiltro
                  				$fechaInFiltro
                  				$fechaFnFiltro
                  				$justifiFiltro
                          ".$fplan."
                  				AND e.tipo_consulta_id=x.tipo_consulta_id    	    
                  				AND x.departamento=dpto.departamento				  
                  				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'						
                  				$sql_centro
                  				$sql_unidad
                  				$sql_dpto 
                  				AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
                  				AND dpto.empresa_id=rep.empresa_id
                  				AND dpto.centro_utilidad=rep.centro_utilidad
                  				AND dpto.unidad_funcional=rep.unidad_funcional
                  				AND dpto.departamento=rep.departamento
                  				AND rep.usuario_id='".UserGetUID()."'
                  				GROUP BY a.tipo_cancelacion_id) as a,tipos_cancelacion b   			
                  				WHERE a.tipo_cancelacion_id=b.tipo_cancelacion_id			         		 
            				";	
  		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $resulta = $dbconn->Execute($query);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
  		if($dbconn->ErrorNo() != 0){
  			$this->error = "Error al Cargar el Modulo";
  			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  			return false;
  		}else{      
  			while($datos=$resulta->FetchRow()){
  				$vector[$datos['tipo_cancelacion_id']]=$datos;				
  			}			
  		}		

  		$_SESSION['CITAS_CANCELADAS_CONSOLIDADO']['DATOS']=$vector;
      $this->ReporteCitasCanceladasConsolidadoEntidad();
  		return true;
  	}

	function BuscarJustificacion(){
    list($dbconn) = GetDBconn();
		$query="SELECT tipo_cancelacion_id,descripcion
		FROM tipos_cancelacion";
		$resulta = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
		}else{
			while(!$resulta->EOF){
				$Tipo_con[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
		}
		return $Tipo_con;
	}

     function RegistrosReporteCausasCitasMedicas()
     {
          GLOBAL $ADODB_FETCH_MODE;          
		if(!empty($_REQUEST['feinictra'])){
			$fechas=explode('/',$_REQUEST['feinictra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
			  $_SESSION['reconeccc']['fechadesde']=$yea.'-'.$mon.'-'.$day;
		  }else{
			  $_REQUEST['feinictra']='';
				$this->frmError["feinictra"]=1;
		  }
		}else{
			$this->frmError["feinictra"]=1;
		}
		if(!empty($_REQUEST['fefinctra'])){
			$fechas=explode('/',$_REQUEST['fefinctra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
				$fech=date ("Y-m-d");
				if($_SESSION['reconeccc']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea))){
				  $_SESSION['reconeccc']['fechahasta']=$yea.'-'.$mon.'-'.$day;
				}else{
					$_REQUEST['fefinctra']='';
					$this->frmError["fefinctra"]=1;
				}
			}else{
				$_REQUEST['fefinctra']='';
				$this->frmError["fefinctra"]=1;
			}
		}else{
			$this->frmError["fefinctra"]=1;
		}
		if($this->frmError["feinictra"]==1 || $this->frmError["fefinctra"]==1){
		  $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
			$this->FormaReporteCausasCitasMedicas();
			return true;
		}
		if($_SESSION['reconeccc']['fechadesde']<>NULL){
		  $fechaInFiltro="AND b.fecha>='".$_SESSION['reconeccc']['fechadesde']."'";
		}
		if($_SESSION['reconeccc']['fechahasta']<>NULL){
		  $fechaFnFiltro="AND b.fecha<='".$_SESSION['reconeccc']['fechahasta']."'";
		}
		$_SESSION['reconeccc']['razonso']=$_SESSION['recoex']['razonso'];
		$_SESSION['reconeccc']['empresa']=$_SESSION['recoex']['empresa'];

          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad) AND $centro_utilidad != '-1')
          { 
               $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'";
               
          }
           
          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional) AND $unidad_funcional != '-1')
          { 
               $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'";
               
          }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento) AND $departamento != '-1')
          { 
               $sql_dpto = "AND dpto.departamento = '$departamento'"; 
               
          }

          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND b.usuario_id = ".$usuario_id[0].""; }
          }

          
          list($dbconn) = GetDBconn();
          $query="SELECT a.*
                    FROM 
                    (SELECT x.tipo_diagnostico_id as diagnostico_id, z.diagnostico_nombre,
					y.descripcion as sexo, x.cantidad
                    FROM 
                    (SELECT count(*) as cantidad, a.tipo_diagnostico_id, d.sexo_id
                     FROM hc_diagnosticos_ingreso a, hc_evoluciones b, 
                          ingresos c, pacientes d , departamentos dpto, userpermisos_repconsultaexterna rep
                     WHERE a.evolucion_id=b.evolucion_id AND b.departamento=dpto.departamento
			      AND b.fecha
                     BETWEEN '".$_SESSION['reconeccc']['fechadesde']."' AND '".$_SESSION['reconeccc']['fechahasta']."'
										 AND dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                     $sql_usuario
                     $sql_centro
                     $sql_unidad
                     $sql_dpto
										 
										AND dpto.empresa_id=rep.empresa_id
										AND dpto.centro_utilidad=rep.centro_utilidad
										AND dpto.unidad_funcional=rep.unidad_funcional
										AND dpto.departamento=rep.departamento
										AND rep.usuario_id='".UserGetUID()."'
										 AND b.departamento=dpto.departamento                                      
                     AND c.ingreso=b.ingreso
                     AND d.paciente_id = c.paciente_id
                     AND d.tipo_id_paciente = c.tipo_id_paciente          
                     GROUP BY a.tipo_diagnostico_id, d.sexo_id) as x, 
                     
                     tipo_sexo as y, diagnosticos as z 
                     
                     WHERE y.sexo_id = x.sexo_id
                     AND x.tipo_diagnostico_id = z.diagnostico_id
                     
                     ORDER BY x.tipo_diagnostico_id,sexo)as a 
                     ORDER BY a.cantidad DESC
                     ";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					
		$resulta = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
          {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
               while ($data = $resulta->FetchRow())
               {
                    $Tipo_con[$data['diagnostico_id']][] = $data;
                    $Tipo_sexo[$data['sexo']][] = $data;
               }
		}
          
          $RangoI = (date("Y-m-d"));          
          $Rango1 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-1))));
		$Rango5 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-5))));
		$Rango14 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-14))));
          $Rango15 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-15))));
          
          $Rango0 = (date("Y-m-d"));
          $Rango0 = explode("-",$Rango0);

          $Rango44 = ($Rango0[0] - 44)."-".$Rango0[1]."-".$Rango0[2];
          $Rango45 = ($Rango0[0] - 45)."-".$Rango0[1]."-".$Rango0[2];
          $Rango69 = ($Rango0[0] - 69)."-".$Rango0[1]."-".$Rango0[2];
          $Rango70 = ($Rango0[0] - 70)."-".$Rango0[1]."-".$Rango0[2];
          
          $Rango_Edades = array();
          
          for($i=0; $i<6; $i++)
          {
          	if($i == 0)
               { $periodo ="AND date(d.fecha_nacimiento) > '$Rango1'"; $edad = "menor_1"; }
          	if($i == 1)
               { $periodo ="AND date(d.fecha_nacimiento)<= '$Rango1' AND date(d.fecha_nacimiento) > '$Rango5'"; $edad = "entre_1_5";}
          	if($i == 2)
               { $periodo ="AND d.fecha_nacimiento BETWEEN '$Rango14' AND '$Rango5'"; $edad = "entre_5_14";}
          	if($i == 3)
               { $periodo ="AND d.fecha_nacimiento BETWEEN '$Rango44' AND '$Rango15'"; $edad = "entre_15_44"; }
          	if($i == 4)
               { $periodo ="AND d.fecha_nacimiento BETWEEN '$Rango69' AND '$Rango45'"; $edad = "entre_45_69";}
          	if($i == 5)
               { $periodo ="AND d.fecha_nacimiento <= '$Rango70'"; $edad = "mayor_70"; }
               
               
              $query_edad = "SELECT a.*
                            FROM (SELECT count(*) as cantidad_$edad, 
                              	        a.tipo_diagnostico_id 
                                    FROM hc_diagnosticos_ingreso a, hc_evoluciones b, 
                                    	 ingresos c, pacientes d, departamentos dpto, userpermisos_repconsultaexterna rep
                                    WHERE a.evolucion_id=b.evolucion_id 
                                          AND b.fecha
                                          BETWEEN '".$_SESSION['reconeccc']['fechadesde']."' AND '".$_SESSION['reconeccc']['fechahasta']."'
																					AND dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
																					AND dpto.empresa_id=rep.empresa_id
																					AND dpto.centro_utilidad=rep.centro_utilidad
																					AND dpto.unidad_funcional=rep.unidad_funcional
																					AND dpto.departamento=rep.departamento
																					AND rep.usuario_id='".UserGetUID()."'
                                          $sql_usuario
																					$sql_centro
																					$sql_unidad
																					$sql_dpto   
																					AND b.departamento=dpto.departamento      
								  												AND c.ingreso=b.ingreso 
                                          AND d.paciente_id = c.paciente_id
                                          AND d.tipo_id_paciente = c.tipo_id_paciente
                                          $periodo  
                                          GROUP BY a.tipo_diagnostico_id
                                          ORDER BY a.tipo_diagnostico_id) as a
                           ORDER BY  cantidad_$edad DESC;";
                                          
               
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resulta = $dbconn->Execute($query_edad);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }else{
                    while ($data = $resulta->FetchRow())
                    {
          			array_push ($Rango_Edades,$data);
                    }
               }
          }           
          $_SESSION['CAUSAS_CITAS']['DATOS']=$Tipo_con;
          $this->ReporteCausasCitas($Tipo_sexo,$Rango_Edades);
		return true;
	}
	
	function VerDetalleReporteEstadisticoOS(){
	
		GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
          
  	$centro_utilidad = $_REQUEST['centroU'];
    if (!empty($centro_utilidad)){
			$sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'"; 
		}

    $unidad_funcional = $_REQUEST['unidadF'];
    if (!empty($unidad_funcional)){
			$sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'"; 
		}

		$departamento = $_REQUEST['DptoSel'];
    if (!empty($departamento)){
			$sql_dpto = "AND dpto.departamento = '$departamento'"; 
		}

		if($_REQUEST['profesional_escojer'] != '-1'){
    	$usuario_id = explode(',',$_REQUEST['profesional_escojer']);
      if(!empty($usuario_id[0])){
				$sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; 
			}
    }
          
		if(!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra'])){
			$feinictra = $this->FechaStamp($_REQUEST['feinictra']);
			$fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
					
			if(!empty($feinictra) AND !empty($fefinctra)){
				$sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";
			}
		}
				
		if($_REQUEST['DetalleAD']){
		
			$queryApd ="SELECT v.apoyod_tipo_id,
										(SELECT x.descripcion FROM apoyod_tipos x WHERE v.apoyod_tipo_id=x.apoyod_tipo_id) as descripcion,
									count(*) as total_tipo
									
									FROM departamentos dpto, userpermisos_repconsultaexterna rep, hc_evoluciones B, os_maestro C, 
												os_cruce_citas D, agenda_citas_asignadas E,
												hc_os_solicitudes J JOIN hc_os_solicitudes_apoyod V 
												ON (J.hc_os_solicitud_id = V.hc_os_solicitud_id AND os_tipo_solicitud_id = 'APD')
									WHERE
												dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
												
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
												
												$sql_centro
												$sql_unidad
												$sql_dpto                      
												AND dpto.departamento = B.departamento
												AND B.estado = '0'
												$sql_usuario
												$sql_fecha
												AND B.evolucion_id = J.evolucion_id
												AND B.numerodecuenta = C.numerodecuenta
												AND C.numero_orden_id = D.numero_orden_id
												AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
												GROUP BY v.apoyod_tipo_id;";
												
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;			
			$resulta = $dbconn->Execute($queryApd);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
						
			if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
			}else{
				if($resulta->RecordCount()>0){
					while ($data = $resulta->FetchRow()){
						$total_apoyos[$data['apoyod_tipo_id']]=$data;
					}
				}	
			}	
		}	

         
    if($_REQUEST['DetalleINTER']){
		         
			$queryInt ="SELECT V.especialidad,
												(SELECT x.descripcion FROM especialidades x WHERE V.especialidad=x.especialidad) as descripcion,
												count(*) as total_tipo
									FROM  departamentos dpto, userpermisos_repconsultaexterna rep, hc_evoluciones B, os_maestro C, 
												os_cruce_citas D, agenda_citas_asignadas E,
												hc_os_solicitudes J JOIN hc_os_solicitudes_interconsultas V ON  (J.hc_os_solicitud_id = V.hc_os_solicitud_id AND os_tipo_solicitud_id = 'INT')
									WHERE
												dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
												
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
												
												$sql_centro
												$sql_unidad
												$sql_dpto                      
												AND dpto.departamento = B.departamento
												AND B.estado = '0'
												$sql_usuario
												$sql_fecha
												AND B.evolucion_id = J.evolucion_id
												AND B.numerodecuenta = C.numerodecuenta
												AND C.numero_orden_id = D.numero_orden_id
												AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
												GROUP BY V.especialidad;";
	
			
						
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resulta = $dbconn->Execute($queryInt);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;						
			if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
			}else{
				if($resulta->RecordCount()>0){
					while ($data = $resulta->FetchRow()){
						$total_Int[$data['especialidad']]=$data;
					}
				}	
			}		
		}	
          
    if($_REQUEST['DetalleINCA']){
		      
			$queryInc ="SELECT J.tipo_incapacidad_id,
											(SELECT x.descripcion FROM hc_tipos_incapacidad x WHERE J.tipo_incapacidad_id=x.tipo_incapacidad_id) as descripcion,
											count(*) as total_tipo
									FROM  departamentos dpto, userpermisos_repconsultaexterna rep , hc_evoluciones B, os_maestro C,
												os_cruce_citas D, agenda_citas_asignadas E, hc_incapacidades J 
									WHERE dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
									
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
									
									
												$sql_centro
												$sql_unidad
												$sql_dpto                      
												AND dpto.departamento = B.departamento
												AND B.estado = '0'
												$sql_usuario
												$sql_fecha
												AND B.evolucion_id = J.evolucion_id
												AND B.numerodecuenta = C.numerodecuenta
												AND C.numero_orden_id = D.numero_orden_id
												AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
												GROUP BY J.tipo_incapacidad_id;";
	
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$resulta = $dbconn->Execute($queryInc);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;						
			if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
			}else{
				if($resulta->RecordCount()>0){
					while ($data = $resulta->FetchRow()){
						$total_Inca[$data['tipo_incapacidad_id']]=$data;
					}
				}	
			}																		
		}	
		$this->ResultadosEstadisticosOrdenesServicioDetalle($total_apoyos,$total_Int,$total_Inca,$_REQUEST['centroU'],$_REQUEST['centroutilidad'],$_REQUEST['unidadF'],$_REQUEST['unidadfunc'],
		$_REQUEST['DptoSel'],$_REQUEST['departamento'],$_REQUEST['profesional_escojer'],$_REQUEST['feinictra'],$_REQUEST['fefinctra'],
		$_REQUEST['Total_consulta'],$_REQUEST['total_frmedicas'],$_REQUEST['total_apoyos'],$_REQUEST['total_Qx'],$_REQUEST['total_NoQx'],
		$_REQUEST['total_Int'],$_REQUEST['total_Inca']);
		return true;
	}
     
     
     function RegistrosReporteCaracteristicasPaciente()
     {
          GLOBAL $ADODB_FETCH_MODE;
          
          if (!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))
          {
               $feinictra = $this->FechaStamp($_REQUEST['feinictra']);
               $fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
               
               if(!empty($feinictra) AND !empty($fefinctra))
               { $sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";}
          }
		
          $_SESSION['reconeccc']['razonso']=$_SESSION['recoex']['razonso'];
					$_SESSION['reconeccc']['empresa']=$_SESSION['recoex']['empresa'];
		
          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad) AND $centro_utilidad != '-1')
          { 
               $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'";
          }
           
          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional) AND $unidad_funcional != '-1')
          { 
               $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'";
          }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento) AND $departamento != '-1')
          { 
               $sql_dpto = "AND dpto.departamento = '$departamento'"; 
          }
          
          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; }
          }

          if(empty($_REQUEST['feinictra']) OR empty($_REQUEST['fefinctra'])){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->FormaReporteCaracteristicasPacientes();
               return true;
		}

                    
          list($dbconn) = GetDBconn();
          //ojo comente esta forma y lo hice como esta abajo porque no daban los resultados igual que 
					//el reporte de causas de consultas citas lorena
          /*$RangoI = (date("Y-m-d"));          
          $Rango1 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-1))));
		
					$Rango5 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-5))));
					$Rango14 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-14))));
          $Rango15 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-15))));
          
          $Rango0 = (date("Y-m-d"));
          $Rango0 = explode("-",$Rango0);

          $Rango44 = ($Rango0[0] - 44)."-".$Rango0[1]."-".$Rango0[2];
          $Rango45 = ($Rango0[0] - 45)."-".$Rango0[1]."-".$Rango0[2];
          $Rango69 = ($Rango0[0] - 69)."-".$Rango0[1]."-".$Rango0[2];
          $Rango70 = ($Rango0[0] - 70)."-".$Rango0[1]."-".$Rango0[2];
          
          $Rango_Edades = array();
          
          for($i=0; $i<6; $i++)
          {
          	if($i == 0)
               { $periodo ="AND date(G.fecha_nacimiento) > '$Rango1'"; $edad = "MENOR DE 1 AÑO"; }
          	if($i == 1)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango1' AND date(G.fecha_nacimiento) > '$Rango5'"; $edad = "ENTRE 1 Y 5 AÑOS";}
          	if($i == 2)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango5' AND date(G.fecha_nacimiento) >= '$Rango14'"; $edad = "ENTRE 5 Y 14 AÑOS";}
          	if($i == 3)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango15' AND date(G.fecha_nacimiento) >= '$Rango44'"; $edad = "ENTRE 15 Y 44 AÑOS"; }
          	if($i == 4)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango45' AND date(G.fecha_nacimiento) >= '$Rango69'"; $edad = "ENTRE 45 Y 69 AÑOS";}
          	if($i == 5)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango70'"; $edad = "MAYOR DE 70 AÑOS"; }
               
               
              $query_edad = "SELECT count(*) as total_citas_edad, F.descripcion, F.sexo_id, '$edad' as tipo
						    FROM departamentos A, hc_evoluciones B, os_maestro C, os_cruce_citas D, agenda_citas_asignadas E, tipo_sexo F, pacientes G, ingresos I
						    WHERE A.empresa_id = '".$_SESSION['recoex']['empresa']."'
                                        $sql_centro
                                        $sql_unidad
                                        $sql_dpto
                                        AND A.departamento = B.departamento 
                                        AND B.estado = '0' 
                                        $sql_usuario
                                        $sql_fecha                                          
                                        AND B.ingreso = I.ingreso
                                        AND G.paciente_id = I.paciente_id
                                        AND G.tipo_id_paciente = I.tipo_id_paciente
                                        AND F.sexo_id = G.sexo_id
	                                   $periodo
                                        AND B.numerodecuenta = C.numerodecuenta 
                                        AND C.numero_orden_id = D.numero_orden_id 
                                        AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id 

								GROUP BY F.descripcion, F.sexo_id
                                        ORDER BY F.sexo_id DESC;";
               echo $query_edad;
							 echo '<BR>';
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resulta = $dbconn->Execute($query_edad);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }else{
                    while ($data = $resulta->FetchRow())
                    {
          			array_push ($Rango_Edades,$data);
                    }
               }
          }*/			
					
					$query="SELECT G.fecha_nacimiento,F.descripcion, F.sexo_id
						    FROM departamentos dpto, userpermisos_repconsultaexterna rep,  hc_evoluciones B, os_maestro C, os_cruce_citas D, agenda_citas_asignadas E, tipo_sexo F, pacientes G, ingresos I
						    WHERE dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
								
								AND dpto.empresa_id=rep.empresa_id
								AND dpto.centro_utilidad=rep.centro_utilidad
								AND dpto.unidad_funcional=rep.unidad_funcional
								AND dpto.departamento=rep.departamento
								AND rep.usuario_id='".UserGetUID()."'
								
                                        $sql_centro
                                        $sql_unidad
                                        $sql_dpto
                                        AND dpto.departamento = B.departamento 
                                        AND B.estado = '0' 
                                        $sql_usuario
                                        $sql_fecha                                          
                                        AND B.ingreso = I.ingreso
                                        AND G.paciente_id = I.paciente_id
                                        AND G.tipo_id_paciente = I.tipo_id_paciente
                                        AND F.sexo_id = G.sexo_id	                                   
                                        AND B.numerodecuenta = C.numerodecuenta 
                                        AND C.numero_orden_id = D.numero_orden_id 
                                        AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id 								
                                        ORDER BY G.fecha_nacimiento,F.sexo_id DESC;";            
																				
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}else{						
						while (!$result->EOF) {
							$vars[]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
					$vectorUnoF=0;$vectorDosF=0;$vectorTresF=0;$vectorCuatroF=0;$vectorCincoF=0;$vectorSeisF=0;
					$vectorUnoM=0;$vectorDosM=0;$vectorTresM=0;$vectorCuatroM=0;$vectorCincoM=0;$vectorSeisM=0;
   				for($l=0;$l<sizeof($vars);$l++){
						$EdadArr=CalcularEdad($vars[$l]['fecha_nacimiento']);
						$edad=$EdadArr['anos'];
						if($edad<1){
							if($vars[$l]['sexo_id']=='F'){
								$vectorUnoF++;
							}else{
								$vectorUnoM++;
							}	
						}elseif($edad>=1 && $edad<5){
							if($vars[$l]['sexo_id']=='F'){
								$vectorDosF++;
							}else{
								$vectorDosM++;
							}	
						}elseif($edad>=5 && $edad<=14){
							if($vars[$l]['sexo_id']=='F'){
								$vectorTresF++;
							}else{
								$vectorTresM++;
							}	
						}elseif($edad>=15 && $edad<=44){
							if($vars[$l]['sexo_id']=='F'){
								$vectorCuatroF++;
							}else{
								$vectorCuatroM++;
							}	
						}elseif($edad>=45 && $edad<=69){
							if($vars[$l]['sexo_id']=='F'){
								$vectorCincoF++;
							}else{
								$vectorCincoM++;
							}	
						}else{
							if($vars[$l]['sexo_id']=='F'){
								$vectorSeisF++;
							}else{
								$vectorSeisM++;
							}	
						}						
					}
					$Rango_Edades1=array();
					$dat1['total_citas_edad']=$vectorUnoF;
					$dat1['descripcion']='FEMENINO';
					$dat1['sexo_id']='F';
					$dat1['tipo']='MENOR DE 1 AÑO';
					array_push ($Rango_Edades1,$dat1);
					$dat2['total_citas_edad']=$vectorUnoM;
					$dat2['descripcion']='MASCULINO';
					$dat2['sexo_id']='M';
					$dat2['tipo']='MENOR DE 1 AÑO';					
					array_push ($Rango_Edades1,$dat2);
					
					$dat3['total_citas_edad']=$vectorDosF;
					$dat3['descripcion']='FEMENINO';
					$dat3['sexo_id']='F';
					$dat3['tipo']='ENTRE 1 Y 5 AÑOS';
					array_push ($Rango_Edades1,$dat3);
					$dat4['total_citas_edad']=$vectorDosM;
					$dat4['descripcion']='MASCULINO';
					$dat4['sexo_id']='M';
					$dat4['tipo']='ENTRE 1 Y 5 AÑOS';
					array_push ($Rango_Edades1,$dat4);
					
					$dat5['total_citas_edad']=$vectorTresF;
					$dat5['descripcion']='FEMENINO';
					$dat5['sexo_id']='F';
					$dat5['tipo']='ENTRE 5 Y 14 AÑOS';
					array_push ($Rango_Edades1,$dat5);
					$dat6['total_citas_edad']=$vectorTresM;
					$dat6['descripcion']='MASCULINO';
					$dat6['sexo_id']='M';
					$dat6['tipo']='ENTRE 5 Y 14 AÑOS';
					array_push ($Rango_Edades1,$dat6);
					
					$dat7['total_citas_edad']=$vectorCuatroF;
					$dat7['descripcion']='FEMENINO';
					$dat7['sexo_id']='F';
					$dat7['tipo']='ENTRE 15 Y 44 AÑOS';
					array_push ($Rango_Edades1,$dat7);
					$dat8['total_citas_edad']=$vectorCuatroM;
					$dat8['descripcion']='MASCULINO';
					$dat8['sexo_id']='M';
					$dat8['tipo']='ENTRE 15 Y 44 AÑOS';
					array_push ($Rango_Edades1,$dat8);
					
					$dat9['total_citas_edad']=$vectorCincoF;
					$dat9['descripcion']='FEMENINO';
					$dat9['sexo_id']='F';
					$dat9['tipo']='ENTRE 45 Y 69 AÑOS';
					array_push ($Rango_Edades1,$dat9);
					$dat10['total_citas_edad']=$vectorCincoM;
					$dat10['descripcion']='MASCULINO';
					$dat10['sexo_id']='M';
					$dat10['tipo']='ENTRE 45 Y 69 AÑOS';
					array_push ($Rango_Edades1,$dat10);
					
					$dat11['total_citas_edad']=$vectorSeisF;
					$dat11['descripcion']='FEMENINO';
					$dat11['sexo_id']='F';
					$dat11['tipo']='MAYOR DE 70 AÑOS';
					array_push ($Rango_Edades1,$dat11);
					$dat12['total_citas_edad']=$vectorSeisM;
					$dat12['descripcion']='MASCULINO';
					$dat12['sexo_id']='M';
					$dat12['tipo']='MAYOR DE 70 AÑOS';
					array_push ($Rango_Edades1,$dat12);					
          $this->ReporteCaracteristicaPacientes($Rango_Edades1);
					return true;
	}

          
     function RegistrosReporteCitasTratamientoOdontologico()
     {
          GLOBAL $ADODB_FETCH_MODE;
          
          if (!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))
          {
               $feinictra = $this->FechaStamp($_REQUEST['feinictra']);
               $fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
               
               if(!empty($feinictra) AND !empty($fefinctra))
               { $sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";}
          }
		
          $_SESSION['reconeccc']['razonso']=$_SESSION['recoex']['razonso'];
					$_SESSION['reconeccc']['empresa']=$_SESSION['recoex']['empresa'];
		
          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad) AND $centro_utilidad != '-1')
          { 
               $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'";
          }
           
          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional) AND $unidad_funcional != '-1')
          { 
               $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'";
          }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento) AND $departamento != '-1')
          { 
               $sql_dpto = "AND dpto.departamento = '$departamento'"; 
          }
          
          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; }
          }

          if((!empty($_REQUEST['feinictra']) AND empty($_REQUEST['fefinctra'])) OR (empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->FormaReporteCitasTratamientoOdontologico();
               return true;
					}

                    
          list($dbconn) = GetDBconn();		
          /*Tratamiento Iniciados*/          
          $query_TI = "SELECT count(DISTINCT A.evolucion_id)
                       FROM hc_odontogramas_tratamientos as A, hc_evoluciones as B, departamentos as dpto, userpermisos_repconsultaexterna rep
                       WHERE A.sw_activo='1' 
                         AND A.evolucion_id=B.evolucion_id
												 AND dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_usuario
                         $sql_fecha
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND B.departamento=dpto.departamento
												 
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."';";  
												                    
					$resulta = $dbconn->Execute($query_TI);
          if($dbconn->ErrorNo() != 0){
								$this->frmError["MensajeError"]="NO EXISTE ODONTOLOGIA: ".$dbconn->ErrorMsg();
								$this->FormaReporteCitasTratamientoOdontologico();
								return true;
          }              
          list($TI) = $resulta->FetchRow();
          
          /*Tratamiento Terminados*/          
          $query_TT ="SELECT count(DISTINCT A.hc_odontograma_tratamiento_id)
                      FROM hc_odontogramas_tratamientos as A, hc_odontogramas_tratamientos_detalle as D,
                           hc_evoluciones as B, departamentos as dpto,userpermisos_repconsultaexterna rep
                      WHERE A.hc_odontograma_tratamiento_id=D.hc_odontograma_tratamiento_id
                        AND A.sw_activo='0'
                        AND D.evolucion_id=
                            (	SELECT MAX(evolucion_id)
                                   FROM hc_odontogramas_tratamientos_detalle
                                   WHERE hc_odontograma_tratamiento_id=A.hc_odontograma_tratamiento_id
                            )
                        AND D.evolucion_id=B.evolucion_id
												AND dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                        $sql_usuario
                        $sql_fecha
                        $sql_centro
                        $sql_unidad
                        $sql_dpto
                        AND B.departamento=dpto.departamento
												
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."';";                         
					$resulta = $dbconn->Execute($query_TT);
          if($dbconn->ErrorNo() != 0){
								$this->frmError["MensajeError"]="NO EXISTE ODONTOLOGIA: ".$dbconn->ErrorMsg();
								$this->FormaReporteCitasTratamientoOdontologico();
								return true;
          }              
          list($TT) = $resulta->FetchRow();                                        
          $this->ReporteCitasTratamientoOdontologico($TI,$TT);
					return true;
	}
     
     
     function BusquedaReporteEstadisticasCausasTipo()
     {   
          GLOBAL $ADODB_FETCH_MODE;
     			list($dbconn) = GetDBconn();
          
          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad))
          { $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'"; }

          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional))
          { $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'"; }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento))
          { $sql_dpto = "AND dpto.departamento = '$departamento'"; }
          
          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; }
          }
          
          if (!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))
          {
               $feinictra = $this->FechaStamp($_REQUEST['feinictra']);
               $fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
               
               if(!empty($feinictra) AND !empty($fefinctra))
               { $sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";}
          }
          
          if ($_REQUEST['tipocita'] != '-1')
          {
               $tipo_cita = explode(',',$_REQUEST['tipocita']);
               if(!empty($tipo_cita[0]))
               { $sql_tipocita = "AND E.tipo_cita = '".$tipo_cita[0]."'";}
	     		}
          
          if(empty($_REQUEST['feinictra']) OR empty($_REQUEST['fefinctra'])){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->FormaReporteEstadisticasCausasTipo();
               return true;
					}

          
          $queryT ="SELECT count(*) as total_consulta
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep,  hc_evoluciones B, os_maestro C,
                   		os_cruce_citas D, agenda_citas_asignadas E
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
												 $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
												 
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
												 
                         AND B.estado = '0'
                         $sql_usuario
                         $sql_fecha
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita;";
												 
         			 
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryT);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                         
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
               
          $Total_consulta = $resulta->FetchRow();
          
               
          $queryTi ="SELECT count(*) as total_tipo_cita, F.descripcion as tipos_de_citas
                     FROM  departamentos dpto, userpermisos_repconsultaexterna rep, hc_evoluciones B, os_maestro C, 
                         os_cruce_citas D, agenda_citas_asignadas E, tipos_cita F
                     WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
												 
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
												 
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         AND B.estado = '0'
                         $sql_usuario
                         $sql_fecha
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita
                         AND E.tipo_cita = F.tipo_cita
                         GROUP BY tipos_de_citas
                         ORDER BY total_tipo_cita DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryTi);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while ($data = $resulta->FetchRow())
               {
                    $total_tipo_cita[] = $data;
               }
          }
          
          $queryFi ="SELECT count(*) as total_citas_finalidad, V.detalle, V.tipo_finalidad_id
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep ,hc_evoluciones B, os_maestro C, 
	                     os_cruce_citas D, agenda_citas_asignadas E,
                          hc_finalidad J LEFT JOIN hc_tipos_finalidad V ON (J.tipo_finalidad_id = V.tipo_finalidad_id)
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
												 
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
												 
                         AND B.estado = '0'
                         $sql_usuario
                         $sql_fecha
                         AND B.evolucion_id = J.evolucion_id
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita
                         GROUP BY V.detalle, V.tipo_finalidad_id
					ORDER BY total_citas_finalidad DESC;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryFi);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
	     while ($data = $resulta->FetchRow())
          {
               $total_consulta_Finalidad[] = $data;
          }
          
          $queryOr ="SELECT count(*) as total_citas_origen, V.detalle, V.tipo_atencion_id
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep , hc_evoluciones B, os_maestro C, 
	                     os_cruce_citas D, agenda_citas_asignadas E,
                          hc_atencion J LEFT JOIN hc_tipos_atencion V ON (J.tipo_atencion_id = V.tipo_atencion_id)
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
                         $sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         AND B.estado = '0'
												 
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
												 
                         $sql_usuario
                         $sql_fecha
                         AND B.evolucion_id = J.evolucion_id
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id
                         $sql_tipocita
                         GROUP BY V.detalle, V.tipo_atencion_id
					ORDER BY total_citas_origen DESC;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryOr);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
	     while ($data = $resulta->FetchRow())
          {
               $total_consulta_Origen[] = $data;
          }          
             

          $this->ResultadosEstadisticosTipoCitas($Total_consulta,$total_tipo_cita,$total_consulta_Finalidad,$total_consulta_Origen);
          return true;
    }

    
     function RegistrosReporteOrdenesServicio()
     {    
          GLOBAL $ADODB_FETCH_MODE;
     	list($dbconn) = GetDBconn();
          
          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad))
          { $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'"; }

          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional))
          { $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'"; }

          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento))
          { $sql_dpto = "AND dpto.departamento = '$departamento'"; }


          if($_REQUEST['profesional_escojer'] != '-1')
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; }
          }
          
          if (!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))
          {
               $feinictra = $this->FechaStamp($_REQUEST['feinictra']);
               $fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
               
               if(!empty($feinictra) AND !empty($fefinctra))
               { $sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";}
          }

          if(empty($_REQUEST['feinictra']) OR empty($_REQUEST['fefinctra'])){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->FormaReporteEstadisticoOrdenesServicio();
               return true;
		}

          $queryT ="SELECT count(*) as total_consulta
                    FROM  departamentos dpto, userpermisos_repconsultaexterna rep , hc_evoluciones B, os_maestro C,
                   		os_cruce_citas D, agenda_citas_asignadas E
                    WHERE
                         dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                         $sql_centro
					$sql_unidad
                         $sql_dpto
                         AND dpto.departamento = B.departamento
                         AND B.estado = '0'
												 
												AND dpto.empresa_id=rep.empresa_id
												AND dpto.centro_utilidad=rep.centro_utilidad
												AND dpto.unidad_funcional=rep.unidad_funcional
												AND dpto.departamento=rep.departamento
												AND rep.usuario_id='".UserGetUID()."'
						
                         $sql_usuario
                         $sql_fecha
                         AND B.numerodecuenta = C.numerodecuenta
                         AND C.numero_orden_id = D.numero_orden_id
                         AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryT);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                         
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
               
          $Total_consulta = $resulta->FetchRow();
          
          $queryFm ="SELECT count(*) as total_formulas_medicas
				 FROM departamentos dpto, userpermisos_repconsultaexterna rep , hc_evoluciones B, os_maestro C,
                          os_cruce_citas D, agenda_citas_asignadas E, 
                     (SELECT DISTINCT evolucion_id FROM hc_medicamentos_recetados_amb ORDER BY evolucion_id) F
                     WHERE dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                     $sql_centro
                     $sql_unidad
                     $sql_dpto                      
                     AND dpto.departamento = B.departamento 
                     AND B.estado = '0'
										 
										 AND dpto.empresa_id=rep.empresa_id
										AND dpto.centro_utilidad=rep.centro_utilidad
										AND dpto.unidad_funcional=rep.unidad_funcional
										AND dpto.departamento=rep.departamento
										AND rep.usuario_id='".UserGetUID()."'
										 
                     $sql_usuario
                     $sql_fecha
                     AND F.evolucion_id = B.evolucion_id
                     AND B.numerodecuenta = C.numerodecuenta 
                     AND C.numero_orden_id = D.numero_orden_id 
                     AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryFm);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $total_frmedicas = $resulta->FetchRow();
          
          
          $queryApd ="SELECT count(*) as total_solicitudes_apd
                      FROM departamentos dpto, userpermisos_repconsultaexterna rep ,hc_evoluciones B, os_maestro C, 
                           os_cruce_citas D, agenda_citas_asignadas E,
                           hc_os_solicitudes J JOIN hc_os_solicitudes_apoyod V 
                           ON (J.hc_os_solicitud_id = V.hc_os_solicitud_id AND os_tipo_solicitud_id = 'APD')
                      WHERE
                           dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                           $sql_centro
                           $sql_unidad
                           $sql_dpto                      
                           AND dpto.departamento = B.departamento
                           AND B.estado = '0'
													 
													 AND dpto.empresa_id=rep.empresa_id
													AND dpto.centro_utilidad=rep.centro_utilidad
													AND dpto.unidad_funcional=rep.unidad_funcional
													AND dpto.departamento=rep.departamento
													AND rep.usuario_id='".UserGetUID()."'
													 
                           $sql_usuario
                           $sql_fecha
                           AND B.evolucion_id = J.evolucion_id
                           AND B.numerodecuenta = C.numerodecuenta
                           AND C.numero_orden_id = D.numero_orden_id
                           AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryApd);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          $total_apoyos = $resulta->FetchRow();

          
          $queryQx ="SELECT count(*) as total_solicitudes_qx
                     FROM  departamentos dpto, userpermisos_repconsultaexterna rep,hc_evoluciones B, os_maestro C, 
                           os_cruce_citas D, agenda_citas_asignadas E,
                           hc_os_solicitudes J JOIN hc_os_solicitudes_procedimientos V 
                           ON (J.hc_os_solicitud_id = V.hc_os_solicitud_id AND os_tipo_solicitud_id = 'QX')
                     WHERE
                           dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                           $sql_centro
                           $sql_unidad
                           $sql_dpto
                           AND dpto.departamento = B.departamento
                           AND B.estado = '0'
													 
													AND dpto.empresa_id=rep.empresa_id
													AND dpto.centro_utilidad=rep.centro_utilidad
													AND dpto.unidad_funcional=rep.unidad_funcional
													AND dpto.departamento=rep.departamento
													AND rep.usuario_id='".UserGetUID()."'
													 
                           $sql_usuario
                           $sql_fecha
                           AND B.evolucion_id = J.evolucion_id
                           AND B.numerodecuenta = C.numerodecuenta
                           AND C.numero_orden_id = D.numero_orden_id
                           AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryQx);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          $total_Qx = $resulta->FetchRow();

          
          $queryNqx ="SELECT count(*) as total_solicitudes_nqx
                      FROM  departamentos dpto, userpermisos_repconsultaexterna rep ,hc_evoluciones B, os_maestro C, 
                            os_cruce_citas D, agenda_citas_asignadas E,
                            hc_os_solicitudes J JOIN hc_os_solicitudes_no_quirurgicos V 
                            ON (J.hc_os_solicitud_id = V.hc_os_solicitud_id AND os_tipo_solicitud_id = 'PNQ')

                      WHERE
                           dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                           $sql_centro
                           $sql_unidad
                           $sql_dpto                      
                           AND dpto.departamento = B.departamento
                           AND B.estado = '0'
													 
													AND dpto.empresa_id=rep.empresa_id
													AND dpto.centro_utilidad=rep.centro_utilidad
													AND dpto.unidad_funcional=rep.unidad_funcional
													AND dpto.departamento=rep.departamento
													AND rep.usuario_id='".UserGetUID()."'
													 
                           $sql_usuario
                           $sql_fecha
                           AND B.evolucion_id = J.evolucion_id
                           AND B.numerodecuenta = C.numerodecuenta
                           AND C.numero_orden_id = D.numero_orden_id
                           AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryNqx);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          $total_NoQx = $resulta->FetchRow();
          
          
          $queryInt ="SELECT count(*) as total_solicitudes_interconsultas
                      FROM  departamentos dpto, userpermisos_repconsultaexterna rep ,hc_evoluciones B, os_maestro C, 
                            os_cruce_citas D, agenda_citas_asignadas E,
                            hc_os_solicitudes J JOIN hc_os_solicitudes_interconsultas V ON  (J.hc_os_solicitud_id = V.hc_os_solicitud_id AND os_tipo_solicitud_id = 'INT')
                      WHERE
                           dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                           $sql_centro
                           $sql_unidad
                           $sql_dpto                      
                           AND dpto.departamento = B.departamento
                           AND B.estado = '0'
													 
													AND dpto.empresa_id=rep.empresa_id
													AND dpto.centro_utilidad=rep.centro_utilidad
													AND dpto.unidad_funcional=rep.unidad_funcional
													AND dpto.departamento=rep.departamento
													AND rep.usuario_id='".UserGetUID()."'
													 
                           $sql_usuario
                           $sql_fecha
                           AND B.evolucion_id = J.evolucion_id
                           AND B.numerodecuenta = C.numerodecuenta
                           AND C.numero_orden_id = D.numero_orden_id
                           AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryInt);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          $total_Int = $resulta->FetchRow();
          
          
          $queryInc ="SELECT count(*) as total_incapacidades
                      FROM  departamentos dpto, userpermisos_repconsultaexterna rep, hc_evoluciones B, os_maestro C,
                            os_cruce_citas D, agenda_citas_asignadas E, hc_incapacidades J 
                      WHERE dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                            $sql_centro
                            $sql_unidad
                            $sql_dpto                      
                            AND dpto.departamento = B.departamento
                            AND B.estado = '0'
														
														AND dpto.empresa_id=rep.empresa_id
														AND dpto.centro_utilidad=rep.centro_utilidad
														AND dpto.unidad_funcional=rep.unidad_funcional
														AND dpto.departamento=rep.departamento
														AND rep.usuario_id='".UserGetUID()."'
														
                            $sql_usuario
                            $sql_fecha
                            AND B.evolucion_id = J.evolucion_id
                            AND B.numerodecuenta = C.numerodecuenta
                            AND C.numero_orden_id = D.numero_orden_id
                            AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($queryInc);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
               
          $total_Inca = $resulta->FetchRow();

                                        
          $this->ResultadosEstadisticosOrdenesServicio($Total_consulta,$total_frmedicas,$total_apoyos,$total_Qx,$total_NoQx,$total_Int,$total_Inca,
					$_REQUEST['centroU'],$_REQUEST['centroutilidad'],$_REQUEST['unidadF'],$_REQUEST['unidadfunc'],$_REQUEST['DptoSel'],$_REQUEST['departamento'],
					$_REQUEST['profesional_escojer'],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);
          return true;
    }

               
     /*		FechaStamp
     *
     *		Convierte los datos en Fechas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
     */

    	function FechaStamp($fecha)
	{
     	$fecha = explode ('/',$fecha);
          $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
          return $fecha;
          
	}
     
	function Get_Profesionales()
	{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
      //$dbconn->debug=true;
		/*	$query="SELECT b.usuario_id,b.tipo_id_tercero,b.tercero_id,c.nombre_tercero as nombre
							FROM profesionales as b, profesionales_empresas AS A, terceros as c
							WHERE b.estado = '1' AND A.tercero_id=b.tercero_id
        			AND A.tipo_id_tercero=b.tipo_id_tercero
							AND A.empresa_id='".$_SESSION['recoex']['empresa']."'
							AND A.tercero_id=c.tercero_id
        			AND A.tipo_id_tercero=c.tipo_id_tercero
							ORDER BY b.nombre ASC;";*/
			/*if($_SESSION['recoex']['auditor']!=1){
				$fil=" AND d.usuario_id='".UserGetUID()."'";
			}		*/		
     if(!empty($_REQUEST["DptoSel"]))
      {
        $fil1= " , profesionales_departamentos as dep ";
       $fil2= "  AND  b.tercero_id=dep.tercero_id  
							   AND b.tipo_id_tercero=dep.tipo_id_tercero AND dep.departamento='".$_REQUEST["DptoSel"]."' ";
            
      }
      
			$query =" SELECT b.usuario_id,b.tipo_id_tercero,b.tercero_id,c.nombre_tercero as nombre
								FROM profesionales as b,profesionales_empresas AS A, 
								terceros as c, profesionales_usuarios as d $fil1
								WHERE b.estado = '1' AND A.tercero_id=b.tercero_id
								AND A.tipo_id_tercero=b.tipo_id_tercero
								AND A.empresa_id='".$_SESSION['recoex']['empresa']."'
								AND A.tercero_id=c.tercero_id
								AND A.tipo_id_tercero=c.tipo_id_tercero
								AND c.tipo_id_tercero=d.tipo_tercero_id
								AND c.tercero_id=d.tercero_id $fil2
                
								ORDER BY b.nombre ASC;";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

			if ($dbconn->ErrorNo() != 0) {
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
			}

			while ($data = $result->FetchRow()){
						$vars[] = $data;
			}

			$result->Close();
				return $vars;
	}

  function LlamaEstadisticoRendimientoProf(){

    if((!empty($_REQUEST['feinictra']) AND empty($_REQUEST['fefinctra'])) OR (empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))){
      $this->frmError["feinictra"]=1;
      $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
      $this->ReporteEstadisticoRendimientoProf();
      return true;
		}
    $this->EstadisticaRendimientoProf($_REQUEST['centroU'],$_REQUEST['centroutilidad'],$_REQUEST['unidadF'],
    $_REQUEST['unidadfunc'],$_REQUEST['departamento'],$_REQUEST['DptoSel'],$_REQUEST['profesional_escojer'],
    $_REQUEST['feinictra'],$_REQUEST['fefinctra']);
    return true;
  }

  /*function ConsultaEstadisticaRendimientoProf($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra){

    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    if(!empty($centroU)){
      $sql_centro = " AND x.centro_utilidad = '".$centroU."'";
    }
    if(!empty($unidadF)){
      $sql_unidad = " AND x.unidad_funcional = '".$unidadF."'";
    }
    if(!empty($DptoSel)){
      $sql_dpto = " AND x.departamento = '".$DptoSel."'";
    }
    if($profesional_escojer != '-1'){
      $usuario_id = explode(',',$profesional_escojer);
      if(!empty($usuario_id[0])){
        $sql_usuario = " AND y.usuario_id = ".$usuario_id[0]."";
      }
    }
    if(!empty($feinictra) AND !empty($fefinctra)){
      $feinictra = $this->FechaStamp($feinictra);
      $fefinctra = $this->FechaStamp($fefinctra);
      if(!empty($feinictra) AND !empty($fefinctra)){
        $sql_fecha = " AND a.fecha_turno BETWEEN '".$feinictra."' AND '".$fefinctra."'";
      }
    }
		$query="SELECT y.usuario_id,asignadas.total as total_asignadas,canceladas.total as total_canceladas,atendidas.total as total_atendidas,abiertas.total as total_abiertas,ter.nombre_tercero,
            atendidas.promedio

            FROM agenda_turnos a,tipos_consulta z,departamentos x,profesionales_usuarios y
            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                (b.sw_estado='1' OR b.sw_estado='2') AND c.sw_atencion='0' AND a.tipo_consulta_id=z.tipo_consulta_id
                AND z.departamento=x.departamento AND a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as asignadas ON (asignadas.usuario_id=y.usuario_id)

            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                c.agenda_cita_asignada_id IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion)
                AND a.tipo_consulta_id=z.tipo_consulta_id AND z.departamento=x.departamento AND
                a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as canceladas ON (canceladas.usuario_id=y.usuario_id)

            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total,(sum(f.fecha_cierre - f.fecha)/count(*)) as promedio
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,os_cruce_citas d,os_maestro e,hc_evoluciones f,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                (b.sw_estado='1' OR b.sw_estado='2') AND
                c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) AND
                c.agenda_cita_asignada_id=d.agenda_cita_asignada_id AND d.numero_orden_id=e.numero_orden_id AND
                e.numerodecuenta=f.numerodecuenta AND f.estado='0'
                AND a.tipo_consulta_id=z.tipo_consulta_id AND z.departamento=x.departamento AND
                a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as atendidas ON (atendidas.usuario_id=y.usuario_id)

            LEFT JOIN (
                SELECT y.usuario_id,count(*) as total
                FROM agenda_turnos a,agenda_citas b,agenda_citas_asignadas c,os_cruce_citas d,os_maestro e,hc_evoluciones f,tipos_consulta z,departamentos x,profesionales_usuarios y
                WHERE a.agenda_turno_id=b.agenda_turno_id AND a.sw_estado_cancelacion='0' AND
                b.agenda_cita_id=c.agenda_cita_id AND b.agenda_cita_id=c.agenda_cita_id_padre AND
                (b.sw_estado='1' OR b.sw_estado='2') AND
                c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) AND
                c.agenda_cita_asignada_id=d.agenda_cita_asignada_id AND d.numero_orden_id=e.numero_orden_id AND
                e.numerodecuenta=f.numerodecuenta AND f.estado='1'
                AND a.tipo_consulta_id=z.tipo_consulta_id AND z.departamento=x.departamento AND
                a.tipo_id_profesional=y.tipo_tercero_id AND a.profesional_id=y.tercero_id
                $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
                GROUP BY y.usuario_id
            ) as abiertas ON (abiertas.usuario_id=y.usuario_id),
            terceros ter
            WHERE a.sw_estado_cancelacion='0' AND a.tipo_consulta_id=z.tipo_consulta_id AND
            z.departamento=x.departamento AND a.tipo_id_profesional=y.tipo_tercero_id AND
            a.profesional_id=y.tercero_id AND y.tipo_tercero_id=ter.tipo_id_tercero AND y.tercero_id=ter.tercero_id
            $sql_centro $sql_unidad $sql_dpto $sql_usuario $sql_fecha
            GROUP BY y.usuario_id,asignadas.total,canceladas.total,atendidas.total,abiertas.total,ter.nombre_tercero,atendidas.promedio
            ORDER BY ter.nombre_tercero
            ";
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      while ($data = $result->FetchRow()){
          $vars[] = $data;
      }
    }
    $result->Close();
    return $vars;
  }*/

//-------------------NUEVO DAR---------------
  function ConsultaEstadisticaRendimientoProf($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra)
	{
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
    //$dbconn->debug=true;
    
		if(!empty($centroU)){
			$sql_centro = " AND dpto.centro_utilidad = '".$centroU."'";
		}
		if(!empty($unidadF)){
			$sql_unidad = " AND dpto.unidad_funcional = '".$unidadF."'";
		}
		if(!empty($DptoSel)){
			$sql_dpto = " AND dpto.departamento = '".$DptoSel."'";
		}
		if($profesional_escojer != '-1'){
			$usuario_id = explode(',',$profesional_escojer);
			if(!empty($usuario_id[0])){
				$sql_usuario = " AND y.usuario_id = ".$usuario_id[0]."";
			}
		}
		if(!empty($feinictra) AND !empty($fefinctra)){
			$feinictra = $this->FechaStamp($feinictra);
			$fefinctra = $this->FechaStamp($fefinctra);
			if(!empty($feinictra) AND !empty($fefinctra)){
				$sql_fecha = " AND date(a.fecha_turno) >= '".$feinictra."' AND date(a.fecha_turno) <= '".$fefinctra."'";
			}
		}		
		//CAMBIO DAR
		$query = "SELECT a.tipo_id_profesional,a.profesional_id,count(c.agenda_cita_asignada_id) as asignadas, count(f.agenda_cita_asignada_id) as canceladas 
							FROM agenda_turnos a,agenda_citas b,
							agenda_citas_asignadas c 
							LEFT JOIN agenda_citas_asignadas_cancelacion as f 
							ON(c.agenda_cita_asignada_id=f.agenda_cita_asignada_id),
							tipos_consulta z,
							departamentos dpto, userpermisos_repconsultaexterna rep , profesionales_usuarios as y						
							WHERE a.agenda_turno_id=b.agenda_turno_id 
							$sql_fecha							
							AND b.agenda_cita_id=c.agenda_cita_id
              AND c.agenda_cita_id=c.agenda_cita_id_padre  					
							AND a.tipo_id_profesional=y.tipo_tercero_id 
							AND a.profesional_id=y.tercero_id
							$sql_usuario 		
							AND a.tipo_consulta_id=z.tipo_consulta_id
							AND z.departamento=dpto.departamento
							$sql_centro $sql_unidad $sql_dpto
							AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
							AND dpto.empresa_id=rep.empresa_id
							AND dpto.centro_utilidad=rep.centro_utilidad
							AND dpto.unidad_funcional=rep.unidad_funcional
							AND dpto.departamento=rep.departamento
							AND rep.usuario_id='".UserGetUID()."'
							
							
							GROUP BY a.tipo_id_profesional,a.profesional_id
							ORDER BY a.tipo_id_profesional,a.profesional_id";
           
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
							//------trae el nombre del tercero
							$sql = "SELECT a.nombre_tercero, b.usuario_id FROM terceros as a, profesionales_usuarios as b
											WHERE a.tipo_id_tercero='".$result->fields[0]."' AND a.tercero_id='".$result->fields[1]."'
											and a.tipo_id_tercero=b.tipo_tercero_id and a.tercero_id=b.tercero_id";
							$resul = $dbconn->Execute($sql);
							if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Cargar el Modulo";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}	
							$nombre=$resul->fields[0];
							$usuario=$resul->fields[1];
							$resul->Close();					
							//busca atendidas 0 evolucion cerras
							$atendidas = $this->CitasAtendidasoAbiertas(0,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
							//busca abiertas 1 evolucion abiertas
							$abiertas = $this->CitasAtendidasoAbiertas(1,$result->fields[0],$result->fields[1],$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto);
							$var[]=array('usuario'=>$usuario,'nombre'=>$nombre,'asignadas'=>$result->fields[2],'canceladas'=>$result->fields[3],'atendidas'=>$atendidas[total],'promedio'=>$atendidas[promedio],'abiertas'=>$abiertas[total]);
							$result->MoveNext();
					}
					$result->Close();													
			}
			return $var;
  }	

	function CitasAtendidasoAbiertas($tipo,$tipo_profesional,$id_profesional,$sql_fecha,$sql_centro,$sql_unidad,$sql_dpto)
	{		//tipo 0 atendidad 1 abiertas
			$var='';
			//abiertas
			if($tipo==1)
			{
					$x='';
					$y= " AND f.estado='1'";
			}
			else
			{		//son atendidad
					$x= ", (sum(f.fecha_cierre - f.fecha)/count(c.agenda_cita_asignada_id)) as promedio";
					$y= " AND f.estado='0'";
			}
			list($dbconn) = GetDBconn();
     // $dbconn->debug=true;
			$query = "SELECT count(c.agenda_cita_asignada_id) as total $x 
								FROM agenda_turnos a,
								agenda_citas b,
								agenda_citas_asignadas c,
								os_cruce_citas d,
								os_maestro e,
								hc_evoluciones f,
								tipos_consulta z,
								departamentos dpto,
								userpermisos_repconsultaexterna rep 
								WHERE a.tipo_id_profesional='$tipo_profesional' 
								AND a.profesional_id='$id_profesional'
								$sql_fecha
								AND a.tipo_consulta_id=z.tipo_consulta_id 
								AND z.departamento=dpto.departamento
								$sql_centro $sql_unidad $sql_dpto
								AND a.agenda_turno_id=b.agenda_turno_id 
								AND a.sw_estado_cancelacion='0' 
								AND b.agenda_cita_id=c.agenda_cita_id 
								AND b.agenda_cita_id=c.agenda_cita_id_padre 
								AND (b.sw_estado='1' OR b.sw_estado='2') 
								AND c.agenda_cita_asignada_id 
								NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
								AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
								AND d.numero_orden_id=e.numero_orden_id 
								AND e.numerodecuenta=f.numerodecuenta
								AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
								AND dpto.empresa_id=rep.empresa_id
								AND dpto.centro_utilidad=rep.centro_utilidad
								AND dpto.unidad_funcional=rep.unidad_funcional
								AND dpto.departamento=rep.departamento
								AND rep.usuario_id='".UserGetUID()."'
								 
								$y"; 
			$resul = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}	
			if(!$result->EOF)
			{
					$var=$resul->GetRowAssoc($ToUpper = false);
					$resul->Close();									
			}
			return $var;
	}
	
	//------------------FIN NUEVO DAR---------------
	

  function DiasLaboradosProfesional($feinictra,$fefinctra,$profesional_escojer){
    list($dbconn) = GetDBconn();
    //$dbconn->debug=true;
    $feinictra = $this->FechaStamp($feinictra);
    $fefinctra = $this->FechaStamp($fefinctra);
    $query="SELECT count(*) as total
            FROM
                    (SELECT date(con.fecha_turno)
                      FROM agenda_turnos con,profesionales_usuarios a
                      WHERE date(con.fecha_turno) BETWEEN '".$feinictra."' AND '".$fefinctra."' AND
                      con.tipo_id_profesional=a.tipo_tercero_id AND con.profesional_id=a.tercero_id AND
                      a.usuario_id='".$profesional_escojer."'
                      GROUP BY date(con.fecha_turno)
                    ) as diaslab";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      return $result->fields[0];
    }
  }

  function LlamaEstadisticoOportunidadCE(){

    if((!empty($_REQUEST['feinictra']) AND empty($_REQUEST['fefinctra'])) OR (empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))){
      $this->frmError["feinictra"]=1;
      $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
      $this->ReporteEstadisticoOportunidadCE();
      return true;
		}
    $this->EstadisticaOportunidadCE($_REQUEST['centroU'],$_REQUEST['centroutilidad'],$_REQUEST['unidadF'],
    $_REQUEST['unidadfunc'],$_REQUEST['departamento'],$_REQUEST['DptoSel'],$_REQUEST['profesional_escojer'],
    $_REQUEST['feinictra'],$_REQUEST['fefinctra']);
    return true;
  }
	 
	function ConsultaEstadisticaOportunidadCE($centroU,$unidadF,$DptoSel,$profesional_escojer,$feinictra,$fefinctra)
	{
			GLOBAL $ADODB_FETCH_MODE;
			list($dbconn) = GetDBconn();
			if(!empty($centroU)){
				$sql_centro = " AND dpto.centro_utilidad = '".$centroU."'";
			}
			if(!empty($unidadF)){
				$sql_unidad = " AND dpto.unidad_funcional = '".$unidadF."'";
			}
			if(!empty($DptoSel)){
				$sql_dpto = " AND dpto.departamento = '".$DptoSel."'";
			}
			if($profesional_escojer != '-1'){
				$usuario_id = explode(',',$profesional_escojer);
				if(!empty($usuario_id[0])){
					$sql_usuario = " AND y.usuario_id = ".$usuario_id[0]."";
				}
			}
			if(!empty($feinictra) AND !empty($fefinctra)){
				$feinictra = $this->FechaStamp($feinictra);
				$fefinctra = $this->FechaStamp($fefinctra);
				if(!empty($feinictra) AND !empty($fefinctra)){
					$sql_fecha = " AND a.fecha_turno BETWEEN '".$feinictra."' AND '".$fefinctra."'";
				}
			}
			//---CAMBIO DAR	se creo un indice y se quito una condicion q no 							
			$query = "SELECT y.usuario_id,ter.nombre_tercero,c.agenda_cita_asignada_id,
								f.fecha,c.fecha_registro, pac.primer_nombre||' '||pac.segundo_nombre||' '||pac.primer_apellido||' '||pac.segundo_apellido as nombre_pac 
								FROM agenda_turnos a,
								profesionales_usuarios y,
								terceros ter, 
								agenda_citas b,
								agenda_citas_asignadas c,
								os_cruce_citas d,
								os_maestro e,
								hc_evoluciones f, 
								pacientes pac,
								departamentos as dpto,
								userpermisos_repconsultaexterna rep 
								WHERE a.sw_estado_cancelacion='0' 
								$sql_fecha
								AND a.tipo_id_profesional=y.tipo_tercero_id 
								AND a.profesional_id=y.tercero_id 
								AND y.tipo_tercero_id=ter.tipo_id_tercero 
								AND y.tercero_id=ter.tercero_id 
								$sql_usuario
								AND a.agenda_turno_id=b.agenda_turno_id 
								AND b.agenda_cita_id=c.agenda_cita_id 
								AND b.agenda_cita_id=c.agenda_cita_id_padre 
								AND (b.sw_estado='1' OR b.sw_estado='2') 
								AND c.agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion) 
								AND c.agenda_cita_asignada_id=d.agenda_cita_asignada_id 
								AND d.numero_orden_id=e.numero_orden_id 
								AND e.numerodecuenta=f.numerodecuenta 
								AND f.departamento=dpto.departamento
								$sql_centro $sql_unidad $sql_dpto  
								AND c.tipo_id_paciente=pac.tipo_id_paciente 
								AND c.paciente_id=pac.paciente_id 
								
								AND dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
								AND dpto.empresa_id=rep.empresa_id
								AND dpto.centro_utilidad=rep.centro_utilidad
								AND dpto.unidad_funcional=rep.unidad_funcional
								AND dpto.departamento=rep.departamento
								AND rep.usuario_id='".UserGetUID()."'
								
								ORDER BY ter.nombre_tercero";
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			$result = $dbconn->Execute($query);
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
			if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}else{
				while ($data = $result->FetchRow()){
						$vars[$data['usuario_id']][$data['nombre_tercero']][$data['agenda_cita_asignada_id']] = $data;
				}
			}
			$result->Close();
			return $vars;
  }
    /**
    *
    */
    function LlamaEstadisticoRendimientoPersonal()
    {
      if((!empty($_REQUEST['feinictra']) AND empty($_REQUEST['fefinctra'])) OR (empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra'])))
      {
        $this->frmError["feinictra"]=1;
        $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
        $this->ReporteEstadisticoRendimientoPersonal();
        return true;
  		}
            
      $this->EstadisticaRendimientoPersonal($_REQUEST['centroU'],$_REQUEST['centroutilidad'],$_REQUEST['unidadF'],
      $_REQUEST['unidadfunc'],$_REQUEST['departamento'],$_REQUEST['DptoSel'],$_REQUEST['usuario_escojer'],
      $_REQUEST['feinictra'],$_REQUEST['fefinctra']);
      return true;
    }
    /**
    *
    */
    function ConsultaEstadisticaRendimientoPersonal($centroU,$unidadF,$DptoSel,$usuario_escojer,$feinictra,$fefinctra,$plan_afiliacion)
    {
      GLOBAL $ADODB_FETCH_MODE;
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      if(!empty($centroU)){
        $sql_centro = " AND dpto.centro_utilidad = '".$centroU."'";
      }
      if(!empty($unidadF)){
        $sql_unidad = " AND dpto.unidad_funcional = '".$unidadF."'";
      }
      if(!empty($DptoSel)){
        $sql_dpto = " AND dpto.departamento = '".$DptoSel."'";
      }
      if($usuario_escojer != '-1')
      {
        $usuario_id = explode(',',$usuario_escojer);
        if(!empty($usuario_id[0]))
        {
          $sql_usuario = " AND usut.usuario_id = ".$usuario_id[0]."";
          $sql_usuarioI = " AND     CU.usuario_id  = ".$usuario_id[0]."";
        }
      }
      if(!empty($feinictra) AND !empty($fefinctra))
      {
        $feinictra = $this->FechaStamp($feinictra);
        $fefinctra = $this->FechaStamp($fefinctra);
        if(!empty($feinictra) AND !empty($fefinctra))
        {
          $sql_fecha = " AND c.fecha_registro::date BETWEEN '".$feinictra."' AND '".$fefinctra."'";
        }
      }
      
      $fplan = "";
      if($plan_afiliacion != '-1' && $plan_afiliacion)
        $fplan = "  AND     c.plan_id = ".$plan_afiliacion." ";
      
      $query = "SELECT DISTINCT d.usuario_id,
                        d.nombre
                FROM    agenda_turnos a,
                        agenda_citas b,
                        agenda_citas_asignadas c,
                        system_usuarios d,
                        tipos_consulta x,
                        departamentos dpto,
                        userpermisos_tipos_consulta  usut,
                        userpermisos_repconsultaexterna rep 
                WHERE   a.agenda_turno_id=b.agenda_turno_id 
                AND     b.agenda_cita_id=c.agenda_cita_id 
                AND     c.agenda_cita_id=c.agenda_cita_id_padre 
                AND     c.usuario_id=d.usuario_id 
                AND     a.tipo_consulta_id=x.tipo_consulta_id 
                AND     x.departamento=dpto.departamento 
                AND     d.usuario_id=usut.usuario_id 
                AND     usut.tipo_consulta_id=x.tipo_consulta_id
            		AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
            		AND     dpto.empresa_id=rep.empresa_id
            		AND     dpto.centro_utilidad=rep.centro_utilidad
            		AND     dpto.unidad_funcional=rep.unidad_funcional
            		AND     dpto.departamento=rep.departamento
            		AND     rep.usuario_id='".UserGetUID()."'
  						  $sql_centro 
                $sql_unidad 
                $sql_dpto 
                $sql_usuario 
                $sql_fecha 
                ".$fplan."
                UNION DISTINCT 
                SELECT DISTINCT d.usuario_id,
                        d.nombre
                FROM    agenda_turnos a,
                        agenda_citas b,
                        agenda_citas_asignadas c,
                        os_cruce_citas e,
                        os_maestro f,
                        cuentas CU,
                        system_usuarios d,
                        tipos_consulta x,
                        departamentos dpto,
                        userpermisos_repconsultaexterna rep
                WHERE   a.agenda_turno_id=b.agenda_turno_id 
                AND     b.agenda_cita_id=c.agenda_cita_id 
                AND     c.agenda_cita_id=c.agenda_cita_id_padre 
                AND     a.tipo_consulta_id=x.tipo_consulta_id 
                AND     x.departamento=dpto.departamento
            		AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
            		AND     dpto.empresa_id=rep.empresa_id
            		AND     dpto.centro_utilidad=rep.centro_utilidad
            		AND     dpto.unidad_funcional=rep.unidad_funcional
            		AND     dpto.departamento=rep.departamento
            		AND     rep.usuario_id='".UserGetUID()."'
                AND     c.agenda_cita_asignada_id=e.agenda_cita_asignada_id 
                AND     e.numero_orden_id=f.numero_orden_id 
                AND     CU.numerodecuenta = f.numerodecuenta
                AND     d.usuario_id= CU.usuario_id 
  						  $sql_centro 
                $sql_unidad 
                $sql_dpto 
                $sql_usuarioI 
                $sql_fecha
                ".$fplan." ";
      
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $result = $dbconn->Execute($query);
      $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
      if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }else{
        while($data = $result->FetchRow()){
          $vars[$data['usuario_id']] = $data;
        }
      }
      $result->Close();
      return $vars;
    }
    /**
    *
    */
    function CitasAsignadasCanceladasRendimientoPersonal($usuario,$centroU,$unidadF,$DptoSel,$usuario_escojer,$feinictra,$fefinctra,$plan_afiliacion)
    {
      list($dbconn) = GetDBconn();
      //$dbconn->debug = true;
      if(!empty($centroU))
      {
        $sql_centro = " AND dpto.centro_utilidad = '".$centroU."'";
      }
      
      if(!empty($unidadF))
      {
        $sql_unidad = " AND dpto.unidad_funcional = '".$unidadF."'";
      }
      
      if(!empty($DptoSel))
      {
        $sql_dpto = " AND dpto.departamento = '".$DptoSel."'";
      }
      
      if($usuario_escojer != '-1')
      {
        $usuario_id = explode(',',$usuario_escojer);
        if(!empty($usuario_id[0]))
        {
          $sql_usuario = " AND usut.usuario_id = ".$usuario_id[0]."";
        }
      }
      
      if(!empty($feinictra) AND !empty($fefinctra))
      {
        $feinictra = $this->FechaStamp($feinictra);
        $fefinctra = $this->FechaStamp($fefinctra);
        if(!empty($feinictra) AND !empty($fefinctra)){
          $sql_fecha = " AND date(c.fecha_registro) BETWEEN '".$feinictra."' AND '".$fefinctra."'";
        }
      }
      
      $fplan = "";
      if($plan_afiliacion != '-1' && $plan_afiliacion)
        $fplan = "  AND     c.plan_id = ".$plan_afiliacion." ";
      
      $sql  = "SELECT  count(*) as asignadas ";
      $sql .= "FROM    agenda_turnos a,";
      $sql .= "        agenda_citas b,";
      $sql .= "        agenda_citas_asignadas c,";
      $sql .= "        tipos_consulta x,";
      $sql .= "        departamentos dpto ";
      $sql .= "WHERE   c.usuario_id='".$usuario."' ";
      $sql .= "AND     a.agenda_turno_id=b.agenda_turno_id ";
      $sql .= "AND     b.agenda_cita_id=c.agenda_cita_id ";
      $sql .= "AND     c.agenda_cita_id=c.agenda_cita_id_padre ";
      $sql .= "AND     a.tipo_consulta_id=x.tipo_consulta_id ";
      $sql .= "AND     x.departamento=dpto.departamento ";
      $sql .= "AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."' ";
  		$sql .= $sql_centro." ";
      $sql .= $sql_unidad." "; 
      $sql .= $sql_dpto." ";
      $sql .= $sql_fecha." ";
      $sql .= $fplan;
      
      $result = $dbconn->Execute($sql);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $vars['asignadas'] = $result->fields[0];
      
      $query = "SELECT  count(*) as cumplidas
                FROM    agenda_turnos a,
                        agenda_citas b,
                        agenda_citas_asignadas c,
                        os_cruce_citas as e,
                        os_maestro f,
                        cuentas CU,
                        tipos_consulta x,
                        departamentos dpto
                WHERE   a.agenda_turno_id=b.agenda_turno_id 
                AND     b.agenda_cita_id=c.agenda_cita_id 
                AND     c.agenda_cita_id=c.agenda_cita_id_padre 
                AND     c.agenda_cita_asignada_id=e.agenda_cita_asignada_id 
                AND     e.numero_orden_id=f.numero_orden_id 
                AND     a.tipo_consulta_id=x.tipo_consulta_id 
                AND     x.departamento=dpto.departamento
            		AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
                AND     CU.numerodecuenta = f.numerodecuenta
                AND     CU.usuario_id = ".$usuario."
                $sql_centro 
                $sql_unidad 
                $sql_dpto 
                $sql_fecha 
                ".$fplan." ";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      
      $vars['cumplimiento'] = $result->fields[0];
      
      $query="SELECT count(*) as canceladas
              FROM  agenda_turnos a,
                    agenda_citas b,
                    agenda_citas_asignadas c,
                    tipos_consulta x,
                    departamentos dpto,
                    agenda_citas_asignadas_cancelacion AC
              WHERE AC.usuario_id='".$usuario."' 
              AND   a.agenda_turno_id=b.agenda_turno_id 
              AND   b.agenda_cita_id=c.agenda_cita_id 
              AND   c.agenda_cita_id=c.agenda_cita_id_padre 
              AND   c.agenda_cita_asignada_id = AC.agenda_cita_asignada_id
              AND   a.tipo_consulta_id=x.tipo_consulta_id 
              AND   x.departamento=dpto.departamento
          		AND   dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
              $sql_centro 
              $sql_unidad 
              $sql_dpto 
              $sql_fecha 
              ".$fplan." ";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $vars['canceladas'] = $result->fields[0];
      
      $query = "SELECT  count(*) as cantidaddias
                FROM    (
                          SELECT  DISTINCT c.fecha_registro::date
                          FROM    agenda_turnos a,
                                  agenda_citas b,
                                  agenda_citas_asignadas c,
                                  system_usuarios d,
                                  userpermisos_tipos_consulta  usut,
                                  tipos_consulta x,departamentos dpto,
                                  userpermisos_repconsultaexterna rep
                          WHERE   d.usuario_id='".$usuario."' 
                          AND     a.agenda_turno_id=b.agenda_turno_id 
                          AND     b.agenda_cita_id=c.agenda_cita_id 
                          AND     c.agenda_cita_id=c.agenda_cita_id_padre 
                          AND     c.usuario_id=d.usuario_id 
                          AND     d.usuario_id=usut.usuario_id 
                          AND     usut.tipo_consulta_id=x.tipo_consulta_id 
                          AND     a.tipo_consulta_id=x.tipo_consulta_id 
                          AND     x.departamento=dpto.departamento
                      		AND     dpto.empresa_id='".$_SESSION['recoex']['empresa']."'
                      		AND     dpto.empresa_id=rep.empresa_id
                      		AND     dpto.centro_utilidad=rep.centro_utilidad
                      		AND     dpto.unidad_funcional=rep.unidad_funcional
                      		AND     dpto.departamento=rep.departamento
                      		AND     rep.usuario_id='".UserGetUID()."'
                      		$sql_centro 
                          $sql_unidad 
                          $sql_dpto 
                          $sql_usuario 
                          $sql_fecha
                          ".$fplan."
                        ) as x";
                        
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      $vars['cantidaddias'] = $result->fields[0];
      
      $result->Close();
      return $vars;
    }

  function Get_UsuariosAsignanCitas(){
    GLOBAL $ADODB_FETCH_MODE;
    list($dbconn) = GetDBconn();
    if($_SESSION['recoex']['auditor']!=1){
      $fil=" AND b.usuario_id='".UserGetUID()."'";
    } 
		$query="SELECT DISTINCT a.usuario_id,b.nombre
    FROM userpermisos_tipos_consulta a,system_usuarios b
    WHERE a.usuario_id=b.usuario_id $fil
    ORDER BY nombre ASC;";
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
      while($data = $result->FetchRow()){
        $vars[] = $data;
      }
    }
    $result->Close();
    return $vars;
  }
  
  //FUNCIONES PARA LA DESCARGA DE LOS DATOS DEL REPORTE
  
  function EncabezadoArchivo($titulo,$empresa,$centroUtilidad,$unidadFuncional,$departamento,$profesional,$fechaIn,$fechaFi){
    $cadena.=$titulo."\n";
    $cadena.='EMPRESA'.'|'.$empresa."\n\n";
    $cadena.='DATOS DE LA BUSQUEDA'."\n";
    $cadena.='CENTRO UTILIDAD'.'|'.$centroUtilidad."\n";
    $cadena.='UNIDAD FUNCIONAL'.'|'.$unidadFuncional."\n";
    $cadena.='DEPARTAMENTO'.'|'.$departamento."\n";
    $cadena.='PROFESIONAL'.'|'.$profesional."\n";
    $cadena.='FECHA INICIAL'.'|'.$fechaIn."\n";
    $cadena.='FECHA FINAL'.'|'.$fechaFi."\n\n";
    return $cadena;
  }
  
  function DescargaDatosCausasCitasMedicas(){
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE CAUSAS DE CONSULTAS MÉDICAS',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteCausasCitas'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE CAUSAS DE CONSULTAS MÉDICAS';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCausasCitasMedicas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosAgendasMedicas(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO AGENDAS MÉDICAS',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteAgendasMedicas'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO AGENDAS MÉDICAS';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaFormaAgendaMedica',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosCausasTiposCitasMedicas(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE CAUSAS Y TIPOS DE CITAS MÉDICAS',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteCausasTiposCitasMedicas'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE CAUSAS Y TIPOS DE CITAS MÉDICAS';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','BusquedaReporteEstadisticasCausasTipo',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosOrdenesServicio(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE ORDENES DE SERVICIO',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteOrdenesServicio'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE ORDENES DE SERVICIO';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteOrdenesServicio',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosCitasCanceladas(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteCitasCanceladas'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCitasCanceladas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosHCAbiertasCerradas(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE HISTORIAS CLINICAS ABIERTAS Y CERRADAS',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteHCAbiertasCerradas'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE HISTORIAS CLINICAS ABIERTAS Y CERRADAS';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteHCAbiertasyCerradas',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosCitasCanceladasConsolidado(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO  DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteCitasCanceladasConsolidado'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO  DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReporteCitasCanceladasConsolidado',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosCitasCanceladasConsolidadoEntidad(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO EN LA ENTIDAD',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteCitasCanceladasConsolidadoEntidad'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE MOTIVOS DE CANCELACION DE CITAS MÉDICAS CONSOLIDADO EN LA ENTIDAD';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','ReporteCitasCanceladasConsolidadoEntidad',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosCaracteristicasPacientes(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE CARACTERISTICAS DE PACIENTES',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteCaracteristicasPacientes'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE CARACTERISTICAS DE PACIENTES';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCaracteristicasPaciente',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosTratamientoOdontologico(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO CITAS DE CITAS DE TRATAMIENTO ODONTOLOGICO',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteTratamientoOdontologico'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO CITAS DE CITAS DE TRATAMIENTO ODONTOLOGICO';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','RegistrosReporteCitasTratamientoOdontologico',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosRendimientoProf(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE RENDIMIENTO PROFESIONALES',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteRendimientoProf'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE RENDIMIENTO PROFESIONALES';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaEstadisticoRendimientoProf',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosOportunidadCE(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE OPORTUNIDADES DE CITAS MÉDICAS',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteOportunidadCitasMedicas'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE OPORTUNIDADES DE CITAS MÉDICAS';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaEstadisticoOportunidadCE',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
  function DescargaDatosRendimientoPersonal(){
    
    $archivo='';
    $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
    
    $archivo.=$this->EncabezadoArchivo('REPORTE ESTADISTICO DE RENDIMIENTO DEL PERSONAL',    
              $_SESSION['recoex']['razonso'],$_REQUEST['centroutilidad'],$_REQUEST['unidadfunc'],$_REQUEST['departamento'],
              $usuario_id[1],$_REQUEST['feinictra'],$_REQUEST['fefinctra']);   
    $archivo.=$_SESSION['DESCARGA_DATOS_REPORTES']['DATOS'];
       
    $name='reporteRendimientoPersonal'.date("Y").date("m").date("d").'.txt';     
    if(!$this->AbrirArchivo($name)){
        return false;
    }
    if(!$this->EscribirArchivo($archivo)){
      return false;
    }
    if(!$this->CerrarArchivo()){
      return false;
    }
    $mensaje="Datos Generados Satisfactoriamente, de Click en Guardar para almacenar los datos";
    $titulo='REPORTE ESTADISTICO DE RENDIMIENTO DEL PERSONAL';    
    $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaEstadisticoRendimientoPersonal',array('centroutilidad'=>$_REQUEST['centroutilidad'],'unidadfunc'=>$_REQUEST['unidadfunc'],
    'departamento'=>$_REQUEST['departamento'],'profesional_escojer'=>$_REQUEST['profesional_escojer'],"feinictra"=>$_REQUEST['feinictra'],"fefinctra"=>$_REQUEST['fefinctra'],
    'centroU'=>$_REQUEST['centroU'],'unidadF'=>$_REQUEST['unidadF'],'DptoSel'=>$_REQUEST['DptoSel']));        
    $dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $ruta=$dir.'/REPORTES_CE/'.$name;      
    $download = download($ruta,$nombre="GUARDAR ARCHIVO",$link=false,$comprimir=false,$boton=true);
    $this->FormaMensaje($mensaje,$titulo,$accion,$download);    
    return true;
  }
  
    /**
    Metodo  para abrir el archivo de rips dependiendo su tipo
    */
    function AbrirArchivo($name){   
        
        $dir=dirname($_SERVER['SCRIPT_FILENAME']);        
        if(!is_dir($dir.'/REPORTES_CE'))
        {
          mkdir($dir.'/REPORTES_CE',0777);
        }          
        $file=$dir.'/REPORTES_CE/'.$name;             
        $this->archivodat = fopen($file,'w+');
        if(!$this->archivodat)
        {
            $this->error = "Error en el Reporte";
            $this->mensajeDeError = 'NO SE PUDO CREAR EL ARCHIVO.';
            return false;
        }

        if(feof($this->archivodat))
        {
            $this->error = "Error en el Reporte";
            $this->mensajeDeError = 'Fin del Archivo...';
            return false;
        }
        return true;      
    }

    /**
    Metodo que escribe en el archivo
    */
    function EscribirArchivo($texto)
    {
        fwrite($this->archivodat,$texto);
        return true;
    }


    function CerrarArchivo()
    {
      if(!fclose($this->archivodat))
      {
            $this->error = "Error en el Reporte";
            $this->mensajeDeError = 'No pude cerrar El archivo...';
            return false;
      }
      return true;
    }
  }
?>