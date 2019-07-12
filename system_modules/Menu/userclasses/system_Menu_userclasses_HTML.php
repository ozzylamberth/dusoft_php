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


class system_Menu_userclasses_HTML extends system_Menu_user
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_Menu_user_HTML()
	{
		$this->salida='';
		$this->system_Menu_user();
		return true;
	}

function Menus(){
       $mensaje_dusoft=$this->control_lectura_Mensajes(UserGetUID());
       for($i=0;$i<sizeof($mensaje_dusoft);$i++){
          if($mensaje_dusoft[$i]['sw']==0 && $mensaje_dusoft[$i]['obligatorio']==1){
              $count+=1;
          } 
       }
       if($count>0){
          $this->MensajeriaControl();
       }else{
          $this->Menus_Submenus();
       }
       return true; 
   } 

	 /**
* Funcion donde se visualiza el menu de usuario.
* @return boolean
*/
	function Menus_Submenus()
//	function Menus()
 {
            // SessionDelVar("ClaseAutorizacion");
           // unset ($_SESSION['orden_servicio']);
 	$dats=$this->MenuPerfiles();
        if(empty($dats))
        $dats=$this->BuscarMenuUsuario();
        $mensaje=$this->ConsultarMensajes();
        $numMensaje=$mensaje[0][todas]-$mensaje[0][leidas];
        $mensaje= $numMensaje.'  MENSAJES NUEVOS';
	$this->salida.="<center>\n";
	if($dats)
				{
					$this->salida .= ThemeMenuAbrirTabla("MENU DEL USUARIO","50%");
					for($i=0;$i<sizeof($dats);$i++)
					{
						$id=$dats[$i][menu_id];
						$menu=$dats[$i][menu_nombre];
						$desc=$dats[$i][descripcion];
						$desc=substr($desc,0,80)."...";
						$dato=$this->BuscarSubMenuUsuario($id);
						$this->salida.="<table border='0' width='100%'>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left' class='normal_10N'>";
                                                if($dats[$i][menu_nombre]=='Mensajeria'){
                                                    if($numMensaje==0){
                                                        $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;$menu&nbsp;&nbsp;";
                                                    }else{
                                                        $this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;$menu&nbsp;&nbsp;<a style='text-decoration: blink;color:#C4051C'>$mensaje</a>";
                                                    }
                                                }else{
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;$menu";
                                                }
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="	<tr>";
						$this->salida.="		<td align='left'>";
						$this->salida.="			<div class='normal_10_menu' valign='middle'><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;".$desc."</div>";
						for($e=0;$e<sizeof($dato);$e++)
						{
							$title=$dato[$e][titulo];
							if( $e % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$action=$this->AsignarUrl($id,$title);
							$this->salida .= ThemeSubMenuTabla("<a href=\"".$action."\">$title</a>","100%");
						}
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
						$this->salida.="</table>";
						$this->salida .="<br>";
					}
					$this->salida .= ThemeMenuCerrarTabla();
				}
				else
				{
					$this->salida .= ThemeAbrirTabla("INFORMACI?N","30%");
					$this->salida.="<table  align=\"center\" border=\"0\" class=\"hc_table_list\" width=\"100%\">\n";
					$this->salida.="	<tr>\n";
					$this->salida.="		<td align=\"center\" class=\"label_error\">NO EXISTEN MENUS PARA ESTE USUARIO.</td>\n";
					$this->salida.="	</tr>\n";
					$this->salida.="</table>\n";
					$this->salida .= ThemeCerrarTabla();
				}
				$this->salida.="</center>\n";
  return true;
 }

function MensajeriaControl(){
    $this->SetXajax(array("glectura"),"app_modules/Mensajeria/RemoteXajax/Funciones.php");
    $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
    $usuario_id=UserGetUID();
    $datos=$objSql->ConsultarControlObligatorio($usuario_id);
    $url2 = ModuloGetURL('app', 'Mensajeria', 'controller', 'Menu');
    $action1 = ModuloGetURL('system','Menu','user','main');
    
    $this->salida.="<script>
                 function guardarlectura(actualizacion_id,s)
                  {
                    xajax_glectura(actualizacion_id,s,'full');
                  }
                </script>";

        $this->salida.= ThemeAbrirTabla('MENSAJES DE LECTURA OBLIGATORIA');            
        $this->salida.= "<div style='background-color:#FFFFFF'>";
        $this->salida.= "<table align='center' border='0' class='hc_table_list' width='100%'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            if($datos[$i][sw]!=1&&$datos[$i][obligatorio]!=0){
            $estilo1 = 'modulo_list_claro';
            $estilo = 'modulo_list_oscuro';
            $actualizacion_id =$datos[$i][actualizacion_id];
            $asunto = $datos[$i][asunto];
            $fecha_fin = $datos[$i][fecha_fin];
            $fecha=$this->FechaStamp($fecha_fin);
            $sw = $datos[$i][sw];
            $nombre = $datos[$i][nombre];
            $descripcion= $datos[$i][descripcion];
            $fecha_lectura=$this->FechaStamp($datos[$i][fecha_lectura]);
            $hora_lectura=$this->HoraStamp($datos[$i][fecha_lectura]);
            if ($sw==0) {
              $mensaje='Mensaje Nuevo';
              $chequearSI='';
              $chequearNO="CHECKED";
            } else {
              $mensaje='';
              $chequearSI="CHECKED";
              $chequearNO='';
            }
            $this->salida.="
            <tbody>
            <tr class='modulo_table_list_title' style=\"font-size:12px\"  >";
            $this->salida.= "
            <td  width='40%' align='left' ><h3><b>Mensaje No.&nbsp;$actualizacion_id&nbsp;&nbsp;&nbsp;&nbsp;Fecha Valides:&nbsp;&nbsp;$fecha</b></h3></td>
            <td  width='30%'><h3><b>Creado Por:&nbsp;$nombre</b></h3></td>
            <td  width='15%'><div id='mensaje$actualizacion_id' ><h3><b><a style='text-decoration: blink;'>$mensaje</a></b></h3></div></td>";
            if ($sw==0) {
            $s=1;
            $this->salida.= " <td  width='15%' align='right'><div id='boton$actualizacion_id'><h3><b>Marcar como Leido<input type='checkbox'
                onClick =\"javascript:guardarlectura('$actualizacion_id','$s');\" name='chequear.$actualizacion_id' value='1'/> </b></h3></div></td>";
            }else{
            $this->salida.= " <td  width='15%' align='right'><h3><b>Leido:&nbsp;$fecha_lectura&nbsp;$hora_lectura</b></h3> </td>";
            }
           $this->salida.= " </tbody>
            <tbody>
            </tr>
            <tr>
            <td align='center' colspan='4'><h3><b>Asunto:&nbsp;$asunto</b></h3></td>
            </tr>
            <tr>
            <td colspan='4'>$descripcion</td>";
          $this->salida.= "   </tr>";
          $this->salida.= "  </tbody>";
            }
        }
        $this->salida.= "</table>";
        $this->salida.= "</div>";
        $this->salida.=ThemeCerrarTabla();
        return true;
}
 function FechaStamp($fecha)
     {
       if($fecha){
          $fech = strtok ($fecha,"-");
          for($l=0;$l<3;$l++)
          {
            $date[$l]=$fech;
            $fech = strtok ("-");
          }
          return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
       }
     }
      function HoraStamp($hora)
      {
        $hor = strtok ($hora," ");
        for($l=0;$l<4;$l++)
        {
          $time[$l]=$hor;
          $hor = strtok (":");
        }
            $x=explode('.',$time[3]);
        return  $time[1].":".$time[2].":".$x[0];
      }

}//fin clase user
?>