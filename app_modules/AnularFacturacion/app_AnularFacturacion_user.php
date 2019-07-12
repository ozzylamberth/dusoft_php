<?php
	/**************************************************************************************  
	* $Id: app_AnularFacturacion_user.php,v 1.5 2010/03/16 13:00:35 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.5 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	IncludeClass('app_AnularFacturacion_Facturas','userclasses','app','AnularFacturacion');
	class app_AnularFacturacion_user extends classModulo
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
		/**
		*  @var $Nota Variable para los mensajes
		**/
		var $Nota = "";
		/**
		* @var $variable Variable donde se guardan datos estaticos para las formas 
		**/
		var $variable = array();
		
		function app_AnularFacturacion_user(){	}
		/**********************************************************************************
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function ObtenerPermisos()
		{
			$fct = new app_AnularFacturacion_Facturas();
			
			SessionDelVar("EmpresaAnulacion");
			$this->action[0] = ModuloGetURL('system','Menu','user');

			$sql .= "SELECT EM.razon_social, ";
			$sql .= "				EM.empresa_id ";
			$sql .= "FROM 	userpermisos_anulacion_facturas UF, ";
			$sql .= "				empresas EM ";
			$sql .= "WHERE 	UF.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UF.empresa_id = EM.empresa_id ";
						
			if(!$rst = $fct->ConexionBaseDatos($sql))
			{
				$this->frmError = $fct->frmError;
				return false;
			}

			while (!$rst->EOF)
			{
				$this->Rta[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();

			if(sizeof($this->Rta) > 0)
				return true;
			else
				return false;
		}
		/**********************************************************************************
		* Funcion donde se crean las variables usadas en la funcion FormaMostrarDocumentos,
		* se averiguan los tipos de documentos
		***********************************************************************************/
		function MostrarDocumentos()
		{
			$fct = new app_AnularFacturacion_Facturas();
			
			SessionDelVar("DocumentoAnulacion");
			SessionDelVar("DescripcionAnulacion");
			
			if(!SessionIsSetVar("EmpresaAnulacion"))
				SessionSetVar("EmpresaAnulacion",$_REQUEST['permiso']['empresa_id']);
			
      $emp = SessionGetVar("EmpresaAnulacion");
      $tipodc = ModuloGetVar("app","AnularFacturacion", "tipodc_".$emp);
			
			$this->Rta = $fct->ObtenerTiposDocumentos($emp,$tipodc);
			$this->frmError = $fct->frmError;
			$this->action[0] = ModuloGetURL('app','AnularFacturacion','user','main');
		}
		/**********************************************************************************
		* Funcion donde se crean las variables usadas en la funcion 
		* FormaBuscarFactursAnulacion y se obtienen los prefijos para llenar el buscador de
		*
		***********************************************************************************/
		function BuscarFacturasAnulacion()
		{
			$fct = new app_AnularFacturacion_Facturas();
			$this->Mtvo = array();
			
			if(!SessionIsSetVar("DocumentoAnulacion"))
			{
				$datos = $_REQUEST['documento'];
				SessionSetVar("DocumentoAnulacion",$datos['documento_id']);
				SessionSetVar("DescripcionAnulacion",$datos['descripcion']);
			}
			
			$this->request[0] = $_REQUEST['prefijo_factura'];
			$this->request[1] = $_REQUEST['factura_fiscal'];			
			$this->variable['descripcion'] = SessionGetVar("DescripcionAnulacion");
			
			if($this->request[1])
			{
				$credito = $fct->ObtenerTipoFactura($this->request[0],$this->request[1],SessionGetVar("EmpresaAnulacion"));
				$result = $fct->IsAnulacion($this->request[0],$this->request[1],SessionGetVar("EmpresaAnulacion"));

				if($credito['estado'] != 2 && sizeof($credito) > 0)
				{
					if($credito['sw_clase_factura'] == 0)//Factura de contado
					{ 
						$this->Mtvo = $fct->ObtenerMotivosAnulacion();
						
						$this->Rta[0] = $fct->ObtenerInformacionFacturaContado($this->request[0],$this->request[1]);
						$res = sizeof($this->Rta[0]);
						
						if(sizeof($this->Rta[0]) <= 0)
						{
							$this->Rta[0] = $fct->ObtenerInformacionFacturaContadoF($this->request[0],$this->request[1]);
						}
						
						if(sizeof($this->Rta[0]) > 0 && $fct->Nota == "")
						{
							$this->Rta[1] = $fct->ObtenerInformacionFacturaCredito($this->Rta[0]['numerodecuenta'],'1');
							
							if($result)
							{
								$datos = array("opcion"=>"1","numerodecuenta"=>$this->Rta[0]['numerodecuenta'],"ingreso"=>$this->Rta[0]['ingreso']);
								$contado = array("prefijo"=>$this->Rta[0]['prefijo'],
																 "factura_fiscal"=>$this->Rta[0]['factura_fiscal'],"total_factura"=>$this->Rta[0]['total_factura'],"valor_factura"=>$this->Rta[0]['total_factura'],
																 "tipo_id_tercero"=>$this->Rta[0]['tipo_id_tercero'],"tipo_id"=>$this->Rta[0]['tipo_id_tercero'],"tercero_id"=>$this->Rta[0]['tercero_id']);
								$factura = array("prefijo"=>$this->Rta[1]['prefijo'],"factura_fiscal"=>$this->Rta[1]['factura_fiscal'],
																 "valor_factura"=>$this->Rta[1]['total_factura'],"tipo_id"=>$this->Rta[1]['tipo_id_tercero'],"tercero_id"=>$this->Rta[1]['tercero_id'] );
								
								if(empty($this->Rta[1])) 
								{
									$factura = $contado;
									$contado = array();
									$datos['estado'] = '1';
								}
								
								$this->action[2] = ModuloGetURL('app','AnularFacturacion','user','EvaluarPrincipal',
																								array("datos"=>$datos,"factura"=>$factura,"contado"=>$contado,"prefijo_factura"=>$this->request[0],"factura_fiscal"=>$this->request[1],"sin_opcion"=>1));
							}
							else
							{
								$this->Nota = "NOTA: ESTA FACTURA YA HA SIDO ENVIADA O PASADA POR INTERFACE CONTABLE";
								$datos = array("opcion"=>"3","adicional"=>"1");
								$factura = array("prefijo"=>$this->Rta[0]['prefijo'],
																 "factura_fiscal"=>$this->Rta[0]['factura_fiscal'],"valor_factura"=>$this->Rta[0]['total_factura'],
																 "tipo_id"=>$this->Rta[0]['tipo_id_tercero'],"tercero_id"=>$this->Rta[0]['tercero_id']);
								$this->Rta[1]['plan_id'] = "1";
								$this->action[2] = ModuloGetURL('app','AnularFacturacion','user','EvaluarPrincipal',
																								array("datos"=>$datos,"factura"=>$factura,"prefijo_factura"=>$this->request[0],"factura_fiscal"=>$this->request[1]));
							}
						}
						else
						{
							$this->Mensaje = "LA FACTURA Nº ".$this->request[0]." ".$this->request[1]." NO SE ENCONTRO ";
              if($fct->Nota != "")
                $this->Mensaje = $fct->Nota;
            }
					}
					else if ($credito['sw_clase_factura'] == 1)
					{
						$this->Mtvo = $fct->ObtenerMotivosAnulacionFacturacion();
						
						$this->Rta[1] = $fct->ObtenerInformacionFactura($this->request[0],$this->request[1],SessionGetVar("EmpresaAnulacion"));
						
						if($this->Rta[1]['prefijo'] && $fct->Nota == "")
						{
							$cuenta = array();
							$this->Rta[1]['cantidad'] = $credito['cuentas'];
							
							if($credito['cuentas'] == 1)
							{
								$this->Rta[2] = $fct->ObtenerInformacionCuenta($this->Rta[1]['numerodecuenta']);
							
								$cuenta = array("ingreso"=>$this->Rta[2]['ingreso'],"numerodecuenta"=>$this->Rta[1]['numerodecuenta'],
																	"tipoId"=>$this->Rta[2]['tipo_id_paciente'],"identificacion"=>$this->Rta[2]['paciente_id']);
							}
							
							if($result)
							{						
								
								$datos = array("opcion"=>"2","agrupado"=>$credito['cuentas']);
								$factura = array("prefijo"=>$this->Rta[1]['prefijo'],"factura_fiscal"=>$this->Rta[1]['factura_fiscal'],"valor_factura"=>$this->Rta[1]['total_factura'],"tipo_id"=>$this->Rta[1]['tipo_id_tercero'],"tercero_id"=>$this->Rta[1]['tercero_id']);
								
								$this->action[2] = ModuloGetURL('app','AnularFacturacion','user','EvaluarPrincipal',
																								array("datos"=>$datos,"cuenta"=>$cuenta,"factura"=>$factura,
																											"prefijo_factura"=>$this->request[0],"factura_fiscal"=>$this->request[1]));
							}
							else
							{
								$datos = array("opcion"=>"3","agrupado"=>$credito['cuentas']);
								
								$this->Nota = "NOTA: ESTA FACTURA YA HA SIDO ENVIADA O PASADA POR INTERFACE CONTABLE";
								$factura = array("prefijo"=>$this->Rta[1]['prefijo'],"factura_fiscal"=>$this->Rta[1]['factura_fiscal'],
																 "tipo_id"=>$this->Rta[1]['tipo_id_tercero'],"tercero_id"=>$this->Rta[1]['tercero_id'],
																 "valor_factura"=>$this->Rta[1]['total_factura']);
								$this->action[2] = ModuloGetURL('app','AnularFacturacion','user','EvaluarPrincipal',
																								array("datos"=>$datos,"factura"=>$factura,"prefijo_factura"=>$this->request[0],
																											"factura_fiscal"=>$this->request[1],"cuenta"=>$cuenta));
							}
						}
						else
						{
              $this->Mensaje = "LA FACTURA Nº ".$this->request[0]." ".$this->request[1]." NO SE ENCONTRO ";
							if($fct->Nota != "")
                $this->Mensaje = $fct->Nota;
						}
					}
				}
				else
				{
					$this->Mensaje = "LA FACTURA Nº ".$this->request[0]." ".$this->request[1]." NO SE ENCONTRO ";
				}
			}
			$this->Pref = $fct->ObtenerPrefijosAnulacion(SessionGetVar("DocumentoAnulacion"));
			
			if(sizeof($fct->frmError) > 0) $this->frmError = $fct->frmError;
			
			$this->action[0] = ModuloGetURL('app','AnularFacturacion','user','FormaMostrarDocumentos');
			$this->action[1] = ModuloGetURL('app','AnularFacturacion','user','FormaBuscarFacturasAnulacion');
		}
		/**********************************************************************************
		* funcion donde se realiza el proceso de anulacion de una factura y se evaluan los
		* request de los datos obligatorios
		* 
		* @return boolean Indica si la factura se anulo o no se anulo
		***********************************************************************************/
		function AnularFactura()
		{
			$result = true;
			
			$datos = $_REQUEST['datos'];
			$this->request[0] = $_REQUEST['prefijo_factura'];
			$this->request[1] = $_REQUEST['factura_fiscal'];	
			$this->request[2] = $_REQUEST['motivo_anula'];
			$this->request[3] = $_REQUEST['observacion'];
			$this->request[4] = $_REQUEST['opcion'];
			
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

			if($this->request[4] == "" && $datos['sin_opcion'] == "1")
			{
				$this->frmError['MensajeError'] = "SE DEBE ESCOGER UNA OPCIÓN";
				return false;
			}
			$fct = new app_AnularFacturacion_Facturas();
			
			$datos = $_REQUEST['datos'];
			$cuenta = $_REQUEST['datos'];
			$contado = $_REQUEST['contado'];
			$factura = $_REQUEST['factura'];
      $emp = SessionGetVar("EmpresaAnulacion");
			$documento = ModuloGetVar('app','AnularFacturacion','documento_'.$emp);
			if(!$documento)
			{
				$this->frmError['MensajeError'] = "PARA LA ANULACION DE LAS FACTURAS SE DEBE CREAR UNA VARIABLE DE MODULO ";
				$this->frmError['MensajeError'] .= "LLAMADA documento PARA EL MODULO AnularFacturacion";
				return false;
			}
			$cuenta['agrupado'] = $datos['agrupado'];
			if(!$this->request[4])
				$this->request[4] = $datos['opcion'];
				
			$rqs['opcion'] = $this->request[4];
			$rqs['motivo_id'] = $this->request[2];
			$rqs['observacion'] = $this->request[3];
			$result = $fct->AnularFacturaNota($documento,$factura,$rqs,$cuenta,$emp,$contado);
			
			if($result)
			{
			
				$this->Mensaje  = "LA FACTURA Nº ".$this->request[0]." ".$this->request[1].", ";
				$this->Mensaje .= "SE HA ANULADO, CON LA CREACIÓN DE LA NOTA DE ANULACION Nº ";
				$this->Mensaje .= $fct->frmError['notas']['prefijo']." ".$fct->frmError['notas']['numeracion'];
				
				$this->action[0] = ModuloGetURL('app','AnularFacturacion','user','FormaBuscarFacturasAnulacion');
			}
			else 
			{
				$this->frmError = $fct->frmError;
			}
			
			if(sizeof($fct->frmError) > 0) $this->frmError = $fct->frmError;
			
			return $result;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularFacturaCredito()
		{
			$this->request[0] = $_REQUEST['prefijo_factura'];
			$this->request[1] = $_REQUEST['factura_fiscal'];	
			$this->request[2] = $_REQUEST['motivo_anula'];
			$this->request[3] = $_REQUEST['observacion'];
			
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
			
			$datos = $_REQUEST['datos'];
			$cuenta = $_REQUEST['cuenta'];
			$factura = $_REQUEST['factura'];
			
			$rqs['motivo_id'] = $this->request[2];
			$rqs['observacion'] = $this->request[3];
			$rqs['opcion'] = $datos['opcion'];
			$result = true;
			$fct = new app_AnularFacturacion_Facturas();

			if($datos['agrupado'] == 1)
			{
				$result = $fct->AnularFactura($cuenta,$factura,$rqs,SessionGetVar("EmpresaAnulacion"));
			}
			else if($datos['agrupado'] > 1)
				{				
					$result = $fct->AnularFacturaAgrupada($factura,$rqs,SessionGetVar("EmpresaAnulacion"));
				}
			
			if($result)
			{
				$this->Mensaje  = "LA FACTURA CREDITO Nº ".$this->request[0]." ".$this->request[1].", ";
				$this->Mensaje .= "SE HA ANULADO ";
				$this->action[0] = ModuloGetURL('app','AnularFacturacion','user','FormaBuscarFacturasAnulacion');
			}
			if(sizeof($fct->frmError) > 0) $this->frmError = $fct->frmError;
			return $result;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularFacturasNotaa()
		{
			$this->request[0] = $_REQUEST['prefijo_factura'];
			$this->request[1] = $_REQUEST['factura_fiscal'];	
			$this->request[2] = $_REQUEST['motivo_anula'];
			$this->request[3] = $_REQUEST['observacion'];
			$this->request[4] = $_REQUEST['opcion'];
			
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
			if($this->request[4] && $this->request[4] == "")
			{
				$this->frmError['MensajeError'] = "SE DEBE INDICAR EL PROCESO A REALIZAR CON LA FACTURA";
				return false;
			}
			$fct = new app_AnularFacturacion_Facturas();
			
			$datos = $_REQUEST['datos'];
			$cuenta = $_REQUEST['cuenta'];
			$factura = $_REQUEST['factura'];
      $emp = SessionGetVar("EmpresaAnulacion");
			$documento = ModuloGetVar('app','AnularFacturacion','documento_'.$emp);
			if(!$documento)
			{
				$this->frmError['MensajeError'] = "PARA LA ANULACION DE LAS FACTURAS SE DEBE CREAR UNA VARIABLE DE MODULO ";
				$this->frmError['MensajeError'] .= "LLAMADA documento PARA EL MODULO AnularFacturacion";
				return false;
			}
			$cuenta['agrupado'] = $datos['agrupado'];
			
			$rqs['opcion'] = $this->request[4];
			$rqs['motivo_id'] = $this->request[2];
			$rqs['observacion'] = $this->request[3];
			$result = $fct->AnularFacturaNota($documento,$factura,$rqs,$cuenta,$emp);
			
			if($result)
			{
			
				$this->Mensaje  = "LA FACTURA Nº ".$this->request[0]." ".$this->request[1].", ";
				$this->Mensaje .= "SE HA ANULADO, CON LA CREACIÓN DE LA NOTA DE ANULACION Nº ";
				$this->Mensaje .= $fct->frmError['notas']['prefijo']." ".$fct->frmError['notas']['numeracion'];
				
				$this->action[0] = ModuloGetURL('app','AnularFacturacion','user','FormaBuscarFacturasAnulacion');
			}
			else 
			{
				$this->frmError = $fct->frmError;
			}
			
			return $result;
		}
	}
?>