<?php

/**
 * $Id: app_InvCodificacionPtos_user.php,v 1.5 2008/06/26 19:21:03 cahenao Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventarios en el Sistema
 */

/**
*Contiene los metodos para realizar la administracion de los Inventarios en el sistema
*/

class app_InvCodificacionPtos_user extends classModulo
{
  var $limit;
	var $conteo;
/**
* Funcion que
* @return boolean
*/
	function app_InvCodificacionPtos_user()
	{
   $this->limit=GetLimitBrowser();
   return true;
	}
/**
* Function que llama al menu
* @return boolean;
*/
	function main(){
		if(!$this->MenuInventariosPrincipal()){
        return false;
    }
		return true;
  }
  /**
* Function que llama al menu
* @return boolean;
*/
	function main1(){
		if(!$this->LogueoUsuario()){
        return false;
    }
		return true;
  }
/**
* Funcion que llama la forma donde se puede insertar una clasificacion para un producto del inventario
* @return boolean;
*/
  function main2(){
		if(!$this->ClasificacionProductoGrupo()){
        return false;
    }
		return true;
  }
/**
* Function que llama al menu que muestra al usuario las empresas en las que puede trabajar
* @return boolean;
*/
	function LogueoUsuario(){
	  list($dbconn) = GetDBconn();
	  $query="SELECT * FROM userpermisos_inventario_cod_general WHERE usuario_id='".UserGetUID()."'";
    $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        $this->FormaMostrarPrInv();
				return true;
			}else{
        $mensaje="No Tiene Permisos en el Sistema para Manejar la Codificacion de los Productos";
				$titulo="INVENTARIOS";
				$accion=ModuloGetURL('app','InvCodificacionPtos','user','MenuInventariosPrincipal');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}
	}
/**
* Funcion que Llama a la forma para adicionar un producto en el inventario o eliminarlo segun la seleccion del usuario
* @return boolean;
*/
  function LlamaAccionInventariosGeneral(){
		$paso=$_REQUEST['paso'];
		$Of=$_REQUEST['Of'];
		list($dbconn) = GetDBconn();
		if($_REQUEST['Insertar']){
		  $query="SELECT inv_mostrar_serial('".$_REQUEST['grupo']."','".$_REQUEST['clasePr']."','".$_REQUEST['subclase']."')";
			$result=$dbconn->Execute($query);
			$Producto=$result->fields[0];
      $this->FormaAdicionarInventario($Producto,'','','','','Sin Especificar','0','0.00','','',$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase'],'',$vende);
			return true;
		}elseif($_REQUEST['buscar']){			
		  //$descripcion=strtoupper($_REQUEST['descripcionPro']);
      $this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro'],$_REQUEST['codigoProAlterno']);
			return true;
		}else{
		  if($origenFun==1){
			  $this->ClasificacionProductoGrupo();
			  return true;
			}
		}
	}

	function EditarProductoInventarioCodifi(){

	  $paso=$_REQUEST['paso'];
    $Of=$_REQUEST['Of'];
    $datosProd=$this->DatosProductoInventarioCodifi($_REQUEST['codigoProducto']);
		$NomGrupo=$datosProd['grupo_id'].' '.$datosProd['nomgrupo'];
		$NomClase=$datosProd['clase_id'].' '.$datosProd['nomclase'];
		$NomSubClase=$datosProd['subclase_id'].' '.$datosProd['nomsubclase'];
		if(!$datosProd['sw_medicamento']){
			$this->FormaEditarProductoInventarioCodifi($NomGrupo,$datosProd['grupo_id'],
			$NomClase,$datosProd['clase_id'],$NomSubClase,$datosProd['subclase_id'],
			$datosProd['producto_id'],$datosProd['descripcion'],$datosProd['descripcion_abreviada'],
			$datosProd['fabricante_id'],$datosProd['nomfabricante'],$datosProd['unidad_id'],$datosProd['contenido_unidad_venta'],$datosProd['porc_iva'],$datosProd['codigo_invima'],$datosProd['sw_control_fecha_vencimiento'],
			$_REQUEST['codigoProducto'],'','','','','','','','','','','','','','','','','','','',$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],
			$datosProd['producto_id'],$datosProd['grupo_id'],$datosProd['clase_id'],$datosProd['subclase_id'],$paso,$Of,$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
			return true;
		}else{
      $DatosMedicamento=$this->DatosDelMedicamento($_REQUEST['codigoProducto']);
			$datosProd['sw_control_fecha_vencimiento'];
      $this->FormaEditarProductoInventarioCodifi($NomGrupo,$datosProd['grupo_id'],
			$NomClase,$datosProd['clase_id'],$NomSubClase,$datosProd['subclase_id'],
			$datosProd['producto_id'],$datosProd['descripcion'],$datosProd['descripcion_abreviada'],
			$datosProd['fabricante_id'],$datosProd['nomfabricante'],$datosProd['unidad_id'],$datosProd['contenido_unidad_venta'],$datosProd['porc_iva'],$datosProd['codigo_invima'],$datosProd['sw_control_fecha_vencimiento'],
			$_REQUEST['codigoProducto'],$datosProd['sw_medicamento'],$DatosMedicamento['cod_anatomofarmacologico'],
			$DatosMedicamento['cod_principio_activo'],$DatosMedicamento['cod_forma_farmacologica'],
			$DatosMedicamento['concentracion_forma_farmacologica'],$DatosMedicamento['cod_concentracion'],$DatosMedicamento['unidad_medida_medicamento_id'],$DatosMedicamento['factor_conversion'],$DatosMedicamento['factor_equivalente_mg'],$DatosMedicamento['via_administracion_id'],$DatosMedicamento['sw_pos'],$DatosMedicamento['sw_uso_controlado'],$DatosMedicamento['sw_antibiotico'],$DatosMedicamento['sw_fotosensible'],$DatosMedicamento['sw_refrigerado'],$DatosMedicamento['sw_alimento_parenteral'],$DatosMedicamento['sw_alimento_enteral'],
			$DatosMedicamento['sw_liquidos_electrolitos'],$DatosMedicamento['dias_previos_vencimiento'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],
			$datosProd['producto_id'],$datosProd['grupo_id'],$datosProd['clase_id'],$datosProd['subclase_id'],$paso,$Of,$_REQUEST['consultaForma'],$_REQUEST['origenFun'],$DatosMedicamento['codigo_cum']);
			return true;
		}
	}

	function EliminarProductoInventarioCodifi(){
    $paso=$_REQUEST['paso'];
		$Of=$_REQUEST['Of'];
    list($dbconn) = GetDBconn();
    if($_REQUEST['bandera']==1){
      $query="UPDATE inventarios_productos SET estado='0' WHERE codigo_producto='".$_REQUEST['codigoProducto']."'";
		}else{
      $query="UPDATE inventarios_productos SET estado='1' WHERE codigo_producto='".$_REQUEST['codigoProducto']."'";
		}
		$result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro']);
		return true;
	}
/**
* Funcion que inserta en la base de datos un nuevo producto al inventario
* @return boolean;
*/
	function InsertarProductoInventarios(){
		$DescripcionCompleta=$_REQUEST['DescripcionCompleta'];
		$DescripcionCompleta=strtoupper($DescripcionCompleta);
		$DescripcionAbreviada=$_REQUEST['DescripcionAbreviada'];
		$DescripcionAbreviada=strtoupper($DescripcionAbreviada);
		if($_REQUEST['Cancelar']){
		  if($_REQUEST['OrigenFuct']!=1){
				$this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase']);
				return true;
			}else{
        $action=ModuloGetURL('app','InvCodificacionPtos','user','BusquedaBDProductosInventarios');
        $this->FormaTiposBusqueda('','',$action);
				return true;
			}
		}

		$existePr=$this->existenciaProductosInv($_REQUEST['codProducto'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase']);
		if($existePr==1){
      $this->frmError["MensajeError"]="El Codigo de Producto ya Existe en la Base de Datos.";
			$this->FormaAdicionarInventario($_REQUEST['codProducto'],$DescripcionCompleta,$DescripcionAbreviada,
	    $_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['fabricante'],$_REQUEST['valorFab'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],
			$_REQUEST['NomSubClase']);
			return true;
		}
		if(empty($DescripcionAbreviada)){
      $DescripcionAbreviada=substr($DescripcionCompleta,0,30);
		}
		if(!$_REQUEST['codProducto'] || !$DescripcionCompleta || !$DescripcionAbreviada ||
		  $_REQUEST['unidad']==-1 || !$_REQUEST['PorcentajeIva'] || !$_REQUEST['grupo'] ||
			!$_REQUEST['clasePr'] || !$_REQUEST['subclase']){
			if(!$_REQUEST['codProducto']){ $this->frmError["codProducto"]=1; }
			if(!$DescripcionCompleta){ $this->frmError["DescripcionCompleta"]=1; }
			if(!$DescripcionAbreviada){ $this->frmError["DescripcionAbreviada"]=1; }
			if($_REQUEST['unidad']==-1){ $this->frmError["unidad"]=1; }
			if(!$_REQUEST['PorcentajeIva']){ $this->frmError["PorcentajeIva"]=1; }
			if(!$_REQUEST['grupo']){ $this->frmError["grupo"]=1; }
			if(!$_REQUEST['clasePr']){ $this->frmError["clasePr"]=1; }
			if(!$_REQUEST['subclase']){ $this->frmError["subclase"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			$this->FormaAdicionarInventario($_REQUEST['codProducto'],$DescripcionCompleta,$DescripcionAbreviada,
	    $_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['fabricante'],$_REQUEST['valorFab'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],
			$_REQUEST['NomSubClase']);
			return true;
		}
    if($_REQUEST['valorFab']){$valorFab1=$_REQUEST['valorFab'];}else{$valorFab1='0';}
		if($_REQUEST['fechaVencimiento']){$fechaVencimiento='1';}else{$fechaVencimiento='0';}
		list($dbconn) = GetDBconn();
		$query = "INSERT INTO inventarios_productos(
																					codigo_producto,
																					grupo_id,
																					clase_id,
																					subclase_id,
																					producto_id,
																					descripcion,
																					descripcion_abreviada,
																					fabricante_id,
																					unidad_id,
																					porc_iva,
																					estado,
																					codigo_invima,
																					sw_control_fecha_vencimiento,
																					contenido_unidad_venta
																					)VALUES('','".$_REQUEST['grupo']."','".$_REQUEST['clasePr']."','".$_REQUEST['subclase']."',
																					'".$_REQUEST['codProducto']."','$DescripcionCompleta',
																					'$DescripcionAbreviada','$valorFab1','".$_REQUEST['unidad']."',
																			    '".$_REQUEST['PorcentajeIva']."','1','".$_REQUEST['codigoInvima']."','$fechaVencimiento','".$_REQUEST['cantidadUnidadMedida']."')";
		$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$grupoMedicamento=$this->IdentificacionGrupoMedico($_REQUEST['grupo']);
		if($grupoMedicamento['sw_medicamento']==1){
      $var=$this->HallarCodigoProducto($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codProducto']);
			$codProducto=$var['codigo_producto'];
      $this->FormaDatosMedicamentos($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codProducto'],'','','','','','','','','','','','','','','','','',$_REQUEST['codProducto'],'','',$DescripcionCompleta);
			return true;
		}else{
      $mensaje="Producto Creado en el inventario";
			$titulo="PRODUCTOS INVENTARIOS";
			$accion=ModuloGetURL('app','InvCodificacionPtos','user','LlamaFormaMostrarPrInv',array("grupo"=>$_REQUEST['grupo'],"clasePr"=>$_REQUEST['clasePr'],"subclase"=>$_REQUEST['subclase'],"NomGrupo"=>$_REQUEST['NomGrupo'],"NomClase"=>$_REQUEST['NomClase'],"NomSubClase"=>$_REQUEST['NomSubClase'],"origenFun"=>$_REQUEST['origenFun'],"codigoPro"=>$_REQUEST['codigoPro'],"descripcionPro"=>$_REQUEST['descripcionPro']));
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
	}

	function LlamaFormaMostrarPrInv(){
    $this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro']);
		return true;
	}

	function HallarCodigoProducto($grupo,$clasePr,$subclase,$codProducto){

		list($dbconn) = GetDBconn();
		$query="SELECT codigo_producto FROM inventarios_productos WHERE grupo_id='$grupo' AND clase_id='$clasePr' AND subclase_id='$subclase' AND producto_id='$codProducto'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'planes' esta vacia ";
				return false;
			}else{
        $vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
		return $vars;
	}

	function LLamaAdicionCancelacionClas(){
    $this->MenuInventariosPrincipal();
		return true;
	}
/**
* Funcion donde es posible editar los nombres de la clasificacion
* @return boolean;
*/
	function EditarClasificacion(){
		$this->FormaEditarClasificacion($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],$_REQUEST['bandera']);
		return true;
	}
/**
* Funcion que consulta los productos y los atributos de los productos existentes en el inventario
* @return array;
* @param string empresa en la que el usuario esta trabajando;
*/
	function TotalInventarioProductosInv($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$codigoProAlterno){
		
		list($dbconn) = GetDBconn();
		
		$queryBuqueda=$this->HallarQueryBusqueda($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$codigoProAlterno);
    if(empty($_REQUEST['conteo']))
		{
				$query = "SELECT count(*) FROM inventarios_productos z,inv_grupos_inventarios y,
				inv_clases_inventarios l,inv_subclases_inventarios as c,unidades m,inv_fabricantes e
				WHERE z.grupo_id=y.grupo_id AND z.grupo_id=l.grupo_id AND z.clase_id=l.clase_id
				AND z.grupo_id=c.grupo_id AND z.clase_id=c.clase_id AND z.subclase_id=c.subclase_id
				AND m.unidad_id=z.unidad_id  AND e.fabricante_id=z.fabricante_id $queryBuqueda";
				$result = $dbconn->Execute($query);
				if($result->EOF){
					$this->error = "Error al ejecutar la consulta.<br>";
					$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
					return false;
				}
				list($this->conteo)=$result->fetchRow();
     }
		 else
		 {
        $this->conteo=$_REQUEST['conteo'];

		 }

		 if(!$_REQUEST['Of'])
		 {
        $Of='0';
		 }
		 else
		 {
       $Of=$_REQUEST['Of'];
		 }

	  $query = "SELECT 	z.codigo_producto,
				z.descripcion,
				e.descripcion as fabricante,
				m.descripcion as unidad,
				z.porc_iva,
				z.estado,
				y.sw_medicamento,
				ME.concentracion_forma_farmacologica,
				ME.descripcion as forma_farmacologica
		FROM	inventarios_productos z
				LEFT JOIN (SELECT 	a.codigo_medicamento,
									a.concentracion_forma_farmacologica,
									b.descripcion
							FROM	medicamentos a,
									inv_med_cod_forma_farmacologica b
							WHERE	a.cod_forma_farmacologica = b.cod_forma_farmacologica) as ME
							ON (ME.codigo_medicamento = z.codigo_producto),
				inv_grupos_inventarios y,
				inv_clases_inventarios l,
				inv_subclases_inventarios as c,
				unidades m,
				inv_fabricantes e
		WHERE 	z.grupo_id=y.grupo_id 
		AND 	z.grupo_id=l.grupo_id 
		AND 	z.clase_id=l.clase_id 
		AND 	z.grupo_id=c.grupo_id 
		AND 	z.clase_id=c.clase_id 
		AND		z.subclase_id=c.subclase_id 
		AND 	m.unidad_id=z.unidad_id  
		AND 	e.fabricante_id=z.fabricante_id
		$queryBuqueda LIMIT " . $this->limit . " OFFSET $Of" ;
		
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
			return $vars;
		}
	}

	function HallarQueryBusqueda($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$codigoProAlterno){

		if($grupo){
      $query.=" AND z.grupo_id='$grupo'";
		}
		if($clasePr){
      $query.=" AND z.clase_id='$clasePr'";
		}
		if($subclase){
      $query.=" AND z.subclase_id='$subclase'";
		}
		if($codigoPro){
      $query.=" AND z.codigo_producto LIKE '$codigoPro%'";
		}
		if($descripcionPro){
      $query.=" AND z.descripcion LIKE '%$descripcionPro%'";
		}
		if($codigoProAlterno){
      $query.=" AND z.cod_ihosp LIKE '%$codigoProAlterno%'";
		}

		return $query;
	}

	function DatosProductoInventarioCodifi($codigoProducto){

	  $query="SELECT a.grupo_id,b.descripcion as nomgrupo,a.clase_id,c.descripcion as nomclase,a.subclase_id,d.descripcion as nomsubclase,a.producto_id,a.descripcion,a.descripcion_abreviada,a.fabricante_id,e.descripcion as nomfabricante,a.unidad_id,a.contenido_unidad_venta,a.porc_iva,b.sw_medicamento,a.codigo_invima,a.sw_control_fecha_vencimiento  FROM inventarios_productos a,inv_grupos_inventarios b,inv_clases_inventarios c,inv_subclases_inventarios d,inv_fabricantes e WHERE a.codigo_producto='$codigoProducto' AND a.grupo_id=b.grupo_id AND a.grupo_id=c.grupo_id AND a.clase_id=c.clase_id AND a.grupo_id=d.grupo_id AND a.clase_id=d.clase_id AND a.subclase_id=d.subclase_id AND a.fabricante_id=e.fabricante_id";
    list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}

/**
* Funcion que consulta las diferentes unidades de medida existentes en la base de datos
* @return array;
*/
	function UnidadesMedida(){
		list($dbconn) = GetDBconn();
		$query="SELECT unidad_id,descripcion FROM unidades";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'planes' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
		return $vars;
	}

	function DatosDelMedicamento($codigoPrincipal){
	  list($dbconn) = GetDBconn();
		$query="SELECT cod_anatomofarmacologico,cod_principio_activo,cod_forma_farmacologica,cod_concentracion,concentracion_forma_farmacologica,unidad_medida_medicamento_id,factor_conversion,factor_equivalente_mg,sw_pos,sw_uso_controlado,sw_antibiotico,sw_liquidos_electrolitos,dias_previos_vencimiento,sw_fotosensible,sw_refrigerado,sw_alimento_parenteral,sw_alimento_enteral,codigo_cum
		FROM medicamentos WHERE codigo_medicamento='$codigoPrincipal'";
    $result=$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		return $vars;
	}
	function AnatomoFarmacologicos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT cod_anatomofarmacologico,descripcion FROM inv_med_cod_anatofarmacologico ORDER BY descripcion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'inv_med_cod_anatofarmacologico' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

  function PrincipiosActivos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT cod_principio_activo,descripcion FROM inv_med_cod_principios_activos ORDER BY descripcion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'inv_med_cod_principios_activos' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function FormasFarmacologicas(){
		list($dbconn) = GetDBconn();
		$query = "SELECT cod_forma_farmacologica,descripcion FROM inv_med_cod_forma_farmacologica ORDER BY descripcion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'inv_med_cod_forma_farmacologica' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function ViasAdministracionMedicamento(){
		list($dbconn) = GetDBconn();
		$query = "SELECT via_administracion_id,nombre FROM hc_vias_administracion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'inv_med_cod_forma_farmacologica' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function modificacionProductoCodificacion(){
	  $paso=$_REQUEST['paso'];
    $Of=$_REQUEST['Of'];
	  if($_REQUEST['Regresar']){
      $this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda']);
			return true;
		}
		if($_REQUEST['adicionAnatomo']){
		  $action=ModuloGetURL('app','InvCodificacionPtos','user','ModificacionDatosMedicamentos',array("NomGrupo"=>$_REQUEST['NomGrupo'],"grupo"=>$_REQUEST['grupo'],
			"NomClase"=>$_REQUEST['NomClase'],"clasePr"=>$_REQUEST['clasePr'],"NomSubClase"=>$_REQUEST['NomSubClase'],"subclase"=>$_REQUEST['subclase'],
			"codProducto"=>$_REQUEST['codProducto'],"DescripcionCompleta"=>$_REQUEST['DescripcionCompleta'],"DescripcionAbreviada"=>$_REQUEST['DescripcionAbreviada'],
			"valorFab"=>$_REQUEST['valorFab'],"fabricante"=>$_REQUEST['fabricante'],"unidad"=>$_REQUEST['unidad'],"cantidadUnidadMedida"=>$_REQUEST['cantidadUnidadMedida'],"PorcentajeIva"=>$_REQUEST['PorcentajeIva'],"codigoInvima"=>$_REQUEST['codigoInvima'],"fechaVencimiento"=>$_REQUEST['fechaVencimiento'],
			"codigoPrincipal"=>$_REQUEST['codigoPrincipal'],"medicamento"=>$_REQUEST['medicamento'],"anatomofarmacologico"=>$_REQUEST['anatomofarmacologico'],
			"principioactivo"=>$_REQUEST['principioactivo'],"FormasFarmacologica"=>$_REQUEST['FormasFarmacologica'],"concentracion"=>$_REQUEST['concentracion'],"concentracionFormaF"=>$_REQUEST['concentracionFormaF'],"medidaMedicamento"=>$_REQUEST['medidaMedicamento'],"factorConversion"=>$_REQUEST['factorConversion'],"factorEquivmg"=>$_REQUEST['factorEquivmg'],"viaAdministracion"=>$_REQUEST['viaAdministracion'],
			"pos"=>$_REQUEST['pos'],"usoControlado"=>$_REQUEST['usoControlado'],"antibiotico"=>$_REQUEST['antibiotico'],"fotosensible"=>$_REQUEST['fotosensible'],"refrigerado"=>$_REQUEST['refrigerado'],"alimparenteral"=>$_REQUEST['alimparenteral'],"alimenteral"=>$_REQUEST['alimenteral'],"solucion"=>$_REQUEST['solucion'],"diasPrevios"=>$_REQUEST['diasPrevios'],"codigoBusqueda"=>$_REQUEST['codigoBusqueda'],"descripcionBusqueda"=>$_REQUEST['descripcionBusqueda'],"bandera"=>1,
			 "codigoAnterior"=>$_REQUEST['codigoAnterior'],"GrupoAnterior"=>$_REQUEST['GrupoAnterior'],"ClaseAnterior"=>$_REQUEST['ClaseAnterior'],"SubClaseAnterior"=>$_REQUEST['SubClaseAnterior'],"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"origenFun"=>$_REQUEST['origenFun']));
      $this->FormaDatosAdicionAnatomo('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$action);
			return true;
		}
		if($_REQUEST['adicionPrincipio']){
      $action=ModuloGetURL('app','InvCodificacionPtos','user','ModificacionDatosMedicamentos',array("NomGrupo"=>$_REQUEST['NomGrupo'],"grupo"=>$_REQUEST['grupo'],
			"NomClase"=>$_REQUEST['NomClase'],"clasePr"=>$_REQUEST['clasePr'],"NomSubClase"=>$_REQUEST['NomSubClase'],"subclase"=>$_REQUEST['subclase'],
			"codProducto"=>$_REQUEST['codProducto'],"DescripcionCompleta"=>$_REQUEST['DescripcionCompleta'],"DescripcionAbreviada"=>$_REQUEST['DescripcionAbreviada'],
			"valorFab"=>$_REQUEST['valorFab'],"fabricante"=>$_REQUEST['fabricante'],"unidad"=>$_REQUEST['unidad'],"cantidadUnidadMedida"=>$_REQUEST['cantidadUnidadMedida'],"PorcentajeIva"=>$_REQUEST['PorcentajeIva'],"codigoInvima"=>$_REQUEST['codigoInvima'],"fechaVencimiento"=>$_REQUEST['fechaVencimiento'],
			"codigoPrincipal"=>$_REQUEST['codigoPrincipal'],"medicamento"=>$_REQUEST['medicamento'],"anatomofarmacologico"=>$_REQUEST['anatomofarmacologico'],
			"principioactivo"=>$_REQUEST['principioactivo'],"FormasFarmacologica"=>$_REQUEST['FormasFarmacologica'],"concentracion"=>$_REQUEST['concentracion'],"concentracionFormaF"=>$_REQUEST['concentracionFormaF'],"medidaMedicamento"=>$_REQUEST['medidaMedicamento'],"factorConversion"=>$_REQUEST['factorConversion'],"factorEquivmg"=>$_REQUEST['factorEquivmg'],"viaAdministracion"=>$_REQUEST['viaAdministracion'],
			"pos"=>$_REQUEST['pos'],"usoControlado"=>$_REQUEST['usoControlado'],"antibiotico"=>$_REQUEST['antibiotico'],"fotosensible"=>$_REQUEST['fotosensible'],"refrigerado"=>$_REQUEST['refrigerado'],"alimparenteral"=>$_REQUEST['alimparenteral'],"alimenteral"=>$_REQUEST['alimenteral'],"solucion"=>$_REQUEST['solucion'],"diasPrevios"=>$_REQUEST['diasPrevios'],"codigoBusqueda"=>$_REQUEST['codigoBusqueda'],"descripcionBusqueda"=>$_REQUEST['descripcionBusqueda'],"bandera"=>2,
			"codigoAnterior"=>$_REQUEST['codigoAnterior'],"GrupoAnterior"=>$_REQUEST['GrupoAnterior'],"ClaseAnterior"=>$_REQUEST['ClaseAnterior'],"SubClaseAnterior"=>$_REQUEST['SubClaseAnterior'],"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"origenFun"=>$_REQUEST['origenFun']));
      $this->FormaDatosAdicionPrincipioActivo('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$action);
			return true;
		}
		if($_REQUEST['adicionForma']){
		  $action=ModuloGetURL('app','InvCodificacionPtos','user','ModificacionDatosMedicamentos',array("NomGrupo"=>$_REQUEST['NomGrupo'],"grupo"=>$_REQUEST['grupo'],
			"NomClase"=>$_REQUEST['NomClase'],"clasePr"=>$_REQUEST['clasePr'],"NomSubClase"=>$_REQUEST['NomSubClase'],"subclase"=>$_REQUEST['subclase'],
			"codProducto"=>$_REQUEST['codProducto'],"DescripcionCompleta"=>$_REQUEST['DescripcionCompleta'],"DescripcionAbreviada"=>$_REQUEST['DescripcionAbreviada'],
			"valorFab"=>$_REQUEST['valorFab'],"fabricante"=>$_REQUEST['fabricante'],"unidad"=>$_REQUEST['unidad'],"cantidadUnidadMedida"=>$_REQUEST['cantidadUnidadMedida'],"PorcentajeIva"=>$_REQUEST['PorcentajeIva'],"codigoInvima"=>$_REQUEST['codigoInvima'],"fechaVencimiento"=>$_REQUEST['fechaVencimiento'],
			"codigoPrincipal"=>$_REQUEST['codigoPrincipal'],"medicamento"=>$_REQUEST['medicamento'],"anatomofarmacologico"=>$_REQUEST['anatomofarmacologico'],
			"principioactivo"=>$_REQUEST['principioactivo'],"FormasFarmacologica"=>$_REQUEST['FormasFarmacologica'],"concentracion"=>$_REQUEST['concentracion'],"concentracionFormaF"=>$_REQUEST['concentracionFormaF'],"medidaMedicamento"=>$_REQUEST['medidaMedicamento'],"factorConversion"=>$_REQUEST['factorConversion'],"factorEquivmg"=>$_REQUEST['factorEquivmg'],"viaAdministracion"=>$_REQUEST['viaAdministracion'],
			"pos"=>$_REQUEST['pos'],"usoControlado"=>$_REQUEST['usoControlado'],"antibiotico"=>$_REQUEST['antibiotico'],"fotosensible"=>$_REQUEST['fotosensible'],"refrigerado"=>$_REQUEST['refrigerado'],"alimparenteral"=>$_REQUEST['alimparenteral'],"alimenteral"=>$_REQUEST['alimenteral'],"solucion"=>$_REQUEST['solucion'],"diasPrevios"=>$_REQUEST['diasPrevios'],"codigoBusqueda"=>$_REQUEST['codigoBusqueda'],"descripcionBusqueda"=>$_REQUEST['descripcionBusqueda'],"bandera"=>3,
			"codigoAnterior"=>$_REQUEST['codigoAnterior'],"GrupoAnterior"=>$_REQUEST['GrupoAnterior'],"ClaseAnterior"=>$_REQUEST['ClaseAnterior'],"SubClaseAnterior"=>$_REQUEST['SubClaseAnterior'],"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"origenFun"=>$_REQUEST['origenFun']));
      $this->FormaDatosAdicionFormaFarma('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$action);
			return true;
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();


    if(($_REQUEST['codigoAnterior']!=$_REQUEST['codProducto']) || ($_REQUEST['grupo']!=$_REQUEST['GrupoAnterior']) || ($_REQUEST['clasePr']!=$_REQUEST['ClaseAnterior']) || ($_REQUEST['subclase']!=$_REQUEST['SubClaseAnterior'])){
			$existePr=$this->existenciaProductosInv($_REQUEST['codProducto'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase']);
			if($existePr==1){
				$this->frmError["codProducto"]=1;
				$this->frmError["MensajeError"]="El Codigo del Producto ya Existe en la Base de Datos Para esta Clasificacion.";
				$this->FormaEditarProductoInventarioCodifi($_REQUEST['NomGrupo'],$_REQUEST['grupo'],
				$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
				$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],
				$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
				$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],
				$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
				$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],
				$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],$paso,$Of,$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
				return true;
			}
		}
		if(empty($_REQUEST['DescripcionAbreviada'])){
      $_REQUEST['DescripcionAbreviada']=substr($_REQUEST['DescripcionCompleta'],0,30);
		}
		if(!$_REQUEST['codProducto'] || !$_REQUEST['DescripcionCompleta'] || !$_REQUEST['DescripcionAbreviada'] ||
		  $_REQUEST['unidad']==-1 || !$_REQUEST['PorcentajeIva'] || !$_REQUEST['fabricante'] || !$_REQUEST['grupo'] ||
			!$_REQUEST['clasePr'] || !$_REQUEST['subclase']){
			if(!$_REQUEST['codProducto']){$this->frmError["codProducto"]=1; }
			if(!$_REQUEST['DescripcionCompleta']){$this->frmError["DescripcionCompleta"]=1; }
			if(!$_REQUEST['DescripcionAbreviada']){$this->frmError["DescripcionAbreviada"]=1; }
			if($_REQUEST['unidad']==-1){$this->frmError["unidad"]=1; }
			if(!$_REQUEST['PorcentajeIva']){$this->frmError["PorcentajeIva"]=1; }
			if(!$_REQUEST['fabricante']){$this->frmError["fabricante"]=1; }
			if(!$_REQUEST['grupo']){$this->frmError["grupo"]=1; }
			if(!$_REQUEST['clasePr']){$this->frmError["clasePr"]=1; }
			if(!$_REQUEST['subclase']){$this->frmError["subclase"]=1; }
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			$this->FormaEditarProductoInventarioCodifi($_REQUEST['NomGrupo'],$_REQUEST['grupo'],
			$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
			$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],
			$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
			$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],
			$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
			$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],
			$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],$paso,$Of,$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
			return true;
		}

    $medic=$this->IdentificacionGrupoMedico($_REQUEST['grupo']);
		if($medic['sw_medicamento']){
      $_REQUEST['medicamento']=1;
		}else{
      $_REQUEST['medicamento']=0;
		}
    if(empty($_REQUEST['medicamento'])){
		  $query="SELECT * FROM medicamentos WHERE codigo_medicamento='".$_REQUEST['codigoPrincipal']."'";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$datos=$result->RecordCount();
				if($datos){
					$mensaje="Este Producto esta Registrado como Medicamento, Y se Eliminar???, Si esta seguro de Este Cambio de click en Aceptar sino de clikc en Cancelar";
					$titulo="CODIFICACION PRODUCTOS";
					$arreglo=array('codProducto'=>$_REQUEST['codProducto'],'DescripcionCompleta'=>$_REQUEST['DescripcionCompleta'],'DescripcionAbreviada'=>$_REQUEST['DescripcionAbreviada'],
					'valorFab'=>$_REQUEST['valorFab'],'unidad'=>$_REQUEST['unidad'],"cantidadUnidadMedida"=>$_REQUEST['cantidadUnidadMedida'],'PorcentajeIva'=>$_REQUEST['PorcentajeIva'],'codigoInvima'=>$_REQUEST['codigoInvima'],"fechaVencimiento"=>$_REQUEST['fechaVencimiento'],'codigoPrincipal'=>$_REQUEST['codigoPrincipal'],
					'grupo'=>$_REQUEST['grupo'],'clasePr'=>$_REQUEST['clasePr'],'subclase'=>$_REQUEST['subclase'],'NomGrupo'=>$_REQUEST['NomGrupo'],'NomClase'=>$_REQUEST['NomClase'],'NomSubClase'=>$_REQUEST['NomSubClase'],'codigoBusqueda'=>$_REQUEST['codigoBusqueda'],'descripcionBusqueda'=>$_REQUEST['descripcionBusqueda'],"origenFun"=>$_REQUEST['origenFun']);
					$this->LlamaConfirmarAccion($arreglo,'','app','InvCodificacionPtos','BorraMedicamentoXCambioGrupo','CancelBorraMedicamentoXCambioGrupo',$mensaje,$titulo,'ACEPTAR','CANCELAR');
					return true;
				}else{
				  if($_REQUEST['fechaVencimiento']){$fechaVencimiento='1';}else{$fechaVencimiento='0';}
				  $query="UPDATE inventarios_productos SET grupo_id='".$_REQUEST['grupo']."',
					clase_id='".$_REQUEST['clasePr']."',subclase_id='".$_REQUEST['subclase']."',
					producto_id='".$_REQUEST['codProducto']."',descripcion='".$_REQUEST['DescripcionCompleta']."',
					descripcion_abreviada='".$_REQUEST['DescripcionAbreviada']."',fabricante_id='".$_REQUEST['valorFab']."',
					unidad_id='".$_REQUEST['unidad']."',contenido_unidad_venta='".$_REQUEST['cantidadUnidadMedida']."',porc_iva='".$_REQUEST['PorcentajeIva']."',codigo_invima='".$_REQUEST['codigoInvima']."',sw_control_fecha_vencimiento='$fechaVencimiento' WHERE codigo_producto='".$_REQUEST['codigoPrincipal']."'";
					$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}
				}
				$dbconn->CommitTrans();
			}
		}else{
			if($_REQUEST['pos']){$pos=1;}else{$pos=0;}
			if($_REQUEST['solucion']){$solucion=1;}else{$solucion=0;}
      if($_REQUEST['usoControlado']){$usoControlado=1;}else{$usoControlado=0;}
			if($_REQUEST['antibiotico']){$antibiotico=1;}else{$antibiotico=0;}
			if($_REQUEST['fotosensible']){$fotosensible=1;}else{$fotosensible=0;}
			if($_REQUEST['refrigerado']){$refrigerado=1;}else{$refrigerado;}
			if($_REQUEST['alimparenteral']){$alimparenteral=1;}else{$alimparenteral;}
			if($_REQUEST['alimenteral']){$alimenteral=1;}else{$alimenteral;}
			if($_REQUEST['anatomofarmacologico']==-1 ||
			!$_REQUEST['anatomofarmacologico'] || $_REQUEST['principioactivo']==-1 ||
			!$_REQUEST['principioactivo'] || $_REQUEST['FormasFarmacologica']==-1 ||
			!$_REQUEST['FormasFarmacologica']){
				if($_REQUEST['anatomofarmacologico']==-1 || !$_REQUEST['anatomofarmacologico']){$this->frmError["anatomofarmacologico"]=1;}
				if($_REQUEST['principioactivo']==-1 || !$_REQUEST['principioactivo']){$this->frmError["principioactivo"]=1;}
				if($_REQUEST['FormasFarmacologica']==-1 || !$_REQUEST['FormasFarmacologica']){$this->frmError["FormasFarmacologica"]=1;}
				$this->medicamentofrmError["MensajeError"]="Es imposible dejar de Seleccionar este elemento pues es un Dato Obligatorio.";
				$this->FormaEditarProductoInventarioCodifi($_REQUEST['NomGrupo'],$_REQUEST['grupo'],
				$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
				$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],
				$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
				$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],
				$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
				$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],
				$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],$paso,$Of,$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
				return true;
			}
			if($_REQUEST['fechaVencimiento']){$fechaVencimiento='1';}else{$fechaVencimiento='0';}
			$query="UPDATE inventarios_productos SET grupo_id='".$_REQUEST['grupo']."',
			clase_id='".$_REQUEST['clasePr']."',subclase_id='".$_REQUEST['subclase']."',
			producto_id='".$_REQUEST['codProducto']."',descripcion='".$_REQUEST['DescripcionCompleta']."',
			descripcion_abreviada='".$_REQUEST['DescripcionAbreviada']."',fabricante_id='".$_REQUEST['valorFab']."',
			unidad_id='".$_REQUEST['unidad']."',contenido_unidad_venta='".$_REQUEST['cantidadUnidadMedida']."',porc_iva='".$_REQUEST['PorcentajeIva']."',codigo_invima='".$_REQUEST['codigoInvima']."',sw_control_fecha_vencimiento='$fechaVencimiento' WHERE codigo_producto='".$_REQUEST['codigoPrincipal']."'";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
			  $query="SELECT codigo_producto FROM inventarios_productos WHERE grupo_id='".$_REQUEST['grupo']."' AND clase_id='".$_REQUEST['clasePr']."' AND subclase_id='".$_REQUEST['subclase']."' AND producto_id='".$_REQUEST['codProducto']."'";
				$result=$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}else{
          $datos=$result->RecordCount();
					if($datos){
						$vars=$result->GetRowAssoc($toUpper=false);
					}
					$query="SELECT * FROM medicamentos WHERE codigo_medicamento='".$vars['codigo_producto']."'";
					$result=$dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al Guardar en la Base de Datos";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						$dbconn->RollbackTrans();
						return false;
					}else{
					  if($_REQUEST['medidaMedicamento']==-1){$medidaMedicamento1='NULL';}else{$medidaMedicamento=$_REQUEST['medidaMedicamento'];$medidaMedicamento1="'$medidaMedicamento'";}
						if(empty($_REQUEST['factorConversion'])){$factorConversion1='NULL';}else{$factorConversion=$_REQUEST['factorConversion'];$factorConversion1="'$factorConversion'";}
		        if(empty($_REQUEST['factorEquivmg'])){$factorEquivmg1='NULL';}else{$factorEquivmg=$_REQUEST['factorEquivmg'];$factorEquivmg1="'$factorEquivmg'";}
            if(empty($_REQUEST['diasPrevios'])){$diasPrevios=0;}else{$diasPrevios=$_REQUEST['diasPrevios'];}
						$datos=$result->RecordCount();
						if(!$datos){
							$query="INSERT INTO medicamentos(codigo_medicamento,
							                                sw_liquidos_electrolitos,
																							cod_anatomofarmacologico,
																							cod_principio_activo,
																							cod_forma_farmacologica,
																							cod_concentracion,
																							concentracion_forma_farmacologica,
																							sw_uso_controlado,
																							sw_antibiotico,
																							sw_fotosensible,
																							sw_refrigerado,
																							sw_alimento_parenteral,
																							sw_alimento_enteral,
																							sw_pos,
																							unidad_medida_medicamento_id,
																							factor_conversion,
																							factor_equivalente_mg,
																							dias_previos_vencimiento)VALUES('".$vars['codigo_producto']."',
																							'$solucion',
																							'".$_REQUEST['anatomofarmacologico']."',
																							'".$_REQUEST['principioactivo']."',
																							'".$_REQUEST['FormasFarmacologica']."',
																							'".$_REQUEST['concentracionFormaF']."',
																							'".$_REQUEST['concentracion']."',
																							'$usoControlado',
																							'$antibiotico',
                                              '$fotosensible',
																							'$refrigerado',
                                              '$alimparenteral',
																							'$alimenteral',
																							'$pos',
																							$medidaMedicamento1,
																							$factorConversion1,
																							$factorEquivmg1,
																							'$diasPrevios')";
							$dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}else{
              $query="UPDATE medicamentos SET sw_liquidos_electrolitos='$solucion',
				cod_anatomofarmacologico='".$_REQUEST['anatomofarmacologico']."',
				cod_principio_activo='".$_REQUEST['principioactivo']."',
				cod_forma_farmacologica='".$_REQUEST['FormasFarmacologica']."',
				cod_concentracion='".$_REQUEST['concentracionFormaF']."',
				concentracion_forma_farmacologica='".$_REQUEST['concentracion']."',
				sw_uso_controlado='$usoControlado',
				sw_antibiotico='$antibiotico',
				sw_fotosensible='$fotosensible',
				sw_refrigerado='$refrigerado',
				sw_alimento_parenteral='$alimparenteral',
				sw_alimento_enteral='$alimenteral',
				sw_pos='$pos',
				unidad_medida_medicamento_id=$medidaMedicamento1,
				factor_conversion=$factorConversion1,
				factor_equivalente_mg=$factorEquivmg1,
				dias_previos_vencimiento='$diasPrevios',
				codigo_cum = '".$_REQUEST['CodigoCum']."'
			WHERE codigo_medicamento='".$vars['codigo_producto']."'";

							$dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
            $query="DELETE FROM inv_medicamentos_vias_administracion WHERE codigo_medicamento='".$vars['codigo_producto']."'";
						$dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al Guardar en la Base de Datos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							$dbconn->RollbackTrans();
							return false;
						}
						$vias=$_REQUEST['vias'];
						for($i=0;$i<sizeof($vias);$i++){
              $query="INSERT INTO inv_medicamentos_vias_administracion(codigo_medicamento,via_administracion_id)VALUES
							('".$vars['codigo_producto']."','".$vias[$i]."')";
						  $dbconn->Execute($query);
							if($dbconn->ErrorNo() != 0){
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								$dbconn->RollbackTrans();
								return false;
							}
						}
					}
				}
				$dbconn->CommitTrans();
			}
		}
		$this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda']);
		return true;
	}

	function IdentificacionGrupoMedico($grupo){

		list($dbconn) = GetDBconn();
		$query="SELECT sw_medicamento FROM inv_grupos_inventarios WHERE grupo_id='$grupo'";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'planes' esta vacia ";
				return false;
			}else{
        $vars=$result->GetRowAssoc($toUpper=false);
			}
		}
		$result->Close();
		return $vars;
	}

	function existenciaProductosInv($codProducto,$grupo,$clasePr,$subclase){

		list($dbconn) = GetDBconn();
		$query="SELECT * FROM inventarios_productos WHERE producto_id='$codProducto' AND grupo_id='$grupo' AND clase_id='$clasePr' AND subclase_id='$subclase'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  $retorno=1;
			}else{
        $retorno=0;
			}
		  $result->Close();
		  return $retorno;
		}
	}

/**
* Funcion que confirma la eliminacion de un registro de la base de datos
* @return boolean
*/
	function LlamaConfirmarAccion($arreglo,$Cuenta,$c,$m,$me,$me2,$mensaje,$Titulo,$boton1,$boton2){
		if(empty($Titulo)){
			$arreglo=$_REQUEST['arreglo'];
			$Cuenta=$_REQUEST['Cuenta'];
			$c=$_REQUEST['c'];
			$m=$_REQUEST['m'];
			$me=$_REQUEST['me'];
			$me2=$_REQUEST['me2'];
			$mensaje=$_REQUEST['mensaje'];
			$Titulo=$_REQUEST['titulo'];
			$boton1=$_REQUEST['boton1'];
			$boton2=$_REQUEST['boton2'];
		}
		$this->salida=ConfirmarAccion($Titulo,$mensaje,$boton1,$boton2,array($c,$m,'user',$me,$arreglo),array($c,$m,'user',$me2,$arreglo));
		return true;
	}

	function BorraMedicamentoXCambioGrupo(){
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		if($_REQUEST['fechaVencimiento']){$fechaVencimiento='1';}else{$fechaVencimiento='0';}
		$query="UPDATE inventarios_productos SET grupo_id='".$_REQUEST['grupo']."',
		clase_id='".$_REQUEST['clasePr']."',subclase_id='".$_REQUEST['subclase']."',
		producto_id='".$_REQUEST['codProducto']."',descripcion='".$_REQUEST['DescripcionCompleta']."',
		descripcion_abreviada='".$_REQUEST['DescripcionAbreviada']."',fabricante_id='".$_REQUEST['valorFab']."',
		unidad_id='".$_REQUEST['unidad']."',contenido_unidad_venta='".$_REQUEST['cantidadUnidadMedida']."',porc_iva='".$_REQUEST['PorcentajeIva']."',codigo_invima='".$_REQUEST['codigoInvima']."',sw_control_fecha_vencimiento='$fechaVencimiento' WHERE codigo_producto='".$_REQUEST['codigoPrincipal']."'";
		$dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Guardar en la Base de Datos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$query="DELETE FROM medicamentos WHERE codigo_medicamento='".$_REQUEST['codigoPrincipal']."'";
			$result=$dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Guardar en la Base de Datos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		$this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda']);
		return true;
	}

	function CancelBorraMedicamentoXCambioGrupo(){
    $this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['origenFun'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda']);
    return true;
	}

	function ModificacionDatosMedicamentos(){
    if($_REQUEST['Regresar']){
      $this->FormaEditarProductoInventarioCodifi($_REQUEST['NomGrupo'],$_REQUEST['grupo'],
			$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
			$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],
			$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
			$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],
			$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
			$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],
			$_REQUEST['descripcionBusqueda'],$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],'','',$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
			return true;
		}
		if($_REQUEST['bandera']==1){
		  $comprobacion=$this->ComprobarExistenciaCodigo('1',$_REQUEST['codigoAnatomo']);
			if($comprobacion==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Registro pues este codigo Ya Existe";
				$this->FormaDatosAdicionAnatomo('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$_REQUEST['action']);
				return true;
			}elseif(!$_REQUEST['codigoAnatomo'] || !$_REQUEST['descripcionAnatomo']){
			  $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
        $this->FormaDatosAdicionAnatomo('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$_REQUEST['action']);
				return true;
			}else{
        $query = "INSERT INTO inv_med_cod_anatofarmacologico(cod_anatomofarmacologico,descripcion)VALUES
			  ('".$_REQUEST['codigoAnatomo']."','".$_REQUEST['descripcionAnatomo']."')";
			}
		}elseif($_REQUEST['bandera']==2){
		  $comprobacion=$this->ComprobarExistenciaCodigo('2',$_REQUEST['codigoPActivo']);
		  if($comprobacion==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Registro pues este codigo Ya Existe";
				$this->FormaDatosAdicionPrincipioActivo('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$_REQUEST['action']);
				return true;
			}elseif(!$_REQUEST['codigoPActivo'] || !$_REQUEST['descripcionPActivo']){
        $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
				$this->FormaDatosAdicionPrincipioActivo('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$_REQUEST['action']);
				return true;
			}else{
		    $query = "INSERT INTO inv_med_cod_principios_activos(cod_principio_activo,descripcion)VALUES
			  ('".$_REQUEST['codigoPActivo']."','".$_REQUEST['descripcionPActivo']."')";
		  }
		}elseif($_REQUEST['bandera']==3){
		  $comprobacion=$this->ComprobarExistenciaCodigo('3',$_REQUEST['codigoFFarmacologica']);
		  if($comprobacion==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Registro pues este codigo Ya Existe";
				$this->FormaDatosAdicionFormaFarma('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$_REQUEST['action']);
				return true;
			}elseif(!$_REQUEST['codigoFFarmacologica']||!$_REQUEST['descripcionFFarmacologica']){
			  $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
        $this->FormaDatosAdicionFormaFarma('','','','','','','','','','','','','','','','','','','','','','','','','','','','','',$_REQUEST['action']);
				return true;
			}else{
        $query = "INSERT INTO inv_med_cod_forma_farmacologica(cod_forma_farmacologica,descripcion)VALUES
				('".$_REQUEST['codigoFFarmacologica']."','".$_REQUEST['descripcionFFarmacologica']."')";
			}
		}
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->FormaEditarProductoInventarioCodifi($_REQUEST['NomGrupo'],$_REQUEST['grupo'],
		$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
		$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],
		$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
		$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],
		$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
		$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],
		$_REQUEST['descripcionBusqueda'],$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],'','',$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
		return true;
	}

	function InsertarMedicamentoInventarios(){
		if($_REQUEST['adicionAnatomo']){
      $this->FormaDatosAdicionAnatomo($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
			return true;
		}
		if($_REQUEST['adicionPrincipio']){
      $this->FormaDatosAdicionPrincipioActivo($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
			return true;
		}
		if($_REQUEST['adicionForma']){
      $this->FormaDatosAdicionFormaFarma($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
			return true;
		}
		if($_REQUEST['pos']){$pos=1;}else{$pos=0;}
		if($_REQUEST['solucion']){$solucion=1;}else{$solucion=0;}
		if($_REQUEST['usoControlado']){$usoControlado=1;}else{$usoControlado=0;}
		if($_REQUEST['antibiotico']){$antibiotico=1;}else{$antibiotico=0;}
		if($_REQUEST['fotosensible']){$fotosensible=1;}else{$fotosensible=0;}
		if($_REQUEST['refrigerado']){$refrigerado=1;}else{$refrigerado=0;}
		if($_REQUEST['alimparenteral']){$alimparenteral=1;}else{$alimparenteral=0;}
		if($_REQUEST['alimenteral']){$alimenteral=1;}else{$alimenteral=0;}
		//if($_REQUEST['CodigoCum']){$CodigoCum=1;}else{$CodigoCum=0;}
		if($_REQUEST['anatomofarmacologico']==-1 || $_REQUEST['principioactivo']==-1 || $_REQUEST['FormasFarmacologica']==-1 || empty($_REQUEST['CodigoCum'])){
			if($_REQUEST['anatomofarmacologico']==-1){$this->frmError["anatomofarmacologico"]=1;}
			if($_REQUEST['principioactivo']==-1){$this->frmError["principioactivo"]=1;}
			if($_REQUEST['FormasFarmacologica']==-1){$this->frmError["FormasFarmacologica"]=1;}
			if(empty($_REQUEST['CodigoCum'])){$this->frmError["CodigoCum"]=1;}
			$this->frmError["MensajeError"]="Faltan datos obligatorios.";
			if(!$this->FormaDatosMedicamentos($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$pos,$usoControlado,$antibiotico,$fotosensible,$refrigerado,$alimparenteral,$alimenteral,$_REQUEST['codProducto'],$solucion,$_REQUEST['diasPrevios'],$_REQUEST['descripcion'])){
				return false;
			}
			return true;
		}
    if($_REQUEST['medidaMedicamento']==-1){$medidaMedicamento1='NULL';}else{$medidaMedicamento=$_REQUEST['medidaMedicamento'];$medidaMedicamento1="'$medidaMedicamento'";}
		if(empty($_REQUEST['factorConversion'])){$factorConversion1='NULL';}else{$factorConversion=$_REQUEST['factorConversion'];$factorConversion1="'$factorConversion'";}
		if(empty($_REQUEST['factorEquivmg'])){$factorEquivmg1='NULL';}else{$factorEquivmg=$_REQUEST['factorEquivmg'];$factorEquivmg1="'$factorEquivmg'";}
    list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT codigo_producto FROM inventarios_productos WHERE grupo_id='".$_REQUEST['grupo']."' AND clase_id='".$_REQUEST['clasePr']."' AND subclase_id='".$_REQUEST['subclase']."' AND producto_id='".$_REQUEST['codMedicamento']."'";
		$result = $dbconn->Execute($query);
		$codigoPto=$result->fields[0];
		if(!$_REQUEST['diasPrevios']){
      $_REQUEST['diasPrevios']=0;
		}
		$query="INSERT INTO medicamentos(codigo_medicamento,
																				sw_liquidos_electrolitos,
																				cod_anatomofarmacologico,
																				cod_principio_activo,
																				cod_forma_farmacologica,
																				cod_concentracion,
																				concentracion_forma_farmacologica,
																				sw_uso_controlado,
																				sw_antibiotico,
																				sw_fotosensible,
																				sw_refrigerado,
																				sw_alimento_parenteral,
																				sw_alimento_enteral,
																				sw_pos,
																				unidad_medida_medicamento_id,
																				factor_conversion,
																				factor_equivalente_mg,
																				dias_previos_vencimiento,
																				codigo_cum
																				) VALUES('$codigoPto','$solucion','".$_REQUEST['anatomofarmacologico']."','".$_REQUEST['principioactivo']."','".$_REQUEST['FormasFarmacologica']."','".$_REQUEST['concentracionFormaF']."','".$_REQUEST['concentracion']."','$usoControlado','$antibiotico','$fotosensible','$refrigerado','$alimparenteral','$alimenteral','$pos',$medidaMedicamento1,$factorConversion1,$factorEquivmg1,'".$_REQUEST['diasPrevios']."','".$_REQUEST['CodigoCum']."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$vias=$_REQUEST['vias'];
			for($i=0;$i<sizeof($vias);$i++){
				$query="INSERT INTO inv_medicamentos_vias_administracion(codigo_medicamento,via_administracion_id)VALUES
				('$codigoPto','".$vias[$i]."')";
				$dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$dbconn->CommitTrans();
		$this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase']);
		return true;
	}

	function InsertarDatosMedicamentos(){
	  if($_REQUEST['Regresar']){
      $this->FormaDatosMedicamentos($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
			return true;
		}
		if($_REQUEST['origenFuncion']==1){
		  $comprobacion=$this->ComprobarExistenciaCodigo('1',$_REQUEST['codigoAnatomo']);
			if($comprobacion==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Registro pues este codigo Ya Existe";
				$this->FormaDatosAdicionAnatomo($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				return true;
			}elseif(!$_REQUEST['codigoAnatomo'] || !$_REQUEST['descripcionAnatomo']){
			    $this->frmError["MensajeError"]="Faltan datos Obligatorios";
          $this->FormaDatosAdicionAnatomo($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				  return true;
			}else{
        $query = "INSERT INTO inv_med_cod_anatofarmacologico(cod_anatomofarmacologico,descripcion)VALUES
			  ('".$_REQUEST['codigoAnatomo']."','".$_REQUEST['descripcionAnatomo']."')";
			}
		}elseif($_REQUEST['origenFuncion']==2){
		  $comprobacion=$this->ComprobarExistenciaCodigo('2',$_REQUEST['codigoPActivo']);
		  if($comprobacion==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Registro pues este codigo Ya Existe";
				$this->FormaDatosAdicionPrincipioActivo($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				return true;
			}elseif(!$_REQUEST['codigoPActivo'] || !$_REQUEST['descripcionPActivo']){
        $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
				$this->FormaDatosAdicionPrincipioActivo($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				return true;
			}else{
		    $query = "INSERT INTO inv_med_cod_principios_activos(cod_principio_activo,descripcion)VALUES
			  ('".$_REQUEST['codigoPActivo']."','".$_REQUEST['descripcionPActivo']."')";
		  }
		}elseif($_REQUEST['origenFuncion']==3){
		  $comprobacion=$this->ComprobarExistenciaCodigo('3',$_REQUEST['codigoFFarmacologica']);
		  if($comprobacion==1){
        $this->frmError["MensajeError"]="Imposible Insertar este Registro pues este codigo Ya Existe";
				$this->FormaDatosAdicionFormaFarma($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				return true;
			}elseif(!$_REQUEST['codigoFFarmacologica'] || !$_REQUEST['descripcionFFarmacologica']){
			  $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
        $this->FormaDatosAdicionFormaFarma($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				return true;
			}else{
				$query = "INSERT INTO inv_med_cod_forma_farmacologica(cod_forma_farmacologica,descripcion)VALUES
				('".$_REQUEST['codigoFFarmacologica']."','".$_REQUEST['descripcionFFarmacologica']."')";
			}
		}
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $this->FormaDatosMedicamentos($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
		return true;
	}

	function ComprobarExistenciaCodigo($origen,$codigo){
		if($origen==1){
		  $query="SELECT * FROM inv_med_cod_anatofarmacologico WHERE cod_anatomofarmacologico='$codigo'";
		}elseif($origen==2){
		  $query="SELECT * FROM inv_med_cod_principios_activos WHERE cod_principio_activo='$codigo'";
		}elseif($origen==3){
		  $query="SELECT * FROM inv_med_cod_forma_farmacologica WHERE cod_forma_farmacologica='$codigo'";
		}
		list($dbconn) = GetDBconn();
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
      if($datos){
        return 1;
			}else{
        return 0;
			}
		}
	}

	function BusquedaBDProductosInventarios(){
		$paso=$_REQUEST['paso'];
		$Of=$_REQUEST['Of'];
		if($_REQUEST['Salir']){
        $this->MenuInventariosPrincipal();
				return true;
		}elseif($_REQUEST['CrearProducto']){
		  list($dbconn) = GetDBconn();
      $query="SELECT inv_mostrar_serial('".$_REQUEST['grupo']."','".$_REQUEST['clasePr']."','".$_REQUEST['subclase']."')";
			$result=$dbconn->Execute($query);
			$Producto=$result->fields[0];
			$OrigenFuct=1;
      $this->FormaAdicionarInventario($Producto,'','','','','','0.00',$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase'],$_REQUEST['OrigenFuct']);
			return true;
		}else{
		  if($_REQUEST['origenFun']){
				$NomGrupo=$_REQUEST['grupo'].' '.$_REQUEST['NomGrupo'];
				$NomClase=$_REQUEST['clasePr'].' '.$_REQUEST['NomClase'];
				$NomSubClase=$_REQUEST['subclase'].' '.$_REQUEST['NomSubClase'];
			}
			if(!$this->FormaMostrarPrInv($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$NomGrupo,$NomClase,$NomSubClase,$_REQUEST['origenFun'],$_REQUEST['codigoPro'],$_REQUEST['descripcionPro'])){
				return false;
			}
			return true;
		}
	}


	function AdicionarClasify(){
		$this->LlamaFormaAdicionarClasify($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],$_REQUEST['Empresa'],$_REQUEST['NombreEmp'],$_REQUEST['bandera']);
		return true;
	}

	function EliminarClasificacionSubclass(){

		list($dbconn) = GetDBconn();
    if($_REQUEST['bandera']==1){
      $query="DELETE FROM inv_grupos_inventarios WHERE  grupo_id='".$_REQUEST['grupo']."'";
		}elseif($_REQUEST['bandera']==2){
      $query="DELETE FROM inv_clases_inventarios WHERE  grupo_id='".$_REQUEST['grupo']."' AND clase_id='".$_REQUEST['claseIn']."'";
		}elseif($_REQUEST['bandera']==3){
		  $query="DELETE FROM inv_subclases_inventarios WHERE  grupo_id='".$_REQUEST['grupo']."' AND clase_id='".$_REQUEST['claseIn']."' AND subclase_id='".$_REQUEST['subclase']."'";
		}
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->ClasificacionProductoGrupo();
		return true;
	}

	function InsertarGrupoclasify(){
		$this->FormaDatosGrupoClasify();
		return true;
	}

/**
* Funcion que selecciona de la base de datos los grupo en los que se pueden calsificar los productos del inventario
* @return array;
*/
	function GruposClasificacionInv(){

		list($dbconn) = GetDBconn();
		$query = "SELECT grupo_id,descripcion FROM inv_grupos_inventarios ORDER BY grupo_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function PosibleEliminacionGrupo($GrupoId){

    list($dbconn) = GetDBconn();
		$query = "SELECT count(*) as contador FROM inv_clases_inventarios WHERE grupo_id='$GrupoId'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
		}
		$retorno=$vars['contador'];
		$result->Close();
    return $retorno;
  }
/**
* Funcion que selecciona de la base de datos las clases en los que se pueden

* clasificar los productos del inventario a patir de un grupo y un subgrupo especifico
* @return array;
* @param string grupo al que pertenecen los clases;
* @param string subgrupo al que pertenecen las clases;
*/
	function ClasesClasificacionInv($grupo){

		list($dbconn) = GetDBconn();
		$query = "SELECT clase_id,descripcion FROM inv_clases_inventarios WHERE grupo_id='$grupo' ORDER BY grupo_id,clase_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function PosibleEliminacionClase($GrupoId,$Clase){

    list($dbconn) = GetDBconn();
		$query = "SELECT count(*) as contador FROM inv_subclases_inventarios WHERE grupo_id='$GrupoId' AND clase_id='$Clase'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
		}
		$retorno=$vars['contador'];
		$result->Close();
    return $retorno;
  }

	/**
	* Funcion que selecciona de la base de datos las subclases en los que se pueden

	* clasificar los productos del inventario a patir de un grupo y un subgrupo y una clase especificas
  * @return array;
	* @param string grupo al que pertenecen las subclases;
	* @param string subgrupo al que pertenecen las subclases;
	* @param string clases a la que pertenecen las subclases;
	*/

	function SubClasesClasificacionInv($grupo,$claseInv){

		list($dbconn) = GetDBconn();
		$query = "SELECT subclase_id,descripcion FROM inv_subclases_inventarios WHERE grupo_id='$grupo' AND clase_id='$claseInv' ORDER BY grupo_id,clase_id,subclase_id";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'diagnosticos' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function VerificarEliminacionClasificacion($GrupoId,$ClaseId,$SubClaseId){

		list($dbconn) = GetDBconn();
		$query = "SELECT count(*) as contador FROM inventarios_productos WHERE grupo_id='$GrupoId' AND clase_id='$ClaseId' AND subclase_id='$SubClaseId'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
		}
		$retorno=$vars['contador'];
		$result->Close();
    return $retorno;
	}

	function AdicionDatosGrupoClasify(){
    if($_REQUEST['swmedicamento']){$swmedicamento='1';}else{$swmedicamento='0';}
		if($_REQUEST['swinsumo']){$swinsumo='1';}else{$swinsumo='0';}
    if($_REQUEST['swventa']){$swventa='1';}else{$swventa='0';}
		if($_REQUEST['Insertar']){
			if(!$_REQUEST['CodGrupo'] || !$_REQUEST['NombreGrupo']){
				if(!$_REQUEST['CodGrupo']){$this->frmError["CodGrupo"]=1;}
				if(!$_REQUEST['NombreGrupo']){$this->frmError["NombreGrupo"]=1;}
				$this->frmError["MensajeError"]="Datos Incompletos.";
				$this->FormaDatosGrupoClasify($Empresa,$NombreEmp,$_REQUEST['CodGrupo'],$_REQUEST['NombreGrupo'],$_REQUEST['swmedicamento'],$_REQUEST['swventa'],$_REQUEST['swinsumo']);
				return true;
			}
			list($dbconn) = GetDBconn();
      $respuesta=$this->ConfirmarExisteGrupo($_REQUEST['CodGrupo']);
			if($respuesta<1){
			  $query = "INSERT INTO inv_grupos_inventarios(grupo_id,descripcion,sw_medicamento,sw_vende,sw_insumos)VALUES('".$_REQUEST['CodGrupo']."','".$_REQUEST['NombreGrupo']."','$swmedicamento','$swventa','$swinsumo')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$this->ClasificacionProductoGrupo();
		    return true;
			}else{
        $mensaje="Relacion Ya Existe en la Base de Datos Verifique Por Favor";
				$titulo="CLASIFICACION ITEMS";
				$accion=ModuloGetURL('app','InvCodificacionPtos','user','main2',array('Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}
    $this->ClasificacionProductoGrupo();
		return true;
	}

	function ConfirmarExisteGrupo($CodGrupo){

    list($dbconn) = GetDBconn();
		$query = "SELECT count(*) as contador FROM inv_grupos_inventarios WHERE grupo_id='$CodGrupo'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
		}
		$retorno=$vars['contador'];
		$result->Close();
    return $retorno;
	}

	function InsertarEdicionClasificacion(){

    list($dbconn) = GetDBconn();
		if($_REQUEST['Modificar']){
		  if($_REQUEST['bandera']==1){
        if(!$_REQUEST['NombreGrupo']){
				  $this->frmError["NombreGrupo"]=1;
					$this->frmError["MensajeError"]="La Descripcion es Obligatoria.";
					$this->FormaEditarClasificacion($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],1);
					return true;
				}
        $query = "UPDATE inv_grupos_inventarios SET descripcion='".$_REQUEST['NombreGrupo']."' WHERE grupo_id='".$_REQUEST['grupo']."'";
			}elseif($_REQUEST['bandera']==2){
			  if(!$_REQUEST['NombreClase']){
				  $this->frmError["NombreClase"]=1;
					$this->frmError["MensajeError"]="La Descripcion es Obligatoria.";
					$this->FormaEditarClasificacion($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],2);
					return true;
				}
        $query = "UPDATE inv_clases_inventarios SET descripcion='".$_REQUEST['NombreClase']."' WHERE grupo_id='".$_REQUEST['grupo']."' AND clase_id='".$_REQUEST['claseIn']."'";
			}elseif($_REQUEST['bandera']==3){
			  if(!$_REQUEST['NombreSubClase']){
				  $this->frmError["NombreSubClase"]=1;
					$this->frmError["MensajeError"]="La Descripcion es Obligatoria.";
					$this->FormaEditarClasificacion($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],3);
					return true;
				}
        $query = "UPDATE inv_subclases_inventarios SET descripcion='".$_REQUEST['NombreSubClase']."' WHERE grupo_id='".$_REQUEST['grupo']."' AND clase_id='".$_REQUEST['claseIn']."' AND subclase_id='".$_REQUEST['subclase']."'";
			}
		  $result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
    $this->ClasificacionProductoGrupo();
		return true;
	}

	function InsertarNuevoItemClasificacion(){

    list($dbconn) = GetDBconn();
		if($_REQUEST['Insertar']){
		  if($_REQUEST['bandera']==1){
				if(!$_REQUEST['CodClase'] || !$_REQUEST['NombreClass']){
          if(!$_REQUEST['CodClase']){$this->frmError["CodClase"]=1;}
          if(!$_REQUEST['NombreClass']){$this->frmError["NombreClass"]=1;}
					$this->frmError["MensajeError"]="Datos Incompletos.";
					$this->LlamaFormaAdicionarClasify($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],$_REQUEST['Empresa'],$_REQUEST['NombreEmp'],$_REQUEST['bandera'],'','',$_REQUEST['CodClase'],$_REQUEST['NombreClass']);
					return true;
				}
				$respuesta=$this->ConfirmarExisteClase($_REQUEST['grupo'],$_REQUEST['CodClase']);
				if($respuesta<1){
          $query = "INSERT INTO inv_clases_inventarios(grupo_id,clase_id,descripcion) VALUES('".$_REQUEST['grupo']."','".$_REQUEST['CodClase']."','".$_REQUEST['NombreClass']."')";
				}else{
					$mensaje="Relacion Ya Existe en la Base de Datos Verifique Por Favor";
					$titulo="CLASIFICACION ITEMS";
					$accion=ModuloGetURL('app','InvCodificacionPtos','user','main2',array('Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
			}elseif($_REQUEST['bandera']==2){
			  if(!$_REQUEST['CodSubClase'] || !$_REQUEST['NombreSubClass']){
          if(!$_REQUEST['CodSubClase']){$this->frmError["CodSubClase"]=1;}
          if(!$_REQUEST['NombreSubClass']){$this->frmError["NombreSubClass"]=1;}
					$this->frmError["MensajeError"]="Datos Incompletos.";
					$this->LlamaFormaAdicionarClasify($_REQUEST['grupo'],$_REQUEST['NombreGrupo'],$_REQUEST['claseIn'],$_REQUEST['NombreClase'],$_REQUEST['subclase'],$_REQUEST['NombreSubClase'],$_REQUEST['Empresa'],$_REQUEST['NombreEmp'],$_REQUEST['bandera'],$_REQUEST['CodSubClase'],$_REQUEST['NombreSubClass']);
					return true;
				}
				$respuesta=$this->ConfirmarExisteSubClase($_REQUEST['grupo'],$_REQUEST['claseIn'],$_REQUEST['CodSubClase']);
				if($respuesta<1){
          $query = "INSERT INTO inv_subclases_inventarios(grupo_id,clase_id,subclase_id,descripcion)VALUES('".$_REQUEST['grupo']."','".$_REQUEST['claseIn']."','".$_REQUEST['CodSubClase']."','".$_REQUEST['NombreSubClass']."') ";
        }else{
				  $mensaje="Relacion Ya Existe en la Base de Datos Verifique Por Favor";
					$titulo="CLASIFICACION ITEMS";
					$accion=ModuloGetURL('app','InvCodificacionPtos','user','main2',array('Empresa'=>$Empresa,'NombreEmp'=>$NombreEmp));
					$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
					return true;
				}
			}
		  $result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0) {
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
    $this->ClasificacionProductoGrupo();
		return true;
	}

	function ConfirmarExisteSubClase($grupo,$claseIn,$CodSubClase){

    list($dbconn) = GetDBconn();
		$query = "SELECT count(*) as contador FROM inv_subclases_inventarios WHERE grupo_id='$grupo' AND clase_id='$claseIn' AND subclase_id='$CodSubClase'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
		}
		$retorno=$vars['contador'];
		$result->Close();
    return $retorno;
	}

	function ConfirmarExisteClase($grupo,$CodClase){

    list($dbconn) = GetDBconn();
		$query = "SELECT count(*) as contador FROM inv_clases_inventarios WHERE grupo_id='$grupo' AND clase_id='$CodClase'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
		}
		$retorno=$vars['contador'];
		$result->Close();
    return $retorno;
	}

	function LlamaAdicionarViaAdmonMtos(){
    $this->AdicionarViaAdmonMtos($_REQUEST['NomGrupo'],$_REQUEST['grupo'],$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
		$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
		$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
		$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],$_REQUEST['paso'],$_REQUEST['Of'],$_REQUEST['origenFun']);
		return true;
	}

	function ViasAdmonMedicamentos($codigo){
	  list($dbconn) = GetDBconn();
    $query="SELECT a.via_administracion_id,b.nombre FROM inv_medicamentos_vias_administracion a,hc_vias_administracion b WHERE a.codigo_medicamento='$codigo' AND a.via_administracion_id=b.via_administracion_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while (!$result->EOF) {
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			return $vars;
		}
	}

	function InsertarViasAdmonMedicamento(){
	  list($dbconn) = GetDBconn();
		if($_REQUEST['insertar']){
		  $datosMedica=$this->DatosDelMedicamento($_REQUEST['codigoPrincipal']);
			if(!$datosMedica){
        $this->frmError["MensajeError"]="Ingrese Primero los Datos del Medicamento, De Click en regresar";
        $this->AdicionarViaAdmonMtos($_REQUEST['NomGrupo'],$_REQUEST['grupo'],$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
				$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
				$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
				$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],$_REQUEST['paso'],$_REQUEST['Of'],$_REQUEST['origenFun']);
				return true;
			}
		  $comprobar=$this->ComprobarRelacionMedicamentoVia($_REQUEST['codigoPrincipal'],$_REQUEST['viaAdministracion']);
			if($_REQUEST['viaAdministracion']==-1 || $comprobar==1){
        $this->frmError["MensajeError"]="Imposible Insertar Esta via Pues ya Esta Relacionada con el Medicamento.";
        $this->AdicionarViaAdmonMtos($_REQUEST['NomGrupo'],$_REQUEST['grupo'],$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
				$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
				$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
				$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],$_REQUEST['SubClaseAnterior'],$_REQUEST['paso'],$_REQUEST['Of'],$_REQUEST['origenFun']);
				return true;
			}else{
				$query="INSERT INTO inv_medicamentos_vias_administracion(codigo_medicamento,via_administracion_id)
				VALUES('".$_REQUEST['codigoPrincipal']."','".$_REQUEST['viaAdministracion']."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->FormaEditarProductoInventarioCodifi($_REQUEST['NomGrupo'],$_REQUEST['grupo'],$_REQUEST['NomClase'],$_REQUEST['clasePr'],$_REQUEST['NomSubClase'],$_REQUEST['subclase'],
		$_REQUEST['codProducto'],$_REQUEST['DescripcionCompleta'],$_REQUEST['DescripcionAbreviada'],$_REQUEST['valorFab'],$_REQUEST['fabricante'],$_REQUEST['unidad'],$_REQUEST['cantidadUnidadMedida'],$_REQUEST['PorcentajeIva'],$_REQUEST['codigoInvima'],$_REQUEST['fechaVencimiento'],
		$_REQUEST['codigoPrincipal'],$_REQUEST['medicamento'],$_REQUEST['anatomofarmacologico'],$_REQUEST['principioactivo'],$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
		$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['codigoBusqueda'],$_REQUEST['descripcionBusqueda'],$_REQUEST['codigoAnterior'],$_REQUEST['GrupoAnterior'],$_REQUEST['ClaseAnterior'],
		$_REQUEST['SubClaseAnterior'],$_REQUEST['paso'],$_REQUEST['Of'],$_REQUEST['consultaForma'],$_REQUEST['origenFun']);
		return true;
	}

	function ComprobarRelacionMedicamentoVia($codigoPrincipal,$viaAdministracion){
	  list($dbconn) = GetDBconn();
    $query="SELECT * FROM inv_medicamentos_vias_administracion a WHERE a.codigo_medicamento='$codigoPrincipal' AND a.via_administracion_id='$viaAdministracion'";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  return 1;
			}else{
        return 0;
			}
		}
	}

	function ViasAdministracionMedicamentoUno(){
		list($dbconn) = GetDBconn();
		$query = "SELECT via_administracion_id,nombre FROM hc_vias_administracion";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}
 /**
* Funcion que consulta las diferentes unidades de medida existentes en la base de datos
* @return array;
*/
	function TiposAutorizacionesCompra(){
		list($dbconn) = GetDBconn();
		$query="SELECT nivel_autorizacion_id,descripcion FROM inv_niveles_autorizacion_compras";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'planes' esta vacia ";
				return false;
			}else{
        while (!$result->EOF) {
					$vars[$result->fields[0]]=$result->fields[1];
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
		return $vars;
	}

	function LlamaAdicionarViaAdmonMtosUno(){
    $this->AdicionarViaAdmonMtosUno($_REQUEST['codMedicamento'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],
		$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
		$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],
		$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
		return true;
	}

	function InsertarViasAdmonMedicamentoUno(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['insertar']){
		  $cadena=explode('/',$_REQUEST['viaAdministracion']);
      $viaAdmon=$cadena[0];
			$NombreviaAdmon=$cadena[1];
		  $comprobar=$this->ComprobarRelacionMedicamentoVia($_REQUEST['codMedicamento'],$viaAdmon);
			if($_REQUEST['viaAdministracion']==-1 || $comprobar==1){
        $this->frmError["MensajeError"]="Imposible Insertar Esta via Pues ya Esta Relacionada con el Medicamento.";
        $this->AdicionarViaAdmonMtosUno($_REQUEST['codMedicamento'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],
				$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
				$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],
				$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
				return true;
			}else{
			  $_SESSION['INVENTARIOS']['MEDICAMENTOS'][$viaAdmon]=$NombreviaAdmon;
			}
		}
		$this->FormaDatosMedicamentos($_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomSubGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase'],$_REQUEST['codMedicamento'],$_REQUEST['codAnexo'],
		$_REQUEST['FormasFarmacologica'],$_REQUEST['concentracion'],$_REQUEST['concentracionFormaF'],$_REQUEST['medidaMedicamento'],$_REQUEST['factorConversion'],$_REQUEST['factorEquivmg'],$_REQUEST['viaAdministracion'],
		$_REQUEST['principioactivo'],$_REQUEST['anatomofarmacologico'],$_REQUEST['pos'],$_REQUEST['usoControlado'],$_REQUEST['antibiotico'],$_REQUEST['fotosensible'],$_REQUEST['refrigerado'],$_REQUEST['alimparenteral'],$_REQUEST['alimenteral'],
		$_REQUEST['codProducto'],$_REQUEST['solucion'],$_REQUEST['diasPrevios'],$_REQUEST['descripcion']);
		return true;
	}

	function UnidadesMedidasMedicamentos(){
		list($dbconn) = GetDBconn();
		$query = "SELECT unidad_medida_medicamento_id,descripcion FROM inv_unidades_medida_medicamentos ORDER BY descripcion";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'unidad_medida_medicamento_id' esta vacia ";
				return false;
			}else{
        while(!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			$result->close();
			return $vars;
		}
	}

	function ClasificacionViasAdmon(){
    list($dbconn) = GetDBconn();
		$query="SELECT tipo_via_id,descripcion FROM hc_vias_administracion_tipos ORDER BY descripcion";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
		return $vars;
	}

	function ViasAdmonSegunTipo($tipoViasid){
    list($dbconn) = GetDBconn();
		$query="SELECT via_administracion_id,nombre FROM hc_vias_administracion WHERE tipo_via_id='$tipoViasid' ORDER BY nombre";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
		}
		$result->Close();
		return $vars;
	}

	function ConfirmarExisteViaMedicamento($codigoPrincipal){
    list($dbconn) = GetDBconn();
		$query="SELECT via_administracion_id FROM inv_medicamentos_vias_administracion WHERE codigo_medicamento='$codigoPrincipal'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF) {
          $vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
			  }
			}
			for($i=0;$i<sizeof($vars);$i++){
        $_SESSION['INVENTARIOS']['VIAS']['MEDICAMENTOS'][$vars[$i]['via_administracion_id']]=1;
			}
			return true;
		}
	}

}//fin clase user

?>

