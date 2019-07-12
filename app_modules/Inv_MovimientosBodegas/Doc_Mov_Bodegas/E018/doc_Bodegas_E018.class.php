<?php
  /******************************************************************************
  * $Id: doc_Bodegas_E018.class.php,v 1.1 2009/07/17 19:08:17 mauricio Exp $
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


	if(!IncludeClass('BodegasDocumentosComun','BodegasDocumentos'))
	{
		die(MsgOut("Error al incluir archivo","BodegasDocumentosComun"));
	}
	
class doc_bodegas_E018
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
    function CrearDoc($bodegas_doc_id, $observacion, $plan_id,$tipo_formula_id,$requisicion)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();//($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra
       $RETORNO=$OBJETO->NewDocTemporal($observacion,$plan_id,$tipo_formula_id,$requisicion,UserGetUID());
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
    
    
    function ProductosDocumento($empresa_id,$centro_utilidad,$bodega,$codigo_barras,$descripcion,$bodegas_doc_id,$tmp_doc_id,$offset)
    {
       if(!empty($codigo_barras))
          $filtro = " d.codigo_barras = '".$codigo_barras."'  ";
       
       $sql="SELECT 
                  a.*,
                  c.costo,
                  d.porc_iva,
                  fc_descripcion_producto(a.codigo_producto) as descripcion
                  FROM
                  existencias_bodegas_lote_fv a
                  JOIN inventarios as c ON (a.empresa_id = c.empresa_id) 
                  and (a.codigo_producto = c.codigo_producto)
                  JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
              WHERE
                    a.empresa_id = '".$empresa_id."'
              AND   a.centro_utilidad = '".$centro_utilidad."'
              AND   a.bodega = '".$bodega."'
              AND   a.existencia_actual > 0 
              AND   d.descripcion ILIKE '%".$descripcion."%' 
              AND   a.codigo_producto||''||a.lote NOT IN (
                                                      Select
                                                      codigo_producto||''||lote
                                                      from
                                                      inv_bodegas_movimiento_tmp_d
                                                      where
                                                          usuario_id = ".UserGetUID()."
                                                      and doc_tmp_id = ".$tmp_doc_id."
                                                      )
              ".$filtro."
              ";
             // print_r($sql);
              $sql .= " Order By d.descripcion ASC, a.fecha_vencimiento ASC ";
              $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
              
              $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
 
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

    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$codigo_barras,$descripcion,$bodegas_doc_id,$tmp_doc_id)
    {
       if(!empty($codigo_barras))
          $filtro = " d.codigo_barras = '".$codigo_barras."'  ";
       
       $sql="SELECT 
                  count(*)
                  FROM
                  existencias_bodegas_lote_fv a
                  LEFT JOIN existencias_bodegas as b ON (a.empresa_id = b.empresa_id) 
                  and (a.centro_utilidad = b.centro_utilidad) and (a.bodega = b.bodega)
                  and (a.codigo_producto = b.codigo_producto)
                  JOIN inventarios as c ON (b.empresa_id = c.empresa_id) 
                  and (b.codigo_producto = c.codigo_producto)
                  JOIN inventarios_productos as d ON (c.codigo_producto = d.codigo_producto)
              WHERE
                    a.empresa_id = '".$empresa_id."'
              AND   a.centro_utilidad = '".$centro_utilidad."'
              AND   a.bodega = '".$bodega."'
              AND   a.existencia_actual > 0 
              AND   d.descripcion ILIKE '%".$descripcion."%'
              AND   a.codigo_producto||''||a.lote NOT IN (
                                                      Select
                                                      codigo_producto||''||lote
                                                      from
                                                      inv_bodegas_movimiento_tmp_d
                                                      where
                                                          usuario_id = ".UserGetUID()."
                                                      and doc_tmp_id = ".$tmp_doc_id."
                                                      )              
              ".$filtro."
              ";
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
    
    
      function ConsultaItemTemporal_($doc_tmp_id,$Formulario,$i)
		{
			$query = 	"
									SELECT 	*
									FROM	inv_bodegas_movimiento_tmp_d
									WHERE
                      doc_tmp_id= ".$doc_tmp_id."
									and codigo_producto= '".$Formulario['codigo_producto'.$i]."'
									and lote= '".$Formulario['lote'.$i]."'
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

	
	function planes_parametrizados()
		{
		
		$sql ="SELECT   plan_id,
		plan_descripcion,
		tipo_tercero_id,
		tercero_id
		FROM     planes
		WHERE     estado='1'
		and       sw_afiliados='1'
		order by empresa_id,plan_descripcion;";

		if(!$rst = $this->ConexionBaseDatos($sql))	return false;
		$datos = array();
		while (!$rst->EOF)
		{
		$datos []= $rst->GetRowAssoc($ToUpper = false);
		$rst->MoveNext();
		}
		$rst->Close();
		return $datos;
		}
		
		function  ConsultarTiposDispensacion($datos)
		{
		
		$sql = " SELECT 
						a.tipo_formula_id,
						a.descripcion_tipo_formula,
						CASE
						WHEN c.tipo_formula_id IS NOT NULL AND b.tipo_formula_id IS NULL AND ('1' = d.sw_topes)
						THEN c.tope_mensual
						WHEN c.tipo_formula_id IS NOT NULL AND b.tipo_formula_id IS NOT NULL AND ('1' = d.sw_topes)
						THEN b.saldo
						END as tope
		FROM   esm_tipos_formulas as a
				   LEFT JOIN esm_topes_dispensacion_mensual AS b ON (a.tipo_formula_id = b.tipo_formula_id)
				   AND (b.empresa_id = '".trim($datos['empresa_id'])."')
				   AND (b.centro_utilidad = '".trim($datos['centro_utilidad'])."')
				   AND (b.lapso = '".(date('Ym'))."')
				   LEFT JOIN esm_topes_dispensacion as c ON (a.tipo_formula_id = c.tipo_formula_id)
				   AND (c.empresa_id = '".trim($datos['empresa_id'])."')
				   AND (c.centro_utilidad = '".trim($datos['centro_utilidad'])."'),
				   (
					   SELECT
					   sw_topes
					   FROM
					   centros_utilidad
					   WHERE TRUE
						AND empresa_id = '".trim($datos['empresa_id'])."'
						AND centro_utilidad = '".trim($datos['centro_utilidad'])."'
				   ) as d
				   WHERE  a.sw_estado = '1'  
		ORDER BY a.descripcion_tipo_formula ASC  ";
		
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
    //$dbconn->debug=true;
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