<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: ListaReportes.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase: ListaReportes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  class ListaReportes extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function ListaReportes(){}
    /*
    * Funcion donde se obtienen los permisos de los usuarios para acceder al modulo
    *
    * @return mixed
    */
		function ObtenerPermisos($usuario)
		{			
			$sql  = "SELECT	
						d.empresa_id,
						d.razon_social,
						c.centro_utilidad,
						c.descripcion as descripcion_centro,
						b.bodega,
						b.descripcion as descripcion_bodega,
						a.tipo_usuario,
						a.autorizador
						FROM
						userpermisos_autorizacion_ajustes_inventarios AS a
						JOIN bodegas as b ON (a.empresa_id = b.empresa_id)
						AND (a.centro_utilidad = b.centro_utilidad)
						JOIN centros_utilidad as c ON (b.empresa_id = c.empresa_id)
						AND (a.centro_utilidad = c.centro_utilidad)
						JOIN empresas as d ON (c.empresa_id = d.empresa_id)
						WHERE TRUE
						AND a.usuario_id = ".UserGetUID()."
						ORDER BY d.razon_social;";

			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[3]][$rst->fields[5]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}

    function ObtenerDocumentosPorAutorizar($empresa,$filtro,$offset)
		{		
			/*$this->debug=true;*/
			$sql  = "
						SELECT
						h.razon_social,
						g.descripcion as descripcion_centro,
						f.descripcion as descripcion_bodega,
						d.prefijo,
						d.descripcion,
						e.usuario_id,
						a.doc_tmp_id,
						b.fecha_registro,
						b.observacion,
						e.nombre,
						a.usuario_control_interno,
						a.usuario_jefe_bodega,
						CASE 
						WHEN a.usuario_control_interno IS NOT NULL THEN
						'Autorizado'
						ELSE 'No Autorizado'
						END as titulo_control,
						CASE 
						WHEN a.usuario_jefe_bodega IS NOT NULL THEN
						'Autorizado'
						ELSE 'No Autorizado'
						END as titulo_jefe,
						CASE 
						WHEN a.usuario_control_interno IS NOT NULL THEN
						'autorizado.png'
						ELSE 'no_autorizado.png'
						END as icono_control_interno,
						CASE 
						WHEN a.usuario_jefe_bodega IS NOT NULL THEN
						'autorizado.png'
						ELSE 'no_autorizado.png'
						END as icono_jefe_bodega
						FROM
						inv_bodegas_movimiento_tmp_ajustes as a
						JOIN inv_bodegas_movimiento_tmp as b ON(a.doc_tmp_id = b.doc_tmp_id)
						AND (a.usuario_id = b.usuario_id)
						JOIN inv_bodegas_documentos as c ON(b.bodegas_doc_id = c.bodegas_doc_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
						JOIN bodegas as f ON(c.empresa_id = f.empresa_id)
						AND (c.centro_utilidad = f.centro_utilidad)
						AND (c.bodega = f.bodega)
						JOIN centros_utilidad as g ON(f.empresa_id = g.empresa_id)
						AND (f.centro_utilidad = g.centro_utilidad)
						JOIN empresas as h ON (g.empresa_id = h.empresa_id)
						WHERE TRUE
						AND c.empresa_id='".trim($empresa['empresa_id'])."'
						AND c.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND c.bodega = '".trim($empresa['bodega'])."'  ";
			if($filtro['doc_tmp_id'])
			$sql .=" AND   a.doc_tmp_id = '".trim($filtro['doc_tmp_id'])."'  ";
			if($filtro['usuario'])
			$sql .=" AND   e.nombre ILIKE '%".trim($filtro['usuario'])."%'  ";
			if($filtro['fecha_inicio'])
			$sql .=" AND   b.fecha_registro::date >= '".trim($this->DividirFecha($filtro['fecha_inicio']))."'::date  ";
			if($filtro['fecha_final'])
			$sql .= " AND   b.fecha_registro::date <= '".trim($this->DividirFecha($filtro['fecha_final']))."'::date ";			
			$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
			$this->ProcesarSqlConteo($cont,$offset);

			$sql .= " ORDER BY a.doc_tmp_id ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
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
		
    function DocumentoAutorizar($empresa,$documento)
		{		
			/*this->debug=true;*/
			$sql  = "
						SELECT
						h.razon_social,
						g.descripcion as descripcion_centro,
						f.descripcion as descripcion_bodega,
						d.prefijo,
						d.descripcion,
						e.usuario_id,
						a.doc_tmp_id,
						b.fecha_registro,
						b.observacion,
						e.nombre,
						a.coordinador_auxiliar,
						a.control_interno,
						a.usuario_control_interno,
						a.usuario_jefe_bodega,
						CASE 
						WHEN a.usuario_control_interno IS NOT NULL THEN
						'Autorizado'
						ELSE 'No Autorizado (Clic para Autorizar)'
						END as titulo_control,
						CASE 
						WHEN a.usuario_jefe_bodega IS NOT NULL THEN
						'Autorizado'
						ELSE 'No Autorizado (Clic para Autorizar)'
						END as titulo_jefe,
						CASE 
						WHEN a.usuario_control_interno IS NOT NULL THEN
						'autorizado.png'
						ELSE 'no_autorizado.png'
						END as icono_control_interno,
						CASE 
						WHEN a.usuario_jefe_bodega IS NOT NULL THEN
						'autorizado.png'
						ELSE 'no_autorizado.png'
						END as icono_jefe_bodega
						FROM
						inv_bodegas_movimiento_tmp_ajustes as a
						JOIN inv_bodegas_movimiento_tmp as b ON(a.doc_tmp_id = b.doc_tmp_id)
						AND (a.usuario_id = b.usuario_id)
						JOIN inv_bodegas_documentos as c ON(b.bodegas_doc_id = c.bodegas_doc_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
						JOIN bodegas as f ON(c.empresa_id = f.empresa_id)
						AND (c.centro_utilidad = f.centro_utilidad)
						AND (c.bodega = f.bodega)
						JOIN centros_utilidad as g ON(f.empresa_id = g.empresa_id)
						AND (f.centro_utilidad = g.centro_utilidad)
						JOIN empresas as h ON (g.empresa_id = h.empresa_id)
						WHERE TRUE
						AND c.empresa_id='".trim($empresa['empresa_id'])."'
						AND c.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND c.bodega = '".trim($empresa['bodega'])."'
						AND a.doc_tmp_id = '".trim($documento['doc_tmp_id'])."' 
						AND a.usuario_id = '".trim($documento['usuario_id'])."' ";
			
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
		
		 function DocumentoAutorizar_d($empresa,$documento)
		{		
			
			$sql  = "
						SELECT
						a.codigo_producto,
						fc_descripcion_producto(a.codigo_producto) as producto,
						a.cantidad,
						a.lote,
						a.fecha_vencimiento,
						a.porcentaje_gravamen,
						a.total_costo
						FROM
						inv_bodegas_movimiento_tmp_d as a
						WHERE TRUE
						AND a.empresa_id='".trim($empresa['empresa_id'])."'
						AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND a.bodega = '".trim($empresa['bodega'])."'
						AND a.doc_tmp_id = '".trim($documento['doc_tmp_id'])."' 
						AND a.usuario_id = '".trim($documento['usuario_id'])."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos['documento'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}	
		
		
		 function AuditarDocumento($documento,$datos)
		{		
			/*$this->debug=true;*/
			$sql  = "UPDATE inv_bodegas_movimiento_ajustes 
						SET
						".$datos['campo']." = '".UserGetUID()."',
						observacion_auditoria = '".$datos['auditoria']['observacion_auditoria']."',
						fecha_auditoria = NOW()
						WHERE TRUE
						AND empresa_id = '".trim($documento['empresa_id'])."'
						AND prefijo = '".trim($documento['prefijo'])."'
						AND numero = '".trim($documento['numero'])."'
						AND ".$datos['campo']." IS NULL;	";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			return true;
		}
		
		 function AutorizarDocumento($documento,$datos)
		{		
			/*$this->debug=true;*/
			$sql  = "UPDATE inv_bodegas_movimiento_tmp_ajustes 
						SET
						".$datos['campo']." = '".UserGetUID()."'
						WHERE TRUE
						AND doc_tmp_id = '".$documento['doc_tmp_id']."'
						AND usuario_id = '".$documento['usuario_id']."';	";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			return true;
		}
		
		
    function ObtenerDocumentosAjustes($empresa,$filtro,$offset)
		{		
			
			$sql  = "SELECT
						i.razon_social,
						h.descripcion as descripcion_centro,
						g.descripcion as descripcion_bodega,
						d.descripcion,
						a.empresa_id,
						b.prefijo,
						b.numero,
						a.fecha_registro,
						a.observacion,
						e.usuario_id||'-'||e.nombre as usuario_documento,
						f.usuario_id||'-'||f.nombre as usuario_auditor,
						b.fecha_auditoria,
						b.observacion_auditoria,
						CASE 
						WHEN auditor IS NOT NULL 
						THEN 'auditoria.png'
						ELSE 'auditoria_selec.png'
						END AS icono_auditado,
						CASE 
						WHEN auditor IS NOT NULL 
						THEN 'Documento Auditado'
						ELSE 'Documento No Auditado'
						END AS titulo_auditado
						FROM
						inv_bodegas_movimiento as a
						JOIN inv_bodegas_movimiento_ajustes as b ON (a.empresa_id = b.empresa_id)
						AND (a.prefijo = b.prefijo)
						AND (a.numero = b.numero)
						JOIN inv_bodegas_documentos as c ON(a.empresa_id = c.empresa_id)
						AND (a.centro_utilidad = c.centro_utilidad)
						AND (a.bodega = c.bodega)
						AND (a.documento_id = c.documento_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON (a.usuario_id = e.usuario_id)
						LEFT JOIN system_usuarios as f ON (b.auditor = f.usuario_id)
						JOIN bodegas as g ON (a.empresa_id = g.empresa_id)
						AND (a.centro_utilidad = g.centro_utilidad)
						AND (a.bodega = g.bodega)
						JOIN centros_utilidad as h ON (g.empresa_id = h.empresa_id)
						AND (g.centro_utilidad = h.centro_utilidad)
						JOIN empresas as i ON (h.empresa_id = i.empresa_id)
						WHERE TRUE
						AND a.empresa_id= '".trim($empresa['empresa_id'])."'
						AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND a.bodega = '".trim($empresa['bodega'])."'  ";
			if($filtro['fecha_inicio'])
			$sql .=" AND   a.fecha_registro::date >= '".trim($this->DividirFecha($filtro['fecha_inicio']))."'::date  ";
			if($filtro['fecha_final'])
			$sql .= " AND   a.fecha_registro::date <= '".trim($this->DividirFecha($filtro['fecha_final']))."'::date ";
			$sql .= "	AND e.nombre ILIKE '%".$filtro['usuario']."%' ";
			$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
			$this->ProcesarSqlConteo($cont,$offset);

			$sql .= " ORDER BY a.fecha_registro ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
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
		
		
			
    function DocumentoAuditar($empresa,$documento)
		{		
			/*$this->debug=true;*/
			$sql  = "SELECT
						i.razon_social,
						h.descripcion as descripcion_centro,
						g.descripcion as descripcion_bodega,
						d.descripcion,
						a.empresa_id,
						b.prefijo,
						b.numero,
						a.fecha_registro,
						a.observacion,
						e.usuario_id||'-'||e.nombre as usuario_documento,
						f.usuario_id||'-'||f.nombre as usuario_auditor,
						b.coordinador_auxiliar,
						b.control_interno,
						b.usuario_control_interno,
						b.usuario_jefe_bodega,
						b.fecha_auditoria,
						b.observacion_auditoria,
						b.auditor,
						a.total_costo,
						CASE 
						WHEN auditor IS NOT NULL 
						THEN 'DOCUMENTO AUDITADO'
						ELSE 'GUARDAR AUDITORIA'
						END AS boton_auditado,
						CASE 
						WHEN auditor IS NOT NULL 
						THEN 'readonly@disabled'
						ELSE ''
						END AS propiedades
						FROM
						inv_bodegas_movimiento as a
						JOIN inv_bodegas_movimiento_ajustes as b ON (a.empresa_id = b.empresa_id)
						AND (a.prefijo = b.prefijo)
						AND (a.numero = b.numero)
						JOIN inv_bodegas_documentos as c ON(a.empresa_id = c.empresa_id)
						AND (a.centro_utilidad = c.centro_utilidad)
						AND (a.bodega = c.bodega)
						AND (a.documento_id = c.documento_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON (a.usuario_id = e.usuario_id)
						LEFT JOIN system_usuarios as f ON (b.auditor = f.usuario_id)
						JOIN bodegas as g ON (a.empresa_id = g.empresa_id)
						AND (a.centro_utilidad = g.centro_utilidad)
						AND (a.bodega = g.bodega)
						JOIN centros_utilidad as h ON (g.empresa_id = h.empresa_id)
						AND (g.centro_utilidad = h.centro_utilidad)
						JOIN empresas as i ON (h.empresa_id = i.empresa_id)
						WHERE TRUE
						AND a.empresa_id= '".trim($empresa['empresa_id'])."'
						AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND a.bodega = '".trim($empresa['bodega'])."' 
						AND a.prefijo = '".$documento['prefijo']."' 
						AND a.numero = '".$documento['numero']."' ";
			
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

		
		 function DocumentoAuditar_d($empresa,$documento)
		{		
			
			$sql  = "
						SELECT
						a.codigo_producto,
						fc_descripcion_producto(a.codigo_producto) as producto,
						a.cantidad,
						a.porcentaje_gravamen,
						a.lote,
						a.fecha_vencimiento,
						a.total_costo
						FROM
						inv_bodegas_movimiento_d as a
						WHERE TRUE
						AND a.empresa_id= '".trim($empresa['empresa_id'])."'
						AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND a.bodega = '".trim($empresa['bodega'])."' 
						AND a.prefijo = '".trim($documento['prefijo'])."' 
						AND a.numero = '".trim($documento['numero'])."' ";
			/*print_r($sql);*/
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      $datos = array();
			while(!$rst->EOF)
			{
				$datos['documento'][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->Close();
			return $datos;
		}	
		
		
		 function ObtenerDocumentosPorAutorizarDespacho($empresa,$filtro,$offset)
		{		
			/*$this->debug=true;*/
			$sql  = "SELECT DISTINCT
						h.razon_social,
						g.descripcion as descripcion_centro,
						f.descripcion as descripcion_bodega,
						d.prefijo,
						d.descripcion,
						e.usuario_id,
						a.doc_tmp_id,
						b.fecha_registro,
						b.observacion,
						e.nombre,
						'<b class=\"label_error\">Farmacia:</b> '|| l.descripcion ||'-'||k.descripcion as cliente,
						i.solicitud_prod_a_bod_ppal_id as numero_pedido
						FROM
						inv_bodegas_movimiento_tmp_autorizaciones_despachos as a
						JOIN inv_bodegas_movimiento_tmp as b ON(a.doc_tmp_id = b.doc_tmp_id)
						AND (a.usuario_id = b.usuario_id)
						JOIN inv_bodegas_documentos as c ON(b.bodegas_doc_id = c.bodegas_doc_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
						JOIN bodegas as f ON(c.empresa_id = f.empresa_id)
						AND (c.centro_utilidad = f.centro_utilidad)
						AND (c.bodega = f.bodega)
						JOIN centros_utilidad as g ON(f.empresa_id = g.empresa_id)
						AND (f.centro_utilidad = g.centro_utilidad)
						JOIN empresas as h ON (g.empresa_id = h.empresa_id)
						
						JOIN inv_bodegas_movimiento_tmp_despachos_farmacias as i ON (a.doc_tmp_id = i.doc_tmp_id)
						AND (a.usuario_id = i.usuario_id)
						LEFT JOIN solicitud_productos_a_bodega_principal as j ON (i.solicitud_prod_a_bod_ppal_id = j.solicitud_prod_a_bod_ppal_id)
						LEFT JOIN bodegas as k ON (j.farmacia_id = k.empresa_id)
						AND (j.centro_utilidad = k.centro_utilidad)
						AND (j.bodega = k.bodega)
						LEFT JOIN centros_utilidad as l ON (k.empresa_id = l.empresa_id)
						AND (k.centro_utilidad = l.centro_utilidad)
						
						WHERE TRUE
						AND c.empresa_id='".trim($empresa['empresa_id'])."'
						AND c.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND c.bodega = '".trim($empresa['bodega'])."'  ";
			if($filtro['doc_tmp_id'])
			$sql .=" AND   a.doc_tmp_id = '".trim($filtro['doc_tmp_id'])."'  ";
			if($filtro['pedido_id'])
			$sql .=" AND   i.solicitud_prod_a_bod_ppal_id = '".trim($filtro['pedido_id'])."'  ";
			if($filtro['usuario'])
			$sql .=" AND   e.nombre ILIKE '%".trim($filtro['usuario'])."%'  ";
			if($filtro['fecha_inicio'])
			$sql .=" AND   b.fecha_registro::date >= '".trim($this->DividirFecha($filtro['fecha_inicio']))."'::date  ";
			if($filtro['fecha_final'])
			$sql .= " AND   b.fecha_registro::date <= '".trim($this->DividirFecha($filtro['fecha_final']))."'::date ";			
			
			$sql .= " UNION ";
			
			$sql  .= "SELECT DISTINCT
						h.razon_social,
						g.descripcion as descripcion_centro,
						f.descripcion as descripcion_bodega,
						d.prefijo,
						d.descripcion,
						e.usuario_id,
						a.doc_tmp_id,
						b.fecha_registro,
						b.observacion,
						e.nombre,
						'<b class=\"label_error\">Cliente:</b> '||j.tipo_id_tercero ||'-'||j.tercero_id ||' : '||j.nombre_tercero as cliente,
						i.pedido_cliente_id as numero_pedido
						FROM
						inv_bodegas_movimiento_tmp_autorizaciones_despachos as a
						JOIN inv_bodegas_movimiento_tmp as b ON(a.doc_tmp_id = b.doc_tmp_id)
						AND (a.usuario_id = b.usuario_id)
						JOIN inv_bodegas_documentos as c ON(b.bodegas_doc_id = c.bodegas_doc_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
						JOIN bodegas as f ON(c.empresa_id = f.empresa_id)
						AND (c.centro_utilidad = f.centro_utilidad)
						AND (c.bodega = f.bodega)
						JOIN centros_utilidad as g ON(f.empresa_id = g.empresa_id)
						AND (f.centro_utilidad = g.centro_utilidad)
						JOIN empresas as h ON (g.empresa_id = h.empresa_id)
						
						JOIN inv_bodegas_movimiento_tmp_despachos_clientes as i ON (a.doc_tmp_id = i.doc_tmp_id)
						AND (a.usuario_id = i.usuario_id)
						LEFT JOIN terceros as j ON (i.tipo_id_tercero = j.tipo_id_tercero)
						AND(i.tercero_id = j.tercero_id)
						
						WHERE TRUE
						AND c.empresa_id='".trim($empresa['empresa_id'])."'
						AND c.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND c.bodega = '".trim($empresa['bodega'])."'  ";
			if($filtro['doc_tmp_id'])
			$sql .=" AND   a.doc_tmp_id = '".trim($filtro['doc_tmp_id'])."'  ";
			if($filtro['pedido_id'])
			$sql .=" AND   i.pedido_cliente_id = '".trim($filtro['pedido_id'])."'  ";
			if($filtro['usuario'])
			$sql .=" AND   e.nombre ILIKE '%".trim($filtro['usuario'])."%'  ";
			if($filtro['fecha_inicio'])
			$sql .=" AND   b.fecha_registro::date >= '".trim($this->DividirFecha($filtro['fecha_inicio']))."'::date  ";
			if($filtro['fecha_final'])
			$sql .= " AND   b.fecha_registro::date <= '".trim($this->DividirFecha($filtro['fecha_final']))."'::date ";			
			
			$cont  = "SELECT COUNT(*) FROM (".$sql.") A  ";
			$this->ProcesarSqlConteo($cont,$offset);

			/*$sql .= " ORDER BY a.doc_tmp_id ";*/
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
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
		
		function DocumentoAutorizar_Despacho($empresa,$documento)
		{		
			/*this->debug=true;*/
			$sql  = "SELECT DISTINCT
						h.razon_social,
						g.descripcion as descripcion_centro,
						f.descripcion as descripcion_bodega,
						d.prefijo,
						d.descripcion,
						e.usuario_id,
						a.doc_tmp_id,
						b.fecha_registro,
						b.observacion,
						e.nombre,
						'<b class=\"label_error\">FARMACIA-</b> '|| l.descripcion ||'-'||k.descripcion as cliente,
						i.solicitud_prod_a_bod_ppal_id as numero_pedido
						FROM
						inv_bodegas_movimiento_tmp_autorizaciones_despachos as a
						JOIN inv_bodegas_movimiento_tmp as b ON(a.doc_tmp_id = b.doc_tmp_id)
						AND (a.usuario_id = b.usuario_id)
						JOIN inv_bodegas_documentos as c ON(b.bodegas_doc_id = c.bodegas_doc_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
						JOIN bodegas as f ON(c.empresa_id = f.empresa_id)
						AND (c.centro_utilidad = f.centro_utilidad)
						AND (c.bodega = f.bodega)
						JOIN centros_utilidad as g ON(f.empresa_id = g.empresa_id)
						AND (f.centro_utilidad = g.centro_utilidad)
						JOIN empresas as h ON (g.empresa_id = h.empresa_id)
						
						JOIN inv_bodegas_movimiento_tmp_despachos_farmacias as i ON (a.doc_tmp_id = i.doc_tmp_id)
						AND (a.usuario_id = i.usuario_id)
						LEFT JOIN solicitud_productos_a_bodega_principal as j ON (i.solicitud_prod_a_bod_ppal_id = j.solicitud_prod_a_bod_ppal_id)
						LEFT JOIN bodegas as k ON (j.farmacia_id = k.empresa_id)
						AND (j.centro_utilidad = k.centro_utilidad)
						AND (j.bodega = k.bodega)
						LEFT JOIN centros_utilidad as l ON (k.empresa_id = l.empresa_id)
						AND (k.centro_utilidad = l.centro_utilidad)
						
						WHERE TRUE
						AND c.empresa_id='".trim($empresa['empresa_id'])."'
						AND c.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND c.bodega = '".trim($empresa['bodega'])."' 
						AND a.doc_tmp_id = '".trim($documento['doc_tmp_id'])."' 
						AND a.usuario_id = '".trim($documento['usuario_id'])."' ";			
			
			$sql .= " UNION ";
			
			$sql  .= "SELECT DISTINCT
						h.razon_social,
						g.descripcion as descripcion_centro,
						f.descripcion as descripcion_bodega,
						d.prefijo,
						d.descripcion,
						e.usuario_id,
						a.doc_tmp_id,
						b.fecha_registro,
						b.observacion,
						e.nombre,
						'<b class=\"label_error\">CLIENTE-</b> '||j.tipo_id_tercero ||'-'||j.tercero_id ||' : '||j.nombre_tercero as cliente,
						i.pedido_cliente_id as numero_pedido
						FROM
						inv_bodegas_movimiento_tmp_autorizaciones_despachos as a
						JOIN inv_bodegas_movimiento_tmp as b ON(a.doc_tmp_id = b.doc_tmp_id)
						AND (a.usuario_id = b.usuario_id)
						JOIN inv_bodegas_documentos as c ON(b.bodegas_doc_id = c.bodegas_doc_id)
						JOIN documentos as d ON(c.empresa_id = d.empresa_id)
						AND (c.documento_id = d.documento_id)
						JOIN system_usuarios as e ON(a.usuario_id = e.usuario_id)
						JOIN bodegas as f ON(c.empresa_id = f.empresa_id)
						AND (c.centro_utilidad = f.centro_utilidad)
						AND (c.bodega = f.bodega)
						JOIN centros_utilidad as g ON(f.empresa_id = g.empresa_id)
						AND (f.centro_utilidad = g.centro_utilidad)
						JOIN empresas as h ON (g.empresa_id = h.empresa_id)
						
						JOIN inv_bodegas_movimiento_tmp_despachos_clientes as i ON (a.doc_tmp_id = i.doc_tmp_id)
						AND (a.usuario_id = i.usuario_id)
						LEFT JOIN terceros as j ON (i.tipo_id_tercero = j.tipo_id_tercero)
						AND(i.tercero_id = j.tercero_id)
						
						WHERE TRUE
						AND c.empresa_id='".trim($empresa['empresa_id'])."'
						AND c.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
						AND c.bodega = '".trim($empresa['bodega'])."' 
						AND a.doc_tmp_id = '".trim($documento['doc_tmp_id'])."' 
						AND a.usuario_id = '".trim($documento['usuario_id'])."' ";
			
			
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
		
		
		    function ObtenerProductos_Autorizacion($empresa,$documento)
		{		
			/*$this->debug=true;*/
			$sql  = "
			SELECT 
			a.doc_tmp_id,
			a.usuario_id, 	
			a.empresa_id, 	
			a.centro_utilidad, 	
			a.bodega, 	
			a.codigo_producto,
			fc_descripcion_producto(codigo_producto) as producto,
			a.lote, 	
			a.fecha_vencimiento, 	
			a.cantidad, 	
			a.porcentaje_gravamen, 	
			a.total_costo, 	
			a.usuario_id_autorizador, 	
			a.observacion, 	
			a.fecha_registro, 	
			a.fecha_autorizacion, 	
			a.sw_autorizado,
			CASE 
			WHEN a.sw_autorizado = '1' THEN 'autorizado.png'
			ELSE 'no_autorizado.png'
			END AS icono
			FROM
			inv_bodegas_movimiento_tmp_autorizaciones_despachos as a
			WHERE TRUE
			AND a.empresa_id='".trim($empresa['empresa_id'])."'
			AND a.centro_utilidad = '".trim($empresa['centro_utilidad'])."'
			AND a.bodega = '".trim($empresa['bodega'])."' 
			AND a.doc_tmp_id = '".trim($documento['doc_tmp_id'])."' 
			AND a.usuario_id = '".trim($documento['usuario_id'])."';	";
			
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
		
		 function AutorizarProducto_Despacho($documento,$datos_autorizar,$datos)
		{		
			/*$this->debug=true;*/
			$sql  = "UPDATE inv_bodegas_movimiento_tmp_autorizaciones_despachos
						SET
						usuario_id_autorizador = '".UserGetUID()."',
						observacion = '".$datos_autorizar['observacion']."',
						fecha_autorizacion = NOW(),
						sw_autorizado = '1'
						WHERE TRUE
						AND doc_tmp_id = '".trim($documento['doc_tmp_id'])."'
						AND usuario_id = '".trim($documento['usuario_id'])."'
						AND empresa_id = '".trim($datos_autorizar['empresa_id'])."'
						AND centro_utilidad = '".trim($datos_autorizar['centro_utilidad'])."'
						AND bodega = '".trim($datos_autorizar['bodega'])."'
						AND codigo_producto = '".trim($datos_autorizar['codigo_producto'])."'
						AND lote = '".trim($datos_autorizar['lote'])."'
						AND fecha_vencimiento = '".trim($datos_autorizar['fecha_vencimiento'])."'
						AND sw_autorizado = '0'	;	";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			return true;
		}
		
		 function EliminarProducto_Despacho($documento,$eliminar_autorizacion,$datos)
		{		
			/*$this->debug=true;*/
			$sql  = " DELETE FROM inv_bodegas_movimiento_tmp_autorizaciones_despachos
						 WHERE TRUE
						AND doc_tmp_id = '".trim($documento['doc_tmp_id'])."'
						AND usuario_id = '".trim($documento['usuario_id'])."'
						AND empresa_id = '".trim($eliminar_autorizacion['empresa_id'])."'
						AND centro_utilidad = '".trim($eliminar_autorizacion['centro_utilidad'])."'
						AND bodega = '".trim($eliminar_autorizacion['bodega'])."'
						AND codigo_producto = '".trim($eliminar_autorizacion['codigo_producto'])."'
						AND lote = '".trim($eliminar_autorizacion['lote'])."'
						AND fecha_vencimiento = '".trim($eliminar_autorizacion['fecha_vencimiento'])."'
						AND sw_autorizado = '0';	";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			return true;
		}
		
  }
?>