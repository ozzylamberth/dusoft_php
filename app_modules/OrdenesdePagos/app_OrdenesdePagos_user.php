<?php

/**
 * $Id: app_OrdenesdePagos_user.php,v 1.5 2007/09/10 15:10:21 jgomez Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* MODULO para el Manejo de Inventarios en el Sistema
*/

/**
*Contiene los metodos para realizar la relacion de voucher de honorarios medicos con las facturas de los profesionales
*/

class app_OrdenesdePagos_user extends classModulo
{
		var $limit;
		var $conteo;

	/**
	* Funcion contructora que inicializa las variables
	* @return boolean
	*/
	function app_OrdenesdePagos_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}
	
	/**
	* Funcion principal que se encarga de llamar al menu para la seleccion de la empresa
	* @return boolean
	*/

	function main()
	{      
		if(!$this->FrmLogueoEmpresa())
		{
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
		
		$query ="	SELECT 	x.empresa_id,
											y.razon_social as descripcion1
							FROM userpermisos_ordenes_de_pago as x,
							empresas as y
							WHERE x.usuario_id = ".UserGetUID()." 
							AND x.empresa_id=y.empresa_id";
							
		$result = $dbconn->Execute($query);
		
		$this->cantidad_empresas=$result->RecordCount();
		
		if($result->EOF)
		{
			$this->error = "Error al Cargar el Modulo Ordenesdepagos - LogueoEmpresa";
			$this->mensajeDeError = $dbconn->ErrorMsg()."<br>".$query;
			return false;
		}
		else
		{
			if($this->cantidad_empresas==1)
			{
				$vars[0]=$result->fields[0];
				$vars[1]=$result->fields[1];
			}
			else
			{
				while(!$result->EOF)
				{
					$datos[$result->fields[1]]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
				$mtz[0]="EMPRESA";
				$vars[0]=$mtz;
				$vars[1]=$datos;
			}
		}
		return $vars;
	}
	
	/**
	* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
	* @return array
	*/
	function TiposTerceros()
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT tipo_id_tercero,descripcion
							FROM tipo_id_terceros 
							ORDER BY indice_de_orden";
		
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->EOF)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "La tabla maestra 'tipo_id_pacientes' esta vacia ";
				return false;
			}
			while (!$result->EOF)
			{
				$vars[$result->fields[0]]=$result->fields[1];
				$result->MoveNext();
			}
		}
		$result->Close();
		return $vars;
	}
	
	function BusquedaProfesionales($nombreProf,$uidProf,$loginProf,$TipoIdProf,$IdProf)
	{
		list($dbconn) = GetDBconn();
		global $ADODB_FETCH_MODE;
		
		if($nombreProf)
		{
			$filtro.=" AND b.nombre ILIKE '%".strtoupper($nombreProf)."%'";
		}
		
		if($uidProf)
		{
			$filtro.=" AND a.usuario_id ILIKE '%".$uidProf."%'";
		}
		if($loginProf)
		{
			$filtro.=" AND c.usuario ILIKE '%".$loginProf."%'";
		}
		
		if($TipoIdProf && $IdProf)
		{
			$filtro.=" AND a.tipo_tercero_id='".$TipoIdProf."' AND a.tercero_id='".$IdProf."'";
		}
		
		$sqlCont="SELECT count(*)
							FROM profesionales_usuarios a,
										profesionales b,
										system_usuarios c
							WHERE a.tipo_tercero_id=b.tipo_id_tercero 
							AND a.tercero_id=b.tercero_id
							AND a.usuario_id=c.usuario_id 
							$filtro";
		
		$this->ProcesarSqlConteo($sqlCont);
		
		$query = "SELECT a.tipo_tercero_id,
										 a.tercero_id,
										 b.nombre,
										 a.usuario_id,
										 c.usuario
							FROM profesionales_usuarios a,
									 profesionales b,
									 system_usuarios c
							WHERE a.tipo_tercero_id=b.tipo_id_tercero 
							AND a.tercero_id=b.tercero_id
							AND a.usuario_id=c.usuario_id 
							$filtro
							ORDER BY b.nombre
							LIMIT ".$this->limit." OFFSET ".$this->offset;
							
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result=$dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		while($res=$result->FetchRow())
			$filas[]=$res;
		
		$result->Close();
		
		return $filas;
	}
	
	function GetCuentasxPagar_Orden($TipoProf,$Prof,$plan,$fecha_ini,$fecha_fin,$radicado,$recaudo)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($TipoProf) AND !empty($Prof))
		{
			$datos.=" AND a.tipo_id_tercero='$TipoProf'
								AND a.tercero_id='$Prof'";
		}
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$datos.=" AND date(c.fecha_radicacion)>='$fecha_ini'
								AND date(c.fecha_radicacion)<='$fecha_fin'";
		}
		
		if(!empty($plan))
		{
			$datos.=" AND c.plan_id=$plan";
		}
		
		if($radicado)
		{
			$datos.=" AND c.fecha_radicacion IS NOT NULL";
		}
		
		if($recaudo)
		{
			$datos.=" AND c.numero_recibo IS NOT NULL";
		}
		
		$query = "
										SELECT a.empresa_id,
													a.prefijo,
													a.numero,
													a.numero_factura_id,
													a.valor,
													a.valor_cruzado,
													TO_CHAR(a.fecha_registro,'YYYY-MM-DD') AS fecha,
													TO_CHAR(c.fecha_radicacion,'YYYY-MM-DD') AS fecha_rad,
													c.plan_id,
													c.numero_recibo,
													c.fecha_radicacion
										FROM voucher_honorarios_cuentas_x_pagar as a
										JOIN voucher_honorarios_facturas_profesionales as b
										ON
										(
											a.empresa_id=b.empresa_id
											AND a.prefijo=b.prefijo_cxp
											AND a.numero=b.numero_cxp
										)
										JOIN voucher_honorarios as c
										ON
										(
											b.empresa_id=c.empresa_id
											AND b.prefijo=c.prefijo
											AND b.numero=c.numero
										)
										WHERE (a.prefijo_orden,a.numero_orden) IS NULL
										AND a.estado='1'
										$datos
										ORDER BY a.numero_factura_id
									
							";
		
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[$result->fields[3]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
	
	function GetVoucher($empresa,$prefijo,$numero)
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT a.prefijo,
										 a.numero,
										 e.descripcion,
										 c.valor as valor_nc,
										 d.valor as valor_nd,
										 b.valor_real
							FROM voucher_honorarios_facturas_profesionales as a,
							voucher_honorarios as b
							LEFT JOIN voucher_honorarios_nc as c
							ON
							(
								b.empresa_id=c.empresa_id
								AND b.prefijo=c.prefijo_voucher 
								AND b.numero=c.numero_voucher 
								AND c.estado='1'
							)
							LEFT JOIN voucher_honorarios_nd as d
							ON
							(
								b.empresa_id=d.empresa_id
								AND b.prefijo=d.prefijo_voucher 
								AND b.numero=d.numero_voucher 
								AND d.estado='1'
							)
							JOIN tarifarios_detalle as e
							ON
							(
								b.tarifario_id=e.tarifario_id
								AND b.cargo=e.cargo
							)
							WHERE a.empresa_id=b.empresa_id 
							AND a.prefijo=b.prefijo
							AND a.numero=b.numero
							AND b.estado='1'
							AND b.valor_real > 0
							AND a.empresa_id='$empresa'
							AND a.prefijo_cxp='$prefijo'
							AND a.numero_cxp=$numero
							ORDER BY a.prefijo,a.numero;";
		
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
	
	function GetValorFactura($empresa,$prefijo,$numero)
	{
		list($dbconn) = GetDBconn();
		
		$query = "SELECT sum(b.valor_real)
							FROM voucher_honorarios_facturas_profesionales as a,
							voucher_honorarios as b
							WHERE a.empresa_id=b.empresa_id 
							AND a.prefijo=b.prefijo
							AND a.numero=b.numero
							AND b.estado='1'
							AND b.valor_real > 0
							AND a.empresa_id='$empresa'
							AND a.prefijo_cxp='$prefijo'
							AND a.numero_cxp=$numero
						";
		
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$v_factura=$result->fields[0];
		
			$query = "UPDATE voucher_honorarios_cuentas_x_pagar SET
								valor=$v_factura
								WHERE empresa_id='$empresa'
								AND prefijo='$prefijo'
								AND numero=$numero
							";
			
			$result=$dbconn->Execute($query);
			
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
		}
		
		return $v_factura;
	}
	
	function GetPlanes($tipo_id,$tercero)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($tipo_id) AND !empty($tercero))
		{
			$dato="	AND tipo_tercero_id='".$tipo_id."'
							AND tercero_id='".$tercero."'";
		}
		
		$query = 	" SELECT DISTINCT plan_id,plan_descripcion
								FROM planes
								WHERE estado='1'
								$dato
								ORDER BY plan_descripcion
							";              
							
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdePgos - GetPlanes";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	function GetTerceros()
	{
		list($dbconn) = GetDBconn();
		
		$query = "  SELECT DISTINCT b.tipo_id_tercero,
											 b.tercero_id,
											 b.nombre_tercero
								FROM planes as a,
								terceros as b
								WHERE a.tipo_tercero_id=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id
								AND a.estado='1'
								ORDER BY b.nombre_tercero;";              
							
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdePgos - GetTerceros";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while(!$result->EOF)
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
	
	
	function GuardarOrdenPago()
	{
		$selfact=$_REQUEST['SelFact'];
		
		if($selfact)
		{
			list($dbconn) = GetDBconn();
			
			$query  = "LOCK TABLE documentos IN ROW EXCLUSIVE MODE;
									SELECT a.documento_id_op,b.prefijo,b.numeracion 
									FROM 	voucher_honorarios_parametros a,
												documentos b
									WHERE a.empresa_id='".$_SESSION['ORDEN_PAGO']['Empresa']."'
									AND a.documento_id_op=b.documento_id;";
		
				$result = $dbconn->Execute($query);
				
				if ($dbconn->ErrorNo() != 0)
				{
					$this->error = "Error al Cargar el Modulo OrdenesdePagos - GuardarOrdenPago SQL1";
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
				
				list($TipoProf,$Prof,$nombre)=explode("||//",$_REQUEST['Profesional']);
				
				$query = "INSERT INTO voucher_honorarios_ordenes_de_pago
									(
										empresa_id,
										prefijo,
										numero,
										documento_id,
										profesional_id,
										tipo_id_profesional,
										valor_total,
										usuario_id
									)
									VALUES
									(
										'".$_SESSION['ORDEN_PAGO']['Empresa']."',
										'".$prefijo."',
										".$numero.",
										$documento_id,
										'".$Prof."',
										'".$TipoProf."',
										0,
										".UserGetUID()."
									)";         
				
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Cargar el Modulo OrdenesdePagos - GuardarOrdenPago SQL2";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				else
				{
					$query="UPDATE documentos SET
									numeracion=numeracion+1
									WHERE documento_id=".$documento_id."
									AND empresa_id='".$_SESSION['ORDEN_PAGO']['Empresa']."';";
			
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) 
					{
						$this->error = "Error al Cargar el Modulo OrdenesdePagos - GuardarOrdenPago SQL3";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
				
				$valor_total=0;
	
				foreach($selfact as $key=>$valor)
				{
					list($empresa_id,$prefijo_cxp,$numero_cxp,$valor_fact)=explode("__",$valor);
					if(!$valor_fact)
						$valor_fact=0;
					
					$valor_total+=$valor_fact;
					
					$query="UPDATE voucher_honorarios_cuentas_x_pagar 
									SET prefijo_orden='$prefijo',
									numero_orden=$numero,
									valor_cruzado=$valor_fact
									WHERE empresa_id='".$empresa_id."'
									AND prefijo='$prefijo_cxp'
									AND numero=$numero_cxp;";
									
					$result = $dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) 
					{
						$this->error = "Error al Cargar el Modulo OrdenesdePagos - GuardarOrdenPago SQL4";
						$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
						return false;
					}
				}
				
				$query="UPDATE voucher_honorarios_ordenes_de_pago
								SET valor_total=$valor_total
								WHERE empresa_id='".$_SESSION['ORDEN_PAGO']['Empresa']."'
								AND prefijo='$prefijo'
								AND numero=$numero;";
			
				$result = $dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0) 
				{
					$this->error = "Error al Cargar el Modulo OrdenesdePagos - GuardarOrdenPago SQL5";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$this->FrmEstadoCuentasProfesional(1);
		}
		else $this->FrmEstadoCuentasProfesional();
		
		return true;
	}
	
	
	function GetOrdenesdePagoTotal($TipoidProf,$Prof,$plan,$fecha_ini,$fecha_fin,$radicado,$recaudo,$sw=null,$sw_cancel=0)
	{
		list($dbconn) = GetDBconn();
		
		if(!empty($TipoidProf) AND !empty($Prof))
		{
			$datos.=" AND a.tipo_id_profesional='$TipoidProf'
							 	AND a.profesional_id='$Prof'";
		}
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$datos.=" AND date(d.fecha_radicacion)>='$fecha_ini'
							 	AND date(d.fecha_radicacion)<='$fecha_fin'";
		}
		
		if($plan)
		{
			$datos.=" AND d.plan_id=$plan";
		}
		
		if($radicado)
		{
			$datos.=" AND d.fecha_radicacion IS NOT NULL";
		}
		
		if($recaudo)
		{
			$datos.=" AND d.numero_recibo IS NOT NULL";
		}
		
		$query = "	SELECT
										d.tipo_id_profesional,
										d.profesional_id,
										a.prefijo as prefijo_op,
										a.numero as numero_op,
										e.nombre,
										b.numero_factura_id,
										d.prefijo as prefijo_v,
										d.numero as numero_v,
										d.valor_real,
										b.valor,
										a.valor_total,
										d.numero_recibo,
										f.primer_apellido || ' ' || f.segundo_apellido || ' ' || f.primer_nombre || ' ' || f.segundo_nombre as nombre_paciente,
										d.plan_id,
										g.plan_descripcion,
										h.nombre_tercero,
										a.fecha_registro,
										TO_CHAR(a.fecha_registro,'YYYY-MM-DD') as fecha,
										a.empresa_id,
										i.descripcion as desc_cargo,
										d.numerodecuenta
								FROM voucher_honorarios_ordenes_de_pago as a
								JOIN voucher_honorarios_cuentas_x_pagar as b
								ON
								(
									a.empresa_id=b.empresa_id
									AND a.prefijo=b.prefijo_orden
									AND a.numero=b.numero_orden
								)
								JOIN voucher_honorarios_facturas_profesionales as c
								ON
								(
									a.empresa_id=c.empresa_id
									AND b.prefijo=c.prefijo_cxp
									AND b.numero=c.numero_cxp
								)
								JOIN voucher_honorarios as d
								ON
								(
									c.empresa_id=d.empresa_id
									AND c.prefijo=d.prefijo
									AND c.numero=d.numero
								)
								JOIN profesionales as e
								ON
								(
									d.tipo_id_profesional=e.tipo_id_tercero
									AND d.profesional_id=e.tercero_id
								)
								JOIN pacientes as f
								ON
								(
									d.tipo_id_paciente=f.tipo_id_paciente
									AND d.paciente_id=f.paciente_id
								)
								JOIN planes as g
								ON
								(
									d.plan_id=g.plan_id
								)
								LEFT JOIN terceros as h
								ON
								(
									g.tipo_tercero_id=h.tipo_id_tercero
									AND g.tercero_id=h.tercero_id
								)
								JOIN tarifarios_detalle as i
								ON
								(
									d.tarifario_id=i.tarifario_id
									AND d.cargo=i.cargo
								)
								WHERE a.estado='1'
								AND a.sw_cancelado='$sw_cancel'
								$datos 
								ORDER BY a.fecha_registro DESC
								";
		
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				if(!$sw)
				{
					while (!$result->EOF) 
					{
						$vars[$result->fields[2]."-".$result->fields[3]][$result->fields[5]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
				else
				{
					while (!$result->EOF) 
					{
						$vars[$result->fields[0]."-".$result->fields[1]][$result->fields[2]."-".$result->fields[3]][$result->fields[5]][]=$result->GetRowAssoc($toUpper=false);
						$result->MoveNext();
					}
				}
			}
		}
		return $vars;
	}
	
	function ProcesarSqlConteo($sqlCont)
	{
		$this->paginaActual = 1;
		$this->offset = 0;
		$this->limit=10;
		
		if($_REQUEST['offset'])
		{
			$this->paginaActual = intval($_REQUEST['offset']);
			if($this->paginaActual > 1)
			{
				$this->offset = ($this->paginaActual - 1) * ($this->limit);
			}
		}
		
		list($dbconn) = GetDBconn();
		$result=$dbconn->Execute($sqlCont);

		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			$this->frmError['MensajeError'] = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
		}
		
		$result->Close();
		return true;
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
	
	function PermisoMenu()
	{
		
		list($dbconn) = GetDBconn();

		$query="
						SELECT DISTINCT a.opcion_menu_id,a.descripcion FROM 
						voucher_honorarios_ordenes_de_pago_descripcion_menu as a,
						userpermisos_ordenes_de_pago as b
						WHERE (a.opcion_menu_id=b.opcion_menu
						OR b.opcion_menu=0)
						AND b.usuario_id=".UserGetUID()."
						";

		$result=$dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while (!$result->EOF) 
				{
					$vars[]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		return $vars;
	}
	
	function CancelacionOrdenesPago($empresa,$prefijo,$numero)
	{
		list($dbconn) = GetDBconn();
		
		$query="UPDATE voucher_honorarios_ordenes_de_pago
						SET sw_cancelado='1'
						WHERE empresa_id='$empresa'
						AND prefijo='$prefijo'
						AND numero=$numero;
						";
						
		$result=$dbconn->Execute($query);
	
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo Ordenes de Pago - CancelacionOrdenesPago";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		
		return true;
	}
	

	function ReporteGeneral($fecha_ini,$fecha_fin)
	{
		
		list($dbconn) = GetDBconn();
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$filtro="AND date(A.fecha)>='$fecha_ini'
								AND date(A.fecha)<='$fecha_fin'";
		}
		
		$query  = "SELECT  A.tipo_id_profesional,
												A.profesional_id,
												D.numero_factura_id,
												A.nombre,
												A.prefijo,
												A.numero,
												B.descripcion,
												A.valor_real as valor_a_pagar
								FROM
								(
									(
										SELECT 	a.tipo_id_profesional,
														a.profesional_id,
														d.nombre,
														a.valor_real,
														a.prefijo,
														a.numero,
														a.cargo,
														a.tarifario_id,
														c.fecha_registro as fecha
										FROM voucher_honorarios as a
										JOIN rc_detalle_tesoreria_facturas as b
										ON
										(
											a.numero_recibo = b.prefijo || b.recibo_caja
										)
										JOIN recibos_caja as c
										ON
										(
											b.empresa_id=c.empresa_id
											AND b.centro_utilidad=c.centro_utilidad
											AND b.prefijo=c.prefijo
											AND b.recibo_caja=c.recibo_caja
										)
										JOIN profesionales as d
										ON
										(
											a.profesional_id=d.tercero_id
											AND a.tipo_id_profesional=d.tipo_id_tercero
										)
										WHERE a.estado='1'
									)
									UNION
									(
										SELECT 	a.tipo_id_profesional,
														a.profesional_id,
														c.nombre,
														a.valor_real,
														a.prefijo,
														a.numero,
														a.cargo,
														a.tarifario_id,
														b.fecha
										FROM voucher_honorarios as a
										JOIN rc_detalle_tesoreria_facturas_externas as b
										ON
										(
											a.numero_recibo = b.numero_recibo
										)
										JOIN profesionales as c
										ON
										(
											a.profesional_id=c.tercero_id
											AND a.tipo_id_profesional=c.tipo_id_tercero
										)
										WHERE a.estado='1'
									)
								)as A
								LEFT JOIN voucher_honorarios_facturas_profesionales as C
								ON
								(
										A.prefijo=C.prefijo
										AND A.numero=C.numero
								)
								LEFT JOIN voucher_honorarios_cuentas_x_pagar as D
								ON
								(
									C.prefijo_cxp=D.prefijo
									AND C.numero_cxp=D.numero
									AND D.estado='1'
								),
								tarifarios_detalle as B
								
								WHERE 
										A.tarifario_id=B.tarifario_id 
										AND A.cargo=B.cargo
										AND A.valor_real > 0	
										$filtro
								ORDER BY A.nombre,A.numero
							";
	
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdePagos - ReporteGeneral SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while (!$result->EOF) 
				{
					$vars[$result->fields[0]."-".$result->fields[1]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}
	
  
  
/*********************************************************************************************
*
************************************************************************************************/
	function ReporteProfesional($fecha_ini,$fecha_fin,$tipo_id_profesional,$profesional_id)
	{
		
		list($dbconn) = GetDBconn();
		
		if(!empty($fecha_ini) AND !empty($fecha_fin))
		{
			$filtro="AND date(A.fecha)>='$fecha_ini'
								AND date(A.fecha)<='$fecha_fin'";
		}
		
		$query  = "SELECT  A.tipo_id_profesional,
												A.profesional_id,
												D.numero_factura_id,
												A.nombre,
												A.prefijo,
												A.numero,
												B.descripcion,
												A.valor_real as valor_a_pagar
								FROM
								(
									(
										SELECT 	a.tipo_id_profesional,
														a.profesional_id,
														d.nombre,
														a.valor_real,
														a.prefijo,
														a.numero,
														a.cargo,
														a.tarifario_id,
														c.fecha_registro as fecha
										FROM voucher_honorarios as a
										JOIN rc_detalle_tesoreria_facturas as b
										ON
										(
											a.numero_recibo = b.prefijo || b.recibo_caja
										)
										JOIN recibos_caja as c
										ON
										(
											b.empresa_id=c.empresa_id
											AND b.centro_utilidad=c.centro_utilidad
											AND b.prefijo=c.prefijo
											AND b.recibo_caja=c.recibo_caja
										)
										JOIN profesionales as d
										ON
										(
											a.profesional_id=d.tercero_id
											AND a.tipo_id_profesional=d.tipo_id_tercero
                      AND a.tipo_id_profesional='$tipo_id_profesional'
                      AND a.profesional_id='$profesional_id'
										)
										WHERE a.estado='1'
									)
									UNION
									(
										SELECT 	a.tipo_id_profesional,
														a.profesional_id,
														c.nombre,
														a.valor_real,
														a.prefijo,
														a.numero,
														a.cargo,
														a.tarifario_id,
														b.fecha
										FROM voucher_honorarios as a
										JOIN rc_detalle_tesoreria_facturas_externas as b
										ON
										(
											a.numero_recibo = b.numero_recibo
										)
										JOIN profesionales as c
										ON
										(
											a.profesional_id=c.tercero_id
											AND a.tipo_id_profesional=c.tipo_id_tercero
                      AND a.tipo_id_profesional='$tipo_id_profesional'
                      AND a.profesional_id='$profesional_id'
										)
										WHERE a.estado='1'
									)
								)as A
								LEFT JOIN voucher_honorarios_facturas_profesionales as C
								ON
								(
										A.prefijo=C.prefijo
										AND A.numero=C.numero
								)
								LEFT JOIN voucher_honorarios_cuentas_x_pagar as D
								ON
								(
									C.prefijo_cxp=D.prefijo
									AND C.numero_cxp=D.numero
									AND D.estado='1'
								),
								tarifarios_detalle as B
								
								WHERE 
										A.tarifario_id=B.tarifario_id 
										AND A.cargo=B.cargo
										AND A.valor_real > 0	
										$filtro
								ORDER BY A.nombre,A.numero
							";
	
		$result = $dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo OrdenesdePagos - ReporteGeneral SQL";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			if($result->RecordCount()>0)
			{
				while (!$result->EOF) 
				{
					$vars[$result->fields[0]."-".$result->fields[1]][]=$result->GetRowAssoc($toUpper=false);
					$result->MoveNext();
				}
			}
		}
		
		return $vars;
	}  
//fin


}//fin clase user

?>