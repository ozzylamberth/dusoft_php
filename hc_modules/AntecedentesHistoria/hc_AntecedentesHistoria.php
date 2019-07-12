<?php

/**
* Submodulo de Antecedentes Historia.
*
* Submodulo para manejar los Antecedentes Historicos del paciente en una evolucion.
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesHistoria.php,v 1.1 2007/11/30 20:35:35 tizziano Exp $
*/


/**
* AntecedentesHistoria.
*
* Clase para accesar los metodos privados de la clase de presentacion, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de insercion y la consulta del
* submodulo.
*/

class AntecedentesHistoria extends hc_classModules
{

    var $limit;
    var $conteo;

/**
* Esta funciï¿½ Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function AntecedentesHistoria()
	{
		$this->limit=5;
		return true;
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
				FROM hc_psicologia_antecedentes_historia
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
                    FROM hc_psicologia_antecedentes_historia
                    WHERE ingreso=".$this->ingreso." AND
                    evolucion_id = ".$this->evolucion.";";

          $result = $dbconn->Execute($query);

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar consultar la tabla \"hc_psicologia_antecedentes_historia\".<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if(!$result->EOF)
               {
                    $this->frmError["MensajeError"] = "YA SE REGISTRO UN ANTECEDENTE EN ESTA EVOLUCION (".$this->evolucion.")";
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

          $sql="INSERT INTO hc_psicologia_antecedentes_historia (descripcion,
                                                                 evolucion_id,
                                                                 ingreso,
                                                                 usuario_id,
                                                                 fecha_registro)
                                                            VALUES($motivito,
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
                         FROM hc_psicologia_antecedentes_historia
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
     
          $query= "SELECT A.descripcion, A.fecha_registro,
                          A.usuario_id, B.nombre, B.usuario
                   FROM hc_psicologia_antecedentes_historia AS A,
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
               list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));
               list($ano,$mes,$dia) = explode("-",$fecha);
               list($hora,$min) = explode(":",$hora);
               $datosfila[hora]=$hora.":".$min;
               $motivos[$fecha][]=$datosfila;
               $resulta->MoveNext();
          }
          return $motivos;
     }

     function Reporte_Motivos()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT A.descripcion,
                          A.fecha_registro,
                          A.usuario_id, B.nombre, B.usuario
                   FROM hc_psicologia_antecedentes_historia AS A,
                        system_usuarios AS B
                   WHERE A.ingreso = '".$this->ingreso."'
                   AND B.usuario_id = A.usuario_id
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
               list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));
               list($ano,$mes,$dia) = explode("-",$fecha);
               list($hora,$min) = explode(":",$hora);
               $datosfila[hora]=$hora.":".$min;
               $fecha = $fecha;
               $enfermedades[$fecha][]=$datosfila;
               $resulta->MoveNext();
          }
          return $enfermedades;
     }

}
?>
