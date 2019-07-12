<?php

/**
* Submodulo de Motivo Consulta Basico.
*
* Submodulo para manejar el motivo de la consulta en una evoluciï¿½.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_MotivoConsultaBasico.php,v 1.10 2006/12/19 21:00:13 jgomez Exp $
*/


/**
* MotivoConsultaBasico.
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta del
* submodulo de motivo consulta.
*/

class MotivoConsultaBasico extends hc_classModules
{

    var $limit;
    var $conteo;

/**
* Esta funciï¿½ Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function MotivoConsultaBasico()
	{
		$this->limit=5;
		return true;
	}



/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

//     function GetVersion()
//     {
//         $informacion=array(
//         'version'=>'1',
//         'subversion'=>'0',
//         'revision'=>'0',
//         'fecha'=>'01/27/2005',
//         'autor'=>'TIZZIANO PEREA OCORO',
//         'descripcion_cambio' => '',
//         'requiere_sql' => false,
//         'requerimientos_adicionales' => '',
//         'version_kernel' => '1.0'
//         );
//         return $informacion;
//     }


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
				FROM hc_motivo_consulta
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
            $this->frmForma();
        }
        else
        {
            if($this->InsertDatos()==true)
            {
                $this->frmForma();
            }

            if($_REQUEST['accion'.$pfj]=='ListadoNotasE')
            {
                $this-> frmForma();
            }
        }
        return $this->salida;
    }


    function PartirFecha($fecha)
    {
        $a=explode('-',$fecha);
        $b=explode(' ',$a[2]);
        $c=explode(':',$b[1]);
        $d=explode('.',$c[2]);
        return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
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
        $motivo="motivo";
        $motivo.=$pfj;
        $enferact="enferact";
        $enferact.=$pfj;

		if ($_REQUEST[$motivo] == "")
		{
			$this->frmError["MensajeError"] = "EL CAMPO DEBE CONTENER DATOS";
			$this->frmForma();
			return true;
		}

		   $query = "SELECT evolucion_id
					FROM hc_motivo_consulta
					WHERE ingreso=".$this->ingreso." AND
					evolucion_id = ".$this->evolucion.";";

			$result = $dbconn->Execute($query);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al ejecutar la conexion";
				$this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_motivo_consulta\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
				return false;
			}
			else
			{
				if(!$result->EOF)
				{
					$this->frmError["MensajeError"] = "YA SE REGISTRO UN MOTIVO DE CONSULTA EN ESTA EVOLUCION (".$this->evolucion.")";
					$this->frmForma();
					return true;
				}
			}

        if($_REQUEST[$motivo]!="" or $_REQUEST[$enferact]!="")
        {


            if (!empty($_REQUEST[$motivo]))
            {
                $motivito = $_REQUEST[$motivo];
                $motivito = "'$motivito'";
            }
            else
            {
                $motivito = $_REQUEST[$motivo];
                $motivito = 'NULL';
            }

            if (!empty($_REQUEST[$enferact]))
            {
                $enfermedad = $_REQUEST[$enferact];
                $enfermedad = "'$enfermedad'";
            }
            else
            {
                $enfermedad = $_REQUEST[$enferact];
                $enfermedad = 'NULL';
            }

         		$sql="INSERT INTO hc_motivo_consulta (descripcion,
                                                enfermedadactual,
                                                evolucion_id,
                                                ingreso,
                                                usuario_id,
                                                fecha_registro)
                                        VALUES($motivito,
                                               $enfermedad,
                                                ".$this->evolucion.",
                                                ".$this->ingreso.",
                                                ".UserGetUID().",
                                                now());";

            error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
            if(!$dbconn->Execute($sql))
            {
            $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            else
            {
                 $this->RegistrarSubmodulo($this->GetVersion());            
                return true;
            }
        }
        return true;
    }

    function ConsultaMotivo()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        if(empty($_REQUEST['conteo'.$pfj]))
        {
            $query = "SELECT count(*)
                      FROM hc_motivo_consulta
                      WHERE ingreso='".$this->ingreso."';";

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

           $query= "SELECT A.descripcion,
                    A.enfermedadactual, A.fecha_registro,
                    A.usuario_id, B.nombre, B.usuario
                    FROM hc_motivo_consulta AS A,
                    system_usuarios AS B
                    WHERE A.ingreso='".$this->ingreso."'
                    AND B.usuario_id=A.usuario_id
                    ORDER BY fecha_registro DESC
                    LIMIT ".$this->limit." OFFSET $Of;";

            $resulta = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
        {
            $datosfila=$resulta->GetRowAssoc($ToUpper = false);
            list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
            list($ano,$mes,$dia) = explode("-",$fecha);//substr(,0,10);
            list($hora,$min) = explode(":",$hora);//substr(,0,10);
            $datosfila[hora]=$hora.":".$min;
            $motivos[$fecha][]=$datosfila;
            $resulta->MoveNext();
        }
//         if($this->conteo==='0')
//         {
//             $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
//             return false;
//         }
        return $motivos;
    }

    function Reporte_Motivos()
    {
        $pfj=$this->frmPrefijo;
        list($dbconn) = GetDBconn();
        $query= "SELECT A.descripcion,
                A.enfermedadactual, A.fecha_registro,
                A.usuario_id, B.nombre, B.usuario
                FROM hc_motivo_consulta AS A,
                system_usuarios AS B
                WHERE A.ingreso='".$this->ingreso."'
                AND B.usuario_id=A.usuario_id
                ORDER BY fecha_registro DESC;";

        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        while(!$resulta->EOF)
        {
            $datosfila=$resulta->GetRowAssoc($ToUpper = false);
            list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
            list($ano,$mes,$dia) = explode("-",$fecha);//substr(,0,10);
            list($hora,$min) = explode(":",$hora);//substr(,0,10);
            $datosfila[hora]=$hora.":".$min;
            $fecha = $fecha;
            $enfermedades[$fecha][]=$datosfila;
            $resulta->MoveNext();
        }
        return $enfermedades;
    }
    
              
     function GetMotivo_PrimeraVez()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT evolucion_id 
                    FROM hc_odontogramas_primera_vez
                    WHERE tipo_id_paciente = '".$this->tipoidpaciente."'
                    AND paciente_id = '".$this->paciente."'
                    AND (sw_activo = '1' OR sw_activo = '0');";
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
            $sql= "SELECT A.descripcion,
                         A.enfermedadactual, A.fecha_registro,
                         A.usuario_id, A.evolucion_id, B.nombre, B.usuario
                       FROM hc_motivo_consulta AS A,
                         system_usuarios AS B
                       WHERE A.evolucion_id=".$primera_Evo."
                         AND B.usuario_id=A.usuario_id
                         ORDER BY fecha_registro DESC;";
               $resultado = $dbconn->Execute($sql);        
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while(!$resultado->EOF)
               {
                    $datosfila=$resultado->GetRowAssoc($ToUpper = false);
                    list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));
                    list($ano,$mes,$dia) = explode("-",$fecha);
                    list($hora,$min) = explode(":",$hora);
                    $datosfila[hora]=$hora.":".$min;
                    $fecha = $fecha;
                    $P_motivo[$fecha][]=$datosfila;
                    $resultado->MoveNext();
            }
          }        
          return $P_motivo;
     }


}
?>
