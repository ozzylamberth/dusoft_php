<?php

/**
 * $Id: app_Os_Pacientes_Hospitalizados_userclasses_HTML.php,v 1.6 2005/07/05 20:01:31 duvan Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo de Listas de Trabajo (PHP).
 * Modulo para el manejo de listas de trabajo
 */

class app_Os_Pacientes_Hospitalizados_userclasses_HTML extends app_Os_Pacientes_Hospitalizados_user
{
		//Constructor de la clase app_Os_Pacientes_Hospitalizados_userclasses_HTML
			function app_Os_Pacientes_Hospitalizados_userclasses_HTML()
			{
									$this->salida='';
									$this->app_Os_Pacientes_Hospitalizados_user();
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
				$this->salida .= "<br><table class=\"modulo_table_title\" border=\"0\"  width=\"80%\" align=\"center\" >";
				$this->salida .= " <tr class=\"modulo_table_title\">";
				$this->salida .= " <td>EMPRESA</td>";
				$this->salida .= " <td>CENTRO UTILIDAD</td>";
				$this->salida .= " <td>DEPARTAMENTO</td>";
				$this->salida .= " </tr>";
				$this->salida .= " <tr align=\"center\">";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['OS_PACIENTES']['NOM_EMP']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['OS_PACIENTES']['NOM_CENTRO']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['OS_PACIENTES']['NOM_DPTO']."</td>";
				$this->salida .= " </tr>";
				$this->salida .= " </table>";
				return true;
		}


/*
* Esta funcion realiza la busqueda de cumplimientos de ordenes de servicio
* según filtros como tipo, documento, nombre y plan
* @return boolean
*/
function FormaMetodoBuscar($arr)
{
		$this->salida="<script language=javascript>\n";
		$this->salida.= "	function load_page() {\n";
		$url=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarOrden');
		$this->salida.= "		var url='$url';\n";
		$this->salida .= "		location.href=url;\n";
		$this->salida.= "	}\n\n";
		$this->salida.="</script>\n";
		$this->salida.="<body onload=compt=setTimeout('load_page();',30000)>\n";

		$this->salida.= ThemeAbrirTabla('ORDEN DE LISTA DE TRABAJO');
		$this->Encabezado();
		if(!empty($arr))
		{
			$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
			$this->salida.= "<table  width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
			for($i=0;$i<sizeof($arr);$i++)
			{
					if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					if($arr[$i][servicio] != $arr[$i-1][servicio])
					{
							$this->salida .= "<tr >";
							$this->salida .= "<td colspan = \"3\">&nbsp;</td>";
							$this->salida .= "</tr>";
							$this->salida.="<tr align=\"center\" class=\"modulo_table_list_title\">";
							$this->salida .= "<td colspan = \"6\">SERVICIO: ".$arr[$i][servicio_descripcion]."</td>";
							$this->salida .= "</tr>";
							if($arr[$i][departamento] != $arr[$i-1][departamento])
						  {
									$this->salida .= "<tr align=\"left\" class=\"modulo_table_title\">";
									$this->salida .= "<td colspan = \"6\">DEPARTAMENTO: ".$arr[$i][dpto]."</td>";
									$this->salida .= "</tr>";
									$this->salida.="<tr align=\"center\" class=\"hc_table_submodulo_list_title\">";
									$this->salida .= "<td width=\"20%\">IDENTIFICACION</td>";
									$this->salida .= "<td width=\"58%\">NOMBRE DEL PACIENTE</td>";
									$this->salida .= "				<td colspan='3' width=\"8%\">ESTADOS</td>";
									$this->salida .= "<td width=\"5%\"></td>";
									$this->salida .= "</tr>";
							}
					}
					else
					{
							if($arr[$i][departamento] != $arr[$i-1][departamento])
						  {
							  $this->salida .= "<tr align=\"left\" class=\"modulo_table_title\">";
								$this->salida .= "<td colspan = \"6\">DEPARTAMENTO: ".$arr[$i][dpto]."</td>";
								$this->salida .= "</tr>";
								$this->salida.="<tr align=\"center\" class=\"hc_table_submodulo_list_title\">";
								$this->salida .= "<td width=\"20%\">IDENTIFICACION</td>";
								$this->salida .= "<td width=\"58%\">NOMBRE DEL PACIENTE</td>";
								$this->salida .= "				<td colspan='3' width=\"8%\">ESTADOS</td>";
								$this->salida .= "<td width=\"5%\"></td>";
								$this->salida .= "</tr>";
							}
					}

					//Edad
					$edad_paciente = CalcularEdad($arr[$i][fecha_nacimiento],date("Y-m-d"));
					$this->salida.="<tr class='$estilo' align='center'>";
					$this->salida.="  <td ><font color='#4D6EAB'>".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</td>";
					$this->salida.="  <td >".$arr[$i][nombre]."</td>";

					unset($arreglo);
					$arreglo=$this->Traer_Estados_Os_maestros($arr[$i][tipo_id_paciente],$arr[$i][paciente_id]);

					if($arreglo[1]=='1')//1 son activas,para pagos //0
					{$img1="cargar.png";$title1="Existen ordenes para Pagar";}else{$img1="checkN.gif";$title1="No hay ordenes para pagos";}

					if($arreglo[2]=='2')//2 son pagas,para cumplimiento 0 ->cargos realizados en la atención no cargados a una cuenta quedando pendiente por cobrar
					{$img2="cargos.png";$title2="Existen ordenes para Cumplimiento";}else{$img2="checkN.gif";$title2="No hay ordenes para Cumplimiento";}

					if($arreglo[3]=='3')//3 son pagas,para atencion
					{$img3="atencion_citas.png";$title3="Existen ordenes para Atencion";}else{$img3="checkN.gif";$title3="No hay ordenes para Atencion";}
					$this->salida .= "				<td width=\"3%\"><img title='$title1' src=\"". GetThemePath() ."/images/$img1\" border='0'></td>";
					$this->salida .= "				<td width=\"3%\"><img title='$title2' src=\"". GetThemePath() ."/images/$img2\" border='0'></td>";
					$this->salida .= "				<td width=\"3%\"><img title='$title3' src=\"". GetThemePath() ."/images/$img3\" border='0'></td>";



					$this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','GetForma',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
					$this->salida.="</tr>";
			}
			$this->salida.="</table>";

			$reporte= new GetReports();
			$mostrar=$reporte->GetJavaReport('app','Os_Pacientes_Hospitalizados','hospitalizados_laboratorio',array('departamento'=>$_SESSION['OS_PACIENTES']['DPTO'], 'datalab'=>$_SESSION['DATALAB']['DPTO_DATALAB'], 'empresa'=>$_SESSION['OS_PACIENTES']['NOM_EMP'], 'centro'=>$_SESSION['OS_PACIENTES']['NOM_CENTRO'], 'dpto'=>$_SESSION['OS_PACIENTES']['NOM_DPTO']),array('rpt_name'=>'pacientes_hopitalizados','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
			$nombre_funcion=$reporte->GetJavaFunction();
			$this->salida .=$mostrar;


			$this->salida.="<center>";
			$this->salida.="<p><label class=\"label_mark\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;&nbsp;IMPRIMIR</a></p>";
			$this->salida.="</center>";


			$actionH=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','LlamarFormaBuscar');
			$this->salida.="<p align=\"center\" class=\"label\"><a href=\"$actionH\">SOLICITUD MANUAL</a></p>";
			$this->salida .=$this->RetornarBarra();
		}
		else
		{
			$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table><br>";
			$actionH=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','LlamarFormaBuscar');
			$this->salida.="<p align=\"center\" class=\"label\"><a href=\"$actionH\">SOLICITUD MANUAL</a></p>";

		}

		$this->salida.= "<table width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
		$actionM=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','main');
		$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
		$this->salida .= "</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
}



	/*
	* Esta funcion te muestra en detalle las ordenes de servicio
	* filtrados por(tipo_afiliado_id,rango,orden_servicio_id),y separarados por plan.
	* @string nom
	* @string tipo
	* @int id
	* @return boolean
	*/


function FrmOrdenar()
{

		$RUTA = "app_modules/Os_Pacientes_Hospitalizados/OrdenesVencidas.php?tipo_id=".$_SESSION['DATOS_PACIENTE']['tipo_id']."&paciente=".$_SESSION['DATOS_PACIENTE']['paciente_id']."&dpto=".$_SESSION['OS_PACIENTES']['DPTO']."";
		$this->salida .= "<SCRIPT language='javascript'>\n";
		$this->salida.="  function abreVentana(){\n";
		$this->salida.="var rem=\"\";\n";
		$this->salida.="    var nombre=\"\"\n";
		$this->salida.="    var url2=\"\"\n";
		$this->salida.="    var width=\"700\"\n";
		$this->salida.="    var height=\"400\"\n";
		$this->salida.="    var winX=width;\n";
		$this->salida.="    var winY=height;\n";
		$this->salida.="    var nombre=\"Printer_Mananger\";\n";
		$this->salida.="    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
		$this->salida.="    var url2 ='$RUTA';\n";
		$this->salida.="    rem = window.open(url2, nombre, str);}\n";
		$this->salida .= "</SCRIPT>\n";

		$this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
		$this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .="".$this->SetStyle("MensajeError")."";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['tipo_id']."&nbsp;".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td></tr>";

		if(!empty($_SESSION['DATOS_PACIENTE']['edad']))
		{  $edad=$_SESSION['DATOS_PACIENTE']['edad'];  }
		else
		{
				$FechaNacimiento=$this->Edad($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
				$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
				$edad=$EdadArr['edad_aprox'];
		}
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">EDAD PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$edad."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">ORDENES VENCIDAS </td><td class=\"modulo_list_claro\" align=\"left\"><a href='javascript:abreVentana()'>VER ORDENES VENCIDAS</a></td></tr>";
		$this->salida .= "</table><BR>";

		//trae las ordenes de servicio de estado 3 .
		unset($vector3);
		$vector3=$this->TraerOrdenesServicio_estado3($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
		if($vector3)
		{
				$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";

				//ALERTA
				if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
				{
				  $this->salida.="  <td align=\"left\" colspan=\"8\">PARA ATENCION</td>";
				}
				else
				{
				  $this->salida.="  <td align=\"left\" colspan=\"7\">PARA ATENCION</td>";
				}
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"8%\">ITEM</td>";
				$this->salida.="  <td width=\"5%\">CANT</td>";
				$this->salida.="  <td width=\"10%\">CARGO</td>";
				$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
				$this->salida.="  <td width=\"20%\">VENCIMIENTO</td>";
  			//ALERTA
				if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
				{
				  $this->salida.="  <td width=\"20%\">ASIGNADO A</td>";
				}
				$this->salida.="  <td width=\"8%\"></td>";
				$this->salida.="  <td width=\"5%\">Sel</td>";

        //ALERTA
				unset($_SESSION['OS_ATENCION']['NUMERO_C']);
				for($i=0;$i<sizeof($vector3);$i++)
				{
					if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}

					$numero=$this->TraerNumeroCumplimiento($vector3[$i][numero_orden_id]);
					//si este $numero llega vacio es por q a la tabla os_cumplimiento_detalle
					//le han borrado el numero_orden_id..

				  //ALERTA
					if(!$_SESSION['OS_ATENCION']['NUMERO_C'])
					{
					  	//ALERTA
							$_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
							$sw_imprimir=0;
							//aqui va el cambio <duvan>
							$accion1=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento']));
							$this->salida .= "<form name=\"formaimp\"  action=".$accion1." method=\"post\">";
					}
					else
					{
							//ALERTA
						  if($_SESSION['OS_ATENCION']['NUMERO_C']==$numero['numero_cumplimiento'])
							{
							  	//ALERTA
									$_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
									$sw_imprimir=0;
							}
							else
							{
								  //ALERTA
									if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
									{$nu=8;}else{$nu=7;}
									$sw_imprimir=0;
									$accion1=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento']));
									$this->salida.="  <tr class='$estilo'><td  colspan='$nu' align=\"center\"><input type='submit' class='input-submit' name='imp' value='imprimir'></td></tr>";
									//ALERTA
									$_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
									$this->salida.="</form>";
									$this->salida .= "<form name=\"formaimp\"  action=".$accion1." method=\"post\">";
							}
					}

					$vecimiento=$vector3[$i][fecha_vencimiento];
					$arr_fecha=explode(" ",$vecimiento);
					$this->salida.="<tr>";
					$this->salida.="  <td class='$estilo' align=\"center\" >".$vector3[$i][numero_orden_id]."</td>";
					$this->salida.="  <td class='$estilo' align=\"center\" >".$vector3[$i][cantidad]."</td>";
					$this->salida.="  <td class='$estilo' align=\"center\" >".$vector3[$i][cargoi]."</td>";
					$this->salida.="  <td class='$estilo' align=\"center\" >".$vector3[$i][des1]."</td>";

					if(strtotime($arr_fecha[0]) > strtotime(date("Y-m-d")))
					{
					  	$this->salida.="  <td  class='$estilo' align=\"center\" >$arr_fecha[0]</td>";
							//ALERTA
							if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
							{
									$this->salida.="  <td class='$estilo' align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";
							}
							$this->salida.="  <td class='$estilo' align=\"center\"><label class='label_mark'>CUMPLIDA</label></td>";
					}
					else
					{
							//ALERTA
							if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
							{$this->salida.="  <td class='$estilo' align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
							$this->salida.="  <td  class='$estilo' align=\"center\"><label class='label_mark'>VENCIDO</label></td>";
							$this->salida.="  <td class='$estilo' align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
					}
					//<duvan>aqui se sigue
					$this->salida.="<td class='$estilo' align=\"center\"><input type=\"checkbox\" value=\"".$vector3[$i][numero_orden_id]."\" name=\"sel[]\"></td>";
          $this->salida.="</tr>";

  				if($sw_imprimir==1)
					{
							$accion1=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'numero_orden_id'=>$vector3[$i][numero_orden_id]));
							//ALERTA
							if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
							{$numero=8;}else{$numero=7;}
							$this->salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><input type='submit' class='input-submit' name='imp' value='imprimir'></td></tr>";
					}

					if($i==sizeof($vector3)-1)
					{
							//ALERTA
							if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
							{$numero=8;}else{$numero=7;}
							$this->salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><input type='submit' class='input-submit' name='imp' value='imprimir'></td></tr>";
							$this->salida.="</form>";
					}
				}
				$this->salida.="</table>";
				//ALERTA
				unset($_SESSION['OS_ATENCION']['NUMERO_C']);
		}

		$vector=$this->TraerOrdenesServicio($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']); //sacamos las ordenes de sevicio que desea pagar.
		//trabajo de claudia

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
						$this->salida.="  <td align=\"left\" colspan=\"8\">PLAN&nbsp;&nbsp;".$vector[$i][descripcion]."&nbsp;&nbsp;".$vector[$i][plan_descripcion]."</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td width=\"5%\">ORDEN</td>";
						$this->salida.="  <td width=\"5%\">ITEM</td>";
						$this->salida.="  <td width=\"7%\">CANTIDAD</td>";
						$this->salida.="  <td width=\"7%\">CARGO</td>";
						if($_SESSION['DATALAB']['DPTO_DATALAB']==1)
						{
						    $this->salida.="  <td width=\"25%\">DESCRIPCION</td>";
							$this->salida.="  <td width=\"16%\">DATALAB</td>";
						}
						else
						{
						    $this->salida.="  <td colspan = 2 width=\"41%\">DESCRIPCION</td>";
						}
						$this->salida.="  <td width=\"10%\">VENCIMIENTO</td>";
						$this->salida.="  <td width=\"5%	\">OPCION</td>";
						$this->salida .= "           <form name=\"formita\" action=\"".ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarCuentaActiva',array('plan_id'=>$vector[$k][plan_id]))."\" method=\"post\">";
						$this->salida.="</tr>";
				}
				while($vector[$i][plan_id]==$vector[$k][plan_id]
				AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
				AND $vector[$i][rango]==$vector[$k][rango]
				AND $vector[$i][servicio]==$vector[$k][servicio]
        //opcion para pintar siempre que la orden de servicio sea distinta se active el boton cumplir
				AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id]
				)
				{
						$this->salida.="<tr class='modulo_list_claro'>";
						$this->salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"5%\">".$vector[$k][orden_servicio_id]."</td>";
						$this->salida.="  <td colspan=\"7\">";
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
								$this->salida.="  <td align='center' class=$estilo width=\"6%\"<label class='label_mark'>".$vector[$l][numero_orden_id]."</label></td>";
								$this->salida.="  <td colspan=6 >";
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
										$this->salida.="  <td width=\"11%\" align=\"center\" >".$vector[$m][cantidad]."</td>";
										$this->salida.="  <td width=\"11%\" align=\"center\" >".$vector[$m][cargoi]."</td>";
                    if($_SESSION['DATALAB']['DPTO_DATALAB']==1)
										{
												$this->salida.="  <td width=\"37%\">".$vector[$m][des1]."</td>";
												//meter a datalab
												$spia = 1;
												$codigos=$this->Cargar_Codigos_Datalab($vector[$m][cargoi]);
												if (sizeof($codigos) > 1)
												{
													$this->salida.="  <td align=\"center\" width=\"24%\"><select name=\"codigo".$vector[$l][numero_orden_id]."\" class=\"select\">";
													$this->salida .=" <option value= '-1' selected>--Seleccione--</option>";
													$this->LLenarComboCodigos($codigos,$_REQUEST['codigo']);
													$this->salida .= "</select></td>";
												}
												elseif (sizeof($codigos) == 1)
												{
													$this->salida.="  <td align=\"center\" width=\"24%\"><input readonly type=\"text\" class=\"input-text\" name=\"codigo".$vector[$l][numero_orden_id]."\" size = 15  value = ".$codigos[0][codigo_datalab]."></td>";
												}
												else
												{
													$this->salida.="  <td align=\"center\" width=\"24%\">SIN EQUIVALENCIA</td>";
													$spia=0;
												}
										}
										else
										{
												$this->salida.="  <td colspan = 2 width=\"61%\">".$vector[$m][des1]."</td>";
										}

										if(strtotime($arr_fecha[0]) > strtotime(date("Y-m-d")))
										{
											$this->salida.="<td width=\"26%\" align=\"center\" >$arr_fecha[0]</td>";
											$this->salida.="<td width=\"15%\" align=\"center\" >";
											$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
											$this->salida.="<tr class=$estilo>";
											$citas = $this->Revision_Cita($vector[$m][numero_orden_id],$vector[$m][cargoi]);
											if($citas[sw_cita]=='1')
											{
												if(!empty($citas[existe_cita][numero_orden_id]))
												{
														//$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','AsignacionProfCitasImagen',array('numero_orden_id'=>$vector[$m][numero_orden_id],'tipo_id_paciente'=>$tipo,'paciente_id'=>$id,'nombre'=>$nom));
														//	$this->salida.="<td width=\"15%\" align=\"center\" ><a href=".$accion."><img src=\"". GetThemePath() ."/images/cumplimientos_citas.png\" border=0></a></td>";
												}
												else
												{
											  		//$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','LlamaProgramCitasImagen',array('numero_orden_id'=>$vector[$m][numero_orden_id],'tipo_id_paciente'=>$tipo,'paciente_id'=>$id,'nombre'=>$nom,'tipo_equipo'=>$citas[tipo_equipo_imagen_id]));
														//	$this->salida.="<td width=\"15%\" align=\"center\" ><a href=".$accion."><img src=\"". GetThemePath() ."/images/atencion_citas.png\" border=0 ></a></td>";
												}
												//validando el spia de datalab
	                      if ($_SESSION['DATALAB']['DPTO_DATALAB']==1)
												{
														if($spia==1)
														{
															$this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
														}
														else
														{
															$this->salida.="  <td align=\"center\" width=\"15%\">&nbsp;&nbsp;</td>";
														}
												}
												else
												{
			                      $this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
												}

											}
											else
											{
													$this->salida.="<td width=\"15%\" align=\"center\">&nbsp;&nbsp;</td>";
                          //validando el spia de datalab
													if ($_SESSION['DATALAB']['DPTO_DATALAB']==1)
													{
														if($spia==1)
														{
															$this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
														}
														else
														{
															$this->salida.="  <td align=\"center\" width=\"15%\">&nbsp;&nbsp;</td>";
														}
													}
													else
													{
		                          $this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
													}
											}
											$this->salida.="</tr>";
											$this->salida.="</table>";
											$this->salida.="</td>";
										}
										else
										{
												$this->salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
                        $this->salida.="<td width=\"15%\" align=\"center\" >";
											  $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
											  $this->salida.="<tr class=$estilo>";
												$this->salida.="<td width=\"15%\" align=\"center\">&nbsp;&nbsp;</td>";
												$this->salida.="  <td width=\"15%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
												$this->salida.="</tr>";
											  $this->salida.="</table>";
											  $this->salida.="</td>";
										}
										$this->salida.="</tr>";
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
				$this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir></td>";
				$this->salida.="</form>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$i=$k;
		}

		$this->salida.="<br><br><table align=\"center\" width='20%' border=\"0\">";
		$action2=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarOrden',array());
		$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"<< Volver\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table><br>";
		$this->salida .= ThemeCerrarTabla();
		return true;
}



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
						{   $vec[$v]=$v1;   }
				}
				$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarOrden',$vec);
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

function LiquidacionOrden($vector,$op,$PlanId,$codigos_datalab,$vector_des='',$forma)
{
		IncludeLib("tarifario_cargos");
		$Cuenta=0;
		$nom=urldecode($nom);
		$this->salida.= ThemeAbrirTabla('LIQUIDACION ORDEN DE SERVICIOS MEDICOS ');
		//$this->Encabezado();
		$this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['tipo_id']."&nbsp;".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td></tr>";
	  $this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">EDAD PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['edad']."</td></tr>";
		$this->salida .= "</table><BR>";
		//$this->salida.="<BR><table  align=\"center\" border=\"2\"  width=\"90%\">";
		if($vector)
		{
				$sw_hay_cuenta=true;//este swiche me indica si hubo  no cuenta, asi determino como liquido
				//el cargo con cuenta o sin cuenta.
				$this->salida.="<BR><table  align=\"center\" bordercolor='#4D6EAB' border=\"1\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td align=\"left\" colspan=\"5\">CUENTA&nbsp;No.&nbsp;".$vector[0][numerodecuenta]."&nbsp;&nbsp;".$vector[$i][plan_descripcion]."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <td width=\"20%\">PLAN</td>";
				$this->salida.="  <td width=\"10%\">TOTAL CUENTA</td>";
				$this->salida.="  <td width=\"20%\">SERVICIO</td>";
				$this->salida.="  <td width=\"10%\">SALDO</td>";
				$this->salida.="  <td width=\"20%\"></td>";
				//$this->salida.="  <td width=\"10%\">Todos&nbsp;<input type=checkbox name=sel onclick=chequeoTotal(this.form,this.checked)></td>";
				//$this->salida .= "           <form name=\"formo\" action=\"".ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarCuentaActiva',array('id_tipo'=>$tipo,'id'=>$id,'plan_id'=>$vector[$k][plan_id]))."\" method=\"post\">";
				$this->salida.="</tr>";
				$Cuenta=$vector[0][numerodecuenta];
				for($i=0;$i<sizeof($vector);$i++)
				{
						$this->salida.="<tr class='modulo_list_claro' align='center'>";
						$this->salida.="  <td >".$vector[$i][tercero]."&nbsp; - &nbsp;".$vector[$i][plan_descripcion]."</td>";
						$this->salida.="  <td >".$vector[$i][total_cuenta]."</td>";
						$this->salida.="  <td >".$vector[$i][descripcion]."</td>";
						$this->salida.="  <td >".$vector[$i][saldo]."</td>";
						$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','InsertarCargoCuenta',array('cuenta'=>$Cuenta,'op'=>$op,'plan'=>$PlanId));
						$this->salida.="  <td ><a href='$accion'>[&nbsp;CARGAR CUENTA&nbsp;]</a></td>";
						$this->salida.="</tr>";
				}
				$this->salida.="</table>";
		}
		else
		{
				$sw_hay_cuenta=false;
		}

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td width=\"5%\">ITEM</td>";
		$this->salida.="  <td width=\"5%\">CARGO</td>";
		$this->salida.="  <td width=\"5%\">TARIF.</td>";
		$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
		$this->salida.="  <td width=\"20%\">SERVICIO</td>";
		$this->salida.="  <td width=\"5%\">CANT.</td>";
		$this->salida.="  <td width=\"15%\">VAL. NO CUBIERTO</td>";
		$this->salida.="  <td width=\"15%\">VAL. EMPRESA</td>";
		$this->salida.="  <td width=\"15%\">VALOR CARGO</td>";
		$this->salida.="</tr>";
		$j=0;

		if($sw_hay_cuenta==false)
		{
				$total_cargo=$total_paciente=$total_empresa=0;
				$cargo_liq=array(); //arreglo que contiene los cargos y demas datos para liquidarlos.
				$Arr_Descripcion[]=array();//arreglo para guardar la descripcion y los servicios.
				//$i=0;

				foreach($op as $index=>$codigo)
				{
						$valores=explode(",",$codigo);
						$datos=$this->DatosOs($valores[0]);
						for($i=0;$i<sizeof($datos);$i++)
						{
							$dat[$j]['cargo']=$datos[$i]['cargo'];
							$dat[$j]['tarifario_id']=$datos[$i]['tarifario_id'];
							$dat[$j][descripcion]=$datos[$i]['descripcion'];
							$dat[$j][numero_orden_id]=$datos[$i]['numero_orden_id'];
							$dat[$j][os_maestro_cargos_id]=$datos[$i]['os_maestro_cargos_id'];
							$Arr_Descripcion[$j]=array('des_cargo'=>$valores[6],'servicio'=>$valores[7],'des_servicio'=>$valores[8],'numero_orden_id'=>$valores[0],'cargo'=>$valores[1]);
							$cargo_liq[]=array('tarifario_id'=>$datos[$i]['tarifario_id'],'cargo'=>$datos[$i]['cargo'],'cantidad'=>$datos[$i]['cantidad'],'autorizacion_int'=>$datos[$i]['autorizacion_int'],'autorizacion_ext'=>$datos[$i]['autorizacion_ext']);
							$j++;
						}
				}
				$cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,'','',$vector_des, $datos[0][plan_id] ,$datos[0][tipo_afiliado_id] ,$datos[0][rango] ,$datos[0][semanas_cotizacion],$datos[0][servicio],'','');
				$afiliado=$datos[0][tipo_afiliado_id];
				$rango=$datos[0][rango];
				$sem=$datos[0][semanas_cotizacion];
				$auto=$datos[0][autorizacion_int];
				$serv=$datos[0][servicio];
				$k=0;
				foreach($cargo_fact[cargos] as $w=>$v)
				{
						$this->salida .= "<tr>";
						if( $k % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class='$estilo' align='center'>";
						$this->salida.="  <td >".$Arr_Descripcion[$k][numero_orden_id]."</td>";
						$this->salida.="  <td >".$v[cargo]."</td>";
						$this->salida.="  <td >".$v[tarifario_id]."</td>";
						$this->salida.="  <td >".$v[descripcion]."</td>";
						$this->salida.="  <td >".$Arr_Descripcion[$k][des_servicio]."</td>";
						$this->salida.="  <td >".$v[cantidad]."</td>";
						$this->salida.="  <td >".$v[valor_no_cubierto]."</td>";
						$this->salida.="  <td >".$v[valor_cubierto]."</td>";
						$this->salida.="  <td >".$v[valor_cargo]."</td>";
						$total_cargo=$total_cargo+$v[valor_cargo];
						$valpac=$v[copago]+$v[cuota_moderadora]+$v[valor_no_cubierto];
						$total_paciente=$total_paciente + $valpac;
						$total_empresa=$total_empresa + $v[valor_empresa];
						$this->salida.="</tr>";
						$cargo_arr[]=array('tarifario_id'=>$v['tarifario_id'],'descripcion'=>$v[descripcion],'os_maestro_cargos_id'=>$dat[$k]['os_maestro_cargos_id'],'numero_orden_id'=>$dat[$k]['numero_orden_id'],'cargo'=>$v['cargo'],'des_servicio'=>$Arr_Descripcion[$k][des_servicio],'cantidad'=>$v['cantidad'],'valor_cargo'=>$v[valor_cargo],'valor_no_cubierto'=>$v[valor_no_cubierto],'autorizacion_int'=>$v['autorizacion_int'],'autorizacion_ext'=>$v['autorizacion_ext']);
						$k++;
			  }
				$sw_link=true;//esta variable permite q salga el link de pago en caja..
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
						if ($dbconn->ErrorNo() != 0)
						{
									$this->error = "Error al Cargar el Modulo";
									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
									return false;
						}
						while(!$resulta->EOF)
						{
								$Liq=LiquidarCargoCuenta($Cuenta,$resulta->fields[0],$resulta->fields[1],$valores[5],0,0,false,false,0,$valores[7],$PlanId,$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
								$afiliado=$datos[tipo_afiliado_id];
								$rango=$datos[rango];
								$sem=$datos[semanas_cotizacion];
								$auto=$datos[autorizacion_int];
								$serv=$datos[servicio];
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr class='$estilo' align='center'>";
								$this->salida.="  <td >".$valores[0]."</td>";
								$this->salida.="  <td >".$resulta->fields[1]."</td>";
								$this->salida.="  <td >".$resulta->fields[0]."</td>";
								$desc=$this->TraerNombreTarifario($resulta->fields[0],$resulta->fields[1]);
								$this->salida.="  <td >".$desc."</td>";
								$this->salida.="  <td >".$valores[8]."</td>";
								$this->salida.="  <td >".$Liq[cantidad]."</td>";
								$this->salida.="  <td >".$Liq[valor_no_cubierto]."</td>";
								$this->salida.="  <td >".$Liq[valor_empresa]."</td>";
								$this->salida.="  <td >".$Liq[valor_cargo]."</td>";
								$total_cargo=$total_cargo+$Liq[valor_cargo];
								$total_paciente=$total_paciente + $Liq[total_paciente];
								$total_empresa=$total_empresa + $Liq[valor_empresa];
								$this->salida.="</tr>";
								$cargo_arr[]=array('tarifario_id'=>$resulta->fields[0],'numero_orden_id'=>$valores[0],'descripcion'=>$desc,'cargo'=>$resulta->fields[1],'des_servicio'=>$valores[8],'cantidad'=>$Liq[cantidad],'valor_cargo'=>$Liq[valor_cargo]);
								$i++;
								$resulta->MoveNext();
						}
				}
		}
		$this->salida.="<tr class='$estilo' align='center'>";
		$this->salida.="  <td colspan='9'>&nbsp;&nbsp;</td>";
		$this->salida.="</tr>";
		$nombres=$this->BuscarNombreCop($datos[0][plan_id]);

		$valpac=$cargo_fact[cuota_moderadora];
		if($cargo_fact[valor_cuota_moderadora]>0)
		{
				$this->salida.="<tr align='left'>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='6'>".$nombres[nombre_cuota_moderadora]."</td>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_cuota_moderadora])."</td>";
				$this->salida.="</tr>";
		}

		if($cargo_fact[valor_cuota_paciente]>0)
		{
				$this->salida.="<tr align='left'>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='6'>".$nombres[nombre_copago]."</td>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_cuota_paciente])."</td>";
				$this->salida.="</tr>";
		}

		if($cargo_fact[valor_no_cubierto]>0)
		{
				$this->salida.="<tr align='left'>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='6'>Valor No Cubierto</td>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_no_cubierto])."</td>";
				$this->salida.="</tr>";
		}

  	if($cargo_fact[valor_gravamen_paciente]>0)
		{
				$this->salida.="<tr align='left'>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='6'>IVA Paciente</td>";
				$this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_gravamen_paciente])."</td>";
				$this->salida.="</tr>";
		}

		$this->salida.="<tr align='left'>";
		$this->salida.="  <td class=\"modulo_table_list_title\" colspan='6'>TOTAL</td>";
		$this->salida.="  <td colspan='3' class=\"modulo_table_list_title\">".FormatoValor($cargo_fact[valor_total_paciente])."</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr align='right'>";
		$this->salida.="  <td  colspan='6'>&nbsp;&nbsp;</td>";

		//esta es la insercion de esta parte.pilas.........
		$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','InsertarCargo',array('cuenta'=>$Cuenta,'op'=>$op,'plan'=>$PlanId,"codigos_datalab"=>$codigos_datalab));
		$this->salida.="  <td colspan='3'><img src=\"".GetThemePath()."/images/informacion.png\"<label class='label_mark'><a href='".$accion."'>CUMPLIMIENTO</a></label></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<br><br><table align=\"center\" width='40%' border=\"0\">";
		if(!empty($form))
		{  $action2=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','FrmOrdenar',array('paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));  }
		else
		{  $action2=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','FormaCumplimiento',array('paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));  }
		$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
		$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
		$this->salida .= "</tr>";
		$this->salida.="</table><br>";

		$this->salida .= ThemeCerrarTabla();
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
		if($boton)
		{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
		}
		else
		{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
		}
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
}


//NUEVA FUNCIONES

function LLenarComboCodigos($codigos,$cod='')
{
		for($i=0; $i<sizeof($codigos); $i++)
		{
		    $valor = $codigos[$i][codigo_datalab];
				if($codigos[$i][codigo_datalab]==$cod)
				{
						$this->salida .=" <option value=\"$valor\" selected>".$valor."</option>";
				}
				else
				{
						$this->salida .=" <option value=\"$valor\">".$valor."</option>";
				}
		}
}

//--------------------------------------SOLICITUD MANUAL DARLING--------------------------
	function FormaBuscar()
	{
				$action=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarPaciente');
      	$this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL - BUSCAR PACIENTE ( '.$_SESSION['OS_PACIENTES']['NOM_DPTO'].' )');
				$this->salida .= "			      <table width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "             <form name=\"formabuscar\" action=\"$action\" method=\"post\">";
				$this->salida .= $this->SetStyle("MensajeError");
        $responsables=$this->responsables();
				if(!empty($responsables))
				{
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("plan")."\">PLAN: </td><td><select name=\"plan\" class=\"select\">";
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
				}
				else
				{
						$this->salida .= "				       <tr><td class=\"".$this->SetStyle("plan")."\">PLAN: </td><td>";
						$this->salida .="NO HAY PLANES ACTIVOS PARA LA EMPRESA</td></tr>";
				}
				$this->salida .= "				       <tr><td  class=\"".$this->SetStyle("Tipo")."\">TIPO DOCUMENTO: </td><td><select name=\"Tipo\" class=\"select\">";
        $tipo_id=$this->tipo_id_paciente();
				$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
				foreach($tipo_id as $value=>$titulo)
				{
						if($value==$_REQUEST['Tipo'])
						{  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";  }
						else
						{  $this->salida .=" <option value=\"$value\">$titulo</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "				       <tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\"></td></tr>";
				$campo=$this->BuscarCamposObligatorios();
				if($campo[historia_prefijo][sw_mostrar]==1)
				{
						$this->salida .= "    <tr height=\"20\">";
						$this->salida .= "      <td class=\"".$this->SetStyle("prefijo")."\">PREFIJO: </td>";
						$this->salida .= "      <td><input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"".$_REQUEST['prefijo']."\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				if($campo[historia_numero][sw_mostrar]==1)
				{
						$this->salida .= "      <td class=\"".$this->SetStyle("historia")."\">No. HISTORIA: </td>";
						$this->salida .= "      <td  height=\"25\"><input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"".$_REQUEST['historia']."\" class=\"input-text\"></td>";
						$this->salida .= "      <td></td>";
						$this->salida .= "    </tr>";
				}
				$this->salida .= "				       <tr><td align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"><br></td></form>";
				$actionM=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarOrden');
				$this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form></tr>";
				$this->salida .= "			     </table>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}


	/**
	*
	*/
	function DatosPaciente()
	{
				if(empty($_SESSION['SOLICITUD']['PACIENTE']['nombre']))
				{
						$nom=$this->NombrePaciente($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
						$_SESSION['DATOS_PACIENTE']['nombre']=$nom['nombre'];
				}
				$this->salida .= "		 <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">IDENTIFICACION: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['tipo_id_paciente']." ".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"10%\">PLAN:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['plan_descripcion']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= " 			</table><BR>";
	}

	/**
	*
	*/
	function FormaDatosSolicitud()
	{

				if(empty($_REQUEST['Serv']))
				{   $_REQUEST['Serv']=$_SESSION['OS_PACIENTES']['SERVICIO'];   }
				$this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL ( '.$_SESSION['OS_PACIENTES']['NOM_DPTO'].' )');
				$this->DatosPaciente();
				$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','GuardarDatosSolicitud');
				$this->salida .= "             <form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "			      <table width=\"60%\" align=\"center\" border=\"0\">";
				$this->salida .= $this->SetStyle("MensajeError");
				if(empty($_REQUEST['Fecha']))
				{  $_REQUEST['Fecha']=date("d/m/Y");  }
				$this->salida .= "	<tr>";
				$this->salida .= "	<td class=\"".$this->SetStyle("Fecha")."\">FECHA: </td>";
				$this->salida .= "	<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"".$_REQUEST['Fecha']."\">";
				$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Fecha','/')."</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	<tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Serv")."\">SERVICIO: </td>";
				$this->salida .= "				      <td colspan=\"2\"><select name=\"Serv\" class=\"select\">";
				$ser=$this->TiposServicios();
				$this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
				for($i=0; $i<sizeof($ser); $i++)
				{
						if($ser[$i][servicio]==$_REQUEST['Serv'])
						{  $this->salida .=" <option value=\"".$ser[$i][servicio]."\" selected>".$ser[$i][descripcion]."</option>";  }
						else
						{  $this->salida .=" <option value=\"".$ser[$i][servicio]."\">".$ser[$i][descripcion]."</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Origen")."\">ENTIDAD SOLICITA: </td>";
				$this->salida .= "			        <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Origen\" value=\"".$_REQUEST['Origen']."\" size=\"40\" maxlength=\"50\"></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Medico")."\">MEDICO EXT: </td>";
				$this->salida .= "			        <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Medico\" value=\"".$_REQUEST['Medico']."\" size=\"40\" maxlength=\"50\"></td>";
				$this->salida .= "			     </tr>";
//------------------------------------------------
				$this->salida .= "	<tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("MedInt")."\">MEDICO INT: </td>";
				$this->salida .= "				      <td colspan=\"2\"><select name=\"MedInt\" class=\"select\">";
				$pro=$this->Profesionales();
				$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
				for($i=0; $i<sizeof($pro); $i++)
				{
						if($pro[$i][tipo_id_tecero]."||".$pro[$i][tecero_id]==$_REQUEST['MedInt'])
						{  $this->salida .=" <option value=\"".$pro[$i][nombre]."\" selected>".$pro[$i][nombre]."</option>";  }
						else
						{  $this->salida .=" <option value=\"".$pro[$i][nombre]."\">".$pro[$i][nombre]."</option>";  }
				}
				$this->salida .= "              </select></td></tr>";

				$this->salida .= "			        <td class=\"".$this->SetStyle("departamento")."\">DEPARTAMENTO: </td>";
				$this->salida .= "				      <td colspan=\"2\"><select name=\"departamento\" class=\"select\">";
				$dpto=$this->BuscarDepartamento();
				$this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
				for($i=0; $i<sizeof($dpto); $i++)
				{
						if($dpto[$i][departamento]==$_REQUEST['departamento'])
						{  $this->salida .=" <option value=\"".$dpto[$i][descripcion]."\" selected>".$dpto[$i][descripcion]."</option>";  }
						else
						{  $this->salida .=" <option value=\"".$dpto[$i][descripcion]."\">".$dpto[$i][descripcion]."</option>";  }
				}
				$this->salida .= "              </select></td></tr>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("cama")."\">CAMA: </td>";
				$this->salida .= "			        <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"cama\" value=\"".$_REQUEST['Medico']."\" size=\"40\" maxlength=\"50\"></td>";
				$this->salida .= "			     </tr>";
//------------------------------------------------
				$this->salida .= "			     <tr>";
				$this->salida .= "			        <td class=\"".$this->SetStyle("Observacion")."\">OBSERVACIONES: </td>";
				$this->salida .= "			        <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"Observacion\">$observacion</textarea></td>";
				$this->salida .= "			     </tr>";
				$this->salida .= "			     </table>";
				$this->salida .= "		 <table width=\"50%\" border=\"0\" align=\"center\">";
				$this->salida .= "				       <tr>";
				$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "				       				</form>";
				$actionM=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','FormaBuscar');
				$this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
				$this->salida .= "				       				<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
				$this->salida .= "				       				</form>";
				$this->salida .= "				       </tr>";
				$this->salida .= "  </table>";
				$this->salida .= "			     </form>";
        $this->salida .= ThemeCerrarTabla();
				return true;
	}

	/**
	*
	*/
	function DatosCompletos()
	{
				if(empty($_SESSION['DATOS_PACIENTE']['nombre']))
				{
						$nom=$this->NombrePaciente($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
						$_SESSION['DATOS_PACIENTE']['nombre']=$nom['nombre'];
				}
				$this->salida .= "		 <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['tipo_id']." ".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"5%\">PLAN:</td><td width=\"40%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['plan_descripcion']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">ENTIDAD: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['OS_PACIENTES']['DATOS']['ENTIDAD']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">MEDICO:</td><td  class=\"modulo_list_claro\">".$_SESSION['OS_PACIENTES']['DATOS']['MEDICO']."</td>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" >FECHA:</td><td  class=\"modulo_list_claro\">".$_SESSION['OS_PACIENTES']['DATOS']['FECHA']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr>";
				$this->salida .= "				<td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">OBSERVACION: </td><td class=\"modulo_list_claro\" colspan=\"5\">".$_SESSION['OS_PACIENTES']['DATOS']['OBSERVACION']."</td>";
				$this->salida .= "			</tr>";
				$this->salida .= " 			</table><BR>";
	}

	function frmForma($arr)
	{
			IncludeLib("tarifario_cargos");
			$this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE APOYOS DIAGNOSTICOS MANUALES  ( '.$_SESSION['OS_PACIENTES']['NOM_DPTO'].' )');
			$this->DatosCompletos();
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table><br>";
			$arreglo=$this->BuscarDatosTmp();
			if(!empty($arreglo))
			{
					$accion1=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','CrearOS');
					$this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
					$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td width=\"7%\">CUPS</td>";
					$this->salida.="  <td width=\"7%\">CARGO</td>";
					$this->salida.="  <td width=\"5%\">TARIF.</td>";
					$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
					$this->salida.="  <td width=\"5%\">CANT.</td>";
					$this->salida.="  <td width=\"10%\">VAL. NO CUBIERTO</td>";
					$this->salida.="  <td width=\"10%\">VAL. EMPRESA</td>";
					$this->salida.="  <td width=\"10%\">VALOR CARGO</td>";
					$this->salida.="  <td width=\"5%\"></td>";
					//$this->salida.="  <td width=\"5%\" colspan=\"2\"></td>";
					$this->salida.="</tr>";
					for($i=0; $i<sizeof($arreglo);)
					{
							$this->salida .= "<tr>";
							$this->salida.="<tr class='modulo_list_claro' align='center'>";
							$this->salida .= "        <td align=\"center\">".$arreglo[$i][cargo_cups]."</td>";
							$this->salida .= "        <td align=\"center\" colspan=\"9\">";
							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"normal_10\">";
							$d=$i;
							while($arreglo[$i][cargo_cups]==$arreglo[$d][cargo_cups])
							{
									$this->salida.="<tr class='modulo_list_oscuro'>";
									$this->salida .= "        <td align=\"center\" width=\"7%\">".$arreglo[$d][cargo]."</td>";
									$this->salida .= "        <td align=\"center\" width=\"6%\">".$arreglo[$d][tarifario_id]."</td>";
									$this->salida .= "        <td width=\"42%\">".$arreglo[$d][descripcion]."</td>";
									$cargos='';
									$cargos[]=array('tarifario_id'=>$arreglo[$d][tarifario_id],'cargo'=>$arreglo[$d][cargo],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
									$liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['DATOS_PACIENTE']['plan_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'],$_SESSION['OS_PACIENTES']['SERVICIO'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id'],'');
									$this->salida.="  <td  width=\"5%\" align=\"center\">".$liq[cargos][0][cantidad]."</td>";
									$this->salida.="  <td  width=\"11%\" align=\"center\">".$liq[cargos][0][valor_no_cubierto]."</td>";
									$this->salida.="  <td  width=\"11%\" align=\"center\">".$liq[cargos][0][valor_cubierto]."</td>";
									$this->salida.="  <td   width=\"10%\" align=\"center\">".$liq[cargos][0][valor_cargo]."</td>";
									$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','EliminarCargo',array('id'=>$arreglo[$d][tmp_solicitud_manual_id],'idDetalle'=>$arreglo[$d][tmp_solicitud_manual_detalle_id]));
									$this->salida.="  <td align=\"center\"><a href=\"$accion\"><img src=\"".GetThemePath()."/images/elimina.png\"  border='0'></a></td>";
									//$this->salida.="  <td align=\"center\"><input type = checkbox name= cargo".$arreglo[$d][tarifario_id]."".$arreglo[$d][cargo]." value=\"".$arreglo[$d][tarifario_id]."||".$arreglo[$d][cargo]."||".$arreglo[$d][cargo_cups]."\"></td>";
									$this->salida.="</tr>";
									$copago+=$liq[valor_cuota_paciente];
									$moderadora+=$liq[valor_cuota_moderadora];
									$nocub+=$liq[valor_no_cubierto];
									$total+=$copago+$moderadora+$nocub;
									$d++;
							}
							$i=$d;
							$this->salida .= "  </table>";
							$this->salida.="</td>";
							$this->salida.="</tr>";
					}
					$this->salida.="<tr><td colspan=\"4\" align=\"center\" class=\"modulo_table_list_title\">TOTAL</td>";
					$this->salida.="<td colspan=\"4\" align=\"center\" class=\"modulo_table_list_title\">$total</td>";
					$this->salida.="<td align=\"center\" class=\"modulo_list_claro\"><input class=\"input-submit\" type=submit name=mandar value=Ordenar></form></td>";
					$this->salida.="</tr>";
					$this->salida .= "  </table><br>";
			}
			$accion1=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','Busqueda_Avanzada');
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
  			if (($_REQUEST['criterio1apoyo'])  == '002')
			{  $this->salida.="<option value = '002' selected>Frecuentes</option>";   }
			else
			{  $this->salida.="<option value = '002' >Frecuentes</option>";  }
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
			if(!empty($arr))
			{
				$this->FormaResultados($arr);
			}
			$accionV=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','LlamarFormaBuscar');
			$this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
			$this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"CANCELAR\"></form></p>";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
	}

	/**
	*
	*/
	function FormaResultados($vectorA)
	{
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
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}
							$this->salida.="<tr class=\"$estilo\">";
							$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
							$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
							$this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
							$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','GuardarApoyo', array('cargo'=>$vectorA[$i][cargo],'apoyod_tipo_id'=>$vectorA[$i][apoyod_tipo_id],'descripcion'=>$vectorA[$i][descripcion]));
							$this->salida.="  <td align=\"center\" width=\"5%\"><a href=\"$accion\">CARGAR</a></td>";
							$this->salida.="</tr>";
					}
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
	}

		/**
		*
		*/
		function FormaVariasEquivalencias()
		{
				IncludeLib("tarifario_cargos");
				IncludeLib("funciones_facturacion");
				$this->salida .= ThemeAbrirTabla('CARGOS EQUIVALENTES');
				$this->DatosCompletos();
				//$v=explode('//',$_REQUEST['apoyo']);
				//mensaje
				$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "  </table><br>";
				$this->salida .= "     <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
				$this->salida .= "          <tr><td colspan=\"5\">EL CARGO CUPS (".$_REQUEST['cargo'].")<b> ".$_REQUEST['descripcion']." </b>TIENE VARIAS EQUIVALENCIAS:</td></tr>";
				$this->salida .= "          <tr><td colspan=\"5\">&nbsp;</td></tr>";
				$this->salida .= "  </table><br>";
				$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','GuardarEquivalencias',array('cups'=>$_REQUEST['cargo'],'apoyod_tipo_id'=>$_REQUEST['apoyod_tipo_id'],'apoyo'=>$_REQUEST['apoyo']));
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$equi=ValdiarEquivalencias($_SESSION['DATOS_PACIENTE']['plan_id'],$_REQUEST['cargo']);
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td width=\"5%\">CARGO</td>";
				$this->salida.="  <td width=\"5%\">TARIF.</td>";
				$this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
				$this->salida.="  <td width=\"5%\">CANT.</td>";
				$this->salida.="  <td width=\"10%\">VAL. NO CUBIERTO</td>";
				$this->salida.="  <td width=\"12%\">VAL. EMPRESA</td>";
				$this->salida.="  <td width=\"15%\">VALOR CARGO</td>";
				$this->salida.="  <td></td>";
				$this->salida.="</tr>";
				for($i=0; $i<sizeof($equi); $i++)
				{
					$this->salida .= "<tr>";
					if( $k % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
					$this->salida.="<tr class='$estilo' align='center'>";
					$this->salida .= "        <td align=\"center\">".$equi[$i][tarifario_id]."</td>";
					$this->salida .= "        <td align=\"center\">".$equi[$i][cargo]."</td>";
					$this->salida .= "        <td>".$equi[$i][descripcion]."</td>";
					$cargos='';
					$cargos[]=array('tarifario_id'=>$equi[$i][tarifario_id],'cargo'=>$equi[$i][cargo],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
					$liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['DATOS_PACIENTE']['plan_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'],$_SESSION['OS_PACIENTES']['SERVICIO'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id'],'');
					$this->salida.="  <td >".$liq[cargos][0][cantidad]."</td>";
					$this->salida.="  <td >".$liq[cargos][0][valor_no_cubierto]."</td>";
					$this->salida.="  <td >".$liq[cargos][0][valor_cubierto]."</td>";
					$this->salida.="  <td >".$liq[cargos][0][valor_cargo]."</td>";
					$this->salida .= "        <td align=\"center\"><input type = checkbox name= Equi".$equi[$i][tarifario_id]."".$equi[$i][cargo]." value=\"".$equi[$i][tarifario_id]."//".$equi[$i][cargo]."//".$equi[$i][descripcion]."//".$liq[cargos][0][cantidad]."\"></td>";
					$this->salida.="</tr>";
				}
				$this->salida .= "  </table><br>";
				$this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
				$this->salida .= "          <tr>";
				$this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "</form>";
				$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','frmForma');
				$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
				$this->salida .= "</form>";
				$this->salida .= "          </tr>";
				$this->salida .= "     </table>";
        $this->salida .= ThemeCerrarTabla();
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
		$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','Busqueda_Avanzada',array('conteoapoyo'=>$this->conteo,'criterio1apoyo'=>$_REQUEST['criterio1apoyo']));

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
  * Se encarga de separar la fecha del formato timestamp
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
   }
 }

 /**
  * Se encarga de separar la hora del formato timestamp
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
		$x=explode('.',$time[3]);
    return  $time[1].":".$time[2].":".$x[0];
  }

	function FormaCumplimiento()
	{
			$this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
			$this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
			$this->salida .="".$this->SetStyle("MensajeError")."";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td></tr>";
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['tipo_id']."&nbsp;".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td></tr>";
			$FechaNacimiento=$this->Edad($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
			$EdadArr=CalcularEdad($FechaNacimiento,$FechaFin);
			$edad=$EdadArr['edad_aprox'];
			$_SESSION['DATOS_PACIENTE']['edad']=$edad;
			$this->salida .= "				       <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">EDAD PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$_SESSION['DATOS_PACIENTE']['edad']."</td></tr>";
			$this->salida .= "</table><BR>";
			$vector=$this->TraerOrdenesServicioAmb($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id'],$_SESSION['OS_PACIENTES']['ORDEN']);
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
							$this->salida.="  <td align=\"left\" colspan=\"8\">PLAN&nbsp;&nbsp;".$vector[$i][descripcion]."&nbsp;&nbsp;".$vector[$i][plan_descripcion]."</td>";
							$this->salida.="</tr>";

							$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
							$this->salida.="  <td width=\"5%\">ORDEN</td>";
							$this->salida.="  <td width=\"5%\">ITEM</td>";
							$this->salida.="  <td width=\"7%\">CANTIDAD</td>";
							$this->salida.="  <td width=\"7%\">CARGO</td>";
							if($_SESSION['DATALAB']['DPTO_DATALAB']==1)
							{
									$this->salida.="  <td width=\"25%\">DESCRIPCION</td>";
									$this->salida.="  <td width=\"16%\">DATALAB</td>";
							}
							else
							{
									$this->salida.="  <td colspan = 2 width=\"41%\">DESCRIPCION</td>";
							}
							$this->salida.="  <td width=\"10%\">VENCIMIENTO</td>";
							$this->salida.="  <td width=\"5%	\">OPCION</td>";
							$this->salida .= "           <form name=\"formita\" action=\"".ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','BuscarCuentaActiva',array('plan_id'=>$vector[$k][plan_id],'Nforma'=>'FormaCumplimiento'))."\" method=\"post\">";

							$this->salida.="</tr>";
					}
					while($vector[$i][plan_id]==$vector[$k][plan_id]
					AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
					AND $vector[$i][rango]==$vector[$k][rango]
					AND $vector[$i][servicio]==$vector[$k][servicio]
					//opcion para pintar siempre que la orden de servicio sea distinta se active el boton cumplir
					AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id]
					)
					{
							$this->salida.="<tr class='modulo_list_claro'>";
							$this->salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"5%\">".$vector[$k][orden_servicio_id]."</td>";
							$this->salida.="  <td colspan=\"7\">";
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
									$this->salida.="  <td align='center' class=$estilo width=\"6%\"<label class='label_mark'>".$vector[$l][numero_orden_id]."</label></td>";
									$this->salida.="  <td colspan=6 >";
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
											$this->salida.="  <td width=\"11%\" align=\"center\" >".$vector[$m][cantidad]."</td>";
											$this->salida.="  <td width=\"11%\" align=\"center\" >".$vector[$m][cargoi]."</td>";
											if($_SESSION['DATALAB']['DPTO_DATALAB']==1)
											{
													$this->salida.="  <td width=\"37%\">".$vector[$m][des1]."</td>";
													//meter a datalab
													$spia = 1;
													$codigos=$this->Cargar_Codigos_Datalab($vector[$m][cargoi]);
													if (sizeof($codigos) > 1)
													{
														$this->salida.="  <td align=\"center\" width=\"24%\"><select name=\"codigo".$vector[$l][numero_orden_id]."\" class=\"select\">";
														$this->salida .=" <option value= '-1' selected>--Seleccione--</option>";
														$this->LLenarComboCodigos($codigos,$_REQUEST['codigo']);
														$this->salida .= "</select></td>";
													}
													elseif (sizeof($codigos) == 1)
													{
														$this->salida.="  <td align=\"center\" width=\"24%\"><input readonly type=\"text\" class=\"input-text\" name=\"codigo".$vector[$l][numero_orden_id]."\" size = 15  value = ".$codigos[0][codigo_datalab]."></td>";
													}
													else
													{
														$this->salida.="  <td align=\"center\" width=\"24%\">SIN EQUIVALENCIA</td>";
														$spia=0;
													}
											}
											else
											{
													$this->salida.="  <td colspan = 2 width=\"61%\">".$vector[$m][des1]."</td>";
											}

											if(strtotime($arr_fecha[0]) > strtotime(date("Y-m-d")))
											{
												$this->salida.="<td width=\"26%\" align=\"center\" >$arr_fecha[0]</td>";
												$this->salida.="<td width=\"15%\" align=\"center\" >";
												$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
												$this->salida.="<tr class=$estilo>";
												$citas = $this->Revision_Cita($vector[$m][numero_orden_id],$vector[$m][cargoi]);
												if($citas[sw_cita]=='1')
												{
													if(!empty($citas[existe_cita][numero_orden_id]))
													{
															//$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','AsignacionProfCitasImagen',array('numero_orden_id'=>$vector[$m][numero_orden_id],'tipo_id_paciente'=>$tipo,'paciente_id'=>$id,'nombre'=>$nom));
															//	$this->salida.="<td width=\"15%\" align=\"center\" ><a href=".$accion."><img src=\"". GetThemePath() ."/images/cumplimientos_citas.png\" border=0></a></td>";
													}
													else
													{
															//$accion=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','LlamaProgramCitasImagen',array('numero_orden_id'=>$vector[$m][numero_orden_id],'tipo_id_paciente'=>$tipo,'paciente_id'=>$id,'nombre'=>$nom,'tipo_equipo'=>$citas[tipo_equipo_imagen_id]));
															//	$this->salida.="<td width=\"15%\" align=\"center\" ><a href=".$accion."><img src=\"". GetThemePath() ."/images/atencion_citas.png\" border=0 ></a></td>";
													}
													//validando el spia de datalab
													if ($_SESSION['DATALAB']['DPTO_DATALAB']==1)
													{
															if($spia==1)
															{
																$this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
															}
															else
															{
																$this->salida.="  <td align=\"center\" width=\"15%\">&nbsp;&nbsp;</td>";
															}
													}
													else
													{
															$this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
													}

												}
												else
												{
														$this->salida.="<td width=\"15%\" align=\"center\">&nbsp;&nbsp;</td>";
														//validando el spia de datalab
														if ($_SESSION['DATALAB']['DPTO_DATALAB']==1)
														{
															if($spia==1)
															{
																$this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
															}
															else
															{
																$this->salida.="  <td align=\"center\" width=\"15%\">&nbsp;&nbsp;</td>";
															}
														}
														else
														{
																$this->salida.="<td width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";
														}
												}
												$this->salida.="</tr>";
												$this->salida.="</table>";
												$this->salida.="</td>";
											}
											else
											{
													$this->salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
													$this->salida.="<td width=\"15%\" align=\"center\" >";
													$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
													$this->salida.="<tr class=$estilo>";
													$this->salida.="<td width=\"15%\" align=\"center\">&nbsp;&nbsp;</td>";
													$this->salida.="  <td width=\"15%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
													$this->salida.="</tr>";
													$this->salida.="</table>";
													$this->salida.="</td>";
											}
											$this->salida.="</tr>";
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
					$this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir></td>";
					$this->salida.="</form>";
					$this->salida.="</tr>";
					$this->salida.="</table>";
					$i=$k;
			}

			$this->salida.="<br><br><table align=\"center\" width='20%' border=\"0\">";
			$action2=ModuloGetURL('app','Os_Pacientes_Hospitalizados','user','LlamarFormaBuscar',array());
			$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"<< Volver\"></form></td>";
			$this->salida .= "</tr>";
			$this->salida.="</table><br>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}

//-----------------------------------FIN SOLICITUD MANUAL	DARLING-------------------------

}//fin clase

?>
