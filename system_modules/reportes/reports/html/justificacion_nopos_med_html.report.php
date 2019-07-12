<?php
//Reporte de prueba formato HTML
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class justificacion_nopos_med_html_report
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
    function justificacion_nopos_med_html_report($datos=array())
    {
		    $this->datos=$datos;
        return true;
    }

// 	//METODO PRIVADO NO MODIFICAR
// 	function GetParametrosReport()
// 	{
// 		$parametros = array('title' => $this->title,'author' => $this->author,'sizepage' => $this->sizepage,'Orientation'=> $this->Orientation,'grayScale' => $this->grayScale,'headers' => $this->headers,'footers' =>$this->footers )
// 		return $parametros;
// 	}
//
//

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

// 	function GetMembrete()
// 	{
// 		$Membrete = array('file'=>'MembreteDePrueba','datos_membrete'=>array('titulo'=>'ESTE ES EL TITULO DEL REPORTE',
// 																'subtitulo'=>'subtitulo',
// 																'logo'=>'logocliente.png',
// 																'align'=>'left'));
// 		return $Membrete;
// 	}
	
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
    if ($this->datos[invocado]== 1)
		{
		  $datos = $this->Reporte_Justificacion_nopos_ambulatorio();
		}
		elseif ($this->datos[invocado]== 2)
		{
      $datos = $this->Reporte_Justificacion_nopos_hospitalario();
		}
		$Salida.="<table  cellpadding= 1 cellspacing = 1 align=\"center\" border=\"0\"  width=\"100%\">";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">JUSTIFICACION DEL MEDICAMENTO NO POS</td>";
		$Salida.="</tr>";
		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}
		//DATOS DEL PACIENTE
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">DATOS DEL PACIENTE</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" colspan=\"1\" width=\"25%\">No. EVOLUCION :<br>FECHA DE EVOLUCION:<br>IDENTIFICACION:<br>PACIENTE:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" colspan=\"3\" width=\"75%\" >".$datos[0][evolucion_id]."<br>".$this->FechaStamp($datos[paciente][0][fecha])." - ".$this->HoraStamp($datos[paciente][0][fecha])."<br>".$datos[paciente][0][tipo_id_paciente]." : ".$datos[paciente][0][paciente_id]."<br>".strtoupper($datos[paciente][0][nombre])."</td>";
		//$Salida.="  <td class=\"Normal_10\" align=\"left\" colspan=\"3\" width=\"75%\" >".$datos[0][evolucion_id]."<br>".$datos[paciente][0][fecha]."<br>".$datos[paciente][0][tipo_id_paciente]." : ".$datos[paciente][0][paciente_id]."<br>".strtoupper($datos[paciente][0][nombre])."</td>";
		$Salida.="</tr>";

		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

		//DATOS DEL MEDICAMENTO
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">DATOS DEL MEDICAMENTO</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" colspan=\"1\" width=\"25%\">CODIGO: <br>PRODUCTO: <br>PRINCIPIO ACTIVO: <br>CONCENTRACION: <br>FORMA: </td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" colspan=\"3\" width=\"75%\" >".$datos[0][codigo_producto]."<br>".$datos[0][producto]."<br>".$datos[0][principio_activo]."<br>".$datos[0][concentracion_forma_farmacologica]." ".$datos[0][unidad_medida_medicamento_id]."<br>".$datos[0][forma]."</td>";
		$Salida.="</tr>";

		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

		//FORMULACION DEL MEDICAMENTO
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">FORMULACION DEL MEDICAMENTO</td>";
		$Salida.="</tr>";

    if ($datos[0][via]!='')
		{
			$Salida.="<tr>";
			$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">VIA DE ADMINISTRACION: </td>";
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][via]."</td>";
			$Salida.="</tr>";
		}

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">DOSIS: </td>";
		$e=$datos[0][dosis]/(floor($datos[0][dosis]));
		if($e==1)
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".floor($datos[0][dosis])." ".$datos[0][unidad_dosificacion]."</td>";
		}
		else
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][dosis]." ".$datos[0][unidad_dosificacion]."</td>";
		}
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">CANTIDAD: </td>";
		$e=($datos[0][cantidad])/(floor($datos[0][cantidad]));
		if ($datos[0][contenido_unidad_venta])
		{
			if($e==1)
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".floor($datos[0][cantidad])." ".$datos[0][descripcion]." por ".$datos[0][contenido_unidad_venta]."</td>";
			}
			else
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][cantidad]." ".$datos[0][descripcion]." por ".$datos[0][contenido_unidad_venta]."</td>";
			}
		}
		else
		{
			if($e==1)
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".floor($datos[0][cantidad])." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</td>";
			}
			else
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][cantidad]." ".$_SESSION['MEDICAMENTOSM'.$pfj][descripcion]."</td>";
			}
		}
		$Salida.="</tr>";
		if($datos[0][observacion]!='')
		{
			$Salida.="<tr>";
			$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">OBSERVACION: </td>";
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][observacion]."</td>";
			$Salida.="</tr>";
		}

		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">DOSIS POR DIA: </td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][dosis_dia]."</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">DIAS DE TRATAMIENTO: </td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][duracion]."</td>";
		$Salida.="</tr>";

		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}
		//DIAGNOSTICOS
    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">DIAGNOSTICOS</td>";
		$Salida.="</tr>";
		if ($datos[diagnosticos])
		{
			for($j=0;$j<sizeof($datos[diagnosticos]);$j++)
			{
        $Salida.="<tr>";
		    $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[diagnosticos][$j][diagnostico_id]." - ".$datos[diagnosticos][$j][diagnostico_nombre]."</td>";
				//$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[diagnosticos][$j][diagnostico_nombre]."</td>";
		    $Salida.="</tr>";
			}
		}
		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

		//DESCRIPCION DEL CASO CLINICO
    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">DESCRIPCION DEL CASO CLINICO</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[0][descripcion_caso_clinico]."</td>";
		$Salida.="</tr>";

		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}
    //ALTERNATIVAS POS PREVIAMENTE UTILIZADAS
    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
		$Salida.="</tr>";
		for ($j=1;$j<3;$j++)
		{
			if ($j==1)
			{
				 $Salida.="<tr>";
		     $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">PRIMERA POSIBILIDAD TERAPEUTICA POS</td>";
		     $Salida.="</tr>";
			}
			else
			{
				if ($datos[alternativas][$j-1][medicamento_pos]!='' OR $datos[alternativas][$j-1][principio_activo] != '')
				{
					$Salida.="<tr>";
		      $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">SEGUNDA POSIBILIDAD TERAPEUTICA POS</td>";
		      $Salida.="</tr>";
				}
				else
				{
					break;
				}
			}

			$Salida.="<tr>";
			$Salida.="<td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">";
			$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		  $Salida.="<tr>";
					$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">MEDICAMENTO: </td>";
					if ($datos[alternativas][$j-1][medicamento_pos]!='')
					{
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">".$a." ".$datos[alternativas][$j-1][medicamento_pos]."</td>";
					}
					else
					{
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">&nbsp;</td>";
					}

					$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">PRINCIPIO ACTIVO: </td>";
					if ($datos[alternativas][$j-1][principio_activo] != '')
					{
						$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">".$a." ".$datos[alternativas][$j-1][principio_activo]."</td>";
					}
					else
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">&nbsp;</td>";
					}
					$Salida.="</tr>";
			$Salida.="</tr>";
			$Salida.="</table>";
			$Salida.="</td>";
			$Salida.="</tr>";

			$Salida.="<tr>";
			$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">";
			$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$Salida.="<tr>";
					$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"20%\" colspan=\"1\">DOSIS POR DIA: </td>";
					if($datos[alternativas][$j-1][dosis_dia_pos]!='')
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">".$datos[alternativas][$j-1][dosis_dia_pos]."</td>";
					}
					else
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">&nbsp;</td>";
					}

					$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"20%\" colspan=\"1\">DURACION DEL TRATAMIENTO: </td>";
					if($datos[alternativas][$j-1][duracion_pos]!='')
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">".$datos[alternativas][$j-1][duracion_pos]."</td>";
					}
					else
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">&nbsp;</td>";
					}
					if ($datos[alternativas][$j-1][sw_no_mejoria]!= '1')
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"10%\" colspan=\"1\">MEJORIA: SI</td>";
					}
					else
					{
						$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"10%\" colspan=\"1\">MEJORIA: NO</td>";
					}
			$Salida.="</tr>";
			$Salida.="</table>";
			$Salida.="</td>";
			$Salida.="</tr>";


			$Salida.="<tr>";
			$Salida.="<td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">";
			$Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$Salida.="<tr>";
			if ($datos[alternativas][$j-1][sw_reaccion_secundaria]!= '1')
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">REACCION SECUNDARIA: NO</td>";
			}
			else
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">REACCION SECUNDARIA: SI</td>";
			}
			if($datos[alternativas][$j-1][reaccion_secundaria]!='')
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$a." ".$datos[alternativas][$j-1][reaccion_secundaria]."</td>";
			}
			else
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">&nbsp;</td>";
			}
			$Salida.="</tr>";



			$Salida.="<tr>";
			if ($datos[alternativas][$j-1][sw_contraindicacion]!= '1')
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">CONTRAINDICACION EXPRESA: NO</td>";
			}
			else
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">CONTRAINDICACION EXPRESA: SI</td>";
			}

			if($datos[alternativas][$j-1][contraindicacion]!='')
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$a." ".$datos[alternativas][$j-1][contraindicacion]."</td>";
			}
			else
			{
				$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">&nbsp;</td>";
			}
			$Salida.="</tr>";

			$Salida.="<tr>";
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">OTRAS</td>";
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$a." ".$datos[alternativas][$j-1][otras]."</td>";
			$Salida.="</tr>";

			$Salida.="<tr>";
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
			$Salida.="</tr>";
			$Salida.="</table>";
			$Salida.="</td>";
			$Salida.="</tr>";
		}
		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}
    //criterios que justifican la solicitud

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">CRITERIOS DE JUSTIFICACION</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">JUSTIFICACION DE LA SOLICITUD:</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[0][justificacion]."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">VENTAJAS DE ESTE MEDICAMENTO:</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[0][ventajas_medicamento]."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">VENTAJAS DEL TRATAMIENTO:</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[0][ventajas_tratamiento]."</td>";
		$Salida.="</tr>";
    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";


		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">PRECAUCIONES: CONTRAINDICACIONES, EFECTOS SECUNDARIOS Y TOXICIDAD ASOCIADA.</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[0][precauciones]."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>";
		$Salida.="</tr>";
    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[0][controles_evaluacion_efectividad]."</td>";
		$Salida.="</tr>";
    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">TIEMPO DE RESPUESTA ESPERADO: </td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$datos[0][tiempo_respuesta_esperado]."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		if ($datos[0][sw_riesgo_inminente]!= '1')
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">RIESGO INMINENTE: NO</td>";
		}
		else
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">RIESGO INMINENTE: SI</td>";
		}
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".$a." ".$datos[0][riesgo_inminente]."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">&nbsp;</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES: </td>";
		if ($datos[0][sw_agotadas_posibilidades_existentes]!= '1')
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">NO</td>";
		}
		else
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">SI</td>";
		}
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">TIENE HOMOLOGO EN EL POS:</td>";
		if ($datos[0][sw_homologo_pos]!= '1')
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">NO</td>";
		}
		else
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">SI</td>";
		}
		$Salida.="</tr>";

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"25%\" colspan=\"1\">ES COMERCIALIZADO EN EL PAIS:</td>";
		if ($datos[0][sw_comercializacion_pais]!= '1')
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">NO</td>";
		}
		else
		{
			$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">SI</td>";
		}
		$Salida.="</tr>";


		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

    $Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10\" align=\"justify\" width=\"100%\" colspan=\"4\">Para el trámite de esta solicitud en su aseguradora sugerimos presentar adicionalmente, copia del carné de la EPS y del documento de identificacion, el original de la formula médica y el resumen o copia de la historia clinica.  La entrega del medicamento está sujeta a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido en el acuerdo 228 de 2002.</td>";
		$Salida.="</tr>";


		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\">&nbsp;</td>";
			$Salida.="</tr>";
		}

		$Salida.="<tr><td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">MEDICO TRATANTE:</td></tr>";
		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\">&nbsp;</td>";
			$Salida.="</tr>";
		}

    $largo = strlen($datos[paciente][0][nombre_tercero]);
		$cad = '___';
		for ($l=0; $l<$largo; $l++)
		{
      $cad = $cad.'_';
    }

		if($datos[paciente][0][tarjeta_profesional] != '')
		{
			$Salida.="<tr class=\"Normal_10N\">";
			$Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\" colspan = 4>".$cad."<br>".strtoupper($datos[paciente][0][nombre_tercero])."<br>".$datos[paciente][0][tipo_id_medico].': '.$datos[paciente][0][medico_id].' T.P.: '.$datos[paciente][0][tarjeta_profesional]."<br>".$datos[paciente][0][tipo_profesional]."</td>";
			$Salida.="</tr>";
		}
		else
		{
			$Salida.="<tr class=\"Normal_10N\">";
			$Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\" colspan = 4>".$cad."<br>".strtoupper($datos[paciente][0][nombre_tercero])."<br>".$datos[paciente][0][tipo_id_medico].': '.$datos[paciente][0][medico_id]."<br>".$datos[paciente][0][tipo_profesional]."</td>";
			$Salida.="</tr>";
		}
  	return $Salida;
 }



//AQUI TODOS LOS METODOS QUE USTED QUIERA


function Reporte_Justificacion_nopos_ambulatorio()
{
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
	  $query= "select	c.descripcion as principio_activo, b.contenido_unidad_venta,
		b.descripcion as producto, p.unidad_dosificacion, q.nombre as via, p.cantidad, p.observacion,
		p.dosis, a.hc_justificaciones_no_pos_amb, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos

		from hc_justificaciones_no_pos_amb as a,	medicamentos as k,
		inv_med_cod_forma_farmacologica as n, hc_medicamentos_recetados_amb p
		left join hc_vias_administracion q on (p.via_administracion_id = q.via_administracion_id),
		inventarios_productos	as b, inv_med_cod_principios_activos as c

		where	a.codigo_producto = '".$this->datos[codigo_producto]."' and
		a.evolucion_id = ".$this->datos[evolucion]." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica =
		n.cod_forma_farmacologica and a.codigo_producto = p.codigo_producto and
		a.evolucion_id = p.evolucion_id
		and a.codigo_producto = b.codigo_producto and k.cod_principio_activo = c.cod_principio_activo
		and b.codigo_producto = k.codigo_medicamento ";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_justificacion[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
		$result->Close();

//consulta de los diagnosticos de la justificacion
		$query= "select a.hc_justificaciones_no_pos_amb, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_amb_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_amb = ".$vector_justificacion[0][hc_justificaciones_no_pos_amb]."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_amb_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_diagnostico[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
  $vector_justificacion[diagnosticos]= $vector_diagnostico;
	$result->Close();
//fin de los diagnosticos

//CONSULTA DE LAS ALTERNATIVAS
  $query= "select a.alternativa_pos_id, a.medicamento_pos,
		a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
		a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
		a.sw_contraindicacion, a.contraindicacion,
		a.otras, a.hc_justificaciones_no_pos_amb
		from hc_justificaciones_no_pos_respuestas_pos as a
 		where (a.hc_justificaciones_no_pos_amb = ".$vector_justificacion[0][hc_justificaciones_no_pos_amb].")";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$vector_alternativas[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[alternativas]= $vector_alternativas;
		$result->Close();
		//FIN DE LAS ALTERNATIVAS

//OBTENER DATOS DEL PACIENTE
    $query= "
				select g.nombre_tercero, g.tipo_id_tercero as tipo_id_medico,
				g.tercero_id as medico_id, h.tarjeta_profesional,
				j.descripcion as tipo_profesional,
				e.tipo_id_tercero, e.id, e.razon_social,
			  a.fecha, b.tipo_id_paciente, b.paciente_id,
			  btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
				c.primer_apellido||' '||c.segundo_apellido,'') as nombre
				from hc_evoluciones a, ingresos b, pacientes c,
				departamentos d, empresas e,
        profesionales_usuarios f, terceros g, profesionales h,
				tipos_profesionales j
				where a.evolucion_id = ".$vector_justificacion[0][evolucion_id]." and
				a.ingreso = b.ingreso and
				b.tipo_id_paciente = c.tipo_id_paciente  and b.paciente_id = c.paciente_id
				and b.departamento = d.departamento and d.empresa_id = e.empresa_id
        and a.usuario_id = f.usuario_id and  f.tipo_tercero_id = g.tipo_id_tercero
				AND f.tercero_id = g.tercero_id and f.tipo_tercero_id = h.tipo_id_tercero
				AND f.tercero_id = h.tercero_id and h.tipo_profesional = j.tipo_profesional";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$paciente[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[paciente]= $paciente;
		$result->Close();
		//FIN DE DATOS DEL PACIENTE
    return $vector_justificacion;

}


function Reporte_Justificacion_nopos_hospitalario()
{
    $pfj=$this->frmPrefijo;
		list($dbconnect) = GetDBconn();
	  $query= "select	c.descripcion as principio_activo, b.contenido_unidad_venta,
		b.descripcion as producto, p.unidad_dosificacion, q.nombre as via, p.cantidad, p.observacion,
		p.dosis, a.hc_justificaciones_no_pos_hosp, a.evolucion_id, n.descripcion as forma,
		k.concentracion_forma_farmacologica, k.unidad_medida_medicamento_id, a.codigo_producto,
		a.usuario_id_autoriza, a.duracion, a.dosis_dia, a.justificacion, a.ventajas_medicamento,
		a.ventajas_tratamiento, a.precauciones, a.controles_evaluacion_efectividad,
		a.tiempo_respuesta_esperado, a.riesgo_inminente, a.sw_riesgo_inminente,
		a.sw_agotadas_posibilidades_existentes, a.sw_comercializacion_pais, a.sw_homologo_pos,
		a.descripcion_caso_clinico, a.sw_existe_alternativa_pos

		from hc_justificaciones_no_pos_hosp as a,	medicamentos as k,
		inv_med_cod_forma_farmacologica as n, hc_medicamentos_recetados_hosp p
		left join hc_vias_administracion q on (p.via_administracion_id = q.via_administracion_id),
		inventarios_productos	as b, inv_med_cod_principios_activos as c

		where	a.codigo_producto = '".$this->datos[codigo_producto]."' and
		a.evolucion_id = ".$this->datos[evolucion]." and
		a.codigo_producto = k.codigo_medicamento and k.cod_forma_farmacologica =
		n.cod_forma_farmacologica and a.codigo_producto = p.codigo_producto and
		a.evolucion_id = p.evolucion_id
		and a.codigo_producto = b.codigo_producto and k.cod_principio_activo = c.cod_principio_activo
		and b.codigo_producto = k.codigo_medicamento ";
		$result = $dbconnect->Execute($query);

		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar la justificacion";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_justificacion[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
		$result->Close();

//consulta de los diagnosticos de la justificacion
		$query= "select a.hc_justificaciones_no_pos_hosp, a.diagnostico_id,
		b.diagnostico_nombre from hc_justificaciones_no_pos_hosp_diagnostico as a,
		diagnosticos as b where a.diagnostico_id = b.diagnostico_id and
		a.hc_justificaciones_no_pos_hosp = ".$vector_justificacion[0][hc_justificaciones_no_pos_hosp]."";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_diagnostico";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			  $vector_diagnostico[]=$result->GetRowAssoc($ToUpper = false);
			  $result->MoveNext();
			}
		}
  $vector_justificacion[diagnosticos]= $vector_diagnostico;
	$result->Close();
//fin de los diagnosticos

//CONSULTA DE LAS ALTERNATIVAS
  $query= "select a.alternativa_pos_id, a.medicamento_pos,
		a.principio_activo, a.dosis_dia_pos, a.duracion_pos,
		a.sw_no_mejoria, a.sw_reaccion_secundaria, a.reaccion_secundaria,
		a.sw_contraindicacion, a.contraindicacion,
		a.otras, a.hc_justificaciones_no_pos_hosp
		from hc_justificaciones_no_pos_hosp_respuestas_pos as a
 		where (a.hc_justificaciones_no_pos_hosp = ".$vector_justificacion[0][hc_justificaciones_no_pos_hosp].")";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$vector_alternativas[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[alternativas]= $vector_alternativas;
		$result->Close();
		//FIN DE LAS ALTERNATIVAS

//OBTENER DATOS DEL PACIENTE
    $query= "
				select g.nombre_tercero, g.tipo_id_tercero as tipo_id_medico,
				g.tercero_id as medico_id, h.tarjeta_profesional,
				j.descripcion as tipo_profesional,
				e.tipo_id_tercero, e.id, e.razon_social,
			  a.fecha, b.tipo_id_paciente, b.paciente_id,
			  btrim(c.primer_nombre||' '||c.segundo_nombre||' '||
				c.primer_apellido||' '||c.segundo_apellido,'') as nombre
				from hc_evoluciones a, ingresos b, pacientes c,
				departamentos d, empresas e,
        profesionales_usuarios f, terceros g, profesionales h,
				tipos_profesionales j
				where a.evolucion_id = ".$vector_justificacion[0][evolucion_id]." and
				a.ingreso = b.ingreso and
				b.tipo_id_paciente = c.tipo_id_paciente  and b.paciente_id = c.paciente_id
				and b.departamento = d.departamento and d.empresa_id = e.empresa_id
        and a.usuario_id = f.usuario_id and  f.tipo_tercero_id = g.tipo_id_tercero
				AND f.tercero_id = g.tercero_id and f.tipo_tercero_id = h.tipo_id_tercero
				AND f.tercero_id = h.tercero_id and h.tipo_profesional = j.tipo_profesional";

		$result = $dbconnect->Execute($query);
		if ($dbconnect->ErrorNo() != 0)
		{
			$this->error = "Error al consultar hc_justificaciones_no_pos_hosp_respuestas_pos";
			$this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
			return false;
		}
		else
		{
			while (!$result->EOF)
			{
			$paciente[]=$result->GetRowAssoc($ToUpper = false);
			$result->MoveNext();
			}
		}
		$vector_justificacion[paciente]= $paciente;
		$result->Close();
		//FIN DE DATOS DEL PACIENTE
    return $vector_justificacion;

}

function FechaStamp($fecha)
	{
			if($fecha){
					$fech = strtok ($fecha,"-");
					for($l=0;$l<3;$l++)
					{
						$date[$l]=$fech;
						$fech = strtok ("-");
					}
					return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
			}
	}

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
}

?>
