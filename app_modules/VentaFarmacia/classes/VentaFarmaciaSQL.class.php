<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: VentaFarmaciaSQL.class.php,v 1.1 2010/06/03 20:43:44 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase : VentaFarmaciaSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sEA.com)
  * @author Hugo F  Manrique
  */
	class VentaFarmaciaSQL extends ConexionBD
	{
  	/*
  	* Constructor de la clase
  	*/
    function VentaFarmaciaSQL(){}
    /**
    * Funcion donde se obtiene el identificador del documento temporal
    *
    * @param integer $documento_id Identificador del documento
    * @param integer $usuario Identificador del usuario
    *
    * @return mixed
    */
    function ObtenerTemporal($documento_id,$usuario)
    {
      $sql  = "SELECT * ";
      $sql .= "FROM   tmp_bodegas_documentos ";
      $sql .= "WHERE  usuario_id = ".$usuario." ";
      $sql .= "AND    bodegas_doc_id = ".$documento_id." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
			
      $datos = array();
			if(!$rst->EOF)
			{
  			$datos = $rst->GetRowAssoc($ToUpper = false);
  			$rst->MoveNext();
			}
			
      $rst->Close();
			return $datos;
    }
    /**
    * Funcion donde se obtiene la informacion de los productos almacenados 
    * temporalmente, de la compra
    *
    * @param integer $documento Identificador del documento
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function ObtenerProductosTemporal($documento,$empresa)
    {
    	//$this->debug=true;
      $sql  = "SELECT TM.consecutivo, ";
      $sql .= "       TM.documento,";
      $sql .= "       TM.codigo_producto,";
      $sql .= "       round(TM.cantidad) AS cantidad, ";
      $sql .= "       round(TM.total_costo) AS total_costo, ";
      $sql .= "       TO_CHAR(TM.fecha_vencimiento,'DD/MM/YYYY') AS fecha_vencimiento, ";
      $sql .= "       TM.lote, ";
      $sql .= "       fc_descripcion_producto(TM.codigo_producto) AS descripcion, ";
      //$sql .= "       IV.descripcion,";
      //$sql .= "       IV.descripcion_abreviada,";
      $sql .= "       SI.descripcion AS molecula,";
      $sql .= "       CI.descripcion AS laboratorio, ";
      $sql .= "       LP.empresa_id ";
      $sql .= "FROM   tmp_bodegas_documentos_d TM, ";
      $sql .= "       inventarios_productos IV, ";
      $sql .= "       inv_subclases_inventarios SI,";
      $sql .= "       inv_clases_inventarios CI, ";
      $sql .= "       listas_precios LP, ";
      $sql .= "       listas_precios_detalle LD ";
      $sql .= "WHERE  TM.documento = ".$documento." ";
      $sql .= "AND    TM.codigo_producto = IV.codigo_producto ";
      $sql .= "AND		IV.grupo_id = SI.grupo_id ";
      $sql .= "AND 		IV.clase_id = SI.clase_id ";
      $sql .= "AND 		IV.subclase_id = SI.subclase_id ";
      $sql .= "AND		SI.grupo_id = CI.grupo_id ";
      $sql .= "AND 		SI.clase_id = CI.clase_id ";
      $sql .= "AND    LP.empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    LP.centro_utilidad = '".$empresa['centro_utilidad']."' ";
      $sql .= "AND    LP.bodega = '".$empresa['bodega']."' ";
      $sql .= "AND    LP.codigo_lista = LD.codigo_lista ";
      $sql .= "AND    LP.empresa_id = LD.empresa_id ";
      $sql .= "AND    LD.codigo_producto = TM.codigo_producto ";
      $sql .= "ORDER BY laboratorio,molecula, IV.descripcion ";
      
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
    * Funcion donde se obtiene la informacion del pais registrado en el sistema
    *
    * @param string $pais Identificador del pais
    *
    * @return mixed
    */
        function ObtenerNombreEmpresa(){
            include '../../../conexion.php';

            $sqlAllDataEmpresa = "
                SELECT
                   t.nombre_tercero AS empresa_nombre
                FROM
                  terceros AS t
                WHERE
                  t.tercero_id = '9005343785'
                AND
                  t.tipo_id_tercero = 'NIT'
            ";
            $query = pg_query($dbconn, $sqlAllDataEmpresa);
            //$nombre_archivo = "logs.txt";
            //$archivo = fopen($nombre_archivo, "a");
            //fwrite($archivo, "Eyyyyy, eyyyy \n");
            //fclose($archivo);
            $empresa = pg_fetch_assoc($query);
            $empresa_nombre = $empresa['empresa_nombre'];

            return $dbconn;
        }

		function ObtenerNombrePais($pais)
		{
			$sql  = "SELECT pais ";
			$sql .= "FROM		tipo_pais ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['pais'];
		}


    /**
    * Funcion donde se obtiene la informacion del deparatmento registrado en el sistema
    *
    * @param string $pais Identificador del pais
    * @param string $dpto Identificador del departamento
    *
    * @return mixed
    */
		function ObtenerNombreDepartamento($pais,$dpto)
		{
			$sql  = "SELECT departamento ";
			$sql .= "FROM		tipo_dptos ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$dpto."' ";
				
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['departamento'];
		}
    /**
    * Funcion donde se obtiene la informacion de la ciudad registrada en el sistema
    *
    * @param string $pais Identificador del pais
    * @param string $dpto Identificador del departamento
    * @param string $mpio Identificador del municipio
    *
    * @return mixed
    */
		function ObtenerNombreCiudad($pais,$dpto,$mpio)
		{
			$sql  = "SELECT municipio ";
			$sql .= "FROM		tipo_mpios ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			$sql .= "AND 		tipo_dpto_id = '".$dpto."' ";
			$sql .= "AND 		tipo_mpio_id = '".$mpio."' ";
		
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['municipio'];
		}
    /**
    * Funcion donde se obtiene la informacion de la comuna registrada en el sistema
    *
    * @param string $pais Identificador del pais
    * @param string $dpto Identificador del departamento
    * @param string $mpio Identificador del municipio
    * @param string $comuna Identificador de la comuna
    *
    * @return mixed
    */
		function ObtenerNombreComuna($pais,$dpto,$mpio,$comuna)
		{
			$sql  = "SELECT	comuna ";
			$sql .= "FROM		tipo_comunas ";
			$sql .= "WHERE 	tipo_pais_id = '".$pais."' ";
			$sql .= "AND		tipo_dpto_id = '".$Dpto."' ";
			$sql .= "AND		tipo_mpio_id = '".$Mpio."' ";
			$sql .= "AND		tipo_comuna_id = '".$comuna."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos['comuna'];
		}
    /**
    * Funcion donde se obtiene la informacion del cliente
    *
    * @param array $form Arreglo de datos con los filtros
    *
    * @return mixed
    */
		function ObtenerDatosCliente($form)
		{
			$sql  = "SELECT	TE.tercero_id,";
			$sql .= " 			TE.tipo_id_tercero,";
			$sql .= " 			TE.nombre_tercero,";
			$sql .= " 			TE.direccion AS direccion,";
			$sql .= " 			TE.telefono,";
			$sql .= " 			TE.celular,";
			$sql .= " 			TE.tipo_pais_id,";
			$sql .= " 			TE.tipo_dpto_id,";
			$sql .= " 			TE.tipo_mpio_id,";
            $sql .= "           TM.municipio, ";
            $sql .= "           TD.departamento, ";
            $sql .= "           TP.pais ";

			$sql .= "FROM		terceros TE, ";
			$sql .= "			tipo_mpios TM, ";
            $sql .= "           tipo_dptos TD, ";
            $sql .= "           tipo_pais TP ";

			$sql .= "WHERE 	    TE.tipo_id_tercero = '".$form['tipo_id_tercero']."' ";
			$sql .= "AND		TE.tercero_id = '".$form['tercero_id']."' ";
            $sql .= "AND        TE.tipo_pais_id = TM.tipo_pais_id ";
			$sql .= "AND        TE.tipo_dpto_id = TM.tipo_dpto_id ";
			$sql .= "AND        TE.tipo_mpio_id = TM.tipo_mpio_id ";
            $sql .= "AND        TM.tipo_pais_id = TD.tipo_pais_id ";
			$sql .= "AND        TM.tipo_dpto_id = TD.tipo_dpto_id ";
            $sql .= "AND        TD.tipo_pais_id = TP.tipo_pais_id ";

 			$sql .= "UNION DISTINCT ";
            $sql .= "SELECT	PA.paciente_id AS tercero_id,";
			$sql .= " 			PA.tipo_id_paciente AS tipo_id_tercero,";
			$sql .= " 			PA.primer_nombre||' '||PA.segundo_nombre||' '||PA.primer_apellido||' '||PA.segundo_apellido AS nombre_tercero,";
			$sql .= " 			PA.residencia_direccion AS direccion,";
			$sql .= " 			PA.residencia_telefono AS telefono,";
            $sql .= "       ' ' AS celular, ";
			$sql .= " 			PA.tipo_pais_id,";
			$sql .= " 			PA.tipo_dpto_id,";
			$sql .= " 			PA.tipo_mpio_id,";
            $sql .= "           TM.municipio, ";
            $sql .= "           TD.departamento, ";
            $sql .= "           TP.pais ";
			$sql .= "FROM		pacientes PA, ";
			$sql .= "			tipo_mpios TM, ";
            $sql .= "           tipo_dptos TD, ";
            $sql .= "           tipo_pais TP ";
			$sql .= "WHERE 	    PA.tipo_id_paciente = '".$form['tipo_id_tercero']."' ";
			$sql .= "AND		PA.paciente_id = '".$form['tercero_id']."' ";
            $sql .= "AND        PA.tipo_pais_id = TM.tipo_pais_id ";
			$sql .= "AND        PA.tipo_dpto_id = TM.tipo_dpto_id ";
			$sql .= "AND        PA.tipo_mpio_id = TM.tipo_mpio_id ";
            $sql .= "AND        TM.tipo_pais_id = TD.tipo_pais_id ";
			$sql .= "AND        TM.tipo_dpto_id = TD.tipo_dpto_id ";
            $sql .= "AND        TD.tipo_pais_id = TP.tipo_pais_id ";
      
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    /**
    * Funcion donde se hace el registro temporal del documento de bodega
    *
    * @param array $form1 Arreglo de datos con la informacion del item a registrar
    * @param array $form2 Arreglo de datos con la informacion del documento
    * @param array $producto Arreglo de datos con la informacion del producto
    *
    * @return mixed
    */
    function IngresarDocumentoTemporal($form1,$form2,$producto,$iva_pdto)
    {
      $this->ConexionTransaccion();
      $documento = $form2['documento'];
      if(!$form2['documento'])
      {
        $sql  = "SELECT NEXTVAL('tmp_bodegas_documentos_documento_seq') AS documento ";
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $indice = $rst->GetRowAssoc($ToUpper = false);
        $documento = $indice['documento'];
        
        $sql  = "INSERT INTO tmp_bodegas_documentos ";
        $sql .= " (";
        $sql .= "   documento,";
        $sql .= " 	fecha,";
        $sql .= " 	total_costo,";
        $sql .= " 	usuario_id,";
        $sql .= " 	fecha_registro,";
        $sql .= " 	bodegas_doc_id ";
        $sql .= " )";
        $sql .= "VALUES ";
        $sql .= " (";
        $sql .= "   ".$documento.", ";
        $sql .= "   NOW(),";
        $sql .= "   0,";
        $sql .= "   ".$form2['usuario_id'].",";
        $sql .= "   NOW(),";
        $sql .= "   ".$form2['bodegas_doc_id']." ";
        $sql .= " )";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $sql  = "INSERT INTO tmp_bodegas_documentos_pagos (documento) ";
        $sql .= "VALUES (".$documento.") ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      
      $sql  = "INSERT INTO tmp_bodegas_documentos_d ";
      $sql .= " (";
      $sql .= "   consecutivo,";
      $sql .= "   documento,";
      $sql .= "   codigo_producto,";
      $sql .= "   cantidad,";
      $sql .= "   total_costo,";
      $sql .= "   bodegas_doc_id,";
      $sql .= "   iva_compra,"; //added
      $sql .= "   lote, ";
      $sql .= "   fecha_vencimiento ";
      $sql .= " )";
      $sql .= "VALUES ";
      $sql .= " (";
      $sql .= "   DEFAULT,";
      $sql .= "   ".$documento.", ";
      $sql .= "  '".$producto[2]."', ";
      $sql .= "   ".$form1['cantidad'][$producto[0]][$producto[1]][$producto[2]].", ";
      //$sql .= "   ".FormatoValor($producto[3],null,false).", ";
      $sql .= "   ".$producto[3].", ";
      $sql .= "   ".$form2['bodegas_doc_id'].", ";
	  //$sql .= "   ".FormatoValor($iva_pdto,null,false).", "; //added
	  $sql .= "    ".$iva_pdto.", "; //added
      $sql .= "  '".$producto[1]."', ";
      $sql .= "  '".$this->DividirFecha($producto[0],"/")."' ";
      $sql .= " )";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
        
      $this->Commit();
      
      return $documento;
    }
    /**
    * Funcion donde se eliminan los temporales de un documento de detalle
    *
    * @param integer $consecutivo Identificador del detalle
    *
    * @return mixed
    */
    function EliminarTemporal($consecutivo)
    {
      $sql  = "DELETE ";
      $sql .= "FROM   tmp_bodegas_documentos_d ";
      $sql .= "WHERE  consecutivo = ".$consecutivo." ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      return true;
    }    
    /**
    * Funcion donde se eliminan los documentos de bodega temporales creados
    *
    * @param integer $documento Identificador del documento
    *
    * @return mixed
    */
    function EliminarDocumentoTemporal($documento)
    {
      $this->ConexionTransaccion();
      
      $sql  = "DELETE FROM tmp_bodegas_documentos_d ";
      $sql .= "WHERE  documento = ".$documento." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;      
      
      $sql  = "DELETE FROM tmp_bodegas_documentos_pagos ";
      $sql .= "WHERE  documento = ".$documento." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $sql  = "DELETE FROM tmp_bodegas_documentos ";
      $sql .= "WHERE  documento = ".$documento." ";

      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
     
      $sql  = "DELETE FROM tmp_cheques_mov_confirmacion ";
      $sql .= "WHERE  documento = ".$documento."; ";
      
      $sql .= "DELETE FROM tmp_cheques_mov_rc ";
      $sql .= "WHERE  documento = ".$documento."; ";
      
      $sql .= "DELETE FROM tmp_tarjetas_mov_debito ";
      $sql .= "WHERE  documento = ".$documento."; ";
      
      $sql .= "DELETE FROM tmp_confirmacion_tar ";
      $sql .= "WHERE  tarjeta_mov_id IN ";
      $sql .= "       ( SELECT tarjeta_mov_id ";
      $sql .= "         FROM   tmp_tarjetas_mov_credito ";
      $sql .= "         WHERE  documento = ".$documento."); ";
      
      $sql .= "DELETE FROM tmp_tarjetas_mov_credito ";
      $sql .= "WHERE  documento = ".$documento."; ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

      $this->Commit();
      return true;
    }
    /**
		* Funcion donde se obtienen las entidades que pueden confirmar pagos de tarjetas o
    * cheques parametrizadas
    *
		* @return mixed 
		*/
		function ObtenerEntidadesConfirma()
		{
			$sql  = "SELECT	entidad_confirma,";
			$sql .= "				descripcion ";
			$sql .= "FROM		confirmacion_entidades ";
			$sql .= "ORDER BY entidad_confirma ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
		* Funcion donde se obtienen los bancos parametrizados en el sistema
    *
		* @return mixed 
		*/
		function ObtenerBancos()
		{
			$sql  = "SELECT	banco,";
			$sql .= "				descripcion ";
			$sql .= "FROM		bancos ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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
    * Funcion donde se obtiene la informcion de las tarjetas parametrizadas en el
    * sistema
    *
    * @return mixed
    */
    function ObtenerTarjetas()
		{
			$sql  = "SELECT	tarjeta,";
			$sql .= "				descripcion,";
			$sql .= "				comision,";
			$sql .= "				cuotas_maxima,";
			$sql .= "				sw_tipo ";
			$sql .= "FROM 	tarjetas ";
			$sql .= "WHERE 	sw_estado = '1' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[4]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
		}
    /**
    * Funcion donde se obtienen los permisos de las cajas
    *
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function ObtenerPermisosCajas($empresa)
    {
      //$this->debug=true;
      $sql  = "SELECT a.caja_id,";
      $sql .= "       b.razon_social, ";
      $sql .= "       e.descripcion, ";
      $sql .= "       d.descripcion AS descripcion3,  ";
      $sql .= "       c.empresa_id,  ";
      $sql .= "       d.prefijo_fac_contado,  ";
      $sql .= "       c.centro_utilidad,  ";
      $sql .= "       d.cuenta_tipo_id, ";
      $sql .= "       d.servicio, ";
      $sql .= "       d.via_ingreso,  ";
      $sql .= "       d.departamento,  ";
      $sql .= "       c.descripcion AS descripcion2, ";
      $sql .= "       CT.tipo_factura ";
      $sql .= "FROM   userpermisos_cajas_rapidas a,  ";
      $sql .= "       empresas b,  ";
      $sql .= "       departamentos c, ";
      $sql .= "       cajas_rapidas d,  ";
      $sql .= "       cuentas_tipos CT,  ";
      $sql .= "       centros_utilidad e ";
      $sql .= "WHERE  a.usuario_id =".$empresa['usuario_id']." ";
      $sql .= "AND    d.departamento = c.departamento ";
     // $sql .= "AND    d.departamento='".$empresa['departamento']."' "; dato no comentado originalmte.
      $sql .= "AND    c.empresa_id = b.empresa_id ";
      $sql .= "AND    a.caja_id = d.caja_id ";
      $sql .= "AND    e.centro_utilidad = c.centro_utilidad ";
      $sql .= "AND    e.empresa_id = c.empresa_id ";
      $sql .= "AND    d.cuenta_tipo_id = CT.cuenta_tipo_id ";
      $sql .= "AND    d.empresa_id = '".$empresa['empresa_id']."' "; //nuevo dato
      $sql .= "AND    c.centro_utilidad = '".$empresa['centro_utilidad']."' "; //nuevo dato
      $sql .= "AND    d.departamento = '".$empresa['bodega']."' "; //nuevo dato
	  
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]][$rst->fields[3]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			return $datos;
    }
    /**
    * Funcion donde se hace el registro temporal del pago con tarjeta credito
    *
    * @param array $datos Arreglo de datos con la informacion del pago
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function IngresarPagoTarjetaCTemporal($datos,$empresa)
    {      
      $fecha = "NULL";
      if($datos['fecha_cheque']) $fecha = "'".$this->DividirFecha($datos['fecha_cheque'])."'::date";
      
      $sql  = "SELECT * ";
      $sql .= "FROM   tmp_tarjetas_mov_credito ";
      $sql .= "WHERE  documento = ".$empresa['documento']." ";
      $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $temp = array();
      if(!$rst->EOF)
			{
				$temp = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      if(!empty($temp))
      {
        $sql  = "DELETE FROM tmp_confirmacion_tar ";
        $sql .= "WHERE  tarjeta_mov_id = ".$temp['tarjeta']."; ";
        $sql .= "DELETE FROM tmp_tarjetas_mov_credito ";
        $sql .= "WHERE  tarjeta = ".$temp['tarjeta']."; ";
      
        if(!$rst = $this->ConexionBaseDatos($sql))
          return false;
      }
      
      $sql = "SELECT NEXTVAL('public.tarjetas_mov_credito_tarjeta_mov_id_seq') AS indice";
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$tarjeta_mov = $rst->fields[0];
      
      $this->ConexionTransaccion();
      
      $sql  = "INSERT INTO tmp_tarjetas_mov_credito";
      $sql .= "   (";
      $sql .= "     tarjeta_mov_id,";
      $sql .= "			tarjeta ,";
      $sql .= "			empresa_id , ";
      $sql .= "			centro_utilidad , ";
      $sql .= "			documento, ";
      $sql .= "     autorizacion,";
      $sql .= "			socio ,";
      $sql .= "			fecha_expira ,";
      $sql .= "			total ,";
      $sql .= "			usuario_id ,";
      $sql .= "			fecha,";
      $sql .= "			fecha_registro,"; 
      $sql .= "			tarjeta_numero ";
      $sql .= "   )";
      $sql .= "VALUES(";
      $sql .= "	    ".$tarjeta_mov.",";
      $sql .= "	   '".$datos['tarjeta']."', ";
      $sql .= "	   '".$empresa['empresa_id']."', ";
      $sql .= "		 '".$empresa['centro_utilidad']."', ";
      $sql .= "		  ".$empresa['documento'].", ";
      $sql .= "	   '".$datos['numero']."',";
      $sql .= "	   '".$datos['socio']."',";
      $sql .= "	   '".$this->DividirFecha($datos['fecha_expiracion'])."'::date, ";
      $sql .= "	    ".$datos['valor'].", ";
      $sql .= "		  ".$empresa['usuario_id'].", ";
      $sql .= "	   '".$this->DividirFecha($datos['fecha_transaccion'])."'::date, ";
      $sql .= "	   	NOW(),";
      $sql .= "	   '".$datos['num_tarjeta']."' ";
      $sql .= "		); ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
        
      $sql  = "INSERT INTO tmp_confirmacion_tar";
			$sql .= "		(	";
      $sql .= "     tarjeta_mov_id,";
			$sql .= "			entidad_confirma,";
			$sql .= "			funcionario_confirma,";
			$sql .= "			numero_confirmacion, ";
			$sql .= "			fecha,";
			$sql .= "			usuario_id,";
			$sql .= "			consecutivo ";
			$sql .= "		)";
			$sql .= "VALUES";
      $sql .= "   ( ";
			$sql .= "			 ".$tarjeta_mov.",";
			$sql .= "			'".$datos['entidad']."',";
			$sql .= "			'".$datos['funcionario']."',";
			$sql .= "			'".$datos['numero']."',";
			$sql .= "			'".$this->DividirFecha($datos['fecha_confirma'])."'::date,";
			$sql .= "			 ".$empresa['usuario_id'].",";
			$sql .= "			 DEFAULT ";
			$sql .= "		) ";

      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
     
      $this->Commit();
      return true;
    }
    /**
    * Funcion donde se obtiene la informacion del pago con tarjeta credito
    *
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function ObtenerInformacionTarjetaCTemp($empresa)
    {      
      $sql  = "SELECT TC.tarjeta ,";
      $sql .= "       TC.autorizacion,";
      $sql .= "			  TC.socio ,";
      $sql .= "			  TO_CHAR(TC.fecha_expira,'DD/MM/YYYY') AS fecha_expira ,";
      $sql .= "			  TC.total ,";
      $sql .= "			  TC.usuario_id ,";
      $sql .= "			  TO_CHAR(TC.fecha,'DD/MM/YYYY') AS fecha,";
      $sql .= "			  TC.tarjeta_numero, ";
			$sql .= "			  TR.entidad_confirma,";
			$sql .= "			  TR.funcionario_confirma,";
			$sql .= "			  TR.numero_confirmacion, ";
			$sql .= "			  TO_CHAR(TR.fecha,'DD/MM/YYYY') AS fecha_confirmacion ";      
      $sql .= "FROM   tmp_tarjetas_mov_credito TC, ";
      $sql .= "       tmp_confirmacion_tar TR ";
      $sql .= "WHERE  TC.documento = ".$empresa['documento']." ";
      $sql .= "AND    TC.empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    TC.centro_utilidad = '".$empresa['centro_utilidad']."' ";
      $sql .= "AND    TC.tarjeta_mov_id = TR.tarjeta_mov_id ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }     
    /**
    * Funcion donde se hace el registro temporal del pago con cheque
    *
    * @param array $datos Arreglo de datos con la informacion del pago
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function IngresarPagoChequeTemporal($datos,$empresa)
    {      
      $fecha = "NULL";
      if($datos['fecha_cheque']) $fecha = "'".$this->DividirFecha($datos['fecha_cheque'])."'::date";
      
      $this->ConexionTransaccion();
      
      $sql  = "DELETE FROM tmp_cheques_mov_confirmacion ";
      $sql .= "WHERE  documento = ".$empresa['documento']."; ";

      $sql .= "DELETE FROM tmp_cheques_mov_rc ";
      $sql .= "WHERE  documento = ".$empresa['documento']." ";
      $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."'; ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $sql  = "SELECT NEXTVAL('tmp_cheques_mov_rc_sq') AS indice ";
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

      $cheque_mov = $rst->fields[0];
        
      $sql  = "INSERT INTO tmp_cheques_mov_rc(";
      $sql .= "		  cheque_mov_id , ";
      $sql .= "		  empresa_id , ";
      $sql .= "		  centro_utilidad , ";
      $sql .= "		  documento, ";
      $sql .= "		  banco , ";
      $sql .= "		  cheque, ";
      $sql .= "		  girador, ";
      $sql .= "		  fecha_cheque, ";
      $sql .= "		  total, ";
      $sql .= "		  fecha , ";
      $sql .= "		  estado, ";
      $sql .= "		  usuario_id, ";	
      $sql .= "		  fecha_registro, ";
      $sql .= "		  cta_cte )";
      $sql .= "VALUES(";
      $sql .= "			".$cheque_mov.",";
      $sql .= "	   '".$empresa['empresa_id']."', ";
      $sql .= "		 '".$empresa['centro_utilidad']."', ";
      $sql .= "		  ".$empresa['documento'].", ";
      $sql .= "	   '".$datos['banco']."',";
      $sql .= "	   '".$datos['numero_cheque']."',";
      $sql .= "	   '".$datos['girador']."', ";
      $sql .= "	    ".$fecha.", ";
      $sql .= "	    ".$datos['valor'].", ";
      $sql .= "	   '".$this->DividirFecha($datos['fecha_transaccion'])."'::date, ";
      $sql .= "		 '0',";
      $sql .= "		  ".$empresa['usuario_id'].", ";
      $sql .= "		  NOW(),";
      $sql .= "	   '".$datos['numero_cuenta']."' ";
      $sql .= "	); ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $sql  = "INSERT INTO tmp_cheques_mov_confirmacion ";
      $sql .= "     ( ";
      $sql .= "       tmp_confirmacion_id,";
      $sql .= "       cheque_mov_id,";
      $sql .= "       documento,";
      $sql .= "       entidad_confirma,";
      $sql .= "       funcionario_confirma,";
      $sql .= "       numero_confirmacion,";
      $sql .= "       fecha ";
      $sql .= "     ) ";
      $sql .= "VALUES";
      $sql .= "     (";
      $sql .= "       DEFAULT,";
      $sql .= "       ".$cheque_mov.",";
      $sql .= "		    ".$empresa['documento'].", ";
      $sql .= "      '".$datos['entidad']."',";
      $sql .= "      '".$datos['funcionario']."',";
      $sql .= "      '".$datos['numero']."',";
      $sql .= "      '".$this->DividirFecha($datos['fecha_confirma'])."'::date ";
      $sql .= "     )";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      $this->Commit();
      
      return true;
    }
    /**
    * Funcion donde se obtiene la informacion del pago con cheque
    *
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function ObtenerInformacionChequeTemp($empresa)
    {
      $sql  = "SELECT TC.banco , ";
      $sql .= "		    TC.cheque, ";
      $sql .= "		    TC.girador, ";
      $sql .= "		    TO_CHAR(TC.fecha_cheque,'DD/MM/YYYY') AS fecha_cheque, ";
      $sql .= "		    TC.total, ";
      $sql .= "		    TO_CHAR(TC.fecha,'DD/MM/YYYY') AS fecha , ";
      $sql .= "		    TC.estado, ";
      $sql .= "		    TC.usuario_id, ";	
      $sql .= "		    TC.cta_cte, ";
      $sql .= "       TR.entidad_confirma,";
      $sql .= "       TR.funcionario_confirma,";
      $sql .= "       TR.numero_confirmacion,";
      $sql .= "       TO_CHAR(TR.fecha,'DD/MM/YYYY') AS fecha_comfirmacion ";
      $sql .= "FROM   tmp_cheques_mov_rc TC, ";
      $sql .= "       tmp_cheques_mov_confirmacion TR ";
      $sql .= "WHERE  TC.documento = ".$empresa['documento']." ";
      $sql .= "AND    TC.empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    TC.centro_utilidad = '".$empresa['centro_utilidad']."' ";
      $sql .= "AND    TC.cheque_mov_id = TR.cheque_mov_id ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }    
    /**
    * Funcion donde se obtiene la informacion de los pagos realizados
    *
    * @param integer $documento Identificador del documento
    *
    * @return mixed
    */
    function ObtenerInformacionPagosTemp($documento)
    {
      $sql  = "SELECT round(total_efectivo) AS total_efectivo, ";
      $sql .= "       round(total_bono) AS total_bono, ";
      $sql .= "       round(total_cheque) AS total_cheque, ";
      $sql .= "       round(total_debito) AS total_debito, ";
      $sql .= "       round(total_credito) AS total_credito  ";
      $sql .= "FROM   tmp_bodegas_documentos_pagos ";
      $sql .= "WHERE  documento = ".$documento." ";

      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    /**
    * Funcion donde se hace el registro temporal del pago con tarjeta debito
    *
    * @param array $datos Arreglo de datos con la informacion del pago
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function IngresarPagoTarjetaDTemporal($datos,$empresa)
		{
      $sql  = "DELETE FROM tmp_tarjetas_mov_debito ";
      $sql .= "WHERE  documento = ".$empresa['documento']." ";
      $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

			$sql  = "INSERT into tmp_tarjetas_mov_debito";
      $sql .= "   (";
			$sql .= "			  empresa_id,";
			$sql .= "			  centro_utilidad,";
			$sql .= "			  documento,";
			$sql .= "			  autorizacion,";
			$sql .= "			  total,";
			$sql .= "			  tarjeta,";
			$sql .= "			  tarjeta_numero";
			$sql .= "		)";
			$sql .= "VALUES";
      $sql .= "   (";
      $sql .= "	    '".$empresa['empresa_id']."', ";
      $sql .= "		  '".$empresa['centro_utilidad']."', ";
      $sql .= "		   ".$empresa['documento'].", ";
			$sql .= "			'".$datos['num_autorizacion']."',";
			$sql .= "			 ".$datos['valor'].",";
			$sql .= "			'".$datos['tarjeta']."',";
			$sql .= "			'".$datos['num_tarjeta']."' ";
			$sql .= "		)";
			
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
        
			return true;
		}
    /**
    * Funcion donde se obtiene la informcion del pago temporal con tarjeta debito
    *
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function ObtenerInformacionTarjetaDTemp($empresa)
		{
			$sql  = "SELECT autorizacion,";
			$sql .= "			  total,";
			$sql .= "			  tarjeta,";
			$sql .= "			  tarjeta_numero ";
			$sql .= "FROM   tmp_tarjetas_mov_debito ";
      $sql .= "WHERE  documento = ".$empresa['documento']." ";
			
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
		
			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
		}
    /**
    * Funcion donde se actualiza el valor que se paga por cada uno de los medios
    * destinados para ello
    *
    * @param integer $valor Valor a actualizar
    * @param integer $documento Identificador del documento
    * @param string $label Identificador del medio de pago
    *
    * @return boolean
    */
    function IngresarPagoEfectivoBonos($valor,$documento,$label)
    {
      $sql  = "UPDATE tmp_bodegas_documentos_pagos ";
      $sql .= "SET    total_".$label." = ".$valor." ";
      $sql .= "WHERE  documento = ".$documento." ";
     
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      return true;
    }
    /**
    * Funcion donde se hace el registro del tercero que hace la compra
    *
    * @param array $form Arreglo de datos con la informacion a registrar
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    *
    * @return mixed
    */
    function IngresarTercero($form,$empresa)
    {
      $sql  = "SELECT nombre_tercero ";
      $sql .= "FROM   terceros ";
      $sql .= "WHERE  tipo_id_tercero = '".$form['tipo_id_tercero']."' ";
      $sql .= "AND    tercero_id = '".$form['tercero_id']."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

			$datos = array();
			if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
      if(!empty($datos))
      {
        $sql  = "UPDATE terceros ";
        $sql .= "SET    nombre_tercero = '".strtoupper($form['nombre_tercero'])."',";
        $sql .= "       tipo_pais_id = '".$form['pais']."', ";
        $sql .= "       tipo_dpto_id = '".$form['dpto']."', ";
        $sql .= "       tipo_mpio_id = '".$form['mpio']."', ";
        $sql .= "       direccion = '".$form['direccion']."', ";
        $sql .= "       telefono = '".$form['telefono']."', ";
        $sql .= "       celular = '".$form['celular']."' ";
        $sql .= "WHERE  tipo_id_tercero = '".$form['tipo_id_tercero']."' ";
        $sql .= "AND    tercero_id = '".$form['tercero_id']."' ";
      }
      else
      {
        $sql  = "INSERT INTO terceros";
        $sql .= "     (";
        $sql .= "       tipo_id_tercero,";
        $sql .= "       tercero_id,";
        $sql .= "       nombre_tercero,";
        $sql .= "       tipo_pais_id,";
        $sql .= "       tipo_dpto_id,";
        $sql .= "       tipo_mpio_id,";
        $sql .= "       direccion,";
        $sql .= "       telefono,";
        $sql .= "       celular,";
        $sql .= "       sw_persona_juridica,";
        $sql .= "       cal_cli,";
        $sql .= "       usuario_id,";
        $sql .= "       fecha_registro ";
        $sql .= "     )";
        $sql .= "VALUES";
        $sql .= "     (";
        $sql .= "       '".$form['tipo_id_tercero']."',";
        $sql .= "       '".$form['tercero_id']."',";
        $sql .= "       '".strtoupper($form['nombre_tercero'])."',";
        $sql .= "       '".$form['pais']."',";
        $sql .= "       '".$form['dpto']."',";
        $sql .= "       '".$form['mpio']."',";
        $sql .= "       '".$form['direccion']."',";
        $sql .= "       '".$form['telefono']."',";
        $sql .= "       '".$form['celular']."',";
        $sql .= "       '1',";
        $sql .= "       '0',";
        $sql .= "        ".$form['usuario_id'].",";
        $sql .= "        NOW() ";
        $sql .= "     ) ";
      }
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;                
      
      return true;
    }
    /**
    * Funcion donde se obtiene que recibos estan sin cuadrar, de otros usuarios
    *
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    * @param array $caja Arreglo de datos con la informacion de la caja de pago
    *
    * @return mixed
    */
    function ObtenerReciboSinCuadre($empresa,$caja)
    {
      $sql = "SELECT a.usuario_id ";
      if($caja['cuenta_tipo_id'] == '03' || $caja['cuenta_tipo_id'] == '08')
      {
        $sql .= "FROM   fac_facturas_contado a,";
        $sql .= "       cajas_rapidas b,";
        $sql .= "       userpermisos_cajas_rapidas c,";
        $sql .= "       system_usuarios d ";
        $sql .= "WHERE  a.caja_id = b.caja_id ";
        $sql .= "AND    a.caja_id = ".$caja['caja_id']." ";
        $sql .= "AND    a.empresa_id='".$empresa['empresa_id']."' ";
        $sql .= "AND    a.centro_utilidad = '".$empresa['centro_utilidad']."'  ";
        $sql .= "AND    a.usuario_id <> ".$empresa['usuario_id']." ";
        $sql .= "AND    a.cierre_caja_id IS NULL ";
        $sql .= "AND    c.caja_id = a.caja_id ";
        $sql .= "AND    a.usuario_id = c.usuario_id ";
        $sql .= "AND    a.usuario_id = d.usuario_id ";
      }
      else
      {
        $sql = "FROM    recibos_caja a,";
        $sql .= "       cajas b,";
        $sql .= "       system_usuarios d,";
        $sql .= "       cajas_usuarios c ";
        $sql .= "WHERE  a.caja_id = ".$caja['caja_id']." ";
        $sql .= "AND    a.caja_id = b.caja_id ";
        $sql .= "AND    a.cierre_caja_id IS NULL ";
        $sql .= "AND    a.caja_id=c.caja_id ";
        $sql .= "AND    a.empresa_id='".$empresa['empresa_id']."' ";
        $sql .= "AND    a.centro_utilidad = '".$empresa['centro_utilidad']."'  ";
        $sql .= "AND    a.usuario_id <> ".$empresa['usuario_id']." ";
        $sql .= "AND    c.usuario_id=d.usuario_id ";
        $sql .= "AND    c.usuario_id=a.usuario_id ";
        $sql .= "AND    b.cuenta_tipo_id = '01' ";
        $sql .= "AND    a.estado IN ('0') ";
        $sql .= "ORDER BY b.descripcion ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;  
        
      $datos = array();
      while(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    
       /*
     * obtiene la ip del cliente
     */
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    /**
    * Funcion donde se hace la creacion de la factura real
    *
    * @param array $datos Datos a ingresar de la factura
    * @param array $empresa Arreglo de datos con la informacion de la empresa
    * @param array $caja Arreglo de datos con la informacion de la caja de pago
    *
    * @return mixed
    */
    function IngresarFacturaVenta($datos, $empresa, $caja)
    {
      $this->ConexionTransaccion();

      $datos['centro_utilidad'] = trim($datos['centro_utilidad']);
      $empresa['empresa_id'] = trim($empresa['empresa_id']);
      $caja['prefijo_fac_contado'] = trim($caja['prefijo_fac_contado']);
      $datos['bodega'] = trim($datos['bodega']);
      $datos['documento'] = trim($datos['documento']);

      if(strlen($datos['centro_utilidad']) == 1){
          $datos['centro_utilidad'] = $datos['centro_utilidad'].' ';
      }
      
      $sql  = "SELECT codigo_producto,";
      $sql .= "       SUM(cantidad) AS cantidad,";
      $sql .= "       SUM(total_costo) AS total_costo,";
      $sql .= "       bodegas_doc_id,";
      $sql .= "       fecha_vencimiento, ";
      $sql .= "       lote ";
      $sql .= "FROM   tmp_bodegas_documentos_d ";
      $sql .= "WHERE  documento = ".$datos['documento']." ";
      $sql .= "GROUP BY codigo_producto,bodegas_doc_id,fecha_vencimiento,lote ";

      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

			$productos = array();
			while(!$rst->EOF)
			{
				$productos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
			}
			$rst->Close();
      
			
      $sql  = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";//Bloqueo de tabla 
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

      // $sql  = "UPDATE documentos ";
      // $sql .= "SET numeracion = numeracion +1 ";
      // $sql .= "WHERE  documento_id = ".$caja['prefijo_fac_contado']." ";
      // $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
	  
       $sql  = "UPDATE documentos ";
       $sql .= "SET numeracion = numeracion +1 ";
       $sql .= "WHERE  documento_id = ".$caja['prefijo_fac_contado']." ";
       $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
       $sql .= "AND    centro_utilidad = '".$datos['centro_utilidad']."' ";
       $sql .= "AND    bodega = '".$datos['bodega']."' ";
	   
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

	   // $sql  = "SELECT prefijo,";
       // $sql .= "              numeracion AS factura_fiscal ";
       // $sql .= "FROM    documentos ";
	   // $sql .= "WHERE  documento_id = ".$caja['prefijo_fac_contado']." ";
       // $sql .= "    AND   empresa_id = '".$empresa['empresa_id']."' ";

	   $sql  = "SELECT prefijo,";
       $sql .= "              numeracion AS factura_fiscal ";
       $sql .= "FROM    documentos ";
	   $sql .= "WHERE  documento_id = ".$caja['prefijo_fac_contado']." ";
       $sql .= "    AND   empresa_id = '".$empresa['empresa_id']."' ";
       $sql .= "    AND   centro_utilidad = '".$datos['centro_utilidad']."' ";
       $sql .= "    AND   bodega = '".$datos['bodega']."' ";
	   
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $num = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();


      $sql  = "INSERT INTO fac_facturas";
      $sql .= "     ( ";
      $sql .= "      empresa_id, ";
      $sql .= " 	    prefijo, ";
      $sql .= " 	    factura_fiscal, ";
      $sql .= " 	    estado, ";
      $sql .= " 	    usuario_id, ";
      $sql .= " 	    fecha_registro, ";
      $sql .= " 	    total_factura, ";
      $sql .= " 	    gravamen, ";
      $sql .= " 	    valor_cargos, ";
      $sql .= " 	    valor_cuota_paciente, ";
      $sql .= " 	    valor_cuota_moderadora, ";
      $sql .= " 	    descuento , ";
      $sql .= " 	    tipo_id_tercero, ";
      $sql .= " 	    tercero_id, ";
      $sql .= " 	    sw_clase_factura, ";
      $sql .= " 	    total_capitacion_real, ";
      $sql .= " 	    documento_id, ";
      $sql .= " 	    tipo_factura ";
      $sql .= "     ) ";
      $sql .= "VALUES";
      $sql .= "     (";
      $sql .= "        '".$empresa['empresa_id']."',";
      $sql .= "        '".$num['prefijo']."',";
      $sql .= "        '".$num['factura_fiscal']."',";
      $sql .= "        '0',";
      $sql .= "         ".$datos['usuario_id'].", ";
      $sql .= "         NOW(),";
      $sql .= "         ".($datos['h_efectivo'] + $datos['h_cheque'] + $datos['h_credito'] + $datos['h_debito'] + $datos['h_bono']).",";
      $sql .= "         ".$datos['valor_iva'].","; //gravamen
      //$sql .= "         0,"; //gravamen
      $sql .= "         ".($datos['h_efectivo'] + $datos['h_cheque'] + $datos['h_credito'] + $datos['h_debito'] + $datos['h_bono']).",";
      $sql .= "         0,";
      $sql .= "         0,";
      $sql .= "         0,";
      $sql .= "        '".$empresa['tipo_id_tercero']."',";
      $sql .= "        '".$empresa['tercero_id']."',";
      $sql .= "        '0',";
      $sql .= "         0,";
      $sql .= "         ".$caja['prefijo_fac_contado'].", ";
      $sql .= "        '".$caja['tipo_factura']."' ";
      $sql .= "      ) ";

       if(!$rst = $this->ConexionTransaccion($sql))
        return false;
       
      $sql  = "INSERT INTO pc_factura_clientes";
      $sql .= "     ( ";
      $sql .= "             ip, ";
      $sql .= " 	    prefijo, ";
      $sql .= " 	    factura_fiscal, ";
      $sql .= " 	    sw_tipo_factura, ";
      $sql .= " 	    fecha_registro, ";
      $sql .= " 	    empresa_id ";
      $sql .= "     ) ";
      $sql .= "VALUES";
      $sql .= "     (";
      $sql .= "        '".$this->get_client_ip()."',";
      $sql .= "        '".$num['prefijo']."',";
      $sql .= "        '".$num['factura_fiscal']."',";
      $sql .= "        '0',";
      $sql .= "         NOW(),";
      $sql .= "         '".$empresa['empresa_id']."'";
      $sql .= "      ) ";

       if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $sql  = "INSERT INTO fac_facturas_contado ";
      $sql .= "     ( ";
      $sql .= "       empresa_id,";
      $sql .= "       centro_utilidad,";
      $sql .= "       prefijo,";
      $sql .= "       factura_fiscal,";
      $sql .= "       total_abono,";
      $sql .= "       total_efectivo,";
      $sql .= "       total_cheques,";
      $sql .= "       total_tarjetas,";
      $sql .= "       total_bonos, ";
      $sql .= "       tipo_id_tercero,";
      $sql .= "       tercero_id,";
      $sql .= "       estado,";
      $sql .= "       fecha_registro,";
      $sql .= "       usuario_id,";
      $sql .= "       caja_id, ";
      $sql .= "       documento_id ";
      $sql .= "     ) ";
      $sql .= "VALUES";
      $sql .= "     (";
      $sql .= "        '".$empresa['empresa_id']."',";
      $sql .= "        '".$empresa['centro_utilidad']."',";
      $sql .= "        '".$num['prefijo']."',";
      $sql .= "         ".$num['factura_fiscal'].",";
      $sql .= "         ".($datos['h_efectivo'] + $datos['h_cheque'] + $datos['h_credito'] + $datos['h_debito'] + $datos['h_bono']).",";
      $sql .= "         ".$datos['h_efectivo'].",";
      $sql .= "         ".$datos['h_cheque'].",";
      $sql .= "         ".($datos['h_credito']+$datos['h_debito']).",";
      $sql .= "         ".$datos['h_bono'].",";
      $sql .= "        '".$empresa['tipo_id_tercero']."',";
      $sql .= "        '".$empresa['tercero_id']."',";
      $sql .= "        '0',";
      $sql .= "         NOW(),";
      $sql .= "         ".$datos['usuario_id'].", ";
      $sql .= "         ".$caja['caja_id'].", ";
      $sql .= "         ".$caja['prefijo_fac_contado']." ";
      $sql .= "       ) ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

      if($datos['h_cheque'] > 0 )
      {
        $sql  = "SELECT NEXTVAL('cheques_mov_venta_directa_cheque_mov_id_seq') AS indice ";
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;

        $cheque_mov = $rst->fields[0];

        $sql  = "INSERT INTO cheques_mov_venta_directa ";
        $sql .= "     (";
        $sql .= "       cheque_mov_id,";
        $sql .= "				empresa_id , ";
        $sql .= "				prefijo, ";
        $sql .= "				factura_fiscal, ";
        $sql .= "				banco , ";
        $sql .= "				cheque, ";
        $sql .= "				girador, ";
        $sql .= "				fecha_cheque, ";
        $sql .= "				total, ";
        $sql .= "				fecha , ";
        $sql .= "				estado, ";
        $sql .= "				usuario_id, ";	
        $sql .= "				fecha_registro, ";
        $sql .= "				cta_cte";
        $sql .= "     ) ";
        $sql .= "SELECT ".$cheque_mov." AS cheque_mov_id,";
        $sql .= "       empresa_id , ";
        $sql .= "       '".$num['prefijo']."' AS prefijo,";
        $sql .= "       ".$num['factura_fiscal']." AS factura_fiscal,";
        $sql .= "		    banco , ";
        $sql .= "		    cheque, ";
        $sql .= "		    girador, ";
        $sql .= "		    fecha_cheque, ";
        $sql .= "		    total, ";
        $sql .= "		    fecha , ";
        $sql .= "		    estado, ";
        $sql .= "		    usuario_id, ";	
        $sql .= "		    fecha_registro, ";
        $sql .= "		    cta_cte ";
        $sql .= "FROM   tmp_cheques_mov_rc ";
        $sql .= "WHERE  documento = ".$datos['documento']." ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
        
        $sql  = "INSERT INTO cheques_mov_venta_directa_confirmacion ";
        $sql .= "     ( ";
        $sql .= "       cheque_mov_id,";
        $sql .= "       entidad_confirma,";
        $sql .= "       funcionario_confirma,";
        $sql .= "       numero_confirmacion,";
        $sql .= "       fecha ";
        $sql .= "     ) ";
        $sql .= "SELECT ".$cheque_mov." AS cheque_mov_id, ";
        $sql .= "       entidad_confirma,";
        $sql .= "       funcionario_confirma,";
        $sql .= "       numero_confirmacion,";
        $sql .= "       fecha ";
        $sql .= "FROM   tmp_cheques_mov_confirmacion ";
        $sql .= "WHERE  documento = ".$datos['documento']." ";
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }

      if($datos['h_credito'] > 0)
      {        
        $sql  = "SELECT NEXTVAL('tarjetas_mov_credito_venta_tarjeta_mov_id_seq') AS indice ";
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;

        $tarjeta = $rst->fields[0];
      
        $sql  = "INSERT INTO tarjetas_mov_credito_venta";
        $sql .= "   ( ";
        $sql .= "     tarjeta_mov_id,";
        $sql .= "     tarjeta,";
        $sql .= "     empresa_id,";
        $sql .= "     prefijo,";
        $sql .= "     factura_fiscal,";
        $sql .= "     fecha,";
        $sql .= "     autorizacion,";
        $sql .= "     socio,";
        $sql .= "     fecha_expira,";
        $sql .= "     total,";
        $sql .= "     usuario_id,";
        $sql .= "     fecha_registro,";
        $sql .= "     tarjeta_numero ";
        $sql .= "   ) ";
        $sql .= "SELECT  ".$tarjeta." AS tarjeta_mov_id, ";
        $sql .= "			  tarjeta ,";
        $sql .= "			  empresa_id , ";
        $sql .= "       '".$num['prefijo']."' AS prefijo,";
        $sql .= "       ".$num['factura_fiscal']." AS factura_fiscal,";
        $sql .= "			  fecha,";
        $sql .= "       autorizacion,";
        $sql .= "			  socio ,";
        $sql .= "			  fecha_expira ,";
        $sql .= "			  total ,";
        $sql .= "			  usuario_id ,";
        $sql .= "			  fecha_registro,"; 
        $sql .= "			  tarjeta_numero ";
        $sql .= "FROM   tmp_tarjetas_mov_credito ";
        $sql .= "WHERE  documento = ".$datos['documento']." ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;

        $sql  = "INSERT INTO  tarjetas_mov_credito_confirmacion ";
        $sql .= "   ( ";
        $sql .= "     tarjeta_mov_id,";
        $sql .= "     entidad_confirma, ";
        $sql .= "     funcionario_confirma,";
        $sql .= "     numero_confirmacion, ";
        $sql .= "     fecha ";
        $sql .= "   ) ";
  			$sql .= "SELECT ".$tarjeta." AS tarjeta_mov_id,";
  			$sql .= "			  TR.entidad_confirma,";
  			$sql .= "			  TR.funcionario_confirma,";
  			$sql .= "			  TR.numero_confirmacion, ";
  			$sql .= "			  TR.fecha ";
        $sql .= "FROM   tmp_confirmacion_tar TR, ";
        $sql .= "       tmp_tarjetas_mov_credito TC ";
        $sql .= "WHERE  TC.documento = ".$datos['documento']." ";
        $sql .= "AND    TC.tarjeta_mov_id = TR.tarjeta_mov_id ";

  			if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }

      if($datos['h_debito'] > 0)
      {
        $sql  = "INSERT INTO tarjetas_mov_debito_venta";
        $sql .= "   ( ";
        $sql .= "     tarjeta,";
        $sql .= "     empresa_id,";
        $sql .= "     prefijo,";
        $sql .= "     factura_fiscal,";
        $sql .= "     autorizacion,";
        $sql .= "     total,";
        $sql .= "     tarjeta_numero, ";
        $sql .= "     fecha_registro,";
        $sql .= "     usuario_id ";
        $sql .= "   ) ";
        $sql .= "SELECT tarjeta ,";
        $sql .= "			  empresa_id , ";
        $sql .= "       '".$num['prefijo']."' AS prefijo,";
        $sql .= "       ".$num['factura_fiscal']." AS factura_fiscal,";
        $sql .= "       autorizacion,";
        $sql .= "			  total ,";
        $sql .= "			  tarjeta_numero, ";
        $sql .= "			  NOW(),";
        $sql .= "       ".$empresa['usuario_id']." AS usuario_id ";
        $sql .= "FROM   tmp_tarjetas_mov_debito ";
        $sql .= "WHERE  documento = ".$datos['documento']." ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      
      $sql  = "LOCK TABLE bodegas_doc_numeraciones IN ROW EXCLUSIVE MODE";
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
        
	  $sql  = "UPDATE bodegas_doc_numeraciones ";
      $sql .= "SET        numeracion = numeracion + 1 ";
      $sql .= "WHERE  bodegas_doc_id = ".$empresa['bodegas_doc_id']." ";
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
			
      $sql  = "SELECT numeracion ";
      $sql .= "FROM    bodegas_doc_numeraciones ";
      $sql .= "WHERE  bodegas_doc_id = ".$empresa['bodegas_doc_id']." ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
      
      $numeracion = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
      
      $sql  = "INSERT INTO bodegas_documentos ";
      $sql .= "   ( ";
      $sql .= "     bodegas_doc_id,";
      $sql .= "		  numeracion,";
      $sql .= "		  fecha,";
      $sql .= "		  total_costo,";
      $sql .= "		  observacion,";
      $sql .= "		  usuario_id,";
      $sql .= "		  fecha_registro ";
      $sql .= "		) ";
      $sql .= "SELECT bodegas_doc_id, ";      
      $sql .= "       ".$numeracion['numeracion']." AS numeracion,";
      $sql .= " 	    fecha,";
      $sql .= " 	    total_costo,";
      $sql .= "       'VENTA DE PRODUCTOS FARMACIA' AS observacion,";
      $sql .= " 	    usuario_id,";
      $sql .= " 	    NOW() ";
      $sql .= "FROM   tmp_bodegas_documentos ";
      $sql .= "WHERE  documento = ".$datos['documento']." ";

      if(!$rst = $this->ConexionTransaccion($sql))
        return false;

      $sql  = "INSERT INTO bodegas_documentos_d ";
      $sql .= "     ( ";
      $sql .= "		    codigo_producto,";
      $sql .= "		    cantidad,";
      $sql .= "		    total_costo,";
      $sql .= "		    bodegas_doc_id,";
      $sql .= "		    numeracion, ";
      $sql .= "		    iva_compra, ";
      $sql .= "       fecha_vencimiento, ";
      $sql .= "       lote ";
      $sql .= "		  ) ";
      $sql .= "SELECT codigo_producto,";
      $sql .= "       SUM(cantidad) AS cantidad,";
      $sql .= "       SUM(total_costo) AS total_costo,";
      $sql .= "       bodegas_doc_id,";
      $sql .= "       ".$numeracion['numeracion']." AS numeracion,";
      $sql .= "       SUM(iva_compra) AS iva_compra,";	  
      $sql .= "       fecha_vencimiento, ";
      $sql .= "       lote ";
      $sql .= "FROM   tmp_bodegas_documentos_d ";
      $sql .= "WHERE  documento = ".$datos['documento']." ";
      $sql .= "GROUP BY codigo_producto,bodegas_doc_id,numeracion,fecha_vencimiento,lote ";

      if(!$rst = $this->ConexionTransaccion($sql))
        return false;      
      
      $sql  = "INSERT INTO facturas_documentos_bodega ";
      $sql .= "     ( ";
      $sql .= "       bodegas_doc_id,";
      $sql .= "       bodegas_numeracion,";
      $sql .= "       empresa_id,";
      $sql .= "       factura_fiscal,";
      $sql .= "       prefijo ";
      $sql .= "     ) ";
      $sql .= "VALUES ";
      $sql .= "     ( ";
      $sql .= "        ".$empresa['bodegas_doc_id'].", ";
      $sql .= "        ".$numeracion['numeracion'].", ";
      $sql .= "       '".$empresa['empresa_id']."', ";
      $sql .= "        ".$num['factura_fiscal'].",";
      $sql .= "       '".$num['prefijo']."' ";
      $sql .= "     ) ";

      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
        
      foreach($productos as $key => $dtl)
      {
        $sql  = "UPDATE existencias_bodegas ";
        $sql .= "SET    existencia = existencia - ".$dtl['cantidad']." ";
        $sql .= "WHERE  empresa_id = '".$empresa['empresa_id']."' ";
        $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."' ";
        $sql .= "AND    bodega = '".$empresa['bodega']."' ";
        $sql .= "AND    codigo_producto = '".$dtl['codigo_producto']."' ";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;

        $sql  = "UPDATE existencias_bodegas_lote_fv ";
        $sql .= "SET    existencia_actual = existencia_actual - ".$dtl['cantidad']." ";
        $sql .= "WHERE  empresa_id = '".$empresa['empresa_id']."' ";
        $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."' ";
        $sql .= "AND    bodega = '".$empresa['bodega']."' ";
        $sql .= "AND    codigo_producto = '".$dtl['codigo_producto']."' ";
        $sql .= "AND    lote = '".$dtl['lote']."' ";
        $sql .= "AND    fecha_vencimiento = '".$dtl['fecha_vencimiento']."' ";

        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      
      $sql  = "DELETE FROM tmp_cheques_mov_confirmacion ";
      $sql .= "WHERE  documento = ".$datos['documento']."; ";
      $sql .= "DELETE FROM tmp_cheques_mov_rc ";
      $sql .= "WHERE  documento = ".$datos['documento']." ";
      $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."'; ";
      $sql .= "DELETE FROM tmp_tarjetas_mov_debito ";
      $sql .= "WHERE  documento = ".$datos['documento']." ";
      $sql .= "AND    empresa_id = '".$empresa['empresa_id']."' ";
      $sql .= "AND    centro_utilidad = '".$empresa['centro_utilidad']."'; ";        
      $sql .= "DELETE FROM tmp_confirmacion_tar ";
      $sql .= "WHERE  tarjeta_mov_id IN ";
      $sql .= "       ( SELECT tarjeta_mov_id ";
      $sql .= "         FROM   tmp_tarjetas_mov_credito ";
      $sql .= "         WHERE  documento = ".$datos['documento']."); ";
      $sql .= "DELETE FROM tmp_tarjetas_mov_credito ";
      $sql .= "WHERE  documento = ".$datos['documento']."; ";
      $sql .= "DELETE FROM tmp_bodegas_documentos_d ";
      $sql .= "WHERE  documento = ".$datos['documento']."; ";
      $sql .= "DELETE FROM tmp_bodegas_documentos ";
      $sql .= "WHERE  documento = ".$datos['documento']."; ";
      $sql .= "DELETE FROM tmp_bodegas_documentos_pagos ";
      $sql .= "WHERE  documento = ".$datos['documento']."; ";
      
      if(!$rst = $this->ConexionTransaccion($sql))
        return false;
			        
      $this->Commit();
      return $num;
    }
  }
?>