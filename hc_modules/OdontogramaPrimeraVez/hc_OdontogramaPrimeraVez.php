
<?php

/**
* Submodulo de Odontograma Primera Vez.
*
* Submodulo para manejar el odontograma del paciente, en su primera atencion medica
* @author Jorge Eliecer Avila Garzon <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_OdontogramaPrimeraVez.php,v 1.49 2008/06/20 22:06:19 cahenao Exp $
*/

/**
* Odontograma Primera Vez
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de odontograma primera vez.
*/

class OdontogramaPrimeraVez extends hc_classModules
{
        var $limit;
        var $conteo;
        var $primeravez;
        var $seismeses;

        function OdontogramaPrimeraVez()
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
                $this->primeravez=0;
                $cprimerave=$this->ComprobarOdontogramaPrimeraVez();
                //MODIFICACION PARA QUE EL TIPO PROFESIONAL 10 (HIGIENISTA) PUEDA
                //CERRAR LA HISTORIA CLINICA SIN QUE EL ODONTOGRAMA ESTE COMPLETAMENTE LLENO
                if($cprimerave < 52 AND $this->tipo_profesional!=10)
                {
                        $this->primeravez=1;
                        return false;
                }
                $primeravez=$this->ComprobarOdontogramaPrimeraVezInactivar();
                if($primeravez==0)
                {
                        $this->EliminarOdontogramasActivo();
                }
                return true;
        }

        function ComprobarOdontogramaPrimeraVez()
        {
                list($dbconn) = GetDBconn();
                $query="SELECT DISTINCT A.hc_tipo_ubicacion_diente_id
                FROM hc_odontogramas_primera_vez_detalle AS A,
                hc_odontogramas_primera_vez AS B
                WHERE B.tipo_id_paciente='".$this->tipoidpaciente."'
                AND B.paciente_id='".$this->paciente."'
                AND B.sw_activo='1'
                AND B.hc_odontograma_primera_vez_id=A.hc_odontograma_primera_vez_id;";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $var=$resulta->RecordCount();
                return $var;
        }

        function ComprobarOdontogramaPrimeraVezInactivar()
        {
                list($dbconn) = GetDBconn();
                $query="SELECT sw_activar
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
                return $resulta->fields[0];
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

        function GetForma()//Desde esta funcion es de JORGE AVILA
        {
                $pfj=$this->frmPrefijo;
                if ($_REQUEST['sel']=='insertarblanco')
                        $_REQUEST['accion'.$pfj]='insertarblanco';
                else
                if ($_REQUEST['sel']=='insertarsinerupcionar')
                        $_REQUEST['accion'.$pfj]='insertarsinerupcionar';
                else
                if ($_REQUEST['sel']=='insertarblancode')
                        $_REQUEST['accion'.$pfj]='insertarblancode';
                else
                if ($_REQUEST['sel']=='insertarausentes')
                        $_REQUEST['accion'.$pfj]='insertarausentes';
                else
                if ($_REQUEST['sel']=='eliminarblanco')
                        $_REQUEST['accion'.$pfj]='eliminarblanco';
                else
                if ($_REQUEST['sel']=='eliminarsinerupcionar')
                        $_REQUEST['accion'.$pfj]='eliminarsinerupcionar';
                else
                if ($_REQUEST['sel']=='eliminarblancode')
                        $_REQUEST['accion'.$pfj]='eliminarblancode';
                else
                if ($_REQUEST['sel']=='eliminarausentes')
                        $_REQUEST['accion'.$pfj]='eliminarausentes';
                //NUEVA OPCION PARA LOS PERMANENTES- INSERTAR-ELIMINAR
                // EXTRAIDOS/AUSENTES
                else
                if ($_REQUEST['sel']=='insertarpermanentesextraidos')
                        $_REQUEST['accion'.$pfj]='insertarpermanentesextraidos';
                else
                if ($_REQUEST['sel']=='eliminarpermanentesausentes')
                        $_REQUEST['accion'.$pfj]='eliminarpermanentesausentes';
                //FIN NUEVA OPCION
                if(empty($_REQUEST['accion'.$pfj]))
                {
                        $this->frmForma();
                }
                elseif($_REQUEST['accion'.$pfj]=='insertaractiodon')
                {
                        if($this->InsertActivacionOdontograma()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='insertarinacodon')
                {
                        if($this->InsertInactivacionOdontograma()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='insertar')
                {
                        if($this->InsertDatos($_REQUEST['trata'])==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='eliminar')
                {
                        if($this->EliminDatos()==true)
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
                elseif($_REQUEST['accion'.$pfj]=='insertarblanco')
                {
                        if($this->InsertDatosBlancos()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='eliminarblanco')
                {
                        if($this->EliminDatosBlancos()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='insertarsinerupcionar')
                {
                        if($this->InsertDatosSinErupcionar()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='eliminarsinerupcionar')
                {
                        if($this->EliminDatosSinErupcionar()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='insertarausentes')
                {
                        if($this->InsertDatosAusentes()==true)
                        {
                                $this->frmForma();
                        }
                }
                //NUEVA OPCION PARA LOS DIENTES PERMANENTES EXTRAIDOS- INSERTAR
                elseif($_REQUEST['accion'.$pfj]=='insertarpermanentesextraidos')
                {
                        if($this->InsertDatosAusentesPermanentes()==true)
                        {
                                $this->frmForma();
                        }
                }
                //FIN OPCION PARA LOS DIENTES PERMANENTES EXTRAIDOS- INSERTAR
                elseif($_REQUEST['accion'.$pfj]=='eliminarausentes')
                {
                        if($this->EliminDatosAusentes()==true)
                        {
                                $this->frmForma();
                        }
                }
                //NUEVA OPCION PARA LOS DIENTES PERMANENTES EXTRAIDOS ELIMINAR
                elseif($_REQUEST['accion'.$pfj]=='eliminarpermanentesausentes')
                {
                        if($this->EliminDatosPermanentesAusentes()==true)
                        {
                                $this->frmForma();
                        }
                }
                //FIN OPCION PARA LOS DIENTES PERMANENTES EXTRAIDOS ELIMINAR
                elseif($_REQUEST['accion'.$pfj]=='insertarblancode')
                {
                        if($this->InsertDatosBlancosDe()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='eliminarblancode')
                {
                        if($this->EliminDatosBlancosDe()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='insertarcopiar')
                {
                        if($this->InsertDatosCopiar()==true)
                        {
                                $this->frmForma();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='insertarapoyos')
                {
                        if($this->InsertDatosApoyos()==true)
                        {
                                $this->frmApoyos();
                        }
                }
                elseif($_REQUEST['accion'.$pfj]=='apoyos')
                {
                        $this->frmApoyos();
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

        function InsertActivacionOdontograma()
        {
                list($dbconn) = GetDBconn();
                $query="UPDATE hc_odontogramas_primera_vez SET
                sw_activar='1'
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
                return true;
        }

        function InsertInactivacionOdontograma()
        {
                list($dbconn) = GetDBconn();
                $query="UPDATE hc_odontogramas_primera_vez SET
                sw_activar='0'
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
                return true;
        }

        function BuscarApoyosOdontologiaGuardados()
        {
                $pfj=$this->frmPrefijo;
                list($dbconn) = GetDBconn();
                $query="SELECT A.cargo,
                B.descripcion,
                C.cantidad,
                C.descripcion_ubicacion,
                C.fecha_registro
                FROM hc_odontogramas_apoyod AS A,
                cups AS B,
                hc_odontogramas_primera_vez_apoyod AS C,
                hc_odontogramas_primera_vez AS D
                WHERE A.cargo=B.cargo
                AND A.cargo=C.cargo
                AND C.hc_odontograma_primera_vez_id=D.hc_odontograma_primera_vez_id
                AND D.tipo_id_paciente='".$this->tipoidpaciente."'
                AND D.paciente_id='".$this->paciente."'
                AND D.sw_activo='1'
                ORDER BY A.cargo;";
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

        function BuscarApoyosOdontologiaGuardadosConsulta()
        {
                $pfj=$this->frmPrefijo;
                list($dbconn) = GetDBconn();
                $query="SELECT A.cargo,
                B.descripcion,
                C.cantidad,
    C.fecha_registro,
    C.descripcion_ubicacion
                FROM hc_odontogramas_apoyod AS A,
                cups AS B,
                hc_odontogramas_primera_vez_apoyod AS C
                WHERE A.cargo=B.cargo
                AND A.cargo=C.cargo
                AND C.hc_odontograma_primera_vez_id=
                (SELECT MAX(D.hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez AS D
                WHERE D.tipo_id_paciente='".$this->tipoidpaciente."'
                AND D.paciente_id='".$this->paciente."'
                AND D.sw_activo='0')
                ORDER BY A.cargo;";
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

        function BuscarApoyosOdontologia()
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
                                        B.descripcion
                                        FROM hc_odontogramas_apoyod AS A,
                                        cups AS B
                                        WHERE A.cargo=B.cargo
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
                                        C.cantidad,
                                        C.cantidad_pend,
                                        C.estado,
                                        C.descripcion_ubicacion,
                                        C.cargo AS guarda
                                        FROM hc_odontogramas_apoyod AS A
                                        LEFT JOIN hc_odontogramas_primera_vez_apoyod AS C ON
                                        (A.cargo=C.cargo
                                        AND C.hc_odontograma_primera_vez_id=".$odonto."),
                                        cups AS B
                                        WHERE A.cargo=B.cargo
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

        function InsertDatosApoyos()
        {
                $pfj=$this->frmPrefijo;
                $contador1=$contador2=$contador3=0;
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
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                for($i=0;$i<sizeof($_REQUEST['vector'.$pfj]);$i++)
                {
                        if($_REQUEST['ayudas'.$i.$pfj]<>NULL
                        AND $_REQUEST['vector'.$pfj][$i]['guarda']==NULL
                        AND $_REQUEST['cantidad'.$i.$pfj]<>NULL
                        AND $_REQUEST['apoyo'.$i.$pfj]<>NULL)
                        {
                                $contador1++;
                                $query="INSERT INTO hc_odontogramas_primera_vez_apoyod
                                (hc_odontograma_primera_vez_id,
                                cargo,
                                cantidad,
                                descripcion_ubicacion,
                                usuario_id,
                                fecha_registro)
                                VALUES
                                (".$odonto.",
                                '".$_REQUEST['vector'.$pfj][$i]['cargo']."',
                                ".$_REQUEST['cantidad'.$i.$pfj].",
                                '".$_REQUEST['apoyo'.$i.$pfj]."',
                                ".UserGetUID().",
                                now());";//'".$fecha_registro."'
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $dbconn->RollbackTrans();
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        return true;
                                }
                        }
                        else if($_REQUEST['ayudas'.$i.$pfj]==NULL
                        AND $_REQUEST['vector'.$pfj][$i]['guarda']<>NULL
                        AND $_REQUEST['vector'.$pfj][$i]['cantidad_pend']==0
                        AND $_REQUEST['vector'.$pfj][$i]['estado']=='1')
                        {
                                $contador2++;
                                $query="DELETE FROM hc_odontogramas_primera_vez_apoyod
                                WHERE hc_odontograma_primera_vez_id=".$odonto."
                                AND cargo='".$_REQUEST['vector'.$pfj][$i]['cargo']."';";
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $dbconn->RollbackTrans();
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        return true;
                                }
                        }
                        else if($_REQUEST['ayudas'.$i.$pfj]<>NULL
                        AND $_REQUEST['vector'.$pfj][$i]['guarda']<>NULL
                        AND ($_REQUEST['cantidad'.$i.$pfj]<>$_REQUEST['vector'.$pfj][$i]['cantidad']
                        OR $_REQUEST['apoyo'.$i.$pfj]<>$_REQUEST['vector'.$pfj][$i]['descripcion_ubicacion'])
                        AND $_REQUEST['vector'.$pfj][$i]['cantidad_pend']==0
                        AND $_REQUEST['vector'.$pfj][$i]['estado']=='1')
                        {
                                //'".$fecha_registro."'
                                $contador3++;
                                $query="UPDATE hc_odontogramas_primera_vez_apoyod SET
                                cantidad='".$_REQUEST['cantidad'.$i.$pfj]."',
                                descripcion_ubicacion='".$_REQUEST['apoyo'.$i.$pfj]."', fecha_registro=now()
                                WHERE hc_odontograma_primera_vez_id=".$odonto."
                                AND cargo='".$_REQUEST['vector'.$pfj][$i]['cargo']."';";
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $dbconn->RollbackTrans();
                                        $this->frmError["MensajeError"]="ERROR AL ACTUALIZAR DATOS: ".$dbconn->ErrorMsg()."";
                                        return true;
                                }
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE: ".$contador1."
                <br>DATOS MODIFICADOS CORRECTAMENTE: ".$contador3."
                <br>DATOS ELIMINADOS CORRECTAMENTE: ".$contador2."";
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

        function BuscarIPBOlearyControl()
        {
                list($dbconn) = GetDBconn();
                $query="SELECT hc_indice_ipb_oleary_id
                FROM hc_indice_ipb_oleary
                WHERE evolucion_id=".$this->evolucion.";";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                return $resulta->fields[0];
        }

        function BuscarOdontogramaForma()
        {
					$this->seismeses=0;
          list($dbconn) = GetDBconn();
					//COMPROBAR SI EL ODONTOGRAMA ES DE CONTROL SEIS MESES
            $query="SELECT count(*)
								FROM hc_odontogramas_primera_vez a,
										hc_odontogramas_primera_vez_detalle b
								WHERE paciente_id='".$this->paciente."'
								AND a.tipo_id_paciente='".$this->tipoidpaciente."'
								AND a.sw_activo='1'
								AND b.sw_control='1'
								AND a.hc_odontograma_primera_vez_id=b.hc_odontograma_primera_vez_id
								AND a.hc_odontograma_primera_vez_id=
									(
													SELECT MAX(a.hc_odontograma_primera_vez_id)
													FROM hc_odontogramas_primera_vez a,
															hc_odontogramas_primera_vez_detalle b
													WHERE paciente_id='".$this->paciente."'
													AND a.tipo_id_paciente='".$this->tipoidpaciente."'
													AND a.sw_activo='1'
													AND b.sw_control='1'
													AND a.hc_odontograma_primera_vez_id=b.hc_odontograma_primera_vez_id
									);";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
								if($resulta->fields[0]>0)
								{
									$this->seismeses=1;
									//$this->frmError["MensajeError"]="LOS DATOS FUERON COPIADOS CORRECTAMENTE PARA LA CITA DE CONTROL";
								}

					//FIN COMPROBAR SI EL ODONTOGRAMA ES DE CONTROL SEIS MESES

            $query="SELECT hc_odontograma_primera_vez_id,
                observacion,
                sw_activar
                FROM hc_odontogramas_primera_vez
                WHERE paciente_id='".$this->paciente."'
                AND tipo_id_paciente='".$this->tipoidpaciente."'
                AND sw_activo='1'
                AND hc_odontograma_primera_vez_id=
                        (
                                SELECT MAX(hc_odontograma_primera_vez_id)
                                FROM hc_odontogramas_primera_vez
                                WHERE paciente_id='".$this->paciente."'
                                AND tipo_id_paciente='".$this->tipoidpaciente."'
                                AND sw_activo='1'
                        );";
                        //evolucion_id=".$this->evolucion." AND
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];
                $_REQUEST['swactivado'.$this->frmPrefijo]=$resulta->fields[2];
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
                        E.nombre
                        FROM hc_odontogramas_primera_vez_detalle AS A,
                        hc_tipos_cuadrantes_dientes AS B,
                        hc_tipos_problemas_dientes AS C,
                        hc_tipos_productos_dientes AS D,
                        system_usuarios AS E
                        WHERE A.hc_odontograma_primera_vez_id=".$odonto."
                        AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
                        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
                        AND A.usuario_id=E.usuario_id
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

        function BuscarOdontogramaFormaConsulta()
        {
              list($dbconn) = GetDBconn();
/*							$control=$this->UltimoOdnotogramaPrimeraVezInactivo();
							if(!empty($control))
							{
								$activo="'1'";
							}
							else
							{
								$activo="'0'";
							}*/
                $query="SELECT A.hc_odontograma_primera_vez_id,
                A.observacion,
                A.sw_activar
                FROM hc_odontogramas_primera_vez AS A
                WHERE A.hc_odontograma_primera_vez_id=
                (SELECT MAX(B.hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez AS B
                WHERE B.tipo_id_paciente='".$this->tipoidpaciente."'
                AND B.paciente_id='".$this->paciente."'
                AND B.sw_activo='1');";
                //evolucion_id=".$this->evolucion." AND
                //AND B.sw_activo=$activo
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];
                $_REQUEST['swactivado'.$this->frmPrefijo]=$resulta->fields[2];
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
                        E.nombre
                        FROM hc_odontogramas_primera_vez_detalle AS A,
                        hc_tipos_cuadrantes_dientes AS B,
                        hc_tipos_problemas_dientes AS C,
                        hc_tipos_productos_dientes AS D,
                        system_usuarios AS E
                        WHERE A.hc_odontograma_primera_vez_id=".$odonto."
                        AND A.hc_tipo_cuadrante_id=B.hc_tipo_cuadrante_id
                        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                        AND A.hc_tipo_producto_diente_id=D.hc_tipo_producto_diente_id
                        AND A.usuario_id=E.usuario_id
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

        //------cambio dar
        function BuscarOdontogramaFormaViejo()
        {
                list($dbconn) = GetDBconn();
                $query="SELECT hc_odontograma_primera_vez_id,
                observacion
                FROM hc_odontogramas_primera_vez
                WHERE hc_odontograma_primera_vez_id=
                (SELECT MAX(hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez
                WHERE paciente_id='".$this->paciente."'
                AND tipo_id_paciente='".$this->tipoidpaciente."'
                AND sw_activo='1'
                );";
                //--AND evolucion_id<>".$this->evolucion." 
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                if($resulta->fields[0]<>NULL)
                {
                        $this->frmError["MensajeError"]="SE ENCONTRÓ UN ODONTOGRAMA ACTIVO";
                        //return true;
                }
                $query="SELECT hc_odontograma_primera_vez_id,
                observacion
                FROM hc_odontogramas_primera_vez
                WHERE hc_odontograma_primera_vez_id=
                (SELECT MAX(hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez
                WHERE paciente_id='".$this->paciente."'
                AND tipo_id_paciente='".$this->tipoidpaciente."'
                AND sw_activo='0');";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];
                if(!empty($odonto))
                {
											$tratamiento=$this->UltimoOdnotogramaTratamientoInactivo();
												$query="(SELECT A.hc_odontograma_primera_vez_detalle_id,
												A.hc_tipo_cuadrante_id,
												A.hc_tipo_producto_diente_id,
												A.hc_tipo_problema_diente_id,
												G.hc_tipo_problema_diente_id AS hc_tipo_problema_diente_id2,
																								
												A.hc_tipo_ubicacion_diente_id,
												A.estado,
												C.sw_cariado,
												C.sw_obturado,
												C.sw_perdidos,
												C.sw_sanos,
												C.sw_presupuesto,
												G.sw_cariado AS sw_cariado2,
												G.sw_obturado AS sw_obturado2,
												G.sw_perdidos AS sw_perdidos2,
												G.sw_sanos AS sw_sanos2,
												H.nombre
												FROM hc_odontogramas_primera_vez_detalle AS A
												LEFT JOIN hc_tipos_problemas_soluciones_dientes AS E ON
												(A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
												AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id)
												LEFT JOIN hc_tipos_problemas_dientes AS G ON
												(E.hc_tipo_probsolu_diente_id=G.hc_tipo_problema_diente_id),
												hc_tipos_problemas_dientes AS C,
												system_usuarios AS H
												WHERE A.hc_odontograma_primera_vez_id=".$odonto."
												AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
												AND A.usuario_id=H.usuario_id
												AND C.sw_heredar='1'
												ORDER BY A.hc_tipo_ubicacion_diente_id ASC, C.sw_cariado DESC,
												C.sw_obturado DESC, C.sw_perdidos DESC, C.sw_sanos DESC)

										UNION

												(SELECT F.hc_odontograma_primera_vez_detalle_id,
															F.hc_tipo_cuadrante_id,
															F.hc_tipo_producto_diente_id,
															F.hc_tipo_problema_diente_id,
															D.hc_tipo_problema_diente_id AS hc_tipo_problema_diente_id2,
												
															F.hc_tipo_ubicacion_diente_id,
															F.estado,
												
															D.sw_cariado,
															D.sw_obturado,
															D.sw_perdidos,
															D.sw_sanos,
															D.sw_presupuesto,
												
															D.sw_cariado AS sw_cariado2,
															D.sw_obturado AS sw_obturado2,
															D.sw_perdidos AS sw_perdidos2,
															D.sw_sanos AS sw_sanos2,
															G.nombre
												
												FROM hc_odontogramas_tratamientos AS A, 
															hc_odontogramas_tratamientos_detalle AS B, 
															hc_tipos_problemas_soluciones_dientes AS C,
															hc_tipos_problemas_dientes AS D,
															hc_odontogramas_primera_vez AS E,
															hc_odontogramas_primera_vez_detalle AS F,
															system_usuarios AS G
												
												WHERE A.hc_odontograma_tratamiento_id=".$tratamiento."
												AND A.sw_activo='0' 
												AND A.hc_odontograma_tratamiento_id=B.hc_odontograma_tratamiento_id 
												AND B.hc_tipo_problema_diente_id<>1 
												AND B.estado='0' 
												AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id 
												AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id 
												AND C.hc_tipo_problema_diente_id=D.hc_tipo_problema_diente_id
												AND B.hc_odontograma_primera_vez_detalle_id=F.hc_odontograma_primera_vez_detalle_id
												AND E.hc_odontograma_primera_vez_id=F.hc_odontograma_primera_vez_id
												AND F.usuario_id=G.usuario_id
												ORDER BY B.hc_tipo_ubicacion_diente_id ASC, B.hc_tipo_cuadrante_id DESC, 
												B.hc_tipo_problema_diente_id ASC);";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        if($resulta->RecordCount()>0)
                        {
													while(!$resulta->EOF)
													{
																	$var[]=$resulta->GetRowAssoc($ToUpper = false);
																	$resulta->MoveNext();
													}
												}
												else
												 $var[1]='no_hay_datos';

                        $var[0]['hc_odontograma_primera_vez_id']=$odonto;
                        
                        $insertar = $this->InsertarDatosCitaControl($var);
                        //ocurrio un error
                        if(empty($insertar))
                        {
                                 "error"; EXIT;
                        }
                }
                return true;
        }
        
        //funcion nueva dar
        function InsertarDatosCitaControl($var)
        {
                        list($dbconn) = GetDBconn();    
                        $dbconn->BeginTrans();
                        $query="SELECT NEXTVAL ('hc_odontogramas_primera_vez_hc_odontograma_primera_vez_id_seq');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        $odonto=$resulta->fields[0];
                        $query="INSERT INTO hc_odontogramas_primera_vez
                        (hc_odontograma_primera_vez_id,
                        evolucion_id,
                        tipo_id_paciente,
                        paciente_id,
                        sw_activo,
                        observacion,
                        fecha_registro)
                        VALUES
                        (".$odonto.",
                        ".$this->evolucion.",
                        '".$this->tipoidpaciente."',
                        '".$this->paciente."',
                        '1',
                        '".$_REQUEST['observacio'.$pfj]."',
                        now());"; 
                        //'".$fecha_registro."'
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo1";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;                                                    
                                $dbconn->RollbackTrans();
                                return false;
                        }
												if($var[1]==='no_hay_datos')
												{
													$this->frmError["MensajeError"]="NO HAY DATOS PARA HEREDAR A LA CITA DE CONTROL";
												}
												else
												{
													for($i=0;$i<sizeof($var);$i++)
													{
																	//0 Realizado.
																	//5 Realizado y con cambios en Tratamiento.
																	if($var[$i]['estado']==0 OR $var[$i]['estado']==5)
																	{
																				$query="INSERT INTO hc_odontogramas_primera_vez_detalle
																					(hc_odontograma_primera_vez_id,
																					hc_tipo_cuadrante_id,
																					hc_tipo_ubicacion_diente_id,
																					hc_tipo_problema_diente_id,
																					hc_tipo_producto_diente_id,
																					estado,
																					evolucion_id,
																					usuario_id,
																					fecha_registro,
																					sw_control)
																					VALUES
																					(".$odonto.",
																					".$var[$i]['hc_tipo_cuadrante_id'].",
																					'".$var[$i]['hc_tipo_ubicacion_diente_id']."',
																					".$var[$i]['hc_tipo_problema_diente_id2'].",
																					".(1).",
																					'3',
																					".$this->evolucion.",
																					".UserGetUID().",
																					now(),
																					'1');";//'".$fecha_registro."'
																					$resulta = $dbconn->Execute($query);
																					if($dbconn->ErrorNo() != 0)
																					{
																									$dbconn->RollbackTrans();
																									$this->fileError = __FILE__;
																									$this->lineError = __LINE__;                                                            
																									$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS1: ".$dbconn->ErrorMsg()."";
																									return true;
																					}
																	}
																	else if($var[$i]['estado']==3 OR $var[$i]['estado']==8)
																	{
																					$query="INSERT INTO hc_odontogramas_primera_vez_detalle
																					(hc_odontograma_primera_vez_id,
																					hc_tipo_cuadrante_id,
																					hc_tipo_ubicacion_diente_id,
																					hc_tipo_problema_diente_id,
																					hc_tipo_producto_diente_id,
																					estado,
																					evolucion_id,
																					usuario_id,
																					fecha_registro,
																					sw_control)
																					VALUES
																					(".$odonto.",
																					".$var[$i]['hc_tipo_cuadrante_id'].",
																					'".$var[$i]['hc_tipo_ubicacion_diente_id']."',
																					".$var[$i]['hc_tipo_problema_diente_id'].",
																					".$var[$i]['hc_tipo_producto_diente_id'].",
																					'3',
																					".$this->evolucion.",
																					".UserGetUID().",
																					now(),
																					'1');"; 
																					$resulta = $dbconn->Execute($query);
																					if($dbconn->ErrorNo() != 0)
																					{
																									$dbconn->RollbackTrans();
																									$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS2: ".$dbconn->ErrorMsg()."";
																									$this->fileError = __FILE__;
																									$this->lineError = __LINE__;                                                            
																									return true;
																					}
																	}
																	else
																	{
																					$query="INSERT INTO hc_odontogramas_primera_vez_detalle
																					(hc_odontograma_primera_vez_id,
																					hc_tipo_cuadrante_id,
																					hc_tipo_ubicacion_diente_id,
																					hc_tipo_problema_diente_id,
																					hc_tipo_producto_diente_id,
																					estado,
																					evolucion_id,
																					usuario_id,
																					fecha_registro,
																					sw_control)
																					VALUES
																					(".$odonto.",
																					".$var[$i]['hc_tipo_cuadrante_id'].",
																					'".$var[$i]['hc_tipo_ubicacion_diente_id']."',
																					".$var[$i]['hc_tipo_problema_diente_id'].",
																					".$var[$i]['hc_tipo_producto_diente_id'].",
																					'1',
																					".$this->evolucion.",
																					".UserGetUID().",
																					now(),
																					'1');";
																					//(1)--'".$fecha_registro."'
																					$resulta = $dbconn->Execute($query);
																					if($dbconn->ErrorNo() != 0)
																					{
																									$dbconn->RollbackTrans();
																									$this->fileError = __FILE__;
																									$this->lineError = __LINE__;                                                    
																									$this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS3: ".$dbconn->ErrorMsg()."";
																									return true;
																					}
																	}
													}// "paso";
													$dbconn->CommitTrans();
													$this->frmError["MensajeError"]="LOS DATOS FUERON COPIADOS CORRECTAMENTE PARA LA CITA DE CONTROL";
												}
                        return true;
        }
        
        //------------fin cambio dar
/*
caombio dar:ESTE FUNCION LA COMENTARIE PORQUE ARRIBA LA ESTOY CAMBIANDO
                
        function BuscarOdontogramaFormaViejo()
        {
                list($dbconn) = GetDBconn();
                $query="SELECT hc_odontograma_primera_vez_id,
                observacion
                FROM hc_odontogramas_primera_vez
                WHERE hc_odontograma_primera_vez_id=
                (SELECT MAX(hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez
                WHERE tipo_id_paciente='".$this->tipoidpaciente."'
                AND paciente_id='".$this->paciente."'
                AND sw_activo='1'
                AND evolucion_id<>".$this->evolucion.");";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                if($resulta->fields[0]<>NULL)
                {
                        $this->frmError["MensajeError"]="SE ENCONTRÓ UN ODONTOGRAMA ACTIVO PERO DE OTRA ATENCIÓN";
                        //return true;
                }
                $query="SELECT hc_odontograma_primera_vez_id,
                observacion
                FROM hc_odontogramas_primera_vez
                WHERE hc_odontograma_primera_vez_id=
                (SELECT MAX(hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez
                WHERE tipo_id_paciente='".$this->tipoidpaciente."'
                AND paciente_id='".$this->paciente."'
                AND sw_activo='0');";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];
                if(!empty($odonto))
                {
                         $query="SELECT A.hc_odontograma_primera_vez_detalle_id,
                        A.hc_tipo_ubicacion_diente_id,
                        A.estado,
                        C.sw_cariado,
                        C.sw_obturado,
                        C.sw_perdidos,
                        C.sw_sanos,
                        C.sw_presupuesto,
                        G.sw_cariado AS sw_cariado2,
                        G.sw_obturado AS sw_obturado2,
                        G.sw_perdidos AS sw_perdidos2,
                        G.sw_sanos AS sw_sanos2,
                        H.nombre
                        FROM hc_odontogramas_primera_vez_detalle AS A
                        LEFT JOIN hc_tipos_problemas_soluciones_dientes AS E ON
                        (A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
                        AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id)
                        LEFT JOIN hc_tipos_problemas_dientes AS G ON
                        (E.hc_tipo_probsolu_diente_id=G.hc_tipo_problema_diente_id),
                        hc_tipos_problemas_dientes AS C,
                        system_usuarios AS H
                        WHERE A.hc_odontograma_primera_vez_id=".$odonto."
                        AND A.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                        AND A.usuario_id=H.usuario_id
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
                $var[0]['hc_odontograma_primera_vez_id']=$odonto;
                return $var;
        }

*/      

        function InsertDatosCopiar()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                $query="SELECT hc_odontograma_primera_vez_id,
                observacion
                FROM hc_odontogramas_primera_vez
                WHERE hc_odontograma_primera_vez_id=
                (SELECT MAX(hc_odontograma_primera_vez_id)
                FROM hc_odontogramas_primera_vez
                WHERE tipo_id_paciente='".$this->tipoidpaciente."'
                AND paciente_id='".$this->paciente."'
                AND sw_activo='0');";
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $_REQUEST['observacio'.$this->frmPrefijo]=$resulta->fields[1];
                if(!empty($odonto))
                {
                        $query="SELECT A.hc_tipo_ubicacion_diente_id,
                        A.hc_tipo_cuadrante_id,
                        A.hc_tipo_producto_diente_id,
                        A.estado,
                        A.hc_tipo_problema_diente_id,
                        G.hc_tipo_problema_diente_id AS hc_tipo_problema_diente_id2
                        FROM hc_odontogramas_primera_vez_detalle AS A
                        LEFT JOIN hc_tipos_problemas_soluciones_dientes AS E ON
                        (A.hc_tipo_problema_diente_id=E.hc_tipo_problema_diente_id
                        AND A.hc_tipo_producto_diente_id=E.hc_tipo_producto_diente_id)
                        LEFT JOIN hc_tipos_problemas_soluciones_dientes AS F ON
                        (A.hc_tipo_problema_diente_id=F.hc_tipo_problema_diente_id
                        AND A.hc_tipo_producto_diente_id=F.hc_tipo_producto_diente_id)
                        LEFT JOIN hc_tipos_problemas_dientes AS G ON
                        (E.hc_tipo_probsolu_diente_id=G.hc_tipo_problema_diente_id)
                        WHERE A.hc_odontograma_primera_vez_id=".$odonto."
                        ORDER BY A.hc_tipo_ubicacion_diente_id;";
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
                        $query="SELECT NEXTVAL ('hc_odontogramas_primera_vez_hc_odontograma_primera_vez_id_seq');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                        }
                        $odonto=$resulta->fields[0];
                        $query="INSERT INTO hc_odontogramas_primera_vez
                        (hc_odontograma_primera_vez_id,
                        evolucion_id,
                        tipo_id_paciente,
                        paciente_id,
                        sw_activo,
                        observacion,
                        fecha_registro)
                        VALUES
                        (".$odonto.",
                        ".$this->evolucion.",
                        '".$this->tipoidpaciente."',
                        '".$this->paciente."',
                        '1',
                        '".$_REQUEST['observacio'.$pfj]."',
                        now());";//'".$fecha_registro."'
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                        }
                        for($i=0;$i<sizeof($var);$i++)
                        {
                                if($var[$i]['estado']==0 OR $var[$i]['estado']==5)
                                {
                                        $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                        (hc_odontograma_primera_vez_id,
                                        hc_tipo_cuadrante_id,
                                        hc_tipo_ubicacion_diente_id,
                                        hc_tipo_problema_diente_id,
                                        hc_tipo_producto_diente_id,
                                        estado,
                                        evolucion_id,
                                        usuario_id,
                                        fecha_registro)
                                        VALUES
                                        (".$odonto.",
                                        ".$var[$i]['hc_tipo_cuadrante_id'].",
                                        '".$var[$i]['hc_tipo_ubicacion_diente_id']."',
                                        ".$var[$i]['hc_tipo_problema_diente_id2'].",
                                        ".(1).",
                                        '3',
                                        ".$this->evolucion.",
                                        ".UserGetUID().",
                                        now());";//'".$fecha_registro."'
                                        $resulta = $dbconn->Execute($query);
                                        if($dbconn->ErrorNo() != 0)
                                        {
                                                $dbconn->RollbackTrans();
                                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                                return true;
                                        }
                                }
                                else if($var[$i]['estado']==3 OR $var[$i]['estado']==8)
                                {
                                        $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                        (hc_odontograma_primera_vez_id,
                                        hc_tipo_cuadrante_id,
                                        hc_tipo_ubicacion_diente_id,
                                        hc_tipo_problema_diente_id,
                                        hc_tipo_producto_diente_id,
                                        estado,
                                        evolucion_id,
                                        usuario_id,
                                        fecha_registro)
                                        VALUES
                                        (".$odonto.",
                                        ".$var[$i]['hc_tipo_cuadrante_id'].",
                                        '".$var[$i]['hc_tipo_ubicacion_diente_id']."',
                                        ".$var[$i]['hc_tipo_problema_diente_id'].",
                                        ".$var[$i]['hc_tipo_producto_diente_id'].",
                                        '3',
                                        ".$this->evolucion.",
                                        ".UserGetUID().",
                                        now());";//'".$fecha_registro."'
                                        $resulta = $dbconn->Execute($query);
                                        if($dbconn->ErrorNo() != 0)
                                        {
                                                $dbconn->RollbackTrans();
                                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                                return true;
                                        }
                                }
                                else
                                {
                                        $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                        (hc_odontograma_primera_vez_id,
                                        hc_tipo_cuadrante_id,
                                        hc_tipo_ubicacion_diente_id,
                                        hc_tipo_problema_diente_id,
                                        hc_tipo_producto_diente_id,
                                        estado,
                                        evolucion_id,
                                        usuario_id,
                                        fecha_registro)
                                        VALUES
                                        (".$odonto.",
                                        ".$var[$i]['hc_tipo_cuadrante_id'].",
                                        '".$var[$i]['hc_tipo_ubicacion_diente_id']."',
                                        ".$var[$i]['hc_tipo_problema_diente_id'].",
                                        ".$var[$i]['hc_tipo_producto_diente_id'].",
                                        '1',
                                        ".$this->evolucion.",
                                        ".UserGetUID().",
                                        now());";//(1)--'".$fecha_registro."'
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
                }
                $this->frmError["MensajeError"]="LOS DATOS FUERON COPIADOS CORRECTAMENTE";
                return true;
        }

        function InsertDatosObser()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
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
                if(empty($odonto))
                {
                        $query="SELECT NEXTVAL ('hc_odontogramas_primera_vez_hc_odontograma_primera_vez_id_seq');";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return true;
                        }
                        $odonto=$resulta->fields[0];
                        $query="INSERT INTO hc_odontogramas_primera_vez
                        (hc_odontograma_primera_vez_id,
                        evolucion_id,
                        tipo_id_paciente,
                        paciente_id,
                        sw_activo,
                        observacion,
                        fecha_registro)
                        VALUES
                        (".$odonto.",
                        ".$this->evolucion.",
                        '".$this->tipoidpaciente."',
                        '".$this->paciente."',
                        '1',
                        '".$_REQUEST['observacio'.$pfj]."',
                        now());";//'".$fecha_registro."'
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return true;
                        }
                }
                else if($_REQUEST['observacio'.$this->frmPrefijo]<>NULL)//Este debe preguntar si los textos son diferentes
                {       //fecha_registro
                        $query="UPDATE hc_odontogramas_primera_vez SET
                        observacion='".$_REQUEST['observacio'.$this->frmPrefijo]."'
                        WHERE hc_odontograma_primera_vez_id=".$odonto.";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                $this->fileError = __FILE__;
                                $this->lineError = __LINE__;
                                return true;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                return true;
        }
//FUNCIÓN INSERTAR DATOS ORIGINAL 10/08/05
//      function InsertDatos()
//      {
//              $pfj=$this->frmPrefijo;
//              $this->frmError["MensajeError"]="";
//              $salir=0;
//              $a=explode(',',$_REQUEST['tipoproble'.$pfj]);
//              if($a[1]==1)
//              {
//                      $_REQUEST['0'.$pfj]=11;
//              }
//              else if($a[1]==0 AND $_REQUEST['0'.$pfj]==11)
//              {
//                      $this->frmError["MensajeError"]="PROBLEMA QUE REQUIERE ESPECIFICAR UNA SUPERFICIE";
//                      return true;
//              }
//              for($i=0;$i<8;$i++)
//              {
//                      if($_REQUEST[$i.$pfj]<>NULL)
//                      {
//                              $salir=1;
//                              $i=8;
//                      }
//              }
//              if($_REQUEST['tipoproble'.$pfj]==NULL OR $_REQUEST['tipoubicac'.$pfj]==NULL
//              OR $salir==0 OR $_REQUEST['tipoproduc'.$pfj]==NULL)
//              {
//                      $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
//                      return true;
//              }
//              else
//              {
//                      list($dbconn) = GetDBconn();
//                      $dbconn->BeginTrans();
//                      $estado=3;
//                      $query="SELECT hc_odontograma_primera_vez_id
//                      FROM hc_odontogramas_primera_vez
//                      WHERE tipo_id_paciente='".$this->tipoidpaciente."'
//                      AND paciente_id='".$this->paciente."'
//                      AND sw_activo='1';";//evolucion_id=".$this->evolucion." AND
//                      $resulta = $dbconn->Execute($query);
//                      if($dbconn->ErrorNo() != 0)
//                      {
//                              $this->error = "Error al Cargar el Modulo";
//                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                              return false;
//                      }
//                      $odonto=$resulta->fields[0];
//                      if(empty($odonto))
//                      {
//                              $query="SELECT NEXTVAL ('hc_odontogramas_primera_vez_hc_odontograma_primera_vez_id_seq');";
//                              $resulta = $dbconn->Execute($query);
//                              if($dbconn->ErrorNo() != 0)
//                              {
//                                      $this->error = "Error al Cargar el Modulo";
//                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                                      return false;
//                              }
//                              $odonto=$resulta->fields[0];
//                              $query="INSERT INTO hc_odontogramas_primera_vez
//                              (hc_odontograma_primera_vez_id,
//                              evolucion_id,
//                              tipo_id_paciente,
//                              paciente_id,
//                              sw_activo,
//                              observacion)
//                              VALUES
//                              (".$odonto.",
//                              ".$this->evolucion.",
//                              '".$this->tipoidpaciente."',
//                              '".$this->paciente."',
//                              '1',
//                              '".$_REQUEST['observacio'.$pfj]."');";
//                              $resulta = $dbconn->Execute($query);
//                              if($dbconn->ErrorNo() != 0)
//                              {
//                                      $dbconn->RollbackTrans();
//                                      $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
//                                      $this->fileError = __FILE__;
//                                      $this->lineError = __LINE__;
//                                      return true;
//                              }
//                      }
//                      if($_REQUEST['tipoubicac'.$pfj]>=51)
//                      {
//                              $query="SELECT hc_tipo_problema_diente_des_id
//                              FROM hc_tipos_problemas_dientes_desiduos
//                              WHERE hc_tipo_problema_diente_des_id=".$a[0].";";
//                              $resulta = $dbconn->Execute($query);
//                              if($dbconn->ErrorNo() != 0)
//                              {
//                                      $this->error = "Error al Cargar el Modulo";
//                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                                      return false;
//                              }
//                              if($resulta->fields[0]==NULL)
//                              {
//                                      $this->frmError["MensajeError"]="PROBLEMA NO VÁLIDO PARA EL DIENTE ".$_REQUEST['tipoubicac'.$pfj]."";
//                                      return true;
//                              }
//                              $query="SELECT hc_tipo_problema_diente_id
//                              FROM hc_odontogramas_primera_vez_detalle
//                              WHERE hc_odontograma_primera_vez_id=".$odonto."
//                              AND hc_tipo_ubicacion_diente_id='".($_REQUEST['tipoubicac'.$pfj]-40)."';";
//                              $resulta = $dbconn->Execute($query);
//                              if($dbconn->ErrorNo() != 0)
//                              {
//                                      $this->error = "Error al Cargar el Modulo";
//                                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                                      return false;
//                              }
//                              if($resulta->fields[0]==NULL)
//                              {
//                                      $this->frmError["MensajeError"]="EL DIENTE '".($_REQUEST['tipoubicac'.$pfj]-40)."' NO TIENE UN DIAGNÓSTICO";
//                                      return true;
//                              }
//                              /*else if(!($resulta->fields[0]==1 OR $resulta->fields[0]==2 OR $resulta->fields[0]==3
//                              OR $resulta->fields[0]==4 OR $resulta->fields[0]==5 OR $resulta->fields[0]==8))
//                              {
//                                      $this->frmError["MensajeError"]="EL DIENTE '".($_REQUEST['tipoubicac'.$pfj]-40)."' TIENE UN DIAGNÓSTICO NO APTO CON UN DIENTE DECIDUO";
//                                      return true;
//                              }*/
//                      }
//                      $query="SELECT A.hc_tipo_problema_diente_id,
//                      B.sw_presupuesto
//                      FROM hc_tipos_problemas_soluciones_dientes AS A,
//                      hc_tipos_problemas_dientes AS B
//                      WHERE A.hc_tipo_problema_diente_id=".$a[0]."
//                      AND A.hc_tipo_producto_diente_id=".$_REQUEST['tipoproduc'.$pfj]."
//                      AND A.sw_activo='1'
//                      AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id;";
//                      $resulta = $dbconn->Execute($query);
//                      if($dbconn->ErrorNo() != 0)
//                      {
//                              $this->error = "Error al Cargar el Modulo";
//                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                              return false;
//                      }
//                      if($resulta->fields[0]==NULL AND ($a[0]<>6 OR $a[0]<>7))/*Quitar el AND y lo del parentisis*/
//                      {
//                              $this->frmError["MensajeError"]="SOLUCIÓN NO VÁLIDA PARA EL PROBLEMA EN EL DIENTE ".$_REQUEST['tipoubicac'.$pfj]."";
//                              return true;
//                      }
//                      if($resulta->fields[1]=='1')
//                      {
//                              $estado=1;
//                      }
//                      $query="SELECT B.sw_diente_completo,
//                      A.hc_tipo_cuadrante_id,
//                      A.hc_tipo_problema_diente_id,
//                      A.hc_tipo_producto_diente_id
//                      FROM hc_odontogramas_primera_vez_detalle AS A,
//                      hc_tipos_problemas_dientes AS B
//                      WHERE A.hc_odontograma_primera_vez_id=".$odonto."
//                      AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id
//                      AND A.hc_tipo_ubicacion_diente_id='".$_REQUEST['tipoubicac'.$pfj]."'
//                      ORDER BY B.sw_diente_completo ASC;";
//                      $resulta = $dbconn->Execute($query);
//                      if($dbconn->ErrorNo() != 0)
//                      {
//                              $this->error = "Error al Cargar el Modulo";
//                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
//                              return false;
//                      }
//                      //*****************************
//                      //PARA EXTRAER EL TIPO DE PROBLEMA 7 (ENDODONCIA INDICADA)
//                      else
//                      {       
//                              while(!$resulta->EOF)
//                              {
//                                      $var[]=$resulta->GetRowAssoc($ToUpper = false);
//                                      $resulta->MoveNext();
//                              }
//                      }
//                      $endodoncia=false;
//                      $i=0;
//                      while($i<sizeof($var))
//                      {
//                              if($var[i][hc_tipo_problema_diente_id]==7)
//                                      $endodoncia=true;
//                              $i++;
//                      }
//                      //FIN EXTRAER EL TIPO DE PROBLEMA 7 (ENDODONCIA INDICADA)
//                      //*****************************
//                      $sw=0;
//                      //if($resulta->EOF AND $_REQUEST['0'.$pfj]<>NULL)
//                      if(($resulta->fields[2]==7 AND $_REQUEST['0'.$pfj]<>NULL) OR
//                                      ($resulta->EOF AND $_REQUEST['0'.$pfj]<>NULL))
//                      {
//                              $query="INSERT INTO hc_odontogramas_primera_vez_detalle
//                              (hc_odontograma_primera_vez_id,
//                              hc_tipo_cuadrante_id,
//                              hc_tipo_ubicacion_diente_id,
//                              hc_tipo_problema_diente_id,
//                              hc_tipo_producto_diente_id,
//                              estado,
//                              evolucion_id,
//                              usuario_id)
//                              VALUES
//                              (".$odonto.",
//                              ".$_REQUEST['0'.$pfj].",
//                              '".$_REQUEST['tipoubicac'.$pfj]."',
//                              ".$a[0].",
//                              ".$_REQUEST['tipoproduc'.$pfj].",
//                              '".$estado."',
//                              ".$this->evolucion.",
//                              ".UserGetUID().");";
//                              $resulta = $dbconn->Execute($query);
//                              if($dbconn->ErrorNo() != 0)
//                              {
//                                      $dbconn->RollbackTrans();
//                                      $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
//                                      $this->fileError = __FILE__;
//                                      $this->lineError = __LINE__;
//                                      return true;
//                              }
//                              $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
//                              $_REQUEST['tipoubicac'.$pfj]='';
//                              $_REQUEST['tipoproble'.$pfj]='';
//                              $_REQUEST['tipoproduc'.$pfj]='';
//                              $dbconn->CommitTrans();
//                              return true;
//                      }
//                      //else if($resulta->EOF AND $_REQUEST['0'.$pfj]==NULL)
//                      else if(($resulta->fields[2]==7 AND $_REQUEST['0'.$pfj]==NULL)
//                                                              OR ($resulta->EOF AND $_REQUEST['0'.$pfj]==NULL) OR
//                                                              $endodoncia)
//                      {
//                              for($i=1;$i<8;$i++)
//                              {
//                                      if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
//                                      OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8)
//                                      //OR $_REQUEST['tipoproduc'.$pfj]==7)
//                                      AND $sw==0 AND $_REQUEST[$i.$pfj]<>NULL)//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
//                                      {
//                                              $sw=1;
//                                      }
//                                      else if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
//                                      OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8)
//                                      //OR $_REQUEST['tipoproduc'.$pfj]==7)
//                                      AND $sw==1 AND $_REQUEST[$i.$pfj]<>NULL)//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
//                                      {
//                                              $_REQUEST['tipoproduc'.$pfj]++;
//                                      }
//                                      if($_REQUEST[$i.$pfj]<>NULL)
//                                      {
//                                              $query="INSERT INTO hc_odontogramas_primera_vez_detalle
//                                              (hc_odontograma_primera_vez_id,
//                                              hc_tipo_cuadrante_id,
//                                              hc_tipo_ubicacion_diente_id,
//                                              hc_tipo_problema_diente_id,
//                                              hc_tipo_producto_diente_id,
//                                              estado,
//                                              evolucion_id,
//                                              usuario_id)
//                                              VALUES
//                                              (".$odonto.",
//                                              ".$_REQUEST[$i.$pfj].",
//                                              '".$_REQUEST['tipoubicac'.$pfj]."',
//                                              ".$a[0].",
//                                              ".$_REQUEST['tipoproduc'.$pfj].",
//                                              '".$estado."',
//                                              ".$this->evolucion.",
//                                              ".UserGetUID().");";
//                                              $resulta = $dbconn->Execute($query);
//                                              if($dbconn->ErrorNo() != 0)
//                                              {
//                                                      $dbconn->RollbackTrans();
//                                                      $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
//                                                      $this->fileError = __FILE__;
//                                                      $this->lineError = __LINE__;
//                                                      return true;
//                                              }
//                                      }
//                              }
//                              $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
//                              $_REQUEST['tipoubicac'.$pfj]='';
//                              $_REQUEST['tipoproble'.$pfj]='';
//                              $_REQUEST['tipoproduc'.$pfj]='';
//                              $dbconn->CommitTrans();
//                              return true;
//                      }
//                      else
//                      {
//                              for($i=1;$i<8;$i++)
//                              {
//                                      $resulta->MoveFirst();
//                                      $inserte=0;
//                                      while(!$resulta->EOF)
//                                      {
//                                              if($resulta->fields[0]==1 AND                                                                                                   //MODIFICACION PARA QUE INSERTE PROBLEMAS DIENTE COMPLETO SOBRE tipo 7 
//                                              !($resulta->fields[2]==6 OR $resulta->fields[2]==24))// OR $resulta->fields[2]==7
//                                              {
//                                                      $dbconn->RollbackTrans();
//                                                      $this->frmError["MensajeError"]="EL DIENTE '".$_REQUEST['tipoubicac'.$pfj]."' TIENE UN PROBLEMA DE DIENTE COMPLETO";
//                                                      return true;
//                                              }
//                                              else if($resulta->fields[0]==1
//                                              AND ($resulta->fields[2]==6 OR $resulta->fields[2]==7 OR $resulta->fields[2]==24)
//                                              AND $_REQUEST[$i.$pfj]<>NULL
//                                              AND ($a[0]==10 OR $a[0]==14 OR $a[0]==20
//                                              OR $a[0]==25 OR $a[0]==26 OR $a[0]==30))
//                                              {
//                                                      $inserte=1;
//                                              }
//                                              else if($resulta->fields[1]<>$_REQUEST[$i.$pfj]
//                                              AND $_REQUEST[$i.$pfj]<>NULL
//                                              AND $resulta->fields[0]==0)
//                                              {
//                                                      $inserte=1;
//                                              }
//                                              else if($resulta->fields[1]==$_REQUEST[$i.$pfj]
//                                              AND $_REQUEST[$i.$pfj]<>NULL)
//                                              {
//                                                              $dbconn->RollbackTrans();
//                                                              $this->frmError["MensajeError"]="DATOS PARA UNA SUPERFICIE REPETIDA";
//                                                              return true;
//                                              }
//                                              if($_REQUEST['tipoproduc'.$pfj]==$resulta->fields[3]
//                                              AND ($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
//                                              OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8))
//                                              //OR $_REQUEST['tipoproduc'.$pfj]==7))//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
//                                              {
//                                                      $_REQUEST['tipoproduc'.$pfj]++;
//                                              }
//                                              $resulta->MoveNext();
//                                      }
//                                      if($inserte==1)
//                                      {
//                                              $query="INSERT INTO hc_odontogramas_primera_vez_detalle
//                                              (hc_odontograma_primera_vez_id,
//                                              hc_tipo_cuadrante_id,
//                                              hc_tipo_ubicacion_diente_id,
//                                              hc_tipo_problema_diente_id,
//                                              hc_tipo_producto_diente_id,
//                                              estado,
//                                              evolucion_id,
//                                              usuario_id)
//                                              VALUES
//                                              (".$odonto.",
//                                              ".$_REQUEST[$i.$pfj].",
//                                              '".$_REQUEST['tipoubicac'.$pfj]."',
//                                              ".$a[0].",
//                                              ".$_REQUEST['tipoproduc'.$pfj].",
//                                              '".$estado."',
//                                              ".$this->evolucion.",
//                                              ".UserGetUID().");";
//                                              $dbconn->Execute($query);
//                                              if($dbconn->ErrorNo() != 0)
//                                              {
//                                                      $dbconn->RollbackTrans();
//                                                      $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
//                                                      $this->fileError = __FILE__;
//                                                      $this->lineError = __LINE__;
//                                                      return true;
//                                              }
//                                              $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
//                                      }
//                              }
//                              if($this->frmError["MensajeError"]==NULL)
//                              {
//                                      $this->frmError["MensajeError"]="EL DIENTE '".$_REQUEST['tipoubicac'.$pfj]."' TIENE UN PROBLEMA DE DIENTE POR SUPERFICIE";
//                              }
//                              $_REQUEST['tipoubicac'.$pfj]='';
//                              $_REQUEST['tipoproble'.$pfj]='';
//                              $_REQUEST['tipoproduc'.$pfj]='';
//                              $dbconn->CommitTrans();
//                              return true;
//                      }
//              }
//      }

        //****MODIFICACIONES A LA FUNCIÓN INSERTAR PARA AGILIZAR EL PROCESO DE
        //INSERTAR DATOS EN EL ODONTOGRAMA
        function InsertDatos($trata)
        {
							if(!empty($_SESSION['PRIMERA_VEZ']['ODONTOGRAMA']))
							{
								$sw_control='1';
							}
							else
							{
								$sw_control='0';
							}

/*
								if()
								{
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
								}
*/
                $pfj=$this->frmPrefijo;
                $this->frmError["MensajeError"]="";
                $salir=0;
                $fecha_registro=date ("Y-m-d");
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
                        $tmp2=false;
                        for($j=11; $j<86; $j++)
                        { 
                                if($_REQUEST['tipoubic'.$j]==on)
                                {
                                        $tmp2=true;
                                }
                        }
		
                if($_REQUEST['tipoproble'.$pfj]==NULL /*OR $_REQUEST['tipoubicac'.$pfj]==NULL*/
                OR $salir==0 OR $_REQUEST['tipoproduc'.$pfj]==NULL OR !$tmp2)
                { 
                        $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
                        return true;
                }
                else
                {
                        //INICIO FOR PARA RECORRER LOS DIENTES QUE SE HAN SELECCIONADO PARA INSERTAR
                        //FOR PARA LOS $_REQUEST['tipoubic'.$j] 
                        for($j=11; $j<86; $j++)
                        { 
                                if($_REQUEST['tipoubic'.$j]==on)
                                { 
                                        list($dbconn) = GetDBconn();
                                        $dbconn->BeginTrans();
                                        $estado=3;
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
                                        if(empty($odonto))
                                        {
                                                $control='1';
                                                $query="SELECT NEXTVAL ('hc_odontogramas_primera_vez_hc_odontograma_primera_vez_id_seq');";
                                                $resulta = $dbconn->Execute($query);
                                                if($dbconn->ErrorNo() != 0)
                                                {
                                                        $this->error = "Error al Cargar el Modulo";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        return false;
                                                }
                                                $odonto=$resulta->fields[0];
                                                $query="INSERT INTO hc_odontogramas_primera_vez
                                                (hc_odontograma_primera_vez_id,
                                                evolucion_id,
                                                tipo_id_paciente,
                                                paciente_id,
                                                sw_activo,
                                                observacion,
                                                fecha_registro)
                                                VALUES
                                                (".$odonto.",
                                                ".$this->evolucion.",
                                                '".$this->tipoidpaciente."',
                                                '".$this->paciente."',
                                                '1',
                                                '".$_REQUEST['observacio'.$pfj]."',
                                                now());";//'".$fecha_registro."'
                                                $resulta = $dbconn->Execute($query);
                                                if($dbconn->ErrorNo() != 0)
                                                {
                                                        $dbconn->RollbackTrans();
                                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                                        $this->fileError = __FILE__;
                                                        $this->lineError = __LINE__;
                                                        return true;
                                                }
                                        }
                                        if($j>=51)//$_REQUEST['tipoubicac'.$pfj]
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
                                                        $this->frmError["MensajeError"]="PROBLEMA NO VÁLIDO PARA EL DIENTE ".$j."[".get_class."][".__LINE__."]";//$_REQUEST['tipoubicac'.$pfj]
                                                        return true;
                                                }
                                                //$_REQUEST['tipoubicac'.$pfj]
                                                $query="SELECT hc_tipo_problema_diente_id
                                                FROM hc_odontogramas_primera_vez_detalle
                                                WHERE hc_odontograma_primera_vez_id=".$odonto."
                                                AND hc_tipo_ubicacion_diente_id='".($j-40)."';";
                                                $resulta = $dbconn->Execute($query);
                                                if($dbconn->ErrorNo() != 0)
                                                {
                                                        $this->error = "Error al Cargar el Modulo";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        return false;
                                                }
                                                if($resulta->fields[0]==NULL)
                                                {
                                                        $this->frmError["MensajeError"]="EL DIENTE '".($j-40)."' NO TIENE UN DIAGNÓSTICO";//$_REQUEST['tipoubicac'.$pfj]
                                                        return true;
                                                }
                                                /*else if(!($resulta->fields[0]==1 OR $resulta->fields[0]==2 OR $resulta->fields[0]==3
                                                OR $resulta->fields[0]==4 OR $resulta->fields[0]==5 OR $resulta->fields[0]==8))
                                                {
                                                        $this->frmError["MensajeError"]="EL DIENTE '".($_REQUEST['tipoubicac'.$pfj]-40)."' TIENE UN DIAGNÓSTICO NO APTO CON UN DIENTE DECIDUO";
                                                        return true;
                                                }*/
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
                                        if($resulta->fields[0]==NULL AND ($a[0]<>6 OR $a[0]<>7)/*Quitar el AND y lo del parentisis*/
																					AND !$_REQUEST['0'.$pfj])//SUPERFICIE TOTAL
                                        {
                                                $this->frmError["MensajeError"]="SOLUCIÓN NO VÁLIDA PARA EL PROBLEMA EN EL DIENTE ".$j."[".get_class."][".__LINE__."]";//$_REQUEST['tipoubicac'.$pfj]
                                                return true;
                                        }
                                        if($resulta->fields[1]=='1')
                                        {
                                                $estado=1;
                                        }
                                        //$_REQUEST['tipoubicac'.$pfj]
//
                                        if(($j>=11 AND $j<=15) OR
                                           ($j>=21 AND $j<=25) OR
                                           ($j>=31 AND $j<=35) OR
                                           ($j>=41 AND $j<=45))
                                        {                      
                                                $query="SELECT hc_tipo_problema_diente_id
                                                FROM hc_odontogramas_primera_vez_detalle
                                                WHERE hc_odontograma_primera_vez_id=".$odonto."
                                                AND hc_tipo_ubicacion_diente_id='".($j+40)."';";
                                                $resulta = $dbconn->Execute($query);
                                                if($dbconn->ErrorNo() != 0)
                                                {
                                                        $this->error = "Error al Cargar el Modulo";
                                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                        return false;
                                                }
                                                //CUANDO SE TRATA DE INSERTAR UN DIENTE PERMANENENTE SANO Y SU
                                                //DESIDUO TIENE COMO DIAGNOSTICO SANO, NO SE DEBE INSERTAR ESTE DIAGNOSTICO
                                                if($resulta->fields[0]==1 AND $_REQUEST['tipoproduc'.$pfj]==1)
                                                {
                                                        $this->frmError["MensajeError"]="EL DIENTE '".($j+40)."'TIENE UN DIAGNÓSTICO";//$_REQUEST['tipoubicac'.$pfj]
                                                        return true;
                                                }
                                        }
//
                                        $query="SELECT B.sw_diente_completo,
                                        A.hc_tipo_cuadrante_id,
                                        A.hc_tipo_problema_diente_id,
                                        A.hc_tipo_producto_diente_id,
                                        A.hc_tipo_ubicacion_diente_id
                                        FROM hc_odontogramas_primera_vez_detalle AS A,
                                        hc_tipos_problemas_dientes AS B
                                        WHERE A.hc_odontograma_primera_vez_id=".$odonto."
                                        AND A.hc_tipo_problema_diente_id=B.hc_tipo_problema_diente_id
                                        AND A.hc_tipo_ubicacion_diente_id='".$j."'
                                        ORDER BY B.sw_diente_completo ASC;";
                                        $resulta = $dbconn->Execute($query); 
                                        if($dbconn->ErrorNo() != 0)
                                        {
                                                $this->error = "Error al Cargar el Modulo";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                                        }
                                        //*****************************
                                        //PARA EXTRAER EL TIPO DE PROBLEMA 7 (ENDODONCIA INDICADA)
                                        else
                                        if ($resulta->RecordCount()>0)
                                        { 
                                                while(!$resulta->EOF)
                                                {
                                                        $var[]=$resulta->GetRowAssoc($ToUpper = false);
                                                        $resulta->MoveNext();
                                                }
                                                $endodoncia=false;
                                                $i=0;
                                                while($i<sizeof($var))
                                                {
                                                        if($var[i][hc_tipo_problema_diente_id]==7)
                                                                $endodoncia=true;
                                                        $i++;
                                                }
                                        }
                                        else    $endodoncia=false;
                                        //FIN EXTRAER EL TIPO DE PROBLEMA 7 (ENDODONCIA INDICADA)
                                        $sw=0;
                                        //if($resulta->EOF AND $_REQUEST['0'.$pfj]<>NULL)
                                        if(($resulta->fields[2]==7 AND $_REQUEST['0'.$pfj]<>NULL) OR
                                                        ($resulta->RecordCount() === 0 AND $_REQUEST['0'.$pfj]<>NULL))
                                        {echo entro3; 
                                                //$_REQUEST['tipoubicac'.$pfj]
                                                $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                                (hc_odontograma_primera_vez_id,
                                                hc_tipo_cuadrante_id,
                                                hc_tipo_ubicacion_diente_id,
                                                hc_tipo_problema_diente_id,
                                                hc_tipo_producto_diente_id,
                                                estado,
                                                evolucion_id,
                                                usuario_id,
                                                fecha_registro,
                                                sw_control)
                                                VALUES
                                                (".$odonto.",
                                                ".$_REQUEST['0'.$pfj].",
                                                '".$j."',
                                                ".$a[0].",
                                                ".$_REQUEST['tipoproduc'.$pfj].",
                                                '".$estado."',
                                                ".$this->evolucion.",
                                                ".UserGetUID().",
                                                now(),
                                                $sw_control);";
                                                //'".$fecha_registro."
                                                $resulta = $dbconn->Execute($query);
                                                if($dbconn->ErrorNo() != 0)
                                                {
                                                        $dbconn->RollbackTrans();
                                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                                        $this->fileError = __FILE__;
                                                        $this->lineError = __LINE__;
                                                        return true;
                                                }
                                                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
/*                                              $_REQUEST['tipoubicac'.$pfj]='';
                                                $_REQUEST['tipoproble'.$pfj]='';
                                                $_REQUEST['tipoproduc'.$pfj]='';*/
                                                //$dbconn->CommitTrans();
                                                //return true;
                                        }
                                        //else if($resulta->EOF AND $_REQUEST['0'.$pfj]==NULL)
/*                                        else if(($resulta->fields[2]===7 AND $_REQUEST['0'.$pfj]==NULL)
                                                 OR ($resulta->EOF AND $_REQUEST['0'.$pfj]==NULL))*/
                                        else if(($resulta->fields[2]===7 AND $_REQUEST['0'.$pfj]==NULL)
                                                 OR ($resulta->RecordCount() === 0 AND $_REQUEST['0'.$pfj]==NULL))
                                                                                //OR $_REQUEST['0'.$pfj]==NULL)
                                        { echo entro2;
                                                //CONSERVAR EL REQUEST ORIGINAL DEL TIPO PRODUCTO, PARA CUANDO
                                                //SE CAMBIE DE DIENTE
                                                $tipoproduc=$_REQUEST['tipoproduc'.$pfj];
                                                for($i=1;$i<8;$i++)
                                                {
                                                        if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                                                        OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8)
                                                        //OR $_REQUEST['tipoproduc'.$pfj]==7)
                                                        AND $sw==0 AND $_REQUEST[$i.$pfj]<>NULL)//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
                                                        {
                                                                $sw=1;
                                                        }
                                                        //SUPERFICIES EN LAS CUALES, SI ES MAS DE UNA, LOS TIPOS PRODUCTOS DIENTES
                                                        //EN LA PRIMERA POSICIÓN ES PRINCIPAL Y LAS DEMAS ADICIONALES
                                                        else if(($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                                                        OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8)
                                                        //OR $_REQUEST['tipoproduc'.$pfj]==7)
                                                        AND $sw==1 AND $_REQUEST[$i.$pfj]<>NULL)//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
                                                        {
                                                                $tipoproduc=$_REQUEST['tipoproduc'.$pfj];
                                                                $tipoproduc++;
                                                        }
                                                        if($_REQUEST[$i.$pfj]<>NULL)
                                                        { 
                                                                //$_REQUEST['tipoubicac'.$pfj]
                                                                //$_REQUEST['tipoproduc'.$pfj]
																																$query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                                                (hc_odontograma_primera_vez_id,
                                                                hc_tipo_cuadrante_id,
                                                                hc_tipo_ubicacion_diente_id,
                                                                hc_tipo_problema_diente_id,
                                                                hc_tipo_producto_diente_id,
                                                                estado,
                                                                evolucion_id,
                                                                usuario_id,
                                                                fecha_registro,
                                                                sw_control)
                                                                VALUES
                                                                (".$odonto.",
                                                                ".$_REQUEST[$i.$pfj].",
                                                                '".$j."',
                                                                ".$a[0].",
                                                                ".$tipoproduc.",
                                                                '".$estado."',
                                                                ".$this->evolucion.",
                                                                ".UserGetUID().",
                                                                now(),
                                                                $sw_control);";
                                                                //'".$fecha_registro."
                                                                $resulta = $dbconn->Execute($query);
                                                                if($dbconn->ErrorNo() != 0)
                                                                {
                                                                        $dbconn->RollbackTrans();
                                                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                                                        $this->fileError = __FILE__;
                                                                        $this->lineError = __LINE__;
                                                                        return true;
                                                                }
                                                        }
                                                }
                                                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
/*                                              $_REQUEST['tipoubicac'.$pfj]='';
                                                $_REQUEST['tipoproble'.$pfj]='';
                                                $_REQUEST['tipoproduc'.$pfj]='';*/
                                                //$dbconn->CommitTrans();
                                                //return true;
                                        }
                                        else
                                        {echo entro; 
                                                $tipoproduc=$_REQUEST['tipoproduc'.$pfj];
                                                for($i=1;$i<8;$i++)
                                                {
                                                        $resulta->MoveFirst();
                                                        $inserte=0;
                                                        while(!$resulta->EOF)
                                                        {
                                                                if($resulta->fields[0]==1 AND                                                                                                   //MODIFICACION PARA QUE INSERTE PROBLEMAS DIENTE COMPLETO SOBRE tipo 7 
                                                                !($resulta->fields[2]==6 OR $resulta->fields[2]==24))// OR $resulta->fields[2]==7
                                                                {echo '-1';
                                                                        $dbconn->RollbackTrans();
                                                                        $this->frmError["MensajeError"]="EL DIENTE '".$j."' TIENE UN PROBLEMA DE DIENTE COMPLETO";//$_REQUEST['tipoubicac'.$pfj]
                                                                        return true;
                                                                }
                                                                else if($resulta->fields[0]==1
                                                                AND ($resulta->fields[2]==6 OR $resulta->fields[2]==7 OR $resulta->fields[2]==24)
                                                                AND $_REQUEST[$i.$pfj]<>NULL
                                                                AND ($a[0]==10 OR $a[0]==14 OR $a[0]==20
                                                                OR $a[0]==25 OR $a[0]==26 OR $a[0]==30))
                                                                {echo '-2';
                                                                        $inserte=1;
                                                                }
                                                                else if($resulta->fields[1]<>$_REQUEST[$i.$pfj]
                                                                AND $_REQUEST[$i.$pfj]<>NULL
                                                                AND $resulta->fields[0]==0)
                                                                {echo 3;
                                                                        $inserte=1;
                                                                }
                                                                else if($resulta->fields[1]==$_REQUEST[$i.$pfj]
                                                                AND $_REQUEST[$i.$pfj]<>NULL)
                                                                {echo '-4';
                                                                                $dbconn->RollbackTrans();
                                                                                $this->frmError["MensajeError"]="DATOS PARA UNA SUPERFICIE REPETIDA";
                                                                                return true;
                                                                }
                                                                //SUPERFICIES EN LAS CUALES, SI ES MAS DE UNA, LOS TIPOS PRODUCTOS DIENTES
                                                                //EN LA PRIMERA POSICIÓN ES PRINCIPAL Y LAS DEMAS ADICIONALES
                                                                if($_REQUEST['tipoproduc'.$pfj]==$resulta->fields[3]
                                                                AND ($_REQUEST['tipoproduc'.$pfj]==2 OR $_REQUEST['tipoproduc'.$pfj]==4
                                                                OR $_REQUEST['tipoproduc'.$pfj]==6 OR $_REQUEST['tipoproduc'.$pfj]==8))
                                                                //OR $_REQUEST['tipoproduc'.$pfj]==7))//Quitar OR $_REQUEST['tipoproduc'.$pfj]==7
                                                                {echo '-5';
                                                                        $tipoproduc=$_REQUEST['tipoproduc'.$pfj];
                                                                        $tipoproduc++;
                                                                }
                                                                $resulta->MoveNext();
                                                        } 
                                                        if($inserte==1)
                                                        {  
                                                                //$_REQUEST['tipoubicac'.$pfj]
                                                                //$_REQUEST['tipoproduc'.$pfj]
																																$query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                                                (hc_odontograma_primera_vez_id,
                                                                hc_tipo_cuadrante_id,
                                                                hc_tipo_ubicacion_diente_id,
                                                                hc_tipo_problema_diente_id,
                                                                hc_tipo_producto_diente_id,
                                                                estado,
                                                                evolucion_id,
                                                                usuario_id,
                                                                fecha_registro,
                                                                sw_control)
                                                                VALUES
                                                                (".$odonto.",
                                                                ".$_REQUEST[$i.$pfj].",
                                                                '".$j."',
                                                                ".$a[0].",
                                                                ".$tipoproduc.",
                                                                '".$estado."',
                                                                ".$this->evolucion.",
                                                                ".UserGetUID().",
                                                                now(),
                                                                $sw_control);";
                                                                //'".$fecha_registro."'
                                                                $dbconn->Execute($query);
                                                                if($dbconn->ErrorNo() != 0)
                                                                {
                                                                        $dbconn->RollbackTrans();
                                                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                                                        $this->fileError = __FILE__;
                                                                        $this->lineError = __LINE__;
                                                                        return true;
                                                                }
                                                                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                                                        }
                                                }
                                                if($this->frmError["MensajeError"]==NULL)
                                                {
                                                        $this->frmError["MensajeError"]="EL DIENTE '".$j."' TIENE UN PROBLEMA DE DIENTE POR SUPERFICIE";//$_REQUEST['tipoubicac'.$pfj]
                                                }
/*                                              $_REQUEST['tipoubicac'.$pfj]='';
                                                $_REQUEST['tipoproble'.$pfj]='';
                                                $_REQUEST['tipoproduc'.$pfj]='';*/
                                                //$dbconn->CommitTrans();
                                                //return true;
                                        }
                                }//FIN DEL IF DE LOS REQUEST EN on
                        }//FIN FOR DE LOS $_REQUEST['tipoubic'.$j]
                }//FIN DEL ELSE IF PPAL
                $_REQUEST['tipoubicac'.$pfj]='';
                $_REQUEST['tipoproble'.$pfj]='';
                $_REQUEST['tipoproduc'.$pfj]='';
                $_REQUEST="";
                $dbconn->CommitTrans();
                $this->RegistrarSubmodulo($this->GetVersion());
                return true;
        }
        //****FIN MODIFICACIONES A LA FUNCIÓN INSERTAR PARA AGILIZAR EL PROCESO DE
        //INSERTAR DATOS EN EL ODONTOGRAMA

        function EliminDatos()
        {
                $pfj=$this->frmPrefijo;
                $this->frmError["MensajeError"]="";
                list($dbconn) = GetDBconn();
                $query="DELETE FROM hc_odontogramas_primera_vez_detalle
                WHERE hc_odontograma_primera_vez_detalle_id=".$_REQUEST['odondetadi'.$pfj].";";
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

        function BuscarEnviarPintarMuelas()
        {
                list($dbconn) = GetDBconn();
                $query="SELECT B.hc_tipo_ubicacion_diente_id,
                B.hc_tipo_cuadrante_id,
                B.hc_tipo_problema_diente_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.paciente_id='".$this->paciente."'
                AND A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_problema_diente_id<>1
                ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
                B.hc_tipo_cuadrante_id DESC,
                B.hc_tipo_problema_diente_id ASC;";//A.evolucion_id=".$this->evolucion." AND
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

        function BuscarEnviarPintarMuelasControl()
        {
                list($dbconn) = GetDBconn();
                //1 SANO
                //2 SIN URUPCIONAR
                //8 AUSENTE POR EXODONCIA
            $trata = $this->UltimoOdnotogramaTratamientoInactivo();
/*						$query="SELECT B.hc_tipo_ubicacion_diente_id,
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
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
								B.hc_tipo_cuadrante_id DESC,
								B.hc_tipo_problema_diente_id ASC;"; */
						$query="(SELECT B.hc_tipo_ubicacion_diente_id,
								B.hc_tipo_cuadrante_id,
								B.hc_tipo_problema_diente_id
								FROM hc_odontogramas_primera_vez AS A,
												hc_odontogramas_primera_vez_detalle AS B,
												hc_tipos_problemas_dientes AS C
								WHERE A.paciente_id='".$this->paciente."'
								AND A.tipo_id_paciente='".$this->tipoidpaciente."'
								AND A.sw_activo='1'
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
									AND B.estado IN ('1', '4')
									AND B.evolucion_id<>".$this->evolucion."
									AND B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
									AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id
									ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
									B.hc_tipo_cuadrante_id DESC,
									B.hc_tipo_problema_diente_id ASC)
;";
                //A.evolucion_id=".$this->evolucion." AND
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
							list($dbconn) = GetDBconn();
/**************************/
							$control=$this->UltimoOdnotogramaPrimeraVezInactivo();
							if(!empty($control))
							{
								$trata=$this->UltimoOdnotogramaTratamientoInactivo();
								$query="(SELECT B.hc_tipo_ubicacion_diente_id,
										B.hc_tipo_cuadrante_id,
										B.hc_tipo_problema_diente_id
										FROM hc_odontogramas_primera_vez AS A,
														hc_odontogramas_primera_vez_detalle AS B,
														hc_tipos_problemas_dientes AS C
										WHERE A.paciente_id='".$this->paciente."'
										AND A.tipo_id_paciente='".$this->tipoidpaciente."'
										AND A.sw_activo='1'
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
											B.hc_tipo_problema_diente_id ASC);
";
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
								AND A.paciente_id='".$this->paciente."'
								AND A.sw_activo='1')
								AND B.hc_tipo_problema_diente_id<>1
								ORDER BY B.hc_tipo_ubicacion_diente_id ASC,
								B.hc_tipo_cuadrante_id DESC,
								B.hc_tipo_problema_diente_id ASC;";//A.evolucion_id=".$this->evolucion." AND
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

        function BuscarEnviarPintarMuelasViejo($odon)
        {
                list($dbconn) = GetDBconn();
               $query="SELECT B.hc_tipo_ubicacion_diente_id,
                B.hc_tipo_cuadrante_id,
                B.hc_tipo_problema_diente_id,
                B.estado,
                C.hc_tipo_probsolu_diente_id
                FROM hc_odontogramas_primera_vez_detalle AS B
                LEFT JOIN hc_tipos_problemas_soluciones_dientes AS C ON
                (B.hc_tipo_problema_diente_id=C.hc_tipo_problema_diente_id
                AND B.hc_tipo_producto_diente_id=C.hc_tipo_producto_diente_id)
                WHERE B.hc_odontograma_primera_vez_id=".$odon."
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
                        if($resulta->fields[3]<>0)
                        {
                                $var[$i][0]=$resulta->fields[0];
                                $var[$i][1]=$resulta->fields[1];
                                $var[$i][2]=$resulta->fields[2];
                        }
                        else
                        {
                                $var[$i][0]=$resulta->fields[0];
                                $var[$i][1]=$resulta->fields[1];
                                $var[$i][2]=$resulta->fields[4];
                        }
                        $i++;
                        $resulta->MoveNext();
                }
                return $var;
        }

        function InsertDatosBlancos()//
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
                A.hc_odontograma_primera_vez_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                $j=0;
                for($i=11;$i<49;$i++)
                {
                        if($var[$j]['hc_tipo_ubicacion_diente_id']==$i)
                        {
                                $j++;
                        }
                        else
                        {
                                $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                (hc_odontograma_primera_vez_id,
                                hc_tipo_cuadrante_id,
                                hc_tipo_ubicacion_diente_id,
                                hc_tipo_problema_diente_id,
                                hc_tipo_producto_diente_id,
                                estado,
                                evolucion_id,
                                usuario_id,
                                fecha_registro)
                                VALUES
                                (".$var[0]['hc_odontograma_primera_vez_id'].",
                                ".(11).",
                                '".$i."',
                                ".(1).",
                                ".(1).",
                                '".(3)."',
                                ".$this->evolucion.",
                                ".UserGetUID().",
                                now());";//'".$fecha_registro."'
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        $dbconn->RollbackTrans();
                                        return true;
                                }
                        }
                        if($i==18)
                        {
                                $i=20;
                        }
                        if($i==28)
                        {
                                $i=30;
                        }
                        if($i==38)
                        {
                                $i=40;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                return true;
        }

        function EliminDatosBlancos()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query="SELECT B.hc_odontograma_primera_vez_detalle_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_problema_diente_id=1
                AND B.hc_tipo_producto_diente_id=1
                AND B.estado='3'
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                for($i=0;$i<sizeof($var);$i++)
                {
                        $query="DELETE FROM hc_odontogramas_primera_vez_detalle
                        WHERE hc_odontograma_primera_vez_detalle_id=".$var[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                return true;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
                return true;
        }

        function InsertDatosBlancosDe()//
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                $query="SELECT A.hc_odontograma_primera_vez_id
                FROM hc_odontogramas_primera_vez AS A
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1';";//A.evolucion_id=".$this->evolucion." AND
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
                FROM hc_odontogramas_primera_vez_detalle AS B
                WHERE B.hc_odontograma_primera_vez_id=".$odonto."
                AND B.hc_tipo_ubicacion_diente_id>49
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                $j=0;
                for($i=51;$i<86;$i++)
                {
                        if($var[$j]['hc_tipo_ubicacion_diente_id']==$i)
                        {
                                $j++;
                        }
                        else
                        {
                                $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                (hc_odontograma_primera_vez_id,
                                hc_tipo_cuadrante_id,
                                hc_tipo_ubicacion_diente_id,
                                hc_tipo_problema_diente_id,
                                hc_tipo_producto_diente_id,
                                estado,
                                evolucion_id,
                                usuario_id,
                                fecha_registro)
                                VALUES
                                (".$odonto.",
                                ".(11).",
                                '".$i."',
                                ".(1).",
                                ".(1).",
                                '".(3)."',
                                ".$this->evolucion.",
                                ".UserGetUID().",
                                now());";//'".$fecha_registro."'
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        $dbconn->RollbackTrans();
                                        return true;
                                }
                        }
                        if($i==55)
                        {
                                $i=60;
                        }
                        if($i==65)
                        {
                                $i=70;
                        }
                        if($i==75)
                        {
                                $i=80;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                return true;
        }

        function EliminDatosBlancosDe()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query="SELECT B.hc_odontograma_primera_vez_detalle_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_problema_diente_id=1
                AND B.hc_tipo_producto_diente_id=1
                AND B.hc_tipo_ubicacion_diente_id>49
                AND B.estado='3'
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                for($i=0;$i<sizeof($var);$i++)
                {
                        $query="DELETE FROM hc_odontogramas_primera_vez_detalle
                        WHERE hc_odontograma_primera_vez_detalle_id=".$var[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                return true;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
                return true;
        }

        function InsertDatosSinErupcionar()//
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id,
                A.hc_odontograma_primera_vez_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                $j=0;
                for($i=11;$i<49;$i++)
                {
                        if($var[$j]['hc_tipo_ubicacion_diente_id']==$i)
                        {
                                $j++;
                        }
                        else
                        {
                                $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                (hc_odontograma_primera_vez_id,
                                hc_tipo_cuadrante_id,
                                hc_tipo_ubicacion_diente_id,
                                hc_tipo_problema_diente_id,
                                hc_tipo_producto_diente_id,
                                estado,
                                evolucion_id,
                                usuario_id,
                                fecha_registro)
                                VALUES
                                (".$var[0]['hc_odontograma_primera_vez_id'].",
                                ".(11).",
                                '".$i."',
                                ".(2).",
                                ".(1).",
                                '".(3)."',
                                ".$this->evolucion.",
                                ".UserGetUID().",
                                now());";//'".$fecha_registro."'
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        $dbconn->RollbackTrans();
                                        return true;
                                }
                        }
                        if($i==18)
                        {
                                $i=20;
                        }
                        if($i==28)
                        {
                                $i=30;
                        }
                        if($i==38)
                        {
                                $i=40;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                return true;
        }

        function EliminDatosSinErupcionar()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query="SELECT B.hc_odontograma_primera_vez_detalle_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_problema_diente_id=2
                AND B.hc_tipo_producto_diente_id=1
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                for($i=0;$i<sizeof($var);$i++)
                {
                        $query="DELETE FROM hc_odontogramas_primera_vez_detalle
                        WHERE hc_odontograma_primera_vez_detalle_id=".$var[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                return true;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
                return true;
        }

        function InsertDatosAusentes()//
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                $query="SELECT A.hc_odontograma_primera_vez_id
                FROM hc_odontogramas_primera_vez AS A
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1';";//A.evolucion_id=".$this->evolucion." AND
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
                FROM hc_odontogramas_primera_vez_detalle AS B
                WHERE B.hc_odontograma_primera_vez_id=".$odonto."
                AND B.hc_tipo_ubicacion_diente_id>49
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                $j=0;
                for($i=51;$i<86;$i++)
                {
                        if($var[$j]['hc_tipo_ubicacion_diente_id']==$i)
                        {
                                $j++;
                        }
                        else
                        {
                                $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                (hc_odontograma_primera_vez_id,
                                hc_tipo_cuadrante_id,
                                hc_tipo_ubicacion_diente_id,
                                hc_tipo_problema_diente_id,
                                hc_tipo_producto_diente_id,
                                estado,
                                evolucion_id,
                                usuario_id,
                                fecha_registro)
                                VALUES
                                (".$odonto.",
                                ".(11).",
                                '".$i."',
                                ".(8).",
                                ".(1).",
                                '".(3)."',
                                ".$this->evolucion.",
                                ".UserGetUID().",
                                now());";//'".$fecha_registro."'
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        $dbconn->RollbackTrans();
                                        return true;
                                }
                        }
                        if($i==55)
                        {
                                $i=60;
                        }
                        if($i==65)
                        {
                                $i=70;
                        }
                        if($i==75)
                        {
                                $i=80;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                return true;
        }
        
        //INSERTAR DATOS AUSENTES - EXTRAIDOS PERMANENTES
        function InsertDatosAusentesPermanentes()//
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $fecha_registro=date ("Y-m-d");
                $query="SELECT A.hc_odontograma_primera_vez_id
                FROM hc_odontogramas_primera_vez AS A
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1';";//A.evolucion_id=".$this->evolucion." AND
                $resulta = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0)
                {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                }
                $odonto=$resulta->fields[0];
                $query="SELECT DISTINCT B.hc_tipo_ubicacion_diente_id
                FROM hc_odontogramas_primera_vez_detalle AS B
                WHERE B.hc_odontograma_primera_vez_id=".$odonto."
                AND B.hc_tipo_ubicacion_diente_id<49
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                $j=0;
                for($i=11;$i<49;$i++)
                {
                        if($var[$j]['hc_tipo_ubicacion_diente_id']==$i)
                        {
                                $j++;
                        }
                        else
                        {
                                $query="INSERT INTO hc_odontogramas_primera_vez_detalle
                                (hc_odontograma_primera_vez_id,
                                hc_tipo_cuadrante_id,
                                hc_tipo_ubicacion_diente_id,
                                hc_tipo_problema_diente_id,
                                hc_tipo_producto_diente_id,
                                estado,
                                evolucion_id,
                                usuario_id,
                                fecha_registro)
                                VALUES
                                (".$odonto.",
                                ".(11).",
                                '".$i."',
                                ".(8).",
                                ".(1).",
                                '".(3)."',
                                ".$this->evolucion.",
                                ".UserGetUID().",
                                now());";//'".$fecha_registro."'
                                $resulta = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0)
                                {
                                        $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                        $dbconn->RollbackTrans();
                                        return true;
                                }
                        }
                        if($i==18)
                        {
                                $i=20;
                        }
                        if($i==28)
                        {
                                $i=30;
                        }
                        if($i==38)
                        {
                                $i=40;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS GUARDADOS CORRECTAMENTE";
                return true;
        }

        function EliminDatosAusentes()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query="SELECT B.hc_odontograma_primera_vez_detalle_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_problema_diente_id=8
                AND B.hc_tipo_producto_diente_id=1
                AND B.hc_tipo_ubicacion_diente_id>49
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                for($i=0;$i<sizeof($var);$i++)
                {
                        $query="DELETE FROM hc_odontogramas_primera_vez_detalle
                        WHERE hc_odontograma_primera_vez_detalle_id=".$var[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                return true;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
                return true;
        }
        
        //ELIMINAR PERMANENTES AUSENTES O EXTRAIDOS
        function EliminDatosPermanentesAusentes()
        {
                list($dbconn) = GetDBconn();
                $dbconn->BeginTrans();
                $query="SELECT B.hc_odontograma_primera_vez_detalle_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B
                WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='1'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                AND B.hc_tipo_problema_diente_id=8
                AND B.hc_tipo_producto_diente_id=1
                AND B.hc_tipo_ubicacion_diente_id<49
                ORDER BY B.hc_tipo_ubicacion_diente_id;";//A.evolucion_id=".$this->evolucion." AND
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
                for($i=0;$i<sizeof($var);$i++)
                {
                        $query="DELETE FROM hc_odontogramas_primera_vez_detalle
                        WHERE hc_odontograma_primera_vez_detalle_id=".$var[$i]['hc_odontograma_primera_vez_detalle_id'].";";
                        $resulta = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0)
                        {
                                $dbconn->RollbackTrans();
                                $this->frmError["MensajeError"]="ERROR AL GUARDAR DATOS: ".$dbconn->ErrorMsg()."";
                                return true;
                        }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="DATOS ELIMINADOS CORRECTAMENTE";
                return true;
        }

//---------------nuevo dar

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
        
        //CONTROL POSTERIOR A LOS SEIS MESES
        function ConrtolPosteriorSeisMeses()
        {  
            list($dbconn) = GetDBconn();
            $query="SELECT count(*)
                    FROM hc_odontogramas_primera_vez AS A,
                         hc_odontogramas_primera_vez_detalle AS B
                    WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
                    AND A.paciente_id='".$this->paciente."'
                    AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
                    AND A.sw_activo='1'
                    AND A.evolucion_id<>".$this->evolucion."
                    AND B.sw_control='1';";
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
        
        //CASO CUANDO LA CITA ES POSTERIOR A LOS SEIS MESES Y SE DEBE DE TRAER
        //SOLO EL ULTIMO ODONTOGRAMA DE TRATAMIENTO
        function BuscarEnviarPintarMuelasUltimoSeisMeses()
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
        
        function BuscarEnviarPintarMuelas2()
        {
            $vartra=$this->BuscarEnviarPintarMuelasUltimoSeisMeses();
            list($dbconn) = GetDBconn();
            $query="SELECT B.hc_tipo_ubicacion_diente_id,
            B.hc_tipo_cuadrante_id,
            C.hc_tipo_probsolu_diente_id,
            date(D.fecha_registro)
            FROM hc_odontogramas_primera_vez AS A,
            hc_odontogramas_primera_vez_detalle AS B,
            hc_tipos_problemas_soluciones_dientes AS C,
            hc_odontogramas_tratamientos_evolucion_primera_vez AS D
            WHERE A.tipo_id_paciente='".$this->tipoidpaciente."'
            AND A.paciente_id='".$this->paciente."'
            AND A.sw_activo='1'
            AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
            AND B.hc_tipo_problema_diente_id<>1
            AND B.estado IN ('0','4')
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
            B.hc_tipo_problema_diente_id ASC;";
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
                $varfin[fecha]=$resulta->fields[3];
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
        //FIN CASO CUANDO LA CITA ES POSTERIOR A LOS SEIS MESES Y SE DEBE DE TRAER
        //SOLO EL ULTIMO ODONTOGRAMA DE TRATAMIENTO
        
        function BuscarEnviarPintarMuelas3($id)
        {
                list($dbconn) = GetDBconn();
                $query="SELECT B.hc_tipo_ubicacion_diente_id,
                B.hc_tipo_cuadrante_id,
                B.hc_tipo_problema_diente_id,
                B.estado,
                C.hc_tipo_probsolu_diente_id
                FROM hc_odontogramas_tratamientos AS A,
                hc_odontogramas_tratamientos_detalle AS B,
                hc_tipos_problemas_soluciones_dientes AS C
                WHERE A.hc_odontograma_tratamiento_id=$id
                --AND A.tipo_id_paciente='".$this->tipoidpaciente."'
                --AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='0'
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
										if($resulta->fields[3]<>0)
										{
											$var[$i][0]=$resulta->fields[0];
											$var[$i][1]=$resulta->fields[1];
											$var[$i][2]=$resulta->fields[2];
										}
										else
										{
											$var[$i][0]=$resulta->fields[0];
											$var[$i][1]=$resulta->fields[1];
											$var[$i][2]=$resulta->fields[4];
										}
//                    $var[$i][0]=$resulta->fields[0];
//                    $var[$i][1]=$resulta->fields[1];
//                    $var[$i][2]=$resulta->fields[2];
										$i++;
										$resulta->MoveNext();
                }
                return $var;
        }
        
        
        function BuscarEnviarPintarMuelasTratamiento($idT,$idP)
        {
                $vartra=$this->BuscarEnviarPintarMuelas3($idT);
                list($dbconn) = GetDBconn();
                $query="SELECT B.hc_tipo_ubicacion_diente_id,
                B.hc_tipo_cuadrante_id,
                B.hc_tipo_problema_diente_id,
                B.estado,
                C.hc_tipo_probsolu_diente_id
                FROM hc_odontogramas_primera_vez AS A,
                hc_odontogramas_primera_vez_detalle AS B,
                hc_tipos_problemas_soluciones_dientes AS C
                WHERE A.hc_odontograma_primera_vez_id=$idP
                --A.tipo_id_paciente='".$this->tipoidpaciente."'
                --AND A.paciente_id='".$this->paciente."'
                AND A.sw_activo='0'
                AND A.hc_odontograma_primera_vez_id=B.hc_odontograma_primera_vez_id
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
                $i=$j=0;
                while(!$resulta->EOF)
                {
										if($resulta->fields[3]<>0)
										{
											$varfin[$i][0]=$resulta->fields[0];
											$varfin[$i][1]=$resulta->fields[1];
											$varfin[$i][2]=$resulta->fields[2];
										}
										else
										{
											$varfin[$i][0]=$resulta->fields[0];
											$varfin[$i][1]=$resulta->fields[1];
											$varfin[$i][2]=$resulta->fields[4];
										}
/*                        $varfin[$i][0]=$resulta->fields[0];
                        $varfin[$i][1]=$resulta->fields[1];
                        $varfin[$i][2]=$resulta->fields[2];*/
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
        
//----------------------------------------      
}
?>
