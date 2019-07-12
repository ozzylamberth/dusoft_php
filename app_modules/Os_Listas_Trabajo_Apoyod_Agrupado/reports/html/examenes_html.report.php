<?php
  /**
  * $Id: examenes_html.report.php,v 1.2 2009/12/07 13:57:42 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * Reporte de prueba formato HTML
  */
  class examenes_html_report
  {
  	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
  	var $datos;

  	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
  	//NO MODIFICAR POR EL MOMENTO - DELEN UN TIEMPITO PARA TERMINAR EL DESARROLLO
  	var $title       = '';
  	var $author      = '';
  	var $sizepage    = 'leter';
  	var $Orientation = '';
  	var $grayScale   = false;
  	var $headers     = array();
  	var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function examenes_html_report($datos=array())
    {
		    $this->datos=$datos;
        return true;
    }
  	//FUNCION GetMembrete() - SI NO VA UTILIZAR MEMBRETE EXTERNO PUEDE BORRAR ESTE METODO
  	//RETORNA EL MEMBRETE DEL DOCUMENTO
  	//
  	// SI RETORNA FALSO SIGNIFICA EL REPORTE NO UTILIZA MEMBRETE EXTERNO AL MISMO REPORTE.
  	// SI RETORNA ARRAY HAY DOS OPCIONES:
  	//
  	// 1. SI $file='NombreMembrete' EL REPORTE UTILIZARA UN MEMBRETE UBICADO EN
  	//    reports/HTML/MEMBRETES/NombreMembrete y el arraglo $datos_membrete
  	//    seran los parametros especificos de este membrete.
  	//
  	//	  EJEMPLO:
  	//
  	// 			function GetMembrete()
  	// 			{
  	// 				$Membrete = array('file'=>'NombreMembrete','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE','subtitulo'=>'SUBTITULO'));
  	// 				return $Membrete;
  	// 			}
  	//
  	// 2. SI $file=false  SIGNIFICA QUE UTILIZA UN MEMBRETE GENERICO QUE CONCISTE EN UN
  	//    LOGO (SI LO HAY), UN TITULO, UN SUBTITULO Y UNA POSICION DEL LOGO (IZQUIERDA,DERECHA O CENTRO)
  	//    LOS PARAMETROS DEL VECTOR datos_membrete DEBN SER:
  	//    titulo    : TITULO DE REPORTE
  	//    subtitulo : SUBTITULO DEL REPORTE
  	//    logo      : LA RUTA DE UN LOGO DENTRO DEL DIRECTORIO images (EN EL RAIZ)
  	//    align     : POSICION DEL LOGO (left,center,right)
  	//
  	//	  EJEMPLO:
  	//
  	// 			function GetMembrete()
  	// 			{
  	// 				$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
  	// 																		'subtitulo'=>'subtitulo'
  	// 																		'logo'=>'logocliente.png'
  	// 																		'align'=>'left'));
  	// 				return $Membrete;
  	// 			}
  	function GetMembrete()
  	{
  		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
  																'subtitulo'=>'',
  																'logo'=>'logocliente.png',
  																'align'=>'left'));
  		return $Membrete;
  	}
  	//FUNCION CrearReporte()
  	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
  	function CrearReporte()
  	{
			//*******************************************termino
			$vector = $this->ReporteResultadoApoyod($this->datos['resultado_id']);
			$Salida .= "<table  align=\"center\" border=\"0\"  width=\"100%\">";
      $Salida .= "<tr class=\"Normal_10\">";
      $Salida .= "  <td width=\"25%\" colspan=\"1\"><b>ENTIDAD :</b></td>";
      $Salida .= "  <td width=\"75%\" colspan=\"3\">".$vector[laboratorio].'&nbsp;&nbsp;'.$vector[tipo_id_tercero].'&nbsp;&nbsp;'.$vector[id]."</td>";
      $Salida .= "</tr>";
      if($vector['fecha_cumplimiento'])
      {
        $Salida .= "  <tr class=\"Normal_10\">\n";
        $Salida .= "    <td><b>FECHA CUMPLIMIENTO :</b></td>\n";
        $Salida .= "    <td colspan=\"3\">".$vector['fecha_cumplimiento']."</td>\n";
        $Salida .= "  </tr>\n";      
      }
      $Salida .= "<tr>";
      $Salida .= "  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">ITEM DE LA ORDEN :</td>";
      $Salida .= "  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$vector[numero_orden_id]."</td>";
      $Salida .= "</tr>";

      $Salida.="<tr>";
      $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">PACIENTE :</td>";
      $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$vector[tipo_id_paciente].' '.$vector[paciente_id].' - '.strtoupper($vector[nombre])."</td>";
      $Salida.="</tr>";

				//LO NUEVO QUES E METIO
				//1. obtener la edad del paciente
				$edad_paciente = CalcularEdad($vector[fecha_nacimiento],date("Y-m-d"));
				$Salida.="<tr>";
				$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">EDAD PACIENTE :</td>";
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$edad_paciente[edad_aprox]."</td>";
				$Salida.="</tr>";
				//2. datos adicionales
        //print_r($vector[datos_adicionales]);
				foreach($vector[datos_adicionales] as $index=>$codigo)
				{
					if ($codigo!='')
					{
						//COMPARACION ESTATICA
						if ($index != 'orden_servicio_id')
						{
							$Salida.="<tr>";
							$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">".strtoupper($index)." : </td>";
							$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$codigo."</td>";
							$Salida.="</tr>";
						}
					}
				}
        
               
				$Salida.="<tr>";
				$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">PLAN :</td>";
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$vector[plan_descripcion]."</td>";
				$Salida.="</tr>";
        
        if(!empty($vector['eps_punto_atencion_id'])){
          $Salida.="<tr>";
          $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">PUNTO DE ATENCION :</td>";
          $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$vector[eps_punto_atencion_nombre]."</td>";
          $Salida.="</tr>";
        
        }
				//FIN DELO NUEVO

					for($t=1; $t<2;$t++)
				{
					$Salida.="<tr>";
					$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
					$Salida.="</tr>";
				}

				$Salida.="<tr>";
				$Salida.="  <td class=\"Normal_10N\" align=\"center\" colspan=\"4\">".$vector[cargo]." - ".strtoupper($vector[titulo])."</td>";
				$Salida.="</tr>";

				for($t=1; $t<2;$t++)
				{
					$Salida.="<tr>";
					$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
					$Salida.="</tr>";
				}
			$Salida.="</table>";
      //$Salida.="<pre>".print_r($vector,true)."</pre>";
			if($vector[detalle])
			{
          $plan="";
					for($i=0;$i<sizeof($vector);$i++)
					{
              if($plan!=$vector[detalle][$i][lab_plantilla_id])
              {
                $sw=true;
                $plan=$vector[detalle][$i][lab_plantilla_id];
              }
              else
              {
                $sw=false;
              }
							if( $i % 2)	{$estilo='modulo_list_claro';}
							else	{$estilo='modulo_list_oscuro';}
							switch ($vector[detalle][$i][lab_plantilla_id])
							{
           		case "1": {    if($sw==true)
                             {
															
                                $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
  															$Salida.="<tr class=\"Normal_10N\">";
  															$Salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
  															$Salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
  															$Salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
  															$Salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
  															$Salida.="<td width=\"10%\" align=\"center\">UND</td>";
  															$Salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
  															$Salida.="</tr>";
                             }
															if(is_null($vector[detalle][$i][rango_min]) || $vector[detalle][$i][rango_min] == '0')
															{
																	$vector[detalle][$i][rango_min] = 0;
															}
                              
															$Salida.="<tr class=\"Normal_10\">";
															$Salida.="<td width=\"35%\" align=\"left\"  >".strtoupper($vector[detalle][$i]['nombre_examen'])."</td>";
															if ($vector[detalle][$i][sw_alerta] == '1')
															{
																	$Salida.="<td width=\"30%\" align=\"center\" class=\"label_error\">".$vector[detalle][$i]['resultado']." &nbsp; ".$vector[detalle][$i]['unidades']."</td>";
																	$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_min']."</td>";
																	$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_max']."</td>";
																	$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['unidades']."</td>";
																	$Salida.="<td width=\"5%\"  align=\"center\">X</td>";
															}
															else
															{
																	$Salida.="<td width=\"30%\" align=\"center\">".$vector[detalle][$i]['resultado']." &nbsp; ".$vector[detalle][$i]['unidades']."</td>";
																	$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_min']."</td>";
																	$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_max']."</td>";
																	$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['unidades']."</td>";
																	$Salida.="<td width=\"5%\"  align=\"center\">&nbsp;</td>";
															}
															$Salida.="</tr>";
                              if($vector[detalle][$i][lab_plantilla_id] != $vector[detalle][$i+1][lab_plantilla_id])
															$Salida.="</table>";
															break;
														}

									case "2": {
                              if($sw==true)
                              { 
																$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
																$Salida.="<tr class=\"Normal_10N\">";
																$Salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																$Salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
																$Salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
																$Salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
																$Salida.="</tr>";
                              } 

																$Salida.="<tr class=\"Normal_10\">";
																$Salida.="<td align=\"left\" width=\"35%\" >".strtoupper($vector[detalle][$i]['nombre_examen'])."</td>";
																if ($vector[detalle][$i][sw_alerta] == '1')
																{
																		$Salida.="<td align=\"center\" width=\"40%\" colspan = \"2\" class=\"label_error\">".$vector[detalle][$i][resultado]."</td>";
																		$Salida.="<td align=\"center\" width=\"20%\" colspan = \"2\">".$vector[detalle][$i]['unidades']."</td>";
																		$Salida.="<td width=\"5%\"  align=\"center\">X</td>";
																}
																else
																{
																		$Salida.="<td align=\"center\" width=\"40%\" colspan = \"2\">".$vector[detalle][$i]['resultado']."</td>";
																		$Salida.="<td align=\"center\" width=\"20%\" colspan = \"2\">".$vector[detalle][$i]['unidades']."</td>";
																		$Salida.="<td width=\"5%\"  align=\"center\">&nbsp;</td>";
																}

																$Salida.="</tr>";
                                if($vector[detalle][$i][lab_plantilla_id] != $vector[detalle][$i+1][lab_plantilla_id])
																$Salida.="</table>";
																break;

														}
									case "3": {
                              
                                if ($vector[detalle][$i][sw_alerta] == '1')
																{
																    $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
																    $Salida.="<tr class=\"Normal_10N\">";
																    $Salida.="<td width=\"5%\" align=\"left\">RESULTADO PATOLOGICO</td>";
																		$Salida.="</tr>";
    																$Salida.="</table>";
																}
																//$vector[detalle][$i][resultado]=str_replace("\x0a","<p></p>",$vector[detalle][$i][resultado]);
																$Salida.="<BLOCKQUOTE>";
																$Salida.="<div align=\"justify\" width=\"100%\" class=\"Normal_10\">".$vector[detalle][$i][resultado]."</div>";
																$Salida.="</BLOCKQUOTE>";
																break;
														}

									case "0": {
                              if($sw==true)
                              {
																$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
																$Salida.="<tr class=\"Normal_10N\">";
																$Salida.="<td width=\"35%\" align=\"left\">SUBEXAMEN: ".strtoupper($vector[detalle][$i]['nombre_examen'])."</td>";
																$Salida.="</tr>";
                              }
																 if ($vector[detalle][$i][sw_alerta] == '1')
																{
																	$Salida.="<tr class=\"Normal_10N\">";
																	$Salida.="<td width=\"60%\" align=\"left\" colspan = \"4\">RESULTADO PATOLOGICO</td>";
																	$Salida.="</tr>";
																}
                                if($vector[detalle][$i][lab_plantilla_id] != $vector[detalle][$i+1][lab_plantilla_id])
																$Salida.="</table>";
																//lo que habia antes
																$vector[detalle][$i][resultado]=str_replace("\x0a","<p>",$vector[detalle][$i][resultado]);
																$Salida.="<BLOCKQUOTE>";
																$Salida.="<div align=\"justify\" width=\"100%\" class=\"Normal_10\">".$vector[detalle][$i][resultado]."</div>";
																$Salida.="</BLOCKQUOTE>";
																break;
														}

									case "5": {   //$Salida.="<pre>".print_r($vector[detalle][$i][lab_plantilla_id],true)."</pre>";
                                
                               if($sw==true)
                               {
																//caso exclusivo para datalab  -- al migrar se copio identica a la plantilla 1
																  $Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
  																$Salida.="<tr class=\"Normal_10N\">";
  																$Salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
  																$Salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
  																$Salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
  																$Salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
  																$Salida.="<td width=\"10%\" align=\"center\">UND</td>";
  																$Salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
  																$Salida.="</tr>";
                                
                                }
                               
                                
																if(is_null($vector[detalle][$i][rango_min]) || $vector[detalle][$i][rango_min] == '0')
																{
																		$vector[detalle][$i][rango_min] = 0;
																}
                                
                                
																$Salida.="<tr class=\"Normal_10\">";
																$Salida.="<td width=\"35%\" align=\"left\"  >".strtoupper($vector[detalle][$i]['nombre_examen'])."</td>";
																if ($vector[detalle][$i][sw_alerta] == '1')
																{
																		$Salida.="<td width=\"30%\" align=\"center\" class=\"label_error\">".$vector[detalle][$i]['resultado']." &nbsp; ".$vector[detalle][$i]['unidades']."</td>";
																		$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_min']."&nbsp;</td>";
																		$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_max']."&nbsp;</td>";
																		$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['unidades']."&nbsp;</td>";
																		$Salida.="<td width=\"5%\"  align=\"center\">X</td>";
																}
																else
																{
																		$Salida.="<td width=\"30%\" align=\"center\">".$vector[detalle][$i]['resultado']." &nbsp; ".$vector[detalle][$i]['unidades']."</td>";
																		$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_min']."&nbsp;</td>";
																		$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['rango_max']."&nbsp;</td>";
																		$Salida.="<td width=\"10%\" align=\"center\">".$vector[detalle][$i]['unidades']."&nbsp;</td>";
																		$Salida.="<td width=\"5%\"  align=\"center\">&nbsp;</td>";
																}
																$Salida.="</tr>";
                                if($vector[detalle][$i][lab_plantilla_id] != $vector[detalle][$i+1][lab_plantilla_id])
																$Salida.="</table>";
																
                              
                                break;
                                
														}
							}//cierra el switche
							//res$i;
					}//cierra el for

					$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
					for($t=1; $t<2;$t++)
					{
						$Salida.="<tr>";
						$Salida.="<td colspan=\"4\" width=\"100%\">&nbsp;</td>";
						$Salida.="</tr>";
					}

					if($vector[informacion]!='')
					{
							$Salida.="<tr class=\"Normal_10N\">";
							$Salida.="<td widht=\"30\" class=\"hc_table_submodulo_list_title\" align=\"left\">INFORMACION: </td><td widht=\"70\" align=\"left\" class=\"Normal_10\"><font size='1'>".$vector[informacion]."</font></td>";
							$Salida.="</tr>";

							for($t=1; $t<2;$t++)
							{
								$Salida.="<tr>";
								$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
								$Salida.="</tr>";
							}
					}

					if($vector[observacion_prestacion_servicio]!='')
					{
						$Salida.="<tr class=\"Normal_10N\">";
						$Salida.="<td align=\"left\" colspan = 4 class=\"hc_table_submodulo_list_title\" width=\"100%\">OBSERVACION DEL PRESTADOR DEL SERVICIO:</td>";
						$Salida.="</tr>";

						$Salida.="<tr class=\"Normal_10\">";
						$Salida.="<td align=\"left\" class=\"$estilo\" width=\"100%\" colspan = 4>".$vector[observacion_prestacion_servicio]."</td>";
						$Salida.="</tr>";

						for($t=1; $t<2;$t++)
						{
							$Salida.="<tr>";
							$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
							$Salida.="</tr>";
						}
					}

					//listado de las observaciones adicionales al resultado
					if(sizeof($vector[observaciones_adicionales])>=1)
					{
							$Salida.="<tr>";
							$Salida.="<td align=\"center\" colspan = 4>";
							$Salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
							$Salida.="<tr class=\"Normal_10N\">";
							$Salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\" width=\"5%\">FECHA REGISTRO</td>";

							$Salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\" width=\"20%\">USUARIO QUE REALIZA LA OBSERVACION</td>";
							$Salida.="<td align=\"center\"  class=\"hc_table_submodulo_list_title\" width=\"60%\">OBSERVACION ADICIONAL AL RESULTADO</td>";
							$Salida.="</tr>";

							for($i=0;$i<sizeof($vector[observaciones_adicionales]);$i++)
							{
									$Salida.="<tr class=\"Normal_10\">";
									$Salida.="<td align=\"center\" class=\"modulo_list_claro\" width=\"5%\">".$this->FechaStampMostrar($vector[observaciones_adicionales][$i][fecha_registro_observacion])." - ".$this->HoraStamp($vector[observaciones_adicionales][$i][fecha_registro_observacion])."</td>";
									$Salida.="<td align=\"center\" class=\"modulo_list_claro\" width=\"30%\">".$vector[observaciones_adicionales][$i][usuario_observacion]."</td>";
									$Salida.="<td align=\"justify\" class=\"modulo_list_claro\" width=\"60%\">".$vector[observaciones_adicionales][$i][observacion_adicional]."</td>";
									$Salida.="</tr>";
							}
							$Salida.="</table>";
							$Salida.="</td>";
							$Salida.="</tr>";
					}

					$Salida.="<tr class=\"Normal_10N\">";
					//MauroB
					if($this->datos['sw_firma']==true)
					{
						//si el que diagnostica y firma son diferentes
						if ( (($vector[usuario_id_profesional])!=($vector[usuario_id_profesional_autoriza]))
								&&($vector[usuario_id_profesional_autoriza]!=NULL) )
						{
							$Salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\" width=\"50%\">DIAGNOSTICO PROFESIONAL :</td>";
							$Salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\" width=\"50%\">REVISADO POR :</td>";
							$Salida.="</tr>";
		
							for($t=1; $t<2;$t++)
								{
									$Salida.="<tr>";
									$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
									$Salida.="</tr>";
								}
							$Salida.="<tr class=\"Normal_10N\">";
							$Salida.="<TD ALIGN=\"LEFT\" ><IMG SRC='images/firmas_profesionales/".$vector['firma']."'></td>";
							$Salida.="</tr>";
							$Salida.="<tr class=\"Normal_10N\">";
						
							if($vector[tarjeta_profesional]!='')
							{
								$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."<br>TP: ".$vector[tarjeta_profesional]."</td>";
							}
							else
							{
								$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."</td>";
							}
							
							if(($vector[usuario_id_profesional_autoriza]!=NULL)||(!empty($vector[usuario_id_profesional_autoriza])))
							{
								$prof_firma=$this->ConsultaFirmaMedico($vector[usuario_id_profesional_autoriza]);
								if($prof_firma[tarjeta_profesional]!='')
								{
									$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($prof_firma[nombre])."<br>".strtoupper($prof_firma[descripcion])."<br>TP: ".$prof_firma[tarjeta_profesional]."</td>";
								}else
								{
									$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($prof_firma[nombre])."<br>".strtoupper($prof_firma[descripcion])."</td>";
								}	
							}
						}
						else
						{	
							$Salida.="<td align=\"left\"  class=\"hc_table_submodulo_list_title\" width=\"50%\">DIAGNOSTICO PROFESIONAL ::</td>";
							$Salida.="</tr>";
		
							for($t=1; $t<2;$t++)
								{
									$Salida.="<tr>";
									$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
									$Salida.="</tr>";
								}
							$Salida.="<tr class=\"Normal_10N\">";
							$Salida.="<TD ALIGN=\"LEFT\" ><IMG SRC='images/firmas_profesionales/".$vector['firma']."'></td>";
							$Salida.="</tr>";
							$Salida.="<tr class=\"Normal_10N\">";
							if($vector[tarjeta_profesional]!='')
							{
								$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."<br>TP: ".$vector[tarjeta_profesional]."</td>";
							}
							else
							{
								$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."</td>";
							}
						}
					}else
					{	
						$Salida.="<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br><br> SIN CONFIRMACION EN EL SISTEMA. </td>";
					}
					$Salida.="</tr>";
					//fin MauroB

					$Salida.="</table>";

					$usuario = $this->ConsultaNombreUsuario(UserGetUID());
					if ($usuario)
					{
							$Salida.="<BR><BR><table  align=\"center\" border=\"0\"  width=\"100%\">";
							$Salida.="<tr class=\"Normal_10N\">";
							$Salida.="  <td align=\"left\" colspan=\"3\">Imprime: ".$usuario[nombre]."</td>";
							$Salida.="</tr>";
							$Salida.="<tr class=\"Normal_10N\">";
							$Salida.="  <td align=\"left\" colspan=\"3\">Fecha Impresión: ".date('Y-m-d')." ".date('h:m')."</td>";
							$Salida.="</tr>";
							$Salida.="</table>";
					}

			}
			return $Salida;
	//*****************************************fin de termino
	}


    //AQUI TODOS LOS METODOS QUE USTED QUIERA
		function ReporteResultadoApoyod($resultado_id)
		{
			list($dbconnect) = GetDBconn();
      
			/*$sql = "SELECT	   a.*,
								         f.razon_social as laboratorio,
								         f.tipo_id_tercero,
								         f.id,
						  CASE WHEN (g.titulo_examen = '' or g.titulo_examen ISNULL) 
              THEN       h.descripcion
							ELSE       g.titulo_examen END as titulo, 
                         g.informacion,
								         btrim(n.primer_nombre||' '||n.segundo_nombre||' '|| n.primer_apellido||' '||n.segundo_apellido,'') as nombre,
								         n.sexo_id as sexo_paciente,
								         n.fecha_nacimiento,
								         l.nombre_tercero,
								         m.tarjeta_profesional,
								         r.descripcion,
								         p.plan_descripcion,
                         afiliacion.eps_punto_atencion_id,
                         afiliacion.eps_punto_atencion_nombre
							FROM (
											SELECT  a.resultado_id,
											        a.paciente_id,
											        a.tipo_id_paciente,
											        a.cargo,
											        a.tecnica_id,
											        a.fecha_realizado,
											        a.usuario_id,
											        a.observacion_prestacion_servicio,
											        b.numero_orden_id,
											        b.usuario_id_profesional_autoriza,
											        b.usuario_id_profesional,
											        c.orden_servicio_id,
											        d.departamento,
                              TO_CHAR(OC.fecha_cumplimiento,'DD/MM/YYYY') AS fecha_cumplimiento
                      FROM    hc_resultados as a,
											        hc_resultados_sistema as b,
											        os_maestro as c
                              LEFT JOIN os_cumplimientos_detalle OC
                              ON (OC.numero_orden_id = c.numero_orden_id) ,
											        os_internas as d
                      WHERE   a.resultado_id = ".$resultado_id."
											AND     b.resultado_id = a.resultado_id
											AND     c.numero_orden_id = b.numero_orden_id
											AND     d.numero_orden_id = c.numero_orden_id
										) AS a,
										    departamentos as e,
										    empresas as f,
										    apoyod_cargos as g,
										    cups as h,
										    profesionales_usuarios as k,
										    profesionales m,
										    terceros as l,
										    pacientes n,
										    tipos_profesionales r,
										    os_ordenes_servicios o,
										    planes p,
                        planes_rangos pr
                        LEFT JOIN (SELECT  ea.plan_atencion,
                                           ea.tipo_afiliado_atencion,
                                           ea.rango_afiliado_atencion,
                                           ep.eps_punto_atencion_id,
                                           ep.eps_punto_atencion_nombre
                                  FROM     eps_afiliados ea,
                                          eps_puntos_atencion ep 
                                  WHERE   ea.eps_punto_atencion_id = ep.eps_punto_atencion_id
                                  ) AS afiliacion
                                  ON (ea.plan_atencion = pr.plan_id
                                        AND ea.tipo_afiliado_atencion = pr.tipo_afiliado_id
                                        AND ea.rango_afiliado_atencion = pr.rango)
							WHERE	    e.departamento = a.departamento
							AND 	    f.empresa_id = e.empresa_id
							AND 	    g.cargo = a.cargo
							AND 	    h.cargo = a.cargo
							AND 	    k.usuario_id = a.usuario_id_profesional
							AND 	    m.tipo_id_tercero = k.tipo_tercero_id
							AND 	    m.tercero_id = k.tercero_id
							AND 	    l.tipo_id_tercero = m.tipo_id_tercero
							AND 	    l.tercero_id = m.tercero_id
							AND 	    r.tipo_profesional = m.tipo_profesional
							AND 	    o.orden_servicio_id = a.orden_servicio_id
							AND 	    p.plan_id = o.plan_id
              AND       pr.plan_id = o.plan_id
              AND       pr.rango = o.rango
              AND       pr.tipo_afiliado_id = o.tipo_afiliado_id
              AND       ep.eps_punto_atencion_id
							AND	 	    n.paciente_id = a.paciente_id
							AND 	    n.tipo_id_paciente = a.tipo_id_paciente";*/
              
        $sql = "SELECT	   a.*,
								         f.razon_social as laboratorio,
								         f.tipo_id_tercero,
								         f.id,
						  CASE WHEN (g.titulo_examen = '' or g.titulo_examen ISNULL) 
              THEN       h.descripcion
							ELSE       g.titulo_examen END as titulo, 
                         g.informacion,
								         btrim(n.primer_nombre||' '||n.segundo_nombre||' '|| n.primer_apellido||' '||n.segundo_apellido,'') as nombre,
								         n.sexo_id as sexo_paciente,
								         n.fecha_nacimiento,
								         l.nombre_tercero,
								         m.tarjeta_profesional,
										 m.firma,
								         r.descripcion,
								         p.plan_descripcion,
                         pr.rango,
                         afiliacion.eps_punto_atencion_id,
                         afiliacion.eps_punto_atencion_nombre
							FROM (
											SELECT  a.resultado_id,
											        a.paciente_id,
											        a.tipo_id_paciente,
											        a.cargo,
											        a.tecnica_id,
											        a.fecha_realizado,
											        a.usuario_id,
											        a.observacion_prestacion_servicio,
											        b.numero_orden_id,
											        b.usuario_id_profesional_autoriza,
											        b.usuario_id_profesional,
											        c.orden_servicio_id,
											        d.departamento,
                              TO_CHAR(OC.fecha_cumplimiento,'DD/MM/YYYY') AS fecha_cumplimiento
                      FROM    hc_resultados as a,
											        hc_resultados_sistema as b,
											        os_maestro as c
                              LEFT JOIN os_cumplimientos_detalle OC
                              ON (OC.numero_orden_id = c.numero_orden_id) ,
											        os_internas as d
                      WHERE   a.resultado_id = ".$resultado_id."
											AND     b.resultado_id = a.resultado_id
											AND     c.numero_orden_id = b.numero_orden_id
											AND     d.numero_orden_id = c.numero_orden_id
										) AS a
                    LEFT JOIN (SELECT  ea.afiliado_tipo_id,
                                           ea.afiliado_id,
                                           ep.eps_punto_atencion_id,
                                           ep.eps_punto_atencion_nombre
                                  FROM     eps_afiliados ea,
                                          eps_puntos_atencion ep 
                                  WHERE   ea.eps_punto_atencion_id = ep.eps_punto_atencion_id
                                  ) AS afiliacion
                                  ON (afiliacion.afiliado_tipo_id = a.tipo_id_paciente
                                        AND afiliacion.afiliado_id = a.paciente_id),
										    departamentos as e,
										    empresas as f,
										    apoyod_cargos as g,
										    cups as h,
										    profesionales_usuarios as k,
										    profesionales m,
										    terceros as l,
										    pacientes n,
										    tipos_profesionales r,
										    os_ordenes_servicios o,
										    planes p,
                        planes_rangos pr
                        
							WHERE	    e.departamento = a.departamento
							AND 	    f.empresa_id = e.empresa_id
							AND 	    g.cargo = a.cargo
							AND 	    h.cargo = a.cargo
							AND 	    k.usuario_id = a.usuario_id_profesional
							AND 	    m.tipo_id_tercero = k.tipo_tercero_id
							AND 	    m.tercero_id = k.tercero_id
							AND 	    l.tipo_id_tercero = m.tipo_id_tercero
							AND 	    l.tercero_id = m.tercero_id
							AND 	    r.tipo_profesional = m.tipo_profesional
							AND 	    o.orden_servicio_id = a.orden_servicio_id
							AND 	    p.plan_id = o.plan_id
              AND       pr.plan_id = o.plan_id
              AND       pr.rango = o.rango
              AND       pr.tipo_afiliado_id = o.tipo_afiliado_id
              AND	 	    n.paciente_id = a.paciente_id
							AND 	    n.tipo_id_paciente = a.tipo_id_paciente";      
								
				$result = $dbconnect->Execute($sql);
				if ($dbconnect->ErrorNo() != 0)
				{
						$this->error = "Error al Consultar los datos del examen";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						echo "<br>error 1";
						return false;
				}
				$a=$result->GetRowAssoc($ToUpper = false);


		    $query= " SELECT DISTINCT a.lab_examen_id, 
                         a.resultado_id, 
                         a.cargo, 
                         a.tecnica_id,
								         a.resultado, 
                         a.sw_alerta, 
                         a.rango_min, 
                         a.rango_max, 
                         a.unidades,
								         b.lab_plantilla_id, 
                         b.nombre_examen
                  FROM   hc_apoyod_resultados_detalles a, 
                         lab_examenes b
								  WHERE  a.resultado_id = ".$a[resultado_id]." 
                  AND    a.cargo= '".$a[cargo]."'
								  AND    a.tecnica_id = ".$a[tecnica_id]." 
                  AND    a.tecnica_id = b.tecnica_id
								  AND    a.cargo = b.cargo 
                  AND    a.lab_examen_id = b.lab_examen_id
                  ORDER BY b.lab_plantilla_id";
				$result = $dbconnect->Execute($query);
				if ($dbconnect->ErrorNo() != 0)
				{
						$this->error = "Error al consultar los resultados de los examenes";
						$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
						echo "<br>error 2";
						return false;
				}
				else
				{
						while (!$result->EOF)
						{
						$vector[]=$result->GetRowAssoc($ToUpper = false);
						$result->MoveNext();
						}
				}
				$a[detalle]=$vector;

       //datos adicionales para pintar el reporte
		   $query=" SELECT    b.profesional, 
                          d.*, 
                          g.descripcion as servicio,
						              e.historia_prefijo, 
                          e.historia_numero,
						              idr.fecha_resultado,
                          idr.comentario
						    FROM      os_maestro f, 
                          os_ordenes_servicios c 
                          LEFT JOIN hc_os_solicitudes_manuales_datos_adicionales d ON
						              (c.orden_servicio_id = d.orden_servicio_id), 
                          hc_os_solicitudes a 
                          LEFT JOIN hc_os_solicitudes_manuales b 
                          ON (a.hc_os_solicitud_id = b.hc_os_solicitud_id),
						              historias_clinicas e, 
                          servicios g, 
                          os_cumplimientos_detalle h,
                          interface_datalab_resultados idr
						    WHERE     f.numero_orden_id = '".$a[numero_orden_id]."' 
                AND       f.orden_servicio_id = c.orden_servicio_id 
                AND       f.hc_os_solicitud_id = a.hc_os_solicitud_id 
                AND       c.tipo_id_paciente = e.tipo_id_paciente 
                AND       c.paciente_id = e.paciente_id 
                AND       f.numero_orden_id = h.numero_orden_id
                AND       f.numero_orden_id = idr.numero_orden_id";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al Consultar los datos del examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				echo "<br>error 3";
				return false;
		}
		$datos=$result->GetRowAssoc($ToUpper = false);
		$a[datos_adicionales]=$datos;
	



		//cargando las observaciones adicionales
    $query= " SELECT  a.resultado_id, 
                      a.observacion_adicional,
						          a.fecha_registro_observacion, 
                      c.nombre_tercero as usuario_observacion
						  FROM    hc_resultados_observaciones_adicionales as a,
						          profesionales_usuarios as b, 
                      terceros as c
						  WHERE   resultado_id = ".$resultado_id." 
              AND     a.usuario_id = b.usuario_id
						  AND     b.tipo_tercero_id = c.tipo_id_tercero 
              AND     b.tercero_id = c.tercero_id
						  ORDER BY a.observacion_resultado_id";


		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
				$this->error = "Error al consultar las observaciones realizadas al Examen";
				$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
				echo "<br>error 4";
				return false;
		}
		else
		{
				while (!$result->EOF)
				{
					$observaciones[]=$result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
		}
		$a[observaciones_adicionales]=$observaciones;

	//	echo "<br>--->";print_r($a);
		return $a;
}
//MAuroB
function ConsultaFirmaMedico($usuario_id){
	list($dbconnect) = GetDBconn();
	$query="SELECT	a.nombre,
									a.descripcion,
									b.tarjeta_profesional
					FROM		system_usuarios a,
									profesionales b,
									tipos_profesionales c
					WHERE		a.usuario_id = $usuario_id AND
									a.usuario_id  = b.usuario_id  AND
									b.tipo_profesional  =  c.tipo_profesional
					";
	$result = $dbconnect->Execute($query);
	if ($dbconnect->ErrorNo() != 0)
	{
			$this->error = "Error al consultar medico que firma";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
	}
	while (!$result->EOF)
	{
		$prof=$result->GetRowAssoc($ToUpper = false);
		$result->MoveNext();
	}
	$result->close();
	return $prof;
}

//fin MAuroB

//detalle de los examnes de datalab
function ConsultaExamenesMaquinas($resultado)
{
	list($dbconnect) = GetDBconn();
	$query= " SELECT * 
            FROM   interface_datalab_resultados
	          WHERE  numero_orden_id = '$resultado'";

	$result = $dbconnect->Execute($query);
	if ($dbconnect->ErrorNo() != 0)
	{
			$this->error = "Error al consultar los resultados de los examenes";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
	}
	else
	{
			while (!$result->EOF)
			{
				$fact[]=$result->GetRowAssoc($ToUpper = false);
				$result->MoveNext();
			}
	}
	return $fact;
}

function FechaStampMostrar($fecha)
     {
            if($fecha){
                    $fech = strtok ($fecha,"-");
                    for($l=0;$l<3;$l++)
                    {
                        $date[$l]=$fech;
                        $fech = strtok ("-");
                    }
                    $mes = str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT);
                    $dia = str_pad(ceil($date[2]), 2, 0, STR_PAD_LEFT);
                    return  ceil($date[0])."-".$mes."-".$dia;
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
    //---------------------------------------




function ConsultaNombreUsuario($usuario_id)
{
	list($dbconnect) = GetDBconn();
	$query= " SELECT  usuario,
                    nombre 
            FROM    system_usuarios
						WHERE   usuario_id= ".$usuario_id."";

	$result = $dbconnect->Execute($query);
	if ($dbconnect->ErrorNo() != 0)
	{
			$this->error = "Error al Consultar el nombre del usuario";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
	}
	$a=$result->GetRowAssoc($ToUpper = false);
	return $a;
}
}

?>
