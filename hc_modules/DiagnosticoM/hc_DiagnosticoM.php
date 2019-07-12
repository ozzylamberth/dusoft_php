<?php

/**
* Submodulo de Diagnosticos Muerte.
*
* Submodulo para manejar los Diagnosticos de Muerte (rips) en un paciente en una evolución.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co

* Modificado por
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* Jun/02/2004

* @version 1.0
* @package SIIS
* $Id: hc_DiagnosticoM.php,v 1.3 2006/12/19 21:00:13 jgomez Exp $
*/
/**
* DiagnosticoM
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo del diagnostico de Muerte .
*/

class DiagnosticoM extends hc_classModules
{
	var $limit;
	var $conteo;
/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function DiagnosticoM()
	{
	     $this->limit=GetLimitBrowser();
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
// 		'autor'=>'JAIME ANDRES VALENCIA',
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
		$pfj=$this->frmPrefijo;
		$accion='accion'.$pfj;
		if(empty($_REQUEST[$accion]))
		{
			$this->frmConsulta();
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
          return true;
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
			if($_REQUEST['accion'.$pfj]=='Busqueda_Avanzada_Diagnosticos')
						{
									 $vectorD= $this->Busqueda_Avanzada_Diagnosticos();
                   $this-> frmForma($vectorD);
						}
			if($_REQUEST['accion'.$pfj]=='insertar_varios_diagnosticos')
			{
					$this->Insertar_Varios_Diagnosticos();
					$this-> frmForma();
			}
			if($_REQUEST['accion'.$pfj]=='eliminar_diagnostico')
			{
					$this-> Eliminar_Diagnostico_Solicitado($_REQUEST['diagnostico_id'.$pfj]);
					$this-> frmForma();
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
//cor - clzc - ads
function Insertar_Varios_Diagnosticos()
{
		 $pfj=$this->frmPrefijo;
		 list($dbconn) = GetDBconn();
     foreach($_REQUEST['opD'.$pfj] as $index=>$codigo)
		    {
				 $arreglo=explode(",",$codigo);

	       $query="INSERT into hc_diagnosticos_muerte
				        (usuario_id,tipo_diagnostico_id,evolucion_id)
						    VALUES('".$_SESSION['SYSTEM_USUARIO_ID']."','".$arreglo[0]."'
								,'$this->evolucion');";

				 $resulta=$dbconn->Execute($query);
				 if ($dbconn->ErrorNo() != 0)
						 {
							$this->error = "Error al insertar en hc_diagnosticos_muerte";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$this->frmError["MensajeError"]="EL DIAGNOSTICO ".$arreglo[0]." YA FUE ASIGNADO.";
							return false;
						 }
					else
					  {
        				$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
					  }
		    }
	$this->RegistrarSubmodulo($this->GetVersion());
  return true;
}


/**
* Esta función borra los datos del submodulo.
*
* @access private
* @return boolean Informa si lo logro o no.
*/

//cor - clzc - ads
function Eliminar_Diagnostico_Solicitado($diagnostico_id)
{
			$pfj=$this->frmPrefijo;
			list($dbconn) = GetDBconn();
			$query="delete from hc_diagnosticos_muerte where tipo_diagnostico_id = '$diagnostico_id'	and evolucion_id=".$this->evolucion.";";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
					$this->frmError["MensajeError"]="NO SE PUEDE ELIMINAR EL DIAGNOSTICO";
					return false;
					}
			else
				  {
					  $this->frmError["MensajeError"]="DIAGNOSTICO ELIMINADO.";
  				}
 $this->RegistrarSubmodulo($this->GetVersion());
 return true;
}


	//cor - clzc -ads
function ConsultaDiagnosticoM()
{
		$pfj=$this->frmPrefijo;
    list($dbconnect) = GetDBconn();
	  $query = "select diagnostico_id,diagnostico_nombre from hc_diagnosticos_muerte,diagnosticos where hc_diagnosticos_muerte.tipo_diagnostico_id=diagnosticos.diagnostico_id
		and evolucion_id=".$this->evolucion.";";

		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al buscar en la consulta de diagnosticos de muerte";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
      return false;
		}
		else
		{ $i=0;
			while (!$result->EOF)
			{
			$vector[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
			}
		}
	  return $vector;

}

	//cor - clzc-jea - ads
function Busqueda_Avanzada_Diagnosticos()
{
		$pfj=$this->frmPrefijo;
		list($dbconn) = GetDBconn();
    $codigo       = STRTOUPPER ($_REQUEST['codigo'.$pfj]);
		$diagnostico  =STRTOUPPER($_REQUEST['diagnostico'.$pfj]);

		$busqueda1 = '';
		$busqueda2 = '';

		if ($codigo != '')
		{
			$busqueda1 =" WHERE diagnostico_id LIKE '$codigo%'";
		}

		if (($diagnostico != '') AND ($codigo != ''))
		{
			$busqueda2 ="AND diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if (($diagnostico != '') AND ($codigo == ''))
		{
			$busqueda2 ="WHERE diagnostico_nombre LIKE '%$diagnostico%'";
		}

		if(empty($_REQUEST['conteo'.$pfj]))
		{
			$query = "SELECT count(*)
						FROM diagnosticos
						$busqueda1 $busqueda2";

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
			   $query = "
						SELECT diagnostico_id, diagnostico_nombre
						FROM diagnosticos
						$busqueda1 $busqueda2 order by diagnostico_id
						LIMIT ".$this->limit." OFFSET $Of;";
		$resulta = $dbconn->Execute($query);
		//$this->conteo=$resulta->RecordCount();
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
		  {       $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
			        return false;
		  }
		 return $var;
}
}

?>
