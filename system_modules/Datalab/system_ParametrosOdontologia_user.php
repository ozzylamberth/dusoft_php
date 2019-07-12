
<?php

/**
* Modulo de ParametrosOdontologia (PHP).
*
* Modulo para el mantenimiento de los cargos del tarifario cups y de
* los cargos de la interface con datalab, asi como sus equivalencias
*
* @author Jorge Eliécer Ávila Garzón <jorkant@hotmail.com>
* @version 1.0
* @package SIIS
**/

/**
* system_ParametrosOdontologia_user.php
*
**/

class system_ParametrosOdontologia_user extends classModulo
{
	var $uno;//para los errores
	var $limit;
	var $conteo;

	function system_ParametrosOdontologia_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}

	function main()
	{
		$this->PrincipalPOdont();
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

	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	function BuscarTiposCuadrantesPOdont()
	{
		list($dbconn) = GetDBconn();
		$query ="SELECT hc_tipo_cuadrante_id,
		descripcion,
		indice_orden,
		sw_mostrar
		FROM hc_tipos_cuadrantes_dientes
		ORDER BY indice_orden;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "ERROR AL CARGAR LOS DATOS DEL MODULO";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var[]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		return $var;
	}

	function ValidarNuevoTiposCuadrantesPOdont()
	{
		if($_POST['descripcio']==NULL)
		{
			$this->frmError["descripcio"]=1;
		}
		if(is_numeric($_POST['ordeindice'])==0)
		{
			$this->frmError["ordeindice"]=1;
		}

		if($this->frmError["descripcio"]==1 OR $this->frmError["ordeindice"]==1)
		{
			$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
			$this->uno=1;
			$this->NuevoTiposCuadrantesPOdont();
			return true;
		}
		else
		{
		}

	}

}//fin de la clase
?>
