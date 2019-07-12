<?php
  /******************************************************************************
  * $Id: app_Facturacion_Conceptos.class.php,v 1.5 2010/11/18 14:18:05 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.5 $ 
	* 
	* @autor Carlo A. Henao 
  * Proposito del Archivo:	Manejo logico de las facturas concepto desde caja
  ********************************************************************************/
	class app_Facturacion_Conceptos
	{
		function app_Facturacion_Conceptos(){}

    //METODO PARA CONSULTAR LOS USUARIOS DE LA CAJA CONCEPTOS
    function TraerUsuariosConceptos()
    {
        $query = "SELECT    a.usuario_id,
                                            a.sw_credito,
                                            a.sw_contado
                            FROM cajas_usuarios_conceptos as a,
                                    userpermisos_cajas_rapidas as b
                            WHERE a.usuario_id=".UserGetUID()."
                                AND a.caja_id=b.caja_id
                                AND a.usuario_id=b.usuario_id;";
			if(!$result = $this->ConexionBaseDatos($query))
				return false;
        $var= $result->GetRowAssoc($ToUpper = false);
        $result->Close();
        return $var;
    }
    //FIN METODO PARA CONSULTAR LOS USUARIOS DE LA CAJA CONCEPTOS

  function DatosEncabezadoEmpresa($empresa)
  {
      $query = "select *
                from empresas as b
                where  b.empresa_id='".$empresa."'";
			if(!$result = $this->ConexionBaseDatos($query))
				return false;
      $var=$result->GetRowAssoc($ToUpper = false);
      $result->Close();
      return $var;
  }
		/**********************************************************************************
		* Funcion donde se evalua si el usuario que esta accediendo al modulo tiene o no
		* permisos
		* 
		* @return boolean Indica si tiene permisos true, o no false
		***********************************************************************************/
		function ObtenerPermisosFacturacion()
		{
			$sql .= "SELECT EM.razon_social, ";
			$sql .= "				EM.empresa_id ";
			$sql .= "FROM 	userpermisos_facturacion UF, ";
			$sql .= "				empresas EM ";
			$sql .= "WHERE 	UF.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UF.empresa_id = EM.empresa_id ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$empresas = array();
			
			while (!$rst->EOF)
			{
				$empresas[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			
			return $empresas;
		}
		/**********************************************************************************
		* Funcion donde se seleccionan los tipos de documentos de la base de datos, 
		* su descripcion el documento asignado y el prefijo asociado
		*
		* @params	char $empresa Empresa relacionada a los documentos
		* @params char $tipodc	Tipo de documento que servira como filtro
		* @return array datos de los documentos
		***********************************************************************************/
		function ObtenerTiposDocumentos($empresa,$tipodc)
		{
			$doc = "";
			$datos = array();
			
			$sql .= "SELECT DC.documento_id, ";
			$sql .= "				DC.descripcion ";
			$sql .= "FROM 	userpermisos_facturacion UF, ";
			$sql .= "				documentos DC ";
			$sql .= "WHERE 	UF.usuario_id = ".UserGetUID()." ";
			$sql .= "AND		UF.empresa_id = '".$empresa."' ";
			$sql .= "AND		DC.empresa_id = UF.empresa_id ";
			$sql .= "AND		DC.documento_id = UF.documento_id ";
			$sql .= "AND		DC.sw_estado IN ('0','1') ";
			$sql .= "ORDER BY 2 ";
						
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			$i=0;
			$todos = "";
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				
				if($doc != $datos[$rst->fields[1]]['descripcion'] )
				{
					if($i > 0)
					{
						$cadena = trim($cadena);
						$cadena = str_replace(" ",",",$cadena);
						$datos[$doc]['documento_id'] = $cadena;
						$todos .= $cadena." ";
						$cadena = "";
					}
					$doc = $rst->fields[1];
				}
				$cadena .= $rst->fields[0]." ";					
				$rst->MoveNext();
				$i++;
		  }
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
			$datos[$doc]['documento_id'] = $cadena;
			
			$todos .= $cadena;
			$todos = trim($todos);
			$todos = str_replace(" ",",",$todos);
			//$datos["TODAS LAS FACTURAS CON PERMISOS"]['documento_id'] = $todos;
			//$datos["TODAS LAS FACTURAS CON PERMISOS"]['descripcion'] = "TODAS LAS FACTURAS CON PERMISOS";
			
			$rst->Close();
			return $datos;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function DatosFactura($tipos,$emp)
		{
			$sql .= "SELECT PF.prefijo_fac_contado,  ";
			$sql .= "				PF.prefijo_fac_credito,  ";
			$sql .= "				PF.punto_facturacion_id ";
      $sql .= "FROM	 	puntos_facturacion PF ";
      $sql .= "WHERE	PF.empresa_id = '".$emp."' ";
			if($tipos)
				$sql .= "AND		PF.prefijo_fac_contado IN (".$tipos.") ";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$facturacion = array();
			while (!$rst->EOF)
			{
				if($tipos)
					$facturacion = $rst->GetRowAssoc($ToUpper = false);
				else
					$facturacion[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}			
			$rst->Close();
			
			if(sizeof($facturacion) == 0)
			{
				$sql  = "SELECT PF.prefijo_fac_credito, ";
				$sql .= "				PF.prefijo_fac_contado, ";
				$sql .= "				PF.punto_facturacion_id ";
	      $sql .= "FROM	 	puntos_facturacion PF ";
	      $sql .= "WHERE	PF.empresa_id = '".$emp."' ";
				if($tipos)
					$sql .= "AND		PF.prefijo_fac_credito IN (".$tipos.") ";
		
				if(!$rst = $this->ConexionBaseDatos($sql))
					return false;
				
				while (!$rst->EOF)
				{
	        if($tipos)
						$facturacion = $rst->GetRowAssoc($ToUpper = false);
					else
						$facturacion[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
	      }
				
				$rst->Close();
			}
			return $facturacion;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerPrefijosFacturas($emp)
		{
			$sql = "SELECT DISTINCT a.prefijo ";
			$sql .= "FROM 	documentos a, ";
			$sql .= "fac_facturas b LEFT JOIN fac_facturas_contado c ON ";
			$sql .= "(b.prefijo = c.prefijo ";
			$sql .= "AND b.factura_fiscal = c.factura_fiscal), ";
			$sql .= "cajas_rapidas e, ";
			$sql .= "fac_facturas_conceptos d ";
			$sql .= "WHERE a.documento_id = b.documento_id ";
			$sql .= "AND b.prefijo = d.prefijo ";
			$sql .= "AND b.factura_fiscal = d.factura_fiscal ";
			$sql .= "AND d.caja_id = e.caja_id ";
			$sql .= "AND a.empresa_id = '".$emp."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$prefijo = array();
			while (!$rst->EOF)
			{
				$prefijo[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			return $prefijo;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ObtenerFacturasXPrefijo($datos,$emp)
		{
			$sql  = "SELECT DISTINCT D.factura_fiscal, ";
			$sql .= "				D.prefijo, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				F.nombre, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				D.empresa_id, ";
			$sql .= "				C.nombre_tercero, ";
			$sql .= "				D.tipo_factura, ";
			$sql .= "				C.tipo_id_tercero ||' '|| C.tercero_id AS identificacion, ";
			$sql .= "				D.sw_clase_factura, ";
			$sql .= "				D.total_factura, ";
			$sql .= "				I.total_efectivo, ";
			$sql .= "				I.total_tarjetas, ";
			$sql .= "				I.total_cheques ";
			$sql .= "FROM 	terceros C, ";
			$sql .= "				fac_facturas D LEFT JOIN fac_facturas_contado I ON ";
			$sql .= "				(D.empresa_id = I.empresa_id ";
			$sql .= "				AND D.prefijo = I.prefijo ";
			$sql .= "				AND D.factura_fiscal = I.factura_fiscal), ";
			$sql .= "				fac_facturas_conceptos E, ";
			$sql .= "				system_usuarios F, ";
			$sql .= "				cajas_rapidas as G, ";
			$sql .= "				departamentos as H ";
			$sql .= "WHERE 	D.estado = '0' ";
			$sql .= "AND 		E.caja_id = G.caja_id ";
			$sql .= "AND		G.departamento = H.departamento ";
			$sql .= "AND 		D.prefijo = E.prefijo ";
			$sql .= "AND 		D.factura_fiscal = E.factura_fiscal ";
			$sql .= "AND		D.usuario_id = F.usuario_id ";
			$sql .= "AND		D.tipo_id_tercero = C.tipo_id_tercero ";
			$sql .= "AND		D.tercero_id = C.tercero_id ";
			$sql .= "AND		D.prefijo = '".$datos['PrefijoFac']."' ";
			$sql .= "AND		D.factura_fiscal = ".$datos['Factura']." ";
			$sql .= "AND		D.empresa_id = '".$emp."' ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function TraerDatosEnvio($FechaI,$FechaF,$prefijo,$numero,$emp,$plan)
		{
			$terceros = explode(',',$plan);
			if($FechaI != "")
			{
				$arr = explode("/",$FechaI);
				$where .= "AND DATE(D.fecha_registro) >= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
			}
			if($FechaF != "")
			{
				$arr = explode("/",$FechaF);
				$where .= "AND DATE(D.fecha_registro) <= '".$arr[2]."-".$arr[1]."-".$arr[0]."' ";
			}
			if($numero != "")
			{
				$where .= "AND D.factura_fiscal = $numero";
			}
			if($prefijo != "")
			{
				$where .= "AND D.prefijo = '$prefijo'";
			}
			$sql  = "SELECT DISTINCT D.factura_fiscal, ";
			$sql .= "				D.prefijo, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				F.nombre, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				D.empresa_id, ";
			$sql .= "				C.nombre_tercero, ";
			$sql .= "				D.tipo_factura, ";
			$sql .= "				C.tipo_id_tercero ||' '|| C.tercero_id AS identificacion, ";
			$sql .= "				D.sw_clase_factura, ";
			$sql .= "				D.total_factura, ";
			$sql .= "				I.total_efectivo, ";
			$sql .= "				I.total_tarjetas, ";
			$sql .= "				I.total_cheques ";
			$sql .= "FROM 	terceros C, ";
			$sql .= "				fac_facturas D LEFT JOIN fac_facturas_contado I ON ";
			$sql .= "				(D.empresa_id = I.empresa_id ";
			$sql .= "				AND D.prefijo = I.prefijo ";
			$sql .= "				AND D.factura_fiscal = I.factura_fiscal), ";
			$sql .= "				fac_facturas_conceptos E, ";
			$sql .= "				system_usuarios F, ";
			$sql .= "				cajas_rapidas as G, ";
			$sql .= "				departamentos as H ";
			$sql .= "WHERE 	D.estado = '0' ";
			$sql .= "AND 		E.caja_id = G.caja_id ";
			$sql .= "AND		G.departamento = H.departamento ";
			$sql .= "AND 		D.prefijo = E.prefijo ";
			$sql .= "AND 		D.factura_fiscal = E.factura_fiscal ";
			$sql .= "AND		D.usuario_id = F.usuario_id ";
			$sql .= "AND		D.tipo_id_tercero = C.tipo_id_tercero ";
			$sql .= "AND		D.tercero_id = C.tercero_id ";
			//$sql .= "AND		D.prefijo = '".$datos['PrefijoFac']."' ";
			//$sql .= "AND		D.factura_fiscal = ".$datos['Factura']." ";
			$sql .= "AND		D.empresa_id = '".$emp."' ";
			$sql .= "AND		C.tipo_id_tercero = '$terceros[0]' ";
			$sql .= "AND		C.tercero_id = '$terceros[1]' ";
			$sql .= " $where ";
			$sql .= "EXCEPT ";
			$sql .= "SELECT DISTINCT D.factura_fiscal, ";
			$sql .= "				D.prefijo, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				F.nombre, ";
			$sql .= "				D.fecha_registro, ";
			$sql .= "				D.empresa_id, ";
			$sql .= "				C.nombre_tercero, ";
			$sql .= "				D.tipo_factura, ";
			$sql .= "				C.tipo_id_tercero ||' '|| C.tercero_id AS identificacion, ";
			$sql .= "				D.sw_clase_factura, ";
			$sql .= "				D.total_factura, ";
			$sql .= "				I.total_efectivo, ";
			$sql .= "				I.total_tarjetas, ";
			$sql .= "				I.total_cheques ";
			$sql .= "FROM 	terceros C, ";
			$sql .= "				fac_facturas D LEFT JOIN fac_facturas_contado I ON ";
			$sql .= "				(D.empresa_id = I.empresa_id ";
			$sql .= "				AND D.prefijo = I.prefijo ";
			$sql .= "				AND D.factura_fiscal = I.factura_fiscal), ";
			$sql .= "				fac_facturas_conceptos E, ";
			$sql .= "				system_usuarios F, ";
			$sql .= "				cajas_rapidas as G, ";
			$sql .= "				departamentos as H, ";
			$sql .= "				envios_detalle as ED ";
			$sql .= "WHERE 	D.estado = '0' ";
			$sql .= "AND 		E.caja_id = G.caja_id ";
			$sql .= "AND		G.departamento = H.departamento ";
			$sql .= "AND 		D.prefijo = E.prefijo ";
			$sql .= "AND 		D.factura_fiscal = E.factura_fiscal ";
			$sql .= "AND		D.usuario_id = F.usuario_id ";
			$sql .= "AND		D.tipo_id_tercero = C.tipo_id_tercero ";
			$sql .= "AND		D.tercero_id = C.tercero_id ";
			$sql .= "AND		D.empresa_id = '".$emp."' ";
			$sql .= "AND		C.tipo_id_tercero = '$terceros[0]' ";
			$sql .= "AND		C.tercero_id = '$terceros[1]' ";
			$sql .= "AND		D.prefijo = ED.prefijo ";
			$sql .= "AND 		D.factura_fiscal = ED.factura_fiscal ";
			$sql .= " $where ";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}

		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerFacturasXTerceroId($datos,$emp,$tipos,$offset,$cant,$empresa)
		{
			$sql = "					SELECT 	FF.tipo_factura, ";
			$sql .= "									FF.empresa_id,";
			$sql .= "									FF.fecha_registro, ";
			$sql .= "									FF.prefijo, ";
			$sql .= "									FF.factura_fiscal, ";
			$sql .= "									FF.usuario_id, ";
			$sql .= "									F.nombre, ";
			$sql .= "									C.nombre_tercero, ";
			$sql .= "									C.tipo_id_tercero ||' '|| C.tercero_id AS identificacion, ";
			$sql .= "									FF.sw_clase_factura  ";
			$sql .= "					FROM		fac_facturas FF,";
			$sql .= "									fac_facturas_conceptos FC,";
			$sql .= "									system_usuarios F, ";
			$sql .= "									cajas_rapidas as G, ";
			$sql .= "									terceros C, ";
			$sql .= "									departamentos as H ";
			$sql .= "					WHERE		FF.estado = '0' ";
			$sql .= "					AND			FF.tipo_id_tercero = '".$datos['TipoDocumentoTercero']."' "; 
			$sql .= "					AND			FF.tercero_id = '".$datos['DocumentoTercero']."' ";
			$sql .= "					AND			FF.empresa_id = '".$empresa."' ";
			$sql .= "					AND 		FC.prefijo = FF.prefijo ";
			$sql .= "					AND 		FC.factura_fiscal = FF.factura_fiscal ";
			$sql .= "					AND 		FC.empresa_id = FF.empresa_id ";
			$sql .= "					AND			FF.usuario_id = F.usuario_id ";
			$sql .= "					AND 		FC.caja_id = G.caja_id ";
			$sql .= "					AND			G.departamento = H.departamento ";
			$sql .= "					AND			FF.tipo_id_tercero = C.tipo_id_tercero ";
			$sql .= "					AND			FF.tercero_id = C.tercero_id ";

			if($cant <= 0)
			{
				$cant = 0;
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;
				if(!$rst->EOF) $cant = $rst->RecordCount();
			}
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant);
			
			if(!$offset) $offset = 0;
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$offset;
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerFacturasTerceros($datos,$emp,$tipos,$offset)
		{
			$pref = $this->ObtenerPrefijosFacturas($emp);
			foreach($pref as $key => $pre)
				$cadena .= "'".$pre['prefijo']."' ";
			
			$cadena = trim($cadena);
			$cadena = str_replace(" ",",",$cadena);
						
			$sql .= "SELECT DISTINCT TE.nombre_tercero, ";
			$sql .= "				TO_CHAR(FF.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha,";
			$sql .= "				FF.prefijo,";
			$sql .= "				FF.factura_fiscal ";
			$sql .= "FROM		terceros TE, ";
			$sql .= "				fac_facturas FF, ";
			$sql .= "				fac_facturas_conceptos FFC ";
			$sql .= "WHERE	FF.tipo_id_tercero = TE.tipo_id_tercero ";
			$sql .= "AND 		FF.tercero_id = TE.tercero_id ";
			$sql .= "AND 		FF.prefijo IN (".$cadena.") ";
			$sql .= "AND 		FF.prefijo = FFC.prefijo ";
			$sql .= "AND 		FF.factura_fiscal = FFC.factura_fiscal ";
			
			if($datos['nombre_tercero'])
				$sql .= "AND 		TE.nombre_tercero ILIKE '%".$datos['nombre_tercero']."%' ";
			
			if($datos['tipo_id_tercero'] != '0' && $datos['tercero_id'])
			{
				$sql .= "AND		FF.tipo_id_tercero = '".$datos['tipo_id_tercero']."' ";
				$sql .= "AND 		FF.tercero_id = '".$datos['tercero_id']."' ";
			}
						
			$this->ProcesarSqlConteo("SELECT COUNT(*) FROM ($sql) AS A",$cant,$offset);
			
			$sql .= "ORDER BY TE.nombre_tercero ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function DetalleFacturaConceptos($empresa,$prefijo,$factura)
		{
			$query = "SELECT a.*,
									a.concepto,c.descripcion
								FROM fac_facturas_conceptos as a,
									fac_facturas_conceptos_dc as b, conceptos_caja_conceptos as c
								WHERE a.empresa_id='".$empresa."'
								AND a.prefijo='".$prefijo."'
								AND a.factura_fiscal=".$factura."
								AND a.fac_factura_concepto_id=b.fac_factura_concepto_id
								AND b.concepto_id=c.concepto_id
								AND b.grupo_concepto=c.grupo_concepto
								AND b.empresa_id=c.empresa_id";
			if(!$result = $this->ConexionBaseDatos($query)) return false;
				while(!$result->EOF)
				{
					$var[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				return $var;
		}
		/********************************************************************************
		*
		*********************************************************************************/

		function ObternerTiposIdTerceros()
		{
			$sql = "SELECT * FROM tipo_id_terceros ORDER BY indice_de_orden";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$vars = array();
			while (!$rst->EOF) 
			{
				$vars[$rst->fields[0]] = $rst->fields[1];
				$rst->MoveNext();
			}
			$rst->Close();
			return $vars;
		}
  /**
  *
  */
  function Terceros($empresa)
  {
      $query = "select distinct a.tipo_id_tercero, a.tercero_id, a.nombre_tercero
                from  terceros as a,
                      fac_facturas as b,
                      fac_facturas_conceptos as c
                where b.empresa_id='".$empresa."'
                and a.tipo_id_tercero=b.tipo_id_tercero
                and a.tercero_id=b.tercero_id
                and b.empresa_id=c.empresa_id
                and b.prefijo=c.prefijo
                and b.factura_fiscal=c.factura_fiscal
                order by a.nombre_tercero";
			if(!$result = $this->ConexionBaseDatos($query)) return false;
      while(!$result->EOF)
      {
              $var[]=$result->GetRowAssoc($ToUpper = false);
              $result->MoveNext();
      }

      $result->Close();
      return $var;
  }
  /**
  *
  */
  function DatosHacerEnvio($empresa,$plan,$f_envio,$datos,$enviod)
  {
			$tercero = explode(',',$plan); // 0 tipo_doc_tercero - 1 documento_tercero
			$fecha_inicial=$this->FormatoFecha($enviod[datos][FechaI]);
			$fecha_final=$this->FormatoFecha($enviod[datos][FechaF]);
			$query="SELECT nextval('envios_envio_id_seq')";
			if(!$result = $this->ConexionBaseDatos($query)) return false;
			$envio=$result->fields[0];
			$query = "INSERT INTO envios (
														envio_id,
														fecha_inicial,
														fecha_final,
														fecha_radicacion,
														departamento,
														usuario_id,
														fecha_registro,
														sw_estado,
														fecha_registro_sistema)
								VALUES( $envio,
											'".$fecha_inicial."',
											'".$fecha_final."',
											NULL,
											NULL,
											".UserGetUID().",
											'".$f_envio."',
											'0',
											now());";
			if(!$result = $this->ConexionBaseDatos($query)) return false;

      $i=0;
      foreach($enviod as $k => $v)
      {
          if(substr_count($k,'Envio'))
          {
                if( $i % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}

                //0 prefijo 1 factura 2 tipoid y paciente 3 nombre
                //4 total 5 plan 6 plan_des 7empresa 8 centro
                $x=explode('||',$v);
                                if(!isset($arr[$envio]))
                                {
                                        $arr[$envio]["envio_id"] = $envio;
                                        $arr[$envio]["total_envio"] = 0;//Sumatoria del total de las facturas
                                }
                                $arr[$envio]['total_envio'] += $x[2];
                                $arr[$envio]['cantidad_facturas']++;
                                //Se cargan en el vertor con un indice para determinar en el momento
                                //de la impresion si el envio contiene varios planes, tipo_planes o terceros
                                $arr[$envio]['print'][$i]["tipo_tercero_id"] = $tercero[0];
                                $arr[$envio]['print'][$i]["tercero_id"] = $tercero[1];

/*                $query = "select envio_id,plan_id from envios_planes
                          where plan_id='$x[5]' and envio_id=$envio";
									if(!$result = $this->ConexionBaseDatos($query)) return false;*/
                //if($results->EOF)
                //{
								//	$paso=0;
								//echo	$query = "INSERT INTO envios_planes (
								//																																							envio_id,
								//																																							plan_id)
								//																									VALUES($envio,'$x[5]')";
								//	if(!$result = $this->ConexionBaseDatos($query)) return false;*/
                //}
								//else
								//{  //ya esta en envios
								//	$envio=$results->fields[0];
								//}
               //exit;
									$query = "select * from envios_detalle
														where envio_id=$envio
															and factura_fiscal='$x[1]'
															and prefijo='$x[0]'";//empresa_id='$x[3]'
								if(!$result = $this->ConexionBaseDatos($query)) return false;
                if($result->EOF)
                {
                   $query = "INSERT INTO envios_detalle (
                                          envio_id,
                                          prefijo,
                                          factura_fiscal,
                                          empresa_id)
                              VALUES($envio,'$x[0]','$x[1]','$x[3]')";
										if(!$result = $this->ConexionBaseDatos($query)) return false;
                }
          }
                $i++;
      }//fin foreach
      return $arr;
  }
        //formato fecha
        function FormatoFecha($f)
        {
            if($f)
            {
                $fecha = explode('/',$f);
                $cad = $fecha[2]."-".$fecha[1]."-".$fecha[0];
                return $cad;
            }
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
		function ProcesarSqlConteo($consulta,$num_reg = null,$offset=null,$limite=null)
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
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$num_reg)
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
				$this->conteo = $num_reg;
			}
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