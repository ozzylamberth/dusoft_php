 <?php

 /**
 * $Id: app_Control_Cierre_userclasses_HTML.php,v 1.32 2006/12/15 14:38:03 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de ordenes de servicio
 */

/**
*  app_Os_Atencion_userclasses_HTML.php
*
* Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
* del modulo Os_Atencion , se extiende la clase Os_Atencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
class app_Control_Cierre_userclasses_HTML extends app_Control_Cierre_user
{
	/**
	* Constructor de la clase app_Os_Atencion_userclasses_HTML
	* El constructor de la clase aOs_Atencion_userclasses_HTML se encarga de llamar
	* a la clase app_Os_Atencion_user quien se encarga de el tratamiento
	* de la base de datos.
	* @return boolean
	*/

		function app_Control_Cierre_userclasses_HTML()
		{
					$this->salida='';
					$this->app_Control_Cierre_user();
					return true;
		}


		function SetStyle($campo)
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
				$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
				return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}



function FrmMostrarDatos($secuencia_orden)
{
		$this->salida.= ThemeAbrirTabla('ORDEN');
		$this->Encabezado()	;
		$this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
		$this->salida.="<tr align=\"center\"> ";
		$this->salida.="<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >NUMERO ORDEN</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr align=\"center\">";
		$this->salida.="<td colspan=\"2\"   align=\"center\" class=\"modulo_list_oscuro\" >$secuencia_orden</td>";
		$this->salida.="</tr>";
		//$ac=ModuloGetURL('app','Laboratorio','admin','RetornarPermisos');
		//$ax=ModuloGetURL('app','Laboratorio','user','BuscarPermisosUser');
		$this->salida.="</table>";

			$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
			$action2=ModuloGetURL('app','Laboratorio','user','DecisionOrdenLab',array('nombre'=>$NombreUsuario));
			$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
			$this->salida .= "</tr>";
			$this->salida.="</table><br>";
			$this->salida.= ThemeCerrarTabla();
		return true;

}

/**ESTE MENU YA NO ESTA HABILITADO .................OJO...........
* Funcion donde se visualiza el menu de usuario.
* @return boolean
*/
	function Menu()
	{
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

		unset($_SESSION['CONTROL_CIERRE']['DATOS']);
		$this->salida.= ThemeAbrirTabla('MENÚ CONTROL DE CIERRES','65%');
		$this->Encabezado();
		$this->salida.="<br><table border=\"0\"    align=\"center\"   width=\"60%\">";
		$this->salida.="<tr>";
		$this->salida.="<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >&nbsp;</td>";
		$this->salida.="</tr>";
		$ax=ModuloGetURL('app','Control_Cierre','user','BusquedaCajasHoy');
		$ac=ModuloGetURL('app','Control_Cierre','user','BuscarArchivo');
		$this->salida.="<tr>";
		$estilo='modulo_list_oscuro';
		$this->salida.="<td  colspan=\"2\"  class=\"$estilo\"  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');  align=\"center\"><a title='AQUI ENCONTRARAS INFORMACION DE LOS RECIBOS Y FACTURAS RECIBIDAS HASTA EL MOMENTO, Y REALIZAR IMPRESIONES !' href=\"$ax\">PENDIENTES X CIERRES</a>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr>";
		$estilo='modulo_list_claro';
		$this->salida.="<td  colspan=\"2\"  class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\"><a title='AQUI SE ENCONTARA TODA LA INFORMACIÓN DE CIERRES PASADOS DE LOS DIFERENTES TIPOS DE CAJAS' href=\"$ac\">ARCHIVO DE CIERRES</a>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
		$action2=ModuloGetURL('app','Control_Cierre','user','main');
		$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table><br>";
		$this->salida.= ThemeCerrarTabla();
		return true;
	}

		/*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
  function Encabezado()
	{
		$this->salida .= "<br><table  border=\"0\" class=\"modulo_table_title\" width=\"80%\" align=\"center\" >";
		$this->salida .= " <tr class=\"modulo_table_title\">";
		$this->salida .= " <td>EMPRESA</td>";
		$this->salida .= " <td>CENTRO UTILIDAD</td>";
		$this->salida .= " <td>MODULO</td>";
		$this->salida .= " </tr>";
		$this->salida .= " <tr align=\"center\">";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['CONTROL_CIERRE']['NOM_EMP']."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['CONTROL_CIERRE']['NOM_CENTRO']."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\" >CONTROL DE CIERRES</td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		return true;
	}
	
		/*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
  function User_Encabezado($uid,$caja)
	{
			$dat=$this->TraerUsuario($uid);
			$this->salida .= "              <br><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"50%\" align=\"center\">";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\" width=\"20%\">&nbsp;CAJA : </td><td class=\"modulo_list_claro\" align=\"left\">".$caja."</td></tr>";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\" class=\"label\" width=\"40%\" align=\"left\">NOMBRE: </td><td class=\"modulo_list_claro\" align=\"left\">".$dat[usuario_id]."&nbsp; - &nbsp;".$dat[nombre]."</td></tr>";
			$this->salida .= " </table>";         
			return true;
	}
	
	/*
	* funcion q revisa los cierres de caja que se van a efectuar el dia de hoy 
	* solo como informativo, se puede escoger el tipo de caja que se va a buscar
	* $sw es para determinar si es facturadora o otra caja
	*/
	function BusquedaCajasHoy($vect='',$sw)
	{ 

				$this->salida.= ThemeAbrirTabla("CONTROL DE CIERRES PENDIENTES POR CONFIRMAR.");
				$this->Encabezado();
				$_SESSION['CONTROL_CIERRE']['VECT_FACT_HOY']=$vect;
				$accion=ModuloGetURL('app','Control_Cierre','user','Busqueda');
				if($this->uno == 1)
				{
						$this->salida .= "<BR><BR><table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= $this->SetStyle("MensajeError");
						$this->salida .= "      </table><br>";
						$this->uno="";
				}
/*				$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">CAJAS</td>";

				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
				$this->salida.="<option value = '1' >Cajas Facturadora</option>";
				$this->salida.="<option value = '2' selected>Cajas Hospitalarias</option>";
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"10%\">DEPARTAMENTO:</td>";
				$departamento=$this->Departamentos();
				$this->salida .= "<td  width=\"6%\" align=\"center\"><select name=\"departamento\" class=\"select\">";
				$this->salida .=" <option value=/a/ selected>Todos</option>";
				foreach($departamento as $value=>$titulo)
				{
					if($value==$Dpto){
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else {
						$this->salida .=" <option value=\"$value\" >$titulo</option>";
					}
				}
				$this->salida .= "         </select></td>";
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				//$this->salida.="</form>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				
				switch($sw)
				{
					case 1:
					{
						$nom='Caja Facturadora';
						break;	
					}
					case 2:
					{
						$nom='Caja Hospitalaria';
						break;	
					}
				
				}
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$nom."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";
				$this->salida.="</form>";*/
				$vect=$this->BusquedaHosp();
				if(!empty($vect) AND $vect !='show')
				{
					$sw=2;
					$_SESSION['CIERRE']['CONFIRMACION']=$vect;
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					$efectivo=$abono=$cheque=$tarjeta=$entregado=$devolucion=$cont=0;
					//$acc2=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array("Caja"=>$Caja,"Empresa"=>$Empresa,"dpto"=>$dpto,"CentroUtilidad"=>$CentroUtilidad,'arreglo'=>$tipo,"TipoCuenta"=>$TipoCuenta,"CU"=>$CU,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
					//$this->salida .= "            <form name=\"formalistarr2\" action=\"$acc2\" method=\"post\">";
					$acc1=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array('criterio'=>$sw));
					$this->salida .= "            <form name=\"formalistarr\" action=\"$acc1\" method=\"post\">";
					$this->salida .= "  <BR><BR><fieldset><legend class=\"field\">CIERRE PENDIENTES POR CONFIRMAR - CAJAS HOSPITALARIAS</legend>";
					for($i=0;$i<sizeof($vect);)
					{
							$descriptivo_caja=str_replace("CAJA","",$vect[$i][descripcion]);
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
							$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"9\">CAJA &nbsp;".$descriptivo_caja."&nbsp;CIERRES PENDIENTES</td></tr>";
							$this->salida.="<tr class=\"modulo_table_list_title\">";
							$this->salida.="  <td width=\"35%\">Usuario</td>";//td width=\"40%\"
							$this->salida.="  <td width=\"12%\">Fecha/hora</td>";//td width=\"10%\"
							$this->salida.="  <td width=\"10%\">T Efectivo</td>";
							$this->salida.="  <td width=\"8%\">T Cheque</td>";
							$this->salida.="  <td width=\"8%\">T Tarjetas</td>";
							$this->salida.="  <td width=\"8%\">T Bonos</td>";
							$this->salida.="  <td width=\"8%\">T Dev</td>";
							$this->salida.="  <td width=\"10%\" >T Entregado</td>";
							$this->salida.="  <td width=\"3%\" >&nbsp;</td>";
							$this->salida.="</tr>";
							$k=$i;
							while($vect[$i][descripcion]==$vect[$k][descripcion])
							{
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
/*								$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
								$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";*/
								if($sw==1)
								{	//*****************FACTURAS**************************
									$arr=$this->TraerTotales($vect[$k][usuario_id],$vect[$k][caja_id],$vect[$k][cierre_de_caja_id]);
									$_SESSION['CIERRE']['CONFIRMACION_CIERRE_DE_CAJA']=$arr;
								}	//*****************FACTURAS**************************
								elseif($sw==2)
								{	//*****************RECIBOS DE CAJA*******************
									//$arr=$this->TraerTotalesRecibos($vect[$k][usuario_id],$vect[$k][caja_id]);
									$arr=$this->TraerTotalesRecibos($vect[$k][cierre_de_caja_id]);
									$_SESSION['CIERRE']['CONFIRMACION_CIERRE_DE_CAJA']=$arr;
									//*****************RECIBOS DE CAJA*******************
									//*****************DEVOLUCIONES*******************
									//$arrdev=$this->TraerTotalesDevoluciones($vect[$k][usuario_id],$vect[$k][caja_id]);
								}	//*****************DEVOLUCIONES*******************
								for($n=0;$n<sizeof($arr);$n++)
								{
									$efectivo=$efectivo+$arr[$n][total_efectivo];
									$cheque=$cheque+$arr[$n][total_cheques];
									$tarjeta=$tarjeta+$arr[$n][total_tarjetas];
									$bonos=$bonos+$arr[$n][total_bonos];
									$devolucion=$devolucion+$arr[$n][total_devolucion];
									$entregado=$entregado+$arr[$n][entrega_efectivo];
									$fecha=$arr[$n][fecha_registro];

								//}
								$cont=$cont+sizeof($arr);
								$te=$te+$efectivo;
								$che=$che+$cheque;
								$tar=$tar+$tarjeta;
								$totaldev=$totaldev+$devolucion;

								if($sw==1)
								{
									$accion=ModuloGetURL('app','Control_Cierre','user','RevisarFacturasHoy',
									array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>1,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id],'dpto'=>$vect[$k][departamento]));
								}
								elseif($sw==2)
								{
									$accion=ModuloGetURL('app','Control_Cierre','user','RevisarRecibosHoy',
									array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>2,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id]));
								}
								$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
								$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$arr[$n][des]."'><label class='label'>".$arr[$n][usuario_id]."&nbsp;-&nbsp;".$arr[$n][nombre]."</label></div></td>";
								$fecha1=explode(' ',$fecha);
								$hora=explode(':',$fecha1[1]);
								$this->salida.="  <td align=\"center\">".$fecha1[0]." ".$hora[0].":".$hora[1]."</td>";
		//					$this->salida.="  <td><a href='$accion'>[".sizeof($arr)."]</a></td>";
								$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($efectivo)."</td>";
								$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($cheque)."</td>";
								$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($tarjeta)."</td>";
								$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($bonos)."</td>";
								$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($devolucion)."</td>";
								$this->salida.="  <td align=\"right\"><label class='label'>$&nbsp;".FormatoValor($entregado)."</label></td>";
								$accion=ModuloGetURL('app','Control_Cierre','user','FrmConfirmarCierre',array('criterio'=>$sw,'cierre_de_caja'=>$arr[$n][cierre_de_caja_id]));
								$this->salida.="<td align=\"center\"><a href=\"$accion\"><img TITLE='CONFIRMAR CIERRE'  src=\"". GetThemePath() ."/images/cargos.png\" border='0' width=20 height=20 ></a></td>";
								//$this->salida.="  <td align=\"center\"><input type=\"checkbox\" name=\"".$arr[$n][cierre_de_caja_id]."\" value=\"".$arr[$n][cierre_de_caja_id]."\"></td>";
								$this->salida.="</tr>";
								if(!empty($arr[$n][observaciones]))
								{
								$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');s>";
								$this->salida.=" <td align=\"right\" width=\"15%\"><label class='label_mark'>Observaciones:</label>
								</td><td width=\"85%\" colspan=\"7\"><label class='label'>".$arr[$n][observaciones]."</label></td>";
								}
								$this->salida.="</tr>";
								//$totaldevabono=$totaldevabono+($abono-$arrdev[0][total_devolucion]);
								unset($devolucion);
								unset($cheque);
								unset($tarjeta);
								unset($efectivo);
								unset($bonos);
								unset($entregado);
							}
								$k++;
						}
		/*					$this->salida.="<tr class=\"$estilo\" >";
							$this->salida.=" <td align=\"right\"><label class='label_mark'>Totales Caja :</label>
							</td><td><label class='label_mark'>$cont</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($te)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($che)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tar)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tbon)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($totaldev)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta-$totaldev)."</label></td>";*/
		/*					$this->salida.="<tr class=\"$estilo\" >";
							$this->salida.=" <td colspan='9' align=\"left\"><label class='label_mark'>Estado:</label>";
							$this->salida.=" <img TITLE='DATOS DE CIERRES SIN CONFIRMAR'  src=\"". GetThemePath() ."/images/caja_abierta.png\" border='0' width=32 height=32 ></td>";
							$this->salida.="</tr>";*/
		//
						//$this->salida.="<tr class=\"modulo_list_claro\" >";
							//$acc=ModuloGetURL('app','CajaGeneral','user','InsertarC',array('Caja'=>$Caja,'Empresa'=>$Empresa,'CentroUtilidad'=>$CentroUtilidad,'arreglo'=>$tipo,'CU'=>$CU,'TipoCuenta'=>$TipoCuenta,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
							//$accion=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array("Caja"=>$Caja,"Empresa"=>$Empresa,"dpto"=>$dpto,"CentroUtilidad"=>$CentroUtilidad,'arreglo'=>$tipo,"TipoCuenta"=>$TipoCuenta,"CU"=>$CU,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
							//$this->salida.=" <td colspan='8' align=\"center\"><label class='label_mark'>Cerrar:&nbsp&nbsp</label>";
							//$this->salida.="<a href=\"$accion\"><img TITLE='CONFIRMAR CIERRE'  src=\"". GetThemePath() ."/images/entregabolsa.png\" border='0' width=20 height=20 ></a></td>";
							//$this->salida.="</tr>";
		//
								unset($cont);
							unset($te);
							unset($ta);
							unset($tar);
							unset($che);
							unset($tbon);
							//$this->salida.="</tr>";
							$i=$k;
							$this->salida.="</table><br>";
					}
					$this->salida .= "  </fieldset>";
/*				if(!empty($vect))
				{*/
/*							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\">";
							$this->salida.="<tr class=\"modulo_list_oscuro\">";
							$this->salida .= "<td width=\"35%\" align=\"center\" class=\"modulo_list_claro\"\" >Observaciones :</td>";
							$this->salida .= "<td align=\"center\" class=\"modulo_list_claro\" ><textarea class=\"textarea\"  name=\"observa\"  rows=\"5\"  cols=\"45\" >".$_REQUEST['observa']."</textarea></td>";
							$this->salida.="</tr>";                                                                                                                  
							$this->salida.="<tr>";
							$this->salida.= " <td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"CONFIRMAR CIERRE\"></form></td>";
							$this->salida.="</tr>";                                                                                                                  
							$this->salida.="</table>";*/
							$this->salida.="</form>";
				//}
				}
;
				//*****************
				if($vect !='show')
				{ 
					$vect=$this->BusquedaFact();
					if(!empty($vect) AND $vect !='show')
					{
						$sw=1;
						$_SESSION['CIERRE']['CONFIRMACION']=$vect;
						$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
	
						$mostrar ="\n<script language='javascript'>\n";
						$mostrar.="function mOvr(src,clrOver) {;\n";
						$mostrar.="src.style.background = clrOver;\n";
						$mostrar.="}\n";
	
						$mostrar.="function mOut(src,clrIn) {\n";
						$mostrar.="src.style.background = clrIn;\n";
						$mostrar.="}\n";
						$mostrar.="</script>\n";
						$this->salida .="$mostrar";
						$efectivo=$abono=$cheque=$tarjeta=$entregado=$devolucion=$cont=0;
						//$acc2=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array("Caja"=>$Caja,"Empresa"=>$Empresa,"dpto"=>$dpto,"CentroUtilidad"=>$CentroUtilidad,'arreglo'=>$tipo,"TipoCuenta"=>$TipoCuenta,"CU"=>$CU,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
						//$this->salida .= "            <form name=\"formalistarr2\" action=\"$acc2\" method=\"post\">";
						$acc1=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array('criterio'=>$sw));
						$this->salida .= "            <form name=\"formalistarr\" action=\"$acc1\" method=\"post\">";
						$this->salida .= "  <BR><fieldset><legend class=\"field\">CIERRE PENDIENTES POR CONFIRMAR - CAJAS RAPIDAS</legend>";
						for($i=0;$i<sizeof($vect);)
						{
								$descriptivo_caja=str_replace("CAJA","",$vect[$i][descripcion]);
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
								$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"8\">CAJA &nbsp;".$descriptivo_caja."&nbsp;CIERRES PENDIENTES</td></tr>";
								$this->salida.="<tr class=\"modulo_table_list_title\">";
								$this->salida.="  <td width=\"27%\">Usuario</td>";//td width=\"40%\"
								$this->salida.="  <td width=\"20%\">Fecha/hora</td>";//td width=\"10%\"
								$this->salida.="  <td width=\"10%\">T Efectivo</td>";
								$this->salida.="  <td width=\"10%\">T Cheque</td>";
								$this->salida.="  <td width=\"10%\">T Tarjetas</td>";
								$this->salida.="  <td width=\"10%\">T Dev</td>";
								$this->salida.="  <td width=\"10%\">T Entregado</td>";
								$this->salida.="  <td width=\"3%\">&nbsp;</td>";
								$this->salida.="</tr>";
								$k=$i;
								while($vect[$i][descripcion]==$vect[$k][descripcion])
								{
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
	/*								$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
									$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";*/
									if($sw==1)
									{	//*****************FACTURAS**************************
										//$arr=$this->TraerTotales($vect[$k][usuario_id],$vect[$k][caja_id],$vect[$k][cierre_de_caja_id]);
										$arr=$this->TraerTotales($vect[$k][cierre_de_caja_id]);
										$_SESSION['CIERRE']['CONFIRMACION_CIERRE_DE_CAJA']=$arr;
									}	//*****************FACTURAS**************************
									elseif($sw==2)
									{	//*****************RECIBOS DE CAJA*******************
										$arr=$this->TraerTotalesRecibos($vect[$k][usuario_id],$vect[$k][caja_id]);
										$_SESSION['CIERRE']['CONFIRMACION_CIERRE_DE_CAJA']=$arr;
										//*****************RECIBOS DE CAJA*******************
										//*****************DEVOLUCIONES*******************
										//$arrdev=$this->TraerTotalesDevoluciones($vect[$k][usuario_id],$vect[$k][caja_id]);
									}	//*****************DEVOLUCIONES*******************
									for($n=0;$n<sizeof($arr);$n++)
									{
										$efectivo=$efectivo+$arr[$n][total_efectivo];
										$cheque=$cheque+$arr[$n][total_cheques];
										$tarjeta=$tarjeta+$arr[$n][total_tarjetas];
										$entregado=$entregado+$arr[$n][entrega_efectivo];
										$devolucion=$devolucion+$arr[$n][total_devolucion];
										$fecha=$arr[$n][fecha_registro];
	
									//}
										$cont=$cont+sizeof($arr);
										$te=$te+$efectivo;
										$che=$che+$cheque;
										$tar=$tar+$tarjeta;
										$totaldev=$totaldev+$devolucion;
		
										if($sw==1)
										{
											$accion=ModuloGetURL('app','Control_Cierre','user','RevisarFacturasHoy',
											array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>1,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id],'dpto'=>$vect[$k][departamento]));
										}
										elseif($sw==2)
										{
											$accion=ModuloGetURL('app','Control_Cierre','user','RevisarRecibosHoy',
											array('descripcion'=>$vect[$k][descripcion],'sw_recibo'=>2,'caja'=>$vect[$k][caja_id],'id'=>$vect[$k][usuario_id]));
										}	
										$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
										$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$arr[$n][des]."'><label class='label'>".$arr[$n][usuario_id]."&nbsp;-&nbsp;".$arr[$n][nombre]."</label></div></td>";
										//$this->salida.="  <td>".$fecha."</td>";
										$fecha1=explode(' ',$fecha);
										$hora=explode(':',$fecha1[1]);
										$this->salida.="  <td align=\"center\">".$fecha1[0]." ".$hora[0].":".$hora[1]."</td>";
				//					$this->salida.="  <td><a href='$accion'>[".sizeof($arr)."]</a></td>";
										$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($efectivo)."</td>";
										$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($cheque)."</td>";
										$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($tarjeta)."</td>";
										$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($devolucion)."</td>";
										$this->salida.="  <td align=\"right\"><label class='label'>$&nbsp;".FormatoValor($entregado)."</label></td>";
										$accion=ModuloGetURL('app','Control_Cierre','user','FrmConfirmarCierre',array('criterio'=>$sw,'cierre_de_caja'=>$arr[$n][cierre_de_caja_id]));
										$this->salida.="<td align=\"center\"><a href=\"$accion\"><img TITLE='CONFIRMAR CIERRE'  src=\"". GetThemePath() ."/images/cargos.png\" border='0' width=20 height=20 ></a></td>";
										//$this->salida.="  <td align=\"center\"><input type=\"checkbox\" name=\"".$arr[$n][cierre_de_caja_id]."\" value=\"".$arr[$n][cierre_de_caja_id]."\"></td>";
										$this->salida.="</tr>";
										if(!empty($arr[$n][observaciones]))
										{
										$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');s>";
										$this->salida.=" <td align=\"right\" width=\"15%\"><label class='label_mark'>Observaciones:</label>
										</td><td width=\"85%\" colspan=\"7\"><label class='label'>".$arr[$n][observaciones]."</label></td>";
										}
										$this->salida.="</tr>";
										//$totaldevabono=$totaldevabono+($abono-$arrdev[0][total_devolucion]);
										unset($devolucion);
										unset($cheque);
										unset($tarjeta);
										unset($efectivo);
										unset($entregado);
									}
									$k++;
							}
			/*					$this->salida.="<tr class=\"$estilo\" >";
								$this->salida.=" <td align=\"right\"><label class='label_mark'>Totales Caja :</label>
								</td><td><label class='label_mark'>$cont</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($te)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($che)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tar)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($tbon)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($totaldev)."</label></td><td><label class='label_mark'>$ &nbsp;".FormatoValor($ta-$totaldev)."</label></td>";*/
			/*					$this->salida.="<tr class=\"$estilo\" >";
								$this->salida.=" <td colspan='9' align=\"left\"><label class='label_mark'>Estado:</label>";
								$this->salida.=" <img TITLE='DATOS DE CIERRES SIN CONFIRMAR'  src=\"". GetThemePath() ."/images/caja_abierta.png\" border='0' width=32 height=32 ></td>";
								$this->salida.="</tr>";*/
			//
							//$this->salida.="<tr class=\"modulo_list_claro\" >";
								//$acc=ModuloGetURL('app','CajaGeneral','user','InsertarC',array('Caja'=>$Caja,'Empresa'=>$Empresa,'CentroUtilidad'=>$CentroUtilidad,'arreglo'=>$tipo,'CU'=>$CU,'TipoCuenta'=>$TipoCuenta,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
								//$accion=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array("Caja"=>$Caja,"Empresa"=>$Empresa,"dpto"=>$dpto,"CentroUtilidad"=>$CentroUtilidad,'arreglo'=>$tipo,"TipoCuenta"=>$TipoCuenta,"CU"=>$CU,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$vec[0][usuario_id],'tefd'=>$tefd));
								//$this->salida.=" <td colspan='8' align=\"center\"><label class='label_mark'>Cerrar:&nbsp&nbsp</label>";
								//$this->salida.="<a href=\"$accion\"><img TITLE='CONFIRMAR CIERRE'  src=\"". GetThemePath() ."/images/entregabolsa.png\" border='0' width=20 height=20 ></a></td>";
								//$this->salida.="</tr>";
			//
								unset($cont);
								unset($te);
								unset($ta);
								unset($tar);
								unset($che);
								unset($tbon);
								//$this->salida.="</tr>";
								$i=$k;
								$this->salida.="</table><br>";
						}
						$this->salida .= "  </fieldset>";
	/*				if(!empty($vect))
					{*/
								$this->salida.="</form>";
					//}
					}
				}
				elseif($vect =='show')
				//else
				{
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "<tr><td  align=\"center\"><label class=label_mark>NO HAY CIERRES PENDIENTES POR CONFIRMAR</label></td></tr>";
					$this->salida.="</table><br>";
				}
				
//**************************************************
//**************************************************
//**************************************************
//TRAER Y MOSTRAR LAS CAJAS QUE NO HAN CERRADO AUN 
// Y/O TIENEN MOVIMIENTO ACTUALMENTE - HOSPITALARIAS
//**************************************************
//**************************************************
//**************************************************
				$sincerrar=$this->TraerUsuariosMov();
				if(!empty($sincerrar))
				{
					$_SESSION['CIERRE']['SIN_CERRAR']=$sincerrar;
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					$efectivo=$devolucion=$cheque=$tarjeta=$cont=0;
					$this->salida .= "  <BR><fieldset><legend class=\"field\">VALORES ACTUALES EN LAS CAJAS</legend>";
					$acc2=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array("Caja"=>$Caja,"Empresa"=>$Empresa,"dpto"=>$dpto,"CentroUtilidad"=>$CentroUtilidad,'arreglo'=>$tipo,"TipoCuenta"=>$TipoCuenta,"CU"=>$CU,'tef'=>$te,'ttar'=>$tar,'tche'=>$che,'tbon'=>$tbon,'ta'=>$ta,'totaldev'=>$totaldev,'user'=>$sincerrar[0][usuario_id],'tefd'=>$tefd));
					$this->salida.="   <form name=\"formalistarr2\" action=\"$acc2\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
					//$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"8\">CAJA &nbsp;".$descriptivo_caja."&nbsp;DOCUMENTOS CUADRADOS SIN CERRAR</td></tr>";
					$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"8\">&nbsp;&nbsp;&nbsp;CAJAS HOSPITALARIAS&nbsp;&nbsp;</td></tr>";
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td width=\"43%\">Usuario/Caja</td>";//td width=\"40%\"
					$this->salida.="  <td width=\"12%\">Fecha/hora</td>";//td width=\"10%\"
					$this->salida.="  <td width=\"8%\">T Efectivo</td>";
					$this->salida.="  <td width=\"8%\">T Cheque</td>";
					$this->salida.="  <td width=\"8%\">T Tarjetas</td>";
					$this->salida.="  <td width=\"8%\">T Bonos</td>";
					$this->salida.="  <td width=\"8%\">T Dev</td>";
					$this->salida.="  <td width=\"10%\" >T Entregado</td>";
					//$this->salida.="  <td width=\"3%\" >&nbsp;</td>";
					$this->salida.="</tr>";
					$sw=2;

					for($i=0;$i<sizeof($sincerrar);$i++)
					{ 
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
							//$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";
							if($sw==1)
							{
								//*****************FACTURAS**************************
								$arr=$this->TraerTotales($sincerrar[$i][usuario_id],$sincerrar[$i][caja_id]);
								//*****************FACTURAS**************************
							}	
							elseif($sw==2)
							{	
								//*****************RECIBOS DE CAJA*******************
								$arr=$this->TraerTotalesMov($sincerrar[$i][fecha],$sincerrar[$i][usuario_id],$sincerrar[$i][caja_id]);
								//*****************RECIBOS DE CAJA*******************
								//*****************DEVOLUCIONES**********************
								//$arr=$sincerrar;
								$arrdev=$this->TraerTotalesDevoluciones($sincerrar[$i][usuario_id],$sincerrar[$i][caja_id]);
								$arrdev1=0;
								for($l=0; $l<sizeof($arrdev);$l++)
								{
									$arrdev1=$arrdev1+$arrdev[$l][total_devolucion];
								}
								//*****************DEVOLUCIONES**********************
							}
							//print_r($arr3); exit;
							$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
							$descripcion=$this->TraerDescripcion($sincerrar[$i][caja_id]);
							$usuario=$this->TraerUltimoUsuario($sincerrar[$i][usuario_id]);
							$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$sincerrar[$i][des]."'><label class='label'>".$usuario[usuario_id]."&nbsp;&nbsp;".$usuario[nombre]."&nbsp;&nbsp;[".$descripcion[descripcion]."]</label></div></td>";
							$fecha=explode(' ',$sincerrar[$i][fecha]);
							$hora=explode(':',$fecha[1]);
							$this->salida.="  <td align=\"center\">".$fecha[0]." ".$hora[0].":".$hora[1]."</td>";
							$entregado=$arr[0][efectivo]+$arr[0][cheques]+$arr[0][tarjetas]+$arr[0][bonos]-$arrdev[0][total_devolucion];
			//			$this->salida.="  <td><a href='$accion'>[".sizeof($arr)."]</a></td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][efectivo])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][cheques])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][tarjetas])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][bonos])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arrdev1)."</td>";
							$this->salida.="  <td align=\"right\"><label class='label'>$&nbsp;".FormatoValor($arr[0][subtotal]-$arrdev1)."</label></td>";
							$this->salida.="</tr>";
					}
						$this->salida.="</table><br>";
				}
//CAJAS FACTURADORAS
				$sincerrar1=$this->TraerUsuariosMovFact();
				if(!empty($sincerrar1))
				{
					//$sincerrar1=$this->TraerUsuariosMovFact();
					$this->salida.="<BR><table  align=\"center\" border=\"0\"  width=\"90%\">";
					//$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"8\">&nbsp;&nbsp;CAJAS FACTURADORAS..&nbsp;&nbsp;&nbsp;</td></tr>";
					$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"7\">&nbsp;&nbsp;CAJAS FACTURADORAS..&nbsp;&nbsp;&nbsp;</td></tr>";
					$this->salida.="<tr class=\"modulo_table_list_title\">";
					$this->salida.="  <td width=\"43%\">Usuario/Caja</td>";//td width=\"40%\"
					$this->salida.="  <td width=\"12%\">Fecha/hora</td>";//td width=\"10%\"
					$this->salida.="  <td width=\"8%\">T Efectivo</td>";
					$this->salida.="  <td width=\"8%\">T Cheque</td>";
					$this->salida.="  <td width=\"8%\">T Tarjetas</td>";
					$this->salida.="  <td width=\"8%\">T Bonos</td>";
					//$this->salida.="  <td width=\"8%\">T Dev</td>";
					$this->salida.="  <td width=\"10%\" >T Entregado</td>";
					//$this->salida.="  <td width=\"3%\" >&nbsp;</td>";
					$this->salida.="</tr>";
					$sw=1;

					for($i=0;$i<sizeof($sincerrar1);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
							//$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";
							if($sw==1)
							{	//*****************FACTURAS**************************
								$arr=$this->TraerTotalesMovFact($sincerrar1[$i][usuario_id],$sincerrar1[$i][caja_id]);
								//*****************FACTURAS**************************
								//*****************DEVOLUCIONES*******************
								//$arrdevfact=$this->TraerTotalesDevolucionesFact($sincerrar1[$i][fecha],$sincerrar1[$i][usuario_id],$sincerrar1[$i][caja_id]);
								//*****************DEVOLUCIONES*******************
							}	
							elseif($sw==2)
							{	//*****************RECIBOS DE CAJA*******************
								$arr=$this->TraerTotalesMov($sincerrar[$i][caja_id]);
								//*****************RECIBOS DE CAJA*******************
							}	
							$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
							$descripcion=$this->TraerDescripcionFact($sincerrar1[$i][caja_id]);
							$usuario=$this->TraerUltimoUsuario($sincerrar1[$i][usuario_id]);
							$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$descripcion[$i][descripcion]."'><label class='label'>".$usuario[usuario_id]."&nbsp;&nbsp;".$usuario[nombre]."&nbsp;&nbsp;[".$descripcion[descripcion]."]</label></div></td>";
							$fecha=explode(' ',$sincerrar1[$i][fecha]);
							$hora=explode(':',$fecha[1]);
							$this->salida.="  <td align=\"center\">".$fecha[0]." ".$hora[0].":".$hora[1]."</td>";
							//$this->salida.="  <td>".$fecha[0]." ".$fecha[1]."</td>";
							$entregado=$arr[0][efectivo]+$arr[0][cheques]+$arr[0][tarjetas]-$arrdev[0][total_devolucion];
			//			$this->salida.="  <td><a href='$accion'>[".sizeof($arr)."]</a></td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][efectivo])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][cheques])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][tarjetas])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arr[0][bonos])."</td>";
							//$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($arrdevfact[0][total_devolucion])."</td>";
							$this->salida.="  <td align=\"right\"><label class='label'>$&nbsp;".FormatoValor($arr[0][subtotal]-$arrdevfact[0][total_devolucion])."</label></td>";
							$this->salida.="</tr>";
					}
						$this->salida.="</table>";
				}
						//CAJAS CONCEPTOS
						$sincerrar=$this->TraerUsuariosMovConceptos();
						if(!empty($sincerrar))
						{
							//$sincerrar=$this->TraerDetalleMovConceptos($sincerrar2[fecha]);
							if(empty($sincerrar1))
							{
								$this->salida.="<BR><table  align=\"center\" border=\"0\"  width=\"90%\">";
								//$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"8\">CAJA &nbsp;".$descriptivo_caja."&nbsp;DOCUMENTOS CUADRADOS SIN CERRAR</td></tr>";
								$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"7\">&nbsp;&nbsp;&nbsp;CAJA CONCEPTOS&nbsp;&nbsp;</td></tr>";
								$this->salida.="<tr class=\"modulo_table_list_title\">";
								$this->salida.="  <td width=\"45%\">Usuario/Caja</td>";//td width=\"40%\"
								$this->salida.="  <td width=\"12%\">Fecha/hora</td>";//td width=\"10%\"
								$this->salida.="  <td width=\"8%\">T Efectivo</td>";
								$this->salida.="  <td width=\"8%\">T Cheque</td>";
								$this->salida.="  <td width=\"8%\">T Tarjetas</td>";
								$this->salida.="  <td width=\"8%\">T Bonos</td>";
								//$this->salida.="  <td width=\"8%\">T Dev</td>";
								$this->salida.="  <td width=\"10%\" >T Entregado</td>";
								//$this->salida.="  <td width=\"3%\" >&nbsp;</td>";
								$this->salida.="</tr>";
							}
							else
								$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
						
							for($i=0;$i<sizeof($sincerrar);$i++)
							{
									if( $i % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
									//$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";
									//*****************FACTURAS**************************
									$arr=$this->TraerTotalesMovConceptos($sincerrar[$i][usuario_id],$sincerrar[$i][caja_id]);
									//*****************FACTURAS**************************
											$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
											$descripcion=$this->TraerDescripcionConceptos($sincerrar[$i][caja_id]);
											$usuario=$this->TraerUltimoUsuario($sincerrar[$i][usuario_id]);
											$this->salida.="  <td align=\"left\" width=\"43%\"><div title='Descripcion : ".$descripcion[descripcion]."'><label class='label'>".$sincerrar[$i][usuario_id]."&nbsp;&nbsp;".$usuario[nombre]."&nbsp;&nbsp;[".$descripcion[descripcion]."]</label></div></td>";
											$fecha=explode(' ',$sincerrar[$i][fecha]);
											$hora=explode(':',$fecha[1]);
											$this->salida.="  <td align=\"center\" width=\"12%\">".$fecha[0]." ".$hora[0].":".$hora[1]."</td>";
											//$this->salida.="  <td>".$fecha[0]." ".$fecha[1]."</td>";
											//$this->salida.="  <td><a href='$accion'>[".sizeof($arr)."]</a></td>";
											$this->salida.="  <td align=\"right\" width=\"8%\">$&nbsp;".FormatoValor($arr[0][efectivo])."</td>";
											$this->salida.="  <td align=\"right\" width=\"8%\">$&nbsp;".FormatoValor($arr[0][cheques])."</td>";
											$this->salida.="  <td align=\"right\" width=\"8%\">$&nbsp;".FormatoValor($arr[0][tarjetas])."</td>";
											$this->salida.="  <td align=\"right\" width=\"8%\">$&nbsp;".FormatoValor($arr[0][bonos])."</td>";
											//$this->salida.="  <td align=\"right\" width=\"8%\">$&nbsp;".FormatoValor($arrdev[0][total_devolucion])."</td>";
											$this->salida.="  <td align=\"right\" width=\"10%\"><label class='label'>$&nbsp;".FormatoValor($arr[0][subtotal])."</label></td>";
											$this->salida.="</tr>";
							}
								$this->salida.="</table>";
						}

//
					$this->salida .= "  </fieldset>";
//**************************************************
//**************************************************
//**************************************************
//FIN TRAER Y MOSTRAR LAS CAJAS QUE NO HAN CERRADO AUN 
// Y/O TIENEN MOVIMIENTO ACTUALMENTE
//**************************************************
//**************************************************
//**************************************************
				$this->salida.="</form>";

				/**Parte de volver**/
				$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
				$action2=ModuloGetURL('app','Control_Cierre','user','Menu');
				//$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
				$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
				$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida .= "</tr>";
				$this->salida.="</table><br>";
				$this->salida.= ThemeCerrarTabla();
				return true;
	}
	
	
	function FrmConfirmarCierre()
	{        
				$sw=$_REQUEST['criterio'];
				$cierre_de_caja=$_REQUEST['cierre_de_caja'];
				$vect=$this->BusquedaCierreConfir($cierre_de_caja,$sw);
				$this->salida= ThemeAbrirTabla("CONTROL DE CIERRES PENDIENTES POR CONFIRMAR..");
				$this->Encabezado();
				$accion2=ModuloGetURL('app','Control_Cierre','user','InsertarConfirmacionCierre',array('criterio'=>$sw,'cierre_de_caja'=>$cierre_de_caja));
//
	        if($this->uno == 1)
        {
            $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "      </table><br>";
						$this->uno="";
        }

				$this->salida .= "  <fieldset><legend class=\"field\">CIERRE PENDIENTE POR CONFIRMAR</legend>";
					for($i=0;$i<sizeof($vect);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$descriptivo_caja=str_replace("CAJA","",$vect[$i][descripcion]);
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
							$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"8\">CAJA &nbsp;".$descriptivo_caja."&nbsp;CIERRE A CONFIRMAR</td></tr>";
							$this->salida.="<tr class=\"modulo_table_list_title\">";
							$this->salida.="  <td width=\"27%\">Usuario</td>";//td width=\"40%\"
							$this->salida.="  <td width=\"20%\">Fecha</td>";//td width=\"10%\"
							$this->salida.="  <td width=\"10%\">T Efectivo</td>";
							$this->salida.="  <td width=\"10%\">T Cheque</td>";
							$this->salida.="  <td width=\"10%\">T Tarjetas</td>";
							$this->salida.="  <td width=\"10%\">T Bonos</td>";
							$this->salida.="  <td width=\"10%\">T Dev</td>";
							$this->salida.="  <td width=\"10%\" >T Entregado</td>";
							$this->salida.="</tr>";
							$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
							$this->salida.="  <td align=\"left\"><div title='Descripcion : ".$vect[$i][des]."'><label class='label'>".$vect[$i][usuario_id]."&nbsp;-&nbsp;".$vect[$i][nombre]."</label></div></td>";
							$fecha=explode(' ',$vect[$i][fecha_registro]);
							$this->salida.="  <td>".$fecha[0]."</td>";
	//					$this->salida.="  <td><a href='$accion'>[".sizeof($arr)."]</a></td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($vect[$i][total_efectivo])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($vect[$i][total_cheques])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($vect[$i][total_tarjetas])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($vect[$i][total_bonos])."</td>";
							$this->salida.="  <td align=\"right\">$&nbsp;".FormatoValor($vect[$i][total_devolucion])."</td>";
							$this->salida.="  <td align=\"right\"><label class='label'>$&nbsp;".FormatoValor($vect[$i][entrega_efectivo])."</label></td>";
							$this->salida.="</tr>";
							//$_POST['valorrecibido']=$vect[$i][entrega_efectivo];
							$_SESSION['CONTROL']['ENTREGA']=$vect[$i][entrega_efectivo];
							if(!empty($vect[$i][observaciones]))
							{
							$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');s>";
							$this->salida.=" <td align=\"right\" width=\"15%\"><label class='label_mark'>Observaciones:</label>
							</td><td width=\"85%\" colspan=\"7\"><label class='label'>".$vect[$i][observaciones]."</label></td>";
							}
							$this->salida.="</tr>";
							//$totaldevabono=$totaldevabono+($abono-$arrdev[0][total_devolucion]);
							$this->salida.="</table><br>";
					}
					$this->salida .= "  </fieldset>";

//
				$this->salida .= " <form name=\"forma\" action=\"$accion2\" method=\"post\">";
				$this->salida .="<br><table  align=\"center\" border=\"0\"  width=\"50%\">";
				$this->salida .="<tr class=\"modulo_list_oscuro\">";
				$this->salida .= "<td width=\"35%\" align=\"center\" class=\"modulo_list_claro\" ><label class=\"".$this->SetStyle("valorrecibido")."\">Valor Recibido :</label></td>";
				$this->salida .= "<td align=\"left\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"valorrecibido\" value=\"".$_POST['valorrecibido']."\" maxlength=\"30\" size=\"15\"></td>";
				$this->salida .="</tr>";                                                                                                                  
				$this->salida .="<tr class=\"modulo_list_oscuro\">";
				$this->salida .= "<td width=\"35%\" align=\"center\" class=\"modulo_list_claro\"><label class=\"".$this->SetStyle("observa")."\">Observaciones :</label></td>";
				$this->salida .= "<td align=\"center\" class=\"modulo_list_claro\" ><textarea class=\"textarea\"  name=\"observa\"  rows=\"5\"  cols=\"45\" >".$_REQUEST['observa']."</textarea></td>";
				$this->salida .="</tr>";                                                                                                                  
				$this->salida .="<tr>";
				$this->salida .= "<td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"CONFIRMAR CIERRE\">";
				$this->salida .= "</form>";
				$action1=ModuloGetURL('app','Control_Cierre','user','BusquedaCajasHoy');
				//$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
				$this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
				$this->salida .= "   <input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida .= "</tr>";
				$this->salida .="</table>";
				$this->salida .= ThemeCerrarTabla();
				return true;

	}

	/**
	*
	*  funcion q revisa las facturas que tiene el paciente en el dia actual.
	**/

	function RevisarFacturasHoy($id,$caja,$sw,$dpto,$caja_des)
	{
			//unset($_SESSION['CONTROL_CIERRE']['DATOS']);

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
			
					if(!$cierre)
					{
						$id=$_REQUEST['id'];
						$caja=$_REQUEST['caja'];
						$sw=$_REQUEST['sw_recibo'];
						$dpto=$_REQUEST['dpto'];
						$caja_des=$_REQUEST['descripcion'];
					}

					if($sw==1)
					{$d="FACTURAS DEL CIERRE"; }
					else{$d="RECIBOS DE CAJA DEL CIERRE";}
					$this->salida.= ThemeAbrirTabla($d);
					$this->Encabezado();
					//cambiar cuando sea tambien control de recibos de caja.//OJO CAMBIAR
					$go_to_url=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
					array('actual'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));

					$this->User_Encabezado($id,$caja_des);
					$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
					if($sw==1){$x='Factura No';}else{$x='Recibo No';}
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='10'>&nbsp;</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">$x</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					$this->salida.="  <td >Paciente</td>";
					$this->salida.="  <td >Total Efectivo</td>";
					$this->salida.="  <td >Total Cheque</td>";
					$this->salida.="  <td >Total Tarjetas</td>";
					$this->salida.="  <td >Total Bonos</td>";

					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}
					$this->salida.="  <td >Sub Total</td>";
					$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					//si son facturas de sos colocamos este campo
					if($sw==1)
					{
						$this->salida.="  <td >Vista</td>";
					}
					$this->salida.="</tr>";

					if($sw==1)
					{$vec=$this->GetFacturasActuales($caja,$id,$dpto);$reporte = new GetReports();}

						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
						for($i=0;$i<sizeof($vec);$i++)
						{
										$rec=$vec[$i][recibo_caja];
										$pre=$vec[$i][prefijo];
										$fech=$vec[$i][fecha_ingcaja];
										$cajadesc=$vec[$i][caja];
										$ef=$vec[$i][total_efectivo];
										$che=$vec[$i][total_cheques];
										$tar=$vec[$i][total_tarjetas];
										$bon=$vec[$i][total_bonos];
										$su=$vec[$i][suma];
										$arreglo[$i]=$vec[$i][caja_id];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
										$this->salida.="  <td>".$pre."-".$rec."</td>";
										$this->salida.="  <td>$fech</td>";

										$this->salida.="  <td>".$this->TraerPaciente($rec,$pre)."</td>";
										$this->salida.="  <td>".FormatoValor($ef)."</td>";
										$this->salida.="  <td>".FormatoValor($che)."</td>";
										$this->salida.="  <td>".FormatoValor($tar)."</td>";
										$this->salida.="  <td>".FormatoValor($bon)."</td>";

										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}
										$this->salida.="  <td>".FormatoValor($su)."</td>";
										$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][numerodecuenta]."^".$vec[$i][recibo_caja]."^".$vec[$i][prefijo]."></td>";
											//este caso es solo para sos..
										if($sw==1)
										{
										    //echo "<br>".$vec[$i][numerodecuenta];

												$this->salida .= $reporte->GetJavaReport('app','CajaGeneral','Factura',
												array('sw_copia'=>TRUE,'cuenta'=>$vec[$i][numerodecuenta],'switche_emp'=>0),array('rpt_dir'=>'cache','rpt_name'=>'recibo'.$vec[$i][numerodecuenta],'rpt_rewrite'=>TRUE));
												$funcion=$reporte->GetJavaFunction();
												$this->salida .= "<td align=\"center\"><a href=\"javascript:$funcion\"><b>PDF</b></a></td>\n";
										}

										$subT=$subT+$su;
										$tef=$tef+$ef;
										$tche=$tche+$che;
										$ttar=$ttar+$tar;
										$tbon=$tbon+$bon;
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}
										$this->salida.="</tr>";
						}
									if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
									$this->salida.="<tr>";
									$moneda="$ ";
									$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
									if($_SESSION['CAJA']['CIERRE']['DEPTO'])
									{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
									$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
									$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subT)."</td>";
									$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
									$this->salida.="</tr>";

									

					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Imprimir\"></form></td>";
					$action2=ModuloGetURL('app','Control_Cierre','user','IrListadoCierre',array('sw_recibo'=>$sw));
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.= ThemeCerrarTabla();
					return true;

	}



	/**
	*
	*  funcion q revisa los recibos de caja que tiene el paciente en el dia actual.
	**/

	function RevisarRecibosHoy($id,$caja,$sw,$dpto,$caja_des)
	{
			//unset($_SESSION['CONTROL_CIERRE']['DATOS']);

					if(!$id)
					{ 
						$id=$_REQUEST['id'];
						$caja=$_REQUEST['caja'];
						$sw=$_REQUEST['sw_recibo'];
						$caja_des=$_REQUEST['descripcion'];
					}
					$RUTA = $_ROOT ."cache/Recibo".UserGetUID().".pdf";
                    //$RUTA = $_ROOT ."classes/classbuscador/buscador.php?tipo=$tipo";
					$DIR="printer.php?ruta=$RUTA";
					$RUTA1= GetBaseURL() . $DIR;
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="var rem=\"\";\n";
					$mostrar.="  function abreVentana(){\n";
					$mostrar.="    var nombre=\"\"\n";
					$mostrar.="    var url2=\"\"\n";
					$mostrar.="    var str=\"\"\n";
					$mostrar.="    var width=\"400\"\n";
					$mostrar.="    var height=\"300\"\n";
					$mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar.="    var url2 ='$RUTA1';\n";
					$mostrar.="    rem = window.open(url2, nombre, str)};\n";
					
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
		
					if($_SESSION['CAJA']['PARAM']=="ShowReport") 
					{
						$this->salida.="<BODY onload=abreVentana();>";
						unset($_SESSION['CAJA']['PARAM']);
					}

					if($sw==2){$d="RECIBOS DE CAJA DEL CIERRE";}
					$this->salida.= ThemeAbrirTabla($d);
					$this->Encabezado();
					//cambiar cuando sea tambien control de recibos de caja.//OJO CAMBIAR
					$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes_Pos_Hosp',
					array('actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));

					$this->User_Encabezado($id,$caja_des);
					$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
					if($sw==1){$x='Factura No';}else{$x='Recibo No';}
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='9'>&nbsp;</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">$x</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					$this->salida.="  <td >Paciente</td>";
					$this->salida.="  <td >Total Efectivo</td>";
					$this->salida.="  <td >Total Cheque</td>";
					$this->salida.="  <td >Total Tarjetas</td>";

					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}
					$this->salida.="  <td >Sub Total</td>";
					$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					$this->salida.="  <td ></td>";
					$this->salida.="</tr>";

					if($sw==2)
					{ 
						$vec=$this->TraerRecibos($caja,$id);
						$dev=$this->TraerDevoluciones($caja,$id);
					}


						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
						for($i=0;$i<sizeof($vec);$i++)
						{
							$rec=$vec[$i][recibo_caja];
							$pre=$vec[$i][prefijo];
							$fech=$vec[$i][fecha_ingcaja];
							$cajadesc=$vec[$i][caja];
							$ef=$vec[$i][total_efectivo];
							$che=$vec[$i][total_cheques];
							$tar=$vec[$i][total_tarjetas];
							$su=$vec[$i][suma];
							$arreglo[$i]=$vec[$i][caja_id];
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" 	onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
							$this->salida.="  <td>".$pre."-".$rec."</td>";
							$this->salida.="  <td>$fech</td>";

							$this->salida.="  <td>".$this->TraerPacienteCajaGeneral($rec,$pre,$vec[$i][devolucion_id])."</td>";
							$this->salida.="  <td>".FormatoValor($ef)."</td>";
							$this->salida.="  <td>".FormatoValor($che)."</td>";
							$this->salida.="  <td>".FormatoValor($tar)."</td>";

							if($_SESSION['CAJA']['CIERRE']['DEPTO'])
							{
								$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
								$this->salida.="  <td>".FormatoValor($des)."</td>";
							}
							$this->salida.="  <td>".FormatoValor($su)."</td>";
							$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][recibo_caja]."*".$vec[$i][prefijo]."*".$vec[$i][numerodecuenta]."></td>";
							//^".$vec[$i][factura_fiscal]."^".$vec[$i][prefijo]."
							$subT=$subT+$su;
							$tef=$tef+$ef;
							$tche=$tche+$che;
							$ttar=$ttar+$tar;
							if($_SESSION['CAJA']['CIERRE']['DEPTO'])
							{$tdes=$tdes+$des;}
							$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
							array('recibo'=>$vec[$i][recibo_caja],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'devolucion'=>$vec[$i][devolucion_id]));

							$this->salida.="  <td><a href='$url_pdf'><b>PDF</b></a></td>";
							$this->salida.="</tr>";
						}
							if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
							$this->salida.="<tr>";
							$moneda="$ ";
							$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
							$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
							$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
							$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
							if($_SESSION['CAJA']['CIERRE']['DEPTO'])
							{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
							$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subT)."</td>";
							$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
							$this->salida.="</tr>";
//TABLA DE DEVOLUCIONES
				if($sw==2)
				{
					$vec=$this->TraerDevoluciones($caja,$id);
				}
				if (sizeof($vec)>0)
				{
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='9'>DEVOLUCIONES</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"7%\">Devolución No</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					$this->salida.="  <td >Paciente</td>";
					$this->salida.="  <td >Total Devolución</td>";
					//$this->salida.="  <td >Total Cheque</td>";
					//$this->salida.="  <td >Total Tarjetas</td>";

/*					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}*/
					$this->salida.="  <td >Sub Total</td>";
					//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					$this->salida.="  <td >&nbsp;</td>";
					$this->salida.="  <td >&nbsp;</td>";
					$this->salida.="</tr>";
						$subT=$tef=0;
						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
						for($i=0;$i<sizeof($vec);$i++)
						{
							$rec=$vec[$i][recibo_caja];
							$pre=$vec[$i][prefijo];
							$fech=$vec[$i][fecha_registro];
							$cajadesc=$vec[$i][caja];
							$ef=$vec[$i][total_devolucion];
							//$che=$vec[$i][total_cheques];
							//$tar=$vec[$i][total_tarjetas];
							$su=$vec[$i][suma];
							$arreglo[$i]=$vec[$i][caja_id];
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\" 	onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB'); align=\"center\">";
							$this->salida.="  <td>".$pre."-".$rec."</td>";
							$this->salida.="  <td>$fech</td>";
							//EL PARAMETRO S solo es un switch
							$this->salida.="  <td>".$this->TraerPacienteCajaGeneral($rec,$pre,'s',$vec[$i][usuario_id])."</td>";
							$this->salida.="  <td>".FormatoValor($ef)."</td>";
							//$this->salida.="  <td>".FormatoValor($che)."</td>";
							//$this->salida.="  <td>".FormatoValor($tar)."</td>";

/*							if($_SESSION['CAJA']['CIERRE']['DEPTO'])
							{
								$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
								$this->salida.="  <td>".FormatoValor($des)."</td>";
							}*/
							$this->salida.="  <td>".FormatoValor($su)."</td>";
							$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=opdv[$i] value=".$vec[$i][recibo_caja]."*".$vec[$i][prefijo]."*".$vec[$i][numerodecuenta]."></td>";
							//^".$vec[$i][factura_fiscal]."^".$vec[$i][prefijo]."
							$subT=$subT+$su;
							$tef=$tef+$ef;
/*							$tche=$tche+$che;
							$ttar=$ttar+$tar;*/
/*							if($_SESSION['CAJA']['CIERRE']['DEPTO'])
							{$tdes=$tdes+$des;}*/
							$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
							array('recibo'=>$vec[$i][recibo_caja],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'dv'=>'DV'));

							$this->salida.="  <td><a href='$url_pdf'><b>PDF</b></a></td>";
							$this->salida.="</tr>";
						}
							if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
							$this->salida.="<tr>";
							$moneda="$ ";
							$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
							$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
/*							$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
							$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";*/
							$this->salida.="<td class=\"hc_table_submodulo_list_title\">".FormatoValor($subT)."</td>";
							$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
							//$this->salida.="<td class=\"hc_table_submodulo_list_title\">&nbsp;</td>";
/*							if($_SESSION['CAJA']['CIERRE']['DEPTO'])
							{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}*/
							//$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">&nbsp;</td>";
							$this->salida.="</tr>";
							$this->salida.="</table>";
					}
//FIN TABLE DE DEVOLUCIONES
					//$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Imprimir\"></form></td>";
					$action2=ModuloGetURL('app','Control_Cierre','user','IrListadoCierre',array('sw_recibo'=>$sw));
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.= ThemeCerrarTabla();
					return true;

	}





	/*
	* funcion q revisa en forma de archivador los cierres fiscales o facturas o cierres que se hicieron anteriormente
	*
	*/
	function BuscarArchivo($vect='',$sw)
	{
				$this->salida.= ThemeAbrirTabla("ARCHIVADOR DE CIERRES ANTERIORES..");
				$this->Encabezado();
			
				$accion=ModuloGetURL('app','Control_Cierre','user','ArchivadorBusqueda');
				$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">CAJAS</td>";

				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<select size = 1 name = 'criterio'  class =\"select\">";
				$this->salida.="<option value = '1' >Cajas Facturadora</option>";
				$this->salida.="<option value = '2' selected>Cajas Hospitalarias</option>";
				//$this->salida.="<option value = '3'>Cajas Conceptos</option>";
				$this->salida.="</select>";
				$this->salida.="</td>";
				$this->salida.="<td width=\"10%\">DEPARTAMENTO:</td>";
				$departamento=$this->Departamentos();
				$this->salida .= "<td  width=\"6%\" align=\"center\"><select name=\"departamento\" class=\"select\">";
				$this->salida .=" <option value=/a/ selected>Todos</option>";
				foreach($departamento as $value=>$titulo)
				{
          if($value==$Dpto){
              $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
          }
          else {
             $this->salida .=" <option value=\"$value\" >$titulo</option>";
          }
        }
				$this->salida .= "         </select></td>";
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</form>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";

				switch($sw)
				{
					case 1:
					{
						$nom='Caja Facturadora';
						break;	
					}
					case 2:
					{
						$nom='Caja Hospitalaria';
						break;	
					}				
				}
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$nom."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";

				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

				if(!empty($vect) AND $vect !='show')//CAJAS 
				{
					$_SESSION['CONTROL_CIERRE']['VECT']=$vect;
					$_SESSION['CONTROL_CIERRE']['SW']=$sw;

					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					for($i=0;$i<sizeof($vect);)
					{
							$descriptivo_caja=str_replace("CAJA","",$vect[$i][descripcion]);
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							if($sw==1 || $sw==2)
							$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"3\">CAJA &nbsp;".$descriptivo_caja."</td></tr>";
							else
							$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"2\">CAJA &nbsp;".$descriptivo_caja."</td></tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td  width=\"50%\">Usuario</td>";
							$this->salida.="  <td  width=\"20%\">Ultimo Cierre</td>";
							if($sw==1 || $sw==2)
							$this->salida.="  <td  width=\"10%\">Accion</td>";
							$this->salida.="</tr>";
							$k=$i;

							while($vect[$i][descripcion]==$vect[$k][descripcion])
							{
								if( $k % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
								$this->salida.="  <td align=\"left\"><div title='Descripción : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";

								if($sw=='1')
								{//si esta en 1 es por que es facturadora...
									$fecha1=$this->TraerUltimoCierre($vect[$k][usuario_id],$vect[$k][caja_id]);
									$fecha=$this->TraerUltimoCierre1($fecha1[fecha_confirmacion]);
									$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',array('descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_de_caja_id],'fecha'=>$fecha[fecha_confirmacion],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id],'cuenta_tipo'=>$vect[$k][cuenta_tipo_id]));
									$go_to_archivo=ModuloGetURL('app','Control_Cierre','user','BuscadorCierresAnteriores',array('sw'=>$sw,'descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id],'cuenta_tipo'=>$vect[$k][cuenta_tipo_id]));
								}
								elseif($sw=='2')
								{
									//si esta en 2 es por que es hospitalaria...
									$fecha1=$this->TraerUltimoCierreCajaHospitalaria($vect[$k][usuario_id],$vect[$k][caja_id]);
									$fecha=$this->TraerUltimoCierreCajaHospitalaria2($fecha1[fecha_confirmacion]);
									$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','CierreConfirmado',array('descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>2,'cierre'=>$fecha[cierre_de_caja_id],'fecha'=>$fecha[fecha_confirmacion],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id],'cuenta_tipo'=>$vect[$k][cuenta_tipo_id]));
									$go_to_archivo=ModuloGetURL('app','Control_Cierre','user','BuscadorCierresAnteriores',array('sw'=>$sw,'descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id],'cuenta_tipo'=>$vect[$k][cuenta_tipo_id]));
								}
								elseif($sw=='3')
								{
									//si esta en 3 es por que es caja conceptos...
									$fecha1=$this->TraerUltimoCierreCajaHospitalaria($vect[$k][usuario_id],$vect[$k][caja_id]);
									$fecha=$this->TraerUltimoCierreCajaHospitalaria2($fecha1[fecha_confirmacion]);
									$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','CierreConfirmado',array('descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>3,'cierre'=>$fecha[cierre_de_caja_id],'fecha'=>$fecha[fecha_confirmacion],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id],'cuenta_tipo'=>$vect[$k][cuenta_tipo_id]));
									$go_to_archivo=ModuloGetURL('app','Control_Cierre','user','BuscadorCierresAnteriores',array('sw'=>$sw,'descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id],'cuenta_tipo'=>$vect[$k][cuenta_tipo_id]));
								}

								if($fecha[fecha_confirmacion])
								{
									$this->salida.="  <td align=\"center\" ><a title='Observacion : ".$fecha[observaciones_confirmacion]."' href='$go_to_ultimo'>".$this->FormateoFechaLocal($fecha[fecha_confirmacion])."</a></td>";
								}
								else
								{
									$this->salida.="  <td align=\"center\" >".$this->FormateoFechaLocal($fecha[fecha_registro])."</td>";
								}	
							if($sw==1 || $sw==2)
								$this->salida.="  <td align=\"center\" ><a title='Buscar los cierres anteriores del cajero &nbsp;:&nbsp;".strtolower($vect[$k][nombre])."' href='$go_to_archivo'>Archivo de Cierres</a></td>";
								$this->salida.="</tr>";
								$k++;
							}
							$this->salida.="</tr>";
							$i=$k;
							$this->salida.="</table><br>";
					}
				}
/*				elseif(!empty($vect) AND $vect !='show' AND $sw==1)//CAJAS FACTURADORAS
				{
					$_SESSION['CONTROL_CIERRE']['VECT']=$vect;
					$_SESSION['CONTROL_CIERRE']['SW']=$sw;

					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					for($i=0;$i<sizeof($vect);)
					{
							$descriptivo_caja=str_replace("CAJA","",$vect[$i][descripcion]);
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
							$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"2\">CAJA &nbsp;".$descriptivo_caja."</td></tr>";
							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td  width=\"50%\">Usuario</td>";
							$this->salida.="  <td  width=\"20%\">Último Cierre</td>";
							//$this->salida.="  <td  width=\"10%\">Accion</td>";
							$this->salida.="</tr>";
							$k=$i;

							while($vect[$i][descripcion]==$vect[$k][descripcion])
							{
									if( $k % 2){ $estilo='modulo_list_claro';}
									else {$estilo='modulo_list_oscuro';}
									$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
									$this->salida.="  <td align=\"left\"><div title='Descripción : ".$vect[$k][des]."'>".$vect[$k][usuario_id]."&nbsp;-&nbsp;".$vect[$k][nombre]."</div></td>";

									if($sw=='1')
									{//si esta en 1 es por que es facturadora...
										$fecha=$this->TraerUltimoCierre($vect[$k][usuario_id],$vect[$k][caja_id]);
										$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',array('descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_de_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id]));
										$go_to_archivo=ModuloGetURL('app','Control_Cierre','user','BuscadorCierresAnteriores',array('sw'=>$sw,'descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id]));
									}
									elseif($sw=='2')
									{
										//si esta en 2 es por que es hospitalaria...
										$fecha=$this->TraerUltimoCierreCajaHospitalaria($vect[$k][usuario_id],$vect[$k][caja_id]);
										$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','CierresAnterioresReciboCaja',array('descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>2,'cierre'=>$fecha[cierre_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id]));
										$go_to_archivo=ModuloGetURL('app','Control_Cierre','user','BuscadorCierresAnteriores',array('sw'=>$sw,'descripcion'=>$vect[$k][descripcion],'dpto'=>$vect[$k][departamento],'sw_recibo'=>1,'cierre'=>$fecha[cierre_caja_id],'fecha'=>$fecha[fecha_registro],'id'=>$vect[$k][usuario_id],'caja'=>$vect[$k][caja_id]));
									}

									if($fecha[fecha_registro])
									{
										$this->salida.="  <td align=\"center\" ><a title='Observacion : ".$fecha[ob]."' href='$go_to_ultimo'>".$this->FormateoFechaLocal($fecha[fecha_registro])."</a></td>";
									}
									else
									{
										$this->salida.="  <td align=\"center\" >".$this->FormateoFechaLocal($fecha[fecha_registro])."</td>";
									}	
									//$this->salida.="  <td align=\"center\" ><a title='Buscar los cierres anteriores del cajero &nbsp;:&nbsp;".strtolower($vect[$k][nombre])."' href='$go_to_archivo'>Archivo de Cierres</a></td>";
									$this->salida.="</tr>";
									$k++;
							}
							$this->salida.="</tr>";
							$i=$k;
							$this->salida.="</table><br>";
					}
				}*/
				elseif($vect =='show')
				{
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					//$this->salida .= "<tr><td  align=\"center\"><label class=label_mark>ERROR GRAVE! NO HAY CAJAS EN EL SISTEMA</label></td></tr>";
					$this->salida .= "<tr><td  align=\"center\"><label class=label_mark>NO HAY CIERRES GUARDADOS.</label></td></tr>";
					$this->salida.="</table>";
				}
				
				
				/**Parte de volver**/
				$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
				$action2=ModuloGetURL('app','Control_Cierre','user','Menu');
				$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
				$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
				$this->salida .= "</tr>";
				$this->salida.="</table><br>";
				$this->salida.= ThemeCerrarTabla();
				return true;
	}
	
//ANTIGUA FUNCIÓN QUE DETERMINABA EL ARCHIVO DE CIERRES TANTO DE LAS CAJAS FACTURA
//FUNCION 
//	
	/*
	*		FORMA donde nos muetras las facturas y nos da la posibilidad de crear facturas
	*   imprimilas y generar rollos fiscales de credito y de contado
	*/
	function CierresAnteriores($id,$caja,$cierre,$fecha,$caja_des,$sw,$dpto,$imp_pdf)
	{
					//unset($_SESSION['CONTROL_CIERRE']['DATOS']);
					if(!$cierre)
					{
						$cierre=$_REQUEST['cierre'];
						$id=$_REQUEST['id'];
						$caja=$_REQUEST['caja'];
						$fecha=$_REQUEST['fecha'];
						$sw=$_REQUEST['sw_recibo'];
						$dpto=$_REQUEST['dpto'];
						$caja_des=$_REQUEST['descripcion'];
						$cuentatipo=$_REQUEST['cuenta_tipo'];
					}

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
		

					//si es 1 es factura 2 si es recibo de caja,.
					$fecha1=$this->FormateoFechaLocal($fecha);
					if($sw==1)
					{$d="FACTURAS DEL CIERRE No.&nbsp;$cierre &nbsp;&nbsp;[&nbsp;$fecha1&nbsp;] "; }
					else{$d="RECIBOS DE CAJA DEL CIERRE No.&nbsp;$cierre &nbsp;&nbsp;$fecha1 ";}
					$this->salida.= ThemeAbrirTabla($d);
					$this->Encabezado();
					//cambiar cuando sea tambien control de recibos de caja.//OJO CAMBIAR
					$go_to_url=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
					array('sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));

					$this->User_Encabezado($id,$caja_des);
					$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
					$RUTA = $_ROOT."cache/control_cierre".UserGetUID()."_".$id.".pdf";
					$DIR="printer.php?ruta=$RUTA";
					$RUTA1= GetBaseURL() . $DIR;
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="var rem=\"\";\n";
					$mostrar.="  function abreVentana(){\n";
					$mostrar.="    var nombre=\"\"\n";
					$mostrar.="    var url2=\"\"\n";
					$mostrar.="    var str=\"\"\n";
					$mostrar.="    var width=\"400\"\n";
					$mostrar.="    var height=\"300\"\n";
					$mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar.="    var url2 ='$RUTA1';\n";
					$mostrar.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar.="</script>\n";
					$this->salida.="$mostrar";
				
					if($sw==1){$x='Cierre de caja No';}else{$x='Recibo No';}
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"90%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='11'>&nbsp;</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">$x</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					$this->salida.="  <td >Usuario</td>";
					$this->salida.="  <td >Total Efectivo</td>";
					$this->salida.="  <td >Total Cheque</td>";
					$this->salida.="  <td >Total Tarjetas</td>";
					$this->salida.="  <td >Total Bonos</td>";

					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}
					$this->salida.="  <td >Sub Total</td>";
					$this->salida.="  <td >Valor Confirmado</td>";
//					$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					//si son facturas de sos colocamos este campo
					if($sw==1)
					{
						$this->salida.="  <td >Vista</td>";
					}
					$this->salida.="</tr>";

					if($sw==1)
					{$vec=$this->TraerFacturas($caja,$id,$cierre,$dpto);$reporte = new GetReports();}else{$vec=$this->TraerRecibos($caja,$id,$cierre);}
						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
						if($imp_pdf)
						{	$this->GenerarListadoCierreCaja($id,$caja,$dpto,$cierre);}

// 						if($vec[ValorAnulado]==0)
// 						{
// 							$lim=sizeof($vec)-1;
// 						}
// 						else
// 						{
// 							$lim=sizeof($vec);
// 						}

						for($i=0;$i<sizeof($vec);$i++)
						{
/*										$rec=$vec[$i][recibo_caja];
										$pre=$vec[$i][prefijo];*/
if($vec[$k][total_abono]!=-1)
{
										$cierre_de_caja=$vec[$i][cierre_de_caja_id];
										$fech=$vec[$i][fecha_ingcaja];
										$cajadesc=$vec[$i][caja];
										$ef=$vec[$i][total_efectivo];
										$che=$vec[$i][total_cheques];
										$tar=$vec[$i][total_tarjetas];
										$bon=$vec[$i][total_bonos];
										$su=$vec[$i][suma];
										$arreglo[$i]=$vec[$i][caja_id];
										$valor_confirmado=$vec[$i][valor_confirmado];
										$observaciones_confirmacion=$vec[$i][observaciones_confirmacion];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
/*										$this->salida.="  <td>".$pre."-".$rec."</td>";*/
										$this->salida.="  <td>$cierre_de_caja</td>";
										$this->salida.="  <td>$fech</td>";

										//$this->salida.="  <td>".$this->TraerPaciente($rec,$pre)."</td>";
										$this->salida.="  <td>".$vec[$i][nombre]."</td>";
										$this->salida.="  <td>$&nbsp;".FormatoValor($ef)."</td>";
										$this->salida.="  <td>".FormatoValor($che)."</td>";
										$this->salida.="  <td>".FormatoValor($tar)."</td>";
										$this->salida.="  <td>".FormatoValor($bon)."</td>";
										$this->salida.="  <td>".FormatoValor($su)."</td>";
									
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}
										$this->salida.="  <td>".FormatoValor($valor_confirmado)."</td>";
										//$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][numerodecuenta]."^".$vec[$i][recibo_caja]."^".$vec[$i][prefijo]."></td>";

										//este caso es solo para sos..
										if($sw==1)
										{
										    //echo "<br>".$vec[$i][numerodecuenta];
/*												$this->salida .= $reporte->GetJavaReport('app','CajaGeneral','Factura',
												array('sw_copia'=>TRUE,'cuenta'=>$vec[$i][numerodecuenta],'switche_emp'=>0),array('rpt_dir'=>'cache','rpt_name'=>'recibo'.$vec[$i][numerodecuenta],'rpt_rewrite'=>TRUE));*/
												$this->salida .= $reporte->GetJavaReport('app','CajaGeneral','FacturaControl',
												array('sw_copia'=>TRUE,'cierre'=>$vec[$i][cierre_de_caja_id],'cuentatipo'=>$cuentatipo,'switche_emp'=>0),array('rpt_dir'=>'cache','rpt_name'=>'factura'.$vec[$i][cierre_de_caja_id],'rpt_rewrite'=>TRUE));
												$funcion=$reporte->GetJavaFunction();
												$this->salida .= "<td align=\"center\"><a href=\"javascript:$funcion\"><b>PDF</b></a></td>\n";
										}

										$subT=$subT+$su;
										$tef=$tef+$ef;
										$tche=$tche+$che;
										$ttar=$ttar+$tar;
										$tbon=$tbon+$bon;
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}
										$this->salida.="</tr>";
}
						}
									if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
									$this->salida.="<tr>";
									$moneda="$ ";
									$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";

									if($_SESSION['CAJA']['CIERRE']['DEPTO'])
									{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
									if($vec[ValorAnulado])
									{
										$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subT-$vec[ValorAnulado])."</td>";
										$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($valor_confirmado-$vec[ValorAnulado])."</td>";
									}
									else
									{
										$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subT)."</td>";
										$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($valor_confirmado)."</td>";
									}
									$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
									$this->salida.="</tr>";
									if(!empty($observaciones_confirmacion))
									{
									$this->salida.="<tr class=\"modulo_table_list\">";
									$this->salida.="<td align=\"left\" colspan='11'><label class='label_mark'>Observaciones: </label>$observaciones_confirmacion</td>";
									$this->salida.="</tr>";
									}
/*									$go_to_contado=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
									array('rollo'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\"  colspan='10'><a href='$go_to_contado'>Generar Rollo Fiscal Contado</a></td>";
									$this->salida.="</tr>";
									$go_to_credito=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
									array('rollo'=>1,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\" colspan='10'><a href='$go_to_credito'>Generar Rollo Fiscal Credito</a></td>";
									$this->salida.="</tr>";
									
									//parte del pdf, pero no lo debe generar hasta que haya guardado
									//una justificacion.
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                  if(empty($imp_pdf))
									{
										$go_to_pdf=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
										array('rollo'=>5,'sw_tipo'=>5,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
										$this->salida.="<td align=\"left\" colspan='10'><a href='$go_to_pdf'>Generar impresion Pdf </a></td>";
									}
									else
									{
											$this->salida.="<td align=\"left\" colspan=10'><a href='javascript:abreVentana()'>[Vista impresion Pdf] </a></td>";
									}*/
									$this->salida.="</tr>";
									
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					//$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Imprimir\"></td>";
					$this->salida .= "    </form>";
					$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.= ThemeCerrarTabla();
					return true;

	}



	
	/*
	*  Funcion que muestra una forma donde se debe guardar el motivo de por que se realiza las impresiones
	* esto es de caracter obligatorio esta forma es para cajas facturadoras.
	*
	*/
	function FrmAuditoria()
	{
			//unset($_SESSION['CONTROL_CIERRE']['DATOS']);
			if(!$id)
			{
				$cierre=$_REQUEST['cierre'];
				$id=$_REQUEST['id'];
				$caja=$_REQUEST['caja'];
				$fecha=$_REQUEST['fecha'];
				$sw=$_REQUEST['sw_recibo'];
				$dpto=$_REQUEST['dpto'];
				$caja_des=$_REQUEST['descripcion'];
				$rollo=$_REQUEST['rollo'];
				$sw_tipo=$_REQUEST['sw_tipo'];
				$actual=$_REQUEST['actual'];
				$cuenta=$_REQUEST['cuenta'];
				$op=$_REQUEST['op'];
				$retorno=$_REQUEST['retorno'];//para guardar pdf utilizo la misma funcion,la diferencia
				//es esta variable ya que si esta en 1 va a cierres caja  anteriores, sino a cierres de facturas 
			}

			$this->salida.= ThemeAbrirTabla("GUARDAR INFORMACION PARA AUDITORIA");
			$this->Encabezado();
			$this->User_Encabezado($id,$caja_des);
			
			if($rollo==1)
			{//si esto es igual a 1 es por que es fiscal no es una impresion ordinaria.
				//ECHO '++'.$rollo; exit;
				$go_to_url=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
				array('rollo'=>$rollo,'sw_tipo'=>$sw_tipo,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
				$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
			}
			elseif($rollo==5)// es generar pdf...
			{ //ECHO '--'.$rollo; exit;
				$go_to_url=ModuloGetURL('app','Control_Cierre','user','GuardarPdf',
				array('retorno'=>$retorno,'op'=>$op,'actual'=>$actual,'sw_tipo'=>$sw_tipo,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'cuenta'=>$cuenta));
				$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
			}
			else
			{
				//ECHO '>'.$rollo; exit;
				$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes',
				array('op'=>$op,'actual'=>$actual,'sw_tipo'=>$sw_tipo,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
				$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
			}
			
			$this->salida.="<br><table border=\"0\"  align=\"center\"   width=\"60%\" >";
			$this->salida .="".$this->SetStyle("MensajeError")."";
			
			$this->salida.="<tr  class=\"modulo_list_claro\">";
			$this->salida .='<td colspan=2><label class=label_mark><b>Nota</b>:&nbsp;Recuerde que debe justificar cada impresion ya que sera auditada</label></td>';
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida .= "<td   width=\"35%\"  >MOTIVO DE IMPRESION :</td>";
			$this->salida .= "<td  align=\"left\"><TEXTAREA name=obs cols=50 rows=8>".$_REQUEST['obs']."</TEXTAREA></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";


			$this->salida.="<br><table align=\"center\">";
			$this->salida.="<tr>";
			$this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Guardar\" class=\"input-submit\"></form></td>";
			$this->salida .='<td>&nbsp;</td>';
			$this->salida .= "<td>";
			
				if($actual==1)
				{
					$accion=ModuloGetURL('app','Control_Cierre','user','RevisarFacturasHoy',
					array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des));
				}
				else
				{
					$accion=ModuloGetURL('app','Control_Cierre','user','CierresAnteriores',
					array('sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
				}
			
			$this->salida .='<form name="forma" action="'.$accion.'" method="post">';
			$this->salida .="<input type=\"submit\" align=\"center\" name=\"tarjetas\" value=\"Volver\" class=\"input-submit\"></form></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.= ThemeCerrarTabla();
			return true;
	}
	
	
/*
	*		FORMA donde nos muetras las facturas y nos da la posibilidad de crear facturas
	*   imprimilas y generar rollos fiscales de credito y de contado
	*/
	function CierresAnterioresReciboCaja($id,$caja,$cierre,$fecha,$caja_des,$sw,$imp_pdf,$cuenta)
	{
		// echo $_SESSION['CONTROL_CIERRE']['DATOS']['CUENTATIPO']; exit;
		//unset($_SESSION['CONTROL_CIERRE']['DATOS']);
					if(!$id)
					{
						$cierre=$_REQUEST['cierre'];
						$id=$_REQUEST['id'];
						$caja=$_REQUEST['caja'];
						$fecha=$_REQUEST['fecha'];
						$sw=$_REQUEST['sw_recibo'];
						$dpto=$_REQUEST['dpto'];
						$caja_des=$_REQUEST['descripcion'];
						$cuenta=$_REQUEST['cuenta_tipo'];
					}

/*					if(!empty($imp_pdf))
						$_SESSION['pdf']=$imp_pdf;
					else
						$imp_pdf=$_SESSION['pdf'];*/
					if ($sw==2 AND $cierre)
					{
						$RUTA = $_ROOT ."cache/Recibo".UserGetUID().".pdf";
						//$RUTA = $_ROOT ."cache/cierre_de_caja_reporte_confirmado".UserGetUID().".pdf";
					}
						//$RUTA = $_ROOT ."cache/ReciboCierre".UserGetUID().".pdf";
					else
						$RUTA = $_ROOT ."cache/ReciboCajaRapida".UserGetUID().".pdf";
					//$RUTA = $_ROOT ."cache/Recibo.pdf";
					$DIR="printer.php?ruta=$RUTA";
					$RUTA1= GetBaseURL() . $DIR;
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="var rem=\"\";\n";
					$mostrar.="  function abreVentana(){\n";
					$mostrar.="    var nombre=\"\"\n";
					$mostrar.="    var url2=\"\"\n";
					$mostrar.="    var str=\"\"\n";
					$mostrar.="    var width=\"400\"\n";
					$mostrar.="    var height=\"300\"\n";
					$mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar.="    var url2 ='$RUTA1';\n";
					$mostrar.="    rem = window.open(url2, nombre, str)};\n";
					
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
							
					//$RUTA2 = $_ROOT ."cache/control_cierre_de_caja".UserGetUID().".pdf";
					$RUTA2 = $_ROOT ."cache/control_cierre".UserGetUID()."_".$id.".pdf";
					$DIR="printer.php?ruta=$RUTA2";
					$RUTAx= GetBaseURL() . $DIR;
					$mostrar1 ="\n<script language='javascript'>\n";
					$mostrar1.="var rem=\"\";\n";
					$mostrar1.="  function abrecierre(){\n";
					$mostrar1.="    var nombre=\"\"\n";
					$mostrar1.="    var url2=\"\"\n";
					$mostrar1.="    var width=\"400\"\n";
					$mostrar1.="    var height=\"300\"\n";
					$mostrar1.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar1.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar1.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar1.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar1.="    var url2 ='$RUTAx';\n";
					$mostrar1.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar1.="</script>\n";
					$this->salida.="$mostrar1";
					if($_SESSION['CAJA']['PARAM']=="ShowReport") 
					{	
						$this->salida.="<BODY onload=abreVentana();>";
						unset($_SESSION['CAJA']['PARAM']);
					}
					else
					if($_SESSION['CAJA']['PARAM']=="ShowReportControl") 
					{	
						$this->salida.="<BODY onload=abrecierre();>";
						unset($_SESSION['CAJA']['PARAM']);
					}

					//si es 1 es factura 2 si es recibo de caja,.
					$fecha1=$this->FormateoFechaLocal($fecha);
					if($sw==2 OR $sw==3)
					{$d="RECIBOS DE CAJA DEL CIERRE No.&nbsp;$cierre &nbsp;&nbsp;$fecha1 ";}
					$this->salida.= ThemeAbrirTabla($d);
					$this->Encabezado();
					//cambiar cuando sea tambien control de recibos de caja.//OJO CAMBIAR
					if($sw==3)
					{
						$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes_Pos_Concepto',
						array('retorno'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					}
					else
					{
						$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes_Pos_Hosp',
						array('retorno'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'cuenta'=>$cuenta));
					}

					$this->User_Encabezado($id,$caja_des);
					$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
					if($cuenta=='03'){$tipocliente='Tercero';}else{$tipocliente='Paciente';}
					if($sw==1){$x='Factura No';}else{$x='Recibo No';}
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='10'>&nbsp;</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">$x</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					$this->salida.="  <td >".$tipocliente."</td>";
					$this->salida.="  <td >Total Efectivo</td>";
					$this->salida.="  <td >Total Cheque</td>";
					$this->salida.="  <td >Total Tarjetas</td>";
					$this->salida.="  <td >Total Bonos</td>";
					$this->salida.="  <td >Dev</td>";

					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}
					$this->salida.="  <td >Sub Total</td>";
					//if($sw!=1)
					//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					$this->salida.="  <td >&nbsp;</td>";
					$this->salida.="</tr>";
					if($sw==1)
					{
						$vec=$this->TraerRecibosAnterior($caja,$id,$cierre,$sw);
						//$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}
					else
					if($sw==2)
					{
						$vec=$this->TraerRecibosAnterior($caja,$id,$cierre,'');
						//$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}
/*					else
					if($sw==3)
					{
						$vec=$this->TraerRecibosConceptos($caja,$id,$cierre);

					}*/
						//print_r($vec); exit;
						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
						//OBSERVACIONES
						$dat=$this->TraerObservaciones($cierre);
						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE'][observaciones]=$dat[observaciones];
						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE'][observaciones_confirmacion]=$dat[observaciones_confirmacion];
						//OBSERVACIONES
						if($imp_pdf AND $sw==1)
						{
							$this->GenerarListadoCierreCaja($id,$caja,'',$cierre,$cuenta);
						}
						else
						if($imp_pdf AND $sw==2)
						{
							$this->GenerarListadoCierreCaja($id,$caja,'',$cierre);
						}
						//para determinar que el cierre q voy a abrir es de cajas_hosp simplemente no envio el
						//departamento y listo!

						for($i=0;$i<sizeof($vec);$i++)
						{
									$pre=$vec[$i][prefijo];
									if($sw==3)
									{
										$rec=$vec[$i][factura_fiscal];
										$fech=$vec[$i][fecha_registro];
									}
									else
									if($sw==2 || $sw==1)
									{
										$fech=$vec[$i][fecha_ingcaja];
										$rec=$vec[$i][recibo_caja];
									}
										$cajadesc=$vec[$i][caja];
										$ef=$vec[$i][total_efectivo];
										$che=$vec[$i][total_cheques];
										$tar=$vec[$i][total_tarjetas];
										$bon=$vec[$i][total_bonos];
										$su=$vec[$i][suma];
										$arreglo[$i]=$vec[$i][caja_id];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
										$this->salida.="  <td>".$pre.$rec."</td>";
										$this->salida.="  <td>$fech</td>";

										if($sw==3)
										{
											$this->salida.="  <td>".$this->TraerClienteConceptos($rec,$pre)."</td>";
										}
										else
										if($sw==2 || $sw==1)
										{
											$this->salida.="  <td>".$this->TraerPacienteCajaGeneral($rec,$pre,'','',$sw,$cuenta,$vec[$i][caja_id])."</td>";
										}
										$this->salida.="  <td>".FormatoValor($ef)."</td>";
										$this->salida.="  <td>".FormatoValor($che)."</td>";
										$this->salida.="  <td>".FormatoValor($tar)."</td>";
										$this->salida.="  <td>".FormatoValor($bon)."</td>";
										$this->salida.="  <td>".FormatoValor($dev)."</td>";
									
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}
										if($vec[$i][total_abono]!=-1)
										{
											$this->salida.="  <td>".FormatoValor($su)."</td>";
										}
										else
										{
											$this->salida.="  <td><font color='red'><b>ANULADO</b></font></td>";
										}
										if($sw==3)
										{
										$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][factura_fiscal]."*".$vec[$i][prefijo]."></td>";
										}
										else
										if($sw!=1)
										{
										//$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][recibo_caja]."*".$vec[$i][prefijo]."*".$vec[$i][numerodecuenta]."*".$vec[$i][usuario_id]."></td>";
										}

										if($vec[$i][total_abono]!=-1)
										{
											$subT=$subT+$su;
											$tef=$tef+$ef;
											$tche=$tche+$che;
											$ttar=$ttar+$tar;
											$tbon=$tbon+$bon;
											$tdev=$tbon+$bon;
										}
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}
/*										if($sw==3)
										{
											$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Conceptos',
										array('retorno'=>1,'recibo'=>$vec[$i][factura_fiscal],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>3,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'usuario'=>$vec[$i][usuario_id]));
										}
										else
										{*/
											$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
										array('retorno'=>1,'recibo'=>$vec[$i][recibo_caja],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$vec[$i][caja_id],'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$vec[$i][cierre_caja_id],'fecha'=>$fecha,'usuario'=>$vec[$i][usuario_id],'imp_pdf'=>$imp_pdf,'cierre2'=>$cierre,'cuenta'=>$cuenta));
									//	}	
										if($vec[$i][total_abono]!=-1)
										{
											$this->salida.="  <td><a href='$url_pdf'><b>PDF..</b></a></td>";
										}
										else
										{
											$this->salida.="  <td><b>PDF</b></td>";
										}

										$this->salida.="</tr>";
						}
									if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
									$this->salida.="<tr>";
									$moneda="$ ";
									$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdev)."</td>";

									if($_SESSION['CAJA']['CIERRE']['DEPTO'])
									{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
									$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subT)."</td>";
									$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">&nbsp;</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr>";
									$this->salida.="<td align=\"center\" colspan='11'>";
//DEVULUCIONES DEL CIERRE
					if($sw==2)
					{
						$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}
					if(sizeof($dev)>0)
					{
						$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\" >";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="<td align=\"left\" colspan='11'>Relación de Devoluciones..</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"9%\">Devolución No</td>";
						$this->salida.="  <td width=\"10%\">Fecha</td>";
						$this->salida.="  <td >Paciente</td>";
						$this->salida.="  <td >Total Efectivo</td>";
						$this->salida.="  <td >Sub Total</td>";
						//$this->salida.="  <td >&nbsp;</td>";
						$this->salida.="  <td >&nbsp;</td>";
						//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
						//$this->salida.="  <td ></td>";
						$this->salida.="</tr>";
	// 					if($sw==2)
	// 					{
	// /*						$vec=$this->TraerRecibosAnterior($caja,$id,$cierre);*/
	// 						$dev=$this->TraerDevAnterior($caja,$id,$cierre);
	// 					}
	
							$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE_DEV']=$dev;
/*							if($imp_pdf)
							{	$this->GenerarListadoCierreCaja($id,$caja,'',$cierre);}*/
							//para determinar que el cierre q voy a abrir es de cajas_hosp simplemente no envio el
							//departamento y listo!
							for($i=0;$i<sizeof($dev);$i++)
							{
										//$rec=$dev[$i][devolucion_id];
										$rec=$dev[$i][recibo_caja];
										$pre=$dev[$i][prefijo];
										$tmp=explode(' ',$dev[$i][fecha_registro]);
										$fech=$tmp[0];
										$cajadesc=$dev[$i][caja];
										$ef=$dev[$i][total_devolucion];
										$su=$dev[$i][total_devolucion];
										$arreglo[$i]=$dev[$i][caja_id];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
										$this->salida.="  <td>".$pre.$rec."</td>";
										$this->salida.="  <td>$fech</td>";

										$this->salida.="  <td>".$this->TraerPacienteCajaGeneralDev($dev[$i][numerodecuenta],$dev[$i][usuario_id],$cierre)."</td>";
										$this->salida.="  <td>".FormatoValor($ef)."</td>";
									
/*										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}*/
										$this->salida.="  <td>".FormatoValor($su)."</td>";
										//$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=opdv2[$i] value=".$dev[$i][numerodecuenta].'*'.$dev[$i][usuario_id].'*'.$cierre.'*'.$dev[$i][devolucion_id].'*'.$dev[$i][prefijo]."></td>";

										$subTd=$subTd+$su;
										$tefd=$tefd+$ef;
/*										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}*/
										$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
										array('retorno'=>1,'recibo'=>$dev[$i][devolucion_id],'prefijo'=>$dev[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'file'=>'1','cuenta'=>$dev[$i][numerodecuenta],'usuario'=>$dev[$i][usuario_id],'dev'=>'1','cuenta'=>$dev[$i][numerodecuenta]));

										$this->salida.="  <td><a href='$url_pdf'><b>PDF</b></a></td>";
										$this->salida.="</tr>";
							}
										if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
										$this->salida.="<tr>";
										$moneda="$ ";
										$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
										$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tefd)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdev)."</td>";
	
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
										$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subTd)."</td>";
										$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
										$this->salida.="</tr>";
										$this->salida.="</table>";
										$this->salida.="</td>";
										$this->salida.="</tr>";
									}
//FIN DEVULUCIONES DEL CIERRE

/*									$go_to_contado=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
									array('sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\"  colspan='11'><a href='$go_to_contado'>Generar Rollo Fiscal Contado</a></td>";
									$this->salida.="</tr>";
									$go_to_credito=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
									array('sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\" colspan='11'><a href='$go_to_credito'>Generar Rollo Fiscal Credito</a></td>";
									$this->salida.="</tr>";*/
									
									
									//parte del pdf, pero no lo debe generar hasta que haya guardado
									//una justificacion.
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                  if(empty($imp_pdf))
									{
										$go_to_pdf=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
										array('retorno'=>1,'rollo'=>5,'sw_tipo'=>5,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'cuenta'=>$cuenta));
										$this->salida.="<td align=\"left\" colspan='10'><a href='$go_to_pdf'>GENERAR IMPRESION PDF..</a></td>";
									}
									else
									{
											$this->salida.="<td align=\"left\" colspan='11'><a href='javascript:abrecierre()'>VISTA IMPRESION PDF </a></td>";
									}
									$this->salida.="</tr>";

					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					if($sw==1)
					{
					$this->salida .= "</form>";
					}
					else
/*					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Imprimir\"></td>";*/
					$this->salida .= "</form>";
					$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.= ThemeCerrarTabla();
					return true;

	}

	function CierreConfirmado($id,$caja,$cierre,$fecha,$caja_des,$sw,$imp_pdf)
	{
					//unset($_SESSION['CONTROL_CIERRE']['DATOS']);
					if(!$id)
					{
						$cierre=$_REQUEST['cierre'];
						$id=$_REQUEST['id'];
						$caja=$_REQUEST['caja'];
						$fecha=$_REQUEST['fecha'];
						$sw=$_REQUEST['sw_recibo'];
						$dpto=$_REQUEST['dpto'];
						$caja_des=$_REQUEST['descripcion'];
						$cuenta=$_REQUEST['cuenta_tipo'];
					}
					if($_SESSION['CAJA']['PARAM']=='ShowReport')
						$RUTA=$_ROOT ."cache/cierre_de_caja_reporte_confirmado".UserGetUID().".pdf";
					else
					if($_SESSION['CAJA']['PARAM']=='ShowReportConcepto')
						$RUTA=$_ROOT ."cache/cierre_de_caja_concepto_confirmado".UserGetUID().".pdf";
					else
						$RUTA=$_ROOT ."cache/Recibo".UserGetUID().".pdf";
					//$RUTA = $_ROOT ."cache/Recibo.pdf";
					$DIR="printer.php?ruta=$RUTA";
					$RUTA1= GetBaseURL() . $DIR;
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="var rem=\"\";\n";
					$mostrar.="  function abreVentana(){\n";
					$mostrar.="    var nombre=\"\"\n";
					$mostrar.="    var url2=\"\"\n";
					$mostrar.="    var str=\"\"\n";
					$mostrar.="    var width=\"400\"\n";
					$mostrar.="    var height=\"300\"\n";
					$mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar.="    var url2 ='$RUTA1';\n";
					$mostrar.="    rem = window.open(url2, nombre, str)};\n";
					
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
							
					$RUTA2 = $_ROOT ."cache/control_cierre_de_caja".UserGetUID().".pdf";
					//$RUTA2 = $_ROOT ."cache/control_cierre".UserGetUID()."_".$id.".pdf";
					$DIR="printer.php?ruta=$RUTA2";
					$RUTAx= GetBaseURL() . $DIR;
					$mostrar1 ="\n<script language='javascript'>\n";
					$mostrar1.="var rem=\"\";\n";
					$mostrar1.="  function abrecierre(){\n";
					$mostrar1.="    var nombre=\"\"\n";
					$mostrar1.="    var url2=\"\"\n";
					$mostrar1.="    var width=\"400\"\n";
					$mostrar1.="    var height=\"300\"\n";
					$mostrar1.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar1.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar1.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar1.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar1.="    var url2 ='$RUTAx';\n";
					$mostrar1.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar1.="</script>\n";
					$this->salida.="$mostrar1";
					
					if($_SESSION['CAJA']['PARAM']=="ShowReport" || ($_SESSION['CAJA']['PARAM']=='ShowReportConcepto')) 
					{	
						$this->salida.="<BODY onload=abreVentana();>";
						unset($_SESSION['CAJA']['PARAM']);
					}
					else
					if($_SESSION['CAJA']['PARAM']=="ShowReportControl") 
					{	
						$this->salida.="<BODY onload=abrecierre();>";
						unset($_SESSION['CAJA']['PARAM']);
					}

					//si es 1 es factura 2 si es recibo de caja,.
					$fecha1=$this->FormateoFechaLocal($fecha);
					if($sw==2 OR $sw==3)
					{$d="RECIBOS DE CAJA DEL CIERRE No.&nbsp;$cierre &nbsp;&nbsp;$fecha1 ";}
					$this->salida.= ThemeAbrirTabla($d);
					$this->Encabezado();
					//cambiar cuando sea tambien control de recibos de caja.//OJO CAMBIAR
					if($sw==3)
					{
						$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes_Pos_Concepto',
						array('retorno'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					}
					else
					{
						$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes_Pos_Hosp',
						array('retorno'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
					}

					$this->User_Encabezado($id,$caja_des);
					$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
					$x='Cierre Nro';
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='10'>&nbsp;</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"10%\">$x</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					$this->salida.="  <td >Total Efectivo</td>";
					$this->salida.="  <td >Total Cheque</td>";
					$this->salida.="  <td >Total Tarjetas</td>";
					$this->salida.="  <td >Total Bonos</td>";
					$this->salida.="  <td >Dev</td>";
					$this->salida.="	<td>Entrega_efectivo</td>";
					$this->salida.="	<td>Valor cofirmado</td>";
					$this->salida.="<td class=\"hc_table_submodulo_list_title\">&nbsp;</td>";
					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}
					//$this->salida.="  <td >Sub Total</td>";
					//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					//$this->salida.="  <td ></td>";
					$this->salida.="</tr>";
					if($sw==2)
					{
						$vec=$this->TraerDatosCierre($caja,$id,$cierre);
						//$vec=$this->TraerRecibosAnterior($caja,$id,$cierre);
						//$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}
					else
					if($sw==3)
					{
						$vec=$this->TraerDatosCierre($caja,$id,$cierre);

					}

						$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
						if($imp_pdf)
						{	$this->GenerarListadoCierreCaja($id,$caja,'',$cierre);}
						//para determinar que el cierre q voy a abrir es de cajas_hosp simplemente no envio el
						//departamento y listo!

						for($i=0;$i<sizeof($vec);$i++)
						{
/*									if($sw==3)
									{
										$rec=$vec[$i][factura_fiscal];
										$fech=$vec[$i][fecha_registro];
									}
									else
									if($sw==2)
									{*/
										$fech=$vec[$i][fecha_registro];
										$rec='';
									//}
										$cierre=$vec[$i][cierre_de_caja_id];
										$pre=$vec[$i][caja];
										$cajadesc=$vec[$i][caja];
										$ef=$vec[$i][total_efectivo];
										$che=$vec[$i][total_cheques];
										$tar=$vec[$i][total_tarjetas];
										$bon=$vec[$i][total_bonos];
										$su=$vec[$i][suma];
										$dev=$vec[$i][total_devolucion];
										$entrega=$vec[$i][entrega_efectivo];
										$valor_confirmado=$vec[$i][valor_confirmado];
										$arreglo[$i]=$vec[$i][caja_id];
										$observa=$vec[$i][observaciones_confirmacion];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
										$this->salida.="  <td>".$cierre."</td>";
										$this->salida.="  <td>$fech</td>";
										//$this->salida.="  <td>".$pre.$rec."</td>";

/*										if($sw==3)
										{
											$this->salida.="  <td>".$this->TraerClienteConceptos($rec,$pre)."</td>";
										}
										else
										if($sw==2)
										{
											$usuario=$this->TraerUsuario($vec[$i][usuario_id]);
											$this->salida.="  <td>".$usuario[nombre]."</td>";
										}*/
										$this->salida.="  <td>".FormatoValor($ef)."</td>";
										$this->salida.="  <td>".FormatoValor($che)."</td>";
										$this->salida.="  <td>".FormatoValor($tar)."</td>";
										$this->salida.="  <td>".FormatoValor($bon)."</td>";
										$this->salida.="  <td>".FormatoValor($dev)."</td>";
										$this->salida.="  <td>".FormatoValor($entrega)."</td>";
										$this->salida.="  <td>".FormatoValor($valor_confirmado)."</td>";
									
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}
										//$this->salida.="  <td>".FormatoValor($su)."</td>";
										/*if($sw==3)
										{
										$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][factura_fiscal]."*".$vec[$i][prefijo]."></td>";
										}
										else
										{
										$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][recibo_caja]."*".$vec[$i][prefijo]."*".$vec[$i][numerodecuenta]."></td>";
										}*/

										$subT=$subT+$su;
										$tef=$tef+$ef;
										$tche=$tche+$che;
										$ttar=$ttar+$tar;
										$tbon=$tbon+$bon;
										$tdev=$tdev+$dev;
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}
										if($sw==3)
										{
											$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Conceptos',
										array('retorno'=>1,'recibo'=>$vec[$i][factura_fiscal],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>3,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'usuario'=>$vec[$i][usuario_id]));
										}
										else
										{
										if($sw==2)
											$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
										array('retorno'=>1,'recibo'=>$vec[$i][recibo_caja],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'usuario'=>$vec[$i][usuario_id]));
										}	
										$this->salida.="  <td><a href='$url_pdf'><b>[PDF]</b></a></td>";
										$this->salida.="</tr>";
						}
									if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
									$this->salida.="<tr>";
									$moneda="$ ";
									$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='2'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdev)."</td>";

									if($_SESSION['CAJA']['CIERRE']['DEPTO'])
									{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
									$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($entrega)."</td>";
									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($valor_confirmado)."</td>";
									$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
									$this->salida.="</tr>";
									$this->salida.="<tr>";
									if(!empty($observa))
									{
									$this->salida.="<tr class=\"modulo_table_list\">";
									$this->salida.="<td align=\"left\" colspan='10'><label class='label_mark'>Observaciones: </label>$observa</td>";
									$this->salida.="</tr>";
									}
									$this->salida.="<td align=\"center\" colspan='11'>";
//DEVULUCIONES DEL CIERRE
					if($sw==2)
					{
						$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}
					if(sizeof($dev)>0 AND $sw==2)
					{
						$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\" >";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="<td align=\"left\" colspan='6'>Relación de Devoluciones</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"9%\">Devolución No</td>";
						$this->salida.="  <td width=\"10%\">Fecha</td>";
						$this->salida.="  <td >Paciente</td>";
						$this->salida.="  <td >Total Efectivo</td>";
						$this->salida.="  <td >Sub Total</td>";
						$this->salida.="  <td >&nbsp;</td>";
						//$this->salida.="  <td >&nbsp;</td>";
						//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
						//$this->salida.="  <td ></td>";
						$this->salida.="</tr>";
	// 					if($sw==2)
	// 					{
	// /*						$vec=$this->TraerRecibosAnterior($caja,$id,$cierre);*/
	// 						$dev=$this->TraerDevAnterior($caja,$id,$cierre);
	// 					}
	
							$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
							if($imp_pdf)
							{	$this->GenerarListadoCierreCaja($id,$caja,'',$cierre);}
							//para determinar que el cierre q voy a abrir es de cajas_hosp simplemente no envio el
							//departamento y listo!
							for($i=0;$i<sizeof($dev);$i++)
							{
										//$rec=$dev[$i][devolucion_id];
										$rec=$dev[$i][recibo_caja];
										$pre=$dev[$i][prefijo];
										$tmp=explode(' ',$dev[$i][fecha_registro]);
										$fech=$tmp[0];
										$cajadesc=$dev[$i][caja];
										$ef=$dev[$i][total_devolucion];
										$su=$dev[$i][total_devolucion];
										$arreglo[$i]=$dev[$i][caja_id];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
										$this->salida.="  <td>".$pre.$rec."</td>";
										$this->salida.="  <td>$fech</td>";

										$this->salida.="  <td>".$this->TraerPacienteCajaGeneralDev($dev[$i][numerodecuenta],$dev[$i][usuario_id],$cierre)."</td>";
										$this->salida.="  <td>".FormatoValor($ef)."</td>";
									
/*										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}*/
										$this->salida.="  <td>".FormatoValor($su)."</td>";
										//$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=opdv2[$i] value=".$dev[$i][numerodecuenta].'*'.$dev[$i][usuario_id].'*'.$cierre.'*'.$dev[$i][devolucion_id].'*'.$dev[$i][prefijo]."></td>";

										$subTd=$subTd+$su;
										$tefd=$tefd+$ef;
/*										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}*/
										$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
										array('retorno'=>1,'recibo'=>$dev[$i][devolucion_id],'prefijo'=>$dev[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'file'=>'1','cuenta'=>$dev[$i][numerodecuenta],'usuario'=>$dev[$i][usuario_id]));
										//REEMPLAZA POR AHORA EL PDF
						$this->salida.="  <td >&nbsp;</td>";
									//	$this->salida.="  <td><a href='$url_pdf'><b>PDF</b></a></td>";
										$this->salida.="</tr>";
							}
										if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
										$this->salida.="<tr>";
										$moneda="$ ";
										$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
										$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tefd)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdev)."</td>";
	
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
										$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subTd)."</td>";
										$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
										$this->salida.="  <td >&nbsp;</td>";
										$this->salida.="</tr>";
										$this->salida.="</table>";
										$this->salida.="</td>";
										$this->salida.="</tr>";
									}
//FIN DEVULUCIONES DEL CIERRE
/*
									$go_to_contado=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
									array('sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\"  colspan='8'><a href='$go_to_contado'>Generar Rollo Fiscal Contado</a></td>";
									$this->salida.="</tr>";
									$go_to_credito=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
									array('sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\" colspan='8'><a href='$go_to_credito'>Generar Rollo Fiscal Credito</a></td>";
									$this->salida.="</tr>";
									*/
									
									//parte del pdf, pero no lo debe generar hasta que haya guardado
									//una justificacion.
									/*$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									//IMPRESION PDF AUDITORIA
                  if(empty($imp_pdf))
									{
										$go_to_pdf=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
										array('retorno'=>1,'rollo'=>5,'sw_tipo'=>5,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
										$this->salida.="<td align=\"left\" colspan='10'><a href='$go_to_pdf'>GENERAR IMPRESION PDFx</a></td>";
									}
									else
									{
											$this->salida.="<td align=\"left\" colspan='10'><a href='javascript:abrecierre()'>VISTA IMPRESION PDF </a></td>";
									}
									$this->salida.="</tr>";*/
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					//$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Imprimir\"></td>";
					$this->salida.="</form>";
					$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.= ThemeCerrarTabla();
					return true;

	}

	function CierresAnterioresReciboCaja_($id,$caja,$cierre,$fecha,$caja_des,$sw,$imp_pdf)
	{
					//unset($_SESSION['CONTROL_CIERRE']['DATOS']);
					if(!$id)
					{
						$cierre=$_REQUEST['cierre'];
						$id=$_REQUEST['id'];
						$caja=$_REQUEST['caja'];
						$fecha=$_REQUEST['fecha'];
						$sw=$_REQUEST['sw_recibo'];
						$dpto=$_REQUEST['dpto'];
						$caja_des=$_REQUEST['descripcion'];
						$cuenta_tipo=$_REQUEST['cuenta_tipo'];
					}

					$RUTA = $_ROOT ."cache/Recibo".UserGetUID().".pdf";
					//$RUTA = $_ROOT ."cache/Recibo.pdf";
					$DIR="printer.php?ruta=$RUTA";
					$RUTA1= GetBaseURL() . $DIR;
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="var rem=\"\";\n";
					$mostrar.="  function abreVentana(){\n";
					$mostrar.="    var nombre=\"\"\n";
					$mostrar.="    var url2=\"\"\n";
					$mostrar.="    var str=\"\"\n";
					$mostrar.="    var width=\"400\"\n";
					$mostrar.="    var height=\"300\"\n";
					$mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar.="    var url2 ='$RUTA1';\n";
					$mostrar.="    rem = window.open(url2, nombre, str)};\n";
					
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";

					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
							
					$RUTA2 = $_ROOT ."cache/control_cierre_de_caja".UserGetUID().".pdf";
					//$RUTA2 = $_ROOT ."cache/control_cierre".UserGetUID()."_".$id.".pdf";
					$DIR="printer.php?ruta=$RUTA2";
					$RUTAx= GetBaseURL() . $DIR;
					$mostrar1 ="\n<script language='javascript'>\n";
					$mostrar1.="var rem=\"\";\n";
					$mostrar1.="  function abrecierre(){\n";
					$mostrar1.="    var nombre=\"\"\n";
					$mostrar1.="    var url2=\"\"\n";
					$mostrar1.="    var width=\"400\"\n";
					$mostrar1.="    var height=\"300\"\n";
					$mostrar1.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar1.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar1.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar1.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar1.="    var url2 ='$RUTAx';\n";
					$mostrar1.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar1.="</script>\n";
					$this->salida.="$mostrar1";
				
					
					
					
					
					if($_SESSION['CAJA']['PARAM']=="ShowReport") 
					{	
						$this->salida.="<BODY onload=abreVentana();>";
						unset($_SESSION['CAJA']['PARAM']);
					}
					else
					if($_SESSION['CAJA']['PARAM']=="ShowReportControl") 
					{	
						$this->salida.="<BODY onload=abrecierre();>";
						unset($_SESSION['CAJA']['PARAM']);
					}

					//si es 1 es factura 2 si es recibo de caja,.
					$fecha1=$this->FormateoFechaLocal($fecha);
					if($sw==2)
					{$d="RECIBOS DE CAJA DEL CIERRE No.&nbsp;$cierre &nbsp;&nbsp;$fecha1 ";}
					$this->salida.= ThemeAbrirTabla($d);
					$this->Encabezado();
					//cambiar cuando sea tambien control de recibos de caja.//OJO CAMBIAR
					$go_to_url=ModuloGetURL('app','Control_Cierre','user','Reportes_Pos_Hosp',
					array('retorno'=>1,'sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));

					$this->User_Encabezado($id,$caja_des);
					$this->salida .= "           <form name=\"formas\" action=\"$go_to_url\" method=\"post\">";
					$this->salida .= "<SCRIPT>";
					$this->salida .= "function chequeoTotal(frm,x){";
					$this->salida .= "  if(x==true){";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=true";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }else{";
					$this->salida .= "    for(i=0;i<frm.elements.length;i++){";
					$this->salida .= "      if(frm.elements[i].type=='checkbox'){";
					$this->salida .= "        frm.elements[i].checked=false";
					$this->salida .= "      }";
					$this->salida .= "    }";
					$this->salida .= "  }";
					$this->salida .= "}";
					$this->salida .= "</SCRIPT>";
					if($sw==1){$x='Factura No';}else{$x='Recibo No';}
					$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\" >";
					$this->salida.="<tr class=\"modulo_table_title\">";
					$this->salida.="<td align=\"left\" colspan='10'>&nbsp;</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"9%\">$x</td>";
					$this->salida.="  <td width=\"10%\">Fecha</td>";
					//$this->salida.="  <td >Paciente</td>";
					$this->salida.="  <td >Total Efectivo</td>";
					$this->salida.="  <td >Total Cheque</td>";
					$this->salida.="  <td >Total Tarjetas</td>";
					$this->salida.="  <td >Total Devolución</td>";
					//$this->salida.="  <td >Dev</td>";

					if($_SESSION['CAJA']['CIERRE']['DEPTO'])
					{
						$this->salida.="  <td >Descuentos</td>";
					}
					$this->salida.="  <td >Sub Total</td>";
					$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
					$this->salida.="  <td ></td>";
					$this->salida.="</tr>";
					if($sw==2)
					{
						$vec=$this->TraerRecibosAnterior($caja,$id,$cierre);
						//$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}
					/*	a.empresa_id,a.centro_utilidad,a.caja_id,
						a.fecha_registro,b.descripcion as caja,
						a.total_efectivo,a.total_cheques,a.total_tarjetas,
						a.total_devolucion,entrega_efectivo,a.usuario_id,
						a.observaciones_confirmacion,a.cierre_de_caja_id*/
						//$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERREPRN']=$vec;
						//if($imp_pdf)
						//{	$this->GenerarListadoCierreCaja($id,$caja,'',$cierre);}
						//para determinar que el cierre q voy a abrir es de cajas_hosp simplemente no envio el
						//departamento y listo!

						for($i=0;$i<sizeof($vec);$i++)
						{
								$rec=$vec[$i][cierre_de_caja_id];
								//$pre=$vec[$i][prefijo];
								$fech=$vec[$i][fecha_registro];
								$cajadesc=$vec[$i][caja];
								$ef=$vec[$i][total_efectivo];
								$che=$vec[$i][total_cheques];
								$tar=$vec[$i][total_tarjetas];
								$bon=$vec[$i][total_devolucion];
								$su=$vec[$i][entrega_efectivo];
								$arreglo[$i]=$vec[$i][cierre_de_caja_id];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
								$this->salida.="  <td>".$rec."</td>";
								$this->salida.="  <td>$fech</td>";
	
								$this->salida.="  <td>".$this->TraerPacienteCajaGeneral($rec,$pre)."</td>";
								$this->salida.="  <td>".FormatoValor($ef)."</td>";
								$this->salida.="  <td>".FormatoValor($che)."</td>";
								$this->salida.="  <td>".FormatoValor($tar)."</td>";
								$this->salida.="  <td>".FormatoValor($bon)."</td>";
								$this->salida.="  <td>".FormatoValor($dev)."</td>";
							
								if($_SESSION['CAJA']['CIERRE']['DEPTO'])
								{
									$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
									$this->salida.="  <td>".FormatoValor($des)."</td>";
								}
								$this->salida.="  <td>".FormatoValor($su)."</td>";
								$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=op[$i] value=".$vec[$i][recibo_caja]."*".$vec[$i][prefijo]."*".$vec[$i][numerodecuenta]."></td>";

								$subT=$subT+$su;
								$tef=$tef+$ef;
								$tche=$tche+$che;
								$ttar=$ttar+$tar;
								$tbon=$tbon+$bon;
								$tdev=$tbon;
								if($_SESSION['CAJA']['CIERRE']['DEPTO'])
								{$tdes=$tdes+$des;}
								$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
					array('retorno'=>1,'cierre'=>$vec[$i][cierre_de_caja_id],'prefijo'=>$vec[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'cuenta_tipo'=>$cuenta_tipo));

										$this->salida.="  <td><a href='$url_pdf'><b>PDF</b></a></td>";
										$this->salida.="</tr>";
						}
								if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
								$this->salida.="<tr>";
								$moneda="$ ";
								$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='2'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
								$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tef)."</td>";
								$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
								$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
								//$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
								$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdev)."</td>";

								if($_SESSION['CAJA']['CIERRE']['DEPTO'])
								{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
								$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subT)."</td>";
								$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
								$this->salida.="</tr>";
								$this->salida.="<tr>";
								$this->salida.="<td align=\"center\" colspan='11'>";
//DEVULUCIONES DEL CIERRE
/*					if($sw==2)
					{
						$dev=$this->TraerDevAnterior($caja,$id,$cierre);
					}*/
					if(sizeof($dev)>0)
					{
						$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"100%\" >";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="<td align=\"left\" colspan='11'>Relación de Devoluciones</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"9%\">Devolución No</td>";
						$this->salida.="  <td width=\"10%\">Fecha</td>";
						$this->salida.="  <td >Paciente</td>";
						$this->salida.="  <td >Total Efectivo</td>";
						$this->salida.="  <td >Sub Total</td>";
						$this->salida.="  <td >&nbsp;</td>";
						$this->salida.="  <td >&nbsp;</td>";
						//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
						//$this->salida.="  <td ></td>";
						$this->salida.="</tr>";
	// 					if($sw==2)
	// 					{
	// /*						$vec=$this->TraerRecibosAnterior($caja,$id,$cierre);*/
	// 						$dev=$this->TraerDevAnterior($caja,$id,$cierre);
	// 					}
	
							$_SESSION['CONTROL_CIERRE']['DATOS']['VECTOR_CIERRE']=$vec;
							if($imp_pdf)
							{	$this->GenerarListadoCierreCaja($id,$caja,'',$cierre);}
							//para determinar que el cierre q voy a abrir es de cajas_hosp simplemente no envio el
							//departamento y listo!
							for($i=0;$i<sizeof($dev);$i++)
							{
										$rec=$dev[$i][devolucion_id];
										$pre=$dev[$i][prefijo];
										$tmp=explode(' ',$dev[$i][fecha_registro]);
										$fech=$tmp[0];
										$cajadesc=$dev[$i][caja];
										$ef=$dev[$i][total_devolucion];
										$su=$dev[$i][total_devolucion];
										$arreglo[$i]=$dev[$i][caja_id];
										if( $i % 2){ $estilo='modulo_list_claro';}
										else {$estilo='modulo_list_oscuro';}
										$this->salida.="<tr class=\"$estilo\" align=\"center\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
										$this->salida.="  <td>".$pre.$rec."</td>";
										$this->salida.="  <td>$fech</td>";

										$this->salida.="  <td>".$this->TraerPacienteCajaGeneralDev($dev[$i][numerodecuenta],$dev[$i][usuario_id],$cierre)."</td>";
										$this->salida.="  <td>".FormatoValor($ef)."</td>";
									
/*										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{
											$des=$this->TraerDescuento($vec[$i][numerodecuenta]);
											$this->salida.="  <td>".FormatoValor($des)."</td>";
										}*/
										$this->salida.="  <td>".FormatoValor($su)."</td>";
										$this->salida.="  <td width=\"10%\" align=\"center\"><input type=checkbox name=opdv2[$i] value=".$dev[$i][numerodecuenta].'*'.$dev[$i][usuario_id].'*'.$cierre.'*'.$dev[$i][devolucion_id].'*'.$dev[$i][prefijo]."></td>";

										$subTd=$subTd+$su;
										$tefd=$tefd+$ef;
/*										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{$tdes=$tdes+$des;}*/
										$url_pdf=ModuloGetURL('app','Control_Cierre','user','Reportes_Pdf_Hosp',
										array('retorno'=>1,'recibo'=>$dev[$i][devolucion_id],'prefijo'=>$dev[$i][prefijo],'actual'=>2,'sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha,'file'=>'1','cuenta'=>$dev[$i][numerodecuenta],'usuario'=>$dev[$i][usuario_id]));

										$this->salida.="  <td><a href='$url_pdf'><b>PDF</b></a></td>";
										$this->salida.="</tr>";
							}
										if($estilo =='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
										$this->salida.="<tr>";
										$moneda="$ ";
										$this->salida.="<td  class=\"modulo_list_oscuro\"  align=\"right\" colspan='3'>Totales &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
										$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tefd)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tche)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($ttar)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tbon)."</td>";
	// 									$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdev)."</td>";
	
										if($_SESSION['CAJA']['CIERRE']['DEPTO'])
										{	$this->salida.="<td class=\"hc_table_submodulo_list_title\">$moneda".FormatoValor($tdes)."</td>";}
										$this->salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\">".FormatoValor($subTd)."</td>";
										$this->salida.="<td class=\"modulo_table_list_title\"><img src=\"".GetThemePath()."/images/wtarrow.gif\"></td>";
										$this->salida.="</tr>";
										$this->salida.="</table>";
										$this->salida.="</td>";
										$this->salida.="</tr>";
									}
//FIN DEVULUCIONES DEL CIERRE
/*
									$go_to_contado=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
									array('sw_tipo'=>1,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\"  colspan='8'><a href='$go_to_contado'>Generar Rollo Fiscal Contado</a></td>";
									$this->salida.="</tr>";
									$go_to_credito=ModuloGetURL('app','Control_Cierre','user','GenerarRolloFiscal',
									array('sw_tipo'=>2,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="<td align=\"left\" colspan='8'><a href='$go_to_credito'>Generar Rollo Fiscal Credito</a></td>";
									$this->salida.="</tr>";
									*/
									
									//parte del pdf, pero no lo debe generar hasta que haya guardado
									//una justificacion.
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
/*                  if(empty($imp_pdf))
									{
										$go_to_pdf=ModuloGetURL('app','Control_Cierre','user','FrmAuditoria',
										array('retorno'=>1,'rollo'=>5,'sw_tipo'=>5,'sw_recibo'=>$sw,'id'=>$id,'caja'=>$caja,'dpto'=>$dpto,'descripcion'=>$caja_des,'cierre'=>$cierre,'fecha'=>$fecha));
										$this->salida.="<td align=\"left\" colspan='10'><a href='$go_to_pdf'>GENERAR IMPRESION PDF</a></td>";
									}
									else
									{
											$this->salida.="<td align=\"left\" colspan='10'><a href='javascript:abrecierre()'>VISTA IMPRESION PDF </a></td>";
									}*/
									$this->salida.="</tr>";
					$this->salida.="</table>";
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"guardar\" type=\"submit\" value=\"Imprimir\"></form></td>";
					$action2=ModuloGetURL('app','Control_Cierre','user','RetornarA');
					$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
					$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
					$this->salida .= "</tr>";
					$this->salida.="</table><br>";
					$this->salida.= ThemeCerrarTabla();
					return true;
	}


	/*
	* funcion q revisa en forma de buscador de archivador los cierres fiscales o facturas o cierres que se hicieron anteriormente
	*
	*/
	function BuscadorCierresAnteriores($vect='',$sw)
	{ 
				if(!$_SESSION['CONTROL_CIERRE']['DATOS'])
				{
					$_SESSION['CONTROL_CIERRE']['DATOS']['ID']=$_REQUEST['id'];
					$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']=$_REQUEST['caja'];
					$_SESSION['CONTROL_CIERRE']['DATOS']['SW']=$_REQUEST['sw_recibo'];
					$_SESSION['CONTROL_CIERRE']['DATOS']['DPTO']=$_REQUEST['dpto'];
					$_SESSION['CONTROL_CIERRE']['DATOS']['DESC']=$_REQUEST['descripcion'];
					$_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT']=$_REQUEST['sw'];
					$_SESSION['CONTROL_CIERRE']['DATOS']['CUENTATIPO']=$_REQUEST['cuenta_tipo'];
				}
				$this->salida.= ThemeAbrirTabla("ARCHIVADOR DE CIERRES ANTERIORES.");
				$this->Encabezado();
				$this->User_Encabezado($_SESSION['CONTROL_CIERRE']['DATOS']['ID'],$_SESSION['CONTROL_CIERRE']['DATOS']['DESC']);
				$accion=ModuloGetURL('app','Control_Cierre','user','BusquedaFechas',array('criterio'=>$_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT']));
				$this->salida .= "            <form name=\"formalistarr\" action=\"$accion\" method=\"post\">";
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">BUSCADOR AVANZADO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				
				$this->salida.="<td width=\"10%\">FECHA:</td>";
				$this->salida .="<td width=\"50%\" align='center'><input type='text' class='input-text' 	name = 'busqueda'  size=\"11\" maxlength=\"10\"  value =\"$buscar\">&nbsp;".ReturnOpenCalendario('formalistarr','busqueda','-')."</td>" ;
				
				$this->salida.="<td width=\"30%\">TODAS LAS FECHAS</td>";
				$this->salida.="<td width=\"10%\" align = left >";
				$this->salida.="<input type=checkbox  name ='fech'>";
				$this->salida.="</td>";
				$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name= 'buscar' type=\"submit\" value=\"BUSCAR\"></td>";
			  $this->salida.="</tr>";
				
				$this->salida.="</form>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				
				switch($sw)
				{
					case 1:
					{
						$nom='Caja Facturadora';
						break;	
					}
					case 2:
					{
						$nom='Caja Hospitalaria';
						break;	
					}

				}
				if($_REQUEST['busqueda'])
				{
					$cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$nom."'&nbsp;";
				}
				else
				{
					$cadena="Buscador Avanzado: Busqueda";
				}
				$this->salida.="  <td align=\"left\" colspan=\"5\">$cadena</td>";
				$this->salida.="</tr>";
				$this->salida.="</table><br>";

			
				if(!empty($vect) AND $vect !='show')
				{
					//$_SESSION['CONTROL_CIERRE']['VECT']=$vect;
					//$_SESSION['CONTROL_CIERRE']['SW']=$sw;
					$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
	
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="function mOvr(src,clrOver) {;\n";
					$mostrar.="src.style.background = clrOver;\n";
					$mostrar.="}\n";
	
					$mostrar.="function mOut(src,clrIn) {\n";
					$mostrar.="src.style.background = clrIn;\n";
					$mostrar.="}\n";
					$mostrar.="</script>\n";
					$this->salida .="$mostrar";
	
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"modulo_table_title\"><td colspan=\"5\">LISTADOS DE CIERRES &nbsp;".$_SESSION['CONTROL_CIERRE']['DATOS']['DESC']."</td></tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"10%\">No Cierre</td>";
					$this->salida.="  <td width=\"50%\">Fecha Cierre</td>";
					//$this->salida.="  <td width=\"5%\">Total Documentos</td>";
					$this->salida.="  <td width=\"40%\">Observacion</td>";
					$this->salida.="  <td></td>";
					$this->salida.="</tr>";
					$efectivo=$tarjeta=$abono=$cheque=$bonos=$devol=0;
					for($i=0;$i<sizeof($vect);$i++)
					{
												if( $i % 2){ $estilo='modulo_list_claro';}
												else {$estilo='modulo_list_oscuro';}
												$this->salida.="<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#A2ACBB');>";
												$this->salida.="  <td align=\"left\">".$vect[$i][cierre_de_caja_id]."</td>";

// 												if($_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT']==1)
// 												{
// 													$numeros=$this->SacarTotalDocumentos($vect[$i][cierre_de_caja_id],$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']);
// 												}
// 												elseif($_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT']==2)
// 												{
// 													$dev=$this->SacarTotalDevHosp($vect[$i][cierre_caja_id],$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']);
// 													$numeros=$this->SacarTotalDocumentosHosp($vect[$i][cierre_caja_id],$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']);
// 												}
// 
												$fecha=$this->FormateoFechaLocal($vect[$i][fecha_registro]);
												$this->salida.="  <td align=\"center\">$fecha</td>";
												//$this->salida.="  <td align=\"left\">".$numeros[no]."</td>";
												$observa=substr($vect[$i][observaciones],0,70);
												if($observa){$observa.= " "."...";}
												$this->salida.="  <td width=\"35%\" align=\"left\">$observa</td>";unset($observa);

 												if($_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT']==1)
												{
													$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','IRCierresAnteriores',array('descripcion'=>$_SESSION['CONTROL_CIERRE']['DATOS']['DESC'],'dpto'=>$_SESSION['CONTROL_CIERRE']['DATOS']['DPTO'],
													'sw_recibo'=>$_SESSION['CONTROL_CIERRE']['DATOS']['SW'],'cierre'=>$vect[$i][cierre_de_caja_id],'fecha'=>$vect[$i][fecha_registro],'id'=>$vect[$i][usuario_id],'cuenta'=>$vect[$i][cuenta_tipo_id],'caja'=>$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']));
												}
												elseif($_SESSION['CONTROL_CIERRE']['DATOS']['HOSP/FACT']==2)
												{
													$go_to_ultimo=ModuloGetURL('app','Control_Cierre','user','IRCierresAnterioresHosp',array('descripcion'=>$_SESSION['CONTROL_CIERRE']['DATOS']['DESC'],'dpto'=>$_SESSION['CONTROL_CIERRE']['DATOS']['DPTO'],
													'sw_recibo'=>$_SESSION['CONTROL_CIERRE']['DATOS']['SW'],'cierre'=>$vect[$i][cierre_de_caja_id],'fecha'=>$vect[$i][fecha_registro],'id'=>$vect[$i][usuario_id],'caja'=>$_SESSION['CONTROL_CIERRE']['DATOS']['CAJA']));
												}
												$this->salida.="  <td align=\"center\" ><a href='$go_to_ultimo'>VER.</a></td>";
												$this->salida.="</tr>";
												//print_r($dev);
												//$efectivo+=$numeros[total_efectivo];
												//$cheque +=$numeros[total_cheques];
												//$tarjeta +=$numeros[total_tarjetas];
												//$bonos +=$numeros[total_bonos];
												//$abono +=$numeros[total_abono];

									$efectivo+=$vect[$i][total_efectivo];
									$cheque +=$vect[$i][total_cheques];
									$tarjeta +=$vect[$i][total_tarjetas];
									$bonos +=$vect[$i][total_bonos];
									$entrega +=$vect[$i][entrega_efectivo];
									$devol+=$vect[$i][total_devolucion];
									$confirmado +=$vect[$i][valor_confirmado];
									$this->salida.="</tr>";
									
					}
					$this->salida.="</table><br>";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"left\"  colspan='5'>Numero Cierres :&nbsp;".sizeof($vect)."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$this->salida.="<td align=\"left\"  colspan='5'>Total Efectivo :&nbsp;$&nbsp;".FormatoValor($efectivo)."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$this->salida.="<td align=\"left\"  colspan='5'>Total Tarjetas :&nbsp;$&nbsp;".FormatoValor($tarjeta)."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$this->salida.="<td align=\"left\"  colspan='5'>Total Tarjetas :&nbsp;$&nbsp;".FormatoValor($cheque)."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"modulo_list_oscuro\">";
					$this->salida.="<td align=\"left\"  colspan='5'>Total Bonos :&nbsp;$&nbsp;".FormatoValor($bonos)."</td>";
					$this->salida.="</tr>";
/*					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"left\"  colspan='5'><label class='label'>SubTotal :&nbsp;$&nbsp;".FormatoValor($abono)."</label></td>";
					$this->salida.="</tr>";                                                     */
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"left\"  colspan='5'><label class='label'>Total Devoluciones:&nbsp;$&nbsp;".FormatoValor($devol)."</label></td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"left\"  colspan='5'><label class='label'>Total :&nbsp;$&nbsp;".FormatoValor($entrega)."</label></td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td align=\"left\"  colspan='5'><label class='label'>Valor confirmado :&nbsp;$&nbsp;".FormatoValor($confirmado)."</label></td>";
					$this->salida.="</tr>";
					
							
					$this->salida.="</table>";
				}
				elseif($vect =='show')
				{
					$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
					$this->salida .= "<tr><td  align=\"center\"><label class=label_mark>NO HUBO RESULTADOS</label></td></tr>";
					$this->salida.="</table>";
				}
				
				
				/**Parte de volver**/
				$this->salida.="<table align=\"center\" width='40%' border=\"0\">";
				$action2=ModuloGetURL('app','Control_Cierre','user','Menu');
				$this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
				$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
				$this->salida .= "</tr>";
				$this->salida.="</table><br>";
				$this->salida.= ThemeCerrarTabla();
				return true;
	}
	
	
	

    /**
    *        FormaMensaje => muestra mensajes al usuario
    *
    *        @Author DRA.
    *        @access Private
    *        @param string => mensaje a mostrar
    *        @param string => titulo de la tabla
    *        @param string => action del form
    *        @param string => value del input-submit
    *        @return boolean
    */
    function FormaMensaje($mensaje,$titulo,$accion,$boton,$botonC='')
    {
			$this->salida .= ThemeAbrirTabla($titulo,"50%")."<br>";
			$this->salida .= "<table width=\"68%\" align=\"center\" class=\"normal_10\" border='0'>\n";
			$this->salida .= "    <form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
			$this->salida .= "        <tr><td colspan=\"3\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
			if(!empty($boton)){
					$this->salida .= "    <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td>\n";
			}
			else{
					$this->salida .= "    <tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
			}
				$this->salida .= "    </form>\n";
			//este boton solo lo mostraria el reporte de cierre de caja.........
			if($botonC)
			{
				if($botonC=='cierre')
				{
					$id=$_REQUEST['id'];
					$RUTA = $_ROOT ."cache/control_cierre".UserGetUID()."_".$id.".pdf";
					$DIR="printer.php?ruta=$RUTA";
					$RUTA1= GetBaseURL() . $DIR;
					$mostrar ="\n<script language='javascript'>\n";
					$mostrar.="var rem=\"\";\n";
					$mostrar.="  function abreVentana(){\n";
					$mostrar.="    var nombre=\"\"\n";
					$mostrar.="    var url2=\"\"\n";
					$mostrar.="    var width=\"400\"\n";
					$mostrar.="    var height=\"300\"\n";
					$mostrar.="    var winX=Math.round(screen.width/2)-(width/2);\n";
					$mostrar.="    var winY=Math.round(screen.height/2)-(height/2);\n";
					$mostrar.="    var nombre=\"Printer_Mananger\";\n";
					$mostrar.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
					$mostrar.="    var url2 ='$RUTA1';\n";
					$mostrar.="    rem = window.open(url2, nombre, str)};\n";
					$mostrar.="</script>\n";
					$this->salida.="$mostrar";
					$this->GenerarListadoCierreCaja($_REQUEST['id'],$_REQUEST['caja'],$_REQUEST['dpto'],$_REQUEST['cierre']);
				}
				if($botonC=='factura')
				{
					$accion=ModuloGetURL('app','CajaGeneral','user','Reportes');
					$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Imprimir\"></td></tr>\n";
					$this->salida .= "</form>";
				}
				else
				{
						$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"button\" name=\"Aceptar\" value=\"PDF\" onclick='abreVentana()'></td></tr>\n";
						//si esta seccion esta habilitada es por que es cierre de factura osea caja rapida.
						if($_SESSION['REF_DPTO'])
						{
							$url=ModuloGetURL('app','CajaGeneral','user','GenerarRolloFiscal',array('sw'=>1,'go_to'=>$accion));
							$url2=ModuloGetURL('app','CajaGeneral','user','GenerarRolloFiscal',array('sw'=>2,'go_to'=>$accion));
							$this->salida .= "<tr><td colspan=\"2\" align=\"center\"><label><br><a href='$url'>Generar Rollo fiscal Contado</a></label></td>";
							$this->salida .= "<td colspan=\"1\" align=\"center\"><label><br><a href='$url2'>Generar Rollo fiscal Crédito</a></label></td></tr>\n";
						}
				}
			}else{$this->salida .= "    </tr>";}
			$this->salida .= "</table>\n";
			$this->salida .= themeCerrarTabla();
			return true;
    }


}//fin clase

?>
