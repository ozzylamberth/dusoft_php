<?php
	$VISTA = "HTML";
	$_ROOT="../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";
	
	class procesos_admin extends rs_server
	{

       /************************************************************************************
		*
		*************************************************************************************/
		function Tipo_Afiliado($plan_id)
        {
			$sql .= "SELECT DISTINCT A.tipo_afiliado_nombre,";
			$sql .= "		A.tipo_afiliado_id ";
			$sql .= "FROM	tipos_afiliado A,";
			$sql .= "		planes_rangos B ";
			$sql .= "WHERE	B.plan_id= ".$plan_id[0]." ";
			$sql .= "AND 	B.tipo_afiliado_id = A.tipo_afiliado_id ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$vars[] = "SELECCIONAR-SELECCIONAR";
			while(!$rst->EOF)
			{
				$vars[] = $rst->fields[0]."-".$rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();

			return implode('~',$vars);

        }
        /************************************************************************************
        *
        *************************************************************************************/
		function Niveles($plan_id)
		{			
			$sql .="SELECT DISTINCT rango ";
			$sql .="FROM 	planes_rangos ";
			$sql .="WHERE 	plan_id= ".$plan_id[0]." ";
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$niveles[] = "SELECCIONAR";
			while(!$rst->EOF)
			{
				$niveles[]=$rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();

			return implode('~',$niveles);

		}
		/************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta 
		* sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
    	
	}    	
	
	$oRS = new procesos_admin( array( 'Tipo_Afiliado','Niveles'));

	// el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
	$oRS->action();

?>