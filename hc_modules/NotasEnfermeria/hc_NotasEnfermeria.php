<?php

/**
* Submodulo de Motivo Consulta.
*
* Submodulo para manejar las notas de enfermeria.
* @author Jairo Duvan Diaz Martinez <planetjd@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_NotasEnfermeria.php,v 1.9 2009/04/22 19:19:09 johanna Exp $
*/


/**
* NotasEnfermeria
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de motivo consulta.
*/

class NotasEnfermeria extends hc_classModules
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	var $limit;
	var $conteo;

	function NotasEnfermeria()
	{
		$this->limit=5;
		return true;
	}


/**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

// 	function GetVersion()
// 	{
// 		$informacion=array(
// 		'version'=>'1',
// 		'subversion'=>'0',
// 		'revision'=>'0',
// 		'fecha'=>'01/27/2005',
// 		'autor'=>'TIZZIANO PEREA OCORO',
// 		'descripcion_cambio' => '',
// 		'requiere_sql' => false,
// 		'requerimientos_adicionales' => '',
// 		'version_kernel' => '1.0'
// 		);
// 		return $informacion;
// 	}


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
* Esta función retorna la presentación del submodulo (consulta o inserción).
*
* @access public
* @return text Datos HTML de la pantalla.
* @param text Determina la acción a realizar.
*/
	function GetForma()
	{
		if($this->tipo_profesional == 3 OR $this->tipo_profesional == 4 OR $this->tipo_profesional == 8 OR $this->tipo_profesional == 7 OR $this->tipo_profesional == 12)
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
			}
			if($_REQUEST['accion'.$pfj]=='ListadoNotasE')
			{
				$this-> frmForma();
			}
		}
		else
		{
			$this->GetConsulta();
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
		$motivo.=$pfj;
		if($_REQUEST[$motivo]!="")
		{
			list($dbconn) = GetDBconn();
			$sql="INSERT INTO hc_notas_enfermeria_descripcion
							(descripcion,
							evolucion_id,
							usuario_id,
							fecha_registro,
							ingreso,
							fecha_registro_nota)
						VALUES('".$_REQUEST[$motivo]."',
							".$this->evolucion.",
							".UserGetUID().",
							now(),
							".$this->ingreso.",
							'".$_REQUEST['selectHora'.$pfj].":".$_REQUEST['selectMinutos'.$pfj]."');";
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
					  FROM hc_notas_enfermeria_descripcion
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

		   $query= "SELECT A.fecha_registro, A.fecha_registro_nota,A.descripcion,
					B.nombre, B.usuario
					FROM hc_notas_enfermeria_descripcion AS A,
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
			list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro_nota']));//substr(,0,10);
			list($ano,$mes,$dia) = explode("-",$fecha);//substr(,0,10);
			list($hora,$min) = explode(":",$hora);//substr(,0,10);
			$datosfila[hora]=$hora.":".$min;
			if($fecha == date("Y-m-d")) {
				$fecha = "HOY";
			}
			elseif($fecha == date("Y-m-d",mktime(0,0,0,date("m") ,date("d")-1,date("Y")))){
				$fecha = "AYER";
			}

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

	function ResumenPlanTerapeutico($can)
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();

		   $query= "SELECT A.fecha_registro,A.fecha_registro_nota, A.descripcion,
					B.nombre, B.usuario
					FROM hc_notas_enfermeria_descripcion AS A,
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
			list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro_nota']));//substr(,0,10);
			list($ano,$mes,$dia) = explode("-",$fecha);//substr(,0,10);
			list($hora,$min) = explode(":",$hora);//substr(,0,10);
			$datosfila[hora]=$hora.":".$min;
			if($fecha == date("Y-m-d")) {
				$fecha = "HOY";
			}
			elseif($fecha == date("Y-m-d",mktime(0,0,0,date("m") ,date("d")-1,date("Y")))){
				$fecha = "AYER";
			}

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


	function PlanTerapeuticoNotasE()
	{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
		$query= "SELECT A.fecha_registro, A.fecha_registro_nota,A.descripcion,
						B.nombre, B.usuario
				 FROM hc_notas_enfermeria_descripcion AS A,
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
				list($fecha,$hora) = explode(" ",$this->PartirFecha($datosfila['fecha_registro_nota']));//substr(,0,10);
				list($ano,$mes,$dia) = explode("-",$fecha);
				list($hora,$min) = explode(":",$hora);
				$datosfila[hora]=$hora.":".$min;
				$fecha = $fecha;
				$Notas[$fecha][]=$datosfila;
				$resulta->MoveNext();
			}
			return $Notas;
		}
	}
}
?>