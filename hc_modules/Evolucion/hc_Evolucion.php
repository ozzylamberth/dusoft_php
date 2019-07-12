<?php

/**
* Submodulo de Evolucion.
*
* Submodulo para manejar las notas de enfermeria.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_Evolucion.php,v 1.1 2009/07/27 21:33:36 johanna Exp $
*/


/**
* Evolucion
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de motivo consulta.
*/

class Evolucion extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $limit;
	var $conteo;
     



	function Evolucion()
	{
		$this->limit=5;
		$this->salida = '';
		return true;
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
			   FROM hc_evolucion_descripcion
			   WHERE evolucion_id=".$this->evolucion.";";
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
     	if($this->tipo_profesional == 12 OR $this->tipo_profesional == 11 OR $this->tipo_profesional == 10 OR $this->tipo_profesional == 2 OR $this->tipo_profesional == 1)
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

				if($_REQUEST['accion'.$pfj]=='ListadoNotasE')
				{
					$this-> frmForma();
				}

				if($_REQUEST['accion'.$pfj]=='Epicrisis_Principal')
				{
					if($this->Evolucion_Principal()==true)
					{
                              $this->frmForma();
					}
					else
					{
						$this->frmForma();
					}
				}
				if($_REQUEST['accion'.$pfj]=='Desactivar_Epicrisis')
				{
					if($this->Desactivar_Epicrisis()==true)
					{
						$this->frmForma();
					}
					else
					{
						$this->frmForma();
					}
				}

			}
		}
		else
		{
			$this->GetConsulta();
		}
		return $this->salida;
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


	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}

	function Desactivar_Epicrisis()
	{
		$pfj=$this->frmPrefijo;
		$evolucion_id = $_REQUEST['evolucion_id'.$pfj];
		list($dbconn) = GetDBconn();
		$sql = "UPDATE hc_evolucion_descripcion
			   SET sw_epicrisis = '0'
			   WHERE hc_evolucion_descripcion_id = $evolucion_id;";
		$resultado = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->RegistrarSubmodulo($this->GetVersion());
		return true;
	}


	function Evolucion_Principal()
	{
		$pfj=$this->frmPrefijo;
		$evolucion_id = $_REQUEST['evolucion_id'.$pfj];
		list($dbconn) = GetDBconn();
		$sql = "UPDATE hc_evolucion_descripcion
			   SET sw_epicrisis = '1'
			   WHERE hc_evolucion_descripcion_id = $evolucion_id;";
		$resultado = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->RegistrarSubmodulo($this->GetVersion());
     	return true;
	}

	function ReconocerProfesional()
	{
		list($dbconn) = GetDBconn();
		$a=UserGetUID();
		if(!empty($a))
		{
		  $sql="SELECT b.tipo_profesional
			   FROM profesionales_usuarios as a,
			        profesionales as b
			   WHERE a.usuario_id=".$a."
			   and a.tipo_tercero_id=b.tipo_id_tercero and a.tercero_id=b.tercero_id;";
		}
		else
		{
			return false;
		}
		$result = $dbconn->Execute($sql);
		$i=0;
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer profesional";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				return $result->fields[0];
			}
			else
			{
				return false;
			}
		}
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
		$motivo="evol";
		$epicrisis = $_REQUEST['check'.$pfj];
		if(empty($epicrisis))
		{
			$epicrisis = '0';
		}

		$motivo.=$pfj;
		if($_REQUEST[$motivo]!="")
		{
			list($dbconn) = GetDBconn();
			$sql="INSERT INTO hc_evolucion_descripcion
							(descripcion,
							evolucion_id,
							ingreso,
							usuario_id,
							fecha_registro,
							sw_epicrisis)
						VALUES('".$_REQUEST[$motivo]."',
							".$this->evolucion.",
							".$this->ingreso.",
							".UserGetUID().",
							now(),
							'$epicrisis');";
			$result=$dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al insertar las notas de enfermeria";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{
				$this->frmError['MensajeError']="Datos Guardados Satisfactoriamente.";
				$this->RegistrarSubmodulo($this->GetVersion());
				return true;
			}
		}
		return true;
	}

	function PlanTerapeuticoActual()
	{
		list($dbconn) = GetDBconn();
		$sql="SELECT descripcion
			 FROM hc_notas_enfermeria_descripcion as a
			 WHERE a.evolucion_id=".$this->evolucion.";";
		$result=$dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al traer las descripcion";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		return $result->fields[0];
	}

	function PlanTerapeuticoTodos($can)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
					FROM hc_evolucion_descripcion
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

          $query= "SELECT A.hc_evolucion_descripcion_id,
               		 A.fecha_registro, A.descripcion,
					 A.sw_epicrisis, B.nombre, B.usuario, B.usuario_id
				FROM hc_evolucion_descripcion AS A,
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
			$datos[$fecha][]=$datosfila;
			$resulta->MoveNext();
		}
		if($this->conteo==='0')
		{
			$this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
		    return false;
		}

		return $datos;
	}

	function PlanTerapeuticoEvoluciones()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query= "SELECT A.hc_evolucion_descripcion_id,
					A.fecha_registro, A.descripcion,
					A.sw_epicrisis, B.nombre, B.usuario, B.usuario_id
				FROM hc_evolucion_descripcion AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$this->ingreso."'
				AND B.usuario_id=A.usuario_id
				ORDER BY fecha_registro ASC;";

		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			while(!$resulta->EOF)
			{
				$datosfila=$resulta->GetRowAssoc($ToUpper = false);
				list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro']));//substr(,0,10);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$datosfila[hora]=$hora.":".$min;
				$fecha = $fecha;
				$evoluciones[$fecha][]=$datosfila;
				$resulta->MoveNext();
			}
		}
		return $evoluciones;
	}
     
     
     function GetEvolucion_PrimeraVez()
     {
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
               $query= "SELECT A.hc_evolucion_descripcion_id,
                         A.fecha_registro, A.descripcion,
                         A.sw_epicrisis, B.nombre, B.usuario
                         FROM hc_evolucion_descripcion AS A,
                         system_usuarios AS B
                         WHERE A.evolucion_id='".$primera_Evo."'
                         AND B.usuario_id=A.usuario_id
                         ORDER BY fecha_registro DESC;";
               
               $resultado = $dbconn->Execute($query);        
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
                    $Primera_Evolucion[$fecha][]=$datosfila;
                    $resultado->MoveNext();
          	}
          }        
          return $Primera_Evolucion;
     }
     
     function Profesionales_Especialidades($usuario)
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT C.descripcion
                   FROM profesionales AS A 
                   LEFT JOIN profesionales_especialidades AS B ON (A.tipo_id_tercero = B.tipo_id_tercero AND A.tercero_id = B.tercero_id)
                   LEFT JOIN especialidades AS C ON (B.especialidad = C.especialidad)
                   WHERE A.usuario_id = ".$usuario.";";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($especialidad) = $resultado->FetchRow();
          return $especialidad;
     }

     function GetEvolucion_Diligenciadas()
     {
          $pfj=$this->frmPrefijo;
          list($dbconn) = GetDBconn();
          $query= "SELECT ingreso 
                   FROM ingresos
                   WHERE tipo_id_paciente = '".$this->tipoidpaciente."'
                   AND paciente_id = '".$this->paciente."';";
          $resultado1 = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while(!$resultado1->EOF)
          {
               $datosfila[]=$resultado1->fields[0];
               $resultado1->MoveNext();
          }
          //
          $k=0;
          foreach($datosfila AS $i=>$v)
          {
               $query= "SELECT B.hc_evolucion_descripcion_id,
                                   B.fecha_registro, B.descripcion,
                                   B.sw_epicrisis, C.nombre, C.usuario, b.evolucion_id,A.ingreso
                         FROM hc_evoluciones AS A,
                                   hc_evolucion_descripcion AS B,
                                   system_usuarios AS C
                         WHERE A.evolucion_id=B.evolucion_id 
                         AND A.ingreso=".$v."
                         AND A.evolucion_id<>".$this->evolucion."
                         AND C.usuario_id=B.usuario_id
                         ORDER BY B.fecha_registro DESC;";
               $resultado = $dbconn->Execute($query);
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
                    $Primera_Evolucion[$fecha][$k]=$datosfila;
                    $k++;
                    $resultado->MoveNext();
               }
               $resultado->Close();
          } 
          return $Primera_Evolucion;
     }
     
}
?>
