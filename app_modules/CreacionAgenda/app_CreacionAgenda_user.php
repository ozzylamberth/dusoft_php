<?php

/**
 * $Id: app_CreacionAgenda_user.php,v 1.32 2006/07/07 21:21:47 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para crear la agenda de los profesionales, para poder realizar la asignacion de  * citas
 */

/**
* CreacionAgenda
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta de la creacion de agenda.
*
*/

class app_CreacionAgenda_user extends classModulo
{


/**
* Esta funcion Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

    function app_CreacionAgenda_user()
    {
        return true;
    }


/**
* Esta funcion es la que llama la funcion para mostrar las acciones que puede realizar el usuario
*
* @access public
* @return boolean Para identificar que se realizo.
*/

    function main(){
    if(!$this->Menu())
        {
            return false;
        }
        return true;
    }


/**
* Esta funcion muestra el listado de permisos que tiene el usuario.
*
* @access public
* @return boolean Para identificar que se realizo.
* @param string direccion donde debe conectar despues de recoger los permisos
*/

    function CitaConsulta($url)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        if(!empty($_SESSION['SYSTEM_USUARIO_ID'])){
            $sql="select b.tipo_consulta_id, e.descripcion as descripcion5, b.departamento, c.descripcion as descripcion4, d.empresa_id, d.razon_social as descripcion1,cu.centro_utilidad,cu.descripcion as descripcion2,uf.unidad_funcional,uf.descripcion as descripcion3
            from userpermisos_creacion_agenda as a, tipos_consulta as b, departamentos as c, empresas as d, tipos_servicios_ambulatorios as e,centros_utilidad cu,unidades_funcionales uf
            where a.tipo_consulta_id=b.tipo_consulta_id and a.usuario_id=".$_SESSION['SYSTEM_USUARIO_ID']." and b.departamento=c.departamento and c.empresa_id=d.empresa_id and b.tipo_consulta_id=e.tipo_servicio_amb_id AND
            cu.empresa_id=c.empresa_id AND cu.centro_utilidad=c.centro_utilidad AND uf.empresa_id=c.empresa_id AND uf.centro_utilidad=c.centro_utilidad AND uf.unidad_funcional=c.unidad_funcional
            order by c.empresa_id,c.centro_utilidad,c.unidad_funcional,c.departamento,b.tipo_consulta_id;";
        }else{
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "EL USUARIO NO SE HA REGISTRADO.";
            return false;
        }
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        $i=0;
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while ($data = $result->FetchRow()) {
                $prueba4[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']][$data['descripcion5']]=$data;
            }
      $i=1;
        }
        if($i<>0){
            $mtz[0]='Empresa';
            $mtz[1]='Centro Utilidad';
            $mtz[2]='Unidad Funcional';
            $mtz[3]='Departamento';
            $mtz[4]='Tipo de Consulta';
            $accion=ModuloGetURL('app','CreacionAgenda','user','main');
            $this->salida.=gui_theme_menu_acceso('MATRIZ DE PERMISOS ADMINISTRATIVOS',$mtz,$prueba4,$url,$accion);
            return true;
        }else{
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "EL USUARIO NO TIENE EMPRESAS PARA MOSTRAR.";
            return false;
        }
    }



/**
* Esta funcion retorna los profesionales a los que se le puede crear agenda
*
* @access public
* @return array retorna el vector con los profesionales a los que se les puede crear agenda.
*/


    function Profesionales()
    {
        list($dbconn) = GetDBconn();
        $sql="SELECT a.tipo_id_tercero, a.tercero_id, b.tipo_id_tercero, b.tercero_id, e.nombre_tercero as nombre,
                    d.estado
                    FROM profesionales as a
                    JOIN (SELECT tipo_id_tercero, tercero_id FROM profesionales_especialidades as a
                          JOIN (SELECT especialidad FROM tipos_consulta WHERE departamento='".$_SESSION['CreacionAgenda']['departamento']."'
                          AND tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']."
                    ) AS b ON(a.especialidad=b.especialidad)) AS b ON (a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id)
                    JOIN profesionales_estado AS d ON(a.tipo_id_tercero=d.tipo_id_tercero AND a.tercero_id=d.tercero_id AND d.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."'
                        AND d.departamento='".$_SESSION['CreacionAgenda']['departamento']."' and d.estado='1')
                    JOIN terceros AS e ON(d.tipo_id_tercero=e.tipo_id_tercero and
                    d.tercero_id=e.tercero_id)
                    JOIN profesionales_departamentos AS x ON(a.tipo_id_tercero=x.tipo_id_tercero and a.tercero_id=x.tercero_id
                    AND x.departamento='".$_SESSION['CreacionAgenda']['departamento']."')
                    ORDER BY e.nombre_tercero;";
    /*echo  $sql="select a.tipo_id_tercero, a.tercero_id, b.tipo_id_tercero, b.tercero_id, e.nombre_tercero as nombre,
                    d.estado
                    from profesionales_empresas as a
                    left join
                    (   select tipo_id_tercero, tercero_id
                            from profesionales_especialidades as a
                            join
                                    (   select especialidad
                                        from tipos_consulta
                                        where departamento='".$_SESSION['CreacionAgenda']['departamento']."'
                                        and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']."
                                    ) as b
                            on (a.especialidad=b.especialidad)
                    ) as b
                    on (a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id)
                    join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)
                    left join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero
                    and a.tercero_id=d.tercero_id and d.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."'
                    and d.departamento='".$_SESSION['CreacionAgenda']['departamento']."')
                    left join terceros as e on(c.tipo_id_tercero=e.tipo_id_tercero and
                    c.tercero_id=e.tercero_id)
                    where a.empresa_id='".$_SESSION['CreacionAgenda']['empresa']."'
                    order by e.nombre_tercero;";*/

        $result = $dbconn->Execute($sql);
        $i=$t=0;
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
                if($result->fields[5]==1 or (empty($result->fields[5]) and !($result->fields[5]==='0')))
                {
                    $profesionales[0][$i]=$result->fields[0];
                    $profesionales[1][$i]=$result->fields[1];
                    $profesionales[2][$i]=$result->fields[4];
                    if($result->fields[2]!='' and $result->fields[3]!='')
                    {
                        $profesionales2[0][$t]=$result->fields[2];
                        $profesionales2[1][$t]=$result->fields[3];
                        $profesionales2[2][$t]=$result->fields[4];
                        $t++;
                    }
                    $i++;
                }
                $result->MoveNext();
            }
        }
        $result->close();
        $sql="select especialidad from tipos_consulta where tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita'].";";
        $result = $dbconn->Execute($sql);
        //print_r($result);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if($i<>0)
        {
            if($profesionales2!='' or !empty($result->fields[0]))
            {
                return $profesionales2;
            }
            else
            {
                return $profesionales;
            }
        }
        else
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "NO EXISTEN PROFESIONALES PARA ESA EMPRESA.";
            return false;
        }
    }




/**
* Esta funcion retorna los profesionales que tienen agenda del dia de hoy hacia adelante
*
* @access public
* @return array retorna el vector con los profesionales.
*/


    function Profesionales2()
    {
        list($dbconn) = GetDBconn();
        $sql="select distinct(tipo_id_profesional), profesional_id, c.nombre_tercero as nombre
    from agenda_turnos as a, profesionales as b, terceros as c
    where a.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and tipo_consulta_id='".$_SESSION['BorrarAgenda']['Cita']."' and
        a.tipo_id_profesional=b.tipo_id_tercero and a.profesional_id=b.tercero_id and date(fecha_turno)>=date(now()) and
        c.tipo_id_tercero=b.tipo_id_tercero and c.tercero_id=b.tercero_id and a.sw_estado_cancelacion='0'
    --and a.agenda_turno_id IN (SELECT d.agenda_turno_id FROM agenda_citas as d WHERE a.agenda_turno_id=d.agenda_turno_id AND d.sw_estado='0')";
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
                $profesionales[0][$i]=$result->fields[0];
                $profesionales[1][$i]=$result->fields[1];
                $profesionales[2][$i]=$result->fields[2];
                $i++;
                $result->MoveNext();
            }
        }
        if($i<>0)
        {
            return $profesionales;
        }
        else
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "NO EXISTEN PROFESIONALES CON AGENDA PARA ESA EMPRESA.";
            return false;
        }
    }



/**
* Esta funcion retorna el listado de los turnos de un profesional
*
* @access public
* @return array retorna el vector con los turnos de un profesional.
*/


    function ListadoTurnosMes()
    { //le quite la condicion b.sw_estado='0' porque Tulua mando requerimiento  194
        list($dbconn) = GetDBconn();
        $sql="select distinct fecha_turno, a.agenda_turno_id
        from agenda_turnos as a, agenda_citas as b
        where empresa_id='".$_SESSION['BorrarAgenda']['empresa']."' and
        tipo_consulta_id=".$_SESSION['BorrarAgenda']['Cita']." and
        date(fecha_turno)>=date(now()) and tipo_id_profesional='".$_SESSION['BorrarAgenda']['DatosProf']['tipoid']."' and
        profesional_id='".$_SESSION['BorrarAgenda']['DatosProf']['tercero']."' and a.sw_estado_cancelacion=0 and
        a.agenda_turno_id=b.agenda_turno_id order by fecha_turno;";
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
                $turnos[0][$i]=$result->fields[0];
                $turnos[1][$i]=$result->fields[1];
                $i++;
                $result->MoveNext();
            }
        }
        if($i<>0)
        {
            return $turnos;
        }
        else
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "NO EXISTEN PROFESIONALES CON AGENDA PARA ESA EMPRESA.";
            return false;
        }
    }


/**
* Esta funcion retorna el listado de los turnos de un profesional por dia
*
* @access public
* @return array retorna el vector con los turnos de un profesional por dia.
*/


    function ListadoTurnosDia()
    {
        list($dbconn) = GetDBconn();
        $a=explode(",",$_REQUEST['TurnoAgenda']);
        if(sizeof($a)==1){
            $sql="SELECT a.hora, a.agenda_cita_id, a.agenda_turno_id, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo, c.residencia_telefono, c.paciente_id, c.tipo_id_paciente,b.agenda_cita_id_padre
            FROM agenda_citas as a
            LEFT JOIN agenda_citas_asignadas as b ON (a.agenda_cita_id=b.agenda_cita_id AND b.agenda_cita_asignada_id NOT IN(SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion))           
            LEFT JOIN pacientes as c on(b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente)
            WHERE a.agenda_turno_id=".$_REQUEST['TurnoAgenda']." and (a.sw_estado='0' OR a.sw_estado='1' OR a.sw_estado='2') order by a.hora,b.tipo_id_paciente,b.paciente_id;";
        }else{
            $sql="SELECT DISTINCT a.hora, a.agenda_cita_id, a.agenda_turno_id, c.primer_nombre || ' ' || c.segundo_nombre || ' ' || c.primer_apellido || ' ' || c.segundo_apellido as nombre_completo, c.residencia_telefono, c.paciente_id, c.tipo_id_paciente,b.agenda_cita_id_padre
            FROM agenda_citas as a
            LEFT JOIN agenda_citas_asignadas as b ON (a.agenda_cita_id=b.agenda_cita_id AND b.agenda_cita_asignada_id NOT IN(SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion))           
            LEFT JOIN pacientes as c on(b.paciente_id=c.paciente_id and b.tipo_id_paciente=c.tipo_id_paciente)
            WHERE (a.agenda_turno_id=".$a[0];
            foreach($a as $v=>$datos){
                if(!empty($datos) and $v!=0){
                    $sql.=" or a.agenda_turno_id=".$datos;
                }
            }
            $sql.=") and (a.sw_estado='0' OR a.sw_estado='1' OR a.sw_estado='2') order by a.hora,c.tipo_id_paciente,c.paciente_id;";
            

        }    
        $result = $dbconn->Execute($sql);
        $i=0;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $turnosdia[0][$i]=$result->fields[0];
                $turnosdia[1][$i]=$result->fields[1];
                $turnosdia[2][$i]=$result->fields[2];
                $turnosdia[3][$i]=$result->fields[3];
                $turnosdia[4][$i]=$result->fields[4];
                $turnosdia[5][$i]=$result->fields[5];
                $turnosdia[6][$i]=$result->fields[6];
                $turnosdia[7][$i]=$result->fields[7];
                $i++;
                $result->MoveNext();
            }
        }
        if($i<>0){
            return $turnosdia;
        }else{
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "NO EXISTEN PROFESIONALES CON AGENDA PARA ESA EMPRESA.";
            return false;
        }
    }




/**
* Esta funcion retorna el listado de los profesionales por especialidad
*
* @access public
* @return array retorna el vector con los profesionales por especialidad.
* @param string numero de identificacion de especialidad
*/


    function BuscarProfesionales($especialidad)
    {
        list($dbconn) = GetDBconn();
        if(!empty($especialidad))
        {
            $sql="select a.tipo_id_tercero, a.tercero_id, e.nombre_tercero as nombre, d.estado
                        from profesionales_empresas as a
                        join profesionales_especialidades as b on(a.tipo_id_tercero=b.tipo_id_tercero and a.tercero_id=b.tercero_id
                        and b.especialidad='$especialidad')
                        join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)
                        join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id
                        and d.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."'
                        and d.departamento='".$_SESSION['BorrarAgenda']['departamento']."' and d.estado='1')
                        join terceros as e on(a.tipo_id_tercero=e.tipo_id_tercero and   a.tercero_id=e.tercero_id)
                        join profesionales_departamentos as x on(a.tipo_id_tercero=x.tipo_id_tercero and a.tercero_id=x.tercero_id
                        and x.departamento='".$_SESSION['BorrarAgenda']['departamento']."')
                        where a.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."'
                        order by e.nombre_tercero;";
        }
        else
        {
            $sql="select a.tipo_id_tercero, a.tercero_id, c.nombre, d.estado
                        from profesionales_empresas as a
                        join profesionales as c on (a.tipo_id_tercero=c.tipo_id_tercero and a.tercero_id=c.tercero_id)
                        left join profesionales_estado as d on(a.tipo_id_tercero=d.tipo_id_tercero and a.tercero_id=d.tercero_id
                        and d.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."'
                        and d.departamento='".$_SESSION['BorrarAgenda']['departamento']."'and d.estado='1')
                        join terceros as e on(a.tipo_id_tercero=e.tipo_id_tercero and   a.tercero_id=e.tercero_id)
                        join profesionales_departamentos as x on(a.tipo_id_tercero=x.tipo_id_tercero and a.tercero_id=x.tercero_id
                        and x.departamento='".$_SESSION['BorrarAgenda']['departamento']."')
                        where a.empresa_id='".$_SESSION['BorrarAgenda']['empresa']."'
                        order by e.nombre_tercero;";
        }
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF)
        {
            if($result->fields[5]==1 or (empty($result->fields[5]) and !($result->fields[5]==='0')))
            {
                $profesionales[]=$result->GetRowAssoc(false);
            }
            $result->MoveNext();
        }
        return $profesionales;
    }


/**
* Esta funcion retorna el listado de las fechas con agenda activa
*
* @access public
* @return array retorna el vector con las fechas activas.
*/

    function BusquedaAgendas()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        foreach($_SESSION['BorrarAgenda']['datos'] as $k=>$v)
        {
            $_REQUEST[$k]=$v;
        }
        foreach($_REQUEST as $v=>$datos)
        {
            if(substr_count ($v,'seleccion')==1)
            {
                $_SESSION['BorrarAgenda']['datos'][$v]=$datos;
                $a=explode(",",$datos);
                if(sizeof($a)==1)
                {
                    $sql="select a.agenda_cita_id, a.hora, b.*, c.especialidad
                    from agenda_citas as a
                    join agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id)
                    join tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id)
                    where a.agenda_turno_id=".$datos." and a.sw_estado='0' order by b.fecha_turno || ' ' || a.hora;";
                }
                else
                {
                    $sql="select a.agenda_cita_id, a.hora, b.*, c.especialidad
                    from agenda_citas as a
                    join agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id)
                    join tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id)
                    where (a.agenda_turno_id=".$a[0];
                    foreach($a as $v=>$datos1)
                    {
                        if(!empty($datos1) and $v!=0)
                        {
                            $sql.=" or a.agenda_turno_id=".$datos1;
                        }
                    }
                    $sql.=") and a.sw_estado='0' order by b.fecha_turno || ' ' || a.hora;";
                }
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                $i=0;
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                while (!$result->EOF)
                {
                    $agenda_cita[$result->fields['agenda_turno_id']][$result->fields['agenda_cita_id']]=$result->GetRowAssoc(false);
                    $result->MoveNext();
                }
            }
        }
        return $agenda_cita;
    }




/**
* Esta funcion cambia el turno completo de la agenda de un profesional
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/

    function CambiarAgendaTurnoCompleto()
    {
        if(empty($_REQUEST['DiaEspe']))
        {
            if($this->CambiarAgendaCompleta('ESCOJE LA FECHA')==false)
            {
                return false;
            }
        }
        else
        {
            if($_REQUEST['DiaEspe']<date("Y-m-d"))
            {
                if($this->CambiarAgendaCompleta('FECHA ANTERIOR AL DÍA DE HOY')==false)
                {
                    return false;
                }
            }
            else
            {
                if(empty($_REQUEST['Cambiar']) or $_REQUEST['justificacion']==-1)
                {
                    if($_REQUEST['justificacion']==-1)
                    {
                        $this->frmError["justificacion"]=1;
                    }
                    if($this->CambiarAgendaCompleta()==false)
                    {
                        return false;
                    }
                }
                else
                {
                    if($this->PantallaFinalCambioAgenda()==false)
                    {
                        return false;
                    }
                }
            }
        }
        return true;
    }



/**
* Esta funcion muestra la ultima pantalla en la busqueda de agendas
*
* @access public
* @return array retorna vector con la informacion de la agenda
*/

    function BusquedaAgendasPantallaFinal()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        foreach($_REQUEST as $v=>$datos){
            if(substr_count ($v,'turno')==1){
                $a=explode(",",$datos);
                if(sizeof($a)==1){
                    $sql="
                    SELECT a.*,
                                d.agenda_cita_id_padre,d.agenda_cita_asignada_id, f.sw_estado,
                                g.paciente_id || ' - ' || g.tipo_id_paciente as identificacion, g.primer_nombre || ' ' || g.segundo_nombre || ' ' || g.primer_apellido || ' ' || g.segundo_apellido as nombre_completo  
                    FROM
                            (
                            SELECT a.agenda_cita_id, a.hora, b.*, c.especialidad,prof.nombre as nomprofesional
                            FROM agenda_citas as a
                            JOIN agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id)
                            JOIN profesionales prof ON (b.tipo_id_profesional=prof.tipo_id_tercero AND b.profesional_id=prof.tercero_id)
                            JOIN tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id)                         
                            WHERE a.agenda_turno_id=".$datos." and (a.sw_estado='0' OR a.sw_estado='1' OR a.sw_estado='2')      
                            ) as a
                    LEFT JOIN agenda_citas_asignadas as d ON (a.agenda_cita_id=d.agenda_cita_id AND d.agenda_cita_asignada_id NOT IN(SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion))                   
                    LEFT JOIN pacientes as g ON(d.tipo_id_paciente=g.tipo_id_paciente and d.paciente_id=g.paciente_id)
                    LEFT JOIN os_cruce_citas as e ON (d.agenda_cita_asignada_id=e.agenda_cita_asignada_id)
                    LEFT JOIN os_maestro as f ON(e.numero_orden_id=f.numero_orden_id and (f.sw_estado!='3' or f.sw_estado is null))                                 
                    ORDER BY a.fecha_turno || ' ' || a.hora,g.tipo_id_paciente,g.paciente_id;";
                }else{                
                    $sql="
                    
                    SELECT a.*,d.agenda_cita_asignada_id,f.sw_estado, 
                                    g.paciente_id || ' - ' || g.tipo_id_paciente as identificacion, 
                                    g.primer_nombre || ' ' || g.segundo_nombre || ' ' || g.primer_apellido || ' ' || g.segundo_apellido as nombre_completo,
                                    d.agenda_cita_id_padre
                    
                    FROM    
                        (                       
                        
                        SELECT a.agenda_cita_id, a.hora, b.*, c.especialidad,
                        prof.nombre as nomprofesional
                        
                        FROM agenda_citas as a
                        JOIN agenda_turnos as b on(a.agenda_turno_id=b.agenda_turno_id)
                        JOIN profesionales prof ON(b.tipo_id_profesional=prof.tipo_id_tercero AND b.profesional_id=prof.tercero_id)
                        JOIN tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id)
                        
                        WHERE (a.agenda_turno_id=".$a[0];
                        foreach($a as $v=>$datos1){                         
                            if(!empty($datos1) and $v!=0){
                                $sql.=" or a.agenda_turno_id=".$datos1;
                            }
                        }
                        $sql.=") and (a.sw_estado='0' OR a.sw_estado='1' OR a.sw_estado='2')        
                        
                        ) as a 
                    LEFT JOIN agenda_citas_asignadas as d on(a.agenda_cita_id=d.agenda_cita_id AND agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion))                 
                    LEFT JOIN pacientes as g on(d.tipo_id_paciente=g.tipo_id_paciente and d.paciente_id=g.paciente_id)
                    LEFT JOIN os_cruce_citas as e on (d.agenda_cita_asignada_id=e.agenda_cita_asignada_id)
                    LEFT JOIN os_maestro as f on(e.numero_orden_id=f.numero_orden_id and (f.sw_estado!='3' or f.sw_estado is null))
                    
                    
                    
                    order by a.fecha_turno || ' ' || a.hora,g.tipo_id_paciente,g.paciente_id;";
                }               
                //(SELECT 1 FROM agenda_turnos z,agenda_citas x WHERE a.hora=x.hora AND x.agenda_turno_id=z.agenda_turno_id AND z.fecha_turno='".$_REQUEST['DiaEspe']."' AND z.profesional_id='".$profesional[1]."' AND z.tipo_id_profesional='".$profesional[0]."' AND z.tipo_consulta_id='".$_SESSION['BorrarAgenda']['Cita']."' AND z.sw_estado_cancelacion=0 AND z.sw_estado_cancelacion=0 AND (x.sw_estado is null OR x.sw_estado=0)) as existe                
                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($sql);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
                while (!$result->EOF){
                    $agenda_cita[$result->fields['agenda_turno_id']][$result->fields['agenda_cita_id']][$result->fields['agenda_cita_asignada_id']]=$result->GetRowAssoc(false);
                    $result->MoveNext();
                }
            }
        }
        return $agenda_cita;
    }

    function VerificarHoraVaciaAgenda($hora,$fecha,$profesional){

     list($dbconn) = GetDBconn();
      $profesionalTot=explode(',',$profesional);
        //cambie el nombre de la base de datos sw_estado x sw_cantidad_pacientes_asignados
    $query="SELECT *
        FROM agenda_turnos z,agenda_citas x
        WHERE x.hora='$hora' AND x.agenda_turno_id=z.agenda_turno_id AND z.fecha_turno='".$fecha."' AND z.profesional_id='".$profesionalTot[1]."' AND
        z.tipo_id_profesional='".$profesionalTot[0]."' AND z.tipo_consulta_id='".$_SESSION['BorrarAgenda']['Cita']."' AND z.sw_estado_cancelacion=0 AND
        x.sw_estado='0' AND (x.sw_cantidad_pacientes_asignados is null OR x.sw_cantidad_pacientes_asignados=0)";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
        return 1;
            }
        }
        return 0;
    }

    function BusquedaAgendasPantallaFinalDestino($DiaEspe,$Profesional){
    GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
    (list($tipoId,$Identificacion)=explode(',',$Profesional));
    $sql="
        SELECT a.*,d.agenda_cita_asignada_id, f.sw_estado, 
                g.paciente_id || ' - ' || g.tipo_id_paciente as identificacion, 
                g.primer_nombre || ' ' || g.segundo_nombre || ' ' || g.primer_apellido || ' ' || g.segundo_apellido as nombre_completo,
                d.agenda_cita_id_padre
        FROM                
                (               
                SELECT a.agenda_cita_id, a.hora, b.*, c.especialidad, 
                            prof.nombre as nomprofesional
                FROM agenda_citas as a,agenda_turnos as b
                JOIN profesionales prof ON(b.tipo_id_profesional=prof.tipo_id_tercero AND b.profesional_id=prof.tercero_id)
                JOIN tipos_consulta as c on (b.tipo_consulta_id=c.tipo_consulta_id)
                WHERE a.agenda_turno_id=b.agenda_turno_id AND date(b.fecha_turno)='$DiaEspe' AND b.tipo_id_profesional='$tipoId' AND b.profesional_id='$Identificacion' AND
                (a.sw_estado='0' OR a.sw_estado='1' OR a.sw_estado='2') AND b.tipo_consulta_id='".$_SESSION['BorrarAgenda']['Cita']."'              
                )as a
        LEFT JOIN agenda_citas_asignadas as d on(a.agenda_cita_id=d.agenda_cita_id AND agenda_cita_asignada_id NOT IN (SELECT agenda_cita_asignada_id FROM agenda_citas_asignadas_cancelacion))     
        LEFT JOIN pacientes as g on(d.tipo_id_paciente=g.tipo_id_paciente AND d.paciente_id=g.paciente_id)
        LEFT JOIN os_cruce_citas as e on (d.agenda_cita_asignada_id=e.agenda_cita_asignada_id)
        LEFT JOIN os_maestro as f on(e.numero_orden_id=f.numero_orden_id AND (f.sw_estado!=3 or f.sw_estado is null))       
        
        ORDER BY a.fecha_turno || ' ' || a.hora,identificacion;";
        
        
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $agenda_cita[$result->fields['agenda_turno_id']][$result->fields['agenda_cita_id']][$result->fields['agenda_cita_asignada_id']]=$result->GetRowAssoc(false);
                $result->MoveNext();
            }
        }
        return $agenda_cita;
    }


/**
* Esta funcion cambia el turno de una agenda de manera completa
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
*/

    function CambiarAgendaCompletaTotal()
    {

      if($_REQUEST['cancelarCitas']){
      if(sizeof($_REQUEST['selectUno'])<1){
        $this->frmError["MensajeError"]="Seleccione las Citas que Desea Cancelar.";
            }else{
              $intervalos=$_REQUEST['selectUno'];
              for($i=0;$i<sizeof($intervalos);$i++){
                  (list($agendaId,$TurnoId)=explode('/',$intervalos[$i]));
                    list($dbconn) = GetDBconn();
                    //$query="UPDATE agenda_citas SET sw_estado_cancelacion='1' WHERE agenda_cita_id='$TurnoId' AND agenda_turno_id='$agendaId'";
          $query="DELETE FROM agenda_citas WHERE agenda_cita_id='$TurnoId' AND agenda_turno_id='$agendaId'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
        $this->frmError["MensajeError"]="Citas Canceladas.";
            }
            $this->PantallaFinalCambioAgenda();
            return true;
        }

        if($_REQUEST['cancelarCitasAgendaDos']){
      if(sizeof($_REQUEST['selectUnoAgendaDos'])<1){
        $this->frmError["MensajeError"]="Seleccione las Citas que Desea Cancelar.";
            }else{
              $intervalos=$_REQUEST['selectUnoAgendaDos'];
              for($i=0;$i<sizeof($intervalos);$i++){
                  (list($agendaId,$TurnoId)=explode('/',$intervalos[$i]));
                    list($dbconn) = GetDBconn();
                    //$query="UPDATE agenda_citas SET sw_estado_cancelacion='1' WHERE agenda_cita_id='$TurnoId' AND agenda_turno_id='$agendaId'";
          $query="DELETE FROM agenda_citas WHERE agenda_cita_id='$TurnoId' AND agenda_turno_id='$agendaId'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
        $this->frmError["MensajeError"]="Citas Canceladas.";
            }
            $this->PantallaFinalCambioAgenda();
            return true;
        }
    /*pasar todas la citas directas*/
        if($_REQUEST['PasarCitas']){
      if(sizeof($_REQUEST['selectUnoPaso'])<1){
        $this->frmError["MensajeError"]="Seleccione las Citas que Desea Asignar.";
            }else{
              $intervalos=$_REQUEST['selectUnoPaso'];
              for($i=0;$i<sizeof($intervalos);$i++){
                  (list($citaAsignada,$TunoIdAnt,$AgendaIdAnt,$justificacion,$fecha,$profesional,$hora)=explode('||//',$intervalos[$i]));
                    (list($tipoIdProf,$IdProf,$NomProf)=explode(',',$profesional));
                    list($dbconn) = GetDBconn();
                    $dbconn->BeginTrans();
                    //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
          $sql="SELECT a.agenda_turno_id,b.agenda_cita_id
                    FROM agenda_turnos a,agenda_citas b
                    WHERE a.fecha_turno='$fecha' AND a.tipo_id_profesional='$tipoIdProf' AND a.profesional_id='$IdProf' AND a.sw_estado_cancelacion='0' AND
                    a.tipo_consulta_id='".$_SESSION['BorrarAgenda']['Cita']."' AND
                    a.agenda_turno_id=b.agenda_turno_id AND b.hora='$hora' AND b.sw_estado='0' AND
                    (b.sw_cantidad_pacientes_asignados=0 OR b.sw_cantidad_pacientes_asignados is null)";
                    $result=$dbconn->Execute($sql);
                    $dbconn->BeginTrans();
          $agendaid=$result->fields[0];
                    $turnoid=$result->fields[1];
                    $sql="UPDATE agenda_citas_asignadas SET agenda_cita_id=$turnoid,agenda_cita_id_padre=$turnoid WHERE agenda_cita_asignada_id='".$citaAsignada."';";
                    $result=$dbconn->Execute($sql);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                      //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
                        $sql="UPDATE agenda_citas SET sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados+1 WHERE agenda_cita_id=$turnoid AND agenda_turno_id=$agendaid";
                        $result=$dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }else{
                          $sql="UPDATE agenda_citas SET sw_estado='1'
                            WHERE sw_cantidad_pacientes_asignados=(SELECT cantidad_pacientes FROM agenda_turnos WHERE agenda_turno_id=$agendaid)
                            AND agenda_cita_id=$turnoid AND agenda_turno_id=$agendaid";
                            $result=$dbconn->Execute($sql);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }else{
                                $sql="UPDATE agenda_citas
                                SET sw_estado='3'
                                WHERE agenda_cita_id='".$TunoIdAnt."' AND agenda_turno_id='".$AgendaIdAnt."'";
                                $result=$dbconn->Execute($sql);
                                if($dbconn->ErrorNo() != 0){
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }else{
                                    $sql="INSERT INTO agenda_citas_canceladas(agenda_cita_id, agenda_tipo_justificacion_id)
                                    VALUES('".$TunoIdAnt."', '".$justificacion."');";
                                    $result=$dbconn->Execute($sql);
                                    if($dbconn->ErrorNo() != 0){
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                }
                            }
                        }
                    }
                }
                $dbconn->CommitTrans();
                $this->frmError["MensajeError"]="Citas Pasadas al Profesional Seleccionado";
            }
      $this->PantallaFinalCambioAgenda();
            return true;
        }
        /*fin de la asignacion de citas*/

        $b=explode(',',$_REQUEST['Profesional']);
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        foreach($_SESSION['BorrarAgenda']['DatosAgenda'] as $k=>$v)
        {
            $turno=0;
            foreach($v as $t=>$s)
            {
                $cita=0;
                foreach($s as $p=>$m)
                {
                    if($turno==0)
                    {
                        $sql="select nextval('public.agenda_turnos_seq');";
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        $id=$result->fields[0];
                        $sql="insert into agenda_turnos (agenda_turno_id, fecha_turno, duracion, tipo_registro, profesional_id,  tipo_consulta_id, cantidad_pacientes, usuario_id, fecha_registro, tipo_id_profesional, consultorio_id, empresa_id) values (".$id.", '".$_REQUEST['DiaEspe']."' ,".$m['duracion']." ,'".$m['tipo_registro']."' ,'".$b[1]."' ,'".$m['tipo_consulta_id']."' ,".$m['cantidad_pacientes']." ,".$_SESSION['SYSTEM_USUARIO_ID']." ,'".date("Y-m-d H:i:s")."' ,'".$b[0]."', '".$m['consultorio_id']."', '".$m['empresa_id']."');";
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $sql="update agenda_turnos set sw_estado_cancelacion=1 where agenda_turno_id=$k;";
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $sql="insert into agenda_turnos_cancelados (agenda_turno_id,agenda_tipo_justificacion_id) values (".$k.", ".$_REQUEST['justificacion'].");";
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $turno=1;
                    }
                    if($cita==0)
                    {
                        $sql="select nextval('agenda_citas_agenda_cita_id_seq');";
                        $result=$dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $agenda_cita_id=$result->fields[0];
                        $sql="insert into agenda_citas (agenda_cita_id, hora, agenda_turno_id) values (".$agenda_cita_id.", '".$m['hora']."',".$id.");";
                        $result=$dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        //Cambie el numero 1 al 3
                        $sql="update agenda_citas set sw_estado='3' where agenda_cita_id=".$t.";";
                        $result=$dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $sql="insert into agenda_citas_canceladas(agenda_cita_id, agenda_tipo_justificacion_id)values (".$t.", ".$_REQUEST['justificacion'].");";
                        $result=$dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0)
                        {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $cita=1;
                    }
                    if(!empty($p))
                    {
                        $sql="update agenda_citas_asignadas set agenda_cita_id=$agenda_cita_id where agenda_cita_asignada_id=$p;";
                        $result=$dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        //cambio el campo sw_estado x sw_cantidad_pacientes_asignados
                        $sql="update agenda_citas set sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados+1 where agenda_cita_id=$agenda_cita_id";
                        $result=$dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }else{
              $sql="UPDATE agenda_citas
                            SET sw_estado='1'
                            WHERE sw_cantidad_pacientes_asignados=(SELECT cantidad_pacientes FROM agenda_turnos WHERE agenda_turno_id='".$id."')
                            AND agenda_cita_id=$agenda_cita_id AND agenda_turno_id='".$id."'";
                            $result=$dbconn->Execute($sql);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                        }
                    }
                }
            }
        }
        if($this->ListadoAgendaMesTurnos()==false){
            return false;
        }
        //$dbconn->RollbackTrans();
        $dbconn->CommitTrans();
        return true;
    }


/**
* Esta funcion revisa que el cambiar la agenda sea posible
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
*/

    function CambiarAgendaTurno()
    {
        if(empty($_REQUEST['DiaEspe']))
        {
            if($this->CambiarAgendaDia('ESCOJE LA FECHA')==false)
            {
                return false;
            }
        }
        else
        {
            if($_REQUEST['DiaEspe']<date("Y-m-d"))
            {
                if($this->CambiarAgendaDia('FECHA ANTERIOR AL DÍA DE HOY')==false)
                {
                    return false;
                }
            }
            else
            {
                if(empty($_REQUEST['Cambiar']) or $_REQUEST['justificacion']==-1)
                {
                    if($_REQUEST['justificacion']==-1)
                    {
                        $this->frmError["justificacion"]=1;
                    }
                    if($this->CambiarAgendaDia()==false)
                    {
                        return false;
                    }
                }
                else
                {
                    if($this->CambioDeAgendaUnica()==false)
                    {
                        return false;
                    }
                    if($this->CambiarAgenda()==false)
                    {
                        return false;
                    }
                }
            }
        }
        return true;
    }

/**
* Esta funcion cambia una hora especifica de un turno a otro turno
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
*/


    function CambioDeAgendaUnica()
    {
        $a=explode(',',$_REQUEST['citas']);
        $b=explode(',',$_REQUEST['Profesional']);
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $sql="select nextval('public.agenda_turnos_seq');";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $id=$result->fields[0];
        $sql="insert into agenda_turnos (agenda_turno_id, fecha_turno, duracion, tipo_registro, profesional_id,  tipo_consulta_id, cantidad_pacientes, usuario_id, fecha_registro, tipo_id_profesional, consultorio_id, empresa_id) values (".$id.", '".$_REQUEST['DiaEspe']."' ,".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['duracion']." ,'".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['tipo_registro']."' ,'".$b[1]."' ,'".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['tipo_consulta_id']."' ,".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['cantidad_pacientes']." ,".$_SESSION['SYSTEM_USUARIO_ID']." ,'".date("Y-m-d H:i:s")."' ,'".$b[0]."', '".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['consultorio_id']."', '".$_SESSION['BorrarAgenda']['DatosCitas'][$a[0]][$a[1]]['empresa_id']."');";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $sql="select nextval('agenda_citas_agenda_cita_id_seq');";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $agenda_cita_id=$result->fields[0];
        $sql="insert into agenda_citas (agenda_cita_id, hora, agenda_turno_id) values (".$agenda_cita_id.", '".$_REQUEST['hora'].':'.$_REQUEST['minutos']."',".$id.");";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        foreach($_SESSION['BorrarAgenda']['DatosCitas1'] as $k=>$v)
        {
            $sql="update agenda_citas_asignadas set agenda_cita_id=$agenda_cita_id where agenda_cita_asignada_id=$k;";
            $result=$dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
      //cambia campo sw_estado x sw_cantidad_pacientes_asignados
            $sql="update agenda_citas set sw_cantidad_pacientes_asignados=sw_cantidad_pacientes_asignados+1 where agenda_cita_id=$agenda_cita_id";
            $result=$dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            $sql="UPDATE agenda_citas
                            SET sw_estado='1'
                            WHERE sw_cantidad_pacientes_asignados=(SELECT cantidad_pacientes FROM agenda_turnos WHERE agenda_turno_id='".$id."')
                            AND agenda_cita_id=$agenda_cita_id AND agenda_turno_id='".$id."'";
            $result=$dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        //Cambie el numero 1 al 3
        $sql="update agenda_citas set sw_estado='3' where agenda_cita_id=".$a[1].";";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        $sql="insert into agenda_citas_canceladas(agenda_cita_id, agenda_tipo_justificacion_id)values (".$a[1].", '".$_REQUEST['justificacion']."');";
        $result=$dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        //$dbconn->RollbackTrans();
        $dbconn->CommitTrans();
        return true;
    }

/**
* Esta funcion cambia una hora especifica de un turno a otro turno
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no fue asi
* @param int identificacion unica del turno
*/

    function BusquedaEspecialidad($turno)
    {
        list($dbconn) = GetDBconn();
        $sql="select b.especialidad from agenda_turnos as a, tipos_consulta as b where a.agenda_turno_id=$turno and a.tipo_consulta_id=b.tipo_consulta_id;";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result->fields[0];
    }



/**
* Esta funcion retorna un vector con los tipos de justificacion
*
* @access public
* @return array retorna un vector con la informacion de la justificacion
*/

    function BusquedaTipoJustificacion()
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql="select * from agenda_tipos_justificacion";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF)
        {
            $citas[$result->fields['agenda_tipo_justificacion_id']]=$result->GetRowAssoc(false);
            $result->MoveNext();
        }
        return $citas;
    }

/**
* Esta funcion retorna un vector con la informacion de la cita especifica
*
* @access public
* @return array retorna un vector con la informacion de la cita
* @param int identificacion unica de la cita
*/

    function BusquedaDatosTurno($cita)
    {
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql="select a.agenda_cita_asignada_id, a.paciente_id, a.tipo_id_paciente,a.tipo_cita, a.plan_id, a.cargo_cita, a.observacion, b.primer_nombre || ' ' || b.segundo_nombre || ' ' || b.primer_apellido || ' ' || b.segundo_apellido as nombre, c.numero_orden_id
        from agenda_citas_asignadas as a, pacientes as b,os_cruce_citas as c
        where a.agenda_cita_id=$cita and a.sw_atencion=0 and a.tipo_id_paciente=b.tipo_id_paciente and a.paciente_id=b.paciente_id and a.agenda_cita_asignada_id=c.agenda_cita_asignada_id;";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while (!$result->EOF){
            $citas[$result->fields['agenda_cita_asignada_id']]=$result->GetRowAssoc(false);
            $result->MoveNext();
        }
        return $citas;
    }


/**
* Esta funcion borra el turno si este no tiene ninguna cita
*
* @access public
* @return int informando si se borro la agenda o no
*/

    function BorrarAgendaTurno()
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $noborra=0;
        foreach($_REQUEST as $v=>$datos)
        {
            if(substr_count ($v,'seleccion')==1){
                $a=explode(",",$datos);
                if(sizeof($a)==1){
                  //cambio el campo de sw_estado x sw_cantidad_pacientes_asignados
                    $sql="select count(agenda_cita_id)
                    from agenda_citas
                    where agenda_turno_id=".$datos." and (sw_cantidad_pacientes_asignados > 0 AND sw_estado!='3');";
                }else{
                  //cambio el campo de sw_estado x sw_cantidad_pacientes_asignados
                    $sql="select count(agenda_cita_id)
                    from agenda_citas
                    where (sw_cantidad_pacientes_asignados > 0 AND sw_estado!='3') and (agenda_turno_id=".$a[0];
                    foreach($a as $v=>$datos1){
                        if(!empty($datos1) and $v!=0){
                            $sql.=" or agenda_turno_id=".$datos1;
                        }
                    }
                    $sql.=");";
                }
                $result = $dbconn->Execute($sql);
                $i=0;
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }else{
                    $saber=$result->fields[0];
                    if($saber==0){
                        if(sizeof($a)==1){
                            $sql="SELECT a.agenda_cita_id,(SELECT 1 FROM agenda_citas_asignadas b WHERE a.agenda_cita_id=b.agenda_cita_id),
                            (SELECT 1 FROM agenda_citas_canceladas c WHERE a.agenda_cita_id=c.agenda_cita_id)
                            FROM agenda_citas a
                            WHERE a.agenda_turno_id=".$datos.";";
              $result = $dbconn->Execute($sql);
              if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }else{
                while(!$result->EOF){
                  if($result->fields[1]==1 || $result->fields[2]==1){
                    $sql1.="UPDATE agenda_citas SET sw_estado='3' WHERE agenda_cita_id=".$result->fields[0].";";
                                        $noborra=1;
                                    }else{
                    $sql1.="DELETE FROM agenda_citas WHERE agenda_cita_id=".$result->fields[0].";";
                                    }
                                    $result->MoveNext();
                                }
                                $result1 = $dbconn->Execute($sql1);
                                if ($dbconn->ErrorNo() != 0){
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                            }
                        }else{

                            $sql="SELECT a.agenda_cita_id,(SELECT 1 FROM agenda_citas_asignadas b WHERE a.agenda_cita_id=b.agenda_cita_id),
                            (SELECT 1 FROM agenda_citas_canceladas c WHERE a.agenda_cita_id=c.agenda_cita_id)
                            FROM agenda_citas a
                            WHERE a.agenda_turno_id=".$a[0];
                            foreach($a as $v=>$datos1){
                                if(!empty($datos1) and $v!=0){
                                    $sql.=" OR agenda_turno_id=".$datos1;
                                }
                                $sql.=";";
                            }
              $result = $dbconn->Execute($sql);
              if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }else{
                while(!$result->EOF){
                  if($result->fields[1]==1  || $result->fields[2]==1){
                    $sql2.="UPDATE agenda_citas SET sw_estado='3' WHERE agenda_cita_id=".$result->fields[0].";";
                                        $noborra=1;
                                    }else{
                    $sql2.="DELETE FROM agenda_citas WHERE agenda_cita_id=".$result->fields[0].";";
                                    }
                                    $result->MoveNext();
                                }
                                $result1 = $dbconn->Execute($sql2);
                                if ($dbconn->ErrorNo() != 0){
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                            }
                        }
                        if($noborra!=1){
                            if(sizeof($a)==1){
                                $sql="delete from agenda_turnos where agenda_turno_id=".$datos.";";
                            }else{
                                $sql="delete from agenda_turnos where agenda_turno_id=".$a[0];
                                foreach($a as $v=>$datos1){
                                    if(!empty($datos1) and $v!=0){
                                        $sql.=" or agenda_turno_id=".$datos1;
                                    }
                                }
                                $sql.=";";
                            }
                        }else{
              if(sizeof($a)==1){
                                $sql="UPDATE agenda_turnos SET sw_estado_cancelacion='1' WHERE agenda_turno_id=".$datos.";";
                            }else{
                                $sql="UPDATE agenda_turnos SET sw_estado_cancelacion='1' WHERE agenda_turno_id=".$a[0];
                                foreach($a as $v=>$datos1){
                                    if(!empty($datos1) and $v!=0){
                                        $sql.=" or agenda_turno_id=".$datos1;
                                    }
                                }
                                $sql.=";";
                            }
                        }
                        $result = $dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        unset($_REQUEST[$v]);
                    }else{
                        break;
                    }
                }
            }
        }
        $dbconn->CommitTrans();
        return $saber;
    }



/**
* Esta funcion borra las citas de un turno
*
* @access public
* @return int informando si se borro la cita o no
*/



    function BorrarAgendaCita()
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        foreach($_REQUEST as $v=>$datos)
        {
            if(substr_count ($v,'seleccion')==1){
                $a=explode(",",$datos);
                $sql="select count(*) from agenda_citas where agenda_turno_id='".$a[1]."';";
                $result = $dbconn->Execute($sql);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }else{
                    $borra=1;
                    if($result->fields[0]==1){
                        $borra=2;
                    }
                }
        //cambio el campo de sw_estado x sw_cantidad_pacientes_asignados
                //Verifica que no hayan pacientes asignados
                $sql="select count(*) from agenda_citas where sw_cantidad_pacientes_asignados > 0 and agenda_cita_id=".$a[0].";";
                $result = $dbconn->Execute($sql);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }else{
                    $borra1=1;
                    if($result->fields[0]>0){
                        $borra1=2;
                    }
                }
                if($borra1==1){
                    $sql="delete from agenda_citas where agenda_cita_id=".$a[0].";";
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                        if($borra==2){
                            $sql="delete from agenda_turnos where agenda_turno_id=".$a[1];
                            $result = $dbconn->Execute($sql);
                            if ($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                        }
                        unset($_REQUEST[$v]);
                    }
                }/*elseif($borra1==2){
          $this->ListadoDiaAgenda();
                    $this->frmError["MensajeError"]="En la Seleccion Existe una Cita Asignada y no es posible Eliminarla.";
                    return true;
                }*/
            }
        }
        $dbconn->CommitTrans();
        return $borra;
    }



/**
* Esta funcion permite cancelar una cita de una agenda
*
* @access public
* @return int informando si se cancelo la cita o no
*/

    function CancelarAgendaCita()
    {
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        foreach($_REQUEST as $v=>$datos){
            if(substr_count ($v,'seleccion')==1){
                $a=explode(",",$datos);
                $sql="select count(*) from agenda_citas where agenda_turno_id='".$a[1]."';";
                $result = $dbconn->Execute($sql);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }else{
                    $borra=1;
                    if($result->fields[0]==1){
                        $borra=2;
                    }
                }
                //Cancela el turno en caso de que haya un acita asignada pero el
                //registro en la asignacion de la cita que da igual
                if($borra==1 || $borra==2){
                  //Cambie el numero 1 al 3
                    $sql="update agenda_citas set sw_estado='3' where agenda_cita_id=".$a[0].";";
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                      //Cancela los padres y los hijos
            $sql="SELECT b.agenda_cita_id
                        FROM (SELECT a.agenda_cita_id_padre
                        FROM agenda_citas_asignadas a
                        WHERE a.agenda_cita_id=".$a[0].") as a,agenda_citas_asignadas b
                        WHERE a.agenda_cita_id_padre=b.agenda_cita_id_padre AND b.agenda_cita_id != ".$a[0]."";
                        $result = $dbconn->Execute($sql);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }else{
              $datos=$result->RecordCount();
                            if($datos){
                while(!$result->EOF){
                  $sql="update agenda_citas set sw_estado='3' where agenda_cita_id=".$result->fields[0].";";
                                    $result1 = $dbconn->Execute($sql);
                                    if($dbconn->ErrorNo() != 0){
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $dbconn->RollbackTrans();
                                        return false;
                                    }
                                    $result->MoveNext();
                                }
                            }
                        }
                    }
                }
                if($borra==2){
                    $sql="update agenda_turnos set sw_estado_cancelacion='1' where agenda_turno_id=".$a[1];
                    $result = $dbconn->Execute($sql);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                    unset($_REQUEST[$v]);
                }
            }
        }
        $dbconn->CommitTrans();
        return $borra;
    }



/**
* Esta funcion retorna un vector identificando los turnos programados
*
* @access public
* @return array retorna un vector con la informacion de los turnos programados
*/

    function TurnosProgramados()
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['saber']==1)
        {
            $sql="select a.fecha_turno,
            (select min(hora)
            from agenda_citas as b
            where b.agenda_turno_id=a.agenda_turno_id) as hora_min,
            (select max(hora)
            from agenda_citas as b
            where b.agenda_turno_id=a.agenda_turno_id) as hora_max,
            a.duracion
            from agenda_turnos as a
            where profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and date(fecha_turno)>=date(now()) order by a.fecha_turno, hora_min;";
        }
        else
        {
            $sql="select count(a.fecha_turno)
            from agenda_turnos as a
            where profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and date(fecha_turno)>=date(now());";
        }
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            echo "hola";
            return false;
        }
        else
        {
            if($_REQUEST['saber']==1)
            {
                while (!$result->EOF)
                {
                    $a=explode("-",$result->fields[0]);
                    $turnos[0][$i]=$a[0];
                    $turnos[1][$i]=$a[0].'-'.$a[1];
                    $turnos[2][$i]=$result->fields[0];
                    $turnos[3][$i]=$result->fields[1];
                    $turnos[4][$i]=$result->fields[2];
                    $turnos[5][$i]=$result->fields[3];
                    $i++;
                    $result->MoveNext();
                }
            }
            else
            {
                $i=1;
                $turnos=$result->fields[0];
            }
        }
        if($i<>0)
        {
            return $turnos;
        }
        else
        {
            return false;
        }
    }

    /**
* Esta funcion retorna un vector identificando los turnos programados
*
* @access public
* @return array retorna un vector con la informacion de los turnos programados
*/

    function TurnosProgramadosProfesional($tipoIdProf,$IdProfesional,$bandera){
        list($dbconn) = GetDBconn();
        if($bandera==1){
          $sql="SELECT a.fecha_turno,1,1,a.agenda_turno_id
            FROM agenda_turnos as a
            WHERE profesional_id='".$IdProfesional."' and tipo_id_profesional='".$tipoIdProf."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and sw_estado_cancelacion=0 and date(fecha_turno)>=date(now())
            ORDER BY a.fecha_turno";
            //$sql="select a.fecha_turno, (select min(hora) from agenda_citas as b where b.agenda_turno_id=a.agenda_turno_id) as hora_min, (select max(hora) from agenda_citas as b where b.agenda_turno_id=a.agenda_turno_id) as hora_max from agenda_turnos as a where profesional_id='".$IdProfesional."' and tipo_id_profesional='".$tipoIdProf."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and date(fecha_turno)>=date(now()) order by a.fecha_turno, hora_min;";
        }else{
            $sql="select count(a.fecha_turno) from agenda_turnos as a where profesional_id='".$IdProfesional."' and tipo_id_profesional='".$tipoIdProf."' and tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." and date(fecha_turno)>=date(now());";
        }
        $result = $dbconn->Execute($sql);
        $i=0;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($bandera==1){
                while(!$result->EOF){
                    $a=explode("-",$result->fields[0]);
                    $turnos[0][$i]=$a[0];
                    $turnos[1][$i]=$a[0].'-'.$a[1];
                    $turnos[2][$i]=$result->fields[0];
                    $turnos[3][$i]=$result->fields[1];
                    $turnos[4][$i]=$result->fields[2];
                    $turnos[5][$i]=$result->fields[3];
                    $i++;
                    $result->MoveNext();
                }
            }else{
                $i=1;
                $turnos=$result->fields[0];
            }
        }
        if($i<>0){
            return $turnos;
        }else{
            return false;
        }
    }



/**
* Esta funcion retorna un vector con los dias festivos
*
* @access public
* @return array retorna un vector con los dias festivos
*/

    function Festivos($a)
    {
        list($dbconn) = GetDBconn();
        $sql="select dia from dias_festivos where extract(year from dia)=".$a.";";
        $result = $dbconn->Execute($sql);
        if ($dbconn->ErrorNo() != 0)
        {
            echo "hola";
            return false;
        }
        else
        {
            $i=0;
            while (!$result->EOF)
            {
                $festivos[$i]=$result->fields[0];
                $i++;
                $result->MoveNext();
            }
        }
        return $festivos;
    }





/**
* Esta funcion retorna la informacion del intervalo e inserta en un vector la informacion de las citas
*
* @access public
* @return int retorna el intervalo
*/


    function CitasDias($todo)
    {
        $a=explode("-",$_REQUEST['DiaEspe']);
        $b=explode(" ",$a[2]);
        $c=explode(":",$b[1]);
        $i=$b[0];
        $c[1]=$_REQUEST['iniminutos'];
        while($i==date("d",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])))
        {
            $j=array_keys($_SESSION['FECHAS'],date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0])));
            $s=$j[0];
            if($s!='' or $s===0)
            {
                break;
            }
            else
            {
                $c[1]=$c[1]+$_REQUEST['interval'];
            }
        }
        $i=$j[0];
        $a=explode("-",$_REQUEST['DiaEspe']);
        $b=explode(" ",$a[2]);
        $c=explode(":",$b[1]);
        $cont=0;
        while($i<sizeof($_SESSION['FECHAS']))
        {
            if($_SESSION['FECHAS'][$i]>=date("Y-m-d H:i",mktime($c[0]+6,59,0,$a[1],$b[0],$a[0])))
            {
                break;
            }
            else
            {
                $dias[$cont]=$_SESSION['FECHAS'][$i];
                $cont++;
            }
            $i++;
        }
        $a=explode("-",$_REQUEST['DiaEspe']);
        $b=explode(" ",$a[2]);
        $c=explode(":",$b[1]);
        $c[1]=$_REQUEST['iniminutos'];
        $d=($c[1]/5);
        if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
        {
            $dia=date("Y-m-d H:i",mktime($c[0],0,0,$a[1],$b[0],$a[0]));
            $s=$_REQUEST['interval'];
        }
        else
        {
                $dia=date("Y-m-d H:i",mktime($c[0],5,0,$a[1],$b[0],$a[0]));
                $s=$_REQUEST['interval']+5;
        }
        $cont=0;
        $p=0;
        while($dia<=date("Y-m-d H:i",mktime($c[0]+6,59,0,$a[1],$b[0],$a[0])))
        {
            if($dia==$dias[$cont])
            {
                $cont++;
            }
            else
            {
                $todo[$p]=$dia;
                $p++;
            }
            $a1=explode("-",$_REQUEST['DiaEspe']);
            $b1=explode(" ",$a1[2]);
            $dia=date("Y-m-d H:i",mktime($c[0],$s,0,$a1[1],$b1[0],$a1[0]));
            $s=$s+$_REQUEST['interval'];
        }
        //print_r($todo);
        if($c[0]==0)
        {
            return 1;
        }
        elseif($c[0]==6)
        {
            return 2;
        }
        elseif($c[0]==12)
        {
            return 3;
        }
        elseif($c[0]==18)
        {
            return 4;
        }
    }





/**
* Esta funcion retorna un vector con los consultorios
* funcion modificada por DUVAN para el problema de los consultorios.
*
* @access public
* @return array retorna un vector con los consultorios
*/


    function Consultorio()
    {
        list($dbconn) = GetDBconn();
      $sql="select b.consultorio, b.consultorio from tipos_consulta_consultorios as a,consultorios b
        where a.tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']."
        AND b.consultorio_id=a.consultorio_id ORDER BY b.consultorio ASC;";
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            echo "Fallo al traer los consultorios";
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $consultorio[0][$i]=$result->fields[0];
                $consultorio[1][$i]=$result->fields[1];
                $result->MoveNext();
                $i++;
            }
        }
        if($i<>0)
        {
            return $consultorio;
        }
        else
        {
            return false;
        }
    }




/**
* Esta funcion retorna un vector con los tipos de registro
*
* @access public
* @return array retorna un vector con los tipos de registro
*/


    function TipoRegistro()
    {
        list($dbconn) = GetDBconn();
        $sql="select tipo_registro, descripcion from tipos_registro;";
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            echo "Fallo al traer los tipos de registro";
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $tipocita[0][$i]=$result->fields[0];
                $tipocita[1][$i]=$result->fields[1];
                $i++;
                $result->MoveNext();
            }
        }
        if($i<>0)
        {
            return $tipocita;
        }
        else
        {
            return false;
        }
    }





/**
* Esta funcion retorna un vector con los diferentes intervalos de una cita
*
* @access public
* @return array retorna un vector con los diferentes intervalos
*/
    function Intervalo()
    {
        list($dbconn) = GetDBconn();
        $sql="select duracion from agenda_medica_tipo_intervalo order by duracion;";
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            echo "hola";
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $intervalo[$i]=$result->fields[0];
                $i++;
                $result->MoveNext();
            }
        }
        if($i<>0)
        {
            return $intervalo;
        }
        else
        {
            return false;
        }
    }





/**
* Esta funcion retorna un vector con la cantidad de pacientes que se puede tener en una cita
*
* @access public
* @return array retorna un vector con la cantidad de pacientes
*/
    function Pacientes()
    {
        list($dbconn) = GetDBconn();
        $sql="select cantidad from agenda_medica_tipo_cantidad_pacientes;";
        $result = $dbconn->Execute($sql);
        $i=0;
        if ($dbconn->ErrorNo() != 0)
        {
            echo "hola";
            return false;
        }
        else
        {
            while (!$result->EOF)
            {
                $pacientes[$i]=$result->fields[0];
                $i++;
                $result->MoveNext();
            }
        }
        if($i<>0)
        {
            return $pacientes;
        }
        else
        {
            return false;
        }
    }






/**
* Esta funcion crea las agendas con sus turnos especificos segun lo solicite el usuario del sistema
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/



    function GuardarDatos()
    {

        if(sizeof($_REQUEST['seleccion'])>0){
            $vectorTot=$_REQUEST['seleccion'];
            for($i=0;$i<sizeof($vectorTot);$i++){
                $valores=explode('||//',$vectorTot[$i]);
                for($j=0;$j<sizeof($valores);$j++){
                    $vectorVerificar[]=$valores[$j];
                }
            }
            list($dbconn) = GetDBconn();
            $i=0;
            $s=0;
            $dbconn->BeginTrans();
            while($i<sizeof($_SESSION['FECHAS']))
            {
                if(!empty($_SESSION['FECHAS'][$i]) && in_array($_SESSION['FECHAS'][$i],$vectorVerificar))
                {
                    $a=explode("-",$_SESSION['FECHAS'][$i]);
                    $b=explode(" ",$a[2]);
                    $c=explode(":",$b[1]);
                    $fechaac=date("Y-m-d",mktime(0,0,0,$a[1],$b[0],$a[0]));
                    if($fechaac!=$fecha)
                    {
                        $sql="select
                        case when count(*)=0 then 0
                        when count(*)!=0 then 1 end
                        from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fechaac."') and a.profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and a.tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id and a.sw_estado_cancelacion='0' and (b.sw_estado='0' OR b.sw_estado='1' OR b.sw_estado='2')) as a
                        where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')<=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI');";
                        //echo '<br>';
                        /*$sql1="select * from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fecha."') and a.profesional_id='".$_REQUEST['tercero']."' and a.tipo_id_profesional='".$_REQUEST['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id) as a where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".$_SESSION['FECHAS'][$t]."','YYYY-MM-DD HH24:MI') and to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')&lt;=to_timestamp('".$_SESSION['FECHAS'][$i-1]."','YYYY-MM-DD HH24:MI');";
                        echo $sql;*/
                        $result=$dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() !=0)
                        {
                            $dbconn->RollbackTrans();
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        if($result->fields[0]==='1')
                        {
                        }
                        else
                        {
                            $a=explode("-",$_SESSION['FECHAS'][$i]);
                            $b=explode(" ",$a[2]);
                            $c=explode(":",$b[1]);
                            $fecha=date("Y-m-d",mktime(0,0,0,$a[1],$b[0],$a[0]));
                            $t=$i;
                            $sql="select nextval('public.agenda_turnos_seq');";
                            $result=$dbconn->Execute($sql);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $dbconn->RollbackTrans();
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            $id=$result->fields[0];
                            $sql="insert into agenda_turnos (agenda_turno_id, fecha_turno, duracion, tipo_registro, profesional_id,  tipo_consulta_id, cantidad_pacientes, usuario_id, fecha_registro, tipo_id_profesional, consultorio_id, empresa_id) values (".$id.", '".$fecha."' ,".$_REQUEST['interval']." ,'".$_REQUEST['tiporegistro']."' ,'".$_SESSION['CreacionAgenda']['tercero']."' ,'".$_SESSION['CreacionAgenda']['Cita']."' ,".$_REQUEST['pacientes']." ,".$_SESSION['SYSTEM_USUARIO_ID']." ,'".date("Y-m-d H:i:s")."' ,'".$_SESSION['CreacionAgenda']['tipoid']."','".$_REQUEST['consultorio']."','".$_SESSION['CreacionAgenda']['empresa']."');";
                            //echo '<br>';
                            $result=$dbconn->Execute($sql);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                            else
                            {
                                $sql2="insert into agenda_citas (hora, agenda_turno_id) values ('".date("H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."',".$id.");";
                                $result=$dbconn->Execute($sql2);
                                if ($dbconn->ErrorNo() != 0)
                                {
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $dbconn->RollbackTrans();
                                    return false;
                                }
                            }
                        }
                    }
                    else
                    {
                      $sql="select
                        case when count(*)=0 then 0
                        when count(*)!=0 then 1 end
                        from (select a.fecha_turno || ' ' || b.hora as fecha_turnos from agenda_turnos as a, agenda_citas as b where date(a.fecha_turno)=date('".$fechaac."') and a.profesional_id='".$_SESSION['CreacionAgenda']['tercero']."' and a.tipo_id_profesional='".$_SESSION['CreacionAgenda']['tipoid']."' and a.agenda_turno_id=b.agenda_turno_id and a.sw_estado_cancelacion='0' and (b.sw_estado='0' OR b.sw_estado='1' OR b.sw_estado='2')) as a
                        where to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')>=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI') and
                        to_timestamp(a.fecha_turnos, 'YYYY-MM-DD HH24:MI')<=to_timestamp('".date("Y-m-d H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."','YYYY-MM-DD HH24:MI');";
                        $result=$dbconn->Execute($sql);
                        if ($dbconn->ErrorNo() !=0)
                        {
                            $dbconn->RollbackTrans();
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                        }
                        if($result->fields[0]==='1')
                        {
                        }
                        else
                        {
                            $sql="insert into agenda_citas (hora, agenda_turno_id) values ('".date("H:i",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))."',".$id.");";
                            //echo '<br>';
                            $result=$dbconn->Execute($sql);
                            if ($dbconn->ErrorNo() != 0)
                            {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $dbconn->RollbackTrans();
                                return false;
                            }
                        }
                    }
                }
                $i++;
            }
            $dbconn->CommitTrans();
            SessionDelVar('FECHAS');
            SessionDelVar('CITASMES');
            SessionDelVar('CITASDIA');
            SessionDelVar('ini');
            SessionDelVar('fin');
            SessionDelVar('dias');
            SessionDelVar('mes');
            SessionDelVar('semana');
            $mensaje='Turnos de la Agenda Medica Creados';
            $titulo='AGENDA MEDICA';
            $accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }else{
          $this->frmError["MensajeError"]="Debe Seleccionar los turnos para Guardar";
      $this->ConfirmarDatosAgenda();
            return true;
        }
        /*$_REQUEST['accion']='';
        $i=0;
        $t=0;
        while($i<13)
        {
            $dato=$i."mes";
            if(!empty($_REQUEST[$dato]))
            {
                $t++;
                if($t==1)
                {
                    break;
                }
            }
            $i++;
        }
        if($t>=1)
        {
            foreach($_REQUEST as $v=>$v1)
            {
                if($v!='1mes' and $v!='2mes' and $v!='3mes' and $v!='4mes' and $v!='5mes' and $v!='6mes' and $v!='7mes' and $v!='8mes' and $v!='9mes' and $v!='10mes' and $v!='11mes' and $v!='12mes' and $v!='guardar' and $v!='mes1' and $v!='mes2' and $v!='mes3' and $v!='mes4' and $v!='mes5' and $v!='mes6' and $v!='mes7' and $v!='mes8' and $v!='mes9' and $v!='mes10' and $v!='mes11' and $v!='mes12' and $v!='DiaEspe' and $v!='metodo')
                {
                    $_REQUEST[$v]=$v1;
                }
                else
                {
                    if($v!='guardar' and $v!='DiaEspe' and $v!='mes1' and $v!='mes2' and $v!='mes3' and $v!='mes4' and $v!='mes5' and $v!='mes6' and $v!='mes7' and $v!='mes8' and $v!='mes9' and $v!='mes10' and $v!='mes11' and $v!='mes12')
                    {
                        $dato='mes'.$v1;
                        $_REQUEST[$dato]=$v1;
                        $_REQUEST['accion']='add';
                        $_REQUEST['Enviar']='Enviar';
                    }
                    else
                    {
                        unset($_REQUEST[$v]);
                    }
                }
            }
        }*/
    }

/**
* Esta funcion redirecciona el proceso de borrado de la agenda
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/

    function BorrarAgenda()
    {
        $this->BorrarAgendaTurno();
        $this->ListadoAgendaMesTurnos();
        return true;
    }

/**
* Esta funcion redirecciona el proceso de borrado de la cita
*
* @access public
* @return boolean retorna verdadero si el proceso se realizo con exito y falso si no
*/

    function BorrarAgendaDia()
    {
        if(!empty($_REQUEST['Borrar']))
        {
            if($this->BorrarAgendaCita()==2)
            {
                $this->ListadoAgendaMesTurnos();
                return true;
            }
        }
        else
        {
            if($this->CancelarAgendaCita()==2)
            {
                $this->ListadoAgendaMesTurnos();
                return true;
            }
        }
        $this->ListadoDiaAgenda();
        return true;
    }

    /*
    * Cambiamos el formato timestamp a un formato de fecha legible para el usuario
    */

    function FormateoFechaMes($fecha)
    {
    if(!empty($fecha))
    {
        $f=explode(".",$fecha);
        $fecha_arreglo=explode(" ",$f[0]);
        $fecha_real=explode("-",$fecha_arreglo[0]);
        return ucwords(strftime("%B",strtotime($fecha_arreglo[0])));
    }
    else
    {
      return "-----";
    }
        return true;
    }

  function FormateoFechaDia($fecha)
    {
    if(!empty($fecha))
    {
        $f=explode(".",$fecha);
        $fecha_arreglo=explode(" ",$f[0]);
        $fecha_real=explode("-",$fecha_arreglo[0]);
        return ucwords(strftime("%A - %d",strtotime($fecha_arreglo[0])));
    }
    else
    {
      return "-----";
    }
        return true;
    }

    function LlamaAgendaConsultaTurnos(){
      SessionDelVar('CITASMES');
    $this->AgendaConsultaTurnos($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep'],$_REQUEST['saber']);
        return true;
    }

    function LlamaCreacionAgendaNueva(){
      $_SESSION['CreacionAgenda']['tercero']=$_REQUEST['tercero'];
        $_SESSION['CreacionAgenda']['tipoid']=$_REQUEST['tipoid'];
        $_SESSION['CreacionAgenda']['nombrep']=$_REQUEST['nombrep'];
    $this->CreacionAgendaNueva($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep']);
        return true;
    }

    function VerficarDatosAgenda(){
      //MODI INI
        unset($_SESSION['FECHAS']);
    $this->Asignar();
    //MODI FIN
        if(empty($_REQUEST['DiaEspe'])){
            $i=0;
            $t=0;
            while($i<13){
                $mes='mes'.$i;
                if(!empty($_REQUEST[$mes])){
                    $t++;
                }
                $i++;
            }
            //$t indica si cuanto meses selecciono el usuario
            //if($t<2){
              //Valida si no ha seleccionado ningun dato y saca error si es asi
                if(!empty($_REQUEST['interval']) and $_REQUEST['inihora']!='' and $_REQUEST['finhora']!='' and $_REQUEST['iniminutos']!='' and $_REQUEST['finminutos']!='' and $_REQUEST['pacientes']!=''){
                    if(empty($_SESSION['FECHAS'])){
                        if($_REQUEST['a']>0){
                            $a=date("Y",mktime(0,0,0,1,1,(date("Y")+$_REQUEST['a'])));
                            $i=0;
                        }else{
                            $a=date("Y");
                            $_REQUEST['a']=0;
                            $i=date("n");
                            if($i==12){
                                $s=date("j");
                                if($s==31){
                                    $s=0;
                                    $a++;
                                    $i=0;
                                }
                            }
                        }
                        $r=1;
                        $t=0;
                        while($r<13){
                            $mes='mes'.$r;
                            if(!empty($_REQUEST[$mes])){
                                $i=1;
                                while($i<32){
                                    $dias='dias'.$i;
                                    if(!empty($_REQUEST[$dias])){
                                        if($_REQUEST[$mes]==date("m",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))){
                                            if(empty($_REQUEST['nosabados']) and empty($_REQUEST['nodomingos'])){
                                                $fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
                                                $t++;
                                            }elseif(!empty($_REQUEST['nosabados']) and !empty($_REQUEST['nodomingos'])){
                                                if(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='dom' and strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='sáb'){
                                                    $fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
                                                    $t++;
                                                }
                                            }elseif(!empty($_REQUEST['nosabados'])){
                                                if(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='sáb'){
                                                    $fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
                                                    $t++;
                                                }
                                            }elseif(!empty($_REQUEST['nodomingos'])){
                                                if(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a))!='dom'){
                                                    $fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$_REQUEST[$dias],$a));
                                                    $t++;
                                                }
                                            }
                                        }
                                    }
                                    $i++;
                                }
                                $i=0;
                                while($i<7){
                                    $semana='semana'.$i;
                                    if(!empty($_REQUEST[$semana])){
                                        $s=1;
                                        while($s<32){
                                            if(strcasecmp($_REQUEST[$semana],chop(strftime("%a",mktime(0,0,0,$_REQUEST[$mes],$s,$a))))==0){
                                                if($_REQUEST[$mes]==date("m",mktime(0,0,0,$_REQUEST[$mes],$s,$a))){
                                                    $k=0;
                                                    $m=$t;
                                                    while($k<$m){
                                                        if(strcasecmp($fechas[$k],date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$s,$a)))==0){
                                                            break;
                                                        }
                                                        $k++;
                                                    }
                                                    if($k==$m){
                                                        $fechas[$t]=date("Y-m-d",mktime(0,0,0,$_REQUEST[$mes],$s,$a));
                                                        $t++;
                                                    }
                                                }
                                            }
                                            $s++;
                                        }
                                    }
                                    $i++;
                                }
                            }
                            $r++;
                        }
                        if(sizeof($fechas)==0){
                            //INICIO MODI
                            $this->Asignar();
                            $this->frmError["MensajeError"]="No se eligio ninguna fecha para realizar agenda.";
                            $this->CreacionAgendaNueva($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep']);
                  return true;
                            //FIN MODI
                            /*$this->salida.='<table align="center" class="modulo_table_list">';
                            $this->salida.='<tr>';
                            $this->salida.='<td align="center">';
                            $this->salida.='No se eligio ninguna fecha para realizar agenda.';
                            $this->salida.='</td>';
                            $this->salida.='</tr>';
                            $this->salida.='<tr>';
                            $this->salida.='<td align="center">';
                            $accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
                            $this->salida.='<form method="post" action="">';
                            $this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
                            $this->salida.='</form>';
                            $this->salida.='</td>';
                            $this->salida.='</tr>';
                            $this->salida.='</table>';
                            */
                        }else{
                            if(!empty($_REQUEST['nofestivos'])){
                                $festivos=$this->Festivos($a);
                                $i=0;
                                while($i<sizeof($festivos)){
                                    $j=array_keys($fechas,$festivos[$i]);
                                    $t=$j[0];
                                    if(!empty($t) or $t===0){
                                        $fechas[$t]='';
                                    }
                                    $i++;
                                }
                            }
                            if($_REQUEST['inihora']<=$_REQUEST['finhora']){
                                $horainic=($_REQUEST['inihora']*60)+$_REQUEST['iniminutos'];
                                $horafina=($_REQUEST['finhora']*60)+$_REQUEST['finminutos'];
                                if($horainic<$horafina AND ($horainic+$_REQUEST['interval'])<=$horafina)
                                /*(($_REQUEST['iniminutos']<=$_REQUEST['finminutos'] AND $_REQUEST['inihora']<=$_REQUEST['finhora'])
                                OR ($_REQUEST['inihora']==$_REQUEST['finhora'] AND $_REQUEST['iniminutos']<$_REQUEST['finminutos'])
                                OR ($_REQUEST['inihora']<$_REQUEST['finhora'] AND $_REQUEST['iniminutos']>$_REQUEST['finminutos']))*/
                                {
                                    $i=0;
                                    $r=0;
                                    $s=0;
                                    while($i<sizeof($fechas)){
                                        if(!empty($fechas[$i])){
                                            $a=explode("-",$fechas[$i]);
                                            $fechastotal[$r]=date("Y-m-d H:i",mktime($_REQUEST['inihora'],$_REQUEST['iniminutos'],0,$a[1],$a[2],$a[0]));
                                            $r++;
                                            $s=$_REQUEST['iniminutos'];
                                            $s=$s+$_REQUEST['interval'];
                                            $k=0;
                                            while(date("m-d H:i",mktime($_REQUEST['inihora'],$s,0,$a[1],$a[2],$a[0]))<=date("m-d H:i",mktime($_REQUEST['finhora'],$_REQUEST['finminutos'],0,$a[1],$a[2],$a[0]))){
                                                $fechastotal[$r]=date("Y-m-d H:i",mktime($_REQUEST['inihora'],$s,0,$a[1],$a[2],$a[0]));
                                                $s=$s+$_REQUEST['interval'];
                                                $r++;
                                            }
                                        }
                                        $i++;
                                    }
                                    array_multisort($fechastotal);
                                    $i=0;
                                    $mes=1;
                                    $dia=1;
                                    $l=0;
                                    $hora[]=0;
                                    $hora[]=0;
                                    $hora[]=0;
                                    $hora[]=0;
                                    while($i<sizeof($fechastotal)){
                                        $a=explode("-",$fechastotal[$i]);
                                        $b=explode(" ",$a[2]);
                                        if($mes==date("m",mktime(0,0,0,$a[1],$b[0],$a[0]))){
                                            if($dia==date("j",mktime(0,0,0,$a[1],$b[0],$a[0]))){
                                                $c=explode(":",$b[1]);
                                                if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=0 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<6 and $hora[0]==0){
                                                    $citasmes[$l]=$fechastotal[$i];
                                                    $l++;
                                                    $hora[0]=1;
                                                }
                                                if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=6 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<12 and $hora[1]==0){
                                                    $citasmes[$l]=$fechastotal[$i];
                                                    $l++;
                                                    $hora[1]=1;
                                                }
                                                if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=12 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<18 and $hora[2]==0){
                                                    $citasmes[$l]=$fechastotal[$i];
                                                    $l++;
                                                    $hora[2]=1;
                                                }
                                                if(date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))>=18 and date("G",mktime($c[0],$c[1],0,$a[1],$b[0],$a[0]))<23 and $hora[3]==0){
                                                    $citasmes[$l]=$fechastotal[$i];
                                                    $l++;
                                                    $hora[3]=1;
                                                }
                                                $i++;
                                            }else{
                                                $dia++;
                                                $hora[0]=$hora[1]=$hora[2]=$hora[3]=0;
                                            }
                                        }else{
                                            $mes++;
                                            $dia=1;
                                            $hora[0]=$hora[1]=$hora[2]=$hora[3]=0;
                                        }
                                    }
                                    SessionSetVar('FECHAS',$fechastotal);
                                    SessionSetVar('CITASMES',$citasmes);
                                    //$this->AgendaHtml();
                                    //echo '444';
                $this->ConfirmarDatosAgenda();
                                    return true;
                                }else{
                                    /*$this->salida.='<script>';
                                    $this->salida.='function vol(){'."\n";
                                    $this->salida.='window.history.back();'."\n";
                                    $this->salida.='}'."\n";
                                    $this->salida.='</script>';*/
                                    //MODI INICIO
                                 $this->Asignar();
                                //MODI FIN
                                  $this->frmError["MensajeError"]="Los minutos finales deben ser mayores a los iniciales.";
                                    $this->CreacionAgendaNueva($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep']);
                                  return true;
                                    /*$this->salida.='<table align="center" class="modulo_table_list">';
                                    $this->salida.='<tr>';
                                    $this->salida.='<td align="center">';
                                    $this->salida.='La hora inicial es igual a la final y los minutos finales son mayores a los iniciales.';
                                    $this->salida.='</td>';
                                    $this->salida.='</tr>';
                                    $this->salida.='<tr>';
                                    $this->salida.='<td align="center">';
                                    $accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
                                    $this->salida.='<form method="post" action="">';//onclick="vol()"
                                    $this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
                                    $this->salida.='</form>';
                                    $this->salida.='</td>';
                                    $this->salida.='</tr>';
                                    $this->salida.='</table>';
                                    */
                                }
                            }else{
//                                                      $this->salida.='<script>';
//                                                      $this->salida.='function vol(){';
//                                                      $this->salida.='window.history.go(-2);';
//                                                      $this->salida.='}';
//                                                      $this->salida.='</script>';
                                                        //MODI INICIO
                                $this->Asignar();
                                //MODI FIN
                                $this->frmError["MensajeError"]="La hora inicial no puede ser menor a la final.";
                                $this->CreacionAgendaNueva($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep']);
                                return true;
                                /*$this->salida.='<table align="center" class="modulo_table_list">';
                                $this->salida.='<tr>';
                                $this->salida.='<td align="center">';
                                $this->salida.='La hora inicial es menor a la final.';
                                $this->salida.='</td>';
                                $this->salida.='</tr>';
                                $this->salida.='<tr>';
                                $this->salida.='<td align="center">';
                                $accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
                                $this->salida.='<form method="post" action="">';//onclick="vol()"
                                $this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
                                $this->salida.='</form>';
                                $this->salida.='</td>';
                                $this->salida.='</tr>';
                                $this->salida.='</table>';
                                */
                            }
                        }
                    }else{
                        SessionDelVar('CITASDIA');
                        //$this->AgendaHtml();
                        ///echo '333';
                        //print_r($_REQUEST);
                        //echo '<BR>';
                        $this->ConfirmarDatosAgenda();
                        return true;
                    }
                }else{
                    /*$this->salida.='<script>';
                    $this->salida.='function vol(){';
                    $this->salida.='window.history.go(-1);';
                    $this->salida.='}';
                    $this->salida.='</script>';*/
                    //**************INICIO MODI*****************
                  $this->Asignar();
                //***************FIN MODI***************
                  $this->frmError["MensajeError"]="No se coloco ningún intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.";
                    $this->CreacionAgendaNueva($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep']);
                    return true;
                    /*$this->salida.='<table align="center" class="modulo_table_list">';
                    $this->salida.='<tr>';
                    $this->salida.='<td align="center">';
                    $this->salida.='No se coloco ningún intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.';
                    $this->salida.='</td>';
                    $this->salida.='</tr>';
                    $this->salida.='<tr>';
                    $this->salida.='<td align="center">';
                    $accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
                    $this->salida.='<form method="post" action="">';//onclick="vol()"
                    $this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
                    $this->salida.='</form>';
                    $this->salida.='</td>';
                    $this->salida.='</tr>';
                    $this->salida.='</table>';
                    */
                }
//          }
//          else{
//          //*********MODI INI*******
//
//          //  print_r($_REQUEST);
//          //          if(!empty($_REQUEST['interval']) and $_REQUEST['inihora']!='' and $_REQUEST['finhora']!='' and $_REQUEST['iniminutos']!='' and $_REQUEST['finminutos']!='' and $_REQUEST['pacientes']!='' and(
//          //          $_REQUEST['semana0']!='' || $_REQUEST['semana1']!='' || $_REQUEST['semana2']!='' || $_REQUEST['semana3']!='' || $_REQUEST['semana4']!='' || $_REQUEST['semana5']!='' || $_REQUEST['semana6']!='') and(
//          //          $_REQUEST['mes1']!='' || $_REQUEST['mes2']!='' || $_REQUEST['mes3']!='' || $_REQUEST['mes4']!='' || $_REQUEST['mes5']!='' || $_REQUEST['mes6']!='' || $_REQUEST['mes7']!=''
//          //          || $_REQUEST['mes8']!='' || $_REQUEST['mes9']!='' || $_REQUEST['mes10']!='' || $_REQUEST['mes11']!='' || $_REQUEST['mes12']!='') and ($_REQUEST['inihora']<=$_REQUEST['finhora']) and $horainic<$horafina
//          //          AND (($horainic+$_REQUEST['interval'])<=$horafina))
//              if(!empty($_REQUEST['interval']) and $_REQUEST['inihora']!='' and $_REQUEST['finhora']!='' and $_REQUEST['iniminutos']!='' and $_REQUEST['finminutos']!='' and $_REQUEST['pacientes']!=''
//                  //and($_REQUEST['semana0']!='' || $_REQUEST['semana1']!='' || $_REQUEST['semana2']!='' || $_REQUEST['semana3']!='' || $_REQUEST['semana4']!='' || $_REQUEST['semana5']!='' || $_REQUEST['semana6']!='')
//                  and($_REQUEST['mes1']!='' || $_REQUEST['mes2']!='' || $_REQUEST['mes3']!='' || $_REQUEST['mes4']!='' || $_REQUEST['mes5']!='' || $_REQUEST['mes6']!='' || $_REQUEST['mes7']!=''
//              || $_REQUEST['mes8']!='' || $_REQUEST['mes9']!='' || $_REQUEST['mes10']!='' || $_REQUEST['mes11']!='' || $_REQUEST['mes12']!=''))
//              {
//                  foreach($_REQUEST  as $v=>$v1){
//                      if($v!='mes1' and $v!='mes2' and $v!='mes3' and $v!='mes4' and $v!='mes5' and $v!='mes6' and $v!='mes7' and $v!='mes8' and $v!='mes9' and $v!='mes10' and $v!='mes11' and $v!='mes12' and $v!='guardar' and $v!='modulo' and $v!='metodo' and $v!='tipo'){
//                          $vec[$v]=$v1;
//                      }else{
//                          if($v!='guardar' and $v!='modulo' and $v!='metodo' and $v!='tipo'){
//                              $dato=$v1.'mes';
//                              $vec[$dato]=$v1;
//                          }else{
//                              unset($_REQUEST[$v]);
//                          }
//                      }
//                  }
//                  //$this->AgendaHtml();
//                  echo '222';
//                  foreach($_REQUEST as $v=>$v1){
//                      if($v=='mes1'){
//                          $vec1=$vec;
//                          unset($vec1['1mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes2'){
//                          $vec1=$vec;
//                          unset($vec1['2mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes3'){
//                          $vec1=$vec;
//                          unset($vec1['3mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes4'){
//                          $vec1=$vec;
//                          unset($vec1['4mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes5'){
//                          $vec1=$vec;
//                          unset($vec1['5mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes6'){
//                          $vec1=$vec;
//                          unset($vec1['6mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes7'){
//                          $vec1=$vec;
//                          unset($vec1['7mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes8'){
//                          $vec1=$vec;
//                          unset($vec1['8mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes9'){
//                          $vec1=$vec;
//                          unset($vec1['9mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes10'){
//                          $vec1=$vec;
//                          unset($vec1['10mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes11'){
//                          $vec1=$vec;
//                          unset($vec1['11mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                      if($v=='mes12'){
//                          $vec1=$vec;
//                          unset($vec1['12mes']);
//                          $vec1[$v]=$v1;
//                          $accion=ModuloGetURL('app','CreacionAgenda','user','VerficarDatosAgenda',$vec1);
//                          $this->salida.='Creación del mes de: <a href="'.$accion.'">'.strftime("%B",mktime(0,0,0,$v1,1,0)).'</a><br>';
//                      }
//                  }
//                  $this->ConfirmarDatosAgenda();
//          return true;
//              }else{
//                  $this->Asignar();
//                  $this->frmError["MensajeError"]="No se coloco ningún intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.";
//                  $this->CreacionAgendaNueva($_REQUEST['tercero'],$_REQUEST['tipoid'],$_REQUEST['nombrep']);
//                  return true;
//                  /*$this->salida.='<table align="center" class="modulo_table_list">';
//                  $this->salida.='<tr>';
//                  $this->salida.='<td align="center">';
//                  $this->salida.='No se coloco ningún intervalo de turno o una hora inicial o una hora final o un minuto inicial o un minuto final o una cantidad de pacientes.';
//                  $this->salida.='</td>';
//                  $this->salida.='</tr>';
//                  $this->salida.='<tr>';
//                  $this->salida.='<td align="center">';
//                  $accion=ModuloGetURL('app','CreacionAgenda','user','CreacionAgenda');
//                  $this->salida.='<form method="post" action="">';//onclick="vol()"
//                  $this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
//                  $this->salida.='</form>';
//                  $this->salida.='</td>';
//                  $this->salida.='</tr>';
//                  $this->salida.='</table>';
//                  */
//              }
//          //********FIN MODI**********
//          }
            //$this->salida .= ThemeCerrarTabla();
        }else{
            $intervalo=$this->CitasDias(&$todo);
            SessionSetVar('CITASDIA',$todo);
            foreach($_REQUEST as $value=>$dato){
                if($value!='modulo' and $value!='tipo' and $value!='DiaEspe'){
                    $vec[$value]=$dato;
                }
            }
            $accion=ModuloGetURL('app','CreacionAgenda','user','',$vec);
            $this->LlamaAgendaRetornoExterno($accion,$intervalo,$nombrep);
            return true;
        }
        //$this->AgendaHtml();
        //echo '111';
        $this->ConfirmarDatosAgenda();
        return true;
    }

    function LlamaConsultaAgendaMes(){

    list($dbconn) = GetDBconn();
      if(!empty($_REQUEST['DiaEspe'])){
      $cadena=explode(' ',$_REQUEST['DiaEspe']);
            $query="SELECT a.fecha_turno||' '||b.hora as fecha,a.duracion
            FROM agenda_turnos a,agenda_citas b
            WHERE a.tipo_id_profesional='".$_REQUEST['tipoid']."' AND a.profesional_id='".$_REQUEST['tercero']."' AND a.sw_estado_cancelacion=0 AND a.tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." AND date(a.fecha_turno)>=date(now()) AND
            date(a.fecha_turno)='".$cadena[0]."' AND a.agenda_turno_id=b.agenda_turno_id ORDER BY fecha";
            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                $datos=$result->RecordCount();
                if($datos){
                    while(!$result->EOF){
                      if(sizeof($vec)==0){
              $horaminima=$result->fields[0];
                            $interval=$result->fields[1];
                        }
                        $vec[]=$result->fields[0];
                        $result->MoveNext();
                    }
                }
            }
            $cadena=explode(' ',$horaminima);
            $hora=explode(':',$cadena[1]);
      $_REQUEST['iniminutos']=$hora[1];
            $_REQUEST['interval']=$interval;
            SessionSetVar('FECHAS',$vec);
        $intervalo=$this->CitasDias(&$todo);
            SessionSetVar('CITASDIA',$todo);
            foreach($_REQUEST as $value=>$dato){
                if($value!='modulo' and $value!='tipo' and $value!='DiaEspe'){
                    $vec[$value]=$dato;
                }
            }
            $accion=ModuloGetURL('app','CreacionAgenda','user','',$vec);
            $this->LlamaAgendaRetornoExterno($accion,$intervalo,$nombrep,$cadena[0]);
            return true;
        }else{
            $fechaexplode=explode('-',$_REQUEST['filtroFecha']);
            $query="SELECT a.fecha_turno,a.fecha_turno||' '||(SELECT min(hora) FROM agenda_citas AS b WHERE b.agenda_turno_id=a.agenda_turno_id AND b.sw_estado='0') as hora_minima,
            a.fecha_turno||' '||(SELECT max(hora) FROM agenda_citas AS b WHERE b.agenda_turno_id=a.agenda_turno_id AND b.sw_estado='0') as hora_maxima
            FROM agenda_turnos as a
            WHERE a.tipo_id_profesional='".$_REQUEST['tipoid']."' AND a.profesional_id='".$_REQUEST['tercero']."' AND a.sw_estado_cancelacion='0' AND a.tipo_consulta_id=".$_SESSION['CreacionAgenda']['Cita']." AND date(a.fecha_turno)>=date(now()) AND
            (SELECT date_part('year',a.fecha_turno))='".$fechaexplode[0]."' AND (SELECT date_part('month',a.fecha_turno))='".$fechaexplode[1]."'
            ORDER BY hora_minima;";
            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                $datos=$result->RecordCount();
                if($datos){
                    $i=0;
                    while(!$result->EOF){
            $vars[]=$result->GetRowAssoc($ToUpper = false);
            $result->MoveNext();
                    }
                }
                for($i=0;$i<sizeof($vars);$i++){
          if(!in_array($vars[$i]['fecha_turno'],$vecFechas)){
                        $vec[]=$vars[$i]['hora_minima'];
                        $vecFechas[]=$vars[$i]['fecha_turno'];
                        if($vars[$i]['fecha_turno']!=$vars[$i+1]['fecha_turno']){
              $vec[]=$vars[$i]['hora_maxima'];
                        }
                    }else{
            if($vars[$i]['fecha_turno']!=$vars[$i+1]['fecha_turno']){
              $vec[]=$vars[$i]['hora_maxima'];
                        }
                    }
                }
            }

            SessionSetVar('CITASMES',$vec);
            $this->ConsultaAgendaMes($_REQUEST['tipoid'],$_REQUEST['tercero'],$_REQUEST['fecha'],$_REQUEST['nombrep']);
            return true;
        }
    }
}

?>
