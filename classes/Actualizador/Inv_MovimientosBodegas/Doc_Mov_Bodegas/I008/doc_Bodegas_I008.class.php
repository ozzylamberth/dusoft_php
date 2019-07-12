<?php
  /******************************************************************************
  * $Id: doc_Bodegas_I008.class.php,v 1.1 2009/07/17 19:08:17 johanna Exp $
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

class doc_bodegas_I008
{
var $mensajeDeError="";
  function DatosParaEditar($tmp_doc_id,$usuario_id)
  {
    $ClassDOC= new BodegasDocumentos();
    $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($tmp_doc_id,$usuario_id);
    return $datos;
  }
  
  function EliminarItem($doc_tmp_id,$item)
  {
     $sql  = "DELETE FROM inv_bodegas_movimiento_tmp_d ";
      $sql .= "WHERE ";
      $sql .= "doc_tmp_id = '".$doc_tmp_id."'";
      $sql .= " and ";
	    $sql .= "item_id = ".$item."";
  //print_r($sql);
	
	if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
				else
				return true;
  }
  
//   function SacarProductosTMP($doc_tmp_id,$usuario_id)
//    {  
//        $ClassDOC= new BodegasDocumentos();
//        $datos=$ClassDOC->GetInfoBodegaDocumentoTMP($doc_tmp_id,$usuario_id);
//        $OBJETO=$ClassDOC->GetOBJ($datos['bodegas_doc_id']);
//        $tabla_de_productos=$OBJETO->GetItemsDocTemporal($doc_tmp_id,$usuario_id);
//        return $tabla_de_productos;
//   }

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

    function CrearDoc($bodegas_doc_id, $observacion, $doc_devolucion)
    {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
         //PRINT_R($tipo_tercero);
       $OBJETO=$ClassDOC->GetOBJ();//($observacion,$tipo_prestamo_id, $tipo_id_tercero, $tercero_id, $usuario_id , $documento_compra, $fecha_doc_compra
       $RETORNO=$OBJETO->NewDocTemporal($observacion,UserGetUID(),$doc_devolucion);
      //    $RETORNO=$OBJETO->GetInfoBodegaDocumento($bodegas_doc_id);
        echo $OBJETO->Err().$OBJETO->ErrMsg();
       //var_dump($RETORNO);
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
    function BuscarProducto($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2,$offset)
    {
      $sql="SELECT 
                  b.codigo_producto,
                  fc_descripcion_producto(b.codigo_producto) as descripcion,
                  b.unidad_id,
                  c.descripcion as descripcion_unidad,
                  e.existencia_actual AS existencia,
                  d.costo,
                  e.fecha_vencimiento,
                  e.lote,
                  b.contenido_unidad_venta,
                  h.descripcion as laboratorio
              FROM
                  existencias_bodegas as a,
                  inventarios_productos as b,
                  unidades as c,
                  inventarios as d,
                  existencias_bodegas_lote_fv e,
                  inv_subclases_inventarios as z,
                  inv_moleculas as f,
                  inv_clases_inventarios as g,
                  inv_laboratorios as h
              WHERE
              a.empresa_id = '$empresa_id'
              AND a.centro_utilidad = '$centro_utilidad'
              AND a.bodega = '$bodega'
              ".$aumento."
              ".$aumento2."              
              AND   b.estado = '1'
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND d.empresa_id = a.empresa_id
              AND d.codigo_producto = a.codigo_producto
              AND   a.empresa_id = e.empresa_id
              AND   a.centro_utilidad = e.centro_utilidad
              AND   a.bodega = e.bodega
              AND   a.codigo_producto = e.codigo_producto 
              AND   b.grupo_id = z.grupo_id
              AND   b.subclase_id=z.subclase_id
              AND   b.clase_id=z.clase_id
              AND   z.molecula_id=f.molecula_id
              AND   b.grupo_id = g.grupo_id
              AND   b.clase_id=g.clase_id
              AND   g.laboratorio_id=h.laboratorio_id
              AND   e.existencia_actual>0
              AND   e.estado = '1'
              
              ";
              $sql .=" Order By b.descripcion ASC, e.fecha_vencimiento ASC ";
              $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
             // print_r($sql);
              $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
               //RETURN $sql;
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


    function ContarProStip($empresa_id,$centro_utilidad,$bodega,$aumento,$aumento2)
    {
            $sql="SELECT
                        count(*)
                  FROM
                      existencias_bodegas as a,
                      inventarios_productos as b,
                      unidades as c,
                      existencias_bodegas_lote_fv e,
                      inv_subclases_inventarios as z,
                      inv_moleculas as f,
                      inv_clases_inventarios as g,
                      inv_laboratorios as h
                  WHERE
                  a.empresa_id = '$empresa_id'
                  AND a.centro_utilidad = '$centro_utilidad'
                  AND a.bodega = '$bodega'
                  ".$aumento."
                  ".$aumento2." 
                  AND b.codigo_producto = a.codigo_producto
                  AND c.unidad_id = b.unidad_id
                  AND   b.grupo_id = z.grupo_id
                  AND   b.subclase_id=z.subclase_id
                  AND   b.clase_id=z.clase_id
                  AND   z.molecula_id=f.molecula_id
                  AND   b.grupo_id = g.grupo_id
                  AND   b.clase_id=g.clase_id
                  AND   g.laboratorio_id=h.laboratorio_id";


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
/**
        * Funcion donde 
       *
       * @return booleano
      */
  function PrestamoDevolDocumentos($tipo_id_tercero,$tercero_id,$tipo_doc_general)
  {
    //$this->debug=true;
     $sql  = "SELECT	a.*,b.descripcion ";
    $sql .= "FROM	   inv_bodegas_movimiento_prestamos as a, inv_bodegas_tipos_prestamos as b, inv_movimientos_bodegas_relacion_pre_dev as c, documentos as d ";
    $sql .= "WHERE	 a.tipo_id_tercero='".$tipo_id_tercero."' ";
    $sql .= "AND		   a.tercero_id=".$tercero_id." ";
    $sql .= "AND		   b.tipo_prestamo_id=a.tipo_prestamo_id ";
    $sql .= "AND       c.tipo_doc_general_id_e='".$tipo_doc_general."' ";
    $sql .= "AND       c.tipo_doc_general_id_i=d.tipo_doc_general_id ";
    $sql .= "AND       d.prefijo=a.prefijo ";
    //print_r($sql);
    if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
        
      if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     //$documentos=Array();
     if(!$resultado->EOF)
     {
       $documentos = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
     //print_r($documentos);
      return $documentos;
  }
  
  /*
	*	Funcion de Consulta SQL, que se encarga de buscar los EstadoDocumentos
	*	Existentes en el sistema, segun el grupo.
	*/
	function Consultar_ExistenciasBodegas($codigo_producto,$empresa_id,$centro_utilidad,$bodega,$doc_tmp_id)
	{
	//	$this->debug=true;
	$sql = "SELECT DISTINCT
	a.*,
	CASE
	WHEN b.item_id IS NOT NULL
	THEN ' disabled checked '
	ELSE ' '
	END as bloqueo
	FROM
	existencias_bodegas_lote_fv AS a
	LEFT JOIN inv_bodegas_movimiento_tmp_d as b ON (a.codigo_producto = b.codigo_producto)
	AND (a.lote = b.lote)
	AND (a.fecha_vencimiento = b.fecha_vencimiento)
	AND (b.doc_tmp_id =".$doc_tmp_id.")
	AND (b.usuario_id =".UserGetUID().") ";
	$sql .= " 		WHERE ";
	$sql .= "           a.codigo_producto = '".$codigo_producto."'  ";
	$sql .= "      and  a.empresa_id = '".$empresa_id."' ";
	$sql .= "      and  a.centro_utilidad = '".$centro_utilidad."' ";
	$sql .= "      and  a.bodega = '".$bodega."' ";
	$sql .= "      and  a.existencia_actual > 0 ";
	$sql .= "      and  a.estado = '1' ";
	$sql .= " ORDER BY a.fecha_vencimiento ASC ";

	/*print_r($sql);*/

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
  
  function ConsultarItemsExistencias($Codigo_Producto,$Empresa_Id,$Centro_Utilidad,$Bodega)
		{
			
      $query = 	"
									SELECT 	
                       codigo_producto,
                       existencia
									FROM	
                      existencias_bodegas
					WHERE 
                      empresa_id = '".trim($Empresa_Id)."'
                      and centro_utilidad = '".trim($Centro_Utilidad)."'
                      and bodega = '".trim($Bodega)."'
                      and codigo_producto = '".trim($Codigo_Producto)."'	";
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
		
    function AgregarItem($doc_tmp_id,$codigo_producto,$cantidad,$total_costo,$iva,$bodegas_doc_id,$lote,$fecha_vencimiento,$localizacion)
		{
			$ClassDOC= new BodegasDocumentosComun($bodegas_doc_id);
			$retorno = $ClassDOC->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$iva,$total_costo,UserGetUID(),$fecha_vencimiento,$lote,$localizacion);
			$this->mensajeDeError=$ClassDOC->mensajeDeError;
			return $retorno;
		}
  
   function ProductosDocumento($empresa_id,$prefijo,$numero,$codigo_barras,$descripcion,$bodegas_doc_id,$doc_tmp_id,$codigo_producto,$lote,$fecha_vencimiento)
  {
     if(!empty($codigo_barras))
          $filtro =" AND   b.codigo_barras = '".trim($codigo_barras)."'  ";
       if(!empty($codigo_producto))
          $filtro .=" AND   b.codigo_producto = '".trim($codigo_producto)."'  ";

       if(!empty($lote))
          $filtro .=" AND   a.lote = '".trim($lote)."'  ";
       if(!empty($lote))
          $filtro .=" AND   a.fecha_vencimiento = '".trim($fecha_vencimiento)."'  ";
      
	  $sql=" SELECT     
           a.codigo_producto,
		   a.lote,
		   a.fecha_vencimiento,
           a.porcentaje_gravamen as porc_iva,
           (a.total_costo/a.cantidad) as costo,
          (((a.cantidad)-((COALESCE(a.cantidad_recibida,0))))-(COALESCE(c.cantidad,0))) as cantidad,
           (COALESCE(c.cantidad,0)) as cantidad_temporal,
           fc_descripcion_producto(a.codigo_producto) as descripcion
           FROM     
           inv_bodegas_movimiento_d as a
           JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
           AND (a.cantidad <> COALESCE(a.cantidad_recibida,0))
           LEFT JOIN (
                SELECT
                x.codigo_producto,
				x.lote,
				x.fecha_vencimiento,
                SUM(x.cantidad)as cantidad
                FROM
                inv_bodegas_movimiento_tmp_d as x
                WHERE
                doc_tmp_id = ".trim($doc_tmp_id)."
                AND usuario_id = ".UserGetUID()."
                GROUP BY x.codigo_producto,x.lote,x.fecha_vencimiento
                ) as c ON (a.codigo_producto = c.codigo_producto)
				AND (a.lote = c.lote)
				AND (a.fecha_vencimiento = c.fecha_vencimiento)
			JOIN inventarios as d ON (a.codigo_producto = d.codigo_producto)
           AND (a.empresa_id = d.empresa_id)
           WHERE   
                    a.empresa_id = '".trim($empresa_id)."'
            AND     a.prefijo = '".trim($prefijo)."'
            AND     a.numero = ".trim($numero)."
            AND     b.descripcion ILIKE '%".trim($descripcion)."%'    			
            ".$filtro." ";
			
            //limit ".$this->limit." OFFSET ".$this->offset."";
        /*print_r($sql);*/
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
  
    function TmpInv_Movimientos($doc_tmp_id,$bodegas_doc_id)
  {
      $sql  = "SELECT * ";
      $sql .= "FROM   inv_bodegas_movimiento_tmp ";
      $sql .= "WHERE  doc_tmp_id = ".$doc_tmp_id."; ";
      $sql .= "AND      bodegas_doc_id = ".$bodegas_doc_id."; ";
    
     //print_r($sql);
      if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     //$documentos=Array();
     if(!$resultado->EOF)
     {
       $documentos = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
     //print_r($documentos);
      return $documentos;
  }

     function GuardarTemporal($bodegas_doc_id,$doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec)
     {
       $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id=null,$fecha_venc,$lotec);
       
       return $RETORNO;
     }
     function llamarhijo()
    {
    
      echo "llegamos";
      
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
   
   /************************************************************************************
*
*Funcion que saca los TERCEROS($pagina,$criterio1,$criterio2,$criterio);
*
*************************************************************************************/    
    function Terceros($pagina,$tipo_id,$id,$nombre,$op)    
    {   
      $whr = "";
      
      $sql  = "SELECT DISTINCT TE.* ";
      $sql .= "FROM   empresas TE ";
      $sql .= "WHERE TE.sw_tipo_empresa=1";
      /*switch($op)
      {
        case 1:
          $sql .= "   ,inv_bodegas_movimiento_prestamos IB ";
          $whr .= "AND  TE.tipo_id_tercero = IB.tipo_id_tercero 	";
          $whr .= "AND  TE.id = IB.tercero_id ";
        break;
      }*/
      //$sql .= "WHERE TRUE ";
      if($nombre)
        $sql .= "AND   TE.razon_social ILIKE '%".$nombre."%'";
      if($id)
        $sql .= "AND   TE.tercero_id='".$id."' ";
      if($tipo_id != "0" )
        $sql .= "AND   TE.id='".$tipo_id."' ";
      $sql .= $whr;
      //print_r($sql);
    $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$pagina);     
    
    $sql .= "ORDER BY TE.razon_social ";
    $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";   
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;    
    
    $cuentas=array();
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
      /*$dbconn->debug=true;*/
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
function GetInfoDocTemporal($bodegas_doc_id,$doc_tmp_id)
		{
			$ClassDOC= new BodegasDocumentos($bodegas_doc_id);
			$objeto=$ClassDOC->GetOBJ();
			return $objeto->GetDocTemporal($doc_tmp_id,UserGetUID());
		}
  }
?>