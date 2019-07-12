<?php

/**
* $Id: app_HonorariosMedicos_userclasses_HTML.php,v 1.10 2007/04/18 19:17:07 luis Exp $
* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @author  luis alejandro vargas
* @package IPSOFT-SIIS
*/
IncludeClass("ClaseHTML");

class app_HonorariosMedicos_userclasses_HTML extends app_HonorariosMedicos_user
{
	function app_HonorariosMedicos_userclasses_HTML()
	{
		$this->app_HonorariosMedicos_user();
		return true;
	}
	
	function FormaConsultaProfesionales()
	{
		$permisos=$this->PermisosUsuarios();
		
		$this->salida .= "	<script>";
		$this->salida .= "		function mOvr(src,clrOver)";
		$this->salida .= "		{";
		$this->salida .= "			src.style.background = clrOver;";
		$this->salida .= "		}";
		$this->salida .= "		function mOut(src,clrIn)";
		$this->salida .= "		{";
		$this->salida .= "			src.style.background = clrIn;";
		$this->salida .= "		}";
		$this->salida .= "	</script>";
		
		if($permisos)
		{
			$this->salida.= ThemeAbrirTabla('HONORARIOS MEDICOS');
			
			$actionP = ModuloGetURL('app','HonorariosMedicos','user','FormaConsultaProfesionales');
				
			$this->salida.="<form name=\"formaVH\" action=\"$actionP\" method=\"post\">";
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"55%\">";
			$this->salida.="	<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="		<td width=\"10%\" colspan=\"4\"> BUSCADOR </td>";
			$this->salida.="	</tr>"; 
			$this->salida.="	<tr aling=\"center\" class=\"modulo_list_oscuro\">";
			$this->salida.="		<td width=\"10%\"> SELECCIONE </td>";
			$this->salida.="		<td width=\"20%\">";
			$this->salida.="			<select name=\"tipo_pro\" class=\"select\">";
			$sel1="";$sel2="";$sel3="";
			if(!empty($_REQUEST['busqueda']))
			{
				switch($_REQUEST['tipo_pro'])
				{
					case 1:
						$sel1="selected";
					break;
					
					case 2:
						$sel2="selected";
					break;
					
					case 3:
						$sel3="selected";
					break;
				}
			}
			else
				$sel2="selected";
				
			$this->salida.="				<option value=\"1\" $sel1>Uid</option>";
			$this->salida.="				<option value=\"2\" $sel2>Login</option>";
			$this->salida.="				<option value=\"3\" $sel3>Nombre</option>";
			$this->salida.="			</select>";
			$this->salida.="		</td>";
			$this->salida.="		<td width=\"70%\"> <input type=\"text\" name=\"busqueda\" class=\"input-text\" value=\"".$_REQUEST['busqueda']."\" size=\"50\"></td>";
			$this->salida.="		<td align=\"center\"><input type=\"submit\" name=\"buscar\" value=\"BUSCAR\" class=\"input-submit\"></td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.="</form><br>";
			
			$usuarios=$this->BuscarProfesionales($_REQUEST['tipo_pro'],$_REQUEST['busqueda']);
			
			if($usuarios)
			{
				$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"55%\">";
				$this->salida.="	<tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida.="		<td width=\"10\"> UID </td>";
				$this->salida.="		<td width=\"20\"> USUARIO </td>";
				$this->salida.="		<td width=\"50\"> IDENTIFICACION </td>";
				$this->salida.="		<td width=\"50\"> NOMBRE </td>";
				$this->salida.="		<td width=\"5\"> HONORARIOS </td>";
				$this->salida.="	</tr>";
				
				
				$k=0;
				foreach($usuarios as $usuario)
				{
					if($k%2==0)
					{
						$estilo='modulo_list_oscuro';
						$background = "#CCCCCC";
					}
					else
					{
						$estilo='modulo_list_claro';
						$background = "#DDDDDD";
					}
					$action = ModuloGetURL('app','HonorariosMedicos','user','FormaConsultarPorFecha',array('usuario_id'=>$usuario['usuario_id'],'tipo_pro'=>$_REQUEST['tipo_pro'],'busqueda'=>$_REQUEST['busqueda']));
					
					$this->salida.="	<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
					$this->salida.="		<td><b>".$usuario['usuario_id']."</b></td>";
					$this->salida.="		<td><b>".$usuario['usuario']."</b></td>";
					$this->salida.="		<td><b>".$usuario['tipo_id_tercero']."-".$usuario['tercero_id']."</b></td>";
					$this->salida.="		<td><b>".$usuario['nombre_profesional']."</b></td>";
					$this->salida.="		<td><a href=\"$action\"><img src=\"".GetThemePath()."/images/honorarios.png\" border=\"0\"></a></td>";
					$this->salida.="	</tr>";
					
					$k++;
				}
				$this->salida.="</table><br>";
			}
			else
			{
				$this->salida.="		<center><img src=\"".GetThemepath()."/images/informacion.png\"> <label class=\"label_error\">NINGUN REGISTRO ENCONTRADO</label></center>";
			}
			
			$action = ModuloGetURL('system','Menu','user','main');
			
			if(!empty($_SESSION['evolucion']))
			{
				$action =ModuloHCGetURL($_SESSION['evolucion'],'cerrar',0,$_SESSION['mod'],$_SESSION['mod']);
			}
			
			$Paginador=new ClaseHTML();
			$accion = ModuloGetURL('app','HonorariosMedicos','user','FormaConsultaProfesionales');
			$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$accion,$this->limit);
				
			$this->salida.="<br><form name=\"formavolver\" method=\"POST\" action=\"$action\">";
			$this->salida.="<table align=\"center\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";	
			$this->salida.=ThemecerrarTabla();
		}
		else
		{
			$this->FormaConsultarPorFecha();
		}
		
		
		
		return true;
	}
     
	/********************************************************************************* 
	* Forma que muestra los datos del usuario y la fecha que se desea consultar
	* 
	* @return boolean  
	**********************************************************************************/
	
	function FormaConsultarPorFecha()
	{
		$usuario_id=$_REQUEST['usuario_id'];
		$tipo_pro=$_REQUEST['tipo_pro'];
		$busqueda=$_REQUEST['busqueda'];
		
		$usuarios=$this->BuscarInformacionUsuario($usuario_id);
		$this->salida.= ThemeAbrirTabla('CONTROL DE HONORARIOS');

		foreach($usuarios as $usuario)
		{
			$action = ModuloGetURL('app','HonorariosMedicos','user','FormaMostrarHonorarios',array('tipo_id_tercero'=>$usuario['tipo_tercero_id'],'tercero_id'=>$usuario['tercero_id'],'nombre'=>$usuario['nombre'],'usuario_id'=>$usuario_id,'tipo_pro'=>$tipo_pro,'busqueda'=>$busqueda));
			if($_REQUEST['fecha_ini'] AND $_REQUEST['fecha_fin'])
			{
				$fecha_ini=$_REQUEST['fecha_ini'];
				$fecha_fin=$_REQUEST['fecha_fin'];
			}
			else
			{
				$fecha_ini=date("d-m-Y");
				$fecha_fin=date("d-m-Y");
			}
			$this->salida .= "	<script language=\"javascript\">";
			$this->salida .= "		function validar_fecha()";
			$this->salida .= "		{";
			$this->salida .= "			
								var bandera=false;
								if(document.formabusqueda.fecha.value> document.formabusqueda.fecha_actual.value)
								{
									alert('La fecha ingresada es superior a la fecha actual');
									bandera=true;
								}
								
								if(bandera==false)
									document.formabusqueda.submit();";
			$this->salida .= "		}";
			$this->salida .= "	</script>";

			$this->salida.="<form name=\"formabusqueda\" method=\"POST\" action=\"$action\">";
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"50%\">";
			$this->salida.="	<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="		<td colspan=\"2\"> BUSCAR HONORARIOS </td>";
			$this->salida.="	</tr>"; 
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			$this->salida.="		<td align=\"left\"> UID </td>";
			$this->salida.="		<td class=\"modulo_list_claro\" align=\"left\">".$usuario['usuario_id']."</td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			$this->salida.="		<td align=\"left\"> USUARIO </td>";
			$this->salida.="		<td class=\"modulo_list_claro\" align=\"left\">".$usuario['usuario']."</td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			$this->salida.="		<td align=\"left\"> NOMBRE </td>";
			$this->salida.="		<td class=\"modulo_list_claro\" align=\"left\">".$usuario['nombre']."</td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			$this->salida.="		<td align=\"left\"> DESCRIPCION </td>";
			$this->salida.="		<td class=\"modulo_list_claro\" align=\"left\">".$usuario['descripcion']."</td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"modulo_table_list_title\">";
			$this->salida.="		<td align=\"left\"> FECHA </td>";
			$this->salida.="		<td class=\"modulo_list_claro\" align=\"left\">";
			$this->salida.="			DE <input type=\"text\" class=\"input-text\" name=\"fecha_ini\" value=\"".$fecha_ini."\" readonly size=\"10\"><sub>".ReturnOpenCalendario("formabusqueda","fecha_ini","-")."</sub>";
			$this->salida.="			&nbsp; A &nbsp;<input type=\"text\" class=\"input-text\" name=\"fecha_fin\" value=\"".$fecha_fin."\" readonly size=\"10\"><sub>".ReturnOpenCalendario("formabusqueda","fecha_fin","-")."</sub>";
			$this->salida.="		</td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.="<br>";
			$this->salida.="<input type=\"hidden\" name=\"fecha_actual\" value=\"".date("d-m-Y")."\">";
			$this->salida.="<table align=\"center\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td><input type=\"submit\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";/*onclick=\"validar_fecha()\"*/
			$this->salida.="</form>";
			
			$action = ModuloGetURL('app','HonorariosMedicos','user','FormaConsultaProfesionales',array('usuario_id'=>$usuario_id,'tipo_pro'=>$tipo_pro,'busqueda'=>$busqueda));
			
			if(!$usuario_id)
			{
				$action = ModuloGetURL('system','Menu','user','main');
				
				if(!empty($_SESSION['evolucion']))
				{
					$action =ModuloHCGetURL($_SESSION['evolucion'],'cerrar',0,$_SESSION['mod'],$_SESSION['mod']);
				}
			}
			
			$this->salida.="<form name=\"formavolver\" method=\"POST\" action=\"$action\">";
			$this->salida.="		<td><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></td>";
			$this->salida.="	</tr>";
			$this->salida.="</form>";
			$this->salida.="</table>";
			
		}
		
		$this->salida.=ThemecerrarTabla();
		
		return true;
	}
     
  /********************************************************************************* 
	* funcion que permite mostrar la informaciuon de las estaciones de enfermeria en pantalla
	* 
	* @return boolean  
	**********************************************************************************/
     
  function FormaMostrarHonorarios()
	{
		$tipo_id_profesional=$_REQUEST['tipo_id_tercero'];
		$profesional_id=$_REQUEST['tercero_id'];
		$fecha_ini=$_REQUEST['fecha_ini'];
		$fecha_fin=$_REQUEST['fecha_fin'];
		
		$usuario_id=$_REQUEST['usuario_id'];
		$tipo_pro=$_REQUEST['tipo_pro'];
		$busqueda=$_REQUEST['busqueda'];

		$fecha_iniI=$this->FechaStamp($fecha_ini);
		$fecha_finF=$this->FechaStamp($fecha_fin);

		$permiso=$this->consulta_permiso_fecha();
		
		if($permiso[0]['sw_consulta_todos']==0 && $fecha_iniI<$permiso[0]['fecha_consulta'])
		{			
			$listado ='NO';
		}
		else
		{
			$listado ='SI';
		}
		
		if($listado=='SI')
		{
			$listado=$this->MostrarHonorarios($tipo_id_profesional,$profesional_id,$fecha_iniI,$fecha_finF);
			
			$this->salida.= ThemeAbrirTabla('HONORARIOS','1200');
			
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"35%\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td class=\"modulo_table_list_title\"> FECHA : </td>";
			$this->salida.="		<td class=\"modulo_list_claro\"> <b>DESDE</b> $fecha_iniI <b>HASTA</b> $fecha_finF </td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.="<br>";
			
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"100%\">";
			$this->salida.="	<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="		<td width=\"5%\">VOUCHER</td>";
			$this->salida.="		<td width=\"5%\">TRANSACCION</td>";
			$this->salida.="		<td width=\"10%\">CARGO</td>";
			$this->salida.="		<td width=\"5%\">NUMERO CUENTA</td>";
			
			$this->salida.="		<td width=\"10%\">PACIENTE</td>";
			$this->salida.="		<td width=\"5%\">FACTURA</td>";
			$this->salida.="		<td width=\"10%\">FECHA RADICACION</td>";
			$this->salida.="		<td width=\"5%\">NUMERO RECIBO</td>";
			$this->salida.="		<td width=\"5%\">PLAN</td>";
			
			$this->salida.="		<td width=\"10%\">VALOR ATENCION</td>";
			$this->salida.="		<td width=\"5%\">PORCETAJE</td>";
			$this->salida.="		<td width=\"5%\">VALOR NOTA CREDITO</td>";
			$this->salida.="		<td width=\"5%\">VALOR NOTA DEBITO</td>";
			$this->salida.="		<td width=\"10%\">VALOR ACTUAL HONORARIO</td>";
			$this->salida.="		<td width=\"5%\">ACCION</td>";	
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

			$k=0;
			$i=0;
			$reporte= new GetReports();
			
			foreach($listado as $lista)
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
				$this->salida.="		<td>". $lista['prefijo']."-".$lista['numero']."</td>";
				$this->salida.="		<td>". $lista['transaccion']."</td>";
				$this->salida.="		<td>". strtoupper($lista['desc_cargo'])."</td>";
				$this->salida.="		<td>". $lista['numerodecuenta']."</td>";
				
				$this->salida.="		<td>". $lista['nombre_paciente']."</td>";
				$this->salida.="		<td>". $lista['numero_factura_id']."</td>";
				$this->salida.="		<td>". $lista['fecha_rad']."</td>";
				$this->salida.="		<td>". $lista['numero_recibo']."</td>";
				$this->salida.="		<td>". $lista['plan_descripcion']."</td>";
				
				$this->salida.="		<td>$ ". $lista['valor_cargo']."</td>";
				$this->salida.="		<td>". ($lista['porcentaje_liquidacion'])." %</td>";

				if($lista['valor_nc']>0)
					$this->salida.="		<td>$ ".$lista['valor_nc']."</td>";
				else
					$this->salida.="		<td>$ 0.00</td>";
				
				if($lista['valor_nd']>0)
					$this->salida.="		<td>$ ". $lista['valor_nd']."</td>";
				else
					$this->salida.="		<td>$ 0.00</td>";
				
				if($lista['valor_honorario']==$lista['valor_real'])
					$this->salida.="		<td>$ ".$lista['valor_honorario']."</td>";
				else
					$this->salida.="		<td>$ ". $lista['valor_real']."</td>";
				
				$mostrar=$reporte->GetJavaReport('app','HonorariosMedicos','ReporteHonorariosMedicos',array('lista'=>$lista,'T'=>'0'),array('rpt_name'=>'ReporteHonorarios','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte->GetJavaFunction();

				$this->salida.="		<td> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> <label class=\"label\"><a href=\"javascript:$funcion\"> IMPRIMIR </a></label></td>";	
				$this->salida.="	</tr>";
				
				$this->salida.= "$mostrar";
				
				$k++;
			}

			$_SESSION['listado']=$listado;

			$mostrarT=$reporte->GetJavaReport('app','HonorariosMedicos','ReporteHonorariosMedicos',array('fecha'=>$fecha,'T'=>'1'),array('rpt_name'=>'ReporteHonorarios','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcionT=$reporte->GetJavaFunction();
			
			$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="		<td colspan=\"15\" align=\"right\"><a href=\"javascript:$funcionT\"> IMPRIMIR TODAS </a> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> </td>";	
			$this->salida.="	</tr>";
			
			$this->salida .= "$mostrarT";
			
			$this->salida.="</table>";
		}
		else if(!$listado)
		{
			$this->salida.="		<center><img src=\"".GetThemepath()."/images/informacion.png\"> <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA ESTA FECHA</label></center>";
		}
		else if($listado=='NO')
			{
	                $fechaI=$permiso[0]['fecha_consulta'];
			
			$listado=$this->MostrarHonorarios($tipo_id_profesional,$profesional_id,$fechaI,$fecha_finF);
			
			$this->salida.= ThemeAbrirTabla('HONORARIOS','1200');
			
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"35%\">";
			$this->salida.="	<tr>";
			$this->salida.="		<td class=\"modulo_table_list_title\"> FECHA : </td>";
			$this->salida.="		<td class=\"modulo_list_claro\"> <b>DESDE</b> $fecha_iniI <b>HASTA</b> $fecha_finF </td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			$this->salida.="<br>";
			
			$this->salida.="<table class=\"modulo_table_list\" align=\"center\" width=\"100%\">";
			$this->salida.="	<tr class=\"modulo_table_list_title\" align=\"center\">";
			$this->salida.="		<td width=\"5%\">VOUCHER</td>";
			$this->salida.="		<td width=\"5%\">TRANSACCION</td>";
			$this->salida.="		<td width=\"10%\">CARGO</td>";
			$this->salida.="		<td width=\"5%\">NUMERO CUENTA</td>";
			
			$this->salida.="		<td width=\"10%\">PACIENTE</td>";
			$this->salida.="		<td width=\"5%\">FACTURA</td>";
			$this->salida.="		<td width=\"10%\">FECHA RADICACION</td>";
			$this->salida.="		<td width=\"5%\">NUMERO RECIBO</td>";
			$this->salida.="		<td width=\"5%\">PLAN</td>";
			
			$this->salida.="		<td width=\"10%\">VALOR ATENCION</td>";
			$this->salida.="		<td width=\"5%\">PORCETAJE</td>";
			$this->salida.="		<td width=\"5%\">VALOR NOTA CREDITO</td>";
			$this->salida.="		<td width=\"5%\">VALOR NOTA DEBITO</td>";
			$this->salida.="		<td width=\"10%\">VALOR ACTUAL HONORARIO</td>";
			$this->salida.="		<td width=\"5%\">ACCION</td>";	
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

			$k=0;
			$i=0;
			$reporte= new GetReports();
			
			foreach($listado as $lista)
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
				$this->salida.="		<td>". $lista['prefijo']."-".$lista['numero']."</td>";
				$this->salida.="		<td>". $lista['transaccion']."</td>";
				$this->salida.="		<td>". strtoupper($lista['desc_cargo'])."</td>";
				$this->salida.="		<td>". $lista['numerodecuenta']."</td>";
				
				$this->salida.="		<td>". $lista['nombre_paciente']."</td>";
				$this->salida.="		<td>". $lista['numero_factura_id']."</td>";
				$this->salida.="		<td>". $lista['fecha_rad']."</td>";
				$this->salida.="		<td>". $lista['numero_recibo']."</td>";
				$this->salida.="		<td>". $lista['plan_descripcion']."</td>";
				
				$this->salida.="		<td>$ ". $lista['valor_cargo']."</td>";
				$this->salida.="		<td>". ($lista['porcentaje_liquidacion'])." %</td>";

				if($lista['valor_nc']>0)
					$this->salida.="		<td>$ ".$lista['valor_nc']."</td>";
				else
					$this->salida.="		<td>$ 0.00</td>";
				
				if($lista['valor_nd']>0)
					$this->salida.="		<td>$ ". $lista['valor_nd']."</td>";
				else
					$this->salida.="		<td>$ 0.00</td>";
				
				if($lista['valor_honorario']==$lista['valor_real'])
					$this->salida.="		<td>$ ".$lista['valor_honorario']."</td>";
				else
					$this->salida.="		<td>$ ". $lista['valor_real']."</td>";
				
				$mostrar=$reporte->GetJavaReport('app','HonorariosMedicos','ReporteHonorariosMedicos',array('lista'=>$lista,'T'=>'0'),array('rpt_name'=>'ReporteHonorarios','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte->GetJavaFunction();

				$this->salida.="		<td> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> <label class=\"label\"><a href=\"javascript:$funcion\"> IMPRIMIR </a></label></td>";	
				$this->salida.="	</tr>";
				
				$this->salida.= "$mostrar";
				
				$k++;
			}

			$_SESSION['listado']=$listado;

			$mostrarT=$reporte->GetJavaReport('app','HonorariosMedicos','ReporteHonorariosMedicos',array('fecha'=>$fecha,'T'=>'1'),array('rpt_name'=>'ReporteHonorarios','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$funcionT=$reporte->GetJavaFunction();
			
			$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="		<td colspan=\"15\" align=\"right\"><a href=\"javascript:$funcionT\"> IMPRIMIR TODAS </a> <IMG src=\"".GetThemePath()."/images/imprimir.png\"> </td>";	
			$this->salida.="	</tr>";
			
			$this->salida .= "$mostrarT";
			
			$this->salida.="</table>";
		}
		
		$this->salida.="<br>";
		$action = ModuloGetURL('app','HonorariosMedicos','user','FormaConsultarPorFecha',array('fecha_ini'=>$fecha_ini,'fecha_fin'=>$fecha_fin,'usuario_id'=>$usuario_id,'tipo_pro'=>$tipo_pro,'busqueda'=>$busqueda));
		$this->salida.="<form name=\"formavolver2\" method=\"POST\" action=\"$action\">";
		$this->salida.="		<center><input type=\"submit\" class=\"input-submit\" name=\"Volver\" value=\"VOLVER\"></center>";
		$this->salida.="</form>";
		
		$this->salida.=ThemecerrarTabla();
	
		return true;
	}
}//fin de la clase

?>

