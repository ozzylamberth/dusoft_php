<?php
/**
 * $Id: procesos.php,v 1.1 2006/01/18 20:28:13 ehudes Exp $
 *
 * @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 * @package IPSOFT-SIIS
 * 
 * Clase para hacer remote scripting en el modulo del censo
 */
$VISTA = "HTML";
$_ROOT="../../../";
include  "../../../classes/rs_server/rs_server.class.php";
include  "../../../includes/enviroment.inc.php";

/**
 * Clase con los metodos para el filtro del buscador del censo
 * 
 *
 * @author   Ehudes Garcia <efgarcia@ipsoft-sa.com>
 * @version   $Revision: 1.1 $
 * @package   IPSOFT-SIIS
 */
class procesosCenso extends rs_server 
{
	/**
	 * Retorna el objeto select con las estaciones correspondientes 
	 * a los parametros que se le pasa(DeparamentoId)
	 *
	 * @param Array parametros
	 * @return string
	 * @access private
	 */
	function GetEstaciones($parametros)
	{
		$DepartamentoId = $parametros[0];
		if($DepartamentoId != "")
		{
			$sqlFiltro1 = " D.departamento='$DepartamentoId' AND ";
		}
		else
			$sqlFiltro1 = "";
		$query = "
			SELECT 
				EE.estacion_id, 
				EE.descripcion
			FROM 
				estaciones_enfermeria EE,
				departamentos D
			WHERE 
				EE.departamento=D.departamento AND
				$sqlFiltro1
				D.empresa_id = '".$_SESSION['CENSO']['EMPRESA']['ID']."'
			ORDER BY 
				EE.descripcion;";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener las estaciones de enfermería.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		else
		{
			while ($data = $result->FetchRow())
			{
				$Estaciones[] = $data;
			}
			$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		}
		$salida .= "<select name=\"estacion\" class=\"select\">\n";
		$salida .= "<option value=\"\">--TODAS--</option>\n";
		foreach($Estaciones as $key => $value)
		{
			$salida .= "<option value=\"".$value['estacion_id']."\" >".$value['descripcion']."</option>\n";
		}
		$salida .= "</select>\n";
		$_SESSION['CENSO']['FRMCENSO']['DPTOELEGIDO']=$DepartamentoId;
		return $salida;
	}//function GetEstaciones
	
	/**
	 * Retorna el objeto select con los planes correspondientes
	 * a los parametros que se le pasan(TipoTerceroId,TerceroId)
	 *
	 * @param Array parametros
	 * @return string
	 * @access private
	 */
	function GetPlanes($parametros)
	{
		if($parametros[0]!="")
		{
			list($TerceroId,$TipoTerceroId)=explode('.-.',$parametros[0]);
		}
		else
		{
			$TipoTerceroId = "";
			$TerceroId = "";
		}
		if(!empty($TipoTerceroId))
		{
			$sqlFiltro = " AND T.tipo_id_tercero='$TipoTerceroId' AND T.tercero_id='$TerceroId'" ;
		}
		$query = "
			SELECT DISTINCT
				P.plan_id,
				P.plan_descripcion
			FROM
				terceros T,
				planes P,
				cuentas C
			WHERE 
				P.tipo_tercero_id = T.tipo_id_tercero AND
				P.tercero_id = T.tercero_id AND
				C.plan_id = P.plan_id AND
				C.empresa_id = '".$_SESSION['CENSO']['EMPRESA']['ID']."' AND
				C.estado = '1' 
				$sqlFiltro
			ORDER BY
				2";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al ejecutar la conexion";
			$this->mensajeDeError = "Error al intentar obtener los terceros.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
			return false;
		}
		while ($data = $result->FetchRow())
		{
			$planes[] = $data;
		}
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		$salida .= "<select name=\"plan\" class=\"select\">\n";
		$salida .= "<option value=\"\">--TODOS--</option>";
		for($i=0; $i<sizeof($planes); $i++)
		{
			if(strcmp($planes[$i]['plan_id'],$ItemBusqueda)==0) {$selected4 = "selected";} else $selected4 = "";
			$salida .= "<option value=\"".$planes[$i]['plan_id']."\" $selected4>".$planes[$i]['plan_descripcion']."</option>\n";
		}
		$this->salida .= "</select>\n";
		$_SESSION['CENSO']['FRMCENSO']['TERCEROELEGIDO']['ID']=$TerceroId;
		$_SESSION['CENSO']['FRMCENSO']['TERCEROELEGIDO']['TIPOID']=$TipoTerceroId;
		return $salida;
	}//Fin GetPlanes
}//Fin clase
$oRS = new procesosCenso(array( 'GetEstaciones','GetPlanes'));
$oRS->action();
?>