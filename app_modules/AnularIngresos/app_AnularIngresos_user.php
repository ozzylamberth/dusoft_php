<?php
	/**************************************************************************************
	* $Id: app_AnularIngresos_user.php,v 1.1 2006/02/27 19:24:38 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo Freddy Manrique Arango
	*
	* MODULO TEMPORAL PARA ANULAR INGRESOS
	***************************************************************************************/
	class app_AnularIngresos_user extends classModulo 
	{
		function app_AnularIngresos_user()
		{
			$this->frmError=array();
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function AnularIngresos()
		{
			$this->Ingreso = $_REQUEST['ingreso'];
			$this->action1 = ModuloGetURL('system','Menu','user');
			$this->action2 = ModuloGetURL('app','AnularIngresos','user','main');
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ObtenerInfoIngreso()
		{
			$sql  = "SELECT	IG.ingreso, ";
			$sql .= "				TO_CHAR(IG.fecha_ingreso,'DD/ MM/ YYYY') AS fecha_ingreso, ";
			$sql .= "				PC.paciente_id, ";
			$sql .= "				PC.tipo_id_paciente, ";
			$sql .= "				PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,";
			$sql .= "				PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres ";
			$sql .= "FROM		ingresos IG, pacientes PC,vias_ingreso VI ";
			$sql .= "WHERE	IG.ingreso = '".$this->Ingreso."' ";
			$sql .= "AND		IG.estado IN ('1','2') ";
			$sql .= "AND		IG.paciente_id = PC.paciente_id ";
			$sql .= "AND		IG.tipo_id_paciente = PC.tipo_id_paciente ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return true;
			
			while(!$rst->EOF)
			{
				$this->Datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
				
			$sql  = "SELECT CU.numerodecuenta, ";
			$sql .= "				CU.total_cuenta, ";
			$sql .= "				CE.descripcion, ";
			$sql .= "				PL.plan_descripcion ";
			$sql .=	"FROM 	cuentas CU, ";
			$sql .=	"				planes PL, ";
			$sql .= "				cuentas_estados CE ";
			$sql .= "WHERE	CU.ingreso = ".$this->Ingreso." ";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			$sql .= "AND		CU.estado = CE.estado ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return true;
			
			while(!$rst->EOF)
			{
				$this->Cuentas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return true;
		}
		/*************************************************************************************************
		* Funcion donde se toma de la base de datos la informacion de la cuenta 
		* 
		* @return boolean 
		**************************************************************************************************/
		function MostrarInformacionGlosarCuenta()
		{	
			$this->Cuenta = $_REQUEST['cuenta'];
			$this->Ingreso = $_REQUEST['ingreso'];
			
			$arreglo['ingreso'] = $this->Ingreso;
			
			$this->action1 = ModuloGetURL('app','AnularIngresos','user','main',$arreglo);
			$this->ObtenerInformacionDetalleCuenta();
		}
		/***************************************************************************************************
		* Funcion donde se toma de la base de datos las cuentas con la descripcion de las mismas 
		* 
		* @return array datos de las cuentas
		****************************************************************************************************/
		function ObtenerInformacionDetalleCuenta()
		{	
			$sql  = "SELECT PL.plan_descripcion ";
			$sql .= "FROM		planes PL, ";
			$sql .= "		   	cuentas CU ";
			$sql .= "WHERE 	CU.numerodecuenta = ".$this->Cuenta." ";
			$sql .= "AND 		CU.ingreso = ".$this->Ingreso." ";
			$sql .= "AND 		CU.plan_id = PL.plan_id ";
			
			if(!$rst= $this->ConexionBaseDatos($sql))
				return false;
				
			while (!$rst->EOF)
			{
				$this->DatosCuenta  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
	    }
			$rst->Close();
			return $datos;
		}
		/****************************************************************************************
		* Funcion donde se obtienen los cargos que estan glosados y los que no pettenecientes a 
		* una cuenta 
		* 
		* @return array  
		*****************************************************************************************/
		function ObtenerCargosCuenta()
		{
			$sql  = "SELECT	TO_CHAR(CU.fecha_registro,'DD/MM/YYYY') AS registro, ";
			$sql .= "				CU.transaccion,  ";
			$sql .= "				CU.cargo_cups, ";
			$sql .= "				TA.tarifario_id,  ";
			$sql .= "				TA.descripcion,"; 
			$sql .= "				CU.codigo_agrupamiento_id AS agrupado ";
			$sql .= "FROM		tarifarios_detalle TA, ";
			$sql .= "				cuentas_detalle CU ";
			$sql .= "WHERE 	CU.numerodecuenta = ".$this->Cuenta." ";
			$sql .= "AND 		CU.facturado = '1' ";
			$sql .= "AND 		TA.cargo = CU.cargo ";
			$sql .= "AND 		TA.tarifario_id = CU.tarifario_id ";
			$sql .= "AND		CU.tarifario_id <> 'SYS' ";	
			$sql .= "ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while (!$rst->EOF)
			{
				$datos[]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/****************************************************************************************
		* Funcion donde se muestran los insumos que han sidoglosados y los que no 
		* 
		* @return array 
		****************************************************************************************/
		function ObtenerInsumosCuenta()
		{
			$sql  = "SELECT	IM.codigo_producto, "; 
			$sql .= "				IM.descripcion, ";
			$sql .= "				SUM(CU.cantidad) AS cantidad ";
			$sql .= "FROM		bodegas_documentos_d BD, ";
			$sql .= "				cuentas_detalle CU , ";
			$sql .= "				inventarios_productos IM ";
			$sql .= "WHERE	CU.numerodecuenta = ".$this->Cuenta." ";
			$sql .= "AND		CU.consecutivo = BD.consecutivo ";
			$sql .= "AND		BD.codigo_producto = IM.codigo_producto ";
			$sql .= "GROUP BY 1,2 ";
			$sql .= "ORDER BY 1 ";
								
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while (!$rst->EOF)
			{
				$datos[]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/****************************************************************************************
		*
		*****************************************************************************************/
		function ObtenerActoQuirurgico($transaccion,$agrupado)
		{
			$sql .= "SELECT CP.descripcion ";
			$sql .= "FROM		cups CP, ";
			$sql .= "				cuentas_cargos_qx_procedimientos CQ, ";
			$sql .= "				cuentas_liquidaciones_qx CL, ";
			$sql .= "				cuentas_codigos_agrupamiento CA ";
			$sql .= "WHERE	CA.codigo_agrupamiento_id = ".$agrupado." ";
			$sql .= "AND		CA.cuenta_liquidacion_qx_id = CL.cuenta_liquidacion_qx_id ";
			$sql .= "AND		CL.cargo_principal = CP.cargo ";
			$sql .= "AND		CL.cuenta_liquidacion_qx_id = CQ.cuenta_liquidacion_qx_id ";
			$sql .= "AND		CQ.transaccion = ".$transaccion." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if (!$rst->EOF)
			{
				$descripcion = $rst->fields[0];
		  }
			$rst->Close();
			return $descripcion;
		}
		/*************************************************************************************************
		* Funcion donde se toma de la base de datos la informacion de la cuenta 
		* 
		* @return boolean 
		**************************************************************************************************/
		function MostrarInformacion()
		{	
			$this->Cuenta = $_REQUEST['cuentas'];
			$this->Ingreso = $_REQUEST['ingreso'];
			
			$arreglo['ingreso'] = $this->Ingreso;
			$this->action1 = ModuloGetURL('app','AnularIngresos','user','main',$arreglo);
			
			$arreglo['cuentas'] = $this->Cuenta;
			$this->action2 = ModuloGetURL('app','AnularIngresos','user','FormaAnularIngreso',$arreglo);
			
			$this->Informacion = "ESTA SEGURO DE QUE DESRA ANULAR EL INGRESO Nº ".$this->Ingreso." ";
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function AnularIngresoBD()
		{
			$this->Cuentas = $_REQUEST['cuentas'];
			$this->Ingreso = $_REQUEST['ingreso'];
			
			$sql  = "SELECT cama,movimiento_id FROM movimientos_habitacion ";
			$sql .= "WHERE 	ingreso=".$this->Ingreso." AND fecha_egreso ISNULL ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if(!$rst->EOF)
			{
				$sql1 .= "UPDATE camas	SET estado = '1' WHERE cama = '".$rst->fields[0]."'; ";
				$sql1 .= "DELETE FROM movimientos_habitacion WHERE	 ingreso = ".$this->Ingreso."; ";				
			}

			$sql1 .= "DELETE from pendientes_x_hospitalizar WHERE ingreso=".$this->Ingreso."; ";			
			$sql1 .= "DELETE FROM pacientes_urgencias WHERE ingreso=".$this->Ingreso."; ";
									
			$sql1 .= "UPDATE cuentas SET estado='5' WHERE ingreso=".$this->Ingreso."; ";										
			$sql1 .= "UPDATE ingresos SET estado='5',fecha_cierre='now()' WHERE ingreso=".$this->Ingreso."; ";
			
			for($i=0; $i<sizeof($this->Cuentas); $i++)
			{
				$sql1 .= "DELETE FROM estaciones_enfermeria_ingresos_pendientes ";
				$sql1 .= "WHERE	numerodecuenta = ".$this->Cuentas[$i]."; ";
				
				$sql1 .= "DELETE FROM estacion_enfermeria_qx_pendientes_ingresar ";
				$sql1 .= "WHERE	numerodecuenta = ".$this->Cuentas[$i]."; ";
			}
			
			$sql1 .= "DELETE FROM ordenes_hospitalizacion WHERE ingreso = ".$this->Ingreso."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql1))
				return false;
			
			$this->Informacion = "EL INGRESO Nº ".$this->Ingreso." FUE ANULADO ";
			$this->action1 = ModuloGetURL('app','AnularIngresos','user','main');
			
			return true;
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
?>
