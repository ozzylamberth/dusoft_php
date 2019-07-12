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

class system_User_modulo_dpto_admin extends classModulo
{
		var $limit;
		var $conteo;

	function system_User_modulo_dpto_admin()
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
	    //borrado de var modulo
			unset($_SESSION['USER_ADMIN_MOD']['MODULO']);
			//borrado de var centro_utilidad
			unset($_SESSION['USER_ADMIN_MOD']['CENTRO']);
			//borrado de var departamento
			unset($_SESSION['USER_ADMIN_MOD']['DPTO']);
			//borrado de var empresa
			unset($_SESSION['USER_ADMIN_MOD']['EMPRESA']);
			//borrado de var sw_empresa-->switche para ver si es multiempresa o no.
			unset($_SESSION['USER_ADMIN_MOD']['SW_EMPRESA']);
			//borrado de la variable de session nombre, del dpto,empresa ETC..
			unset($_SESSION['USER_ADMIN_MOD']['NOMBRE']);
      //borrado del tipo de tabla_userpermisos estoy usando en el momento.
			unset($_SESSION['USER_ADMIN_MOD']['TIPO_MENU']);

			//este metodo es para la administracion del  menu general........
				list($dbconn) = GetDBconn();

				$query = "select b.modulo,b.modulo_tipo,b.descripcion
									from system_user_admin_modulos a,system_modulos b
									where a.modulo_tipo=b.modulo_tipo and a.modulo=b.modulo
									and a.usuario_id=".UserGetUID()."
									and b.sw_admin='1' order by modulo ";
				$result = $dbconn->Execute($query);
				$i=0;
				if($result->EOF)
				{
								$this->error = "ACCESO DENEGADO";
								$this->mensajeDeError = "El Usuario No posee Departamentos";
								return False;

				}
        if($result->RecordCount() >1)
				{

						while(!$result->EOF)
									{
											$var[$i]=$result->GetRowAssoc($ToUpper = false);
											$i++;
											$result->MoveNext();
									}
						$this->MenuModulos($var);
						return true;
				}
				else
				{
							$var[0]=$result->GetRowAssoc($ToUpper = false);
							$_SESSION['USER_ADMIN_MOD']['MODULO']=$var[0][modulo];
              if(strtoupper($_REQUEST['mod'])!='CONTRATACION'
								AND strtoupper($_REQUEST['mod'])!='SOAT'
								AND strtoupper($_REQUEST['mod'])!='CENSO'	)
							{
								$this->MenuDpto();
							}
							else
							{
								$this->MenuEmpresa();
							}
						return true;//aqui se coloca lamotra funcion a la cua sera los departamentos
				}//
  }




/*Esta funcion decide a que forma visualiza, si los modulos son comunes a MenuDpto
* o si son especiales a MenuEmpresa.
*/
function Decision()
{
 if(strtoupper($_REQUEST['mod'])!='CONTRATACION'

	AND strtoupper($_REQUEST['mod'])!='SOAT'

	AND strtoupper($_REQUEST['mod'])!='CENSO'	)//ojo CONTRATACION--PRUEBA CAJAGENERAL
	{
		$this->MenuDpto($_REQUEST['mod']);
	}
	else
	{
		$_SESSION['USER_ADMIN_MOD']['MODULO']=$_REQUEST['mod'];
		$this->MenuEmpresa();
	}

	return true;
}



/*Borra los usuarios de manera estandar en FormaMostrarElementos
* esto con el fin de eliminar usuarios del los MODULOS X.
*/
function BorrarUsuarios()
{
	list($dbconn) = GetDBconn();
	$query="DELETE FROM ".$_REQUEST['tabla']." WHERE  ".$_REQUEST['campo']."='".$_REQUEST['id']."'
	AND usuario_id='".$_REQUEST['usuario']."'";
	$resulta=$dbconn->execute($query);
	if ($dbconn->ErrorNo() != 0) {
							$this->frmError["MensajeError"]= "EL USUARIO TIENE REGISTROS CARGADOS";
							$this->MostrarElementos($_REQUEST['tabla'],$_REQUEST['campo_vect']);
							return false;
	}

	$this->frmError["MensajeError"]="DATOS BORRADOS SATISFACTORIAMENTE";
	$this->MostrarElementos($_REQUEST['tabla'],$_REQUEST['campo_vect']);
	return true;
}





/*esta funcion trae los datos de la base de datos segun la tabla y los campos de manera
* estandar,la variable $tabla,trae el nombre de la tabla, y $datos trae el arreglo de
* los campos que tienen relacion para poder sacar la tabla con los usuarios.
*/
	function MostrarElementos($tabla,$datos)
	{
 	      if(empty($tabla))
				{
					$tabla=$_REQUEST['tabla'];
					$campo_vect=$campos=$_REQUEST['vectorcampos'];
				}
				else
				{
					$campo_vect=$campos=$datos;
				}
				list($dbconn) = GetDBconn();

				//esta variable de session se activa si se ha entrado al caso4.
				if(!empty($_SESSION['ADMINISTRADOR']['VAR']))
				{
					$a=4; //le colocamos valor 4 a esta variable para no confundir
					//el arreglo $campo[0] que se repite varias veces en el join.
					$campos[$a]=$_SESSION['ADMINISTRADOR']['VAR'];
				}
				else
				{
					$a=0;  //si no se ha entrado al caso4, quiere decir que el arreglo
					$campos[$a]; //$campos[] es normal entonces le daremos el valor de '0'.
				}

				$valores=$this->RelacionesTabla($tabla); //esta funcion saca las relaciones.
				GLOBAL $ADODB_FETCH_MODE;


				//Si esta variable de session tiene datos implica que el modulo que se ha escogido es
				// un modulo especial, y que no tiene que ver nada con filtrado de departamentos
				//si no con empresas, asi que hay que filtrarlo como tal
				if(!empty($_SESSION['USER_ADMIN_MOD']['EMPRESA']))
				{
							$query=" select DISTINCT d.".$campos[1]." as puntero,d.usuario as desc,d.nombre as desc1,
										b.".$valores[0][desc]." as descripcion,b.".$campos[0]." from system_usuarios d, system_usuarios_empresas as c,
										".$tabla." as a ,  ".$valores[0][relacion]." as b
										WHERE d.sw_admin=0 AND d.usuario_id <> 0
										AND a.".$campos[1]." = d.".$campos[1]."
										AND a.empresa_id='".$_SESSION['USER_ADMIN_MOD']['EMPRESA']."'
										AND 	a.".$campos[$a]." =b.".$campos[0]."
										AND c.".$campos[1]."=".UserGetUID()."
										order by b.".$valores[0][desc]."";
				}
				else
				{



				     	$query=" select a.".$campos[1]." as puntero,a.usuario as desc,a.nombre as desc1,
										o.".$valores[0][desc].",d.".$campos[0]." from system_usuarios a, system_usuarios_departamentos as e,
										".$tabla." as d ,  ".$valores[0][relacion]." as o  WHERE a.sw_admin =0 AND a.usuario_id <> 0
										AND a.".$campos[1]." = e.".$campos[1]." and e.departamento='".$_SESSION['USER_ADMIN_MOD']['DPTO']."' ".$_SESSION['USER_ADMIN_MOD']['SW_OS']."
										AND   o.".$campos[$a]."=d.".$campos[0]." AND 	d.".$campos[1]." =e.".$campos[1]."  order BY o.".$valores[0][desc]."";
				}

				
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$resulta=$dbconn->execute($query);
				$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al buscar en la tabla $tabla";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}


						//si no trae nada entonces que retorne vacio a la funcion
						if($resulta->RecordCount() < 1)
						{
										$this->FormaMostrarElementos($campos[0],$tabla,$valores[0][relacion],$var,$tipo,$usuario,$campo_vect);
 										return true;
						}

				while ($data = $resulta->FetchRow()) {
					$tipo[$data['descripcion']]+= 1;
				//	$login[$data['descripcion']][$data['desc']] += 1;
					$usuario[$data['descripcion']][$data['desc']][$data['desc1']] = 1;
				}

				$resulta=$dbconn->execute($query);
				while(!$resulta->EOF)
				{
						$var[]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
				$this->FormaMostrarElementos($campos[0],$tabla,$valores[0][relacion],$var,$tipo,$usuario,$campo_vect);
 				return true;
	}



/*funcion que saca el departamento y centro utilidad según el usuario de sistema*/
function TraerDpto()
{
			list($dbconn) = GetDBconn();
			$query="select b.departamento,b.centro_utilidad,
							b.descripcion
							from system_usuarios_departamentos a,departamentos b
							where a.departamento=b.departamento
							and a.usuario_id=".UserGetUID()." order by b.descripcion ";
							$resulta=$dbconn->execute($query);

				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Buscar el departamento";
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


/*funcion que saca la empresa según el usuario de sistema*/
function TraerEmpresa()
{
			list($dbconn) = GetDBconn();
			$query="select b.empresa_id,b.id,b.website,b.sw_usuarios_multiempresa,
							b.razon_social
							from system_usuarios_empresas a,empresas b
							where a.empresa_id=b.empresa_id
							and a.usuario_id=".UserGetUID()." order by b.razon_social";
							$resulta=$dbconn->execute($query);

				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Buscar la empresa";
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


/*funcion que saca el centro de utilidad según el usuario de sistema*/
function TraerCentro()
{
			list($dbconn) = GetDBconn();
			$query="select descripcion,centro_utilidad,empresa_id  from centros_utilidad
							 WHERE empresa_id='".$_SESSION['USER_ADMIN_MOD']['EMPRESA']."'
							 order by descripcion";
							$resulta=$dbconn->execute($query);

				if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al Buscar la empresa";
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



/*funcion que nos manda al modulo el cual se ha escogido pero si se termina en
	* centro de utilidad
. */
function DatosRetornoCentro()
{
		$_SESSION['USER_ADMIN_MOD']['CENTRO']=$_REQUEST['cu'];
		//estas variables de session se crean para que el modulo que las reciba,
		//pueda volver a esta pantalla.
		$_SESSION['USER_ADMIN_MOD']['CONTENEDOR']='system';
		$_SESSION['USER_ADMIN_MOD']['MOD']='User_modulo_dpto';
		$_SESSION['USER_ADMIN_MOD']['TIPO']='admin';
		$_SESSION['USER_ADMIN_MOD']['METODO']='MenuCentro';
		$this->ReturnMetodoExterno('app', $_SESSION['USER_ADMIN_MOD']['MODULO'], 'admin', 'main');
		return true;
}



/*funcion que nos manda al modulo el cual se ha escogido. */
function DatosRetorno()
{
		if($_REQUEST['espia'])
		{
			$_SESSION['USER_ADMIN_MOD']['EMPRESA']=$_REQUEST['emp'];
			$_SESSION['USER_ADMIN_MOD']['SW_EMPRESA']=$_REQUEST['sw_e'];
			$_SESSION['USER_ADMIN_MOD']['NOMBRE']=$_REQUEST['desc'];//mandamos el nombre de la emp.
			$metodo='MenuEmpresa';
		}
		else
		{
  		$_SESSION['USER_ADMIN_MOD']['DPTO']=$_REQUEST['dpto'];
			$metodo='MenuDpto';
			$_SESSION['USER_ADMIN_MOD']['NOMBRE']=$_REQUEST['desc']; //mandamos el nombre del dpto.
		}
		//estas variables de session se crean para que el modulo que las reciba,
		//pueda volver a esta pantalla.
		$_SESSION['USER_ADMIN_MOD']['CONTENEDOR']='system';
		$_SESSION['USER_ADMIN_MOD']['MOD']='User_modulo_dpto';
		$_SESSION['USER_ADMIN_MOD']['TIPO']='admin';
		$_SESSION['USER_ADMIN_MOD']['METODO']=$metodo;

		$_SESSION['USER_ADMIN_MOD']['MODULO'];
		$this->ReturnMetodoExterno('app', $_SESSION['USER_ADMIN_MOD']['MODULO'], 'admin', 'main');
		return true;
}



/*Funcion que nos trae las relaciones segun el nombre de la tabla*/
function RelacionesTabla($tabla)
{
	list($dbconn) = GetDBconn();
	GLOBAL $ADODB_FETCH_MODE;
	$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	$query="SELECT campos[1] as campo,relacion[1] as relacion,descripcion[1] as desc FROM userpermisos_admin
	WHERE tabla= '".$tabla."'";
	$resulta=$dbconn->execute($query);
	if ($dbconn->ErrorNo() != 0) {
							$this->error = "Error al buscar en la tabla";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
						}
		$i=0;
		while(!$resulta->EOF)
				{
						$var[$i]=$resulta->GetRowAssoc($ToUpper = false);
						$resulta->MoveNext();
				}
	$i++;
	return $var;
}


/*esta funcion trae el nombre del centro de utilidad, o la empresa,segun el caso
* esta en modo experimental.
*/
function TraerNombre($id)
{
	list($dbconn) = GetDBconn();
	$query="SELECT razon_social FROM empresas where empresa_id='$id'";
	$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error al en la tabla empresa";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
		}
		return $result->fields[0];
}





/*esta funcion inserta en las tablas de segundo nivel,osea q solamente las q
 * poseen dos tablas.
*/
function InsertarP()
{

		//parte de la insercion del programa esto para preguntar.............
		list($dbconn) = GetDBconn();
		$campos=$_REQUEST['vectorcampos'];
		$tabla=$_REQUEST['tabla'];
		$i=0;
	//print_r($_REQUEST);
		if(sizeof($_REQUEST['sel1'])<1 || sizeof($_REQUEST['sel2'])<1)
						{
							$this->frmError["MensajeError"]='DEBE ESCOGER ALMENOS 1 OPCION DE CADA UNA DE LAS MATRICES';
							$this->TraerDatos($tabla);
							return true;
						}


foreach($_REQUEST['sel1'] as $index=>$sel1)
{
		foreach($_REQUEST['sel2'] as $index2=>$sel2)
		{


						$query="SELECT COUNT(*) FROM ".$_REQUEST['tabla']." where
						".$campos[0]."='$sel1' and   ".$campos[1]."='".$sel2."'";
						$resulta=$dbconn->Execute($query);


				if($resulta->fields[0]<1)
				{


							$query="INSERT INTO ".$_REQUEST['tabla']."
										( ".$campos[0].", ".$campos[1].")
										VALUES
										('".$sel1."','".$sel2."')";
							$resulta=$dbconn->Execute($query);
							if ($dbconn->ErrorNo() != 0){
							$this->error = "Error al insertar en ".$tabla."";
							$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
							return false;
							}
				}
				else
				{
					$repetido[$i]="(".$sel1."/".$sel2.")";
					$i++;
				}

	  }
}

						if(!empty($repetido))
						{
									$salida.= "NO SE INSERTARON LOS REGISTROS&nbsp";
							foreach($repetido as $index=>$repe)
							{
								 $salida.="".$repe."&nbsp;";
							}
							$salida.="&nbsp 	YA EXISTEN " ;
							$this->frmError["MensajeError"]=$salida;
							$this->TraerDatos($tabla);
							return true;
						}
						else
						{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE";
							$this->TraerDatos($tabla);
							return true;
						}
	return true;
}



/*Esta funcion vuelve al modulo al cual inicialmente la llamo*/
function Retornar_A_Modulo()
{
	$this->ReturnMetodoExterno('app', $_SESSION['USER_ADMIN_MOD']['MODULO'], 'admin', 'main');
	return true;
}



//esta funcion se llama asi por q es nivel 3 osea  q la matriz es contra 3 tablas.
function InsertarPnivel3()
{

						//parte de la insercion del programa esto para preguntar.............
								list($dbconn) = GetDBconn();
								$campos=$_REQUEST['vectorcampos'];
								$tabla=$_REQUEST['tabla'];
								$i=0;

						if(sizeof($_REQUEST['sel1'])<1 || sizeof($_REQUEST['sel3'])<1
								|| sizeof($_REQUEST['sel2'])<1)
						{
							$this->frmError["MensajeError"]='DEBE ESCOGER ALMENOS 1 OPCION DE CADA UNA DE LAS MATRICES';
							$this->TraerDatos($tabla);
							return true;
						}

						foreach($_REQUEST['sel1'] as $index=>$sel1)
						{
							foreach($_REQUEST['sel3'] as $index3=>$sel3)
							{

									foreach($_REQUEST['sel2'] as $index2=>$sel2)
									{


													$query="SELECT COUNT(*) FROM ".$_REQUEST['tabla']." where
												".$campos[0]."='$sel1'  AND
												".$campos[2]."='$sel3' AND  ".$campos[1]."=".$sel2."";
												$resulta=$dbconn->Execute($query);


										if($resulta->fields[0]<1)
										{


													$query="INSERT INTO ".$_REQUEST['tabla']."
																( ".$campos[0].", ".$campos[1].",".$campos[2].")
																VALUES
																('".$sel1."',".$sel2.",'".$sel3."')";
													$resulta=$dbconn->Execute($query);
													if ($dbconn->ErrorNo() != 0){
													$this->error = "Error al insertar en ".$tabla."";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
													}
										}
										else
										{
												$repetido[$i]="(".$sel1."/".$sel3."/".$sel2.")";
											$i++;
										}

								}
							}
						}

						if(!empty($repetido))
						{
							$salida.= "NO SE INSERTARON LOS REGISTROS&nbsp";
							foreach($repetido as $index=>$repe)
							{
								 $salida.="".$repe."&nbsp;";
							}
							$salida.="&nbsp 	YA EXISTEN " ;
							$this->frmError["MensajeError"]=$salida;
							$this->TraerDatos($tabla);
							return true;
						}
						else
						{
							$this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE";
							$this->TraerDatos($tabla);
							return true;
						}
return true;
}




}//fin clase user

?>


