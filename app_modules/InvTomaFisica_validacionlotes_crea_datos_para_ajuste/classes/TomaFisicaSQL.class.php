<?php
  /******************************************************************************
  * $Id: TomaFisicaSQL.class.php,v 1.16 2010/02/01 21:17:48 johanna Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.16 $ 
	* 
	* @autor Jaime Gomez
  ********************************************************************************/
	
  
class TomaFisicaSQL 
{
    /**
    * Codigo de error
    *
    * @var string
    * @access public
    */
    var $error;
    
    /**
    * Mensaje de error
    *
    * @var string
    * @access public
    */
    var $mensajeDeError;

    function TomaFisicaSQL() {}

    function AddUsuarioConteo($toma,$usuario_id)
    {
        $sql  = "   INSERT INTO inv_toma_fisica_usuarios_conteo(toma_fisica_id,  usuario_id)
                    VALUES ($toma,$usuario_id);
        ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        return true;
    } 


    function AddUsuarioValidacion($toma,$usuario_id)
    {
        $sql  = "   INSERT INTO inv_toma_fisica_usuarios_validacion(toma_fisica_id,usuario_id)
                    VALUES ($toma,$usuario_id);
        ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        return true;
    } 

    
    function Sacar_Usuarios_Toma_Validacion($toma_fisica)
    {

        $sql="SELECT
                    a.usuario_id,
                    a.usuario,
                    a.nombre
                FROM
                    system_usuarios AS a,
                    inv_toma_fisica_usuarios_validacion AS b
                WHERE
                    a.usuario_id=b.usuario_id
                AND b.toma_fisica_id=$toma_fisica";

        if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

    }



    function Sacar_Usuarios_Toma_Conteo($toma_fisica)
    {

        $sql="SELECT
                    a.usuario_id,
                    a.usuario,
                    a.nombre
                FROM
                    system_usuarios AS a,
                    inv_toma_fisica_usuarios_conteo AS b
                WHERE
                    a.usuario_id=b.usuario_id
                AND b.toma_fisica_id=$toma_fisica";

        if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

    }


    /**
    * Metodo para obtener los usuarios del sistema que no estan en el sistema de EPS
    *
    * @param array $filtros
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetSystemUsersForConteo($filtros=array(), $count=null, $limit=null, $offset=null,$toma)
    {
        if($filtros['tipo_u']==='usuario_id' && $filtros['valor']!='')
        {
           $filtro = " AND a.usuario_id = " . $filtros['valor'] . " ";
        }
        elseif($filtros['tipo_u']==='usuario' && $filtros['valor']!='' )
        {
           $filtro = " AND a.usuario = '" . $filtros['valor'] . "' ";
        }
        elseif($filtros['tipo_u']==='nombre' && $filtros['valor']!='' )
        {
           $filtro = " AND a.nombre ILIKE '%" . $filtros['valor'] . "%' ";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if(empty($count))
        {
            $select = "
                        a.usuario_id,
                        a.usuario,
                        a.nombre,
                        b.toma_fisica_id
            ";
            $select1 = " z.*";

            $filtro .= " ORDER BY a.nombre ";
        }
        else
        {   $select = "
                        a.usuario_id,
                        a.usuario,
                        a.nombre,
                        b.toma_fisica_id
            ";
            $select1 = " COUNT(z.*) as cantidad";
        }

        $sql  = "

                    SELECT $select1
                    FROM
                    (
                        SELECT $select
                        FROM
                        system_usuarios as a
                        LEFT JOIN
                        (
                            SELECT *
                            FROM
                            inv_toma_fisica_usuarios_conteo AS inv
                            WHERE
                            inv.toma_fisica_id=$toma
                        )  as b
                        ON (b.usuario_id = a.usuario_id)
    
                        WHERE
                        a.activo = '1'
                        $filtro
                    
                    ) as z
                    WHERE
                    z.toma_fisica_id IS NULL
            $filtro_limit;
        ";

        if(!$result = $this->ConexionBaseDatos($sql))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        if(empty($count))
        {
            $retorno = array();

            while(!$result->EOF)
            {
                $retorno[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }

            $result->Close();

        }
        else
        {
            $fila = $result->GetRowAssoc($ToUpper = false);
            $retorno = $fila['cantidad'];
        }

        return  $retorno;

    }

    
     /**
    * Metodo para obtener los usuarios del sistema que no estan en el sistema de EPS
    *
    * @param array $filtros
    * @param boolean $count (opcional) True/False indica si retorna el numero de registros o los registros
    * @param integer $limit (opcional) limite de registros que retorna la consulta
    * @param integer $offset (opcional) desde que registro inicia la consulta
    * @return array
    * @access public
    */
    function GetSystemUsersForValidacion($filtros=array(), $count=null, $limit=null, $offset=null,$toma)
    {
        if($filtros['tipo_u']==='usuario_id' && $filtros['valor']!='')
        {
           $filtro = " AND a.usuario_id = " . $filtros['valor'] . " ";
        }
        elseif($filtros['tipo_u']==='usuario' && $filtros['valor']!='')
        {
           $filtro = " AND a.usuario = '" . $filtros['valor'] . "' ";
        }
        elseif($filtros['tipo_u']==='nombre' && $filtros['valor']!='')
        {
           $filtro = " AND a.nombre ILIKE '%" . $filtros['valor'] . "%' ";
        }

        if(is_numeric($limit) && is_numeric($offset))
        {
            $filtro_limit = " LIMIT $limit OFFSET $offset ";
        }
        else
        {
            $filtro_limit = "";
        }

        if(empty($count))
        {
            $select = "
                        a.usuario_id,
                        a.usuario,
                        a.nombre,
                        b.toma_fisica_id
            ";
            $select1 = " z.*";

            $filtro .= " ORDER BY a.nombre ";
        }
        else
        {
            $select = "
                        a.usuario_id,
                        a.usuario,
                        a.nombre,
                        b.toma_fisica_id
            ";
            $select1 = " COUNT(z.*) as cantidad";
        }

       $sql  = "
                    SELECT $select1
                    FROM
                    (
                        SELECT $select
                        FROM
                        system_usuarios as a
                        
                        LEFT JOIN
                        (
                            SELECT *
                            FROM
                            inv_toma_fisica_usuarios_validacion as inv
                            
                            WHERE
                            inv.toma_fisica_id=$toma
                        )  as b
                        ON (b.usuario_id = a.usuario_id)
                        
                        WHERE
                        a.activo = '1'
                        AND b.usuario_id IS NULL
                        $filtro
                        
                    ) as z
                    WHERE
                    z.toma_fisica_id IS NULL
                    $filtro_limit;
        ";

        if(!$result = $this->ConexionBaseDatos($sql,true))
        {
            $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
            return false;
        }

        if(empty($count))
        {
            $retorno = array();

            while(!$result->EOF)
            {

                $retorno[] = $result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }

            $result->Close();
            
            
        }
        else
        {
            $fila = $result->GetRowAssoc($ToUpper = false);
            $retorno = $fila['cantidad'];
        }

        return  $retorno;

    }

    function ListarDocumentosIngreso($empresa,$cu,$bodega,$usuario)
    {

        $sql="SELECT
            a.empresa_id,
            a.centro_utilidad,
            a.bodega,
            a.bodegas_doc_id,
            b.prefijo,
            b.descripcion
            FROM
            (
                SELECT
                    documento_id
                FROM
                    inv_bodegas_userpermisos
                WHERE
                    usuario_id = '$usuario'
                    AND empresa_id = '$empresa'
                    AND centro_utilidad = '$cu'
                    AND bodega = '$bodega'
            ) AS u,
            inv_bodegas_documentos as a,
            documentos as b
            WHERE
            a.documento_id = u.documento_id
            AND a.empresa_id = '$empresa'
            AND a.centro_utilidad = '$cu'
            AND a.bodega = '$bodega'
            AND b.documento_id = a.documento_id
            AND b.empresa_id = a.empresa_id
            AND b.tipo_doc_general_id = 'I003'";
            if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
            $datos=Array();
            while(!$resultado->EOF)
            {

                $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            return $datos;


    }



/**
* funcion que se utiliza para listar los documentos de egreso
*
***/

function ListarDocumentosEgreso($empresa,$cu,$bodega,$usuario)
    {

        $sql="SELECT
            a.empresa_id,
            a.centro_utilidad,
            a.bodega,
            a.bodegas_doc_id,
            b.prefijo,
            b.descripcion
            FROM
            (
                SELECT
                    documento_id
                FROM
                    inv_bodegas_userpermisos
                WHERE
                    usuario_id = '$usuario'
                    AND empresa_id = '$empresa'
                    AND centro_utilidad = '$cu'
                    AND bodega = '$bodega'
            ) AS u,
            inv_bodegas_documentos as a,
            documentos as b
            WHERE
            a.documento_id = u.documento_id
            AND a.empresa_id = '$empresa'
            AND a.centro_utilidad = '$cu'
            AND a.bodega = '$bodega'
            AND b.documento_id = a.documento_id
            AND b.empresa_id = a.empresa_id
            AND b.tipo_doc_general_id = 'E003'";
          
            if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
            $datos=Array();
            while(!$resultado->EOF)
            {

                $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }

            $resultado->Close();
            return $datos;


    }
 //  busqueda para el informe   
function BuscarConteoValor($empresa,$bodega,$centro,$cabecera){

            $sql="select *
                    from
                    (
                
                    select 
                        a.codigo_producto,
                        fc_descripcion_producto(a.codigo_producto) as nombre,
                        case when ((fc_costo_producto('$empresa',a.codigo_producto)*(sum(a.cantidad)-sum(a.existencia_actual)))  is null) then 0.00 else (fc_costo_producto('$empresa',a.codigo_producto)*(sum(a.cantidad)-sum(a.existencia_actual))) end as valor,
                        case when ((sum(a.cantidad)-sum(a.existencia_actual)) is null) then 0 else (sum(a.cantidad)-sum(a.existencia_actual)) end  as diferencia,
                        sum(a.cantidad) as conteo,
                        sum(a.existencia_actual) as stock

                 from
                         (
                                       (
                         select c.empresa_id,
                                c.bodega,
                                c.codigo_producto,
                                c.lote,
                                c.cantidad,
                                fecha_vencimiento,
                                c.existencia_actual,
                                     abs(cantidad - existencia_actual) as diferente,case when ((cantidad - existencia_actual))>0 then 'I'    when ((cantidad - existencia_actual))=0 then 'NA'  else 'E' end as caso
                                      from (
                                             select a.empresa_id,a.bodega,a.codigo_producto,a.lote,a.fecha_vencimiento,
                                             (case when b.existencia_actual is null then 0 else b.existencia_actual end )as existencia_actual,b.fecha_vencimiento as c,sum(a.cantidad) as cantidad
                                             from inv_conteo_tomas_fisicas_detalle as a
                                             left join existencias_bodegas_lote_fv as b on
                                             (a.empresa_id=b.empresa_id and a.bodega=b.bodega and a.centro_utilidad=b.centro_utilidad and a.lote=b.lote and a.codigo_producto=b.codigo_producto and b.fecha_vencimiento =a.fecha_vencimiento )
                                              where a.empresa_id='$empresa' and a.bodega='$bodega' and a.centro_utilidad='$centro' and a.conteo= '2' and a.id_conteo_toma_fisica='$cabecera'
                                              group by 1,2,3,4,5,6,7

                                         ) as c

                         )

                         union

                               (
                          select a.empresa_id,a.bodega,a.codigo_producto,a.lote,0 as cantidad,a.fecha_vencimiento,a.existencia_actual,
                                     abs(b.cantidad - a.existencia_actual) as diferente,case when ((b.cantidad - a.existencia_actual))>0 then 'I' when ((b.cantidad - a.existencia_actual))=0 then 'NA'  else 'E' end as caso
                         from existencias_bodegas_lote_fv as a
                         inner join
                                     ( (
                                         select
                                               empresa_id,bodega,codigo_producto,lote,0 as cantidad,fecha_vencimiento ,0 as existencia_actual
                                         from existencias_bodegas_lote_fv
                                         where empresa_id='$empresa' and bodega='$bodega' and centro_utilidad='$centro')
                                    except
                                          (select
                                                 empresa_id,bodega,codigo_producto,lote,0 as cantidad,fecha_vencimiento,0 as existencia_actual
                                           from inv_conteo_tomas_fisicas_detalle
                                           where empresa_id='$empresa' and bodega='$bodega' and centro_utilidad='$centro' and conteo='2' and id_conteo_toma_fisica='$cabecera'
                                          ))  as b
                                           on (a.empresa_id=b.empresa_id and a.bodega=b.bodega and a.codigo_producto=b.codigo_producto and a.lote=b.lote and  a.fecha_vencimiento=b.fecha_vencimiento)

                                 ) ) as a 
                 group by 1 
                 ) as s
                     where diferencia <> 0
                    order by valor ,diferencia asc ,stock,conteo desc ;";
         
           if(!$resultado = $this->ConexionBaseDatos($sql))
              return false;                    
            $datos=Array();
            while(!$resultado->EOF)
            {
                $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                $resultado->MoveNext();
            }
            $resultado->Close();
             return $datos;
}    

function GetProductosParaAjustarPorIngresoEgreso_inventario($empresa,$centro_utilidad,$bodega,$cabecera,$bandera){
    $sql="	
	select * from
	(
		      (
	select c.empresa_id,c.bodega,c.codigo_producto,c.lote,c.cantidad,fecha_vencimiento,c.existencia_actual, 
		    abs(cantidad - existencia_actual) as diferente,case when ((cantidad - existencia_actual))>0 then 'I'    when ((cantidad - existencia_actual))=0 then 'NA'  else 'E' end as caso
		     from (               
			    select a.empresa_id,a.bodega,a.codigo_producto,a.lote,a.fecha_vencimiento,
			    (case when b.existencia_actual is null then 0 else b.existencia_actual end )as existencia_actual,b.fecha_vencimiento as c,sum(a.cantidad) as cantidad
			    from inv_conteo_tomas_fisicas_detalle as a 
			    left join existencias_bodegas_lote_fv as b on 
			    (a.empresa_id=b.empresa_id and a.bodega=b.bodega and a.centro_utilidad=b.centro_utilidad and a.lote=b.lote and a.codigo_producto=b.codigo_producto and b.fecha_vencimiento =a.fecha_vencimiento )
			     where a.empresa_id='$empresa' and a.bodega='$bodega' and a.centro_utilidad='$centro_utilidad' and a.conteo='2' and a.id_conteo_toma_fisica='$cabecera' 
			     group by 1,2,3,4,5,6,7
			    
			) as c 

	)
	
	union

	      (
	 select a.empresa_id,a.bodega,a.codigo_producto,a.lote,0 as cantidad,a.fecha_vencimiento,a.existencia_actual, 
		    abs(b.cantidad - a.existencia_actual) as diferente,case when ((b.cantidad - a.existencia_actual))>0 then 'I' when ((b.cantidad - a.existencia_actual))=0 then 'NA'  else 'E' end as caso
	from existencias_bodegas_lote_fv as a
	inner join 
		    ( (   
			select 
			      empresa_id,bodega,codigo_producto,lote,0 as cantidad,fecha_vencimiento ,0 as existencia_actual	    
			from existencias_bodegas_lote_fv 
			where empresa_id='$empresa' and bodega='$bodega' and centro_utilidad='$centro_utilidad')
		   except 
			 (select 
				empresa_id,bodega,codigo_producto,lote,0 as cantidad,fecha_vencimiento,0 as existencia_actual
			  from inv_conteo_tomas_fisicas_detalle
			  where empresa_id='$empresa' and bodega='$bodega' and centro_utilidad='$centro_utilidad' and conteo='2' and id_conteo_toma_fisica='$cabecera'
			 ))  as b
			  on (a.empresa_id=b.empresa_id and a.bodega=b.bodega and a.codigo_producto=b.codigo_producto and a.lote=b.lote and  a.fecha_vencimiento=b.fecha_vencimiento)

		) ) as f 
;";

       if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
            $datos=Array();
            while(!$resultado->EOF)
            {
                $datos = $resultado->GetRowAssoc($ToUpper = false);
                if($bandera==$datos['caso']){
                    $value[] = $resultado->GetRowAssoc($ToUpper = false);
                }
                $resultado->MoveNext();
            }

            $resultado->Close();
            return $value;
}
    
    //$toma_fisica ==cabecera
    function CrearDocumento_Ingreso($empresa,$centro_utilidad,$bodega,$cabecera,$bodega_doc_id)
    {
        //var_dump($bodega_doc_id);
        if(!IncludeClass("BodegasDocumentos"))
        { 
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentos]";
            return false;
        }
        if(!IncludeClass("BodegasDocumentosComun","BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentosComun]";
            return false;
        }
        
        $observacion="PRODUCTOS PARA AJUSTAR POR INGRESO TOMA FISICA ".$cabecera;
        $datosPro=$this->GetProductosParaAjustarPorIngresoEgreso_inventario($empresa,$centro_utilidad,$bodega,$cabecera,'I');
        //var_dump($datosPro);

        $ClassDOC = new BodegasDocumentos($bodega_doc_id);
        if(!is_object($ClassDOC))
        {
            $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
            return false;
        }
         //var_dump($ClassDOC);
        $objeto = $ClassDOC->GetOBJ();
         //var_dump($objeto);
        if(!is_object($objeto))
        {
            $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL METODO [GetOBJ] ".__LINE__;
            return false;
        }
        /*$doc_temporal=$objeto->NewDocTemporal($observacion);*/
		$doc_temporal=$objeto->NewDocTemporal($observacion,NULL,NULL,NULL,NULL,$cabecera);
                
        //var_dump($doc_temporal);
        if($doc_temporal===false)
        { 
            $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
              
            return false;
        }
      /*
       * $ClassDOC= new BodegasDocumentos($bodegas_doc_id);
       $OBJETO=$ClassDOC->GetOBJ();
       $RETORNO=$OBJETO->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$porcentaje_gravamen,$total_costo,$usuario_id=null,$fecha_venc,$lotec);
       //var_dump($RETORNO);
       return $RETORNO;
        */
       
        foreach($datosPro as $key => $valor)
        { 
            $fechav=explode("-",$valor['fecha_vencimiento']);
            $fechavencimiento=$fechav[2]."-".$fechav[1]."-".$fechav[0];
            //costo no esta en la tabla se remplazo por 0 ;$valor['cantidad']*$valor['costo']; 
            $costo=$this->CostoProducto($empresa,$valor['codigo_producto']);
            $costo=$costo>0?$costo:1;
            $retorno=$objeto->AddItemDocTemporal($doc_temporal['doc_tmp_id'], $valor['codigo_producto'], $valor['diferente'], $porcentaje_gravamen=0, ( $valor['diferente']*$costo),null,$fechavencimiento,$valor['lote'],null);
           // var_dump($retorno);
            if($retorno===false)
            {
                $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError; 
                return false;
            }
        }
    
    
        $retorno=$objeto->CrearDocumento($doc_temporal['doc_tmp_id']);
        //var_dump($retorno." ----->>><<<<----   oc_temporal['doc_tmp_id']".$doc_temporal['doc_tmp_id']);
        if($retorno===false)
        {
            $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
            return false;
        }

        $sql="  UPDATE inv_conteo_tomas_fisicas SET 
                estado = '0',
                usuario_ajuste ='".UserGetUID()."',
                fecha_ajuste= now()
                WHERE id_conteo_toma_fisica = '$cabecera'; ";
                    
        global $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al ejecutar actualizacion";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
            
        
        return $retorno;
    }
    
    function CostoProducto($empresa,$codigo_producto){
        $sql=" select fc_costo_producto('$empresa','$codigo_producto') as costo";
         if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
            $datos=Array();
            while(!$resultado->EOF)
            {
                $datos = $resultado->GetRowAssoc($ToUpper = false);
                if($bandera==$datos['caso']){
                    $value = $resultado->GetRowAssoc($ToUpper = false);
                }
                $resultado->MoveNext();
            }

            $resultado->Close();
            return $value['costo'];
    }
    
     function CrearDocumento_Egreso($empresa,$centro_utilidad,$bodega,$cabecera,$bodega_doc_id)
     { 
        //var_dump($bodega_doc_id);
        if(!IncludeClass("BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentos]";
            return false;
        }
        if(!IncludeClass("BodegasDocumentosComun","BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentosComun]";
            return false;
        }
        
        $observacion="PRODUCTOS PARA AJUSTAR POR EGRESO TOMA FISICA ".$cabecera;        
        $datosPro=$this->GetProductosParaAjustarPorIngresoEgreso_inventario($empresa,$centro_utilidad,$bodega,$cabecera,'E');
      

         $ClassDOC = new BodegasDocumentos($bodega_doc_id);
        
         if(!is_object($ClassDOC))
         { 
             $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
             return false;
         }
          //var_dump($ClassDOC);
         $objeto = $ClassDOC->GetOBJ();
		 
         //var_dump($objeto);
         if(!is_object($objeto))
         {
             $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL METODO [GetOBJ] ".__LINE__;
             return false;
         }
//         
      
          $doc_temporal=$objeto->NewDocTemporal($observacion,NULL,NULL,NULL,NULL,$cabecera);
        
          if($doc_temporal===false)
          {
              $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
              return false;
          }
         
  
          foreach($datosPro as $key => $valor)
          { 
              $fechav=explode("-",$valor['fecha_vencimiento']);
              $fechavencimiento=$fechav[2]."-".$fechav[1]."-".$fechav[0];
              $costo=$this->CostoProducto($empresa,$valor['codigo_producto']);
              $costo=$costo>0?$costo:1;
              //AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$localizacion)//$valor['costo']
              
              $retorno=$objeto->AddItemDocTemporal($doc_temporal['doc_tmp_id'], $valor['codigo_producto'], $valor['diferente'], $porcentaje_gravamen=0, ( $valor['diferente']*$costo),null,$fechavencimiento,$valor['lote'],null);
              
             if($retorno===false)
              {
                  $this->mensajeDeError=$objeto->error."<br>AQUI".$objeto->mensajeDeError;
                  return false;
              }
          }


        $retorno=$objeto->CrearDocumento($doc_temporal['doc_tmp_id']);
       
		
		 
          if(!$retorno)
          {
              $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
		//print_r($this->mensajeDeError);
              return false;
          }

        $sql="  UPDATE inv_conteo_tomas_fisicas SET 
                  estado_egreso = '1',
                usuario_ajuste ='".UserGetUID()."',
                fecha_ajuste= now()    
                WHERE id_conteo_toma_fisica = '$cabecera'; ";
        
          global $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();

          $dbconn->Execute($sql);
          if($dbconn->ErrorNo() != 0)
          {
             $this->error = "Error al ejecutar actualizacion";
             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
             return false;
          }
		/*print_r($retorno);*/
         
         return $retorno;
     }
     
     function Backup_existencias_bodegas_lote($cabecera,$empresa,$centro_utilidad,$bodega){
         $sql="insert into inv_saldos_iniciales 
                                 (
                                        empresa_id,
                                        centro_utilidad,
                                        codigo_producto,
                                        bodega,
                                        fecha_vencimiento,
                                        lote,
                                        existencia_inicial,
                                        existencia_actual,
                                        estado,
                                        fecha_registro,
                                        ubicacion_id,
                                        id_conteo_toma_fisica
                                       )
                                select 
                                empresa_id,
                                centro_utilidad,
                                codigo_producto,
                                bodega,
                                fecha_vencimiento,
                                lote,
                                existencia_inicial,
                                existencia_actual,
                                estado,
                                fecha_registro,
                                ubicacion_id,
                                '$cabecera'
                                from  
                                existencias_bodegas_lote_fv
                                where empresa_id='$empresa' and bodega='$bodega' and centro_utilidad='$centro_utilidad';";
     
       
         if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          //echo "<pre>".$sql;
          return false;  
        }else {             
           return true;
         } 
     } 

    
    function CrearDocumento($toma_fisica,$bodega_doc_id)
    {
        //var_dump($bodega_doc_id);
        if(!IncludeClass("BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentos]";
            return false;
        }
        if(!IncludeClass("BodegasDocumentosComun","BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentosComun]";
            return false;
        }
        
        $observacion="PRODUCTOS PARA AJUSTAR POR INGRESO TOMA FISICA ".$toma_fisica;
        $datosPro=$this->GetProductosParaAjustarPorIngreso($toma_fisica);
        //var_dump($datosPro);

        $ClassDOC = new BodegasDocumentos($bodega_doc_id);
        if(!is_object($ClassDOC))
        {
            $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
            return false;
        }
         //var_dump($ClassDOC);
        $objeto = $ClassDOC->GetOBJ();
         //var_dump($objeto);
        if(!is_object($objeto))
        {
            $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL METODO [GetOBJ] ".__LINE__;
            return false;
        }
        /*$doc_temporal=$objeto->NewDocTemporal($observacion);*/
		$doc_temporal=$objeto->NewDocTemporal($observacion,NULL,NULL,NULL,NULL,$toma_fisica);
                
        //var_dump($doc_temporal);
        if($doc_temporal===false)
        {
            $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
              
            return false;
        }
      
        foreach($datosPro as $key => $valor)
        { 
            $fechav=explode("-",$valor['fecha_vencimiento']);
            $fechavencimiento=$fechav[2]."-".$fechav[1]."-".$fechav[0];
            $retorno=$objeto->AddItemDocTemporal($doc_temporal['doc_tmp_id'], $valor['codigo_producto'], $valor['cantidad'], $porcentaje_gravamen=0, ( $valor['cantidad']*$valor['costo']),null,$fechavencimiento,$valor['lote'],null);
            //var_dump($retorno);
            if($retorno===false)
            {
                $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
                return false;
            }
        }
    
        
        $retorno=$objeto->CrearDocumento($doc_temporal['doc_tmp_id']);
        //var_dump($retorno."jsdkjdj");
        if($retorno===false)
        {
            $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
            return false;
        }

        $sql="  UPDATE inv_toma_fisica_update SET sw_actualizado = '1'
                WHERE toma_fisica_id = $toma_fisica
                    AND etiqueta IN
                    (
                SELECT
				a.etiqueta	
                FROM inv_toma_fisica_update AS a,inv_toma_fisica_d as b
                WHERE a.nueva_existencia > a.existencia
                AND a.toma_fisica_id = $toma_fisica
                AND a.toma_fisica_id=b.toma_fisica_id
                AND a.etiqueta=b.etiqueta
                AND sw_actualizado IS NULL
                    ); ";
                    
        global $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $dbconn->Execute($sql);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al ejecutar actualizacion";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
            
        
        return $retorno;
    }


     function CrearDocumentoEgreso($toma_fisica,$bodega_doc_id)
     { 
        //var_dump($bodega_doc_id);
        if(!IncludeClass("BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentos]";
            return false;
        }
        if(!IncludeClass("BodegasDocumentosComun","BodegasDocumentos"))
        {
            $this->FrmError['MensajeError']="ERROR: NO SE PUEDE INCLUIR DE LA CLASE [BodegasDocumentosComun]";
            return false;
        }
        
        $observacion="PRODUCTOS PARA AJUSTAR POR EGRESO TOMA FISICA ".$toma_fisica;
        $datosPro=$this->GetProductosParaAjustarPorEgreso($toma_fisica);
        /*print_r($datosPro);*/

         $ClassDOC = new BodegasDocumentos($bodega_doc_id);
         //var_dump($ClassDOC);
         if(!is_object($ClassDOC))
         {
             $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
             return false;
         }
          //var_dump($ClassDOC);
         $objeto = $ClassDOC->GetOBJ();
		 
         //var_dump($objeto);
         if(!is_object($objeto))
         {
             $this->mensajeDeError="ERROR AL CREAR UNA INSTANCIA DEL METODO [GetOBJ] ".__LINE__;
             return false;
         }
//         
          $doc_temporal=$objeto->NewDocTemporal($observacion,NULL,NULL,NULL,NULL,$toma_fisica);
      
          if($doc_temporal===false)
          {
              $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
              return false;
          }
          
  
          foreach($datosPro as $key => $valor)
          { 
              $fechav=explode("-",$valor['fecha_vencimiento']);
              $fechavencimiento=$fechav[2]."-".$fechav[1]."-".$fechav[0];
              //AddItemDocTemporal($doc_tmp_id, $codigo_producto, $cantidad, $porcentaje_gravamen, $total_costo, $usuario_id=null,$fecha_venc,$lotec,$localizacion)
              $retorno=$objeto->AddItemDocTemporal($doc_temporal['doc_tmp_id'], $valor['codigo_producto'], $valor['cantidad'], $porcentaje_gravamen=0, ( $valor['cantidad']*$valor['costo']),null,$fechavencimiento,$valor['lote'],null);
              
             if($retorno===false)
              {
                  $this->mensajeDeError=$objeto->error."<br>AQUI".$objeto->mensajeDeError;
                  return false;
              }
          }

          
        $retorno=$objeto->CrearDocumento($doc_temporal['doc_tmp_id']);
         
		
		 
          if(!$retorno)
          {
              $this->mensajeDeError=$objeto->error."<br>".$objeto->mensajeDeError;
			   /*print_r($this->mensajeDeError);*/
              return false;
          }

        $sql="  UPDATE inv_toma_fisica_update SET sw_actualizado = '1'
                WHERE toma_fisica_id = $toma_fisica
                    AND etiqueta IN
                    (
                SELECT
				a.etiqueta	
                FROM inv_toma_fisica_update AS a,inv_toma_fisica_d as b
                WHERE a.nueva_existencia < a.existencia
                AND a.toma_fisica_id = $toma_fisica
                AND a.toma_fisica_id=b.toma_fisica_id
                AND a.etiqueta=b.etiqueta
                AND sw_actualizado IS NULL
                    ); ";
          global $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();

          $dbconn->Execute($sql);
          if($dbconn->ErrorNo() != 0)
          {
             $this->error = "Error al ejecutar actualizacion";
             $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
             return false;
          }
		/*print_r($retorno);*/
         
         return $retorno;
     }

 
    function ProductosParaAjustarPorIngreso($toma_fisica)
    {

        $sql="SELECT
                 a.codigo_producto,
                abs((a.nueva_existencia - a.existencia )) AS cantidad, b.fecha_vencimiento, b.lote	
                FROM inv_toma_fisica_update AS a,inv_toma_fisica_d as b
                WHERE a.nueva_existencia > a.existencia
                AND a.toma_fisica_id = $toma_fisica
                AND a.toma_fisica_id=b.toma_fisica_id
                AND a.etiqueta=b.etiqueta
                AND sw_actualizado IS NULL";

                if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

    }

  function ProductosReporteIngresoEgresoUbicacion($toma_fisica)
  {
       /*$sql= " SELECT   b.etiqueta_x_producto, 
                                a.codigo_producto,
                                fc_descripcion_producto(b.codigo_producto) as descripcion_producto, 
                                SUM(a.nueva_existencia) as ingreso,                  
                                c.ubicacion_id,
                                d.descripcion as ubicacion,
                                a.num_conteo,
                                nueva_exist_egre.nueva_existe as egreso
                   FROM    inv_toma_fisica_update AS a, 
                               inv_toma_fisica_d AS b LEFT JOIN (
                                        SELECT   SUM(a.nueva_existencia) as nueva_existe,
                                                       b.etiqueta_x_producto,
                                                       a.toma_fisica_id 
                                         FROM     inv_toma_fisica_update AS a,
                                                       inv_toma_fisica_d AS b,
                                                       existencias_bodegas_lote_fv AS c
                                         WHERE    a.nueva_existencia < a.existencia
                                         AND         a.toma_fisica_id = ".$toma_fisica."
                                         AND         a.toma_fisica_id = b.toma_fisica_id
                                         AND         a.etiqueta = b.etiqueta
                                         AND         b.empresa_id = c.empresa_id
                                         AND         b.centro_utilidad = c.centro_utilidad
                                         AND         b.codigo_producto = c.codigo_producto
                                         AND         b.bodega = c.bodega
                                         AND         b.fecha_vencimiento = c.fecha_vencimiento
                                         AND         b.lote = c.lote
                                         AND         c.existencia_actual >= ABS(a.existencia - a.nueva_existencia)
                                         AND         sw_actualizado IS NULL
                                         GROUP BY b.etiqueta_x_producto,a.toma_fisica_id ) as nueva_exist_egre 
                                 ON (  b.toma_fisica_id = nueva_exist_egre.toma_fisica_id
                                          AND b.etiqueta = nueva_exist_egre.etiqueta_x_producto),
                               existencias_bodegas_lote_fv AS c,
                               bodegas_ubicaciones AS d
                   WHERE  a.nueva_existencia > a.existencia
                   AND      a.toma_fisica_id = ".$toma_fisica."
                   AND      a.toma_fisica_id = b.toma_fisica_id
                   AND      a.etiqueta = b.etiqueta
                   AND      b.empresa_id = c.empresa_id
                   AND      b.centro_utilidad = c.centro_utilidad
                   AND      b.codigo_producto = c.codigo_producto
                   AND      b.bodega = c.bodega
                   AND      b.fecha_vencimiento = c.fecha_vencimiento
                   AND      b.lote = c.lote
                   AND      sw_actualizado IS NULL
                   GROUP BY b.etiqueta_x_producto,a.codigo_producto,c.ubicacion_id,d.descripcion,fc_descripcion_producto(b.codigo_producto),a.num_conteo,nueva_exist_egre.nueva_existe;";*/
				$sql .= "SELECT 
							a.toma_fisica_id,
							a.centro_utilidad, 	
							a.bodega, 	
							a.empresa_id, 	
							a.descripcion_bodega, 	
							a.etiqueta, 	
							a.etiqueta_x_producto, 	
							a.codigo_producto,	
							fc_descripcion_producto(a.codigo_producto)as descripcion,
							a.existencia, 	
							a.fecha_vencimiento, 	
							a.lote, 	
							a.costo, 	
							a.sw_ajusteautomatico, 	
							a.conteo_1, 	
							a.validacion_conteo_1, 	
							a.diferencia_1, 	
							a.conteo_2, 	
							a.validacion_conteo_2, 	
							a.diferencia_2, 	
							a.diferencia_1con2, 	
							a.conteo_3, 	
							a.validacion_conteo_3, 	
							a.diferencia_3, 	
							a.diferencia_2con3, 	
							a.diferencia_1con3, 	
							a.nueva_existencia, 	
							a.diferencia, 	
							a.num_conteo_nueva_existencia, 	
							a.sw_manual,
							CASE 
							WHEN a.sw_manual = '1' 
							THEN 'pparacar.png@MANUAL'
							WHEN a.sw_manual = '0' 
							THEN 'pc.png@AUTOMATICO'
							ELSE 'no.png@NO HA SIDO AJUSTADO' END as tipo_ajuste
							
							FROM
							tomas_fisicas as a
							WHERE TRUE
							AND toma_fisica_id = ".trim($toma_fisica)."
							ORDER BY a.etiqueta_x_producto;	";

                if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

   }
   
   
  /*
   *
   */
  function ProductosReporteAutomatico($toma_fisica)
  {    
      $sql= " SELECT   a.codigo_producto,
                                fc_descripcion_producto(b.codigo_producto) as descripcion_producto, 
                                SUM(a.nueva_existencia) as ingreso,
                                a.num_conteo,
                                nueva_exist_egre.nueva_existe as egreso,
                                i.descripcion as laboratorio,
                                f.costo,
                                a.existencia,
                                (nueva_exist_egre.nueva_existe-SUM(a.nueva_existencia)) as Ingreso_Menos_Egreso,
                                CASE WHEN 
                                          nueva_exist_egre.nueva_existe 
                                         IS NULL THEN 
                                          SUM(a.nueva_existencia)*f.costo 
                                         ELSE 
                                            (nueva_exist_egre.nueva_existe-SUM(a.nueva_existencia))*f.costo 
                                         END as Costo_Total_Ajuste,
                                CASE WHEN 
                                           nueva_exist_egre.nueva_existe 
                                          IS NULL THEN 
                                           (a.existencia+SUM(a.nueva_existencia))*f.costo 
                                          ELSE 
                                            ABS(a.existencia-nueva_exist_egre.nueva_existe)*f.costo 
                                          END as Costo_Total
                   FROM    inv_toma_fisica_update AS a, 
                               inv_toma_fisica_d AS b LEFT JOIN (
                                        
                                   SELECT   SUM(a.nueva_existencia) as nueva_existe,
                                            b.etiqueta_x_producto,a.toma_fisica_id 
                                   FROM      inv_toma_fisica_update AS a,
                                                  inv_toma_fisica_d AS b,
                                                  existencias_bodegas_lote_fv AS c
                                   WHERE    a.nueva_existencia < a.existencia
                                   AND        a.toma_fisica_id =".$toma_fisica."
                                   AND        a.toma_fisica_id = b.toma_fisica_id
                                   AND        a.etiqueta = b.etiqueta
                                   AND        b.empresa_id = c.empresa_id
                                   AND        b.centro_utilidad = c.centro_utilidad
                                   AND        b.codigo_producto = c.codigo_producto
                                   AND        b.bodega = c.bodega
                                   AND        b.fecha_vencimiento = c.fecha_vencimiento
                                   AND        b.lote = c.lote
                                   AND        c.existencia_actual >= ABS(a.existencia - a.nueva_existencia)
                                   AND        sw_actualizado IS NULL
                                   GROUP BY b.etiqueta_x_producto,a.toma_fisica_id ) AS nueva_exist_egre 
                                 ON (  b.toma_fisica_id = nueva_exist_egre.toma_fisica_id
                                          AND b.etiqueta = nueva_exist_egre.etiqueta_x_producto),
                               existencias_bodegas_lote_fv AS c,
                               inventarios_productos AS d,
                               inv_clases_inventarios AS e,
                               inv_laboratorios AS i,
                               inv_toma_fisica_detalle_inicial AS f
                   WHERE  a.nueva_existencia > a.existencia
                   AND      a.toma_fisica_id = ".$toma_fisica."
                   AND      a.toma_fisica_id = b.toma_fisica_id
                   AND      a.etiqueta = b.etiqueta
                   AND      b.empresa_id = c.empresa_id
                   AND      b.centro_utilidad = c.centro_utilidad
                   AND      b.codigo_producto = c.codigo_producto
                   AND      b.bodega = c.bodega
                   AND      b.fecha_vencimiento = c.fecha_vencimiento
                   AND      b.lote = c.lote
                   AND      b.codigo_producto = d.codigo_producto
                   AND      d.grupo_id=e.grupo_id 
                   AND      d.clase_id=e.clase_id
                   AND      e.laboratorio_id=i.laboratorio_id
                   AND      a.toma_fisica_id = b.toma_fisica_id
                   AND      a.toma_fisica_id = f.toma_fisica_id
                   AND      b.empresa_id = f.empresa_id
                   AND      b.centro_utilidad = f.centro_utilidad
                   AND      b.bodega = f.bodega
                   AND      b.codigo_producto = f.codigo_producto
                   AND      b.fecha_vencimiento = f.fecha_vencimiento
                   AND      b.lote = f.lote
                   AND      sw_actualizado IS NULL
                   GROUP BY  b.etiqueta_x_producto,
                                    a.codigo_producto,
                                    fc_descripcion_producto(b.codigo_producto),
                                    a.num_conteo,
                                    d.descripcion,
                                    nueva_exist_egre.nueva_existe,
                                    i.descripcion,
                                    f.costo,
                                    a.existencia;";

                if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

   }
/*
    */
   function ObtenerReporteAutomatico($toma_fisica)
   {
      $sql = "SELECT   a.codigo_producto,
                                fc_descripcion_producto(b.codigo_producto) as descripcion_producto, 
                                SUM(a.nueva_existencia) as ingreso,
                                a.num_conteo,
                                nueva_exist_egre.nueva_existe as egreso,
                                i.descripcion as laboratorio,
                                f.costo,
                                a.existencia,
                                (nueva_exist_egre.nueva_existe-SUM(a.nueva_existencia)) as Ingreso_Menos_Egreso,
                                CASE WHEN 
                                          nueva_exist_egre.nueva_existe 
                                         IS NULL THEN 
                                          SUM(a.nueva_existencia)*f.costo 
                                         ELSE 
                                            (nueva_exist_egre.nueva_existe-SUM(a.nueva_existencia))*f.costo 
                                         END as Costo_Total_Ajuste,
                                CASE WHEN 
                                           nueva_exist_egre.nueva_existe 
                                          IS NULL THEN 
                                           (a.existencia+SUM(a.nueva_existencia))*f.costo 
                                          ELSE 
                                            ABS(a.existencia-nueva_exist_egre.nueva_existe)*f.costo 
                                          END as Costo_Total
                   FROM    inv_toma_fisica_update AS a, 
                               inv_toma_fisica_d AS b LEFT JOIN (
                                        
                                   SELECT   SUM(a.nueva_existencia) as nueva_existe,
                                            b.etiqueta_x_producto,a.toma_fisica_id 
                                   FROM      inv_toma_fisica_update AS a,
                                                  inv_toma_fisica_d AS b,
                                                  existencias_bodegas_lote_fv AS c
                                   WHERE    a.nueva_existencia < a.existencia
                                   AND        a.toma_fisica_id =".$toma_fisica."
                                   AND        a.toma_fisica_id = b.toma_fisica_id
                                   AND        a.etiqueta = b.etiqueta
                                   AND        b.empresa_id = c.empresa_id
                                   AND        b.centro_utilidad = c.centro_utilidad
                                   AND        b.codigo_producto = c.codigo_producto
                                   AND        b.bodega = c.bodega
                                   AND        b.fecha_vencimiento = c.fecha_vencimiento
                                   AND        b.lote = c.lote
                                   AND        c.existencia_actual >= ABS(a.existencia - a.nueva_existencia)
                                   AND        sw_actualizado IS NULL
                                   GROUP BY b.etiqueta_x_producto,a.toma_fisica_id ) AS nueva_exist_egre 
                                 ON (  b.toma_fisica_id = nueva_exist_egre.toma_fisica_id
                                          AND b.etiqueta = nueva_exist_egre.etiqueta_x_producto),
                               existencias_bodegas_lote_fv AS c,
                               inventarios_productos AS d,
                               inv_clases_inventarios AS e,
                               inv_laboratorios AS i,
                               inv_toma_fisica_detalle_inicial AS f
                   WHERE  a.nueva_existencia > a.existencia
                   AND      a.toma_fisica_id = ".$toma_fisica."
                   AND      a.toma_fisica_id = b.toma_fisica_id
                   AND      a.etiqueta = b.etiqueta
                   AND      b.empresa_id = c.empresa_id
                   AND      b.centro_utilidad = c.centro_utilidad
                   AND      b.codigo_producto = c.codigo_producto
                   AND      b.bodega = c.bodega
                   AND      b.fecha_vencimiento = c.fecha_vencimiento
                   AND      b.lote = c.lote
                   AND      b.codigo_producto = d.codigo_producto
                   AND      d.grupo_id=e.grupo_id 
                   AND      d.clase_id=e.clase_id
                   AND      e.laboratorio_id=i.laboratorio_id
                   AND      a.toma_fisica_id = b.toma_fisica_id
                   AND      a.toma_fisica_id = f.toma_fisica_id
                   AND      b.empresa_id = f.empresa_id
                   AND      b.centro_utilidad = f.centro_utilidad
                   AND      b.bodega = f.bodega
                   AND      b.codigo_producto = f.codigo_producto
                   AND      b.fecha_vencimiento = f.fecha_vencimiento
                   AND      b.lote = f.lote
                   AND      sw_actualizado IS NULL
                   GROUP BY  b.etiqueta_x_producto,
                                    a.codigo_producto,
                                    fc_descripcion_producto(b.codigo_producto),
                                    a.num_conteo,
                                    d.descripcion,
                                    nueva_exist_egre.nueva_existe,
                                    i.descripcion,
                                    f.costo,
                                    a.existencia;";
     
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        return $rst;
  }
  
    function ProductosParaAjustarPorEgreso($toma_fisica)
    {
       $sql="SELECT
                 a.codigo_producto,
                abs((a.nueva_existencia - a.existencia )) AS cantidad, b.fecha_vencimiento, b.lote	
                FROM inv_toma_fisica_update AS a,inv_toma_fisica_d as b
                WHERE a.nueva_existencia < a.existencia
                AND a.toma_fisica_id = $toma_fisica
                AND a.toma_fisica_id=b.toma_fisica_id
                AND a.etiqueta=b.etiqueta
                AND sw_actualizado IS NULL";
		/*print_r($sql);*/
        if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
        $datos=Array();
        while(!$resultado->EOF)
        {

            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $datos;
     }

    
    function GetProductosParaAjustarPorIngreso($toma_fisica)
    {
         $sql="SELECT
							a.toma_fisica_id,
							a.centro_utilidad, 	
							a.bodega, 	
							a.empresa_id, 	
							a.etiqueta, 	
							a.etiqueta_x_producto, 	
							a.codigo_producto,
							a.existencia, 	
							a.fecha_vencimiento, 	
							a.lote, 	
							a.costo, 	
							a.sw_ajusteautomatico, 	
							a.conteo_1, 	
							a.validacion_conteo_1, 	
							a.diferencia_1, 	
							a.conteo_2, 	
							a.validacion_conteo_2, 	
							a.diferencia_2, 	
							a.diferencia_1con2, 	
							a.conteo_3, 	
							a.validacion_conteo_3, 	
							a.diferencia_3, 	
							a.diferencia_2con3, 	
							a.diferencia_1con3, 	
							a.nueva_existencia, 	
							a.diferencia, 	
							a.num_conteo_nueva_existencia, 	
							a.sw_manual,
							abs(a.diferencia) as cantidad
				FROM
							tomas_fisicas as a
							WHERE TRUE
							AND a.nueva_existencia IS NOT NULL
							AND a.diferencia >0
							AND a.existencia <> a.nueva_existencia
							AND a.toma_fisica_id = '".trim($toma_fisica)."'
							AND a.sw_actualizado IS NULL
				ORDER BY a.etiqueta_x_producto";
		
	
		/*$sql="SELECT
                 a.codigo_producto,
                (a.nueva_existencia - a.existencia ) AS cantidad, b.fecha_vencimiento, b.lote	
                FROM inv_toma_fisica_update AS a,inv_toma_fisica_d as b
                WHERE a.nueva_existencia > a.existencia
                AND a.toma_fisica_id = $toma_fisica
                AND a.toma_fisica_id=b.toma_fisica_id
                AND a.etiqueta=b.etiqueta
                AND sw_actualizado IS NULL";*/

                if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;
    }
    


    function GetProductosParaAjustarPorEgreso($toma_fisica)
    {//AND b.toma_fisica_id = $toma_fisica
        $sql="SELECT
							a.toma_fisica_id,
							a.centro_utilidad, 	
							a.bodega, 	
							a.empresa_id, 	
							a.etiqueta, 	
							a.etiqueta_x_producto, 	
							a.codigo_producto,
							a.existencia, 	
							a.fecha_vencimiento, 	
							a.lote, 	
							a.costo, 	
							a.sw_ajusteautomatico, 	
							a.conteo_1, 	
							a.validacion_conteo_1, 	
							a.diferencia_1, 	
							a.conteo_2, 	
							a.validacion_conteo_2, 	
							a.diferencia_2, 	
							a.diferencia_1con2, 	
							a.conteo_3, 	
							a.validacion_conteo_3, 	
							a.diferencia_3, 	
							a.diferencia_2con3, 	
							a.diferencia_1con3, 	
							a.nueva_existencia, 	
							a.diferencia, 	
							a.num_conteo_nueva_existencia, 	
							a.sw_manual,
							abs(a.diferencia) as cantidad
				FROM
							tomas_fisicas as a
							WHERE TRUE
							AND a.nueva_existencia IS NOT NULL
							AND a.diferencia < 0
							AND a.existencia <> a.nueva_existencia
							AND a.toma_fisica_id = '".trim($toma_fisica)."'
							AND a.sw_actualizado IS NULL
				ORDER BY a.etiqueta_x_producto ";
				
        /*print_r($sql);*/
        if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
        $datos=Array();
        while(!$resultado->EOF)
        {

            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }

        $resultado->Close();
        return $datos;
     }   



     
        function CrearTomaFisica($empresa_id,$centro_utilidad,$bodega,$numero_conteos,$descripcion,$orderby=false,$solo_con_existencia=true)
        {
            list($dbconn) = GetDBconn();
        //$dbconn->debug=true;
            
          $sql = "SELECT nextval('inv_toma_fisica_toma_fisica_id_seq'::regclass)";
            $result = $dbconn->Execute($sql);
        
       // EXIT;
            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = $dbconn->ErrorMsg();
                return false;
            }
            if($result->EOF)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                $this->mensajeDeError = "Error no retorno valor la secuencia.";
                return false;
            }
        
            list($toma_fisica_id) = $result->FetchRow();
            $result->Close();
            
           
     
            if($orderby==='descripcion')
            {
                $var_order = " ORDER BY b.descripcion ";
            }
            
			if($orderby==='codigo')
            {
                $var_order = " ORDER BY a.codigo_producto ";
            }
            
			if($orderby==='laboratorio')
            {
                $var_order = " ORDER BY e.descripcion,b.descripcion ";
            }
        
            if($solo_con_existencia)
            {
                $var_filtro = " AND a.existencia > 0 ";
                $var_filtro = " AND c.existencia_actual > 0 ";
            }
            else
            {
                $var_filtro = "";
            }
        
        
            $dbconn->BeginTrans();
        
          $sql = "
                    INSERT INTO inv_toma_fisica
                    (
                        toma_fisica_id,
                        empresa_id,
                        centro_utilidad,
                        bodega,
                        fecha_registro,
                        usuario_id,
                        numero_conteos,
                        sw_estado,
                        descripcion,
                        observacion,
                        fecha_inicio
                    )
                    VALUES
                    (
                        $toma_fisica_id,
                        '$empresa_id',
                        '$centro_utilidad',
                        '$bodega',
                        now(),
                        ".UserGetUID().",
                        $numero_conteos,
                        '1',
                        '$descripcion',
                        NULL,
                        NULL
                    );
        
                    CREATE TEMPORARY SEQUENCE inv_toma_fisica_d_temp_seq
                    INCREMENT BY 1
                    NO MAXVALUE
                    NO MINVALUE
                    CACHE 1;
                    
                    
        
                    INSERT INTO inv_toma_fisica_d
                    SELECT
                        $toma_fisica_id as toma_fisica_id,
                        nextval('inv_toma_fisica_d_temp_seq'::regclass) as etiqueta,
                        X.empresa_id,
                        X.centro_utilidad,
                        X.codigo_producto ,
                        X.bodega,
                        X.fecha_vencimiento,
                        X.lote
                    FROM
                    (
                        SELECT
                            a.empresa_id,
                            a.centro_utilidad,
                            a.codigo_producto ,
                            a.bodega,
                            c.fecha_vencimiento,
                            c.lote
                        FROM existencias_bodegas as a,
                             inventarios_productos as b
							 JOIN inv_subclases_inventarios as d ON(b.subclase_id = d.subclase_id)
							 AND (b.clase_id = d.clase_id)
							 AND (b.grupo_id = d.grupo_id)
							 JOIN inv_clases_inventarios as e ON (d.grupo_id = e.grupo_id)
							 AND (d.clase_id = e.clase_id),
                             existencias_bodegas_lote_fv as c
                             
                        WHERE
                            a.empresa_id = '$empresa_id'
                            AND a.centro_utilidad = '$centro_utilidad'
                            AND a.bodega = '$bodega'
                            AND a.estado = '1'
                            $var_filtro
                            AND b.codigo_producto = a.codigo_producto
                            AND c.empresa_id = a.empresa_id
                            AND c.centro_utilidad = a.centro_utilidad
                            AND c.bodega = a.bodega
                            AND c.codigo_producto = a.codigo_producto
                        $var_order
                    ) AS X;

                    INSERT INTO inv_toma_fisica_usuarios_administradores
                    (
                        toma_fisica_id,
                        usuario_id
                    )
                    VALUES
                    (
                        $toma_fisica_id,
                        ".UserGetUID()."
                    );

                    DROP SEQUENCE inv_toma_fisica_d_temp_seq;
            ";
        
             $dbconn->Execute($sql);
             
         
             if($dbconn->ErrorNo() != 0)
             {
                 $dbconn->RollbackTrans();
                 $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
                 $this->mensajeDeError = $dbconn->ErrorMsg();
                 return false;
             }
                $dbconn->CommitTrans();
                
         //$this->Productostomafisica($empresa_id,$centro_utilidad,$bodega,$toma_fisica_id);
            return true;
        }

 /*function Productostomafisica($empresa_id,$centro_utilidad,$bodega,$toma_fisica_id)
    {
      
        $sql = "SELECT a.codigo_producto, 
                               a.fecha_vencimiento,
                               a.lote 
                         FROM   inv_toma_fisica_d as a  
                         WHERE a.empresa_id = '$empresa_id'
                            AND  a.centro_utilidad = '$centro_utilidad'
                            AND  a.bodega = '$bodega'
                            AND  a.toma_fisica_id = '$toma_fisica_id' ";

                if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

    }*/


    function ListaBodegasProductos($empresa_id)
    {

        $sql="SELECT
                a.*,
                b.num_productos,
                c.num_productos_existencia,
                u.descripcion as nom_centro
                

                FROM
                bodegas as a,
                centros_utilidad as u, 
                (
                    SELECT empresa_id,centro_utilidad,bodega, COUNT(*) as num_productos
                    FROM existencias_bodegas GROUP BY empresa_id,centro_utilidad,bodega
                ) as b,
                (
                    SELECT empresa_id,centro_utilidad,bodega,   coalesce(COUNT(*),0) AS num_productos_existencia
                    FROM existencias_bodegas 
                    WHERE existencia > 0
                    GROUP BY empresa_id,centro_utilidad,bodega
                ) as c
                WHERE
                    a.empresa_id='$empresa_id'
                    AND b.empresa_id = a.empresa_id
                    AND b.centro_utilidad = a.centro_utilidad
                    AND b.bodega = a.bodega
                    AND c.empresa_id = a.empresa_id
                    AND c.centro_utilidad = a.centro_utilidad
                    AND c.bodega = a.bodega
                    AND u.empresa_id = a.empresa_id
                    AND u.centro_utilidad = a.centro_utilidad
                ";
			
                if(!$resultado = $this->ConexionBaseDatos($sql))
                    return false;
                    
                $datos=Array();
                while(!$resultado->EOF)
                {
                    
                    $datos[] = $resultado->GetRowAssoc($ToUpper = false);
                    $resultado->MoveNext();
                }
                
                $resultado->Close();
                return $datos;

    }


    /**
    * funcion que sirve obtener el nombre de una bodega
    * @param integer $bodega id de la bodega
    * @param string $centro_utilidad centro de utilidad al que pertenece la bodega
    * @param string $empresa_id 
    * @return array $datos con el nombre de la bodega
    **/
    function ActivarTomaFisica($toma_fisica_id)
    {

        $query =" SELECT * FROM inv_toma_fisica WHERE toma_fisica_id=$toma_fisica_id";

        global $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "LA TOMA FISICA NO EXISTE";
            $this->mensajeDeError = "LA TOMA FISICA [$toma_fisica_id] NO EXISTE";
            return false;
        }
        
        $fila=$result->FetchRow();
        $result->Close();

        if(!empty($fila['fecha_inicio']))
        {
            $this->error = "LA TOMA FISICA NO EXISTE";
            $this->mensajeDeError = "LA TOMA FISICA [$toma_fisica_id] YA ESTA ACTIVADA";
            return false;
        }
        
        $query =" SELECT
                    COUNT(*)
                  FROM
                  inv_toma_fisica_detalle_inicial
                  WHERE
                  toma_fisica_id=$toma_fisica_id";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }        
   
        
        list($num_reg)=$result->FetchRow();

        if($num_reg > 0)
        {
            $this->error = "LA TOMA FISICA NO EXISTE";
            $this->mensajeDeError = "ERROR:  LA TOMA FISICA [$toma_fisica_id] YA ESTA REGISTRADA EN [inv_toma_fisica_detalle_inicial].";
            return false;
        }

        $query="
            INSERT INTO inv_toma_fisica_detalle_inicial
            (toma_fisica_id,
             empresa_id,
             centro_utilidad,
             bodega,
             codigo_producto,
             existencia,
             costo,
             fecha_vencimiento,
             lote)
            SELECT
            $toma_fisica_id as toma_fisica_id,
            a.empresa_id,
            a.centro_utilidad,
            a.bodega,
            a.codigo_producto,
            a.existencia_actual AS existencia,
            b.costo,
            a.fecha_vencimiento,
            a.lote
            
            FROM existencias_bodegas_lote_fv as a,
            inventarios as b
            
            WHERE
            a.empresa_id = '".$fila['empresa_id']."'
            AND a.centro_utilidad = '".$fila['centro_utilidad']."'
            AND a.bodega = '".$fila['bodega']."'
            AND b.empresa_id = a.empresa_id
            AND b.codigo_producto = a.codigo_producto
            ;
            
            UPDATE inv_toma_fisica
            SET fecha_inicio = now(), sw_estado = '1'
            WHERE toma_fisica_id = $toma_fisica_id;
        ";

        $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
                
        $query =" SELECT * FROM inv_toma_fisica WHERE toma_fisica_id=$toma_fisica_id";

        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

        if($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if($result->EOF)
        {
            $this->error = "LA TOMA FISICA NO EXISTE";
            $this->mensajeDeError = "LA TOMA FISICA [$toma_fisica_id] NO EXISTE";
            return false;
        }
        
        $fila=$result->FetchRow();
        $result->Close();

        return $fila['fecha_inicio'];

    }    
    
    /**
    * funcion que sirve obtener el nombre de una bodega
    * @param integer $bodega id de la bodega
    * @param string $centro_utilidad centro de utilidad al que pertenece la bodega
    * @param string $empresa_id 
    * @return array $datos con el nombre de la bodega
    **/
    function bodegasname($bodega,$centro_utilidad,$empresa_id)
    { 
             $sql=" SELECT
                    
                    descripcion,
                    bodega

                   FROM
                   bodegas

                   WHERE
                   centro_utilidad='".$centro_utilidad."'
                   AND bodega = '$bodega'
                   AND empresa_id ='$empresa_id'";
                
        if(!$resultado = $this->ConexionBaseDatos($sql))
            return false;
            
        $datos=Array();
        while(!$resultado->EOF)
        {
            
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
        }
        
        $resultado->Close();
        return $datos;
    }



    /**
    * funcion que sirve para la generacion de reportes de los productos de una toma fisica
    * @param integer $toma_fisica
    * @param string $empresa_id
    * @param string $centro_utilidad
    * @param string $bodega
    * @return array $datos
    **/
    function ReporteProductosTomaFisica($toma_fisica,$empresa_id,$centro_utilidad,$bodega,$filtro)
    {
        if($filtro=='1')
        {
            /*$filtro1 =" i.descripcion as laboratorio, ";*/
            $filtro1 =" h.descripcion as laboratorio, ";
            $filtro2 =" ";
            $filtro5  =" ";
            $filtro3 =" ";
            $filtro4 =" ";
			$order = " ORDER BY	h.descripcion ASC,e.descripcion ASC,a.etiqueta_x_producto ASC ";
        }
        if($filtro=='2')
        {
            $filtro1  ="  g.descripcion as molecula, ";
            $filtro2  =" ,h.descripcion as laboratorio ";
            $filtro5  =" ";
			$order = "  ORDER BY g.descripcion ASC,e.descripcion ASC,a.etiqueta_x_producto ASC ";
            /*$filtro3  =" ,inv_moleculas as mol  ";*/
            /*$filtro4 .=" AND  g.molecula_id=mol.molecula_id ";*/
          
            

        }
        if($filtro=='3')
        {
            $filtro1  .=" bod_ub.descripcion, ";
            $filtro2  =" ";
            $filtro5  =" LEFT JOIN bodegas_ubicaciones AS bod_ub ON( bod_ub.empresa_id = b.empresa_id
                                                                                                  AND bod_ub.centro_utilidad  = b.centro_utilidad
                                                                                                  AND bod_ub.ubicacion_id = b.ubicacion_id
                                                                                                  AND bod_ub.bodega = b.bodega)";
            $filtro3  =" ";
            $filtro4  =" ";
			$order = "	ORDER BY g.descripcion ASC,e.descripcion ASC,a.etiqueta_x_producto ASC ";
            
        }
       /*$sql .= " SELECT   a.toma_fisica_id, 
                                  a.etiqueta, 
                                  e.codigo_producto, 
                                  fc_descripcion_producto(e.codigo_producto) as descripcion_producto, 
                                  b.existencia_actual, 
                                  b.fecha_vencimiento, 
                                  b.lote 
                    FROM     inv_toma_fisica_d as a, 
                                  existencias_bodegas_lote_fv as b , 
                                  existencias_bodegas as c, 
                                  inventarios as d, 
                                  inventarios_productos as e
                   WHERE    a.toma_fisica_id = ".$toma_fisica." 
                   AND         a.empresa_id = '".$empresa_id."' 
                   AND         a.centro_utilidad = '".$centro_utilidad."' 
                   AND         a.bodega = '".$bodega."' 
                   AND         b.empresa_id = a.empresa_id 
                   AND         b.centro_utilidad = a.centro_utilidad 
                   AND         b.bodega = a.bodega 
                   AND         b.codigo_producto = a.codigo_producto 
                   AND         b.fecha_vencimiento = a.fecha_vencimiento 
                   AND         b.lote = a.lote 
                   AND         c.empresa_id = b.empresa_id 
                   AND         c.centro_utilidad = b.centro_utilidad 
                   AND         c.bodega = b.bodega 
                   AND         c.codigo_producto = b.codigo_producto 
                   AND        d.empresa_id = '".$empresa_id."' 
                   AND        d.codigo_producto = c.codigo_producto 
                   AND        e.codigo_producto = d.codigo_producto 
                   AND        b.existencia_actual > 0 
                   AND        b.fecha_registro < now() 
                  ";*/
				  
				  /*
				  ,
                            inv_laboratorios as i
							
             AND        i.laboratorio_id=h.laboratorio_id 
				  */
				  
  $sql .= " SELECT  ".$filtro1." a.toma_fisica_id, 
                            a.etiqueta, 
							a.etiqueta_x_producto,
                            e.codigo_producto,
                            fc_descripcion_producto(e.codigo_producto) as descripcion_producto, 
                            e.unidad_id,
                            f.descripcion as descripcion_unidad,
                            b.existencia_actual,
                            b.fecha_vencimiento,
                            b.lote
                            ".$filtro2."
               FROM    inv_toma_fisica_d as a
                            JOIN existencias_bodegas_lote_fv as b ON (a.empresa_id = b.empresa_id)
							AND         (a.centro_utilidad = b.centro_utilidad)
							AND         (a.bodega = b.bodega)
							AND         (a.codigo_producto = b.codigo_producto)
							AND         (a.fecha_vencimiento = b.fecha_vencimiento)
							AND         (a.lote = b.lote)
							".$filtro5."
                            JOIN inventarios_productos as e ON (b.codigo_producto = e.codigo_producto)
                            JOIN unidades as f ON (f.unidad_id = e.unidad_id)
                            JOIN inv_subclases_inventarios as g ON (e.grupo_id=g.grupo_id)
							AND         (e.clase_id=g.clase_id)
							AND         (e.subclase_id = g.subclase_id)
                            JOIN inv_clases_inventarios as h ON (h.grupo_id = g.grupo_id)
							AND         (h.clase_id = g.clase_id)
                            ".$filtro3."
             WHERE    a.toma_fisica_id = '".trim($toma_fisica)."' 
             AND         a.empresa_id = '".trim($empresa_id)."' 
             AND         a.centro_utilidad = '".trim($centro_utilidad)."'
             AND         a.bodega = '".trim($bodega)."' 
             AND        b.existencia_actual > 0
             AND        b.fecha_registro < now()
             ".$filtro4."
			 ".$order."
              ";
       /* $sql="SELECT ".$filtro1."
                    a.toma_fisica_id,
                    a.etiqueta,
                    b.codigo_producto,
                    fc_descripcion_producto(b.codigo_producto) as descripcion_producto,
                    b.unidad_id,
                    c.descripcion as descripcion_unidad,
                    e.existencia_actual,
                    e.fecha_vencimiento,
                    e.lote
                    ".$filtro2."
                FROM
                    inv_toma_fisica_d as a,
                    inventarios_productos as b,
                    unidades as c,
                    existencias_bodegas_lote_fv as e".$filtro5.",
                    inv_clases_inventarios as f, 
                    inv_laboratorios as g
                     ".$filtro3."
                WHERE
                    a.toma_fisica_id = ".$toma_fisica."
                    AND a.empresa_id = '".$empresa_id."'
                    AND a.centro_utilidad = '".$centro_utilidad."'
                    AND a.bodega = '".$bodega."'
                    AND a.empresa_id = e.empresa_id
                    AND a.centro_utilidad  = e.centro_utilidad
                    AND a.bodega = e.bodega
                    AND a.codigo_producto = b.codigo_producto
                    AND a.codigo_producto = e.codigo_producto
                    AND a.codigo_producto = e.codigo_producto
                    AND a.fecha_vencimiento = e.fecha_vencimiento
                    AND a.lote = e.lote
                    AND a.empresa_id = e.empresa_id
                    AND a.centro_utilidad = e.centro_utilidad
                    AND c.unidad_id = b.unidad_id
                    AND b.grupo_id=f.grupo_id
                    AND b.clase_id=f.clase_id
                    AND f.laboratorio_id=g.laboratorio_id
                     ".$filtro4."
                     ";*/

         
        if(!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];
                //false;
        
            $datos=Array();
            while(!$resultado->EOF)
            {
            $datos[$resultado->fields[0]][$resultado->fields[2]] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
            }
        
            $resultado->Close();
            return $datos;
    }
   /**
    * funcion que sirve para la generacion de reportes de los productos de una toma fisica
    * @param integer $toma_fisica
    * @param string $empresa_id
    * @param string $centro_utilidad
    * @param string $bodega
    * @return array $datos
    **/
    function ReporteProductosStockInicial($toma_fisica,$empresa_id,$centro_utilidad,$bodega)
    {
        $sql = "SELECT  
							fc_descripcion_producto(b.codigo_producto) as descripcion_producto,
							b.codigo_producto,
							c.descripcion as laboratorio,
							b.existencia_actual as cantidad,
							b.existencia_actual,
							b.fecha_vencimiento,
							b.lote,
							f.costo,
							b.existencia_actual*f.costo as costo_promedio
                 FROM     inventarios_productos as a,
                               existencias_bodegas_lote_fv as b,
                               inv_clases_inventarios as c,
                               inventarios as f                               
                WHERE     b.empresa_id = '".trim($empresa_id)."'
                AND         b.centro_utilidad = '".trim($centro_utilidad)."'
                AND         b.bodega = '".trim($bodega)."'
                AND         a.codigo_producto = b.codigo_producto
                AND         a.grupo_id=c.grupo_id
                AND         a.clase_id=c.clase_id
                AND         f.empresa_id=b.empresa_id
                AND         f.codigo_producto=b.codigo_producto
				AND			b.existencia_actual >0
				ORDER BY c.descripcion ASC, a.descripcion ASC ";
		
		
		/*$sql="SELECT  a.codigo_producto,
                              fc_descripcion_producto(b.codigo_producto) as descripcion_producto,
                              a.descripcion,
                              b.existencia_actual,
                              b.fecha_vencimiento,
                              b.lote,
                              d.descripcion as laboratorio,
                              f.costo
                 FROM     inventarios_productos as a,
                               existencias_bodegas_lote_fv as b,
                               inv_clases_inventarios as c, 
                               inv_laboratorios as d,
                               inventarios as f                               
                WHERE     b.empresa_id = '".$empresa_id."'
                AND         b.centro_utilidad = '".$centro_utilidad."'
                AND         b.bodega = '".$bodega."'
                AND         a.codigo_producto = b.codigo_producto
                AND         a.grupo_id=c.grupo_id
                AND         a.clase_id=c.clase_id
                AND         c.laboratorio_id=d.laboratorio_id
                AND         f.empresa_id=b.empresa_id
                AND         f.codigo_producto=b.codigo_producto
              ";*/

         
        if(!$resultado = $this->ConexionBaseDatos($sql))
            return $this->frmError['MensajeError'];
                //false;
        
            $datos=Array();
            while(!$resultado->EOF)
            {
            $datos[] = $resultado->GetRowAssoc($ToUpper = false);
            $resultado->MoveNext();
            }
        
            $resultado->Close();
            return $datos;


    }
    /**
    *
    */
    function ObtenerReporteStockInicial($datos)
    {
      //$ruta_archivo =  GetVarConfigAplication('DIR_SIIS')."/reportes_sql/".$datos['nombre'].".sql";
      //$lines = file($ruta_archivo);
        
      $sql = "SELECT  fc_descripcion_producto(b.codigo_producto) as descripcion_producto,
                              c.descripcion as laboratorio,
                              b.existencia_actual as cantidad,
                              f.costo,
                              b.existencia_actual*f.costo as costo_promedio
                 FROM     inventarios_productos as a,
                               existencias_bodegas_lote_fv as b,
                               inv_clases_inventarios as c,
                               inventarios as f                               
                WHERE     b.empresa_id = '".$datos['empresa_id']."'
                AND         b.centro_utilidad = '".$datos['centro_utilidad']."'
                AND         b.bodega = '".$datos['bodega']."'
                AND         a.codigo_producto = b.codigo_producto
                AND         a.grupo_id=c.grupo_id
                AND         a.clase_id=c.clase_id
                AND         f.empresa_id=b.empresa_id
                AND         f.codigo_producto=b.codigo_producto
				AND			b.existencia_actual >0
				ORDER BY c.descripcion ASC, a.descripcion ASC ";
      //foreach ($lines as $line_num => $line) 
        //$sql .= $line;
      
      //$sql = str_replace("_1","'".$this->DividirFecha($datos['fecha_inicial'],"-")."'::date",$sql);
      //$sql = str_replace("_2","'".$this->DividirFecha($datos['fecha_final'],"-")."'::date",$sql);
     
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        return $rst;
    }
/***************************************************************
*
******************************************************************/
function AjustaraCeroBD($toma)
{

  $sql="INSERT INTO inv_toma_fisica_update
    (
        toma_fisica_id,
        etiqueta,
        num_conteo,
        sw_manual,
        empresa_id,
        centro_utilidad,
        bodega,
        codigo_producto,
        existencia,
        nueva_existencia,
        costo,
        fecha_vencimiento,
        lote
    )
    SELECT
        a.toma_fisica_id,
        a.etiqueta,
        0 as num_conteo,
        '1' as sw_manual,
        a.empresa_id,
        a.centro_utilidad,
        a.bodega,
        a.codigo_producto,
        a.existencia,
        0 as nueva_existencia,
        a.costo,
        a.fecha_vencimiento,
        a.lote        
    FROM
       tomas_fisicas  as a
	WHERE TRUE
		AND a.toma_fisica_id = '".trim($toma)."' 
		AND a.conteo_1 IS NULL ";

        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
         return false;
        } 
        else 
         {
             return true;
         }   

}
/*
* Funcion que ajusta automaticamente el conteo 1 y conteo 2 si los dos conteos son iguales..De resto no lo ajusta
*/
function AjustaraAutomaticamenteCONTEO3($toma)
{
  $vector=$this->SacarNoCuadroC3CuadreAutomatico($toma);
  
  //exit;
  foreach($vector as $key =>$conteo_1)
  {
    foreach($conteo_1 as $key1=>$conteo_2)
    {
  //for($i=0;$i<count($vector);$i++)
  //{
      if($key!=$key1 AND $conteo_2['sw_ajusteautomatico']=='1')
      {
          $sql.="INSERT INTO inv_toma_fisica_update ( toma_fisica_id,
                                                                      etiqueta,
                                                                      num_conteo,
                                                                      sw_manual,
                                                                      empresa_id,
                                                                      centro_utilidad,
                                                                      bodega,
                                                                      codigo_producto,
                                                                      existencia,
                                                                      nueva_existencia,
                                                                      costo,
                                                                      fecha_vencimiento,
                                                                      lote)
                                                 SELECT          a.toma_fisica_id,
                                                                      a.etiqueta,
                                                                      3 as num_conteo,
                                                                      '0' as sw_manual,
                                                                      t.empresa_id,
                                                                      t.centro_utilidad,
                                                                      a.bodega,
                                                                      a.codigo_producto,
                                                                      i.existencia,
                                                                      ".$conteo_2['conteo_3']." as nueva_existencia,
                                                                      i.costo,
                                                                      a.fecha_vencimiento,
                                                                      a.lote                                                                     
                                              FROM                inv_toma_fisica as t,
                                                                       inv_toma_fisica_d as a
                                              LEFT JOIN          inv_toma_fisica_conteos AS y
                                                              ON(
                                                                  y.toma_fisica_id = a.toma_fisica_id
                                                                  AND y.etiqueta = a.etiqueta
                                                                  AND y.num_conteo = 3
                                                              )
                                              LEFT JOIN         inv_toma_fisica_update AS u
                                                            ON(
                                                                u.toma_fisica_id = a.toma_fisica_id
                                                                AND u.etiqueta = a.etiqueta
                                                            ),
                                                                inv_toma_fisica_detalle_inicial as i
                                            WHERE         t.toma_fisica_id = ".$toma."
                                            AND              a.toma_fisica_id = t.toma_fisica_id
                                            AND             a.codigo_producto = '".$conteo_2['codigo_producto']."'
                                            AND             a.fecha_vencimiento='".$conteo_2['fecha_vencimiento']."'
                                            AND             a.lote='".$conteo_2['lote']."'
                                            AND              u.toma_fisica_id IS NULL
                                            AND              i.toma_fisica_id = a.toma_fisica_id
                                            AND             i.empresa_id = a.empresa_id
                                            AND             i.centro_utilidad = a.centro_utilidad
                                            AND             i.bodega = a.bodega
                                            AND             i.codigo_producto = a.codigo_producto
                                            AND             a.fecha_vencimiento=i.fecha_vencimiento
                                            AND             a.lote=i.lote; ";
                               
               if(!$resultado = $this->ConexionBaseDatos($sql))
               {
                 
                  return false;
                }   
        
       }
      elseif($key==$key1 AND $conteo_2['sw_ajusteautomatico']=='1')
       {
          $sql.="INSERT INTO inv_toma_fisica_update ( toma_fisica_id,
                                                                      etiqueta,
                                                                      num_conteo,
                                                                      sw_manual,
                                                                      empresa_id,
                                                                      centro_utilidad,
                                                                      bodega,
                                                                      codigo_producto,
                                                                      existencia,
                                                                      nueva_existencia,
                                                                      costo,
                                                                      fecha_vencimiento,
                                                                      lote)
                                                 SELECT          a.toma_fisica_id,
                                                                      a.etiqueta,
                                                                      2 as num_conteo,
                                                                      '0' as sw_manual,
                                                                      t.empresa_id,
                                                                      t.centro_utilidad,
                                                                      a.bodega,
                                                                      a.codigo_producto,
                                                                      i.existencia,
                                                                      ".$conteo_2['conteo_2']." as nueva_existencia,
                                                                      i.costo,
                                                                      a.fecha_vencimiento,
                                                                      a.lote       
                                              FROM                inv_toma_fisica as t,
                                                                       inv_toma_fisica_d as a
                                              LEFT JOIN          inv_toma_fisica_conteos AS y
                                                              ON(
                                                                  y.toma_fisica_id = a.toma_fisica_id
                                                                  AND y.etiqueta = a.etiqueta
                                                                  AND y.num_conteo = 2
                                                              )
                                              LEFT JOIN         inv_toma_fisica_update AS u
                                                            ON(
                                                                u.toma_fisica_id = a.toma_fisica_id
                                                                AND u.etiqueta = a.etiqueta
                                                            ),
                                                                inv_toma_fisica_detalle_inicial as i
                                            WHERE         t.toma_fisica_id = ".$toma."
                                            AND              a.toma_fisica_id = t.toma_fisica_id
                                            AND             a.codigo_producto = '".$conteo_2['codigo_producto']."'
                                            AND             a.fecha_vencimiento='".$conteo_2['fecha_vencimiento']."'
                                            AND             a.lote='".$conteo_2['lote']."'
                                            AND              u.toma_fisica_id IS NULL
                                            AND              i.toma_fisica_id = a.toma_fisica_id
                                            AND             i.empresa_id = a.empresa_id
                                            AND             i.centro_utilidad = a.centro_utilidad
                                            AND             i.bodega = a.bodega
                                            AND             i.codigo_producto = a.codigo_producto
                                            AND             a.fecha_vencimiento=i.fecha_vencimiento
                                            AND             a.lote=i.lote; ";
                                            
                if(!$resultado = $this->ConexionBaseDatos($sql))
               {
                return false;
                }
       }
    }

       
  }         
}


/**************************************
*
***************************************/
function ContarParaCierre($toma_fisica)
{
		 $sql="SELECT 
		(
		  SELECT COUNT(*)  
		  FROM tomas_fisicas
		  WHERE toma_fisica_id =".$toma_fisica."
		  AND nueva_existencia IS NULL
		  AND conteo_1 IS NOT NULL
		  AND conteo_2 IS NULL
		)AS cont_conteo1,
		(
		  SELECT COUNT(*) 
		  FROM tomas_fisicas
		  WHERE toma_fisica_id =".$toma_fisica."
		  AND nueva_existencia IS NULL
		  AND conteo_2 IS NOT NULL
		  AND conteo_3 IS NULL
		)AS cont_conteo2,
		(
		  SELECT COUNT(*) 
		  FROM tomas_fisicas
		  WHERE toma_fisica_id =".$toma_fisica."
		  AND nueva_existencia IS NULL
		  AND conteo_3 IS NOT NULL
		)AS cont_conteo3,
		(
		  SELECT COUNT(*)
		  FROM tomas_fisicas
		  WHERE toma_fisica_id = ".$toma_fisica."
		  AND nueva_existencia IS NULL
		  AND conteo_1 IS NULL
		) AS sin_contar,
		(
		SELECT COUNT(*)
		FROM tomas_fisicas
		WHERE toma_fisica_id = ".$toma_fisica."
		AND nueva_existencia IS NOT NULL
		) AS cuadrados,

		(
		  SELECT SUM(nueva_existencia*costo)
		  FROM tomas_fisicas
		  WHERE toma_fisica_id = ".$toma_fisica."
		  AND nueva_existencia IS NOT NULL
		)AS inv_actual,

		(
		 SELECT SUM(existencia*costo)
		  FROM tomas_fisicas
		  WHERE toma_fisica_id = ".$toma_fisica."
		  AND nueva_existencia IS NOT NULL
		)AS inv_anterior";
/*print_r($sql);*/
if(!$resultado = $this->ConexionBaseDatos($sql))
       return $this->frmError['MensajeError']; 
         //false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
}


function InconsistenciasC1($toma_fisica)
{
 $sql="  SELECT *  
         FROM tomas_fisicas
         WHERE toma_fisica_id =".$toma_fisica."
         AND nueva_existencia IS NOT NULL
         AND conteo_1 IS NOT NULL ";


if(!$resultado = $this->ConexionBaseDatos($sql))
       return $this->frmError['MensajeError']; 
         //false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
}

function InconsistenciasC2($toma_fisica)
{
 $sql="  SELECT *  
         FROM tomas_fisicas
         WHERE toma_fisica_id =".$toma_fisica."
         AND nueva_existencia IS NOT NULL
         AND conteo_2 IS NOT NULL
          ";

      
      if(!$resultado = $this->ConexionBaseDatos($sql))
       return $this->frmError['MensajeError']; 
         //false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
}

function InconsistenciasC3($toma_fisica)
{
 $sql="  SELECT *  
         FROM tomas_fisicas
         WHERE toma_fisica_id =".$toma_fisica."
         AND nueva_existencia IS NOT NULL
         AND conteo_3 IS NOT NULL
          ";

      
      if(!$resultado = $this->ConexionBaseDatos($sql))
       return $this->frmError['MensajeError']; 
         //false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
}

function InconSistemaVsTomaF($toma_fisica,$empresa,$cu,$bodega)
{
 /*$sql="  SELECT a.*,b.descripcion  
         FROM   inv_toma_fisica_update as a,inventarios_productos as b
         WHERE  a.toma_fisica_id =".$toma_fisica."
         AND    a.empresa_id='".$empresa."'
         AND    a.centro_utilidad='".$cu."'
         AND    a.bodega='".$bodega."'
         AND    a.codigo_producto=b.codigo_producto
         AND    a.existencia < a.nueva_existencia  ";*/
	$sql .= "
				SELECT
							a.toma_fisica_id,
							a.centro_utilidad, 	
							a.bodega, 	
							a.empresa_id, 	
							a.descripcion_bodega, 	
							a.etiqueta, 	
							a.etiqueta_x_producto, 	
							a.codigo_producto,	
							fc_descripcion_producto(a.codigo_producto)as descripcion,
							a.existencia, 	
							a.fecha_vencimiento, 	
							a.lote, 	
							a.costo, 	
							a.sw_ajusteautomatico, 	
							a.conteo_1, 	
							a.validacion_conteo_1, 	
							a.diferencia_1, 	
							a.conteo_2, 	
							a.validacion_conteo_2, 	
							a.diferencia_2, 	
							a.diferencia_1con2, 	
							a.conteo_3, 	
							a.validacion_conteo_3, 	
							a.diferencia_3, 	
							a.diferencia_2con3, 	
							a.diferencia_1con3, 	
							a.nueva_existencia, 	
							a.diferencia, 	
							a.num_conteo_nueva_existencia, 	
							a.sw_manual,
							CASE 
							WHEN diferencia <0 THEN 
							abs(diferencia) END as ajuste_egreso,
							CASE 
							WHEN diferencia >0 THEN 
							abs(diferencia) END as ajuste_ingreso
				FROM
							tomas_fisicas as a
							WHERE TRUE
							AND a.nueva_existencia IS NOT NULL
							AND a.existencia <> a.nueva_existencia
							AND a.toma_fisica_id = '".trim($toma_fisica)."'
				ORDER BY a.etiqueta_x_producto
	";

      
      if(!$resultado = $this->ConexionBaseDatos($sql))
       return $this->frmError['MensajeError']; 
         //false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
}
/*
*
*/
function BuscarTomaFProductosCuadrados($toma_fisica)
{
 $sql="  SELECT count(a.*) as total
            FROM   inv_toma_fisica_update as a
            WHERE  a.toma_fisica_id =".$toma_fisica."
           AND    a.sw_cuadre ='1'
          ";

      
      if(!$resultado = $this->ConexionBaseDatos($sql))
       return $this->frmError['MensajeError']; 
         //false;

      $documentos=Array();
      if(!$resultado->EOF)
      {
        $documentos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
}

/******************************************************************************
*ELIMINAR PRODUCTO CUADRADO
********************************************************************************/
function EliminarAjuste($toma_fisica_id,$etiqueta)
{
 
$sql="DELETE FROM inv_toma_fisica_update
      WHERE
       toma_fisica_id=".$toma_fisica_id."
       AND etiqueta=".$etiqueta;
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Ajuste Eliminada Correctamente";
           return $cad;
         }   
}




/*************************************************************************/
function AddCuadrar($toma_fisica_id,
            $etiqueta,
            $num_conteo,
            $sw_manual,
            $empresa_id,
            $centro_utilidad,
            $bodega,
            $codigo_producto,
            $existencia,
			$nueva_existencia,
            $costo,
			$lote,
			$fecha_vencimiento)
{

    $sql="INSERT INTO inv_toma_fisica_update(
                                                                    toma_fisica_id,
                                                                    etiqueta,
                                                                    num_conteo,
                                                                    sw_manual,
                                                                    empresa_id,
                                                                    centro_utilidad,
                                                                    bodega,
                                                                    codigo_producto,
                                                                    existencia,
                                                                    nueva_existencia,
                                                                    costo,
																	fecha_vencimiento,
																	lote)
														VALUES (".trim($toma_fisica_id).",
														".trim($etiqueta).",
														".trim($num_conteo).",
														'".trim($sw_manual)."',
														'".trim($empresa_id)."',
														'".trim($centro_utilidad)."',
														'".trim($bodega)."',
														'".trim($codigo_producto)."',
														".trim($existencia).",
														".trim($nueva_existencia).",
														".trim($costo).",
														'".trim($fecha_vencimiento)."',
														'".trim($lote)."')";

if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la insercion";
         return $cad;
         
       }
      else
       {      
         $cad="Producto Cuadrado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }

}

/*************************************************************************/
function AjustesAutomaticos($formulario)
{

    $sql=" INSERT INTO inv_toma_fisica_update
			(
			toma_fisica_id, 	
			etiqueta, 	
			num_conteo, 	
			sw_manual,	
			empresa_id, 	
			centro_utilidad, 	
			bodega, 	
			codigo_producto, 	
			existencia, 	
			nueva_existencia, 	
			costo, 
			fecha_vencimiento, 	
			lote
			) ";
		$sql .="SELECT
				a.toma_fisica_id, 	
				a.etiqueta,
				'".trim($formulario['numero_conteos'])."'::integer AS num_conteo,
				'0' AS sw_manual,
				a.empresa_id, 	
				a.centro_utilidad, 	
				a.bodega, 	
				a.codigo_producto, 	
				a.existencia,
				CASE 
				WHEN '".trim($formulario['numero_conteos'])."' = '1' AND a.diferencia_1 =0
				THEN conteo_1
				WHEN '".trim($formulario['numero_conteos'])."' = '2' AND (abs(a.diferencia_2::integer)+abs(a.diferencia_1con2::integer)) =0
				THEN conteo_2
				WHEN '".trim($formulario['numero_conteos'])."' = '3' AND (abs(diferencia_3::integer)+abs(diferencia_1con3::integer)+abs(diferencia_2con3::integer)) =0
				THEN conteo_3
				ELSE	a.".trim($formulario['opc'])." END as  nueva_existencia,	";
	$sql .= 	"a.costo, 	
				a.fecha_vencimiento, 	
				a.lote	
				FROM
				tomas_fisicas as a
				WHERE TRUE
				AND a.toma_fisica_id = '".trim($formulario['toma_fisica_id'])."'
				AND a.nueva_existencia IS NULL
				AND a.validacion_conteo_".trim($formulario['numero_conteos'])." = '1'
				AND a.sw_ajusteautomatico = '1' ";
		$sql .=" UNION ";
		$sql .="SELECT
				a.toma_fisica_id, 	
				a.etiqueta,
				'".trim($formulario['numero_conteos'])."'::integer AS num_conteo,
				'0' AS sw_manual,
				a.empresa_id, 	
				a.centro_utilidad, 	
				a.bodega, 	
				a.codigo_producto, 	
				a.existencia,
				0 as  nueva_existencia,	";
	$sql .= 	"a.costo, 	
				a.fecha_vencimiento, 	
				a.lote	
				FROM
				tomas_fisicas as a
				WHERE TRUE
				AND a.toma_fisica_id = '".trim($formulario['toma_fisica_id'])."'
				AND a.nueva_existencia IS NULL
				AND a.validacion_conteo_".trim($formulario['numero_conteos'])." != '1'
				AND a.sw_ajusteautomatico = '1';";

if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="ERROR EN LA OPERACION";
         return $cad;
         
       }
      else
       {      
         $cad="PRODUCTOS AJUSTADOS SATISFACTORIAMENTE";
         $rst->Close();
         return $cad;
       }
	   
	   

}
function GetNoCuadre($toma,$etiqueta)
{
  /*$sql="SELECT
          x.*,
          y.conteo as conteo_1,
          CASE WHEN y.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_1,
          (x.existencia - y.conteo) as diferencia_1,
          z.conteo as conteo_2,
          CASE WHEN z.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_2,
          (x.existencia - z.conteo) as diferencia_2,
          w.conteo as conteo_3,
          CASE WHEN w.usuario_valido IS NULL THEN '0' ELSE '1' END as validacion_conteo_3,
          (x.existencia - w.conteo) as diferencia_3
      FROM
      (
          SELECT
              a.toma_fisica_id,
              a.etiqueta,
              a.centro_utilidad,
              a.bodega,
              a.empresa_id,
              b.codigo_producto,
              b.descripcion,
              b.unidad_id,
              c.descripcion as descripcion_unidad,
              e.existencia_actual as existencia,
			  e.lote,
			  e.fecha_vencimiento,
              f.costo
          FROM
              inv_toma_fisica_d as a,
              inventarios_productos as b,
              unidades as c,
              existencias_bodegas_lote_fv as e,
              inventarios as f
      
          WHERE
              a.toma_fisica_id = ".$toma."
              AND a.etiqueta = ".$etiqueta."
              AND b.codigo_producto = a.codigo_producto
              AND c.unidad_id = b.unidad_id
              AND e.empresa_id = a.empresa_id
              AND e.centro_utilidad  = a.centro_utilidad
              AND e.bodega = a.bodega
              AND e.codigo_producto = a.codigo_producto
              AND e.fecha_vencimiento=a.fecha_vencimiento
              AND e.lote=a.lote
              AND a.empresa_id = f.empresa_id
              AND a.codigo_producto = f.codigo_producto

      ) AS x LEFT JOIN inv_toma_fisica_conteos AS y
      ON(
          y.toma_fisica_id = x.toma_fisica_id
          AND y.etiqueta = x.etiqueta
          AND y.num_conteo = 1
      )
      LEFT JOIN inv_toma_fisica_conteos AS z
      ON(
          z.toma_fisica_id = x.toma_fisica_id
          AND z.etiqueta = x.etiqueta
          AND z.num_conteo = 2
      )
      LEFT JOIN inv_toma_fisica_conteos AS w
      ON(
          w.toma_fisica_id = x.toma_fisica_id
          AND w.etiqueta = x.etiqueta
          AND w.num_conteo = 3
      )";*/
	  
	 $sql="SELECT
                        toma_fisica_id,
						bodega,
						empresa_id,
						centro_utilidad,
						descripcion_bodega,
						etiqueta,
						etiqueta_x_producto,
						codigo_producto,
						fc_descripcion_producto(codigo_producto) as descripcion,
						unidad_id,
						descripcion_unidad,
						existencia,
						fecha_vencimiento,
						lote,
						costo,
						sw_ajusteautomatico,
						conteo_1,
						validacion_conteo_1,
						diferencia_1,
						conteo_2,
						validacion_conteo_2,
						diferencia_2,
						diferencia_1con2,
						conteo_3,
						validacion_conteo_3,
						diferencia_3,
						diferencia_2con3,
						diferencia_1con3,
						nueva_existencia,
						diferencia,
						num_conteo_nueva_existencia,
						sw_manual
            FROM   tomas_fisicas
            WHERE  toma_fisica_id = ".$toma."
			AND 		etiqueta = ".$etiqueta." ";

      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;  
  
}

function GetValidacionCont($toma_fisica_id,$etiqueta,$num_conteo)
{
  $sql= "
           SELECT
          CASE WHEN a.usuario_valido IS NULL THEN '0' ELSE '1' END as validado
          FROM inv_toma_fisica_conteos a,
                    inv_toma_fisica_d b
          WHERE b.toma_fisica_id = $toma_fisica_id
          AND b.etiqueta_x_producto = $etiqueta
          AND a.toma_fisica_id = b.toma_fisica_id
          AND   a.etiqueta = b.etiqueta
          AND a.num_conteo = $num_conteo
             ";
  /*"
      SELECT
      CASE WHEN usuario_valido IS NULL THEN '0' ELSE '1' END as validado
      FROM inv_toma_fisica_conteos
      WHERE toma_fisica_id = $toma_fisica_id
      AND etiqueta = $etiqueta
      AND num_conteo = $num_conteo
   ";*/
  
  if(!$resultado = $this->ConexionBaseDatos($sql)) return false;
  list($salida)=$resultado->FetchRow();
  $resultado->Close();
  return $salida;
}
/*
 *
 */
function SacarNoCuadroC3CuadreAutomatico($toma)
{
    $sql="SELECT
                        etiqueta,
                        codigo_producto,
                        empresa_id,
                        descripcion,
                        costo,
                        existencia,
                        fecha_vencimiento,
                        lote,
                        descripcion_unidad,
                        conteo_1,
                        conteo_2,
                        conteo_3,
                        diferencia_3,
                        diferencia_1con3,
                        diferencia_2con3,
                        validacion_conteo_3,
                        sw_ajusteautomatico
            FROM   tomas_fisicas
            WHERE toma_fisica_id=".$toma."
            AND      nueva_existencia IS NULL
            AND      conteo_3 IS NOT NULL";
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[$resultado->fields[9]][$resultado->fields[10]]= $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}
function SacarNoCuadroC3($toma,$numero_conteos,$buscador,$offset)
{
    $sql="SELECT
                        a.toma_fisica_id,
						a.bodega,
						a.empresa_id,
						a.descripcion_bodega,
						a.etiqueta,
						a.etiqueta_x_producto,
						a.codigo_producto,
						fc_descripcion_producto(a.codigo_producto) as descripcion,
						a.unidad_id,
						a.descripcion_unidad,
						a.existencia,
						a.fecha_vencimiento,
						a.lote,
						a.costo,
						a.sw_ajusteautomatico,
						a.conteo_1,
						a.validacion_conteo_1,
						a.diferencia_1,
						a.conteo_2,
						a.validacion_conteo_2,
						a.diferencia_2,
						a.diferencia_1con2,
						a.conteo_3,
						a.validacion_conteo_3,
						a.diferencia_3,
						a.diferencia_2con3,
						a.diferencia_1con3,
						a.nueva_existencia,
						a.diferencia,
						a.num_conteo_nueva_existencia,
						a.sw_manual
            FROM   tomas_fisicas as a
			JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
			JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
			AND (b.clase_id = c.clase_id)
			AND (b.subclase_id = c.subclase_id)
			JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
			AND (c.clase_id = d.clase_id)
            WHERE a.toma_fisica_id=".$toma."
            AND      a.nueva_existencia IS NULL
            AND      a.conteo_3 IS NOT NULL
			AND		(abs(a.diferencia_3::integer)+abs(a.diferencia_1con3::integer)+abs(a.diferencia_2con3::integer)) >0	";
      if($buscador['etiqueta'])
	  $sql .= " AND a.etiqueta_x_producto = '".trim($buscador['etiqueta'])."' ";
	  if($buscador['producto'])
	  $sql .= " AND a.descripcion ILIKE '%".trim($buscador['producto'])."%' ";
	  if($buscador['clase_descripcion'])
	  $sql .= " AND d.descripcion ILIKE '%".trim($buscador['clase_descripcion'])."%' ";
	  
	  $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",100,$offset); 
      $sql .= " ORDER BY a.etiqueta_x_producto ";        
	  $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
	
	/*print_r($sql);*/
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}



function SacarNoCuadroC2($toma,$numero_conteos,$buscador,$offset)
{
    $sql="SELECT
				a.etiqueta,
				a.etiqueta_x_producto,
				a.codigo_producto,
				fc_descripcion_producto(a.codigo_producto)as descripcion,
				a.empresa_id,
				a.costo,
				a.existencia,
				a.descripcion_unidad,
				a.fecha_vencimiento,
				a.lote,
				a.conteo_1,
				a.conteo_2,
				a.diferencia_1,
				a.diferencia_2,
				a.diferencia_1con2,
				a.validacion_conteo_2
          FROM    tomas_fisicas  a
			JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
			JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
			AND (b.clase_id = c.clase_id)
			AND (b.subclase_id = c.subclase_id)
			JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
			AND (c.clase_id = d.clase_id)
          WHERE  a.toma_fisica_id=".$toma."
          AND       a.nueva_existencia IS NULL
          AND       a.conteo_2 IS NOT NULL
          AND       a.conteo_3 IS NULL
		  AND		(abs(a.diferencia_2::integer)+abs(a.diferencia_1con2::integer)) >0	  ";
		if($buscador['etiqueta'])
		$sql .= " AND a.etiqueta_x_producto = '".trim($buscador['etiqueta'])."' ";
		if($buscador['producto'])
		$sql .= " AND a.descripcion ILIKE '%".trim($buscador['producto'])."%' ";
		if($buscador['clase_descripcion'])
		$sql .= " AND d.descripcion ILIKE '%".trim($buscador['clase_descripcion'])."%' ";

		$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",100,$offset); 
		$sql .= " ORDER BY a.etiqueta_x_producto ";        
		$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
    /*print_r($sql);*/
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}




//NO CUADRADOS CONTEO1
function SacarNoCuadroC1($toma,$numero_conteos,$buscador,$offset)
{
$sql="SELECT 	a.etiqueta,
						a.etiqueta_x_producto,
						a.codigo_producto,
						fc_descripcion_producto(a.codigo_producto)as descripcion,
						a.empresa_id,
						a.costo,
						a.existencia,
						a.descripcion_unidad,
						a.fecha_vencimiento,
						a.lote,
						a.conteo_1,
						a.diferencia_1,
						a.validacion_conteo_1
		FROM   tomas_fisicas a
		JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
		JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
		AND (b.clase_id = c.clase_id)
		AND (b.subclase_id = c.subclase_id)
		JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
		AND (c.clase_id = d.clase_id)
          WHERE a.toma_fisica_id=".$toma."
          AND      a.nueva_existencia IS NULL
          AND      a.conteo_1 IS NOT NULL
		  AND      a.conteo_2 IS NULL ";
	if($numero_conteos == '1')
	$sql .= "	AND		(abs(a.diferencia_1::integer)) >0	";
	if($buscador['etiqueta'])
	$sql .= " AND a.etiqueta_x_producto = '".trim($buscador['etiqueta'])."' ";
	if($buscador['producto'])
	$sql .= " AND a.descripcion ILIKE '%".trim($buscador['producto'])."%' ";
	if($buscador['clase_descripcion'])
	$sql .= " AND d.descripcion ILIKE '%".trim($buscador['clase_descripcion'])."%' ";

	$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",100,$offset); 
	$sql .= " ORDER BY a.etiqueta_x_producto ";        
	$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
	
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}
/*
*
*/
function SacarNoCuadroC1Reporte($toma)
{
 $sql="SELECT 	etiqueta,
						etiqueta_x_producto,
						codigo_producto,
						fc_descripcion_producto(codigo_producto)as descripcion,
						empresa_id,
						costo,
						existencia as existencia_actual,
						descripcion_unidad,
						fecha_vencimiento,
						lote,
						conteo_1,
						abs(diferencia_1) as diferencia_1,
						validacion_conteo_1
          FROM   tomas_fisicas
          WHERE toma_fisica_id=".trim($toma)."
                    AND      conteo_1 IS NOT NULL ";
	$sql .= "	AND		(abs(diferencia_1::integer)) >0	";
	$sql .= " ORDER BY etiqueta_x_producto ";
         
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}
  /*
    */
    function ObtenerReporteNoCuadroC1Reporte($toma)
    {
      //$ruta_archivo =  GetVarConfigAplication('DIR_SIIS')."/reportes_sql/".$datos['nombre'].".sql";
      //$lines = file($ruta_archivo);
        
      $sql = "SELECT  a.etiqueta,
                        a.codigo_producto,
                        fc_descripcion_producto(a.codigo_producto)as descripcion_prod,
                        d.descripcion as laboratorio,
                        a.existencia as cantidad,
                        a.conteo_1 as cantidad_conteo,
                        a.diferencia_1 as cantidad_diferencia,
                        a.costo*a.diferencia_1 as costo_diferencia,
                        a.fecha_vencimiento,
                        a.lote,
                        a.costo,
                        bod_ub.descripcion as ubicacion
           FROM    tomas_fisicas as a,
                        inventarios_productos as b,
                        inv_clases_inventarios as c, 
                        inv_laboratorios as d,
                        existencias_bodegas_lote_fv as e LEFT JOIN bodegas_ubicaciones AS bod_ub ON( bod_ub.empresa_id = e.empresa_id
                                                                                                  AND bod_ub.centro_utilidad  = e.centro_utilidad
                                                                                                  AND bod_ub.ubicacion_id = e.ubicacion_id
                                                                                                  AND bod_ub.bodega = e.bodega)
           WHERE  a.toma_fisica_id=".$toma." 
           AND       b.codigo_producto = a.codigo_producto
           AND       b.grupo_id=c.grupo_id
           AND       b.clase_id=c.clase_id
           AND       a.bodega = e.bodega
           AND       a.codigo_producto = e.codigo_producto
           AND       a.fecha_vencimiento = e.fecha_vencimiento
           AND       a.lote = e.lote
           AND       c.laboratorio_id=d.laboratorio_id
           AND       a.conteo_1 IS NOT NULL
           AND       a.conteo_2 IS NULL";
      //foreach ($lines as $line_num => $line) 
        //$sql .= $line;
      
      //$sql = str_replace("_1","'".$this->DividirFecha($datos['fecha_inicial'],"-")."'::date",$sql);
      //$sql = str_replace("_2","'".$this->DividirFecha($datos['fecha_final'],"-")."'::date",$sql);
     
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        return $rst;
    }

 /*
*
*/
function SacarNoCuadroC2Reporte($toma)
{
 /*$sql=" SELECT  a.etiqueta,
                        a.codigo_producto,
                        fc_descripcion_producto(a.codigo_producto)as descripcion_prod,
                        d.descripcion as laboratorio,
                        a.existencia as cantidad,
                        a.conteo_2 as cantidad_conteo,
                        a.diferencia_2 as cantidad_diferencia,
                        a.costo*a.diferencia_2 as costo_diferencia,
                        a.fecha_vencimiento,
                        a.lote,
                        a.costo,
                        bod_ub.descripcion as ubicacion
           FROM    tomas_fisicas as a,
                        inventarios_productos as b,
                        inv_clases_inventarios as c, 
                        inv_laboratorios as d,
                        existencias_bodegas_lote_fv as e LEFT JOIN bodegas_ubicaciones AS bod_ub ON( bod_ub.empresa_id = e.empresa_id
                                                                                                  AND bod_ub.centro_utilidad  = e.centro_utilidad
                                                                                                  AND bod_ub.ubicacion_id = e.ubicacion_id
                                                                                                  AND bod_ub.bodega = e.bodega)
           WHERE  a.toma_fisica_id=".$toma." 
           AND       b.codigo_producto = a.codigo_producto
           AND       b.grupo_id=c.grupo_id
           AND       b.clase_id=c.clase_id
           AND       a.bodega = e.bodega
           AND       a.codigo_producto = e.codigo_producto
           AND       a.fecha_vencimiento = e.fecha_vencimiento
           AND       a.lote = e.lote
           AND       c.laboratorio_id=d.laboratorio_id
           AND       a.conteo_2 IS NOT NULL
           AND       a.conteo_3 IS NULL";*/
	$sql="SELECT
					etiqueta,
					etiqueta_x_producto,
					codigo_producto,
					fc_descripcion_producto(codigo_producto)as descripcion,
					empresa_id,
                      costo,
                      existencia as existencia_actual,
                      descripcion_unidad,
                      fecha_vencimiento,
                      lote,
                      conteo_1,
                      conteo_2,
                      diferencia_1,
                      diferencia_2,
                      abs(diferencia_1con2) as diferencia_1con2,
                      validacion_conteo_2
          FROM    tomas_fisicas
          WHERE  toma_fisica_id=".$toma."
          AND       conteo_2 IS NOT NULL
          AND		(abs(diferencia_1con2::integer)) >0
		  ORDER BY etiqueta_x_producto";
    
         
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}
  /*
    */
    function ObtenerReporteNoCuadroC2Reporte($toma)
    {
      //$ruta_archivo =  GetVarConfigAplication('DIR_SIIS')."/reportes_sql/".$datos['nombre'].".sql";
      //$lines = file($ruta_archivo);
        
      $sql = "SELECT  a.etiqueta,
                        a.codigo_producto,
                        fc_descripcion_producto(a.codigo_producto)as descripcion_prod,
                        d.descripcion as laboratorio,
                        a.existencia as cantidad,
                        a.conteo_2 as cantidad_conteo,
                        a.diferencia_2 as cantidad_diferencia,
                        a.costo*a.diferencia_2 as costo_diferencia,
                        a.fecha_vencimiento,
                        a.lote,
                        a.costo,
                        bod_ub.descripcion as ubicacion
           FROM    tomas_fisicas as a,
                        inventarios_productos as b,
                        inv_clases_inventarios as c, 
                        inv_laboratorios as d,
                        existencias_bodegas_lote_fv as e LEFT JOIN bodegas_ubicaciones AS bod_ub ON( bod_ub.empresa_id = e.empresa_id
                                                                                                  AND bod_ub.centro_utilidad  = e.centro_utilidad
                                                                                                  AND bod_ub.ubicacion_id = e.ubicacion_id
                                                                                                  AND bod_ub.bodega = e.bodega)
           WHERE  a.toma_fisica_id=".$toma." 
           AND       b.codigo_producto = a.codigo_producto
           AND       b.grupo_id=c.grupo_id
           AND       b.clase_id=c.clase_id
           AND       a.bodega = e.bodega
           AND       a.codigo_producto = e.codigo_producto
           AND       a.fecha_vencimiento = e.fecha_vencimiento
           AND       a.lote = e.lote
           AND       c.laboratorio_id=d.laboratorio_id
           AND       a.conteo_2 IS NOT NULL
           AND       a.conteo_3 IS NULL";
      //foreach ($lines as $line_num => $line) 
        //$sql .= $line;
      
      //$sql = str_replace("_1","'".$this->DividirFecha($datos['fecha_inicial'],"-")."'::date",$sql);
      //$sql = str_replace("_2","'".$this->DividirFecha($datos['fecha_final'],"-")."'::date",$sql);
     
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        return $rst;
    } 
/*
*
*/
function SacarNoCuadroC3Reporte($toma)
{
 /*$sql=" SELECT  a.etiqueta,
                        a.codigo_producto,
                        fc_descripcion_producto(a.codigo_producto)as descripcion_prod,
                        d.descripcion as laboratorio,
                        a.existencia as cantidad,
                        a.conteo_3 as cantidad_conteo,
                        a.diferencia_3 as cantidad_diferencia,
                        a.costo*a.diferencia_3 as costo_diferencia,
                        a.fecha_vencimiento,
                        a.lote,
                        a.costo,
                        bod_ub.descripcion as ubicacion
           FROM    tomas_fisicas as a,
                        inventarios_productos as b,
                        inv_clases_inventarios as c, 
                        inv_laboratorios as d,
                        existencias_bodegas_lote_fv as e LEFT JOIN bodegas_ubicaciones AS bod_ub ON( bod_ub.empresa_id = e.empresa_id
                                                                                                  AND bod_ub.centro_utilidad  = e.centro_utilidad
                                                                                                  AND bod_ub.ubicacion_id = e.ubicacion_id
                                                                                                  AND bod_ub.bodega = e.bodega)
           WHERE  a.toma_fisica_id=".$toma." 
           AND       b.codigo_producto = a.codigo_producto
           AND       b.grupo_id=c.grupo_id
           AND       b.clase_id=c.clase_id
           AND       a.bodega = e.bodega
           AND       a.codigo_producto = e.codigo_producto
           AND       a.fecha_vencimiento = e.fecha_vencimiento
           AND       a.lote = e.lote
           AND       c.laboratorio_id=d.laboratorio_id
           AND       a.conteo_3 IS NOT NULL";*/
	$sql="SELECT
                         toma_fisica_id,
						bodega,
						empresa_id,
						descripcion_bodega,
						etiqueta,
						etiqueta_x_producto,
						codigo_producto,
						fc_descripcion_producto(codigo_producto) as descripcion,
						unidad_id,
						descripcion_unidad,
						existencia as existencia_actual,
						fecha_vencimiento,
						lote,
						costo,
						sw_ajusteautomatico,
						conteo_1,
						validacion_conteo_1,
						diferencia_1,
						conteo_2,
						validacion_conteo_2,
						diferencia_2,
						diferencia_1con2,
						conteo_3,
						validacion_conteo_3,
						abs(diferencia_3) as diferencia_3,
						abs(diferencia_2con3) as diferencia_2con3,
						abs(diferencia_1con3) as diferencia_1con3,
						nueva_existencia,
						diferencia,
						num_conteo_nueva_existencia,
						sw_manual
            FROM   tomas_fisicas
            WHERE toma_fisica_id=".$toma."
            AND      conteo_3 IS NOT NULL
			AND		(abs(diferencia_3::integer)+abs(diferencia_1con3::integer)+abs(diferencia_2con3::integer)) >0	";
		   
         
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
} 
  /*
    */
   function ObtenerReporteNoCuadroC3Reporte($toma)
   {
      $sql = "SELECT  a.etiqueta,
                        a.codigo_producto,
                        fc_descripcion_producto(a.codigo_producto)as descripcion_prod,
                        d.descripcion as laboratorio,
                        a.existencia as cantidad,
                        a.conteo_3 as cantidad_conteo,
                        a.diferencia_3 as cantidad_diferencia,
                        a.costo*a.diferencia_3 as costo_diferencia,
                        a.fecha_vencimiento,
                        a.lote,
                        a.costo,
                        bod_ub.descripcion as ubicacion
           FROM    tomas_fisicas as a,
                        inventarios_productos as b,
                        inv_clases_inventarios as c, 
                        inv_laboratorios as d,
                        existencias_bodegas_lote_fv as e LEFT JOIN bodegas_ubicaciones AS bod_ub ON( bod_ub.empresa_id = e.empresa_id
                                                                                                  AND bod_ub.centro_utilidad  = e.centro_utilidad
                                                                                                  AND bod_ub.ubicacion_id = e.ubicacion_id
                                                                                                  AND bod_ub.bodega = e.bodega)
           WHERE  a.toma_fisica_id=".$toma." 
           AND       b.codigo_producto = a.codigo_producto
           AND       b.grupo_id=c.grupo_id
           AND       b.clase_id=c.clase_id
           AND       a.bodega = e.bodega
           AND       a.codigo_producto = e.codigo_producto
           AND       a.fecha_vencimiento = e.fecha_vencimiento
           AND       a.lote = e.lote
           AND       c.laboratorio_id=d.laboratorio_id
           AND       a.conteo_3 IS NOT NULL";
     
      if(!$rst = $this->ConexionBaseDatos($sql))	return false;
        return $rst;
  } 
   
function SacarSinConteo($toma,$buscador,$offset)
{
    $sql="SELECT
      a.etiqueta_x_producto as etiqueta,
      a.codigo_producto,
      fc_descripcion_producto(a.codigo_producto) as descripcion,
      a.descripcion_unidad, 
      a.costo,
      a.existencia,
      a.descripcion_unidad, 
	  a.fecha_vencimiento,
	  a.lote
      FROM tomas_fisicas as a
	  JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
	  JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
	  AND (b.clase_id = c.clase_id)
	  AND (b.subclase_id = c.subclase_id)
	  JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
	  AND (c.clase_id = d.clase_id)
      WHERE TRUE
	  AND a.conteo_1 IS NULL
      AND a.toma_fisica_id=".$toma." ";
	  if($buscador['etiqueta'])
	  $sql .= " AND a.etiqueta_x_producto = '".trim($buscador['etiqueta'])."' ";
	  if($buscador['producto'])
	  $sql .= " AND a.descripcion ILIKE '%".trim($buscador['producto'])."%' ";
	  if($buscador['clase_descripcion'])
	  $sql .= " AND d.descripcion ILIKE '%".trim($buscador['clase_descripcion'])."%' ";
	  
	  $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",100,$offset); 
      $sql .= " ORDER BY a.etiqueta_x_producto ";        
	  $sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
	  
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos; 
}
  
function SacarInfoConteo3($conteo3,$toma,$buscador,$numero_conteos,$offset)
{

$sql="  SELECT
                    a.toma_fisica_id,
                    a.etiqueta,
					a.etiqueta_x_producto,
                    a.codigo_producto,
                    fc_descripcion_producto(a.codigo_producto)as descripcion,
                    a.costo,
                    a.existencia,
                    a.descripcion_unidad,
                    a.conteo_1,
                    a.conteo_2,
                    a.conteo_3,
                    a.diferencia_3,
                    a.diferencia_1con3,
                    a.diferencia_2con3,
                    a.nueva_existencia,
                    a.sw_manual,
                    a.fecha_vencimiento,
                    a.lote
        FROM    tomas_fisicas as a
					JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
					JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
					AND (b.clase_id = c.clase_id)
					AND (b.subclase_id = c.subclase_id)
					JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
					AND (c.clase_id = d.clase_id)
        WHERE   a.nueva_existencia IS NOT NULL
        AND       a.toma_fisica_id =".$toma."
        AND       a.num_conteo_nueva_existencia =".$conteo3;
		if($buscador['etiqueta'])
		$sql .= " AND a.etiqueta_x_producto = '".trim($buscador['etiqueta'])."' ";
		if($buscador['producto'])
		$sql .= " AND a.descripcion ILIKE '%".trim($buscador['producto'])."%' ";
		if($buscador['clase_descripcion'])
		$sql .= " AND d.descripcion ILIKE '%".trim($buscador['clase_descripcion'])."%' ";

		$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",100,$offset); 
		$sql .= " ORDER BY a.etiqueta_x_producto ";        
		$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
		

if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;
}

  
function SacarInfoConteo2($conteo2,$toma)
{

$sql="SELECT
    toma_fisica_id,
    etiqueta,
    codigo_producto,
    descripcion,
    descripcion_unidad,
    costo,
    existencia,
    conteo_1,
    conteo_2,
    diferencia_1,
    diferencia_2,
    diferencia_1con2,
    nueva_existencia,
    sw_manual,
    fecha_vencimiento,
    lote

FROM tomas_fisicas
WHERE nueva_existencia IS NOT NULL
AND toma_fisica_id=".$toma."
AND num_conteo_nueva_existencia =".$conteo2;

if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;

      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }

      $resultado->Close();
      return $documentos;



}

function SacarInfoConteo($conteo1,$toma,$buscador,$numero_conteos,$offset)
{
    //$dbconn->debug=true;
    $sql="SELECT
    a.toma_fisica_id, 	
	a.bodega, 	
	a.empresa_id, 	
	a.descripcion_bodega, 	
	a.etiqueta, 	
	a.etiqueta_x_producto, 	
	a.codigo_producto, 	
	fc_descripcion_producto(a.codigo_producto)as descripcion, 	
	a.unidad_id, 	
	a.descripcion_unidad, 	
	a.existencia, 	
	a.fecha_vencimiento, 	
	a.lote, 	
	a.costo, 	
	a.sw_ajusteautomatico, 	
	a.conteo_1, 	
	a.validacion_conteo_1, 	
	a.diferencia_1, 	
	a.conteo_2, 	
	a.validacion_conteo_2, 	
	a.diferencia_2,	
	a.diferencia_1con2, 	
	a.conteo_3, 	
	a.validacion_conteo_3, 	
	a.diferencia_3, 	
	a.diferencia_2con3, 	
	a.diferencia_1con3, 	
	a.nueva_existencia, 	
	a.diferencia, 	
	a.num_conteo_nueva_existencia, 	
	a.sw_manual
    FROM tomas_fisicas a
	JOIN inventarios_productos as b ON (a.codigo_producto = b.codigo_producto)
	JOIN inv_subclases_inventarios as c ON (b.grupo_id = c.grupo_id)
	AND (b.clase_id = c.clase_id)
	AND (b.subclase_id = c.subclase_id)
	JOIN inv_clases_inventarios as d ON (c.grupo_id = d.grupo_id)
	AND (c.clase_id = d.clase_id)
    WHERE a.nueva_existencia IS NOT NULL
    AND a.toma_fisica_id =".trim($toma)."
    AND a.num_conteo_nueva_existencia =".trim($conteo1)." ";
	if($buscador['etiqueta'])
	$sql .= " AND a.etiqueta_x_producto = '".trim($buscador['etiqueta'])."' ";
	if($buscador['producto'])
	$sql .= " AND a.descripcion ILIKE '%".trim($buscador['producto'])."%' ";
	if($buscador['clase_descripcion'])
	$sql .= " AND d.descripcion ILIKE '%".trim($buscador['clase_descripcion'])."%' ";

	$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",100,$offset); 
	$sql .= " ORDER BY a.etiqueta_x_producto ";        
	$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset." ";
    /*print_r($sql);*/
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
}
function total_productos_lotes($empresa)
{
    //$dbconn->debug=true;
    $sql="SELECT a.empresa_id, a.bodega,a.codigo_producto,a.exis,a.lote,a.descripcion
            FROM (
            select b.*, 'lote' as lote
            from (
            (select f.empresa_id,f.bodega,codigo_producto, sum(existencia_actual) as exis,b.descripcion
            from existencias_bodegas_lote_fv f
            inner join bodegas as b on (b.bodega=f.bodega and b.empresa_id=f.empresa_id)
            where existencia_actual >0  and  f.empresa_id='$empresa'
            group by 1,2,3,5
            order by codigo_producto
            )
            EXCEPT
            (
            select b.empresa_id,b.bodega,codigo_producto,existencia as exis,b.descripcion
            from existencias_bodegas f
            inner join bodegas as b on (b.bodega=f.bodega and b.empresa_id=f.empresa_id)
            where existencia > 0  and  f.empresa_id='$empresa'
            order by codigo_producto
            ) ) as b
            where empresa_id='$empresa' 
            union
            select c.*, 'titular' as lote
            from (
            (select b.empresa_id,b.bodega,codigo_producto,existencia as exis,b.descripcion
            from existencias_bodegas f
            inner join bodegas as b on (b.bodega=f.bodega and b.empresa_id=f.empresa_id)
            where existencia >0  and  f.empresa_id='$empresa'
            order by codigo_producto
            )
            EXCEPT
            (
            select b.empresa_id,b.bodega,f.codigo_producto, sum(existencia_actual) as exis,b.descripcion
            from existencias_bodegas_lote_fv f
            inner join bodegas as b on (b.bodega=f.bodega and b.empresa_id=f.empresa_id)
            where existencia_actual > 0  and  f.empresa_id='$empresa'
            group by 1,2,3,5
            order by codigo_producto
            ) ) as c 

            ) as a ORDER BY a.bodega,a.codigo_producto";
   
    
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
}


function ActualizarLote($empresa,$bodega,$producto,$lote,$cantidad,$fecha)
{
//   $sql =" UPDATE existencias_bodegas_lote_fv
//            SET existencia_actual=".$cantidad."
//           WHERE codigo_producto='$producto'
//              AND lote= '$lote'   
//              AND fecha_vencimiento='$fecha'
//              AND empresa_id='$empresa' 
//              AND bodega='$bodega'";
//
//      if(!$resultado = $this->ConexionBaseDatos($sql))
//        {
//          $cad="Operacion Invalida";
//          return 0;
//        }else{
//          $cad="Lote Modificado Exitosamente";
//          return 1;  
//        }
    return 1;
    
}

//* Funcion donde se Consultan los centros de Utilidades de la farmacia.
//		* @return array $datos vector que contiene la informacion de la consulta.
//	*/
	   	function ListarCentrodeUtilidad($empresa)
		{
		
			$sql  = "SELECT   	empresa_id, centro_utilidad,descripcion,Ubicacion";
			$sql .= "           From centros_utilidad  ";
			$sql .= "WHERE      empresa_id='".$empresa."' order by descripcion asc ";
			if(!$rst = $this->ConexionBaseDatos($sql))
			return false;
			$datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;	
		}



/************************************************************************************
*
*Funcion que consulta el lote con mayor existencia
*
*************************************************************************************/    
    function  GetLotedeMayorExistencia($empresa,$bodega,$producto,$estado)
    { 
        if($estado=='0'){
            $and=" and existencia_actual>0 ";
            $order=" desc limit 1 ;";
        }elseif($estado=='1'){
            $and=" and existencia_actual>0 ";
            $order=" asc ;";
        }elseif($estado=='2'){
            $and="";
            $order=" desc limit 1 ;";
        }
            $sql="select lote,existencia_actual,fecha_vencimiento
                from
                existencias_bodegas_lote_fv
                where
                codigo_producto='$producto'
                and  empresa_id='$empresa' and bodega='$bodega' $and
                order by existencia_actual ".$order; 
             
 
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        if($estado=='0'||$estado=='3'){
         $documentos = $resultado->GetRowAssoc($ToUpper = false);
         $resultado->MoveNext();
        }else{
          $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
          $resultado->MoveNext();
        }
        
      }
      
      $resultado->Close();
      return $documentos;
        
    }
    
//    function BuscarProducto(){
//        if(!$resultado = $this->ConexionBaseDatos($sql))
//        return false;
//        
//      $documentos=Array();
//      while(!$resultado->EOF)
//      {
//        if($estado=='0'||$estado=='3'){
//         $documentos = $resultado->GetRowAssoc($ToUpper = false);
//         $resultado->MoveNext();
//        }else{
//          $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
//          $resultado->MoveNext();
//        }
//        
//      }
//      
//      $resultado->Close();
//      return $documentos;
//    }
    
     function Guardar_Detalle_inventario($empresa_id,$centro_utilidad,$bodega,$id_conteo_toma_fisica,$codigo_producto,$cantidad,
                                         $lote,$fecha_vencimiento,$usuario_id,$fecha_registro,$conteo)
  {
      $sql="  INSERT INTO inv_conteo_tomas_fisicas_detalle(
              empresa_id, 
              centro_utilidad, 
              bodega, 
              codigo_producto, 
              cantidad, 
              lote, 
              fecha_vencimiento, 
              usuario_id, 
              fecha_registro, 
              id_conteo_toma_fisica, 
              conteo
              )
                  VALUES
                               (    '".$empresa_id."',
                                    '".$centro_utilidad."',
                                    '".$bodega."',
                                    '".$codigo_producto."',
                                    '".$cantidad."',
                                    '".$lote."',
                                    '".$fecha_vencimiento."',
                                    '".$usuario_id."',
                                    '".$fecha_registro."',
                                    '".$id_conteo_toma_fisica."',
                                    '".$conteo."'); ";
    
   
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
     $this->mensajeDeError="EXITO EN LA OPERACION";
	 return true;
  } 
     function Update_Detalle_inventario($empresa_id,$centro_utilidad,$bodega,$id_conteo_toma_fisica,$codigo_producto,$cantidad,
                                         $lote,$fecha_vencimiento,$usuario_id,$fecha_registro,$conteo,$existente)
  {
     $cantidad= $cantidad+$existente;
      $sql="  UPDATE inv_conteo_tomas_fisicas_detalle
                SET cantidad='$cantidad'
              WHERE
                empresa_id=  '$empresa_id' AND
                centro_utilidad='$centro_utilidad' AND 
                bodega= '$bodega' AND 
                codigo_producto='$codigo_producto' and               
                lote='$lote' and
                fecha_vencimiento ='$fecha_vencimiento' and
                id_conteo_toma_fisica = '$id_conteo_toma_fisica' and 
                conteo='$conteo';";      
   
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
         return true;
  } 
     function InsertarCabecera($empresa_id,$nombre,$centro_utilidad,$bodega)
  {
      $sql="  INSERT INTO inv_conteo_tomas_fisicas
                                   ( empresa_id,
                                     centro_utilidad,                                     
                                     bodega,
                                     descripcion,
                                     usuario_id,
                                     fecha_registro,
                                     estado)
                  VALUES
                               (   '".trim($empresa_id)."',
                                   '".trim($centro_utilidad)."',
                                    '".trim($bodega)."',
                                    '".trim($nombre)."',
                                    '".UserGetUID()."',
                                      now(),
                                     '1')
                                     returning id_conteo_toma_fisica; ";
      
  
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
     $this->mensajeDeError="EXITO EN LA OPERACION";
	 return $rst;
  } 
  
   
/************************************************************************************
*
*Funcion que consulta las cabecera activa
*
*************************************************************************************/    
    function  ConsultarProductoExistente($empresa,$centro_utilidad,$bodega,$cabecera_id,$nombre_producto,$codigo_producto,$conteo,$fecha)
    { 
        if(!($conteo=='3')){
          $and=" and conteo='$conteo' ";   
        }
        if($codigo_producto!=""){
          $and.=" and a.codigo_producto='$codigo_producto' ";   
        }
        if($nombre_producto!=""){
          $and.=" and b.descripcion ilike '%".$nombre_producto."%' ";   
        }
        
        $sql="select a.codigo_producto,b.descripcion,a.lote,a.cantidad,a.fecha_vencimiento,a.conteo,c.nombre,a.inv_conteo_tomas_fisicas_detalle_id
                from inv_conteo_tomas_fisicas_detalle as a
                inner join inventarios_productos as b on (a.codigo_producto=b.codigo_producto)
                inner join system_usuarios as c on (a.usuario_id=c.usuario_id)
                where 
                a.empresa_id='$empresa' and a.bodega='$bodega' and a.centro_utilidad='$centro_utilidad' 
                $and and a.id_conteo_toma_fisica='$cabecera_id' and  a.fecha_vencimiento='$fecha'; "; 
     
        if(!$resultado = $this->ConexionBaseDatos($sql))
              return false;        
        $productos=Array();
            while(!$resultado->EOF)
            {        
              $productos = $resultado->GetRowAssoc($ToUpper = false);
              $productos= $productos['cantidad'] ;
             
              $resultado->MoveNext();
            }
            //echo "<pre>"; print_r($productos);
        $resultado->Close();
        return $productos; 
    }
    
/************************************************************************************
*
*Funcion que consulta las cabecera activa
*
*************************************************************************************/    
    function  ConsultarProducto($empresa,$centro_utilidad,$bodega,$cabecera_id,$nombre_producto,$codigo_producto,$conteo)
    { 
        if(!($conteo=='3')){
          $anda=" and conteo='$conteo' ";   
        }
        if($codigo_producto!=""){
          $anda.=" and a.codigo_producto='$codigo_producto' ";   
        }
        if($nombre_producto!=""){
          $and.=" where c.descripcion ilike '%".$nombre_producto."%' ";   
        }
        
        $sql="select *
                    from (
                    select a.codigo_producto, fc_descripcion_producto(a.codigo_producto) as descripcion,a.lote,a.cantidad,a.fecha_vencimiento,a.conteo,c.nombre,a.inv_conteo_tomas_fisicas_detalle_id
                            from inv_conteo_tomas_fisicas_detalle as a
                            inner join system_usuarios as c on (a.usuario_id=c.usuario_id)
                            inner join inventarios_productos as b on (a.codigo_producto=b.codigo_producto)
                            where                
                        a.empresa_id='$empresa' and a.bodega='$bodega' and a.centro_utilidad='$centro_utilidad' 
                        $anda and a.id_conteo_toma_fisica='$cabecera_id'
                            ) as c $and order by c.conteo; "; 
 
        if(!$resultado = $this->ConexionBaseDatos($sql))
              return false;        
        $productos=Array();
            while(!$resultado->EOF)
            {        
              $id = $resultado->GetRowAssoc($ToUpper = false);
              $productos[$id['inv_conteo_tomas_fisicas_detalle_id']] = $resultado->GetRowAssoc($ToUpper = false);
              $resultado->MoveNext();
            }
           
        $resultado->Close();
        return $productos; 
    }
    
    
    
/*Funcion que ejecutara el script para modificar un conteo*/
function modificarProducto($empresa,$centro_utilidad,$bodega,$cabecera_id,$codigo_producto,$conteo,$cantidad,$lote,$fecha,$key)
{
   $sql =" UPDATE inv_conteo_tomas_fisicas_detalle 
              SET      cantidad='".$cantidad."',
                       lote='".$lote."',
                       fecha_vencimiento='".$fecha."'
              WHERE    empresa_id='$empresa' 
                   and bodega='$bodega' 
                   and centro_utilidad='$centro_utilidad'  
                   and codigo_producto='$codigo_producto'
                   and conteo='$conteo'
           and id_conteo_toma_fisica='$cabecera_id'
                   and inv_conteo_tomas_fisicas_detalle_id ='$key'; ";
            
             

      if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida";
          return 0;
        } 
        else 
         {
           $cad="Lote Modificado Exitosamente";
            return 1;  
         }
}

    
/************************************************************************************
*
*Funcion que consulta las cabecera activa
*
*************************************************************************************/    
    function  ConsultarCabecera($empresa,$centro_utilidad,$bodega)
    { 
        $sql=" select  *
               from inv_conteo_tomas_fisicas
               where 
               empresa_id = '".$empresa."' and 
               centro_utilidad = '$centro_utilidad' and
               bodega = '$bodega' 
              ; "; 
             
  
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
        
    }
/************************************************************************************
*
*Funcion que consulta las cabecera activa
*
*************************************************************************************/    
    function  ConsultarCabeceraActiva($empresa,$centro_utilidad,$bodega)
    { 
        $sql=" select  *
               from inv_conteo_tomas_fisicas
               where 
               empresa_id = '".$empresa."' and 
               centro_utilidad = '$centro_utilidad' and
               bodega = '$bodega' and
               estado='1'; ";              

      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
        
    }
/************************************************************************************
*
*Funcion que consulta las bodegas de la empresa
*
*************************************************************************************/    
    function  GetBodegas($empresa,$centro)
    { 
        $and='';
        if($centro!=''){
            $and=" and centro_utilidad = '$centro' ";
        }
        $sql=" select  *
               from bodegas
               where empresa_id = '".$empresa."' $and
               order by descripcion; "; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
        
    }
/************************************************************************************
*
*Funcion que consulta diferencias entre los lotes
*
*************************************************************************************/    
    function  GetDiferenciasLotes($empresa,$bodegas_id)
    { 
        
        $sql="SELECT a.empresa_id, a.bodega,a.codigo_producto,a.exis,a.lote
            FROM (
            select b.*, 'lote' as lote
            from (
            (select empresa_id,bodega,codigo_producto, sum(existencia_actual) as exis--,existencia_actual
            from existencias_bodegas_lote_fv 
            where existencia_actual >0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
            group by 1,2,3
            order by codigo_producto
            )
            EXCEPT
            (
            select empresa_id,bodega,codigo_producto,existencia as exis
            from ajuste_vencidos 
            where existencia > 0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
            order by codigo_producto
            ) 
             ) as b
            where empresa_id='$empresa' and bodega='$bodegas_id'
           union
            select c.*, 'titular' as lote
            from (
            (select empresa_id,bodega,codigo_producto,existencia as exis
            from ajuste_vencidos
            where existencia >0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
            order by codigo_producto
            )
            EXCEPT
            (
            select empresa_id,bodega,codigo_producto, sum(existencia_actual) as exis
            from existencias_bodegas_lote_fv 
            where existencia_actual > 0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
            group by 1,2,3
            order by codigo_producto
            ) ) as c 
            ) as a"; 
            
    //echo "<pre>".$sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $vars = $resultado->GetRowAssoc($ToUpper = false);
        $documentos[$vars['codigo_producto']][$vars['lote']] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
        
    }
    
    function Consultar_Cantidad_Vencida($empresa,$bodegas_id,$codigo_producto,$cantidad_vencidos){ 
     $sql="
          select 
          case when (sum(existencia_actual)>$cantidad_vencidos) then $cantidad_vencidos 
              else  
              sum(existencia_actual) end as existencia
           from existencias_bodegas_lote_fv         
           where existencia_actual >0  and  empresa_id='$empresa' and bodega='$bodegas_id' and codigo_producto='$codigo_producto'
          ";
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
       
        $documentos = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos['existencia'];
        
    }
    
    
    
//    function  GetDiferenciasLotes2($empresa,$bodegas_id)
//    { 
//        
//        $sql="SELECT a.empresa_id, a.bodega,a.codigo_producto,a.exis,a.lote
//            FROM (
//            select b.*, 'lote' as lote
//            from (
//            (select empresa_id,bodega,codigo_producto, sum(existencia_actual) as exis--,existencia_actual
//            from existencias_bodegas_lote_fv 
//            where existencia_actual >0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
//            group by 1,2,3
//            order by codigo_producto
//            )
//            EXCEPT
//            (
//            select empresa_id,bodega,codigo_producto,existencia as exis
//            from existencias_bodegas 
//            where existencia > 0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
//            order by codigo_producto
//            ) ) as b
//            where empresa_id='$empresa' and bodega='$bodegas_id'
//            union
//            select c.*, 'titular' as lote
//            from (
//            (select empresa_id,bodega,codigo_producto,existencia as exis
//            from existencias_bodegas
//            where existencia >0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
//            order by codigo_producto
//            )
//            EXCEPT
//            (
//            select empresa_id,bodega,codigo_producto, sum(existencia_actual) as exis
//            from existencias_bodegas_lote_fv 
//            where existencia_actual > 0  and  empresa_id='$empresa' and bodega='$bodegas_id' 
//            group by 1,2,3
//            order by codigo_producto
//            ) ) as c 
//            ) as a"; 
//            
//    
//      if(!$resultado = $this->ConexionBaseDatos($sql))
//        return false;
//        
//      $documentos=Array();
//      while(!$resultado->EOF)
//      {
//        
//        $vars = $resultado->GetRowAssoc($ToUpper = false);
//        $documentos[$vars['codigo_producto']][$vars['lote']] = $resultado->GetRowAssoc($ToUpper = false);
//        $resultado->MoveNext();
//      }
//      
//      $resultado->Close();
//      return $documentos;
//        
//    }
/************************************************************************************
*
*Funcion que consulta diferencias entre conteo 2
*
*************************************************************************************/    
    function  GetDiferenciasInventario($empresa,$bodegas_id,$cabecera_id)
    { 
        
        $sql="select  empresa_id,bodega,codigo_producto,(case when exislote is null then 0 else exislote end ) as exislote, existitular, 
                ((case when exislote is null then 0 else exislote end ) - existitular) as diferente
                from 
                (
                select  a.empresa_id,a.bodega,a.codigo_producto, sum(cantidad) as exislote,existencia as existitular 
                from existencias_bodegas as a
                left join inv_conteo_tomas_fisicas_detalle as b on (a.empresa_id=b.empresa_id and a.bodega=b.bodega and a.centro_utilidad=b.centro_utilidad and a.codigo_producto=b.codigo_producto and conteo='2' and id_conteo_toma_fisica='$cabecera_id')
                where   a.empresa_id='$empresa' and a.bodega='$bodegas_id' 
                GROUP BY 1,2,3,5

                union

                select b.empresa_id,b.bodega,b.codigo_producto,
                (select sum(cantidad) from inv_conteo_tomas_fisicas_detalle where empresa_id='$empresa' and bodega='$bodegas_id' and codigo_producto=b.codigo_producto and conteo='2' and id_conteo_toma_fisica='$cabecera_id') as exislote,0 as existitular
                from (
                (select empresa_id,bodega,codigo_producto
                from inv_conteo_tomas_fisicas_detalle  
                where empresa_id='$empresa' and bodega='$bodegas_id' and conteo='2' and id_conteo_toma_fisica='$cabecera_id'
                )
                EXCEPT
                (
                select empresa_id,bodega,codigo_producto
                from existencias_bodegas 
                where empresa_id='$empresa' and bodega='$bodegas_id' 
                ) ) as b
                where empresa_id='$empresa' and bodega='$bodegas_id') as v 
                where ((case when exislote is null then 0 else exislote end ) - existitular) <> 0
                order by diferente"; 

      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $vars = $resultado->GetRowAssoc($ToUpper = false);
        $documentos[]= $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
        
    }
    
function ActualizarKardex($empresa_id,$centro_utilidad,$bodega,$codigo_producto,$existencia)
  {
      $sql="UPDATE existencias_bodegas
                    existencia='$existencia'
                WHERE
                    empresa_id= '".$empresa_id."' AND 
                    centro_utilidad='".$centro_utilidad."' AND
                    bodega= '".$bodega."' AND
                    codigo_producto= '".$codigo_producto."';";      
   
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
     $this->mensajeDeError="EXITO EN LA OPERACION";
	 return true;
  } 
  
function ActualizarCabecera($empresa_id,$centro_utilidad,$bodega,$cabecera_id)
  {
      $sql="UPDATE inv_conteo_tomas_fisicas
                    estado='0'
                WHERE
                    empresa_id= '".$empresa_id."' AND 
                    centro_utilidad='".$centro_utilidad."' AND
                    bodega= '".$bodega."' AND
                    id_conteo_toma_fisica= '".$cabecera_id."';";      
   
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
     $this->mensajeDeError="EXITO EN LA OPERACION";
	 return true;
  } 

/************************************************************************************
*
*Funcion que consulta nombre de la empresa
*
*************************************************************************************/    
    function  ColocarEmpresa($empresa)
    { 
       $sql=" select razon_social,empresa_id 
       from empresas
       where empresa_id = '".strtoupper($empresa)."'"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }


/******************************************
* buscador de productos
*******************************************/

function SacarPro($toma_fisica,$aumento,$aumento1,$offset)
{
 /* $sql1="SELECT  count(*) 
             FROM    inv_toma_fisica_d as b,
                          inventarios_productos as c,
                          unidades as d
             WHERE  b.toma_fisica_id = ".$toma_fisica."
             ".$aumento."
             AND c.codigo_producto = b.codigo_producto
             AND d.unidad_id = c.unidad_id";*/
	$sql1="SELECT  count(*)  as A
            FROM                   
											inv_toma_fisica_d as b,
											inventarios_productos as c
											JOIN inv_subclases_inventarios f ON (c.grupo_id=f.grupo_id)
											AND  (c.clase_id=f.clase_id)
											AND  (c.subclase_id 	=f.subclase_id),
											existencias_bodegas as e
				WHERE                 b.toma_fisica_id =  ".$toma_fisica."
				AND                      c.codigo_producto = b.codigo_producto
				AND                      b.codigo_producto=e.codigo_producto
				AND                      b.empresa_id=e.empresa_id
				AND                      b.centro_utilidad=e.centro_utilidad
				".$aumento."
				AND                      b.bodega=e.bodega ";
		
            /*$this->ProcesarSqlConteo($sql1,10,$offset);      */

     $sql= " SELECT  DISTINCT  c.codigo_producto,b.etiqueta_x_producto,
                                             fc_descripcion_producto(c.codigo_producto)as descripcion,
                                             e.existencia as existencia
                                              ".$aumento1."
				FROM                   
											inv_toma_fisica_d as b,
											inventarios_productos as c
											JOIN inv_subclases_inventarios f ON (c.grupo_id=f.grupo_id)
											AND  (c.clase_id=f.clase_id)
											AND  (c.subclase_id 	=f.subclase_id),
											existencias_bodegas as e
				WHERE                 b.toma_fisica_id =  ".$toma_fisica."
				AND                      c.codigo_producto = b.codigo_producto
				AND                      b.codigo_producto=e.codigo_producto
				AND                      b.empresa_id=e.empresa_id
				AND                      b.centro_utilidad=e.centro_utilidad
				".$aumento."
				AND                      b.bodega=e.bodega
                 order by b.etiqueta_x_producto ";
				 
				 $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
              
				$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
				 
                /*limit ".$this->limit." OFFSET ".$this->offset."";*/
                
  /*$sql=" SELECT  b.etiqueta_x_producto,
                         c.codigo_producto,
                         c.descripcion,
                         c.unidad_id,
                         c.codigo_barras,
                         b.fecha_vencimiento,
                         b.lote,    
                         d.descripcion as descripcion_unidad,
                         e.existencia_actual as existencia,
                         M.descripcion as farmacologico
                         ".$aumento1."
             FROM    inv_toma_fisica_d as b,
                          inventarios_productos as c LEFT JOIN (SELECT 	a.codigo_medicamento,
									                                                                              a.	concentracion_forma_farmacologica,
									                                                                              b.descripcion
                                                                                  FROM	   medicamentos a,
                                                                                               inv_med_cod_forma_farmacologica b
							                                                                    WHERE	a.cod_forma_farmacologica = b.cod_forma_farmacologica) as M
							             ON (M.codigo_medicamento = c.codigo_producto),
                          unidades as d,
                          existencias_bodegas_lote_fv as e,
                          inv_subclases_inventarios f
             WHERE   b.toma_fisica_id = ".$toma_fisica."
             AND        c.codigo_producto = b.codigo_producto
             AND        c.grupo_id=f.grupo_id
             AND        c.clase_id=f.clase_id 	
             AND        c.subclase_id 	=f.subclase_id 	
             ".$aumento."
             AND        d.unidad_id = c.unidad_id
             AND        b.codigo_producto=e.codigo_producto
             AND        b.fecha_vencimiento=e.fecha_vencimiento
             AND        b.lote=e.lote
             AND        b.empresa_id=e.empresa_id
             AND        b.centro_utilidad=e.centro_utilidad
             AND        b.bodega=e.bodega
    
              order by b.etiqueta_x_producto
              limit ".$this->limit." OFFSET ".$this->offset."";*/
    
   
     //$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
              
    //$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
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

function SeleccionarTotalConteo($toma_fisica_id,$conteo,$etiqueta_x_product)
{
  $sql="SELECT count(a.*)
        FROM  inv_toma_fisica_conteos as a,
                   inv_toma_fisica_d as b 
        WHERE a.toma_fisica_id=".$toma_fisica_id."
        AND a.num_conteo=".$conteo."
        AND a.toma_fisica_id=b.toma_fisica_id
        AND a.etiqueta=b.etiqueta
        AND b.etiqueta_x_producto<>".$etiqueta_x_product." ";
    
    //return $sql;
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      list($documentos)=$resultado->FetchRow();

      $resultado->Close();
      return $documentos;
}
function Etiquetaxproducto($toma_fisica_id,$codigo_producto)
{
  $sql="SELECT b.etiqueta_x_producto
            FROM     inv_toma_fisica_d as b 
            WHERE   b.toma_fisica_id=".$toma_fisica_id."
            AND       b.codigo_producto='".$codigo_producto."'
        ";
    
    //return $sql;
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      list($documentos)=$resultado->FetchRow();

      $resultado->Close();
      return $documentos;
}


/******************************************
* buscador de productos
*******************************************/

function ListarProductos($toma_fisica,$codigo_producto,$etiqueta,$conteo,$etiqueta_x_product)
{
 
 /*$siConteo=$this->SeleccionarTotalConteo($toma_fisica,$conteo,$etiqueta_x_product);
print_r($siConteo);	*/
	// if(($conteo=='2' OR $conteo=='3' OR $conteo=='1') AND $siConteo=='0' )
 /*if(($conteo=='2' OR $conteo=='3') AND $siConteo=='0' )
 {
   $conteo1=$conteo-1;
   $filtro= " , inv_toma_fisica_conteos as con  ";
   $filtro1= " AND       con.toma_fisica_id=b.toma_fisica_id
                  AND       con.num_conteo=".$conteo1."
                  AND       con.etiqueta =b.etiqueta  ";
 }
 else
 {
    $filtro= " ";
     $filtro1= " ";
 }
 if($siConteo>0 AND ($conteo=='2' OR $conteo=='3' OR $conteo=='1'))
 {
   $filtro2= "AND v.num_conteo  IS NULL ";
 }
 else
 {
    $filtro2= " ";
 }*/
     $sql=" SELECT  b.etiqueta_x_producto,
                            b.etiqueta,
                            c.codigo_producto,
                            c.descripcion,
                            c.unidad_id,
                            c.codigo_barras,
                            b.fecha_vencimiento,
                            b.lote,
                            b.empresa_id,
                            b.centro_utilidad,                            
                           d.descripcion as descripcion_unidad,
                           e.existencia_actual as existencia,
                           f.descripcion as ubicacion,
                           CASE WHEN v.num_conteo IS NULL 
						   THEN '0' 
						   ELSE '1' 
						   END as cuadrado ,
						   CASE 
						   WHEN v.etiqueta IS NOT NULL 
						   THEN '#00FF40@<label class=\"normal_10AN\">EL PRODUCTO YA EST??? CUADRADO EN EL CONTEO #'|| v.num_conteo|| ' </label>@disabled'
						   WHEN (h.etiqueta IS NULL OR h.usuario_valido IS NULL) AND '".$conteo."'::integer >'1'::integer
						   THEN '#00FF40@<label class=\"normal_10AN\">EL PRODUCTO DEBE REGISTRARSE/VALIDARSE PRIMERO EN EL CONTEO #:".($conteo-1)."</label>@disabled'
						   WHEN g.etiqueta IS NOT NULL AND g.usuario_valido IS NULL 
						   THEN '#00FF40@<label class=\"normal_10AN\">CONTADO #CONTEO :".$conteo."</label>@disabled'
						   WHEN g.etiqueta IS NOT NULL AND g.usuario_valido IS NOT NULL 
						   THEN '#FF1000@<label class=\"normal_10AN\">CONTADO Y VALIDADO. #CONTEO :".$conteo."</label>@disabled'
						   WHEN g.usuario_valido IS NULL AND g.usuario_valido IS NULL 
						   THEN '@<label class=\"normal_10AN\">NO CONTADO. #CONTEO :".$conteo."</label>@'
						   END as producto_conteo,
						   round(g.conteo) as conteo
						   
             FROM    inv_toma_fisica_d as b 
						JOIN existencias_bodegas_lote_fv as e ON (b.empresa_id=e.empresa_id)
						AND       (b.centro_utilidad=e.centro_utilidad)
						AND       (b.bodega=e.bodega)
						AND       (b.codigo_producto=e.codigo_producto)
						AND       (b.fecha_vencimiento=e.fecha_vencimiento)
						AND       (b.lote=e.lote)
                          LEFT JOIN bodegas_ubicaciones as f ON (e.ubicacion_id=f.ubicacion_id)
                          AND       (e.empresa_id=f.empresa_id)
                          AND       (e.centro_utilidad=f.centro_utilidad)
                          AND       (e.bodega=f.bodega)
                          LEFT JOIN inv_toma_fisica_update as v ON(v.toma_fisica_id = b.toma_fisica_id)
						  AND (v.etiqueta  = b.etiqueta)
						  LEFT JOIN inv_toma_fisica_conteos as g ON(b.etiqueta = g.etiqueta)
						  AND (b.toma_fisica_id = g.toma_fisica_id)
						  AND (g.num_conteo = ".$conteo.")
						  LEFT JOIN inv_toma_fisica_conteos as h ON(b.etiqueta = h.etiqueta)
						  AND (b.toma_fisica_id = h.toma_fisica_id)
						  AND (h.num_conteo = ".(($conteo>1)?($conteo-1): $conteo)."),
						  inventarios_productos as c,
                          unidades as d
              WHERE   b.toma_fisica_id = ".$toma_fisica."
              AND 		b.etiqueta_x_producto = '".$etiqueta_x_product."'
      		  AND        b.codigo_producto = c.codigo_producto
			  AND       c.unidad_id = d.unidad_id
              $filtro1
              $filtro2
              order by b.etiqueta_x_producto";
    
	/*print_r($sql);*/
     //$this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",10,$offset); 
              
    //$sql .= " LIMIT ".$this->limit." OFFSET ".$this->offset."";
               //RETURN $sql;
        if(!$rst = $this->ConexionBaseDatos($sql))
       return false;
      
       $datos = array();
       while(!$rst->EOF)
       {
         $datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
         //$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
         $rst->MoveNext();
       } 
       $rst->Close();
      
       return $datos;
   /* if(!$resultado = $this->ConexionBaseDatos($sql))
      return $this->frmError['MensajeError'];
       // $rst->GetRowAssoc($ToUpper = false);             
      $cuentas=Array();
      while(!$resultado->EOF)
      {
         //$documentos[$resultado->fields[1]] = $resultado->GetRowAssoc($ToUpper = false);
        $cuentas[$resultado->fields[0]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      
      return $cuentas; */

}


function BuscarTodaTomas($toma_fisica)
{
     $sql=" SELECT MAX(b.etiqueta)
             FROM    inv_toma_fisica_d as b
             WHERE   b.toma_fisica_id = ".trim($toma_fisica)."; ";
   
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

function BuscarUbicacion($empresa_id,$centro_utililidad,$bodega)
{
     /*$sql=" SELECT *
               FROM    bodegas_ubicaciones_n1
               ";*/
	$sql = "
				SELECT 
				a.ubicacion_id, 	
				a.empresa_id, 	
				a.centro_utilidad, 	
				a.bodega, 	
				a.n1, 	
				a.n2, 	
				a.n3, 	
				a.n4,	
				a.n1||' - '||a.n2||' - '||a.n3||' - '||a.n4 as descripcion
				FROM
				bodegas_ubicaciones as a
				WHERE TRUE
				AND a.empresa_id = '".trim($empresa_id)."'
				AND a.centro_utilidad = '".trim($centro_utililidad)."'
				AND a.bodega = '".trim($bodega)."'
				ORDER BY a.n1, 	
				a.n2, 	
				a.n3, 	
				a.n4;	";

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

function BuscarUbicacionN2($ubicacion_n1)
{
     $sql=" SELECT   b.*
                FROM    bodegas_ubicaciones_n1 as a,
                             bodegas_ubicaciones_n2 as b
               WHERE   a.n1='".$ubicacion_n1."'
               AND       a.n1=b.n1
               AND       a.empresa_id=b.empresa_id
               AND       a.centro_utilidad 	=b.centro_utilidad 	
               AND       a.bodega=b.bodega
               AND       b.n2 <> ''
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

function BuscarUbicacionN3($ubicacion_n1,$ubicacion_n2)
{
     $sql=" SELECT c.*
               FROM   bodegas_ubicaciones_n1 as a,
                           bodegas_ubicaciones_n2 as b,
                           bodegas_ubicaciones_n3 as c
              WHERE  a.n1='".$ubicacion_n1."'
              AND       b.n2='".$ubicacion_n2."'
              AND       a.empresa_id=b.empresa_id
              AND       a.centro_utilidad 	=b.centro_utilidad 	
              AND       a.bodega=b.bodega
              AND       a.n1=b.n1
              AND       c.n1=b.n1
              AND       c.n2=b.n2
              AND       c.empresa_id=b.empresa_id
              AND       c.centro_utilidad 	=b.centro_utilidad 	
              AND       c.bodega=b.bodega
              AND       c.n3 <> ''
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

function BuscarUbicacionN4($ubicacion_n1,$ubicacion_n2,$ubicacion_n3)
{
     $sql=" SELECT d.*
               FROM   bodegas_ubicaciones_n1 as a,
                           bodegas_ubicaciones_n2 as b,
                           bodegas_ubicaciones_n3 as c,
                           bodegas_ubicaciones_n4 as d
              WHERE  a.n1='".$ubicacion_n1."'
              AND       b.n2='".$ubicacion_n2."'
              AND       c.n3='".$ubicacion_n3."'
              AND       a.empresa_id=b.empresa_id
              AND       a.centro_utilidad 	=b.centro_utilidad 	
              AND       a.bodega=b.bodega
              AND       c.n1=b.n1
              AND       c.n2=b.n2
              AND       c.empresa_id=b.empresa_id
              AND       c.centro_utilidad 	=b.centro_utilidad 	
              AND       c.bodega=b.bodega
              AND       d.n1=c.n1
              AND       d.n2=c.n2
              AND       d.n3=c.n3
              AND       d.empresa_id=c.empresa_id
              AND       d.centro_utilidad 	=c.centro_utilidad 	
              AND       d.bodega=c.bodega
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

function BuscarEmpresaAndCentroUtilidad($toma_fisica)
{
     $sql=" SELECT DISTINCT
							empresa_id,
							centro_utilidad,
							bodega
               FROM    inv_toma_fisica_d
               WHERE  toma_fisica_id = ".$toma_fisica."         ";
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
      return $documentos;
}

function BuscarParametrizacion_IngresoLoteFV($empresa_id,$centro_utilidad)
{
     $sql=" SELECT conteo_2 ,conteo_3	
               FROM    inv_toma_fisica_paramingreso
               WHERE  empresa_id = '".$empresa_id."'
               AND      centro_utilidad = '".$centro_utilidad."'   ";
   
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
      return $documentos;
}


function BuscarEtiquetas($toma_fisica,$etiqueta_x_producto)
{
     $sql=" SELECT *
             FROM    inv_toma_fisica_d as b
             WHERE   b.toma_fisica_id = ".$toma_fisica."
             AND        b.etiqueta_x_producto = '".$etiqueta_x_producto."'   ";
   
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

//BuscarFechaLote($codigo_producto,$forma['fecha_venci'],$forma['lote']);   
function BuscarFechaLote($codigo_producto,$fecha_vencimiento,$lote,$empresa_id,$centro_utilidad,$bodega)
{
     $sql=" SELECT    *
               FROM      existencias_bodegas_lote_fv 
               WHERE    codigo_producto = '".trim($codigo_producto)."'
               AND         fecha_vencimiento  = '".trim($fecha_vencimiento)."'
               AND         lote  = '".trim($lote)."'
               AND         empresa_id  = '".trim($empresa_id)."'
               AND         centro_utilidad  = '".trim($centro_utilidad)."'
               AND         bodega  = '".trim($bodega)."' ";
   
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


//BuscarFechaLote($codigo_producto,$forma['fecha_venci'],$forma['lote']);   
function BuscarFechaLote_TomaFisica($toma_fisica_id,$codigo_producto,$fecha_vencimiento,$lote)
{
     $sql=" SELECT    	toma_fisica_id, 	
								etiqueta, 	
								empresa_id, 	
								centro_utilidad, 	
								codigo_producto, 	
								bodega, 	
								fecha_vencimiento, 	
								lote, 	
								etiqueta_x_producto
               FROM      inv_toma_fisica_d
               WHERE    codigo_producto = '".trim($codigo_producto)."'
               AND         fecha_vencimiento  = '".trim($fecha_vencimiento)."'
               AND         lote  = '".trim($lote)."'
               AND         toma_fisica_id  = ".trim($toma_fisica_id)." ";
 
              // AND         toma_fisica_id  = '".trim($toma_fisica_id)."' ";
 
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
 *
 */
function BuscarUbicacionTodos($ubicacion_1,$ubicacion_2,$ubicacion_3,$ubicacion_4,$empresa_id,$centro_utilidad,$bodega)
{
     $sql=" SELECT    ubicacion_id
               FROM      bodegas_ubicaciones 
               WHERE    empresa_id  = '".$empresa_id."'
               AND         centro_utilidad  = '".$centro_utilidad."'
               AND         bodega  = '".$bodega."'
               AND         n1  = '".$ubicacion_1."'
               AND         n2  = '".$ubicacion_2."'
               AND         n3  = '".$ubicacion_3."'
               AND         n4  = '".$ubicacion_4."'
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
  function InsertarExistenciasFV($empresa_id,$centro_utilidad,$codigo_producto,$bodega,$fecha_vencimiento,$lote,$ubicacion)
  {
      $sql="  INSERT INTO existencias_bodegas_lote_fv
                                   ( empresa_id,
                                     centro_utilidad,
                                     codigo_producto,
                                     bodega,
                                     fecha_vencimiento,
                                     lote,
                                     existencia_inicial,
                                     existencia_actual,
                                     estado,
                                     ubicacion_id )
                  VALUES
                               (   '".trim($empresa_id)."',
                                   '".trim($centro_utilidad)."',
                                    '".trim($codigo_producto)."',
                                    '".trim($bodega)."',
                                    '".trim($fecha_vencimiento)."',
                                    '".trim($lote)."',
                                    0,
                                    0,
                                    1,
                                     ".(($ubicacion=="")? "NULL":trim($ubicacion))."); ";
      
   
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
     $this->mensajeDeError="EXITO EN LA OPERACION";
	 return true;
  } 

function SeleccionarNumConteo($toma_fisica_id,$conteo)
{
  $sql="SELECT a.numero_lista
        FROM  inv_toma_fisica_conteos as a
        WHERE a.toma_fisica_id=".$toma_fisica_id."
        AND a.num_conteo=".$conteo."
        ORDER BY numero_lista";
    
    //return $sql;
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      list($documentos)=$resultado->FetchRow();

      $resultado->Close();
      return $documentos;
}

  function InsertarTomaFisica($toma_fisica_id,$etiqueta,$empresa_id,$centro_utilidad,$codigo_producto,$bodega,$fecha_vencimiento,$lote,$etiqueta_x_pro,$conteo)
  {
          
	  
		$sql="  INSERT INTO inv_toma_fisica_d
		(
		toma_fisica_id,
		etiqueta,
		empresa_id,
		centro_utilidad,
		codigo_producto,
		bodega,
		fecha_vencimiento,
		lote,
		etiqueta_x_producto 	
		)
		VALUES
		(
		".trim($toma_fisica_id).",
		".trim($etiqueta).",
		'".trim($empresa_id)."',
		'".trim($centro_utilidad)."',
		'".trim($codigo_producto)."',
		'".trim($bodega)."',
		'".trim($fecha_vencimiento)."',
		'".trim($lote)."',
		".trim($etiqueta_x_pro)."
		); ";
		   
          $sql .="  INSERT INTO  inv_toma_fisica_detalle_inicial
                                             (toma_fisica_id,
                                              empresa_id,
                                              centro_utilidad,
                                              bodega,
                                              codigo_producto,
                                              existencia,
                                              costo,
                                             fecha_vencimiento,
                                             lote)
											 SELECT
											 ".trim($toma_fisica_id)." as toma_fisica_id,
											 '".trim($empresa_id)."' AS empresa_id,
                                             '".trim($centro_utilidad)."' AS centro_utilidad,
                                             '".trim($bodega)."' as bodega,
											 '".trim($codigo_producto)."' as codigo_producto,
											 0 AS existencia,
											 a.costo,
                                             '".trim($fecha_vencimiento)."' as fecha_vencimiento,
                                             '".trim($lote)."' as lote
											FROM
											inventarios as a
											WHERE TRUE 
											AND a.codigo_producto= '".trim($codigo_producto)."'
											AND a.empresa_id =  '".trim($empresa_id)."' ;";                
             /*print_r($sql);*/
            if(!$rst = $this->ConexionBaseDatos($sql))
			{  
			return false;
			}
 
			 $this->mensajeDeError="EXITO EN LA OPERACION";
			 return true;
           
          /*  $sql1.="  INSERT INTO inv_toma_fisica_conteos
            (
                toma_fisica_id,
                etiqueta,
                num_conteo,
                conteo,
                usuario_registro,
                fecha_registro,
                numero_lista,
                usuario_valido,
                fecha_validacion,
                indice_orden                
            )
            VALUES
            (
                ".$toma_fisica_id.",
                ".$etiqueta.",
                ".$conteo.",
                0,
                ".UserGetUID().",
                now(),
                ".$num_lista.",
                NULL,
                NULL,
                default
            ); ";
       

       if(!$rst = $this->ConexionBaseDatos($sql1)) 
       {  $cad="ERROR EN LA INSERCION";
         return $cad;
       }
      else
       {      
         $cad="	EL PRODUCTO HACE PARTE DE LA TOMA FISICA";
         $rst->Close();
         return $cad;
       }*/
 
      /* if($conteo=='2' OR $conteo=='3' )
       {
          $conteo1=$conteo-1;
          $num_lista=$this->SeleccionarNumConteo($toma_fisica_id,$conteo1);
           
          $query="  INSERT INTO  inv_toma_fisica_detalle_inicial
                                             (toma_fisica_id,
                                              empresa_id,
                                              centro_utilidad,
                                              bodega,
                                              codigo_producto,
                                              existencia,
                                              costo,
                                             fecha_vencimiento,
                                             lote)
                                             SELECT
                                                $toma_fisica_id as toma_fisica_id,
                                                a.empresa_id,
                                                a.centro_utilidad,
                                                a.bodega,
                                                a.codigo_producto,
                                                a.existencia_actual AS existencia,
                                                b.costo,
                                                a.fecha_vencimiento,
                                                a.lote
                                             FROM   existencias_bodegas_lote_fv as a,
                                                         inventarios as b
                                             WHERE  a.empresa_id = '".$empresa_id."'
                                             AND       a.centro_utilidad = '".$centro_utilidad."'
                                             AND       a.bodega = '".$bodega."'
                                             AND       a.codigo_producto = '".$codigo_producto."'
                                             AND       a.fecha_vencimiento 	 = '".$fecha_vencimiento."'
                                             AND       a.lote 	 = '".$lote."'
                                             AND       b.empresa_id = a.empresa_id
                                             AND      b.codigo_producto = a.codigo_producto
                                              ;";
                 
                           
            if(!$rst = $this->ConexionBaseDatos($query))
              return false;
           
            $sql1.="  INSERT INTO inv_toma_fisica_conteos
            (
                toma_fisica_id,
                etiqueta,
                num_conteo,
                conteo,
                usuario_registro,
                fecha_registro,
                numero_lista,
                usuario_valido,
                fecha_validacion,
                indice_orden                
            )
            VALUES
            (
                ".$toma_fisica_id.",
                ".$etiqueta.",
                ".$conteo1.",
                0,
                ".UserGetUID().",
                now(),
                ".$num_lista.",
                NULL,
                NULL,
                default
            ); ";
       
      //return $sql;      
       if(!$rst = $this->ConexionBaseDatos($sql1)) 
       {  $cad="no se hizo la insercion";
         return $cad;
         
       }
      else
       {      
         $cad="Producto Cuadrado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    }       */
   
   } 
/******************************************
* eliminar captura
******************************************/
function EliminarCaptura($toma_fisica_id,$etiqueta,$num_conteo)
{
 
$sql="DELETE FROM inv_toma_fisica_conteos
      WHERE
       toma_fisica_id=".$toma_fisica_id."
       AND etiqueta=".$etiqueta."
       AND num_conteo=".$num_conteo."
	   AND usuario_valido IS NULL";
   
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Captura Eliminada Correctamente";
           return $cad;
         }   
}
/*********************************************
*actualizacion para la validacion de listas
**********************************************/
function ValidarActualizarConteo($datos)
{
  
   $sql="";
 for($i=0;$i<count($datos);$i++)
 {
  
   list($toma_fisica_id,$etiqueta,$num_conteo,$cantidad) = explode("@", $datos[$i]);
   $sql.=" UPDATE inv_toma_fisica_conteos
              SET       conteo=".$cantidad.",
                           usuario_valido=".UserGetUID().",
                           fecha_validacion=now()
             WHERE    toma_fisica_id=".$toma_fisica_id."
             AND        etiqueta=".$etiqueta."
             AND        num_conteo=".$num_conteo.";";
          
 }
   //return $sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida";
          return $this->frmError['MensajeError'];//$cad;
        } 
        else 
         {
           $cad="Productos Validados Exitosamente";
           return $cad;
         }   
 

}

/*
 *
 */
function ActualizarConteo2($toma_fisica_id,$etiqueta,$cantidad,$num_conteo)
{
   $sql.=" UPDATE inv_toma_fisica_conteos
              SET       conteo=".$cantidad."
             WHERE    toma_fisica_id=".$toma_fisica_id."
             AND        etiqueta=".$etiqueta."
             AND        num_conteo=".$num_conteo.";";

      if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida";
          return $this->frmError['MensajeError'];//$cad;
        } 
        else 
         {
           $cad="Productos Modificados Exitosamente";
            return $cad;  
         }
}



/***************************************************************************
*sacar los productos de la lista escogida
****************************************************************************/
function SacarProductosLista($toma,$lista)
{
 $sql="SELECT
          a.*,
          c.codigo_producto,
          fc_descripcion_producto(c.codigo_producto) as descripcion,
          c.unidad_id,
          d.descripcion as descripcion_unidad,
          b.fecha_vencimiento,
          b.lote,
		  b.etiqueta_x_producto
      FROM
      (
        SELECT *
        FROM
        inv_toma_fisica_conteos
        WHERE
        toma_fisica_id = ".$toma."
        AND numero_lista = ".$lista."
        AND usuario_valido IS NULL
      ) as a,
      inv_toma_fisica_d as b,
      inventarios_productos as c,
      unidades as d
      
      WHERE
      b.toma_fisica_id = a.toma_fisica_id
      AND b.etiqueta = a.etiqueta
      AND c.codigo_producto = b.codigo_producto
      AND d.unidad_id = c.unidad_id
      ORDER BY a.indice_orden";

      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
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
*SACAr listas de registros de tomas fisicas
***********************************************************************************/
function SacarListas($toma_id)
{
   $sql="
        SELECT
        a.numero_lista,
        b.nombre,
        a.fecha,
        a.cantidad_reg
      FROM 
      (
        SELECT
          numero_lista,
          usuario_registro,
          MAX(fecha_registro) AS fecha,
          COUNT(*) as cantidad_reg
        
        FROM
          inv_toma_fisica_conteos
        
        WHERE
          toma_fisica_id=".$toma_id."
          AND usuario_valido IS NULL
          GROUP BY numero_lista, usuario_registro
      ) AS a,
      system_usuarios AS b
      WHERE a.usuario_registro=b.usuario_id
      ORDER BY a.numero_lista, b.nombre";

  
  if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;   
}


/***********************************************************************************
* funcion que sirve sacardatos producto
************************************************************************************/
function SacarDatosProducto($codigo,$toma)
{
  $sql="SELECT  a.codigo_producto,
          fc_descripcion_producto(a.codigo_producto) as descripcion,
          b.unidad_id,
          c.descripcion as descripcion_unidad,
          a.fecha_vencimiento,
          a.lote          
  FROM  
          inv_toma_fisica_d as a,
          inventarios_productos as b,
          unidades as c 
  WHERE
        a.etiqueta =".$codigo."
        AND a.toma_fisica_id=".$toma."
        AND a.codigo_producto = b.codigo_producto
        AND b.unidad_id = c.unidad_id";
        
  // return $sql;
  if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;   


}

/***********************************************************************************
* funcion que sirve para listar
************************************************************************************/
function SeleccionarConteo($toma_fisica_id,$numero_lista)
{
  $sql="SELECT a.*,b.etiqueta_x_producto
        FROM  inv_toma_fisica_conteos as a
		LEFT JOIN inv_toma_fisica_d as b ON (a.etiqueta = b.etiqueta)
		AND (a.toma_fisica_id = b.toma_fisica_id)
        WHERE TRUE
		AND a.toma_fisica_id=".$toma_fisica_id."
		AND a.numero_lista=".$numero_lista."
        ORDER BY numero_lista ";
    
    //return $sql;
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;   




}
/***********************************************************************
*INSERTAR EL CONTEO DE LA TOMA FISICA
***********************************************************************/
  function InsertarConteo($toma_fisica_id,$etiqueta,$num_conteo,$conteo,$usuario_registro,$numero_lista)
  {
    $sql="  INSERT INTO inv_toma_fisica_conteos
            (
                toma_fisica_id,
                etiqueta,
                num_conteo,
                conteo,
                usuario_registro,
                fecha_registro,
                numero_lista
            )
            VALUES
            (
                $toma_fisica_id,
                $etiqueta,
                $num_conteo,
                $conteo,
                ".UserGetUID().",
                now(),
                $numero_lista
            ); ";
       
      if(!$rst = $this->ConexionBaseDatos($sql))
      {  
        return false;
      }
      return true;
  } 


/***********************************************************************
*usuario validacion toma fisica
***********************************************************************/
function BuscarUsuarioValidacion($usuario,$empresa_id)
{
  $sql="SELECT x.*,y.*
        FROM
        (
          SELECT a.*,c.descripcion as nom_bodega
          
          FROM
          inv_toma_fisica as a,
          inv_toma_fisica_usuarios_validacion as b,
          bodegas as c
          
          WHERE
          a.sw_estado = '1'
          AND b.toma_fisica_id = a.toma_fisica_id
          AND b.usuario_id = ".$usuario."
          AND c.empresa_id = a.empresa_id
          AND c.empresa_id = '".$empresa_id."'
          AND c.centro_utilidad = a.centro_utilidad
          AND c.bodega = a.bodega
          AND a.fecha_inicio IS NOT NULL
        ) AS x,
        (
          SELECT COUNT(*) AS cantidad_reg,toma_fisica_id
          FROM inv_toma_fisica_d
          GROUP BY toma_fisica_id
        ) AS y
        WHERE x.toma_fisica_id = y.toma_fisica_id";

      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;

}

/***********************************************************************
*usuarios validacion captura toma fisica
***********************************************************************/
function BuscarUsuario($usuario)
  {
      $sql="Select nombre from system_usuarios
            where usuario_id=".$usuario."";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;   
  }


/***********************************************************************
* NUMERO DE CONTEOS
***********************************************************************/
function ObtenerNumeroConteos($toma_fisica_id)
  {
      $sql="	SELECT
						numero_conteos
						FROM
						inv_toma_fisica 
						WHERE TRUE
						AND toma_fisica_id = '".trim($toma_fisica_id)."'	";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;   
  }


 function GetNumeroLista($toma_fisica_id,$opc=null)
 {
    $sql  = "LOCK TABLE inv_toma_fisica_numeros_listas IN ROW EXCLUSIVE MODE;";
    $sql .= "SELECT numero_lista FROM inv_toma_fisica_numeros_listas WHERE toma_fisica_id = $toma_fisica_id;";

    if(!$resultado = $this->ConexionBaseDatos($sql)) return false;

    $val_retorno=1;
    
    if($resultado->EOF)
    {
        $sql = "INSERT INTO inv_toma_fisica_numeros_listas(toma_fisica_id,numero_lista) VALUES ($toma_fisica_id,2);";
        if(!$this->ConexionBaseDatos($sql)) return false;
    }
    else
    {
        list($val_retorno)=$resultado->FetchRow();
        $resultado->Close();
        
		if($opc!='1')
        $sql = "
		UPDATE 
		inv_toma_fisica_numeros_listas SET numero_lista = numero_lista + 1 WHERE toma_fisica_id = $toma_fisica_id;";
        if(!$this->ConexionBaseDatos($sql)) return false;
    }
    return $val_retorno;
 }


/*******************************************************************************************
*
*******************************************************************************************/
function BuscarProducto($toma_fisica_id,$etiqueta,$etiqueta_normal,$codigo_barras)
{ 
  if(!empty($codigo_barras))
  {
    $filtro1="  AND     b.codigo_barras ='".$codigo_barras."' ";
  }
  else
  {
   $filtro1="";
  }
 
  if(!empty($etiqueta))
  {
    $filtro2="  AND     a.etiqueta_x_producto =".$etiqueta;
  }
  else
  {
   $filtro2="";
  }
   
  if(empty($etiqueta_normal))
  {
    $filtro3= " AND   b.etiqueta_x_producto = 1 "  ;
  }
  else
  {
    $filtro3=" AND   b.etiqueta_x_producto =".$etiqueta_normal;  
  }
 
   /*if(!empty($etiqueta)AND !empty($etiqueta_normal))
   {
     $filtro_etiqueta=" AND       b.etiqueta_x_producto =".$etiqueta_normal." ";
     $filtro_etiqueta1=" AND     a.etiqueta_x_producto =".$etiqueta." ";
   }
   else
   {
       $filtro_etiqueta=" ";
       $filtro_etiqueta1=" ";
   }*/
  $sql=" SELECT   DISTINCT a.toma_fisica_id,
                          a.etiqueta_x_producto as etiqueta,
                          b.codigo_producto,
                          fc_descripcion_producto(b.codigo_producto) as descripcion,
                          b.unidad_id,
                          b.contenido_unidad_venta,
                          c.descripcion as descripcion_unidad,
                          M.descripcion as farmacologica,
                          (
                            SELECT (COALESCE(MAX(a.num_conteo), 0) + 1)
                            FROM    inv_toma_fisica_conteos a,
                                    inv_toma_fisica_d b
                            WHERE   a.toma_fisica_id =".$toma_fisica_id."  
                            $filtro3
                            AND      a.toma_fisica_id=b.toma_fisica_id
                            AND      a.etiqueta=b.etiqueta
                          ) as num_conteo,
           CASE WHEN v.num_conteo IS NULL THEN '0' ELSE '1' END as cuadrado 
           FROM     inv_toma_fisica_d as a
           LEFT JOIN inv_toma_fisica_update as v ON(v.toma_fisica_id = a.toma_fisica_id AND v.etiqueta  = a.etiqueta),
                           inventarios_productos as b LEFT JOIN (SELECT 	a.codigo_medicamento,
									                                                                              a.	concentracion_forma_farmacologica,
									                                                                              b.descripcion
                                                                                  FROM	   medicamentos a,
                                                                                               inv_med_cod_forma_farmacologica b
							                                                                    WHERE	a.cod_forma_farmacologica = b.cod_forma_farmacologica) as M
							             ON (M.codigo_medicamento = b.codigo_producto),
                           unidades as c
           WHERE     a.toma_fisica_id =".$toma_fisica_id."
           $filtro2
           $filtro1
           AND          b.codigo_producto = a.codigo_producto
           AND          c.unidad_id = b.unidad_id
           ";
     
        //return $sql;
      if(!$resultado = $this->ConexionBaseDatos($sql))//contenido_unidad_venta
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
}


/********************************************************************************
* etiqueta por codigo barras
 *********************************************************************************/
function BuscarEtiquetasCodBar($empresa,$tomafisica_id,$conteo_oficial,$codigo_barras)
{

	$sql  = "        SELECT ";
	$sql .= "                  tf.etiqueta_x_producto FROM inv_toma_fisica_d tf, ";
	$sql .= "				   inventarios_productos ip, ";
	//$sql .= "				   inv_toma_fisica_conteos tfc, ";
	$sql .= "                   existencias_bodegas eb ";
	$sql .= "				  WHERE tf.codigo_producto = ip.codigo_producto ";
	//$sql .= "				   AND tf.toma_fisica_id = tfc.toma_fisica_id ";
	$sql .= "                  AND eb.empresa_id = tf.empresa_id ";
	$sql .= "                  AND eb.centro_utilidad = tf.centro_utilidad ";
	$sql .= "                  AND eb.bodega= tf.bodega ";
	$sql .= "                  AND eb.codigo_producto =tf.codigo_producto ";
	$sql .= "				   AND ip.codigo_barras = '".$codigo_barras."' ";
	$sql .= "				   AND tf.empresa_id = '".$empresa."' ";
	$sql .= "				   AND tf.toma_fisica_id = ".$tomafisica_id." ";
	//$sql .= "				   AND tfc.num_conteo = ".$conteo_oficial."  ";
	$sql .= "               group by 1 ";
	
    if(!$resultado = $this->ConexionBaseDatos($sql))
       return false;

     $etCodBar=Array();
     while(!$resultado->EOF)
      {
        $etCodBar = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
     $resultado->Close();
     return $etCodBar;	   

}






function BuscarProductoOtro($toma_fisica_id,$etiqueta,$etiqueta_normal,$codigo_barras,$conteo_oficial)
{ 
  if(!empty($codigo_barras))
  {
    $filtro1="  AND          b.codigo_barras ='".$codigo_barras."' ";
  }
  else
  {
   $filtro1="";
  }
  
  $sql=" SELECT  DISTINCT   a.toma_fisica_id,
                          a.etiqueta_x_producto as etiqueta,
                          b.codigo_producto,
                          fc_descripcion_producto(b.codigo_producto) as descripcion,
                          b.unidad_id,
                          b.contenido_unidad_venta,
                          c.descripcion as descripcion_unidad,
                          M.descripcion as farmacologica,
                          ".$conteo_oficial." as num_conteo
           FROM     inv_toma_fisica_d as a
           LEFT JOIN inv_toma_fisica_update as v ON(v.toma_fisica_id = a.toma_fisica_id AND v.etiqueta  = a.etiqueta),
                           inventarios_productos as b LEFT JOIN (SELECT 	a.codigo_medicamento,
									                                                                              a.	concentracion_forma_farmacologica,
									                                                                              b.descripcion
                                                                                  FROM	   medicamentos a,
                                                                                               inv_med_cod_forma_farmacologica b
							                                                                    WHERE	a.cod_forma_farmacologica = b.cod_forma_farmacologica) as M
							             ON (M.codigo_medicamento = b.codigo_producto),
                           unidades as c
           WHERE     a.toma_fisica_id =".$toma_fisica_id."
           AND          a.etiqueta_x_producto =".$etiqueta."
           $filtro1
           AND          b.codigo_producto = a.codigo_producto
           AND          c.unidad_id = b.unidad_id
          AND           v.num_conteo IS NULL
           ";
     
      if(!$resultado = $this->ConexionBaseDatos($sql))//contenido_unidad_venta
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      
      return $cuentas;
    
}

/***************************************************************************************
* TOMA FISICA
***************************************************************************************/
function SacarAdmonTomaFisica($usuario,$empresa_id)
{

    $sql="SELECT x.*,y.*
    FROM
    (
      SELECT a.*,c.descripcion as nom_bodega
      FROM
      inv_toma_fisica as a,
      inv_toma_fisica_usuarios_administradores as b,
      bodegas as c
      WHERE
      a.sw_estado = '1'
      AND b.toma_fisica_id = a.toma_fisica_id
      AND b.usuario_id = ".$usuario."
      AND c.empresa_id = a.empresa_id
      AND a.empresa_id = '".$empresa_id."'
      AND c.centro_utilidad = a.centro_utilidad
      AND c.bodega = a.bodega
    ) AS x,
    (
      SELECT COUNT(*) AS cantidad_reg,toma_fisica_id
      FROM inv_toma_fisica_d
      GROUP BY toma_fisica_id
    ) AS y
    WHERE
    x.toma_fisica_id = y.toma_fisica_id";
   
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
         
}




/***************************************************************************************
* TOMA FISICA
***************************************************************************************/
function SacarTomaFisica($usuario,$activas=false,$empresa_id)
{

    if($activas)
    {
         $filtroActivas =" AND a.fecha_inicio IS NOT NULL ";
    }
    else
    {
        $filtroActivas ="";
    }
    
    $sql="SELECT x.*,y.*
    FROM
    (
      SELECT a.*,c.descripcion as nom_bodega
      FROM
      inv_toma_fisica as a,
      inv_toma_fisica_usuarios_conteo as b,
      bodegas as c
      WHERE
      a.sw_estado = '1'
      AND b.toma_fisica_id = a.toma_fisica_id
      AND b.usuario_id = ".$usuario."
      AND c.empresa_id = a.empresa_id
      AND c.empresa_id = '".$empresa_id."'
      AND c.centro_utilidad = a.centro_utilidad
      AND c.bodega = a.bodega
      $filtroActivas
    ) AS x,
    (
      SELECT COUNT(*) AS cantidad_reg,toma_fisica_id
      FROM inv_toma_fisica_d
      GROUP BY toma_fisica_id
    ) AS y
    WHERE
    x.toma_fisica_id = y.toma_fisica_id";
   
    if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
         
}










/************************************************************************************
*Funcion que lista cuentas
*************************************************************************************/
 function BuscarCuentasStip($tip_bus,$elemento,$offset,$empresa)
 {  
     
       if($tip_bus==0)
       {    
           $sql1="select  count(*) 
            from 
            cg_conf.cg_plan_de_cuentas where empresa_id='".$empresa."'";
            $this->ProcesarSqlConteo($sql1,10,$offset);      
            
              $sql=" select * from cg_conf.cg_plan_de_cuentas  
            where empresa_id='".$empresa."' order by cuenta 
            limit ".$this->limit." OFFSET ".$this->offset."" ;
        }  
       
    if($tip_bus==1)
     {  
         $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      cuenta LIKE '".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
      $this->ProcesarSqlConteo($sql1,10,$offset);      
       
         $sql=" select * from cg_conf.cg_plan_de_cuentas where cuenta LIKE '".strtoupper ($elemento)."%' 
       and empresa_id='".$empresa."' order by cuenta
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
      
     if($tip_bus==2)
     {  
         $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      descripcion LIKE '%".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
      $this->ProcesarSqlConteo($sql1,10,$offset);      
       
         $sql=" select * from cg_conf.cg_plan_de_cuentas where descripcion LIKE '%".strtoupper ($elemento)."%' 
       and empresa_id='".$empresa."' order by cuenta
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     } 
       
   
     if($tip_bus==3)
     { 
        
      list($elemento1,$elemento2) = explode("-", $elemento);  
        $sql1="select  count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."' and empresa_id='".$empresa."'";
      $this->ProcesarSqlConteo($sql1,10,$offset);      
       
         $sql=" select  * from cg_conf.cg_plan_de_cuentas 
       where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."'
        and empresa_id='".$empresa."' order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
      
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }
 
    
/************************************************************************************
*
*Funcion que cuenta cuentas
*
*************************************************************************************/    
 function ContarCuentasStip($toma_fisica,$aumento)
 { 
     
          $sql1="select count(*)
          FROM
          inv_toma_fisica_d as b,
          inventarios_productos as c,
          unidades as d,
          inv_bodegas_movimiento_d e
          WHERE
          b.toma_fisica_id = ".$toma_fisica."
          ".$aumento."
          AND c.codigo_producto = b.codigo_producto
          AND d.unidad_id = c.unidad_id";
       
       if(!$resultado = $this->ConexionBaseDatos($sql1))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }
 

/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function Prefixo1()
{ 
       $sql=" select prefijo,documento_id,descripcion from documentos 
              where empresa_id='".SessionGetVar("EMPRESA")."'
              order by prefijo"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

  
  
            
/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function NombreCuenta($cuenta)
{ 
       
       $sql=" select descripcion from cg_conf.cg_plan_de_cuentas  
               where cuenta='".$cuenta."' and 
               empresa_id='".SessionGetVar("EMPRESA")."' 
               order by cuenta"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
            
  
  /************************************************************************************
*
*Funcion que lista los tipos documentos segun el tipo de empresa.
*
*************************************************************************************/    
    function ListarTiposDocumentos()
    { 
     
      $sql="select * from tipos_doc_generales where sw_doc_sistema='0' order by tipo_doc_general_id"; //
       
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $resultado->fields[1] = strtoupper($resultado->fields[1]);
        $resultado->fields[1] = ereg_replace("???", "E", $resultado->fields[1]); 
        $documentos[$resultado->fields[1]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      
      return $documentos;
     }

   
/************************************************************************************
*
*Funcion que lista los tipos documentos segun el tipo de empresa.
*
*************************************************************************************/    
    function ListarEmpresas() 
    { 
     
      $sql=" select * from empresas
      order by empresa_id"; //
       
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $resultado->fields[3] = strtoupper($resultado->fields[3]);
        $resultado->fields[3] = ereg_replace("???", "E", $resultado->fields[3]); 
        $documentos[$resultado->fields[3]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
   
   
   
  
/************************************************************************************
*
*Funcion que saca el nuevo id
*
*************************************************************************************/    
 function tmp_id()
 { 
  $sql1="select nextval('cg_conf.tmp_cg_mov_contable_tmp_id_seq'::regclass)";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
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

/************************************************************************************
*
*Funcion que saca el CUENTAS
*
*************************************************************************************/    
 function ConsultaCuentas($cuenta)    
 { 
    $sql1="select * from cg_conf.cg_plan_de_cuentas
    where cuenta='".$cuenta."' and empresa_id='".SessionGetVar("EMPRESA")."'
    ";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }    
      

/************************************************************************************
*
*Funcion que saca el nuevo id
*
*************************************************************************************/    
 function GranLapso($empresa)
 { 
    $sql1="select * from cg_conf.cg_lapsos_contables
    where empresa_id='".SessionGetVar("EMPRESA")."' and sw_estado='1'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
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



/************************************************************************************
*
*Funcion que saca el nuevo id
*
*************************************************************************************/    
 function ExisteLapso($lapso)    
 { 
    $sql1="select * from cg_conf.cg_lapsos_contables
    where lapso='".$lapso."' and empresa_id='".SessionGetVar("EMPRESA")."' ";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
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
 
/************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Departamentos()    
 { 
    $sql1="select centro_de_costo_id,descripcion from cg_conf.centros_de_costo
           ORDER BY descripcion";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
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

/************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Departamentos_d($depto)    
 { 
    $sql1="select descripcion from cg_conf.centros_de_costo
           where centro_de_costo_id='".$depto."' ORDER BY descripcion";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }   
 
 /************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Sacartmp_CgMovcontable_d($tmp_id) 
 { 
    $sql1="select * from cg_conf.tmp_cg_mov_contable_d
           where tmp_id='".$tmp_id."'
           ORDER BY tmp_movimiento_id";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }   
 
 
 function Sacarprefi($dc)    
 { 
           $sql1="select numero, prefijo from ".$this->MOVIMIENTO."
           where documento_contable_id='".$dc."'
           ORDER BY numero";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 } 
 function Consultampio($pais,$depto,$Municipio)
 { 
           $sql1="select tipo_mpio_id from tipo_mpios
           where 
            tipo_pais_id='".$pais."' AND
            tipo_dpto_id='".$depto."' AND
            municipio='".strtoupper($Municipio)."'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  {
   //return $sql1;
   return false;
  }
  else
  {     
    $munis=array();
    while(!$resultado->EOF)
      {
        $munis[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $munis;
  }    
 }
 
 function Consultadpto($departamentox)
 { 
   $sql1="select tipo_dpto_id from tipo_dptos
   where departamento='".strtoupper($departamentox)."'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  {
   return false;    
  } 
  else
  {
    $deptos=array();
    while(!$resultado->EOF)
      {
        $deptos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $deptos;
   }   
 }
/************************************************************************************
*
*Funcion que saca los TERCEROS($pagina,$criterio1,$criterio2,$criterio);
*
*************************************************************************************/    
  function Terceros($pagina,$tipo_id,$id,$nombre)    
  { 
       if( $tipo_id=="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) from terceros";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          $sql="select * from terceros ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
      elseif($tipo_id=="0" && $id!="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tercero_id='".$id."'
          or nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
       elseif($tipo_id!="0" && $id!="0" && $nombre=="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and tercero_id='".$id."'         
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id!="0" && $id=="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          and nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
       elseif($tipo_id!="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) 
          from terceros
          where tipo_id_tercero='".$tipo_id."'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where tipo_id_tercero='".$tipo_id."' 
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id=="0" && $id!="0" && $nombre=="0")
       {
          $sql1="select count(*) 
          from terceros
          where tercero_id='".$id."'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where
          tercero_id='".$id."'
          ORDER BY tercero_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo_id=="0" && $id=="0" && $nombre!="0")
       {
          $sql1="select count(*) 
          from terceros
          where nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
          $this->ProcesarSqlConteo($sql1,10,$pagina);     
          
          $sql="select * 
          from terceros
          where nombre_tercero LIKE '%".strtoupper ($nombre)."%'
          ORDER BY nombre_tercero
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql;
      return $cuentas;
 }   
 
 /********************************************************************************
*FUNCION QUE CUENTA TERCEROS SEGUN TIPO DE BUSQUEDA
*********************************************************************************/

  function ContarTercerosStip($tipo_id,$id,$nombre)
  { 
    
    if($tipo_id=="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) from terceros";
       }
      elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'
        or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id!="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'";
      }
      elseif($tipo_id=="0" && $id=="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id=="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."'";
      }
      elseif($tipo_id!="0" && $id=="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'
        or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      
      elseif($tipo_id=="0" && $id!="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tercero_id='".$id."'
        ";
      } 
    ///
    
      
         
     
       if(!$resultado = $this->ConexionBaseDatos($sql1))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }   
/************************************************************************************
*
*Funcion que saca los TERCEROS
*
*************************************************************************************/    
 function DC($pagina,$tip_bus,$criterio)    
 { 
    if($tip_bus==6 || $tip_bus==0)
    {     
      $sql1="select count(*) 
      from ".$this->MOVIMIENTO."";
      $this->ProcesarSqlConteo($sql1,10,$pagina);     
      
       $sql=" select *
       from ".$this->MOVIMIENTO." ORDER BY lapso
       limit ".$this->limit." OFFSET ".$this->offset.""; 
    }  
       
    if($tip_bus==1)
     {  
       $sql1="select count(*) from ".$this->MOVIMIENTO."
      where documento_contable_id ='".$criterio."'";
      $this->ProcesarSqlConteo($sql1,10,$pagina);      
       
       $sql=" select * ".$this->MOVIMIENTO."
      where documento_contable_id ='".$criterio."' ORDER BY lapso
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
       
       
    if($tip_bus==2)
     {  
         $sql1="select count(*) 
        from 
        ".$this->MOVIMIENTO."
        where lapso = '".$criterio."'";
        $this->ProcesarSqlConteo($sql1,10,$pagina);      
        
        $sql=" select * 
        from 
        ".$this->MOVIMIENTO."
        where lapso = '".$criterio."'
        ORDER BY lapso
        limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
    
      if($tip_bus==3)
      { 
       list($prefijo,$numero) = explode("-", $criterio);     
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".strtoupper($prefijo)."' and  numero=".$numero."";
       $this->ProcesarSqlConteo($sql1,10,$offset);      
       
       list($prefijo,$numero) = explode("-", $criterio);     
       $sql=" select * 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".strtoupper($prefijo)."' and  numero=".$numero."
       order by lapso
       limit ".$this->limit." OFFSET ".$this->offset."" ;
      }
       
      if($tip_bus==4)
      { 
         
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where numero = '".$criterio."'";
       $this->ProcesarSqlConteo($sql1,10,$offset);      
       
        $sql=" select *
        from 
        ".$this->MOVIMIENTO."
        where numero = '".$criterio."'
        order by lapso
        limit ".$this->limit." OFFSET ".$this->offset."" ;
      }
      if($tip_bus==5)
      { 
        list($criterio1,$criterio2) = explode("-", $criterio);   
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where tipo_id_tercero = '".$criterio1."' and tercero_id='".$criterio2."'";
       $this->ProcesarSqlConteo($sql1,10,$offset);      
      
        $sql=" select * 
        from 
        ".$this->MOVIMIENTO."
        where tipo_id_tercero = '".$criterio1."' and tercero_id='".$criterio2."'
        order by lapso
        limit ".$this->limit." OFFSET ".$this->offset."" ;
      }
      
     
      
     
    
    
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
 
 /********************************************************************************
*FUNCION QUE CUENTA TERCEROS SEGUN TIPO DE BUSQUEDA
*********************************************************************************/

  function ContarDCStip($tip_bus,$criterio)
  { 
    
    ///
    if($tip_bus==6 || $tip_bus==0)
    {     
      $sql1="select count(*) 
      from ".$this->MOVIMIENTO."";
    }  
       
    if($tip_bus==1)
     {  
       $sql1="select count(*) 
       from 
       ".$this->MOVIMIENTO."
       where documento_contable_id ='".$criterio."'";
     }
            
    if($tip_bus==2)
     {  
        $sql1="select count(*) 
        from 
        ".$this->MOVIMIENTO."
        where lapso = '".$criterio."'";
     }
    
      if($tip_bus==3)
      { 
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".$criterio."'";
      }
       
      if($tip_bus==4)
      { 
         
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where numero = '".$criterio."'";
     
       
     
      }
      if($tip_bus==5)
      { 
         list($criterio1,$criterio2) = explode("-", $criterio);  
       $sql1="select  count(*) 
       from 
       ".$this->MOVIMIENTO."
       where tipo_id_tercero = '".$criterio1."' and tercero_id='".$criterio2."'";
         
     
      }
      
     
      
    
    ///
    
      
         
     
       if(!$resultado = $this->ConexionBaseDatos($sql1))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }   
 
     
/************************************************************************************
*
*Funcion que saca toda la info de tmp_cg_mov_contable
*
*************************************************************************************/    
 function SacarCgMovcontable($tip_doc)    
 { 
    $sql1="select a.*,b.tipo_doc_general_id from cg_conf.tmp_cg_mov_contable as a,documentos as b
           where b.tipo_doc_general_id='".$tip_doc."' 
           and a.prefijo=b.prefijo 
           and a.documento_id=b.documento_id
           and a.usuario_id='".UserGetUID()."' order by tmp_id";
  //return $sql1; 
  if(!$resultado = $this->ConexionBaseDatos($sql1))
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
    * Funcion que inserta  en la tabla cg_parametros_documentos, 
    * 
    * @return mensaje de confirmacion
    ***********************************************************************************/
  function  GuardarDocumentoBD($tmp_id,$lapso,$empresa_id,$prefijox,$documento_id,$total_d,$total_c,$tip_ter_id,$ter_id,$usuario,$fecha)
   { 
      
      $sql="insert into  cg_conf.tmp_cg_mov_contable
      values(".$tmp_id.",'".$lapso."','".$empresa_id."','".$prefijox."',
             ".$documento_id.",".$total_d.",".$total_c.",'".$tip_ter_id."','".$ter_id."',".$usuario.",'".$fecha."');";
                   
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci???";
         //return $cad;
         return $sql;
       }
      else
       {      
         $cad="Documento Agregado Satisfactoriamente";
         $rst->Close();
         return $cad;
       }
    
    }
     
 /**********************************************************************************
  * Funcion que inserta  departamentos, 
  * 
  * @return vector
    ***********************************************************************************/
  function GXD($id_pais,$departamentox)
   { 
      
      $sql="select max(tipo_dpto_id) from tipo_dptos
      where tipo_pais_id='".$id_pais."'"; 
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
      $documentos=Array();
      while(!$resultado->EOF)
      {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
      }
      $resultado->Close();
      if(!empty($documentos))
      {
        $codigo_dep=$documentos[0]['max']+1;
      }
      else
      {
       $codigo_dep=1;
      }
      $sql="insert into tipo_dptos values('".$codigo_dep."','".$id_pais."','".strtoupper($departamentox)."');";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci???";
         //return $cad;
         return $sql;
       }
      else
       {      
         $cad=$codigo_dep;
         $rst->Close();
         return $cad;
       }
    
    }
    
/**********************************************************************************
* Funcion que inserta  municipios, 
* 
* @return vector
***********************************************************************************/
  function GXM($id_pais,$id_dept,$Municipio)
   { 
    
      $sql="select max(tipo_mpio_id) from tipo_mpios
      where tipo_pais_id='".$id_pais."' and  tipo_dpto_id='".$id_dept."'"; 
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
      $documentos=Array();
      while(!$resultado->EOF)
      {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
      }
      $resultado->Close();
      if(!empty($documentos))
      {
        $codigo_mun=$documentos[0]['max']+1;
      }
      else
      {
        $codigo_mun=1;
      }                                        
      $sql="insert into tipo_mpios values('".$id_pais."','".$id_dept."','".$codigo_mun."','".strtoupper($Municipio)."');";
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="no se hizo la inserci???";
         //return $cad;
         return $sql;
       }
      else
       {      
         $cad=$codigo_mun;
         $rst->Close();
         return $cad;
       }
    
    }
    

/**********************************************************************************
* Funcion que inserta  en la tabla cg_parametros_documentos, 
* 
* @return mensaje de confirmacion
***********************************************************************************/
 function  GuardarPersonas( $tipo_identificacion,
                            $id_tercero,
                            $nombre,
                            $pais,
                            $departamento,
                            $municipio,
                            $direccion,
                            $telefono,
                            $faz,
                            $email,
                            $celular,
                            $perjur)
 { 
      //var_dump($direccion);
      if($direccion=="")
      {
         $direccion="NULL";
      }
      else
      {
         $direccion="'".$direccion."'";
      }     
      if($telefono==0)
      {
         $telefono="NULL";
      }
      else
      {
         $telefono="'".$telefono."'";
      }     
      if($faz==0)
      {
         $faz="NULL";
      }
      else
      {
        $faz="'".$faz."'";
      }
      if($email==0)
      {
         $email="NULL";
      }
      else
      {
        $email="'".$email."'";
      }
      if($celular==0)
      {
         $celular="NULL";
      } 
      else
      {
        $celular="'".$celular."'";
      }
          
      $sql="insert into  terceros
      values('".$tipo_identificacion."','".$id_tercero."','".$pais."',
             '".$departamento."','".$municipio."',".$direccion.",".$telefono.",
             ".$faz.",".$email.",".$celular.",'".$perjur."','0',".UserGetUID().",now(),NULL,'".$nombre."');";
//           tipo_id_tercero   tercero_id  tipo_pais_id  
//           tipo_dpto_id  tipo_mpio_id  direccion
//          telefono  fax   email   celular   
//          sw_persona_juridica   cal_cli   usuario_id  
//          fecha_registro  busca_persona   nombre_tercero           
   if(!$rst = $this->ConexionBaseDatos($sql)) 
   {  $cad="no se hizo la inserci???";
      //return $cad;
     return $sql;
   }
   else
   {      
     //$cad=$sql;
     $cad="EXITO";
     $rst->Close();
     return $cad;
   }
    
}
    /*******************************************************************************
    *up cg_mov_contable
    ********************************************************************************/
    function UpDocumentosCgMov($tmp_id,$debicredi,$valor)
    {

      if($debicredi=="D")
      {   
         // $credi=0; 
       // tmp_id  lapso   empresa_id  prefijo   documento_id  total_debitos   total_creditos  tipo_id_tercero   tercero_id  usuario_id
      
          $sql="Update cg_conf.tmp_cg_mov_contable 
          SET total_debitos=total_debitos+".$valor."
           where tmp_id=".$tmp_id."";
      }
      elseif($debicredi=="C")
      {
        $sql="Update cg_conf.tmp_cg_mov_contable 
        SET total_creditos=total_creditos+".$valor."
        where tmp_id=".$tmp_id.""; 
      
      }
                       
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         return $sql;
         //return $cad;
       }
      else
       {      
         $cad="DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE";
         $rst->Close();
         return $cad;
       }
    
    }
     /*******************************************************************************
    *up cg_mov_contable
    ********************************************************************************/
    function RestarDescuento($tmp_id,$descuento_D,$descuento_C)
    {

      if($descuento_D>0)
      {   
         // $credi=0; 
       // tmp_id  lapso   empresa_id  prefijo   documento_id  total_debitos   total_creditos  tipo_id_tercero   tercero_id  usuario_id
      
          $sql="Update cg_conf.tmp_cg_mov_contable 
          SET total_debitos=total_debitos-".$descuento_D."
           where tmp_id=".$tmp_id."";
      }
      elseif($descuento_C>0)
      {
        $sql="Update cg_conf.tmp_cg_mov_contable 
        SET total_creditos=total_creditos-".$descuento_C."
        where tmp_id=".$tmp_id.""; 
      
      }
                       
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         return $sql;
         //return $cad;
       }
      else
       {      
         $cad="DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE";
         $rst->Close();
         return $cad;
       }
    
    }
    
    
    
/************************************************************************************
*
*Funcion que consulta lapsos contables.
*
*************************************************************************************/    
    function BuscarLapsos()
    { 
       $sql=" select * from cg_conf.cg_lapsos_contables WHERE empresa_id='".SessionGetVar("EMPRESA")."' order by lapso DESC"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql;
      return $documentos;
     }
    
     
//   functi
//   $sql1="select count(*) 
//           from terceros
//           where tipo_id_tercero='".$tipo_id."' 
//           and tercero_id='".$id."'
//           or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";   
/************************************************************************************
*
*Funcion que consulta lapsos contables.
*
*************************************************************************************/    
    function Buscardcs($prefijo,$numero)
    { 
       $sql=" select * 
       from 
       ".$this->MOVIMIENTO."
       where prefijo = '".strtoupper($prefijo)."' and  numero=".$numero."
       order by lapso"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
    
    
    
    
 
 

/************************************************************************************
*
*Funcion que consulta EL monto del mivimiento.
*
*************************************************************************************/    
    function DescuentoMov($id)
    { 
       $sql=" select * from cg_conf.tmp_cg_mov_contable_d
              where tmp_movimiento_id=".$id.""; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }     

/************************************************************************************
*
*Funcion que consulta documentos de tipos_doc_generales.
*
*************************************************************************************/    
    function MostrarDocumentos()
    { 
       $sql=" select * from cg_conf.cg_lapsos_contables"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }      
/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
    function TiposDocumento()
    { 
       $sql=" select * from tipos_doc_generales Order by descripcion"; 
             
     
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
     }

/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function PrefijoWTip_doc($tip_doc)
{ 
       $sql=" select prefijo,documento_id,descripcion from documentos 
              where tipo_doc_general_id='".$tip_doc."' and empresa_id='".SessionGetVar("EMPRESA")."'
              order by descripcion"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta tipos de documentos ORGANIZA PREFIJO.
*
*************************************************************************************/    
function PrefijoWTip_docP($tip_doc)
{ 
       $sql=" select prefijo,documento_id,descripcion from documentos 
              where tipo_doc_general_id='".$tip_doc."' and empresa_id='".SessionGetVar("EMPRESA")."'
              order by prefijo"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta tipos de documentos.
*
*************************************************************************************/    
function SacarDescripcionDocumento($prefijo)
{ 
       $sql=" select descripcion from documentos 
              where prefijo='".$prefijo."' and empresa_id='".SessionGetVar("EMPRESA")."'"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      //return $sql;
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta tipos de id terceros.
*
*************************************************************************************/    
function Terceros_id()
{ 
       $sql=" select * from tipo_id_terceros order by indice_de_orden"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta paises.
*
*************************************************************************************/    
function Paises()
{ 
       $sql=" select * from tipo_pais order by pais"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta paises.
*
*************************************************************************************/    
function DePX($id_pais)
{ 
       $sql="select * 
       from tipo_dptos
       where tipo_pais_id='".$id_pais."'    
       order by departamento"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
  
  /************************************************************************************
*
*Funcion que consulta paises.
*
*************************************************************************************/    
function DeMX($id_pais,$id_dpto)
{ 
       $sql="select * 
       from tipo_mpios
       where tipo_pais_id='".$id_pais."'    
       and tipo_dpto_id='".$id_dpto."'
       order by municipio"; 
       
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
/************************************************************************************
*
*Funcion que consulta tipos de documentos2.
*
*************************************************************************************/    
function ConsultarXPrefijo($pre,$lapso)
{ 
       $sql=" select prefijo,numero,documento_id 
              from ".$this->MOVIMIENTO."
              where prefijo='".$pre."' and lapso='".$lapso."' "; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/************************************************************************************
*
*Funcion que consulta movimientos.
*
*************************************************************************************/    
function ConsultarXLapso($lap,$empresa_id)
{ 
       $sql=" select *
              from 
              ".$this->MOVIMIENTO."
              where lapso='".$lap."' and empresa_id='".$empresa_id."'"; 
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
/***************************************************************************
* Consultar por lapso con documento 
****************************************************************************/
function ConsultarXLapsoWdoc($lap,$empresa_id,$tip_doc)
{ 
       $sql="SELECT distinct b.prefijo,b.documento_contable_id,b.numero 
            from (select prefijo from documentos where tipo_doc_general_id='".$tip_doc."') as a 
            JOIN 
            (select * from 
            ".$this->MOVIMIENTO." where lapso='".$lap."' and empresa_id='".$empresa_id."') as b 
            on 
            (a.prefijo=b.prefijo)";
       
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}

/***************************************************************************
* Consultar por lapso con documento 
****************************************************************************/
function ConsultarXLapsoWdocPre($lap,$empresa_id,$tip_doc)
{ 
       $sql="SELECT distinct b.prefijo 
            from (select prefijo from documentos where tipo_doc_general_id='".$tip_doc."') as a 
            JOIN 
            (select * from 
            ".$this->MOVIMIENTO." where lapso='".$lap."' and empresa_id='".$empresa_id."') as b 
            on 
            (a.prefijo=b.prefijo)";
       
             
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      return $documentos;
}
/**************************************************************************************
*
*************************************************************************************/
function PrefijoWTip_docWlapso($tip_doc,$lapso)
{
    $sql=" select prefijo,documento_id from documentos 
    where 
    tipo_doc_general_id='".$tip_doc."' and 
    empresa_id='".SessionGetVar("EMPRESA")."'"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      return $documentos;


}
/**************************************************************************************
*busca la descripcion de la tabla tipo bloqueo
*************************************************************************************/
function descrip($tip_blo)
{
    $sql=" select tipo_bloqueo_id,descripcion 
    from cg_tipo_bloqueo_movimientos
    where 
    tipo_bloqueo_id='".$tip_blo."'"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      return $documentos;


}
/**************************************************************************************
*seleccion de movimiento detalle
*************************************************************************************/
function ConsultarMovDet($doc,$lapso)
{
    $sql="select distinct a.*,b.descripcion 
    from cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_".$lapso." as a,cg_conf.cg_plan_de_cuentas as b
    where 
    documento_contable_id='".$doc."' and a.cuenta=b.cuenta"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     //return $sql;
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}
/**************************************************************************************
*seleccion del proximo tmp_movimiento_id
*************************************************************************************/
function tmp_movimiento_id()
{
    $sql=" select nextval('cg_conf.tmp_cg_mov_contable_d_tmp_movimiento_id_seq'::regclass)"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      return $documentos;


}


/**************************************************************************************
*seleccion del proximo tmp_movimiento_id
*************************************************************************************/
function doc_contable_id()
{
    $sql ="Select nextval('cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_documento_contable_id_seq'::regclass);"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}

/*************************************************************************************
*sacar nuevo id para cg_mov_contable
***********************************************************************************/
function nue_mov_contable($lapso)
{
    $sql ="select   nextval('cg_mov_".SessionGetVar("EMPRESA").".cg_mov_contable_".SessionGetVar("EMPRESA")."_".$lapso."_movimiento_contable_id_seq'::regclass)"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}

/**********************************************************************************
*sacar datos
***********************************************************************************/
function SacarDatosMovimientoTmp($tmp_id)
{
   GLOBAL $ADODB_FETCH_MODE;
   $sql ="Select * from cg_conf.tmp_cg_mov_contable where tmp_id='".$tmp_id."'"; 
             
     //if(!$resultado = $this->ConexionBaseDatos($sql))
     //return false;
        
     list($dbconn) = GetDBconn();
     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     $result = $dbconn->Execute($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_NUM;      
     $documentos=Array();
      while($datos=$result->FetchRow())
      {
         $documentos=$datos;
       
       //$documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       //$resultado->MoveNext();
     }
  $result->Close();   
     
     $sql ="Select tipo_doc_general_id from documentos where documento_id=".$documentos['documento_id']."";  
     //var_dump($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     $result1 = $dbconn->Execute($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    //var_dump($result1);
       
     while($datos1=$result1->FetchRow())
      {//var_dump($datos1);
        $documentos['tipo_doc_general_id']=$datos1['tipo_doc_general_id'];  
     }
     $result1->Close();
       
      
      $documentos=str_replace(" ", "",$documentos);
      //var_dump($documentos);
      return $documentos;
}



/**********************************************************************************
*sacar datos tmp_d
***********************************************************************************/
function SacarDatosMovimientos_tmp_d($tmp_id)
{
   GLOBAL $ADODB_FETCH_MODE;
   $sql ="Select * from cg_conf.tmp_cg_mov_contable_d where tmp_id='".$tmp_id."'"; 
             
     list($dbconn) = GetDBconn();
     $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
     $result = $dbconn->Execute($sql);
     $ADODB_FETCH_MODE = ADODB_FETCH_NUM;      
        
     $movimientos=Array();
     while($datos=$result->FetchRow())
      {
        $movimientos[]=$datos;
     }
    
      $result->Close();
      $movimientos=str_replace(" ", "",$movimientos);
      //var_dump($movimientos);
      return $movimientos;
}

/**************************************************************************************
*seleccion del proximo tmp_movimiento_id
*************************************************************************************/
function Sacartmp_Cg_Mov_deb_cre($tmp_id)
{
    $sql=" select total_creditos,total_debitos
            from cg_conf.tmp_cg_mov_contable
            where tmp_id=".$tmp_id.""; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}
/************************************************************************************
*funcion q guarda en tmp_cg_mov_contable_d
*************************************************************************************/
function Guardar_Mov_db($tmp_movimiento_id,$tmp_id,$dcruce,$empresa,
                        $cuenta_mov,$tipo_id_tercero,$tercero_id,
                        $debito,$credito,$detalle_mov,
                        $centro_de_costo,$base_rtf,$porcentaje_rtf)
{

              $sql="insert into cg_conf.tmp_cg_mov_contable_d 
              values(".$tmp_movimiento_id.",".$tmp_id.",$dcruce,'".$empresa."',
               '".$cuenta_mov."',$tipo_id_tercero,$tercero_id,
               ".$debito.",".$credito.",'".$detalle_mov."',
               ".$centro_de_costo.",".$base_rtf.",".$porcentaje_rtf.")";

     if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="OPERACION INVALIDA";
          return $sql;
          return $cad;
        } 
        else 
         {
           $cad="MOVIMIENTO CREADO SATISFACTORIAMENTE";  
           return $cad;
         }   




}                        

function GetClassDocumentos($tip_doc)
{
  $sql = "SELECT b.tipo_doc_general_id
          FROM tipos_doc_generales as b
          WHERE b.tipo_doc_general_id ='".$tip_doc."' 
          and b.sw_doc_sistema = '1' ";
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return $sql;
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
   
  $resultado->Close();
   
  if(empty($documentos))
  {
    //echo "jj".$this->MOVIMIENTO."ja";
    return $this->MOVIMIENTO;
  }
  else
  {
    $salida ="cg_mov_".SessionGetVar("EMPRESA"). '."' . "cg_mov_contable_".SessionGetVar("EMPRESA") . "_" . $documentos[0]['tipo_doc_general_id'] . '"';
    return $salida;
  }  
  
}

/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
function SacarMovPN($offset,$prefijo,$numero)
{ 
  if($prefijo=='-1' || $numero=='')
  { 
        $cad="DATOS INCOMPLETOS";
        return $cad;
  
  }
  
  $sqlx="select tipo_doc_general_id 
         from documentos 
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc[0]['tipo_doc_general_id']);
  
 
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where prefijo='".$prefijo."'
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."' ";
  
  $this->ProcesarSqlConteo($sql1,10,$offset);     
  
  $sql=" select *
  from ".$MOVIMIENTO."
  where prefijo='".$prefijo."'
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."'
  order by fecha_documento,numero   
  limit ".$this->limit." OFFSET ".$this->offset.""; 
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  {
   //return $sql;
   return false;
  } 
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  //return $sql;
  return $documentos;
}

/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
function SacarMov($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
{ 
  if($lapso=='0' || $tip_doc=='-1' || $prefijo=='1')
  { 
        $cad="DATOS INCOMPLETOS";
        return $cad;
  
  }
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  
  $filtrodia="";
  
  if(!empty($dia1))
  {
      $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $filtrodia=" and fecha_documento = '".$fecha1."' ";
      if(!empty($dia2))
      {
        if( $dia2 < $dia1) 
        {
          $cad="EL DIA FINAL DEBE SER MAYOR AL DIA INICIAL".$dia1."**".$dia2; 
          return $cad; 
        }
        $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
        $fecha2=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia2;
        $filtrodia=" and fecha_documento >= '".$fecha1."' and fecha_documento <= '".$fecha2."' ";    
      
      }
      
    
    
  }
  
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."' ";
  
  $this->ProcesarSqlConteo($sql1,10,$offset);     
  
  $sql=" select *
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."'
  order by fecha_documento,numero   
  limit ".$this->limit." OFFSET ".$this->offset.""; 
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  //return $MOVIMIENTO;
  //return $sql;
  //return false;
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  //return $MOVIMIENTO;
  //return $sql;
  return $documentos;
}
/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
function SacarMov2($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
{ 
  if($lapso=='0' || $tip_doc=='-1' || $prefijo=='1')
  { 
        $cad="DATOS INCOMPLETOS";
        return $cad;
  
  }
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  
  $filtrodia="";
  
  if(!empty($dia1))
  {
      $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $filtrodia=" and fecha_documento = '".$fecha1."' ";
      if(!empty($dia2))
      {
        if( $dia2 < $dia1) 
        {
          $cad="EL DIA FINAL DEBE SER MAYOR AL DIA INICIAL".$dia1."**".$dia2; 
          return $cad; 
        }
        $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
        $fecha2=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia2;
        $filtrodia=" and fecha_documento >= '".$fecha1."' and fecha_documento <= '".$fecha2."' ";    
      
      }
      
    
    
  }
  
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."' ";
  
  $this->ProcesarSqlConteo($sql1,50,$offset);     
  
  $sql=" select *
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."'
  order by fecha_documento,numero   
  limit ".$this->limit." OFFSET ".$this->offset.""; 
  
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return false;
    
  $documentos=Array();
  while(!$resultado->EOF)
  {    
    $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $documentos;
}
/************************************************************************
**para encontrar prefijo y nuero de un doc cruz
**************************************************************************/
function num($dc)
{
$sql=" select prefijo,numero
            from ".$this->MOVIMIENTO."
            where documento_contable_id=".$dc.""; 
             
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
/*******************************
*nom terceros
*********************************/

function Nombre($tercero_id)
{
 $sql=" select *
        from terceros
        where tercero_id='".trim($tercero_id)."'"; 
             
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
function Nombres($tipo_id,$tercero_id)
{
 $sql=" select *
        from terceros
        where tercero_id='".trim($tercero_id)."'
        and tipo_id_tercero='".$tipo_id."'"; 
             
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
/***********************************************************************
*Realiza consulta dependiendo de los parametros
************************************************************************/
         
function ContarSacarMov($lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
{ 
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  $filtrodia="";
  
  if(!empty($dia1))
  {
      $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
      $filtrodia=" and fecha_documento = '".$fecha1."' ";
      if(!empty($dia2))
      {
        if( $dia2 < $dia1) 
        {
          $cad="EL DIA FINAL DEBE SER MAYOR AL DIA INICIAL".$dia1."**".$dia2; 
          return $cad; 
        }
        $fecha1=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia1;
        $fecha2=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia2;
        $filtrodia=" and fecha_documento >= '".$fecha1."' and fecha_documento <= '".$fecha2."' ";    
      
      }
  }
  
  $sql="select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo='$prefijo' 
  $filtrodia
  and empresa_id='".SessionGetVar("EMPRESA")."' ";


       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
       
      $resultado->Close();
      //return $sql;
      return $documentos;
}


/***********************************************************************
*comtar pendientes contablizar
************************************************************************/
         
function ContarPenDocs($lapso,$prefijo,$tip_doc)
{ 
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc);
  $sql="select count(*) 
  from ".$MOVIMIENTO."
  where lapso='$lapso' 
  and prefijo ='$prefijo' 
  and empresa_id='".SessionGetVar("EMPRESA")."' 
  and documento_contable_id IS NULL";


      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      list($documentos)=$resultado->FetchRow();

      $resultado->Close();
      return $documentos;
}
/***********************************************************************
**descripcion del tipo e doc general
***********************************************************************/
function TipoDocumento($tip_doc)
{ 
  
  $sqlx="select descripcion
         from tipos_doc_generales
         where tipo_doc_general_id='".$tip_doc."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_dos=Array();
  while(!$resultado->EOF)
  {    
    $tip_dos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_dos;
}
/***********************************************************************
**descripcion del tipo e doc general
***********************************************************************/
function Documentus($prefijo)
{ 
  
  $sqlx="select descripcion
         from documentos
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_dos=Array();
  while(!$resultado->EOF)
  {    
    $tip_dos[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_dos;
}
/***********************************************************************
**contar comn prefijoi y numero
***********************************************************************/
function ContarPenDocs1($prefijo,$numero)
{ 
  
  $sqlx="select tipo_doc_general_id 
         from documentos 
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc[0]['tipo_doc_general_id']);
   $sql="select count(*) 
  from ".$MOVIMIENTO."
  where prefijo ='$prefijo' 
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."' 
  and documento_contable_id IS NULL";
  
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_doc;
 
  
}
/***********************************************************************
**contar comn prefijoi y numero
***********************************************************************/
function ContarSacarMovPN($prefijo,$numero)
{ 
  
  $sqlx="select tipo_doc_general_id 
         from documentos 
         where prefijo='".$prefijo."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sqlx))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  
  $MOVIMIENTO = $this->GetClassDocumentos($tip_doc[0]['tipo_doc_general_id']);
  
 
  $sql1=" select count(*) 
  from ".$MOVIMIENTO."
  where prefijo='".$prefijo."'
  and numero=".$numero." 
  and empresa_id='".SessionGetVar("EMPRESA")."'";
  
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;
    
  $tip_doc=Array();
  while(!$resultado->EOF)
  {    
    $tip_doc[] = $resultado->GetRowAssoc($ToUpper = false);
    $resultado->MoveNext();
  }
    
  $resultado->Close();
  return $tip_doc;
}
/************************************************************************************
*
*Funcion borrara parametros
*
*************************************************************************************/    
    function EliminarMovDet($id)
    { 
       
      $sql="delete from cg_conf.tmp_cg_mov_contable_d
          where tmp_movimiento_id=".$id."";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $cad;
        } 
        else 
         {
           $cad="Movimiento Eliminado Correctamente";  
           return $cad;
         }   
     }
         
/************************************************************************************
*
*Funcion borrara documentos
*
*************************************************************************************/    
    function EliminarDocs($tmp_id)
    { 
       
      $sql="delete from cg_conf.tmp_cg_mov_contable
          where tmp_id=".$tmp_id."";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $sql;
          //return $cad;
        } 
        else 
         {
           $cad="DOCUMENTO ELIMINADO CORRECTAMENTE";  
           return $cad;
         }   
     }
  
         
/************************************************************************************
*
*Funcion borrara documentos
*
*************************************************************************************/    
    function EliminarDocsMov($tmp_id)
    { 
       
      $sql="delete from cg_conf.tmp_cg_mov_contable_d
          where tmp_id=".$tmp_id."";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida al borrar datos";
          return $sql;          
          //return $cad;
        } 
        else 
         {
           $cad="MOVIMIENTOS ELIMINADOS CORRECTAMENTE";  
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

 function BorrarTemporalMovimiento($tmp_id)
 {
   $sql="delete from cg_conf.tmp_cg_mov_contable where tmp_id=".$tmp_id."";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
        $cad="Operacion Invalida al borrar datos";
        return $sql;          
      return $cad;
      }
      else $cad="ok1";
      return $cad;
 }
 
 function BorrarTemporalMovimientoDetalle($tmp_id)
 {
   $sql="delete from cg_conf.tmp_cg_mov_contable_d where tmp_id=".$tmp_id."";
      if(!$resultado = $this->ConexionBaseDatos($sql))
      {
        $cad="Operacion Invalida al borrar datos";
        return $sql;          
      return $cad;
      }
     else $cad="ok2";
     return $cad; 
       
 }
 
  function copiar($datos_movimiento,$datos_movimiento_d)
   {
    //var_dump($datos_movimiento);
     $contabilizador= new ContabilizarDocumento();
     if($contabilizador->SetDocumento($datos_movimiento,true)===false)
     {
       $CAD="TA MAL POR MIVIMINETO"; 
       RETURN $contabilizador->mensajeDeError;
     }
     
     foreach($datos_movimiento_d as $k=>$v)
     {
       if($contabilizador->AddMOV($v)===false)
       {
         $cad="detalles".$contabilizador->ErrMsg();
         RETURN $cad; 
       }
     }
     //$contabilizador->SetDocumento($datos_movimiento,true);

     
     //$contabilizador->AddMOV($datos_movimiento_d);     
     
     if($contabilizador->GenerarDocumentoContable()===false)
     {
       $CAD="mezcla".$contabilizador->ErrMsg();
       //$CAD="mezcla".$contabilizador->GenerarDocumentoContable();
       RETURN $CAD;
     }
     //$contabilizador->GenerarDocumentoContable();   
     if($contabilizador->RetornarDocumentoContable()===false)
     {
       $cad=$contabilizador->ErrMsg();
       return $cad;
     }
     if(is_array($contabilizador->RetornarDocumentoContable()))
     {
       $resultadito=$contabilizador->RetornarDocumentoContable();
       
        $total=$resultadito['RESULTADO_D']." CON PREFIJO  ".$resultadito['prefijo']."  Y CON NUMERO  ".$resultadito['numero'];
        return $total;
     }
     
   }
 
/************************************************************************
*contabilizar un solo doc
**************************************************************************/

function ContabilizarDocx($datos)
   {
     $contabilizador= new ContabilizacionDeDocumentos();
    
    for($i=0;$i<count($datos);$i++)
    {   
        list($empresa_id,$prefijo,$numero,$actualizar) = explode("@", $datos[$i]);  
        
        if($actualizar=="")
        $actualizar=false;
        if($actualizar=="1")
        $actualizar=true;
        
        
        $resultad=$contabilizador->ContabilizarDocumento($empresa_id,$prefijo,$numero,$actualizar);
        if($resultad ===false)
        {
          $CAD="problemas &nbsp;".$contabilizador->Err().$contabilizador->ErrMsg();
          RETURN $CAD;
        }
        else
        {
         $vector[]=$resultad;
        
        }
    } 
     
     return $vector;
     
   }
/**************************************************************************************
*funcion para contabilizar por lapso
****************************************************************************************/   
  function Contabilizarlapso($empresa_id,$prefijo,$lapso,$actualizar=false)
   {
    $cad="";
    $contabilizador= new ContabilizacionDeDocumentos();
     
    $resultado=$contabilizador->ContabilizarLapsoDocumento($empresa_id,$prefijo,$lapso,$actualizar);
    if($resultado === false)
     {
        $cad= "ERRORES : " . $a->Err() . "<br>" . $a->ErrMsg() . "<br>";
        return $cad;
     }
     
    $RETORNOS = $contabilizador->GetRetornoLoteContabilizacion();
    if(is_array($RETORNOS))
    {
        $cad="LAPSO : $lapso   NUMERO DE DOCUMENTOS CONTABILIZADOS : " . count($RETORNOS) . "<br>";
        $cad.= "---------------------------------------------------------------------------<br><br>";

        foreach($RETORNOS as $numero => $detalle)
        {
            if($detalle['RESULTADO'])
            {
                $cad.= "$prefijo $numero : " . $detalle['DETALLE'] . "<br>";
            }
            else
            {
                $cad.= "$prefijo $numero : " . $detalle['TITULO'] . "<br>";
                $cad.= $detalle['DETALLE'] . "<br><br>";
            }
        }
    }
    else
    {
        $cad.= "NO SE CONTABILIZARON DOCUMENTOS<BR><BR>";
        
    }
     
    
    return $cad; 
     
     
   }
   
/**************************************************************************************
*funcion para obtener documentos por lapso
****************************************************************************************/   
  function RevisionLapso($empresa_id,$lapso)
   {
    $cad="";
    $contabilizador= new ContabilizacionDeDocumentos();
     
    $resultado=$contabilizador->GetInformacionDocumentosLapso($empresa_id,$lapso);
    if($resultado === false)
     {
        $cad= "ERRORES : " . $contabilizador->Err() . "<br>" . $contabilizador->ErrMsg() . "<br>";
        return $cad;
     }
    
    if(is_array($resultado))
    {
      return $resultado;  
    }
    else
    {
        $cad.= "NO SE CONTABILIZARON DOCUMENTOS<BR><BR>";
        
        return $cad; 
    }
     
    
    
     
     
   }      
   
    /**
        * Funcion donde consulta parametrizacion de la torre de cada producto y su due??o
       *
       * @return booleano
      */
  function Buscarparamprod($empresa_id,$toma_fisica_id)
  {
    
    $sql  = "SELECT	* ";
    $sql .= "FROM		vald_jefetomaf ";
    $sql .= "WHERE	empresa_id='".$empresa_id."' ";
    $sql .= "AND	  toma_fisica_id='".$toma_fisica_id."' ";
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
  }
  
  function GuardarParGrabar($toma_fisica_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id)
  {
     //$this->ConexionTransaccion();
     $sql = " INSERT INTO  vald_jefetomaf(
                           id_vald_jefetomaf,              
                           toma_fisica_id,	
                           sw_jefebodega,
                           sw_jefecontroli,
                           empresa_id,
                           usuario_registro,
                           fecha_registro)
               VALUES     (default,
                           '".$toma_fisica_id."',
                           '".$sw_jefebodega."',
                           '".$sw_jefecontroli."',
                           '".$empresa_id."',
                           ".UserGetUID().",
                           NOW() )";
                 
    if(!$resultado = $this->ConexionBaseDatos($sql))
    {
      $cad="Operacion Invalida";
      return false;//$cad;
    } 
    return true;
  }
  
  function ActuParam($toma_fisica_id,$sw_jefebodega,$sw_jefecontroli,$empresa_id)
  {
     $sql = " UPDATE vald_jefetomaf
              SET    sw_jefebodega='".$sw_jefebodega."',sw_jefecontroli='".$sw_jefecontroli."'             
              WHERE  toma_fisica_id='".$toma_fisica_id."'
              AND    empresa_id ='".$empresa_id."';
              ";
              
      
              
    
    if(!$resultado = $this->ConexionBaseDatos($sql))
    {
      $cad="Operacion Invalida";
      return false;//$cad;
    } 
    
    return true;
  }
    /******************************************************************************
    *INACTIVAR TOMA FISICA
    ********************************************************************************/
    function InactivarTomaFisica($toma_id)
    {
      $sql=" UPDATE inv_toma_fisica
             SET    sw_estado='0'
             WHERE  toma_fisica_id = ".$toma_id." ";
      if(!$result = $this->ConexionBaseDatos($sql))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        return false;
      }

      return true;
    }
    
    function CuadrarTomaFisica($toma_id)
    {
      $sql=" UPDATE inv_toma_fisica_update
             SET    sw_cuadre='1'
             WHERE  toma_fisica_id = ".$toma_id." ";
      if(!$result = $this->ConexionBaseDatos($sql))
      {
        $this->error = "[" . get_class($this) . "][" . __LINE__ . "]";
        return false;
      }

      return true;
    }
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			/*$dbconn->debug=true;*/
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->mensajeDeError = "ERROR DB : " . $dbconn->ErrorMsg();
                return false;
			}
			return $rst;
		}
	}
?>