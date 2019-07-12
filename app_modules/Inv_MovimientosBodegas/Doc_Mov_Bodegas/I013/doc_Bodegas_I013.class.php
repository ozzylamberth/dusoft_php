<?php
  /******************************************************************************
  * $Id: doc_Bodegas_I013.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.1 $ 
  * 
  * @autor Mauricio Medina
  ********************************************************************************/

if (!IncludeClass('BodegasDocumentos'))
{
    die(MsgOut("Error al incluir archivo","BodegasDocumentos"));
}

class doc_bodegas_I013
{

  function DatosParaEditar($tmp_doc_id,$usuario_id)
  {
    $ClassDOC= new BodegasDocumentos();
    $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($tmp_doc_id,$usuario_id);
    return $datos;
  }
  
  function EliminarItem($tr,$item)
  {
    //print_r($item);
    list($bodegas_doc_id,$i) = explode("@",$tr);
    $ClassDOC= new BodegasDocumentos();
    $OBJETO=$ClassDOC->GetOBJ($bodegas_doc_id);
    $resultado=$OBJETO->DelItemDocTemporal($item);
    return $resultado;
  }


  function SacarProductosTMP($doc_tmp_id,$usuario_id)//
   {
       $ClassDOC= new BodegasDocumentos();
       $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id,$usuario_id);
       IF(!EMPTY($datos))
       {
          $OBJETO=$ClassDOC->GetOBJ($datos['bodegas_doc_id']);
          $tabla_de_productos=$OBJETO->GetItemsDocTemporal($doc_tmp_id,$usuario_id);
          return $tabla_de_productos;
       }
       else
       {
         var_dump($ClassDOC->ErrMsg());

       }
  }
    //CrearDoc($bodegas_doc_id, $observacion, $tipo_id_tercero, $tercero_id, $prestamo);
    function CrearDoc($bodegas_doc_id, $observacion, $formula_id, $formula_papel)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();//($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra
       $RETORNO=$OBJETO->NewDocTemporal($observacion,$formula_id, $formula_papel,UserGetUID());
      //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
        echo $OBJETO->Err().$OBJETO->ErrMsg();
       //var_dump($RETORNO);
        if(!is_object($ClassDOC))
        {
            die(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
     return $RETORNO;
    }


    function GetDocTemporal($bodegas_doc_id,$doc_tmp_id,$usuario_id)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->GetDocTemporal($doc_tmp_id,$usuario_id);
       //print_r($RETORNO);
       if(!is_object($ClassDOC))
        {
            die(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
     return $RETORNO;

    }
    
    function TraerDatos($bodegas_doc_id)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
       //var_dump($RETORNO);
       if(!is_object($ClassDOC))
        {
            die(MsgOut("Error al crear la clase","BodegasDocumentos"));
        }
     return $RETORNO;

    }
    
    
      function Productos_Formula($formula_papel,$empresa_id,$codigo_barras,$descripcion,$tipo_paciente,$paciente_id)
    {
         
	if($tipo_paciente != "" && $paciente_id != "")
                 $documento = " AND efe.tipo_id_paciente ='$tipo_paciente' AND  efe.paciente_id='$paciente_id' ";
	if($codigo_barras != "")
		$filtro = " AND invp.codigo_barras = ".$codigo_barras." ";
		
       $sql="select
			*
			from (   
			    select
			    fc_descripcion_producto_alterno(bdd.codigo_producto) as descripcion,
				efdm.formula_id,
			    bd.fecha_registro,
			    bdd.consecutivo,
			    bdd.bodegas_doc_id,
			    bdd.numeracion,
			    bdd.codigo_producto,
				bdd.total_costo,
			    (bdd.cantidad-bdd.cantidad_devuelta) as cantidad,
			    bdd.lote,
			    bdd.fecha_vencimiento
			    from
			    esm_formula_externa efe,
			    esm_formulacion_despachos_medicamentos efdm,
			    bodegas_documentos bd,
			    bodegas_documentos_d bdd,
				bodegas_doc_numeraciones bdn,
				inventarios_productos invp
			    where
			         efe.formula_papel = '".$formula_papel."' $documento
			    and  efe.sw_estado IN ('0','1')
			    and  efe.formula_id = efdm.formula_id
			    and  efdm.bodegas_doc_id = bd.bodegas_doc_id
			    and  efdm.numeracion = bd.numeracion
			    and  bd.bodegas_doc_id = bdd.bodegas_doc_id
			    and  bd.numeracion = bdd.numeracion
				and	 (bdd.cantidad-bdd.cantidad_devuelta)>0
				and  bd.bodegas_doc_id=bdn.bodegas_doc_id
				and  bdn.empresa_id='".$empresa_id."'
				and	 bdd.codigo_producto = invp.codigo_producto
				and  invp.descripcion ILIKE '%".$descripcion."%'
				$filtro
			    UNION
			    select
			    fc_descripcion_producto_alterno(bdd.codigo_producto) as descripcion,
				efdm.formula_id,
			    bd.fecha_registro,
			    bdd.consecutivo,
			    bdd.bodegas_doc_id,
			    bdd.numeracion,
			    bdd.codigo_producto,
				bdd.total_costo,
			    (bdd.cantidad-bdd.cantidad_devuelta) as cantidad,
			    bdd.lote,
			    bdd.fecha_vencimiento
			    from
			    esm_formula_externa efe,
			    esm_formulacion_despachos_medicamentos_pendientes efdm,
			    bodegas_documentos bd,
			    bodegas_documentos_d bdd,
				bodegas_doc_numeraciones bdn,
				inventarios_productos invp
			    where
			         efe.formula_papel = '".$formula_papel."' $documento
			    and  efe.sw_estado IN ('0','1')
			    and  efe.formula_id = efdm.formula_id
			    and  efdm.bodegas_doc_id = bd.bodegas_doc_id
			    and  efdm.numeracion = bd.numeracion
			    and  bd.bodegas_doc_id = bdd.bodegas_doc_id
			    and  bd.numeracion = bdd.numeracion
				and	 (bdd.cantidad-bdd.cantidad_devuelta)>0
				and  bd.bodegas_doc_id=bdn.bodegas_doc_id
				and  bdn.empresa_id='".$empresa_id."'
				and	 bdd.codigo_producto = invp.codigo_producto
				and  invp.descripcion ILIKE '%".$descripcion."%'
				$filtro
			    ) AS A
			    order by descripcion ASC,lote ASC
              ";
              
              //print_r($sql);
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }
	
   function Formula_Buscada($formula_papel,$empresa_id,$tipo_doc,$doc)
    {
       $sql="    select
			    efe.formula_papel,
			    efe.formula_id,
			    efe.tipo_id_paciente,
			    efe.paciente_id,
			    pac.primer_nombre|| ' ' ||pac.segundo_nombre|| ' ' ||pac.primer_apellido|| ' ' ||pac.segundo_apellido as paciente,
                            CASE WHEN (extract(month from efe.fecha_formula) < extract(month from now())) THEN '0'
            		    ELSE '1'
                            END as estado_fecha,to_char(efe.fecha_formula, 'DD-MM-YYYY') as fecha
			    from
			    esm_formula_externa efe,
			    esm_formulacion_despachos_medicamentos efdm,
			    bodegas_doc_numeraciones bdn,
			    pacientes pac
			    
			    where
                            efe.tipo_id_paciente='$tipo_doc' AND efe.paciente_id='$doc' 
			    and  efe.formula_papel = '".$formula_papel."'
			    and  efe.sw_estado IN ('0','1')
			    and  efe.formula_id = efdm.formula_id
			    and  efdm.bodegas_doc_id=bdn.bodegas_doc_id
			    and  bdn.empresa_id='".$empresa_id."'
			    and  efe.paciente_id = pac.paciente_id
			    and  efe.tipo_id_paciente = pac.tipo_id_paciente
			    group by efe.formula_papel,
			    efe.formula_id,
			    efe.tipo_id_paciente,
			    efe.paciente_id,paciente,estado_fecha,fecha;
              ";
              
              //print_r($sql);
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }
	
  function ProductosExistencias($EmpresaId,$CentroUtilidad,$Bodega,$CodigoProducto,$Lote,$FechaVencimiento)
    {
       $sql="SELECT 

                  *
              FROM
                  existencias_bodegas_lote_fv
              WHERE
                   empresa_id  = '".$EmpresaId."'
               AND centro_utilidad = '".$CentroUtilidad."'
               AND bodega = '".$Bodega."'
               AND codigo_producto = '".$CodigoProducto."'
               AND lote = '".$Lote."'
               AND fecha_vencimiento = '".$FechaVencimiento."';
              ";
              
              //print_r($sql);
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }
	
  
    
    
     function Modificar_CantidadesDocumento($bodegas_doc_id,$numeracion,$consecutivo,$cantidad)
	{
	$sql  = " UPDATE bodegas_documentos_d ";
  $sql .= " SET 
	          cantidad_devuelta = cantidad_devuelta + ".$cantidad.",
	          total_costo = (total_costo/(cantidad-cantidad_devuelta)) * (cantidad-".$cantidad.") ";
  $sql .= " Where ";
  $sql .= " 		bodegas_doc_id = ".$bodegas_doc_id." ";
  $sql .= " AND		numeracion = ".$numeracion." ";
  $sql .= " AND		consecutivo = ".$consecutivo." ";
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			
			    $rst->Close();
				return true;
				
			
			
		}
    
 
    
    function ProductosDocumento($farmacia_id,$prefijo,$numero,$codigo_barras,$descripcion)
    {
       if(!empty($codigo_barras))
          $filtro = " invp.codigo_barras = '".$codigo_barras."'  ";
       
       $sql="SELECT 
                  md.*,
                  fc_descripcion_producto(md.codigo_producto) as descripcion
                  FROM
                  inv_bodegas_movimiento_d md,
                  inventarios_productos invp
              WHERE
                    md.empresa_id='".trim($farmacia_id)."'
              AND   md.prefijo = '".$prefijo."'
              AND   md.numero = '".$numero."' 
              AND   md.cantidad <> md.cantidad_recibida
              AND   md.codigo_producto = invp.codigo_producto
              AND   invp.descripcion ILIKE '%".$descripcion."%'  
                    ".$filtro."
              ";
              //print_r($sql);
              $sql .= " Order By invp.descripcion ASC, md.fecha_vencimiento ASC ";
                            
              if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }

    	function ListadoNovedadesDevolucion()
{

          $sql="
            select
            novedad_devolucion_id as codigo,
            descripcion,
            estado
            From
            inv_novedades_devoluciones
            where
            estado='1'
            
            order by codigo ASC
            ";
 
 
 //$this->debug=true;
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
}
    
    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento)
    {
            $sql="SELECT
                        count(*)
                  FROM
                      existencias_bodegas as a,
                      inventarios_productos as b,
                      unidades as c
                  WHERE
                  a.empresa_id = '$empresa_id'
                  AND a.centro_utilidad = '$centro_utilidad'
                  AND a.bodega = '$bodega'
                  ".$aumento."
                  AND b.codigo_producto = a.codigo_producto
                  AND c.unidad_id = b.unidad_id";


            if(!$resultado = $this->ConexionBaseDatos($sql))
                return $this->frmError['MensajeError'];

                $cuentas=Array();
                while(!$resultado->EOF)
                {
                  $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                  $resultado->MoveNext();
                }

                $resultado->Close();

                return $cuentas;   
    }
	
	function GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec)
     {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id=null,$fecha_venc,$lotec);
       //var_dump($RETORNO);
       return $RETORNO;
     }
     
     
      function DescripcionProducto($CodigoProducto)
    {
       $sql=" select fc_descripcion_producto('".$CodigoProducto."') as descripcion_producto;  ";
              
             
              if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }
     
     
     function llamarhijo()
    {
    
      echo "llegamos";
      
    }      


function ItemEnMovimiento($doc_tmp_id,$item_id,$item_id_compras)
    {
       
	$sql  = "UPDATE inv_bodegas_movimiento_tmp_d ";
  $sql .= "SET ";
  $sql .= "lote_devuelto = '".$item_id_compras."' ";
  $sql .= " Where ";
  $sql .= " item_id = ".$item_id." ";
	$sql .= " and doc_tmp_id = ".$doc_tmp_id." ";
  $sql .= " and usuario_id = ".UserGetUID().";";
	
	//print_r($sql);
        
      
			if(!$result = $this->ConexionBaseDatos($sql))
				return false;
	    else
         return true;
			
			$result->Close();
       
    }    

    function ConsultaItemTemporal($item_id)
		{
			$query = 	"
									SELECT 	*
									FROM	inv_bodegas_movimiento_tmp_d
									WHERE
									item_id= ".$item_id."
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
        function ConsultaDetalleVerificacion($doc_tmp_id,$item_id)
		{
			$query = 	"
									SELECT 	
                          idv.*,
                          ind.descripcion
									FROM	
                  inv_documento_verificacion_tmp_d idv,
                  inv_novedades_devoluciones ind
                  WHERE
                        idv.item_id= ".$item_id."
                  and   idv.doc_tmp_id = ".$doc_tmp_id."
                  and   idv.usuario_id = ".UserGetUID()."
                  and   idv.novedad_devolucion_id = ind.novedad_devolucion_id
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
    
    function ConsultaDocumentoVerificacionTmp($farmacia_id,$prefijo,$numero)
		{
			$query = 	"
									SELECT 	
                        *
									FROM	
                  inv_documento_verificacion_tmp
                  WHERE
                        farmacia_id= '".$farmacia_id."'
                  and   prefijo = '".$prefijo."'
                  and   numero = ".$numero." ";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
    
    
    function ConsultaDocumentoVerificacionTmp_d($farmacia_id,$prefijo,$numero)
		{
			$query = 	"
									SELECT 	
                      *
                  FROM	
                  inv_documento_verificacion_tmp_d
                  WHERE
                        farmacia_id = '".$farmacia_id."'
                  and   prefijo = '".$prefijo."'
                  and   numero = ".$numero."
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
    
    
      function ConsultaItemTemporal_($doc_tmp_id,$item_id)
		{
			$query = 	"
									SELECT 	*
									FROM	inv_bodegas_movimiento_tmp_d
									WHERE
									 doc_tmp_id= ".$doc_tmp_id."
									and lote_devuelto= '".$item_id."'
									and usuario_id =".UserGetUID().";
									";
			
			//print_r($query);
      if(!$result = $this->ConexionBaseDatos($query))
				return false;
	
			if($result->RecordCount() > 0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
			$result->Close();
			
			return $vars;
		}
/******************************************************************************
*funcion constructora 
*******************************************************************************/  
    
 
           

   /********************************************************************************
    * Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
    * importantes a la hora de referenciar al paginador
    * 
    * @param String Cadena que contiene la consulta sql del conteo 
    * @param int numero que define el limite de datos,cuando no se desa el del 
    *        usuario,si no se pasa se tomara por defecto el del usuario 
    * @return boolean 
    *********************************************************************************/
    function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
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

      if(!$result = $this->ConexionBaseDatos($consulta))
        return false;

      if(!$result->EOF)
      {
        $this->conteo = $result->fields[0];
        $result->MoveNext();
      }
      $result->Close();
      
      
      return true;
    }

 
 
 function PoliticasVencimiento($tercero_id)
    {
       $sql="SELECT 
                *
              FROM
                  inv_terceros_proveedores_politicasdevolucion
              WHERE
                tercero_id = '".$tercero_id."'
              ";
              
              //print_r($sql);
               if(!$resultado = $this->ConexionBaseDatos($sql))
                  return $this->frmError['MensajeError'];
                    
                  $cuentas=Array();
                  while(!$resultado->EOF)
                  {
                    $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                  }
                  
                  $resultado->Close();
                  
                  return $cuentas;
    }
  /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    * 
    * @param  string  $sql  sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
    * @return rst 
    ************************************************************************************/
    function ConexionBaseDatos($sql)
    {
      list($dbconn)=GetDBConn();
//     $dbconn->debug=true;
      $rst = $dbconn->Execute($sql);
        
      if ($dbconn->ErrorNo() != 0)
      {
        $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
         "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
        return false;
      }
      return $rst;
    }
    /**********************************************************************************
    * Funcion que permite crear una transaccion 
    * @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
    * @param char $num Numero correspondiente a la sentecia sql - por defect es 1
    *
    * @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
    *                se devuelve nada
    ***********************************************************************************/

  }
?>