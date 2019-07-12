<?php

/**
 * $Id: app_CreacionAgenda_adminclasses_HTML.php,v 1.9 2006/05/19 17:39:37 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */
IncludeClass("ClaseHTML");
class app_CreacionAgenda_adminclasses_HTML extends app_CreacionAgenda_admin
{

	function app_CreacionAgenda_admin_HTML()
	{
		$this->app_CreacionAgenda_admin(); //Constructor del padre 'modulo'
		$this->salida='';
		return true;
	}

	/**
	* Function que muestra al usuario la empresas
	
	* @return boolean
	*/
	function FrmLogueoEmpresa(){

    $Empresas=$this->LogueoCirugias();
		if(sizeof($Empresas)>0){
			$url[0]='app';
			$url[1]='CreacionAgenda';
			$url[2]='admin';
			$url[3]='LlamaListadoUsuarios';
			$url[4]='datos_query';
			$this->salida .= gui_theme_menu_acceso("SELECCION DE LA EMPRESA",$Empresas[0],$Empresas[1],$url);
		}else{
      $mensaje = "NO SE ENCONTRARON EMPRESAS CREADAS EN EL SISTEMA.";
			$titulo = "CONSULTA EXTERNA";
			$boton = "";//REGRESAR
			$accion="";
			$this->FormaMensaje($mensaje,$titulo,$accion,$boton);
			return true;
		}
		return true;
	}


	function SetStyle($campo)
	{
				if ($this->frmError[$campo] || $campo=="MensajeError"){
					if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					else
					{
						return ("label_error");
					}
				}
			return ("label");
	}
	
	function ListadoUsuarios(){
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset'])
		{
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
			$accion=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuarios',array("criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"buscar"=>$_REQUEST['buscar'],"ordenamiento"=>$_REQUEST['ordenamiento']));
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
			$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"40\" maxlength=\"40\"  value =\"".$_REQUEST['busqueda']."\"></td>" ;

			$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</form>";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			if($_REQUEST['busqueda']){
				$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
			}else{
				$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
			}
			$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";

			if($_REQUEST['buscar']){
				unset($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']);
				$filtro=$this->GetFiltroUsuarios($_REQUEST['criterio'],$_REQUEST['busqueda']);
			}else{
				if($_SESSION['CREACION_AGENDA_PERMISOS']['FILTRO']){
					$filtro=$_SESSION['CREACION_AGENDA_PERMISOS']['FILTRO'];
				}				
			}

			$img='UID';
			$color='';
			$imgN='NOMBRE USUARIO';
			$imgL='LOGIN';

			//ordenamiento por numero de usuario
			if($_REQUEST['ordenamiento']=='si'){
				$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']='order by usuario_id asc';
			}
			if($_REQUEST['ordenamiento']=='no'){
				$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']='order by usuario_id desc';
			}

			//ordenamiento por nombre
			if($_REQUEST['ordenamiento']=='nomsi'){
				$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']='order by nombre asc';
			}
			if($_REQUEST['ordenamiento']=='nomno'){
				$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']='order by nombre desc';
			}

			//ordenamiento por login
			if($_REQUEST['ordenamiento']=='losi'){
				$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']='order by usuario asc';
			}
			if($_REQUEST['ordenamiento']=='lono'){
				$_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']='order by usuario desc';
			}

			/*PARTE DE CLAUDIA*/

			if($filtro){$_SESSION['CREACION_AGENDA_PERMISOS']['FILTRO']=$filtro;}//esto guarda el filtro...
			$this->salida .= "			      <br><br>";
			$accion=ModuloGetURL('app','CreacionAgenda','admin','main');
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
			if($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']=='order by usuario_id asc'){
				$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
				$color='class=modulo_list_claro';
				$acc=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'no'));
			}elseif($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']=='order by usuario_id desc'){
				$img="<img src=\"". GetThemePath() ."/images/uf.png\" border='0' width='20' height='18'>";
				$color='class=modulo_list_claro';
				$acc=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
			}else{
				$acc=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'si'));
			}

			//ordenamiento por nombre
			if($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']=='order by nombre asc'){
				$imgN="NOMBRE USUARIO<BR>[ascendente]";
				$accN=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomno'));
			}elseif($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']=='order by nombre desc'){
				$imgN="NOMBRE USUARIO<BR>[descendente]";
				$accN=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
			}else{
				$accN=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'nomsi'));
			}

			//ordenamiento por login de usuario
			if($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']=='order by usuario asc'){
				$imgL="LOGIN<BR>[ascendente]";
				$accL=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'lono'));
			}elseif($_SESSION['CREACION_AGENDA_PERMISOS']['ORDENAMIENTO']=='order by usuario desc'){
				$imgL="LOGIN<BR>[descendente]";
				$accL=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
			}else{
				$accL=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuariosSistema',array('ordenamiento'=>'losi'));
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
				$actionPermisos=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));				
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
				$this->salida .= "			<td align=\"center\"><img src=\"".GetThemePath()."/images/pass.png\">&nbsp;<a href=\"$actionPermisos\">PERMISOS</a></td>";				
				$this->salida .= "     </tr>";
				$y++;
			}
			$this->salida .= "       </table>";
			$this->salida .= "        </td></tr>";
			$this->salida .= "        </table>";
			$Paginador = new ClaseHTML();
			$this->actionPaginador=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuarios',array("criterio"=>$_REQUEST['criterio'],"busqueda"=>$_REQUEST['busqueda'],"buscar"=>$_REQUEST['buscar'],"ordenamiento"=>$_REQUEST['ordenamiento']));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
			$this->salida .= "           </form>";
			$action3=ModuloGetURL('app','CreacionAgenda','admin','main',array("uid"=>$uid));
			$this->salida .= "       <table align=\"center\">";
			$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";			
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"Menu\"></td></tr>";
			$this->salida .= "       </table>";
			$this->salida .= "           </form>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}
	
	function AsignarPermisosUsuarios($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNAR PERMISOS');
		//$action=ModuloGetURL('system','Usuarios','admin','InsertarProfesional',array("uid"=>$uid,"nombre"=>$nombre,"usuario"=>$usuario,"empresa"=>$empresa,"descripcion"=>$descripcion,"modificacion"=>$modificacion));
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
		$this->salida .= " 		<td>$nombreE</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";
		$this->salida .= " <fieldset><legend class=\"field\">PERMISOS PARA ASIGNAR AL USUARIO</legend>";
		$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$actionCreacion=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisoCreacionAgenda',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));							
		$this->salida .= "				       <td align=\"center\" class=\"label\"><a href=\"$actionCreacion\">AGENDA Y CITAS MÉDICAS</a></td>";							
		$this->salida .= "				       </tr>";				
		/*$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$this->salida .= "				       <td align=\"center\" class=\"label\">ATENCIÓN DE ORDENES DE SERVICIO</td>";		
		$this->salida .= "				       </tr>";*/
		$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$actionPago=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisoPagoCaja',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "				       <td align=\"center\" class=\"label\"><a href=\"$actionPago\">PAGO DE CITAS MÉDICAS</a></td>";		
		$this->salida .= "				       </tr>";
		$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$actionCierre=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisoCierreCaja',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "				       <td align=\"center\" class=\"label\"><a href=\"$actionCierre\">CIERRE DE CAJA</a></td>";		
		$this->salida .= "				       </tr>";
		$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$actionPuntos=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisoPuntosAdmision',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "				       <td align=\"center\" class=\"label\"><a href=\"$actionPuntos\">ADMISIONES</a></td>";		
		$this->salida .= "				       </tr>";
		$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$actionEE=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisoEstacionEnfermeria',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "				       <td align=\"center\" class=\"label\"><a href=\"$actionEE\">ESTACIONES DE ENFERMERIA</a></td>";		
		$this->salida .= "				       </tr>";
		$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$actionDpto=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisosEnDepartamento',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "				       <td align=\"center\" class=\"label\"><a href=\"$actionDpto\">PERMISOS EN EL DEPARTAMENTO</a></td>";		
		$this->salida .= "				       </tr>";
    
    $this->salida .= "               <tr class=\"modulo_list_claro\">";    
    $estado=$this->ConsultaEstadoRegFallasSistema($uid);
    if($estado=='1'){
      $img="<img src=\"". GetThemePath() ."/images/inactivo.gif\" title=\"Inactivo\" border='0' width='15' height='13'>";
    }elseif($estado=='0'){
      $img="<img src=\"". GetThemePath() ."/images/activo.gif\" title=\"Activo\" border='0' width='15' height='13'>";      
    }else{
      $img="<img src=\"". GetThemePath() ."/images/inactivo.gif\" border='0' width='15' height='13'>";
    }
    $actionFalla=ModuloGetURL('app','CreacionAgenda','admin','PermisosUsuariosRegFallas',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE,"estado"=>$estado));          
    $this->salida .= "               <td align=\"center\" class=\"label\"><a href=\"$actionFalla\">$img&nbsp;PERMISOS CONSULTA FALLAS SISTEMA</a></td>";    
    $this->salida .= "               </tr>";    
    $this->salida .= "               <tr class=\"modulo_list_claro\">";
    $actionPuntosFac=ModuloGetURL('app','CreacionAgenda','admin','LlamaPermisoPuntosFacturacionRips',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));         
    $this->salida .= "               <td align=\"center\" class=\"label\"><a href=\"$actionPuntosFac\">PERMISOS PUNTOS FACTURACION (RIPS)</a></td>";   
    $this->salida .= "               </tr>";
    
		/*$this->salida .= "				       <tr class=\"modulo_list_claro\">";
		$this->salida .= "				       <td align=\"center\" class=\"label\">ADMISIONES</td>";		
		$this->salida .= "				       </tr>";*/
		$this->salida .= "			         </table>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";		
		$this->salida .= "	</form>";		
    $action3=ModuloGetURL('app','CreacionAgenda','admin','ListadoUsuarios');					
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function PermisosPagoCaja($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNAR PERMISOS DE PAGO EN CAJA');
		$action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosPagoCaja',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"80%\" border=\"0\" align=\"center\">";
		
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
		$this->salida .= " 		<td>$nombreE</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";		
		$this->salida .= " <fieldset><legend class=\"field\">CAJAS RAPIDAS CREADAS EN EL SISTEMA</legend>";		
		$Seleccion=$_REQUEST['Seleccion'];		
		$cajas=$this->CajasRapidasdelSistema();
		if($cajas){
			$cont=0;
			$h=0;
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			for($i=0;$i<sizeof($cajas);$i++){
				if($h % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$che='';
				if(in_array($cajas[$i]['caja_id'],$Seleccion)){
					$che='checked';
				}
				if($cont % 2 > 0){					
					$this->salida .= "				       <td width=\"40%\">".$cajas[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$cajas[$i]['caja_id']."\" $che></td>";
					$this->salida .= "				       </tr>";
					$h++;
				}else{
					$this->salida .= "				       <tr class=\"$estilo\">";
					$this->salida .= "				       <td width=\"40%\">".$cajas[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$cajas[$i]['caja_id']."\" $che></td>";		
				}	
				$cont++;				
			}
			if($cont % 2 > 0){
				$this->salida .= "				       <td colspan=\"2\" width=\"40%\">&nbsp;</td></tr>";
			}
			$this->salida .= "			         </table>";
		}else{
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY CAJAS CREADAS EN EL SISTEMA</td></tr>";
			$this->salida .= "			         </table>";
		}
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";		
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Asignar Permisos\"></td></tr>";
		$this->salida .= "	</form>";		
    $action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}	
	
	function PermisosEnDepartamento($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNACION DE PERMISOS EN EL DEPARTAMENTO');		
		$action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosEnDepartamento',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));
		$this->salida .= "           <form name=\"forma\" action=\"$action\" method=\"post\">";
		$RUTA = "app_modules/Reportes_Consulta_Externa/buscador1.php?sign=";
		$mostrar ="\n<script language='javascript'>\n";
		$mostrar.="var rem=\"\";\n";
		$mostrar.="  function xxx(a){\n";
		$mostrar.="    var nombre=\"\"\n";
		$mostrar.="    var url2=\"\"\n";
		$mostrar.="    var str=\"\"\n";
		$mostrar.="    var nombre=\"REPORTE\";\n";
		$mostrar.="    var str =\"width=450,height=180,resizable=no,location=no, status=no,scrollbars=yes\";\n";
		$mostrar.="    var url2 ='$RUTA';\n";
		$mostrar.="    url2 +=a;\n";
		$mostrar.="    rem = window.open(url2, nombre, str)};\n";		
		
		$mostrar.=    "function chequeoTotal(frm,x){";
    $mostrar.="    if(x==true){";
    $mostrar.="    for(i=0;i<frm.elements.length;i++){";   
    $mostrar.="    if(frm.elements[i].type=='checkbox'){";
    $mostrar.="    frm.elements[i].checked=true";
		$mostrar.="    }";
		$mostrar.="    }";
    $mostrar.="    }else{";
    $mostrar.="    for(i=0;i<frm.elements.length;i++){";		
    $mostrar.="    if(frm.elements[i].type=='checkbox'){";
    $mostrar.="    frm.elements[i].checked=false";
		$mostrar.="    }";
		$mostrar.="    }";
		$mostrar.="    }";
    $mostrar.="    }";		
		$mostrar.="</script>\n";
    $this->salida .= $mostrar;
		$_SESSION['recoex']['empresa']=$empID;
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
    $this->salida .= "   <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= "   </td></tr>";
		$this->salida .= "            </table><BR>";
		
		$this->salida .= " 		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= " 		<tr class=\"modulo_table_list_title\">";
		$this->salida .= " 		<td width=\"20%\" nowrap>CENTRO UTILIDAD</td>";
		$this->salida .= " 		<td width=\"20%\" nowrap>UNIDAD FUNCIONAL</td>";
		$this->salida .= " 		<td width=\"30%\" nowrap>DEPARTAMENTO</td>";
		$this->salida .= " 		<td width=\"10%\" nowrap>CONSULTA<br>REPORTES</td>";		                                               
		$this->salida .= " 		<td width=\"10%\" nowrap>CENTRAL DE<BR>IMPRESION</td>";
		$this->salida .= " 		<td width=\"10%\" nowrap>ORDENES DE<BR>SERVICIO</td>";
		//<input type=\"checkbox\" name=\"SeleccionTodos\" onclick=\"chequeoTotal(this.form,this.checked)\" value=\"1\">
		$this->salida .= " 		</tr>";
		$Seleccion=$_REQUEST['Seleccion'];							
		$SeleccionUno=$_REQUEST['SeleccionUno'];		
		$SeleccionDos=$_REQUEST['SeleccionDos'];		
		$centros=$this->CentrosUtilidad($_SESSION['CREACION_AGENDA_PERMISOS']['empresa']);  
		if($centros){
			for($i=0;$i<sizeof($centros);$i++){
				$this->salida .= " 		<tr class=\"modulo_list_claro\">";
				$this->salida .= " 		<td>".$centros[$i]['centro']."</td>";
				$this->salida .= " 		<td colspan=\"5\">";				
				$unidades=$this->Unidades_Funcionales($_SESSION['CREACION_AGENDA_PERMISOS']['empresa'],$centros[$i]['centro_utilidad']);  	
				if($unidades){
					$this->salida .= " 		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
					for($j=0;$j<sizeof($unidades);$j++){
						$this->salida .= " 		<tr class=\"modulo_list_oscuro\">";
						$this->salida .= " 		<td width=\"24%\" nowrap>".$unidades[$j]['unidad']."</td>";
						$this->salida .= " 		<td>";				
						$departamentos=$this->Departamentos($_SESSION['CREACION_AGENDA_PERMISOS']['empresa'],$centros[$i]['centro_utilidad'],$unidades[$j]['unidad_funcional']);  	
						if($departamentos){	
							$this->salida .= " 		<table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
							for($m=0;$m<sizeof($departamentos);$m++){
								$che='';$che1='';$che2='';																
								if(in_array($centros[$i]['centro_utilidad'].','.$unidades[$j]['unidad_funcional'].','.$departamentos[$m]['departamento'],$Seleccion)){
									$che='checked';
								}
								if(in_array($centros[$i]['centro_utilidad'].','.$unidades[$j]['unidad_funcional'].','.$departamentos[$m]['departamento'],$SeleccionUno)){
									$che1='checked';
								}
								if(in_array($centros[$i]['centro_utilidad'].','.$unidades[$j]['unidad_funcional'].','.$departamentos[$m]['departamento'],$SeleccionDos)){
									$che2='checked';
								}
								$this->salida .= " 		<tr class=\"modulo_list_claro\">";
								$this->salida .= " 		<td width=\"51%\" nowrap>".$departamentos[$m]['nombre_dpto']."</td>";
								$this->salida .= " 		<td width=\"17%\" nowrap align=\"center\"><input type=\"checkbox\" $che name=\"Seleccion[]\" value=\"".$centros[$i]['centro_utilidad'].",".$unidades[$j]['unidad_funcional'].",".$departamentos[$m]['departamento']."\"></td>";
								$this->salida .= " 		<td width=\"20%\" nowrap align=\"center\"><input type=\"checkbox\" $che1 name=\"SeleccionUno[]\" value=\"".$centros[$i]['centro_utilidad'].",".$unidades[$j]['unidad_funcional'].",".$departamentos[$m]['departamento']."\"></td>";
								$this->salida .= " 		<td align=\"center\"><input type=\"checkbox\" $che2 name=\"SeleccionDos[]\" value=\"".$centros[$i]['centro_utilidad'].",".$unidades[$j]['unidad_funcional'].",".$departamentos[$m]['departamento']."\"></td>";
								$this->salida .= " 		</tr>";
							}
							$this->salida .= " 		</table>";
						}
					}
					$this->salida .= " 		</td>";
					$this->salida .= " 		</tr>";	
					$this->salida .= " 		</table>";
				}								
			}
			$this->salida .= " 		</td>";
			$this->salida .= " 		</tr>";				
		}
		$che='';
		if($_REQUEST['todosProfesionalesRepCE']){
			$che='checked';
		}
		$this->salida .= "    			  <br><tr class=\"modulo_list_claro\"><td class=\"label\">VER REPORTES CE DE TODOS<br>LOS PROFESIONALES</td><td colspan=\"5\"><br><input name=\"todosProfesionalesRepCE\" type=\"checkbox\" value=\"1\" $che></td></tr>";		  
		$this->salida .= "    			  <tr><td colspan=\"6\"  align=\"right\"><br><input class=\"input-submit\" name=\"Asignar\" type=\"submit\" value=\"ASIGNAR PERMISOS\"></td></tr>";		 
		$this->salida .= "			      </form>";
		$action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "           <form name=\"forma\" action=\"$action3\" method=\"post\">";
		$this->salida .= "    			  <tr><td colspan=\"6\"  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"VOLVER\"></td></tr>";		
		$this->salida .= "    </table><BR>";
		$this->salida .= "			      </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function PermisosCreacionAgenda($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNACIÓN DE PERMISOS PARA LA CREACIÓN DE AGENDAS Y CITAS MÉDICAS');
		$action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosCreaciónAgenda',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"80%\" border=\"0\" align=\"center\">";
		
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
		$this->salida .= " 		<td>$nombreE</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";		
		$this->salida .= " <fieldset><legend class=\"field\">TIPOS DE CONSULTAS Y PERMISOS</legend>";		
		$Seleccion=$_REQUEST['Seleccion'];		
		$Seleccion1=$_REQUEST['Seleccion1'];		
		$Seleccion2=$_REQUEST['Seleccion2'];		
		$tiposCon=$this->TiposConsultasdelSistema();
		if($tiposCon){
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= " 								<tr class=\"modulo_table_list_title\">";
			$this->salida .= " 								<td>TIPO CONSULTA</td>";
			$this->salida .= " 								<td>CREACIÓN DE AGENDA</td>";
			$this->salida .= " 								<td>ASIGNACIÓN Y CANCELACIÓN DE CITAS</td>";
			$this->salida .= " 								<td>CUMPLIMIENTO DE CITAS</td>";
			$this->salida .= " 								</tr>";
			for($i=0;$i<sizeof($tiposCon);$i++){
				$this->salida .= "				       <tr class=\"modulo_list_claro\">";
				$this->salida .= "				       <td>".$tiposCon[$i]['descripcion']."</td>";
				//CREACION DE AGENDA
				$che='';
				if(in_array($tiposCon[$i]['tipo_consulta_id'],$Seleccion)){
					$che='checked';
				}
				$this->salida .= "				       <td align=\"center\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$tiposCon[$i]['tipo_consulta_id']."\" $che></td>";
				//AGIGNACION DE CITAS
				$che='';
				if(in_array($tiposCon[$i]['tipo_consulta_id'],$Seleccion1)){
					$che='checked';
				}
				$this->salida .= "				       <td align=\"center\"><input type=\"checkbox\" name=\"Seleccion1[]\" value=\"".$tiposCon[$i]['tipo_consulta_id']."\" $che></td>";
				//CUMPLIMIENTO DE CITAS
				$che='';
				if(in_array($tiposCon[$i]['tipo_consulta_id'],$Seleccion2)){
					$che='checked';
				}
				$this->salida .= "				       <td align=\"center\"><input type=\"checkbox\" name=\"Seleccion2[]\" value=\"".$tiposCon[$i]['tipo_consulta_id']."\" $che></td>";
				$this->salida .= "				       </tr>";
			}
			$this->salida .= "			         </table>";
		}else{
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY CAJAS CREADAS EN EL SISTEMA</td></tr>";
			$this->salida .= "			         </table>";
		}
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";		
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Asignar Permisos\"></td></tr>";
		$this->salida .= "	</form>";		
    $action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}	
	
	function PermisosCierreCaja($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNAR PERMISOS DE CIERRE DE CAJA');
		$action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosCierreCaja',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"80%\" border=\"0\" align=\"center\">";
		
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
		$this->salida .= " 		<td>$nombreE</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";		
		$this->salida .= " <fieldset><legend class=\"field\">CAJAS CREADAS EN EL SISTEMA</legend>";		
		$Seleccion=$_REQUEST['Seleccion'];		
		$cajas=$this->CajasdelSistema();
		if($cajas){
			$cont=0;
			$h=0;
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			for($i=0;$i<sizeof($cajas);$i++){				
				if($h % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$che='';
				if(in_array($cajas[$i]['caja_id'],$Seleccion)){
					$che='checked';
				}
				if($cont % 2 > 0){
					$this->salida .= "				       <td width=\"40%\">".$cajas[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$cajas[$i]['caja_id']."\" $che></td>";				
					$this->salida .= "				       </tr>";
					$h++;
				}else{
					$this->salida .= "				       <tr class=\"$estilo\">";
					$this->salida .= "				       <td width=\"40%\">".$cajas[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$cajas[$i]['caja_id']."\" $che></td>";									
				}
				$cont++;				
			}
			if($cont % 2 > 0){
				$this->salida .= "				       <td colspan=\"2\" width=\"40%\">&nbsp;</td></tr>";
			}
			$this->salida .= "			         </table>";
		}else{
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY CAJAS CREADAS EN EL SISTEMA</td></tr>";
			$this->salida .= "			         </table>";
		}
		$this->salida .= "  </fieldset></td></tr>";
		
		$this->salida .= " <tr><td>";		
		$this->salida .= " <fieldset><legend class=\"field\">DEPARTMAENTOS RELACIONADOS CON EL USUARIO</legend>";		
		$SeleccionDptos=$_REQUEST['SeleccionDptos'];		
		$dptos=$this->DepartamentosdelSistema();
		if($dptos){
			$cont=0;			
			$h=0;
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			for($i=0;$i<sizeof($dptos);$i++){
				if($h % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$che='';
				if(in_array($dptos[$i]['departamento'],$SeleccionDptos)){
					$che='checked';
				}
				if($cont % 2 > 0){
					$this->salida .= "				       <td width=\"40%\">".$dptos[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"SeleccionDptos[]\" value=\"".$dptos[$i]['departamento']."\" $che></td>";				
					$this->salida .= "				       </tr>";
					$h++;
				}else{
					$this->salida .= "				       <tr class=\"$estilo\">";
					$this->salida .= "				       <td width=\"40%\">".$dptos[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"SeleccionDptos[]\" value=\"".$dptos[$i]['departamento']."\" $che></td>";									
				}
				$cont++;	
			}
			if($cont % 2 > 0){
				$this->salida .= "				       <td colspan=\"2\" width=\"40%\">&nbsp;</td></tr>";
			}
			$this->salida .= "			         </table>";
		}else{
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY CAJAS CREADAS EN EL SISTEMA</td></tr>";
			$this->salida .= "			         </table>";
		}
		$this->salida .= "  </fieldset></td></tr>";
		
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";		
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Asignar Permisos\"></td></tr>";
		$this->salida .= "	</form>";		
    $action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}
	
	function PermisoPuntosAdmision($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNAR PERMISOS EN PUNTOS DE ADMISION');
		$action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosPuntosAdmision',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"80%\" border=\"0\" align=\"center\">";
		
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
		$this->salida .= " 		<td>$nombreE</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";		
		$this->salida .= " <fieldset><legend class=\"field\">PUNTOS DE ADMISION CREADOS EN EL SISTEMA</legend>";		
		$Seleccion=$_REQUEST['Seleccion'];		
		$puntos=$this->PuntosAdmisionSistema();
		if($puntos){
			$cont=0;
			$h=0;
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			for($i=0;$i<sizeof($puntos);$i++){				
				if($h % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$che='';
				if(in_array($puntos[$i]['punto_admision_id'],$Seleccion)){
					$che='checked';
				}
				if($cont % 2 > 0){
					$this->salida .= "				       <td width=\"40%\">".$puntos[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$puntos[$i]['punto_admision_id']."\" $che></td>";				
					$this->salida .= "				       </tr>";
					$h++;
				}else{
					$this->salida .= "				       <tr class=\"$estilo\">";
					$this->salida .= "				       <td width=\"40%\">".$puntos[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$puntos[$i]['punto_admision_id']."\" $che></td>";									
				}
				$cont++;				
			}
			if($cont % 2 > 0){
				$this->salida .= "				       <td colspan=\"2\" width=\"40%\">&nbsp;</td></tr>";
			}
			$this->salida .= "			         </table>";
		}else{
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY PUNTOS DE ADMISION CREADOS EN EL SISTEMA</td></tr>";
			$this->salida .= "			         </table>";
		}
		$this->salida .= "  </fieldset></td></tr>";
				
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";		
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Asignar Permisos\"></td></tr>";
		$this->salida .= "	</form>";		
    $action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "	 </form>";
	//	$this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}	
	
	function PermisoEstacionEnfermeria($uid,$nombre,$empresa,$usuario,$nombreE){
		
		$this->salida  = ThemeAbrirTabla('ASIGNAR PERMISOS EN ESTACION DE ENFERMERIA');
		$action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosEstacionesEnfermeria',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
		$this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"80%\" border=\"0\" align=\"center\">";
		
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
		$this->salida .= " 		<td>$nombreE</td>";
		$this->salida .= " 		</tr>";
		$this->salida .= " 		</table>";
		$this->salida .= "  </fieldset>";
		$this->salida .= "  </td></tr>";						
    $this->salida .= " <tr><td>";
		$this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
		$this->salida .= " <tr><td>";		
		$this->salida .= " <fieldset><legend class=\"field\">ESTACIONES DE ENFERMERIA CREADAS EN EL SISTEMA</legend>";		
		$Seleccion=$_REQUEST['Seleccion'];		
		$estaciones=$this->EstacionesEnfermeriaSistema();
		if($estaciones){
			$cont=0;
			$h=0;
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			for($i=0;$i<sizeof($estaciones);$i++){				
				if($h % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$che='';
				if(in_array($estaciones[$i]['estacion_id'],$Seleccion)){
					$che='checked';
				}
				if($cont % 2 > 0){
					$this->salida .= "				       <td width=\"40%\">".$estaciones[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$estaciones[$i]['estacion_id']."\" $che></td>";				
					$this->salida .= "				       </tr>";
					$h++;
				}else{
					$this->salida .= "				       <tr class=\"$estilo\">";
					$this->salida .= "				       <td width=\"40%\">".$estaciones[$i]['descripcion']."</td>";
					$this->salida .= "				       <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$estaciones[$i]['estacion_id']."\" $che></td>";									
				}
				$cont++;				
			}
			if($cont % 2 > 0){
				$this->salida .= "				       <td colspan=\"2\" width=\"40%\">&nbsp;</td></tr>";
			}
			$this->salida .= "			         </table>";
		}else{
			$this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "				       <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY ESTACIONES DE ENFERMERIA CREADAS EN EL SISTEMA</td></tr>";
			$this->salida .= "			         </table>";
		}
		$this->salida .= "  </fieldset></td></tr>";
				
		$this->salida .= "   </table>";
		$this->salida .= "  <table width=\"40%\" align=\"center\">";		
		$this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Asignar Permisos\"></td></tr>";
		$this->salida .= "	</form>";		
    $action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));					
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

  function PermisoPuntosFacturacionRips($uid,$nombre,$empresa,$usuario,$nombreE){
    
    $this->salida  = ThemeAbrirTabla('ASIGNAR PERMISOS EN PUNTOS FACTURACION(RIPS)');
    $action=ModuloGetURL('app','CreacionAgenda','admin','GuardarPermisosPuntosFacturacionRips',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));          
    $this->salida .= " <form name=\"forma\" action=\"$action\" method=\"post\">";
    $this->salida .= " <table width=\"80%\" border=\"0\" align=\"center\">";
    
    $this->salida .= " <tr><td>";   
    $this->salida .= "  <fieldset><legend class=\"field\">DATOS DEL USUARIO</legend>";
    $this->salida .= "    <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td width=\"30%\" class=\"label\">UID</td>";
    $this->salida .= "    <td>$uid</td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td width=\"30%\" class=\"label\">LOGIN</td>";
    $this->salida .= "    <td>$usuario</td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td width=\"30%\" class=\"label\">NOMBRE USUARIO</td>";
    $this->salida .= "    <td>$nombre</td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td width=\"30%\" class=\"label\">EMPRESA</td>";
    $this->salida .= "    <td>$nombreE</td>";
    $this->salida .= "    </tr>";
    $this->salida .= "    </table>";
    $this->salida .= "  </fieldset>";
    $this->salida .= "  </td></tr>";            
    $this->salida .= " <tr><td>";
    $this->salida .= $this->SetStyle("MensajeError");
    $this->salida .= " </td></tr>";
    $this->salida .= " <tr><td>";   
    $this->salida .= " <fieldset><legend class=\"field\">PUNTOS DE ADMISION CREADOS EN EL SISTEMA</legend>";    
    $Seleccion=$_REQUEST['Seleccion'];  
    
    $puntos=$this->PuntosFacturacionRips($empresa);
    if($puntos){
      $cont=0;
      $h=0;
      $this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
      for($i=0;$i<sizeof($puntos);$i++){        
        if($h % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $che='';
        if(in_array($puntos[$i]['punto_facturacion_id'],$Seleccion)){
          $che='checked';
        }
        if($cont % 2 > 0){
          $this->salida .= "               <td width=\"40%\">".$puntos[$i]['descripcion']."</td>";
          $this->salida .= "               <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$puntos[$i]['punto_facturacion_id']."\" $che></td>";       
          $this->salida .= "               </tr>";
          $h++;
        }else{
          $this->salida .= "               <tr class=\"$estilo\">";
          $this->salida .= "               <td width=\"40%\">".$puntos[$i]['descripcion']."</td>";
          $this->salida .= "               <td width=\"5%\"><input type=\"checkbox\" name=\"Seleccion[]\" value=\"".$puntos[$i]['punto_facturacion_id']."\" $che></td>";                 
        }
        $cont++;        
      }
      if($cont % 2 > 0){
        $this->salida .= "               <td colspan=\"2\" width=\"40%\">&nbsp;</td></tr>";
      }
      $this->salida .= "               </table>";
    }else{
      $this->salida .= "               <table cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"100%\" align=\"center\">";
      $this->salida .= "               <tr class=\"modulo_list_claro\"><td class=\"label_error\">NO HAY PUNTOS DE FACTURACION CREADOS EN EL SISTEMA</td></tr>";
      $this->salida .= "               </table>";
    }
    $this->salida .= "  </fieldset></td></tr>";
        
    $this->salida .= "   </table>";
    $this->salida .= "  <table width=\"40%\" align=\"center\">";    
    $this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar\" type=\"submit\" value=\"Asignar Permisos\"></td></tr>";
    $this->salida .= "  </form>";   
    $action3=ModuloGetURL('app','CreacionAgenda','admin','LlamaAsignarPermisosUsuarios',array("uid"=>$uid,"nombre"=>$nombre,"empresa"=>$empresa,"usuario"=>$usuario,"nombreE"=>$nombreE));          
    $this->salida .= "   <form name=\"forma2\" action=\"$action3\" method=\"post\">";
    $this->salida .= "   <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></td></tr>";
    $this->salida .= "   </form>";
  //  $this->salida .= "            </table>";
    $this->salida .= "   </table><BR><BR>";
    $this->salida .= ThemeCerrarTabla();
    return true;
  } 

}

?>
