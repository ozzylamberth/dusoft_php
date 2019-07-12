<?php

/**
 * $Id: app_CajaRapida_userclasses_HTML.php,v 1.2 2005/06/07 12:57:27 leydi Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_CajaRapida_userclasses_HTML extends app_CajaRapida_user
{
	/**
	*Constructor de la clase app_CentroAutorizacion_user_HTML
	*El constructor de la clase app_CentroAutorizacion_user_HTML se encarga de llamar
	*a la clase app_CentroAutorizacion_user quien se encarga de el tratamiento
	* de la base de datos.
	*/

  function app_CajaRapida_user_HTML()
	{
				$this->salida='';
				$this->app_CajaRapida_user();
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
	* Forma para capturar los datos para buscar el paciente
	* @access private
	* @return boolean
	*/
	function FormaBuscar()
	{
				$action=ModuloGetURL('app','CajaRapida','user','BuscarPaciente');
				$this->salida .= ThemeAbrirTabla('CAJA RAPIDA - BUSCAR PACIENTE');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("plan")."\">PLAN: </td><td><select name=\"plan\" class=\"select\">";
				$responsables=$this->responsables();
				if(!empty($_SESSION['CAJARAPIDA']['PACIENTE']['plan_id']))
				{  $_REQUEST['plan']=$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id'];  }
				$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
				for($i=0; $i<sizeof($responsables); $i++)
				{
						if($responsables[$i][plan_id]==$_REQUEST['plan']){
								$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
						}else{
								$this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
						}
				}
				$this->salida .= "              </select></td></tr>";
				if(!empty($_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']))
				{  $_REQUEST['Tipo']=$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'];  }
					$this->salida .= "				       <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"Tipo\" class=\"select\">";
        $tipo_id=$this->CallMetodoExterno('app','Triage','user','tipo_id_paciente','');
				foreach($tipo_id as $value=>$titulo)
				{
						if($value==$_REQUEST['Tipo'])
						{  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{  $this->salida .=" <option value=\"$value\">$titulo</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				if(!empty($_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']))
				{  $_REQUEST['Documento']=$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id'];  }
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
				$this->salida .= "				       <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
				if(!empty($_SESSION['CAJARAPIDA']['EXT']))
				{
						$contenedor=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['contenedor'];
						$modulo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['modulo'];
						$tipo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['tipo'];
						$metodo=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['metodo'];
						$argumentos=$_SESSION['CAJARAPIDA']['EXT']['RETORNO']['argumentos'];
						$actionM=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
				}
				else
				{ 	$actionM=ModuloGetURL('app','CajaRapida','user','main');   }
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
				$this->salida .= "			     </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function DatosCompletos()
	{
				if(empty($_SESSION['CAJARAPIDA']['PACIENTE']['nombre']))
				{
						$nom=$this->NombrePaciente($_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'],$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']);
						$_SESSION['CAJARAPIDA']['PACIENTE']['nombre']=$nom['nombre'];
						$plan=$this->NombrePlan($_SESSION['CAJARAPIDA']['PACIENTE']['plan_id']);
						$_SESSION['CAJARAPIDA']['PACIENTE']['plan_descripcion']=$plan['plan_descripcion'];
				}
				$this->salida .= "		 <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']." ".$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['CAJARAPIDA']['PACIENTE']['nombre']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"7%\">PLAN:</td><td width=\"40%\" class=\"modulo_list_claro\">".$_SESSION['CAJARAPIDA']['PACIENTE']['plan_descripcion']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= " 			</table><BR>";
	}

//-------------------------------APOYOS-----------------------------------------------

	function frmForma()
	{
			$this->salida= ThemeAbrirTablaSubModulo('CAJA RAPIDA - SOLICITUD DE APOYOS DIAGNOSTICOS');
			$this->DatosCompletos();
			//$vector1=$this->Consulta_Solicitud_Apoyod();
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			if(!empty($_SESSION['ARREGLO']['AUTORIZACIONES']))
		  //if($vector1)
	    {
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">CAJA RAPIDA - APOYOS DIAGNOSTICOS SOLICITADOS</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"7%\">TIPO</td>";
					$this->salida.="  <td width=\"9%\">CARGO</td>";
					$this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
					$this->salida.="  <td colspan= 2 width=\"13%\">OPCION</td>";
					$this->salida.="</tr>";
					foreach($_SESSION['ARREGLO']['AUTORIZACIONES'] as $k => $v)
          {        //$k es hc_os_solicitud_id
					//for($i=0;$i<sizeof($vector1);$i++)
								$vector1=$this->Consulta_Solicitud_Apoyod($k);
								$hc_os_solicitud_id =$vector1[hc_os_solicitud_id];
								$tipo=$vector1[tipo];
								$cargo=$vector1[cargo];
								$descripcion= $vector1[descripcion];
								$observacion= $vector1[observacion];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td ROWSPAN = 4 align=\"center\" width=\"7%\">$tipo</td>";
								$this->salida.="  <td align=\"center\" width=\"9%\">$cargo</td>";
								$this->salida.="  <td align=\"left\" width=\"52%\">$descripcion</td>";
								$accion1=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'observacion','hc_os_solicitud_idapoyo' => $hc_os_solicitud_id, 'cargoapoyo'=>$cargo, 'descripcionapoyo' => $descripcion, 'observacionapoyo'=> $observacion));
								$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
								$accion2=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'eliminar', 'hc_os_solicitud_idapoyo'=> $hc_os_solicitud_id));
								$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$observacion</td>";
								$this->salida.="</tr>";
								$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">";
								$this->salida.="<table>";
								for($j=0;$j<sizeof($diag);$j++)
								{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
										$this->salida.="<td colspan = 2>".$diag[$j][diagnostico_nombre]."</td>";
										$this->salida.="</tr>";
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"modulo_table_title\">";
                $this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\" >INFORMACION</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">".$vector1[informacion_cargo]."</td>";
								$this->salida.="</tr>";
						}
						$this->salida.="</table><br>";
				}
				$accion1=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada','Ofapoyo'=>$_REQUEST['Ofapoyo'],'paso1'=>$_REQUEST['paso1apoyo'],
				'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
				'cargoapoyo'=>$_REQUEST['cargoapoyo'],
				'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE APOYOS DIAGNOSTICOS - BUSQUEDA AVANZADA </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">TIPO</td>";
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio1apoyo'  class =\"select\">";
				$this->salida.="<option value = '001' selected>Todos</option>";
				if (($_REQUEST['criterio1'])  == '002')
				{
						$this->salida.="<option value = '002' selected>Frecuentes</option>";
				}
				else
				{
						$this->salida.="<option value = '002' >Frecuentes</option>";
				}
				$categoria = $this->tipos();
				for($i=0;$i<sizeof($categoria);$i++)
				{
					$apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
					$opcion = $categoria[$i][descripcion];

					if (($_REQUEST['criterio1'])  != $apoyod_tipo_id)
					{

						$this->salida.="<option value = $apoyod_tipo_id>$opcion</option>";
					}
					else
					{
						$this->salida.="<option value = $apoyod_tipo_id selected >$opcion</option>";
					}
				}
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"6%\">CARGO:</td>";
				$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargoapoyo'  value =\"".$_REQUEST['cargoapoyo']."\"    ></td>" ;
				$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
				$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionapoyo'   value =\"".$_REQUEST['descripcionapoyo']."\"        ></td>" ;
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";
				//$accionV=ModuloGetURL('app','CajaRapida','user','PedirAutorizacion');
				//$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
				//$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"TERMINAR SOLICITUD\"></form></p>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\">";
				$this->salida.="<tr>";
				$accionV=ModuloGetURL('app','CajaRapida','user','PedirAutorizacion');
				$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<td align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"TERMINAR SOLICITUD\"></form></td>";
				$accionV=ModuloGetURL('app','CajaRapida','user','Cancelar');
				$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<td align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"CANCELAR\"></form></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida .= ThemeCerrarTablaSubModulo();
				return true;
}

	/**
	*
	*/
	function frmForma_Seleccion_Apoyos($vectorA)
	{
					$this->salida= ThemeAbrirTablaSubModulo('CAJA RAPIDA - APOYO DIAGNOSTICO');
					$this->DatosCompletos();
					$vector1=$this->Consulta_Solicitud_Apoyod();
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";					
		  		if($vector1)
	     	 {
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"5\">CAJA RAPIDO - APOYOS DIAGNOSTICOS SOLICITADOS</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"7%\">TIPO</td>";
					$this->salida.="  <td width=\"9%\">CARGO</td>";
					$this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
					$this->salida.="  <td colspan= 2 width=\"13%\">OPCION</td>";
					$this->salida.="</tr>";
					for($i=0;$i<sizeof($vector1);$i++)
					{
								$hc_os_solicitud_id =$vector1[$i][hc_os_solicitud_id];
								$tipo=$vector1[$i][tipo];
								$cargo=$vector1[$i][cargo];
								$descripcion= $vector1[$i][descripcion];
								$observacion= $vector1[$i][observacion];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td ROWSPAN = 3 align=\"center\" width=\"7%\">$tipo</td>";
								$this->salida.="  <td align=\"center\" width=\"9%\">$cargo</td>";
								$this->salida.="  <td align=\"left\" width=\"52%\">$descripcion</td>";
								$accion1=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'observacion','hc_os_solicitud_idapoyo' => $hc_os_solicitud_id, 'cargoapoyo'=>$cargo, 'descripcionapoyo' => $descripcion, 'observacionapoyo'=> $observacion));
								$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion1'><img src=\"".GetThemePath()."/images/modificar.png\" border='0'></a></td>";
								$accion2=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'eliminar', 'hc_os_solicitud_idapoyo'=> $hc_os_solicitud_id));
								$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accion2'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
								$this->salida.="</tr>";
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">$observacion</td>";
								$this->salida.="</tr>";
								$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
								$this->salida.="<tr class=\"$estilo\">";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"9%\">Diagnosticos</td>";
								$this->salida.="  <td colspan = 3 align=\"left\" width=\"64%\">";
								$this->salida.="<table>";
								for($j=0;$j<sizeof($diag);$j++)
								{
										$this->salida.="<tr class=\"$estilo\">";
										$this->salida.="<td colspan = 1>".$diag[$j][diagnostico_id]."</td>";
										$this->salida.="<td colspan = 2>".$diag[$j][diagnostico_nombre]."</td>";
										$this->salida.="</tr>";
								}
								$this->salida.="</table>";
								$this->salida.="</td>";
										$this->salida.="</tr>";
						}
						$this->salida.="</table><br>";
				}
					$accion1=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada','Ofapoyo'=>$_REQUEST['Ofapoyo'],'paso1'=>$_REQUEST['paso1apoyo'],
					'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
					'cargoapoyo'=>$_REQUEST['cargoapoyo'],
					'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));
					$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="  <td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"5%\">TIPO</td>";
					$this->salida.="<td width=\"10%\" align = left >";
					$this->salida.="<select size = 1 name = 'criterio1apoyo'  class =\"select\">";
					$this->salida.="<option value = '001' selected>Todos</option>";
					$categoria = $this->tipos();
					for($i=0;$i<sizeof($categoria);$i++)
					{
						$apoyod_tipo_id = $categoria[$i][apoyod_tipo_id];
						$opcion = $categoria[$i][descripcion];
						if (($_REQUEST['criterio1'])  != $apoyod_tipo_id)
						{
							$this->salida.="<option value = $apoyod_tipo_id>$opcion</option>";
						}
						else
						{
							$this->salida.="<option value = $apoyod_tipo_id selected >$opcion</option>";
						}
					}
					$this->salida.="</select>";
					$this->salida.="</td>";
					$this->salida.="<td width=\"6%\">CARGO:</td>";
					$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name = 'cargoapoyo'  value =\"".$_REQUEST['cargoapoyo']."\"    ></td>" ;
					$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
					$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = 'descripcionapoyo'   value =\"".$_REQUEST['descripcionapoyo']."\"        ></td>" ;
					$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
					$this->salida.="</tr>";
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida.="</form>";
					$accion=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'insertar_varias'));
					$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion\" method=\"post\">";
					if ($vectorA)
					{
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"15%\">TIPO</td>";
						$this->salida.="  <td width=\"10%\">CARGO</td>";
						$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
						$this->salida.="  <td width=\"5%\">OPCION</td>";
						$this->salida.="</tr>";
						for($i=0;$i<sizeof($vectorA);$i++)
						{
							$apoyod_tipo_id = $vectorA[$i][apoyod_tipo_id];
							$tipo           = $vectorA[$i][tipo];
							$cargo          = $vectorA[$i][cargo];
							$descripcion    = $vectorA[$i][descripcion];

							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="  <td align=\"center\" width=\"15%\">$tipo</td>";
							$this->salida.="  <td align=\"center\" width=\"10%\">$cargo</td>";
							$this->salida.="  <td align=\"left\" width=\"50%\">$descripcion</td>";
							$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opapoyo[$i]' value = ".$cargo.",".$apoyod_tipo_id."></td>";
							$this->salida.="</tr>";
						}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
						$this->salida.="</tr>";
						$this->salida.="</table><br>";
						$var=$this->RetornarBarraExamenes_Avanzada();
						if(!empty($var))
						{
							$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
							$this->salida .= "  <tr>";
							$this->salida .= "  <td width=\"100%\" align=\"center\">";
							$this->salida .=$var;
							$this->salida .= "  </td>";
							$this->salida .= "  </tr>";
							$this->salida .= "  </table><br>";
						}
				}
				$this->salida .= "</form>";
				//BOTON VOLVER
				$accionV=ModuloGetURL('app','CajaRapida','user','Apoyos');
				$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
				$this->salida .= ThemeCerrarTablaSubModulo();
				return true;
	}

	/**
	*
	*/
 	function RetornarBarraExamenes_Avanzada()//Barra paginadora de los planes clientes
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1apoyo'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada','conteoapoyo'=>$this->conteo,'paso1apoyo'=>$_REQUEST['paso1apoyo'],
		'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
		'cargoapoyo'=>$_REQUEST['cargoapoyo'],
		'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset(1)."&paso1apoyo=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso-1)."&paso1apoyo=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

	/**
	*
	*/
	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	/**
	*
	*/
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	/**
	*
	*/
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}


	/**
	*
	*/
 	function RetornarBarraDiagnosticos_Avanzada()
	{
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1apoyo'];
		if(empty($paso))
		{
			$paso=1;
		}
		$accion=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada_Diagnosticos','conteoapoyo'=>$this->conteo,'paso1apoyo'=>$_REQUEST['paso1apoyo'],
		'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],
		'cargoapoyo'=>$_REQUEST['cargoapoyo'],
		'descripcionapoyo'=>$_REQUEST['descripcionapoyo']));

		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset(1)."&paso1apoyo=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso-1)."&paso1apoyo=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}

		/**
		*
		*/
		function frmForma_Modificar_Observacion($hc_os_solicitud_id, $cargo, $descripcion, $observacion, $vectorD)
		{
				$this->salida= ThemeAbrirTablaSubModulo('APOYO DIAGNOSTICO');
				$this->DatosCompletos();
				$accion=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'modificar','hc_os_solicitud_idapoyo'=>$hc_os_solicitud_id));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"2\">OBSERVACION</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"15%\">CARGO</td>";
				$this->salida.="  <td width=\"65%\">DESCRIPCION</td>";
				$this->salida.="</tr>";
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">$cargo</td>";
				$this->salida.="  <td align=\"left\" width=\"65%\">$descripcion</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
				$this->salida .="<td width=\"65%\" align='center'><textarea class='textarea' name = 'obsapoyo' cols = 100 rows = 3>$observacion</textarea></td>" ;
				$this->salida.="</tr>";
				$diag =$this->Diagnosticos_Solicitados($hc_os_solicitud_id);
				if ($diag)
				{
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="<td align=\"center\" width=\"15%\">DIAGNOSTICOS</td>";
					$this->salida.="<td width=\"65%\">";
					$this->salida.="<table>";
					for($i=0;$i<sizeof($diag);$i++)
						{
							$this->salida.="<tr class=\"$estilo\">";
							$accionE=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'eliminar_diagnostico', 'hc_os_solicitud_idapoyo'=> $hc_os_solicitud_id, 'codigoapoyo'=> $diag[$i][diagnostico_id],
							'hc_os_solicitud_idapoyo'=>$_REQUEST['hc_os_solicitud_idapoyo'],
							'cargoapoyo'=>$_REQUEST['cargoapoyo'],
							'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],
							'observacionapoyo'=>$_REQUEST['observacionapoyo']));
							$this->salida.="  <td align=\"center\" width=\"6%\"><a href='$accionE'><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_id]."</td>";
							$this->salida.="<td align=\"left\">".$diag[$i][diagnostico_nombre]."</td>";
							$this->salida.="<tr>";
						}
					$this->salida.="</table>";
					$this->salida .="</td>" ;
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida .= "</form>";
				$accionD=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'Busqueda_Avanzada_Diagnosticos',
				'Ofapoyo'=>$_REQUEST['Ofapoyo'],'paso1'=>$_REQUEST['paso1apoyo'],
				'codigoapoyo'=>$_REQUEST['codigoapoyo'],
				'diagnosticoapoyo'=>$_REQUEST['diagnosticoapoyo'],
				'hc_os_solicitud_idapoyo'=>$_REQUEST['hc_os_solicitud_idapoyo'],
				'cargoapoyo'=>$_REQUEST['cargoapoyo'],
				'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],
				'observacionapoyo'=>$_REQUEST['observacionapoyo']));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accionD\" method=\"post\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"4%\">CODIGO:</td>";
				$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigoapoyo'></td>" ;
				$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
				$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = 'diagnosticoapoyo'   value =\"".$_REQUEST['diagnostico']."\"        ></td>" ;
				$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";
				$accionI=ModuloGetURL('app','CajaRapida','user','GetForma',array('accionapoyo'=>'insertar_varios_diagnosticos',
				'hc_os_solicitud_idapoyo'=>$_REQUEST['hc_os_solicitud_idapoyo'],
				'cargoapoyo'=>$_REQUEST['cargoapoyo'],
				'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],
				'observacionapoyo'=>$_REQUEST['observacionapoyo']));
				$this->salida .= "<form name=\"formadesapoyo\" action=\"$accionI\" method=\"post\">";
				if ($vectorD)
					{
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\">";
							$this->salida.="  <td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
							$this->salida.="</tr>";

							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"10%\">CODIGO</td>";
							$this->salida.="  <td width=\"65%\">DIAGNOSTICO</td>";
							$this->salida.="  <td width=\"5%\">OPCION</td>";
							$this->salida.="</tr>";
							for($i=0;$i<sizeof($vectorD);$i++)
							{
									$codigo          = $vectorD[$i][diagnostico_id];
									$diagnostico    = $vectorD[$i][diagnostico_nombre];

									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\">";

									$this->salida.="  <td align=\"center\" width=\"10%\">$codigo</td>";
									$this->salida.="  <td align=\"left\" width=\"65%\">$diagnostico</td>";
									$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= 'opDapoyo[$i]' value = ".$hc_os_solicitud_id.",".$codigo."></td>";
									$this->salida.="</tr>";

								}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida .= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardarapoyo\" type=\"submit\" value=\"GUARDAR\"></td>";
							$this->salida.="</tr>";
							$this->salida.="</table><br>";
							$var=$this->RetornarBarraDiagnosticos_Avanzada();
								if(!empty($var))
									{
										$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
										$this->salida .= "  <tr>";
										$this->salida .= "  <td width=\"100%\" align=\"center\">";
										$this->salida .=$var;
										$this->salida .= "  </td>";
										$this->salida .= "  </tr>";
										$this->salida .= "  </table><br>";
									}
							}
					$this->salida .= "</form>";
					//BOTON VOLVER
					$accionV=ModuloGetURL('app','CajaRapida','user','Apoyos');
					$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
					$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
					$this->salida .= ThemeCerrarTablaSubModulo();
					return true;
		}


	 /**
  * Forma para los mansajes
	* @access private
	* @return void
  */
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
				$this->salida .= ThemeAbrirTabla($titulo);
				$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
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
	*
	*/
	function FormaListadoCargos($arr)
	{
			IncludeLib("tarifario");
			$this->salida .= ThemeAbrirTabla('CAJA RAPIDA - CARGOS ORDENES SERVICIO');
			$this->DatosCompletos();
			//mensaje
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "	</table><br>";
			$accion=ModuloGetURL('app','CajaRapida','user','CrearOrdenServicio',array('datos'=>$arr));
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			for($i=0; $i<sizeof($arr);)
			{
					$this->salida .= "		 <table width=\"98%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
					$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "				<td>CARGO</td>";
					$this->salida .= "				<td>DESCRICPION</td>";
					$this->salida .= "				<td>PROVEEDOR</td>";
					$this->salida .= "			</tr>";
					$d=$i;
					if($i % 2) {  $estilo="modulo_list_claro";  }
					else {  $estilo="modulo_list_oscuro";   }
					$this->salida .= "			<tr class=\"$estilo\">";
					$this->salida .= "				<td align=\"center\" width=\"10%\">".$arr[$i][cargos]."</td>";
					$this->salida .= "				<td >".$arr[$i][descar]."</td>";
					$this->salida .= "				<td align=\"center\">".$_SESSION['CAJARAPIDA']['DPTONOMBRE']."</td>";
					$this->salida .= "       <input type=\"hidden\" name=\"Combo".$arr[$d][hc_os_solicitud_id]."\" value=\"".$arr[$i][hc_os_solicitud_id].",".$_SESSION['CAJARAPIDA']['DPTO'].",dpto,".$arr[$i][tarifario_id].",".$arr[$i][cargo].",".$arr[$i][cargos].",".$arr[$i][fecha]."\">";
					$this->salida .= "			</tr>";
					$this->salida .= "			<tr>";
					$this->salida .= "			<td colspan=\"3\">";
					$this->salida .= "		 <table width=\"100%\" border=\"0\" align=\"center\" class=\"normal_10\">";
					$this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "				<td>CARGO</td>";
					$this->salida .= "				<td>TARIFARIO</td>";
					$this->salida .= "				<td>DESCRICPION</td>";
					$this->salida .= "				<td>PRECIO</td>";
					$this->salida .= "				<td></td>";
					$this->salida .= "			</tr>";
					$x=0;
					while($arr[$i][cargos]==$arr[$d][cargos])
					{
							$this->salida .= "			<tr class=\"$estilo\">";
							$this->salida .= "			<td align=\"center\" width=\"10%\">".$arr[$d][cargo]."</td>";
							$this->salida .= "			<td align=\"center\" width=\"10%\">".$arr[$d][tarifario_id]."</td>";
							$this->salida .= "			<td width=\"70%\">".$arr[$d][descripcion]."</td>";
							$cargos[]=array('tarifario_id'=>$arr[$d][tarifario_id],'cargo'=>$arr[$d][cargo],'cantidad'=>1,'autorizacion_int'=>$_SESSION['CENTROAUTORIZACION']['TODO']['NumAutorizacion'],'autorizacion_ext'=>'');
							$liq=LiquidarCargosCuentaVirtual($cargos, $_SESSION['CENTROAUTORIZACION']['TODO']['PLAN'] ,$_SESSION['CENTROAUTORIZACION']['TODO']['tipo_afiliado_id'] ,$_SESSION['CENTROAUTORIZACION']['TODO']['rango'] ,$_SESSION['CENTROAUTORIZACION']['TODO']['semanas'],$arr[$d][servicio]);
							$this->salida .= "			<td align=\"center\" width=\"15%\">".FormatoValor($liq[0][valor_cargo])."</td>";
							if($_REQUEST['Op'.$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]]==$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id])
							{  $this->salida .= "			<td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id]."\" name=\"Op".$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]."\" checked></td>";  }
							else
							{  $this->salida .= "			<td width=\"5%\" align=\"center\"><input type=\"checkbox\" value=\"".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargo].",".$arr[$d][tarifario_id]."\" name=\"Op".$arr[$d][hc_os_solicitud_id].$arr[$d][cargo].$arr[$d][tarifario_id]."\"></td>";  }
							$this->salida .= "			</tr>";
							$d++;
							$x++;
					}
					$i=$d;
					$this->salida .= " </table>";
					$this->salida .= "			</td>";
					$this->salida .= "			</tr>";
					$this->salida .= " </table><br>";
			}
			$this->salida .= "		<table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">";
			$this->salida .= "				       <tr>";
			$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar1\" value=\"ACEPTAR\"></td>";
			$this->salida .= "				       				</form>";
			$this->salida .= "				       </tr>";
			$this->salida .= "  </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}

	/**
	* Separa la fecha del formato timestamp
	* @access private
	* @return string
	* @param date fecha
	*/
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
//					return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
	}

	/**
	* Separa la hora del formato timestamp
	* @access private
	* @return string
	* @param date hora
	*/
	function HoraStamp($hora)
	{
			$hor = strtok ($hora," ");
			for($l=0;$l<4;$l++)
			{
				$time[$l]=$hor;
				$hor = strtok (":");
			}

			$x = explode (".",$time[3]);
			return  $time[1].":".$time[2].":".$x[0];
	}

//-----------------------PAGO CAJA------------------------------------------------
	/*
	* Esta funcion te muestra en detalle las ordenes de servicio
	* filtrados por(tipo_afiliado_id,rango,orden_servicio_id),y separarados por plan.
	* @return boolean
	*/
	 function FrmOrdenar($nom,$tipo,$id)
	 {
			$this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIO');
			//$this->Encabezado();
			$this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
			$this->salida .="".$this->SetStyle("MensajeError")."";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['CAJARAPIDA']['PACIENTE']['nombre']."</td></tr>";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente']."&nbsp;".$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id']."</td></tr>";
			$this->salida .= "</table><BR>";
      $vector=$this->TraerOrdenesServicio($tipo,$id); //sacamos las ordenes de sevicio que desea pagar.

			for($i=0;$i<sizeof($vector);)
			{
					$k=$i;
					if($vector[$i][plan_id]==$vector[$k][plan_id]
					AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
					AND $vector[$i][rango]==$vector[$k][rango]
					AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id])
					{
					$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"7\">PLAN&nbsp;&nbsp;".$vector[$i][descripcion]."&nbsp;&nbsp;".
					$vector[$i][plan_descripcion]."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"7%\">ORDEN</td>";
					$this->salida.="  <td width=\"8%\">ITEM</td>";
					$this->salida.="  <td width=\"10%\">CANTIDAD</td>";
					$this->salida.="  <td width=\"10%\">CARGO</td>";
					$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
					$this->salida.="  <td width=\"20%\">VENCIMIENTO</td>";
					$this->salida.="  <td width=\"8%	\"></td>";
					//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
        //form
					$this->salida .= "<form name=\"formo\" action=\"".ModuloGetURL('app','CajaRapida','user','BuscarCuentaActiva',array('id_tipo'=>$_SESSION['CAJARAPIDA']['PACIENTE']['tipo_id_paciente'],'nom'=>urlencode($_SESSION['CAJARAPIDA']['PACIENTE']['nombre']),'id'=>$_SESSION['CAJARAPIDA']['PACIENTE']['paciente_id'],'plan_id'=>$_SESSION['CAJARAPIDA']['PACIENTE']['plan_id']))."\" method=\"post\">";
					$this->salida.="</tr>";
					}
					while($vector[$i][plan_id]==$vector[$k][plan_id]
					AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
					AND $vector[$i][rango]==$vector[$k][rango]
					AND $vector[$i][servicio]==$vector[$k][servicio])
					{
							$this->salida.="<tr class='modulo_list_claro'>";
							$this->salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"7%\">".$vector[$k][orden_servicio_id]."</td>";
							$this->salida.="  <td colspan=\"6\">";
							$this->salida.="  <table align=\"center\" border=\"1\" width=\"100%\">";
							$l=$k;
							while($vector[$k][orden_servicio_id]==$vector[$l][orden_servicio_id]
							AND $vector[$k][plan_id]==$vector[$l][plan_id]
							AND $vector[$k][tipo_afiliado_id]==$vector[$l][tipo_afiliado_id]
							AND $vector[$k][rango]==$vector[$l][rango]
							AND $vector[$k][servicio]==$vector[$l][servicio])
							{
									$vecimiento=$vector[$l][fecha_vencimiento];
									$arr_fecha=explode(" ",$vecimiento);
									if( $l % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr align='center'>";
									$this->salida.="  <td align='center' class=$estilo width=\"8%\"<label class='label_mark'>".$vector[$l][numero_orden_id]."</label></td>";
									$this->salida.="  <td colspan=5>";
									$this->salida.="  <table align=\"center\" border=\"0\" width=\"100%\">";
									$m=$l;
									while($vector[$l][numero_orden_id]==$vector[$m][numero_orden_id]
									AND $vector[$l][orden_servicio_id]==$vector[$m][orden_servicio_id]
									AND $vector[$l][plan_id]==$vector[$m][plan_id]
									AND $vector[$l][tipo_afiliado_id]==$vector[$m][tipo_afiliado_id]
									AND $vector[$l][rango]==$vector[$m][rango]
									AND $vector[$l][servicio]==$vector[$m][servicio])
									{
											$this->salida.="<tr class=$estilo>";
											$this->salida.="  <td width=\"10%\" align=\"center\" >".$vector[$m][cantidad]."</td>";
											$this->salida.="  <td width=\"14%\" align=\"center\" >".$vector[$m][cargoi]."</td>";
											$this->salida.="  <td width=\"42%\">".$vector[$m][des1]."</td>";

											if(strtotime($vector[$m][fecha_vencimiento]) > strtotime(date("Y-m-d")))
											{
												$this->salida.="  <td width=\"26%\" align=\"center\" >$arr_fecha[0]";
												$this->salida.="  <td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
											}
											else
											{
												$this->salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
												$this->salida.="  <td><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
											}
											$this->salida.="</tr>";echo "paso"; exit;
											$m++;
									}
									$this->salida.="</table>";
									$this->salida.="</td>";
									$this->salida.="</tr>";
									$l=$m;
						}
						//parte de alex.
						$this->salida.="<tr><td colspan='8' align=\"center\">";
						$this->salida.="<table width='100%' border='0' cellpadding='2' align=\"center\">";
						$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >OBSERVACION</td><td class='modulo_list_claro'>".$vector[$k][observacion]."</td></tr>";
						$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >SERVICIO</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][serv_des]."</td></tr>";
						$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. INT.</td><td width='80%' class='modulo_list_claro'>".$vector[$k][autorizacion_int]."</td></tr>";
						$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. EXT.</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][autorizacion_ext]."</td></tr>";
						$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AFILIACION</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][tipo_afiliado_nombre]."</td></tr>";
						$this->salida.="</table>";
						$this->salida.="</td></tr>";
						//parte de alex.

						$this->salida.="</table>";
    				$this->salida.="</td>";
						$this->salida.="</tr>";
						$k=$l;
				}
				$this->salida.="</table>";
				$this->salida.="<table align='center' width='80%'>";
				$this->salida.="<tr align='right' class=\"modulo_table_button\">";
				$this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Ordenar></form></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$i=$k;
			}
			$this->salida .= ThemeCerrarTabla();
			return true;
	 }




	 /*
	* Esta funcion permite al usuario verificar los cargos liquidados y revisar
	* si tiene una cuenta activa o no para tener la opcion de cargarla a la cuenta.
	* @return boolean
	*/
	 function LiquidacionOrden($vector,$nom,$tipo,$id,$op,$PlanId)
	 {
	 		IncludeLib("tarifario");
			$Cuenta=0;
      $nom=urldecode($nom);
			$this->salida.= ThemeAbrirTabla('LIQUIDACION ORDEN DE SERVICIOS MEDICOS ');
			$this->Encabezado();
			$this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$nom."</td></tr>";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"40%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$tipo."&nbsp;".$id."</td></tr>";
			$this->salida .= "</table><BR>";
			//$this->salida.="<BR><table  align=\"center\" border=\"2\"  width=\"90%\">";
			if($vector)
			{
					$sw_hay_cuenta=true;//este swiche me indica si hubo  no cuenta, asi determino como liquido
					//el cargo con cuenta o sin cuenta.
					$this->salida.="<BR><table  align=\"center\" bordercolor='#4D6EAB' border=\"1\"  width=\"90%\">";
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td align=\"left\" colspan=\"5\">CUENTA&nbsp;No.&nbsp;".$vector[0][numerodecuenta]."&nbsp;&nbsp;".$vector[$i][plan_descripcion]."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td width=\"20%\">PLAN</td>";
					$this->salida.="  <td width=\"10%\">TOTAL CUENTA</td>";
					$this->salida.="  <td width=\"20%\">SERVICIO</td>";
					$this->salida.="  <td width=\"10%\">SALDO</td>";
					$this->salida.="  <td width=\"20%\"></td>";
					$this->salida.="</tr>";
					$Cuenta=$vector[0][numerodecuenta];
					for($i=0;$i<sizeof($vector);$i++)
					{
									$this->salida.="<tr class='modulo_list_claro' align='center'>";
									$this->salida.="  <td >".$vector[$i][tercero]."&nbsp; - &nbsp;".$vector[$i][plan_descripcion]."</td>";
									$this->salida.="  <td >".$vector[$i][total_cuenta]."</td>";
									$this->salida.="  <td >".$vector[$i][descripcion]."</td>";
									$this->salida.="  <td >".$vector[$i][saldo]."</td>";
									$accion=ModuloGetURL('app','Os_Atencion','user','InsertarCargoCuenta',array('cuenta'=>$Cuenta,'op'=>$op,'plan'=>$PlanId,"tipo_id"=>$tipo,"pac"=>$id));
									$this->salida.="  <td ><a href='$accion'>[&nbsp;CARGAR CUENTA&nbsp;]</a></td>";
									$this->salida.="</tr>";
					}
					$this->salida.="</table>";
			}
			else
			{
					$sw_hay_cuenta=false;
					//este swiche $sw_hay_cuenta  se pone false cuando no existe cuenta.
					$this->salida.="<p class='label_error' align=\"center\" >	EL PACIENTE NO TIENE UNA CUENTA CREADA</p>";
			}

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
			$i=0;
			if($sw_hay_cuenta==false)
			{
								$total_cargo=$total_paciente=$total_empresa=0;
								$cargo_liq=array(); //arreglo que contiene los cargos y demas datos para liquidarlos.
								$Arr_Descripcion[]=array();//arreglo para guardar la descripcion y los servicios.

								foreach($op as $index=>$codigo)
								{
										$valores=explode(",",$codigo);

										$datos=$this->DatosOs($valores[0]);

										$Arr_Descripcion[$i]=array('des_cargo'=>$valores[6],'servicio'=>$valores[7],'des_servicio'=>$valores[8],'numero_orden_id'=>$valores[0],'cargo'=>$valores[1]);
										$cargo_liq[]=array('tarifario_id'=>$datos['tarifario_id'],'cargo'=>$datos['cargo'],'cantidad'=>$datos['cantidad'],'autorizacion_int'=>$datos['autorizacion_int'],'autorizacion_ext'=>$datos['autorizacion_ext']);
										$i++;
								}
					//print_r($Arr_Descripcion);
					//print_r($cargo_liq);exit;
					$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq, $datos[plan_id] ,$datos[tipo_afiliado_id] ,$datos[rango] ,$datos[semanas_cotizacion]);

         //print_r($cargo_fact);
				 //exit;

					for($k=0;$k<sizeof($cargo_fact);$k++)
					{
							if( $k % 2){ $estilo='modulo_list_claro';}
					 		else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class='$estilo' align='center'>";
							$this->salida.="  <td >".$Arr_Descripcion[$k][numero_orden_id]."</td>";
							$this->salida.="  <td >".$Arr_Descripcion[$k][cargo]."</td>";
							$this->salida.="  <td ></td>";
							$this->salida.="  <td >".urldecode($Arr_Descripcion[$k][des_cargo])."</td>";
							$this->salida.="  <td >".$Arr_Descripcion[$k][des_servicio]."</td>";
							$this->salida.="  <td >".$cargo_fact[$k][cantidad]."</td>";
							$this->salida.="  <td >".$cargo_fact[$k][valor_cargo]."</td>";
							$total_cargo=$total_cargo+$cargo_fact[$k][valor_cargo];
							$this->salida.="  <td >".$cargo_fact[$k][total_paciente]."</td>";
							$total_paciente=$total_paciente + $cargo_fact[$k][total_paciente];
							$this->salida.="  <td >".$cargo_fact[$k][valor_empresa]."</td>";
							$total_empresa=$total_empresa + $cargo_fact[valor_empresa];
							$this->salida.="</tr>";
					}
			}
			else
			{
					$total_cargo=$total_paciente=$total_empresa=0;
					foreach($op as $index=>$codigo)
								{
										$valores=explode(",",$codigo);
										$datos=$this->DatosOs($valores[0]);
										list($dbconn) = GetDBconn();
										$query="SELECT tarifario_id,cargo FROM os_maestro_cargos
														WHERE numero_orden_id=".$valores[0]."";
										$resulta=$dbconn->execute($query);
										if ($dbconn->ErrorNo() != 0) {
													$this->error = "Error al Cargar el Modulo";
													$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
													return false;
										}
										while(!$resulta->EOF){

												$Liq=LiquidarCargoCuenta($Cuenta,$resulta->fields[0],$resulta->fields[1],$valores[5],0,0,false,false,0,$valores[7],$PlanId,$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
												if( $i % 2){ $estilo='modulo_list_claro';}
												else {$estilo='modulo_list_oscuro';}
												$this->salida.="<tr class='$estilo' align='center'>";
												$this->salida.="  <td >".$valores[0]."</td>";
												$this->salida.="  <td >".$resulta->fields[1]."</td>";
												$this->salida.="  <td >".$resulta->fields[0]."</td>";
												$this->salida.="  <td >".$this->TraerNombreTarifario($resulta->fields[0],$resulta->fields[1])."</td>";
												$this->salida.="  <td >".$valores[8]."</td>";
												$this->salida.="  <td >".$Liq[cantidad]."</td>";
												$this->salida.="  <td >".$Liq[valor_cargo]."</td>";
												$total_cargo=$total_cargo+$Liq[valor_cargo];
												$this->salida.="  <td >".$Liq[total_paciente]."</td>";
												$total_paciente=$total_paciente + $Liq[total_paciente];
												$this->salida.="  <td >".$Liq[valor_empresa]."</td>";
												$total_empresa=$total_empresa + $Liq[valor_empresa];
												$this->salida.="</tr>";
												$i++;
												$resulta->MoveNext();
										}
								}
					//print_r($cargo_liq);ex
			}
      //print_r($cargo_fact);
			//exit;

			$this->salida.="<tr class='$estilo' align='center'>";
			$this->salida.="  <td colspan='9'>&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr align='right'>";
			$this->salida.="  <td class=\"modulo_table_list_title\" colspan='6'>TOTAL</td>";
			$this->salida.="  <td class=\"modulo_table_list_title\">$total_cargo</td>";
			$this->salida.="  <td class=\"modulo_table_list_title\">$total_paciente</td>";
			$this->salida.="  <td class=\"modulo_table_list_title\">$total_empresa</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr align='right'>";
			$this->salida.="  <td  colspan='6'>&nbsp;&nbsp;</td>";
			$this->salida.="  <td colspan='2'><img src=\"".GetThemePath()."/images/informacion.png\"<label class='label_mark'><a href='".ModuloGetURL('app','CajaGeneral','user','CajaRapida',array())."'>&nbsp;&nbsp;PAGO EN CAJA RAPIDA</a></label></td >";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	 }

//-----------------------------------------------------------------------------------
}//fin clase

?>

