<?php

/**
 * $Id: app_Control_Cierre_user.php,v 1.30 2006/12/15 14:38:03 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de control de cierres.
 */

/**
* app_Control_Cierre_user.php
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del modulo Os_Atencion se extiende la clase Os_Atencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/


class app_Control_Cierre_user extends classModulo
{
	/**
	* Es el contructor de la clase Os_Atencion
	* @return boolean
	*/
	var $limit;
	var $conteo;//para saber cuantos registros encontró
	var $uno;

	function app_Control_Cierre_user()
	{
		$this->limit=GetLimitBrowser();
		return true;
	}



	/**
	* La funcion main es la principal y donde se llama FormaPrincipal
	* que muestra los diferentes tipos de busqueda de una cuenta para hospitalización.
	* @access public
	* @return boolean
	*/
	function main()
	{
		if(!$this->BuscarPermisosUser()){
		return false;
	  }
				return true;
  }




  /**
	* La funcion BuscarPermisosUser recibe todas las variables de manejo y verifica si el
	* usuario posee los permisos para acceder al modulo del laboratorio.
	* Nota: las variables pueden llegar por REQUEST o por Parametros.
	* @access private
	* @return boolean
	*/
	function BuscarPermisosUser()
	{

			list($dbconn) = GetDBconn();
			GLOBAL $ADODB_FETCH_MODE;

      $query=" SELECT d.descripcion as cent,e.empresa_id,e.razon_social as emp,
		 								d.centro_utilidad , b.usuario_id
										FROM  userpermisos_control_cierres b,centros_utilidad d,empresas e
										WHERE b.usuario_id=".UserGetUID()."
										AND e.empresa_id=d.empresa_id
										AND d.centro_utilidad=b.centro_utilidad
										AND b.empresa_id=d.empresa_id
										ORDER BY cent";

			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

			$resulta = $dbconn->Execute($query);


			while($data = $resulta->FetchRow())
			{
				$laboratorio[$data['emp']][$data['cent']][$data['cent']]=$data;
			}

			$url[0]='app';
			$url[1]='Control_Cierre';
			$url[2]='user';
			$url[3]='MenuControl';
			$url[4]='control';

			$arreglo[0]='EMPRESA';
			$arreglo[1]='CENTRO UTILIDAD';
			$arreglo[2]='CONTROL DE CIERRES';

			$this->salida.= gui_theme_menu_acceso('SELECCIONAR CENTRO DE UTILIDAD',$arreglo,$laboratorio,$url);
			return true;

	}

//DepartamentosLabora

    function SetStyle($campo)//Mensaje de error en caso de no encontrar los datos
    {
        if ($this->frmError[$campo] || $campo=="MensajeError")
        {
            if ($campo=="MensajeError")
            {
                return ("<tr><td class='label_error' colspan='2' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
            }
            else
            {
                return ("label_error");
            }
        }
        return ("label");
    }


	function MenuControl()
	{
		$_SESSION['CONTROL_CIERRE']['EMP']=$_REQUEST['control']['empresa_id'];
		$_SESSION['CONTROL_CIERRE']['CENTRO']=$_REQUEST['control']['centro_utilidad'];
		$_SESSION['CONTROL_CIERRE']['NOM_CENTRO']=$_REQUEST['control']['cent'];
		$_SESSION['CONTROL_CIERRE']['NOM_EMP']=$_REQUEST['control']['emp'];
		$this->Menu();
		return true;
	}


 /**
  * Busca el departamento y su descripcion en la tabla departamentos.
  * @access public
  * @return array
  */
  function Departamentos()
  {

	    $EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();
      $query = "SELECT a.departamento,a.descripcion
                  FROM departamentos as a,cajas_rapidas b WHERE a.empresa_id='$EmpresaId'
                  AND a.centro_utilidad='$CentroU'
									AND a.departamento=b.departamento";
      $result = $dbconn->Execute($query);


            while (!$result->EOF) {
              $vars[$result->fields[0]]=$result->fields[1];
              $result->MoveNext();
            }

    $result->Close();
    return $vars;
  }


	/*
	* traemos los recibos de caja segun los cierres
	*/
	function TraerRecibos($caja,$id)
	{ 
			list($dbconn) = GetDBconn();
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
/*			$query="select a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,b.caja_id,
				b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
				a.total_tarjetas,usuario_id,a.prefijo,
				(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
				from recibos_caja a,cajas b where a.caja_id=b.caja_id and a.empresa_id='$EmpresaId'
				and a.centro_utilidad='".$CentroU."' and a.cierre_caja_id ISNULL
				and a.caja_id=$caja AND usuario_id=".UserGetUID()."
				and b.cuenta_tipo_id='01'
				order by a.recibo_caja,a.fecha_ingcaja";*/
			$query="SELECT a.empresa_id,a.centro_utilidad,a.recibo_caja,a.fecha_ingcaja,b.caja_id,
				b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
				a.total_tarjetas,usuario_id,a.prefijo,
				(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
				FROM recibos_caja a,cajas b 
				WHERE a.caja_id=b.caja_id AND a.empresa_id='$EmpresaId'
				AND a.centro_utilidad='".$CentroU."' AND a.cierre_caja_id ISNULL
				AND a.caja_id=$caja AND a.usuario_id=$id
				AND b.cuenta_tipo_id='01'
				AND a.estado IN ('0')
				ORDER BY a.recibo_caja,a.fecha_ingcaja";
				$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer los recibos de caja para cierre";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					while(!$resulta->EOF)
				{
						$var[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				return $var;
    }

	/*
	* traemos las devoluciones segun los cierres
	*/

	function TraerDevoluciones($caja,$id)
	{
			list($dbconn) = GetDBconn();
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$query="SELECT	a.empresa_id,a.centro_utilidad,a.recibo_caja,
											b.descripcion as caja,
											a.fecha_registro,b.caja_id, a.total_devolucion,
											a.usuario_id,a.prefijo,a.total_devolucion as suma
									FROM rc_devoluciones a,cajas b 
									WHERE a.caja_id=b.caja_id AND a.empresa_id='$EmpresaId'
									AND a.centro_utilidad='".$CentroU."' AND a.cierre_caja_id ISNULL
									AND a.caja_id=$caja AND a.usuario_id=$id
									AND b.cuenta_tipo_id='01'
									ORDER BY a.recibo_caja,a.fecha_registro";
				$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer los recibos de caja para cierre";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}

					while(!$resulta->EOF)
				{
						$var[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				return $var;
    }

	/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja general(hosp))
*
*/
function TraerPaciente($recibo,$prefijo)
{
		list($dbconn) = GetDBconn();
		$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
		$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
                f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
                f.tipo_id_paciente||' '||f.paciente_id as id

								FROM pacientes f,ingresos s,cuentas x,fac_facturas_cuentas a
                WHERE a.factura_fiscal=".$recibo."
								AND a.prefijo='".$prefijo."'
								AND a.empresa_id='$EmpresaId'
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

			return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];
}



/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja general(hosp))
*
*/
function TraerPacienteCajaGeneral($recibo,$prefijo,$devolucion,$id,$sw,$cuenta,$caja_id)
{
			list($dbconn) = GetDBconn();
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			$cuenta_tipo=$this->ConsultarCuentaTipo($caja_id);
			if($sw==2 AND $cuenta_tipo[0][cuenta_tipo_id]=='06')
			{
					$query = "SELECT btrim(f.primer_nombre||' '||f.segundo_nombre||' ' || f.primer_apellido||' '||f.segundo_apellido,'') as nombre, 
												f.tipo_id_paciente||' '||f.paciente_id as id,
												b.numerodecuenta
									FROM pacientes f,ingresos s,cuentas x,
												 rc_detalle_pagare a, pagares b
									WHERE a.recibo_caja=".$recibo."
											AND a.prefijo='".$prefijo."'
											AND a.centro_utilidad='$CentroU'
											AND a.empresa_id='".$EmpresaId."'
											AND a.empresa_id=b.empresa_id
											AND b.numerodecuenta=x.numerodecuenta 
											AND x.ingreso=s.ingreso 
											AND s.paciente_id=f.paciente_id 
											AND s.tipo_id_paciente=f.tipo_id_paciente
											AND b.prefijo=a.prefijo
											AND b.numero=a.numero;";
			}
			elseif($sw==1 AND ($cuenta=='08' OR $cuenta=='03'))
			{
					$query = "SELECT  b.nombre_tercero as nombre,
									b.tipo_id_tercero||' '||b.tercero_id as id
									FROM fac_facturas_contado a, terceros b
									WHERE a.factura_fiscal=".$recibo." 
									AND a.prefijo='".$prefijo."'
									AND a.empresa_id='$EmpresaId'
									AND a.centro_utilidad='$CentroU'
									AND a.tipo_id_tercero=b.tipo_id_tercero
									AND a.tercero_id=b.tercero_id
									AND a.estado IN ('0');"; 
			}
			else
			if($sw==1)
			{
		$query = "SELECT btrim(f.primer_nombre||' '||f.segundo_nombre||' ' || f.primer_apellido||' '||f.segundo_apellido,'') as nombre, 
												f.tipo_id_paciente||' '||f.paciente_id as id,
												b.numerodecuenta
									FROM pacientes f,ingresos s,cuentas x,fac_facturas_contado a,
											fac_facturas_cuentas b
									WHERE a.factura_fiscal=".$recibo."
											AND a.prefijo='".$prefijo."'
											AND a.factura_fiscal=b.factura_fiscal 
											AND a.prefijo=b.prefijo 
											AND a.centro_utilidad='$CentroU'
											AND a.empresa_id='".$EmpresaId."'
											AND b.numerodecuenta=x.numerodecuenta 
											AND x.ingreso=s.ingreso 
											AND s.paciente_id=f.paciente_id 
											AND s.tipo_id_paciente=f.tipo_id_paciente
											AND a.estado IN ('0','2');"; 
			}
			else
			if(empty($devolucion))
			{
				$query = "SELECT  btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
										f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
										f.tipo_id_paciente||' '||f.paciente_id as id
	
									FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
									WHERE a.recibo_caja=".$recibo."
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='$CentroU'
									AND a.empresa_id='".$EmpresaId."'
									AND a.numerodecuenta=x.numerodecuenta
									AND x.ingreso=s.ingreso
									AND s.paciente_id=f.paciente_id
									AND s.tipo_id_paciente=f.tipo_id_paciente;"; 
			}
			else
			{
				$query = "SELECT trim(d.primer_nombre||' '||d.segundo_nombre||' ' ||
										d.primer_apellido||' '||d.segundo_apellido,'') as nombre,
										d.tipo_id_paciente||' '||d.paciente_id as id
									FROM rc_devoluciones a, cuentas c, pacientes d, ingresos e
									WHERE a.empresa_id='".$EmpresaId."'
										AND a.centro_utilidad='$CentroU'
										AND a.usuario_id=$id
										AND a.numerodecuenta=c.numerodecuenta
										AND c.ingreso=e.ingreso
										AND e.tipo_id_paciente=d.tipo_id_paciente
										AND e.paciente_id=d.paciente_id
										AND a.recibo_caja=".$recibo."
										AND a.prefijo='".$prefijo."'";
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];

}

function TraerClienteConceptos($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
				$query = "SELECT  b.nombre_tercero as nombre,
										b.tipo_id_tercero||' '||b.tercero_id as id
	
									FROM fac_facturas_contado a, terceros b
									WHERE a.factura_fiscal=".$recibo."
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='$CentroU'
									AND a.empresa_id='".$EmpresaId."'
									AND a.tipo_id_tercero=b.tipo_id_tercero
									AND a.tercero_id=b.tercero_id
									AND a.estado IN ('0');"; 
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];

}


function TraerPacienteCajaGeneralDev($cuenta,$id,$cierre,$file)
{
			list($dbconn) = GetDBconn();
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			if(!empty($cuenta) AND empty($file))
			{
				$query = "SELECT distinct trim(d.primer_nombre||' '||d.segundo_nombre||' ' ||
										d.primer_apellido||' '||d.segundo_apellido,'') as nombre,
										d.tipo_id_paciente||' '||d.paciente_id as id
									FROM rc_devoluciones a, cuentas c, pacientes d, ingresos e,
											rc_devoluciones_cierre b
									WHERE a.empresa_id='".$EmpresaId."'
										AND a.centro_utilidad='$CentroU'
										AND a.usuario_id=$id
										AND a.numerodecuenta=$cuenta
										AND b.cierre_caja_id=a.cierre_caja_id
										AND a.numerodecuenta=c.numerodecuenta
										AND c.ingreso=e.ingreso
										AND e.tipo_id_paciente=d.tipo_id_paciente
										AND e.paciente_id=d.paciente_id
										AND b.cierre_caja_id IN (SELECT h.cierre_caja_id
																	FROM cierre_de_caja g,
																			cierre_de_caja_detalle h
																	WHERE g.cierre_de_caja_id=$cierre
																		AND g.cierre_de_caja_id=h.cierre_de_caja_id
																);";
			}
			else
			if(!empty($file))
			{
				$query = "SELECT distinct f.tipo_id_paciente,f.paciente_id,x.plan_id
									FROM pacientes f,ingresos s,cuentas x,rc_devoluciones a
									WHERE a.numerodecuenta=$cuenta
										AND a.usuario_id=$id
										AND a.centro_utilidad='$CentroU'
										AND a.empresa_id='".$EmpresaId."'
										AND a.numerodecuenta=x.numerodecuenta
										AND x.ingreso=s.ingreso
										AND s.paciente_id=f.paciente_id
										AND s.tipo_id_paciente=f.tipo_id_paciente
										AND a.cierre_caja_id IN (SELECT h.cierre_caja_id
																	FROM cierre_de_caja g,
																			cierre_de_caja_detalle h
																	WHERE g.cierre_de_caja_id=$cierre
																		AND g.cierre_de_caja_id=h.cierre_de_caja_id
																);";
					$resulta=$dbconn->Execute($query);
					if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al traer datos del usuario";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
					}
					$var=$resulta->GetRowAssoc($ToUpper = false);
					return $var;
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			return $resulta->fields[1]."&nbsp;-&nbsp;".$resulta->fields[0];

}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre de caja general(hosp))
*
*/
function Traer_Id_Paciente_hosp($recibo,$prefijo,$caja)
{
			list($dbconn) = GetDBconn();
			$cuenta_tipo=$this->ConsultarCuentaTipo($caja);
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			if($cuenta_tipo[0][cuenta_tipo_id]=='06')
			{
			 $query = "SELECT  f.tipo_id_paciente,f.paciente_id,x.plan_id
	
									FROM pacientes f,ingresos s,cuentas x,
											rc_detalle_pagare a, pagares b
									WHERE a.recibo_caja=".$recibo."
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='$CentroU'
									AND a.empresa_id='".$EmpresaId."'
									AND b.numerodecuenta=x.numerodecuenta
									AND x.ingreso=s.ingreso
									AND s.paciente_id=f.paciente_id
									AND s.tipo_id_paciente=f.tipo_id_paciente
									AND a.prefijo=b.prefijo
									AND a.numero=b.numero;";
			}
			else
			{
				$query = "SELECT  f.tipo_id_paciente,f.paciente_id,x.plan_id
	
									FROM pacientes f,ingresos s,cuentas x,rc_detalle_hosp a
									WHERE a.recibo_caja=".$recibo."
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='$CentroU'
									AND a.empresa_id='".$EmpresaId."'
									AND a.numerodecuenta=x.numerodecuenta
									AND x.ingreso=s.ingreso
									AND s.paciente_id=f.paciente_id
									AND s.tipo_id_paciente=f.tipo_id_paciente;";
			}
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			return $resulta->GetRowAssoc($ToUpper = false);

}

function Traer_Id_Cliente($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
				$query = "SELECT b.tipo_id_tercero,b.tercero_id
	
									FROM fac_facturas_contado a, terceros b
									WHERE a.factura_fiscal=".$recibo."
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='$CentroU'
									AND a.empresa_id='".$EmpresaId."'
									AND a.tipo_id_tercero=b.tipo_id_tercero
									AND a.tercero_id=b.tercero_id
									AND a.estado IN ('0');";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer datos del usuario";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}
			return $resulta->GetRowAssoc($ToUpper = false);

}

/*
*funcion que trae el nombre del usuasrio para el reporte de cierre devolución)
*
*/
function Traer_Id_Paciente_dv($recibo,$prefijo)
{
			list($dbconn) = GetDBconn();
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
				$query = "SELECT  f.tipo_id_paciente,f.paciente_id,x.plan_id
									FROM pacientes f,ingresos s,cuentas x,rc_devoluciones a
									WHERE a.recibo_caja=".$recibo."
									AND a.prefijo='".$prefijo."'
									AND a.centro_utilidad='$CentroU'
									AND a.empresa_id='".$EmpresaId."'
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
			return $resulta->GetRowAssoc($ToUpper = false);
}

	function RetornarA()
	{
		unset($_SESSION['CONTROL_CIERRE']['DATOS']);
		$this->BuscarArchivo($_SESSION['CONTROL_CIERRE']['VECT'],$_SESSION['CONTROL_CIERRE']['SW']);
		return true;
	}


	/**
* Funcion que busca un usuario en particular
* @return array
*/

	function TraerUsuario($uid){
    list($dbconn) = GetDBconn();
	  $query = "SELECT  usuario_id,usuario,nombre from system_usuarios WHERE
							usuario_id='$uid'";
	  $result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar 	el usuario";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}else{

							$var=$result->GetRowAssoc($ToUpper = false);
				}
					return $var;
	}




	/*
	* traemos las facturas de las cajas segun los cierres
	*/

	function TraerFacturas($caja,$id,$cierre,$dpto)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();

/*		echo	$query="select a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
								a.fecha_registro as fecha_ingcaja,b.caja_id,
								b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
								a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,e.sw_tipo,
								(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) 									as suma
								from fac_facturas_cuentas e,fac_facturas v,
								fac_facturas_contado a,cajas_rapidas b,userpermisos_cajas_rapidas c

								where a.caja_id=b.caja_id and a.empresa_id='$EmpresaId'
								and a.centro_utilidad='$CentroU'
								and a.cierre_caja_id=$cierre
								and b.departamento='$dpto'
								and c.caja_id=a.caja_id
								and a.usuario_id=c.usuario_id
								and a.usuario_id=$id
								and a.caja_id=$caja
								and e.factura_fiscal=a.factura_fiscal
								and e.prefijo=a.prefijo
								and e.factura_fiscal=v.factura_fiscal
								and e.prefijo=v.prefijo
								and v.estado='0'
								and e.sw_tipo='0'
								order by a.factura_fiscal,a.fecha_registro";*/
/*				$query="SELECT a.factura_fiscal,a.prefijo, a.fecha_registro, a.total_efectivo,
										a.total_tarjetas, a.total_cheques, 
										a.total_bonos, a.tipo_id_tercero, a.tercero_id,a.caja_id,
										c.nombre, d.descripcion as caja,b.sw_facturado, d.descripcion as caja,
										CASE WHEN e.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
								FROM fac_facturas_contado a, recibos_caja_cierre as b,
											system_usuarios as c, cajas_rapidas d, fac_facturas e
								WHERE a.cierre_caja_id=279
								AND a.usuario_id=c.usuario_id
								AND a.cierre_caja_id=b.cierre_caja_id
								AND a.prefijo=e.prefijo
								AND a.factura_fiscal=e.factura_fiscal
								AND b.sw_facturado='1'
								AND d.caja_id=a.caja_id;";*/

				//VERIFICAR LAS FACTURAS QUE SE HAN ANULADO DESPUES DEL CIERRE DE CAJA
				//Y CALCULAR DE NUEVO LOS TOTALES
					 $query="SELECT b.cierre_caja_id
									FROM cierre_de_caja AS a, cierre_de_caja_detalle AS b
									WHERE b.cierre_de_caja_id=$cierre
									AND a.cierre_de_caja_id=b.cierre_de_caja_id;";
					$result=$dbconn->Execute($query);
					$i=0;
					while (!$result->EOF)
					{
						$var2[$i]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$i++;
					}
					//$valoranulado=0;
					for($i=0;$i<sizeof($var2);$i++)
					{
						$query = "SELECT a.factura_fiscal,a.prefijo, a.fecha_registro, a.total_efectivo,
											a.total_tarjetas, a.total_cheques, 
											a.total_bonos, a.tipo_id_tercero, a.tercero_id,a.caja_id,
											c.nombre, d.descripcion as caja,b.sw_facturado, d.descripcion as caja,
											CASE WHEN e.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
									FROM fac_facturas_contado a, recibos_caja_cierre as b,
												system_usuarios as c, cajas_rapidas d, fac_facturas e
									WHERE a.cierre_caja_id=".$var2[$i][cierre_caja_id]."
									AND a.usuario_id=c.usuario_id
									AND a.cierre_caja_id=b.cierre_caja_id
									AND a.prefijo=e.prefijo
									AND a.factura_fiscal=e.factura_fiscal
									AND b.sw_facturado='1'
									AND d.caja_id=a.caja_id
									AND a.estado IN ('0','2');";
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
								$this->error = "Error al Guardar en la Base de Datos";
								$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
								return false;
								}
							$j=0;
							while (!$resulta->EOF)
							{
								$var1[$j]=$resulta->GetRowAssoc($ToUpper = false);
								$resulta->MoveNext();
								$j++;
							}
/*							for($k=0; $k<sizeof($var1); $k++)
							{
								if($var1[$k][total_abono]==-1)
								{
									$valoranulado+=$var1[$k][total_efectivo]+$var1[$k][total_tarjetas]+$var1[$k][total_cheques]+$var1[$k][total_bonos];
								}
							}//fin for*/
					}//fin for
			//FIN
			$query="SELECT a.empresa_id,a.centro_utilidad,
										a.fecha_registro as fecha_ingcaja,b.caja_id,
										b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
										a.total_tarjetas,a.usuario_id,
										(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
										d.nombre,a.cierre_de_caja_id,a.observaciones_confirmacion,
										a.valor_confirmado
								FROM cierre_de_caja a,cajas_rapidas b,userpermisos_cajas_rapidas c,
										system_usuarios d
								WHERE a.caja_id=b.caja_id
								AND a.usuario_id=d.usuario_id
								AND a.empresa_id='$EmpresaId'
								AND a.centro_utilidad='$CentroU'
								AND a.cierre_de_caja_id=$cierre
								AND b.departamento='$dpto'
								AND c.caja_id=a.caja_id
								AND a.usuario_id=c.usuario_id
								AND a.sw_confirmado='1'
								ORDER BY a.fecha_registro;";

        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
      	{
          $var[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      	}
/*				if ($valoranulado!=0)
				{
					$var[ValorAnulado]=$valoranulado;
				}
				else
				{
					$var[ValorAnulado]=0;
				}*/
      	return $var;
   }




function TraerRecibosAnterior($caja,$id,$cierre,$sw)
	{ 
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
			$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
			$cuenta_tipo=$this->ConsultarCuentaTipo($caja);
			list($dbconn) = GetDBconn();
				if($sw==1)
				{
					 $query="SELECT c.cierre_caja_id
									FROM cierre_de_caja_detalle c
									WHERE  c.cierre_de_caja_id=$cierre;";
					$result=$dbconn->Execute($query);
										$i=0;
					while (!$result->EOF)
					{
						$var[$i]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$i++;
					}
					for($i=0;$i<sizeof($var);$i++)
					{
/*					echo	$query="SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal AS recibo_caja,
												a.fecha_registro AS fecha_ingcaja,b.caja_id,
												b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
												a.total_tarjetas,a.usuario_id,a.prefijo,
												(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma
												FROM fac_facturas_contado a, cajas_rapidas b,
															userpermisos_cajas_rapidas c
				
												WHERE a.caja_id=b.caja_id and a.empresa_id='$EmpresaId'
												and a.centro_utilidad='$CentroU'
												and a.cierre_caja_id=$cierre
												and c.caja_id=a.caja_id
												and a.usuario_id=c.usuario_id
												and a.usuario_id=$id
												and a.caja_id=$caja
												order by a.factura_fiscal,a.fecha_registro;";  exit;*/
						$query="SELECT CASE WHEN v.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono,
										a.total_cheques as total_cheques,
										a.total_tarjetas as total_tarjetas,
										a.total_efectivo as total_efectivo,
										a.total_bonos as total_bonos,
										a.fecha_registro AS fecha_ingcaja,a.prefijo,a.factura_fiscal AS recibo_caja,
										(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,c.caja_id,
										c.descripcion as caja, a.usuario_id, a.cierre_caja_id
										FROM fac_facturas_contado a,recibos_caja_cierre b,fac_facturas v, cajas_rapidas c,
										userpermisos_cajas_rapidas d
										WHERE a.usuario_id=b.usuario_id
										AND a.usuario_id=d.usuario_id
										AND a.caja_id=d.caja_id
										AND a.caja_id=c.caja_id
										and a.empresa_id='$EmpresaId'
										and a.centro_utilidad='$CentroU'
										AND a.cierre_caja_id=b.cierre_caja_id
										AND a.prefijo=v.prefijo
										AND a.factura_fiscal=v.factura_fiscal
										AND a.caja_id=$caja
										AND b.sw_facturado='1'
										AND v.estado IN ('0','2')
										AND b.cierre_caja_id=".$var[$i][cierre_caja_id]."
										AND a.estado IN ('0','2')";
									$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al traer las facturas";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}

								while(!$resulta->EOF)
								{
									$var1[]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
								}

					}
				}
				elseif($cuenta_tipo[0][cuenta_tipo_id]=='06')
				{
					$query="SELECT c.cierre_caja_id
									FROM cierre_de_caja_detalle c
									WHERE  c.cierre_de_caja_id=$cierre;";
					$result=$dbconn->Execute($query);
										$i=0;
					while (!$result->EOF)
					{
						$var[$i]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$i++;
					}
					for($i=0;$i<sizeof($var);$i++)
					{

						$query="SELECT a.total_abono as total_abono, 
									a.total_cheques as total_cheques, 
									a.total_tarjetas as total_tarjetas, 
									a.total_efectivo as total_efectivo, 
									a.total_bonos as total_bonos, 
									a.fecha_registro AS fecha_ingcaja,a.prefijo,
									a.recibo_caja, 
									(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,
									c.caja_id, b.descripcion as caja, 
									a.usuario_id, 
									a.cierre_caja_id,
									d.numerodecuenta 
							FROM pagares d,
										rc_detalle_pagare e, 
										recibos_caja a,
										cajas b,
										cajas_usuarios c 
							WHERE a.usuario_id=c.usuario_id 
							AND a.caja_id=b.caja_id 
							AND a.caja_id=c.caja_id and a.empresa_id='$EmpresaId'
							AND a.centro_utilidad='$CentroU'
							AND a.caja_id=$caja
							AND e.recibo_caja=a.recibo_caja 
							AND e.prefijo=a.prefijo 
							AND e.empresa_id=a.empresa_id 
							AND e.centro_utilidad=a.centro_utilidad 
							AND a.cierre_caja_id=".$var[$i][cierre_caja_id]."
							AND d.prefijo=e.prefijo
							AND d.numero=e.numero
							AND a.estado IN ('0')
							ORDER BY a.recibo_caja,a.fecha_registro;";

										$resulta=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al traer las facturas";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}
							
										while(!$resulta->EOF)
										{
											$var1[]=$resulta->GetRowAssoc($ToUpper = false);
											$resulta->MoveNext();
										}

						}
				}
				else
				{
					$query="SELECT c.cierre_caja_id
									FROM cierre_de_caja_detalle c
									WHERE  c.cierre_de_caja_id=$cierre;";
					$result=$dbconn->Execute($query);
										$i=0;
					while (!$result->EOF)
					{
						$var[$i]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						$i++;
					}
					for($i=0;$i<sizeof($var);$i++)
					{
// 					$query="SELECT a.empresa_id,a.centro_utilidad,a.recibo_caja,
// 											a.fecha_ingcaja,b.caja_id,
// 											b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
// 											a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,
// 											(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) 									as suma
// 											FROM rc_detalle_hosp e,
// 											recibos_caja a,cajas b,cajas_usuarios c
// 			
// 											WHERE a.caja_id=b.caja_id 
//											and a.empresa_id='$EmpresaId'
// 											and a.centro_utilidad='$CentroU'
// 											and a.cierre_caja_id=$cierre
// 											and c.caja_id=a.caja_id
// 											and a.usuario_id=c.usuario_id
// 											and a.usuario_id=$id
// 											and a.caja_id=$caja
// 											and e.recibo_caja=a.recibo_caja
// 											and e.prefijo=a.prefijo
// 											order by a.recibo_caja,a.fecha_registro;";

// 						 $query="SELECT a.total_abono as total_abono,
// 										a.total_cheques as total_cheques,
// 										a.total_tarjetas as total_tarjetas,
// 										a.total_efectivo as total_efectivo,
// 										a.total_bonos as total_bonos,
// 										a.fecha_registro AS fecha_ingcaja,a.prefijo,a.factura_fiscal AS recibo_caja,
// 										(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,c.caja_id,
// 										c.descripcion as caja, a.usuario_id, a.cierre_caja_id
// 										FROM fac_facturas_contado a,recibos_caja_cierre b,fac_facturas v, cajas_rapidas c,
// 										userpermisos_cajas_rapidas d
// 										WHERE a.usuario_id=b.usuario_id
// 										AND a.usuario_id=d.usuario_id
// 										AND a.caja_id=d.caja_id
// 										AND a.caja_id=c.caja_id
// 										and a.empresa_id='$EmpresaId'
// 										and a.centro_utilidad='$CentroU'
// 										AND a.cierre_caja_id=b.cierre_caja_id
// 										AND a.prefijo=v.prefijo
// 										AND a.factura_fiscal=v.factura_fiscal
// 										AND a.caja_id=$caja
// 										AND b.sw_facturado='1'
// 										AND v.estado='0'
// 										AND b.cierre_caja_id=".$var[$i][cierre_caja_id]."";

							$query="SELECT a.total_cheques as total_cheques,
										a.total_tarjetas as total_tarjetas,
										a.total_efectivo as total_efectivo,
										a.total_bonos as total_bonos,
										a.fecha_registro AS fecha_ingcaja,a.prefijo,a.recibo_caja,
										(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma,c.caja_id,
										b.descripcion as caja, a.usuario_id, a.cierre_caja_id,
										e.numerodecuenta,
										CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
									FROM rc_detalle_hosp e,
											recibos_caja a,cajas b,cajas_usuarios c
									WHERE a.usuario_id=c.usuario_id
										AND a.caja_id=b.caja_id
										AND a.caja_id=c.caja_id
										and a.empresa_id='$EmpresaId'
										and a.centro_utilidad='$CentroU'
										AND a.caja_id=$caja
										and e.recibo_caja=a.recibo_caja
										and e.prefijo=a.prefijo
										and e.empresa_id=a.empresa_id
										and e.centro_utilidad=a.centro_utilidad
										AND a.cierre_caja_id=".$var[$i][cierre_caja_id]."
										AND a.estado IN ('0','1')
										ORDER BY a.recibo_caja,a.fecha_registro;"; 
										$resulta=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al traer las facturas";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}
							
										while(!$resulta->EOF)
										{
											$var1[]=$resulta->GetRowAssoc($ToUpper = false);
											$resulta->MoveNext();
										}

						}
				}
/*					 $query="SELECT a.empresa_id,a.centro_utilidad,a.caja_id,
										a.cierre_de_caja_id,a.fecha_registro,
										b.descripcion as caja,a.total_efectivo,
										a.total_cheques,a.total_tarjetas,a.total_devolucion,
										entrega_efectivo,a.usuario_id,a.observaciones_confirmacion
									FROM cierre_de_caja a,cajas b,cajas_usuarios c
									WHERE a.caja_id=b.caja_id and a.empresa_id='$EmpresaId' 
										AND a.centro_utilidad='$CentroU' 
										AND a.cierre_de_caja_id=$cierre
										AND c.caja_id=a.caja_id 
										AND a.usuario_id=c.usuario_id 
										AND a.usuario_id=$id
										AND a.caja_id=$caja
									ORDER BY a.fecha_registro";*/
/*					$resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
      	{
          $var[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      	}*/
      	return $var1;
   }

function ConsultarCuentaTipo($caja)
{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();
				$query="SELECT a.cuenta_tipo_id
								FROM cajas a
								WHERE a.empresa_id='$EmpresaId' 
								AND a.centro_utilidad='$CentroU' 
								AND a.caja_id=$caja;";
					$resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
      	{
          $var[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      	}
      	return $var;
}

function TraerDatosCierre($caja,$id,$cierre)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();
				$query="SELECT a.empresa_id,a.centro_utilidad,a.caja_id,
										a.cierre_de_caja_id,a.fecha_registro,
										b.descripcion as caja,a.total_efectivo,
										a.total_cheques,a.total_tarjetas,a.total_devolucion,
										entrega_efectivo,a.usuario_id,a.observaciones_confirmacion,
										a.valor_confirmado,	a.total_bonos
									FROM cierre_de_caja a,cajas b,cajas_usuarios c
									WHERE a.caja_id=b.caja_id and a.empresa_id='$EmpresaId' 
										AND a.centro_utilidad='$CentroU' 
										AND a.cierre_de_caja_id=$cierre
										AND c.caja_id=a.caja_id 
										AND a.usuario_id=c.usuario_id 
										AND a.usuario_id=$id
										AND a.caja_id=$caja
									ORDER BY a.fecha_registro;";
					$resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
      	{
          $var[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      	}
      	return $var;
   }

		function TraerRecibosConceptos($caja,$id,$cierre)
		{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();
			$query="SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal, 
										a.fecha_registro,b.caja_id, b.descripcion as caja, 
										a.total_efectivo,a.total_cheques,a.total_bonos, 
										a.total_tarjetas,a.usuario_id,a.prefijo, 
										(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) as suma 
							FROM fac_facturas_contado a,cajas b,cajas_usuarios c, 
									recibos_caja_cierre d 
							WHERE a.caja_id=b.caja_id and a.empresa_id='$EmpresaId' 
										AND a.centro_utilidad='$CentroU' 
										AND a.cierre_caja_id=$cierre
										AND a.cierre_caja_id=d.cierre_caja_id 
										AND c.caja_id=a.caja_id 
										AND a.usuario_id=c.usuario_id 
										AND a.usuario_id=$id
										AND a.caja_id=$caja
										AND a.estado IN ('0')
							ORDER BY a.factura_fiscal,a.fecha_registro;";
					$resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
      	{
          $var[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      	}
      	return $var;
		}

	function TraerDevAnterior($caja,$id,$cierre)
	{
		$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
		$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
		list($dbconn) = GetDBconn();
//											AND a.usuario_id=$id
		$query=" SELECT e.devolucion_id,e.prefijo, e.recibo_caja,a.cierre_caja_id,
										a.empresa_id, a.centro_utilidad,b.caja_id,b.descripcion as caja,
											e.total_devolucion,a.usuario_id,e.fecha_registro, 
											e.numerodecuenta
									FROM rc_devoluciones e,rc_devoluciones_cierre a, cajas b, 
											cajas_usuarios c
									WHERE a.empresa_id='$EmpresaId' 
											AND a.centro_utilidad='$CentroU' 
											AND a.cierre_caja_id=e.cierre_caja_id
											AND e.caja_id=c.caja_id 
											AND e.usuario_id=c.usuario_id 
											AND b.caja_id=c.caja_id 
											AND a.usuario_id=c.usuario_id 
											AND b.caja_id=$caja 
											AND a.cierre_caja_id IN (SELECT h.cierre_caja_id
																	FROM cierre_de_caja g,
																			cierre_de_caja_detalle h
																	WHERE g.cierre_de_caja_id=$cierre
																		AND g.cierre_de_caja_id=h.cierre_de_caja_id
																)
											ORDER BY a.fecha_registro;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer las facturas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while(!$resulta->EOF)
			{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			return $var;
	}

	function TraerDevUltimoCierre($caja,$id,$cierre)
	{
		$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
		$CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
		//AND a.usuario_id=$id 
		list($dbconn) = GetDBconn();
		$query="SELECT e.devolucion_id,e.prefijo, e.recibo_caja, a.cierre_caja_id,
										a.empresa_id, a.centro_utilidad,b.caja_id,b.descripcion as caja,
										e.total_devolucion,a.usuario_id,e.fecha_registro, 
										e.numerodecuenta
						FROM rc_devoluciones e,rc_devoluciones_cierre a, cajas b, 
											cajas_usuarios c
						WHERE a.empresa_id='$EmpresaId' 
						AND a.centro_utilidad='$CentroU' 
						AND a.cierre_caja_id=e.cierre_caja_id
						AND e.caja_id=c.caja_id 
						AND e.usuario_id=c.usuario_id 
						AND b.caja_id=c.caja_id 
						AND a.usuario_id=c.usuario_id 
						AND b.caja_id=$caja 
						AND a.cierre_caja_id IN(SELECT f.cierre_caja_id 
																		FROM cierre_de_caja d,
																			cierre_de_caja_detalle f
																		WHERE  d.cierre_de_caja_id=$cierre
																		AND d.cierre_de_caja_id=f.cierre_de_caja_id)
						ORDER BY a.fecha_registro;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer las facturas";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
			}

			while(!$resulta->EOF)
			{
				$var[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			return $var;
	}
	/*
	* Cambiamos el formato timestamp a un formato de fecha legible para el usuario
	*/
	function FormateoFechaLocal($fecha)
	{

			if(!empty($fecha))
			{
					$f=explode(".",$fecha);
					$fecha_arreglo=explode(" ",$f[0]);
					$fecha_real=explode("-",$fecha_arreglo[0]);
					return strftime("%A, %d de %B de %Y",strtotime($fecha_arreglo[0]));

			}
			else
			{
				return "-----";
			}

			return true;
	}





	/*
	* funcion que trae la fecha del ultimo cierre de este usuario para caja hospitalaria
	*/
	function TraerUltimoCierreCajaHospitalaria($uid,$caja)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();

/*			$query="SELECT
			max(a.fecha_registro) as fecha_registro,max(a.cierre_de_caja_id) as cierre_caja_id,
			max(a.observaciones) as ob
			FROM recibos_caja_cierre a,recibos_caja b
			WHERE
			a.usuario_id=$uid
			AND a.sw_facturado=0
			AND a.cierre_caja_id=b.cierre_caja_id
			AND b.caja_id=".$caja."
			AND a.empresa_id=b.empresa_id
			AND a.centro_utilidad= b.centro_utilidad
			AND a.empresa_id='$EmpresaId'
			AND a.centro_utilidad='$CentroU'";*/

			//AND a.cierre_de_caja_id=$cierre
			$query="SELECT
										MAX(a.fecha_confirmacion) AS fecha_confirmacion
									FROM cierre_de_caja a, cajas b, cierre_de_caja_detalle d
									WHERE a.usuario_id=$uid
										AND a.caja_id=".$caja."
										AND a.caja_id=b.caja_id
										AND a.empresa_id='$EmpresaId'
										AND a.centro_utilidad='$CentroU'
										AND a.sw_confirmado='1'
										AND a.cierre_de_caja_id=d.cierre_de_caja_id 
										AND d.cierre_caja_id IN
												(SELECT cierre_caja_id 
													FROM recibos_caja_cierre
													WHERE empresa_id='$EmpresaId'
													AND centro_utilidad='$CentroU'
													AND sw_facturado='0');";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de recibos_caja_cierre";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}

	function TraerUltimoCierreCajaHospitalaria2($fecha)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();
			$query="SELECT DISTINCT
										a.fecha_confirmacion AS fecha_confirmacion,
										a.cierre_de_caja_id,a.valor_confirmado, 
										a.observaciones_confirmacion
									FROM cierre_de_caja a, cajas b, cierre_de_caja_detalle d
									WHERE a.caja_id=b.caja_id
										AND a.empresa_id='$EmpresaId'
										AND a.centro_utilidad='$CentroU'
										AND a.fecha_confirmacion='$fecha'
										AND a.sw_confirmado='1'
										AND a.cierre_de_caja_id=d.cierre_de_caja_id 
										AND d.cierre_caja_id IN
												(SELECT cierre_caja_id 
													FROM recibos_caja_cierre
													WHERE empresa_id='$EmpresaId'
													AND centro_utilidad='$CentroU'
													AND sw_facturado='0');";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de recibos_caja_cierre";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}


	function TraerUltimoCierre1($fecha)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();
			$query="SELECT a.fecha_confirmacion AS fecha_confirmacion,
											a.cierre_de_caja_id, a.observaciones_confirmacion
									FROM cierre_de_caja a, cajas_rapidas b, cierre_de_caja_detalle d
									WHERE a.caja_id=b.caja_id
										AND a.empresa_id='$EmpresaId'
										AND a.centro_utilidad='$CentroU'
										AND a.fecha_confirmacion='$fecha'
										AND a.sw_confirmado='1'
										AND a.cierre_de_caja_id=d.cierre_de_caja_id 
										AND d.cierre_caja_id IN
												(SELECT cierre_caja_id 
													FROM recibos_caja_cierre
													WHERE empresa_id='$EmpresaId'
													AND centro_utilidad='$CentroU'
													AND sw_facturado='1');";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de recibos_caja_cierre";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}

	/*
	* funcion que trae la fecha del ultimo cierre de este usuario para caja facturadora
	*/
	function TraerUltimoCierre($uid,$caja)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();

/*			$query="SELECT
			max(a.fecha_registro) as fecha_registro,max(a.cierre_caja_id) as cierre_caja_id,
			max(a.observaciones) as ob
			FROM recibos_caja_cierre a,fac_facturas_contado b
			WHERE
			a.usuario_id=$uid
			AND a.sw_facturado='1'
			AND a.cierre_caja_id=b.cierre_caja_id
			AND b.caja_id=".$caja."
			AND a.empresa_id=b.empresa_id
			AND a.centro_utilidad= b.centro_utilidad
			AND a.empresa_id='$EmpresaId'
			AND a.centro_utilidad='$CentroU'";*/
			$query="SELECT
										MAX(a.fecha_confirmacion) as fecha_confirmacion
									FROM cierre_de_caja a, cajas_rapidas b, 
												cierre_de_caja_detalle d, userpermisos_cajas_rapidas c
									WHERE a.usuario_id=$uid
										AND b.caja_id=c.caja_id 
										AND a.usuario_id=c.usuario_id 
										AND a.caja_id=".$caja."
										AND a.caja_id=b.caja_id
										AND a.empresa_id='$EmpresaId'
										AND a.centro_utilidad='$CentroU'
										AND a.sw_confirmado='1'
										AND a.cierre_de_caja_id=d.cierre_de_caja_id 
										AND d.cierre_caja_id IN
												(SELECT cierre_caja_id 
													FROM recibos_caja_cierre
													WHERE empresa_id='$EmpresaId'
													AND centro_utilidad='$CentroU'
													AND sw_facturado='1');";

			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de recibos_caja_cierre";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			$var=$resulta->GetRowAssoc($ToUpper = false);
			return $var;
	}


	//acordar de que hay q' colocarle el filtro de la fecha de hoy
	//traer totales es solo para cajas facturadoras ok.
	//function TraerTotales($uid,$caja,$cierre)
	function TraerTotales($cierre)
	{
		list($dbconn) = GetDBconn();
/*		$query="SELECT a.total_abono,a.total_efectivo,a.total_tarjetas,a.total_bonos
						FROM fac_facturas_contado a,fac_facturas b
						WHERE a.usuario_id=$uid
						AND a.caja_id=$caja
						AND a.cierre_caja_id isnull
						AND a.prefijo=b.prefijo
						AND a.factura_fiscal=b.factura_fiscal
						AND b.estado='0'";*/
/*		$query="SELECT a.entrega_efectivo,a.total_efectivo,a.total_cheques,
									a.total_tarjetas, a.total_devolucion,a.fecha_registro,
									a.observaciones, b.nombre, b.descripcion as des, a.usuario_id,
									a.cierre_de_caja_id, a.observaciones
							FROM cierre_de_caja a, system_usuarios b
							WHERE a.usuario_id=$uid
							AND a.usuario_id=b.usuario_id
							AND a.cierre_de_caja_id=$cierre
							AND a.caja_id=$caja
							AND a.sw_confirmado=0;";*/
			$query="SELECT a.entrega_efectivo,a.total_efectivo,a.total_cheques,
									a.total_tarjetas, a.total_devolucion,a.fecha_registro,
									a.observaciones, b.nombre, b.descripcion as des, a.usuario_id,
									a.cierre_de_caja_id, a.observaciones
							FROM cierre_de_caja a, system_usuarios b
							WHERE a.usuario_id=b.usuario_id
							AND a.cierre_de_caja_id=$cierre
							AND a.sw_confirmado=0;";
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



	//acordar de que hay q' colocarle el filtro de la fecha de hoy
	//traer totales es solo para cajas hospitalarias ok.
	//function TraerTotalesRecibos($uid,$caja)
	function TraerTotalesRecibos($cierre)
	{
		list($dbconn) = GetDBconn();
/*			$query="SELECT a.total_abono,a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos
									FROM recibos_caja a,rc_detalle_hosp b
									WHERE a.usuario_id=$uid
									AND a.caja_id=$caja
									AND a.cierre_caja_id isnull
									AND a.prefijo=b.prefijo
									AND a.recibo_caja=b.recibo_caja
									AND a.estado='0'";*/
/*			 $query="SELECT a.entrega_efectivo,a.total_efectivo,a.total_cheques,
											a.total_tarjetas, a.total_devolucion,a.fecha_registro,
											a.observaciones, b.nombre, b.descripcion as des, a.usuario_id,
											a.cierre_de_caja_id, a.observaciones, a.total_bonos
									FROM cierre_de_caja a, system_usuarios b
									WHERE a.usuario_id=$uid
									AND a.usuario_id=b.usuario_id
									AND a.caja_id=$caja
									AND a.sw_confirmado=0;"; */
			 $query="SELECT a.entrega_efectivo,a.total_efectivo,a.total_cheques,
											a.total_tarjetas, a.total_devolucion,a.fecha_registro,
											a.observaciones, b.nombre, b.descripcion as des, a.usuario_id,
											a.cierre_de_caja_id, a.observaciones, a.total_bonos
									FROM cierre_de_caja a, system_usuarios b
									WHERE a.cierre_de_caja_id=$cierre
									AND a.usuario_id=b.usuario_id
									AND a.sw_confirmado=0;"; 
							//ojo con el estado del recibo q actualmente es 0.
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

	//acordar de que hay q' colocarle el filtro de la fecha de hoy
	//traer totales es solo para cajas hospitalarias ok.
	function TraerTotalesDevoluciones($uid,$caja)
	{
		/*SELECT a.total_abono,a.total_efectivo,a.total_tarjetas,a.total_bonos
		FROM recibos_caja a left join rc_devoluciones c on(c.empresa_id=a.empresa_id AND c.centro_utilidad=a.centro_utilidad AND c.prefijo=a.prefijo AND c.recibo_caja=a.recibo_caja AND c.cierre_caja_id ISNULL) 
		WHERE a.usuario_id=2 AND a.caja_id=3 AND a.cierre_caja_id isnull AND a.estado='0'*/
/*echo	$query="SELECT SUM(a.total_devolucion) AS total_devolucion
										FROM rc_devoluciones a, cajas
										WHERE a.usuario_id=$uid
										AND a.caja_id=$caja
										AND a.fecha='$fecha'
										AND (a.cierre_caja_id isnull
																		OR (a.cierre_caja_id is not null
																		AND a.cierre_caja_id NOT IN
																		(SELECT b.cierre_caja_id
																		FROM rc_devoluciones_cierre b
																		WHERE b.usuario_id=$uid)))
										AND a.estado='0';";exit;*/
			list($dbconn) = GetDBconn();
	$query="SELECT MAX(a.fecha_registro),a.total_devolucion AS total_devolucion 
							FROM rc_devoluciones a, cajas b 
							WHERE a.usuario_id=$uid
							AND a.caja_id=$caja
							AND a.caja_id=b.caja_id
							AND (a.cierre_caja_id isnull OR (
										a.cierre_caja_id IS NOT NULL AND a.cierre_caja_id IN
												(SELECT b.cierre_caja_id 
												FROM rc_devoluciones_cierre b 
												WHERE b.usuario_id=$uid) AND a.cierre_caja_id NOT IN
												(SELECT d.cierre_caja_id
												FROM cierre_de_caja c, cierre_de_caja_detalle d
												WHERE c.cierre_de_caja_id=d.cierre_de_caja_id)))
							AND a.estado='0'
							GROUP BY a.total_devolucion;";
							//ojo con el estado del recibo q actualmente es 0.
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

	function TraerTotalesDevolucionesFact($fecha,$uid,$caja)
	{
		/*SELECT a.total_abono,a.total_efectivo,a.total_tarjetas,a.total_bonos
		FROM recibos_caja a left join rc_devoluciones c on(c.empresa_id=a.empresa_id AND c.centro_utilidad=a.centro_utilidad AND c.prefijo=a.prefijo AND c.recibo_caja=a.recibo_caja AND c.cierre_caja_id ISNULL) 
		WHERE a.usuario_id=2 AND a.caja_id=3 AND a.cierre_caja_id isnull AND a.estado='0'*/
/*echo	$query="SELECT SUM(a.total_devolucion) AS total_devolucion
										FROM rc_devoluciones a, cajas
										WHERE a.usuario_id=$uid
										AND a.caja_id=$caja
										AND a.fecha='$fecha'
										AND (a.cierre_caja_id isnull
																		OR (a.cierre_caja_id is not null
																		AND a.cierre_caja_id NOT IN
																		(SELECT b.cierre_caja_id
																		FROM rc_devoluciones_cierre b
																		WHERE b.usuario_id=$uid)))
										AND a.estado='0';";exit;*/
			list($dbconn) = GetDBconn();
	$query="SELECT SUM(a.total_devolucion) AS total_devolucion 
							FROM rc_devoluciones a, cajas_rapidas b 
							WHERE a.usuario_id=$uid
							AND a.caja_id=$caja
							AND a.caja_id=b.caja_id
							AND (a.cierre_caja_id isnull OR 
									(a.cierre_caja_id is not null AND a.cierre_caja_id NOT IN 
											(SELECT b.cierre_caja_id 
											FROM rc_devoluciones_cierre b 
											WHERE b.usuario_id=$uid)))
											AND a.estado='0';"; 
							//ojo con el estado del recibo q actualmente es 0.
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

//TRAER DEVOLUCIONES SIN CUADRAR
	function TraerDevolucionesSinCuadrar($uid,$caja)
	{
		/*SELECT a.total_abono,a.total_efectivo,a.total_tarjetas,a.total_bonos
		FROM recibos_caja a left join rc_devoluciones c on(c.empresa_id=a.empresa_id AND c.centro_utilidad=a.centro_utilidad AND c.prefijo=a.prefijo AND c.recibo_caja=a.recibo_caja AND c.cierre_caja_id ISNULL) 
		WHERE a.usuario_id=2 AND a.caja_id=3 AND a.cierre_caja_id isnull AND a.estado='0'*/
		list($dbconn) = GetDBconn();
				$query="SELECT SUM(total_devolucion) AS total_devolucion 
										FROM rc_devoluciones 
										WHERE usuario_id=$uid 
										AND caja_id=$caja 
										AND fecha_registro=
												(	SELECT MAX(fecha_registro) 
													FROM rc_devoluciones 
													WHERE usuario_id=$uid
													AND caja_id=$caja) 
										AND cierre_caja_id isnull 
										AND estado='0';";
							//ojo con el estado del recibo q actualmente es 0.
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

//FIN TRAER DEVOLUCIONES SIN CUADRAR
	/*
	* ir al listado de los pagos de la caja el dia de hoy
	*/
	function IrListadoCierre()
	{
	    $vect=$_SESSION['CONTROL_CIERRE']['VECT_FACT_HOY'];
			unset($_SESSION['CONTROL_CIERRE']['VECT_FACT_HOY']);
			$sw=$_REQUEST['sw_recibo'];
			$this->BusquedaCajasHoy($vect,$sw);
			return true;
	}
		
	
	/*
	* funcion q trae las factueras para poder imprimirlas.
	*/
	function GetFacturasActuales($caja,$id,$dpto)
	{
			$EmpresaId=$_SESSION['CONTROL_CIERRE']['EMP'];
      $CentroU=$_SESSION['CONTROL_CIERRE']['CENTRO'];
      list($dbconn) = GetDBconn();

		 	 $query="  SELECT a.empresa_id,a.centro_utilidad,a.factura_fiscal as recibo_caja,
								a.fecha_registro as fecha_ingcaja,b.caja_id,
								b.descripcion as caja, a.total_efectivo,a.total_cheques,a.total_bonos,
								a.total_tarjetas,a.usuario_id,a.prefijo,e.numerodecuenta,e.sw_tipo,
								(a.total_efectivo + a.total_cheques + a.total_tarjetas + a.total_bonos) 									as suma
								FROM 
								fac_facturas_cuentas e,	fac_facturas_contado a,fac_facturas v,
								cajas_rapidas b,userpermisos_cajas_rapidas c

								WHERE
								a.caja_id=b.caja_id and a.empresa_id='$EmpresaId'
								and a.centro_utilidad='$CentroU'
								and a.cierre_caja_id ISNULL
								and b.departamento='$dpto'
								and c.caja_id=a.caja_id
								and a.usuario_id=c.usuario_id
								and a.usuario_id=$id
								and a.caja_id=$caja
								and e.factura_fiscal=a.factura_fiscal
								and e.prefijo=a.prefijo
								and e.factura_fiscal=v.factura_fiscal
								and e.prefijo=v.prefijo
								and e.sw_tipo='0'
								and v.estado='0'
								and a.estado IN ('0')
								order by a.factura_fiscal,a.fecha_registro";

        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        while(!$resulta->EOF)
      	{
          $var[]=$resulta->GetRowAssoc($ToUpper = false);
          $resulta->MoveNext();
      	}
      	return $var;	
	}
	

	//BUSQUEDA CAJAS HOSPITALARIAS Y FACTURADORAS
	function BusquedaHosp()
	{ 
			list($dbconn) = GetDBconn();

/*		echo	$query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
								d.descripcion as des
						FROM cierre_de_caja a,cajas b,system_usuarios d ,
								cajas_usuarios c, cierre_de_caja_detalle e
						WHERE a.caja_id=b.caja_id
							AND a.caja_id=c.caja_id
							AND a.sw_confirmado='0'
							AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							AND c.usuario_id=d.usuario_id
							AND c.usuario_id=a.usuario_id
							AND a.cierre_de_caja_id=e.cierre_de_caja_id
							AND e.cierre_caja_id IN(SELECT b.cierre_caja_id
																			FROM recibos_caja_cierre b
																			WHERE b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
																			AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
																			AND b.sw_facturado='0')
							ORDER BY b.descripcion;"; exit;*/
							//MAX(a.cierre_de_caja_id) AS 
							$query="SELECT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
													 d.descripcion as desa ,a.cierre_de_caja_id 
							FROM cierre_de_caja a,cajas b,system_usuarios d , cajas_usuarios c,
							  cierre_de_caja_detalle e 
							WHERE a.caja_id=b.caja_id 
							AND a.caja_id=c.caja_id 
							AND a.sw_confirmado='0' 
							AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
							AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
							AND c.usuario_id=d.usuario_id 
							AND c.usuario_id=a.usuario_id 
							AND a.cierre_de_caja_id=e.cierre_de_caja_id 
							AND e.cierre_caja_id IN(SELECT b.cierre_caja_id 
																			FROM recibos_caja_cierre b 
																			WHERE b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
																			AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
																			AND b.sw_facturado='0') 
AND a.sw_estado IN ('1')
						GROUP BY a.usuario_id,a.caja_id,d.nombre,b.descripcion, d.descripcion,
										a.cierre_de_caja_id
						ORDER BY b.descripcion;";
					//colocarle el filtro de la fecha de hoy
						$resulta=$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al traer la consulta de los cierres";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						$i=0;

// 						if($resulta->EOF)
// 						{
// 							$this->BusquedaCajasHoy('show');
// 							return true;
// 						}

						while (!$resulta->EOF)
						{
							$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
							$resulta->MoveNext();
							$i++;
						}
			return $var;
	}

	function BusquedaFact()
	{
					$dpto=$_REQUEST['departamento'];
					if($_REQUEST['departamento']=='/a/')
					{$search_dpto='';}else{$search_dpto="AND b.departamento='$dpto'";}

      				list($dbconn) = GetDBconn();

/*							$query="select  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
							d.descripcion as des
							FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
							userpermisos_cajas_rapidas c
							where
							a.caja_id=b.caja_id
							and a.cierre_caja_id isnull
							and a.caja_id=c.caja_id
							and a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							and a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							and c.usuario_id=d.usuario_id
							and c.usuario_id=a.usuario_id $search_dpto
							order by b.descripcion ";*/
							$query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
												d.descripcion as des,a.cierre_de_caja_id
											FROM cierre_de_caja a,cajas_rapidas b,system_usuarios d,
												userpermisos_cajas_rapidas c, cierre_de_caja_detalle e
											WHERE a.caja_id=b.caja_id
											AND a.cierre_de_caja_id IS NOT NULL
											AND a.sw_confirmado='0'
											AND a.caja_id=c.caja_id
											AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
											AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
											AND c.usuario_id=d.usuario_id
											AND c.usuario_id=a.usuario_id 
											AND a.cierre_de_caja_id=e.cierre_de_caja_id
											AND e.cierre_caja_id IN
													(
														SELECT b.cierre_caja_id
														FROM recibos_caja_cierre b
														WHERE b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
														AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
														AND b.sw_facturado='1'
										   		 )

        	AND a.sw_estado IN ('1')										ORDER BY b.descripcion;";    
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

// 								if($resulta->EOF)
// 								{
// 									$this->BusquedaCajasHoy('show');
// 									return true;
// 								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}
				return $var;
	}
	//

	function BusquedaCierreConfir($cierre_de_caja,$sw)
	{

      				list($dbconn) = GetDBconn();

			if($sw==2)
			{
							$query="SELECT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
														d.descripcion as des,a.cierre_de_caja_id,
														a.total_efectivo, a.total_cheques, a.total_tarjetas,
														a.total_devolucion,a.entrega_efectivo,a.fecha_registro,
														a.total_bonos
													FROM cierre_de_caja a,cajas b,system_usuarios d,
														cajas_usuarios c
													WHERE a.caja_id=b.caja_id
													AND a.cierre_de_caja_id IS NOT NULL
													AND a.cierre_de_caja_id=$cierre_de_caja
													AND a.sw_confirmado='0'
													AND a.caja_id=c.caja_id
													AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
													AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
													AND c.usuario_id=d.usuario_id
													AND sw_confirmado='0'
													AND c.usuario_id=a.usuario_id
													ORDER BY b.descripcion;"; 
						}
						elseif($sw==1)
						{
							$query="SELECT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
														d.descripcion as des,a.cierre_de_caja_id,
														a.total_efectivo, a.total_cheques, a.total_tarjetas,
														a.total_devolucion,a.entrega_efectivo,a.fecha_registro,
														a.total_bonos
													FROM cierre_de_caja a,cajas_rapidas b,system_usuarios d,
														userpermisos_cajas_rapidas c
													WHERE a.caja_id=b.caja_id
													AND a.cierre_de_caja_id IS NOT NULL
													AND a.cierre_de_caja_id=$cierre_de_caja
													AND a.sw_confirmado='0'
													AND a.caja_id=c.caja_id
													AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
													AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
													AND c.usuario_id=d.usuario_id
													AND sw_confirmado='0'
													AND c.usuario_id=a.usuario_id 
													ORDER BY b.descripcion;";    
						}
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BusquedaCajasHoy('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}
				return $var;
	}

	/*
	* funcion que realiza las busqueda de losn cierres que se hacen en la fecha actual.
	*/
	function Busqueda($reload)
	{ 
			if($reload)
				$_REQUEST['criterio']=$reload;
			if($_REQUEST['criterio']=='2') //caja hospitalarias..
			{
					list($dbconn) = GetDBconn();

/*							$query="select  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
							d.descripcion as des
							FROM recibos_caja a,cajas b,system_usuarios d ,
							cajas_usuarios c
							where
							a.caja_id=b.caja_id
							and a.cierre_caja_id isnull
							and a.caja_id=c.caja_id
							and a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							and a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							and c.usuario_id=d.usuario_id
							and c.usuario_id=a.usuario_id
							and b.cuenta_tipo_id='01'
							order by b.descripcion ";*/
							  $query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
													d.descripcion as des
											FROM cierre_de_caja a,cajas b,system_usuarios d ,
													cajas_usuarios c, cierre_de_caja_detalle e
											WHERE a.caja_id=b.caja_id
												AND a.caja_id=c.caja_id
												AND a.sw_confirmado='0'
												AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
												AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
												AND c.usuario_id=d.usuario_id
												AND c.usuario_id=a.usuario_id
												AND a.cierre_de_caja_id=e.cierre_de_caja_id
												AND e.cierre_caja_id IN(SELECT b.cierre_caja_id
																								FROM recibos_caja_cierre b
																								WHERE b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
																								AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
																								AND b.sw_facturado='0')
												ORDER BY b.descripcion;"; 
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BusquedaCajasHoy('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}

				$this->BusquedaCajasHoy($var,2);
				return true;
			}

			//parte de las cajas facturadoras.
			elseif($_REQUEST['criterio']=='1')//cajas facturadoras..
			{
					$dpto=$_REQUEST['departamento'];
					if($_REQUEST['departamento']=='/a/')
					{$search_dpto='';}else{$search_dpto="AND b.departamento='$dpto'";}

      				list($dbconn) = GetDBconn();

/*							$query="select  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
							d.descripcion as des
							FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
							userpermisos_cajas_rapidas c
							where
							a.caja_id=b.caja_id
							and a.cierre_caja_id isnull
							and a.caja_id=c.caja_id
							and a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							and a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							and c.usuario_id=d.usuario_id
							and c.usuario_id=a.usuario_id $search_dpto
							order by b.descripcion ";*/
							$query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
														d.descripcion as des,a.cierre_de_caja_id
													FROM cierre_de_caja a,cajas_rapidas b,system_usuarios d,
														userpermisos_cajas_rapidas c, cierre_de_caja_detalle e
													WHERE a.caja_id=b.caja_id
													AND a.cierre_de_caja_id IS NOT NULL
													AND a.sw_confirmado='0'
													AND a.caja_id=c.caja_id
													AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
													AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
													AND c.usuario_id=d.usuario_id
													AND sw_confirmado='0'
													AND c.usuario_id=a.usuario_id $search_dpto
													AND a.cierre_de_caja_id=e.cierre_de_caja_id
													AND e.cierre_caja_id IN(SELECT b.cierre_caja_id
																									FROM recibos_caja_cierre b
																									WHERE b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
																									AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
																									AND b.sw_facturado='1')
													ORDER BY b.descripcion;";
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BusquedaCajasHoy('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}

				$this->BusquedaCajasHoy($var,1);
				return true;
		}
		return true;
	}

//****************************************************
//****************************************************
//****************************************************
//BUSQUEDA DE CAJAS QUE TIENEN MOVIMIENTO ACTUALMENTE 
function TraerUsuariosMov()
{
/*
SELECT max(fecha_registro), a.usuario_id, a.caja_id,d.nombre,b.descripcion,
				d.descripcion as des
FROM recibos_caja a,cajas b,system_usuarios d,
			cajas_usuarios c
WHERE a.caja_id=b.caja_id
	AND ((a.cierre_caja_id isnull and a.sw_facturado isnull)
				OR (a.cierre_caja_id is not null
				AND a.cierre_caja_id NOT IN
													(SELECT b.cierre_caja_id
													FROM cierre_de_caja a,cierre_de_caja_detalle b
													WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
	AND a.caja_id=c.caja_id
	AND a.empresa_id='01'
	AND a.centro_utilidad='01'
	AND c.usuario_id=d.usuario_id
	AND c.usuario_id=a.usuario_id
	GROUP BY a.usuario_id,a.caja_id,d.nombre,b.descripcion,
				d.descripcion
*/
		list($dbconn) = GetDBconn();
		$query=" SELECT DISTINCT a.caja_id
		FROM cajas a, recibos_caja b, cajas_usuarios c
		WHERE a.caja_id=b.caja_id
		AND	a.caja_id=c.caja_id
		AND	b.usuario_id=c.usuario_id
		AND	b.caja_id=c.caja_id
		AND b.estado IN ('0')
		AND (b.cierre_caja_id ISNULL OR (
												b.cierre_caja_id IS NOT NULL
												AND b.cierre_caja_id NOT IN(
																			SELECT b.cierre_caja_id 
																			FROM cierre_de_caja a,cierre_de_caja_detalle b 
																			WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)));";
			$result=$dbconn->Execute($query);
		$i=0;
		while (!$result->EOF)
		{
			$var1[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}
	$j=0;
	for($i=0;$i<sizeof($var1);$i++)
	{
		$query="SELECT a.fecha_registro as fecha, a.caja_id, a.usuario_id 
		FROM recibos_caja a, cajas b, cajas_usuarios c
		WHERE (a.cierre_caja_id isnull 
					OR (a.cierre_caja_id is not null 
							AND a.cierre_caja_id NOT IN(
														SELECT b.cierre_caja_id 
														FROM cierre_de_caja a,cierre_de_caja_detalle b 
														WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
		AND a.caja_id=b.caja_id 
		AND a.caja_id=c.caja_id 
		AND b.caja_id=c.caja_id 
		AND a.usuario_id=c.usuario_id 
		AND a.caja_id=".$var1[$i][caja_id]."
		AND a.estado IN ('0')
		AND a.fecha_registro=(SELECT MAX(a.fecha_registro) 
													FROM recibos_caja a, cajas b 
													WHERE (a.cierre_caja_id isnull 
																OR (a.cierre_caja_id is not null 
																		AND a.cierre_caja_id NOT IN(
																											SELECT b.cierre_caja_id 
																											FROM cierre_de_caja a,cierre_de_caja_detalle b 
																											WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))) 
													AND a.caja_id=b.caja_id AND a.caja_id=".$var1[$i][caja_id]."
													AND a.estado IN ('0')) 
		GROUP BY a.caja_id,a.usuario_id,a.fecha_registro;";
			//colocarle el filtro de la fecha de hoy
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al traer la consulta de los cierres";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
	// 	if($resulta->EOF)
	// 	{
	// 		$this->BusquedaCajasHoy('show');
	// 		return true;
	// 	}
	
		while (!$resulta->EOF)
		{
			$var[$j]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$j++;
		}
	}
	return $var;
}
//****************************************************
//****************************************************
//****************************************************
//FIN BUSAQUEDA DE CAJAS QUE NO HAN REALIZADO CUADRES Y/O 
//TIENEN MOVIMIENTO AUN. - HOSPITALARIAS
//****************************************************
//BUSQUEDA DE CAJAS QUE TIENEN MOVIMIENTO ACTUALMENTE 
function TraerUsuariosMovFact()
{
/*
SELECT max(fecha_registro), a.usuario_id, a.caja_id,d.nombre,b.descripcion,
				d.descripcion as des
FROM recibos_caja a,cajas b,system_usuarios d,
			cajas_usuarios c
WHERE a.caja_id=b.caja_id
	AND ((a.cierre_caja_id isnull and a.sw_facturado isnull)
				OR (a.cierre_caja_id is not null
				AND a.cierre_caja_id NOT IN
													(SELECT b.cierre_caja_id
													FROM cierre_de_caja a,cierre_de_caja_detalle b
													WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
	AND a.caja_id=c.caja_id
	AND a.empresa_id='01'
	AND a.centro_utilidad='01'
	AND c.usuario_id=d.usuario_id
	AND c.usuario_id=a.usuario_id
	GROUP BY a.usuario_id,a.caja_id,d.nombre,b.descripcion,
				d.descripcion
*/
		list($dbconn) = GetDBconn();
		$query=" SELECT DISTINCT a.caja_id
						FROM cajas_rapidas a, fac_facturas_contado b,
									userpermisos_cajas_rapidas c
						WHERE a.caja_id=b.caja_id
						AND a.caja_id=c.caja_id
						AND b.caja_id=c.caja_id
						AND b.usuario_id=c.usuario_id
						AND b.estado IN ('0')
						AND (b.cierre_caja_id ISNULL OR (
												b.cierre_caja_id IS NOT NULL
												AND b.cierre_caja_id NOT IN(
																SELECT b.cierre_caja_id 
																FROM cierre_de_caja a,cierre_de_caja_detalle b 
																WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)));";
		$result=$dbconn->Execute($query);
		$i=0;
		while (!$result->EOF)
		{
			$var1[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}
	$j=0;
	for($i=0;$i<sizeof($var1);$i++)
	{
		$query="SELECT a.fecha_registro as fecha, a.caja_id, a.usuario_id 
		FROM fac_facturas_contado a, cajas_rapidas b,
					userpermisos_cajas_rapidas c
		WHERE a.caja_id=c.caja_id
				AND a.usuario_id=c.usuario_id
				AND a.estado IN ('0')
				AND b.caja_id=c.caja_id
				AND	(a.cierre_caja_id isnull 
					OR (a.cierre_caja_id is not null 
							AND a.cierre_caja_id NOT IN(
														SELECT b.cierre_caja_id 
														FROM cierre_de_caja a,cierre_de_caja_detalle b 
														WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))) 
		AND a.caja_id=b.caja_id AND a.caja_id=".$var1[$i][caja_id]."
		AND a.fecha_registro=(SELECT MAX(a.fecha_registro) 
													FROM fac_facturas_contado a, cajas_rapidas b 
													WHERE (a.cierre_caja_id isnull 
																OR (a.cierre_caja_id is not null 
																		AND a.cierre_caja_id NOT IN(
																											SELECT b.cierre_caja_id 
																											FROM cierre_de_caja a,cierre_de_caja_detalle b 
																											WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))) 
													AND a.caja_id=b.caja_id AND a.caja_id=".$var1[$i][caja_id]."
													AND a.estado IN ('0')
													) 
		GROUP BY a.caja_id,a.usuario_id,a.fecha_registro;";
			//colocarle el filtro de la fecha de hoy
				$resulta=$dbconn->Execute($query);
				if ($dbconn->ErrorNo() != 0){
					$this->error = "Error al traer la consulta de los cierres";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
	// 	if($resulta->EOF)
	// 	{
	// 		$this->BusquedaCajasHoy('show');
	// 		return true;
	// 	}
		while (!$resulta->EOF)
		{
			$var[$j]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$j++;
		}
	}
	return $var;
}

function TraerUsuariosMovConceptos()
{
		list($dbconn) = GetDBconn();
		 $query="SELECT DISTINCT a.caja_id
						FROM cajas a, fac_facturas_contado b
						WHERE a.cuenta_tipo_id='03' AND a.caja_id=b.caja_id
						AND b.estado IN ('0')
						AND (b.cierre_caja_id ISNULL OR (
										b.cierre_caja_id IS NOT NULL
										AND b.cierre_caja_id NOT IN(
														SELECT b.cierre_caja_id 
														FROM cierre_de_caja a,cierre_de_caja_detalle b 
														WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)));";
		$result=$dbconn->Execute($query);
		$i=0;
		while (!$result->EOF)
		{
			$var1[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}
	$j=0;
	for($i=0;$i<sizeof($var1);$i++)
	{
/*	echo	$query="SELECT MAX(a.fecha_registro) as fecha, a.caja_id,
									a.usuario_id
								FROM fac_facturas_contado a, cajas b
								WHERE	b.cuenta_tipo_id='03'
								AND a.cierre_caja_id isnull
								OR (a.cierre_caja_id is not null 
										AND a.cierre_caja_id NOT IN(
														SELECT b.cierre_caja_id
														FROM cierre_de_caja a,cierre_de_caja_detalle b
														WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
										AND a.caja_id=b.caja_id;"; exit;*/
		//colocarle el filtro de la fecha de hoy
		$query="SELECT a.fecha_registro as fecha, a.caja_id, a.usuario_id 
		FROM fac_facturas_contado a, cajas b 
		WHERE (a.cierre_caja_id isnull 
					OR (a.cierre_caja_id is not null 
							AND a.cierre_caja_id NOT IN(
														SELECT b.cierre_caja_id 
														FROM cierre_de_caja a,cierre_de_caja_detalle b 
														WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))
					) 
		AND a.estado IN ('0')
		AND a.caja_id=b.caja_id AND a.caja_id=".$var1[$i][caja_id]."
		AND a.fecha_registro=(SELECT MAX(a.fecha_registro) 
													FROM fac_facturas_contado a, cajas b 
													WHERE (a.cierre_caja_id isnull 
																OR (a.cierre_caja_id is not null 
																		AND a.cierre_caja_id NOT IN(
																											SELECT b.cierre_caja_id 
																											FROM cierre_de_caja a,cierre_de_caja_detalle b 
																											WHERE a.cierre_de_caja_id=b.cierre_de_caja_id))) 
													AND a.caja_id=b.caja_id AND a.caja_id=".$var1[$i][caja_id]."
													AND a.estado IN ('0')
													) 
		GROUP BY a.caja_id,a.usuario_id,a.fecha_registro;";
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}
		while (!$resulta->EOF)
		{
			$var[$j]=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
			$j++;
		}
	}

//		$var=$resulta->GetRowAssoc($ToUpper = false);
	return $var;
}

function TraerDetalleMovConceptos($fecha)
{
		list($dbconn) = GetDBconn();
		$query="SELECT DISTINCT a.fecha_registro as fecha,a.usuario_id, 
								a.caja_id, b.descripcion as des, c.nombre
								FROM fac_facturas_contado a, cajas b, system_usuarios c
								WHERE a.fecha_registro='$fecha'
									AND a.caja_id=b.caja_id
									AND a.usuario_id=c.usuario_id
									AND a.estado IN ('0');";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}
//TOTALES CAJAS
function TraerTotalesMov($fecha,$user,$caja)
{
/*
SELECT max(fecha_registro), a.usuario_id, a.caja_id,d.nombre,b.descripcion,
				d.descripcion as des
FROM recibos_caja a,cajas b,system_usuarios d,
			cajas_usuarios c
WHERE a.caja_id=b.caja_id
	AND ((a.cierre_caja_id isnull and a.sw_facturado isnull)
				OR (a.cierre_caja_id is not null
				AND a.cierre_caja_id NOT IN
													(SELECT b.cierre_caja_id
													FROM cierre_de_caja a,cierre_de_caja_detalle b
													WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
	AND a.caja_id=c.caja_id
	AND a.empresa_id='01'
	AND a.centro_utilidad='01'
	AND c.usuario_id=d.usuario_id
	AND c.usuario_id=a.usuario_id
	GROUP BY a.usuario_id,a.caja_id,d.nombre,b.descripcion,
				d.descripcion
*/
		list($dbconn) = GetDBconn();
		//AND a.fecha_registro='$fecha'
		//AND a.usuario_id=$user
		$query="SELECT  sum(a.total_efectivo) as efectivo, sum(a.total_cheques) as cheques,
									sum(a.total_tarjetas) as tarjetas,sum(a.total_bonos) as bonos,
									(sum(a.total_efectivo)+sum(a.total_cheques)+sum(a.total_tarjetas)+sum(a.total_bonos))
									as subtotal, b.descripcion as caja
												FROM recibos_caja a,cajas b,system_usuarios d ,
												cajas_usuarios c
												WHERE	a.caja_id=b.caja_id
												AND a.caja_id=$caja
												AND ((a.cierre_caja_id isnull AND a.sw_facturado isnull)
															OR (a.cierre_caja_id is not null
															AND a.cierre_caja_id NOT IN
															(SELECT b.cierre_caja_id
															FROM cierre_de_caja a,cierre_de_caja_detalle b
															WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
												AND a.caja_id=c.caja_id
												AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
												AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
												AND a.estado IN ('0')
												AND c.usuario_id=d.usuario_id
												AND c.usuario_id=a.usuario_id
												AND (b.cuenta_tipo_id='01' OR b.cuenta_tipo_id='06')
								GROUP BY b.descripcion;";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}

//FIN TOTALES CAJAS

//TOTALES CAJAS FACT
function TraerTotalesMovFact($user,$caja)
{
/*
SELECT max(fecha_registro), a.usuario_id, a.caja_id,d.nombre,b.descripcion,
				d.descripcion as des
FROM recibos_caja a,cajas b,system_usuarios d,
			cajas_usuarios c
WHERE a.caja_id=b.caja_id
	AND ((a.cierre_caja_id isnull and a.sw_facturado isnull)
				OR (a.cierre_caja_id is not null
				AND a.cierre_caja_id NOT IN
													(SELECT b.cierre_caja_id
													FROM cierre_de_caja a,cierre_de_caja_detalle b
													WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
	AND a.caja_id=c.caja_id
	AND a.empresa_id='01'
	AND a.centro_utilidad='01'
	AND c.usuario_id=d.usuario_id
	AND c.usuario_id=a.usuario_id
	GROUP BY a.usuario_id,a.caja_id,d.nombre,b.descripcion,
				d.descripcion
*/
		list($dbconn) = GetDBconn();
		//										AND a.usuario_id=$user
		 $query="SELECT  sum(a.total_efectivo) as efectivo, sum(a.total_cheques) as cheques,
									sum(a.total_tarjetas) as tarjetas,sum(a.total_bonos) as bonos,
									(sum(a.total_efectivo)+sum(a.total_cheques)+sum(a.total_tarjetas)+sum(a.total_bonos))
									as subtotal, b.descripcion as caja
												FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
												userpermisos_cajas_rapidas c
												WHERE	a.caja_id=b.caja_id
												AND a.caja_id=$caja
												AND (a.cierre_caja_id isnull
																				OR (a.cierre_caja_id is not null
																				AND a.cierre_caja_id NOT IN
																				(SELECT b.cierre_caja_id
																				FROM cierre_de_caja a,cierre_de_caja_detalle b
																				WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
												AND a.caja_id=c.caja_id
												AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
												AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
												AND c.usuario_id=d.usuario_id
												AND c.usuario_id=a.usuario_id
												AND a.estado IN ('0')
								GROUP BY b.descripcion;";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}
function TraerTotalesMovConceptos($user,$caja)
{
		list($dbconn) = GetDBconn();
		 $query="SELECT SUM(a.total_efectivo) as efectivo, SUM(a.total_cheques) as cheques,
									SUM(a.total_tarjetas) as tarjetas, SUM(a.total_bonos) as bonos,
									(SUM(a.total_efectivo)+SUM(a.total_cheques)+SUM(a.total_tarjetas)+SUM(a.total_bonos))
									as subtotal, b.descripcion as caja
												FROM fac_facturas_contado a,cajas b,system_usuarios d ,
												cajas_usuarios c
												WHERE	a.caja_id=b.caja_id
												AND a.usuario_id=$user
												AND a.caja_id=$caja
												AND (a.cierre_caja_id isnull
																				OR (a.cierre_caja_id is not null
																				AND a.cierre_caja_id NOT IN
																				(SELECT b.cierre_caja_id
																				FROM cierre_de_caja a,cierre_de_caja_detalle b
																				WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)))
												AND a.caja_id=c.caja_id
												AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
												AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
												AND c.usuario_id=d.usuario_id
												AND c.usuario_id=a.usuario_id
												AND a.estado IN ('0')
												GROUP BY b.descripcion;";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}

//FIN TOTALES CAJAS FACT

//DESCRIPCION DE LAS CAJAS
	function TraerDescripcion($caja)
	{
		list($dbconn) = GetDBconn();
		 $query="SELECT descripcion
								FROM cajas
								WHERE caja_id=".$caja.";";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

		$var=$resulta->GetRowAssoc($ToUpper = false);
	return $var;
		
	}
//
//DESCRIPCION DE LAS CAJAS
	function TraerDescripcionFact($caja)
	{
		list($dbconn) = GetDBconn();
		 $query="SELECT descripcion
								FROM cajas_rapidas
								WHERE caja_id=".$caja.";";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

		$var=$resulta->GetRowAssoc($ToUpper = false);
	return $var;
		
	}
//
//FIN DESCRIPCION DE LAS CAJAS
function TraerDescripcionConceptos($caja)
	{
		list($dbconn) = GetDBconn();
		 $query="SELECT descripcion
								FROM cajas
								WHERE caja_id=".$caja.";";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

		$var=$resulta->GetRowAssoc($ToUpper = false);
	return $var;
		
	}
	function TraerUltimoUsuario($usuario)
	{
		list($dbconn) = GetDBconn();
		$query="SELECT usuario_id, nombre 
						FROM system_usuarios
						WHERE usuario_id=$usuario;"; 
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}

		$var=$resulta->GetRowAssoc($ToUpper = false);
	return $var;
		
	}
//
//****************************************************
//****************************************************
//BUSAQUEDA DE CAJAS QUE NO HAN REALIZADO CIERRES Y/O 
//TIENEN MOVIMIENTO AUN - FACTURADORAS
function TraerCajasSinCerrarFacturadoras()
{
		$fecha=date('Y-m-d');
//	AND DATE(a.fecha_registro)='$fecha'
		list($dbconn) = GetDBconn();
//AND (b.cierre_caja_id=a.cierre_caja_id OR b.cierre_caja_id ISNULL)
		 $query="SELECT distinct a.usuario_id,a.fecha_registro, 
											a.total_efectivo, a.total_cheques, 
											a.total_tarjetas, c.descripcion, d.nombre, 
											d.descripcion AS des, a.cierre_caja_id,
											c.caja_id

								FROM recibos_caja_cierre a, fac_facturas_contado b, 
											cajas_rapidas c, system_usuarios d 
								WHERE a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND a.cierre_caja_id NOT IN
												(SELECT b.cierre_caja_id
													FROM cierre_de_caja a,cierre_de_caja_detalle b 
													WHERE a.cierre_de_caja_id=b.cierre_de_caja_id)
								AND b.cierre_caja_id=a.cierre_caja_id
								AND b.caja_id=c.caja_id 
								AND a.usuario_id=d.usuario_id
								AND a.sw_facturado='1'
								AND b.estado IN ('0')
								ORDER BY c.descripcion;";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}
//****************************************************
//****************************************************
//****************************************************
//FIN BUSAQUEDA DE CAJAS QUE NO HAN REALIZADO CUADRES Y/O 
//TIENEN MOVIMIENTO AUN.

function TraerCajasSinCuadrarRecibos($caja)
{
		$fecha=date('Y-m-d');
		list($dbconn) = GetDBconn();
								//AND (b.cierre_caja_id=a.cierre_caja_id OR b.cierre_caja_id ISNULL)
		 $query="SELECT distinct a.usuario_id,a.fecha_registro, 
											a.total_efectivo, a.total_cheques, 
											a.total_tarjetas, c.descripcion, d.nombre, 
											d.descripcion AS des, c.caja_id
								FROM recibos_caja a, 
											cajas c, system_usuarios d 
								WHERE a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND a.cierre_caja_id ISNULL
								AND a.caja_id=c.caja_id 
								AND a.estado IN ('0')
								AND a.fecha_registro=
										(SELECT MAX(a.fecha_registro) 
										FROM recibos_caja a, cajas c, 
													system_usuarios d 
										WHERE a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
										AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
										AND a.cierre_caja_id ISNULL 
										AND a.caja_id=c.caja_id 
										AND a.caja_id=$caja
										AND a.usuario_id=d.usuario_id
										AND a.estado IN ('0'))
								AND a.usuario_id=d.usuario_id
								ORDER BY c.descripcion;";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}
function TraerCajasSinCuadrarRecibosFacturadoras($caja)
{
		$fecha=date('Y-m-d');
		list($dbconn) = GetDBconn();
								//AND (b.cierre_caja_id=a.cierre_caja_id OR b.cierre_caja_id ISNULL)
		 $query="SELECT distinct a.usuario_id,a.fecha_registro, 
											a.total_efectivo, a.total_cheques, 
											a.total_tarjetas, c.descripcion, d.nombre, 
											d.descripcion AS des, c.caja_id
								FROM fac_facturas_contado a, 
											cajas_rapidas c, system_usuarios d 
								WHERE a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND a.cierre_caja_id ISNULL
								AND a.caja_id=c.caja_id
								AND a.estado IN ('0') 
								AND a.fecha_registro=
										(SELECT MAX(a.fecha_registro) 
										FROM fac_facturas_contado a, cajas_rapidas c, 
													system_usuarios d 
										WHERE a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
										AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
										AND a.cierre_caja_id ISNULL 
										AND a.caja_id=c.caja_id 
										AND a.caja_id=$caja
										AND a.usuario_id=d.usuario_id
										AND a.estado IN ('0'))
								AND a.usuario_id=d.usuario_id
								ORDER BY c.descripcion;";
		//colocarle el filtro de la fecha de hoy
			$resulta=$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al traer la consulta de los cierres";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
// 	if($resulta->EOF)
// 	{
// 		$this->BusquedaCajasHoy('show');
// 		return true;
// 	}

	$i=0;
	while (!$resulta->EOF)
	{
		$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
		$resulta->MoveNext();
		$i++;
	}
	return $var;
}
//****************************************************
//****************************************************
//****************************************************
//FIN BUSAQUEDA DE CAJAS QUE NO HAN REALIZADO CUADRES Y/O 
//TIENEN MOVIMIENTO AUN.

//USUARIOS SIN REALIZAR CUADRES  - HOSPITALARIAS
	function BusquedaUsuariosDesCuadrados()
	{ 
			list($dbconn) = GetDBconn();
			$fecha=date('Y-m-d');
//			AND DATE(a.fecha_registro)='".$fecha."'
			$query="SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
			d.descripcion as des
			FROM recibos_caja a,cajas b,system_usuarios d ,
			cajas_usuarios c
			WHERE	a.caja_id=b.caja_id
			AND a.cierre_caja_id isnull
			AND a.caja_id=c.caja_id
			AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
			AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
			AND c.usuario_id=d.usuario_id
			AND c.usuario_id=a.usuario_id
			AND b.cuenta_tipo_id='01'
			AND a.estado IN ('0')
			ORDER BY b.descripcion;"; 
			//colocarle el filtro de la fecha de hoy
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
//FIN USUARIOS SIN REALIZAR CUADRES - HOSPITALARIAS
///************************************************
//USUARIOS SIN REALIZAR CUADRES- FACTURADORAS
	function BusquedaUsuariosDesCuadradosFacturadoras()
	{ 
			list($dbconn) = GetDBconn();
//			AND DATE(a.fecha_registro)='".$fecha."'
			$fecha=date('Y-m-d');
			$query="SELECT  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
			d.descripcion as des
			FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
			cajas_usuarios c
			WHERE	a.caja_id=b.caja_id
			AND a.cierre_caja_id isnull
			AND a.caja_id=c.caja_id
			AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
			AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
			AND c.usuario_id=d.usuario_id
			AND c.usuario_id=a.usuario_id
			AND a.estado IN ('0')
			ORDER BY b.descripcion;"; 
			//colocarle el filtro de la fecha de hoy
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
//FIN USUARIOS SIN REALIZAR CUADRES- factutadoras
	/*
	* funcion que realiza las busqueda de los cierres anteriores.
	*/
	function ArchivadorBusqueda()
	{

/*			if($_REQUEST['criterio']=='3') //caja hospitalarias..
			{

							list($dbconn) = GetDBconn();
							$query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,
												b.descripcion,d.descripcion as des, b.cuenta_tipo_id
											FROM cierre_de_caja a,cajas b,system_usuarios d ,
														cajas_usuarios c
											WHERE a.caja_id=b.caja_id
												AND a.caja_id=c.caja_id
												AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
												AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
												AND c.usuario_id=d.usuario_id
												AND c.usuario_id=a.usuario_id
												AND b.cuenta_tipo_id='03'
												AND a.sw_confirmado='1'
												ORDER BY b.descripcion;";
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BuscarArchivo('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}
				$this->BuscarArchivo($var,3);
				return true;
		}
		else*/
			if($_REQUEST['criterio']=='2') //caja hospitalarias..
			{

							list($dbconn) = GetDBconn();
// 							$query="select  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,
// 							d.descripcion as des
// 							FROM recibos_caja a,cajas b,system_usuarios d ,
// 							cajas_usuarios c
// 							where
// 							a.caja_id=b.caja_id
// 							and a.caja_id=c.caja_id
// 							and a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
// 							and a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
// 							and c.usuario_id=d.usuario_id
// 							and c.usuario_id=a.usuario_id
// 							and b.cuenta_tipo_id='01'
// 							order by b.descripcion ";

							$query="SELECT DISTINCT a.usuario_id,a.caja_id,d.nombre,
												b.descripcion,d.descripcion as des, b.cuenta_tipo_id
											FROM cierre_de_caja a,cajas b,system_usuarios d ,
														cajas_usuarios c
											WHERE a.caja_id=b.caja_id
												AND a.caja_id=c.caja_id
												AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
												AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
												AND c.usuario_id=d.usuario_id
												AND c.usuario_id=a.usuario_id
												AND (b.cuenta_tipo_id='01' OR b.cuenta_tipo_id='06')
												AND a.sw_confirmado='1'
												ORDER BY b.descripcion;";
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BuscarArchivo('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}
				$this->BuscarArchivo($var,2);
				return true;
		}


			//elseif es por que son cajas facturadoras.
			elseif($_REQUEST['criterio']=='1')//cajas facturadoras..
			{
					$dpto=$_REQUEST['departamento'];
					if($_REQUEST['departamento']=='/a/')
					{$search_dpto='';}else{$search_dpto="AND b.departamento='$dpto'";}

      				list($dbconn) = GetDBconn();

/*						echo	$query="select  DISTINCT a.usuario_id,a.caja_id,d.nombre,b.descripcion,b.departamento,
							d.descripcion as des
							FROM fac_facturas_contado a,cajas_rapidas b,system_usuarios d ,
							userpermisos_cajas_rapidas c
							where
							a.caja_id=b.caja_id
							and a.caja_id=c.caja_id
							and a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							and a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							and c.usuario_id=d.usuario_id
							and c.usuario_id=a.usuario_id $search_dpto
							order by b.descripcion ";*/
							 $query="SELECT  DISTINCT e.usuario_id,e.caja_id,d.nombre,
										b.descripcion,b.departamento, d.descripcion as des, 
										b.cuenta_tipo_id
							FROM cajas_rapidas b,system_usuarios d ,
							userpermisos_cajas_rapidas c, cierre_de_caja e, 
							cierre_de_caja_detalle f 
							WHERE e.cierre_de_caja_id=f.cierre_de_caja_id 
							
							AND e.caja_id=b.caja_id
							AND e.caja_id=c.caja_id
							AND e.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							AND e.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							AND c.usuario_id=d.usuario_id
							AND e.sw_confirmado='1'
							AND c.usuario_id=e.usuario_id $search_dpto
							ORDER BY b.descripcion;";
							//colocarle el filtro de la fecha de hoy
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BuscarArchivo('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}

				$this->BuscarArchivo($var,1);
				return true;
		}
		return true;
	}


	/*
	*	 en esta funcion saco los totales de los documentos de las cajas facturadoras.
	*/
	function SacarTotalDocumentos($no,$caja)
	{
			list($dbconn) = GetDBconn();
/*		echo	 $query="SELECT COUNT(*) as no,SUM(a.total_abono) as total_abono,
							SUM(a.total_cheques) as total_cheques,
							SUM(a.total_tarjetas) as total_tarjetas,
							SUM(a.total_efectivo) as total_efectivo,
							SUM(a.total_bonos) as total_bonos
							FROM fac_facturas_contado a,recibos_caja_cierre b,fac_facturas v
							WHERE a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
							AND a.usuario_id=b.usuario_id
							AND a.cierre_caja_id=b.cierre_caja_id
							AND a.prefijo=v.prefijo
							AND a.factura_fiscal=v.factura_fiscal
							AND a.caja_id=$caja
							AND b.sw_facturado='1'
							AND v.estado='0'
							AND b.cierre_caja_id=$no";   exit;*/
			 $query="SELECT c.cierre_caja_id
							FROM cierre_de_caja_detalle c
							WHERE  c.cierre_de_caja_id=$no;";
			$result=$dbconn->Execute($query);
								$i=0;
			while (!$result->EOF)
			{
				$var[$i]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
				$i++;
			}
			for($i=0;$i<sizeof($var);$i++)
			{
			 $query="SELECT COUNT(*) as no,SUM(a.total_abono) as total_abono,
							SUM(a.total_cheques) as total_cheques,
							SUM(a.total_tarjetas) as total_tarjetas,
							SUM(a.total_efectivo) as total_efectivo,
							SUM(a.total_bonos) as total_bonos
							FROM fac_facturas_contado a,recibos_caja_cierre b,fac_facturas v
							WHERE a.usuario_id=b.usuario_id
							AND a.cierre_caja_id=b.cierre_caja_id
							AND a.prefijo=v.prefijo
							AND a.factura_fiscal=v.factura_fiscal
							AND a.caja_id=$caja
							AND b.sw_facturado='1'
							AND v.estado='0'
							AND a.estado IN ('0')
							AND b.cierre_caja_id=".$var[$i][cierre_caja_id].""; 
			$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
			}
			return 	$var=$resulta->GetRowAssoc($ToUpper = false);
	}



	/*
	*	 en esta funcion saco los totales de los documentos de las cajas hospitalizacion.
	*/
	function SacarTotalDocumentosHosp($no,$caja)
	{
			list($dbconn) = GetDBconn();
		if($_SESSION['CONTROL_CIERRE']['DATOS']['CUENTATIPO']=='03')
		{
			$query="SELECT COUNT(*) as no,SUM(a.total_abono) as total_abono,
							SUM(a.total_cheques) as total_cheques,
							SUM(a.total_tarjetas) as total_tarjetas,
							SUM(a.total_efectivo) as total_efectivo,
							SUM(a.total_bonos) as total_bonos
							FROM fac_facturas_contado a,recibos_caja_cierre b
							WHERE a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
							AND a.usuario_id=b.usuario_id
							AND a.cierre_caja_id=b.cierre_caja_id
							AND a.caja_id=$caja
							AND b.sw_facturado='0'
							AND a.estado IN ('0')
							AND b.cierre_caja_id=$no";
		}
		else
		{
			$query="SELECT COUNT(*) as no,SUM(a.total_abono) as total_abono,
							SUM(a.total_cheques) as total_cheques,
							SUM(a.total_tarjetas) as total_tarjetas,
							SUM(a.total_efectivo) as total_efectivo,
							SUM(a.total_bonos) as total_bonos
							FROM recibos_caja a,recibos_caja_cierre b
							WHERE a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
							AND a.usuario_id=b.usuario_id
							AND a.cierre_caja_id=b.cierre_caja_id
							AND a.caja_id=$caja
							AND a.estado IN ('0')
							AND b.sw_facturado='0'
							AND b.cierre_caja_id=$no";
		}
			$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;
			return 	$var=$resulta->GetRowAssoc($ToUpper = false);
	}

	/*
	*	 en esta funcion saco los totales de las devoluciones de las cajas hospitalizacion.
	*/
	function SacarTotalDevHosp($no,$caja)
	{
//							AND b.sw_facturado='0'
			list($dbconn) = GetDBconn();
			$query="SELECT distinct b.total_devolucion
							FROM rc_devoluciones a, rc_devoluciones_cierre b
							WHERE a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
							AND a.usuario_id=b.usuario_id
							AND a.cierre_caja_id=b.cierre_caja_id
							AND a.caja_id=$caja
							AND b.cierre_caja_id=$no";
			$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;
			return 	$var=$resulta->GetRowAssoc($ToUpper = false);
	}


	/*
	* esta funcion va a mostrar los cierres anteriores de cajas facturadoras.
	*/
	function IRCierresAnteriores()
	{
			$id=$_SESSION['CONTROL_CIERRE']['DATOS']['ID'];
			$caja=$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA'];
			$sw=$_SESSION['CONTROL_CIERRE']['DATOS']['SW'];
			$dpto=$_SESSION['CONTROL_CIERRE']['DATOS']['DPTO'];
			$cierre=$_REQUEST['cierre'];
			$fecha=$_REQUEST['fecha'];
			$cuenta=$_REQUEST['cuenta'];
			$caja_des=$_REQUEST['descripcion'];
			$_SESSION['CONTROL_CIERRE']['DATOS']['DESC']=$_REQUEST['descripcion'];
			//$this->CierresAnteriores($id,$caja,$cierre,$fecha,$caja_des,$sw,$dpto);
			//$recibo_pdf='y';
			$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw,'',$cuenta);
			return true;
	}


	/*
	* esta funcion va a mostrar los cierres anteriores de cajas hospitalarias.
	*/
	function IRCierresAnterioresHosp()
	{
			//echo $_SESSION['CONTROL_CIERRE']['DATOS']['CUENTATIPO']; exit;
			//print_r($_SESSION['CONTROL_CIERRE']['DATOS']);
			//exit;

			$id=$_SESSION['CONTROL_CIERRE']['DATOS']['ID'];
			$caja=$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA'];
			$sw=$_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT'];
			$dpto=$_SESSION['CONTROL_CIERRE']['DATOS']['DPTO'];
			$cierre=$_REQUEST['cierre'];
			$fecha=$_REQUEST['fecha'];
			$caja_des=$_REQUEST['descripcion'];
			$_SESSION['CONTROL_CIERRE']['DATOS']['DESC']=$_REQUEST['descripcion'];
			//$recibo_pdf='y';
/*echo "id".$id;
echo "caja".$caja;
echo "sw".$sw;
echo "dpto".$dpto;
echo "cierre".$cierre;
echo "fecha".$fecha;
echo "caja_des".$caja_des;
exit;*/
			$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw);
			return true;
	}



	/*
	* funcion que realiza las busqueda de las fechas de los cierres anteriores.
	*/
	function BusquedaFechas()
	{

			if($_REQUEST['criterio']=='2') //caja hospitalarias..
			{
					if(empty($_REQUEST['busqueda']) and empty($_REQUEST['fech']))
						{
								$this->BuscadorCierresAnteriores();
								return true;
						}

						if($_REQUEST['fech'])
						{$search_dpto='';}
						else
						{	$fechar=explode("-",$_REQUEST['busqueda']);
							$fechar=$fechar[2]."".$fechar[1]."".$fechar[0];
							$search_dpto="AND date(a.fecha_registro)='$fechar'";
						}

							list($dbconn) = GetDBconn();

/*					if($_SESSION['CONTROL_CIERRE']['DATOS']['CUENTATIPO']=='03')	
					{
					 $query="SELECT  DISTINCT a.fecha_registro,a.observaciones,a.cierre_caja_id,
										a.usuario_id
							FROM recibos_caja_cierre a,fac_facturas_contado b, cajas c
							WHERE
							a.sw_facturado='0'
							AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							AND a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
							AND a.usuario_id=b.usuario_id
							AND a.empresa_id=b.empresa_id
							AND a.centro_utilidad=b.centro_utilidad
							AND b.caja_id =".$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']."
							AND b.caja_id =c.caja_id
							AND c.cuenta_tipo_id='".$_SESSION['CONTROL_CIERRE']['DATOS']['CUENTATIPO']."'
							AND b.cierre_caja_id=a.cierre_caja_id
							$search_dpto
							ORDER BY a.fecha_registro asc;";
					}*/
					 $query="SELECT DISTINCT a.fecha_registro,
									a.observaciones_confirmacion AS observaciones,
									a.cierre_de_caja_id, a.total_efectivo, a.total_cheques,
									a.total_tarjetas, a.total_devolucion,
									a.total_bonos, a.entrega_efectivo, a.valor_confirmado
							FROM cierre_de_caja a,
									cajas c, 
									cajas_usuarios d 
							WHERE a.caja_id=d.caja_id 
							AND c.caja_id=d.caja_id 
							AND a.caja_id=d.caja_id 
							AND a.usuario_id=d.usuario_id 
							AND a.caja_id =".$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']."
							AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
							AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
							AND a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']." 
							AND a.sw_confirmado=1
							$search_dpto
							order by a.fecha_registro asc;"; 
								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BuscadorCierresAnteriores('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}

				$this->BuscadorCierresAnteriores($var,2);
				return true;
			}
			elseif($_REQUEST['criterio']=='1')//cajas facturadoras..
			{

			    if(empty($_REQUEST['busqueda']) and empty($_REQUEST['fech']))
					{
							$this->BuscadorCierresAnteriores();
							return true;
					}


					if($_REQUEST['fech'])
					{$search_dpto='';}
					else
					{
						$fechar=explode("-",$_REQUEST['busqueda']);
						$fechar=$fechar[2]."".$fechar[1]."".$fechar[0];
						$search_dpto="AND date(a.fecha_registro)='$fechar'";
					}

      				list($dbconn) = GetDBconn();

// 						 $query="SELECT  DISTINCT a.fecha_registro,a.observaciones,a.cierre_caja_id
// 
// 							FROM recibos_caja_cierre a,fac_facturas_contado b
// 
// 							WHERE
// 							a.sw_facturado='1'
// 							AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
// 							AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
// 							AND a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
// 							AND a.usuario_id=b.usuario_id
// 							AND a.empresa_id=b.empresa_id
// 							AND a.centro_utilidad=b.centro_utilidad
// 							AND b.caja_id =".$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']."
// 							AND b.cierre_caja_id=a.cierre_caja_id
// 							$search_dpto
// 							order by a.fecha_registro asc ";
/*					echo	 $query="SELECT  DISTINCT a.fecha_registro,a.observaciones_confirmacion AS observaciones, a.cierre_de_caja_id, b.cierre_caja_id
							FROM cierre_de_caja a, cierre_de_caja_detalle b
							WHERE a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']."
							AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
							AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
							AND a.caja_id =".$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']."
							AND a.cierre_de_caja_id=b.cierre_de_caja_id
							$search_dpto
							order by a.fecha_registro asc;";     exit;*/
						$query=" SELECT DISTINCT a.fecha_registro,
														a.observaciones_confirmacion AS observaciones,
														a.cierre_de_caja_id, a.total_efectivo, a.total_cheques,
														a.total_tarjetas, a.total_devolucion,
														a.total_bonos, a.entrega_efectivo, 
														a.valor_confirmado,c.cuenta_tipo_id
													FROM cierre_de_caja a,
															cajas_rapidas c, 
															userpermisos_cajas_rapidas d 
													WHERE a.caja_id=d.caja_id 
													AND c.caja_id=d.caja_id 
													AND a.caja_id=d.caja_id 
													AND a.usuario_id=d.usuario_id 
													AND a.caja_id =".$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']."
													AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."' 
													AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."' 
													AND a.usuario_id=".$_SESSION['CONTROL_CIERRE']['DATOS']['ID']." 
													AND a.sw_confirmado=1
													$search_dpto
				 									order by a.fecha_registro asc;";

								$resulta=$dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0){
									$this->error = "Error al traer la consulta de los cierres";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
								$i=0;

								if($resulta->EOF)
								{
									$this->BuscadorCierresAnteriores('show');
									return true;
								}

								while (!$resulta->EOF)
								{
									$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->MoveNext();
									$i++;
								}

				$this->BuscadorCierresAnteriores($var,1);
				return true;
		}
		return true;
	}


	/*
	* funcion que guarda la justificacion para poder visualizar el pdf.
	*/
	function GuardarPdf()
	{
			$sw_tipo=$_REQUEST['sw_tipo'];//aqui va si el rollo es de 1 de contado o 2 de credito..
			$cierre=$_REQUEST['cierre'];
			$id=$_REQUEST['id'];
			$caja=$_REQUEST['caja'];
			$fecha=$_REQUEST['fecha'];
			$sw=$_REQUEST['sw_recibo'];
			$dpto=$_REQUEST['dpto'];
			$caja_des=$_REQUEST['descripcion'];
			$rollo=$_REQUEST['rollo'];
			$cuenta=$_REQUEST['cuenta'];
			$imp_pdf=5; //nos garantiza que en el html nos mostrara la ventana emergente.
			$retorno=$_REQUEST['retorno'];//para guardar pdf utilizo la misma funcion,la diferencia
				//es esta variable ya que si esta en 1 va a cierres caja  anteriores, sino a cierres de facturas 
	

			if(empty($_REQUEST['obs']))
			{
					$go_to=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
					array('retorno'=>$retorno,'sw_tipo'=>$sw_tipo,'rollo'=>$rollo,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					$this->FormaMensaje('ES DE CARACTER OBLIGATORIO JUSTIFICAR  LA IMPRESION','ADVERTENCIA',$go_to,'Volver');
					return true;
			}

	/***Insercion de rollo*****/
			list($dbconn) = GetDBconn();
			$query="INSERT INTO control_cierre_auditoria
							(
							cierre_caja_id,
							usuario_id,
							fecha_registro,
							observacion,
							sw_facturado
							)
							VALUES
							(
								$cierre,
								".UserGetUID().",
								now(),
								'".$_REQUEST['obs']."',
								'1'
							)";
				$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Insertar en control_cierre_auditoria";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

			/**************************/
		if($_REQUEST['retorno']==1)
		{ 
			$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw,$imp_pdf,$cuenta);
		}
		else
		{
			$this->CierresAnteriores($id,$caja,$cierre,$fecha,$caja_des,$sw,$dpto,$imp_pdf);
		}	
		return true;

	}

	function InsertarConfirmacionCierre()
	{ 
			$entrega=$_REQUEST['entrega'];
			$criterio=$_REQUEST['criterio'];
			$cierre=$_REQUEST['cierre_de_caja'];
// 			echo $_SESSION['CONTROL']['ENTREGA'].'<BR>';
// 			echo $_POST['valorrecibido'].'<BR>';

			if(!empty($_POST['valorrecibido']) AND !is_numeric($_POST['valorrecibido']))
			{
					$this->frmError["valorrecibido"]=1;
					$this->frmError["MensajeError"]="EL CAMPO DEBE SER NUMERICO";
					$this->uno=1;
					$this->FrmConfirmarCierre();
					return true;                         
			}

			if($_POST['valorrecibido']<$_SESSION['CONTROL']['ENTREGA'] AND empty($_REQUEST['observa']))
			{
					$this->frmError["observa"]=1;
					$this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS";
					$this->uno=1;
					$this->FrmConfirmarCierre();
					return true;                         
				
			}
			
			list($dbconn) = GetDBconn();
			$dbconn->BeginTrans();
			$query="UPDATE cierre_de_caja SET sw_confirmado='1', fecha_confirmacion=now(), 
										observaciones_confirmacion='".$_REQUEST['observa']."',
										usuario_recibio=".UserGetUID().",
										valor_confirmado=".$_POST['valorrecibido']."
								WHERE cierre_de_caja_id=".$cierre.";";
			$dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0) {
					$dbconn->RollBackTrans();
					$this->error = "Error al Insertar en cierre_de_caja";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
			$dbconn->CommitTrans();
			$this->frmError["MensajeError"]="CIERRE CONFIRMADO.";
			$this->uno=1;
			$this->BusquedaCajasHoy('',$criterio);
			return true;
	}



    /*funcion que genera el pdf para despues ser mostrado como reporte*/
    function GenerarListadoCierreCaja($id,$caja,$dpto,$cierre,$cuenta)
    {
      IncludeLib("reportes/control_cierre"); //car
      GenerarControlCierreCaja($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE'],$id,$caja,$dpto,$cierre,$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE_DEV'],$cuenta);
      return true;
    }




	//-------------------------Rollo fiscal---------------------------------------------------------
	/*
	*  Esta funcion genera una copia de cada factura del dia, que se ha pagado en la caja
	*  en el momento que se realiza un cierre,obviamente se imprimiran tantas copias de facturas según
	*  como aparezcan en el reporte de cierre de la caja.
	*/
	function GenerarRolloFiscal()
	{
			//$action=$_REQUEST['go_to'];//aqui va la direccion a donde debe volver..
			$sw_tipo=$_REQUEST['sw_tipo'];//aqui va si el rollo es de 1 de contado o 2 de credito..
			$cierre=$_REQUEST['cierre'];
			$id=$_REQUEST['id'];
			$caja=$_REQUEST['caja'];
			$fecha=$_REQUEST['fecha'];
			$sw=$_REQUEST['sw_recibo'];
			$dpto=$_REQUEST['dpto'];
			$caja_des=$_REQUEST['descripcion'];
			$rollo=$_REQUEST['rollo'];

//print_r($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']); exit;

			if(empty($_REQUEST['obs']))
			{
					$go_to=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
					array('sw_tipo'=>$sw_tipo,'rollo'=>$rollo,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					$this->FormaMensaje('ES DE CARACTER OBLIGATORIO JUSTIFICAR  LA IMPRESION','ADVERTENCIA',$go_to,'Volver');
					return true;
			}
				
			
			/***Insercion de rollo*****/
			list($dbconn) = GetDBconn();
			$query="INSERT INTO control_cierre_auditoria
							(
							cierre_caja_id,
							usuario_id,
							fecha_registro,
							observacion,
							sw_facturado
							)
							VALUES
							(
								$cierre,
								".UserGetUID().",
								now(),
								'".$_REQUEST['obs']."',
								'1'
							)";
				$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Insertar en control_cierre_auditoria";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

			/**************************/


			if($_REQUEST['sw_tipo']==1)
			{$sw_pos=0;}else{$sw_pos=1;}
//print_r($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']); exit;
			$a=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE'];
			$x=0;

			if(is_array($a))
			{
							for($i=0;$i<sizeof($a);$i++)
							{
													$cuenta=$a[$i][numerodecuenta];
													$var='';
													$var[0]=$this->EncabezadoFactura($cuenta);

													list($dbconn) = GetDBconn();
													if (!IncludeFile("classes/reports/reports.class.php")) {
															$this->error = "No se pudo inicializar la Clase de Reportes";
															$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
															return false;
													}

												$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
																		a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
																		b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
																		e.texto1, e.texto2, e.mensaje, f.*
																		from cuentas_detalle as a, tarifarios_detalle as b,
																		fac_facturas_cuentas as c, documentos as e, fac_facturas as f
																		where a.numerodecuenta=$cuenta and a.cargo=b.cargo
																		and a.tarifario_id=b.tarifario_id
																		and a.cargo!='DESCUENTO'
																		and c.numerodecuenta=a.numerodecuenta
																		and c.sw_tipo='$sw_pos'
																		and a.empresa_id=e.empresa_id
																		and c.prefijo=e.prefijo
																		and c.prefijo=f.prefijo
																		and c.factura_fiscal=f.factura_fiscal
																		order by b.grupo_tipo_cargo desc ";
															$result = $dbconn->Execute($query);
																if ($dbconn->ErrorNo() != 0) {
																				$this->error = "Error al Cargar el Modulo";
																				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
																				return false;
																}
																while(!$result->EOF)
																{
																				$var[]=$result->GetRowAssoc($ToUpper = false);
																				$result->MoveNext();
																}
																$result->MoveFirst();
																if(!$result->EOF)
																{
																		$classReport = new reports;
																		$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
																		$reporte=$classReport->PrintReport('pos','app','Control_Cierre','factura',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
																		if(!$reporte){
																				$this->error = $classReport->GetError();
																				$this->mensajeDeError = $classReport->MensajeDeError();
																				unset($classReport);
																				return false;
																		}
																		$resultado=$classReport->GetExecResultado();
																		unset($classReport);
																		$x++;
																}

																if(!empty($resultado[codigo])){
																		"El PrintReport retorno : " . $resultado[codigo] . "<br>";
																}
						}//fin for

						if($x==0)
						{
								$go_to=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',
								array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
								//$this->CierresAnteriores($id,$caja,$cierre,$fecha,$caja_des,$sw,$dpto);
								$this->FormaMensaje('NO HAY ROLLO FISCAL GENERADO PARA ESTE TIPO DE FACTURAS','CONFIRMACION',$go_to,'Volver','cierre');
								return true;
						}
		$go_to=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',
		array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
		$this->FormaMensaje('ROLLO FISCAL GENERADO SATISFACTORIAMENTE','CONFIRMACION',$go_to,'Volver','cierre');
    return true;
		}
		else
		{
			$go_to=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',
			array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre));
			$this->FormaMensaje('EL ROLLO FISCAL NO SE PUDO IMPRIMIR,NOTIFICAR AL ADMIN','CONFIRMACION',$go_to,'Volver','cierre');
    	return true;

		}
	}
	/******************************Generar Rollo Fiscal****************************************************/









	/****************** Encabezado Reportes de Facturas  *****************************/

	/**
  *
  */
  function EncabezadoFactura($cuenta)
  {
        list($dbconn) = GetDBconn();
        $query = "select (a.abono_efectivo + a.abono_cheque + a.abono_tarjetas + a.abono_chequespf + a.abono_bonos) as abonos,
                  a.numerodecuenta, a.ingreso, a.plan_id, a.empresa_id, b.plan_descripcion,
                  c.nombre_tercero, c.tipo_id_tercero, c.tercero_id, d.tipo_id_paciente, d.paciente_id,
                  e.primer_nombre||' '||e.segundo_nombre||' '||e.primer_apellido||' '||e.segundo_apellido as nombre,
                  e.residencia_telefono, e.residencia_direccion, d.departamento_actual as dpto, h.descripcion,
                  i.razon_social, i.direccion, i.telefonos, i.tipo_id_tercero as tipoid,
                  i.id, j.departamento, k.municipio, d.fecha_registro, a.rango, Z.tipo_afiliado_nombre,
                  b.nombre_cuota_moderadora, b.nombre_copago, x.nombre as usuario, x.usuario_id,
									a.valor_cuota_moderadora, a.valor_cuota_paciente, a.valor_nocubierto,
									a.valor_total_paciente, a.valor_total_empresa, a.valor_descuento_paciente,
									a.valor_descuento_empresa, a.valor_cubierto
                  from cuentas as a, planes as b, terceros as c, pacientes as e, departamentos as  h,
                  empresas as i, tipo_dptos as j, tipo_mpios as k, ingresos as d,
                  system_usuarios as x, tipos_afiliado as Z
                  where a.numerodecuenta=$cuenta and a.plan_id=b.plan_id and b.tercero_id=c.tercero_id
                  and b.tipo_tercero_id=c.tipo_id_tercero
                  and x.usuario_id=".UserGetUID()."
                  and a.tipo_afiliado_id=Z.tipo_afiliado_id
                  and d.ingreso=a.ingreso and d.tipo_id_paciente=e.tipo_id_paciente
                  and d.paciente_id=e.paciente_id
                  and a.empresa_id=i.empresa_id and i.tipo_pais_id=j.tipo_pais_id and i.tipo_dpto_id=j.tipo_dpto_id
                  and i.tipo_pais_id=k.tipo_pais_id and i.tipo_dpto_id=k.tipo_dpto_id and i.tipo_mpio_id=k.tipo_mpio_id
                  and d.departamento_actual=h.departamento";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
  }

	function TraerObservaciones($cierre)
	{
        list($dbconn) = GetDBconn();
        $query = "select observaciones, observaciones_confirmacion
                  from cierre_de_caja
                  where cierre_de_caja_id=$cierre ";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $var=$resulta->GetRowAssoc($ToUpper = false);
        $resulta->Close();
        return $var;
	}

	//esta funcion saca los reportes de recibos de caja en pos hospitalizacion
	function Reportes_Pdf_Hosp()
	{  /*echo $_REQUEST['caja']; exit;*/
		//print_r($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN']); exit;
		$cierre=$_REQUEST['cierre'];
		$id=$_REQUEST['id'];
		$caja=$_REQUEST['caja'];
		$fecha=$_REQUEST['fecha'];
		$sw=$_REQUEST['sw_recibo'];
		$dpto=$_REQUEST['dpto'];
		$caja_des=$_REQUEST['descripcion'];
		$actual=$_REQUEST['actual'];
		$devolucion=$_REQUEST['devolucion'];
		$cuenta=$_REQUEST['cuenta_tipo'];
		$usuario=$_REQUEST['usuario'];
		$imp_pdf=$_REQUEST['imp_pdf'];
		$recibo=$_REQUEST['recibo'];
		$prefijo=$_REQUEST['prefijo'];
		$dev=$_REQUEST['dev'];
		$cuenta=$_REQUEST['cuenta'];

			if($sw==1  AND empty($dev) AND ($cuenta=='03' OR $cuenta=='08'))
			{
				$sql="SELECT a.cierre_caja_id,a.fecha_registro AS fecha_ingcaja,
											a.usuario_id,a.prefijo,
							a.factura_fiscal as recibo_caja,a.caja_id,
							a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos,a.total_abono,
							d.nombre AS usuario, e.razon_social,e.direccion, f.descripcion AS cu,
							b.nombre_tercero,
							b.tipo_id_tercero||' '||b.tercero_id as tercero_id
					FROM fac_facturas_contado a, terceros b,
								system_usuarios d, empresas e, centros_utilidad f
					WHERE a.cierre_caja_id=$cierre
					AND a.factura_fiscal=$recibo
					AND a.prefijo='$prefijo'
					AND a.usuario_id=$usuario
					AND a.usuario_id=d.usuario_id
					AND a.empresa_id=e.empresa_id
					AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND a.empresa_id=f.empresa_id
					AND a.centro_utilidad=f.empresa_id
					AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND a.tipo_id_tercero=b.tipo_id_tercero
					AND a.tercero_id=b.tercero_id
					AND a.estado IN ('0');";  
			}
			else
			if($sw==1  AND empty($dev))
			{ 
			$sql="SELECT a.cierre_caja_id,a.fecha_registro AS fecha_ingcaja,
											a.usuario_id,a.prefijo,
							a.factura_fiscal as recibo_caja,a.caja_id,
							a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos,a.total_abono,
							d.nombre AS usuario, e.razon_social,e.direccion, f.descripcion AS cu,
							btrim(c.primer_nombre||' '||c.segundo_nombre||' ' ||
					c.primer_apellido||' '||c.segundo_apellido,'') as nombre,
					c.tipo_id_paciente||' '||c.paciente_id as id
					FROM fac_facturas_contado a, fac_facturas_cuentas b,
							pacientes c, cuentas g, ingresos h,
							system_usuarios d, empresas e, centros_utilidad f
					WHERE a.cierre_caja_id=$cierre
					AND a.factura_fiscal=$recibo
					AND a.prefijo='$prefijo'
					AND a.usuario_id=$usuario
					AND a.usuario_id=d.usuario_id
					AND a.empresa_id=e.empresa_id
					AND a.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND a.empresa_id=f.empresa_id
					AND a.centro_utilidad=f.empresa_id
					AND a.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND a.factura_fiscal=b.factura_fiscal
					AND a.prefijo=b.prefijo
					AND b.numerodecuenta=g.numerodecuenta
					AND g.ingreso=h.ingreso
					AND h.tipo_id_paciente=c.tipo_id_paciente
					AND h.paciente_id=c.paciente_id
					AND a.estado IN ('0');"; 
			}
			else
			if($sw==2 AND empty($dev) AND empty($_REQUEST['cierre2']))
			{ 
/*				echo	$sql="SELECT a.fecha_registro,a.devolucion_id,a.prefijo,a.caja_id,a.total_devolucion,
					b.razon_social,b.direccion,c.descripcion,d.plan_descripcion,e.usuario
			
					FROM recibos_caja a, cierre_de_caja b, cierre_de_caja_detalle c,
								system_usuarios e,pacientes f
					WHERE b.cierre_de_caja_id=$cierre
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=e.usuario_id
					AND a.cierre_caja_id=c.cierre_caja_id
					AND b.cierre_de_caja_id=c.cierre_de_caja_id
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'";  exit;*/
					$dat=$this->TraerObservaciones($cierre);
					$var[observaciones]=$dat[observaciones];
					$var[observaciones_confirmacion]=$dat[observaciones_confirmacion];
					$sql="SELECT a.cierre_caja_id,a.fecha_registro,a.usuario_id,a.prefijo,a.recibo_caja,a.caja_id,
							a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos,
							d.nombre , e.razon_social,e.direccion, f.descripcion AS cu,
							g.cuenta_tipo_id,
							CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono,
							g.descripcion as descripcion_caja
					FROM recibos_caja a, cierre_de_caja b, cierre_de_caja_detalle c,
							system_usuarios d, empresas e, centros_utilidad f, cajas g
					WHERE b.cierre_de_caja_id=$cierre
					AND a.cierre_caja_id=c.cierre_caja_id 
					AND a.usuario_id=d.usuario_id
					AND b.cierre_de_caja_id=c.cierre_de_caja_id 
					AND b.empresa_id=e.empresa_id
					AND b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND b.empresa_id=f.empresa_id
					AND b.centro_utilidad=f.empresa_id
					AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND a.caja_id=g.caja_id
					AND a.estado IN ('0','1');";
					$dev=$this->TraerDevUltimoCierre($caja,$id,$cierre);
					if(!empty($dev))
						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE_DEV']=$dev;
			}
			else
			if($sw==2 AND empty($dev) AND !empty($_REQUEST['cierre2']))
			{ 
/*				echo	$sql="SELECT a.fecha_registro,a.devolucion_id,a.prefijo,a.caja_id,a.total_devolucion,
					b.razon_social,b.direccion,c.descripcion,d.plan_descripcion,e.usuario
			
					FROM recibos_caja a, cierre_de_caja b, cierre_de_caja_detalle c,
								system_usuarios e,pacientes f
					WHERE b.cierre_de_caja_id=$cierre
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=e.usuario_id
					AND a.cierre_caja_id=c.cierre_caja_id
					AND b.cierre_de_caja_id=c.cierre_de_caja_id
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'";  exit;*/
/*		echo	$sql="SELECT a.cierre_caja_id,a.fecha_registro,a.usuario_id,a.prefijo,a.recibo_caja,a.caja_id,
							a.total_efectivo,a.total_cheques,a.total_tarjetas,a.total_bonos,a.total_abono,
							d.nombre , e.razon_social,e.direccion, f.descripcion AS cu
					FROM recibos_caja a, cierre_de_caja b, cierre_de_caja_detalle c,
							system_usuarios d, empresas e, centros_utilidad f
					WHERE b.cierre_de_caja_id=$cierre
					AND a.caja_id=$caja
					AND a.recibo_caja=$recibo
					AND a.prefijo='$prefijo'
					AND a.cierre_caja_id=c.cierre_caja_id 
					AND a.usuario_id=d.usuario_id
					AND b.cierre_de_caja_id=c.cierre_de_caja_id 
					AND b.empresa_id=e.empresa_id
					AND b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND b.empresa_id=f.empresa_id
					AND b.centro_utilidad=f.empresa_id
					AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."';";*/
/*					AND a.cierre_caja_id IN (SELECT h.cierre_caja_id
																	FROM cierre_de_caja g,
																			cierre_de_caja_detalle h
																	WHERE g.cierre_de_caja_id=$cierre
																		AND g.cierre_de_caja_id=h.cierre_de_caja_id
																);";exit;*/
					$data=$this->Traer_Id_Paciente_hosp($_REQUEST['recibo'],$_REQUEST['prefijo'],$caja);
					$sql="SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,
								a.fecha_registro,a.total_efectivo,
								a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,b.direccion,
								c.descripcion,d.plan_descripcion,e.usuario,
								btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
								f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
								f.tipo_id_paciente||' '||f.paciente_id as id,
								CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
					FROM recibos_caja a,empresas b,centros_utilidad c,
							planes d,system_usuarios e,pacientes f
					WHERE a.recibo_caja='".$_REQUEST['recibo']."'
					AND a.prefijo='".$_REQUEST['prefijo']."'
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND d.plan_id='".$data['plan_id']."'
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=".$usuario."
					AND tipo_id_paciente='".$data['tipo_id_paciente']."'
					AND paciente_id='".$data['paciente_id']."'
					AND a.caja_id='$caja'
					AND a.cierre_caja_id =$cierre
					AND a.estado IN ('0','1');";
					
			}
			else
			if (!empty($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN']))
			{//echo 'caja->'.$_REQUEST['caja']; exit;
				IncludeLib("reportes/cierre_caja"); //car
				$_SESSION['REPORTES']['VARIABLE']='cierre_de_caja_control';
				$_SESSION['CIERRE']['CIERRE_TOTAL']['SEQ']=$cierre;
				$_SESSION['CIERRE']['CIERRE_TOTAL']['CAJA']=$caja;
				$_SESSION['CIERRE']['CIERRE_TOTAL']['cuenta']=$cuenta;
				GenerarCierreDeCaja();
				$_SESSION['CAJA']['PARAM']='ShowReportControl';//esta variable es para que muestre el reporte
				$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw);
				return true;
			}
			else
			if (!empty($_REQUEST['file']))
			{
					$data=$this->TraerPacienteCajaGeneralDev($_REQUEST['cuenta'],$usuario,$cierre,$_REQUEST['file']);
					 $sql="SELECT a.fecha_registro,a.devolucion_id,a.prefijo,a.recibo_caja,a.caja_id,a.total_devolucion,
					b.razon_social,b.direccion,c.descripcion,d.plan_descripcion,e.usuario,
					btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
					f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
					f.tipo_id_paciente||' '||f.paciente_id as id
			
					FROM rc_devoluciones a,empresas b,centros_utilidad c,
					planes d,system_usuarios e,pacientes f, cuentas g, ingresos h
					WHERE a.devolucion_id='".$_REQUEST['recibo']."'
					AND a.prefijo='".$_REQUEST['prefijo']."'
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND d.plan_id='".$data['plan_id']."'
					AND a.usuario_id=e.usuario_id
					AND a.numerodecuenta=".$_REQUEST['cuenta']."
					AND a.numerodecuenta=g.numerodecuenta
					AND g.ingreso=h.ingreso
					AND h.tipo_id_paciente=f.tipo_id_paciente
					AND h.paciente_id=f.paciente_id
					AND a.usuario_id=$usuario
					AND a.caja_id='$caja'";    
			}
			else
			if (empty($_REQUEST['dv']))
			{
					$data=$this->Traer_Id_Paciente_hosp($_REQUEST['recibo'],$_REQUEST['prefijo']);
					//AND a.usuario_id=".UserGetUID()."
					$sql="SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_efectivo,
					a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,b.direccion,
					c.descripcion,d.plan_descripcion,e.usuario,
					btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
					f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
					f.tipo_id_paciente||' '||f.paciente_id as id,
					CASE WHEN a.estado ='0' THEN a.total_abono ELSE -1 END AS total_abono
					FROM recibos_caja a,empresas b,centros_utilidad c,
					planes d,system_usuarios e,pacientes f
					WHERE a.recibo_caja='".$_REQUEST['recibo']."'
					AND a.prefijo='".$_REQUEST['prefijo']."'
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND d.plan_id='".$data['plan_id']."'
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=".$usuario."
					AND tipo_id_paciente='".$data['tipo_id_paciente']."'
					AND paciente_id='".$data['paciente_id']."'
					AND a.caja_id='$caja'
					AND a.estado IN ('0','1');";
			}
			else
			if(!empty($cierre))
			{
				$data=$this->Traer_Id_Paciente_dv($_REQUEST['recibo'],$_REQUEST['prefijo']);
				$sql="SELECT a.fecha_registro,a.recibo_caja,a.prefijo,a.caja_id,a.total_devolucion,
				b.razon_social,b.direccion,c.descripcion,d.plan_descripcion,e.usuario,
				btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
				f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
				f.tipo_id_paciente||' '||f.paciente_id as id
		
				FROM rc_devoluciones a,empresas b,centros_utilidad c,
				planes d,system_usuarios e,pacientes f
				WHERE a.recibo_caja='".$_REQUEST['recibo']."'
				AND a.prefijo='".$_REQUEST['prefijo']."'
				AND a.empresa_id=b.empresa_id
				AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
				AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
				AND d.plan_id='".$data['plan_id']."'
				AND a.usuario_id=e.usuario_id
				AND a.usuario_id=".UserGetUID()."
				AND tipo_id_paciente='".$data['tipo_id_paciente']."'
				AND paciente_id='".$data['paciente_id']."'
				AND a.caja_id='$caja'"; 
			}
					list($dbconn) = GetDBconn();
					$resulta=$dbconn->execute($sql);
          if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
					return false;
					}
			if($sw==2 AND empty($_REQUEST['cuenta']) AND empty($_REQUEST['cierre2']))
			{
				while(!$resulta->EOF)
				{
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				$resulta->Close();
				IncludeLib("reportes/cierre_caja"); //car
				GenerarCierreDeCajaConfirmadoControl($var,$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE_DEV'],$cierre);
				$_SESSION['CAJA']['PARAM']='ShowReport';//esta variable es para que muestre el reporte
				$this->CierreConfirmado();
				return true;
			}
			else
			if($sw==2 AND !empty($_REQUEST['cierre2']) AND empty($_REQUEST['dv']) AND empty($_REQUEST['file']) AND empty($_REQUEST['cuenta']))
			{   
				$var=$resulta->GetRowAssoc($ToUpper = false);
					IncludeLib("reportes/recibo_caja"); //car
					//unset($_SESSION['CAJA']['PARAM']);
					GenerarReciboCajaCierre($var);
					//GenerarReciboCaja($var);
					$_SESSION['CAJA']['PARAM']='ShowReport';//esta variable es para que muestre el reporte
			}
			//else
			if(!empty($_REQUEST['dev']))
			{   
				$var=$resulta->GetRowAssoc($ToUpper = false);
					IncludeLib("reportes/recibo_caja"); //car
					//unset($_SESSION['CAJA']['PARAM']);
					GenerarReciboDevolucionControlCierre($var);
					$_SESSION['CAJA']['PARAM']='ShowReport';//esta variable es para que muestre el reporte
			}
			else
			if($sw==1 AND empty($_REQUEST['dev']))
			{ 
					$var=$resulta->GetRowAssoc($ToUpper = false);
					IncludeLib("reportes/recibo_caja"); //car
					//unset($_SESSION['CAJA']['PARAM']);
					GenerarReciboCajaCierre($var,$sw,$cuenta);
					//GenerarReciboCaja($var);
					$_SESSION['CAJA']['PARAM']='ShowReport';//esta variable es para que muestre el 
			}
		if($_REQUEST['retorno']==1)
		{ 
			$_SESSION['CAJA']['PARAM']='ShowReport';//esta variable es para que muestre el reporte   
      if(!empty($_REQUEST['cierre2']))
				$cierre=$_REQUEST['cierre2'];
			$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw,'',$cuenta);
		}
		else
		{
			$this->RevisarRecibosHoy($id,$caja,$sw,$dpto,$caja_des);
		}	
		
		return true;
	}
	
	
	function Reportes_Pdf_Conceptos()
	{
		$cierre=$_REQUEST['cierre'];
		$id=$_REQUEST['id'];
		$caja=$_REQUEST['caja'];
		$fecha=$_REQUEST['fecha'];
		$sw=$_REQUEST['sw_recibo'];
		$dpto=$_REQUEST['dpto'];
		$caja_des=$_REQUEST['descripcion'];
		$actual=$_REQUEST['actual'];
		$devolucion=$_REQUEST['devolucion'];
		$cuenta=$_REQUEST['cuenta_tipo'];
		$usuario=$_REQUEST['usuario'];
		$data=$this->Traer_Id_Cliente($_REQUEST['recibo'],$_REQUEST['prefijo']);
					//AND a.usuario_id=".UserGetUID()."
/*					$sql="SELECT a.fecha_registro,a.factura_fiscal,a.prefijo,
										a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
										a.total_tarjetas,a.total_cheques,a.total_bonos,
										b.razon_social,b.direccion,c.descripcion,
										e.usuario,nombre_tercero as nombre,
										f.tipo_id_tercero||' '||f.tercero_id as id
			
					FROM fac_facturas_contado a,empresas b,centros_utilidad c,
								system_usuarios e,terceros f
					WHERE a.factura_fiscal='".$_REQUEST['recibo']."'
					AND a.prefijo='".$_REQUEST['prefijo']."'
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=".$usuario."
					AND f.tipo_id_tercero='".$data['tipo_id_tercero']."'
					AND f.tercero_id='".$data['tercero_id']."'
					AND a.caja_id='$caja'";*/
			$sql="SELECT a.cierre_caja_id,a.fecha_registro,a.usuario_id,a.prefijo,
										a.factura_fiscal,a.caja_id,a.total_efectivo,a.total_cheques,
										a.total_tarjetas,a.total_bonos,a.total_abono,
										d.nombre , e.razon_social,e.direccion, f.descripcion AS cu
					FROM fac_facturas_contado a, cierre_de_caja b, cierre_de_caja_detalle c,
							system_usuarios d, empresas e, centros_utilidad f
					WHERE b.cierre_de_caja_id=$cierre
					AND a.cierre_caja_id=c.cierre_caja_id 
					AND a.usuario_id=d.usuario_id
					AND a.estado IN ('0')
					AND b.cierre_de_caja_id=c.cierre_de_caja_id 
					AND b.empresa_id=e.empresa_id
					AND b.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND b.empresa_id=f.empresa_id
					AND b.centro_utilidad=f.empresa_id
					AND b.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."';";
					list($dbconn) = GetDBconn();
					$resulta=$dbconn->execute($sql);
          if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al Cargar el Modulo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					$this->fileError = __FILE__;
					$this->lineError = __LINE__;
					return false;
					}
				while(!$resulta->EOF)
				{
					$var[]=$resulta->GetRowAssoc($ToUpper = false);
					$resulta->MoveNext();
				}
				$resulta->Close();
				IncludeLib("reportes/cierre_caja"); //car
				GenerarCierreDeCajaConceptoConfirmado($var,$cierre);
				$_SESSION['CAJA']['PARAM']='ShowReportConcepto';//esta variable es para que muestre el reporte
				$this->CierreConfirmado();
				return true;
	}

	
	//esta funcion saca los reportes de recibos de caja en pos hospitalizacion
	function Reportes_Pos_Hosp()
	{ 
		$cierre=$_REQUEST['cierre'];
		$id=$_REQUEST['id'];
		$caja=$_REQUEST['caja'];
		$fecha=$_REQUEST['fecha'];
		$sw=$_REQUEST['sw_recibo'];
		$dpto=$_REQUEST['dpto'];
		$caja_des=$_REQUEST['descripcion'];
		$actual=$_REQUEST['actual'];
			
		/*echo "<br>"."cierre:".$cierre;
		echo "<br>"."id:".$id;
		echo "<br>"."caja_id:".$caja_id;
		echo "<br>"."fecha:".$fecha;
		echo "<br>"."sw:".$sw;
		echo "<br>"."actual:".$actual;exit;*/
		list($dbconn) = GetDBconn();		
			if (!empty($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN']))
			{
				$EmpresaId=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][empresa_id];
				$CentroU=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][centro_utilidad];
				$cierre=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][cierre_de_caja_id];
				$id=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][usuario_id];
				$caja=$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN'][0][caja_id];
				$query="SELECT a.empresa_id,a.centro_utilidad,a.caja_id,
										a.cierre_de_caja_id,a.fecha_registro,
										b.descripcion as caja,a.total_efectivo,
										a.total_cheques,a.total_tarjetas,a.total_devolucion,
										entrega_efectivo,a.usuario_id,a.observaciones_confirmacion,
										f.usuario, e.descripcion AS utilidad,
										d.razon_social
									FROM cierre_de_caja a,cajas b,cajas_usuarios c,empresas d,
											centros_utilidad e, system_usuarios f
									WHERE a.caja_id=b.caja_id 
										AND a.empresa_id='$EmpresaId' 
										AND a.empresa_id=d.empresa_id
										AND a.centro_utilidad='$CentroU' 
										AND a.empresa_id=e.empresa_id
										AND a.centro_utilidad=e.centro_utilidad
										AND a.cierre_de_caja_id=$cierre
										AND c.caja_id=a.caja_id 
										AND a.usuario_id=c.usuario_id 
										AND a.usuario_id=$id
										AND a.usuario_id=f.usuario_id
										AND a.caja_id=$caja
									ORDER BY a.fecha_registro;";
					$resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las facturas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }

				$var=$resulta->GetRowAssoc($ToUpper = false);
				if (!IncludeFile("classes/reports/reports.class.php")) {
				$this->error = "No se pudo inicializar la Clase de Reportes";
				$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
				return false;
				}

				$classReport = new reports;
				$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
				$reporte=$classReport->PrintReport('pos','app','Control_Cierre','cierre_de_caja',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
				if(!$reporte){
						$this->error = $classReport->GetError();
						$this->mensajeDeError = $classReport->MensajeDeError();
						unset($classReport);
						return false;
				}
		
				$resultado=$classReport->GetExecResultado();
				unset($classReport);
				$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw);
				return true;
			}
		foreach($_REQUEST['op'] as $index=>$arr)
		{
					$vect=explode("*",$arr);
					//vect[0]-recibo,vect[1]-prefijo,vect[2]-numero de cuenta,vect[3]-usuario
					$data=$this->Traer_Id_Paciente_hosp($vect[0],$vect[1]);
					$sql="SELECT a.fecha_ingcaja,a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_abono,a.total_efectivo,
					a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,b.direccion,
					c.descripcion,d.plan_descripcion,e.usuario,
					btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
					f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
					f.tipo_id_paciente||' '||f.paciente_id as id
					FROM recibos_caja a,empresas b,centros_utilidad c,
					planes d,system_usuarios e,pacientes f
					WHERE a.recibo_caja='".$vect[0]."'
					AND a.prefijo='".$vect[1]."'
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND d.plan_id='".$data['plan_id']."'
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=".$vect[3]."
					AND tipo_id_paciente='".$data['tipo_id_paciente']."'
					AND paciente_id='".$data['paciente_id']."'
					AND a.caja_id='$caja'
					AND a.estado IN ('0')";
					$resulta=$dbconn->execute($sql);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
										return false;
									}
									if (!IncludeFile("classes/reports/reports.class.php")) {
									$this->error = "No se pudo inicializar la Clase de Reportes";
									$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
									return false;
							}
									$var=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->Close();
								$classReport = new reports;
								$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
								$reporte=$classReport->PrintReport('pos','app','CajaGeneral','Recibo',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
								if(!$reporte){
										$this->error = $classReport->GetError();
										$this->mensajeDeError = $classReport->MensajeDeError();
										unset($classReport);
										return false;
								}
				
								$resultado=$classReport->GetExecResultado();
								unset($classReport);
								$var='';
		}

		foreach($_REQUEST['opdv'] as $index=>$arr)
		{
					$vect=explode("*",$arr);
					//vect[0]-recibo,vect[1]-prefijo,vect[2]-numero de cuenta
					$data=$this->Traer_Id_Paciente_dv($vect[0],$vect[1]);
					$sql="SELECT a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_devolucion,
											b.razon_social,b.direccion,c.descripcion,d.plan_descripcion,
											e.usuario,btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
											f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
											f.tipo_id_paciente||' '||f.paciente_id as id
								FROM rc_devoluciones a,empresas b,centros_utilidad c,
										planes d,system_usuarios e,pacientes f
								WHERE a.recibo_caja='".$vect[0]."'
								AND a.prefijo='".$vect[1]."'
								AND a.empresa_id=b.empresa_id
								AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND d.plan_id='".$data['plan_id']."'
								AND a.usuario_id=e.usuario_id
								AND a.usuario_id=".UserGetUID()."
								AND tipo_id_paciente='".$data['tipo_id_paciente']."'
								AND paciente_id='".$data['paciente_id']."'
								AND a.caja_id='$caja'";
					$resulta=$dbconn->execute($sql);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
										return false;
									}
									if (!IncludeFile("classes/reports/reports.class.php")) {
									$this->error = "No se pudo inicializar la Clase de Reportes";
									$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
									return false;
							}
									$var=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->Close();
								$classReport = new reports;
								$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
								$reporte=$classReport->PrintReport('pos','app','CajaGeneral','Devolucion',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
								if(!$reporte){
										$this->error = $classReport->GetError();
										$this->mensajeDeError = $classReport->MensajeDeError();
										unset($classReport);
										return false;
								}
				
								$resultado=$classReport->GetExecResultado();
								unset($classReport);
								$var='';
		}

		foreach($_REQUEST['opdv2'] as $index=>$arr)
		{
					$vect=explode("*",$arr);
					//vect[0]-cuenta,vect[1]-usuario,vect[2]-cierre,vect[3]-devolucion_id,
					//vect[4]-prefijo
					$data=$this->TraerPacienteCajaGeneralDev($vect[0],$vect[1],$vect[2],'1');
					//$data=$this->Traer_Id_Paciente_dv($vect[0],$vect[1]);
					$sql="SELECT a.recibo_caja,a.prefijo,a.caja_id,a.fecha_registro,a.total_devolucion,
											b.razon_social,b.direccion,c.descripcion,d.plan_descripcion,
											e.usuario,btrim(f.primer_nombre||' '||f.segundo_nombre||' ' ||
											f.primer_apellido||' '||f.segundo_apellido,'') as nombre,
											f.tipo_id_paciente||' '||f.paciente_id as id
								FROM rc_devoluciones a,empresas b,centros_utilidad c,
										planes d,system_usuarios e,pacientes f
								WHERE a.devolucion_id=".$vect[3]."
								AND a.prefijo='".$vect[4]."'
								AND a.empresa_id=b.empresa_id
								AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
								AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
								AND d.plan_id='".$data['plan_id']."'
								AND a.usuario_id=e.usuario_id
								AND a.usuario_id=".$vect[1]."
								AND tipo_id_paciente='".$data['tipo_id_paciente']."'
								AND paciente_id='".$data['paciente_id']."'
								AND a.caja_id='$caja'";
					$resulta=$dbconn->execute($sql);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
										return false;
									}
									if (!IncludeFile("classes/reports/reports.class.php")) {
									$this->error = "No se pudo inicializar la Clase de Reportes";
									$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
									return false;
							}
									$var=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->Close();
								$classReport = new reports;
								$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
								$reporte=$classReport->PrintReport('pos','app','CajaGeneral','Devolucion',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
								if(!$reporte){
										$this->error = $classReport->GetError();
										$this->mensajeDeError = $classReport->MensajeDeError();
										unset($classReport);
										return false;
								}
				
								$resultado=$classReport->GetExecResultado();
								unset($classReport);
								$var='';
		}

		if($_REQUEST['retorno']==1)
		{
			$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw);
		}
		else
		{
			$this->RevisarRecibosHoy($id,$caja,$sw,$dpto,$caja_des);
		}	
		return true;
	}
	
	
	function Reportes_Pos_Concepto()
	{ 
		$cierre=$_REQUEST['cierre'];
		$id=$_REQUEST['id'];
		$caja=$_REQUEST['caja'];
		$fecha=$_REQUEST['fecha'];
		$sw=$_REQUEST['sw_recibo'];
		$dpto=$_REQUEST['dpto'];
		$caja_des=$_REQUEST['descripcion'];
		$actual=$_REQUEST['actual'];
			
		/*echo "<br>"."cierre:".$cierre;
		echo "<br>"."id:".$id;
		echo "<br>"."caja_id:".$caja_id;
		echo "<br>"."fecha:".$fecha;
		echo "<br>"."sw:".$sw;
		echo "<br>"."actual:".$actual;exit;*/
		list($dbconn) = GetDBconn();		
		foreach($_REQUEST['op'] as $index=>$arr)
		{
					$vect=explode("*",$arr);
					//vect[0]-recibo,vect[1]-prefijo,vect[2]-numero de cuenta
					$data=$this->Traer_Id_Cliente($vect[0],$vect[1]);
					$sql="SELECT a.fecha_registro,a.factura_fiscal,a.prefijo,a.caja_id,
										a.fecha_registro,a.total_abono,a.total_efectivo,
					a.total_tarjetas,a.total_cheques,a.total_bonos,b.razon_social,b.direccion,
					c.descripcion,e.usuario,nombre_tercero as nombre,
					f.tipo_id_tercero||' '||f.tercero_id as id
					FROM fac_facturas_contado a,empresas b,centros_utilidad c,
								system_usuarios e,terceros f
					WHERE a.factura_fiscal='".$vect[0]."'
					AND a.prefijo='".$vect[1]."'
					AND a.empresa_id=b.empresa_id
					AND c.empresa_id='".$_SESSION['CONTROL_CIERRE']['EMP']."'
					AND c.centro_utilidad='".$_SESSION['CONTROL_CIERRE']['CENTRO']."'
					AND a.usuario_id=e.usuario_id
					AND a.usuario_id=".$id."
					AND f.tipo_id_tercero='".$data['tipo_id_tercero']."'
					AND f.tercero_id='".$data['tercero_id']."'
					AND a.caja_id='$caja'
					AND a.estado IN ('0')";
					$resulta=$dbconn->execute($sql);
									if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										$this->fileError = __FILE__;
										$this->lineError = __LINE__;
										return false;
									}
									if (!IncludeFile("classes/reports/reports.class.php")) {
									$this->error = "No se pudo inicializar la Clase de Reportes";
									$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
									return false;
							}
									$var=$resulta->GetRowAssoc($ToUpper = false);
									$resulta->Close();
								$classReport = new reports;
								$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
								$reporte=$classReport->PrintReport('pos','app','CajaGeneral','Recibo',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
								if(!$reporte){
										$this->error = $classReport->GetError();
										$this->mensajeDeError = $classReport->MensajeDeError();
										unset($classReport);
										return false;
								}
				
								$resultado=$classReport->GetExecResultado();
								unset($classReport);
								$var='';
		}


		if($_REQUEST['retorno']==1)
		{
			$this->CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw);
		}
		else
		{
			$this->RevisarRecibosHoy($id,$caja,$sw,$dpto,$caja_des);
		}	
		return true;
	}

	
	/****************** Encabezado Reportes de Facturas  *****************************/

	/****************** Reportes de Facturas  *****************************/

	 /**
    *
    */
    function Reportes()
    {
		//ECHO GERERAR; EXIT;
				$cierre=$_REQUEST['cierre'];
				$id=$_REQUEST['id'];
				$caja=$_REQUEST['caja'];
				$fecha=$_REQUEST['fecha'];
				$sw=$_REQUEST['sw_recibo'];
				$dpto=$_REQUEST['dpto'];
				$caja_des=$_REQUEST['descripcion'];
				$actual=$_REQUEST['actual'];
				$op=$_REQUEST['op'];
				//echo 'op--'.$op; exit;
				//print_r($_REQUEST);exit;
				
				if(empty($_REQUEST['obs']))
				{
					$go_to=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
					array('op'=>$op,'actual'=>$actual,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					$this->FormaMensaje('ES DE CARACTER OBLIGATORIO JUSTIFICAR  LA IMPRESION','ADVERTENCIA',$go_to,'Volver');
					return true;
				}
					list($dbconn) = GetDBconn();
//print_r($_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']); exit;
				foreach($_REQUEST['op'] as $index=>$numerodecuenta)
				{
				
								$arr_op=explode("^",$numerodecuenta);
								/***Insercion de rollo*****/
								
									$query="INSERT INTO control_cierre_auditoria
													(
													documento,
													prefijo,
													usuario_id,
													fecha_registro,
													observacion,
													sw_facturado
													)
													VALUES
													(
														$arr_op[1],
														'$arr_op[2]',
														".UserGetUID().",
														now(),
														'".$_REQUEST['obs']."',
														'1'
													)";
										$dbconn->Execute($query);
									/*	if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Insertar en control_cierre_auditoria";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
										}*/
								/**************************/
								
								$cuenta=$arr_op[0];//esta variable en la posicion 0 es el numerodecuenta.
								$var[0]=$this->EncabezadoFactura($cuenta);
								$_SESSION['CAJA']['FACTURA']['encabezado']=$var[0];

								//list($dbconn) = GetDBconn();
								if (!IncludeFile("classes/reports/reports.class.php")) {
										$this->error = "No se pudo inicializar la Clase de Reportes";
										$this->mensajeDeError = "No se pudo Incluir el archivo : classes/reports/reports.class.php";
										return false;
								}
								//siempre se hace la del paciente
								$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
													a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
													b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
													e.texto1, e.texto2, e.mensaje, f.*
													from cuentas_detalle as a, tarifarios_detalle as b,
													fac_facturas_cuentas as c, documentos as e, fac_facturas as f
													where a.numerodecuenta=$cuenta and a.cargo=b.cargo
													and a.tarifario_id=b.tarifario_id
													and a.cargo!='DESCUENTO'
													and c.numerodecuenta=a.numerodecuenta
													and c.sw_tipo=0
													and a.empresa_id=e.empresa_id
													and c.prefijo=e.prefijo
													and c.prefijo=f.prefijo
													and c.factura_fiscal=f.factura_fiscal
													order by b.grupo_tipo_cargo desc ";
								$result = $dbconn->Execute($query);
								if ($dbconn->ErrorNo() != 0) {
												$this->error = "Error al Cargar el Modulo";
												$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
												return false;
								}
								while(!$result->EOF)
								{
												$var[]=$result->GetRowAssoc($ToUpper = false);
												$result->MoveNext();
								}
								$result->Close();
								$classReport = new reports;
								$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
								$reporte=$classReport->PrintReport('pos','app','Control_Cierre','factura',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
								if(!$reporte){
										$this->error = $classReport->GetError();
										$this->mensajeDeError = $classReport->MensajeDeError();
										unset($classReport);
										return false;
								}
				
								$resultado=$classReport->GetExecResultado();
								unset($classReport);
								$var='';
								if(!empty($_SESSION['CAJA']['FACTURA']['EMPRESA']))
								{
											$cuenta=$_SESSION['CAJA']['FACTURA']['EMPRESA']['cuenta'];
											//$var[0]=$this->EncabezadoFactura($cuenta);
											$var[0]=$_SESSION['CAJA']['FACTURA']['encabezado'];
											$query = "select c.prefijo, c.factura_fiscal, a.valor_nocubierto,a.precio,
																a.cargo, a.tarifario_id, a.cantidad, a.fecha_cargo, a.transaccion,
																b.descripcion as desccargo, a.departamento, b.grupo_tipo_cargo, c.sw_tipo,
																e.texto1, e.texto2, e.mensaje, f.*
																from cuentas_detalle as a, tarifarios_detalle as b,
																fac_facturas_cuentas as c, documentos as e, fac_facturas as f
																where a.numerodecuenta=$cuenta and a.cargo=b.cargo
																and a.tarifario_id=b.tarifario_id
																and a.cargo!='DESCUENTO'
																and c.numerodecuenta=a.numerodecuenta
																and c.sw_tipo=1
																and a.empresa_id=e.empresa_id
																and c.prefijo=e.prefijo
																and c.prefijo=f.prefijo
																and c.factura_fiscal=f.factura_fiscal
																order by b.grupo_tipo_cargo desc ";
											$result = $dbconn->Execute($query);
											if ($dbconn->ErrorNo() != 0) {
															$this->error = "Error al Cargar el Modulo";
															$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
															return false;
											}
											while(!$result->EOF)
											{
															$var[]=$result->GetRowAssoc($ToUpper = false);
															$result->MoveNext();
											}
											$result->Close();
											$classReport = new reports;
											$impresora=$classReport->GetImpresoraPredeterminada($tipo_reporte='pos');
											$reporte=$classReport->PrintReport('pos','app','CajaGeneral','factura',$var,$impresora,$orientacion='',$unidades='',$formato='',$html=1);
											if(!$reporte){
													$this->error = $classReport->GetError();
													$this->mensajeDeError = $classReport->MensajeDeError();
													unset($classReport);
													return false;
											}
											$resultado=$classReport->GetExecResultado();
											unset($classReport);
								}
								$var='';
								if(!empty($resultado[codigo])){
										"El PrintReport retorno : " . $resultado[codigo] . "<br>";
								}
								//aqui va donde vuelve

				}

				if($actual==1)
				{
					$go_to=ModuloGetURL('app','Control_Cierre','user','RevisarFacturasHoy',
					array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des));
					$this->FormaMensaje('IMPRESION HECHA SATISFACTORIAMENTE','CONFIRMACION',$go_to,'Volver');
				}
				else
				{
					$go_to=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',
					array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					$this->FormaMensaje('IMPRESION HECHA SATISFACTORIAMENTE','CONFIRMACION',$go_to,'Volver');
				}
					return true;
    }

	/****************** Reportes de Facturas  *****************************/



//MenuOs_Atencion
}//fin clase user

?>
