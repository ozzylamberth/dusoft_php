<?php

/**
*MODULO para el Manejo de Programacion e cirugias del Sistema
*
* @author Lorena Aragon
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la programacion de cirugias del paciente
*/

class app_Banco_Sangre_user extends classModulo
{

	function app_Banco_Sangre_user()
	{
	  $this->limit=GetLimitBrowser();
		//$this->limit=2;
    return true;
	}
/**
* Funcion que llama la forma donde se muestran los departamentos del sistema a los que el usuario puede accesar
* @return array
*/
	function main(){
		//if(!$this->FrmLogueoCirugias()){
		if(!$this->MenuConsultas()){
      return false;
    }
		return true;
	}

/**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
	function tipo_id_paciente(){
		list($dbconn) = GetDBconn();
		$query = "SELECT tipo_id_paciente,descripcion
		FROM tipos_id_pacientes ORDER BY indice_de_orden";
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


	function ConsultaFactor(){
		list($dbconn) = GetDBconn();
		$query = "SELECT a.grupo_sanguineo,a.rh,b.descripcion
		FROM hc_tipos_sanguineos a,hc_tipos_rh b
		WHERE a.rh=b.rh
		ORDER BY a.indice_de_orden";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);;
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }

	function LlamaIngresoBolsaSangre(){
    //$this->IngresoBolsaSangre();
    $this->FormaPedidoAlbaran();
		return true;
	}

	function ValidarAlbaranProcedencia(){
    if($_REQUEST['Cancelar']){
      $this->MenuConsultas();
			return true;
		}
		if($_REQUEST['Aceptar']){
      $this->FormaPedidoAlbaran($_REQUEST['albaran']);
			return true;
		}
		if($_REQUEST['Buscar']){
      $this->SeleccionEntidad($_REQUEST['albaran']);
			return true;
		}
		if($_REQUEST['SeleccionDatos']){
      if(!$_REQUEST['albaran'] || !$_REQUEST['descipcion_sgsss'] || !$_REQUEST['codigo_sgsss']){
        if(!$_REQUEST['albaran']){$this->frmError["albaran"]=1;}
				if(!$_REQUEST['descipcion_sgsss']){$this->frmError["descipcion_sgsss"]=1;}
        if(!$_REQUEST['codigo_sgsss']){$this->frmError["descipcion_sgsss"]=1;}
			  $this->frmError["MensajeError"]="El Albaran y La Procedencia son obligatorias.";
        $this->FormaPedidoAlbaran($_REQUEST['albaran'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss']);
				return true;
			}
			$this->IngresoBolsaSangre('','','','','',$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],'','',$_REQUEST['albaran'],'');
			return true;
		}
	}

	function SeleccionProcedencias($albaran){
	  list($dbconn) = GetDBconn();
    $query="SELECT a.entidad_origen,c.nombre_tercero FROM banco_sangre_albaranes a,terceros_sgsss b,terceros c WHERE a.albaran='$albaran' AND a.entidad_origen=b.codigo_sgsss AND
		b.tipo_id_tercero=c.tipo_id_tercero AND b.tercero_id=c.tercero_id ORDER BY b.indice_de_orden";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}

	function SeleccionAlbaranProcedencia(){
    $this->IngresoBolsaSangre('','','','','',$_REQUEST['nombreTercero'],$_REQUEST['codigo'],'','',$_REQUEST['albaran'],'');
		return true;
	}

	function InsertarBolsaSangre(){
	  if($_REQUEST['Buscar']){
      $this->SeleccionEntidad($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],'',$_REQUEST['albaran']);
			return true;
		}
		if($_REQUEST['calcular']){
      list($dbconn) = GetDBconn();
			$query="SELECT dias_calculo_fecha_extraccion
			FROM hc_tipos_componentes
			WHERE hc_tipo_componente='".$_REQUEST['tipoComponente']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar banco_sangre_bolsas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$datos=$result->RecordCount();
				if($datos){
					$vars=$result->GetRowAssoc($toUpper=false);
					if(strlen($_REQUEST['FechaVencimiento'])==10){
						$_REQUEST['FechaVencimiento']=ereg_replace("-","/",$_REQUEST['FechaVencimiento']);
						(list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaVencimiento']));
					}elseif(strlen($_REQUEST['FechaVencimiento'])==8){
						$dia=substr($_REQUEST['FechaVencimiento'],0,2);
						$mes=substr($_REQUEST['FechaVencimiento'],2,2);
						$ano=substr($_REQUEST['FechaVencimiento'],4,4);
					}
					$FechaExtraccion=date('d/m/Y',mktime(0,0,0,$mes,($dia-$vars['dias_calculo_fecha_extraccion']),$ano));
				}
			}
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$FechaExtraccion,$_REQUEST['origen'],$_REQUEST['albaran']);
			return true;
		}

		if($_REQUEST['cancelar']){
      $this->FormaPedidoAlbaran($_REQUEST['albaran']);
			return true;
		}
    if(!$_REQUEST['BolsaId'] || !$_REQUEST['selloCalidad'] || $_REQUEST['grupo_sanguineo']==-1 ||
		!$_REQUEST['FechaVencimiento'] || $_REQUEST['tipoComponente']==-1 || !$_REQUEST['origen']){
      if(!$_REQUEST['BolsaId']){$this->frmError["BolsaId"]=1;}
			if(!$_REQUEST['selloCalidad']){$this->frmError["selloCalidad"]=1;}
      if($_REQUEST['grupo_sanguineo']==-1){$this->frmError["grupo_sanguineo"]=1;}
      if(!$_REQUEST['FechaVencimiento']){$this->frmError["FechaVencimiento"]=1;}
      if($_REQUEST['tipoComponente']==-1){$this->frmError["tipoComponente"]=1;}
			if(!$_REQUEST['origen']){$this->frmError["origen"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos.";
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],$_REQUEST['origen'],$_REQUEST['albaran']);
			return true;
		}
    if(strlen($_REQUEST['FechaVencimiento'])==10){
      $_REQUEST['FechaVencimiento']=ereg_replace("-","/",$_REQUEST['FechaVencimiento']);
      (list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaVencimiento']));
		}elseif(strlen($_REQUEST['FechaVencimiento'])==8){
      $dia=substr($_REQUEST['FechaVencimiento'],0,2);
			$mes=substr($_REQUEST['FechaVencimiento'],2,2);
			$ano=substr($_REQUEST['FechaVencimiento'],4,4);
		}
		$fechaVence=$ano.'-'.$mes.'-'.$dia;
		if($_REQUEST['FechaExtraccion']){
		$_REQUEST['FechaExtraccion']=ereg_replace("-","/",$_REQUEST['FechaExtraccion']);
		(list($dia1,$mes1,$ano1)=explode('/',$_REQUEST['FechaExtraccion']));
		$fechaExtrac="'$ano1-$mes1-$dia1'";
		}
		if((mktime(0,0,0,$mes,$dia,$ano))<(mktime(0,0,0,date('m'),date('d'),date('Y')))){
      $this->frmError["MensajeError"]="Error en las fechas, La fecha de Vencimiento debe ser mayor a la Actual.";
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],$_REQUEST['origen'],$_REQUEST['albaran']);
			return true;
		}
    if(mktime(0,0,0,$mes1,$dia1,$ano1)>=mktime(0,0,0,$mes,$dia,$ano)){
      $this->frmError["MensajeError"]="Error en las fechas, La fecha de Extraccion no puede ser mayor o Igual a la del vencimiento.";
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],$_REQUEST['origen'],$_REQUEST['albaran']);
			return true;
		}
    $existe=$this->ComprobarExistenciaBolsa($_REQUEST['BolsaId'],$_REQUEST['tipoComponente']);
		if($existe==1){
      $this->frmError["MensajeError"]="Error, El numero de la Bolsa ya Existe en la Base de Datos";
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],$_REQUEST['origen'],$_REQUEST['albaran']);
			return true;
		}
		$mensaje='Verifique los Datos que Ingreso y Confirme la Entrada de la Bolsa al Inventario de lo Contrario Cancele la Accion ';
    $titulo='INVENTARIO DE BOLSAS DE COMPONENTES SANGUINEOS';
		$this->FormaMensajeConfirmacion($mensaje,$titulo,$_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],
		$_REQUEST['grupo_sanguineo'],$_REQUEST['codigo_sgsss'],$_REQUEST['descipcion_sgsss'],$_REQUEST['FechaVencimiento'],$_REQUEST['tipoComponente'],$_REQUEST['FechaExtraccion'],$_REQUEST['origen'],$_REQUEST['albaran']);
		return true;
	}

	function ComprobarExistenciaBolsa($bolsa,$componente){
    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM banco_sangre_bolsas WHERE bolsa_id='".$bolsa."' AND tipo_componente='".$componente."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        return 1;
			}
		}
		return 0;
	}

	function BusquedaEntidad(){

	  if($_REQUEST['regresar']) {
      $this->FormaPedidoAlbaran($_REQUEST['albaran']);
			return true;
		}
    if($_REQUEST['seleccionar']){
		  $this->IngresoBolsaSangre('','','','','',$_REQUEST['nombretercero'],$_REQUEST['codigo'],'','',$_REQUEST['albaran'],'');
			return true;
		}
    list($dbconn) = GetDBconn();
		$query="SELECT a.codigo_sgsss,b.nombre_tercero FROM terceros_sgsss a,terceros b WHERE a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
    if($_REQUEST['codigo_sgsssBus']){
		  $codigo_sgsssBus=strtoupper($_REQUEST['codigo_sgsssBus']);
      $query.=" AND a.codigo_sgsss LIKE '%".$codigo_sgsssBus."%'";
		}
		if($_REQUEST['descipcion_sgsssBus']){
		  $descipcion_sgsssBus=strtoupper($_REQUEST['descipcion_sgsssBus']);
      $query.=" AND b.nombre_tercero LIKE '%".$descipcion_sgsssBus."%'";
		}
		$query.=" ORDER BY a.indice_de_orden";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$this->SeleccionEntidad($_REQUEST['albaran'],$vars);
		return true;
	}

	function ConsultaComponente($hcReservaSangreId){
		list($dbconn) = GetDBconn();
		$query = "SELECT b.hc_tipo_componente,b.componente
		FROM hc_tipos_componentes b";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
	  return $vars;
  }

	function LlamaConsultaBolsasSangre(){
	  $this->ConsultaInventariosBolsasSangre();
		return true;
	}

	function ConsultaBolsasSangre(){
    $this->ConsultaInventariosBolsasSangre($_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componente'],$_REQUEST['FechaVmto'],$_REQUEST['estado'],$_REQUEST['cruze'],$_REQUEST['albaran']);
		return true;
	}

	function SeleccionBolsasComponentes($codigoBolsa,$grupoSanguineo,$componente,$FechaVmto,$estado,$cruze,$albaran){

		list($dbconn) = GetDBconn();
		$query1="SELECT COUNT(*)
		FROM banco_sangre_bolsas a,terceros_sgsss b,terceros c,hc_tipos_componentes d, banco_sangre_estados_bolsas_alicuotas e,banco_sangre_bolsas_alicuotas f,banco_sangre_albaranes z
		WHERE z.entidad_origen=b.codigo_sgsss AND b.tipo_id_tercero=c.tipo_id_tercero AND b.tercero_id=c.tercero_id AND
		a.tipo_componente=d.hc_tipo_componente  AND a.ingreso_bolsa_id=f.ingreso_bolsa_id AND f.sw_estado=e.estado AND z.registro_albaran_id=a.registro_albaran_id";
		 $query="SELECT a.ingreso_bolsa_id,a.cruzada,a.sello_calidad,f.sw_estado,e.descripcion as nomestado,a.bolsa_id,a.grupo_sanguineo,a.rh,z.entidad_origen,c.nombre_tercero,a.fecha_vencimiento,a.fecha_extraccion,a.tipo_componente,d.componente,f.numero_alicuota,f.cantidad,z.albaran
		FROM banco_sangre_bolsas a,terceros_sgsss b,terceros c,hc_tipos_componentes d, banco_sangre_estados_bolsas_alicuotas e,banco_sangre_bolsas_alicuotas f,banco_sangre_albaranes z
		WHERE z.entidad_origen=b.codigo_sgsss AND b.tipo_id_tercero=c.tipo_id_tercero AND b.tercero_id=c.tercero_id AND
		a.tipo_componente=d.hc_tipo_componente  AND a.ingreso_bolsa_id=f.ingreso_bolsa_id AND f.sw_estado=e.estado AND z.registro_albaran_id=a.registro_albaran_id";
		if($codigoBolsa){
      $query.=" AND a.bolsa_id LIKE '%".$codigoBolsa."%'";
			$query1.=" AND a.bolsa_id LIKE '%".$codigoBolsa."%'";
		}
		if($grupoSanguineo && $grupoSanguineo!=-1){
		  (list($grupoSanguineo,$rh)=explode('/',$grupoSanguineo));
      $query.=" AND a.grupo_sanguineo='".$grupoSanguineo."' AND a.rh='".$rh."'";
			$query1.=" AND a.grupo_sanguineo='".$grupoSanguineo."' AND a.rh='".$rh."'";
		}
		if($componente && $componente!=-1){
		  $query.=" AND a.tipo_componente='".$componente."'";
			$query1.=" AND a.tipo_componente='".$componente."'";
		}
		if($FechaVmto){
		  $query.=" AND a.fecha_vencimiento='".$FechaVmto."'";
			$query1.=" AND a.fecha_vencimiento='".$FechaVmto."'";
		}
		if($estado){
			$query.=" AND f.sw_estado='".$estado."'";
			$query1.=" AND f.sw_estado='".$estado."'";
		}
		if($albaran){
			$query.=" AND z.albaran='".$albaran."'";
			$query1.=" AND z.albaran='".$albaran."'";
		}
		$query.=" ORDER BY a.ingreso_bolsa_id,f.numero_alicuota";
		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
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
		$query.=" LIMIT " . $this->limit . " OFFSET $Of";
		$result = $dbconn->Execute($query);
		if($result->EOF){
			$this->error = "Error al ejecutar la consulta.<br>";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]][$result->fields[14]]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}
		return $vars;
	}

	function DardeBajaBolsaSangre(){
    $this->SeleccionMotivoDarBajaSangre($_REQUEST['BolsaIn'],$_REQUEST['bolsas'],$_REQUEST['bolsaId'],$_REQUEST['sello'],$_REQUEST['componenteId'],
		$_REQUEST['Grupo'],$_REQUEST['rhId'],$_REQUEST['Procedencia'],$_REQUEST['FVence'],$_REQUEST['alicuota']);
		return true;
	}

	function MotivosdeBaja(){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.motivo_id,a.descripcion
		FROM banco_sangre_motivos_incineraciones a";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
	  return $vars;
	}

	function InsertarMotivoBajaSangre(){
    list($dbconn) = GetDBconn();
		if($_REQUEST['motivo']!=-1 && !$_REQUEST['cancelar']){
			$query ="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='4' WHERE ingreso_bolsa_id='".$_REQUEST['BolsaIn']."' AND numero_alicuota='".$_REQUEST['alicuota']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
			  $_REQUEST['FechaDevuelve']=ereg_replace("-","/",$_REQUEST['FechaDevuelve']);
			  (list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaDevuelve']));
			  $fecha=$ano.'-'.$mes.'-'.$dia.' '.$_REQUEST['horaPrueba'].':'.$_REQUEST['minutosPrueba'].':'.'00';
				$query ="INSERT INTO banco_sangre_bolsas_incineradas(ingreso_bolsa_id,numero_alicuota,motivo_id,observaciones,usuario_id,fecha_registro,persona_devuelve,hora_devolucion)
				VALUES('".$_REQUEST['BolsaIn']."','".$_REQUEST['alicuota']."','".$_REQUEST['motivo']."','".$_REQUEST['observaciones']."','".UserGetUID()."','".date("Y-m-d H:i:s")."',
				'".$_REQUEST['presonaDevuelve']."','$fecha')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->ConsultaInventariosBolsasSangre();
		return true;
	}

	function ConfirmarInsercionBolsas(){
    if($_REQUEST['Cancelar']){
      $this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],$_REQUEST['motivoInsercion'],$_REQUEST['albaran']);
			return true;
		}
		if(strlen($_REQUEST['FechaVencimiento'])==10){
      $_REQUEST['FechaVencimiento']=ereg_replace("-","/",$_REQUEST['FechaVencimiento']);
      (list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaVencimiento']));
		}elseif(strlen($_REQUEST['FechaVencimiento'])==8){
      $dia=substr($_REQUEST['FechaVencimiento'],0,2);
			$mes=substr($_REQUEST['FechaVencimiento'],2,2);
			$ano=substr($_REQUEST['FechaVencimiento'],4,4);
		}
		$fechaVence=$ano.'-'.$mes.'-'.$dia;
		(list($grupoSanguineo,$rh)=explode('/',$_REQUEST['grupo_sanguineo']));

		if($_REQUEST['FechaExtraccion']){
		$_REQUEST['FechaExtraccion']=ereg_replace("-","/",$_REQUEST['FechaExtraccion']);
		(list($dia1,$mes1,$ano1)=explode('/',$_REQUEST['FechaExtraccion']));
		$fechaExtrac="'$ano1-$mes1-$dia1'";
		}else{
      $fechaExtrac='NULL';
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
    $query="SELECT registro_albaran_id FROM banco_sangre_albaranes WHERE albaran='".$_REQUEST['albaran']."' AND entidad_origen='".$_REQUEST['codigo_sgsss']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        $vars=$result->GetRowAssoc($ToUpper = false);
        $secuenciaAlbaran=$vars['registro_albaran_id'];
			}else{
        $query="SELECT nextval('banco_sangre_albaranes_registro_albaran_id_seq')";
				$result = $dbconn->Execute($query);
				$secuenciaAlbaran=$result->fields[0];
				$query ="INSERT INTO banco_sangre_albaranes(registro_albaran_id,albaran,entidad_origen)VALUES('$secuenciaAlbaran','".$_REQUEST['albaran']."','".$_REQUEST['codigo_sgsss']."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar hc_tipos_sanguineos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$dbconn->RollbackTrans();
					return false;
				}
			}
		}
		$query="SELECT nextval('banco_sangre_bolsas_ingreso_bolsa_id_seq')";
		$result = $dbconn->Execute($query);
		$IngresoBolsa=$result->fields[0];
		$query ="INSERT INTO banco_sangre_bolsas(ingreso_bolsa_id,bolsa_id,sello_calidad,
												grupo_sanguineo,rh,fecha_vencimiento,
												tipo_componente,fecha_extraccion,
												usuario_id,fecha_registro,cruzada,motivo_insercion,registro_albaran_id)
												VALUES($IngresoBolsa,'".$_REQUEST['BolsaId']."','".$_REQUEST['selloCalidad']."',
												'$grupoSanguineo','$rh','".$fechaVence."',
												'".$_REQUEST['tipoComponente']."',".$fechaExtrac.",
												'".UserGetUID()."','".date("Y-m-d H:i:s")."','0','".$_REQUEST['motivoInsercion']."','$secuenciaAlbaran')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
			$query ="INSERT INTO banco_sangre_bolsas_alicuotas(ingreso_bolsa_id,numero_alicuota,cantidad,sw_estado)VALUES('$IngresoBolsa','0','0','1')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar hc_tipos_sanguineos";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$dbconn->CommitTrans();
			}
		}
		$this->FormaPedidoAlbaran($_REQUEST['albaran']);
		return true;
	}

	function nombreComponente($tipoComponente){
    list($dbconn) = GetDBconn();
		$query = "SELECT componente FROM hc_tipos_componentes WHERE hc_tipo_componente='$tipoComponente'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  $vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
	  return $vars;
	}

	function LlamaComponentesAVencer(){
    $this->ListadoComponentesAVencer();
		return true;
	}

	function ComponentesCercaAVencer(){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.fecha_vencimiento,a.bolsa_id,a.sello_calidad,b.componente,a.grupo_sanguineo,a.rh,d.nombre_tercero
		FROM banco_sangre_bolsas a,hc_tipos_componentes b,terceros_sgsss c,terceros d,banco_sangre_albaranes z
		WHERE a.tipo_componente=b.hc_tipo_componente AND ('".date("Y-m-d")."' >= a.fecha_vencimiento-b.dias_previos_vencimiento AND '".date("Y-m-d")."'<= a.fecha_vencimiento)
		AND z.entidad_origen=c.codigo_sgsss AND c.tipo_id_tercero=d.tipo_id_tercero AND c.tercero_id=d.tercero_id
		AND z.registro_albaran_id=a.registro_albaran_id ORDER BY a.fecha_vencimiento,a.tipo_componente";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
	  return $vars;
	}

	function EstadoBolsasComponentes(){
    list($dbconn) = GetDBconn();
		$query = "SELECT a.estado,a.descripcion
		FROM banco_sangre_estados_bolsas_alicuotas a";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
	  return $vars;
	}

	function LlamaSolicitudExterna(){
    $this->SolicitudExterna();
		return true;
	}

	function TotalUnidadesSanguineas($codigoBolsa,$grupoSanguineo,$componente){
    list($dbconn) = GetDBconn();
		if(!$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']){
      $_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']='NULL';
		}
		if($grupoSanguineo && $grupoSanguineo!=-1){
			(list($grupo_Sanguineo,$rh)=explode('/',$grupoSanguineo));
			$concat.=" AND a.grupo_sanguineo='$grupo_Sanguineo' AND a.rh='$rh'";
		}
		if($codigoBolsa){
      $concat.=" AND a.bolsa_id='$codigoBolsa'";
		}
		if($componente && $componente!=-1){
      $concat.=" AND a.tipo_componente='$componente'";
		}

		$query1 = "SELECT count(*)
		FROM
		(SELECT x.ingreso_bolsa_id,x.numero_alicuota
		FROM banco_sangre_bolsas_alicuotas x
		WHERE x.sw_estado='1'
		EXCEPT
		SELECT y.ingreso_bolsa_id,y.numero_alicuota
		FROM banco_sangre_solicitud_ext_detalle y
		WHERE y.documento_solicitud_id=".$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT'].") as z,
		banco_sangre_bolsas a
		LEFT JOIN hc_tipos_componentes b ON(a.tipo_componente=b.hc_tipo_componente),
		banco_sangre_albaranes l
		LEFT JOIN terceros_sgsss c ON(l.entidad_origen=c.codigo_sgsss)
		LEFT JOIN terceros d  ON(d.tipo_id_tercero=c.tipo_id_tercero AND d.tercero_id=c.tercero_id)
		,banco_sangre_bolsas_alicuotas e
		WHERE z.ingreso_bolsa_id=e.ingreso_bolsa_id AND z.numero_alicuota=e.numero_alicuota
		AND e.ingreso_bolsa_id=a.ingreso_bolsa_id AND a.registro_albaran_id=l.registro_albaran_id $concat";
		$query = "SELECT a.ingreso_bolsa_id,a.bolsa_id,a.sello_calidad,b.componente,a.grupo_sanguineo,a.rh,d.nombre_tercero,a.fecha_vencimiento,
		e.numero_alicuota,e.cantidad
		FROM
		(SELECT x.ingreso_bolsa_id,x.numero_alicuota
		FROM banco_sangre_bolsas_alicuotas x
		WHERE x.sw_estado='1'
		EXCEPT
		SELECT y.ingreso_bolsa_id,y.numero_alicuota
		FROM banco_sangre_solicitud_ext_detalle y
		WHERE y.documento_solicitud_id=".$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT'].") as z,
		banco_sangre_bolsas a
		LEFT JOIN hc_tipos_componentes b ON(a.tipo_componente=b.hc_tipo_componente),
		banco_sangre_albaranes l
		LEFT JOIN terceros_sgsss c ON(l.entidad_origen=c.codigo_sgsss)
		LEFT JOIN terceros d  ON(d.tipo_id_tercero=c.tipo_id_tercero AND d.tercero_id=c.tercero_id)
		,banco_sangre_bolsas_alicuotas e
		WHERE z.ingreso_bolsa_id=e.ingreso_bolsa_id AND z.numero_alicuota=e.numero_alicuota
		AND e.ingreso_bolsa_id=a.ingreso_bolsa_id AND a.registro_albaran_id=l.registro_albaran_id $concat";

		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
		return $vars;
	}

	function BusquedaEntidadSolicitante(){
    list($dbconn) = GetDBconn();
		$query="SELECT a.codigo_sgsss,b.nombre_tercero FROM terceros_sgsss a,terceros b WHERE a.tipo_id_tercero=b.tipo_id_tercero AND a.tercero_id=b.tercero_id";
    if($_REQUEST['codigo_sgsssBus']){
		  $codigo_sgsssBus=strtoupper($_REQUEST['codigo_sgsssBus']);
      $query.=" AND a.codigo_sgsss LIKE '%".$codigo_sgsssBus."%'";
		}
		if($_REQUEST['descipcion_sgsssBus']){
		  $descipcion_sgsssBus=strtoupper($_REQUEST['descipcion_sgsssBus']);
      $query.=" AND b.nombre_tercero LIKE '%".$descipcion_sgsssBus."%'";
		}
		$query.=" ORDER BY a.indice_de_orden";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
			}
		}
		$this->SolicitudExterna($vars);
		return true;
	}

	function LlamaSolicitudExternaDetalle(){
    $this->SolicitudExternaDetalle($_REQUEST['entidadExterna'],$_REQUEST['nombreEntidad']);
		return true;
	}

	function GuardarSolicitudExterna(){
	  list($dbconn) = GetDBconn();
	  if($_REQUEST['salir']){
		  if($_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']!='NULL'){
				$query="SELECT * FROM banco_sangre_solicitud_ext_detalle WHERE documento_solicitud_id='".$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']."'";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar banco_sangre_solicitud_ext";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
					$datos=$result->RecordCount();
					if(!$datos){
						$this->frmError["MensajeError"]="Debe solicitar Unidades en esta Solicitud";
						$this->SolicitudExternaDetalle($_REQUEST['entidadExterna'],$_REQUEST['nombreEntidad']);
						return true;
					}
				}
			}
		  unset($_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']);
      $this->MenuConsultas();
			return true;
		}
		if($_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']=='NULL'){
      $query="SELECT nextval('banco_sangre_solicitud_ext_documento_solicitud_id_seq')";
			$result = $dbconn->Execute($query);
			$solicitudExt=$result->fields[0];
		  $query="INSERT INTO banco_sangre_solicitud_ext(documento_solicitud_id,
			entidad_solicitante,motivo_solicitud,fecha_registro,usuario_id)
			VALUES('$solicitudExt','".$_REQUEST['entidadExterna']."','".$_REQUEST['motivo']."','".date("Y-m-d H:i:s")."','".UserGetUID()."')";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar banco_sangre_solicitud_ext";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
			  $bolsas=$_REQUEST['seleccion'];
        for($i=0;$i<sizeof($bolsas);$i++){
				  (list($ingresoBolsa,$alicuota)=explode('|/',$bolsas[$i]));
          $query="INSERT INTO banco_sangre_solicitud_ext_detalle(documento_solicitud_id,ingreso_bolsa_id,numero_alicuota)VALUES('$solicitudExt','".$ingresoBolsa."','".$alicuota."')";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}else{
            $query="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='3' WHERE ingreso_bolsa_id='".$ingresoBolsa."' AND numero_alicuota='".$alicuota."'";
						$result = $dbconn->Execute($query);
						if($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
					}
				}
			}
			$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']=$solicitudExt;
	  }else{
      $bolsas=$_REQUEST['seleccion'];
			for($i=0;$i<sizeof($bolsas);$i++){
			  (list($ingresoBolsa,$alicuota)=explode('|/',$bolsas[$i]));
				$query="INSERT INTO banco_sangre_solicitud_ext_detalle(documento_solicitud_id,ingreso_bolsa_id,numero_alicuota)VALUES('".$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']."','".$ingresoBolsa."','".$alicuota."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}else{
          $query="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='3' WHERE ingreso_bolsa_id='".$ingresoBolsa."' AND numero_alicuota='".$alicuota."'";
					$result = $dbconn->Execute($query);
					if($dbconn->ErrorNo() != 0){
						$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
		}
		$this->SolicitudExternaDetalle($_REQUEST['entidadExterna'],$_REQUEST['nombreEntidad']);
		return true;
	}

	function UnidadesSolicitadas(){
	  list($dbconn) = GetDBconn();
    $query="SELECT a.ingreso_bolsa_id,f.numero_alicuota,b.bolsa_id,b.sello_calidad,c.componente,b.grupo_sanguineo,b.rh,e.nombre_tercero,b.fecha_vencimiento
		FROM banco_sangre_solicitud_ext_detalle a,banco_sangre_bolsas b
		LEFT JOIN hc_tipos_componentes c ON(b.tipo_componente=c.hc_tipo_componente),
    banco_sangre_albaranes l
		LEFT JOIN terceros_sgsss d ON(l.entidad_origen=d.codigo_sgsss)
		LEFT JOIN terceros e  ON(d.tipo_id_tercero=e.tipo_id_tercero AND d.tercero_id=e.tercero_id),
		banco_sangre_bolsas_alicuotas f
		WHERE a.documento_solicitud_id='".$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']."' AND
		a.ingreso_bolsa_id=b.ingreso_bolsa_id AND a.ingreso_bolsa_id=f.ingreso_bolsa_id AND a.numero_alicuota=f.numero_alicuota AND
		b.registro_albaran_id=l.registro_albaran_id";
    $result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
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

	function EliminaBolsaSolicitud(){
	  list($dbconn) = GetDBconn();
    $query="DELETE FROM banco_sangre_solicitud_ext_detalle
		WHERE documento_solicitud_id='".$_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']."' AND
		ingreso_bolsa_id='".$_REQUEST['bolsa']."' AND numero_alicuota='".$_REQUEST['alicuota']."' ";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $query="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='1' WHERE ingreso_bolsa_id='".$_REQUEST['bolsa']."' AND numero_alicuota='".$_REQUEST['alicuota']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al insertar banco_sangre_solicitud_ext_detalle";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->SolicitudExternaDetalle($_REQUEST['entidadExterna'],$_REQUEST['nombreEntidad']);
		return true;
	}

	function BusquedaFiltroBolsas(){
    $this->SolicitudExternaDetalle($_REQUEST['entidadExterna'],$_REQUEST['nombreEntidad'],$_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componente']);
		return true;
	}

	function ConsultaPacientesCruzes(){
    $this->FormaConsultaPacientesCruzes($_REQUEST['ingresoBolsa'],$_REQUEST['BolsaCruze'],$_REQUEST['NumBolsa'],$_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componente'],
		$_REQUEST['FechaVmto'],$_REQUEST['estado'],$_REQUEST['cruze']);
		return true;
	}

	function TotalCruzesUnidadesSanguineas($BolsaCruze){

    list($dbconn) = GetDBconn();
		$query="SELECT a.cruze_sanguineo_id,a.fecha_prueba,a.tipo_id_profesional_responsable,a.profesional_responsable_id,b.nombre as profesional,
    a.compatibilidad,c.bolsa_id,c.grupo_sanguineo,c.rh,d.tipo_id_paciente,d.paciente_id,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido
		FROM pacientes
		WHERE d.tipo_id_paciente=tipo_id_paciente AND d.paciente_id=paciente_id) as nombre
		FROM  banco_sangre_cruzes_sanguineos a
		LEFT JOIN profesionales b ON(a.tipo_id_profesional_responsable=b.tipo_id_tercero AND a.profesional_responsable_id=b.tercero_id),
		banco_sangre_bolsas c,banco_sangre_reserva d
		WHERE a.ingreso_bolsa_id=c.ingreso_bolsa_id AND a.solicitud_reserva_sangre_id=d.solicitud_reserva_sangre_id AND
		a.ingreso_bolsa_id='$BolsaCruze' AND a.estado='1'";
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

	function ConsultaCruzeSangre(){

    list($dbconn) = GetDBconn();
		$query="SELECT a.ingreso_bolsa_id,b.tipo_id_paciente,b.paciente_id,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido FROM pacientes WHERE b.tipo_id_paciente=tipo_id_paciente AND b.paciente_id=paciente_id) as nombre,
    b.fecha_hora_reserva,b.responsable_solicitud,b.grupo_sanguineo,b.rh,a.solicitud_reserva_sangre_id,
		c.bolsa_id,c.sello_calidad,c.fecha_vencimiento,c.grupo_sanguineo as grupo_sanguineo_bolsa,c.rh as rh_bolsa,e.nombre_tercero,c.fecha_extraccion,
		a.hemoclasificacion_manual_anti_a,m.descripcion as hemoclasificacion_manual_anti_a_des,
		a.hemoclasificacion_manual_anti_b,n.descripcion as hemoclasificacion_manual_anti_b_des,
		a.hemoclasificacion_manual_anti_ab,o.descripcion as hemoclasificacion_manual_anti_ab_des,
		a.hemoclasificacion_manual_anti_d,p.descripcion as hemoclasificacion_manual_anti_d_des,
		a.interpretacion_grupo_manual,a.interpretacion_rh_manual,a.tipo_id_profesional_manual,a.profesional_manual_id,
		a.hemoclasificacion_gel_anti_a,q.descripcion as hemoclasificacion_gel_anti_a_des,
		a.hemoclasificacion_gel_anti_b,r.descripcion as hemoclasificacion_gel_anti_b_des,
		a.hemoclasificacion_gel_anti_ab,s.descripcion as hemoclasificacion_gel_anti_ab_des,
		a.hemoclasificacion_gel_anti_d,t.descripcion as hemoclasificacion_gel_anti_d_des,
		a.interpretacion_grupo_gel,a.interpretacion_rh_gel,a.tipo_id_profesional_gel,a.profesional_gel_id,
		a.reaccion_cruzada_visual,
		a.celulas_a,rr.descripcion as celulas_a_des,
		a.celulas_b,ss.descripcion as celulas_b_des,
		a.celulas_0,tt.descripcion as celulas_0_des,
    a.fecha_prueba,a.observaciones,
		a.enzimas,aa.descripcion as enz_des,
		a.fase_coobms,bb.descripcion as coobms_d_des,
		a.compatibilidad,a.tipo_id_profesional_responsable,a.profesional_responsable_id,
		a.cde,pp.descripcion as cde_des,
		a.lectina,qq.descripcion as lectina_des,
		a.rai_cel2,cc.descripcion as rai_cel2_des,
		a.rai_cel1,dd.descripcion as rai_cel1_des,
		a.rai_auto,ee.descripcion as rai_auto_des,
		a.rai_otros,ff.descripcion as rai_otros_des,
		v.nombre as profesionalmanual,w.nombre as profesionalgel,y.nombre as profesionalresponsable,
    yy1.tipo_id_profesional_entrega,yy1.profesional_entrega_id,yy2.nombre as profesionalentrega,yy1.tipo_id_profesional_recibe,yy1.profesional_recibe_id,yy3.nombre as profesionalrecibe,yy1.fecha_recibe,
		neww.grupo_sanguineo as grupo_paciente,neww.rh as rh_paciente
		FROM banco_sangre_cruzes_sanguineos a
		LEFT JOIN banco_sangre_cantidad_cruzes m ON(a.hemoclasificacion_manual_anti_a=m.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes n ON(a.hemoclasificacion_manual_anti_b=n.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes o ON(a.hemoclasificacion_manual_anti_ab=o.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes p ON(a.hemoclasificacion_manual_anti_d=p.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes q ON(a.hemoclasificacion_gel_anti_a=q.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes r ON(a.hemoclasificacion_gel_anti_b=r.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes s ON(a.hemoclasificacion_gel_anti_ab=s.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes t ON(a.hemoclasificacion_gel_anti_d=t.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes pp ON(a.cde=pp.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes qq ON(a.lectina=qq.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes rr ON(a.celulas_a=rr.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes ss ON(a.celulas_b=ss.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes tt ON(a.celulas_0=tt.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes aa ON(a.enzimas=aa.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes bb ON(a.fase_coobms=bb.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes cc ON(a.rai_cel2=cc.codigo_cantidad_cruces)
		LEFT JOIN banco_sangre_cantidad_cruzes dd ON(a.rai_cel1=dd.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes ee ON(a.rai_auto=ee.codigo_cantidad_cruces)
    LEFT JOIN banco_sangre_cantidad_cruzes ff ON(a.rai_otros=ff.codigo_cantidad_cruces)
		LEFT JOIN profesionales v ON(a.tipo_id_profesional_manual=v.tipo_id_tercero AND a.profesional_manual_id=v.tercero_id)
		LEFT JOIN profesionales w ON(a.tipo_id_profesional_gel=w.tipo_id_tercero AND a.profesional_gel_id=w.tercero_id)
		LEFT JOIN profesionales y ON(a.tipo_id_profesional_responsable=y.tipo_id_tercero AND a.profesional_responsable_id=y.tercero_id)
		LEFT JOIN banco_sangre_cruzes_sanguineos_entregados yy1 ON(a.cruze_sanguineo_id=yy1.cruze_sanguineo_id)
		LEFT JOIN profesionales yy2 ON(yy1.tipo_id_profesional_entrega=yy2.tipo_id_tercero AND yy1.profesional_entrega_id=yy2.tercero_id)
		LEFT JOIN profesionales yy3 ON(yy1.tipo_id_profesional_recibe=yy3.tipo_id_tercero AND yy1.profesional_recibe_id=yy3.tercero_id),
		banco_sangre_reserva b
    LEFT JOIN pacientes_grupo_sanguineo neww ON(b.tipo_id_paciente=neww.tipo_id_paciente AND b.paciente_id=neww.paciente_id AND neww.estado='1'),
		banco_sangre_bolsas c,
		banco_sangre_albaranes nuev
		LEFT JOIN terceros_sgsss d ON(nuev.entidad_origen=d.codigo_sgsss)
    LEFT JOIN terceros e ON(d.tipo_id_tercero=e.tipo_id_tercero AND d.tercero_id=e.tercero_id)
		WHERE a.cruze_sanguineo_id='".$_REQUEST['cruzeid']."' AND a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id AND nuev.registro_albaran_id=c.registro_albaran_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $vars=$result->GetRowAssoc($toUpper=false);
      $_REQUEST['hemoclasifyManualA']=$vars['hemoclasificacion_manual_anti_a'];
			$_REQUEST['hemoclasifyManualA_des']=$vars['hemoclasificacion_manual_anti_a_des'];
			$_REQUEST['hemoclasifyManualB']=$vars['hemoclasificacion_manual_anti_b'];
			$_REQUEST['hemoclasifyManualB_des']=$vars['hemoclasificacion_manual_anti_b_des'];
			$_REQUEST['hemoclasifyManualAB']=$vars['hemoclasificacion_manual_anti_ab'];
			$_REQUEST['hemoclasifyManualAB_des']=$vars['hemoclasificacion_manual_anti_ab_des'];
			$_REQUEST['hemoclasifyManualD']=$vars['hemoclasificacion_manual_anti_d'];
			$_REQUEST['hemoclasifyManualD_des']=$vars['hemoclasificacion_manual_anti_d_des'];
			$_REQUEST['grupoManual']=$vars['interpretacion_grupo_manual'].'/'.$vars['interpretacion_rh_manual'];
			$_REQUEST['bacteriologoManual']=$vars['profesional_manual_id'].'/'.$vars['tipo_id_profesional_manual'];
			$_REQUEST['hemoclasifyGelA']=$vars['hemoclasificacion_gel_anti_a'];
			$_REQUEST['hemoclasifyGelA_des']=$vars['hemoclasificacion_gel_anti_a_des'];
			$_REQUEST['hemoclasifyGelB']=$vars['hemoclasificacion_gel_anti_b'];
			$_REQUEST['hemoclasifyGelB_des']=$vars['hemoclasificacion_gel_anti_b_des'];
			$_REQUEST['hemoclasifyGelAB']=$vars['hemoclasificacion_gel_anti_ab'];
			$_REQUEST['hemoclasifyGelAB_des']=$vars['hemoclasificacion_gel_anti_ab_des'];
			$_REQUEST['hemoclasifyGelD']=$vars['hemoclasificacion_gel_anti_d'];
			$_REQUEST['hemoclasifyGelD_des']=$vars['hemoclasificacion_gel_anti_d_des'];
			$_REQUEST['grupoGel']=$vars['interpretacion_grupo_gel'].'/'.$vars['interpretacion_rh_gel'];
			$_REQUEST['bacteriologoGel']=$vars['profesional_gel_id'].'/'.$vars['tipo_id_profesional_gel'];
			$_REQUEST['formaResultadoCruze']=$vars['reaccion_cruzada_visual'];
			$_REQUEST['lectina']=$vars['lectina'];
			$_REQUEST['lectina_des']=$vars['lectina_des'];
			$_REQUEST['cde']=$vars['cde'];
			$_REQUEST['cde_des']=$vars['cde_des'];
			$_REQUEST['celulasA']=$vars['celulas_a'];
			$_REQUEST['celulasA_des']=$vars['celulas_a_des'];
			$_REQUEST['celulasB']=$vars['celulas_b'];
			$_REQUEST['celulasB_des']=$vars['celulas_b_des'];
			$_REQUEST['celulas0']=$vars['celulas_0'];
			$_REQUEST['celulas0_des']=$vars['celulas_0_des'];
      $_REQUEST['CelI']=$vars['rai_cel1'];
			$_REQUEST['CelI_des']=$vars['rai_cel1_des'];
			$_REQUEST['CelII']=$vars['rai_cel2'];
			$_REQUEST['CelII_des']=$vars['rai_cel2_des'];
			$_REQUEST['Auto']=$vars['rai_auto'];
			$_REQUEST['Auto_des']=$vars['rai_auto_des'];
			$_REQUEST['OtrosRai']=$vars['rai_otros'];
			$_REQUEST['OtrosRai_des']=$vars['rai_otros_des'];
      (list($fecha,$time)=explode(' ',$vars['fecha_prueba']));
			(list($ano,$mes,$dia)=explode('-',$fecha));
      (list($hora,$minutos)=explode(':',$time));
			$_REQUEST['fechaPrueba']=$dia.'/'.$mes.'/'.$ano;
			$_REQUEST['horaPrueba']=$hora;
			$_REQUEST['minutosPrueba']=$minutos;
      $_REQUEST['observaciones']=$vars['observaciones'];
			$_REQUEST['enz']=$vars['enzimas'];
			$_REQUEST['enz_des']=$vars['enz_des'];
			$_REQUEST['cDirecto']=$vars['fase_coobms'];
			$_REQUEST['cDirecto_des']=$vars['coobms_d_des'];
		  $_REQUEST['compatibilidad']=$vars['compatibilidad'];
			$_REQUEST['bacteriologoEntrega']=$vars['profesional_responsable_id'].'/'.$vars['tipo_id_profesional_responsable'];
			$_REQUEST['profesionalResponsable']=$vars['profesionalresponsable'];
			$_REQUEST['profesionalentrega']=$vars['profesionalentrega'];
			$_REQUEST['profesionalrecibe']=$vars['profesionalrecibe'];
			(list($fecha,$time)=explode(' ',$vars['fecha_prueba']));
			(list($anoR,$mesR,$diaR)=explode('-',$fecha));
      (list($horaR,$minutosR)=explode(':',$time));
			$_REQUEST['fechaRecibe']=$diaR.'/'.$mesR.'/'.$anoR;
			$_REQUEST['horaRecibe']=$horaR;
			$_REQUEST['minutosRecibe']=$minutosR;
			$this->FormaResultadosCruze($vars['ingreso_bolsa_id'],$vars['tipo_id_paciente'],$vars['paciente_id'],$vars['nombre'],$vars['fecha_hora_reserva'],$vars['responsable_solicitud'],
			$vars['grupo_sanguineo'],$vars['rh'],$vars['solicitud_reserva_sangre_id'],$vars['bolsa_id'],$vars['sello_calidad'],$vars['fecha_vencimiento'],$vars['grupo_sanguineo_bolsa'],
			$vars['rh_bolsa'],$vars['nombre_tercero'],$vars['fecha_extraccion'],1);
			return true;
		}
	}

	function ConsultaFactorRh(){
		list($dbconn) = GetDBconn();
		$query = "SELECT grupo_sanguineo,rh FROM hc_tipos_sanguineos ORDER BY grupo_sanguineo";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar hc_tipos_sanguineos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			$datos=$result->RecordCount();
			if($datos){
			  while(!$result->EOF){
					$vars[]=$result->GetRowAssoc($ToUpper = false);;
					$result->MoveNext();
				}
			}
		}
	  return $vars;
  }

	function TotalBacteriologos(){

		list($dbconn) = GetDBconn();
		$query="SELECT tipo_id_tercero,tercero_id,nombre FROM profesionales WHERE tipo_profesional='6' AND estado=1 ORDER BY nombre";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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

	function TotalAuxiliares(){

		list($dbconn) = GetDBconn();
		$query="SELECT tipo_id_tercero,tercero_id,nombre FROM profesionales WHERE (tipo_profesional='3' OR tipo_profesional='4') AND estado=1 ORDER BY nombre";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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

	function AlicuotarBolsa(){
    $this->FormaAlicuotarBolsa($_REQUEST['ingresoBolsa'],$_REQUEST['bolsaId'],$_REQUEST['sello'],$_REQUEST['componenteId'],$_REQUEST['FVence'],$_REQUEST['Grupo'],$_REQUEST['rhId'],$_REQUEST['Procedencia'],$_REQUEST['cantidadPrincipal'],
		$_REQUEST['codigoBolsaBuscar'],$_REQUEST['grupoSanguineoBuscar'],$_REQUEST['componenteBuscar'],$_REQUEST['FechaVmtoBuscar'],$_REQUEST['estadoBuscar'],$_REQUEST['cruzeBuscar']);
		return true;
	}

	function GuardarAlicuota(){

    list($dbconn) = GetDBconn();
		if($_REQUEST['Cancelar']){
      $this->ConsultaInventariosBolsasSangre($_REQUEST['codigoBolsaBuscar'],$_REQUEST['grupoSanguineoBuscar'],$_REQUEST['componenteBuscar'],$_REQUEST['FechaVmtoBuscar'],$_REQUEST['estadoBuscar'],$_REQUEST['cruzeBuscar']);
			return true;
		}
		if(!$_REQUEST['cantidad'] || !$_REQUEST['cantidadTotal']){
			$this->frmError["MensajeError"]="Inserte la Cantidad Principal y la Cantidad de la Alicuota";
			$this->FormaAlicuotarBolsa($_REQUEST['ingresoBolsa'],$_REQUEST['bolsaId'],$_REQUEST['sello'],$_REQUEST['componenteId'],$_REQUEST['FVence'],$_REQUEST['Grupo'],$_REQUEST['rhId'],$_REQUEST['Procedencia'],$_REQUEST['cantidadPrincipal'],
			$_REQUEST['codigoBolsaBuscar'],$_REQUEST['grupoSanguineoBuscar'],$_REQUEST['componenteBuscar'],$_REQUEST['FechaVmtoBuscar'],$_REQUEST['estadoBuscar'],$_REQUEST['cruzeBuscar']);
			return true;
		}
		if($_REQUEST['cantidad']>=$_REQUEST['cantidadTotal']){
      $this->frmError["MensajeError"]="La Cantidad Insertada No puede ser mayor o igual a la Cantidad de la bolsa Prinicipal";
			$this->FormaAlicuotarBolsa($_REQUEST['ingresoBolsa'],$_REQUEST['bolsaId'],$_REQUEST['sello'],$_REQUEST['componenteId'],$_REQUEST['FVence'],$_REQUEST['Grupo'],$_REQUEST['rhId'],$_REQUEST['Procedencia'],$_REQUEST['cantidadPrincipal'],
	    $_REQUEST['codigoBolsaBuscar'],$_REQUEST['grupoSanguineoBuscar'],$_REQUEST['componenteBuscar'],$_REQUEST['FechaVmtoBuscar'],$_REQUEST['estadoBuscar'],$_REQUEST['cruzeBuscar']);
			return true;
		}
		$dbconn->BeginTrans();
		$query="INSERT INTO banco_sangre_bolsas_alicuotas(ingreso_bolsa_id,numero_alicuota,cantidad,sw_estado)
		VALUES('".$_REQUEST['ingresoBolsa']."','".$_REQUEST['numeroAlicuota']."','".$_REQUEST['cantidad']."',1)";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
		  $canridadReal=$_REQUEST['cantidadTotal']-$_REQUEST['cantidad'];
      $query="UPDATE banco_sangre_bolsas_alicuotas
			SET cantidad='$canridadReal'
			WHERE ingreso_bolsa_id='".$_REQUEST['ingresoBolsa']."' AND numero_alicuota='0'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
        $dbconn->CommitTrans();
			}
		}
		$this->ConsultaInventariosBolsasSangre($codigoBolsa,$grupoSanguineo,$componente,$FechaVmto,$estado,$cruze);
		return true;
	}

	function SeleccionNumeroAliciotas($ingresoBolsa){
    list($dbconn) = GetDBconn();
		$query="SELECT COUNT(*) as suma
		FROM banco_sangre_bolsas_alicuotas
		WHERE ingreso_bolsa_id='$ingresoBolsa'";
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
		$result->Close();
 		return $vars;
	}

	function MotivosInsercionBolsa(){
	  list($dbconn) = GetDBconn();
		$query="SELECT codigo_motivo,descripcion
		FROM banco_sangre_motivos_insercion_bolsas ORDER BY indice_de_orden";
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

	function nombreMotivoInsercion($tipoMotivo){
    list($dbconn) = GetDBconn();
		$query = "SELECT descripcion FROM banco_sangre_motivos_insercion_bolsas WHERE codigo_motivo='$tipoMotivo'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		  $datos=$result->RecordCount();
			if($datos){
			  $vars=$result->GetRowAssoc($ToUpper = false);
			}
		}
	  return $vars;
	}

	function ConfirmarUsuarioEntrega(){
    list($dbconn) = GetDBconn();
		$query = "SELECT * FROM banco_sangre_usuarios_distribuyen WHERE usuario_id='".UserGetUID()."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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

	function LlamaEntregaBolsaSanguinea(){
    $this->EtregaBolsaSanguinea($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componente'],$_REQUEST['ingresoBolsa'],$_REQUEST['numeroAlicuota']);
		return true;
	}

  function BuscarBolsasParaEngregar(){

    if(empty($_SESSION['PACIENTES']['RETORNO']['PASO'])){
      $this->MenuConsultas();
			return true;
		}
		if($_REQUEST['filtro']){
      $this->SeleccionComponenteSangre($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['bolsa'],$_REQUEST['grupoSan'],$_REQUEST['rh'],$_REQUEST['bolsaNum'],$_REQUEST['tipoComponente'],$_REQUEST['nombre'],
			$_REQUEST['componenteDes'],$_REQUEST['componenteId'],$_REQUEST['solicitudId'],$_REQUEST['alicuota'],$_REQUEST['cantidadAli'],$_REQUEST['nombre'],$_REQUEST['BolsaFiltro'],$_REQUEST['grupo_sanguineoFiltro'],$_REQUEST['AlicuotaNoFiltro'],$_REQUEST['reservaId']);
			return true;
		}
		if($_REQUEST['reservaId']){
		  $_REQUEST['solicitudId']=$_REQUEST['reservaId'];
      $filt=" AND a.solicitud_reserva_sangre_id='".$_REQUEST['reservaId']."'";
		}
    list($dbconn) = GetDBconn();
		$query="SELECT z.tipo_id_paciente,z.paciente_id,z.primer_nombre||' '||z.segundo_nombre||' '||z.primer_apellido||' '||z.segundo_apellido as nombre,
		a.solicitud_reserva_sangre_id,b.tipo_componente_id,b.cantidad_componente,c.componente,c.sw_cruze,b.sw_estado as confirmado
		FROM pacientes z
		LEFT JOIN banco_sangre_reserva a ON (a.tipo_id_paciente=z.tipo_id_paciente AND a.paciente_id=z.paciente_id AND a.sw_estado='1' $filt)
		LEFT JOIN banco_sangre_reserva_detalle b ON(a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND b.sw_estado='2')
		LEFT JOIN hc_tipos_componentes c ON(b.tipo_componente_id=c.hc_tipo_componente)
		WHERE z.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND z.paciente_id='".$_REQUEST['Documento']."' ORDER BY c.hc_tipo_componente";
		/*echo $query="SELECT z.tipo_id_paciente,z.paciente_id,z.primer_nombre||' '||z.segundo_nombre||' '||z.primer_apellido||' '||z.segundo_apellido as nombre,
		a.solicitud_reserva_sangre_id,b.tipo_componente_id,b.cantidad_componente,c.componente,c.sw_cruze,b.sw_estado as confirmado
		FROM pacientes z,
		banco_sangre_reserva a,
		banco_sangre_reserva_detalle b,
		hc_tipos_componentes c
		WHERE z.tipo_id_paciente='".$_REQUEST['TipoDocumento']."' AND z.paciente_id='".$_REQUEST['Documento']."' AND
		a.tipo_id_paciente=z.tipo_id_paciente AND a.paciente_id=z.paciente_id AND a.sw_estado='1' AND
		a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND b.sw_estado='2' AND
		b.tipo_componente_id=c.hc_tipo_componente $filt
		ORDER BY c.hc_tipo_componente ";
		*/

		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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
		$datosBolsa=$this->DatosBolsaSeleccion($_REQUEST['ingresoBolsa'],$_REQUEST['numeroAlicuota']);
		if($datosBolsa){
      $_REQUEST['bolsa']=$datosBolsa['ingreso_bolsa_id'];
			$_REQUEST['grupoSan']=$datosBolsa['grupo_sanguineo'];
			$_REQUEST['rh']=$datosBolsa['rh'];
			$_REQUEST['bolsaNum']=$datosBolsa['bolsa_id'];
			$_REQUEST['componenteDes']=$datosBolsa['componente'];
			$_REQUEST['componenteId']=$datosBolsa['hc_tipo_componente'];
			$_REQUEST['solicitudId']=$datosBolsa[''];
			$_REQUEST['alicuota']=$datosBolsa['numero_alicuota'];
			$_REQUEST['cantidadAli']=$datosBolsa['cantidad'];
		}
		$this->EleccionBolsaEntrega($vars,$_REQUEST['bolsa'],$_REQUEST['grupoSan'],$_REQUEST['rh'],$_REQUEST['bolsaNum'],$_REQUEST['componenteDes'],$_REQUEST['componenteId'],$_REQUEST['solicitudId'],$_REQUEST['alicuota'],$_REQUEST['cantidadAli'],$_REQUEST['reservaId']);
		return true;
	}

	function SeleccionCruzesReserva($solicitud){

		list($dbconn) = GetDBconn();
		$query="SELECT b.bolsa_id,a.ingreso_bolsa_id,b.grupo_sanguineo,b.rh,c.numero_alicuota,c.cantidad
		FROM banco_sangre_cruzes_sanguineos a,banco_sangre_bolsas b,banco_sangre_bolsas_alicuotas c
		WHERE a.solicitud_reserva_sangre_id='$solicitud' AND a.ingreso_bolsa_id=b.ingreso_bolsa_id AND a.estado='1' AND
		a.ingreso_bolsa_id=c.ingreso_bolsa_id AND sw_estado='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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

	function LlamaSeleccionComponenteSangre(){
    $this->SeleccionComponenteSangre($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['bolsa'],$_REQUEST['grupoSan'],$_REQUEST['rh'],$_REQUEST['bolsaNum'],$_REQUEST['tipoComponente'],$_REQUEST['nombre'],$_REQUEST['componenteDes'],$_REQUEST['componenteId'],$_REQUEST['solicitudId'],$_REQUEST['alicuota'],$_REQUEST['cantidadAli'],$_REQUEST['nombre'],'','','',$_REQUEST['reservaId']);
		return true;
	}

	function TotalBolsasEntregar($tipoComponente,$BolsaFiltro,$grupo_sanguineoFiltro,$AlicuotaNoFiltro){
	  list($dbconn) = GetDBconn();
		$query1="SELECT count(*)
		FROM banco_sangre_bolsas a,banco_sangre_bolsas_alicuotas b,hc_tipos_componentes c,banco_sangre_albaranes z
		WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND b.sw_estado='1' AND a.tipo_componente=c.hc_tipo_componente AND z.registro_albaran_id=a.registro_albaran_id";
    $query="SELECT a.ingreso_bolsa_id,a.bolsa_id,a.grupo_sanguineo,a.rh,c.componente,a.tipo_componente,b.numero_alicuota,b.cantidad
		FROM banco_sangre_bolsas a,banco_sangre_bolsas_alicuotas b,hc_tipos_componentes c,banco_sangre_albaranes z
		WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND b.sw_estado='1' AND a.tipo_componente=c.hc_tipo_componente AND z.registro_albaran_id=a.registro_albaran_id";
		if($tipoComponente && $tipoComponente!=-1){
      $query.=" AND a.tipo_componente='$tipoComponente'";
			$query1.=" AND a.tipo_componente='$tipoComponente'";
		}
		if($BolsaFiltro){
      $query.=" AND a.bolsa_id LIKE '$BolsaFiltro%'";
			$query1.=" AND a.bolsa_id LIKE '$BolsaFiltro%'";
		}
    if($grupo_sanguineoFiltro && $grupo_sanguineoFiltro!=-1){
		  (list($grupo,$rh)=explode('/',$grupo_sanguineoFiltro));
      $query.=" AND a.grupo_sanguineo='$grupo' AND a.rh='$rh'";
			$query1.=" AND a.grupo_sanguineo='$grupo' AND a.rh='$rh'";
		}
		if($AlicuotaNoFiltro){
      $query.=" AND b.numero_alicuota='$AlicuotaNoFiltro'";
			$query1.=" AND b.numero_alicuota='$AlicuotaNoFiltro'";
		}
		if(empty($_REQUEST['conteo'])){
		  $result = $dbconn->Execute($query1);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
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
		return $vars;
	}

	function GuardarBolsaEntregar(){

	  if($_REQUEST['salir']){
		  /*$regs=$this->SeleccionBolsasSinConfirmar($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
			if(sizeof($regs)>0){
        $this->frmError["MensajeError"]="Debe Realizar la Entrega de los Componentes Sanguineos insertados de lo contrario eliminelos y de click en Salir";
				$this->BuscarBolsasParaEngregar();
				return true;
			}*/
      $this->EtregaBolsaSanguinea();
			return true;
		}
    if(!$_REQUEST['bolsa']){
		  $this->frmError["MensajeError"]="Debe Seleccionar un Componente Sanguineo";
      $this->BuscarBolsasParaEngregar();
			return true;
		}
		if(!$_REQUEST['solicitudId']){
      $solicitud='NULL';
		}else{
      $solicitud="'".$_REQUEST['solicitudId']."'";
		}
		list($dbconn) = GetDBconn();
		$dbconn->BeginTrans();
		$query="INSERT INTO banco_sangre_entrega_bolsas(ingreso_bolsa_id,paciente_id,tipo_id_paciente,
		solicitud_reserva_sangre_id,tipo_componente_id,usuario_id,fecha_registro,observaciones,
		a_quien_entrega,numero_alicuota)VALUES('".$_REQUEST['bolsa']."','".$_REQUEST['Documento']."','".$_REQUEST['TipoDocumento']."',
		$solicitud,'".$_REQUEST['componenteId']."','".UserGetUID()."','".date("Y-m-d H:i:s")."','".$_REQUEST['observaciones']."',
		'".$_REQUEST['quien_recibe']."','".$_REQUEST['alicuota']."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
     $query="UPDATE banco_sangre_bolsas_alicuotas
			SET sw_estado='5'
			WHERE ingreso_bolsa_id='".$_REQUEST['bolsa']."' AND numero_alicuota='".$_REQUEST['alicuota']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}
		}
		$dbconn->CommitTrans();
		/*$mensaje='Registro de entrega Guardado Satisfactoriamente';
    $titulo='REGISTRO DE ENTREGA';
		$accion=ModuloGetURL('app','Banco_Sangre','user','MenuConsultas');
		$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
		return true;*/
		$this->LlamaEleccionBolsaEntrega($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['reservaId']);
		return true;
	}

	function registrarErrorDigitacion(){
    list($dbconn) = GetDBconn();
		$query="INSERT INTO banco_sangre_contro_errores_digitacion(ingreso_bolsa_id,usuario_id,fecha_registro)
		VALUES('".$_REQUEST['BolsaIn']."','".UserGetUID()."','".date("Y-m-d H:i:s")."')";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $query="UPDATE banco_sangre_bolsas_alicuotas SET sw_estado='6' WHERE ingreso_bolsa_id='".$_REQUEST['BolsaIn']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->ConsultaInventariosBolsasSangre($_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componente'],$_REQUEST['FechaVmto'],$_REQUEST['estado'],$_REQUEST['cruze'],$_REQUEST['albaran']);
		return true;
	}

	function PedirDatosPaciente(){

	  if($_REQUEST['cancelar']){
		  $this->MenuConsultas();
			return true;
		}

		if($_REQUEST['aceptarComponente']){
      $this->EtregaBolsaSanguinea('','',$_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componente']);
			return true;
		}

		if(!$_REQUEST['Documento']){
			$this->frmError["MensajeError"]="El tipo de Documento del Paciente es Obligatorio";
			$this->EtregaBolsaSanguinea($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],'','','',$_REQUEST['ingresoBolsa'],$_REQUEST['numeroAlicuota']);
			return true;
		}
		$TipoId=$_REQUEST['TipoDocumento'];
		$PacienteId=$_REQUEST['Documento'];
		unset($_SESSION['PACIENTES']);
		$_SESSION['PACIENTES']['PACIENTE']['paciente_id']=$PacienteId;
		$_SESSION['PACIENTES']['PACIENTE']['tipo_id_paciente']=$TipoId;
		$_SESSION['PACIENTES']['PACIENTE']['plan_id']=2;
		$_SESSION['PACIENTES']['RETORNO']['contenedor']='app';
		$_SESSION['PACIENTES']['RETORNO']['modulo']='Banco_Sangre';
		$_SESSION['PACIENTES']['RETORNO']['tipo']='user';
		$_SESSION['PACIENTES']['RETORNO']['metodo']='BuscarBolsasParaEngregar';
		$_SESSION['PACIENTES']['RETORNO']['argumentos']=array("TipoDocumento"=>$TipoId,"Documento"=>$PacienteId,"ingresoBolsa"=>$_REQUEST['ingresoBolsa'],"numeroAlicuota"=>$_REQUEST['numeroAlicuota']);
		$this->ReturnMetodoExterno('app','Pacientes','user','PedirDatos');
		return true;
	}

	function BusquedaBolsasParaEntregar($codigoBolsa,$grupoSanguineo,$componente){
    list($dbconn) = GetDBconn();
		$query="SELECT a.ingreso_bolsa_id,a.bolsa_id,a.grupo_sanguineo,a.rh,a.fecha_vencimiento,b.componente,c.numero_alicuota,c.cantidad
		FROM banco_sangre_bolsas a,hc_tipos_componentes b,banco_sangre_bolsas_alicuotas c,banco_sangre_albaranes d
		WHERE a.tipo_componente=b.hc_tipo_componente AND a.ingreso_bolsa_id=c.ingreso_bolsa_id AND c.sw_estado='1' AND
    d.registro_albaran_id=a.registro_albaran_id";
		if($codigoBolsa){
      $query.=" AND a.bolsa_id='".$codigoBolsa."'";
		}
		if($grupoSanguineo!=-1 && !empty($grupoSanguineo)){
		  (list($grupo,$rh)=explode('/',$grupoSanguineo));
      $query.=" AND a.grupo_sanguineo='".$grupo."' AND a.rh='".$rh."'";
		}
		if($componente!=-1 && !empty($componente)){
      $query.=" AND a.tipo_componente='".$componente."'";
		}
		$query.=" ORDER BY a.ingreso_bolsa_id,c.numero_alicuota";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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

	function DatosBolsaSeleccion($ingresoBolsa,$numeroAlicuota){
    list($dbconn) = GetDBconn();
		$query="SELECT a.ingreso_bolsa_id,a.bolsa_id,a.grupo_sanguineo,a.rh,a.fecha_vencimiento,b.hc_tipo_componente,b.componente,c.numero_alicuota,c.cantidad
		FROM banco_sangre_bolsas a,hc_tipos_componentes b,banco_sangre_bolsas_alicuotas c,banco_sangre_albaranes d
		WHERE a.ingreso_bolsa_id='".$ingresoBolsa."' AND a.tipo_componente=b.hc_tipo_componente AND a.ingreso_bolsa_id=c.ingreso_bolsa_id AND c.numero_alicuota='".$numeroAlicuota."' AND
		c.sw_estado='1' AND d.registro_albaran_id=a.registro_albaran_id";
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

	function EditarDatosComponente(){
    $accion=ModuloGetURL('app','Banco_Sangre','user','GuardaCambiosComponente',array("componenteId"=>$_REQUEST['componenteId'],"codigoBolsa"=>$_REQUEST['codigoBolsa'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"componenteDes"=>$_REQUEST['componenteDes'],"FechaVmto"=>$_REQUEST['FechaVmto'],"estado"=>$_REQUEST['estado'],"cruze"=>$_REQUEST['cruze'],"albaranDes"=>$_REQUEST['albaranDes']));
		if($_REQUEST['fecha_vencimiento']){
		(list($ano,$mes,$dia)=explode('-',$_REQUEST['fecha_vencimiento']));
		$_REQUEST['fecha_vencimiento']=$dia.'-'.$mes.'-'.$ano;
		}
		if($_REQUEST['fecha_extraccion']){
		(list($ano,$mes,$dia)=explode('-',$_REQUEST['fecha_extraccion']));
		$_REQUEST['fecha_extraccion']=$dia.'-'.$mes.'-'.$ano;
		}
		$this->IngresoBolsaSangre($_REQUEST['bolsa_id'],$_REQUEST['sello_calidad'],$_REQUEST['componente'],$_REQUEST['grupo_sanguineo'].'/'.$_REQUEST['rh'],$_REQUEST['fecha_vencimiento'],$_REQUEST['nombre_tercero'],$_REQUEST['entidad_origen'],$_REQUEST['fecha_extraccion'],'',$_REQUEST['albaran'],$accion);
		return true;
	}

	function GuardaCambiosComponente(){

		if($_REQUEST['Buscar']){
		  $accion=ModuloGetURL('app','Banco_Sangre','user','GuardaCambiosComponente',array("componenteId"=>$_REQUEST['componenteId'],"codigoBolsa"=>$_REQUEST['codigoBolsa'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"componenteDes"=>$_REQUEST['componenteDes'],"FechaVmto"=>$_REQUEST['FechaVmto'],"estado"=>$_REQUEST['estado'],"cruze"=>$_REQUEST['cruze'],"albaranDes"=>$_REQUEST['albaranDes']));
      $this->SeleccionEntidad($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],'',$_REQUEST['albaran'],$accion);
			return true;
		}
		if($_REQUEST['cancelar']){
      $this->ConsultaInventariosBolsasSangre($_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componenteDes'],$_REQUEST['FechaVmto'],$_REQUEST['estado'],$_REQUEST['cruze'],$_REQUEST['albaranDes']);
			return true;
		}

		if($_REQUEST['calcular']){
      list($dbconn) = GetDBconn();
			$query="SELECT dias_calculo_fecha_extraccion
			FROM hc_tipos_componentes
			WHERE hc_tipo_componente='".$_REQUEST['tipoComponente']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar banco_sangre_bolsas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				$dbconn->RollbackTrans();
				return false;
			}else{
				$datos=$result->RecordCount();
				if($datos){
					$vars=$result->GetRowAssoc($toUpper=false);
					if(strlen($_REQUEST['FechaVencimiento'])==10){
						$_REQUEST['FechaVencimiento']=ereg_replace("-","/",$_REQUEST['FechaVencimiento']);
						(list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaVencimiento']));
					}elseif(strlen($_REQUEST['FechaVencimiento'])==8){
						$dia=substr($_REQUEST['FechaVencimiento'],0,2);
						$mes=substr($_REQUEST['FechaVencimiento'],2,2);
						$ano=substr($_REQUEST['FechaVencimiento'],4,4);
					}
					$FechaExtraccion=date('d/m/Y',mktime(0,0,0,$mes,($dia-$vars['dias_calculo_fecha_extraccion']),$ano));
				}
			}
			$accion=ModuloGetURL('app','Banco_Sangre','user','GuardaCambiosComponente',array("componenteId"=>$_REQUEST['componenteId'],"codigoBolsa"=>$_REQUEST['codigoBolsa'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"componenteDes"=>$_REQUEST['componenteDes'],"FechaVmto"=>$_REQUEST['FechaVmto'],"estado"=>$_REQUEST['estado'],"cruze"=>$_REQUEST['cruze'],"albaranDes"=>$_REQUEST['albaranDes']));
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$FechaExtraccion,$_REQUEST['origen'],$_REQUEST['albaran'],$accion);
			return true;
		}

		if(!$_REQUEST['BolsaId'] || !$_REQUEST['selloCalidad'] || $_REQUEST['grupo_sanguineo']==-1 || !$_REQUEST['codigo_sgsss'] ||
		!$_REQUEST['descipcion_sgsss'] || !$_REQUEST['FechaVencimiento'] || $_REQUEST['tipoComponente']==-1 || !$_REQUEST['origen']){
      if(!$_REQUEST['BolsaId']){$this->frmError["BolsaId"]=1;}
			if(!$_REQUEST['selloCalidad']){$this->frmError["selloCalidad"]=1;}
      if($_REQUEST['grupo_sanguineo']==-1){$this->frmError["grupo_sanguineo"]=1;}
			if(!$_REQUEST['codigo_sgsss']){$this->frmError["codigo_sgsss"]=1;}
			if(!$_REQUEST['descipcion_sgsss']){$this->frmError["codigo_sgsss"]=1;}
      if(!$_REQUEST['FechaVencimiento']){$this->frmError["FechaVencimiento"]=1;}
      if($_REQUEST['tipoComponente']==-1){$this->frmError["tipoComponente"]=1;}
			if(!$_REQUEST['origen']){$this->frmError["origen"]=1;}
			$this->frmError["MensajeError"]="Complete los Datos.";
			$accion=ModuloGetURL('app','Banco_Sangre','user','GuardaCambiosComponente',array("componenteId"=>$_REQUEST['componenteId'],"codigoBolsa"=>$_REQUEST['codigoBolsa'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"componenteDes"=>$_REQUEST['componenteDes'],"FechaVmto"=>$_REQUEST['FechaVmto'],"estado"=>$_REQUEST['estado'],"cruze"=>$_REQUEST['cruze'],"albaranDes"=>$_REQUEST['albaranDes']));
			$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$_REQUEST['FechaExtraccion'],$_REQUEST['origen'],$_REQUEST['albaran'],$accion);
			return true;
		}
		if(strlen($_REQUEST['FechaVencimiento'])==10){
      $_REQUEST['FechaVencimiento']=ereg_replace("-","/",$_REQUEST['FechaVencimiento']);
      (list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaVencimiento']));
		}elseif(strlen($_REQUEST['FechaVencimiento'])==8){
      $dia=substr($_REQUEST['FechaVencimiento'],0,2);
			$mes=substr($_REQUEST['FechaVencimiento'],2,2);
			$ano=substr($_REQUEST['FechaVencimiento'],4,4);
		}
		$fechaVence=$ano.'-'.$mes.'-'.$dia;
		(list($grupoSanguineo,$rh)=explode('/',$_REQUEST['grupo_sanguineo']));

		if($_REQUEST['FechaExtraccion']){
		$_REQUEST['FechaExtraccion']=ereg_replace("-","/",$_REQUEST['FechaExtraccion']);
		(list($dia1,$mes1,$ano1)=explode('/',$_REQUEST['FechaExtraccion']));
		$fechaExtrac="'$ano1-$mes1-$dia1'";
		}else{
      $fechaExtrac='NULL';
		}
		list($dbconn) = GetDBconn();
		$query="UPDATE banco_sangre_bolsas SET bolsa_id='".$_REQUEST['BolsaId']."',sello_calidad='".$_REQUEST['selloCalidad']."',
		grupo_sanguineo='".$grupoSanguineo."',rh='".$rh."',
		fecha_vencimiento='".$fechaVence."',tipo_componente='".$_REQUEST['tipoComponente']."',fecha_extraccion=".$fechaExtrac.",
		motivo_insercion='".$_REQUEST['origen']."'
		WHERE ingreso_bolsa_id='".$_REQUEST['componenteId']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}
		$this->ConsultaInventariosBolsasSangre($_REQUEST['codigoBolsa'],$_REQUEST['grupoSanguineo'],$_REQUEST['componenteDes'],$_REQUEST['FechaVmto'],$_REQUEST['estado'],$_REQUEST['cruze'],$_REQUEST['albaranDes']);
		return true;
	}

	function CalculoFechaExtraccion(){
    list($dbconn) = GetDBconn();
		$query="SELECT dias_calculo_fecha_extraccion
		FROM hc_tipos_componentes
		WHERE hc_tipo_componente='".$_REQUEST['tipoComponente']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$dbconn->RollbackTrans();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
				$vars=$result->GetRowAssoc($toUpper=false);
				if(strlen($_REQUEST['FechaVencimiento'])==10){
					$_REQUEST['FechaVencimiento']=ereg_replace("-","/",$_REQUEST['FechaVencimiento']);
					(list($dia,$mes,$ano)=explode('/',$_REQUEST['FechaVencimiento']));
				}elseif(strlen($_REQUEST['FechaVencimiento'])==8){
					$dia=substr($_REQUEST['FechaVencimiento'],0,2);
					$mes=substr($_REQUEST['FechaVencimiento'],2,2);
					$ano=substr($_REQUEST['FechaVencimiento'],4,4);
				}
				$FechaExtraccion=date('d/m/Y',mktime(0,0,0,$mes,($dia-$vars['dias_calculo_fecha_extraccion']),$ano));
			}
		}
		$this->IngresoBolsaSangre($_REQUEST['BolsaId'],$_REQUEST['selloCalidad'],$_REQUEST['tipoComponente'],$_REQUEST['grupo_sanguineo'],$_REQUEST['FechaVencimiento'],$_REQUEST['descipcion_sgsss'],$_REQUEST['codigo_sgsss'],$FechaExtraccion,$_REQUEST['origen'],$_REQUEST['albaran']);
		return true;
	}

	function SeleccionBolsasSinConfirmar($TipoId,$PacienteId){
    list($dbconn) = GetDBconn();
		if($TipoId && $PacienteId){
      $concat=" WHERE tipo_id_paciente='$TipoId' AND paciente_id='$PacienteId'";
		}
		$query="SELECT a.ingreso_bolsa_id,a.numero_alicuota,b.bolsa_id,b.sello_calidad,b.grupo_sanguineo,b.rh,b.fecha_vencimiento,b.tipo_componente,c.componente
		FROM ((SELECT ingreso_bolsa_id,numero_alicuota FROM banco_sangre_entrega_bolsas $concat) EXCEPT (SELECT ingreso_bolsa_id,numero_alicuota FROM banco_sangre_entrega_bolsas_enrega_confirmacion)) a,
		banco_sangre_bolsas b,hc_tipos_componentes c,banco_sangre_albaranes d
		WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND b.tipo_componente=c.hc_tipo_componente AND d.registro_albaran_id=b.registro_albaran_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
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

	function EliminarBolsaEntrega(){
    list($dbconn) = GetDBconn();
		$query="DELETE FROM banco_sangre_entrega_bolsas WHERE numero_alicuota='".$_REQUEST['alicuota']."' AND ingreso_bolsa_id='".$_REQUEST['ingresoBolsa']."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $query="UPDATE banco_sangre_bolsas_alicuotas
			SET sw_estado='1'
			WHERE ingreso_bolsa_id='".$_REQUEST['ingresoBolsa']."' AND numero_alicuota='".$_REQUEST['alicuota']."'";
			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al consultar banco_sangre_bolsas";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		$this->LlamaEleccionBolsaEntrega($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
		return true;
	}

	function LlamaEleccionBolsaEntrega($TipoDocumento,$Documento,$reservaId){
	  list($dbconn) = GetDBconn();
		$query="SELECT z.tipo_id_paciente,z.paciente_id,z.primer_nombre||' '||z.segundo_nombre||' '||z.primer_apellido||' '||z.segundo_apellido as nombre,
		a.solicitud_reserva_sangre_id,b.tipo_componente_id,b.cantidad_componente,c.componente,c.sw_cruze,b.sw_estado as confirmado
		FROM pacientes z
		LEFT JOIN banco_sangre_reserva a ON (a.tipo_id_paciente=z.tipo_id_paciente AND a.paciente_id=z.paciente_id AND a.sw_estado='1')
		LEFT JOIN banco_sangre_reserva_detalle b ON(a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND b.sw_estado='2')
		LEFT JOIN hc_tipos_componentes c ON(b.tipo_componente_id=c.hc_tipo_componente)
		WHERE z.tipo_id_paciente='".$TipoDocumento."' AND z.paciente_id='".$Documento."' ORDER BY c.hc_tipo_componente";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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
		$this->EleccionBolsaEntrega($vars,$_REQUEST['bolsa'],$_REQUEST['grupoSan'],$_REQUEST['rh'],$_REQUEST['bolsaNum'],$_REQUEST['componenteDes'],$_REQUEST['componenteId'],$_REQUEST['solicitudId'],$_REQUEST['alicuota'],$_REQUEST['cantidadAli'],$reservaId);
		return true;
	}

	function LlamaConfirmarEntrega(){
    $this->ConfirmarEntrega($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombre']);
		return true;
	}

	function RegistroAutenticacionUsuario(){
	  IncludeLib("users.inc.php");
    if($_REQUEST['Menu']){
      $this->MenuConsultas();
			return true;
		}
		if($_REQUEST['Buscar']){
      $this->ConfirmarEntrega($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombre'],$_REQUEST['todos']);
			return true;
		}
		$select=$_REQUEST['seleccion'];
		if(sizeof($select)<1){
      $this->frmError["MensajeError"]="Debe Seleccionar Componentes para la Entrega";
      $this->ConfirmarEntrega($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombre'],$_REQUEST['todos']);
			return true;
		}
		//$usuario=UserValidarPasswd($_REQUEST['login'],$_REQUEST['passwordd']);
		//if($usuario){
		if(!empty($_REQUEST['DocumentoAutentic']) && $this->ExisteUsuarioAutentic($_REQUEST['TipoDocumentoAutentic'],$_REQUEST['DocumentoAutentic'])==1){
		  list($dbconn) = GetDBconn();
		  for($i=0;$i<sizeof($select);$i++){
				(list($bolsaNum,$albarnNum)=explode('/',$select[$i]));
				$query="INSERT INTO banco_sangre_entrega_bolsas_enrega_confirmacion(ingreso_bolsa_id,numero_alicuota,tipo_id_identificacion,identificacion)
				VALUES('$bolsaNum','$albarnNum','".$_REQUEST['TipoDocumentoAutentic']."','".$_REQUEST['DocumentoAutentic']."')";
				$result = $dbconn->Execute($query);
				if($dbconn->ErrorNo() != 0){
					$this->error = "Error al consultar banco_sangre_bolsas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			if(empty($_SESSION['RESERVA_SANGRE']['RETORNO'])){
				$mensaje='Registro de entrega Guardado Satisfactoriamente';
				$titulo='REGISTRO DE ENTREGA';
				$accion=ModuloGetURL('app','Banco_Sangre','user','MenuConsultas');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
      }else{
        $mensaje='Registro de entrega Guardado Satisfactoriamente';
				$titulo='REGISTRO DE ENTREGA';
				$accion=ModuloGetURL('app','Solicitud_Reserva_Sangre','user','CompatibilidadSangre');
				$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
				return true;
			}
		}else{
      //$this->frmError["MensajeError"]="El usuario No existe en la base de Datos";
			$this->frmError["MensajeError"]="Identificacion del usuario que Recibe Incorrecta";
      $this->ConfirmarEntrega($_REQUEST['TipoDocumento'],$_REQUEST['Documento'],$_REQUEST['nombre'],$_REQUEST['todos']);
			return true;
		}
	}

	function ExisteUsuarioAutentic($TipoDocumentoAutentic,$DocumentoAutentic){
    list($dbconn) = GetDBconn();
    $query="SELECT 1
		FROM banco_sangre_autorizados_entregas_componentes
		WHERE tipo_id_identificacion='".$TipoDocumentoAutentic."' AND identificacion='".$DocumentoAutentic."'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      return $result->fields[0];
		}
		return 0;
	}

	function SeleccionBolsasSinConfirmarParametros($TipoId,$PacienteId,$nombre,$todos){
    list($dbconn) = GetDBconn();
		if($TipoId && $PacienteId){
      $concat.=" AND x.tipo_id_paciente='$TipoId' AND x.paciente_id='$PacienteId'";
		}
		if($nombre){
			$concat.=" AND y.primer_nombre||' '||y.segundo_nombre||' '||y.primer_apellido||' '||y.segundo_apellido LIKE '%".strtoupper($_REQUEST['nombre'])."%'";
		}
		$query="SELECT a.ingreso_bolsa_id,a.numero_alicuota,b.bolsa_id,b.sello_calidad,b.grupo_sanguineo,b.rh,b.fecha_vencimiento,b.tipo_componente,c.componente,
		f.primer_nombre||' '||f.segundo_nombre||' '||primer_apellido||' '||segundo_apellido as nombre,f.tipo_id_paciente,f.paciente_id
		FROM ((SELECT x.ingreso_bolsa_id,x.numero_alicuota FROM banco_sangre_entrega_bolsas x,pacientes y WHERE x.tipo_id_paciente=y.tipo_id_paciente AND x.paciente_id=y.paciente_id $concat) EXCEPT (SELECT ingreso_bolsa_id,numero_alicuota FROM banco_sangre_entrega_bolsas_enrega_confirmacion)) a
		LEFT JOIN banco_sangre_entrega_bolsas e ON(e.ingreso_bolsa_id=a.ingreso_bolsa_id AND e.numero_alicuota=a.numero_alicuota)
		LEFT JOIN pacientes f ON(e.tipo_id_paciente=f.tipo_id_paciente AND e.paciente_id=f.paciente_id),
		banco_sangre_bolsas b,hc_tipos_componentes c,banco_sangre_albaranes d
		WHERE a.ingreso_bolsa_id=b.ingreso_bolsa_id AND b.tipo_componente=c.hc_tipo_componente AND d.registro_albaran_id=b.registro_albaran_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al consultar banco_sangre_bolsas";
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

	function ReservasSinConfirmar($TipoDocumento,$Documento){
    list($dbconn) = GetDBconn();
		//confirmadas
    $query="SELECT DISTINCT a.solicitud_reserva_sangre_id,a.paciente_id,a.tipo_id_paciente,
		(SELECT primer_nombre||' '||segundo_nombre||' '||primer_apellido||' '||segundo_apellido FROM pacientes WHERE a.paciente_id=paciente_id AND a.tipo_id_paciente=tipo_id_paciente) as nombre,
		c.grupo_sanguineo,c.rh
		FROM banco_sangre_reserva a
		LEFT JOIN pacientes_grupo_sanguineo c ON(c.paciente_id=a.paciente_id AND c.tipo_id_paciente=a.tipo_id_paciente),
		banco_sangre_reserva_detalle b
		WHERE a.solicitud_reserva_sangre_id=b.solicitud_reserva_sangre_id AND b.sw_estado='2' AND a.sw_estado='1'";
		if($TipoDocumento && $Documento){
      $query.=" AND a.paciente_id='$Documento' AND a.tipo_id_paciente='$TipoDocumento'";
		}
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
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

	function DatosPacienteBD($TipoDocumento,$Documento){
    list($dbconn) = GetDBconn();
		$query="SELECT a.fecha_nacimiento,b.grupo_sanguineo,b.rh
		FROM pacientes a
		LEFT JOIN pacientes_grupo_sanguineo b ON(a.tipo_id_paciente=b.tipo_id_paciente AND a.paciente_id=b.paciente_id)
		WHERE a.tipo_id_paciente='$TipoDocumento' AND a.paciente_id='$Documento'";
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

	function unidadesEntregadasSolicitud($solicitudId,$tipoComponente){
    list($dbconn) = GetDBconn();
		$query="SELECT count(*)
		FROM banco_sangre_entrega_bolsas A,banco_sangre_bolsas B,banco_sangre_entrega_bolsas_enrega_confirmacion C
		WHERE A.solicitud_reserva_sangre_id='$solicitudId' AND A.ingreso_bolsa_id=B.ingreso_bolsa_id AND
		B.tipo_componente='$tipoComponente' AND A.ingreso_bolsa_id=C.ingreso_bolsa_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
      $datos=$result->RecordCount();
			if($datos){
        return $result->fields[0];
			}
		}
		return true;
	}







}//fin clase user


/*select distinct quirofano_id
from qx_equipos_especiales
where departamento='0501'
and tipo_equipo_id IN ('01','03')*/


// borre la tabla e inserte estos insert solo con los registros no repetidos
//   select distinct 'INSERT INTO derechos_porcentaje VALUES("' || forma_calculo ||
//   '","' || tipo_derecho || '","' || via_acceso  || '","' || secuencia || '","' ||
//   porcentaje || '","' || valor || '")' FROM derechos_porcentaje ;
?>

