<?php

/**
* $Id: app_InvBodegasReposicion_user.php,v 1.3 2007/07/10 13:47:32 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* Modulo para el Manejo de la reposicion de productos entre bodegas
*/

class app_InvBodegasReposicion_user extends classModulo
{

	var $limit;
	var $conteo;

	/**
	* Funcion que
	* @return boolean
	*/


	function app_InvBodegasReposicion_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
		
	/**
	* Funcion que se encarga de llamar al menu para la seleccion de la empresa,centro de utilidad y la bodega
	* @return boolean
	*/

	function main()
	{
		unset($_SESSION['BodegasReposicion']);
		$this->Principal();
		return true;
	}
	
	/**
	* Funcion que consulta en la base de datos los permisos del usuario para trabajar con las bodegas
	* @return array
	*/
	
	function PermisosUsuarios()
	{
		list($dbconn) = GetDBconn();

		$query = "SELECT 	a.empresa_id,
											b.razon_social AS descripcion1,
											a.centro_utilidad,
											c.descripcion AS descripcion2,
											e.bodega,
											e.descripcion AS descripcion3,
											g.departamento,
											g.descripcion AS descripcion4,
											a.usuario_id,
											d.nombre
							FROM 	userpermisos_bodegas_reposicion AS a,
										empresas AS b,
										centros_utilidad AS c,
										system_usuarios AS d,
										bodegas AS e,
										departamentos AS g
							WHERE a.usuario_id=".UserGetUID()."
							AND 	a.empresa_id=b.empresa_id
							AND 	a.centro_utilidad=c.centro_utilidad
							AND 	a.empresa_id=c.empresa_id
							AND 	a.usuario_id=d.usuario_id
							AND 	e.empresa_id=a.empresa_id
							AND 	e.centro_utilidad=a.centro_utilidad
							AND 	e.bodega=a.bodega
							AND 	e.departamento=g.departamento
							ORDER BY e.descripcion;";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$result->EOF)
		{
			$vars[$result->fields[1]][$result->fields[3]][$result->fields[5]]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
		}
		
		$mtz[0]='EMPRESAS';
		$mtz[1]='CENTROS UTILIDAD';
		$mtz[2]='BODEGAS';
		$url[0]='app';
		$url[1]='InvBodegasReposicion';
		$url[2]='user';
		$url[3]='FrmBodegasReposicion';
		$url[4]='PermisoReposicion';
		
		$this->salida .=gui_theme_menu_acceso('SELECCIONE BODEGA', $mtz, $vars, $url, ModuloGetURL('system','Menu'));
		return true;
	}
	
	function GetBodegasReposicion()
	{
		list($dbconn) = GetDBconn();
	
		$query=	"
							SELECT 	b.empresa_id,
											b.centro_utilidad_destino,
											b.bodega_destino,
											a.descripcion
							FROM 		bodegas as a,
											bodegas_restitucion as b
							WHERE a.empresa_id=b.empresa_id
							AND 	a.centro_utilidad=b.centro_utilidad_destino
							AND 	a.bodega=b.bodega_destino
							AND 	b.empresa_id='".$_SESSION['BodegasReposicion']['empresa_id']."'
							AND 	b.centro_utilidad_origen='".$_SESSION['BodegasReposicion']['centro_id']."'
							AND 	b.bodega_origen='".$_SESSION['BodegasReposicion']['bodega']."'
							AND 	a.sw_restitucion='1'
							AND 	a.estado='1'
							ORDER BY a.descripcion;
						";
						
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo InvBodegasReposicion - GetBodegasReposicion SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		return $vars;
	}
	
	function GetProductosBodegasReposicion($bodega_destino)
	{
		list($dbconn) = GetDBconn();
	
		$query=	"
							SELECT
							a.codigo_producto,
							b.descripcion,
							c.descripcion as descripcion_unidad,
							a.bodega as bodega_o,
							x.bodega as bodega_d,
							abs(x.existencia_maxima - x.existencia) as pedido,
							a.existencia as existencia_o,
							a.existencia_minima as existencia_minima_o,
							a.existencia_maxima as existencia_maxima_o,
							x.existencia as existencia_d,
							x.existencia_minima as existencia_minima_d,
							x.existencia_maxima as existencia_maxima_d,
							b.porc_iva,
							d.costo
							
							FROM
							existencias_bodegas as a,
							inventarios_productos as b,
							unidades as c,
							inventarios as d,
							existencias_bodegas as x
							
							WHERE a.bodega = '".$_SESSION['BodegasReposicion']['bodega']."'
							AND a.codigo_producto=x.codigo_producto
							AND x.bodega='".$bodega_destino."'
							AND x.existencia < x.existencia_minima
							AND x.codigo_producto = b.codigo_producto
							AND x.estado='1'
							AND (a.existencia-a.existencia_minima) > 0
							AND c.unidad_id = b.unidad_id
							AND b.codigo_producto=d.codigo_producto
							ORDER BY b.descripcion
						";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo InvBodegasReposicion - GetReporteBodegasReposicion SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		return $vars;
	}
	
	function GetExistenciasBodegas($bodega_destino,$codigo_producto)
	{
		list($dbconn) = GetDBconn();
	
		$query=	"
							SELECT
							a.codigo_producto,
							b.descripcion,
							c.descripcion as descripcion_unidad,
							a.bodega as bodega_o,
							x.bodega as bodega_d,
							abs(x.existencia_maxima - x.existencia) as pedido,
							a.existencia as existencia_o,
							abs(a.existencia - a.existencia_minima) as existencia_real,
							a.existencia_minima as existencia_minima_o,
							a.existencia_maxima as existencia_maxima_o,
							x.existencia as existencia_d,
							x.existencia_minima as existencia_minima_d,
							x.existencia_maxima as existencia_maxima_d
							
							FROM
							existencias_bodegas as a,
							inventarios_productos as b,
							unidades as c,
							inventarios as d,
							existencias_bodegas as x
							
							WHERE a.bodega = '".$_SESSION['BodegasReposicion']['bodega']."'
							AND a.codigo_producto=x.codigo_producto
							AND x.bodega='".$bodega_destino."'
							AND x.existencia < x.existencia_minima
							AND x.codigo_producto = b.codigo_producto
							AND x.estado='1'
							AND a.codigo_producto='$codigo_producto'
							AND (a.existencia-a.existencia_minima) > 0
							AND c.unidad_id = b.unidad_id
							AND b.codigo_producto=d.codigo_producto
							ORDER BY b.descripcion;
						";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo InvBodegasReposicion - GetReporteBodegasReposicion SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->RecordCount() > 0)
		{
			while(!$result->EOF)
			{
				$vars=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
		}
		
		return $vars;
	}
	
	function CrearDocumento()
	{
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
		
		$codigosPro=SessionGetVar('SelectPro');
		$cantid=SessionGetVar('CantReponer');
		$bodega_d=$_REQUEST['bodega'];
		$bodega_desc_d=$_REQUEST['descripcion'];
		$observacion=$_REQUEST['observacion'];
		$bodegas_doc_id=$_REQUEST['bodega_doc_id'];
		$tipo_doc_bodega_id=$_REQUEST['tipo_doc_bodega_id'];
		$docs_tmp=$_REQUEST['docs_tmp'];
		$datosPro=$this->GetProductosBodegasReposicion($bodega_d);
		
		if(!$docs_tmp)
			$info_docs_tmp=$this->CrearDocTmp($observacion,$_SESSION['BodegasReposicion']['centro_id'],$bodega_d,$bodegas_doc_id);
		else
			$info_docs_tmp['doc_tmp_id']=$docs_tmp;
		
		if(!$info_docs_tmp)
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR EL DOCUMENTO TEMPORAL";
			return false;
		}
		
		$this->vector=null;
		$acumCod="";
		foreach($datosPro as $valorX)
		{
			foreach($codigosPro as $valor)
			{
				list($codigoProducto,$gravamen,$costo)=explode(".-.",$valor);
				
				if($valorX['codigo_producto']==$codigoProducto)
				{
					if($cantid[$codigoProducto] > $valorX['existencia_o']-$valorX['existencia_minima_o'])
					{
						$acumCod.= $codigoProducto." , ";
						$this->FrmError['MensajeError']="ERROR LA CANTIDAD PEDIDA ES MAYOR QUE LA EXISTENCIA [$acumCod]";
						$this->vector[$codigoProducto]=1;
					}
					else
					{
						$total_costo=$costo+($costo*$gravamen)/100;
						$dato=$this->AgregarItem($info_docs_tmp['doc_tmp_id'],$codigoProducto,$cantid[$codigoProducto],$total_costo,$gravamen,$bodegas_doc_id);
						if(!$dato)
						{
							$this->FrmError['MensajeError']="ERROR AL INGRESAR UN PRODUCTO EN EL DOCUMENTO TEMPORAL [$codigoProducto]";
							return false;
						}
					}
				}
			}
		}
		
		if(!$this->vector)
		{
			$infoDoc=$this->CrearDoc($info_docs_tmp['doc_tmp_id'],$bodegas_doc_id);
			if(!$infoDoc)
			{
				$this->FrmError['MensajeError']="ERROR EN LA CREACION DEL DOCUMENTO [".$info_docs_tmp['doc_tmp_id']."]";
				return false;
			}
			
			$this->FrmError['MensajeError']="SE HA CREADO DEL DOCUMENTO EXITOSAMENTE";
			$this->FormaMensaje($infoDoc);
		}
		else
		{
			$this->EliminarDocTemporal($info_docs_tmp['doc_tmp_id'],$bodegas_doc_id);
			$this->FrmConfirmaReposicion($_REQUEST);
		}
		return true;
	}
	
	function CrearDocTmp($observacion,$centro_utilidad,$bodega_d,$bodegas_doc_id)
	{
		$ClassDOC = new BodegasDocumentos($bodegas_doc_id);
		if(!is_object($ClassDOC))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
			return false;
		}
		$objeto = $ClassDOC->GetOBJ();
		if(!is_object($objeto))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DEL METODO [GetOBJ] ".__LINE__;
			return false;
		}
		$retorno=$objeto->NewDocTemporal($observacion,$centro_utilidad,$bodega_d);
		if($retorno===false)
		{
			$this->FrmError['MensajeError']=$ClassDOC->error."<br>".$ClassDOC->mensajeDeError;
			return false;
		}
		return $retorno;
	}
	
	function AgregarItem($doc_tmp_id,$codigo_producto,$cantidad,$total_costo,$iva,$bodegas_doc_id)
	{
		$ClassDOC= new BodegasDocumentosComun($bodegas_doc_id);
		if(!is_object($ClassDOC))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
			return false;
		}
		$retorno=$ClassDOC->AddItemDocTemporal($doc_tmp_id,$codigo_producto,$cantidad,$iva,$total_costo);
		if($retorno===false)
		{
			$this->FrmError['MensajeError']=$ClassDOC->error."<br>".$ClassDOC->mensajeDeError;
			return false;
		}
		return $retorno;
	}
	
	function CrearDoc($doc_tmp_id,$bodegas_doc_id)
	{
		$ClassDOC= new BodegasDocumentos($bodegas_doc_id);
		if(!is_object($ClassDOC))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DEL OBJETO DE LA CLASE [BodegasDocumentos] ".__LINE__;
			return false;
		}
		$objeto = $ClassDOC->GetOBJ();
		if(!is_object($objeto))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DEL METODO [GetOBJ] ".__LINE__;
			return false;
		}
		$retorno=$objeto->CrearDocumento($doc_tmp_id);
		if($retorno===false)
		{
			$this->FrmError['MensajeError']=$ClassDOC->error."<br>".$ClassDOC->mensajeDeError;
			return false;
		}
		return $retorno;
	}
	
	function EliminarItem($item_id,$bodegas_doc_id)
	{
		$ClassDOC= new BodegasDocumentosComun($bodegas_doc_id);
		if(!is_object($ClassDOC))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DE LA CLASE [BodegasDocumentosComun] ".__LINE__;
			return false;
		}
		$retorno=$ClassDOC->DelItemDocTemporal($item_id);
		if($retorno===false)
		{
			$this->FrmError['MensajeError']=$ClassDOC->error."<br>".$ClassDOC->mensajeDeError;
			return false;
		}
		return $retorno;
	}
	
	function EliminarDocTemporal($doc_tmp_id,$bodegas_doc_id)
	{
		$ClassDOC= new BodegasDocumentosComun($bodegas_doc_id);
		if(!is_object($ClassDOC))
		{
			$this->FrmError['MensajeError']="ERROR AL CREAR UNA INSTANCIA DE LA CLASE [BodegasDocumentosComun] ".__LINE__;
			return false;
		}
		$retorno=$ClassDOC->DelDocTemporal($doc_tmp_id);
		if($retorno===false)
		{
			$this->FrmError['MensajeError']=$ClassDOC->error."<br>".$ClassDOC->mensajeDeError;
			return false;
		}
		return $retorno;
	}
}
?>