<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: AdminFarmaciaSQL.class.php,v 1.0 2010/01/26 22:40:38 sandra Exp $Revision: 1.26 $
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	class AdminFarmaciaSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function AdminFarmaciaSQL(){}

	/**
	* Funcion donde se Obtiene el permiso 
	* @return array $datos vector que contiene la informacion de la consulta 
	*/
		function ObtenerPermisos()
		{
	
			$sql  = "SELECT   	a.empresa_id, ";
			$sql .= "           b.razon_social as descripcion1, ";
			$sql .= "       	b.sw_activa, ";
			$sql .= "           a.centro_utilidad, ";
			$sql .= "           c.descripcion as descripcion2, ";
			$sql .= "           a.usuario_id ";
			$sql .= "FROM 	    userpermisos_AdminisFarmacia as a, ";
			$sql .= "           empresas as b, ";
			$sql .= "           centros_utilidad as c ";
			$sql .= "WHERE      a.usuario_id= ".UserGetUID()."  ";
			$sql .= "           AND 	a.empresa_id=b.empresa_id ";
			$sql .= "           AND 	a.centro_utilidad=c.centro_utilidad ";
			$sql .= "           AND 	a.empresa_id=c.empresa_id AND b.sw_activa='1' AND  b.sw_tipo_empresa='1' ";

			if(!$rst = $this->ConexionBaseDatos($sql))
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
	/*
		* Funcion donde se Consultan los centros de Utilidades de la farmacia.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	   	function ListarCentrodeUtilidad($empresa)
		{
		
			$sql  = "SELECT   	empresa_id, centro_utilidad,descripcion,Ubicacion";
			$sql .= "           From centros_utilidad  ";
			$sql .= "WHERE      empresa_id='".$empresa."'  ";
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
	/*
		* Funcion donde se obtienen las bodegas asociadas a la farmacia..
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ObtenerBodegaFarmacia($empresa,$centro)
		{
		
			$sql  = "SELECT   	";
			$sql .= "           ";
			$sql .= "           ";
			$sql .= "           ";
			$sql .= "           e.bodega,  ";
			$sql .= "           e.descripcion AS descripcion3   ";
			$sql .= "FROM 	    empresas AS b, ";
			$sql .= "           centros_utilidad AS c, ";
			$sql .= "           bodegas AS e ";
			$sql .= "WHERE     	e.empresa_id=c.empresa_id  ";
			$sql .= "           AND 	e.centro_utilidad=c.centro_utilidad ";
			$sql .= "           AND 	c.empresa_id=b.empresa_id ";
			$sql .= "           AND 	b.empresa_id='".$empresa."' AND  b.sw_tipo_empresa='1' AND  b.sw_activa='1' and e.centro_utilidad='".$centro."'";
			$sql .= "           ORDER BY e.descripcion; ";
			if(!$rst = $this->ConexionBaseDatos($sql))
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
	/*
		* Funcion donde se Consultan los centro de Utilidades de la farmacia que esta referenciados en la bodega.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/	
		function ObtenerBodegaCentroUtilidad($bodega)
		{
			
			$sql  = "SELECT   	";
			$sql .= "           ";
			$sql .= "           ";
			$sql .= "           e.centro_utilidad, ";
			$sql .= "           e.bodega,  ";
			$sql .= "           e.descripcion AS descripcion3   ";
			$sql .= "FROM 	    empresas AS b, ";
			$sql .= "           centros_utilidad AS c, ";
			$sql .= "           bodegas AS e ";
			$sql .= "WHERE     	e.empresa_id=c.empresa_id  ";
			$sql .= "           AND 	e.centro_utilidad=c.centro_utilidad ";
			$sql .= "           AND 	c.empresa_id=b.empresa_id ";
			$sql .= "           AND 	b.sw_tipo_empresa='1' and e.bodega='".$bodega."' ";
			$sql .= "           ORDER BY e.descripcion; ";
			if(!$rst = $this->ConexionBaseDatos($sql))
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
	/*
		* Funcion donde se consulta las empresas principales.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarPendientesPorConfirmar($farmacia)
		{
		
		 $sql = " 	SELECT Distinct i.empresa_id,e.razon_social
					FROM   inv_bodegas_movimiento_despachos_farmacias as i left join empresas e ON(i.empresa_id=e.empresa_id)
					where  i.farmacia_id = '".$farmacia."' AND i.sw_confirma = '0'; ";
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
		/*
		* Funcion donde se obtiene la el documento de acuerdo a la empresa seleccionado, al prefijo y numero.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ObtenerFiltrosDocDespacho($filtros,$offset,$empresas,$prefijo,$farmacia)
		{
		 
			
			$sql =" SELECT 	F.farmacia_id, 
							F.prefijo, 
							F.numero,
							E.razon_social,
							I.observacion,
							I.bodega,
							F.empresa_id,
							count(M.codigo_producto) as cantidad
					FROM   	inv_bodegas_movimiento_despachos_farmacias F,
							inv_bodegas_movimiento I,
							empresas E,
							inv_bodegas_movimiento_d M 
					where  	I.prefijo=F.prefijo 
					and 	I.numero=F.numero 
					and 	F.empresa_id=I.empresa_id 
					and 	E.empresa_id=I.empresa_id
					and     M.prefijo=F.prefijo 
					and 	M.numero=F.numero
					and 	M.empresa_id=E.empresa_id
					AND 	F.sw_confirma='0' 
					and 	F.farmacia_id='".$farmacia."' 
					AND     F.empresa_id='".$empresas."' ";
							
			if($prefijo!= "-1" )
			$sql.="  and  F.prefijo= '". $prefijo."'  ";
			
			if($filtros['numero']!=""){
			$sql.=" and F.numero= '".$filtros['numero']."' ";
			}
			
			$sql .= " GROUP BY  F.prefijo, F.numero,I.observacion,E.razon_social,F.farmacia_id,I.bodega,F.empresa_id ";
			$cont="select COUNT(*) from (".$sql.") AS A";
			$this->ProcesarSqlConteo($cont,$offset);
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
	/*
		** Funcion donde se consulta si hay productos pendientes.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

		function ConsultarExistencias($empresa,$prefijo,$numero,$farmacia)
		{
			
			$sql  = "SELECT   Pendiente_veri_id, empresa_id,prefijo,numero,farmacia_id,codigo_producto ";
			$sql .= "FROM  ProductosPendientesPorVerificar ";
			$sql .=  "where   empresa_id='".$empresa. "' and prefijo='".$prefijo. "' and numero='".$numero. "' and farmacia_id='".$farmacia. "' and  usuario_id= ".UserGetUID()."  ";

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
		
		
	/*
		* Funcion donde se obtiene la informacion general del documento consultado.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ObtenerInfMoviDetalle($prefijo,$numero,$empresa)
		{
			
			
			$sql = " SELECT F.farmacia_id, 
							F.prefijo,
							F.numero,
							N.bodega,
							N.observacion,
							N.abreviatura,
							M.movimiento_id,
							M.cantidad,
							M.porcentaje_gravamen,
							M.total_costo,
							M.existencia_bodega,
							M.existencia_inventario,
							M.costo_inventario,
							M.empresa_id,
							P.codigo_producto,
							P.descripcion,
							P.contenido_unidad_venta,
							S.descripcion as molecula,
							C.descripcion as laboratorio,
							M.fecha_vencimiento,
							M.lote,
							N.abreviatura,
							U.descripcion as unidad
					From 	inv_bodegas_movimiento_d M,
							existencias_bodegas E,
							inventarios I,
							inventarios_productos P, 
							inv_subclases_inventarios S,
							inv_clases_inventarios C,
							inv_bodegas_movimiento N,
							inv_bodegas_movimiento_despachos_farmacias F,
							Unidades as U
					where 	M.empresa_id=E.empresa_id 
					and 	M.codigo_producto=E.codigo_producto
					and 	E.empresa_id=I.empresa_id 
					and 	E.codigo_producto=I.codigo_producto
					and 	I.codigo_producto=P.codigo_producto
					and 	P.grupo_id=S.grupo_id 
					and 	P.clase_id=S.clase_id
					and 	P.subclase_id=S.subclase_id
					and 	S.grupo_id=C.grupo_id 
					and 	S.clase_id=C.clase_id 
					and     P.unidad_id=U.unidad_id
					and 	F.prefijo=N.prefijo 
					and 	F.numero=N.numero 
					and 	F.prefijo=M.prefijo 
					and 	F.numero=M.numero 
					AND 	M.prefijo='".$prefijo."'
					and 	M.numero='".$numero."' 
					and		M.empresa_id='".$empresa."'   ";
		
			
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
		/*
		** Funcion donde obtiene la cantidad de registros que arrojo la consulta.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function contarDatos($prefijo,$numero,$empresa)
		{
			
			$sql  = "SELECT   F.farmacia_id, F.prefijo, F.numero,N.bodega,N.observacion,M.movimiento_id,M.cantidad,M.empresa_id,P.codigo_producto,P.descripcion,Mo.molecula_id,Mo.descripcion as molecula,L.laboratorio_id,L.descripcion as laboratorio  ";
			$sql .= " From inv_bodegas_movimiento_d M,existencias_bodegas E, inventarios I,inventarios_productos P, inv_subclases_inventarios S,inv_moleculas Mo,inv_laboratorios L, inv_clases_inventarios C,inv_bodegas_movimiento N,inv_bodegas_movimiento_despachos_farmacias F  ";
			$sql .= " where M.empresa_id=E.empresa_id and M.codigo_producto=E.codigo_producto and E.empresa_id=I.empresa_id and E.codigo_producto=I.codigo_producto and I.codigo_producto=P.codigo_producto ";
			$sql .= " and P.grupo_id=S.grupo_id and P.clase_id=S.clase_id and P.subclase_id=S.subclase_id and S.molecula_id=Mo.molecula_id  and S.grupo_id=C.grupo_id and S.clase_id=C.clase_id ";
			$sql .= " and F.prefijo=N.prefijo and F.numero=N.numero and F.prefijo=M.prefijo and F.numero=M.numero ";
			$sql .= "  and C.laboratorio_id=L.laboratorio_id AND M.prefijo='".$prefijo."' and M.numero='".$numero."' and M.empresa_id='".$empresa."'  ";
			$consulta="SELECT COUNT(*)as c FROM(".$sql.") AS A ";

			if(!$rst = $this->ConexionBaseDatos($consulta))
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
	/*
		* Funcion donde se consulta si existe ya un registro en la tabla inv_bodegas_documentos.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Consultarinv_bodegas_documentos($IdIngreso,$farmacia,$centroU,$Bodega)
		{
			
			$sql  = " SELECT  documento_id,empresa_id,centro_utilidad,bodega,bodegas_doc_id    ";
			$sql .= " from  inv_bodegas_documentos  ";
			$sql .= " where documento_id=".trim($IdIngreso)." and empresa_id ='".trim($farmacia)."'  and centro_utilidad='".trim($centroU)."' and bodega='".trim($Bodega)."'; ";

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
		/*
		*Funcion donde se obtiene  el prefijo y la numeracion del documento de ingreso que se va a generar.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
    	 function SelecPrefijoNumerodocumentos($IdIngreso,$farmacia)
		{
		 
     		
			$sql  = " SELECT prefijo,numeracion,tipo_doc_general_id,descripcion FROM documentos ";
			$sql .= " where documento_id=".$IdIngreso." and empresa_id ='".$farmacia."' ";

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
		/*
		* Funcion donde se obtiene la informacion de los productos que se han ingresado en la tabla producto_verificados_tmp.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	  
		function TraerInformacionTemporal($prefijo,$numeracion)
		{
			
			$sql  = " SELECT  * ";
			$sql .= " from   producto_verificados_tmp where 	prefijo='".$prefijo."' 	and  numero='".$numeracion."' and usuario_id= ".UserGetUID()." ";
		
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
    
    /*
		* Funcion donde se obtiene la informacion de los productos que se han ingresado en la tabla producto_verificados_tmp.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	  
		function ConsultarExistenciasBodegas($farmacia_id,$centro_utilidad,$bodega,$codigo_producto)
		{
			$sql  = " SELECT  * ";
			$sql .= " from   existencias_bodegas ";
			$sql .= " where";
			$sql .= "         empresa_id = '".$farmacia_id."' ";
			$sql .= " AND     centro_utilidad = '".$centro_utilidad."' ";
			$sql .= " AND     bodega = '".$bodega."' ";
			$sql .= " AND     codigo_producto = '".$codigo_producto."'; ";
		
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
    
		/* Funcion que permite consultar la informacion temporal
		 * @return array $datos con la informacion de la consulta*/
		
		 function ConsultarTemporales($prefijo,$numeracion,$farmacia)
		{
			
			$sql = "  SELECT  p.pendiente_veri_id,
                        p.empresa_id,
                        p.prefijo,
                        p.numero,
                        p.farmacia_id, 
                        to_char(p.fecha_vencimiento,'dd-mm-YYYY') as fecha_vencimiento,
                        p.lote,
                        i.codigo_producto,
                        i.descripcion,
                        i.contenido_unidad_venta,
                        u.descripcion as unidad
                From    productospendientesporverificar p,
                        inventarios_productos i,
                        unidades u
                where   p.codigo_producto=i.codigo_producto
                and     i.unidad_id=u.unidad_id
          		  and     p.prefijo = '".$prefijo."'
                and     p.numero = '".$numeracion."' 
                and     p.farmacia_id = '".$farmacia."' 
                and     p.usuario_id= ".UserGetUID()."";
      
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
		/*
		* Funcion donde se genera parte del documento:
		  - se hace el ingreso a la tabla   inv_bodegas_movimiento_ingreso_farmacia donde se Guarda el Prefijo y el numero del nuevo documento de la Farmacia
		  - se hace el ingreso a la tabla  inv_bodegas_movimiento
		  - se actualiza la numeracion del documento en la tabla documentos
		* @return  boolean de acuerdo a la ejecucion del sql.
	*/
	  	 function GenerarDocumentoIngresoInventarioFarmacia($IdIngreso,$farmacia,$centroU,$Bodega,$prefijI,$numeracion,$observacion,$abrev_estado)
		{
		
		  
		 
		 	$sql  = " LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
			$sql .= "SELECT NEXTVAL('inv_bodegas_movimiento_ingreso_inv_bodegas_mto_ing_farmacia_seq') AS sq; ";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			if(!$rst->EOF)
			{
			$indice = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();     
			}
			$rst->Close(); 

			$sqlerror = "SELECT setval('inv_bodegas_movimiento_ingreso_inv_bodegas_mto_ing_farmacia_seq', ".($indice['sq']-1).") ";   
			
			$this->ConexionTransaccion();
			
			$sql .= "INSERT INTO inv_bodegas_movimiento_ingreso_farmacia
							(
								inv_bodegas_mto_ing_farmacia,
								farmacia_id,
								prefijo,
								numero,
								usuario_id,
								fecha_registro
							)
							VALUES
							(
								".$indice['sq'].",
								'".$farmacia."',
								'".$prefijI."',
								".$numeracion." + 1,
								".UserGetUID().",
								 NOW()
							); ";
			$sql .= "INSERT INTO inv_bodegas_movimiento
					(
						documento_id,
						empresa_id,
						centro_utilidad,
						bodega,
						prefijo,
						numero,
						observacion,
						sw_estado,
						usuario_id,
						fecha_registro,
						abreviatura ,
						empresa_destino
					)
					VALUES
					(
						".$IdIngreso.",
						'".$farmacia."',
						'".$centroU."',
						'".$Bodega."',
						'".$prefijI."',
						  ".$numeracion." + 1,
						'".$observacion."',
						'1',
						".UserGetUID().",
						 NOW(), ";
						 
				if($abrev_estado=='null')
				{
				   $sql .= " NULL, ";
				}
				else
                {				
						$sql .=" '".$abrev_estado."',";
						
				}
				$sql .= "  '".$farmacia."' 
				);";
							
			$sql .= "UPDATE documentos ";
			$sql .= "SET numeracion = numeracion + 1 ";
			$sql .= "WHERE documento_id =".$IdIngreso." AND empresa_id = '".$farmacia."' ;";
					
				if(!$rst = $this->ConexionTransaccion($sql))
							{
							if(!$rst = $this->ConexionTransaccion($sqlerror)) 
							return false;      
							}    
							else
							{
							$this->Commit();
							return true;
							}
		}
		/*
		* Funcion donde se genera parte  final del documento de ingreso:
		  - se hace el ingreso a la tabla   iinv_bodegas_movimiento_d
		* @return  boolean de acuerdo a la ejecucion del sql.
	*/
						
		function GenerarDoumentoInv_bodega_movimiento_id($farmacia,$centroU,$Bodega,$prefijI,$numeracion,$InfTem)
		{
		
		 	$sql  = " LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
			foreach($InfTem as $item=>$fila)
			{
					
				$this->ConexionTransaccion();
			
				$sql .= "INSERT INTO inv_bodegas_movimiento_d
						(
							movimiento_id,	
							empresa_id,
							prefijo,
							numero,
							centro_utilidad,
							bodega,
							codigo_producto,
							cantidad,
							porcentaje_gravamen,
							total_costo,
							existencia_bodega,
							existencia_inventario,
							costo_inventario,
							fecha_vencimiento,
							lote
						)
						VALUES
						(
							NEXTVAL('inv_bodegas_movimiento_d_movimiento_id_seq'),
							 '".$farmacia."',
							'".$prefijI."',
							  ".$numeracion." + 1,
							'".$centroU."',
							 '".$Bodega."',
							'".$fila['codigo_producto']."',
							".$fila['cantidad'].",
							".$fila['porcentaje_gravamen'].",
							".$fila['total_costo'].",
							".$fila['existencia_bodega'].",
							".$fila['existencia_inventario'].",
							".$fila['costo_inventario'].",
							'".$fila['fecha_vencimiento']."',
							'".$fila['lote']."'
							
						);
					
					";
			}	
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
			
		}
		/* Funcion que permite actualizar si se ha confirmado por completo los productos del documento de despacho
		*@return  array $datos con la informacion 
		*/
		function ActualizarInformacion($prefijo,$numeracion,$farmacia)
		{
			  
			$sql= " update inv_bodegas_movimiento_despachos_farmacias set sw_confirma='1' where farmacia_id='".$farmacia."' and prefijo='".$prefijo."' and numero='".$numeracion."' ";
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
	/*
		* Funcion donde se obtiene el numero del documento de ingreso generado.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function MostrarNumeracionDocumento($IdIngreso,$farmacia,$centroU,$Bodega,$prefijI)
		{
		
	
			$sql  = " SELECT COALESCE(MAX(numero),0) AS movimiento ";
			$sql  .= " FROM inv_bodegas_movimiento ";
			$sql .= " where  documento_id=".$IdIngreso." and empresa_id ='".$farmacia."' and  centro_utilidad='".$centroU."'  and  bodega='".$Bodega."' and prefijo='".$prefijI."' ";

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
       /*
		* Funcion donde se inserta  a la tabla inv_bodegas_documentos en el caso de que la consulta anterior no arrojara ningun registro.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
			
		function Ingresoinv_bodegas_documentos($IdIngreso,$farmacia,$centroU,$Bodega)
		{
		
			 $indice = array();

		  $sql = "SELECT NEXTVAL('inv_bodegas_documentos_bodegas_doc_id_seq') AS sq ";

		  if(!$rst = $this->ConexionBaseDatos($sql)) 
		  return false;
		  if(!$rst->EOF)
		  {
		  $indice = $rst->GetRowAssoc($ToUpper = false);
		  $rst->MoveNext();     
		  }
		  $rst->Close(); 

		  $sqlerror = "SELECT setval('inv_bodegas_documentos_bodegas_doc_id_seq', ".($indice['sq']-1).") ";    
		  $this->ConexionTransaccion();
		  $sql = "INSERT INTO inv_bodegas_documentos
					(
						documento_id,
						empresa_id,
						centro_utilidad,
						bodega,
						bodegas_doc_id,
						sw_estado
					)
					VALUES
					(
						".$IdIngreso.",
						'".$farmacia."',
						'".$centroU."',
						'".$Bodega."',
						".$indice['sq'].",
					   '1'
					 ); ";

			  if(!$rst = $this->ConexionTransaccion($sql))
			  {
			  if(!$rst = $this->ConexionTransaccion($sqlerror)) 
			  return false;      
			  }    
			  else
			  {
			  $this->Commit();
			  return true;
			  }
		}
	/*
		* Funcion donde obtiene  los prefijos de los documentos que tiene la empresa.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function  ObtenerDocumentoFarmacia($empresa)  
		{
			
			$sql  = "SELECT    DISTINCT  F.prefijo ";
			$sql .= "FROM     inv_bodegas_movimiento I, inv_bodegas_movimiento_despachos_farmacias F, empresas E ";
			$sql .=  "where   I.prefijo=F.prefijo and I.numero=F.numero and E.empresa_id=I.empresa_id and  E.empresa_id='".$empresa."'";

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
		/*
		* Funcion  donde se ingresa temporamente los productos que  estan pendientes por ser verificados.
		* @return boolean de acuerdo a la ejecucion del sql.
		
	*/
		function IngresarTemporalmenteProducP($codigo_producto,$empresa,$prefijo,$numero,$farmacia,$fechav,$lot)
		{
  
			$this->ConexionTransaccion();
			$id=$codigo_producto."".$fechav."".$lot;
			$sql  = "INSERT INTO productospendientesporverificar( ";
			$sql .= "       pendiente_veri_id,";
			$sql .= "       empresa_id, ";
			$sql .= "       prefijo, ";
			$sql .= "       numero, ";
			$sql .= "       farmacia_id, ";
			$sql .= "       codigo_producto, ";
      $sql .= "       fecha_vencimiento, ";
      $sql .= "       lote," ;
			$sql .= "       usuario_id, ";
			$sql .= "       fecha_registro  ";
			$sql .= ")VALUES( ";
			$sql .= "       '".$id."', ";
			$sql .= "       '".$empresa."', ";
			$sql .= "       '".$prefijo."', ";
			$sql .= "       ".$numero.", ";
			$sql .= "       '".$farmacia."', ";
			$sql .= "       '".$codigo_producto."', ";
      $sql .= "       '".$fechav."', ";
      $sql .= "       '".$lot."', ";      
			$sql .= "          ".UserGetUID().",";
			$sql .= "         NOW() ";
			$sql .= "       ); ";

			if(!$rst = $this->ConexionTransaccion($sql))
			{
			return false;
			}
			$this->Commit();
			return true;
		}
		/*
		** Funcion donde se consulta los productos que se encuentran ya verificados y listos para generar el documento de ingreso.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarTemporal($empresa,$prefijo,$numero,$farmacia)
		{
		

		$sql = "  SELECT  p.pendiente_veri_id,
                        p.empresa_id,
                        p.prefijo,
                        p.numero,
                        p.farmacia_id, 
                        to_char(p.fecha_vencimiento,'dd-mm-YYYY') as fecha_vencimiento,
                        p.lote,
                        i.codigo_producto,
                        i.descripcion,
                        i.contenido_unidad_venta,
                        u.descripcion as unidad
                From    productospendientesporverificar p,
                        inventarios_productos i,
                        unidades u
                where   p.codigo_producto=i.codigo_producto
                and     i.unidad_id=u.unidad_id
          		  and     p.prefijo = '".$prefijo."'
                and     p.numero = '".$numero."' 
                and     p.farmacia_id = '".$farmacia."' 
                and     p.usuario_id= ".UserGetUID()."";
      
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
		/*	
		* Funcion donde se consulta la informacion general del documento de despacho que tiene productos pendientes.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

		function DetalleProductosPendientes($prefijo,$numero,$empresa)
		{
	
			
      $sql .= "  SELECT  M.movimiento_id,
                          M.cantidad,
                          M.empresa_id,
                          M.codigo_producto,
                          M.fecha_vencimiento,
                          M.lote,
                          M.bodega,
                          M.porcentaje_gravamen,
                          M.total_costo,
                          M.existencia_bodega,
                          M.existencia_inventario,
                          M.costo_inventario,
                          F.farmacia_id,
                          F.prefijo, 
                          F.numero,
                          P.descripcion,
                          P.contenido_unidad_venta,
                          S.descripcion as molecula, 
                          C.descripcion as laboratorio, 
                          U.descripcion as unidad,
                          M.codigo_producto||''||M.fecha_vencimiento||''||M.lote as producto
                  FROM    inv_bodegas_movimiento_d M,
                          inv_bodegas_movimiento  N,
                          inv_bodegas_movimiento_despachos_farmacias F,
                          existencias_bodegas E,
                          inventarios   I,
                          inv_subclases_inventarios S, 
                          inv_clases_inventarios C, 
                          unidades U, 
                          inventarios_productos P
                  
                  WHERE   N.empresa_id=M.empresa_id
                  And     N.prefijo=M.prefijo
                  And     N.numero=M.numero
                  And     N.empresa_id=F.empresa_id
                  And     N.prefijo=F.prefijo
                  And     N.numero=F.numero
                  And     M.empresa_id=E.empresa_id
                  And     M.centro_utilidad=E.centro_utilidad
                  And     M.bodega=E.bodega
                  And     M.codigo_producto=E.codigo_producto
                  And     E.empresa_id=I.empresa_id
                  And     E.codigo_producto=I.codigo_producto
                  And     I.codigo_producto=P.codigo_producto
                  And     P.grupo_id=S.grupo_id 
                  And     P.clase_id=S.clase_id 
                  And     P.subclase_id=S.subclase_id 
                  And     S.grupo_id=C.grupo_id 
                  And     S.clase_id=C.clase_id 
                  And     P.unidad_id=U.unidad_id 
                  And     M.prefijo='".$prefijo."'
                  And     M.numero='".$numero."'
                  And     M.empresa_id='".$empresa."'
                  And      M.codigo_producto||''||M.fecha_vencimiento||''||M.lote in( Select PE.codigo_producto||''||PE.fecha_vencimiento||''||PE.lote AS PRODUCTO
                                                                                      from   productospendientesporverificar PE
                                                                                      where  PE.prefijo='".$prefijo."'
                                                                                      And    PE.numero='".$numero."'
                                                                                      And    PE.empresa_id='".$empresa."') ;"; 
          

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
		/*
		* Funcion que permite contar cuantos registro arrojo la consulta
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function contarDatos2($prefijo,$numero,$empresa)
		{
		 
          $sql .= "  SELECT  M.movimiento_id,
                          M.cantidad,
                          M.empresa_id,
                          M.codigo_producto,
                          M.fecha_vencimiento,
                          M.lote,
                          M.bodega,
                          F.farmacia_id,
                          F.prefijo, 
                          F.numero,
                          P.descripcion,
                          P.contenido_unidad_venta,
                          S.descripcion as molecula, 
                          C.descripcion as laboratorio, 
                          U.descripcion as unidad,
                          M.codigo_producto||''||M.fecha_vencimiento||''||M.lote as producto
                  FROM    inv_bodegas_movimiento_d M,
                          inv_bodegas_movimiento  N,
                          inv_bodegas_movimiento_despachos_farmacias F,
                          existencias_bodegas E,
                          inventarios   I,
                          inv_subclases_inventarios S, 
                          inv_clases_inventarios C, 
                          unidades U, 
                          inventarios_productos P
                  
                  WHERE   N.empresa_id=M.empresa_id
                  And     N.prefijo=M.prefijo
                  And     N.numero=M.numero
                  And     N.empresa_id=F.empresa_id
                  And     N.prefijo=F.prefijo
                  And     N.numero=F.numero
                  And     M.empresa_id=E.empresa_id
                  And     M.centro_utilidad=E.centro_utilidad
                  And     M.bodega=E.bodega
                  And     M.codigo_producto=E.codigo_producto
                  And     E.empresa_id=I.empresa_id
                  And     E.codigo_producto=I.codigo_producto
                  And     I.codigo_producto=P.codigo_producto
                  And     P.grupo_id=S.grupo_id 
                  And     P.clase_id=S.clase_id 
                  And     P.subclase_id=S.subclase_id 
                  And     S.grupo_id=C.grupo_id 
                  And     S.clase_id=C.clase_id 
                  And     P.unidad_id=U.unidad_id 
                  And     M.prefijo='".$prefijo."'
                  And     M.numero='".$numero."'
                  And     M.empresa_id='".$empresa."'
                  And      M.codigo_producto||''||M.fecha_vencimiento||''||M.lote in( Select PE.codigo_producto||''||PE.fecha_vencimiento||''||PE.lote AS PRODUCTO
                                                                                      from   productospendientesporverificar PE
                                                                                      where  PE.prefijo='".$prefijo."'
                                                                                      And    PE.numero='".$numero."'
                                                                                      And    PE.empresa_id='".$empresa."' )  "; 
              
			$consulta="SELECT COUNT(*)as c FROM(".$sql.") AS A ";

			if(!$rst = $this->ConexionBaseDatos($consulta))
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
		/*
		** Funcion donde se Elimina los productos que ya no son pendientes
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		 function EliminarProductosPend($prefijo,$numero,$empresa,$farmacia)
		 {
	   
			$sql = " delete from productospendientesporverificar ";
			$sql .= " where prefijo='".$prefijo."' and  empresa_id='".$empresa."' and numero='".$numero."' and farmacia_id='".$farmacia."' and usuario_id= ".UserGetUID()." ; ";

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
		/*
		** Funcion donde se Elimina todos los productos que se han ingresado en la tabla temporal de los productos verificados.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function EliminarTemporalProductosVerif($farmacia_id,$prefijo,$numero)
		{
		
					
			$sql  = " delete from producto_verificados_tmp
                      where  farmacia_id='".$farmacia_id."'	
					  and  prefijo='".$prefijo."'
					  and   numero='".$numero."' and usuario_id= ".UserGetUID()."  ; ";
		
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
		/* Funcion que me permite listar las empresas que no son farmacias*/
		function ListarEmpresas()
		{
				
		 
      		$sql =" SELECT      b.empresa_id,
								b.razon_social        
					FROM 	    empresas AS b 
					WHERE    	  b.sw_activa='1' 
				    ORDER BY b.razon_social;  ";
				
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
		/* Funcion que me permite consultar la Existencia del documento
		@return  array $datos con  la informacion de la consulta*/
		
		function TiposDocumentosExistentes($empresa,$centro,$bodega)
		{
		
			$sql = "  SELECT
		                    c.inv_tipo_movimiento as tipo_movimiento,
		                    a.bodegas_doc_id,
		                    b.tipo_doc_general_id as tipo_doc_bodega_id,
		                    c.descripcion as tipo_clase_documento,
		                    b.prefijo,
		                    b.descripcion,
							a.documento_id
		                FROM
		                    (
		                        SELECT
		                            documento_id
		                        FROM
		                            inv_bodegas_userpermisos
		                        WHERE
		                            usuario_id= ".UserGetUID()."
		                           and  empresa_id = '".$empresa."'
		                            AND centro_utilidad = '".$centro." '
		                            AND bodega = '".$bodega."'
		                    ) AS u,
		                    inv_bodegas_documentos as a,
		                    documentos as b,
		                    tipos_doc_generales as c
		                WHERE
		                a.documento_id = u.documento_id
		                AND a.empresa_id =  '".$empresa."'
		                AND a.centro_utilidad = '".$centro." '
		                AND a.bodega = '".$bodega."'
		                AND b.documento_id = a.documento_id
		                AND b.empresa_id = a.empresa_id
		                AND c.tipo_doc_general_id = b.tipo_doc_general_id
						AND c.inv_tipo_movimiento='E'
		                ORDER BY tipo_movimiento, tipo_doc_bodega_id ";
				
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
	/*
		*** Funcion que permite seleccionar el Maximo de id del documento de la tabla inv_bodegas_movimiento_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function SelecMaxdoc_tmp_id()
		{
		 
			$sql = "SELECT (COALESCE(MAX(doc_tmp_id),0) + 1) AS numero FROM inv_bodegas_movimiento_tmp where usuario_id= ".UserGetUID().";	";
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
		/* Function Donde se consulta los estados del documento que este en sw_verificados=1
		@ return array $datos que contiene la informacion de la consulta*/
	
		function C_documentog($tipo_doc_general_id)
		{
		 
          $sql ="	SELECT id_para_documentosg,
                          tipo_doc_general_id,
                          abreviatura,
                          doc_tmp_id,
                          sw_verifico
                          FROM     para_documentosg
                          WHERE   tipo_doc_general_id='".$tipo_doc_general_id."' 
                          AND    sw_verifico=1; ";

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
		/* Function Donde se consulta los tipos de estado del documento*/ 
		function Documentog($tipo_doc_general_id)
		{
	    
		   $sql ="	SELECT id_para_documentosg,
		                   tipo_doc_general_id,
						   abreviatura,
						   doc_tmp_id,
						   sw_verifico
				   FROM     para_documentosg
				   WHERE   tipo_doc_general_id='".$tipo_doc_general_id."';"; 
                 
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
	/*
		*** Funcion que permite seleccionar el Maximo de id del documento de la tabla inv_bodegas_movimiento_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		 function EstadosParamestadosdocum($tipo_doc_general_id,$empresa_id)
			{
	
				$sql = "SELECT  p.abreviatura,
                        i.descripcion
                  FROM  paramestadosdocum p,
                        inv_estados_documentos i 
                  WHERE p.abreviatura=i.abreviatura 
                  and   p.tipo_doc_general_id= '".$tipo_doc_general_id."' 
                  AND   p.empresa_id='".$empresa_id."'; ";
					
					
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
	/* Function que permite Ingresar los estados del documento
       @return boolean*/ 	
		function Insertar_Estados_para_documentosg($Estados,$doc_tmp_id)
		{
		
			foreach($Estados as $item=>$fila)
			{
						
				$this->ConexionTransaccion();
			
				$sql .= "INSERT INTO para_documentosg
							(
								id_para_documentosg,	
								tipo_doc_general_id,
								abreviatura,
								doc_tmp_id,
								usuario_id,
								fecha_registro,
								sw_verifico
							)
							VALUES
							(
								NEXTVAL('para_documentosg_id_para_documentosg_seq'),
								'".$fila['tipo_doc_general_id']."',
								'".$fila['abreviatura']."',
								".$doc_tmp_id.",
								".UserGetUID().",
							    NOW(),
								0
							);
						";
			}	
							
			if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
		}
		/* Function Donde se consulta los estados del documento
		@return array $datos conla informacion de la consulta*/
		function Consultarpara_documentosg($tipo_doc_general_id,$doc_tmp_id)
		{
	    
		    $sql ="	SELECT  a.abreviatura,
                        a.sw_verifico,
                        b.descripcion 
                FROM    para_documentosg a,
                        inv_estados_documentos b 
                WHERE   a.tipo_doc_general_id='".$tipo_doc_general_id."' 
                AND     a.doc_tmp_id=".$doc_tmp_id."   
                AND     a.sw_verifico=0
                AND     a.abreviatura=b.abreviatura; ";
		
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
		/*
		* Funcion donde se genera el documento de devolucion temporal en la tabla inv_bodegas_movimiento_tmp
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/
		function GrabarDocinv_bodegas_movimiento_tmp($empresa_destino,$bodegas_doc_id,$observacion,$doc_tmp_id,$abreviatura)
		{
		
			
			$this->ConexionTransaccion();
    
			if($abreviatura=="")
			{
			   $abreviatura=NULL;
			}
			 
			$sql = "INSERT INTO inv_bodegas_movimiento_tmp
							(
								usuario_id,
								doc_tmp_id,
								bodegas_doc_id,
								observacion,
								fecha_registro,
								abreviatura,
                empresa_destino								
                
							
							)
							VALUES
							(
								".UserGetUID().",
								".$doc_tmp_id.",
								".$bodegas_doc_id.",
								'".$observacion."',
								 NOW(),
								 '".$abreviatura."',
								 '".$empresa_destino."'
                
								
							); ";
			      	if(!$rst = $this->ConexionTransaccion($sql))
							{
							if(!$rst = $this->ConexionTransaccion($sqlerror)) 
							return false;      
							}    
							else
							{
							$this->Commit();
							return true;
							}
		}
	/* Function que permite Eliminar los estados ya verificados
	@return array con los datos de la consulta*/
		function Eliminar_AbrevParaDocumeng($tipo_doc_general_id,$doc_tmp_id,$abreviatura)
		{
		
          $sql = " DELETE   FROM  para_documentosg
                    where	          tipo_doc_general_id='".$tipo_doc_general_id."'	
                    and             doc_tmp_id=".$doc_tmp_id."	
                    and             abreviatura='".$abreviatura."' ";

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
		/*
		* Funcion donde se Listan o se lista el producto buscado 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
     function BuscaroListarProductoBodega($farmacia,$centroutil,$bodega,$filtros,$offset)
		{
	  
			$sql  = " SELECT X.empresa_id,
                            X.centro_utilidad,
                            X.codigo_producto,
                            X.bodega,
                            X.fecha_vencimiento,
                            X.lote,
                            X.existencia_inicial,
                            X.existencia_actual as actual,
                            p.descripcion,
                            p.codigo_alterno,
                            p.codigo_barras,
                            p.cantidad as presentacion,
                            p.subclase_id,
							p.contenido_unidad_venta,
                            s.descripcion as molecula,
                            c.descripcion as laboratorio,
                            u.unidad_id,
                            u.abreviatura AS unidad,
                            I.costo_ultima_compra,
                            a.existencia,
                            I.precio_venta,
                            t.tipo_producto_id,
                            t.descripcion as  tipopro
							
							
									
		FROM        existencias_bodegas_lote_fv X,
                existencias_bodegas a,
                inventarios I,
                inventarios_productos p,
                inv_subclases_inventarios s,
                inv_clases_inventarios  c,
                unidades u,
                inv_tipo_producto t
		WHERE       X.empresa_id=a.empresa_id
		AND         X.centro_utilidad= a.centro_utilidad
		AND         X.bodega=a.bodega
		AND         X.codigo_producto=a.codigo_producto
		AND         a.empresa_id=I.empresa_id
        AND         a.codigo_producto=I.codigo_producto
		AND         I.codigo_producto=p.codigo_producto
        AND         p.tipo_producto_id=t.tipo_producto_id
	    AND			p.grupo_id=s.grupo_id 
		AND 		p.clase_id=s.clase_id 
		AND 		p.subclase_id=s.subclase_id 
		AND			s.grupo_id=c.grupo_id 
		AND 		s.clase_id=c.clase_id 
		AND 		p.unidad_id=u.unidad_id
        AND        X.existencia_actual >0
		AND         X.empresa_id='".$farmacia."'
		AND         X.centro_utilidad='".$centroutil."'
		AND         X.bodega='".$bodega."' ";
		
			if($filtros['codigo_producto'])
			{
			$sql.=" and p.codigo_producto= '".$filtros['codigo_producto']."' ";
			}
			if($filtros['codigo_alterno'])
			$sql.=" and p.codigo_alterno= '".$filtros['codigo_alterno']."' ";
			
			if($filtros['codigo_barras'] != "")
			$sql .= "AND     p.codigo_barras  ILIKE '%".$filtros['codigo_barras']."%' ";
			
			if($filtros['descripcion'] != "")
			$sql .= "AND    p.descripcion  ILIKE '%".$filtros['descripcion']."%' ";
			$cont="select COUNT(*) from (".$sql.") AS A";
			$this->ProcesarSqlConteo($cont,$offset);
		
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/*
		* Funcion que permite buscar los datos de la tabla temporal 
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
	
		function BuscarTemporalDevoluc($farmacia,$centro_utilidad,$bodega)
		{
	   

        $sql = " SELECT farmacia_id,centro_utilidad,bodega,codigo_producto ";
        $sql .= " FROM  doc_devolucion_tmp ";
        $sql .= " where farmacia_id='".$farmacia."' ";
        $sql .= "  and 	centro_utilidad= '".$centro_utilidad."' ";
        $sql .= "  and  bodega='".$bodega."' and usuario_id= ".UserGetUID()." 	";
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
		/*
		* Funcion que permite consultar la informacion sobre los productos seleccionados
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function ConsultarInformacionDoc_devolucion_tmp($farmacia,$centro,$bodega)
		{
	
		
			
			$sql = " SELECT * ";
			$sql .= " from doc_devolucion_tmp
                      where farmacia_id='".$farmacia."'
                      and   centro_utilidad='".$centro."'
                      and   bodega='".$bodega."'	  ";
			
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
		/*
		** Funcion donde se genera el documento de devolucion temporal en la tabla inv_bodegas_movimiento_tmp_d
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/
		function GenerarDocumentoinv_bodegas_movimiento_tmp_d($farmacia,$centroU,$Bodega,$Doc_tmp_id,$InfTem)
		{
		
		
			foreach($InfTem as $item=>$fila)
			{
						
				$this->ConexionTransaccion();
				
				$sql .= "INSERT INTO inv_bodegas_movimiento_tmp_d
							(
								item_id,	
								usuario_id,
								doc_tmp_id,
								empresa_id,
								centro_utilidad,
								bodega,
								codigo_producto,
								cantidad,
								porcentaje_gravamen,
								total_costo,
								fecha_vencimiento,
								lote
							)
							VALUES
							(
								NEXTVAL('inv_bodegas_movimiento_tmp_d_item_id_seq'),
								 ".UserGetUID().",
								".$Doc_tmp_id.",
								  '".$farmacia."',
								'".$centroU."',
								 '".$Bodega."',
								'".$fila['codigo_producto']."',
								".$fila['cantidad'].",
								0,
								".$fila['total_costo'].",
								'".$fila['fecha_vencimiento']."',
								'".$fila['lote']."'
							);
						";
			}	
							
			if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
			return true;
			
		}
	/*
		** Funcion que permite eliminar datos y asu vez actualizar
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Eliminarv_bodegas_movimiento_tmp($far,$Centrid,$bod)
		{
	
			
			$sql = " Delete from doc_devolucion_tmp ";
			$sql .= "where  	farmacia_id='".$far."'   and centro_utilidad='".$Centrid."' and bodega ='".$bod."' ;  ";
	      
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
	/* Funcion que permite consultar la informacion de los productos asociados al documento temporar 
	@return array $datos con la informacion d ela consulta */
		function ConsularInformaciontmpC($itmid)
		{
	
		  $sql = " SELECT 	d.item_id,
							d.doc_tmp_id,
							d.empresa_id,
							d.centro_utilidad,
							d.bodega,
							d.codigo_producto,
							d.cantidad,
							d.porcentaje_gravamen,
							d.total_costo,
							d.fecha_vencimiento,
							d.lote,
                            i.precio_venta,
							i.costo_ultima_compra,
							i.existencia as existencia_inventario,
							i.costo as costo_inventario,
							p.descripcion,
							s.descripcion as molecula,
							c.descripcion as laboratorio,
							x.existencia as existencia_bodega,
							u.descripcion as unidad,
							p.contenido_unidad_venta
							
					FROM 	inv_bodegas_movimiento_tmp_d d,
							existencias_bodegas x,
							inventarios i,
							inventarios_productos p,
							inv_subclases_inventarios s,
							inv_clases_inventarios c,
							inv_grupos_inventarios g,
							unidades u
					WHERE 	d.empresa_id=x.empresa_id
					AND     d.centro_utilidad=x.centro_utilidad
					AND     d.bodega=x.bodega
					AND     d.codigo_producto=x.codigo_producto
					AND     x.empresa_id=i.empresa_id
					AND     x.codigo_producto=i.codigo_producto
					AND     i.codigo_producto=p.codigo_producto
					AND     p.unidad_id=u.unidad_id
					AND     p.grupo_id=s.grupo_id
					AND     p.clase_id=s.clase_id
					AND     p.subclase_id=s.subclase_id
					AND     s.grupo_id=c.grupo_id
					AND     s.clase_id =c.clase_id
					AND     c.grupo_id=g.grupo_id
					AND     d.doc_tmp_id = ".$itmid."
          AND     d.usuario_id = ".UserGetUID()." ";
					
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
    /* Function que me permite eeliminar todo lo que contiene el Temporal seleccionado
        @return array $datos con la informacion */	
		function EliminartmpCompleto($docid)
		{
			
		      $sql = " delete  from inv_bodegas_movimiento_tmp 
		         where   doc_tmp_id= '".$docid."';
				 "; 
				 
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
	/* Funcion que permite eliminar la Abreviatura de los estados una vez el documento temporal sea eliminado 
	@return array $datos con la informacion */
		
		function Eliminar_AbreveParaDocumeng($tipo_doc_general_id,$doc_tmp_id)
		{
	   
			$sql = " delete  from para_documentosg
                    where	 tipo_doc_general_id='".$tipo_doc_general_id."'	
                    and      doc_tmp_id=".$doc_tmp_id."	 ";
					
                    
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
	/* Function que me permite Actualizar los estados del documento 
	@return  boolean 
	*/
		function ActuEstadotmp($abreviatura,$doc_tmp_id,$tipo_doc_general_id)
		{
		
			$sql = " UPDATE inv_bodegas_movimiento_tmp
			SET    abreviatura='".$abreviatura."'              
			WHERE  usuario_id=".UserGetUID()."
			AND    doc_tmp_id ='".$doc_tmp_id."';
			";

			$sql .= " delete  from para_documentosg
                    where	 tipo_doc_general_id='".$tipo_doc_general_id."'	
                    and      doc_tmp_id=".$doc_tmp_id."	
					and      abreviatura='".$abreviatura."' ";

		
		if(!$resultado = $this->ConexionBaseDatos($sql))
		{
		$cad="Operacion Invalida";
		return false;
		} 

		return true;
		}
	/*
		* Funcion que permite consular los datos de la tabla temporal de movimiento de bodega
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function  ConsultarInformacionBodegaTmp($bodegas_doc_id,$doc_tmp_id)
		{
	

			$sql = " SELECT usuario_id, ";
			$sql .= " doc_tmp_id, ";
			$sql .= " bodegas_doc_id, ";
			$sql .= " observacion, ";
			$sql .= " fecha_registro, ";
			$sql .= " abreviatura, empresa_destino ";
			$sql .= " from inv_bodegas_movimiento_tmp ";
			$sql .= " where bodegas_doc_id='".$bodegas_doc_id."' ";
			$sql  .= "      and  doc_tmp_id='".$doc_tmp_id."' "; 

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
		/*
		*  Funcion donde se genera el documento de devolucion real una vez verificados todos sus estados
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/
		function GenerarDocumentoDevolucionReal($idDocDevol,$farmacia,$centroU,$Bodega,$prefijI,$numeracion,$observacion,$abrev_estado,$empresa_destino)
		{
			//$this->=true;
		 	$this->ConexionTransaccion();
			
			IF($abrev_estado=="")
			
			{
			  $abrev_estado==NULL;
			}
			
			$sql  = " LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
		
			$sql .= "INSERT INTO inv_bodegas_movimiento
					(
						documento_id,
						empresa_id,
						centro_utilidad,
						bodega,
						prefijo,
						numero,
						observacion,
						sw_estado,
						usuario_id,
						fecha_registro,
						abreviatura,
						empresa_destino
					)
					VALUES
					(
						".$idDocDevol.",
						'".$farmacia."',
						'".$centroU."',
						'".$Bodega."',
						'".$prefijI."',
						  ".$numeracion." + 1,
						'".$observacion."',
						'1',
						".UserGetUID().",
						 NOW(),
						'".$abrev_estado."',
						'".$empresa_destino."'
					);";
							
			$sql .= "UPDATE documentos ";
			$sql .= "SET numeracion = numeracion + 1 ";
			$sql .= "WHERE documento_id =".$idDocDevol." AND empresa_id = '".$farmacia."' ;";
					
				if(!$rst = $this->ConexionTransaccion($sql))
							{
							if(!$rst = $this->ConexionTransaccion($sqlerror)) 
							return false;      
							}    
							else
							{
							$this->Commit();
							return true;
							}
			return $datos;
		}
		/*
		**  Funcion donde se genera el detalle documento de devolucion real una vez verificados todos sus estados
		*  @return  boolean de acuerdo a la ejecucion del sql.
	*/
		function GenerarDocInv_bodegas_movimiento_d($farmacia,$prefijI,$numeracion,$centroU,$Bodega,$InfTem)
		{
			
		 	$sql  = " LOCK TABLE documentos IN ROW EXCLUSIVE MODE; ";
			$this->ConexionTransaccion();
			foreach($InfTem as $item=>$fila)
			{
				$sql .= "INSERT INTO inv_bodegas_movimiento_d
							(
								movimiento_id,	
								empresa_id,
								prefijo,
								numero,
								centro_utilidad,
								bodega,
								codigo_producto,
								cantidad,
								porcentaje_gravamen,
								total_costo,
								existencia_bodega,
								existencia_inventario,
								costo_inventario,
								fecha_vencimiento,
								lote
							)
							VALUES
							(
								NEXTVAL('inv_bodegas_movimiento_d_movimiento_id_seq'),
								 '".$farmacia."',
								'".$prefijI."',
								  ".$numeracion." + 1,
								'".$centroU."',
								 '".$Bodega."',
								'".$fila['codigo_producto']."',
								".$fila['cantidad'].",
								".$fila['porcentaje_gravamen'].",
								".$fila['precio_venta'].",
								".$fila['existencia_bodega'].",
								".$fila['existencia_inventario'].",
								".$fila['costo_inventario'].",
								'".$fila['fecha_vencimiento']."',
								'".$fila['lote']."'
								
							);
						
						";
						
						$sql .= " 	update  devolucion_rotacion_farmacia 
						            set     cantidad_dev=COALESCE(cantidad_dev,0) +".$fila['cantidad']."
								    WHERE 	empresa_id = '".$farmacia."'
									AND 	centro_utilidad = '".$centroU."'
									AND 	bodega = '".$Bodega."' 
									AND     codigo_producto='".$fila['codigo_producto']."'
									AND     COALESCE(cantidad_dev,0) < cantidad;  ";
						
						$sql .= " 	update  devolucion_rotacion_farmacia 
						            set     sw_devuelto='1'
								    WHERE 	empresa_id = '".$farmacia."'
									AND 	centro_utilidad = '".$centroU."'
									AND 	bodega = '".$Bodega."' 
									AND     codigo_producto='".$fila['codigo_producto']."'
									AND     COALESCE(cantidad_dev,0) >= cantidad;  ";
						
						
				}	
			
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				
				
				
				$this->Commit();
				return true;
			
		}
		/*
		** Funcion que permite eliminar los datos de la tabla bodegas_movimiento_tmp
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Delete_bodegas_movimiento_tmp($bodegas_doc_id,$doc_tmp_id)
		{
			//$this->=true;
			$sql.= " Delete from  inv_bodegas_movimiento_tmp ";
			$sql .= " where  	doc_tmp_id 	='".$doc_tmp_id 	."'   and bodegas_doc_id='".$bodegas_doc_id."';  ";
		    
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
	/*
		*
		** Funcion que permite eliminar los datos de la tabla bodegas_movimiento_tmp_d
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function Delete_bodegas_movimiento_tmp_d($doc_tmp_id,$farmacia,$Centrid,$bod)
		{
			//$this->=true;
			
			$sql = " Delete from inv_bodegas_movimiento_tmp_d ";
			$sql .= "where  	doc_tmp_id='".$doc_tmp_id."'  and empresa_id='".$farmacia."'  and centro_utilidad='".$Centrid."' and bodega ='".$bod."' ;  ";
		  	
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
		
		/*
		* Funcion donde se Elimina los productos temporales
		* @return array $datos vector que contiene la informacion de la consulta.
	*/

		function Eliminar_ProducDevolucion_temporales($farmacia,$centroU,$Bodega,$codigo_producto,$fecha,$lote)
		{
	     

			$sql = " delete from Doc_Devolucion_tmp ";
			$sql .= " where farmacia_id='".$farmacia."'
                      and   centro_utilidad='".$centroU."'
                      and   bodega='".$Bodega."'
                      and  codigo_producto='".$codigo_producto."'
                      and  fecha_vencimiento= '".$fecha."'      
                      and  lote= '".$lote."' ";

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
		/*
		* Funcion que permite consultar y listar las farmacias o bodegas que se tienen el producto buscado
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function BuscarProducPorFarmacia($bod,$filtros,$offset)
		{
	        //$this->=true;
			$sql  = "Select	 e.empresa_id, ";
			$sql .= "		 e.razon_social,  ";
			$sql .= " 		 c.centro_utilidad, ";
			$sql .= "		 c.descripcion as centro, ";
			$sql .= " 		 b.bodega, ";
			$sql .= " 		 b.descripcion as bodega, ";
			$sql .= " 		 p.codigo_producto, ";
			$sql .= " 		 p.descripcion as producto, ";
			$sql .= " 		 p.cantidad,  ";
			$sql .= " 		 p.codigo_alterno,  ";
			$sql .= " 		 p.codigo_barras, ";
			$sql .= " 		 u.unidad_id,  ";
			$sql .= "      u.descripcion as unidad, ";
			$sql .= "      p.contenido_unidad_venta, ";
			$sql .= "      cla.descripcion as laboratorio, ";
			$sql .= "     	x.existencia ";
			$sql .= " From  empresas e, ";
			$sql .= "  	  	centros_utilidad c, ";
			$sql .= "       bodegas b, ";
			$sql .= "       inventarios_productos p, ";
			$sql .= "       existencias_bodegas x,";
			$sql .= "       unidades u, ";
			$sql .= "       inv_subclases_inventarios sub, ";
			$sql .= "       inv_clases_inventarios cla ";
			$sql .="  WHERE e.empresa_id=c.empresa_id  ";
			$sql .= " 		and e.empresa_id=b.empresa_id ";
			$sql .= " 		and c.centro_utilidad=b.centro_utilidad ";
			$sql .= "       and e.empresa_id=x.empresa_id "; 
			$sql .= "       and c.centro_utilidad=x.centro_utilidad ";
			$sql .= "       and b.bodega=x.bodega ";
			$sql .= "       and p.codigo_producto=x.codigo_producto  ";
			$sql .= "       and  u.unidad_id=p.unidad_id  ";
			$sql .= "       and  p.subclase_id=sub.subclase_id  ";
			$sql .= "       and  p.clase_id=sub.clase_id  ";
			$sql .= "       and  p.grupo_id=sub.grupo_id  ";
			$sql .= "       and  sub.grupo_id=cla.grupo_id  ";
			$sql .= "       and  sub.clase_id=cla.clase_id  ";
			//$sql .= "       and e.sw_tipo_empresa='1' AND  e.sw_activa='1' "; 
			$sql .= "       and   e.sw_activa='1' "; 
			$sql .= "       and x.estado='1'   ";
			$sql .= "       and x.existencia>0   ";
			//$sql .= "       and b.bodega != '".$ebod."' "; 
			if($filtros['empresa_'])
			{
			$sql.=" and e.empresa_id= '".$filtros['empresa_']."' ";
			}										
			if($filtros['codigo_producto'])
			{
			$sql.=" and p.codigo_producto= '".$filtros['codigo_producto']."' ";
			}
			if($filtros['codigo_alterno'])
			$sql.=" and p.codigo_alterno= '".$filtros['codigo_alterno']."' ";
			
			if($filtros['codigo_barras'] != "")
			$sql .= "AND     p.codigo_barras  ILIKE '%".$filtros['codigo_barras']."%' ";
			
			if($filtros['descripcion'] != "")
			$sql .= "AND    p.descripcion  ILIKE '%".$filtros['descripcion']."%' ";
			$cont="select COUNT(*) from (".$sql.") AS A";
			 $sql .= " ORDER by e.empresa_id,x.existencia DESC ";
			$this->ProcesarSqlConteo($cont,$offset);
			
			
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			if(!$rst = $this->ConexionBaseDatos($sql))	return false;
			$datos = array();
			while (!$rst->EOF)
			{
			$datos[] = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		 /*
		* Funcion donde se ingresa temporalmente los productos verificados .
		* @return boolean de acuerdo a la ejecucion del sql.
	*/
		function IngresarTemporalmenteProduc($cadenaMarc,$empresa,$prefijo,$numero,$farmacia,$cantidadM,$porcentaje_gravamenM,$total_costoM,$existencia_bodegaM,$existencia_inventarioM,$costo_inventarioM,$fechav,$lote,$bodega)
		{
	
		    $this->ConexionTransaccion();
			$id=$cadenaMarc."".$fechav."".$lote;
			$sql .= "INSERT INTO producto_verificados_tmp( ";
			$sql .= "       prod_verif_id, ";
			$sql .= "       empresa_id, ";
			$sql .= "       prefijo, ";
			$sql .= "       numero, ";
			$sql .= "       farmacia_id, ";
			$sql .= "       codigo_producto, ";
			$sql .= "       cantidad, ";
			$sql .= "       porcentaje_gravamen, ";
			$sql .= "       total_costo, ";
			$sql .= "       existencia_bodega, ";
			$sql .= "       existencia_inventario, ";
			$sql .= "       costo_inventario, ";
			$sql .= "       fecha_vencimiento, ";
			$sql .= "       lote, usuario_id ";
			$sql .= ")VALUES( ";
			$sql .= "       '".$id."', ";
			$sql .= "       '".$empresa."', ";
			$sql .= "       '".$prefijo."', ";
			$sql .= "       ".$numero.", ";
			$sql .= "       '".$farmacia."', ";
			$sql .= "       '".$cadenaMarc."', ";
            $sql .= "       ".$cantidadM.", ";
			$sql .= "       ".$porcentaje_gravamenM.", ";
			$sql .= "       ".$total_costoM.", ";
			$sql .= "       0.0, ";
			$sql .= "       0.0, ";
			$sql .= "       0.0, ";
			$sql .= "       '".$fechav."', ";
			$sql .= "       '".$lote."', ";
      $sql .= "       ".UserGetUID()."  ";
			$sql .= "       ); ";
			if(!$rst = $this->ConexionTransaccion($sql))
			{
			return false;
			}

			$this->Commit();
			return true;
		}
	/*
		* Funcion donde se eliminan los productos ya verificados.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		 function EliminarProductosVerificados($prefijo,$numero,$empresa,$farmacia)
		 {
			//  $this->=true;

			$sql = " delete from producto_verificados_tmp ";
			$sql .= " where prefijo='".$prefijo."' and  empresa_id='".$empresa."' and numero='".$numero."' and farmacia_id='".$farmacia."'; ";

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
		/*
		* Funcion donde se elimina los productos que ya han dejado de ser pendientes y los que ya han sido verificados.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function EliminarProductosTempo($empresa,$prefijo,$numero,$farmacia)
		{

			//$this->=true;

			$sql = " delete from productospendientesporverificar ";
			$sql .= "  where prefijo='".$prefijo."' and  empresa_id='".$empresa."' and numero='".$numero."'; ";
            $sql .= " delete from producto_verificados_tmp; ";

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
		 /* 
		** Funcion donde se elimina los productos que ya han dejado de ser pendientes.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		function EliminarDatosDeLosProductos($prefijo,$numero,$empresa,$farmacia)
		{
			//$this->=true;
			$sql = " delete from productospendientesporverificar ";
			$sql .= "  where prefijo='".$prefijo."' and  empresa_id='".$empresa."' and numero='".$numero."' and farmacia_id='".$farmacia."'; ";

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
		function ConsultarInfomacionDocumento($empresa,$centro,$bodega,$documento)
		
		{
			
		
			$sql = " SELECT 	documento_id,
        								empresa_id,
        								centro_utilidad,
        								bodega,
        								bodegas_doc_id,
        								sw_estado
					 FROM       inv_bodegas_documentos
					 WHERE      documento_id = '".$documento."' 
					 AND        empresa_id = '".$empresa."'
					 AND        centro_utilidad = '".$centro."'
					 AND        bodega = '".$bodega."' ";
					 
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
		function consultarinformaciondocumento($documento_id)
		{
		
		 $sql = " SELECT    documento_id,
                        empresa_id,
                        tipo_doc_general_id,
                        prefijo,
                        sw_estado,
                        numeracion,
                        descripcion
				FROM        documentos
				WHERE      documento_id = '".$documento_id."' ";
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
				/*
		* Funcion donde se ingresan los productos a la tabla temporal.
		* * @return  boolean de acuerdo a la ejecucion del sql.
		
	*/
			
	    function IngresarProducDevolucion_temporales($farmacia,$centroU,$Bodega,$codigo_producto,$cantidad,$fecha_vencimiento,$lote,$total_costo)
		{			
		
			$this->ConexionTransaccion();
		
	   $cadena=$codigo_producto."".$fecha_vencimiento."".$lote;
			
			$sql = "INSERT INTO Doc_Devolucion_tmp
						(
							Prod_dev_id,	
							farmacia_id,
							centro_utilidad,
							bodega,
							codigo_producto,
							cantidad,
              porcentaje_gravamen,
              total_costo,
							fecha_vencimiento,
							lote,
              usuario_id
		
						)
						VALUES
						(
							'".$cadena."',
							 '".$farmacia."',
							'".$centroU."',
							'".$Bodega."',
							'".$codigo_producto."',
							".$cantidad.",
              0.0,
            	".$total_costo.",
							'".$fecha_vencimiento."',
							'".$lote."',
               ".UserGetUID()." 
							
						);
					";
				
			  if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
		}
  /* Function que permite insertar al tmp de los movimientos de la bodega
       return boolean */
    function Registro_inv_bodegas_movimiento_tmp($doc_tmp_id,$bodegas_doc_id)
    {
     
        	$this->ConexionTransaccion();
       
        $sql = " INSERT INTO inv_bodegas_movimiento_tmp
                            (
                              usuario_id,	
                              doc_tmp_id,
                              bodegas_doc_id,
                              fecha_registro
                            )values(
                            ".UserGetUID().",
                            ".$doc_tmp_id.",
                            ".$bodegas_doc_id.",
                            now() )";
                            
              if(!$rst1 = $this->ConexionTransaccion($sql))
              {
              return false;
              }
              $this->Commit();
              return true;
    }    
    
    
    function consutartmp($bodegas_doc_id)
	   {
	   
      $sql = " SELECT  usuario_id,
                doc_tmp_id,
                bodegas_doc_id,
                observacion,
                fecha_registro,
                abreviatura
      FROM     inv_bodegas_movimiento_tmp
      WHERE    bodegas_doc_id = '".$bodegas_doc_id."'";

	   
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
   /* Funcion que permite Consultar los productos que se estan seleccionando para ser devueltos por fecha de vencimiento
       return array con la informacion */
       
      function Productos_Seleccionados_x_devolver($farmacia,$centro,$bodega)
      {
          $sql = " SELECT  d.codigo_producto,
                            d.cantidad,
                            d.porcentaje_gravamen,
                            d.total_costo,
                            d.fecha_vencimiento,
                            d.lote,
                            i.descripcion AS producto,
                            i.contenido_unidad_venta,
                            u.descripcion as unidad,
                            s.descripcion as molecula,
                            c.descripcion as laboratorio
                    FROM    doc_devolucion_tmp d,
                            inventarios_productos  i,
                            unidades u,
                            inv_subclases_inventarios s,
                            inv_clases_inventarios c
                    WHERE   d.codigo_producto=i.codigo_producto
                    AND     i.unidad_id=u.unidad_id
                    AND     i.grupo_id=s.grupo_id
                    AND     i.clase_id=s.clase_id
                    AND     i.subclase_id=s.subclase_id
                    AND     s.grupo_id=c.grupo_id
                    AND     s.clase_id=c.clase_id
                    AND     farmacia_id = '".$farmacia."' 
                    AND     centro_utilidad = '".$centro."' 
                    AND     bodega = '".$bodega."' ";
                    
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
    /* Funcion que permite Eliminar los productos que ya no se desean devolver
          return array con la informacion */
      
      function Eliminar_producto_seleccionados($codigo,$farmacia,$centro,$bodega,$fechave,$lote)
	   {
	  
      $sql = " DELETE   from  doc_devolucion_tmp     
               WHERE    codigo_producto= '".$codigo."' 
               and      farmacia_id='".$farmacia."' 
               and      centro_utilidad='".$centro."'
               and      bodega='".$bodega."'
               and      fecha_vencimiento='".$fechave."'
               and      lote='".$lote."'       ";

	   
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
    
    /*
		** Funcion donde se Elimina todos los productos que se han ingresado en la tabla temporal de los productos verificados.
		* @return array $datos vector que contiene la informacion de la consulta.
	*/
		
		function EliminarTemporales($farmacia_id)
		{
		
		
			$sql  = " delete from producto_verificados_tmp
                where  farmacia_id='".$farmacia_id."'	
    					  and    usuario_id= ".UserGetUID()."  ; ";
                
       $sql.= " delete from ProductosPendientesPorVerificar
                where  farmacia_id='".$farmacia_id."'	
    					  and    usuario_id= ".UserGetUID()."  ; ";
		
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
     
       
}
 ?>