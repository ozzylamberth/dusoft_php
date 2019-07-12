<?php

/**
 * $Id: app_Reportes_Consulta_Externa_user.php,v 1.2 2005/06/03 18:46:59 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Reportes_Consulta_Externa_user extends classModulo
{
    function app_Reportes_Consulta_Externa_user()
    {
        return true;
    }

    function main()
    {
        $this->PantallaInicial();
        return true;
    }

    function UsuariosRepconsultaExterna()//Función de permisos
    {
        list($dbconn) = GetDBconn();
        $usuario=UserGetUID();
        $query = "SELECT A.empresa_id,
                B.razon_social AS descripcion1
                FROM userpermisos_RepconsultaExterna AS A,
                empresas AS B
                WHERE A.usuario_id=".$usuario."
                AND A.empresa_id=B.empresa_id
                ORDER BY descripcion1;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $var1[$resulta->fields[1]]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
        }
        $mtz[0]='EMPRESAS';
        $url[0]='app';
        $url[1]='Reportes_Consulta_Externa';
        $url[2]='user';
        $url[3]='PantallaInicial';
        $url[4]='permisoreconex';
        $this->salida .=gui_theme_menu_acceso('REPORTES CONSULTA EXTERNA', $mtz, $var1, $url, ModuloGetURL('system','Menu'));
        return true;
    }

    function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
    {
        if ($this->frmError[$campo] || $campo=="MensajeError")
        {
            if ($campo=="MensajeError")
            {
                return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            else
            {
                return ("label_error");
            }
        }
        return ("label");
    }

    function LlamaFormaSeleccion()
    {
        $this->FormaSeleccion();
        return true;
    }

    function BuscarDepartamento()//Esta ligado solo a Consulta Externa
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.departamento,
        A.descripcion
        FROM departamentos AS A
        WHERE A.servicio='3'
        AND A.empresa_id='".$_SESSION['recoex']['empresa']."'
        ORDER BY A.descripcion;";
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

    function BuscarTipoConsultas()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_consulta_id,
        departamento,
        especialidad,
        descripcion
        FROM tipos_consulta
        ORDER BY descripcion;";
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

    function BuscarProf()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero
        FROM profesionales_empresas AS A,
        terceros AS B
        WHERE A.tercero_id=B.tercero_id
        AND A.tipo_id_tercero=B.tipo_id_tercero
        AND A.empresa_id='".$_SESSION['recoex']['empresa']."'
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

    function LlamaFormaAgendaMedica()
    {
        if(!empty($_POST['feinictra']))
        {
            $fechas=explode('/',$_POST['feinictra']);
            $day=$fechas[0];
            $mon=$fechas[1];
            $yea=$fechas[2];
            if(!(checkdate($mon, $day, $yea)==0))
            {
                $fech=date ("Y-m-d");
                if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                {
                    $_SESSION['recone']['fechadesde']=$yea.'-'.$mon.'-'.$day;
                }
                else
                {
                    $_POST['feinictra']='';
                    $this->frmError["feinictra"]=1;
                }
            }
            else
            {
                $_POST['feinictra']='';
                $this->frmError["feinictra"]=1;
            }
        }
        else
        {
            $this->frmError["feinictra"]=1;
        }
        if(!empty($_POST['fefinctra']))
        {
            $fechas=explode('/',$_POST['fefinctra']);
            $day=$fechas[0];
            $mon=$fechas[1];
            $yea=$fechas[2];
            if(!(checkdate($mon, $day, $yea)==0))
            {
                $fech=date ("Y-m-d");
                //if($fech >= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                //{
                    if($fecdes <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea)))
                    {
                        $_SESSION['recone']['fechahasta']=$yea.'-'.$mon.'-'.$day;
                    }
                    else
                    {
                        $_POST['fefinctra']='';
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
                $_POST['fefinctra']='';
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
        if(!empty($_POST['depto']))
        {
            $a=explode(',',$_POST['depto']);
            $busqueda1="JOIN profesionales_departamentos AS D ON
            (A.tipo_id_tercero=D.tipo_id_tercero
            AND A.tercero_id=D.tercero_id
            AND D.departamento='".$a[0]."')";
            $_SESSION['recone']['codigodepa']=$a[0];
            $_SESSION['recone']['descridepa']=$a[1];
        }
        if(!empty($_POST['tipoconsul']))
        {
            $a=explode(',',$_POST['tipoconsul']);
            $_SESSION['recone']['codigotico']=$a[0];
            $_SESSION['recone']['descritico']=$a[1];
            $busqueda3="AND X.tipo_consulta_id='".$_SESSION['recone']['codigotico']."'";
        }
        if(!empty($_POST['profesional']))
        {
            $a=explode(',',$_POST['profesional']);
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
        $query = "SELECT DISTINCT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero,
        G.estado
        FROM profesionales AS A
        ".$busqueda1.",
        terceros AS B,
        profesionales_estado AS G,
				agenda_turnos AS X
        WHERE A.tipo_id_tercero=B.tipo_id_tercero
        AND A.tercero_id=B.tercero_id
        AND A.tipo_id_tercero=G.tipo_id_tercero
        AND A.tercero_id=G.tercero_id
        AND A.tipo_id_tercero=X.tipo_id_profesional
        AND A.tercero_id=X.profesional_id
        AND X.empresa_id='".$_SESSION['recoex']['empresa']."'
				AND X.empresa_id=G.empresa_id
        $busqueda2
				$busqueda3
        $busqueda4
        $busqueda5
        ORDER BY A.tipo_id_tercero, A.tercero_id;";
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

    function BuscarFormaDetalleAgenda($tipo,$docu)
    {
        if($_SESSION['recone']['codigotico']<>NULL)
        {
            $busqueda1="AND A.tipo_consulta_id='".$_SESSION['recone']['codigotico']."'";
        }
        if($_SESSION['recone']['fechadesde']<>NULL)
        {
            $busqueda2="AND A.fecha_turno>='".$_SESSION['recone']['fechadesde']."'";
        }
        if($_SESSION['recone']['fechahasta']<>NULL)
        {
            $busqueda3="AND A.fecha_turno<='".$_SESSION['recone']['fechahasta']."'";
        }
        $busqueda4="AND A.tipo_id_profesional='".$tipo."'
        AND A.profesional_id='".$docu."'";
        list($dbconn) = GetDBconn();
        $query = "SELECT A.fecha_turno,
        A.duracion,
        A.agenda_turno_id,
        A.tipo_consulta_id,
        A.consultorio_id,
        B.descripcion,
        C.hora,
        D.tipo_id_paciente,
        D.paciente_id,
        E.primer_apellido ||' '|| E.segundo_apellido ||' '|| E.primer_nombre ||' '|| E.segundo_nombre AS nombre
        FROM agenda_turnos AS A,
        tipos_consulta AS B,
        agenda_citas AS C
        LEFT JOIN agenda_citas_asignadas AS D ON
        (C.agenda_cita_id=D.agenda_cita_id)
        LEFT JOIN pacientes AS E ON
        (D.tipo_id_paciente=E.tipo_id_paciente
        AND D.paciente_id=E.paciente_id)
        WHERE A.tipo_consulta_id=B.tipo_consulta_id
        AND A.agenda_turno_id=C.agenda_turno_id
        $busqueda1
        $busqueda2
        $busqueda3
        $busqueda4
        ORDER BY A.tipo_consulta_id,
        A.fecha_turno, C.hora;";
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

/*
        echo $query = "SELECT DISTINCT A.tipo_id_tercero,
        A.tercero_id,
        B.nombre_tercero,
        G.estado
        FROM profesionales AS A
        ".$busqueda1.",
        terceros AS B,
        profesionales_empresas AS C,
        profesionales_estado AS G,
				agenda_turnos AS X
        WHERE A.tipo_id_tercero=B.tipo_id_tercero
        AND A.tercero_id=B.tercero_id
        AND A.tipo_id_tercero=C.tipo_id_tercero
        AND A.tercero_id=C.tercero_id
        AND C.empresa_id='".$_SESSION['recoex']['empresa']."'
        AND A.tipo_id_tercero=G.tipo_id_tercero
        AND A.tercero_id=G.tercero_id
        AND C.empresa_id=G.empresa_id
        AND A.tipo_id_tercero=X.tipo_id_profesional
        AND A.tercero_id=X.profesional_id
        AND X.empresa_id='".$_SESSION['recoex']['empresa']."'
        $busqueda2
				$busqueda3
        $busqueda4
        $busqueda5
        ORDER BY A.tipo_id_tercero, A.tercero_id;";
*/
}
?>
