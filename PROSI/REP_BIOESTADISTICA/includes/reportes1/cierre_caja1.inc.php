<?php

/**
 * $Id: cierre_caja.inc.php,v 1.21 2006/01/06 13:45:24 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * funcion q trae los decuestos de las facturas
 */

	function TraerDescuento($no_cuenta)
	{ 
			list($dbconn) = GetDBconn();
			$query="SELECT precio
											FROM cuentas_detalle 
											WHERE 
											empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
											AND centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
											AND departamento='".$_SESSION['REF_DPTO']."'
											AND numerodecuenta=$no_cuenta
											AND cargo='DESCUENTO'
											AND tarifario_id='SYS'";
			$resulta=$dbconn->Execute($query);							
			if(!$resulta->fields[0])
			{
			 return 0;
			}
			else
			{
				return $resulta->fields[0];
			}
	}
	
/*
*
* FUNCION QUE POR MEDIO DE LA SECUENCIA DEL CIERRE DE CAJA SACA LA OBSERVACIONES DE LA IMPRESION
*
*/

function TraerDatoCierre()
{
		$secuencia=$_SESSION['CAJA']['CIERRES']['SEQ'];
		list($dbconn) = GetDBconn();
					$query = "SELECT fecha_registro,observaciones,cierre_caja_id
										FROM recibos_caja_cierre WHERE cierre_caja_id=$secuencia ";	
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
}

function TraerDatoCierreDeCaja()
{
		$secuencia=$_SESSION['CAJA']['CIERRE_TOTAL']['SEQ'];
		list($dbconn) = GetDBconn();
					$query = "SELECT fecha_registro,observaciones,cierre_de_caja_id
										FROM cierre_de_caja WHERE cierre_de_caja_id=$secuencia ";	
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
}

function TraerDatoCierreDeCajaControl()
{
		$secuencia=$_SESSION['CIERRE']['CIERRE_TOTAL']['SEQ'];
		list($dbconn) = GetDBconn();
					$query = "SELECT fecha_registro,observaciones,cierre_de_caja_id
										FROM cierre_de_caja WHERE cierre_de_caja_id=$secuencia ";	
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
}
/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja
*
*/
function TraerDatoUsuario()
{
			list($dbconn) = GetDBconn();
			$query = "SELECT usuario,nombre,usuario_id from system_usuarios WHERE usuario_id=".UserGetUID()."";	
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

function TraerDatoCierreControl($cierre)
{
			list($dbconn) = GetDBconn();
			$query = "SELECT fecha_registro 
								FROM cierre_de_caja 
								WHERE cierre_de_caja_id=$cierre";	
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}


function TraerDatosPacienteControl_Cierre($recibo,$prefijo)
{
	list($dbconn) = GetDBconn();
/*	echo	$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
								FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
                WHERE a.factura_fiscal=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";*/
			$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja general(hosp))
*
*/
function TraerDatosPaciente($recibo,$prefijo)
{
	list($dbconn) = GetDBconn();
/*	echo	$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
								FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
                WHERE a.factura_fiscal=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";*/
			$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."' 
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente;";
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}
//
function TraerDatosPacienteControlConfirmado($recibo,$prefijo)
{
	list($dbconn) = GetDBconn();
			$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente;";
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

function TraerDatosPacienteControlConfirmadoConcepto($recibo,$prefijo)
{
	list($dbconn) = GetDBconn();
			$query = "SELECT  b.nombre_tercero as nombre,
                b.tipo_id_tercero||' '||b.tercero_id as id

								FROM fac_facturas_contado a, terceros b
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.tipo_id_tercero=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id;";
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja rapida
*$recibo <-- esta variable en realidad traera la el No.de factura fiscal.
*/
function TraerDatosPacienteCajaR($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();

/*		echo	$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";*/

			$query = "SELECT  btrim(f.primer_nombre||'  '|| f.primer_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,
										fac_facturas_cuentas a, fac_facturas_contado b
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.factura_fiscal=b.factura_fiscal
								AND a.prefijo=b.prefijo
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

function TraerDatosPacienteCaja($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();

			$query = "SELECT  btrim(f.primer_nombre||'  '|| f.primer_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,
										fac_facturas_cuentas a,
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}


function TraerDatosCliente($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();

/*		echo	$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";*/
			$query = "SELECT  b.nombre_tercero as nombre,
                b.tipo_id_tercero||' '||b.tercero_id as id
								FROM fac_facturas_contado a, terceros b
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.tipo_id_tercero=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id;"; 
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}

function TraerDatosClienteControl($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();
			if($_SESSION['CAJA']['TIPOCUENTA']=='03' OR $_SESSION['CAJA']['TIPOCUENTA']=='08')
			{
			$query = "SELECT  b.nombre_tercero as nombre,
                b.tipo_id_tercero||' '||b.tercero_id as id
								FROM fac_facturas_contado a, terceros b
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.tipo_id_tercero=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id;";
			}
			else
			if($_SESSION['CIERRE']['CIERRE_TOTAL']['cuenta']=='03')
			{
			$query = "SELECT  b.nombre_tercero as nombre,
                b.tipo_id_tercero||' '||b.tercero_id as id
								FROM fac_facturas_contado a, terceros b
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.tipo_id_tercero=b.tipo_id_tercero
								AND a.tercero_id=b.tercero_id;";
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}


function TraerDatosPacienteCajaR_Control_Cierre($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();

/*		echo	$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
                WHERE a.recibo_caja=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente";*/
			$query = "SELECT  btrim(f.primer_nombre||'  '|| f.primer_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
                WHERE a.factura_fiscal=".$recibo." 
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente"; 
									
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
}


function TraerPacientePagare($recibo,$prefijo)
{
		list($dbconn) = GetDBconn();
		$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
								FROM pacientes f,ingresos s,cuentas x,
										rc_detalle_pagare a, pagares b
                WHERE a.recibo_caja=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND b.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente
								AND a.empresa_id=b.empresa_id 
								AND a.prefijo=b.prefijo 
								AND a.numero=b.numero";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			//return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];
			return $var;
}

function TraerPacienteDev($recibo,$prefijo)
{
		list($dbconn) = GetDBconn();
		$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
								FROM pacientes f,ingresos s,cuentas x,
										rc_devoluciones a
                WHERE a.recibo_caja=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente
								AND a.empresa_id=a.empresa_id 
								AND a.prefijo=a.prefijo";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			//return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];
			return $var;
}

function TraerPacienteDevControl($recibo,$prefijo)
{
		list($dbconn) = GetDBconn();
		$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id
								FROM pacientes f,ingresos s,cuentas x,
										rc_devoluciones a
                WHERE a.devolucion_id=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.numerodecuenta=x.numerodecuenta
								AND x.ingreso=s.ingreso
								AND s.paciente_id=f.paciente_id
								AND s.tipo_id_paciente=f.tipo_id_paciente
								AND a.empresa_id=a.empresa_id 
								AND a.prefijo=a.prefijo";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			//return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];
			return $var;
}


	function DatosEncabezadoEmpresaInventarios()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descuenta, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
										FROM cajas_rapidas as a, empresas as b,centros_utilidad as c
										WHERE c.empresa_id='".$_SESSION['CAJA']['EMPRESA']."' and c.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
										and a.caja_id='".$_SESSION['CAJA']['CAJAID']."' and b.empresa_id='".$_SESSION['CAJA']['EMPRESA']."';";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}


	function DatosEncabezadoEmpresaControl()
	{

			$CentroU=$_SESSION['CAJA']['CENTROUTILIDAD'];
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descuenta, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
										FROM cajas as a, empresas as b,centros_utilidad as c
										WHERE  c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' and c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
										and a.caja_id='".$_SESSION['CIERRE']['CIERRE_TOTAL']['CAJA']."' and b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}

	function DatosEncabezadoEmpresaControlConfirmado()
	{

			$CentroU=$_SESSION['CAJA']['CENTROUTILIDAD'];
			list($dbconn) = GetDBconn();
			$query = "SELECT c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
										FROM  empresas as b,centros_utilidad as c
										WHERE  c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
											and c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
											and b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}


	function DatosEncabezadoEmpresaRecibo()
	{
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descuenta, a.caja_id, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
								FROM cajas as a, empresas as b,centros_utilidad as c
								WHERE  a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND c.empresa_id=b.empresa_id
								AND a.empresa_id=b.empresa_id
								AND c.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
								and a.caja_id='".$_SESSION['CAJA']['CAJAID']."' ";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}
	
	function TraerDatoUsuarioCierre()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT usuario,nombre,usuario_id from system_usuarios WHERE usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][usuario_id]."";	
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}

	function DatosEncabezadoEmpresaCierre()
	{
			$CentroU=$_SESSION['CAJA']['CENTROUTILIDAD'];
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descaja, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
										FROM cajas as a, empresas as b,centros_utilidad as c
										WHERE  c.empresa_id='".$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][empresa_id]."' and c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][centro_utilidad]."'
										and a.caja_id='".$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][caja_id]."' and b.empresa_id='".$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][empresa_id]."'";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}
	
	function ConsultarRecibos($cierre)
	{
			list($dbconn) = GetDBconn();
			if($_SESSION['CIERRE']['CIERRE_TOTAL']['cuenta']=='03' OR $_SESSION['CAJA']['TIPOCUENTA']=='08')
			{
//, a.total_abono
		echo	$query = "SELECT a.factura_fiscal,a.prefijo, a.fecha_registro, a.total_efectivo,
										a.total_tarjetas, a.total_cheques, 
										a.total_bonos, a.tipo_id_tercero, a.tercero_id,a.caja_id,
										c.nombre, d.descripcion as caja,b.sw_facturado,
										CASE WHEN f.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
								FROM fac_facturas_contado a, recibos_caja_cierre as b,
											system_usuarios as c, cajas_rapidas d, 
											userpermisos_cajas_rapidas e,
											fac_facturas f
								WHERE a.cierre_caja_id=".$cierre."
								AND a.usuario_id=c.usuario_id
								AND c.usuario_id=e.usuario_id
								AND a.prefijo=f.prefijo
								AND a.factura_fiscal=f.factura_fiscal
								AND a.caja_id=e.caja_id
								AND a.cierre_caja_id=b.cierre_caja_id
								AND b.sw_facturado='0'
								AND d.caja_id=a.caja_id;";
			}
			else
			if($_SESSION['CAJA']['TIPOCUENTA']!='01')
			{
//, a.total_abono
	echo		$query = "SELECT a.factura_fiscal,a.prefijo, a.fecha_registro, a.total_efectivo,
										a.total_tarjetas, a.total_cheques, 
										a.total_bonos, a.tipo_id_tercero, a.tercero_id,a.caja_id,
										c.nombre, d.descripcion as caja,b.sw_facturado,
										CASE WHEN e.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
								FROM fac_facturas_contado a, recibos_caja_cierre as b,
											system_usuarios as c, cajas_rapidas d,fac_facturas e
								WHERE a.cierre_caja_id=".$cierre."
								AND a.usuario_id=c.usuario_id
								AND a.cierre_caja_id=b.cierre_caja_id
								AND b.sw_facturado='1'
								AND d.caja_id=a.caja_id
								AND a.prefijo=e.prefijo
								AND a.factura_fiscal=e.factura_fiscal;"; 
			}
			else
			{
			$query = "SELECT a.recibo_caja,a.prefijo, a.fecha_registro, a.total_efectivo,
										a.total_tarjetas, a.total_cheques, a.total_abono, 
										a.total_bonos, a.tipo_id_tercero, a.tercero_id,a.caja_id,
										c.nombre, d.descripcion as caja,b.sw_facturado
								FROM recibos_caja a, recibos_caja_cierre as b,
											system_usuarios as c, cajas d
								WHERE a.cierre_caja_id=".$cierre."
								AND a.usuario_id=c.usuario_id
								AND a.cierre_caja_id=b.cierre_caja_id
								AND b.sw_facturado='0'
								AND d.caja_id=a.caja_id;";
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
				$i=0;
				while (!$resulta->EOF)
				{
					$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
				}
			return $var;
	}

	function TraerTotales($uid,$caja,$cierre,$dp)
	{
		if(!empty($_SESSION['CAJA']['SERIALCIERRE']))
		{
			$cond="AND a.cierre_caja_id is not null";
			$cond2="AND a.cierre_caja_id=".$_SESSION['CAJA']['SERIALCIERRE']."";
		}
		else
		{
			$cond="AND a.cierre_caja_id is null";
			$cond2="";
		}
		list($dbconn) = GetDBconn();
		//a.total_abono,
		//				AND b.estado='0'
		$query="SELECT a.total_cheques,a.total_efectivo,
									a.total_tarjetas,a.total_bonos, a.fecha_registro,
									a.factura_fiscal, a.prefijo,
									CASE WHEN b.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
						FROM fac_facturas_contado a,fac_facturas b
						WHERE a.usuario_id=$uid
						AND a.cierre_caja_id=".$_SESSION['CAJA']['SERIALCIERRE']."
						AND a.caja_id=$caja
						$cond
						$cond2
						AND a.prefijo=b.prefijo
						AND a.factura_fiscal=b.factura_fiscal;";
		$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
								$this->error = "Error al traer la consulta de los cierres";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
							}
							$i=0;

							while (!$resulta->EOF)
							{
								$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$i++;
							}
		return $var;
	}

function DatosEncabezadoEmpresa()
	{
			list($dbconn) = GetDBconn();
			$query = "SELECT a.descripcion as descuenta, c.descripcion, b.razon_social,b.tipo_id_tercero,b.id
								FROM cajas_rapidas as a, empresas as b,centros_utilidad as c
								WHERE  a.empresa_id='".$_SESSION['CAJA']['EMPRESA']."'
								AND c.empresa_id=b.empresa_id
								AND a.empresa_id=b.empresa_id
								AND c.centro_utilidad='".$_SESSION['CAJA']['CENTROUTILIDAD']."'
								and a.caja_id='".$_SESSION['CAJA']['CAJAID']."' ";
			
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}


	function GenerarCierreDeCaja1($ar,$arrdev,$arrdev1)
	{ 
	//print_r($ar); exit;
		//print_r($arrdev);
		//IncludeLib("tarifario");
		$Dir="cache/cierre_de_caja_reporte".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		UNSET($_SESSION['TMP']['VARIABLE']['ENCABEZADO']);
		$_SESSION['TMP']['VARIABLE']['ENCABEZADO']=1;
		$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja';
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
		for($j=0;$j<sizeof($ar);$j++)
		{
			$arr=ConsultarRecibos($ar[$j][cierre_caja_id]);
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo2='#CCCCCC';}
				else {$estilo2='#DDDDDD';}
				
				if( $_SESSION['CAJA']['TIPOCUENTA']=='03' OR $_SESSION['CAJA']['TIPOCUENTA']=='08')
				{
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][factura_fiscal]."</td>";
					$pac=TraerDatosClienteControl($arr[$i][factura_fiscal],$arr[$i][prefijo]);
				}
				else
				if($_SESSION['PAGARE']['TIPOCUENTA']=='06')
				{//echo $_SESSION['PAGARE']['TIPOCUENTA'].'PAGARE'.$_SESSION['REF_DPTO'];exit;
					//traemos los pacientes de caja PAGARES
					$pac=TraerPacientePagare($arr[$i][recibo_caja],$arr[$i][prefijo]);
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
				}
				else
				//if($_SESSION['CAJA']['CIERRE']['DEPTO'])
				//if(empty($_SESSION['REF_DPTO']))
				if($_SESSION['CAJA']['TIPOCUENTA']=='01' || $_SESSION['CAJA']['TIPOCUENTA']=='02')
				{	
					//si entra aqui es por q ws cierre normal osea hospitalario
					$pac=TraerDatosPaciente($arr[$i][recibo_caja],$arr[$i][prefijo]);
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
				}	
				else
				if($_SESSION['CAJA']['TIPOCUENTA']=='05')
				{//echo NOREF_DPTO;
					//si entra aqui es por q ws cierre es de cualquiere caja rapida
					$pac=TraerDatosPacienteCajaR($arr[$i][factura_fiscal],$arr[$i][prefijo]);
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][factura_fiscal]."</td>";
				}
				else
				{//echo NOREF_DPTO;
					//si entra aqui es por q ws cierre es de cualquiere caja rapida
					$pac=TraerDatosPacienteCaja($arr[$i][recibo_caja],$arr[$i][prefijo]);
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
				}

			//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
				if(empty($pac)){$pac="-------";}
				$fecha=explode(" ",$arr[$i][fecha_registro])	;
				$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
				$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
				$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";
	
	
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$descuento=TraerDescuento($arr[$i][numerodecuenta]);
					$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
				}	*/
				//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
				if($arr[$i][total_abono]==-1)
				{
					$salida.="  <td width='60' bgcolor=$estilo><font color='red'>ANULADO</font></td>";
				}
				else
				{
					$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
				}
				if($arr[$i][total_abono]!=-1)
				{
					$bon=$bon+$arr[$i][total_bonos];
					$efe=$efe+$arr[$i][total_efectivo];
					$che=$che+$arr[$i][total_cheques];
					$tar=$tar+$arr[$i][total_tarjetas];
					$tbon=$tbon+$arr[$i][total_bonos];
					$tdes=$tdes+$descuento;
					//$sum=$sum + $arr[$i][suma];
					$sum=$sum + $arr[$i][total_abono];
				}
				$salida.="</tr>";
			}
		}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"1\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		$ciclo=sizeof($arrdev);
		if($ciclo>0 AND $arrdev!=1)
		{
				$salida.="<br><br><table border=\"1\" align=\"center\"  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][recibo_caja]."</td>";
					$pac=TraerPacienteDev($arrdev[$i][recibo_caja],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100'>".$fecha[0]."</td>";
					$salida.="  <td  width='75'>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310'>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57'>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table border=\"1\" width=90 align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
		else
		if($arrdev==1)
		{		$efed=0;
				$ciclo=sizeof($arrdev1);
				$salida.="<br><br><table  align=\"center\" border=\"1\" width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
/*					for($j=0;$j<sizeof($arrdev1[$i]);$j++)
					{*/
						$salida.="  <td  width='75'>".$arrdev1[$i][prefijo]."$sp $sp".$arrdev1[$i][recibo_caja]."</td>";
						$pac=TraerPacienteDev($arrdev1[$i][recibo_caja],$arrdev1[$i][prefijo]);
						if(empty($pac)){$pac="-------";}
						$fecha=explode(" ",$arrdev1[$i][fecha_registro])	;
						$salida.="  <td  width='100'>".$fecha[0]."</td>";
						$salida.="  <td  width='75'>".$arrdev1[$i][caja_id]."</td>";
						$salida.="  <td  width='310'>".$pac['id']."$spa".$pac['nombre']."</td>";
						$salida.="  <td  width='57'>".FormatoValor($arrdev1[$i][total_devolucion])."</td>";
					/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
						$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
						$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/
	
	
						/*if(!empty($_SESSION['REF_DPTO']))
						{	
							$descuento=TraerDescuento($arr[$i][numerodecuenta]);
							$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
						}	*/
						//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
						//$bond=$bond+$arr[$i][total_bonos];
						$efed=$efed+$arrdev1[$i][total_devolucion];
						//$ched=$ched+$arr[$i][total_cheques];
						//$tard=$tard+$arr[$i][total_tarjetas];
						//$tbond=$tbon+$arr[$i][total_bonos];
						//$tdesd=$tdesd+$descuento;
						//$sumd=$sumd + $arrdev[$i][suma];
						$salida.="</tr>";
//					}
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"1\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180'><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150'><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida.= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "			</tr>";
		$salida.= "			<tr>";
		$salida.= "				<td align='center' width='150'><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150'><font color='black'><b>FIRMA DEL USUARIO</b>  $s $s $s </td></tr><tr><td align='center' width='150'>------------------------------------------------------"." </font></td></tr>";
		$salida.= "			</tr>";
		$salida.= "			<tr>";
		$salida.= "				<td align='center' width='150'><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150'><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}

	// CIERRE DE CAJA CONCEPTO CONFIRMADO
	function GenerarCierreDeCajaConceptoConfirmado($ar,$cierre)
	{ 
//print_r($ar); exit;
		//IncludeLib("tarifario");
		$Dir="cache/cierre_de_caja_concepto_confirmado".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja';
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
//ENCABEZADO
				//$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
				//$html="".$this->image('images/logocliente.png',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
				$salida = "		<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
				$salida .= "			<tr>";
				$space=" ";
				$datos=DatosEncabezadoEmpresaControlConfirmado();
				$info=TraerDatoUsuario();
				$datos_cierre=TraerDatoCierreControl($cierre);
				$_SESSION['observa']=$datos_cierre['observaciones'];
				$_SESSION['login']=$info['usuario'];
				$impresion="IMPRESION :";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
				$salida.= "			</tr>";
/*				$salida .= "			<tr>";
				$fech=explode(".",$datos_cierre[fecha_registro]);
				$TITULO='REPORTE DE CIERRE DE CAJA :'." ".strtoupper($datos['descuenta'])."";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";*/
				//esta parte es nueva de sos


				$salida .= "			<tr>";
				$TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$cierre."";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";


				$salida .= "			<tr>";
				$fech=explode('.',$datos_cierre[fecha_registro]);
				$TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";	
				
				$salida.="<table>";
				$salida .= "		<table width=100% border=\"1\" align=\"center\" >";
				$salida.= "				<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
				$salida.= "				<td width='80' ><font color='white'><b>FECHA</b></font></td>";
				$salida.= "				<td  width='310'   ><font color='white'><b>PACIENTE</b></font></td>";
				$salida.= "				<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
				$salida.= "				<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
				$salida.= "				<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
				$salida.= "				<td width='65'><font color='white'><b>BONOS</b></font></td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{
					$html.= "				<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
				}*/
				$salida.= "				<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
				$salida.="</tr>";
				$salida.="<tr>";

//FIN ENCABEZADO
			//$arr=ConsultarRecibos($ar[$j][cierre_caja_id]);
			$arr=$ar;
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo2='#CCCCCC';}
				else {$estilo2='#DDDDDD';}
				
				$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][factura_fiscal]."</td>";
					//si entra aqui es por q ws cierre normal osea hospitalario
					$pac=TraerDatosPacienteControlConfirmadoConcepto($arr[$i][factura_fiscal],$arr[$i][prefijo]);
			//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
				if(empty($pac)){$pac="-------";}
				$fecha=explode(" ",$arr[$i][fecha_registro])	;
				$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
				$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
				$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$descuento=TraerDescuento($arr[$i][numerodecuenta]);
					$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
				}	*/
				//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
				$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
				//$bon=$bon+$arr[$i][total_bonos];
				$efe=$efe+$arr[$i][total_efectivo];
				$che=$che+$arr[$i][total_cheques];
				$tar=$tar+$arr[$i][total_tarjetas];
				$tbon=$tbon+$arr[$i][total_bonos];
				$tdes=$tdes+$descuento;
				$salida.="</tr>";
			}
		$salida.="</table>";
		$sum=$efe+$che+$tar+$tbon;
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}
	//FIN CIERRE DE CAJA CONCEPTO CONFIRMADO

	//CIERRE DE CAJA CONFIRMADO
	function GenerarCierreDeCajaConfirmado($ar,$arrdev,$cierre)
	{ 
//print_r($arrdev); exit;
		//IncludeLib("tarifario");
		$Dir="cache/cierre_de_caja_reporte_confirmado".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja';
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
//ENCABEZADO
				//$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
				//$html="".$this->image('images/logocliente.png',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
				$salida = "		<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
				$salida .= "			<tr>";
				$space=" ";
				$datos=DatosEncabezadoEmpresaControlConfirmado();
				$info=TraerDatoUsuario();
				$datos_cierre=TraerDatoCierreControl($cierre);
				$_SESSION['observa']=$datos_cierre['observaciones'];
				$_SESSION['login']=$info['usuario'];
				$impresion="IMPRESION :";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
				$salida.= "			</tr>";
/*				$salida .= "			<tr>";
				$fech=explode(".",$datos_cierre[fecha_registro]);
				$TITULO='REPORTE DE CIERRE DE CAJA :'." ".strtoupper($datos['descuenta'])."";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";*/
				//esta parte es nueva de sos


				$salida .= "			<tr>";
				$TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$cierre."";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";


				$salida .= "			<tr>";
				$fech=explode('.',$datos_cierre[fecha_registro]);
				$TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";	
				
				$salida.="<table>";
				$salida .= "		<table width=100% border=\"1\" align=\"center\" >";
				$salida.= "				<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
				$salida.= "				<td width='80' ><font color='white'><b>FECHA</b></font></td>";
				$salida.= "				<td  width='310'   ><font color='white'><b>PACIENTE</b></font></td>";
				$salida.= "				<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
				$salida.= "				<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
				$salida.= "				<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
				$salida.= "				<td width='65'><font color='white'><b>BONOS</b></font></td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{
					$html.= "				<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
				}*/
				$salida.= "				<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
				$salida.="</tr>";
				$salida.="<tr>";

//FIN ENCABEZADO
			//$arr=ConsultarRecibos($ar[$j][cierre_caja_id]);
			$arr=$ar;
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo2='#CCCCCC';}
				else {$estilo2='#DDDDDD';}
				
				$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
					//si entra aqui es por q ws cierre normal osea hospitalario
					$pac=TraerDatosPacienteControlConfirmado($arr[$i][recibo_caja],$arr[$i][prefijo]);
			//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
				if(empty($pac)){$pac="-------";}
				$fecha=explode(" ",$arr[$i][fecha_registro])	;
				$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
				$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
				$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$descuento=TraerDescuento($arr[$i][numerodecuenta]);
					$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
				}	*/
				//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
				$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
				//$bon=$bon+$arr[$i][total_bonos];
				$efe=$efe+$arr[$i][total_efectivo];
				$che=$che+$arr[$i][total_cheques];
				$tar=$tar+$arr[$i][total_tarjetas];
				$tbon=$tbon+$arr[$i][total_bonos];
				$tdes=$tdes+$descuento;
				$salida.="</tr>";
			}
		$salida.="</table>";
		$sum=$efe+$che+$tar+$tbon;
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		//print_r($arrdev); 
		$ciclo=sizeof($arrdev);
	 //$ciclo=0; 
		if($ciclo>0)
		{
				$salida.="<br><br><table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][recibo_caja]."</td>";
					$pac=TraerPacienteDev($arrdev[$i][recibo_caja],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}

//CIERRE DE CAJA CONFIRMADO CONTROL
	function GenerarCierreDeCajaConfirmadoControl($ar,$arrdev,$cierre)
	{ 
//print_r($arrdev); exit;
		//IncludeLib("tarifario");
		$Dir="cache/cierre_de_caja_reporte_confirmado".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja';
		UNSET($_SESSION['REPORTES']['VARIABLE']);
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
//ENCABEZADO
				//$this->Cell(0,2,'Pagina '.$this->PageNo().'de {nb}',0,0,'C');
				//$html="".$this->image('images/logocliente.png',85,3,22)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
				$salida = "		<br><br><br><br><table width=\"100\" border=\"1\" align=\"center\" >";
				$salida .= "			<tr>";
				$space=" ";
				$datos=DatosEncabezadoEmpresaControlConfirmado();
				$info=TraerDatoUsuario();
				$datos_cierre=TraerDatoCierreControl($cierre);
				$_SESSION['observa']=$datos_cierre['observaciones'];
				$_SESSION['login']=$info['usuario'];
				$impresion="IMPRESION :";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$datos['razon_social']."$space $space $space".$datos['tipo_id_tercero']."$space".$datos['id']."$space $space $space $space $impresion $space".date("Y-m-d H:i")."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$datos['descripcion']."</b></font></td>";
				$salida.= "			</tr>";
				$salida .= "			<tr>";
				$salida.= "				<td align='center' width=780 ><font color='black'><b>".$info['usuario_id']."$space $space".$info['nombre']."</b></font></td>";
				$salida.= "			</tr>";
/*				$salida .= "			<tr>";
				$fech=explode(".",$datos_cierre[fecha_registro]);
				$TITULO='REPORTE DE CIERRE DE CAJA :'." ".strtoupper($datos['descuenta'])."";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";*/
				//esta parte es nueva de sos


				$salida .= "			<tr>";
				$TITULO='REPORTE DE CIERRE DE CAJA No :'." ".$cierre."";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";


				$salida .= "			<tr>";
				$fech=explode('.',$datos_cierre[fecha_registro]);
				$TITULO='FECHA DE CIERRE DE CAJA :'."$fech[0]";
				$salida.= "				<td width=780 ><font color='black' size=10><b>$TITULO</b></font></td>";
				$salida.= "			</tr>";	
				
				$salida.="<table>";
				$salida .= "		<table width=100% border=\"1\" align=\"center\" >";
				$salida.= "				<tr><td  width='68' ><font color='white'><b>RECIBO</b></font></td>";
				$salida.= "				<td width='80' ><font color='white'><b>FECHA</b></font></td>";
				$salida.= "				<td  width='310'   ><font color='white'><b>PACIENTE</b></font></td>";
				$salida.= "				<td width='65' ><font color='white'><b>EFECTIVO</b></font></td>";
				$salida.= "				<td width='65' ><font color='white'><b>CHEQUE</b></font></td>";
				$salida.= "				<td width='65'><font color='white'><b>TARJETAS</b></font></td>";
				$salida.= "				<td width='65'><font color='white'><b>BONOS</b></font></td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{
					$html.= "				<td width='67'><font color='white'><b>DESCUENTO</b></font></td>";
				}*/
				$salida.= "				<td width='60'><font color='white'><b>SUBTOTAL</b></font></td></tr>";
				$salida.="</tr>";
				$salida.="<tr>";

//FIN ENCABEZADO
			//$arr=ConsultarRecibos($ar[$j][cierre_caja_id]);
			$arr=$ar;
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo2='#CCCCCC';}
				else {$estilo2='#DDDDDD';}
				
				$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
					//si entra aqui es por q ws cierre normal osea hospitalario
					$pac=TraerDatosPacienteControlConfirmado($arr[$i][recibo_caja],$arr[$i][prefijo]);
			//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
				if(empty($pac)){$pac="-------";}
				$fecha=explode(" ",$arr[$i][fecha_registro])	;
				$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
				$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
				$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";

				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$descuento=TraerDescuento($arr[$i][numerodecuenta]);
					$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
				}	*/
				//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
				$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
				//$bon=$bon+$arr[$i][total_bonos];
				$efe=$efe+$arr[$i][total_efectivo];
				$che=$che+$arr[$i][total_cheques];
				$tar=$tar+$arr[$i][total_tarjetas];
				$tbon=$tbon+$arr[$i][total_bonos];
				$tdes=$tdes+$descuento;
				$salida.="</tr>";
			}
		$salida.="</table>";
		$sum=$efe+$che+$tar+$tbon;
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		//print_r($arrdev); 
		$ciclo=sizeof($arrdev);
	 //$ciclo=0; 
		if($ciclo>0)
		{
				$salida.="<br><br><table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][devolucion_id]."</td>";
					$pac=TraerPacienteDevControl($arrdev[$i][devolucion_id],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}
//FIN CIERRE DE CAJA CONFIRMADO CONTROL
	
//CIERRE DE CAJA REPORTE DESDE CAJA
	function GenerarReporteDeCaja($ar,$arrdev)
	{
		//IncludeLib("tarifario");
		$Dir="cache/cierre_de_caja_reporte2".UserGetUID().".pdf";
		//$Dir="cache/reporte_cierre_de_caja".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja';
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
//
		for($j=0;$j<sizeof($ar);$j++)
		{
			$arr=ConsultarRecibos($ar[$j][cierre_caja_id]);
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo2='#CCCCCC';}
				else {$estilo2='#DDDDDD';}
				
				$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
				if($_SESSION['PAGARE']['TIPOCUENTA']=='06')
				{//echo $_SESSION['PAGARE']['TIPOCUENTA'].'PAGARE'.$_SESSION['REF_DPTO'];exit;
					//traemos los pacientes de caja PAGARES
					$pac=TraerPacientePagare($arr[$i][recibo_caja],$arr[$i][prefijo]);
				}
				else
				//if($_SESSION['CAJA']['CIERRE']['DEPTO'])
				if(empty($_SESSION['REF_DPTO']))
				{	//echo REF_DPTO;exit;
					//si entra aqui es por q ws cierre normal osea hospitalario
					$pac=TraerDatosPaciente($arr[$i][recibo_caja],$arr[$i][prefijo]);
				}	
				else
				{//echo NOREF_DPTO;
					//si entra aqui es por q ws cierre es de cualquiere caja rapida
					$pac=TraerDatosPacienteCajaR($arr[$i][recibo_caja],$arr[$i][prefijo]);
				}
			//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
				if(empty($pac)){$pac="-------";}
				$fecha=explode(" ",$arr[$i][fecha_registro])	;
				$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
				$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
				$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";
	
	
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$descuento=TraerDescuento($arr[$i][numerodecuenta]);
					$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
				}	*/
				//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
				$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
				$bon=$bon+$arr[$i][total_bonos];
				$efe=$efe+$arr[$i][total_efectivo];
				$che=$che+$arr[$i][total_cheques];
				$tar=$tar+$arr[$i][total_tarjetas];
				$tbon=$tbon+$arr[$i][total_bonos];
				$tdes=$tdes+$descuento;
				//$sum=$sum + $arr[$i][suma];
				$sum=$sum + $arr[$i][total_abono];
				$salida.="</tr>";
			}
		}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		$ciclo=sizeof($arrdev);
		if($ciclo>0)
		{
				$salida.="<br><br><table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][recibo_caja]."</td>";
					$pac=TraerPacienteDev($arrdev[$i][recibo_caja],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}
//fin REPORTE CIERRE DE CAJA

//GENERAR REPORTE CUADRE DE CAJA DESDE CAJA
	function GenerarReporteDeCuadreCaja($ar,$arrdev,$observa)
	{
		//print_r($ar); exit;
		//IncludeLib("tarifario");
		$Dir="cache/cierre_de_caja_reporte2".UserGetUID().".pdf";
		//$Dir="cache/reporte_cierre_de_caja".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja';
		$_SESSION['REPORTES']['VARIABLE']='cuadre_caja';
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
		//
			$arr=TraerTotales($ar[0][usuario_id],$ar[0][caja_id],'',$ar[0][departamento]);
			for($i=0;$i<sizeof($arr);$i++)
			{
				if( $i % 2){ $estilo2='#CCCCCC';}
				else {$estilo2='#DDDDDD';}
				
				$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][factura_fiscal]."</td>";
			/*	if($_SESSION['PAGARE']['TIPOCUENTA']=='06')
				{//echo $_SESSION['PAGARE']['TIPOCUENTA'].'PAGARE'.$_SESSION['REF_DPTO'];exit;
					//traemos los pacientes de caja PAGARES
					$pac=TraerPacientePagare($arr[$i][recibo_caja],$arr[$i][prefijo]);
				}
				else
				//if($_SESSION['CAJA']['CIERRE']['DEPTO'])
				if(empty($_SESSION['REF_DPTO']))
				{	//echo REF_DPTO;exit;
					//si entra aqui es por q ws cierre normal osea hospitalario
					$pac=TraerDatosPaciente($arr[$i][recibo_caja],$arr[$i][prefijo]);
				}	
				else
				{//echo NOREF_DPTO;*/
					//si entra aqui es por q ws cierre es de cualquiere caja rapida
					$pac=TraerDatosPacienteCajaR($arr[$i][factura_fiscal],$arr[$i][prefijo]);
				//}
			//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
				if(empty($pac)){$pac="-------";}
				$fecha=explode(" ",$arr[$i][fecha_registro])	;
				$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
				$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
				$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
				$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";
	
	
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$descuento=TraerDescuento($arr[$i][numerodecuenta]);
					$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
				}	*/
				//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
				if($arr[$i][total_abono]==-1)
				{
					$salida.="  <td width='60' bgcolor=$estilo><font color='red'>ANULADO</font></td>";
				}
				else
				{
					$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
				}
				if($arr[$i][total_abono]!=-1)
				{
					$bon=$bon+$arr[$i][total_bonos];
					$efe=$efe+$arr[$i][total_efectivo];
					$che=$che+$arr[$i][total_cheques];
					$tar=$tar+$arr[$i][total_tarjetas];
					$tbon=$tbon+$arr[$i][total_bonos];
					$tdes=$tdes+$descuento;
				//$sum=$sum + $arr[$i][suma];
					$sum=$sum + $arr[$i][total_abono];
				}
				$salida.="</tr>";
			}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		$ciclo=sizeof($arrdev);
		if($ciclo>0)
		{
				$salida.="<br><br><table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][recibo_caja]."</td>";
					$pac=TraerPacienteDev($arrdev[$i][recibo_caja],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";

		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if(!empty($observa))
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACIONES :".$observa,0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}

//FIN CUADRAR CAJA DESDE CAJA

	function ConsultarCierres($cierre)
	{
			list($dbconn) = GetDBconn();
//								AND b.sw_facturado='0'
			$query = "SELECT b.cierre_caja_id
								FROM cierre_de_caja a, cierre_de_caja_detalle b
								WHERE a.cierre_de_caja_id=".$cierre."
								AND a.cierre_de_caja_id=b.cierre_de_caja_id;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Guardar en la Base de Datos";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
					}
				$i=0;
				while (!$resulta->EOF)
				{
					$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
					$i++;
				}
			return $var;
	}

//FIN REPORTE CIERRE DE CAJA DESDE CONTROL DE CIERRES
	function GenerarCierreDeCaja()
	{
		//IncludeLib("tarifario");
		$Dir="cache/control_cierre_de_caja".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$_SESSION['REPORTES']['VARIABLE']='cierre_caja';
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
		$datos=DatosEncabezadoEmpresaCierre();
		$user=TraerDatoUsuarioCierre();
		//$salida="".$pdf->image('images/logocliente.png',8,6,12)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
		$dat=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'];
		for($k=0;$k<sizeof($dat);$k++)
		{
			$ar=ConsultarCierres($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][$k][cierre_de_caja_id]);
			//print_r($ar); exit;
			for($j=0;$j<sizeof($ar);$j++)
			{
				$arr=ConsultarRecibos($ar[$j][cierre_caja_id]);
				
				for($i=0;$i<sizeof($arr);$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
					if($_SESSION['CIERRE']['CIERRE_TOTAL']['cuenta']=='03')
					{
						//si entra aqui es por q ws cierre es de cualquiere caja rapida
						$pac=TraerDatosClienteControl($arr[$i][factura_fiscal],$arr[$i][prefijo]);
					}
					else
					if($_SESSION['PAGARE']['TIPOCUENTA']=='06')
					{//echo $_SESSION['PAGARE']['TIPOCUENTA'].'PAGARE'.$_SESSION['REF_DPTO'];exit;
						//traemos los pacientes de caja PAGARES
						$pac=TraerPacientePagare($arr[$i][recibo_caja],$arr[$i][prefijo]);
					}
					else
					//if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					if($arr[$i][sw_facturado]==0)//CAJAS HOSPITALARIOS
					{	//echo REF_DPTO;exit;
						//si entra aqui es por q ws cierre normal osea hospitalario
						$pac=TraerDatosPacienteControl_Cierre($arr[$i][recibo_caja],$arr[$i][prefijo]);
					}	
					else
					{//echo NOREF_DPTO;//CAJAS FACTURADORAS
						//si entra aqui es por q ws cierre es de cualquiere caja rapida
						$pac=TraerDatosPacienteCajaR_Control_Cierre($arr[$i][recibo_caja],$arr[$i][prefijo]);
					}
				//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arr[$i][fecha_registro])	;
					$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";
		
		
					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
					$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][total_abono])."</td>";
					$bon=$bon+$arr[$i][total_bonos];
					$efe=$efe+$arr[$i][total_efectivo];
					$che=$che+$arr[$i][total_cheques];
					$tar=$tar+$arr[$i][total_tarjetas];
					$tbon=$tbon+$arr[$i][total_bonos];
					$tdes=$tdes+$descuento;
					//$sum=$sum + $arr[$i][suma];
					$sum=$sum + $arr[$i][total_abono];
					$salida.="</tr>";
				}
			}
		}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		$ciclo=sizeof($arrdev);
		if($ciclo>0)
		{
				$salida.="<br><br><table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][recibo_caja]."</td>";
					$pac=TraerPacienteDev($arrdev[$i][recibo_caja],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}

/*	function GenerarCierreDeCaja()
	{//print_r($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN']); exit;
		//IncludeLib("tarifario");
		$Dir="cache/control_cierre_de_caja".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$_SESSION['REPORTES']['VARIABLE']='cierre_caja';
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
		$datos=DatosEncabezadoEmpresaCierre();
		$user=TraerDatoUsuarioCierre();
		//$salida="".$pdf->image('images/logocliente.png',8,6,12)."";//$pdf->image('classes/fpdf/clinicaoccidente.gif'
		
		$salida ="<TABLE BORDER='1' WIDTH='1520'>";
		$salida.="<TR>";
		$salida.="<TD ALIGN='CENTER' WIDTH='760'><br><br>";
		$salida.="<FONT SIZE='26'> </FONT>";
		$salida.="</TD>";
		$salida.="</TR>";
		$salida.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>EMPRESA:</TD><TD WIDTH='380' HEIGHT=22>".$datos['razon_social']."</TD></TR>";
		$salida.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>CENTRO DE UTILIDAD:</TD><TD WIDTH='380' HEIGHT=22>".$datos['descripcion']."</TD></TR>";
		$salida.="<TR><font color='#000000'><TD HEIGHT=30 WIDTH='380'><font size='24'><b>CIERRE DE CAJA</b></font></TD><TD WIDTH='380' HEIGHT=30>".$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][cierre_de_caja_id]."</TD></TR>";
		$salida.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>CAJA:</TD><TD WIDTH='380' HEIGHT=22>".$datos['descaja']."</TD></TR>";
		$salida.="<TR><font color='#000000'><TD HEIGHT=22 WIDTH='380'>USUARIO:</TD><TD WIDTH='380' HEIGHT=22>".$user['usuario_id'].'--'.$user['usuario']."</TD></TR>";
		$salida.="<TR>";
		$salida.="  <td width='110' HEIGHT=22 bgcolor=#DDDDDD>FECHA</td>";
		$salida.="  <td width='120' HEIGHT=22 bgcolor=#CCCCCC>TOTAL EFECTIVO</td>";
		$salida.="  <td width='120' HEIGHT=22 bgcolor=#DDDDDD>TOTAL CHEQUES</td>";
		$salida.="  <td width='120' HEIGHT=22 bgcolor=#CCCCCC>TOTAL TARJETAS</td>";
		$salida.="  <td width='120' HEIGHT=22 bgcolor=#DDDDDD>TOTAL DEVOLUCIÓN</td>";
		$salida.="  <td width='125' HEIGHT=22 bgcolor=#CCCCCC>TOTAL ENTREGA</td>";
		$salida.="</TR>";
		$arr=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'];
		for($i=0;$i<sizeof($arr);$i++)
		{
			if( $i % 2){ $estilo2='#CCCCCC';}
			else {$estilo2='#DDDDDD';}
			
/*			$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";*/
/*			$fecha=explode(" ",$arr[$i][fecha_registro])	;
			$salida.="<TR>";
			$salida.="  <td  width='110' bgcolor=$estilo>".$fecha[0]."</td>";
			//$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
			$salida.="  <td  width='120' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
			$salida.="  <td width='120' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
			$salida.="  <td width='120' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
			$salida.="  <td width='120' bgcolor=$estilo>".FormatoValor($arr[$i][total_devolucion])."</td>";
			$salida.="  <td width='125' bgcolor=$estilo>".FormatoValor($arr[$i][entrega_efectivo])."</td>";

			$dev=$dev+$arr[$i][total_devolucion];
			$efe=$efe+$arr[$i][total_efectivo];
			$che=$che+$arr[$i][total_cheques];
			$tar=$tar+$arr[$i][total_tarjetas];
			$tent=$tent+$arr[$i][entrega_efectivo];
			$salida.="</tr>";
		}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"center\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DEVOLUCIÓN :"." ".FormatoValor($dev)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL ENTREGA :"." ".FormatoValor($tent)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"center\" >";
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$prnuser=TraerDatoUsuario();
		$salida.= "				<td align='center' width='75' bgcolor=$estilo><font color='black'><b>IMPRESIÓN</b></font></td>";
		$salida.= "				<td align='center' width='80' bgcolor=$estilo><font color='black'><b>".$prnuser[usuario_id]."</b></font></td>";
		$salida.= "				<td align='center' width='80' bgcolor=$estilo><font color='black'><b>".$prnuser[usuario]."</b></font></td>";
		$salida.= "				<td align='center' width='80' bgcolor=$estilo><font color='black'><b>".$prnuser[nombre]."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$arr[0][observaciones_confirmacion],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
		//unset($_SESSION['observa']);
		//unset($_SESSION['CAJA']['VECTOR_CIERRE']);
		//unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}*/
//FIN REPORTE CIERRE DE CAJA DESDE CONTROL DE CIERRES

	function GenerarCierreCaja($arr,$arrdev)
	{ echo $_SESSION['TMP']['CONTROL_CIERRE']['DPTO'].'*-*'.$_SESSION['CAJA']['TIPOCUENTA'];
		//print_r($arrdev);exit;
		IncludeLib("tarifario");
		$Dir="cache/cierre_caja".UserGetUID().".pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		$_SESSION['REPORTES']['VARIABLE']='cuadre_caja';
		$pdf=new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$spa=" - ";
		$s="  ";
		for($i=0;$i<sizeof($arr);$i++)
		{
			if( $i % 2){ $estilo2='#CCCCCC';}
			else {$estilo2='#DDDDDD';}
			
			$salida.="  <td  width='58' bgcolor=$estilo>".$arr[$i][prefijo]."$sp $sp".$arr[$i][recibo_caja]."</td>";
			if($_SESSION['PAGARE']['TIPOCUENTA']=='06')
			{//echo $_SESSION['PAGARE']['TIPOCUENTA'].'PAGARE'.$_SESSION['REF_DPTO'];exit;
				//traemos los pacientes de caja PAGARES
				$pac=TraerPacientePagare($arr[$i][recibo_caja],$arr[$i][prefijo]);
			}
			else
			//if($_SESSION['CAJA']['CIERRE']['DEPTO'])
			if(!empty($_SESSION['CAJA']['CONCEPTOS']) AND ($_SESSION['CAJA']['CONCEPTOS']=='03' OR $_SESSION['CAJA']['CONCEPTOS']=='08'))
			{
				$pac=TraerDatosCliente($arr[$i][recibo_caja],$arr[$i][prefijo]);
			}
			else
			if(empty($_SESSION['REF_DPTO']))
			{	//echo REF_DPTO;exit;
				//si entra aqui es por q ws cierre normal osea hospitalario
				$pac=TraerDatosPaciente($arr[$i][recibo_caja],$arr[$i][prefijo]);
			}	
			else
			{//echo NOREF_DPTO;
				//si entra aqui es por q ws cierre es de cualquiere caja rapida
				$pac=TraerDatosPacienteCajaR($arr[$i][recibo_caja],$arr[$i][prefijo]);
			}
		//echo $arr[$i][recibo_caja].'--'.$arr[$i][prefijo]; exit;
			if(empty($pac)){$pac="-------";}
			$fecha=explode(" ",$arr[$i][fecha_ingcaja])	;
			$salida.="  <td  width='75' bgcolor=$estilo>".$fecha[0]."</td>";
			$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
			$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_efectivo])."</td>";
			$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_cheques])."</td>";
			$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_tarjetas])."</td>";
			$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arr[$i][total_bonos])."</td>";


			/*if(!empty($_SESSION['REF_DPTO']))
			{	
				$descuento=TraerDescuento($arr[$i][numerodecuenta]);
				$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
			}	*/
			$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arr[$i][suma])."</td>";
			$bon=$bon+$arr[$i][total_bonos];
			$efe=$efe+$arr[$i][total_efectivo];
			$che=$che+$arr[$i][total_cheques];
			$tar=$tar+$arr[$i][total_tarjetas];
			$tbon=$tbon+$arr[$i][total_bonos];
			$tdes=$tdes+$descuento;
			$sum=$sum + $arr[$i][suma];
			$salida.="</tr>";
		}
		$salida.="</table>";
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
  	$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";

		/*if(!empty($_SESSION['REF_DPTO']))
		{	
			$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
		}	*/
		$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL :"." ".FormatoValor($sum)."</b></font></td>";
		$salida.= "			</tr>";
		$salida.="</table>";
//DEVOLUCIONES
		$bond=$efed=$ched=$tard=$tbond=$sumd=$tdeds=0;
		$ciclo=sizeof($arrdev);
		if($ciclo>0)
		{
				$salida.="<br><br><table  align=\"center\" border=\"3\"  bordercolor='#CCCCCC'  width=\"100%\" >";
				$salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$salida.="  <td width='75'>DEVOLUCIÓN</td>";
				$salida.="  <td width='100'>FECHA</td>";
				$salida.="  <td width=75'>CAJA</td>";
				$salida.="  <td width='310'>PACIENTE</td>";
				$salida.="  <td width='57'>TOTAL</td>";
				$salida.="</tr>";
				for($i=0;$i<$ciclo;$i++)
				{
					if( $i % 2){ $estilo2='#CCCCCC';}
					else {$estilo2='#DDDDDD';}
					
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][prefijo]."$sp $sp".$arrdev[$i][recibo_caja]."</td>";
					$pac=TraerPacienteDev($arrdev[$i][recibo_caja],$arrdev[$i][prefijo]);
					if(empty($pac)){$pac="-------";}
					$fecha=explode(" ",$arrdev[$i][fecha_registro])	;
					$salida.="  <td  width='100' bgcolor=$estilo>".$fecha[0]."</td>";
					$salida.="  <td  width='75' bgcolor=$estilo>".$arrdev[$i][caja_id]."</td>";
					$salida.="  <td  width='310' bgcolor=$estilo>".$pac['id']."$spa".$pac['nombre']."</td>";
					$salida.="  <td  width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_devolucion])."</td>";
				/*	$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_cheques])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_tarjetas])."</td>";
					$salida.="  <td width='57' bgcolor=$estilo>".FormatoValor($arrdev[$i][total_bonos])."</td>";*/


					/*if(!empty($_SESSION['REF_DPTO']))
					{	
						$descuento=TraerDescuento($arr[$i][numerodecuenta]);
						$salida.="  <td width='62' bgcolor=$estilo>".FormatoValor($descuento)."</td>";
					}	*/
					//$salida.="  <td width='60' bgcolor=$estilo>".FormatoValor($arrdev[$i][suma])."</td>";
					//$bond=$bond+$arr[$i][total_bonos];
					$efed=$efed+$arrdev[$i][total_devolucion];
					//$ched=$ched+$arr[$i][total_cheques];
					//$tard=$tard+$arr[$i][total_tarjetas];
					//$tbond=$tbon+$arr[$i][total_bonos];
					//$tdesd=$tdesd+$descuento;
					//$sumd=$sumd + $arrdev[$i][suma];
					$salida.="</tr>";
				}
				$salida.="</table>";
				$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
				$salida .= "			<tr>";
				//$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL EFECTIVO :"." ".FormatoValor($efe)."</b></font></td>";
/*				$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>TOTAL CHEQUES :"." ".FormatoValor($che)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL TARJETAS :"." ".FormatoValor($tar)."</b></font></td>";
				$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL BONOS :"." ".FormatoValor($tbon)."</b></font></td>";*/
		
				/*if(!empty($_SESSION['REF_DPTO']))
				{	
					$salida.= "				<td align='center' width='150' bgcolor=$estilo ><font color='black'><b>TOTAL DESCUENTOS :"." ".FormatoValor($tdes)."</b></font></td>";
				}	*/
				$salida.= "				<td align='center' width='180' bgcolor=$estilo ><font color='red'><b>TOTAL DEVOLUCIONES:"." ".FormatoValor($efed)."</b></font></td>";
				$salida.= "			</tr>";
				$salida.="</table>";
		}
//FIN DEVOLUCIONES
		$salida .= "		<br><table width=90 border=\"0\" align=\"left\" >";
		//VALORES
		if($_SESSION['PAGARE']['TIPOCUENTA']<>'06' AND $ciclo>0)
		{
			$cierre=$sum-$efed;
			$salida .= "			<tr>";
			$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black' size='12'><b>CIERRE: </b>$sum - $efed = $cierre</font></td>";
			$salida.= "			</tr>";
		}
		//FIN VALORES
		$salida .= "			<tr>";
		$salida.= "				<td> </td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b>FIRMA DEL USUARIO  $s $s $s ------------------------------------------------------"." </b></font></td>";
		$salida.= "				</tr>";
		$salida .= "			<tr>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "				<td align='center' width='150' bgcolor=$estilo><font color='black'><b></b></font></td>";
		$salida.= "			</tr>";

		//unset($_SESSION['REF_DPTO']);//referencia al depto en el cual se realiza el cierre de caja.
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(203,203,203);
		//$pdf->RotatedText(60,80,GetVarConfigAplication('Cliente'),35);
		$pdf->SetFont('Arial','',7);
		$pdf->SetTextColor(2,2,2);
		$pdf->WriteHTML($salida);

		if($_SESSION['observa'])
		{
			$salida1=$pdf->MultiCell(170,3,"OBSERVACION :".$_SESSION['observa'],0,'J',0);
			$pdf->WriteHTML($salida1);
		}
			unset($_SESSION['observa']);
	//	unset($_SESSION['CAJA']['VECTOR_CIERRE']);
	//	unset($_SESSION['CAJA']['CIERRES']['SEQ']);
		$pdf->Output($Dir,'F');
		return true;
	}

	//}
?>
