<?php

/**
* $Id: system_Usuarios_admin.php,v 1.8 2006/07/10 13:48:04 carlos Exp $
*MODULO Administrativo para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon - Jairo Duvan Diaz Martinez
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

class system_Usuarios_admin extends classModulo
{
        var $limit;
        var $conteo;

    function system_Usuarios_admin()
    {
        $this->limit=GetLimitBrowser();
    return true;
    }



/**
* Funcion donde se llama la funcion Menu
* @return boolean
*/

    function main(){
    unset($_SESSION['USER']['FECH']);
        unset($_SESSION['USER']['DIAS']);
    if(!$this->Menu()){
        return false;
   }

//$tabla='userpermisos_tipos_facturas'; //estos datos son de prueba
//$tabla='cajas_usuarios';
//$tabla='usuarios_maestro_inventarios';
//$tabla='userpermisos_mantenimiento_profesionales';
//$tabla='userpermisos_contratacion';
//$tabla='cuentas_filtros_usuarios';
//$this->InterfazAdmin($tabla);//esto es de prueba........
        return true;
  }

    /**
    * Retorna listado de temas (Carpetas en el path de temas de la aplicacion)
    *
    * @return array
    * @access public
    */
    function listarDirectorios()
    {
        global $VISTA;
        $themes=opendir("themes/$VISTA");
        $i=0;
        while ($file = readdir($themes))
        {
            if ($file != "." && $file != ".." && $file !='CVS' && $file !='Suspendido')
            {
                $archivos[$i]=$file;
                $i++;
            }
        }
        closedir($themes);
        return $archivos;
    }


/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/
function Usuario(){

    $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
        if(!$this->FormaInsertarUsuarioSistema('','','','','',$action,'','')){
         return false;
     }

        return true;
  }



/**
* Funcion donde se Modifica en la base de datos el estado(1=activo,0=inactivo) del usuarios en el sistema
* @return boolean
*/

    function ModificarEstadoUsuarioIp(){

        $ip=$_REQUEST['ip'];
    list($dbconn) = GetDBconn();
      $query = "SELECT sw_bloqueo FROM system_host WHERE ip='$ip'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{

      if($result->fields[0]=='1'){
          $query = "UPDATE system_host SET sw_bloqueo='0' WHERE ip='$ip'";
          $result = $dbconn->Execute($query);
          }else{
          $query = "UPDATE system_host SET sw_bloqueo='1' WHERE ip='$ip'";
          $result = $dbconn->Execute($query);
          }
        }
      if($_REQUEST['marca']==true)
            {
                $this->ListadoAccesos($_REQUEST['dats'],$ip,$_REQUEST['host']);
                return true;
            }
            else
            {
                $this->ListadoGeneralSistema();
                return true;
            }
  }


/**
* Funcion donde si tiene conexion
* @return boolean
*/

  function BuscarConexion($uid)
    {
            list($dbconn) = GetDBconn();

        $query = "SELECT count(*) from system_session where usuario_id=$uid;";
        $res=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al consultar en system_session";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
        $existencia=$res->fields[0];
            return $existencia;
        }
    }


    /**
* Funcion donde sacamos el numero de dias de caducidad de contraseña de un usuario.
* @return boolean
*/

  function TraerUserDias($uid)
    {
            list($dbconn) = GetDBconn();

        $query = "SELECT    fecha_caducidad_cuenta,caducidad_contrasena from system_usuarios where usuario_id=$uid;";
        $res=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al consultar en system_usuarios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
        $existencia[0]=$res->fields[0];
                $existencia[1]=$res->fields[1];
        return $existencia;
        }
    }




  function ListadoIps()
    {
                    list($dbconn) = GetDBconn();
                    $query="
                    select B.*, C.inicio_session
                    from
                    (
                    SELECT MAX(A.ultimo_acceso_session), A.usuario_id,
                    A.ip, A.hostname, A.usuario, A.nombre , A.sw_bloqueo
                    FROM
                    (
                        select  a.ip,a.hostname,a.sw_bloqueo,
                                    e.inicio_session,e.ultimo_acceso_session,
                                    e.usuario_id,c.usuario,c.nombre
                                    from system_host a
                                    left join system_session as e on(a.ip=e.ip_address)
                                    left join system_usuarios as c on( c.usuario_id=e.usuario_id)
                                    order by a.ip ,e.usuario_id,e.ultimo_acceso_session  desc
                    ) AS A
                    group by A.usuario_id, A.ip, A.hostname,  A.usuario, A.nombre, A.sw_bloqueo
                    order by A.ip
                    ) AS B,
                    (
                    select  a.ip,a.hostname,a.sw_bloqueo,
                                    e.inicio_session,e.ultimo_acceso_session,
                                    e.usuario_id,c.usuario,c.nombre
                                    from system_host a
                                    left join system_session as e on(a.ip=e.ip_address)
                                    left join system_usuarios as c on( c.usuario_id=e.usuario_id)
                                    order by a.ip ,e.usuario_id,e.ultimo_acceso_session  desc
                    ) as C
                    where (B.max = C.ultimo_acceso_session And
                    B.ip=C.ip) or (B.max is null And B.ip=C.ip)";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al listar las direcciones ip's";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
                    $i=0;

                    while (!$resulta->EOF)
                    {
                        $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                        //$datos[$var['ip']][$var['usuario']][sizeof($datos[$var['ip']][$var['usuario']])]=$var;
                        $resulta->MoveNext();
                        $i++;
                    }

                    return $var;
    }



function ComboEmpresa()
{
    list($dbconn) = GetDBconn();
        $query="select a.empresa_id,a.razon_social from empresas a ,
                        system_usuarios_administradores e
                        where e.usuario_id='".UserGetUID()."'
                        and e.empresa_id=a.empresa_id  order by a.razon_social asc";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al listar las empresas";
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

    function ComboDepartamentos($empresa){
        list($dbconn) = GetDBconn();
        $query="select a.departamento,a.descripcion
                        from departamentos a
                        where a.empresa_id='".$empresa."'
                        order by a.descripcion";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al listar las empresas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $i=0;
            while(!$resulta->EOF){
                $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
                $i++;
            }
        }
        return $var;
    }
//FUNCIONES CREADAS PARA EL PERFIL DEL PROFESIONAL
/**
* Funcion que retorna los tipo de documentos de la base de datos que puede tener el paciente
* @return array
*/
    function TiposPaciente(){
        list($dbconn) = GetDBconn();
        $query = "SELECT tipo_id_paciente,descripcion
        FROM tipos_id_pacientes
        ORDER BY indice_de_orden";
        $resulta = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            while(!$resulta->EOF){
                $var[]=$resulta->GetRowAssoc($ToUpper = false);
                $resulta->MoveNext();
            }
        }
        $resulta->Close();
        return $var;
    }

    /**
    * Busca el nombre del pais
    * @access public
    * @return array
    * @param int codigo del pais
    */
    function nombre_pais($Pais){
        list($dbconn) = GetDBconn();
        $query = "SELECT pais FROM tipo_pais WHERE tipo_pais_id='$Pais'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        else{

                if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                }
        }
        $result->Close();
        return $result->fields[0];
  }

    /**
    * Busca el nombre del departamento
    * @access public
    * @return array
    * @param int codigo del pais
  * @param int codigo del departamento
    */
    function nombre_dpto($Pais,$Dpto){
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM tipo_dptos WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        else{

                if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                }
        }
        $result->Close();
        return $result->fields[2];
  }

    /**
    * Busca el nombre de la ciudad o municipio
    * @access public
    * @return array
    * @param int codigo del pais
  * @param int codigo del departamento
    * @param int codigo del municipio
    */
    function nombre_ciudad($Pais,$Dpto,$Mpio){
        list($dbconn) = GetDBconn();
        $query = "SELECT * FROM tipo_mpios WHERE tipo_pais_id='$Pais' AND tipo_dpto_id='$Dpto' AND tipo_mpio_id='$Mpio'";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }
        else{

                if($result->EOF){
                        $this->error = "Error al Cargar el Modulo";
                        $this->mensajeDeError = "La tabla maestra 'tipo_pais' esta vacia ";
                        return false;
                }
        }
        $result->Close();
        return $result->fields[3];
  }

    /**
    * Busca los diferentes tipos de sexo utilizados en la aplicacion
    * @access public
    * @return array
    */
  function sexo(){
        list($dbconn) = GetDBconn();
        $result="";
        $query = "SELECT sexo_id,descripcion
                            FROM tipo_sexo WHERE sexo_id<>0
                            ORDER BY indice_de_orden";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }else{
            while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
        }
        $result->Close();
    return $vars;
  }

    /**
    * Busca los diferentes tipos de sexo utilizados en la aplicacion
    * @access public
    * @return array
    */
  function TiposProfesionales(){
        list($dbconn) = GetDBconn();
        $result="";
        $query = "SELECT tipo_profesional,descripcion
                            FROM tipos_profesionales
                            ORDER BY descripcion
                            ";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }else{
            while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
        }
        $result->Close();
    return $vars;
  }

    /**
    * Busca los diferentes tipos de sexo utilizados en la aplicacion
    * @access public
    * @return array
    */
  function Especialidades(){
        list($dbconn) = GetDBconn();
        $result="";
        $query = "SELECT especialidad,descripcion
                            FROM especialidades
                            ORDER BY descripcion
                            ";
        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }else{
            while (!$result->EOF) {
                    $vars[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
            }
        }
        $result->Close();
    return $vars;
  }

    function ValidacionInsertarProfesional(){
        if($_REQUEST['tipoDocumento']==-1 || !$_REQUEST['Documento'] || !$_REQUEST['pais'] || !$_REQUEST['dpto'] ||
            !$_REQUEST['mpio'] || !$_REQUEST['Direccion'] || $_REQUEST['especialidad']==-1 ||
            $_REQUEST['tipo_profesional']==-1 || $_REQUEST['Sexo']==-1){
            if($_REQUEST['tipoDocumento']==-1){echo '1';$this->frmError["tipoDocumento"]=1;}
            if(!$_REQUEST['Documento']){$this->frmError["Documento"]=1;}
            if(!$_REQUEST['pais']){$this->frmError["pais"]=1;}
            if(!$_REQUEST['dpto']){$this->frmError["dpto"]=1;}
            if(!$_REQUEST['mpio']){$this->frmError["mpio"]=1;}
            if(!$_REQUEST['Direccion']){$this->frmError["Direccion"]=1;}
            if($_REQUEST['tipo_profesional']==-1){$this->frmError["tipo_profesional"]=1;}
            if($_REQUEST['Sexo']==-1){$this->frmError["Sexo"]=1;}
            //if($_REQUEST['departamento']==-1){$this->frmError["departamento"]=1;}
            if($_REQUEST['especialidad']==-1){$this->frmError["especialidad"]=1;}

      $this->frmError["MensajeError"]="Faltan Datos Obligatorios.";
            $this->FormaInsertarProfesionalUsuarioSistema($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['usuario'],$_REQUEST['empresa'],$_REQUEST['descripcion'],$_REQUEST['modificacion']);
            return true;
        }
        list($dbconn) = GetDBconn();
        $query = "SELECT departamento
                            FROM profesionales_departamentos
                            WHERE tipo_id_tercero='".$_REQUEST['tipoDocumento']."' AND tercero_id='".$_REQUEST['Documento']."'";
        $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
        }else{
            while (!$result->EOF) {
                    $vars[]=$result->fields[0];
                    $result->MoveNext();
            }
        }
        $_REQUEST['Seleccion']=$vars;
        $this->FormaDptosProfesionalesUsuario($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['usuario'],$_REQUEST['empresa'],$_REQUEST['descripcion'],$_REQUEST['modificacion'],
        $_REQUEST['tipoDocumento'],$_REQUEST['Documento'],$_REQUEST['tipoDocumentoAnt'],$_REQUEST['DocumentoAnt'],$_REQUEST['especialidadAnt'],$_REQUEST['pais'],$_REQUEST['dpto'],
        $_REQUEST['mpio'],$_REQUEST['Direccion'],$_REQUEST['especialidad'],$_REQUEST['tipo_profesional'],$_REQUEST['Sexo'],
        $_REQUEST['telefono'],$_REQUEST['fax'],$_REQUEST['e_mail'],$_REQUEST['celular'],$_REQUEST['tarjetaProf'],$_REQUEST['universidad'],
        $_REQUEST['reg_salud'],$_REQUEST['observacion']);
        return true;
    }

    function InsertarProfesional(){
        $departamentos=$_REQUEST['Seleccion'];

        if(empty($departamentos)){
            $this->frmError["MensajeError"]="Seleccione por lo menos un departamento para el profesional";
            $this->FormaDptosProfesionalesUsuario($_REQUEST['uid'],$_REQUEST['nombre'],$_REQUEST['usuario'],$_REQUEST['empresa'],$_REQUEST['descripcion'],$_REQUEST['modificacion'],
            $_REQUEST['tipoDocumento'],$_REQUEST['Documento'],$_REQUEST['tipoDocumentoAnt'],$_REQUEST['DocumentoAnt'],$_REQUEST['especialidadAnt'],$_REQUEST['pais'],$_REQUEST['dpto'],
            $_REQUEST['mpio'],$_REQUEST['Direccion'],$_REQUEST['especialidad'],$_REQUEST['tipo_profesional'],$_REQUEST['Sexo'],
            $_REQUEST['telefono'],$_REQUEST['fax'],$_REQUEST['e_mail'],$_REQUEST['celular'],$_REQUEST['tarjetaProf'],$_REQUEST['universidad'],
            $_REQUEST['reg_salud'],$_REQUEST['observacion']);
            return true;
        }
        if(empty($_REQUEST['modificacion'])){
//      
        list($dbconn) = GetDBconn();
        $query = "SELECT count(*)
                      FROM terceros
                      WHERE tipo_id_tercero='".$_REQUEST['tipoDocumentoAnt']."' AND tercero_id='".$_REQUEST['DocumentoAnt']."';";
        $result = $dbconn->Execute($query);
            if($result->fields[0]>0)
            {  
             $query = "UPDATE terceros
                     SET tipo_id_tercero='".$_REQUEST['tipoDocumento']."',tercero_id='".$_REQUEST['Documento']."',tipo_pais_id='".$_REQUEST['pais']."',
                             tipo_dpto_id='".$_REQUEST['dpto']."',tipo_mpio_id='".$_REQUEST['mpio']."',direccion='".$_REQUEST['Direccion']."',
                             telefono='".$_REQUEST['telefono']."',fax='".$_REQUEST['fax']."',email='".$_REQUEST['e_mail']."',celular='".$_REQUEST['celular']."'
                             WHERE tipo_id_tercero='".$_REQUEST['tipoDocumentoAnt']."' AND tercero_id='".$_REQUEST['DocumentoAnt']."';";
            }
            else
            {
//                           
            $query = "INSERT INTO terceros(tipo_id_tercero,tercero_id,tipo_pais_id,tipo_dpto_id,
            tipo_mpio_id,direccion,telefono,fax,email,celular,sw_persona_juridica,cal_cli,
            usuario_id,fecha_registro,busca_persona,nombre_tercero)
            VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$_REQUEST['pais']."','".$_REQUEST['dpto']."',
            '".$_REQUEST['mpio']."','".$_REQUEST['Direccion']."','".$_REQUEST['telefono']."','".$_REQUEST['fax']."','".$_REQUEST['e_mail']."',
            '".$_REQUEST['celular']."','0','0','".UserGetUID()."','".date("Y-m-d H:i:s")."','','".$_REQUEST['nombre']."');";
            }
            
            $query.="INSERT INTO profesionales(tipo_id_tercero,tercero_id,nombre,tipo_profesional,tarjeta_profesional,
            estado,sexo_id,universidad,sw_registro_defuncion,fecha_registro,usuario_id,observacion,
            registro_salud_departamental)
            VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$_REQUEST['nombre']."','".$_REQUEST['tipo_profesional']."','".$_REQUEST['tarjetaProf']."',
            '1','".$_REQUEST['Sexo']."','".$_REQUEST['universidad']."','0','".date("Y-m-d H:i:s")."','".$_REQUEST['uid']."','".$_REQUEST['observacion']."',
            '".$_REQUEST['registro_salud_departamental']."');";

            //$query.="INSERT INTO profesionales_empresas(tipo_id_tercero,tercero_id,empresa_id,cuenta_debito,cuenta_credito)
            //VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$_REQUEST['empresa']."',NULL,NULL);";
           
           $query.="INSERT INTO profesionales_empresas(tipo_id_tercero,tercero_id,empresa_id)
            VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$_REQUEST['empresa']."');";
            
            if($departamentos){
                $query.="DELETE FROM profesionales_departamentos
                WHERE tipo_id_tercero='".$_REQUEST['tipoDocumento']."' AND tercero_id='".$_REQUEST['Documento']."';";

                $query.="DELETE FROM profesionales_estado
                WHERE tipo_id_tercero='".$_REQUEST['tipoDocumento']."' AND tercero_id='".$_REQUEST['Documento']."';";

                for($i=0;$i<sizeof($departamentos);$i++){
                    $query.="INSERT INTO profesionales_departamentos(departamento,tipo_id_tercero,tercero_id)VALUES('".$departamentos[$i]."',
                    '".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."');";

                    $query.="INSERT INTO profesionales_estado (tipo_id_tercero,tercero_id,departamento,estado,empresa_id)
                    VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$departamentos[$i]."','1','".$_REQUEST['empresa']."');";
                }
            }

            $query.="INSERT INTO profesionales_especialidades (tipo_id_tercero,tercero_id,especialidad,universidad,sub_especialidad)
            VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$_REQUEST['especialidad']."','".$_REQUEST['universidad']."','');";


            $query.="INSERT INTO profesionales_usuarios(usuario_id,tipo_tercero_id,tercero_id)
            VALUES('".$_REQUEST['uid']."','".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."');";
            $result = $dbconn->Execute($query);
        }else{
            $query = "UPDATE terceros
                     SET tipo_id_tercero='".$_REQUEST['tipoDocumento']."',tercero_id='".$_REQUEST['Documento']."',tipo_pais_id='".$_REQUEST['pais']."',
                             tipo_dpto_id='".$_REQUEST['dpto']."',tipo_mpio_id='".$_REQUEST['mpio']."',direccion='".$_REQUEST['Direccion']."',
                             telefono='".$_REQUEST['telefono']."',fax='".$_REQUEST['fax']."',email='".$_REQUEST['e_mail']."',celular='".$_REQUEST['celular']."'
                             WHERE tipo_id_tercero='".$_REQUEST['tipoDocumentoAnt']."' AND tercero_id='".$_REQUEST['DocumentoAnt']."';";


            $query.="UPDATE profesionales
            SET tipo_profesional='".$_REQUEST['tipo_profesional']."',tarjeta_profesional='".$_REQUEST['tarjetaProf']."',
            sexo_id='".$_REQUEST['Sexo']."',universidad='".$_REQUEST['universidad']."',
            observacion='".$_REQUEST['observacion']."',
            registro_salud_departamental='".$_REQUEST['registro_salud_departamental']."'
            WHERE tipo_id_tercero='".$_REQUEST['tipoDocumento']."' AND tercero_id='".$_REQUEST['Documento']."';";

            $query.="UPDATE profesionales_empresas
            SET empresa_id='".$_REQUEST['empresa']."'
            WHERE tipo_id_tercero='".$_REQUEST['tipoDocumento']."' AND tercero_id='".$_REQUEST['Documento']."';";

            if($departamentos){
                $query.="DELETE FROM profesionales_departamentos
                WHERE tipo_id_tercero='".$_REQUEST['tipoDocumentoAnt']."' AND tercero_id='".$_REQUEST['DocumentoAnt']."';";

                $query.="DELETE FROM profesionales_estado
                WHERE tipo_id_tercero='".$_REQUEST['tipoDocumentoAnt']."' AND tercero_id='".$_REQUEST['DocumentoAnt']."';";

                for($i=0;$i<sizeof($departamentos);$i++){
                    $query.="INSERT INTO profesionales_departamentos(departamento,tipo_id_tercero,tercero_id)VALUES('".$departamentos[$i]."',
                    '".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."');";

                    $query.="INSERT INTO profesionales_estado (tipo_id_tercero,tercero_id,departamento,estado,empresa_id)
                    VALUES('".$_REQUEST['tipoDocumento']."','".$_REQUEST['Documento']."','".$departamentos[$i]."','1','".$_REQUEST['empresa']."');";
                }
            }

            $query.="UPDATE profesionales_especialidades
            SET especialidad='".$_REQUEST['especialidad']."',universidad='".$_REQUEST['universidad']."'
            WHERE tipo_id_tercero='".$_REQUEST['tipoDocumento']."' AND tercero_id='".$_REQUEST['Documento']."' AND
            especialidad='".$_REQUEST['especialidadAnt']."';";
        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);



        }
/*        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);*/
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al traer las 0rdenes de servicios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            if($_REQUEST['modificacion']=='1'){
                $mensaje='El profesional fue Modificado';
                $titulo='MODIFICACIÓN PROFESIONAL';
                $accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosProfesionales');
            }else{
                $mensaje='El usuario fue Creado como Profesional';
                $titulo='CREACIÓN PROFESIONAL';
                $accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistemaProfesional');
            }
            $this->FormaMensaje($mensaje,$titulo,$accion,$boton);
            return true;
        }
        return falses;
    }

//fin funciones




/*funcion en la cual creamos un nuevo perfil*/
function InsertarPerfil()
{
 if(empty($_REQUEST['descrip']) || $_REQUEST['empresa']==-1)
 {
                            if($_REQUEST['descrip']==''){ $this->frmError["des"]=1; }
                            if($_REQUEST['empresa']==-1){ $this->frmError["emp"]=1; }
                            $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS.";
                            $this->FormaInsertNewPerfil($_REQUEST['empresa'],$_REQUEST['descrip']);
                            return true;
 }

                list($dbconn) = GetDBconn();
                $query="SELECT COUNT(*) FROM system_perfiles
                                WHERE       descripcion='".strtoupper($_REQUEST['descrip'])."'
                                AND empresa_id='".$_REQUEST['empresa']."'";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al buscar en la tabla en system_perfiles";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                    }
            if($resulta->fields[0]>=1)
            {
                            $this->frmError["MensajeError"]="EL NOMBRE DEL PERFIL YA EXISTE CAMBIELO POR FAVOR.";
                            $this->FormaInsertNewPerfil($_REQUEST['empresa'],$_REQUEST['descrip']);
                            return true;
            }
            else
            {
                    $query="INSERT INTO system_perfiles
                                                            ( descripcion,empresa_id)
                                                            VALUES
                                                            ('".strtoupper($_REQUEST['descrip'])."','".$_REQUEST['empresa']."')";
                                            $resulta=$dbconn->Execute($query);
                                            if ($dbconn->ErrorNo() != 0){
                                                $this->error = "Error al insertar en system_perfiles";
                                                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                                                return false;
                                                }
                    $this->frmError["MensajeError"]="DATOS GUARDADOS SATISFACTORIAMENTE.";
                    $this->FormaInsertNewPerfil(-1,'');
                    return true;
            }
}


    /*esta funcion fue creada para eliminar los perfiles*/
    function BorrarPerfil()
    {
            list($dbconn) = GetDBconn();
            $query="DELETE FROM system_perfiles
                            WHERE       descripcion='".strtoupper($_REQUEST['desc'])."'
                            AND empresa_id='".$_REQUEST['id']."'";
                $resulta=$dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0){
                    $this->frmError["MensajeError"]="NO SE PUEDE BORRAR YA QUE TIENE REGISTROS CARGADOS.";
                    $this->ListadoPerfiles();
                    return false;
                    }

            $this->ListadoPerfiles();
            return true;
     }




/*Esta funcion lo que hace es insertar ó adicionar menus a perfiles,por ejemplo
 * el perfil Economico contiene los menus Caja,Facturacion,CxC
*/
function InsertarAPerfiMenu()
{


    list($dbconn) = GetDBconn();
    if(empty($_REQUEST['op']))
    {
        $this->frmError["MensajeError"]="Debe escoger Alguna opcion.";
        $this->ListadoMenu($_REQUEST['per'],$_REQUEST['razon'],$_REQUEST['desc']);
    }
    $query="delete  from system_perfiles_menus
                where perfil_id=".$_REQUEST['per']."";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al borrar en system_perfiles_menus";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
        foreach($_REQUEST['op'] as $index=>$codigo)
        {
                        $query="INSERT INTO system_perfiles_menus
                                        ( perfil_id,menu_id)
                                        VALUES
                                        (".$_REQUEST['per'].",'".$codigo."')";
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0){
                            $this->error = "Error al insertar en system_perfiles_menus";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                            }

        }
        $this->ListadoMenu($_REQUEST['per'],$_REQUEST['razon'],$_REQUEST['nom']);
return true;


}


function InsertarAPerfilUsuario()
{

    list($dbconn) = GetDBconn();
    if(empty($_REQUEST['op']))
    {
        $this->frmError["MensajeError"]="Debe escoger Alguna opcion.";
        $this->ListadoPerfilUsuario($_REQUEST['uid'],$_REQUEST['user'],$_REQUEST['nom'],$_REQUEST['empresa'],$_REQUEST['NoEmp']);
    }
    $query="delete  from system_usuarios_perfiles
                where empresa_id=".$_REQUEST['empresa']."
                AND usuario_id=".$_REQUEST['uid']."";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al borrar en system_perfiles_usuarios";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
        foreach($_REQUEST['op'] as $index=>$codigo)
        {
                        $query="INSERT INTO system_usuarios_perfiles
                                        ( usuario_id,empresa_id,perfil_id)
                                        VALUES
                                        (".$_REQUEST['uid'].",'".$_REQUEST['empresa']."',".$codigo.")";
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0){
                            $this->error = "Error al insertar en system_perfiles_menus";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                            }

        }
        $this->ListadoPerfilUsuario($_REQUEST['uid'],$_REQUEST['user'],$_REQUEST['nom'],$_REQUEST['empresa'],$_REQUEST['NoEmp']);
return true;


}


function InsertarPermisosU()
{
        list($dbconn) = GetDBconn();
    $query="select count(*) from system_usuarios_empresas
                    where usuario_id=".$_REQUEST['uid']."";
    $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al buscar en system_usuarios_empresas";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
            if($resulta->fields[0]<1)
            {
             $query="INSERT INTO system_usuarios_empresas
                                ( usuario_id,empresa_id)
                                VALUES
                                (".$_REQUEST['uid'].",'".$_REQUEST['emp']."')";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al buscar en system_usuarios_empresas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            }
                $query="delete  from system_usuarios_departamentos
                where usuario_id=".$_REQUEST['uid']."";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al borrar en system_usuarios_departamentos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
        foreach($_REQUEST['op'] as $index=>$codigo)
        {
                        $query="INSERT INTO system_usuarios_departamentos
                                        ( usuario_id,departamento)
                                        VALUES
                                        (".$_REQUEST['uid'].",'".$codigo."')";
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0){
                            $this->error = "Error al insertar en system_usuarios_departamentos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                            }

        }
        $this->AsignarPermisosUserModulo($_REQUEST['uid'],urldecode($_REQUEST['NombreUsuario']),$_REQUEST['usuario'],'1',$_REQUEST['empID']);
return true;
}



function InsertarPermisosModulo()
{
                list($dbconn) = GetDBconn();

                $query="delete  from system_user_admin_modulos
                where usuario_id=".$_REQUEST['uid']."";
                $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0){
                    $this->error = "Error al borrar en system_user_admin_modulos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
        foreach($_REQUEST['op'] as $index=>$codigo)
        {
                        $query="INSERT INTO system_user_admin_modulos
                                        (  usuario_id,modulo,modulo_tipo)
                                        VALUES
                                        (".$_REQUEST['uid'].",'".$codigo."','app')";
                        $resulta=$dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0){
                            $this->error = "Error al insertar en system_user_admin_modulos";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                            }

        }
        $this->AsignarPermisosUserModulo($_REQUEST['uid'],urldecode($_REQUEST['NombreUsuario']),$_REQUEST['usuario'],'2',$_REQUEST['empID']);
return true;
}









function ComboDpto($empresa_id,$uid)
{
    list($dbconn) = GetDBconn();
        $query="select a.departamento,a.descripcion,
                        e.usuario_id from departamentos a
                        left join system_usuarios_departamentos
                        as e on(e.departamento=a.departamento and usuario_id='$uid')
                        where empresa_id='".$empresa_id."' order by  e.usuario_id asc ;";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al listar las empresas";
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




/*Esta funcion saca los modulos del sistema que sean solo 'app'(aplicación)
 * y paque tengan switche administrativo ='1'
 */
function TraerModulo($uid)
{
    list($dbconn) = GetDBconn();
        $query="select a.modulo,a.modulo_tipo,a.descripcion,e.usuario_id from system_modulos a
                        left join system_user_admin_modulos as e on(e.modulo=a.modulo and
                        e.modulo_tipo=e.modulo_tipo and usuario_id='".$uid."')
                        where
                        a.sw_admin='1' and a.modulo_tipo = 'app'
                        and a.modulo <> '' order by a.modulo";
        $resulta=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0){
            $this->error = "Error al listar modulos";
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



/*funcion que saca los menus para adherir al perfil */
function TraerMenus($perfil)
{
    list($dbconn) = GetDBconn();
    $query="select b.perfil_id,a.menu_id,
                            a.menu_nombre,a.descripcion from system_menus   a
                            left join system_perfiles_menus b
                            on (a.menu_id=b.menu_id and b.perfil_id=$perfil)
                            where a.menu_id <> 20
                            order by a.menu_nombre";
                    $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0){
                                $this->error = "Error al listar los Menus";
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



/*funcion que saca perfiles */
function TraerPerfilesUser($uid,$emp)
{
    list($dbconn) = GetDBconn();
    $query="
                    select a.perfil_id,b.usuario_id,a.descripcion  from system_perfiles a
                    left join system_usuarios_perfiles as b
                    on(a.empresa_id=b.empresa_id and b.usuario_id=".$uid.")
                    WHERE a.empresa_id='".$emp."'";
                    $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0){
                                $this->error = "Error al listar en la tabla system_perfiles";
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





/*funcion trae los perfiles según la empresa */
function TraerPerfil($perfil)
{
    list($dbconn) = GetDBconn();
    $query="select b.perfil_id,a.menu_id,
                            a.menu_nombre,a.descripcion from system_menus   a
                            left join system_perfiles_menus b
                            on (a.menu_id=b.menu_id and b.perfil_id=$perfil)
                            where a.menu_id <> 20
                            order by a.menu_nombre";
                    $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0){
                                $this->error = "Error al listar los Menus";
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


/*funcion trae los perfiles según la empresa */
function GetCaducidadContrasena()
{
    list($dbconn) = GetDBconn();
    $query="SELECT caducidad_id,descripcion
                    FROM system_caducidad_passwd ORDER BY indice_orden";
                    $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0){
                                $this->error = "Error al listar los formatos de caducidad";
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



 function ListarMenus($var,$uid,$nombre,$usuario,$descripcion)
    {
         if(!empty($_REQUEST['uid']))
                  {
                            $uid=$_REQUEST['uid'];
                            $nombre=$_REQUEST['nombre'];
                            $usuario=$_REQUEST['usuario'];
                            $descripcion=$_REQUEST['descripcion'];
                            list($dbconn) = GetDBconn();
                            $query="
                            select a.menu_id, a.menu_nombre,a.descripcion,
                            b.usuario_id from system_menus a left join system_usuarios_menus b
                            on (a.menu_id=b.menu_id AND b.usuario_id=".$uid.")
                            WHERE sw_system=0
                            order by a.menu_nombre";
                            $resulta=$dbconn->Execute($query);
                            if ($dbconn->ErrorNo() != 0){
                                $this->error = "Error al listar los Menus";
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
                            $this->PermisosMenuUsuario($var,$uid,$nombre,$usuario,$descripcion);
                    }
                    else
                    {
                            $this->PermisosMenuUsuario($var,$uid,$nombre,$usuario,$descripcion);
                    }
                    return true;
    }



function EstadoIps($ip)
    {
                    list($dbconn) = GetDBconn();
                $query="SELECT sw_bloqueo from system_host WHERE ip='$ip'";
                    $resulta=$dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0){
                        $this->error = "Error al listar las direcciones ip's";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }
          $ips= $resulta->fields[0];
                    return $ips;
    }



    function InsertarPermisoMenu()
    {
            $uid=$_REQUEST['uid'];
            $menu=$_REQUEST['menu'];
            list($dbconn) = GetDBconn();
            $query = "SELECT count(*) FROM system_usuarios_menus WHERE usuario_id='$uid'
            and menu_id='$menu'";
            $result = $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }else{
                    if($result->fields[0]>0){
                    $query = "DELETE  from  system_usuarios_menus
                                        WHERE usuario_id='$uid' and menu_id='$menu'";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Borrar en system_usuarios_menus";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }

                }else{
                    $query = "INSERT INTO system_usuarios_menus
                                        (usuario_id,menu_id)
                                        VALUES
                                        ($uid,$menu)";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Insertar en system_usuarios_menus";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
                }
            }
                $this->ListarMenus($var,$uid,$nombre,$usuario,$descripcion);
                return true;
    }



    function VerListadoAcceso()
    {
            $ip=$_REQUEST['ip'];
            $host=$_REQUEST['host'];
            list($dbconn) = GetDBconn();
            $query="select  b.descripcion,b.tipo_alerta_id,
            a.log,a.tipo_log,a.fecha,a.detalle
            from system_host_log a,system_tipos_log b
            where a.tipo_log=b.tipo_log_id and a.host='$ip' order by a.fecha desc LIMIT 10 OFFSET 0;";
            $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            else
                {
                        $i=0;
                            while(!$resulta->EOF)
                                    {
                                            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                                            $resulta->MoveNext();
                                            $i++;
                                    }
                    }
                    $this->ListadoAccesos($var,$ip,$host);
                    return true;
 }


    /*$num es el numero de opcion que escogio en el combo */
    /*$busca es la busqueda*/
    function GetFiltroUsuarios($num,$busca)
    {

            switch($num)
            {
                    case "1":
                    {
                        if(is_numeric($busca))
                        {
                                    $filtro="AND d.usuario_id=".trim($busca)."";

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
                                    $filtro="AND lower(d.usuario) like '%".strtolower(trim($busca))."%'";
                                         //or lower(d.usuario) like '%".strtolower(trim($busca))."'
                                        // or lower(d.usuario) like '".strtolower(trim($busca))."%'
                                        $_SESSION['CENTRAL']['negrilla']=2;
                        break;
                    }
                    case "3":
                    {
                                    $filtro="AND lower(d.nombre) like '%".strtolower(trim($busca))."%'";
                                         //or lower(d.nombre) like '%".strtolower(trim($busca))."'
                                         //or lower(d.nombre) like '".strtolower(trim($busca))."%'
                                         $_SESSION['CENTRAL']['negrilla']=3;
                        break;
                    }
            }
            return $filtro;
    }


/**
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

    function BuscarUsuariosSistema($filtro){

        list($dbconn) = GetDBconn();
        if(empty($_REQUEST['conteo'])){
        $query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
                                 d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
                                 from system_usuarios_empresas a, empresas as c
                                ,system_usuarios_administradores b,system_usuarios as d
                                where a.empresa_id=b.empresa_id
                                and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
                         and a.usuario_id=d.usuario_id
                                 --and a.usuario_id <> '".UserGetUID()."'
                                  $filtro order by empresa_id";

        $result = $dbconn->Execute($query);
        list($this->conteo)=$result->RecordCount();
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
            $this->conteo=$result->RecordCount();
    }else{
      $this->conteo=$_REQUEST['conteo'];
        }
        if(!$_REQUEST['Of']){
      $Of='0';
        }else{
      $Of=$_REQUEST['Of'];
        }
    /*$query = "SELECT usuario_id,
                         usuario,
                                         nombre,
                                         descripcion,
                                         passwd,
                                         activo,
                                         sw_admin
                      FROM system_usuarios WHERE usuario_id > 0 ORDER BY usuario LIMIT " . $this->limit . " OFFSET $Of";*/
                if(!empty($_SESSION['USUARIOS']['ORDENAMIENTO']))
                {$ordenamiento=$_SESSION['USUARIOS']['ORDENAMIENTO'];}else{$ordenamiento='order by empresa_id,usuario';}
              $query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
                                 d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
                                 from system_usuarios_empresas a, empresas as c
                                ,system_usuarios_administradores b,system_usuarios as d
                                where a.empresa_id=b.empresa_id
                                and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
                                and a.usuario_id=d.usuario_id
                                --and a.usuario_id <> '".UserGetUID()."'
                                $filtro $ordenamiento
                                LIMIT " . $this->limit . " OFFSET $Of";

        $result = $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
    $i=0;
        while(!$result->EOF){
        $datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.
        $result->fields[6].'/'.$result->fields[7].'/'.$result->fields[8];
      $result->MoveNext();
            $i++;
        }
    $result->Close();
    return $datos;
    }

    /**
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

    function BuscarUsuariosSistemaNoProfesionales($filtro){

        list($dbconn) = GetDBconn();
        if(!empty($_SESSION['USUARIOS']['ORDENAMIENTO'])){
            $ordenamiento=$_SESSION['USUARIOS']['ORDENAMIENTO'];
        }else{
            $ordenamiento='order by empresa_id,usuario';
        }
        $query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
        d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
        from system_usuarios_empresas a, empresas as c
        ,system_usuarios_administradores b,system_usuarios as d
        where a.empresa_id=b.empresa_id
        and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
        and a.usuario_id=d.usuario_id
        --and a.usuario_id <> '".UserGetUID()."'
        AND d.usuario_id NOT IN (SELECT usuario_id FROM profesionales_usuarios)
        $filtro $ordenamiento";
        $result = $dbconn->Execute($query);
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
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $i=0;
            while(!$result->EOF){
            $datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.
            $result->fields[6].'/'.$result->fields[7].'/'.$result->fields[8];
                $result->MoveNext();
                $i++;
            }
        }
    $result->Close();
    return $datos;
    }

        /**
* Funcion que busca los datos principales de los usuarios del sistema
* @return array
*/

    function BuscarUsuariosSistemaProfesionales($filtro){

        list($dbconn) = GetDBconn();
        if(!empty($_SESSION['USUARIOS']['ORDENAMIENTO'])){
            $ordenamiento=$_SESSION['USUARIOS']['ORDENAMIENTO'];
        }else{
            $ordenamiento='order by empresa_id,usuario';
        }
        $query = "select a.usuario_id,d.usuario,d.nombre,d.descripcion,
        d.passwd,d.activo,d.sw_admin,a.empresa_id,c.razon_social
        from system_usuarios_empresas a, empresas as c
        ,system_usuarios_administradores b,system_usuarios as d
        where a.empresa_id=b.empresa_id
        and b.usuario_id='".UserGetUID()."' and a.empresa_id=c.empresa_id
        and a.usuario_id=d.usuario_id
        --and a.usuario_id <> '".UserGetUID()."'
        AND d.usuario_id IN (SELECT x.usuario_id FROM profesionales_usuarios x,profesionales y
                            WHERE x.tipo_tercero_id=y.tipo_id_tercero AND x.tercero_id=y.tercero_id AND y.estado='1')
        $filtro $ordenamiento";
        $result = $dbconn->Execute($query);
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
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
            $i=0;
            while(!$result->EOF){
            $datos[$i]=$result->fields[0].'/'.$result->fields[1].'/'.$result->fields[2].'/'.$result->fields[3].'/'.$result->fields[4].'/'.$result->fields[5].'/'.
            $result->fields[6].'/'.$result->fields[7].'/'.$result->fields[8];
                $result->MoveNext();
                $i++;
            }
        }
    $result->Close();
    return $datos;
    }

//OJO CON ESTA FUNCION QUE ES DE REVISAR EL LOGUEO DE LA PERSONA.............1588

    function BuscarLog($uid,$señal='')
    {
            list($dbconn) = GetDBconn();
            if($señal==true)
            {
                $LIMITE='LIMIT 5 OFFSET 0';
            }
        //  if($señal==)
            $query="select a.fecha,b.descripcion,b.tipo_alerta_id from system_usuarios_log a,
            system_tipos_log b where usuario_id=$uid and
            a.tipo_log=b.tipo_log_id  order by fecha desc $LIMITE";
            $resulta=$dbconn->Execute($query);
                if ($dbconn->ErrorNo() != 0)
                {
                    $this->error = "Error al Guardar en la Base de Datos";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                }
            else
                {
          if($señal==false)
                    {
                        $var=$resulta->RecordCount();
                    }
                    else
                    {
                            $i=0;
                            while(!$resulta->EOF)
                                    {
                                            $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
                                            $resulta->MoveNext();
                                            $i++;
                                    }
                    }
                    return $var;
                }
 }


/**
* Funcion que busca el estado(1=activo,0=inactivo) actual en la base de datos del usuario en el sistema
* @return boolean
*/

    function BuscaEstadoAfiliado($uid){
    list($dbconn) = GetDBconn();
      $query = "SELECT activo FROM system_usuarios WHERE usuario_id='$uid'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{
      $dato=$result->fields[0];
        }
        $result->Close();
    return $dato;
    }




/**
* Funcion que busca los perfiles
* @return array
*/

    function BuscarPerfil(){
    list($dbconn) = GetDBconn();
      $query = "select  a.perfil_id,a.descripcion,a.empresa_id,b.razon_social
                            from system_perfiles a,empresas b,system_usuarios_empresas c
                            where  a.empresa_id=b.empresa_id
                            and a.empresa_id=c.empresa_id and c.usuario_id='".UserGetUID()."'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al buscar los perfiles";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{

                            $i=0;
                            while(!$result->EOF)
                                    {
                                            $var[$i]=$result->GetRowAssoc($ToUpper = false);
                                            $result->MoveNext();
                                            $i++;
                                    }
                    }
                    return $var;
    }



/**
* Funcion que busca un usuario en particular
* @return array
*/

    function TraerUsuario(){
    list($dbconn) = GetDBconn();
      $query = "SELECT  usuario_id,usuario,nombre from system_usuarios WHERE
                            usuario_id='".UserGetUID()."'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al buscar     el usuario";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{

                            $i=0;
                            while(!$result->EOF)
                                    {
                                            $var[$i]=$result->GetRowAssoc($ToUpper = false);
                                            $result->MoveNext();
                                            $i++;
                                    }
                    }
                    return $var;
    }



function BuscaEstadoUserEmpresa($uid,$empresa){
    list($dbconn) = GetDBconn();
      $query = "SELECT sw_activo FROM system_usuarios_empresas WHERE usuario_id='$uid'
        and empresa_id='".$empresa."'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{
      $dato=$result->fields[0];
        }
        $result->Close();
    return $dato;
    }



/**
* Funcion que busca el estado(1=activo,0=inactivo) actual en la base de datos del usuario en el sistema
* @return boolean
*/

    function RevisarTema($uid){
    list($dbconn) = GetDBconn();
      $query = "SELECT valor from system_usuarios_vars WHERE variable='Tema' and usuario_id='$uid'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{
      $dato=$result->fields[0];
        }
        return $dato;
    }


/**
* Funcion que llama la funcion FormaAsignarPermisosUsuarios
* @return boolean
*/

    function LlamaAsignarPermisosUsuarios(){

    $uid=$_REQUEST['uid'];
        $NombreUsuario=urldecode($_REQUEST['nombre']);
    $Usuario=$_REQUEST['usuario'];
    $empresa=$_REQUEST['empID'];
    $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$empresa);
        return true;
    }


/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

    function LlamaModificarUsuarioSistema(){
    $uid=$_REQUEST['uid'];
        $nombre=$_REQUEST['nombre'];
    $usuario=$_REQUEST['usuario'];
        $tema=$_REQUEST['tema'];
        $descripcion=$_REQUEST['descripcion'];
        $empresa=$_REQUEST['empID'];
    $consulta='1';
        $action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid,"emp"=>$_REQUEST['empID']));
        if(!$this->FormaInsertarUsuarioSistema($nombre,$usuario,'','',$tema,$action,$consulta,$descripcion,true,$uid,$empresa)){
        return false;
    }
        return true;
  }

/**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

    function LlamaCrearProfesionalSistema(){
    $uid=$_REQUEST['uid'];
        $nombre=$_REQUEST['nombre'];
    $usuario=$_REQUEST['usuario'];
        $descripcion=$_REQUEST['descripcion'];
        $empresa=$_REQUEST['empID'];
        if(!$this->FormaInsertarProfesionalUsuarioSistema($uid,$nombre,$usuario,$empresa,$descripcion)){
        return false;
    }
        return true;
  }

    /**
* Funcion donde se llama la funcion FormaInsertarUsuarioSistema
* @return boolean
*/

    function LlamaModificarProfesionalSistema(){
    $uid=$_REQUEST['uid'];
        $nombre=$_REQUEST['nombre'];
    $usuario=$_REQUEST['usuario'];
        $descripcion=$_REQUEST['descripcion'];
        $empresa=$_REQUEST['empID'];
        list($dbconn) = GetDBconn();
      $query = "SELECT a.tipo_tercero_id,a.tercero_id,c.departamento,d.especialidad,d.universidad,
                            f.nombre,f.tipo_profesional,f.tarjeta_profesional,f.sexo_id,f.observacion,
                            f.registro_salud_departamental,g.tipo_pais_id,g.tipo_dpto_id,g.tipo_mpio_id,
                            g.direccion,g.telefono,g.fax,g.email,g.celular,g.nombre_tercero

                            FROM profesionales_usuarios a,profesionales_estado b,profesionales_departamentos c,profesionales_especialidades d,
                            profesionales_empresas e,profesionales f,terceros g
                            WHERE a.usuario_id='".$uid."' AND
                            a.tipo_tercero_id=b.tipo_id_tercero AND a.tercero_id=b.tercero_id AND b.estado='1' AND
                            b.departamento=c.departamento AND b.tipo_id_tercero=c.tipo_id_tercero AND b.tercero_id=c.tercero_id AND
                            a.tipo_tercero_id=d.tipo_id_tercero AND a.tercero_id=d.tercero_id AND
                            a.tipo_tercero_id=e.tipo_id_tercero AND a.tercero_id=e.tercero_id AND b.empresa_id=e.empresa_id AND
                            a.tipo_tercero_id=f.tipo_id_tercero AND a.tercero_id=f.tercero_id AND f.estado='1' AND
                            g.tipo_id_tercero=a.tipo_tercero_id AND g.tercero_id=a.tercero_id";

      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{
            while (!$result->EOF) {
                $vars=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
        }
        $_REQUEST['tipoDocumento']=$vars['tipo_tercero_id'];
        $_REQUEST['Documento']=$vars['tercero_id'];
        $_REQUEST['tipoDocumentoAnt']=$vars['tipo_tercero_id'];
        $_REQUEST['DocumentoAnt']=$vars['tercero_id'];
        $_REQUEST['Sexo']=$vars['sexo_id'];
        $_REQUEST['pais']=$vars['tipo_pais_id'];
        $_REQUEST['dpto']=$vars['tipo_dpto_id'];
        $_REQUEST['mpio']=$vars['tipo_mpio_id'];
        $_REQUEST['departamento']=$vars['departamento'];
        $_REQUEST['Direccion']=$vars['direccion'];
        $_REQUEST['telefono']=$vars['telefono'];
        $_REQUEST['fax']=$vars['fax'];
        $_REQUEST['e_mail']=$vars['email'];
        $_REQUEST['celular']=$vars['celular'];
        $_REQUEST['tipo_profesional']=$vars['tipo_profesional'];
        $_REQUEST['especialidad']=$vars['especialidad'];
        $_REQUEST['especialidadAnt']=$vars['especialidad'];
        $_REQUEST['tarjetaProf']=$vars['tarjeta_profesional'];
        $_REQUEST['universidad']=$vars['universidad'];
        $_REQUEST['reg_salud']=$vars['registro_salud_departamental'];
        $_REQUEST['observacion']=$vars['observacion'];

        if(!$this->FormaInsertarProfesionalUsuarioSistema($uid,$nombre,$usuario,$empresa,$descripcion,$modificacion='1')){
        return false;
    }
        return true;
  }


    /**
* Funcion donde se Modifican en la base de datos los datos principales de un usuario que ya exite en el sistema
* @return boolean
*/

    function ModificarUsuariosSistema(){

    $fechacaduca=$_REQUEST['caducidad'];
        $_SESSION['USER']['FECH']=$fechacaduca;
        $_SESSION['USER']['DIAS']=$_REQUEST['dias'];
        $uid=$_REQUEST['uid'];
        $nombreUsuario=urldecode($_REQUEST['nombreUsuario']);
    $tema=$_REQUEST['tema'];
        $descripcion=$_REQUEST['descripcion'];
        $activo=$_REQUEST['activo'];
        $sw_empresa=$_REQUEST['administrador']; //esta variable es el switche de empresa.
    $loginUsuario=$_REQUEST['loginUsuario'];
        $empresa=$_REQUEST['empresa'];

        if($nombreUsuario=='' || $loginUsuario=='' || $empresa==-1){
            if($nombreUsuario==''){ $this->frmError["nombreUsuario"]=1; }
            if($loginUsuario==''){ $this->frmError["loginUsuario"]=1; }
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
            $consulta='1';
      $action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
            if(!$this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion,$empresa)){
                return false;
            }
            return true;
        }

        if($activo){$activo='1';
        }else{$activo='0';}
    if($sw_empresa){$sw_empresa='1';
        }else{$sw_empresa='0';}
        if($tema==-1){$tema='';}


    if(empty($fechacaduca))
        {
                $fech=",caducidad_contrasena=".$_SESSION['USER']['DIAS'].",fecha_caducidad_cuenta=NULL";

                if($_SESSION['USER']['DIAS']!=0)
                {
                    $fech.=",fecha_caducidad_contrasena='". date("Y-m-d",strtotime("+".$_SESSION['USER']['DIAS']." days",strtotime(date("Y-m-d"))))."'";
                }
                else
                {
                    $fech.=",fecha_caducidad_contrasena=NULL";
                }
        }
        else
        {
                                            //        if(!checkdate(date($fechacaduca)))
                                //          {
                                //              $this->frmError["MensajeError"]="Escoga una fecha en formato d-m-a";
                                //              $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
                                //              $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion,$fechacaduca);
                                //              return true;
                                //          }
                    if(strtotime($fechacaduca) >= strtotime(date("d-m-Y")))
                    {

                            $fech=",fecha_caducidad_cuenta='$fechacaduca',
                                            fecha_caducidad_contrasena='". date("Y-m-d",strtotime("+".$_SESSION['USER']['DIAS']." days",strtotime(date("Y-m-d"))))."'
                                            ,caducidad_contrasena=".$_SESSION['USER']['DIAS']."";
                    }
                    else
                    {
                        $this->frmError["MensajeError"]="La fecha debe ser de hoy o posterior.";
                        $consulta='1';
                        $action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
                      $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion,$empresa);
                        return true;
                    }
        }




        $login=$this->verificaExisteLoginInsertado($loginUsuario,$uid);
        if($login){
                $this->frmError["MensajeError"]="Este login ya existe Debe Cambiarlo";
        $consulta='1';
                $action=ModuloGetURL('system','Usuarios','admin','ModificarUsuariosSistema',array("uid"=>$uid));
              $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,$consulta,$descripcion,$empresa);
              return true;
        }

        $nombreUsuario=strtoupper($nombreUsuario);
        //$loginUsuario=strtoupper($loginUsuario);
        list($dbconn) = GetDBconn();
        $dbconn->StartTrans();  //comienza transacion
        $query = "UPDATE system_usuarios SET usuario='$loginUsuario',
                                              nombre='$nombreUsuario',
                                                                                    descripcion='$descripcion',
                                                                                    activo='$activo',
                                                                sw_admin='0'
                                                                                    $fech
                                                                                    WHERE usuario_id='$uid'";
        $dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al actualizar en system_usuarios";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }


        $query="SELECT count(*) FROM system_usuarios_empresas WHERE
                         usuario_id='$uid' AND empresa_id='$empresa'";

        $res=$dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al consultar en system_usuarios_empresas";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
            }

         //para que al actualizar no nos saque un error por eso lo comparamos
    if($res->fields[0]<1)
        {
                 $query = "UPDATE  system_usuarios_empresas SET
                                                                        empresa_id='$empresa',
                                                                        sw_activo='$sw_empresa'
                                                                        WHERE usuario_id='$uid' AND empresa_id='".$_REQUEST['emp']."'";
                $dbconn->Execute($query);

                if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error al actualizar en system_usuarios_empresas";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
                    }
         }

        $query = "SELECT COUNT(*) FROM  system_usuarios_vars WHERE variable='Tema' AND usuario_id='$uid'";
        $res=$dbconn->Execute($query);

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al buscar en system_usuarios_vars";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        $conteo=$res->fields[0];
        if($conteo > 0)
        {
                                if(!empty($tema))
                                {
                 UserSetVar($uid,'Tema',$tema);
                                }
                                else
                                {
                                    UserDelVar($uid,'Tema');
                                }

        }
        else
        {UserSetVar($uid,'Tema',$tema);}
        $dbconn->CompleteTrans();   //termina la transaccion
        $this->ListadoUsuariosSistema();
        return true;

    }

/**
* Funcion donde se Modifica en la base de datos el estado(1=activo,0=inactivo) del usuarios en el sistema
* @return boolean
*/

function BorrarUsuarios()
{
                        list($dbconn) = GetDBconn();
                        $uid=$_REQUEST['uid'];
                        $query = "DELETE FROM system_usuarios WHERE usuario_id=$uid";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                        $this->frmError["MensajeError"]="EL USUARIO NO SE BORRO YA QUE TIENE REGISTROS CARGADOS.";
            if(!$this->ListadoUsuariosSistema()){
                        return false;
                        }
                        return true;
                    }

                UserDelVar($uid,'Tema');
                $this->ListadoUsuariosSistema();
                return true;
}



function ModificarEstadoEmpresa()
{
        list($dbconn) = GetDBconn();
        $uid=$_REQUEST['uid'];
    $TipoForma=$_REQUEST['TipoForma'];
    $NombreUsuario=urldecode($_REQUEST['NombreUsuario']);
    $Usuario=$_REQUEST['usuario'];

        $query = "SELECT activo FROM system_usuarios WHERE usuario_id='$uid'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }

     $result->fields[0];
     if($result->fields[0]=='1')
         {
                    $query = "SELECT sw_activo FROM system_usuarios_empresas WHERE usuario_id='$uid'
                    and empresa_id='".$_REQUEST['empresa']."';";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                        $this->error = "Error al buscar en system_usuarios_empresas";
                        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                        return false;
                    }else{

                        $result->fields[0];
                        if($result->fields[0]=='1'){
                            $query = "UPDATE system_usuarios_empresas SET sw_activo='0' WHERE usuario_id='$uid'
                            and empresa_id='".$_REQUEST['empresa']."';";
                            $result = $dbconn->Execute($query);
                        }else{
                            $query = "UPDATE system_usuarios_empresas SET sw_activo='1' WHERE usuario_id='$uid'
                            and empresa_id='".$_REQUEST['empresa']."';";
                            $result = $dbconn->Execute($query);
                        }
                    }
                    if(!$TipoForma){
                        $this->ListadoUsuariosSistema();
                        return true;
                    }elseif($TipoForma==1){
                        $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$_REQUEST['empresa']);
                        return true;
                    }
        }
        else
        {
            if(!$TipoForma){
                        $this->ListadoUsuariosSistema();
                        return true;
                    }elseif($TipoForma==1){
                        $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$_REQUEST['empresa']);
                        return true;
                    }
        }
}


function ModificarEstadoUsuario(){

        $uid=$_REQUEST['uid'];
    $TipoForma=$_REQUEST['TipoForma'];
    $NombreUsuario=urldecode($_REQUEST['NombreUsuario']);
    $Usuario=$_REQUEST['usuario'];

        list($dbconn) = GetDBconn();
      $query = "SELECT activo FROM system_usuarios WHERE usuario_id='$uid'";
      $result = $dbconn->Execute($query);
        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
      return false;
        }else{
      $result->fields[0];
      if($result->fields[0]=='1'){
              $query = "UPDATE system_usuarios SET activo='0' WHERE usuario_id='$uid';";
          $result = $dbconn->Execute($query);
                $query = "UPDATE system_usuarios_empresas SET sw_activo='0' WHERE usuario_id='$uid'
                and empresa_id='".$_REQUEST['empresa']."';";
          $result = $dbconn->Execute($query);
          }else{
            $query = "UPDATE system_usuarios SET activo='1' WHERE usuario_id='$uid';";
          $result = $dbconn->Execute($query);
                $query = "UPDATE system_usuarios_empresas SET sw_activo='1' WHERE usuario_id='$uid'
                and empresa_id='".$_REQUEST['empresa']."';";
                $result = $dbconn->Execute($query);
          }
        }
    if(!$TipoForma){
          $this->ListadoUsuariosSistema();
          return true;
        }elseif($TipoForma==1){
      $this->FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$_REQUEST['empresa']);
            return true;
        }

//      else{
//       $this->AsignarDepartamentosUsuario($uid,$NombreUsuario,$Usuario);
//          return true;
//      }
  }

/**
* Funcion que llama a la funcion FormaModificarPasswd
* @return boolean
*/

function LlamaFormaModificarPasswd(){
    $uid=$_REQUEST['uid'];
        $nombre=$_REQUEST['nombre'];
        $usuario=$_REQUEST['usuario'];
        $action=ModuloGetURL('system','Usuarios','admin','ModificarPasswd',array("uid"=>$uid));
        if(!$this->FormaModificarPasswd($action,'','',$nombre,$usuario)){
        return false;
    }
        return true;
    }

/**
* Funcion que modifica en la base de datos el password actual que tiene el usuario en el sistema
* @return boolean
*/

  function ModificarPasswd(){

        $uid=$_REQUEST['uid'];
        $password=$_REQUEST['password'];
    $passwordReal=$_REQUEST['passwordReal'];
        $nombre=$_REQUEST['nombre'];
        $usuario=$_REQUEST['usuario'];

        //$_REQUEST['resetear'];

        if($_REQUEST['aceptar'])
        {
                        if($password=='' || $passwordReal==''){
                            if($password==''){ $this->frmError["password"]=1; }
                            if($passwordReal==''){ $this->frmError["passwordReal"]=1; }
                            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
                            $action=ModuloGetURL('system','Usuarios','admin','ModificarPasswd',array("uid"=>$uid));
                            $this->FormaModificarPasswd($action,$password,$passwordReal,$nombre,$usuario);
                            return true;
                        }

                        if(strcmp($password,$passwordReal)==0){
                            $passwd=UserEncriptarPasswd($password);
                        }else{
                            $this->frmError["MensajeError"]="La Contraseña esta Errada.";
                            $action=ModuloGetURL('system','Usuarios','admin','ModificarPasswd',array("uid"=>$uid));
                            $this->FormaModificarPasswd($action,'','',$nombre,$usuario);
                            return true;
                        }

                        list($dbconn) = GetDBconn();
                        $query = "UPDATE system_usuarios SET passwd='$passwd' WHERE usuario_id='$uid'";
                        $dbconn->Execute($query);
                        if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al actualizar en la tabla system_usuarios";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                          }else{
                            $this->ListadoUsuariosSistema();
                            return true;
                         }

        }
        elseif($_REQUEST['resetear'])
        {
            UserResetPasswd($uid);
            $this->ListadoUsuariosSistema();
            return true;
        }


 }

 /**
* Funcion donde se llama la funcion listadoUsuariosSistema
* @return boolean
*/

  function listadoUsuarios(){
    if(!$this->ListadoUsuariosSistema()){
      return false;
    }
      return true;
  }

/**
* Funcion que verifica si en la base de datos existe el login que tiene el usuario del sistema
* @return string
* @param string login del usuario
*/

  function verificaExisteLogin($login){

        $login=strtoupper($login);
        list($dbconn) = GetDBconn();
      $query = "SELECT * FROM system_usuarios WHERE usuario='$login'";
      $result = $dbconn->Execute($query);
        $datos=$result->RecordCount();

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($datos){
         return 1;
            }
        }
  }

/**
* Funcion que verifica si el usuario del sistema al cambiar el login, este ya existe en la base de datos para otro usuario
* @return boolean
* @param string login del usuario
* @param integer identificacion unica del usuario
*/

    function verificaExisteLoginInsertado($login,$uid){

        $login=strtoupper($login);
        list($dbconn) = GetDBconn();
      $query = "SELECT * FROM system_usuarios WHERE usuario='$login' AND usuario_id!='$uid'";
      $result = $dbconn->Execute($query);
        $datos=$result->RecordCount();

        if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }else{
      if($datos){
         return 1;
            }
        }
  }


    /**
* Funcion donde se Insertan datos de un usuario del sistema que fue creado en la forma
* @return boolean
*/

    function InsertarUsuariosSistema(){
        $nombreUsuario=urldecode($_REQUEST['nombreUsuario']);
    $fechacaduca=$_REQUEST['caducidad'];
//echo "-->".$fechacaduca;
//exit;
        $_SESSION['USER']['FECH']=$fechacaduca;
    $_SESSION['USER']['DIAS']=$_REQUEST['dias'];
        $tema=$_REQUEST['tema'];
        $activo=$_REQUEST['activo'];
        $descripcion=$_REQUEST['descripcion'];
    $loginUsuario=$_REQUEST['loginUsuario'];
    $password=$_REQUEST['password'];
    $passwordReal=$_REQUEST['passwordReal'];
        $empresa=$_REQUEST['empresa'];
        $sw_empresa=$_REQUEST['administrador']; //ya no es administrador sino que este dato va
        //para el switche de system_usuarios_empresas.
        $action=$_REQUEST['action'];//este si es el switche de system_usuarios

        if($nombreUsuario=='' || $loginUsuario==''){
            if($nombreUsuario==''){ $this->frmError["nombreUsuario"]=1; }
            if($loginUsuario==''){ $this->frmError["loginUsuario"]=1; }
            if($empresa==-1){ $this->frmError["emp"]=1; }
            //if($password==''){ $this->frmError["password"]=1; }
      //if($passwordReal==''){ $this->frmError["passwordReal"]=1; }
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
            $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
            return true;
        }

//     if(empty($_REQUEST['nocad']) and empty($fechacaduca))
//      {
//          $this->frmError["MensajeError"]="Debe escoger una fecha de caducidad o escoger la opcion de no caducidad.";
//       $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
//          $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion,$fechacaduca);
//          return true;
//      }

    if(empty($fechacaduca))
        {
            $comparador_de_insercion=1; //esta variable cambia el query a que inserte NULL
        }
        else
        {
                    $feca=explode("-",$fechacaduca);
                    $fechacaduca=$feca[2]."-".$feca[1]."-".$feca[0];

//        if(!checkdate(date($fechacaduca)))
//          {
//              $this->frmError["MensajeError"]="Escoga una fecha en formato d-m-a";
//              $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
//              $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion,$fechacaduca);
//              return true;
//          }

            if(strtotime(date($fechacaduca)) >= strtotime(date("Y-m-d")))
            {
        $comparador_de_insercion=0; //esta variable cambia el query a q inserte fecha.
            }
            else
            {
                $this->frmError["MensajeError"]="La fecha debe ser de hoy o posterior.";
                $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
                $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
                return true;
            }

        }



    if($activo){$activo='1';
        }else{$activo='0';}
    if($sw_empresa){$sw_empresa='1';
        }else{$sw_empresa='0';}
    if($tema==-1){$tema='';}

        list($dbconn) = GetDBconn();
        //revisamos q no exista un login igual ó parecido en la base de datos..
        $query = "SELECT COUNT(*) FROM system_usuarios WHERE UPPER(usuario)='".rtrim(ltrim(strtoupper($loginUsuario)))."'";
        $res=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al buscar login en system_usuarios";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
            }

            if($res->fields[0]>0)
            {
                $this->frmError["MensajeError"]="EXISTE UN LOGIN IGUAL EN LA BASE DE DATOS,POR FAVOR CAMBIE SU LOGIN!";
                $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
                $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
                return true;
            }



//      if(strcmp($password,$passwordReal)==0){
//          $passwd=UserEncriptarPasswd($password);
//      }else{
//       $this->frmError["MensajeError"]="Escriba de Nuevo las contraseñas, Estas no coinciden";
//       $action=ModuloGetURL('system','Usuarios','user','InsertarUsuariosSistema');
//          $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,'','',$tema,$action,'',$descripcion);
//          return true;
//      }
//
//      $login=$this->verificaExisteLogin($loginUsuario);
//      if($login){
//         $this->frmError["MensajeError"]="Este login ya existe Debe Cambiarlo";
//         $action=ModuloGetURL('system','Usuarios','user','InsertarUsuariosSistema');
//            $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
//            return true;
//      }

        if($empresa==-1){
            $this->frmError["emp"]=1;
            $this->frmError["MensajeError"]="Faltan datos obligatorios.";
      $action=ModuloGetURL('system','Usuarios','admin','InsertarUsuariosSistema');
            $this->FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,'',$descripcion);
            return true;
        }


        $query = "select nextval('system_usuarios_usuario_id_seq');";
        $res=$dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al seleccionar el serial de system_usuarios";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
            }
    $serial=$res->fields[0];



        $dbconn->StartTrans();  //comienza transacion
        $nombreUsuario=strtoupper($nombreUsuario);
    //$loginUsuario=strtoupper($loginUsuario);
     $fecha_contraseña=date('Y-m-d',strtotime('+'.$_SESSION['USER']['DIAS'].'days',strtotime(date('Y-m-d'))));

// $fecha_contraseña;
//exit;
        $query = "select codigo_alterno from system_usuarios_codigo_alterno;";
        $res=$dbconn->Execute($query);
    if($dbconn->ErrorNo() != 0){
            $this->error = "Error al seleccionar el serial de system_usuarios_codigo_alterno";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
        }
        if(!empty($res->fields[0])){
            $codigoAlterno="'".$res->fields[0]."'";
        }else{
            $codigoAlterno='NULL';
        }


        if($comparador_de_insercion==1)
        {

                if($_SESSION['USER']['DIAS']==0)
                {
                    //quiere decir que no caduca la contrasena
                    //generamos el passwd

                     $query = "INSERT INTO system_usuarios(
                                                                        usuario_id,
                                                                        usuario,
                                                                        nombre,
                                                                        descripcion,
                                                                        activo,
                                                                        sw_admin,
                                                                        caducidad_contrasena,
                                                                        passwd,
                                                                        codigo_alterno
                                                                        )
                                                                        VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0',".$_SESSION['USER']['DIAS'].",
                                                                        '".UserEncriptarPasswd('siis')."',$codigoAlterno)";
                    //esta variable no me manda a resetear el passwd
                    //siempre y cuando  inserte el usuario con la opcion que no caduque su contrasena
                    $SW_NO_CADUCA=1;

                }else{

                                    $query = "INSERT INTO system_usuarios(
                                                                        usuario_id,
                                                                        usuario,
                                                                        nombre,
                                                                        descripcion,
                                                                        activo,
                                                                        sw_admin,
                                                                        caducidad_contrasena,
                                                                        fecha_caducidad_contrasena,
                                                                        codigo_alterno
                                                                        )
                                                                        VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0',".$_SESSION['USER']['DIAS'].",
                                                                        '$fecha_contraseña',$codigoAlterno)";
                        }
        }
        else
        {

                if($_SESSION['USER']['DIAS']==0)
                {
                     $query = "INSERT INTO system_usuarios(
                                        usuario_id,
                                                                usuario,
                                                                nombre,
                                                                descripcion,
                                                                activo,
                                                                sw_admin,
                                                                fecha_caducidad_cuenta,
                                                                caducidad_contrasena,
                                                                codigo_alterno
                                                                )
                                                                VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0','$fechacaduca', ".$_SESSION['USER']['DIAS'].",$codigoAlterno)";
                }else{

                                    $query = "INSERT INTO system_usuarios(
                                                                                    usuario_id,
                                                                                    usuario,
                                                                                    nombre,
                                                                                    descripcion,
                                                                                    activo,
                                                                                    sw_admin,
                                                                                    fecha_caducidad_cuenta,
                                                                                    caducidad_contrasena,
                                                                                    fecha_caducidad_contrasena,
                                                                                    codigo_alterno
                                                                                    )
                                                                                    VALUES($serial,'$loginUsuario','$nombreUsuario','$descripcion','$activo','0','$fechacaduca', ".$_SESSION['USER']['DIAS'].",
                                                                                    '$fecha_contraseña',$codigoAlterno)";
                            }
        }
        //echo $query;exit;
        $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en system_usuarios";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
            }
             $query = "INSERT INTO system_usuarios_empresas(
                                        usuario_id,
                                                                empresa_id,
                                                                sw_activo
                                                                )
                                                                VALUES($serial,'$empresa','$sw_empresa')";
        $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en system_usuarios_empresas";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
            }

            //insertamos el link de configuracion del usuario cada vez que se guarde
            //por primera vez..
            //45 es CONFIGURACION DEL USUARIO.
            $query = "INSERT INTO system_usuarios_menus(
                                        usuario_id,
                                                                menu_id
                                                                )
                                                                VALUES($serial,45)";
        $dbconn->Execute($query);
    if ($dbconn->ErrorNo() != 0) {
          $this->error = "Error al Guardar en system_usuarios_menus";
          $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
          return false;
            }

     if(!empty($tema))
        {
                UserSetVar($serial,'Tema',$tema);
            }

        if($SW_NO_CADUCA!=1)
        {
            UserResetPasswd($serial);
        }
        $dbconn->CompleteTrans();   //termina la transaccion
        $this->ListadoUsuariosSistema();
      return true;

  } //final funcion....


}//fin clase user

?>


