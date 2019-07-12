<?php

class app_CapitacionCoomeva_user extends classModulo
{

	function app_CapitacionCoomeva_user()
	{
		return true;
	}

	function main()
	{
    if($this->FormaPrincipal()==false)
		{
			return false;
		}
		/*if($this->forma2('2004-02-24')==false)
		{
			return false;
		}*/
		/*if($this->forma3('2004-02-24','2004-03-10')==false)
		{
			return false;
		}*/
		return true;
	}

	function escribir($fw,$texto)
	{
		fwrite($fw,$texto);
	}

	function DatosCoomeva()
	{
		list($dbconn) = GetDBconn();
		$sql="select fecha_radicacion,sw_estado from informacion_bd where plan_id=".$_REQUEST['Responsable'].";";
		$result = $dbconn->Execute($sql);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while (!$result->EOF)
		{
			$var[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		return $var;
	}

	function FormaUrgencias()
	{
		if($this->CreacionUrgencias()==false)
		{
			return false;
		}
		return true;
	}

	function FormaUrgenciasContinuacion()
	{
		if($this->CreacionUrgenciasContinuacion()==false)
		{
			return false;
		}
		return true;
	}

	/**
  * Busca los diferentes tipos de responsable (planes)
    * @access public
    * @return array
    */
        function responsables()
        {
					$datos=ModuloGetVar('app','CapitacionCoomeva','coomeva');
					$datos=explode(',',$datos);
					list($dbconn) = GetDBconn();
					$query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
													WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now() and tercero_id='".$datos[1]."' and tipo_tercero_id='".$datos[0]."';";
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Cargar el Modulo";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					while (!$result->EOF) {
									$var[]=$result->GetRowAssoc($ToUpper = false);
									$result->MoveNext();
					}
					$result->Close();
					return $var;
        }

}//end of class

?>
