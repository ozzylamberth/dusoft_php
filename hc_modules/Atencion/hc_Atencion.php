<?php

/**
* Submodulo de Atención.
*
* Submodulo para manejar el tipo de atención (rips) de un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_Atencion.php,v 1.6 2006/12/15 19:11:43 alex Exp $
*/


/**
* Atencion
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de atención.
*/

class Atencion extends hc_classModules
{


/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
    function Atencion()
    {
        $this->sw_multiples_inserciones=1;
        return true;
    }


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

    function GetVersion()
    {
        $informacion=array(
        'version'=>'1',
        'subversion'=>'0',
        'revision'=>'0',
        'fecha'=>'01/27/2005',
        'autor'=>'JAIME ANDRES VALENCIA SALAZAR',
        'descripcion_cambio' => '',
        'requiere_sql' => false,
        'requerimientos_adicionales' => '',
        'version_kernel' => '1.0'
        );
        return $informacion;
    }

/**
* Esta función retorna los datos de la impresión de la consulta del submodulo.
*
* @access private
* @return text Datos HTML de la pantalla.
*/
    function GetConsulta()
    {
        if($this->frmConsulta()==false)
        {
            return true;
        }
        return $this->salida;
    }



/**
* Esta metodo captura los datos de la impresión de la Historia Clinica.
* @access private
* @return text Datos HTML de la pantalla.
*/

    function GetReporte_Html()
    {
        $imprimir=$this->frmHistoria();
        if($imprimir==false)
        {
            return true;
        }
        return $imprimir;
    }



/**
* Esta función verifica si este submodulo fue utilizado para la atencion de un paciente.
*
* @access private
* @return text Datos HTML de la pantalla.
*/

    function GetEstado()
    {
        $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
        $query="SELECT count(*)
                FROM hc_atencion
                WHERE ingreso=".$this->ingreso.";";
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
            $estado=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }

        if ($estado[count] == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }



/**
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
    function GetForma()
    {
        $pfj=$this->frmPrefijo;
        if(empty($_REQUEST['accion'.$pfj]))
        {
        $this->frmForma($_REQUEST['valor']);
        }
        else
        {
            if($this->InsertDatos()==true)
            {
                $this->frmForma();
            }
        }
        return $this->salida;
    }

/**
* Esta función inserta los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/
    function InsertDatos()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query="select tipo_atencion_id from hc_atencion where evolucion_id=".$this->evolucion." and ingreso=".$this->ingreso.";";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            return false;
        }
        else
        {
            $atencion="atencion".$pfj;
            if (!empty ($_REQUEST[$atencion]))
            {
                if($result->RecordCount()==0)
                {
                    $sql="";
                    $sql="insert into hc_atencion
                        (tipo_atencion_id ,evolucion_id,ingreso)
                        values('".$_REQUEST[$atencion]."',".$this->evolucion.",".$this->ingreso.")";
                // Reportar errores para depuracion.
                    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
                    list($dbconn) = GetDBconn();
                    if(!$dbconn->Execute($sql))
                    {
                        $error=$dbconn->ErrorMsg();
                        echo "$error";
                        return false;
                    }
                    else
                    {
                        $this->frmError['MensajeError']="DATOS GUARDADOS SATISFACTORIAMENTE";
                    }
                    $this->RegistrarSubmodulo($this->GetVersion());
                    return true;
                }
                else
                {
                    $sql="update hc_atencion set tipo_atencion_id='".$_REQUEST[$atencion]."' where evolucion_id=".$this->evolucion." and ingreso=".$this->ingreso.";";
                    // Reportar errores para depuracion.
                    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
                    list($dbconn) = GetDBconn();
                    if(!$dbconn->Execute($sql))
                    {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    else
                    {
                        $this->frmError['MensajeError']="DATOS GUARDADOS SATISFACTORIAMENTE";
                    }
                    $this->RegistrarSubmodulo($this->GetVersion());
                    return true;
                }
            }
            else
            {
                return true;
            }
        }
    }

    function AtencionConsulta()
    {
        list($dbconn) = GetDBconn();
        $query = "SELECT detalle,a.tipo_atencion_id,b.evolucion_id FROM hc_tipos_atencion as a left join hc_atencion as b on (a.tipo_atencion_id=b.tipo_atencion_id and b.evolucion_id=".$this->evolucion.") order by indice_de_orden,tipo_atencion_id;";
        //echo $query;
        $result = $dbconn->Execute($query);
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
                $atencion[0][$i]=$result->fields[0];
                $atencion[1][$i]=$result->fields[1];
                $atencion[2][$i]=$result->fields[2];
                $result->MoveNext();
                $i++;
            }
        }
        return $atencion;
    }

    function RiesgoAtencion()
    {
        list($dbconn) = GetDBconn();
        $query = "select d.tipo_atencion_id, d.detalle from pacientes as a join hc_diagnosticos_ingreso as b on(a.paciente_id='".$this->paciente."' and a.tipo_id_paciente='".$this->tipoidpaciente."') join enfermedades_profesionales as c on(b.tipo_diagnostico_id=c.diagnostico_id and a.ocupacion_id=c.ocupacion_id) join hc_tipos_atencion as d on(c.tipo_atencion_id=d.tipo_atencion_id) where b.evolucion_id=".$this->evolucion.";";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        else
        {
            if(!$result->EOF)
            {
                while(!$result->EOF)
                {
                    $dato[]=$result->GetRowAssoc(false);
                    $result->MoveNext();
                }
            }
            else
            {
                return false;
            }
        }
        return $dato;
    }

    function ConsultaAtencion()
    {
        list($dbconn) = GetDBconn();
        $query ="SELECT detalle FROM hc_atencion,hc_tipos_atencion where hc_atencion.tipo_atencion_id = hc_tipos_atencion.tipo_atencion_id and ingreso=".$this->ingreso.";";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return $result;
    }

    function GetOrigenAtencion_PrimeraVez()
    {
            list($dbconn) = GetDBconn();
                $pfj=$this->frmPrefijo;
                list($dbconn) = GetDBconn();
                $query= "SELECT evolucion_id
                                    FROM hc_odontogramas_primera_vez
                                    WHERE tipo_id_paciente = '".$this->tipoidpaciente."'
                                    AND paciente_id = '".$this->paciente."'
                                    AND sw_activo = '1';";
                $resultado = $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0)
                {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                }
                list($primera_Evo) = $resultado->FetchRow();
                $this->primera_Evo = $primera_Evo;

                if (!empty($primera_Evo))
                {
                    $query ="SELECT detalle
                                    FROM hc_atencion,hc_tipos_atencion
                                    WHERE hc_atencion.tipo_atencion_id = hc_tipos_atencion.tipo_atencion_id
                                    AND evolucion_id=".$primera_Evo.";";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }
                }
            return $result;
    }

}
?>
