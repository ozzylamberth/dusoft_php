<?php

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}

  class Permisos extends ConexionBD
  {
    /************************
    *     Constructor
    ************************/
    function Permisos(){}
		
    /**************************************************************************************
	* Busca las empresas a las que tiene permiso el usuario
	* @return array
	***************************************************************************************/
	function BuscarPermisos()
	{
		/*$sql = "SELECT	
							   e.razon_social AS Empresa,
							   e.empresa_id
					FROM  userpermisos_pqrs up,
							   empresas e
				  WHERE up.usuario_id =".UserGetUID()."
					   AND e.empresa_id = up.empresa_id ORDER BY e.razon_social ASC;";*/

	   	/*$sql = "SELECT DISTINCT
				a.razon_social AS Empresa,
				a.empresa_id
				FROM
				empresas AS a
				JOIN inv_bodegas_userpermisos as b ON (a.empresa_id = b.empresa_id)
				WHERE TRUE
				AND a.sw_activa = '1'
				AND b.usuario_id = '".UserGetUID()."'";*/

		$sql = "SELECT DISTINCT
				a.razon_social AS Empresa,
				a.empresa_id
				FROM
				empresas AS a
				JOIN centros_utilidad AS b ON (a.empresa_id = b.empresa_id)
				JOIN bodegas AS c ON (b.empresa_id = c.empresa_id) AND b.centro_utilidad = c.centro_utilidad
				JOIN userpermisos_pedidos_recibidos AS d ON (c.empresa_id = d.empresa_id) AND c.centro_utilidad = d.centro_utilidad AND c.bodega = d.bodega
				WHERE TRUE
				AND a.sw_activa = '1'
				AND d.usuario_id = '".UserGetUID()."'";
		//echo $sql;
		if(!$rst = $this->ConexionBaseDatos($sql)) 
		return false;

		$datos = array();
		
		while(!$rst->EOF)
		{
			$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
	}


	function ColocarBodegas($usuario_id=null, $empresa_id)
    {
        if(empty($empresa_id))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = "FALTA ARGUMENTO [empresa_id]";
            return false;
        }

        if(empty($usuario_id))
        {
            $usuario_id = UserGetUID();
        }

        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $sql = "SELECT
                    a.*,
                    b.descripcion as nom_bodega
                FROM
                (
                    SELECT DISTINCT
                        empresa_id,
                        centro_utilidad,
                        bodega
                    FROM userpermisos_pedidos_recibidos
                    WHERE usuario_id = $usuario_id AND empresa_id = '$empresa_id'
                ) as a,
                bodegas as b
                WHERE b.empresa_id=a.empresa_id
                AND  b.centro_utilidad =a.centro_utilidad
                AND  b.bodega = a.bodega
                ORDER BY nom_bodega
        ";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($sql);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            $this->mensajeDeError = $dbconn->ErrorMsg();
            return false;
        }

        $retorno = array();

        while($fila = $result->FetchRow())
        {
            $retorno[]=$fila;
        }
        $result->Close();

        return  $retorno;
    }
		
  }
?>