<?php
/**
 * $Id: app_InvBodegas_user.php,v 1.2 2010/06/24 12:53:34 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventarios en el Sistema
 */

/**
*Contiene los metodos para realizar la administracion de los Inventarios en el sistema
*/

class app_InvBodegas_user extends classModulo
{
  var $limit;
    var $conteo;

/**
* Funcion que
* @return boolean
*/


    function app_InvBodegas_user()
    {
   $this->limit=GetLimitBrowser();
   return true;
    }
/**
* Funcion que se encarga de llamar al menu para la seleccion de la empresa,centro de utilidad y la bodega
* @return boolean
*/

    function main(){

    unset($_SESSION['BODEGAS']);
        if(!$this->FrmLogueoBodega()){
        return false;
    }
        return true;
  }
/**
* Funcion que consulta en la base de datos los permisos del usuario para trabajar con las bodegas
* @return array
*/
    function LogueoBodega()
    {
        list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
        $query = "SELECT x.empresa_id,y.razon_social as descripcion1,x.centro_utilidad,z.descripcion as descripcion2,x.bodega,l.descripcion as descripcion3  FROM bodegas_usuarios as x,empresas as y,centros_utilidad as z,bodegas as l WHERE x.usuario_id = ".UserGetUID()." AND x.empresa_id=y.empresa_id AND x.empresa_id=z.empresa_id AND x.centro_utilidad=z.centro_utilidad AND x.empresa_id=l.empresa_id AND x.centro_utilidad=l.centro_utilidad AND x.bodega=l.bodega";
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{

        while ($data = $result->FetchRow()) {
            $datos[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']]=$data;
        }

        $mtz[0]="EMPRESA";
        $mtz[1]="CENTRO UTILIDAD";
        $mtz[4]="BODEGA";

        $vars[0]=$mtz;
    $vars[1]=$datos;
        return $vars;
        }
    }

/**
* Funcion que llama a la forma del menu para la seleccion de las opciones
* @return boolean
*/

    function LlamaFormaMenu(){
    $_SESSION['BODEGAS']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
        $_SESSION['BODEGAS']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
        $_SESSION['BODEGAS']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
        $_SESSION['BODEGAS']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
        $_SESSION['BODEGAS']['BodegaId']=$_REQUEST['datos_query']['bodega'];
        $_SESSION['BODEGAS']['NombreBodega']=$_REQUEST['datos_query']['descripcion3'];
        $this->MenuInventarios();
        return true;
    }

    function LlamaMenuInventarios4(){
        $this->MenuInventarios4();
        return true;
    }

/**
* Funcion que llama a la forma que muestra las existencias de una bodega
* @return boolean
*/

    function LlamaMemuExis(){
        if(!$this->FormaExistenciasBodegas()){
            return false;
        }
        return true;
    }

    function HallarQueryBusqueda($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$filtroEstado=0,$codigoProdAlterno){

        if($grupo){
      $query.=" AND i.grupo_id='$grupo'";
        }
        if($clasePr){
      $query.=" AND i.clase_id='$clasePr'";
        }
        if($subclase){
      $query.=" AND i.subclase_id='$subclase'";
        }
        if($codigoPro){
      $query.=" AND i.codigo_producto LIKE '$codigoPro%'";
        }
        if($descripcionPro){
      $descripcionPro=strtoupper($descripcionPro);
      $query.=" AND i.descripcion LIKE '%$descripcionPro%'";
        }
    if($filtroEstado==1){
      $query.=" AND x.estado='1'";
    }
        if($codigoProdAlterno){
      $codigoProdAlterno=strtoupper($codigoProdAlterno);
      $query.=" AND i.cod_ihosp LIKE '%$codigoProdAlterno%'";
        }

        return $query;
    }


    function ConsultaExistenciasBodegas($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$filtroEstado=0,$codigoProdAlterno){

        list($dbconn) = GetDBconn();
		$query1=$this->HallarQueryBusqueda($grupo,$clasePr,$subclase,$codigoPro,$descripcionPro,$filtroEstado=0,$codigoProdAlterno);

    if(empty($_REQUEST['conteo'])){
            $query ="SELECT x.sw_control_fecha_vencimiento,x.codigo_producto,x.existencia,x.existencia_minima,x.existencia_maxima, i.descripcion as desprod,x.estado,ub.descripcion as ubicacion
            FROM existencias_bodegas as x LEFT JOIN bodegas_ubicaciones ub ON (x.ubicacion_id=ub.ubicacion_id), inventarios h,inventarios_productos i
            WHERE x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND h.codigo_producto=x.codigo_producto AND x.empresa_id=h.empresa_id AND x.codigo_producto=i.codigo_producto";
      $query.="$query1 ORDER BY i.descripcion";
            $result = $dbconn->Execute($query);
            $dat = $result->RecordCount();
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }
          $this->conteo=$dat;
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
      if(!$this->offset){
        $Of='0';
      }else{
        $Of=$this->offset;
      }
        }else{
      $Of=$_REQUEST['Of'];
        }
      $query = "SELECT 	x.sw_control_fecha_vencimiento,
				x.codigo_producto,
				CASE WHEN lote.existencia_actual IS NOT NULL THEN lote.existencia_actual
					ELSE x.existencia
					END as existencia,
				x.existencia_minima,
				x.existencia_maxima, 
				i.descripcion as desprod,
				x.estado,
				ub.descripcion as ubicacion,
				M.concentracion_forma_farmacologica,
				M.descripcion as forma_farmacologica,
				lote.lote,
				lote.fecha_vencimiento
        FROM 	existencias_bodegas as x 
				LEFT JOIN bodegas_ubicaciones ub ON (x.ubicacion_id=ub.ubicacion_id)
				LEFT JOIN existencias_bodegas_lote_fv lote ON (x.empresa_id=lote.empresa_id
														AND		x.centro_utilidad=lote.centro_utilidad
														AND		x.codigo_producto=lote.codigo_producto
														AND		x.bodega=lote.bodega), 
				inventarios h,
				inventarios_productos i
				LEFT JOIN (SELECT 	a.codigo_medicamento,
									a.	concentracion_forma_farmacologica,
									b.descripcion
							FROM	medicamentos a,
									inv_med_cod_forma_farmacologica b
							WHERE	a.cod_forma_farmacologica = b.cod_forma_farmacologica) as M
							ON (M.codigo_medicamento = i.codigo_producto)
        WHERE 	x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' 
		AND     x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' 
		AND 	x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' 
		AND     h.codigo_producto=x.codigo_producto 
		AND     x.empresa_id=h.empresa_id 
		AND     x.codigo_producto=i.codigo_producto";
        $query.="$query1 ORDER BY i.descripcion LIMIT " . $this->limit . " OFFSET $Of";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'existencias_bodegas' esta vacia ";
                return false;
            }else{
              while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
              }
            }
            $result->Close();
            return $vars;
        }
    }

    function LlamaConsultaExistenciasBodegas(){
    if($_REQUEST['Salir']){
      $this->MenuInventarios4();
            return true;
        }
        $this->FormaExistenciasBodegas($_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase'],$_REQUEST['codigoProdAlterno']);
        return true;
    }

/**
* Funcion en la que se cambia el estado de un producto existente en el inventario de un abodega
* @return boolean
*/
    function CambioEstadoProductoInv(){
        $codProd=$_REQUEST['codProd'];
        $bandera=$_REQUEST['bandera'];
        $conteo=$_REQUEST['conteo'];
        $Of=$_REQUEST['Of'];
        $paso=$_REQUEST['paso'];
        list($dbconn) = GetDBconn();
        if($bandera==1){
      $query="UPDATE existencias_bodegas SET estado='0' WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND codigo_producto='$codProd' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        }else{
      $query="UPDATE existencias_bodegas SET estado='1' WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND codigo_producto='$codProd' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        }
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if(!$this->FormaExistenciasBodegas()){
      return false;
    }
        return true;
    }

    function LlamaFormaSeleccionProductosInv(){
    $this->FormaSeleccionProductosInv();
        return true;
    }

    function LlamaFormaSeleccionProductosInvUno(){
    $this->FormaSeleccionProductosInv($_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase']);
        return true;
    }

    /**
    * Funcion que selecciona los productos en el inventario de acuerdo a los parametros de busqueda
    * @return array
    */

    function RealizarBusquedaInventarios($codigoProd,$descripcion,$grupo,$clasePr,$subclase){

        list($dbconn) = GetDBconn();
        //$queryUnoSelect=" SELECT a.codigo_producto FROM inventarios a";
    //$queryDosWhere=" WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
    if($codigoProd){
      $queryTemp.=" AND x.codigo_producto LIKE '%$codigoProd%'";
            //$queryTempDos.=" AND b.codigo_producto LIKE '%$codigoProd%'";
        }
    if($descripcion){
          $descripcion=strtoupper($descripcion);
      //$queryUnoSelect.=",inventarios_productos c";
            $queryTemp.=" AND d.descripcion LIKE '%$descripcion%'";
        }
        if($grupo && $grupo!=-1){
            $queryTemp.=" AND y.grupo_id='$grupo'";
        }
        if($clasePr && $clasePr!=-1){
            $queryTemp.=" AND l.clase_id='$clasePr'";
        }
        if($subclase && $subclase!=-1){
            $queryTemp.=" AND c.subclase_id='$subclase'";
        }
        /*if($grupo || $clasePr || $subclase){
      /*if(!$descripcion){
                $queryUnoSelect.=",inventarios_productos c";
                $queryDosWhere.=" AND a.codigo_producto=c.codigo_producto";
            }
        }*/
    //$queryTemp=$queryUnoSelect.' '.$queryDosWhere;
        return $queryTemp;
    }


    function DatosProductoInventario($codigoProd,$descripcion,$grupo,$clasePr,$subclase){

    list($dbconn) = GetDBconn();
        $queryTemp=$this->RealizarBusquedaInventarios($codigoProd,$descripcion,$grupo,$clasePr,$subclase);
    if(empty($_REQUEST['conteo'])){
      $query="SELECT count(*) FROM inventarios as x,
            inventarios_productos as d,inv_grupos_inventarios as y,inv_clases_inventarios as l,
            inv_subclases_inventarios as c WHERE x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
            x.codigo_producto=d.codigo_producto AND x.estado='1' AND d.grupo_id=y.grupo_id AND d.grupo_id=l.grupo_id AND
            d.clase_id=l.clase_id AND d.grupo_id=c.grupo_id AND d.clase_id=c.clase_id AND
            d.subclase_id=c.subclase_id
            AND x.codigo_producto NOT IN
            (SELECT b.codigo_producto
            FROM existencias_bodegas b
            WHERE b.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND b.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND  b.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
            $queryTemp";

            $result = $dbconn->Execute($query);
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }
            list($this->conteo)=$result->fetchRow();
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
      $Of='0';
        }else{
      $Of=$_REQUEST['Of'];
        }
        $query="SELECT x.codigo_producto,d.descripcion,y.descripcion as desgrupo,
        l.descripcion as desclase,c.descripcion as dessubclase FROM inventarios as x,
        inventarios_productos as d,inv_grupos_inventarios as y,inv_clases_inventarios as l,
        inv_subclases_inventarios as c WHERE x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        x.codigo_producto=d.codigo_producto AND x.estado='1' AND d.grupo_id=y.grupo_id AND d.grupo_id=l.grupo_id AND
        d.clase_id=l.clase_id AND d.grupo_id=c.grupo_id AND d.clase_id=c.clase_id AND
        d.subclase_id=c.subclase_id
        AND x.codigo_producto NOT IN
        (SELECT b.codigo_producto
        FROM existencias_bodegas b
        WHERE b.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND b.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND  b.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
        $queryTemp
        ORDER BY x.codigo_producto LIMIT " . $this->limit . " OFFSET $Of";

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

    function DestinoFormaSeleccionInv(){

        list($dbconn) = GetDBconn();
        $paso=$_REQUEST['paso'];
        $Of=$_REQUEST['Of'];
        if($_REQUEST['SalirSinGuardar']){
          unset($_SESSION['Existencias']);
            unset($_SESSION['CONTROLFECHAS']);
          $this->MenuInventarios4();
          return true;
        }
    if($_REQUEST['Salir']){
          $this->InsertarExistenciasBodega();
          unset($_SESSION['Existencias']);
            unset($_SESSION['CONTROLFECHAS']);
          $this->MenuInventarios4();
          return true;
        }
        if($_REQUEST['InsertarTotal']){
      $query="SELECT h.codigo_producto,a.sw_control_fecha_vencimiento FROM (SELECT codigo_producto FROM inventarios WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
            EXCEPT SELECT codigo_producto FROM existencias_bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
            AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."') as h,inventarios_productos a WHERE h.codigo_producto=a.codigo_producto";
            $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
              $datos=$result->RecordCount();
              if($datos){
          while(!$result->EOF){
                      $vars[]=$result->GetRowAssoc($toUpper=false);
                      $result->MoveNext();
                  }
                }
            }
            for($i=0;$i<sizeof($vars);$i++){
              $Producto=$vars[$i]['codigo_producto'];
                $fechaVenci=$vars[$i]['sw_control_fecha_vencimiento'];
        $query="INSERT INTO existencias_bodegas(empresa_id,
                                                                                                centro_utilidad,
                                                                                                codigo_producto,
                                                                                                bodega,
                                                                                                existencia,
                                                                                                existencia_minima,
                                                                                                existencia_maxima,
                                                                                                usuario_id,
                                                                                                fecha_registro,
                                                                                                estado,
                                                                                                sw_control_fecha_vencimiento)VALUES('".$_SESSION['BODEGAS']['Empresa']."','".$_SESSION['BODEGAS']['CentroUtili']."','$Producto','".$_SESSION['BODEGAS']['BodegaId']."','0.00','0.00','0.00','".UserGetUID()."','".date("Y/m/d H:i:s")."','1','$fechaVenci')";
                $result = $dbconn->Execute($query);
                
                //WS
                $resultado_ws = $this->Ejecutar_WS($query);
                
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
      $mensaje="Todos los Productos han sido Insertados Correctamente en la bodega : ".$_SESSION['BODEGAS']['NombreBodega'];
            $titulo="EXISTENCIAS BODEGA";
            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios4');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        //unset($_SESSION['Existencias']);
        //unset($_SESSION['CONTROLFECHAS']);
        if(empty($_REQUEST['paso']))
        {
            $_REQUEST['paso']=1;
        }
        foreach($_REQUEST['SeleccionActual'] as $codProductoActual=>$val){
      if(!in_array($codProductoActual,$_REQUEST['Seleccion'])){
              unset($_SESSION['Existencias'][$codProductoActual]);
            }
        }
        foreach($_REQUEST['Seleccion'] as $codProducto=>$val){
            $_SESSION['Existencias'][$codProducto]=1;
        }
        foreach($_REQUEST['controlFecha'] as $codProducto=>$val){
            $_SESSION['CONTROLFECHAS'][$codProducto]=1;
        }
        $this->FormaSeleccionProductosInv($_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase']);
        return true;
    }
    
    function Ejecutar_WS($query)
    {
        //Consumo del WS
        require_once ('nusoap/lib/nusoap.php');
        $url_wsdl = "http://dusoft.cosmitet.net/SIIS/ws/ws_ejecutar_sql.php?wsdl";
        //$url_wsdl = "http://10.0.1.80/SIIS/ws/ws_ejecutar_sql.php?wsdl";
        $soapclient = new nusoap_client($url_wsdl,true);
        $function = "ejecutar_query";
        $inputs = array('sql' => $query);
        $resultado = $soapclient->call($function,$inputs);

        return $resultado;
    }

    function InsertarExistenciasBodega(){
      list($dbconn) = GetDBconn();
    if(sizeof($_SESSION['Existencias'])>0){
            foreach($_SESSION['Existencias'] as $Producto=>$z){
                if($_SESSION['CONTROLFECHAS'][$Producto]==1){$fechaVenci='1';}else{$fechaVenci='0';}
                $query="INSERT INTO existencias_bodegas(empresa_id,
                                                                                                centro_utilidad,
                                                                                                codigo_producto,
                                                                                                bodega,
                                                                                                existencia,
                                                                                                existencia_minima,
                                                                                                existencia_maxima,
                                                                                                usuario_id,
                                                                                                fecha_registro,
                                                                                                estado,
                                                                                                sw_control_fecha_vencimiento)VALUES('".$_SESSION['BODEGAS']['Empresa']."','".$_SESSION['BODEGAS']['CentroUtili']."','$Producto','".$_SESSION['BODEGAS']['BodegaId']."','0.00','0.00','0.00','".UserGetUID()."','".date("Y-m-d H:i:s")."','1','$fechaVenci')";

                $result = $dbconn->Execute($query);
                //WS
                $resultado_ws = $this->Ejecutar_WS($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        return true;
    }


  function ConsultaMenorExistencia($Bodega,$centroUtilidad){

        list($dbconn) = GetDBconn();

        $query="SELECT x.codigo_producto,z.descripcion,x.existencia,x.existencia_minima,x.existencia_maxima,a.existencia as exisobodega,a.existencia_minima as exismin
        FROM
        existencias_bodegas x
        JOIN existencias_bodegas a on (a.codigo_producto=x.codigo_producto and a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' and a.bodega='".$_SESSION['BODEGAS']['BodegaId']."'),
        inventarios y,inventarios_productos as z
        WHERE
        x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x.centro_utilidad='$centroUtilidad' AND x.bodega='$Bodega'
        AND x.estado=1 AND y.empresa_id=x.empresa_id AND y.codigo_producto=x.codigo_producto AND
        z.codigo_producto=x.codigo_producto AND x.existencia <= x.existencia_minima
        ORDER BY z.descripcion";
        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while (!$result->EOF) {
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function LlamaMenuInventarios3(){
        $this->MenuInventarios3();
        return true;
    }

/**
* Funcion que Llama la forma donde se crea un nuevo documento de bodega pasando a la funcion los parametros requeridos
* @return boolean
*/
    function LlamaFormaCrearDocumentosBodega(){
        $this->FormaCrearDocumentosBodega('',date("d/m/Y"),'');
        return true;
    }

    function ConceptosInventarios(){
        list($dbconn) = GetDBconn();
        $query="SELECT a.bodegas_doc_id,b.descripcion as nombremov,a.descripcion
        FROM bodegas_doc_numeraciones a,tipos_doc_bodega b
        WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND
        bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        a.tipo_doc_bodega_id=b.tipo_doc_bodega_id AND
        a.sw_estado='1'";
        //EXCEPT SELECT concepto_inv,descripcion FROM inv_conceptos WHERE tipo_mov='I' AND (sw_traslado='1' OR sw_compras='1')";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
              }
            }
        }
        $result->Close();
        return $vars;
    }

  function MoivosCancelacionDevolucion(){
        list($dbconn) = GetDBconn();
        $query="SELECT motivo_id,descripcion
        FROM inv_solicitudes_devolucion_motivos_cancelacion";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
        while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
              }
            }
        }
        $result->Close();
        return $vars;
    }

  function GuardarCancelacionDevoluciones(){
    if($_REQUEST['Insertar']){
      if($_REQUEST['MotivoId']==-1){
        if($_REQUEST['MotivoId']==1){$this->frmError["MotivoId"]=1;}
        $this->frmError["MensajeError"]="Faltan datos obligatorios.";
        $this->CancelarSolicitudesDevoluciones($_REQUEST['checkboxDevol'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],$_REQUEST['bandera'],$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],
        $_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza']);
        return true;
      }
      list($dbconn) = GetDBconn();
      foreach($_REQUEST['checkboxDevol'] as $indice=>$vector){
        (list($codigo,$cantidad,$consecutivo)=explode('.-.',$vector));
        $query.="INSERT INTO inv_solicitudes_devoluciones_canceladas(consecutivo,motivo_id,observaciones)
        VALUES('$consecutivo','".$_REQUEST['MotivoId']."','".$_REQUEST['observacion']."');";
        $query.="UPDATE inv_solicitudes_devolucion_d SET estado='1' WHERE consecutivo='".$consecutivo."';";
      }
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        $query="SELECT * FROM inv_solicitudes_devolucion_d WHERE documento='".$_REQUEST['Documento']."' AND estado='0'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          $datos=$result->RecordCount();
          if($datos<1){
            $query="UPDATE inv_solicitudes_devolucion SET estado='2' WHERE documento='".$_REQUEST['Documento']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
            $mensaje="Se Cancelaron Todos los Productos de la Solicitud de devolucion";
            $titulo="EXISTENCIAS BODEGA";
            $accion=ModuloGetURL('app','InvBodegas','user','LlamaDevolucionMedicamentos');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
          }
        }
      }
      $result->Close();
    }
    $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],$_REQUEST['bandera'],$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],
      $_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
        return true;
  }

/**
* Funcion que inserta en la base de datos en tablas temporales la cabecera de un documento de bodega
* @return boolean
*/
    function InsertarDocumentoBodega(){

        $conceptoInv=$_REQUEST['conceptoInv'];
        $FechaDocumento=$_REQUEST['FechaDocumento'];
        if($_REQUEST['Salir']){
       $this->MenuInventarios3();
             return true;
        }
        if($conceptoInv==-1 || !$FechaDocumento){
            if($conceptoInv==-1){ $this->frmError["conceptoInv"]=1; }
            if(!$FechaDocumento){ $this->frmError["FechaDocumento"]=1; }
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
            $this->FormaCrearDocumentosBodega($conceptoInv,$FechaDocumento,$_REQUEST['observacion']);
            return true;
        }
    $move=$this->DefinicionConceptoInv($conceptoInv);
    if($move['sw_traslado']=='1'){
            if($move['tipo_movimiento']=='E'){
                $this->PedirBodegaTrasladoPtos($conceptoInv,$FechaDocumento,$_REQUEST['observacion']);
                return true;
            }else{
                $this->frmError["MensajeError"]="Debe Realizar el Documento desde la Bodega Origen de la Transferencia.";
                $this->FormaCrearDocumentosBodega($conceptoInv,$FechaDocumento,$_REQUEST['observacion']);
                return true;
            }
        }
        $cadena=explode('/',$FechaDocumento);
    $dia=$cadena[0];
    $mes=$cadena[1];
        $ano=$cadena[2];
    $FechaDocumento=$ano.'-'.$mes.'-'.$dia;
        list($dbconn) = GetDBconn();
        $query="SELECT nextval('tmp_bodegas_documentos_documento_seq')";
        $result=$dbconn->Execute($query);
        $Documento=$result->fields[0];
        $query="INSERT INTO tmp_bodegas_documentos(documento,
                                                                                    fecha,
                                                                                    total_costo,
                                                                                    transaccion,
                                                                                    observacion,
                                                                                    usuario_id,
                                                                                    fecha_registro,
                                                                                    bodegas_doc_id
                                                                                    )VALUES('$Documento','$FechaDocumento','0',NULL,
                                                                                    '".$_REQUEST['observacion']."','".UserGetUID()."',
                                                                                    '".date("Y-m-d H:i:s")."','$conceptoInv')";
        $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if($move['tipo_movimiento']=='I'){
          $this->DetDocumentoBodegaLotes($Documento,$FechaDocumento,$conceptoInv);
          return true;
        }
        $this->DetalleDocumentosBodega($Documento,$conceptoInv,$FechaDocumento,'0.00','0.00');
        return true;
    }

    function SeleccionBodegaTransferencia(){
    if($_REQUEST['salir']){
            $this->MenuInventarios3();
            return true;
        }
        if($_REQUEST['numBodega']==-1){
            $this->frmError["MensajeError"]="Seleccione la Bodega Destino";
            $this->PedirBodegaTrasladoPtos($_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['observacion']);
            return true;
        }
        $cadena=explode('/',$_REQUEST['FechaDocumento']);
    $dia=$cadena[0];
    $mes=$cadena[1];
        $ano=$cadena[2];
    $FechaDocumento=$ano.'-'.$mes.'-'.$dia;
        list($dbconn) = GetDBconn();
    $query="SELECT nextval('inv_documento_transferencia_b_inv_documento_transferencia_i_seq')";
        $result=$dbconn->Execute($query);
        $consecutivo=$result->fields[0];
    $cadena=explode('/',$_REQUEST['numBodega']);
        $CentroUtilityDest=$cadena[0];
        $BodegaDest=$cadena[1];
    $TipoReposicion=$cadena[2];
        $query="INSERT INTO inv_documento_transferencia_bodegas(inv_documento_transferencia_id,
                                                                empresa_id,
                                                                                                                        centro_utilidad,
                                                                                                                        bodega,
                                                                                                                        bodega_destino,
                                                                                                                        centro_utilidad_destino,
                                                                                                                        estado,
                                                                                                                        usuario_id,
                                                                                                                        fecha_transferencia)VALUES(
                                                                                                                        '$consecutivo',
                                                                                                                        '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                        '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                        '$BodegaDest',
                                                                                                                        '$CentroUtilityDest',
                                                                                                                        '0',
                                                                                                                        '".UserGetUID()."',
                                                                                                                        '$FechaDocumento')";
        $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->PtosTransferenciaBodegas($consecutivo,$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$BodegaDest,$CentroUtilityDest,'','','','','','','',$TipoReposicion);
        return true;
    }

    function InsPtosTransferenciaBodegas(){

        if($_REQUEST['buscar']){
      $this->BuscadorProductoExistencias($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],'','',2,$_REQUEST['CentroUtilityDest'],$_REQUEST['BodegaDest'],
      $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }

      list($dbconn) = GetDBconn();
      if($_REQUEST['Regresar']){
          $registros=$this->ConsultaProductosDocumentoTransaccion($_REQUEST['Documento']);
            if(!$registros){
                $query="DELETE FROM inv_documento_transferencia_bodegas WHERE inv_documento_transferencia_id='".$_REQUEST['Documento']."'";
                $dbconn->Execute($query);
                if($dbconn->ErrorNo() !=0 ){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
            $this->MenuInventarios3();
            return true;
        }


    if(!$_REQUEST['codigo'] || !$_REQUEST['cantSolicitada']){
            $this->frmError["MensajeError"]="Datos Incompletos";
            $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
            $_REQUEST['TipoReposicion']);
            return true;
        }
        $VerificarCodigoBodega=$this->VerificarCodigoBodegaDestino($_REQUEST['codigo'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest']);
        if($VerificarCodigoBodega!=1){
            $this->frmError["MensajeError"]="Este codigo no existe en la Bodega Destino";
            $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
            $_REQUEST['TipoReposicion']);
            return true;
        }
        $CantidadDisponible=$this->VerificarCantidad($_REQUEST['codigo'],$_REQUEST['cantSolicitada']);
        if($CantidadDisponible!=1){
            $this->frmError["MensajeError"]="Es Imposible Insertar esta Cantidad. Es mayor a la existencia en Bodega";
            $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
            $_REQUEST['TipoReposicion']);
            return true;
        }

        $confirmarProducto=$this->ConfirmarProductoTransferencia($_REQUEST['consecutivo'],$_REQUEST['codigo']);
        if($confirmarProducto==1){
      $this->frmError["MensajeError"]="Ya Inserto este Producto en el Detalle del Documento";
            $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
            $_REQUEST['TipoReposicion']);
            return true;
        }
        $query="INSERT INTO inv_documento_transferencia_bodegas_d(inv_documento_transferencia_id,
                                                                  codigo_producto,
                                                                                                                            cantidad)VALUES(
                                                                                                                            '".$_REQUEST['Documento']."',
                                                                                                                            '".$_REQUEST['codigo']."',
                                                                                                                            '".$_REQUEST['cantSolicitada']."')";
        $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],'','','','','','','',$_REQUEST['TipoReposicion']);
        return true;
    }

    function VerificarCodigoBodegaDestino($codigoPto,$BodegaDestino,$centroUtili){
    list($dbconn) = GetDBconn();
        $query="SELECT * FROM existencias_bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='$centroUtili' AND bodega='$BodegaDestino' AND codigo_producto='$codigoPto'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
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

    /**
* Funcion que consulta las bodegas existentes en la base de datos exceptuando la bodega en la que se esta trabajando
* @return array
* @param string codigo de la empresa en la que se esta trabajando
* @param string codigo de la bodega en la que se esta trabajando
*/
    function NombreBodegasInventario($Bodega,$CentroUtili){

        list($dbconn) = GetDBconn();
        $query="SELECT bodega,descripcion FROM bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad= '$CentroUtili' AND bodega='$Bodega'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'bodegas' esta vacia ";
                return false;
            }else{
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que consulta el nombre del nombre del concepto del inventario a partir  del codigo
* @return array
* @param string codigo del concepto del inventario
*/
    function NomConceptoDocumento($conceptoInv){

    list($dbconn) = GetDBconn();
        $query = "SELECT b.descripcion,a.sw_compras FROM bodegas_doc_numeraciones a,tipos_doc_bodega b WHERE a.bodegas_doc_id='$conceptoInv' AND a.tipo_doc_bodega_id=b.tipo_doc_bodega_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'inv_conceptos' esta vacia ";
                return false;
            }else{
        $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que inserta en la base de datos en tablas temporales el detalle del documento de la bodega
* @return boolean
*/
    function InsDetalleDocumentosBodega(){

        if($_REQUEST['buscar']){
      $this->BuscadorProductoExistencias($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],'','',1,'','',
      $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],
      $_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }

        if($_REQUEST['Regresar']){
          $this->EliminaciondeTablas($_REQUEST['Documento'],$_REQUEST['conceptoInv']);
          $this->MenuInventarios3();
            return true;
        }
    if($_REQUEST['Guardar']){
            $confirmar=$this->ConfirmacionDetalle($_REQUEST['Documento'],$_REQUEST['conceptoInv']);
            if($confirmar!=1){
                $this->frmError["MensajeError"]="Imposible Guardar, No ha Insertado el Detalle del Documento";
                $this->DetalleDocumentosBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['cantSolicitada'],
                $_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto']);
                return true;
            }else{
              $this->TotalizarDocBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv']);
                $this->GuardarDocumentoBD($_REQUEST['Documento'],$_REQUEST['conceptoInv'],'E');
                return true;
            }
        }
        if(!$_REQUEST['cantSolicitada'] || !$_REQUEST['nombreProducto'] || !$_REQUEST['codigo']){
            if(!$_REQUEST['cantSolicitada']){ $this->frmError["cantSolicitada"]=1; }
            if(!$_REQUEST['nombreProducto']){ $this->frmError["nombreProducto"]=1; }
            if(!$_REQUEST['codigo']){ $this->frmError["codigo"]=1; }
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
            $this->DetalleDocumentosBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['cantSolicitada'],
            $_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto']);
            return true;
        }
        $confirmarProducto=$this->ConfirmarProductoDocumento($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['codigo']);
        if($confirmarProducto==1){
      $this->frmError["MensajeError"]="Ya Inserto este Producto en el Detalle del Documento";
            $this->DetalleDocumentosBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['cantSolicitada'],
            $_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto']);
            return true;
        }
    if($confirmarConcepto['tipo_mov']!='I'){
            $CantidadDisponible=$this->VerificarCantidad($_REQUEST['codigo'],$_REQUEST['cantSolicitada']);
            if($CantidadDisponible!=1){
                $this->frmError["MensajeError"]="Es Imposible Insertar esta Cantidad. Es mayor a la existencia en Bodega";
                $this->DetalleDocumentosBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['cantSolicitada'],
              $_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto']);
                return true;
            }
        }
        $costoProducto=$this->HallarCostoProducto($_SESSION['BODEGAS']['Empresa'],$_REQUEST['codigo']);
    list($dbconn) = GetDBconn();
        $query="INSERT INTO tmp_bodegas_documentos_d(documento,
                                                                                            codigo_producto,
                                                                                            cantidad,
                                                                                            total_costo,
                                                                                            bodegas_doc_id,
                                              iva_compra)VALUES('".$_REQUEST['Documento']."','".$_REQUEST['codigo']."','".$_REQUEST['cantSolicitada']."','".$_REQUEST['costoProducto']."',
                                                                                            '".$_REQUEST['conceptoInv']."','0.0')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->DetalleDocumentosBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],'0.00','0.00');
        return true;
    }

 /**
* Funcion que elimina las tablas temporales que contienen los datos sobre el documento
* @return boolean
* @param string empresa a la que partenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function EliminaciondeTablas($Documento,$concepto){

        list($dbconn) = GetDBconn();
        $query="DELETE FROM tmp_bodegas_documentos WHERE documento='$Documento' AND bodegas_doc_id='$concepto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return true;
    }
/**
* Funcion que verifica antes de guardar en las tablas originales del documento si los datos son correctos
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento*
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function ConfirmacionDetalle($numeroDoc,$concepto){

        list($dbconn) = GetDBconn();
    $query="SELECT * FROM tmp_bodegas_documentos_d WHERE documento='$numeroDoc' AND bodegas_doc_id='$concepto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
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
            return $retorno;
        }
    }

/**
* Funcion que totaliza los registros del detalle del documento en la cabecera del documento
* @return boolean
* @param string empresa a la que partenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function TotalizarDocBodega($Documento,$concepto){

        list($dbconn) = GetDBconn();
        $query="SELECT sum((z.total_costo * z.cantidad)) as tcosto
                FROM tmp_bodegas_documentos_d as z
                WHERE z.documento='$Documento' AND z.bodegas_doc_id='$concepto'";
        $result = $dbconn->Execute($query);
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
        $query="UPDATE tmp_bodegas_documentos SET total_costo='".$vars['tcosto']."' WHERE documento='$Documento' AND bodegas_doc_id='$concepto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
    }

    /**
* Funcion que totaliza los registros del detalle del documento en la cabecera del documento
* @return boolean
* @param string empresa a la que partenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function TotalizarDocDepacho($Documento,$tipoSolicitud){

        list($dbconn) = GetDBconn();
        if($tipoSolicitud!='I'){
        $query="SELECT sum(z.total_costo*z.cantidad) as tcosto FROM bodegas_documento_despacho_med_d as z WHERE z.documento_despacho_id='$Documento'";
        }else{
    $query="SELECT sum(z.total_costo*z.cantidad) as tcosto FROM bodegas_documento_despacho_ins_d as z WHERE z.documento_despacho_id='$Documento'";
        }
        $result = $dbconn->Execute($query);
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
        $query="UPDATE bodegas_documento_despacho_med SET total_costo='".$vars['tcosto']."' WHERE documento_despacho_id='$Documento'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
    }

    /**
* Funcion que totaliza los registros del detalle del documento en la cabecera del documento
* @return boolean
* @param string empresa a la que partenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function TotalizarDocumentoFinalBodega($numeracion,$concepto){

        list($dbconn) = GetDBconn();
        $query="SELECT sum(CASE WHEN z.iva_compra > 0 THEN ((((z.iva_compra * 0.01 ) +1) * z.total_costo) * z.cantidad) ELSE ((z.total_costo) * z.cantidad) END) as tcosto
          FROM bodegas_documentos_d as z WHERE z.bodegas_doc_id='$concepto' AND z.numeracion='$numeracion'";
        $result = $dbconn->Execute($query);
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
        $query="UPDATE bodegas_documentos SET total_costo='".$vars['tcosto']."' WHERE bodegas_doc_id='$concepto' AND numeracion='$numeracion'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
    }

/**
* Funcion que verifica los datos del documento bodega y los inserta en la tablas originales
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string nombre de la empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string nombre del centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string nombre de de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function GuardarDocumentoBD($Documento,$concepto,$movimiento,$origenFun,$centinela){
    IncludeLib("despacho_medicamentos");
        list($dbconn) = GetDBconn();
    $query = "SELECT * FROM tmp_bodegas_documentos_d y WHERE y.documento='$Documento' AND y.bodegas_doc_id='$concepto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if(!$result->EOF){
              $numeracion=AsignarNumeroDocumentoDespacho($concepto);
                $numeracion=$numeracion['numeracion'];
                $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                               numeracion,
                                                                                     fecha,
                                                                                     total_costo,
                                                                                     transaccion,
                                                                                     observacion,
                                                                                     usuario_id,
                                                                                     fecha_registro)SELECT
                                                                                     bodegas_doc_id,
                                                                                     '$numeracion',
                                                                                     fecha,
                                                                                     total_costo,
                                                                                     transaccion,
                                                                                     observacion,
                                                                                     usuario_id,
                                                                                     fecha_registro FROM tmp_bodegas_documentos WHERE documento='$Documento' AND bodegas_doc_id='$concepto'";

                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }else{
          $query="INSERT INTO bodegas_documentos_compras(
                    numeracion,
                    bodegas_doc_id,
                                        numero_factura,
                                        tipo_id_proveedor,
                                        proveedor_id,
                                        otros_gastos,
                                        costo_fletes,
                    observaciones)
                                        SELECT '$numeracion',
                    bodegas_doc_id,
                    numero_factura,
                    tipo_id_proveedor,
                    proveedor_id,
                    otros_gastos,
                    costo_fletes,
                    observaciones
                                        FROM tmp_bodegas_documentos_compras
                WHERE documento='".$Documento."' AND bodegas_doc_id='$concepto'";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }
                  $query="SELECT cantidad,codigo_producto,consecutivo,total_costo,iva_compra FROM tmp_bodegas_documentos_d WHERE documento='$Documento' AND bodegas_doc_id='$concepto'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        $datos=$result->RecordCount();
                        if($datos){
                            while(!$result->EOF){
                                $vars1[]=$result->GetRowAssoc($toUpper=false);
                                $result->MoveNext();
                            }
                        }
                    }

                    for($i=0;$i<sizeof($vars1);$i++){
                      $CodigoPro=$vars1[$i]['codigo_producto'];
                      $query="SELECT  nextval('bodegas_documentos_d_consecutivo_seq')";
                      $result = $dbconn->Execute($query);
                      $consecutivo=$result->fields[0];
            $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                        codigo_producto,
                                                                                                        cantidad,
                                                                                                        total_costo,
                                                                                                        bodegas_doc_id,
                                                                                                        numeracion,
                                                    iva_compra)VALUES(
                                                                                                        '$consecutivo',
                                                    '".$CodigoPro."',
                                                    '".$vars1[$i]['cantidad']."',
                                                    '".$vars1[$i]['total_costo']."',
                                                    '$concepto',
                                                                                                        '$numeracion',
                                                    '".$vars1[$i]['iva_compra']."')";

                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }
                        if($movimiento=='I'){
                            $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                                                                                                    lote,
                                                                                                                                                    saldo,
                                                                                                                                                    cantidad,
                                                                                                                                                    empresa_id,
                                                                                                                                                    centro_utilidad,
                                                                                                                                                    bodega,
                                                                                                                                                    codigo_producto,
                                                                                                                                                    consecutivo)SELECT
                                                                                                                                                    fecha_vencimiento,
                                                                                                                                                    lote,
                                                                                                                                                    saldo,
                                                                                                                                                    cantidad,
                                                                                                                                                    empresa_id,
                                                                                                                                                    centro_utilidad,
                                                                                                                                                    bodega,
                                                                                                                                                    codigo_producto,
                                                                                                                                                    $consecutivo FROM tmp_bodegas_documentos_d_fvencimiento_lotes WHERE consecutivo='".$vars1[$i]['consecutivo']."'";

                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Guardar en la Base de Datos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }else{
                $query="SELECT a.codigo_producto,a.cantidad,y.existencia,y.costo,y.costo_ultima_compra,a.total_costo
                FROM tmp_bodegas_documentos_d a,tmp_bodegas_documentos_compras b,inventarios y
                WHERE a.consecutivo='".$vars1[$i]['consecutivo']."' AND a.documento=b.documento AND a.bodegas_doc_id=b.bodegas_doc_id AND
                a.codigo_producto=y.codigo_producto AND
                y.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                  $this->error = "Error al Guardar en la Base de Datos";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $this->GuardarNumeroDocumento($commit=false);
                  return false;
                }else{
                  if($result->RecordCount()>0){
                    $datPro=$result->GetRowAssoc($toUpper=false);
                    $costo=(($datPro['existencia'] * $datPro['costo']) + ($datPro['total_costo'] * $datPro['cantidad'] ))/($datPro['existencia'] + $datPro['cantidad']);
                    $costoultimaCompra=($datPro['total_costo']);
                    $query="UPDATE inventarios SET costo='$costo',costo_anterior='".$datPro['costo']."',costo_ultima_compra='$costoultimaCompra',costo_penultima_compra='".$datPro['costo_ultima_compra']."' WHERE codigo_producto='".$datPro['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Cargar el Modulo";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
                    }
                  }
                }
                            }
                        }
                      if($origenFun!=1){
                            $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE codigo_producto='$CodigoPro' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }else{
                                $datos=$result->RecordCount();
                                if($datos){
                                    $exis=$result->GetRowAssoc($toUpper=false);
                                    if($movimiento=='I'){
                                      $TotalExistencias=$exis['existencia']+$vars1[$i]['cantidad'];
                                  }else{
                                        $TotalExistencias=$exis['existencia']-$vars1[$i]['cantidad'];
                                        if($exis['sw_control_fecha_vencimiento']=='1'){
                                            DescargarLotesBodega($_SESSION['BODEGAS']['Empresa'],$_SESSION['BODEGAS']['CentroUtili'],$_SESSION['BODEGAS']['BodegaId'],$CodigoPro,$vars1[$i]['cantidad']);
                                        }
                                    }
                                }
                                if($TotalExistencias<0){
                  $mensaje='El Documento no fue creado pues no hay suficientes existencias en la bodega para el producto'.' '.$CodigoPro;
                                    $accion=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios3');
                                    $boton='Refrescar';
                                    if(!$this->FormaMensaje($mensaje,'CREACION DOCUMENTO BODEGA',$accion,$boton)){
                                        return false;
                                    }
                                    return true;
                                }
                                $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='$CodigoPro' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                                $result = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0){
                                    $this->error = "Error al Cargar el Modulo";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $this->GuardarNumeroDocumento($commit=false);
                                    return false;
                                }
                                /*else{
                                    $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='$CodigoPro' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                                    $result = $dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0){
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->GuardarNumeroDocumento($commit=false);
                                        return false;
                                    }else{
                    $Regs=$result->GetRowAssoc($toUpper=false);
                                      if($Regs['existencia']==$TotalExistencias){
                    return 1;
                                        }
                                    }
                                }*/
                            }
                        }
                    }
                    $this->GuardarNumeroDocumento($commit=true);
                    $this->EliminaciondeTablas($Documento,$concepto);
                    if(!$centinela && $origenFun!=1){
                        $mensaje='El Documento fue Creado Correctamente y se le Asigno el Numero  '.$numeracion;
                        $accion=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios3');
                        $boton='Aceptar';
                        $imprimir[0]=$concepto;
                        $imprimir[1]=$numeracion;
                        if(!$this->FormaMensaje($mensaje,'CREACION DOCUMENTO BODEGA',$accion,$boton,'',$imprimir)){
                            return false;
                        }
                        return true;
                    }else{
                      $vectorCompras[]=$numeracion;
            $vectorCompras[]=$concepto;
                      return $vectorCompras;
                    }
                }
            }else{
                $mensaje='Este Proceso no se Realizo Correctamente Favor Consulte los Datos o Informe al Administrador del Sistema';
                $accion=ModuloGetURL('app','InvBodegas','user','LlamaMenu');
                $boton='Refrescar';
                if(!$this->FormaMensaje($mensaje,'CREACION DOCUMENTO BODEGA',$accion,$boton)){
                        return false;
                }
                return true;
            }
        }
    }

/**
* Funcion que verifica si el producto que va ha insertar en el documento ya lo inserto anteriormente
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
* @param string codigo unico que indentifica al producto en una empresa
*/
    function ConfirmarProductoDocumento($Documento,$concepto,$codigo){

        list($dbconn) = GetDBconn();
        $query="SELECT * FROM tmp_bodegas_documentos_d WHERE documento='$Documento' AND bodegas_doc_id='$concepto' AND codigo_producto='$codigo'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                return 1;
            }
            return 0;
        }
    }

    /**
* Funcion que verifica si el producto que va ha insertar en el documento ya lo inserto anteriormente
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
* @param string codigo unico que indentifica al producto en una empresa
*/
    function ConfirmarProductoTransferencia($consecutivo,$producto){

        list($dbconn) = GetDBconn();
        $query="SELECT * FROM inv_documento_transferencia_bodegas_d WHERE inv_documento_transferencia_id='$consecutivo' AND codigo_producto='$producto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
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
        }
        return $retorno;
    }

/**
* Funcion que consulta los productos insertados en un documento
* @return array
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento*
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function ConsultaProductosDocumento($numeroDoc,$conceptoInv){

        list($dbconn) = GetDBconn();
    $query="SELECT x.codigo_producto,z.descripcion,x.cantidad,x.consecutivo,a.sw_control_fecha_vencimiento,(x.total_costo / ((x.iva_compra * 0.01) + 1)) as costo_unitario,x.iva_compra
        FROM tmp_bodegas_documentos_d x,bodegas_doc_numeraciones b,inventarios as y,inventarios_productos z,existencias_bodegas a
        WHERE x.documento='$numeroDoc' AND x.bodegas_doc_id='$conceptoInv' AND b.bodegas_doc_id=x.bodegas_doc_id AND
        b.empresa_id=a.empresa_id AND b.centro_utilidad=a.centro_utilidad AND b.bodega=a.bodega AND x.codigo_producto=a.codigo_producto
        AND x.codigo_producto=y.codigo_producto AND b.empresa_id=y.empresa_id AND y.codigo_producto=z.codigo_producto
    ORDER BY x.consecutivo";
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
                return $vars;
            }
        }
    }

    /**
* Funcion que consulta los productos insertados en un documento
* @return array
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento*
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function ConsultaProductosDocumentoTransaccion($consecutivo){

        list($dbconn) = GetDBconn();
    $query="SELECT a.codigo_producto,b.descripcion,a.cantidad,c.sw_control_fecha_vencimiento,inv.costo,
        (SELECT x.sw_control_fecha_vencimiento FROM existencias_bodegas x WHERE x.empresa_id=c.empresa_id AND x.centro_utilidad=y.centro_utilidad_destino AND x.bodega=y.bodega_destino AND x.codigo_producto=c.codigo_producto) as sw_control_fecha_vencimiento_dest
    FROM inv_documento_transferencia_bodegas y,inv_documento_transferencia_bodegas_d a,inventarios_productos b,existencias_bodegas c,inventarios inv
    WHERE y.inv_documento_transferencia_id=a.inv_documento_transferencia_id AND a.inv_documento_transferencia_id='$consecutivo' AND a.codigo_producto=b.codigo_producto AND
    c.codigo_producto=a.codigo_producto AND c.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
    c.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND c.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
    inv.codigo_producto=b.codigo_producto AND inv.empresa_id=c.empresa_id";
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
                return $vars;
            }
        }
    }

/**
* Funcion que consulta las bodegas existentes en la base de datos exceptuando la bodega en la que se esta trabajando
* @return array
* @param string codigo de la empresa en la que se esta trabajando
* @param string codigo de la bodega en la que se esta trabajando
*/
    function BodegasInventario(){

        list($dbconn) = GetDBconn();
        $query="SELECT bodega,centro_utilidad,descripcion FROM bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND bodega!='".$_SESSION['BODEGAS']['BodegaId']."'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
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

/**
* Funcion que busca el tipo de mivimiento del concepto del inventario apartir del codigo del concepto
* @return array
* @param string codigo qie identifica el concepto del inventario
*/
    function DefinicionConceptoInv($conceptoInv){

        list($dbconn) = GetDBconn();
        $query="SELECT tipo_movimiento,sw_traslado FROM bodegas_doc_numeraciones WHERE bodegas_doc_id='$conceptoInv'";
        $result = $dbconn->Execute($query);
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

/**
* Funcion que verifica si la cantidad que va a retirar de la bodega es mayor o menor a las existencias de la misma
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que indentifica al producto en una empresa
* @param string cantidad solicitada a la bodega
*/
    function VerificarCantidad($codigo,$cantSolicitada){

        list($dbconn) = GetDBconn();
    $query="(SELECT codigo_producto,existencia FROM existencias_bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND codigo_producto='$codigo' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."')
        EXCEPT
        (SELECT codigo_producto,NULL as existencia  FROM inventarios WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND codigo_producto='$codigo' AND sw_servicio!='1')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);                
                if($vars['existencia']>=$cantSolicitada){                  
                  return 1;
                }
            }
            return 0;
        }
    }

/**
* Funcion que llama la forma que consulta los documentos creados en bodega
* @return boolean
*/
    function LlamaConsultaDocumentos(){
        $this->BusquedaDocumentosBodega();
        return true;
    }

    function LlamaMenuInventarios2(){
        $this->MenuInventarios2();
        return true;
    }

/**
* Funcion que llama la forma que consulta los documentos creados en bodega
* @return boolean
*/
    function LlamaSelecProdTomaFisica(){
        $this->SeleccionProductosTomaFisica();
        return true;
    }


    function ConsultaProductosSelectTomaXFiltro(){
        $this->SeleccionProductosTomaFisica($_REQUEST['TomaFisica'], $_REQUEST['CancelarTomAlet'], $_REQUEST['codigoProd'], $_REQUEST['descripcion'], $_REQUEST['grupo'], $_REQUEST['NomGrupo'], $_REQUEST['clasePr'], $_REQUEST['NomClase'], $_REQUEST['subclase'], $_REQUEST['NomSubClase']);
        return true;
    }


/**
* Funcion que realiza la consulta en la base de datos la existencia de una bodega
* @return array
* @param string empresa a la que partenece la bodega a la que se van consultar las existencias
* @param string centro de utilidad a la que partenece la bodega a la que se van consultar las existencias
* @param string codigo de la bodega a la que se van consultar las existencias
*/

  function ConsultaProductosSelectToma($grupo, $clasePr, $subclase, $codigoProd, $descripcion){

	     list($dbconn) = GetDBconn();
          
          if($codigoProd)
          { $codigo = "AND d.codigo_producto = '".$codigoProd."'"; }
          
          if($descripcion)
          { $desc = "AND d.descripcion LIKE '%$descripcion%'"; }
          
          if($grupo)
          { $agrupacion = "AND d.grupo_id = '".$grupo."'";
          	
               if($clasePr)
               { $agrupacion .= "AND d.clase_id = '".$clasePr."'"; } 
               
               if($subclase)
               { $agrupacion .= "AND d.subclase_id = '".$subclase."'"; }
          }
          
          
          $query = " SELECT x.codigo_producto,x.existencia,x.existencia_minima, x.existencia_maxima,d.descripcion as desprod
          FROM existencias_bodegas x, inventarios_productos d
          WHERE x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' 
          $codigo
          $desc
          $agrupacion
          AND x.codigo_producto=d.codigo_producto AND x.estado='1' AND x.codigo_producto NOT IN
          (SELECT z.codigo_producto FROM inv_toma_fisica_d z WHERE z.empresa_id = '".$_SESSION['BODEGAS']['Empresa']."' AND z.centro_utilidad = '".$_SESSION['BODEGAS']['CentroUtili']."' AND z.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
          ORDER BY d.descripcion";


        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
            $result->Close();
            return $vars;
        }
    }

    function InsertarTomaFisica(){
    $Guardar=$_REQUEST['Guardar'];
        $Salir=$_REQUEST['Salir'];
        $Seleccion=$_REQUEST['Seleccion'];
        $TomaFisica=$_REQUEST['TomaFisica'];
        $conteo=$this->conteo;
        $Of=$_REQUEST['Of'];
        $paso=$_REQUEST['paso'];
        if($_REQUEST['Aleatoria']){
      $this->TomarTomaAleatoria('0');
            return true;
        }
    if($_REQUEST['VerTomaFisica']){
      if(!$_SESSION['Inventarios']){
              $this->frmError["MensajeError"]="No se Han Seleccionado Ningùn Producto para la Toma Fisica";
        $this->SeleccionProductosTomaFisica($_SESSION['TomaFisica']);
                return true;
            }
          $_REQUEST['Of']="";
          $_REQUEST['paso']="";
      $retorno=$this->InsertarSelleccionToma();
            $cadenaD=explode('*',$retorno);
            $TomaFisica=$cadenaD[0];
            $_REQUEST['TomaFisica']=$TomaFisica;
            $Fecha=$cadenaD[1];
            $_REQUEST['Fecha']=$Fecha;
          unset ($_SESSION['Inventarios']);
            $this->DetalleListadoTomasFisicas($TomaFisica,$Fecha,$bandera);
            return true;
        }
        if($_REQUEST['Salir']){
          if(empty($_REQUEST['CancelarTomAlet'])){
            $retorno=$this->InsertarSelleccionToma();
            }
          unset ($_SESSION['Inventarios']);
      $this->MenuInventarios2();
            return true;
        }
        if($_REQUEST['SalirSinGuardar']){
          unset ($_SESSION['Inventarios']);
      $this->MenuInventarios2();
            return true;
        }
        if(sizeof($_REQUEST['Seleccion'])>0){
            unset ($_SESSION['Inventarios'][$_REQUEST['paso']]);
            if(empty($_REQUEST['paso']))
            {
              $_REQUEST['paso']=1;
            }
            foreach($_REQUEST['Seleccion'] as $i => $cadena){
                $cadenaCompl=explode('/',$cadena);
                $codigoProd=$cadenaCompl[0];
                $Existencias=$cadenaCompl[1];
                $_SESSION['Inventarios'][$_REQUEST['paso']][$codigoProd][$Existencias]=1;
        //$comprobarPrExisToma=$this->ComprobarPrEnToma($codigoProd,$TomaFisica,$BodegaId,$CentroUtili,$Empresa);
            }
        }
    $this->SeleccionProductosTomaFisica($_SESSION['TomaFisica']);
        return true;
    }

    function TomaFisicaAleatoriaInv($grupo,$clasePr,$subclase){
    if($grupo){$query1.=" AND a.grupo_id='$grupo'";}
        if($clasePr){$query1.=" AND a.clase_id='$clasePr'";}
        if($subclase){$query1.=" AND a.subclase_id='$subclase'";}
        list($dbconn) = GetDBconn();
        $query="SELECT a.codigo_producto FROM (SELECT codigo_producto
        FROM existencias_bodegas a
        WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'
        EXCEPT
        SELECT codigo_producto
        FROM inv_toma_fisica_d
        WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."') as b,
    inventarios_productos a WHERE a.codigo_producto=b.codigo_producto $query1";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
              while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        foreach($vars as $v=>$vector){
          foreach($vector as $nom=>$codigo){
        $vectorFin[]=$codigo;
            }
        }
        return $vectorFin;
 }

    function DatosProductosExsitenciaAlea($codigoPr){

        list($dbconn) = GetDBconn();
        $query = "SELECT x.codigo_producto,x.existencia,x.existencia_minima,
        x.existencia_maxima,d.descripcion as desprod FROM existencias_bodegas x,
        inventarios y,inventarios_productos d WHERE x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        x.codigo_producto='$codigoPr' AND x.codigo_producto=y.codigo_producto AND
        x.empresa_id=y.empresa_id AND x.codigo_producto=d.codigo_producto";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
        $vars=$result->GetRowAssoc($toUpper=false);
            }
            $result->Close();
            return $vars;
        }
    }

    function InsertarSelleccionToma(){
    $retorno=true;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT nextval('inv_toma_fisica_toma_fisica_id_seq')";
        $result=$dbconn->Execute($query);
        $Fecha=date("Y/m/d H:i:s");
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }else{
            $TomaFisica=$result->fields[0];
            $query="INSERT INTO inv_toma_fisica(toma_fisica_id,empresa_id,centro_utilidad,
                                                                                    bodega,fecha_registro,usuario_id)VALUES(
                                                                                    '$TomaFisica','".$_SESSION['BODEGAS']['Empresa']."','".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                    '".$_SESSION['BODEGAS']['BodegaId']."','$Fecha','".UserGetUID()."')";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
        }
        foreach($_SESSION['Inventarios'] as $x=>$vector){
          foreach($vector as $codigoProd=>$vector1){
              foreach($vector1 as $existencias=>$b){
                    $query="INSERT INTO inv_toma_fisica_d(toma_fisica_id,empresa_id,centro_utilidad,
                                                                                            codigo_producto,bodega,cantidad_sistema,
                                                                                            cantidad_fisica)VALUES('$TomaFisica','".$_SESSION['BODEGAS']['Empresa']."',
                                                                                            '".$_SESSION['BODEGAS']['CentroUtili']."','$codigoProd','".$_SESSION['BODEGAS']['BodegaId']."','$existencias','0')";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }else{
                      $retorno=$TomaFisica.'*'.$Fecha;
                      $dbconn->CommitTrans();
                    }
              }
            }
        }
        return $retorno;
    }

    function liquidacionMedicamentos()
    {
       IncludeLib("despacho_medicamentos");
       list($dbconn) = GetDBconn();
       $cuenta=$_SESSION['INVENTARIOS']['CUENTA'];
       $query="SELECT empresa_id,centro_utilidad,bodega FROM tmp_cuenta_insumos WHERE numerodecuenta='$cuenta' GROUP BY empresa_id,centro_utilidad,bodega,numerodecuenta";
       $result = $dbconn->Execute($query);

        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'tmp_cuenta_insumos' esta vacia ";
                return false;
            }else{
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
                  $result->MoveNext();
              }
            }
        }
        for($i=0;$i<sizeof($vars);$i++){
            $Empresa=$vars[$i]['empresa_id'];
            $CentroUtili=$vars[$i]['centro_utilidad'];
            $BodegaId=$vars[$i]['bodega'];
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
            empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $_SESSION['RETORNO']['numeracion'] = $numeracion;
            $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');
            if($codigoAgrupamiento!='0')
            {
              $query = "SELECT  codigo_producto,
                                departamento,
                                precio,
                                fecha_cargo,
                                plan_id,
                                servicio_cargo,
                                lote,
                                fecha_vencimiento,
                                sum(cantidad) as cantidad
                        FROM    tmp_cuenta_insumos
                        WHERE   numerodecuenta='$cuenta' 
                        AND     empresa_id='$Empresa' 
                        AND     centro_utilidad='$CentroUtili' 
                        AND     bodega='$BodegaId'
                        GROUP BY codigo_producto,departamento,precio,
                                fecha_cargo,plan_id, servicio_cargo, lote, fecha_vencimiento";
              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() !=0 )
              {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
              }
              else
              {
                $datosCont=$result->RecordCount();
                if($datosCont)
                {
                  while(!$result->EOF)
                  {
                    $varsPr[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                  }
                }
              }
            }
            else
            {
              $_SESSION['INVENTARIOS']['RETORNO']['Bodega']=false;
              $_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega1';
              $this->ReturnMetodoExterno($_SESSION['INVENTARIOS']['RETORNO']['contenedor'],$_SESSION['INVENTARIOS']['RETORNO']['modulo'],$_SESSION['INVENTARIOS']['RETORNO']['tipo'],$_SESSION['INVENTARIOS']['RETORNO']['metodo'],$_SESSION['INVENTARIOS']['RETORNO']['argurmentos']);
              return true;
            }
            for($j=0;$j<sizeof($varsPr);$j++)
            {
              $Cantidad=$varsPr[$j]['cantidad'];
              $codigoProducto=$varsPr[$j]['codigo_producto'];
              $departamento=$varsPr[$j]['departamento'];
              $FechaCargo=$varsPr[$j]['fecha_cargo'];
              $Plan=$varsPr[$j]['plan_id'];
              $Servicio=$varsPr[$j]['servicio_cargo'];
              $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
              $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
              $result=$dbconn->Execute($query);
              $Consecutivo=$result->fields[0];
              $InsertarDocumentod = $this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto,$varsPr[$j]['lote'],$varsPr[$j]['fecha_vencimiento']);
              if($InsertarDocumentod==1)
              {
                if(($Transaccion=$this->InsertarBodegasDocumentosdCober($Consecutivo,$varsPr[$j]['fecha_cargo'],$cuenta,$codigoProducto,$Cantidad,$varsPr[$j]['precio'],$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD'))==false)
                {
                  $_SESSION['INVENTARIOS']['RETORNO']['Bodega']=false;
                  $_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
                  $this->ReturnMetodoExterno($_SESSION['INVENTARIOS']['RETORNO']['contenedor'],$_SESSION['INVENTARIOS']['RETORNO']['modulo'],$_SESSION['INVENTARIOS']['RETORNO']['tipo'],$_SESSION['INVENTARIOS']['RETORNO']['metodo'],$_SESSION['INVENTARIOS']['RETORNO']['argurmentos']);
                  return true;
                }
                else
                {
                  $query = "SELECT  A.existencia, 
                                    A.sw_control_fecha_vencimiento, 
                                    B.sw_restriccion_stock 
                            FROM    existencias_bodegas AS A,
                                    bodegas AS B
                            WHERE   A.empresa_id = '$Empresa' 
                            AND     A.centro_utilidad = '$CentroUtili' 
                            AND     A.bodega = '$BodegaId' 
                            AND     A.codigo_producto = '$codigoProducto'
                            AND     A.bodega = B.bodega";
                  $result = $dbconn->Execute($query);
                  $Existencias = $result->fields[0];
                  $NonExistencias = $result->fields[2];
                  if($NonExistencias == "0")
                  {
                    if(($Existencias-$Cantidad)<0)
                    {
                      $_SESSION['INVENTARIOS']['RETORNO']['Bodega']=false;
                      $_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles'.'||//'.$Transaccion;
                      $this->ReturnMetodoExterno($_SESSION['INVENTARIOS']['RETORNO']['contenedor'],$_SESSION['INVENTARIOS']['RETORNO']['modulo'],$_SESSION['INVENTARIOS']['RETORNO']['tipo'],$_SESSION['INVENTARIOS']['RETORNO']['metodo'],$_SESSION['INVENTARIOS']['RETORNO']['argurmentos']);
                      return true;
                    }
                  }
                  if($Existencias > 0)
                  {
                    $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto,$varsPr[$j]);
                  }
                  /*if($result->fields[1]=='1')
                  {
                    DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
                  }*/
                }
              }
              else
              {
                $_SESSION['INVENTARIOS']['RETORNO']['Bodega']=false;
                $_SESSION['INVENTARIOS']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
                $this->ReturnMetodoExterno($_SESSION['INVENTARIOS']['RETORNO']['contenedor'],$_SESSION['INVENTARIOS']['RETORNO']['modulo'],$_SESSION['INVENTARIOS']['RETORNO']['tipo'],$_SESSION['INVENTARIOS']['RETORNO']['metodo'],$_SESSION['INVENTARIOS']['RETORNO']['argurmentos']);
                return true;
              }
            }
            unset($varsPr);
            $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
            $query="DELETE FROM tmp_cuenta_insumos WHERE numerodecuenta='$cuenta' AND empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0)
            {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }
        }
        $this->GuardarNumeroDocumento($commit=true);
    $_SESSION['INVENTARIOS']['RETORNO']['Bodega']=true;
        $this->ReturnMetodoExterno($_SESSION['INVENTARIOS']['RETORNO']['contenedor'],$_SESSION['INVENTARIOS']['RETORNO']['modulo'],$_SESSION['INVENTARIOS']['RETORNO']['tipo'],$_SESSION['INVENTARIOS']['RETORNO']['metodo'],$_SESSION['INVENTARIOS']['RETORNO']['argurmentos']);
        return true;
        //fin por cada solicitud
    }//fin functionUpdateX

/**
* Funcion que realiza la insercion de la cabecera del documento cuando se realiza una solicitud
* @return boolean
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param date fecha de realizacion del documento
* @param string obesrvaciones realizadas al documento
* @param string prefijo del documento
* @param boolean indicador de destino de la funcion
*/

    function InsertarBodegasDocumentos($concepto,$numeracion,$Fecha,$observaciones,$tipoCargo){

        list($dbconn) = GetDBconn();
		
		$query = "INSERT INTO bodegas_documentos(
                                                            bodegas_doc_id,
                                                            numeracion,
                                                            fecha,
                                                            total_costo,
                                                            transaccion,
                                                            observacion,
                                                            usuario_id,
                                                            fecha_registro)VALUES('$concepto','$numeracion','$Fecha','0',NULL,
                                                        '$observaciones','".UserGetUID()."','".date("Y-m-d H:i:s")."');";

        $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
            $query = "SELECT nextval('cuentas_codigos_agrupamiento_codigo_agrupamiento_id_seq')";
            $result = $dbconn->Execute($query);
            $codigoAgrupamiento=$result->fields[0];
      if($tipoCargo=='DIMD'){
        $descrip='DEVOLUCION DE MEDICAMENTOS';
      }else{
        $descrip='DESCARGO DE MEDICAMENTOS';
      }
      if(!empty($_SESSION['LIQUIDACION_QX']['NoLIQUIDACION'])){
        $NoLiquidacion="'".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."'";
      }else{
        $NoLiquidacion='NULL';
      }
            $query = "INSERT INTO cuentas_codigos_agrupamiento(codigo_agrupamiento_id,
                                                                                                                descripcion,
                                                                                                                bodegas_doc_id,
                                                                                                                numeracion,
                                                        cuenta_liquidacion_qx_id)
                                                                                                                VALUES('$codigoAgrupamiento',
                                                                                                                '".$descrip."',
                                                                                                                '$concepto',
                                                                                                                '$numeracion',
                                                        $NoLiquidacion);";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
                return $codigoAgrupamiento;
            }
        }
        return '0';
    }
    /**
    * Funcion que inserta el detalle del documento
    *
    * @param string $Consecutivo Consecutivo de la transaccion
    * @param integer $numeracion Numero asignado al documento
    * @param string $concepto bodega_doc_id
    * @param string $Codigo Codigo del producto
    * @param integer $Cantidad Cantidad del producto
    * @param integer $costoProducto costo del producto
    * @param string $lote Lote del producto 
    * @param string $fecha_vencimiento Fecha de vencimiento del producto
    *
    * @return boolean
    */
    function InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$Codigo,$Cantidad,$costoProducto,$lote,$fecha_vencimiento)
    {
      list($dbconn) = GetDBconn();
	  
      $query = "INSERT INTO bodegas_documentos_d
                  (
                    consecutivo,
                    codigo_producto,
                    cantidad,
                    total_costo,
                    bodegas_doc_id,
                    numeracion,
                    lote,
                    fecha_vencimiento
                  )
                VALUES
                  (
                    '$Consecutivo',
                    '$Codigo',
                    '$Cantidad',
                    '$costoProducto',
                    '$concepto',
                    '$numeracion',
                    '".$lote."',
                     ".(($fecha_vencimiento)? "'".$fecha_vencimiento."'":"NULL")."
                  );";

      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $this->GuardarNumeroDocumento($commit=false);
        return false;
      }
      return 1;
    }

    /**
    * Funcion que inserta y calcula los valore del cargos del medicamento o insumo
    * @return array
    * @param string codigo unico que el identifica el registro de insercion del medicamento o insumo
    */
    function InsertarBodegasDocumentosdCober($Consecutivo,$fechaCargo,$cuenta,$codigo,$cantidad,$precio,$codigoAgrupamiento,$planId,$Servicio,$Empresa,$CentroUtili,$departamento,$devolucion,$tipoCargo)
    {
      IncludeLib("tarifario_cargos");
      if(empty($Consecutivo))
      {
        $Consecutivo=$_REQUEST['Consecutivo'];
      }
        list($dbconn) = GetDBconn();
        $varsCuenDet=LiquidarIyM($cuenta,$codigo,$cantidad,$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$departamento,$Empresa);
        $autorizacion_int=$varsCuenDet['autorizacion_int'];
        if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
        $autorizacion_ext=$varsCuenDet['autorizacion_ext'];
        if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

        $query="SELECT nextval('cuentas_detalle_transaccion_seq')";
        $result=$dbconn->Execute($query);
        $Transaccion=$result->fields[0];
        if($devolucion=='1'){
          $valor_cargo=($varsCuenDet['valor_cargo']*-1);
            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
        }else{
      $valor_cargo=$varsCuenDet['valor_cargo'];
            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
            $valor_cubierto=$varsCuenDet['valor_cubierto'];
        }
        if(empty($tipoCargo))
        {
          $tipoCargo='IMD';
        }
       $query = "INSERT INTO cuentas_detalle(transaccion,
                            empresa_id,centro_utilidad,
                            numerodecuenta,departamento,tarifario_id,
                            cargo,cantidad,precio,
                            porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                            valor_cubierto,facturado,fecha_cargo,
                            usuario_id,fecha_registro,sw_liq_manual,
                            valor_descuento_empresa,valor_descuento_paciente,porcentaje_descuento_paciente,
                            servicio_cargo,autorizacion_int,autorizacion_ext,
                            porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
                            codigo_agrupamiento_id,consecutivo,cargo_cups,sw_cargue,departamento_al_cargar)VALUES
                            ('$Transaccion','$Empresa','$CentroUtili',
                            $cuenta,'$departamento','SYS',
                            '$tipoCargo','$cantidad','".$varsCuenDet['precio_plan']."',
                            '".$varsCuenDet['porcentaje_descuento_empresa']."','".$valor_cargo."','".$valor_nocubierto."',
                            '".$valor_cubierto."','".$varsCuenDet['facturado']."','$fechaCargo',
                            '".UserGetUID()."','".date('Y-m-d H:i:s')."','0',
                            '".$varsCuenDet['valor_descuento_empresa']."','".$varsCuenDet['valor_descuento_paciente']."','".$varsCuenDet['porcentaje_descuento_paciente']."',
                            '$Servicio',$autorizacion_int1,$autorizacion_ext1,
                            '".$varsCuenDet['porcentaje_gravamen']."','".$varsCuenDet['sw_cuota_paciente']."','".$varsCuenDet['sw_cuota_moderadora']."',
                                                                                '$codigoAgrupamiento','$Consecutivo',NULL,'3','$departamento')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
          //Falta Validar lo de la Cuenta estado
            $query = "SELECT a.transaccion,
                        a.cargo,
                        a.cantidad,
                        a.departamento_al_cargar
                    FROM cuentas_detalle a, bodegas_documentos_d b
                    WHERE a.numerodecuenta='$cuenta' AND a.consecutivo=b.consecutivo AND
                    b.codigo_producto='$codigo' AND a.consecutivo <> '$Consecutivo' AND a.sw_liq_manual='0'";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
        $datos=$result->RecordCount();
                if($datos){
                    $i=0;
                    while(!$result->EOF){
            $vars[$i]=$result->GetRowAssoc($toUpper=false);
                        $varsCuenDet=LiquidarIyM($cuenta,$codigo,$vars[$i]['cantidad'],$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$vars[$i]['departamento_al_cargar'],$Empresa);
                        if($vars[$i]['cargo']=='DIMD'){
              $valor_cargo=($varsCuenDet['valor_cargo']*-1);
                            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
                            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
                        }else{
              $valor_cargo=$varsCuenDet['valor_cargo'];
                            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
              $valor_cubierto=$varsCuenDet['valor_cubierto'];
                        }
                        $query = "UPDATE cuentas_detalle
                        SET precio='".$varsCuenDet['precio_plan']."',
                        porcentaje_descuento_empresa='".$varsCuenDet['porcentaje_descuento_empresa']."',
                        valor_cargo='".$valor_cargo."',valor_nocubierto='".$valor_nocubierto."',
                        valor_cubierto='".$valor_cubierto."',
                        facturado='".$varsCuenDet['facturado']."',valor_descuento_empresa='".$varsCuenDet['valor_descuento_empresa']."',
                        valor_descuento_paciente='".$varsCuenDet['valor_descuento_paciente']."',porcentaje_descuento_paciente='".$varsCuenDet['porcentaje_descuento_paciente']."',
                        porcentaje_gravamen='".$varsCuenDet['porcentaje_gravamen']."',sw_cuota_paciente='".$varsCuenDet['sw_cuota_paciente']."',
                        sw_cuota_moderadora='".$varsCuenDet['sw_cuota_moderadora']."'
                        WHERE transaccion='".$vars[$i]['transaccion']."'";

            $result1 = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }
                        $result->MoveNext();
                        $i++;
                    }
                }
            }
      return $Transaccion;
        }
        return false;
    }

    /**
    * Funcion que modifica las existencias en bodega de un producto
    * @return boolean
    * @param integer valor de las existencias en la bodega del producto
    * @param integer valor de la cantidad pedida en la solicitud
    * @param string empresa a la que pertenece la bodega donde se va a crear el documento
    * @param string centro de utilidad al  que partenece la bodega donde se va a crear el documento
    * @param string codigo de la bodega donde se va a crear el documento
    * @param string codigo unico que identifica al producto
    */
    function ModificacionExistencias($Existencias,$cantidadSolici,$Empresa,$CentroUtili,$BodegaId,$Codigo,$adicionales)
    {
      list($dbconn) = GetDBconn();
      $ExistenciasTotal= $Existencias - $cantidadSolici;
      $query  = " UPDATE  existencias_bodegas 
                  SET     existencia = '$ExistenciasTotal' 
                  WHERE   empresa_id='$Empresa' 
                  AND     centro_utilidad='$CentroUtili' 
                  AND     bodega='$BodegaId' 
                  AND     codigo_producto='$Codigo'; ";
                  
      $query .= " UPDATE  existencias_bodegas_lote_fv
    							SET     existencia_actual = existencia_actual - ".$cantidadSolici."
    							WHERE   empresa_id = '".$Empresa."' 
    							AND     centro_utilidad = '".$CentroUtili."' 
    							AND     bodega = '".$BodegaId."' 
    							AND     codigo_producto = '".$Codigo."'
    							AND     fecha_vencimiento = '".$adicionales['fecha_vencimiento']."'
                  AND     lote = '".$adicionales['lote']."'; ";
                  
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0) 
      {
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $this->GuardarNumeroDocumento($commit=false);
        return false;
      }
      return 1;
    }

/**
* Funcion que halla el costo de un producto
* @return array
* @param string empresa a la que pertenece la bodega donde se va a crear el documento
* @param string codigo unico que identifica la producto
*/

  function HallarCostoProducto($Empresa,$Codigo){

        list($dbconn) = GetDBconn();
		
        $query="SELECT costo FROM inventarios WHERE empresa_id='$Empresa' AND codigo_producto='$Codigo'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datosCont=$result->RecordCount();
            if($datosCont){
                $vars=$result->GetRowAssoc($toUpper=false);
                $costoProducto=$vars['costo'];
            }
        }
        return $costoProducto;
    }

 /**
* Funcion que totaliza los valores del detalle y lo actualiza en el documento
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param integer codigo unico que identifica el documento
* @param integer prefijo del documento
* @param integer codigo de que identifica el movimiento de la cuenta del paciente
*/

  function TotalizarCostoDocumento($numeracion,$concepto){
    list($dbconn) = GetDBconn();
	
        $query="SELECT sum(total_costo*cantidad) as sumaCosto FROM bodegas_documentos_d  WHERE bodegas_doc_id='$concepto' AND numeracion='$numeracion'";
        $result = $dbconn->Execute($query);
        $sumaCosto=$result->fields[0];
        $query="UPDATE bodegas_documentos SET total_costo='$sumaCosto' WHERE bodegas_doc_id='$concepto' AND numeracion='$numeracion'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      return 1;
        }
    }

    function DetDocumentosBodegaFechaVmto(){

        if($_REQUEST['buscar']){
      $this->BuscadorProductoExistencias($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],'','','','','',
      $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],
      $_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
    if($_REQUEST['Regresar']){
          $this->EliminaciondeTablas($_REQUEST['Documento'],$_REQUEST['conceptoInv']);
          $this->MenuInventarios3();
            return true;
        }
        if($_REQUEST['Guardar']){
            $this->TotalizarDocBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv']);
            $this->GuardarDocumentoBD($_REQUEST['Documento'],$_REQUEST['conceptoInv'],'I');
            return true;
        }
    if($_REQUEST['BuscarProveedor']){
          $this->BuscadorProveedores($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
            $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
            $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
        if(!$_REQUEST['cantSolicitada'] || !$_REQUEST['costoProducto'] || !$_REQUEST['nombreProducto'] || !$_REQUEST['codigo']){
            if(!$_REQUEST['cantSolicitada']){ $this->frmError["cantSolicitada"]=1; }
            if(!$_REQUEST['nombreProducto']){ $this->frmError["nombreProducto"]=1; }
            if(!$_REQUEST['codigo']){ $this->frmError["codigo"]=1; }
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
            $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
        $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
            $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
    if($_REQUEST['compras']==1){
      if(empty($_REQUEST['iva'])){$_REQUEST['iva']='0';}
            if(!$_REQUEST['costoUnit'] || !$_REQUEST['numFactura'] || ($_REQUEST['iva']<0) || !$_REQUEST['tipoIdProveedor'] || !$_REQUEST['ProveedorId']){
                if(!$_REQUEST['costoUnit']){ $this->frmError["costoUnit"]=1; }
                if(!$_REQUEST['numFactura']){ $this->frmError["numFactura"]=1; }
                if($_REQUEST['iva']<0){ $this->frmError["iva"]=1; }
                if(!$_REQUEST['tipoIdProveedor'] || !$_REQUEST['ProveedorId']){$this->frmError["proveedor"]=1;}
                $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
                $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
                $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
              $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
                return true;
            }
        }
        $confirmarProducto=$this->ConfirmarProductoDocumento($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['codigo']);
        if($confirmarProducto==1){
      $this->frmError["MensajeError"]="Ya Inserto este Producto en el Detalle del Documento";
            $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
        $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
            $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="SELECT nextval('tmp_bodegas_documentos_d_consecutivo_seq')";
    $result = $dbconn->Execute($query);
        $consecutivo=$result->fields[0];
    if(!$_REQUEST['iva']){$iva=0;}else{$iva=$_REQUEST['iva'];}
    if(empty($_REQUEST['costoUnit'])){$costoU=$_REQUEST['costoProducto'];}else{$costoU=$_REQUEST['costoUnit'];}
          if($iva > 0)
          {
          	$valorIva = (($iva * 0.01) + 1);
          	$costoUnitario = $costoU * $valorIva;
          }
          else
          {$costoUnitario = $costoU;}
        $query="INSERT INTO tmp_bodegas_documentos_d(consecutivo,
                                                  documento,
                                                                                            codigo_producto,
                                                                                            cantidad,
                                                                                            total_costo,
                                                                                            bodegas_doc_id,
                                              iva_compra)VALUES('$consecutivo','".$_REQUEST['Documento']."','".$_REQUEST['codigo']."',
                                                                                            '".$_REQUEST['cantSolicitada']."','".$costoUnitario."','".$_REQUEST['conceptoInv']."',
                                              '$iva')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($_REQUEST['compras']==1){
        if(empty($_REQUEST['valorFletes'])){$valorFletes='0.0';}else{$valorFletes=$_REQUEST['valorFletes'];}
              if(empty($_REQUEST['otrosGastos'])){$otrosGastos='0.0';}else{$otrosGastos=$_REQUEST['otrosGastos'];}
        $query="SELECT *
        FROM tmp_bodegas_documentos_compras a
        WHERE documento='".$_REQUEST['Documento']."'";
        $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }else{
          $datos=$result->RecordCount();
          if($datos){
            $query="UPDATE tmp_bodegas_documentos_compras SET
              numero_factura='".$_REQUEST['numFactura']."',
              tipo_id_proveedor='".$_REQUEST['tipoIdProveedor']."',
              proveedor_id='".$_REQUEST['ProveedorId']."',
              otros_gastos='".$otrosGastos."',
              costo_fletes='".$valorFletes."',
              observaciones='".$_REQUEST['observaciones']."'
                WHERE documento='".$_REQUEST['Documento']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
          }else{
            $query="INSERT INTO tmp_bodegas_documentos_compras(
            documento,numero_factura,tipo_id_proveedor,
            proveedor_id,otros_gastos,costo_fletes,bodegas_doc_id,observaciones)VALUES(
            '".$_REQUEST['Documento']."','".$_REQUEST['numFactura']."',
            '".$_REQUEST['tipoIdProveedor']."','".$_REQUEST['ProveedorId']."',
            $otrosGastos,$valorFletes,'".$_REQUEST['conceptoInv']."','".$_REQUEST['observaciones']."')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              return false;
            }
          }
        }
            }
        }
    $VerificasFV=$this->VerificacionControlFV($_REQUEST['codigo']);
        if($VerificasFV['sw_control_fecha_vencimiento']==1){
      $this->LotesFechaVmtoPto($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigo'],$_REQUEST['nombreProducto'],
      $_REQUEST['cantSolicitada'],$consecutivo,'','','',$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],
      $_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
        $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],'','','','','','','','',
    $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],'',
    $_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;
    }

    function LlamaBuscadorProveedores(){
      if($_REQUEST['centinela']==1){
      $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
            $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['TipoProveedor'],$_REQUEST['ProveedorIdd'],
            $_REQUEST['NomTercero'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
        if($_REQUEST['Volver']){
      $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
            $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
            $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
            $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
    $this->BuscadorProveedores($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
        $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
        $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones'],$_REQUEST['TipoDocumentoBus'],$_REQUEST['DocumentoBus'],$_REQUEST['descripcionBus']);
        return true;
    }

    function VerificacionControlFV($codigo){
    list($dbconn) = GetDBconn();
        $query="SELECT  sw_control_fecha_vencimiento FROM existencias_bodegas WHERE codigo_producto='$codigo' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
      }
        }
        $result->Close();
        return $vars;
    }

    function InsertarLotesProducto(){
    if(!$_REQUEST['cantidadLote'] || !$_REQUEST['NoLote'] || !$_REQUEST['FechaVmto'] || !$_REQUEST['codigo'] ){
            if(!$_REQUEST['cantidadLote']){ $this->frmError["cantidadLote"]=1; }
            if(!$_REQUEST['NoLote']){ $this->frmError["NoLote"]=1; }
            if(!$_REQUEST['FechaVmto']){ $this->frmError["FechaVmto"]=1; }
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
            $this->LotesFechaVmtoPto($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigo'],$_REQUEST['nombreProducto'],$_REQUEST['cantSolicitada'],$_REQUEST['consecutivo'],$_REQUEST['cantidadLote'],$_REQUEST['NoLote'],$_REQUEST['FechaVmto'],
      $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }

        $cadena=explode('/',$_REQUEST['FechaVmto']);
        if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date("m"),date("d"),date("Y"))){
      $this->frmError["MensajeError"]="La Fecha Insertada No Puede ser Menor a la Actual";
            $this->LotesFechaVmtoPto($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigo'],$_REQUEST['nombreProducto'],$_REQUEST['cantSolicitada'],$_REQUEST['consecutivo'],$_REQUEST['cantidadLote'],$_REQUEST['NoLote'],$_REQUEST['FechaVmto'],
      $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
        $SumaCan=$this->SumaCantidadesLotes($_REQUEST['consecutivo'],$_REQUEST['codigo']);
        if(($SumaCan['sumacantidadeslotes']+$_REQUEST['cantidadLote'])>$_REQUEST['cantSolicitada']){
      $this->frmError["MensajeError"]="La Cantidad Insertada Supera La Cantidad Total";
            $this->LotesFechaVmtoPto($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigo'],$_REQUEST['nombreProducto'],$_REQUEST['cantSolicitada'],$_REQUEST['consecutivo'],$_REQUEST['cantidadLote'],$_REQUEST['NoLote'],$_REQUEST['FechaVmto'],
      $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
    $cadena=explode('/',$_REQUEST['FechaVmto']);
    $FechaVmto=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
        list($dbconn) = GetDBconn();
        $query="INSERT INTO tmp_bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                    lote,
                                                                                                                                saldo,
                                                                                                                                cantidad,
                                                                                                                                empresa_id,
                                                                                                                                centro_utilidad,
                                                                                                                                bodega,
                                                                                                                                codigo_producto,
                                                                                                                                consecutivo)VALUES(
                                                                                                                                '$FechaVmto',
                                                                                                                                '".$_REQUEST['NoLote']."',
                                                                                                                                '0',
                                                                                                                                '".$_REQUEST['cantidadLote']."',
                                                                                                                                '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                                '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                                '".$_REQUEST['codigo']."',
                                                                                                                                '".$_REQUEST['consecutivo']."')";
        $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $SumaCan=$this->SumaCantidadesLotes($_REQUEST['consecutivo'],$_REQUEST['codigo']);
        if($SumaCan['sumacantidadeslotes']<$_REQUEST['cantSolicitada']){
      $this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es Aùn menor que La Cantidad Total";
            $this->LotesFechaVmtoPto($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigo'],$_REQUEST['nombreProducto'],$_REQUEST['cantSolicitada'],$_REQUEST['consecutivo'],
      '','','',$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
            return true;
        }
        $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],'','','','','','','','',
    $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],
    $_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;
    }

    function SumaCantidadesLotes($consecutivo,$codigo){

    list($dbconn) = GetDBconn();
        $query="SELECT sum(cantidad) as sumacantidadeslotes FROM tmp_bodegas_documentos_d_fvencimiento_lotes WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND consecutivo='$consecutivo' AND codigo_producto='$codigo'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
      }
        }
        $result->Close();
        return $vars;
    }

    function ConsultaProductosDocumentoLotes($consecutivo,$codigo){
    list($dbconn) = GetDBconn();
        $query="SELECT fecha_vencimiento,lote,cantidad FROM tmp_bodegas_documentos_d_fvencimiento_lotes WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND consecutivo='$consecutivo' AND codigo_producto='$codigo'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
              while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
      }
        }
        $result->Close();
        return $vars;
    }

    function EliminarRegistroFVLote(){
    list($dbconn) = GetDBconn();
        $query="DELETE FROM tmp_bodegas_documentos_d_fvencimiento_lotes WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND consecutivo='".$_REQUEST['consecutivo']."' AND codigo_producto='".$_REQUEST['codigo']."' AND fecha_vencimiento='".$_REQUEST['FechaVmto']."' AND lote='".$_REQUEST['lote']."' AND cantidad='".$_REQUEST['cantidad']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
      $this->LotesFechaVmtoPto($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigo'],$_REQUEST['nombreProducto'],$_REQUEST['cantSolicitada'],$_REQUEST['consecutivo'],
    '','','',$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;
    }

    function LlamaEliminarPtosLotes(){
    list($dbconn) = GetDBconn();
        $query="DELETE FROM tmp_bodegas_documentos_d WHERE consecutivo='".$_REQUEST['consecutivo']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],'','','','','','','','',
    $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;
    }

    function LlamaDetallePtosLotes(){
    $this->DetalleConsultaPtosLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['cantidad'],$_REQUEST['consecutivo'],
    $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;
    }

    function LlamaDetDocumentoBodegaLotes(){
    $this->DetDocumentoBodegaLotes($_REQUEST['Documento'],$_REQUEST['fechaDocumento'],$_REQUEST['conceptoInv'],
        $_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],
        $_REQUEST['costoProducto'],$_REQUEST['precioProducto'],
      $_REQUEST['cantSolicitada'],$_REQUEST['costoUnit'],$_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],
        $_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;

    }

/**
* Funcion que consulta en la base de datos los datos de detalle del documento de bodega a partir de los parametros elegidos de busqueda
* @return array
*/
    function selecBusquedaDocumento1(){
        $TipoBusquedaInv=$_REQUEST['TipoBusquedaInv'];
        $BuscarTotal=$_REQUEST['BuscarTotal'];
        $NumBusqueda=$_REQUEST['NumBusqueda'];
    $Salir=$_REQUEST['Salir'];
        $conteo=$_REQUEST['conteo'];
        $paso=$_REQUEST['paso'];
        list($dbconn) = GetDBconn();
        if($Salir){
      $this->MenuInventarios3();
      return true;
        }
    $this->BusquedaDocumentosBodega($_REQUEST['BusquedaBus'],$_REQUEST['documentosBus'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal'],$_REQUEST['conceptoInv'],$_REQUEST['numDocumento']);
    return true;
  }

  function ConsultaDocumentosBodega($numDocumento,$FechaInicial,$FechaFinal,$conceptoInv){
    list($dbconn) = GetDBconn();
    if($numDocumento){
          $fil1=" AND b.numeracion='".$numDocumento."'";
        }
    if($FechaInicial && $FechaFinal){
            $cadena=explode('/',$FechaInicial);
            $FechaInicial=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
            $cadena=explode('/',$FechaFinal);
            $FechaFinal=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
            $fil2=" AND date(b.fecha)>=date('$FechaInicial') AND date(b.fecha)<=date('$FechaFinal')";
    }
        if($conceptoInv && $conceptoInv!=-1){
            $fil3=" AND a.bodegas_doc_id='".$conceptoInv."'";
        }
        $query="SELECT b.numeracion,a.prefijo,b.fecha,a.tipo_doc_bodega_id,b.total_costo,b.centro_utilidad_transferencia,b.bodega_destino_transferencia,a.bodegas_doc_id,bod.otros_gastos,usu.nombre as usuario
        FROM bodegas_doc_numeraciones a,bodegas_documentos b
    LEFT JOIN bodegas_documentos_compras bod ON(bod.bodegas_doc_id=b.bodegas_doc_id AND bod.numeracion=b.numeracion),
        system_usuarios usu
        WHERE a.bodegas_doc_id=b.bodegas_doc_id AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND  a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
        AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND b.usuario_id=usu.usuario_id $fil1 $fil2 $fil3 ORDER BY b.numeracion";
        $query1="SELECT b.numeracion,a.prefijo,b.fecha,a.tipo_doc_bodega_id,b.total_costo,b.centro_utilidad_transferencia,b.bodega_destino_transferencia,a.bodegas_doc_id,bod.otros_gastos,usu.nombre as usuario
        FROM bodegas_doc_numeraciones a,bodegas_documentos b
    LEFT JOIN bodegas_documentos_compras bod ON(bod.bodegas_doc_id=b.bodegas_doc_id AND bod.numeracion=b.numeracion),
        system_usuarios usu
        WHERE a.bodegas_doc_id=b.bodegas_doc_id AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND  a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
        AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND b.usuario_id=usu.usuario_id $fil1 $fil2 $fil3 ORDER BY b.fecha DESC LIMIT " . $this->limit . " OFFSET   ";

    if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            $dat = $result->RecordCount();
            $this->conteo=$dat;
      }else{
          $this->conteo=$_REQUEST['conteo'];
      }
      if(!$_REQUEST['Of']){
          $Of='0';
      }else{
          $Of=$_REQUEST['Of'];
    }
    $query1=$query1.' '.$Of;
    $result = $dbconn->Execute($query1);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
          if($datos){
              while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
              }
            }
            $result->Close();
      return $vars;
        }
    }

/**
* Funcion que consulta el tipo de solicitud de una solicitud
* @return array
* @param integer empresa a la que pertenece la bodega
* @param integer centro de utilidad al  que partenece la bodega
* @param integer codigo de la bodega
* @param integer codigo unico que identifica el documento
* @param integer prefijo del documento
*/

    function HallarSolicitudDocumento($numeracion,$concepto){

        list($dbconn) = GetDBconn();
        $query="(SELECT x.solicitud_id FROM hc_solicitudes_medicamentos x WHERE x.numeracion='$numeracion' AND x.bodegas_doc_id='$concepto')
        UNION
        (SELECT x.solicitud_id FROM bodegas_documentos_inv_devolucion x WHERE x.numeracion='$numeracion' AND x.bodegas_doc_id='$concepto')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
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
* Funcion que llama la forma que visualiza el detalle de un documento de bodega
* @return boolean
*/
    function VerDetalleDocumentoBodega(){
        $conteo=$this->conteo;
        $Of=$_REQUEST['Of'];
        $paso=$_REQUEST['paso'];
        $this->DetalleDelDocumentoBodega($_REQUEST['Documento'],$_REQUEST['concepto'],$_REQUEST['documentos'],$_REQUEST['fecha'],$_REQUEST['solicitud'],$_REQUEST['nomconcepto'],$_REQUEST['costo'],$_REQUEST['centroutiliTrans'],$_REQUEST['BodegaTrans'],
    $_REQUEST['BusquedaBus'],$_REQUEST['documentosBus'],$_REQUEST['FechaInicialBus'],$_REQUEST['FechaFinalBus'],$_REQUEST['conceptoInvBus'],$_REQUEST['numDocumentoBus'],$_REQUEST['usuario']);
        return true;
    }

/**
* Funcion que consulta los datos de los medicamentos o insumos que hacen parte del detalle del documento de la bodega
* @return boolean
*/
    function DatosDetalleDelDocumento($Documento,$concepto){

    list($dbconn) = GetDBconn();
        $query="SELECT x.codigo_producto,z.descripcion,x.cantidad,x.total_costo,x.iva_compra,
    com.numero_factura,com.otros_gastos,com.costo_fletes,com.observaciones,
    ter.nombre_tercero
    FROM bodegas_documentos_d x
    LEFT JOIN bodegas_documentos_compras com ON(x.numeracion=com.numeracion AND x.bodegas_doc_id=com.bodegas_doc_id)
    LEFT JOIN terceros ter ON(ter.tipo_id_tercero=com.tipo_id_proveedor AND ter.tercero_id=com.proveedor_id)
    ,inventarios y,inventarios_productos z,bodegas_doc_numeraciones a
    WHERE x.numeracion='$Documento' AND x.bodegas_doc_id='$concepto' AND x.bodegas_doc_id=a.bodegas_doc_id AND y.empresa_id=a.empresa_id AND x.codigo_producto=y.codigo_producto AND x.codigo_producto=z.codigo_producto";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        return $vars;
    }

    function LlamaMenuInventarios6(){
        $this->MenuInventarios6();
        return true;
    }

    function LlamaTemperaturasEquipos(){
        $this->TemperaturasEquipos('','','','',1);
        return true;
    }

    function LlamaDatosRegTemperaturas(){
        $this->DatosRegTemperaturas();
        return true;
    }

    function DatosGraficarMedidas(){
    $ano=$_REQUEST['ano'];
        $mes=$_REQUEST['mes'];
        $equipo=$_REQUEST['equipo'];
    $salir=$_REQUEST['salir'];
        if($salir){
      $this->MenuInventarios6();
            return true;
        }
    if($equipo==-1 || !$ano || !$mes){
            if($equipo==-1){$this->frmError["equipo"]=1;}
            if(!$ano){$this->frmError["ano"]=1;}
            if(!$mes){$this->frmError["mes"]=1;}
            $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
            $this->DatosRegTemperaturas($equipo,$ano,$mes);
            return true;
        }
        $this->consultaRegistrosTemperaturas($equipo,$ano,$mes);
        return true;
    }

    function LlamaReporteTomasFisicas(){

    $this->ListadoTomasFisicas();
        return true;
    }

    function ConsultaTotalTomas(){

    list($dbconn) = GetDBconn();
        $query="SELECT toma_fisica_id,fecha_registro FROM inv_toma_fisica WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'inv_toma_fisica' esta vacia ";
                return false;
            }else{
        while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
              }
            }
        }
        $result->Close();
        return $vars;
    }


    function InsertarSeleccionFisicaAlea(){

        if($_REQUEST['Cancelar']){
      $this->SeleccionProductosTomaFisica('','1');
      return true;
        }
        if($_REQUEST['Aceptar']){
      if($_REQUEST['cantidadPro']<=1){
        $this->frmError["MensajeError"]="Debe Elegir una Cantidad Mayor a 1";
        $this->TomarTomaAleatoria(0,'');
              return true;
            }
          $this->TomarTomaAleatoria(1,$_REQUEST['cantidadPro'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase']);
            return true;
        }
        if($_REQUEST['GuardarToma']){
          $retorno=$this->InsertarSelleccionToma();
            $cadenaD=explode('*',$retorno);
            $TomaFisica=$cadenaD[0];
            $_REQUEST['TomaFisica']=$TomaFisica;
            $Fecha=$cadenaD[1];
            $_REQUEST['Fecha']=$Fecha;
          unset ($_SESSION['Inventarios']);
            $this->DetalleListadoTomasFisicas($TomaFisica,$Fecha,$_REQUEST['bandera'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoProd'],$_REQUEST['descripcionPro']);
            return true;
        }
        $this->SeleccionProductosTomaFisica();
        return true;
  }

    function ConsultaProductosTomaFisica($TomaFisica,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcionPro){

      if($grupo){$query1.=" AND z.grupo_id='$grupo'";}
        if($clasePr){$query1.=" AND z.clase_id='$clasePr'";}
        if($subclase){$query1.=" AND z.subclase_id='$subclase'";}
        if($codigoProd){$query1.=" AND z.codigo_producto LIKE '$codigoProd%'";}
        if($descripcionPro){$descripcionPro=strtoupper($descripcionPro);$query1.=" AND z.descripcion LIKE '%$descripcionPro%'";}
    list($dbconn) = GetDBconn();
        if(empty($_REQUEST['conteo'])){
            $query="SELECT x.codigo_producto,z.descripcion as desprod,x.cantidad_sistema,x.cantidad_fisica FROM inv_toma_fisica_d x,inventarios y,inventarios_productos z WHERE x.toma_fisica_id='$TomaFisica' AND x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND  x.codigo_producto=y.codigo_producto AND y.empresa_id=x.empresa_id AND z.codigo_producto=y.codigo_producto $query1";
            $result = $dbconn->Execute($query);
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }
            $dat = $result->RecordCount();
            $this->conteo=$dat;
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
            $Of='0';
        }else{
            $Of=$_REQUEST['Of'];
        }
        $query="SELECT x.codigo_producto,z.descripcion as desprod,x.cantidad_sistema,x.cantidad_fisica FROM inv_toma_fisica_d x,inventarios y,inventarios_productos z WHERE x.toma_fisica_id='$TomaFisica' AND x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND  x.codigo_producto=y.codigo_producto AND y.empresa_id=x.empresa_id AND z.codigo_producto=y.codigo_producto $query1 LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'inv_toma_fisica_d' esta vacia ";
                return false;
            }else{
                while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function InsertarCantidadesFisica(){
        if($_REQUEST['Salir']){
            $this->ListadoTomasFisicas();
            return true;
        }
        if($_REQUEST['imprimir']){
            $this->ImprimirTomaFisica($_REQUEST['TomaFisica'],$_REQUEST['Fecha'],$_REQUEST['bandera'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase']);
            return true;
        }
        list($dbconn) = GetDBconn();
        if(sizeof($_REQUEST['CantToma'])>0){
            foreach($_REQUEST['CantToma'] as $Pr=>$CantidadToma){
              if($CantidadToma<0){
          $mensaje=1;
                }
                if($CantidadToma>0){
                    $query="UPDATE inv_toma_fisica_d SET cantidad_fisica='$CantidadToma' WHERE toma_fisica_id='".$_REQUEST['TomaFisica']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND codigo_producto='$Pr' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
            }
        }
        if($mensaje==1){
      $this->frmError["MensajeError"]="No Debe Digitar valores menores a 0";
        }
        $this->DetalleListadoTomasFisicas($_REQUEST['TomaFisica'],$_REQUEST['Fecha'],$_REQUEST['bandera'],$_REQUEST['grupo'],$_REQUEST['clasePr'],$_REQUEST['subclase'],$_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['NomGrupo'],$_REQUEST['NomClase'],$_REQUEST['NomSubClase']);
        return true;
    }

    function ImprimirTomaFisica($TomaFisica,$Fecha,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion,$NomGrupo,$NomClase,$NomSubClase){
        list($dbconn) = GetDBconn();
        $query="SELECT a.toma_fisica_id,a.fecha_registro,a.usuario_id,b.nombre
        FROM inv_toma_fisica a,system_usuarios b
        WHERE a.toma_fisica_id='$TomaFisica' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        a.usuario_id=b.usuario_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $query="SELECT a.codigo_producto,b.descripcion_abreviada as nomproducto,d.descripcion as ubicacion,a.cantidad_fisica
        FROM inventarios_productos b,inv_toma_fisica_d a
        LEFT JOIN existencias_bodegas c ON (a.empresa_id=c.empresa_id AND a.centro_utilidad=c.centro_utilidad AND
        a.bodega=c.bodega AND a.codigo_producto=c.codigo_producto)
        LEFT JOIN bodegas_ubicaciones d ON(c.ubicacion_id=d.ubicacion_id)
        WHERE a.toma_fisica_id='$TomaFisica' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        a.codigo_producto=b.codigo_producto";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while (!$result->EOF) {
                    $varsUn[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $arr[0]=$vars;
        $arr[1]=$varsUn;
        $this->ImprimirMuestrasTomasFisicas($arr,$TomaFisica,$Fecha,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion,$NomGrupo,$NomClase,$NomSubClase);
        return true;
    }

    function ImprimirMuestrasTomasFisicas($datosUn,$TomaFisica,$Fecha,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion,$NomGrupo,$NomClase,$NomSubClase){
        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportTomaFisica',
        array("Empresa"=>$_SESSION['BODEGAS']['NombreEmp'],"Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],
        "BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],"datosUn"=>$datosUn),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
								$this->frmError['MensajeError'] = $this->mensajeDeError;
								$this->DetalleListadoTomasFisicas($TomaFisica,$Fecha,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion,$NomGrupo,$NomClase,$NomSubClase);
								return true;
                //return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
        $this->DetalleListadoTomasFisicas($TomaFisica,$Fecha,$bandera,$grupo,$clasePr,$subclase,$codigoProd,$descripcion,$NomGrupo,$NomClase,$NomSubClase);
        return true;
    }

    function NombreEquipo($equipo){
    list($dbconn) = GetDBconn();
        $query="SELECT descripcion FROM inv_equipos_temperaturas WHERE codigo_equipo='$equipo'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

    function SelleccionMedidasEquipos($equipo){

        list($dbconn) = GetDBconn();
        $query="SELECT x.codigo_medida,x.valor_desde,x.valor_hasta,y.descripcion FROM inv_tipos_medidas_equipos x,inv_tipos_medidas y WHERE x.codigo_equipo='$equipo' AND x.codigo_medida=y.codigo_medida";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
              while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function SacaRegistrosEquipoTemp($equipo,$Fecha){
      list($dbconn) = GetDBconn();
    $cadena=explode('-',$Fecha);
        $dia=$cadena[0];
    $mes=$cadena[1];
        $ano=$cadena[2];
        $query="SELECT fecha_toma,temperatura,humedad,registro_id FROM inv_registro_temperaturas WHERE codigo_equipo='$equipo' AND date_part('month',fecha_toma)='$mes' AND date_part('year',fecha_toma)='$ano' ORDER BY fecha_toma";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
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
        }
        $result->Close();
        return $vars;
    }

    function InsertarTemperaturaEquipo(){
        $Temperatura=$_REQUEST['Temperatura'];
        $Humedad=$_REQUEST['Humedad'];
        $medida=$_REQUEST['medida'];
        list($dbconn) = GetDBconn();
    if($_REQUEST['salir']){
            $this->MenuInventarios6();
            return true;
        }
        if($_REQUEST['insertar']){
          if($_REQUEST['bandera']==1){
              if($_REQUEST['equipo']==-1){
                  $this->frmError["MensajeError"]="Seleccione el Equipo";
                    $this->TemperaturasEquipos('','','','',1);
                    return true;
                }
        $this->TemperaturasEquipos($_REQUEST['equipo'],date("d-m-Y"),date("H"),date("i"),0);
                return true;
            }else{
                if(!$_REQUEST['Fecha'] || !$_REQUEST['Hora'] || !$_REQUEST['Minutos']){
                    if(!$_REQUEST['Fecha']){$this->frmError["Fecha"]=1;}
                    if(!$_REQUEST['Hora']){$this->frmError["Hora"]=1;}
                    if(!$_REQUEST['Minutos']){$this->frmError["Hora"]=1;}
                    $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
                    $this->TemperaturasEquipos($_REQUEST['equipo'],$_REQUEST['Fecha'],$_REQUEST['Hora'],$_REQUEST['Minutos'],'');
                    return true;
                }else{
                  $cadena=explode('-',$_REQUEST['Fecha']);
                    $FechaToma=$cadena[2].'-'.$cadena[1].'-'.$cadena[0]." ".$_REQUEST['Hora'].":".$_REQUEST['Minutos'].':00';
                    foreach($_REQUEST['medida'] as $x => $vect){
          foreach($vect as $medida => $valor){
            if($medida=='01'){
                           if($valor==-1 || $valor==''){$Temperatura1='NULL';}else{$Temperatura=$valor;$Temperatura1="'$Temperatura'";}
                        }elseif($medida=='02'){
                          if($valor==-1 || $valor==''){$Humedad1='NULL';}else{$Humedad=$valor;$Humedad1="'$Humedad'";}
                        }
                     }
                    }
                    if(empty($Temperatura1)){$Temperatura1='NULL';}
                    if(empty($Humedad1)){$Humedad1='NULL';}
                    $query="INSERT INTO inv_registro_temperaturas(codigo_equipo,temperatura,humedad,
                                                              fecha_toma,fecha_registro,usuario_id)VALUES
                                                                                                            ('".$_REQUEST['equipo']."',$Temperatura1,$Humedad1,'$FechaToma',
                                                                                                            '".date("Y/m/d H:i:s")."','".UserGetUID()."')";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $this->frmError["MensajeError"]="Registro Insertado Correctamente";
                    $this->TemperaturasEquipos($_REQUEST['equipo'],$_REQUEST['Fecha'],date('H'),date('i'),'');
                    return true;
              }
      }
        }
        if($_REQUEST['verGrafica']){
          $cadena=explode('-',$Fecha);
      $ano=$cadena[2];
            $mes=$cadena[1];
      $this->consultaRegistrosTemperaturas($_REQUEST['equipo'],$_REQUEST['ano'],$_REQUEST['mes']);
            return true;
        }
        return true;
    }

/**
* Funcion que retorna los distintos tipos de conceptos inventarios que existen en la base de datos
* @return array
*/

    function TipoEquiposTemperaturas(){

        list($dbconn) = GetDBconn();
        $query="SELECT codigo_equipo,descripcion FROM inv_equipos_temperaturas";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'inv_equipos_temperaturas' esta vacia ";
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

    function VerGraficaTemperaturaEquipo(){
        if($_REQUEST['salir']){
      $this->DatosRegTemperaturas($_REQUEST['equipo'],$_REQUEST['ano'],$_REQUEST['mes']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="SELECT date_part('day',fecha_toma) as diaT,date_part('hour',fecha_toma) as hora,date_part('minute',fecha_toma) as minuto,temperatura as medida FROM inv_registro_temperaturas WHERE date_part('month',fecha_toma)='".$_REQUEST['mes']."' AND date_part('year',fecha_toma)='".$_REQUEST['ano']."' AND codigo_equipo='".$_REQUEST['equipo']."' ORDER BY fecha_toma";
    $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos>1){
                while(!$result->EOF){
                    $varsTemp[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $query="SELECT date_part('day',fecha_toma) as diat,date_part('hour',fecha_toma) as hora,date_part('minute',fecha_toma) as minuto,humedad as medida FROM inv_registro_temperaturas WHERE date_part('month',fecha_toma)='".$_REQUEST['mes']."' AND date_part('year',fecha_toma)='".$_REQUEST['ano']."' AND codigo_equipo='".$_REQUEST['equipo']."' ORDER BY fecha_toma";
    $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos>1){
                while(!$result->EOF){
                    $varsHume[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        for($i=0;$i<sizeof($varsTemp);$i++){
          if(!empty($varsTemp[$i]['medida'])){
                $Heure=str_pad($varsTemp[$i]['hora'],2,0, STR_PAD_LEFT);
                $minut=str_pad($varsTemp[$i]['minuto'],2,0, STR_PAD_LEFT);
                $jour=str_pad($varsTemp[$i]['diat'],2,0, STR_PAD_LEFT);
                $array1T[]=$jour.' - '.$Heure.':'.$minut;
                $array2T[]=$varsTemp[$i]['medida'];
            }
        }
        for($i=0;$i<sizeof($varsHume);$i++){
          if(!empty($varsHume[$i]['medida'])){
                $Heure=str_pad($varsHume[$i]['hora'],2,0, STR_PAD_LEFT);
                $minut=str_pad($varsHume[$i]['minuto'],2,0, STR_PAD_LEFT);
                $jour=str_pad($varsHume[$i]['diat'],2,0, STR_PAD_LEFT);
                $array1H[]=$jour.' - '.$Heure.':'.$minut;
                $array2H[]=$varsHume[$i]['medida'];
            }
        }
    $this->GraficaMedidas($_REQUEST['equipo'],$_REQUEST['ano'],$_REQUEST['mes'],$array1T,$array2T,$array1H,$array2H,$_REQUEST['centinela']);
        return true;
    }

        function ActualTomaFisicas(){
        $mensaje="Se Realizo la Actualizacion en el Sistema Correctamente";
        $titulo="ACUALIZACION SISTEMA TOAMS FISICAS";
        $accion=ModuloGetURL('app','InvBodegas','user','LlamaMenuInventarios2',array("Empresa"=>$Empresa,"NombreEmp"=>$NombreEmp,"CentroUtili"=>$CentroUtili,"NombreCU"=>$NombreCU,"BodegaId"=>$BodegaId,"NombreBodega"=>$NombreBodega));
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
    }


    function DefectoValorToma($TomaFisica,$Pr){
    list($dbconn) = GetDBconn();
        $query="SELECT cantidad_fisica FROM inv_toma_fisica_d WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND codigo_producto='$Pr' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND toma_fisica_id='$TomaFisica'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

    function EliminarRegistroTemperatura(){
    list($dbconn) = GetDBconn();
        $registroId=$_REQUEST['registroId'];
        $query="DELETE FROM inv_registro_temperaturas WHERE registro_id='$registroId'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->TemperaturasEquipos($_REQUEST['equipo'],$_REQUEST['Fecha'],$_REQUEST['Hora'],$_REQUEST['Minutos'],'');
        return true;
    }

    function VerDetalleToma(){
    $this->DetalleListadoTomasFisicas($_REQUEST['TomaFisica'],$_REQUEST['Fecha'],$_REQUEST['bandera']);
        return true;
    }

    function LlamaReporteDifTomasFisicas(){
        $bandera='1';
        $this->ListadoTomasFisicas($bandera);
        return true;

    }

    function EliminarToma(){
        $TomaId=$_REQUEST['TomaId'];
        $Fecha=$_REQUEST['Fecha'];
    $bandera=$_REQUEST['bandera'];
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="DELETE FROM inv_toma_fisica_d WHERE toma_fisica_id='".$_REQUEST['TomaId']."' AND
        empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }else{
            $query="DELETE FROM inv_toma_fisica WHERE toma_fisica_id='".$_REQUEST['TomaId']."' AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }else{
        $dbconn->CommitTrans();
                $this->ListadoTomasFisicas($_REQUEST['bandera']);
                return true;
            }
        }
    }

    function ActualizarSistema(){

        $HechoDocBodegaIng=1;
        $HechoDocBodegaEgr=1;
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT codigo_producto,cantidad_fisica,cantidad_sistema FROM inv_toma_fisica_d WHERE toma_fisica_id='".$_REQUEST['TomaFisica']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar inv_toma_fisica_d";
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
        for($i=0;$i<sizeof($vars);$i++){
          $codigoProducto=$vars[$i]['codigo_producto'];
            $query="SELECT costo FROM inventarios WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND codigo_producto='$codigoProducto'";
            $result=$dbconn->Execute($query);
            $CostoPro=$result->fields[0];
      $Diferencia=$vars[$i]['cantidad_sistema']-$vars[$i]['cantidad_fisica'];
            if($Diferencia<=0){
              if($HechoDocBodegaIng==1){
                    $query="SELECT nextval('tmp_bodegas_documentos_d_consecutivo_seq')";
                    $result=$dbconn->Execute($query);
                    $Documento=$result->fields[0];
          $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='I' AND sw_ajuste='1' AND sw_estado='1' AND
                    empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
                    AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
                    $result=$dbconn->Execute($query);
                    $concepto=$result->fields[0];
                    $query="INSERT INTO tmp_bodegas_documentos(documento,
                                                                                                fecha,
                                                                                                total_costo,
                                                                                                transaccion,
                                                                                                observacion,
                                                                                                usuario_id,
                                                                                                fecha_registro,
                                                                                                bodegas_doc_id
                                                                                                )VALUES('$Documento','".date("Y-m-d")."','0',NULL,
                                                                                                '','".UserGetUID()."','".date('Y-m-d H:i:s')."','$concepto')";
                    $dbconn->Execute($query);
          $HechoDocBodegaIng=0;
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar tmp_bodegas_documentos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
                $Diferencia=abs($Diferencia);
                $query="INSERT INTO tmp_bodegas_documentos_d(documento,
                                                        codigo_producto,
                                                                                                cantidad,
                                                                                                total_costo,
                                                                                                bodegas_doc_id,
                                                iva_compra)VALUES('$Documento',
                                                                                                '$codigoProducto','$Diferencia','$CostoPro',
                                                                                                '$concepto','0.0')";
              $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar tmp_bodegas_documentos_d";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
            }elseif($Diferencia>0){
        if($HechoDocBodegaEgr==1){
          $query="SELECT nextval('tmp_bodegas_documentos_documento_seq')";
                    $result=$dbconn->Execute($query);
                    $DocumentoEg=$result->fields[0];
          $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_ajuste='1' AND sw_estado='1' AND
                    empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
                    AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
                    $result=$dbconn->Execute($query);
                   if($result->fields[0]){
                    $conceptoEg=$result->fields[0];
                   }
                   else{
                     $conceptoEg = 'NULL';
                   }
                    $query="INSERT INTO tmp_bodegas_documentos(documento,
                                                                                                fecha,
                                                                                                total_costo,
                                                                                                transaccion,
                                                                                                observacion,
                                                                                                usuario_id,
                                                                                                fecha_registro,
                                                                                                bodegas_doc_id
                                                                                                )VALUES('$DocumentoEg','".date("Y-m-d")."','0',
                                                                                                NULL,
                                                                                                '',".UserGetUID().",'".date('Y-m-d H:i:s')."',".$conceptoEg.")";
                    $dbconn->Execute($query);
          $HechoDocBodegaEgr=0;
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar tmp_bodegas_documentos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
                $Diferencia1=abs($Diferencia);
                $query="INSERT INTO tmp_bodegas_documentos_d(documento,
                                                                                                codigo_producto,
                                                                                                cantidad,
                                                                                                total_costo,
                                                                                                bodegas_doc_id,
                                                iva_compra)VALUES('$DocumentoEg','$codigoProducto','$Diferencia1','$CostoPro',
                                                                                                $conceptoEg,'0.0')";
              $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar tmp_bodegas_documentos_d";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
            }
        }
        $dbconn->CommitTrans();
        $mensaje='Para Finalizar el Proceso de click en Aceptar de lo contrario Cancele';
        $accion=ModuloGetURL('app','InvBodegas','user','FinAcrualizacionSistema',array("Documento"=>$Documento,"concepto"=>$concepto,
        "DocumentoEg"=>$DocumentoEg,"conceptoEg"=>$conceptoEg,"TomaFisica"=>$_REQUEST['TomaFisica']));
        if(!$this->FormaMensaje($mensaje,'ACTUALIZACION TOMAS FISICAS EN SISTEMA',$accion,$boton,1)){
                return false;
        }
        return true;
    }

    function FinAcrualizacionSistema(){

        $Documento=$_REQUEST['Documento'];
        $concepto=$_REQUEST['concepto'];
        $DocumentoEg=$_REQUEST['DocumentoEg'];
        $conceptoEg=$_REQUEST['conceptoEg'];
        $TomaFisica=$_REQUEST['TomaFisica'];
        if($_REQUEST['CancelarProceso']){
          if(!empty($_REQUEST['Documento']) && !empty($_REQUEST['concepto'])){
        $this->EliminaciondeTablas($_REQUEST['Documento'],$_REQUEST['concepto']);
            }
            if(!empty($_REQUEST['DocumentoEg']) && !empty($_REQUEST['conceptoEg'])){
        $this->EliminaciondeTablas($_REQUEST['DocumentoEg'],$_REQUEST['conceptoEg']);
            }
            $this->ListadoTomasFisicas();
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="SELECT codigo_producto,cantidad_fisica FROM inv_toma_fisica_d WHERE toma_fisica_id='".$_REQUEST['TomaFisica']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
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
        for($i=0;$i<sizeof($vars);$i++){
            $CantFisica=$vars[$i]['cantidad_fisica'];
            $codigoProducto=$vars[$i]['codigo_producto'];
            //altera los valores de la existencia como los de la toma fisica
            $query="UPDATE existencias_bodegas SET existencia='$CantFisica' WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND codigo_producto='$codigoProducto'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        if(!empty($Documento) && !empty($concepto)){
      $this->TotalizarDocBodega($Documento,$concepto);
            $vectorI=$this->GuardarDocumentoBD($Documento,$concepto,'I',1);
      $this->EliminacionTomaFisica($TomaFisica);
        }
        if(!empty($DocumentoEg) && !empty($conceptoEg)){
      $this->TotalizarDocBodega($DocumentoEg,$conceptoEg);
            $vectorE=$this->GuardarDocumentoBD($DocumentoEg,$conceptoEg,'E',1);
            $this->EliminacionTomaFisica($TomaFisica);
        }
        $mensaje='Los Documentos fueron Creados Correctamente con las numeraciones '.$vectorI[0].' y '.$vectorE[0];
        $accion=ModuloGetURL('app','InvBodegas','user','LlamaReporteTomasFisicas');
        $boton='Refrescar';
        if(!$this->FormaMensaje($mensaje,'CREACION DOCUMENTO BODEGA',$accion,$boton)){
            return false;
        }
        return true;
    }
/**
* Funcion que elimina las tablas temporales que contienen los datos sobre el documento
* @return boolean
* @param string empresa a la que partenece la bodega donde se va a crear el documento
* @param string centro de utilidad al  que pertenece la bodega donde se va a crear el documento
* @param string codigo de la bodega donde se va a crear el documento
* @param string codigo unico que identifica a el documento
* @param string prefijo del documento
*/
    function EliminacionTomaFisica($TomaFisica){

        list($dbconn) = GetDBconn();
        $query="DELETE FROM inv_toma_fisica_d WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND toma_fisica_id='$TomaFisica'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
    $query="DELETE FROM inv_toma_fisica WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND toma_fisica_id='$TomaFisica'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        return true;
    }


  function LlamaMenuInventarios5(){
        $this->MenuInventarios5();
        return true;
    }

    function LlamaConfirmacionTransferenciasBodegas(){
    $this->ConfirmacionTransferenciasBodegas();
        return true;
    }

    function TranferenciasConfirmarBodega(){
    list($dbconn) = GetDBconn();
        $query="SELECT inv_documento_transferencia_id,bodega,centro_utilidad,fecha_transferencia FROM inv_documento_transferencia_bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND estado='0' AND bodega_destino='".$_SESSION['BODEGAS']['BodegaId']."'";
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

    function LlamaDetalleTransferenciaBodega(){
     $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
     return true;
    }

    function FechasLotesProductos($consecutivo,$codigoProducto){
    list($dbconn) = GetDBconn();
        $query="SELECT fecha_vencimiento,lote,cantidad FROM inv_bodegas_transferencia_fvencimiento_lotes WHERE inv_documento_transferencia_id='$consecutivo' AND codigo_producto='$codigoProducto'";
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

    function SumaFechasLotesProductos($consecutivo,$codigoProducto){
    list($dbconn) = GetDBconn();
        $query="SELECT sum(cantidad) as suma FROM inv_bodegas_transferencia_fvencimiento_lotes WHERE inv_documento_transferencia_id='$consecutivo' AND codigo_producto='$codigoProducto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }


    function LlamaInsertarFechaVenciLotePto(){
    $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia'],1,$_REQUEST['codigoProducto'],$_REQUEST['cantidadTotal'],$_REQUEST['descripcion']);
        return true;
    }


    function ConfirmarTransferenciasBodegas(){
      IncludeLib("despacho_medicamentos");
    if($_REQUEST['regresar']){
      $this->ConfirmacionTransferenciasBodegas();
            return true;
        }
        $ProductosDocumento=$this->ConsultaProductosDocumentoTransaccion($_REQUEST['consecutivo']);
        if($ProductosDocumento){
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']=='1'){
          $datos=$this->FechasLotesProductos($_REQUEST['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                    $suma=$this->SumaFechasLotesProductos($_REQUEST['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                    if(!$datos){
                      $this->frmError["MensajeError"]="Es obligatoria la fecha de vencimiento y el lote para el producto con codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
           $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
                        return true;
                    }elseif($suma['suma']<$ProductosDocumento[$i]['cantidad']){
            $this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
            $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
                        return true;
                    }
                }
            }
        }
        list($dbconn) = GetDBconn();
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_traslado='1' AND
        empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['centroUtilidadOrigen']."' AND bodega='".$_REQUEST['bodegaOrigen']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
    if($result->RecordCount()<1){
            $mensaje="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Origen para Soportar la Repocision";
            $titulo="TRANSFERENCIA ENTRE BODEGAS";
            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        $concepto=$result->fields[0];
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                               numeracion,
                                                                                     fecha,
                                                                                     total_costo,
                                                                                     transaccion,
                                                                                     observacion,
                                                                                     usuario_id,
                                                                                     fecha_registro,
                                                                                     centro_utilidad_transferencia,
                                                                                     bodega_destino_transferencia)VALUES(
                                                                                     '$concepto',
                                                                                     '$numeracion',
                                                                                     '".date("Y-m-d")."',
                                                                                     '0',NULL,'',
                                                                                     '".UserGetUID()."',
                                                                                     '".date("Y-m-d H:i:s")."',
                                                                                     '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                     '".$_SESSION['BODEGAS']['BodegaId']."')";
        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
              $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result = $dbconn->Execute($query);
                $consecutivo=$result->fields[0];
                $query="SELECT costo FROM inventarios WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                $result = $dbconn->Execute($query);
                $costo=$result->fields[0];
                $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                    codigo_producto,
                                                                                                    cantidad,
                                                                                                    total_costo,
                                                                                                    bodegas_doc_id,
                                                                                                    numeracion)VALUES(
                                                                                                    '$consecutivo',
                                                                                                    '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                    '".$ProductosDocumento[$i]['cantidad']."',
                                                                                                    '$costo',
                                                                                                    '$concepto',
                                                                                                    '$numeracion')";
                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }else{
          if($ProductosDocumento[$i]['sw_control_fecha_vencimiento']=='1'){
                    DescargarLotesBodega($_SESSION['BODEGAS']['Empresa'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['bodegaOrigen'],$ProductosDocumento[$i]['codigo_producto'],$ProductosDocumento[$i]['cantidad']);
                    }
          $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['centroUtilidadOrigen']."' AND bodega='".$_REQUEST['bodegaOrigen']."'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        $datos=$result->RecordCount();
                        if($datos){
                            $exis=$result->GetRowAssoc($toUpper=false);
                        }
            $TotalExistencias=$exis['existencia']-$ProductosDocumento[$i]['cantidad'];
                        if($TotalExistencias<0){
                            $mensaje="La Transferencia No tuvo Exito, no hay Suficientes Existencias en Bodega para el Producto".' '.$ProductosDocumento[$i]['codigo_producto'];
                            $titulo="TRANSFERENCIA ENTRE BODEGAS";
                            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
                            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                            return true;
                        }
                        $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['centroUtilidadOrigen']."' AND bodega='".$_REQUEST['bodegaOrigen']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }/*else{
                            $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='$CodigoPro' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }else{
                              $Regs=$result->GetRowAssoc($toUpper=false);
                                if($Regs['existencia']==$TotalExistencias){
                                  return 1;
                                }
                            }
                        }*/
                    }
                }
            }
            $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
            //DOCUMENTO DE INGRESO A LA BODEGA
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='I' AND sw_estado='1' AND sw_traslado='1' AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            if($result->RecordCount()<1){
                $mensaje="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Destino para Soportar la Repocision";
                $titulo="TRANSFERENCIA ENTRE BODEGAS";
                $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
                $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                return true;
            }
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                                  numeracion,
                                                                                        fecha,
                                                                                        total_costo,
                                                                                        transaccion,
                                                                                        observacion,
                                                                                        usuario_id,
                                                                                        fecha_registro,
                                                                                        centro_utilidad_transferencia,
                                                                                        bodega_destino_transferencia)VALUES(
                                                                                        '$concepto',
                                                                                        '$numeracion',
                                                                                        '".date("Y-m-d")."',
                                                                                        '0',NULL,'',
                                                                                        '".UserGetUID()."',
                                                                                        '".date("Y-m-d H:i:s")."',
                                                                                        '".$_REQUEST['centroUtilidadOrigen']."',
                                                                                      '".$_REQUEST['bodegaOrigen']."')";
            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
                for($i=0;$i<sizeof($ProductosDocumento);$i++){
                    $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                    $result = $dbconn->Execute($query);
                    $consecutivo=$result->fields[0];
                    $query="SELECT costo FROM inventarios WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                    $result = $dbconn->Execute($query);
                    $costo=$result->fields[0];
                    $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                        codigo_producto,
                                                                                                        cantidad,
                                                                                                        total_costo,
                                                                                                        bodegas_doc_id,
                                                                                                        numeracion)VALUES(
                                                                                                        '$consecutivo',
                                                                                                        '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                        '".$ProductosDocumento[$i]['cantidad']."',
                                                                                                        '$costo',
                                                                                                        '$concepto',
                                                                                                        '$numeracion')";
                    $result=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']=='1'){
                            $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        saldo,
                                                                                                                                                        cantidad,
                                                                                                                                                        empresa_id,
                                                                                                                                                        centro_utilidad,
                                                                                                                                                        bodega,
                                                                                                                                                        codigo_producto,
                                                                                                                                                        consecutivo
                                                                                                                                                        )SELECT
                                                                                                                                                        fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        '0',
                                                                                                                                                        cantidad,
                                                                                                                                                        '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                                                        '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                                                        '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                                                                        '$consecutivo'
                                                                                                                                                        FROM inv_bodegas_transferencia_fvencimiento_lotes
                                                                                                                                                        WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";
                            $result=$dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en la Base de Datos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                        $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            $datos=$result->RecordCount();
                            if($datos){
                                $exis=$result->GetRowAssoc($toUpper=false);
                            }
                            $TotalExistencias=$exis['existencia']+$ProductosDocumento[$i]['cantidad'];
                            $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                    }
                }
        $query="DELETE FROM inv_documento_transferencia_bodegas WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";
                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
                $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
            }
            $this->GuardarNumeroDocumento($commit=true);
            $mensaje="La Transferencia Fue Exitosa";
            $titulo="TRANSFERENCIA ENTRE BODEGAS";
            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        $mensaje="La Transferencia No tuvo Exito, Consulte al Administrador del Sistema";
        $titulo="TRANSFERENCIA ENTRE BODEGAS";
        $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
    }


    function InsertarFechaVencimientoLotetmp(){
      if($_REQUEST['cancelar']){
      $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
            return true;
        }
        if(!$_REQUEST['fechaVencimiento'] || !$_REQUEST['lote'] || !$_REQUEST['cantidad']){
          $this->frmError["MensajeError"]="La fecha de Vencimiento, Cantidad y el Lote son Datos Obligatorios";
            $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia'],1,$_REQUEST['codigoIns'],$_REQUEST['cantidadTotal'],$_REQUEST['descipIns']);
            return true;
        }
        $cadena=explode('/',$_REQUEST['fechaVencimiento']);
    $fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
        if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y'))){
      $this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
            $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia'],1,$_REQUEST['codigoIns'],$_REQUEST['cantidadTotal'],$_REQUEST['descipIns']);
            return true;
        }
        $sumaTotal=$this->SumaFechasLotesProductos($_REQUEST['consecutivo'],$_REQUEST['codigoIns']);
    if($sumaTotal['suma']+$_REQUEST['cantidad']>$_REQUEST['cantidadTotal']){
          $this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total";
            $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia'],1,$_REQUEST['codigoIns'],$_REQUEST['cantidadTotal'],$_REQUEST['descipIns']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="INSERT INTO inv_bodegas_transferencia_fvencimiento_lotes(inv_documento_transferencia_id,
                                                                        codigo_producto,
                                                                                                                                        fecha_vencimiento,
                                                                                                                                        lote,
                                                                                                                                        cantidad)VALUES(
                                                                                                                                        '".$_REQUEST['consecutivo']."',
                                                                                                                                        '".$_REQUEST['codigoIns']."',
                                                                                                                                        '$fecha',
                                                                                                                                        '".$_REQUEST['lote']."',
                                                                                                                                        '".$_REQUEST['cantidad']."')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
        return true;
  }

    function LlamaModificacionExistenciasMinMax(){
      $conteo=$this->conteo;
        $Of=$_REQUEST['Of'];
        $paso=$_REQUEST['paso'];
    $this->ModificacionExistenciasMinMax($_REQUEST['codProducto'],$_REQUEST['descripcion'],$_REQUEST['codigoProd'],$_REQUEST['descripcionProd'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
        return true;
    }

    function InsertarActualExistencias(){
    if($_REQUEST['regresar']){
      $this->FormaExistenciasBodegas($_REQUEST['codigoProd'],$_REQUEST['descripcionProd'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
            return true;
        }
        list($dbconn) = GetDBconn();
        if(!$_REQUEST['existencia_min']){
          $_REQUEST['existencia_min']=0;
        }
        if(!$_REQUEST['existencia_max']){
      $_REQUEST['existencia_max']=0;
        }
        if($_REQUEST['existencia_min']>$_REQUEST['existencia_max']){
          $this->frmError["MensajeError"]='Las Existencias Mimimas no pueden ser Mayores a las Existencias Maximas ';
      $this->ModificacionExistenciasMinMax($_REQUEST['codProducto'],$_REQUEST['descripcion'],$_REQUEST['codigoProd'],$_REQUEST['descripcionProd'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
            return true;
        }
        $query="UPDATE existencias_bodegas SET existencia_minima='".$_REQUEST['existencia_min']."',existencia_maxima='".$_REQUEST['existencia_max']."' WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND codigo_producto='".$_REQUEST['codProducto']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->FormaExistenciasBodegas($_REQUEST['codigoProd'],$_REQUEST['descripcionProd'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
        return true;

    }



    function LlamaMostrasrLotesProducto(){
      $paso=$_REQUEST['paso'];
        $Of=$_REQUEST['Of'];
    $this->MostrasrLotesProducto($_REQUEST['codigoProducto'],$_REQUEST['DescripProd'],$_REQUEST['codigoProd'],$_REQUEST['descripcionProd'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
        return true;
    }

    function RegresoFormaExistenciasBodegas(){
    $this->FormaExistenciasBodegas($_REQUEST['codigoProd'],$_REQUEST['descripcionProd'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
        return true;

    }

    function lotesFechasProducto($codigo){
    list($dbconn) = GetDBconn();
        $query="SELECT fecha_vencimiento,lote,cantidad FROM bodegas_documentos_d_fvencimiento_lotes WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND codigo_producto='$codigo' ORDER BY fecha_vencimiento";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
              while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
      }
        }
        $result->Close();
        return $vars;
    }

    function LlamaReposicionAutomaticaPtos(){
    $this->PedirBodegaReposicion();
        return true;
    }

    /**
* Funcion que consulta las bodegas existentes en la base de datos exceptuando la bodega en la que se esta trabajando
* @return array
* @param string codigo de la empresa en la que se esta trabajando
* @param string codigo de la bodega en la que se esta trabajando
*/
    function BodegasInventarioReposicion(){

        list($dbconn) = GetDBconn();
        $query="SELECT a.bodega_destino as bodega,a.centro_utilidad_destino as centro_utilidad,b.descripcion,b.tipo_reposicion FROM bodegas_restitucion a,bodegas b WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad_origen='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega_origen='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id=b.empresa_id AND a.centro_utilidad_destino=b.centro_utilidad AND a.bodega_destino=b.bodega AND b.sw_restitucion='1'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
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

    function BuscarPtosParaReposicion(){
    if($_REQUEST['salir']){
      $this->MenuInventarios5();
            return true;
        }
        if($_REQUEST['DatosBodega']==-1){
          $this->frmError["MensajeError"]="Debe Elegir una Bodega";
      $this->PedirBodegaReposicion();
            return true;
        }
        $this->ExistenciasMenores($_REQUEST['DatosBodega']);
        return true;
    }

  function BuscarPtosParaReposicionExisMenores(){
    if($_REQUEST['salir']){
          unset ($_SESSION['EXISTENCIAS']['TRANSFER']);
            unset ($_SESSION['EXISTENCIAS']['CANTIDAD']);
      $this->MenuInventarios5();
            return true;
        }
        if($_REQUEST['insertar']){
      unset ($_SESSION['EXISTENCIAS']['TRANSFER'][$_REQUEST['paso']]);
            unset ($_SESSION['EXISTENCIAS']['CANTIDAD'][$_REQUEST['paso']]);
            if(empty($_REQUEST['paso']))
            {
                $_REQUEST['paso']=1;
            }
            foreach($_REQUEST['seleccion'] as $val=>$codProducto){
                $_SESSION['EXISTENCIAS']['TRANSFER'][$_REQUEST['paso']][$codProducto]=1;
            }
            foreach($_REQUEST['canDespachar'] as $codProducto=>$val){
              if($_SESSION['EXISTENCIAS']['TRANSFER'][$_REQUEST['paso']][$codProducto]==1){
                  $_SESSION['EXISTENCIAS']['CANTIDAD'][$_REQUEST['paso']][$codProducto]=$val;
                }
            }
        }
        if($_REQUEST['guardar']){
          list($dbconn) = GetDBconn();
          $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_traslado='1' AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            if($result->RecordCount()<1){
              unset ($_SESSION['EXISTENCIAS']['TRANSFER']);
              unset ($_SESSION['EXISTENCIAS']['CANTIDAD']);
        $mensaje="Error al Tratar De Insertar La Repocision, No Existe un Documento de la Bodega que Soporte la Repocision";
                $titulo="TRANSFERENCIAS BODEGA";
                $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
                $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                return true;
            }
          $cadena=explode('/',$_REQUEST['DatosBodega']);
            $centroUtilidadDest=$cadena[0];
            $BodegaDest=$cadena[1];
            $dbconn->BeginTrans();
            $query="SELECT nextval('inv_documento_transferencia_b_inv_documento_transferencia_i_seq')";
            $result=$dbconn->Execute($query);
            $consecutivo=$result->fields[0];
            $query="INSERT INTO inv_documento_transferencia_bodegas(inv_documento_transferencia_id,
                                                                                                                            empresa_id,
                                                                                                                            centro_utilidad,
                                                                                                                            bodega,
                                                                                                                            bodega_destino,
                                                                                                                            centro_utilidad_destino,
                                                                                                                            estado,
                                                                                                                            usuario_id,
                                                                                                                            fecha_transferencia)VALUES(
                                                                                                                            '$consecutivo',
                                                                                                                            '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                            '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                            '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                            '$BodegaDest',
                                                                                                                            '$centroUtilidadDest',
                                                                                                                            '0',
                                                                                                                            '".UserGetUID()."',
                                                                                                                            '".date("Y-m-d")."')";
            $dbconn->Execute($query);
            if($dbconn->ErrorNo() !=0 ){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }else{
        foreach($_SESSION['EXISTENCIAS']['CANTIDAD'] as $paso=>$vector){
                  foreach($vector as $producto=>$cantidad){
            $query="INSERT INTO inv_documento_transferencia_bodegas_d(inv_documento_transferencia_id,
                                                                                                                                    codigo_producto,
                                                                                                                                    cantidad)VALUES(
                                                                                                                                    '$consecutivo',
                                                                                                                                    '$producto',
                                                                                                                                    '$cantidad')";
                        $dbconn->Execute($query);
                        if($dbconn->ErrorNo() !=0 ){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
            $dbconn->CommitTrans();
            unset ($_SESSION['EXISTENCIAS']['TRANSFER']);
            unset ($_SESSION['EXISTENCIAS']['CANTIDAD']);
            $mensaje="Todos los Productos han sido Insertados en el documento de la Transferencia";
            $titulo="TRANSFERENCIAS BODEGA";
            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        $this->ExistenciasMenores($_REQUEST['DatosBodega']);
        return true;
    }

    /**
* Funcion que consulta las bodegas existentes en la base de datos exceptuando la bodega en la que se esta trabajando
* @return array
* @param string codigo de la empresa en la que se esta trabajando
* @param string codigo de la bodega en la que se esta trabajando
*/
    function NombreCentroUtilidad($CentroUtili){

        list($dbconn) = GetDBconn();
        $query="SELECT centro_utilidad,descripcion FROM centros_utilidad WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad= '$CentroUtili'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'bodegas' esta vacia ";
                return false;
            }else{
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que llama la forma que visuliza las solicitudes de medicamentos realizadas a la bodega
* @return boolean
*/
    function LlamaSoliciMedica(){
        $this->FormaListadoSolicitudes();
        return true;
    }

/**
* Funcion que busca en la base de datos las solicitudes de medicamentos que no hansido despachadas
* @return array
* @param string codigo de la empresa a la que pertenece la solicitud
* @param string codigo del centro de utilidad al que pertenece la solicitud
* @param string codigo de la bodega donde fue relaizado la silicitud
*/
    function SolicitudesMedicamentos(){

        list($dbconn) = GetDBconn();
        $query = "SELECT a.*,j.cama,j.pieza
                  FROM
                    	(SELECT c.departamento||'-'||c.descripcion as dpto,a.solicitud_id,a.estacion_id,a.fecha_solicitud,a.ingreso,d.nombre as usuarioestacion,
                                 a.usuario_id,c.descripcion as deptoestacion,e.numerodecuenta,
                                 e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,i.tipo_id_paciente,i.paciente_id,
                                 l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac
                          FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,
                               departamentos c,system_usuarios d,cuentas e,
						 planes h,ingresos i,tipos_afiliado k,pacientes l
					 WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' 
                          AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' 
                          AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' 
                          AND a.sw_estado='0' 
                          AND a.estacion_id=b.estacion_id
                          AND b.departamento=c.departamento 
                          AND a.usuario_id=d.usuario_id 
                          AND a.ingreso=e.ingreso 
                          AND (e.estado='1' OR e.estado='2')
					 AND a.ingreso=i.ingreso 
                          AND e.plan_id=h.plan_id 
                          AND k.tipo_afiliado_id=e.tipo_afiliado_id 
                          AND i.tipo_id_paciente=l.tipo_id_paciente 
                          AND i.paciente_id=l.paciente_id) as a
                  LEFT JOIN movimientos_habitacion f ON(a.numerodecuenta=f.numerodecuenta)
                  LEFT JOIN camas j ON(f.cama=j.cama AND f.fecha_egreso is NULL)
                  ORDER BY a.dpto,a.fecha_solicitud";
		$result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) 
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{
			$datos=$result->RecordCount();
			if($datos){
				while(!$result->EOF) 
                    {
                         $vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
                         $result->MoveNext();
               	}
			}
		}
          $result->Close();
          return $vars;
	}

/**
* Funcion que retorna el nombre de la estacion de enfermeria a partir de su codigo
* @return array
* @param string codigo de la estacion
*/
    function NombreEstacion($codigo){

        list($dbconn) = GetDBconn();
        $query = "SELECT descripcion FROM estaciones_enfermeria WHERE estacion_id='$codigo'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'estaciones_enfermeria' esta vacia ";
                return false;
            }else{
        $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que llama la forma que visualiza el detalle de la solicitud realizada a la bodega
* @return boolean
*/
    function DetalleSolicitudMedicamento(){
        $this->FrmAtenderSolicitudPaciente($_REQUEST['SolicitudId'],$_REQUEST['Ingreso'],$_REQUEST['EstacionId'],
        $_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],$_REQUEST['nombrepac'],
        $_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama']);
        return true;
    }
/**
* Funcion que llama la forma que visualiza el detalle de la solicitud realizada a la bodega
* @return boolean
*/
    function LlamaDespachoSolicitudesDpto(){
    $this->DespachoSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto']);
        return true;
    }




/**
* Funcion que retorna el tipo de la solicitud
* @return array
* @param integer codigo unico que identifica la solicitud
* @param integer codigo de la empresa a donde pertenece la bodega
* @param integer codigo del centro de utilidad al que pertenece la bodega
* @param integer bodega a la que realizaron la solicitud
*/
  function GetTipoSolicitudBodega($solicitud){
      list($dbconn) = GetDBconn();
        $query = "SELECT tipo_solicitud,b.observacion,b.fecha_registro,c.nombre
    FROM hc_solicitudes_medicamentos a
    LEFT JOIN hc_auditoria_solicitudes_medicamentos b ON(a.solicitud_id=b.solicitud_id)
    LEFT JOIN system_usuarios c ON(b.usuario_id=c.usuario_id)
    WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
    AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
    AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
    AND a.solicitud_id='$solicitud'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'hc_solicitudes_medicamentos' esta vacia ";
                return false;
            }else{
        $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que retorna los medicamentos de una solicitud de medicamentos
* @return array
* @param integer codigo unico que identifica la solicitud
* @param array datos de la ubicacion de la peticion de la solicitud
*/
    function GetMedicamentosSolicitud($solicitud)
    {
        $query = "(SELECT SMD.solicitud_id,
                                                SMD.consecutivo_d,
                                                NULL as mezcla_recetada_id,
                                                SMD.medicamento_id,
                                                SMD.evolucion_id,
                                                SMD.cant_solicitada,
                                                M.cod_forma_farmacologica,
                                                INVP.descripcion as nomMedicamento,
                                                FF.descripcion as FF,
                                                INV.codigo_producto as codigo_medicamento
                    FROM
                        hc_solicitudes_medicamentos_d SMD,
                                                medicamentos M,
                                                inventarios INV,
                                                inventarios_productos INVP,
                                                inv_med_cod_forma_farmacologica FF
                    WHERE
                                            SMD.solicitud_id = '$solicitud'
                                                AND SMD.medicamento_id=M.codigo_medicamento
                                                AND INV.codigo_producto = M.codigo_medicamento
                                                AND INV.empresa_id ='".$_SESSION['BODEGAS']['Empresa']."'
                                                AND INV.codigo_producto = INVP.codigo_producto
                                                AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica)";
        //echo "<br>++<br>".$query;
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){ //echo "<br>".$query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
            $this->error = "Atención";
            $this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
              $vars=array();
                while(!$result->EOF){
                    array_push($vars,$result->GetRowAssoc($ToUpper = false));
                    $result->MoveNext();
                }
          }//si retornó mezclas de la solicitud
        }
        return $vars;
    }//fin GetMedicamentosSolicitud($solicitud)

/**
* Funcion que retorna los medicamentos de una solicitud de medicamentos de una mezcla
* @return array
* @param integer codigo unico que identifica la solicitud
* @param array datos de la ubicacion de la peticion de la solicitud
*/
    function GetMezclasSolicitud($solicitud)
    {
        $query = "(SELECT
                                    SMD.solicitud_id,
                                    SMD.consecutivo_d,
                                    SMD.mezcla_recetada_id,
                                    SMD.medicamento_id,
                                    SMD.evolucion_id,
                                    SMD.cant_solicitada,
                                    M.cod_forma_farmacologica,
                                    INVP.descripcion as nomMedicamento,
                                    FF.descripcion as FF,
                                    M.codigo_medicamento
              FROM hc_solicitudes_medicamentos_mezclas_d SMD,
                                medicamentos M,
                                    inventarios INV,
                                    inventarios_productos INVP,
                                    inv_med_cod_forma_farmacologica FF
                            WHERE SMD.solicitud_id = '$solicitud'
                                AND SMD.medicamento_id=M.codigo_medicamento
                                    AND INV.codigo_producto = M.codigo_medicamento
                                    AND INV.empresa_id  = '".$_SESSION['BODEGAS']['Empresa']."'
                                    AND INV.codigo_producto=INVP.codigo_producto
                                    AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica)";
        //echo "<br>++<br>".$query;
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){ //echo "<br>".$query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
            $this->error = "Atención";
            $this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
              $vars=array();
                while(!$result->EOF){
                    array_push($vars,$result->GetRowAssoc($ToUpper = false));
                    $result->MoveNext();
                }
          }
        }//si retornó mezclas de la solicitud
        return $vars;
    }//fin GetMedicamentosSolicitud($solicitud)

/**
* Funcion que retorna los insumos de una solicitud
* @return array
* @param integer codigo unico que identifica la solicitud
* @param array datos de la ubicacion de la peticion de la solicitud
*/
    function GetInsumosSolicitud($solicitud){
      list($dbconn) = GetDBconn();
    $query = "(SELECT   SMD.solicitud_id,
                                                SMD.consecutivo_d,
                                                NULL as mezcla_recetada_id,
                                                SMD.medicamento_id as medicamento_id,
                                                NULL as evolucion_id,
                                                SMD.cant_solicitada as cant_solicitada,
                                                NULL as cod_forma_farmacologica,
                                                INVP.descripcion||' '||UNI.descripcion||' '||INVP.contenido_unidad_venta  as nomMedicamento,
                                                NULL as FF,
                                                INV.codigo_producto as codigo_medicamento
              FROM  hc_solicitudes_insumos_d SMD,
              inventarios INV,inventarios_productos INVP,
                            existencias_bodegas EXIS, unidades UNI
                            WHERE SMD.solicitud_id = '$solicitud' AND
                            SMD.medicamento_id = INVP.codigo_producto AND
                            INVP.codigo_producto = INV.codigo_producto AND
                            INV.empresa_id  = '".$_SESSION['BODEGAS']['Empresa']."' AND
                            INV.codigo_producto = EXIS.codigo_producto AND
              INV.empresa_id = EXIS.empresa_id AND
                            EXIS.centro_utilidad = '".$_SESSION['BODEGAS']['CentroUtili']."' AND
                            EXIS.bodega = '".$_SESSION['BODEGAS']['BodegaId']."' AND
                            INVP.unidad_id = UNI.unidad_id)";
              //borrado por que nelly no tiene una buena clasificacion de los grupos de inventarios
              //AND INVP.grupo_id = INVG.grupo_id AND INVG.sw_insumos='1'
        //echo "<br>++<br>".$query;
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){ //echo "<br>".$query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
            $this->error = "Atención";
            $this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
              $vars=array();
                while(!$result->EOF){
                    array_push($vars,$result->GetRowAssoc($ToUpper = false));
                    $result->MoveNext();
                }
          }
        }//si retornó mezclas de la solicitud
        return $vars;
    }//fin GetMedicamentosSolicitud($solicitud)

/**
* Funcion que retorna las existencias en bodega de un producto
* @return array
* @param integer codigo del producto
* @param array datos de la ubicacion de la bodega de la cual se van a consultar las existencias
*/
    function GetCantidadExistenteBodega($medicamento)
    {
        //echo "<br>".$medicamento;
        $query = "SELECT existencia
                            FROM existencias_bodegas
                            WHERE empresa_id = '".$_SESSION['BODEGAS']['Empresa']."' AND
                            centro_utilidad = '".$_SESSION['BODEGAS']['CentroUtili']."' AND
                            codigo_producto = '".$medicamento."' AND
                            bodega = '".$_SESSION['BODEGAS']['BodegaId']."'";
        //echo "query<br><br>".$query;
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){ //echo "<br>".$query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
            $this->error = "Atención";
            $this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de la solicitud seleccionada";
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
        $vars=$result->GetRowAssoc($toUpper=false);
            }
        }//si retornó mezclas de la solicitud
        return $vars;
    }

/**
* Funcion que retorna los medicamento similares a un medicamento especifico
* @return array
* @param string codigo unico que identifica al medicamento  como producto
* @param string codigo del producto como medicamento
* @param integer cantidad solicitada del producto
* @param array datos de la ubicacion de la peticion de la solicitud
*/
    function GetMedicamentosSimilares($cod_prod,$CantSolicitada)
    {
        $query="SELECT INV.codigo_producto,
                M.codigo_medicamento,
                INVP.descripcion as nomMedicamento,
                        M.cod_concentracion,
                        FF.descripcion as FF
                    FROM Inventarios INV,
                        inventarios_productos INVP,
                        existencias_bodegas EB,
                        medicamentos M,
                        inv_med_cod_forma_farmacologica FF,
                        (SELECT cod_principio_activo, cod_anatomofarmacologico, cod_forma_farmacologica FROM medicamentos WHERE codigo_medicamento='".$cod_prod."') PA
                    WHERE PA.cod_principio_activo=M.cod_principio_activo
                        AND PA.cod_anatomofarmacologico=M.cod_anatomofarmacologico
                        AND PA.cod_forma_farmacologica=M.cod_forma_farmacologica
                        AND INV.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                        AND INV.codigo_producto=INVP.codigo_producto
                        AND EB.codigo_producto=INV.codigo_producto
                        --AND EB.existencia >= ".$CantSolicitada."
                        AND EB.empresa_id=INV.empresa_id
                        AND EB.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
                        AND EB.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
                        AND M.codigo_medicamento = INV.codigo_producto
                        AND FF.cod_forma_farmacologica = M.cod_forma_farmacologica";
        
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        { echo "<br>".$query. " ".$dbconn->ErrorMsg(); exit;//me imprime el error que existe
            $this->error = "Atención";
            $this->mensajeDeError = "Ocurrió un error al intentar obtener los medicamentos de igual tipo";
            return false;
        }
          $Vector=array();
            while (!$result->EOF)
            {
                array_push($Vector,$result->GetRowAssoc($ToUpper = false));
                $result->MoveNext();
            }
        //echo "<br><br>";print_r($datos); exit;
        return $Vector;
    }//GetMedicamentosSimilares

    function ConfirmacionDespachoDetalleSolicitud($SolicitudId,$TipoSolicitud){

      list($dbconn) = GetDBconn();
    if($TipoSolicitud=='I'){
      $query="SELECT a.medicamento_id as codigo_producto,a.cant_solicitada AS cantidad,b.descripcion,a.consecutivo_d
            FROM hc_solicitudes_insumos_d a,inventarios_productos b,existencias_bodegas c
            WHERE a.solicitud_id=".$SolicitudId." AND a.codigo_producto=b.codigo_producto AND
            b.codigo_producto=c.codigo_producto AND c.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND c.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND c.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
            ";
        }else{
      $query="SELECT a.medicamento_id as codigo_producto,a.cant_solicitada as cantidad,b.descripcion,ff.descripcion as forma,a.evolucion_id,a.ingreso,a.consecutivo_d
      FROM hc_solicitudes_medicamentos_d a,inventarios_productos b,medicamentos c,inv_med_cod_forma_farmacologica ff,existencias_bodegas d
            WHERE solicitud_id=".$SolicitudId." AND a.medicamento_id=b.codigo_producto AND
            b.codigo_producto=c.codigo_medicamento AND FF.cod_forma_farmacologica=c.cod_forma_farmacologica AND
            b.codigo_producto=d.codigo_producto AND d.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND d.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND d.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'";
        }
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
            if($result->RecordCount()>0){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        return $vars;
    }

/**
* Funcion que realiza el despacho de la solicitud de la bodega
* @return boolean
*/
    function DespacharMedicamentos(){

        $CheckDespachar = $_REQUEST['CheckDespachar'];
        $CantDespachar = $_REQUEST['CantDespachar'];
        $SelectMedicamentos = $_REQUEST['SelectMedicamentos'];
        $datos_bodega = $_REQUEST['datos_bodega'];
        GLOBAL $ADODB_FETCH_MODE;
        list($dbconn) = GetDBconn();
        $Salir=$_REQUEST['Salir'];
        if($Salir){
      $this->FormaListadoSolicitudes();
            return true;
        }
        list($dbconn) = GetDBconn();
        if(sizeof($CheckDespachar) == 0){
            $mensaje = "DEBE SELECCIONAR LOS MEDICAMENTOS A DESPACHO";
            $titulo = "DESPACHO SOLICITUD MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','CallFrmAtenderSolicitudPaciente',array("Ingreso"=>$_REQUEST['Ingreso'],"SolicitudId"=>$_REQUEST['SolicitudId'],"EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"usuarioestacion"=>$_REQUEST['usuarioestacion'],
            "nombrepac"=>$_REQUEST['nombrepac'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"cama"=>$_REQUEST['cama']));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }

        $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='E'
        AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
        if(empty($concepto)){
            $mensaje = "NO EXISTE EL TIPO DE DOCUMENTO PARA REALIZAR EL MOVIMIENTO AUTOMATICO EN LA BODEGA";
            $titulo = "DESPACHO SOLICITUD MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','CallFrmAtenderSolicitudPaciente',array("Ingreso"=>$_REQUEST['Ingreso'],"SolicitudId"=>$_REQUEST['SolicitudId'],"EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"usuarioestacion"=>$_REQUEST['usuarioestacion'],
            "nombrepac"=>$_REQUEST['nombrepac'],"tipo_id_paciente"=>$_REQUEST['tipo_id_paciente'],"paciente_id"=>$_REQUEST['paciente_id'],"cama"=>$_REQUEST['cama']));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
    //ordenar por # de solicitud para generar los documentos de bodega
        for($i=0; $i<sizeof($CheckDespachar); $i++){
            $y= explode(".-.",$CheckDespachar[$i]);
            $temp = array();//echo "<br><br>compara si existe el key = ".$y[3]." en el select"; print_r($SelectMedicamentos);
            //echo "<br>respuesta-> ".array_key_exists ( $y[3], $SelectMedicamentos);
            if(array_key_exists($y[3], $SelectMedicamentos)){//echo "<br><br>compara si medicamento del check = ".$y[1]." igual al del select ".$SelectMedicamentos[$y[3]];
                //echo "<br>respuesta=> ".strcmp($y[1],$SelectMedicamentos[$y[3]]);
                if(strcmp($y[1],$SelectMedicamentos[$y[3]]) != 0)//son diferentes
                {
                    $temp = $y;
                    $temp[1] = $SelectMedicamentos[$y[3]];
                    $datos[$y[0]][] = $temp;
                }else{
                        $datos[$y[0]][] = $y;
                }
            }else{
                    $datos[$y[0]][] = $y;
            }
        }
        $this->ConfirmacionDespachoPendientes($datos,$CantDespachar,$datos_bodega,$concepto,$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
        $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama']);
        return true;

        //fin por cada solicitud
    }//fin functionUpdateX

    function ConfirmarDespachoSolicitudes(){

    //Se adicionó por error en le REQUEST
    $_REQUEST['datos'] = SessionGetVar("datosConfirmarDespachoSolicitudes");
    //FIN Se adicionó por error en le REQUEST
    
    if($_REQUEST['cancelar']){
            $Motivos=$_REQUEST['motivoCancelacion'];
      foreach($_REQUEST['cancelar'] as $codigo=>$cantidad){
        if(empty($Motivos[$codigo]) || $Motivos[$codigo]==-1){
          $this->frmError["MensajeError"]='Debe Especificar Algun Motivo para la cancelacion del Producto '.$codigo;
          $this->ConfirmacionDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
                    $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones']);
                    return true;
                }
            }
        }
        list($dbconn) = GetDBconn();
        //Numero Documento
        if(!empty($_REQUEST['observacion_elimina'])){
      $query="DELETE FROM hc_solicitudes_observaciones_despachos WHERE observacion_id='".$_REQUEST['observacion_elimina']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->frmError["MensajeError"]='Observacion Eliminada';
            $this->ConfirmacionDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
            $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones']);
            return true;
        }
        if(!empty($_REQUEST['editar'])){
      $this->EditarDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
            $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones'],$_REQUEST['editar']);
            return true;
        }
        if(!empty($_REQUEST['insertarObservacion'])){
      $this->EditarDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
            $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones']);
            return true;
        }
        $datos=$_REQUEST['datos'];
    $CantDespachar=$_REQUEST['CantDespachar'];
        $concepto=$_REQUEST['concepto'];
        //Tipo Documento
        $query="SELECT nextval('bodegas_documento_despacho_med_documento_despacho_id_seq')";
        $result = $dbconn->Execute($query);
        $documento=$result->fields[0];
        //Insertar Documento
        $query="INSERT INTO bodegas_documento_despacho_med(documento_despacho_id,bodegas_doc_id,
                                                  fecha,total_costo,
                                                  observacion,usuario_id,
                                                                                            fecha_registro)
                                                                                VALUES('$documento',$concepto,
                                                                                      '".$_REQUEST['Fecha']."','0',
                                                                                      '".$_REQUEST['observaciones']."','".UserGetUID()."',
                                                                                      '".date("Y-m-d H:i:s")."')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }
        //echo "<br>vector ordenado = "; print_r($datos);
        foreach($datos as $key=>$value){
            //echo "<br>key==>.".$key."<br>";  print_r($value);
            foreach($value as   $keyVal=>$valores)//por cada medicamento de la solicitud
            {
                //echo "<br>key==>.".$keyVal."<br>";  print_r($valores);
                $contador=$valores[3];
                $costoProducto=$this->HallarCostoProducto($_SESSION['BODEGAS']['Empresa'],$valores[1]);
                if($_REQUEST['TipoSolicitud']!='I'){
                    $query="SELECT nextval('bodegas_documento_despacho_med_d_consecutivo_depacho_seq')";
                    $result = $dbconn->Execute($query);
                    $consecutivo=$result->fields[0];
                    $query="INSERT INTO bodegas_documento_despacho_med_d(consecutivo_depacho,documento_despacho_id,
                                                            codigo_producto,cantidad,
                                                                                                        total_costo,consecutivo_solicitud)
                                                            VALUES('$consecutivo','$documento',
                                                                                                        '".$valores[1]."','".$CantDespachar[$contador]."',
                                                                                                        '$costoProducto','".$valores[5]."')";
                }else{
          $query="SELECT nextval('bodegas_documento_despacho_ins_d_consecutivo_depacho_seq')";
                    $result = $dbconn->Execute($query);
                    $consecutivo=$result->fields[0];
                    $query="INSERT INTO bodegas_documento_despacho_ins_d(consecutivo_depacho,documento_despacho_id,
                                                            codigo_producto,cantidad,
                                                                                                        total_costo,consecutivo_solicitud)
                                                            VALUES('$consecutivo','$documento',
                                                                                                        '".$valores[1]."','".$CantDespachar[$contador]."',
                                                                                                        '$costoProducto','".$valores[5]."')";
                }

                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
            }
        }
        $totalizCostoDoc=$this->TotalizarDocDepacho($documento,$_REQUEST['TipoSolicitud']);
        //$InsSolicitudes=$this->tmpInsertarSolicitudes($_REQUEST['SolicitudId'],$documento);
        $query="UPDATE hc_solicitudes_medicamentos SET sw_estado='1',documento_despacho='".$documento."' WHERE solicitud_id='".$_REQUEST['SolicitudId']."' AND tipo_solicitud='".$_REQUEST['TipoSolicitud']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{

          if($_REQUEST['pendiente']){
                $query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq')";
                $result = $dbconn->Execute($query);
                $solicitudActiva=$result->fields[0];
                $query="INSERT INTO hc_solicitudes_medicamentos(
                solicitud_id,ingreso,bodega,empresa_id,centro_utilidad,
                usuario_id,sw_estado,fecha_solicitud,
                estacion_id,tipo_solicitud,documento_despacho,bodegas_doc_id,
                numeracion)VALUES('$solicitudActiva','".$_REQUEST['Ingreso']."','".$_SESSION['BODEGAS']['BodegaId']."','".$_SESSION['BODEGAS']['Empresa']."',
                '".$_SESSION['BODEGAS']['CentroUtili']."','".UserGetUID()."','0','".date("Y-m-d H:i:s")."','".$_REQUEST['EstacionId']."',
                '".$_REQUEST['TipoSolicitud']."',NULL,NULL,NULL);";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
                foreach($_REQUEST['pendiente'] as $codigo=>$cantidad){
                  (list($canti,$evolucion,$ing)=explode('||//',$cantidad));
                  if($_REQUEST['TipoSolicitud']=='I'){
            $query="INSERT INTO hc_solicitudes_insumos_d(
                        medicamento_id,cant_solicitada,solicitud_id)VALUES(
                        '$codigo','$canti','$solicitudActiva');";
                    }else{
                      $query="INSERT INTO hc_solicitudes_medicamentos_d(
                        solicitud_id,medicamento_id,evolucion_id,cant_solicitada,
                        mezcla_recetada_id,ingreso)VALUES(
                        '$solicitudActiva','$codigo',NULL,'$canti',NULL,'".$ing."');";
                    }

                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }
                }
            }

            if($_REQUEST['cancelar']){
              $Motivos=$_REQUEST['motivoCancelacion'];
                $Observaciones=$_REQUEST['observaciones'];
                $query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq')";
                $result = $dbconn->Execute($query);
                $solicitudInactiva=$result->fields[0];
                $query="INSERT INTO hc_solicitudes_medicamentos(
                solicitud_id,ingreso,bodega,empresa_id,centro_utilidad,
                usuario_id,sw_estado,fecha_solicitud,
                estacion_id,tipo_solicitud,documento_despacho,bodegas_doc_id,
                numeracion)VALUES('$solicitudInactiva','".$_REQUEST['Ingreso']."','".$_SESSION['BODEGAS']['BodegaId']."','".$_SESSION['BODEGAS']['Empresa']."',
                '".$_SESSION['BODEGAS']['CentroUtili']."','".UserGetUID()."','3','".date("Y-m-d H:i:s")."','".$_REQUEST['EstacionId']."',
                '".$_REQUEST['TipoSolicitud']."',NULL,NULL,NULL)";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
                foreach($_REQUEST['cancelar'] as $codigo=>$cantidad){
          (list($canti,$evolucion,$ing)=explode('||//',$cantidad));
                  if($_REQUEST['TipoSolicitud']=='I'){
                      $query="SELECT nextval('hc_solicitudes_insumos_d_consecutivo_d_seq')";
                        $result = $dbconn->Execute($query);
                        $consec=$result->fields[0];
            $query="INSERT INTO hc_solicitudes_insumos_d(
                        consecutivo_d,medicamento_id,cant_solicitada,solicitud_id)VALUES(
                        '$consec','$codigo','$canti','$solicitudInactiva');";
                        $query.="INSERT INTO hc_solicitudes_insumos_motivos_cancela(
                        consecutivo_d,motivo_id,observaciones)VALUES('$consec','".$Motivos[$codigo]."','".$Observaciones[$codigo]."');";
                    }else{
                      $query="SELECT nextval('hc_solicitudes_medicamentos_d_consecutivo_d_seq')";
                        $result = $dbconn->Execute($query);
                        $consec=$result->fields[0];
                        $query="INSERT INTO hc_solicitudes_medicamentos_d(
                        consecutivo_d,solicitud_id,medicamento_id,evolucion_id,
                        cant_solicitada,mezcla_recetada_id,ingreso)VALUES(
                        '$consec','$solicitudInactiva','$codigo',NULL,
                        '$canti',NULL,'".$ing."');";
                        $query.="INSERT INTO hc_solicitudes_medicamentos_motivos_cancela(
                        consecutivo_d,motivo_id,observaciones)VALUES('$consec','".$Motivos[$codigo]."','".$Observaciones[$codigo]."');";
                    }

                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }
                }
            }
          $this->GuardarNumeroDocumento($commit=true);
          $mensaje="Documento Despachado Correctamente";
            if(!empty($solicitudActiva)){
        $mensaje.=" , Una Nueva Solictud Fue Creada No. ".$solicitudActiva;
            }
            $titulo="DESPACHO DE MEDICAMENTOS";
            $accion=ModuloGetURL('app','InvBodegas','user','FormaListadoSolicitudes');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
    }
    }
  function LlamaEditarDespachoPendientes(){

        $this->EditarDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
        $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones'],$_REQUEST['editar']);
        return true;

    }

    function DespachoMyIAutomatico(){
    //variables paso

        IncludeLib("despacho_medicamentos");
      $Solicitud=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['SOLICITUD'];
        $cuenta=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['CUENTA'];
        $PlanId=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['PLAN'];
    //fin variables paso
        if(!$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['SOLICITUD']){
      $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "NO EXISTE LA VARIABLE SOLICITUD";
            $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=1;
            return true;
        }
        if(!$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['CUENTA']){
      $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "NO EXISTE LA VARIABLE CUENTA";
            $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=1;
            return true;
        }
    if(!$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['PLAN']){
      $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "NO EXISTE LA VARIABLE PLAN";
            $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=1;
            return true;
        }
        list($dbconn) = GetDBconn();

    $query="SELECT a.tipo_solicitud,a.empresa_id,a.centro_utilidad,a.bodega
        FROM hc_solicitudes_medicamentos a
        WHERE a.solicitud_id='".$Solicitud."' AND a.sw_estado='4'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos - hc_solicitudes_medicamentos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
            if($result->RecordCount()>0){
                $vars=$result->GetRowAssoc($toUpper=false);
            }else{
        $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "NO EXISTE ESTE NUMERO DE SOLICITUD";
              $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=1;
        $this->GuardarNumeroDocumento($commit=false);
                return true;
            }
        }

    $query="SELECT *
        FROM hc_solicitudes_medicamentos a,bodegas b
        WHERE a.solicitud_id='".$Solicitud."' AND a.sw_estado='4' AND a.bodega=b.bodega AND b.sw_consumo_directo='1'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos - hc_solicitudes_medicamentos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
            if($result->RecordCount()<0){
        $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "IMPOSIBLE EL DESPACHO DE ESTA BODEGA PORQUE NO MANEJA EL CONSUMO DIRECTO";
              $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=1;
        $this->GuardarNumeroDocumento($commit=false);
                return true;
            }
        }

        $TipoSolicitud=$vars['tipo_solicitud'];
        $Empresa=$vars['empresa_id'];
        $CentroUtilidad=$vars['centro_utilidad'];
    $Bodega=$vars['bodega'];
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='E'
        AND empresa_id='".$Empresa."' AND centro_utilidad='".$CentroUtilidad."' AND bodega='".$Bodega."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
        if(empty($concepto)){
            $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "NO EXISTE EL TIPO DE DOCUMENTO PARA REALIZAR EL MOVIMIENTO AUTOMATICO EN LA BODEGA";
            $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=1;
      $this->GuardarNumeroDocumento($commit=false);
      return true;
        }
        //Numero Documento
        $query="SELECT nextval('bodegas_documento_despacho_med_documento_despacho_id_seq')";
        $result = $dbconn->Execute($query);
        $documento=$result->fields[0];
        //Insertar Documento
        $query="INSERT INTO bodegas_documento_despacho_med(documento_despacho_id,bodegas_doc_id,
                                                  fecha,total_costo,
                                                  observacion,usuario_id,
                                                                                            fecha_registro)
                                                                                VALUES('$documento',$concepto,
                                                                                      '".date("Y-m-d")."','0',
                                                                                      '','".UserGetUID()."',
                                                                                      '".date("Y-m-d H:i:s")."')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos - bodegas_documento_despacho_med";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      if($TipoSolicitud!='I'){
        $query="SELECT a.consecutivo_d,a.medicamento_id as producto,a.cant_solicitada as cantidad
                FROM hc_solicitudes_medicamentos_d a
                WHERE a.solicitud_id='$Solicitud'";
            }else{
        $query="SELECT a.consecutivo_d,a.medicamento_id as producto,a.cant_solicitada as cantidad
                FROM hc_solicitudes_insumos_d a
                WHERE a.solicitud_id='$Solicitud'";
            }
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos - hc_solicitudes_insumos_d";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
                if($result->RecordCount()>0){
          while(!$result->EOF) {
                      $varsPtos[]=$result->GetRowAssoc($toUpper=false);
            $result->MoveNext();
                    }
                }
            }
          for($i=0;$i<sizeof($varsPtos);$i++){
              $codigoProducto=$varsPtos[$i]['producto'];
                $cantDespachar=$varsPtos[$i]['cantidad'];
                $consecutivoSolicitud=$varsPtos[$i]['consecutivo_d'];
                $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
                if($TipoSolicitud!='I'){
                    $query="INSERT INTO bodegas_documento_despacho_med_d(documento_despacho_id,
                                                                                                        codigo_producto,cantidad,
                                                                                                        total_costo,consecutivo_solicitud)
                                                                                                        VALUES('$documento',
                                                                                                        '".$codigoProducto."','".$cantDespachar."',
                                                                                                        '$costoProducto','".$consecutivoSolicitud."')";
                }else{
                    $query="INSERT INTO bodegas_documento_despacho_ins_d(documento_despacho_id,
                                                                                                        codigo_producto,cantidad,
                                                                                                        total_costo,consecutivo_solicitud)
                                                                                                        VALUES('$documento',
                                                                                                        '".$codigoProducto."','".$cantDespachar."',
                                                                                                        '$costoProducto','".$consecutivoSolicitud."')";
                }
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Base de Datos - bodegas_documento_despacho_ins_d";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
            }
            $totalizCostoDoc=$this->TotalizarDocDepacho($documento,$TipoSolicitud);
            $query="UPDATE hc_solicitudes_medicamentos SET sw_estado='1',documento_despacho='".$documento."' WHERE solicitud_id='".$Solicitud."' AND tipo_solicitud='".$TipoSolicitud."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos - hc_solicitudes_medicamentos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
                $this->GuardarNumeroDocumento($commit=true);
                $_SESSION['DESPACHO']['MEDICAMENTOS']['SOLICITUD']=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['SOLICITUD'];
                $_SESSION['DESPACHO']['MEDICAMENTOS']['CUENTA']=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['CUENTA'];
                $_SESSION['DESPACHO']['MEDICAMENTOS']['PLAN']=$_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['PLAN'];
                DocumentoDespachoMedicamentos();
                if($_SESSION['DESPACHO']['MEDICAMENTOS']['RETORNO']!=4){
          $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']=$_SESSION['DESPACHO']['MEDICAMENTOS']['Mensaje'];
                    UNSET($_SESSION['DESPACHO']['MEDICAMENTOS']);
                    return true;
                }else{
                  UNSET($_SESSION['DESPACHO']['MEDICAMENTOS']);
          $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['Mensaje']= "Documento Despachado Correctamente";
                    $_SESSION['DESPACHO']['MEDICAMENTOS_E_INSUMOS']['RETORNO']=4;
                    return true;;
                }
          }
        }
    }


    function MtosDespachadosImpresion($numeracion,$concepto){
    list($dbconn) = GetDBconn();
    $query="SELECT b.codigo_producto,b.descripcion_abreviada as descripmed,a.cantidad,c.abreviatura
        FROM bodegas_documentos_d a,inventarios_productos b,unidades c,bodegas_doc_numeraciones d
        WHERE a.numeracion='$numeracion' AND a.bodegas_doc_id='$concepto' AND a.bodegas_doc_id=d.bodegas_doc_id AND
        d.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND d.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
        AND d.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.codigo_producto=b.codigo_producto AND c.unidad_id=b.unidad_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $varsMed[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        return $varsMed;
    }

    function DatosPacienteDespachoImpresion($solicitud){
    list($dbconn) = GetDBconn();
    $query="SELECT b.tipo_id_paciente,b.paciente_id,
        c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombre,
        d.rango,h.tipo_afiliado_nombre as tipo_afiliado_id,f.pieza,f.cama,g.plan_descripcion as plan
        FROM hc_solicitudes_medicamentos a,ingresos b,pacientes c,cuentas d,movimientos_habitacion e,camas f,planes g,tipos_afiliado h
        WHERE a.solicitud_id='$solicitud' AND a.ingreso=b.ingreso AND
        b.tipo_id_paciente=c.tipo_id_paciente AND b.paciente_id=c.paciente_id AND
        a.ingreso=d.ingreso AND (d.estado='1' OR d.estado='2') AND d.numerodecuenta=e.numerodecuenta AND
        e.fecha_egreso is NULL AND e.cama=f.cama AND d.plan_id=g.plan_id AND h.tipo_afiliado_id=d.tipo_afiliado_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                $varsPac=$result->GetRowAssoc($toUpper=false);
            }
        }
        return $varsPac;
    }
/**
* Funcion que llama la forma que visualiza el detalle de la solicitud realizada a la bodega
* @return boolean
*/
    function CallFrmAtenderSolicitudPaciente(){
        $this->FrmAtenderSolicitudPaciente($_REQUEST['SolicitudId'],$_REQUEST['Ingreso'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
        $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama']);
        return true;
    }

/**
* Funcion que inserta la relacion del documento generado y la solicitud
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo del documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param integer prefijo del documento
* @param integer codigo de la solicitud
*/

  function tmpInsertarSolicitudes($solicitud,$documento){
      list($dbconn) = GetDBconn();
      $query ="INSERT INTO tmp_bodegas_documentos_hc_solicitudes(solicitud_id,
                                                                                                                    documento)VALUES(
                                                                                                                    '$solicitud',
                                                                                                                    '$documento')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      return 1;
        }
        return 0;
    }

/**
* Funcion que inserta la relacion del documento generado y la solicitud
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo del documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param integer prefijo del documento
* @param integer codigo de la solicitud
*/

  function InsertarSolicitudes($concepto,$numeracion,$solicitud){
      list($dbconn) = GetDBconn();
      $query ="INSERT INTO bodegas_documentos_hc_solicitudes(solicitud_id,
                                                                                                                    estado,
                                                                                                                    bodegas_doc_id,
                                                                                                                    numeracion)VALUES(
                                                                                                                    '$solicitud',
                                                                                                                    '0',
                                                                                                                    '$concepto',
                                                                                                                    '$numeracion')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      return 1;
        }
        return 0;
    }

/**
* Funcion que cuenta los medicamentos pertenecientes a una mezcla espacifica
* @return array
* @param codigo unico que identifica la solicitud
* @param codigo que identifica la mezcla a la que pertenecen los medicamentos
*/
    function rowspanMezclas($solicitud,$mezcla){
    list($dbconn) = GetDBconn();
        $query="SELECT count(*) as contador FROM hc_solicitudes_medicamentos_mezclas_d WHERE solicitud_id='$solicitud' AND mezcla_recetada_id='$mezcla'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
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
* Funcion que llama a la forma que visualiza las devoluciones solicitadas
* @return boolean
*/

    function LlamaDevolucionMedicamentos(){
        $this->FormaDevolucionMedicamentos();
        return true;
    }

/**
* Funcion que consulta en la base de datos las devoluciones para recibir
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo de la bodega donde se va a crear el documento
*/
    function DevolucionesMedicamentos(){

        list($dbconn) = GetDBconn();
        $query = "SELECT c.departamento||'-'||c.descripcion as dpto,a.documento,a.estacion_id,a.fecha_registro as fecha,a.ingreso,
        d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
        e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,i.tipo_id_paciente,i.paciente_id,
        l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
        j.cama,j.pieza,a.observacion,est.descripcion as parametro
        FROM inv_solicitudes_devolucion a
    LEFT JOIN estacion_enfermeria_parametros_devolucion est ON(est.parametro_devolucion_id=a.parametro_devolucion_id),
    estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e
        LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta)
        LEFT JOIN camas j ON(f.cama=j.cama AND f.fecha_egreso is NULL)
        ,planes h,ingresos i,tipos_afiliado k,pacientes l
        WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.estado='0' AND
        a.estacion_id=b.estacion_id AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
        AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

/**
* Funcion que retorna al menu principal
* @return boolean
*/
    function RetornarFormaMenuDevoluciones(){
    $this->MenuInventariosDevolucion();
        return true;
    }

/**
* Funcion que llama la forma que visualiza el detalle de una devolucion realizada a la bodega
* @return boolean
*/

    function DetalleDevolucionMedicamentos(){
        $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],
        '','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
        return true;
    }

/**
* Funcion inserta el documento de soporte de una devolcion
* @return boolean
*/
    function RealizarDevolucionMedicamentos(){

    IncludeLib("despacho_medicamentos");
        $EstacionId=$_REQUEST['EstacionId'];
        $NombreEstacion=$_REQUEST['NombreEstacion'];
    $Documento=$_REQUEST['Documento'];
        $Fecha=$_REQUEST['Fecha'];
        $Ingreso=$_REQUEST['Ingreso'];
        $observaciones=$_REQUEST['observaciones'];
        $checkboxDevol=$_REQUEST['checkboxDevol'];
        $banderaDest=1;
        if($_REQUEST['Salir']){
      $this->FormaDevolucionMedicamentos();
            return true;
        }

    if($_REQUEST['CancelarProductos']){
      if(sizeof($checkboxDevol) == 0){
        $mensaje = "DEBE SELECCIONAR LOS PRODUCTOS QUE SE VAN A CANCELAR DE LA SOLICITUD DE DEVOLUCION";
        $titulo = "DEVOLUCION DE MEDICAMENTOS";
        $accion = ModuloGetURL('app','InvBodegas','user','DetalleDevolucionMedicamentos',array("EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"Documento"=>$_REQUEST['Documento'],"Ingreso"=>$_REQUEST['Ingreso'],"observaciones"=>$_REQUEST['observaciones'],"bandera"=>$_REQUEST['bandera'],"codigoProducto"=>$_REQUEST['codigoProducto'],"descripcion"=>$_REQUEST['descripcion'],"Cantidad"=>$_REQUEST['Cantidad'],"consecutivo"=>$_REQUEST['consecutivo'],
          "identificacion"=>$_REQUEST['identificacion'],"nombrepac"=>$_REQUEST['nombrepac'],"cama"=>$_REQUEST['cama'],"pieza"=>$_REQUEST['pieza']));
        $boton = "REGRESAR";
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
      }
      $this->CancelarSolicitudesDevoluciones($checkboxDevol,$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],$_REQUEST['bandera'],$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],
        $_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza']);
      return true;
    }

    if(sizeof($checkboxDevol) == 0){
            $mensaje = "DEBE SELECCIONAR LOS MEDICAMENTOS E INSUMOS A RECIBIR DE LA DEVOLUCION";
            $titulo = "DEVOLUCION DE MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','DetalleDevolucionMedicamentos',array("EstacionId"=>$_REQUEST['EstacionId'],"NombreEstacion"=>$_REQUEST['NombreEstacion'],"Fecha"=>$_REQUEST['Fecha'],"Documento"=>$_REQUEST['Documento'],"Ingreso"=>$_REQUEST['Ingreso'],"observaciones"=>$_REQUEST['observaciones'],"bandera"=>$_REQUEST['bandera'],"codigoProducto"=>$_REQUEST['codigoProducto'],"descripcion"=>$_REQUEST['descripcion'],"Cantidad"=>$_REQUEST['Cantidad'],"consecutivo"=>$_REQUEST['consecutivo'],
        "identificacion"=>$_REQUEST['identificacion'],"nombrepac"=>$_REQUEST['nombrepac'],"cama"=>$_REQUEST['cama'],"pieza"=>$_REQUEST['pieza']));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }

        list($dbconn) = GetDBconn();
        $query="SELECT c.numerodecuenta,c.plan_id FROM cuentas c WHERE c.ingreso='$Ingreso' AND (c.estado='1' OR c.estado='2')";
        $result = $dbconn->Execute($query);
        $numeroDeCuenta=$result->fields[0];
        $PlanId=$result->fields[1];
        if(empty($PlanId) || empty($numeroDeCuenta)){
            $mensaje = "VERIFIQUE QUE LA CUENTA DEL PACIENTE ESTEN ACTIVOS";
            $titulo = "DEVOLUCION DE MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDevolucionMedicamentos');
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }

        $ProductosDocumento=$this->ProductosDevolucion($_REQUEST['Documento']);
        if($ProductosDocumento){
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento']=='1'){
          $datos=$this->FechasLotesProductosDevol($ProductosDocumento[$i]['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                    $suma=$this->SumaFechasLotesProductosDevol($ProductosDocumento[$i]['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                    if(!$datos){
                      $this->frmError["MensajeError"]="Es obligatoria la fecha de vencimiento y el lote para el producto con codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
            $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],
                        '','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
                        return true;
                    }elseif($suma['suma']<$ProductosDocumento[$i]['cantidad']){
            $this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
            $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],
                        '','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
                        return true;
                    }
                }
            }
        }
        $query="SELECT a.departamento,b.empresa_id,b.centro_utilidad,b.servicio
        FROM estaciones_enfermeria a,departamentos b
        WHERE  a.estacion_id='".$_REQUEST['EstacionId']."' AND a.departamento=b.departamento";
    $result = $dbconn->Execute($query);
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
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='I'
        AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
        if(empty($concepto)){
            $mensaje = "NO EXISTE UN DOCUMENTO DE BODEGA CREADO PARA ESTE TIPO DE MOVIMIENTOS";
            $titulo = "DEVOLUCION DE MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDevolucionMedicamentos');
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','DIMD');
        for($i=0;$i<sizeof($checkboxDevol);$i++){
            $cadena=explode('.-.',$checkboxDevol[$i]);
            $CodigoPro=$cadena[0];
            $Cantidad=$cadena[1];
            $numeroComsecutivo=$cadena[2];
            $costoProducto=$this->HallarCostoProducto($_SESSION['BODEGAS']['Empresa'],$CodigoPro);
            $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
            $result=$dbconn->Execute($query);
            $Consecutivo=$result->fields[0];
            $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$CodigoPro,$Cantidad,$costoProducto);
            if($InsertarDocumentod==1){
              $this->InsertarBodegasDocumentosdCober($Consecutivo,date('Y-m-d H:i:s'),$numeroDeCuenta,$CodigoPro,$Cantidad,$varsPr[$j]['precio'],$codigoAgrupamiento,$PlanId,$vars['servicio'],$vars['empresa_id'],$vars['centro_utilidad'],$vars['departamento'],'1','DIMD');
                $query="SELECT existencia FROM existencias_bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND codigo_producto='$CodigoPro'";
                $result = $dbconn->Execute($query);
                $Existencias=$result->fields[0];
                $ModifExist=$this->ModificacionExistenciasResta($Existencias,$Cantidad,$_SESSION['BODEGAS']['Empresa'],$_SESSION['BODEGAS']['CentroUtili'],$_SESSION['BODEGAS']['BodegaId'],$CodigoPro);
                $query="SELECT cantidad_acum FROM hc_bodega_paciente WHERE ingreso='$Ingreso' AND medicamento_id='$CodigoPro'";
                $result = $dbconn->Execute($query);
                $cantidaArestar=$result->fields[0];
                if($cantidaArestar==$Cantidad){
                 $query="DELETE FROM hc_bodega_paciente WHERE ingreso='$Ingreso' AND medicamento_id='$CodigoPro'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }
                }else{
                    $cantidadTotalMed=$cantidaArestar-$Cantidad;
          $query="UPDATE hc_bodega_paciente SET cantidad_acum='$cantidadTotalMed' WHERE ingreso='$Ingreso' AND medicamento_id='$CodigoPro'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }
                }
                $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                                                                                            lote,
                                                                                                                                            saldo,
                                                                                                                                            cantidad,
                                                                                                                                            empresa_id,
                                                                                                                                            centro_utilidad,
                                                                                                                                            bodega,
                                                                                                                                            codigo_producto,
                                                                                                                                            consecutivo)SELECT
                                                                                                                                            fecha_vencimiento,
                                                                                                                                            lote,
                                                                                                                                            '0',
                                                                                                                                            cantidad,
                                                                                                                                            '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                            '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                                            '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                                            codigo_producto,
                                                                                                                                            '$Consecutivo' FROM inv_solicitudes_devolucion_fvencimiento_lotes WHERE consecutivo='$numeroComsecutivo' AND codigo_producto='$CodigoPro'";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
            }
        }
        $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
        $query="UPDATE inv_solicitudes_devolucion SET estado='1',bodegas_doc_id='$concepto',numeracion='$numeracion' WHERE documento='$Documento'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
            $this->GuardarNumeroDocumento($commit=true);
            $mensaje = "SE DEVOLVIERON A LA BODEGA LOS PRODUCTOS SELECCIONADOS";
            $titulo = "DESPACHO DE MEDICAMENTOS BODEGAS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDevolucionMedicamentos');
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
    }

    /**
* Funcion inserta el documento de soporte de una devolcion
* @return boolean
*/
    function RealizarDevolucionMedicamentosDpto(){

    IncludeLib("despacho_medicamentos");
        $checkboxDevol=$_REQUEST['checkboxDevol'];
        if(sizeof($checkboxDevol) == 0){
            $mensaje = "DEBE SELECCIONAR LOS MEDICAMENTOS A RECIBIR DE LA DEVOLUCION";
            $titulo = "DEVOLUCION DE MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDevolucionesSolicitudesDpto',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        foreach($checkboxDevol as $producto=>$datos){
          (list($cantidad,$cantidadLotes)=explode('||//',$datos));
      if($cantidadLotes){
        if($cantidadLotes<$cantidad){
          $this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$producto;
                    $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto']);
                    return true;
                }
            }
        }
    list($dbconn) = GetDBconn();
        $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='I'
        AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
        if(empty($concepto)){
            $mensaje = "NO EXISTE UN DOCUMENTO DE BODEGA CREADO PARA ESTE TIPO DE MOVIMIENTOS";
            $titulo = "DEVOLUCION DE MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDevolucionesSolicitudesDpto',array("departamento"=>$departamento,"descripcionDpto"=>$descripcionDpto));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','DIMD');
        foreach($checkboxDevol as $CodigoPro=>$datos){
            (list($Cantidad,$cantidadLotes)=explode('||//',$datos));
      $costoProducto=$this->HallarCostoProducto($_SESSION['BODEGAS']['Empresa'],$CodigoPro);
            $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
            $result=$dbconn->Execute($query);
            $Consecutivo=$result->fields[0];
            $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$CodigoPro,$Cantidad,$costoProducto);
      if($InsertarDocumentod==1){
        $query="SELECT existencia FROM existencias_bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND codigo_producto='$CodigoPro'";
                $result = $dbconn->Execute($query);
                $Existencias=$result->fields[0];
                $ModifExist=$this->ModificacionExistenciasResta($Existencias,$Cantidad,$_SESSION['BODEGAS']['Empresa'],$_SESSION['BODEGAS']['CentroUtili'],$_SESSION['BODEGAS']['BodegaId'],$CodigoPro);

        //Cuentas y Paciente Arreglar
                $query="SELECT DISTINCT a.documento,a.estacion_id,est.departamento,dpto.empresa_id,dpto.centro_utilidad,dpto.servicio,a.ingreso,e.numerodecuenta,e.plan_id
                FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,estaciones_enfermeria est,departamentos dpto,cuentas e
                WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.estado='0' AND b.estado='0' AND
                a.documento=b.documento AND b.codigo_producto='".$CodigoPro."' AND a.estacion_id=est.estacion_id AND est.departamento='".$_REQUEST['departamento']."' AND est.departamento=dpto.departamento AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')";

                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }else{
                    $datos=$result->RecordCount();
                    if($datos){
                        while(!$result->EOF) {
                            $Documentos[]=$result->GetRowAssoc($toUpper=false);
                            $result->MoveNext();
                        }
                    }
                    for($i=0;$i<sizeof($Documentos);$i++){
                        $query="SELECT a.consecutivo,a.cantidad
                        FROM inv_solicitudes_devolucion_d a WHERE a.documento='".$Documentos[$i]['documento']."' AND a.codigo_producto='".$CodigoPro."' AND a.estado='0'";

                        $result=$dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                          unset($DetalleDoc);
                            $datos=$result->RecordCount();
                            if($datos){
                                while(!$result->EOF){
                                    $DetalleDoc[]=$result->GetRowAssoc($toUpper=false);
                                    $result->MoveNext();
                                }
                            }
                        }
                        if($DetalleDoc){
                        for($j=0;$j<sizeof($DetalleDoc);$j++){
                            $CantidadProd=$DetalleDoc[$j]['cantidad'];
                            $this->InsertarBodegasDocumentosdCober($Consecutivo,date('Y-m-d H:i:s'),$Documentos[$i]['numerodecuenta'],$CodigoPro,$CantidadProd,$varsPr[$j]['precio'],$codigoAgrupamiento,$Documentos[$i]['plan_id'],$Documentos[$i]['servicio'],$Documentos[$i]['empresa_id'],$Documentos[$i]['centro_utilidad'],$Documentos[$i]['departamento'],'1','DIMD');
                            $query="SELECT cantidad_acum FROM hc_bodega_paciente WHERE ingreso='".$Documentos[$i]['ingreso']."' AND medicamento_id='$CodigoPro'";
                            $result = $dbconn->Execute($query);
                            $cantidaArestar=$result->fields[0];
                            if($cantidaArestar==$CantidadProd){
                            $query="DELETE FROM hc_bodega_paciente WHERE ingreso='".$Documentos[$i]['ingreso']."' AND medicamento_id='$CodigoPro'";
                                $result = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Base de Datos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $this->GuardarNumeroDocumento($commit=false);
                                    return false;
                                }
                            }else{
                                $cantidadTotalMed=$cantidaArestar-$CantidadProd;
                                $query="UPDATE hc_bodega_paciente SET cantidad_acum='$cantidadTotalMed' WHERE ingreso='".$Documentos[$i]['ingreso']."' AND medicamento_id='$CodigoPro'";
                                $result = $dbconn->Execute($query);
                                if($dbconn->ErrorNo() != 0) {
                                    $this->error = "Error al Guardar en la Base de Datos";
                                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                    $this->GuardarNumeroDocumento($commit=false);
                                    return false;
                                }
                            }
                            $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        saldo,
                                                                                                                                                        cantidad,
                                                                                                                                                        empresa_id,
                                                                                                                                                        centro_utilidad,
                                                                                                                                                        bodega,
                                                                                                                                                        codigo_producto,
                                                                                                                                                        consecutivo)SELECT
                                                                                                                                                        fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        '0',
                                                                                                                                                        cantidad,
                                                                                                                                                        '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                                                        '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                                                        codigo_producto,
                                                                                                                                                        '$Consecutivo' FROM inv_solicitudes_devolucion_fvencimiento_lotes WHERE consecutivo='".$DetalleDoc[$j]['consecutivo']."' AND codigo_producto='$CodigoPro'";

                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en la Base de Datos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                        $query="UPDATE inv_solicitudes_devolucion SET estado='1',bodegas_doc_id='$concepto',numeracion='$numeracion' WHERE documento='".$Documentos[$i]['documento']."'";
            $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }
                        }
                  }
                }
            }
        }
        $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
        $this->GuardarNumeroDocumento($commit=true);
        $mensaje = "SE DEVOLVIERON A LA BODEGA LOS PRODUCTOS SELECCIONADOS";
        $titulo = "DESPACHO DE MEDICAMENTOS BODEGAS";
        $accion = ModuloGetURL('app','InvBodegas','user','FormaDevolucionMedicamentos');
        $boton = "VOLVER";
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
    }

/**
* Funcion que consulta en la base de datos los medicametos o insumos que hacen parte de una solicitud de devolucion
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param array solicitudes de devolucion activas
*/

    function  ProductosDevolucion($Documento){

        list($dbconn) = GetDBconn();
        $query="SELECT b.codigo_producto,b.cantidad,d.descripcion,e.sw_control_fecha_vencimiento,b.consecutivo
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,inventarios c,inventarios_productos d,
        existencias_bodegas e
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.documento='$Documento' AND
        a.documento=b.documento AND  c.empresa_id=a.empresa_id AND c.codigo_producto=b.codigo_producto AND
        d.codigo_producto=b.codigo_producto AND a.empresa_id=e.empresa_id AND a.centro_utilidad=e.centro_utilidad AND a.bodega=e.bodega AND
        b.codigo_producto=e.codigo_producto AND b.estado='0'";
        /*$query="SELECT b.codigo_producto,b.cantidad,d.descripcion,e.sw_control_fecha_vencimiento,b.consecutivo,
    i.tipo_id_paciente||' '||i.paciente_id as identificacion,
        l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,inventarios c,inventarios_productos d,
        existencias_bodegas e,cuentas ee
    LEFT JOIN movimientos_habitacion f ON(ee.numerodecuenta=f.numerodecuenta AND f.fecha_egreso is NULL)
        LEFT JOIN camas j ON(f.cama=j.cama),ingresos i,pacientes l
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.documento='$Documento' AND
        a.documento=b.documento AND  c.empresa_id=a.empresa_id AND c.codigo_producto=b.codigo_producto AND
        d.codigo_producto=b.codigo_producto AND a.empresa_id=e.empresa_id AND a.centro_utilidad=e.centro_utilidad AND a.bodega=e.bodega AND
        b.codigo_producto=e.codigo_producto AND a.ingreso=ee.ingreso AND a.ingreso=i.ingreso AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id";
    */
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
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

    /**
* Funcion que consulta en la base de datos los medicametos o insumos que hacen parte de una solicitud de devolucion
* @return boolean
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param array solicitudes de devolucion activas
*/

    function  ProductosTotalesDevolucion($departamento){

        list($dbconn) = GetDBconn();
        $query="SELECT b.codigo_producto,sum(b.cantidad)as cantidad,
        (SELECT d.descripcion FROM inventarios_productos d WHERE d.codigo_producto=b.codigo_producto) as descripcion,
        (SELECT e.sw_control_fecha_vencimiento FROM existencias_bodegas e WHERE e.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND e.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND e.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND b.codigo_producto=e.codigo_producto) as sw_control_fecha_vencimiento
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,estaciones_enfermeria est,cuentas e
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.estado='0' AND
        a.documento=b.documento AND b.estado='0' AND a.estacion_id=est.estacion_id AND est.departamento='".$departamento."' AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
        GROUP BY b.codigo_producto";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
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

/**
* Funcion actualiza las existencias en bodega de un producto cuando son ingresos
* @return boolean
* @param integer cantidad de existencias del producto en la bodega
* @param integer cantidades del producto en la devolucion
* @param integer empresa a la que pertenece la bodega donde se va a crear el documento
* @param integer centro de utilidad al  que partenece la bodega donde se va a crear el documento
* @param integer codigo de la bodega donde se va a crear el documento
* @param integer codigo unico que identifica el producto
*/
    function ModificacionExistenciasResta($Existencias,$cantidadDevol,$Empresa,$CentroUtili,$BodegaId,$Codigo,$FechaVencimiento,$Lote){

        list($dbconn) = GetDBconn();
        $ExistenciasTotal= $Existencias + $cantidadDevol;
        $query="UPDATE existencias_bodegas SET existencia='$ExistenciasTotal' WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$Codigo';";
        
		$query .= " UPDATE  existencias_bodegas_lote_fv
    							SET     existencia_actual = existencia_actual + ".$cantidadDevol."
    							WHERE   empresa_id = '".$Empresa."' 
    							AND     centro_utilidad = '".$CentroUtili."' 
    							AND     bodega = '".$BodegaId."' 
    							AND     codigo_producto = '".$Codigo."'
    							AND     fecha_vencimiento = '".$FechaVencimiento."'
                  AND     lote = '".$Lote."'; ";
		
		$result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }
    return 1;
    }


    function LlamaMostrarLotesPtosDevols(){
    $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
        return true;
    }

    function InsertarFechaVencimientoLoteDevol(){
      if($_REQUEST['cancelar']){
            $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],'','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
            return true;
        }
        if(!$_REQUEST['fechaVencimiento'] || !$_REQUEST['lote'] || !$_REQUEST['cantidadLote']){
          $this->frmError["MensajeError"]="La fecha de Vencimiento, Cantidad y el Lote son Datos Obligatorios";
            $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
            return true;
        }
        $cadena=explode('/',$_REQUEST['fechaVencimiento']);
    $fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
        if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y'))){
      $this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
            $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
            return true;
        }
        $sumaTotal=$this->SumaFechasLotesProductosDevol($_REQUEST['consecutivo'],$_REQUEST['codigoProducto']);
    if($sumaTotal['suma']+$_REQUEST['cantidadLote']>$_REQUEST['Cantidad']){
          $this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total del producto con cogigo".' '.$_REQUEST['codigoProducto'];
            $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcion'],$_REQUEST['Cantidad'],$_REQUEST['consecutivo'],$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="INSERT INTO inv_solicitudes_devolucion_fvencimiento_lotes(consecutivo,
                              codigo_producto,
                                                    fecha_vencimiento,
                                                    lote,
                                                    cantidad)VALUES('".$_REQUEST['consecutivo']."',
                                                                                  '".$_REQUEST['codigoProducto']."',
                                                                                    '$fecha',
                                                                                    '".$_REQUEST['lote']."',
                                                                                    '".$_REQUEST['cantidadLote']."')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],'','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
        return true;
    }

    function InsertarFechaVencimientoLoteDocs(){

        if($_REQUEST['cancelar']){
            $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],$_REQUEST['TipoReposicion']);
            return true;
        }
        if(!$_REQUEST['fechaVencimiento'] || !$_REQUEST['lote'] || !$_REQUEST['cantidadLote']){
          $this->frmError["MensajeError"]="La fecha de Vencimiento, Cantidad y el Lote son Datos Obligatorios";
            $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],$_REQUEST['TipoReposicion'],
            $_REQUEST['codigoProductoProd'],$_REQUEST['descripcionProd'],$_REQUEST['CantidadProd'],$_REQUEST['consecutivoProd']);
            return true;
        }
        $cadena=explode('/',$_REQUEST['fechaVencimiento']);
    $fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
        if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y'))){
      $this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
            $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],$_REQUEST['TipoReposicion'],
            $_REQUEST['codigoProductoProd'],$_REQUEST['descripcionProd'],$_REQUEST['CantidadProd'],$_REQUEST['consecutivoProd']);
            return true;
        }
        $sumaTotal=$this->SumaFechasLotesProductos($_REQUEST['consecutivo'],$_REQUEST['codigoProductoProd']);
    if($sumaTotal['suma']+$_REQUEST['cantidadLote']>$_REQUEST['CantidadProd']){
          $this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total del producto con cogigo".' '.$_REQUEST['codigoProductoProd'];
            $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],$_REQUEST['TipoReposicion'],
            $_REQUEST['codigoProductoProd'],$_REQUEST['descripcionProd'],$_REQUEST['CantidadProd'],$_REQUEST['consecutivoProd']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="INSERT INTO inv_bodegas_transferencia_fvencimiento_lotes(
                              inv_documento_transferencia_id,
                                                    codigo_producto,
                                                    fecha_vencimiento,
                                                    lote,
                                                    cantidad)VALUES('".$_REQUEST['consecutivo']."',
                                                                                  '".$_REQUEST['codigoProductoProd']."',
                                                                                    '$fecha',
                                                                                    '".$_REQUEST['lote']."',
                                                                                    '".$_REQUEST['cantidadLote']."')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],$_REQUEST['TipoReposicion'],
        $_REQUEST['codigoProductoProd'],$_REQUEST['descripcionProd'],$_REQUEST['CantidadProd'],$_REQUEST['consecutivoProd']);
        return true;

    }

    function InsertarFechaVencimientoLoteDevolDpto(){

        if($_REQUEST['cancelar']){
            $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto']);
            return true;
        }
        if(!$_REQUEST['fechaVencimiento'] || !$_REQUEST['lote'] || !$_REQUEST['cantidadLote']){
          $this->frmError["MensajeError"]="La fecha de Vencimiento, Cantidad y el Lote son Datos Obligatorios";
            $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcionProd'],$_REQUEST['cantidad']);
            return true;
        }
        $cadena=explode('/',$_REQUEST['fechaVencimiento']);
    $fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
        if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y'))){
      $this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
            $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcionProd'],$_REQUEST['cantidad']);
            return true;
        }
        $sumaTotal=$this->SumaFechasLotesProductosDevolDpto($_REQUEST['departamento'],$_REQUEST['codigoProducto']);
    if($sumaTotal['suma']+$_REQUEST['cantidadLote']>$_REQUEST['cantidad']){
          $this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total del producto con cogigo".' '.$_REQUEST['codigoProducto'];
            $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto'],1,$_REQUEST['codigoProducto'],$_REQUEST['descripcionProd'],$_REQUEST['cantidad']);
            return true;
        }

        list($dbconn) = GetDBconn();
        //Consulta que trae el consecutivo que no ha alcanzado la cantidad para insertarlo en inv_solicitudes_devolucion_fvencimiento_lotes
        $query="SELECT result.consecutivo,(CASE WHEN result.cantidad_insertada IS NULL THEN result.cantidad ELSE result.cantidad-result.cantidad_insertada END ) as cantidad
        FROM (SELECT b.consecutivo,b.cantidad,
        (SELECT sum(x.cantidad) FROM inv_solicitudes_devolucion_fvencimiento_lotes x WHERE x.consecutivo=b.consecutivo AND x.codigo_producto='".$_REQUEST['codigoProducto']."') as cantidad_insertada
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,estaciones_enfermeria est,cuentas e
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.estado='0' AND
        a.documento=b.documento AND b.estado='0' AND b.codigo_producto='".$_REQUEST['codigoProducto']."' AND a.estacion_id=est.estacion_id AND est.departamento='".$_REQUEST['departamento']."' AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')) as result
        WHERE (result.cantidad_insertada < result.cantidad OR result.cantidad_insertada IS NULL) ORDER BY result.consecutivo";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()>0){
        while(!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        //Fin Consulta
        $i=0;
        $cantidadLote=$_REQUEST['cantidadLote'];
        while($i<sizeof($vars) && $cantidadLote>0){
      if($vars[$i]['cantidad']<=$cantidadLote){
        $cantidadInsertar=$vars[$i]['cantidad'];
        $cantidadLote-=$vars[$i]['cantidad'];
            }else{
        $cantidadInsertar=$cantidadLote;
                $cantidadLote=0;
            }
          $queryy.="INSERT INTO inv_solicitudes_devolucion_fvencimiento_lotes(consecutivo,
                              codigo_producto,
                                                    fecha_vencimiento,
                                                    lote,
                                                    cantidad)VALUES('".$vars[$i]['consecutivo']."',
                                                                                  '".$_REQUEST['codigoProducto']."',
                                                                                    '$fecha',
                                                                                    '".$_REQUEST['lote']."',
                                                                                    '".$cantidadInsertar."');";
            $i++;
        }
        $result = $dbconn->Execute($queryy);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
    $this->frmError["MensajeError"]="Datos Guardados";
        $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto']);
        return true;
    }

    function SumaFechasLotesProductosDevol($consecutivo,$codigoProducto){
    list($dbconn) = GetDBconn();
        $query="SELECT sum(cantidad) as suma FROM inv_solicitudes_devolucion_fvencimiento_lotes WHERE consecutivo='$consecutivo' AND codigo_producto='$codigoProducto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

    function SumaFechasLotesProductosDevolDpto($departamento,$codigoProducto){
    list($dbconn) = GetDBconn();

        $query="SELECT sum(a.cantidad) as suma
        FROM inv_solicitudes_devolucion_fvencimiento_lotes a,
        (SELECT b.consecutivo
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,estaciones_enfermeria est,cuentas e
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.estado='0' AND
        a.documento=b.documento AND b.estado='0' AND b.codigo_producto='".$codigoProducto."' AND a.estacion_id=est.estacion_id AND est.departamento='".$departamento."' AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')) as consecutivos
        WHERE a.consecutivo=consecutivos.consecutivo AND a.codigo_producto='$codigoProducto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

    function FechasLotesProductosDevol($consecutivo,$codigoProducto){
    list($dbconn) = GetDBconn();
        $query="SELECT fecha_vencimiento,lote,cantidad FROM inv_solicitudes_devolucion_fvencimiento_lotes WHERE consecutivo='$consecutivo' AND codigo_producto='$codigoProducto'";
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

    function FechasLotesProductosDevolDpto($departamento,$codigoProducto){

    list($dbconn) = GetDBconn();
        $query="SELECT a.fecha_vencimiento,a.lote,a.cantidad,a.consecutivo
        FROM inv_solicitudes_devolucion_fvencimiento_lotes a,
        (SELECT b.consecutivo
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,estaciones_enfermeria est,cuentas e
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.estado='0' AND
        a.documento=b.documento AND b.estado='0' AND b.codigo_producto='".$codigoProducto."' AND a.estacion_id=est.estacion_id AND est.departamento='".$departamento."' AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')) as consecutivos
        WHERE a.consecutivo=consecutivos.consecutivo AND a.codigo_producto='".$codigoProducto."'";
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

    function LlamaEliminarFechaVDevol(){
    list($dbconn) = GetDBconn();
        $query="DELETE FROM inv_solicitudes_devolucion_fvencimiento_lotes WHERE consecutivo='".$_REQUEST['consecutivo']."' AND codigo_producto='".$_REQUEST['codigoProducto']."' AND cantidad='".$_REQUEST['Cantidad']."' AND fecha_vencimiento='".$_REQUEST['FechaVencimiento']."' AND lote='".$_REQUEST['Lote']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if($_REQUEST['destino']==1){
      $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto']);
            return true;
        }
        $this->FormaDetalleDevolucionMedicamentos($_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['Documento'],$_REQUEST['Ingreso'],$_REQUEST['observaciones'],'','','','','',$_REQUEST['identificacion'],$_REQUEST['nombrepac'],$_REQUEST['cama'],$_REQUEST['pieza'],$_REQUEST['parametro']);
        return true;
    }

    function LlamaEliminarFechaV(){
    list($dbconn) = GetDBconn();
        $query="DELETE FROM inv_bodegas_transferencia_fvencimiento_lotes WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."' AND codigo_producto='".$_REQUEST['codigoProducto']."' AND cantidad='".$_REQUEST['Cantidad']."' AND fecha_vencimiento='".$_REQUEST['FechaVencimiento']."' AND lote='".$_REQUEST['Lote']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->DetalleTransferenciaBodega($_REQUEST['consecutivo'],$_REQUEST['bodegaOrigen'],$_REQUEST['centroUtilidadOrigen'],$_REQUEST['FechaTransferencia']);
        return true;
    }

    function LlamaEliminarFechaVDocs(){
    list($dbconn) = GetDBconn();
        $query="DELETE FROM inv_bodegas_transferencia_fvencimiento_lotes WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."' AND codigo_producto='".$_REQUEST['codigoProductoProd']."' AND cantidad='".$_REQUEST['Cantidad']."' AND fecha_vencimiento='".$_REQUEST['FechaVencimiento']."' AND lote='".$_REQUEST['Lote']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],$_REQUEST['TipoReposicion'],
        $_REQUEST['codigoProductoProd'],$_REQUEST['descripcionProd'],$_REQUEST['CantidadProd'],$_REQUEST['consecutivoProd']);
        return true;
    }

    function LlamaListadoSolicidudesnoConfirmar(){
    $this->ListadoSolicidudesnoConfirmar();
        return true;
    }

    function SolicitudesSinConfirmar(){
    list($dbconn) = GetDBconn();
        $query="(SELECT a.solicitud_id as codigo,
                a.ingreso as ingreso,
                        a.fecha_solicitud as fecha,
                        a.estacion_id as estacion,
                        d.primer_nombre,
                        d.segundo_nombre,
                        d.primer_apellido,
                        d.segundo_apellido,
                        a.tipo_solicitud as tipo,
                        'S' as  tipoSolicitud
                FROM hc_solicitudes_medicamentos a,ingresos c,pacientes d
                        WHERE a.sw_estado='2' AND a.ingreso=c.ingreso AND c.paciente_id=d.paciente_id AND c.tipo_id_paciente=d.tipo_id_paciente)
                        UNION
                        (SELECT b.documento as codigo,
                b.ingreso as ingreso,
                        b.fecha as fecha,
                        b.estacion_id as estacion,
                        f.primer_nombre,
                        f.segundo_nombre,
                        f.primer_apellido,
                        f.segundo_apellido,
                        NULL as tipo,
                        'D' as  tipoSolicitud
                FROM inv_solicitudes_devolucion b,ingresos e,pacientes f
                        WHERE b.estado='2' AND b.ingreso=e.ingreso AND e.paciente_id=f.paciente_id AND e.tipo_id_paciente=f.tipo_id_paciente
                        )";

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

    function DetalleSolicitudNoConfirmar(){
      $this->FormaDetalleSolicitudNoConfirmar($_REQUEST['tipoSolicitud'],$_REQUEST['SolicitudId'],$_REQUEST['tipoSol'],$_REQUEST['ingreso'],$_REQUEST['primerNombre'],$_REQUEST['segundoNombre'],$_REQUEST['primerApellido'],$_REQUEST['segundoApellido'],$_REQUEST['Fecha']);
        return true;
    }

    function DetalleMtosSolicitud($tipoSolicitud,$SolicitudId,$tipoSol){
    list($dbconn) = GetDBconn();
        if($tipoSolicitud=='S'){
          if($tipoSol=='M'){
          $query="SELECT a.medicamento_id as mto,a.cant_solicitada as cant,b.descripcion,NULL as mezcla FROM hc_solicitudes_medicamentos_d a,inventarios_productos b WHERE a.solicitud_id='$SolicitudId' AND a.medicamento_id=b.codigo_producto";
            }elseif($tipo=='Z'){
      $query="SELECT a.medicamento_id as mto,a.cant_solicitada as cant,b.descripcion,mezcla_recetada_id as mezcla FROM hc_solicitudes_medicamentos_mezclas_d a,inventarios_productos b WHERE a.solicitud_id='$SolicitudId' AND a.medicamento_id=b.codigo_producto";
            }else{
      $query="SELECT a.medicamento_id as mto,a.cant_solicitada as cant,b.descripcion,NULL as mezcla FROM hc_solicitudes_insumos_d a,inventarios_productos b WHERE a.solicitud_id='$SolicitudId' AND a.codigo_producto=b.codigo_producto";
            }
        }elseif($tipoSolicitud=='D'){
      $query="SELECT a.codigo_producto as mto,a.cantidad as cant,b.descripcion,NULL as mezcla FROM inv_solicitudes_devolucion_d a,inventarios_productos b WHERE a.documento='$SolicitudId' AND a.codigo_producto=b.codigo_producto AND a.estado='0'";
        }
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


  function LlamaRecibirOrdenesCompra(){
      list($dbconn) = GetDBconn();
    $query="SELECT * FROM bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND autorizacion_recibir_compras='1'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
        $this->RecibirOrdenesCompra();
            }else{
        $mensaje="Esta Bodega no esta Autorizada para recibir compras de proveedores";
                $titulo="EXISTENCIAS BODEGA";
                $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios5');
                $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                return true;
            }
        }
        return true;
 }

    function BuscarProveedoresProductosCompra()//Busca los terceros que sean proveedores de la empresa
    {
        list($dbconn) = GetDBconn();
        if($_REQUEST['codigocomp'])
        {
            $codigo=$_REQUEST['codigocomp'];
            $busqueda1="AND A.tercero_id LIKE '%$codigo%'";
        }
        else
        {
            $busqueda1='';
        }
        if($_REQUEST['descricomp'])
        {
            $codigo=STRTOUPPER($_REQUEST['descricomp']);
            $busqueda2="AND UPPER(B.nombre_tercero) LIKE '%$codigo%'";
        }
        else
        {
            $busqueda2='';
        }
        if(empty($_REQUEST['conteo']))
        {
            $query = "SELECT count(*) FROM
                    (
                        SELECT DISTINCT A.codigo_proveedor_id,
                        A.tipo_id_tercero,
                        A.tercero_id,
                        B.nombre_tercero,
                        (
                        SELECT COUNT(D.orden_pedido_id)
                        FROM compras_ordenes_pedidos AS D
                        WHERE A.codigo_proveedor_id=D.codigo_proveedor_id
                        AND D.estado='3'
                        ) AS numerorden
                        FROM terceros_proveedores AS A,
                        terceros AS B,
                        compras_ordenes_pedidos AS C
                        WHERE (A.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                        OR A.empresa_id_centro='".$_SESSION['BODEGAS']['Empresa']."')
                        AND A.tipo_id_tercero=B.tipo_id_tercero
                        AND A.tercero_id=B.tercero_id
                        AND A.codigo_proveedor_id=C.codigo_proveedor_id
                        AND C.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                        AND C.estado='3'
                        $busqueda1
                        $busqueda2
                    ) AS r;";
            $resulta = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0)
            {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            list($this->conteo)=$resulta->fetchRow();
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
            if($_REQUEST['Of'] > $this->conteo)
            {
                $Of='0';
                $_REQUEST['Of']='0';
                $_REQUEST['paso']='1';
            }
        }
        $query = "
                (
                SELECT DISTINCT A.codigo_proveedor_id,
                A.tipo_id_tercero,
                A.tercero_id,
                B.nombre_tercero,
                (
                SELECT COUNT(D.orden_pedido_id)
                FROM compras_ordenes_pedidos AS D
                WHERE A.codigo_proveedor_id=D.codigo_proveedor_id
                AND D.estado='3'
                ) AS numerorden
                FROM terceros_proveedores AS A,
                terceros AS B,
                compras_ordenes_pedidos AS C
                WHERE (A.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                OR A.empresa_id_centro='".$_SESSION['BODEGAS']['Empresa']."')
                AND A.tipo_id_tercero=B.tipo_id_tercero
                AND A.tercero_id=B.tercero_id
                AND A.codigo_proveedor_id=C.codigo_proveedor_id
                AND C.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                AND C.estado='3'
                $busqueda1
                $busqueda2
                ORDER BY B.nombre_tercero
                )
                LIMIT ".$this->limit." OFFSET $Of;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function HallarDetalleOrdenProveedor($provelegip){
            list($dbconn) = GetDBconn();
        $query = "
                SELECT A.orden_pedido_id,
                A.fecha_orden,
                A.fecha_envio,
                B.codigo_producto,
                B.numero_unidades,
                B.valor,
                B.porc_iva,
                B.numero_unidades_recibidas,
                C.descripcion


                FROM compras_ordenes_pedidos AS A,
                compras_ordenes_pedidos_detalle AS B,
                inventarios_productos AS C

                WHERE A.codigo_proveedor_id=".$provelegip."
                AND A.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                AND A.estado='3'
                AND A.orden_pedido_id=B.orden_pedido_id
                AND B.estado='1'
                AND B.codigo_producto=C.codigo_producto

                ORDER BY B.codigo_producto, A.fecha_orden DESC, A.orden_pedido_id;";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0)
        {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $i=0;
        while(!$resulta->EOF)
        {
            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
            $resulta->MoveNext();
            $i++;
        }
        return $var;
    }

    function RealizarDocumentoCompra(){
        if($_REQUEST['Salir']){
      $this->RecibirOrdenesCompra();
            return true;
        }
    $vectorTotales=$_REQUEST['totalesRecibida'];
        foreach($_REQUEST['cantidaRecibida'] as $codigoProducto=>$cantidadrec){
          $totalesCompar=$_REQUEST['totalCompar'];
          if($cantidadrec>$totalesCompar[$codigoProducto]){
        $this->frmError["MensajeError"]="Inposible Insertar esta Cantidad en El Producto con Codigo".'  '.$codigoProducto.'  '.",es mayor que las Cantidades Pedidas";
                $this->DetalleRecepcionCompra();
                return true;
            }
      $total=$vectorTotales[$codigoProducto];
            if($cantidadrec && $cantidadrec > 0){
        if(!$total || $total<0){
                  $this->frmError["MensajeError"]="El Producto con Codigo".'  '.$codigoProducto.'  '."tiene Cantidades Menor a Cero y no puede tener al valor nulo";
          $this->DetalleRecepcionCompra();
                    return true;
                }
            }
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_compras='1' AND sw_estado='1' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
        $result=$dbconn->Execute($query);
        $concepto=$result->fields[0];
        $query="SELECT nextval('tmp_bodegas_documentos_documento_seq')";
        $result=$dbconn->Execute($query);
        $Documento=$result->fields[0];
        $query="INSERT INTO tmp_bodegas_documentos(documento,
                                                                                        fecha,
                                                                                        total_costo,
                                                                                        transaccion,
                                                                                        observacion,
                                                                                        usuario_id,
                                                                                        fecha_registro,
                                                                                        bodegas_doc_id)
                                                                                        VALUES('$Documento','".date("Y-m-d")."',
                                                                                        '0',NULL,'',
                                                                                        '".UserGetUID()."',
                                                                                        '".date("Y-m-d H:i:s")."',
                                                                                        '$concepto')";
        $dbconn->Execute($query);
        if($dbconn->ErrorNo() !=0 ){
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $dbconn->RollbackTrans();
            return false;
        }
        foreach($_REQUEST['cantidaRecibida'] as $codigoProducto=>$cantidadrec){
      $total=$vectorTotales[$codigoProducto];
            if($cantidadrec>0){
            $query="INSERT INTO tmp_bodegas_documentos_d(documento,
                                                                                                codigo_producto,
                                                                                                cantidad,
                                                                                                total_costo,
                                                                                                bodegas_doc_id,
                                                iva_compra)VALUES('$Documento','$codigoProducto','$cantidadrec','$total',
                                                                                                '$concepto','0.0')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
            }
    }
        /*FUNCIÓN DE LOTES*/
        $dbconn->CommitTrans();
        $_REQUEST['proveedor']=$_REQUEST['provelegip'];
        $_REQUEST['tipoprov']=$_REQUEST['tipoProv'];
        $_REQUEST['nomprov']=$_REQUEST['nombreProv'];
        $this->FormaPedirFechaVenceCompra($_REQUEST['provelegip'],$_REQUEST['tipoProv'],$_REQUEST['nombreProv'],$Documento,$concepto);
        return true;
    }


    function DetalleDocumentoComprasFechaVence($documento,$concepto){
    list($dbconn) = GetDBconn();
        $query = "SELECT a.codigo_producto,a.cantidad,b.descripcion,a.consecutivo
        FROM tmp_bodegas_documentos_d a,inventarios_productos b,existencias_bodegas c,bodegas_doc_numeraciones x
        WHERE a.documento='$documento' AND a.bodegas_doc_id='$concepto' AND a.bodegas_doc_id=x.bodegas_doc_id
        AND x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
        AND x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."'
        AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
        AND a.codigo_producto=b.codigo_producto AND x.empresa_id=c.empresa_id AND
        x.centro_utilidad=c.centro_utilidad AND x.bodega=c.bodega AND
        a.codigo_producto=c.codigo_producto AND c.sw_control_fecha_vencimiento='1'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($ToUpper = false);
                $result->MoveNext();
            }
            return $vars;
        }
    }

    function LlamaInsertarFechaVenciLotePtoCompras(){
    $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto'],1,$_REQUEST['consecutivo'],$_REQUEST['codigoProducto'],$_REQUEST['cantidadTotal'],$_REQUEST['descripcion']);
        return true;
    }

    function InsertarFechaVencimientoLoteCompras(){
        if($_REQUEST['cancelar']){
            $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto']);
            return true;
        }
        if(!$_REQUEST['fechaVencimiento'] || !$_REQUEST['lote'] || !$_REQUEST['cantidadLote']){
            $this->frmError["MensajeError"]="La fecha de Vencimiento, Cantidad y el Lote son Datos Obligatorios";
            $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto'],1,$_REQUEST['consecutivo'],$_REQUEST['codigoProducto'],$_REQUEST['cantidadTotal'],$_REQUEST['descripcion']);
            return true;
        }
        $cadena=explode('/',$_REQUEST['fechaVencimiento']);
        $fecha=$cadena[2].'-'.$cadena[1].'-'.$cadena[0];
        if(mktime(0,0,0,$cadena[1],$cadena[0],$cadena[2])<mktime(0,0,0,date('m'),date('d'),date('Y'))){
            $this->frmError["MensajeError"]="La fecha de Vencimiento no puede ser menor a la Actual";
            $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto'],1,$_REQUEST['consecutivo'],$_REQUEST['codigoProducto'],$_REQUEST['cantidadTotal'],$_REQUEST['descripcion']);
            return true;
        }
        $sumaTotal=$this->SumaFechasLotesProductosCompras($_REQUEST['consecutivo'],$_REQUEST['codigoProducto']);
        if($sumaTotal['suma']+$_REQUEST['cantidadLote']>$_REQUEST['cantidadTotal']){
            $this->frmError["MensajeError"]="La suma de las Cantidades supera la Cantidad Total";
            $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto'],1,$_REQUEST['consecutivo'],$_REQUEST['codigoProducto'],$_REQUEST['cantidadTotal'],$_REQUEST['descripcion']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query="INSERT INTO tmp_bodegas_documentos_d_fvencimiento_lotes(codigo_producto,
                                                                                                                                        fecha_vencimiento,
                                                                                                                                        lote,
                                                                                                                                        cantidad,
                                                                                                                                        empresa_id,
                                                                                                                                        centro_utilidad,
                                                                                                                                        bodega,
                                                                                                                                        consecutivo,
                                                                                                                                        saldo)VALUES(
                                                                                                                                        '".$_REQUEST['codigoProducto']."',
                                                                                                                                        '$fecha',
                                                                                                                                        '".$_REQUEST['lote']."',
                                                                                                                                        '".$_REQUEST['cantidadLote']."',
                                                                                                                                        '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                                                                        '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                                                                                        '".$_REQUEST['consecutivo']."',
                                                                                                                                        '0')";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto']);
        return true;
    }

    function SumaFechasLotesProductosCompras($consecutivo,$codigoProducto){
    list($dbconn) = GetDBconn();
        $query="SELECT sum(cantidad) as suma FROM tmp_bodegas_documentos_d_fvencimiento_lotes WHERE consecutivo='$consecutivo' AND codigo_producto='$codigoProducto'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                $vars=$result->GetRowAssoc($toUpper=false);
            }
        }
        $result->Close();
        return $vars;
    }

    function FechasLotesProductosCompras($consecutivo,$codigoProducto){
    list($dbconn) = GetDBconn();
        $query="SELECT fecha_vencimiento,lote,cantidad FROM tmp_bodegas_documentos_d_fvencimiento_lotes WHERE consecutivo='$consecutivo' AND codigo_producto='$codigoProducto'";
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

    function LlamaEliminarFechaVCompras(){
    list($dbconn) = GetDBconn();
        $query="DELETE FROM tmp_bodegas_documentos_d_fvencimiento_lotes   WHERE consecutivo='".$_REQUEST['consecutivo']."' AND codigo_producto='".$_REQUEST['codigoProducto']."' AND cantidad='".$_REQUEST['cantidad']."' AND fecha_vencimiento='".$_REQUEST['fechaVencimiento']."' AND lote='".$_REQUEST['lote']."'
        AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto']);
        return true;
    }

    function GuardarDocumentoCompra(){
    if($_REQUEST['Salir']){
      $this->EliminaciondeTablas($_REQUEST['documento'],$_REQUEST['concepto']);
            $this->RecibirOrdenesCompra();
            return true;
        }
        $ProductosDocumento=$this->DetalleDocumentoComprasFechaVence($_REQUEST['documento'],$_REQUEST['concepto']);
        if($ProductosDocumento){
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
                $datos=$this->FechasLotesProductosCompras($ProductosDocumento[$i]['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                $suma=$this->SumaFechasLotesProductosCompras($ProductosDocumento[$i]['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                if(!$datos){
                    $this->frmError["MensajeError"]="Es obligatoria la fecha de vencimiento y el lote para el producto con codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
                    $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto']);
                    return true;
                }elseif($suma['suma']<$ProductosDocumento[$i]['cantidad']){
                    $this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
                    $this->FormaPedirFechaVenceCompra($_REQUEST['proveedor'],$_REQUEST['tipoprov'],$_REQUEST['nomprov'],$_REQUEST['documento'],$_REQUEST['concepto']);
                    return true;
                }
            }
        }
        $this->ActualizarCostoCompra($_REQUEST['documento'],$_REQUEST['concepto']);
        $this->TotalizarDocBodega($_REQUEST['documento'],$_REQUEST['concepto']);
        $vector=$this->GuardarDocumentoBD($_REQUEST['documento'],$_REQUEST['concepto'],'I','',1);
    $this->ModificacionCompras($vector[0],$vector[1],$_REQUEST['proveedor']);
        return true;
    }


    function ModificacionCompras($numeracion,$concepto,$proveedor){
    list($dbconn) = GetDBconn();
        $query="SELECT codigo_producto, cantidad, total_costo
        FROM bodegas_documentos_d
        WHERE numeracion=".$numeracion." AND bodegas_doc_id='".$concepto."'
        ORDER BY codigo_producto";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF) {
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
            for($k=0;$k<sizeof($vars);$k++){
                $query="SELECT A.orden_pedido_id, A.numero_unidades, A.estado, A.numero_unidades_recibidas
                FROM compras_ordenes_pedidos_detalle AS A, compras_ordenes_pedidos AS B
                WHERE B.codigo_proveedor_id=".$proveedor." AND B.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
                AND B.orden_pedido_id=A.orden_pedido_id AND A.codigo_producto='".$vars[$k]['codigo_producto']."'
                AND B.estado='3' AND A.estado='1' ORDER BY A.orden_pedido_id";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }else{
                    $productos='';
                    while(!$result->EOF) {
                        $productos[]=$result->GetRowAssoc($toUpper=false);
                        $result->MoveNext();
                    }
                    $cantreci=$vars[$k]['cantidad'];
                    for($i=0;$i<sizeof($productos);){
                        if($productos[$i]['numero_unidades_recibidas']<>NULL)
                        {
                            $cantpedi=$productos[$i]['numero_unidades']-$productos[$i]['numero_unidades_recibidas'];
                        }
                        else
                        {
                            $cantpedi=$productos[$i]['numero_unidades'];
                        }
                        $cantreci=$cantreci-$cantpedi;
                        if($cantreci > 0){
                        $guardar=$productos[$i]['numero_unidades_recibidas']+$cantpedi;
                            $query="UPDATE compras_ordenes_pedidos_detalle SET
                            numero_unidades_recibidas=".$guardar.",
                            estado='0'
                            WHERE codigo_producto='".$vars[$k]['codigo_producto']."'
                            AND orden_pedido_id=".$productos[$i]['orden_pedido_id']."";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            $i++;
                        }elseif($cantreci == 0){
                          $guardar=$productos[$i]['numero_unidades_recibidas']+$cantpedi;
                            $query="UPDATE compras_ordenes_pedidos_detalle SET
                            numero_unidades_recibidas=".$guardar.",
                            estado='0'
                            WHERE codigo_producto='".$vars[$k]['codigo_producto']."'
                            AND orden_pedido_id=".$productos[$i]['orden_pedido_id']."";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            $i=sizeof($productos);
                        }elseif($cantreci < 0){
                            $cantpedi=$cantpedi+$cantreci;
                            $guardar=$productos[$i]['numero_unidades_recibidas']+$cantpedi;
                            $query="UPDATE compras_ordenes_pedidos_detalle SET
                            numero_unidades_recibidas=".$guardar."
                            WHERE codigo_producto='".$vars[$k]['codigo_producto']."'
                            AND orden_pedido_id=".$productos[$i]['orden_pedido_id']."";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }
                            $i=sizeof($productos);
                        }
                    }
                }
            }
        }
        $query="SELECT DISTINCT A.orden_pedido_id,
        (
        SELECT COUNT(C.orden_pedido_id)
        FROM compras_ordenes_pedidos_detalle AS C
        WHERE B.orden_pedido_id=C.orden_pedido_id
        AND C.estado='1'
        ) AS cambiar
        FROM compras_ordenes_pedidos_detalle AS A, compras_ordenes_pedidos AS B
        WHERE B.codigo_proveedor_id=".$proveedor."
        AND B.empresa_id='".$_SESSION['BODEGAS']['Empresa']."'
        AND B.orden_pedido_id=A.orden_pedido_id
        AND B.estado='3'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF) {
                $cambiar[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
            for($i=0;$i<sizeof($cambiar);$i++){
                if($cambiar[$i]['cambiar']==0){
                    $query="UPDATE compras_ordenes_pedidos SET
                    estado='0',
                    fecha_recibido='".date("Y-m-d")."'
                    WHERE orden_pedido_id=".$cambiar[$i]['orden_pedido_id']."
                    AND codigo_proveedor_id=".$proveedor."
                    AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                }
            }
        }
        $this->RecibirOrdenesCompra();
        return true;
    }

    function ActualizarCostoCompra($documento,$concepto){
    list($dbconn) = GetDBconn();
        $query="SELECT x.codigo_producto,x.cantidad,x.total_costo,y.existencia,y.costo,y.costo_ultima_compra
        FROM tmp_bodegas_documentos_d x,inventarios y,bodegas_doc_numeraciones a
        WHERE x.documento='$documento' AND x.bodegas_doc_id='$concepto' AND x.bodegas_doc_id=a.bodegas_doc_id
        AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
        AND a.empresa_id=y.empresa_id AND y.codigo_producto=x.codigo_producto";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          while(!$result->EOF) {
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
            for($i=0;$i<sizeof($vars);$i++){
        $costo=(($vars[$i]['existencia'] * $vars[$i]['costo']) + ( $vars[$i]['total_costo'] * $vars[$i]['cantidad']))/($vars[$i]['existencia']+$vars[$i]['cantidad']);
                $costoultimaCompra=($vars[$i]['total_costo']);
                $query="UPDATE inventarios SET costo='$costo',costo_anterior='".$vars[$i]['costo']."',costo_ultima_compra='$costoultimaCompra',costo_penultima_compra='".$vars[$i]['costo_ultima_compra']."' WHERE codigo_producto='".$vars[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
        }
        return true;
    }

    function LlamaReporteProductosCercaFVmto(){
    $this->ReporteProductosCercaFVmto();
        return true;
    }

    function ProductosCercaVencer(){
    list($dbconn) = GetDBconn();
        $query="SELECT a.codigo_producto,d.descripcion,b.fecha_vencimiento,c.dias_previos_vencimiento,b.cantidad,b.lote FROM existencias_bodegas a,bodegas_documentos_d_fvencimiento_lotes b,medicamentos c,inventarios_productos d WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
                AND a.sw_control_fecha_vencimiento='1' AND a.codigo_producto=b.codigo_producto AND a.empresa_id=b.empresa_id AND a.centro_utilidad=b.centro_utilidad AND a.bodega=b.bodega AND a.codigo_producto=c.codigo_medicamento AND
            a.codigo_producto=d.codigo_producto AND
                        (current_date >= (date(b.fecha_vencimiento) - c.dias_previos_vencimiento))";
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

    function ImprimirSolicitudMed(){

    $cadenausuario=$_REQUEST['usuarioId'].' - '.$_REQUEST['usuarioestacion'];
    $cadenausuario=substr($cadenausuario,0,31);
        $cadenaestacion=$_REQUEST['EstacionId'].' - '.$_REQUEST['NombreEstacion'];
        $cadenaestacion=substr($cadenaestacion,0,31);
        $cadptoestacion=$_REQUEST['deptoestacion'];
    $cadptoestacion=str_pad($cadptoestacion,0,31);
        $cadnombrepac=$_REQUEST['nombrepac'];
    $cadnombrepac=str_pad($cadnombrepac,0,31);

        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportSolicitud',
        $datos=array("razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],
        "Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],"cadenaestacion"=>$cadenaestacion,"cadptoestacion"=>$cadptoestacion,
        "SolicitudId"=>$_REQUEST['SolicitudId'],"Fecha"=>$_REQUEST['Fecha'],"cadenausuario"=>$cadenausuario,"medicamentos"=>$_REQUEST['medicamentos'],
        "rango"=>$_REQUEST['rango'],"tipoafil"=>$_REQUEST['tipoafil'],"cama"=>$_REQUEST['cama'],"pieza"=>$_REQUEST['pieza'],"plan"=>$_REQUEST['plan'],"tipoidPac"=>$_REQUEST['tipoidPac'],"paciente"=>$_REQUEST['paciente'],"cadnombrepac"=>$cadnombrepac),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
    $this->FormaListadoSolicitudes();
        return true;
  }

    function ImprimirDevolucionIndividual(){

    $cadenausuario=$_REQUEST['usuarioId'].' - '.$_REQUEST['usuarioestacion'];
    $cadenausuario=substr($cadenausuario,0,31);
        $cadenaestacion=$_REQUEST['EstacionId'].' - '.$_REQUEST['NombreEstacion'];
        $cadenaestacion=substr($cadenaestacion,0,31);
        $cadptoestacion=$_REQUEST['deptoestacion'];
    $cadptoestacion=str_pad($cadptoestacion,0,31);
        $cadnombrepac=$_REQUEST['nombrepac'];
    $cadnombrepac=str_pad($cadnombrepac,0,31);

        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $productos=$this->productosReportImprimir($_REQUEST['documento']);
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportDevolucion',
        $datos=array("razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],
        "Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],"cadenaestacion"=>$cadenaestacion,"cadptoestacion"=>$cadptoestacion,
        "documento"=>$_REQUEST['documento'],"Fecha"=>$_REQUEST['Fecha'],"cadenausuario"=>$cadenausuario,"productos"=>$productos,
        "rango"=>$_REQUEST['rango'],"tipoafil"=>$_REQUEST['tipoafil'],"cama"=>$_REQUEST['cama'],"pieza"=>$_REQUEST['pieza'],"plan"=>$_REQUEST['plan'],"tipoidPac"=>$_REQUEST['tipoidPac'],"paciente"=>$_REQUEST['paciente'],"cadnombrepac"=>$cadnombrepac),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
    $this->FormaDevolucionMedicamentos();
        return true;
  }

    function productosReportImprimir($Documento){

        list($dbconn) = GetDBconn();
        $query="(SELECT x.codigo_producto,x.descripcion_abreviada as desmed,b.cantidad,x2.descripcion as ubicacion,u.abreviatura
        FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d b,unidades u,inventarios_productos x
        LEFT JOIN existencias_bodegas x1 ON (x.codigo_producto=x1.codigo_producto AND x1.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x1.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x1.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
        LEFT JOIN bodegas_ubicaciones x2 ON (x1.ubicacion_id=x2.ubicacion_id)
        WHERE a.documento='".$Documento."' AND a.documento=b.documento AND b.estado='0' AND b.codigo_producto=x.codigo_producto AND x.unidad_id=u.unidad_id)
        ";
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


    function DatosSolicitudesDepartamento($departamento){

        list($dbconn) = GetDBconn();
    $query = "(SELECT i.tipo_id_paciente||' '||i.paciente_id,a.solicitud_id,det.consecutivo_d,a.estacion_id,a.fecha_solicitud,a.ingreso,d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
        e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,
        l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
        j.cama,j.pieza,a.tipo_solicitud,b.descripcion as nomestacion,
        det.medicamento_id as codigo_producto,invp.descripcion_abreviada as desmed,det.cant_solicitada,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
        FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e
    LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta AND f.fecha_egreso is NULL)
        LEFT JOIN camas j ON(f.cama=j.cama)
        ,planes h,ingresos i,tipos_afiliado k,pacientes l,hc_solicitudes_medicamentos_d det
        ,inventarios_productos invp
        LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
        LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
        ,unidades u
        WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
        AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
        AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id AND
        a.solicitud_id=det.solicitud_id AND
        det.medicamento_id=invp.codigo_producto AND invp.unidad_id=u.unidad_id
        ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha_solicitud)
        UNION
        (SELECT i.tipo_id_paciente||' '||i.paciente_id,a.solicitud_id,det.consecutivo_d,a.estacion_id,a.fecha_solicitud,a.ingreso,d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
        e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,
        l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
        j.cama,j.pieza,a.tipo_solicitud,b.descripcion as nomestacion,
        det.medicamento_id as codigo_producto,invp.descripcion_abreviada as desmed,det.cant_solicitada as cant_solicitada,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
        FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e
    LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta AND f.fecha_egreso is NULL)
        LEFT JOIN camas j ON(f.cama=j.cama)
        ,planes h,ingresos i,tipos_afiliado k,pacientes l,hc_solicitudes_insumos_d det,
        inventarios_productos invp
        LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
        LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
        ,unidades u
        WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
        AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
        AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id AND
        a.solicitud_id=det.solicitud_id AND
        det.medicamento_id=invp.codigo_producto AND invp.unidad_id=u.unidad_id
        ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha_solicitud)";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $vars[$result->fields[0]][$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;

    }

    function DatosDevolucionesDepartamento($departamento){

        list($dbconn) = GetDBconn();
    $query = "(SELECT i.tipo_id_paciente||' '||i.paciente_id,a.documento,det.consecutivo,a.estacion_id,a.fecha,a.ingreso,d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
        e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,
        l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
        j.cama,j.pieza,b.descripcion as nomestacion,
        det.codigo_producto,invp.descripcion_abreviada as desmed,det.cantidad,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
        FROM inv_solicitudes_devolucion a,estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e
    LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta AND f.fecha_egreso is NULL)
        LEFT JOIN camas j ON(f.cama=j.cama)
        ,planes h,ingresos i,tipos_afiliado k,pacientes l,inv_solicitudes_devolucion_d det
        ,inventarios_productos invp
        LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
        LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
        ,unidades u
        WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
        AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso AND (e.estado='1' OR e.estado='2')
        AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id AND
        a.documento=det.documento AND det.estado='0' AND
        det.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id
        ORDER BY l.tipo_id_paciente,l.paciente_id,a.fecha)";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $vars[$result->fields[0]][$result->fields[1]][$result->fields[2]]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;

    }

    function DatosTotalesSolicitudesDpto($departamento){

        list($dbconn) = GetDBconn();
    $query = "
             (SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion_abreviada as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
                  FROM
                  (SELECT det.medicamento_id as codigo_producto,sum(det.cant_solicitada) as cant_solicitada
              FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
                            a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND
                            a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
              GROUP BY det.medicamento_id) as a,
                            inventarios_productos invp
                            LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
                            LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
                            ,unidades u
                          WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada
                         )";
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
        $query ="(SELECT a.codigo_producto,a.cant_solicitada,invp.descripcion_abreviada as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
                  FROM
                  (SELECT det.medicamento_id as codigo_producto,sum(det.cant_solicitada) as cant_solicitada
              FROM hc_solicitudes_medicamentos a,hc_solicitudes_insumos_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
                            a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.sw_estado='0' AND
                            a.solicitud_id=det.solicitud_id AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
              GROUP BY det.codigo_producto) as a,
                            inventarios_productos invp
                            LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
                            LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
                            ,unidades u
                         WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada)
                         ";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $vars1[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        $vector[0]=$vars;
        $vector[1]=$vars1;
        return $vector;
    }

    function ImprimirSolicitudMedTotalDpto(){

    $vars=$this->DatosSolicitudesDepartamento($_REQUEST['departamento']);
        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportSolicitudDepartamento',
        array("Datos"=>$vars,"razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],
        "Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],"CentroUtilidad"=>$_SESSION['BODEGAS']['NombreCU'],"departamento"=>$_REQUEST['departamento'],
        "descripcionDpto"=>$_REQUEST['descripcionDpto']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
    $this->FormaListadoSolicitudes();
        return true;
  }

    function ImprimirDevolucionesMedTotalDpto(){

    $vars=$this->DatosDevolucionesDepartamento($_REQUEST['departamento']);
        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportDevolucionesDepartamento',
        array("Datos"=>$vars,"razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],
        "Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],"CentroUtilidad"=>$_SESSION['BODEGAS']['NombreCU'],"departamento"=>$_REQUEST['departamento'],
        "descripcionDpto"=>$_REQUEST['descripcionDpto']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
    $this->FormaDevolucionMedicamentos();
        return true;
  }

    function ImprimirTotalesSolicitudesDpto(){

    $vars=$this->DatosTotalesSolicitudesDpto($_REQUEST['departamento']);
        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportSolicitudesTotalesDpto',
        array("Datos"=>$vars,"razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],
        "Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],"CentroUtilidad"=>$_SESSION['BODEGAS']['NombreCU'],"departamento"=>$_REQUEST['departamento'],
        "descripcionDpto"=>$_REQUEST['descripcionDpto']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
    $this->FormaListadoSolicitudes();
        return true;
  }

    function ImprimirTotalesDevolucionesDpto(){

    $vars=$this->DatosTotalesDevolucionesDpto($_REQUEST['departamento']);
        if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportDevolucionesTotalesDpto',
        array("Datos"=>$vars,"razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],
        "Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],"CentroUtilidad"=>$_SESSION['BODEGAS']['NombreCU'],"departamento"=>$_REQUEST['departamento'],
        "descripcionDpto"=>$_REQUEST['descripcionDpto']),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
    $this->FormaDevolucionMedicamentos();
        return true;
  }

    function DatosTotalesDevolucionesDpto($departamento){

        list($dbconn) = GetDBconn();
        $query ="(SELECT a.codigo_producto,a.cantidad,invp.descripcion_abreviada as desmed,bu.descripcion as ubicacion,u.abreviatura,exis.existencia
                  FROM
                  (SELECT det.codigo_producto,sum(det.cantidad) as cantidad
              FROM inv_solicitudes_devolucion a,inv_solicitudes_devolucion_d det,estaciones_enfermeria b
              WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
                            a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.estado='0' AND
                            a.documento=det.documento AND det.estado='0' AND a.estacion_id=b.estacion_id AND b.departamento='".$departamento."'
              GROUP BY det.codigo_producto) as a,
                            inventarios_productos invp
                            LEFT JOIN existencias_bodegas exis ON (invp.codigo_producto=exis.codigo_producto AND exis.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND exis.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND exis.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
                            LEFT JOIN bodegas_ubicaciones bu ON (exis.ubicacion_id=bu.ubicacion_id)
                            ,unidades u
                         WHERE a.codigo_producto=invp.codigo_producto AND invp.unidad_id=u.unidad_id ORDER BY invp.descripcion_abreviada)
                         ";
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

    function medicamentosReportImprimir($Solicitud){

        list($dbconn) = GetDBconn();
        $query="(SELECT a.*,x2.descripcion as ubicacion
            FROM
              (SELECT x.codigo_producto,x.descripcion_abreviada as desmed,b.cant_solicitada,u.abreviatura
                  FROM hc_solicitudes_medicamentos a,hc_solicitudes_medicamentos_d b,unidades u,inventarios_productos x
                  WHERE a.solicitud_id='$Solicitud' AND a.solicitud_id=b.solicitud_id AND b.medicamento_id=x.codigo_producto AND x.unidad_id=u.unidad_id
              ) as a
            LEFT JOIN existencias_bodegas x1 ON (a.codigo_producto=x1.codigo_producto AND x1.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND x1.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x1.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
            LEFT JOIN bodegas_ubicaciones x2 ON (x1.ubicacion_id=x2.ubicacion_id)
    )
        UNION
        (    SELECT a.*,y2.descripcion as ubicacion
         FROM
            (SELECT y.codigo_producto,y.descripcion_abreviada as desmed,d.cant_solicitada,u.abreviatura
                FROM hc_solicitudes_medicamentos c,hc_solicitudes_medicamentos_mezclas_d d,unidades u,inventarios_productos y
                WHERE c.solicitud_id='$Solicitud' AND c.solicitud_id=d.solicitud_id AND d.medicamento_id=y.codigo_producto AND y.unidad_id=u.unidad_id
            ) as a
            LEFT JOIN existencias_bodegas y1 ON(a.codigo_producto=y1.codigo_producto AND y1.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND y1.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND y1.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
            LEFT JOIN bodegas_ubicaciones y2 ON(y1.ubicacion_id=y2.ubicacion_id)
    )
        UNION
        (    SELECT a.*,z2.descripcion as ubicacion
         FROM
            (SELECT z.codigo_producto,z.descripcion_abreviada as desmed,f.cant_solicitada as cant_solicitada,u.abreviatura
                FROM hc_solicitudes_medicamentos e,hc_solicitudes_insumos_d f,unidades u,inventarios_productos z
                WHERE e.solicitud_id='$Solicitud' AND e.solicitud_id=f.solicitud_id AND f.medicamento_id=z.codigo_producto AND z.unidad_id=u.unidad_id
            )as a
            LEFT JOIN existencias_bodegas z1 ON (a.codigo_producto=z1.codigo_producto AND z1.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND z1.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND z1.bodega='".$_SESSION['BODEGAS']['BodegaId']."')
            LEFT JOIN bodegas_ubicaciones z2 ON (z1.ubicacion_id=z2.ubicacion_id)
    )";

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

    function ImprimirDespachoMtos($EstacionId,$NombreEstacion,$departamentoEsta,$usuarioestacion,$usuario,$medicamentos,
    $tipoIdPaciente,$Paciente,$nombre,$rango,$tipoAfiliadoId,$pieza,$cama,$plan){
        $cadenaestacion=substr($EstacionId.' - '.$NombreEstacion,0,31);
        $cadptoestacion=substr($departamentoEsta,0,31);
        $cadusuestacion=substr($usuarioestacion,0,31);
        $cadusuSYstem=substr($usuario,0,31);
        $cadenanombre=substr($nombre,0,31);
    if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportDespacha',
        $datos=array("razonsocial"=>$_SESSION['BODEGAS']['NombreEmp'],"CentroUtili"=>$_SESSION['BODEGAS']['NombreCU'],
        "BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],"Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],
        "cadenaestacion"=>$cadenaestacion,"cadptoestacion"=>$cadptoestacion,"cadusuestacion"=>$cadusuestacion,
        "Fecha"=>date("d-m-Y H:i:s"),"cadusuSYstem"=>$cadusuSYstem,"medicamentos"=>$medicamentos,
        "tipoIdPaciente"=>$tipoIdPaciente,"Paciente"=>$Paciente,"cadenanombre"=>$cadenanombre,"rango"=>$rango,"tipoAfiliadoId"=>$tipoAfiliadoId,"pieza"=>$pieza,"cama"=>$cama,
        "plan"=>$plan),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
        $mensaje = "EL DOCUMENTO DEL DESPACHO DE MEDICAMENTOS SE REALIZO CORRECTAMENTE, Y SE ESTA IMPRIMIMIENDO EL REPORTE PARA EL PATINADOR";
        $titulo = "DESPACHO DE MEDICAMENTOS BODEGAS";
        $accion =ModuloGetURL('app','InvBodegas','user','LlamaSoliciMedica');
        $boton = "REGRESAR";
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
    }

    function LlamaImprimirDocumentoBodega(){
      list($dbconn) = GetDBconn();
      $query="SELECT a.codigo_producto,b.descripcion_abreviada,a.cantidad,a.total_costo,c.unidad_id
        FROM bodegas_documentos_d a,inventarios_productos b,unidades c,bodegas_doc_numeraciones x
        WHERE a.numeracion='".$_REQUEST['Documento']."' AND a.bodegas_doc_id='".$_REQUEST['concepto']."' AND a.bodegas_doc_id=x.bodegas_doc_id AND
        x.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        x.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND x.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        a.codigo_producto=b.codigo_producto AND b.unidad_id=c.unidad_id";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $varsProductos[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
            $result->Close();
        }
        $query="SELECT descripcion FROM bodegas WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['centroutiliTrans']."' AND bodega='".$_REQUEST['BodegaTrans']."'";
        $result = $dbconn->Execute($query);
        $desBodTrans=$result->fields[0];
        $query="SELECT a.estacion_id,b.descripcion as deesta,c.descripcion as dpto,a.usuario_id,d.nombre
        FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,departamentos c,system_usuarios d
        WHERE a.solicitud_id='".$_REQUEST['solicitud_id']."' AND a.estacion_id=b.estacion_id AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id";
        $result = $dbconn->Execute($query);
        $cadenaEstacion=substr($result->fields[0].' - '.$result->fields[1],0,31);
        $caddptoEsta=substr($result->fields[2],0,31);
    $cadusuEsta=substr($result->fields[3].' - '.$result->fields[4],0,31);
        $this->ImprimirDocumentoBodega($_REQUEST['fecha'],$_REQUEST['Documento'],$_REQUEST['prefijo'],
        $_REQUEST['solicitud_id'],$_REQUEST['nomconcepto'],$_REQUEST['costo'],$_REQUEST['centroutiliTrans'],
        $_REQUEST['BodegaTrans'],$desBodTrans,$cadenaEstacion,$caddptoEsta,$cadusuEsta,$varsProductos,
        $_REQUEST['Busqueda'],$_REQUEST['documentos'],$_REQUEST['FechaInicial'],$_REQUEST['FechaFinal']);
        return true;
    }

    function ImprimirDocumentoBodega($fecha,$Documento,$Prefijo,$solicitud_id,$concepto,$costo,$centroutiliTrans,
    $BodegaTrans,$desBodTrans,$cadenaEstacion,$caddptoEsta,$cadusuEsta,$Productos,
    $Busqueda,$documentos,$FechaInicial,$FechaFinal){
    $cadenaconcepto=substr($concepto,0,31);
        if($BodegaTrans && $desBodTrans){
      $cadBodTrans=substr($BodegaTrans.' - '.$desBodTrans,0,31);
        }
      if(!IncludeFile("classes/reports/reports.class.php")){
            $this->error = "No se pudo inicializar la Clase de Reportes";
            $this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
            return false;
    }
        $classReport = new reports;
        $impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
        $reporte=$classReport->PrintReport($tipo_reporte='pos',$tipo_modulo='app',$modulo='InvBodegas',$reporte_name='reportDocumentoBodega',
        $datos=array("Empresa"=>$_SESSION['BODEGAS']['NombreEmp'],"BodegaId"=>$_SESSION['BODEGAS']['BodegaId'],"Bodega"=>$_SESSION['BODEGAS']['NombreBodega'],
        "fecha"=>$fecha,"Documento"=>$Documento,"Prefijo"=>$Prefijo,"solicitud_id"=>$solicitud_id,"cadenaconcepto"=>$cadenaconcepto,"costo"=>$costo,
        "centroutiliTrans"=>$centroutiliTrans,"cadBodTrans"=>$cadBodTrans,"Productos"=>$Productos,
        "cadenaEstacion"=>$cadenaEstacion,"caddptoEsta"=>$caddptoEsta,"cadusuEsta"=>$cadusuEsta),$impresora,$orientacion='',$unidades='',$formato='',$html=1);
        if(!$reporte){
                $this->error = $classReport->GetError();
                $this->mensajeDeError = $classReport->MensajeDeError();
                unset($classReport);
                return false;
        }
        $resultado=$classReport->GetExecResultado();
        unset($classReport);
        if(!empty($resultado[codigo]))
        {
            "El PrintReport retorno : " . $resultado[codigo] . "<br>";
        }
        $this->BusquedaDocumentosBodega($Busqueda,$documentos,$FechaInicial,$FechaFinal);
        return true;
    }

    function GuardarNumeroDocumento($commit=true)
    {
            list($dbconn) = GetDBconn();
			
            if($commit)
            {
                $sql="COMMIT;";
            }
            else
            {
                $sql="ROLLBACK;";
            }
            $result = $dbconn->Execute($sql);
            if ($dbconn->ErrorNo() != 0) {
                die(MsgOut("Error al terminar la transaccion","Error DB : " . $dbconn->ErrorMsg()));
                return false;
            }
            return true;
    }

  function LlamaCreacionTiposDocBodegas(){
    $this->CreacionTiposDocBodegas();
        return true;
    }

    function ConsultaTiposDocumento(){
        list($dbconn) = GetDBconn();
    $query="SELECT tipo_doc_bodega_id,descripcion FROM tipos_doc_bodega";
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function TipoMovimiento(){

        list($dbconn) = GetDBconn();
    $query="SELECT tipo_mov,descripcion FROM bodegas_tipo_movimiento";
        $result = $dbconn->Execute($query);
        if($result->EOF){
            $this->error = "Error al ejecutar la consulta.<br>";
            $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
            return false;
        }else{
          $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF) {
                    $vars[$result->fields[0]]=$result->fields[1];
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function GuardarTipDocBodega(){
    if($_REQUEST['Salir']){
          $this->MenuInventarios3();
            return true;
        }
        if($_REQUEST['Cancelar']){
      $this->CreacionTiposDocBodegas('','','','','','',$_REQUEST['cambio'],'','');
            return true;
        }
        if(empty($_REQUEST['BodegaDocId'])){
            $confirma=$this->cofirmarExisteTipoDocuemtno($_REQUEST['tipoDocumento'],$_REQUEST['prefijo']);
            if($confirma==1){
                $this->frmError["MensajeError"]="Ya Existe este Tipo de Documento";
                $this->CreacionTiposDocBodegas($_REQUEST['tipoDocumento'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],
                $_REQUEST['numeracion'],$_REQUEST['movimiento'],$_REQUEST['digitos'],'1',$_REQUEST['concepto']);
                return true;
            }
        }
    if($_REQUEST['tipoDocumento']==-1 || !$_REQUEST['prefijo'] || !$_REQUEST['numeracion'] || $_REQUEST['movimiento']==-1){
            if($_REQUEST['tipoDocumento']==-1){$this->frmError["tipoDocumento"]=1;}
            if(!$_REQUEST['prefijo']){$this->frmError["prefijo"]=1;}
            if(!$_REQUEST['numeracion']){$this->frmError["numeracion"]=1;}
            if($_REQUEST['movimiento']==-1){$this->frmError["movimiento"]=1;}
            $this->frmError["MensajeError"]="Faltan Datos Obligatorios";
            $this->CreacionTiposDocBodegas($_REQUEST['tipoDocumento'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],
            $_REQUEST['numeracion'],$_REQUEST['movimiento'],$_REQUEST['digitos'],'',$_REQUEST['concepto']);
            return true;
        }
        if($_REQUEST['numeracion']<0){
      $this->frmError["MensajeError"]="El Inicio de la Numeracion no Puede Ser Menor que Cero";
            $this->CreacionTiposDocBodegas($_REQUEST['tipoDocumento'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],
            $_REQUEST['numeracion'],$_REQUEST['movimiento'],$_REQUEST['digitos'],'',$_REQUEST['concepto']);
            return true;
        }
    if($_REQUEST['concepto']=='swajuste'){$swajuste='1';}else{$swajuste='0';}
    if($_REQUEST['concepto']=='swtraslado'){$swtraslado='1';}else{$swtraslado='0';}
    if($_REQUEST['concepto']=='swcompras'){$swcompras='1';}else{$swcompras='0';}
    if($_REQUEST['concepto']=='transmed'){$transmed='1';}else{$transmed='0';}

        if($_REQUEST['digitos']>0){
          $digitos=$_REQUEST['digitos'];
        }else{
          $digitos=0;
        }
        if($_REQUEST['BodegaDocId']){
      $query="UPDATE bodegas_doc_numeraciones SET tipo_doc_bodega_id='".$_REQUEST['tipoDocumento']."',
            prefijo='".$_REQUEST['prefijo']."',descripcion='".$_REQUEST['descripcion']."',
            tipo_movimiento='".$_REQUEST['movimiento']."',sw_ajuste='$swajuste',sw_traslado='$swtraslado',
            sw_compras='$swcompras',numero_digitos='$digitos',sw_transaccion_medicamentos='$transmed' WHERE
            bodegas_doc_id='".$_REQUEST['BodegaDocId']."'";
        }else{
          $query="INSERT INTO bodegas_doc_numeraciones(empresa_id,centro_utilidad,bodega,tipo_doc_bodega_id,
            prefijo,descripcion,numeracion,sw_estado,tipo_movimiento,sw_ajuste,sw_traslado,
            sw_compras,numero_digitos,sw_transaccion_medicamentos)VALUES('".$_SESSION['BODEGAS']['Empresa']."',
            '".$_SESSION['BODEGAS']['CentroUtili']."','".$_SESSION['BODEGAS']['BodegaId']."','".$_REQUEST['tipoDocumento']."',
            '".$_REQUEST['prefijo']."','".$_REQUEST['descripcion']."','".($_REQUEST['numeracion']-1)."','1',
            '".$_REQUEST['movimiento']."','$swajuste','$swtraslado','$swcompras','$digitos','$transmed')";
        }
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg()."[".get_class($this)."][".__LINE__."]";
            return false;
        }
        $this->CreacionTiposDocBodegas('','','','','','',1,'','');
        return true;
    }

    function VerDocumentosCreados(){
        $this->CreacionTiposDocBodegas('','','','','','',1);
        return true;
    }

    function cofirmarExisteTipoDocuemtno($tipoDocumento,$prefijo){
    list($dbconn) = GetDBconn();
        $query="SELECT * FROM bodegas_doc_numeraciones WHERE empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        tipo_doc_bodega_id='".$tipoDocumento."' AND prefijo='".$prefijo."'";
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

    function RegistroDocumentosCreados(){
    list($dbconn) = GetDBconn();
        $query="SELECT a.bodegas_doc_id,b.descripcion as nomtipodocumento,a.tipo_doc_bodega_id,a.prefijo,a.descripcion,c.descripcion as tipomov,a.tipo_movimiento,a.sw_ajuste,a.sw_traslado,a.sw_compras,a.sw_transaccion_medicamentos,a.numero_digitos,a.numeracion
        FROM bodegas_doc_numeraciones a,tipos_doc_bodega b,bodegas_tipo_movimiento c
        WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND
        a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.sw_estado='1' AND
        a.tipo_doc_bodega_id=b.tipo_doc_bodega_id AND a.tipo_movimiento=c.tipo_mov";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      $datos=$result->RecordCount();
            if($datos){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        return $vars;
    }

    function EditarTipoDocumento(){
    if($_REQUEST['ajuste']){
      $_REQUEST['concepto']='swajuste';
        }elseif($_REQUEST['traslado']){
      $_REQUEST['concepto']='swtraslado';
        }elseif($_REQUEST['compras']){
      $_REQUEST['concepto']='swcompras';
        }elseif($_REQUEST['medica']){
      $_REQUEST['concepto']='transmed';
        }else{
      $_REQUEST['concepto']='swninguno';
        }
    $this->CreacionTiposDocBodegas($_REQUEST['tipoDocumento'],$_REQUEST['prefijo'],$_REQUEST['descripcion'],
        $_REQUEST['numeracion'],$_REQUEST['movimiento'],$_REQUEST['digitos'],1,$_REQUEST['concepto'],$_REQUEST['BodegaDocId']);
        return true;
    }

    function LlamaMtoFechasVencimiento(){
    $this->MtoFechasVencimiento();
        return true;
    }

    function BusquedaPtoFechasVmto(){
    $this->MtoFechasVencimiento($_REQUEST['codigoProd'],$_REQUEST['descripcion']);
        return true;
    }

    function LlamaModificacionFechaVmto(){
    $this->ModificacionFechaVmto($_REQUEST['producto'],$_REQUEST['descripcion'],$_REQUEST['existencias']);
        return true;
    }

    function BuscarPtosModFechasVmto($codigoProd,$descripcion){

    list($dbconn) = GetDBconn();
        $query="SELECT a.codigo_producto,b.descripcion,a.existencia FROM existencias_bodegas a,inventarios_productos b WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
        AND a.codigo_producto=b.codigo_producto";
        if($descripcion){
      $query.=" AND b.descripcion LIKE '%$descripcion%'";
        }
        if($codigoProd){
      $query.=" AND b.codigo_producto LIKE '%$codigoProd%'";
        }
        if(empty($_REQUEST['conteo'])){
          $result = $dbconn->Execute($query);
            $dat = $result->RecordCount();
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }
          $this->conteo=$dat;
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
      $Of='0';
        }else{
      $Of=$_REQUEST['Of'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function InsertarModifyFechas(){

    list($dbconn) = GetDBconn();
        if(!$numeroDoc){
            $query="SELECT bodegas_doc_id,numeracion FROM bodegas_doc_numeraciones WHERE sw_ajesteFechasVmto=1 AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND
            bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
            $result=$dbconn->Execute($query);
            $bodegaDocId=$result->fields[0];
            $query="SELECT nextval('tmp_bodegas_documentos_documento_seq')";
            $result=$dbconn->Execute($query);
            $numeroDoc=$result->fields[0];
            $query="INSERT INTO tmp_bodegas_documentos (documento,
                                                                                                    fecha,
                                                                                                    total_costo,
                                                                                                    transaccion,
                                                                                                    observacion,
                                                                                                    usuario_id,
                                                                                                    fecha_registro,
                                                                                                    bodegas_doc_id)
                                                                                                    VALUES(
                                                                                                    '$numeroDoc',
                                                                                                    '".date("Y-m-d")."',
                                                                                                    0,NULL,'','".UserGetUID()."',
                                                                                                    '".date("Y-m-d H:i:s")."',
                                                                                                    '$bodegaDocId')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $query="INSERT INTO tmp_bodegas_documentos_d(documento,
                                                                                                    codigo_producto,
                                                                                                    cantidad,
                                                                                                    total_costo,
                                                                                                    bodegas_doc_id,
                                                  iva_compra)
                                                                                        VALUES('$numeroDoc',
                                                                                        '$numeroDoc',
                                                                                        '".$_REQUEST['producto']."',
                                                                                        '".$_REQUEST['existencias']."',
                                                                                        '0','$bodegaDocId','0.0')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
        }
        $query="INSERT INTO tmp_bodegas_documentos_d_fvencimiento_lotes(
                                                          lote,
                                                                                                            saldo,
                                                                                                            cantidad,
                                                                                                            empresa_id,
                                                                                                            centro_utilidad,
                                                                                                            bodega,
                                                                                                            codigo_producto,
                                                                                                            consecutivo,
                                                                                                            fecha_vencimiento)VALUES()";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

    }

    function UltimaFechaReposicionBodega($Bodega,$centroUtilidad){

        list($dbconn) = GetDBconn();
        $query="SELECT b.fecha
        FROM bodegas_doc_numeraciones a,bodegas_documentos b
        WHERE a.sw_traslado='1' AND a.tipo_movimiento='E' AND a.sw_estado='1' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
        a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
        a.bodegas_doc_id=b.bodegas_doc_id AND
        b.centro_utilidad_transferencia='".$centroUtilidad."' AND b.bodega_destino_transferencia='".$Bodega."'
        ORDER BY b.fecha DESC";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if($result->RecordCount()>0){
                $vars=$result->GetRowAssoc($toUpper=false);
                $result->Close();
            }
        }
        return $vars;
    }

    /**
* Funcion que realiza el despacho de la solicitud de la bodega
* @return boolean
*/
    function DespacharMedicamentosDepartamento(){

        $CheckDespachar = $_REQUEST['CheckDespachar'];
        $CantDespachar = $_REQUEST['CantDespachar'];
        $SelectMedicamentos = $_REQUEST['SelectMedicamentos'];
        $datos_bodega = $_REQUEST['datos_bodega'];
        GLOBAL $ADODB_FETCH_MODE;
        $Salir=$_REQUEST['Salir'];
        if($Salir){
      $this->FormaListadoSolicitudes();
            return true;
        }
        list($dbconn) = GetDBconn();
        if(sizeof($CheckDespachar) == 0){
            $mensaje = "DEBE SELECCIONAR LOS MEDICAMENTOS A DESPACHO";
            $titulo = "DESPACHO SOLICITUD MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDespachoSolicitudesDpto',array("departamento"=>$_REQUEST['departamento'],"descripcionDpto"=>$_REQUEST['descripcionDpto']));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }

        $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='E'
        AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
        if(empty($concepto)){
            $mensaje = "NO EXISTE EL TIPO DE DOCUMENTO PARA REALIZAR EL MOVIMIENTO AUTOMATICO EN LA BODEGA";
            $titulo = "DESPACHO SOLICITUD MEDICAMENTOS";
            $accion = ModuloGetURL('app','InvBodegas','user','LlamaDespachoSolicitudesDpto',array("departamento"=>$_REQUEST['departamento'],"descripcionDpto"=>$_REQUEST['descripcionDpto']));
            $boton = "REGRESAR";
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }

    //ordenar por # de solicitud para generar los documentos de bodega
        for($i=0; $i<sizeof($CheckDespachar); $i++){
            $y= explode(".-.",$CheckDespachar[$i]);
            $temp = array();//echo "<br><br>compara si existe el key = ".$y[3]." en el select"; print_r($SelectMedicamentos);
            //echo "<br>respuesta-> ".array_key_exists ( $y[3], $SelectMedicamentos);
            if(array_key_exists($y[3], $SelectMedicamentos)){//echo "<br><br>compara si medicamento del check = ".$y[1]." igual al del select ".$SelectMedicamentos[$y[3]];
                //echo "<br>respuesta=> ".strcmp($y[1],$SelectMedicamentos[$y[3]]);
                if(strcmp($y[1],$SelectMedicamentos[$y[3]]) != 0)//son diferentes
                {
                    $temp = $y;
                    $temp[1] = $SelectMedicamentos[$y[3]];
                    $datos[$y[0]][] = $temp;
                }else{
                    $datos[$y[0]][] = $y;
                }
            }else{
                $datos[$y[0]][] = $y;
            }
        }
        $this->ConfirmacionDespachoPendientesDpto($datos,$CantDespachar,$_REQUEST['departamento'],$_REQUEST['descripcionDpto'],$concepto);
        return true;
  }


    function LlamaConfirmacionDespachoPendientesDpto(){
    $this->ConfirmacionDespachoPendientesDpto($datos,$CantDespachar,$_REQUEST['departamento'],$_REQUEST['descripcionDpto'],$_REQUEST['concepto']);
        return true;
    }


  function GuardaDespachoMedDepartamentoConfirmacion(){

        if($_REQUEST['cancelar']){
            $Motivos=$_REQUEST['motivoCancelacion'];
      foreach($_REQUEST['cancelar'] as $SolicitudCodigo=>$cantidad){
        if(empty($Motivos[$SolicitudCodigo]) || $Motivos[$SolicitudCodigo]==-1){
                  (list($Solicitud,$Codigo)=explode('||//',$SolicitudCodigo));
          $this->frmError["MensajeError"]='Debe Especificar Algun Motivo para la cancelacion del Producto '.$Codigo.' de la Solicitud '.$Solicitud;
          $this->ConfirmacionDespachoPendientesDpto($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['departamento'],$_REQUEST['descripcionDpto'],$_REQUEST['concepto']);
                    return true;
                }
            }
        }
        list($dbconn) = GetDBconn();
        $dbconn->BeginTrans();
        //Numero Documento
        $datos=$_REQUEST['datos'];
    $CantDespachar=$_REQUEST['CantDespachar'];
        $concepto=$_REQUEST['concepto'];
        //Tipo Documento
        foreach($datos as $Solici=>$value){
      $query="SELECT date(a.fecha_solicitud) as fecha,a.tipo_solicitud,a.ingreso,a.estacion_id FROM hc_solicitudes_medicamentos a WHERE a.solicitud_id='".$Solici."';";
      $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
        if($result->RecordCount()>0){
                    $vars=$result->GetRowAssoc($toUpper=false);
                    $result->Close();
                    $fecha=$vars['fecha'];
          $TipoSolicitud=$vars['tipo_solicitud'];
                    $ingreso=$vars['ingreso'];
          $EstacionId=$vars['estacion_id'];
                }
            }
            $query="SELECT nextval('bodegas_documento_despacho_med_documento_despacho_id_seq')";
            $result = $dbconn->Execute($query);
            $documento=$result->fields[0];
            //Insertar Documento
            $query="INSERT INTO bodegas_documento_despacho_med(documento_despacho_id,bodegas_doc_id,
                                                                                                fecha,total_costo,
                                                                                                observacion,usuario_id,
                                                                                                fecha_registro)
                                                                                    VALUES('$documento',$concepto,
                                                                                                '".date("Y-m-d")."','0',
                                                                                                '','".UserGetUID()."',
                                                                                                '".date("Y-m-d H:i:s")."')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Tabla bodegas_documento_despacho_med";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
                return false;
            }else{
        foreach($value as   $keyVal=>$valores)//por cada medicamento de la solicitud
                {
                    //echo "<br>key==>.".$keyVal."<br>";  print_r($valores);
                    $contador=$valores[3];
                    $costoProducto=$this->HallarCostoProducto($_SESSION['BODEGAS']['Empresa'],$valores[1]);
                    if($TipoSolicitud!='I'){
                        $query="SELECT nextval('bodegas_documento_despacho_med_d_consecutivo_depacho_seq')";
                        $result = $dbconn->Execute($query);
                        $consecutivo=$result->fields[0];
                        $query="INSERT INTO bodegas_documento_despacho_med_d(consecutivo_depacho,documento_despacho_id,
                                                                                                            codigo_producto,cantidad,
                                                                                                            total_costo,consecutivo_solicitud)
                                                                                                            VALUES('$consecutivo','$documento',
                                                                                                            '".$valores[1]."','".$CantDespachar[$contador]."',
                                                                                                            '$costoProducto','".$valores[5]."')";

                    }else{
                        $query="SELECT nextval('bodegas_documento_despacho_ins_d_consecutivo_depacho_seq')";
                        $result = $dbconn->Execute($query);
                        $consecutivo=$result->fields[0];
                        $query="INSERT INTO bodegas_documento_despacho_ins_d(consecutivo_depacho,documento_despacho_id,
                                                                                                            codigo_producto,cantidad,
                                                                                                            total_costo,consecutivo_solicitud)
                                                                                                            VALUES('$consecutivo','$documento',
                                                                                                            '".$valores[1]."','".$CantDespachar[$contador]."',
                                                                                                            '$costoProducto','".$valores[5]."')";

                    }
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Guardar en el detalle del Documento bodegas_documento_despacho_med";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $dbconn->RollbackTrans();
                        return false;
                    }
                }
                $totalizCostoDoc=$this->TotalizarDocDepacho($documento,$TipoSolicitud);
                //$InsSolicitudes=$this->tmpInsertarSolicitudes($_REQUEST['SolicitudId'],$documento);
                $query="UPDATE hc_solicitudes_medicamentos SET sw_estado='1',documento_despacho='".$documento."' WHERE solicitud_id=".$Solici." AND tipo_solicitud =  '".$TipoSolicitud."'";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Guardar en la Tabla hc_solicitudes_medicamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $dbconn->RollbackTrans();
                    return false;
                }
            }
            if($_REQUEST['pendiente']){
              $insertada=0;
              foreach($_REQUEST['pendiente'] as $SolicitudCodigo=>$cantidad){
          (list($Solicitud,$Codigo)=explode('||//',$SolicitudCodigo));
                  (list($canti,$evolucion)=explode('||//',$cantidad));
                    if($Solicitud==$Solici && $insertada==0){
                        $query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq')";
                        $result = $dbconn->Execute($query);
                        $solicitudActiva=$result->fields[0];
                        $query="INSERT INTO hc_solicitudes_medicamentos(
                        solicitud_id,ingreso,bodega,empresa_id,centro_utilidad,
                        usuario_id,sw_estado,fecha_solicitud,
                        estacion_id,tipo_solicitud,documento_despacho,bodegas_doc_id,
                        numeracion)VALUES('$solicitudActiva','".$ingreso."','".$_SESSION['BODEGAS']['BodegaId']."','".$_SESSION['BODEGAS']['Empresa']."',
                        '".$_SESSION['BODEGAS']['CentroUtili']."','".UserGetUID()."','0','".date("Y-m-d H:i:s")."','".$EstacionId."',
                        '".$TipoSolicitud."',NULL,NULL,NULL);";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Tabla hc_solicitudes_medicamentos por Pendientes";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                        $vectorSolicitudes[]=$solicitudActiva;
                        $insertada=1;
                    }
                    if($Solicitud==$Solici){
                        if($TipoSolicitud=='I'){
                            $query="INSERT INTO hc_solicitudes_insumos_d(
                            medicamento_id,cant_solicitada,solicitud_id)VALUES(
                            '$Codigo','$canti','$solicitudActiva');";
                        }else{
                            $query="INSERT INTO hc_solicitudes_medicamentos_d(
                            solicitud_id,medicamento_id,evolucion_id,cant_solicitada,
                            mezcla_recetada_id)VALUES(
                            '$solicitudActiva','$Codigo','$evolucion','$canti',NULL);";
                        }
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en el detalle de la Tabla hc_solicitudes_medicamentos por Pendientes";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
      if($_REQUEST['cancelar']){
              $insertada=0;
              foreach($_REQUEST['cancelar'] as $SolicitudCodigo=>$cantidad){
                  (list($Solicitud,$Codigo)=explode('||//',$SolicitudCodigo));
                  (list($canti,$evolucion)=explode('||//',$cantidad));
                    $Motivos=$_REQUEST['motivoCancelacion'];
                    $Observaciones=$_REQUEST['observaciones'];
                    if($Solicitud==$Solici && $insertada==0){
                        $query="SELECT nextval('hc_solicitudes_medicamentos_solicitud_id_seq')";
                        $result = $dbconn->Execute($query);
                        $solicitudInactiva=$result->fields[0];
                        $query="INSERT INTO hc_solicitudes_medicamentos(
                        solicitud_id,ingreso,bodega,empresa_id,centro_utilidad,
                        usuario_id,sw_estado,fecha_solicitud,
                        estacion_id,tipo_solicitud,documento_despacho,bodegas_doc_id,
                        numeracion)VALUES('$solicitudInactiva','".$ingreso."','".$_SESSION['BODEGAS']['BodegaId']."','".$_SESSION['BODEGAS']['Empresa']."',
                        '".$_SESSION['BODEGAS']['CentroUtili']."','".UserGetUID()."','3','".date("Y-m-d H:i:s")."','".$EstacionId."',
                        '".$TipoSolicitud."',NULL,NULL,NULL)";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Tabla hc_solicitudes_medicamentos por Canceladas";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
            $insertada=1;
                    }
                    if($Solicitud==$Solici){
                        if($TipoSolicitud=='I'){
                            $query="SELECT nextval('hc_solicitudes_insumos_d_consecutivo_d_seq')";
                            $result = $dbconn->Execute($query);
                            $consec=$result->fields[0];
                            $query="INSERT INTO hc_solicitudes_insumos_d(
                            consecutivo_d,medicamento_id,cant_solicitada,solicitud_id)VALUES(
                            '$consec','$Codigo','$canti','$solicitudInactiva');";
                            $query.="INSERT INTO hc_solicitudes_insumos_motivos_cancela(
                            consecutivo_d,motivo_id,observaciones)VALUES('$consec','".$Motivos[$SolicitudCodigo]."','".$Observaciones[$SolicitudCodigo]."');";
                        }else{
                            $query="SELECT nextval('hc_solicitudes_medicamentos_d_consecutivo_d_seq')";
                            $result = $dbconn->Execute($query);
                            $consec=$result->fields[0];
                            $query="INSERT INTO hc_solicitudes_medicamentos_d(
                            consecutivo_d,solicitud_id,medicamento_id,evolucion_id,
                            cant_solicitada,mezcla_recetada_id)VALUES(
                            '$consec','$solicitudInactiva','$Codigo','$evolucion',
                            '$canti',NULL);";
                            $query.="INSERT INTO hc_solicitudes_medicamentos_motivos_cancela(
                            consecutivo_d,motivo_id,observaciones)VALUES('$consec','".$Motivos[$SolicitudCodigo]."','".$Observaciones[$SolicitudCodigo]."');";
                        }
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en el detalle de la Tabla por Canceladas";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $dbconn->RollbackTrans();
                            return false;
                        }
                    }
                }
            }
        }
        $dbconn->CommitTrans();
        $mensaje="Documentos Despachado Correctamente";
        if(sizeof($vectorSolicitudes)>0){
      $mensaje.=" ,Nuevas Solicitudes Creadas: ";
            for($i=0;$i<sizeof($vectorSolicitudes);$i++){
        $mensaje.=" -".$vectorSolicitudes[$i]." ";
            }
        }
        $titulo="DESPACHO DE MEDICAMENTOS";
        $accion=ModuloGetURL('app','InvBodegas','user','FormaListadoSolicitudes');
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
        //fin por cada solicitud

    }//fin functionUpdateX



    function LlamaBuscadorProductoExistencias(){
    $this->BuscadorProductoExistencias($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['codigoBus'],$_REQUEST['descripcionBus'],$_REQUEST['origen'],$_REQUEST['CentroUtilityDest'],$_REQUEST['BodegaDest'],
    $_REQUEST['tipoIdProveedor'],$_REQUEST['ProveedorId'],$_REQUEST['proveedor'],$_REQUEST['numFactura'],$_REQUEST['iva'],$_REQUEST['valorFletes'],$_REQUEST['otrosGastos'],$_REQUEST['observaciones']);
        return true;
    }

    function DocumentoProductosExistencias($codigo,$descripcion,$CentroUtilityDest,$BodegaDest){
    list($dbconn) = GetDBconn();
        if($CentroUtilityDest && $BodegaDest){
          $filt2=" ,z1.existencia as exisdes";
      $filt=" JOIN existencias_bodegas z1 ON(z.codigo_producto=z1.codigo_producto AND z1.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND z1.centro_utilidad='".$CentroUtilityDest."' AND z1.bodega='".$BodegaDest."')";
        }
        $query="SELECT x.codigo_producto,l.descripcion,x.precio_venta,z.existencia,y.descripcion as unidad,x.costo $filt2
        FROM existencias_bodegas z
        $filt,
        inventarios x,inventarios_productos l
        LEFT JOIN unidades y ON(l.unidad_id=y.unidad_id)
        WHERE z.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND z.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND z.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND z.estado='1' AND
        z.empresa_id=x.empresa_id AND z.codigo_producto=x.codigo_producto AND
        z.codigo_producto=l.codigo_producto";
    if($codigo){
      $query.=" AND x.codigo_producto  LIKE '$codigo%'";
        }
        if($descripcion){
      $query.=" AND l.descripcion LIKE '%".strtoupper($descripcion)."%'";
        }
        if(empty($_REQUEST['conteo'])){
          $result = $dbconn->Execute($query);
            $dat = $result->RecordCount();
            if($result->EOF){
                $this->error = "Error al ejecutar la consulta.<br>";
                $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
                return false;
            }
          $this->conteo=$dat;
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
      $Of='0';
        }else{
      $Of=$_REQUEST['Of'];
        }
        $query.=" ORDER BY l.descripcion";
        $query.=" LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function LlamaDetalleDocumentosBodega(){
    $this->DetalleDocumentosBodega($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],
        $_REQUEST['cantSolicitada'],
      $_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto']);
        return true;
    }

    function LlamaPtosTransferenciaBodegas(){
    $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['fechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
      $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
        $_REQUEST['TipoReposicion']);
    return true;
    }

    function MotivosCancelacionDespacho(){
      list($dbconn) = GetDBconn();
    $query="SELECT motivo_id,descripcion
        FROM bodegas_motivos_cancelacion_despacho";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function LlamaDevolucionesSolicitudesDpto(){
    $this->DevolucionesSolicitudesDpto($_REQUEST['departamento'],$_REQUEST['descripcionDpto'],$_REQUEST['Lotes'],$_REQUEST['codigoProducto'],$_REQUEST['descripcionProd'],$_REQUEST['cantidad']);
        return true;
    }

    function registrosObservacionesSolicitud($SolicitudId){
    list($dbconn) = GetDBconn();
    $query="SELECT a.observacion_id,a.observacion,a.fecha_registro,a.fecha_ultima_modificacion,
        a.usuario_id,b.usuario,(CASE WHEN a.usuario_id='".UserGetUID()."' THEN 1 ELSE 0 END) as propio
        FROM hc_solicitudes_observaciones_despachos a,system_usuarios b
        WHERE a.solicitud_id='".$SolicitudId."' AND a.usuario_id=b.usuario_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$result->EOF){
                $vars[]=$result->GetRowAssoc($toUpper=false);
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

    function DatosObservacionesSolicitud($Observacion){
    list($dbconn) = GetDBconn();
    $query="SELECT a.observacion_id,a.observacion,a.fecha_registro,a.fecha_ultima_modificacion,
        a.usuario_id,b.usuario
        FROM hc_solicitudes_observaciones_despachos a,system_usuarios b
        WHERE a.observacion_id='".$Observacion."' AND a.usuario_id=b.usuario_id";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
        }
        $result->Close();
        return $vars;
    }

    function InsertarDespachosPendientes(){
      if($_REQUEST['VOLVER']){
            $this->ConfirmacionDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
            $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones']);
            return true;
        }
    list($dbconn) = GetDBconn();
        if(empty($_REQUEST['editar'])){
        $query="SELECT nextval('hc_solicitudes_observaciones_despachos_observacion_id_seq')";
        $result = $dbconn->Execute($query);
    $codigo=$result->fields[0];
    $query="INSERT INTO hc_solicitudes_observaciones_despachos(observacion_id,solicitud_id,observacion,fecha_registro,usuario_id,fecha_ultima_modificacion)VALUES
        ('".$codigo."','".$_REQUEST['SolicitudId']."','".$_REQUEST['observacionesEdit']."','".date("Y-m-d H:i:s")."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
    $_REQUEST['editar']=$codigo;
        }else{
    $query="UPDATE hc_solicitudes_observaciones_despachos SET observacion='".$_REQUEST['observacionesEdit']."',fecha_ultima_modificacion='".date("Y-m-d H:i:s")."' WHERE observacion_id='".$_REQUEST['editar']."'";
        }
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $this->frmError["MensajeError"]="Datos Guardados";
        $this->EditarDespachoPendientes($_REQUEST['datos'],$_REQUEST['CantDespachar'],$_REQUEST['datos_bodega'],$_REQUEST['concepto'],$_REQUEST['Ingreso'],$_REQUEST['SolicitudId'],$_REQUEST['TipoSolicitud'],$_REQUEST['EstacionId'],$_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],
      $_REQUEST['nombrepac'],$_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['pendiente'],$_REQUEST['cancelar'],$_REQUEST['motivoCancelacion'],$_REQUEST['observaciones'],$_REQUEST['editar']);
        return true;
    }

    function BuscarProveedoresProductos($TipoDocumentoBus,$DocumentoBus,$descripcionBus){
      list($dbconn) = GetDBconn();
    $query="SELECT tipo_id_tercero,tercero_id,nombre_tercero
        FROM terceros";
        if((!empty($TipoDocumentoBus) && $TipoDocumentoBus!=-1 && !empty($DocumentoBus))||!empty($descripcionBus)){
      $query.=" WHERE";
            if(!empty($TipoDocumentoBus) && $TipoDocumentoBus!=-1 && !empty($DocumentoBus)){
        $query.=" tipo_id_tercero='".$TipoDocumentoBus."' AND tercero_id='".$DocumentoBus."'";
                $conAnd=1;
            }
            if(!empty($descripcionBus)){
              if($conAnd==1){
          $query.=" AND ";
                }
        $query.="  nombre_tercero LIKE '%".strtoupper($descripcionBus)."%'";
            }
        }
        if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            $dat = $result->RecordCount();
          $this->conteo=$dat;
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
      $Of='0';
        }else{
      $Of=$_REQUEST['Of'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET $Of";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
          if($result->RecordCount()>0){
                while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
            }
        }
        $result->Close();
        return $vars;
    }

    function DescripcionProductoInv($codigo){
    list($dbconn) = GetDBconn();
    $query="SELECT descripcion
        FROM inventarios_productos
        WHERE codigo_producto='".$codigo."'";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $vars=$result->GetRowAssoc($toUpper=false);
        }
        $result->Close();
        return $vars;
    }

    /**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
    function tipo_id_paciente(){
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_id_tercero,descripcion
        FROM tipo_id_terceros ORDER BY indice_de_orden";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
                return false;
            }
            while (!$result->EOF) {
                $vars[$result->fields[0]]=$result->fields[1];
                $result->MoveNext();
            }
        }
        $result->Close();
        return $vars;
    }

  function LlamaSolicitudesSuministroEst(){
    $this->SolicitudesSuministroEst();
    return true;
  }

  function ConsultaSolicitudesSuministrosEst(){
    list($dbconn) = GetDBconn();
        $query = "SELECT DISTINCT b.descripcion as estacion,a.solicitud_id,a.estacion_id,date(a.fecha_registro) as fecha
    FROM hc_solicitudes_suministros_estacion a,estaciones_enfermeria b,hc_solicitudes_suministros_estacion_detalle det
    WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
    a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND
    a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
    a.estacion_id=b.estacion_id AND a.solicitud_id=det.solicitud_id AND
    (det.sw_estado='0' OR det.sw_estado='1')";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
                    $vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
      }
      $result->Close();
    }
    return $vars;
  }

  function LlamaDetalleSolicitudSuministros(){
    $this->DetalleSolicitudSuministros($_REQUEST['estacion_id'],$_REQUEST['estacion']);
    return true;
  }

  function RegDetalleSolicitudSuministros($estacion_id){
    list($dbconn) = GetDBconn();
        $query = "SELECT a.solicitud_id,b.codigo_producto,c.descripcion,
    b.cantidad as cantidad_solicitada,
    (CASE WHEN b.cantidad_despachada IS NULL THEN 0 ELSE b.cantidad_despachada END) as cantidad_despachada,
    (CASE WHEN b.cantidad_despachada IS NOT NULL THEN (b.cantidad - b.cantidad_despachada) ELSE b.cantidad END) as cantidad_pendiente,
    b.consecutivo,d.existencia
    FROM hc_solicitudes_suministros_estacion a,hc_solicitudes_suministros_estacion_detalle b,inventarios_productos c,existencias_bodegas d
    WHERE a.estacion_id='".$estacion_id."' AND a.solicitud_id=b.solicitud_id AND
    (b.sw_estado='0' OR b.sw_estado='1') AND b.codigo_producto=c.codigo_producto AND
    c.codigo_producto=d.codigo_producto AND d.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
    d.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND
    d.bodega='".$_SESSION['BODEGAS']['BodegaId']."'
    ORDER BY a.solicitud_id,c.descripcion";

    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
                    $vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
      }
      $result->Close();
    }
    return $vars;
  }

  function GuardarSolicitudesSuministros(){
    list($dbconn) = GetDBconn();
    if($_REQUEST['ConsecutivoCancel'] && $_REQUEST['SolicitudCancel']){
      $motivosVector=$_REQUEST['MotivoCancel'];
      $motivosVector[$_REQUEST['SolicitudCancel']][$_REQUEST['ConsecutivoCancel']];
      if($motivosVector[$_REQUEST['SolicitudCancel']][$_REQUEST['ConsecutivoCancel']]==-1){
        $this->frmError["MensajeError"]="Especifique el Motivo de la Cancelacion";
        $this->DetalleSolicitudSuministros($_REQUEST['estacion_id'],$_REQUEST['estacion']);
        return true;
      }
      $query="UPDATE hc_solicitudes_suministros_estacion_detalle SET sw_estado='3' WHERE solicitud_id='".$_REQUEST['SolicitudCancel']."' AND consecutivo='".$_REQUEST['ConsecutivoCancel']."';";
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      $this->frmError["MensajeError"]="Solicitud Pendiente Cancelada";
      $this->DetalleSolicitudSuministros($_REQUEST['estacion_id'],$_REQUEST['estacion']);
      return true;
    }

    $Seleccion=$_REQUEST['Seleccion'];
    $Cantidades=$_REQUEST['Cantidades'];
    $Existencias=$_REQUEST['Existencias'];
    if(sizeof($Seleccion)<1){
      $this->frmError["MensajeError"]="Debe Realizar la Seleccion de los Productos para Despachar";
      $this->DetalleSolicitudSuministros($_REQUEST['estacion_id'],$_REQUEST['estacion']);
      return true;
    }
    foreach($Seleccion as $Solicitud=>$vector){
      foreach($vector as $consecutivo=>$valorPendiente){
        $this->frmError["MensajeError"]="Error en la Cantidad a despachar de alguno de los productos Seleccionados";
        if(empty($Cantidades[$Solicitud][$consecutivo]) || $Cantidades[$Solicitud][$consecutivo] < 0 || $Cantidades[$Solicitud][$consecutivo] > $valorPendiente || $Cantidades[$Solicitud][$consecutivo] > $Existencias[$Solicitud][$consecutivo]){
          $this->DetalleSolicitudSuministros($_REQUEST['estacion_id'],$_REQUEST['estacion']);
          return true;
        }
      }
    }
    foreach($Seleccion as $Solicitud=>$vector){
      foreach($vector as $consecutivo=>$valorPendiente){
        $query.="INSERT INTO hc_solicitudes_suministros_est_x_confirmar(
        consecutivo,cantidad,bodegas_doc_id,numeracion)
        VALUES('".$consecutivo."','".$Cantidades[$Solicitud][$consecutivo]."',NULL,NULL);";
        if($Cantidades[$Solicitud][$consecutivo] < $valorPendiente){
          $estado=1;
        }elseif($Cantidades[$Solicitud][$consecutivo] == $valorPendiente){
          $estado=2;
        }
        $query.="UPDATE hc_solicitudes_suministros_estacion_detalle SET sw_estado='$estado',cantidad_despachada=(coalesce(cantidad_despachada,0) + ".$Cantidades[$Solicitud][$consecutivo].") WHERE solicitud_id='$Solicitud' AND consecutivo='$consecutivo';";
      }
    }
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    $this->frmError["MensajeError"]="Despachos Realizados Correctamente";
    $_REQUEST['Seleccion']='';
    $_REQUEST['Cantidades']='';
    $this->DetalleSolicitudSuministros($_REQUEST['estacion_id'],$_REQUEST['estacion']);
    return true;
  }


  function MotivosCancelacionSuministros(){
    list($dbconn) = GetDBconn();
        $query = "SELECT motivo_id,descripcion
    FROM hc_solicitudes_suministros_motivos_cancelacion";
    $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
                    $vars[]=$result->GetRowAssoc($toUpper=false);
                    $result->MoveNext();
                }
      }
      $result->Close();
    }
    return $vars;
  }

  //Funcion que debe llamar la estacion de enfermeria
  function EgresoConfirmacionesSuministros(){
    $Confirmaciones=$_SESSION['SUMINISTRO_X_ESTACION']['CONFIRMACIONES'];
    if(sizeof($_SESSION['SUMINISTRO_X_ESTACION']['CONFIRMACIONES'])<0){
      $mensaje="No Selecciono Productos Para Transferir";
      $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
      return false;
    }
        /*ECHO $_SESSION['SUMINISTRO_X_ESTACION']['Empresa'];
        ECHO '-';
        ECHO $_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE'];
        ECHO '-';
        ECHO $_SESSION['SUMINISTRO_X_ESTACION']['BodegaE'];
        ECHO '-';
        ECHO $_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI'];
        ECHO '-';
        ECHO $_SESSION['SUMINISTRO_X_ESTACION']['BodegaI'];*/
    if(empty($_SESSION['SUMINISTRO_X_ESTACION']['Empresa'])||empty($_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE'])
    ||empty($_SESSION['SUMINISTRO_X_ESTACION']['BodegaE'])||empty($_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI'])
    ||empty($_SESSION['SUMINISTRO_X_ESTACION']['BodegaI'])){
      $mensaje="Las Variables estan Vacias Imporsible Realizar la Transaccion";
      $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
      return false;
    }
    IncludeLib("despacho_medicamentos");
    list($dbconn) = GetDBconn();
    //Documento de Egreso
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_traslado='1' AND
        empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE']."' AND bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
    if($result->RecordCount()<1){
            $mensaje="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Origen para Soportar la Transferencia";
      $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
      return false;
        }
        $concepto=$result->fields[0];
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                               numeracion,
                                                                                     fecha,
                                                                                     total_costo,
                                                                                     transaccion,
                                                                                     observacion,
                                                                                     usuario_id,
                                                                                     fecha_registro,
                                                                                     centro_utilidad_transferencia,
                                                                                     bodega_destino_transferencia)VALUES(
                                                                                     '$concepto',
                                                                                     '$numeracion',
                                                                                     '".date("Y-m-d")."',
                                                                                     '0',NULL,'',
                                                                                     '".UserGetUID()."',
                                                                                     '".date("Y-m-d H:i:s")."',
                                                                                     '".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI']."',
                                                                                     '".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaI']."')";

        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      for($i=0;$i<sizeof($Confirmaciones);$i++){
        $query="SELECT a.cantidad,b.codigo_producto,c.sw_control_fecha_vencimiento FROM hc_solicitudes_suministros_est_x_confirmar a,hc_solicitudes_suministros_estacion_detalle b,existencias_bodegas c WHERE a.confirmacion_id='".$Confirmaciones[$i]."' AND a.consecutivo=b.consecutivo AND
        b.codigo_producto=c.codigo_producto AND c.empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND c.centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE']."' AND c.bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE']."'";
        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->GuardarNumeroDocumento($commit=false);
          return false;
        }else{
          if($result->RecordCount()<1){
            $mensaje="Error al Consultar los Datos de las confirmaciones";
            $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }else{
            $vars=$result->GetRowAssoc($toUpper=false);
            $codigo_producto=$vars['codigo_producto'];
            $cantidad=$vars['cantidad'];
            $sw_control_fecha_vencimiento=$vars['sw_control_fecha_vencimiento'];
            $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
            $result = $dbconn->Execute($query);
            $consecutivo=$result->fields[0];
            $query="SELECT costo FROM inventarios WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."'";
            $result = $dbconn->Execute($query);
            $costo=$result->fields[0];
            $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                    codigo_producto,
                                                                    cantidad,
                                                                    total_costo,
                                                                    bodegas_doc_id,
                                                                    numeracion)VALUES(
                                                                    '$consecutivo',
                                                                    '".$codigo_producto."',
                                                                    '".$cantidad."',
                                                                    '$costo',
                                                                    '$concepto',
                                                                    '$numeracion')";

            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }else{
              if($sw_control_fecha_vencimiento=='1'){
                DescargarLotesBodega($_SESSION['SUMINISTRO_X_ESTACION']['Empresa'],$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE'],$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE'],$codigo_producto,$cantidad);
              }
              $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE']."' AND bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE']."'";
              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
              }else{
                if($result->RecordCount()>0){
                  $exis=$result->GetRowAssoc($toUpper=false);
                  $TotalExistencias=$exis['existencia']-$cantidad;
                  if($TotalExistencias<0){
                    $mensaje="La Transferencia No tuvo Exito, no hay Suficientes Existencias en Bodega para el Producto".' '.$ProductosDocumento[$i]['codigo_producto'];
                    $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                  }
                  $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE']."' AND bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE']."'";

                  $result = $dbconn->Execute($query);
                  if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                  }
                }else{
                  $mensaje="Error al Consultar las Existencias de los Productos";
                  $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
                  $this->GuardarNumeroDocumento($commit=false);
                  return false;
                }
              }
            }
          }
        }
      }
      $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);



      //DOCUMENTO DE INGRESO A LA BODEGA
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='I' AND sw_estado='1' AND sw_traslado='1' AND
            empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI']."' AND bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaI']."' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            if($result->RecordCount()<1){
                $mensaje="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Destino para Soportar la Transferencia";
        $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
        $this->GuardarNumeroDocumento($commit=false);
        return false;
            }
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                                  numeracion,
                                                                                        fecha,
                                                                                        total_costo,
                                                                                        transaccion,
                                                                                        observacion,
                                                                                        usuario_id,
                                                                                        fecha_registro,
                                                                                        centro_utilidad_transferencia,
                                                                                        bodega_destino_transferencia)VALUES(
                                                                                        '$concepto',
                                                                                        '$numeracion',
                                                                                        '".date("Y-m-d")."',
                                                                                        '0',NULL,'',
                                                                                        '".UserGetUID()."',
                                                                                        '".date("Y-m-d H:i:s")."',
                                                                                        '".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE']."',
                                                                                      '".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE']."')";

            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
        for($i=0;$i<sizeof($Confirmaciones);$i++){
          $query="SELECT a.cantidad,b.codigo_producto,c.sw_control_fecha_vencimiento FROM hc_solicitudes_suministros_est_x_confirmar a,hc_solicitudes_suministros_estacion_detalle b,existencias_bodegas c WHERE a.confirmacion_id='".$Confirmaciones[$i]."' AND a.consecutivo=b.consecutivo AND
          b.codigo_producto=c.codigo_producto AND c.empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND c.centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliE']."' AND c.bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaE']."'";
          $result=$dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }else{
            if($result->RecordCount()>0){
              $vars=$result->GetRowAssoc($toUpper=false);
            }else{
              $mensaje="Error al Consultar los Datos de las confirmaciones";
              $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }
          }
          $codigo_producto=$vars['codigo_producto'];
          $cantidad=$vars['cantidad'];
          $sw_control_fecha_vencimiento=$vars['sw_control_fecha_vencimiento'];
          $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
          $result = $dbconn->Execute($query);
          $consecutivo=$result->fields[0];
          $query="SELECT costo FROM inventarios WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."'";
          $result = $dbconn->Execute($query);
          $costo=$result->fields[0];
          $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                  codigo_producto,
                                                                  cantidad,
                                                                  total_costo,
                                                                  bodegas_doc_id,
                                                                  numeracion)VALUES(
                                                                  '$consecutivo',
                                                                  '".$codigo_producto."',
                                                                  '".$cantidad."',
                                                                  '$costo',
                                                                  '$concepto',
                                                                  '$numeracion')";

          $result=$dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }else{
            $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI']."' AND bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaI']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            $datos=$result->RecordCount();
                            if(!$datos){
                $mensaje="El Producto no Existe en la Bodega de la Estacion";
                $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
                $this->GuardarNumeroDocumento($commit=false);
                return false;
                            }else{
                $exis=$result->GetRowAssoc($toUpper=false);
                $TotalExistencias=$exis['existencia']+$cantidad;
                $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['SUMINISTRO_X_ESTACION']['Empresa']."' AND centro_utilidad='".$_SESSION['SUMINISTRO_X_ESTACION']['CentroUtiliI']."' AND bodega='".$_SESSION['SUMINISTRO_X_ESTACION']['BodegaI']."'";
                $result = $dbconn->Execute($query);

                if($dbconn->ErrorNo() != 0){
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $this->GuardarNumeroDocumento($commit=false);
                  return false;
                }else{
                  $query="UPDATE hc_solicitudes_suministros_est_x_confirmar SET bodegas_doc_id='$concepto',numeracion='$numeracion' WHERE confirmacion_id='".$Confirmaciones[$i]."'";

                  $result = $dbconn->Execute($query);
                  if($dbconn->ErrorNo() != 0){
                      $this->error = "Error al Cargar el Modulo";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      $this->GuardarNumeroDocumento($commit=false);
                      return false;
                  }
                }
              }
                        }
          }
        }
        $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
        $mensaje="Datos Guardados Satisfactoriamente";
        $_SESSION['SUMINISTRO_X_ESTACION']['MENSAJE']=$mensaje;
        $this->GuardarNumeroDocumento($commit=true);
        return true;
      }
    }
    return false;
  }

  function LlamaSolicitudesProductosResposables(){
    if($_REQUEST['Volver']){
      $this->MenuInventariosDespachos();
      return true;
    }
    if($_REQUEST['VolverLista']){
      UNSET($_SESSION['SOLICITUDES_BOD_RESPONSABLES']);
    }
    $this->SolicitudesProductosResposables($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
    return true;

  }


  function BusquedaSolicitudesResponsanble($solicitudBus,$estacionBus,$usuarioResBus,$EstadoBus,$FechaBus){

    list($dbconn) = GetDBconn();
    if($EstadoBus!=-1 && !empty($EstadoBus)){
      if($EstadoBus==1){
        $condicion=" AND a.sw_estado='1' AND (SELECT count(*) FROM inv_solicitudes_iym_responsable_d x WHERE a.inv_solicitudes_iym_id=x.inv_solicitudes_iym_id)<>(SELECT count(*) FROM inv_solicitudes_iym_responsable_d x WHERE a.inv_solicitudes_iym_id=x.inv_solicitudes_iym_id AND x.sw_estado='2')";
      }elseif($EstadoBus==2){
        $condicion=" AND (SELECT count(*) FROM inv_solicitudes_iym_responsable_d x WHERE a.inv_solicitudes_iym_id=x.inv_solicitudes_iym_id)=(SELECT count(*) FROM inv_solicitudes_iym_responsable_d x WHERE a.inv_solicitudes_iym_id=x.inv_solicitudes_iym_id AND x.sw_estado='2')";
      }else{
        $condicion=" AND a.sw_estado='2'";
      }
    }
    $query="SELECT a.*
    FROM (SELECT a.estacion_id,b.descripcion as nom_estacion,a.inv_solicitudes_iym_id,
    a.fecha_registro,a.usuario_id,a.responsable_solicitud,
    d.nombre as nom_responsable,
    (CASE WHEN
    (SELECT count(*) FROM inv_solicitudes_iym_responsable_d x WHERE a.inv_solicitudes_iym_id=x.inv_solicitudes_iym_id)=(SELECT count(*) FROM inv_solicitudes_iym_responsable_d x WHERE a.inv_solicitudes_iym_id=x.inv_solicitudes_iym_id AND (x.sw_estado='2' OR x.sw_estado = '4'))
    THEN '1'
    ELSE
    '0'
    END) as estado_modifi
    FROM inv_solicitudes_iym_responsable a,estaciones_enfermeria b,
    system_usuarios d
    WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
    a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND
    a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
    a.usuario_id='".UserGetUID()."' AND
    a.estacion_id=b.estacion_id AND
    a.responsable_solicitud=d.usuario_id
    $condicion) as a
    WHERE a.estado_modifi='0'";
    if($solicitudBus){
      $query.=" AND a.inv_solicitudes_iym_id='".$solicitudBus."'";
    }

    if($estacionBus!=-1 && !empty($estacionBus)){
      $query.=" AND a.estacion_id='".$estacionBus."'";
    }

    if($usuarioResBus!=-1 && !empty($usuarioResBus)){
      $query.=" AND a.responsable_solicitud='".$usuarioResBus."'";
    }

    if($FechaBus){
      (list($dia,$mes,$ano)=explode('/',$FechaBus));
      $query.=" AND date(a.fecha_registro)='".$ano."-".$mes."-".$dia."'";
    }
    $query.=" ORDER BY a.fecha_registro";

    if(empty($_REQUEST['conteo'])){
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $this->conteo=$result->RecordCount();
        }else{
            $this->conteo=$_REQUEST['conteo'];
        }
        $query.=" LIMIT " . $this->limit . " OFFSET ".$this->offset."";

    GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while($data = $result->FetchRow()){
                $vars[$data['estacion_id']][$data['nom_estacion']][$data['inv_solicitudes_iym_id']]=$data;
            }
    }
    return $vars;
  }

  function LlamaCreacionSolicitudResponsable(){
    if(!empty($_REQUEST['estacion']) && $_REQUEST['estacion']!=-1){
      $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION']=$_REQUEST['estacion'];
    }
    if(!empty($_REQUEST['usuarioRes']) && $_REQUEST['usuarioRes']!=-1){
      $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['RESPONSABLE']=$_REQUEST['usuarioRes'];
    }
    $cantidades=$_REQUEST['cantidad'];
    foreach($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'] as $codigo=>$Vect){
      foreach($Vect as $descrip=>$canti){
        $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'][$codigo][$descrip]=$cantidades[$codigo];
      }
    }
    //Validacion de datos
    if($_REQUEST['Guardar']){
      if(empty($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION'])||empty($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['RESPONSABLE'])){
        $this->frmError["MensajeError"]="Seleccione la Estacion y el Responsable";
        $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
        return true;
      }
      foreach($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'] as $codigo=>$Vect){
        foreach($Vect as $descrip=>$canti){
          if(empty($canti) || $canti<0 || $canti>$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['EXISTENCIAS_PRODUCTOS'][$codigo]){
            $this->frmError["MensajeError"]="Las Cantidades no pueden ser menores a 0 ni mayores a las existencias";
            $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
            return true;
          }
        }
      }
      if($this->GuardarSolicitudResponsable()==true){
        $this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
        $this->SolicitudesProductosResposables();
        return ntrue;
      }else{
        $this->frmError["MensajeError"]="Error al Guardar los Datos";
        $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
        return true;
      }
    }
    //fin validadicon
    if($_REQUEST['SeleccionProd']){
      $this->FormaBuscadorProductosBodega();
      return true;
    }
    $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
    return true;
  }

  function LlamaFormaBuscadorProductosBodega(){
    if($_REQUEST['volver']){
      $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
      return true;
    }
    $this->FormaBuscadorProductosBodega($_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
    return true;
  }

  function EstacionesBodega(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.estacion_id,b.descripcion
    FROM bodegas_estaciones a,estaciones_enfermeria b
    WHERE a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
    a.estacion_id=b.estacion_id ORDER BY a.sw_bodega_principal DESC";
     GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while($data = $result->FetchRow()){
                $vars[$data['estacion_id']]=$data;
            }
    }
    return $vars;
  }

  function UsuariosResponsablesSol($estacion){
    list($dbconn) = GetDBconn();
    $query="SELECT a.usuario_id,b.nombre
    FROM estaciones_enfermeria_usuarios a,system_usuarios b
    WHERE a.estacion_id='".$estacion."' AND a.usuario_id=b.usuario_id AND
    b.activo='1'
    ORDER BY b.nombre";
     GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      while($data = $result->FetchRow()){
                $vars[$data['usuario_id']]=$data;
            }
    }
    return $vars;
  }

  function InsertarProductoResponsable(){
    $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'][$_REQUEST['producto']][$_REQUEST['descripcionProd']]=0;
    $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['EXISTENCIAS_PRODUCTOS'][$_REQUEST['producto']]=$_REQUEST['existencias'];
    $this->FormaBuscadorProductosBodega($_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
    return true;
  }

  function BorrarProductoResponsable(){
    list($dbconn) = GetDBconn();
    if($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CONSECUTIVOS'][$_REQUEST['producto']]){
      $query = "DELETE FROM inv_solicitudes_iym_responsable_d WHERE consecutivo='".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CONSECUTIVOS'][$_REQUEST['producto']]."'";
      $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
    }

    unset($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'][$_REQUEST['producto']]);
    unset($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['EXISTENCIAS_PRODUCTOS'][$_REQUEST['producto']]);
    unset($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTADOS_PRODUCTOS'][$_REQUEST['producto']]);
    unset($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$_REQUEST['producto']]);
    unset($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CONSECUTIVOS'][$_REQUEST['producto']]);

    if($_REQUEST['destino']==1){
      $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
      return true;
    }
    $this->FormaBuscadorProductosBodega($_REQUEST['codigoProd'],$_REQUEST['descripcion'],$_REQUEST['grupo'],$_REQUEST['NomGrupo'],$_REQUEST['clasePr'],$_REQUEST['NomClase'],$_REQUEST['subclase'],$_REQUEST['NomSubClase']);
    return true;
  }

  function GuardarSolicitudResponsable(){
    list($dbconn) = GetDBconn();
    if($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ID']){
      $dbconn->BeginTrans();
      $query="UPDATE inv_solicitudes_iym_responsable
             SET estacion_id='".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION']."',
                 responsable_solicitud='".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['RESPONSABLE']."'
             WHERE inv_solicitudes_iym_id='".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ID']."'";

      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }else{
        $query="DELETE FROM inv_solicitudes_iym_responsable_d WHERE inv_solicitudes_iym_id='".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ID']."'";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
      }
      $inv_solicitudes_iym_id=$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ID'];
    }else{
      $dbconn->BeginTrans();
      $query="SELECT nextval('banco_sangre_bolsas_ingreso_bolsa_id_seq')";
      $result = $dbconn->Execute($query);
      $inv_solicitudes_iym_id=$result->fields[0];
      $query="INSERT INTO inv_solicitudes_iym_responsable(
              inv_solicitudes_iym_id,empresa_id,
              centro_utilidad,bodega,
              estacion_id,fecha_registro,
              usuario_id,sw_estado,
              responsable_solicitud)VALUES(
              $inv_solicitudes_iym_id,'".$_SESSION['BODEGAS']['Empresa']."',
              '".$_SESSION['BODEGAS']['CentroUtili']."','".$_SESSION['BODEGAS']['BodegaId']."',
              '".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION']."','".date("Y-m-d H:i:s")."',
              '".UserGetUID()."',1,
              '".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['RESPONSABLE']."')";

      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        $dbconn->RollbackTrans();
        return false;
      }
    }
    foreach($_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'] as $codigo=>$Vect){
      foreach($Vect as $descrip=>$canti){
        $query="INSERT INTO inv_solicitudes_iym_responsable_d(
                inv_solicitudes_iym_id,codigo_producto,cantidad)VALUES(
                $inv_solicitudes_iym_id,'".$codigo."','".$canti."')";

        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $dbconn->RollbackTrans();
          return false;
        }
      }
    }
    $dbconn->CommitTrans();
    unset($_SESSION['SOLICITUDES_BOD_RESPONSABLES']);
    return true;
  }

  function ModificacionSolicitudResponsable(){

    $vars=$this->DetalleSolicitudResponsable($_REQUEST['NoSolicitud']);
    if($vars){
      for($i=0;$i<sizeof($vars);$i++){
        if($i==0){
          $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTACION']=$vars[0]['estacion_id'];
          $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['RESPONSABLE']=$vars[0]['responsable_solicitud'];
          $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['SOLICITUD']=$vars[0]['inv_solicitudes_iym_id'];
        }
        $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['PRODUCTOS'][$vars[$i]['codigo_producto']][$vars[$i]['descripcion']]=$vars[$i]['cantidad'];
        $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['EXISTENCIAS_PRODUCTOS'][$vars[$i]['codigo_producto']]=$vars[$i]['existencia'];
        $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTADOS_PRODUCTOS'][$vars[$i]['codigo_producto']]=$vars[$i]['sw_estado'];
        $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CANTIDADES_AJUSTADAS'][$vars[$i]['codigo_producto']]=$vars[$i]['cantidad_ajustada'];
        $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CONSECUTIVOS'][$vars[$i]['codigo_producto']]=$vars[$i]['consecutivo'];
      }
      $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ID']=$_REQUEST['NoSolicitud'];
      $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
      return true;
    }
    $this->frmError["MensajeError"]="Error al intentar Acceder a los Datos de la Solicitud";
    $this->SolicitudesProductosResposables($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
    return true;
  }

  function DetalleSolicitudResponsable($NoSolicitud){
    list($dbconn) = GetDBconn();
    $query="SELECT a.inv_solicitudes_iym_id,a.estacion_id,a.responsable_solicitud,b.codigo_producto,c.descripcion,b.cantidad,b.cantidad_ajustada,d.existencia,e.descripcion as nom_estacion,
            date(a.fecha_registro) as fecha,f.nombre as nom_responsable,b.sw_estado,b.consecutivo
            FROM inv_solicitudes_iym_responsable a,inv_solicitudes_iym_responsable_d b,inventarios_productos c,existencias_bodegas d,
            estaciones_enfermeria e,system_usuarios f
            WHERE a.inv_solicitudes_iym_id='".$NoSolicitud."' AND a.inv_solicitudes_iym_id=b.inv_solicitudes_iym_id AND
            b.codigo_producto=c.codigo_producto AND a.empresa_id=d.empresa_id AND a.centro_utilidad=d.centro_utilidad AND
            a.bodega=d.bodega AND b.codigo_producto=d.codigo_producto AND a.estacion_id=e.estacion_id AND
            a.responsable_solicitud=f.usuario_id";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vars;
  }


  function liquidacionIyMResponsable(){
      IncludeLib("despacho_medicamentos");
        list($dbconn) = GetDBconn();
    $cuenta=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CUENTA'];
    $Plan=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['PLAN'];
    $Empresa=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['EMPRESA'];
    $CentroUtili=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CENTRO_UTILIDAD'];
    $BodegaId=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['BODEGA'];
    $departamento=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['DEPARTAMENTO'];
    $Servicio=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['SERVICIO'];
    $VectorSel=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CONSECUTIVOS'];
    $VectorCan=$_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['CANTIDADES'];
    if(empty($cuenta)||empty($Empresa)||empty($CentroUtili)||empty($BodegaId) || (sizeof($VectorSel) < 0)){
      $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error']='Error en las Variables de Entrada';
      return false;
    }
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
    empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId'  ORDER BY bodegas_doc_id";

    $result = $dbconn->Execute($query);
    $concepto=$result->fields[0];
    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
    $numeracion=$numeracion['numeracion'];
    $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');
    if($codigoAgrupamiento=='0'){
      $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega2';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
        }else{
      $i=1;
      $query = "SELECT a.inv_solicitudes_iym_id,b.consecutivo,b.codigo_producto,a.inv_solicitudes_iym_id,c.descripcion,d.nombre as usuario_bodega,date(a.fecha_registro) as fecha,b.cantidad,b.cantidad_ajustada
      FROM inv_solicitudes_iym_responsable a,inv_solicitudes_iym_responsable_d b,inventarios_productos c,system_usuarios d
      WHERE a.inv_solicitudes_iym_id=b.inv_solicitudes_iym_id AND b.sw_estado = '1' AND
      ( ";
      foreach($VectorSel as $consecutivo => $indice){
        if($i==sizeof($VectorSel)){
          $query.=" b.consecutivo = ".$consecutivo."";
        }else{
          $query.=" b.consecutivo = ".$consecutivo." OR ";
        }
        $i++;
      }
      $query.=" ) AND b.codigo_producto=c.codigo_producto AND a.usuario_id=d.usuario_id ORDER BY a.inv_solicitudes_iym_id";

      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() !=0 ){
        $this->error = "Error al Guardar en la Base de Datos";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        $datosCont=$result->RecordCount();
        if($datosCont){
          while(!$result->EOF){
            $varsPr[]=$result->GetRowAssoc($toUpper=false);
            $result->MoveNext();
          }
          $SolicitudAnt=-1;
          for($j=0;$j<sizeof($varsPr);$j++){
            $Cantidad=$VectorCan[$varsPr[$j]['consecutivo']];
            $codigoProducto=$varsPr[$j]['codigo_producto'];
            $CantidadDespachada=$varsPr[$j]['cantidad'];
            $CantidadAjustada=$varsPr[$j]['cantidad_ajustada'];
            $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
            $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
            $result=$dbconn->Execute($query);
            $Consecutivo=$result->fields[0];
            $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto);
            if($InsertarDocumentod!=1){
              $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }else{
              if($this->InsertarBodegasDocumentosdCober($Consecutivo,date("Y-m-d"),$cuenta,$codigoProducto,$Cantidad,0,$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD')==false){
                $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
                $this->GuardarNumeroDocumento($commit=false);
                return false;
              }else{
                $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
                $result = $dbconn->Execute($query);
                $Existencias=$result->fields[0];
                if(($Existencias-$Cantidad)<0){
                  $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
                  $this->GuardarNumeroDocumento($commit=false);
                  return false;
                }else{
                  $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto);
                  if($result->fields[1]=='1'){
                    DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
                  }
                  if(($CantidadAjustada+$Cantidad)>=$CantidadDespachada){
                    $query="UPDATE inv_solicitudes_iym_responsable_d SET sw_estado='2',cantidad_ajustada='".$CantidadDespachada."' WHERE consecutivo='".$varsPr[$j]['consecutivo']."'";
                  }else{
                    $query="UPDATE inv_solicitudes_iym_responsable_d SET cantidad_ajustada='".($CantidadAjustada+$Cantidad)."' WHERE consecutivo='".$varsPr[$j]['consecutivo']."'";
                  }
                  $result = $dbconn->Execute($query);
                  if($dbconn->ErrorNo() !=0 ){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                  }else{
                    $Solicitud=$varsPr[$j]['inv_solicitudes_iym_id'];
                    if($SolicitudAnt!=$Solicitud){
                      $query="INSERT INTO inv_solicitudes_iym_responsable_documentos(inv_solicitudes_iym_id,bodegas_doc_id,numeracion)VALUES('$Solicitud','$concepto','$numeracion')";
                      $result = $dbconn->Execute($query);
                      if($dbconn->ErrorNo() !=0 ){
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                      }
                      $SolicitudAnt=$Solicitud;
                    }
                  }
                }
              }
            }
          }
          $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
        }
      }
        }
    $_SESSION['ESTACION_ENFERMERIA_DESPACHOS_USUARIOS_INVENTARIOS']['Mensaje_Error']='Datos Guardados Satisfactoriamente';
    $this->GuardarNumeroDocumento($commit=true);
    return true;
        //fin por cada solicitud
    }

  function ConsultaSolicitudResponsable(){
    $this->FormaConsultaSolicitudResponsable($_REQUEST['NoSolicitud'],$_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
    return true;
  }

  function LlamaDevolucionSuministrosEstacion(){
    $this->DevolucionSuministrosEstacion();
    return true;
  }

  function ConsultaDevolucionesSuministrosEst(){

        $query = "SELECT d.estacion_id,d.bodega_solicita,a.confirmacion_id,b.solicitud_id,b.codigo_producto,c.descripcion,a.cantidad,est.descripcion as estacion,bod.descripcion as nom_bodega,
    exis.sw_control_fecha_vencimiento
    FROM hc_solicitudes_suministros_est_x_confirmar a,hc_solicitudes_suministros_estacion_detalle b,
    inventarios_productos c,hc_solicitudes_suministros_estacion d,estaciones_enfermeria est,existencias_bodegas exis,bodegas bod
    WHERE a.estado='2' AND a.consecutivo=b.consecutivo AND b.codigo_producto=c.codigo_producto AND
    b.solicitud_id=d.solicitud_id AND d.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND d.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND d.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND
    d.estacion_id=est.estacion_id AND d.empresa_id=exis.empresa_id AND d.centro_utilidad=exis.centro_utilidad AND d.bodega=exis.bodega AND b.codigo_producto=exis.codigo_producto AND
    d.empresa_id=bod.empresa_id AND d.centro_utilidad=bod.centro_utilidad AND d.bodega_solicita=bod.bodega";
    list($dbconn) = GetDBconn();
        GLOBAL $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      while($data = $result->FetchRow()){
        $datos[0][$data['bodega_solicita']][$data['confirmacion_id']]=$data;
        $datos[1][$data['bodega_solicita']]=$data['nom_bodega'];
      }
    }
    return $datos;
  }

  function GuardarDevolucionSuministrosEstacion(){

    $checkboxDevol=$_REQUEST['Seleccion'];
    if(sizeof($checkboxDevol)<1){
      $this->frmError["MensajeError"]="Debe Seleccionar Productos para la Devolucion";
      $this->DevolucionSuministrosEstacion();
      return true;
    }
    foreach($checkboxDevol as $confimacion=>$valor){
      (list($codigo_producto,$cantidad,$sw_control_fecha_vencimiento)=explode(',',$valor));
      foreach($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$confimacion] as $lote => $vector){
        foreach($vector as $fechaVence => $cantidadSuma){
          $cantidadSumTot+=$cantidadSuma;
        }
      }
      if($cantidadSumTot<$cantidad){
        $this->frmError["MensajeError"]="Error, Debe Registrar las fechas de vencimiento de los productos que las exigen";
        $this->DevolucionSuministrosEstacion();
        return true;
      }
    }
    IncludeLib("despacho_medicamentos");
    //Documento de Egreso
    list($dbconn) = GetDBconn();
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_traslado='1' AND
        empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_REQUEST['BodegaDestino']."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
    if($result->RecordCount()<1){
      $this->frmError["MensajeError"]="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Origen para Soportar la Transferencia";
      $this->DevolucionSuministrosEstacion();
      return true;
        }
        $concepto=$result->fields[0];
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                               numeracion,
                                                                                     fecha,
                                                                                     total_costo,
                                                                                     transaccion,
                                                                                     observacion,
                                                                                     usuario_id,
                                                                                     fecha_registro,
                                                                                     centro_utilidad_transferencia,
                                                                                     bodega_destino_transferencia)VALUES(
                                                                                     '$concepto',
                                                                                     '$numeracion',
                                                                                     '".date("Y-m-d")."',
                                                                                     '0',NULL,'',
                                                                                     '".UserGetUID()."',
                                                                                     '".date("Y-m-d H:i:s")."',
                                                                                     '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                     '".$_SESSION['BODEGAS']['BodegaId']."')";

        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      foreach($checkboxDevol as $confimacion=>$valor){
        (list($codigo_producto,$cantidad,$sw_control_fecha_vencimiento)=explode(',',$valor));
        $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
        $result = $dbconn->Execute($query);
        $consecutivo=$result->fields[0];
        $query="SELECT costo FROM inventarios WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
        $result = $dbconn->Execute($query);
        $costo=$result->fields[0];
        $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                codigo_producto,
                                                cantidad,
                                                total_costo,
                                                bodegas_doc_id,
                                                numeracion)VALUES(
                                                '$consecutivo',
                                                '".$codigo_producto."',
                                                '".$cantidad."',
                                                '$costo',
                                                '$concepto',
                                                '$numeracion')";

        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en la Base de Datos";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          $this->GuardarNumeroDocumento($commit=false);
          return false;
        }else{
          if($sw_control_fecha_vencimiento=='1'){
            DescargarLotesBodega($_SESSION['BODEGAS']['Empresa'],$_SESSION['BODEGAS']['CentroUtili'],$_REQUEST['BodegaDestino'],$codigo_producto,$cantidad);
          }
          $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_REQUEST['BodegaDestino']."'";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }else{
            if($result->RecordCount()>0){
              $exis=$result->GetRowAssoc($toUpper=false);
              $TotalExistencias=$exis['existencia']-$cantidad;
              if($TotalExistencias<0){
                $this->GuardarNumeroDocumento($commit=false);
                $this->frmError["MensajeError"]="La Transferencia No tuvo Exito, no hay Suficientes Existencias en Bodega para el Producto".' '.$codigo_producto;
                $this->DevolucionSuministrosEstacion();
                return true;
              }
              $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_REQUEST['BodegaDestino']."'";
              $result = $dbconn->Execute($query);
              if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
              }
            }
          }
        }
      }
      $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
      $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='I' AND sw_estado='1' AND sw_traslado='1' AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."' ORDER BY bodegas_doc_id";

            $result = $dbconn->Execute($query);
            if($result->RecordCount()<1){
        $this->GuardarNumeroDocumento($commit=false);
        $this->frmError["MensajeError"]="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Destino para Soportar la Transferencia";
        $this->DevolucionSuministrosEstacion();
        return true;
            }
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                                  numeracion,
                                                                                        fecha,
                                                                                        total_costo,
                                                                                        transaccion,
                                                                                        observacion,
                                                                                        usuario_id,
                                                                                        fecha_registro,
                                                                                        centro_utilidad_transferencia,
                                                                                        bodega_destino_transferencia)VALUES(
                                                                                        '$concepto',
                                                                                        '$numeracion',
                                                                                        '".date("Y-m-d")."',
                                                                                        '0',NULL,'',
                                                                                        '".UserGetUID()."',
                                                                                        '".date("Y-m-d H:i:s")."',
                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                      '".$_REQUEST['BodegaDestino']."')";

            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
        foreach($checkboxDevol as $confimacion=>$valor){
          (list($codigo_producto,$cantidad,$sw_control_fecha_vencimiento)=explode(',',$valor));
          $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
          $result = $dbconn->Execute($query);
          $consecutivo=$result->fields[0];
          $query="SELECT costo FROM inventarios WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
          $result = $dbconn->Execute($query);
          $costo=$result->fields[0];
          $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                  codigo_producto,
                                                                  cantidad,
                                                                  total_costo,
                                                                  bodegas_doc_id,
                                                                  numeracion)VALUES(
                                                                  '$consecutivo',
                                                                  '".$codigo_producto."',
                                                                  '".$cantidad."',
                                                                  '$costo',
                                                                  '$concepto',
                                                                  '$numeracion')";

          $result=$dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }else{
            if($sw_control_fecha_vencimiento==1){
              foreach($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$confimacion] as $lote => $vector){
                foreach($vector as $fechaVence => $cantidadSuma){
                  (list($dia,$mes,$ano)=explode('/',$fechaVence));
                  $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                              lote,
                                                                              saldo,
                                                                              cantidad,
                                                                              empresa_id,
                                                                              centro_utilidad,
                                                                              bodega,
                                                                              codigo_producto,
                                                                              consecutivo)
                                                                              VALUES(
                                                                              '".$ano."-".$mes."-".$dia."',
                                                                              '$lote',
                                                                              '0',
                                                                              $cantidadSuma,
                                                                              '".$_SESSION['BODEGAS']['Empresa']."',
                                                                              '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                              '".$_SESSION['BODEGAS']['BodegaId']."',
                                                                              '$codigo_producto',
                                                                              '$consecutivo')";

                  $result = $dbconn->Execute($query);
                  if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                  }
                }
              }
            }
            $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }else{
              $datos=$result->RecordCount();
              if(!$datos){
                $this->GuardarNumeroDocumento($commit=false);
                $this->frmError["MensajeError"]="El Producto no Existe en la Bodega de la Estacion";
                $this->DevolucionSuministrosEstacion();
                return true;
              }else{
                $exis=$result->GetRowAssoc($toUpper=false);
                $TotalExistencias=$exis['existencia']+$cantidad;
                $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$codigo_producto."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";

                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0){
                  $this->error = "Error al Cargar el Modulo";
                  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                  $this->GuardarNumeroDocumento($commit=false);
                  return false;
                }else{
                  $query="UPDATE hc_solicitudes_suministros_est_x_confirmar SET estado='3' WHERE confirmacion_id='".$confimacion."'";

                  $result = $dbconn->Execute($query);
                  if($dbconn->ErrorNo() != 0){
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                  }
                }
              }
            }
          }
        }

        $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
        unset($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE']);
        $this->GuardarNumeroDocumento($commit=true);
        $mensaje="Devolucion Realizada Correctamente";
        $titulo="DEVOLUCION SUMINISTROS";
        $accion=ModuloGetURL('app','InvBodegas','user','LlamaDevolucionSuministrosEstacion');
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        return true;
      }
    }
    return false;
  }

  function LlamaFechaVenceSuministroDevol(){
    $this->FechaVenceSuministroDevol($_REQUEST['NoConfirmacion'],$_REQUEST['cantidad'],$_REQUEST['codigo_producto'],$_REQUEST['descripcion']);
    return true;
  }

  function InsertarFechaVencimientoLoteSuministros(){
    if($_REQUEST['insertar']){
      foreach($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$_REQUEST['NoConfirmacion']] as $lote => $vector){
        foreach($vector as $fechaVence => $cantidadSuma){
          $cantidadSumTot+=$cantidadSuma;
        }
      }
      if(($cantidadSumTot+$_REQUEST['cantidadLote'])>$_REQUEST['cantidad']){
        $this->frmError["MensajeError"]="Error, las suma de las cantidades de los Lotes es mayor a la cantidad devuelta";
      }else{
        if($_REQUEST['fechaVencimiento']<date("d/m/Y")){
          $this->frmError["MensajeError"]="Error, La Fecha de vencimiento no puede ser menor a la actual";
        }else{
          if($_REQUEST['fechaVencimiento'] && $_REQUEST['lote'] && $_REQUEST['cantidadLote']){
            $_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$_REQUEST['NoConfirmacion']][$_REQUEST['lote']][$_REQUEST['fechaVencimiento']]=$_REQUEST['cantidadLote'];
          }
        }
      }
      $this->FechaVenceSuministroDevol($_REQUEST['NoConfirmacion'],$_REQUEST['cantidad'],$_REQUEST['codigo_producto'],$_REQUEST['descripcion']);
      return true;
    }
    $this->DevolucionSuministrosEstacion();
    return true;
  }

  function LlamaEliminarFechaVSuministros(){
    unset($_SESSION['SUMINISTROS_ESTACION_FECHAS_VENCE'][$_REQUEST['NoConfirmacion']][$_REQUEST['lote']]);
    $this->FechaVenceSuministroDevol($_REQUEST['NoConfirmacion'],$_REQUEST['cantidad'],$_REQUEST['codigo_producto'],$_REQUEST['descripcion']);
    return true;
  }

  /*Apartir de qui se realizaron tres funsiones que liquidan los medicamento e insumos para la liquidacion de la cirugia*/

  function liquidacionIyMCirugia(){
        $VectorDatos=$_SESSION['LIQUIDACION_QX']['VECTOR_DATOS'];
		
        if(!is_array($VectorDatos)){
            $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error, vector de Cantidades esta Vacio';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
        }
        UNSET($_SESSION['LIQUIDACION_QX']['VECTOR_DATOS']);
    IncludeLib("despacho_medicamentos");
    $Plan=$_SESSION['LIQUIDACION_QX']['PLAN'];
    $cuenta=$_SESSION['LIQUIDACION_QX']['CUENTA'];
    list($dbconn) = GetDBconn();
	
    $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.servicio
    FROM estacion_enfermeria_qx_departamentos a,departamentos b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.departamento=b.departamento";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $Datos=$result->GetRowAssoc($toUpper=false);
      $Empresa=$Datos['empresa_id'];
            $CentroUtili=$Datos['centro_utilidad'];
            $BodegaId=$Datos['bodega'];
      $Servicio=$Datos['servicio'];
    }
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
    empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id";
    $result = $dbconn->Execute($query);
    $concepto=$result->fields[0];
    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
    $numeracion=$numeracion['numeracion'];
    $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');

    if($codigoAgrupamiento!='0'){

      $query="	SELECT 	x.*,z.devolucion,(x.despacho - coalesce(z.devolucion,0)) as total,
						m.programacion_id, m.fecha_cirugia
				FROM (	SELECT 	b.codigo_producto,
								b.lote,
								b.fecha_vencimiento,
								sum(b.cantidad) as despacho,
						(	SELECT c.descripcion 
							FROM inventarios_productos c 
							WHERE b.codigo_producto=c.codigo_producto
						) as descripcion,

						(	SELECT 	h.existencia_actual as existencia
							FROM 	existencias_bodegas f,
									estacion_enfermeria_qx_departamentos g,
									existencias_bodegas_lote_fv h
							WHERE 	g.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' 
							AND		g.empresa_id=f.empresa_id 
							AND 	g.centro_utilidad=f.centro_utilidad 
							AND 	g.bodega=f.bodega 
							AND 	f.codigo_producto=b.codigo_producto
							AND		f.empresa_id = h.empresa_id
							AND     f.centro_utilidad = h.centro_utilidad
							AND     f.bodega = h.bodega
							AND		f.codigo_producto = h.codigo_producto
							AND	    h.lote = b.lote
							AND     h.fecha_vencimiento = b.fecha_vencimiento
						) as existencia

				FROM 	cuentas_liquidaciones_qx a,
						estacion_enfermeria_qx_iym b
				WHERE 	a.cuenta_liquidacion_qx_id='".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."' 
				AND 	a.programacion_id=b.programacion_id
				GROUP BY b.codigo_producto,b.lote,b.fecha_vencimiento
					) x
				LEFT JOIN (	SELECT 	e.codigo_producto,
									e.lote,
									e.fecha_vencimiento,
									sum(e.cantidad) as devolucion
							FROM 	cuentas_liquidaciones_qx d,
									estacion_enfermeria_qx_iym_devoluciones e
							WHERE d.cuenta_liquidacion_qx_id='".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."' 
							AND d.programacion_id=e.programacion_id
							GROUP BY e.codigo_producto,e.lote,e.fecha_vencimiento
						  ) z 
				ON (x.codigo_producto=z.codigo_producto AND x.lote = z.lote AND x.fecha_vencimiento = z.fecha_vencimiento),
				cuentas_liquidaciones_qx m
				WHERE m.cuenta_liquidacion_qx_id='".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."'
				ORDER BY x.codigo_producto, x.lote, x.fecha_vencimiento";
		
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }else{
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega3';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }
	
    $ProgramacionId=$vars[0]['programacion_id'];
    for($j=0;$j<sizeof($vars);$j++){
	
            if(array_key_exists($vars[$j]['codigo_producto'],$VectorDatos)){
				$Cantidad=$VectorDatos[$vars[$j]['codigo_producto']][$vars[$j]['lote']][$vars[$j]['fecha_vencimiento']];
                $CantidadComparar=$vars[$j]['total'];
                $codigoProducto=$vars[$j]['codigo_producto'];
				$lote=$vars[$j]['lote'];
				$fecha_vencimiento=$vars[$j]['fecha_vencimiento'];
                $departamento=$_SESSION['LIQUIDACION_QX']['Departamento'];
                //$FechaCargo=date("Y-m-d");
				$FechaCargo=$vars[$j]['fecha_cirugia'];
                $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
                $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result=$dbconn->Execute($query);
                $Consecutivo=$result->fields[0];
                $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto,$lote,$fecha_vencimiento);

                if($InsertarDocumentod==1){
                    if($this->InsertarBodegasDocumentosdCoberCirugia($Consecutivo,$FechaCargo,$cuenta,$codigoProducto,$Cantidad,0,$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD',$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION'])==false){
                        $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{

                        $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
                        $result = $dbconn->Execute($query);
                        $Existencias=$result->fields[0];
                        if(($Existencias-$Cantidad)<0){
                            $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;

                        }
                        $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto,array("fecha_vencimiento"=>$fecha_vencimiento,"lote"=>$lote));
                        if($result->fields[1]=='1'){
                            DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
                        }
                        //Ojo incremento el valor de las cantidades cargadas a la cuenta al documento de las devoluciones para poder hacer la resta
                        $query="INSERT INTO estacion_enfermeria_qx_iym_devoluciones(programacion_id,codigo_producto,cantidad,fecha_registro,usuario_id,estado,fecha_vencimiento,lote)
                        VALUES('".$ProgramacionId."','".$codigoProducto."','$Cantidad','".date('Y-m-d H:i:s')."','".UserGetUID()."','0','".$fecha_vencimiento."','".$lote."')";

                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            $query="SELECT a.cantidad_sol,b.cantidad_dev
                            FROM
                            (SELECT sum(a.cantidad)  as cantidad_sol
                            FROM estacion_enfermeria_qx_iym a
                            WHERE a.programacion_id='".$ProgramacionId."' AND a.codigo_producto='".$codigoProducto."'
							AND   a.fecha_vencimiento = '".$fecha_vencimiento."'
							AND   a.lote = '".$lote."'
                            ) a,
                            (SELECT sum(a.cantidad)  as cantidad_dev
                            FROM estacion_enfermeria_qx_iym_devoluciones a
                            WHERE a.programacion_id='".$ProgramacionId."' AND a.codigo_producto='".$codigoProducto."'
							AND   a.fecha_vencimiento = '".$fecha_vencimiento."'
							AND   a.lote = '".$lote."'
                            ) b";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }else{
                                $cantidadesEval=$result->GetRowAssoc($toUpper=false);
                                if($cantidadesEval['cantidad_sol']==$cantidadesEval['cantidad_dev']){
                                    $query="UPDATE estacion_enfermeria_qx_iym SET estado='1' WHERE programacion_id='".$ProgramacionId."' AND codigo_producto='".$codigoProducto."' AND fecha_vencimiento = '".$fecha_vencimiento."' AND lote = '".$lote."' ";
                                    $result = $dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0){
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->GuardarNumeroDocumento($commit=false);
                                        return false;
                                    }else{
                                        $query="UPDATE estacion_enfermeria_qx_iym_devoluciones SET estado='1' WHERE programacion_id='".$ProgramacionId."' AND codigo_producto='".$codigoProducto."' AND fecha_vencimiento = '".$fecha_vencimiento."' AND lote = '".$lote."' ";
                                        $result = $dbconn->Execute($query);
                                        if($dbconn->ErrorNo() != 0){
                                            $this->error = "Error al Cargar el Modulo";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $this->GuardarNumeroDocumento($commit=false);
                                            return false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
            }
    }
    $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
    $this->GuardarNumeroDocumento($commit=true);
    return true;
        //fin por cada solicitud
    }//fin functionUpdateX

    function liquidacionIyMCirugiaNOQX(){
        $VectorDatos=$_SESSION['LIQUIDACION_QX']['VECTOR_DATOS'];
        if(!is_array($VectorDatos)){
            $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error, vector de Cantidades esta Vacio';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
        }
        UNSET($_SESSION['LIQUIDACION_QX']['VECTOR_DATOS']);
    IncludeLib("despacho_medicamentos");
    $Plan=$_SESSION['LIQUIDACION_QX']['PLAN'];
    $cuenta=$_SESSION['LIQUIDACION_QX']['CUENTA'];
    list($dbconn) = GetDBconn();
    $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.servicio
    FROM estacion_enfermeria_qx_departamentos a,departamentos b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.departamento=b.departamento";

    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $Datos=$result->GetRowAssoc($toUpper=false);
      $Empresa=$Datos['empresa_id'];
            $CentroUtili=$Datos['centro_utilidad'];
            $BodegaId=$Datos['bodega'];
      $Servicio=$Datos['servicio'];
    }

    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
    empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id";

    $result = $dbconn->Execute($query);
    $concepto=$result->fields[0];
    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
    $numeracion=$numeracion['numeracion'];
    $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');

    if($codigoAgrupamiento!='0'){

      $query="SELECT x.*,z.devolucion,(x.despacho - coalesce(z.devolucion,0)) as total

      FROM (SELECT b.codigo_producto,sum(b.cantidad) as despacho,
      (SELECT c.descripcion FROM inventarios_productos c WHERE b.codigo_producto=c.codigo_producto) as descripcion,
      (SELECT f.existencia
      FROM existencias_bodegas f,estacion_enfermeria_qx_departamentos g
      WHERE g.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND
      g.empresa_id=f.empresa_id AND g.centro_utilidad=f.centro_utilidad AND g.bodega=f.bodega AND f.codigo_producto=b.codigo_producto) as existencia

      FROM estacion_enfermeria_qx_iym b
      WHERE b.programacion_id='".$_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS']."'
      GROUP BY b.codigo_producto) x
      LEFT JOIN (SELECT e.codigo_producto,sum(e.cantidad) as devolucion
      FROM estacion_enfermeria_qx_iym_devoluciones e
      WHERE e.programacion_id='".$_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS']."'
      GROUP BY e.codigo_producto) z ON (x.codigo_producto=z.codigo_producto)";

      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }else{
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega4';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }

    $ProgramacionId=$_SESSION['LIQUIDACION_QX']['PROGRAMACION_INSUMOS'];
    for($j=0;$j<sizeof($vars);$j++){
            if(array_key_exists($vars[$j]['codigo_producto'],$VectorDatos)){
                $Cantidad=$VectorDatos[$vars[$j]['codigo_producto']];
                $CantidadComparar=$vars[$j]['total'];
                $codigoProducto=$vars[$j]['codigo_producto'];
                $departamento=$_SESSION['LIQUIDACION_QX']['Departamento'];
                $FechaCargo=date("Y-m-d");
                $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
                $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result=$dbconn->Execute($query);
                $Consecutivo=$result->fields[0];
                $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto);

                if($InsertarDocumentod==1){
                    if($this->InsertarBodegasDocumentosdCober($Consecutivo,$FechaCargo,$cuenta,$codigoProducto,$Cantidad,0,$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD')==false){
                        $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{

                        $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
                        $result = $dbconn->Execute($query);
                        $Existencias=$result->fields[0];
                        if(($Existencias-$Cantidad)<0){
                            $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;

                        }
                        $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto);
                        if($result->fields[1]=='1'){
                            DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
                        }
                        //Ojo incremento el valor de las cantidades cargadas a la cuenta al documento de las devoluciones para poder hacer la resta
                        $query="INSERT INTO estacion_enfermeria_qx_iym_devoluciones(programacion_id,codigo_producto,cantidad,fecha_registro,usuario_id,estado)
                        VALUES('".$ProgramacionId."','".$codigoProducto."','$Cantidad','".date('Y-m-d H:i:s')."','".UserGetUID()."','0')";

                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            $query="SELECT a.cantidad_sol,b.cantidad_dev
                            FROM
                            (SELECT sum(a.cantidad)  as cantidad_sol
                            FROM estacion_enfermeria_qx_iym a
                            WHERE a.programacion_id='".$ProgramacionId."' AND a.codigo_producto='".$codigoProducto."'
                            ) a,
                            (SELECT sum(a.cantidad)  as cantidad_dev
                            FROM estacion_enfermeria_qx_iym_devoluciones a
                            WHERE a.programacion_id='".$ProgramacionId."' AND a.codigo_producto='".$codigoProducto."'
                            ) b";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                return false;
                            }else{
                                $cantidadesEval=$result->GetRowAssoc($toUpper=false);
                                if($cantidadesEval['cantidad_sol']==$cantidadesEval['cantidad_dev']){
                                    $query="UPDATE estacion_enfermeria_qx_iym SET estado='1' WHERE programacion_id='".$ProgramacionId."' AND codigo_producto='".$codigoProducto."'";
                                    $result = $dbconn->Execute($query);
                                    if($dbconn->ErrorNo() != 0){
                                        $this->error = "Error al Cargar el Modulo";
                                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                        $this->GuardarNumeroDocumento($commit=false);
                                        return false;
                                    }else{
                                        $query="UPDATE estacion_enfermeria_qx_iym_devoluciones SET estado='1' WHERE programacion_id='".$ProgramacionId."' AND codigo_producto='".$codigoProducto."'";
                                        $result = $dbconn->Execute($query);
                                        if($dbconn->ErrorNo() != 0){
                                            $this->error = "Error al Cargar el Modulo";
                                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                            $this->GuardarNumeroDocumento($commit=false);
                                            return false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }
            }
    }
    $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
    $this->GuardarNumeroDocumento($commit=true);
    return true;
        //fin por cada solicitud
    }



  function liquidacionIyMCirugiaGases(){
    $VectorDatos=$_SESSION['LIQUIDACION_QX']['VECTOR_DATOS'];
	
    if(!is_array($VectorDatos)){
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error, vector de Cantidades esta Vacio';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }
    UNSET($_SESSION['LIQUIDACION_QX']['VECTOR_DATOS']);
    IncludeLib("despacho_medicamentos");
    $Plan=$_SESSION['LIQUIDACION_QX']['PLAN'];
    $cuenta=$_SESSION['LIQUIDACION_QX']['CUENTA'];
    list($dbconn) = GetDBconn();
	//$dbconn->debug=true;
    $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.servicio
    FROM estacion_enfermeria_qx_departamentos a,departamentos b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.departamento=b.departamento";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $Datos=$result->GetRowAssoc($toUpper=false);
      $Empresa=$Datos['empresa_id'];
      $CentroUtili=$Datos['centro_utilidad'];
      $BodegaId=$Datos['bodega'];
      $Servicio=$Datos['servicio'];
    }
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
    empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id";
    $result = $dbconn->Execute($query);
    $concepto=$result->fields[0];
    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
    $numeracion=$numeracion['numeracion'];
    $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');

    if($codigoAgrupamiento!='0'){

      /*$query="SELECT e.programacion_id,a.suministro_gas_id,b.codigo_producto,b.descripcion,c.existencia,(a.tiempo_suministro * d.factor_conversion) as total
      FROM cuentas_liquidaciones_qx_gases_anestesicos a
      JOIN tipos_gases b ON (a.tipo_gas_id=b.tipo_gas_id)
      JOIN existencias_bodegas c ON (b.codigo_producto=c.codigo_producto AND c.empresa_id='$Empresa' AND c.centro_utilidad='$CentroUtili' AND c.bodega='$BodegaId')
      JOIN tipos_frecuencia_gases d ON(a.frecuencia_id=d.frecuencia_id AND a.tipo_suministro_id=d.tipo_suministro_id),
      cuentas_liquidaciones_qx e
      WHERE a.cuenta_liquidacion_qx_id='".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."'
      AND a.cuenta_liquidacion_qx_id=e.cuenta_liquidacion_qx_id AND a.transaccion_cuenta IS NULL";*/
	  
	  
	  $query="SELECT 	e.programacion_id,
						e.fecha_cirugia,
						a.suministro_gas_id,
						b.codigo_producto,
						b.descripcion,
						c.existencia,
						(a.tiempo_suministro * d.factor_conversion) as total,
						h.fecha_vencimiento,
						h.lote
				FROM 	cuentas_liquidaciones_qx_gases_anestesicos a
						JOIN tipos_gases b ON (a.tipo_gas_id=b.tipo_gas_id)
						JOIN existencias_bodegas c ON (b.codigo_producto=c.codigo_producto AND c.empresa_id='$Empresa' AND c.centro_utilidad='$CentroUtili' AND c.bodega='$BodegaId')
						JOIN tipos_frecuencia_gases d ON(a.frecuencia_id=d.frecuencia_id AND a.tipo_suministro_id=d.tipo_suministro_id),
						cuentas_liquidaciones_qx e,
						estacion_enfermeria_qx_departamentos f,
						existencias_bodegas g,
						existencias_bodegas_lote_fv h
				WHERE 	a.cuenta_liquidacion_qx_id='".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."'
				AND 	a.transaccion_cuenta IS NULL
				AND		a.cuenta_liquidacion_qx_id = e.cuenta_liquidacion_qx_id
				AND		e.departamento = f.departamento
				AND		f.empresa_id = g.empresa_id
				AND		f.centro_utilidad = g.centro_utilidad
				AND		f.bodega = g.bodega
				AND		b.codigo_producto = g.codigo_producto
				AND		g.empresa_id = h.empresa_id
				AND		g.centro_utilidad = h.centro_utilidad
				AND		g.bodega = h.bodega
				AND		g.codigo_producto = h.codigo_producto
				AND		h.existencia_actual > 0
				AND		h.estado = '1'";
				
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() != 0){
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }else{
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }else{
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega5';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }
    $ProgramacionId=$vars[0]['programacion_id'];
	
    for($j=0;$j<sizeof($vars);$j++){
		
      if(array_key_exists($vars[$j]['suministro_gas_id']."-".$vars[$j]['fecha_vencimiento']."-".$vars[$j]['lote'],$VectorDatos)){
        
		
		$Cantidad=$vars[$j]['total'];
        $codigoProducto=$vars[$j]['codigo_producto'];
		$fecha_vencimiento=$vars[$j]['fecha_vencimiento'];
		$lote=$vars[$j]['lote'];
        $departamento=$_SESSION['LIQUIDACION_QX']['Departamento'];
        //$FechaCargo=date("Y-m-d");
		$FechaCargo=$vars[$j]['fecha_cirugia'];
        $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
        $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
        $result=$dbconn->Execute($query);
        $Consecutivo=$result->fields[0];
        $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto, $lote, $fecha_vencimiento);

        if($InsertarDocumentod==1){
          $tran=$this->InsertarBodegasDocumentosdCoberCirugia($Consecutivo,$FechaCargo,$cuenta,$codigoProducto,$Cantidad,0,$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD',$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']);
          if($tran==false){
            $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
            $this->GuardarNumeroDocumento($commit=false);
            return false;
          }else{
            $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
            $result = $dbconn->Execute($query);
            $Existencias=$result->fields[0];
            if(($Existencias-$Cantidad)<0){
              $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
              $this->GuardarNumeroDocumento($commit=false);
              return false;

            }
            $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto,$vars[$j]);
            if($result->fields[1]=='1'){
              DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
            }
            $query="UPDATE cuentas_liquidaciones_qx_gases_anestesicos SET transaccion_cuenta=".$tran." WHERE suministro_gas_id='".$vars[$j]['suministro_gas_id']."'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
              $this->error = "Error al Cargar el Modulo";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }
          }
        }else{
          $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
          $this->GuardarNumeroDocumento($commit=false);
          return false;
        }
      }
    }

    $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
    $this->GuardarNumeroDocumento($commit=true);
    return true;
    //fin por cada solicitud
  }//fin functionUpdateX

    /**
* Funcion que inserta y calcula los valore del cargos del medicamento o insumo
* @return array
* @param string codigo unico que el identifica el registro de insercion del medicamento o insumo
*/
  function InsertarBodegasDocumentosdCoberCirugia($Consecutivo,$fechaCargo,$cuenta,$codigo,$cantidad,$precio,$codigoAgrupamiento,$planId,$Servicio,$Empresa,$CentroUtili,$departamento,$devolucion,$tipoCargo,$NoLIQUIDACION){

      IncludeLib("tarifario_cargos");
      if(empty($Consecutivo)){
      $Consecutivo=$_REQUEST['Consecutivo'];
        }
        list($dbconn) = GetDBconn();
		
        $varsCuenDet=LiquidarIyMQX($cuenta,$codigo,$cantidad,$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$departamento,$Empresa,$NoLIQUIDACION);        
        $autorizacion_int=$varsCuenDet['autorizacion_int'];
        if(!$autorizacion_int){$autorizacion_int1='NULL';}else{$autorizacion_int1="'$autorizacion_int'";}
        $autorizacion_ext=$varsCuenDet['autorizacion_ext'];
        if(!$autorizacion_ext){$autorizacion_ext1='NULL';}else{$autorizacion_ext1="'$autorizacion_ext'";}

        $query="SELECT nextval('cuentas_detalle_transaccion_seq')";
        $result=$dbconn->Execute($query);
        $Transaccion=$result->fields[0];
        if($devolucion=='1'){
          $valor_cargo=($varsCuenDet['valor_cargo']*-1);
            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
        }else{
      $valor_cargo=$varsCuenDet['valor_cargo'];
            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
            $valor_cubierto=$varsCuenDet['valor_cubierto'];
        }
        if(empty($tipoCargo)){
      $tipoCargo='IMD';
        }
        $query = "INSERT INTO cuentas_detalle(transaccion,empresa_id,centro_utilidad,
                                                                                    numerodecuenta,departamento,tarifario_id,
                                                                                    cargo,cantidad,precio,
                                                                                    porcentaje_descuento_empresa,valor_cargo,valor_nocubierto,
                                                                                    valor_cubierto,facturado,fecha_cargo,
                                                                                    usuario_id,fecha_registro,sw_liq_manual,
                                                                                    valor_descuento_empresa,valor_descuento_paciente,porcentaje_descuento_paciente,
                                                                                    servicio_cargo,autorizacion_int,autorizacion_ext,
                                                                                    porcentaje_gravamen,sw_cuota_paciente,sw_cuota_moderadora,
                                                                                    codigo_agrupamiento_id,consecutivo,cargo_cups,sw_cargue,
                                                                                    departamento_al_cargar)VALUES
                                                                                    ('$Transaccion','$Empresa','$CentroUtili',
                                                                                    $cuenta,'$departamento','SYS',
                                                                                    '$tipoCargo','$cantidad','".$varsCuenDet['precio_plan']."',
                                                                                    '".$varsCuenDet['porcentaje_descuento_empresa']."','".$valor_cargo."','".$valor_nocubierto."',
                                                                                    '".$valor_cubierto."','".$varsCuenDet['facturado']."','$fechaCargo',
                                                                                    '".UserGetUID()."','".date('Y-m-d H:i:s')."','0',
                                                                                    '".$varsCuenDet['valor_descuento_empresa']."','".$varsCuenDet['valor_descuento_paciente']."','".$varsCuenDet['porcentaje_descuento_paciente']."',
                                                                                    '$Servicio',$autorizacion_int1,$autorizacion_ext1,
                                                                                    '".$varsCuenDet['porcentaje_gravamen']."','".$varsCuenDet['sw_cuota_paciente']."','".$varsCuenDet['sw_cuota_moderadora']."',
                                                                                    '$codigoAgrupamiento','$Consecutivo',NULL,'3','$departamento')";
        
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
          //Falta Validar lo de la Cuenta estado
            $query = "SELECT a.transaccion,a.cargo,a.cantidad,a.departamento_al_cargar,c.cuenta_liquidacion_qx_id
            FROM cuentas_detalle a, bodegas_documentos_d b,cuentas_codigos_agrupamiento c
            WHERE a.numerodecuenta='$cuenta' AND a.consecutivo=b.consecutivo AND
            b.codigo_producto='$codigo' AND a.consecutivo <> '$Consecutivo' AND a.sw_liq_manual='0' AND
            a.codigo_agrupamiento_id=c.codigo_agrupamiento_id
	    AND c.cuenta_liquidacion_qx_id = $NoLIQUIDACION";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
        $datos=$result->RecordCount();
                if($datos){
                    $i=0;
                    while(!$result->EOF){
            $vars[$i]=$result->GetRowAssoc($toUpper=false);                                     
                        $varsCuenDet=LiquidarIyMQX($cuenta,$codigo,$vars[$i]['cantidad'],$descuento_manual_empresa=0,$descuento_manual_paciente=0,$aplicar_descuento_empresa=false,$aplicar_descuento_paciente=false,NULL,$planId,$autorizar=false,$vars[$i]['departamento_al_cargar'],$Empresa,$vars[$i]['cuenta_liquidacion_qx_id']);
                        if($vars[$i]['cargo']=='DIMD'){
              $valor_cargo=($varsCuenDet['valor_cargo']*-1);
                            $valor_nocubierto=($varsCuenDet['valor_nocubierto']*-1);
                            $valor_cubierto=($varsCuenDet['valor_cubierto']*-1);
                        }else{
              $valor_cargo=$varsCuenDet['valor_cargo'];
                            $valor_nocubierto=$varsCuenDet['valor_nocubierto'];
              $valor_cubierto=$varsCuenDet['valor_cubierto'];
                        }
                        $query = "UPDATE cuentas_detalle
                        SET precio='".$varsCuenDet['precio_plan']."',
                        porcentaje_descuento_empresa='".$varsCuenDet['porcentaje_descuento_empresa']."',
                        valor_cargo='".$valor_cargo."',valor_nocubierto='".$valor_nocubierto."',
                        valor_cubierto='".$valor_cubierto."',
                        facturado='".$varsCuenDet['facturado']."',valor_descuento_empresa='".$varsCuenDet['valor_descuento_empresa']."',
                        valor_descuento_paciente='".$varsCuenDet['valor_descuento_paciente']."',porcentaje_descuento_paciente='".$varsCuenDet['porcentaje_descuento_paciente']."',
                        porcentaje_gravamen='".$varsCuenDet['porcentaje_gravamen']."',sw_cuota_paciente='".$varsCuenDet['sw_cuota_paciente']."',
                        sw_cuota_moderadora='".$varsCuenDet['sw_cuota_moderadora']."'
                        WHERE transaccion='".$vars[$i]['transaccion']."'";
             
            $result1 = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }
                        $result->MoveNext();
                        $i++;
                    }
                }
            }
      return $Transaccion;
        }
        return false;
    }


  function liquidacionIyMCargosCuenta(){

    IncludeLib("despacho_medicamentos");
    $Plan=$_SESSION['LIQUIDACION_QX']['PLAN'];
    $cuenta=$_SESSION['LIQUIDACION_QX']['CUENTA'];
    list($dbconn) = GetDBconn();
	
    $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.servicio
    FROM estacion_enfermeria_qx_departamentos a,departamentos b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.departamento=b.departamento;";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $Datos=$result->GetRowAssoc($toUpper=false);
      $Empresa=$Datos['empresa_id'];
            $CentroUtili=$Datos['centro_utilidad'];
            $BodegaId=$Datos['bodega'];
      $Servicio=$Datos['servicio'];
    }
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='0' AND
    empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id;";
    $result = $dbconn->Execute($query);
    $concepto=$result->fields[0];
	
	$query1="SELECT fecha_cirugia FROM cuentas_liquidaciones_qx WHERE cuenta_liquidacion_qx_id = '".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."';";
    $result1 = $dbconn->Execute($query1);
    $Fecha_Cirugia=$result1->fields[0];
	
    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
    $numeracion=$numeracion['numeracion'];
    $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');
	
    if($codigoAgrupamiento=='0'){
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega6';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }
    foreach($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM'] as $codigoProducto=>$vector2){
		foreach($vector2 as $lote=>$vector1){
			foreach($vector1 as $fecha_vencimiento=>$vector){
			  foreach($vector as $descripcion=>$existencias){
				if(!empty($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento])){

				  $Cantidad=$_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento];
				  $departamento=$_SESSION['LIQUIDACION_QX']['Departamento'];
				  //$FechaCargo=date("Y-m-d");
				  $FechaCargo=$Fecha_Cirugia;
				  $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
				  $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
				  $result=$dbconn->Execute($query);
				  $Consecutivo=$result->fields[0];
				  $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto,$lote,$fecha_vencimiento);
				  if($InsertarDocumentod==1){                                            
					if($this->InsertarBodegasDocumentosdCoberCirugia($Consecutivo,$FechaCargo,$cuenta,$codigoProducto,$Cantidad,0,$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD',$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION'])==false){
					  $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
					  $this->GuardarNumeroDocumento($commit=false);
					  return false;
					}else{
					  $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
					  $result = $dbconn->Execute($query);
					  $Existencias=$result->fields[0];
					  if(($Existencias-$Cantidad)<0){
						$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
						$this->GuardarNumeroDocumento($commit=false);
						return false;

					  }
					  $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto, array("fecha_vencimiento"=>$fecha_vencimiento,"lote"=>$lote));
					  if($result->fields[1]=='1'){
						DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
					  }
					}
				  }else{
					$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
					$this->GuardarNumeroDocumento($commit=false);
					return false;
				  }
				}
			  }
			}
		}		
    }
    $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
    unset($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM']);
    unset($_SESSION['IYM_CUENTAS_QX']['PRODUCTOS_IYM_CANTIDADES_DES']);
    $this->GuardarNumeroDocumento($commit=true);
    return true;
        //fin por cada solicitud
    }//fin functionUpdateX

  /**
* Funcion inserta el documento de soporte de una devolcion
* @return boolean
*/
    function DevolucionliquidacionIyMCargosCuenta(){
    IncludeLib("despacho_medicamentos");
    $PlanId=$_SESSION['LIQUIDACION_QX']['PLAN'];
    $numeroDeCuenta=$_SESSION['LIQUIDACION_QX']['CUENTA'];
    list($dbconn) = GetDBconn();
        $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,b.servicio
    FROM estacion_enfermeria_qx_departamentos a,departamentos b
    WHERE a.departamento='".$_SESSION['LIQUIDACION_QX']['Departamento']."' AND a.departamento=b.departamento";
    $result = $dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $Datos=$result->GetRowAssoc($toUpper=false);
      $Empresa=$Datos['empresa_id'];
            $CentroUtili=$Datos['centro_utilidad'];
            $BodegaId=$Datos['bodega'];
      $Servicio=$Datos['servicio'];
    }
    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='I'
        AND empresa_id='".$Empresa."' AND centro_utilidad='".$CentroUtili."' AND bodega='".$BodegaId."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
		
	$query1="SELECT fecha_cirugia FROM cuentas_liquidaciones_qx WHERE cuenta_liquidacion_qx_id = '".$_SESSION['LIQUIDACION_QX']['NoLIQUIDACION']."';";
    $result1 = $dbconn->Execute($query1);
    $Fecha_Cirugia=$result1->fields[0];
	
        if(empty($concepto)){
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='NO EXISTE UN DOCUMENTO DE BODEGA CREADO PARA ESTE TIPO DE MOVIMIENTOS';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
        }
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','DIMD');
    if($codigoAgrupamiento=='0'){
      $_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega7';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }
    $departamento=$_SESSION['LIQUIDACION_QX']['Departamento'];
        foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['PRODUCTOS_IYM_CANTIDADES_DEV'] as $CodigoPro=>$vector2){
			foreach($vector2 as $lote=>$vector1){
				foreach($vector1 as $fecha_vencimiento=>$Cantidad){
						$costoProducto=$this->HallarCostoProducto($Empresa,$CodigoPro);
						$query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
						$result=$dbconn->Execute($query);
						$Consecutivo=$result->fields[0];
						$FechaCargo = $Fecha_Cirugia;
						$InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$CodigoPro,$Cantidad,$costoProducto,$lote,$fecha_vencimiento);
						if($InsertarDocumentod==1){
						  $this->InsertarBodegasDocumentosdCober($Consecutivo,$FechaCargo,$numeroDeCuenta,$CodigoPro,$Cantidad,$varsPr[$j]['precio'],$codigoAgrupamiento,$PlanId,$Servicio,$Empresa,$CentroUtili,$departamento,'1','DIMD');
							$query="SELECT existencia FROM existencias_bodegas WHERE empresa_id='".$Empresa."' AND centro_utilidad='".$CentroUtili."' AND bodega='".$BodegaId."' AND codigo_producto='$CodigoPro'";
							$result = $dbconn->Execute($query);
							$Existencias=$result->fields[0];
							$ModifExist=$this->ModificacionExistenciasResta($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$CodigoPro,$fecha_vencimiento,$lote);
					if($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$CodigoPro]){
					  foreach($_SESSION['IYM_CUENTAS_QX_DEVOL']['FECHAS_VENCE'][$CodigoPro] as  $lote=>$arreglo){
						(list($cantidades,$fecha)=explode('||//',$arreglo));
						(list($dia,$mes,$ano)=explode('/',$fecha));
						$query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
																					  lote,
																					  saldo,
																					  cantidad,
																					  empresa_id,
																					  centro_utilidad,
																					  bodega,
																					  codigo_producto,
																					  consecutivo)VALUES('".$ano."-".$mes."-".$dia."','".$lote."',0,'".$cantidades."','".$Empresa."','".$CentroUtili."','".$BodegaId."','".$CodigoPro."','".$Consecutivo."')";

						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0) {
						  $this->error = "Error al Guardar en la Base de Datos";
						  $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						  $this->GuardarNumeroDocumento($commit=false);
						  return false;
						}
					  }
					}

				  }else{
					$_SESSION['LIQUIDACION_QX']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
					$this->GuardarNumeroDocumento($commit=false);
					return false;
				  }
				}
			}	
		}
		$totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
		unset($_SESSION['IYM_CUENTAS_QX_DEVOL']);
		$this->GuardarNumeroDocumento($commit=true);
		return true;
    }
/*Fin funsiones*/

 /**
* Funcion inserta el documento de caja general para la caja de inventarios
* @return array
*/

    function CrearDocumentosBodegaCajaGeneral(){
    IncludeLib("despacho_medicamentos");
        list($dbconn) = GetDBconn();
        $query="SELECT a.empresa_id,a.centro_utilidad,a.bodega,a.codigo_producto,a.cantidad,a.rc_inventario_id
        FROM tmp_detalle_inventarios a
        WHERE a.tipo_id_tercero='".$_SESSION['CAJA']['TIPO_ID_TERCERO']."'
	AND a.tercero_id='".$_SESSION['CAJA']['TERCEROID']."'
	--AND a.rc_inventario_id = ".$_SESSION['CAJA']['rc_inventario_id']."
	";
        GLOBAL $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $result = $dbconn->Execute($query);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while($datos=$result->FetchRow()){
                $vector[$datos['empresa_id']][$datos['centro_utilidad']][$datos['bodega']][$datos['rc_inventario_id']]=$datos;
            }
        }
        $cont=0;
        foreach($vector as $Empresa=>$vector1){
            foreach($vector1 as $CentroUtilidad=>$vector2){
                foreach($vector2 as $Bodega=>$vector3){
                    $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='0' AND
                    empresa_id='$Empresa' AND centro_utilidad='$CentroUtilidad' AND bodega='$Bodega' ORDER BY bodegas_doc_id";
                    $result = $dbconn->Execute($query);
                    $concepto=$result->fields[0];
                    $numeracion=AsignarNumeroDocumentoDespacho($concepto);
                    $numeracion=$numeracion['numeracion'];
                    if($numeracion && $concepto){
			$query = "INSERT INTO bodegas_documentos(
					bodegas_doc_id,
					numeracion,
					fecha,
					total_costo,
					transaccion,
					observacion,
					usuario_id,
					fecha_registro)VALUES('$concepto','$numeracion','".date("Y-m-d")."','0',NULL,
					'','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            foreach($vector3 as $Id=>$datosT){
                                $Cantidad=$datosT['cantidad'];
                                $codigoProducto=$datosT['codigo_producto'];
                                $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
                                $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                                $result=$dbconn->Execute($query);
                                $Consecutivo=$result->fields[0];
                                $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto);
                                if($InsertarDocumentod==1){
                                    $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtilidad' AND bodega='$Bodega' AND codigo_producto='$codigoProducto'";
                                    $result = $dbconn->Execute($query);
                                    $Existencias=$result->fields[0];
                                    if(($Existencias-$Cantidad)<0){
                                        $_SESSION['CAJA_GENERAL']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
                                        $this->GuardarNumeroDocumento($commit=false);
                                        return false;
                                    }else{
                                        $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtilidad,$Bodega,$codigoProducto);
                                        if($result->fields[1]=='1'){
                                            DescargarLotesBodega($Empresa,$CentroUtilidad,$Bodega,$codigoProducto,$Cantidad);
                                        }
                                    }
                                }else{
                                    $_SESSION['CAJA_GENERAL']['RETORNO']['Mensaje_Error']='Error al Guardar en el Detalle del Documento';
                                    $this->GuardarNumeroDocumento($commit=false);
                                    return false;
                                }
                                $vectorFinal[$cont]['numero']=$numeracion;
                                $vectorFinal[$cont]['tipo_doc']=$concepto;
                                $vectorFinal[$cont]['consecutivo_bodega']=$Consecutivo;
                                $vectorFinal[$cont]['consecutivo_tmp']=$datosT['rc_inventario_id'];
                                $cont++;
                            }
                        }
                    }else{
                        $_SESSION['CAJA_GENERAL']['RETORNO']['Mensaje_Error']='Verifique que Existe el tipo de Documento de Bodega para realizar la Transaccion';
                        $this->GuardarNumeroDocumento($commit=false);
                return false;
                    }
                }
            }
        }
        $_SESSION['CAJA_GENERAL']['RETORNO']['VECTOR']=$vectorFinal;
        $this->GuardarNumeroDocumento($commit=true);
    return true;
    }

    function LlamaMostrarLotesPtosDocs(){
        $this->PtosTransferenciaBodegas($_REQUEST['consecutivo'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
      $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],
        $_REQUEST['ExisDest'],$_REQUEST['TipoReposicion'],$_REQUEST['codigoProductoProd'],$_REQUEST['descripcionProd'],$_REQUEST['CantidadProd'],$_REQUEST['consecutivoProd']);
        return true;
    }

    function TransferenciasBodegasDocs(){
      IncludeLib("despacho_medicamentos");

        $ProductosDocumento=$this->ConsultaProductosDocumentoTransaccion($_REQUEST['consecutivo']);
        if($ProductosDocumento){
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']=='1'){
          $datos=$this->FechasLotesProductos($_REQUEST['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                    $suma=$this->SumaFechasLotesProductos($_REQUEST['consecutivo'],$ProductosDocumento[$i]['codigo_producto']);
                    if(!$datos){
                      $this->frmError["MensajeError"]="Es obligatoria la fecha de vencimiento y el lote para el producto con codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
            $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
                        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
                        $_REQUEST['TipoReposicion']);
                        return true;
                    }elseif($suma['suma']<$ProductosDocumento[$i]['cantidad']){
            $this->frmError["MensajeError"]="La Suma de las Cantidades Insertadas es menor a la Cantidad Total del Producto con Codigo".' '.$ProductosDocumento[$i]['codigo_producto'];
            $this->PtosTransferenciaBodegas($_REQUEST['Documento'],$_REQUEST['conceptoInv'],$_REQUEST['FechaDocumento'],$_REQUEST['BodegaDest'],$_REQUEST['CentroUtilityDest'],
                        $_REQUEST['cantSolicitada'],$_REQUEST['costoProducto'],$_REQUEST['nombreProducto'],$_REQUEST['codigo'],$_REQUEST['unidadProducto'],$_REQUEST['ExisProducto'],$_REQUEST['ExisDest'],
                        $_REQUEST['TipoReposicion']);
                        return true;
                    }
                }
            }
        }
        list($dbconn) = GetDBconn();

        $concepto=$_REQUEST['conceptoInv'];
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                               numeracion,
                                                                                     fecha,
                                                                                     total_costo,
                                                                                     transaccion,
                                                                                     observacion,
                                                                                     usuario_id,
                                                                                     fecha_registro,
                                                                                     centro_utilidad_transferencia,
                                                                                     bodega_destino_transferencia)VALUES(
                                                                                     '$concepto',
                                                                                     '$numeracion',
                                                                                     '".date("Y-m-d")."',
                                                                                     '0',NULL,'',
                                                                                     '".UserGetUID()."',
                                                                                     '".date("Y-m-d H:i:s")."',
                                                                                     '".$_REQUEST['CentroUtilityDest']."',
                                                                                     '".$_REQUEST['BodegaDest']."')";

        $result=$dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Guardar en la Base de Datos";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            $this->GuardarNumeroDocumento($commit=false);
            return false;
        }else{
      for($i=0;$i<sizeof($ProductosDocumento);$i++){
              $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result = $dbconn->Execute($query);
                $consecutivo=$result->fields[0];
                $query="SELECT costo FROM inventarios WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                $result = $dbconn->Execute($query);
                $costo=$result->fields[0];
                $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                    codigo_producto,
                                                                                                    cantidad,
                                                                                                    total_costo,
                                                                                                    bodegas_doc_id,
                                                                                                    numeracion)VALUES(
                                                                                                    '$consecutivo',
                                                                                                    '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                    '".$ProductosDocumento[$i]['cantidad']."',
                                                                                                    '$costo',
                                                                                                    '$concepto',
                                                                                                    '$numeracion')";

                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }else{
          if($ProductosDocumento[$i]['sw_control_fecha_vencimiento']=='1'){
                    DescargarLotesBodega($_SESSION['BODEGAS']['Empresa'],$_SESSION['BODEGAS']['CentroUtili'],$_SESSION['BODEGAS']['BodegaId'],$ProductosDocumento[$i]['codigo_producto'],$ProductosDocumento[$i]['cantidad']);
                    }
          $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                    $result = $dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        $datos=$result->RecordCount();
                        if($datos){
                            $exis=$result->GetRowAssoc($toUpper=false);
                        }
            $TotalExistencias=$exis['existencia']-$ProductosDocumento[$i]['cantidad'];
                        if($TotalExistencias<0){
                            $mensaje="La Transferencia No tuvo Exito, no hay Suficientes Existencias en Bodega para el Producto".' '.$ProductosDocumento[$i]['codigo_producto'];
                            $titulo="TRANSFERENCIA ENTRE BODEGAS";
                            $accion=ModuloGetURL('app','InvBodegas','user','LlamaMostrarLotesPtosDocs',array("Documento"=>$_REQUEST['Documento'],"conceptoInv"=>$_REQUEST['conceptoInv'],
                            "FechaDocumento"=>$_REQUEST['FechaDocumento'],"BodegaDest"=>$_REQUEST['BodegaDest'],"CentroUtilityDest"=>$_REQUEST['CentroUtilityDest'],
                            "cantSolicitada"=>$_REQUEST['cantSolicitada'],"costoProducto"=>$_REQUEST['costoProducto'],"nombreProducto"=>$_REQUEST['nombreProducto'],
                            "codigo"=>$_REQUEST['codigo'],"unidadProducto"=>$_REQUEST['unidadProducto'],"ExisProducto"=>$_REQUEST['ExisProducto'],"ExisDest"=>$_REQUEST['ExisDest'],
                            "TipoReposicion"=>$_REQUEST['TipoReposicion']));
                            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                            $this->GuardarNumeroDocumento($commit=false);
                            return true;
                        }
                        $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";

                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }/*else{
                            $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='$CodigoPro' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND bodega='".$_SESSION['BODEGAS']['BodegaId']."'";
                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }else{
                              $Regs=$result->GetRowAssoc($toUpper=false);
                                if($Regs['existencia']==$TotalExistencias){
                                  return 1;
                                }
                            }
                        }*/
                    }
                }
            }
            $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
            $imprimir[0]=$concepto;
            $imprimir[1]=$numeracion;
            //DOCUMENTO DE INGRESO A LA BODEGA
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='I' AND sw_estado='1' AND sw_traslado='1' AND
            empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['CentroUtilityDest']."' AND bodega='".$_REQUEST['BodegaDest']."'
            ORDER BY bodegas_doc_id";

            $result = $dbconn->Execute($query);
            if($result->RecordCount()<1){
                $mensaje="Error al Realizar La Transferencia, No existe un Tipo de Documento en la Bodega Destino para Soportar la Repocision";
                $titulo="TRANSFERENCIA ENTRE BODEGAS";
                $accion=ModuloGetURL('app','InvBodegas','user','LlamaMostrarLotesPtosDocs',array("Documento"=>$_REQUEST['Documento'],"conceptoInv"=>$_REQUEST['conceptoInv'],
                "FechaDocumento"=>$_REQUEST['FechaDocumento'],"BodegaDest"=>$_REQUEST['BodegaDest'],"CentroUtilityDest"=>$_REQUEST['CentroUtilityDest'],
                "cantSolicitada"=>$_REQUEST['cantSolicitada'],"costoProducto"=>$_REQUEST['costoProducto'],"nombreProducto"=>$_REQUEST['nombreProducto'],
                "codigo"=>$_REQUEST['codigo'],"unidadProducto"=>$_REQUEST['unidadProducto'],"ExisProducto"=>$_REQUEST['ExisProducto'],"ExisDest"=>$_REQUEST['ExisDest'],
                "TipoReposicion"=>$_REQUEST['TipoReposicion']));
                $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
                $this->GuardarNumeroDocumento($commit=false);
                return true;
            }
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $query="INSERT INTO bodegas_documentos(bodegas_doc_id,
                                                  numeracion,
                                                                                        fecha,
                                                                                        total_costo,
                                                                                        transaccion,
                                                                                        observacion,
                                                                                        usuario_id,
                                                                                        fecha_registro,
                                                                                        centro_utilidad_transferencia,
                                                                                        bodega_destino_transferencia)VALUES(
                                                                                        '$concepto',
                                                                                        '$numeracion',
                                                                                        '".date("Y-m-d")."',
                                                                                        '0',NULL,'',
                                                                                        '".UserGetUID()."',
                                                                                        '".date("Y-m-d H:i:s")."',
                                                                                        '".$_SESSION['BODEGAS']['CentroUtili']."',
                                                                                      '".$_SESSION['BODEGAS']['BodegaId']."')";

            $result=$dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }else{
                for($i=0;$i<sizeof($ProductosDocumento);$i++){
                    $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                    $result = $dbconn->Execute($query);
                    $consecutivo=$result->fields[0];
                    $query="SELECT costo FROM inventarios WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."'";
                    $result = $dbconn->Execute($query);
                    $costo=$result->fields[0];
                    $query="INSERT INTO bodegas_documentos_d(consecutivo,
                                                                                                        codigo_producto,
                                                                                                        cantidad,
                                                                                                        total_costo,
                                                                                                        bodegas_doc_id,
                                                                                                        numeracion)VALUES(
                                                                                                        '$consecutivo',
                                                                                                        '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                        '".$ProductosDocumento[$i]['cantidad']."',
                                                                                                        '$costo',
                                                                                                        '$concepto',
                                                                                                        '$numeracion')";

                    $result=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        if($ProductosDocumento[$i]['sw_control_fecha_vencimiento_dest']=='1'){
                            $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        saldo,
                                                                                                                                                        cantidad,
                                                                                                                                                        empresa_id,
                                                                                                                                                        centro_utilidad,
                                                                                                                                                        bodega,
                                                                                                                                                        codigo_producto,
                                                                                                                                                        consecutivo
                                                                                                                                                        )SELECT
                                                                                                                                                        fecha_vencimiento,
                                                                                                                                                        lote,
                                                                                                                                                        '0',
                                                                                                                                                        cantidad,
                                                                                                                                                        '".$_SESSION['BODEGAS']['Empresa']."',
                                                                                                                                                        '".$_REQUEST['CentroUtilityDest']."',
                                                                                                                                                        '".$_REQUEST['BodegaDest']."',
                                                                                                                                                        '".$ProductosDocumento[$i]['codigo_producto']."',
                                                                                                                                                        '$consecutivo'
                                                                                                                                                        FROM inv_bodegas_transferencia_fvencimiento_lotes
                                                                                                                                                        WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";

                            $result=$dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0) {
                                $this->error = "Error al Guardar en la Base de Datos";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                        $query="SELECT existencia FROM existencias_bodegas WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['CentroUtilityDest']."' AND bodega='".$_REQUEST['BodegaDest']."'";
                        $result = $dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0){
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }else{
                            $datos=$result->RecordCount();
                            if($datos){
                                $exis=$result->GetRowAssoc($toUpper=false);
                            }
                            $TotalExistencias=$exis['existencia']+$ProductosDocumento[$i]['cantidad'];
                            $query="UPDATE existencias_bodegas SET existencia='$TotalExistencias' WHERE codigo_producto='".$ProductosDocumento[$i]['codigo_producto']."' AND empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND centro_utilidad='".$_REQUEST['CentroUtilityDest']."' AND bodega='".$_REQUEST['BodegaDest']."'";

                            $result = $dbconn->Execute($query);
                            if($dbconn->ErrorNo() != 0){
                                $this->error = "Error al Cargar el Modulo";
                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                $this->GuardarNumeroDocumento($commit=false);
                                return false;
                            }
                        }
                    }
                }
                $query="DELETE FROM inv_bodegas_transferencia_fvencimiento_lotes WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";

                $result=$dbconn->Execute($query);
                if($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    $this->GuardarNumeroDocumento($commit=false);
                    return false;
                }else{
                    $query="DELETE FROM inv_documento_transferencia_bodegas_d WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";

                    $result=$dbconn->Execute($query);
                    if($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al Guardar en la Base de Datos";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        $this->GuardarNumeroDocumento($commit=false);
                        return false;
                    }else{
                        $query="DELETE FROM inv_documento_transferencia_bodegas WHERE inv_documento_transferencia_id='".$_REQUEST['consecutivo']."'";

                        $result=$dbconn->Execute($query);
                        if($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Guardar en la Base de Datos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            $this->GuardarNumeroDocumento($commit=false);
                            return false;
                        }
                    }
                }
                $this->TotalizarDocumentoFinalBodega($numeracion,$concepto);
            }
            $this->GuardarNumeroDocumento($commit=true);
            $mensaje="La Transferencia Fue Exitosa";
            $titulo="TRANSFERENCIA ENTRE BODEGAS";
            $accion=ModuloGetURL('app','InvBodegas','user','MenuInventarios3');
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton,'',$imprimir);
            return true;
        }
        $mensaje="La Transferencia No tuvo Exito, Consulte al Administrador del Sistema";
        $titulo="TRANSFERENCIA ENTRE BODEGAS";
        $accion=ModuloGetURL('app','InvBodegas','user','LlamaMostrarLotesPtosDocs',array("Documento"=>$_REQUEST['Documento'],"conceptoInv"=>$_REQUEST['conceptoInv'],
        "FechaDocumento"=>$_REQUEST['FechaDocumento'],"BodegaDest"=>$_REQUEST['BodegaDest'],"CentroUtilityDest"=>$_REQUEST['CentroUtilityDest'],
        "cantSolicitada"=>$_REQUEST['cantSolicitada'],"costoProducto"=>$_REQUEST['costoProducto'],"nombreProducto"=>$_REQUEST['nombreProducto'],
        "codigo"=>$_REQUEST['codigo'],"unidadProducto"=>$_REQUEST['unidadProducto'],"ExisProducto"=>$_REQUEST['ExisProducto'],"ExisDest"=>$_REQUEST['ExisDest'],
        "TipoReposicion"=>$_REQUEST['TipoReposicion']));
        $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
        $this->GuardarNumeroDocumento($commit=false);
        return true;
    }
//Funcion para la Devolucion de medicamentos de la cuenta
/**
* Funcion inserta el documento de soporte de una devolcion
* @return boolean
*/
    function DevolucionIyMCargosCuenta(){
        IncludeLib("despacho_medicamentos");
        $PlanId=$_SESSION['FACTURACION_CUENTAS']['PLAN'];
        $numeroDeCuenta=$_SESSION['FACTURACION_CUENTAS']['CUENTA'];
        $Empresa=$_SESSION['FACTURACION_CUENTAS']['Empresa'];
        $CentroUtili=$_SESSION['FACTURACION_CUENTAS']['Centro_Utilidad'];
        $BodegaId=$_SESSION['FACTURACION_CUENTAS']['Bodega'];
        $codigoAgrupamientoCargue=$_SESSION['FACTURACION_CUENTAS']['codigoAgrupamientoCargue'];
        list($dbconn) = GetDBconn();
        $query="SELECT a.departamento,b.servicio
        FROM bodegas a,departamentos b
        WHERE a.empresa_id='".$Empresa."'
        AND a.centro_utilidad='".$CentroUtili."'
        AND a.bodega='".$BodegaId."'
        AND a.departamento=b.departamento";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          $Datos=$result->GetRowAssoc($toUpper=false);
          $Servicio=$Datos['servicio'];
          //$departamento=$Datos['departamento'];
        }
        
        $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE sw_transaccion_medicamentos='1' AND sw_estado='1' AND tipo_movimiento='I'
        AND empresa_id='".$Empresa."' AND centro_utilidad='".$CentroUtili."' AND bodega='".$BodegaId."' ORDER BY bodegas_doc_id";
        $result = $dbconn->Execute($query);
        $concepto=$result->fields[0];
        if(empty($concepto)){
      $_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error']='NO EXISTE UN DOCUMENTO DE BODEGA CREADO PARA ESTE TIPO DE MOVIMIENTOS';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
        }
        $numeracion=AsignarNumeroDocumentoDespacho($concepto);
        $numeracion=$numeracion['numeracion'];
        
        $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','DIMD');
    if($codigoAgrupamiento=='0'){
      $_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega8';
      $this->GuardarNumeroDocumento($commit=false);
      return false;
    }
   
        foreach($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV'] as $CodigoPro=>$Cantidad){
			
            $dat = explode('//||',$CodigoPro);
            $CodigoPro = $dat[0];
			$FechaVencimiento = $dat[1];
			$Lote = $dat[2];
            $departamento = $dat[3];
            //$ConsecutivoCargue=$_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CONSECUTIVOS_DEV'][$CodigoPro];
            $costoProducto=$this->HallarCostoProducto($Empresa,$CodigoPro);
            $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
            $result=$dbconn->Execute($query);
            $Consecutivo=$result->fields[0];
            $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$CodigoPro,$Cantidad,$costoProducto,$Lote,$FechaVencimiento);
            if($InsertarDocumentod==1){
              $Transaccion=$this->InsertarBodegasDocumentosdCober($Consecutivo,date('Y-m-d H:i:s'),$numeroDeCuenta,$CodigoPro,$Cantidad,$varsPr[$j]['precio'],$codigoAgrupamiento,$PlanId,$Servicio,$Empresa,$CentroUtili,$departamento,'1','DIMD');
                $query="SELECT existencia FROM existencias_bodegas WHERE empresa_id='".$Empresa."' AND centro_utilidad='".$CentroUtili."' AND bodega='".$BodegaId."' AND codigo_producto='$CodigoPro'";
                $result = $dbconn->Execute($query);
                $Existencias=$result->fields[0];
                $ModifExist=$this->ModificacionExistenciasResta($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$CodigoPro,$FechaVencimiento,$Lote);
        if($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$CodigoPro]){
          foreach($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE'][$CodigoPro] as  $lote=>$arreglo){
            (list($cantidades,$fecha)=explode('||//',$arreglo));
            (list($dia,$mes,$ano)=explode('/',$fecha));
            $query="INSERT INTO bodegas_documentos_d_fvencimiento_lotes(fecha_vencimiento,
                                                                          lote,
                                                                          saldo,
                                                                          cantidad,
                                                                          empresa_id,
                                                                          centro_utilidad,
                                                                          bodega,
                                                                          codigo_producto,
                                                                          consecutivo)VALUES('".$ano."-".$mes."-".$dia."','".$lote."',0,'".$cantidades."','".$Empresa."','".$CentroUtili."','".$BodegaId."','".$CodigoPro."','".$Consecutivo."')";

            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
              $this->error = "Error al Guardar en la Base de Datos";
              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
              $this->GuardarNumeroDocumento($commit=false);
              return false;
            }
          }
        }
      }else{
        $_SESSION['FACTURACION_CUENTAS']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
        $this->GuardarNumeroDocumento($commit=false);
        return false;
      }
            $query="INSERT INTO bodegas_documentos_devolucion_cuentas(transaccion_cargue_cuenta,
            transaccion_descargue_cuenta,usuario_id,fecha_registro,observaciones,motivo_devolucion_id,cantidad)
            VALUES(NULL,$Transaccion,'".UserGetUID()."','".date("Y-m-d H:i:s")."','DESCARGADO DESDE CUENTAS','".$_SESSION['FACTURACION_CUENTAS']['motivosDevolucion']."','$Cantidad')";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }
    }
    $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
    unset($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CANTIDADES_DEV']);
        unset($_SESSION['FACTURACION_CUENTAS']['PRODUCTOS_IYM_CONSECUTIVOS_DEV']);
        unset($_SESSION['FACTURACION_CUENTAS']['FECHAS_VENCE']);
    $this->GuardarNumeroDocumento($commit=true);
    return true;
    }

    function liquidarIYMOrdenServicio(){
      IncludeLib("despacho_medicamentos");
        list($dbconn) = GetDBconn();
    $cuenta=$_SESSION['OS_ATENCION']['CUENTA'];
      $query="SELECT empresa_id,centro_utilidad,bodega FROM tmp_cuenta_imd WHERE numerodecuenta='$cuenta' GROUP BY empresa_id,centro_utilidad,bodega,numerodecuenta";
        $result = $dbconn->Execute($query);
        if($dbconn->ErrorNo() != 0){
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($result->EOF){
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "La tabla 'tmp_cuenta_imd' esta vacia ";
                return false;
            }else{
        while(!$result->EOF){
          $vars[]=$result->GetRowAssoc($toUpper=false);
                  $result->MoveNext();
              }
            }
        }
        for($i=0;$i<sizeof($vars);$i++){
      $Empresa=$vars[$i]['empresa_id'];
            $CentroUtili=$vars[$i]['centro_utilidad'];
            $BodegaId=$vars[$i]['bodega'];
            $query="SELECT bodegas_doc_id FROM bodegas_doc_numeraciones WHERE tipo_movimiento='E' AND sw_estado='1' AND sw_transaccion_medicamentos='1' AND
            empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' ORDER BY bodegas_doc_id";
            $result = $dbconn->Execute($query);
            $concepto=$result->fields[0];
            $numeracion=AsignarNumeroDocumentoDespacho($concepto);
            $numeracion=$numeracion['numeracion'];
            $codigoAgrupamiento=$this->InsertarBodegasDocumentos($concepto,$numeracion,date("Y/m/d"),'','IMD');
            if($codigoAgrupamiento!='0'){
                $query="SELECT codigo_producto,cantidad,departamento,precio,fecha_cargo,plan_id,servicio_cargo FROM tmp_cuenta_imd WHERE numerodecuenta='$cuenta' AND empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId'";
                $result = $dbconn->Execute($query);
                if($dbconn->ErrorNo() !=0 ){
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }else{
                    $datosCont=$result->RecordCount();
                    if($datosCont){
                        while(!$result->EOF){
                            $varsPr[]=$result->GetRowAssoc($toUpper=false);
                            $result->MoveNext();
                        }
                    }
                }
            }else{
                $_SESSION['OS_ATENCION']['RETORNO']['Bodega']=false;
        $_SESSION['OS_ATENCION']['RETORNO']['Mensaje_Error']='Error en la Creacion de Los Documentos de Bodega, Consulte al Administrador de la Bodega9';
                $this->ReturnMetodoExterno($_SESSION['OS_ATENCION']['RETORNO']['contenedor'],$_SESSION['OS_ATENCION']['RETORNO']['modulo'],$_SESSION['OS_ATENCION']['RETORNO']['tipo'],$_SESSION['OS_ATENCION']['RETORNO']['metodo'],$_SESSION['OS_ATENCION']['RETORNO']['argurmentos']);
                return true;
            }
            for($j=0;$j<sizeof($varsPr);$j++){
              $Cantidad=$varsPr[$j]['cantidad'];
        $codigoProducto=$varsPr[$j]['codigo_producto'];
                $departamento=$varsPr[$j]['departamento'];
                $FechaCargo=$varsPr[$j]['fecha_cargo'];
                $Plan=$varsPr[$j]['plan_id'];
                $Servicio=$varsPr[$j]['servicio_cargo'];
        $costoProducto=$this->HallarCostoProducto($Empresa,$codigoProducto);
                $query="SELECT nextval('bodegas_documentos_d_consecutivo_seq')";
                $result=$dbconn->Execute($query);
                $Consecutivo=$result->fields[0];
                $InsertarDocumentod=$this->InsertarBodegasDocumentosd($Consecutivo,$numeracion,$concepto,$codigoProducto,$Cantidad,$costoProducto);
                if($InsertarDocumentod==1){
                  if($this->InsertarBodegasDocumentosdCober($Consecutivo,$varsPr[$j]['fecha_cargo'],$cuenta,$codigoProducto,$Cantidad,$varsPr[$j]['precio'],$codigoAgrupamiento,$Plan,$Servicio,$Empresa,$CentroUtili,$departamento,'0','IMD')==false){
                      $_SESSION['OS_ATENCION']['RETORNO']['Bodega']=false;
            $_SESSION['OS_ATENCION']['RETORNO']['Mensaje_Error']='Error al Guardar en la Cuenta del Paciente Verifique la Contratacion';
                        $this->ReturnMetodoExterno($_SESSION['OS_ATENCION']['RETORNO']['contenedor'],$_SESSION['OS_ATENCION']['RETORNO']['modulo'],$_SESSION['OS_ATENCION']['RETORNO']['tipo'],$_SESSION['OS_ATENCION']['RETORNO']['metodo'],$_SESSION['OS_ATENCION']['RETORNO']['argurmentos']);
                        return true;
                    }else{
            $query="SELECT existencia,sw_control_fecha_vencimiento FROM existencias_bodegas WHERE empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId' AND codigo_producto='$codigoProducto'";
                        $result = $dbconn->Execute($query);
                        $Existencias=$result->fields[0];
                        if(($Existencias-$Cantidad)<0){
                            $_SESSION['OS_ATENCION']['RETORNO']['Bodega']=false;
                            $_SESSION['OS_ATENCION']['RETORNO']['Mensaje_Error']='Imposible Realizar la Transaccion, La bodega no Cuenta con las Existencias Solicitadas Disponibles';
                            //$_SESSION['INVENTARIOS']['RETORNO']['Existencias']=true;
                            $this->ReturnMetodoExterno($_SESSION['OS_ATENCION']['RETORNO']['contenedor'],$_SESSION['OS_ATENCION']['RETORNO']['modulo'],$_SESSION['OS_ATENCION']['RETORNO']['tipo'],$_SESSION['OS_ATENCION']['RETORNO']['metodo'],$_SESSION['OS_ATENCION']['RETORNO']['argurmentos']);
                            return true;
                        }
                        $ModifExist=$this->ModificacionExistencias($Existencias,$Cantidad,$Empresa,$CentroUtili,$BodegaId,$codigoProducto);
                        if($result->fields[1]=='1'){
                            DescargarLotesBodega($Empresa,$CentroUtili,$BodegaId,$codigoProducto,$Cantidad);
                        }
                    }
                }else{
          $_SESSION['OS_ATENCION']['RETORNO']['Bodega']=false;
          $_SESSION['OS_ATENCION']['RETORNO']['Mensaje_Error']='Error en la Creacion del Detalle del Documento de Bodega, Consulte al Administrador de la Bodega';
                    $this->ReturnMetodoExterno($_SESSION['OS_ATENCION']['RETORNO']['contenedor'],$_SESSION['OS_ATENCION']['RETORNO']['modulo'],$_SESSION['OS_ATENCION']['RETORNO']['tipo'],$_SESSION['OS_ATENCION']['RETORNO']['metodo'],$_SESSION['OS_ATENCION']['RETORNO']['argurmentos']);
                    return true;
                }
            }
            $totalizCostoDoc=$this->TotalizarCostoDocumento($numeracion,$concepto);
            $query="DELETE FROM tmp_cuenta_imd WHERE numerodecuenta='$cuenta' AND empresa_id='$Empresa' AND centro_utilidad='$CentroUtili' AND bodega='$BodegaId'";
            $result = $dbconn->Execute($query);
            if($dbconn->ErrorNo() != 0){
                $this->error = "Error al Guardar en la Base de Datos";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                $this->GuardarNumeroDocumento($commit=false);
                return false;
            }
        }
        $this->GuardarNumeroDocumento($commit=true);
    $_SESSION['OS_ATENCION']['RETORNO']['Bodega']=true;
        $this->ReturnMetodoExterno($_SESSION['OS_ATENCION']['RETORNO']['contenedor'],$_SESSION['OS_ATENCION']['RETORNO']['modulo'],$_SESSION['OS_ATENCION']['RETORNO']['tipo'],$_SESSION['OS_ATENCION']['RETORNO']['metodo'],$_SESSION['OS_ATENCION']['RETORNO']['argurmentos']);
        return true;
        //fin por cada solicitud
    }//fin functionUpdateX

  function ConsultarSolicitudesSinConfimar(){
    
    $this->FrmConsultarSolicitudesSinConfimar($_REQUEST['departamento'],$_REQUEST['descripcionDpto']);
    return true;
  }

  /**
* Funcion que busca en la base de datos las solicitudes de medicamentos que no hansido despachadas
* @return array
* @param string codigo de la empresa a la que pertenece la solicitud
* @param string codigo del centro de utilidad al que pertenece la solicitud
* @param string codigo de la bodega donde fue relaizado la silicitud
*/
  function SolicitudesMedicamentosSinConfirmar($departamento){

    list($dbconn) = GetDBconn();
    //OJO dUVAN ME LA HOZO QUITAR PERO LA VOLVI A COLOCAR
    //j.cama,j.pieza
    //LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta)
    //LEFT JOIN camas j ON(f.cama=j.cama AND f.fecha_egreso is NULL)
    //FIN


    $query = "SELECT a.*,j.cama,j.pieza
              FROM
                  (SELECT DISTINCT c.departamento||'-'||c.descripcion as dpto,a.solicitud_id,a.estacion_id,
                  a.fecha_solicitud,a.ingreso,d.nombre as usuarioestacion,a.usuario_id,c.descripcion as deptoestacion,
                  e.rango,k.tipo_afiliado_nombre as tipo_afiliado_id,h.plan_descripcion,i.tipo_id_paciente,i.paciente_id,
                  l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
                  e.numerodecuenta,a.sw_estado

                  FROM hc_solicitudes_medicamentos a,estaciones_enfermeria b,departamentos c,system_usuarios d,cuentas e

                  ,planes h,ingresos i,tipos_afiliado k,pacientes l
                  WHERE a.bodega='".$_SESSION['BODEGAS']['BodegaId']."' AND a.empresa_id='".$_SESSION['BODEGAS']['Empresa']."' AND
                  a.centro_utilidad='".$_SESSION['BODEGAS']['CentroUtili']."' AND (a.sw_estado='1' OR a.sw_estado='6') AND a.estacion_id=b.estacion_id
                  AND b.departamento=c.departamento AND a.usuario_id=d.usuario_id AND a.ingreso=e.ingreso
                  AND a.ingreso=i.ingreso AND e.plan_id=h.plan_id AND k.tipo_afiliado_id=e.tipo_afiliado_id AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id
                  AND b.departamento='$departamento' AND i.estado='1') as a

              LEFT JOIN movimientos_habitacion f ON(a.numerodecuenta=f.numerodecuenta)
              LEFT JOIN camas j ON(f.cama=j.cama AND f.fecha_egreso is NULL)
              ORDER BY a.dpto,a.fecha_solicitud DESC";
    
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        while(!$result->EOF) {
          $vars[$result->fields[0]][$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    $result->Close();
    return $vars;
  }

  /**
* Funcion que llama la forma que visualiza el detalle de la solicitud realizada a la bodega despachada sin confirmar
* @return boolean
*/
  function DetalleSolicitudMedicamentoSinConfirmar(){
    $this->FrmAtenderSolicitudPacienteSinConfirmar($_REQUEST['SolicitudId'],$_REQUEST['Ingreso'],$_REQUEST['EstacionId'],
    $_REQUEST['NombreEstacion'],$_REQUEST['Fecha'],$_REQUEST['usuarioestacion'],$_REQUEST['nombrepac'],
    $_REQUEST['tipo_id_paciente'],$_REQUEST['paciente_id'],$_REQUEST['cama'],$_REQUEST['departamento'],$_REQUEST['descripcionDpto'],
    $_REQUEST['estado']);
    return true;
  }

  function ConfirmarDocumentoPaciente($Documento,$concepto){

    list($dbconn) = GetDBconn();
    $query = "SELECT i.tipo_id_paciente,i.paciente_id,
    l.primer_nombre||' '||l.segundo_nombre||' '||l.primer_apellido||' '||l.segundo_apellido as nombrepac,
    j.cama,j.pieza,e.numerodecuenta

    FROM hc_solicitudes_medicamentos a,ingresos i,pacientes l,cuentas e
    LEFT JOIN movimientos_habitacion f ON(e.numerodecuenta=f.numerodecuenta)
    LEFT JOIN camas j ON(f.cama=j.cama AND f.fecha_egreso is NULL)

    WHERE a.bodegas_doc_id='".$concepto."' AND a.numeracion='".$Documento."' AND
    a.ingreso=e.ingreso AND a.ingreso=i.ingreso AND i.tipo_id_paciente=l.tipo_id_paciente AND i.paciente_id=l.paciente_id";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        $vars=$result->GetRowAssoc($toUpper=false);
      }
    }
    $result->Close();
    return $vars;
  }

  function ConfirmarCancelacionDetalle(){
    list($dbconn) = GetDBconn();
    $query = "UPDATE inv_solicitudes_iym_responsable_d SET sw_estado='4' WHERE consecutivo='".$_SESSION['SOLICITUDES_BOD_RESPONSABLES']['CONSECUTIVOS'][$_REQUEST['producto']]."'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }
    $_SESSION['SOLICITUDES_BOD_RESPONSABLES']['ESTADOS_PRODUCTOS'][$_REQUEST['producto']]=4;
    $this->frmError["MensajeError"]="Confirmacion de Productos Recibidos Realizada";
    $this->CreacionSolicitudResponsable($_REQUEST['solicitudBus'],$_REQUEST['estacionBus'],$_REQUEST['usuarioResBus'],$_REQUEST['EstadoBus'],$_REQUEST['FechaBus']);
    return true;
  }
//fin
}//fin clase user
?>