<?php
	/**************************************************************************************  
	* $Id: app_Glosas_user.php,v 1.42 2009/03/19 20:07:27 cahenao Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.42 $ 
	* 
	*
	* Clase user del modulo de glosas 
	* 
	* @author Hugo F. Manrique 	
  *
	***************************************************************************************/
	class app_Glosas_user extends classModulo
	{
		/********************************************************************************** 
		* Constructor
		* 
		* @access private	  
		***********************************************************************************/
		function app_Glosas_user()
		{
			return true;
		}
		/********************************************************************************** 
		* Función principal del módulo
		* 
		* @access private 
		***********************************************************************************/
		function main()
		{
			unset($_SESSION['glosas']);
			IncludeClass('Glosas','','app','Glosas');
			$gl = new Glosas();
			$terceros = $gl->ObtenerNombresTerceros();
			
			SessionSetVar("TercerosGlosas",$terceros);
			
			$this->MostrarMenuEmpresas();
			return true;
		}
		/***********************************************************************************
		* Muestra el menu de las empresas y centros de utilidad
		* 
		* @access public 
		***********************************************************************************/
		function MostrarMenuEmpresas()
		{
			unset($_SESSION['glosas']);
			SessionDelVar('PermisosResponder');

			$empresas=$this->BuscarEmpresasUsuario();
			$mtz[0]='EMPRESAS';
			$url[0]='app';												//contenedor
			$url[1]='Glosas';											//módulo
			$url[2]='user';												//clase
			$url[3]='MostrarMenuPrincipalGlosas';	//método 
			$url[4]='permisoglosas';							//indice del request
			$this->salida .= gui_theme_menu_acceso('GLOSAS', $mtz, $empresas, $url, ModuloGetURL('system','Menu'));
			return true;
		}
		/********************************************************************************** 
		* Retorna las empresas a las cuales tiene permisos el usuario de acceder 
		* 
		* @access public
		***********************************************************************************/
		function BuscarEmpresasUsuario()
		{
			$usuario=UserGetUID();
			
			$query = "SELECT 	B.empresa_id AS empresa_id, 
												B.razon_social AS razon_social,
												G.sw_todos_los_clientes AS sw_clientes,
												G.sw_responder,
												B.tipo_id_tercero,
												B.id
								FROM 		userpermisos_glosas G,empresas B
								WHERE 	G.usuario_id = $usuario 
								AND 		G.empresa_id = B.empresa_id ";
			if(!$resultado = $this->ConexionBaseDatos($query))
				return false;	  
			
			while(!$resultado->EOF)
			{
				$empresas[$resultado->fields[1]]=$resultado->GetRowAssoc($ToUpper = false);
				$resultado->MoveNext();
			}
			$resultado->Close();
			return $empresas;
		}
		/********************************************************************************** 
		* Funcuion que permnite desplegar la informacion de la factura al usuario 
		* 
		* @return boolean 
		***********************************************************************************/ 
		function MostrarInformacionFacturas()
		{	
			unset($_SESSION['Glosas']['sistema']);
			$this->Numero = $_REQUEST['numero'];
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->TerceroId = $_REQUEST['tercero_id'];
			$this->AuditorSel = $_REQUEST['auditor_sel'];
			$this->EstadoGlosa = $_REQUEST['estado_glosa'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			$this->FacturaFiscal = $_REQUEST['factura_fiscal'];
			$this->TipoDocumento = $_REQUEST['tipo_documento'];
			$this->TipoIdTercero = $_REQUEST['tipo_id_tercero'];
			$this->NombreTercero = $_REQUEST['nombre_tercero'];
			$this->PrefijoFactura = $_REQUEST['prefijo_factura'];
			
			SessionDelVar("EstadosGlosas");
			SessionDelVar("EstadoGlosaBuscar");
			SessionDelVar("SistemaFactura");
			SessionDelVar("IndiceRetorno");
			SessionDelVar("NumeroGlosa");
			

			$request = array("numero"=>$this->Numero,"fecha_fin"=>$this->FechaFin,"estado_glosa"=>$this->EstadoGlosa,"nombre_tercero"=>$this->NombreTercero,
							 				 "tercero_id"=>$this->TerceroId,"fecha_inicio"=>$this->FechaInicio,"tipo_documento"=>$this->TipoDocumento,"auditor_sel"=>$this->AuditorSel,
							 				 "tipo_id_tercero"=>$this->TipoIdTercero,"factura_fiscal"=>$this->FacturaFiscal,"prefijo_factura"=>$this->PrefijoFactura);
			
			$request['cantidad'] = $this->conteo;
			
			if($_REQUEST['estado_glosa'])	SessionSetVar("EstadosGlosas",$_REQUEST['estado_glosa']);
			
			$this->mostrar_reporte = SessionGetVar("EstadosGlosas");
			
			$this->action = ModuloGetURL('app','Glosas','user','MostrarMenuEmpresas');
			$this->actionBuscador = ModuloGetURL('app','Glosas','user','ObtenerSqlBuscarFacturas');
			$this->actionBuscadorF = ModuloGetURL('app','Glosas','user','ObtenerSqlFacturas');
			if($this->metodo)
				$this->action1['paginador'] = ModuloGetURL('app','Glosas','user',$this->metodo,$request);
	
			$this->FormaMostrarInformacionFacturas();
			return true;
		}
		/********************************************************************************** 
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		***********************************************************************************/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros ";
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);;
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************** 
		* Funcion donde se obtiene el sql que hace la busqueda de facturas segun los 
		* criterios que se hayan dado para la misma, se suben los dos sql (en el que se 
		* cuenta el numero de registros y el que busca los datos y se suben a session) 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerSqlBuscarFacturas()
		{
			$this->metodo = "ObtenerSqlBuscarFacturas";
			IncludeClass('Glosas','','app','Glosas');
			
			$this->request = $_REQUEST;
			$this->request['estado'] = $_REQUEST['estado_glosa'];
			
			$gl = new Glosas();
			$this->glosas = $gl-> ObtenerGlosas($this->request,$_SESSION['glosas']['empresa_id'],$_SESSION['glosas']['sw_clientes']);
			$this->conteo = $gl->conteo; 
			$this->paginaActual = $gl->paginaActual;
			
			if($this->PrimeraVez != 1) $this->MostrarInformacionFacturas();
			
			return true;
		}
		/********************************************************************************** 
		* Funcion en donde se averigua si un usuario tiene o permisos para trabajar en un 
		* empresa 
		* 
		* @return 
		***********************************************************************************/
		function ObtenerPermisosUsuariosGlosas()
		{
			$contador = "";
			
			$sql .= "SELECT COUNT(*) ";
			$sql .= "FROM userpermisos_glosas_clientes ";
			$sql .= "WHERE empresa_id = '".$_SESSION['glosas']['empresa_id']."' ";
			$sql .= "AND usuario_id = ".UserGetUID();
	
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			if(!$rst->EOF)
			{
				$contador = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
		
			return $contador;  		       		
		}
		/********************************************************************************** 
		* Funcion donde se obtiene el sql que permite buscar las facturas 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerSqlFacturas()
		{
			$this->request = $_REQUEST;
			$this->metodo = "ObtenerSqlFacturas";
			
			IncludeClass('Glosas','','app','Glosas');
			$gl = new Glosas();
			
			$this->glosas = $gl->ObtenerGlosasPorFactura($this->request,$_SESSION['glosas']['empresa_id'],$_SESSION['glosas']['sw_clientes']);
			$this->conteo = $gl->conteo; 
			$this->paginaActual = $gl->paginaActual;
				
			$this->MostrarInformacionFacturas();
			return true;
		}
		/********************************************************************************** 
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @return array datos de la tabla documentos
		***********************************************************************************/
		function ObtenerPrefijos()
		{	
			$tipoDoc = ModuloGetVar('app','Glosas','tipo_doc');
			
			$sql  = "SELECT prefijo ";
			$sql .= "FROM 	documentos ";
			$sql .= "WHERE 	empresa_id = '".$_SESSION['glosas']['empresa_id']."' ";
			$sql .= "AND 		tipo_doc_general_id = '".$tipoDoc."' ";
			$sql .= "UNION DISTINCT  ";
			$sql .= "SELECT DISTINCT prefijo ";
			$sql .= "FROM 	facturas_externas ";
			$sql .= "WHERE 	empresa_id = '".$_SESSION['glosas']['empresa_id']."' ";
			$sql .= "AND 		estado = '0' ";
			$sql .= "ORDER BY 1 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
	
			$i = 0;
			while (!$rst->EOF)
			{
				$datos[$i] = $rst->fields[0];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $datos;  		       		
		}
		/********************************************************************************** 
		* Funcion donde se consulta la informacion de la factura 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerInformacionFactura($datos,$empresa_id)
		{			
			if($datos['envio_numero'])
			{
				$sql  = "SELECT T.nombre_tercero,";
				$sql .= "				F.tipo_id_tercero,";
				$sql .= "				F.tercero_id,";
				$sql .= "				F.total_factura,";
				$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
				$sql .= "				TO_CHAR(E.fecha_radicacion,'DD/MM/YYYY') AS fecha_radicacion,";
				$sql .= "				P.num_contrato,"; 
				$sql .= "				P.plan_descripcion,";
				$sql .= "				P.plan_id, ";
				$sql .= "				F.saldo ";
				$sql .= "FROM 	terceros T,fac_facturas F,";
				$sql .= "				envios_detalle ED,";
				$sql .= "				envios E, ";
				$sql .= "	  		planes P ";
				$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
				$sql .= "AND 		ED.factura_fiscal = F.factura_fiscal ";	
				$sql .= "AND 		ED.empresa_id = F.empresa_id ";
				$sql .= "AND 		ED.envio_id = E.envio_id ";
				$sql .= "AND 		ED.prefijo = F.prefijo ";
				$sql .= "AND	 	ED.envio_id = ".$datos['envio_numero']." ";
				$sql .= "AND 		F.prefijo = '".$datos['prefijo']."' ";
				$sql .= "AND 		F.factura_fiscal = ".$datos['factura_fiscal']." ";
				$sql .= "AND 		F.tercero_id = T.tercero_id ";
				$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";
				$sql .= "AND 		F.sw_clase_factura = '1' ";
				$sql .= "AND 		F.plan_id = P.plan_id ";
				$sql .= "AND 		F.empresa_id = P.empresa_id ";
			}
			else
			{
				if($this->Sistema == "EXT")
				{
					$this->GlosaDocumento = "on";
					$sql  = "SELECT T.nombre_tercero,";
					$sql .= "				F.tipo_id_tercero,";
					$sql .= "				F.tercero_id,";
					$sql .= "				F.saldo, ";
					$sql .= "				F.total_factura,";
					$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro ";
					$sql .= "FROM 	terceros T,facturas_externas F ";
					$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
					$sql .= "AND 		F.prefijo = '".$datos['prefijo']."' ";
					$sql .= "AND 		F.factura_fiscal = ".$datos['factura_fiscal']." ";
					$sql .= "AND 		F.tercero_id = T.tercero_id ";
					$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";		
				}
				else
				{
					$sql  = "SELECT T.nombre_tercero,";
					$sql .= "				F.tipo_id_tercero,";
					$sql .= "				F.tercero_id,";
					$sql .= "				F.total_factura,";
					$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
					$sql .= "				P.num_contrato,"; 
					$sql .= "				P.plan_descripcion,";
					$sql .= "				P.plan_id, ";
					$sql .= "				F.saldo ";
					$sql .= "FROM 	terceros T,fac_facturas F,";
					$sql .= "	  		planes P ";
					$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
					$sql .= "AND 		F.prefijo = '".$datos['prefijo']."' ";
					$sql .= "AND 		F.factura_fiscal = ".$datos['factura_fiscal']." ";
					$sql .= "AND 		F.tercero_id = T.tercero_id ";
					$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";
					$sql .= "AND 		F.sw_clase_factura = '1' ";
					$sql .= "AND 		F.plan_id = P.plan_id ";
					$sql .= "AND 		F.empresa_id = P.empresa_id ";
				}				
			}
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$factura = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			$this->EntidadNit = $factura['tipo_id_tercero'];
			$this->EntidadId = $factura['tercero_id'];
			$this->EnvioFecha = $factura['fecha_radicacion'];
			$this->PlanIdNumero = $factura['plan_id'];
			$this->FacturaFecha = $factura['fecha_registro'];
			$this->EntidadNombre = $factura['nombre_tercero'];
			$this->PlanDescripcion = $factura['plan_descripcion'];
			$this->PlanNumContrato = $factura['num_contrato'];
			$this->SaldoFactura = $factura['saldo'];
			$this->TotalFactura = $factura['total_factura'];
			
			if($this->FechaGlosamiento == "")	$this->FechaGlosamiento = date("d/m/Y");
			
			return true;
		}
		/********************************************************************************** 
		* Funcion con la que se despliega la informacion de la factura 
		* 
		* @return boolean 
		***********************************************************************************/
		function MostrarInformacionDetalleFactura()
		{
			$this->request = $_REQUEST;
			if($this->request['sistema']) SessionSetVar("SistemaFactura",$this->request['sistema']);
			$this->request['sistema'] = SessionGetVar("SistemaFactura");
			
			$this->request['sistema'] = $_SESSION['Glosas']['sistema'];

			$post['factura_fiscal'] = $this->request['factura_fiscal'];
			$post['envio_numero'] = $this->request['envio_numero'];
			$post['empresa_id'] =  $_SESSION['glosas']['empresa_id'];
			$post['glosa_id'] = $this->request['glosa_id']; 
			$post['prefijo'] = $this->request['prefijo'];

			$this->actionV = ModuloGetURL('app','Glosas','user','MostrarInformacionFacturas');
			$this->action  = ModuloGetURL('app','Glosas','user','IngresarGlosa',$post);
			$this->actiong['responder'] = ModuloGetURL('app','Glosas','user','FormaResponderGlosaTotalFactura',array("datos_glosa"=>$post));
			
			$this->ObtenerInformacionFactura($this->request,$_SESSION['glosas']['empresa_id']);
			
			$this->FormaMostrarDetalleFactura();
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se averiguan las clasificaciones que se le pueden añadir a la glosa 
		* 
		* @return array datos de las clasificaciones de las glosas 
		***********************************************************************************/
		function ObtenerClasificacionGlosas()
		{
			$sql  = "SELECT glosa_tipo_clasificacion_id AS gtci,descripcion ";
			$sql .= "FROM glosas_tipos_clasificacion ";
					
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
		* Funcion donde se averiguan los auditores internos asociados al plan de la factura 
		* 
		* @return array datos de las clasificaciones de las glosas 
		***********************************************************************************/
		function IngresarGlosa($responder = null)
		{
			$this->request = $_REQUEST;
			$this->MotivoGlosa = '-1';
			if(!$this->EvaluarRequestGlosaFactura())
			{
				$this->MostrarInformacionDetalleFactura();
				if(!$responder) return true;
				else return false;
			}
			 
			$sql = "SELECT nextval('glosas_glosa_id_seq'::text) ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$glosaId = $rst->fields[0];
			$rst->Close();
			
			if($_SESSION['Glosas']['sistema'] == "EXT")
			{
				$this->SwGlosaFactura = "2";
			}
			if(!is_numeric($this->GlosaValor))
				$this->GlosaValor = 0;
			
			$codigo_concepto_general = "-1";
			$codigo_concepto_especifico = "-1";
	
			if($_REQUEST[concepto_especifico])
			{
				$dat = explode("||//",$_REQUEST[concepto_especifico]);
				$codigo_concepto_general = $dat[0];
				$codigo_concepto_especifico = $dat[1];
			}
			
			$sql  = "INSERT INTO glosas(empresa_id,";
			$sql .= "					prefijo,";
			$sql .= "					factura_fiscal,";
			$sql .= "					valor_glosa, ";
			$sql .= "					fecha_glosa,";
			$sql .= "					motivo_glosa_id,";
			$sql .= "					observacion,";
			$sql .= "					glosa_tipo_clasificacion_id,";
			$sql .= "					documento_interno_cliente_id,";
			$sql .= "					auditor_id,";
			$sql .= "					sw_glosa_total_factura,";
			$sql .= "					sw_estado,";
			$sql .= "					usuario_id,";
			$sql .= "					fecha_registro,";
			$sql .= "					glosa_id, ";
			$sql .= "					codigo_concepto_general, "; 
			$sql .= "					codigo_concepto_especifico) ";
			$sql .= "VALUES ('".$_SESSION['glosas']['empresa_id']."',";
			$sql .= "		 '".$this->request['prefijo']."',";
			$sql .= "		  ".$this->request['factura_fiscal'].",";
			$sql .= "		  ".$this->GlosaValor.",";
			$sql .= "		 '".$this->Fecha."',";
			$sql .= "		  ".$this->MotivoGlosa.",";
			$sql .= "		  ".$this->DescripcionGlosa.", ";
			$sql .= "		  ".$this->ClasificacionGlosa.",";
			$sql .= "		 '".$this->DocumentoGlosa."',";
			$sql .= "		  ".$this->AuditorId.",";
			$sql .= "		 '".$this->SwGlosaFactura."',";
			$sql .= "		 '1',";
			$sql .= "		  ".UserGetUID().",";
			$sql .= "		  now(),";
			$sql .= "		  ".$glosaId.",";
			$sql .= "		  '".$codigo_concepto_general."',";
			$sql .= "		  '".$codigo_concepto_especifico."');";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$rst->Close();
			
 			if($this->SwGlosaFactura != '0' )
 			{
				$this->action = ModuloGetURL('app','Glosas','user','MostrarInformacionFacturas');
 			}
 			else
 			{
				if($this->request['sistema']) SessionSetVar("SistemaFactura",$this->request['sistema']);
				$this->Sistema = SessionGetVar("SistemaFactura");
			
				$post['factura_fiscal'] = $this->request['factura_fiscal'];
				$post['envio_numero'] = $this->request['envio_numero'];
				$post['empresa_id'] =  $_SESSION['glosas']['empresa_id'];
				$post['glosa_id'] = $glosaId; 
				$post['prefijo'] = $this->request['prefijo'];
				
			 	IncludeClass("Glosas","","app","Glosas");
				$gl = new Glosas();
				$this->datos_factura = $gl->ObtenerCantidadGlosas($glosaId,$this->request['factura_fiscal'],$this->request['prefijo']);
				 	
				$metodo = "FormaMostrarDetalleCuenta";
				$retorno = 1; 
			 	if($this->datos_factura ['cuentas_numero'] > 1)
				{
					$retorno = 2;
					$metodo = "FormaMostrarListadoCuentasFactura";
				}
				else
				{
					$cnt = $gl->ObtenerNumeroCuenta($post);
					$post['numerodecuenta'] = $cnt['numerodecuenta'];
				}
				SessionSetVar("IndiceRetorno",$retorno);
				
				$post['cantidad'] = $this->datos_factura ['cuentas_numero'];
			 	$this->action = MOduloGetURL('app','Glosas','user',$metodo,array("datos_glosa"=>$post));
			}

 			$informacion = "LA GLOSA SE HA ADICIONADO CORRECTAMENTE";
			if(!$responder) 
			{//,'envio_numero'=>'13809'
			 	$this->action = MOduloGetURL('app','Glosas','user','ConsultarInformacionGlosa').URLRequest(array('sistema'=>'SIIS','prefijo'=>$this->request['prefijo'],'glosa_id'=>$glosaId,'sw_estado'=>'1','factura_fiscal'=>$this->request['factura_fiscal']));
				$this->FormaInformacion($informacion);
			}
			else
			{
				$this->dat['glosa_id'] = $glosaId;
				$this->dat['prefijo'] = $this->request['prefijo'];
				$this->dat['factura'] = $this->request['factura_fiscal'];
			} 
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se consulta la informacion de la glosa de la factura seleccionada 
		* 
		* @return boolean 
		************************************************************************************/
		function ConsultarInformacionGlosa()
		{
			unset($_SESSION['codproducto']);
			unset($_SESSION['transaccion']);
			$post = array();
			$this->request = $_REQUEST;
			//print_r($this->request);
			SessionDelvar("ObservacionesCargos");
			
			if($this->request['glosa_id']) SessionSetVar("NumeroGlosa",$this->request['glosa_id']);
			//if(!($this->request['sw_estado'] === null)) 
			if($this->request['sw_estado']) 
			{
				SessionSetVar("EstadoGlosaBuscar","'".$this->request['sw_estado']."'");
			}
			
			if($this->request['sistema']) SessionSetVar("SistemaFactura",$this->request['sistema']);
			
			$this->request['glosa_id'] = SessionGetVar("NumeroGlosa");
			$this->request['sw_estado'] = str_replace("'","",SessionGetVar("EstadoGlosaBuscar"));
			$this->Sistema = SessionGetVar("SistemaFactura");
		
			$this->ObtenerInformacionFactura($this->request,$_SESSION['glosas']['empresa_id']);
			$this->ObtenerInformacionGlosaFactura($this->request,$_SESSION['glosas']['empresa_id']);
			
			$post['factura_fiscal'] = $this->request['factura_fiscal'];
			$post['envio_numero'] = $this->request['envio_numero'];
			$post['empresa_id'] =  $_SESSION['glosas']['empresa_id'];
			$post['glosa_id'] = $this->request['glosa_id']; 
			$post['prefijo'] = $this->request['prefijo'];
			$post['sistema'] = $this->Sistema;
			
		 	if($this->Sistema == "SIIS")
		 	{				
			 	IncludeClass("Glosas","","app","Glosas");
				$gl = new Glosas();
				$this->datos_factura = $gl->ObtenerCantidadGlosas($this->request['glosa_id'],$this->request['factura_fiscal'],$this->request['prefijo']);
			 	
				$metodo = "FormaMostrarDetalleCuenta";
				$retorno = 1; 
			 	if($this->datos_factura ['cuentas_numero'] > 1)
				{
					$retorno = 2;
					$metodo = "FormaMostrarListadoCuentasFactura";
				}
				else
				{
					$cnt = $gl->ObtenerNumeroCuenta($post);
					$post['numerodecuenta'] = $cnt['numerodecuenta'];
				}
				SessionSetVar("IndiceRetorno",$retorno);
				
				$post['cantidad'] = $this->datos_factura ['cuentas_numero'];
			 	$this->action['cargos'] = MOduloGetURL('app','Glosas','user',$metodo,array("datos_glosa"=>$post));
			}
			
			$this->action['volver'] = ModuloGetURL('app','Glosas','user','MostrarInformacionFacturas');
			$this->action['anular'] = ModuloGetURL('app','Glosas','user','EliminarGlosa');
		 	$this->action['editar'] = ModuloGetURL('app','Glosas','user','ModificarGlosaFactura',$post);
		 	$this->action['responder'] = ModuloGetURL('app','Glosas','user','FormaResponderGlosaTotalFactura',array("datos_glosa"=>$post));
	
			$this->FormaMostrarConsultaGlosa();
			return true; 
		}
		/********************************************************************************** 
		* Funcion que permite desplegar la informacion de la glosa perteneciente a la
		* factura que se desea modificar 
		* 
		* @return boolean 
		************************************************************************************/
		function ModificarGlosaFactura()
		{
			$this->request = $_REQUEST;
			
			$post['factura_fiscal'] = $this->request['factura_fiscal'];
			$post['envio_numero'] = $this->request['envio_numero'];
			$post['empresa_id'] =  $_SESSION['glosas']['empresa_id'];
			$post['glosa_id'] = $this->request['glosa_id']; 
			$post['prefijo'] = $this->request['prefijo'];
			
			$this->request['sw_estado'] = str_replace("'","",SessionGetVar("EstadoGlosaBuscar"));
				
			$this->ObtenerInformacionFactura($this->request,$_SESSION['glosas']['empresa_id']);
			$this->ObtenerInformacionGlosaFactura($this->request,$_SESSION['glosas']['empresa_id']);
			
			$this->actionV = ModuloGetURL('app','Glosas','user','ConsultarInformacionGlosa',$post);
			$post['sw_modificar'] = 0;
			
		 	//$this->actiong['responder'] = ModuloGetURL('app','Glosas','user','FormaResponderGlosaTotalFactura',array("datos_glosa"=>$post));
		 	$this->action = ModuloGetURL('app','Glosas','user','ModificarGlosaFacturaBD',$post);
		 	$this->NoResponder = 1;				   		 
		 	$this->FormaMostrarDetalleFactura();
		 	return true;
		}
		/********************************************************************************** 
		* Funcion donde se modifica la informacion de la glosa de una factura en la base 
		* de datos 
		*  
		* @return boolean 
		***********************************************************************************/
		function ModificarGlosaFacturaBD()
		{
			$this->request = $_REQUEST;
			if(!$this->EvaluarRequestGlosaFactura())
			{
				$this->ModificarGlosaFactura();
				return true;			
			}
		 	$this->GlosaId = $_REQUEST['glosa_id'];
			if($_SESSION['Glosas']['sistema'] == "EXT") $this->SwGlosaFactura = "2";
			
			$this->Fecha = str_replace("/","-",$this->Fecha);
			
			$setC = "";
			if($this->concepto_general)
			{
				$setC = ", codigo_concepto_general = '".$this->concepto_general."', codigo_concepto_especifico = '".$this->concepto_especifico."'";
			}
			
			$sql .= "UPDATE glosas ";
			$sql .= "SET		fecha_glosa = '".$this->Fecha."',";
			$sql .= "				motivo_glosa_id =  ".$this->MotivoGlosa." ,";
			$sql .= "				observacion = ".$this->DescripcionGlosa.", ";
			$sql .= "				glosa_tipo_clasificacion_id =  ".$this->ClasificacionGlosa." ,";
			$sql .= "				documento_interno_cliente_id = '".$this->DocumentoGlosa."',";
			$sql .= "				auditor_id = ".$this->AuditorId.",";
			$sql .= "				sw_glosa_total_factura = '".$this->SwGlosaFactura."', ";
			$sql .= "				usuario_id = ".UserGetUID().", ";
			$sql .= "				fecha_registro = NOW() $setC";
			$sql .= "WHERE glosa_id = ".$this->GlosaId."; ";
			
			if($this->SwGlosaFactura == "1")
			{
				$sql .= "DELETE FROM glosas_detalle_cargos ";
				$sql .= "WHERE glosa_id = ".$this->GlosaId."; ";
				$sql .= "DELETE FROM glosas_detalle_cuentas ";
				$sql .= "WHERE glosa_id = ".$this->GlosaId."; ";
				$sql .= "DELETE FROM glosas_detalle_inventarios ";
				$sql .= "WHERE glosa_id = ".$this->GlosaId."; ";
			}

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$rst->Close();
	 		
			$post['factura_fiscal'] = $this->request['factura_fiscal'];
			$post['envio_numero'] = $this->request['envio_numero'];
			$post['glosa_id'] = $this->request['glosa_id'];
			$post['prefijo'] = $this->request['prefijo'];
			
			$this->action = ModuloGetURL('app','Glosas','user','ConsultarInformacionGlosa',$post);
	
	 		$informacion = "LA GLOSA PERTENECIENTE A LA FACTURA Nº ".$this->request['prefijo']." ".$post['factura_fiscal']." SE HA MODIFICADO EXITOSAMENTE ";
			$this->FormaInformacion($informacion);
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se toma de la base de datos el nombre del auditor 
		* 
		* @return string nombre del auditor 
		************************************************************************************/
		function ObtenerAuditorNombre($id)
		{
			if($id != null && $id != 0)
			{
				$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
	
				if (!$rst->EOF)
				{
					$nombreUsuario = $rst->fields[0];
					$rst->MoveNext();
		    	}
				$rst->Close();
			}
	 		return $nombreUsuario;
		}
		/************************************************************************************************
		* 
		* @return boolean 
		*************************************************************************************************/
		function MostrarListadoCuentasFactura()
		{
			$this->request = $_REQUEST;
			
		 	$this->action['volver'] = MOduloGetURL('app','Glosas','user','ConsultarInformacionGlosa',$this->request['datos_glosa']);
		 	$this->action['glosar'] = MOduloGetURL('app','Glosas','user','FormaMostrarDetalleCuenta');
		 	$this->action['paginador'] = MOduloGetURL('app','Glosas','user','FormaMostrarListadoCuentasFactura',array("datos_glosa"=>$this->request['datos_glosa']));
		}
		/************************************************************************************************
		* 
		* @return boolean 
		*************************************************************************************************/
		function MostrarDetalleCuenta()
		{
			$this->request = $_REQUEST;
			$rtn = $this->request['datos_glosa'];
			unset($rtn['numerodecuenta']);
			
			switch(SessionGetVar("IndiceRetorno"))
			{
				case 1:
					$this->action['volver'] = MOduloGetURL('app','Glosas','user','ConsultarInformacionGlosa',$rtn);
				break;
				case 2:
					$this->action['volver'] = MOduloGetURL('app','Glosas','user','FormaMostrarListadoCuentasFactura',array("datos_glosa"=>$rtn));
				break;
		 	}
			$this->action['glosar'] = MOduloGetURL('app','Glosas','user','FormaGuardarGlosa',array("datos_glosa"=>$this->request['datos_glosa']));
		 	$this->action['modificar'] = MOduloGetURL('app','Glosas','user','FormaModificarGlosaCargoCuenta',array("datos_glosa"=>$this->request['datos_glosa']));
		 	$this->action['actualizar'] = MOduloGetURL('app','Glosas','user','FormaMostrarDetalleCuenta',array("datos_glosa"=>$this->request['datos_glosa']));
			$this->action['responder'] = ModuloGetURL('app','Glosas','user','FormaResponderGlosa',array("datos_glosa"=>$this->request['datos_glosa']));
		}
		/************************************************************************************************
		* Funcion mediante la cual se obtiene la informacion de la tabla glosas_detalle_cargos 
		* 
		* @return array datos de la tabla glosas_detalle_cargos 
		*************************************************************************************************/
		function ObtenerInformacionGlosaDetalle($glosa_id,$opcion,$numero_cuenta=null)
		{
			$sql  = "SELECT M.motivo_glosa_descripcion, ";
			$sql .= "		G.observacion ";
			switch($opcion)
			{
				case 0:
					$sql .= "FROM glosas_detalle_cuentas G,";
					$sql .= "	  glosas_motivos M ";
					$sql .= "WHERE G.glosa_id = ".$glosa_id." ";
					$sql .= "AND G.numerodecuenta = ".$numero_cuenta." ";
					$sql .= "AND G.sw_estado <> '0' ";
				break;
				case 1:
					$sql .= "	   ,U.nombre,";
					$sql .= "		G.numerodecuenta";
					$sql .= "FROM glosas_motivos M,";
					$sql .= "	  glosas_detalle_cuentas G LEFT JOIN ";
					$sql .= "	  system_usuarios U ON(";
					$sql .= "	  G.auditor_id = U.usuario_id)";
					$sql .= "WHERE G.glosa_id = ".$glosa_id." ";
					$sql .= "AND G.sw_estado <> '0' ";
				break;
			}
			
			$sql .= "AND G.motivo_glosa_id = M.motivo_glosa_id ";
			$sql .= "AND G.sw_glosa_total_cuenta = '1'";

			if(!$rst->$this->ConexionBaseDatos($sql))
				return false;
							
			switch($opcion)
			{
				case 0:
					if(!$rst->EOF)
					{
						$datos[0] = $rst->fields[0];
						$datos[1] = $rst->fields[1];
						$rst->MoveNext();
			    }
				break;
				case 1:
					$i = 0;
					while(!$rst->EOF)
					{
						$datos[$i] = $rst->fields[0]."ç".$rst->fields[1]."ç".$rst->fields[2]."ç".$rst->fields[3];
						$rst->MoveNext();
						$i++;
			    }
				break;
			}
			$rst->Close();
			
			return $datos;
		}
		/************************************************************************************************ 
		* Funcion que permite desplegar la forma de modificar la glosa de un cargo de la cuenta 
		* seleccionada 
		* 
		* @return boolean 
		*************************************************************************************************/
		function ModificarGlosaCargoCuenta()
		{
			$this->request = $_REQUEST;
		 	$this->action['volver'] = MOduloGetURL('app','Glosas','user','FormaMostrarDetalleCuenta',array("datos_glosa"=>$this->request['datos_glosa']));
		}
		/******************************************************************************* 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		********************************************************************************/
		function ProcesarSqlConteo($sqlCont,$limite=null,$cant= null)
		{
			$this->paginaActual = 1;
			$this->offset = 0;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
				if(!$this->limit)	$this->limit = 20;
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($_REQUEST['offset'])
			{
				$this->paginaActual = intval($_REQUEST['offset']);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		
			if(!$cant)
			{
				if(!$result = $this->ConexionBaseDatos($sqlCont))
					return false;
	
				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
					$result->Close();
				}
			}
			else
			{
				$this->conteo = $cant;
			}
			return true;
		}
		/***************************************************************************************
		* Funcion que permite obtener la informacion de la glosa de una factura 
		* 
		* @return boolean 
		****************************************************************************************/
		function ObtenerInformacionGlosaFactura($datos,$empresa_id)
		{
			$estado = $datos['sw_estado'];
		
			$sql  = "SELECT	TC.descripcion,";
			$sql .= "				M.motivo_glosa_descripcion,";
			$sql .= "				G.observacion,";
			$sql .= "				G.documento_interno_cliente_id,";
			$sql .= "				G.valor_glosa,";
			$sql .= "				G.valor_aceptado,";
			$sql .= "				G.valor_pendiente, ";
			$sql .= "				G.sw_glosa_total_factura,";
			$sql .= "				G.sw_estado,";
			$sql .= "				G.glosa_id, ";
			$sql .= "				G.sw_glosa_total_factura, ";
			$sql .= "				G.motivo_glosa_id, ";
			$sql .= "				G.glosa_tipo_clasificacion_id, ";
			$sql .= "				U.nombre, ";
			$sql .= "				COALESCE(G.auditor_id,0) AS auditor,";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa,";
			$sql .= "				TO_CHAR(G.fecha_cierre,'DD/MM/YYYY') AS fecha_cierre,";
			$sql .= "				TO_CHAR(G.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "				GCG.codigo_concepto_general, ";
			$sql .= "				GCE.codigo_concepto_especifico, ";
			$sql .= "				GCG.descripcion_concepto_general, ";
			$sql .= "				GCE.descripcion_concepto_especifico ";
			$sql .= "FROM		glosas G LEFT JOIN glosas_motivos M ";
			$sql .= "				ON(G.motivo_glosa_id = M.motivo_glosa_id) ";
			$sql .= "				LEFT JOIN glosas_tipos_clasificacion TC ";
			$sql .= "				ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "				LEFT JOIN glosas_concepto_general GCG ";
			$sql .= "				ON(G.codigo_concepto_general = GCG.codigo_concepto_general) ";
			$sql .= "				LEFT JOIN glosas_concepto_especifico GCE ";
			$sql .= "				ON(G.codigo_concepto_especifico = GCE.codigo_concepto_especifico), ";
			$sql .= "				system_usuarios U ";
			$sql .= "WHERE 	G.usuario_id = U.usuario_id ";
			$sql .= "AND		G.empresa_id = '".$empresa_id."' ";
			$sql .= "AND		G.prefijo = '".$datos['prefijo']."' ";
			$sql .= "AND		G.factura_fiscal = ".$datos['factura_fiscal']." ";
			//$sql .= "AND		G.sw_estado = '".$estado."' ";
			if($datos['glosa_id'])
				$sql .= "AND		G.glosa_id = ".$datos['glosa_id']." ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
		//echo 	$sql;
			$this->GlosaId = $glosa['glosa_id'];
			$this->Usuario = $glosa['nombre'];
			$this->AuditorId = $glosa['auditor'];
			$this->GlosaValor = $this->ValorGlosa = $glosa['valor_glosa'];
			$this->EstadoGlosa = $glosa['sw_estado'];
			$this->FechaCierre = $glosa['fecha_cierre'];
			$this->MotivoGlosa = $glosa['motivo_glosa_id'];
			$this->SwGlosaTotal = $glosa['sw_glosa_total_factura'];
			$this->FechaRegistro = $glosa['fecha_registro'];
			$this->ValorAceptado = $glosa['valor_aceptado'];
			$this->DocumentoGlosa = $glosa['documento_interno_cliente_id'];
			$this->ValorPendiente = $glosa['valor_pendiente'];
			$this->DescripcionGlosa = $glosa['observacion'];
			$this->FechaGlosamiento = $glosa['fecha_glosa'];				
			$this->ClasificacionGlosa = $glosa['glosa_tipo_clasificacion_id'];
			$this->MotivoGlosaDescripcion = $glosa['motivo_glosa_descripcion'];
			$this->ClasificacionGlosaDescripcion = $glosa['descripcion'];
			$this->CodigoCG = $glosa['codigo_concepto_general'];
			$this->CodigoCE = $glosa['codigo_concepto_especifico'];
			$this->DescripcionCG = $glosa['descripcion_concepto_general'];
			$this->DescripcionCE = $glosa['descripcion_concepto_especifico'];
			$this->AuditorInterno = $this->ObtenerAuditorNombre($this->AuditorId);
		
			($this->SwGlosaTotal == "0")? $this->GlosaDocumento = "off":$this->GlosaDocumento = "on";
			
			return true;
		}
		/*******************************************************************************************
		* Funcion por medio de la cual se evalua si la informacion para ingresar o modificar 
		* una glosa es correcta 
		* 
		* @return boolean 
		********************************************************************************************/
		function EvaluarRequestGlosaFactura()
		{
			$this->EnvioFecha = $_REQUEST['envio_fecha'];
			$this->GlosaValor = $_REQUEST['glosaValor'];
			$this->FacturaFecha = $_REQUEST['factura_fecha'];
			$this->FacturaNumero = $_REQUEST['factura_numero'];
			$this->DocumentoGlosa = $_REQUEST['documento_glosa'];
			$this->AuditorId = $_REQUEST['auditor_interno'];
			$this->DescripcionGlosa = $_REQUEST['descripcion_glosa'];		
			$this->FechaGlosamiento = $_REQUEST['fecha_glosamiento'];
			$this->ClasificacionGlosa = $_REQUEST['clasificacion_glosa'];
			if($_REQUEST[concepto_especifico])
			{
				$dat = explode("||//",$_REQUEST[concepto_especifico]);
				$this->concepto_general = $dat[0];
				$this->concepto_especifico = $dat[1];
			}
			($_SESSION['Glosas']['sistema'] == "EXT")? $this->GlosaDocumento = "on": $this->GlosaDocumento = $_REQUEST['glosa_documento'];
			
			$motivos = explode("/",$_REQUEST['motivos_glosa']);
			//$this->MotivoGlosa = $motivos[0];
			$this->MotivoGlosa = "-1";
			
			if($this->FechaGlosamiento == "") $this->FechaGlosamiento = date("d/m/Y");
			
			$f = explode("/",$this->FechaGlosamiento);
			$resultado = checkdate($f[1],$f[0],$f[2]);
			if($resultado != 1 || sizeof($f) != 3)
			{
				$this->frmError["MensajeError"] = "EL FORMATO DE FECHA ES INCORRECTO ";
				return false;
			}
	
			$this->Fecha = $f[2]."/".$f[1]."/".$f[0];
			if($this->Fecha > date("Y/m/d"))
			{
				$this->frmError["MensajeError"] = "LA FECHA DE LA GLOSA NO PUEDE SER MAYOR A LA FECHA ACTUAL ";
				return false;
			}
			
			$f2 =  explode("ç",$this->FacturaFecha);
			$this->FacturaFecha = $f2[2]."/".$f2[1]."/".$f2[0];
			if($this->Fecha  < $this->FacturaFecha)
			{
				$this->frmError["MensajeError"] = "LA FECHA DE LA GLOSA NO PUEDE SER MENOR A LA FECHA DE REGISTRO DE LA FACTURA ";
				return false;
			}
			
			$f3 =  explode("ç",$this->EnvioFecha);
			$this->EnvioFecha = $f3[2]."/".$f3[1]."/".$f3[0];		
			if($this->Fecha < $this->EnvioFecha)
			{
				$this->frmError["MensajeError"] = "LA FECHA DE LA GLOSA NO PUEDE SER MENOR A LA FECHA DE RADICACIÓN DE LA FACTURA ";
				return false;
			}
			
			$this->SwGlosaFactura = 0;
			if($this->GlosaDocumento == 'on') $this->SwGlosaFactura = 1;
			
			if($this->SwGlosaFactura == 1)
			{
				if($this->MotivoGlosa == 'V')
				{
					$this->frmError["MensajeError"] = "LA GLOSA DEBE ESTAR ASOCIADA A UN MOTIVO";
					return false;			
				}
						
				if($this->ClasificacionGlosa == 'V')
				{
					$this->frmError["MensajeError"] = "LA GLOSA DEBE TENER UNA CLASIFICACIÓN ASOCIADA";
					return false;			
				}
				
				$saldo = $_REQUEST['saldoValor'];
				if(!is_numeric($this->GlosaValor))
				{
					$this->frmError["MensajeError"] = "EL VALOR DE LA GLOSA ES INCORRECTO";
					return false;
				}
				if($this->GlosaValor > $saldo)
				{
					$this->frmError["MensajeError"] = "EL VALOR DE LA GLOSA NO DEBE SER MAYOR AL SALDO DE LA FACTURA";
					return false;
				}
			}
      else
      {
        $this->GlosaValor = 0;
      }
			
			if($_SESSION['Glosas']['sistema'] == "EXT")
			{
				$saldo = $_REQUEST['saldoValor'];
				if(!is_numeric($this->GlosaValor))
				{
					$this->frmError["MensajeError"] = "EL VALOR DE LA GLOSA ES INCORRECTO";
					return false;
				}
				if($this->GlosaValor > $saldo)
				{
					$this->frmError["MensajeError"] = "EL VALOR DE LA GLOSA NO DEBE SER MAYOR AL SALDO DE LA FACTURA";
					return false;
				}
			}
			
			if($_REQUEST['glosa_documento'] && ($_REQUEST['concepto_general'] == 'V' OR !$_REQUEST['concepto_especifico']))
			{
				$this->frmError["MensajeError"] = "DEBE SELECCIONAR CONCEPTO GENERAL Y ESPECIFICO";
				return false;
			}
			
			if($this->AuditorId  == 'V') $this->AuditorId  = "NULL";
			($this->MotivoGlosa == 'V')? $this->MotivoGlosa = "NULL":$this->MotivoGlosa = "'".$this->MotivoGlosa."'";
			($this->ClasificacionGlosa == 'V')? $this->ClasificacionGlosa = "NULL":$this->ClasificacionGlosa = "'".$this->ClasificacionGlosa."' ";
			
			($this->DescripcionGlosa == "")? $this->DescripcionGlosa = "NULL":$this->DescripcionGlosa = "'".$this->DescripcionGlosa."'";
			
			$this->Factura = explode(" ",$this->FacturaNumero);
			return true;
		}
		/********************************************************************************
		* Funcion donde se calcula el numero de cuentas que posee un factura y ademas si 
		* si pasa el numero de la factura y la glosa se retorma tambien el numero de 
		* cuenta y si dicha cuenta esta glosada o no 
		* 
		* @ params int numero de la factura, por defecto es nulo 
		* 		   int numero de la glosa  
		* @return string 
		*********************************************************************************/
		function ObtenerCantidadCuentasFactura($num = null,$glosa = null)
		{
			$numero = $num;
			if($numero == null)
			{
				$numero = $_REQUEST['factura_numero'];
			}
			$factura = explode(" ",$numero);
			
			$sql  = "SELECT COUNT(*) ";
			$sql .= "FROM 	fac_facturas_cuentas ";
			$sql .= "WHERE 	prefijo = '".$factura[0]."' ";
			$sql .= "AND 		factura_fiscal = ".$factura[1]." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if (!$rst->EOF)
			{
				$datos = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			
			if($num != null)
			{		
				$sql  = "SELECT FF.numerodecuenta,";
				$sql .= "				G.sw_estado ";			
				$sql .= "FROM ingresos I,fac_facturas_cuentas FF,planes P,pacientes PA, ";
				$sql .= "	  fac_facturas F,cuentas C LEFT JOIN glosas_detalle_cuentas G ";
				$sql .= "	  ON(C.numerodecuenta = G.numerodecuenta ";
				$sql .= "		 AND G.sw_estado <> '0' ";
				$sql .= "		 AND G.glosa_id = ".$glosa." ) ";
				$sql .= "WHERE F.empresa_id = '".$_SESSION['glosas']['empresa_id']."' ";
				$sql .= "AND F.prefijo = '".$factura[0]."' ";
				$sql .= "AND F.factura_fiscal = ".$factura[1]." ";
				$sql .= "AND FF.prefijo = F.prefijo ";
				$sql .= "AND FF.factura_fiscal = F.factura_fiscal ";
				$sql .= "AND FF.empresa_id = F.empresa_id ";
				$sql .= "AND C.numerodecuenta = FF.numerodecuenta ";
				$sql .= "AND C.ingreso = I.ingreso ";
				$sql .= "AND I.tipo_id_paciente = PA.tipo_id_paciente ";
				$sql .= "AND I.paciente_id = PA.paciente_id ";
				$sql .= "AND C.plan_id = P.plan_id ";

				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				if (!$rst->EOF)
				{
					$datos .= "/".$rst->fields[0]."/".$rst->fields[1];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			return $datos;
		}
		/****************************************************************************************
		*
		*****************************************************************************************/
		function ResponderGlosa()
		{
			$this->request = $_REQUEST;

			$this->actiong['volver'] = ModuloGetURL('app','Glosas','user','FormaMostrarDetalleCuenta',array("datos_glosa"=>$this->request['datos_glosa']));
			$this->actiong['nota'] = ModuloGetURL('app','Glosas','user','MostrarInformacionFacturas');

			$this->request['factura_f'] = $this->request['datos_glosa']['factura_fiscal'];
			$this->request['glosa_id'] = $this->request['datos_glosa']['glosa_id'];
			$this->request['prefijo'] = $this->request['datos_glosa']['prefijo'];
			$this->request['sistema'] = SessionGetVar("SistemaFactura");
			$this->empresa =	$_SESSION['glosas']['empresa_id'];
			
			$_REQUEST = $this->request;
			
			return true;
		}
		/****************************************************************************************
		*
		*****************************************************************************************/
		function ResponderGlosaTotal()
		{
			$this->request = $_REQUEST;
			$rst = true;
						//echo "<pre>".print_r($this->request,true)."</pre>";

			if(!$this->request['datos_glosa']['glosa_id'])
			{
				$_REQUEST['prefijo'] = $this->request['datos_glosa']['prefijo'];
				$_REQUEST['factura_fiscal'] = $this->request['datos_glosa']['factura_fiscal'];
				
				$rst = $this->IngresarGlosa("RS");
			}
			
			if($this->dat)
			{
				$this->request['datos_glosa']['factura_fiscal'] = $this->dat['factura'];
				$this->request['datos_glosa']['glosa_id'] = $this->dat['glosa_id'];
				$this->request['datos_glosa']['prefijo'] = $this->dat['prefijo'];
				
				$this->request['factura_f'] = $this->dat['factura'];
				$this->request['glosa_id'] = $this->dat['glosa_id'];
				$this->request['prefijo'] = $this->dat['prefijo'];
			}
			else
			{
				$this->request['factura_f'] = $this->request['datos_glosa']['factura_fiscal'];
				$this->request['glosa_id'] = $this->request['datos_glosa']['glosa_id'];
				$this->request['prefijo'] = $this->request['datos_glosa']['prefijo'];
			}
			
			$this->actiong['volver'] = ModuloGetURL('app','Glosas','user','ConsultarInformacionGlosa',$this->request['datos_glosa']);
			$this->actiong['nota'] = ModuloGetURL('app','Glosas','user','MostrarInformacionFacturas');
			
			$this->request['sistema'] = $_SESSION['Glosas']['sistema'];
			$this->empresa =	$_SESSION['glosas']['empresa_id'];
			
			$_REQUEST = $this->request;
			return $rst;
		}
		/***********************************************************************************************
		* Forma donde se muestran los cargos que han sido glosados 
		************************************************************************************************/
		function GuardarGlosa()
		{
			$this->request = $_REQUEST;
			IncludeClass('Glosas','','app','Glosas');
			$gl = new Glosas();
			$rst = false;

			if(!$this->request['glcuenta'])
			{
				$observaciones = SessionGetVar("ObservacionesCargos");
				$rst = $gl->IngresarGlosaCargosInsumos($this->request,$observaciones);
			}
			else
			{
				$rst = $gl->IngresarGlosaCuenta($this->request);
			}
			
			if($rst) 
			{
				SessionDelVar("ObservacionesCargos");
				$this->frmError['MensajeError'] = "LA GLOSA SOBRE LA CUENTA Y/O EL DETALLE DE LA MISMA SE HA REGISTRADO DE FORMA CORRECTA";
			}
			else
				$this->frmError['MensajeError'] = $gl->frmError['MensajeError'];
			
			if($this->request['sw_responder'])
				$this->action['volver'] = ModuloGetURL('app','Glosas','user','FormaResponderGlosa',array("datos_glosa"=>$this->request['datos_glosa']));
			else
				$this->action['volver'] = ModuloGetURL('app','Glosas','user','FormaMostrarDetalleCuenta',array("datos_glosa"=>$this->request['datos_glosa']));
		}
		/****************************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*****************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
			
			if ($dbconn->ErrorNo() != 0)
			{
				echo $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg() ."<br>".$sql;
				return false;
			}
			return $rst;
		}		
	}//Fin de la clase
?>