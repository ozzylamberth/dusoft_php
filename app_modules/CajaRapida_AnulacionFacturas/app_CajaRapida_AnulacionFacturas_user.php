<?php
	/**************************************************************************************  
	* $Id: app_CajaRapida_AnulacionFacturas_user.php,v 1.5 2006/05/19 22:07:56 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.5 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	class app_CajaRapida_AnulacionFacturas_user extends classModulo
	{
		/**
		* @var $action Variable donde se guardan los action de las formsa
		**/
		var $action = array();
		/**
		* @var $Rta Variable donde se guardan los resultados de las consultas
		**/
		var $Rta = array();
		/**
		* @var $request Variable donde se guardan los valores de los request que se usaran en el HTML
		**/
		var $request = array();
		/**
		*  @var $Mensaje Variable para los mensajes
		**/
		var $Mensaje = "";
		
		function app_CajaRapida_AnulacionFacturas_user()
		{
			return true;
		}
		/**********************************************************************************
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos
		* 
		* @params int	$cajaid Identificacion de la caja para obtener los permisos 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function ObtenerPermisos($cajaid = null)
		{
			$datos = "";
			$this->action[0] = ModuloGetURL('system','Menu','user');

			$sql .= "SELECT caja_id ";
			$sql .= "FROM 	userpermisos_cajas_rapidas_anulacion ";
			$sql .= "WHERE usuario_id = ".UserGetUID()." ";
			
			if($cajaid != null) $sql .= "AND		caja_id = ".$cajaid." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos = $rst->fields[0];
				$rst->MoveNext();
		  }
			$rst->Close();
			
			if($datos != "")
				return true;
			else
				return false;
		}
		/**********************************************************************************
		* Funcion donde se crean las variables usadas en la funcion 
		* FormaBuscarFactursAnulacion y se obtienen los prefijos para llenar el buscador de
		*
		***********************************************************************************/
		function BuscarFacturasAnulacion()
		{
			$this->request[0] = $_REQUEST['prefijo_factura'];
			$this->request[1] = $_REQUEST['factura_fiscal'];			
			
			if($this->request[1] != "")
			{
				$factura = $this->ObtenerInformacionFacturaContado($this->request[0],$this->request[1]);
				$this->Rta[0] = $factura; 
				
				if(sizeof($factura) > 0)
				{				
					$permisos = $this->ObtenerPermisos($factura['caja_id']);
					if($permisos)
					{
						if($factura['cierre_caja_id'] == null)
						{
							$credito = $this->ObtenerInformacionFacturaCredito($factura['numerodecuenta']);
							$this->Rta[1] = $credito; 
						}
						else
						{
							$this->Mensaje  = "LA FACTURA CONTADO ".$this->request[0]." ".$this->request[1].",";
							$this->Mensaje .= "NO SE PUEDE ANULAR, PORQUE POSEE UN CIERRE DE CAJA HECHO";
						}
					}
					else
					{
						$this->Mensaje  = "LA FACTURA CONTADO ".$this->request[0]." ".$this->request[1].",";
						$this->Mensaje .= "FACTURADA EN  LA CAJA RAPIDA DE ".$factura['descripcion'].", ";
						$this->Mensaje .= "NO SE PUEDE ANULAR POR FALTA DE PERMISOS";
					}
				}
				else
				{
					$this->Mensaje = "LA FACTURA CONTADO ".$this->request[0]." ".$this->request[1].", NO SE ENCONTRO";
				}
			}
			
			$datos = array("opcion"=>"1","prefijo_factura"=>$this->request[0],"factura_fiscal"=>$this->request[1], "empresa"=>$this->Rta[0]['empresa_id'],
										 "cuenta"=>$this->Rta[0]['numerodecuenta'],"ingreso"=>$this->Rta[0]['ingreso'],"prefijo"=>$this->Rta[0]['prefijo'],
										 "factura"=>$this->Rta[0]['factura_fiscal'],"aprefijo"=>$this->Rta[1]['prefijo'],"afactura"=>$this->Rta[1]['factura_fiscal']);
			$this->action[0] = ModuloGetURL('system','Menu','user');
			$this->action[1] = ModuloGetURL('app','CajaRapida_AnulacionFacturas','user','FormaBuscarFacturasAnulacion');
			$this->action[2] = ModuloGetURL('app','CajaRapida_AnulacionFacturas','user','EvaluarPrincipal',array("datos"=>$datos));
			
			$tipoDoc = ModuloGetVar('app','CajaRapida_AnulacionFacturas','tipo_doc');
			$this->Pref = $this->ObtenerPrefijos($tipoDoc);

		}
		/********************************************************************************** 
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @return array datos de la tabla documentos
		***********************************************************************************/
		function ObtenerPrefijos($tipoDoc)
		{	
			$sql  = "SELECT DISTINCT prefijo ";
			$sql .= "FROM 	fac_facturas_contado ";
			$sql .= "ORDER BY 1 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;  		       		
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una factura contado y la cuenta 
		* asociada a ella
		*
		* @params char 	$prefijo Prefijo de la factura contado
		* @params int		$numerofactura Numero de la factura
		* @return array Datos de la factura
		***********************************************************************************/
		function ObtenerInformacionFacturaContado($prefijo,$numerofactura)
		{
			$datos = array();
			
			$sql .= "SELECT	FC.prefijo,";
			$sql .= "				FC.factura_fiscal,";
			$sql .= "				FC.caja_id,";
			$sql .= "				FC.cierre_caja_id, ";
			$sql .= "				FC.empresa_id, ";
			$sql .= "				CR.descripcion, ";
			$sql .= "				CU.numerodecuenta, ";
			$sql .= "				CU.total_cuenta, ";
			$sql .= "				CU.valor_nocubierto,";
			$sql .= "				CU.valor_cubierto, ";
			$sql .= "				CU.ingreso, ";
			$sql .= "				PL.plan_descripcion, ";
			$sql .= "				PA.tipo_id_paciente ||' '||PA.paciente_id AS identificacion, ";
			$sql .= "				PA.primer_nombre ||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos ";
			$sql .= "FROM		fac_facturas_contado FC, ";
			$sql .= "				cajas_rapidas CR, ";
			$sql .= "				fac_facturas_cuentas FF, ";
			$sql .= "				fac_facturas FS, ";
			$sql .= "				cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	FC.prefijo = '".$prefijo."' ";
			$sql .= "AND		FC.factura_fiscal = ".$numerofactura." ";
			$sql .= "AND		FC.caja_id = CR.caja_id ";
			$sql .= "AND		FC.prefijo = FF.prefijo ";
			$sql .= "AND		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		FC.empresa_id = FF.empresa_id ";
			$sql .= "AND		FC.prefijo = FS.prefijo ";
			$sql .= "AND		FC.factura_fiscal = FS.factura_fiscal ";
			$sql .= "AND		FC.empresa_id = FS.empresa_id ";
			$sql .= "AND		FS.estado = '0' ";
			$sql .= "AND		FF.numerodecuenta = CU.numerodecuenta ";
			$sql .= "AND		CU.ingreso = IG.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$datos_factura = array();
			if(sizeof($datos) == 1) $datos_factura = $datos[0];
			
			return $datos_factura;
		}
		/**********************************************************************************
		* Funcion donde se obtiene informacion de la factura credito y el tercero
		* 
		* @params int $numerodecuenta Numero de cuenta
		* @return array datos de la factura
		***********************************************************************************/
		function ObtenerInformacionFacturaCredito($numerodecuenta)
		{
			$datos = array();
			
			$sql .= "SELECT	FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				TE.nombre_tercero ";
			$sql .= "FROM		fac_facturas FF, ";
			$sql .= "				fac_facturas_cuentas FC, ";
			$sql .= "				terceros TE ";
			$sql .= "WHERE	FC.numerodecuenta = ".$numerodecuenta." ";
			$sql .= "AND		FC.prefijo = FF.prefijo ";
			$sql .= "AND		FC.empresa_id = FF.empresa_id ";
			$sql .= "AND		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "AND		FF.tercero_id = TE.tercero_id ";
			$sql .= "AND		FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		FF.sw_clase_factura = '1' ";
			$sql .= "AND		FF.estado <> '2' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos[0];
		}
		/**********************************************************************************
		* Funcion donde se obtienen los motivos de anulacion para las facturas
		* hospitalaria
		* 
		* @return array informacion de los motivo de anulacion de las facturas
		***********************************************************************************/
		function ObtenerMotivosAnulacion()
		{
			$datos = array();
			
			$sql .= "SELECT	motivo_anulacion_id,";
			$sql .= "				motivo_descripcion ";
			$sql .= "FROM		cajas_rapidas_motivos_anulaciones ";
			$sql .= "WHERE	sw_activo = '1' ";
			$sql .= "ORDER BY 2 ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/**********************************************************************************
		* funcion donde se realiza el proceso de anulacion de una factura y se evaluan los
		* request de los datos obligatorios
		* 
		* @return boolean Indica si la factura se anulo o no se anulo
		***********************************************************************************/
		function AnularFactura()
		{
			$this->request[2] = $_REQUEST['motivo_anula'];
			$this->request[3] = $_REQUEST['observacion'];
			$this->request[4] = $_REQUEST['opcion'];
			
			$datos = $_REQUEST['datos'];
			$_REQUEST = $datos;
			
			$this->parametro = "MensajeError";
			if($this->request[2] == '0')
			{
				$this->frmError['MensajeError'] = "SE DEBE SELECCIONAR UN MOTOVO DE ANULACIÓN";
				return false;
			}
			if($this->request[3] == "")
			{
				$this->frmError['MensajeError'] = "SE DEBE INGREAR UNA OBSERVACIÓN A LA ANULACIÓN DE LA FACTURA";
				return false;
			}
			if($this->request[4] == "")
			{
				$this->frmError['MensajeError'] = "SE DEBE ESCOGER UNA OPCIÓN";
				return false;
			}
			$result = true;
			$result = $this->AnularFacturaContadoCredito($datos['prefijo'],$datos['factura'],$this->request[4],$datos['ingreso'],$datos['cuenta']);
			if($result)
			{
				if($datos['aprefijo'])
					$result = $this->AnularFacturaContadoCredito($datos['aprefijo'],$datos['afactura']);
				
				if($result)
				{
					$sql  = "INSERT INTO cajas_rapidas_auditoria_anulaciones ";
					$sql .= "				(empresa_id ,";
					$sql .= "				prefijo ,";
					$sql .= "				factura_fiscal ,";
					$sql .= "				prefijo_referencia ,";
					$sql .= "				factura_fiscal_referencia,";
					$sql .= "				numerodecuenta ,";
					$sql .= "				observacion ,";
					$sql .= "				motivo_anulacion_id,";
					$sql .= "				sw_cuenta	,";
					$sql .= "				usuario_id,";
					$sql .= " 			fecha_registro ) ";
					$sql .= "VALUES (";
					$sql .= "				'".$datos['empresa']."' ,";
					$sql .= "				'".$datos['prefijo']."' ,";
					$sql .= "				 ".$datos['factura'].",";
					if($datos['aprefijo'])
					{
						$sql .= "				'".$datos['aprefijo']."' ,";
						$sql .= "				 ".$datos['afactura'].",";
					}
					else
					{
						$sql .= "				NULL,";
						$sql .= "				NULL,";
					}
					$sql .= "				 ".$datos['cuenta']." ,";
					$sql .= "				'".$this->request[3]."' ,";
					$sql .= "				 ".$this->request[2].",";
					$sql .= "				'".$this->request[4]."',";
					$sql .= "				 ".UserGetUID().", ";
					$sql .= "				 NOW() ";
					$sql .= "				);";
					
					if(!$rst = $this->ConexionBaseDatos($sql)) return false;
					
					if($result)
					{
						$this->Mensaje  = "LA FACTURA CONTADO ".$datos['prefijo']." ".$datos['factura'].", ";
						$this->Mensaje .= "SE HA ANULADO ";
						$this->action[0] = ModuloGetURL('app','CajaRapida_AnulacionFacturas','user','FormaBuscarFacturasAnulacion');
					}
				}
				else if($this->Mensaje != "")
				{
					unset($_REQUEST);
					unset($this->request);
				}
			}
			return $result;
		}
		/**********************************************************************************
		* funcion donde se anula una factura sea contado o credito y se llama a las funciones 
		* que anulan o liberan una cuenta segun lo se indique
		*
		* @params char 	$prefijo Prefijo de la factura que se va a anular
		* @params int		$factura Numero de la factura que se va a anular
		* @params char	$opcion	 Indica si la la cuenta se va a anular o a liberar
		* @params int		$ingreso Numero del ingreso asociado a la factura que se anulara
		* @parmas int 	$cuenta	 Numero de cuenta asociado a la factura que se anulara
		* @return boolean Indica si la factura se anulo o no
		***********************************************************************************/
		function AnularFacturaContadoCredito($prefijo,$factura,$opcion,$ingreso,$cuenta)
		{
			$result = true;
			if($opcion == '0')
				$result = $this->AnularCuenta($ingreso,$cuenta);
			else if($opcion == '1')
				$result = $this->LiberarCuenta($cuenta,$ingreso);
			
			if($result)
			{
				$sql  = "UPDATE fac_facturas ";
				$sql .= "SET 		estado = '2' ";
				$sql .= "WHERE 	prefijo = '".$prefijo."' ";
				$sql .= "AND		factura_fiscal = ".$factura." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				$sql  = "UPDATE fac_facturas_contado ";
				$sql .= "SET 		estado = '2' ";
				$sql .= "WHERE 	prefijo = '".$prefijo."' ";
				$sql .= "AND		factura_fiscal = ".$factura." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			}
			else
			{
				$this->Mensaje .= "LA CUENTA ASOCIADA A LA FACTURA CONTADO ".$prefijo." ".$factura.", NO SE PUEDE ANULAR";
			}
			
			return $result;
		}
		/**********************************************************************************
		* Funcion donde se hace el proceso de liberar una cuenta 
		* 
		* @params int $cuenta Numero de cuenta
		* @params int $ingreso Numero de ingreso asociado a la cuenta
		* @return boolean Indica si la cuenta se libero o no 
		***********************************************************************************/
		function LiberarCuenta($cuenta,$ingreso)
		{
			$sql  = "UPDATE cuentas ";
			$sql .= "SET 		estado = '1' ";
			$sql .= "WHERE 	numerodecuenta = ".$cuenta." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$sql  = "UPDATE ingresos ";
			$sql .= "SET 		estado='0',";
			$sql .= "				fecha_cierre='now()' ";
			$sql .= "WHERE 	ingreso = ".$ingreso." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion donde se hace el proceso de anular una cuenta 
		* 
		* @params int $cuenta Numero de cuenta
		* @params int $ingreso Numero de ingreso asociado a la cuenta
		* @return boolean Indica si la cuenta se anulo o no 
		***********************************************************************************/
		function AnularCuenta($ingreso,$cuenta)
		{
			$result = true;
			$sql = "SELECT COUNT(*) FROM cuentas_detalle WHERE numerodecuenta = ".$cuenta." ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

      if($rst->fields[0] > 0 && !$rst->EOF)
      	$result = $this->AnularOrdenServicio($cuenta);
						
			if($result)
			{				
				if(!$rst = $this->ConexionBaseDatos($sql))	return false;
				
				$sql  = "UPDATE ingresos ";
				$sql .= "SET 		estado='0',";
				$sql .= "				fecha_cierre='NOW()' ";
				$sql .= "WHERE 	ingreso = ".$ingreso." ";
					
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				
				$sql  = "UPDATE cuentas ";
				$sql .= "SET 		estado='5' ";
				$sql .= "WHERE 	numerodecuenta = ".$cuenta." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;     
			}
			return $result;
		}
		/**********************************************************************************
		* Funcion donde se anulan las ordenes de servicio segun sean manuales o del sistema
		* 
		* @params	int	$cuenta	Numero de cuenta asociado a la orden de servicio
		* @return boolean Indioca si se anulo o no la orden de servicio
		***********************************************************************************/
		function AnularOrdenServicio($cuenta)
		{
			$cadena = "";
			$datos = array();
			
			$sql .= "SELECT	HS.evolucion_id,";
			$sql .= "				OM.hc_os_solicitud_id ";
			$sql .= "FROM		hc_os_solicitudes HS, ";
			$sql .= "				os_maestro OM ";
			$sql .= "WHERE	OM.hc_os_solicitud_id = HS.hc_os_solicitud_id ";
			$sql .= "AND		OM.numerodecuenta = ".$cuenta." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$cadena .= $rst->fields[1]." ";
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			
			$estado = "1";
			if($datos[0]['evolucion_id']) $estado = "8";
			
			$sql  = "UPDATE	hc_os_solicitudes ";
			$sql .= "SET		sw_estado = '2' ";
			$sql .= "WHERE	hc_os_solicitud_id IN (".$cadena."); ";
			
			$sql .= "UPDATE	os_maestro ";
			$sql .= "SET		sw_estado = '".$estado."' ";
			$sql .= "WHERE	numerodecuenta = ".$cuenta."; ";
			
			$sql .= "UPDATE os_maestro_cargos ";
			$sql .= "SET		transaccion = NULL ";
			$sql .= "WHERE	transaccion IN (";
			$sql .= "				SELECT	transaccion ";
			$sql .= "				FROM		cuentas_detalle ";
			$sql .= "				WHERE		numerodecuenta = ".$cuenta." );";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			return true;
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
	}
?>