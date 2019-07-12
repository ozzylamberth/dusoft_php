<?php
	/**************************************************************************************  
	* $Id: app_FacturacionNotaCD_user.php,v 1.2 2010/03/16 13:00:58 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	***************************************************************************************/
	class app_FacturacionNotaCD_user extends classModulo
	{
		/********************************************************************************** 
		* Constructor 
		* 
		* @access private	  
		***********************************************************************************/
		function app_FacturacionNotaCD_user()
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
			unset($_SESSION['SqlBuscarFacturas']);
			unset($_SESSION['SqlContarFacturas']);
			$this->MostrarMenuEmpresas();
			return true;
		}
		/*************************************************************************************
		* Funcion que permite mostrar las facturas que han sido glosadas y se les puede 
		* generar una nota credito o debito 
		* 
		* @return boolean 
		**************************************************************************************/
		function MostrarFacturasContabilizar()
		{
			$this->Numero = $_REQUEST['numero'];
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->TerceroId = $_REQUEST['tercero_id'];
			$this->EstadoGlosa = $_REQUEST['estado_glosa'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			$this->FacturaFiscal = $_REQUEST['factura_fiscal'];
			$this->TipoDocumento = $_REQUEST['tipo_documento'];
			$this->TipoIdTercero = $_REQUEST['tipo_id_tercero'];
			$this->PrefijoFactura = $_REQUEST['prefijo_factura'];
			
			$request = array("numero"=> $_REQUEST['numero'],"fecha_fin"=>$_REQUEST['fecha_fin'],"estado_glosa"=>$_REQUEST['estado_glosa'],
							 "tercero_id"=>$_REQUEST['tercero_id'],"fecha_inicio"=>$_REQUEST['fecha_inicio'],"tipo_documento"=>$_REQUEST['tipo_documento'],
							 "tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],"factura_fiscal"=>$_REQUEST['factura_fiscal'],"prefijo_factura"=> $_REQUEST['prefijo_factura']);
			
			$this->actionBuscadorF = ModuloGetURL('app','FacturacionNotaCD','user','ObtenerSqlFacturas');
			$this->actionBuscador  = ModuloGetURL('app','FacturacionNotaCD','user','ObtenerSqlBuscarFacturas');
			$this->actionPaginador = ModuloGetURL('app','FacturacionNotaCD','user','MostrarFacturasContabilizar',$request);

		
			if(!$_SESSION['SqlBuscarFacturas'] && !$_SESSION['SqlContarFacturas'] 
			   && !empty($_SESSION['NotasCD']['empresa']))
			{
				$this->PrimeraVez = 1;
				$this->ObtenerSqlBuscarFacturas();
			}
			$this->action = ModuloGetURL('app','FacturacionNotaCD','user','MostrarMenuPrincipalGlosas');
			if($_REQUEST['offset']) $this->Facturas = $this->ObtenerDatosFacturas();	
			$this->FormaMostrarFacturasContabilizar();
			return true;
		}
		/**********************************************************************************
		* Funcion que permite mostrar en pantalla la informacion de la factura 
		* 
		* @return boolean 
		***********************************************************************************/
		function MostrarInformacionFactura()
		{
			if(!$this->ObtenerInformacionGlosaFactura())
				return false;
		
			$this->actionV = ModuloGetURL('app','FacturacionNotaCD','user','MostrarFacturasContabilizar',
		 								   array("offset"=>$_REQUEST['pagina']));
		 	
		 	$this->action = ModuloGetURL('app','FacturacionNotaCD','user','GenerarNotaCreditoDebito',
		 								  array("pagina"=>$_REQUEST['pagina'],"glosa_id"=>$this->GlosaId,"glosa_parcial"=>$this->GlosaParcial,
		 								  		"factura_fiscal"=>$_REQUEST['factura_fiscal'],"prefijo_factura"=> $_REQUEST['prefijo_factura'],
		 								  		"v_glosa"=>$this->GlosaValorGlosado,"v_acepta"=>$this->GlosaValorAceptado,"v_no_acepta"=>$this->GlosaValorNoAceptado));

			$this->FormaMostrarInformacionFactura();
			return true;
		}
		/**********************************************************************************
		* Funcion que permite desplegar la informacion de las facturas a las que se les ha 
		* generado una nota credito
		* 
		* @return boolean 
		***********************************************************************************/
		function MostrarNotasCredito()
		{
			if(!$_SESSION['SqlBuscarFacturas'] && !$_SESSION['SqlContarFacturas'] 
			   && !empty($_SESSION['NotasCD']['empresa']))
			{
				$this->PrimeraVez = 1;
				$this->ObtenerSqlBuscarNotasCredito();
			}
			
			$this->TerceroId = $_REQUEST['tercero_id'];
			$this->GlosaFechaF = $_REQUEST['fecha_fin'];
			$this->GlosaNumero = $_REQUEST['numero_glosa'];
			$this->GlosaFechaI = $_REQUEST['fecha_inicio'];
			$this->TerceroTipoId = $_REQUEST['tipo_id_tercero'];
			$this->NombreTercero = $_REQUEST['nombreTercero'];
			
			$this->action  = ModuloGetURL('app','FacturacionNotaCD','user','MostrarMenuPrincipalGlosas');
			$this->actionB = ModuloGetURL('app','FacturacionNotaCD','user','ObtenerSqlBuscarNotasCredito');
			$this->actionP = ModuloGetURL('app','FacturacionNotaCD','user','MostrarNotasCredito',
																		 array("tercero_id"=>$this->TerceroId,"fecha_fin"=>$this->GlosaFechaF,"nombreTercero"=>$this->NombreTercero,
																		   		 "fecha_inicio"=>$this->GlosaFechaI,"tipo_id_tercero"=>$this->TerceroTipoId,"numero_glosa"=>$this->GlosaNumero));
			$this->FormaMostrarNotasCredito();
			return true;
		}
		/**********************************************************************************
		* Funcion que permite desplegar la informacion de una nota credito, para la glosa
		* seleccionada 
		* 
		* @return boolean 
		***********************************************************************************/
		function MostrarInformacionNotaCredito()
		{
			$nota = $_REQUEST['nota_numero'];
			$glosa = $_REQUEST['glosa_id'];
			$codigo = $_REQUEST['codigo'];
			
			$this->NotaCreditoNumero = $_REQUEST['nota_prefijo']." ".$_REQUEST['nota_numero'];
			
			$this->ObtenerInformacionNotaCredito($glosa,$codigo,$nota);
			$this->action = ModuloGetURL('app','FacturacionNotaCD','user','MostrarNotasCredito',
										  						  array("offset"=>$_REQUEST['pagina']));
			
			$this->FormaMostrarInformacionNotaCredito();
			return true;
		} 
		/**********************************************************************************
		* Funcion que permite generar la nota credito y debito de la glosa 
		* 
		* @return boolean 
		***********************************************************************************/
		function GenerarNotaCreditoDebito()
		{
			$empresa = $_SESSION['NotasCD']['empresa'];
			$this->GlosaId = $_REQUEST['glosa_id'];
			$this->Imprimir = 1;
			
			$documento = ModuloGetVar('app','FacturacionNotaCD','documento_'.$empresa);
			
			$sql1 = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
			
			list($dbconn)=GetDBConn();
			
			$dbconn->BeginTrans();
			//$dbconn->debug = true;
			$result = $dbconn->Execute($sql1);
			if ($dbconn->ErrorNo() != 0) {
				die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
				$dbconn->RollbackTrans();
				return false;
			}
			else
			{
				$sql4  = "SELECT prefijo,numeracion FROM documentos ";
				$sql4 .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."' ";
			
				$numeracion = $this->ObtenerNumeracion($sql4,&$dbconn);
						
				if($_REQUEST['glosa_parcial'] == '1')
				{
					$this->CodigoNC = "NT";
					$sql  = "INSERT INTO notas_credito_glosas ";
					$sql .= "		(documento_id,";
					$sql .= "		 empresa_id, ";
					$sql .= "		 prefijo, ";
					$sql .= "		 numero, ";
					$sql .= "		 glosa_id, ";
					$sql .= "		 usuario_id, ";
					$sql .= "		 fecha_registro,";
					$sql .= "		 valor_glosa,";
					$sql .= "		 valor_aceptado,";
					$sql .= "		 valor_no_aceptado ) ";
					$sql .= "VALUES ( ".$documento.", ";
					$sql .= "		 '".$empresa."', ";
					$sql .= "		 '".$numeracion[0]."',";
					$sql .= "		  ".$numeracion[1].",";
					$sql .= "		  ".$this->GlosaId.", ";
					$sql .= "		  ".UserGetUID().", ";
					$sql .= "		    NOW(),";
					$sql .= "		  ".$_REQUEST['v_glosa'].", ";
					$sql .= "		  ".$_REQUEST['v_acepta'].", ";
					$sql .= "		  ".$_REQUEST['v_no_acepta']."); ";
					$sql .= "UPDATE glosas ";
					$sql .= "SET  sw_estado = '3', ";
					$sql .= "	  fecha_cierre = NOW() ";
					$sql .= "WHERE glosa_id = ".$this->GlosaId."; ";
					$sql .= "UPDATE glosas_detalle_cuentas ";
					$sql .= "SET sw_estado = '3' ";
					$sql .= "WHERE glosa_id = ".$this->GlosaId." ";
					$sql .= "AND sw_estado = '2'; ";
				}
				else
				{
					$this->CodigoNC = "NI";
					$sql2  = "SELECT glosa_detalle_cargo_id, ";
					$sql2 .= "		 valor_glosa, ";
					$sql2 .= "		 valor_aceptado,";
					$sql2 .= "		 valor_no_aceptado ";
					$sql2 .= "FROM 	 glosas_detalle_cargos ";
					$sql2 .= "WHERE	 glosa_id = ".$this->GlosaId." ";
					$sql2 .= "AND	 sw_estado = '2' ";
					$retorno = $this->EjecutarSentencias($sql2);
				
					for($i=0; $i<sizeof($retorno); $i++)
					{
						$cargos = explode("*",$retorno[$i]);	
						$sql .= "INSERT INTO notas_credito_glosas_detalle_cargos ";
						$sql .= "		(documento_id,";
						$sql .= "		 empresa_id, ";
						$sql .= "		 prefijo, ";
						$sql .= "		 numero, ";
						$sql .= "		 glosa_id, ";
						$sql .= "		 glosa_detalle_cargo_id, ";
						$sql .= "		 usuario_id, ";
						$sql .= "		 fecha_registro, ";
						$sql .= "		 valor_glosa,";
						$sql .= "		 valor_aceptado,";
						$sql .= "		 valor_no_aceptado ) ";
						$sql .= "VALUES ( ".$documento.", ";
						$sql .= "		 '".$empresa."', ";
						$sql .= "		 '".$numeracion[0]."',";
						$sql .= "		  ".$numeracion[1].",";
						$sql .= "		  ".$this->GlosaId.", ";
						$sql .= "		  ".$cargos[0].", ";
						$sql .= "		  ".UserGetUID().", ";
						$sql .= "		    NOW(), ";
						$sql .= "		  ".$cargos[1].", ";
						$sql .= "		  ".$cargos[2].", ";
						$sql .= "		  ".$cargos[3]."); ";
					}
				
					$sql3  = "SELECT 	glosa_detalle_inventario_id, ";
					$sql3 .= "		 		valor_glosa, ";
					$sql3 .= "		 		valor_aceptado,";
					$sql3 .= "		 		valor_no_aceptado ";
					$sql3 .= "FROM 	 	glosas_detalle_inventarios ";
					$sql3 .= "WHERE	 	glosa_id = ".$this->GlosaId." ";
					$sql3 .= "AND	 		sw_estado = '2' ";
					$retorno = $this->EjecutarSentencias($sql3);
					
					for($i=0; $i<sizeof($retorno); $i++)
					{
						$insumos = explode("*",$retorno[$i]);
						
						$sql .= "INSERT INTO notas_credito_glosas_detalle_inventarios ";
						$sql .= "		(documento_id,";
						$sql .= "		 empresa_id, ";
						$sql .= "		 prefijo, ";
						$sql .= "		 numero, ";
						$sql .= "		 glosa_id, ";
						$sql .= "		 glosa_detalle_inventario_id, ";
						$sql .= "		 usuario_id, ";
						$sql .= "		 fecha_registro, ";
						$sql .= "		 valor_glosa,";
						$sql .= "		 valor_aceptado,";
						$sql .= "		 valor_no_aceptado ) ";
						$sql .= "VALUES ( ".$documento.",";
						$sql .= "		 '".$empresa."', ";
						$sql .= "		 '".$numeracion[0]."',";
						$sql .= "		  ".$numeracion[1].",";
						$sql .= "		  ".$this->GlosaId.", ";
						$sql .= "		  ".$insumos[0].", ";
						$sql .= "		  ".UserGetUID().", ";
						$sql .= "		    NOW(), ";
						$sql .= "		  ".$insumos[1].", ";
						$sql .= "		  ".$insumos[2].", ";
						$sql .= "		  ".$insumos[3]."); ";

					}
					
					$sql .= "UPDATE glosas ";
					$sql .= "SET  sw_estado = '1', ";
					$sql .= "	  fecha_cierre = NOW() ";
					$sql .= "WHERE glosa_id = ".$this->GlosaId."; ";
					$sql .= "UPDATE glosas_detalle_cuentas ";
					$sql .= "SET sw_estado = '1' ";
					$sql .= "WHERE glosa_id = ".$this->GlosaId." ";
					$sql .= "AND sw_estado = '2'; ";

					$sql1  = "INSERT INTO notas_credito_glosas ";
					$sql1 .= "		(documento_id,";
					$sql1 .= "		 empresa_id, ";
					$sql1 .= "		 prefijo, ";
					$sql1 .= "		 numero, ";
					$sql1 .= "		 glosa_id, ";
					$sql1 .= "		 usuario_id, ";
					$sql1 .= "		 fecha_registro,";
					$sql1 .= "		 valor_glosa,";
					$sql1 .= "		 valor_aceptado,";
					$sql1 .= "		 valor_no_aceptado ) ";
					$sql1 .= "VALUES ( ".$documento.", ";
					$sql1 .= "		 '".$empresa."', ";
					$sql1 .= "		 '".$numeracion[0]."',";
					$sql1 .= "		  ".$numeracion[1].",";
					$sql1 .= "		  ".$this->GlosaId.", ";
					$sql1 .= "		  ".UserGetUID().", ";
					$sql1 .= "		    NOW(),";
					$sql1 .= "		  ".$_REQUEST['v_glosa'].", ";
					$sql1 .= "		  ".$_REQUEST['v_acepta'].", ";
					$sql1 .= "		  ".$_REQUEST['v_no_acepta']."); ";
					
					$sql = $sql1 .$sql;
				}
			
				$sql .= "UPDATE documentos ";
				$sql .= "SET numeracion = numeracion + 1 ";
				$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$empresa."'; ";
				if($_SESSION['NotasCD']['sistema'] == "SIIS")
				{
					$sql .= "UPDATE glosas_detalle_cargos ";
					$sql .= "SET sw_estado = '3' ";
					$sql .= "WHERE glosa_id= ".$this->GlosaId." ";
					$sql .= "AND sw_estado = '2'; ";
					$sql .= "UPDATE glosas_detalle_inventarios ";
					$sql .= "SET sw_estado = '3' ";
					$sql .= "WHERE glosa_id = ".$this->GlosaId." ";
					$sql .= "AND sw_estado = '2'; ";
				}
				
				$rst = $dbconn->Execute($sql);
				
				if (!$rst) 
				{
					$this->mensajeDeError = "$sql";
					$this->frmError['MensajeError'] = "ERROR DB : TRANSACCION 1 " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
			
			$info = "<br>LA NOTA CREDITO HA SIDO CREADA<br>";
		 	$this->action = ModuloGetURL('app','FacturacionNotaCD','user','MostrarFacturasContabilizar',
		 								  array("offset"=>$_REQUEST['pagina']));
		 	$this->NotaCreditoNumero = $numeracion[0]." ".$numeracion[1];
			$this->FormaInformacion($info);
			
			return true;
			
		}
		/********************************************************************************** 
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		***********************************************************************************/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero,descripcion FROM tipo_id_terceros";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			$i = 0;
			while (!$rst->EOF)
			{
				$documentos[$i] = $rst->fields[0]."/".$rst->fields[1];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			
			return $documentos;
		}
		/********************************************************************************** 
		* Funcion donde se procesan los sql, uno que cuenta el numero de facturas segun las 
		* condiciones de busqueda que se den y otro que trae los datos de las mismas 
		* 
		* @return array datos de las facturas 
		************************************************************************************/ 
		function ObtenerDatosFacturas()
		{
			//echo $_SESSION['SqlBuscarFacturas'];
			if(!$rst = $this->ConexionBaseDatos($_SESSION['SqlBuscarFacturas'])) 
				return false;
			
			$cont = 0;
			if(!$rst->EOF) 
				$cont = $rst->RecordCount();


				$this->ProcesarSqlConteo($_SESSION['SqlBuscarFacturas'],null,$cont);
	
			$sql = $_SESSION['SqlBuscarFacturas'];	
			$sql .= "ORDER BY 1,2 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $facturas;
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
			$empresa_id = $_SESSION['NotasCD']['empresa'];
			
			$this->Numero = $_REQUEST['numero'];
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->TerceroId = $_REQUEST['tercero_id'];
			$this->EstadoGlosa = $_REQUEST['estado_glosa'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			$this->FacturaFiscal = $_REQUEST['factura_fiscal'];
			$this->NombreTercero = $_REQUEST['nombreTercero'];
			$this->TipoIdTercero = $_REQUEST['tipo_id_tercero'];
			
			$sql .= "SELECT 	F.prefijo, ";
			$sql .= "					F.factura_fiscal, ";
			$sql .= "					F.tipo_id_tercero,";
			$sql .= "					F.tercero_id, ";
			$sql .= "					F.total_factura, "; 
			$sql .= "					TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "					E.envio_id, ";
			$sql .= "					TO_CHAR(E.fecha_radicacion,'DD/MM/YYYY') AS fecha_envio, ";
			$sql .= "					F.sistema, ";
			$sql .= "					TE.nombre_tercero ";
			$where .= "FROM 	view_fac_facturas F";
			
			$where .= "				LEFT JOIN ";
			$where .= "				(	SELECT 	E.envio_id, ";
			$where .= "									E.fecha_radicacion, ";
			$where .= "									E.sw_estado, ";
			$where .= "									ED.prefijo, ";
			$where .= "									ED.factura_fiscal ";
			$where .= "					FROM		envios_detalle ED, ";
			$where .= "									envios E ";
			$where .= "					WHERE		ED.envio_id = E.envio_id ";
			$where .= "					AND			E.sw_estado != '2' ";
			$where .= "					AND			ED.empresa_id = '".$empresa_id."' ";
			$where .= "				)AS E ";
			$where .= "				ON( E.prefijo = F.prefijo AND ";
			$where .= "						E.factura_fiscal = F.factura_fiscal), ";
			$where .= "				glosas GL, ";
			$where .= "				terceros TE ";
			$where .= "WHERE 	COALESCE(E.sw_estado,'0') IN ('0','1','3') ";
			$where .= "AND		F.empresa_id = '".$empresa_id."' ";
			$where .= "AND		GL.prefijo = F.prefijo ";
			$where .= "AND		GL.factura_fiscal = F.factura_fiscal ";	
			$where .= "AND		GL.empresa_id = F.empresa_id ";
			$where .= "AND 		GL.sw_estado = '2' ";
			$where .= "AND		TE.tercero_id = F.tercero_id ";
			$where .= "AND		TE.tipo_id_tercero = F.tipo_id_tercero ";
			if($this->TipoIdTercero != '0' && $this->TipoIdTercero)
			{
				$where .="AND F.tipo_id_tercero = '".$this->TipoIdTercero."' ";
			}
			
			if($this->TerceroId != "" && $this->TerceroId)
			{
				$where .= "AND F.tercero_id = '".$this->TerceroId."' ";
			}
			
			if($this->Numero !="")
			{
				$where .= "AND F.factura_fiscal = '".$this->Numero."' ";
			}
						
			if($this->FechaInicio != "")
			{
				$arreglo = explode("/",$this->FechaInicio);
				$where .= "AND F.fecha_registro >= '".$arreglo[2]."-".$arreglo[1]."-".$arreglo[0]." 00:00:00' ";
			}
			if($this->FechaFin != "")
			{
				$arreglo = explode("/",$this->FechaFin);
				$where .= "AND F.fecha_registro <= '".$arreglo[2]."-".$arreglo[1]."-".$arreglo[0]." 00:00:00' ";
			}
			
			if($this->NombreTercero)
				$where .= "AND TE.nombre_tercero ILIKE '%".$this->NombreTercero."%' ";
			
			$_SESSION['SqlBuscarFacturas'] = $sql.$where;
			$_SESSION['SqlContarFacturas'] = "SELECT COUNT(*) ".$where;
			if($this->PrimeraVez != 1)
			{
				$this->Facturas = $this->ObtenerDatosFacturas();
				$this->MostrarFacturasContabilizar();
			}
						
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se obtiene el sql que permite buscar las facturas 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerSqlFacturas()
		{					
			$this->FacturaFiscal = $_REQUEST['factura_fiscal'];
			$this->PrefijoFactura = $_REQUEST['prefijo_factura'];
			$empresa_id = $_SESSION['NotasCD']['empresa'];
						
			$sql .= "SELECT 	F.prefijo, ";
			$sql .= "					F.factura_fiscal, ";
			$sql .= "					F.tipo_id_tercero,";
			$sql .= "					F.tercero_id, ";
			$sql .= "					F.total_factura, "; 
			$sql .= "					TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
			$sql .= "					E.envio_id, ";
			$sql .= "					TO_CHAR(E.fecha_radicacion,'DD/MM/YYYY') AS fecha_envio, ";
			$sql .= "					F.sistema, ";
			$sql .= "					TE.nombre_tercero ";
			$where .= "FROM 	view_fac_facturas F ";
			$where .= "				LEFT JOIN ";
			$where .= "				(	SELECT 	ED.prefijo,  ";
			$where .= "									ED.factura_fiscal,  ";
			$where .= "									ED.empresa_id,  ";
			$where .= "									ED.envio_id, ";
			$where .= "									EN.sw_estado, ";
			$where .= "									EN.fecha_radicacion ";
			$where .= "					FROM 		envios_detalle ED,  ";
			$where .= "									envios EN  ";
			$where .= "					WHERE 	ED.envio_id = EN.envio_id  ";
			$where .= "					AND 		EN.sw_estado <> '2'  ";
			$where .= "					AND 		ED.empresa_id = '".$empresa_id."' ";
			$where .= "				) AS E ";
			$where .= "				ON( E.prefijo = F.prefijo AND ";
			$where .= "						E.factura_fiscal = F.factura_fiscal AND ";
			$where .= "						E.empresa_id = F.empresa_id ) ,";
			$where .= "				glosas GL, ";
			$where .= "				terceros TE ";
			$where .= "WHERE 	COALESCE(E.sw_estado,'0') IN ('0','1','2','3') ";
			$where .= "AND		F.empresa_id = '".$empresa_id."' ";
			$where .= "AND		GL.prefijo = F.prefijo ";
			$where .= "AND		GL.factura_fiscal = F.factura_fiscal ";	
			$where .= "AND		GL.empresa_id = F.empresa_id ";
			$where .= "AND 		GL.sw_estado = '2' ";
			$where .= "AND		TE.tercero_id = F.tercero_id ";
			$where .= "AND		TE.tipo_id_tercero = F.tipo_id_tercero ";
			$where .= "AND 		F.prefijo = '".$this->PrefijoFactura."' ";
			
			if($this->FacturaFiscal != "")
			{
				$where .= "AND 		F.factura_fiscal = '".$this->FacturaFiscal."' ";
			}
			
			$_SESSION['SqlBuscarFacturas'] = $sql.$where;
			$_SESSION['SqlContarFacturas'] = "SELECT COUNT(*) ".$where;
			$this->Facturas = $this->ObtenerDatosFacturas();		
			$this->MostrarFacturasContabilizar();
			return true;
		}
		
		/***************************************************************************************
		* Funcion que permite obtener la informacion de la glosa de una factura 
		* 
		* @return boolean 
		****************************************************************************************/
		function ObtenerInformacionGlosaFactura()
		{
			$this->FacturaNumero = explode(" ",$_REQUEST['factura_numero']);
			
			$sql  = "SELECT M.motivo_glosa_descripcion,";
			$sql .= "				TC.descripcion,";
			$sql .= "				G.glosa_id, ";
			$sql .= "				G.observacion,";
			$sql .= "				G.documento_interno_cliente_id,";
			$sql .= "				G.valor_glosa,";
			$sql .= "				G.valor_aceptado,";
			$sql .= "				G.sw_glosa_parcial, ";
			$sql .= "				G.valor_no_aceptado, ";
			$sql .= "				COALESCE(G.auditor_id,0) AS auditor,";
			$sql .= "				G.sw_glosa_total_factura,";
			$sql .= "				TO_CHAR(G.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa,";
			$sql .= "				U.nombre ";
			$sql .= "FROM 	system_usuarios U,";
			$sql .= "	  		glosas G LEFT JOIN glosas_motivos M";
			$sql .= "				ON(G.motivo_glosa_id = M.motivo_glosa_id ) ";
			$sql .= "				LEFT JOIN glosas_tipos_clasificacion TC ";
			$sql .= "				ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "WHERE 	G.empresa_id = '".$_SESSION['NotasCD']['empresa']."' ";
			$sql .= "AND 		G.prefijo = '".$this->FacturaNumero[0]."' ";
			$sql .= "AND 		G.factura_fiscal = ".$this->FacturaNumero[1]." ";
			$sql .= "AND 		G.usuario_id = U.usuario_id ";
			$sql .= "AND 		G.sw_estado = '2' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$glosa = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			
			$this->GlosaId = $glosa['glosa_id'];
			$this->GlosaUsuario = $glosa['nombre'];
			$this->GlosaParcial = $glosa['sw_glosa_parcial'];
			$this->GlosaValorGlosado = $glosa['valor_glosa'];
			$this->GlosaValorAceptado = $glosa['valor_aceptado'];
			$this->GlosaFechaRegistro = $glosa['fecha_registro'];
			$this->GlosaClasificacion = $glosa['descripcion'];
			$this->GlosaSwGlosaFactura = $glosa['sw_glosa_total_factura'];
			$this->GlosaValorNoAceptado = $glosa['valor_no_aceptado'];
			$this->GlosaFechaGlosamiento = $glosa['fecha_glosa'];
			$this->GlosaDocumentoCliente = $glosa['documento_interno_cliente_id'];
			$this->GlosaMotivoGlosamiento = $glosa['motivo_glosa_descripcion'];
			$this->GlosaObservacionGlosamiento = $glosa['observacion'];

			if($glosa['auditor'] != 0) $this->AuditorNombre = $this->ObtenerUsuarioNombre($glosa['auditor']);
			$this->ObtenerInformacionFactura();
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se consulta la informacion de la factura 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerInformacionFactura()
		{
			$empresa_id = $_SESSION['NotasCD']['empresa'];
			
			(empty($_REQUEST['sistema']))? $this->Sistema = $_SESSION['NotaCD']['sistema']: $this->Sistema = $_REQUEST['sistema'];
			
			$_SESSION['NotaCD']['sistema'] = $this->Sistema;
			
			
			
			switch($this->Sistema)
			{
				case "EXT":
					$sql  = "SELECT F.tipo_id_tercero,";
					$sql .= "				F.tercero_id,";
					$sql .= "				F.saldo, ";
					$sql .= "				F.total_factura,";
					$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
					$sql .= "				T.nombre_tercero ";
					$sql .= "FROM 	facturas_externas F, terceros T ";
					$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
					$sql .= "AND 		F.prefijo = '".$this->FacturaNumero[0]."' ";
					$sql .= "AND 		F.factura_fiscal = ".$this->FacturaNumero[1]." ";
					$sql .= "AND 		F.tercero_id = T.tercero_id ";
					$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";
				break;
				case "SIIS":
					$sql  = "SELECT F.tipo_id_tercero,";
					$sql .= "				F.tercero_id,";
					$sql .= "				F.total_factura,";
					$sql .= "				TO_CHAR(F.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
					$sql .= "				P.num_contrato,"; 
					$sql .= "				P.plan_descripcion,";
					$sql .= "				P.plan_id, ";
					$sql .= "				T.nombre_tercero ";
					$sql .= "FROM 	fac_facturas F,";
					$sql .= "	  		terceros T, ";
					$sql .= "	  		planes P ";
					$sql .= "WHERE 	F.empresa_id = '".$empresa_id."' "; 
					$sql .= "AND 		F.prefijo = '".$this->FacturaNumero[0]."' ";
					$sql .= "AND 		F.factura_fiscal = ".$this->FacturaNumero[1]." ";
					$sql .= "AND 		F.tercero_id = T.tercero_id ";
					$sql .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero ";
					$sql .= "AND 		F.sw_clase_factura = '1' ";
					$sql .= "AND 		F.plan_id = P.plan_id ";
					$sql .= "AND 		F.empresa_id = P.empresa_id ";
				break;
			}			
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$factura = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$this->EnvioNumero = $_REQUEST['envio_numero'];
			$this->FacturaNumero = $_REQUEST['factura_numero'];
			$this->TerceroNombre = $factura['nombre_tercero'];
			$this->TerceroTipoDoc = $factura['tipo_id_tercero'];
			$this->PlanDescripcion = $factura['plan_descripcion'];
			$this->TerceroDocumento = $factura['tercero_id'];
			$this->PlanNumeroContrato = $factura['num_contrato'];
			$this->FacturaFechaRegistro = $factura['fecha_registro'];
			
			if($this->FechaGlosamiento == "")	$this->FechaGlosamiento = date("d/m/Y");
			
			return true;
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
		function ProcesarSqlConteo($consulta,$limite=null,$total=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = UserGetVar(UserGetUID(),'LimitRowsBrowser');
				if(!$this->limit)
					$this->limit = 20;
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
			
			if(!$total)
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
			{
				$this->conteo = $total;
			}
			return true;
		}
		/********************************************************************************** 
		* Retorna las empresas a las cuales se les ha dado permiso de usar este modulo 
		* 
		* @access public
		***********************************************************************************/
		function BuscarEmpresasUsuario()
		{
			$sql  = "SELECT E.empresa_id AS empresa, E.razon_social AS razon_social ";
			$sql .= "FROM	userpermisos_glosas_contabilizacion G,empresas E ";
			$sql .= "WHERE	G.usuario_id = ".UserGetUID()." ";
			$sql .= "AND	G.empresa_id = E.empresa_id";

			if(!$resultado = $this->ConexionBaseDatos($sql))
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
		* Funcion en donde se obtienen los prefijos que maneja la empresa 
		* 
		* @return array datos de la tabla documentos
		************************************************************************************/
		function ObtenerPrefijos()
		{	
			$sql  = "SELECT DISTINCT GL.prefijo ";			
			$sql .= "FROM 	glosas GL ";
			$sql .= "WHERE 	GL.empresa_id = '".$_SESSION['NotasCD']['empresa']."' ";
			$sql .= "AND 		GL.sw_estado = '2' ";
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
		* Funcion donde se toma de la base de datos el nombre del auditor 
		* 
		* @param  int usuario_id  
		* @return string nombre del auditor 
		************************************************************************************/
		function ObtenerUsuarioNombre($id)
		{
			if($id != null || $id != 0)
			{
				$sql  = "SELECT nombre FROM system_usuarios WHERE usuario_id = ".$id;
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
	
				if (!$rst->EOF)
				{
					$UsuarioNombre = $rst->fields[0];
					$rst->MoveNext();
			    }
				$rst->Close();
			}			
	 		return $UsuarioNombre;
		}
		/****************************************************************************************
		* Funcion mediante la cual se buscan los cargos glosados de las cuentas pertenecientes 
		* a una factura
		* 
		* @param string identificador de la glosa 
		* @return array datos de los cargos glosados  
		*****************************************************************************************/
		function ObtenerCargosGlosados($glosaId)
		{
			$sql  = "SELECT	C.numerodecuenta, ";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				GC.valor_aceptado ,";
			$sql .= "				'---' ,";
			$sql .= "				'---' ,";
			$sql .= "				CASE WHEN GC.sw_glosa_total_cuenta = '0' THEN 'DA' ";
			$sql .= "		    		 WHEN GC.sw_glosa_total_cuenta = '1' THEN 'DT' END ";
			$sql .= "FROM		cuentas C,";
			$sql .= "				glosas_detalle_cuentas GC LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(GM.motivo_glosa_id = GC.motivo_glosa_id) ";
			$sql .= "WHERE	GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 		C.numerodecuenta = GC.numerodecuenta ";
			$sql .= "AND		GC.sw_estado = '2' ";
			$sql .= "UNION  ";
			$sql .= "SELECT	CD.numerodecuenta, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				GC.valor_aceptado, ";
			$sql .= "				CD.cargo,  ";
			$sql .= "				TD.descripcion, ";
			$sql .= "				'DC' ";
			$sql .= "FROM 	glosas_detalle_cargos GC, ";
			$sql .= "				cuentas_detalle CD, ";
			$sql .= "				glosas_motivos GM,";
			$sql .= "				glosas_detalle_cuentas GD, ";
			$sql .= "				tarifarios_detalle TD ";
			$sql .= "WHERE 	GC.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 		GC.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 		GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 		GC.transaccion = CD.transaccion ";
			$sql .= "AND 		GC.sw_estado = '2' ";
			$sql .= "AND 		GC.glosa_id = ".$glosaId." ";
			$sql .= "AND 		TD.cargo = CD.cargo ";
			$sql .= "AND 		TD.tarifario_id = CD.tarifario_id ";
			$sql .= "UNION ";
			$sql .= "SELECT CD.numerodecuenta, ";
			$sql .= "				GM.motivo_glosa_descripcion, ";
			$sql .= "				GI.valor_aceptado, ";
			$sql .= "				'--', ";
			$sql .= "				ID.descripcion, ";
			$sql .= "				'DI' ";
			$sql .= "FROM 	glosas_detalle_inventarios GI, ";
			$sql .= "				cuentas CD, ";
			$sql .= "				glosas_motivos GM, ";
			$sql .= "				glosas_detalle_cuentas GD, ";
			$sql .= "				inventarios_productos ID ";
			$sql .= "WHERE	GI.motivo_glosa_id = GM.motivo_glosa_id ";
			$sql .= "AND 		GI.glosa_detalle_cuenta_id = GD.glosa_detalle_cuenta_id ";
			$sql .= "AND 		GD.numerodecuenta = CD.numerodecuenta ";
			$sql .= "AND 		GI.sw_estado = '2' ";
			$sql .= "AND 		GI.glosa_id = ".$glosaId." ";
			$sql .= "AND		GI.codigo_producto = ID.codigo_producto ";
			$sql .= "AND 		GD.sw_estado = '2' ";
			$sql .= "AND 		GD.glosa_id = GI.glosa_id ";
			$sql .= "ORDER BY 1,6 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			while (!$rst->EOF)
			{
				$cargos[$i] = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3]."*".$rst->fields[4]."*".$rst->fields[5];
				$rst->MoveNext();
				$i++;
		    }
			$rst->Close();
			return $cargos;
		}
		/********************************************************************************** 
		* Funcion donde se obtiene el sql que hace la busqueda de glosas que tienen notas 
		* creditos hechas 
		* 
		* @return boolean 
		***********************************************************************************/
		function ObtenerSqlBuscarNotasCredito()
		{		
			$empresa_id = $_SESSION['NotasCD']['empresa'];

			$sql  = "SELECT	NC.prefijo AS prefijo_nota, ";
			$sql .= "				NC.numero, ";
			$sql .= "				NC.codigo, ";
			$sql .= "				G.glosa_id, ";
			$sql .= "				G.prefijo, ";
			$sql .= "				G.factura_fiscal, ";
			$sql .= "				T.nombre_tercero,	";
			$sql .= "				SU.nombre, ";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa, ";
			$sql .= "				SUM(NC.valor_glosa) AS valor_glosa, ";
			$sql .= "				SUM(NC.valor_aceptado) AS valor_aceptado, ";
			$sql .= "				SUM(NC.valor_no_aceptado) AS valor_no_aceptado ";
			$where .= "FROM 		glosas G, ";
			//$where .= "					view_fac_facturas F, ";
			$where .= "					(SELECT	fac_facturas.tercero_id,  ";
			$where .= "									fac_facturas.tipo_id_tercero, "; 
			$where .= "									fac_facturas.prefijo,  ";
			$where .= "									fac_facturas.factura_fiscal,  ";
			$where .= "									fac_facturas.total_factura,  ";
			$where .= "									fac_facturas.empresa_id,  ";
			$where .= "									fac_facturas.saldo  ";
			$where .= "					FROM		fac_facturas ";
			$where .= "					WHERE 	fac_facturas.sw_clase_factura = '1' ";
			$where .= "					UNION  ";
			$where .= "					SELECT	facturas_externas.tercero_id,  ";
			$where .= "									facturas_externas.tipo_id_tercero,  ";
			$where .= "									facturas_externas.prefijo,  ";
			$where .= "									facturas_externas.factura_fiscal,  ";
			$where .= "									facturas_externas.total_factura,  ";
			$where .= "									facturas_externas.empresa_id,  ";
			$where .= "									facturas_externas.saldo  ";
			$where .= "					FROM 		facturas_externas) AS F, ";
			$where .= "					terceros T, ";
			$where .= "					system_usuarios SU, ";
			$where .= "					(	SELECT 	prefijo, ";
			$where .= "										numero, ";
			$where .= "										glosa_id, ";
			$where .= "										usuario_id, ";
			$where .= "										'NT' AS codigo,"; 
			$where .= "										TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro, ";
			$where .= "										COALESCE(SUM(valor_glosa),0) AS valor_glosa, ";
			$where .= "										COALESCE(SUM(valor_aceptado),0) AS valor_aceptado, ";
			$where .= "										COALESCE(SUM(valor_no_aceptado),0) AS valor_no_aceptado  ";
			$where .= "						FROM 		notas_credito_glosas ";
			$where .= "						GROUP BY 1,2,3,4,5,6 ";
			$where .= "						UNION ";
			$where .= "						SELECT 	prefijo, ";
			$where .= "										numero, ";
			$where .= "										glosa_id, ";
			$where .= "										usuario_id,  ";
			$where .= "										'NP' AS codigo, "; 
			$where .= "										TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro, ";
			$where .= "										COALESCE(SUM(valor_glosa),0) AS valor_glosa,  ";
			$where .= "										COALESCE(SUM(valor_aceptado),0) AS valor_aceptado, ";
			$where .= "										COALESCE(SUM(valor_no_aceptado),0) AS valor_no_aceptado  ";
			$where .= "						FROM 		notas_credito_glosas_detalle_cargos  ";
			$where .= "						GROUP BY 1,2,3,4,5,6 ";
			$where .= "						UNION ";
			$where .= "						SELECT 	prefijo, ";
			$where .= "										numero, ";
			$where .= "										glosa_id, ";
			$where .= "										usuario_id,  ";
			$where .= "										'NP' AS codigo, "; 
			$where .= "										TO_CHAR(fecha_registro,'DD/MM/YYYY') AS registro, ";
			$where .= "										COALESCE(SUM(valor_glosa),0) AS valor_glosa,  ";
			$where .= "										COALESCE(SUM(valor_aceptado),0) AS valor_aceptado, ";
			$where .= "										COALESCE(SUM(valor_no_aceptado),0) AS valor_no_aceptado  ";
			$where .= "						FROM 		notas_credito_glosas_detalle_inventarios  ";
			$where .= "						GROUP BY 1,2,3,4,5,6 ";
			$where .= "					) AS NC ";	
			$where .= "WHERE 	G.empresa_id = '".$empresa_id."'  ";
			$where .= "AND 		G.sw_estado <> '0'  ";
			$where .= "AND 		G.prefijo = F.prefijo  ";
			$where .= "AND 		G.factura_fiscal = F.factura_fiscal  ";
			$where .= "AND		F.tercero_id = T.tercero_id  ";
			$where .= "AND 		F.tipo_id_tercero = T.tipo_id_tercero  ";
			$where .= "AND 		NC.valor_glosa > 0  ";
			$where .= "AND 		NC.glosa_id = G.glosa_id  ";
			$where .= "AND		SU.usuario_id = NC.usuario_id ";
			
			if($_REQUEST['tercero_id'] != "")
				$where .= "AND T.tercero_id = ".$_REQUEST['tercero_id']." ";
			
			if($_REQUEST['tipo_id_tercero'] != 0)
				$where .= "AND T.tipo_id_tercero = '".$_REQUEST['tipo_id_tercero']."' ";
			
			if($_REQUEST['numero_glosa'] != "")
				$where .= "AND G.glosa_id = ".$_REQUEST['numero_glosa']." ";
			
			if($_REQUEST['nombreTercero'])
				$where .= "AND	T.nombre_tercero ILIKE '%".$_REQUEST['nombreTercero']."%' ";
			
			if($_REQUEST['fecha_inicio'] != "")
			{
				$fecha = explode("/",$_REQUEST['fecha_inicio']);
				$where .= "AND  G.fecha_glosa >= '".$fecha[2]."-".$fecha[1]."-".$fecha[0]." 00:00:00' ";
			}
			if($_REQUEST['fecha_fin'] != "")
			{
				$fecha = explode("/",$_REQUEST['fecha_fin']);
				$where .= "AND  G.fecha_glosa <= '".$fecha[2]."-".$fecha[1]."-".$fecha[0]." 00:00:00' ";
			}
			
			$_SESSION['SqlContarFacturas'] = "SELECT COUNT(*) ".$where;
			$where .= "GROUP BY 1,2,3,4,5,6,7,8,9 ";
			$_SESSION['SqlBuscarFacturas'] = $sql.$where;
			
			if($this->PrimeraVez != 1)
			{
				$this->MostrarNotasCredito();
			}
						
			return true;
		}
		/********************************************************************************** 
		* Funcion donde se procesan los sql, uno que cuenta el numero de facturas segun las 
		* condiciones de busqueda que se den y otro que trae los datos de las mismas 
		* 
		* @return array datos de las facturas 
		************************************************************************************/ 
		function ObtenerNotasCredito()
		{
			if(!$rst = $this->ConexionBaseDatos($_SESSION['SqlBuscarFacturas'])) 
				return false;
			
			$cont = 0;
			if(!$rst->EOF) $cont = $rst->RecordCount();
			$this->ProcesarSqlConteo("",null,$cont);
	
			$sql  = $_SESSION['SqlBuscarFacturas'];	
			$sql .= "ORDER BY 2 DESC ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while(!$rst->EOF)
			{
				$glosa[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $glosa;
		}
		/*************************************************************************************** 
		* Funcion por la cual se toma de la base de datos la informacion de la nota credito 
		* correspondiente a la glosa seleccionada 
		*
		* @params 	int numero de glosa
		*			string cadena donde se relaciona cual fue la nota seleccionada
		* 
		* @return array 
		****************************************************************************************/
		function ObtenerInformacionNotaCredito($glosa_id,$codigo,$numero)
		{

			$sql .= "SELECT	TO_CHAR(NC.fecha_registro,'DD/MM/YYYY') AS fecha_registro,";
			$sql .= "				T.nombre_tercero,";
			$sql .= "				T.tipo_id_tercero,";
			$sql .= "				T.tercero_id,";
			$sql .= "				G.glosa_id,";
			$sql .= "				GM.motivo_glosa_descripcion,";
			$sql .= "				TC.descripcion,";
			$sql .= "				G.prefijo||' '||G.factura_fiscal AS factura,";
			$sql .= "				TO_CHAR(G.fecha_glosa,'DD/MM/YYYY') AS fecha_glosa,";
			$sql .= "				G.documento_interno_cliente_id,";
			
			if($codigo == "NT")
			{			
				$sql .= "				COALESCE(NC.valor_glosa,0) AS valor_glosa,";
				$sql .= "				COALESCE(NC.valor_aceptado,0) AS valor_aceptado,";
				$sql .= "				COALESCE(NC.valor_no_aceptado,0) AS valor_no_aceptado,";
			}
			else
			{	
				$sql .= "				COALESCE(NC.valor_glosa,0) + COALESCE(NI.valor_glosa,0) AS valor_glosa,";
				$sql .= "				COALESCE(NC.valor_aceptado,0) + COALESCE(NI.valor_aceptado,0) AS valor_aceptado,";
				$sql .= "				COALESCE(NC.valor_no_aceptado,0) + COALESCE(NI.valor_no_aceptado,0) AS valor_no_aceptado,";
			}	
			
			$sql .= "				U.nombre,";
			$sql .= "				SU.nombre AS responsable,";
			$sql .= "				TO_CHAR(G.fecha_cierre,'DD/MM/YYYY') AS fecha_cierre,";
			$sql .= "				G.observacion ";
			$sql .= "FROM 	glosas G LEFT JOIN glosas_motivos GM ";
			$sql .= "				ON(G.motivo_glosa_id = GM.motivo_glosa_id) ";
			$sql .= "				LEFT JOIN glosas_tipos_clasificacion TC ";
			$sql .= "				ON(G.glosa_tipo_clasificacion_id = TC.glosa_tipo_clasificacion_id) ";
			$sql .= "				LEFT JOIN system_usuarios U ";
			$sql .= "				ON(G.auditor_id = U.usuario_id) ";
			$sql .= "				LEFT JOIN ";
			if($codigo == "NT")
			{
				$sql .= "				(	SELECT 	glosa_id,fecha_registro,usuario_id, ";
				$sql .= "									SUM(valor_glosa) AS valor_glosa,";
				$sql .= "									SUM(valor_aceptado) AS valor_aceptado,";
				$sql .= "									SUM(valor_no_aceptado) AS valor_no_aceptado ";
				$sql .= "		 			FROM 		notas_credito_glosas ";
				$sql .= "		 			WHERE  	glosa_id = ".$glosa_id." ";
				$sql .= "		 			AND			numero = ".$numero." ";
				$sql .= "		 			GROUP BY 1,2,3) AS NC ";
				$sql .= "				ON(	NC.glosa_id = G.glosa_id), ";
			}
			else
			{
				$sql .= "		 		(	SELECT 	glosa_id,fecha_registro,usuario_id,";
				$sql .= "									SUM(valor_glosa) AS valor_glosa,";
				$sql .= "									SUM(valor_aceptado) AS valor_aceptado,";
				$sql .= "									SUM(valor_no_aceptado) AS valor_no_aceptado ";
				$sql .= "		 			FROM 		notas_credito_glosas_detalle_cargos ";
				$sql .= "		 			WHERE  	glosa_id =".$glosa_id." ";
				$sql .= "		 			AND			numero = ".$numero." ";
				$sql .= "		 			GROUP BY 1,2,3) AS NC ";
				$sql .= "				ON(	NC.glosa_id = G.glosa_id) ";
				$sql .= "		 		LEFT JOIN ";
				$sql .= "		 		(	SELECT 	glosa_id,fecha_registro,usuario_id, ";
				$sql .= "									SUM(valor_glosa) AS valor_glosa,";
				$sql .= "									SUM(valor_aceptado) AS valor_aceptado,";
				$sql .= "									SUM(valor_no_aceptado) AS valor_no_aceptado ";
				$sql .= "		 			FROM		notas_credito_glosas_detalle_inventarios ";
				$sql .= "		 			WHERE  glosa_id = ".$glosa_id." ";
				$sql .= "		 			AND		numero = ".$numero." ";
				$sql .= "		 			GROUP BY 1,2,3 ";
				$sql .= "				)AS NI ";
				$sql .= "				ON(	NI.glosa_id = G.glosa_id), ";
			}
			$sql .= "				view_fac_facturas F,";
			$sql .= "				terceros T, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	G.glosa_id = ".$glosa_id." ";
			$sql .= "AND 		G.prefijo = F.prefijo ";
			$sql .= "AND		G.factura_fiscal = F.factura_fiscal ";	
			$sql .= "AND 		F.tercero_id = T.tercero_id ";
			$sql .= "AND		F.tipo_id_tercero = T.tipo_id_tercero ";
			
			if($codigo == "NT")
				$sql .= "AND		NC.usuario_id = SU.usuario_id ";
			else
				$sql .= "AND		(NI.usuario_id = SU.usuario_id OR NC.usuario_id = SU.usuario_id) ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while(!$rst->EOF)
			{
				$nota = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();

			$this->TerceroId = $nota['tercero_id'];
			$this->TerceroNit = $nota['tipo_id_tercero'];
			$this->GlosaFactura = $nota['factura'];
			$this->TerceroNombre = $nota['nombre_tercero'];
			$this->GlosaObservacion = $nota['observacion'];
			$this->GlosaFechaCierre = $nota['fecha_cierre'];
			$this->GlosaValorGlosado = $nota['valor_glosa'];
			$this->GlosaValorAceptado = $nota['valor_aceptado'];
			$this->GlosaIdentificador = $nota['glosa_id'];
			$this->GlosaValorNoAceptado = $nota['valor_no_aceptado'];
			$this->GlosaFechaGlosamiento = $nota['fecha_glosa'];
			$this->GlosaDocumentoInterno = $nota['documento_interno_cliente_id'];
			$this->GlosaMotivoGlosamiento = $nota['motivo_glosa_descripcion'];
			$this->NotaCreditoResponsable = $nota['responsable'];
			$this->GlosaTipoClasificacion = $nota['descripcion'];
			$this->NotaCreditoFechaRegistro = $nota['fecha_registro'];
	
			$this->GlosaAuditor = $nota['nombre'];
			return true;
		} 
		/****************************************************************************************
		* @return array 
		*****************************************************************************************/
		function EjecutarSentencias($sql)
		{
			if(!$rst = $this->ConexionBaseDatos($sql))
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$i=0;
			while(!$rst->EOF)
			{
				$retorno[$i] = $rst->fields[0]."*".$rst->fields[1]."*".$rst->fields[2]."*".$rst->fields[3];
				$rst->MoveNext();
				$i++;
			}
			$rst->Close();
			return $retorno;
		}
		/****************************************************************************************
		* @return array 
		*****************************************************************************************/
		function ObtenerNumeracion($sql,&$dbconn)
		{
			$rst = $dbconn->Execute($sql);

			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			if(!$rst->EOF)
			{
				$retorno[0] = $rst->fields[0];
				$retorno[1] = $rst->fields[1];
				$rst->MoveNext();
			}

			return $retorno;
		}
		/************************************************************************************
		*
		* @return string Nombre del tercero 
		*************************************************************************************/
		function ObtenerTercero($tipoId, $Id)
		{			
			$sql .= "SELECT	nombre_tercero ";
			$sql .= "FROM		terceros ";
			$sql .= "WHERE	tipo_id_tercero = '".$tipoId."' ";
			$sql .= "AND		tercero_id = '".$Id."' ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;	
			
			if(!$rst->EOF)
			{
				$nombre = $rst->fields[0];
				$rst->MoveNext();
			}
			$rst->Close();
			return $nombre;
		}
		/**************************************************************************************
		* Funcion en la que se obtienen las observaciones hechas a la respùestas de la nota
		*
		* @return array datos de la observacion
		***************************************************************************************/
		function ObtenerObservaciones($glosa_id)
		{
			$sql .= "SELECT observacion ";
			$sql .= "FROM		respuesta_glosas "; 
			$sql .= "WHERE 	glosa_id = ".$glosa_id." ";
			
			$observacion = array();
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			while (!$rst->EOF)
			{
				$observa[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}	
			$rst->Close();
			return $observa;
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
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			return $rst;
		}
	}
?>