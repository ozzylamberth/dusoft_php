<?php
/**
* $Id: system_Usuarios_adminclasses_HTML.php,v 1.9 2006/07/10 13:48:04 carlos Exp $
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon - Jairo Duvan Diaz Martinez
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

IncludeClass("ClaseHTML");
class system_Usuarios_adminclasses_HTML extends system_Usuarios_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_Usuarios_admin_HTML()
	{
		$this->salida='';
		$this->system_Usuarios_admin();
		return true;
	}


/**
* Funcion donde se visializa la forma que pide datos para insertar un usuario
* @return boolean
*/

	function ListadoGeneralSistema()
	{
		$this->salida  = ThemeAbrirTabla('LISTADO DE HOST DEL SISTEMA');
		$this->salida .= "			      <br><br>";
		$accion=ModuloGetURL('system','Usuarios','admin','main');
		$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
    $this->salida .= "			      <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$usuarios=$this->ListadoIps();

		if(!$usuarios){
			$this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO HAY USUARIOS EN EL SISTEMA</td></tr>";
			$this->salida .= "				<tr><td align=\"center\"><br  ><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
		  $this->salida .= "        </td></tr>";
      $this->salida .= "        </table>";
      $this->salida .= ThemeCerrarTabla();
		  return true;
		}

    $this->salida .= "			  <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
    $this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "					       <td width=\"5%\">IP</td>";
    $this->salida .= "	  	  	       <td width=\"7%\">HOST</td>";
		$this->salida .= "          		 	 <td width=\"70%\">USUARIOS  --  INICIO  --  FINAL</td>";
		$this->salida .= "          	 	  	<td width=\"10%\">BLOQUEO</td>";
		$this->salida .= "      		        <td width=\"5%\"></td>";
		$this->salida .= "            </tr>";
    $y=1;
		$i=0;

		while($i<sizeof($usuarios)){
		  if($i % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}

		  $bloqueo=$usuarios[$i][sw_bloqueo];

      if($bloqueo=='1'){
        $tipoBloqueo='DESBLOQUEO';
				$img='inactivoip.gif';

			}else{
        $tipoBloqueo='BLOQUEO.....';
				$img='activo.gif';
			}

      $this->salida .= "<tr class=\"$estilo\">";
			$this->salida .= "     <td align=\"center\" valign='middle'><table  width='100%' border='0'><tr><td valign=\"middle\"><img src=\"".GetThemePath()."/images/pc.png\"></td>&nbsp;<td class='label'>".$usuarios[$i][ip]."</td></tr></table></td>";
			$this->salida .= "     <td align=\"center\" valign='middle'><table  width='100%' border='0'><tr><td valign=\"middle\"><img src=\"".GetThemePath()."/images/usuarios.png\"></td>&nbsp;<td class='label'>".$usuarios[$i][hostname]."</td></tr></table></td>";
			$this->salida .= "     <td align=\"center\">";
			$this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"2\" width=\"99%\" align=\"center\" class=\"modulo_list_claro\">";
			$b=$i;
			while($usuarios[$i][ip] == $usuarios[$b][ip])
			{
					$this->salida .= "            <tr>";
					if(!empty($usuarios[$b][usuario]))
					{
						$this->salida .= "     <td width=\"40%\"><b>".$usuarios[$b][usuario]."</b>(".$usuarios[$b][nombre].")</td>";
					}
					else
					{
						$this->salida .= "     <td class=\"label_error\" align=\"center\"><img src=\"".GetThemePath()."/images/no_usuarios.png\">No hay usuarios</td>";
					}
						if(!empty($usuarios[$b][inicio_session]))
						{
								$this->salida .= "     <td width=\"8%\"><img src=\"".GetThemePath()."/images/fecha_inicio.gif\">";
								$this->salida .= "</td>";
								$this->salida .= "     <td width=\"20%\">";
								$this->salida .= "     ".date("d/m/Y  h:i:s A",$usuarios[$b][inicio_session])."</td>";
								$this->salida .= "     <td width=\"8%\"><img src=\"".GetThemePath()."/images/fecha_fin.gif\">";
								$this->salida .= "</td>";
								$this->salida .= "     <td width=\"20%\">";
								$this->salida .= "     ".date("d/m/Y  h:i:s A",$usuarios[$b][max])."</td>";
								$this->salida .= "  		</tr>";
								$b++;
						}
						else
						{
								$b++;
						}
			}
			$this->salida .= "        </table>";
			$guardarBloqueo=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuarioIp',array("uid"=>$usuarios[$i][usuario_id],"ip"=>$usuarios[$i][ip],"host"=>$usuarios[$i][hostname]));
			$this->salida .= "			<td width=\"20\" align=\"center\"><img src=\"".GetThemePath()."/images/$img\">&nbsp;<a href=\"$guardarBloqueo\">$tipoBloqueo</a></td>";
      $action=ModuloGetURL('system','Usuarios','admin','VerListadoAcceso',array("ip"=>$usuarios[$i][ip],"host"=>$usuarios[$i][hostname]));
			$this->salida .= "     <td width=\"10\"><a href=\"$action\"><br>VER</a></td>";
			$this->salida .= "  </tr>";
			$i=$b;
			}
    $this->salida .= "        </table>";
		$action3=ModuloGetURL('system','Usuarios','admin','main',array("uid"=>$uid));
    $this->salida .= "       <table align=\"center\">";
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "    <tr><td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= "        </td></tr>";
    $this->salida .= "        </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



function ListadoAccesos($dats,$dir,$host)
{
 $ip=$this->EstadoIps($dir);
 	if($ip=='1'){
        $tipoBloqueo='DESBLOQUEO';
				$img='inactivoip.gif';

			}else{
        $tipoBloqueo='BLOQUEO.....';
				$img='activo.gif';
			}
		$action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuarioIp',array("ip"=>$dir,"marca"=>true,"host"=>$host,"dats"=>$dats));
    $this->salida  = ThemeAbrirTabla('REVISION DE LOG DE LA IP&nbsp;:&nbsp;'.$dir.'');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
    $this->salida .= "			      <table width=\"85%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DIRECCION IP</legend>";
		$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
   	$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">DIRECCION IP: </td><td class=\"modulo_list_claro\" align=\"left\">$dir</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"20%\">HOSTNAME: </td><td class=\"modulo_list_oscuro\" align=\"left\">$host</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"25%\" align=\"left\">ESTADO DE lA IP: </td><td class=\"modulo_list_claro\" align=\"left\">&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\"></td></tr>";
		$this->salida .= "              <tr><td align=\"center\" class=\"label\" colspan=\"2\"><a href=\"$action\">$tipoBloqueo</a>&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\"></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";
		$this->salida .= "            </table><BR><BR>";


		if($dats)
		{
  				$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"85%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>Log</td>";
					$this->salida.="  <td>Fecha</td>";
					$this->salida.="  <td>Logueo</td>";
					$this->salida.="  <td>Detalle</td>";
					$this->salida.="  <td></td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($dats);$i++)
					{
							$fecha=$dats[$i][fecha];
							$log=$dats[$i][log];
              $desc=$dats[$i][descripcion];
							$detalle=$dats[$i][detalle];
							$alerta=$dats[$i][tipo_alerta_id];
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" align=\"center\">";
							$this->salida.="  <td>$log</td>";
							$this->salida.="  <td>$fecha</td>";
							$this->salida.="  <td>$desc</td>";
							$this->salida.="  <td>$detalle</td>";
							switch($alerta)
							{
               case '0':
							 $imagen="ok.png";
							 break;
							 case '1':
							 $imagen="fallo.png";
							 break;
							 case '2':
							 $imagen="alarma.gif";
							 break;
							 case '3':
							 $imagen="interrogacion.png";
							 break;
               case '4':
							 $imagen="interrogacion.png";
							 break;
							 case '5':
							 $imagen="bloqueo.png";
							 break;
							}
							$this->salida.="  <td><img src=\"".GetThemePath()."/images/$imagen\" width=\"15\" height=\"15\"></td>";
							$this->salida.="</tr>";
					}
          $this->salida.="</table>";
					$acc=ModuloGetURL('system','Usuarios','admin','ListadoGeneralSistema');
					$this->salida.="<br><table align=\"center\" border=\"0\">";
					$this->salida.="<tr class =\"label_error\">";
					$this->salida.="<td  align=\"center\"><a href=\"$acc\">Regresar</a></td>";
					$this->salida.="</tr>";
					$this->salida.="</table>";

		}
		else
		{
		    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td align=\"center\" class=\"label_error\"><img src=\"".GetThemePath()."/images/informacion.png\">&nbsp;&nbsp;Este usuario no tiene registros de logueo</td>";
				$this->salida.="</tr>";
				$acc=ModuloGetURL('system','Usuarios','admin','ListadoGeneralSistema');
        $this->salida.="</table><br>";
				$this->salida.="<br><table align=\"center\" border=\"0\">";
				$this->salida.="<tr>";
				$this->salida.="<td align=\"center\"><a href=\"$acc\">Regresar</a></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";

		}

		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
* Funcion donde se visializa el estilo de error de un dato en la forma
* @return string
* @param string nombre del campo que genera error
*/

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
* Funcion donde se visializa el Listado de los usuarios del sistema
* @return boolean
*/

	function ListadoUsuariosSistema(){
	  unset($_SESSION['USER']['FECH']);
		unset($_SESSION['USER']['DIAS']);
		$this->salida  = ThemeAbrirTabla('LISTADO USUARIOS');

				$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="function mOvr(src,clrOver) {;\n";
				$mostrar.="src.style.background = clrOver;\n";
				$mostrar.="}\n";

				$mostrar.="function mOut(src,clrIn) {\n";
				$mostrar.="src.style.background = clrIn;\n";
				$mostrar.="}\n";
				$mostrar.="</script>\n";
				$this->salida .="$mostrar";
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');


		/*PARTE DE CLAUDIA*/
				$accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema');
				$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO USUARIOS </td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">TIPO</td>";

				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
				$this->salida.="<option value = '1'>Id</option>";
				$this->salida.="<option value = '2' selected>Login</option>";
				$this->salida.="<option value = '3'>Nombre Usuario</option>";
				$this->salida.="</select>";
				$this->salida.="</td>";
				//$buscar=$_REQUEST['busqueda'];
				$this->salida.="<td width=\"10%\">DESCRIPCIÓN:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;

				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</form>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";


				if($_REQUEST['buscar'])
				{
								unset($_SESSION['USUARIOS']['ORDENAMIENTO']);
								$filtro=$this->GetFiltroUsuarios($_REQUEST['criterio'],$_REQUEST['busqueda']);
				}
				else
				{
					if($_SESSION['USUARIOS']['FILTRO'])
					{
						$filtro=$_SESSION['USUARIOS']['FILTRO'];
					}
				}

        $img='UID';
				$color='';
				$imgN='NOMBRE USUARIO';
				$imgL='LOGIN';

				//ordenamiento por numero de usuario
				if($_REQUEST['ordenamiento']=='si')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario_id asc';
				}
					if($_REQUEST['ordenamiento']=='no')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario_id desc';
				}

        //ordenamiento por nombre
					if($_REQUEST['ordenamiento']=='nomsi')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by nombre asc';
				}
					if($_REQUEST['ordenamiento']=='nomno')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by nombre desc';
				}

				//ordenamiento por login
					if($_REQUEST['ordenamiento']=='losi')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario asc';
				}
					if($_REQUEST['ordenamiento']=='lono')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario desc';
				}

	/*PARTE DE CLAUDIA*/

		if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		$this->salida .= "			      <br><br>";
		$accion=ModuloGetURL('system','Usuarios','admin','main');
		$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
		$this->salida .= "			      <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr><td>";
		$usuarios=$this->BuscarUsuariosSistema($filtro);
		if(!$usuarios){
		$this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO SE ENCONTRÓ NINGUN REGISTRO</td></tr>";
		$this->salida .= "				<tr><td align=\"center\"><br  ><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
		$this->salida .= "        </td></tr>";
		$this->salida .= "        </table>";
		$this->salida .= ThemeCerrarTabla();
		  return true;
		}
		$this->salida .= "       <table align=\"center\">";
		$this->salida .="".$this->SetStyle("MensajeError")."";
		$this->salida .= "       </table>";
		$this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" >";
		$this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";

		//ordenamiento por usuario_id
		if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario_id asc')
		{
			$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
			$color='class=modulo_list_claro';
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'no'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario_id desc')
		{
			$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
			$color='class=modulo_list_claro';
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
		}
		else
		{
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
		}

		//ordenamiento por nombre
		 if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by nombre asc')
		{
			$imgN="NOMBRE USUARIO<BR>[ascendente]";
			$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomno'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by nombre desc')
		{
			$imgN="NOMBRE USUARIO<BR>[descendente]";
			$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
		}
		else
		{
					$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
		}

		//ordenamiento por login de usuario
		 if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario asc')
		{
			$imgL="LOGIN<BR>[ascendente]";
			$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'lono'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario desc')
		{
			$imgL="LOGIN<BR>[descendente]";
			$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
		}
		else
		{
					$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
		}
		$this->salida .= "				       <td $color><font color='#ffffff'><a class='hcLink' href='$acc'>$img</a></font></td>";
		$this->salida .= "	  	         <td><a class='hcLink' href='$accL'>$imgL</a</td>";
		$this->salida .= "              <td><a class='hcLink' href='$accN'>$imgN</a</td>";
		$this->salida .= "              <td>EMPRESA</td>";
		$this->salida .= "              <td >ACCION</td>";
		$this->salida .= "              <td colspan=\"6\">EVENTOS</td>";
		$this->salida .= "            </tr>";
	$y=1;
		for($i=0;$i<sizeof($usuarios);$i++){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}
			$arreglo = explode ("/", $usuarios[$i]);
			$uid=$arreglo[0];
			$usuario=$arreglo[1];
			$nombre=$arreglo[2];
			$descripcion=$arreglo[3];
			$passwd=$arreglo[4];
			$activo=$arreglo[5];
			$admin=$arreglo[6];
			$empresa=$arreglo[7];
			$nombreE=$arreglo[8];

/*
ESTOE ES PARA COLOCARLE <B> NEGRILLA A LOS VALORES...
$fila=str_replace(strtoupper($val),"<b>".strtoupper($val)."</b>",$fila);
$fila=str_replace(strtolower($val),"<b>".strtolower($val)."</b>",$fila);
*/
      if($activo=='1'){
        $tipoBloqueo='DESACTIVAR';
				$img='activo.gif';
        $action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuario',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			}else{
        $tipoBloqueo='ACTIVAR......';
				$img='inactivo.gif';
				$action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuario',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			}
     	$actionEditar=ModuloGetURL('system','Usuarios','admin','LlamaModificarUsuarioSistema',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"tema"=>$tema,"empID"=>$empresa,"descripcion"=>$descripcion,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			$actionPasswd=ModuloGetURL('system','Usuarios','admin','LlamaFormaModificarPasswd',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			$actionPermisos=ModuloGetURL('system','Usuarios','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empID"=>$empresa,"usuario"=>$usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			$actionBorrar=ModuloGetURL('system','Usuarios','admin','BorrarUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			$actionMenu=ModuloGetURL('system','Usuarios','admin','ListarMenus',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"descripcion"=>$descripcion,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda'],'busqueda'=>$_REQUEST['busqueda']));
			$actionPerfil=ModuloGetURL('system','Usuarios','admin','ListadoPerfilUsuario',array("uid"=>$uid,"nombre"=>$nombre,"empID"=>$empresa,"usuario"=>$usuario,'nom_emp'=>$nombreE,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
			$this->salida .= "		 <tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
			if($_SESSION['CENTRAL']['negrilla']==1){$uid=str_replace($_REQUEST['busqueda'],"<font color=#0C609C><b>".$_REQUEST['busqueda']."</b></font",$uid);}
			if($_SESSION['CENTRAL']['negrilla']==2){$usuario=str_replace($_REQUEST['busqueda'],"<font color=#0C609C><b>".$_REQUEST['busqueda']."</b></font>",$usuario);}
			if($_SESSION['CENTRAL']['negrilla']==3){$nombre=str_replace(strtoupper($_REQUEST['busqueda']),"<font color=#0C609C><b>".strtoupper($_REQUEST['busqueda'])."</b></font>",strtoupper($nombre));}
			//toco colocarlo en mayuscula para poder que le coloque el color cuando busque por ejemplo jai o JAI, entonces ->strtoupper($nombre))RECORDARLO!
			//hay muchos registros que vienen en minuscula y otros en mayusculas.....
			$this->salida .= "     <td align=\"center\">$uid</td>";
			$this->salida .= "     <td>$usuario</td>";
			$this->salida .= "     <td>$nombre</td>";
			$this->salida .= "     <td>$nombreE</td>";
				//$this->salida .= "     <td>$activo</td>";
			//$this->salida.="  <td><img src=\"".GetThemePath()."/images/$img\"></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/$img\">&nbsp;<a href=\"$action\">$tipoBloqueo</a></td>";
      $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/edita.png\">&nbsp;<a href=\"$actionEditar\">EDIT</a></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/pass.png\">&nbsp;<a href=\"$actionPasswd\">PWD</a></td>";
      $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/mail_find.png\">&nbsp;<a href=\"$actionPermisos\">PERMISO</a></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/modificar.gif\">&nbsp;<a href=\"$actionPerfil\">PERFIL</a></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/folder_lleno.png\">&nbsp;<a href=\"$actionMenu\">MENÚ</a></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/delete2.gif\">&nbsp;<a href=\"$actionBorrar\">ELIM</a></td>";
     // $this->salida .= "			<td align=\"center\"><a href=\"$actionDepartamentos\">DEPARTAMENTOS</a></td>";
			$this->salida .= "     </tr>";
      $y++;
		}
    $this->salida .= "       </table>";
		$this->salida .= "        </td></tr>";
    $this->salida .= "        </table>";
		$this->salida .=$this->RetornarBarra($filtro);
		$action3=ModuloGetURL('system','Usuarios','admin','main',array("uid"=>$uid));
    $this->salida .= "       <table align=\"center\">";
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
		$this->salida .= "       </table>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	/**
* Funcion donde se visializa el Listado de los usuarios del sistema
* @return boolean
*/

	function ListadoUsuariosSistemaProfesional(){
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
	  unset($_SESSION['USER']['FECH']);
		unset($_SESSION['USER']['DIAS']);
		$this->salida  = ThemeAbrirTabla('LISTADO USUARIOS');

				$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="function mOvr(src,clrOver) {;\n";
				$mostrar.="src.style.background = clrOver;\n";
				$mostrar.="}\n";

				$mostrar.="function mOut(src,clrIn) {\n";
				$mostrar.="src.style.background = clrIn;\n";
				$mostrar.="}\n";
				$mostrar.="</script>\n";
				$this->salida .="$mostrar";
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');


		/*PARTE DE CLAUDIA*/
				$accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistemaProfesional');
				$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO USUARIOS </td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">TIPO</td>";

				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
				$this->salida.="<option value = '1'>Id</option>";
				$this->salida.="<option value = '2' selected>Login</option>";
				$this->salida.="<option value = '3'>Nombre Usuario</option>";
				$this->salida.="</select>";
				$this->salida.="</td>";
				//$buscar=$_REQUEST['busqueda'];
				$this->salida.="<td width=\"10%\">DESCRIPCIÓN:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;

				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</form>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";


				if($_REQUEST['buscar'])
				{
								unset($_SESSION['USUARIOS']['ORDENAMIENTO']);
								$filtro=$this->GetFiltroUsuarios($_REQUEST['criterio'],$_REQUEST['busqueda']);
				}
				else
				{
					if($_SESSION['USUARIOS']['FILTRO'])
					{
						$filtro=$_SESSION['USUARIOS']['FILTRO'];
					}
				}

        $img='UID';
				$color='';
				$imgN='NOMBRE USUARIO';
				$imgL='LOGIN';

				//ordenamiento por numero de usuario
				if($_REQUEST['ordenamiento']=='si')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario_id asc';
				}
					if($_REQUEST['ordenamiento']=='no')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario_id desc';
				}

        //ordenamiento por nombre
					if($_REQUEST['ordenamiento']=='nomsi')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by nombre asc';
				}
					if($_REQUEST['ordenamiento']=='nomno')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by nombre desc';
				}

				//ordenamiento por login
					if($_REQUEST['ordenamiento']=='losi')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario asc';
				}
					if($_REQUEST['ordenamiento']=='lono')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario desc';
				}

	/*PARTE DE CLAUDIA*/

		if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		$this->salida .= "			      <br><br>";
		$accion=ModuloGetURL('system','Usuarios','admin','main');
		$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
		$this->salida .= "			      <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr><td>";
		$usuarios=$this->BuscarUsuariosSistemaNoProfesionales($filtro);
		if(!$usuarios){
		$this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO SE ENCONTRÓ NINGUN REGISTRO</td></tr>";
		$this->salida .= "				<tr><td align=\"center\"><br  ><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
		$this->salida .= "        </td></tr>";
		$this->salida .= "        </table>";
		$this->salida .= ThemeCerrarTabla();
		  return true;
		}
		$this->salida .= "       <table align=\"center\">";
		$this->salida .="".$this->SetStyle("MensajeError")."";
		$this->salida .= "       </table>";
		$this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" >";
		$this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";

		//ordenamiento por usuario_id
		if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario_id asc')
		{
			$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
			$color='class=modulo_list_claro';
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'no'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario_id desc')
		{
			$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
			$color='class=modulo_list_claro';
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
		}
		else
		{
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
		}

		//ordenamiento por nombre
		 if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by nombre asc')
		{
			$imgN="NOMBRE USUARIO<BR>[ascendente]";
			$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomno'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by nombre desc')
		{
			$imgN="NOMBRE USUARIO<BR>[descendente]";
			$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
		}
		else
		{
					$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
		}

		//ordenamiento por login de usuario
		 if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario asc')
		{
			$imgL="LOGIN<BR>[ascendente]";
			$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'lono'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario desc')
		{
			$imgL="LOGIN<BR>[descendente]";
			$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
		}
		else
		{
					$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
		}
		$this->salida .= "				       <td $color><font color='#ffffff'><a class='hcLink' href='$acc'>$img</a></font></td>";
		$this->salida .= "	  	         <td><a class='hcLink' href='$accL'>$imgL</a</td>";
		$this->salida .= "              <td><a class='hcLink' href='$accN'>$imgN</a</td>";
		$this->salida .= "              <td>EMPRESA</td>";
		$this->salida .= "              <td >ACCION</td>";		
		$this->salida .= "            </tr>";
	$y=1;
		for($i=0;$i<sizeof($usuarios);$i++){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}
			$arreglo = explode ("/", $usuarios[$i]);
			$uid=$arreglo[0];
			$usuario=$arreglo[1];
			$nombre=$arreglo[2];
			$descripcion=$arreglo[3];
			$passwd=$arreglo[4];
			$activo=$arreglo[5];
			$admin=$arreglo[6];
			$empresa=$arreglo[7];
			$nombreE=$arreglo[8];

/*
ESTOE ES PARA COLOCARLE <B> NEGRILLA A LOS VALORES...
$fila=str_replace(strtoupper($val),"<b>".strtoupper($val)."</b>",$fila);
$fila=str_replace(strtolower($val),"<b>".strtolower($val)."</b>",$fila);
*/
      
     	$actionCrear=ModuloGetURL('system','Usuarios','admin','LlamaCrearProfesionalSistema',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"empID"=>$empresa,"descripcion"=>$nombreE));			
			$this->salida .= "		 <tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
			if($_SESSION['CENTRAL']['negrilla']==1){$uid=str_replace($_REQUEST['busqueda'],"<font color=#0C609C><b>".$_REQUEST['busqueda']."</b></font",$uid);}
			if($_SESSION['CENTRAL']['negrilla']==2){$usuario=str_replace($_REQUEST['busqueda'],"<font color=#0C609C><b>".$_REQUEST['busqueda']."</b></font>",$usuario);}
			if($_SESSION['CENTRAL']['negrilla']==3){$nombre=str_replace(strtoupper($_REQUEST['busqueda']),"<font color=#0C609C><b>".strtoupper($_REQUEST['busqueda'])."</b></font>",strtoupper($nombre));}
			//toco colocarlo en mayuscula para poder que le coloque el color cuando busque por ejemplo jai o JAI, entonces ->strtoupper($nombre))RECORDARLO!
			//hay muchos registros que vienen en minuscula y otros en mayusculas.....
			$this->salida .= "     <td align=\"center\">$uid</td>";
			$this->salida .= "     <td>$usuario</td>";
			$this->salida .= "     <td>$nombre</td>";
			$this->salida .= "     <td>$nombreE</td>";
				//$this->salida .= "     <td>$activo</td>";
			//$this->salida.="  <td><img src=\"".GetThemePath()."/images/$img\"></td>";			
      $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/proveedor.png\">&nbsp;<a href=\"$actionCrear\">CREAR PROFESIONAL</a></td>";			
			$this->salida .= "     </tr>";
      $y++;
		}
    $this->salida .= "       </table>";
		$this->salida .= "        </td></tr>";
    $this->salida .= "        </table>";
		//$this->salida .=$this->RetornarBarra($filtro);
		$Paginador = new ClaseHTML();
		$this->actionPaginador=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistemaProfesional',array("criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"buscar"=>$_REQUEST['buscar'],"ordenamiento"=>$_REQUEST['ordenamiento']));
		$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		
		$this->salida .= "       <table align=\"center\">";			
		$this->salida .= "           </form>";
		$action=ModuloGetURL('system','Usuarios','admin','MenuUsuariosSistemaProfesionales');		
		$this->salida .= "           <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
		$this->salida .= "           </form>";
		$this->salida .= "       </table>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

		/**
* Funcion donde se visializa el Listado de los usuarios del sistema
* @return boolean
*/

	function ListadoUsuariosProfesionales(){
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset']){
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1){
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
	  unset($_SESSION['USER']['FECH']);
		unset($_SESSION['USER']['DIAS']);
		$this->salida  = ThemeAbrirTabla('LISTADO USUARIOS PROFESIONALES');

				$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="function mOvr(src,clrOver) {;\n";
				$mostrar.="src.style.background = clrOver;\n";
				$mostrar.="}\n";

				$mostrar.="function mOut(src,clrIn) {\n";
				$mostrar.="src.style.background = clrIn;\n";
				$mostrar.="}\n";
				$mostrar.="</script>\n";
				$this->salida .="$mostrar";
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');


		/*PARTE DE CLAUDIA*/
				$accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosProfesionales');
				$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO USUARIOS </td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">TIPO</td>";

				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
				$this->salida.="<option value = '1'>Id</option>";
				$this->salida.="<option value = '2' selected>Login</option>";
				$this->salida.="<option value = '3'>Nombre Usuario</option>";
				$this->salida.="</select>";
				$this->salida.="</td>";
				//$buscar=$_REQUEST['busqueda'];
				$this->salida.="<td width=\"10%\">DESCRIPCIÓN:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"$buscar\"></td>" ;

				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</form>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";


				if($_REQUEST['buscar'])
				{
								unset($_SESSION['USUARIOS']['ORDENAMIENTO']);
								$filtro=$this->GetFiltroUsuarios($_REQUEST['criterio'],$_REQUEST['busqueda']);
				}
				else
				{
					if($_SESSION['USUARIOS']['FILTRO'])
					{
						$filtro=$_SESSION['USUARIOS']['FILTRO'];
					}
				}

        $img='UID';
				$color='';
				$imgN='NOMBRE USUARIO';
				$imgL='LOGIN';

				//ordenamiento por numero de usuario
				if($_REQUEST['ordenamiento']=='si')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario_id asc';
				}
					if($_REQUEST['ordenamiento']=='no')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario_id desc';
				}

        //ordenamiento por nombre
					if($_REQUEST['ordenamiento']=='nomsi')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by nombre asc';
				}
					if($_REQUEST['ordenamiento']=='nomno')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by nombre desc';
				}

				//ordenamiento por login
					if($_REQUEST['ordenamiento']=='losi')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario asc';
				}
					if($_REQUEST['ordenamiento']=='lono')
				{
					$_SESSION['USUARIOS']['ORDENAMIENTO']='order by usuario desc';
				}

	/*PARTE DE CLAUDIA*/

		if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		$this->salida .= "			      <br><br>";
		$accion=ModuloGetURL('system','Usuarios','admin','main');
		$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
		$this->salida .= "			      <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr><td>";
		$usuarios=$this->BuscarUsuariosSistemaProfesionales($filtro);
		if(!$usuarios){
		$this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO SE ENCONTRÓ NINGUN REGISTRO</td></tr>";
		$this->salida .= "				<tr><td align=\"center\"><br  ><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
		$this->salida .= "        </td></tr>";
		$this->salida .= "        </table>";
		$this->salida .= ThemeCerrarTabla();
		  return true;
		}
		$this->salida .= "       <table align=\"center\">";
		$this->salida .="".$this->SetStyle("MensajeError")."";
		$this->salida .= "       </table>";
		$this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\" >";
		$this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";

		//ordenamiento por usuario_id
		if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario_id asc')
		{
			$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
			$color='class=modulo_list_claro';
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'no'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario_id desc')
		{
			$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
			$color='class=modulo_list_claro';
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
		}
		else
		{
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
		}

		//ordenamiento por nombre
		 if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by nombre asc')
		{
			$imgN="NOMBRE USUARIO<BR>[ascendente]";
			$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomno'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by nombre desc')
		{
			$imgN="NOMBRE USUARIO<BR>[descendente]";
			$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
		}
		else
		{
					$accN=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
		}

		//ordenamiento por login de usuario
		 if($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario asc')
		{
			$imgL="LOGIN<BR>[ascendente]";
			$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'lono'));
		}
		elseif($_SESSION['USUARIOS']['ORDENAMIENTO']=='order by usuario desc')
		{
			$imgL="LOGIN<BR>[descendente]";
			$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
		}
		else
		{
					$accL=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
		}
		$this->salida .= "				       <td $color><font color='#ffffff'><a class='hcLink' href='$acc'>$img</a></font></td>";
		$this->salida .= "	  	         <td><a class='hcLink' href='$accL'>$imgL</a</td>";
		$this->salida .= "              <td><a class='hcLink' href='$accN'>$imgN</a</td>";
		$this->salida .= "              <td>EMPRESA</td>";
		$this->salida .= "              <td >ACCION</td>";		
		$this->salida .= "            </tr>";
	$y=1;
		for($i=0;$i<sizeof($usuarios);$i++){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}
			$arreglo = explode ("/", $usuarios[$i]);
			$uid=$arreglo[0];
			$usuario=$arreglo[1];
			$nombre=$arreglo[2];
			$descripcion=$arreglo[3];
			$passwd=$arreglo[4];
			$activo=$arreglo[5];
			$admin=$arreglo[6];
			$empresa=$arreglo[7];
			$nombreE=$arreglo[8];

/*
ESTOE ES PARA COLOCARLE <B> NEGRILLA A LOS VALORES...
$fila=str_replace(strtoupper($val),"<b>".strtoupper($val)."</b>",$fila);
$fila=str_replace(strtolower($val),"<b>".strtolower($val)."</b>",$fila);
*/
      
     	$actionCrear=ModuloGetURL('system','Usuarios','admin','LlamaModificarProfesionalSistema',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"empID"=>$empresa,"descripcion"=>$nombreE));			
			$this->salida .= "		 <tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#FFFFFF');>";
			if($_SESSION['CENTRAL']['negrilla']==1){$uid=str_replace($_REQUEST['busqueda'],"<font color=#0C609C><b>".$_REQUEST['busqueda']."</b></font",$uid);}
			if($_SESSION['CENTRAL']['negrilla']==2){$usuario=str_replace($_REQUEST['busqueda'],"<font color=#0C609C><b>".$_REQUEST['busqueda']."</b></font>",$usuario);}
			if($_SESSION['CENTRAL']['negrilla']==3){$nombre=str_replace(strtoupper($_REQUEST['busqueda']),"<font color=#0C609C><b>".strtoupper($_REQUEST['busqueda'])."</b></font>",strtoupper($nombre));}
			//toco colocarlo en mayuscula para poder que le coloque el color cuando busque por ejemplo jai o JAI, entonces ->strtoupper($nombre))RECORDARLO!
			//hay muchos registros que vienen en minuscula y otros en mayusculas.....
			$this->salida .= "     <td align=\"center\">$uid</td>";
			$this->salida .= "     <td>$usuario</td>";
			$this->salida .= "     <td>$nombre</td>";
			$this->salida .= "     <td>$nombreE</td>";
				//$this->salida .= "     <td>$activo</td>";
			//$this->salida.="  <td><img src=\"".GetThemePath()."/images/$img\"></td>";			
      $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/proveedor.png\">&nbsp;<a href=\"$actionCrear\">MODIFICAR PROFESIONAL</a></td>";			
			$this->salida .= "     </tr>";
      $y++;
		}
    $this->salida .= "       </table>";
		$this->salida .= "        </td></tr>";
    $this->salida .= "        </table>";
		//$this->salida .=$this->RetornarBarra($filtro);
		$Paginador = new ClaseHTML();
		$this->actionPaginador=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosProfesionales',array("criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"buscar"=>$_REQUEST['buscar'],"ordenamiento"=>$_REQUEST['ordenamiento']));
		$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		
		$this->salida .= "       <table align=\"center\">";			
		$this->salida .= "           </form>";
		$action=ModuloGetURL('system','Usuarios','admin','MenuUsuariosSistemaProfesionales');		
		$this->salida .= "           <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
		$this->salida .= "           </form>";
		$this->salida .= "       </table>";
    $this->salida .= ThemeCerrarTabla();
		return true;
	}

function CalcularNumeroPasos($conteo){
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	function CalcularBarra($paso){
		$barra=floor($paso/10)*10;
		if(($paso%10)==0){
			$barra=$barra-10;
		}
		return $barra;
	}

	function CalcularOffset($paso){
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	 function RetornarBarra($filtro){
	 	if($this->limit>=$this->conteo){
				return '';
		}
		//if($filtro){$_SESSION['USUARIOS']['FILTRO']=$filtro;}//esto guarda el filtro...
		//de busqueda...
	  $paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		$accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('conteo'=>$this->conteo,'busqueda'=>$_REQUEST['busqueda']));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table width='22%' border='0'  align='center' cellspacing=\"5\"  cellpadding=\"1\"><tr><td width='10%' class='label' bgcolor=\"#D3DCE3\">Páginas :</td>";
		if($paso > 1){
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td width='7%' bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td width='7%'  bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$this->salida .= "<td width='7%' bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$colspan++;
			}
      if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$this->salida .= "</tr><tr><td  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
		}
		else
		{
			if($numpasos>10)
			{
				$valor=10+5;
			}
			else
			{
				$valor=$numpasos+5;
			}
		$this->salida .= "</tr><tr><td   class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
		}
    }
	}


	function AsignarPermisosUserModulo($uid,$NombreUsuario,$Usuario,$dato,$empresa)
	{
//echo "nom->".$NombreUsuario;
//echo "um->".$Usuario;

	if(!empty($_REQUEST['empresa']))
	{
		$empresa=$_REQUEST['empresa'];
	}

  if(empty($uid))
	{
			$uid=$_REQUEST['uid'];
			$NombreUsuario=urldecode($_REQUEST['NombreUsuario']);
			$Usuario=$_REQUEST['usuario'];
	}

	 if(empty($dato))
		{
       $dato=$_REQUEST['dato'];
		}

		$Destino='1';
		$conexion=$this->BuscarConexion($uid);
    if($conexion >0){
      $estado='Logueado';
			$img1='conectado.png';
	  }else{
			$estado='No logueado';
			$img1='desconectado.png';
		}
		$dats=$this->BuscarLog($uid,true);
		$da=$this->BuscarLog($uid,false);
		if(empty($dats[0][fecha]))
		{
			$registro='No hay registros de logueo';
		}
		else
		{
     $registro=$dats[0][fecha];
		}

		$this->salida  = ThemeAbrirTabla('ASIGNACION PERMISOS USUARIO&nbsp;:&nbsp;'.$Usuario.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Estado del usuario: '.$estado.'');
    $this->salida .= "			      <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">PERMISOS USUARIO</legend>";
		$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">$Usuario</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"20%\">NOMBRE<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_oscuro\" align=\"left\">$NombreUsuario</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NUMERO DE LOGUEOS: </td><td class=\"modulo_list_claro\" align=\"left\">$da</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">ULTIMO LOGUEO: </td><td class=\"modulo_list_oscuro\" align=\"left\">".$registro."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"25%\" align=\"left\">ESTADO DEL USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">$estado&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img1\"></td></tr>";
		$datos=$this->ComboEmpresa();
		//$this->salida .= "              <tr><td class=\"modulo_table_list_title\" class=\"label\" colspan=\"2\"><a href=\"$action\">$tipoBloqueo</a>&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\"></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";
		$this->salida .= "            </table><BR><BR>";



		if($dato=='1')
		{

							$action3=ModuloGetURL('system','Usuarios','admin','InsertarPermisosU',array("uid"=>$uid,'NombreUsuario'=>urlencode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
							$this->salida .= "           <form name=\"formas\" action=\"$action3\" method=\"post\">";
							$this->salida .= "<SCRIPT>";
							$this->salida .= "function chequeoTotal(frm,x){";
							$this->salida .= "  if(x==true){";
							$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
							$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
							$this->salida .= "        frm.elements[i].checked=true";
							$this->salida .= "      }";
							$this->salida .= "    }";
							$this->salida .= "  }else{";
							$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
							$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
							$this->salida .= "        frm.elements[i].checked=false";
							$this->salida .= "      }";
							$this->salida .= "    }";
							$this->salida .= "  }";
							$this->salida .= "}";
							$this->salida .= "</SCRIPT>";
							$this->salida.="<table  align=\"center\" border=\"0\" class=\"modulo_table_list\" width=\"70%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"left\" colspan=\"4\">PERMISOS DEPARTAMENTOS</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"40%\">Empresa</td>";
							$this->salida.="  <td width=\"10%\">Cod</td>";
							$this->salida.="  <td width=\"40%\">Departamento</td>";
							$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";


						//	$action3=ModuloGetURL('system','Usuarios','admin','InsertarPermisosU',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
							///$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";

							$this->salida.="<input type='hidden' name='emp' value=".$_REQUEST['empresa'].">";
							$this->salida.="</tr>";

					for($e=0;$e<sizeof($datos);$e++)
					{
							if( $e % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr align=\"center\">";
							$this->salida.="  <td width=\"10%\" class=\"$estilo\">".$datos[$e][razon_social]."</td>";

							$vector=$this->ComboDpto($datos[$e][empresa_id],$uid);
							if(empty($vector[0][departamento]))
							{
								$this->salida.="  <td class=\"$estilo\" colspan='3'>NO Hay Departamentos</td>";
							}
							else
							{		$this->salida.=" <td colspan=\"3\">";  }
							if(!empty($vector[0][departamento]))
							{
							for($i=0;$i<sizeof($vector);$i++)
							{
									$dpto=$vector[$i][departamento];
									$desc=$vector[$i][descripcion];
									$check=$vector[$i][usuario_id];
									if($check)
									{
									$chequeo='checked';
									}
									else
									{
									$chequeo='';
									}

									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.=" <table width=\"100%\" class=\"normal_10\" cellpanding=\"1\" cellpascing=\"1\"><tr class=\"$estilo\"><td width=\"10%\" align=\"center\">$dpto</td>";
									$this->salida.="  <td width=\"40%\">$desc</td>";
									$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=$dpto $chequeo></td>";
									$this->salida.="</tr></table>";


							}
							}
									$this->salida.=" </td>";
									$this->salida.="</tr>";
						}

							$this->salida.="</table>";
							$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
							$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Guardar\"></form></td>";
							$action2=ModuloGetURL('system','Usuarios','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,'nombre'=>urldecode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
							$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
							$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
							$this->salida .= "</tr>";
							$this->salida.="</table><br>";
   }
	 else
	 {
		    $vector=$this->TraerModulo($uid);
        if($vector)
				{
         $this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
          $action=ModuloGetURL('system','Usuarios','admin','InsertarPermisosModulo',array("uid"=>$uid,'NombreUsuario'=>urldecode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
					$this->salida .= "           <form name=\"formades\" action=\"$action\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"70%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"4\">PERMISOS MODULOS</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>Tipo</td>";
					$this->salida.="  <td>Modulo</td>";
					$this->salida.="  <td>Descripción</td>";
					$this->salida.="  <td>Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
          //$this->salida.="<input type='hidden' name='emp' value=".$_REQUEST['empresa'].">";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vector);$i++)
					{
							$modulo=$vector[$i][modulo];
							$tipo_mod=$vector[$i][modulo_tipo];
							$desc=$vector[$i][cion];
							$check=$vector[$i][usuario_id];
							if($check)
							{
               $chequeo='checked';
							}
							else
							{
               $chequeo='';
							}
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" align=\"left\">";
							$this->salida.="  <td width=\"10%\">$tipo_mod</td>";
							$this->salida.="  <td width=\"30%\">$modulo</td>";
							$this->salida.="  <td width=\"30%\">$desc</td>";
							$this->salida.="  <td width=\"10%\"><input type=checkbox name=op[$i] value=$modulo $chequeo></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Guardar\"></form></td>";
					$action2=ModuloGetURL('system','Usuarios','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,'nombre'=>urldecode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";

				}

	 }
	  $this->salida .= ThemeCerrarTabla();
return true;
}

/**
* Funcion visualiza La forma que piden datos de los nuevos permisos para adicionar a un usuario
* @return boolean
*/

	function FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario,$empresa){

		 $activo=$this->BuscaEstadoAfiliado($uid);
		if($activo=='1'){
      $tipoBloqueo='DESACTIVAR';
			$img='activo.gif';
			$titulo="USUARIO <b> $NombreUsuario</b> ESTA ACTIVO EN EL SISTEMA";
	  }else{
			$tipoBloqueo='ACTIVAR';
			$img='inactivo.gif';
			$titulo="USUARIO  <b>$NombreUsuario</b> NO ESTA ACTIVO EN EL SISTEMA";
		}

		/*esta funcion busca el estado de la empresa '1' activo '0' desactivo*/
		$estadoe=$this->BuscaEstadoUserEmpresa($uid,$empresa);

		if($estadoe=='1')
		{
			$tipoBlo='INACTIVAR EMPRESA';
			$estado_empresa='activoemp.png';
			$tituloe="LA EMPRESA  ESTA ACTIVA EN EL SISTEMA";
		}
		else
		{
			$tipoBlo='ACTIVAR EMPRESA';
			$estado_empresa='inactivoemp.png';
			$tituloe="LA EMPRESA  NO ESTA ACTIVA EN EL SISTEMA";
		}
		$Destino='1';
		$conexion=$this->BuscarConexion($uid);
    if($conexion >0){
      $estado='Logueado';
			$img1='conectado.png';
	  }else{
			$estado='No logueado';
			$img1='desconectado.png';
		}
		$dats=$this->BuscarLog($uid,true);
		$da=$this->BuscarLog($uid,false);
		if(empty($dats[0][fecha]))
		{
			$registro='No hay registros de logueo';
		}
		else
		{
     $registro=$dats[0][fecha];
		}
		$action_mod=ModuloGetURL('system','Usuarios','admin','ModificarEstadoEmpresa',array("uid"=>$uid,"empresa"=>$empresa,"TipoForma"=>$Destino,"NombreUsuario"=>urlencode($NombreUsuario),"usuario"=>$Usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$actionInsertar=ModuloGetURL('system','Usuarios','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>urlencode($NombreUsuario),"usuario"=>$Usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuario',array("uid"=>$uid,"empresa"=>$empresa,"TipoForma"=>$Destino,"NombreUsuario"=>urlencode($NombreUsuario),"usuario"=>$Usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
    $actionBorrarPermisos=ModuloGetURL('system','Usuarios','admin','BorrarTodosPermisosUsuarios',array("uid"=>$uid,"NombreUsuario"=>urlencode($NombreUsuario),"usuario"=>$Usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$this->salida  = ThemeAbrirTabla('ASIGNACION PERMISOS USUARIO&nbsp;:&nbsp;'.$Usuario.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Estado del usuario: '.$estado.'');
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
    $this->salida .= "			      <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">PERMISOS USUARIO</legend>";
		$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"99%\" align=\"center\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">$Usuario</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"20%\">NOMBRE<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_oscuro\" align=\"left\">$NombreUsuario</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NUMERO DE LOGUEOS: </td><td class=\"modulo_list_claro\" align=\"left\">$da</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">ULTIMO LOGUEO: </td><td class=\"modulo_list_oscuro\" align=\"left\">".$registro."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"25%\" align=\"left\">ESTADO DEL USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">$estado&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img1\"></td></tr>";
		$datos=$this->ComboEmpresa();
		$this->salida .= "              <tr><td align=\"center\" class=\"modulo_list_claro\" class=\"label\" colspan=\"2\"><a title='$titulo' href=\"$action\">$tipoBloqueo</a>&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\">&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp&nbsp;<a title='$tituloe' href=\"$action_mod\">$tipoBlo&nbsp;&nbsp&nbsp;</a><img src=\"".GetThemePath()."/images/$estado_empresa\"></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";
		$this->salida .= "            </table><BR><BR>";
		$this->salida .= "			      </form>";


					$action3=ModuloGetURL('system','Usuarios','admin','AsignarPermisosUserModulo',array("uid"=>$uid,"empresa"=>$empresa,'dato'=>'1','usuario'=>$Usuario,'NombreUsuario'=>urlencode($NombreUsuario),'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
          $action4=ModuloGetURL('system','Usuarios','admin','AsignarPermisosUserModulo',array("uid"=>$uid,"empresa"=>$empresa,'dato'=>'2','usuario'=>$Usuario,'NombreUsuario'=>urlencode($NombreUsuario),'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
					//$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";



					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"2\">ASIGNAR USUARIO</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\" >";
					$this->salida.="<td width='51%' align=\"center\" class=\"label_error\"><a title='PERMITE  RELACIONAR AL USUARIO  <p><b> $NombreUsuario </b></p>  CON ALGÚN DEPARTAMENTO' href=".$action3.">[ ASIGNAR DEPARTAMENTOS ]</a></td><td align=\"center\" class=\"label_error\"><a title='PERMITE  ASIGNAR AL USUARIO  <p><b> $NombreUsuario </b></p>  MODULOS ADMINISTRATIVOS, SOLO SI <b> $NombreUsuario </b> ES ADMINISTRADOR DE EMPRESAS' href=".$action4.">[ ASIGNAR MODULOS ADMINISTRATIVOS ]</a></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";




    if($dats)
		{
  				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"3\">ULTIMOS LOGS DE USUARIO</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>Fecha</td>";
					$this->salida.="  <td>Tipo de logueo</td>";
					$this->salida.="  <td></td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($dats);$i++)
					{
							$fecha=$dats[$i][fecha];
              $desc=$dats[$i][descripcion];
							$alerta=$dats[$i][tipo_alerta_id];
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" align=\"center\">";
							$this->salida.="  <td>$fecha</td>";
							$this->salida.="  <td>$desc</td>";
             	switch($alerta)
							{
               case '0':
							 $imagen="ok.png";
							 break;
							 case '1':
							 $imagen="fallo.png";
							 break;
							 case '2':
							 $imagen="alarma.png";
							 break;
							 case '3':
							 $imagen="interrogacion.png";
							 break;
               case '4':
							 $imagen="interrogacion.png";
							 break;
							 case '5':
							 $imagen="bloqueo.png";
							 break;
							}
							$this->salida.="  <td><img width='12' src=\"".GetThemePath()."/images/$imagen\"></td>";
							$this->salida.="</tr>";
					}
					$this->salida .= "			      </form>";
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\"  border=\"0\">";
					$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
					$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Volver\" type=\"submit\" value=\"Volver\"></td></tr>";
					$this->salida.="</table>";


		}
		else
		{
		    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td align=\"center\" class=\"label_error\"><img src=\"".GetThemePath()."/images/informacion.png\">&nbsp;&nbsp;Este usuario no tiene registros de logueo</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
  			$this->salida.="<table align=\"center\"  border=\"0\">";
				$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
				$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
				$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Volver\" type=\"submit\" value=\"Volver\"></td></tr>";
				$this->salida .= "			      </form>";
				$this->salida.="</table>";
		}

		$this->salida .= ThemeCerrarTabla();
		return true;
	}


/**
* Funcion donde se visializa la forma que pide datos para insertar un usuario
* @return boolean
*/

	function FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,$consulta,$descripcion,$spia,$uid,$empresa){
  
    $fechacaduca=$_SESSION['USER']['FECH'];
		$this->salida  = ThemeAbrirTabla('INSERTAR USUARIO');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "            </td></tr>";

		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DATOS USUARIO SISTEMA</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
		$this->salida .= "				       <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("emp")."\" width=\"40%\" align=\"left\">EMPRESA: </td><td class=\"modulo_list_oscuro\" align=\"left\">";
		$datos=$this->ComboEmpresa();

			$this->salida.="<select name='empresa' class='select'>";
			$this->salida.="<option value=-1>----Seleccione----</option>";

      if(empty($empresa))
			{
					for($i=0;$i<sizeof($datos);$i++)
					{
						$this->salida.="<option value=".$datos[$i][empresa_id].">".$datos[$i][razon_social]."</option>";

					}
				$this->salida.="</select>";
			}
			else
			{
			for($i=0;$i<sizeof($datos);$i++)
								{
                  if($datos[$i][empresa_id]==$empresa)
									{
										$this->salida .=" <option value=\"".$datos[$i][empresa_id]."\" selected>".$datos[$i][razon_social]."</option>";

									}
									else
									{
										$this->salida.="<option value=".$datos[$i][empresa_id].">".$datos[$i][razon_social]."</option>";
									}

								}
							$this->salida.="</select>";
			}


			$this->salida .= "	</td></tr>";


		//acordarse de esto hay q arreglarlo si se daña sabremos donde fue el daño.	
    $this->salida.= "               <input type=\"hidden\" name=\"action\" value=\"$action\">";

		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("nombreUsuario")."\">NOMBRE USUARIO: </td><td><input type=\"text\" class=\"input-text\" name=\"nombreUsuario\" size=\"80\" maxlength=\"60\" value=\"$nombreUsuario\"></td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("tema")."\">TEMA: </td><td><select name=\"tema\" class=\"select\">";
    $archivos=$this->listarDirectorios();
    $tm=$this->RevisarTema($uid);
		if($spia==true)
		{
       if(empty($tm))
			 {
			  		$this->salida .=" <option value=\"-1\">Default</option>";
						for($i=0;$i<sizeof($archivos);$i++){
								if($archivos[$i]==$tema){
									$this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
								}else{
									$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
								}
						}
				}
				else
				{
            $this->salida .=" <option value=\"-1\">Default</option>";
						for($i=0;$i<sizeof($archivos);$i++){
								if($archivos[$i]==$tm){
								  $this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
								}else{
									$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
								}
						}
					}
		}
    elseif(empty($spia))
		{

				$this->salida .=" <option value=\"-1\">Default</option>";
				for($i=0;$i<sizeof($archivos);$i++){
						if($archivos[$i]==$tema){
							$this->salida .=" <option value=\"$archivos[$i]\" selected>$archivos[$i]</option>";
						}else{
							$this->salida .=" <option value=\"$archivos[$i]\">$archivos[$i]</option>";
						}
				}
		}
		$this->salida .= "       </select></td></tr>";
    if(empty( $_SESSION['USER']['DIAS']))
		{
			$arr=$this->TraerUserDias($uid);
			$numero=$arr[1];
    	 if(!empty($arr[0]))
			 {
					$vect=explode(" ",$arr[0]);
					$fecha_vect=explode("-",$vect[0]);  //partimos el vector para quitar del timestamp -> las horas...
					$fechacaduca=$fecha_vect[2]."-".$fecha_vect[1]."-".$fecha_vect[0];
			 }
			 else
			 {
					$fechacaduca='';
			 }
		}
		else
		{
     $numero=$_SESSION['USER']['DIAS'];
		}
		$this->salida .= "              <tr class=\"modulo_list_claro\"><td class=\"label\" align=\"center\">ACTIVO USER  <input type=\"checkbox\" name=\"activo\" checked></td>";
    $this->salida .= "              <td align=\"center\" class=\"label\">ACTIVO EMPRESA  <input type=\"checkbox\" name=\"administrador\" checked></td></tr>";
		//textarea { min-width:100%;max-width:100%;width:100%; }
    $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCIÓN: </td><td><textarea style=\" width:80%\"  class=\"textarea\" name=\"descripcion\"  cols=\"20\" rows=\"2\">$descripcion</textarea></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("loginUsuario")."\">LOGIN: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"loginUsuario\" maxlength=\"25\" value=\"$loginUsuario\"></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("fechac")."\">FECHA CADUCIDAD CUENTA: </td><td align=\"left\"><input type=\"text\" class=\"input-text\" name=\"caducidad\" size='11' maxlength=\"10\" value=\"$fechacaduca\">".ReturnOpenCalendario('forma','caducidad','-')."</td>";
    $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"left\" class=\"".$this->SetStyle("loginUsuario")."\">CADUCIDAD CONTRASEÑA : </td><td align=\"left\"><select name=\"dias\" class=\"select\">";
		//traemos las variables de caducidad.
		$fecha_cadu=$this->GetCaducidadContrasena();
		for($i=0;$i<sizeof($fecha_cadu);$i++)
		{
		  if($fecha_cadu[$i][caducidad_id]==$numero)
									{
										$this->salida .=" <option value=\"".$fecha_cadu[$i][caducidad_id]."\" selected>".$fecha_cadu[$i][descripcion]."</option>";

									}
									else
									{
										$this->salida.="<option value=".$fecha_cadu[$i][caducidad_id].">".$fecha_cadu[$i][descripcion]."</option>";
									}

		}
		 $this->salida .= "</select>&nbsp;&nbsp;&nbsp;<font><b>Días</b></font></td></tr>";
	   $this->salida .= "				       </tr>";
    if(!$consulta){
		 // $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("password")."\">PASSWORD: </td><td><input type=\"password\" class=\"input-text\" name=\"password\" maxlength=\"40\" value=\"$password\"></td></tr>";
     // $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("passwordReal")."\">CONFIRMACION PASSWORD: </td><td><input type=\"password\" class=\"input-text\" name=\"passwordReal\" maxlength=\"40\" value=\"$passwordReal\"></td></tr>";
    }
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";

		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Insertar\"><br></td>";
		$this->salida .= "			      </form>";
    $action3=ModuloGetURL('system','Usuarios','admin','Menu',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$this->salida .= "           <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Cancelar\"></td></tr>";
    $this->salida .= "			      </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "            </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	/**
* Funcion donde se visializa la forma que pide datos para insertar un usuario
* @return boolean
*/

	function FormaInsertarProfesionalUsuarioSistema($uid,$nombre,$usuario,$empresa,$descripcion,$modificacion){
  	if($modificacion=='1'){
			$this->salida  = ThemeAbrirTabla('MODIFICAR PROFESIONAL');
		}else{
    	$this->salida  = ThemeAbrirTabla('INSERTAR PROFESIONAL');
		}			
		$action=ModuloGetURL('system','Usuarios','admin','ValidacionInsertarProfesional',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"empresa"=>$empresa,"descripcion"=>$descripcion,"modificacion"=>$modificacion));
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"60%\" border=\"0\" align=\"center\">";
		
		$this->salida .= " <tr><td>";		
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL USUARIO</legend>";
		$this->salida .= " 		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">UID</td>";
		$this->salida .= " 		<td>$uid</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">LOGIN</td>";
		$this->salida .= " 		<td>$usuario</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">NOMBRE USUARIO</td>";
		$this->salida .= " 		<td>$nombre</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">EMPRESA</td>";
		$this->salida .= " 		<td>$descripcion</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";				
		$this->salida .= "      				 <input type=\"hidden\" name=\"tipoDocumentoAnt\" value=\"".$_REQUEST['tipoDocumentoAnt']."\">";				
		$this->salida .= "      				 <input type=\"hidden\" name=\"DocumentoAnt\" value=\"".$_REQUEST['DocumentoAnt']."\">";				
		$this->salida .= "      				 <input type=\"hidden\" name=\"especialidadAnt\" value=\"".$_REQUEST['especialidadAnt']."\">";				
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";
		$this->salida .= " <fieldset><legend class=\"field\">DATOS DEL PROFESIONAL</legend>";
		$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
		$this->salida .= "				       <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("tipoDocumento")."\" width=\"40%\" align=\"left\">TIPO DOCUMENTO </td>";
		$this->salida .= "				       <td class=\"modulo_list_oscuro\" align=\"left\">";
		$Tipos=$this->TiposPaciente();		
		if($Tipos){
			$this->salida.="								<select name='tipoDocumento' class='select'>";			
			for($i=0;$i<sizeof($Tipos);$i++){			
				if($_REQUEST['tipoDocumento']==$Tipos[$i][tipo_id_paciente]){
					$this->salida.="						<option value=".$Tipos[$i][tipo_id_paciente]." selected>".$Tipos[$i][descripcion]."</option>";
				}else{
					$this->salida.="						<option value=".$Tipos[$i][tipo_id_paciente].">".$Tipos[$i][descripcion]."</option>";
				}				
			}
			$this->salida.="								</select>";
		}
		$this->salida .= "				       </td>";
		$this->salida .= "								</tr>";
		$this->salida .= "				       <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("Documento")."\" width=\"40%\" align=\"left\">DOCUMENTO </td>";								
		$this->salida .= "      				 <td><input type=\"text\" maxlength=\"32\" class=\"input-text\" name=\"Documento\" value=\"".$_REQUEST['Documento']."\"></td>";				
		$this->salida .= "								</tr>";
		/*$this->salida .= "				       <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      				 <td class=\"".$this->SetStyle("Nombres")."\" width=\"40%\" align=\"left\">NOMBRES </td>";
		$this->salida .= "      				 <td><input type=\"text\" maxlength=\"50\" name=\"Nombres\" value=\"".$_REQUEST['Nombres']."\" class=\"input-text\"></td>";		
		$this->salida .= "    					 </tr>";
		$this->salida .= "               <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("Apellidos")."\" width=\"40%\" align=\"left\">APELLIDOS </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"50\" name=\"Apellidos\" value=\"".$_REQUEST['Apellidos']."\" class=\"input-text\"></td>";		
		$this->salida .= "    					</tr>";	*/
		$this->salida .= "    					<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("Sexo")."\">SEXO </td><td><select name=\"Sexo\" class=\"select\">";
		$sexo_id=$this->sexo();
		if($sexo_id){
			$this->salida.="								<select name='Sexo' class='select'>";
			$this->salida.="								<option value=-1 selected>----Seleccione----</option>";      
			for($i=0;$i<sizeof($sexo_id);$i++){			
				if($_REQUEST['Sexo']==$sexo_id[$i][sexo_id]){
					$this->salida.="						<option value=".$sexo_id[$i][sexo_id]." selected>".$sexo_id[$i][descripcion]."</option>";
				}else{
					$this->salida.="						<option value=".$sexo_id[$i][sexo_id].">".$sexo_id[$i][descripcion]."</option>";
				}				
			}
			$this->salida.="								</select>";
		}		
		$this->salida .= "                </td></tr>";
		$ru='classes/BuscadorDestino/selectorCiudad.js';
		$rus='classes/BuscadorDestino/selector.php';
		$this->salida .= "<script languaje='javascript' src=\"$ru\"></script>";		
		if(!$_REQUEST['pais']){
				$_REQUEST['pais']=GetVarConfigAplication('DefaultPais');
				$_REQUEST['dpto']=GetVarConfigAplication('DefaultDpto');
				$_REQUEST['mpio']=GetVarConfigAplication('DefaultMpio');
		}	
		$this->salida .= "    					<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      				<td class=\"".$this->SetStyle("pais")."\">PAIS</td>";
		$NomPais=$this->nombre_pais($_REQUEST['pais']);
		$this->salida .= "      				<td><input type=\"text\" name=\"npais\" value=\"$NomPais\" class=\"input-text\" readonly size=\"30\">";
		$this->salida .= "      				<input type=\"hidden\" name=\"pais\" value=\"".$_REQUEST['pais']."\" class=\"input-text\"></td>";		
		$this->salida .= "    					</tr>";
		$this->salida .= "    					<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      				<td class=\"".$this->SetStyle("dpto")."\">DEPARTAMENTO </td>";
		$NomDpto=$this->nombre_dpto($_REQUEST['pais'],$_REQUEST['dpto']);
		$this->salida .= "      				<td><input type=\"text\" name=\"ndpto\" value=\"$NomDpto\" class=\"input-text\" readonly size=\"30\">";
		$this->salida .= "      				<input type=\"hidden\" name=\"dpto\" value=\"".$_REQUEST['dpto']."\" class=\"input-text\"></td>";		
		$this->salida .= "    					</tr>";
		$this->salida .= "    					<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      				<td class=\"".$this->SetStyle("mpio")."\">CIUDAD </td>";
		$NomCiudad=$this->nombre_ciudad($_REQUEST['pais'],$_REQUEST['dpto'],$_REQUEST['mpio']);
		$this->salida .= "      				<td><input type=\"text\" name=\"nmpio\"  value=\"$NomCiudad\" class=\"input-text\" readonly size=\"30\">";
		$this->salida .= "       				<input type=\"hidden\" name=\"mpio\" value=\"".$_REQUEST['mpio']."\" class=\"input-text\" >";
		$this->salida .= "       				<input class=\"input-submit\" type=\"button\" name=\"Cambiar\" value=\"Cambiar\" onclick=\"abrirVentana('Buscador_Destino','$rus',450,200,0,this.form,1)\">";
		$this->salida .= "       				</td>";
		$this->salida .= "    					</tr>";				
		/*$this->salida .= "				       <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("departamento")."\" width=\"40%\" align=\"left\">DEPARTAMENTO<BR>EMPRESA</td>";
		$this->salida .= "				       <td class=\"modulo_list_oscuro\" align=\"left\">";
		$datos=$this->ComboDepartamentos($empresa);
		if($datos){
			$this->salida.="								<select name='departamento' class='select'>";
			$this->salida.="								<option value=-1 selected>----Seleccione----</option>";      
			for($i=0;$i<sizeof($datos);$i++){			
				if($_REQUEST['departamento']==$datos[$i][departamento]){
					$this->salida.="						<option value=".$datos[$i][departamento]." selected>".$datos[$i][descripcion]."</option>";
				}else{
					$this->salida.="						<option value=".$datos[$i][departamento].">".$datos[$i][descripcion]."</option>";
				}				
			}
			$this->salida.="								</select>";
		}
		$this->salida .= "								</td>";
		$this->salida .= "								</tr>";
		*/
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("Direccion")."\">DIRECCION </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"100\" name=\"Direccion\" value=\"".$_REQUEST['Direccion']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("telefono")."\">TELEFONO </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"30\" name=\"telefono\" value=\"".$_REQUEST['telefono']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("fax")."\">FAX </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"15\" name=\"fax\" value=\"".$_REQUEST['fax']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("e_mail")."\">E - MAIL </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"60\" name=\"e_mail\" value=\"".$_REQUEST['e_mail']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("celular")."\">CELULAR </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"15\" name=\"celular\" value=\"".$_REQUEST['celular']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("tipo_profesional")."\">TIPO PROFESIONAL </td><td><select name=\"tipo_profesional\" class=\"select\">";
		$tipos_profesionales=$this->TiposProfesionales();
		if($tipos_profesionales){
			$this->salida.="								<select name='tipo_profesional' class='select'>";
			$this->salida.="								<option value=-1 selected>----Seleccione----</option>";      
			for($i=0;$i<sizeof($tipos_profesionales);$i++){			
				if($_REQUEST['tipo_profesional']==$tipos_profesionales[$i][tipo_profesional]){
					$this->salida.="						<option value=".$tipos_profesionales[$i][tipo_profesional]." selected>".$tipos_profesionales[$i][descripcion]."</option>";
				}else{
					$this->salida.="						<option value=".$tipos_profesionales[$i][tipo_profesional].">".$tipos_profesionales[$i][descripcion]."</option>";
				}				
			}
			$this->salida.="								</select>";
		}		
		$this->salida .= "                </td></tr>";		
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("especialidad")."\">ESPECIALIDAD </td><td><select name=\"especialidad\" class=\"select\">";
		$especialidades=$this->Especialidades();
		if($especialidades){
			$this->salida.="								<select name='especialidad' class='select'>";
			$this->salida.="								<option value=-1 selected>----Seleccione----</option>";      
			for($i=0;$i<sizeof($especialidades);$i++){			
				if($_REQUEST['especialidad']==$especialidades[$i][especialidad]){
					$this->salida.="						<option value=".$especialidades[$i][especialidad]." selected>".$especialidades[$i][descripcion]."</option>";
				}else{
					$this->salida.="						<option value=".$especialidades[$i][especialidad].">".$especialidades[$i][descripcion]."</option>";
				}				
			}
			$this->salida.="								</select>";
		}		
		$this->salida .= "                </td></tr>";		
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("tarjetaProf")."\">TARJETA PROFESIONAL </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"20\" name=\"tarjetaProf\" value=\"".$_REQUEST['tarjetaProf']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("universidad")."\">UNIVERSIDAD </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"60\" name=\"universidad\" value=\"".$_REQUEST['universidad']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "      					<td class=\"".$this->SetStyle("reg_salud")."\">REGISTRO DE SALUD DPTAL. </td>";
		$this->salida .= "      					<td><input type=\"text\" maxlength=\"60\" name=\"reg_salud\" value=\"".$_REQUEST['reg_salud']."\" class=\"input-text\" size=\"30\"></td>";		
		$this->salida .= "    						</tr>";
		$this->salida .= "       					<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "       					<td colspan=\"2\" class=\"".$this->SetStyle("observacion")."\">OBSERVACIONES<br><textarea name=\"observacion\" maxlength=\"256\" cols=\"65\" rows=\"3\" class=\"textarea\">".$_REQUEST['observacion']."</textarea>";
		$this->salida .= "       					</td>";
		$this->salida .= "       					</tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Continuar\"><br></td>";
		$this->salida .= "	</form>";
		if($modificacion=='1'){
			$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosProfesionales');		
		}else{
    	$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistemaProfesional');		
		}	
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "   </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function FormaDptosProfesionalesUsuario($uid,$nombre,$usuario,$empresa,$descripcion,$modificacion,
		$tipoDocumento,$Documento,$tipoDocumentoAnt,$DocumentoAnt,$especialidadAnt,$pais,$dpto,
		$mpio,$Direccion,$especialidad,$tipo_profesional,$Sexo,
		$telefono,$fax,$e_mail,$celular,$tarjetaProf,$universidad,$reg_salud,$observacion){
		
		if($modificacion=='1'){
			$this->salida  = ThemeAbrirTabla('MODIFICAR PROFESIONAL');
		}else{
    	$this->salida  = ThemeAbrirTabla('INSERTAR PROFESIONAL');
		}
		$action=ModuloGetURL('system','Usuarios','admin','InsertarProfesional',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"empresa"=>$empresa,"descripcion"=>$descripcion,"modificacion"=>$modificacion,
		"tipoDocumento"=>$tipoDocumento,"Documento"=>$Documento,"tipoDocumentoAnt"=>$tipoDocumentoAnt,"DocumentoAnt"=>$DocumentoAnt,"especialidadAnt"=>$especialidadAnt,"pais"=>$pais,"dpto"=>$dpto,
		"mpio"=>$mpio,"Direccion"=>$Direccion,"especialidad"=>$especialidad,"tipo_profesional"=>$tipo_profesional,"Sexo"=>$Sexo,
		"telefono"=>$telefono,"fax"=>$fax,"e_mail"=>$e_mail,"celular"=>$celular,"tarjetaProf"=>$tarjetaProf,"universidad"=>$universidad,"reg_salud"=>$reg_salud,"observacion"=>$observacion));
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"60%\" border=\"0\" align=\"center\">";
		
		$this->salida .= " <tr><td>";		
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL USUARIO</legend>";
		$this->salida .= " 		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">UID</td>";
		$this->salida .= " 		<td>$uid</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">LOGIN</td>";
		$this->salida .= " 		<td>$usuario</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">NOMBRE USUARIO</td>";
		$this->salida .= " 		<td>$nombre</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		<tr class=\"modulo_list_claro\">";
		$this->salida .= " 		<td width=\"30%\" class=\"label\">EMPRESA</td>";
		$this->salida .= " 		<td>$descripcion</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";
		$this->salida .= " <fieldset><legend class=\"field\">DEPARTAMENTOS ASIGNADOS AL PROFESIONAL</legend>";
		$cont=0;
		$Seleccion=$_REQUEST['Seleccion'];
		
		$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"100%\" align=\"center\">";
		$departamentos=$this->ComboDepartamentos($empresa);
		for($i=0;$i<sizeof($departamentos);$i++){
			$che='';
			if(in_array($departamentos[$i]['departamento'],$Seleccion)){
				$che='checked';	
			}
			if($cont % 2 > 0){
				$this->salida .= "    						<td width=\"45%\">".$departamentos[$i]['descripcion']."</td>";			
				$this->salida .= "    						<td align=\"center\" width=\"5%\"><input $che type=\"checkbox\" name=\"Seleccion[]\" value=\"".$departamentos[$i]['departamento']."\"></td>";			
				$this->salida .= "    						</tr>";
			}else{
				$this->salida .= "    						<tr class=\"modulo_list_oscuro\">";
				$this->salida .= "    						<td width=\"45%\">".$departamentos[$i]['descripcion']."</td>";			
				$this->salida .= "    						<td align=\"center\" width=\"5%\"><input $che type=\"checkbox\" name=\"Seleccion[]\" value=\"".$departamentos[$i]['departamento']."\"></td>";			
			}	
			$cont++;			
		}	
		if($cont % 2 > 0){
			$this->salida .= "    						<td colspan=\"2\">&nbsp;</td></tr>";
		}
		$this->salida .= "			         </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "  <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Insertar\"><br></td>";
		$this->salida .= "	</form>";
		if($modificacion=='1'){
			$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosProfesionales');		
		}else{
    	$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistemaProfesional');		
		}	
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
		//	$this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";	
		$this->salida .= ThemeCerrarTabla();
		return true;		
	}
		
	
	
	
	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($boton){
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
		}
	  else{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
	  }
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}






/*funcion donde se encuentra todas las opciones del administrador de empresas*/
		function Menu()
 {
      //esta variable es la q ordena por el numero de usuario 'usuario_id'
			//de forma acendente o descendente.
			unset($_SESSION['USUARIOS']['ORDENAMIENTO']);
			$this->salida.= ThemeAbrirTabla('MENÚ DE ADMINISTRACION SIIS','60%');
     	$this->salida.="<br><table border=\"0\"    align=\"center\"   width=\"50%\" >";
			$this->salida.="<tr>";
	    $this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE ADMINISTRACION</td>";
			$this->salida.="</tr>";
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema');
			$ac=ModuloGetURL('system','Usuarios','admin','ListadoGeneralSistema');
			$ax=ModuloGetURL('system','Usuarios','admin','Usuario');
			$action=ModuloGetURL('system','Usuarios','admin','ListadoPerfiles');
			$actionProf=ModuloGetURL('system','Usuarios','admin','MenuUsuariosSistemaProfesionales');;			
      $this->salida.="<tr>";
			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a title='ADICIONA NUEVOS USUARIOS AL SISTEMA' href=\"$ax\">CREAR NUEVO USUARIO</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/nuevo_usuario.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a title='PERMITE ADICIONAR MENUS,MODULOS,DEPARTAMENTOS,CAMBIOS DE CONTRASEÑA ETC..' href=\"$acc\">PROPIEDADES DE USUARIOS</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/usuarios.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr>";
			$this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a title='PERMITE VISUALIZAR LOS LOGUEOS DE USUARIOS EN LAS MAQUINAS' href=\"$ac\">PROPIEDADES DE EQUIPOS</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/pc.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr>";
			$this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a title='PERMITE CREAR NUEVOS PERFILES' href=\"$action\">CREAR PERFILES</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/pc.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a title='PERMITE CREAR Y MODIFICAR PROFESIONALES' href=\"$actionProf\">CREAR PROFESIONAL</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/proveedor.png\">";
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
 
 	function MenuUsuariosSistemaProfesionales()
 {	
      //esta variable es la q ordena por el numero de usuario 'usuario_id'
			//de forma acendente o descendente.
			unset($_SESSION['USUARIOS']['ORDENAMIENTO']);
			unset($_SESSION['USUARIOS']['FILTRO']);
			$this->salida.= ThemeAbrirTabla('MENÚ DE PROFESIONALES DEL SISTEMA');
     	$this->salida.="<br><table border=\"0\"    align=\"center\"   width=\"50%\" >";
			$this->salida.="<tr>";
	    $this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE ADMINISTRACION</td>";
			$this->salida.="</tr>";			
			$actionProf=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistemaProfesional');
			$actionModyProf=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosProfesionales');
      $this->salida.="<tr>";
			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$actionProf\">CREAR NUEVO PROFESIONAL</a>&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/nuevo_usuario.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$actionModyProf\">MODIFICAR PROFESIONAL</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/nuevo_usuario.png\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="<table align=\"center\" width='20%' border=\"0\">";
			$action2=ModuloGetURL('system','Usuarios','admin','Menu');
			$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
			$this->salida .= "</tr>";
			$this->salida.="</table><br>";
			$this->salida.= ThemeCerrarTabla();
			return true;
 }

/**
* Funcion donde se visializa la forma que pide datos para modificar el password de un usuario
* @return boolean
*/

  function FormaModificarPasswd($action,$password,$passwordReal,$nombre,$usuario){

		$this->salida  = ThemeAbrirTabla('CAMBIO CONTRASEÑA :  '.$usuario.'');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaContraseña\" action=\"$action\" method=\"post\">";
    $this->salida .= "			      <table width=\"80%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "            </td></tr>";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DATOS CONTRASEÑA</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"60%\" align=\"center\" class=\"modulo_table\">";
    $this->salida .= "              <tr><td class=\"modulo_table_list_title\" width=\"23%\">Usuario :</td><td class=\"modulo_list_claro\">$nombre</td></tr>";
		$this->salida .= "  <tr><td class=\"modulo_table_list_title\" width=\"20%\">Login :</td><td class=\"modulo_list_claro\">$usuario</td></tr>";
  //  $this->salida .= "              <tr><td colspan=\"2\"><BR></td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td width=\"30%\" class=\"".$this->SetStyle("password")."\">Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input type=\"password\" class=\"input-text\" name=\"password\" maxlength=\"40\" value=\"$password\"></td></tr>";
    $this->salida .= "				       <tr class=\"modulo_table_list_title\"><td width=\"30%\" class=\"".$this->SetStyle("passwordReal")."\">Repita Password: </td><td align=\"left\" class=\"modulo_list_oscuro\" colspan=\"1\"><input  type=\"password\" class=\"input-text\" name=\"passwordReal\" maxlength=\"40\" value=\"$passwordReal\"></td></tr>";
		$this->salida .= "	  	          <input type=\"hidden\" name=\"usuario\" value=\"$usuario\">";
		$this->salida .= "	  	          <input type=\"hidden\" name=\"nombre\" value=\"$nombre\">";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";

    $this->salida .= "			      <table width=\"40%\"  border=\"0\" align=\"center\">";
		$this->salida .= "              <tr><td align=\"center\"><br><input class=\"input-submit\" name=\"aceptar\" type=\"submit\" value=\"Cambiar\"></td>";
		$this->salida .= " <td  align=\"center\"><br><input class=\"input-submit\" name=\"resetear\" type=\"submit\" value=\"Resetear\"></td>";
		$this->salida .= "			      </form>";

		$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Cancelar\"></td></tr>";
		//$this->salida .= "            </table>";
		$this->salida .= "            </table><BR>";
		$this->salida .= "			      </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




	function PermisosMenuUsuario($dats,$uid,$nombre,$usuario,$descripcion)
 {
	$this->salida .= ThemeAbrirTabla('MENUS DEL USUARIO');
	//$this->salida .= "<br>";
	$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
	if($dats)
				{

							$this->salida .= "            <tr><td>";
							$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"85%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">".$usuario."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">".$nombre."</td></tr>";
            if(!empty($descripcion))
           	{
								$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">DESCRIPCIÓN: </td><td class=\"modulo_list_oscuro\" align=\"left\">".$descripcion."</td></tr>";
							}
							$this->salida .= "			         </table>";
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







/**
* Funcion donde se crea un nuevo perfil
* @return boolean
*/

	function FormaInsertNewPerfil($empresa,$desc){
		$this->salida  = ThemeAbrirTabla('INSERTAR PERFIL');
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"".ModuloGetURL('system','Usuarios','admin','InsertarPerfil',array("desc"=>$desc,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']))."\" method=\"post\">";
    $this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "            </td></tr>";

		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DATOS PERFIL</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"80%\" align=\"center\">";
		$this->salida .= "				       <tr class=\"modulo_list_oscuro\"><td class=\"".$this->SetStyle("emp")."\" width=\"40%\" align=\"left\">EMPRESA: </td><td class=\"modulo_list_oscuro\" align=\"left\">";
		$datos=$this->ComboEmpresa();

			$this->salida.="<select name='empresa' class='select'>";
			$this->salida.="<option value=-1>----Seleccione----</option>";

      if(empty($empresa))
		  {
			  	$empresa=$_REQUEST['empresa'];
					$desc=$_REQUEST['descrip'];
					for($i=0;$i<sizeof($datos);$i++)
					{
						$this->salida.="<option value=".$datos[$i][empresa_id].">".$datos[$i][razon_social]."</option>";

					}
				$this->salida.="</select>";
			}
			else
			{
			for($i=0;$i<sizeof($datos);$i++)
								{
                  if($datos[$i][empresa_id]==$empresa)
									{
										$this->salida .=" <option value=\"".$datos[$i][empresa_id]."\" selected>".$datos[$i][razon_social]."</option>";

									}
									else
									{
										$this->salida.="<option value=".$datos[$i][empresa_id].">".$datos[$i][razon_social]."</option>";
									}

								}
							$this->salida.="</select>";
			}


		$this->salida .= "	</td></tr>";

	  $this->salida .= "				       <tr  class=\"modulo_list_claro\"><td class=\"".$this->SetStyle("des")."\">DESCRIPCIÓN: </td><td><textarea class=\"textarea\" name=\"descrip\" cols=\"38\" rows=\"4\">$desc</textarea></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";

		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Insertar\"><br></td>";
		$this->salida .= "			      </form>";
    $action3=ModuloGetURL('system','Usuarios','admin','ListadoPerfiles');
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Cancelar\"></td></tr>";
    $this->salida .= "			      </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "            </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/*funcion que visualiza los usuarios del sistema para adicionar*/
function ListadoPerfiles()
 {
	$this->salida .= ThemeAbrirTabla('LISTADO DE PERFILES');
	$dats=$this->BuscarPerfil();
	$user=$this->TraerUsuario();
	if($dats)
				{     $this->salida .= "<br><table border=\"0\" width=\"80%\" align=\"center\">";
							$actionInsertar=ModuloGetURL('system','Administrador','admin','InsertarUsuarioSistema');
              $this->salida .= $this->SetStyle("MensajeError");
							$this->salida .= "            <tr><td>";
							$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\">";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">".$user[0][usuario_id]." &nbsp;&nbsp;&nbsp;".$user[0][usuario]."</td></tr>";
							$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">".$user[0][nombre]."</td></tr>";
            	$this->salida .= "			         </table>";
							$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"85%\">";
            	$this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td align=\"center\">Perfil</td>";
							$this->salida.="  <td align=\"center\">Empresa</td>";
							$this->salida.="  <td align=\"center\">Descripción</td>";
							$this->salida.="  <td colspan='2' align=\"center\">Accion</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($dats);$i++)
							{
                	$perfil=$dats[$i][perfil_id];
	   							$razon=$dats[$i][razon_social];
									$desc=$dats[$i][descripcion];
									$empresa_id=$dats[$i][empresa_id];
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" align=\"center\">";
                  $this->salida.="  <td  align=\"center\">$perfil</td>";
									$this->salida.="  <td  align=\"center\">$razon</td>";
									$this->salida.="  <td  align=\"center\">$desc</td>";
									$this->salida.="  <td><a href=\"".ModuloGetURL('system','Usuarios','admin','ListadoMenu',array("perfil"=>$perfil,"razon"=>$razon,"desc"=>$desc))."\">ADICIONAR MENÚ </a></td>";
									$this->salida.="  <td><a href=\"".ModuloGetURL('system','Usuarios','admin','BorrarPerfil',array("perfil"=>$perfil,"desc"=>$desc,"id"=>$empresa_id))."\">ELIMINAR </a></td>";
                  $this->salida.="</tr>";
							}
							   	$this->salida.="</td></tr>";
									$this->salida.="</table>";
									$this->salida.="</table>";

									$this->salida.="<br><table align='center'>";
									$this->salida.="<tr>";
         					$this->salida.="<td align='center' class='normal_10'><a href=\"".ModuloGetURL('system','Usuarios','admin','FormaInsertNewPerfil',array("desc"=>$desc))."\">CREAR NUEVO PERFIL</a></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";

									$this->salida.="<table align=\"center\" border=\"0\"  width=\"20%\">";
									$this->salida.="<tr>";
									$this->salida .='<form name="forma" action="'.ModuloGetURL('system','Usuarios','admin','Menu').'" method="post">';
									$this->salida.="  <td align=\"center\">";
									$this->salida .="<br><input type=\"submit\" class='input-submit' align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
									$this->salida.="</tr>";
									$this->salida.="</table>";

				}
        else
        {
								$this->salida.="<table align=\"center\">";
								$this->salida.="<tr>";
								$this->salida.="  <td class='label_error' align=\"center\"><img src=\"".GetThemePath()."/images/informacion.png\"  border=\"0\">&nbsp;&nbsp;&nbsp;NO EXISTEN PERFILES</td>";
								$this->salida.="</tr>";
								$this->salida.="</table>";

								$this->salida.="<br><table align='center'>";
								$this->salida.="<tr>";
								$this->salida.="<td align='center' class='normal_10'><a href=\"".ModuloGetURL('system','Usuarios','admin','FormaInsertNewPerfil',array("desc"=>$desc))."\">CREAR NUEVO PERFIL</a></td>";
								$this->salida.="</tr>";
								$this->salida.="<tr>";
								$this->salida.="  <td align=\"center\">";
								$this->salida .='<form name="formares" action="'.ModuloGetURL('system','Usuarios','admin','Menu').'" method="post">';
								$this->salida .="<br><br><input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Volver\" class=\"input-submit\"></form></td>";
        				$this->salida.="</tr>";
								$this->salida.="</table>";

				 }
								$this->salida .= ThemeCerrarTabla();
  return true;
 }




//parametros,$per->perfil,$empresa->nombre empresa,$nom->nombre del perfil.
function ListadoMenu($per,$empresa,$nom)
{
				$this->salida = ThemeAbrirTabla('LISTADO DE MENUS');

				if(empty($per))
				{
					$per=$_REQUEST['perfil'];
					$empresa=$_REQUEST['razon'];
					$nom=$_REQUEST['desc'];
				}
				$vector=$this->TraerMenus($per);
        if($vector)
				{
          $this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
          $action=ModuloGetURL('system','Usuarios','admin','InsertarAPerfiMenu',array("per"=>$per,"razon"=>$empresa,"nom"=>$nom));
					$this->salida .= "           <form name=\"formades\" action=\"$action\" method=\"post\">";

					$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\">";
					$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$empresa."</td></tr>";
					$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">PERFIL: </td><td class=\"modulo_list_claro\" align=\"left\">".$per."&nbsp;&nbsp;".$nom."</td></tr>";
					$this->salida .= "			         </table><br>";

					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"3\">PERMISOS MODULOS</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>Menú</td>";
					$this->salida.="  <td>Descripción</td>";
					$this->salida.="  <td>Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
          $this->salida.="</tr>";
					for($i=0;$i<sizeof($vector);$i++)
					{
							$menu=$vector[$i][menu_id];
							$nombre=$vector[$i][menu_nombre];
							$desc=$vector[$i][descripcion];
							$check=$vector[$i][perfil_id];
							if(!is_null($check))
							{
               $chequeo='checked';
							}
							else
							{
               $chequeo='';
							}
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" align=\"left\">";
							$this->salida.="  <td width=\"10%\">$nombre</td>";
							$this->salida.="  <td width=\"30%\">$desc</td>";
							$this->salida.="  <td align='center' width=\"10%\"><input type=checkbox name=op[$i] value=$menu  $chequeo></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Guardar\"></form></td>";
					$action2=ModuloGetURL('system','Usuarios','admin','ListadoPerfiles');
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";

				}
$this->salida .= ThemeCerrarTabla();
return true;
}


//parametros,$per->perfil,$empresa->nombre empresa,$nom->nombre del perfil.
function ListadoPerfilUsuario($uid,$user,$nom,$empresa,$nombreE)
{
				$this->salida = ThemeAbrirTabla('LISTADO DE PERFILES USUARIO');

				if(empty($uid))
				{
					$uid=$_REQUEST['uid'];
					$user=$_REQUEST['usuario'];
					$nom=$_REQUEST['nombre'];
					$empresa=$_REQUEST['empID'];
					$nombreE=$_REQUEST['nom_emp'];
				}
				$vector=$this->TraerPerfilesUser($uid,$empresa);
        if($vector)
				{
          $this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
          $action=ModuloGetURL('system','Usuarios','admin','InsertarAPerfilUsuario',array("uid"=>$uid,"user"=>$user,"nom"=>$nom,"NoEmp"=>$nombreE,"empresa"=>$empresa));
					$this->salida .= "           <form name=\"formades\" action=\"$action\" method=\"post\">";

					$this->salida .= "              <table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\">";
					$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN: </td><td class=\"modulo_list_claro\" align=\"left\">".$uid."&nbsp;&nbsp;".strtoupper($user)."</td></tr>";
					$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">".$nom."</td></tr>";
					$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">EMPRESA: </td><td class=\"modulo_list_claro\" align=\"left\">".$nombreE."</td></tr>";
					$this->salida .= "			         </table><br>";

					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"70%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"3\">PERMISOS MODULOS</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td>Menú</td>";
					$this->salida.="  <td>Descripción</td>";
					$this->salida.="  <td>Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
          $this->salida.="</tr>";
					for($i=0;$i<sizeof($vector);$i++)
					{
							$perfil_id=$vector[$i][perfil_id];
							$desc=$vector[$i][descripcion];
							$check=$vector[$i][usuario_id];
							if(!is_null($check))
							{
               $chequeo='checked';
							}
							else
							{
               $chequeo='';
							}
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" align=\"left\">";
							$this->salida.="  <td width=\"10%\">$perfil_id</td>";
							$this->salida.="  <td width=\"30%\">$desc</td>";
							$this->salida.="  <td align='center' width=\"10%\"><input type=checkbox name=op[$i] value=$perfil_id  $chequeo></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Guardar\"></form></td>";
					$action2=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";

				}
				else
				{
				  $this->salida.="<table align=\"center\" width='80%' border=\"0\">";
					$this->salida .= "<tr>";
					$this->salida .= "<td align='center' class='label_error'>ESTA EMPRESA NO POSEE PERFILES</td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";

					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "<tr>";
					$action2=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of'],'busqueda'=>$_REQUEST['busqueda']));
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
				}
$this->salida .= ThemeCerrarTabla();
return true;
}

}//fin clase user
?>

