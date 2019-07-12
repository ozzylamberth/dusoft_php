<?php
/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Jairo Duvan Diaz Martinez
* ultima actualizacion: Jairo Duvan Diaz Martinez -->lunes 1 de marzo 2004
*/
// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar la administracion de usuarios
*/


class system_Usuario_Config_userclasses_HTML extends system_Usuario_Config_user
{
    /**
    *Constructor de la clase app_Usuarios_user_HTML
    *El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
    *a la clase app_Usuarios_user quien se encarga de el tratamiento
    * de la base de datos.
    */

  function system_Usuario_Config_user_HTML()
    {
        $this->salida='';
        $this->system_Usuario_Config_user();
        return true;
    }

/**
* Funcion donde se visualiza la forma de la configuración del usuario
* @return boolean
*/

    function FormaConfigUsuarioSistema($action)
    {
        $dats=$this->TraerUsuario();
        $this->salida  = ThemeAbrirTabla('CONFIGURACION DE USUARIO:&nbsp; '.$dats[0][usuario].'');
        $this->salida .= "                <br><br>";
        $this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
        $this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"60%\" align=\"center\">";
        $this->salida .= "              <tr><td class=\"modulo_table_title\" width=\"23%\">Usuario :</td><td class=\"modulo_list_claro\">".$dats[0][nombre]."</td></tr>";
        $this->salida .= "  <tr><td class=\"modulo_table_title\" width=\"20%\">Login :</td><td class=\"modulo_list_claro\">".$dats[0][usuario]."</td></tr></table>";
        $this->salida .= "                <table width=\"60%\" border=\"0\" align=\"center\">";
        $this->salida .= "            <tr><td>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "            </td></tr>";
        $this->salida .= "            <tr><td>";
        $this->salida .= "              <fieldset><legend class=\"field\">CONFIGURACION</legend>";
        $this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\" border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida.= "               <input type=\"hidden\" name=\"action\" value=\"$action\">";
        $archivos=$this->listarDirectorios();
        $uid=UserGetUID();
        $tema=$this->RevisarTema($uid);
        $this->salida .= "                     <tr><td class=\"".$this->SetStyle("tema")."\">TEMA: </td><td class=\"modulo_list_oscuro\"><select name=\"tema\" class=\"select\">";

        if(empty($tm))
        {
            $this->salida .=" <option value=\"-1\">Default</option>";
            for($i=0;$i<sizeof($archivos);$i++)
            {
                if($archivos[$i]==$tema)
                {
                    $this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
                }
                else
                {
                    $this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
                }
            }
        }
        $this->salida .= "       </select></td></tr>";
        $this->salida .= "                     <tr><td class=\"".$this->SetStyle("descripcion")."\">NUMERO DE REGISTROS X CONSULTA: </td><td class=\"modulo_list_oscuro\"><select name=\"barra\" class=\"select\">";
        $numero=$this->TraerBarra();

        for($i=1;$i<101;$i++)
        {
          if($i!=$numero)
            {
                 $this->salida .= "<option value=\"$i\">$i</option>";
            }
            else
            {
                $this->salida .= "<option value=\"$i\" selected>$i</option>";
            }
        }

        $i=1;
        $this->salida .= "</select></td></tr>";
        $this->salida .= "                   </table>";
        $this->salida .= "                 </fieldset></td></tr>";

        $this->salida .= "  <table width=\"40%\" align=\"center\">";
        $this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Aceptar\"><br></td>";
        $this->salida .= "                </form>";
        $action3=ModuloGetURL('system','Usuario_Config','user','main',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
        $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
        $this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
        $this->salida .= "                </form>";
        $this->salida .= "            </table><BR><BR>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }


    function SetStyle($campo)
    {
                if ($this->frmError[$campo] || $campo=="MensajeError"){
                    if ($campo=="MensajeError"){
                        return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
                    }
                    return ("label_error");
                }
            return ("label");
    }



 /**
* Funcion donde se visualiza la forma que pide datos para modificar
* el password de un usuario.
* @return boolean
*/

  function FormaModificarPasswd($action){
            $dats=$this->TraerUsuario();
            $this->salida  = ThemeAbrirTabla('CAMBIO CONTRASEÑA :  '.$dats[0][usuario].'');
            $this->salida .= "                <br><br>";
            $this->salida .= "           <form name=\"formaContraseña\" action=\"$action\" method=\"post\">";
            $this->salida .= "                <table width=\"80%\" border=\"0\" align=\"center\">";
            $this->salida .= "            <tr><td>";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "            </td></tr>";
            $this->salida .= "            <tr><td>";
            $this->salida .= "              <fieldset><legend class=\"field\">DATOS CONTRASEÑA</legend>";
            $this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "              <tr><td class=\"modulo_table_title\" width=\"23%\">Usuario :</td><td class=\"modulo_list_claro\">".$dats[0][nombre]."</td></tr>";
            $this->salida .= "  <tr><td class=\"modulo_table_title\" width=\"20%\">Login :</td><td class=\"modulo_list_claro\">".$dats[0][usuario]."</td></tr>";
    if(empty($_SESSION['PWD']))
        {
                $nom='verificar';
                $dat='Verificar';
                $this->salida .= "                     <tr  class=\"modulo_table_title\"><td width=\"30%\" class=\"".$this->SetStyle("password")."\">Repita su Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input type=\"password\" class=\"input-text\" name=\"viejopass\" maxlength=\"40\"></td></tr>";
        }
        else
        {
          $nom='aceptar';
       $dat='Cambiar';
            $this->salida .= "                     <tr  class=\"modulo_table_title\"><td width=\"30%\" class=\"".$this->SetStyle("password")."\">Nuevo Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input type=\"password\" class=\"input-text\" name=\"password\" maxlength=\"40\" value=\"$password\"></td></tr>";
        $this->salida .= "                     <tr class=\"modulo_table_title\"><td width=\"30%\" class=\"".$this->SetStyle("passwordReal")."\">Repita Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input  type=\"password\" class=\"input-text\" name=\"passwordReal\" maxlength=\"40\" value=\"$passwordReal\"></td></tr>";

        }
            $this->salida .= "                <input type=\"hidden\" name=\"usuario\" value=\"".$dats[0][usuario]."\">";
            $this->salida .= "                <input type=\"hidden\" name=\"nombre\" value=\"".$dats[0][nombre]."\">";
            $this->salida .= "                <input type=\"hidden\" name=\"viejo\" value=\"".$dats[0][passwd]."\">";
            $this->salida .= "                   </table>";
            $this->salida .= "                 </fieldset></td></tr>";
            $this->salida .= "                <table width=\"40%\"  border=\"0\" align=\"center\">";
            $this->salida .= "              <tr><td align=\"center\"><br><input class=\"input-submit\" name=\"$nom\" type=\"submit\" value=\"$dat\"></td>";
            $this->salida .= "                </form>";

            $action3=ModuloGetURL('system','Usuario_Config','user','Menu',array("uid"=>$uid));
            $this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Volver\"></td></tr>";
            //$this->salida .= "            </table>";
            $this->salida .= "            </table><BR>";
            $this->salida .= "                </form>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }




    /*refrescando pantalla, esta funcion es simplemente experimental.............*/
 function RefrescarPantalla($mensaje='')
  {
    $this->salida  = "<div align=\"center\" class='titulo1'>\n";
    $this->salida .= "Un momento por favor<br>$mensaje\n";
    $this->salida .= "</div><br><br>\n";

    if(SessionGetVar('StyleFrames')){
      $this->salida .=  "\n\n<script language=\"javascript\">setTimeout('top.location.reload()',1500);</script>\n\n";
    }else{
      $this->salida .=  "\n\n<script language=\"javascript\">setTimeout('reload()',1500);</script>\n\n";
    }
        //$this->main();
    return true;
  }
/**************************************esta es una funcion experimental**************/


/*
        funcion que le muestra al usuario las impresoras que tiene predeterminadas
*/
function FormaImpresorasPredeterminadas()
{
            $vector3=$this->TraerImpresorasPrdeterminadas();
            $this->salida  = ThemeAbrirTabla('IMPRESORAS PREDETERMINADA DE LA IP:&nbsp;'.GetIPAddress().'');

        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
                        $this->uno="";
        }

            if($vector3)
            {
                $dats=$this->TraerUsuario();
                    $this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"50%\">";
                    $action2=ModuloGetURL('system','Usuario_Config','user','GuardarDatos',array('vector'=>$vector3));
                    $this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"left\" colspan=\"2\">CONTROL IMPRESORAS DE: &nbsp;".$dats[0][usuario]."</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"40%\">IMPRESORA</td>";
                    $this->salida.="  <td width=\"10%\">ESTADO</td>";
                    //$this->salida.="  <td width=\"5%\"></td>";
                    for($i=0;$i<sizeof($vector3);$i++)
                    {
                            if( $i % 2){ $estilo='modulo_list_claro';}
                            else {$estilo='modulo_list_oscuro';}

                            $estado=$vector3[$i][sw_predeterminada];
                            $this->salida.="<tr class=$estilo>";
                            $this->salida.="  <td align=\"center\" ><b>".$vector3[$i][impresora]."</b></td>";
                            if($estado==1)
                                $this->salida.="  <td align=\"center\" ><label class=label_mark>Predeterminada</label></td>";
                            else
                            if($estado==0)
                            {
                                $this->salida.="<td align=\"center\">";
                                $this->salida.="<input type=\"checkbox\" name=\"dx$i\" value=\"1\">";
                                //$this->salida.="<input type=\"radio\" name=\"op\" value=\"0\">";
                                $this->salida.="</td>";
                            }
/*                          else
                                $this->salida.="  <td align=\"center\" ><input type=\"radio\" name=\"op\" value=\"0\"></td>";*/
                    }
                    $this->salida.="</table>";

            }
            else
            {
                $this->salida.="<table  align=\"center\" border=\"0\" width=\"50%\">";
                $this->salida.="<tr >";
                $this->salida.="  <td class='label_mark' align=\"left\" colspan=\"3\">LA DIRECCION &nbsp;".GetIPAddress()."  NO TIENE IMPRESORAS PREDETERMINADAS</td>";
                $this->salida.="</tr>";
            }
            $this->salida.="<table align=\"center\" width='20%' border=\"0\">";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"CAMBIAR PRED\"><input class=\"input-submit\" name=\"eliminar\" type=\"submit\" value=\"ELIMINAR\"></form></td>";
/*                  $action=ModuloGetURL('system','Usuario_Config','user','GuardarDatos',array('vector'=>$vector3));
                    $this->salida .= "<form name=\"formados\" action=\"$action\" method=\"post\">";
            $this->salida .= "</form>";*/
            $this->salida .= "</tr>";
            $this->salida.="</table><br>";
//
    $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
    $dats=$this->PrinterSystem();
    if(is_array($dats))
        {
                $this->salida.="<tr><td><br>";




                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
                $this->salida .='<form name="forma" action="'.ModuloGetURL('system','Usuario_Config','user','InsertarImpresoras',array('dats'=>$dats)).'" method="post">';

                $this->salida.= "<tr class=\"modulo_table_list_title\"><td width=\"100%\" colspan=\"4\">IMPRESORAS DEL SISTEMA</td></tr>";
                $this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="  <td align=\"center\" width=\"3%\">SEL</td>";
                $this->salida.="  <td align=\"center\" width=\"17%\">IMPRESORA</td>";
                $this->salida.="  <td align=\"center\" width=\"40%\">DESCRIPCIÓN</td>";
                $this->salida.="  <td align=\"center\" width=\"40%\">UBICACIÓN</td>";
                $this->salida.="</tr>";
                //esta variable de session contiene los datos del menus,los menus
                //del usuario..
                for($i=0;$i<sizeof($dats);$i++)
                {
                    $impresora=$dats[$i][impresora];
                    $descripcion=$dats[$i][descripcion];
                    $ubicacion=$dats[$i][ubicacion];
                    //for($j=0;$j<sizeof($vector3);$j++)
                    //{
                        /*if(is_null($user))
                        {
                            $imagen='checkN.gif';
                            $estilo2="";
                        }
                        else
                        {
                            $imagen='checkS.gif';
                            $estilo2="label";
                        }
        $j=0;
        for($i=0; $i<sizeof($atributos); $i++)
        {
              $n=0;
              while($n<sizeof($fk2))
              {
                      if ($fk2[$n]!=$atributos[$j]['nombre_campo'])
                      {
                                $x=false;
                                $f=0;
                                while($f<sizeof($fk2))
                                {
                                        if ($fk2[$f]==$atributos[$j]['nombre_campo'])
                                        {
                                            $x=true;
                                        }
                                            $f++;
                                }

                                if($x==false)
                                {
                                    //CICLO PARA EXTRAER EL TIPO DE DATO PARA CADA CAMPO
                                    for($k=0; $k<sizeof($tipos_campos);$k++)
                                    {
                                        if ($atributos[$j][nombre_campo]==$tipos_campos[$k][nombre_campo])
                                        {
                                            $this->salida .= "<tr><td class=\"".$this->SetStyle("".$atributos[$j]['nombre_campo']."")."\">".$atributos[$j]['nombre_campo'].": </td><td>".$tipos_campos[$k][tipo_campo]."</td><td><input type=\"text\" class=\"input-text\" name=\"campo".$i."\" value='".$_REQUEST["campo".$i]."' size=\"35\" maxlength=\"55\"></td></tr>";
                                            $n=sizeof($fk2);
                                        }
                                    }
                                }
                            }
                $n++;
                }
            $j++;
        }
*/
                        if( $i % 2){ $estilo='modulo_list_claro';}
                        else {
                        $estilo='modulo_list_oscuro';}
                        $this->salida.="<tr class=\"$estilo\" align=\"center\">";
                        //$this->salida.="  <td class=\"$estilo2\" align=\"left\"><a title='<p><b>$menu :</b></p>$desc' href=\"".ModuloGetURL('system','Usuarios','admin','InsertarPermisoMenu',array('menu'=>$id,'uid'=>$uid,'usuario'=>$usuario,'nombre'=>$nombre,'descripcion'=>$descripcion,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']))."\"><font color='black'>$menu</font></a></td>";
/*                      if($vector3[$j][impresora]==$impresora)
                        {
                                $this->salida.="    <td align=\"center\" width=\"3%\"><input disabled=true type=checkbox name=\"print".$i."\"></td>";
                                $this->salida.="    <td align=\"left\" width=\"17%\">$impresora</td>";
                                $this->salida.="    <td align=\"left\" width=\"40%\">$descripcion</td>";
                                $this->salida.="    <td align=\"left\" width=\"40%\">$ubicacion</td>";
                                $this->salida.="</tr>";
                                //$j=sizeof($vector3);
                        }
                        else
                        //if ($i==0)
                        {*/
                                $this->salida.="    <td align=\"center\" width=\"3%\"><input type=checkbox name=\"print".$i."\"></td>";
                                $this->salida.="    <td align=\"left\" width=\"17%\"><label class=label><font color='black'>$impresora</font></label></td>";
                                $this->salida.="    <td align=\"left\" width=\"40%\">$descripcion</td>";
                                $this->salida.="    <td align=\"left\" width=\"40%\">$ubicacion</td>";
                                $this->salida.="</tr>";
//                      }
                    //}
                }
                $this->salida.="</table>";
                $this->salida.="</td></tr>";
        }
                $this->salida.="</table>";
                $this->salida.="<table align=\"center\">";
                $this->salida.="<tr>";
                $this->salida.="  <td align=\"center\">";
                //$this->salida .='<form name="forma" action="'.ModuloGetURL('system','Usuario_Config','user','LlamaImpresora',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda'])).'" method="post">';
                $this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"GUARDAR PRN\" class=\"input-submit\"></form>";
                $this->salida .='<form name="forma" action="'.ModuloGetURL('system','Usuario_Config','user','main').'" method="post">';
                $this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"VOLVER\" class=\"input-submit\"></form></td>";
                $this->salida.="</form>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
//

//
            $this->salida .= ThemeCerrarTabla();
    return true;
}








     /**
* Funcion donde se visualiza el menu de usuario.
* @return boolean
*/
    function Menu()
 {   //echo  mail("192.168.1.16", "jaja", "eres \n un \n patron");
      unset($_SESSION['PWD']);
            $this->salida.= ThemeAbrirTabla('CONFIGURACIÓN DE USUARIOS');
        $this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
            $this->salida.="<tr>";
        $this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE USUARIOS</td>";
            $this->salida.="</tr>";
            $ir_otra_confg=ModuloGetURL('system','Usuario_Config','user','LlamaConfigUsuarioSistema');
            $ir_cambio_contrasena=ModuloGetURL('system','Usuario_Config','user','LlamaFormaModificarPasswd');
            $ir_impresora=ModuloGetURL('system','Usuario_Config','user','LlamaImpresora');


      $this->salida.="<tr>";
            $this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$ir_cambio_contrasena\">CAMBIAR CONTRASEÑA DE USUARIO</a>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr>";
            $this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ir_impresora\">IMPRESORAS DEL USUARIO</a>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="<tr>";
            $this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ir_otra_confg\">OTRAS CONFIGURACIONES DE USUARIO</a>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            $this->salida.="<table align=\"center\" width='20%' border=\"0\">";
            $action2=ModuloGetURL('system','Menu','user','main');
            $this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
            $this->salida .= "</tr>";
            $this->salida.="</table><br>";
            $this->salida.= ThemeCerrarTabla();
            return true;
 }

//
    function PermisosMenuUsuario($dats,$uid,$nombre,$usuario,$descripcion)
 {
    $this->salida .= ThemeAbrirTabla('MENUS DEL USUARIO');
    //$this->salida .= "<br>";
    $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
    if($dats)
                {

                            $this->salida .= "            <tr><td>";
                            $this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
                            $this->salida .= "                     <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">".$usuario."</td></tr>";
                            $this->salida .= "                     <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">".$nombre."</td></tr>";
            if(!empty($descripcion))
            {
                                $this->salida .= "                     <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">DESCRIPCIÓN: </td><td class=\"modulo_list_oscuro\" align=\"left\">".$descripcion."</td></tr>";
                            }
                            $this->salida .= "                   </table>";
                            $this->salida .= "            </tr></td>";

                            $this->salida.="<tr><td><br>";




                            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";

                            $this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td></td>";
                            $this->salida.="  <td>MODULO</td>";
                            $this->salida.="  <td></td>";
                            $this->salida.="  <td>MODULO</td>";
                            $this->salida.="  <td></td>";
                            $this->salida.="  <td>MODULO</td>";
                            $this->salida.="</tr>";
                            //esta variable de session contiene los datos del menus,los menus
                            //del usuario..
                            for($i=0;$i<sizeof($dats);$i++)
                            {
                  $user=$dats[$i][usuario_id];
                                $id=$dats[$i][menu_id];
                                    $menu=$dats[$i][menu_nombre];
                                    $desc=$dats[$i][descripcion];
                                    if(is_null($user))
                                    {
                                        $imagen='checkN.gif';
                                        $estilo2="";
                                    }
                                    else
                                    {
                                        $imagen='checkS.gif';
                                        $estilo2="label";
                                    }
                                    if( $i % 2){ $estilo='modulo_list_claro';}
                                    else {
                                    $estilo='modulo_list_oscuro';
                }


                                    if( $i % 3){
                                    }else{$this->salida.="<tr class=\"$estilo\" align=\"center\">";}
                                  $this->salida.="  <td><img src=\"".GetThemePath()."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\"></td>";

                                    //<a href=\"".ModuloGetURL('system','Usuarios','admin','InsertarPermisoMenu',
                                    //array("menu"=>$id,"uid"=>$uid,"usuario"=>$usuario,"nombre"=>$nombre,"descripcion"=>
                                    //$descripcion,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],"var"=>$dats))."\">
                  //esto es el link de los iconos preguntar esto...despues a ver si asi queda solucionado.

                                    $this->salida.="  <td class=\"$estilo2\" align=\"left\"><a title='<p><b>$menu :</b></p>$desc' href=\"".ModuloGetURL('system','Usuarios','admin','InsertarPermisoMenu',array('menu'=>$id,'uid'=>$uid,'usuario'=>$usuario,'nombre'=>$nombre,'descripcion'=>$descripcion,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']))."\"><font color='black'>$menu</font></a></td>";
                                    //$this->salida.="  <td  align=\"left\">$desc</td>";

                                    if($i > 2)
                                    {
                                        if( !$i % 3){$this->salida.="</tr>";
                                        }
                                    }
                            }
                            $this->salida.="</table>";
                            $this->salida.="</td></tr>";
                }
                $this->salida.="</table>";

                $this->salida.="<table align=\"center\">";
                $this->salida.="<tr>";
                $this->salida.="  <td align=\"center\">";
                $this->salida .='<form name="forma" action="'.ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda'])).'" method="post">';
                $this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida .= ThemeCerrarTabla();
  return true;
 }

//
}//fin clase user
?>

