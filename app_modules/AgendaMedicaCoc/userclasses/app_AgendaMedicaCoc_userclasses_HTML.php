<?php

/**
 * app_AgendaMedicaCoc_userclasses_HTML.php
 * $Id: app_AgendaMedicaCoc_userclasses_HTML.php,v 1.1 2009/09/02 13:08:12 hugo Exp $

 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de   * consulta de los datos
 */

class app_AgendaMedicaCoc_userclasses_HTML extends app_AgendaMedicaCoc_user
{



/**
* Esta funcion Inicializa las variable de la clase e instancia la clase user de Agenda Medica
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function app_AgendaMedicaCoc_user_HTML()
	{
		$this->app_AgendaMedicaCoc_user(); //Constructor del padre 'modulo' 
		$this->salida='';
		return true;
	}




/**
* Esta funcion muestra el menu para la eleccion del usuario cual funcion va ha realizar asignar, cumplir, atender y cancelar
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function Menu()
	{
		
		
		$asigncioncancelacion=$this->BusquedaAsignacionCancelacion();
		$cumplimiento=$this->BusquedaCumplimiento();
		$atencion=$this->BusquedaAtencionCitas();
		$this->salida = ThemeAbrirTabla('CONSULTA EXTERNA');
		$this->salida .= "<br>";
		$this->salida .= "<center><label class=\"label_error\">".$this->frmError["MensajeError"]."</label></center>";
		$this->salida .= "<br>";
		$this->salida .= "<table width=\"40%\" border=\"1\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td align=\"center\" class=\"modulo_table_list_title\">MENU DE CONSULTA EXTERNA</td>";
		$this->salida .= "</tr>";
		$spy=0;
		if($asigncioncancelacion>0)
		{
			if($spy==0)
			{
				$this->salida .='<tr class="modulo_list_claro">';
				$spy=1;
			}
			else
			{
				$this->salida .='<tr class="modulo_list_oscuro">';
				$spy=0;
			}
			$this->salida .='<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','user','AsignarCitas');
			$this->salida .='<a href="'.$accion.'">Asignaci??? de Citas</a>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
		}
		if($cumplimiento>0)
		{
			if($spy==0)
			{
				$this->salida .='<tr class="modulo_list_claro">';
				$spy=1;
			}
			else
			{
				$this->salida .='<tr class="modulo_list_oscuro">';
				$spy=0;
			}
			$this->salida .='<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','user','CumplimientoCita');
			$this->salida .='<a href="'.$accion.'">Cumplimiento de Citas</a>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
		}
		if($atencion>0)
		{
			if($spy==0)
			{
				$this->salida .='<tr class="modulo_list_claro">';
				$spy=1;
			}
			else
			{
				$this->salida .='<tr class="modulo_list_oscuro">';
				$spy=0;
			}
			$this->salida .='<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','user','AtenderCita');
			$this->salida .='<a href="'.$accion.'">Atenci??? de Citas</a>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
		}
		else
		{
				$this->salida .='<tr class="modulo_list_oscuro">';
				$this->salida .='<td align="center" class="label_error">NO TIENE AGENDA CREADA</td>';
				$this->salida .='</tr>';
		}
		if($asigncioncancelacion>0)
		{
			if($spy==0)
			{
				$this->salida .='<tr class="modulo_list_claro">';
				$spy=1;
			}
			else
			{
				$this->salida .='<tr class="modulo_list_oscuro">';
				$spy=0;
			}
			$this->salida .='<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','user','CancelarCitas');
			$this->salida .='<a href="'.$accion.'">Cancelaci??? de Citas</a>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
		}
		$this->salida .='</table>';
		$this->salida .='<br>';
		$this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
		$this->salida .='<tr>';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('system','Menu','user','main');
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		//$this->salida .='<a href="'.$accion.'">Volver</a>';
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
		$this->salida .="</td>";
		$this->salida .="</tr>";
		$this->salida .=" </form>";
		$this->salida .="</table>";
		$this->salida .="<br>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}





// 	function LiquidarCitas()
// 	{
// 		unset($_SESSION['LiquidarCitas']);
// 		unset($_SESSION['AsignacionCitas']);
// 		unset($_SESSION['CumplirCita']);
// 		$url[0]='app';
// 		$url[1]='AgendaMedicaCoc';
// 		$url[2]='user';
// 		$url[3]='DatosPaciente';
// 		$url[4]='LiquidarCitas';
// 		if($this->TipoConsulta($url)==false)
// 		{
// 			return false;
// 		}
// 		return true;
// 	}




/**
* Esta funcion muestra los derechos que tiene el usuario para asignar citas
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function AsignarCitas()
	{
		unset($_SESSION['AsignacionCitas']);
		unset($_SESSION['LiquidarCitas']);
		unset($_SESSION['CumplirCita']);
		SessionDelVar('CITASMES');
		$url[0]='app';
		$url[1]='AgendaMedicaCoc';
		$url[2]='user';
		$url[3]='DatosPaciente';
		$url[4]='Citas';
		if($this->TipoConsulta($url)==false)
		{
			return false;
		}
		return true;
	}



/**
* Esta funcion lista las posibles ordenes que tenga un paciente
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function PantallaOrdenes()
	{
		unset($_SESSION['AsignacionCitas']['ORDENES'][$_SESSION['AsignacionCitas']['NumeroOrden']]);
		unset($_SESSION['AsignacionCitas']['NumeroOrden']);
		unset($_SESSION['AsignacionCitas']['idcitas']);
		if(!empty($_SESSION['AsignacionCitas']['PrimerNombre']) or !empty($_SESSION['AsignacionCitas']['SegundoNombre']) or !empty($_SESSION['AsignacionCitas']['PrimerApellido']) or !empty($_SESSION['AsignacionCitas']['SegundoApellido']))
		{
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre']=$_SESSION['AsignacionCitas']['PrimerNombre'];
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre']=$_SESSION['AsignacionCitas']['SegundoNombre'];
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido']=$_SESSION['AsignacionCitas']['PrimerApellido'];
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido']=$_SESSION['AsignacionCitas']['SegundoApellido'];
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['residencia_telefono']=$_SESSION['AsignacionCitas']['Telefono'];
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['sexo_id']=$_SESSION['AsignacionCitas']['Sexo'];
			$_SESSION['AsignacionCitas']['DATOSPACIENTE']['residencia_direccion']=$_SESSION['AsignacionCitas']['Direccion'];
		}
		$this->salida = ThemeAbrirTabla('ORDENES DE SERVICIO PARA ASIGNAR CITA');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['AsignacionCitas']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['AsignacionCitas']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Identificaci???";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Nombre Paciente";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .=$_SESSION['AsignacionCitas']['Documento'].' - '.$_SESSION['AsignacionCitas']['TipoDocumento'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre']) and !empty($_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido']))
		{
			$nom=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido'];
		}
		else
		{
			if(empty($_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre']))
			{
				if(empty($_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido']))
				{
					$nom=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido'];
				}
				else
				{
					$nom=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido'];
				}
			}
			else
			{
				if(empty($_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_apellido']))
				{
					$nom=$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_nombre'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['segundo_nombre'].' '.$_SESSION['AsignacionCitas']['DATOSPACIENTE']['primer_apellido'];
				}
			}
		}
		$this->salida .= $nom;
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida.="<table align=\"center\" class=\"modulo_table_list\">";
		$this->salida.="<tr align=\"center\" class=\"modulo_table_title\">";
		$this->salida.="<td>";
		$this->salida.="Orden de Servicio";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="Numero de Orden";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="Fecha de Activaci???";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="Fecha de Vencimiento";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="Estado";
		$this->salida.="</td>";
		$this->salida.="<td>";
		$this->salida.="Accion";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		foreach($_SESSION['AsignacionCitas']['ORDENES'] as $k=>$v)
		{
			if($spy==0)
			{
				$this->salida .= "<tr class=\"modulo_list_oscuro\">";
				$spy=1;
			}
			else
			{
				$this->salida .= "<tr class=\"modulo_list_claro\">";
				$spy=0;
			}
			$this->salida.="<td>";
			$this->salida.=$v[orden_servicio_id];
			$this->salida.="</td>";
			$this->salida.="<td>";
			$this->salida.=$v[numero_orden_id];
			$this->salida.="</td>";
			$this->salida.="<td>";
			$dat=explode(' ',$v[fecha_activacion]);
			$this->salida.=$dat[0];
			$this->salida.="</td>";
			$this->salida.="<td>";
			$dat=explode(' ',$v[fecha_vencimiento]);
			$this->salida.=$dat[0];
			$this->salida.="</td>";
			$a=explode('-',$v['fecha_activacion']);
			$b=explode(' ',$a[2]);
			$a1=explode('-',$v['fecha_vencimiento']);
			$b1=explode(' ',$a1[2]);
			if(date("Y-m-d",mktime(1,1,1,$a[1],$b[0],$a[0]))<=date("Y-m-d") and date("Y-m-d",mktime(1,1,1,$a1[1],$b1[0],$a1[0]))>=date("Y-m-d"))
			{
				$this->salida.="<td>";
				$this->salida.="Activo";
				$this->salida.="</td>";
				$this->salida.="<td>";
				$accion=ModuloGetURL('app','AgendaMedicaCoc','user','PacienteOrdenServicio',array('numero_orden_id'=>$v[numero_orden_id],'tipo_afiliado_id'=>$v['tipo_afiliado_id'],'rango'=>$v['rango'],'semanas_cotizadas'=>$v['semanas_cotizadas'], 'autorizacion_int'=>$v['autorizacion_int']));
				$this->salida.="<a href=\"$accion\">Asignar Cita</a>";
				$this->salida.="</td>";
			}
			else
			{
				$this->salida.="<td>";
				$this->salida.="Inactivo";
				$this->salida.="</td>";
				$this->salida.="<td>";
				$this->salida.="</td>";
			}
			$this->salida.="</tr>";
		}
		if($spy==0)
		{
			$this->salida .= "<tr class=\"modulo_list_oscuro\">";
			$spy=1;
		}
		else
		{
			$this->salida .= "<tr class=\"modulo_list_claro\">";
			$spy=0;
		}
		$this->salida.="<td colspan=\"6\" align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','AutorizarPaciente');
		$this->salida.="<a href=\"$accion\">AUTORIZAR PACIENTE</a>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .='<br>';
		$this->salida .='<table border="0" align="center" width="50%">';
		$this->salida .='<tr>';
		$this->salida .='<td align="center">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','DatosPaciente');
		$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .='<input type="submit" name="volver" value="Volver" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




/**
* Esta funcion muestra un calendario con los diferentes posibles dias donde existe cita disponible, ademas de realizar un listado de los profesionales que tiene cita disponible y un listado de los turnos disponibles para asignar citas
*
* @access public
* @return boolean Para identificar que se realizo.
* @param int cita que se encuentra ocupada
*/

	function EscogerBusqueda($citaocupada)
	{
		SessionDelVar('CITASMES');
		SessionDelVar('CITASDIA');
		unset($_SESSION['AsignacionCitas']['hora']);
		$_SESSION['AsignacionCitas']['profesional']=$_REQUEST['profesional'];
		$_SESSION['AsignacionCitas']['nompro']=$_REQUEST['nompro'];
		$a=explode("-",$_REQUEST['DiaEspe']);
		if(empty($_REQUEST['DiaEspe']) or date("Y-m-d")>date("Y-m-d",mktime(0,0,0,$a[1],$a[2],$a[0])))
		{
			if(empty($_SESSION['CITASMES']))
			{
				$fechas=$this->DiasCitas();
				SessionSetVar('CITASMES',$fechas);
			}
			$this->salida = ThemeAbrirTabla('FILTRO PARA ASIGNACI??? DE CITA MEDICA');
			$this->salida .= "<BR>";
			$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "<BR>";
			$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Identificaci???";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Nombre Paciente";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .=$_SESSION['AsignacionCitas']['Documento'].' - '.$_SESSION['AsignacionCitas']['TipoDocumento'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			if(!empty($_SESSION['AsignacionCitas']['SegundoNombre']) and !empty($_SESSION['AsignacionCitas']['SegundoApellido']))
			{
				$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['SegundoNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'].' '.$_SESSION['AsignacionCitas']['SegundoApellido'];
			}
			else
			{
				if(empty($_SESSION['AsignacionCitas']['SegundoNombre']))
				{
					if(empty($_SESSION['AsignacionCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'];
					}
					else
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'].' '.$_SESSION['AsignacionCitas']['SegundoApellido'];
					}
				}
				else
				{
					if(empty($_SESSION['AsignacionCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['SegundoNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'];
					}
				}
			}
			$this->salida .= $nom;
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			if(!empty($_SESSION['AsignacionCitas']['idcitas']))
			{
				foreach($_SESSION['AsignacionCitas']['idcitas'] as $k=>$v)
				{ //unset($_SESSION['AsignacionCitas']['idcitas'][$k]);
					if(!is_array($_SESSION['AsignacionCitas']['idcitas'][$k]))
					{
						$_SESSION['AsignacionCitas']['idcitas'][$k]=$this->BuscarInformacionCita($k);
					}
				}
				$this->salida .= "<br>";
				$this->salida .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= '<tr align="center" class="modulo_table_title">';
				$this->salida .= "<td width=\"20%\">";
				$this->salida .= "Fecha Cita";
				$this->salida .= "</td>";
				$this->salida .= "<td width=\"20%\">";
				$this->salida .= "Nombre Profesional";
				$this->salida .= "</td>";
				$this->salida .= "<td width=\"20%\">";
				$this->salida .= "Estado";
				$this->salida .= "</td>";
				$this->salida .= "<td width=\"20%\">";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$AgendaIdAnt=-1;
				foreach($_SESSION['AsignacionCitas']['idcitas'] as $k=>$v){
				  if($v['agenda_cita_id_padre']!=$AgendaIdAnt){
					  $contAgenda=0;
						foreach($_SESSION['AsignacionCitas']['idcitas'] as $CitaIde=>$Datos){
							if($Datos['agenda_cita_id_padre']==$v['agenda_cita_id_padre']){
								$contAgenda++;
							}
						}
					}
					if($spy==0)
					{
						$this->salida .= "<tr class=\"modulo_list_oscuro\">";
						$spy=1;
					}
					else
					{
						$this->salida .= "<tr class=\"modulo_list_claro\">";
						$spy=0;
					}
					$this->salida .= "<td width=\"20%\">";
					$this->salida .= $v['fecha'];
					$this->salida .= "</td>";
					if($v['agenda_cita_id_padre']!=$AgendaIdAnt){
						$this->salida .= "<td rowspan=\"$contAgenda\" width=\"62%\">";
						$this->salida .= $v['nombre_tercero'];
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$contAgenda\" width=\"62%\">";
						if($v['sw_estado']==1){
							$this->salida .= "Activo";
						}elseif($v['sw_estado']==2){
							$this->salida .= "Paga";
						}elseif($v['sw_estado']==3){
							$this->salida .= "Cumplida";
						}
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$contAgenda\" width=\"62%\">";
						if($v['sw_estado']==1){
							$accion=ModuloGetURL('app','AgendaMedicaCoc','user','EliminarCitasEscogerBusqueda',array('idcita'=>$k));
							$this->salida .= "<a href=\"$accion\">Eliminar</a>";
						}
						$this->salida .= "</td>";
					}
					$this->salida .= "</tr>";
					$AgendaIdAnt=$v['agenda_cita_id_padre'];
				}
				$this->salida .= "</table>";
				$this->salida .= "<br>";
			}
			//CAMBIO DAR
				$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "</table>";
			//FIN CAMBIO DAR			
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "<tr>";
			$this->salida .= "<td width=\"62%\">";
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= "<tr>";
			$this->salida .= "<td>";
			$this->salida .= "<fieldset>";
			$this->salida .= "<legend class=\"field\">";
			$this->salida .= "BUSQUEDA DE FECHA";
			$this->salida .= "</legend>";
			$this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">";
			$this->salida .= "<tr>";
			$this->salida .= "<td width=\"100%\">";
			$this->salida.="\n".'<script>'."\n";
			$this->salida.='function year1(t)'."\n";
			$this->salida.='{'."\n";
			$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
			foreach($_REQUEST as $v=>$v1)
			{
				if($v!='year' and $v!='meses' and $v!='DiaEspe')
				{
					$this->salida.='&'.$v.'='.$v1;
				}
			}
			$this->salida.='";'."\n";
			$this->salida.='}'."\n";
			$this->salida.='function profe(t)'."\n";
			$this->salida.='{'."\n";
			//$this->salida.='var a=t.elements[1].value'."\n";
			$this->salida.="var b=t.split(',')"."\n";
			$this->salida.="if(b[0]!=undefined && b[1]!=undefined)"."\n";
			$this->salida.="{"."\n";
			//$this->salida.="alert(t);"."\n";
			$this->salida.='window.location.href="Contenido.php?profesional="+b[0]+","+b[1]+"&nompro="+b[2]+"';
			foreach($_REQUEST as $v=>$v1)
			{
				if($v!='year' and $v!='meses' and $v!='DiaEspe' and $v!='profesional' and $v!='nompro')
				{
					$this->salida.='&'.$v.'='.$v1;
				}
			}
			$this->salida.='";'."\n";
			$this->salida.="}"."\n";
			$this->salida.="else"."\n";
			$this->salida.="{"."\n";
			//$this->salida.="alert(t.elements[2].value);"."\n";
			$this->salida.='window.location.href="Contenido.php?profesional="+b[0]+","+b[1]+"&nompro="+b[2]+"';
			foreach($_REQUEST as $v=>$v1)
			{
				if($v!='year' and $v!='meses' and $v!='DiaEspe' and $v!='profesional' and $v!='nompro')
				{
					$this->salida.='&'.$v.'='.$v1;
				}
			}
			$this->salida.='";'."\n";
			$this->salida.="}"."\n";
			$this->salida.='}'."\n";
			$this->salida.='</script>';
			$this->salida .='<form name="cosa">';
			$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
			$this->salida .='<tr align="center">';
			$this->salida .="<td class=\"label\">A???</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['year']))
			{
				$a=explode("-",$_SESSION['CITASMES'][0]);
				$year=$_REQUEST['year']=$a[0];
				$this->AnosAgenda(True,$_REQUEST['year']);
			}
			else
			{
				$this->AnosAgenda(true,$_REQUEST['year']);
				$year=$_REQUEST['year'];
			}
			$this->salida .= "</select></td>";
			$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['meses']))
			{
				$a=explode("-",$_SESSION['CITASMES'][0]);
				if(empty($a[0]))
				{
					$mes=$_REQUEST['meses']=date("m");
					$year=date("Y");
				}
				else
				{
					$mes=$_REQUEST['meses']=$a[1];
				}
				$this->MesesAgenda(True,$year,$mes);
			}
			else
			{
				$this->MesesAgenda(True,$year,$_REQUEST['meses']);
				$mes=$_REQUEST['meses'];
			}
			$this->salida .= "</select>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .='</form>';
			$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
			$this->salida .='<br>';
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</fieldset>";
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "<BR>";
			$this->salida .= "<table border=\"0\" width=\"92%\" align=\"center\">";
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','EscogerBusqueda');
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "<tr>";
			$this->salida .= "<td>";
			$this->salida .= "<fieldset>";
			$this->salida .= "<legend class=\"field\">";
			$this->salida .= "BUSQUEDA AVANZADA";
			$this->salida .= "</legend>";
			$this->salida .= "<table width=\"90%\" align=\"center\">";
			$this->salida .= "<tr>";
			$this->salida .= "<br>";
			$this->salida .= "<td class=\"label\">";
			$this->salida .= "TIPO BUSQUEDA: ";
			$this->salida .= "</td>";
			$this->salida .= "<td>";
			$this->salida .= "<select name=\"TipoBusqueda\" class=\"select\">";
			if($_REQUEST['TipoBusqueda']==1)
			{
				$this->salida .= "<option value=\"1\" selected>NOMBRE MEDICO</option>";
			}
			else
			{
				$this->salida .= "<option value=\"1\">NOMBRE MEDICO</option>";
			}
			if($_REQUEST['TipoBusqueda']==2)
			{
				$this->salida .= "<option value=\"2\" selected>MEDICOS HOMBRES</option>";
			}
			else
			{
				$this->salida .= "<option value=\"2\">MEDICOS HOMBRES</option>";
			}
			if($_REQUEST['TipoBusqueda']==3)
			{
				$this->salida .= "<option value=\"3\" selected>MEDICOS MUJERES</option>";
			}
			else
			{
				$this->salida .= "<option value=\"3\">MEDICOS MUJERES</option>";
			}
			$this->salida .= "</select>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			if($_REQUEST['TipoBusqueda']==1 or $_REQUEST['TipoBusqueda']==2 or $_REQUEST['TipoBusqueda']==3)
			{
				$this->salida .= "<tr>";
				$this->salida .= "<td class=\"label\">";
				$this->salida .= "Medicos: ";
				$this->salida .= "</td>";
				$this->salida .= "<td>";
				$this->salida .= "<select name=\"Profesionales\" class=\"select\" onchange=\"profe(this.value)\">";
				if(empty($_SESSION['AsignacionCitas']['profesional']))
				{
					$this->salida .= "<option value=\"\" selected>--Seleccione--</option>";
					$profesional=$this->Profesionales();
					$i=0;
					while($i<sizeof($profesional[0]))
					{
						$this->salida .= "<option value=\"".$profesional[1][$i].",".urlencode($profesional[2][$i]).",".$profesional[0][$i]."\">".$profesional[0][$i]."</option>";
						$i++;
					}
				}
				else
				{
					$this->salida .= "<option value=\"\">--Seleccione--</option>";
					$profesional=$this->Profesionales();
					$i=0;
					$a=explode(",",$_SESSION['AsignacionCitas']['profesional']);
					while($i<sizeof($profesional[0]))
					{
						if($a[0]==$profesional[1][$i] and $a[1]==$profesional[2][$i])
						{
							$this->salida .= "<option value=\"".$profesional[1][$i].",".$profesional[2][$i].",".$profesional[0][$i]."\" selected>".$profesional[0][$i]."</option>";
						}
						else
						{
							$this->salida .= "<option value=\"".$profesional[1][$i].",".$profesional[2][$i].",".$profesional[0][$i]."\">".$profesional[0][$i]."</option>";
						}
						$i++;
					}
				}
				$this->salida .= "</select>";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "<tr>";
			$this->salida .= "<td colspan=\"2\" align=\"center\">";
			$this->salida .= "<br>";
			$this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"Buscar\">";
			$this->salida .= "<br>";
			$this->salida .= "<br>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</form>";
			$this->salida .= "</table>";
			$this->salida .= "</fieldset>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .='<br>';
			$this->salida .='<table border="0" align="center" width="50%">';
			$this->salida .='<tr>';
			$this->salida .='<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','AsignarCitas');
			$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .='<input type="submit" name="volver" value="Volver" class="input-submit">';
			$this->salida .='</form>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$this->salida .= ThemeCerrarTabla();
		}
		else
		{
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];

			SessionDelVar('CITASMES');
			$this->salida = ThemeAbrirTabla('SELECCIONE LA CITA PARA ASIGNAR');
			$this->salida .= "<BR>";
			$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Empresa";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Departamento";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomemp'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomdep'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= "<BR>";
			$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Identificaci???";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Nombre Paciente";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .=$_SESSION['AsignacionCitas']['Documento'].' - '.$_SESSION['AsignacionCitas']['TipoDocumento'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			if(!empty($_SESSION['AsignacionCitas']['SegundoNombre']) and !empty($_SESSION['AsignacionCitas']['SegundoApellido']))
			{
				$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['SegundoNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'].' '.$_SESSION['AsignacionCitas']['SegundoApellido'];
			}
			else
			{
				if(empty($_SESSION['AsignacionCitas']['SegundoNombre']))
				{
					if(empty($_SESSION['AsignacionCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'];
					}
					else
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'].' '.$_SESSION['AsignacionCitas']['SegundoApellido'];
					}
				}
				else
				{
					if(empty($_SESSION['AsignacionCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['SegundoNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'];
					}
				}
			}
			$this->salida .= $nom;
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			if(empty($_SESSION['AsignacionCitas']['profesional']))
			{
				if(empty($_REQUEST['TipoBusqueda']))
				{
					$_REQUEST['TipoBusqueda']=1;
				}
				$profesional=$this->Profesionales();
				$i=0;
				$this->salida .='<br>';
				$this->salida .='<br>';
				if(!empty($profesional))
				{
					$this->salida.='<table align="center" width="80%" class="modulo_table_list">';
					$this->salida.='<tr align="center" class="modulo_table_title">';
					$this->salida.='<td align="center">';
					$this->salida.='Profesionales';
					$this->salida.='</td>';
					$this->salida.='</tr>';
					while($i<sizeof($profesional[0]))
					{
						$this->salida.='<tr>';
						if($spy==0)
						{
							$this->salida.='<td class="modulo_list_claro" align="center">';
							$spy=1;
						}
						else
						{
							$this->salida.='<td class="modulo_list_oscuro" align="center">';
							$spy=0;
						}
						$vec1['profesional']=$profesional[1][$i].",".$profesional[2][$i];
						$vec1['nompro']=$profesional[0][$i];
						$vec1['DiaEspe']=$_REQUEST['DiaEspe'];
						$accion=ModuloGetURL('app','AgendaMedicaCoc','','EscogerBusqueda',$vec1);
						$this->salida.='<a href="'.$accion.'">'.$profesional[0][$i].'</a>';
						$this->salida.='</td>';
						$this->salida.='</tr>';
						$i++;
					}
					$this->salida.='</table>';
					$this->salida .='<br>';
				}
				else
				{
					$this->salida .='<table border="0" align="center" width="50%">';
					$this->salida .='<tr>';
					$this->salida .='<td align="center">';
					$this->salida.='<label class="label_error">No existen profesionales para este d???.</label>';
					$this->salida .='</td>';
					$this->salida .='</tr>';
					$this->salida .='</table>';
					$this->salida .='<br>';
				}
				$this->salida .='<table border="0" align="center" width="50%">';
				$this->salida .='<tr>';
				$this->salida .='<td align="center">';
				$vec='';
				foreach($_REQUEST as $v=>$v1)
				{
					if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and $v!='DiaEspe' and $v!='volver' and $v!='profesional' and substr_count ($v,'seleccion')<1)
					{
						$vec[$v]=$v1;
					}
				}
				$accion=ModuloGetURL('app','AgendaMedicaCoc','','EscogerBusqueda',$vec);
				$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
				$this->salida .='<input type="submit" name="volver" value="Volver" class="input-submit">';
				$this->salida .='</form>';
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$this->salida .='</table>';
				$this->salida .='<br>';
			}
			else
			{
				$this->salida .='<br>';
				$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
				$this->salida .= '<tr align="center" class="modulo_table_title">';
				$this->salida .= '<td align="center">';
				$this->salida .= "Profesional";
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= "Fecha de Cita";
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= '<tr class="modulo_list_oscuro">';
				$this->salida .= '<td align="center">';
				$this->salida .= $_SESSION['AsignacionCitas']['nompro'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$a=explode("-",$_REQUEST['DiaEspe']);
				$this->salida .= ucwords(strftime("%A %d de %B de %Y",mktime(0,0,0,$a[1],$a[2],$a[0])));
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
				$this->salida .= "</table>";
				//subi esta forma
				foreach($_REQUEST as $v=>$v1)
				{
					if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and substr_count ($v,'seleccion')<1 and substr_count ($v,'tmpcita')<1)
					{
						$vec[$v]=$v1;
					}
				}
				$con=0;
				$accion=ModuloGetURL('app','AgendaMedicaCoc','','InsertarDatosPaciente',$vec);
				$this->salida .='<form name="forma" method="post" action="'.$accion.'">';
        //fin
				$hisant=$this->BusquedaHistoriaClinicaAnterior($_SESSION['AsignacionCitas']['TipoDocumento'],$_SESSION['AsignacionCitas']['Documento']);
				if(!empty($hisant))
				{
					$atencionanterior=$this->BusquedaAtencionesAnterioresH($_SESSION['AsignacionCitas']['TipoDocumento'],$_SESSION['AsignacionCitas']['Documento']);
					$salida ='<br>';
					$salida .= '<table width="70%" align="center" class="modulo_table_list">';
					$salida .= '<tr align="center" class="modulo_table_title">';
					$salida .= '<td align="center">';
					$salida .= "<input type=\"checkbox\" name=\"historia\" value=\"1\"";
					if(empty($atencionanterior) OR !empty($_REQUEST['historia']) OR $_REQUEST['chequeo']==1)
					{
						$salida .="checked";
					}
					$salida .=" >    Solicitar Historia Cl???ica";
					$salida .= "</td>";
					$salida .= "</table>";
				}else{
          $salida .= "<input type=\"hidden\" name=\"historia\" value=\"0\"";
				}
				//CAMBIO DAR
				$this->salida .= "       <BR><table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  </table>";
				//FIN CAMBIO DAR
				if(!empty($citaocupada))
				{
					$this->salida .='<br>';
					$this->salida .= '<table width="80%" align="center">';
					$this->salida .= '<tr align="center">';
					$this->salida .= '<td align="center" class="label_error">';
					$this->salida .= "La cita ya esta ocupada";
					$this->salida .= "</td>";
					$this->salida .= "</tr>";
					$this->salida .= "</table>";
				}
				$this->DelCitasTmp();
				$citas=$this->CitasDia();
				if(!empty($citas))
				{
					$dato=$this->TamCita();
					$f=0;
					$t=0;
					$s=0;
					while($f<sizeof($dato[2]))
					{
						$a=array_keys($dato[2],$citas[1][$s]);
						if(!empty($a))
						{
							$difcita[$dato[2][$a[0]]][$citas[2][$s]][$citas[3][$s]]=$citas[0][$s];
							$t++;
							$s++;
						}
						else
						{
							$t=0;
							$f++;
						}
					}
					$con=0;
// 					while($con<sizeof($dato[2]))
// 					{
// 						foreach($difcita[$dato[2][$con]] as $k=>$v)
// 						{
// 							$a=explode(":",$v);
// 							break;
// 						}
// 						$d=$a[1]/5;
// 						if($d==0 or $d==2 or $d==4 or $d==6 or $d==8 or $d==10)
// 						{
// 							$i='00';
// 						}
// 						else
// 						{
// 							$i='05';
// 						}
// 						$f=0;
// 						$t=0;
//print_r($difcita);
						foreach($difcita as $k=>$v){
							$cita='';
							foreach($v as $t=>$vector){
							  foreach($vector as $estadoCita=>$m){
								  if($estadoCita==0){
                    $cita[$t]=$_REQUEST['DiaEspe']." ".$m;
									}
								}
							}
							$datoscita[]=$cita;
						}
// 						while(date("d H:i",mktime(0,$i,0,1,1,1))<date("d H:i",mktime(23,59,0,1,1,1)))
// 						{
// 							echo $f.'=>'.$difcita[$dato[2][$con]][$f].date("H:i",mktime(0,$i,0,1,1,1)).'<br>';
// 							if($difcita[$dato[2][$con]][$f]!=date("H:i",mktime(0,$i,0,1,1,1)))
// 							{
// 								$cita[$t]=$_REQUEST['DiaEspe']." ".date("H:i",mktime(0,$i,0,1,1,1));
// 								$t++;
// 							}
// 							else
// 							{
// 								$f++;
// 							}
// 							$i=$i+$dato[0][$con];
// 						}
// 						$con++;
// 					}
          /*foreach($_REQUEST as $v=>$v1)
					{
						if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and substr_count ($v,'seleccion')<1 and substr_count ($v,'tmpcita')<1)
						{
							$vec[$v]=$v1;
						}
					}
					$con=0;
          $accion=ModuloGetURL('app','AgendaMedicaCoc','','InsertarDatosPaciente',$vec);
					$this->salida .='<form name="forma" method="post" action="'.$accion.'">';
					*/
					$this->salida.=$salida;
					$cont=0;
					$this->salida.="<br>";
					//print_r($difcita);
					foreach($difcita as $k=>$v)
					{
						SessionSetVar('CITASDIA',$datoscita[$cont]);
						$cont=$cont+1;
						$inter=array_keys($dato[2],$k);
						$i=0;
						$this->salida .= "<script>\n";
						$this->salida .= "function ir(p,j,forma)\n";
						$this->salida .= "{\n";
						//$this->salida .= "alert(forma.indice.value);\n";
            $this->salida .= "var arreglo=new Array();";
            $this->salida .= "l=0;";
            $this->salida .= "for(x=0;x<forma.elements.length;x++){";
		        $this->salida .= "  if(forma.elements[x].type=='checkbox' && forma.elements[x].name!='historia' && forma.elements[x].checked==true){";
            $this->salida .= "    arreglo[l]=forma.elements[x].value;";
            $this->salida .= "    l++;";
						$this->salida .= "  }";
            $this->salida .= "}";
						$this->salida .= "if(j==true)\n";
						$this->salida .= "{\n";
						$this->salida .= " if(forma.historia.checked==true){\n";
						$this->salida .= "  checkeado=1;";
						$this->salida .= " }else{\n";
						$this->salida .= "  checkeado=0;";
						$this->salida .= " }\n";
						$this->salida .= "window.location.href='".ModuloGetUrl('app','AgendaMedicaCoc','','EscogerBusqueda',array('profesional'=>$_REQUEST['profesional'],'nompro'=>$_REQUEST['nompro'],'DiaEspe'=>$_REQUEST['DiaEspe']))."&dato='+p+'&chequeo='+checkeado+'&datosArreglo='+arreglo;\n";
						$this->salida .= "}\n";
						$this->salida .= "}\n";
						$this->salida .= "</script>\n";
            $vectorDatos=explode(',',$_REQUEST['datosArreglo']);
						if(sizeof($vectorDatos)>1){
              $secuencia=$this->verificarSecuenciaDatos($vectorDatos);
							if($secuencia!=1){
							  $valorUnico=$vectorDatos[0];
							  unset($vectorDatos);
								unset($_REQUEST['datosArreglo']);
                $vectorDatos[0]=$valorUnico;
								$this->salida .= "<table border=\"0\" width=\"70%\" align=\"center\">";
								$this->salida .= "<tr><td>".$this->SetStyle("MensajeError")."</td></tr>";
                $this->salida .= "</table>";
							}
						}
						$this->salida .= "<table border=\"1\" width=\"70%\" align=\"center\" class=\"modulo_table\">";
						//echo $_REQUEST['dato'];
						$horaAnt=-1;
						$contadorMaximo=0;
						foreach($v as $p=>$vector){
						  foreach($vector as $estadoCita=>$m){
								$Fecha=explode(":",$m);
								if($Fecha[0]!=$horaAnt){
									$contador=1;
									$horaAnt=$Fecha[0];
								}else{
									$contador++;
									$contadorMax=$contador;
									if($contadorMax>$contadorMaximo){
										$contadorMaximo=$contadorMax;
									}
								}
							}
						}
						$horaAnt=-1;
						$bandera=0;
						$this->salida .= "<tr class=\"modulo_table_title\">";
						$this->salida .= "<td align=\"center\">Hora</td>";
						$this->salida .= "<td colspan=\"$contadorMaximo\">Minutos</td>";
						$this->salida .= "</tr>";
						$con=0;
						foreach($v as $p=>$vector){
						  foreach($vector as $estadoCita=>$m){
								if($i==0){
									$i=1;
									$c=explode(":",$m);
								}
								$Fecha=explode(":",$m);
								if($Fecha[0]!=$horaAnt){
									if($bandera==1){
									if($sumadorCajones<$contadorMaximo){
										while($sumadorCajones<$contadorMaximo){
											$this->salida .='<td>&nbsp;</td>';
											$sumadorCajones++;
										}
									}
									$this->salida .="         </tr>";
									}
									$this->salida .="         <tr>";
									$this->salida .= "        <td bgcolor=\"#F3F3E9\" align=\"center\" class=\"titulo2\" width=\"10%\" nowrap>".$Fecha[0]."</td>";
									if($estadoCita==1 ||  $estadoCita==2){
										$color='DiaHoy';
										$this->salida .= "        <td class=\"$color\" valign=\"top\" width=\"5%\">".$Fecha[1]."";
									}elseif($estadoCita==3){
									  $color='modulo_list_oscuro';
										$this->salida .= "        <td class=\"$color\" valign=\"top\" width=\"5%\">".$Fecha[1]."";
									}else{
										$this->salida .= "        <td valign=\"top\" width=\"5%\">".$Fecha[1]."";
									}
									$dis='';
									if($estadoCita!=0){
                    $dis='disabled';
									}
									if(in_array($p,$vectorDatos)){
										$this->salida .= "        &nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$p."\" onclick=\"ir('$p',this.checked,this.form)\" checked $dis>";
									}else{
										$this->salida .= "        &nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$p."\" onclick=\"ir('$p',this.checked,this.form)\" $dis>";
									}
									$this->salida .= "         </td>";
									$bandera=1;
									$horaAnt=$Fecha[0];
									$sumadorCajones=1;
								}else{
								  if($estadoCita==1 ||  $estadoCita==2){
										$color='DiaHoy';
										$this->salida .= "        <td class=\"$color\" valign=\"top\" width=\"5%\">".$Fecha[1]."";
									}elseif($estadoCita==3){
									  $color='modulo_list_oscuro';
										$this->salida .= "        <td class=\"$color\" valign=\"top\" width=\"5%\">".$Fecha[1]."";
									}else{
										$this->salida .= "        <td valign=\"top\" width=\"5%\">".$Fecha[1]."";
									}
									$dis='';
									if($estadoCita!=0){
                    $dis='disabled';
									}
									if(in_array($p,$vectorDatos)){
										$this->salida .= "        &nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$p."\" onclick=\"ir('$p',this.checked,this.form)\" checked $dis>";
									}else{
										$this->salida .= "        &nbsp&nbsp;<input type=\"checkbox\" name=\"seleccion$con\" value=\"".$p."\" onclick=\"ir('$p',this.checked,this.form)\" $dis>";
									}
									$this->salida .= "         </td>";
									$sumadorCajones++;
								}//$this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$m."\"></td>";
								$con++;
							}
						}
						if($sumadorCajones<$contadorMaximo){
							while($sumadorCajones<$contadorMaximo){
								$this->salida .='<td>&nbsp;</td>';
								$sumadorCajones++;
							}
						}
						$this->salida .="         </tr>";
						$this->salida .= "			  </table>";
						$this->salida.="<br>";
            unset($_SESSION['CITASDIA']);

						/*$this->salida .= "<BR><table border=\"1\" width=\"80%\" align=\"center\" class=\"modulo_table\">";
						//echo $_REQUEST['dato'];
						foreach($v as $p=>$m)
						{
							if($i==0)
							{
								$i=1;
								$c=explode(":",$m);
							}
							$this->salida .="         <tr bgcolor=\"#F3F3E9\">";
							$this->salida .= "        <td>$m</td>";
							//$this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$m."\"></td>";
							if($p==$_REQUEST['dato'])
							{
								$this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$p."\" onclick=\"ir('$p',this.checked,this.form)\" checked></td>";
							}
							else
							{
								$this->salida .= "        <td width=\"5%\"><input type=\"checkbox\" name=\"seleccion$con\" value=\"".$p."\" onclick=\"ir('$p',this.checked,this.form)\"></td>";
							}
							$this->salida .="         </tr>";
						}
						$this->salida .= "			  </table>";
						$this->salida.="<br>";
						unset($_SESSION['CITASDIA']);
            */
						//$a=explode(":",$m);
						//$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioConsultaDia',array('interval'=>$dato[0][$inter[0]],'intervalo'=>5,'opciones'=>3,'iniminutos'=>$c[1],'inihora'=>$c[0],'ocupado'=>1,'finhora'=>$a[0],'finminutos'=>$a[1]));


						//$this->salida .='<br>';
					}
					$this->salida.="<br>";
					$this->salida .= "<table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
					$this->salida .=" <tr>";
					$this->salida .=" <td class=\"modulo_list_claro\" width=\"5%\" nowrap>&nbsp;</td>";
					$this->salida .=" <td>Turno Cita Cancelada</td>";
					$this->salida .=" </tr>";
					$this->salida .=" <tr>";
					$this->salida .=" <td class=\"DiaHoy\" width=\"5%\" nowrap>&nbsp;</td>";
					$this->salida .=" <td>Turno Cita Asignada</td>";
					$this->salida .=" </tr>";
					$this->salida .= "</table>";
					/*while($con<sizeof($dato[2]))
					{
						SessionSetVar('CITASDIA',$datoscita[$con]);
						$c=explode(":",$difcita[$dato[2][$con]][0]);
						$a=explode(":",$difcita[$dato[2][$con]][sizeof($difcita[$dato[2][$con]])-1]);
						$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioConsultaDia',array('interval'=>$dato[0][$con],'intervalo'=>5,'opciones'=>2,'iniminutos'=>$c[1],'inihora'=>$c[0],'ocupado'=>1,'finhora'=>$a[0],'finminutos'=>$a[1]));
						unset($_SESSION['CITASDIA']);
						$this->salida .='<br>';
						$con++;
					}*/
					unset($_SESSION['Agenda']);
				}
				else
				{
					$this->salida .='<br>';
					$this->salida .='<table border="0" align="center" width="50%">';
					$this->salida .='<tr>';
					$this->salida .='<td align="center">';
					$this->salida .= '<label class="label_error">No existen citas para este profesional</label>';
					$this->salida .='</td>';
					$this->salida .='</tr>';
					$this->salida .='</table>';
					$this->salida .='<br>';
				}
				$this->salida .='<table border="0" align="center" width="50%">';
				$this->salida .='<tr>';
				if(!empty($citas))
				{
					$this->salida .='<td align="right" width="50%" valign="center">';
					$this->salida .='<input type="submit" name="Asignar" value="Asignar" class="input-submit">';
					$this->salida .='</form>';
					$this->salida .='</td>';
				}
				$vec='';
				foreach($_REQUEST as $v=>$v1)
				{
					if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and $v!='DiaEspe' and $v!='volver' and $v!='profesional' and substr_count ($v,'tmpcita')<1 and substr_count ($v,'tmpcita')<1)
					{
						$vec[$v]=$v1;
					}
				}
				if(!empty($_SESSION['CumplirCita']['cita'])){
					$accion=ModuloGetURL('app','AgendaMedicaCoc','','ListadoCitasCumplidas');
				}else{
				  $accion=ModuloGetURL('app','AgendaMedicaCoc','','EscogerBusqueda',$vec);
				}
				$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
				$this->salida .='<td align="left" width="100%">';
				$this->salida .='<input type="submit" name="volver" value="Volver" class="input-submit">';
				$this->salida .='</form>';
				$this->salida .='</td>';
				$this->salida .='</tr>';
				$this->salida .='<tr>';
				$this->salida .= '<td align="center" width="26%" colspan="2">';
				foreach($_REQUEST as $v=>$v1)
				{
					if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses')
					{
						$vec[$v]=$v1;
					}
				}
				$accion=ModuloGetURL('app','AgendaMedicaCoc','','EscogerBusqueda',$vec);
				$this->salida .= '<a href="'.$accion.'" class="normal_10"><br>Refrescar</a>';
				$this->salida .= '</td>';
				$this->salida .='</tr>';
				$this->salida .='</table>';
				$this->salida .='<br>';
			}
			$this->salida .= ThemeCerrarTabla();
		}
		return true;
	}

/**
	* Forma para mensajes.
	* @access private
	* @return boolean
	* @param string mensaje
	* @param string nombre de la ventana
	* @param string accion de la forma
	* @param string nombre del boton
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
				$this->salida .= ThemeAbrirTabla($titulo);
				$this->salida .= "			      <table width=\"60%\" align=\"center\">";
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




/**
* Esta funcion muestra los datos basicos de tipo de cedula, numero de cedula, plan y tipo de consulta para realizar la autorizacion del paciente
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function DatosPaciente()
	{

		SessionDelVar('AsignacionAutomatica');
		if(!empty($_REQUEST[Asignar]))
		{
			SessionSetVar('AsignacionAutomatica',$_REQUEST[Asignar]);
		}
    /*echo "<pre>".print_r($_SESSION,true)."</pre>";
    echo "<pre>".print_r($_REQUEST,true)."</pre>";*/
		if(!empty($_SESSION['CumplirCita']['cita']))
		{
			$_SESSION['AsignacionCitas']=$_SESSION['CumplirCita'];
		}
		else
		{
			if(empty($_REQUEST['LiquidarCitas']['tipo_consulta_id']) and empty($_SESSION['LiquidarCitas']['cita']))
			{
				if(empty($_SESSION['AsignacionCitas']['cita']))
				{
					if(empty($_SESSION['SEGURIDAD']['Citas']['Arreglo'][2][$_REQUEST['Citas']['descripcion1']][$_REQUEST['Citas']['descripcion2']][$_REQUEST['Citas']['descripcion3']]))
					{
						$this->error = "USTED NO TIENE DERECHOS PARA TRABAJAR EN NUESTRO SISTEMA";
						$this->mensajeDeError = "Por favor rec tifique su informaci???.";
						return false;
					}
					$_SESSION['AsignacionCitas']['cita']=$_REQUEST['Citas']['tipo_consulta_id'];
					$_SESSION['AsignacionCitas']['departamento']=$_REQUEST['Citas']['departamento'];
					$_SESSION['AsignacionCitas']['empresa']=$_REQUEST['Citas']['empresa_id'];
					$_SESSION['AsignacionCitas']['nomcit']=$_REQUEST['Citas']['descripcion3'];
					$_SESSION['AsignacionCitas']['nomdep']=$_REQUEST['Citas']['descripcion2'];
					$_SESSION['AsignacionCitas']['nomemp']=$_REQUEST['Citas']['descripcion1'];
					$_SESSION['AsignacionCitas']['sw_anestesiologia']=$_REQUEST['Citas']['sw_anestesiologia'];
					$_SESSION['AsignacionCitas']['cargo_cups']=$_REQUEST['Citas']['cargo_cups'];
					$_SESSION['AsignacionCitas']['sw_busqueda_citas']=$_REQUEST['Citas']['sw_busqueda_citas'];
				}
			}
			else
			{
				if(empty($_SESSION['LiquidarCitas']['cita']))
				{
					$_SESSION['LiquidarCitas']['cita']=$_REQUEST['LiquidarCitas']['tipo_consulta_id'];
					$_SESSION['LiquidarCitas']['departamento']=$_REQUEST['LiquidarCitas']['departamento'];
					$_SESSION['LiquidarCitas']['empresa']=$_REQUEST['LiquidarCitas']['empresa_id'];
					$_SESSION['LiquidarCitas']['nomcit']=$_REQUEST['LiquidarCitas']['descripcion3'];
					$_SESSION['LiquidarCitas']['nomdep']=$_REQUEST['LiquidarCitas']['descripcion2'];
					$_SESSION['LiquidarCitas']['nomemp']=$_REQUEST['LiquidarCitas']['descripcion1'];
					$_SESSION['LiquidarCitas']['sw_anestesiologia']=$_REQUEST['LiquidarCitas']['sw_anestesiologia'];
					$_SESSION['LiquidarCitas']['cargo_cups']=$_REQUEST['LiquidarCitas']['cargo_cups'];
				}
				if(empty($_SESSION['SEGURIDAD']['LiquidarCitas']['Arreglo'][2][$_SESSION['LiquidarCitas']['nomemp']][$_SESSION['LiquidarCitas']['nomdep']][$_SESSION['LiquidarCitas']['nomcit']]))
				{
					$this->error = "USTED NO TIENE DERECHOS PARA TRABAJAR EN NUESTRO SISTEMA";
					$this->mensajeDeError = "Por favor rectifique su informaci???.";
					return false;
				}
			}
		}
		$js = "<script>";
		$js .= "function cambiaEstado(valor)";
		$js .= "{ 
				if(valor==0){
					document.formabuscar.CentroRemision.disabled=true;
					document.formabuscar.CentroRemision.selectedIndex=0;
				}
				else{
					document.formabuscar.CentroRemision.disabled=false;
					
				}";
		$js .= "}";
		$js .= "function validCRemi()";
		$js .= "{";
		$js .= "	if(document.formabuscar.remision[0].checked)
					if(document.formabuscar.CentroRemision.selectedIndex==0){
						alert('Debe especificar un Centro de Remision');
						document.formabuscar.CentroRemision.focus();
						return false;
					}
					else{ 
						document.formabuscar.Buscar.submit();
					}
				else
					document.formabuscar.Buscar.submit();
			
			
			";
		
		$js .= "}";
		$js .= "</script>";
		$this->salida  = ThemeAbrirTabla('BUSCAR PACIENTE');
		$this->salida .= "<BR>";
		$this->salida .= $js;
		$this->salida .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
		$this->salida .= "	<tr align=\"center\" class=\"modulo_table_title\">\n";
		$this->salida .= "		<td align=\"center\">Empresa</td>\n";
		$this->salida .= "		<td align=\"center\">Departamento</td>\n";
		$this->salida .= "		<td align=\"center\">Tipo de Cita</td>\n";
		$this->salida .= "	</tr>\n";
		$this->salida .= "	<tr class=\"modulo_list_oscuro\">\n";
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomemp'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomemp'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomdep'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomdep'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomcit'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];
		}
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<br><br>";
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and $v!='volver' and $v!='Asignar')
			{
				$vec[$v]=$v1;
			}
		}
		
		//$this->salida .= "<table width=\"60%\" align=\"center\">";
		if(!$_SESSION['AsignacionCitas'])
		{
			$action=ModuloGetURL('app','AgendaMedicaCoc','user','DatosIniciales',$vec);
		}
		else
		{
			$arr1 = $vec;
			$vec['arreglo'] = $arr1; 
			$action = ModuloGetURL('app','AgendaMedicaCoc','user','EventoSoat',$vec);
		}

    $this->salida .= "<script>\n";
    $this->salida .= "  function Continuar()\n";
    $this->salida .= "  {\n";
    $this->salida .= "    document.formabuscar.action = \"".$action."\";\n";
    $this->salida .= "    document.formabuscar.submit();\n";
    $this->salida .= "  }\n";
    $this->salida .= "  function EvaluarDatos()\n";
    $this->salida .= "  {\n";
    $this->salida .= "    if(document.formabuscar.remision[0].checked)
					if(document.formabuscar.CentroRemision.selectedIndex==0){
						alert('Debe especificar un Centro de Remision');
						document.formabuscar.CentroRemision.focus();
						return false;
					}
					/*else{ 
						document.formabuscar.Buscar.submit();
					}
				else
					document.formabuscar.Buscar.submit();*/
                          xajax_ValidarPaciente(xajax.getFormValues('formabuscar'));\n";
    //$this->salida .= "	     
    $this->salida .= "  }\n";
    $this->salida .= "</script>\n";
    
		//$this->salida .= "<form name=\"formabuscar\" action=\"$action\" method=\"post\">";
		$this->salida .= "<center><div id=\"error\" class=\"label_error\"></div></center>\n";
		$this->salida .= "<form name=\"formabuscar\" id=\"formabuscar\" action=\"\" method=\"post\">\n";
		$this->salida .= "  <input type='hidden' name='NoAutorizacion' value=\"\">\n";
		$this->salida .= "  <table align=\"center\">".$this->SetStyle("MensajeError")."</table>\n";
		
    $this->salida .= "  <table width=\"75%\" align=\"center\" class=\"modulo_table_list\" border=\"0\">\n";
		$this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "      <td align=\"left\">* TIPO DOCUMENTO: </td>\n";
		$this->salida .= "	    <td class=\"modulo_list_claro\" align=\"left\">\n";
    $this->salida .= "        <select name=\"TipoDocumento\" class=\"select\">\n";
		$tipo_id=$this->tipo_id_paciente();
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$a=$_SESSION['LiquidarCitas']['TipoDocumento'];
		}
		else
		{
			$a=$_SESSION['AsignacionCitas']['TipoDocumento'];
		}
		$tipo_id_paciente=$a;
		$this->BuscarIdPaciente($tipo_id,'False',$a);
		$this->salida .= "      </select>\n";
    $this->salida .= "    </td>\n";
    $this->salida .= "  </tr>\n";
		if(empty($_SESSION['AsignacionCitas']['cita']))
		{
			$a=$_SESSION['LiquidarCitas']['Documento'];
		}
		else
		{
			$a=$_SESSION['AsignacionCitas']['Documento'];
		}
		$paciente_id=$a;
    
		$this->salida .= "  <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "    <td align=\"left\">* DOCUMENTO: </td>\n";
    $this->salida .= "    <td class=\"modulo_list_claro\" align=\"left\">\n";
    $this->salida .= "      <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$a."\">\n";
    $this->salida .= "    </td>\n";
    $this->salida .= "  </tr>\n";
		$this->salida .= "	<tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "    <td align=\"left\">* PLAN: </td>\n";
    $this->salida .= "    <td class=\"modulo_list_claro\" align=\"left\">\n";
    $this->salida .= "      <select name=\"Responsable\"  class=\"select\">\n";
		$this->salida .= "        <option value=\"-1\">-------SELECCIONE-------</option>\n";
		
    $planes = $this->ObtenerResponsables();
    
		if(empty($_SESSION['AsignacionCitas']['cita']))
			$a=$_SESSION['LiquidarCitas']['Responsable'];
		else
			$a=$_SESSION['AsignacionCitas']['Responsable'];
				
    $chk = "";
    foreach($planes as $key => $dtl)
    {
      ($dtl['plan_id'] == $a)? $chk = "selected": $chk="";
      $this->salida .= "        <option value=\"".$dtl['plan_id']."\" $chk>".$dtl['plan_descripcion']."</option>\n";
    }
    $this->salida .= "       </select>\n";
    $this->salida .= "      </td>\n";
    $this->salida .= "    </tr>\n";
 		
		IncludeFile("app_modules/AgendaMedicaCoc/RemoteXajax/RemoteX.php");
		IncludeFile("app_modules/AgendaMedicaCoc/RemoteXajax/Pacientes.php");
		$this->SetXajax(array("ListaProgramasPYP","Seleccion","ValidarPaciente"));
	
		$on="";
		$programa=$_SESSION[$tipo_id_paciente][$paciente_id]['Programa_id'];
		$programas=$this->GetProgramasPYP();
		if(!$_SESSION['PROMOCION_Y_PREVENCION'] AND !empty($programas))
			$on="onChange=\"xajax_ListaProgramasPYP(document.formabuscar.TipoConsulta.value);\"";
		
		$this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "      <td align=\"left\">* TIPO DE CONSULTA: </td>\n";
    $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
    $this->salida .= "        <select name=\"TipoConsulta\"  class=\"select\" $on>\n";
 		$tipoconsulta=$this->TipoConsulta1();
 		if($tipoconsulta==false)
 		{
 			$this->error = "No existe ningun cargo";
 			$this->mensajeDeError = "Verifique los cargos en la tabla cargo_cita.";
 			return false;
 		}
 		$i=0;
 		$this->salida .= "          <option value=\"-1\">-------SELECCIONE------</option>\n";
 		if(empty($_SESSION['AsignacionCitas']['cargo_cups']))
 		{
 			$a=$_SESSION['LiquidarCitas']['cargo_cups'];
 		}
 		else
 		{
 			$a=$_SESSION['AsignacionCitas']['cargo_cups'];
 		}
 		while($i<sizeof($tipoconsulta[0]))
 		{
			if(!$_SESSION['PROMOCION_Y_PREVENCION'])
			{
				if($tipoconsulta[0][$i]==$a)
				{
					$this->salida .=" <option value=\"".$tipoconsulta[0][$i]."\" selected>".$tipoconsulta[1][$i]."</option>";
				}
				else
				{
					$this->salida .=" <option value=\"".$tipoconsulta[0][$i]."\">".$tipoconsulta[1][$i]."</option>";
				}
			}
			elseif($tipoconsulta[2][$i])
			{
				if($tipoconsulta[0][$i]==$a)
				{
					$this->salida .=" <option value=\"".$tipoconsulta[0][$i]."\" selected>".$tipoconsulta[1][$i]."</option>";
				}
				else
				{
					$this->salida .=" <option value=\"".$tipoconsulta[0][$i]."\">".$tipoconsulta[1][$i]."</option>";
				}
			}
 			$i++;
 		}

 		$this->salida .= "       </select></td></tr>";
		
		
		
		//////////////////////////////////////
		$CentroRemision = $this->CentrosRemision();
		if(!$CentroRemision==false)
 		{
 			$i=0;
			$this->salida .= " <tr><td class=\"".$this->SetStyle("TipoConsulta")."\">REMITIDO : </td>";
			$this->salida .="<td class=\"".$this->SetStyle("TipoConsulta")."\"><table align='center' border='1'width='100%'>
					<tr><td class=\"".$this->SetStyle("TipoConsulta")."\">SI<input name='remision' type='radio' value=1/ onClick='cambiaEstado(this.value);'>";
			$this->salida .="NO<input name='remision' type='radio' value=0 checked/ onClick='cambiaEstado(this.value);'></td>";
			$this->salida .="<td class=\"".$this->SetStyle("TipoConsulta")."\" align='rigth'>CENTRO DE REMISION:</td>";
			$this->salida .="<td ><select name=\"CentroRemision\"  class=\"select\" disabled>";
			$this->salida .=" <option value=\"-1\">-------SELECCIONE------</option>";
			while($i<sizeof($CentroRemision[0]))
 			{
			
				$this->salida .=" <option value=\"".$CentroRemision[0][$i]."\">".$CentroRemision[1][$i]."</option>";
				$i++;
			}
		
			$this->salida .="</select></td></tr></table></td></tr>";
		}
 		/////////////////////////////////
 		
		
		$this->salida .= "        </select>\n";
    		$this->salida .= "      </td>\n";
    		$this->salida .= "    </tr>\n";
		$this->salida .= "  </table>\n";
		$this->salida .= "  <table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida .= "    <tr>\n";
		$this->salida .= "		  <td id=\"prog_pyp\" class=\"label\" align=\"center\"> </td>";
		$this->salida .= "	  </tr>\n";
		$this->salida .= "  </table>\n";	
		$this->salida .= "  <br>\n";
		$this->salida .= "  <table border=\"0\" align=\"center\" width=\"50%\">\n";
		$this->salida .= "    <tr>\n";
		$this->salida .= "      <td align=\"center\">\n";
		$this->salida .= "        <input type=\"button\" name=\"Buscar\" value=\"Busqueda\" class=\"input-submit\" onclick=\"EvaluarDatos()\">\n";
		$this->salida .= "      </td>\n";
    

		if(empty($_SESSION['CumplirCita']['cita']))
		{
			if(empty($_SESSION['AsignacionCitas']['cita']))
			{
				$a='LiquidarCitas';
			}
			else
			{
				$a='AsignarCitas';
			}
		}
		else
		{
			$a='ListadoCitasCumplidas';
		}
		if(!$_SESSION['PROMOCION_Y_PREVENCION'])
			$accion=ModuloGetURL('app','AgendaMedicaCoc','',$a);
		else
		{
			if(!empty($_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_CPN']))
			{      
				$accion=ModuloGetURL('app','GestionSeguimientoCPN','user','FrmSeguimientoCPN',array('datos'=>$_SESSION['DatosSeguimientoCPN'],'opcion'=>$_SESSION['opcion']));		
				unset($_SESSION['AsignacionCitas']);
				unset($_SESSION['DATOSPACIENTE']);
				unset($_SESSION['CONSULTAEXT']);         
    	}
			elseif(!empty($_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_RENOPROTECCION']))
			{
				  
				$accion=ModuloGetURL('app','GestionSeguimientoReno','user','FrmSeguimientoReno',array('datos'=>$_SESSION['DatosSeguimientoReno'],'opcion'=>$_SESSION['opcion']));		
				unset($_SESSION['AsignacionCitas']);
				unset($_SESSION['DATOSPACIENTE']);
				unset($_SESSION['CONSULTAEXT']); 
			}
			elseif(!empty($_SESSION['PROMOCION_Y_PREVENCION']['GESTION_SEGUIMIENTO_PFLIAR']))
			{
				$accion=ModuloGetURL('app','GestionSeguimientoPFliar','user','FrmSeguimientoPFliar',array('datos'=>$_SESSION['DatosSeguimientoPFliar'],'opcion'=>$_SESSION['opcion']));
				unset($_SESSION['AsignacionCitas']);
				unset($_SESSION['DATOSPACIENTE']);
				unset($_SESSION['CONSULTAEXT']); 
			}
		}
		
    $this->salida .= "    <td align=\"center\">\n";
		//$this->salida .='<form name="volver" method="post" >';
		$this->salida .= "      <input type=\"button\" onclick=\"Volver()\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
		//$this->salida .='</form>';
		$this->salida .= "    </td>\n";
		$this->salida .= "   </tr>\n";
		$this->salida .= "  </table>\n";
    $this->salida .= "</form>\n";
    $this->salida .= "<script>\n";
    $this->salida .= "  function Volver()\n";
    $this->salida .= "  {\n";
    $this->salida .= "    document.formabuscar.action =\"".$accion."\";\n";
    $this->salida .= "    document.formabuscar.submit();\n";
    $this->salida .= "  }\n";
    $this->salida .= "</script>\n";
		$this->SetJavaScripts('BuscadorBD');
		$this->salida .='<br>';
		$this->salida .='<table border="0" align="right" width="50%">';
		$this->salida .='<tr align="right">';
		$this->salida .='<td align="right" class="normal_10">';
		$this->salida .=RetornarWinOpenDatosBuscadorBD($_SESSION['AsignacionCitas']['departamento'],'formabuscar');
		$this->salida .='</td>';
		$this->salida .='</tr>';
		$this->salida .='</table>';
		$this->salida .='<br>';
		
		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserDrag");
		$this->IncludeJS("CrossBrowserEvent");
		
		
		
		$this->salida.= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
		$this->salida.= "	<div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida.= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
		$this->salida.= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida.= "	<div id='d2Contents'>\n";
		$this->salida.="			<form name=\"forma_pyp\" action=\"\" method=\"post\">";
		$this->salida.=" 				<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.=" 					<tr align=\"center\" class=\"hc_table_submodulo_list_title\">";
		$this->salida.=" 						<td>PROGRAMA PYP</td>";
		$this->salida.=" 						<td>OP</td>";
		$this->salida.=" 					</tr>";
		$k=0;
		foreach($programas as $prog)
		{
			if($k%2==0)
				$estilo="modulo_list_oscuro";
			else
				$estilo="modulo_list_claro";
			
			$check="";
			if($prog['programa_id']==$programa)
			{
				$check="checked";
				$this->salida.="<script>";
				$this->salida.="xajax_Seleccion(".$prog['programa_id'].",'".strtoupper($prog['descripcion'])."');";
				$this->salida.="</script>";
			}
			if(!$check)
			{
				$this->salida.=" 					<tr align=\"center\" class=\"$estilo\">";
				$this->salida.=" 						<td align=\"right\" class=\"label\">".strtoupper($prog['descripcion'])."</td>";
				$this->salida.=" 						<td><input type=\"radio\" name=\"programas\" value=\"".$prog['programa_id']."\" onClick=\"xajax_Seleccion(".$prog['programa_id'].",'".strtoupper($prog['descripcion'])."')\" $check></td>";
				$this->salida.=" 					</tr>";
			}
			$k++;
		}
		$this->salida.="			</table>";
		$this->salida.="		</form>";
		$this->salida.="	</div>\n";
		$this->salida.="</div>\n";

		$this->salida.="<script>";
		$this->salida.="	var hiZ=2;";
		
		$this->salida .= "	function Iniciar()\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  titulo = 'titulo';\n";
		$this->salida .= "	  contenedor = 'd2Container';\n";
		$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
		$this->salida .= "		ele = xGetElementById('d2Contents');\n";
		$this->salida .= "		for(var i=0;i<document.forma_pyp.elements.length;i++)\n";
		$this->salida .= "			document.forma_pyp.programas[i].checked=false;\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/2, xScrollTop()+200);\n";
		$this->salida .= "	  xResizeTo(ele,330,'auto');\n";
		$this->salida .= "		ele = xGetElementById(contenedor);\n";
		$this->salida .= "	  xMoveTo(ele, xClientWidth()/2, xScrollTop()+200);\n";
		$this->salida .= "	  xResizeTo(ele,330, 'auto');\n";
		$this->salida .= "		ele = xGetElementById(titulo);\n";
		$this->salida .= "	  xResizeTo(ele,310, 20);\n";
		$this->salida .= "		xMoveTo(ele, 0, 0);\n";
		$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "		ele = xGetElementById('cerrar');\n";
		$this->salida .= "	  xResizeTo(ele,20, 20);\n";
		$this->salida .= "		xMoveTo(ele, 310, 0);\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  window.status = '';\n";
		$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
		$this->salida .= "	  ele.myTotalMX = 0;\n";
		$this->salida .= "	  ele.myTotalMY = 0;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	  if (ele.id == titulo) {\n";
		$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$this->salida .= "	  }\n";
		$this->salida .= "	  else {\n";
		$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$this->salida .= "	  }  \n";
		$this->salida .= "	  ele.myTotalMX += mdx;\n";
		$this->salida .= "	  ele.myTotalMY += mdy;\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
		$this->salida .= "	{\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function MostrarSpan(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"\";\n";
		$this->salida .= "	}\n";
		
		$this->salida .= "	function Cerrar(Seccion)\n";
		$this->salida .= "	{ \n";
		$this->salida .= "		e = xGetElementById(Seccion);\n";
		$this->salida .= "		e.style.display = \"none\";\n";
		$this->salida .= "	}\n";

		$this->salida.="</script>";
		
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
* Esta funcion muestra los datos basicos de tipo de cedula, numero de cedula, plan y tipo de consulta para realizar la autorizacion del paciente
*
* @access public
* @return boolean Para identificar que se realizo.
* @param string tipo de documento
* @param string documento
* @param string mensaje para mostrar
* @param boolean si el paciente existe o no
* @param string responsable de la cuenta
* @param string primer apellido
* @param string segundo apellidos
* @param string primer nombre
* @param string segundo nombre
* @param string telefono
* @param char sexo
* @param string direccion
*/

	function FormaPedirDatos($TipoId,$PacienteId,$mensaje,$Existe,$Responsable,$PrimerApellido,$SegundoApellido,$PrimerNombre,$SegundoNombre,$Telefono,$Sexo,$Direccion,$FechaNacimiento)
	{
		$this->salida .= ThemeAbrirTabla('DATOS PACIENTE');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['nomemp']))
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomemp'];
		}
		else
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomemp'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['nomdep']))
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomdep'];
		}
		else
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomdep'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['nomcit']))
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];
		}
		else
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomcit'];
		}
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		if(!empty($_SESSION['AsignacionCitas']['nomcit']))
		{
			$incumplidas=$this->CitasIncumplidasPaciente($TipoId,$PacienteId,$_SESSION['AsignacionCitas']['Responsable']);
		}
		else
		{
			$incumplidas=$this->CitasIncumplidasPaciente($TipoId,$PacienteId,$_SESSION['LiquidarCitas']['Responsable']);
		}
		if(!empty($incumplidas))
		{
			$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .="<tr><td><fieldset><legend class=\"field\">CITAS INCUMPLIDAS</legend>";
			$this->salida .='<table align="center" width="100%" border="0" class="modulo_table_list">';
			$this->salida .='<tr align="center" class="modulo_table_title">';
			$this->salida .='<td align="center">';
			$this->salida .='Fecha';
			$this->salida .='</td>';
			$this->salida .='<td align="center">';
			$this->salida .='Profesional';
			$this->salida .='</td>';
			$this->salida .='</tr>';
			$i=0;
			while($i<sizeof($incumplidas[0]))
			{
				if($spy==0)
				{
					$this->salida.='<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida.='<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida.='<td align="center">';
				$this->salida.=$incumplidas[0][$i];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$incumplidas[1][$i];
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i++;
			}
			$this->salida.='</table>';
			$this->salida .= "</fieldset></td></tr></table>";
		}
    //Cambio Muestra Citas por Cumplir

		$this->salida .='<br>';
		if(!empty($_SESSION['AsignacionCitas']['nomcit']))
		{
			$porcumplir=$this->CitasPorCumplirPaciente($TipoId,$PacienteId,$_SESSION['AsignacionCitas']['Responsable']);
		}
		else
		{
			$porcumplir=$this->CitasPorCumplirPaciente($TipoId,$PacienteId,$_SESSION['LiquidarCitas']['Responsable']);
		}
		if(!empty($porcumplir))
		{
			$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .="<tr><td><fieldset><legend class=\"field\">CITAS POR CUMPLIR</legend>";
			$this->salida .='<table align="center" width="100%" border="0" class="modulo_table_list">';
			$this->salida .='<tr align="center" class="modulo_table_title">';
			$this->salida .='<td align="center">';
			$this->salida .='Fecha';
			$this->salida .='</td>';
			$this->salida .='<td align="center">';
			$this->salida .='Profesional';
			$this->salida .='</td>';
			$this->salida .='</tr>';
			$i=0;
			while($i<sizeof($porcumplir[0]))
			{
				if($spy==0)
				{
					$this->salida.='<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida.='<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida.='<td align="center">';
				$this->salida.=$porcumplir[0][$i];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$porcumplir[1][$i];
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i++;
			}
			$this->salida.='</table>';
			$this->salida .= "</fieldset></td></tr></table>";
		}

		//Fin Cambio
		$cartera=$this->CuentasxCobrar($TipoId,$PacienteId);
		if(!empty($cartera))
		{
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr><td><fieldset><legend class=\"field\">CARTERA PENDIENTE</legend>";
			$this->salida.='<table align="center" width="100%" border="0" class="modulo_table_list">';
			$this->salida.='<tr align="center" class="modulo_table_title">';
			$this->salida.='<td align="center">';
			$this->salida .= 'Empresa';
			$this->salida.='</td>';
			$this->salida.='<td align="center">';
			$this->salida .= 'Centro de Utilidad';
			$this->salida.='</td>';
			$this->salida.='<td align="center">';
			$this->salida .= 'Valor';
			$this->salida.='</td>';
			$this->salida.='<td align="center">';
			$this->salida .= 'Saldo';
			$this->salida.='</td>';
			$this->salida.='<td align="center">';
			$this->salida .= 'Vencimiento';
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$i=0;
			while($i<sizeof($cartera))
			{
				if($spy==0)
				{
					$this->salida.='<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida.='<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida.='<td align="center">';
				$this->salida.=$cartera[$i][razon_social];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$cartera[$i][descripcion];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$cartera[$i][valor];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$cartera[$i][saldo];
				$this->salida.='</td>';
				if($cartera[$i][fecha_vence]<=date("Y-m-d"))
				{
					$this->salida.='<td align="center" class="label_error">';
				}
				else
				{
					$this->salida.='<td align="center">';
				}
				$this->salida.=$cartera[$i][fecha_vence];
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i++;
			}
			$this->salida.='</table>';
			$this->salida .= "</fieldset></td></tr></table>";
		}
		$adelante=$this->CitasAdelante($TipoId,$PacienteId);
		$this->salida .='<br>';
		if(!empty($adelante))
		{
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr><td><fieldset><legend class=\"field\">CITAS PENDIENTES</legend>";
			$this->salida.='<table align="center" width="100%" border="0" class="modulo_table_list">';
			$this->salida.='<tr align="center" class="modulo_table_title">';
			$this->salida.='<td align="center">';
			$this->salida .= 'Fecha';
			$this->salida.='</td>';
			$this->salida.='<td align="center">';
			$this->salida .= 'Profesional';
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$i=0;
			while($i<sizeof($adelante[0]))
			{
				if($spy==0)
				{
					$this->salida.='<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida.='<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida.='<td align="center">';
				$this->salida.=$adelante[0][$i];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$adelante[1][$i];
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i++;
			}
			$this->salida.='</table>';
			$this->salida .= "</fieldset></td></tr></table>";
		}
		$atencion=$this->BusquedaAtencionRiesgo($TipoId,$PacienteId);
		if(!empty($atencion))
		{
			$i=0;
			//$this->salida.="<br>";
			$salida.="<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td>";
			$salida.="Atencion Accidentes de Tr???sito";
			$salida.="</td>";
			$salida1.="<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
			$salida1.="<tr class=\"modulo_table_title\">";
			$salida1.="<td align=\"center\">";
			$salida1.="Atencion Enfermedades Profesionales";
			$salida1.="</td>";
			$salida.="</tr>";
			$salida1.="</tr>";
			$spy=0;
			$actran=0;
			$enferprof=0;
			while($i<sizeof($atencion[0]))
			{
				$motivos=$this->BusquedaMotivos($atencion[2][$i]);
				$salida2="";
				if(!empty($motivos))
				{
					$salida2="<table border=\"0\" width=\"80%\" class=\"normal_10\">";
					$sa.="<td rowspan=\"".sizeof($motivos)."\">";
					$sa.="Motivos de Consulta";
					$sa.="</td>";
					foreach($motivos as $k=>$v)
					{
						$salida2.="<tr>";
						$salida2.=$sa;
						$salida2.="<td>";
						$salida2.=$v['descripcion'];
						$salida2.="</td>";
						$salida2.="</tr>";
						$sa="";
					}
					$salida2.="</table>";
				}
				$diagnosticos=$this->BusquedaDiagnosticos($atencion[2][$i]);
				$salida3="";
				if(!empty($diagnosticos))
				{
					foreach($diagnosticos as $k=>$v)
					{
						$salida3.=$v['diagnostico_nombre'].'<br>';
					}
				}
				if($atencion[0][$i]=='02')
				{
					if($spy==0)
					{
						$salida.="<tr class=\"modulo_list_oscuro\">";
						$tr="<tr class=\"modulo_list_oscuro\">";
						$spy=1;
					}
					else
					{
						$salida.="<tr class=\"modulo_list_claro\">";
						$tr="<tr class=\"modulo_list_claro\">";
						$spy=0;
					}
					$salida.="<td align=\"center\">";
					$salida.="Evolucion: ".$atencion[2][$i]." - Fecha: ".$atencion[3][$i];
					$salida.="</td>";
					if(!empty($salida3))
					{
						$salida.="</tr>";
						$salida.=$tr;
						$salida.="<td align=\"center\">";
						$salida.="Diagnosticos: ".$salida3;
						$salida.="</td>";
					}
					if(!empty($salida2))
					{
						$salida.="</tr>";
						$salida.=$tr;
						$salida.="<td align=\"center\">";
						$salida.=$salida2;
						$salida.="</td>";
					}
					$salida.="</tr>";
					$actran=1;
				}
				else
				{
					if($spy1==0)
					{
						$salida1.="<tr class=\"modulo_list_oscuro\">";
						$tr="<tr class=\"modulo_list_oscuro\">";
						$spy1=1;
					}
					else
					{
						$salida1.="<tr class=\"modulo_list_claro\">";
						$tr="<tr class=\"modulo_list_claro\">";
						$spy1=0;
					}
					$salida1.="<td align=\"center\">";
					$salida1.="Evolucion: ".$atencion[2][$i]." - Fecha: ".$atencion[3][$i];
					$salida1.="</td>";
					if(!empty($salida3))
					{
						$salida1.="</tr>";
						$salida1.=$tr;
						$salida1.="<td align=\"center\">";
						$salida1.="Diagnosticos: ".$salida3;
						$salida1.="</td>";
					}
					if(!empty($salida2))
					{
						$salida1.="</tr>";
						$salida1.=$tr;
						$salida1.="<td align=\"center\">";
						$salida1.=$salida2;
						$salida1.="</td>";
					}
					$salida1.="</tr>";
					$enferprof=1;
				}
				$i++;
			}
			$salida.="</table>";
			$salida1.="</table>";

			if($actran==1)
			{
				$this->salida .="<br>";
				$this->salida .=$salida;
				$this->salida.="<br>";
			}
			if($enferprof==1)
			{
				if($actran!=1)
				{
					$this->salida.="<br>";
				}
				$this->salida .=$salida1;
				$this->salida.="<br>";
			}
		}
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and $v!='volver' and $v!='TipoDocumento' and $v!='Documento' and $v!='Buscar' and $v!='Cancelar' and $v!='TipoBusqueda')
			{
				$vec[$v]=$v1;
			}
		}
		//echo '==>'.SessionIsSetVar('AsignacionAutomatica');
		if(SessionIsSetVar('AsignacionAutomatica') OR (!$variable = ModuloGetVar('app','AgendaMedicaCoc','MenuAsignacionCitas')))
		{
			$action=ModuloGetURL('app','AgendaMedicaCoc','user','GuardarCita',$vec);
		}
		else
		{
			$action=ModuloGetURL('app','AgendaMedicaCoc','user','FormaDecidirTipoAsignacionCita',$vec);
		}
		//echo $action;
		
		
		
		$this->salida .='<script>';
		$this->salida .='function ValidarFormaGuardar($obj){';
		$this->salida .='	if($obj.PrimerNombre.value==""){alert("Debe especificar un Nombre para el Paciente.");';
		$this->salida .='       $obj.PrimerNombre.focus();return false;}';
		$this->salida .='	if($obj.PrimerApellido.value==""){alert("Debe especificar un Apellido para el Paciente.");';
		$this->salida .='	$obj.PrimerApellido.focus();return false;}';
		$this->salida .='	if($obj.FechaNacimiento.value==""){alert("Debe especificar una Fecha de Nacimiento para el Paciente.");';
		$this->salida .='       $obj.FechaNacimiento.focus();return false;}';
		$this->salida .='	else if(!fechas($obj.FechaNacimiento.value)){alert("Fecha Erronea. Debe especificar una fecha valida.");$obj.FechaNacimiento.focus();$obj.FechaNacimiento.value="";return false;}';
		$this->salida .='	if($obj.Telefono.value==""){alert("Debe especificar numero telefonico para el Paciente.");';
		$this->salida .='	$obj.Telefono.focus();return false;}';
		$this->salida .='	if($obj.Sexo.value=="-1"){alert("Debe seleccionar Sexo del Paciente.");';
		$this->salida .='	$obj.Sexo.focus();return false;}';
		$this->salida .='	if($obj.TipoCita.value=="-1"){alert("Debe seleccionar un tipo de Cita.");';
		$this->salida .='	$obj.TipoCita.focus();return false;}';
		$this->salida .='';
		$this->salida .='';
		$this->salida .='';
		$this->salida .='';
		$this->salida .='}';
		
		$this->salida .='function fechas(caja){ 
   					
					if (caja){  
      						borrar = caja;
      						if ((caja.substr(2,1) == "/") && (caja.substr(5,1) == "/")){      
         						for (i=0; i<10; i++){	
            							if (((caja.substr(i,1) < 0) || (caja.substr(i,1)> 9)) && (i != 2) && (i != 5)){
               								borrar = "";
               								break;  
								}  
         						}
	     						if (borrar){ 
	        						a = caja.substr(6,4);
		    						m = caja.substr(3,2);
		   						d = caja.substr(0,2);
		    						if((a < 1900) || (a > 2050) || (m < 1) || (m > 12) || (d < 1) || (d > 31))
		       							borrar = "";
		    						else{
		       							if((a%4 != 0) && (m == 2) && (d > 28))	   
		          							borrar = ""; 
			   						else{
		          							if ((((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)))
			         							borrar = "";	      				  	 
			   						} 
		    						} 
         						} 
      						} 
	  					else
	     						borrar = "";
	 					if (borrar == "")
	     						return false;//alert(\'Fecha erronea\');
						else return true;
   					} 
				}';
		
		
		$this->salida .='</script>';
		
		$this->salida .= '<form name="formaguardar" action="'.$action.'" method="post">';


		$this->salida .= "<BR><table width=\"80%\" border=\"0\" align=\"center\">";
		$this->salida .= "<tr><td class=\"modulo_list_oscuro\" width=\"100%\" valign=\"top\">";
		$this->salida .= "<table width=\"98%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\" class=\"Normal_10\">";
		$this->salida .= "<tr height=\"5\"><td colspan=\"3\">&nbsp;</td></tr>";
		if(!$Existe)
		{
			$this->salida .= "<tr height=\"5\"><td colspan=\"3\" align=\"center\" class=\"label_error\">$mensaje</td></tr>";
			$this->salida .= "<tr height=\"5\"><td colspan=\"3\" align=\"center\" class=\"label_error\">".$this->SetStyle("MensajeError")."</td></tr>";
			//$this->salida .= "<tr height=\"5\"><td colspan=\"3\">&nbsp;</td></tr>";
		}
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\"><td class=\"label\">TIPO DOCUMENTO: </td><td>";
		$Tipo=$this->mostrar_id_paciente($TipoId);
// 		$this->salida .= '<input type="hidden" name="Existe" value="'.$Existe.'">';
		$this->salida .= "<input type=\"hidden\" name=\"TipoId\" value=\"$TipoId\">$Tipo</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"label\">DOCUMENTO: </td>";
		$this->salida .= "<td><input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">$PacienteId</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"".$this->SetStyle("PrimerNombre")."\">PRIMER NOMBRE: </td>";
		$this->salida .= "<td><input type=\"text\" maxlength=\"20\" name=\"PrimerNombre\" value=\"";
		if($PrimerNombre)
		{
			$this->salida .= "$PrimerNombre\" class=\"input-text\"";
		}
		else
		{
			$this->salida .= $_SESSION['DATOSPACIENTE']['campo_Primer_nombre']."\" class=\"input-text\"";
		}
		if($Existe)
		{
			$this->salida .= "READONLY";
		}
		$this->salida .="></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"label\">SEGUNDO NOMBRE: </td>";
		$this->salida .= "<td><input type=\"text\" maxlength=\"20\" name=\"SegundoNombre\" value=\"";
		if($SegundoNombre)
		{
			$this->salida .= "$SegundoNombre\" class=\"input-text\"";
		}
		else
		{
			$this->salida .= $_SESSION['DATOSPACIENTE']['campo_Segundo_nombre']."\" class=\"input-text\"";
		}
		if($Existe)
		{
			$this->salida .= "READONLY";
		}
		$this->salida .= "></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"".$this->SetStyle("PrimerApellido")."\">PRIMER APELLIDO: </td>";
		$this->salida .= "<td><input type=\"text\" maxlength=\"30\" name=\"PrimerApellido\" value=\"";
		if($PrimerApellido)
		{
			$this->salida .= "$PrimerApellido\" class=\"input-text\"";
		}
		else
		{
			$this->salida .= $_SESSION['DATOSPACIENTE']['campo_Primer_apellido']."\" class=\"input-text\"";
		}
		if($Existe)
		{
			$this->salida .= "READONLY";
		}
		$this->salida .= "></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"label\">SEGUNDO APELLIDO: </td>";
		$this->salida .= "<td><input type=\"text\" maxlength=\"30\" name=\"SegundoApellido\" value=\"";
		if($SegundoApellido)
		{
			$this->salida .= "$SegundoApellido\" class=\"input-text\"";
		}
		else
		{
			$this->salida .= $_SESSION['DATOSPACIENTE']['campo_Segundo_apellido']."\" class=\"input-text\"";
		}
		if($Existe)
		{
			$this->salida .= "READONLY";
		}
		$this->salida .= "></td>";
		$this->salida .= "</tr>";
		
		
		///////////////////////////////////FECHA NACIMIENTO CLINICA REHABILITACION
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"".$this->SetStyle("FechaNacimiento")."\">FECHA NACIMIENTO: </td>";
		$this->salida .= "<td><input type=\"text\" maxlength=\"30\" name=\"FechaNacimiento\" value=\"";
		//
		if($Existe){	
			$FechaNacimiento = $this->BusquedaFechaNacimientoPaciente($PacienteId);
			if($FechaNacimiento)
			{
				$FechaNacimiento= date('d/m/Y',strtotime($FechaNacimiento));
				$this->salida .= "$FechaNacimiento\" class=\"input-text\"";
				$Existe1=true;
			}
			else
			{
				$this->salida .= "\" class=\"input-text\"";
			}	
		
		}
		else{	
			if($FechaNacimiento)
			{
				$FechaNacimiento= date('d/m/Y',strtotime($FechaNacimiento));
				$this->salida .= "$FechaNacimiento\" class=\"input-text\"";
				$Existe1=true;
			}
			else
			{
				$this->salida .= "\" class=\"input-text\"";
			}
		}
		
		$this->salida .= ">&nbsp;&nbsp;".ReturnOpenCalendario('formaguardar','FechaNacimiento','/')."</td>";
		$this->salida .= "</tr>";
		///////////////////////////////////////FIN FECHA
			
		$this->salida .= "		<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "	  	<td class=\"label\">DIRECCION: </td>";
		$this->salida .= "	  	<td><input type=\"text\" maxlength=\"60\" name=\"Direccion\" value=\"";
		if($Direccion)
		{
			$this->salida .= "$Direccion\" class=\"input-text\"></td>";
		}
		else
		{
			$this->salida .= $_SESSION['DATOSPACIENTE']['campo_direccion_afiliado']."\" class=\"input-text\"></td>";
		}
		$this->salida .= "		</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"".$this->SetStyle("Telefono")."\">TEL???ONO: </td>";
		$this->salida .= "<td><input type=\"text\" maxlength=\"30\" name=\"Telefono\" value=\"";
		if($Telefono)
		{
			$this->salida .= "$Telefono\" class=\"input-text\"";
		}
		else
		{
			$this->salida .= $_SESSION['DATOSPACIENTE']['campo_telefono_afiliado']."\" class=\"input-text\"";
		}
		$this->salida .= "></td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= '<td class="'.$this->SetStyle("Sexo").'">SEXO: </td>';
		$this->salida .= '<td>';
		$sexo_id=$this->sexo();
		if($sexo_id==false)
		{
			return false;
		}
		if($Sexo)
		{
			$this->salida.='<input type="hidden" name="Sexo" value="'.$Sexo.'">';
			$this->salida.=$sexo_id[$Sexo];
		}
		else
		{
			$this->BuscarSexo($sexo_id,$_SESSION['DATOSPACIENTE']['campo_sexo']);
		}
		$this->salida .= '</td></tr>';
		$this->salida .= "		<tr class=\"modulo_list_claro\" height=\"20\"><td class=\"".$this->SetStyle("TipoCita")."\">TIPO DE CITA: </td><td><select name=\"TipoCita\"  class=\"select\">";
		if(!empty($_SESSION['AsignacionCitas']['sw_anestesiologia']))
		{
			$s=$_SESSION['AsignacionCitas']['sw_anestesiologia'];
		}
		else
		{
			$s=$_SESSION['LiquidarCitas']['sw_anestesiologia'];
		}
		$tipocita=$this->TipoCita($s);
		if($tipocita==false)
		{
			return false;
		}
		$i=0;
		$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
		$sabercitaprimeravez=$this->BusquedaAtencionesPrimeraVez();
		while($i<sizeof($tipocita[0]))
		{
			if($tipocita[0][$i]==$_REQUEST['TipoCita'])
			{
				$this->salida .="<option value=\"".$tipocita[0][$i]."\" selected>".$tipocita[1][$i]."</option>";
			}
			else
			{
				$this->salida .=" <option value=\"".$tipocita[0][$i]."\">".$tipocita[1][$i]."</option>";
			}
			$i++;
		}
		$this->salida .= "       </select></td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" height=\"20\">";
		$this->salida .= "<td class=\"".$this->SetStyle("Observacion")."\">OBSERVACI???: </td>";
		$this->salida .= "<td><textarea name=\"Observacion\" cols=\"50\" rows=\"3\" class=\"input-text\">".$_REQUEST['Observacion']."</textarea>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\" height=\"20\">\n";
		$this->salida .= "    <td align=\"right\">\n";
		$this->salida .= "      <input class=\"input-submit\" type=\"submit\" name=\"Siguiente\" value=\"Siguiente\" OnClick=\" return ValidarFormaGuardar(this.form);\">\n";
		$this->salida .= "    </td>\n";
		$this->salida .= "    </form>\n";
		$actionCancelar=ModuloGetURL('app','AgendaMedicaCoc','user','DatosPaciente');
		$this->salida .= "    <form name=\"formacancelar\" action=\"".$actionCancelar."\" method=\"post\">\n";
		$this->salida .= "    <td align=\"left\">\n";
		$this->salida .= "      &nbsp;&nbsp;&nbsp;&nbsp;<input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"Cancelar\">";
		$this->salida .= "    </td>\n";
		$this->salida .= "    </form>\n";
		$this->salida .= "  </tr>\n";
		$this->salida .= "</table><BR>";
		$this->salida .= "</td>";
		$this->salida .= "<td valign=\"top\">";
		if(is_array($_SESSION['DATOSPACIENTE']))
		{
				$a=ImplodeArrayAssoc($_SESSION['DATOSPACIENTE']);
				$arreglon=ExplodeArrayAssoc($a);
				if(!empty($arreglon[campo_Primer_apellido]))
				{
						$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
						$this->salida .= "	<tr>";
						$this->salida .= "	<td colspan=\"2\">";
						$this->salida .= "			      <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
						$this->salida .= "				       <tr>";
						$this->salida .= "				          <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO</td>";
						$this->salida .= "				       </tr>";
						$i=0;
						$plantilla=$this->PlantilaBD($_SESSION['AsignacionCitas']['Responsable']);
						foreach($arreglon as $k => $v)
						{
								$mostrar='';
								$mostrar=$this->CamposMostrarBD($k,$plantilla);
								if(!empty($mostrar[sw_mostrar]))
								{
										if($i % 2) {  $estilo="modulo_list_claro";  }
										else {  $estilo="modulo_list_oscuro";   }
										$this->salida .= "         <tr class=\"$estilo\">";
										if(!empty($mostrar[nombre_mostrar]))
										{  $k=$mostrar[nombre_mostrar];}
										$this->salida .= "            <td align=\"center\">$k</td>";
										$this->salida .= "            <td align=\"center\">$v</td>";
										$this->salida .= "        </tr>";
										$i++;
								}
						}
						$this->salida .= "			     </table><BR>";
						$this->salida .= "				       </td>";
						$this->salida .= "				       </tr>";
						$this->salida .= "			     </table><BR>";
				}
		}
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function FormaDecidirTipoAsignacionCita()
	{
			$this->salida = ThemeAbrirTabla('AGENDA MEDICA - TIPO DE ASIGNACION DE CITA','75%');
			$this->salida .= "            <br>";
			$this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU ASIGNACION CITA</td>";
			$this->salida .= "               </tr>";
			$this->salida .= "               <tr>";
			foreach($_REQUEST as $v=>$v1)
			{
				if($v!='modulo' and $v!='metodo' and $v!='year' and $v!='meses' and $v!='volver' and $v!='TipoDocumento' and $v!='Documento' and $v!='Buscar' and $v!='Cancelar' and $v!='TipoBusqueda')
				{
					$vec[$v]=$v1;
				}
			}
			
			//$accionF=ModuloGetURL('app','AgendaMedicaCoc','user','LlamarFormaBuscarPaciente');
			$acc1=ModuloGetURL('app','AgendaMedicaCoc','user','GuardarCita',$vec);
			//echo $acc1.'<br>';
			$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a title='PERMITE ASIGNACION DE CITAS MANUALES' href=\"$acc1\">ASIGNACION MANUAL</a></td>";
			$this->salida .= "               </tr>";
			
			$this->salida .= "               <tr>";
			$acc2=ModuloGetURL('app','AgendaMedicaCoc','user','DatosAsignacionCitaAutomatica',$vec);
			//echo $acc2.'<br>';
			$this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a title='PERMITE LA ASIGNACION DE CITAS DE MANERA AUTOMATICA' href=\"$acc2\">ASIGNACION AUTOMATICA</a></td>";
			$this->salida .= "               </tr>";
			$this->salida .= "           </table>";
			
			$accion=ModuloGetURL('app','AgendaMedicaCoc','user','main');
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			//echo $accion;
			$this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
			$this->salida .= "</form>";
			$this->salida .= ThemeCerrarTabla();
			return true;//CC 1116234605
	}



/**
* Esta funcion muestra la pantalla final en la asignacion de la cita
*
* @access public
* @return boolean Para identificar que se realizo.
* @param int numero de la orden
*/


	function PantallaFinal($orden,$msg)
	{
		IncludeLib("tarifario_cargos");
		IncludeLib('funciones_facturacion');
		$_SESSION['CONSULTAEXT']['RETORNO']['contenedor']="app";
		$_SESSION['CONSULTAEXT']['RETORNO']['modulo']="AgendaMedicaCoc";
		$_SESSION['CONSULTAEXT']['RETORNO']['tipo']="user";
		$_SESSION['CONSULTAEXT']['RETORNO']['metodo']="EscogerBusqueda";
		$a=$this->BuscarInformacionCitaPrincipal($_SESSION['AsignacionCitas']['citaasignada']);
		$vectorimprimir['idcita']=$_SESSION['AsignacionCitas']['citaasignada'];
		$vectorimprimir['empresa']=$_SESSION['AsignacionCitas']['nomemp'];
		$vectorimprimir['departamento']=$_SESSION['AsignacionCitas']['nomdep'];
		//cambio dar
		$unid=$this->BuscarDatosUnidad($_SESSION['AsignacionCitas']['departamento']);
		$vectorimprimir['departamentoUbicacion']=$unid['ubicacion'];
		//$vectorimprimir['TelefonoCancelacion']=$unid['telefono'];
		$vectorimprimir['TelefonoCancelacion']=$unid['text1'];
		//fin cambio dar
		$vectorimprimir['tipoconsulta']=$_SESSION['AsignacionCitas']['nomcit'];
		$vectorimprimir['profesional']=$_SESSION['AsignacionCitas']['nompro'];
		$vectorimprimir['consultorio']=$a['consultorio_id'];
		$vectorimprimir['ubicacion']=$a['descripcion'];
		$dat=$this->BusquedaResponsable($_SESSION['AsignacionCitas']['Responsable']);
		$vectorimprimir['telefono_cancelacion_cita']=$dat['telefono_cancelacion_cita'];
		$vectorimprimir['Responsable']=$dat['nombreres'];
		$vectorimprimir['Tercero']=$dat['nombreter'];
		$vectorimprimir['DiasCancelacion']=$dat['horasc'];
		//$vectorimprimir['TelefonoCancelacion']=$dat['telefonocan'];
		$vectorimprimir['NombreUsuario']=$this->BusquedaNomUsuario();
		$vectorimprimir['UsuarioId']=UserGetUID();
		$cargo_liq[]=array('tarifario_id'=>$_SESSION['AsignacionCitas']['tarifario'],'cargo'=>$_SESSION['AsignacionCitas']['cargo'],'cantidad'=>$_SESSION['AsignacionCitas']['cantidad'],'autorizacion_int'=>$_SESSION['AsignacionCitas']['NumAutorizacion'],'autorizacion_ext'=>$_SESSION['AsignacionCitas']['NumAutorizacionExt']);
		//cambio dar para el empleador en virtual
		$emp=BuscarEmpleadorOrden($_SESSION['AsignacionCitas']['numero_orden_id']);
		$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq, array(),array(),array(),$_SESSION['AsignacionCitas']['Responsable'] ,$_SESSION['AsignacionCitas']['tipo_afiliado_id'] ,$_SESSION['AsignacionCitas']['rango'] ,$_SESSION['AsignacionCitas']['semanas'],$_SESSION['AsignacionCitas']['servicio'],$_SESSION['AsignacionCitas']['TipoDocumento'],$_SESSION['AsignacionCitas']['Documento'],$emp['tipo_id_empleador'],$emp['empleador_id']);
		//fin cambio dar
		$this->salida .= ThemeAbrirTabla('DATOS PACIENTE');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['nomemp']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomemp'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomemp'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['nomdep']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomdep'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomdep'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['nomcit']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['nomcit'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['nomcit'];
		}
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Identificaci???";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Nombre Paciente";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		if(empty($_SESSION['AsignacionCitas']['Documento']))
		{
			$this->salida .= $_SESSION['LiquidarCitas']['Documento'].' - '.$_SESSION['LiquidarCitas']['TipoDocumento'];
		}
		else
		{
			$this->salida .= $_SESSION['AsignacionCitas']['Documento'].' - '.$_SESSION['AsignacionCitas']['TipoDocumento'];
		}
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			if(!empty($_SESSION['AsignacionCitas']['SegundoNombre']) and !empty($_SESSION['AsignacionCitas']['SegundoApellido']))
			{
				$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['SegundoNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'].' '.$_SESSION['AsignacionCitas']['SegundoApellido'];
			}
			else
			{
				if(empty($_SESSION['AsignacionCitas']['SegundoNombre']))
				{
					if(empty($_SESSION['AsignacionCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'];
					}
					else
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'].' '.$_SESSION['AsignacionCitas']['SegundoApellido'];
					}
				}
				else
				{
					if(empty($_SESSION['AsignacionCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['AsignacionCitas']['PrimerNombre'].' '.$_SESSION['AsignacionCitas']['SegundoNombre'].' '.$_SESSION['AsignacionCitas']['PrimerApellido'];
					}
				}
			}
		}
		else
		{
			if(!empty($_SESSION['LiquidarCitas']['SegundoNombre']) and !empty($_SESSION['LiquidarCitas']['SegundoApellido']))
			{
				$nom=$_SESSION['LiquidarCitas']['PrimerNombre'].' '.$_SESSION['LiquidarCitas']['SegundoNombre'].' '.$_SESSION['LiquidarCitas']['PrimerApellido'].' '.$_SESSION['LiquidarCitas']['SegundoApellido'];
			}
			else
			{
				if(empty($_SESSION['LiquidarCitas']['SegundoNombre']))
				{
					if(empty($_SESSION['LiquidarCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['LiquidarCitas']['PrimerNombre'].' '.$_SESSION['LiquidarCitas']['PrimerApellido'];
					}
					else
					{
						$nom=$_SESSION['LiquidarCitas']['PrimerNombre'].' '.$_SESSION['LiquidarCitas']['PrimerApellido'].' '.$_SESSION['LiquidarCitas']['SegundoApellido'];
					}
				}
				else
				{
					if(empty($_SESSION['LiquidarCitas']['SegundoApellido']))
					{
						$nom=$_SESSION['LiquidarCitas']['PrimerNombre'].' '.$_SESSION['LiquidarCitas']['SegundoNombre'].' '.$_SESSION['LiquidarCitas']['PrimerApellido'];
					}
				}
			}
		}
		$this->salida .= $nom;
		$vectorimprimir['paciente']=$nom;
		$vectorimprimir['identificacion']=$_SESSION['AsignacionCitas']['Documento'].' - '.$_SESSION['AsignacionCitas']['TipoDocumento'];
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .='<br>';
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Profesional";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr class="modulo_list_oscuro">';
			$this->salida .= '<td align="center">';
			$this->salida .= $_SESSION['AsignacionCitas']['nompro'];
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			if(empty($_SESSION['AsignacionCitas']['hora']))
			{
				$_SESSION['AsignacionCitas']['hora']=strtoupper(SIIS_sfrtime($_REQUEST['DiaEspe'].' '.$a[hora],'I'));
			}
			$vectorimprimir['fechacita']=$_SESSION['AsignacionCitas']['hora'];
			$vectorimprimir['liqcita']=$cargo_fact;
			$this->salida .= $_SESSION['AsignacionCitas']['hora'];
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .= '<BR>';
		}
		$citas=$this->CitasEnFechaHora($_REQUEST['DiaEspe'],$a[hora],$_SESSION['AsignacionCitas']['citaasignada']);
		if(!empty($citas))
		{
			$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center" colspan="3">';
			$this->salida .= "<label class='label2_error'>CITAS PARA LA MISMA HORA CON OTRO PROFESIONAL</label>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= '<tr align="center" class="modulo_table_title">';
			$this->salida .= '<td align="center">';
			$this->salida .= "Tipo de Cita";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Profesional";
			$this->salida .= "</td>";
			$this->salida .= '<td align="center">';
			$this->salida .= "Fecha de Cita";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			foreach($citas as $a=>$k)
			{
				$this->salida .= '<tr class="modulo_list_oscuro">';
				$this->salida .= '<td align="center">';
				$this->salida .= $k['descripcion'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= $k['nombre_tercero'];
				$this->salida .= "</td>";
				$this->salida .= '<td align="center">';
				$this->salida .= $k['fecha_completa'];
				$this->salida .= "</td>";
				$this->salida .= "</tr>";
			}
			$this->salida .= "</table>";
			$this->salida .= '<BR>';
		}
		$this->salida .= '<table width="80%" align="center">';
		$this->salida .= '<tr align="center">';
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			$this->salida .= '<label class="label_error">SE ASIGNO LA CITA.</label>';
			$this->salida .= "<label class=\"label_mark\">$msg</label>";
		}
		else
		{
			$this->salida .= '<label class="label">Acerquese a la caja a cancelar la cita.</label>';
		}
		$this->salida .= '</td>';
		$this->salida .= "</tr>";
		//URL PROTOCOLO DAR
		$protocolo=$this->Protocolo();
		if(!empty($protocolo))
		{
				if(file_exists("protocolos/".$protocolo.""))
				{
						$Protocolo=$protocolo;
						$this->salida .= "<script>";
						$this->salida .= "function Protocolo(valor){";
						$this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
						$this->salida .= "}";
						$this->salida .= "</script>";
						$accion="javascript:Protocolo('$Protocolo')";
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td>';
						$this->salida .= "			    <br><table width=\"40%\" align=\"center\" border=\"0\" class=\"normal_10\" 																						cellpadding=\"3\">";
						$this->salida .= "             <tr class=\"modulo_list_claro\">";
						$this->salida .= "             		<td width=\"30%\" class=\"label\">PROTOCOLO</td>";
						$this->salida .= "             		<td><a href=\"$accion\">$Protocolo</a></td>";
						$this->salida .= "             </tr>";
						$this->salida .= "			      </table><br>";
						$this->salida .= '</td>';
						$this->salida .= "</tr>";

				}
		}


		$this->salida .= '</table>';
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td width=\"5%\">ITEM</td>";
		$this->salida.="  <td width=\"5%\">CARGO</td>";
		$this->salida.="  <td width=\"5%\">TARIF.</td>";
		$this->salida.="  <td width=\"30%\">DESCRIPCION</td>";
		$this->salida.="  <td width=\"10%\">SERVICIO</td>";
		$this->salida.="  <td width=\"5%\">CANT.</td>";
		$this->salida.="  <td width=\"10%\">VALOR CARGO</td>";
		$this->salida.="  <td width=\"15%\">TOTAL PACIENTE</td>";
		$this->salida.="  <td width=\"15%\">TOTAL EMPRESA</td>";
		$this->salida.="</tr>";
		$_SESSION['AsignacionCitas']['desservicio']=$this->BuscarDescripcionServicio($_SESSION['AsignacionCitas']['servicio']);
		foreach($cargo_fact[cargos] as $k=>$v)
		{
				if($spy==0)
				{
					$estilo="modulo_list_oscuro";
					$spy=1;
				}
				else
				{
					$estilo="modulo_list_claro";
					$spy=0;
				}
				$this->salida.="<tr class='$estilo' align='center'>";
				$this->salida.="  <td >".$_SESSION['AsignacionCitas']['citaasignada']."</td>";
				$this->salida.="  <td >".$_SESSION['AsignacionCitas']['cargo']."</td>";
				$this->salida.="  <td >".$_SESSION['AsignacionCitas']['tarifario']."</td>";
				$this->salida.="  <td >".$_SESSION['AsignacionCitas']['descripcioncargo']."</td>";
				$this->salida.="  <td >".$_SESSION['AsignacionCitas']['desservicio']."</td>";
				$this->salida.="  <td >".$v[cantidad]."</td>";
				$this->salida.="  <td >".$v[valor_cargo]."</td>";
				$this->salida.="  <td >".$cargo_fact[valor_total_paciente]."</td>";
				$this->salida.="  <td >".$cargo_fact[valor_total_empresa]."</td>";
				$this->salida.="</tr>";
				$cargo_arr[]=array('tarifario_id'=>$cargo_liq[$k][tarifario_id],'descripcion'=>$_SESSION['AsignacionCitas']['descripcioncargo'],'numero_orden_id'=>$_SESSION['AsignacionCitas']['numero_orden_id'],'cargo'=>$cargo_liq[$k][cargo],'des_servicio'=>$_SESSION['AsignacionCitas']['desservicio'],'cantidad'=>1,'valor_cargo'=>$v[valor_cargo],'os_maestro_cargos_id'=>$_SESSION['AsignacionCitas']['os_maestro_cargos_id'],'autorizacion_int'=>$_SESSION['AsignacionCitas']['NumAutorizacion'],'autorizacion_ext'=>$_SESSION['AsignacionCitas']['NumAutorizacionExt']);
				$dat[]=array('tarifario_id'=>$cargo_liq[$k][tarifario_id],'descripcion'=>$_SESSION['AsignacionCitas']['descripcioncargo'],'numero_orden_id'=>$_SESSION['AsignacionCitas']['numero_orden_id'],'cargo'=>$cargo_liq[$k][cargo],'os_maestro_cargos_id'=>$_SESSION['AsignacionCitas']['os_maestro_cargos_id']);
		}
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= '<BR>';
		$this->salida .= '<table width="80%" align="center">';
		$this->salida .= '<tr align="center">';
		$this->salida .= '<td align="center">';
		if(!empty($_SESSION['AsignacionCitas']['cita']))
		{
			if(!empty($_SESSION['AsignacionCitas']['NumeroOrden']))
			{
				 $accion=ModuloGetURL('app','AgendaMedicaCoc','','PantallaOrdenes');
			}
			else
			{
				$accion=ModuloGetURL('app','AgendaMedicaCoc','','EscogerBusqueda');
			}
		}
		else
		{
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente');
		}
		//ACCION PARA CITAS ASIGNADAS AUTOMATICAMENTE
		if($msg)
		{
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','main');
		}
		//FIN
		unset($_SESSION['CAJA']);
		$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .='<input type="submit" name="Continuar" value="Siguiente" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .= '</td>';
		//recordar para mandar
		//$_SESSION['AsignacionCitas']['os_maestro_cargos_id']
		$this->BuscarPermiso();
		if(!empty($_SESSION['AsignacionCitas']['cuantascajas']))
		{
			if($_SESSION['AsignacionCitas']['cuantascajas']==1)
			{
				foreach($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'] as $k=>$v)
				{
					foreach($v as $t=>$s)
					{
						foreach($s as $m=>$n)
						{
							$datos1=$n;
							break;
						}
						break;
					}
					break;
				}
				$_SESSION['AsignacionCitas']['Informacion']['datos']=$dat;
				$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$_SESSION['AsignacionCitas']['TipoDocumento'],'id'=>$_SESSION['AsignacionCitas']['Documento'],'afiliado'=>$_SESSION['AsignacionCitas']['tipo_afiliado_id'],'rango'=>$_SESSION['AsignacionCitas']['rango'],'sem'=>$_SESSION['AsignacionCitas']['semanas'],'plan'=>$_SESSION['AsignacionCitas']['Responsable'],'auto'=>$_SESSION['AsignacionCitas']['NumAutorizacion'],'servicio'=>$_SESSION['AsignacionCitas']['servicio'],'depto'=>$_SESSION['AsignacionCitas']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));

				//$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$_SESSION['AsignacionCitas']['TipoDocumento'],'id'=>$_SESSION['AsignacionCitas']['Documento'],'afiliado'=>$_SESSION['AsignacionCitas']['tipo_afiliado_id'],'rango'=>$_SESSION['AsignacionCitas']['rango'],'sem'=>$_SESSION['AsignacionCitas']['semanas'],'plan'=>$_SESSION['AsignacionCitas']['Responsable'],'auto'=>$_SESSION['AsignacionCitas']['NumAutorizacion'],'servicio'=>$_SESSION['AsignacionCitas']['servicio'],'datos'=>$dat,'depto'=>$_SESSION['AsignacionCitas']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));
			}
			else
			{
				$accion=ModuloGetURL('app','AgendaMedicaCoc','user','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$_SESSION['AsignacionCitas']['TipoDocumento'],'id'=>$_SESSION['AsignacionCitas']['Documento'],'afiliado'=>$_SESSION['AsignacionCitas']['tipo_afiliado_id'],'rango'=>$_SESSION['AsignacionCitas']['rango'],'sem'=>$_SESSION['AsignacionCitas']['semanas'],'plan'=>$_SESSION['AsignacionCitas']['Responsable'],'auto'=>$_SESSION['AsignacionCitas']['NumAutorizacion'],'servicio'=>$_SESSION['AsignacionCitas']['servicio'],'depto'=>$_SESSION['AsignacionCitas']['departamento'],'DiaEspe'=>$_REQUEST['DiaEspe']));
				$_SESSION['AsignacionCitas']['Informacion']['datos']=$dat;
				//$accion=ModuloGetURL('app','AgendaMedicaCoc','user','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom, 'tipoid'=>$_SESSION['AsignacionCitas']['TipoDocumento'],'id'=>$_SESSION['AsignacionCitas']['Documento'],'afiliado'=>$_SESSION['AsignacionCitas']['tipo_afiliado_id'],'rango'=>$_SESSION['AsignacionCitas']['rango'],'sem'=>$_SESSION['AsignacionCitas']['semanas'],'plan'=>$_SESSION['AsignacionCitas']['Responsable'],'auto'=>$_SESSION['AsignacionCitas']['NumAutorizacion'],'servicio'=>$_SESSION['AsignacionCitas']['servicio'],'depto'=>$_SESSION['AsignacionCitas']['departamento'],'datos'=>$dat,'DiaEspe'=>$_REQUEST['DiaEspe']));
			}
			$this->salida .= '<td align="center">';
			//$this->salida.="<a href=\"$accion\">Pago en Caja R???ida</a>";
      //
      $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <input class=\"input-submit\" type=\"submit\" name=\"pagocaja\" value=\"Pago en Caja\">";
      $this->salida .= "  </form>";
      //
			$this->salida .= '</td>';
		}
		$this->salida .= '</table>';
		$this->salida .= '<table width="40%" align="center">';
		$this->salida .= '<tr class=label>';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','FuncionParaImprimir',$vectorimprimir);
		$this->salida .= '<td align="center">';
		$this->salida.="<a href=\"$accion\">Imprimir POS</a>";
		$this->salida .= '</td>';
		$this->salida .= '<td align="center">';
		$reporte = new GetReports();
		$opciones=array();
		$this->salida.=$reporte->GetJavaReport('app','AgendaMedicaCoc','Recibo',$vectorimprimir,$opciones);
		$this->salida.="<a href=\"javascript:".$reporte->GetJavaFunction().";\">Imprimir PDF</a>";
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= '<BR>';
		$this->salida.='';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


	function PantallaFinalError($msg)
	{
		$this->salida .= ThemeAbrirTabla('CITA AUTOMATICA INCOMPLETA');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="center">';
		$this->salida .= $msg.'<br>';
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		
		
		if($msg)
		{
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','main');
		}
		
		$this->salida .= '<tr >';
		$this->salida .= '<td align="center">';
		$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
		$this->salida .='<input type="submit" name="Continuar" value="VOLVER" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .= '</td>';
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


//Funciones Cumplimiento de Cita.
/**
* Esta funcion muestra el listado de derechos de un paciente para el cumplimiento de una cita
*
* @access public
* @return boolean Para identificar que se realizo.
*/

 	function CumplimientoCita()
	{
		unset($_SESSION['AsignacionCitas']);
		unset($_SESSION['LiquidarCitas']);
		unset($_SESSION['CumplirCita']);
		SessionDelVar('CITASMES');
		$url[0]='app';
		$url[1]='AgendaMedicaCoc';
		$url[2]='user';
		$url[3]='BuscarCita';
		$url[4]='Citas';
		if($this->TipoConsultaCumplimiento($url)==false)
		{
			return false;
		}
   return true;
	}




/**
* Esta funcion permite realizar la busqueda de las citas por nombre o identificacion
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function BuscarCita()
	{
	  $_REQUEST['nombres']=strtoupper($_REQUEST['nombres']);
		unset($_SESSION['CAJA']);
		$_SESSION['CONSULTAEXT']['RETORNO']['contenedor']="app";
		$_SESSION['CONSULTAEXT']['RETORNO']['modulo']="AgendaMedicaCoc";
		$_SESSION['CONSULTAEXT']['RETORNO']['tipo']="user";
		$_SESSION['CONSULTAEXT']['RETORNO']['metodo']="BuscarCita";
		if(empty($_SESSION['CumplirCita']['cita']))
		{
			$_SESSION['CumplirCita']['cita']=$_REQUEST['Citas']['tipo_consulta_id'];
			$_SESSION['CumplirCita']['departamento']=$_REQUEST['Citas']['departamento'];
			$_SESSION['CumplirCita']['empresa']=$_REQUEST['Citas']['empresa_id'];
			$_SESSION['CumplirCita']['nomcit']=$_REQUEST['Citas']['descripcion3'];
			$_SESSION['CumplirCita']['nomdep']=$_REQUEST['Citas']['descripcion2'];
			$_SESSION['CumplirCita']['nomemp']=$_REQUEST['Citas']['descripcion1'];
			$_SESSION['CumplirCita']['cargo_cups']=$_REQUEST['Citas']['cargo_cups'];
		}

		$this->salida = ThemeAbrirTabla('CUMPLIMIENTO DE CITAS');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$sab=true;
		if(!empty($_REQUEST['Documento']) || !empty($_REQUEST['nombres']) || !empty($_REQUEST['apellidos']))
		{
			$incumplidas=$this->CitasIncumplidasPaciente($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
			//$Cita=$this->CitasPacienteAtender();
			$Cita=$this->CitasPacienteAtenderNombre();
			$citascom=$this->CitasPacienteAtender2();
			if(empty($Cita))
			{
				$doc=$_REQUEST['Documento'];
				$sab=false;
				$_REQUEST['Documento']='';
			}
		}
    /*if(!empty($_REQUEST['nombres']))
		{
			//$incumplidas=$this->CitasIncumplidasPaciente($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
			$Cita=$this->CitasPacienteAtenderNombre($_REQUEST['nombres']);
			$citascom=$this->CitasPacienteAtenderNombre2();
		}*/
		$otrastipos=true;
		if(empty($citascom))
		{
			$otrastipos=false;
		}
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','BuscarCita');
		$this->salida .= "		<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<table width=\"50%\" border=\"0\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<fieldset><legend class=\"field\">FILTRO DE BUSQUEDA</legend>";
		$this->salida .= "		<table width=\"90%\" align=\"center\">";
		$this->salida .= "  	<tr>";
		$this->salida .= "  	<td class=\"label\">";
		$this->salida .= "      TIPO DOCUMENTO: ";
		$this->salida .= "    </td>";
		$this->salida .= "  	<td>";
		$this->salida .= "   <select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,'False',$_REQUEST['TipoDocumento']);
		$this->salida .= "		</select>";
		$this->salida .= "  	</td>";
		$this->salida .= "  	</tr>";
		$this->salida .= "  	<tr>";
		$this->salida .= "		<td class=\"".$this->SetStyle("Documento")."\">";
		$this->salida .= "		DOCUMENTO: ";
		$this->salida .= "		</td>";
		$this->salida .= "		<td>";
		if($doc){$val=$doc;}else{$val=$_REQUEST['Documento'];}
		$this->salida .= "		<input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$val."\">";
		$this->salida .= "		</td>";
		$this->salida .= "  	</tr>";
		$this->salida .=      $this->SetStyle("MensajeError");
		$this->salida .= "  	<tr>";
		$this->salida .= "		<td class=\"label\">";
		$this->salida .= "		NOMBRES:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td>";
		$this->salida .= "		<input type=\"text\" class=\"input-text\" name=\"nombres\" value=\"".$_REQUEST['nombres']."\">";
		$this->salida .= "		</td>";
		$this->salida .= "  	</tr>";
		$this->salida .= "  	<tr>";
		$this->salida .= "		<td class=\"label\">";
		$this->salida .= "		APELLIDOS:";
		$this->salida .= "		</td>";
		$this->salida .= "		<td>";
		$this->salida .= "		<input type=\"text\" class=\"input-text\" name=\"apellidos\" value=\"".$_REQUEST['apellidos']."\">";
		$this->salida .= "		</td>";
		$this->salida .= "  	</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "		<td align=\"center\">";
		$this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">";
    $this->salida .= "		</td>";
		$this->salida .= "    </form>";
		$accion1=ModuloGetURL('app','AgendaMedicaCoc','','CumplimientoCita');
		$this->salida .= "		<form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
		$this->salida .= "		<td align=\"center\">";
    $this->salida .= "    <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\">";
    $this->salida .= "		</td>";
		$this->salida .= "    </form>";
		$this->salida .= "    </table>";
		$this->salida .= "	  </fieldset></td></tr>";
		$this->salida .= "	  </table><br>";
		if(!empty($_REQUEST['Documento']) or !empty($_REQUEST['nombres']))
		{
			if(!empty($Cita))
			{
				IncludeLib("tarifario_cargos");
				$this->BuscarPermiso();
				$this->salida.='<table align="center" width="80%" border="0" class="modulo_table_list">';
				$this->salida.='<tr align="center" class="modulo_table_title">';
				$this->salida.='<td align="center">';
				$this->salida.='Fecha';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Paciente';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Profesional';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Estado';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Acci???';
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i=0;
				$spy=0;
				while($i<sizeof($Cita[0]))
				{
					if($Cita[0][$i-1]!=$Cita[0][$i]-1 or $Cita[3][$i-1]!=$Cita[3][$i])
					{
						if($spy==0)
						{
							$this->salida.='<tr class="modulo_list_claro">';
							$spy=1;
						}
						else
						{
							$this->salida.='<tr class="modulo_list_oscuro">';
							$spy=0;
						}
						$this->salida.='<td align="center">';
						if($Cita[3][$i]==date("Y-m-d"))
						{
							$this->salida.=$Cita[1][$i];
						}
						else
						{
							$this->salida.='<label class="label_error">'.$Cita[1][$i].'</label>';
						}
						$this->salida.='</td>';
						$this->salida.='<td align="center">';
						$this->salida.=$Cita[20][$i].' - '.$Cita[19][$i].' '.$Cita[22][$i];
						$this->salida.='</td>';
            $this->salida.='<td align="center">';
            $this->salida.=$Cita[2][$i];
						$this->salida.='</td>';
						$this->salida.='<td align="center">';
						if($Cita[16][$i]==1)
						{
							$this->salida.="ACTIVA";
						}
						elseif($Cita[16][$i]==2)
						{
							$this->salida.="PAGA";
						}
						elseif($Cita[16][$i]==3)
						{
							$this->salida.="CUMPLIDA";
						}
						elseif($Cita[16][$i]==5)
						{
							$this->salida.="DERECHOS VALIDADOS";
						}
						$this->salida.='</td>';
						$this->salida.='<td align="center">';
						if($Cita[3][$i]==date("Y-m-d"))
						{
							if($Cita[4][$i]==1)
							{
								$vec1=$vec;
								$vec1=$vec;
								if(empty($_REQUEST['Documento']))
								{
									$vec1['CUMPLIR']['paciente']=$Cita[19][$i];
									$vec1['CUMPLIR']['tipo_id_paciente']=$Cita[20][$i];
								}
								else
								{
									$vec1['CUMPLIR']['paciente']=$_REQUEST['Documento'];
									$vec1['CUMPLIR']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
								}
								$vec1['CUMPLIR']['plan']=$Cita[6][$i];
								$vec1['CUMPLIR']['cargo_cups']=$Cita[21][$i];
								$vec1['CUMPLIR']['cargo']=$Cita[7][$i];
								$vec1['CUMPLIR']['tarifario']=$Cita[8][$i];
								$vec1['CUMPLIR']['numero_orden_id']=$Cita[15][$i];
								$vec1['CUMPLIR']['orden_servicio_id']=$Cita[18][$i];
								$accion=ModuloGetURL('app','AgendaMedicaCoc','','AutorizarPaciente',$vec1);
								$this->salida.='<a href="'.$accion.'">Revisar Derechos</a>';
							}
							else
							{
								if($Cita[4][$i]==3)
								{
									if(empty($_REQUEST['Documento']))
									{
										$vec1['CUMPLIR']['paciente']=$Cita[19][$i];
										$vec1['CUMPLIR']['tipo_id_paciente']=$Cita[20][$i];
									}
									else
									{
										$vec1['CUMPLIR']['paciente']=$_REQUEST['Documento'];
										$vec1['CUMPLIR']['tipo_id_paciente']=$_REQUEST['TipoDocumento'];
									}
									$vec1['CUMPLIR']['plan']=$Cita[6][$i];
									$vec1['CUMPLIR']['numero_orden_id']=$Cita[15][$i];
									$accion=ModuloGetURL('app','AgendaMedicaCoc','','PedirDatosPaciente',$vec1);
									$this->salida.='<a href="'.$accion.'">Datos Paciente</a>';
								}
								else
								{
									if($Cita[4][$i]==2)
									{
										$this->salida.='<label class="label">Acerquese al consultorio</label>';
									}
									else
									{
									  if($Cita[4][$i]!=8)
										{
											if(!empty($_SESSION['CumplirCita']['cuantascajas']))
											{
												$cargo_liq[0]=array('tarifario_id'=>$Cita[8][$i],'cargo'=>$Cita[7][$i],'cantidad'=>1,'autorizacion_int'=>$Cita[13][$i],'autorizacion_ext'=>$Cita[14][$i]);
												$servicio=$this->BusquedaServicio($_SESSION['CumplirCita']['departamento']);
												//$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq, array() ,array() ,array(),$Cita[6][$i] ,$Cita[10][$i],$Cita[11][$i], $Cita[12][$i], $servicio, $_REQUEST['TipoDocumento'], $_REQUEST['Documento'],'aasa');
												$descripcionservicio=$this->BuscarDescripcionServicio($servicio);
												$descripcioncargo=$this->BusquedaDescripcionCargo($Cita[8][$i],$Cita[7][$i]);
												$cargo_arr[0]=array('tarifario_id'=>$Cita[8][$i],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$Cita[15][$i],'cargo'=>$Cita[7][$i],'des_servicio'=>$descripcionservicio,'cantidad'=>1,'valor_cargo'=>$cargo_fact[0][valor_cargo],'os_maestro_cargos_id'=>$Cita[17][$i],'autorizacion_int'=>$Cita[13][$i],'autorizacion_ext'=>$Cita[14][$i]);
												$dat[0]=array('tarifario_id'=>$cargo_liq[0][tarifario_id],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$Cita[15][$i],'cargo'=>$cargo_liq[0][cargo],'os_maestro_cargos_id'=>$Cita[17][$i]);
												$nom=$this->BuscarNombrePaciente($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);
												if($_SESSION['CumplirCita']['cuantascajas']==1)
												{
													foreach($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'] as $k=>$v)
													{
														foreach($v as $t=>$s)
														{
															foreach($s as $m=>$n)
															{
																$datos1=$n;
																break;
															}
															break;
														}
														break;
													}
													//$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$_REQUEST['TipoDocumento'],'id'=>$_REQUEST['Documento'],'afiliado'=>$Cita[10][$i],'rango'=>$Cita[11][$i],'sem'=>$Cita[12][$i],'plan'=>$Cita[6][$i],'auto'=>$Cita[13][$i],'servicio'=>$servicio,'datos'=>$dat,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));
													//--$accion=ModuloGetURL('app','AgendaMedicaCoc','user','MenuCaja',array('ircaja'=>'1','arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$_REQUEST['TipoDocumento'],'id'=>$_REQUEST['Documento'],'afiliado'=>$Cita[10][$i],'rango'=>$Cita[11][$i],'sem'=>$Cita[12][$i],'plan'=>$Cita[6][$i],'auto'=>$Cita[13][$i],'servicio'=>$servicio,'datos'=>$dat,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));

													//$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$_REQUEST['TipoDocumento'],'id'=>$_REQUEST['Documento'],'afiliado'=>$Cita[10][$i],'rango'=>$Cita[11][$i],'sem'=>$Cita[12][$i],'plan'=>$Cita[6][$i],'auto'=>$Cita[13][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));
													$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$_REQUEST['TipoDocumento'],'id'=>$_REQUEST['Documento'],'afiliado'=>$Cita[10][$i],'rango'=>$Cita[11][$i],'sem'=>$Cita[12][$i],'plan'=>$Cita[6][$i],'auto'=>$Cita[13][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));

												 $_SESSION['CAJA']['datos']=$dat;

													$this->salida.='<a href="'.$accion.'">Caja Rapida</a>';
												}
												else
												{
													$cargo_liq[0]=array('tarifario_id'=>$Cita[8][$i],'cargo'=>$Cita[7][$i],'cantidad'=>1,'autorizacion_int'=>$Cita[13][$i],'autorizacion_ext'=>$Cita[14][$i]);
													$servicio=$this->BusquedaServicio($_SESSION['CumplirCita']['departamento']);
													//$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(), $Cita[6][$i] ,$Cita[10][$i] ,$Cita[11][$i] ,$Cita[12][$i],$servicio);
													$descripcionservicio=$this->BuscarDescripcionServicio($servicio);
													$descripcioncargo=$this->BusquedaDescripcionCargo($Cita[8][$i],$Cita[7][$i]);
													$cargo_arr[0]=array('tarifario_id'=>$Cita[8][$i],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$Cita[15][$i],'cargo'=>$Cita[7][$i],'des_servicio'=>$descripcionservicio,'cantidad'=>1,'valor_cargo'=>$cargo_fact[cargos][0][valor_cargo],'os_maestro_cargos_id'=>$Cita[17][$i],'autorizacion_int'=>$Cita[13][$i],'autorizacion_ext'=>$Cita[14][$i]);
													$dat[0]=array('tarifario_id'=>$cargo_liq[0][tarifario_id],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$Cita[15][$i],'cargo'=>$cargo_liq[0][cargo],'os_maestro_cargos_id'=>$Cita[17][$i]);
													$nom=$this->BuscarNombrePaciente($_REQUEST['TipoDocumento'],$_REQUEST['Documento']);


													//$accion=ModuloGetURL('app','AgendaMedicaCoc','','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$_REQUEST['TipoDocumento'],'id'=>$_REQUEST['Documento'],'afiliado'=>$Cita[10][$i],'rango'=>$Cita[11][$i],'sem'=>$Cita[12][$i],'plan'=>$Cita[6][$i],'auto'=>$Cita[13][$i],'servicio'=>$servicio,'datos'=>$dat,'depto'=>$_SESSION['CumplirCita']['departamento']));
													$accion=ModuloGetURL('app','AgendaMedicaCoc','','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$_REQUEST['TipoDocumento'],'id'=>$_REQUEST['Documento'],'afiliado'=>$Cita[10][$i],'rango'=>$Cita[11][$i],'sem'=>$Cita[12][$i],'plan'=>$Cita[6][$i],'auto'=>$Cita[13][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento']));

													 $_SESSION['CAJA']['datos']=$dat;
													$this->salida.='<a href="'.$accion.'">Caja Rapida</a>';
												}
											}
											else
											{
												$this->salida.='Acerquese a la caja a cancelar la cita.';
											}
										}
										else
										{
									    $this->salida.='Orden Anulada Por Vencimiento.';
										}
									}
								}
							}
						}
						else
						{
							$this->salida.='<label class="label_error">Su cita no es para el d??? de hoy</label>';
						}
						$this->salida.='</td>';
						$this->salida.='</tr>';
					}
					$i++;
				}
				$this->salida.='</table>';
				$this->salida .='<br>';
			}
		}

		if($otrastipos==true)
		{
			if(!empty($citascom))
			{
				$this->salida.='<table align="center" width="80%" border="0" class="modulo_table_list">';
				$this->salida.='<tr align="center" class="modulo_table_title">';
				$this->salida.='<td align="center">';
				$this->salida.='Fecha';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Profesional';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Estado';
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.='Tipo Consulta';
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i=0;
				$spy=0;
				while($i<sizeof($citascom[0]))
				{
					if($citascom[0][$i-1]!=$citascom[0][$i]-1 or $citascom[3][$i-1]!=$citascom[3][$i])
					{
						if($spy==0)
						{
							$this->salida.='<tr class="modulo_list_claro">';
							$spy=1;
						}
						else
						{
							$this->salida.='<tr class="modulo_list_oscuro">';
							$spy=0;
						}
						$this->salida.='<td align="center">';
						if($citascom[3][$i]==date("Y-m-d"))
						{
							$this->salida.=$citascom[1][$i];
						}
						else
						{
							$this->salida.='<label class="label_error">'.$citascom[1][$i].'</label>';
						}
						$this->salida.='</td>';
						$this->salida.='<td align="center">';
						$this->salida.=$citascom[2][$i];
						$this->salida.='</td>';
						$this->salida.='<td align="center">';
						if($Cita[16][$i]==1)
						{
							$this->salida.="ACTIVA";
						}
						elseif($Cita[16][$i]==2)
						{
							$this->salida.="PAGA";
						}
						elseif($Cita[16][$i]==3)
						{
							$this->salida.="CUMPLIDA";
						}
						elseif($Cita[16][$i]==5)
						{
							$this->salida.="DERECHOS VALIDADOS";
						}
						$this->salida.='</td>';
						$this->salida.='<td align="center">';
						$this->salida.=$citascom[18][$i];
						$this->salida.='</td>';
						$this->salida.='</tr>';
					}
					$i++;
				}
				$this->salida.='</table>';
				$this->salida .='<br>';
			}
		}
		else
		{
			if($sab==false)
			{
				$this->salida.='<table align="center" width="80%" border="0">';
				$this->salida.='<tr align="center">';
				$this->salida.='<td align="center">';
				$this->salida.='<label class="label_error">No existen citas para cumplir al paciente ';
				if($doc && $_REQUEST['TipoDocumento']){
				$this->salida .= ''.$doc.' - '.$_REQUEST['TipoDocumento'].'';
				}
				if($_REQUEST['nombres']){
        $this->salida .= ' '.$_REQUEST['nombres'].'';
				}
				$this->salida .= '</label>';
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$this->salida.='</table>';
				$this->salida .='<br>';
			}
		}
		if(!empty($incumplidas))
		{
			$this->salida .='<br>';
			$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "<tr><td><fieldset><legend class=\"field\">CITAS INCUMPLIDAS</legend>";
			$this->salida.='<table align="center" width="93%" border="0" class="modulo_table_list">';
			$this->salida.='<tr align="center" class="modulo_table_title">';
			$this->salida.='<td align="center">';
			$this->salida .= 'Fecha';
			$this->salida.='</td>';
			$this->salida.='<td align="center">';
			$this->salida .= 'Profesional';
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$i=0;
			while($i<sizeof($incumplidas[0]))
			{
				if($spy==0)
				{
					$this->salida.='<tr class="modulo_list_claro">';
					$spy=1;
				}
				else
				{
					$this->salida.='<tr class="modulo_list_oscuro">';
					$spy=0;
				}
				$this->salida.='<td align="center">';
				$this->salida.=$incumplidas[0][$i];
				$this->salida.='</td>';
				$this->salida.='<td align="center">';
				$this->salida.=$incumplidas[1][$i];
				$this->salida.='</td>';
				$this->salida.='</tr>';
				$i++;
			}
			$this->salida.='</table>';
			$this->salida .= "</fieldset></td></tr></table>";
		}
		$profeconsul=$this->ProfeConsul();
		if($profeconsul==false)
		{
			$this->salida .='<br>';
			$this->salida .='<table border="0" align="center" width="50%">';
			$this->salida .='<tr>';
			$this->salida .='<td align="center">';
			$this->salida .= '<label class="label_error">No existen citas para cumplir</label>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
			$this->salida .='<tr>';
			$this->salida .='<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','CumplimientoCita');
			$this->salida .='<form name="volver" method="post" action="'.$accion.'">';
			$this->salida .='<input type="submit" name="volver" value="Volver" class="input-submit">';
			$this->salida .='</form>';
			$this->salida .='</td>';
			$this->salida .='</tr>';
			$this->salida .='</table>';
			$this->salida .='<br>';
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		$this->salida.='<table align="center" width="80%" border="0" class="modulo_table_list">';
		$this->salida.='<tr align="center" class="modulo_table_title">';
		$this->salida.='<td align="center">';
		$this->salida.='Medico';
		$this->salida.='</td>';
		$this->salida.='<td align="center">';
		$this->salida.='Consultorio';
		$this->salida.='</td>';
		$this->salida.='<td align="center">';
		$this->salida.='Descripci???';
		$this->salida.='</td>';
		$this->salida.='</tr>';
		$i=0;
		while($i<sizeof($profeconsul[0]))
		{
			if($spy==0)
			{
				$this->salida.='<tr class="modulo_list_claro">';
				$spy=1;
			}
			else
			{
				$this->salida.='<tr class="modulo_list_oscuro">';
				$spy=0;
			}
			$vec1['profesional']=$profeconsul[3][$i].",".$profeconsul[4][$i];
			$vec1['DiaEspe']=date("Y-m-d");
			$vec1['nompro']=$profeconsul[0][$i];
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','ListadoCitasCumplidas',$vec1);
			$this->salida.='<td>';
			$this->salida.='<a href="'.$accion.'">';
			$this->salida.=$profeconsul[0][$i];
			$this->salida.='</a>';
			$this->salida.='</td>';
			$this->salida.='<td>';
			$this->salida.=$profeconsul[1][$i];
			$this->salida.='</td>';
			$this->salida.='<td>';
			$this->salida.=$profeconsul[2][$i];
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$i++;
		}
		$this->salida.='</table>';
		$this->salida.='<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




/**
* Esta funcion permite realizar la busqueda de las citas por profesional
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function ListadoCitasCumplidas()
	{
		unset($_SESSION['ADMISION']);
		unset($_SESSION['PACIENTES']);
		unset($_SESSION['TRIAGE']);
		unset($_SESSION['AsignacionCitas']);
		unset($_SESSION['AsignacionCitas']);
		unset($_SESSION['CAJA']);
		unset($_SESSION['AgendaMedicaCoc']['CitaPrioritaria']);
		$_SESSION['CONSULTAEXT']['RETORNO']['contenedor']="app";
		$_SESSION['CONSULTAEXT']['RETORNO']['modulo']="AgendaMedicaCoc";
		$_SESSION['CONSULTAEXT']['RETORNO']['tipo']="user";
		$_SESSION['CONSULTAEXT']['RETORNO']['metodo']="ListadoCitasCumplidas";
		$this->BuscarPermiso();
		IncludeLib("tarifario_cargos");
		if(empty($_SESSION['CumplirCita']['profesional']) or ($_SESSION['CumplirCita']['profesional']!=$_REQUEST['profesional'] and !empty($_REQUEST['profesional'])))
		{
			$_SESSION['CumplirCita']['nompro']=$_REQUEST['nompro'];
			$_SESSION['CumplirCita']['profesional']=$_REQUEST['profesional'];
		}
		
		$js = "<script>\n";
		$js .= "function MostrarTurnos(hora){\n";
		//$js .= "alert(hora);";
		$js .= "	e = document.getElementById('turnos'+hora);\n";
		//$js .= "alert(e);";
		$js .= "	if(e.style.display == \"block\"){\n";
		$js .= "		e.style.display = \"none\";\n";
		$js .= "	 }else{\n";  
		$js .= "		e.style.display = \"block\";\n";
		$js .= "	}\n";  
		$js .= "}\n";  
		$js .= "</script>\n";
		$this->salida = "$js";
		$this->salida .= ThemeAbrirTabla('CUMPLIMIENTO DE CITAS');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Fecha de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nompro'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$a=explode("-",$_REQUEST['DiaEspe']);
		if(empty($_SESSION['CumplirCita']['fechacita']))
		{
			$_SESSION['CumplirCita']['DiaEspe']=$_REQUEST['DiaEspe'];
			$_SESSION['CumplirCita']['fechacita']=strftime("%d de %B de %Y",mktime(0,0,0,$a[1],$a[2],$a[0]));
		}

		$this->salida .= $_SESSION['CumplirCita']['fechacita'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<br><br>";
		$datos=$this->ListadoCitas();
//echo '<pre>';
//print_r($datos);
		$this->salida .= '<table width="80%" align="center" border="1" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center" width="10%">';
		$this->salida .= "Hora";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Pacientes";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Observaci???";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center" width="20%">';
		$this->salida .= "Acci???";
		$this->salida .= "</td>";
		/*$this->salida .= '<td align="center" width="20%">';
		$this->salida .= "Responsable";
		$this->salida .= "</td>";*/
		$this->salida .= "</tr>";
		$i=0;
		$this->SetJavaScripts('DatosPaciente');
		/*foreach($_REQUEST as $v=>$dato)
		{
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID')
			{
				$vec[$v]=$dato;
			}
		}*/
		$AgendaCitaPadreAnt=-1;
    $identificacionAnt=-1;
		while($i<sizeof($datos[0]))
		{ unset($vec1);
		  $AgendaCitaPadre=$datos[23][$i];      
			if($AgendaCitaPadre!=$AgendaCitaPadreAnt || empty($AgendaCitaPadre) || ($AgendaCitaPadre==$AgendaCitaPadreAnt && !empty($AgendaCitaPadre) && $datos[3][$i].' '.$datos[2][$i]!=$identificacionAnt)){
				$conta=0;
				foreach($datos[23] as $indice=>$AgendaId){
					if($AgendaId==$AgendaCitaPadre && $datos[3][$i].' '.$datos[2][$i]==$datos[3][$indice].' '.$datos[2][$indice]){
						$conta++;
					}
				}
				if($spy==0){$this->salida .= '<tr class="modulo_list_oscuro">';	$spy=1;}
				else{$this->salida .= '<tr class="modulo_list_claro">';$spy=0;}
				
				if($datos[25][$i] > 1 AND $datos[0][$i] <> $datos[0][$i-1])
				{
					$this->salida .= "<td>";
					$this->salida .= "<a href = \"javascript:MostrarTurnos('".$datos[0][$i]."');\" title=\"TURNO PARA ".$datos[25][$i]." PACIENTES.\">".$datos[0][$i]."</a>";
					$this->salida .= "</td>";
					$this->salida .= "<td rowspan=\"$conta\">";
					$this->salida .= "<table width=\"80%\" align=\"center\" border=\"1\" class=\"modulo_list_claro\">";
					$datosMultiCitas=$this->VerMultiCitas($datos[24][$i],$datos[0][$i]);
					if($datosMultiCitas > 0 )
					{
						for($m=$i; $m<$datosMultiCitas+$i;)
						{
							$l = $m;
							while($datos[0][$m] == $datos[0][$l])
							{
								$this->salida .= "	<tr>";
								$this->salida .= "		<td rowspan=\"$conta\">";
								$nom=$this->NombrePaciente($datos[3][$l],$datos[2][$l]);
								$dato=RetornarWinOpenDatosPaciente($datos[3][$l],$datos[2][$l],$nom);
								$this->salida .=$dato;
								$this->salida .= "		</td>";
								$this->salida .= "	</tr>";
								$l++;
							}
							$m = $l;
						}
					}
					$this->salida .= "</table>";
					$this->salida .= "</td>";
					//
					$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
					$this->salida.=$datos[7][$i];
					$this->salida .= "</td>";
					//
					//
					$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
					$this->salida .= "	<div id=\"turnos".$datos[0][$i]."\" style=\"display:none\">";
						$cargo_liq[0]=array('tarifario_id'=>$datos[11][$i],'cargo'=>$datos[10][$i],'cantidad'=>1,'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i]);
						//$cargo_arr[0]=array('tarifario_id'=>$datos[11][$i],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$datos[10][$i],'des_servicio'=>$descripcionservicio,'cantidad'=>$cargo_fact[cargos][0][cantidad],'valor_cargo'=>$cargo_fact[cargos][0][valor_cargo],'os_maestro_cargos_id'=>$datos[19][$i],'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i],'valor_no_cubierto'=>$cargo_fact[cargos][0][valor_no_cubierto]);
						$dat[0]=array('tarifario_id'=>$cargo_liq[0][tarifario_id],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$cargo_liq[0][cargo],'os_maestro_cargos_id'=>$datos[19][$i]);
						$argu=array('profesional'=>$_SESSION['CumplirCita']['profesional'],'DiaEspe'=>$_SESSION['CumplirCita']['DiaEspe'],'nompro'=>$_SESSION['CumplirCita']['nompro'],$tmp=>$datos[8][$i]);
						$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$argu);
						$_SESSION['CAJA']['datos']=$dat;
					$datosMultiCitas=$this->VerMultiCitas($datos[24][$i],$datos[0][$i]);
					if($datosMultiCitas > 0)
					{
						//
						for($k=0; $k<$datosMultiCitas; $k++)
						{
							$this->salida.="<a><label class=\"label_mark\">ASIGNADO</label></a>";
							$this->salida.="<br>";
						}
						//$j1 = $k;
						for($k=0; $k<$datos[25][$i]-$datosMultiCitas; $k++)
						{
							if($datos[0][$i] >= date('H:i'))
							{
								$this->salida.="<a href=\"$accion\">Asignar</a>";
							}
							else
							{
								$this->salida.='Hora Vencida';
							}
							$this->salida.="<br>";
						}
						//$j = $j1 + $k;
						//
					}
					else
					{
						for($j=0; $j<$datos[25][$i]; $j++)
						{
							$this->salida .= "	<table width=\"80%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">";
							$this->salida .= "		<tr>";
							$this->salida .= "			<td rowspan=\"$conta\">";
							if($datos[0][$i] >= date('H:i')){
									//$vec1=$vec;
									$tmp='seleccion'.$i;
									//$vec1[$tmp]=$datos[8][$i];
									//$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$vec1);
									//cambio dar
									$argu=array('profesional'=>$_SESSION['CumplirCita']['profesional'],'DiaEspe'=>$_SESSION['CumplirCita']['DiaEspe'],'nompro'=>$_SESSION['CumplirCita']['nompro'],$tmp=>$datos[8][$i]);
									$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$argu);
									//fin cambio dar
									$this->salida.="<a href=\"$accion\" title=\"TURNO PARA ".$datos[25][$i]." PACIENTES.\">Asignar </a>";
							}else{
								$this->salida.="Hora Vencida";
							}
							$this->salida .= "			</td>";
							$this->salida .= "		</tr>";
							$this->salida .= "	</table>";
						}
					}
					$this->salida .= "	</div>";
					$this->salida .= "</td>";
					//
					//$i = $m; 
/*							$this->salida .= "<td rowspan=\"$conta\">";
							$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\" class=\"modulo_list_claro\">";
							$this->salida .= $html;
							$this->salida .= "	<tr>";
							$this->salida .= "		<td rowspan=\"$conta\">";
							$nom=$this->NombrePaciente($datos[3][$i],$datos[2][$i]);
							$dato=RetornarWinOpenDatosPaciente($datos[3][$i],$datos[2][$i],$nom);
							$this->salida.=$dato;
							$this->salida .= "		</td>";
							$this->salida .= "	</tr>";
							$this->salida .= "</table>";
							$this->salida .= "</td>";
							$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
							$this->salida.=$datos[7][$i];
							$this->salida .= "</td>";*/
				}
				elseif($datos[0][$i] <> $datos[0][$i-1])
				{
					$this->salida .= "<td>";
					$this->salida.=$datos[0][$i];
					$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$conta\">";
						$nom=$this->NombrePaciente($datos[3][$i],$datos[2][$i]);
						$dato=RetornarWinOpenDatosPaciente($datos[3][$i],$datos[2][$i],$nom);
						$this->salida.=$dato;
						$this->salida .= "</td>";
						$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
						$this->salida.=$datos[7][$i];
						$this->salida .= "</td>";
				}
				//$i = $l;

				
/*				$this->salida .= "<td rowspan=\"$conta\">";
				$nom=$this->NombrePaciente($datos[3][$i],$datos[2][$i]);
				$dato=RetornarWinOpenDatosPaciente($datos[3][$i],$datos[2][$i],$nom);
				$this->salida.=$dato;
				$this->salida .= "</td>";
				$this->salida .= "<td rowspan=\"$conta\" align=\"center\">";
				$this->salida.=$datos[7][$i];
				$this->salida .= "</td>";*/
				//$this->salida .= "<td rowspan=\"$conta\">**";
				if($datos[6][$i]==2 AND $datos[25][$i] == 1){
					$this->salida .= "<td rowspan=\"$conta\">";
/*					$turnoVariosPacinetes = false;
					if($datos[25][$i] > 1)
					{echo $datos[0][$i].'<br>';
						$turnoVariosPacinetes = true;
						//turnos para mas de un paciente
						$this->salida .= "	<div id=\"turnos".$datos[0][$i]."\" style=\"display:block\">";
						for($j=0; $j<$datos[25][$i]; $j++)
						{
							$this->salida .= "		<table width=\"80%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">";
							$this->salida .= "			<tr>";
							$this->salida .= "				<td rowspan=\"$conta\">";
							if($datos[0][$i] >= date('H:i')){
									//$vec1=$vec;
									$tmp='seleccion'.$i;
									//$vec1[$tmp]=$datos[8][$i];
									//$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$vec1);
									//cambio dar
									$argu=array('profesional'=>$_SESSION['CumplirCita']['profesional'],'DiaEspe'=>$_SESSION['CumplirCita']['DiaEspe'],'nompro'=>$_SESSION['CumplirCita']['nompro'],$tmp=>$datos[8][$i]);
									$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$argu);
									//fin cambio dar
									$this->salida.="<a href=\"$accion\" title=\"TURNO PARA ".$datos[25][$i]." PACIENTES.\">Asignar </a>";
							}else{
								$this->salida.="Hora Vencida";
							}
							$this->salida .= "			</td>";
							$this->salida .= "		</tr>";
							$this->salida .= "	</table>";
						}
						$this->salida .= "	</div>";
					}*/
					//turnos para mas de un paciente
					//else
					//{
						if($datos[0][$i] >= date('H:i') AND $datos[25][$i] == 1){
								//$vec1=$vec;
								$tmp='seleccion'.$i;
								//$vec1[$tmp]=$datos[8][$i];
								//$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$vec1);
								//cambio dar
								$argu=array('profesional'=>$_SESSION['CumplirCita']['profesional'],'DiaEspe'=>$_SESSION['CumplirCita']['DiaEspe'],'nompro'=>$_SESSION['CumplirCita']['nompro'],$tmp=>$datos[8][$i],'Asignar'=>'Asignar');
								$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$argu);
								//fin cambio dar
								$this->salida.='<a href="'.$accion.'">Asignar.. </a>';
						}elseif($datos[25][$i] == 1){
							$this->salida.='Hora Vencida';
						}
					//}
				$this->salida .= '</td>';
				}elseif($datos[25][$i] == 1){
					$this->salida .= "<td rowspan=\"$conta\">";
					if($datos[6][$i]===0){
						if($datos[5][$i]==0){			  		
							//cambio dar: se valida la varible y si esta en 1 no se mira lo de cajas
							if(ModuloGetVar('app','AgendaMedicaCoc','NoManejoCaja') ==1)					
							{			//institucion q no maneja caja
										$accion=ModuloGetURL('app','AgendaMedicaCoc','user','CrearCuenta',array('nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'numero_orden_id'=>$datos[13][$i]));
										$this->salida.='<a href="'.$accion.'">Cumplimiento</a>';
							}
							else
							{	//proceso normal
								if(!empty($_SESSION['CumplirCita']['cuantascajas'])){
									$cargo_liq[0]=array('tarifario_id'=>$datos[11][$i],'cargo'=>$datos[10][$i],'cantidad'=>1,'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i]);
									$servicio=$this->BusquedaServicio($_SESSION['CumplirCita']['departamento']);
									//$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq, array() ,array(),array() ,$datos[9][$i] ,$datos[14][$i], $datos[15][$i], $datos[16][$i],$servicio,$datos[3][$i], $datos[2][$i],'');
									$descripcionservicio=$this->BuscarDescripcionServicio($servicio);
									$descripcioncargo=$this->BusquedaDescripcionCargo($datos[11][$i],$datos[10][$i]);
									$cargo_arr[0]=array('tarifario_id'=>$datos[11][$i],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$datos[10][$i],'des_servicio'=>$descripcionservicio,'cantidad'=>$cargo_fact[cargos][0][cantidad],'valor_cargo'=>$cargo_fact[cargos][0][valor_cargo],'os_maestro_cargos_id'=>$datos[19][$i],'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i],'valor_no_cubierto'=>$cargo_fact[cargos][0][valor_no_cubierto]);
									$dat[0]=array('tarifario_id'=>$cargo_liq[0][tarifario_id],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$cargo_liq[0][cargo],'os_maestro_cargos_id'=>$datos[19][$i]);
									if($_SESSION['CumplirCita']['cuantascajas']==1){
										foreach($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'] as $k=>$v){
											foreach($v as $t=>$s){
												foreach($s as $m=>$n){
													$datos1=$n;
													break;
												}
												break;
											}
											break;
										}
										//este link es el que habia actualmente en la sos...vamos a quitar el vector $dat
										//$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));
										$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));
										$_SESSION['CAJA']['datos']=$dat;
										//$accion=ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'servicio'=>$servicio,'datos'=>$dat,'depto'=>$_SESSION['CumplirCita']['departamento'],$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1));
										$this->salida.='<a href="'.$accion.'">Caja Rapida</a>';
									}
									else
									{
/*										if($datos[25][$i] > 1)
										{
											$turnoVariosPacinetes = true;
											//turnos para mas de un paciente
											$this->salida .= "	<div id=\"turnos".$datos[0][$i]."\" style=\"display:none\">";
											for($j=0; $j<$datos[25][$i];)
											{
												$this->salida .= "		<table width=\"80%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">";
												$this->salida .= "			<tr>";
												$this->salida .= "				<td rowspan=\"$conta\">";
													$cargo_liq[0]=array('tarifario_id'=>$datos[11][$i],'cargo'=>$datos[10][$i],'cantidad'=>1,'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i]);
													$servicio=$this->BusquedaServicio($_SESSION['CumplirCita']['departamento']);
													//$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(), $datos[9][$i] ,$datos[14][$i] ,$datos[15][$i] ,$datos[16][$i],$servicio);
													$descripcionservicio=$this->BuscarDescripcionServicio($servicio);
													$descripcioncargo=$this->BusquedaDescripcionCargo($datos[11][$i],$datos[10][$i]);
													$cargo_arr[0]=array('tarifario_id'=>$datos[11][$i],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$datos[10][$i],'des_servicio'=>$descripcionservicio,'cantidad'=>$cargo_fact[cargos][0][cantidad],'valor_cargo'=>$cargo_fact[cargos][0][valor_cargo],'os_maestro_cargos_id'=>$datos[19][$i],'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i],'valor_no_cubierto'=>$cargo_fact[cargos][0][valor_no_cubierto]);
													$dat[0]=array('tarifario_id'=>$cargo_liq[0][tarifario_id],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$cargo_liq[0][cargo],'os_maestro_cargos_id'=>$datos[19][$i]);
													//$accion=ModuloGetURL('app','AgendaMedicaCoc','','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento']));
													$argu=array('profesional'=>$_SESSION['CumplirCita']['profesional'],'DiaEspe'=>$_SESSION['CumplirCita']['DiaEspe'],'nompro'=>$_SESSION['CumplirCita']['nompro'],$tmp=>$datos[8][$i]);
													$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$argu);
													$_SESSION['CAJA']['datos']=$dat;
													$datosMultiCitas=$this->VerMultiCitas($datos[24][$i],$datos[0][$i]);
													//echo $datosMultiCitas; exit;
													for($k=0; $k<$datosMultiCitas; $k++)
													{
														$this->salida.="<a><label class=\"label_mark\">ASIGNADO</label></a>";
														$this->salida.="<br>";
													}
													$j1 = $k;
													for($k=0; $k<$datos[25][$i]-$datosMultiCitas; $k++)
													{
															if($datos[0][$i] >= date('H:i'))
															{
																$this->salida.="<a href=\"$accion\">Asignar</a>";
															}
															else
															{
																$this->salida.='Hora Vencida';
															}
														$this->salida.="<br>";
													}
													$j = $j1 + $k;
													$this->salida .= "			</td>";
													$this->salida .= "		</tr>";
													$this->salida .= "	</table>";
											}
											$this->salida .= "	</div>";
										}
										else
										{*/
											$cargo_liq[0]=array('tarifario_id'=>$datos[11][$i],'cargo'=>$datos[10][$i],'cantidad'=>1,'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i]);
											$servicio=$this->BusquedaServicio($_SESSION['CumplirCita']['departamento']);
											//$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,array(),array(),array(), $datos[9][$i] ,$datos[14][$i] ,$datos[15][$i] ,$datos[16][$i],$servicio);
											$descripcionservicio=$this->BuscarDescripcionServicio($servicio);
											$descripcioncargo=$this->BusquedaDescripcionCargo($datos[11][$i],$datos[10][$i]);
											$cargo_arr[0]=array('tarifario_id'=>$datos[11][$i],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$datos[10][$i],'des_servicio'=>$descripcionservicio,'cantidad'=>$cargo_fact[cargos][0][cantidad],'valor_cargo'=>$cargo_fact[cargos][0][valor_cargo],'os_maestro_cargos_id'=>$datos[19][$i],'autorizacion_int'=>$datos[17][$i],'autorizacion_ext'=>$datos[18][$i],'valor_no_cubierto'=>$cargo_fact[cargos][0][valor_no_cubierto]);
											$dat[0]=array('tarifario_id'=>$cargo_liq[0][tarifario_id],'descripcion'=>$descripcioncargo,'numero_orden_id'=>$datos[13][$i],'cargo'=>$cargo_liq[0][cargo],'os_maestro_cargos_id'=>$datos[19][$i]);
											//$accion=ModuloGetURL('app','AgendaMedicaCoc','','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_fact,'nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'servicio'=>$servicio,'datos'=>$dat,'depto'=>$_SESSION['CumplirCita']['departamento']));
											$accion=ModuloGetURL('app','AgendaMedicaCoc','','MenuCaja',array('arr'=>$cargo_arr,'liq'=>$cargo_liq,'nom'=>$nom,'tipoid'=>$datos[3][$i],'id'=>$datos[2][$i],'afiliado'=>$datos[14][$i],'rango'=>$datos[15][$i],'sem'=>$datos[16][$i],'plan'=>$datos[9][$i],'auto'=>$datos[17][$i],'servicio'=>$servicio,'depto'=>$_SESSION['CumplirCita']['departamento']));
											$_SESSION['CAJA']['datos']=$dat;
											$this->salida.='<a href="'.$accion.'">Caja Rapida</a>';
										//}
									}
								}
								else{
									$this->salida.='Acerquese a la caja.';
								}
							}//fin del else de la variable	
						}
						if($datos[5][$i]==1){
							//$vec1=$vec;
							$vec1['CUMPLIR']['paciente']=$datos[2][$i];
							$vec1['CUMPLIR']['tipo_id_paciente']=$datos[3][$i];
							$vec1['CUMPLIR']['plan']=$datos[9][$i];
							$vec1['CUMPLIR']['cargo']=$datos[10][$i];
							$vec1['CUMPLIR']['tarifario']=$datos[11][$i];
							$vec1['CUMPLIR']['numerodecuenta']=$datos[12][$i];
							$vec1['CUMPLIR']['numero_orden_id']=$datos[13][$i];
							$vec1['CUMPLIR']['orden_servicio_id']=$datos[20][$i];
							$vec1['CUMPLIR']['cargo_cups']=$datos[22][$i];
							$accion=ModuloGetURL('app','AgendaMedicaCoc','','AutorizarPaciente',$vec1);
							$this->salida.='<a href="'.$accion.'">Revisar Derechos</a>';
						}
						if($datos[5][$i]==2){
							$vec1['CUMPLIR']['paciente']=$datos[2][$i];
							$vec1['CUMPLIR']['tipo_id_paciente']=$datos[3][$i];
							$vec1['CUMPLIR']['plan']=$datos[9][$i];
							$vec1['CUMPLIR']['numero_orden_id']=$datos[13][$i];
							$accion=ModuloGetURL('app','AgendaMedicaCoc','','PedirDatosPaciente',$vec1);
							$this->salida.='<a href="'.$accion.'">Datos Paciente</a>';
						}
						if($datos[5][$i]==3){
							$this->salida.='<label class="label">Acerquese al consultorio</label>';
						}
						if($datos[5][$i]==8){
							$this->salida.='<label class="label">Orden Vencida por Vencimiento.</label>';
						}
					}
					$this->salida .= '</td>';
				}
				//$this->salida .= '</td>';
			}else{
        if($spy==0){$this->salida .= '<tr class="modulo_list_oscuro">';	$spy=1;}
				else{$this->salida .= '<tr class="modulo_list_claro">';$spy=0;}
				$this->salida .= '<td>';
				$this->salida.=$datos[0][$i];
				$this->salida .= '</td>';
			}
			/*$this->salida .= '<td>';
			if(!empty($datos[13][$i]) and $datos[5][$i]==1)
			{
				$vec1=array();
				$vec1['CUMPLIR']['plan']=$datos[9][$i];
				$vec1['CUMPLIR']['orden_servicio_id']=$datos[20][$i];
				$vec1['CUMPLIR']['agenda_cita_asignada_id']=$datos[1][$i];
				$accion=ModuloGetURL('app','AgendaMedicaCoc','','CambioResponsable',$vec1);
				$this->salida .= '<a href="'.$accion.'">Cambiar Responsable</a>';
			}
			$this->salida .= '</td>';*/
			
			$this->salida .= '</tr>';
      $identificacionAnt=$datos[3][$i].' '.$datos[2][$i];
			$i++;
      $AgendaCitaPadreAnt=$AgendaCitaPadre;      
		}
		$this->salida .= '</table>';
		$this->salida .= "<br><br>";
		$this->salida .= '<table width="80%" align="center">';
		$this->salida .= '<tr align="center">';
		$this->salida .= '<td align="center" width="26%">';
		//$this->salida .= '<a href="" class="normal_10">Refrescar</>';
    //
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','ListadoCitasCumplidas');
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="refrescar" value="Refrescar" class="input-submit">';
 		$this->salida .='</form>';
   //
		$this->salida .= '</td>';
		$this->salida .= '<td align="center" width="26%">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','BuscarCita');
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="Volver" value="Volver" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .= '</td>';
		$this->salida .= '<td align="center" width="26%">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','CitaPrioritaria');
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="Volver" value="Prioritaria" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


/**
* Esta funcion permite cambiar el responsable de la orden de servicio, la funcion debe revisarse y mejorarse
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function ResponsableActual()
	{
		$this->salida = ThemeAbrirTabla('CAMBIO DE RESPONSABLE');
		$vec1['CUMPLIR']['plan']=$_REQUEST['CUMPLIR']['plan'];
		$vec1['CUMPLIR']['orden_servicio_id']=$_REQUEST['CUMPLIR']['orden_servicio_id'];
		$vec1['CUMPLIR']['agenda_cita_asignada_id']=$_REQUEST['CUMPLIR']['agenda_cita_asignada_id'];
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','CambiarValorResponsable',$vec1);
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .= "<table border='0' align='center'>";
		$this->salida .= "<tr height=\"20\"><td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td><td><select name=\"Responsable\"  class=\"select\">";
		$responsables=$this->CallMetodoExterno('app','Triage','user','responsables');
		if($responsables==false)
		{
			$this->error = "No existe ningun plan abierto";
			$this->mensajeDeError = "Verifique los planes.";
			return false;
		}
		$this->MostrarResponsable($responsables,$_REQUEST['CUMPLIR']['plan']);
		$this->salida .= "</select></td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "<br>";
		$this->salida .= '<table width="50%" border="0" align="center">';
		$this->salida .= '<tr>';
		$this->salida .= '<td align="right" width="50%">';
		$this->salida .='<input type="submit" name="Cambiar" value="Cambiar" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .='</form>';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','ListadoCitasCumplidas');
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .= '<td align="left" width="50%">';
		$this->salida .='<input type="submit" name="Volver" value="Volver" class="input-submit">';
		$this->salida .= '</td>';
		$this->salida .='</form>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
* Esta funcion permite asignar citas de manera prioritaria, lo que permite es asignar una cita asi ya este ocupada
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function CitaPrioritaria()
	{ $_SESSION['AgendaMedicaCoc']['CitaPrioritaria']=1;
		unset($_SESSION['ADMISION']);
		unset($_SESSION['PACIENTES']);
		unset($_SESSION['TRIAGE']);
		unset($_SESSION['AsignacionCitas']);
		unset($_SESSION['AsignacionCitas']);
		$this->salida = ThemeAbrirTabla('CUMPLIMIENTO DE CITAS');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='<br>';
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Fecha de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CumplirCita']['nompro'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$a=explode("-",$_REQUEST['DiaEspe']);
		$this->salida .= strftime("%d de %B de %Y",mktime(0,0,0,$a[1],$a[2],$a[0]));
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<br><br>";
		$datos=$this->ListadoCitasPrioritarias();
		$horaAnt=-1;
		$contadorMaximo=0;
		while($i<sizeof($datos[0])){
			$Fecha=explode(":",$datos[0][$i]);
			if($Fecha[0]!=$horaAnt){
				$contadorMax=$contador;
				$contador=1;
				if($contadorMax>$contadorMaximo){
					$contadorMaximo=$contadorMax;
				}
				$horaAnt=$Fecha[0];
			}else{
				$contador++;
			}
			$i++;
		}
		$this->salida .= '<table width="60%" align="center" border="1" class="modulo_table_list">';
		$contadorMaximo1=$contadorMaximo+1;
    $this->salida .= '<tr align="center" class="modulo_table_title"><td colspan="'.$contadorMaximo1.'">ASIGNAR CITA</td></tr>';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center" width="5%" nowrap>';
		$this->salida .= "Hora";
		$this->salida .= "</td>";
		$this->salida .= "<td colspan=\"$contadorMaximo\" align=\"center\">";
		$this->salida .= "Minutos";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$i=0;
		foreach($_REQUEST as $v=>$dato){
			if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID'){
				$vec[$v]=$dato;
			}
		}
		$bandera=0;
		$horaAnt=-1;
		$i=0;
		while($i<sizeof($datos[0])){
      $hora=explode(':',$datos[0][$i]);
      if($hora[0]!=$horaAnt){
			  if($bandera==1){
          $this->salida .= '</tr>';
				}
				if($spy==0){$this->salida .= '<tr class="modulo_list_oscuro">';	$spy=1;}
				else{$this->salida .= '<tr class="modulo_list_claro">';$spy=0;}
				$this->salida .= '<td align="center">';
				$this->salida.="".$hora[0]."";
				$this->salida .= '</td>';
				$this->salida .= "<td>";
				if($datos[0][$i]<date("H:i")){
          $this->salida.="".$hora[1]."";
				}else{
					$vec1=$vec;
					$tmp='seleccion'.$i;
					$vec1[$tmp]=$datos[1][$i];
					$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$vec1);
					$this->salida.="<a href=".$accion.">".$hora[1]."</a>";
				}
				$this->salida .= '</td>';
				$horaAnt=$hora[0];
				$bandera=1;
			}else{
        $this->salida .= "<td>";
				if($datos[0][$i]<date("H:i")){
          $this->salida.="".$hora[1]."";
				}else{
				  $vec1=$vec;
					$tmp='seleccion'.$i;
					$vec1[$tmp]=$datos[1][$i];
					$accion=ModuloGetURL('app','AgendaMedicaCoc','','DatosPaciente',$vec1);
					$this->salida.="<a href=".$accion.">".$hora[1]."</a>";
				}
				$this->salida .= '</td>';
			}
			$i++;
		}
		$this->salida .= '</table>';
		$this->salida .= "<br><br>";
		$this->salida .= '<table width="80%" align="center">';
		$this->salida .= '<tr align="center">';
		$this->salida .= '<td align="center" width="26%">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','ListadoCitasCumplidas',$vec);
		$this->salida .= '<form name="volver" action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="Volver" value="Volver" class="input-submit">';
		$this->salida .='</form>';
		$this->salida .= '</td>';
		$this->salida .= '</tr>';
		$this->salida .= '</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




//Funciones Atenci??? Cita.
/**
* Esta funcion muestra el listado de los permisos que tiene el usuario para atender las citas
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function AtenderCita()
	{
		$cita=$this->TiposCitasAtender();
		if($cita==false)
		{
			return false;
		}
		return true;
	}



/**
* Esta funcion muestra la agenda del dia del profesional
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function AgendaDia()
	{
		$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AgendaMedicaCoc';
		$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='LLegadaHistoriaClinica';
		$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
		$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
		if(!empty($_REQUEST['Atencion']['tipo_consulta_id']))
		{
			$_SESSION['Atencion']['DiaEspe']=$_REQUEST['Atencion']['diaespe'];
			$_SESSION['Atencion']['cita']=$_REQUEST['Atencion']['tipo_consulta_id'];
			$_SESSION['Atencion']['departamento']=$_REQUEST['Atencion']['departamento'];
			$_SESSION['Atencion']['empresa']=$_REQUEST['Atencion']['empresa_id'];
			$_SESSION['Atencion']['nomcit']=$_REQUEST['Atencion']['descripcion3'];
			$_SESSION['Atencion']['nomdep']=$_REQUEST['Atencion']['descripcion2'];
			$_SESSION['Atencion']['nomemp']=$_REQUEST['Atencion']['descripcion1'];
			$_SESSION['Atencion']['profesional']=$_REQUEST['Atencion']['tipo_id_profesional']. ',' .$_REQUEST['Atencion']['profesional_id'];
			$_SESSION['Atencion']['nompro']=$_REQUEST['Atencion']['nombre'];
			$_SESSION['Atencion']['hc_modulo']=$_REQUEST['Atencion']['hc_modulo'];
			$_SESSION['Atencion']['bodega_unico']=$_REQUEST['Atencion']['bodega_unico'];
			$_SESSION['Atencion']['especialidad']=$_REQUEST['Atencion']['especialidad'];
		}
		if($_REQUEST['DiaEspe']!=$_SESSION['Atencion']['DiaEspe'] and !empty($_REQUEST['DiaEspe']))
		{
			$_SESSION['Atencion']['DiaEspe']=$_REQUEST['DiaEspe'];
		}
		if($_SESSION['Atencion']['DiaEspe']<date("Y-m-d"))
		{
			$this->CalendarioAtencion();
			return true;
		}
    $file = 'app_modules/AgendaMedicaCoc/RemoteXajax/NotasAdministrativas.php';
    $this->SetXajax(array("reqNotasAdministrativas","InsertarNota"),$file);        
    $this->IncludeJS("CrossBrowser");
    $this->IncludeJS("CrossBrowserDrag");
    $this->IncludeJS("CrossBrowserEvent");   
    $js = "  <SCRIPT>\n";
    $js .= "  function Iniciar()\n";
    $js .= "  {\n";        
    $js .= "    document.getElementById('titulo').innerHTML = '<center>NOTA ADMINISTRATIVA</center>';\n";
    $js .= "    document.getElementById('error').innerHTML = '';\n";                
    $js .= "    contenedor = 'd2Container';\n";
    $js .= "    titulo = 'titulo';\n";
    $js .= "    ele = xGetElementById('d2Container');\n";
    $js .= "    xResizeTo(ele,400, 'auto');\n";
    $js .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
    $js .= "    ele = xGetElementById('titulo');\n";
    $js .= "    xResizeTo(ele,380, 20);\n";
    $js .= "    xMoveTo(ele, 0, 0);\n";
    $js .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
    $js .= "    ele = xGetElementById('cerrar');\n";
    $js .= "    xResizeTo(ele,20, 20);\n";
    $js .= "    xMoveTo(ele, 380, 0);\n";
    $js .= "  }\n";
    $js .= "  function myOnDragStart(ele, mx, my)\n";
    $js .= "  {\n";
    $js .= "    window.status = '';\n";
    $js .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
    $js .= "    else xZIndex(ele, hiZ++);\n";
    $js .= "    ele.myTotalMX = 0;\n";
    $js .= "    ele.myTotalMY = 0;\n";
    $js .= "  }\n";
    $js .= "  function myOnDrag(ele, mdx, mdy)\n";
    $js .= "  {\n";
    $js .= "    if (ele.id == titulo) {\n";
    $js .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
    $js .= "    }\n";
    $js .= "    else {\n";
    $js .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
    $js .= "    }  \n";
    $js .= "    ele.myTotalMX += mdx;\n";
    $js .= "    ele.myTotalMY += mdy;\n";
    $js .= "  }\n";
    $js .= "  function myOnDragEnd(ele, mx, my)\n";
    $js .= "  {\n";
    $js .= "  }\n";
    $js .= "  function MostrarSpan(Seccion)\n";
    $js .= "  { \n";
    $js .= "    e = xGetElementById(Seccion);\n";
    $js .= "    e.style.display = \"\";\n";
    $js .= "  }\n";
    $js .= "  function Cerrar()\n";
    $js .= "  { \n";
    $js .= "    e = xGetElementById('d2Container');\n";
    $js .= "    e.style.display = \"none\";\n";
    $js .= "  }\n";
    $js .= "  function MostrarVentana()\n";
    $js .= "  { \n";
    $js .= "    e = xGetElementById('d2Container');\n";
    $js .= "    e.style.display = \"block\";\n";
    $js .= "  }\n";  
    $js .= "  </SCRIPT>\n";
		$this->salida .= $js;
		
		//CAPA NOTA ADMINISTRATIVA
    $this->salida .= "<div id=\"d2Container\" class=\"d2Container\" style=\"display:none\">\n";
    $this->salida .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
    $this->salida .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
    $this->salida .= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
		$this->salida .= "  <div id='d2Contents'>\n";
		$this->salida .= "  </div>";
    $this->salida .= "</div>";   
		//FIN CAPA NOTA ADMINISTRATIVA
		     
		$this->salida .= ThemeAbrirTabla('ATENCION DE CITAS');
		$turnosdia=$this->ListadoCitasAtender();
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class=modulo_table_title>';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class=modulo_table_title>';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Fecha de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nompro'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$a=explode("-",$_SESSION['Atencion']['DiaEspe']);
		$this->salida .= strftime("%d de %B de %Y",mktime(0,0,0,$a[1],$a[2],$a[0]));
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		if($turnosdia==true){
			$this->salida.='<br>';
			$this->salida.='<table align="center" width="80%" border="0">';
			$this->salida.='<tr align="center" class="modulo_table_title">';
			$this->salida.='<td nowrap width="5%" align="center">';
			$this->salida.='Hora';
			$this->salida.='</td>';
			$this->salida.='<td nowrap width="30%" align="center">';
			$this->salida.='Paciente';
			$this->salida.='</td>';
			$this->salida.='<td nowrap width="25%" align="center">';
			$this->salida.='Cargo';
			$this->salida.='</td>';
			$this->salida.='<td nowrap width="20%" align="center">';
			$this->salida.='Observaci???';
			$this->salida.='</td>';
			$this->salida.='<td nowrap width="20%" align="center" colspan="2">';
			$this->salida.='Acci???';
			$this->salida.='</td>';
               $this->salida.='<td nowrap width="20%" align="center">';
			$this->salida.='Nota';
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$i=0;
			$this->SetJavaScripts('DatosPaciente');
			$this->SetJavaScripts('DatosEvolucionInactiva');
			$AgendaCitaIdPadreAnt=-1;
      $identificacionAnt=-1;
      while($i<sizeof($turnosdia[0]))
      {
               	$AgendaCitaIdPadre=$turnosdia[12][$i];
               	if($AgendaCitaIdPadre!=$AgendaCitaIdPadreAnt || empty($AgendaCitaIdPadre) || ($AgendaCitaIdPadre==$AgendaCitaIdPadreAnt && !empty($AgendaCitaIdPadre) && $turnosdia[2][$i].' '.$turnosdia[3][$i]!=$identificacionAnt)){
                         $conta=0;
                         foreach($turnosdia[12] as $indice=>$CitaIden){
                    		if($CitaIden==$AgendaCitaIdPadre && $turnosdia[2][$i].' '.$turnosdia[3][$i]==$turnosdia[2][$indice].' '.$turnosdia[3][$indice]){
                    			$conta++;
                              }
               		}
					if($spy==0){
						$this->salida.='<tr class="modulo_list_claro">';
						$spy=1;
					}else{
						$this->salida.='<tr class="modulo_list_oscuro">';
						$spy=0;
					}
					$this->salida.='<td align="center">';
					$this->salida.=$turnosdia[0][$i];
					$this->salida.='</td>';
					$this->salida.="<td rowspan=\"$conta\" align=\"center\">";
					$nom=$this->NombrePaciente($turnosdia[2][$i],$turnosdia[3][$i]);
					$dato=RetornarWinOpenDatosPaciente($turnosdia[2][$i],$turnosdia[3][$i],$nom);
					$this->salida.=$dato;
					$this->salida.='</td>';
					$nota_administrativa = false;
					$evento = "";
					if(!empty($nom))
					{
						$horaCita = $turnosdia[0][$i];
						$fechaCita =  $_SESSION[Atencion][DiaEspe];
						$datosProfesional = explode(',',$_SESSION[Atencion][profesional]);
						$TipoPacienteId = $turnosdia[2][$i];
						$PacienteId = $turnosdia[3][$i];
						$ConsultaNotas = $this->ConsultaNotasAdministrativas($TipoPacienteId,$PacienteId,$fechaCita,$horaCita);
						$existenota_id = "NoExiste";
						if(is_array($ConsultaNotas))
						{
							$Imagen = "<img src=\"".GetThemePath()."/images/ok.png\" title=\"$ConsultaNotas[observaciones]\">";
							$existenota_id = $ConsultaNotas[notas_administrativas_consulta_externa_id];
						}
						else
						{
							$Imagen = "<img title=\"Adici??? de Nota Administrativa\" src=\"". GetThemePath() ."/images/news.gif\" border=\"0\">";
						}
						$evento = "onClick=\"xajax_reqNotasAdministrativas('$fechaCita','$horaCita','$datosProfesional[0]','$datosProfesional[1]','$TipoPacienteId','$PacienteId','$existenota_id');\"";
						$nota_administrativa = true;
					}
					$this->salida.='<td align="center">';
					$this->salida.=$turnosdia[13][$i];
					$this->salida.='</td>';
					$this->salida.="<td align=\"center\" rowspan=\"$conta\">";
					$this->salida.=$turnosdia[10][$i];
					$this->salida.='</td>';
          
					if($_SESSION['Atencion']['DiaEspe']==date("Y-m-d"))
          {
						//echo "<pre>$i".print_r($turnosdia[6],true)."</pre>";
                if(!empty($turnosdia[6][$i])){
		//echo $turnosdia[10][$i];					;
		//$TipoCita = $this->RetornaTipoCita($turnosdia[1][$i]);
		//echo $TipoCita.'<br>';
		
		//////////cambio crv 2008-06-16
		
		$variable = ModuloGetVar('app','AgendaMedicaCoc','MenuAsignacionCitas');
		if($variable){
		if($turnosdia[10][$i] != 'ASIGNACION DE CITA AUTOMATICA'){	
			
			if(!empty($turnosdia[7][$i]))
              		{
				if($this->CantidadCitasPendientesPaciente($turnosdia[2][$i],$turnosdia[3][$i]) > 0){
				
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
						if($turnosdia[9][$i]==3){
							$accion=ModuloGetURL('app','AgendaMedicaCoc','','PeticionOficio',array('tipoid'=>$turnosdia[2][$i],'pacienteid'=>$turnosdia[3][$i],'ingreso'=>$turnosdia[6][$i],'cups_cita'=>$turnosdia[11][$i]));
							$this->salida.='<a href="'.$accion.'">Continuar Atencion</a> ';
							$entro=0;
						}
						$this->salida.='</td>';
				
				}else{	
					if($turnosdia[8][$i]==1){
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
						$accion=ModuloHCGetURL($turnosdia[7][$i],'','',$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array('HC_DATOS_CONTROL'=>array('DEPARTAMENTO'=>$_SESSION['Atencion']['departamento'],'ESPECIALIDAD'=>$_SESSION['Atencion']['especialidad'])));
						$this->salida.='<a href="'.$accion.'">Continuar Atencion</a> ';
						$this->salida.=' - '.$turnosdia[7][$i];
						$this->salida.='</td>';
						$entro=0;
					}else{
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
						//$dato=RetornarWinOpenDatosEvolucionInactiva($turnosdia[7][$i],'Atendido');
						//$this->salida.=$dato;
						$accion=ModuloHCGetURL($turnosdia[7][$i],'','',$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array());
						$this->salida.='<a href="'.$accion.'">Atendido</a> ';
						$this->salida.=' - '.$turnosdia[7][$i];
						$this->salida.='</td>';
						$entro=1;
					}
				}	
			}
              		else
              		{
                                $this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
                                if($turnosdia[9][$i]==3){
					$accion=ModuloGetURL('app','AgendaMedicaCoc','','PeticionOficio',array('tipoid'=>$turnosdia[2][$i],'pacienteid'=>$turnosdia[3][$i],'ingreso'=>$turnosdia[6][$i],'cups_cita'=>$turnosdia[11][$i]));
					$this->salida.='<a href="'.$accion.'">Atender</a> ';
                                	$entro=0;
				}
				$this->salida.='</td>';
			}
		}	
		else{
			if(!empty($turnosdia[7][$i]))
              		{
				
				if($this->CantidadCitasPendientesPaciente($turnosdia[2][$i],$turnosdia[3][$i]) > 0){
				
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
						if($turnosdia[9][$i]==3){
							$accion=ModuloGetURL('app','AgendaMedicaCoc','','PeticionOficio',array('tipoid'=>$turnosdia[2][$i],'pacienteid'=>$turnosdia[3][$i],'ingreso'=>$turnosdia[6][$i],'cups_cita'=>$turnosdia[11][$i]));
							$this->salida.='<a href="'.$accion.'">Continuar Atencion</a> ';
							$entro=0;
						}
						$this->salida.='</td>';
				
				}else{	
					if($turnosdia[8][$i]==1){
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
						$accion=ModuloHCGetURL($turnosdia[7][$i],'','',$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array('HC_DATOS_CONTROL'=>array('DEPARTAMENTO'=>$_SESSION['Atencion']['departamento'],'ESPECIALIDAD'=>$_SESSION['Atencion']['especialidad'])));
						$this->salida.='<a href="'.$accion.'">Continuar Atenci???</a> ';
						$this->salida.=' - '.$turnosdia[7][$i];
						$this->salida.='</td>';
						$entro=0;
					}else{
					
						
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
						//$dato=RetornarWinOpenDatosEvolucionInactiva($turnosdia[7][$i],'Atendido');
						//$this->salida.=$dato;
						$accion=ModuloHCGetURL($turnosdia[7][$i],'','',$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array());
						$this->salida.='<a href="'.$accion.'">Atendido</a> ';
						$this->salida.=' - '.$turnosdia[7][$i];
						$this->salida.='</td>';
						$entro=1;
					}
				}	
			}
			else{
				$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
				if($turnosdia[9][$i]==3){
					$accion=ModuloGetURL('app','AgendaMedicaCoc','','PeticionOficio',array('tipoid'=>$turnosdia[2][$i],'pacienteid'=>$turnosdia[3][$i],'ingreso'=>$turnosdia[6][$i],'cups_cita'=>$turnosdia[11][$i]));
					$this->salida.='<a href="'.$accion.'">Atender</a> ';
					$entro=0;
				}
				$this->salida.='</td>';
			}
		}	
		}
		else{
			if(!empty($turnosdia[7][$i]))
              		{
								if($turnosdia[8][$i]==1){
									$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
									$accion=ModuloHCGetURL($turnosdia[7][$i],'','',$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array('HC_DATOS_CONTROL'=>array('DEPARTAMENTO'=>$_SESSION['Atencion']['departamento'],'ESPECIALIDAD'=>$_SESSION['Atencion']['especialidad'])));
									$this->salida.='<a href="'.$accion.'">Continuar Atenci???</a> ';
									$this->salida.=' - '.$turnosdia[7][$i];
									$this->salida.='</td>';
                                             				$entro=0;
								}else{
									$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
									//$dato=RetornarWinOpenDatosEvolucionInactiva($turnosdia[7][$i],'Atendido');
									//$this->salida.=$dato;
									$accion=ModuloHCGetURL($turnosdia[7][$i],'','',$_SESSION['Atencion']['hc_modulo'],$_SESSION['Atencion']['hc_modulo'],array());
									$this->salida.='<a href="'.$accion.'">Atendido</a> ';
									$this->salida.=' - '.$turnosdia[7][$i];
									$this->salida.='</td>';
                                             				$entro=1;
								}
			}
              		else
              		{
                                             	$this->salida.="<td rowspan=\"$conta\" align=\"center\" width=\"10%\" colspan=\"2\">";
                                             	if($turnosdia[9][$i]==3){
									$accion=ModuloGetURL('app','AgendaMedicaCoc','','PeticionOficio',array('tipoid'=>$turnosdia[2][$i],'pacienteid'=>$turnosdia[3][$i],'ingreso'=>$turnosdia[6][$i],'cups_cita'=>$turnosdia[11][$i]));
									$this->salida.='<a href="'.$accion.'">Atender</a> ';
                                             				$entro=0;
								}
								$this->salida.='</td>';
			}
		
		
		
		}	
			
			
			}else{
							$this->salida.="<td rowspan=\"$conta\" align=\"center\" colspan=\"2\">";
							$this->salida.='</td>';
			}
		}else{
						$this->salida.="<td rowspan=\"$conta\" align=\"center\" colspan=\"2\">";
						$this->salida.='</td>';
					}
				}else{
                         if($spy==0){
						$this->salida.='<tr class="modulo_list_claro">';
						$spy=1;
					}else{
						$this->salida.='<tr class="modulo_list_oscuro">';
						$spy=0;
					}
					$this->salida.='<td align="center">';
					$this->salida.=$turnosdia[0][$i];
					$this->salida.='</td>';
				}
				if(!empty($AgendaCitaIdPadre)){
			          $AgendaCitaIdPadreAnt=$AgendaCitaIdPadre;
				}
                    $this->salida.="<td align=\"center\" $evento>";
                    if($entro==1){
                         $AcionNota = ModuloGetUrl('app','AgendaMedicaCoc','user','LlamarIngresarNota',array('ingreso'=>$turnosdia[6][$i],'evolucion'=>$turnosdia[7][$i],'nombre'=>'E'));
                         $this->salida.="<a href=\"$AcionNota\"><img title=\"Adicion de Nota Medica.\" src=\"". GetThemePath() ."/images/edita.png\" border=\"0\"></a>";
                    }
										
                    elseif($nota_administrativa)
										{
											$this->salida .= "<div id = 'nota$horaCita'>";
											$this->salida .= $Imagen;
											//$this->salida .= "<img title=\"Adici??? de Nota Administrativa\" src=\"". GetThemePath() ."/images/news.gif\" border=\"0\">";
											$this->salida .= "</div>";
											//$this->salida.="<a href=\"javascript:Iniciar();MostrarVentana('d2Container');\"><img title=\"Adici??? de Nota Administrativa\" src=\"". GetThemePath() ."/images/edita.png\" border=\"0\"></a>";
										}
										else{$this->salida.="&nbsp";}
                    $entro=0;
                    $this->salida.='</td>';
				$this->salida.='</tr>';
        $identificacionAnt=$turnosdia[2][$i].' '.$turnosdia[3][$i];
				$i++;
			}
			$this->salida.='</table>';
			$this->salida.='<br>';
		}else{
			$this->salida.='<br>';
			$this->salida.='<table align="center" width="80%" border="0">';
			$this->salida.='<tr>';
			$this->salida.='<td align="center">';
			$this->salida.='<label class="label_error">No existen citas programadas para este d???</label>';
			$this->salida.='</td>';
			$this->salida.='</tr>';
			$this->salida.='</table>';
			$this->salida.='<br>';
		}
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','AtenderCita');
		$this->salida.='<br>';
		$this->salida.='<table align="center" width="80%" border="0">';
		$this->salida.='<tr align="center">';
		$this->salida.='<td align="right" width="55%">';
		$this->salida .='<form action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="Volver" value="Volver" class="input-submit">';
		$this->salida .='</form>';
		$this->salida.='</td>';
		$this->salida.='<td align="right">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','CalendarioAtencion');
		$this->salida .='<form action="'.$accion.'" method="post">';
		$this->salida .='<input type="submit" name="Agenda Completa" value="Agenda Completa" class="input-submit">';
		$this->salida .='</form>';
		$this->salida.='</td>';
		$this->salida.='</tr>';
		$this->salida.='</table>';
		$this->salida .='</form>';
		$this->salida.='<br>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
* Esta funcion lleva a la historia clinica
*
* @access public
* @return boolean Para identificar que se realizo.
*/

	function VolverListado($url)
	{
		$salida.="<script>\n";
		$salida.="location.href=\"$url\";\n";
		$salida.="</script>\n";
		$salida.="<a href=\"$url\">Ir Historia</a>";
		return $salida;
	}




/**
* Esta funcion muestra las ocupaciones del paciente
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	function MuetraOcupaciones()
	{
		//$datos=$this->ocupacion();
		$ocupacion=$this->BuscaOcupacion();
		/*if($datos==false)
		{
			return false;
		}*/
		$this->SetJavaScripts('Ocupaciones');
		$this->salida = ThemeAbrirTabla('OCUPACIONES','90%');
		
		$nom=$this->NombrePaciente($_REQUEST['tipoid'],$_REQUEST['pacienteid']);
		$this->salida .= "				<table align=center class='modulo_table_title' border='0' width='60%'>\n";
		$this->salida .= "					<tr class='modulo_table_title'>\n";
		$this->salida .= "						<td>IDENTIFICACION</td>\n";
		$this->salida .= "						<td>NOMBRE</td>\n";
		$this->salida .= "						<td>No. INGRESO</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "					<tr class='modulo_list_oscuro'>\n";
		$this->salida .= "						<td>".$_REQUEST['tipoid']."-".$_REQUEST['pacienteid']."</td>\n";
		$this->salida .= "						<td>".$nom."</td>\n";
		$this->salida .= "						<td>".$_REQUEST['ingreso']."</td>\n";
		//$this->salida .= "						<td>".$datos['descripcion4']."</td>\n";
		$this->salida .= "					</tr>\n";
		$this->salida .= "				</table>\n";

		
		
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','SeguirAtencion',array('tipoid'=>$_REQUEST['tipoid'],'pacienteid'=>$_REQUEST['pacienteid'],'ingreso'=>$_REQUEST['ingreso'],'cups_cita'=>$_REQUEST['cups_cita']));
		$this->salida.='<form name="form2" action="'.$accion.'" method="post">';
		$this->salida.='<br>';
		$this->salida.='<br>';
		$this->salida.='<table width="60%" align="center" border="0">';
		
		$this->salida.='<tr  class="modulo_table_title">';
		$this->salida.='<td colspan=2 width="60%" align="center">&nbsp;';
		$this->salida.='</td>';
		$this->salida.='</tr>';
		
		
		$this->salida.='<tr class="modulo_list_claro">';
		$this->salida.='<td width="40%">';
		$this->salida.='<label class="label">ELIJA OCUPACI??? DEL PACIENTE: </label>';
		$this->salida.='</td>';
		$this->salida.='<td width="60%" align="center">';
		/*$this->salida.='<select name="ocupacion" class="select">';
		$this->salida.='<option value="-1">--SELECCIONE--</option>';
		foreach($datos as $k=>$v)
		{
			if($ocupacion==$k)
			{
				$this->salida.='<option value="'.$k.'" selected>'.$v.'</option>';
			}
			else
			{
				$this->salida.='<option value="'.$k.'">'.$v.'</option>';
			}
		}
		$this->salida.='</select>';*/
		$this->salida.=RetornarWinOpenBuscadorOcupaciones('form2','',$ocupacion);
		$this->salida.='</td>';
		$this->salida.='</tr>';
    
    $arreglox=$this->Get_datos_Adicionales($_REQUEST['tipoid'],$_REQUEST['pacienteid']);
    $this->salida .= "<tr class=\"hc_submodulo_list_claro\">";
    $this->salida .= "<td class=\"".$this->SetStyle("mano_dominante")."\">MANO DOMINANTE</td>";
    $this->salida .= "<td colspan=\"3\">";
    $this->salida .= "<select name=\"mano_dominante$pfj\" class=\"select\">";    
    if($arreglox[mano_dominante]=='DERECHA'){$sel='selected';}elseif($arreglox[mano_dominante]=='IZQUIERDA'){$sel1='selected';}
    $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
    $this->salida .=" <option value=\"DERECHA\" $sel>DERECHA</option>";    
    $this->salida .=" <option value=\"IZQUIERDA\" $sel1>IZQUIERDA</option>";     
    $this->salida .=" <option value=\"AMBIDIESTRO\" $sel2>AMBIDIESTRO</option>";     
    $this->salida .= "</select>";
    $this->salida .= "</td>";     
    $this->salida .= "</tr>";       
    $this->salida .= "<tr class=\"hc_submodulo_list_claro\">";
    $this->salida .= "<td class=\"".$this->SetStyle("raza")."\">ETNIA</td>";
    $this->salida .= "<td colspan=\"3\">";
    $this->salida .= "<select name=\"raza$pfj\" class=\"select\">";
    $razas=$this->ConsultarTiposRazas();
    $this->salida .="    <option value=\"-1\">---Seleccione---</option>";
    for($i=0;$i<sizeof($razas);$i++){
      if($razas[$i]['tipo_raza_id']==$arreglox[tipo_raza_id]){
        $this->salida .=" <option value=\"".$razas[$i]['tipo_raza_id']."\" selected>".$razas[$i]['descripcion']."</option>";      
      }else{      
        $this->salida .=" <option value=\"".$razas[$i]['tipo_raza_id']."\">".$razas[$i]['descripcion']."</option>";      
      }
    }
    $this->salida .= "    </select>";
    $this->salida .= "</td>";     
    $this->salida .= "</tr>";  
    
    $this->salida .= "<tr class=\"hc_submodulo_list_claro\">";
    $this->salida .= "<td class=\"".$this->SetStyle("preferencia_sexual")."\">PREFERENCIA SEXUAL</td>";
    $this->salida .= "<td colspan=\"3\">";
    $this->salida .= "<select name=\"preferencia_sexual$pfj\" class=\"select\">";    
    if($arreglox[preferencia_sexual]=='1'){$sel1='selected';}
    elseif($arreglox[preferencia_sexual]=='2'){$sel2='selected';}
    elseif($arreglox[preferencia_sexual]=='3'){$sel3='selected';}
    $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
    $this->salida .=" <option value=\"1\" $sel1>HETEROSEXUAL</option>";    
    $this->salida .=" <option value=\"2\" $sel2>HOMOSEXUAL</option>";     
    $this->salida .=" <option value=\"3\" $sel3>BISEXUAL</option>";     
    $this->salida .= "</select>";
    $this->salida .= "</td>";     
    $this->salida .= "</tr>"; 
    
    
		$this->salida.='</table>';
		$this->salida.='<br>';
		$this->salida.='<table width="60%" align="center">';
		$this->salida.='<tr>';
		$this->salida.='<td width="50%" align="center">';
		$this->salida.='<input type="submit" name="volver" value="ATENDER" class="input-submit">';
		$this->salida.='</form>';
		$this->salida.='</td>';
		$this->salida.='<td width="50%" align="center">';
		$accion=ModuloGetURL('app','AgendaMedicaCoc','','AgendaDia');
		$this->salida.='<form name="form1" action="'.$accion.'" method="post">';
		$this->salida.='<input type="submit" name="volver" value="VOLVER" class="input-submit">';
		$this->salida.='</form>';
		$this->salida.='</td>';
		$this->salida.='</tr>';
		$this->salida.='</table>';
		$this->salida .= ThemeCerrarTabla();
		return true;
	}




/**
* Esta funcion muestra el calendario para la atencion
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function CalendarioAtencion()
	{
		SessionDelVar('CITASMES');
		$a=explode(",",$_SESSION['Atencion']['profesional']);
		$sql="select distinct(fecha_turno) from agenda_turnos as a, agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['Atencion']['cita']." and empresa_id='".$_SESSION['Atencion']['empresa']."' and profesional_id='".$a[1]."' and tipo_id_profesional='".$a[0]."' and date(fecha_turno)>=date(now()) order by fecha_turno;";
		//echo "select distinct(fecha_turno) from agenda_turnos as a, agenda_citas as b where a.agenda_turno_id=b.agenda_turno_id and tipo_consulta_id=".$_SESSION['Atencion']['cita']." and empresa_id='".$_SESSION['Atencion']['empresa']."' and sw_estado&lt;cantidad_pacientes and profesional_id='".$a[1]."' and tipo_id_profesional='".$a[0]."' and date(fecha_turno)>=date(now()) order by fecha_turno;";
		$fechas=$this->DiasCitas($sql);
		SessionSetVar('CITASMES',$fechas);
		$this->salida .= ThemeAbrirTabla('Atenci??? Citas');
		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
			if($v!='year' and $v!='meses')
			{
				$this->salida.='&'.$v.'='.$v1;
			}
		}
		$this->salida.='";'."\n";
		$this->salida.='}'."\n";
		$this->salida.='</script>';
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Profesional";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Fecha de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['Atencion']['nompro'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$a=explode("-",$_SESSION['Atencion']['DiaEspe']);
		$this->salida .= strftime("%d de %B de %Y",mktime(0,0,0,$a[1],$a[2],$a[0]));
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .='<form name="cosa">';
		$this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table\">";
		$this->salida .='<tr align="center">';
		$this->salida .="<td class=\"label\">A???</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['year']))
		{
			$a=explode("-",$_SESSION['CITASMES'][0]);
			$year=$_REQUEST['year']=$a[0];
			$this->AnosAgenda(True,$_REQUEST['year']);
		}
		else
		{
			$this->AnosAgenda(true,$_REQUEST['year']);
			$year=$_REQUEST['year'];
		}
		$this->salida .= "</select></td>";
		$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
		if(empty($_REQUEST['meses']))
		{
			$a=explode("-",$_SESSION['CITASMES'][0]);
			if(empty($a[0]))
			{
				$mes=$_REQUEST['meses']=date("m");
				$year=date("Y");
			}
			else
			{
				$mes=$_REQUEST['meses']=$a[1];
			}
			$this->MesesAgenda(True,$year,$mes);
		}
		else
		{
			$this->MesesAgenda(True,$year,$_REQUEST['meses']);
			$mes=$_REQUEST['meses'];
		}
		$this->salida .= "</select>";
		$this->salida .= "</td>";
		if($_SESSION['Atencion']['DiaEspe']!=date("Y-m-d"))
		{
			foreach($_REQUEST as $v=>$datos)
			{
				if($v!='DiaEspe' and $v!='SIIS_SID' and $v!='modulo' and $v!='metodo')
				{
					$vec[$v]=$datos;
				}
			}
			$vec['DiaEspe']=date("Y-m-d");
			$this->salida .= '<td align="center">';
			$accion=ModuloGetURL('app','AgendaMedicaCoc','','AgendaDia',$vec);
			$this->salida .= '<a href="'.$accion.'">Dia Actual</a>';
			$this->salida .= "</td>";
		}
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .='</form>';
		$this->salida .= '<table width="80%" align="center">';
		$this->salida .= '<tr align="center">';
		$this->salida .= '<td align="center">';
		$_REQUEST['metodo']='AgendaDia';
		$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard',$_REQUEST);
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}






//Cancelar Citas
/**
* Esta funcion genera el listado de permisos que tiene el usuario
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function CancelarCitas()
	{
		unset($_SESSION['CancelarCita']);
		SessionDelVar('CITASMES');
		$url[0]='app';
		$url[1]='AgendaMedicaCoc';
		$url[2]='user';
		$url[3]='BuscarPacienteCancelar';
		$url[4]='Citas';
		$cita=$this->TipoConsulta($url);
		if($cita==false)
		{
			return false;
		}
		return true;
	}


/**
* Esta funcion genera la pantalla para la consulta de las citas a cancelar de los pacientes
*
* @access public
* @return boolean Para identificar que se realizo.
* @param string mensaje para presentar en la forma
* @param array arreglo de la informacion de los usuarios
*/


	function BuscarPacienteCancelar($mensaje,$arr,$f)
	{
		unset($_SESSION['CancelarCita']['CITA']);
		unset($_SESSION['AsignacionCita']);
		if(empty($_SESSION['CancelarCita']['cita']))
		{
			$_SESSION['CancelarCita']['cita']=$_REQUEST['Citas']['tipo_consulta_id'];
			$_SESSION['CancelarCita']['departamento']=$_REQUEST['Citas']['departamento'];
			$_SESSION['CancelarCita']['empresa']=$_REQUEST['Citas']['empresa_id'];
			$_SESSION['CancelarCita']['nomcit']=$_REQUEST['Citas']['descripcion3'];
			$_SESSION['CancelarCita']['nomdep']=$_REQUEST['Citas']['descripcion2'];
			$_SESSION['CancelarCita']['nomemp']=$_REQUEST['Citas']['descripcion1'];
			$_SESSION['CancelarCita']['sw_anestesiologia']=$_REQUEST['Citas']['sw_anestesiologia'];
			$_SESSION['CancelarCita']['cargo_cups']=$_REQUEST['Citas']['cargo_cups'];
			$_SESSION['CancelarCita']['sw_busqueda_citas']=$_REQUEST['Citas']['sw_busqueda_citas'];
		}
		$this->salida = ThemeAbrirTabla('CANCELACI??? DE CITAS');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		if(!$_REQUEST['Busqueda']){
			$Busqueda=1;
			$vec['Busqueda']=1;
		}else{
			$Busqueda=$_REQUEST['Busqueda'];
		}
		$this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"60%\">";
		$this->salida .= "	<table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "	<tr>";
		$this->salida .= "	<td>";
		$this->salida .= "	<fieldset>";
		$this->salida .= "		<legend class=\"field\">";
		$this->salida .= "		BUSCAR DATOS CITAS";
		$this->salida .= "		</legend>";
		$this->salida .= "		<table width=\"90%\" align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','BuscarPacientes',array('Busqueda'=>$_REQUEST['Busqueda']));
		$this->salida .= "		<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		if($Busqueda=='1'){
			$this->salida .= "	<tr>";
			$this->salida .= "	<td class=\"label\">";
			$this->salida .= "		TIPO DOCUMENTO: ";
			$this->salida .= "	</td>";
			$this->salida .= "	<td>";
			$this->salida .= "		<select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->BuscarIdPaciente($tipo_id,'False',$_REQUEST['TipoDocumento']);
			$this->salida .= "		</select>";
			$this->salida .= "	</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "	<tr>";
			$this->salida .= "		<td class=\"".$this->SetStyle("Documento")."\">";
			$this->salida .= "		DOCUMENTO: ";
			$this->salida .= "		</td>";
			$this->salida .= "		<td>";
			$this->salida .= "		<input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\">";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
		}
		if($Busqueda=='2'){
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"label\">";
		$this->salida .= "		NOMBRES";
		$this->salida .= "		</td>";
		$this->salida .= "		<td>";
		$this->salida .= "		<input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\" value=\"".$_REQUEST['nombres']."\">";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		$this->salida .= "	<tr>";
		$this->salida .= "		<td class=\"label\">";
		$this->salida .= "		APELLIDOS";
		$this->salida .= "		</td>";
		$this->salida .= "		<td>";
		$this->salida .= "		<input type=\"text\" class=\"input-text\" name=\"apellidos\" maxlength=\"32\" value=\"".$_REQUEST['apellidos']."\">";
		$this->salida .= "		</td>";
		$this->salida .= "	</tr>";
		}
		$this->salida .= "		<tr>";
		$this->salida .= "		<td align=\"right\">";
		$this->salida .= "			<input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\">";
		$this->salida .= "			</form>";
		$this->salida .= "		</td>";
		$this->salida .= "		</fieldset>";
		$this->salida .= "		</tr>";
		$this->salida .= "		</table>";
		$this->salida .= "		</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table>";
		$this->salida .= "</td>";
		$this->salida .= "<td>";
		$this->salida .= "	<table border=\"0\" width=\"92%\" align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','BuscarPacienteCancelar');
		$this->salida .= "	<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<tr>";
		$this->salida .= "	<td>";
		$this->salida .= "		<fieldset>";
		$this->salida .= "		<legend class=\"field\">";
		$this->salida .= "		BUSQUEDA AVANZADA";
		$this->salida .= "		</legend>";
		$this->salida .= "		<table width=\"90%\" align=\"center\">";
		$this->salida .= "		<tr>";
		$this->salida .= "		<td class=\"label\">";
		$this->salida .= "			TIPO BUSQUEDA: ";
		$this->salida .= "		</td>";
		$this->salida .= "		<td>";
		$this->salida .= "		<select name=\"Busqueda\" class=\"select\">";
		$this->salida .="			<option value=\"1\" selected>DOCUMENTO</option>";
		$this->salida .="			<option value=\"2\">NOMBRE</option>";
		$this->salida .= "		</select>";
		$this->salida .= "		</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr>";
		$this->salida .= "		<td colspan=\"2\" align=\"center\">";
		$this->salida .= "		<input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"BUSCAR\">";
		$this->salida .= "		</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "   </form>";
		$this->salida .= "   </table>";
		$this->salida .= "   </fieldset>";
		$this->salida .= " 	 </td>";
		$this->salida .= "	 </tr>";
		$this->salida .= "	 </table>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		if($mensaje){
			$this->salida .= "			<p class=\"label_error\" align=\"center\">$mensaje</p>";
		}
		$vec='';
		foreach($_REQUEST as $v=>$datos){
			if($v!='SIIS_SID' and $v!='modulo' and $v!='metodo'){
				$vec[$v]=$datos;
			}
		}
		if($arr){
			$this->salida .= "		   <br>";
			$this->salida .= "		<table width=\"85%\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\"  align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "<td>IDENTIFICACION</td>";
			$this->salida .= "<td>NOMBRE</td>";
			//$this->salida .= "<td>APELLIDOS</td>";
			$this->salida .= "<td>PROFESIONAL</td>";
			$this->salida .= "<td>FECHA Y HORA</td>";
			$this->salida .= "<td>Acci???</td>";
			$this->salida .= "</tr>";
			$AgendaCitaIdPadreAnt=-1;
			for($i=0;$i<sizeof($arr);$i++){
			  $AgendaCitaIdPadre=$arr[$i][12];
				if($AgendaCitaIdPadre!=$AgendaCitaIdPadreAnt){
          $l=0;
					$conta=0;
          while($l<sizeof($arr)){
					  if($arr[$l][12]==$AgendaCitaIdPadre){
              $conta++;
						}
            $l++;
					}
					$TipoId=$arr[$i][2];
					$PacienteId=$arr[$i][1];
					$PApellido=$arr[$i][3];
					$SApellido=$arr[$i][4];
					$PNombre=$arr[$i][5];
					$SNombre=$arr[$i][6];
					if($i % 2) $estilo='modulo_list_claro';
					else $estilo='modulo_list_oscuro';
					$this->salida .= "<tr class=\"$estilo\">";
					$this->salida .= "<td rowspan=\"$conta\">$TipoId $PacienteId</td>";
					$this->salida .= "<td rowspan=\"$conta\">$PNombre $SNombre $PApellido $SApellido</td>";
					$this->salida .= "<td rowspan=\"$conta\">".$arr[$i][10]."</td>";
					$this->salida .= "<td>".$arr[$i][9]."</td>";
					if($arr[$i][8]!=1){
						$vec1['Datos']=$arr[$i];
						$vec1['Cancelar']=1;
						$accionHRef=ModuloGetURL('app','AgendaMedicaCoc','user','BorrarCitaDatos',$vec1);
						//$this->salida .= "				<td rowspan=\"$conta\" align=\"center\"><a href=\"$accionHRef\">Cancelar</a></td>";
						$this->salida .= "<td rowspan=\"$conta\" align=\"center\">	<input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Cancelar\" onclick=\"location='$accionHRef'\"></td>";

          }else{
						$this->salida .= "				<td rowspan=\"$conta\" align=\"center\">Cita Cancelada</td>";
					}
					$this->salida .= "			</tr>";
				}else{
				  $conta=0;
          if( $i % 2) $estilo='modulo_list_claro';
					else $estilo='modulo_list_oscuro';
					$this->salida .= "<tr class=\"$estilo\">";
          $this->salida .= "<td>".$arr[$i][9]."</td>";
          $this->salida .= "</tr>";
				}
				$AgendaCitaIdPadreAnt=$AgendaCitaIdPadre;
			}//fin for
			$this->salida .= "</table>";
			$this->salida .= "<br>";
		}
		$this->salida .= "<table width=\"90%\" align=\"center\">";
		$this->salida .= "<tr align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','CancelarCitas');
		$this->salida .= "<td><form name=\"Volver\" action=\"$accion\" method=\"post\"><input type=\"submit\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}





/**
* Esta funcion genera la pantalla para mostrar los datos adicionales para borrar una cita
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function DatosAdicionalesBorrarCita()
	{
		if(empty($_SESSION['CancelarCita']['CITA']['cita_asignada_id']))
		{
			$_SESSION['CancelarCita']['CITA']['cita_asignada_id']=$_REQUEST['Datos'][7];
			$_SESSION['CancelarCita']['CITA']['cita_id']=$_REQUEST['Datos'][0];
			$_SESSION['CancelarCita']['CITA']['tipo_id_paciente']=$_REQUEST['Datos'][2];
			$_SESSION['CancelarCita']['CITA']['paciente_id']=$_REQUEST['Datos'][1];
			$_SESSION['CancelarCita']['CITA']['plan_id']=$_REQUEST['Datos'][11];
		}

		$this->salida = ThemeAbrirTabla('DATOS ADICIONALES CANCELACI??? DE CITAS');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$datos=$this->BuscarJustificacion();
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','BorrarCita');
		$this->salida .= "	<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida.='<table width="60%" align="center" border="0" class="modulo_table_list">';
		$this->salida.='<tr class="modulo_list_claro">';
		$this->salida.='<td width="40%">';
		$this->salida.='<label class="'.$this->SetStyle("justificacion").'">MOTIVO: </label>';
		$this->salida.='</td>';
		$this->salida.='<td width="60%" align="center">';
		$this->salida.='<select name="justificacion" class="select">';
		$this->salida.='<option value="-1">--SELECCIONE--</option>';
		foreach($datos as $k=>$v)
		{
			if($_REQUEST['justificacion']==$k)
			{
				$this->salida.='<option value="'.$k.'" selected>'.$v['descripcion'].'</option>';
			}
			else
			{
				$this->salida.='<option value="'.$k.'">'.$v['descripcion'].'</option>';
			}
		}
		$this->salida.='</select>';
		$this->salida.='</td>';
		$this->salida.='</tr>';
		$this->salida.='<tr class="modulo_list_oscuro">';
		$this->salida.='<td width="40%">';
		$this->salida.='<label class="'.$this->SetStyle("Observacion").'">OBSERVACION: </label>';
		$this->salida.='</td>';
		$this->salida.='<td width="60%" align="center">';
		$this->salida .= "<textarea name=\"Observacion\" cols=\"20\" rows=\"3\" class=\"input-text\">".$_REQUEST['Observacion']."</textarea>";
		$this->salida.='</td>';
		$this->salida.='</tr>';
		$this->salida.='<tr class="modulo_list_claro">';
		$this->salida.='<td width="40%">';
		$this->salida.='<label class="'.$this->SetStyle("Observacion").'">TIPO DE CANCELACI???: </label>';
		$this->salida.='</td>';
		$this->salida.='<td width="60%" align="center">';
		$this->salida .= "Liberar Orden<input type=\"radio\" name=\"liberacion\" value=\"1\">";
		$this->salida .= "Cancelar Cita<input type=\"radio\" name=\"liberacion\" value=\"0\" checked>";
		$this->salida.='</td>';
		$this->salida.='</tr>';
		$this->salida.='</table>';
		$this->salida .= "<table width=\"10%\" border=\"0\" align=\"center\">";
		$this->salida .= "<tr align=\"center\">";
		$this->salida .= "<td><input type=\"submit\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\"></form></td>";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','BuscarPacienteCancelar');
		$this->salida .= "<td><form name=\"Volver\" action=\"$accion\" method=\"post\"><input type=\"submit\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}



/**
* Esta funcion permite enlazarse con el proceso de asignacion de cita
*
* @access public
* @return boolean Para identificar que se realizo.
*/


	function AsignarNuevaCita()
	{
		$this->salida = ThemeAbrirTabla('DATOS ADICIONALES CANCELACI??? DE CITAS');
		$this->salida .= "<BR>";
		$this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
		$this->salida .= '<tr align="center" class="modulo_table_title">';
		$this->salida .= '<td align="center">';
		$this->salida .= "Empresa";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Departamento";
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= "Tipo de Cita";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= '<tr class="modulo_list_oscuro">';
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomemp'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomdep'];
		$this->salida .= "</td>";
		$this->salida .= '<td align="center">';
		$this->salida .= $_SESSION['CancelarCita']['nomcit'];
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= "<table width=\"20%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
		$this->salida .= "<tr align=\"center\" class=\"modulo_list_oscuro\">";
		$cosa['Citas']['tipo_consulta_id']=$_SESSION['CancelarCita']['cita'];
		$cosa['Citas']['departamento']=$_SESSION['CancelarCita']['departamento'];
		$cosa['Citas']['empresa_id']=$_SESSION['CancelarCita']['empresa'];
		$cosa['Citas']['descripcion3']=$_SESSION['CancelarCita']['nomcit'];
		$cosa['Citas']['descripcion2']=$_SESSION['CancelarCita']['nomdep'];
		$cosa['Citas']['descripcion1']=$_SESSION['CancelarCita']['nomemp'];
		$cosa['Citas']['sw_anestesiologia']=$_SESSION['CancelarCita']['sw_anestesiologia'];
		$cosa['Citas']['cargo_cups']=$_SESSION['CancelarCita']['cargo_cups'];
		$cosa['Citas']['sw_busqueda_citas']=$_SESSION['CancelarCita']['sw_busqueda_citas'];
		$_SESSION['AsignacionCitas']['Responsable']=$_SESSION['CancelarCita']['CITA']['plan_id'];
		$_SESSION['AsignacionCitas']['TipoDocumento']=$_SESSION['CancelarCita']['CITA']['tipo_id_paciente'];
		$_SESSION['AsignacionCitas']['Documento']=$_SESSION['CancelarCita']['CITA']['paciente_id'];
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','DatosPaciente',$cosa);
		$this->salida .= "<td align=\"center\"><b><a href='$accion'>Asignacion Nueva Cita</a></b></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= "<BR>";
		$this->salida .= "<table width=\"10%\" border=\"0\" align=\"center\">";
		$this->salida .= "<tr align=\"center\">";
		$accion=ModuloGetURL('app','AgendaMedicaCoc','user','BuscarPacienteCancelar');
		$this->salida .= "<td><form name=\"Volver\" action=\"$accion\" method=\"post\"><input type=\"submit\" name=\"Volver\" value=\"Volver\" class=\"input-submit\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}


//Funciones Estandard para algunos combos.

	/**
	* Muestra en el combo los diferentes tipos de vias de ingreso
	* @access private
	* @return string
	* @param array arreglo con las vias de ingreso
	* @param boolean indica si el combo ya esta seleccionado
	* @param int la via de ingreso que viene por defecto
	* @param string tipo de forma
	*/
	function	MostrarZonas()
	{
		$zonas=$this->ZonasResidencia();
		for($i=0; $i<sizeof($zonas); $i++)
		{
			$Zona=$zonas[$i][descripcion];
			$ZonaId=$zonas[$i][zona_residencia];
			if($ZonaId=='U'){
					$this->salida .= "	 $Zona<input type=\"radio\" name=\"Zona\" value=\"$ZonaId\" checked>";
			}
			else{
					$this->salida .= "	 $Zona<input type=\"radio\" name=\"Zona\" value=\"$ZonaId\">";
			}
		}
	}

	/**
	* Muestra en el combo los tipos de ocupaciones
	* @access private
	* @return string
	* @param array con los tipos de ocupacion
	* @param boolean indica si el combo ya esta seleccionado
	* @param int el tipo de ocupacion que viene por defecto
	*/
	function BuscarOcupacion($ocupacion_id,$Seleccionado='False',$Ocupacion='')
	{
		$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
		switch($Seleccionado){
			case 'False':{
					foreach($ocupacion_id as $value=>$titulo){
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				break;
			}

			case 'True':{
					foreach($ocupacion_id as $value=>$titulo){
						if($value==$Ocupacion){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}
						else{
						   $this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				break;
			}
		}
	}

	/**
	* Muestra en el combo los tipos de estado civil
	* @access private
	* @return string
	* @param array con los tipos de estados civil
	* @param boolean indica si el combo ya esta seleccionado
	* @param int el tipo de estado civil que viene por defecto
	*/
	function BuscarEstadoCivil($estado_civil_id,$Seleccionado='False',$EstadoCivil='')
	{
		$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
		switch($Seleccionado){
			case 'False':{
					foreach($estado_civil_id as $value=>$titulo){
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				break;
			}

			case 'True':{
					foreach($estado_civil_id as $value=>$titulo){
						if($value==$EstadoCivil){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}
						else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				break;
			}
		}
	}

	/**
	* Muestra el nombre del tercero con sus respectivos planes
	* @access private
	* @return string
	* @param array arreglor con los tipos de responsable
	* @param int el responsable que viene por defecto
	*/
 function MostrarResponsable($responsables,$Responsable)
 {
      $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
			for($i=0; $i<sizeof($responsables); $i++)
			{
					if($responsables[$i][plan_id]==$Responsable){
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
					}else{
							$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
					}
			}
 }

	/**
	* Muestra en el combo los tipo de sexo
	* @access private
	* @return string
	* @param array con los tipos de sexo
	* @param boolean indica si el combo ya esta seleccionado
	* @param string el sexo que viene por defecto
	*/

	function BuscarSexo($sexo_id,$Sexo)
	{
		$this->salida .= '<select name="Sexo" class="select">';
		$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
		foreach($sexo_id as $value=>$titulo)
		{
			if($value==$Sexo)
			{
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}
			else
			{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
		$this->salida .= '</select>';
	}




/**
	* Muestra el combo de los tipo id paciente
	* @access private
	* @param array con los tipos de identificacion
	* @param boolean si ya esta seleccionado
	* @param string el tipo seleccionado
	*/


	function BuscarIdPaciente($tipo_id,$Seleccionado='False',$TipoId='')
	{
		switch($Seleccionado){
			case 'False':{
				foreach($tipo_id as $value=>$titulo){
					if($value==$TipoId){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
			case 'True':{
				foreach($tipo_id as $value=>$titulo){
					if($value==$TipoId){
						$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
						$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
				}
				break;
			}
		}
	}




/**
	* Muestra los a??os en los que se puede buscar la agenda medica
	* @access private
	* @param boolean si ya esta seleccionado
	* @param string a??o se??alado
	*/



	function AnosAgenda($Seleccionado='False',$ano)
	{

		$anoActual=date("Y");
		$anoActual1=$anoActual;
    for($i=0;$i<=10;$i++)
		{
      $vars[$i]=$anoActual1;
			$anoActual1=$anoActual1+1;
		}
		switch($Seleccionado)
		{
			case 'False':
			{
				foreach($vars as $value=>$titulo)
				{
          if($titulo==$ano)
					{
					  $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
				  }
				}
				break;
		  }case 'True':
			{
			  foreach($vars as $value=>$titulo)
				{
					if($titulo==$ano)
					{
				    $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
				  }else{
				    $this->salida .=" <option value=\"$titulo\">$titulo</option>";
					}
				}
				break;
		  }
	  }
	}





/**
	* Muestra los meses para realizar la consulta de la agenda
	* @access private
	* @param boolean si ya esta seleccionado
	* @param string a??o se??alado
	* @param string mes por defecto
	*/


	function MesesAgenda($Seleccionado='False',$A???,$Defecto)
	{
		$anoActual=date("Y");
		$vars[1]='ENERO';
    $vars[2]='FEBRERO';
		$vars[3]='MARZO';
		$vars[4]='ABRIL';
		$vars[5]='MAYO';
		$vars[6]='JUNIO';
		$vars[7]='JULIO';
		$vars[8]='AGOSTO';
		$vars[9]='SEPTIEMBRE';
		$vars[10]='OCTUBRE';
		$vars[11]='NOVIEMBRE';
		$vars[12]='DICIEMBRE';
		$mesActual=date("m");
		switch($Seleccionado)
		{
			case 'False':
			{
			  if($anoActual==$A???)
				{
			    foreach($vars as $value=>$titulo)
					{
				    if($value>=$mesActual)
						{
						  if($value==$Defecto)
							{
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
					}
				}
				else
				{
          foreach($vars as $value=>$titulo)
					{
						if($value==$Defecto)
						{
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
			case 'True':
			{
			  if($anoActual==$A???)
				{
				  foreach($vars as $value=>$titulo)
					{
					  if($value>=$mesActual)
						{

						  if($value==$Defecto)
							{
								$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else
							{
								$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
					}
				}
				else
				{
          foreach($vars as $value=>$titulo)
					{
						if($value==$Defecto)
						{
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
						}else
						{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
						}
					}
				}
				break;
			}
		}
	}

/**
	* esta funcion determina segun el vector de frmError si existe algun campo sin llenar
	* @return string
	* @access private
	* @param string identificacion del campo para se??alar como no lleno
	*/

	

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
	/**********************************************************************************
	*
	***********************************************************************************/
	function MostrarEventosSoat()
	{
		$paciente = $this->BuscarNombrePaci($this->TipoDoc,$this->Documento);
		
		$this->salida  = ThemeAbrirTabla('EVENTOS SOAT RELACIONADOS CON EL PACIENTE');
		if(!empty($paciente))
		{
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida	.= "	<table  align=\"center\" border=\"0\"  width=\"80%\">\n";
			$this->salida .= "		".$this->SetStyle("MensajeError");
			$this->salida	.= "	</table>";
			$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\">\n";
			$this->salida .= "  	<tr>\n";
			$this->salida .= "  		<td>\n";
			$this->salida .= "  			<fieldset><legend class=\"field\">EVENTOS SOAT DEL PACIENTE</legend>\n";
			$this->salida .= "      		<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "      			<tr class=modulo_list_claro>\n";
			$this->salida .= "      				<td class=\"modulo_table_list_title\" width=\"15%\">DOCUMENTO:</td>\n";
			$this->salida .= "      				<td width=\"25%\">\n";
			$this->salida .= "      						<b>".$this->TipoDoc."".' - '."".$this->Documento."</b>\n";
			$this->salida .= "      				</td>\n";
			$this->salida .= "      				<td class=\"modulo_table_list_title\" width=\"10%\">NOMBRE:</td>\n";
			$this->salida .= "      				<td width=\"50%\">\n";
			$this->salida .= "      					<b>".$paciente['apellidos']." ".$paciente['nombres']."</b>\n";
			$this->salida .= "      				</td>\n";
			$this->salida .= "      			</tr>\n";
			$this->salida .= "      		</table><br>\n";
			
			$evensoat = $this->BuscarEventoSoatPaciente($this->TipoDoc,$this->Documento);
	
			if(!empty($evensoat))
			{
				$this->salida .= "<form name=\"formadmin\" action=\"".$this->action2."\" method=\"post\">\n";
				$this->salida .= "      		<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "      			<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "      				<td width=\"3%\">No.</td>\n";
				$this->salida .= "      				<td width=\"9%\" >FECHA</td>\n";
				$this->salida .= "      				<td width=\"9%\" >HORA</td>\n";
				$this->salida .= "      				<td width=\"20%\">POLIZA</td>\n";
				$this->salida .= "      				<td width=\"30%\">ASEGURADORA</td>\n";
				$this->salida .= "      				<td width=\"11%\">SALDO</td>\n";
				$this->salida .= "      				<td width=\"9%\" >ELEGIR</td>\n";
				$this->salida .= "      			</tr>\n";
	
				$k=1;				
				
				for($i = 0; $i<sizeof($evensoat); $i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro';  $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					($evensoat[$i]['poliza']==$evensoat[$i+1]['poliza'])? $k++:	$k=1;
					
					$this->salida .= "			<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "				<td align=\"center\">".$k."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['fecha_accidente']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['hora_accidente']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['poliza']."</td>\n";
					$this->salida .= "				<td align=\"center\">".$evensoat[$i]['nombre_tercero']."</td>\n";
					$this->salida .= "				<td align=\"center\">".number_format(($evensoat[$i]['saldo']), 2, ',', '.')."</td>\n";
					$this->salida .= "				<td align=\"center\">\n";
					$this->salida .= "				<input type='radio' name='eligevento' value=\"".$evensoat[$i]['evento']."\">\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "    </table>\n";
				$this->salida .= "	</fieldset>\n";
				$this->salida .= "</table><br>\n";
				$this->salida .= "<table width=\"50%\" align=\"center\" >\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td>\n";
				$this->salida .= "	</form>\n";
				$this->salida .= "  <form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
				$this->salida .= "		<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Cancelar\"></td>";
				$this->salida .= "	</form>\n";
				$this->salida .= "  </tr>\n";
				$this->salida .= "</table>\n";
			}
			else
			{
				$this->salida .= "    <table border=\"0\" width=\"100%\" align=\"center\" >\n";
				$this->salida .= "    	<tr>\n";
				$this->salida .= "				<td aling=\"justify\">\n";
				$this->salida .= "					<b class=\"label_error\">EL PACIENTE NO POSEE UN EVENTO SOAT RELACIONADO, SE DEBE CREAR UN ENVENRTO PARA SEGUIR CON LA ADMISION</b></center><br><br>\n";
				$this->salida .= "				</td>\n";
				$this->salida .= "			</tr>\n";
				$this->salida .= "    </table>\n";
				
				$this->salida .= "	</fieldset>\n";
				$this->salida .= "</table><br>\n";
				$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
				$this->salida .= "		<tr>\n";
				$this->salida .= "    	<form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
				$this->salida .= "				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>";
				$this->salida .= "			</form>\n";
				$this->salida .= "   	</tr>\n";
				$this->salida .= "	</table>\n";
			}
		}
		else
		{
			$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" >\n";
			$this->salida .= "   	<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<b class=\"label_error\">EL PACIENTE NO SE ENCUENTRA REGISTRADO EN BASE DE DATOS</b></center><br><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "  </table><br>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$this->salida .= "    	<form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>";
			$this->salida .= "			</form>\n";
			$this->salida .= "   	</tr>\n";
			$this->salida .= "	</table>\n";
		}
		$this->salida .= ThemeCerrarTabla();
	}
	
	function FormaAsignacionCitaAutomatica($msg)
	{
			$this->salida  = ThemeAbrirTabla('','60%');
			$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" >\n";
			$this->salida .= "   	<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<b class=\"label_mark\">ASIGNACION DE CITA AUTOMATICA COMPLETA</b></center><br><br>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "  </table><br>\n";
			$this->salida .= "	<table width=\"50%\" align=\"center\" >\n";
			$this->salida .= "		<tr>\n";
			$action1 = ModuloGetURL('app','AgendaMedicaCoc','user','Menu');
			$this->salida .= "    	<form name=\"cancelar\" action=\"".$action1."\" method=\"post\">\n";
			$this->salida .= "				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Volver\"></td>";
			$this->salida .= "			</form>\n";
			$this->salida .= "   	</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}
}

?>
