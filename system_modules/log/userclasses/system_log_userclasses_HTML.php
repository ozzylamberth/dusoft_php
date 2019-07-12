<?php

class system_log_userclasses_HTML extends system_log_user
{

    function system_log_userclasses_HTML()
    {
      $this->system_log_user(); //Constructor del padre 'modulo'
        $this->salida='';
        return true;
    }

    function frmLogin($usuario='',$mensaje='')
    {

        if($_REQUEST['reset_log'])
        {
            $mensaje='La sesión expiro.';
        }

        $action = ModuloGetURL('system','log','user','validarLogin');
        $homepage = ModuloGetURL();
        $this->salida = ThemeAbrirTabla("","50%");
        $this->salida .= "<table width='100%' height='100%' cellspacing=4 border=0 cellpadding=0><tr><td align='center' valign='center'>\n";
        $this->salida .= "  <div align='center' class='titulo1'>Autenticación del Usuario</div>\n";
        $this->salida .= "  <div align='center' class='label_error'>&nbsp;$mensaje&nbsp;</div><br />\n";
        $this->salida .= "  <form name='login_form' action='$action' method='POST'>\n";
        $this->salida .= "    <table cellspacing=4 border=1 cellpadding=4 align='center'>\n";
        $this->salida .= "      <tr><td class='titulo2'>Usuario</td><td><input type='text' name='usuario' value='$usuario' size=15 maxlength=25 alt='Ingrese el nombre de usuario del sistema'></td></tr>\n";
        $this->salida .= "      <tr><td class='titulo2'>Contraseña</td><td><input type='password' name='passwd' size=15 maxlength=40></td></tr>\n";
        $this->salida .= "    </table><br />\n";
        $this->salida .= "    <input type='submit' value='Aceptar'>\n";
        $this->salida .= "  </form>\n";
        $this->salida .= "  <br /><br />\n";
        $this->salida .= "  <a href='$homepage'>Página Inicial</a>\n";
        $this->salida .= "</td></tr></table>\n";
        $this->salida .= "<script language='javascript'>\n";
        $this->salida .= "   <!--\n";
        $this->salida .= "    var uname = document.login_form.usuario;\n";
        $this->salida .= "     var pword = document.login_form.passwd;\n";
        $this->salida .= "    if (uname.value == '') {\n";
        $this->salida .= "      uname.focus();\n";
        $this->salida .= "     } else {\n";
        $this->salida .= "       pword.focus();\n";
        $this->salida .= "    }\n";
        $this->salida .= "   -->\n";
        $this->salida .= " </script>\n";

        if($_REQUEST['reset_log'])
        {
            $this->salida .= RefrescarCabecera();
        }

        $this->salida .= themeCerrarTabla();

        return true;
    }



     function frmCambiarPWD($usuario_id='',$usuario='',$mensaje='')
    {
    $action = ModuloGetURL('system','log','user','validarCambioPass',array('usuario_id'=>$usuario_id));
    $homepage = ModuloGetURL();

    $this->salida  = "<table width='100%' height='100%' cellspacing=4 border=0 cellpadding=0><tr><td align='center' valign='center'>\n";
    $this->salida .= "  <div align='center' class='titulo1'>Password de Usuario <font color='red'>!Caducado</font></div>\n";
    $this->salida .= "  <div align='center' class='label_error'>&nbsp;$mensaje&nbsp;</div>\n";
    $this->salida .= "  <form name='login_form' action='$action' method='POST'>\n";
    $this->salida .= "    <br><table cellspacing=4 border=1 cellpadding=4 align='center'>\n";
        $this->salida .= "      <tr><td class='titulo2'>Nueva Contraseña</td><td><input type='password' name='passwd' value='$usuario' size=15 maxlength=25 alt='Ingrese el nombre de usuario del sistema'></td></tr>\n";
    $this->salida .= "      <tr><td class='titulo2'>Repita Contraseña</td><td><input type='password' name='rpasswd' size=15 maxlength=40></td></tr>\n";
    $this->salida .= "    </table><br />\n";
    $this->salida .= "    <input type='submit' value='Guardar'>\n";
    $this->salida .= "  </form>\n";
    $this->salida .= "  <br /><br />\n";
    $this->salida .= "  <a href='$homepage'>Página Inicial</a>\n";
    $this->salida .= "</td></tr></table>\n";
    $this->salida .= "<script language='javascript'>\n";
    $this->salida .= "   <!--\n";
    $this->salida .= "    var pwd = document.login_form.passwd;\n";
    $this->salida .= "     var rpwd = document.login_form.rpasswd;\n";
    $this->salida .= "    if (pwd.value == '') {\n";
    $this->salida .= "      pwd.focus();\n";
    $this->salida .= "     } else {\n";
    $this->salida .= "       rpwd.focus();\n";
    $this->salida .= "    }\n";
    $this->salida .= "   -->\n";
    $this->salida .= " </script>\n";
     return true;
    }





    function frmConfirmarLogout()
    {
    $homepage = ModuloGetURL();
    $action = ModuloGetURL('system','log','user','logout');
    $user_vars = UserGetVars(UserGetUID());
    $mensaje = "Existe una sesión activa";
    if(!empty($user_vars['usuario'])){
        $mensaje .= " del usuario $user_vars[usuario]";
        if(!empty($user_vars['nombre'])){
            $mensaje .= " ($user_vars[nombre])";
        }
    }
    $mensaje .= "<br /><br />Desea cerrarla?";

    $this->salida  = "<table width='100%' height='100%' cellspacing=4 cellpadding=4 align='center'>\n";
    $this->salida .= "<tr><td align='center' valign='center'>\n";
    $this->salida .= "  <div align=\"center\" class='titulo1'>\n";
    $this->salida .= "    $mensaje\n";
    $this->salida .= "  </div>\n";
    $this->salida .= "  <form name='frm_confirmar' action='$action' method='POST'>\n";
    $this->salida .= "    <table cellspacing=8 cellpadding=8 align='center'>\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "    <td align='center'><input type='submit' value='Cerrar la sesión activa'></td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "    <tr>\n";
    $this->salida .= "    <td align='center'><a href='$homepage'>Página Inicial</a></td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "    </table>\n";
    $this->salida .= "  </form>\n";
    $this->salida .= "</td></tr>\n";
    $this->salida .= "</table>\n";

    return true;
  }

  function frmSeleccionarEmpresas($usuario_id)
  {
    $action = ModuloGetURL('system','log','user','loguin_full');
    $homepage = ModuloGetURL();

    $departamento_default=UserGetVar($usuario_id,'departamento_default');

    if(!empty($departamento_default)){

        $query = "SELECT DISTINCT a.empresa_id
                    FROM system_usuarios_empresas as a, system_usuarios_departamentos as b, departamentos as c
                    WHERE c.empresa_id = a.empresa_id AND
                          c.departamento = b.departamento AND
                          b.departamento = '".$departamento_default."' AND
                          a.usuario_id = b.usuario_id AND
                          a.usuario_id = $usuario_id";

        list($dbconn) = GetDBconn();
        $result = $dbconn->Execute($query);

        if($dbconn->ErrorNo() == 0) {
          if (!$result->EOF) {
            list($empresa_default) = $result->FetchRow();
            $result->Close();
          }
        }
    }

    $this->salida  = "<table width='100%' height='100%' cellspacing=4 border=0 cellpadding=0><tr><td align='center' valign='center'>\n";
    $this->salida .= "<div align='center' class='titulo1'>Seleccione una Empresa y un Departamento<br />para iniciar sesión.</div>\n";
    $this->salida .= "<br />\n";

    $empresas = UserGetEmpresas($usuario_id);
    if(!empty($empresas) && is_array($empresas)){

      $this->salida .= "  <form name='login_form' action='$action' method='POST'>\n";


      $this->salida.="<script>\n";
      $this->salida.="function SetDpto(forma) {\n";

      foreach($empresas as $empresa_id=>$empresa_nombre){
        $this->salida.="if (forma.value=='$empresa_id') {\n";
        $dpto = UserGetDepartamentos($usuario_id,$empresa_id);
        $cont=0;
        $this->salida.="document.login_form.departamento.length=".count($dpto)."\n";
        foreach($dpto as $dpto_id=>$dpto_nombre){
            $this->salida.="document.login_form.departamento.options[".$cont."]= new Option('$dpto_nombre','$dpto_id');\n";
            $cont++;
        }
        $this->salida.="}\n\n";
      }
      $this->salida.="}\n\n";
      $this->salida.="</script>\n";

      $this->salida .= "<table border=0 cellspacing=4 cellpadding=4 align='center'>\n";
      $this->salida .= "<tr><td align='left' class='titulo2'>Empresa</td>\n";
      $this->salida .= "<td align='left'><select name='empresa' OnChange='SetDpto(this)'>\n";
      reset($empresas);
      foreach($empresas as $empresa_id  =>$empresa_nombre){
        if(empty($codempresa)){
          $codempresa=$empresa_id;
        }
        if($empresa_id==$empresa_default){
          $this->salida .= "<option value=\"$empresa_id\" selected>$empresa_nombre</option>\n";
          $codempresa=$empresa_id;
        }else{
          $this->salida .= "<option value=\"$empresa_id\">$empresa_nombre</option>\n";
        }
      }
      $this->salida .= "</select></td>\n";
      $this->salida .= "</tr>\n";


      $this->salida .= "<tr><td align='left' class='titulo2'>Departamento</td>\n";
      $this->salida .= "<td align='left'><select name='departamento'>\n";

      $departamento = UserGetDepartamentos($usuario_id,$codempresa);
      foreach($departamento as $departamento_id=>$departamento_nombre){
        if(empty($departamento_actual)){
          $departamento_actual=$departamento_id;
        }
        if($departamento_id==$departamento_default){
          $this->salida .= "<option value=\"$departamento_id\" selected>$departamento_nombre</option>\n";
          $departamento_actual=$departamento_id;
        }else{
          $this->salida .= "<option value=\"$departamento_id\">$departamento_nombre</option>\n";
        }
      }
      $this->salida .= "</select></td>\n";
      $this->salida .= "</tr>\n";


      $this->salida .= "<tr><td>&nbsp;</td>\n";
      $this->salida .= "<td align='left'><input type='submit' value='Seleccionar'><input type= \"hidden\" name=\"usuario\" value=\"$usuario_id\"></td>\n";
      $this->salida .= "</tr>\n";
      $this->salida .= "</table>\n";

      $this->salida .= "  </form><br />\n";
    }

    $ultimo_dpto_login = UserGetVar($usuario_id,'ultimo_dpto_login');
    if($ultimo_dpto_login != $departamento_actual){

    $query ="SELECT a.empresa_id, d.razon_social, e.centro_utilidad, e.descripcion, f.unidad_funcional, f.descripcion, c.departamento, c.descripcion
              FROM system_usuarios_empresas as a, system_usuarios_departamentos as b, departamentos as c, empresas as d, centros_utilidad as e, unidades_funcionales as f
              WHERE c.empresa_id = a.empresa_id AND
              d.empresa_id = a.empresa_id AND
              c.departamento = b.departamento AND
              b.departamento = '$ultimo_dpto_login' AND
              e.empresa_id = d.empresa_id AND
              e.centro_utilidad = c.centro_utilidad AND
              f.empresa_id = d.empresa_id AND
              f.centro_utilidad  = e.centro_utilidad AND
              f.unidad_funcional = c.unidad_funcional AND
              a.usuario_id = b.usuario_id AND
              a.usuario_id = $usuario_id";


      list($dbconn) = GetDBconn();
      $result = $dbconn->Execute($query);
      if($dbconn->ErrorNo() == 0) {
        if(!$result->EOF) {

            list($empresa_id,$empresa_nombre,$centro_utilidad, $centro_utilidad_nombre, $unidad_funcional, $unidad_funcional_nombre, $departamento, $departamento_nombre) = $result->FetchRow();
            $result->Close();
            $action_dafault = ModuloGetURL('system','log','user','loguin_full', array('usuario'=>$usuario_id, 'empresa'=>$empresa_id, 'departamento'=>$departamento));
            $this->salida .= "  <form name='default_dpto' action='$action_dafault' method='POST'>\n";
            $this->salida .= "<table cellspacing=4 cellpadding=4 align=\"center\" border=\"1\" class=\"modulo_table\">\n";
            $this->salida .= "  <tr><td align=\"center\" class='titulo2'>Ultima Sesión de Trabajo</tr></td>\n";
            $this->salida .= "  <tr>\n";
            $this->salida .= "    <td>\n";
            $this->salida .= "      <table cellspacing=0 cellpadding=2 align=\"center\" border=\"0\" class='normal'>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td class='label'>Empresa</td><td>:</td><td>$empresa_id</td><td> - </td><td>$empresa_nombre</td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td class='label'>Centro de Utilidad</td><td>:</td><td>$centro_utilidad</td><td> - </td><td>$centro_utilidad_nombre</td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td class='label'>Unidad Funcional</td><td>:</td><td>$unidad_funcional</td><td> - </td><td>$unidad_funcional_nombre</td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td class='label'>Departamento</td><td>:</td><td>$ultimo_dpto_login</td><td> - </td><td>$departamento_nombre</td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "        <tr>\n";
            $this->salida .= "          <td colspan=5 align=\"center\"><input type=\"submit\" value=\"Seleccionar\"></td>\n";
            $this->salida .= "        </tr>\n";
            $this->salida .= "      </table>\n";
            $this->salida .= "    </td>\n";
            $this->salida .= "  </tr>\n";
            $this->salida .= "</table>\n";
            $this->salida .= "  </form>\n";
        }
      }
    }

    $this->salida .= "</td></tr></table>\n";


    return true;
  }


  function frmRefrescar($mensaje='')
  {
    $this->salida  = "<div align=\"center\" class='titulo1'>\n";
    $this->salida .= "Un momento por favor<br>$mensaje\n";
    $this->salida .= "</div>\n";

    if(SessionGetVar('StyleFrames'))
        {
     //$this->salida .=  "\n\n<script language=\"javascript\">setTimeout('top.location.reload(false)',500);</script>\n\n";
     $this->salida .=  "\n\n<script language=\"javascript\">setTimeout(\"top.location ='index.php' \",500);</script>\n\n";
    }
        else
        {
     //$this->salida .=  "\n\n<script language=\"javascript\">setTimeout('reload()',500);</script>\n\n";
     $this->salida .=  "\n\n<script language=\"javascript\">setTimeout(\"top.location ='index.php' \",500);</script>\n\n";
    }
    return true;
  }

  function frmHostLock()
  {
    $this->salida .= MsgOut('EQUIPO BLOQUEADO','El equipo acaba de ser bloqueado por razones de seguridad, consulte al Administrador');
    return true;
  }

  function frmNoDptos()
  {
    $this->salida .= MsgOut('EL USUARIO NO PUEDE INICIAR SESION','El usuario no tiene Departamentos asignados para trabajar en el sistema.');
    return true;
  }

  function frmNoEmpresas()
  {
    $this->salida .= MsgOut('EL USUARIO NO PUEDE INICIAR SESION','El usuario no tiene Empresa(s) con Departamentos asignados para trabajar en el sistema.');
    return true;
  }

}//fin de la class


?>

