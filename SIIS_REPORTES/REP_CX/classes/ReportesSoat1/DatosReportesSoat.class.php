<?php

	class fpdf_reporte_soat1 extends FPDF
	{
			var $correcion_x;
			var $correcion_y;
	
			function fpdf_reporte_soat1($orientation='P',$unit='mm',$format='letter')
			{
					$this->correcion_x = 1;
					$this->correcion_y = 1;
					$this->FPDF($orientation='P',$unit='mm',$format='letter');
			}
	
			function set_correcion_x($valor)
			{
					if(is_numeric($valor))
					{
							$this->correcion_x = $valor;
					}
			}
	
			function set_correcion_y($valor)
			{
					if(is_numeric($valor))
					{
							$this->correcion_y = $valor;
					}
			}
	
			function Text_corregida($x,$y,$txt)
			{
					$this->Text($x * $this->correcion_x, $y * $this->correcion_y, $txt);
			}
//**********************************************
	function TraerDatos($TipoDo,$Docume,$evento)
	{
		list($dbconn) = GetDBconn();
		$query = "SELECT A.residencia_direccion,
				A.residencia_telefono,
				A.fecha_nacimiento,
				--B.descripcion AS dessexo,
				B.sexo_id,
				C.municipio AS munipaciente,
				D.poliza,
				CASE WHEN D.asegurado='1' THEN 'SI'
					WHEN D.asegurado='2' THEN 'NO'
					WHEN D.asegurado='4' THEN 'POLIZA FALSA'
					WHEN D.asegurado='5' THEN 'POLIZA VENCIDA'
				ELSE 'FANTASMA' END AS asegura,
				E.descripcion AS descondicion,
				F.fecha_accidente,
				F.sitio_accidente,
				F.informe_accidente,
				G.municipio AS muniaccidente,
				H.descripcion AS deszona,
				I.departamento,
				J.poliza,
				J.vigencia_desde,
				J.vigencia_hasta,
				J.sucursal,
				J.placa_vehiculo,
				J.marca_vehiculo,
				J.tipo_vehiculo,
				K.nombre_tercero,
				M.apellidos_conductor,
				M.nombres_conductor,
				M.tipo_id_conductor,
				M.conductor_id,
				M.direccion_conductor,
				M.telefono_conductor,
				N.tipo_id_tercero,
				N.id,
				N.direccion,
				N.telefonos,
				O.municipio AS muniempresa,
				P.municipio AS munivehiculo,
				Q.fecha_remision,
				R.descripcion AS descentro,
				S.municipio AS municentro
				FROM pacientes AS A,
				tipo_sexo AS B,
				tipo_mpios AS C,
				soat_eventos AS D
				LEFT JOIN condicion_accidentados AS E ON
				(D.condicion_accidentado=E.condicion_accidentado
				AND D.evento=".$evento.")
				LEFT JOIN soat_vehiculo_conductor AS M ON
				(M.evento=".$evento.")
				LEFT JOIN tipo_mpios AS P ON
				(M.tipo_pais_id=P.tipo_pais_id
				AND M.tipo_dpto_id=P.tipo_dpto_id
				AND M.tipo_mpio_id=P.tipo_mpio_id)
				LEFT JOIN soat_remision AS Q ON
				(Q.evento=".$evento."
				AND Q.remision_id=
					(SELECT MAX(remision_id)
					FROM soat_remision
					WHERE evento=".$evento."))
				LEFT JOIN centros_remision AS R ON
				(Q.centro_remision=R.centro_remision)
				LEFT JOIN tipo_mpios AS S ON
				(R.tipo_pais_id=S.tipo_pais_id
				AND R.tipo_dpto_id=S.tipo_dpto_id
				AND R.tipo_mpio_id=S.tipo_mpio_id),
				soat_accidente AS F,
				tipo_mpios AS G,
				zonas_residencia AS H,
				tipo_dptos AS I,
				soat_polizas AS J,
				terceros AS K,
				empresas AS N,
				tipo_mpios AS O
				WHERE A.tipo_id_paciente='".$TipoDo."'
				AND A.paciente_id='".$Docume."'
				AND A.sexo_id=B.sexo_id
				AND A.tipo_pais_id=C.tipo_pais_id
				AND A.tipo_dpto_id=C.tipo_dpto_id
				AND A.tipo_mpio_id=C.tipo_mpio_id
				AND A.tipo_id_paciente=D.tipo_id_paciente
				AND A.paciente_id=D.paciente_id
				AND D.evento=".$evento."
				AND D.accidente_id=F.accidente_id
				AND F.tipo_pais_id=G.tipo_pais_id
				AND F.tipo_dpto_id=G.tipo_dpto_id
				AND F.tipo_mpio_id=G.tipo_mpio_id
				AND F.zona=H.zona_residencia
				AND F.tipo_pais_id=I.tipo_pais_id
				AND F.tipo_dpto_id=I.tipo_dpto_id
				AND D.poliza=J.poliza
				AND J.tipo_id_tercero=K.tipo_id_tercero
				AND J.tercero_id=K.tercero_id
				AND D.empresa_id=N.empresa_id
				AND N.tipo_pais_id=O.tipo_pais_id
				AND N.tipo_dpto_id=O.tipo_dpto_id
				AND N.tipo_mpio_id=O.tipo_mpio_id;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$EdadArr=CalcularEdad($var['fecha_nacimiento'],'');
		$edad=explode(' ',$EdadArr['edad_aprox']);
		$fecha=explode(' ',$var['fecha_accidente']);
		$var['hora']=$fecha[1];
		$fecha=explode('-',$fecha[0]);
		$var['fecha_accidente']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
		$fecha=explode('-',$var['vigencia_desde']);
		$var['vigencia_desde']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
		$fecha=explode('-',$var['vigencia_hasta']);
		$var['vigencia_hasta']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
		$fecha=explode(' ',$var['fecha_remision']);
		$fecha=explode('-',$fecha[0]);
		$var['fecha_remision']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];

		//VECTOR 1
		$vect[61][54][$_SESSION['soa1']['razonso']];//61,54,
echo $vect[61][54][$_SESSION['soa1']['razonso']];exit;
		if($var[tipo_id_tercero] AND $var[id])
		{
			$vect[138][54]=$var[tipo_id_tercero].'-'.$var[id];//138,54,
		}
		if($var[direccion] AND $var[muniempresa] AND $var[telefonos])
		{
			$vect[36][59]=$var[direccion];//36,59
			$vect[112][59]=$var[muniempresa];//112,59
			$vect[149][59]=$var[telefonos];//149,59
		}

		//VECTOR 2
		$vect[79][1]=$edad[0];//EDAD 79,71
		if($var[sexo_id]=='M')
		{
			$vect[2][1][2]=$var[sexo_id]; //91,71,
		}
		elseif($var[sexo_id]=='F')
		{
			$vect[2][1][2]=$var[sexo_id]; //98,71
		}
		if($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='CC')
		{
			$vect[2][1][3]='X';//136,71
		}
		elseif($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='CE')
		{
			$vect[2][1][3]='X';//146,71
		}
		$vect[2][1][4]=$_SESSION['soat']['evento']['nombresoat']['paciente_id'];//162,71
//$pdf->Text(162,79,'CALI');
		$vect[2][2][1]=$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];//46,79
		if($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='TI')
		{
			$vect[2][2][2]='X';//135,79
		}
		elseif($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='PA')
		{
			$vect[2][2][2]='X';//148,79
		}
		$vect[2][3][1]=$var['residencia_direccion'];//32,84
		$vect[2][3][2]=$var['munipaciente'];//112,84
		$vect[2][3][3]=$var['residencia_telefono'];//162,84
		if($var['descondicion']=='Ocupante')
		{
			$vect[2][4][1]=$var['descondicion'];//70,89
		}
		else
		if($var['descondicion']=='Peaton')
		{
			$vect[2][4][1]=$var['descondicion'];//93,89
		}
		$f=explode('/',$var['fecha_accidente']);
$vect[2][5][1]=$f[2];//117,92 año
$vect[2][5][1]=$f[1];//127,92, mes
$vect[2][5][1]=$f[0];//133,92 dia;
		//$vect[2][5][1]=$var['fecha_accidente'];
		$vect[2][5][2]=$var['hora'];//155,92
		$h=explode(':',$var['hora']);//172,92
		if($h[0]>='12')
		{
			$vect[2][5][2]='X';//172,92
		}
		else
		{
			$vect[2][5][2]='X';//180,92
		}

		$vect[2][6][1]=$var['sitio_accidente'];//69,98
		$vect[2][7][1]=$var['muniaccidente'];//31,104
		$vect[2][7][2]=$var['departamento'];//91,104
		if($var['deszona']=='Urbana')
		{
		$vect[2][7][3]='X';//150,104
		}
		else
		{
		$vect[2][7][3]='X';//171,104
		}
		$vect[2][7][3]=$var['deszona'];
		$vect[2][8][1]=$var['informe_accidente'];
		$vect[2][9][1]=$var['marca_vehiculo'];
		$vect[2][9][2]=$var['placa_vehiculo'];
		$vect[2][9][3]=$var['tipo_vehiculo'];
		$vect[2][10][1]=$var['nombre_tercero'];
		$vect[2][10][2]=$var['sucursal'];
		$vect[2][11][1]=$var['asegura'];
		$vect[2][11][2]=$var['poliza'];
		$vect[2][11][3]=$var['vigencia_desde'];
		$vect[2][11][4]=$var['vigencia_hasta'];
		$vect[2][12][1]=$var['apellidos_conductor'].' '.$var['nombres_conductor'];
		$vect[2][12][2]=$var['tipo_id_conductor'];
		$vect[2][12][3]=$var['conductor_id'];
		$vect[2][13][1]=$var['direccion_conductor'];
		$vect[2][13][2]=$var['munivehiculo'];
		$vect[2][13][3]=$var['telefono_conductor'];

		//VECTOR 3
		$query = "SELECT A.ingreso,
				B.fecha_ingreso,
				C.via_ingreso_nombre
				FROM ingresos_soat AS A,
				ingresos AS B,
				vias_ingreso AS C
				WHERE A.evento=".$evento."
				AND A.ingreso=B.ingreso
				AND B.via_ingreso_id=C.via_ingreso_id
				ORDER BY A.ingreso;";
		$resulta = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		while(!$resulta->EOF)
		{
			$var2=$resulta->GetRowAssoc($ToUpper = false);
			$resulta->MoveNext();
		}
		$fecha=explode(' ',$var2['fecha_ingreso']);
		$vect[3][1][2]=$fecha[1]; //hora ingreso
		$fecha=explode('-',$fecha[0]);
		$vect[3][1][1]=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];//fecha ingreso
		$vect[3][1][3]=$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']; //historia clinica
		if($var2['ingreso']<>NULL)
		{
			$query = "SELECT evolucion_id,
					fecha_cierre,
					estado
					FROM hc_evoluciones
					WHERE ingreso=".$var2['ingreso'].";";
			$resulta = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
				$var3[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			//si estado en cero, esta cerrada y tiene fecha de cierre
			//si estado en dos, esta en pendiente y tiene fecha de cierre
			$i=sizeof($var3)-1;//esto o un ciclo
			/*if($var3[$i]['estado']==0 OR $var3[$i]['fecha_cierre']<>NULL)
			{
			}*/
			$fecha=explode(' ',$var3[$i]['fecha_cierre']);
			$var['hora_egreso']=$fecha[1];
			$fecha=explode('-',$fecha[0]);
			$vect[3][2][1]=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];//fecha egreso
			$vect[3][2][2]=$vect[3][2][1]-$vect[3][1][1];//dias_estancia
			if(empty($vect[3][2][2]))
			{
				$vect[3][2][2]='0';//dias_estancia
			}
			$vect[3][2][3]=$var2['via_ingreso_nombre'];

		if($var3[0]['evolucion_id']<>NULL)
		{
			$query = "SELECT B.diagnostico_nombre AS ingreso
					FROM hc_diagnosticos_ingreso AS A,
					diagnosticos AS B
					WHERE A.evolucion_id=".$var3[0]['evolucion_id']."
					AND A.tipo_diagnostico_id=B.diagnostico_id
					ORDER BY A.sw_principal DESC;";
			$resulta = $dbconn->Execute($query);//la primera evolucion
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
				$var4[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			$query = "SELECT B.diagnostico_nombre AS egreso
					FROM hc_diagnosticos_egreso AS A,
					diagnosticos AS B
					WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
					AND A.tipo_diagnostico_id=B.diagnostico_id;";//ORDER BY sw_principal DESC
			$resulta = $dbconn->Execute($query);//la ultima evolucion
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
				$var5[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			$query = "SELECT B.diagnostico_nombre AS muerte
					FROM hc_diagnosticos_muerte AS A,
					diagnosticos AS B
					WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
					AND A.tipo_diagnostico_id=B.diagnostico_id;";//ORDER BY sw_principal DESC
			$resulta = $dbconn->Execute($query);//la ultima evolucion
			if ($dbconn->ErrorNo() != 0)
			{
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}
			while(!$resulta->EOF)
			{
				$var6[]=$resulta->GetRowAssoc($ToUpper = false);
				$resulta->MoveNext();
			}
			for($j=0;$j<sizeof($var4);$j++)
			{
				$vect[3][3][1].=$var4[$j]['ingreso'];//diagnostico de ingreso
				if(empty($var5))
				{
					$vect[3][4][1].=$var4[$j]['ingreso'];//diagnostico de egreso
				}
			}
			for($j=0;$j<sizeof($var5);$j++)
			{
				$vect[3][4][1].=$var5[$j]['egreso'];//desc_diagnostico_de _EGRESO
			}
/*			for($j=0;$j<sizeof($var6);$j++)
			{
//*****************************************************
				$var['causa_muerte'].=$var6[$j]['muerte'];//causa_muerte
//*****************************************************
			}*/
		}
		if($var[descentro]==NULL)
		{
			$var[descentro]='****';
			$var[municentro]='****';
			$var[fecha_remision]='****';
			$vect[3][5][1]='---';
			$vect[3][5][2]='---';
			$vect[3][5][3]='---';
		}
		else
		{
			$vect[3][5][1]=$_SESSION['soa1']['razonso'];//PERSONA REMITIDA DE:
			$vect[3][5][2]=$var[muniempresa];
			$vect[3][5][3]=$var[fecha_remision];
		}
		$vect[3][6][1]=$var[descentro];//PERSONA REMITIDA A:
		$vect[3][6][2]=$var[municentro];
		$vect[3][6][3]=$var[fecha_remision];
		}

//VECTOR 4
		for($j=0;$j<sizeof($var6);$j++)
		{
			$vect[4][1][1].=$var6[$j]['muerte'];//causa_muerte
		}
		return $vect;
	}

//**********************************************
		//$datos=TraerDatos($TipoDo,$Docume,$evento);
/*echo '<pre>';
print_r($datos,false);
echo '</pre>';*/
		$Dir="cache/reclamacion_entidades_1.pdf";
		require("classes/fpdf/html_class.php");
		define('FPDF_FONTPATH','font/');
		//$pdf=new PDF('P','mm','legal');//letter2
		$pdf=new fpdf_reporte_soat1($orientation='P',$unit='mm',$format='letter');
		$pdf->set_correcion_x(1);
		$pdf->set_correcion_y(1);
		$datos=$pdf->TraerDatos($TipoDo,$Docume,$evento);
		$pdf->AddPage();
		$pdf->SetFont('Arial','',8);

		foreach($datos as $k=>$v)
		{
				$pdf->Text_corregida($v[0],$v[1],$v[2]);
		}
		
		$pdf->Output($Dir,'F');
		return true;
	}//end of class

// 	function GenerarReporte($TipoDo,$Docume,$evento)
// 	{
// 		$datos=TraerDatos($TipoDo,$Docume,$evento);
// echo '<pre>';
// print_r($datos,false);
// echo '</pre>';
// 		$Dir="cache/reclamacion_entidades_1.pdf";
// 		require("classes/fpdf/html_class.php");
// 		define('FPDF_FONTPATH','font/');
// 		//$pdf=new PDF('P','mm','legal');//letter2
// 		$pdf=new fpdf_reporte_soat1($orientation='P',$unit='mm',$format='letter');
// 		$pdf->set_correcion_x(1);
// 		$pdf->set_correcion_y(1);
// 		$pdf->AddPage();
// 		$pdf->SetFont('Arial','',8);
// 
// 		foreach($datos as $k=>$v)
// 		{
// 				$pdf->Text_corregida($v[0],$v[1],$v[2]);
// 		}
// 		
// 		$pdf->Output($Dir,'F');
// //
// 		$fecha_envio=date('Y  m  d');
// //BLOQUE 1
// 		$nombre_centro=$datos[1][1][1]; 
// 		$nit=$datos[1][1][2]; 
// 		$direccion=$datos[1][2][1]; 
// 		$ciudad=$datos[1][2][2]; 
// 		$telefono=$datos[1][2][3]; 
// //BLOQUE 2-1
// 		$edad=$datos[2][1][1];
// 		$sexo=$datos[2][1][2];
// 		$tipo_documento=$datos[2][1][3];
// 		$documento=$datos[2][1][4];
// //BLOQUE 2-2
// 		$apellidos_nombres=$datos[2][2][1];
// //BLOQUE 2-3
// 		$direccion=$datos[2][3][1];
// 		$ciudad=$datos[2][3][2];
// 		$telefono=$datos[2][3][3];
// //BLOQUE 2-4
// 		$condicion=$datos[2][4][1];
// //BLOQUE 2-5
// 		$fecha=$datos[2][5][1];
// 		$hora=$datos[2][5][2];
// //BLOQUE 2-6
// 		$sitio=$datos[2][6][1];
// //BLOQUE 2-7
// 		$municipio=$datos[2][7][1];
// 		$departamento=$datos[2][7][2];
// 		$zona=$datos[2][7][3];
// //BLOQUE 2-8
// 		$informe=$datos[2][8][1];
// //BLOQUE 2-9
// 		$marca=$datos[2][9][1];
// 		$placa=$datos[2][9][2];
// 		$tipo=$datos[2][9][3];
// //BLOQUE 2-10
// 		$aseguradora=$datos[2][10][1];
// 		$sucursal=$datos[2][10][2];
// //BLOQUE 2-11
// 		$asegurado=$datos[2][11][1];
// 		$poliza=$datos[2][11][2];
// 		$fecha_desde=$datos[2][11][3];
// 		$fecha_hasta=$datos[2][11][4];
// //BLOQUE 2-12
// 		$conductor=$datos[2][12][1];
// 		$tipo_id=$datos[2][12][2];
// 		$id=$datos[2][12][3];
// //BLOQUE 2-13
// 		$dir_conductor=$datos[2][13][1];
// 		$ciudad_conductor=$datos[2][13][2];
// 		$tel_conductor=$datos[2][13][3];
// //BLOQUE 3-1
// 		$fecha_ingreso=$datos[3][1][1];
// 		$hora_ingreso=$datos[3][1][2];
// 		$historia=$datos[3][1][3];
// //BLOQUE 3-2
// 		$fecha_egreso=$datos[3][1][1];
// 		$dias_estancia=$datos[3][1][2];
// 		$tratamiento=$datos[3][1][3];
// //BLOQUE 3-3
// 		$diag_ingreso=$datos[3][3][1];
// //BLOQUE 3-4
// 		$diag_definitivo=$datos[3][4][1];
// //BLOQUE 3-5
// 		$institucion_remitida_de=$datos[3][5][1];
// 		$ciudad_remitida_de=$datos[3][5][2];
// 		$fecha_remitida_de=$datos[3][5][3];
// //BLOQUE 3-6
// 		$institucion_remitida_a=$datos[3][6][1];
// 		$ciudad_remitida_a=$datos[3][6][2];
// 		$fecha_remitida_a=$datos[3][6][3];
// //BLOQUE 4-1
// 		$causamuerte=$datos[4][1][1];
// //
// 		$html="<br><br>";
// 		$html.="<br><br>                                                                      $fecha_envio                    ";
// //$pdf->Text(float x, float y, string txt);
// 		$pdf->Text(61, 54, $nombre_centro);
// 		$html.="";
// 		$html.="                                                                            $nombre_centro                                                                      $nit";
// 		$html.="<p>";
// 		//$html.="                                          $direccion                                                                         $ciudad                                              $telefono";
// 		$html.="<p>";
// 		$html.="                                                                              $edad          $sexo                 $tipo_documento                                $documento";
// 		$html.="<p>";
// 		$html.="                                                      $apellidos_nombres";
// 		$html.="<p>";
// 		$html.="                                       $direccion                                                                $ciudad                                            $telefono";
// 		$html.="<p>";
// 		$html.="                       $condicion";
// 		$html.="<p>";
// 		$html.="";
// 		$html.="                                                                                                                                                 $fecha                                   $hora";
// 		$html.="<p>";
// 		$html.="                                               $sitio";
// 		$html.="<p>";
// 		$html.="                                       $municipio                                                 $departamento                                      $zona";
// 		$html.="<p>";
// 		$html.="<p>";
// 		$html.="$informe";
// 		$html.="<p>";
// 		$html.="<p>";
// 		$html.="                       $marca                                                                       $placa                                              $tipo";
// 		$html.="<p>";
// 		$html.="<p>";
// 		$html.="                                                            $aseguradora                                                             $sucursal";
// 		$html.="<p>";
// 		$html.="        $asegurado                       $poliza                              $fecha_desde                        $fecha_hasta";
// 		$html.="<p>";
// 		$html.="$conductor                                                                                     $tipo_id                                          $id";
// 		$html.="<p>";
// 		$html.="                            $dir_conductor                                                            $ciudad_conductor                                          $tel_conductor";
// 		$html.="<p>";
// 		$html.="<p>";
// 		$html.="                         $fecha_ingreso                                  $hora_ingreso                                      $historia";
// 		$html.="<p>";
// 		$html.="                         $fecha_egreso                                   $dias_estancia                                     $tratamiento";
// 		$html.="<p>";
// 		$html.="                                                        $diag_ingreso";
// 		$html.="<p>";
// 		$html.="                                                        $diag_definitivo";
// 		$html.="<p>";
// 		$html.="                                                                 $institucion_remitida_de                                 $ciudad_remitida_de                                 $fecha_remitida_de";
// 		$html.="<p>";
// 		$html.="                                                                 $institucion_remitida_a                                  $ciudad_remitida_a                                  $fecha_remitida_a";
// 		$html.="<p>";
// 		$html.="<p>";
// 		$html.="                                                                                            $causamuerte";
// 		$pdf->WriteHTML($html);
// 		$pdf->Output($Dir,'F');
// 		return true;
// 	}

// 	function TraerDatos($TipoDo,$Docume,$evento)
// 	{
// 		list($dbconn) = GetDBconn();
// 		$query = "SELECT A.residencia_direccion,
// 				A.residencia_telefono,
// 				A.fecha_nacimiento,
// 				--B.descripcion AS dessexo,
// 				B.sexo_id,
// 				C.municipio AS munipaciente,
// 				D.poliza,
// 				CASE WHEN D.asegurado='1' THEN 'SI'
// 					WHEN D.asegurado='2' THEN 'NO'
// 					WHEN D.asegurado='4' THEN 'POLIZA FALSA'
// 					WHEN D.asegurado='5' THEN 'POLIZA VENCIDA'
// 				ELSE 'FANTASMA' END AS asegura,
// 				E.descripcion AS descondicion,
// 				F.fecha_accidente,
// 				F.sitio_accidente,
// 				F.informe_accidente,
// 				G.municipio AS muniaccidente,
// 				H.descripcion AS deszona,
// 				I.departamento,
// 				J.poliza,
// 				J.vigencia_desde,
// 				J.vigencia_hasta,
// 				J.sucursal,
// 				J.placa_vehiculo,
// 				J.marca_vehiculo,
// 				J.tipo_vehiculo,
// 				K.nombre_tercero,
// 				M.apellidos_conductor,
// 				M.nombres_conductor,
// 				M.tipo_id_conductor,
// 				M.conductor_id,
// 				M.direccion_conductor,
// 				M.telefono_conductor,
// 				N.tipo_id_tercero,
// 				N.id,
// 				N.direccion,
// 				N.telefonos,
// 				O.municipio AS muniempresa,
// 				P.municipio AS munivehiculo,
// 				Q.fecha_remision,
// 				R.descripcion AS descentro,
// 				S.municipio AS municentro
// 				FROM pacientes AS A,
// 				tipo_sexo AS B,
// 				tipo_mpios AS C,
// 				soat_eventos AS D
// 				LEFT JOIN condicion_accidentados AS E ON
// 				(D.condicion_accidentado=E.condicion_accidentado
// 				AND D.evento=".$evento.")
// 				LEFT JOIN soat_vehiculo_conductor AS M ON
// 				(M.evento=".$evento.")
// 				LEFT JOIN tipo_mpios AS P ON
// 				(M.tipo_pais_id=P.tipo_pais_id
// 				AND M.tipo_dpto_id=P.tipo_dpto_id
// 				AND M.tipo_mpio_id=P.tipo_mpio_id)
// 				LEFT JOIN soat_remision AS Q ON
// 				(Q.evento=".$evento."
// 				AND Q.remision_id=
// 					(SELECT MAX(remision_id)
// 					FROM soat_remision
// 					WHERE evento=".$evento."))
// 				LEFT JOIN centros_remision AS R ON
// 				(Q.centro_remision=R.centro_remision)
// 				LEFT JOIN tipo_mpios AS S ON
// 				(R.tipo_pais_id=S.tipo_pais_id
// 				AND R.tipo_dpto_id=S.tipo_dpto_id
// 				AND R.tipo_mpio_id=S.tipo_mpio_id),
// 				soat_accidente AS F,
// 				tipo_mpios AS G,
// 				zonas_residencia AS H,
// 				tipo_dptos AS I,
// 				soat_polizas AS J,
// 				terceros AS K,
// 				empresas AS N,
// 				tipo_mpios AS O
// 				WHERE A.tipo_id_paciente='".$TipoDo."'
// 				AND A.paciente_id='".$Docume."'
// 				AND A.sexo_id=B.sexo_id
// 				AND A.tipo_pais_id=C.tipo_pais_id
// 				AND A.tipo_dpto_id=C.tipo_dpto_id
// 				AND A.tipo_mpio_id=C.tipo_mpio_id
// 				AND A.tipo_id_paciente=D.tipo_id_paciente
// 				AND A.paciente_id=D.paciente_id
// 				AND D.evento=".$evento."
// 				AND D.accidente_id=F.accidente_id
// 				AND F.tipo_pais_id=G.tipo_pais_id
// 				AND F.tipo_dpto_id=G.tipo_dpto_id
// 				AND F.tipo_mpio_id=G.tipo_mpio_id
// 				AND F.zona=H.zona_residencia
// 				AND F.tipo_pais_id=I.tipo_pais_id
// 				AND F.tipo_dpto_id=I.tipo_dpto_id
// 				AND D.poliza=J.poliza
// 				AND J.tipo_id_tercero=K.tipo_id_tercero
// 				AND J.tercero_id=K.tercero_id
// 				AND D.empresa_id=N.empresa_id
// 				AND N.tipo_pais_id=O.tipo_pais_id
// 				AND N.tipo_dpto_id=O.tipo_dpto_id
// 				AND N.tipo_mpio_id=O.tipo_mpio_id;";
// 		$resulta = $dbconn->Execute($query);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			return false;
// 		}
// 		while(!$resulta->EOF)
// 		{
// 			$var=$resulta->GetRowAssoc($ToUpper = false);
// 			$resulta->MoveNext();
// 		}
// 		$EdadArr=CalcularEdad($var['fecha_nacimiento'],'');
// 		$edad=explode(' ',$EdadArr['edad_aprox']);
// 		$fecha=explode(' ',$var['fecha_accidente']);
// 		$var['hora']=$fecha[1];
// 		$fecha=explode('-',$fecha[0]);
// 		$var['fecha_accidente']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
// 		$fecha=explode('-',$var['vigencia_desde']);
// 		$var['vigencia_desde']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
// 		$fecha=explode('-',$var['vigencia_hasta']);
// 		$var['vigencia_hasta']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
// 		$fecha=explode(' ',$var['fecha_remision']);
// 		$fecha=explode('-',$fecha[0]);
// 		$var['fecha_remision']=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];
// 
// 		//VECTOR 1
// 		$vect[61][54][$_SESSION['soa1']['razonso']];//61,54,
// echo $vect[61][54][$_SESSION['soa1']['razonso']];exit;
// 		if($var[tipo_id_tercero] AND $var[id])
// 		{
// 			$vect[138][54]=$var[tipo_id_tercero].'-'.$var[id];//138,54,
// 		}
// 		if($var[direccion] AND $var[muniempresa] AND $var[telefonos])
// 		{
// 			$vect[36][59]=$var[direccion];//36,59
// 			$vect[112][59]=$var[muniempresa];//112,59
// 			$vect[149][59]=$var[telefonos];//149,59
// 		}
// 
// 		//VECTOR 2
// 		$vect[79][1]=$edad[0];//EDAD 79,71
// 		if($var[sexo_id]=='M')
// 		{
// 			$vect[2][1][2]=$var[sexo_id]; //91,71,
// 		}
// 		elseif($var[sexo_id]=='F')
// 		{
// 			$vect[2][1][2]=$var[sexo_id]; //98,71
// 		}
// 		if($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='CC')
// 		{
// 			$vect[2][1][3]='X';//136,71
// 		}
// 		elseif($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='CE')
// 		{
// 			$vect[2][1][3]='X';//146,71
// 		}
// 		$vect[2][1][4]=$_SESSION['soat']['evento']['nombresoat']['paciente_id'];//162,71
// //$pdf->Text(162,79,'CALI');
// 		$vect[2][2][1]=$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];//46,79
// 		if($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='TI')
// 		{
// 			$vect[2][2][2]='X';//135,79
// 		}
// 		elseif($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='PA')
// 		{
// 			$vect[2][2][2]='X';//148,79
// 		}
// 		$vect[2][3][1]=$var['residencia_direccion'];//32,84
// 		$vect[2][3][2]=$var['munipaciente'];//112,84
// 		$vect[2][3][3]=$var['residencia_telefono'];//162,84
// 		if($var['descondicion']=='Ocupante')
// 		{
// 			$vect[2][4][1]=$var['descondicion'];//70,89
// 		}
// 		else
// 		if($var['descondicion']=='Peaton')
// 		{
// 			$vect[2][4][1]=$var['descondicion'];//93,89
// 		}
// 		$f=explode('/',$var['fecha_accidente']);
// $vect[2][5][1]=$f[2];//117,92 año
// $vect[2][5][1]=$f[1];//127,92, mes
// $vect[2][5][1]=$f[0];//133,92 dia;
// 		//$vect[2][5][1]=$var['fecha_accidente'];
// 		$vect[2][5][2]=$var['hora'];//155,92
// 		$h=explode(':',$var['hora']);//172,92
// 		if($h[0]>='12')
// 		{
// 			$vect[2][5][2]='X';//172,92
// 		}
// 		else
// 		{
// 			$vect[2][5][2]='X'//180,92
// 		}
// 
// 		$vect[2][6][1]=$var['sitio_accidente'];//69,98
// 		$vect[2][7][1]=$var['muniaccidente'];//31,104
// 		$vect[2][7][2]=$var['departamento'];//91,104
// 		if($var['deszona']=='Urbana')
// 		{
// 		$vect[2][7][3]='X';//150,104
// 		}
// 		else
// 		{
// 		$vect[2][7][3]='X';//171,104
// 		}
// 		$vect[2][7][3]=$var['deszona'];
// 		$vect[2][8][1]=$var['informe_accidente'];
// 		$vect[2][9][1]=$var['marca_vehiculo'];
// 		$vect[2][9][2]=$var['placa_vehiculo'];
// 		$vect[2][9][3]=$var['tipo_vehiculo'];
// 		$vect[2][10][1]=$var['nombre_tercero'];
// 		$vect[2][10][2]=$var['sucursal'];
// 		$vect[2][11][1]=$var['asegura'];
// 		$vect[2][11][2]=$var['poliza'];
// 		$vect[2][11][3]=$var['vigencia_desde'];
// 		$vect[2][11][4]=$var['vigencia_hasta'];
// 		$vect[2][12][1]=$var['apellidos_conductor'].' '.$var['nombres_conductor'];
// 		$vect[2][12][2]=$var['tipo_id_conductor'];
// 		$vect[2][12][3]=$var['conductor_id'];
// 		$vect[2][13][1]=$var['direccion_conductor'];
// 		$vect[2][13][2]=$var['munivehiculo'];
// 		$vect[2][13][3]=$var['telefono_conductor'];
// 
// 		//VECTOR 3
// 		$query = "SELECT A.ingreso,
// 				B.fecha_ingreso,
// 				C.via_ingreso_nombre
// 				FROM ingresos_soat AS A,
// 				ingresos AS B,
// 				vias_ingreso AS C
// 				WHERE A.evento=".$evento."
// 				AND A.ingreso=B.ingreso
// 				AND B.via_ingreso_id=C.via_ingreso_id
// 				ORDER BY A.ingreso;";
// 		$resulta = $dbconn->Execute($query);
// 		if ($dbconn->ErrorNo() != 0)
// 		{
// 			$this->error = "Error al Cargar el Modulo";
// 			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 			return false;
// 		}
// 		while(!$resulta->EOF)
// 		{
// 			$var2=$resulta->GetRowAssoc($ToUpper = false);
// 			$resulta->MoveNext();
// 		}
// 		$fecha=explode(' ',$var2['fecha_ingreso']);
// 		$vect[3][1][2]=$fecha[1]; //hora ingreso
// 		$fecha=explode('-',$fecha[0]);
// 		$vect[3][1][1]=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];//fecha ingreso
// 		$vect[3][1][3]=$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']; //historia clinica
// 		if($var2['ingreso']<>NULL)
// 		{
// 			$query = "SELECT evolucion_id,
// 					fecha_cierre,
// 					estado
// 					FROM hc_evoluciones
// 					WHERE ingreso=".$var2['ingreso'].";";
// 			$resulta = $dbconn->Execute($query);
// 			if ($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			while(!$resulta->EOF)
// 			{
// 				$var3[]=$resulta->GetRowAssoc($ToUpper = false);
// 				$resulta->MoveNext();
// 			}
// 			//si estado en cero, esta cerrada y tiene fecha de cierre
// 			//si estado en dos, esta en pendiente y tiene fecha de cierre
// 			$i=sizeof($var3)-1;//esto o un ciclo
// 			/*if($var3[$i]['estado']==0 OR $var3[$i]['fecha_cierre']<>NULL)
// 			{
// 			}*/
// 			$fecha=explode(' ',$var3[$i]['fecha_cierre']);
// 			$var['hora_egreso']=$fecha[1];
// 			$fecha=explode('-',$fecha[0]);
// 			$vect[3][2][1]=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];//fecha egreso
// 			$vect[3][2][2]=$vect[3][2][1]-$vect[3][1][1];//dias_estancia
// 			if(empty($vect[3][2][2]))
// 			{
// 				$vect[3][2][2]='0';//dias_estancia
// 			}
// 			$vect[3][2][3]=$var2['via_ingreso_nombre'];
// 
// 		if($var3[0]['evolucion_id']<>NULL)
// 		{
// 			$query = "SELECT B.diagnostico_nombre AS ingreso
// 					FROM hc_diagnosticos_ingreso AS A,
// 					diagnosticos AS B
// 					WHERE A.evolucion_id=".$var3[0]['evolucion_id']."
// 					AND A.tipo_diagnostico_id=B.diagnostico_id
// 					ORDER BY A.sw_principal DESC;";
// 			$resulta = $dbconn->Execute($query);//la primera evolucion
// 			if ($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			while(!$resulta->EOF)
// 			{
// 				$var4[]=$resulta->GetRowAssoc($ToUpper = false);
// 				$resulta->MoveNext();
// 			}
// 			$query = "SELECT B.diagnostico_nombre AS egreso
// 					FROM hc_diagnosticos_egreso AS A,
// 					diagnosticos AS B
// 					WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
// 					AND A.tipo_diagnostico_id=B.diagnostico_id;";//ORDER BY sw_principal DESC
// 			$resulta = $dbconn->Execute($query);//la ultima evolucion
// 			if ($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			while(!$resulta->EOF)
// 			{
// 				$var5[]=$resulta->GetRowAssoc($ToUpper = false);
// 				$resulta->MoveNext();
// 			}
// 			$query = "SELECT B.diagnostico_nombre AS muerte
// 					FROM hc_diagnosticos_muerte AS A,
// 					diagnosticos AS B
// 					WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
// 					AND A.tipo_diagnostico_id=B.diagnostico_id;";//ORDER BY sw_principal DESC
// 			$resulta = $dbconn->Execute($query);//la ultima evolucion
// 			if ($dbconn->ErrorNo() != 0)
// 			{
// 				$this->error = "Error al Cargar el Modulo";
// 				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
// 				return false;
// 			}
// 			while(!$resulta->EOF)
// 			{
// 				$var6[]=$resulta->GetRowAssoc($ToUpper = false);
// 				$resulta->MoveNext();
// 			}
// 			for($j=0;$j<sizeof($var4);$j++)
// 			{
// 				$vect[3][3][1].=$var4[$j]['ingreso'];//diagnostico de ingreso
// 				if(empty($var5))
// 				{
// 					$vect[3][4][1].=$var4[$j]['ingreso'];//diagnostico de egreso
// 				}
// 			}
// 			for($j=0;$j<sizeof($var5);$j++)
// 			{
// 				$vect[3][4][1].=$var5[$j]['egreso'];//desc_diagnostico_de _EGRESO
// 			}
// /*			for($j=0;$j<sizeof($var6);$j++)
// 			{
// //*****************************************************
// 				$var['causa_muerte'].=$var6[$j]['muerte'];//causa_muerte
// //*****************************************************
// 			}*/
// 		}
// 		if($var[descentro]==NULL)
// 		{
// 			$var[descentro]='****';
// 			$var[municentro]='****';
// 			$var[fecha_remision]='****';
// 			$vect[3][5][1]='---';
// 			$vect[3][5][2]='---';
// 			$vect[3][5][3]='---';
// 		}
// 		else
// 		{
// 			$vect[3][5][1]=$_SESSION['soa1']['razonso'];//PERSONA REMITIDA DE:
// 			$vect[3][5][2]=$var[muniempresa];
// 			$vect[3][5][3]=$var[fecha_remision];
// 		}
// 		$vect[3][6][1]=$var[descentro];//PERSONA REMITIDA A:
// 		$vect[3][6][2]=$var[municentro];
// 		$vect[3][6][3]=$var[fecha_remision];
// 		}
// 
// //VECTOR 4
// 		for($j=0;$j<sizeof($var6);$j++)
// 		{
// 			$vect[4][1][1].=$var6[$j]['muerte'];//causa_muerte
// 		}
// 		return $vect;
// 	}

?>
