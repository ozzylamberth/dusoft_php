<?php

/**
* $Id: app_Consulta_Fallas_Sistema_userclasses_HTML.php,v 1.3 2006/04/05 19:39:53 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  luis alejandro vargas
* @package IPSOFT-SIIS
*/

IncludeClass("ClaseHTML");

class app_Consulta_Fallas_Sistema_userclasses_HTML extends app_Consulta_Fallas_Sistema_user
{
	function app_Registro_Fallas_Sistema_userclasses_HTML()
	{
		$this->app_Registro_Fallas_Sistema_user();
		return true;
	}
	
	function FormaConsultaPermisos()
     	{
		$permisos=$this->ConsultaPermisos();
		
		if(!empty($permisos))
		{
			$this->FormaConsulta();
		}
		else
		{
			$this->salida.= ThemeAbrirTabla('CONSULTA DE FALLAS DEL SISTEMA');	
			$this->salida.=" <center><label class=\"label\"> EL USUARIO NO TIENE PERMISOS PARA ACCEDER A ESTE MODULO </label><br><br>";
			
			$action1 = ModuloGetURL('system','Menu','user','main');
			$this->salida.=" <form name=\"formavolver\" method=\"POST\" action=\"$action1\">";
			$this->salida.="   <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
			$this->salida.=" </form></center>";
			$this->salida.= ThemeCerrarTabla();	
		}
	}
	/********************************************************************************* 
	* funcion que consulta las fallas del sistema
	* 
	* @return boolean  
	**********************************************************************************/
     	function FormaConsulta()
     	{
		if(!empty($_REQUEST['criterio']))
		{
			$tipo_filtro=$_REQUEST['criterio'];	
		}
		else
		{
			$tipo_filtro=1;	
		}

		$action = ModuloGetURL('app','Consulta_Fallas_Sistema','user','FormaConsulta');
		
		$this->salida.= ThemeAbrirTabla('CONSULTA DE FALLAS DEL SISTEMA');		
		
		$this->salida.="<form name=\"forma_buscar\" method=\"POST\" action=\"$action\">";
		
		$this->salida.="	<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="		<tr class=\"modulo_table_list_title\">";
		$this->salida.="  			<td align=\"center\" colspan=\"7\">BUSCADOR DE REGISTROS DE FALLAS DEL SISTEMA </td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="			<td width=\"5%\">TIPO</td>";

		$this->salida.="			<td width=\"10%\" align =\"left\" >";
		
		if($tipo_filtro==1)
		{
			$this->salida.="			<select size = 1 name = \"criterio\"  class =\"select\" onChange=\"submit()\">";	
			$this->salida.="				<option value = \"1\" selected> FECHA DE REGISTRO </option>";
			$this->salida.="				<option value = \"2\"> TIPO DE FALLA   </option>";
			$this->salida.="				<option value = \"3\"> PROFESIONAL </option>";
			$this->salida.="			</select>";
		}
		else if($tipo_filtro==2)
		{
			$this->salida.="			<select size = 1 name = \"criterio\"  class =\"select\" onChange=\"submit()\">";	
			$this->salida.="				<option value = \"1\"> FECHA DE REGISTRO </option>";
			$this->salida.="				<option value = \"2\" selected> TIPO DE FALLA   </option>";
			$this->salida.="				<option value = \"3\"> PROFESIONAL </option>";
			$this->salida.="			</select>";
		}
		else if($tipo_filtro==3)
		{
			$this->salida.="			<select size = 1 name = \"criterio\"  class =\"select\" onChange=\"submit()\">";	
			$this->salida.="				<option value = \"1\"> FECHA DE REGISTRO </option>";
			$this->salida.="				<option value = \"2\"> TIPO DE FALLA   </option>";
			$this->salida.="				<option value = \"3\" selected> PROFESIONAL </option>";
			$this->salida.="			</select>";
		}
		$this->salida.="			</td>";
		
		if($tipo_filtro==1)
		{
			$this->salida.="		<td width=\"5%\"> DESDE: </td>";
			$this->salida .="		<td width=\"25%\" align=\"center\"><input type=\"text\" class=\"input-text\" 	name = \"fecha_ini\" size=\"20\" maxlength=\"10\"  value =\"".$_REQUEST['fecha_ini']."\" readonly><sub>".ReturnOpenCalendario("forma_buscar","fecha_ini","-")."</sub></td>";
			$this->salida.="		<td width=\"5%\"> HASTA: </td>";
			$this->salida .="		<td width=\"25%\" align=\"center\"><input type=\"text\" class=\"input-text\" 	name = \"fecha_fin\" size=\"20\" maxlength=\"10\"  value =\"".$_REQUEST['fecha_fin']."\" readonly><sub>".ReturnOpenCalendario("forma_buscar","fecha_fin","-")."</sub></td>";	
		}
		else if($tipo_filtro==2)
		{
			$this->salida.="			<td width=\"5%\"> TIPO FALLA: </td>";
			
			$tp_f=$this->LlamarTiposFallas();
			
			$this->salida.="			<td width=\"10%\" align=\"left\">";
			$this->salida.="				<select size = 1 name = \"tipo_falla\" 
			class =\"select\">";
			$this->salida.="		<option value = \"\">--SELECCIONE TIPO FALLA--</option>";
			
			foreach($tp_f as $tipofalla)
			{
				if($_REQUEST['tipo_falla']==$tipofalla['tipo_falla_id'])
				{
					$this->salida.="		<option value = \"".$tipofalla['tipo_falla_id']."\" selected>".$tipofalla['tipo_falla']."</option>";
				}
				else
				{
					$this->salida.="		<option value = \"".$tipofalla['tipo_falla_id']."\">".$tipofalla['tipo_falla']."</option>";
				}
			}
				
			$this->salida.="</select>";
			$this->salida.="</td>";
		}
		else if($tipo_filtro==3)
		{
			$this->salida.="			<td width=\"5%\"> TIPO: </td>";
			$this->salida.="			<td width=\"10%\" align=\"left\">";
			$this->salida.="				<select size = 1 name = \"criterio_pro\"  class =\"select\">";	
		
			if($_REQUEST['criterio_pro']==1)
			{
				$this->salida.="					<option value = \"1\" selected> IDENTIFICACION </option>";
				$this->salida.="					<option value = \"2\"> NOMBRE </option>";
			}
			else if($_REQUEST['criterio_pro']==2)
			{
				$this->salida.="                   <option value = \"1\"> IDENTIFICACION </option>";
				$this->salida.="                   <option value = \"2\" selected> NOMBRE </option>";
			}
			else
			{
				$this->salida.="                   <option value = \"1\"> IDENTIFICACION </option>";
				$this->salida.="                   <option value = \"2\"> NOMBRE </option>";
			}
		
			$this->salida.="				</select>";
			$this->salida.="			</td>";
			
			$this->salida .="			<td width=\"25%\" align=\"center\"><input type=\"text\" class=\"input-text\" 	name = \"profesional\" size=\"40\" maxlength=\"40\"  value =\"".$_REQUEST['profesional']."\"></td>";
		}
		
		$this->salida .= "			<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="		</tr>";
		$this->salida.="	</form>";
		$this->salida.="</table>";

		$this->salida.=" <br>";
		$ban=0;
		
		$fechaI=$this->FechaStamp($_REQUEST['fecha_ini']);
		$fechaF=$this->FechaStamp($_REQUEST['fecha_fin']);
			
		if(!empty($_REQUEST['buscar']))
		{
			$fecha=date("Y-m-d");
			
			if($fechaI > $fecha)
			{
				$this->salida.=" <center><label class=\"label\"> LA FECHA INICIAL NO PUEDE SER MAYOR QUE LA FECHA ACTUAL </label><br><br>";
				$ban=1;
			}
			if($fechaF > $fecha)
			{
				$this->salida.=" <center><label class=\"label\"> LA FECHA FINAL NO PUEDE SER MAYOR QUE LA FECHA ACTUAL </label><br><br>";
				$ban=1;
			}
			if($fechaI > $fechaF)
			{
				$this->salida.=" <center><label class=\"label\"> LA FECHA INICIAL NO PUEDE SER MAYOR QUE LA FECHA FINAL </label><br><br>";	
				$ban=1;
			}
		}
		
		$k=0;
		
		if($ban==0)
			$reg=$this->ConsultarFallasSistema($fechaI,$fechaF,$_REQUEST['tipo_falla'],$_REQUEST['profesional'],$_REQUEST['criterio_pro']);
		else
		{
			$reg=$this->ConsultarFallasSistema("","",$_REQUEST['tipo_falla'],$_REQUEST['profesional'],$_REQUEST['criterio_pro']);
			$ban=0;
		}
		if(!empty($reg))
		{
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"100%\">";
			$this->salida.=" <tr class=\"modulo_table_list_title\">";
			$this->salida.="  <td> # REGISTRO </td>";
			$this->salida.="  <td> TIPO DE FALLA </td>";
			$this->salida.="  <td> FECHA </td>";
			$this->salida.="  <td> DESCRIPCION </td>";
			$this->salida.="  <td> IDENTIFICACION </td>";
			$this->salida.="  <td> PROFESIONAL </td>";
			$this->salida.=" </tr>";
			
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

			foreach($reg as $registros)
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
				
				$this->salida.=" <tr align=\"center\" class=\"$estilo\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
				$this->salida.="  <td width=\"10%\" align=\"center\">".$registros['registro_id']."</td>";
				$this->salida.="  <td width=\"10%\" align=\"center\">".$registros['tipo_falla']."</td>";
				$this->salida.="  <td width=\"15%\" align=\"center\">".$registros['fecha_registro']."</td>";
				$this->salida.="  <td width=\"35%\" align=\"left\">".$registros['descripcion'] ."</td>";
				$this->salida.="  <td width=\"10%\" align=\"left\">".$registros['tipo_id_tercero']." - ".$registros['tercero_id'] ."</td>";
				$this->salida.="  <td width=\"20%\" align=\"left\">".$registros['nombre'] ."</td>";
				$this->salida.=" </tr>";
				
				$k++;
			}
		
			$this->salida.="</table>";
		}
		else
			$this->salida.=" <center><label class=\"label\"> NO SE ENCONTRARON REGISTROS DE LA BUSQUEDA </label><br><br>";
		
		$this->salida.="<br>";
		
		$Paginador=new ClaseHTML();
		
		$action = ModuloGetURL('app','Consulta_Fallas_Sistema','user','FormaConsulta');
	
		$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
		
		$this->salida.=" <br>";
		
		$this->salida.=" <center>";
		$action1 = ModuloGetURL('system','Menu','user','main');
		$this->salida.=" <form name=\"formavolver\" method=\"POST\" action=\"$action1\">";
		$this->salida.="   <input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\">";
		$this->salida.=" </form></center>";
		
		$this->salida.=ThemecerrarTabla();
		
		return true;
     	}
	
}//fin de la clase

?>

