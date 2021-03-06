<?php

/**
* $Id: app_Registro_Fallas_Sistema_userclasses_HTML.php,v 1.4 2006/05/17 14:02:08 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  luis alejandro vargas
* @package IPSOFT-SIIS
*/
IncludeClass("ClaseHTML");

class app_Registro_Fallas_Sistema_userclasses_HTML extends app_Registro_Fallas_Sistema_user
{
	function app_Registro_Fallas_Sistema_userclasses_HTML()
	{
		$this->app_Registro_Fallas_Sistema_user();
		return true;
	}
	
     	/********************************************************************************* 
	* funcion que permite desplegar la informacion de los departamentos en pantalla
	* 
	* @return boolean  
	**********************************************************************************/
     	
	function FormaRegistro($tipo_falla,$fecha_ocurrio,$hora_ocurrio,$descripcion)
     	{
		$action = ModuloGetURL('app','Registro_Fallas_Sistema','user','IngresarRegistroFallaSistema',array('registro_id'=>$_REQUEST['registros']['registro_id']));
		
		$this->salida.= ThemeAbrirTabla('REGISTRO DE FALLAS DEL SISTEMA');
		
		$registros=$_REQUEST['registros'];
		
		if(!empty($registros) && !empty($_REQUEST['editar']))
		{
			unset($_SESSION['error']);
			
			$fechaI=$registros['fecha_ocurrio'];
			$horaI=$registros['fecha_ocurrio'];
			
			$_SESSION['registro']=$registros['registro_id'];
			
			$fecha=substr($fechaI,0,10);
			$hora=substr($horaI,11,18);
			
			$fecha=$this->FechaStamp($fecha);	
		} 
		else
		{	
			$fecha = date("d-m-Y");
			$hora = date('h:i:s');
			$registros['descripcion']="";
			$registros['tipo_falla_id']="";
		}
		
		if(empty($_REQUEST['Nuevo']))
		{
			if($_SESSION['error']==1)
			{
				$this->salida.=" <center><label class=\"label\">LA FECHA, HORA,TIPO DE FALLA O DESCRIPCION NO HA SIDO INGRESADA </label></center><br>";
			}
			else if($_REQUEST['Insertar'])
			{
				$fecha = $this->FechaStamp($fecha_ocurrio);
				$hora = $hora_ocurrio;			
				$registros['tipo_falla_id']=$tipo_falla;
				$registros['descripcion']=$descripcion;
				$this->salida.=" <center><label class=\"label\"> EL REGISTRO SE HA INGRESADO EXISTOSAMENTE </label></center><br>";
				
			}
			$_SESSION['error']=0;
		}
		
		$this->salida.="<form name=\"forma_registro\" method=\"POST\" action=\"$action\">";
		
		$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"45%\">";
		
		$this->salida.=" <tr>";
		$this->salida.="  <td class=\"modulo_table_list_title\">Fecha: </td>";
		$this->salida.="  <td class=\"modulo_list_claro\"><input type=\"text\" class=\"input-text\" name=\"fecha_ocurrio\" value=\"".$fecha."\"><sub>".ReturnOpenCalendario("forma_registro","fecha_ocurrio","-")."</sub></td>";
		$this->salida.=" </tr>";
		$this->salida.=" <tr>";
		$this->salida.="  <td class=\"modulo_table_list_title\"> Hora: </td> <td  class=\"modulo_list_claro\"> <input type=\"text\" class=\"input-text\" name=\"hora\" value=\"".$hora."\"> [hh:mm:ss] </td>";
		$this->salida.=" </tr>";
		
		$this->salida.="<tr>";
		$this->salida.=" <td class=\"modulo_table_list_title\">Tipo de Falla: </td>";
		$this->salida.=" <td colspan=\"2\" class=\"modulo_list_claro\">";
		$this->salida.="  <select name=\"tipo_falla\" class=\"select\">";
		$this->salida.="   <option value=\"\">--TIPO DE FALLA--</option>";
		
		$tipo_f=$this->LlamarTiposFallas();

		foreach($tipo_f as $tiposfs)
			if($registros['tipo_falla_id']==$tiposfs['tipo_falla_id'])
				$this->salida.="   <option value=\"".$tiposfs['tipo_falla_id']."\" selected>".$tiposfs['tipo_falla']."</option>";
			else
				$this->salida.="   <option value=\"".$tiposfs['tipo_falla_id']."\">".$tiposfs['tipo_falla']."</option>";	
		
     		$this->salida.="  </select>";
		$this->salida.=" </td>";
		$this->salida.="</tr>";
		
		$this->salida.=" <tr>";
		$this->salida.="  <td class=\"modulo_table_list_title\">Descripcion: </td>";
		$this->salida.="  <td colspan=\"2\" class=\"modulo_list_claro\"><textarea class=\"textarea\" name=\"descripcion\" cols=\"60\" rows=\"4\">".$registros['descripcion']."</textarea></td>";
		$this->salida.=" </tr>";
		
		$this->salida.="</table>";
		$this->salida.="<br>";
		$this->salida.=" <table align=\"center\">";
		$this->salida.="  <tr>";
		$this->salida.="   <td><input type=\"submit\" class=\"input-submit\" name=\"Insertar\" value=\"INSERTAR\"></td>";
		
		if($_REQUEST['Nuevo'])
		{
			$this->salida.="   <input type=\"hidden\" name=\"editar\" value=\"\">";
			$_REQUEST['Nuevo']="";
		}
		else
		{
			$this->salida.="   <input type=\"hidden\" name=\"editar\" value=\"".$_REQUEST['editar']."\">";
		}
		$this->salida.="   <td><input type=\"submit\" class=\"input-submit\" name=\"Nuevo\" value=\"NUEVO\"></td>";
		$this->salida.="</form>";
		
		$action1 = ModuloGetURL('system','Menu','user','main');
		$this->salida.=" <form name=\"formavolver\" method=\"POST\" action=\"$action1\">";
		$this->salida.="   <td><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td>";
		$this->salida.="  </tr>";
		$this->salida.=" </form>";
		$this->salida.=" </table>";
		
		$this->salida.=" <br>";
		
		$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"100%\">";
		$this->salida.=" <tr class=\"modulo_table_list_title\">";
		$this->salida.="  <td> # REGISTRO </td>";
		$this->salida.="  <td> TIPO DE FALLA </td>";
		$this->salida.="  <td> FECHA </td>";
		$this->salida.="  <td> DESCRIPCION </td>";
		$this->salida.="  <td colspan=\"2\"> ACCION </td>";
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

		$k=0;
		
		$reg=$this->ListarRegistros();
		
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
			
			$this->salida.="	<tr align=\"center\" class=\"$estilo\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
			$this->salida.="  <td width=\"10%\" align=\"center\">". $registros['registro_id']."</td>";
			$this->salida.="  <td width=\"10%\" align=\"center\">". $registros['tipo_falla']."</td>";
			$this->salida.="  <td width=\"15%\" align=\"center\">". $registros['fecha_registro']."</td>";
			$this->salida.="  <td width=\"45%\" align=\"left\">". $registros['descripcion'] ."</td>";
			
			$action = 
			ModuloGetURL('app','Registro_Fallas_Sistema','user','FormaRegistro',array('editar'=>'editar','registros'=>$registros));
			$this->salida.="  <td width=\"10%\"> <img src=\"".GetThemePath()."/images/editar.png\"><a href=\"$action\"> EDITAR </a></td>";
			$action = ModuloGetURL('app','Registro_Fallas_Sistema','user','EliminarRegistro',array('eliminar'=>'eliminar','registros'=>$registros));
			$this->salida.="  <td width=\"10%\"> <img src=\"".GetThemePath()."/images/elimina.png\"><a href=\"$action\"> ELIMINAR </a></td>";
			$this->salida.=" </tr>";
			
			$k++;
		}
		
		$this->salida.="</table>";
		
		$this->salida.="<br>";
		
		$Paginador=new ClaseHTML();
		
		$action = ModuloGetURL('app','Registro_Fallas_Sistema','user','FormaRegistro');
	
		$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$action);
		
		$this->salida.=ThemecerrarTabla();
		
		return true;
     	}
}//fin de la clase

?>

