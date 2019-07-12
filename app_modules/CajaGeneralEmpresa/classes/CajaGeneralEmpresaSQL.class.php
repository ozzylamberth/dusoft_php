<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: CajaGeneralEmpresaSQL.class.php,v 1.26 2010/01/26 22:40:38 sandra Exp $Revision: 1.26 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class CajaGeneralEmpresaSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function CajaGeneralEmpresaSQL(){}
 
  /**
    * Funcion donde se consulta los permisos del usuario
    * @return array $datos vector que contiene la informacion del usuario
    */
	  function BuscarPermisosUser()
    {
    
      //$this->debug=true;
      $usuario=UserGetUID();
      $query="SELECT      a.caja_id,b.descripcion FROM cajas_usuarios a , cajas as b
                            WHERE a.usuario_id=$usuario
                            AND a.caja_id=b.caja_id
                           ;";
                          if(!$rst = $this->ConexionBaseDatos($query))
                          return false;
                          $datos = array();
                          while(!$rst->EOF)
                          {
                          $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
                          $rst->MoveNext();
                          }
                          $rst->Close();
                          return $datos;
    }
  /**
    * Funcion donde se consulta los datos basicos del tercero a quien se le realiza la venta
    * @return array $datos vector que contiene la informacion 
    */
    function  DatosBasicosTercero($tipo_id_tercero,$tercero_id)
    {
       // $this->debug=true;
        $sql = "  SELECT tipo_id_tercero,
                       tercero_id,
                       tipo_pais_id,
                       tipo_dpto_id,
                       tipo_mpio_id,
                       direccion,
                       telefono,
                       fax,
                       nombre_tercero
                FROM   terceros
                WHERE  tipo_id_tercero= '".$tipo_id_tercero."' 
                AND    tercero_id= '".$tercero_id."' ";
                if(!$rst = $this->ConexionBaseDatos($sql))
                return false;
                $datos = array();
                while(!$rst->EOF)
                {
                $datos[] = $rst->GetRowAssoc($ToUpper = false);
                $rst->MoveNext();
                }
                $rst->Close();
                return $datos;
    }
  /**
    * Funcion donde se consulta los datos del documento real de venta
    * @return array $datos vector que contiene la informacion 
    */
		function consultarInformacionDocumentoReal($empresa,$prefijo,$numero)
    {
       //$this->debug=true;
       $sql = " SELECT      d.movimiento_id,
                            d.empresa_id,
                            d.prefijo,
                            d.numero,
                            d.centro_utilidad,
                            d.bodega,
                            d.codigo_producto,
                            d.cantidad,
                            d.porcentaje_gravamen,
                            d.total_costo,
                            d.fecha_vencimiento,
                            d.lote,
                            p.descripcion,
                            p.cantidad as presen,
                            u.descripcion as unidad,
                            s.descripcion as molecula,
                            c.descripcion as laboratorio
                          
                 FROM       inv_bodegas_movimiento_d d,
                            existencias_bodegas x,
                            inventarios i,
                            inventarios_productos p,
                            unidades u,
                            inv_subclases_inventarios s,
                            inv_clases_inventarios c
                 WHERE      d.empresa_id=x.empresa_id
                 and        d.centro_utilidad=x.centro_utilidad
                 and        d.bodega=x.bodega
                 and        d.codigo_producto=x.codigo_producto
                 and        x.empresa_id=i.empresa_id
                 and        x.codigo_producto=i.codigo_producto
                 and        i.codigo_producto=p.codigo_producto
                 and        p.unidad_id=u.unidad_id
                 and        p.grupo_id=s.grupo_id
                 and        p.clase_id=s.clase_id
                 and        p.subclase_id=s.subclase_id
                 and        p.grupo_id=c.grupo_id
                 and        p.clase_id=c.clase_id 
                 and        d.empresa_id='".$empresa."'
                 and        d.prefijo='".$prefijo."'
                 and        d.numero= ".$numero." ";
 
         		           if(!$rst = $this->ConexionBaseDatos($sql))
                          return false;
                          $datos = array();
                          while(!$rst->EOF)
                          {
                          $datos[] = $rst->GetRowAssoc($ToUpper = false);
                          $rst->MoveNext();
                          }
                          $rst->Close();
                          return $datos;
    }
  /**
    * Funcion donde se consulta el prefijo del recibo de caja
    * @return array $datos vector que contiene la informacion 
    */

    function ConsultarPrefijoReciboCaja($documento_id)
    {
        //$this->debug=true;
        $sql = " SELECT documento_id,
                        empresa_id,
                        tipo_doc_general_id,
                        prefijo,
                        numeracion
                FROM    documentos 
                WHERE   documento_id = '".$documento_id."' ";
       
         		             if(!$rst = $this->ConexionBaseDatos($sql))
                          return false;
                          $datos = array();
                          while(!$rst->EOF)
                          {
                          $datos[] = $rst->GetRowAssoc($ToUpper = false);
                          $rst->MoveNext();
                          }
                          $rst->Close();
                          return $datos;
   
	}
  /**
    * Funcion donde se consulta las entidades del banco
    * @return array $datos vector que contiene la informacion 
    */
      function ComboEntidadConfirma()
      {
    
        $busca = "  SELECT    entidad_confirma,descripcion
                    FROM      confirmacion_entidades
                    ORDER BY  entidad_confirma";
  			   		           if(!$rst = $this->ConexionBaseDatos($busca))
                            return false;
                            $datos = array();
                            while(!$rst->EOF)
                            {
                            $datos[] = $rst->GetRowAssoc($ToUpper = false);
                            $rst->MoveNext();
                            }
                            $rst->Close();
                            return $datos;
  
      }
  /**
    * Funcion donde se  Ingresa la confirmacion del cheque
    * @return boolean de acuerdo a la ejecucion del sql.
    */
  
    function IngresarConfirmacionCheque($entidad_confirma,$funcionario_confirma,$numero_confirmacion,$fecha)
    {
        //$this->debug=true;
       
        $fdatos=explode("-", $fecha);
        $fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];
        $this->ConexionTransaccion();
        $query = "INSERT INTO tmp_confirmacion_che
                            (cheque_mov_id,
                             entidad_confirma,
                             funcionario_confirma,
                             numero_confirmacion,
                             fecha,
                             usuario_id,
                             consecutivo,
                             numerodecuenta
                             )VALUES
                            (
            				NEXTVAL('tmp_confirmacion_che_consecutivo_seq'),
            				'".$entidad_confirma."',
            				'".$funcionario_confirma."',
            				'".$numero_confirmacion."',
            				'".$fedatos."',
            				 ".UserGetUID().",
            				 NEXTVAL('tmp_confirmacion_che_indice_automatico_seq'),
            				0
            				)";
              if(!$rst1 = $this->ConexionTransaccion($query))
              {
                return false;
              }
              $this->Commit();
              return true;
  	}
  /**
    * Funcion donde se consulta el ultimo consecutivo de  la confirmacion del  cheque
    * @return array $datos vector que contiene la informacion 
    */ 
    function SeleccionarMaxtmp_confirmacion_che()
    {
			//$this->debug=true;
     	$sql = "SELECT (COALESCE(MAX(consecutivo),0)) AS numero FROM tmp_confirmacion_che;	";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
	 /**
    * Funcion donde se  Ingresa los movimientos del cheque temporal
    * @return boolean de acuerdo a la ejecucion del sql.
    */
    function IngresarMovimientoCheque($empresa,$banco,$cheque,$girador,$fechacheque,$total,$fechatrans,$ctac,$recibo_caja,$prefijo,$centro_utilidad)
   {	   
        //$this->debug=true;
        $fdatos=explode("-", $fechacheque);
        $fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];
		    $fdat=explode("-", $fechatrans);
        $fedat= $fdat[2]."/".$fdat[1]."/".$fdat[0];
        $this->ConexionTransaccion();
        $sql = "INSERT INTO cheques_mov_Empresa(
													cheque_mov_eid,
													empresa_id,
													centro_utilidad,
													recibo_caja,
													prefijo,
													banco,
													cheque,
													girador,
													fecha_cheque,
													total,
													fecha,
													estado,
													usuario_id,
													fecha_registro,
													cta_cte,
													sw_postfechado
												)
										VALUES(
													nextval('cheques_mov_empresa_cheque_mov_eid_seq'),
													'".$empresa."',
													'".$centro_utilidad."',
													".$recibo_caja.",
													'".$prefijo."',
													'".$banco."',
                                                    '".$cheque."',
													'".$girador."',
													'".$fedatos."',
													'".$total."',
													'".$fedat."',
													0,
													".UserGetUID().",
													 now(),
													 '".$ctac."',
													0
													)";		
											
			    if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
    }
   /**
    * Funcion donde se consulta la informacion del cheque confirmado
    * @return array $datos vector que contiene la informacion 
    */ 
		function seleccionarInformacionConfirmacion($consecutivo)
		{
	
      $sql = "  SELECT  	  consecutivo,
                            cheque_mov_id,
                            entidad_confirma,
                            funcionario_confirma,
                            numero_confirmacion,
                            fecha,
                            usuario_id,
                            numerodecuenta
        				 FROM       tmp_confirmacion_che
        				 WHERE      consecutivo = ".$consecutivo."  ";
				 
				 if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	
	}
  /**
    * Funcion donde se  Ingresa los movimientos  de la tarjeta debito
    * @return boolean de acuerdo a la ejecucion del sql.
    */
	
	function InsertarTarjetaDebito($empresa_id,$centro_utilidad,$recibo_caja,$prefijo,$autorizacion,$tarjetat,$tarjeta_numero,$valor)
	{
	
		//$this->debug=true;//;
        $this->ConexionTransaccion();
		$sql =" INSERT INTO tarjetas_mov_debito_Empresa(
													tarjeta_mov_db_eid,
													empresa_id,
													centro_utilidad,
													recibo_caja,
													prefijo,
													autorizacion,
													tarjeta,
													total,
													tarjeta_numero,
													estado
													
												)
										VALUES(
												     nextval('tarjetas_mov_debito_empresa_tarjeta_mov_db_eid_seq'),
													'".$empresa_id."',
													'".$centro_utilidad."',
													".$recibo_caja.",
													'".$prefijo."',
													'".$autorizacion."',
													'".$tarjetat."',
													".$valor.",
													'".$tarjeta_numero."',
                                                    0
													
												)";		
											
			    if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
	}
	 /**
    * Funcion donde se  Ingresa los movimientos del cheque real
    * @return boolean de acuerdo a la ejecucion del sql.
    */
	  Function ingresarMovimientoChequeR($cheque_mov_id,$entidad_confirma,$funcionario_confirma,$numero_confirmacion)
    {
	
      	//$this->debug=true;
        $this->ConexionTransaccion();
        $sql =" INSERT INTO confirmacion_che(
													consecutivo,
													cheque_mov_id,
													entidad_confirma,
													funcionario_confirma,
													numero_confirmacion,
													fecha,
													usuario_id
													
												)
										VALUES(
													nextval('confirmacion_che_consecutivo_seq'),
													'".$cheque_mov_id."',
													'".$entidad_confirma."',
													'".$funcionario_confirma."',
													'".$numero_confirmacion."',
                                                    now(),
													".UserGetUID()."
												)";		
											
          if(!$rst1 = $this->ConexionTransaccion($sql))
          {
          return false;
          }
          $this->Commit();
          return true;
	  }
	/**
    * Funcion donde se elimina la  informacion  temporal del movimiento del cheque
    * @return array $datos vector que contiene la informacion 
    */ 
    function EliminarTemp($tmp_confirmacion_che)
    {
    
      $sql =" delete from  tmp_confirmacion_che
	                where  cheque_mov_id=".$tmp_confirmacion_che." ";
	  
	  
			   		           if(!$rst = $this->ConexionBaseDatos($sql))
                          return false;
                          $datos = array();
                          while(!$rst->EOF)
                          {
                          $datos[] = $rst->GetRowAssoc($ToUpper = false);
                          $rst->MoveNext();
                          }
                          $rst->Close();
                          return $datos;
     }
   /**
      * Funcion donde se elimina la  informacion  de tarjeta de credito
       * @return boolean de acuerdo a la ejecucion del sql.
      */ 
    function InsertarTarjetaCredito($tarjetat,$empresa_id,$centro_utilidad,$recibo_caja,$prefijo,$autorizacion,$socio,$autorizado_por,$total,$tarjeta_numero,$fecha_expira,$fecha)
    {
			    //$this->debug=true;
          $fdatos=explode("-", $fecha);
          $fedatos= $fdatos[2]."/".$fdatos[1]."/".$fdatos[0];
          $fdat=explode("-", $fecha_expira);
          $fedat= $fdat[2]."/".$fdat[1]."/".$fdat[0];
          $this->ConexionTransaccion();
         	$sql = " INSERT INTO tarjetas_mov_credito_empresa(
      													tarjeta_mov_db_eid,
      													tarjeta,
      													empresa_id,
      													centro_utilidad,
      													recibo_caja,
      													prefijo,
      													fecha,
      													autorizacion,
      													socio,
      													fecha_expira,
      													autorizado_por,
      													total,
      													usuario_id,
      													fecha_registro,
      													tarjeta_numero,
      													estado
      											
												)
										VALUES(
    												    nextval('tarjetas_mov_credito_empresa_tarjeta_mov_db_eid_seq'),
    													'".$tarjetat."',
    													'".$empresa_id."',
    													'".$centro_utilidad."',
    													".$recibo_caja.",
    													'".$prefijo."',
    													'".$fedatos."',
    													'".$autorizacion."',
    													'".$socio."',
    													'".$fedat."',
    													'".$autorizado_por."',
    													".$total.",
    													".UserGetUID().",
    													now(),
    													'".$tarjeta_numero."',
                                                        0
													
												)";		
											
			    if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
		   	return true;
    }
  /**
      * Funcion donde se selecciona la caja
      * @return array $datos vector que contiene la informacion 
      */ 
	 function Seleccionarcaja()
	 {
		  //$this->debug=true;
       
		  $sql = "SELECT (COALESCE(MAX(recibocajaem_id),0)) AS reciboid FROM recibocaja_empresa;	";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
  /**
      * Funcion donde se selecciona la tarjetas
      * @return array $datos vector que contiene la informacion 
      */ 
	
    function SeleccionarTarjeta()
    {
		    $query = " SELECT tarjeta,descripcion,comision,cuotas_maxima,sw_tipo FROM tarjetas
								    WHERE sw_estado = '1'";
	
	     	if(!$rst = $this->ConexionBaseDatos($query))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
	/**
      * Funcion donde se insertan los recibos de caja generados 
        * @return boolean de acuerdo a la ejecucion del sql.
      */ 
  
    function InsertarRecibocaja($empresa_id,$prefijo,$total_pago,$total_efectivo,$total_cheques,$total_tarjetas,$tipo_id_tercero,$tercero_id,$documento_id,$prefijo_doc,$numero_doc,$caja_id,$doc)
    {
    		//$this->debug=true;
    		$this->ConexionTransaccion();
    		$sql = " INSERT INTO  ReciboCaja_Empresa (
                							recibocajaem_id,	
                							empresa_id,
                							prefijo,
                							caja_id,
                							fecha_ingcaja,
                							total_pago,
                							total_efectivo,
                							total_cheques,
                							total_tarjetas,
                							tipo_id_tercero,
                							tercero_id,
                							estado,
                							documento_id,
                							prefijo_doc,
                							numero_doc,
                							usuario_id,
                							fecha_registro
    						)
    						VALUES
    						(
            							NEXTVAL('recibocaja_empresa_recibocajaem_id_seq'),
            							 '".$empresa_id."',
            							'".$prefijo."',
                          ".$caja_id.",
            							now(),
            							".$total_pago.",
            							".$total_efectivo.",
            							".$total_cheques.",
            							".$total_tarjetas.",
            							'".$tipo_id_tercero."',
            							'".$tercero_id."',
            							0,
            							 '".$documento_id."',
            							'".$prefijo_doc."',
            							 ".$numero_doc.",
            							 ".UserGetUID().",
            							 now()
    							 	
    						);
    					";
                $sql .= "UPDATE documentos ";
                $sql .= "SET numeracion = numeracion + 1 ";
                $sql .= "WHERE documento_id =".$documento_id." ;";
    					     if(!$rst1 = $this->ConexionTransaccion($sql))
    				{
    				return false;
    				}
    				$this->Commit();
    			return true;
      
		}
  /**
      * Funcion donde se insertan la factura de contado
        * @return boolean de acuerdo a la ejecucion del sql.
      */ 
		function InsertarFacturaContado($empresa_id,$centro_utilidad,$factura_fiscal,$prefijo,$total_abono,$total_efectivo,$total_cheques,$total_tarjetas,$tipo_id_tercero,$tercero_id,$caja_id,$documento_id,$recibocajaem_id)
		{
			//$this->debug=true;
			$this->ConexionTransaccion();
			$sql = " INSERT INTO  fac_facturas_contado (
                            empresa_id,
                            centro_utilidad,
                            factura_fiscal,
                            prefijo,
                            total_abono,
                            total_efectivo,
                            total_cheques,
                            total_tarjetas,
                            tipo_id_tercero,
                            tercero_id,
                            estado,
                            fecha_registro,
                            usuario_id,
                            caja_id,
                            cierre_caja_id,
                            total_bonos,
                             recibocajaem_id
                          )
                          VALUES
                          (
                            '".$empresa_id."',
                            '".$centro_utilidad."',
                            ".$factura_fiscal.",
                            '".$prefijo."',
                            ".$total_abono.",
                            ".$total_efectivo.",
                            ".$total_cheques.",
                            ".$total_tarjetas.",
                            '".$tipo_id_tercero."',
                            '".$tercero_id."',
                            0,
                            now(),
                             ".UserGetUID().",
                              ".$caja_id.",
                              null,
                             ".$total_abono.",
                              ".$recibocajaem_id."
                                        
                          );
					";
					
      			$sql .= "UPDATE documentos ";
      			$sql .= "SET numeracion = numeracion + 1 ";
      			$sql .= "WHERE documento_id =".$documento_id." ;";
    				if(!$rst1 = $this->ConexionTransaccion($sql))
    				{
    				return false;
    				}
    				$this->Commit();
            return true;
  	}
  /**
      * Funcion donde se selecciona el ultimo recibo generado
     * @return array $datos vector que contiene la informacion 
      */ 
  	function SeleccionarMaxRecibo()
    {
			//$this->debug=true;
       
			$sql = "SELECT (COALESCE(MAX(recibocajaem_id),0)) AS numero FROM recibocaja_empresa ;	";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
    }
  /**
      * Funcion donde se  consulta los datos de la empresa
        * @return array $datos vector que contiene la informacion 
      */ 
	 function ConsDatosEmpresa($empresa) 
      {
         //$this->debug=true;
        $sql  = "SELECT ep.empresa_id, ep.tipo_id_tercero,  ep.id, ep.razon_social, ep.representante_legal, ep.direccion, ep.telefonos, ep.fax, ep.email, ep.codigo_sgsss, ep.tipo_pais_id, ep.tipo_dpto_id, ep.tipo_mpio_id, tm.municipio, td.departamento ";
        $sql .= "FROM   empresas ep, tipo_mpios tm, tipo_dptos td ";
        $sql .= "WHERE  ep.tipo_dpto_id = tm.tipo_dpto_id  ";
        $sql .= "and    ep.tipo_mpio_id = tm.tipo_mpio_id ";
        $sql .= "and    ep.tipo_dpto_id= td.tipo_dpto_id ";
        $sql .= "and    ep.empresa_id = '".$empresa."' ";

        if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        if(!$rst->EOF)
        {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
      }
  /**
       * Funcion donde se  consulta la informacion  de la factura de contado
       * @return array $datos vector que contiene la informacion 
      */ 
	  function ConsultarDatosDelaFactura($empresa,$centro,$factura,$prefijo)
	  {
	     $sql = " SELECT 	empresa_id,
          							centro_utilidad,
          							factura_fiscal,
          							prefijo,
          							total_abono,
          							total_efectivo,
          							total_cheques,
          							total_tarjetas,
          							tipo_id_tercero,
          							tercero_id,
          							estado
        				FROM 		fac_facturas_contado
        				WHERE 	empresa_id = '".$empresa."'
        				AND     centro_utilidad= '".$centro."'
        				AND 		factura_fiscal = ".$factura." 
        				AND 		prefijo = '".$prefijo."' 
        				and         estado=0 ";
	  
	    if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        if(!$rst->EOF)
        {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	  }
    /**
      * Funcion donde se consulta la informacion del usuario actual
        * @return array $datos vector que contiene la informacion 
      */ 
	  
	  function consultarDatosUsuarioActual()
	  {
	  
        $sql = " SELECT usuario_id,
                        usuario,
                        nombre,
                        descripcion
        				FROM 	system_usuarios
        				WHERE 	usuario_id =  ".UserGetUID().";";
	      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        $datos = array();
        if(!$rst->EOF)
        {
        $datos = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
	  	  
	  }
	  /**
      * Funcion donde se consulta el detalle del movimiento 
     * @return array $datos vector que contiene la informacion 
      */ 
	  function ConsultarDtosDetalleMovimiento($empresa,$prefijo,$numero,$centro,$bodega)
	  {
	     //$this->debug=true;
	  
      		$sql = " SELECT d.movimiento_id,
              						d.empresa_id,
              						d.prefijo,
              						d.numero,
              						d.centro_utilidad,
              						d.bodega,
              						d.codigo_producto,
              						d.cantidad,
              						d.porcentaje_gravamen,
              						d.total_costo,
              						d.fecha_vencimiento,
              						d.lote,
              						p.descripcion,
              						p.cantidad as prese,
              						u.descripcion as unidad,
              						s.descripcion as molecula,
              						c.descripcion as laboratorio,
									p.contenido_unidad_venta
              FROM      	inv_bodegas_movimiento_d d,
                          existencias_bodegas x,
                          inventarios i,
                          inventarios_productos p,
              						unidades u,
              						inv_subclases_inventarios s,
              						inv_clases_inventarios c
      				WHERE 	d.empresa_id=x.empresa_id
      				and     d.centro_utilidad=x.centro_utilidad
      				and     d.bodega=x.bodega
      				and     d.codigo_producto=x.codigo_producto
      				and     x.empresa_id=i.empresa_id
      				and     x.codigo_producto=i.codigo_producto
      				and     i.codigo_producto=p.codigo_producto
      				and     p.unidad_id=u.unidad_id
      				and     p.grupo_id=s.grupo_id
      				and     p.clase_id=s.clase_id
      				and     p.subclase_id=s.subclase_id
      				and     s.grupo_id=c.grupo_id
      				and     s.clase_id=c.clase_id
      				and  	d.empresa_id = '".$empresa."'
      				and     d.prefijo = '".$prefijo."'
      				and     d.numero = ".$numero." 
      				and     d.centro_utilidad = '".$centro."'
      				and     d.bodega='".$bodega."' ";
      	  
	   if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
	  }
  /**
     * Funcion donde se consulta el detalle del movimiento 
     * @return array $datos vector que contiene la informacion 
      */ 
  	function contarConsultarDtosDetalleMovimiento($empresa,$prefijo,$numero,$centro,$bodega)
	  {
	     // $this->debug=true;
	  
      		$sql = " SELECT d.movimiento_id,
              						d.empresa_id,
              						d.prefijo,
              						d.numero,
              						d.centro_utilidad,
              						d.bodega,
              						d.codigo_producto,
              						d.cantidad,
              						d.porcentaje_gravamen,
              						d.total_costo,
              						d.fecha_vencimiento,
              						d.lote,
              						p.descripcion,
              						p.cantidad as prese,
              						u.descripcion as unidad,
              						s.descripcion as molecula,
              						c.descripcion as laboratorio
                  FROM 	  inv_bodegas_movimiento_d d,
                          existencias_bodegas x,
                          inventarios i,
                          inventarios_productos p,
                          unidades u,
                          inv_subclases_inventarios s,
                          inv_clases_inventarios c
      				WHERE 	d.empresa_id=x.empresa_id
      				and     d.centro_utilidad=x.centro_utilidad
      				and     d.bodega=x.bodega
      				and     d.codigo_producto=x.codigo_producto
      				and     x.empresa_id=i.empresa_id
      				and     x.codigo_producto=i.codigo_producto
      				and     i.codigo_producto=p.codigo_producto
      				and     p.unidad_id=u.unidad_id
      				and     p.grupo_id=s.grupo_id
      				and     p.clase_id=s.clase_id
      				and     p.subclase_id=s.subclase_id
      				and     s.grupo_id=c.grupo_id
      				and     s.clase_id=c.clase_id
      				and  	d.empresa_id = '".$empresa."'
      				and     d.prefijo = '".$prefijo."'
      				and     d.numero = ".$numero." 
      				and     d.centro_utilidad = '".$centro."'
      				and     d.bodega='".$bodega."' ";
              $consulta="SELECT COUNT(*)as c FROM(".$sql.") AS A ";
        	   if(!$rst = $this->ConexionBaseDatos($consulta))
              return false;
              $datos = array();
              if(!$rst->EOF)
              {
              $datos = $rst->GetRowAssoc($ToUpper);
              $rst->MoveNext();
              }
              $rst->Close();
              return $datos;
	  	  
    } 
 }
 ?>