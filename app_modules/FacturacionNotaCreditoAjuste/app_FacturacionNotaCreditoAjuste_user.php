<?php
	/**  
	* $Id: app_FacturacionNotaCreditoAjuste_user.php,v 1.2 2010/03/12 18:41:36 hugo Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Hugo F  Manrique 
	*/
	IncludeClass('NotasDebito','','app','FacturacionNotaCreditoAjuste');
	IncludeClass('NotasCredito','','app','FacturacionNotaCreditoAjuste');
	class app_FacturacionNotaCreditoAjuste_user extends classModulo
	{
		function app_FacturacionNotaCreditoAjuste_user()
		{
			return true;
		}
		/********************************************************************************** 
		* Retorna las empresas a las cuales se les ha dado permiso de usar este modulo 
		* 
		* @access public
		***********************************************************************************/
		function BuscarEmpresasUsuario()
		{
			unset($_SESSION['NotasAjuste']);
			
			$sql  = "SELECT	E.empresa_id AS empresa, ";
			$sql .= "				E.razon_social AS razon_social, ";
			$sql .= "				E.tipo_id_tercero,  ";
			$sql .= "				E.id  ";
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
		* Funcion donde se obtienen las variables de sesion
		***********************************************************************************/
		function ObtenerVatriables()
		{
			if(empty($_SESSION['NotasAjuste']['empresa']))
			{
				$_SESSION['NotasAjuste']['empresa'] = $_REQUEST['permisoajuste']['empresa'];
				$_SESSION['NotasAjuste']['rz_social'] = $_REQUEST['permisoajuste']['razon_social'];
				$_SESSION['NotasAjuste']['tipo_id_tercero'] = $_REQUEST['permisoajuste']['tipo_id_tercero'];
				$_SESSION['NotasAjuste']['id'] = $_REQUEST['permisoajuste']['id'];
			}
			
			SessionDelVar("TipoNota");
			SessionDelVar("Prefijos");
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','main');
			$this->actionH1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaAjuste');
			$this->actionH2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes',array("sw_tipo"=>'C'));
			$this->actionH3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes',array("sw_tipo"=>'D'));
			$this->actionH4 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarNotasCredito');
			$this->actionH5 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarNotasDebito');
			$this->actionH6 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaInformacionNotasAjuste');
			$this->actionH7 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotasAjusteExterna');
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CrearNotaAjuste()
		{
			if(empty($_SESSION['NotasAjuste']['empresa']))
				return false;
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaSubmenuPrincipal');
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','CrearNotaAjusteBD');
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CrearNotaAjusteBD()
		{
			$this->Valor = $_REQUEST['valor_na'];
			if(!is_numeric($this->Valor))
			{
				$this->Parametro = "MensajeError";
				$this->frmError['MensajeError'] = "EL VALOR DE LA NOTA DE AJUSTE ES INCORRECTO";	
			}
			else
			{
				$this->Observacion = $_REQUEST['observa'];
				($this->Observacion)? $this->Observacion = "'".$this->Observacion."'": $this->Observacion = "NULL";
				($_REQUEST['tercero_id'])? $tercero_id = "'".$_REQUEST['tercero_id']."'": $tercero_id = "NULL";
				($_REQUEST['tercero_tipo'])? $tipo_tercero = "'".$_REQUEST['tercero_tipo']."'": $tipo_tercero = "NULL";
				
				$empresa = $_SESSION['NotasAjuste']['empresa'];
				
				$sql .= "INSERT INTO tmp_notas_credito_ajuste(";
				$sql .= "		tmp_nota_id,";
				$sql .= "		empresa_id,";
				$sql .= "		total_nota_ajuste,";
				$sql .= "		fecha_registro,";
				$sql .= "		usuario_id, ";
				$sql .= "		tercero_id, ";
				$sql .= "		tipo_id_tercero, ";
				$sql .= "		observacion	";
				$sql .= "		) ";
				$sql .= "VALUES(";
				$sql .= "		(SELECT COALESCE(MAX(tmp_nota_id),0)+1 FROM tmp_notas_credito_ajuste ),";
				$sql .= "		'".$empresa."',";
				$sql .= "		'".$this->Valor."',";
				$sql .= "		 NOW(),";
				$sql .= "		".UserGetUID().", ";
				$sql .= "		".$tercero_id.", ";
				$sql .= "		".$tipo_tercero.", ";
				$sql .= "		".$this->Observacion." ";
				$sql .= ") ";
				
				if(!$this->ConexionBaseDatos($sql))
					return false;
				
				$this->Valor = "";
				$this->Parametro = "Informacion";
			}

			$this->Observacion = "";
			switch($_REQUEST['retorno'])
			{
				case '1': 
					$this->frmError['Informacion'] = "LA NOTA POR ANTICIPOS SE HA CREADO CORRECTAMENTE";
					$this->FormaCrearNotaAnticipo();	
				break;
				default:
					$this->frmError['Informacion'] = "LA NOTA DE AJUSTE SE HA CREADO CORRECTAMENTE";	
					$this->FormaCrearNotaAjuste(); 		
				break;
			}
			return true;
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaAdicionarConceptos
		***********************************************************************************/
		function AdicionarConceptos()
		{
			$this->Debitos = $this->Creditos = 0;
			$this->Codigo = $_REQUEST['tmp_id'];
			
			$this->ValorFactura = $this->ObtenerValorfacturas($this->Codigo);
			
			$arreglo = array("tercero_id"=>$_REQUEST['tercero_id'],"tercero_tipo"=>$_REQUEST['tercero_tipo'],
							 			 	 "tercero_nombre"=>$_REQUEST['tercero_nombre'],"tmp_id"=>$this->Codigo,
							 			 	 "valorNota"=>$this->ValorNota,"retorno"=>$_REQUEST['retorno']);
											 
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaAnticipo',$arreglo);
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','AdicionarConceptoBD',$arreglo);
			$this->action3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaModificarInformacion',array("datos"=>$arreglo));
			$this->action4 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCruzarNotasAnticipos',$arreglo);
			$this->action7 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaBuscarTerceros');
		}
		/**********************************************************************************
		* Funcion donde se obtiene el valor abonado por las facturas para una nota de ajuste
		* 
		* @param int $codigo Codigo de la nota de ajuste temporal
		* @return array datos de las facturas con los respectivos valores
		***********************************************************************************/
		function ObtenerValorfacturas($codigo)
		{
			$valor = array();
			$arreglo = array();
			
			$sql .= "SELECT	valor_abonado AS abono,";
			$sql .= " 			prefijo_factura,";
			$sql .= " 			factura_fiscal, ";
			$sql .= " 			tmp_nota_id ";
			$sql .= "FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$codigo." ";
			$sql .= "AND		empresa_id = '".$_SESSION['NotasAjuste']['empresa']."' ";
			
			$arreglo['tmp_id'] = $this->Codigo;
			$arreglo['retorno'] = $_REQUEST['retorno'];
			$arreglo['tercero_id'] = $_REQUEST['tercero_id'];
			$arreglo['tercero_tipo'] = $_REQUEST['tercero_tipo'];
			$arreglo['tercero_nombre'] = $_REQUEST['tercero_nombre'];
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
      	$valor[] = $rst->GetRowAssoc($ToUpper = false);
				
				$arreglo['prefijo'] = $rst->fields[1];
				$arreglo['factura_fiscal'] = $rst->fields[2];
				$arreglo['tmp_nota_factura_id'] = $rst->fields[3];
				$this->action6[] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','EliminarAsociacionFacturaBD',array("datos"=>$arreglo));
				
      	$rst->MoveNext();
      }
      $rst->Close();
      
      return $valor;
		}
		/***
		* Funcion donde se elimina de las tablas temporales la asociacion entre un concepto y 
		* una nota de ajuste
		* 
		* @return boolean 
		**/
		function EliminarAsociacionFacturaBD()
		{
			$datos = $_REQUEST['datos'];
			if(!empty($datos))
			{
				$sql .= "DELETE FROM  tmp_notas_credito_ajuste_detalle_facturas ";
				$sql .= "WHERE	tmp_nota_ajuste_id = ".$datos['tmp_id']." ";
				$sql .= "AND		prefijo_factura = '".$datos['prefijo']."' ";
				$sql .= "AND		factura_fiscal = ".$datos['factura_fiscal']." ";
				$sql .= "AND		tmp_nota_id = ".$datos['tmp_nota_factura_id']." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				$this->Parametro = "Informacion";
				$this->frmError['Informacion'] = "EL CONCEPTO SE HA ELIMINADO DE LA LISTA CORRECTAMENTE";
				$_REQUEST = $datos;
			}
			$this->FormaAdicionarConceptos();
			return true;		
		}
		/***
		* Funcion donde se obtiene el valor de los conceptos que pertenecen a un recibo de caja 
		* 
		* @return array datos de los conceptos 
		**/
		function ObtenerValorConceptos()
		{
			$sql .= "SELECT	TC.valor,";
			$sql .= " 			TC.naturaleza, ";
			$sql .= " 			AC.descripcion, ";
			$sql .= " 			AC.concepto_id, ";
			$sql .= "				TC.tmp_concepto_id, ";
			$sql .= "				COALESCE(DE.descripcion,'NO APLICA')||'/'||COALESCE(TC.tipo_id_tercero||' '||TC.tercero_id,'NINGUNO') AS departamento ";
			$sql .= "FROM 	notas_credito_ajuste_conceptos AC, ";
			$sql .= "				tmp_notas_credito_ajuste_detalle_conceptos TC";
			$sql .= "				LEFT JOIN departamentos DE ";
			$sql .= "				ON(TC.departamento = DE.departamento) ";
			$sql .= "WHERE	TC.empresa_id = '".$_SESSION['NotasAjuste']['empresa']."' ";
			$sql .= "AND		TC.concepto_id = AC.concepto_id ";
			$sql .= "AND		TC.tmp_nota_ajuste_id = ".$this->Codigo." ";
			$sql .= "ORDER BY 5 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$arreglo['tmp_id'] = $this->Codigo;
			$arreglo['valorNota'] = $this->ValorNota;
			
			while(!$rst->EOF)
			{
				$conceptos[] = $rst->GetRowAssoc($ToUpper = false);
				
				$arreglo['retorno'] = $_REQUEST['retorno'];
				$arreglo['tercero_id'] = $_REQUEST['tercero_id'];
				$arreglo['tercero_tipo'] = $_REQUEST['tercero_tipo'];
				$arreglo['tercero_nombre'] = $_REQUEST['tercero_nombre'];
				
				$arreglo['concepto'] = $rst->fields[3];
				$arreglo['tmpConceptoId'] = $rst->fields[4];
				$this->action5[] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','EliminarConceptosBD',$arreglo);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $conceptos;
		}
		/***
		* Funcion donde se obtienen los conceptos que pueden ser adicionados a un recibo de caja 
		* 
		* @return array datos de los conceptos de tesoreria 
		**/
		function ObtenerConceptos()
		{
			$nd = new NotasDebito();
			$conceptos = $nd->ObtenerConceptos('D',$_SESSION['NotasAjuste']['empresa']);
			
			return $conceptos;
		}
		/**********************************************************************************
		* Funcion en la que se adicionan los conceptos a la base de datos
		* 
		* @return boolean 
		***********************************************************************************/
		function AdicionarConceptoBD()
		{
			$selectC = explode("*",$_REQUEST['concepto']);
			
			($selectC[2] == 1)? $this->Script = 1:$this->Script = 0;
			($selectC[3] == 1)? $this->Script2 = 1:$this->Script2 = 0;
			
			$this->Concepto = $selectC[0];
			$this->Departamento = $_REQUEST['departamento'];
			$this->ValorConcepto = $_REQUEST['valor_concepto'];
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			$this->TerceroIdentificador = $_REQUEST['tercero_identifica'];
			
			if($this->Concepto == "0")
			{
				$this->Parametro = "MensajeError";
				$this->frmError['MensajeError'] = "SE DEBE INDICAR EL CONCEPTO QUE SE VA A ADICIONAR";
			}
			else if(!is_numeric($this->ValorConcepto))
				{
					$this->Parametro = "MensajeError";
					$this->frmError['MensajeError'] = "EL VALOR DEl CONCEPTO NO ES VALIDO";
				}
				else if($selectC[2] == 1 && $this->Departamento == 0)
					{
						$this->Parametro = "MensajeError";
						$this->frmError['MensajeError'] = "SE DEBE SELECCIONAR EL DEPARTAMENTO ASOCIADO AL CONCEPTO";
					}
					else if($selectC[3] == 1 && $this->TerceroIdentificador == "")
						{
							$this->Parametro = "MensajeError";
							$this->frmError['MensajeError'] = "SE DEBE SELECCIONAR EL TERCERO ASOCIADO AL CONCEPTO";
						}
						else
							{			
							
								($this->Departamento == 0)? $dep = "NULL": $dep = "'".$this->Departamento."'";
								if($this->TerceroIdentificador == "") 
								{
									$tercero_id = "NULL";
									$tercero_tipo = "NULL";
								}
								else
								{
									$arr = explode("*",$this->TerceroIdentificador);
									$tercero_id = "'".$arr[1]."'";
									$tercero_tipo = "'".$arr[0]."'";
								}
							
								$this->Codigo = $_REQUEST['tmp_id'];
							
								$sql .= "INSERT INTO tmp_notas_credito_ajuste_detalle_conceptos(";
								$sql .= "		empresa_id,";
								$sql .= "		valor,";
								$sql .= "		naturaleza,";
								$sql .= "		tmp_nota_ajuste_id,";
								$sql .= "		concepto_id,";
								$sql .= "		departamento,";
								$sql .= "		tipo_id_tercero,";
								$sql .= "		tercero_id) ";
								$sql .= "VALUES (";
								$sql .= "		'".$_SESSION['NotasAjuste']['empresa']."',";
								$sql .= "		'".$this->ValorConcepto."',";
								$sql .= "		'".$selectC[1]."',";
								$sql .= "		'".$this->Codigo."',";
								$sql .= "		'".$selectC[0]."',";
								$sql .= "		 ".$dep.", ";
								$sql .= "		 ".$tercero_tipo.", ";
								$sql .= "		 ".$tercero_id." ";
								$sql .= "		)";
							
								if(!$rst = $this->ConexionBaseDatos($sql))
									return false;
									
								$this->Script = 0;
								$this->Script2 = 0;
								$this->Concepto = "0";
								$this->Departamento = 0;
								$this->ValorConcepto = "";
								$this->TerceroNombre = "";
								$this->TerceroIdentificador = "";
								$this->Parametro = "Informacion";
								$this->frmError['Informacion'] = "EL CONCEPTO SE HA ADICIONADO CORRECTAMENTE";
							}
			$this->FormaAdicionarConceptos();
			return true;
		}
		/**********************************************************************************
		* Funcion en lka que se eliminan los conceptos de la base datos, segun hayan sido 
		* seleccionados
		* 
		* @return boolean 
		***********************************************************************************/
		function EliminarConceptosBD()
		{	
			$this->Id = $_REQUEST['tmpConceptoId'];
			$this->Codigo = $_REQUEST['tmp_id'];
			$this->Concept = $_REQUEST['concepto'];
			
			$sql .= "DELETE FROM tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$this->Codigo." ";
			$sql .= "AND		concepto_id = '".$this->Concept."' ";
			$sql .= "AND		tmp_concepto_id = ".$this->Id." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			$this->Parametro = "Informacion";
			$this->frmError['Informacion'] = "EL CONCEPTO SE HA ELIMINADO DE LA LISTA CORRECTAMENTE";

			$this->FormaAdicionarConceptos();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CruzarNotas()
		{
			$this->Doc = $_REQUEST['tipo_id'];
			$this->TmpId = $_REQUEST['tmp_id'];
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FechaFin = $_REQUEST['fecha_fin'];
			$this->TerceroId = $_REQUEST['documento'];
			$this->FechaInicio = $_REQUEST['fecha_inicio'];
			$this->FacturaFiscal = $_REQUEST['factura'];
			$this->NombreTercero = $_REQUEST['nombre_tercero'];
			
			$this->ObtenerValoresNota();
			
			$arreglo = array("tipo_id"=>$this->Doc,"fecha_fin"=>$this->FechaFin,"tmp_id"=>$this->TmpId,
							 				 "valorNota"=>$this->TmpValor,"documento"=>$this->TerceroId,"nombre_tercero"=>$this->NombreTercero,
							 				 "fecha_inicio"=>$this->FechaInicio,"prefijo"=>$this->Prefijo,"factura"=>$this->FacturaFiscal);
							 
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaAjuste');
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCruzarNotas',$arreglo);
			
			$arreglo = array("tmp_id"=>$this->TmpId,"valorNota"=>$this->TmpValor,"offset"=>$_REQUEST['offset']);
			$this->action4 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','CruzarNotasBD',$arreglo);
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturas()
		{
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FacturaFiscal = $_REQUEST['factura'];
			$empresa = $_SESSION['NotasAjuste']['empresa'];
						
			$sql  = "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha, ";
			$sql .= "				FF.tipo_id_tercero,";
			$sql .= "				FF.tercero_id,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				GL.glosa_id, ";
			$sql .= "				TNF.abono, ";
			$sql .= "				TE.nombre_tercero ";
			$where .= "FROM view_fac_facturas FF ";
			$where .= "		 	LEFT JOIN glosas GL ";
			$where .= "		 	ON(	GL.prefijo = FF.prefijo AND ";
			$where .= "					GL.factura_fiscal = FF.factura_fiscal AND ";	
			$where .= "					GL.empresa_id = FF.empresa_id AND ";
			$where .= "					GL.sw_estado NOT IN ('0','3') ) "; 
			$where .= "		 	LEFT JOIN ";
			$where .= "			(SELECT SUM(valor_abonado) AS abono, ";
			$where .= "							prefijo_factura, ";
			$where .= "							factura_fiscal ";
			$where .= "			 FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$where .= "			 GROUP BY 2,3 ";
			$where .= "			) AS TNF ";
			$where .= "		 	ON(	TNF.prefijo_factura = FF.prefijo AND  ";
			$where .= "					TNF.factura_fiscal = FF.factura_fiscal ), ";
			$where .= "			terceros TE ";
			$where .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$where .= "AND	 	FF.estado = '0' ";	
			$where .= "AND	 	FF.saldo > 0 ";
			$where .= "AND		FF.saldo - COALESCE(TNF.abono,0) > 0 ";
			$where .= "AND		TE.tercero_id = FF.tercero_id ";
			$where .= "AND		TE.tipo_id_tercero = FF.tipo_id_tercero ";
			
			if($this->Prefijo)
			{
				$where .= "AND	FF.prefijo = '".$this->Prefijo ."' ";
				if($this->FacturaFiscal)
						$where .= "AND	FF.factura_fiscal = ".$this->FacturaFiscal." ";
			}
			else
			{
				if($this->Doc != "0" && $this->TerceroId !="")
				{
					$where .= "AND	FF.tipo_id_tercero = '".$this->Doc."' ";
					$where .= "AND	FF.tercero_id = '".$this->TerceroId."' ";
				}
				
				if($this->NombreTercero)
					$where .= "AND	TE.nombre_tercero ILIKE '%".$this->NombreTercero."%' ";
				
				if($this->FechaInicio != "")
				{
					$f = explode('/',$this->FechaInicio);
	        $fec = $f[2].'-'.$f[1].'-'.$f[0];
	
					if($this->ValidarFecha($fec))
						$where .= "AND FF.fecha_registro >= '".$fec." 00:00:00' ";
				}
				
				if($this->FechaFin != "")
				{
					$f = explode('/',$this->FechaFin);
	        $fec = $f[2].'-'.$f[1].'-'.$f[0];
	
					if($this->ValidarFecha($fec))
						$where .= "AND FF.fecha_registro <= '".$fec." 00:00:00' ";
				}
			}
			
			$sqlCont  = "SELECT COUNT(*) ".$where;
			
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
				
			$sql .= $where;
			$sql .= "ORDER BY 10,1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
			{
				$facturas[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $facturas;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturasAnticipos()
		{
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FacturaFiscal = $_REQUEST['factura'];
			$empresa = $_SESSION['NotasAjuste']['empresa'];
						
			$sql  = "SELECT FF.prefijo,";
			$sql .= "				FF.factura_fiscal,";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY') AS fecha, ";
			$sql .= "				FF.tipo_id_tercero,";
			$sql .= "				FF.tercero_id,";
			$sql .= "				FF.total_factura, ";
			$sql .= "				FF.saldo, ";
			$sql .= "				GL.glosa_id, ";
			$sql .= "				TNF.abono, ";
			$sql .= "				TN.abonado ";
			$where .= "FROM view_fac_facturas FF ";
			$where .= "		 	LEFT JOIN glosas GL ";
			$where .= "		 	ON(	GL.prefijo = FF.prefijo AND ";
			$where .= "					GL.factura_fiscal = FF.factura_fiscal AND ";	
			$where .= "					GL.empresa_id = FF.empresa_id AND ";
			$where .= "					GL.sw_estado NOT IN ('0','3') ) "; 
			$where .= "		 	LEFT JOIN ";
			$where .= "			(SELECT SUM(valor_abonado) AS abono, ";
			$where .= "							prefijo_factura, ";
			$where .= "							factura_fiscal ";
			$where .= "			 FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$where .= "			 GROUP BY 2,3 ";
			$where .= "			) AS TNF ";
			$where .= "		 	ON(	TNF.prefijo_factura = FF.prefijo AND  ";
			$where .= "					TNF.factura_fiscal = FF.factura_fiscal ) ";
			$where .= "		 	LEFT JOIN ";
			$where .= "			(SELECT SUM(valor_abonado) AS abonado, ";
			$where .= "							prefijo_factura, ";
			$where .= "							factura_fiscal ";
			$where .= "			 FROM		tmp_notas_credito_ajuste_detalle_facturas ";
			$where .= "			 WHERE	tmp_nota_ajuste_id = ".$this->TmpId."	";
			$where .= "			 GROUP BY 2,3 ";
			$where .= "			) AS TN ";
			$where .= "		 	ON(	TN.prefijo_factura = FF.prefijo AND  ";
			$where .= "					TN.factura_fiscal = FF.factura_fiscal ) ";
			$where .= "WHERE 	FF.empresa_id = '".$empresa."' ";
			$where .= "AND	 	FF.estado = '0' ";	
			$where .= "AND	 	FF.saldo > 0 ";
			$where .= "AND		FF.saldo - COALESCE(TNF.abono,0) > 0 ";
			$where .= "AND		FF.tercero_id = '".$this->TerceroId."' ";
			$where .= "AND		FF.tipo_id_tercero = '".$this->TerceroTipo."' ";
			
			if($this->Prefijo)
				$where .= "AND	FF.prefijo = '".$this->Prefijo ."' ";
			
			if($this->FacturaFiscal)
				$where .= "AND	FF.factura_fiscal = ".$this->FacturaFiscal." ";
			
			$sqlCont  = "SELECT COUNT(*) ".$where;
			
			if(!$this->ProcesarSqlConteo($sqlCont))
				return false;
				
			$sql .= $where;
			$sql .= "ORDER BY 1 ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i = 0;
			$this->PagoFacturas = 0;	
			while(!$rst->EOF)
			{
				$facturas[$i] = $rst->GetRowAssoc($ToUpper = false);
				$this->PagoFacturas += $facturas[$i]['abonado'];
				$rst->MoveNext();
				$i++;
		  }
			$rst->Close();
			
			return $facturas;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerValoresNota()
		{
			$sql .= "SELECT NA.total_nota_ajuste + COALESCE(ND.valor,0) AS debitos,";
			$sql .= "		COALESCE(NF.valor,0) + COALESCE(NC.valor,0) AS creditos ";
			$sql .= "FROM	tmp_notas_credito_ajuste NA ";
			$sql .= "		LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "							tmp_nota_ajuste_id ";
			$sql .= "					FROM	tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "					WHERE 	naturaleza = 'C'";
			$sql .= "					GROUP BY 2 ";
			$sql .= "					) AS NC ";
			$sql .= "		ON(	NC.tmp_nota_ajuste_id = NA.tmp_nota_id ) ";
			$sql .= "		LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "							tmp_nota_ajuste_id ";
			$sql .= "					FROM	tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "					WHERE 	naturaleza = 'D'";
			$sql .= "					GROUP BY 2 ";
			$sql .= "					) AS ND ";
			$sql .= "		ON(	ND.tmp_nota_ajuste_id = NA.tmp_nota_id ) ";
			$sql .= "		LEFT JOIN ( SELECT	SUM(valor_abonado) AS valor,";
			$sql .= "							tmp_nota_ajuste_id ";
			$sql .= "					FROM	tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "					GROUP BY 2 ";
			$sql .= "					) AS NF ";
			$sql .= "		ON(NF.tmp_nota_ajuste_id = NA.tmp_nota_id ) ";
			$sql .= "WHERE	NA.empresa_id ='".$_SESSION['NotasAjuste']['empresa']."' ";
			$sql .= "AND	NA.tmp_nota_id ='".$this->TmpId."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			if(!$rst->EOF)
			{
				$this->Debitos = $rst->fields[0];
				$this->Creditos = $rst->fields[1];
				$this->TmpValor = $this->Debitos - $this->Creditos;
				$rst->MoveNext();
		  }
			$rst->Close();
			return true;

		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CruzarNotasBD()
		{
			$this->TmpId = $_REQUEST['tmp_id'];
			$this->Valor = $_REQUEST['valorpago'];
			$this->ValorS = $_REQUEST['valorsug'];
			$this->Facturas = $_REQUEST['facturas'];
			$this->ValorNota = $_REQUEST['tmpValorNota'];
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$bool = false;
			for($i=0; $i<sizeof($this->Valor); $i++)
			{
				if($this->Valor[$i] != "")
				{
					if(!is_numeric($this->Valor[$i]))
					{
						$this->frmError['MensajeError'] = "PARA LA FACTURA ".$this->Facturas[$i]." EL VALOR INGRESADO NO ES VALIDO ";
						$bool = false;
						break;
					}
					if($this->Valor[$i] > $this->ValorS[$i])
					{
						$this->frmError['MensajeError'] = "PARA LA FACTURA ".$this->Facturas[$i]." NO SE PUEDE INGRESAR UN VALOR MAYOR QUE EL SUGERIDO ";
						$bool = false;
						break;
					}
					
					if($this->ValorNota > 0)
					{
						$fac = explode("-",$this->Facturas[$i]);
						if($this->ValorNota < $this->Valor[$i])
						{
							$valorpago = $this->ValorNota;
							$abono .= "<li>".$this->Facturas[$i];
						}
						else
						{
							$valorpago = $this->Valor[$i];
							$pago .= "<li>".$this->Facturas[$i];
						}
						
						$this->ValorNota = $this->ValorNota - $valorpago;
						
						$sql .= "INSERT INTO tmp_notas_credito_ajuste_detalle_facturas( ";
						$sql .= "		empresa_id,";
						$sql .= "		prefijo_factura,";
						$sql .= "		factura_fiscal,";
						$sql .= "		valor_abonado,";
						$sql .= "		tmp_nota_ajuste_id)";
						$sql .= "VALUES (";
						
						$sql .= "		'".$empresa."',";
						$sql .= "		'".$fac[0]."',";
						$sql .= "		 ".$fac[1].",";
						$sql .= "		 ".$valorpago.",";
						$sql .= "		 ".$this->TmpId." ";					
						$sql .= "		);";
					}
					else
					{
						$no_pago .= "<li>".$this->Facturas[$i];
					}
					$bool = true;
				}
			}
			
			if($bool)
			{
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
			
				$informacion  = "A LAS SIGUIENTES FACTURAS SE LES HIZO UN ABONO POR EL VALOR TOTAL: <menu>".$pago."</menu>";
			
				if($abono) $informacion .= "A LAS SIGUIENTES FACTURAS SE LES HIZO UN ABONO POR UN VALOR MENOR: <menu>".$abono."</menu>";
			
				if($no_pago) $informacion .= "LAS SIGUIENTES FACTURAS NO FUERON INCLUIDAS EN LA NOTA DE AJUSTE POR QUE EL VALOR DE LA NOTA FUE CUBIERTO: <menu>".$no_pago."</menu>";
			
				($this->ValorNota > 0)?	$metodo = "FormaCruzarNotas":$metodo = "FormaCrearNotaAjuste";
				
				$arreglo = array("tmp_id"=>$this->TmpId,"valorNota"=>$this->TmpValor,"offset"=>$_REQUEST['offset']);
				$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user',$metodo,$arreglo);

				
				$this->FormaInformacion($informacion);
				return true;
			}
			
			$this->FormaCruzarNotas();
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CerrarNotaAjuste()
		{
			$datos = $_REQUEST['datos'];
			
			if($datos['diferencia'] != 0)
			{
				$_REQUEST = $datos;
				$this->Parametro = "MensajeError";
				$this->frmError['MensajeError'] = "LA NOTA NO PUEDE SER CERRADA, LA SUMA DE DEBITOS NO ES IGUAL A LA SUMA DE CREDITOS";
				$this->FormaCrearNotaAnticipo();
			}
			else
			{
				$informacion .= "<center>ESTA SEGURO DE QUE DESEA CERRAR LA NOTA DE AJUSTE ?</center>";
				$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','CerrarNotaAjusteBD',$datos);
				$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaAnticipo',$datos);
				$this->FormaInformacion($informacion);
			}
			return true;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function CerrarNotaAjusteBD()
		{
			$this->TmpId = $_REQUEST['tmp_id'];
			
			$sql  = "SELECT empresa_id,";	
			$sql .= "				total_nota_ajuste, ";	
			$sql .= "				fecha_registro,	";
			$sql .= "				usuario_id, ";
			$sql .= "				tipo_id_tercero, ";
			$sql .= "				tercero_id, ";
			$sql .= "				observacion ";
			$sql .= "FROM		tmp_notas_credito_ajuste ";
			$sql .= "WHERE	tmp_nota_id = ".$this->TmpId." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
				
			while(!$rst->EOF)
			{
				$notas = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			if(sizeof($notas) > 0)
			{
				$sql  = "SELECT empresa_id,";	
				$sql .= "				valor,";
				$sql .= "				naturaleza,";
				$sql .= "				concepto_id, ";
				$sql .= "				departamento, ";
				$sql .= "				tercero_id, ";
				$sql .= "				tipo_id_tercero ";
				$sql .= "FROM		tmp_notas_credito_ajuste_detalle_conceptos ";
				$sql .= "WHERE	tmp_nota_ajuste_id = ".$this->TmpId." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
					
				while(!$rst->EOF)
				{
					$detalleconceptos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
			  }
				$rst->Close();
				
				$sql  = "SELECT empresa_id,";	
				$sql .= "				prefijo_factura,";
				$sql .= "				factura_fiscal,";
				$sql .= "				valor_abonado ";
				$sql .= "FROM		tmp_notas_credito_ajuste_detalle_facturas ";
				$sql .= "WHERE	tmp_nota_ajuste_id = ".$this->TmpId." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
					
				while(!$rst->EOF)
				{
					$detallefacturas[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
			  }
				$rst->Close();
				
				$documento = ModuloGetVar('app','FacturacionNotaCreditoAjuste','documento_'.$notas['empresa_id']);
				$sql = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE ";//Bloqueo de tabla 
				
				list($dbconn) = GetDBConn();
				
				$dbconn->BeginTrans();
				$result = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					die(MsgOut("Error al iniciar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
					$dbconn->RollbackTrans();
					return false;
				}
				else
				{
					$sql  = "SELECT prefijo,numeracion FROM documentos ";
					$sql .= "WHERE documento_id = ".$documento." AND empresa_id = '".$notas['empresa_id']."' ";
					
					$numeracion = $this->ObtenerNumeracion($sql,&$dbconn);
					
					$tipo = "'".$notas['tipo_id_tercero']."'";
					$idtercero = "'".$notas['tercero_id']."'";
					($notas['observacion'])? $observa = "'".$notas['observacion']."'": $observa = "NULL";
					
					(sizeof($detallefacturas) > 0)? $estado = '1':$estado = '3';
					
					$sql  = "INSERT INTO  notas_credito_ajuste( ";
					$sql .= "				empresa_id,";
					$sql .= "				total_nota_ajuste,";
					$sql .= "				fecha_registro,";
					$sql .= "				usuario_id,";
					$sql .= "				tipo_id_tercero, ";
					$sql .= "				tercero_id, ";
					$sql .= "				prefijo,";
					$sql .= "				nota_credito_ajuste,";
					$sql .= "				documento_id, ";
					$sql .= "				estado, ";
					$sql .= "				observacion) ";
					$sql .= "VALUES (";
					$sql .= "		'".$notas['empresa_id']."', ";
					$sql .= "		 ".$notas['total_nota_ajuste'].", ";
					$sql .= "		'".$notas['fecha_registro']."', ";
					$sql .= "		 ".$notas['usuario_id'].", ";
					$sql .= "		 ".$tipo.", ";
					$sql .= "		 ".$idtercero.", ";
					$sql .= "		'".$numeracion['prefijo']."', ";
					$sql .= "		 ".$numeracion['numeracion'].", ";
					$sql .= "		 ".$documento.", ";
					$sql .= "		 ".$estado.", ";
					$sql .= "		 ".$observa." ";
					$sql .= "		);";
					
					for($i =0; $i< sizeof($detalleconceptos);$i++)
					{
						(!$detalleconceptos[$i]['departamento'])? $dep = "NULL": $dep = "'".$detalleconceptos[$i]['departamento']."'";
						(!$detalleconceptos[$i]['tercero_id'])? $tid = "NULL": $tid = "'".$detalleconceptos[$i]['tercero_id']."'";
						(!$detalleconceptos[$i]['tipo_id_tercero'])? $tipo = "NULL": $tipo = "'".$detalleconceptos[$i]['tipo_id_tercero']."'";
						
						$sql .= "INSERT INTO notas_credito_ajuste_detalle_conceptos( ";
						$sql .= "		empresa_id,";
						$sql .= "		valor,";
						$sql .= "		naturaleza,";
						$sql .= "		concepto_id, ";
						$sql .= "		prefijo,";
						$sql .= "		nota_credito_ajuste, ";
						$sql .= "		departamento, ";
						$sql .= "		tercero_id, ";
						$sql .= "		tipo_id_tercero ";
						$sql .= "		) ";
						$sql .= "VALUES (";
						$sql .= "		'".$detalleconceptos[$i]['empresa_id']."', ";
						$sql .= "		 ".$detalleconceptos[$i]['valor'].", ";
						$sql .= "		'".$detalleconceptos[$i]['naturaleza']."', ";
						$sql .= "		 ".$detalleconceptos[$i]['concepto_id'].", ";
						$sql .= "		'".$numeracion['prefijo']."', ";
						$sql .= "		 ".$numeracion['numeracion'].", ";
						$sql .= "		 ".$dep.", ";
						$sql .= "		 ".$tid.", ";
						$sql .= "		 ".$tipo." ";
						$sql .= "		);";				
					}
					
					for($i =0; $i< sizeof($detallefacturas);$i++)
					{
						$sql .= "INSERT INTO notas_credito_ajuste_detalle_facturas( ";
						$sql .= "		empresa_id,";
						$sql .= "		prefijo_factura,";
						$sql .= "		factura_fiscal,";
						$sql .= "		valor_abonado, ";
						$sql .= "		prefijo,";
						$sql .= "		nota_credito_ajuste ";
						$sql .= "		) ";
						$sql .= "VALUES (";
						$sql .= "		'".$detallefacturas[$i]['empresa_id']."', ";
						$sql .= "		'".$detallefacturas[$i]['prefijo_factura']."', ";
						$sql .= "		 ".$detallefacturas[$i]['factura_fiscal'].", ";
						$sql .= "		 ".$detallefacturas[$i]['valor_abonado'].", ";
						$sql .= "		'".$numeracion['prefijo']."', ";
						$sql .= "		 ".$numeracion['numeracion']." ";
						$sql .= "		);";				
					}
					
					$sql .= "UPDATE documentos ";
					$sql .= "SET 	numeracion = numeracion + 1 ";
					$sql .= "WHERE 	documento_id = ".$documento." AND empresa_id = '".$notas['empresa_id']."'; ";
					
					$sql .= "DELETE FROM tmp_notas_credito_ajuste ";
					$sql .= "WHERE	tmp_nota_id = ".$this->TmpId."; ";
	
					$rst = $dbconn->Execute($sql);
					
					if (!$rst) 
					{
						$this->frmError['MensajeError'] = "ERROR DB : TRANSACCION 1 " . $dbconn->ErrorMsg()."<br> $sql";
						$dbconn->RollbackTrans();
						return false;
					}		
					$dbconn->CommitTrans();				
					$this->Parametro = "Informacion";
					$this->frmError['Informacion'] = "LA NOTA DE AJUSTE ".$numeracion['prefijo']." ".$numeracion['numeracion']." FUE CREADA EXITOSAMENTE";
					$this->Arreglo = array("nota_credito_ajuste"=>$numeracion['numeracion'],"prefijo"=>$numeracion['prefijo'],
																 "empresa_id"=>$_SESSION['NotasAjuste']['empresa'],"tipo_id_tercero"=>$notas['tipo_id_tercero'],
																 "tercero_id"=>$notas['tercero_id']);
					$this->Imprimir = 1;
				}
			}
			
			$this->FormaCrearNotaAnticipo();
			return true;
		}
		/********************************************************************************** 
		* Funcion domde se seleccionan los tipos de id de los terceros 
		* 
		* @return array datos de tipo_id_terceros 
		***********************************************************************************/
		function ObtenerTipoIdTerceros()
		{
			$sql  = "SELECT tipo_id_tercero AS id, descripcion FROM tipo_id_terceros ";
	
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
		/************************************************************************************
		* Funcion donde se obtienen los los prefijos de las facturas para agregarlos al 
		* buscador
		*
		* @return array datos de las facturas 
		*************************************************************************************/
		function ObtenerPrefijos()
		{
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$sql  = "SELECT DISTINCT FF.prefijo ";
			$sql .= "FROM 	fac_facturas FF  ";
			$sql .= "WHERE 	FF.sw_clase_factura = '1'::bpchar  "; 
			$sql .= "AND 		FF.empresa_id = '".$empresa."'  "; 
			$sql .= "AND 		FF.estado = '0'::bpchar  ";
			$sql .= "UNION DISTINCT  "; 
			$sql .= "SELECT DISTINCT FF.prefijo  ";
			$sql .= "FROM 	facturas_externas FF  ";
			$sql .= "WHERE 	FF.empresa_id = '".$empresa."'  ";
			$sql .= "AND 		FF.estado <> '2'::bpchar ";
			
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
		/****
		* @return array 
		***/
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
				$retorno['prefijo'] = $rst->fields[0];
				$retorno['numeracion'] = $rst->fields[1];
				$rst->MoveNext();
			}

			return $retorno;
		}
		/************************************************************************************ 
		*  
		*************************************************************************************/
		function ConsultarClientes()
		{
			if($_REQUEST['buscar'])
				$this->ObtenerDatosCliente();
			
			SessionDelVar("DatosTercero");
			SessionDelVar("Tercero");
			//SessionDelVar("PrefijosA");
			if(!SessionIsSetVar("TipoNota")) SessionSetVar("TipoNota",$_REQUEST['sw_tipo']);
			
			$this->TipoFact = SessionGetVar("TipoNota");
			
			if(!SessionIsSetVar("Prefijos"))
				SessionSetVar("Prefijos",$this->ObtenerPrefijos());
			
			$this->Prefijos = SessionGetVar("Prefijos");
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaSubmenuPrincipal');
			$this->action3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes');
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes',$this->Arreglo);
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ObtenerDatosCliente()
		{
			$this->TerceroDocumento = $_REQUEST['tercero_id'];
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			$this->TerceroTipoId = $_REQUEST['tipo_id_tercero'];
			
			$this->Prefijo = $_REQUEST['prefijo'];
			$this->FacturaFiscal = $_REQUEST['factura_f'];
			
			if($this->Prefijo && $this->FacturaFiscal != "")
			{				
				$sql1  = "SELECT DISTINCT FF.tipo_id_tercero, ";
				$sql1 .= "				FF.tercero_id  ";
				$sql1 .= "FROM 		fac_facturas FF ";
				$sql1 .= "WHERE 	FF.sw_clase_factura = '1'::bpchar  ";
				$sql1 .= "AND 		FF.empresa_id = '".$_SESSION['NotasAjuste']['empresa']."'  ";
				$sql1 .= "AND 		FF.estado = '0'::bpchar  ";
				$sql1 .= "AND			FF.prefijo = '".$this->Prefijo."' ";
				$sql1 .= "AND			FF.factura_fiscal = ".$this->FacturaFiscal." ";
				$sql1 .= "UNION DISTINCT ";
				$sql1 .= "SELECT DISTINCT FF.tipo_id_tercero, ";
				$sql1 .= "				FF.tercero_id ";
				$sql1 .= "FROM 		facturas_externas FF ";
				$sql1 .= "WHERE 	FF.empresa_id = '".$_SESSION['NotasAjuste']['empresa']."' ";
				$sql1 .= "AND 		FF.estado <> '2'::bpchar ";
				$sql1 .= "AND			FF.prefijo = '".$this->Prefijo."' ";
				$sql1 .= "AND			FF.factura_fiscal = ".$this->FacturaFiscal." ";
				
				if(!$rst = $this->ConexionBaseDatos($sql1))
					return false;
				
				if(!$rst->EOF)
				{				
					$this->TerceroTipoId = $rst->fields[0];
					$this->TerceroDocumento = $rst->fields[1];
					$rst->MoveNext();
				}
				$rst->Close();
			}
						
			if(($this->Prefijo && $this->FacturaFiscal != "" && $this->TerceroDocumento) || empty($this->Prefijo))
			{
				$sql .= "SELECT T.tipo_id_tercero,"; 
				$sql .= "				T.tercero_id, ";
				$sql .= "				T.nombre_tercero ";			
				$where .= "FROM terceros T, ";
				$where .= "			(SELECT DISTINCT FF.tipo_id_tercero, ";
				$where .= "		 					FF.tercero_id  ";
				$where .= "			 FROM 	fac_facturas FF ";
				$where .= "			 WHERE 	FF.sw_clase_factura = '1'::bpchar  ";
				$where .= "			 AND 		FF.empresa_id = '".$_SESSION['NotasAjuste']['empresa']."'  ";
				$where .= "			 AND 		FF.estado = '0'::bpchar  ";
				if($this->Prefijo && $this->FacturaFiscal != "")
				{
					$where .= "			 AND		FF.prefijo = '".$this->Prefijo."' ";
					$where .= "			 AND		FF.factura_fiscal = ".$this->FacturaFiscal." ";
				}
				$where .= "			 UNION DISTINCT ";
				$where .= "			 SELECT DISTINCT FF.tipo_id_tercero, ";
				$where .= "			 				FF.tercero_id ";
				$where .= "			 FROM 	facturas_externas FF ";
				$where .= "			 WHERE 	FF.empresa_id = '".$_SESSION['NotasAjuste']['empresa']."' ";
				$where .= "			 AND 		FF.estado <> '2'::bpchar ";
				if($this->Prefijo && $this->FacturaFiscal != "")
				{
					$where .= "			 AND		FF.prefijo = '".$this->Prefijo."' ";
					$where .= "			 AND		FF.factura_fiscal = ".$this->FacturaFiscal." ";
				}
				$where .= "			) AS F ";
				$where .= "WHERE F.tipo_id_tercero = T.tipo_id_tercero ";
				$where .= "AND 	 F.tercero_id = T.tercero_id "; 
				
				if($this->TerceroDocumento != "")
				{
					$where .= "AND T.tercero_id = '".$this->TerceroDocumento."' ";
				}
				if($this->TerceroNombre != "")
				{
					$where .= "AND T.nombre_tercero ILIKE '%".$this->TerceroNombre."%' ";
				}
				if($this->TerceroTipoId != "0" && $this->TerceroTipoId != "")
				{
					$where .= "AND T.tipo_id_tercero = '".$this->TerceroTipoId."' ";
				}
					
				if(!$_REQUEST['registros'])
				{
					if(!$rst = $this->ConexionBaseDatos($sql.$where)) return false;
					$cont = 0;
					if(!$rst->EOF) $cont = $rst->RecordCount();
					$_REQUEST['registros'] = $cont;
				}
				$this->ProcesarSqlConteo("");
				
				$where .= "ORDER BY 3 ";
				$where .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
				
				$this->Arreglo["registros"] = $this->conteo;
				$this->Arreglo["tercero_id"] = $this->TerceroDocumento;
				$this->Arreglo["tercero_id1"] = $this->TerceroDocumento;
				$this->Arreglo["nombre_tercero"] = $this->TerceroNombre;
				$this->Arreglo["tipo_id_tercero"] = $this->TerceroTipoId;
				
				if($this->Prefijo && $this->FacturaFiscal != "")
				{
					$this->TerceroTipoId = "";
					$this->TerceroDocumento = "";
				}
				
				if(!$rst = $this->ConexionBaseDatos($sql.$where))
					return false;

				while (!$rst->EOF)
				{
					$this->Clientes[]  = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
				$rst->Close();
			}
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CrearNotaAnticipo()
		{
			$this->Tercero_id = $_REQUEST['tercero_id'];
			$this->Tipo_tercero = $_REQUEST['tercero_tipo'];
			$this->Nombre_tercero = $_REQUEST['tercero_nombre'];
			
			if($_REQUEST['nombre_tercero'])
			{
				$_SESSION['NotasAjuste']['nombre_tercero'] = $_REQUEST['nombre_tercero'];
				$_SESSION['NotasAjuste']['tipo_id_tercero'] = $_REQUEST['tipo_id_tercero'];
			}
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes',
																		array('tercero_id'=>$_SESSION['NotasAjuste']['tercero_id'],
																					'nombre_tercero'=>$_SESSION['NotasAjuste']['nombre_tercero'],
																					'tipo_id_tercero'=>$_SESSION['NotasAjuste']['tipo_id_tercero']));
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','CrearNotaAjusteBD',
																		 array("valor_na"=>0,"retorno"=>"1","tercero_id"=>$this->Tercero_id,
																		 			 "tercero_tipo"=>$this->Tipo_tercero));
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerNotasPorAnticipos()
		{			
			$sql .= "SELECT TO_CHAR(NA.fecha_registro,'DD /MM /YYYY') AS fecha,";
			$sql .= "				NA.tmp_nota_id,";			
			$sql .= "				NA.total_nota_ajuste,";
			$sql .= "				COALESCE(ND.valor,0) AS debitos,";
			$sql .= "				COALESCE(NF.valor,0) + COALESCE(NC.valor,0) AS creditos, ";
			$sql .= "				COALESCE(NF.valor,0) AS abonado ";
			$sql .= "FROM		tmp_notas_credito_ajuste NA ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "														tmp_nota_ajuste_id ";
			$sql .= "										FROM		tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "										WHERE 	naturaleza = 'C'";
			$sql .= "										GROUP BY 2 ";
			$sql .= "									) AS NC ";
			$sql .= "				ON(	NC.tmp_nota_ajuste_id = NA.tmp_nota_id ) ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor) AS valor,";
			$sql .= "														tmp_nota_ajuste_id ";
			$sql .= "										FROM	tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "										WHERE 	naturaleza = 'D'";
			$sql .= "										GROUP BY 2 ";
			$sql .= "									) AS ND ";
			$sql .= "				ON(	ND.tmp_nota_ajuste_id = NA.tmp_nota_id ) ";
			$sql .= "				LEFT JOIN ( SELECT	SUM(valor_abonado) AS valor,";
			$sql .= "														tmp_nota_ajuste_id ";
			$sql .= "										FROM	tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "										GROUP BY 2 ";
			$sql .= "									) AS NF ";
			$sql .= "				ON(NF.tmp_nota_ajuste_id = NA.tmp_nota_id ) ";
			$sql .= "WHERE	NA.empresa_id ='".$_SESSION['NotasAjuste']['empresa']."' ";
			$sql .= "AND		NA.tercero_id ='".$this->Tercero_id."' ";
			$sql .= "AND		NA.tipo_id_tercero = '".$this->Tipo_tercero."' ";
			$sql .= "AND		NA.usuario_id = ".UserGetUID()." ";
			$sql .= "ORDER BY 2 ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$i=0;
      while(!$rst->EOF)
      {
      	$notas[$i] = $rst->GetRowAssoc($ToUpper = false);
      	$arreglo = array("tmp_id"=>$notas[$i]['tmp_nota_id'],"valorNota"=>$notas[$i]['total_nota_ajuste'],
      									 "tercero_nombre"=>$this->Nombre_tercero,"tercero_id"=>$this->Tercero_id,
      									 "tercero_tipo"=>$this->Tipo_tercero);
      									 
				$this->action6[] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','EliminarNota',$arreglo);
				
				$arreglo['retorno'] = "FormaCrearNotaAnticipo";
				$this->action4[] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaAdicionarConceptos',$arreglo);
				
				$arreglo['abonado'] = $notas[$i]['abonado'];
				$arreglo['diferencia'] = $notas[$i]['debitos']-$notas[$i]['creditos'];
				$this->action5[] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','CerrarNotaAjuste',array("datos"=>$arreglo));
      	$rst->MoveNext();
      	$i++;
      }
            
      $rst->Close();			

			return $notas;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CruzarNotasAnticipos()
		{
			$this->TmpId = $_REQUEST['tmp_id'];
			$this->TerceroId = $_REQUEST['tercero_id'];
			$this->TerceroTipo = $_REQUEST['tercero_tipo'];
			$this->TerceroNombre = $_REQUEST['tercero_nombre'];
			
			$arreglo = array("tercero_id"=>$this->TerceroId,"tercero_tipo"=>$this->TerceroTipo,
								 			 "tercero_nombre"=>$this->TerceroNombre);
			
			$arreglo['tmp_id'] = $this->TmpId; 
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaAdicionarConceptos',$arreglo);
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCruzarNotasAnticipos',$arreglo);
			$this->action4 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','CruzarFacturasAnticiposBD',$arreglo);
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function EliminarNota()
		{
			$this->Tercero_id = $_REQUEST['tercero_id'];
			$this->Tipo_tercero = $_REQUEST['tercero_tipo'];
			$arreglo =  array("tercero_id"=>$this->Tercero_id,"tercero_tipo"=>$this->Tipo_tercero,
												"tercero_nombre"=>$_REQUEST['tercero_nombre'],"tmp_id"=>$_REQUEST['tmp_id']);
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','EliminarNotaBD',$arreglo);
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaAnticipo',$arreglo);
			
			$informacion .= "ESTA SEGURO DE QUE DESEA ELIMINAR LA NOTA POR ANTICIPOS DE LA EMPRESA ".$_REQUEST['tercero_nombre']."?";
				
			$this->FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function EliminarNotaBD()
		{
			$this->Tmp_id = $_REQUEST['tmp_id'];
			$this->Tercero_id = $_REQUEST['tercero_id'];
			$this->Tipo_tercero = $_REQUEST['tercero_tipo'];
			
			$sql  = "DELETE FROM tmp_notas_credito_ajuste ";
			$sql .= "WHERE	tipo_id_tercero = '".$this->Tipo_tercero."' ";
			$sql .= "AND		tercero_id ='".$this->Tercero_id."' ";
			$sql .= "AND		usuario_id = ".UserGetUID()." ";
			$sql .= "AND		tmp_nota_id = ".$this->Tmp_id." ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$this->FormaCrearNotaAnticipo();
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CruzarFacturasAnticiposBD()
		{
			$this->TmpId = $_REQUEST['tmp_id'];
			$this->Valor = $_REQUEST['valorpago'];
			$this->ValorS = $_REQUEST['valorsug'];
			$this->Facturas = $_REQUEST['facturas'];
			$this->ValorNota = $_REQUEST['tmpValorNota'];
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$bool = false;
			for($i=0; $i<sizeof($this->Valor); $i++)
			{
				if($this->Valor[$i] != "")
				{
					if(!is_numeric($this->Valor[$i]))
					{
						$this->frmError['MensajeError'] = "PARA LA FACTURA ".$this->Facturas[$i]." EL VALOR INGRESADO NO ES VALIDO ";
						$bool = false;
						break;
					}
					if($this->Valor[$i] > $this->ValorS[$i])
					{
						$this->frmError['MensajeError'] = "PARA LA FACTURA ".$this->Facturas[$i]." NO SE PUEDE INGRESAR UN VALOR MAYOR QUE EL SUGERIDO ";
						$bool = false;
						break;
					}
					
					$fac = explode("-",$this->Facturas[$i]);
					$abono .= "<li>".$this->Facturas[$i];
			
					$sql .= "INSERT INTO tmp_notas_credito_ajuste_detalle_facturas( ";
					$sql .= "		empresa_id,";
					$sql .= "		prefijo_factura,";
					$sql .= "		factura_fiscal,";
					$sql .= "		valor_abonado,";
					$sql .= "		tmp_nota_ajuste_id)";
					$sql .= "VALUES (";	
					$sql .= "		'".$empresa."',";
					$sql .= "		'".$fac[0]."',";
					$sql .= "		 ".$fac[1].",";
					$sql .= "		 ".$this->Valor[$i].",";
					$sql .= "		 ".$this->TmpId." ";					
					$sql .= "		);";
					
					$bool = true;
				}
			}
			
			if($bool)
			{
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				$informacion = "LAS SIGUIENTES FACTURAS SE FUERON INCLUIDAS EN LA NOTA POR ANTICIPOS: <menu>".$abono."</menu>";
				unset($this->Valor);
			}
			
			$this->FormaCruzarNotasAnticipos();
			return true;
		}
		/************************************************************************************
		* Funcion donde se obtienen los departamentos, de la base de dartos
		* 
		* @return array
		*************************************************************************************/
		function ObtenerDepartamentos()
		{
			$nd = new NotasDebito();
			$departamentos = $nd->ObtenerDepartamentos($_SESSION['NotasAjuste']['empresa']);
			
			return $departamentos;			
		}
		/************************************************************************************
		* Funcion donde se crean las variables para mostrar la confirmacion de la eliminacion 
		* de la nota de ajuste
		*
		* @return boolean
		*************************************************************************************/
		function EliminarNotaAjuste()
		{
			$datos = $_REQUEST['datos'];
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','EliminarNotaAjusteBD',array("datos"=>$datos));
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaAjuste',$datos);
			
			$informacion .= "<center>ESTA SEGURO DE QUE DESEA ELIMINAR LA NOTA DE AJUSTE ?</center>";
				
			$this->FormaInformacion($informacion);
			return true;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function EliminarNotaAjusteBD()
		{
			$datos = $_REQUEST['datos'];
			$sql .= "DELETE FROM tmp_notas_credito_ajuste_detalle_conceptos ";
			$sql .= "WHERE	tmp_nota_ajuste_id  = ".$datos['tmp_id']."; ";
			$sql .= "DELETE FROM tmp_notas_credito_ajuste_detalle_facturas ";
			$sql .= "WHERE	tmp_nota_ajuste_id = ".$datos['tmp_id']."; ";
			$sql .= "DELETE FROM tmp_notas_credito_ajuste ";
			$sql .= "WHERE	tmp_nota_id = ".$datos['tmp_id']."; ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$_REQUEST = $datos;
			
			$this->Parametro = "Informacion";
			$this->frmError['Informacion'] = "LA NOTA DE AJUSTE SE HA ELIMINADO";
			
			$this->FormaCrearNotaAjuste();
			return true;
		}
		/***
		*
		**/
		function ModificarInformacion()
		{
			$datos = $_REQUEST['datos'];
			if(empty($this->Notas)) $this->ObtenerInformacionNota();
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaAdicionarConceptos',$datos);
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','ModificarInformacionNota',array("datos"=>$datos));
		}
		/***
		*
		**/
		function ObtenerInformacionNota()
		{
			$datos = $_REQUEST['datos'];
			$sql .= "SELECT	observacion ";
			$sql .= "FROM		tmp_notas_credito_ajuste "; 
			$sql .= "WHERE	tmp_nota_id = ".$datos['tmp_id']." ";
			$sql .= "AND		empresa_id = '".$_SESSION['NotasAjuste']['empresa']."' ";		
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			if(!$rst->EOF)
			{
				$this->Notas =  $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
		}
		/***
		*
		**/
		function BuscarTerceros()
		{	
			$this->Doc = $_REQUEST['tipo_id_tercero'];
			$this->TerceroNombre = $_REQUEST['nombre_tercero'];
			$this->TerceroDocumento = $_REQUEST['tercero_id'];
			$this->action3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaBuscarTerceros');
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaBuscarTerceros',
																		array("tipo_id_tercero"=>$this->Doc,
																					"nombre_tercero"=>$this->TerceroNombre,"tercero_id"=>$this->TerceroDocumento));
		}
		/***
		*
		**/
		function ObtenerTerceros()
		{
			$sql .= "SELECT 	T.tipo_id_tercero,"; 
			$sql .= "					T.tercero_id, ";
			$sql .= "					T.nombre_tercero ";			
			$where .= "FROM 	terceros T ";
			$where .= "WHERE	tercero_id IS NOT NULL ";
			
			if($this->Doc != "" && $this->Doc != '0')
			{
				$where .= "AND T.tipo_id_tercero = '".$this->Doc."' ";
			}
			if($this->TerceroNombre != "")
			{
				$where .= "AND T.nombre_tercero ILIKE '%".$this->TerceroNombre."%' ";
			}
			if($this->TerceroDocumento != "")
			{
				$where .= "AND T.tercero_id = '".$this->TerceroDocumento."' ";
			}
				
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where");
			
			$where .= "ORDER BY 3 ";
			$where .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
						
			if(!$rst = $this->ConexionBaseDatos($sql.$where))
				return false;

			while (!$rst->EOF)
			{
				$terceros[]  = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $terceros;
		}
		/************************************************************************************ 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*************************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
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
			
			if(!$_REQUEST['registros'])
			{
        if($consulta != "")
        {
          if(!$rst = $this->ConexionBaseDatos($consulta))
            return false;
	
          if(!$rst->EOF)
          {
            $this->conteo = $rst->fields[0];
            $rst->MoveNext();
          }
          $rst->Close();
        }
			}
			else
			{
				$this->conteo = $_REQUEST['registros'];
			}
			return true;
		}
		/************************************************************************************
		* Funcion donde se crean las variables de para la funcion FormaCrearNotasDebito
		*************************************************************************************/
		function CrearNotasDebito()
		{
			$dat = array();
			$rq = $_REQUEST;
			if($this->datos['nombre_tercero'])
			{
				$dat = array('tercero_id'=>$this->datos['tercero_id'],'nombre_tercero'=>$this->datos['nombre_tercero'],
										 'tipo_id_tercero'=>$this->datos['tipo_id_tercero']);
			}
			
			if(!SessionIsSetVar("DatosTercero"))
				SessionSetVar("DatosTercero",$_REQUEST);
			
			$this->datos = SessionGetVar("DatosTercero");
			SessionSetVar("rutaimag",GetThemePath());
			
			if($rq['tercero_id'])
				SessionSetVar("Tercero",array("tercero_tipo"=>$rq['tercero_tipo'],"tercero_id"=>$rq['tercero_id']));
			
			$td = SessionGetVar("Tercero");

			$nd = new NotasDebito();
			$this->Empresa = $_SESSION['NotasAjuste']['empresa'];
			$this->Notas = $nd->ObtenerNotasPorAnticipos(UserGetUID(),$this->datos,$this->Empresa,"D");	
				
			$this->Prefijos = $nd->ObtenerPrefijosDebito($this->Empresa,$td['tercero_tipo'],$td['tercero_id']);

			$this->Auditores = $nd->ObtenerAuditoresInternos();
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes',$dat);
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaAdicionarConceptos
		***********************************************************************************/
		function CrearCuerpoNotas()
		{	
			if($_REQUEST['tmp_id'])
				SessionSetVar("TmpIdentificador",$_REQUEST['tmp_id']);
			
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$this->datos = SessionGetVar("DatosTercero");
			
			$nd = new NotasDebito();
			$this->Conceptos = $nd->ObtenerConceptos('C',$empresa);
			$this->Deptnos = $nd->ObtenerDepartamentos($empresa);
			$this->AConceptos = $nd->ObtenerConceptosAdicionados(SessionGetVar("TmpIdentificador"),$empresa);
			$this->Nota = $nd->ObtenerInformacionNotaDebito(SessionGetVar("TmpIdentificador"),$empresa);
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaDebito');
			$this->action7 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaBuscarTerceros');
		}
		/************************************************************************************
		* Funcion donde se crean las variables de para la funcion FormaCrearNotasCredito
		*************************************************************************************/
		function CrearNotaCredito()
		{
			$dat = array();
			$rq = $_REQUEST;
			if($this->datos['nombre_tercero'])
			{
				$dat = array('tercero_id'=>$this->datos['tercero_id'],'nombre_tercero'=>$this->datos['nombre_tercero'],
										 'tipo_id_tercero'=>$this->datos['tipo_id_tercero']);
			}
			
			if(!SessionIsSetVar("DatosTercero"))
				SessionSetVar("DatosTercero",$_REQUEST);
			
			$this->datos = SessionGetVar("DatosTercero");
			SessionSetVar("rutaimag",GetThemePath());
			
			if($rq['tercero_id'])
				SessionSetVar("Tercero",array("tercero_tipo"=>$rq['tercero_tipo'],"tercero_id"=>$rq['tercero_id']));
			
			$td = SessionGetVar("Tercero");
			$nc = new NotasCredito();
			$this->Empresa = $_SESSION['NotasAjuste']['empresa'];
			$this->Notas = $nc->ObtenerNotasCreditos(UserGetUID(),$this->datos,$this->Empresa);	
			$this->Prefijos = $nc->ObtenerPrefijosCredito($this->Empresa,$td['tercero_tipo'],$td['tercero_id']);
			$this->Auditores = $nc->ObtenerAuditoresInternos();
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarClientes',$dat);
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaCrearCuerpoNotasCredito
		***********************************************************************************/
		function CrearCuerpoNotasCredito()
		{	
			if($_REQUEST['tmp_id'])
				SessionSetVar("TmpIdentificador",$_REQUEST['tmp_id']);
			
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$this->datos = SessionGetVar("DatosTercero");
			$this->saldo = $_REQUEST['saldo'];
			
			$nc = new NotasCredito();
			$this->Conceptos = $nc->ObtenerConceptos('D',$empresa);
			$this->Deptnos = $nc->ObtenerDepartamentos($empresa);
			$this->AConceptos = $nc->ObtenerConceptosAdicionados(SessionGetVar("TmpIdentificador"),$empresa);
			$this->Nota = $nc->ObtenerInformacionNotaCredito(SessionGetVar("TmpIdentificador"),$empresa);
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotaCredito');
			$this->action7 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaBuscarTerceros');
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaConsultarNotasCredito
		***********************************************************************************/
		function ConsultarNotasCredito()
		{			
			$this->rqs = $_REQUEST;
			$this->Empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$nd = new NotasDebito();			
			$pst['Nota'] = $this->rqs['Nota'];
			$pst['Numero'] = $this->rqs['Numero'];
			$pst['Prefijo'] = $this->rqs['Prefijo'];
			
			$nc = new NotasCredito();			
			$this->Notas = $nc->ObtenerNotasCreditoCerrada($this->Empresa,$this->rqs['offset'],$pst);
			$this->Prefijos = $nc->ObtenerPrefijos($this->Empresa);

			if($nc->conteo > 0)
				$this->Motivos = $nc->ObtenerMotivosAnulacion();
						
			$this->conteo = $nc->conteo;
			$this->paginaActual = $nc->paginaActual;
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaSubmenuPrincipal');
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarNotasCredito',$pst);
			$this->action3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarNotasCredito');
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaConsultarNotasDebito
		***********************************************************************************/
		function ConsultarNotasDebito()
		{			
			$this->rqs = $_REQUEST;
			$this->Empresa = $_SESSION['NotasAjuste']['empresa'];
		
			$nd = new NotasDebito();			
			$pst['Nota'] = $this->rqs['Nota'];
			$pst['Numero'] = $this->rqs['Numero'];
			$pst['Prefijo'] = $this->rqs['Prefijo'];
			
			
			$this->Notas = $nd->ObtenerNotasDebitoCerrada($this->Empresa,$this->rqs['offset'],$pst);
			$this->Prefijos = $nd->ObtenerPrefijos($this->Empresa);
			
			if($nd->conteo > 0)
				$this->Motivos = $nd->ObtenerMotivosAnulacion();
						
			$this->conteo = $nd->conteo;
			$this->paginaActual = $nd->paginaActual;
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaSubmenuPrincipal');
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarNotasDebito',$pst);
			$this->action3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaConsultarNotasDebito');
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaConsultarNotasDebito
		***********************************************************************************/
		function ConsultarNotasCreditoAjuste()
		{			
			IncludeClass('NotasAjuste','','app','FacturacionNotaCreditoAjuste');
			
			$this->rqs = $_REQUEST;
			$this->Empresa = $_SESSION['NotasAjuste']['empresa'];
		
			$na = new NotasAjuste();			
			$pst['Nota'] = $this->rqs['Nota'];
			$pst['Numero'] = $this->rqs['Numero'];
			$pst['Prefijo'] = $this->rqs['Prefijo'];
			
			
			$this->Notas = $na->ObtenerNotasDeAjuste($this->Empresa,$this->rqs['offset'],$pst);
			$this->Prefijos = $na->ObtenerPrefijos($this->Empresa);
									
			$this->conteo = $na->conteo;
			$this->paginaActual = $na->paginaActual;
			
			$this->action1 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaSubmenuPrincipal');
			$this->action2 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaInformacionNotasAjuste',$pst);
			$this->action3 = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaInformacionNotasAjuste');
		}
		/************************************************************************************
		* Funcion donde se crean las variables de para la funcion FormaCrearNotasCredito
		*************************************************************************************/
		function CrearNotasAjusteExterna()
		{
			$dat = array();
			$this->request = $_REQUEST;			
			
			$td = SessionGetVar("Tercero");
			$nc = new NotasCredito();
			$this->Empresa = $_SESSION['NotasAjuste']['empresa'];
			$this->Notas = $nc->ObtenerNotasDeAjuste($this->Empresa,UserGetUID());	
			$this->Prefijos = $nc->ObtenerPrefijosCreditoExternos($this->Empresa);
			$this->Auditores = $nc->ObtenerAuditoresInternos();
			$this->action['volver'] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaSubmenuPrincipal');
			$this->action['conceptos'] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaAdicionarConceptosExternos');
		}
		/**********************************************************************************
		* Funcion donde se crean las variables que se usan en la funcion 
		* FormaAdicionarConceptos
		***********************************************************************************/
		function AdicionarConceptosExternos()
		{
			$this->request = $_REQUEST;
			
			$this->empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$nc = new NotasCredito();
			$this->conceptos = $nc->ObtenerConceptos('D',$this->empresa);
			$this->Deptnos = $nc->ObtenerDepartamentos($this->empresa);
			$this->cnp = $nc->ObtenerConceptosExternosAdicionados($this->request['tmp_id'],$this->empresa);
			$this->Nota = $nc->ObtenerNotasDeAjuste($this->empresa,UserGetUID(),$this->request['tmp_id']);				
 					 
			$this->action['volver'] = ModuloGetURL('app','FacturacionNotaCreditoAjuste','user','FormaCrearNotasAjusteExterna');
		}
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
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
				return false;
			}
			return $rst;
		}
		/*************************************************************************************
		* Funcion donde se evalua si un afecha es correcta o no 
		* 
		* @param $fecha dato a evaluar
		* @return boolean 
		**************************************************************************************/
		function ValidarFecha($fecha)
		{		
			$f = explode("-",$fecha); 
			
			$resultado = checkdate($f[1],$f[2],$f[0]);
			if($resultado != 1 || sizeof($f) != 3)
			{
				$this->frmError["MensajeError"] = "EL FORMATO DE FECHA ES INCORRECTO ";
				return false;
			}
			return true;
		}
	}
?>