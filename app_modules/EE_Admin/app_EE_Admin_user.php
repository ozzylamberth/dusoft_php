<?php

/**
* $Id: app_EE_Admin_user.php,v 1.3 2006/01/30 16:08:05 tizziano Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  Alexander Giraldo -- alexgiraldo@ipsoft-sa.com
* @package IPSOFT-SIIS
*/

class app_EE_Admin_user extends classModulo
{

     /**
     * Metodo Inicial - realiza la seleccion de la estacion a trabajar
     *
     * @return boolean
     */
     function main()
     {
          unset($_SESSION['EE_Admin'][UserGetUID()]);
          $this->FrmLogueoEstacion();
          return true;
     }
     
     
     /**
     * Metodo valida la estacion que selecciono el usuario, lo loguea y lo manda al listado de la estacion.
     *
     * @return boolean
     */
     function InicioSeleccionEstacion()
     {
          unset($_SESSION['EE_Admin'][UserGetUID()]);
     
          if(empty($_REQUEST['estacion_id']))
          {
               $this->FrmLogueoEstacion();
               return true;
          }
     
          if($datosEE = $this->GetUserPermisos($_REQUEST['estacion_id']))
          {
               $_SESSION['EE_Admin'][UserGetUID()] = $datosEE;
               $this->FrmUsuariosEstacion();
               return true;
          }
          $this->FrmLogueoEstacion();
          return true;
     }
     
     
     /**
     * Metodo para obtener los userpermisos de un usuario para el modulo
     *
     * @param string $estacion_id opcional valida si el usuario tiene permiso en una estacion
     * @return boolean
     */
     function GetUsuariosEstacion($estacion_id,$paso,$limit=null,$numReg=null,$filtro)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          if($numReg===null)
          {
               $query="SELECT COUNT(*) FROM estaciones_enfermeria_usuarios AS A, system_usuarios AS C
               		     WHERE A.estacion_id='$estacion_id'
                              AND A.usuario_id = C.usuario_id
                              $filtro";
               $numReg = $dbconn->GetOne($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "EE_Admin - GetUsuariosEstacion - SQL ERROR 1";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
               }
          }
          if($numReg===0)
          {
               return 0;
          }
     
          if(empty($limit))
          {
               $limit = GetLimitBrowser();
          }
     
          $offSet = ($paso -1) * $limit;
     
          $query="SELECT A.*, B.descripcion, 
                         C.usuario_id, C.usuario, C.nombre, 
                         C.activo
                    FROM estaciones_enfermeria_usuarios AS A
                         LEFT JOIN estaciones_enfermeria_perfiles AS B ON(A.estacion_perfil_id=B.estacion_perfil_id),
                         system_usuarios AS C
                    WHERE estacion_id='$estacion_id'
                    AND A.usuario_id=C.usuario_id
                    $filtro
                    ORDER BY C.nombre
                    LIMIT $limit
                    OFFSET $offSet";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - GetUsuariosEstacion - SQL ERROR 2";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $filas = $resultado->GetRows();
          $resultado->Close();
     
          $retorno[0] = &$filas;
          $retorno[1] = $numReg;
     
          return $retorno;
     }
     
     /**
     * Metodo para obtener los userpermisos de un usuario para el modulo
     *
     * @param string $estacion_id opcional valida si el usuario tiene permiso en una estacion
     * @return boolean
     */
     function GetUsuariosSistema($estacion_id,$paso,$limit=null,$numReg=null,$filtro)
     {
          list($dbconn) = GetDBconn();
          global $ADODB_FETCH_MODE;

          if($numReg===null)
          {
               $query="SELECT count(*) 
	                  FROM system_usuarios AS C, profesionales AS P 
		       	   JOIN tipos_profesionales AS D ON(D.tipo_profesional=P.tipo_profesional) 
		       	   WHERE C.usuario_id = P.usuario_id 
		       	   $filtro;";
               $numReg = $dbconn->GetOne($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "EE_Admin - GetUsuariosEstacion - SQL ERROR 1";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
               }
          }
          if($numReg===0)
          {
               return 0;
          }
     
          if(empty($limit))
          {
               $limit = GetLimitBrowser();
          }
     
          $offSet = ($paso -1) * $limit;
     
          $query="SELECT DISTINCT C.usuario_id, C.usuario, C.nombre, C.activo, 
                         D.descripcion AS tipo_profesional 
                  FROM system_usuarios AS C, profesionales AS P 
                  JOIN tipos_profesionales AS D ON(D.tipo_profesional=P.tipo_profesional) 
                  WHERE C.usuario_id = P.usuario_id
                  $filtro 
                  ORDER BY C.nombre
                  LIMIT $limit
                  OFFSET $offSet";
     
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - GetUsuariosEstacion - SQL ERROR 2";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $filas = $resultado->GetRows();
          $resultado->Close();
     
          $retorno[0] = &$filas;
          $retorno[1] = $numReg;
     
          return $retorno;
     }
     
     /**
     * Metodo para obtener los userpermisos de un usuario para el modulo
     *
     * @param string $estacion_id opcional valida si el usuario tiene permiso en una estacion
     * @return boolean
     */
     function GetUserPermisos($estacion_id='')
     {
          if(!UserGetUID()) return false;
          if(empty($_SESSION['USERPERMISOS']['EE_Admin'][UserGetUID()]))
          {
               list($dbconn) = GetDBconn();
               global $ADODB_FETCH_MODE;
     
               $query="SELECT
                         f.razon_social as empresa_descripcion,
                         e.descripcion as centro_utilidad_descripcion,
                         d.descripcion as unidad_funcional_descripcion,
                         c.descripcion as departamento_descripcion,
                         b.descripcion as estacion_descripcion,
                         b.titulo_atencion_pacientes,
                         c.empresa_id,
                         c.centro_utilidad,
                         c.unidad_funcional,
                         c.departamento,
                         a.estacion_id,
                         b.hc_modulo_medico,
                         b.hc_modulo_enfermera,
                         b.hc_modulo_consulta_urgencias
                         FROM
                         userpermisos_ee_admin a,
                         estaciones_enfermeria b,
                         departamentos c,
                         unidades_funcionales d,
                         centros_utilidad e,
                         empresas f
     
                         WHERE   a.usuario_id = ".UserGetUID()."
                         AND b.estacion_id = a.estacion_id
                         AND c.departamento = b.departamento
                         AND d.unidad_funcional = c.unidad_funcional
                         AND d.centro_utilidad = c.centro_utilidad
                         AND d.empresa_id = c.empresa_id
                         AND e.centro_utilidad = c.centro_utilidad
                         AND e.empresa_id = c.empresa_id
                         AND f.empresa_id = c.empresa_id
     
                         ORDER BY c.empresa_id, c.centro_utilidad, c.unidad_funcional, c.departamento, a.estacion_id";
     
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
     
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "EE_Admin - SQL ERROR 1";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
               }
     
               if(!$resultado->EOF)
               {
                    while($fila = $resultado->FetchRow())
                    {
                         $_SESSION['USERPERMISOS']['EE_Admin'][UserGetUID()][$fila['estacion_id']]=$fila;
                    }
                    $resultado->Close();
               }
               else
               {
                    return false;
               }
          }
          if($estacion_id)
          {
               if($_SESSION['USERPERMISOS']['EE_Admin'][UserGetUID()][$estacion_id])
               {
                    return $_SESSION['USERPERMISOS']['EE_Admin'][UserGetUID()][$estacion_id];
               }
               else
               {
                    return false;
               }
          }
          else
          {
               if($_SESSION['USERPERMISOS']['EE_Admin'][UserGetUID()])
               {
                    return $_SESSION['USERPERMISOS']['EE_Admin'][UserGetUID()];
               }
               else
               {
                    return false;
               }
          }
     }
     
     /**
     * Metodo para cambiar el estado de activacion de la cuenta de un usuario, con permisos en la estacion.
     *
     * @param string $usuario_id
     * @return boolean
     */
     function DesactivaProfesional()
     {
          list($dbconn) = GetDBconn();
          if($_REQUEST['accion_bloqueo'] == 1)
          { $accion = 0;}else{ $accion = 1;}
          $query = "UPDATE system_usuarios SET activo = '$accion'
          		WHERE usuario_id=".$_REQUEST['usuario'].";";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $this->FrmUsuariosEstacion();
		return true;     
     }
     
     /**
     * Metodo de consulta de todos y cada uno de los componentes a los q tiene
     * derecho un usuario de la estacion.
     *
     * @param string $usuario_id, $perfil_estacion
     * @return boolean
     */
     function GetPerfil_ComponenteEstacion($perfil,$usuario,$estacion_id)
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          $query = "SELECT A.estacion_componente_id as componente, A.descripcion AS grupo_componente,B.estacion_componente_id,C.descripcion AS descripcion_componente,
                         CASE WHEN B.estacion_componente_id IS NULL THEN 0 ELSE 1 END AS restar
                    
                    FROM estaciones_enfermeria_componentes AS A 
                    LEFT JOIN
                    (
                         (
                              SELECT A.estacion_componente_id
                              FROM estaciones_enfermeria_perfiles_componentes AS A 
                                   LEFT JOIN estaciones_enfermeria_usuarios_componentes B 
                                   ON(A.estacion_componente_id = B.estacion_componente_id AND B.usuario_id = ".$usuario." AND B.estacion_id = '".$estacion_id."' AND B.sw_permiso='0')
                              WHERE A.estacion_perfil_id = '".$perfil."' AND  B.estacion_componente_id IS NULL
                         )
                         UNION 
                         (
                              SELECT A.estacion_componente_id 
                              FROM estaciones_enfermeria_usuarios_componentes A
                              WHERE A.usuario_id = ".$usuario."
                              AND A.estacion_id = '".$estacion_id."' AND A.sw_permiso='1'
                         )
                    ) AS B ON (A.estacion_componente_id=B.estacion_componente_id),
                    estaciones_enfermeria_grupos_componentes AS C
                    
                    WHERE
                    C.estacion_grupo_componente_id = A.estacion_grupo_componente_id
                    
                    ORDER BY C.estacion_grupo_componente_id ASC;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          while($data = $resultado->FetchRow())
          {
          	$componentes[$data['descripcion_componente']][] = $data;
          }
		return $componentes;     
     }
     
     /**
     * Metodo que permite eliminar un componente de la lista de perfiles del Usuario.
     * Para esto, se realiza una insercion en la tabla respectiva.
     *
     * @param $estacion_id, $usuario, $componente.
     * @return vect
     */
     function Eliminar_ComponenteUsuario()
     {
          $estacion_id = $_REQUEST['estacion_id'];
          $usuario = $_REQUEST['usuario'];
          $componente = $_REQUEST['componente'];
          list($dbconn) = GetDBconn();
          $query_busqueda = "SELECT count(estacion_componente_id)
                              FROM estaciones_enfermeria_usuarios_componentes
                              WHERE estacion_componente_id = '".$componente."'
                              AND usuario_id = ".$usuario."
                              AND estacion_id = '".$estacion_id."';";
          $resultado = $dbconn->Execute($query_busqueda);
          if($resultado->fields[0] >= 1)
          {
               $query = "UPDATE estaciones_enfermeria_usuarios_componentes 
               		SET  sw_permiso = '0'
                         WHERE estacion_componente_id = '".$componente."'
                         AND usuario_id = ".$usuario."
                         AND estacion_id = '".$estacion_id."';";
          }
          else
          {
               $query = "INSERT INTO estaciones_enfermeria_usuarios_componentes 
                                                      (estacion_id,
                                                       usuario_id,
                                                       estacion_componente_id,
                                                       sw_permiso)
                                           VALUES	('".$estacion_id."',
                                                       ".$usuario.",
                                                       '".$componente."',
                                                       '0');";
          }
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $this->FrmFormaPermisos();
          return true;
     }
             
     
     /**
     * Metodo que permite eliminar un componente de la lista de perfiles del Usuario.
     * Para esto, se realiza una insercion en la tabla respectiva.
     *
     * @param $estacion_id, $usuario, $componente.
     * @return vect
     */
     function Adicionar_ComponenteUsuario()
     {
          $estacion_id = $_REQUEST['estacion_id'];
          $usuario = $_REQUEST['usuario'];
          $componente = $_REQUEST['componente'];
          $perfil = $_REQUEST['perfil'];
          
          list($dbconn) = GetDBconn();
          $query_busqueda = "SELECT count(estacion_componente_id)
                              FROM estaciones_enfermeria_usuarios_componentes
                              WHERE estacion_componente_id = '".$componente."'
                              AND usuario_id = ".$usuario."
                              AND estacion_id = '".$estacion_id."'
                              AND sw_permiso = '0';";
          $resultado = $dbconn->Execute($query_busqueda);
          if($resultado->fields[0] >= 1)
          {
               $query = "DELETE FROM estaciones_enfermeria_usuarios_componentes
                         WHERE estacion_id = '".$estacion_id."'
                         AND usuario_id = ".$usuario."
                         AND estacion_componente_id = '".$componente."';";
               $resultado = $dbconn->Execute($query);
               if ($dbconn->ErrorNo() != 0) {
                    $this->error = "EE_Admin - SQL ERROR 1";
                    $this->mensajeDeError = $dbconn->ErrorMsg();
                    return false;
               }
          }
          
          $query_busqueda2 = "SELECT count(estacion_componente_id)
                              FROM estaciones_enfermeria_usuarios_componentes
                              WHERE estacion_componente_id = '".$componente."'
                              AND usuario_id = ".$usuario."
                              AND estacion_id = '".$estacion_id."';";
          $resultado = $dbconn->Execute($query_busqueda2);
          if($resultado->fields[0] < 1)
          {
	          $query = "INSERT INTO estaciones_enfermeria_usuarios_componentes 
                                                       (estacion_id,
                                                        usuario_id,
                                                        estacion_componente_id,
                                                        sw_permiso)
                                             VALUES	('".$estacion_id."',
                                                        ".$usuario.",
                                                        '".$componente."',
                                                        '1');";
          }
          else
          {
               $query = "UPDATE estaciones_enfermeria_usuarios_componentes 
               		SET  sw_permiso = '1'
                         WHERE estacion_componente_id = '".$componente."'
                         AND usuario_id = ".$usuario."
                         AND estacion_id = '".$estacion_id."';";
          }
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $this->FrmFormaPermisos();
          return true;
     }
     
     /**
     * Metodo de consulta de todos y cada uno de los perfiles q tiene
     * asignado el paciente de la estacion.
     *
     * @param ''
     * @return vect
     */
     function GetPerfiles()
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          $query = "SELECT *
          		FROM estaciones_enfermeria_perfiles 
				ORDER BY estacion_perfil_id ASC;";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          while($data = $resultado->FetchRow())
          {
          	$perfiles[] = $data;
          }
		return $perfiles;
     }
     
     /**
     * Metodo de consulta del perfil del profesional de la estacion.
     *
     * @param $usuario_id, $estacion_id
     * @return vect
     */
     function GetPerfiles_Usuarios($usuario,$estacion_id)
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          $query = "SELECT *
          		FROM estaciones_enfermeria_usuarios
                    WHERE usuario_id = ".$usuario."
                    AND estacion_id = '".$estacion_id."';";

          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $resultado = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;

          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          
          $perfiles_US = $resultado->FetchRow();
		return $perfiles_US;
     }
     
     /**
     * Metodo de Actualizacion del perfil al profesional de la estacion.
     *
     * @param $usuario_id, $perfil_id, $estacion_id
     * @return vect
     */
     function ActualizarPerfil()
     {
          $estacion_id = $_REQUEST['estacion_id'];
          $usuario = $_REQUEST['usuario'];
          $perfil_id = $_REQUEST['perfil_id'];
          list($dbconn) = GetDBconn();

          $query = "UPDATE estaciones_enfermeria_usuarios 
          		SET estacion_perfil_id = '".$perfil_id."'
                    WHERE usuario_id = ".$usuario."
                    AND estacion_id = '".$estacion_id."';";

          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          $this->FrmFormaPermisos($estacion_id,$usuario);
		return true; 
     }
     
     /**
     * Metodo que permite eliminar un componente de la lista de perfiles del Usuario.
     * Para esto, se realiza una insercion en la tabla respectiva.
     *
     * @param $estacion_id, $usuario, $componente.
     * @return vect
     */
     function InsertarUSenEE()
     {
          $estacion_id = $_SESSION['EE_Admin'][UserGetUID()]['estacion_id'];
          $usuario = $_REQUEST['usuario'];

          list($dbconn) = GetDBconn();
          $query_busqueda = "SELECT count(usuario_id)
                              FROM estaciones_enfermeria_usuarios
                              WHERE usuario_id = ".$usuario."
                              AND estacion_id = '".$estacion_id."';";
          $resultado = $dbconn->Execute($query_busqueda);
          if($resultado->fields[0] >= 1)
          {
               unset($_REQUEST['accion_BUSCAR']);
               $this->FrmUsuariosEstacion();
          }
          else
          {
               $query = "INSERT INTO estaciones_enfermeria_usuarios
                                                      (estacion_id,
                                                       usuario_id,
                                                       estacion_perfil_id)
                                           VALUES	('".$estacion_id."',
                                                   ".$usuario.",
                                                   NULL);";
          }
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0) {
               $this->error = "EE_Admin - SQL ERROR 1";
               $this->mensajeDeError = $dbconn->ErrorMsg();
               return false;
          }
          unset($_REQUEST['accion_BUSCAR']);
          $this->FrmUsuariosEstacion();
          return true;
     }
     
     /*$num es el numero de opcion que escogio en el combo*/
	/*$busca es la busqueda*/
	function GetFiltroUsuarios($num,$busca)
	{
          switch($num)
          {
               case "1":
               {
                    if(is_numeric($busca))
                    {
                         $filtro="AND C.usuario_id=".trim($busca)."";
                    }
                    else
                    {
                         $filtro="";
                    }
                    $_SESSION['CENTRAL']['negrilla']=1;
                    break;
               }
               case "2":
               {
                    $filtro="AND lower(C.usuario) like '%".strtolower(trim($busca))."%'";
                    $_SESSION['CENTRAL']['negrilla']=2;
                    break;
               }
               case "3":
               {
                    $filtro="AND lower(C.nombre) like '%".strtolower(trim($busca))."%'";
                    $_SESSION['CENTRAL']['negrilla']=3;
                    break;
               }
          }
          return $filtro;
	}


}//end of class

?>
