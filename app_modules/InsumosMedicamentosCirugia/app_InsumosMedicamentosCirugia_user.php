 <?


 /**
 * $Id: app_InsumosMedicamentosCirugia_user.php,v 1.13 2006/09/19 19:20:04 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo que selecciona los medicamentos e insumos de la programacion de la Cirugia para el paciente que va a entrar o llega de la cirugia
 */



/**
*		class app_InsumosMedicamentosCirugia_user
*
*		Clase que maneja todas los metodos que llaman a las vistas relacionadas a la programacion de insumos y medicamentos para los pacientes de la cirugia
*		ubicadas en la clase hija html
*		ubicacion => app_modules/InsumosMedicamentosCirugia/app_InsumosMedicamentosCirugia_user.php
*		fecha creaci?n => 10/26/2005 10:35 am
*
*		@Author => Lorena Arag?n G.
*		@version =>
*		@package SIIS
*/
class app_InsumosMedicamentosCirugia_user extends classModulo
{
	var $frmError = array();


	/**
	*		app_InsumosMedicamentosCirugia_user()
	*
	*		constructor
	*
	*		@Author Lorena Arag?n G.
	*		@access Public
	*		@return bool
	*/
	function app_InsumosMedicamentosCirugia_user()//Constructor padre
	{
    $this->limit=GetLimitBrowser();
		//$this->limit=2;
    return true;
	}

	/**
	*		main
	*
	*		Esta funci?n permite seleccionar todas los departamentos que agrupa la estacion
	*		organizadas por su empresa, departamento
	*		a la cual pertenecen.
	*
	*		@Author Lorena Arag?n G.
	*		@access Public
	*		@return bool
	*/

	function main(){
		if(!$this->FrmLogueoEstacionQX()){
			$this->error = "No se puede cargar la vista";
			$this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"FrmLogueoEstacionQX\"";
			return false;
		}
		return true;
	}//FIN main

	/**
	*		GetLogueoEstacion
	*
	*		Esta funci?n obtiene los departamentos asociados a la Estacion de Cirugia.
	*
	*		@Lorena Arag?n G.
	*		@access Public
	*		@return bool
	*/
	function GetLogueoEstacion(){

	  $query =  "SELECT e.razon_social as descripcion1,
											cu.descripcion as descripcion2,
											uf.descripcion as descripcion3,
											c.descripcion as descripcion4,
											c.empresa_id,
											c.centro_utilidad,
											c.unidad_funcional,
											c.departamento,
                      bod.descripcion as nom_bodega,
                      bod.bodega
							FROM  userpermisos_estacion_enfermeria_qx a,
										estacion_enfermeria_qx_departamentos b,
										departamentos c,
										empresas e,
										centros_utilidad cu,
										unidades_funcionales uf,
                    bodegas bod
							WHERE a.usuario_id=".UserGetUID()." AND
										a.departamento=b.departamento AND
										c.departamento=b.departamento AND
										e.empresa_id=c.empresa_id AND
										cu.empresa_id=c.empresa_id AND
										cu.centro_utilidad=c.centro_utilidad AND
										uf.empresa_id=c.empresa_id AND
										uf.centro_utilidad=c.centro_utilidad AND
										uf.unidad_funcional=c.unidad_funcional AND
                    bod.empresa_id=b.empresa_id AND
                    bod.centro_utilidad=b.centro_utilidad AND
                    bod.bodega=b.bodega
							ORDER BY c.empresa_id, c.centro_utilidad, c.unidad_funcional, c.departamento";
		GLOBAL $ADODB_FETCH_MODE;
		list($dbconn) = GetDBconn();
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if (!$result) {
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}

		while ($data = $result->FetchRow()){
			$vectorDptos[$data['descripcion1']][$data['descripcion2']][$data['descripcion3']][$data['descripcion4']]=$data;
		}

		$mtz[0]="EMPRESA";
    $mtz[1]="CENTRO UTILIDAD";
    $mtz[2]="UNIDAD FUNCIONAL";
		$mtz[3]="DEPARTAMENTO";
    $url[0]='app';
    $url[1]='InsumosMedicamentosCirugia';
    $url[2]='user';
    $url[3]='LlamaMenu';
    $url[4]='datos_query';
		$Datos[0]=$mtz;
		$Datos[1]=$vectorDptos;
		$Datos[2]=$url;
		return $Datos;
	}

  /**
  *		LlamaMenu
  *
  *		Esta funci?n lista el menu de la estacion de enfermeria de Cirugia.
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@param array datos de la estacion
  *		@return bool
  */
  function LlamaMenu(){

    $_SESSION['IYM_PROGRAMACIONES_QX']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['CentroUtili']=$_REQUEST['datos_query']['centro_utilidad'];
		$_SESSION['IYM_PROGRAMACIONES_QX']['NombreCU']=$_REQUEST['datos_query']['descripcion2'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['UnidadFunc']=$_REQUEST['datos_query']['unidad_funcional'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['NombreFunc']=$_REQUEST['datos_query']['descripcion3'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['Departamento']=$_REQUEST['datos_query']['departamento'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['NombreDpto']=$_REQUEST['datos_query']['descripcion4'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['Bodega']=$_REQUEST['datos_query']['bodega'];
    $_SESSION['IYM_PROGRAMACIONES_QX']['NombreBod']=$_REQUEST['datos_query']['nom_bodega'];

    if(!$this->Menu()){
      $this->error = "No se puede cargar la vista";
      $this->mensajeDeError = "Ocurri? un error al intentar cargar la vista \"Menu\"";
      return false;
    }
    return true;
  }

  /**
  *		PacientesIngresadosEstacionQX
  *
  *		Esta funci?n Busca los pacientes ingresados a la estacion de cirugia.
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return bool
  */

  function PacientesIngresadosEstacionQX(){
    list($dbconn) = GetDBconn();
    $query = "SELECT a.*,ter.nombre_tercero as profesional
                   FROM 
                      (SELECT a.numero_registro,a.numerodecuenta,i.tipo_id_paciente,i.paciente_id,
                        c.primer_nombre||' '||c.segundo_nombre||' '||c.primer_apellido||' '||c.segundo_apellido as nombrepac,
                        a.fecha_ingreso,i.ingreso,d.programacion_id,e.quirofano_id,f.descripcion as nom_quirofano,e.hora_inicio,
                        e.hora_fin,d.tipo_id_cirujano,d.cirujano_id							
                        FROM estacion_enfermeria_qx_pacientes_ingresados a,cuentas b,ingresos i,
                        pacientes c,qx_programaciones d,qx_quirofanos_programacion e,qx_quirofanos f
    
                        WHERE a.departamento='".$_SESSION['IYM_PROGRAMACIONES_QX']['Departamento']."' AND (a.sw_estado='1' OR a.sw_estado='0') AND
                        a.numerodecuenta=b.numerodecuenta AND b.ingreso=i.ingreso AND
                        i.tipo_id_paciente=c.tipo_id_paciente AND i.paciente_id=c.paciente_id AND
                        a.programacion_id=d.programacion_id AND d.estado='1'AND
                        d.programacion_id=e.programacion_id AND e.qx_tipo_reserva_quirofano_id='3' AND
                        f.quirofano=e.quirofano_id) as a                        
                        LEFT JOIN terceros ter ON (a.tipo_id_cirujano=ter.tipo_id_tercero AND a.cirujano_id=ter.tercero_id)";
                        
    $result=$dbconn->execute($query);
    if($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      if($result->RecordCount()>0){
        while(!$result->EOF){
          $var[]=$result->GetRowAssoc($ToUpper = false);
          $result->MoveNext();
        }
      }
    }
    return $var;
  }


  /**
  *		ConsultaProgramacionQX
  *
  *		Funcion que Llama la funcion que consulta la programacion quirurgica
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function ConsultaProgramacionQX(){
    $this->FrmConsultaProgramacionQX($_REQUEST['programacionId'],$_REQUEST['action']);
    return true;
  }

  /**
  *		DatosProgramacionQX
  *
  *		Funcion que busca en los los dtos de la Programacion
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function DatosProgramacionQX($programacion){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.programacion_id,ter.nombre_tercero as cirujano,
    a.tipo_id_paciente,a.paciente_id,b.primer_nombre||' '||b.segundo_nombre||' '||b.primer_apellido||' '||b.segundo_apellido as nombre_pac,
    f.nombre_tercero as anestesiologo,f.tipo_id_tercero as tipo_id_tercero_aneste,f.tercero_id as tercero_id_aneste,
    g.nombre_tercero as instrumentador,g.tipo_id_tercero as tipo_id_tercero_instru,g.tercero_id as tercero_id_instru,
    h.nombre_tercero as circulante,h.tipo_id_tercero as tipo_id_tercero_circu ,h.tercero_id as tercero_id_circu,
    i.nombre_tercero as ayudante,i.tipo_id_tercero as tipo_id_tercero_ayud,i.tercero_id as tercero_id_ayud,
    c.hora_inicio,c.hora_fin,d.descripcion as quirofano,pl.plan_descripcion,terpl.nombre_tercero as tercero_plan,diag.diagnostico_nombre,
    c.qx_quirofano_programacion_id
    FROM qx_programaciones a
    LEFT JOIN terceros ter ON (a.cirujano_id=ter.tercero_id AND a.tipo_id_cirujano=ter.tipo_id_tercero)
    LEFT JOIN qx_anestesiologo_programacion e ON(a.programacion_id=e.programacion_id)
    LEFT JOIN terceros f ON(e.tipo_id_tercero=f.tipo_id_tercero AND e.tercero_id=f.tercero_id)
    LEFT JOIN terceros g ON(e.tipo_id_instrumentista=g.tipo_id_tercero AND e.instrumentista_id=g.tercero_id)
    LEFT JOIN terceros h ON(e.tipo_id_circulante=h.tipo_id_tercero AND e.circulante_id=h.tercero_id)
    LEFT JOIN terceros i ON(e.tipo_id_ayudante=i.tipo_id_tercero AND e.ayudante_id=i.tercero_id)
    LEFT JOIN planes pl ON(a.plan_id=pl.plan_id)
    LEFT JOIN terceros terpl ON(pl.tipo_tercero_id=terpl.tipo_id_tercero AND pl.tercero_id=terpl.tercero_id)
    LEFT JOIN diagnosticos diag ON(a.diagnostico_id=diag.diagnostico_id),
    pacientes b,qx_quirofanos_programacion c,qx_quirofanos d
    WHERE a.programacion_id='".$programacion."' AND a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id AND
    a.programacion_id=c.programacion_id AND c.quirofano_id=d.quirofano AND c.qx_tipo_reserva_quirofano_id='3'";

    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector[0]=$result->GetRowAssoc($toUpper=false);
        $query = "SELECT a.procedimiento_qx,b.descripcion,a.tipo_id_cirujano||' '||a.cirujano_id as cirujano_id,ter.nombre_tercero as cirujano,a.observaciones
        FROM qx_procedimientos_programacion a
        LEFT JOIN terceros ter ON (ter.tipo_id_tercero=a.tipo_id_cirujano AND ter.tercero_id=a.cirujano_id),
        cups b
        WHERE a.programacion_id='".$programacion."' AND a.procedimiento_qx=b.cargo
        ORDER BY a.tipo_id_cirujano,a.cirujano_id";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
        }else{
          $datos=$result->RecordCount();
          if($datos){
            while(!$result->EOF){
              $vector[1][]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
        }
      }
    }
    return $vector;
  }

  /**
  *		DatosEquiposProgramacionCirugia
  *
  *		Funcion que Consulta los equipos reservados para la cirugia
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */
  function DatosEquiposProgramacionCirugia($QuirofanoProgramacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.equipo_id,b.descripcion as nom_equipo
    FROM qx_equipos_programacion a,qx_equipos_moviles b
    WHERE a.qx_quirofano_programacion_id='".$QuirofanoProgramacionId."' AND a.equipo_id=b.equipo_id";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        while(!$result->EOF){
          $vector[]=$result->GetRowAssoc($toUpper=false);
          $result->MoveNext();
        }
      }
    }
    return $vector;
  }

  /**
  *		ConsultaProgramacionQX
  *
  *		Funcion que Consulta los datos de la cirugia - rips
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */
  function DatosCirugia($programacionId){

    list($dbconn) = GetDBconn();
    $query = "SELECT a.tipo_cirugia,b.descripcion as tipo,a.ambito_cirugia,c.descripcion as ambito,
    a.via_acceso,d.descripcion as via,a.finalidad_procedimiento_id,e.descripcion as finalidad
    FROM qx_datos_procedimientos_cirugias a
    LEFT JOIN qx_tipos_cirugia b ON (a.tipo_cirugia=b.tipo_cirugia_id)
    LEFT JOIN qx_ambitos_cirugias c ON(a.ambito_cirugia=c.ambito_cirugia_id)
    LEFT JOIN qx_vias_acceso d ON(a.via_acceso=d.via_acceso)
    LEFT JOIN qx_finalidades_procedimientos e ON(a.finalidad_procedimiento_id=e.finalidad_procedimiento_id)
    WHERE a.programacion_id='".$programacionId."'";
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
      $this->error = "Error al Cargar el Modulo";
      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
    }else{
      $datos=$result->RecordCount();
      if($datos){
        $vector=$result->GetRowAssoc($toUpper=false);
      }
    }
    return $vector;
  }

  /**
  *		DespachoCanastasCirugia
  *
  *		Funcion que llama la forma para el despacho de insumos y medicamentos para una cirugia
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function DespachoCanastasCirugia(){
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM']);
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES']);
    unset($_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV']);
    if($_REQUEST['programacionId']){
      $_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']=$_REQUEST['programacionId'];
    }
    $regs=$this->ConsultaProgramacionDespachosIyM();
    if(is_array($regs)){
      if($regs){
        for($i=0;$i<sizeof($regs);$i++){
          $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']][$regs[$i]['descripcion']]=$regs[$i]['existencia'];
          $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]=$regs[$i]['cantidad_des'];
          $regs1=$this->ConsultaProgramacionDevolucionesIyM($regs[$i]['codigo_producto'],$regs[$i]['lote'],$regs[$i]['fecha_vencimiento']);        
          $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]=$regs1['cantidad_dev'];
        }
      }
    }else{
      $regs=$this->ConsultaCanastasProgramadasIyM();       
      if($regs){
        for($i=0;$i<sizeof($regs);$i++){
          $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$regs[$i]['codigo_producto']][$regs[$i]['descripcion']]=$regs[$i]['existencia'];
          $cadena=$regs[$i]['codigo_producto'].'||//'.$regs[$i]['descripcion'];          
          $vec[urlencode($cadena)]=$regs[$i]['cantidad_des'];
          $_REQUEST['CantDespachadas']=$vec;
        }
      }    
    }    
    $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

  /**
  *		GuardarProductosCirugiaPac
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */


  function GuardarProductosCirugiaPac(){

    if($_REQUEST['Devolver']){
      $Seleccion=$_REQUEST['SeleccionDev'];
	  
      list($dbconn) = GetDBconn();
	  $dbconn->debug=true;
      foreach($Seleccion as $codigo=>$vector2){
		foreach($vector2 as $lote=>$vector1){
			foreach($vector1 as $fecha_vencimiento=>$cantidadDevol){
				if($cantidadDevol!=-1){
				  $query = "INSERT INTO estacion_enfermeria_qx_iym_devoluciones(
				  programacion_id,codigo_producto,cantidad,fecha_registro,usuario_id,fecha_vencimiento,lote)
				  VALUES('".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."','".$codigo."','".$cantidadDevol."','".date("Y-m-d H:i:s")."','".UserGetUID()."','".$fecha_vencimiento."','".$lote."');";
				  
				  $result = $dbconn->Execute($query);
				  if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				  }
				  $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$codigo][$lote][$fecha_vencimiento]+=$cantidadDevol;
				  $in=1;
				}
			}
		}
	  }
      if($in==1){
        $this->frmError["MensajeError"]="Devoluciones Realizadas";
      }else{
        $this->frmError["MensajeError"]="Seleccione las Cantidades a Devolver";
      }
      $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      return true;
    }

    if($_REQUEST['Despachar']){
      $cantidades=$_REQUEST['CantDespachadas'];
	  
      $centinela=0;
      foreach($cantidades as $producto=>$cantidad){
        if(!empty($cantidad)){
          $centinela=1;
          $vector[$producto]=$cantidad;
        }
      }
      if($centinela==0){
        $this->frmError["MensajeError"]="Seleccione las Cantidades A Despachar";
        $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      }else{
        $this->frmDespachoCantidades($vector,$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      }
      return true;
    }

    if($_REQUEST['SeleccionPaquete']){
      $this->BuscadorPaquetesInv('','',$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      return true;
    }

    if($_REQUEST['SeleccionProducto']){
      $this->BuscadorProductoInv('','',$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      return true;
    }

  }

  /**
  *		LlamaBuscadorProductoInv
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function LlamaBuscadorProductoInv(){
    if($_REQUEST['Volver']){
      $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      return true;
    }
    $this->BuscadorProductoInv($_REQUEST['codigoBus'],$_REQUEST['DescripcionBus'],$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

  /**
  *		ProductosInventariosBodega
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */


  function ProductosInventariosBodega($codigoBus,$DescripcionBus){
    $this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
    list($dbconn) = GetDBconn();
		$query = "SELECT a.codigo_producto,d.lote, d.fecha_vencimiento,b.descripcion,d.existencia_actual as existencia
    FROM existencias_bodegas a,inventarios_productos b,inv_grupos_inventarios c, existencias_bodegas_lote_fv d
    WHERE a.empresa_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['Empresa']."' AND a.centro_utilidad='".$_SESSION['IYM_PROGRAMACIONES_QX']['CentroUtili']."' AND a.bodega='".$_SESSION['IYM_PROGRAMACIONES_QX']['Bodega']."' AND
    a.codigo_producto=b.codigo_producto AND b.grupo_id=c.grupo_id AND
    (c.sw_medicamento='1' OR c.sw_insumos='1') 
	AND a.empresa_id = d.empresa_id
	AND a.centro_utilidad = d.centro_utilidad
	AND a.bodega = d.bodega
	AND a.codigo_producto = d.codigo_producto";
    if($codigoBus){
      $query.=" AND a.codigo_producto ILIKE '$codigoBus%'";
    }
    if($DescripcionBus){
      $query.=" AND b.descripcion ILIKE '%".strtoupper($DescripcionBus)."%'";
    }
    $query.=" ORDER BY b.descripcion";
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
		return $vars;
  }

  /**
  *		ProductosInventariosBodega
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function SeleccionProductoInventariosQx(){

    if(!$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]){
      $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']][$_REQUEST['descripcion']]=$_REQUEST['existencia'];
      $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]=0;
      $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$_REQUEST['producto']][$_REQUEST['lote']][$_REQUEST['fecha_vencimiento']]=0;
    }
    //$this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    $this->BuscadorProductoInv($_REQUEST['codigoBus'],$_REQUEST['DescripcionBus'],$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

/**
* Funcion que busca en los profesionales especialistas anestesiologos existentes en la base de datos
* @return array
*/
	function profesionalesEspecialistaCiculantes(){

		list($dbconn) = GetDBconn();
		$query = "SELECT a.*
		FROM (SELECT x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$_SESSION['IYM_PROGRAMACIONES_QX']['Departamento']."' AND
    z.especialidad=l.especialidad AND z.sw_circulante='1' AND x.tercero_id=l.tercero_id AND
    x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$_SESSION['IYM_PROGRAMACIONES_QX']['Departamento']."')='1'    
		UNION
		SELECT  x.tercero_id,c.nombre_tercero as nombre,x.tipo_id_tercero
    FROM profesionales x,profesionales_departamentos y,especialidades z,profesionales_especialidades l,terceros c
    WHERE x.tipo_id_tercero=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.departamento='".$_SESSION['IYM_PROGRAMACIONES_QX']['Departamento']."' AND
    z.especialidad=l.especialidad AND z.sw_instrumentista='1' AND x.tercero_id=l.tercero_id AND
    x.tipo_id_tercero=l.tipo_id_tercero  AND x.tercero_id=c.tercero_id AND x.tipo_id_tercero=c.tipo_id_tercero AND
    profesional_activo(c.tipo_id_tercero,c.tercero_id,'".$_SESSION['IYM_PROGRAMACIONES_QX']['Departamento']."')='1') as a
		ORDER BY a.nombre";
		
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->EOF){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla 'profesionales' esta vacia ";
				return false;
			}
			while (!$result->EOF) {
				$vars[]=$result->GetRowAssoc($toUpper=false);
			  $result->MoveNext();
			}
		}
		$result->Close();
 		return $vars;
	}

  /**
  *		GuardarDespachoIyM
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function GuardarDespachoIyM(){
    list($dbconn) = GetDBconn();
    if($_REQUEST['circulante']==-1){
      $this->frmError["MensajeError"]="Seleccione el Circulante";
      $this->frmDespachoCantidades($_REQUEST['cantidades'],$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      return true;
    }
    $cantidades=$_REQUEST['cantidades'];
    foreach($cantidades as $producto=>$cantidad){
      (list($codigoProducto,$lote,$fecha_vencimiento,$descripcion)=explode('||//',urldecode($producto)));
      (list($tipoIdTercero,$TerceroId)=explode('/',$_REQUEST['circulante']));
      $query="INSERT INTO estacion_enfermeria_qx_iym(
            codigo_producto,cantidad,tipo_id_tercero,tercero_id,observaciones,fecha_registro,usuario_id,programacion_id,paquete_insumos_id,fecha_vencimiento,lote)
            VALUES('".$codigoProducto."','".$cantidad."','".$tipoIdTercero."','".$TerceroId."',
            '".$_REQUEST['observacion']."','".date("Y-m-d H:i:s")."','".UserGetUID()."','".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."',NULL, '".$fecha_vencimiento."', '".$lote."')";
      
	  $result = $dbconn->Execute($query);
      if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Cargar el Modulo";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
      }
      $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$codigoProducto][$lote][$fecha_vencimiento]+=$cantidad;
    }
    $this->frmError["MensajeError"]="Datos Guardados Satisfactoriamente";
    $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

  /**
  *		ConsultarRegistrosDespachos
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function ConsultarRegistrosDespachos(){
    $this->FrmConsultarRegistrosDespachos(urldecode($_REQUEST['Producto']),$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

  /**
  *		ConsultaDespachosIyM
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */


  function ConsultaDespachosIyM($codigoProducto,$lote,$fecha_vencimiento){
    list($dbconn) = GetDBconn();
	
    $query="SELECT a.cantidad,a.tipo_id_tercero,a.tercero_id,
    a.observaciones,a.fecha_registro,a.usuario_id,ter.nombre_tercero,
    b.nombre as nombre_usuario
    FROM estacion_enfermeria_qx_iym a,terceros ter,system_usuarios b
    WHERE a.codigo_producto='".$codigoProducto."' AND a.programacion_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."' AND
    a.tipo_id_tercero=ter.tipo_id_tercero AND a.tercero_id=ter.tercero_id AND
    a.usuario_id=b.usuario_id AND a.estado='0' AND
	a.fecha_vencimiento = '".$fecha_vencimiento."' AND
	a.lote = '".$lote."' ";
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
        $result->Close();
      }
    }
    return $vars;
  }

  /**
  *		ConsultaProgramacionDespachosIyM
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */


  function ConsultaProgramacionDespachosIyM(){
    list($dbconn) = GetDBconn();
	
    $query="SELECT a.codigo_producto,a.lote,a.fecha_vencimiento,sum(a.cantidad) as cantidad_des,b.existencia,
    (SELECT b.descripcion FROM inventarios_productos b WHERE a.codigo_producto=b.codigo_producto) as descripcion
    FROM estacion_enfermeria_qx_iym a,existencias_bodegas b
    WHERE a.programacion_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."' AND a.estado='0' AND
    a.codigo_producto=b.codigo_producto AND b.empresa_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['Empresa']."' AND b.centro_utilidad='".$_SESSION['IYM_PROGRAMACIONES_QX']['CentroUtili']."' AND b.bodega='".$_SESSION['IYM_PROGRAMACIONES_QX']['Bodega']."'
    GROUP BY a.codigo_producto,a.lote,a.fecha_vencimiento,b.existencia
	ORDER BY a.codigo_producto,a.lote,a.fecha_vencimiento";
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
        $result->Close();
      }
    }
    return $vars;
  }
  
  /**
  *   ConsultaProgramacionDespachosIyM
  *
  *   Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *   @Author Lorena Arag?n G.
  *   @access Public
  *   @return boolean
  */


  function ConsultaCanastasProgramadasIyM(){
    list($dbconn) = GetDBconn();
    $query="SELECT a.codigo_producto,sum(a.cantidad)as cantidad_des,c.existencia,
                (SELECT b.descripcion FROM inventarios_productos b WHERE a.codigo_producto=b.codigo_producto) as descripcion
                FROM (SELECT a1.codigo_producto,(a.cantidad * a1.cantidad) as cantidad
                    FROM qx_programacion_paquetes a,qx_paquetes_contiene_insumos a1
                    WHERE a.programacion_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."'
                    AND a.paquete_insumos_id=a1.paquete_insumos_id)as a,existencias_bodegas c
                WHERE a.codigo_producto=c.codigo_producto AND c.empresa_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['Empresa']."' AND c.centro_utilidad='".$_SESSION['IYM_PROGRAMACIONES_QX']['CentroUtili']."' AND c.bodega='".$_SESSION['IYM_PROGRAMACIONES_QX']['Bodega']."'     
                GROUP BY a.codigo_producto,c.existencia";
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
        $result->Close();
      }
    }
    return $vars;
  }

  /**
  *		ConsultaProgramacionDespachosIyM
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */


  function ConsultaProgramacionDevolucionesIyM($codigoProducto,$lote,$fecha_vencimiento){
    list($dbconn) = GetDBconn();
    $query="SELECT sum(a.cantidad) as cantidad_dev
    FROM estacion_enfermeria_qx_iym_devoluciones a
    WHERE a.programacion_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."' AND
    a.codigo_producto='".$codigoProducto."' AND a.fecha_vencimiento='".$fecha_vencimiento."' 
	AND a.lote='".$lote."' AND a.estado='0'";
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
  *   ConsultaProgramacionDespachosIyM
  *
  *   Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *   @Author Lorena Arag?n G.
  *   @access Public
  *   @return boolean
  */


  function ConsultaProgramacionSuministrosIyM($codigoProducto){
    list($dbconn) = GetDBconn();
	
    $query="SELECT sum(a.cantidad_suministrada) as cantidad_suministro
    FROM  estacion_enfermeria_qx_iym_suministrados a
    WHERE a.programacion_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."' AND
    a.codigo_producto='".$codigoProducto."'";
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
  *		PaquetesInventariosBodega
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function PaquetesInventariosBodega($codigoBus,$DescripcionBus){


    list($dbconn) = GetDBconn();
		$query = "SELECT a.paquete_insumos_id,a.descripcion
    FROM qx_paquetes_insumos a";
    if($codigoBus){
      $query.=" WHERE a.paquete_insumos_id LIKE '$codigoBus%'";
      $yaand=1;
    }
    if($DescripcionBus){
      if($yaand==1){
        $query.=" AND a.descripcion LIKE '%".strtoupper($DescripcionBus)."%'";
      }else{
        $query.=" WHERE a.descripcion LIKE '%".strtoupper($DescripcionBus)."%'";
      }
    }
    $query.=" ORDER BY a.descripcion";

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
		return $vars;
  }

  /**
  *		SeleccionPaquetesInventariosQx
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function SeleccionPaquetesInventariosQx(){
    if($_REQUEST['Volver']){
      $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
      return true;
    }
    $this->BuscadorPaquetesInv($_REQUEST['codigoBus'],$_REQUEST['DescripcionBus'],$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }


  /**
  *		ConsultaPaquetesInventariosQx
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function ConsultaPaquetesInventariosQx(){
    $this->LlamaConsultaPaquetesInventariosQx($_REQUEST['paqueteId'],$_REQUEST['nomPaquete'],$_REQUEST['codigoBus'],$_REQUEST['DescripcionBus'],$_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

  /**
  *		ProductosPaquetesInventariosBodega
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function ProductosPaquetesInventariosBodega($paqueteId){
    list($dbconn) = GetDBconn();
	
    $query="SELECT 	a.codigo_producto,
					c.descripcion,
					a.cantidad,
					e.lote,
					e.fecha_vencimiento,
					e.existencia_actual as existencia
			FROM 	qx_paquetes_contiene_insumos a,
					inventarios b,
					inventarios_productos c,
					existencias_bodegas d,
					existencias_bodegas_lote_fv e
			WHERE 	a.paquete_insumos_id='".$paqueteId."' 
			AND		a.empresa_id=b.empresa_id 
			AND 	a.codigo_producto=b.codigo_producto 
			AND		b.codigo_producto=c.codigo_producto 
			AND 	a.codigo_producto=d.codigo_producto 
			AND 	d.empresa_id='".$_SESSION['IYM_PROGRAMACIONES_QX']['Empresa']."' 
			AND 	d.centro_utilidad='".$_SESSION['IYM_PROGRAMACIONES_QX']['CentroUtili']."' 
			AND 	d.bodega='".$_SESSION['IYM_PROGRAMACIONES_QX']['Bodega']."'
			AND		(	d.empresa_id = e.empresa_id 
					AND d.centro_utilidad = e.centro_utilidad
					AND	d.codigo_producto = e.codigo_producto
					AND	d.bodega = e.bodega
					AND	e.existencia_actual > 0
					)";
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

  /**
  *		SeleccionPtosPaqueteInv
  *
  *		Funcion que Guarda los despachos y devoluciones para la cirugia del paciente
  *
  *		@Author Lorena Arag?n G.
  *		@access Public
  *		@return boolean
  */

  function SeleccionPtosPaqueteInv(){
    $regs=$this->ProductosPaquetesInventariosBodega($_REQUEST['paqueteId']);
	
    for($i=0;$i<sizeof($regs);$i++){
      if(!$_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]){
        $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']][$regs[$i]['descripcion']]=$regs[$i]['existencia'];
        $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DES'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]=0;
        $_SESSION['IYM_PROGRAMACIONES_QX']['PRODUCTOS_IYM_CANTIDADES_DEV'][$regs[$i]['codigo_producto']][$regs[$i]['lote']][$regs[$i]['fecha_vencimiento']]=0;
      }
      $divisor=(int)($regs[$i]['cantidad']);
      if($regs[$i]['cantidad']%$divisor){
        $divisor=$regs[$i]['cantidad'];
      }else{
        $divisor=(int)($regs[$i]['cantidad']);
      }
      $cadena=urlencode($regs[$i]['codigo_producto'].'||//'.$regs[$i]['lote'].'||//'.$regs[$i]['fecha_vencimiento'].'||//'.$regs[$i]['descripcion']);
      $vec[$cadena]=$divisor;
      $_REQUEST['CantDespachadas']=$vec;
    }
	print_r($regs);
    $this->FrmDespachoCanastasCirugia($_REQUEST['programacionId'],$_REQUEST['profesional'],$_REQUEST['nombrepac'],$_REQUEST['ValorfechaInicia'],$_REQUEST['ValorhoraInicia'],$_REQUEST['ValorhoraFin']);
    return true;
  }

  /*
    (CASE WHEN (SELECT count(*)
    FROM qx_cups_paquetes_insumos b,cuentas_liquidaciones_qx_procedimientos c
    WHERE b.paquete_insumos_id=a.paquete_insumos_id AND c.cuenta_liquidacion_qx_id='".$liquidacionId."' AND
    b.cargo=c.cargo_cups) > 0 THEN '1' ELSE '0' END) as existe
  */

  function NumeroVecesProducto($codigoProducto){
    list($dbconn) = GetDBconn();
	
    $query="SELECT count(*) as cantidad_producto
			FROM (	SELECT DISTINCT on (codigo_producto,lote,fecha_vencimiento) * 
					FROM estacion_enfermeria_qx_iym 
					WHERE codigo_producto = '".$codigoProducto."' 
					AND programacion_id = '".$_SESSION['IYM_PROGRAMACIONES_QX']['No_Programacion']."'
				 ) as cant_prod";
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
}//fin class
?>
