
<?php

/**
* Submodulo de Odontograma Primera Vez.
*
* Submodulo para manejar el odontograma del paciente, en su primera atencion medica
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_OdontogramaTratamiento.php,v 1.57 2007/07/09 19:20:54 tizziano Exp $
*/

/**
* Odontograma Primera Vez
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de odontograma primera vez.
*/

class OdontogramaTratamiento extends hc_classModules
{
    var $limit;
    var $conteo;

    function OdontogramaTratamiento()
    {
        $this->limit=GetLimitBrowser();
        return true;
    }

    function GetVersion()
    {
        $informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JORGE ELIECER AVILA',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'requerimientos_adicionales' => '',
        'version_kernel' => '1.0'
        );
        return $informacion;
    }

    function GetReporte_Html()
    {
        $imprimir=$this->frmHistoria();
        if($imprimir==false)
        {
            return true;
        }
        return $imprimir;
    }

    function GetConsulta()
    {
        if($this->frmConsulta()==false)
        {
            return true;
        }
        return $this->salida;
    }

    function GetEstado()
    {
        $primeravez=$this->ComprobarOdontogramaPrimeraVezInactivar();
        $tratamient=$this->ComprobarOdontogramaTratamientoInactivar();
        if(sizeof($primeravez)==0 AND sizeof($tratamient)==0
        AND $this->tipo_profesional!=10)
        {
            $this->EliminarOdontogramasActivo();
        }
        return true;
    }

    function ComprobarOdontogramaPrimeraVezInactivar()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_primera_vez_detalle_id
        FROM hc_odontogramas_primera_vez_detalle AS A,
        hc_odontogramas_primera_vez AS B
        WHERE B.tipo_id_paciente='".$this->tipoidpaciente."'
        AND B.paciente_id='".$this->paciente."'
        AND B.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND (A.estado='1'
        OR A.estado='4');";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function ComprobarOdontogramaTratamientoInactivar()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_tratamiento_detalle_id
        FROM hc_odontogramas_tratamientos_detalle AS A,
        hc_odontogramas_tratamientos AS B
        WHERE B.tipo_id_paciente='".$this->tipoidpaciente."'
        AND B.paciente_id='".$this->paciente."'
        AND B.sw_activo='1'
        AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
        AND (A.estado='1'
        OR A.estado='4');";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function GetForma()//Desde esta funcion es de JORGE AVILA
    {
        $pfj=$this->frmPrefijo;
        if(empty($_REQUEST['accion'.$pfj]))
        {
            $this->frmForma();
        }
        elseif($_REQUEST['accion'.$pfj]=='corregir')
        {
            if($this->CorrregirDientes()==true)
            {
                if($this->frmError["MensajeError"]=="FALTAN DATOS OBLIGATORIOS")
                {
                    $this->frmTratamientos();
                }
                else
                {
                    $this->frmForma();
                }
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='corregirtrat')
        {
            if($this->CorrregirDientes2()==true)
            {
                if($this->frmError["MensajeError"]=="FALTAN DATOS OBLIGATORIOS")
                {
                    $this->frmTratamientosTra();
                }
                else
                {
                    $this->frmForma();
                }
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='insertarpresupuestos')
        {
            $this->InsertarPresupuestos();
            if($this->frmError["MensajeError"]=="FALTAN DATOS OBLIGATORIOS<br>O LA CANTIDAD ENVIADA O PENDIENTE ES MENOR A LA REALIZADA")
            {
                $this->frmTratamientosPresupuestos();
            }
            else
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='insertarapoyos')
        {
            $this->InsertarApoyos();
            if($this->frmError["MensajeError"]=="FALTAN DATOS OBLIGATORIOS<br>O LA CANTIDAD ENVIADA O PENDIENTE ES MENOR A LA REALIZADA")
            {
                $this->frmTratamientosApoyos();
            }
            else
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='activar')
        {
            if($this->ActivarDientes()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='borrar')
        {
            if($this->BorrarDientes()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='borrartrat')
        {
            if($this->BorrarDientesTra()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='insertar')
        {
            if($this->InsertarDientes()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='insertarobser')
        {
            if($this->InsertDatosObser()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='insertarjustif')
        {
            if($this->InsertJustif()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='borrartrat')
        {
            if($this->BorrarDientesTra()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='eliminartrat')
        {
            if($this->BorrarTratamiento()==true)
            {
                $this->frmForma();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='cancelarapoyos')
        {
            if($this->CancelarApoyos()==true)
            {
                $this->frmTratamientosApoyos();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='activarapoyos')
        {
            if($this->ActivarApoyos()==true)
            {
                $this->frmTratamientosApoyos();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='cancelarpresupuestos')
        {
            if($this->CancelarPresupuestos()==true)
            {
                $this->frmTratamientosPresupuestos();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='activarpresupuestos')
        {
            if($this->ActivarPresupuestos()==true)
            {
                $this->frmTratamientosPresupuestos();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='insertarcargosnopres')
        {
            if($this->InsertCargosNoPresupuTrata()==true)
            {
                $this->frmNuevosCargosPresupuesto();
            }
        }
        elseif($_REQUEST['accion'.$pfj]=='tratamientospresupuestos')
        {
            $this->frmTratamientosPresupuestos();
        }
        elseif($_REQUEST['accion'.$pfj]=='tratamientos')
        {
            $this->frmTratamientos();
        }
        elseif($_REQUEST['accion'.$pfj]=='tratamientoshistorial')
        {
            $this->frmTratamientosHistorial();
        }
        elseif($_REQUEST['accion'.$pfj]=='cancelar')
        {
            $this->frmCancelar();
        }
        elseif($_REQUEST['accion'.$pfj]=='modificartratamiento')
        {
            $this->frmModificarTratamientos();
        }
        elseif($_REQUEST['accion'.$pfj]=='tratamientos2')
        {
            $this->frmTratamientosTra();//$recibe
        }
        elseif($_REQUEST['accion'.$pfj]=='nuevoscargosnopres')
        {
            $this->frmNuevosCargosPresupuesto();
        }
        elseif($_REQUEST['accion'.$pfj]=='tratamientosapoyos')
        {
            $this->frmTratamientosApoyos();
        }
        elseif($_REQUEST['accion'.$pfj]=='insertar_diagnosticos')
        {
            $this->InsertDiagnosticosTratam();
            $this->frmTratamientos();
        }
        elseif($_REQUEST['accion'.$pfj]=='insertar_diagnosticos2')
        {
            $this->InsertDiagnosticosTratam2();
            $this->frmTratamientosTra();
        }
        elseif($_REQUEST['accion'.$pfj]=='insertar_diagnosticos3')
        {
            $this->InsertDiagnosticosTratam3();
            $this->frmTratamientosPresupuestos();
        }
        elseif($_REQUEST['accion'.$pfj]=='insertar_diagnosticos4')
        {
            $this->InsertDiagnosticosTratam4();
            $this->frmTratamientosApoyos();
        }
        elseif($_REQUEST['accion'.$pfj]=='eliminar_diagnosticos')
        {
            $this->EliminarDiagnosticosTratam();
            $this->frmTratamientos();
        }
        elseif($_REQUEST['accion'.$pfj]=='eliminar_diagnosticos2')
        {
            $this->EliminarDiagnosticosTratam2();
            $this->frmTratamientosTra();
        }
        elseif($_REQUEST['accion'.$pfj]=='eliminar_diagnosticos3')
        {
            $this->EliminarDiagnosticosTratam3();
            $this->frmTratamientosPresupuestos();
        }
        elseif($_REQUEST['accion'.$pfj]=='eliminar_diagnosticos4')
        {
            $this->EliminarDiagnosticosTratam4();
            $this->frmTratamientosApoyos();
        }
        elseif($_REQUEST['accion'.$pfj]=='cambiardiagnostico1')
        {
            $this->CambiarDiagnosticos1();
            $this->frmTratamientos();
        }
        elseif($_REQUEST['accion'.$pfj]=='cambiardiagnostico2')
        {
            $this->CambiarDiagnosticos2();
            $this->frmTratamientosTra();
        }
        elseif($_REQUEST['accion'.$pfj]=='cambiardiagnostico3')
        {
            $this->CambiarDiagnosticos3();
            $this->frmTratamientosPresupuestos();
        }
        elseif($_REQUEST['accion'.$pfj]=='cambiardiagnostico4')
        {
            $this->CambiarDiagnosticos4();
            $this->frmTratamientosApoyos();
        }
        return $this->salida;
    }

    function CalcularNumeroPasos($conteo)
    {
        $numpaso=ceil($conteo/$this->limit);
        return $numpaso;
    }

    function CalcularBarra($paso)
    {
        $barra=floor($paso/10)*10;
        if(($paso%10)==0)
        {
            $barra=$barra-10;
        }
        return $barra;
    }

    function CalcularOffset($paso)
    {
        $offset=($paso*$this->limit)-$this->limit;
        return $offset;
    }

    function BuscarDiagnosticosApoyosTratamGuarda()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.diagnostico_id,
        B.diagnostico_nombre,
        C.hc_odontograma_primera_vez_id,
        C.evolucion_id,
        C.sw_principal,
        C.tipo_diagnostico
        FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
        diagnosticos AS B,
        hc_odontogramas_tratamientos_evolucion_apoyod AS C,
        hc_odontogramas_primera_vez AS D
        WHERE A.diagnostico_id=B.diagnostico_id
        AND A.diagnostico_id=C.diagnostico_id
        AND C.cargo='".$_REQUEST['cargotrata'.$pfj]."'
        AND C.hc_odontograma_primera_vez_id=D.hc_odontograma_primera_vez_id
        AND D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."'
        AND D.sw_activo='1'
        ORDER BY A.diagnostico_id;";
        $resulta = $dbconn->Execute($query);
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
        return $var;
    }

    function BuscarDiagnosticosApoyosTratam()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $codigo=STRTOUPPER ($_REQUEST['codigo'.$pfj]);
        $diagnostico=STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
        $busqueda1='';
        $busqueda2='';
        if ($codigo!='')
        {
            $busqueda1 ="AND A.diagnostico_id LIKE '$codigo%'";
        }
        if($diagnostico!='')
        {
            $busqueda2 ="AND B.diagnostico_nombre LIKE '%$diagnostico%'";
        }
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ) AS r;";
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
            $this->conteo=$_REQUEST['conteo'.$pfj];
        }
        if(!$_REQUEST['Of'.$pfj])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'.$pfj];
            if($Of > $this->conteo)
            {
                $Of=0;
                $_REQUEST['Of'.$pfj]=0;
                $_REQUEST['paso1'.$pfj]=1;
            }
        }
        $query="SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre,
                    C.hc_odontograma_primera_vez_id,
                    C.sw_principal,
                    C.tipo_diagnostico
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A
                    LEFT JOIN hc_odontogramas_tratamientos_evolucion_apoyod AS C ON
                    (C.hc_odontograma_primera_vez_id=".$odonto."
                    AND C.cargo='".$_REQUEST['cargotrata'.$pfj]."'
                    AND C.evolucion_id=".$this->evolucion."
                    AND A.diagnostico_id=C.diagnostico_id),
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ORDER BY A.diagnostico_id
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
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
        {
            $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
        }
        return $var;
    }

    function InsertarApoyos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        if($_REQUEST['validadiag'.$pfj]==0
        OR $_REQUEST['evolucprtra'.$pfj]==NULL
        OR $_REQUEST['cantida2'.$pfj]==NULL
        OR $_REQUEST['cantida2'.$pfj]==0
        OR $_REQUEST['cantidad'.$pfj]<$_REQUEST['cantida2'.$pfj]
        OR ($_REQUEST['cantida3'.$pfj]<$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]>0))
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS<br>O LA CANTIDAD ENVIADA O PENDIENTE ES MENOR A LA REALIZADA";
            return true;
        }
        if($_REQUEST['cantidad'.$pfj]==$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]==0)
        {
            $estado=$cantidadpe=0;
        }
        else if($_REQUEST['cantidad'.$pfj]>$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]==0)
        {
            $estado=1;
            $cantidadpe=$_REQUEST['cantidad'.$pfj]-$_REQUEST['cantida2'.$pfj];
        }
        else if($_REQUEST['cantida2'.$pfj]==$_REQUEST['cantida3'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]<>0)
        {
            $estado=$cantidadpe=0;
        }
        else if($_REQUEST['cantida3'.$pfj]>$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]<>0)
        {
            $estado=1;
            $cantidadpe=$_REQUEST['cantida3'.$pfj]-$_REQUEST['cantida2'.$pfj];
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_apoyod SET
        estado='".$estado."',
        cantidad_pend='".$cantidadpe."',
        evolucion='".$_REQUEST['evolucprtra'.$pfj]."',
        usuario_id=".UserGetUID()."
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo=".$_REQUEST['cargotrata'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $dbconn->RollbackTrans();
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        
        $query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
        $result=$dbconn->Execute($query);
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error ingresos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
        }       
                
        $query="INSERT INTO hc_os_solicitudes
        (cargo,
        evolucion_id,
        plan_id,
        os_tipo_solicitud_id,
        sw_estado,
        cantidad,
        hc_os_solicitud_id)
        VALUES
        ('".$_REQUEST['cargotrata'.$pfj]."',
        ".$this->evolucion.",
        ".$this->plan.",
        'PSC',
        '3',
        '".$_REQUEST['cantida2'.$pfj]."',".$result->fields[0].");";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $dbconn->RollbackTrans();
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $query="SELECT DISTINCT cantidad_realizada
        FROM hc_odontogramas_tratamientos_evolucion_apoyod
        WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
        AND hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND evolucion_id=".$this->evolucion.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        while(!$resulta->EOF)
        {
            $cantidad_guardada=$cantidad_guardada+$resulta->fields[0];
            $resulta->MoveNext();
        }
        $cantidad_guardada=$cantidad_guardada+$_REQUEST['cantida2'.$pfj];
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_apoyod SET
        cantidad_realizada=".$cantidad_guardada.",
        hc_os_solicitud_id=".$result->fields[0]."
        WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
        AND hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND evolucion_id=".$this->evolucion.";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
        return true;
    }

    function CancelarApoyos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_apoyod SET
        estado='2'
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo=".$_REQUEST['cargotrata'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $dbconn->CommitTrans();
        $_REQUEST['estado'.$pfj]='2';
        $this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
        return true;
    }

    function ActivarApoyos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_apoyod SET
        estado='1'
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo=".$_REQUEST['cargotrata'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $dbconn->CommitTrans();
        $_REQUEST['estado'.$pfj]='1';
        $this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
        return true;
    }

    //FUNCIÓN BUSCAR CARGOS SIN MODIFICADAR - ORIGINAL
    function BuscarCargosPlan()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $codigo = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
        $diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
        $busqueda1 = '';
        $busqueda2 = '';
        if ($codigo != '')
        {
            $busqueda1 ="AND A.cargo LIKE '$codigo%'";
        }
        if($diagnostico != '')
        {
            $busqueda2 ="AND B.descripcion LIKE '%$diagnostico%'";
        }
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT A.cargo,
                    B.descripcion,
                    C.descripcion AS desplantra
                    FROM hc_tipos_plan_tratamiento_cargo AS A,
                    cups AS B,
                    hc_tipos_plan_tratamiento AS C
                    WHERE A.cargo=B.cargo
                    AND (A.hc_tipo_plan_tratamiento_id=1
                    OR A.hc_tipo_plan_tratamiento_id=5
                    OR A.hc_tipo_plan_tratamiento_id=6
                    OR A.hc_tipo_plan_tratamiento_id=7
                    OR A.hc_tipo_plan_tratamiento_id=9)
                    AND A.hc_tipo_plan_tratamiento_id=C.hc_tipo_plan_tratamiento_id
                    $busqueda1
                    $busqueda2
                    ) AS r;";
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
            $this->conteo=$_REQUEST['conteo'.$pfj];
        }
        if(!$_REQUEST['Of'.$pfj])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'.$pfj];
            if($Of > $this->conteo)
            {
                $Of=0;
                $_REQUEST['Of'.$pfj]=0;
                $_REQUEST['paso1'.$pfj]=1;
            }
        }
        $query="SELECT A.cargo,
                    B.descripcion,
                    C.descripcion AS desplantra,
                    D.cantidad,
                    D.cantidad_pend,
                    D.estado,
                    D.hc_odontograma_tratamiento_id,
                    D.hc_odontogramas_primera_vez_presupuesto_id,
                    ".$odonto." AS hc_odontograma_primera_vez_id
                    FROM hc_tipos_plan_tratamiento_cargo AS A
                    LEFT JOIN hc_odontogramas_primera_vez_presupuesto AS D ON
                    (A.cargo=D.cargo
                    AND D.hc_odontograma_primera_vez_id=".$odonto."),
                    cups AS B,
                    hc_tipos_plan_tratamiento AS C
                    WHERE A.cargo=B.cargo
                    AND (A.hc_tipo_plan_tratamiento_id=1
                    OR A.hc_tipo_plan_tratamiento_id=5
                    OR A.hc_tipo_plan_tratamiento_id=6
                    OR A.hc_tipo_plan_tratamiento_id=7
                    OR A.hc_tipo_plan_tratamiento_id=9)
                    AND A.hc_tipo_plan_tratamiento_id=C.hc_tipo_plan_tratamiento_id
                    $busqueda1
                    $busqueda2
                    ORDER BY A.cargo
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query); 
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
        {
            $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
        }
        return $var;
    }
    
    //FUNCIÓN BUSCAR CARGOS MODIFICADA-FUNCION AUXILIAR PARA NO MOSTAR LOS
    //CARGOS REPETIDOS
    function BuscarCargosPlan2()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $codigo = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
        $diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
        $busqueda1 = '';
        $busqueda2 = '';
        if ($codigo != '')
        {
            $busqueda1 ="AND A.cargo LIKE '$codigo%'";
        }
        if($diagnostico != '')
        {
            $busqueda2 ="AND B.descripcion LIKE '%$diagnostico%'";
        }
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT DISTINCT A.cargo,
                    B.descripcion,
                    C.descripcion AS desplantra
                    FROM hc_tipos_plan_tratamiento_cargo AS A,
                    cups AS B,
                    hc_tipos_plan_tratamiento AS C
                    WHERE A.cargo=B.cargo
                    AND (A.hc_tipo_plan_tratamiento_id=1
                    OR A.hc_tipo_plan_tratamiento_id=5
                    OR A.hc_tipo_plan_tratamiento_id=6
                    OR A.hc_tipo_plan_tratamiento_id=7
                    OR A.hc_tipo_plan_tratamiento_id=9)
                    AND A.hc_tipo_plan_tratamiento_id=C.hc_tipo_plan_tratamiento_id
                    $busqueda1
                    $busqueda2
                    ) AS r;";
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
            $this->conteo=$_REQUEST['conteo'.$pfj];
        }
        if(!$_REQUEST['Of'.$pfj])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'.$pfj];
            if($Of > $this->conteo)
            {
                $Of=0;
                $_REQUEST['Of'.$pfj]=0;
                $_REQUEST['paso1'.$pfj]=1;
            }
        }
        $query="SELECT DISTINCT A.cargo,
                    B.descripcion,
                    C.descripcion AS desplantra,
                    ".$odonto." AS hc_odontograma_primera_vez_id
                    FROM hc_tipos_plan_tratamiento_cargo AS A
                    LEFT JOIN hc_odontogramas_primera_vez_presupuesto AS D ON
                    (A.cargo=D.cargo
                    AND D.hc_odontograma_primera_vez_id=".$odonto."),
                    cups AS B,
                    hc_tipos_plan_tratamiento AS C
                    WHERE A.cargo=B.cargo
                    AND (A.hc_tipo_plan_tratamiento_id=1
                    OR A.hc_tipo_plan_tratamiento_id=5
                    OR A.hc_tipo_plan_tratamiento_id=6
                    OR A.hc_tipo_plan_tratamiento_id=7
                    OR A.hc_tipo_plan_tratamiento_id=9)
                    AND A.hc_tipo_plan_tratamiento_id=C.hc_tipo_plan_tratamiento_id
                    $busqueda1
                    $busqueda2
                    ORDER BY A.cargo
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
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
        {
            $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
        }
        return $var;
    }

    function InsertCargosNoPresupuTrata()
    {
        $pfj=$this->frmPrefijo;
        $contador1=$contador2=$contador3=0;
         $fecha_registro=date("Y-m-d");
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_tratamiento_id
        FROM hc_odontogramas_tratamientos
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        if(empty($odonto))
        {
            $query="SELECT NEXTVAL ('hc_odontogramas_tratamientos_hc_odontograma_tratamiento_id_seq');";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $odonto=$resulta->fields[0];
            $query="INSERT INTO hc_odontogramas_tratamientos
            (hc_odontograma_tratamiento_id,
            tipo_id_paciente,
            paciente_id,
            sw_activo,
            evolucion_id,
            fecha_registro)
            VALUES
            (".$odonto.",
            '".$this->tipoidpaciente."',
            '".$this->paciente."',
            '1',
            ".$this->evolucion.",
            now());";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }
        }
        for($i=0;$i<sizeof($_REQUEST['vector'.$pfj]);$i++)
        {
/*          if($_REQUEST['cantidad'.$i.$pfj]==NULL)
            {
                $_REQUEST['cantidad'.$i.$pfj]=1;
            }*/
            if(($_REQUEST['cantidad'.$i.$pfj]<>NULL AND $_REQUEST['vector'.$pfj][$i]['cantidad']==NULL AND $_REQUEST['ayudas'.$i.$pfj]<>NULL)
                OR ($_REQUEST['cantidad'.$i.$pfj]<>NULL AND $_REQUEST['vector'.$pfj][$i]['cantidad']<>NULL AND $_REQUEST['cantidad'.$i.$pfj]<>$_REQUEST['vector'.$pfj][$i]['cantidad'] AND $_REQUEST['ayudas'.$i.$pfj]<>NULL))
            {
                //$_REQUEST['ayudas'.$i.$pfj]
                $contador1++;
                $query = "SELECT NEXTVAL('hc_odontogramas_primera_vez_presupuesto_seq');";
                $resulta2=$dbconn->Execute($query);
                $query="INSERT INTO hc_odontogramas_primera_vez_presupuesto
                (hc_odontograma_primera_vez_id,
                cargo,
                cantidad,
                usuario_id,
                hc_odontograma_tratamiento_id,
                fecha_registro,
                hc_odontogramas_primera_vez_presupuesto_id)
                VALUES
                (".$_REQUEST['vector'.$pfj][$i]['hc_odontograma_primera_vez_id'].",
                '".$_REQUEST['vector'.$pfj][$i]['cargo']."',
                ".$_REQUEST['cantidad'.$i.$pfj].",
                ".UserGetUID().",
                ".$odonto.",
                now(),
                ".$resulta2->fields[0].");";//'".$fecha_registro."'
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            else if($_REQUEST['ayudas'.$i.$pfj]==NULL AND $_REQUEST['vector'.$pfj][$i]['cantidad']<>NULL
            AND $_REQUEST['vector'.$pfj][$i]['estado']==1 AND $_REQUEST['vector'.$pfj][$i]['hc_odontograma_tratamiento_id']<>NULL)
            { 
                $contador2++;
                $query="DELETE FROM hc_odontogramas_primera_vez_presupuesto
                WHERE hc_odontograma_primera_vez_id=".$_REQUEST['vector'.$pfj][$i]['hc_odontograma_primera_vez_id']."
                AND cargo='".$_REQUEST['vector'.$pfj][$i]['cargo']."'
                AND hc_odontogramas_primera_vez_presupuesto_id=".$_REQUEST['vector'.$pfj][$i]['hc_odontogramas_primera_vez_presupuesto_id'].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL ELIMINAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
        }
        $dbconn->CommitTrans();
    $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
    <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
    //<br>DATOS ACTUALIZADOS CORRECTAMENTE: ".$contador3."";
        return true;
    }

    function InsertarPresupuestos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        if($_REQUEST['validadiag'.$pfj]==0
        OR $_REQUEST['evolucprtra'.$pfj]==NULL
        OR $_REQUEST['cantida2'.$pfj]==NULL
        OR $_REQUEST['cantida2'.$pfj]==0
        OR $_REQUEST['cantidad'.$pfj]<$_REQUEST['cantida2'.$pfj]
        OR ($_REQUEST['cantida3'.$pfj]<$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]>0))
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS<br>O LA CANTIDAD ENVIADA O PENDIENTE ES MENOR A LA REALIZADA";
            return true;
        }
        if($_REQUEST['cantidad'.$pfj]==$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]==0)
        {
            $estado=$cantidadpe=0;
        }
        else if($_REQUEST['cantidad'.$pfj]>$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]==0)
        {
            $estado=1;
            $cantidadpe=$_REQUEST['cantidad'.$pfj]-$_REQUEST['cantida2'.$pfj];
        }
        else if($_REQUEST['cantida2'.$pfj]==$_REQUEST['cantida3'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]<>0)
        {
            $estado=$cantidadpe=0;
        }
        else if($_REQUEST['cantida3'.$pfj]>$_REQUEST['cantida2'.$pfj]
        AND $_REQUEST['cantida3'.$pfj]<>0)
        {
            $estado=1;
            $cantidadpe=$_REQUEST['cantida3'.$pfj]-$_REQUEST['cantida2'.$pfj];
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        //*****************************
        //SELECCIONAR EL MAXIMO SERIAL DEL ODONTOGRAMA PRIMERA VEZ PRESUPUESTO
        //PARA INSERTAR EN EL PRIMERA VEZ EVOLUCIÓN PRESUPUESTO
        $query="SELECT MAX(hc_odontogramas_primera_vez_presupuesto_id)
        FROM hc_odontogramas_primera_vez_presupuesto
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $max=$resulta->fields[0];
        //FIN SELECCIÓN
        //*****************************
        $query="UPDATE hc_odontogramas_primera_vez_presupuesto SET
        estado='".$estado."',
        cantidad_pend='".$cantidadpe."',
        evolucion='".$_REQUEST['evolucprtra'.$pfj]."',
        usuario_id=".UserGetUID()."
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo=".$_REQUEST['cargotrata'.$pfj]."
        AND hc_odontogramas_primera_vez_presupuesto_id=$max;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        
        $query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
        $results=$dbconn->Execute($query);
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error ingresos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
        }                   
        
        $query="INSERT INTO hc_os_solicitudes
        (hc_os_solicitud_id,cargo,
        evolucion_id,
        plan_id,
        os_tipo_solicitud_id,
        sw_estado,
        cantidad)
        VALUES
        (".$results->fields[0].",'".$_REQUEST['cargotrata'.$pfj]."',
        ".$this->evolucion.",
        ".$this->plan.",
        'PSC',
        '3',
        '".$_REQUEST['cantida2'.$pfj]."');";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        { 
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true; 
        }
        $query="SELECT DISTINCT cantidad_realizada
        FROM hc_odontogramas_tratamientos_evolucion_presupuesto
        WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
        AND hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND evolucion_id=".$this->evolucion."";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        while(!$resulta->EOF)
        {
            $cantidad_guardada=$cantidad_guardada+$resulta->fields[0];
            $resulta->MoveNext();
        }
        $cantidad_guardada=$cantidad_guardada+$_REQUEST['cantida2'.$pfj];
        //*****************************
        //SELECCIONAR EL MAXIMO SERIAL DEL ODONTOGRAMA_TRATAMIENTOS_EVOLUCION_PRESUPUESTOS
        //PARA INSERTAR EN EL PRIMERA VEZ EVOLUCIÓN PRESUPUESTO
        $query="SELECT MAX(hc_odontogramas_tratamientos_evolucion_presupuesto_id)
        FROM hc_odontogramas_tratamientos_evolucion_presupuesto
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."'";//AND evolucion_id=".$this->evolucion.";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $max=$resulta->fields[0];
        //FIN SELECCIÓN
        //*****************************
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_presupuesto SET
        cantidad_realizada=".$cantidad_guardada.",
        hc_os_solicitud_id=".$results->fields[0]."
        WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
        AND hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND evolucion_id=".$this->evolucion."
        AND hc_odontogramas_tratamientos_evolucion_presupuesto_id=$max;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL ACTUALIZAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
        return true;
    }

    function CancelarPresupuestos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_presupuesto SET
        estado='2'
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo=".$_REQUEST['cargotrata'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $dbconn->CommitTrans();
        $_REQUEST['estado'.$pfj]='2';
        $this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
        return true;
    }

    function ActivarPresupuestos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_presupuesto SET
        estado='1'
        WHERE hc_odontograma_primera_vez_id=".$_REQUEST['odondetadi'.$pfj]."
        AND cargo=".$_REQUEST['cargotrata'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $dbconn->CommitTrans();
        $_REQUEST['estado'.$pfj]='1';
        $this->frmError["MensajeError"]="DATOS MODIFICADOS CORRECTAMENTE";
        return true;
    }

    function BuscarTipoProblema()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT hc_tipo_problema_diente_id,
        descripcion,
        indice_orden,
        sw_diente_completo
        FROM hc_tipos_problemas_dientes
        ORDER BY indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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

    function BuscarTipoProblemaTra()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT hc_tipo_problema_diente_id,
        descripcion,
        indice_orden,
        sw_diente_completo
        FROM hc_tipos_problemas_dientes
        WHERE sw_presupuesto='1'
        ORDER BY indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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

    function BuscarTipoUbicacion()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT hc_tipo_ubicacion_diente_id,
        indice_orden
        FROM hc_tipos_ubicaciones_dientes
        ORDER BY indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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

    function BuscarTipoCuadrantes()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT hc_tipo_cuadrante_id,
        descripcion,
        indice_orden
        FROM hc_tipos_cuadrantes_dientes
        WHERE sw_mostrar='1'
        ORDER BY indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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

    function BuscarTipoProductos()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT hc_tipo_producto_diente_id,
        descripcion,
        indice_orden
        FROM hc_tipos_productos_dientes
        ORDER BY indice_orden;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
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

    function BuscarCargoDescripcion($cups)
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT descripcion
        FROM CUPS
        WHERE cargo='".$cups."';";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[0];
    }

    function BuscarDiagnosticosPresupuestoTratamGuarda()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.diagnostico_id,
        B.diagnostico_nombre,
        C.hc_odontograma_primera_vez_id,
        C.evolucion_id,
        C.sw_principal,
        C.tipo_diagnostico
        FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
        diagnosticos AS B,
        hc_odontogramas_tratamientos_evolucion_presupuesto AS C,
        hc_odontogramas_primera_vez AS D
        WHERE A.diagnostico_id=B.diagnostico_id
        AND A.diagnostico_id=C.diagnostico_id
        AND C.cargo='".$_REQUEST['cargotrata'.$pfj]."'
        AND C.hc_odontograma_primera_vez_id=D.hc_odontograma_primera_vez_id
        AND D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."'
        AND D.sw_activo='1'
        ORDER BY A.diagnostico_id;";
        $resulta = $dbconn->Execute($query);
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
        return $var;
    }

    function BuscarDiagnosticosTratamGuarda()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.diagnostico_id,
        B.diagnostico_nombre,
        C.hc_odontograma_primera_vez_detalle_id,
        C.evolucion_id,
        C.sw_principal,
        C.tipo_diagnostico
        FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
        diagnosticos AS B,
        hc_odontogramas_tratamientos_evolucion_primera_vez AS C
        WHERE A.diagnostico_id=B.diagnostico_id
        AND A.diagnostico_id=C.diagnostico_id
        AND C.hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
        AND C.evolucion_id=".$this->evolucion."
        ORDER BY A.diagnostico_id;";
        $resulta = $dbconn->Execute($query);
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
        return $var;
    }

    function BuscarDiagnosticosTratamGuarda2()/*cambia*/
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.diagnostico_id,
        B.diagnostico_nombre,
        C.hc_odontograma_tratamiento_detalle_id,
        C.evolucion_id,
        C.sw_principal,
        C.tipo_diagnostico
        FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
        diagnosticos AS B,
        hc_odontogramas_tratamientos_evolucion_tratamiento AS C
        WHERE A.diagnostico_id=B.diagnostico_id
        AND A.diagnostico_id=C.diagnostico_id
        AND C.hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."
        AND C.evolucion_id=".$this->evolucion."
        ORDER BY A.diagnostico_id;";
        $resulta = $dbconn->Execute($query);
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
        return $var;
    }

    function BuscarDiagnosticosTratamGuardaHistorial()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.diagnostico_id,
        B.diagnostico_nombre,
        C.hc_odontograma_primera_vez_detalle_id,
        C.sw_principal,
        C.tipo_diagnostico
        FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
        diagnosticos AS B,
        hc_odontogramas_tratamientos_evolucion_primera_vez AS C
        WHERE A.diagnostico_id=B.diagnostico_id
        AND A.diagnostico_id=C.diagnostico_id
        AND C.hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
        AND C.evolucion_id<>".$this->evolucion."
        ORDER BY A.diagnostico_id;";
        $resulta = $dbconn->Execute($query);
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
        return $var;
    }

    function BuscarDiagnosticosTratamGuardaHistorial2()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT DISTINCT A.diagnostico_id,
        B.diagnostico_nombre,
        C.hc_odontograma_tratamiento_detalle_id,
        C.sw_principal,
        C.tipo_diagnostico
        FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
        diagnosticos AS B,
        hc_odontogramas_tratamientos_evolucion_tratamiento AS C
        WHERE A.diagnostico_id=B.diagnostico_id
        AND A.diagnostico_id=C.diagnostico_id
        AND C.hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."
        AND C.evolucion_id<>".$this->evolucion."
        ORDER BY A.diagnostico_id;";
        $resulta = $dbconn->Execute($query);
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
        return $var;
    }

    function CambiarDiagnosticos1()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_primera_vez SET
        sw_principal='0'
        WHERE evolucion_id=".$this->evolucion."
        AND hc_odontograma_primera_vez_detalle_id='".$_REQUEST['odondetadi'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_primera_vez SET
        sw_principal='1'
        WHERE evolucion_id=".$this->evolucion."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND hc_odontograma_primera_vez_detalle_id='".$_REQUEST['odondetadi'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        return true;
    }

    function CambiarDiagnosticos2()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_tratamiento SET
        sw_principal='0'
        WHERE evolucion_id=".$this->evolucion."
        AND hc_odontograma_tratamiento_detalle_id='".$_REQUEST['odontratdi'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_tratamiento SET
        sw_principal='1'
        WHERE evolucion_id=".$this->evolucion."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND hc_odontograma_tratamiento_detalle_id='".$_REQUEST['odontratdi'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        return true;
    }

    function CambiarDiagnosticos3()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_presupuesto SET
        sw_principal='0'
        WHERE evolucion_id=".$this->evolucion."
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_presupuesto SET
        sw_principal='1'
        WHERE evolucion_id=".$this->evolucion."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        return true;
    }

    function CambiarDiagnosticos4()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_apoyod SET
        sw_principal='0'
        WHERE evolucion_id=".$this->evolucion."
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        $query="UPDATE hc_odontogramas_tratamientos_evolucion_apoyod SET
        sw_principal='1'
        WHERE evolucion_id=".$this->evolucion."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."';";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al insertar en hc_diagnosticos_ingreso";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->frmError["MensajeError"]="EL DIAGNOSTICO YA FUE ASIGNADO.";
            return false;
        }
        return true;
    }

    function BuscarDiagnosticosTratam()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $codigo=STRTOUPPER ($_REQUEST['codigo'.$pfj]);
        $diagnostico=STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
        $busqueda1='';
        $busqueda2='';
        if ($codigo!='')
        {
            $busqueda1 ="AND A.diagnostico_id LIKE '$codigo%'";
        }
        if($diagnostico!='')
        {
            $busqueda2 ="AND B.diagnostico_nombre LIKE '%$diagnostico%'";
        }
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ) AS r;";
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
            $this->conteo=$_REQUEST['conteo'.$pfj];
        }
        if(!$_REQUEST['Of'.$pfj])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'.$pfj];
            if($Of > $this->conteo)
            {
                $Of=0;
                $_REQUEST['Of'.$pfj]=0;
                $_REQUEST['paso1'.$pfj]=1;
            }
        }
        $query="SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre,
                    C.hc_odontograma_primera_vez_detalle_id,
                    C.sw_principal,
                    C.tipo_diagnostico
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A
                    LEFT JOIN hc_odontogramas_tratamientos_evolucion_primera_vez AS C ON
                    (C.hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
                    AND C.evolucion_id=".$this->evolucion."
                    AND A.diagnostico_id=C.diagnostico_id),
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ORDER BY A.diagnostico_id
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
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
        {
            $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
        }
        return $var;
    }

    function BuscarDiagnosticosTratam2()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $codigo=STRTOUPPER ($_REQUEST['codigo'.$pfj]);
        $diagnostico=STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
        $busqueda1='';
        $busqueda2='';
        if ($codigo!='')
        {
            $busqueda1 ="AND A.diagnostico_id LIKE '$codigo%'";
        }
        if($diagnostico!='')
        {
            $busqueda2 ="AND B.diagnostico_nombre LIKE '%$diagnostico%'";
        }
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ) AS r;";
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
            $this->conteo=$_REQUEST['conteo'.$pfj];
        }
        if(!$_REQUEST['Of'.$pfj])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'.$pfj];
            if($Of > $this->conteo)
            {
                $Of=0;
                $_REQUEST['Of'.$pfj]=0;
                $_REQUEST['paso1'.$pfj]=1;
            }
        }
        $query="SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre,
                    C.hc_odontograma_tratamiento_detalle_id,
                    C.sw_principal,
                    C.tipo_diagnostico
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A
                    LEFT JOIN hc_odontogramas_tratamientos_evolucion_tratamiento AS C ON
                    (C.hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."
                    AND C.evolucion_id=".$this->evolucion."
                    AND A.diagnostico_id=C.diagnostico_id),
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ORDER BY A.diagnostico_id
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
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
        {
            $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
        }
        return $var;
    }

    function BuscarDiagnosticosPresupuestoTratam()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $codigo=STRTOUPPER ($_REQUEST['codigo'.$pfj]);
        $diagnostico=STRTOUPPER($_REQUEST['diagnostico'.$pfj]);
        $busqueda1='';
        $busqueda2='';
        if ($codigo!='')
        {
            $busqueda1 ="AND A.diagnostico_id LIKE '$codigo%'";
        }
        if($diagnostico!='')
        {
            $busqueda2 ="AND B.diagnostico_nombre LIKE '%$diagnostico%'";
        }
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query ="SELECT count(*) FROM
                    (
                    SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A,
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ) AS r;";
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
            $this->conteo=$_REQUEST['conteo'.$pfj];
        }
        if(!$_REQUEST['Of'.$pfj])
        {
            $Of='0';
        }
        else
        {
            $Of=$_REQUEST['Of'.$pfj];
            if($Of > $this->conteo)
            {
                $Of=0;
                $_REQUEST['Of'.$pfj]=0;
                $_REQUEST['paso1'.$pfj]=1;
            }
        }
        $query="SELECT DISTINCT A.diagnostico_id,
                    B.diagnostico_nombre,
                    C.hc_odontograma_primera_vez_id,
                    C.sw_principal,
                    C.tipo_diagnostico
                    FROM hc_sub_diagnosticos_odontologicos_maestro AS A
                    LEFT JOIN hc_odontogramas_tratamientos_evolucion_presupuesto AS C ON
                    (C.hc_odontograma_primera_vez_id=".$odonto."
                    AND C.cargo='".$_REQUEST['cargotrata'.$pfj]."'
                    AND C.evolucion_id=".$this->evolucion."
                    AND A.diagnostico_id=C.diagnostico_id),
                    diagnosticos AS B
                    WHERE A.diagnostico_id=B.diagnostico_id
                    $busqueda1
                    $busqueda2
                    ORDER BY A.diagnostico_id
                    LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
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
        {
            $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
        }
        return $var;
    }

    function InsertDiagnosticosTratam()
    {
        $pfj=$this->frmPrefijo;
        $contador1=$contador2=0;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        if($_REQUEST['swprincipal'.$pfj]==1)
        {
            $swprincipal=0;
        }
        else
        {
            $swprincipal=1;
        }
        $vectorD=$this->BuscarDiagnosticosTratam();
        $ciclo=sizeof($vectorD);
        for($i=0;$i<$ciclo;$i++)
        {
            if($_REQUEST['ayudas'.$i.$pfj]<>NULL AND $vectorD[$i]['hc_odontograma_primera_vez_detalle_id']==NULL)
            {
                if($_REQUEST['dx'.$i.$pfj]==NULL)
                {
                    $_REQUEST['dx'.$i.$pfj]=1;
                }
                $contador1++;
                $query="INSERT INTO hc_odontogramas_tratamientos_evolucion_primera_vez
                (hc_odontograma_primera_vez_detalle_id,
                diagnostico_id,
                evolucion_id,
                usuario_id,
                fecha_registro,
                sw_principal,
                tipo_diagnostico)
                VALUES
                (".$_REQUEST['odondetadi'.$pfj].",
                '".$_REQUEST['ayudas'.$i.$pfj]."',
                ".$this->evolucion.",
                ".UserGetUID().",
                '".date("Y-m-d H:i:s")."',
                '".$swprincipal."',
                '".$_REQUEST['dx'.$i.$pfj]."');";
                $swprincipal=0;
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            else if($_REQUEST['ayudas'.$i.$pfj]==NULL AND $vectorD[$i]['hc_odontograma_primera_vez_detalle_id']<>NULL)
            {
                $contador2++;
                $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_primera_vez
                WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
                AND diagnostico_id='".$vectorD[$i]['diagnostico_id']."'
                AND evolucion_id=".$this->evolucion.";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
        <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
        return true;
    }

    function InsertDiagnosticosTratam2()
    {
        $pfj=$this->frmPrefijo;
        $contador1=$contador2=0;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        if($_REQUEST['swprincipal'.$pfj]==1)
        {
            $swprincipal=0;
        }
        else
        {
            $swprincipal=1;
        }
        $vectorD=$this->BuscarDiagnosticosTratam2();
        $ciclo=sizeof($vectorD);
        for($i=0;$i<$ciclo;$i++)
        {
            if($_REQUEST['ayudas'.$i.$pfj]<>NULL AND $vectorD[$i]['hc_odontograma_tratamiento_detalle_id']==NULL)
            {
                if($_REQUEST['dx'.$i.$pfj]==NULL)
                {
                    $_REQUEST['dx'.$i.$pfj]=1;
                }
                $contador1++;
                $query="INSERT INTO hc_odontogramas_tratamientos_evolucion_tratamiento
                (hc_odontograma_tratamiento_detalle_id,
                diagnostico_id,
                evolucion_id,
                usuario_id,
                fecha_registro,
                sw_principal,
                tipo_diagnostico)
                VALUES
                (".$_REQUEST['odontratdi'.$pfj].",
                '".$_REQUEST['ayudas'.$i.$pfj]."',
                ".$this->evolucion.",
                ".UserGetUID().",
                '".date("Y-m-d H:i:s")."',
                '".$swprincipal."',
                '".$_REQUEST['dx'.$i.$pfj]."');";
                $swprincipal=0;
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS1: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            else if($_REQUEST['ayudas'.$i.$pfj]==NULL AND $vectorD[$i]['hc_odontograma_tratamiento_detalle_id']<>NULL)
            {
                $contador2++;
                $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_tratamiento
                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."
                AND diagnostico_id='".$vectorD[$i]['diagnostico_id']."'
                AND evolucion_id=".$this->evolucion.";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS2: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
        <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
        return true;
    }

    function InsertDiagnosticosTratam3()
    { 
        $pfj=$this->frmPrefijo;
        $contador1=$contador2=0;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_primera_vez_id,
        observacion,
        evolucion_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        //*****************************
        //SELECCIONAR EL MAXIMO SERIAL DEL ODONTOGRAMA PRIMERA VEZ PRESUPUESTO
        //PARA INSERTAR EN EL PRIMERA VEZ EVOLUCIÓN PRESUPUESTO
        $query="SELECT MAX(hc_odontogramas_primera_vez_presupuesto_id)
        FROM hc_odontogramas_primera_vez_presupuesto
        WHERE hc_odontograma_primera_vez_id=".$odonto."
        AND cargo='".$_REQUEST['cargotrata'.$pfj]."';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $max=$resulta->fields[0];
        //FIN SELECCIÓN
        //*****************************
        if($_REQUEST['swprincipal'.$pfj]==1)
        {
            $swprincipal=0;
        }
        else
        {
            $swprincipal=1;
        }
        $vectorD=$this->BuscarDiagnosticosPresupuestoTratam();
        $ciclo=sizeof($vectorD);
        for($i=0;$i<$ciclo;$i++)
        {
            if($_REQUEST['ayudas'.$i.$pfj]<>NULL AND $vectorD[$i]['hc_odontograma_primera_vez_id']==NULL)
            {
                if($_REQUEST['dx'.$i.$pfj]==NULL)
                {
                    $_REQUEST['dx'.$i.$pfj]=1;
                }
                $contador1++;
                $query="INSERT INTO hc_odontogramas_tratamientos_evolucion_presupuesto
                (hc_odontograma_primera_vez_id,
                cargo,
                diagnostico_id,
                evolucion_id,
                usuario_id,
                fecha_registro,
                sw_principal,
                tipo_diagnostico,
                hc_odontogramas_tratamientos_evolucion_presupuesto_id)
                VALUES
                (".$odonto.",
                '".$_REQUEST['cargotrata'.$pfj]."',
                '".$_REQUEST['ayudas'.$i.$pfj]."',
                ".$this->evolucion.",
                ".UserGetUID().",
                '".date("Y-m-d H:i:s")."',
                '".$swprincipal."',
                '".$_REQUEST['dx'.$i.$pfj]."',
                $max);";
                $swprincipal=0;
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            else if($_REQUEST['ayudas'.$i.$pfj]==NULL AND $vectorD[$i]['hc_odontograma_primera_vez_id']<>NULL)
            {
                $contador2++;
                $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_presupuesto
                WHERE hc_odontograma_primera_vez_id=".$odonto."
                AND diagnostico_id='".$vectorD[$i]['diagnostico_id']."'
                AND evolucion_id=".$this->evolucion.";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
        <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
        return true;
    }

    function InsertDiagnosticosTratam4()
    {
        $pfj=$this->frmPrefijo;
        $contador1=$contador2=0;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_primera_vez_id,
        observacion,
        evolucion_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        if($_REQUEST['swprincipal'.$pfj]==1)
        {
            $swprincipal=0;
        }
        else
        {
            $swprincipal=1;
        }
        $vectorD=$this->BuscarDiagnosticosApoyosTratam();
        $ciclo=sizeof($vectorD);
        for($i=0;$i<$ciclo;$i++)
        {
            if($_REQUEST['ayudas'.$i.$pfj]<>NULL AND $vectorD[$i]['hc_odontograma_primera_vez_id']==NULL)
            {
                if($_REQUEST['dx'.$i.$pfj]==NULL)
                {
                    $_REQUEST['dx'.$i.$pfj]=1;
                }
                $contador1++;
                $query="INSERT INTO hc_odontogramas_tratamientos_evolucion_apoyod
                (hc_odontograma_primera_vez_id,
                cargo,
                diagnostico_id,
                evolucion_id,
                usuario_id,
                fecha_registro,
                sw_principal,
                tipo_diagnostico)
                VALUES
                (".$odonto.",
                '".$_REQUEST['cargotrata'.$pfj]."',
                '".$_REQUEST['ayudas'.$i.$pfj]."',
                ".$this->evolucion.",
                ".UserGetUID().",
                '".date("Y-m-d H:i:s")."',
                '".$swprincipal."',
                '".$_REQUEST['dx'.$i.$pfj]."');";
                $swprincipal=0;
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            else if($_REQUEST['ayudas'.$i.$pfj]==NULL AND $vectorD[$i]['hc_odontograma_primera_vez_id']<>NULL)
            {
                $contador2++;
                $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_apoyod
                WHERE hc_odontograma_primera_vez_id=".$odonto."
                AND diagnostico_id='".$vectorD[$i]['diagnostico_id']."'
                AND evolucion_id=".$this->evolucion.";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
        <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
        return true;
    }

    function EliminarDiagnosticosTratam()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_primera_vez
        WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND evolucion_id=".$this->evolucion.";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        return true;
    }

    function EliminarDiagnosticosTratam2()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_tratamiento
        WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND evolucion_id=".$this->evolucion.";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        return true;
    }

    function EliminarDiagnosticosTratam3()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_presupuesto
        WHERE hc_odontograma_primera_vez_id=".$odonto."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND evolucion_id=".$this->evolucion.";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        return true;
    }

    function EliminarDiagnosticosTratam4()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_apoyod
        WHERE hc_odontograma_primera_vez_id=".$odonto."
        AND diagnostico_id='".$_REQUEST['diagnostitra'.$pfj]."'
        AND evolucion_id=".$this->evolucion.";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        return true;
    }

    function BuscarOdontogramaForma()//busca el odontograma inicial
    {
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id,
        observacion,
        evolucion_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];//.','.$resulta->fields[2];
        if(!empty($odonto))
        {
            $query="SELECT A.hc_odontograma_primera_vez_detalle_id,
            A.hc_tipo_ubicacion_diente_id,
            A.estado,
            A.fecha_registro,
            B.descripcion AS des1,
            C.descripcion AS des2,
            D.descripcion AS des3,
            C.sw_cariado,
            C.sw_obturado,
            C.sw_perdidos,
            C.sw_sanos,
            C.sw_presupuesto,
            E.hc_tipo_problema_diente_id,
            F.cargo,
            G.sw_cariado AS sw_cariado2,
            G.sw_obturado AS sw_obturado2,
            G.sw_perdidos AS sw_perdidos2,
            G.sw_sanos AS sw_sanos2,
            H.hc_odontograma_tratamiento_detalle_id,
            H.evolucion_id,
            H.evolucion,
            H.estado AS estadotrat
            FROM hc_odontogramas_primera_vez_detalle AS A
            LEFT JOIN hc_tipos_problemas_soluciones_dientes AS E ON
            (A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
            AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id)
            LEFT JOIN hc_tipos_problemas_soluciones_dientes AS F ON
            (A.hc_tipo_problema_diente_id=F.hc_tipo_problema_diente_id
            AND A.hc_tipo_producto_diente_id=F.hc_tipo_producto_diente_id)
            LEFT JOIN hc_tipos_problemas_dientes AS G ON
            (E.hc_tipo_probsolu_diente_id=G.hc_tipo_problema_diente_id)
            LEFT JOIN hc_odontogramas_tratamientos_detalle AS H ON
            (A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_primera_vez_detalle_id),
            hc_tipos_cuadrantes_dientes AS B,
            hc_tipos_problemas_dientes AS C,
            hc_tipos_productos_dientes AS D
            WHERE A.hc_odontograma_primera_vez_id=".$odonto."
            AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
            AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
            AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
            ORDER BY A.hc_tipo_ubicacion_diente_id ASC, C.sw_cariado DESC,
            C.sw_obturado DESC, C.sw_perdidos DESC, C.sw_sanos DESC;";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
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
        }
        return $var;
    }

    function BuscarApoyosOdontograma()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_primera_vez_id,
        B.cargo,
        B.descripcion_ubicacion,
        B.cantidad,
        B.cantidad_pend,
        B.estado,
        B.evolucion,
        B.fecha_registro,
        C.descripcion
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_apoyod AS B,
        cups AS c
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.cargo=C.cargo
        ORDER BY B.cargo;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function BuscarApoyosOdontogramaConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_primera_vez_id,
        B.cargo,
        B.descripcion_ubicacion,
        B.cantidad,
        B.cantidad_pend,
        B.estado,
        B.evolucion,
        B.fecha_registro,
        C.descripcion
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_apoyod AS B,
        cups AS c
        WHERE A.hc_odontograma_primera_vez_id=
        (SELECT MAX(D.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."')
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.cargo=C.cargo;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function BuscarPresupuestosOdontograma()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_primera_vez_id,
        B.cargo,
        B.cantidad,
        B.cantidad_pend,
        B.estado,
        B.evolucion,
        B.evolucion,
        B.fecha_registro,
        C.descripcion
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_presupuesto AS B,
        cups AS c
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.cargo=C.cargo
        ORDER BY B.cargo;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function BuscarPresupuestosOdontogramaConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_primera_vez_id,
        B.cargo,
        B.cantidad,
        B.estado,
        B.fecha_registro,
        C.descripcion
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_presupuesto AS B,
        cups AS c
        WHERE A.hc_odontograma_primera_vez_id=
        (SELECT MAX(D.hc_odontograma_primera_vez_id)
        FROM hc_odontogramas_primera_vez AS D
        WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
        AND D.paciente_id='".$this->paciente."')
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.cargo=C.cargo;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function BuscarOdontogramaFormaConsulta()//busca el odontograma inicial
    {
        list($dbconn) = GetDBconn();
        $query="SELECT hc_odontograma_primera_vez_id,
        observacion,
        evolucion_id
        FROM hc_odontogramas_primera_vez
        WHERE hc_odontograma_primera_vez_id=
        (SELECT hc_odontograma_primera_vez_id
        FROM hc_odontogramas_primera_vez
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo = '1'
        );";
        //AND evolucion_id=".$this->evolucion."
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];//.','.$resulta->fields[2];
        if(!empty($odonto))
        {
            $query="SELECT A.hc_odontograma_primera_vez_detalle_id,
            A.hc_tipo_ubicacion_diente_id,
            A.estado,
            A.fecha_registro,
            B.descripcion AS des1,
            C.descripcion AS des2,
            D.descripcion AS des3,
            C.sw_cariado,
            C.sw_obturado,
            C.sw_perdidos,
            C.sw_sanos,
            C.sw_presupuesto,
            E.hc_tipo_problema_diente_id,
            F.cargo,
            G.sw_cariado AS sw_cariado2,
            G.sw_obturado AS sw_obturado2,
            G.sw_perdidos AS sw_perdidos2,
            G.sw_sanos AS sw_sanos2,
            H.hc_odontograma_tratamiento_detalle_id,
            H.evolucion_id,
            H.evolucion,
            H.estado AS estadotrat
            FROM hc_odontogramas_primera_vez_detalle AS A
            LEFT JOIN hc_tipos_problemas_soluciones_dientes AS E ON
            (A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
            AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id)
            LEFT JOIN hc_tipos_problemas_soluciones_dientes AS F ON
            (A.hc_tipo_problema_diente_id=F.hc_tipo_problema_diente_id
            AND A.hc_tipo_producto_diente_id=F.hc_tipo_producto_diente_id)
            LEFT JOIN hc_tipos_problemas_dientes AS G ON
            (E.hc_tipo_probsolu_diente_id=G.hc_tipo_problema_diente_id)
            LEFT JOIN hc_odontogramas_tratamientos_detalle AS H ON
            (A.hc_odontograma_primera_vez_detalle_id=H.hc_odontograma_primera_vez_detalle_id),
            hc_tipos_cuadrantes_dientes AS B,
            hc_tipos_problemas_dientes AS C,
            hc_tipos_productos_dientes AS D
            WHERE A.hc_odontograma_primera_vez_id=".$odonto."
            AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
            AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
            AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
            --AND A.sw_control<>1
            ORDER BY A.hc_tipo_ubicacion_diente_id ASC, C.sw_cariado DESC,
            C.sw_obturado DESC, C.sw_perdidos DESC, C.sw_sanos DESC;";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
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
        }
        return $var;
    }

    function BuscarIndicesTratamiento($indicetrat)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT C.sw_cariado,
        C.sw_obturado,
        C.sw_perdidos,
        C.sw_sanos
        FROM hc_odontogramas_tratamientos_detalle AS A,
        hc_tipos_problemas_dientes AS C,
        hc_tipos_problemas_soluciones_dientes AS D
        WHERE A.hc_odontograma_tratamiento_detalle_id=".$indicetrat."
        AND A.hc_tipo_problema_diente_id=D.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
        AND D.hc_tipo_probsolu_diente_id=C.hc_tipo_problema_diente_id
        ORDER BY C.sw_cariado DESC, C.sw_obturado DESC,
        C.sw_perdidos DESC, C.sw_sanos DESC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarCambiosTratamiento($tratadetalle)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.descripcion AS des1,
        C.descripcion AS des2,
        D.descripcion AS des3
        FROM hc_odontogramas_tratamientos_detalle AS A,
        hc_tipos_cuadrantes_dientes AS B,
        hc_tipos_problemas_dientes AS C,
        hc_tipos_productos_dientes AS D
        WHERE A.hc_odontograma_tratamiento_detalle_id=".$tratadetalle."
        AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarCambiosTratamientoEvolucion($tratadetalle)
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_tratamiento_detalle_id,
        A.hc_tipo_ubicacion_diente_id,
        A.evolucion_id,
        A.evolucion,
        A.estado,
        B.descripcion AS des1,
        C.descripcion AS des2,
        D.descripcion AS des3,
        E.cargo,
        F.descripcion
        FROM hc_odontogramas_tratamientos_detalle AS A,
        hc_tipos_cuadrantes_dientes AS B,
        hc_tipos_problemas_dientes AS C,
        hc_tipos_productos_dientes AS D,
        hc_tipos_problemas_soluciones_dientes AS E,
        cups AS F
        WHERE A.hc_odontograma_tratamiento_detalle_id=".$tratadetalle."
        AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
        AND A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id
        AND E.cargo=F.cargo;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarOdontogramaDiente()//busca el odontograma inicial
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_primera_vez_detalle_id,
        A.hc_tipo_ubicacion_diente_id,
        A.estado,
        B.descripcion AS des1,
        C.descripcion AS des2,
        D.descripcion AS des3,
        F.cargo
        FROM hc_odontogramas_primera_vez_detalle AS A
        LEFT JOIN hc_tipos_problemas_soluciones_dientes AS F ON
        (A.hc_tipo_problema_diente_id=F.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=F.hc_tipo_producto_diente_id),
        hc_tipos_cuadrantes_dientes AS B,
        hc_tipos_problemas_dientes AS C,
        hc_tipos_productos_dientes AS D,
        hc_odontogramas_primera_vez AS I
        WHERE A.hc_odontograma_primera_vez_id=I.hc_odontograma_primera_vez_id
        AND I.paciente_id='".$this->paciente."'
        AND I.tipo_id_paciente='".$this->tipoidpaciente."'
        AND I.sw_activo='1'
        AND A.hc_odontograma_primera_vez_detalle_id<>".$_REQUEST['odondetadi'.$this->frmPrefijo]."
        AND A.hc_tipo_ubicacion_diente_id=".$_REQUEST['ubicacion'.$this->frmPrefijo]."
        AND A.estado='1'
        AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
        ORDER BY A.hc_tipo_ubicacion_diente_id ASC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function BuscarOdontogramaDienteTra()//busca el odontograma inicial
    {
        list($dbconn) = GetDBconn();
        $query="SELECT A.hc_odontograma_tratamiento_detalle_id,
        A.hc_odontograma_tratamiento_id,
        A.hc_tipo_ubicacion_diente_id,
        A.estado,
        B.descripcion AS des1,
        C.descripcion AS des2,
        D.descripcion AS des3,
        F.cargo
        FROM hc_odontogramas_tratamientos_detalle AS A
        LEFT JOIN hc_tipos_problemas_soluciones_dientes AS F ON
        (A.hc_tipo_problema_diente_id=F.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=F.hc_tipo_producto_diente_id),
        hc_tipos_cuadrantes_dientes AS B,
        hc_tipos_problemas_dientes AS C,
        hc_tipos_productos_dientes AS D,
        hc_odontogramas_tratamientos AS I
        WHERE A.hc_odontograma_tratamiento_id=I.hc_odontograma_tratamiento_id
        AND I.tipo_id_paciente='".$this->tipoidpaciente."'
        AND I.paciente_id='".$this->paciente."'
        AND I.sw_activo='1'
        AND A.hc_odontograma_tratamiento_detalle_id<>".$_REQUEST['odontratdi'.$this->frmPrefijo]."
        AND A.hc_tipo_ubicacion_diente_id=".$_REQUEST['ubicacion'.$this->frmPrefijo]."
        AND A.estado='1'
        AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
        ORDER BY A.hc_tipo_ubicacion_diente_id ASC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
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
        return $var;
    }

    function CorrregirDientes()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        $fecha_registro=date("Y-m-d");
        $sw=0;
        if($_REQUEST['validadiag'.$pfj]==0 OR $_REQUEST['evoluciontra'.$pfj]==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            return true;
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_tratamiento_id
        FROM hc_odontogramas_tratamientos
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        if(empty($odonto))
        {
            $query="SELECT NEXTVAL ('hc_odontogramas_tratamientos_hc_odontograma_tratamiento_id_seq');";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $odonto=$resulta->fields[0];
            $query="INSERT INTO hc_odontogramas_tratamientos
            (hc_odontograma_tratamiento_id,
            tipo_id_paciente,
            paciente_id,
            sw_activo,
            evolucion_id,
            fecha_registro)
            VALUES
            (".$odonto.",
            '".$this->tipoidpaciente."',
            '".$this->paciente."',
            '1',
            ".$this->evolucion.",
            now());";//'".$fecha_registro."'
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }
        }
        if($_REQUEST['estado'.$pfj]==1)
        {
            $query="INSERT INTO hc_odontogramas_tratamientos_detalle
            (hc_odontograma_tratamiento_id,
            hc_odontograma_primera_vez_detalle_id,
            evolucion_id,
            evolucion,
            fecha_registro)
            VALUES
            (".$odonto.",
            ".$_REQUEST['odondetadi'.$pfj].",
            ".$this->evolucion.",
            '".$_REQUEST['evoluciontra'.$pfj]."',
            now());";//'".$fecha_registro."'
        }
        else if(($_REQUEST['estado'.$pfj]==0
        OR $_REQUEST['estado'.$pfj]==4)
        AND ($_REQUEST['idevolucitra'.$pfj]==$this->evolucion
        OR $_REQUEST['idevolucitra'.$pfj]==NULL))
        {
            $query="UPDATE hc_odontogramas_tratamientos_detalle SET
            evolucion='".$_REQUEST['evoluciontra'.$pfj]."'
            WHERE hc_odontograma_tratamiento_id=".$odonto."
            AND hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
            AND evolucion_id=".$this->evolucion.";";
            $this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
        }
        else if(($_REQUEST['estado'.$pfj]==0
        OR $_REQUEST['estado'.$pfj]==4)
        AND ($_REQUEST['idevolucitra'.$pfj]<>$this->evolucion
        AND $_REQUEST['idevolucitra'.$pfj]<>NULL))
        {
            $query="UPDATE hc_odontogramas_tratamientos_detalle SET
            evolucion='".$_REQUEST['evoluciontra'.$pfj]."'
            WHERE hc_odontograma_tratamiento_id=".$odonto."
            AND hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
            AND evolucion_id=".$_REQUEST['idevolucitra'.$pfj].";";
            $this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
        }
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }
        if($_REQUEST['continua'.$pfj]==NULL AND $_REQUEST['estado'.$pfj]==1)
        {
            $query="UPDATE hc_odontogramas_primera_vez_detalle SET
            estado='0'
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO TOTALMENTE";
            $sw=1;
        }
        else if($_REQUEST['continua'.$pfj]<>NULL AND $_REQUEST['estado'.$pfj]==1)
        {
            $query="UPDATE hc_odontogramas_primera_vez_detalle SET
            estado='4'
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO PARCIALMENTE";
        }
        else if($_REQUEST['continua'.$pfj]==NULL AND $_REQUEST['estado'.$pfj]==4)
        {
            $query="UPDATE hc_odontogramas_primera_vez_detalle SET
            estado='0'
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO TOTALMENTE";
            $sw=1;
        }
        else if($_REQUEST['continua'.$pfj]<>NULL AND $_REQUEST['estado'.$pfj]==0)
        {
            $query="UPDATE hc_odontogramas_primera_vez_detalle SET
            estado='4'
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO PARCIALMENTE";
            $sw=2;
        }
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }
        /*$query="SELECT cantidad FROM hc_os_solicitudes
        WHERE cargo=".$_REQUEST['cargotrata'.$pfj]."
        AND evolucion_id=".$this->evolucion."
        AND plan_id=".$this->plan."
        AND os_tipo_solicitud_id='PSC'
        AND sw_estado='3';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        if($resulta->fields[0]==NULL AND $sw==1)*/
        if($sw==1)
        {
            $query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
            $results=$dbconn->Execute($query);
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error ingresos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
            }           
            
            $query="INSERT INTO hc_os_solicitudes
            (hc_os_solicitud_id,cargo,
            evolucion_id,
            plan_id,
            os_tipo_solicitud_id,
            sw_estado,
            cantidad)
            VALUES
            (".$results->fields[0].",
            '".$_REQUEST['cargotrata'.$pfj]."',
            ".$this->evolucion.",
            ".$this->plan.",
            'PSC',
            '3',
            '1');";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR INSERT INTO hc_os_solicitudes: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }   
                
            $queryU="UPDATE hc_odontogramas_primera_vez_detalle SET
            hc_os_solicitud_id=".$results->fields[0]."
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            $resulta = $dbconn->Execute($queryU);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL ACTUALIZAR EL NUMERO DE SOLICTUD: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }               
        }
        else if($sw==2)
        {
            /*$query="DELETE FROM hc_os_solicitudes
            WHERE cargo=".$_REQUEST['cargotrata'.$pfj]."
            AND evolucion_id=".$this->evolucion."
            AND os_tipo_solicitud_id='PSC'
            AND sw_estado='3';";*/
            
            $queryU="UPDATE hc_odontogramas_primera_vez_detalle SET
            hc_os_solicitud_id=NULL
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            $resulta = $dbconn->Execute($queryU);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="1ERROR AL ACTUALIZAR EL NUMERO DE SOLICTUD: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }
        }
        /*$resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }   */
    
        $dientes=$this->BuscarOdontogramaDiente();
        for($i=0;$i<sizeof($dientes);$i++)
        {
            if($_REQUEST['copiar'.$i.$pfj]<>NULL)
            {
                if($dientes[$i]['estado']==1)
                {                           
                    $query="SELECT nextval('hc_odontogramas_tratamientos__hc_odontograma_tratamiento_de_seq')";
                    $resultado=$dbconn->Execute($query);
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error ingresos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                    }                       
                    $query="INSERT INTO hc_odontogramas_tratamientos_detalle
                    (hc_odontograma_tratamiento_detalle_id,
                    hc_odontograma_tratamiento_id,
                    hc_odontograma_primera_vez_detalle_id,
                    evolucion_id,
                    evolucion,
                    fecha_registro)
                    VALUES
                    (".$resultado->fields[0].",".$odonto.",
                    ".$dientes[$i]['hc_odontograma_primera_vez_detalle_id'].",
                    ".$this->evolucion.",
                    '".$_REQUEST['evoluciontra'.$pfj]."',
                    now());";//'".$fecha_registro."'
                }
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }
                if($_REQUEST['continua'.$pfj]==NULL AND $dientes[$i]['estado']==1)
                {
                    $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                    estado='0'
                    WHERE hc_odontograma_primera_vez_detalle_id=".$dientes[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                    $this->frmError["MensajeError"]="PROBLEMA CORREGIDO TOTALMENTE";
                    $sw=1;
                }
                else if($_REQUEST['continua'.$pfj]<>NULL AND $dientes[$i]['estado']==1)
                {
                    $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                    estado='4'
                    WHERE hc_odontograma_primera_vez_detalle_id=".$dientes[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                    $this->frmError["MensajeError"]="PROBLEMA CORREGIDO PARCIALMENTE";
                }
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }
                
                            
            //  if($resulta->fields[0]==NULL AND $sw==1)
                if($sw==1)
                {               
                    $query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
                    $results=$dbconn->Execute($query);
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error ingresos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                    }   
                                                
                    $query="INSERT INTO hc_os_solicitudes
                    (hc_os_solicitud_id,cargo,
                    evolucion_id,
                    plan_id,
                    os_tipo_solicitud_id,
                    sw_estado,
                    cantidad)
                    VALUES
                    (".$results->fields[0].",'".$dientes[$i]['cargo']."',
                    ".$this->evolucion.",
                    ".$this->plan.",
                    'PSC',
                    '3',
                    '1');";
            /*  }
                else if($resulta->fields[0]<>NULL AND $sw==1)
                {
                    $query="UPDATE hc_os_solicitudes SET
                    cantidad=".($resulta->fields[0]+(1))."
                    WHERE cargo='".$dientes[$i]['cargo']."'
                    AND evolucion_id=".$this->evolucion."
                    AND plan_id=".$this->plan."
                    AND os_tipo_solicitud_id='PSC'
                    AND sw_estado='3';";
                }*/
                    $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                        $dbconn->RollbackTrans();
                        return true;
                    }
                    
                    $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                    hc_os_solicitud_id=".$results->fields[0]."
                    WHERE hc_odontograma_primera_vez_detalle_id=".$dientes[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                    $dbconn->Execute($query);                   
                    if($dbconn->ErrorNo() != 0)
                    {
                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                        $dbconn->RollbackTrans();
                        return true;
                    }                   
                }   
                
                $query="INSERT INTO hc_odontogramas_tratamientos_evolucion_primera_vez
                (hc_odontograma_primera_vez_detalle_id,
                diagnostico_id,
                evolucion_id,
                usuario_id,
                fecha_registro,
                sw_principal,
                tipo_diagnostico)
                SELECT
                ".$dientes[$i]['hc_odontograma_primera_vez_detalle_id'].",
                diagnostico_id,
                ".$this->evolucion.",
                ".UserGetUID().",
                '".date("Y-m-d H:i:s")."',
                sw_principal,
                tipo_diagnostico
                FROM hc_odontogramas_tratamientos_evolucion_primera_vez
                WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
        }
        $dbconn->CommitTrans();
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }

    function CorrregirDientes2()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        $sw=0;
        if($_REQUEST['validaditr'.$pfj]==0 OR $_REQUEST['evoluciontr2'.$pfj]==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            return true;
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_tratamientos_detalle SET
        evolucion='".$_REQUEST['evoluciontr2'.$pfj]."'
        WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
        $this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }
        if($_REQUEST['continua'.$pfj]==NULL AND $_REQUEST['estado'.$pfj]==1)
        {
            $query="UPDATE hc_odontogramas_tratamientos_detalle SET
            estado='0'
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO TOTALMENTE";
            $sw=1;
        }
        else if($_REQUEST['continua'.$pfj]<>NULL AND $_REQUEST['estado'.$pfj]==1)
        {
            $query="UPDATE hc_odontogramas_tratamientos_detalle SET
            estado='4'
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO PARCIALMENTE";
        }
        else if($_REQUEST['continua'.$pfj]==NULL AND $_REQUEST['estado'.$pfj]==4)
        {
            $query="UPDATE hc_odontogramas_tratamientos_detalle SET
            estado='0'
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO TOTALMENTE";
            $sw=1;
        }
        else if($_REQUEST['continua'.$pfj]<>NULL AND $_REQUEST['estado'.$pfj]==0)
        {
            $query="UPDATE hc_odontogramas_tratamientos_detalle SET
            estado='4'
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            $this->frmError["MensajeError"]="PROBLEMA CORREGIDO PARCIALMENTE";
            $sw=2;
        }
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }
        
        
        $query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
        $results=$dbconn->Execute($query);
        $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error ingresos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
        }       
        /*$query="SELECT cantidad FROM hc_os_solicitudes
        WHERE cargo=".$_REQUEST['cargotrata'.$pfj]."
        AND evolucion_id=".$this->evolucion."
        AND plan_id=".$this->plan."
        AND os_tipo_solicitud_id='PSC'
        AND sw_estado='3';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }*/
        //if($resulta->fields[0]==NULL AND $sw==1)      
        if($sw==1)
        {
                $query="INSERT INTO hc_os_solicitudes
                (hc_os_solicitud_id,cargo,
                evolucion_id,
                plan_id,
                os_tipo_solicitud_id,
                sw_estado,
                cantidad)
                VALUES
                (".$results->fields[0].",'".$_REQUEST['cargotrata'.$pfj]."',
                ".$this->evolucion.",
                ".$this->plan.",
                'PSC',
                '3',
                '1');";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS1: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }               
            
                $queryU="UPDATE hc_odontogramas_tratamientos_detalle SET
                hc_os_solicitud_id=".$results->fields[0]."
                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
                $this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
                $resulta = $dbconn->Execute($queryU);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS2: ".$dbconn->ErrorMsg()."";
                    $this->fileError = __FILE__;
                    $this->lineError = __LINE__;
                    $dbconn->RollbackTrans();
                    return true;
                }           
        }
    /*  else if($resulta->fields[0]<>NULL AND $sw==1)
        {
            $query="UPDATE hc_os_solicitudes SET
            cantidad=".($resulta->fields[0]+(1))."
            WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
            AND evolucion_id=".$this->evolucion."
            AND plan_id=".$this->plan."
            AND os_tipo_solicitud_id='PSC'
            AND sw_estado='3';";
        }
        else if($resulta->fields[0]==1 AND $sw==2)*/
        else if($sw==2)
        {
            $queryU="UPDATE hc_odontogramas_tratamientos_detalle SET
            hc_os_solicitud_id=NULL
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            $this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS3: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return true;
            }
                        
            $query="DELETE FROM hc_os_solicitudes
            WHERE hc_os_solicitud_id=(SELECT hc_os_solicitud_id FROM hc_odontogramas_tratamientos_detalle 
                                                                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].");";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS4: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;                
                $dbconn->RollbackTrans();
                return true;
            }           
        }
        /*else if($resulta->fields[0]>1 AND $sw==2)
        {
            $query="UPDATE hc_os_solicitudes SET
            cantidad=".($resulta->fields[0]-(1))."
            WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
            AND evolucion_id=".$this->evolucion."
            AND plan_id=".$this->plan."
            AND os_tipo_solicitud_id='PSC'
            AND sw_estado='3';";
        }*/     
    
        $dientes=$this->BuscarOdontogramaDienteTra();
        for($i=0;$i<sizeof($dientes);$i++)
        {
            if($_REQUEST['copiar'.$i.$pfj]<>NULL)
            {
                if($dientes[$i]['estado']==1)
                {
                    $query="UPDATE hc_odontogramas_tratamientos_detalle SET
                    evolucion='".$_REQUEST['evoluciontr2'.$pfj]."'
                    WHERE hc_odontograma_tratamiento_detalle_id=".$dientes[$i]['hc_odontograma_tratamiento_detalle_id'].";";
                    $this->frmError["MensajeError"]="DATOS ACTUALIZADOS";
                }
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }
                if($_REQUEST['continua'.$pfj]==NULL AND $dientes[$i]['estado']==1)
                {
                    $query="UPDATE hc_odontogramas_tratamientos_detalle SET
                    estado='0'
                    WHERE hc_odontograma_tratamiento_detalle_id=".$dientes[$i]['hc_odontograma_tratamiento_detalle_id'].";";
                    $this->frmError["MensajeError"]="PROBLEMA CORREGIDO TOTALMENTE";
                    $sw=1;
                }
                else if($_REQUEST['continua'.$pfj]<>NULL AND $dientes[$i]['estado']==1)
                {
                    $query="UPDATE hc_odontogramas_tratamientos_detalle SET
                    estado='4'
                    WHERE hc_odontograma_tratamiento_detalle_id=".$dientes[$i]['hc_odontograma_tratamiento_detalle_id'].";";
                    $this->frmError["MensajeError"]="PROBLEMA CORREGIDO PARCIALMENTE";
                }
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }
                
                $query="SELECT nextval('hc_os_solicitudes_hc_os_solicitud_id_seq')";
                $results=$dbconn->Execute($query);
                $dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0) {
                                $this->error = "Error ingresos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                }                                   
                /*$query="SELECT cantidad FROM hc_os_solicitudes
                WHERE cargo=".$dientes[$i]['cargo']."
                AND evolucion_id=".$this->evolucion."
                AND plan_id=".$this->plan."
                AND os_tipo_solicitud_id='PSC'
                AND sw_estado='3';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
                if($resulta->fields[0]==NULL AND $sw==1)    
                {*/
                    $query="INSERT INTO hc_os_solicitudes
                    (hc_os_solicitud_id,cargo,
                    evolucion_id,
                    plan_id,
                    os_tipo_solicitud_id,
                    sw_estado,
                    cantidad)
                    VALUES
                    (".$results->fields[0].",'".$dientes[$i]['cargo']."',
                    ".$this->evolucion.",
                    ".$this->plan.",
                    'PSC',
                    '3',
                    '1');";
                /*}
                else if($resulta->fields[0]<>NULL AND $sw==1)
                {
                    $query="UPDATE hc_os_solicitudes SET
                    cantidad=".($resulta->fields[0]+(1))."
                    WHERE cargo='".$dientes[$i]['cargo']."'
                    AND evolucion_id=".$this->evolucion."
                    AND plan_id=".$this->plan."
                    AND os_tipo_solicitud_id='PSC'
                    AND sw_estado='3';";
                }*/
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }
                $query="INSERT INTO hc_odontogramas_tratamientos_evolucion_tratamiento
                (hc_odontograma_tratamiento_detalle_id,
                diagnostico_id,
                evolucion_id,
                usuario_id,
                fecha_registro,
                sw_principal,
                tipo_diagnostico)
                SELECT
                ".$dientes[$i]['hc_odontograma_tratamiento_detalle_id'].",
                diagnostico_id,
                ".$this->evolucion.",
                ".UserGetUID().",
                '".date("Y-m-d H:i:s")."',
                sw_principal,
                tipo_diagnostico
                FROM hc_odontogramas_tratamientos_evolucion_tratamiento
                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
                
                $query="UPDATE hc_odontogramas_tratamientos_detalle SET
                hc_os_solicitud_id=".$results->fields[0]."
                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
                $dbconn->Execute($query);                   
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }                   
            }
        }
        $dbconn->CommitTrans();
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }

    function BorrarDientes()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        if($_REQUEST['estado'.$pfj]==0 OR $_REQUEST['estado'.$pfj]==4)
        {
            $query="SELECT hc_odontograma_tratamiento_id
            FROM hc_odontogramas_tratamientos
            WHERE tipo_id_paciente='".$this->tipoidpaciente."'
            AND paciente_id='".$this->paciente."'
            AND sw_activo='1';";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                return false;
            }
            $odonto=$resulta->fields[0];
                
            $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_primera_vez
            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
            AND evolucion_id=".$this->evolucion.";";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR DELETE FROM hc_odontogramas_tratamientos_evolucion_primera_vez: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return true;
            }
            
            $sql= " SELECT hc_os_solicitud_id FROM hc_odontogramas_primera_vez_detalle 
                            WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."";
            $results = $dbconn->Execute($sql);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR DELETE FROM hc_odontogramas_tratamientos_detalle: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return true;
            }                   
            $solicitud=$results->fields[0];
                
            $query="DELETE FROM hc_odontogramas_tratamientos_detalle
            WHERE hc_odontograma_tratamiento_id=".$odonto."
            AND hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
            AND evolucion_id=".$this->evolucion.";";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR DELETE FROM hc_odontogramas_tratamientos_detalle: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return true;
            }
            if($_REQUEST['idevolucitra'.$pfj]<>NULL
            AND $_REQUEST['idevolucitra'.$pfj]<>$this->evolucion)
            {
                $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                estado='4', hc_os_solicitud_id=NULL
                WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            }
            else if($_REQUEST['idevolucitra'.$pfj]<>NULL
            AND $_REQUEST['idevolucitra'.$pfj]==$this->evolucion)
            {
                $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                estado='1', hc_os_solicitud_id=NULL
                WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
            }
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR UPDATE hc_odontogramas_primera_vez_detalle: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return true;
            }
            /*$query="SELECT cantidad FROM hc_os_solicitudes
            WHERE cargo=".$_REQUEST['cargotrata'.$pfj]."
            AND evolucion_id=".$this->evolucion."
            AND plan_id=".$this->plan."
            AND os_tipo_solicitud_id='PSC'
            AND sw_estado='3';";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return false;
            }
            if($resulta->fields[0]==1)
            {*/
                $query="DELETE FROM hc_os_solicitudes
                WHERE hc_os_solicitud_id=$solicitud
                AND os_tipo_solicitud_id='PSC'
                AND sw_estado='3';";
            /*}
            else if($resulta->fields[0]>1)
            {
                $query="UPDATE hc_os_solicitudes SET
                cantidad=".($resulta->fields[0]-(1))."
                WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
                AND evolucion_id=".$this->evolucion."
                AND plan_id=".$this->plan."
                AND os_tipo_solicitud_id='PSC'
                AND sw_estado='3';";
            }*/
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="1ERROR AL BORRA O ACTUALIZAR SOLICITUD: ".$dbconn->ErrorMsg()."";
                $this->fileError = __FILE__;
                $this->lineError = __LINE__;
                $dbconn->RollbackTrans();
                return true;
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }

    function BorrarDientesTra()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        if($_REQUEST['estado'.$pfj]==0 OR $_REQUEST['estado'.$pfj]==4)
        {
            $query="DELETE FROM hc_odontogramas_tratamientos_evolucion_tratamiento
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."
            AND evolucion_id=".$this->evolucion.";";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }
            if($_REQUEST['idevolucitr2'.$pfj]<>NULL
            AND $_REQUEST['idevolucitr2'.$pfj]<>$this->evolucion)
            {
                $query="UPDATE hc_odontogramas_tratamientos_detalle SET
                estado='4'
                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            }
            else if($_REQUEST['idevolucitr2'.$pfj]<>NULL
            AND $_REQUEST['idevolucitr2'.$pfj]==$this->evolucion)
            {
                $query="UPDATE hc_odontogramas_tratamientos_detalle SET
                estado='1',
                evolucion=".'NULL'."
                WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj].";";
            }
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }
            
            
            $query="SELECT hc_os_solicitud_id FROM hc_odontogramas_tratamientos_detalle
            WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odontratdi'.$pfj]."";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            /*          
            $query="SELECT cantidad FROM hc_os_solicitudes
            WHERE cargo=".$_REQUEST['cargotrata'.$pfj]."
            AND evolucion_id=".$this->evolucion."
            AND plan_id=".$this->plan."
            AND os_tipo_solicitud_id='PSC'
            AND sw_estado='3';";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            if($resulta->fields[0]==1)
            {*/
                $query="DELETE FROM hc_os_solicitudes
                WHERE hc_os_solicitud_id=".$resulta->fields[0]."
                AND os_tipo_solicitud_id='PSC'
                AND sw_estado='3';";
        /*  }
            else if($resulta->fields[0]>1)
            {
                $query="UPDATE hc_os_solicitudes SET
                cantidad=".($resulta->fields[0]-(1))."
                WHERE cargo='".$_REQUEST['cargotrata'.$pfj]."'
                AND evolucion_id=".$this->evolucion."
                AND plan_id=".$this->plan."
                AND os_tipo_solicitud_id='PSC'
                AND sw_estado='3';";
            }*/
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                $dbconn->RollbackTrans();
                return true;
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        $this->RegistrarSubmodulo($this->GetVersion());
        return true;
    }

    function BorrarTratamiento()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_tratamiento_id
        FROM hc_odontogramas_tratamientos
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        $query="SELECT B.estado,
        B.hc_odontograma_primera_vez_detalle_id
        FROM hc_odontogramas_primera_vez AS A,
        hc_odontogramas_primera_vez_detalle AS B
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
        AND B.hc_tipo_ubicacion_diente_id='".$_REQUEST['ubicacion'.$pfj]."';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            return true;
        }
        while(!$resulta->EOF)
        {
            $var[]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        for($i=0;$i<sizeof($var);$i++)
        {
            $prueba=$var[$i]['estado']-5;
            $query="UPDATE hc_odontogramas_primera_vez_detalle SET
            estado='".$prueba."'
            WHERE hc_odontograma_primera_vez_detalle_id=".$var[$i]['hc_odontograma_primera_vez_detalle_id'].";";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $dbconn->RollbackTrans();
                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                return true;
            }
        }
        $query="DELETE FROM hc_odontogramas_tratamientos_detalle
        WHERE hc_odontograma_tratamiento_id=".$odonto."
        AND hc_tipo_ubicacion_diente_id='".$_REQUEST['ubicacion'.$pfj]."'
        AND estado IS NOT NULL;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $dbconn->RollbackTrans();
            $this->frmError["MensajeError"]="EXISTE OTRO DIAGNÓSTICO YA SOLUCIONADO PARA ESTE DIENTE
            <br>POR FAVOR, ELIMINE LA EVOLUCIÓN SI DESEA BORRAR EL CAMBIO EN TRATAMIENTO";
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
        return true;
    }

    function InsertarDientes()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        $fecha_registro=date("Y-m-d");
        $salir=0;
        $a=explode(',',$_REQUEST['tipoproble'.$pfj]);
        if($a[1]==1)
        {
            $_REQUEST['0'.$pfj]=11;
        }
        else if($a[1]==0 AND $_REQUEST['0'.$pfj]==11)
        {
            $this->frmError["MensajeError"]="PROBLEMA QUE REQUIERE ESPECIFICAR UNA SUPERFICIE";
            return true;
        }
        for($i=0;$i<8;$i++)
        {
            if($_REQUEST[$i.$pfj]<>NULL)
            {
                $salir=1;
                $i=8;
            }
        }
        if($_REQUEST['tipoproble'.$pfj]==NULL OR $_REQUEST['ubicacion'.$pfj]==NULL
        OR $salir==0 OR $_REQUEST['tipoproduc'.$pfj]==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
            return true;
        }
        else
        {
            list($dbconn) = GetDBconn();
            $dbconn->BeginTrans();
            $estado=1;
            $query="SELECT hc_odontograma_tratamiento_id
            FROM hc_odontogramas_tratamientos
            WHERE tipo_id_paciente='".$this->tipoidpaciente."'
            AND paciente_id='".$this->paciente."'
            AND sw_activo='1';";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $odonto=$resulta->fields[0];
            if(empty($odonto))
            {
                $query="SELECT NEXTVAL ('hc_odontogramas_tratamientos_hc_odontograma_tratamiento_id_seq');";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                $odonto=$resulta->fields[0];
                $query="INSERT INTO hc_odontogramas_tratamientos
                (hc_odontograma_tratamiento_id,
                tipo_id_paciente,
                paciente_id,
                sw_activo,
                evolucion_id,
                fecha_registro)
                VALUES
                (".$odonto.",
                '".$this->tipoidpaciente."',
                '".$this->paciente."',
                '1',
                ".$this->evolucion.",
                now());";//'".$fecha_registro."'
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    $dbconn->RollbackTrans();
                    return true;
                }
            }
            $query="SELECT A.hc_odontograma_primera_vez_id,
            A.hc_tipo_cuadrante_id,
            A.hc_tipo_problema_diente_id,
            A.hc_tipo_producto_diente_id,
            B.sw_diente_completo
            FROM hc_odontogramas_primera_vez_detalle AS A,
            hc_tipos_problemas_dientes AS B
            WHERE A.hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj]."
            AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id;";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            while(!$resulta->EOF)
            {
                $var=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }
            $a=explode(',',$_REQUEST['tipoproble'.$pfj]);
            //if($a[0]==$var['hc_tipo_problema_diente_id'])//{
            //$this->frmError["MensajeError"]="EL PROBLEMA DE PRIMERA VEZ ES IGUAL AL PROBLEMA DE TRATAMIENTO";return true;//}
            if($a[1]==1)
            {
                $_REQUEST['0'.$pfj]=11;
            }
            else if($a[1]==0 AND $_REQUEST['0'.$pfj]==11)
            {
                $this->frmError["MensajeError"]="PROBLEMA QUE REQUIERE ESPECIFICAR UNA SUPERFICIE";
                return true;
            }
            if($_REQUEST['tipoubicac'.$pfj]>=51)
            {
                $query="SELECT hc_tipo_problema_diente_des_id
                FROM hc_tipos_problemas_dientes_desiduos
                WHERE hc_tipo_problema_diente_des_id=".$a[0].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                if($resulta->fields[0]==NULL)
                {
                    $this->frmError["MensajeError"]="PROBLEMA NO VÁLIDO PARA EL DIENTE ".$_REQUEST['tipoubicac'.$pfj]."";
                    return true;
                }
            }
            $query="SELECT A.hc_tipo_problema_diente_id,
            B.sw_presupuesto
            FROM hc_tipos_problemas_soluciones_dientes AS A,
            hc_tipos_problemas_dientes AS B
            WHERE A.hc_tipo_problema_diente_id=".$a[0]."
            AND A.hc_tipo_producto_diente_id=".$_REQUEST['tipoproduc'.$pfj]."
            AND A.sw_activo='1'
            AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id;";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            if($resulta->fields[0]==NULL AND ($a[0]<>6 OR $a[0]<>7))/*Quitar el AND y lo del parentisis*/
            {
                $this->frmError["MensajeError"]="SOLUCIÓN NO VÁLIDA PARA EL PROBLEMA EN EL DIENTE ".$_REQUEST['tipoubicac'.$pfj]."";
                return true;
            }
            if($var['sw_diente_completo']==0)
            {
                $query="SELECT estado,
                hc_odontograma_primera_vez_detalle_id
                FROM hc_odontogramas_primera_vez_detalle
                WHERE hc_odontograma_primera_vez_id=".$var['hc_odontograma_primera_vez_id']."
                AND hc_tipo_ubicacion_diente_id='".$_REQUEST['ubicacion'.$pfj]."';";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
                while(!$resulta->EOF)
                {
                    $var2[]=$resulta->GetRowAssoc($ToUpper = false);
                    $resulta->MoveNext();
                }
                for($i=0;$i<sizeof($var2);$i++)
                {
                    $prueba=$var2[$i]['estado']+5;
                    $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                    estado='".$prueba."'
                    WHERE hc_odontograma_primera_vez_id=".$var['hc_odontograma_primera_vez_id']."
                    AND hc_odontograma_primera_vez_detalle_id=".$var2[$i]['hc_odontograma_primera_vez_detalle_id']."
                    AND hc_tipo_ubicacion_diente_id='".$_REQUEST['ubicacion'.$pfj]."';";
                    $resulta = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0)
                    {
                        $dbconn->RollbackTrans();
                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                        return true;
                    }
                }
            }
            else
            {
                $prueba=$_REQUEST['estado'.$pfj]+5;
                $query="UPDATE hc_odontogramas_primera_vez_detalle SET
                estado='".$prueba."'
                WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            $sw=0;
            if($_REQUEST['0'.$pfj]<>NULL)
            {
                $query="INSERT INTO hc_odontogramas_tratamientos_detalle
                (hc_odontograma_tratamiento_id,
                hc_tipo_cuadrante_id,
                hc_tipo_ubicacion_diente_id,
                hc_tipo_problema_diente_id,
                hc_tipo_producto_diente_id,
                hc_odontograma_primera_vez_detalle_id,
                evolucion_id,
                estado,
                fecha_registro)
                VALUES
                (".$odonto.",
                ".$_REQUEST['0'.$pfj].",
                '".$_REQUEST['ubicacion'.$pfj]."',
                ".$a[0].",
                ".$_REQUEST['tipoproduc'.$pfj].",
                ".$_REQUEST['odondetadi'.$pfj].",
                ".$this->evolucion.",
                '".$estado."',
                now());";//'".$fecha_registro."'
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                    $dbconn->RollbackTrans();
                    $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                    return true;
                }
            }
            else if($_REQUEST['0'.$pfj]==NULL)
            {
                for($i=1;$i<8;$i++)
                {
                    if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                    OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8
                    OR $_REQUEST['tipoproduc'.$pfj]==7)
                    AND $sw==0 AND $_REQUEST[$i.$pfj]<>NULL)//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
                    {
                        $sw=1;
                    }
                    else if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                    OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8
                    OR $_REQUEST['tipoproduc'.$pfj]==7)
                    AND $sw==1 AND $_REQUEST[$i.$pfj]<>NULL)//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
                    {
                        $_REQUEST['tipoproduc'.$pfj]++;
                    }
                    if($_REQUEST[$i.$pfj]<>NULL)
                    {
                        $query="INSERT INTO hc_odontogramas_tratamientos_detalle
                        (hc_odontograma_tratamiento_id,
                        hc_tipo_cuadrante_id,
                        hc_tipo_ubicacion_diente_id,
                        hc_tipo_problema_diente_id,
                        hc_tipo_producto_diente_id,
                        hc_odontograma_primera_vez_detalle_id,
                        evolucion_id,
                        estado,
                        fecha_registro)
                        VALUES
                        (".$odonto.",
                        ".$_REQUEST[$i.$pfj].",
                        '".$_REQUEST['ubicacion'.$pfj]."',
                        ".$a[0].",
                        ".$_REQUEST['tipoproduc'.$pfj].",
                        ".$_REQUEST['odondetadi'.$pfj].",
                        ".$this->evolucion.",
                        '".$estado."',
                        now());";//'".$fecha_registro."'
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                            $dbconn->RollbackTrans();
                            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                            return true;
                        }
                    }
                }
            }
            $dbconn->CommitTrans();
            $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
            return true;
        }
    }

    function BuscarDatosObser()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT observacion
        FROM hc_odontogramas_tratamientos
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[0];
    }

    function BuscarDatosObserConsulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT observacion
        FROM hc_odontogramas_tratamientos
        WHERE hc_odontograma_tratamiento_id=
        (SELECT MAX(hc_odontograma_tratamiento_id)
        FROM hc_odontogramas_tratamientos
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."');";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $resulta->fields[0];
    }

    function InsertDatosObser()
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT hc_odontograma_tratamiento_id
        FROM hc_odontogramas_tratamientos
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $odonto=$resulta->fields[0];
        if(empty($odonto))
        {
            $query="SELECT NEXTVAL ('hc_odontogramas_tratamientos_hc_odontograma_tratamiento_id_seq');";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $odonto=$resulta->fields[0];
            $query="INSERT INTO hc_odontogramas_tratamientos
            (hc_odontograma_tratamiento_id,
            tipo_id_paciente,
            paciente_id,
            sw_activo,
            observacion,
            evolucion_id)
            VALUES
            (".$odonto.",
            '".$this->tipoidpaciente."',
            '".$this->paciente."',
            '1',
            '".$_REQUEST['observacio2'.$this->frmPrefijo]."',
            ".$this->evolucion.");";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        else if($_REQUEST['observacio2'.$this->frmPrefijo]<>NULL)//Este debe preguntar si los textos son diferentes
        {
            $query="UPDATE hc_odontogramas_tratamientos SET
            observacion='".$_REQUEST['observacio2'.$this->frmPrefijo]."'
            WHERE hc_odontograma_tratamiento_id=".$odonto.";";
            $resulta = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
        return true;
    }

    function EliminDatos()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        list($dbconn) = GetDBconn();
        $query="DELETE FROM hc_odontogramas_tratamientos_detalle
        WHERE hc_odontograma_tratamiento_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
            return true;
        }
    }

    function InsertJustif()
    {
        $pfj=$this->frmPrefijo;
        $this->frmError["MensajeError"]="";
        if($_REQUEST['justificac'.$pfj]==NULL)
        {
            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS - DEBE DIGITAR UNA JUSTIFICACIÓN";
            return true;
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_detalle SET
        justifica_cancelacion='".$_REQUEST['justificac'.$pfj]."',
        usuario_id_justifica=".UserGetUID().",
        estado='2'
        WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="PROCEDIMIENTO CANCELADO CORRECTAMENTE";
        return true;
    }

    function ActivarDientes()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez_detalle SET
        justifica_cancelacion=".'NULL'.",
        usuario_id_justifica=".'NULL'.",
        estado='1'
        WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
            $dbconn->RollbackTrans();
            return true;
        }
        $dbconn->CommitTrans();
        $this->frmError["MensajeError"]="PROCEDIMIENTO ACTIVADO CORRECTAMENTE";
        return true;
    }

		function UltimoOdnotogramaTratamientoInactivo()
		{
			list($dbconn) = GetDBconn();
			$query="(SELECT MAX(D.hc_odontograma_tratamiento_id)
			FROM hc_odontogramas_tratamientos AS D
			WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
			AND D.paciente_id='".$this->paciente."'
			AND D.sw_activo='0');";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
			}
			if(!$resulta->EOF)
			{
							$odonto=$resulta->fields[0];
							$resulta->Close();
							return $odonto;
			}
			return $odonto;
		}
       
    function BuscarEnviarPintarMuelas()
    {
				list($dbconn) = GetDBconn();
				$trata = $this->UltimoOdnotogramaTratamientoInactivo();
				if(empty($trata))
				{
					$query="SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_id,
					B.hc_tipo_problema_diente_id
					FROM hc_odontogramas_primera_vez AS A,
					hc_odontogramas_primera_vez_detalle AS B
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
					AND B.hc_tipo_problema_diente_id<>1
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
					B.hc_tipo_cuadrante_id DESC,
					B.hc_tipo_problema_diente_id ASC;";
				}
				else
				{
					$query="(SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_id,
					B.hc_tipo_problema_diente_id
					FROM hc_odontogramas_primera_vez AS A,
					hc_odontogramas_primera_vez_detalle AS B
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
					AND B.hc_tipo_problema_diente_id<>1
					AND B.hc_tipo_ubicacion_diente_id
						NOT IN
						(
							SELECT B.hc_tipo_ubicacion_diente_id 
							FROM hc_odontogramas_primera_vez AS A, 
									hc_odontogramas_primera_vez_detalle AS B, 
									hc_tipos_problemas_soluciones_dientes AS C 
							WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
							AND A.paciente_id='".$this->paciente."'
							AND A.sw_activo='1' 
							AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
							AND B.hc_tipo_problema_diente_id<>1 
							AND B.estado='0' 
							AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
							AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id 
							ORDER BY B.hc_tipo_ubicacion_diente_id ASC, B.hc_tipo_cuadrante_id DESC, B.hc_tipo_problema_diente_id ASC
						)
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
					B.hc_tipo_cuadrante_id DESC,
					B.hc_tipo_problema_diente_id ASC)
	
			UNION
	
				(SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_id,
					C.hc_tipo_producto_diente_id
					FROM hc_odontogramas_tratamientos AS A,
					hc_odontogramas_tratamientos_detalle AS B,
					hc_tipos_problemas_soluciones_dientes AS C
					WHERE A.hc_odontograma_tratamiento_id=$trata
					AND A.sw_activo='0'
					AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
					AND B.hc_tipo_problema_diente_id<>1
					AND B.estado='0'
					AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
					AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
					B.hc_tipo_cuadrante_id DESC,
					B.hc_tipo_problema_diente_id ASC)

			UNION

					(SELECT B.hc_tipo_ubicacion_diente_id,
							B.hc_tipo_cuadrante_id,
							C.hc_tipo_probsolu_diente_id
					FROM hc_odontogramas_primera_vez AS A,
						hc_odontogramas_primera_vez_detalle AS B,
						hc_tipos_problemas_soluciones_dientes AS C, 
						hc_odontogramas_tratamientos_evolucion_primera_vez AS D
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
					AND B.hc_tipo_problema_diente_id<>1
					AND B.estado='0'
					AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
					AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
					AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id
					AND D.evolucion_id<>".$this->evolucion."
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
						B.hc_tipo_cuadrante_id DESC,
						B.hc_tipo_problema_diente_id ASC)

			UNION

				(SELECT DISTINCT B.hc_tipo_ubicacion_diente_id, 
						B.hc_tipo_cuadrante_id, 
						C.hc_tipo_problema_diente_id 
				FROM hc_odontogramas_primera_vez AS A, 
						hc_odontogramas_primera_vez_detalle AS B,
						hc_tipos_problemas_soluciones_dientes AS C,
						hc_odontogramas_tratamientos_evolucion_primera_vez AS D
				WHERE A.paciente_id='".$this->paciente."' 
				AND A.tipo_id_paciente='".$this->tipoidpaciente."' 
				AND A.sw_activo='1' 
				AND B.sw_control='1' 
				AND B.estado='0' 
				AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
				AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
				AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
				AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id 
				AND D.evolucion_id=".$this->evolucion."
				ORDER BY B.hc_tipo_ubicacion_diente_id ASC, 
						B.hc_tipo_cuadrante_id DESC, 
						C.hc_tipo_problema_diente_id ASC)

			UNION
	
				(SELECT DISTINCT B.hc_tipo_ubicacion_diente_id, 
						B.hc_tipo_cuadrante_id, 
						C.hc_tipo_problema_diente_id 
				FROM hc_odontogramas_primera_vez AS A, 
						hc_odontogramas_primera_vez_detalle AS B,
						hc_tipos_problemas_soluciones_dientes AS C
				WHERE A.paciente_id='".$this->paciente."' 
				AND A.tipo_id_paciente='".$this->tipoidpaciente."' 
				AND A.sw_activo='1' 
				AND B.sw_control='1' 
				AND B.estado='1' 
				AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
				AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
				AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
				AND B.evolucion_id=".$this->evolucion."
				ORDER BY B.hc_tipo_ubicacion_diente_id ASC, 
						B.hc_tipo_cuadrante_id DESC, 
						C.hc_tipo_problema_diente_id ASC)

			UNION
	
						(SELECT B.hc_tipo_ubicacion_diente_id,
						B.hc_tipo_cuadrante_id,
						B.hc_tipo_problema_diente_id
						FROM hc_odontogramas_primera_vez AS A,
						hc_odontogramas_primera_vez_detalle AS B,
						hc_tipos_problemas_soluciones_dientes AS C
						WHERE A.tipo_id_paciente='".$this->tipoidpaciente."' 
						AND A.paciente_id='".$this->paciente."'
						AND A.sw_activo='1'
						AND B.sw_control='1' 
						AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
						AND B.hc_tipo_problema_diente_id<>1
						AND B.estado IN ('1','4') 
						AND B.evolucion_id<>".$this->evolucion."
						AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
						AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
						ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
						B.hc_tipo_cuadrante_id DESC,
						B.hc_tipo_problema_diente_id ASC);";
				}
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i][0]=$resulta->fields[0];
						$var[$i][1]=$resulta->fields[1];
						$var[$i][2]=$resulta->fields[2];
						$i++;
						$resulta->MoveNext();
				}
				return $var;
    }

    function BuscarEnviarPintarMuelasConsulta()
    {
				$control=$this->UltimoOdnotogramaPrimeraVezInactivo();
				if(!empty($control))
				{
					$trata = $this->UltimoOdnotogramaTratamientoInactivo();
					$query="(SELECT B.hc_tipo_ubicacion_diente_id,
								B.hc_tipo_cuadrante_id,
								B.hc_tipo_problema_diente_id
								FROM hc_odontogramas_primera_vez AS A,
												hc_odontogramas_primera_vez_detalle AS B,
												hc_tipos_problemas_dientes AS C
								WHERE A.paciente_id='".$this->paciente."'
								AND A.tipo_id_paciente='".$this->tipoidpaciente."'
								AND A.sw_activo='0'
								AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
								AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
								AND C.sw_heredar='1'
								AND B.hc_tipo_ubicacion_diente_id NOT IN
										(
											SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
											FROM hc_odontogramas_primera_vez AS A, 
												hc_odontogramas_primera_vez_detalle AS B, 
												hc_tipos_problemas_dientes AS C,
												hc_tipos_problemas_soluciones_dientes AS D
											WHERE A.paciente_id='".$this->paciente."'
											AND A.tipo_id_paciente='".$this->tipoidpaciente."'
											AND A.sw_activo='1' 
											AND B.estado='0' 
											AND B.sw_control='1' 
											AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
											AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
											AND C.hc_tipo_problema_diente_id=D.hc_tipo_problema_diente_id
										)
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
								B.hc_tipo_cuadrante_id DESC,
								B.hc_tipo_problema_diente_id ASC)

							UNION

								(SELECT DISTINCT B.hc_tipo_ubicacion_diente_id, 
										B.hc_tipo_cuadrante_id, 
										C.hc_tipo_probsolu_diente_id 
								FROM hc_odontogramas_primera_vez AS A, 
										hc_odontogramas_primera_vez_detalle AS B,
										hc_tipos_problemas_soluciones_dientes AS C,
										hc_odontogramas_tratamientos_evolucion_primera_vez AS D 
								WHERE A.paciente_id='".$this->paciente."' 
								AND A.tipo_id_paciente='".$this->tipoidpaciente."' 
								AND A.sw_activo='1' 
								AND B.sw_control='1' 
								AND B.estado='0' 
								AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
								AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
								AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id 
								AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id
								AND date(D.fecha_registro)<>
								(
									SELECT MAX(date(D.fecha_registro))
									FROM hc_odontogramas_primera_vez AS A, 
										hc_odontogramas_primera_vez_detalle AS B,
										hc_tipos_problemas_soluciones_dientes AS C,
										hc_odontogramas_tratamientos_evolucion_primera_vez AS D 
									WHERE A.paciente_id='".$this->paciente."' 
									AND A.tipo_id_paciente='".$this->tipoidpaciente."' 
									AND A.sw_activo='1' 
									AND B.sw_control='1' 
									AND B.estado='0' 
									AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
									AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id
									AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id 
								)
									ORDER BY B.hc_tipo_ubicacion_diente_id ASC, 
									B.hc_tipo_cuadrante_id DESC, 
									C.hc_tipo_probsolu_diente_id ASC)

							UNION

								(SELECT DISTINCT B.hc_tipo_ubicacion_diente_id, 
										B.hc_tipo_cuadrante_id, 
										C.hc_tipo_problema_diente_id 
								FROM hc_odontogramas_primera_vez AS A, 
										hc_odontogramas_primera_vez_detalle AS B,
										hc_tipos_problemas_soluciones_dientes AS C,
										hc_odontogramas_tratamientos_evolucion_primera_vez AS D
								WHERE A.paciente_id='".$this->paciente."' 
								AND A.tipo_id_paciente='".$this->tipoidpaciente."' 
								AND A.sw_activo='1' 
								AND B.sw_control='1' 
								AND B.estado='0' 
								AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
								AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
								AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
								AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id 
								AND D.evolucion_id=".$this->evolucion."
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC, 
										B.hc_tipo_cuadrante_id DESC, 
										C.hc_tipo_problema_diente_id ASC)

							UNION

								(SELECT B.hc_tipo_ubicacion_diente_id,
								B.hc_tipo_cuadrante_id,
								C.hc_tipo_probsolu_diente_id
								FROM hc_odontogramas_primera_vez AS A,
								hc_odontogramas_primera_vez_detalle AS B,
								hc_tipos_problemas_soluciones_dientes AS C,
								hc_odontogramas_tratamientos_evolucion_primera_vez AS D
								WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
								AND A.paciente_id='".$this->paciente."'
								AND A.sw_activo='1'
								AND B.sw_control='1' 
								AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
								AND B.hc_tipo_problema_diente_id<>1
								AND B.estado='0'
								AND D.hc_odontograma_primera_vez_detalle_id=B.hc_odontograma_primera_vez_detalle_id
								AND D.evolucion_id<>".$this->evolucion."
								AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
								AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
								AND date(D.fecha_registro) =
								(
										SELECT MAX(date(D.fecha_registro))
										FROM hc_odontogramas_primera_vez AS A,
										hc_odontogramas_primera_vez_detalle AS B,
										hc_tipos_problemas_soluciones_dientes AS C,
										hc_odontogramas_tratamientos_evolucion_primera_vez AS D
										WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
										AND A.paciente_id='".$this->paciente."'
										AND A.sw_activo='1'
										AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
										AND B.hc_tipo_problema_diente_id<>1
										AND B.estado='0'
										AND D.hc_odontograma_primera_vez_detalle_id=B.hc_odontograma_primera_vez_detalle_id
										AND D.evolucion_id<>".$this->evolucion."
										AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
								)
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
								B.hc_tipo_cuadrante_id DESC,
								B.hc_tipo_problema_diente_id ASC)

						UNION
				
							(SELECT B.hc_tipo_ubicacion_diente_id,
								B.hc_tipo_cuadrante_id,
								C.hc_tipo_producto_diente_id
								FROM hc_odontogramas_tratamientos AS A,
								hc_odontogramas_tratamientos_detalle AS B,
								hc_tipos_problemas_soluciones_dientes AS C
								WHERE A.hc_odontograma_tratamiento_id=$trata
								AND A.sw_activo='0'
								AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
								AND B.hc_tipo_problema_diente_id<>1
								AND B.estado='0'
								AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
								AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
								B.hc_tipo_cuadrante_id DESC,
								B.hc_tipo_problema_diente_id ASC)

							UNION

								(SELECT DISTINCT B.hc_tipo_ubicacion_diente_id, 
										B.hc_tipo_cuadrante_id, 
										C.hc_tipo_problema_diente_id 
								FROM hc_odontogramas_primera_vez AS A, 
										hc_odontogramas_primera_vez_detalle AS B,
										hc_tipos_problemas_soluciones_dientes AS C
								WHERE A.paciente_id='".$this->paciente."' 
								AND A.tipo_id_paciente='".$this->tipoidpaciente."' 
								AND A.sw_activo='1' 
								AND B.sw_control='1' 
								AND B.estado='1' 
								AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id 
								AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
								AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
								AND B.evolucion_id=".$this->evolucion."
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC, 
										B.hc_tipo_cuadrante_id DESC, 
										C.hc_tipo_problema_diente_id ASC)

							UNION
							
										(SELECT B.hc_tipo_ubicacion_diente_id,
										B.hc_tipo_cuadrante_id,
										B.hc_tipo_problema_diente_id
										FROM hc_odontogramas_primera_vez AS A,
										hc_odontogramas_primera_vez_detalle AS B,
										hc_tipos_problemas_soluciones_dientes AS C
										WHERE A.tipo_id_paciente='".$this->tipoidpaciente."' 
										AND A.paciente_id='".$this->paciente."'
										AND A.sw_activo='1'
										AND B.sw_control='1' 
										AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
										AND B.hc_tipo_problema_diente_id<>1
										AND B.estado IN ('1','4')
										AND B.evolucion_id<>".$this->evolucion."
										AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
										AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
										ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
										B.hc_tipo_cuadrante_id DESC,
										B.hc_tipo_problema_diente_id ASC);";
				}
				else
				{
						$query="SELECT B.hc_tipo_ubicacion_diente_id,
						B.hc_tipo_cuadrante_id,
						B.hc_tipo_problema_diente_id
						FROM hc_odontogramas_primera_vez_detalle AS B
						WHERE B.hc_odontograma_primera_vez_id=
						(SELECT MAX(A.hc_odontograma_primera_vez_id)
						FROM hc_odontogramas_primera_vez AS A
						WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
						AND A.paciente_id='".$this->paciente."')
						AND B.hc_tipo_problema_diente_id<>1
						ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
						B.hc_tipo_cuadrante_id DESC,
						B.hc_tipo_problema_diente_id ASC;";
				}
				list($dbconn) = GetDBconn();
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=0;
				while(!$resulta->EOF)
				{
						$var[$i][0]=$resulta->fields[0];
						$var[$i][1]=$resulta->fields[1];
						$var[$i][2]=$resulta->fields[2];
						$i++;
						$resulta->MoveNext();
				}
				return $var;
    }

		function BuscarEnviarPintarMuelas2()
		{
				list($dbconn) = GetDBconn();
				$vartra=$this->BuscarEnviarPintarMuelas3();
				$trata = $this->UltimoOdnotogramaTratamientoInactivo();
				if(empty($trata))
				{
					$query="SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_id,
					C.hc_tipo_probsolu_diente_id
					FROM hc_odontogramas_primera_vez AS A,
					hc_odontogramas_primera_vez_detalle AS B,
					hc_tipos_problemas_soluciones_dientes AS C
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
					AND B.hc_tipo_problema_diente_id<>1
					AND B.estado='0'
					AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
					AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
					B.hc_tipo_cuadrante_id DESC,
					B.hc_tipo_problema_diente_id ASC;";
				}
				elseif(!empty($trata))
				{
					$query="SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_id,
					C.hc_tipo_probsolu_diente_id
					FROM hc_odontogramas_primera_vez AS A,
					hc_odontogramas_primera_vez_detalle AS B,
					hc_tipos_problemas_soluciones_dientes AS C,
					hc_odontogramas_tratamientos_evolucion_primera_vez AS D
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
					AND B.hc_tipo_problema_diente_id<>1
					AND B.estado='0'
					AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
					AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
					AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id
					AND D.evolucion_id=".$this->evolucion."
					AND B.sw_control='1'
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
					B.hc_tipo_cuadrante_id DESC,
					B.hc_tipo_problema_diente_id ASC;";
				}
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=$j=0;
				while(!$resulta->EOF)
				{
						$varfin[$i][0]=$resulta->fields[0];
						$varfin[$i][1]=$resulta->fields[1];
						$varfin[$i][2]=$resulta->fields[2];
						$i++;
						$resulta->MoveNext();
				}
				$l=sizeof($vartra);
				while($k<$l)
				{
						$varfin[$i][0]=$vartra[$j][0];
						$varfin[$i][1]=$vartra[$j][1];
						$varfin[$i][2]=$vartra[$j][2];
						$i++;
						$j++;
						$k++;
				}
				return $varfin;
		}

		function BuscarEnviarPintarMuelas2Consulta()
		{
				$vartra=$this->BuscarEnviarPintarMuelas3Consulta();
				list($dbconn) = GetDBconn();
				$trata = $this->UltimoOdnotogramaTratamientoInactivo();
				if(empty($trata))
				{
				$query="SELECT B.hc_tipo_ubicacion_diente_id,
				B.hc_tipo_cuadrante_id,
				C.hc_tipo_probsolu_diente_id
				FROM hc_odontogramas_primera_vez AS A,
				hc_odontogramas_primera_vez_detalle AS B,
				hc_tipos_problemas_soluciones_dientes AS C
				WHERE A.hc_odontograma_primera_vez_id = B.hc_odontograma_primera_vez_id
				AND A.tipo_id_paciente='".$this->tipoidpaciente."'
				AND A.paciente_id='".$this->paciente."'
				AND B.hc_tipo_problema_diente_id<>1
				AND B.estado='0'
				AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
				AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
				ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
				B.hc_tipo_cuadrante_id DESC,
				B.hc_tipo_problema_diente_id ASC;";
				//AND A.evolucion_id=".$this->evolucion."
				}
				elseif(!empty($trata))
				{
					$query="SELECT B.hc_tipo_ubicacion_diente_id,
					B.hc_tipo_cuadrante_id,
					C.hc_tipo_probsolu_diente_id
					FROM hc_odontogramas_primera_vez AS A,
					hc_odontogramas_primera_vez_detalle AS B,
					hc_tipos_problemas_soluciones_dientes AS C,
					hc_odontogramas_tratamientos_evolucion_primera_vez AS D
					WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
					AND A.paciente_id='".$this->paciente."'
					AND A.sw_activo='1'
					AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
					AND B.hc_tipo_problema_diente_id<>1
					AND B.estado='0'
					AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
					AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
					AND B.hc_odontograma_primera_vez_detalle_id=D.hc_odontograma_primera_vez_detalle_id
					AND D.evolucion_id=".$this->evolucion."
					AND B.sw_control='1'
					ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
					B.hc_tipo_cuadrante_id DESC,
					B.hc_tipo_problema_diente_id ASC;";
				}
				$resulta = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0)
				{
						$this->error = "Error al Cargar el Modulo";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
				}
				$i=$j=0;
				while(!$resulta->EOF)
				{
						$varfin[$i][0]=$resulta->fields[0];
						$varfin[$i][1]=$resulta->fields[1];
						$varfin[$i][2]=$resulta->fields[2];
						$i++;
						$resulta->MoveNext();
				}
				$l=sizeof($vartra);
				while($k<$l)
				{
						$varfin[$i][0]=$vartra[$j][0];
						$varfin[$i][1]=$vartra[$j][1];
						$varfin[$i][2]=$vartra[$j][2];
						$i++;
						$j++;
						$k++;
				}
				return $varfin;
		}

    function BuscarEnviarPintarMuelas3()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_cuadrante_id,
        C.hc_tipo_probsolu_diente_id
        FROM hc_odontogramas_tratamientos AS A,
        hc_odontogramas_tratamientos_detalle AS B,
        hc_tipos_problemas_soluciones_dientes AS C
        WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND A.sw_activo='1'
        AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id
        AND B.hc_tipo_problema_diente_id<>1
        AND B.estado='0'
        AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
        ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
        B.hc_tipo_cuadrante_id DESC,
        B.hc_tipo_problema_diente_id ASC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i][0]=$resulta->fields[0];
            $var[$i][1]=$resulta->fields[1];
            $var[$i][2]=$resulta->fields[2];
            $i++;
            $resulta->MoveNext();
        }
        return $var;
    }

    function BuscarEnviarPintarMuelas3Consulta()
    {
        list($dbconn) = GetDBconn();
        $query="SELECT B.hc_tipo_ubicacion_diente_id,
        B.hc_tipo_cuadrante_id,
        C.hc_tipo_probsolu_diente_id
        FROM hc_odontogramas_tratamientos AS A,
        hc_odontogramas_tratamientos_detalle AS B,
        hc_tipos_problemas_soluciones_dientes AS C
        WHERE A.hc_odontograma_tratamiento_id = B.hc_odontograma_tratamiento_id
        AND A.tipo_id_paciente='".$this->tipoidpaciente."'
        AND A.paciente_id='".$this->paciente."'
        AND B.evolucion_id=".$this->evolucion."
        AND B.hc_tipo_problema_diente_id<>1
        AND B.estado='0'
        AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
        AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
        ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
        B.hc_tipo_cuadrante_id DESC,
        B.hc_tipo_problema_diente_id ASC;";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i][0]=$resulta->fields[0];
            $var[$i][1]=$resulta->fields[1];
            $var[$i][2]=$resulta->fields[2];
            $i++;
            $resulta->MoveNext();
        }
        return $var;
    }

    function EliminarOdontogramasActivo()//busca el odontograma inicial
    {
    /*OJO CON ODONTOGRAMAS DE TRATAMIENTO ACTIVOS PARA DE DESACTIVARLOS*/
    /*TAMBIEN COLOCAR EL DETALLE QUE ESTEN EN 1 A 5 NUEVAMENTE COMO NO PRESUPUESTADO
    4 CUANDO SE HAGA ALGO EN TRATAMIENTO QUE NO ESTABA EN EL DE 1 VEZ
    TAMBIÉN SE DEBEN DE INACTIVAR EL IPBOLEARY DE PRIMERA VEZ, YA QUE EL DE TRATAMIENTO LO HACE AL CERRAR*/
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="UPDATE hc_odontogramas_primera_vez SET
        sw_activo='0'
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $query="UPDATE hc_odontogramas_tratamientos SET
        sw_activo='0'
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $query="UPDATE hc_indice_ipb_oleary SET
        sw_activo='0'
        WHERE tipo_id_paciente='".$this->tipoidpaciente."'
        AND paciente_id='".$this->paciente."'
        AND sw_activo='1';";
        $resulta = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $dbconn->CommitTrans();
        return true;
    }

		function UltimoOdnotogramaPrimeraVezInactivo()  
		{
				list($dbconn) = GetDBconn();
				$query="SELECT MAX(D.hc_odontograma_primera_vez_id)
				FROM hc_odontogramas_primera_vez AS D
				WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
				AND D.paciente_id='".$this->paciente."'
				AND D.sw_activo='0';";
				$resulta = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}                       
				if(!$resulta->EOF)
				{
					$odonto=$resulta->fields[0];
					$resulta->Close();
					return $odonto;
				}
				return $odonto;
		}

}
?>
