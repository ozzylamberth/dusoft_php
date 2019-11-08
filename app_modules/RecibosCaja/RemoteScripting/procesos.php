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
		function Bancos($banco)
    {
			$sql  = "SELECT BC.banco,";
			$sql .= "				BC.numero_cuenta ";
			$sql .= "FROM		bancos_cuentas BC ";
			$sql .= "WHERE	BC.banco = '".$banco[0]."' ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$vars[] = "NC*---------SELECCIONAR---------";
			while(!$rst->EOF)
			{
				$vars[] = $rst->fields[0]."*".$rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();

			return implode('~',$vars);

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