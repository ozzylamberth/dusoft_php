<?php
/**
* $Id: app_Os_ListaTrabajoVitros_userclasses_HTML.php,v 1.36 2006/02/20 14:40:48 ehudes Exp $
*
* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
* @package IPSOFT-SIIS
*
* @author    Mauricio Bejarano L. 
* @version   $Revision: 1.36 $
* @package   Os_ListaTrabajoVitros
* 
* Modulo de Listas de Trabajo para VITROS.
* Modulo para el manejo de listas de trabajo en Interface con VITROS
* El codigo fue tomado de Os_ListaTrabajoDatalab y modificado para Vitros
*/

IncludeClass("ClaseHTML");
class app_Os_ListaTrabajoVitros_userclasses_HTML extends app_Os_ListaTrabajoVitros_user
{
	//Constructor de la clase app_Os_ListaTrabajoVitros_userclasses_HTML
	function app_Os_ListaTrabajoVitros_userclasses_HTML()
	{
							$this->salida='';
							$this->app_Os_ListaTrabajoVitros_user();
							return true;
	}

	//aoltu
	function SetStyle($campo)
	{
			if ($this->frmError[$campo] || $campo=="MensajeError")
			{
					if ($campo=="MensajeError")
					{
							$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
					}
					return ("label_error");
			}
			return ("label");
	}

	/*
	* aoltu
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
	function Encabezado()
		{
				$this->salida .= "<br><table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
				$this->salida .= " <tr class=\"modulo_table_title\">";
				$this->salida .= " <td>EMPRESA</td>";
				$this->salida .= " <td>CENTRO UTILIDAD</td>";
				$this->salida .= " <td>DEPARTAMENTO</td>";
				$this->salida .= " </tr>";
				$this->salida .= " <tr align=\"center\">";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJO_VITROS']['NOM_EMP']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['LTRABAJO_VITROS']['NOM_CENTRO']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJO_VITROS']['NOM_DPTO']."</td>";
				$this->salida .= " </tr>";
				$this->salida .= " </table>";
				return true;
		}


//aoltu
/**
* Se utilizada listar en el combo los diferentes tipo de identificacion de los pacientes
* @access private
* @return void
*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
			foreach($tipo_id as $value=>$titulo)
			{
					if($value==$TipoId)
					{
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else
					{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
			}
	}


	/*
	* Esta funcion realiza la busqueda de cumplimientos de ordenes de servicio
	* según filtros como tipo, documento, nombre y plan
	* @return boolean
	*/
	function FormaMetodoBuscar($arr)
	{
			$this->salida.= ThemeAbrirTabla('ORDEN DE LISTA DE TRABAJO CON VITROS');
			$accion=ModuloGetURL('app','Os_ListaTrabajoVitros','user','BuscarOrden');
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->Encabezado();
			
			//Criterios de busqueda inicio
			$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr class=\"modulo_table_title\">";
			$this->salida .= "<td align = center colspan = 2 >CUMPLIMIENTOS</td>";
			$this->salida .= "</tr>";

			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
			//$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
			$this->salida .= "</tr>";

			$this->salida .= "<tr class=\"modulo_list_claro\" >";
			$this->salida .= "<td width=\"40%\" >";
			$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr><td>";
			$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
			//$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function Revisar(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "frm.Fecha.value='TODAS LAS FECHAS'";
					$this->salida .= "  }";
					$this->salida .= "else{";
					$this->salida .= "frm.Fecha.value=''";
					$this->salida .= "}";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
			$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
			$tipo_id=$this->tipo_id_paciente();
			$this->salida .=" <option value= -1 selected>Todos</option>";
			$this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
			$this->salida .= "</select></td></tr>";

			$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" id=\"Documento\" maxlength=\"32\" value = ".$_REQUEST['Documento']."></td></tr>";
			$this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" value = ".$_REQUEST['Nombres']."></td></tr>";
			
			/*if(empty($_REQUEST['DiaEspe'])){
				$_REQUEST['DiaEspe']=Date('Y-m-d');
			}*/
			
			$this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = \"".$_REQUEST['Fecha']."\"><sub>".ReturnOpenCalendario("formabuscar","Fecha","-")."</sub></td></tr>";
			$this->salida .= "<tr class=\"label\">";
			$this->salida .= "<td align = left >TODAS LAS FECHAS</td>";
			$this->salida.="  <td align=\"left\"><input type = checkbox name= 'allfecha' onclick=Revisar(this.form,this.checked)></td>";
			$this->salida .= "</tr>";

	    //filtro de pacientes
			$this->salida .= "<tr><td class=\"label\">PACIENTES: </td><td><select name=\"opcion_pacientes\" class=\"select\">";
			$seleccion1 = '';
			$seleccion2 = '';
			$seleccion3 = '';
			if ($_REQUEST['opcion_pacientes']==1){
				$seleccion1 = 'selected';
			}elseif($_REQUEST['opcion_pacientes']==2){
				$seleccion2 = 'selected';
			}elseif($_REQUEST['opcion_pacientes']==2){
				$seleccion3 = 'selected';
			}
			$this->salida .=" <option value= 1 $selected1>Examenes pendientes a procesar</option>";
			$this->salida .=" <option value= 2 $selected2>Examenes en proceso</option>";
			$this->salida .=" <option value= 3 $selected3>Todos los pacientes</option>";
			$this->salida .= "</select></td></tr>";
			//fin de filtros

			$this->salida .= "<tr><td class=\"label\">No. CUMPLIMIENTO</td><td><input type=\"text\" class=\"input-text\" name=\"Cumplimiento\" maxlength=\"50\" size = 20 value = ".$_REQUEST['Cumplimiento']."></td></tr>";
			//boton de busqueda
			$this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
			$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar_Orden_Cargar_Session\" value=\"BUSCAR\"></td>";
			$this->salida .= "</form>";
			//boton menu
			$actionMenu=ModuloGetURL('app','Os_ListaTrabajoVitros','user','main');
			$this->salida .= "<form name=\"formaVolver\" action=\"$actionMenu\" method=\"post\">";
			$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Menu\" value=\"MENU\"><br></td></form>";
			$this->salida .= "</tr>";

			$this->salida .= "</table></td></tr>";
			$this->salida .= "</td></tr></table>";
			$this->salida .= "</table>";
			$this->salida .= "</td>";
			/*$this->salida .= "<td>";
			$this->salida .= "<BR><table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .= "<tr><td>";
//     Calendario                   
			$this->salida.="\n".'<script>'."\n";
			$this->salida.='function year1(t)'."\n";
			$this->salida.='{'."\n";
//			$this->salida.="alert(document.getElementById('Documento').value)\n";
			$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
			foreach($_REQUEST as $v=>$v1)
			{
					if($v!='year' and $v!='meses' and $v!='DiaEspe')
					{
							if (is_array($v1)) {
											foreach($v1 as $k2=>$v2) {
													if (is_array($v2)) {
															foreach($v2 as $k3=>$v3) {
																	if (is_array($v3)) {
																			foreach($v3 as $k4=>$v4) {
																					$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
																			}
																	}else{
																			$this->salida .= "&$v" . "[$k2][$k3]=$v3";
																	}
															}
													}else{
															$this->salida .= "&$v" . "[$k2]=$v2";
													}
											}
									} else {
											$this->salida .= "&$v=$v1";
									}
					}
			}
			
			//$this->salida .= "&Documento=\"+document.getElementById('Documento').value;\n";
			$this->salida.='";'."\n";
			$this->salida.='}'."\n";
			$this->salida.='</script>';

			$this->salida .='<form name="cosa">';
			$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .='<tr align="center">';
			$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['year']))
			{
					$_REQUEST['year']=date("Y");
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
					$mes=$_REQUEST['meses']=date("m");
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
			$this->salida .= "   </td></tr>";

			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
/** FinCalendario  **/
			//$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			//Criterios de busqueda fin
			
      // muestra resultados de la busqueda $arr pasa por parametro despues de 
			//realizada la consulta con los criterios de busqueda. Llama a GetForma
			if(!empty($arr))
			{
					$this->salida.= "<table border=\"0\" align=\"center\"  width=\"80%\">";
					$this->salida.= $this->SetStyle("MensajeError");
					$this->salida.= "</table><br>";
					$this->salida.= "<br><table width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";

	        //codigo para pintar en el resultado de la busqueda el filtro utilizado.
					$texto = '';
					if ($_REQUEST['opcion_pacientes'] == 1){
						$texto = 'EXAMENES PENDIENTES A PROCESAR EN VITROS';
					}elseif ($_REQUEST['opcion_pacientes'] == 2){
						$texto = 'EXAMENES EN PROCESO. EN ESPERA DE RESPUESTA';
					}elseif ($_REQUEST['opcion_pacientes'] == 3){
						$texto = 'TODOS LOS PACIENTES VITROS';
					}
					if ($texto != '')
					{
						$this->salida .= "<tr class=\"modulo_table_title\">";
						$this->salida.="<td colspan=5 align=\"center\">FILTRO DE BUSQUEDA: ".$texto."</td>";
						$this->salida.="</tr><br>";
					}

					$this->salida.= "<tr align=\"center\" class=\"modulo_table_list_title\">";
// 					$this->salida.= "<td width=\"10%\">FECHA DEL CUMPLIMIENTO</td>";
// 					$this->salida.= "<td width=\"10%\">No. CUMPLIMIENTO</td>";
					$this->salida.= "<td width=\"10%\">No. CUMPLIMIENTO</td>";
					$this->salida.= "<td width=\"10%\">SERVICIO</td>";
					$this->salida.= "<td width=\"5%\">IDENTIFICACION</td>";
					$this->salida.= "<td width=\"35%\">NOMBRE DEL PACIENTE</td>";
					$this->salida.= "<td width=\"10%\">OPCION</td>";
					$this->salida.= "</tr>";
					
					
					for($i=0;$i<sizeof($arr);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$cumplimiento=$this->ConvierteCumplimiento($arr[$i][fecha_cumplimiento],$arr[$i][numero_cumplimiento],$_SESSION['LTRABAJO_VITROS']['DPTO']);
							$edad_paciente = CalcularEdad($arr[$i][fecha_nacimiento],date("Y-m-d"));
							$this->salida.="<tr class='$estilo' align='center'>";
							$this->salida.="  <td ><font color='#4D6EAB'>".$cumplimiento." </font></td>";
							$this->salida.="  <td >".$arr[$i][servicio_descripcion]."</td>";
							$this->salida.="  <td >".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</td>";
							$this->salida.="  <td >".$arr[$i][nombre]."</td>";
							$this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','GetForma',array('numero_cumplimiento'=>$arr[$i][numero_cumplimiento], 'fecha_cumplimiento'=>$arr[$i][fecha_cumplimiento], 'departamento'=>$arr[$i][departamento], 'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
// 					$Paginador = new ClaseHTML();
// 					$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
					$this->salida .=$this->RetornarBarra();
			}
			else
			{
				$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table><br>";
			}
			$this->salida .= ThemeCerrarTabla();
	return true;
	}//fin metodo FormaMetodoBuscar

/*
* Esta funcion calcula el numero de pasos que saldran en la barra de navegación.
* @return boolean
*/
function CalcularNumeroPasos($conteo)
{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
}

/*
* Esta funcion calcula la barra de navegación.
* @return boolean
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

/*
* Esta funcion calcula los segmentos en que se desplaza el apuntador de los registros
* de la base de datos.
* @return boolean
*/
function CalcularOffset($paso)
{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
}


/*
* Esta funcion integra (CalcularNumeroPasos,CalcularOffset,CalcularBarra), para asi
* crear una barra de navegacion, para los registros.
* @return boolean
*/
	function RetornarBarra()
	{
			//$this->conteo;
			//$this->limit;

			if($this->limit>=$this->conteo)
			{
					return '';
			}
			$paso=$_REQUEST['paso'];
			if(is_null($paso))
			{
			    $paso=1;
			}
	    $vec='';
			foreach($_REQUEST as $v=>$v1)
			{
					if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
					{
					  $vec[$v]=$v1;
					}
			}
			$accion=ModuloGetURL('app','Os_ListaTrabajoVitros','user','BuscarOrden',$vec);
			$barra=$this->CalcularBarra($paso);
			$numpasos=$this->CalcularNumeroPasos($this->conteo);
			$colspan=1;

			$this->salida .= "<br><table border='0' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
			if($paso > 1)
			{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
					$colspan+=1;
			}
			else
			{
	// $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
		//$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
	    }
			$barra ++;
			if(($barra+10)<=$numpasos)
			{
					for($i=($barra);$i<($barra+10);$i++)
					{
							if($paso==$i)
							{
											$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
							}
							else
							{
											$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
							}
							$colspan++;
					}
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
		      $colspan+=2;
			}
			else
			{
		      $diferencia=$numpasos-9;
					if($diferencia<=0){$diferencia=1;}//cambiar en todas las barra
					for($i=($diferencia);$i<=$numpasos;$i++)
					{
							if($paso==$i)
							{
									$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
							}
							else
							{
									$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
							}
							$colspan++;
					}
					if($paso!=$numpasos)
					{
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
							$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
							$colspan++;
					}
					else
					{
		// $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
			//$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
		      }
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
					$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
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
			    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
			}
			//}
	}


//FUNCIONES QUE ACOMPAÑAN AL CALENDARIO
/**
* Funcion que Saca los años para el calendario a partir del año actual
* @return array
*/
function AnosAgenda($Seleccionado='False',$ano)
{
		$anoActual=date("Y");
		//$ano = $anoActual;
		$anoActual1=$anoActual-10;
    for($i=0;$i<=20;$i++)
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
							  }
								else
								{
				          $this->salida .=" <option value=\"$titulo\">$titulo</option>";
							  }
						}
						break;
			  }
				case 'True':
						{
							foreach($vars as $value=>$titulo)
							{
									if($titulo==$ano)
									{
									  $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
								  }
									else
									{
									  $this->salida .=" <option value=\"$titulo\">$titulo</option>";
									}
							}
							break;
					  }
		}
}

function MesesAgenda($Seleccionado='False',$Año,$Defecto)
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
		//$mesActual=date("m");
		switch($Seleccionado)
		{
				case 'False':
				{
					if($anoActual==$Año)
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
					if($anoActual==$Año)
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
* Formulario que muestra los examenes que pueden ser procesados en la vitros dado unas condiciones de busqueda
* dadas por el usuario
*/
function Consultar_Cumplimiento($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,$paciente_id,$nombre,$edad_paciente)
{
    $this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE CUMPLIMIENTOS PARA VITROS');
// 			$this->salida="<script language=javascript>\n";
// 			$this->salida.="function load_page()\n";
// 			$this->salida.="{\n";
// 			$this->salida.="location.reload();\n";
// 			$this->salida.="}\n";
// 			$this->salida.="</script>\n";
// 			$this->salida.="<body onload=compt=setTimeout('load_page();',10000)>\n";
			
		//$accion=ModuloGetURL('app','Os_ListaTrabajoVitros','user','ActualizaDatos',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente));
		$accion=ModuloGetURL('app','Os_ListaTrabajoVitros','user','ReorganizaDatos',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente));
		
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
		
		$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
		//$this->salida.="<td align=\"center\" width=\"20%\" class=\"".$this->SetStyle("MensajeError")."\"></td> ";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$paciente_id."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">".$nombre."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$edad_paciente."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento);
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"center\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"left\" width=\"10%\">".$fecha_cumplimiento."</td>";
		$this->salida.="  <td align=\"center\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"left\" width=\"10%\">".$cumplimiento."</td>";
		//$this->salida.="  <td align=\"center\" width=\"10%\">".$numero_cumplimiento."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		//script para consultar bandejas
			$this->salida .= "  <script>";
			$this->salida.=  "  function filtrobandeja(valor)"."\n";
			$this->salida.='    {'."\n";
			//$this->salida.=' alert(valor);'."\n";
			$accion2=ModuloGetUrl('app','Os_ListaTrabajoVitros','user','GetForma',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente));
			//$this->salida.='    window.location.href="Contenido.php?bandeja="+valor+"';
			$this->salida.='    window.location.href="'.$accion2.'&bandeja="+valor';
			$this->salida.=' ;'."\n";
			$this->salida.=' }'."\n";
			$this->salida.= "</script>";
			
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan = 10 align=\"center\" width=\"100%\">DATOS BASICOS</td>";
		$this->salida.="</tr>";
		
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		
		//analizador
		$this->salida.="<td align=\"right\" width=\"20%\" class=\"".$this->SetStyle("analizador")."\">ANALIZADOR</td> ";
		$this->salida .= "<td><select name=\"tipo_analizador\" class=\"select\">";
		$tipo_analizador=$this->TiposAnalizador();
		
		$this->salida .=" <option value= 'VITRO1' selected>Seleccione</option>";
		for($j=1;$j< sizeof($tipo_analizador);$j++){
			if($tipo_analizador[$j][analizador_id]==$_REQUEST['tipo_analizador']){
				$this->salida .=" <option value= $tipo_analizador[analizador_id] selected>".$tipo_analizador[descripcion_analizador]."</option>";
			}else{
				$this->salida .=" <option value= $tipo_analizador[analizador_id]>".$tipo_analizador[descripcion_analizador]."</option>";
			}
		}
		//bandeja
		$this->salida.="<td align=\"right\" width=\"20%\" class=\"".$this->SetStyle("bandeja")."\">BANDEJA</td> ";
			$this->salida .= "  <td width=\"50%\" >";
			$this->salida .= "  	<select name=\"bandeja\" onchange=\"filtrobandeja(this.value)\" class=\"select\">";
			$this->salida .= "  		<option value=\"0\" selected>SIN DETERMINAR</option>";
			$tipo_bandeja=$this->TiposBandeja();
			for($i=0;$i<sizeof($tipo_bandeja);$i++){
				if(($i==$_REQUEST['bandeja'])&&($_REQUEST['bandeja']!=null)){
					$this->salida .="		<option value=\"".$i."\" selected>".$tipo_bandeja[$i]."</option>";
				}else{
					$this->salida .="		<option value=\"".$i."\">".$tipo_bandeja[$i]."</option>";
				}
			}
			$this->salida .= "   </select>";
			$this->salida .= "  </td>";
		
		//copa 
		$this->salida.="<td align=\"right\" width=\"25%\" class=\"".$this->SetStyle("copa")."\">POSICION_COPA</td> ";
		$this->salida.= "		<td align=\"left\" ><select name=\"posicion_copa\" class=\"select\">";
		$copas=$this->RetornaCopaDisponible($_REQUEST['bandeja']);
		$this->salida .=" 	<option value=\"0\" selected>--</option>";
		foreach($copas as $copa){
			$val=$copa;
			if($copa==$_REQUEST['posicion_copa']){
				$this->salida.="	<option value = ".$copa." selected>".$val."</option>";
			}else{
				$this->salida.="	<option value = ".$copa." >".$val."</option>";
			}
		}
		$this->salida.="		</select></td>";
		
		$this->salida .= "</select></td>";
		$this->salida.="	<SCRIPT language='javascript'>";
		$this->salida.="		function acceptNum(evt)\n";
		$this->salida.="		{\n";
		$this->salida.="			var nav4 = window.Event ? true : false;\n";
		$this->salida.="			var key = nav4 ? evt.which : evt.keyCode;\n";
		$this->salida.="			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
		$this->salida.="		}\n";
		$this->salida.="	</SCRIPT>";
		//dilucion
		$this->salida.="<td align=\"left\" width=\"20%\" class=\"".$this->SetStyle("dilucion")."\">DILUCION</td> ";
		if(empty($_REQUEST['dilucion'])){$dilucion='1.000';}else{$dilucion=$_REQUEST['dilucion'];}
		//$this->salida.= "<td align=\"left\"> <input type=\"text\" class=\"input-text\" name=\"dilucion\" maxlength=\"5\" value=".$dilucion."> </td> ";
		$this->salida.="	<td width=\"10%\" align=\"right\"><input type=\"text\" name=\"dilucion\" class=\"input-text\" maxlength=\"12\" size=\"12\" value=\"".$dilucion."\" onKeyPress='return acceptNum(event)'>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
		
		$vector = $this->ConsultaOrdenesPaciente($numero_cumplimiento, $fecha_cumplimiento, $departamento);
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">"; 
		
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan = 7 align=\"center\" width=\"80%\">ORDENES DE SERVICIO PENDIENTES DE PROCESAR</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"15%\">NUM. CUMPLIMIENTO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">CARGO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"40%\">DESCRIPCION</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">COD. VITROS</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">VOLUMEN</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">FLUIDO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">OPCION</td>";
		$this->salida.="</tr>";
	
		//aqui se muestran los datos para que le medico los seleccione (arma el paquete) y los deje 
		//listos para enviar a la vitros
		$cont_datos=sizeof($vector);
		$volumen=0;
		
		for($i=0;$i<sizeof($vector);$i++){
			if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				//si no esta en la lista de la vitros
				//if((($vector[$i][estado_examen]=='0')||($vector[$i][estado_examen]==null))&&($vector[$i][sw_estado_cumplimiento]==1)){
				if(($vector[$i][estado_examen]=='0')||($vector[$i][estado_examen]==null)){
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td colspan = 1 align=\"center\" >".$cumplimiento."</td>";
					$this->salida.="  <td colspan = 1 align=\"center\" >".$vector[$i][cargo]."</td>";
					$this->salida.="  <td colspan = 1 align=\"left\" >".$vector[$i][nombre_prueba]."</td>";
					$this->salida.="  <td colspan = 1 align=\"center\" >".$vector[$i][nombre_reporte]."</td>";
					if($this->DeterminaPedirVolumen($vector[$i][cargo]) == '1' ){
						$this->salida.="	<td width=\"10%\" align=\"right\"><input type=\"text\" name=\"prueba"."[$i]"."[volumen]\" class=\"input-text\" maxlength=\"10\" size=\"10\" value= \"".$volumen."\" onKeyPress='return acceptNum(event)'>";
					}else{
						$this->salida.="	<td><input type=hidden name='prueba"."[$i]"."[volumen]' value= 0></td>";
					}
					//PIDE EL FLUIDO
							//$this->salida.="<td align=\"left\" width=\"20%\" class=\"".$this->SetStyle("fluido")."\">FLUIDO</td> ";
							$this->salida .= "<td><select name='prueba"."[$i]"."[tipo_fluido]' class='select'>";
							$tipo_fluido=$this->TiposFluido($vector[$i][cargo]);
							//$this->salida .=" <option value= -1 selected>Seleccione</option>";
							foreach($tipo_fluido as $id => $des){
								if($id==$_REQUEST['tipo_fluido']){
									$this->salida .=" <option value= $id selected>".$des."</option>";
								}else{
									$this->salida .=" <option value= $id>".$des."</option>";
								}
							}
					
					$this->salida.="  <td align=\"center\" width=\"13%\"><input type = checkbox name= 'prueba"."[$i]"."[op]' value = 1 ></td>";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[numero_orden_id]' value= ".$vector[$i][numero_orden_id].">";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[cargo]' value= ".$vector[$i][cargo].">";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[nombre_reporte]' value= ".$vector[$i][nombre_reporte].">";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[orden_servicio_id]' value= ".$vector[$i][orden_servicio_id].">";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[cumplimiento]' value= ".$cumplimiento.">";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[lab_examen_id]' value= ".$vector[$i][lab_examen_id].">";
					$this->salida.="<input type=hidden name='prueba"."[$i]"."[codigo_vitros]' value= '".$vector[$i][codigo_vitros]."'>"; 
					$_SESSION['CODIGO_VITROS'][$i] = $vector[$i][codigo_vitros];
				}
			$this->salida.="</tr>";
		}
		
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="<input type=hidden name='cont_datos' value= ".$cont_datos.">";
		$this->salida .= "<td align=\"right\"  width=\"13%\" colspan=\"7\"><input class=\"input-submit\" name=\"enviar\" type=\"submit\" value=\"VALIDAR DATOS\"></td>";
		$this->salida.="</tr>";

		$this->salida.="</table>";
    $this->salida .= "</form>";
		//datos validados en vitros
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">"; 
		
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan = 7 align=\"center\" width=\"100%\">ESTADO DE LAS ORDENES DE SERVICIO QUE SON ENVIADAS A LA VITROS</td>";
		$this->salida.="</tr>";
		$os_vitros=$this->ConsultaEstadoOS();

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">NUM. CUMPLIMIENTO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">CARGO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">BANDEJA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">COPA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"30%\">PRUEBAS</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"30%\">ESTADO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"20%\">ELIMINA</td>";
		$this->salida.="</tr>";
		$SwReenvia='0';
		//muestras listas para enviar a la vitros
		if(!empty($os_vitros)){
			for($i=0;$i<sizeof($os_vitros);$i++){
				//if(($os_vitros[$i][estado_examen]!='0')&&($os_vitros[$i][estado_examen]!=null)){
				//se modifica a que solo muestre los que estan pendientes por ser enviados
				if(($os_vitros[$i][estado_examen]=='1')||($os_vitros[$i][estado_examen]=='5')){
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td colspan = 1 align=\"center\" >".$os_vitros[$i][muestra_id]."</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" >".$os_vitros[$i][cargo]."</td>";
						if($os_vitros[$i][bandeja_id]=='0'){
							$bandeja='';$copa='';
						}else{
							$bandeja=$os_vitros[$i][bandeja_id];
							$copa=$os_vitros[$i][posicion_copa];}
						$this->salida.="  <td colspan = 1 align=\"center\" >".$bandeja."</td>";
						$this->salida.="  <td colspan = 1 align=\"center\" >".$copa."</td>";
						$this->salida.="  <td colspan = 1 align=\"left\" >".$os_vitros[$i][nombre_prueba]."</td>";
						$this->salida.= "<td colspan = 1 align=\"center\" >";
						$directiva='';
						if($os_vitros[$i][estado_examen]=='1'){
							$imagen='lab_listo.png';
							$this->salida.= "<img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\"></BR>LISTO PARA ENVIAR A VITROS</td>";
							$imagen='elimina.png';
							$this->salida.= "<td colspan = 1 align=\"center\" title=\"ELIMINA EXAMEN DE LA LISTA DE VITROS\">";
							$this->salida.= "		<a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','EliminaExamenListaVitros',array('numero_orden_id'=>$os_vitros[$i][numero_orden_id],'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id,'nombre'=>$nombre, 'edad_paciente'=>$edad_paciente))."> <img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\"></a>";
							$this->salida.= "</td>";
						}else{
							if($os_vitros[$i][estado_examen]=='2'){
								$imagen='lab_procesando.png';$texto='EN ESPERA DE RESPUESTA';
							}elseif($os_vitros[$i][estado_examen]=='3'){$imagen='lab_ok.png';$texto='RESPUESTA OBTENIDA';
							}elseif($os_vitros[$i][estado_examen]=='5'){$imagen='alarma.gif';
																											$texto='ERROR DE COMUNICACION';
																											$ayuda="REVISE LA CONECCION CON LA VITROS O QUE EL WEBSERVICE ESTE EN EJECUCION";
																											$directiva="<a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','Consulta_Vitros',array('numero_orden_id'=>$os_vitros[$i][numero_orden_id],'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id,'nombre'=>$nombre, 'edad_paciente'=>$edad_paciente)).">";
																											}
							$this->salida.= "$directiva <img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\" title=\"$ayuda\"></BR>$texto</td>";
							$this->salida.= "<td></td>";
						}
				}//if
			}//for
		}else{
			$this->salida.="<tr class=modulo_list_claro>";
			$this->salida.="  <td colspan = 7 align=\"center\" >NO HAY PRUEBAS A PROCESAR</td>";
			$this->salida.="<tr>";
		}
		$this->salida.="</table>";
		
		
		//BOTON DE ENVIAR
		$accionEnviar=ModuloGetURL('app','Os_ListaTrabajoVitros','user','CrearArchivo',array('numero_orden_id'=>$os_vitros[$i][numero_orden_id],'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'vector'=>$vector));
		$imagen='auditoria.png';
		$texto='DETALLES DE LA COMUNICACION';
		$ayuda="PERMITE VISUALIZAR EL ESTADO DE LOS EXAMENES QUE SE ESTAN PROCESANDO EN EL VITROS EN ESTE INSTANTE";
		$directiva="<a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','Consulta_Ordenes',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id,'nombre'=>$nombre, 'edad_paciente'=>$edad_paciente)).">";
		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <form name=\"forma\" action=\"$accionEnviar\" method=\"post\">";
		$this->salida.="  	<td width=\"33%\">&nbsp;&nbsp;</td>";
		$this->salida.="  	<td width=\"33%\" align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"ENVIAR DATOS A LA VITROS\"></form></td>";
		$this->salida.="  	<td width=\"33%\" align=\"right\" >";
		$this->salida.="			$directiva <img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\" title=\"$ayuda\">$texto";
		$this->salida.="  	</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
// 		$accionEnviar=ModuloGetURL('app','Os_ListaTrabajoVitros','user','CrearArchivo',array('numero_orden_id'=>$os_vitros[$i][numero_orden_id],'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'vector'=>$vector));
// 		
// 		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
// 		$this->salida.="<tr class=\"$estilo\">";
// 		$this->salida.="  <form name=\"forma\" action=\"$accionEnviar\" method=\"post\">";
// 		$this->salida.="  	<td align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"ENVIAR DATOS A LA VITROS\"></form></td>";
// 		$this->salida.="</tr>";
// 		$this->salida.="</table>";
		//BOTON DE VOLVER
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"$estilo\">";
		$accionV=ModuloGetURL('app','Os_ListaTrabajoVitros','user','BuscarOrden');
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}//fin function Consultar_Cumplimiento

	/**
	* Muestra un mensaje de error al tratar de enviar examenes a la vitros sin que esten asignados y preparados
	*/
	function MuestraError($mensaje){
		$this->salida = ThemeAbrirTablaSubModulo('¡¡¡MENSAJE DE ERROR!!!');
		$this->salida.= "<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.= "<tr >";
		$this->salida.= "	<td align=\"center\" ><font color='#FF0000'>$mensaje</font><td>";
		$this->salida.= "</tr>";
		$this->salida.="<tr>";
		$accionV=ModuloGetURL('app','Os_ListaTrabajoVitros','user','BuscarOrden');
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"POSPONER ENVIO\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		return true;
	}
		

		
	/**
* Mediante este formulario se puede ver el estado de error de los examenes que fueron enviados a la vitros
* y ademas puede renevarlos si es necesario
*/
function Consultar_Estado_Os_Vitros($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,$paciente_id,$nombre,$edad_paciente)
{
    $this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE CUMPLIMIENTOS PARA VITROS');
		$accion=ModuloGetURL('app','Os_ListaTrabajoVitros','user','ActualizaDatos',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'Buscar_Orden_Cargar_Session'=>'buscar'));
		$this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";

		$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$paciente_id."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">".$nombre."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$edad_paciente."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"left\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"center\" width=\"10%\">".$fecha_cumplimiento."</td>";
		$this->salida.="  <td align=\"left\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
		$cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento);
		$this->salida.="  <td align=\"center\" width=\"10%\">".$cumplimiento."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		
		//datos validados
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">"; 
		
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan = 6 align=\"center\" width=\"80%\">ESTADO DE LAS ORDENES DE SERVICIO QUE SON ENVIADAS A LA VITROS</td>";
		$this->salida.="</tr>";
		$os_vitros=$this->ConsultaEstadoOS();
	
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">MUESTRA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">BANDEJA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"5%\">COPA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"30%\">PRUEBAS</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"30%\">ESTADO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"20%\">ACCION</td>";
		$this->salida.="</tr>";
		$SwReenvia='0';
		//muestras listas para enviar a la vitros
		for($i=0;$i<sizeof($os_vitros);$i++){
			if($os_vitros[$i][estado_examen]>='5'){
					if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td colspan = 1 align=\"center\" >".$os_vitros[$i][muestra_id]."</td>";
					if($os_vitros[$i][bandeja_id]=='0'){
						$bandeja='';$copa='';
					}else{
						$bandeja=$os_vitros[$i][bandeja_id];
						$copa=$os_vitros[$i][posicion_copa];}
					$this->salida.="  <td colspan = 1 align=\"center\" >".$bandeja."</td>";
					$this->salida.="  <td colspan = 1 align=\"center\" >".$copa."</td>";
					$this->salida.="  <td colspan = 1 align=\"center\" >".$os_vitros[$i][nombre_prueba]."</td>";
					$this->salida.= "<td colspan = 1 align=\"center\" >";
						if($os_vitros[$i][estado_examen]=='2'){$imagen='lab_procesando.png';$texto='EN ESPERA DE RESPUESTA';
						}elseif($os_vitros[$i][estado_examen]=='5'){$imagen='alarma.gif';
																											if($os_vitros[$i][descripcion_error]==NULL){$texto='ERROR DE COMUNICACION';
																											}else{$texto=$os_vitros[$i][descripcion_error];}
																											$ayuda="REVISE LA CONECCION CON LA VITROS O QUE EL WEBSERVICE ESTE EN EJECUCION";}
																											$SwReenvia="1";
					$this->salida.= "<img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\" title=\"$ayuda\"></BR>$texto</td>";
					if($SwReenvia=='1'){
						$imagen='uf.png';
						$file="S".str_pad($os_vitros[$i][nombre_archivo],7, "0", STR_PAD_LEFT);
						$this->salida.="<td colspan = 1 align=\"center\" > <a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','ReenviaArchivo',array('file'=>$file, 'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente))."> <img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\"></a><br>REENVIAR</td>";
					}
					else{
						$this->salida.="  <td colspan = 1 align=\"center\" ></td>";
					}
			}
		}
		$this->salida.="</table>";
		//BOTON DE VOLVER
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"$estilo\">";
		$accionV=ModuloGetURL('app','Os_ListaTrabajoVitros','user','BuscarOrden');
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}

	/**
	*
	*/
	function Consultar_Ordenes_Vitros($numero_cumplimiento,$fecha_cumplimiento,$departamento,$tipo_id_paciente,$paciente_id,$nombre,$edad_paciente)
{
    $this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE CUMPLIMIENTOS PARA VITROS');
		
		$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		
		//datos validados
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">"; 
		
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td colspan = 4 align=\"center\" width=\"80%\">ESTADO DE LAS ORDENES QUE ESTAN EN LA BANDEJA DE TRABAJO DE EL VITROS</td>";
		$this->salida.="</tr>";
		$os_vitros=$this->ConsultaOrdenesVitros();
	
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"15%\">MUESTRA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"25%\">PRUEBA</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"35%\">ESTADO</td>";
		$this->salida.="  <td colspan = 1 align=\"center\" width=\"25%\">ACCION</td>";
		$this->salida.="</tr>";
		$SwReenvia='0';
		//muestras listas para enviar a la vitros
		for($i=0;$i<sizeof($os_vitros);$i++)
		{
			if( $i % 2){ $estilo='modulo_list_claro';}
			else {$estilo='modulo_list_oscuro';}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="  <td colspan = 1 align=\"center\" >".$os_vitros[$i][muestra_id]."</td>";
			$this->salida.="  <td colspan = 1 align=\"center\" >".$os_vitros[$i][nombre_prueba]."</td>";
			$this->salida.= "<td colspan = 1 align=\"center\" >";
			if($os_vitros[$i][estado_examen]=='2')
			{
				$imagen='lab_procesando.png';$texto='EN ESPERA DE RESPUESTA';
			}
			elseif($os_vitros[$i][estado_examen]=='5')
			{
				$imagen='alarma.gif';
				if($os_vitros[$i][descripcion_error]==NULL)
				{$texto='ERROR DE COMUNICACION';
				}else{$texto=$os_vitros[$i][descripcion_error];}
				$ayuda="REVISE LA CONECCION CON LA VITROS O QUE EL WEBSERVICE ESTE EN EJECUCION";
			}
			$SwReenvia="1";
			$this->salida.= "<img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\" title=\"$ayuda\"></BR>$texto</td>";
			
			//ACCION A EJECUTAR CON EL EXAMEN
			$this->salida.="	<td class=\"$estilo\" colspan = 1>";
			$this->salida.="		<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="			<tr class=\"$estilo\">";
			$imagen='uf.png';
			$file="S".str_pad($os_vitros[$i][nombre_archivo],7, "0", STR_PAD_LEFT);
			$this->salida.="				<td align=\"center\" > <a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','ReenviaArchivo',array('file'=>$file, 'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente))."> <img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\"><br>REENVIAR</a></td>";
			$imagen='elimina.png';
			$this->salida.="				<td align=\"center\" title=\"ELIMINA EXAMEN DE LA LISTA DE VITROS\">";
			$this->salida.="					<a href=".ModuloGetURL('app','Os_ListaTrabajoVitros','user','EliminaExamenListaVitros',array('numero_orden_id'=>$os_vitros[$i][numero_orden_id],'numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$os_vitros[$i][tipo_id_paciente], 'paciente_id'=>$os_vitros[$i][paciente_id],'nombre'=>$nombre, 'edad_paciente'=>$edad_paciente))."> <img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\"><BR>ELIMINAR</a>";
			$this->salida.="				</td>";
			$this->salida.="			</tr>";
			$this->salida.="		</table>";	
			$this->salida.="	</td>";
			$this->salida.="</tr>";
		}
		$this->salida.="</table>";
		//BOTON DE VOLVER
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"$estilo\">";
		$accionV=ModuloGetURL('app','Os_ListaTrabajoVitros','user','GetForma',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'Buscar_Orden_Cargar_Session'=>'buscar'));
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.= ThemeCerrarTabla();
		
		return true;
	}

}//fin clase

?>
