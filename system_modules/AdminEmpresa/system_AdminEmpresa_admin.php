<?php

/**
*MODULO Administrativo para el Manejo de Usuarios del Sistema
*
* @Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Email: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos para realizar la administracion de usuarios
*/

class system_AdminEmpresa_admin extends classModulo
{
		var $limit;
		var $conteo;

	function system_AdminEmpresa_admin()
	{
		$this->limit=GetLimitBrowser();
  	return true;
	}




/**
* Funcion donde se llama la funcion MenudeEmpresas
* @return boolean
*/

	function main()
	{
	 			unset($_SESSION['ADMIN']['DPTO']);
        unset($_SESSION['ADMIN']['UNIDAD']);
				unset($_SESSION['ADMIN']['EMPRESAID']);
				unset($_SESSION['ADMIN']['CENTROU']);
				list($dbconn) = GetDBconn();
	   		$query = "	SELECT a.empresa_id,a.razon_social,a.website,
										a.sw_usuarios_multiempresa,a.codigo_sgsss from
										empresas as a,system_usuarios_administradores as b where
										b.usuario_id=".UserGetUID()." and a.empresa_id=b.empresa_id
										order by razon_social";
				$result = $dbconn->Execute($query);

				if(!$result->EOF)
				{

							$i=0;
							while(!$result->EOF)
										{
												$var[$i]=$result->GetRowAssoc($ToUpper = false);
												$i++;
												$result->MoveNext();
										}
							$this->MenuEmpresas($var);
							return true;
				}
				else
				{
					$this->error = "PERMISO DENEGADO";
					$this->mensajeDeError = "El usuario no tiene permisos de administrador ";
					return false;
				}
			return true;
  }





/**
* Funcion que busca los datos de los centros de utilidad según la empresa
* @return array
*/

 //$var si viene con datos realiza el filtro mediante la identifiacción del centro_utilidad
 // si viene vacia trae todos los centros de utilidad de la empresa en el momento...
	function TraerListadoCentroUtilidad($variable=''){
	list($dbconn) = GetDBconn();
		if($variable=='')
		{
				$query = " SELECT centro_utilidad,descripcion
							 from centros_utilidad where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 order by centro_utilidad";
		}
		else
		{

				 $query = " SELECT centro_utilidad,descripcion
							 from centros_utilidad where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 and centro_utilidad='".$variable."'";
		}
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar los centros de utilidad 555";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;

		while (!$result->EOF)
		{
			$var[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}

		 return $var;
	}



/*funcion que saca el numero de unidades funcionales q posee cada centro de utilidad*/

function TraerConteoUnidadFuncional($cu)
{

		list($dbconn) = GetDBconn();

		$query = " SELECT COUNT(*)
							 from unidades_funcionales
							 where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 and centro_utilidad='".$cu."' ";
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar conteo de unidades funcionales";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		 return $result->fields[0];
	}



	/*funcion que saca el numero de unidades funcionales q posee cada centro de utilidad*/

function TraerConteoDepartamentos($uf)
{

		list($dbconn) = GetDBconn();

		$query = " SELECT COUNT(*)
							 from departamentos
							 where unidad_funcional='".$uf."'
							 AND empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 AND centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'";

		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar conteo de unidades funcionales";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}

		 return $result->fields[0];
	}



 //$var si viene con datos realiza el filtro mediante la identifiacción del centro_utilidad
 // si viene vacia trae todos los centros de utilidad de la empresa en el momento...
 //REVISION 9/AUGUST/2004 QUITARON LOS CAMPOS sw_hospitalizacion1 Y sw_asistencial1
	function TraerListadoDpto($variable=''){
	list($dbconn) = GetDBconn();
		if($variable=='')
		{
			$query = " SELECT departamento,descripcion,servicio,sw_internacion
							 from departamentos
							 where unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
							 and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."' order by departamento";
		}
		else
		{
			$query = "	SELECT
									departamento,descripcion,servicio,sw_internacion as sw_hospitalizacion1
									from departamentos
									where unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
									and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
									and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."' AND
									departamento='".$_SESSION['ADMIN']['DPTO']."' order by departamento";
		}
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar en los departamentos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;

		while (!$result->EOF)
		{
			$var[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}

		 return $var;
	}




/**
* Funcion que busca los datos de los centros de utilidad según la empresa
* @return array
*/

 //$var si viene con datos realiza el filtro mediante la identifiacción del centro_utilidad
 // si viene vacia trae todos los centros de utilidad de la empresa en el momento...
	function TraerListadoUnidadFuncional($variable=''){
	list($dbconn) = GetDBconn();
		if($variable=='')
		{
			$query = " SELECT centro_utilidad,descripcion,unidad_funcional
							 from unidades_funcionales where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."' order by unidad_funcional";
		}
		else
		{
			$query = " SELECT centro_utilidad,descripcion,unidad_funcional
							 from unidades_funcionales where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
							 and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."' AND
							 unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."' order by unidad_funcional";
		}
		$result = $dbconn->Execute($query);

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al buscar las unidades funcionales";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
    $i=0;

		while (!$result->EOF)
		{
			$var[$i]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			$i++;
		}

		 return $var;
	}




/*	funcion que trae los datos de la empresa
 *  datos como razon social,identificación,representante legal etc..
 *  retorna arreglo.
*/
function TraerDatosEmpresa($id)
{

  	list($dbconn) = GetDBconn();
		if(!empty($id))
		{
			$query="select empresa_id,razon_social,tipo_id_tercero,id,codigo_sgsss,
							tipo_pais_id,tipo_dpto_id,tipo_mpio_id,direccion,telefonos,codigo_postal,
							sw_activa,sw_usuarios_multiempresa,id,representante_legal,
							website,email,fax from empresas	where empresa_id='".$id."';";
    }
		else
		{
			$query="select empresa_id,razon_social,tipo_id_tercero,id,codigo_sgsss,
							tipo_pais_id,tipo_dpto_id,tipo_mpio_id,direccion,telefonos,codigo_postal,
							sw_activa,sw_usuarios_multiempresa,id,representante_legal,
							website,email,fax from empresas";
		}

		$resulta=$dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al listar la tabla de empresa";
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




/**
* Funcion donde se borra el centro de utilidad despues q no tenga relaciones
* sera borrada.
* @return boolean
*/

function BorrarCentroUtilidad()
{
						list($dbconn) = GetDBconn();
					 	$query = "DELETE FROM centros_utilidad WHERE
											empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."' and
											centro_utilidad ='".$_REQUEST['cu']."'";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
						$this->frmError["MensajeError"]="EL CENTRO DE UTILIDAD NO SE PUEDE BORRAR YA QUE TIENE REGISTROS CARGADOS..";
            if(!$this->ListadoCentrosUtilidad()){
						return false;
						}
						return true;
					}
				$this->ListadoCentrosUtilidad();
				return true;
}


/**
* Funcion donde se borra la unidad funcional si no tiene relaciones sera borrada.
* sera borrada.
* @return boolean
*/

function BorrarUnidadFuncional()
{
						list($dbconn) = GetDBconn();
					 	$query = "DELETE FROM unidades_funcionales
											WHERE centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
											and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."' and
											unidad_funcional ='".$_REQUEST['uf']."'";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
						$this->frmError["MensajeError"]="LA UNIDAD FUNCIONAL NO SE PUEDE BORRAR YA QUE TIENE REGISTROS CARGADOS.";
            if(!$this->ListadoCentrosUtilidad()){
						return false;
						}
						return true;
					}
				$this->FormaUnidadFuncional();
				return true;
}


/**
* Funcion donde se borra la unidad funcional si no tiene relaciones sera borrada.
* sera borrada.
* @return boolean
*/

function BorrarDpto()
{
						list($dbconn) = GetDBconn();
						$query = "DELETE FROM departamentos
											WHERE
											unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
											and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
											and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."' and
											departamento ='".$_REQUEST['dpto']."'";
						$dbconn->Execute($query);
						if ($dbconn->ErrorNo() != 0) {
						$this->frmError["MensajeError"]="EL DEPARTAMENTO NO SE PUEDE BORRAR YA QUE TIENE REGISTROS CARGADOS.";
            if(!$this->ListadoDepartamentos()){
						return false;
						}
						return true;
					}
				$this->ListadoDepartamentos();
				return true;
}





/*funcion en la cual se modifica un centro de utilidad
 * retorna booleano
 */
 function ModificarCentroutilidad()
 {
 				list($dbconn) = GetDBconn();
				if($_REQUEST['descripcion']=='' ||		$_REQUEST['cu']=='')
				{
					if($_REQUEST['descripcion']==''){ $this->frmError["descripcion"]=1; }
					if($_REQUEST['cu']==''){ $this->frmError["centro"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					$this->FormaCentroUtilidad('true',$_REQUEST['cu']);
					return true;
				}

							if($_SESSION['ADMIN']['CENTROU']!=$_REQUEST['cu'])
							{
										$query = "SELECT COUNT(*) FROM centros_utilidad
														WHERE empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
														and centro_utilidad='".$_REQUEST['cu']."'";
										$resultado=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar existencia ID en el centro de utilidad 237";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									if($resultado->fields[0]>=1)
									{
										$this->frmError["MensajeError"]="El ID del centro de utilidad ya existe.";
										$this->frmError["centro"]=1;
										$this->FormaCentroUtilidad('true',$_REQUEST['cu']);
										return true;
									}
									$query = "UPDATE centros_utilidad
												SET centro_utilidad='".$_REQUEST['cu']."',
												descripcion='".$_REQUEST['descripcion']."'
												where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
												and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Insertar el centro de utilidad 235";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
									}
							}
							else
							{
									$query = "UPDATE centros_utilidad
														SET centro_utilidad='".$_REQUEST['cu']."',
														descripcion='".$_REQUEST['descripcion']."'
														where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
														and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al Insertar el centro de utilidad 242";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
							}

						$this->ListadoCentrosUtilidad();
						return true;
	}




/*funcion en la cual se modifica una unidad funcional.
 * retorna booleano
 */
 function ModificarUnidadFuncional()
 {
			list($dbconn) = GetDBconn();
				if($_REQUEST['descripcion']=='' ||		$_REQUEST['uf']=='')
				{
					if($_REQUEST['descripcion']==''){ $this->frmError["descripcion"]=1; }
					if($_REQUEST['uf']==''){ $this->frmError["centro"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					$this->FormaOPUnidadFuncional('true',$_REQUEST['uf']);
					return true;
				}

							if($_SESSION['ADMIN']['UNIDAD']!=$_REQUEST['uf'])
							{
										$query = "SELECT COUNT(*) FROM unidades_funcionales
															WHERE centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
															AND empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
															and unidad_funcional='".$_REQUEST['uf']."'";
										$resultado=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar existencia ID en la unidad funcional";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									if($resultado->fields[0]>=1)
									{
										$this->frmError["MensajeError"]="El ID de la unidad funcional ya existe.";
										$this->frmError["centro"]=1;
										$this->FormaOPUnidadFuncional('true',$_REQUEST['uf']);
										return true;
									}
									$query = "UPDATE unidades_funcionales
														SET unidad_funcional='".$_REQUEST['uf']."',
														descripcion='".$_REQUEST['descripcion']."'
														where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
														and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
														and unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al modificar unidad funcional";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
									}
							}
							else
							{
									$query = "UPDATE unidades_funcionales
														SET unidad_funcional='".$_REQUEST['uf']."',
														descripcion='".$_REQUEST['descripcion']."'
														where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
														and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
														and unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al  modificar unidad funcional";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
							}

						$this->FormaUnidadFuncional();
						return true;
 }






/*funcion en la cual se crea un nuevo centro de utilidad
 * retorna booleano
 */
 function InsertarCentroutilidad()
 {
				list($dbconn) = GetDBconn();
				if($_REQUEST['descripcion']=='' ||		$_REQUEST['cu']=='')
				{
					if($_REQUEST['descripcion']==''){ $this->frmError["descripcion"]=1; }
					if($_REQUEST['cu']==''){ $this->frmError["centro"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					$this->FormaCentroUtilidad('',$_REQUEST['cu']);
					return true;
				}
				else
				{

					  	$query = "SELECT COUNT(*) FROM centros_utilidad
											WHERE empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
											and centro_utilidad='".$_REQUEST['cu']."'";
							$resultado=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al buscar existencia ID en el centro de utilidad 237";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						if($resultado->fields[0]>=1)
						{
							$this->frmError["MensajeError"]="El ID del centro de utilidad ya existe.";
							$this->frmError["centro"]=1;
							$this->FormaCentroUtilidad('',$_REQUEST['cu']);
							return true;
						}

						$query = "INSERT INTO centros_utilidad(
											empresa_id,
											centro_utilidad,
											descripcion)
											VALUES(
											'".$_SESSION['ADMIN']['EMPRESAID']."',
											'".$_REQUEST['cu']."',
											'".$_REQUEST['descripcion']."')";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Insertar el centro de utilidad 242";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}

						$this->ListadoCentrosUtilidad();
						return true;
				}
	}





/*	funcion en la cual se crea una nueva unidad funcional.
 * 	retorna booleano
 */
 function InsertarUnidadFuncional()
 {
				list($dbconn) = GetDBconn();
				if($_REQUEST['descripcion']=='' ||		$_REQUEST['uf']=='')
				{
					if($_REQUEST['descripcion']==''){ $this->frmError["descripcion"]=1; }
					if($_REQUEST['uf']==''){ $this->frmError["centro"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					$this->FormaOPUnidadFuncional('',$_REQUEST['uf']);
					return true;
				}
				else
				{

					  	$query = "SELECT COUNT(*) FROM unidades_funcionales
												WHERE centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
											 	and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
												and unidad_funcional='".$_REQUEST['uf']."'";
							$resultado=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al buscar existencia ID en unidad funcional";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						if($resultado->fields[0]>=1)
						{
							$this->frmError["MensajeError"]="El ID del unidad funcional ya existe.";
							$this->frmError["centro"]=1;
							$this->FormaOPUnidadFuncional('',$_REQUEST['uf']);
							return true;
						}

						$query = "INSERT INTO unidades_funcionales(
											empresa_id,
											centro_utilidad,
											unidad_funcional,
											descripcion)
											VALUES(
											'".$_SESSION['ADMIN']['EMPRESAID']."',
											'".$_SESSION['ADMIN']['CENTROU']."',
											'".$_REQUEST['uf']."',
											'".$_REQUEST['descripcion']."')";
							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Insertar en la unidad funcional";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}

						$this->FormaUnidadFuncional();
						return true;
				}
	}



/*	funcion en la cual se crea una nueva unidad funcional.
 * 	retorna booleano
 */
 function InsertarDpto()
 {
				list($dbconn) = GetDBconn();
				if($_REQUEST['descripcion']=='' ||		$_REQUEST['dpto']=='' || $_REQUEST['servicio']==-1)
				{
					if($_REQUEST['descripcion']==''){ $this->frmError["descripcion"]=1; }
					if($_REQUEST['dpto']==''){ $this->frmError["centro"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					$this->FormaDepartamento('',$_REQUEST['dpto']);
					return true;
				}

				else
				{

					  	$query = "SELECT COUNT(*) FROM departamentos
												WHERE unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
												and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
											 	and empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
												and departamento='".$_REQUEST['dpto']."'";
							$resultado=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al buscar existencia ID del departamento";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						if($resultado->fields[0]>=1)
						{
							$this->frmError["MensajeError"]="El ID del Departamento ya existe.";
							$this->frmError["centro"]=1;
							$this->FormaDepartamento('',$_REQUEST['dpto']);
							return true;
						}
         /*ojo este es para validar si el numero de departamento esta repetido*/
							$query = "SELECT COUNT(*) FROM departamentos
												WHERE departamento='".$_REQUEST['dpto']."'";
							$resultado=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al buscar existencia ID del departamento";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
						if($resultado->fields[0]>=1)
						{
							$this->frmError["MensajeError"]="El ID del Departamento ya existe.";
							$this->frmError["centro"]=1;
							$this->FormaDepartamento('',$_REQUEST['dpto']);
							return true;
						}

			//$_REQUEST['hospital'] es sw_internacion .. ojo con eso, se dejo esto por q antes
			//tenia un campo llamado sw_hospitalizacion1,y otro sw_asistencias, ahora existe solamente
			//sw_internacion, entonces lo q hize fue borrar y dejar $_REQUEST['hospital'] ==sw_internacion
     	if(empty($_REQUEST['hospital']))
						{$hospital=0;}else{$hospital=1;}

						 $query = "INSERT INTO departamentos(
											unidad_funcional,
											empresa_id,
											centro_utilidad,
           						sw_internacion,
											servicio,
											departamento,
											descripcion)
											VALUES(
											'".$_SESSION['ADMIN']['UNIDAD']."',
											'".$_SESSION['ADMIN']['EMPRESAID']."',
											'".$_SESSION['ADMIN']['CENTROU']."',
											'".$hospital."',
											'".$_REQUEST['servicio']."',
											'".$_REQUEST['dpto']."',
											'".$_REQUEST['descripcion']."')";

							$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Insertar en departamentos";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}

						$this->ListadoDepartamentos();
						return true;
				}
	}



/*funcion en la cual se modifica una unidad funcional.
 * retorna booleano
 */
 function ModificarDpto()
 {
				list($dbconn) = GetDBconn();
				if($_REQUEST['descripcion']=='' ||		$_REQUEST['dpto']=='' || $_REQUEST['servicio']==-1)
				{
					if($_REQUEST['descripcion']==''){ $this->frmError["descripcion"]=1; }
					if($_REQUEST['dpto']==''){ $this->frmError["centro"]=1; }
					$this->frmError["MensajeError"]="Faltan datos obligatorios.";
					$this->FormaDepartamento('true',$_REQUEST['dpto']);
					return true;
				}

							if($_SESSION['ADMIN']['DPTO']!==$_REQUEST['dpto'])
							{
										$query = "SELECT COUNT(*) FROM departamentos
															WHERE
															centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
															AND empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
															and unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
															and departamento='".$_REQUEST['dpto']."'";
										$resultado=$dbconn->Execute($query);
										if ($dbconn->ErrorNo() != 0) {
										$this->error = "Error al buscar existencia ID en dpto";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
									}
									if($resultado->fields[0]>=1)
									{
										$this->frmError["MensajeError"]="El ID de dpto ya existe.";
										$this->frmError["centro"]=1;
										$this->FormaDepartamento('true',$_REQUEST['dpto']);
										return true;
									}

									if(empty($_REQUEST['hospital']))
									{$hospital=0;}else{$hospital=1;}
									 $query = "UPDATE departamentos
									          SET
														departamento='".$_REQUEST['dpto']."',
														descripcion='".$_REQUEST['descripcion']."',
														sw_internacion='".$hospital."',
														servicio='".$_REQUEST['servicio']."'
														where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
														and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
														and unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
														and departamento='".$_SESSION['ADMIN']['DPTO']."'";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al modificar unidad funcional";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
									}
							}
							else
							{
									if(empty($_REQUEST['hospital']))
									{$hospital=0;}else{$hospital=1;}
									 	$query = "UPDATE departamentos
									          SET
														departamento='".$_REQUEST['dpto']."',
														descripcion='".$_REQUEST['descripcion']."',
														sw_internacion='".$hospital."',
														servicio='".$_REQUEST['servicio']."'
														where empresa_id='".$_SESSION['ADMIN']['EMPRESAID']."'
														and centro_utilidad='".$_SESSION['ADMIN']['CENTROU']."'
														and unidad_funcional='".$_SESSION['ADMIN']['UNIDAD']."'
														and departamento='".$_SESSION['ADMIN']['DPTO']."'";

									$dbconn->Execute($query);
									if ($dbconn->ErrorNo() != 0) {
									$this->error = "Error al  modificar departamentos";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
								}
							}

						$this->ListadoDepartamentos();
						return true;
 }




	function TraerComboServicio()
	{
				list($dbconn) = GetDBconn();
				$query = "	SELECT servicio,descripcion from servicios
										WHERE servicio <> '0'
										order by descripcion";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer los servicios";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$i=0;

				while (!$result->EOF)
				{
					$var[$i]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
					$i++;
				}

				return $var;
	}

		function ListaTodos()
		{
				list($dbconn) = GetDBconn();
				 $query="select a.razon_social,e.descripcion as cu,f.descripcion as uf,
								r.departamento,r.descripcion as dpto from empresas a
								left join centros_utilidad as e on(e.empresa_id=a.empresa_id)
								left join unidades_funcionales as f on(f.empresa_id=e.empresa_id
								and f.centro_utilidad=e.centro_utilidad)
								left join departamentos as r on(r.empresa_id=f.empresa_id
								and r.centro_utilidad=e.centro_utilidad and
								r.unidad_funcional=f.unidad_funcional) order by razon_social,cu,uf,dpto";
				$result = $dbconn->Execute($query);

				if ($dbconn->ErrorNo() != 0) {
					$this->error = "Error al traer el listado de todo";
					$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
					return false;
				}
				$i=0;

				while (!$result->EOF)
				{
					$var[$i]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
					$i++;
				}

				return $var;
		}


}//fin clase user

?>


