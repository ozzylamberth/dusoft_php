<?php

/**
 * $Id: app_Os_Control_Placas_userclasses_HTML.php,v 1.4 2005/11/17 22:22:41 mauricio Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_Os_Control_Placas_userclasses_HTML extends app_Os_Control_Placas_user
{

	function app_Os_Control_Placas_user_HTML()
	{
		$this->app_Os_Control_Placas_user(); //Constructor del padre 'modulo'
			$this->salida='';
			return true;
	}
	/**
	*
	*/
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
	/**
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
			$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJO']['NOM_EMP']."</td>";
			$this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['LTRABAJO']['NOM_CENTRO']."</td>";
			$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LTRABAJO']['NOM_DPTO']."</td>";
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
					if($value==$TipoId){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}else{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
			}
	}
	
	
		/**
		* Esta funcion calcula el numero de pasos que saldran en la barra de navegación.
		* @return boolean
		*/
		function CalcularNumeroPasos($conteo)
		{
				$numpaso=ceil($conteo/$this->limit);
				return $numpaso;
		}

		/**
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


	/**
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
					{   $vec[$v]=$v1;   }
			}
			$accion=ModuloGetURL('app','Os_Control_Placas','user','BuscarOrden',$vec);
			$barra=$this->CalcularBarra($paso);
			$numpasos=$this->CalcularNumeroPasos($this->conteo);
			$colspan=1;

			$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
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
									}else{
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
									}else{
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
	* Esta funcion realiza la busqueda de cumplimientos de ordenes de servicio
	* según filtros como tipo, documento, nombre y plan
	* @return boolean
	*/
	function FormaBuscar($arr)
	{
		$this->salida.= ThemeAbrirTabla('CONTROL DE PLACAS');
		$accion=ModuloGetURL('app','Os_Control_Placas','user','BuscarOrden');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();

		if ($_SESSION['LTRABAJO']['MOSTRAR_LISTAS']==1)
		{
				if (!$_SESSION['IMAGENES']['LISTAS'])
			{
						$_SESSION['IMAGENES']['LISTAS'] = $this->GetListasTrabajo();
				}

				if ($_SESSION['IMAGENES']['LISTAS'])
				{
						$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
						$this->salida .= "<tr class=\"modulo_table_title\">";
						$this->salida .= "<td align = center colspan = 3 >LISTAS DE TRABAJO ASIGNADAS</td>";
						$this->salida .= "</tr>";

						//seleccionar todas las listas
						$this->salida .= "<SCRIPT>";
						$this->salida .= "function chequeoTotal(frm,x){";
						$this->salida .= "  if(x==true){";
						$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
						$this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name != 'allfecha'){";
						$this->salida .= "        frm.elements[i].checked=true";
						$this->salida .= "      }";
						$this->salida .= "    }";
						$this->salida .= "  }else{";
						$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
						$this->salida .= "      if(frm.elements[i].type=='checkbox' && frm.elements[i].name != 'allfecha'){";
						$this->salida .= "        frm.elements[i].checked=false";
						$this->salida .= "      }";
						$this->salida .= "    }";
						$this->salida .= "  }";
						$this->salida .= "}";
						$this->salida .= "</SCRIPT>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida .= "<td colspan = 2 align = right width=\"15%\">SELECCIONAR TODAS</td>";
						$this->salida.="  <td align=\"center\" width=\"10%\"><input type = checkbox name= 'AllListas' onclick=chequeoTotal(this.form,this.checked)></td>";
						$this->salida .= "</tr>";
						//fin de listas

						$this->salida .= "<tr class=\"modulo_table_list_title\">";
						$this->salida .= "<td align = center width=\"15%\">NUMERO DE LISTA</td>";
						$this->salida .= "<td align = center width=\"55%\">LISTA DE TRABAJO</td>";
						$this->salida .= "<td align = center width=\"10%\">OPCION</td>";
						$this->salida .= "</tr>";

						$x=$_REQUEST['op'];
						for ($i=0; $i<sizeof($_SESSION['IMAGENES']['LISTAS']);$i++)
						{
								$this->salida .= "<tr class=\"modulo_list_claro\" >";
								$this->salida .= "<td align = center>".$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id']."</td>";
								$this->salida .= "<td align = left>".$_SESSION['IMAGENES']['LISTAS'][$i]['nombre_lista']."</td>";
								if($x[$i]==$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id'])
								{
										$this->salida.="  <td align=\"center\"><input type = checkbox name= 'op[$i]' value = ".$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id']." checked></td>";
								}
								else
								{
										$this->salida.="  <td align=\"center\"><input type = checkbox name= 'op[$i]' value = ".$_SESSION['IMAGENES']['LISTAS'][$i]['tipo_os_lista_id']."></td>";
								}
								$this->salida .= "</tr>";
						}
						$this->salida .= "</table>";
				}
		}
		$rep= new GetReports();
		$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td align = center colspan = 2 >CUMPLIMIENTOS</td>";
		$this->salida .= "</tr>";

		$this->salida .= "<tr class=\"modulo_table_list_title\">";
		$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
		$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
		$this->salida .= "</tr>";

		$this->salida .= "<tr class=\"modulo_list_claro\" >";
		$this->salida .= "<td width=\"40%\" >";
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
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
		$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value = ".$_REQUEST['Documento']."></td></tr>";
		$this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" value = ".$_REQUEST['Nombres']."></td></tr>";
		$this->salida .= "<tr><td class=\"label\">No. CUMPLIMIENTO</td><td><input type=\"text\" class=\"input-text\" name=\"Cumplimiento\" maxlength=\"50\" size = 20 value = ".$_REQUEST['Cumplimiento']."></td></tr>";
		$this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = ".$_REQUEST['DiaEspe']."></td></tr>";

		//$this->salida .= "<table>";
		$this->salida .= "<tr class=\"label\">";
		$this->salida .= "<td align = left >TODAS LAS FECHAS</td>";
		$this->salida.="  <td align=\"left\"><input type = checkbox name= 'allfecha' onclick=Revisar(this.form,this.checked)></td>";
		$this->salida .= "</tr>";
		//por defecto que traiga SOLO los pacientes atendidos
		$this->salida.="<input type=hidden name='opcion_pacientes' value= '2'>";
// 		$this->salida .= "<tr><td class=\"label\">PACIENTES: </td><td><select name=\"opcion_pacientes \" class=\"select\">";
// 		$this->salida .=" <option value= 1 selected>Pacientes sin Atender</option>";
// 		if ($_REQUEST['opcion_pacientes']==2)
// 		{
//     $this->salida .=" <option value= 2 selected>Pacientes Atendidos</option>";
// 		}
// 		else
// 		{
//       $this->salida .=" <option value= 2 >Pacientes Atendidos</option>";
// 		}
// 		if ($_REQUEST['opcion_pacientes']==3)
// 		{
//     $this->salida .=" <option value= 3 selected>Todos los Pacientes</option>";
// 		}
// 		else
// 		{
//       $this->salida .=" <option value= 3 >Todos los Pacientes</option>";
// 		}
//     $this->salida .= "</select></td></tr>";
    
		//fin de filtros
		$this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
		$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar_Orden_Cargar_Session\" value=\"BUSCAR\"></td>";
		$this->salida .= "</form>";

		$actionM=ModuloGetURL('app','Os_Control_Placas','user','main');
		$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
		$this->salida .= "</tr>";

		$this->salida .= "<tr align='center' class=\"modulo_list_claro\" >";
		//lo de alex
		//$reporte= new GetReports();
		$mostrar=$rep->GetJavaReport('app','Os_Control_Placas','examenes_html','',array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		$nombre_funcion=$rep->GetJavaFunction();
		$this->salida .=$mostrar;
		//$this->salida .= "    <td><br><input class=\"input-submit\" name=\"Cancelar\" type=\"button\" value=\"IMPRIMIR\" onclick=\"javascript:$nombre_funcion\"></td>";
		$this->salida.="<td width=\"10%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> INFORME DE PLACAS</a></td>";
		//fin de alex
		$this->salida .= "</tr>";
		$this->salida .= "</table></td></tr>";

		$this->salida .= "</td></tr></table>";

		$this->salida .= "</table>";
		$this->salida .= "</td>";

		$this->salida .= "<td>";

		$this->salida .= "<BR><table border=\"0\" width=\"80%\" align=\"center\">";
//aqui inserte lo de lorena
		$this->salida .= "<tr><td>";

		//$_REQUEST['DiaEspe'];

		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
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
/**************************************/
		$this->salida .= "</table>";

		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table>";

		if(!empty($arr))
		{
				$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida.= $this->SetStyle("MensajeError");
				$this->salida.="</table>";
				$this->salida.= "<table width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";

				//codigo para pintar en el resultado de la busqueda el filtro utilizado.
				$texto = '';
				if ($_REQUEST['opcion_pacientes'] == 1)
				{
					$texto = 'PACIENTES SIN ATENDER';
				}
				if ($_REQUEST['opcion_pacientes'] == 2)
				{
					$texto = 'PACIENTES ATENDIDOS';
				}
				if ($_REQUEST['opcion_pacientes'] == 3)
				{
					$texto = 'TODOS LOS PACIENTES';
				}
				if ($texto != '')
				{
					$this->salida .= "<tr class=\"modulo_table_title\">";
					$this->salida.="<td colspan=6 align=\"center\">FILTRO DE BUSQUEDA: ".$texto."</td>";
					$this->salida.="</tr><br>";
				}
				//fin del pintado del filtro


				$this->salida .= "            <tr align=\"center\" class=\"modulo_table_list_title\">";
				//MauroB
				$this->salida .= "                <td width=\"15%\">No. CUMPLIMIENTO</td>";
				//fin MauroB
				$this->salida .= "                <td width=\"10%\">SERVICIO</td>";
				$this->salida .= "                <td width=\"5%\">HISTORIA CLINICA</td>";
				$this->salida .= "                <td width=\"5%\">IDENTIFICACION</td>";
				$this->salida .= "                <td width=\"35%\">NOMBRE DEL PACIENTE</td>";
				$this->salida .= "                <td width=\"10%\">OPCION</td>";
				$this->salida .= "            </tr>";

				for($i=0;$i<sizeof($arr);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$cumplimiento=$this->ConvierteCumplimiento($arr[$i][fecha_cumplimiento],$arr[$i][numero_cumplimiento],$_SESSION['LTRABAJO']['DPTO']);
						//Edad
						$edad_paciente = CalcularEdad($arr[$i][fecha_nacimiento],date("Y-m-d"));
						$this->salida.="<tr class='$estilo' align='center'>";
						//MauroB
						$this->salida.="  <td ><font color='#4D6EAB'>".$cumplimiento." </font></td>";
						//fin MauroB
						$this->salida.="  <td >".$arr[$i][servicio_descripcion]."</td>";
						$this->salida.="  <td >".$arr[$i][historia_prefijo]." - ".$arr[$i][historia_numero]."</td>";
						$this->salida.="  <td align='left'>".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</td>";
						$this->salida.="  <td >".$arr[$i][nombre]."</td>";
						$this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_Control_Placas','user','GetForma',array('numero_orden_id'=>$arr[$i][$numero_orden_id],'numero_cumplimiento'=>$arr[$i][numero_cumplimiento], 'fecha_cumplimiento'=>$arr[$i][fecha_cumplimiento], 'departamento'=>$arr[$i][departamento], 'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
						$this->salida.="</tr>";
				}
				$this->salida.="</table>";
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
	}
	
	/**
	*
	*/
	function Consultar_Cumplimiento($numero_cumplimiento, $fecha_cumplimiento, $departamento, $tipo_id_paciente, $paciente_id,$nombre, $edad_paciente)
	{

		$this->salida= ThemeAbrirTablaSubModulo('CONTROL DE PLACAS');
		$cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento);
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">$paciente_id</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">$nombre</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">$edad_paciente</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"left\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"center\" width=\"10%\">$fecha_cumplimiento</td>";
		$this->salida.="  <td align=\"left\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
		$this->salida.="  <td align=\"center\" width=\"10%\">$cumplimiento</td>";
			$this->salida.="</tr>";
		$this->salida.="</table>";
		$vector = $this->ConsultaOrdenesPaciente($numero_cumplimiento, $fecha_cumplimiento, $departamento);
		
		$accionReubica=ModuloGetURL('app','Os_Control_Placas','user','GetControlPlacas',array('numero_cumplimiento'=>$numero_cumplimiento, 'fecha_cumplimiento'=>$fecha_cumplimiento, 'departamento'=>$departamento, 'tipo_id_paciente'=>$tipo_id_paciente, 'paciente_id'=>$paciente_id, 'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'sw_estado'=>$vector[$i][sw_estado]) );
		$this->salida.="<form name=\"forma\" action=\"$accionReubica\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";

	//Mauro
		$columnas=6;
		$permiso=$this->ConsultaPermisoControlPlaca();

	//fin MauroB
	if($permiso=='1'){
		for($i=0;$i<sizeof($vector);$i++)
				{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$_SESSION['LTRABAJO']['DPTO']);
								if ($vector[$i][nombre_lista] != $vector[$i-1][nombre_lista])
								{
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td colspan = $columnas align=\"center\" width=\"10%\">".$vector[$i][nombre_lista]."</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
										$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">NUM. CUMPLIMIENTO</td>";
										$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">NUMERO ORDEN</td>";
										$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">CARGO</td>";
										$this->salida.="  <td colspan = 1 align=\"center\" width=\"40%\">DESCRIPCION</td>";
										$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">UBICACION</td>";
										$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">OPCION</td>";
										$this->salida.="</tr>";
								}

								$this->salida.="<tr class=\"$estilo\">";
								//$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">".$numero_cumplimiento." - ".($i+1)."</td>";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">".$cumplimiento."</td>";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">".$vector[$i][numero_orden_id]."</td>";
								$this->salida.="  <td colspan = 1 align=\"center\" width=\"10%\">".$vector[$i][cargo]."</td>";
								$this->salida.="  <td colspan = 1 align=\"left\" width=\"40%\">".$vector[$i][descripcion]."</td>";

								/** ## Valida si es Rx*/
									if($vector[$i][sw_estado]=='1'){
										$ubicacion=$this->ConsultaUbicacionRx($vector[$i][numero_orden_id]);

										$perdida=$this->ConsultaEstadoPlacaPerdido($vector[$i][numero_orden_id]);
										//echo "<br>Perdida--->>>".$perdida;
										//echo "<br> des-> ".$ubicacion[0][descripcion]." ltrabaj-> ".$_SESSION['LTRABAJO']['NOM_DPTO'] ;
										if(($perdida=='1')){
											$texto='Rx PERDIDA';$imagen='RXperdida';
										}
										elseif($ubicacion[0][nuevo_departamento]==$_SESSION['LTRABAJO']['DPTO']){
											$imagen='RXdeptoImagenologia.png';$texto=$ubicacion[0][descripcion];
										}
										else{
											$imagen='RXotrodepto.png';$texto=$ubicacion[0][descripcion];}
											$this->salida .= "<td width=\"10%\" align=\"center\"><img src=\"". GetThemePath() ."/images/$imagen\" width=\"15\" border=\"0\" height=\"15\">$texto</a></td>";
									}else{
										$this->salida .= "<td align=\"center\" width=\"10%\">PLACA SIN TOMAR</td>";
									}
								//fin mauricio
								if($vector[$i][sw_estado]=='1'){
									$this->salida.="  <td align=\"center\" width=\"13%\"><input type = checkbox name= 'placas"."[$i]"."[op]' value = 1 checked></td>";
								}else{
									$this->salida.="  <td></td>";
								}
								$this->salida.="		<input type=hidden name='placas"."[$i]"."[numero_orden_id]' value= ".$vector[$i][numero_orden_id].">";
								$this->salida.="		<input type=hidden name='placas"."[$i]"."[cargo]' value= ".$vector[$i][cargo].">";
								$this->salida.="		<input type=hidden name='placas"."[$i]"."[descripcion]' value= ".$vector[$i][descripcion].">";
								$this->salida.="</tr>";
				}
			
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="	<td  colspan = $columnas align=\"right\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"REUBICA PLACA\"></form></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
			}else{
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td colspan = $columnas align=\"center\" width=\"10%\">EL DEPARTAMENTO NO POSEE PERMISOS PARA UBICAR PLACAS DE RX</td>";
				$this->salida.="</tr>";
			}
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
				$this->salida.="<tr class=\"$estilo\">";
				//BOTON DE VOLVER
				$accionV=ModuloGetURL('app','Os_Control_Placas','user','BuscarOrden');
				$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
		}

	/** 
	*	Formulario para registrar el control de las placas
	* @access public
	* @return true
	*/
		function ControlPlacas($placas,$numero_cumplimiento,$fecha_cumplimiento,$departamento,
														$tipo_id_paciente,$paciente_id,$nombre,$edad_paciente,$sw_estado){

			$disabled='';
			$estado_placa='';
			$this->salida.= ThemeAbrirTablaSubModulo('CONTROL DE PLACAS');
			$accionUpdate=ModuloGetURL('app','Os_Control_Placas','user','UpdateControlPlacas',
											array('placas'=>$placas,'numero_cumplimiento'=>$numero_cumplimiento,
											'fecha_cumplimiento'=>$fecha_cumplimiento,'departamento'=>$departamento,'tipo_id_paciente'=>$tipo_id_paciente,
											'paciente_id'=>$paciente_id,'nombre'=>$nombre,'edad_paciente'=>$edad_paciente));
			$this->salida .= "<form name=\"enviar\" action=\"$accionUpdate\" method=\"post\">";
			
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
			$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
			$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"1\">$paciente_id</td>";
			$this->salida.="  <td align=\"center\" colspan=\"2\">$nombre</td>";
			$this->salida.="  <td align=\"center\" colspan=\"1\">$edad_paciente</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
	
			$cumplimiento=$this->ConvierteCumplimiento($fecha_cumplimiento,$numero_cumplimiento,$departamento);
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td align=\"left\" width=\"20%\">FECHA DE CUMPLIMIENTO: </td>";
			$this->salida.="  <td align=\"center\" width=\"10%\">$fecha_cumplimiento</td>";
			$this->salida.="  <td align=\"left\" width=\"20%\">NUMERO DE CUMPLIMIENTO: </td>";
			$this->salida.="  <td align=\"center\" width=\"10%\">$cumplimiento</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			
			$this->salida .= "  <script language=\"Javascript\">";
			$this->salida .= " function abrir(pagina) {"."\n";
			$this->salida .= " 		window.open(pagina,'window','params')"."\n";
			$this->salida .= "}"."\n";
			$this->salida .= "</script>"."\n";
			
			//script para consultar departamentos
			$this->salida .= "  <script>";
			$this->salida.=  "  function filtrodpto(valor)"."\n";
			$this->salida.='    {'."\n";
			//$this->salida.=' alert(valor);'."\n";
			$this->salida.='    window.location.href="Contenido.php?depto="+valor+"';
			foreach($_REQUEST as $v=>$v1){
				if($v!='depto'){
					$this->salida.='&'.$v.'='.$v1;
				}
			}
			$a='';
			$this->salida.=' ";'."\n";
			$this->salida.=' }'."\n";
			$this->salida.= "</script>";
				$this->salida.= "<SCRIPT language='javascript'>";
				$this->salida.= "function Revisar(frm,x,pos){";
				$this->salida.= "  if(x==true){\n";
				//$this->salida.= "  alert('aqui estoy')\n";
				$this->salida.= "			document.getElementById('perdida_'+pos).value='1'\n";
				
				$this->salida.= "			frm.depto.disabled=true\n";
				$this->salida.= "  }else{\n";
				$this->salida.= "			document.getElementById('perdida_'+pos).value='0'\n";
				$this->salida.= "			frm.depto.disabled=false\n";
				$this->salida.= "  }";
				$this->salida.= "}";
				$this->salida.= "</SCRIPT>";
			/**/
			
			$estilo='modulo_list_claro';
			$this->salida.="<fieldset><legend class=\"field\">CONTROL DE UBICACION DE PLACAS</legend>";
			$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.="</table>";

			$this->salida.="<table class=\"module_table_list\" align=\"center\" border=\"0\"  width=\"80%\" colspan=\"4\">";
			
			$this->salida.="	<tr class=\"$estilo\">";
			$this->salida.="		<td align=\"left\" width=\"20%\" class=\"".$this->SetStyle("depto")."\"> NUEVA UBICACION: </td>";
			//filtro departamento
			$this->salida .= "  <td width=\"20%\" >";
			//$this->salida .= "  	<select name=\"depto\" onchange=\"filtrodpto(this.value)\" class=\"select\" $disabled>";
			$this->salida .= "  	<select name=\"depto\" class=\"select\" $disabled>";
			$this->salida .= "  		<option value=\"-1\" selected>--  SELECCIONE  --</option>";
			$dpto=$this->BuscarDepartamento();
			$a=explode(',',$_REQUEST['depto']);
			for($i=0;$i<sizeof($dpto);$i++){
				if($dpto[$i]['departamento']==$a[0]){
					$this->salida .="		<option value=\"".$dpto[$i]['departamento']."\" selected>".$dpto[$i]['descripcion']."</option>";
				}else{
					$this->salida .="		<option value=\"".$dpto[$i]['departamento']."\">".$dpto[$i]['descripcion']."</option>";
				}
			}
			$this->salida .= "   </select>";
			$this->salida .= "  </td>";


			//usuario que recibe la placa
			$this->salida.="	<td>";
			$this->salida.="		<table  align=\"center\" border=\"0\" >";
			$this->salida.="			<tr>";
			$this->salida.="				<td align=\"left\" colspan=\"1\" class=\"".$this->SetStyle("login_usu")."\" >USUARIO QUE RECIBE LA PLACA : </td>";
			$this->salida.="				<td width=\"10%\" align=\"left\"><input type=\"text\" name=\"login_usu\" class=\"input-text\" maxlength=\"10\" size=\"10\" value= \"\"></td>";
			$this->salida.="				<td align=\"left\" colspan=\"1\" class=\"".$this->SetStyle("login_usu")."\">PASSWORD : </td>";
			$this->salida.="			</tr>";
			$this->salida.="		</table>";	
			$this->salida.="	</td>";
			$this->salida.="	<td width=\"10%\" align=\"left\"><input type=\"password\" name=\"paswd_usu\" class=\"input-text\" maxlength=\"10\" size=\"10\" value= \"\"></td>";

			$this->salida.="	</tr>";
			
			$this->salida.="	<tr class=\"$estilo\">";
			$this->salida.="		<td align=\"left\" colspan=\"1\"> COMENTARIO: </td>";
			$this->salida.="		<td colspan=\"4\" align=\"center\" width=\"30%\"><textarea style=\"width:80%\" class='textarea' name='comentario' cols=100 rows=10> </textarea></td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			$this->salida.="<table class=\"module_table_list\" align=\"center\" border=\"0\"  width=\"80%\" colspan=\"4\">";	
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td align=\"left\" width=\"20%\">CARGO: </td>";
			$this->salida.="  <td align=\"center\" width=\"50%\">DESCRIPCION: </td>";
			$this->salida.="  <td align=\"left\" width=\"20%\"> </td>";
			$this->salida.="  <td align=\"center\" width=\"10%\">OPCION: </td>";
			$this->salida.="</tr>";
			
			
			for($j=0;$j<sizeof($placas);$j++){
				if($placas[$j]['op']== '1'){
					$this->salida.="	<tr class=\"$estilo\">";
					$this->salida.="	<td width=\"20%\">".$placas[$j]['cargo']."</td>";
					$this->salida.="	<td width=\"50%\">".$placas[$j]['descripcion']."</td>";
					$this->salida.="		<td align=\"right\" width=\"20%\" > DECLARAR PERDIDA: </td>";
					$estado_placa=$this->ConsultaEstadoPlacaPerdido($placas[$j]['numero_orden_id']);
					if($estado_placa=='1'){
						$estado_placa='checked';
						$disabled="disabled";
						//$this->CambiaEstadoPerdida_a_Encontrada($placas[$j]['numero_orden_id']);
					}
					$this->salida.="		<td align=\"center\" width=\"10%\" > <input type = checkbox name= 'placas"."[$j]"."[perdida]' onclick=Revisar(this.form,this.checked,$j) $estado_placa></td>";
					$this->salida.="<input type=hidden name='placas"."[$j]"."[perdida]' id='perdida_$j'>";
					$this->salida.="	</tr>";
				}
			}
			$this->salida.="</table>";
			$this->salida.="<input type=hidden name='usuario_remitente' value= ".UserGetUID().">";

			//BOTON ACTUALIZAR DATOS
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"$estilo\">";
					$this->salida .= "<td  colspan = 6 align=\"center\"><input class=\"input-submit\" name=\"enviar\" type=\"submit\" value=\"RECIBIR PLACA(S)\"></form></td>";
				$this->salida.="</tr>";
			$this->salida.="</table>";
			
			for($j=0;$j<sizeof($placas);$j++){
				if($placas[$j]['op']== '1'){
					$vector=$this->ConsultaMovimientoPlacas($placas[$j]['numero_orden_id']);
					//print_r($vector);
					if(!empty($vector)){
						$this->salida.= "<table width=\"80%\" border=\"0\" cellspacing=\"6\" cellpadding=\"3\" align=\"center\" >";
						$this->salida.= "	<tr class=\"modulo_table_list_title\">";
						$this->salida.="	<td align=\"left\" width=\"20%\" >".$placas[$j]['cargo']."</td>";
						$this->salida.="	<td align=\"left\" width=\"50%\" colspan=\"5\">".$placas[$j]['descripcion']."</td>";
						$this->salida.= "	</tr>";
						$this->salida.= "	<tr align=\"center\" class=\"modulo_table_list_title\">";
						$this->salida.= "    <td width=\"10%\">FECHA</td>";
						$this->salida.= "    <td width=\"15%\">USUARIO REMITENTE</td>";
						$this->salida.= "    <td width=\"15%\">DEPARTAMENTO REMITENTE</td>";
						$this->salida.= "    <td width=\"15%\">USUARIO DESTINO</td>";
						$this->salida.= "    <td width=\"15%\">DEPARTAMENTO DESTINO</td>";
						$this->salida.= "    <td width=\"30%\">COMENTARIO</td>";
						$this->salida.= " </tr>";
		
						for($i=0;$i<sizeof($vector);$i++)
						{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$fecha=$this->FechaStamp($vector[$i][fecha]);
								$hora=$this->HoraStamp($vector[$i][fecha]);
								$u_remitente=$this->ConsultaNombreProfesional($vector[$i][usuario_id_remite]);
								$d_remitente=$this->BuscaNombreDepartamento($vector[$i][departamento_actual]);
								$u_destino=$this->ConsultaNombreProfesional($vector[$i][usuario_id_recibe]);
								$d_destino=$this->BuscaNombreDepartamento($vector[$i][nuevo_departamento]);
								
								$this->salida.="<tr class='$estilo' align='center'>";
								$this->salida.="  <td >".$fecha." ".$hora."</td>";
								$this->salida.="  <td >".$u_remitente."</td>";
								$this->salida.="  <td >".$d_remitente."</td>";
								$this->salida.="  <td >".$u_destino."</td>";
								$this->salida.="  <td >".$d_destino."</td>";
								$this->salida.="  <td textarea class='textarea' readonly>".$vector[$i][comentario]."</td>";
								$this->salida.="</tr>";
						}
						$this->salida.="</table>";
					}
				}
			}
			//BOTON DE VOLVER
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"$estilo\">";
					$accionVolver=ModuloGetURL('app','Os_Control_Placas','user','GetForma',array('numero_cumplimiento'=>$numero_cumplimiento,'fecha_cumplimiento'=>$fecha_cumplimiento,'departamento'=>$departamento,
																																											'tipo_id_paciente'=>$tipo_id_paciente,'paciente_id'=>$paciente_id,'nombre'=>$nombre,
																																											'edad_paciente'=>$edad_paciente));
					$this->salida .= "<form name=\"forma\" action=\"$accionVolver\" method=\"post\">";
					$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}//fin function ControlPlacas
		

}//fin de la clase
?>

