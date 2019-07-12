<?php
/**
*MODULO para el Manejo de Usuarios del Sistema
*
* @author Lorena Aragon & Jairo Duvan Diaz Martinez
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


class system_Menu_adminclasses_HTML extends system_Menu_admin
{
	/**
	*Constructor de la clase app_Usuarios_user_HTML
	*El constructor de la clase app_Usuarios_user_HTML se encarga de llamar
	*a la clase app_Usuarios_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function system_Menu_admin_HTML()
	{
		$this->salida='';
		$this->system_Menu_admin();
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
    $this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
		$this->salida .= "				       <td width=\"5%\">IP</td>";
    $this->salida .= "	  	         <td width=\"7%\">HOST</td>";
		$this->salida .= "              <td width=\"70%\">USUARIOS  --  INICIO  --  FINAL</td>";
		$this->salida .= "              <td width=\"10%\">BLOQUEO</td>";
		$this->salida .= "              <td width=\"5%\"></td>";
		$this->salida .= "            </tr>";
    $y=1;
		$i=0;
		while($i<sizeof($usuarios)){
		  if($y % 2){
			  $estilo='modulo_list_claro';
			}else{
			  $estilo='modulo_list_oscuro';
			}

		  $bloqueo=$usuarios[$i][sw_bloqueo];

      if($bloqueo=='1'){
        $tipoBloqueo='DESBLOQUEO';
				$img='man-whi.gif';

			}else{
        $tipoBloqueo='BLOQUEO.....';
				$img='man-gr.gif';
			}

      $this->salida .= "<tr class=\"$estilo\">";
			$this->salida .= "     <td align=\"center\"><img src=\"".GetThemePath()."/images/pc.png\">".$usuarios[$i][ip]."</td>";
			$this->salida .= "     <td><img src=\"".GetThemePath()."/images/usuario.gif\">".$usuarios[$i][hostname]."</td>";
			$this->salida .= "     <td align=\"center\">";
			$this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"2\" width=\"99%\" align=\"center\" class=\"modulo_list_oscuro\">";
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
						$this->salida .= "     <td class=\"label_error\" align=\"center\"><img src=\"".GetThemePath()."/images/no_usuarios.gif\">No hay usuarios</td>";
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
								//$this->salida .= "<td colspan=\"3\"></td>";
							//	$this->salida .= "<td></td>";
							//	$this->salida .= "<td></td>";
							//	$this->salida .= "<td></td>";
							//	$this->salida .= "</tr>";
								$b++;
						}
			}
			$this->salida .= "        </table>";
			$this->salida .= "     </td>";
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
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
		$this->salida .= "       </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



function ListadoAccesos($dats,$dir,$host)
{
 $ip=$this->EstadoIps($dir);
 	if($ip=='1'){
        $tipoBloqueo='DESBLOQUEO';
				$img='man-whi.gif';

			}else{
        $tipoBloqueo='BLOQUEO.....';
				$img='man-gr.gif';
			}
		$action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuarioIp',array("ip"=>$dir,"marca"=>true,"host"=>$host,"dats"=>$dats));
    $this->salida  = ThemeAbrirTabla('REVISION DE LOG DE LA IP&nbsp;:&nbsp;'.$dir.'');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
    $this->salida .= "			      <table width=\"85%\" border=\"0\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DIRECCION IP</legend>";
		$this->salida .= "              <table class=\"modulo_table\" cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"99%\" align=\"center\">";
   	$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">DIRECCION IP: </td><td class=\"modulo_list_claro\" align=\"left\">$dir</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"20%\">HOSTNAME: </td><td class=\"modulo_list_oscuro\" align=\"left\">$host</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"25%\" align=\"left\">ESTADO DE lA IP: </td><td class=\"modulo_list_claro\" align=\"left\">&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\"></td></tr>";
		$this->salida .= "              <tr><td class=\"modulo_table_list_title\" class=\"label\" colspan=\"2\"><a href=\"$action\">$tipoBloqueo</a>&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\"></td></tr>";
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
					$this->salida.="<br><table align=\"center\" border=\"0\" class=\"modulo_table_list_title\">";
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
				$this->salida.="<td align=\"center\" class=\"label_error\"><img src=\"".GetThemePath()."/images/info.png\">&nbsp;&nbsp;Este usuario no tiene registros de logueo</td>";
				$this->salida.="</tr>";
				$acc=ModuloGetURL('system','Usuarios','admin','ListadoGeneralSistema');
        $this->salida.="</table><br>";
				$this->salida.="<br><table align=\"center\" border=\"0\" class=\"modulo_table_list_title\">";
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
		$this->salida  = ThemeAbrirTabla('LISTADO USUARIOS SISTEMA');
		$this->salida .= "			      <br><br>";
		$accion=ModuloGetURL('system','Usuarios','admin','main');
		$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
    $this->salida .= "			      <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "            <tr><td>";
		$usuarios=$this->BuscarUsuariosSistema();
		if(!$usuarios){
			$this->salida .= "        <tr><td class=\"label_error\" align=\"center\">NO HAY USUARIOS EN EL SISTEMA</td></tr>";
			$this->salida .= "				<tr><td align=\"center\"><br  ><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
		  $this->salida .= "        </td></tr>";
      $this->salida .= "        </table>";
      $this->salida .= ThemeCerrarTabla();
		  return true;
		}
    $this->salida .= "       <table align=\"center\">";
		$this->salida .="".$this->SetStyle("MensajeError")."";
		$this->salida .= "       </table>";
    $this->salida .= "			      <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"99%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "            <tr class=\"modulo_table_list_title\" align=\"center\">";
    $this->salida .= "				       <td>UID</td>";
    $this->salida .= "	  	         <td>LOGIN</td>";
		$this->salida .= "              <td>NOMBRE USUARIO</td>";
		$this->salida .= "              <td >ACCION</td>";
		$this->salida .= "              <td colspan=\"4\">EVENTOS</td>";
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

      if($activo=='1'){
        $tipoBloqueo='DESACTIVAR';
				$img='man-gr.gif';
        $action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuario',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
			}else{
        $tipoBloqueo='ACTIVAR......';
				$img='man-red.gif';
				$action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuario',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
			}
      $actionEditar=ModuloGetURL('system','Usuarios','admin','LlamaModificarUsuarioSistema',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"tema"=>$tema,"descripcion"=>$descripcion,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
			$actionPasswd=ModuloGetURL('system','Usuarios','admin','LlamaFormaModificarPasswd',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
			$actionPermisos=ModuloGetURL('system','Usuarios','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
			$actionBorrar=ModuloGetURL('system','Usuarios','admin','BorrarUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
		//	$actionDepartamentos=ModuloGetURL('system','Usuarios','user','LlamaAsignarDepartamentosUsuario',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario));
			$this->salida .= "		 <tr class=\"$estilo\">";
      $this->salida .= "     <td align=\"center\">$uid</td>";
      $this->salida .= "     <td>$usuario</td>";
			$this->salida .= "     <td>$nombre</td>";
			//$this->salida .= "     <td>$activo</td>";
			//$this->salida.="  <td><img src=\"".GetThemePath()."/images/$img\"></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/$img\">&nbsp;<a href=\"$action\">$tipoBloqueo</a></td>";
      $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/edita.png\">&nbsp;<a href=\"$actionEditar\">EDITAR</a></td>";
			$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/pass.png\">&nbsp;<a href=\"$actionPasswd\">PASSWD</a></td>";
      $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/mail_find.png\">&nbsp;<a href=\"$actionPermisos\">PERMISOS</a></td>";
			 $this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/delete2.gif\">&nbsp;<a href=\"$actionBorrar\">BORRAR</a></td>";
     // $this->salida .= "			<td align=\"center\"><a href=\"$actionDepartamentos\">DEPARTAMENTOS</a></td>";
			$this->salida .= "     </tr>";
      $y++;
		}
    $this->salida .= "       </table>";
		$this->salida .= "        </td></tr>";
    $this->salida .= "        </table>";
		$this->salida .=$this->RetornarBarra();
		$action3=ModuloGetURL('system','Usuarios','admin','main',array("uid"=>$uid));
    $this->salida .= "       <table align=\"center\">";
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
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

	 function RetornarBarra(){
		if($this->limit>=$this->conteo){
				return '';
		}
	  $paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		$accion=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array('conteo'=>$this->conteo));
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table width='30%' border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos){
			for($i=($barra);$i<($barra+10);$i++){
				if($paso==$i){
						$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}else{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
			$colspan+=2;
		}else{
      $diferencia=$numpasos-9;
			if($diferencia<0){$diferencia=1;}
			for($i=($diferencia);$i<=$numpasos;$i++){
				if($paso==$i){
					$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}else{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos){
  			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' ><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a></td>";
				$colspan++;
			}else{
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
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
			$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
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
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página&nbsp; $paso de $numpasos</td><tr></table>";
		}
    }
	}

/**
* Funcion visualiza La forma que piden datos de los nuevos permisos para adicionar a un usuario
* @return boolean
*/

	function FormaAsignarPermisosUsuarios($uid,$NombreUsuario,$Usuario){

    $activo=$this->BuscaEstadoAfiliado($uid);
		if($activo=='1'){
      $tipoBloqueo='DESACTIVAR';
			$img='man-gr.gif';
	  }else{
			$tipoBloqueo='ACTIVAR';
			$img='man-red.gif';
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
		$actionInsertar=ModuloGetURL('system','Usuarios','admin','InsertarAsignacionPermisosUsuarios');
		$action=ModuloGetURL('system','Usuarios','admin','ModificarEstadoUsuario',array("uid"=>$uid,"TipoForma"=>$Destino,"NombreUsuario"=>$NombreUsuario,"Usuario"=>$Usuario));
    $actionBorrarPermisos=ModuloGetURL('system','Usuarios','admin','BorrarTodosPermisosUsuarios',array("uid"=>$uid,"NombreUsuario"=>$NombreUsuario,"Usuario"=>$Usuario));
		$this->salida  = ThemeAbrirTabla('ASIGNACION PERMISOS USUARIO&nbsp;:&nbsp;'.$Usuario.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Estado del usuario: '.$estado.'');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$actionInsertar\" method=\"post\">";
    $this->salida .= "			      <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">PERMISOS USUARIO</legend>";
		$this->salida .= "              <table class=\"modulo_table\" cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"99%\" align=\"center\">";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" width=\"20%\">LOGIN<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">$Usuario</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"20%\">NOMBRE<noBR>&nbsp&nbsp;USUARIO: </td><td class=\"modulo_list_oscuro\" align=\"left\">$NombreUsuario</td></tr>";
    $this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">NUMERO DE LOGUEOS: </td><td class=\"modulo_list_claro\" align=\"left\">$da</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"40%\" align=\"left\">ULTIMO LOGUEO: </td><td class=\"modulo_list_oscuro\" align=\"left\">".$registro."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" class=\"label\" width=\"25%\" align=\"left\">ESTADO DEL USUARIO: </td><td class=\"modulo_list_claro\" align=\"left\">$estado&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img1\"></td></tr>";
		$this->salida .= "              <tr><td class=\"modulo_table_list_title\" class=\"label\" colspan=\"2\"><a href=\"$action\">$tipoBloqueo</a>&nbsp&nbsp;<img src=\"".GetThemePath()."/images/$img\"></td></tr>";
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";
		$this->salida .= "            </table><BR><BR>";

    ////////////*************************************

		if($dats)
		{
  				$this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"70%\">";
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
							$this->salida.="  <td><img src=\"".GetThemePath()."/images/$imagen\"></td>";
							$this->salida.="</tr>";
					}
//           $this->salida.="<tr>";
// 					$this->salida.="<td colspan=\"3\"  class=\"modulo_table_list_title\" align=\"center\"></td>";
// 					$this->salida.="</tr>";
					//$this->salida.="<tr>";
					//$this->salida.="<td colspan=\"3\"  class=\"modulo_table_list_title\" align=\"center\"><a href=\"jaja.php\" >VER MAS LOGS</a></td>";
				//	$this->salida.="</tr>";
					$this->salida.="</table>";

		}
		else
		{
		    $this->salida.="<table  align=\"center\" border=\"1\" class=\"hc_table_list\" width=\"70%\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="</tr>";
				$this->salida.="<tr>";
				$this->salida.="<td align=\"center\" class=\"label_error\"><img src=\"".GetThemePath()."/images/info.png\">&nbsp;&nbsp;Este usuario no tiene registros de logueo</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
		/*		$this->salida.="<tr>";
				$this->salida.="  <td align=\"center\">";
				$this->salida .='<form name="forma" action="'.ModuloGetURL('app','CajaGeneral','user','BuscarDetalleC',array('Cajaid'=>$Cajaid,'arx'=>$valores)).'" method="post">';
				$this->salida .="<input type=\"submit\" align=\"center\" name=\"Buscar\" value=\"Adicionar Conceptos\" class=\"input-submit\"></form></td>";
				$this->salida.="</td>";
				$this->salida.="</tr>";*/
		}

		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion donde se visializa la forma que pide datos para insertar un usuario
* @return boolean
*/

	function FormaInsertarUsuarioSistema($nombreUsuario,$loginUsuario,$password,$passwordReal,$tema,$action,$consulta,$descripcion,$spia,$uid){
		$this->salida  = ThemeAbrirTabla('INSERTAR USUARIO');
		$this->salida .= "			      <br><br>";
		$this->salida .= "           <form name=\"formaUsuarios\" action=\"$action\" method=\"post\">";
    $this->salida .= "			      <table width=\"60%\" border=\"0\" align=\"center\" class=\"modulo_table\">";
    $this->salida .= "            <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "            </td></tr>";
		$this->salida .= "            <tr><td>";
		$this->salida .= "              <fieldset><legend class=\"field\">DATOS USUARIO SISTEMA</legend>";
		$this->salida .= "              <table cellspacing=\"2\"  cellpadding=\"3\"border=\"1\" width=\"80%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("nombreUsuario")."\">NOMBRE USUARIO: </td><td><input type=\"text\" class=\"input-text\" name=\"nombreUsuario\" maxlength=\"60\" value=\"$nombreUsuario\"></td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("tema")."\">TEMA: </td><td><select name=\"tema\" class=\"select\">";
    $this->salida.= "               <input type=\"hidden\" name=\"action\" value=\"$action\">";
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
		$this->salida .= "              <tr class=\"modulo_list_claro\"><td class=\"label\" align=\"center\">ACTIVO   <input type=\"checkbox\" name=\"activo\" checked></td>";
    $this->salida .= "              <td align=\"center\" class=\"label\">USU ADMINISTRADOR  <input type=\"checkbox\" name=\"administrador\"></td></tr>";
    $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("descripcion")."\">DESCRIPCION: </td><td><textarea class=\"textarea\" name=\"descripcion\" cols=\"20\" rows=\"2\">$descripcion</textarea></td></tr>";
		$this->salida .= "				       <tr  class=\"modulo_list_claro\"><td align=\"center\" class=\"".$this->SetStyle("loginUsuario")."\">LOGIN: </td><td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"loginUsuario\" maxlength=\"25\" value=\"$loginUsuario\"></td></tr>";
    if(!$consulta){
		 // $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("password")."\">PASSWORD: </td><td><input type=\"password\" class=\"input-text\" name=\"password\" maxlength=\"40\" value=\"$password\"></td></tr>";
     // $this->salida .= "				       <tr  class=\"modulo_table_list_title\"><td class=\"".$this->SetStyle("passwordReal")."\">CONFIRMACION PASSWORD: </td><td><input type=\"password\" class=\"input-text\" name=\"passwordReal\" maxlength=\"40\" value=\"$passwordReal\"></td></tr>";
    }
		$this->salida .= "			         </table>";
		$this->salida .= "		           </fieldset></td></tr>";

		$this->salida .= "  <table width=\"40%\" align=\"center\">";
		$this->salida .= "              <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Aceptar\" type=\"submit\" value=\"Insertar\"><br></td>";
		$this->salida .= "			      </form>";
    $action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Cancelar\"></td></tr>";
    $this->salida .= "			      </form>";
		$this->salida .= "            </table>";
    $this->salida .= "            </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


		function Menu()
 {

			$this->salida.= ThemeAbrirTabla('MENU DE ADMINISTRACION SIIS V1.0 ALFA');
     	$this->salida.="<br><table border=\"1\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
			$this->salida.="<tr>";
	    $this->salida .= "<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE ADMINISTRACION</td>";
			$this->salida.="</tr>";
			$acc=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema');
			$ac=ModuloGetURL('system','Usuarios','admin','ListadoGeneralSistema');
			$ax=ModuloGetURL('system','Usuarios','admin','Usuario');

//       $this->salida.="<tr>";
// 			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$ax\">CREAR NUEVO USUARIO</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/nuevo_usuario.gif\">";
// 			$this->salida.="</td>";
// 			$this->salida.="</tr>";
			$this->salida.="<tr>";
			$this->salida .= "<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$acc\">ADICIONAR PERMISOS DE MENU</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/usuario.gif\">";
			$this->salida.="</td>";
			$this->salida.="</tr>";

// 			$this->salida.="<tr>";
// 			$this->salida .= "<td   colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ac\">PROPIEDADES DE EQUIPOS</a>&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/pc.png\">";
// 			$this->salida.="</td>";
// 			$this->salida.="</tr>";
			$this->salida.="</table>";
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
    $this->salida .= "			      <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table\">";
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

    $this->salida .= "			      <table width=\"40%\"  border=\"0\" align=\"center\" class=\"modulo_table\">";
		$this->salida .= "              <tr><td align=\"center\"><br><input class=\"input-submit\" name=\"aceptar\" type=\"submit\" value=\"Cambiar\"></td>";
		$this->salida .= " <td  align=\"center\"><br><input class=\"input-submit\" name=\"resetear\" type=\"submit\" value=\"Resetear\"></td>";
		$this->salida .= "			      </form>";

		$action3=ModuloGetURL('system','Usuarios','admin','ListadoUsuariosSistema',array("uid"=>$uid,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Cancelar\"></td></tr>";
		$this->salida .= "            </table>";
		$this->salida .= "            </table><BR>";
		$this->salida .= "			      </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


}//fin clase user
?>

