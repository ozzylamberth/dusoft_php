<?php
	/**************************************************************************************  
	* $Id: app_Cajas_AnulacionRecibos_user.php,v 1.1 2006/05/09 19:43:34 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.1 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	class app_Cajas_AnulacionRecibos_user extends classModulo
	{
		/**
		* Variable donde se guardan los action de las formsa
		**/
		var $action = array();
		/**
		* Variable donde se guardan los resultados de las consultas
		**/
		var $Rta = array();
		/**
		* Variable donde se guardan los valores de los request que se usaran en el HTML
		**/
		var $request = array();
		/**
		* Variable para los mensajes
		**/
		var $Mensaje = "";
		
		
		function app_Cajas_AnulacionRecibos_user()
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

			$sql .= "SELECT 	caja_id ";
			$sql .= "FROM 		userpermisos_cajas_anulaciones ";
			$sql .= "WHERE 	usuario_id = ".UserGetUID()." ";
			
			if($caja != null) $sql .= "AND 	caja_id = ".$cajaid." ";
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
		* FormaBuscarRecibosAnulacion y se obtienen los prefijos para llenar el buscador de
		* recibos de caja
		***********************************************************************************/
		function BuscarRecibosAnulacion()
		{
			$this->request[0] = $_REQUEST['prefijo_recibo'];
			$this->request[1] = $_REQUEST['recibo_caja'];			
			
			if($this->request[1] != "")
			{
				$recibo = $this->ObtenerInformacionRecibo($this->request[0],$this->request[1]);
				$this->Rta[0] = $recibo; 
				
				if(sizeof($recibo) > 0)
				{				
					$permisos = $this->ObtenerPermisos($recibo['caja_id']);
					if($permisos)
					{
						$cuentas = $this->ObtenerInformacionCuenta($recibo['numerodecuenta']);
						$this->Rta[1] = $cuentas; 
						if(sizeof($cuentas) == 0)
						{
							$this->Mensaje  = "EL RECIBO DE CAJA Nº ".$this->request[0]." ".$this->request[1].",";
							$this->Mensaje .= "NO SE PUEDE ANULAR, PORQUE LA CUENTA NO SE PUEDE MODIFICAR";
						}
					}
					else
					{
						$this->Mensaje  = "EL RECIBO DE CAJA Nº ".$this->request[0]." ".$this->request[1].", ";
						$this->Mensaje .= "NO SE PUEDE ANULAR POR FALTA DE PERMISOS ";
					}
				}
				else
				{
					$this->Mensaje = "EL RECIBO DE CAJA Nº ".$this->request[0]." ".$this->request[1].", NO SE ENCONTRO";
				}
			}
			
 			$datos = array("opcion"=>"1","prefijo_recibo"=>$this->request[0],"recibo_caja"=>$this->request[1], "empresa"=>$this->Rta[0]['empresa_id'],
										 "cuenta"=>$this->Rta[0]['numerodecuenta'],"centro"=>$this->Rta[0]['centro_utilidad'],"prefijo"=>$this->Rta[0]['prefijo'],
										 "recibo"=>$this->Rta[0]['recibo_caja']);
			$this->action[0] = ModuloGetURL('system','Menu','user');
			$this->action[1] = ModuloGetURL('app','Cajas_AnulacionRecibos','user','FormaBuscarRecibosAnulacion');
			$this->action[2] = ModuloGetURL('app','Cajas_AnulacionRecibos','user','EvaluarPrincipal',array("datos"=>$datos));
			
			$this->Pref = $this->ObtenerPrefijos();

		}
		/********************************************************************************** 
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @return array datos de la tabla documentos
		***********************************************************************************/
		function ObtenerPrefijos()
		{	
			$sql  = "SELECT DISTINCT prefijo ";
			$sql .= "FROM		rc_detalle_hosp ";
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
		* Funcion donde se obtiene la informacion del recibo de caja X
		* 
		* @params	char	$prefijo	Prefijo del recibo de caja
		* @params	int		$numerorecibo	NUmero del recibo de caja
		* @return array informacion del recibo de caja
		***********************************************************************************/
		function ObtenerInformacionRecibo($prefijo,$numerorecibo)
		{
			$datos = array();
			
			$sql .= "SELECT	RC.prefijo,";
			$sql .= "				RC.recibo_caja,";
			$sql .= "				RC.caja_id,";
			$sql .= "				RC.total_abono,";
			$sql .= "				RC.total_efectivo,";
			$sql .= "				RC.total_cheques,";
			$sql .= "				RC.total_tarjetas,";
			$sql .= "				RC.total_bonos,";
			$sql .= "				RC.empresa_id, ";
			$sql .= "				RC.centro_utilidad, ";
			$sql .= "				TO_CHAR(RC.fecha_registro,'DD/MM/YYY') AS fecha, ";
			$sql .= "				CU.descripcion, ";
			$sql .= "				RD.numerodecuenta, ";
			$sql .= "				TE.nombre_tercero ";
			$sql .= "FROM		recibos_caja RC, ";
			$sql .= "				centros_utilidad CU, ";
			$sql .= "				terceros TE, ";
			$sql .= "				rc_detalle_hosp RD ";
			$sql .= "WHERE	RC.prefijo = '".$prefijo."' ";
			$sql .= "AND		RC.recibo_caja = ".$numerorecibo." ";
			$sql .= "AND		RC.estado <> '1' ";
			$sql .= "AND		RC.prefijo = RD.prefijo ";
			$sql .= "AND		RC.recibo_caja = RD.recibo_caja ";
			$sql .= "AND		RC.empresa_id = RD.empresa_id ";
			$sql .= "AND		RC.centro_utilidad = RD.centro_utilidad ";
			$sql .= "AND		RC.tercero_id = TE.tercero_id ";
			$sql .= "AND		RC.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND		RC.centro_utilidad = CU.centro_utilidad ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
		
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			$datos_recibo = array();
			$datos_recibo = $datos[0];
			
			return $datos_recibo;
		}
		/**********************************************************************************
		* Funcion donde se obtiene la informacion de una cuenta X,plan y nombre e 
		* identificacion del paciente 
		* 
		* @params int	$numerodecuenta	Numero de cuenta
		* @returns array Informacion de la cuenta
		***********************************************************************************/
		function ObtenerInformacionCuenta($numerodecuenta)
		{
			$datos = array();
			
			$sql .= "SELECT	PL.plan_descripcion, ";
			$sql .= "				PA.tipo_id_paciente ||' '||PA.paciente_id AS identificacion, ";
			$sql .= "				PA.primer_nombre ||' '||PA.segundo_nombre AS nombres, ";
			$sql .= "				PA.primer_apellido ||' '||PA.segundo_apellido AS apellidos ";
			$sql .= "FROM		cuentas CU, ";
			$sql .= "				ingresos IG, ";
			$sql .= "				pacientes PA, ";
			$sql .= "				planes PL ";
			$sql .= "WHERE	CU.numerodecuenta = ".$numerodecuenta." ";
			$sql .= "AND		CU.estado IN ('1','2')	";
			$sql .= "AND		CU.plan_id = PL.plan_id ";
			$sql .= "AND		CU.ingreso = IG.ingreso ";
			$sql .= "AND		IG.tipo_id_paciente = PA.tipo_id_paciente ";
			$sql .= "AND		IG.paciente_id = PA.paciente_id ";
				
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
		* Funcion donde se obtienen los motivos de anulacion para los recibos de caja
		* hospitalaria
		* 
		* @return array informacion de los motivo de anulacion de los recibos decaja
		***********************************************************************************/
		function ObtenerMotivosAnulacion()
		{
			$datos = array();
			
			$sql .= "SELECT	motivo_anulacion_id,";
			$sql .= "				motivo_descripcion ";
			$sql .= "FROM		cajas_motivos_anulaciones ";
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
		* Funcion donde se hace el proceso de anulacion del recibo de caja
		*
		* @returns boolean Indica si la operacion se realizo con exito o no
		***********************************************************************************/
		function AnularRecibo()
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
				$this->frmError['MensajeError'] = "SE DEBE INGREAR UNA OBSERVACIÓN A LA ANULACIÓN DEL RECIBO DE CAJA";
				return false;
			}
			$result = true;
			
			$sql .= "UPDATE recibos_caja ";
			$sql .= "SET		estado = '1' ";
			$sql .= "WHERE	prefijo = '".$datos['prefijo']."' ";
			$sql .= "AND		recibo_caja = ".$datos['recibo']." ";
			$sql .= "AND		empresa_id = '".$datos['empresa']."'; ";
					
			$sql .= "INSERT INTO cajas_auditoria_anulaciones ";
			$sql .= "				(empresa_id ,";
			$sql .= "				centro_utilidad ,";
			$sql .= "				recibo_caja ,";
			$sql .= "				prefijo ,";
			$sql .= "				numerodecuenta ,";
			$sql .= "				observacion ,";
			$sql .= "				motivo_anulacion_id,";
			$sql .= "				usuario_id,";
			$sql .= " 			fecha_registro ) ";
			$sql .= "VALUES (";
			$sql .= "				'".$datos['empresa']."' ,";
			$sql .= "				'".$datos['centro']."' ,";
			$sql .= "				 ".$datos['recibo'].",";
			$sql .= "				'".$datos['prefijo']."' ,";
			$sql .= "				 ".$datos['cuenta']." ,";
			$sql .= "				'".$this->request[3]."' ,";
			$sql .= "				 ".$this->request[2].",";
			$sql .= "				 ".UserGetUID().", ";
			$sql .= "				 NOW() ";
			$sql .= "				);";
					
			$sql .= "SELECT CalcularValorHospitalizacion(".$datos['cuenta']."::integer,'".$datos['empresa']."'::bpchar,'".$datos['centro']."'::bpchar); ";

			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$this->Mensaje  = "EL RECIBO DE CAJA Nº ".$datos['prefijo']." ".$datos['recibo'].", ";
			$this->Mensaje .= "SE HA ANULADO ";
			$this->action[0] = ModuloGetURL('app','Cajas_AnulacionRecibos','user','FormaBuscarRecibosAnulacion');
			
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
