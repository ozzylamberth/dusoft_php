<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: Informacion_Buzon_DocDespachoSQL.class.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/

	class Informacion_Buzon_DocDespachoSQL extends ConexionBD
	{
	/*
	* Constructor de la clase
	*/
	function Informacion_Buzon_DocDespachoSQL(){}

	/**
	* Funcion donde se Consultan los Tipos de identificacion 
	* @return array $datos vector que contiene la informacion de la consulta de los Tipos 
	* de Identificacion
	*/

		function ObtenerPermisos()
		{
			// $this->debug = true;
			$sql  = " SELECT   	a.empresa_id, ";
			$sql .= "           b.razon_social AS descripcion1, ";
			$sql .= "       	b.sw_activa, ";
			$sql .= "           a.centro_utilidad, ";
			$sql .= "           c.descripcion AS descripcion2, ";
			$sql .= "           a.usuario_id ";
			$sql .= "FROM 	    userpermisos_Informacion_Buzon_DocDespacho AS a, ";
			$sql .= "           empresas AS b, ";
			$sql .= "           centros_utilidad AS c ";
			$sql .= "WHERE      a.usuario_id= ".UserGetUID()."  ";
			$sql .= "           AND 	a.empresa_id=b.empresa_id ";
			$sql .= "           AND 	a.centro_utilidad=c.centro_utilidad ";
			$sql .= "           AND 	a.empresa_id=c.empresa_id AND b.sw_activa='1' AND  b.sw_tipo_empresa='0' ";

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
	
        function ConsultarDocumentDespa($empresa)
		{
			//$this->debug=true;
			
			$sql  = " SELECT 	 F.empresa_id, ";
			$sql .= " 			 F.prefijo,  ";
			$sql .= " 			 F.numero, ";
			$sql .= "			 F.farmacia_id, ";
			$sql .= " 			 to_char(F.fecha_registro,'DD-MM-YYYY') AS fecha_registro, ";
			$sql .= "			 F.sw_revisado, ";
			$sql .= " 			 E.razon_social, ";
			$sql .= "			 S.usuario_id, ";
			$sql .= "			 S.nombre, ";
			$sql .= "			 S.descripcion ";
			$sql .= "FROM        inv_bodegas_movimiento_despachos_farmacias F, ";
			$sql .= "			 system_usuarios S,  ";
			$sql .= "			 empresas E ";
			$sql .= "where       F.usuario_id=S.usuario_id   ";
			$sql .= "AND         F.farmacia_id=E.empresa_id  ";
			$sql .= "AND         F.sw_revisado='0'  ";
			$sql .= "AND         F.empresa_id='".$empresa."'  ";
			
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
		
		  function InformacionDelUsuario()
		{
			//$this->debug=true;
			$sql  = " SELECT 	 usuario_id, ";
			$sql .= " 			 usuario,  ";
			$sql .= " 			 nombre, ";
			$sql .= "			 descripcion ";
			$sql .= "FROM        system_usuarios ";
			$sql .= "where       usuario_id=".UserGetUID()." ;  ";
		
			
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
		
		function ActualizarCampoSw_revisado($empresa,$prefijo,$numero)
		{
			
			//$this->debug=true;
		 	$this->ConexionTransaccion();
			$sql  = "UPDATE  inv_bodegas_movimiento_despachos_farmacias ";
			$sql .= "SET     sw_revisado=1  ";
			$sql .= "WHERE 	empresa_id='".$empresa."' AND prefijo= '".$prefijo."'  AND numero= '".$numero."';";
			
			if(!$rst1 = $this->ConexionTransaccion($sql))
				{
				return false;
				}
				$this->Commit();
				return true;
			
		}
    	function ConsultarDetalleDeDocumentoDespacho($empresa_id,$prefijo,$numero)
		{
			//$this->debug=true;
			
                                              
			$sql  = " SELECT 	  D.movimiento_id, ";
			$sql .= " 			  D.empresa_id, ";
			$sql .= " 			  D.prefijo, ";
			$sql .= "			  D.numero, ";
			$sql .= " 			  D.centro_utilidad, ";
			$sql .= "			  D.bodega, ";
			$sql .= "			 p.codigo_producto,   p.cantidad as cant, ";
			$sql .= "           fc_descripcion_producto(p.codigo_producto) as producto, ";
			$sql .= "			 D.cantidad, ";
			$sql .= " 			 D.porcentaje_gravamen, ";
			$sql .= "			 D.total_costo, ";
			$sql .= "			 D.existencia_bodega, ";
			$sql .= "			 D.existencia_inventario, ";
			$sql .= " 			 D.costo_inventario, ";
			$sql .= "			 D.fecha_vencimiento, ";
			$sql .= "			 D.lote, ";
			$sql .= " 			 g.grupo_id,  ";
			$sql .= "			 c.clase_id, ";
			$sql .= "			 c.descripcion as laboratorio, ";
			$sql .= "            s.subclase_id,";
			$sql .= "            s.descripcion as molecula,";
			$sql .= "            u.unidad_id,";
			$sql .= "            u.descripcion as unidad ";
			$sql .= "FROM        inv_bodegas_movimiento_d D, ";
			$sql .= "			 inventarios_productos p,  ";
			$sql .= "            inv_subclases_inventarios s, ";
			$sql .= "            inv_clases_inventarios c, ";
			$sql .= "            inv_grupos_inventarios g, ";
			$sql .= "            inventarios i, ";
			$sql .= "            unidades u, ";
			$sql .= "            existencias_bodegas x ";
			$sql .= "WHERE       D.empresa_id=x.empresa_id ";
			$sql .= " AND         D.centro_utilidad=x.centro_utilidad    ";
			$sql .= " AND         D.bodega=x.bodega ";
			$sql .= " AND         D.codigo_producto=x.codigo_producto";
			$sql .= " AND         x.empresa_id=i.empresa_id";
			$sql .= " AND         x.codigo_producto=i.codigo_producto";
			$sql .= " AND         i.codigo_producto=p.codigo_producto";
			$sql .= " AND         p.grupo_id=s.grupo_id ";
			$sql .= " AND         p.clase_id=s.clase_id   ";
			$sql .= " AND         p.subclase_id=s.subclase_id";
			$sql .= " AND         s.grupo_id=c.grupo_id 	";
			$sql .= " AND         s.clase_id=c.clase_id ";
			$sql .= " AND         c.grupo_id=g.grupo_id ";
			$sql .= " AND        p.unidad_id=u.unidad_id ";
			$sql .= " AND        D.empresa_id='".$empresa_id."' ";
			$sql .= " AND         D.prefijo='".$prefijo."' ";
			$sql .= " AND         D.numero='".$numero."' ";
							
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
		
		function AuditoriaUsuariosDocDespacho($empresa_id,$prefijo,$numero,$farmacia_id)
		{
			//$this->debug=true;
		 	$sql = "SELECT NEXTVAL('usuarios_consulta_docdespachos_consulta_docdesp_id_seq') AS sq; ";

			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;
			if(!$rst->EOF)
			{
			$indice = $rst->GetRowAssoc($ToUpper = false);
			$rst->MoveNext();     
			}
			$rst->Close(); 

			$sqlerror = "SELECT setval('usuarios_consulta_docdespachos_consulta_docdesp_id_seq', ".($indice['sq']-1).") ";   
			
			$this->ConexionTransaccion();
		
			$sql = "INSERT INTO usuarios_consulta_docdespachos
					(
						consulta_docdesp_id,
						empresa_id,
						prefijo,
						numero,
						farmacia_id,
						usuario_id,
						fecha_consulta
					)
					VALUES
					(
						".$indice['sq'].",
						'".$empresa_id."',
						'".$prefijo."',
						".$numero.",
						'".$farmacia_id."',
						".UserGetUID().",
						 NOW()
					);";
			
					
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
		
		
	
	
		  function ConsultarPrefijoDespacho($empresa)
		{
			//$this->debug=true;
			$sql  = " SELECT 	 empresa_id, ";
			$sql .= " 			 prefijo,  ";
			$sql .= " 			 numero ";
		
			$sql .= "FROM        inv_bodegas_movimiento_despachos_farmacias ";
			$sql .= "where       empresa_id='".$empresa."' ;  ";
		
			
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
	*/
		 function ConsultarDocumentDespaVerf($empresa,$filtros,$offset)
		{
			//$this->debug=true;
			
			$sql  = " SELECT 	 F.empresa_id, ";
			$sql .= " 			 F.prefijo,  ";
			$sql .= " 			 F.numero, ";
			$sql .= "			 F.farmacia_id, ";
			$sql .= " 			 to_char(F.fecha_registro,'DD-MM-YYYY') AS fecha_registro, ";
			$sql .= "			 F.sw_revisado, ";
			$sql .= " 			 E.razon_social, ";
			$sql .= "			 S.usuario_id, ";
			$sql .= "			 S.nombre, ";
			$sql .= "			 S.descripcion ";
			$sql .= "FROM        inv_bodegas_movimiento_despachos_farmacias F, ";
			$sql .= "			 system_usuarios S,  ";
			$sql .= "			 empresas E ";
			$sql .= "where       F.usuario_id=S.usuario_id   ";
			$sql .= "AND         F.farmacia_id=E.empresa_id  ";
			$sql .= "AND         F.sw_revisado='1'  ";
			$sql .= "AND         F.empresa_id='".$empresa."'  ";
		       
			      
			$FechaI=$filtros['fecha_inicio'];
			$FechaF=$filtros['fecha_final'];

			$fdatos=explode("-", $FechaI);
			$fedatos= $fdatos[2]."-".$fdatos[1]."-".$fdatos[0];
		
			$fdtos=explode("-", $FechaF);
			$fecdtos= $fdtos[2]."-".$fdtos[1]."-".$fdtos[0];
							
			if($fedatos && $filtros['fecha_final'] )
			{
			$sql.=" AND F.fecha_registro between '".$fedatos."'::date  AND  '".$fecdtos."' ::date ";
			}
				
			
			$cont= "   select COUNT(*) from (".$sql.") AS A";
			$sql .= "  ORDER by  F.fecha_registro DESC ";
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
	}
 ?>
