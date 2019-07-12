<?php

/**
* Submodulo de Motivo Consulta Psicologico.
*
* Submodulo para manejar el motivo de la consulta en una evolucion.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_TrabajosRealizados.php,v 1.1 2007/11/30 20:57:09 tizziano Exp $
*/


/**
* MotivoConsultaPsicologico.
*
* Clase para accesar los metodos privados de la clase de presentaciï¿½, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserciï¿½ y la consulta del
* submodulo de motivo consulta.
*/

class TrabajosRealizados extends hc_classModules
{

    var $limit;
    var $conteo;

/**
* Esta funciï¿½ Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function TrabajosRealizados()
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


     function Get_TiposCrecimientos()
     {
          list($dbconn) = GetDBconn();
          $query= "SELECT *
                   FROM hc_psicologia_tipo_trabajos_realizados;";
     
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while(!$resulta->EOF)
          {
               $datos[] = $resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $datos;
     }
     
     function GetDatosDetalleMotivo()
     {
          list($dbconn) = GetDBconn();
          $query= "SELECT B.descripcion, C.descripcion AS tipo_mayor
                   FROM hc_psicologia_trabajos_realizados_detalle AS A,
                        hc_psicologia_tipo_trabajos_realizados_detalle AS B,
                        hc_psicologia_tipo_trabajos_realizados AS C
                   WHERE ingreso = ".$this->ingreso."
                   AND evolucion_id = ".$this->evolucion."
                   AND A.tipo_motivo_d = B.trabajo_detalle_id
                   AND A.tipo_motivo = C.trabajo_id;";
     
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while(!$resulta->EOF)
          {
               $vector[$resulta->fields[1]][] = $resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
          }
          return $vector;
     
     }

}
?>
