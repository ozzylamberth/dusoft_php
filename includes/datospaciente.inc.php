<?php

/**
 * $Id: datospaciente.inc.php,v 1.11 2009/07/30 12:50:01 johanna Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 * @author Alexander Giraldo Salas - alexgiraldo@ipsoft-sa.com
 */


    /**
    * Retorna Vector con los datos del paciente consultado
    *
    * La consulta se puede realizar por identificacion, numero de ingreso o numero de evolucion
    *
    * @param string $pacienteId opcional si esta requiere obligatorio el parametro $tipoIdPaciente
    * @param string $tipoIdPaciente opcional si esta requiere obligatorio el parametro $pacienteId
    * @param integer $ingreso opcional
    * @param integer $evolucion opcional
    * @return array
    * @access public
    */
    function &GetDatosPaciente($pacienteId='',$tipoIdPaciente='',$ingreso='',$evolucion='')
    {
        if((empty($pacienteId) || empty($tipoIdPaciente)) && empty($ingreso) && empty($evolucion))
        {
            return false;
        }
        static $DatosPacientesTipo_id;
        static $DatosPacientesIngreso;
        static $DatosPacientesEvolucion;

        if($pacienteId!="" && $tipoIdPaciente!="")
        {
            if(!$DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId])
            {
                GLOBAL $ADODB_FETCH_MODE;
                list($dbconn) = GetDBconn();
                $query="SELECT  c.primer_apellido, 
                                c.segundo_apellido, 
                                c.primer_nombre,
                                c.segundo_nombre, 
                                c.sexo_id, 
                                c.fecha_nacimiento, 
                                c.residencia_direccion,
                                c.paciente_id, 
                                c.tipo_id_paciente,
                                b.historia_prefijo, 
                                b.historia_numero,
                                c.residencia_telefono, 
                                c.tipo_pais_id, 
                                c.tipo_dpto_id,
                                c.tipo_mpio_id,
                                --c.sw_ficha,
                                c.ocupacion_id
                        FROM historias_clinicas as b, pacientes as c
                        WHERE c.paciente_id='$pacienteId'
                        and c.tipo_id_paciente='$tipoIdPaciente'
                        and b.paciente_id=c.paciente_id
                        and b.tipo_id_paciente=c.tipo_id_paciente;";

                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                if ($dbconn->ErrorNo() != 0) return false;

                if ($result->EOF)
                {
                    $query="SELECT primer_apellido, 
                              segundo_apellido, 
                              primer_nombre,
                              segundo_nombre, 
                              sexo_id, 
                              fecha_nacimiento, 
                              residencia_direccion,
                              paciente_id, 
                              tipo_id_paciente,
                              residencia_telefono, 
                              tipo_pais_id, 
                              tipo_dpto_id,
                              tipo_mpio_id,
                              --sw_ficha,
                              ocupacion_id
                            FROM pacientes
                            WHERE paciente_id='$pacienteId'
                            and tipo_id_paciente='$tipoIdPaciente';";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if ($dbconn->ErrorNo() != 0) return false;
                    if ($result->EOF) return false;
                }

                $datos=$result->FetchRow();
                $result->Close();

                $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId] = &$datos;
                $DatosUbicacion=GetInfoUbicacion($datos['tipo_pais_id'],$datos['tipo_dpto_id'],$datos['tipo_mpio_id']);
                $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId]['municipio']=$DatosUbicacion['municipio'];
                $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId]['departamento']=$DatosUbicacion['departamento'];
                $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId]['pais']=$DatosUbicacion['pais'];
            }
            return $DatosPacientesTipo_id[$tipoIdPaciente][$pacienteId];
        }

        if(!empty($ingreso))
        {
            if(!$DatosPacientesIngreso[$ingreso])
            {
                GLOBAL $ADODB_FETCH_MODE;
                list($dbconn) = GetDBconn();

                $query="SELECT c.primer_apellido, 
                          c.segundo_apellido, 
                          c.primer_nombre, 
                          c.segundo_nombre,
                          c.sexo_id, 
                          c.fecha_nacimiento, 
                          c.residencia_direccion, 
                          c.paciente_id,
                          c.tipo_id_paciente, 
                          b.historia_prefijo,
                          b.historia_numero, 
                          c.residencia_telefono, 
                          c.tipo_pais_id, 
                          c.tipo_dpto_id, 
                          c.tipo_mpio_id,
                          --c.sw_ficha,
                          c.ocupacion_id
                        FROM  pacientes as c , 
                              historias_clinicas as b, 
                              ingresos as a
                        WHERE a.ingreso=$ingreso
                        and c.paciente_id=a.paciente_id
                        and c.tipo_id_paciente=a.tipo_id_paciente

                        and b.paciente_id=c.paciente_id
                        and b.tipo_id_paciente=c.tipo_id_paciente;";


                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if ($dbconn->ErrorNo() != 0) return false;
                if ($result->EOF)
                {
                    $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre, c.segundo_nombre,
                                c.sexo_id, 
                                c.fecha_nacimiento, 
                                c.residencia_direccion, 
                                c.paciente_id,
                                c.tipo_id_paciente, 
                                c.residencia_telefono, 
                                c.tipo_pais_id, 
                                c.tipo_dpto_id, 
                                c.tipo_mpio_id,
                                --c.sw_ficha,
                                c.ocupacion_id

                            FROM pacientes as c , ingresos as a

                            WHERE a.ingreso=".$ingreso."
                            and c.paciente_id=a.paciente_id
                            and c.tipo_id_paciente=a.tipo_id_paciente";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if ($dbconn->ErrorNo() != 0) return false;
                    if ($result->EOF) return false;
                }

                $datos=$result->FetchRow();
                $result->Close();
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']] = &$datos;
                $DatosUbicacion=GetInfoUbicacion($datos['tipo_pais_id'],$datos['tipo_dpto_id'],$datos['tipo_mpio_id']);
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['municipio']=$DatosUbicacion['municipio'];
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['departamento']=$DatosUbicacion['departamento'];
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['pais']=$DatosUbicacion['pais'];
                $DatosPacientesIngreso[$ingreso]['tipo_id_paciente']=$datos['tipo_id_paciente'];
                $DatosPacientesIngreso[$ingreso]['paciente_id']=$datos['paciente_id'];
            }
            return $DatosPacientesTipo_id[$DatosPacientesIngreso[$ingreso]['tipo_id_paciente']][$DatosPacientesIngreso[$ingreso]['paciente_id']];
        }

        if(!empty($evolucion))
        {
            if(!$DatosPacientesEvolucion[$evolucion])
            {
                GLOBAL $ADODB_FETCH_MODE;
                list($dbconn) = GetDBconn();

                $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre,
                        c.segundo_nombre, c.sexo_id, c.fecha_nacimiento, c.residencia_direccion,
                        c.paciente_id, c.tipo_id_paciente,
                        e.historia_prefijo, e.historia_numero,
                        c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
                        c.tipo_mpio_id,
                        --c.sw_ficha,
                        c.ocupacion_id
                        FROM hc_evoluciones as b, historias_clinicas as e,
                        pacientes as c, ingresos as a
                        WHERE b.evolucion_id=$evolucion and a.ingreso=b.ingreso
                        and a.tipo_id_paciente=c.tipo_id_paciente and   a.paciente_id=c.paciente_id
                        and c.tipo_id_paciente=e.tipo_id_paciente and   c.paciente_id=e.paciente_id;";


                $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                $result = $dbconn->Execute($query);
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                if ($dbconn->ErrorNo() != 0) return false;
                if ($result->EOF)
                {
                    $query="SELECT c.primer_apellido, c.segundo_apellido, c.primer_nombre,
                            c.segundo_nombre, c.sexo_id, c.fecha_nacimiento, c.residencia_direccion,
                            c.paciente_id, c.tipo_id_paciente,
                            c.residencia_telefono, c.tipo_pais_id, c.tipo_dpto_id,
                            c.tipo_mpio_id,
                            --c.sw_ficha,
                            c.ocupacion_id
                            FROM hc_evoluciones as b, pacientes as c, ingresos as a
                            WHERE b.evolucion_id=$evolucion and a.ingreso=b.ingreso
                            and c.paciente_id=a.paciente_id and c.tipo_id_paciente=a.tipo_id_paciente;";

                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    $result = $dbconn->Execute($query);
                    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

                    if ($dbconn->ErrorNo() != 0) return false;
                    if ($result->EOF) return false;
                }

                $datos=$result->FetchRow();
                $result->Close();
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']] = &$datos;
                $DatosUbicacion=GetInfoUbicacion($datos['tipo_pais_id'],$datos['tipo_dpto_id'],$datos['tipo_mpio_id']);
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['municipio']=$DatosUbicacion['municipio'];
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['departamento']=$DatosUbicacion['departamento'];
                $DatosPacientesTipo_id[$datos['tipo_id_paciente']][$datos['paciente_id']]['pais']=$DatosUbicacion['pais'];
                $DatosPacientesEvolucion[$evolucion]['tipo_id_paciente']=$datos['tipo_id_paciente'];
                $DatosPacientesEvolucion[$evolucion]['paciente_id']=$datos['paciente_id'];
            }
            return $DatosPacientesTipo_id[$DatosPacientesEvolucion[$evolucion]['tipo_id_paciente']][$DatosPacientesEvolucion[$evolucion]['paciente_id']];
        }

        return false;
    }


    /**
    * Retorna Vector con los datos obligatorios de los pacientes
    *
    * @return array
    * @access public
    */
    function BuscarCamposObligatoriosPacientes()
    {
        static $var;
        if(empty($var))
        {
            list($dbconn) = GetDBconn();
            $query="SELECT campo,sw_mostrar,sw_obligatorio FROM pacientes_campos_obligatorios";
            $result=$dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) return false;
            while(!$result->EOF){
                $var[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
        }
        return $var;
    }


    /**
    * Retorna Vector con los tipos de id de los pacientes
    *
    * @return array vector con los tipos de id de los pacientes
    * @access public
    */
    function TiposIdPacientes()
    {
        static $vars;
        if(empty($vars))
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT * FROM tipos_id_pacientes ORDER BY indice_de_orden";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) return false;
            if ($result->EOF) return false;
            while (!$result->EOF) {
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            $result->Close();
        }
        return $vars;
    }

    /**
    * Retorna Descripciones del Pais,Departamento,municipio recibiendo como parametros los codigos
    *
    * @param string $tipo_pais_id
    * @param string $tipo_dpto_id
    * @param string $tipo_mpio_id
    * @return array vector conlas descripciones de Pais,Departamento y Municipio.
    * @access public
    */
    function GetInfoUbicacion($tipo_pais_id,$tipo_dpto_id,$tipo_mpio_id)
    {
        static $datosUbicacion;

        if(empty($tipo_pais_id) || empty($tipo_dpto_id) || empty($tipo_mpio_id))
        {
            return false;
        }

        if(!$datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id])
        {
            list($dbconn) = GetDBconn();
            GLOBAL $ADODB_FETCH_MODE;

            $sql=" SELECT municipio,departamento,pais
                    FROM tipo_mpios a, tipo_dptos b, tipo_pais c
                    WHERE a.tipo_mpio_id = '$tipo_mpio_id'
                    AND a.tipo_dpto_id='$tipo_dpto_id'
                    AND a.tipo_pais_id='$tipo_pais_id'
                    AND b.tipo_dpto_id=a.tipo_dpto_id
                    AND b.tipo_pais_id=a.tipo_pais_id
                    AND c.tipo_pais_id=b.tipo_pais_id";

            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $result = $dbconn->Execute($sql);
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

            if ($dbconn->ErrorNo() != 0) return false;
            if ($result->EOF) return false;
            $fila= $result->FetchRow();
            $result->Close();
            $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id]['municipio']=$fila['municipio'];
            $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id]['departamento']=$fila['departamento'];
            $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id]['pais']=$fila['pais'];
            unset($fila);
        }
        return $datosUbicacion[$tipo_pais_id][$tipo_dpto_id][$tipo_mpio_id];
    }

?>