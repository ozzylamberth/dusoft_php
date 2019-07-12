<?php
/***************
 * $Id: app_Os_Listas_Trabajo_Administracion_userclasses_HTML.php,v 1.0 2006/03/14 21:59:58 luis Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @autor luis alejandor vargas
 * @package IPSOFT-SIIS
 *
 * Modulo para la administracion de listas de trabajo
 ***************/

/****************************************************************************
*Contiene los metodos visuales para realizar la administracion de listas de trabajo
*****************************************************************************/

IncludeClass("ClaseHTML");

class app_Os_Listas_Trabajo_Administracion_userclasses_HTML extends app_Os_Listas_Trabajo_Administracion_user
{
	/***
	* Constructor de la clase
	* @return boolean
	*/
	
	function app_Os_Listas_Trabajo_Administracion_userclasses_HTML()
	{
		$this->app_Os_Listas_Trabajo_Administracion_user();
		$administracion=$_REQUEST['administracion'];
		$this->salida='';
		return true;	
	}
	/***
	* Funcion donde se visualiza el menu.
	* @return boolean
	*/
	
	function Inicio()
	{
		$adminitracion=$_REQUEST['administracion'];
		$_SESSION['empresa_id']=$adminitracion[0];
		$_SESSION['descripcion_emp']=$adminitracion[1];
		$_SESSION['centro_id']=$adminitracion[2];
		$_SESSION['descripcion_centro']=$adminitracion[3];
		$_SESSION['departamento']=$adminitracion[6];
		$_SESSION['descripcion']=$adminitracion[7];
		$this->Principal();
		return true;	
	}
	
	function Principal()
	{
		$action1=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$action2=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaCrearListaTrabajo');
		
		$action3=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		
		$this->salida.= ThemeAbrirTabla('ADMINISTRACION LISTAS DE TRABAJO','50%');
	
		$this->salida.="<br>";
	
		$this->salida.="<table border=\"0\"  class=\"modulo_table_list\"  align=\"center\" width=\"80%\" >";
		
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="<td align=\"center\">MENU</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="<td align=\"center\" class=\"modulo_list_oscuro\" ><a href=\"$action1\">VER LISTAS DE TRABAJO</a></td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="<td  align=\"center\" class=\"modulo_list_oscuro\" ><a href=\"$action2\">CREAR LISTA DE TRABAJO</a></td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="<td  align=\"center\" class=\"modulo_list_oscuro\" ><a href=\"$action3\">PERMISOS DE USUARIO</a></td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="</table>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','BuscarPermisos');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
 	}
	
	/***
	* Funcion donde se muestra un mensaje de ingresos exitosos
	* @return boolean
	*/
	function mensaje($mensaje)
	{
		$this->salida.= ThemeAbrirTabla('');
		
		$this->salida.="<center><label class=\"label\"> $mensaje </center>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','Principal');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	
	/***
	* Funcion donde se encuentran las listas de trabajo de los departamentos que el usuario tenga permiso
	* @return boolean
	*/
	
	function FormaVerListasTrabajos()
	{
		$this->salida.= ThemeAbrirTabla('LISTAS DE TRABAJO');
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$this->salida.="	<form action=\"$action\" name=\"forma\" method=\"POST\">";
		
		$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="  			<td align=\"center\" colspan=\"5\">LISTAS DE TRABAJO</td>";
		$this->salida.="		</tr>";

		$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
		
		$this->salida.="			<td width=\"5%\">TIPO</td>";
		
		$this->salida.="			<td width=\"10%\" align = left >";
		$this->salida.="				<select size = 1 name = \"criterio\"  class =\"select\">";	
		$this->salida.="					<option value = \"1\"># LISTA</option>";
		$this->salida.="					<option value = \"2\" selected>LISTA</option>";
		$this->salida.="				</select>";
		$this->salida.="			</td>";
		
		$this->salida.="			<td width=\"10%\">DESCRIPCIÓN:</td>";
		$this->salida .="			<td width=\"25%\" align=\"center\"><input type=\"text\" class=\"input-text\" 	name = \"busqueda\" size=\"40\" maxlength=\"40\"  value =\"".$_REQUEST['busqueda']."\"></td>" ;
		
		$this->salida .= "			<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= \"buscar\" type=\"submit\" value=\"BUSCAR\"></td>";
		
		$this->salida.="		</tr>";
		
		$this->salida.="	</form>";
		
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		
		if($_REQUEST['busqueda'])
		{
			$cadena="El Buscador de Listas: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
		}
		else
		{
			$cadena="El Buscador de Listas: Busqueda de todos las Lista de trabajo";
		}
		
		$this->salida.="  		<td align=\"left\" colspan=\"5\">$cadena</td>";
		
		$this->salida.="	</tr>";
		
		$this->salida.="</table>";

		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaCrearListaTrabajo');	
		$this->salida.="<br><center><label class=\"label\"><a href=\"$action\">CREAR NUEVA LISTA DE TRABAJO</a></center>";
		
		$this->salida.="<br>";
		
		$this->salida.="<table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"80%\">";
		
		$this->salida.="<tr align=\"center\" class=\"modulo_table_list_title\">";
		
		$this->salida.="<td># LISTA</td>";
		
		$this->salida.="<td>NOMBRE</td>";
		
		$this->salida.="<td>DEPARTAMENTO</td>";
		
		$this->salida.="<td colspan=\"3\" width=\"36%\">PERMISOS</td>";
		
		$this->salida.="</tr>";
			
		$this->salida .= "	<script language=\"javascript\">";
		$this->salida .= "		function mOvr(src,clrOver)";
		$this->salida .= "		{";
		$this->salida .= "			src.style.background = clrOver;";
		$this->salida .= "		}";
		$this->salida .= "		function mOut(src,clrIn)";
		$this->salida .= "		{";
		$this->salida .= "			src.style.background = clrIn;";
		$this->salida .= "		}";
		$this->salida .= "	</script>";
		
		$k=0;
		
		$verlt=$this->VerListasTrabajos($_SESSION['departamento'],$_REQUEST['busqueda'],$_REQUEST['criterio']);	
		
		foreach($verlt as $lista)
		{
			if($k % 2 == 0)
			{
				$estilo='modulo_list_oscuro';
				$background = "#CCCCCC";
			}
			else
			{
				$estilo='modulo_list_claro';
				$background = "#DDDDDD";
			}
			
			$this->salida.="<tr align=\"center\" class=\"$estilo\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
			
			$this->salida.="<td>".$lista[0]."</td>";
			
			$this->salida.="<td align=\"left\">".$lista[1]."</td>";
			
			$this->salida.="<td>".$lista[2]."</td>";

			$action3=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaEditarListaTrabajo',array('lista'=>$lista));
			
			$this->salida.="<td align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/editar.png\"><a href=\"$action3\"> EDITAR</a></label></td>";
			
			$action4=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaEliminarLista',array('lista'=>$lista));
			
			$this->salida.="<td align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/elimina.png\"><a href=\"$action4\"> ELIMINAR</a></label></td>";
			
			$action5=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaUsuariosListaTrabajo',array('lista_id'=>$lista));
			
			$this->salida.="<td align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/usuarios.png\"><a href=\"$action5\"> USUARIOS</a></label></td>";
	
			$this->salida.="</tr>";
				
			$k++;	
		}

		$this->salida.="</table>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','Principal');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center>";
		$this->salida.="</form>";
		
		$this->salida.= ThemeCerrarTabla();
			
		return true;
	}
	
	function FormaEliminarLista()
	{
		
		$lista=$_REQUEST['lista'];
		
		$this->salida.= ThemeAbrirTabla('ELIMINAR LISTA');
		
		$this->salida.="<table align=\"center\" width=\"45%\">";
		$this->salida.="<tr>";
		$this->salida.="	<td align=\"left\" class=\"modulo_table_list_title\"  width=\"25%\">";
		$this->salida.="		# LISTA: ";
		$this->salida.="	</td>";
		$this->salida.="	<td class=\"modulo_list_claro\" align=\"left\">";
		$this->salida.="		$lista[0]";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="	<td align=\"left\" class=\"modulo_table_list_title\">";
		$this->salida.="		LISTA: ";
		$this->salida.="	</td>";
		$this->salida.="	<td class=\"modulo_list_claro\" align=\"left\">";
		$this->salida.="		".$lista[1]."";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="	<td align=\"left\" class=\"modulo_table_list_title\">";
		$this->salida.="		DEPARTAMENTO: ";
		$this->salida.="	</td>";
		$this->salida.="	<td class=\"modulo_list_claro\" align=\"left\">";
		$this->salida.="		".$_SESSION['descripcion']."";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
		$action1=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','EliminarLista',array('lista'=>$lista,'departamento'=>$_SESSION['departamento']));
		
		$action2=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$this->salida.="<table align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="	<td>";
		$this->salida.="	<form action=\"$action1\" name=\"forma\" method=\"POST\">";
		$this->salida.="		<br><center><input type=\"submit\" class=\"input-submit\" name=\"Eliminar\" value=\"ELIMINAR\"></center><br>";
		$this->salida.="	</form>";
		$this->salida.="	</td>";
		$this->salida.="	<td>";
		$this->salida.="	<form action=\"$action2\" name=\"forma\" method=\"POST\">";
		$this->salida.="		<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="	</form>";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}

	/***
	* Funcion donde se crea una lista de trabajo deacuerdo a los departamentos que el usuario tenga permiso
	* @return boolean
	*/	
	function FormaCrearListaTrabajo()
	{
		
		$this->salida.= ThemeAbrirTabla('CREAR LISTA DE TRABAJO');

		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaCrearListaTrabajo');
		
		if(!empty($_REQUEST['grupo_tipo_cargo']))
		{
			
			$nombre=$_REQUEST['nombre'];
		
			$departamento=$_REQUEST['departamento'];
			
			$sw_examen=$_REQUEST['sw_examen'];
			
			$grupo_tipocargo=$_REQUEST['grupo_tipo_cargo'];
			
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','CrearListaTrabajo');
		}
		
		$this->salida.="<br>";
		
		$this->salida .= "	<script language=\"javascript\">";
		$this->salida .= "		function validar(objeto)";
		$this->salida .= "		{
							var bandera=false;
							if(objeto.nombre.value==\"\")
							{	
								alert('Debe ingresar el nombre de la lista');
								bandera=true;
							}
							else if(objeto.departamento.value==\"\")
							{
								alert('Debe seleccionar un departamento');
								bandera=true;
							}	
							else if(objeto.grupo_tipo_cargo.value==\"\")
							{	
								alert('Debe seleccionar un grupo tipo de cargo');
								bandera=true;
							}	
							
							if(bandera==false)
								objeto.submit();
											
						}";
		$this->salida .= "	</script>";
		
		$this->salida.="<form name=\"forma\" method=\"POST\" action=\"$action\">";

		$this->salida.="<table border=\"0\" class=\"modulo_table_list\"  align=\"center\" width=\"60%\" >";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\" width=\"40%\">NOMBRE LISTA</td>";
		
		$this->salida.="<td><input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"$nombre\" size=\"50\" maxlength=\"50\"></td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\">DEPARTAMENTO</td>";
		
		$this->salida.="<td><select name=\"departamento\" class=\"select\">";
		
		$this->salida.="<option value=\"\">--DEPARTAMENTO--</option>";
		
		//if($_SESSION['departamento']==$departamento)
			$this->salida.="<option value=\"".$_SESSION['departamento']."\" selected>".$_SESSION['descripcion']."</option>";
		//else
			//$this->salida.="<option value=\"".$_SESSION['departamento']."\">".$_SESSION['descripcion']."</option>";
			
		$this->salida.="</select></td></tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\">VISUALIZAR EXAMEN SIN FIRMAR EN HC</td>";
		
		$this->salida.="<td><select name=\"sw_examen\" class=\"select\">";
		
		$this->salida.="<option value=\"0\">NO</option>";
		$this->salida.="<option value=\"1\">SI</option>";
		
		$this->salida.="</select></td></tr>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\">GRUPO TIPO CARGO</td>";
		
		$this->salida.="<td><select name=\"grupo_tipo_cargo\" class=\"select\" onChange='submit()'>";
		
		$this->salida.="<option value=\"\">--GRUPO TIPO CARGO--</option>";
		
		$grupotipocargo=$this->GruposTiposCargo();
		
		foreach($grupotipocargo as $valor)
			if($valor[0]==$grupo_tipocargo)
				$this->salida.="<option value=\"$valor[0]\" selected>$valor[1]</option>";
			else
				$this->salida.="<option value=\"$valor[0]\">$valor[1]</option>";

		$this->salida.="<select>";
		
		$this->salida.="</td>";
		
		$this->salida.="</tr>";
		
		$tipocargo=$this->TiposCargo($grupo_tipocargo);
		
		$this->salida.="<tr>";
		
		$this->salida.="<td class=\"modulo_table_list_title\">TIPO CARGO</td>";
		
		$this->salida.="<td>";

		foreach($tipocargo as $valor)
		{
			$this->salida.="<input type=\"checkbox\" name=\"tipo_cargo[]\" value=\"$valor[0]\" checked>$valor[1]<br>";
		}

		$this->salida.="</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="	<td>";
		$this->salida.="<center><input type=\"button\" class=\"input-submit\" name=\"guardar\" value=\"GUARDAR\" onClick=\"validar(this.form)\"></center>";
		
		$this->salida.="</form>";
		$this->salida.="	</td>";
		$this->salida.="	<td>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	
	
	/***
	* Funcion donde se edita una lista de trabajo de acuerdo a los departamentos que el usuario tenga permiso
	* @return boolean
	*/
	
	function FormaEditarListaTrabajo()
	{
		$lista=$_REQUEST['lista'];

		$this->salida.= ThemeAbrirTabla('EDITAR LISTA DE TRABAJO');
		
		/*$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaEditarListaTrabajo',array('lista'=>$lista));  
		
		if(!empty($_REQUEST['grupo_tipo_cargo']))
		{
			//print_r($_REQUEST);
			
			$nombre=$_REQUEST['nombre'];
			$departamento=$_REQUEST['departamento'];
			$sw_examen=$_REQUEST['sw_examen'];
			$grupo=$_REQUEST['grupo_tipo_cargo'];
			
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','EditarListaTrabajo');
		}
		else
		{
			//print_r($_REQUEST);
			
			$nombre=$lista[1];
			$departamento=$lista[2];
			$sw_examen=$lista[3];
		}*/
		
		$this->salida .= "	<script language=\"javascript\">";
		$this->salida .= "		function validar(objeto)";
		$this->salida .= "		{
							var bandera=false;
							if(objeto.nombre.value==\"\")
							{	
								alert('Debe ingresar el nombre de la lista');
								bandera=true;
							}
							else if(objeto.departamento.value==\"\")
							{
								alert('Debe seleccionar un departamento');
								bandera=true;
							}
							
							else if(bandera==false)
								objeto.submit();
											
						}";
		$this->salida .= "	</script>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','EditarListaTrabajo');
		
		$nombre=$lista[1];
		$departamento=$lista[2];
		$sw_examen=$lista[3];
		
		$this->salida.="<br>";
		
		$this->salida.="<form name=\"forma\" method=\"POST\" action=\"$action\">";
		
		$this->salida.="<input type=\"hidden\" name=\"nlista\" value=\"$lista[0]\">";
		
		$this->salida.="<table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"60%\" >";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\" width=\"35%\">NOMBRE LISTA</td>";
		
		$this->salida.="<td><input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"$nombre\" size=\"50\"></td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\">DEPARTAMENTO</td>";
		
		
		$this->salida.="<td><select name=\"departamento\" class=\"select\">";
		
		$this->salida.="<option value=\"\">--DEPARTAMENTO-</option>";
	
		if(!empty($_REQUEST['grupo_tipo_cargo']))
		{
			if($_SESSION['departamento']==$departamento)
				$this->salida.="<option value=\"".$_SESSION['departamento']."\" selected>".$_SESSION['descripcion']."</option>";
			else
				$this->salida.="<option value=\"".$_SESSION['departamento']."\">".$_SESSION['descripcion']."</option>";
		}
		else
		{
			if($_SESSION['descripcion']==$departamento)
				$this->salida.="<option value=\"".$_SESSION['departamento']."\" selected>".$_SESSION['descripcion']."</option>";
			else
				$this->salida.="<option value=\"".$_SESSION['departamento']."\">".$_SESSION['descripcion']."</option>";
		}
			
		$this->salida.="</select></td></tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\" >VISUALIZAR EXAMEN SIN FIRMAR EN HC</td>";
		
		$this->salida.="<td><select name=\"sw_examen\" class=\"select\">";
		
		if($sw_examen==0)
		{
			$this->salida.="<option value=\"0\" selected>NO</option>";
			$this->salida.="<option value=\"1\">SI</option>";
		}
		else
		{
			$this->salida.="<option value=\"0\">NO</option>";
			$this->salida.="<option value=\"1\" selected>SI</option>";
		}
		
		$this->salida.="</select></td></tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\">GRUPO TIPO CARGO</td>";

		$lista_detalle=$this->ListaTrabajoDetalle($lista[0],'grupo_tipo_cargo');
		
		$grupotipocargo=$this->GruposTiposCargo();
		
		$i=0;
		$this->salida.="<td><select name=\"grupo_tipo_cargo\" class=\"select\" onChange='submit()' disabled>";
		
		if($lista_detalle)
		{
			$this->salida.="<option value=\"\">--GRUPO TIPO CARGO-</option>";
			
			foreach($lista_detalle as $grupo_tipo_cargo) 
			{
				foreach($grupotipocargo as $gtc)
				{
					if(!empty($_REQUEST['grupo_tipo_cargo']))
					{
						if($gtc[0]==$_REQUEST['grupo_tipo_cargo'])
							$this->salida.="<option value=\"$gtc[0]\" selected>$gtc[1]</option>";
						else
							$this->salida.="<option value=\"$gtc[0]\">$gtc[1]</option>";
						
						$grupo=$_REQUEST['grupo_tipo_cargo'];
					}
					else
					{
						if($gtc[0]==$grupo_tipo_cargo[0])
							$this->salida.="<option value=\"$gtc[0]\" selected>$gtc[1]</option>";
						else
							$this->salida.="<option value=\"$gtc[0]\">$gtc[1]</option>";
							
						$grupo=$grupo_tipo_cargo[0];
					}	
				}
			}
		}
		else
		{
			$this->salida.="<option value=\"\">--GRUPO TIPO CARGO-</option>";
			foreach($grupotipocargo as $gtc)
			{
				$this->salida.="<option value=\"$gtc[0]\">$gtc[1]</option>";
			}
			$grupo=$grupo_tipo_cargo;
		}
	
		
		$this->salida.="</select>";
		
		$this->salida.="</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"modulo_list_claro\">";
		
		$this->salida.="<td class=\"modulo_table_list_title\">TIPO CARGO</td>";
		
		$this->salida.="<td>";
		
		$lista_detalle=$this->ListaTrabajoDetalle($lista[0],'tipo_cargo');
		
		$tipocargo=$this->TiposCargo($grupo);
		
		foreach($tipocargo as $tipo_c)
		{
			$ban=0;
			
			foreach($lista_detalle as $tpc) 
			{
				if($tipo_c[0]==$tpc[0])
				{
					$this->salida.="<input type=\"checkbox\" name=\"tipo_cargo[]\" value=\"$tipo_c[0]\" checked disabled>$tipo_c[1]<br>";
					$ban=1;
					break;
				}
			}
			if($ban==0) 
			{
				$this->salida.="<input type=\"checkbox\" name=\"tipo_cargo[]\" value=\"$tipo_c[0]\" disabled>$tipo_c[1]<br>";	
			}
		}
			
		$this->salida.="</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="</table>";
		
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="	<td>";
		$this->salida.="<center><input type=\"button\" class=\"input-submit\" name=\"guardar\" value=\"GUARDAR\" onClick=\"validar(this.form)\"></center>";
		
		$this->salida.="</form>";
		$this->salida.="	</td>";
		$this->salida.="	<td>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	/***
	* Funcion donde se muestra por cada lista, los usuarios que el administrador pueda asignarle permisos 
	* @return boolean
	*/
	function FormaUsuariosListaTrabajo()
	{
		$this->salida.= ThemeAbrirTabla('USUARIOS');
		
		$lista=$_REQUEST['lista_id'];
		
		$llamado=$_REQUEST['llamado'];
		
		$_SESSION['lista']=$lista;
		
		$usuarios=$this->UsuariosListaTrabajo($lista[0],$lista[2]);
		
		if($usuarios)
		{
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','Usuarios');
			
			$this->salida.="<form name=\"forma\" method=\"POST\" action=\"$action\">";
			
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaCrearUsuarios');
	
			$this->salida.="<table border=\"0\" class=\"modulo_table_list\"  align=\"center\" width=\"80%\">";
			
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			
			$this->salida.="<td>UID</td>";
			
			$this->salida.="<td>USUARIO</td>";
			
			$this->salida.="<td>NOMBRE</td>";
			
			$this->salida.="<td>DESCRIPCION</td>";
			
			$this->salida.="<td>PERMISOS</td>";
			
			$this->salida.="</tr>";
			
			$this->salida .= "	<script language=\"javascript\">";
			$this->salida .= "		function mOvr(src,clrOver)";
			$this->salida .= "		{";
			$this->salida .= "			src.style.background = clrOver;";
			$this->salida .= "		}";
			$this->salida .= "		function mOut(src,clrIn)";
			$this->salida .= "		{";
			$this->salida .= "			src.style.background = clrIn;";
			$this->salida .= "		}";
			$this->salida .= "	</script>";
			
			$i=0;
		
			foreach($usuarios as $valor)
			{
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro';
					$background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro';
					$background = "#DDDDDD";
				}
				
				$this->salida.="<tr class=\"$estilo\"  onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
				
				$this->salida.="<td>".$valor[0]."</td>";
				
				$this->salida.="<td>".$valor[1]."</td>";
				
				$this->salida.="<td>".$valor[2]."</td>";
				
				$this->salida.="<td>".$valor[3]."</td>";
				
				$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAsignacionPermisosListas',array('datos'=>$valor,'llamado'=>'FormaUsuariosListaTrabajo'));
					
				$this->salida.="<td align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/modificar.png\"><a href=\"$action\"> EDITAR PERMISOS </a></label></td>";
	
				$this->salida.="</tr>";
				
				$i++;
			}
			
			$this->salida.="</table>";
			
			$this->salida.="</form>";
		}
		else
		{
			$this->salida.="<br><center><label class=\"label\">NO SE ENCONTRARON USUARIOS EN: $lista[1]</label></center>";
		}
			
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;	
	}
	/***
	* Funcion donde se lista todos los usuarios de los departamentos que el administrador pueda asignarle permisos
	* @return boolean
	*/
	function FormaPermisosUsuariosListas($usuario_id)
	{
		$this->salida.= ThemeAbrirTabla('USUARIOS REGISTRADOS DEL DEPARTAMENTO');
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		
		if($usuario_id)
		{
			$_REQUEST['busqueda']=$usuario_id;
			$_REQUEST['criterio']=1;
		}
		
		$this->salida.="<form name=\"forma\" method=\"POST\" action=\"$action\">";
		
		$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="  			<td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO USUARIOS </td>";
		$this->salida.="		</tr>";

		$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="			<td width=\"5%\">TIPO</td>";

		$this->salida.="			<td width=\"10%\" align = left >";
		$this->salida.="				<select size = 1 name = \"criterio\"  class =\"select\">";	
		$this->salida.="					<option value = \"1\">Id</option>";
		$this->salida.="					<option value = \"2\" selected>Login</option>";
		$this->salida.="					<option value = \"3\">Nombre Usuario</option>";
		$this->salida.="				</select>";
		$this->salida.="			</td>";
		
		$this->salida.="			<td width=\"10%\">DESCRIPCIÓN:</td>";
		$this->salida .="			<td width=\"25%\" align=\"center\"><input type=\"text\" class=\"input-text\" 	name = \"busqueda\" size=\"40\" maxlength=\"40\"  value =\"".$_REQUEST['busqueda']."\"></td>" ;

		$this->salida .= "			<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= \"buscar\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="		</tr>";
		$this->salida.="	</form>";
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		
		if($_REQUEST['busqueda'])
		{
			$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
		}
		else
		{
			$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
		}
		
		$this->salida.="  		<td align=\"left\" colspan=\"5\">$cadena</td>";
		
		$this->salida.="	</tr>";
		
		$this->salida.="</table>";

		$usuarios=$this->PermisosUsuariosListas($_SESSION['departamento'],$_REQUEST['busqueda'],$_REQUEST['criterio']);
			
		$this->salida .= "<input class=\"input-submit\" name= \"departamento\" type=\"hidden\" value=\"".$_SESSION['departamento']."\">";
		
		
		$action2=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAdicionarUsuarios');
		
		$this->salida.="<br><center><label class=\"label\"><a href=\"$action2\">ADICIONAR NUEVO USUARIO</a></center><br>";
		
		if(!empty($usuarios))
		{
			$this->salida.="<table border=\"0\" class=\"modulo_table_list\"  align=\"center\" width=\"100%\">";
			
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			
			$this->salida.="		<td>UID</td>";
			
			$this->salida.="		<td>USUARIO</td>";
			
			$this->salida.="		<td>NOMBRE</td>";
			
			$this->salida.="		<td>DESCRIPCION</td>";
	
			$this->salida.="		<td colspan=\"3\" width=\"36%\">PERMISOS</td>";
			
			$this->salida.="	</tr>";
			
			$this->salida .= "	<script language=\"javascript\">";
			$this->salida .= "		function mOvr(src,clrOver)";
			$this->salida .= "		{";
			$this->salida .= "			src.style.background = clrOver;";
			$this->salida .= "		}";
			$this->salida .= "		function mOut(src,clrIn)";
			$this->salida .= "		{";
			$this->salida .= "			src.style.background = clrIn;";
			$this->salida .= "		}";
			$this->salida .= "	</script>";
			
			$i=0;
			
			foreach($usuarios as $valor)
			{
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro';
					$background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro';
					$background = "#DDDDDD";
				}
				
				$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
				
				$this->salida.="<td>".$valor[0]."</td>";
				
				$this->salida.="<td>".strtoupper($valor[1])."</td>";
				
				$this->salida.="<td>".$valor[2]."</td>";
				
				$this->salida.="<td>".strtoupper($valor[3])."</td>";
				
				$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAsignacionPermisosListas',array('datos'=>$valor,'llamado'=>'FormaPermisosUsuariosListas'));
				
				$this->salida.="<td width=\"13%\" align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/editar.png\"><a href=\"$action\"> ASIGNAR LISTAS </a></label></td>";
				
				$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaUsuarioAdmin',array('datos'=>$valor));
				
				$this->salida.="<td width=\"13%\" align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/usuarios.png\"><a href=\"$action\"> ADMINISTRADOR </a></label></td>";
				
				$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaEliminarUsuario',array('datos'=>$valor));
				
				$this->salida.="<td width=\"8%\" align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/elimina.png\"><a href=\"$action\"> BORRAR </a></label></td>";
	
				$this->salida.="</tr>";
				$i++;
			}
			$this->salida.="</table>";
			
			$this->salida.="<br>";
				
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas',array('busqueda'=>$_REQUEST['busqueda'],'criterio'=>$_REQUEST['criterio']));
			
			$Paginador=new ClaseHTML();
			
			$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
		}
		else
			$this->salida.="<center><label class=\"label\"> NO SE ENCONTRARON REGISTROS </label></center>";
		
		//$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','Principal');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;			
	}
	
	/***
		funcion que muestra el usuario administrador
		@return boolean
	*/
	
	function FormaUsuarioAdmin()
	{
		$usuario=$_REQUEST['datos'];	
		
		$this->salida .= "	<script language=\"javascript\">";
		$this->salida .= "		function validar(objeto)";
		$this->salida .= "		{";
		$this->salida .= "			var bandera=0;";
		$this->salida .= "			if(objeto.sw_estado.value==\"\")";
		$this->salida .= "			{	";
		$this->salida .= "				alert('seleccione el estado');";
		$this->salida .= "				bandera=1;";
		$this->salida .= "			}";
		$this->salida .= "			if(bandera==0)";
		$this->salida .= "				objeto.submit();";
		$this->salida .= "		}";
		$this->salida .= "	</script>";
		
		
		$this->salida.= ThemeAbrirTabla('USUARIO ADMINISTRADOR');
		
		$action1=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','UsuarioAdmin',array('datos'=>$usuario,'departamento'=>$_SESSION['departamento']));
		
		$this->salida.="	<form action=\"$action1\" name=\"forma\" method=\"POST\">";
		
		$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"45%\">";
		
		$this->salida.="<tr>";
		
		$this->salida.="	<td class=\"modulo_table_list_title\">UDI</td>";
		$this->salida.="	<td class=\"modulo_list_claro\">".$usuario[0]."</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr>";		
		
		$this->salida.="	<td class=\"modulo_table_list_title\">USUARIO</td>";
		$this->salida.="	<td class=\"modulo_list_claro\">".strtoupper($usuario[1])."</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr>";
		
		$this->salida.="	<td class=\"modulo_table_list_title\">NOMBRE</td>";
		$this->salida.="	<td class=\"modulo_list_claro\">".$usuario[2]."</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr>";
		
		$this->salida.="	<td class=\"modulo_table_list_title\">DESCRIPCION</td>";
		$this->salida.="	<td class=\"modulo_list_claro\">".strtoupper($usuario[3])."</td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr>";
		
		$dept=$this->BuscarUsuarioAdmin($usuario[0],$_SESSION['departamento']);
		
		$this->salida.="<td class=\"modulo_table_list_title\">DEPARTAMENTO</td>";
		
		$this->salida.="<td class=\"modulo_list_claro\">".$_SESSION['descripcion']."</td>";
		
		$this->salida.="</tr>";

		$this->salida.="<tr>";
		
		$this->salida.="<td class=\"modulo_table_list_title\">ESTADO</td>";
		
		$this->salida.="<td class=\"modulo_list_claro\"><select name=\"sw_estado\" class=\"select\">";
		
		$this->salida.="<option value=\"\">--ESTADO--</option>";
		
		if($dept)
		{
			foreach($dept as $valor)
			{
				if($valor[2]=='0')
				{
					$this->salida.="<option value=\"0\" selected>ACTIVO</option>";
					$this->salida.="<option value=\"1\">INACTIVO</option>";
				}
				else
				{
					$this->salida.="<option value=\"0\">ACTIVO</option>";
					$this->salida.="<option value=\"1\" selected>INACTIVO</option>";
				}
			}
		}
		else
		{
			$this->salida.="<option value=\"0\">ACTIVO</option>";
			$this->salida.="<option value=\"1\">INACTIVO</option>";
		}
			
		$this->salida.="</select></td></tr>";
		
		$this->salida.="</table>";

		
		$this->salida.="<table align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="	<td>";
		$this->salida.="	<br><center><input type=\"button\" class=\"input-submit\" name=\"Aceptar\" value=\"ACEPTAR\" onClick=\"validar(this.form)\"></center><br>";
		
		$this->salida.="</form>";
				
		
		$this->salida.="	</td>";
		$this->salida.="	<td>";
		$action2=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		$this->salida.="	<form action=\"$action2\" name=\"forma\" method=\"POST\">";
		$this->salida.="		<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="	</form>";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	
	/***
		Funcion que muestra el usuario a eliminar
		@return boolean
	*/
	function FormaEliminarUsuario()
	{
		
		$datos=$_REQUEST['datos'];
		$departamento=$_SESSION['departamento'];
		$usuario=$datos[1];
		
		$this->salida.= ThemeAbrirTabla('');
		$this->salida.="<table align=\"center\" width=\"45%\">";
		$this->salida.="<tr>";
		$this->salida.="	<td align=\"left\" class=\"modulo_table_list_title\"  width=\"25%\">";
		$this->salida.="		LOGIN: ";
		$this->salida.="	</td>";
		$this->salida.="	<td class=\"modulo_list_claro\" align=\"left\">";
		$this->salida.="		$usuario";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$this->salida.="	<td align=\"left\" class=\"modulo_table_list_title\">";
		$this->salida.="		DEPARTAMENTO: ";
		$this->salida.="	</td>";
		$this->salida.="	<td class=\"modulo_list_claro\" align=\"left\">";
		$this->salida.="		".$_SESSION['descripcion']."";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
		$action1=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','EliminarUsuario',array('datos'=>$datos,'departamento'=>$departamento));
		$action2=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		
		$this->salida.="<table align=\"center\">";
		$this->salida.="<tr>";
		$this->salida.="	<td>";
		$this->salida.="	<form action=\"$action1\" name=\"forma\" method=\"POST\">";
		$this->salida.="		<br><center><input type=\"submit\" class=\"input-submit\" name=\"Eliminar\" value=\"BORRAR\"></center><br>";
		$this->salida.="	</form>";
		$this->salida.="	</td>";
		$this->salida.="	<td>";
		$this->salida.="	<form action=\"$action2\" name=\"forma\" method=\"POST\">";
		$this->salida.="		<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="	</form>";
		$this->salida.="	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
	
	/***********************************************************************************************************
		Funcion que muestra los usuarios del sistema para adicionarlos al departamento correspondiente a las listas de trabajo
		@return boolean
	************************************************************************************************************/
	
	function FormaAdicionarUsuarios()
	{
		$this->salida.= ThemeAbrirTabla('USUARIOS DEL SISTEMA');
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAdicionarUsuarios');

		$this->salida.="<form name=\"forma\" method=\"POST\" action=\"$action\">";
		
		$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="  			<td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO USUARIOS </td>";
		$this->salida.="		</tr>";

		$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="			<td width=\"5%\">TIPO</td>";

		$this->salida.="			<td width=\"10%\" align = left >";
		$this->salida.="				<select size = 1 name = \"criterio\"  class =\"select\">";
		$this->salida.="					<option value = \"1\">Id</option>";
		$this->salida.="					<option value = \"2\" selected>Login</option>";
		$this->salida.="					<option value = \"3\">Nombre Usuario</option>";
		$this->salida.="				</select>";
		$this->salida.="			</td>";
		
		$this->salida.="			<td width=\"10%\">DESCRIPCIÓN:</td>";
		$this->salida .="			<td width=\"25%\" align=\"center\"><input type=\"text\" class=\"input-text\" 	name = \"busqueda\" size=\"40\" maxlength=\"40\"  value =\"".$_REQUEST['busqueda']."\"></td>" ;

		$this->salida .= "			<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= \"buscar\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="		</tr>";
		$this->salida.="	</form>";
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		
		if($_REQUEST['busqueda'])
		{
			$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
		}
		else
		{
			$cadena="Buscador Avanzado: Busqueda de todos los usuarios";
		}
		
		$this->salida.="  		<td align=\"left\" colspan=\"5\">$cadena</td>";
		
		$this->salida.="	</tr>";
		
		$this->salida.="</table><br>";
		
		
		$usuarios=$this->ListarUsuarios($_SESSION['departamento'],$_REQUEST['busqueda'],$_REQUEST['criterio']);
	
		$this->salida .= "<input class=\"input-submit\" name= \"departamento\" type=\"hidden\" value=\"".$_SESSION['departamento']."\">";
		
		if(!empty($usuarios))
		{
		
			$this->salida.="<table border=\"0\" class=\"modulo_table_list\"  align=\"center\" width=\"80%\">";
			
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			
			$this->salida.="		<td>UID</td>";
			
			$this->salida.="		<td>USUARIO</td>";
			
			$this->salida.="		<td>NOMBRE</td>";
			
			$this->salida.="		<td>DESCRIPCION</td>";
	
			$this->salida.="		<td>ACCION</td>";
			
			$this->salida.="	</tr>";
			
			$this->salida .= "	<script language=\"javascript\">";
			$this->salida .= "		function mOvr(src,clrOver)";
			$this->salida .= "		{";
			$this->salida .= "			src.style.background = clrOver;";
			$this->salida .= "		}";
			$this->salida .= "		function mOut(src,clrIn)";
			$this->salida .= "		{";
			$this->salida .= "			src.style.background = clrIn;";
			$this->salida .= "		}";
			$this->salida .= "	</script>";
			
			$i=0;
			foreach($usuarios as $valor)
			{
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro';
					$background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro';
					$background = "#DDDDDD";
				}
				
				$this->salida.="<tr class=\"$estilo\"  onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
				
				$this->salida.="<td >".$valor[0]."</td>";
				
				$this->salida.="<td>".strtoupper($valor[1])."</td>";
				
				$this->salida.="<td>".$valor[2]."</td>";
				
				$this->salida.="<td>".strtoupper($valor[3])."</td>";
				
				$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','AdicionarUsuario',array('datos'=>$valor,'departamento'=>$_SESSION['departamento']));
				
				$this->salida.="<td align=\"center\"><label class=\"label\"><img src=\"".GetThemePath()."/images/usuarios.png\"><a href=\"$action\"> ADICIONAR</a></label></td>";
	
				$this->salida.="</tr>";
				
				$i++;
			}
			$this->salida.="</table>";
			
			$this->salida.="<br>";
			
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAdicionarUsuarios',array('busqueda'=>$_REQUEST['busqueda'],'criterio'=>$_REQUEST['criterio']));

			$Paginador=new ClaseHTML();
			
			$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
			
		}
		else
			$this->salida.="<center><label class=\"label\"> NO SE ENCONTRARON REGISTROS </label></center>";
		
		//$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		
		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;			
	}

	/***
	* Funcion donde se asigna los permisos de los usuarios a las listas de trabajo
	* @return boolean
	*/
	
	function FormaAsignacionPermisosListas($usuario,$tp,$llamado)
	{
		$this->salida.= ThemeAbrirTabla('ASIGNACION DE PERMISOS');
		
		if(!empty($_REQUEST['datos']))
		{
			
			$usuario=$_REQUEST['datos'];
			$llamado=$_REQUEST['llamado'];
			
			if($_REQUEST['tp'])	
				$tipo=$_REQUEST['tp'];
			else
				$tipo='tomado';
		}
		else
		{
			$tipo=$tp;
			$llamado=$llamado;	
		}
		
		$UID=$usuario[0];
		$usu=$usuario[1];
		$nombre=$usuario[2];
		$descripcion=$usuario[3];
		
		$this->salida .= "<SCRIPT>";
		$this->salida .= "	function GuardarCambios()
					{
						
						if(confirm('Desea guardar los cambios?'))
						{
							document.forma.submit();
						}
					}
		";
		$this->salida .= "</SCRIPT>";
			
		$action1=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','AsignacionPermisosListas',array('usuario'=>$usuario,'tp'=>$tipo,'llamado'=>$llamado));
		
		$this->salida.="<form name=\"forma\" method=\"POST\" action=\"$action1\">";
				
		$profesional=$this->BuscarProfesionales($UID);
		
		$this->salida.="<input type=\"hidden\" name=\"num_deptos\" value=\"1\">";
		
		$this->salida.="<input type=\"hidden\" name=\"tp\" value=\"".$tipo."\">";
		
		$this->salida.="<table border=\"0\" class=\"modulo_table_list\"  align=\"center\" width=\"60%\">";
		
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="		<td align=\"left\" width=\"30%\">UID</td>";
		
		$this->salida.="		<td class=\"modulo_list_oscuro\" align=\"left\"><label class=\"label\">$UID</label></td>";
		
		$this->salida.="	</tr>";
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="		<td align=\"left\">USUARIO</td>";
		
		$this->salida.="		<td class=\"modulo_list_oscuro\" align=\"left\"><label class=\"label\">".strtoupper($usu)."</label></td>";
		
		$this->salida.="	</tr>";
		
		$this->salida.="	<tr class=\"modulo_table_list_title\" align=\"left\">";
		
		$this->salida.="		<td align=\"left\">NOMBRE</td>";
		
		$this->salida.="		<td class=\"modulo_list_oscuro\" align=\"left\"><label class=\"label\">".strtoupper($nombre)."</label></td>";
		
		$this->salida.="	</tr>";
		
		$this->salida.="</table>";
		
		$this->salida.="<br>";
		
		$this->salida.="<input type=\"hidden\" name=\"usuario_id\" value=\"$UID\">";
		
		$this->salida.="<table align=\"center\" width=\"80%\" cellspacing=\"0\" cellpadding=\"0\">";
		
		$this->salida.="	<tr>";

		$izq_azul="/images/HistoriaClinica/angulo_der_sup1.gif";
		$franja_azul="/images/HistoriaClinica/azul.png";
		$der_azul="/images/HistoriaClinica/angulo_izq_sup1.gif";
		
		$izq_azulclaro="/images/HistoriaClinica/angulo_der_sup1.gif";
		$franja_azulclaro="/images/HistoriaClinica/azulclaro.png";
		$der_azulclaro="/images/HistoriaClinica/angulo_izq_sup1.gif";
		
		$action1=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAsignacionPermisosListas',array('datos'=>$usuario,'tp'=>'tomado','llamado'=>$llamado));
		
		if($tipo=='tomado')
		{
			$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$izq_azul\" width=\"100%\" height=\"30\"></td>";
			
			$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"30\" height=\"30\" class=\"titulo_tabla\">TOMADO</td>";
			
			$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$der_azul\" width=\"100%\" height=\"30\"></td>";	
		
		}
		else
		{
			$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$izq_azulclaro\" width=\"100%\" height=\"30\"></td>";
			
			$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"30\" height=\"30\" class=\"titulo_tabla\"><a href=\"$action1\">TOMADO</a></td>";
			
			$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$der_azulclaro\" width=\"100%\" height=\"30\"></td>";	
		
		}
		
		$action2=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAsignacionPermisosListas',array('datos'=>$usuario,'tp'=>'transcripcion','llamado'=>$llamado));
		
		
		if($tipo=='transcripcion')
		{
			
			$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$izq_azul\" width=\"100%\" height=\"30\"></td>";
			
			$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"30\" height=\"30\" class=\"titulo_tabla\">TRANSCRIPCION</td>";
			
			$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$der_azul\" width=\"100%\" height=\"30\"></td>";	
		}
		else
		{
			$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$izq_azulclaro\" width=\"100%\" height=\"30\"></td>";
			
			$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"30\" height=\"30\" class=\"titulo_tabla\"><a  href=\"$action2\">TRANSCRIPCION</a></td>";
			
			$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$der_azulclaro\" width=\"100%\" height=\"30\"></td>";	
		
		}
			
		$action3=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaAsignacionPermisosListas',array('datos'=>$usuario,'tp'=>'firmado','llamado'=>$llamado));
		
		$espacio=7;
		
		if(!empty($profesional))
		{
			if($tipo=='firmado')
			{
				
				$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$izq_azul\" width=\"100%\" height=\"30\"></td>";
			
				$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"30\" height=\"30\" class=\"titulo_tabla\">FIRMADO</td>";
				
				$this->salida.="	<td background=\"".GetThemePath()."$franja_azul\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$der_azul\" width=\"100%\" height=\"30\"></td>";	
			}
			else
			{
				$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$izq_azulclaro\" width=\"100%\" height=\"30\"></td>";
				
				$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"30\" height=\"30\" class=\"titulo_tabla\"><a href=\"$action3\">FIRMADO</a></td>";
				
				$this->salida.="	<td background=\"".GetThemePath() ."$franja_azulclaro\" width=\"10\" height=\"30\"><img src=\"".GetThemePath()."$der_azulclaro\" width=\"100%\" height=\"30\"></td>";	
	
			}
			$espacio=10;
		}
	
		$this->salida.="<td><pre>                                                            </pre></td>";
		
		$this->salida.="</tr>";
		
		$this->salida.="<tr>";
		
		$this->salida.="	<td colspan=\"$espacio\" width=\"100%\">";
		
		$this->salida.= ThemeAbrirTabla('');
		
		$this->salida.="<table border=\"0\" class=\"modulo_table_list\"  align=\"center\" width=\"80%\">";
		
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
		
		$this->salida.="		<td>EMPRESA</td>";
		
		$this->salida.="		<td>CENTRO DE UTILIDAD</td>";
		
		$this->salida.="		<td>DEPARTAMENTO</td>";
		
		$this->salida .= "<SCRIPT>";
		$this->salida .= "	function chequeoMostrar(frm,x){";
		$this->salida .= "  		if(x==true){";
		$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      			if(frm.elements[i].type=='checkbox' &&  frm.elements[i].name == 'sw_mostrar_listas'){";
		$this->salida .= "        				frm.elements[i].checked=true";
		$this->salida .= "				}";
		$this->salida .= "    			}";
		$this->salida .= " 		}else{";
		$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      			if(frm.elements[i].type=='checkbox' && frm.elements[i].name == 'sw_mostrar_listas'){";
		$this->salida .= "        				frm.elements[i].checked=false";
		$this->salida .= "      			}";
		$this->salida .= "    			}";
		$this->salida .= "  		}";
		$this->salida .= "	}";
		$this->salida .= "</SCRIPT>";
		
		//$this->salida.="		<td><input type=\"checkbox\" name=\"Mostrar\" onclick=chequeoMostrar(this.form,this.checked)>TODOS</td>";
		
		if($tipo=='transcripcion' || $tipo=='firmado')
			$this->salida.="	<td>TIPO DE PRESENTACION</td>";
		
		$this->salida.="	</tr>";
		
		$i=0;

		$listas=$this->ListasTrabajosPorDepartamento($_SESSION['departamento']);
		
		if(!empty($listas))
		{
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			
			$this->salida.="		<td class=\"modulo_list_oscuro\"><label class=\"label\">".$_SESSION['descripcion_emp']."</label></td>";
			
			$this->salida.="		<td class=\"modulo_list_oscuro\"><label class=\"label\">".$_SESSION['descripcion_centro']."</label></td>";
			
			$this->salida.="		<td class=\"modulo_list_oscuro\"><label class=\"label\">".$_SESSION['descripcion']."</label></td>";
			
			$componentes=$this->MostrarListas($UID,$_SESSION['departamento'],$tipo);
			
			if($componentes)
			{
				foreach($componentes as $info)
				{
					/*
						checkea si muestra o no las listas
					*/
					/*$this->salida.="<td class=\"modulo_list_oscuro\">";
					
					if($info[0]==0 || is_null($info[0]))
					{
						$this->salida.="<input type=\"checkbox\" name=\"sw_mostrar_listas$i\" value=\"1\"><label class=\"label\">MOSTRAR LISTAS</label>";
					}
					else 
					{
						$this->salida.="<input type=\"checkbox\" name=\"sw_mostrar_listas$i\" value=\"1\" checked><label class=\"label\">MOSTRAR LISTAS</label>";
					}
					
					$this->salida.="</td>";*/
						
					/*
						tipo de presentacion
					*/
					
					if($tipo=='transcripcion' || $tipo=='firmado')
					{
						$this->salida.="<td class=\"modulo_list_oscuro\">"; 
						$this->salida.="<select name=\"tipo_presentacion$i\" class=\"select\">";
						
						if($info[1]==1)
						{
							$this->salida.="<option value=\"1\" selected>NORMAL</option>";
							$this->salida.="<option value=\"2\">AGRUPADA</option>";
						}
						else
						{
							$this->salida.="<option value=\"1\">NORMAL</option>";
							$this->salida.="<option value=\"2\" selected>AGRUPADA</option>";		
						}
						$this->salida.="</select></td>";	
					}
					
					if($tipo=='firmado')
					{
						$tipo_id_tercero=$info[2];
						$tercero_id=$info[3];
					}
				}
			}
			else
			{
				//$this->salida.="<td class=\"modulo_list_oscuro\"><input type=\"checkbox\" name=\"sw_mostrar_listas$i\" value=\"1\"><label class=\"label\">MOSTRAR LISTAS</label></td>";
				
				if($tipo=='transcripcion' || $tipo=='firmado')
				{
					$this->salida.="<td class=\"modulo_list_oscuro\"><select name=\"tipo_presentacion$i\" class=\"select\">
					<option value=\"1\">NORMAL</option>";
					$this->salida.="<option value=\"2\">AGRUPADA</option>";
					$this->salida.="</select></td>";
				}
			}
			$this->salida.="	</tr>";
			$i++;		
		}

		if($tipo=='firmado')
		{
			
			$tipo_id=$this->TraerDatosTerceros();
			
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			
			$this->salida.="<td colspan=\"5\" class=\"modulo_list_oscuro\">";

			$this->salida.="<label class=\"label\"> TIPO: </label>";
			
			$this->salida.="<select name=\"tipo_id_tercero\">";
			
			foreach($tipo_id as $tipo_tercero)
			{
				/*
					$tipo_tercero[0] --> tipo_id_tercero (CC, NIT)
				*/
				if($tipo_id_tercero==$tipo_tercero[0])
				{
					$this->salida.="<option value=\"$tipo_tercero[0]\" selected>$tipo_tercero[0]</option>";
				}
				else
				{
					$this->salida.="<option value=\"$tipo_tercero[0]\">$tipo_tercero[0]</option>";
				}
			}
			
			$this->salida.="</select>";
			
			$this->salida.="<label class=\"label\"> - IDENTIFICACION: </label>";

			$this->salida.="<input type=\"text\" name=\"tercero_id\" value=\"$tercero_id\"></td>";
			
			$this->salida.="</tr>";
		}
		
		$this->salida.="</table>";					

		$this->salida.="<br>";
		
		$this->salida.="<table border=\"0\" class=\"modulo_table_list\" align=\"center\" width=\"80%\">";
		
		$this->salida.="	<tr align=\"center\">";
		
		$this->salida.="		<td colspan=\"4\" class=\"modulo_table_list_title\">LISTAS DE TRABAJO</td>";
		
		$this->salida.="	</tr>";

		$this->salida .= "<SCRIPT>";
		$this->salida .= "	function chequeoListas(frm,x){";
		$this->salida .= "  		if(x==true){";
		$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      			if(frm.elements[i].type=='checkbox' &&  frm.elements[i].name !='sw_mostrar_listas' && frm.elements[i].name !='Mostrar'){";
		$this->salida .= "        				frm.elements[i].checked=true";
		$this->salida .= "				}";
		$this->salida .= "    			}";
		$this->salida .= " 		}else{";
		$this->salida .= "    			for(i=0;i<frm.elements.length;i++){";
		$this->salida .= "      			if(frm.elements[i].type=='checkbox' &&  frm.elements[i].name !='sw_mostrar_listas' && frm.elements[i].name !='Mostrar'){";
		$this->salida .= "        				frm.elements[i].checked=false";
		$this->salida .= "      			}";
		$this->salida .= "    			}";
		$this->salida .= "  		}";
		$this->salida .= "	}";
		$this->salida .= "</SCRIPT>";	
		
		$this->salida.="	<tr class=\"modulo_table_list_title\">";
				
		$this->salida.="		<td width=\"5%\">#</td>";
										
		$this->salida.="		<td>DESCRIPCION</td>";
						
		$this->salida.="		<td width=\"15%\"> TODAS <input type = \"checkbox\" name= \"TodasListas\" onclick=\"chequeoListas(this.form,this.checked)\"></td>";
	
		$this->salida.="	</tr>";
		
		$i=0;
			
		$permiso_lista=$this->BuscarPermisosListas($UID,$_SESSION['departamento'],$tipo);

		$listas=$this->ListasTrabajosPorDepartamento($_SESSION['departamento']);

		if(!empty($listas))
		{
		
			$this->salida.="<input type=\"hidden\" name=\"num_listas[]\" value=\"".sizeof($listas)."\">";
			
			$this->salida.="<input type=\"hidden\" name=\"departamento[]\" value=\"".$_SESSION['departamento']."\">";
			
			$j=0;
			
			foreach($listas as $valor)
			{
				$this->salida.="		<tr class=\"modulo_table_list_title\">";

				$this->salida.="			<td class=\"modulo_list_claro\" width=\"5%\">".$valor[0]."</td>";
				
				$this->salida.="			<td class=\"modulo_list_claro\">".$valor[1]."</td>";
				
				$this->salida.="			<td class=\"modulo_list_claro\" width=\"5%\">";
				
				$ban=false;
				
				foreach($permiso_lista as $lista)
				{
					if($valor[0]==$lista[0])
					{
						$this->salida.="<input type=\"checkbox\" name=\"listas_id$i$j\" value=\"$valor[0]\" checked>";
						$ban=true;
						$j++;
						break;
					}
				}
				
				if($ban==false)
				{
					$this->salida.="<input type=\"checkbox\" name=\"listas_id$i$j\" value=\"$valor[0]\">";
					$j++;
				}
					
				$this->salida.="			</td>";
				
				$this->salida.="		</tr>";
			}
			
			$i++;
		}

		$this->salida.="</table>";
		
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"guardar\" value=\"GUARDAR\"></center>";

		$this->salida.= ThemeCerrarTabla();
		
		$this->salida.="</td>";
		
		$this->salida.="</tr>";

		$this->salida.="</table>";

		$this->salida.="</form>";
		
		$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaVerListasTrabajos');
				
		if($llamado=='FormaUsuariosListaTrabajo')
		{
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaUsuariosListaTrabajo',array('lista_id'=>$_SESSION['lista']));
		}
		else
		{
			$action=ModuloGetURL('app','Os_Listas_Trabajo_Administracion','user','FormaPermisosUsuariosListas');
		}

		$this->salida.="<form action=\"$action\" name=\"forma\" method=\"POST\">";
		$this->salida.="<br><center><input type=\"submit\" class=\"input-submit\" name=\"volver\" value=\"VOLVER\"></center><br>";
		$this->salida.="</form>";		
		
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}
		
}//fin clase userclasses
?>

