<?php

/**
 * $Id: app_Voucher_FacturasProfesionales_user.php,v 1.13 2007/07/12 21:49:15 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * MODULO para el Manejo de Inventarios en el Sistema
 */

/**
*Contiene los metodos para realizar la relacion de voucher de honorarios medicos con las facturas de los profesionales
*/

class app_Voucher_FacturasProfesionales_user extends classModulo
{
    var $limit;
    var $conteo;

  /**
  * Funcion contructora que inicializa las variables
  * @return boolean
  */


  function app_Voucher_FacturasProfesionales_user()
  {
    $this->limit=GetLimitBrowser();
    return true;
  }
  
  /**
  * Funcion principal que se encarga de llamar al menu para la seleccion de la empresa
  * @return boolean
  */

  function main(){      
    if(!$this->FrmLogueoEmpresa()){
      return false;
    }
    return true;
  }
    
    
  /**
  * Funcion que consulta en la base de datos los permisos del usuario para trabajar en una empresa
  * @return array
  */
  function LogueoEmpresa()
  {
      list($dbconn) = GetDBconn();
      GLOBAL $ADODB_FETCH_MODE;
      $query = "SELECT x.empresa_id,y.razon_social as descripcion1
                FROM userpermisos_relacion_voucher_facturas as x,empresas as y
                WHERE x.usuario_id = ".UserGetUID()." AND x.empresa_id=y.empresa_id";
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      $result = $dbconn->Execute($query);
      if($result->EOF){
          $this->error = "Error al ejecutar la consulta.<br>";
          $this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
          return false;
      }else{
        while ($data = $result->FetchRow()) {
            $datos[$data['descripcion1']]=$data;
        }  
        $mtz[0]="EMPRESA";          
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
      $_SESSION['VOUCHER_FACTURAS']['Empresa']=$_REQUEST['datos_query']['empresa_id'];
      $_SESSION['VOUCHER_FACTURAS']['NombreEmp']=$_REQUEST['datos_query']['descripcion1'];        
      $this->Menu();
      return true;
  }
  
  function RelacionarFactura(){
    unset($_SESSION['op']);
		$this->FrmRelacionarFactura();  
    return true;
  }
	
  function ImpresionFactura()
	{
    $_SESSION['op']=1;
		$this->FrmImpresionFactura();  
    return true;
  }  
  
  function SeleccionarRelacionFactura(){
    
    if($_REQUEST['Volver']){
      $this->Menu();
      return true;
    }
    if($_REQUEST['Filtrar']){
      if(empty($_REQUEST['nombreProf']) || empty($_REQUEST['uidProf']) || empty($_REQUEST['loginProf']) || empty($_REQUEST['IdProf'])){
        $this->frmError["MensajeError"]="SELECCIONE ALGUN PARAMETRO DE BUSQUEDA";
      }
      $this->FrmRelacionarFactura();
      return true;
    }
    if(empty($_REQUEST['NoFactura'])){
      $this->frmError["MensajeError"]="DIGITE EL NUMERO DE LA FACTURA";
      $this->FrmRelacionarFactura();
      return true;
    }elseif(empty($_REQUEST['Profesional'])){
      $this->frmError["MensajeError"]="SELECCIONE EL PROFESIONAL";
      $this->FrmRelacionarFactura();
      return true;
    }
    $this->SeleccionarVoucherHonorarios($_REQUEST['NoFactura'],$_REQUEST['Profesional']);
    return true;
  
  }
	
	function ResultadoImpresion()
	{
		if($_REQUEST['Filtrar'])
		{
			if(empty($_REQUEST['NoFactura']) && empty($_REQUEST['nombreProf']) && empty($_REQUEST['uidProf']) && empty($_REQUEST['loginProf']) && empty($_REQUEST['IdProf']) && empty($_REQUEST['fecha_ini']) && empty($_REQUEST['fecha_fin']))
			{
				$this->frmError["MensajeError"]="SELECCIONE ALGUN PARAMETRO DE BUSQUEDA";
				$this->FrmImpresionFactura($_REQUEST);
				return true;
			}elseif(!empty($_REQUEST['fecha_ini']))
			{
				if(empty($_REQUEST['fecha_fin']))
				{
					$this->frmError["MensajeError"]="INGRESE LA FECHA FINAL";
					$this->FrmImpresionFactura($_REQUEST);
					return true;
				}
			}
			elseif(!empty($_REQUEST['fecha_fin']))
			{
				if(empty($_REQUEST['fecha_ini']))
				{
					$this->frmError["MensajeError"]="INGRESE LA FECHA INICIAL";
					$this->FrmImpresionFactura($_REQUEST);
					return true;
				}
			}
		}
		if($_REQUEST['buscar'])
		{
			if(empty($_REQUEST['nombreProf']) && empty($_REQUEST['uidProf']) && empty($_REQUEST['loginProf']) && empty($_REQUEST['IdProf']))
			{
				$this->frmError["MensajeError"]="SELECCIONE ALGUN PARAMETRO DE BUSQUEDA PARA PROFEISONAL";
			}
			elseif(empty($_REQUEST['Profesional']))
			{
				$this->frmError["MensajeError"]="SELECCIONE EL PROFESIONAL";
			}
			$this->FrmImpresionFactura($_REQUEST);
			return true;
		}
		$_REQUEST['fecha_ini']=$this->FechaStamp($_REQUEST['fecha_ini']);
		$_REQUEST['fecha_fin']=$this->FechaStamp($_REQUEST['fecha_fin']);

    $this->FrmResultadoImpresion($_REQUEST['busqueda_por'],$_REQUEST['NoFactura'],$_REQUEST['Profesional'],$_REQUEST['fecha_ini'],$_REQUEST['fecha_fin']);
    return true;
  }
	
  
  function ObtenerVoucher($NoFactura,$Profesional,$pffiltro,$numfiltro,$fechaFil){
    $filtro='';
    list($dbconn) = GetDBconn();
    (list($tipoProf,$Prof)=explode('||//',$Profesional)); 
    if($pffiltro){
      $filtro.=" AND a.prefijo='".$pffiltro."'";
    }
    if($numfiltro){
      $filtro.=" AND a.numero='".$numfiltro."'";
    } 
    if($fechaFil){
      (list($dia,$mes,$ano)=explode('/',$fechaFil));
      $filtro.=" AND date(a.fecha_registro) ='".$ano."-".$mes."-".$dia."'";
    }     
			
		$query="SELECT count(*),prefijo,numero
						FROM tmp_voucher_honorarios_cuentas_x_pagar
						WHERE numero_factura_id='".$_REQUEST['NoFactura']."'
						AND tercero_id='$Prof' 
						AND tipo_id_tercero='$tipoProf'
						GROUP BY 2,3";
		
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - ObtenerVoucher SQL01";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		if($result->fields[0])
			$tabla_fph="tmp_voucher_honorarios_facturas_profesionales";
		else
			$tabla_fph="voucher_honorarios_facturas_profesionales";
		
			
		$query ="SELECT a.prefijo,
										a.numero,
										b.descripcion,
										a.valor_honorario,
										date(a.fecha_registro) as fecha,
										a.numerodecuenta,
										a.valor_real,
										d.valor as valor_nc,
										e.valor as valor_nd
							FROM voucher_honorarios a
							LEFT JOIN $tabla_fph as c 
							ON
							(
								a.prefijo=c.prefijo 
								AND a.numero=c.numero
							)
							LEFT JOIN voucher_honorarios_nc as d 
							ON
							(
								a.prefijo=d.prefijo_voucher 
								AND a.numero=d.numero_voucher 
								AND d.estado='1'
							)
							LEFT JOIN voucher_honorarios_nd as e 
							ON
							(
								a.prefijo=e.prefijo_voucher 
								AND a.numero=e.numero_voucher
								AND e.estado='1'
							),
							cups b
							WHERE a.estado='1' 
							AND a.empresa_id='".$_SESSION['VOUCHER_FACTURAS']['Empresa']."' 
							AND a.tipo_id_tercero='".$tipoProf."' 
							AND a.tercero_id='".$Prof."'
							AND a.cargo_cups=b.cargo
							AND a.valor_real > 0
							AND (a.empresa_id,a.prefijo,a.numero) 
																						NOT IN 
																						(
 																							SELECT empresa_id,prefijo,numero
																							FROM $tabla_fph
																						)
							$filtro
							ORDER BY a.fecha_registro DESC";         
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
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
			$result->Close();
			return $vars;                     
  }
	
	
	function TmpGuardarRelacionVoucherFactura()
	{
		if($_REQUEST['Volver'])
		{
			$this->FrmRelacionarFactura();
			return true;
		}
		
		list($dbconn) = GetDBconn();
    //$dbconn->debug = true;
		(list($tipoProf,$Prof,$nomProf)=explode('||//',$_REQUEST['Profesional'])); 
		
		if($_REQUEST['generar_cxp'])
		{
			$query = "SELECT count(*)
								FROM voucher_honorarios_cuentas_x_pagar
								WHERE numero_factura_id='".$_REQUEST['NoFactura']."'
								AND tipo_id_tercero='$tipoProf'
								AND tercero_id='$Prof';";
				
			$result = $dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - TmpGuardarRelacionVoucherFactura REAL2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			if(!$result->fields[0])
			{
				$query = "INSERT INTO voucher_honorarios_cuentas_x_pagar
									(
										empresa_id,
										prefijo,
										numero,
										numero_factura_id,
										tercero_id,
										tipo_id_tercero,
										documento_id,
										valor,
										usuario_id
									)
									(
										SELECT 
											empresa_id,
											prefijo,
											numero,
											numero_factura_id,
											tercero_id,
											tipo_id_tercero,
											documento_id,
											".$_REQUEST['valor_fact'].",
											usuario_id
										FROM tmp_voucher_honorarios_cuentas_x_pagar
										WHERE numero_factura_id='".$_REQUEST['NoFactura']."'
										AND tipo_id_tercero='$tipoProf'
										AND tercero_id='$Prof'
									);
									";
				
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - TmpGuardarRelacionVoucherFactura REAL2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
			
			$query = "SELECT 
											empresa_id,
											prefijo,
											numero,
											numero_factura_id,
											tercero_id,
											tipo_id_tercero,
											documento_id,
											".$_REQUEST['valor_fact'].",
											usuario_id
								FROM 	tmp_voucher_honorarios_cuentas_x_pagar
								WHERE numero_factura_id='".$_REQUEST['NoFactura']."'
								AND 	tipo_id_tercero='$tipoProf'
								AND 	tercero_id='$Prof'
								";
			
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - tmp_voucher_honorarios_cuentas_x_pagar sql 2";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			else
			{  
				if($result->RecordCount()>0)
				{
					while (!$result->EOF) 
					{
						$datos_tmp[]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
			
			foreach($datos_tmp as $valor)
			{
				$query = "INSERT INTO voucher_honorarios_facturas_profesionales
									(
										empresa_id,
										prefijo,
										numero,
										prefijo_cxp,
										numero_cxp
									)
									(
										SELECT 
											empresa_id,
											prefijo,
											numero,
											prefijo_cxp,
											numero_cxp
										FROM tmp_voucher_honorarios_facturas_profesionales
										WHERE empresa_id='".$valor['empresa_id']."'
										AND prefijo_cxp='".$valor['prefijo']."'
										AND numero_cxp='".$valor['numero']."'
									);
								";
				
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - TmpGuardarRelacionVoucherFactura sql_real";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
				
			$query = "DELETE FROM tmp_voucher_honorarios_cuentas_x_pagar
								WHERE numero_factura_id='".$_REQUEST['NoFactura']."'
								AND tipo_id_tercero='$tipoProf'
								AND tercero_id='$Prof'";
			
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - TmpGuardarRelacionVoucherFactura REAL";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$this->mensaje="CUENTA POR PAGAR GENERADA EXITOSAMENTE";
			$this->PermisoE=0;
		}
		
		
		if($_REQUEST['Seleccion'])
		{
			 
			$query="SELECT count(*),prefijo,numero
							FROM tmp_voucher_honorarios_cuentas_x_pagar
							WHERE numero_factura_id='".$_REQUEST['NoFactura']."'
							AND tercero_id='$Prof' 
							AND tipo_id_tercero='$tipoProf'
							GROUP BY 2,3";
			
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) 
			{
				$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - GuardarRelacionVoucherFactura SQL3";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			
			$prefijo=$result->fields[1];
			$numero=$result->fields[2];
			
			if($result->fields[0]==0)
			{
				$query = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE;
									SELECT a.documento_id_cxp,b.prefijo,b.numeracion 
									FROM 	voucher_honorarios_parametros a,
												documentos b
									WHERE a.empresa_id='".$_SESSION['VOUCHER_FACTURAS']['Empresa']."'
												AND a.documento_id_cxp=b.documento_id;";
		
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - GuardarRelacionVoucherFactura SQL1";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{  
					if($result->RecordCount()>0)
					{
						while (!$result->EOF) 
						{
							list($documento_id,$prefijo,$numero)=$result->FetchRow();
							$result->MoveNext();
						}
					}
				}
				$result->Close();
				
				$query = "INSERT INTO tmp_voucher_honorarios_cuentas_x_pagar
									(
										empresa_id,
										prefijo,
										numero,
										numero_factura_id,
										tercero_id,
										tipo_id_tercero,
										documento_id,
										prefijo_orden,
										numero_orden,
										usuario_id
									)
									VALUES
									(
										'".$_SESSION['VOUCHER_FACTURAS']['Empresa']."',
										'".$prefijo."',
										".$numero.",
										'".$_REQUEST['NoFactura']."',
										'".$Prof."',
										'".$tipoProf."',
										$documento_id,
										NULL,
										NULL,
										".UserGetUID()."
									)";         
				
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - GuardarRelacionVoucherFactura SQL4 ".$query;
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$query="UPDATE documentos SET
									numeracion=numeracion+1
									WHERE documento_id=".$documento_id."
									AND empresa_id='".$_SESSION['VOUCHER_FACTURAS']['Empresa']."';";
			
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) 
					{
						$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - GuardarRelacionVoucherFactura SQL2";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
			}
			
			$vector=$_REQUEST['Seleccion'];
			foreach($vector as $indice=>$valor)
			{
				(list($prefijo_v,$numero_v)=explode('||//',$valor));
				
				$query  = "INSERT INTO tmp_voucher_honorarios_facturas_profesionales
									(
										empresa_id,
										prefijo,
										numero,
										prefijo_cxp,
										numero_cxp
									)
									VALUES
									(
										'".$_SESSION['VOUCHER_FACTURAS']['Empresa']."',
										'".$prefijo_v."',
										".$numero_v.",
										'".$prefijo."',
										".$numero."
									);";         
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - GuardarRelacionVoucherFactura SQL5";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			}
		}
		$this->PermisoE=1;
		$this->SeleccionarVoucherHonorarios($_REQUEST['NoFactura'],$_REQUEST['Profesional']);
		return true;    
  }
	
	
  function ObtenerVoucherAsociadosFactura($NoFactura=null,$Profesional=null,$fecha_ini=null,$fecha_fin=null)
	{
    list($dbconn) = GetDBconn();
    
		if(!empty($NoFactura))
		{
			$datFactura=" AND a.numero_factura_id='".$NoFactura."' "; 
		}
		
		if(!empty($Profesional))
		{
			(list($tipoProf,$Prof)=explode('||//',$Profesional));   
			$datprofesional="AND a.tipo_id_tercero='".$tipoProf."' AND a.tercero_id='".$Prof."'"; 
		}
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$datFechas="AND date(a.fecha_registro)>='".$fecha_ini."' 
									AND date(a.fecha_registro)<='".$fecha_fin."'";
		}
		
		
		$query ="	SELECT count(*) 
						 	FROM tmp_voucher_honorarios_cuentas_x_pagar as a
						 	WHERE estado='1'
						 	$datFactura
							$datprofesional;";
		
		$result = $dbconn->Execute($query);
    
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - ObtenerVoucherAsociadosFactura";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
    }
		
		if($result->fields[0])
		{
			$tabla_cxp="tmp_voucher_honorarios_cuentas_x_pagar";
			$tabla_fph="tmp_voucher_honorarios_facturas_profesionales";
			$this->PermisoE=1;
		}
		else
		{
			$tabla_cxp="voucher_honorarios_cuentas_x_pagar";
			$tabla_fph="voucher_honorarios_facturas_profesionales";
			$this->PermisoE=0;
		}
		
		 $query = "SELECT a.numero_factura_id,
										c.prefijo,
										c.numero,
										c.valor_honorario,
										c.numerodecuenta,
										a.tipo_id_tercero,
										a.tercero_id,
										f.nombre,
										c.valor_real,
										d.valor as valor_nc,
										e.valor as valor_nd,
										a.prefijo as prefijo_cxp,
										a.numero as numero_cxp
							FROM 
						 			$tabla_cxp a
									JOIN $tabla_fph b
									ON
									(
										a.empresa_id=b.empresa_id
										AND a.prefijo=b.prefijo_cxp
										AND a.numero=b.numero_cxp 
										AND a.estado='1'
									)
									JOIN voucher_honorarios as c
									ON
									(
										b.prefijo=c.prefijo 
										AND b.numero=c.numero 
										AND b.empresa_id=c.empresa_id 
									)
									LEFT JOIN voucher_honorarios_nc as d
									ON
									(
										c.empresa_id=d.empresa_id
										AND c.prefijo=d.prefijo_voucher 
										AND c.numero=d.numero_voucher 
										AND d.estado='1'
									)
									LEFT JOIN voucher_honorarios_nd as e
									ON
									(
										c.empresa_id=e.empresa_id
										AND c.prefijo=e.prefijo_voucher 
										AND c.numero=e.numero_voucher 
										AND e.estado='1'
									)
									LEFT JOIN profesionales as f
									ON
									(
										a.tipo_id_tercero=f.tipo_id_tercero 
						 				AND a.tercero_id=f.tercero_id	
									)
             WHERE c.valor_real>0
             $datFactura 
             $datprofesional
						 $datFechas
             ORDER BY a.fecha_registro DESC";         
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
        $this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - ObtenerVoucherAsociadosFactura";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
    }else{
        if($result->RecordCount()>0)
				{ 
					if(!$_SESSION['op'])
					{
						while(!$result->EOF)
						{
							$vars[]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
					else
					{
						while(!$result->EOF)
						{
							$vars[$result->fields[7]][$result->fields[0]][]=$result->GetRowAssoc($toUpper=false);
							$result->MoveNext();
						}
					}
        }
    }
    $result->Close();
    return $vars;                     
  }
  
  function EliminarVoucherFactura()
	{
    list($dbconn) = GetDBconn();    
    $query = "DELETE 
							FROM tmp_voucher_honorarios_facturas_profesionales
							WHERE empresa_id='".$_SESSION['VOUCHER_FACTURAS']['Empresa']."' 
							AND prefijo='".$_REQUEST['prefijo']."' 
							AND numero=".$_REQUEST['numero']."";                                             
		$result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) 
		{
			$this->error = "Error al Cargar el Modulo Vouche_FacturasProfesionales - EliminarVoucherFactura";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$this->SeleccionarVoucherHonorarios($_REQUEST['NoFactura'],$_REQUEST['Profesional']);
		return true;
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
  
  function BusquedaProfesionales($nombreProf,$uidProf,$loginProf,$TipoIdProf,$IdProf){
    $filtro='';
    list($dbconn) = GetDBconn();
    if($nombreProf){
      $filtro.=" AND b.nombre_tercero ILIKE '%".strtoupper($nombreProf)."%'";
    }
		
    if($uidProf){
      $filtro.=" AND a.usuario_id ILIKE '%".$uidProf."%'";
    }
    if($loginProf){
      $filtro.=" AND c.usuario ILIKE '%".$loginProf."%'";
    }
    if($TipoIdProf && $IdProf){
      $filtro.=" AND a.tipo_tercero_id='".$TipoIdProf."' AND a.tercero_id='".$IdProf."'";
    }
    $query = "SELECT a.tipo_tercero_id,a.tercero_id,b.nombre_tercero,a.usuario_id,c.usuario
              FROM profesionales_usuarios a,terceros b,system_usuarios c
              WHERE a.tipo_tercero_id=b.tipo_id_tercero 
              AND a.tercero_id=b.tercero_id
              AND a.usuario_id=c.usuario_id $filtro";
             
    $result = $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
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
	
	/****
	* Separa la Fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	****/
	function FechaStamp($fecha)
	{
		if($fecha)
		{
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}

			return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
		}
	}
    

//fin


}//fin clase user

?>