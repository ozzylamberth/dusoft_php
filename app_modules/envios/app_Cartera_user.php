<?php
	/***********************************************************************************
	* $Id: app_Cartera_user.php,v 1.22 2007/08/09 19:44:11 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	*
	* Contiene la clase user del módulo de Cartera
	*
	* Clase user del módulo Cartera, el módulo Cartera se encarga de mostrar
	* los saldos de la facturación para cada cliente
	* 
	* @author Hugo Freddy Manrique
	* Codigo tomado de otra version de cartera
	*		@author Carlos Henao
	* 	@author Ehudes Fernán García <efgarcia@ipsoft-sa.com>
	*
	* @package IPSOFT-SIIS-FI-CARTERA
	*************************************************************************************/
 	IncludeClass('app_Cartera_Notas','','app','Cartera');
	class app_Cartera_user extends classModulo
	{
		function app_Cartera_user(){}
		/********************************************************************************
		* Función principal del módulo
		*
		* @access private
		*********************************************************************************/
		function main()
		{
			$this->MostrarMenuEmpresasCartera();
			return true;
		}
		/********************************************************************************
		* Muestra el menu de las empresas y centros de utilidad
		* 
		* @access public 
		*********************************************************************************/
		function MostrarMenuEmpresasCartera()
		{
			unset($_SESSION['cartera']);
			$empresas=$this->BuscarEmpresasUsuario();
			$mtz[0]='EMPRESAS';
			$mtz[1]='CENTRO DE UTILIDAD';
			$url[0]='app';							//contenedor
			$url[1]='Cartera';						//módulo
			$url[2]='user';							//clase
			$url[3]='MostrarMenuPrincipalCartera';	//método 
			$url[4]='permisocartera';				//indice del request
			
			$this->salida .= gui_theme_menu_acceso('CARTERA', $mtz, $empresas, $url, ModuloGetURL('system','Menu'));
			return true;
		}
		/********************************************************************************
		* Retorna las empresas a las cuales tiene permisos el usuario de acceder
		* 
		* @access public
		*********************************************************************************/
		function BuscarEmpresasUsuario()
		{
			$sql .= "SELECT A.empresa_id AS empresa_id, ";
			$sql .= "				B.razon_social AS razon_social, ";
			$sql .= "				A.centro_utilidad AS centro_utilidad, ";
			$sql .= "				C.descripcion AS descripcion_centro_utilidad ";
			$sql .= "FROM 	userpermisos_cartera AS A, ";
			$sql .= "				empresas AS B, ";
			$sql .= "				centros_utilidad AS C ";
			$sql .= "WHERE 	A.usuario_id = ".UserGetUID()." ";
			$sql .= "AND 		A.empresa_id = B.empresa_id ";
			$sql .= "AND 		A.centro_utilidad=C.centro_utilidad ";
			$sql .= "AND 		A.empresa_id=C.empresa_id;";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while(!$rst->EOF)
			{
				$empresas[$rst->fields[1]][$rst->fields[3]]=$rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $empresas;
		}
		/********************************************************************************
		* Muestra el menú principal de cartera.
		*
		* @access public
		*********************************************************************************/
		function MostrarMenuPrincipalCartera()
		{
			unset($_SESSION['cartera']['sistema']);
			SessionDelVar("VectorCartera");
			if(empty($_SESSION['cartera']['empresa_id']))
			{
				$_SESSION['cartera']['empresa_id'] = $_REQUEST['permisocartera']['empresa_id'];
				$_SESSION['cartera']['razon_social'] = $_REQUEST['permisocartera']['razon_social'];
			}
			
			if(empty($_SESSION['cartera']['Enviados']) || empty($_SESSION['cartera']['NoEnviados']))
			{
				$this->ObtenerNombresTerceros(1);
			}
			
			IncludeClass('Cartera','','app','Cartera');
			$ctr = new Cartera();
			$anticipos = $ctr->ObtenerAnticipos($_SESSION['cartera']['empresa_id']);
			
			SessionSetvar("AnticiposCartera",$anticipos);
			
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarMenuEmpresasCartera');
			$this->action3 = ModuloGetURL('app','Cartera','user','MostrarCarteraClientes');
			$this->action4 = ModuloGetURL('app','Cartera','user','MostrarCarteraClientesNoEnviada');
			$this->action5 = ModuloGetURL('app','Cartera','user','SubMenuMovimientos');
			$this->action6 = ModuloGetURL('app','Cartera','user','MostrarCarteraPlanes',array("envio"=>'1'));
			$this->action7 = ModuloGetURL('app','Cartera','user','MostrarCarteraPlanes',array("envio"=>'0'));
			$this->action8 = ModuloGetURL('app','Cartera','user','FormaMostrarConsultaTodo');
			$this->FormaMostrarMenuPrincipalCartera();
			return true;
		}
		/********************************************************************************
		* Funcion que permite mostrar la cartera de todos los clientes que posee la 
		* empresa seleccionada
		*
		* @return boolean 
		*********************************************************************************/
		function MostrarCarteraClientes()
		{
			$this->RazonSocial = $_SESSION['cartera']['razon_social'];
			$_SESSION['enviada'] = '1';
			
			$this->anticipos = SessionGetvar("AnticiposCartera");
			
			$this->request = $_REQUEST;
			
			$this->Cliente = $_REQUEST['nombre_tercero'];
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FacturaFiscal = $_REQUEST['factura_f'];
			$this->PeriodoSeleccionado = $_REQUEST['periodo'];
			
			if($this->request['nombre_tercero'] || $this->request['periodo'])
				SessionDelVar("VectorCartera");
			
			$rst = SessionGetVar("VectorCartera");
			if(!SessionIsSetVar("VectorCartera"))
			{
				IncludeClass('Cartera','','app','Cartera');
				$crt = new Cartera();
				$rst = $crt->ConsultarCarteraClientes($this->request,$_SESSION['cartera']['empresa_id']);
			}
			
			$this->Arreglo = $rst['cartera'];
			$this->Intervalos =  $rst['intervalos'];
			$this->TotalCartera =  $rst['total_cartera'];
			
			SessionSetVar("VectorCartera",$rst);
			
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarMenuPrincipalCartera');
			$this->action2['buscador'] = ModuloGetURL('app','Cartera','user','MostrarCarteraClientes');
			$this->action3 = ModuloGetURL('app','Cartera','user','MostrarCarteraClientes');
			
			$this->action2['envios'] = ModuloGetURL('app','Cartera','user','FormaMostrarEnviosCliente');
			$this->FormaMostrarCarteraClientes();
		
			return true;
		}
		/********************************************************************************
		* Funcion donde se muestra la cartera de un cliente seleccionado
		*
		* @return boolean
		*********************************************************************************/
		function MostrarCarteraClienteSel()
		{
			unset($_SESSION['cartera']['retorno']);
			$terceros = $_SESSION['cartera']['Enviados'];
			
			$this->TerceroC = explode(" ",$_REQUEST['cliente_id']);
			foreach($terceros as $key => $cliente)
			{
				if($cliente['tipo_id_tercero'] == $this->TerceroC[0] && $cliente['tercero_id'] == $this->TerceroC[1] )
				{
					$this->NombreCliente = $cliente['nombre_tercero'];
					break;
				}
			}
			
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarCarteraClientes',
																	   array("periodo"=>$_REQUEST['periodo'],"nombre_tercero"=>$_REQUEST['nombre_tercero']));
			$this->action2 = ModuloGetURL('app','Cartera','user','MostrarCarteraClienteSel',
																	   array("cliente_id"=>$_REQUEST['cliente_id']));
			
			$this->MetodoR = "MostrarCarteraClienteSel";
			$this->Registros = ModuloGetVar('app','Cartera','total_facturas');
			$this->FormaMostrarCarteraClienteSel();
			return true;
		}
		/********************************************************************************
		* Funcion que permite mostrar las facturas de un rango seleccionado para un 
		* cliente determinado
		* 
		* @return boolean 
		*********************************************************************************/
		function MostrarFacturasRango()
		{			
			$terceros = $_SESSION['cartera']['NoEnviados'];
			$this->request = $_REQUEST;
			
			$this->datos['rango'] = $this->request['rango'];
			$this->datos['intervalo'] = $this->request['intervalo'];
			$this->datos['direccion'] = $this->request['direccion'];
			$this->datos['diferencia'] = $this->request['diferencia'];
			
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			list($this->datos['tipo_id_tercero'],$this->datos['tercero_id']) = explode(" ",$this->request['cliente_id']);			

			foreach($terceros as $key => $cliente)
			{
				if($cliente['tipo_id_tercero'] == $this->datos['tipo_id_tercero'] && $cliente['tercero_id'] == $this->datos['tercero_id'])
				{
					$this->datos['nombre_tercero'] = $cliente['nombre_tercero'];
					break;
				}
			}
			
			$metodo =  $this->request['retorno1'];
			if($metodo == "") $metodo ="MostrarCarteraClienteSel";
			
			$this->Intervalo = $_SESSION['cartera']['retorno']['intervalo'];
			$this->Direccion = $_SESSION['cartera']['retorno']['direccion'];
			
			$this->action['volver'] = ModuloGetURL('app','Cartera','user',$metodo,
															array("periodo"=>$_REQUEST['periodo'],"cliente_id"=>$_REQUEST['cliente_id'],
																	 	"intervalo"=>$this->Intervalo,"direccion"=>$this->Direccion));
			
			$this->ConsultarFacturasRangos($this->datos);
			$this->FormaMostrarFacturasRango();
			return true;
		}
		/********************************************************************************
		* 
		*********************************************************************************/
		function MostrarCarteraClientesNoEnviada()
		{
			$this->RazonSocial = $_SESSION['cartera']['razon_social'];
			$this->request = $_REQUEST;
			$this->Cliente = $_REQUEST['nombre_tercero'];
			$this->PeriodoSeleccionado = $_REQUEST['periodo'];
			
			$_SESSION['enviada'] = '0';
			$this->request = $_REQUEST;

			if($this->request['nombre_tercero'] || $this->request['periodo'])
				SessionDelVar("VectorCartera");
			
			$rst = SessionGetVar("VectorCartera");
			if(!SessionIsSetVar("VectorCartera"))
			{
				IncludeClass('Cartera','','app','Cartera');
				$crt = new Cartera();
				$rst = $crt->ConsultarCarteraClientesNoRadicada($this->request,$_SESSION['cartera']['empresa_id']);
			}
			
			
			$this->Arreglo = $rst['cartera'];
			$this->Intervalos =  $rst['intervalos'];
			$this->TotalCartera =  $rst['total_cartera'];
			
			SessionSetVar("VectorCartera",$rst);
			
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarMenuPrincipalCartera');
			$this->action2['buscador'] = ModuloGetURL('app','Cartera','user','MostrarCarteraClientesNoEnviada');
			$this->FormaMostrarCarteraClientesNoEnviada();
			
			return true;
		}
		/********************************************************************************
		*
		* @return boolean
		*********************************************************************************/
		function MostrarCarteraClienteNOEnviadaSel()
		{
			$terceros = $_SESSION['cartera']['NoEnviados'];
			
			$this->TerceroC = explode(" ",$_REQUEST['cliente_id']);
			foreach($terceros as $key => $cliente)
			{
				if($cliente['tipo_id_tercero'] == $this->TerceroC[0] && $cliente['tercero_id'] == $this->TerceroC[1] )
				{
					$this->NombreCliente = $cliente['nombre_tercero'];
					break;
				}
			}
			unset($_SESSION['cartera']['retorno']);
			
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarCarteraClientesNoEnviada',
																	   array("periodo"=>$_REQUEST['periodo'],"nombre_tercero"=>$_REQUEST['nombre_tercero']));
			$this->action2 = ModuloGetURL('app','Cartera','user','MostrarCarteraClienteNOEnviadaSel',
										   							 array("cliente_id"=>$_REQUEST['cliente_id']));
			
			$this->MetodoR = "MostrarCarteraClienteNOEnviadaSel";
			$this->Registros = ModuloGetVar('app','Cartera','total_facturas');
			$this->FormaMostrarCarteraClienteSel();
			return true;
		}
		/******************************************************************************** 
		* Funcion donde se seleccionan el nombre de los terceros que son clientes y 
		* tienen envios radicados para que se pueda filtrar por ellos en una busqueda  
		* 
		* @return array datos de tipo_id_terceros 
		*********************************************************************************/
		function ObtenerNombresTerceros()
		{
			$empresa = $_SESSION['cartera']['empresa_id'];
			$datos = array();
			$documentos = array();
			
			$sql  = "SELECT DISTINCT TE.nombre_tercero, ";
			$sql .= "				TE.tipo_id_tercero,";
			$sql .= "				TE.tercero_id ";
			$sql .= "FROM   fac_facturas FF, ";
			$sql .= "				terceros TE ";			
			$sql .= "WHERE	TE.tipo_id_tercero = FF.tipo_id_tercero ";
			$sql .= "AND		TE.tercero_id = FF.tercero_id ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND    FF.fecha_vencimiento_factura IS NULL ";
			$sql .= "AND    FF.saldo > 0 ";
			$sql .= "AND		FF.estado = '0'::bpchar ";
			$sql .= "AND    FF.sw_clase_factura = '1'::bpchar ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$documentos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$datos[] = $documentos[$rst->fields[0]];
				$rst->MoveNext();
			}
			$rst->Close();
			
			if(!empty($datos))	$_SESSION['cartera']['NoEnviados'] = $datos;

			$sql  = "SELECT DISTINCT TE.nombre_tercero, ";
			$sql .= "      	FF.tipo_id_tercero, ";
			$sql .= "      	FF.tercero_id  ";				
			$sql .= "FROM		terceros TE,";
			$sql .= "				facturas_externas FF ";
			$sql .= "WHERE	TE.tipo_id_tercero = FF.tipo_id_tercero ";
			$sql .= "AND		TE.tercero_id = FF.tercero_id ";
			$sql .= "AND		FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.estado = '0'::bpchar ";
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while (!$rst->EOF)
			{
				if(empty($documentos[$rst->fields[0]]))
				{
					$documentos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				}
				$rst->MoveNext();
		  }
			$rst->Close();
			ksort($documentos);
			$i = 0;
			unset($datos);
			foreach($documentos as $key => $terceros)
				$datos[$i++] = $terceros;
			
			if(!empty($datos)) $_SESSION['cartera']['Enviados'] = $datos;
			
			return $datos;
		}
		/********************************************************************************
		* Funcion donde se consultan las factura que pertenecen a un rango determinado
		* para un cliente X
		*
		* @return boolean
		*********************************************************************************/
		function ConsultarFacturasRangos($datos)
		{
			$sql .= "SELECT TO_CHAR(FF.fecha_registro,'YYYY-MM') AS intervalo , ";
			$sql .= "				FF.prefijo,";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "  			FF.saldo AS saldo,  ";
			$sql .= "				COALESCE(GL.valor_glosa,0) AS valor_glosa, ";
			$sql .= "				COALESCE(GL.valor_aceptado,0) AS valor_aceptado, ";
			$sql .= "				COALESCE(GL.valor_no_aceptado,0) AS valor_no_aceptado, ";
			$sql .= "  			COALESCE(GL.valor_pendiente,0) AS valor_pendiente, ";
			$sql .= "				FF.total_factura AS total, ";
			$sql .= "  			(FF.fecha_registro::date - NOW()::date) / 30 AS diferencia ";
			$sql .= "FROM		fac_facturas FF LEFT JOIN  ";
			$sql .= "				(SELECT SUM(valor_pendiente) AS valor_pendiente,  ";
			$sql .= "      					SUM(valor_glosa) AS valor_glosa,  ";
			$sql .= "      					SUM(valor_aceptado) AS valor_aceptado,  ";
			$sql .= "      					SUM(valor_no_aceptado) AS valor_no_aceptado,";
			$sql .= "      					empresa_id,  ";
			$sql .= "      					prefijo,  ";
			$sql .= "      					factura_fiscal ";
			$sql .= "         FROM	glosas ";
			$sql .= "	      	WHERE sw_estado <> '0' ";
			$sql .= "					AND	  empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "	      	GROUP BY 5,6,7) AS GL  ";
			$sql .= "        ON(GL.prefijo = FF.prefijo AND ";
			$sql .= "       	 	GL.factura_fiscal = FF.factura_fiscal AND ";
			$sql .= "       	 	GL.empresa_id = FF.empresa_id ) ";
			$sql .= "WHERE  FF.empresa_id = '".$datos['empresa_id']."' ";
			$sql .= "AND    FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
			$sql .= "AND    FF.tercero_id = '".$datos['tercero_id']."' ";
			$sql .= "AND    FF.fecha_vencimiento_factura IS NULL ";
			$sql .= "AND    FF.sw_clase_factura='1'::bpchar ";
			$sql .= "AND    FF.estado = '0'::bpchar ";
			$sql .= "AND    FF.saldo > 0 ";
			
			$filtro = "";
			if($datos['diferencia']*(-1) >= 7)
				$filtro = " <= -7 ";
			else if($datos['diferencia'] >= 7)
				$filtro = " >= 7 ";
				else
					$filtro = " = ".$datos['diferencia'];
			
			$sql .= "AND		((FF.fecha_registro::date - NOW()::date) / 30) ".$filtro." ";
			$sql .= "ORDER BY FF.prefijo,FF.factura_fiscal ";	
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$this->Totales = array();
			
			while(!$rst->EOF)
			{
				$factura = $rst->GetRowAssoc($ToUpper = false);
			
				$this->Facturas[$datos['rango']][] = $rst->GetRowAssoc($ToUpper = false);
				$this->Totales[0] += $factura['total'];
				$this->Totales[1] += $factura['saldo'];
				$this->Totales[2] += $factura['valor_glosa'];
				$this->Totales[3] += $factura['valor_aceptado'];
				$this->Totales[4] += $factura['valor_no_aceptado'];
				$this->Totales[5] += $factura['valor_pendiente'];
				$rst->MoveNext();
			}
		
			$rst->Close();			
			
			return true;
		}
		/******************************************************************************** 
		* Funcion donde se toma de la base de datos el nombre de un usuario
		* 
		* @param  int id del usuario
		* @return string nombre del auditor 
		*********************************************************************************/
		function ObtenerUsuarioNombre($id)
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
		/********************************************************************************
		*
		*********************************************************************************/
		function ConsultarFacturasClienteNoEnviadas()
		{
			$empresa = $_SESSION['cartera']['empresa_id'];
			
			$sql .= "SELECT TO_CHAR(FF.fecha_registro,'YYYY-MM') AS vencimiento , ";
			$sql .= "				FF.prefijo, ";
			$sql .= "				FF.factura_fiscal, ";
			$sql .= "  			FF.saldo ,  ";
			$sql .= "				COALESCE(GL.valor_glosa,0) AS valor_glosa, ";
			$sql .= "				COALESCE(GL.valor_aceptado,0) AS valor_aceptado, ";
			$sql .= "				COALESCE(GL.valor_no_aceptado,0) AS valor_no_aceptado, ";
			$sql .= "  			COALESCE(GL.valor_pendiente,0) AS valor_pendiente, ";
			$sql .= "				FF.total_factura AS total, ";
			$sql .= "  			(FF.fecha_registro::date - NOW()::date) / 30 AS diferencia ";
			$where .= "FROM  fac_facturas FF LEFT JOIN  ";
			$where .= "      (SELECT 	SUM(valor_pendiente) AS valor_pendiente,  ";
			$where .= "      		 			SUM(valor_glosa) AS valor_glosa,  ";
			$where .= "      		 			SUM(valor_aceptado) AS valor_aceptado,  ";
			$where .= "      		 			SUM(valor_no_aceptado) AS valor_no_aceptado,";
			$where .= "      		 			prefijo,  ";
			$where .= "      		 			factura_fiscal ";
			$where .= "        FROM	 	glosas ";
			$where .= "	       WHERE 	sw_estado <> '0' ";
			$where .= "	       GROUP BY 5,6) AS GL  ";
			$where .= "      	ON(	GL.prefijo = FF.prefijo AND ";
			$where .= "       		GL.factura_fiscal = FF.factura_fiscal) ";			
			$where .= "WHERE  FF.empresa_id = '".$empresa."' ";
			$where .= "AND    FF.tipo_id_tercero = '".$this->TerceroC[0]."' ";
			$where .= "AND    FF.tercero_id = '".$this->TerceroC[1]."'";;
			$where .= "AND    FF.sw_clase_factura='1' ";
			$where .= "AND    FF.estado = '0' ";
			$where .= "AND    FF.saldo > 0 ";
			$where .= "AND		FF.fecha_vencimiento_factura IS NULL ";
			
			$sqlCont = "SELECT COUNT(*) ".$where;
			$this->ProcesarSqlConteo($sqlCont);
			
			$sql .= $where;
			$sql .= "ORDER BY diferencia,2,3 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;	
			while(!$rst->EOF)
			{
				$facturas[$i]  = $rst->GetRowAssoc($ToUpper = false);
			 	$dif = $facturas[$i]['diferencia'];
				
				if($dif == 0)
				{ 
					$nombre = "ESTE MES";
					$facturas[$i]['intervalo'] = "0";
					$facturas[$i]['direccion'] = "2";
				}
				else
				{
					$facturas[$i]['direccion'] = "0";
					if($dif < 0)
					{
					 	$a = $dif*(-1);
					 	$facturas[$i]['direccion'] = "1";
					}
						
					if($a == 1 )
					{
						$nombre = "A  30 DÍAS";
						$facturas[$i]['intervalo'] = "30";
					}
					else if($a == 2)
						{
							$nombre = "A  60 DÍAS";
							$facturas[$i]['intervalo'] = "60";
						}
						else if($a == 3)  
							{
								$nombre = "A  90 DÍAS";
								$facturas[$i]['intervalo'] = "90";
							}
							else if($a == 4)
								{ 
									$nombre = "A 120 DÍAS";
									$facturas[$i]['intervalo'] = "120";
								}
								else if($a == 5) 
									{
										$nombre = "A 150 DÍAS";
										$facturas[$i]['intervalo'] = "150";
									}
									else if($a == 6) 
										{
											$nombre = "A 180 DÍAS";
											$facturas[$i]['intervalo'] = "180";
										}
										else 
										{
											$nombre = " MAS DE 180";
											$facturas[$i]['intervalo'] = "190";
										}
				}
				$facturas[$i]['nombre'] = $nombre;
				
				$this->Facturas2[$nombre][$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			
			$rst->Close();			
			return $facturas;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function SubMenuMovimientos()
		{
			SessionDelVar("TodosEnvios");
			
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarMenuPrincipalCartera');
			$this->action2 = ModuloGetURL('app','Cartera','user','BuscarInformacionFactura');
			$this->action3 = ModuloGetURL('app','Cartera','user','FormaMostrarRecibosCaja');
			$this->action4 = ModuloGetURL('app','Cartera','user','FormaInformacionNotasAjuste');
			$this->action5 = ModuloGetURL('app','Cartera','user','FormaMostrarTodasFacturas');
			$this->FormaSubMenuMovimientos();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarInformacionRecibosCaja()
		{		
			$this->request = $_REQUEST;
			$this->TerceroId = $_REQUEST['documento_id'];
			$this->NombreTercero = $_REQUEST['nombre_tercero'];
			$this->TipoIdTercero = $_REQUEST['tipo_id_tercero'];
			
			$request = array("documento_id"=>$this->request['documento_id'],"nombre_tercero"=>$this->request['nombre_tercero'],
											 "tipo_id_tercero"=>$this->request['tipo_id_tercero']);
			
			$this->action = ModuloGetURL('app','Cartera','user','SubMenuMovimientos');
			$this->actionL['buscador'] = ModuloGetURL('app','Cartera','user','BuscarInformacionRecibosCaja');
			$this->actionL['paginador'] = ModuloGetURL('app','Cartera','user','BuscarInformacionRecibosCaja',$request);
			
			$this->FormaMostrarInformacionRecibosCaja();
			return true;
		}
		/***************************************************************************************
		*
		****************************************************************************************/
		function MostrarRecibosCaja()
		{
			$this->request = $_REQUEST;
			IncludeClass('CarteraRecibos','','app','Cartera');
			$cr = new CarteraRecibos();
			$this->terceros = $cr->ObtenerTercerosRecibos($_SESSION['cartera']['empresa_id']);
			$this->Prefijos = $cr->ObtenerPrefijosRecibos($_SESSION['cartera']['empresa_id']);
			$this->Recibos = $cr->ObtenerRecibosCaja($_SESSION['cartera']['empresa_id'],$this->request);
			
			$this->conteo = $cr->conteo;
			$this->paginaActual = $cr->paginaActual; 
			
			$this->datos['numero'] = $this->request['numero'];
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			$this->datos['prefijo'] = $this->request['prefijo'];
			$this->datos['tercero'] = $this->request['tercero'];
			$this->datos['fecha_fin'] = $this->request['fecha_fin'];
			$this->datos['tercero_id'] = $this->request['tercero_id'];
			$this->datos['fecha_inicio'] = $this->request['fecha_inicio'];
			$this->datos['tipo_id_tercero'] = $this->request['tipo_id_tercero'];
			
			$this->action[1] = ModuloGetURL('app','Cartera','user','SubMenuMovimientos');
			$this->action[2] = ModuloGetURL('app','Cartera','user','FormaMostrarRecibosCaja');
			$this->action[3] = ModuloGetURL('app','Cartera','user','FormaMostrarRecibosCaja',$this->datos);

			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function InformacionNotasAjuste()
		{
			$this->request = $_REQUEST;
			
			$this->Empresa = $_SESSION['cartera']['empresa_id'];
			$documento = ModuloGetVar('app','FacturacionNotaCreditoAjuste','documento');
			
			$this->datos = array();
			$this->datos['empresa'] = $this->Empresa;
			$this->datos['numero'] = $this->request['numero'];
			$this->datos['prefijo'] = $this->request['prefijo'];
			$this->datos['fecha_fin'] = $this->request['fecha_fin'];
			$this->datos['tipo_nota'] = $this->request['tipo_nota'];
			$this->datos['fecha_inicio'] = $this->request['fecha_inicio'];
			
			$nts = new app_Cartera_Notas();
			$this->Notas = $nts->ObtenerNotasDeAjuste($this->request,$this->Empresa);
			$this->Prefijos = $nts->ObtenerPrefijos($this->Empresa);
			
			$this->conteo = $nts->conteo;
			$this->paginaActual = $nts->paginaActual; 
			
			$this->action[1] = ModuloGetURL('app','Cartera','user','SubMenuMovimientos');
			$this->action[2] = ModuloGetURL('app','Cartera','user','FormaInformacionNotasAjuste');
			$this->action[3] = ModuloGetURL('app','Cartera','user','FormaMostrarDetalleNotaCredito');
			$this->action[4] = ModuloGetURL('app','Cartera','user','FormaInformacionNotasAjuste',$this->datos);

			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function MostrarDetalleNotaCredito()
		{
			$nts = new app_Cartera_Notas();
			
			$filtro = $_REQUEST['filtro'];
			$this->Datos = $_REQUEST['datos'];
			
			$empresa = $_SESSION['cartera']['empresa_id'];
			$this->Notas = $nts->ObtenerInformacionNota($this->Datos,$empresa);
			$this->Facturas = $nts->ObtenerFacturasCruzadasNA($this->Datos,$empresa);
			$this->ConceptosV = $nts->ObtenerValorConceptosNA($this->Datos,$empresa);
			
			$this->action1 = ModuloGetURL('app','Cartera','user','FormaInformacionNotasAjuste',$filtro);
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarInformacionFactura()
		{
			$this->request = $_REQUEST;
			$this->empresa = $_SESSION['cartera']['empresa_id'];
			
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FacturaFiscal = $_REQUEST['factura_f'];
			if($_REQUEST['factura'])
			{
				$factura = explode(" ",$_REQUEST['factura']);
				$this->Prefijo = $factura[0];
				$this->FacturaFiscal = $factura[1];
			}
			
			$this->action1 = ModuloGetURL('app','Cartera','user','SubMenuMovimientos');
			$this->action2 = ModuloGetURL('app','Cartera','user','ObtenerFacturas',
																		 array("prefijo"=>$this->Prefijo,"factura"=>$this->FacturaFiscal));
			$this->action['buscador'] = ModuloGetURL('app','Cartera','user','ObtenerFacturas');
			$this->FormaInformacionFactura();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function MostrarInformacionFacturaRecibo()
		{
			$this->Factura = $_REQUEST['factura'];
			$this->ValorFactura = $_REQUEST['valor'];
			
			$this->action1 = ModuloGetURL('app','Cartera','user','ObtenerFacturas',
																		 array("factura"=>$this->Factura,"offset"=>$_REQUEST['pagina']));
			$this->FormaMostrarInformacionFacturaRecibo();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function MostrarInformacionFacturaNotas()
		{
			$this->Factura = $_REQUEST['factura'];
			$this->ValorFactura = $_REQUEST['valor'];
			
			$this->action1 = ModuloGetURL('app','Cartera','user','ObtenerFacturas',
																		 array("factura"=>$this->Factura,"offset"=>$_REQUEST['pagina']));
			$this->FormaMostrarInformacionFacturaNotas();
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtienen los prefijos de las facturas para incluirlos en el 
		* buscador
		* 
		* @return array 
		*************************************************************************************/
		function ObtenerPrefijos()
		{
			$empresa = $_SESSION['cartera']['empresa_id'];
			
			$sql  = "SELECT DISTINCT FF.prefijo ";
			$sql .= "FROM		vista_cartera FF ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$sql .= "AND		FF.estado = '0' ";	
			$sql .= "AND		FF.saldo > 0 ";		
			
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
		/***************************************************************************************
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		****************************************************************************************/
		function ObtenerValorConceptos()
		{
			$sql .= "SELECT	COALESCE(RCT.valor,0) AS valor,";
			$sql .= " 			RCT.naturaleza, ";
			$sql .= " 			RC.descripcion, ";
			$sql .= "				COALESCE(DE.descripcion,'NO ASOCIADO') AS departamento ";
			$sql .= "FROM 	rc_conceptos_tesoreria RC, ";
			
			switch($this->Estado)
			{
				case '0':
					$sql .= "				tmp_rc_detalle_tesoreria_conceptos RCT ";
					$sql .= "				LEFT JOIN departamentos DE ";
					$sql .= "				ON(DE.departamento = RCT.departamento) ";
					$sql .= "WHERE	RCT.tmp_recibo_id = ".$this->ReciboId." ";		
				break;
				case '1':
					$sql .= "				rc_detalle_tesoreria_conceptos RCT ";
					$sql .= "				LEFT JOIN departamentos DE ";
					$sql .= "				ON(DE.departamento = RCT.departamento) ";
					$sql .= "WHERE	RCT.recibo_caja = ".$this->ReciboId." ";
					$sql .= "AND		RCT.prefijo = '".$this->Prefijo."' ";
				break;
			}
			
			$sql .= "AND		RCT.empresa_id = '".$_SESSION['cartera']['empresa_id']."' ";
			$sql .= "AND		RCT.concepto_id = RC.concepto_id ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$datos[] =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerFacturas()
		{
			$this->Factura = array();
			$empresa = $_SESSION['cartera']['empresa_id'];
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FacturaFiscal = $_REQUEST['factura_f'];
			
			if($_REQUEST['factura'])
			{
				$factura = explode(" ",$_REQUEST['factura']);
				$this->Prefijo = $factura[0];
				$this->FacturaFiscal = $factura[1];
			}
			$sql  = "SELECT	FF.prefijo, ";
			$sql .= "				FF.factura_fiscal,  ";
			$sql .= "				FF.total_factura,  ";
			$sql .= "				FF.saldo,  ";
			$sql .= "				FF.retencion_fuente,  ";
			$sql .= "				FF.estado,  ";
			$sql .= "				FF.sistema,  ";
			$sql .= "				FF.registro,  ";
			$sql .= "				FF.nombre_tercero,  ";
			$sql .= "				GL.num_glosas,  ";
			$sql .= "				RC.num_recibos,  ";
			$sql .= "				SUM(NA.valor) AS num_notas 	";
			$sql .= "FROM	( SELECT	FF.prefijo, ";
			$sql .= "								FF.factura_fiscal, ";
			$sql .= "								FF.total_factura, ";
			$sql .= "								FF.saldo, ";
			$sql .= "								FF.retencion_fuente, ";
			$sql .= "								FF.estado, ";
			$sql .= "								FF.empresa_id, ";
			$sql .= "								'SIIS' AS sistema, ";
			$sql .= "								TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS registro,  ";
			$sql .= "								TE.nombre_tercero ";
			$sql .= "				FROM		fac_facturas FF, ";
			$sql .= "								terceros TE ";
			$sql .= "				WHERE		FF.empresa_id = '".$empresa."'   ";
			$sql .= "				AND			FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "				AND   	FF.tercero_id = TE.tercero_id ";
			$sql .= "				AND			FF.sw_clase_factura = '1'::bpchar ";
			$sql .= "				AND			FF.estado IN ('0'::bpchar,'1'::bpchar)   ";
			//$sql .= "				AND			FF.saldo > 0 ";
			//$sql .= "				AND			FF.fecha_vencimiento_factura IS NOT NULL ";
			if($this->Prefijo) $sql .= "AND		FF.prefijo = '".$this->Prefijo."' ";
			
			if($this->FacturaFiscal) $sql .= "AND		FF.factura_fiscal = ".$this->FacturaFiscal." ";

			$sql .= "				UNION ALL   ";
			$sql .= "				SELECT	FF.prefijo, ";
			$sql .= "								FF.factura_fiscal, ";
			$sql .= "								FF.total_factura, ";
			$sql .= "								FF.saldo, ";
			$sql .= "								0 AS retencion_fuente, ";
			$sql .= "								FF.estado, ";
			$sql .= "								FF.empresa_id, ";
			$sql .= "								'EXT' AS sistema, ";
			$sql .= "								TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS registro,  ";
			$sql .= "								TE.nombre_tercero ";
			$sql .= "				FROM		facturas_externas FF, ";
			$sql .= "								terceros TE ";
			$sql .= "				WHERE		FF.empresa_id = '".$empresa."'   ";
			$sql .= "				AND			FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "				AND   	FF.tercero_id = TE.tercero_id ";
			$sql .= "				AND			FF.estado IN ('0'::bpchar,'1'::bpchar)  ";
			//$sql .= "				AND			FF.saldo > 0			 ";
			$sql .= "				AND			FF.fecha_vencimiento IS NOT NULL ";
			if($this->Prefijo) $sql .= "AND		FF.prefijo = '".$this->Prefijo."' ";
			
			if($this->FacturaFiscal) $sql .= "AND		FF.factura_fiscal = ".$this->FacturaFiscal." ";

			$sql .= "				)AS FF  LEFT JOIN  ";
			$sql .= "				(	SELECT 	prefijo, ";
			$sql .= "									factura_fiscal, ";
			$sql .= "									empresa_id, ";
			$sql .= "									COUNT(*) AS num_glosas   ";
			$sql .= "					FROM	 	glosas   ";
			$sql .= "					WHERE		sw_estado <> '0'  ";
			$sql .= "					AND 		empresa_id = '".$empresa."' ";
			if($this->Prefijo) $sql .= "AND		prefijo = '".$this->Prefijo."' ";
			
			if($this->FacturaFiscal) $sql .= "AND		factura_fiscal = ".$this->FacturaFiscal." "; 

			$sql .= "					GROUP BY 1,2,3   ";
			$sql .= "				)AS GL   ";
			$sql .= "				ON(	FF.prefijo = GL.prefijo AND   ";
			$sql .= "						FF.factura_fiscal = GL.factura_fiscal AND ";
			$sql .= "						FF.empresa_id = GL.empresa_id ";
			$sql .= "				) LEFT JOIN   ";
			$sql .= "				(	SELECT 	prefijo_factura, ";
			$sql .= "									factura_fiscal, ";
			$sql .= "									empresa_id, ";
			$sql .= "									COUNT(*) AS num_recibos   ";
			$sql .= "					FROM		rc_detalle_tesoreria_facturas ";
			$sql .= "					WHERE		empresa_id = '".$empresa."' ";
			if($this->Prefijo) $sql .= "AND		prefijo_factura = '".$this->Prefijo."' ";
			
			if($this->FacturaFiscal) $sql .= "AND		factura_fiscal = ".$this->FacturaFiscal." "; 

			$sql .= "					GROUP BY 1,2,3   ";
			$sql .= "				)AS RC   ";
			$sql .= "				ON(	FF.prefijo = RC.prefijo_factura AND   ";
			$sql .= "						FF.factura_fiscal = RC.factura_fiscal  AND ";
			$sql .= "						FF.empresa_id = RC.empresa_id ";
			$sql .= "				) LEFT JOIN   ";
			$sql .= "				(	SELECT 	prefijo_factura,factura_fiscal,empresa_id,COALESCE(valor_nota,0) AS valor";
			$sql .= "					FROM		notas_debito ";
			$sql .= "					WHERE		empresa_id = '".$empresa."' ";
			if($this->FacturaFiscal) 
				$sql .= "					AND		factura_fiscal = ".$this->FacturaFiscal." "; 
			if($this->Prefijo) 
				$sql .= "					AND		prefijo_factura = '".$this->Prefijo."' ";
				
			$sql .= "					AND			estado = '1' ";
			$sql .= "					UNION ";
			$sql .= "					SELECT 	prefijo_factura,factura_fiscal,empresa_id,COALESCE(valor_nota,0) AS valor ";
			$sql .= "					FROM		notas_credito ";
			$sql .= "					WHERE		empresa_id = '".$empresa."' ";
			if($this->FacturaFiscal) 
				$sql .= "					AND		factura_fiscal = ".$this->FacturaFiscal." "; 
			if($this->Prefijo) 
				$sql .= "					AND		prefijo_factura = '".$this->Prefijo."' ";
			
			$sql .= "					AND			estado = '1' ";
			$sql .= "					UNION ";
			$sql .= "					SELECT 	prefijo_factura,factura_fiscal,empresa_id,COALESCE(SUM(valor_abonado),0) AS valor ";
			$sql .= "					FROM		notas_credito_ajuste_detalle_facturas ";
			$sql .= "					WHERE		empresa_id = '".$empresa."' ";
			$sql .= "					AND			valor_abonado > 0 ";
			if($this->FacturaFiscal) 
				$sql .= "					AND		factura_fiscal = ".$this->FacturaFiscal." "; 
			if($this->Prefijo) 
				$sql .= "					AND		prefijo_factura = '".$this->Prefijo."' ";
				
			$sql .= "					GROUP BY prefijo_factura,factura_fiscal,empresa_id";
			$sql .= "				)AS NA   ";
			$sql .= "				ON(	FF.prefijo = NA.prefijo_factura AND   ";
			$sql .= "						FF.factura_fiscal = NA.factura_fiscal AND ";
			$sql .= "						FF.empresa_id = NA.empresa_id ";
			$sql .= "				)";
			$sql .= "WHERE	FF.empresa_id = '".$empresa."' ";
			$sql .= "GROUP BY 1,2,3,4,5,6,7,8,9,10,11 ";
			
			$cont = 0;
			if(!$this->FacturaFiscal)
			{
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				if(!$rst->EOF) $cont = $rst->RecordCount();
			}
			
			if(!$this->FacturaFiscal)
				$this->ProcesarSqlConteo("",null,$cont);
			
			$sql .= "ORDER BY FF.registro,FF.prefijo,FF.factura_fiscal DESC ";
			
			if(!$this->FacturaFiscal)
				$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$this->Factura[$i]  = $rst->GetRowAssoc($ToUpper = false);				
				$arreglo['pagina'] = $this->PaginaActual;
				$arreglo['retorno'] = "ObtenerFacturas";
				$arreglo['sistema'] = $this->Factura[$i]['sistema'];
				$arreglo['factura'] = $this->Factura[$i]['prefijo']." ".$this->Factura[$i]['factura_fiscal'];
				
				$this->action4[$i] = ModuloGetURL('app','Cartera','user','MostrarConsultaFactura',$arreglo);
				$this->action5[$i] = ModuloGetURL('app','Cartera','user','MostrarInformacionFactura',$arreglo);
				
				$arreglo['valor'] = $this->Factura[$i]['total_factura'];
				$this->action6[$i] = ModuloGetURL('app','Cartera','user','MostrarInformacionFacturaRecibo',$arreglo);
				$this->action7[$i] = ModuloGetURL('app','Cartera','user','MostrarInformacionFacturaNotas',$arreglo);
				
				$rst->MoveNext();
				$i++;
		  }
		  $rst->Close();
		  $this->BuscarInformacionFactura();
		  return true;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerInfoRecibosFactura()
		{
			$factura = explode(" ",$this->Factura); 
			
			$sql .= "SELECT	'TMP' AS prefijo, ";
			$sql .= "				RF.tmp_recibo_id AS recibo, ";
			$sql .= "				RF.valor_abonado, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				'TEMPORAL' AS estado, ";
			$sql .= "				TO_CHAR(RC.fecha_registro,'DD/MM/YYYY') AS registro ";
			$sql .= "FROM		tmp_rc_detalle_tesoreria_facturas RF, ";
			$sql .= "				tmp_recibos_caja RC, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	RC.tmp_recibo_id = RF.tmp_recibo_id ";
			$sql .= "AND		RC.empresa_id = '".$_SESSION['cartera']['empresa_id']."' ";
			$sql .= "AND		RF.prefijo_factura = '".$factura[0]."' ";
			$sql .= "AND		RF.factura_fiscal = ".$factura[1]." ";
			$sql .= "AND		RC.usuario_id = SU.usuario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT	RC.prefijo, ";
			$sql .= "				RF.recibo_caja AS recibo, ";
			$sql .= "				RF.valor_abonado, ";
			$sql .= "				SU.nombre, ";
			$sql .= "				'CERRADO' AS estado, ";
			$sql .= "				TO_CHAR(RC.fecha_registro,'DD/MM/YYYY') AS registro ";
			$sql .= "FROM		rc_detalle_tesoreria_facturas RF, ";
			$sql .= "				recibos_caja RC, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	RC.recibo_caja = RF.recibo_caja ";
			$sql .= "AND		RC.prefijo = RF.prefijo ";
			$sql .= "AND		RC.empresa_id = '".$_SESSION['cartera']['empresa_id']."' ";
			$sql .= "AND		RF.prefijo_factura = '".$factura[0]."' ";
			$sql .= "AND		RF.factura_fiscal = ".$factura[1]." ";
			$sql .= "AND		RC.usuario_id = SU.usuario_id ";
			$sql .= "ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
		/********************************************************************************
		* Funcion dondes se llamsn los metodos y se crean las variables necesarias para 
		* que se pueda mostrar la forma donde se muestra la información de la cartera
		*********************************************************************************/
		function MostrarCarteraPlanes()
		{
			unset($_SESSION['cartera']['planes']);
			if($_REQUEST['envio'] != "" ) $_SESSION['cartera']['envio'] = $_REQUEST['envio'];
			
			$this->envio = $_SESSION['cartera']['envio'];
			
			if($this->request['nombre_tercero'] || $this->request['periodo'])
				SessionDelVar("VectorCartera");
			
			$rst = SessionGetVar("VectorCartera");
			if(!SessionIsSetVar("VectorCartera"))
			{
				IncludeClass('Cartera','','app','Cartera');
				$crt = new Cartera();
				$rst = $crt->ConsultarCarteraPlanes($this->envio,"",$_SESSION['cartera']['empresa_id']);
			}
			$this->Arreglo = $rst['cartera'];
			$this->Intervalos =  $rst['intervalos'];
			$this->TotalCartera =  $rst['total_cartera'];
			SessionSetVar("VectorCartera",$rst);
			
			$this->RazonSocial = $_SESSION['cartera']['razon_social'];
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarMenuPrincipalCartera');
			
			$this->FormaMostrarCarteraPlanes();
			return true;
		}
		/********************************************************************************
		* Funcion donde se crean las variables para mostrar la cartera por planes
		*********************************************************************************/
		function MostrarFacturasPlan()
		{
			$datos = $_REQUEST;
			
			if(empty($_SESSION['cartera']['planes']['plan']))
			{
				$_SESSION['cartera']['planes']['plan'] = $datos['plan_id'];
				$_SESSION['cartera']['planes']['descripcion'] = $datos['nombre_plan'];
			}
			unset($_SESSION['cartera']['planes']['intervalo']);
			unset($_SESSION['cartera']['planes']['direccion']);
				
			$this->PlanId = $_SESSION['cartera']['planes']['plan'];
			$this->PlanNombre = $_SESSION['cartera']['planes']['descripcion'];
			$this->Registros = ModuloGetVar('app','Cartera','total_facturas');
			$envio = $_SESSION['cartera']['envio'];
			if(!$this->Registros) $this->Registros = 100;
			
			$this->Facturas = $this->ConsultarFacturasPlan($this->PlanId,$_SESSION['cartera']['empresa_id'],$envio);
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarCarteraPlanes');
		}
		/********************************************************************************
		* Funcion donde se consulta la informacion de la cartera de un plan 
		* determinado, se evalua la diferencia entre la fecha de vencimiento y la fecha 
		* actual para determinar las facturas a que rango pertenencen, se cuenta la 
		* cantidad de registros encontrdaos para determinar si se muesran las facturas en
		* un listado o se presentan agrupadas por el rango 
		*
		* @param  int $plan_id Variable que identifica el plan al clual se le averiguara 
		* 										 la cartera
		* @param  int $empresa Variable que identifica la empresa a la que pertenece la 
		* 										 cartera
		* @param  char $envio	 Indica si la cartera que se va a consultar es la enviada o
		*											 la no enviada
		* @return array Facturas que pertenecen al plan seleccionado
		*********************************************************************************/
		function ConsultarFacturasPlan($plan_id,$empresa,$envio)
		{
			if($envio == '1')
				$sql .= "SELECT 	(FF.fecha_vencimiento_factura::date - NOW()::date)/30 AS diferencia, ";
			else if($envio == '0')
				$sql .= "SELECT 	(FF.fecha_registro::date - NOW()::date)/30 AS diferencia, ";

			$sql .= "					FF.prefijo,";
			$sql .= "					FF.factura_fiscal, ";
			$sql .= "					FF.saldo, ";
			$sql .= "					COALESCE(GL.valor_glosa,0) AS valor_glosa, ";
			$sql .= "					COALESCE(GL.valor_aceptado,0) AS valor_aceptado, ";
			$sql .= "					COALESCE(GL.valor_no_aceptado,0) AS valor_no_aceptado, ";
			$sql .= "					COALESCE(GL.valor_pendiente,0) AS valor_pendiente, ";
			$sql .= "					COALESCE(RC.valor_abonado_rc,0) AS valor_abonado_rc, "; 
			$sql .= "					COALESCE(NA.valor_abonado_na,0) AS valor_abonado_na, ";
			$sql .= "					FF.total_factura AS total ";
			$where .= "FROM 	fac_facturas FF ";
			$where .= "				LEFT JOIN ";
			$where .= "				(	SELECT 	SUM(valor_pendiente) AS valor_pendiente, ";
			$where .= "									SUM(valor_glosa) AS valor_glosa, ";
			$where .= "									SUM(valor_aceptado) AS valor_aceptado, ";
			$where .= "									SUM(valor_no_aceptado) AS valor_no_aceptado, ";
			$where .= "									prefijo, ";
			$where .= "									factura_fiscal ";
			$where .= "					FROM 		glosas ";
			$where .= "					WHERE 	sw_estado <> '0'";
			$where .= "					AND 		empresa_id = '".$empresa."' ";
			$where .= "					GROUP BY 5,6 ";
			$where .= "				) AS GL ";
			$where .= "				ON(	GL.prefijo = FF.prefijo AND ";
			$where .= "						GL.factura_fiscal = FF.factura_fiscal ";
			$where .= "					) ";
			$where .= "				LEFT JOIN ";
			$where .= "				( SELECT 	SUM(valor_abonado) AS valor_abonado_rc, ";
			$where .= "									prefijo_factura, ";
			$where .= "									factura_fiscal ";
			$where .= "					FROM 		rc_detalle_tesoreria_facturas ";
			$where .= "					WHERE 	empresa_id = '".$empresa."' ";
			$where .= "					GROUP BY 2,3 ) AS RC ";
			$where .= "				ON( RC.prefijo_factura = FF.prefijo AND ";
			$where .= "						RC.factura_fiscal = FF.factura_fiscal ) ";
			$where .= "				LEFT JOIN ";
			$where .= "				( SELECT 	SUM(valor_abonado) AS valor_abonado_na, ";
			$where .= "									prefijo_factura, ";
			$where .= "									factura_fiscal ";
			$where .= "					FROM 		notas_credito_ajuste_detalle_facturas ";
			$where .= "					WHERE 	empresa_id = '".$empresa."' ";
			$where .= "					GROUP BY 2,3 ) AS NA ";
			$where .= "				ON( NA.prefijo_factura = FF.prefijo AND ";
			$where .= "						NA.factura_fiscal = FF.factura_fiscal ) ";
			
			if($envio == '1')
				$where .= "WHERE  FF.fecha_vencimiento_factura IS NOT NULL ";
			else if($envio == '0')
				$where .= "WHERE  FF.fecha_vencimiento_factura IS NULL ";
			
			$where .= "AND		FF.plan_id = ".$plan_id." ";
			$where .= "AND		FF.sw_clase_factura = '1' ";
			$where .= "AND		FF.estado = '0' ";
			$where .= "AND		FF.saldo > 0 ";
			
			$sqlCont = "SELECT COUNT(*) $where ";
			$this->ProcesarSqlConteo($sqlCont);

			$sql .= $where;
			$sql .= "ORDER BY 1,2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;	
			while(!$rst->EOF)
			{
				$facturas[$i]  = $rst->GetRowAssoc($ToUpper = false);
			 	$dif = $facturas[$i]['diferencia'];
				
				if($dif == 0)
				{ 
					$nombre = "ESTE MES";
					$facturas[$i]['intervalo'] = "0";
					$facturas[$i]['direccion'] = "2";
				}
				else
				{
					$facturas[$i]['direccion'] = "0";
					if($dif < 0)
					{
					 	$a = $dif*(-1);
					 	$facturas[$i]['direccion'] = "1";
					}
						
					if($a == 1 )
					{
						$nombre = "A  30 DÍAS";
						$facturas[$i]['intervalo'] = "30";
					}
					else if($a == 2)
						{
							$nombre = "A  60 DÍAS";
							$facturas[$i]['intervalo'] = "60";
						}
						else if($a == 3)  
							{
								$nombre = "A  90 DÍAS";
								$facturas[$i]['intervalo'] = "90";
							}
							else if($a == 4)
								{ 
									$nombre = "A 120 DÍAS";
									$facturas[$i]['intervalo'] = "120";
								}
								else if($a == 5) 
									{
										$nombre = "A 150 DÍAS";
										$facturas[$i]['intervalo'] = "150";
									}
									else if($a == 6) 
										{
											$nombre = "A 180 DÍAS";
											$facturas[$i]['intervalo'] = "180";
										}
										else 
										{
											$nombre = " MAS DE 180";
											$facturas[$i]['intervalo'] = "190";
										}
				}
				$facturas[$i]['nombre'] = $nombre;
				
				$this->Facturas2[$nombre][$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();		
			return $facturas;
		}
		/********************************************************************************
		* Funcion que permite mostrar las facturas de un rango seleccionado para un 
		* cliente determinado
		* 
		* @return boolean 
		*********************************************************************************/
		function MostrarFacturasPlanRango()
		{
			$datos = $_REQUEST;
			
			$this->action['volver'] = ModuloGetURL('app','Cartera','user','FormaMostrarFacturasPlan');						
			
			$envio = $_SESSION['cartera']['envio'];
			$empresa = $_SESSION['cartera']['empresa_id'];
			$plan_id = $_SESSION['cartera']['planes']['plan'];
			
			$this->rqst['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			$this->rqst['rango'] = $datos['rango'];
			$this->rqst['diferencia'] = $datos['diferencia'];
			$this->rqst['plan_descripcion'] = $_SESSION['cartera']['planes']['descripcion'];
			
			$vector = $this->ConsultarFacturasPlanRangos($empresa,$plan_id,$datos['direccion'],$datos['intervalo'],$envio,$this->rqst);
			$this->Totales = $vector['totales'];
			$this->Facturas = $vector['facturas'];
		}
		/********************************************************************************
		* Funcion donde se consultan las factura que pertenecen a un rango determinado
		* para un plan X
		* 
		* @params string 	$empresa 	Hace referencia a la empresa dueña de la cartera
		* @params int 		$plan_id	Identificacion del plan al que se le averigua la 
		*														cartera
		* @params int			$direccion Indica si el rango escogido esta por vencer, es 
		*														 de este mes o esta vencido
		* @params string	$intervalo Indica cual periodo es el se necesita
		* @params string	$rango 		 Indica el rango para construir el vector
		* @params char    $envio		 Indica la cartera que se va a consultar, enviada o
		*														 la no enviada
		* @return array		Facturas y totales asociados a la cartera
		*********************************************************************************/
		function ConsultarFacturasPlanRangos($empresa,$plan_id,$direccion,$intervalo,$envio,$datos)
		{
			if($envio == '1')
				$sql .= "SELECT (FF.fecha_vencimiento_factura::date - NOW()::date)/30 AS intervalo , ";
			else if($envio == '0')
				$sql .= "SELECT (FF.fecha_registro::date - NOW()::date)/30 AS intervalo , ";
				
			$sql .= "				FF.prefijo,";
			$sql .= " 			FF.factura_fiscal, ";
			$sql .= "  			FF.saldo AS saldo,  ";
			$sql .= "				COALESCE(GL.valor_glosa,0) AS valor_glosa, ";
			$sql .= "				COALESCE(GL.valor_aceptado,0) AS valor_aceptado, ";
			$sql .= "				COALESCE(GL.valor_no_aceptado,0) AS valor_no_aceptado, ";
			$sql .= "  			COALESCE(GL.valor_pendiente,0) AS valor_pendiente, ";
			$sql .= "				FF.total_factura AS total ";
			$sql .= "FROM		fac_facturas FF LEFT JOIN  ";
			$sql .= "				(SELECT SUM(valor_pendiente) AS valor_pendiente,  ";
			$sql .= "      					SUM(valor_glosa) AS valor_glosa,  ";
			$sql .= "      					SUM(valor_aceptado) AS valor_aceptado,  ";
			$sql .= "      					SUM(valor_no_aceptado) AS valor_no_aceptado,";
			$sql .= "      					empresa_id,  ";
			$sql .= "      					prefijo,  ";
			$sql .= "      					factura_fiscal ";
			$sql .= "        FROM		glosas ";
			$sql .= "	       WHERE 	sw_estado <> '0' ";
			$sql .= "	       GROUP BY 5,6,7) AS GL  ";
			$sql .= "        ON(GL.empresa_id = FF.empresa_id AND ";
			$sql .= "      		 	GL.prefijo = FF.prefijo AND ";
			$sql .= "       	 	GL.factura_fiscal = FF.factura_fiscal) ";
			$sql .= "WHERE  FF.empresa_id = '".$empresa."' ";
			$sql .= "AND    FF.plan_id = ".$plan_id." ";
			$sql .= "AND    FF.estado = '0' ";
			$sql .= "AND    FF.saldo > 0 ";
			
			if($envio == '1')
				$sql .= "AND    FF.fecha_vencimiento_factura IS NOT NULL ";
			else if($envio == '0')
				$sql .= "AND    FF.fecha_vencimiento_factura IS NULL ";
				
			$filtro = "";
			if($datos['diferencia']*(-1) >= 7)
				$filtro = " <= -7 ";
			else if($datos['diferencia'] >= 7)
				$filtro = " >= 7 ";
				else
					$filtro = " = ".$datos['diferencia'];

			$sql .= "AND		((FF.fecha_registro::date - NOW()::date) / 30) ".$filtro." ";
			$sql .= "ORDER BY FF.prefijo,FF.factura_fiscal ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$i = 0;
			$totales = array();
			$facturas = array();
			$this->Totales = array();
			
			while(!$rst->EOF)
			{
				$factura = $rst->GetRowAssoc($ToUpper = false);
			
				$facturas[$datos['rango']][] = $rst->GetRowAssoc($ToUpper = false);
				$totales[0] += $factura['total'];
				$totales[1] += $factura['saldo'];
				$totales[2] += $factura['valor_glosa'];
				$totales[3] += $factura['valor_aceptado'];
				$totales[4] += $factura['valor_no_aceptado'];
				$totales[5] += $factura['valor_pendiente'];
				$rst->MoveNext();
			}
		
			$rst->Close();	
			
			$datos = array("facturas"=>$facturas,"totales"=>$totales);
			
			return $datos;
		}
		/********************************************************************************
		* Funcion donde se crean las variables para la forma creada en la funcion 
		* FormaMostrarConsultaTodo
		*********************************************************************************/
		function MostrarConsultaTodo()
		{
			$this->action1 = ModuloGetURL('app','Cartera','user','MostrarMenuPrincipalCartera');
			$this->action2 = ModuloGetURL('app','Cartera','user','FormaReportesCarteraEnviada');
			$this->action3 = ModuloGetURL('app','Cartera','user','FormaReportesCarteraNoEnviada');
			$this->action4 = ModuloGetURL('app','Cartera','user','FormaReportesCarteraCuentas');
		}
		/********************************************************************************
		* Funcion donde se crean las variables para la forma creada en la funcion 
		* FormaReportesCarteraEnviada
		*********************************************************************************/
		function ReportesCarteraEnviada()
		{
			$this->mst= false;
			$this->request = $_REQUEST;
			$this->Periodo = $_REQUEST['periodo'];
			$this->Cliente = $_REQUEST['nombre_tercero'];
			$this->Cliente2 = $_REQUEST['nombre_tercero1'];
			$this->Arreglo = array();
			if($this->Cliente2)
			{
				$datos = explode("ç",$this->Cliente2);
				$this->Arreglo['tipo_id'] = $datos[0];
				$this->Arreglo['tercero_id'] = $datos[1];
				$this->Arreglo['nombre'] = $datos[2];
				$this->Arreglo['empresa'] = $_SESSION['cartera']['empresa_id'];
			}
			if($this->request['fecha'])
			{
				$f = explode("/",$this->request['fecha']);
				if(!checkdate($f[1],$f[0],$f[2]))
					$this->frmError['MensajeError'] = "SE DEBE INGRESAR UNA FECHA VALIDA PARA EL REPORTE";
				else
					$this->mst= true;
			}
			$this->action1 = ModuloGetURL('app','Cartera','user','FormaMostrarConsultaTodo');
			$this->action2 = ModuloGetURL('app','Cartera','user','FormaReportesCarteraEnviada');
		}
		/********************************************************************************
		* Funcion donde se crean las variables para la forma creada en la funcion 
		* FormaReportesCarteraNoEnviada
		*********************************************************************************/
		function ReportesCarteraNoEnviada()
		{
			$this->Periodo = $_REQUEST['periodo'];
			$this->Cliente = $_REQUEST['nombre_tercero'];
			$this->action1 = ModuloGetURL('app','Cartera','user','FormaMostrarConsultaTodo');
			$this->action2 = ModuloGetURL('app','Cartera','user','FormaReportesCarteraNoEnviada');
		}
		/********************************************************************************
		* Funcion donde se crean las variables usadas en la funcion 
		* FormaReportesCarteraCuentas
		*********************************************************************************/
		function ReportesCarteraCuentas()
		{
			$this->EstadoC = $_REQUEST['estado'];
			$this->DepartamentoSel = $_REQUEST['departamento'];
			
			$nts = new app_Cartera_Notas();
			$this->Departamentos = $nts->ObtenerDepartamentos($_SESSION['cartera']['empresa_id']);
			$this->action1 = ModuloGetURL('app','Cartera','user','FormaMostrarConsultaTodo');
			$this->action2 = ModuloGetURL('app','Cartera','user','FormaReportesCarteraCuentas');
		}
		/********************************************************************************
		*
		* @return boolean 
		*********************************************************************************/
		function MostrarEnviosCliente()
		{
			$this->request = $_REQUEST;
			$this->datos = array();
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			list($this->datos['tipo_id_tercero'],$this->datos['tercero_id']) = explode(" ",$this->request['datos_cliente']['cliente_id']);			
		
			$terceros = $_SESSION['cartera']['Enviados'];
			
			foreach($terceros as $key => $cliente)
			{
				if($cliente['tipo_id_tercero'] == $this->datos['tipo_id_tercero'] && $cliente['tercero_id'] == $this->datos['tercero_id'] )
				{
					$this->datos['nombre_tercero'] = $cliente['nombre_tercero'];
					break;
				}
			}
			$rqst = array("periodo"=>$this->request['datos_cliente']['periodo'],"nombre_tercero"=>$this->request['datos_cliente']['nombre_tercero']);
			$this->action['cartera'] = ModuloGetURL('app','Cartera','user','FormaMostrarFacturasEnvio');
			$this->action['volver'] = ModuloGetURL('app','Cartera','user','MostrarCarteraClientes',$rqst);
		}
		/********************************************************************************
		* Funcion donde se muestra la cartera de un cliente seleccionado
		*
		* @return boolean
		*********************************************************************************/
		function MostrarFacturasEnvio()
		{
			$terceros = $_SESSION['cartera']['Enviados'];
			$this->request = $_REQUEST;
			
			$this->datos = $this->request['datos_cliente'];
			$this->datos['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			list($this->datos['tipo_id_tercero'],$this->datos['tercero_id']) = explode(" ",$this->datos['cliente_id']);			

			foreach($terceros as $key => $cliente)
			{
				if($cliente['tipo_id_tercero'] == $this->datos['tipo_id_tercero'] && $cliente['tercero_id'] == $this->datos['tercero_id'])
				{
					$this->datos['nombre_tercero'] = $cliente['nombre_tercero'];
					break;
				}
			}
			
			$this->action['volver'] = ModuloGetURL('app','Cartera','user','FormaMostrarEnviosCliente',array("datos_cliente"=>$this->request['datos_cliente']));
		}
		/********************************************************************************
		* Funcion donde se muestra la cartera de un cliente seleccionado
		*
		* @return boolean
		*********************************************************************************/
		function MostrarTodasFacturas()
		{
			IncludeClass('CarteraDetalle','','app','Cartera');
			$cd = new CarteraDetalle();
			
			$this->request = $_REQUEST;
			$this->request['empresa_id'] = $_SESSION['cartera']['empresa_id'];
			
			$this->datos = array();
			$this->arreglo = array();
			if($this->request['facturacion'])
			{
				if($this->request['meses'])
				{
					$fechaf = date("Y-m-d", mktime(0, 0, 0,(intval($this->request['meses'])+1), 0,$this->request['anyo']));
					$fechai = date("Y-m-d", mktime(0, 0, 0,(intval($this->request['meses'])), 1,$this->request['anyo']));
				}
				
				if($this->request['facturacion'] == '3')
				{
					$this->facturas = $cd->ObtenerFacturasExternas($this->request['empresa_id'],$fechai,$fechaf,$this->request['facturacion'],$this->request['cantidad'],$this->request['offset'],$this->request);
					SessionDelVar("TodosEnvios");
				}
				else
				{
					$this->facturas = $cd->ObtenerFacturas($this->request['empresa_id'],$fechai,$fechaf,$this->request['facturacion'],$this->request['cantidad'],$this->request['offset'],$this->request);
					if(!SessionIsSetVar("TodosEnvios") || !$this->request['cantidad'])
					{
						$this->envios = $cd->ObtenerFacturasEnvios($this->request['empresa_id'],$fechai,$fechaf,$this->request);
						SessionSetVar("TodosEnvios",$this->envios);
					}
					$this->envios = SessionGetVar("TodosEnvios");
				}
				
				$this->arreglo['anyo'] = $this->request['anyo'];
				$this->arreglo['orden'] = $this->request['orden'];
				$this->arreglo['meses'] = $this->request['meses'];
				$this->arreglo['envio'] = $this->request['envio'];
				$this->arreglo['prefijo'] = $this->request['prefijo'];
				$this->arreglo['factura_f'] = $this->request['factura_f'];
				$this->arreglo['facturacion'] = $this->request['facturacion'];
				$this->arreglo['cantidad'] = $cd->conteo;
				
				$this->datos['pagina_actual'] = $cd->paginaActual;
				$this->datos['cantidad'] = $cd->conteo;
			}
			$this->prefijos = $cd->ObtenerPrefijos($this->request['empresa_id']);
			$this->action['buscar'] = ModuloGetURL('app','Cartera','user','FormaMostrarTodasFacturas');
			$this->action['paginador'] = ModuloGetURL('app','Cartera','user','FormaMostrarTodasFacturas',$this->arreglo);
			$this->action['volver'] = ModuloGetURL('app','Cartera','user','SubMenuMovimientos');
		}
		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null,$cont = null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
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
			if($cont === null)
			{
				if(!$result = $this->ConexionBaseDatos($consulta))
					return false;

				if(!$result->EOF)
				{
					$this->conteo = $result->fields[0];
					$result->MoveNext();
				}
				$result->Close();
			}
			else
				$this->conteo = $cont;
			return true;
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
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