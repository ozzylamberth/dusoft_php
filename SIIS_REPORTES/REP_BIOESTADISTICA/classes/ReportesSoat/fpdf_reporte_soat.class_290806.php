<?php

/**
 * $Id: fpdf_reporte_soat.class.php,v 1.2 2006/08/28 19:05:07 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

    class fpdf_reporte_soat extends FPDF
    {
            var $correcion_x;
            var $correcion_y;
    
            function fpdf_reporte_soat($orientation,$unit,$format)
            {
                    $this->correcion_x = 1;
                    $this->correcion_y = 1;
                    $this->FPDF($orientation,$unit,$format);
                    return true;
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
            // REPORTE SOAT 1
            function TraerDatosReclamacionEntidades($TipoDo,$Docume,$evento)
            {
                list($dbconn) = GetDBconn();
                $query = "SELECT A.residencia_direccion,
                        A.residencia_telefono,
                        A.fecha_nacimiento,
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
                        F.tipo_tratamiento,
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
                $fecha_accidente=$fecha=explode(' ',$var['fecha_accidente']);
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
                $vect[0][0]=62;
                $vect[0][1]=56;
                $vect[0][2]=$_SESSION['soa1']['razonso'];//61,55,
                if($var[tipo_id_tercero] AND $var[id])
                {
                    $vect[1][0]=150;
                    $vect[1][1]=56;
                    $vect[1][2]=$var[tipo_id_tercero].'-'.$var[id];//150,55,
                }
                if($var[direccion] AND $var[muniempresa] AND $var[telefonos])
                {
                    $vect[2][0]=36;
                    $vect[2][1]=64;
                    $vect[2][2]=$var[direccion];//36,59

                    $vect[3][0]=120;
                    $vect[3][1]=64;
                    $vect[3][2]=$var[muniempresa];//112,59

                    $vect[4][0]=162;
                    $vect[4][1]=64;
                    $vect[4][2]=$var[telefonos];//149,59
                }
        
                //VECTOR 2
                    $vect[5][0]=86;
                    $vect[5][1]=85;
                    $vect[5][2]=$edad[0];//EDAD 79,71
                if($var[sexo_id]=='M')
                {
                    $vect[6][0]=98;
                    $vect[6][1]=85;
                    $vect[6][2]=$var[sexo_id];//91,71,
                }
                elseif($var[sexo_id]=='F')
                {
                    $vect[6][0]=105;
                    $vect[6][1]=85;
                    $vect[6][2]=$var[sexo_id];//98,71,
                }
                if($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='CC')
                {
                    $vect[7][0]=146;
                    $vect[7][1]=85;
                    $vect[7][2]='X';//136,71
                }
                elseif($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='CE')
                {
                    $vect[7][0]=156;
                    $vect[7][1]=85;
                    $vect[7][2]='X';//146,71
                }
                    $vect[8][0]=176;
                    $vect[8][1]=85;
                    $vect[8][2]=$_SESSION['soat']['evento']['nombresoat']['paciente_id'];//162,71
//$pdf->Text(162,79,'CALI');
                    $vect[9][0]=47;
                    $vect[9][1]=98;
                    $vect[9][2]=$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];//46,79
                if($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='TI')
                {
                    $vect[10][0]=146;
                    $vect[10][1]=98;
                    $vect[10][2]='X';//135,79
                }
                elseif($_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']=='PA')
                {
                    $vect[10][0]=156;
                    $vect[10][1]=98;
                    $vect[10][2]='X';//148,79
                }
                $vect[11][0]=32;
                $vect[11][1]=108;
                $vect[11][2]=$var['residencia_direccion'];//32,84

                $vect[12][0]=120;
                $vect[12][1]=108;
                $vect[12][2]=$var['munipaciente'];//112,84

                $vect[13][0]=176;
                $vect[13][1]=108;
                $vect[13][2]=$var['residencia_telefono'];//162,84

                if($var['descondicion']=='Ocupante')
                {
                    $vect[14][0]=76;
                    $vect[14][1]=115;
                    $vect[14][2]='X';//70,89
                }
                else
                if($var['descondicion']=='Peaton')
                {
                    $vect[14][0]=94;
                    $vect[14][1]=115;
                    $vect[14][2]='X';//93,89
                }
                $f=explode('/',$var['fecha_accidente']);
                $vect[15][0]=127;
                $vect[15][1]=121;
                $vect[15][2]=$f[2];//117,92 a?o

                $vect[16][0]=138;
                $vect[16][1]=121;
                $vect[16][2]=$f[1];//127,92, mes

                $vect[17][0]=144;
                $vect[17][1]=121;
                $vect[17][2]=$f[0];//133,92 dia;

            //$vect[2][5][1]=$var['fecha_accidente'];
                $vect[18][0]=168;
                $vect[18][1]=121;
                //$vect[18][2]=$var['hora'];//155,92
                $h=explode(':',$var['hora']);//172,92

                if($h[0]>=12)
                {
                    if($h[0]==12)
                      $vect[18][2]=$h[0].':'.$h[1].':'.$h[2];//155,92
                    else
                    {
                      $hora=$h[0]-12;
                      $vect[18][2]=$hora.':'.$h[1].':'.$h[2];//155,92
                    }

                    $vect[19][0]=193;
                    $vect[19][1]=121;
                    $vect[19][2]='X';//172,92
                }
                else
                {
                    $vect[18][2]=$h[0].':'.$h[1].':'.$h[2];//155,92

                    $vect[19][0]=187;
                    $vect[19][1]=121;
                    $vect[19][2]='X';//180,92
                }
        
                    $vect[20][0]=73;
                    $vect[20][1]=129;
                    $vect[20][2]=$var['sitio_accidente'];//69,98

                    $vect[21][0]=33;
                    $vect[21][1]=142;
                    $vect[21][2]=$var['muniaccidente'];//31,104

                    $vect[22][0]=97;
                    $vect[22][1]=142;
                    $vect[22][2]=$var['departamento'];//91,104

                if($var['deszona']=='Urbana')
                {
                    $vect[23][0]=165;
                    $vect[23][1]=142;
                    $vect[23][2]='X';//150,104
                }
                else
                {
                    $vect[23][0]=185;
                    $vect[23][1]=142;
                    $vect[23][2]='X';//171,104
                }
                    $vect[24][0]=15;
                    $vect[24][1]=159;
                    $vect[24][2]=$var['informe_accidente'];;//17,114

                    $vect[25][0]=28;
                    $vect[25][1]=179;
                    $vect[25][2]=$var['marca_vehiculo'];//28,125

                    $vect[26][0]=108;
                    $vect[26][1]=179;
                    $vect[26][2]=$var['placa_vehiculo'];//112,125

                    $vect[27][0]=154;
                    $vect[27][1]=179;
                    $vect[27][2]=$var['tipo_vehiculo'];//142,125

                    $vect[28][0]=52;
                    $vect[28][1]=196;
                    $vect[28][2]=$var['nombre_tercero'];//50,134


                    $vect[29][0]=163;
                    $vect[29][1]=196;
                    $vect[29][2]=$var['sucursal'];//151,134
                    if($var['asegura']=='SI')
                    {
                        $vect[30][0]=19;
                        $vect[30][1]=209;
                        $vect[30][2]='X';//29,142
                    }
                    elseif($var['asegura']=='NO')
                    {
                        $vect[30][0]=23;
                        $vect[30][1]=209;
                        $vect[30][2]='X';//32,142
                    }
                    elseif($var['asegura']=='FANTASMA')
                    {
                        $vect[30][0]=29;
                        $vect[30][1]=209;
                        $vect[30][2]='X';//42,142
                    }

                    if($var['asegura']=='POLIZA FALSA')
                    {
                        $vect[31][0]=23;
                        $vect[31][1]=209;
                        $vect[31][2]=$var['asegura'];//53,142
                    }
                    elseif($var['asegura']=='POLIZA VENCIDA')
                    {
                        $vect[31][0]=23;
                        $vect[31][1]=209;
                        $vect[31][2]=$var['asegura'];//53,142
                    }
                    else
                    {
                        $vect[31][0]=56;
                        $vect[31][1]=209;
                        $vect[31][2]=$var['poliza'];//53,142
                    }

                    $f1=explode('/',$var['vigencia_desde']);
                    $vect[32][0]=126;
                    $vect[32][1]=209;
                    $vect[32][2]=$f1[2];//117,142

                    $vect[33][0]=137;
                    $vect[33][1]=209;
                    $vect[33][2]=$f1[1];//127,142

                    $vect[34][0]=145;
                    $vect[34][1]=209;
                    $vect[34][2]=$f1[0];//133,142

                    $f1=explode('/',$var['vigencia_hasta']);
                    $vect[35][0]=177;
                    $vect[35][1]=209;
                    $vect[35][2]=$f1[2];//162,142

                    $vect[36][0]=187;
                    $vect[36][1]=209;
                    $vect[36][2]=$f1[1];//172,142

                    $vect[37][0]=194;
                    $vect[37][1]=209;
                    $vect[37][2]=$f1[0];//178,142

                    $vect[38][0]=15;
                    $vect[38][1]=229;
                    $vect[38][2]=$var['apellidos_conductor'].' '.$var['nombres_conductor'];//18,154
                if($var['tipo_id_conductor']=='CC')
                {
                    $vect[39][0]=125;
                    $vect[39][1]=224;
                    $vect[39][2]='X';//120,150
                }
                else
                if($var['tipo_id_conductor']=='CE')
                {
                    $vect[39][0]=137;
                    $vect[39][1]=224;
                    $vect[39][2]='X';//132,150
                }

                if($var['tipo_id_conductor']=='TI')
                {
                    $vect[40][0]=125;
                    $vect[40][1]=229;
                    $vect[40][2]='X';//120,154
                }
                else
                if($var['tipo_id_conductor']=='PAS')
                {
                    $vect[40][0]=137;
                    $vect[40][1]=229;
                    $vect[40][2]='X';//132,154
                }

                $vect[41][0]=156;
                $vect[41][1]=224;
                $vect[41][2]=$var['conductor_id'];//143,150

                $vect[42][0]=34;
                $vect[42][1]=242;
                $vect[42][2]=$var['direccion_conductor'];//31,161

                $vect[43][0]=113;
                $vect[43][1]=242;
                $vect[43][2]=$var['munivehiculo'];//114,161

                $vect[44][0]=163;
                $vect[44][1]=242;
                $vect[44][2]=$var['telefono_conductor'];//148,161
        
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
                $vect1[3][1][1]=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];//fecha ingreso
                $vect[45][0]=102;
                $vect[45][1]=273;
                $hora=explode(':',$fecha[1]);
                //$vect[45][2]=$hora[0].':'.$hora[1].'';//108,179 //hora ingreso

                if($hora[0]<12)
                {
                    $vect[45][2]=$hora[0].':'.$hora[1].'';//108,179 //hora ingreso

                    $vect[68][0]=115;
                    $vect[68][1]=273;
                    $vect[68][2]='X';//112,179 AM
                }
                else
                {
                  if($hora[0]==12)
                  {
                    $vect[45][2]=$hora[0].':'.$hora[1].'';//108,179 //hora ingreso                    
                  }
                  else
                  {
                    $h=$hora[0]-12;
                    $vect[45][2]=$h.':'.$hora[1];//108,179 //hora ingreso                    
                  }

                    $vect[68][0]=120;
                    $vect[68][1]=273;
                    $vect[68][2]='X';//119,179 PM
                }

                $fecha=explode('-',$fecha[0]);

                $vect[46][0]=44;
                $vect[46][1]=273;
                $vect[46][2]=$fecha[0];//42,179 //fecha ingreso A?O

                $vect[47][0]=55;
                $vect[47][1]=273;
                $vect[47][2]=$fecha[1];//52,179 MES

                $vect[48][0]=62;
                $vect[48][1]=273;
                $vect[48][2]=$fecha[2];//58,179 DIA

                $vect[49][0]=170;
                $vect[49][1]=273;
                $vect[49][2]=$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente']."".' - '."".$_SESSION['soat']['evento']['nombresoat']['paciente_id']; //historia clinica//116,179
//EVOLUCION DEL INGRESO
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

                    $vect[50][0]=44;
                    $vect[50][1]=290;
                    $vect[50][2]=$fecha[0];//42,188 //fecha egreso A?O
    
                    $vect[51][0]=55;
                    $vect[51][1]=290;
                    $vect[51][2]=$fecha[1];//52,188 MES
    
                    $vect[52][0]=62;
                    $vect[52][1]=290;
                    $vect[52][2]=$fecha[2];//58,188 //fecha egreso DIA

                    $vect1[3][2][1]=$fecha[2].'/'.$fecha[1].'/'.$fecha[0];//fecha egreso

                    $vect[53][0]=98;
                    $vect[53][1]=290;
                    //$vect[53][2]=$vect1[3][2][1]-$vect1[3][1][1];//dias_estancia
                    $vect[53][2]='--';//dias_estancia
                    if(empty($vect[23][2]))
                    {
                        $vect[53][2]='0';//dias_estancia
                    }

/*                    if($var2['via_ingreso_nombre']=='Hospitalizacion')
                    {
                    $vect[54][0]=160;
                    $vect[54][1]=290;
                    $vect[54][2]='X';//158,192
                    }*/

                    if($var['tipo_tratamiento']=='0')//OBSERVACION
                    {
                    $vect[54][0]=180;
                    $vect[54][1]=285;
                    $vect[54][2]='X';//158,192
                    }
                    else
                    if($var['tipo_tratamiento']=='1')//HOSPITALARIO
                    {
                    $vect[69][0]=180;
                    $vect[69][1]=290;
                    $vect[69][2]='X';//158,192
                    }
                    else
                    if($var['tipo_tratamiento']=='2')//AMBULATORIO
                    {
                    $vect[70][0]=200;
                    $vect[70][1]=290;
                    $vect[70][2]='X';//158,192
                    }

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
                            WHERE A.evolucion_id=".$var3[0]['evolucion_id']."
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
/*                   $query = "SELECT B.diagnostico_nombre AS muerte
                            FROM hc_diagnosticos_muerte AS A,
                            diagnosticos AS B
                            WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
                            AND A.tipo_diagnostico_id=B.diagnostico_id;";*/
										$query = "SELECT C.diagnostico_nombre,
																		A.fecha, 
																		D.nombre,
																		D.registro_salud_departamental
																	FROM hc_conducta_defuncion A,
																		hc_conducta_diagnosticos_defuncion B,
																		diagnosticos C,
																		profesionales D
																	WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
																	AND A.ingreso=".$var2['ingreso']."
																	AND A.evolucion_id=B.evolucion_id
																	AND A.ingreso=B.ingreso
																	AND B.diagnostico_defuncion_id=C.diagnostico_id
																	AND A.usuario_id=D.usuario_id;";
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
                        $vect[55][0]=60;
                        $vect[55][1]=305;
                        $vect[55][2].=$var4[$j]['ingreso'];//56,194 diagnostico de ingreso
                        if(empty($var5))
                        {
                        $vect[56][0]=60;
                        $vect[56][1]=325;
                        $vect[56][2].=$var4[$j]['ingreso'];//56,227 //diagnostico de ingreso
                        }
                    }
                    for($j=0;$j<sizeof($var5);$j++)
                    {
                        $vect[56][0]=56;
                        $vect[56][1]=325;
                        $vect[56][2].=$var5[$j]['egreso'];//56,227 desc_diagnostico_de _EGRESO
                    }
                }


/************************************************/
//echo '<BR>INGRESO-->'.$var2['ingreso'].'<BR>';
/************************************************/
//echo 'EVOLUCION_ID-->'.$var3[$i]['evolucion_id'].'<BR>';
/************************************************/
                if($var3[0]['evolucion_id']<>NULL)
                {

//DATOS REMISION DESDE LA HC
								$query = "SELECT C.descripcion, D.municipio
															FROM hc_conducta_remision AS A,
																hc_conducta_remision_centros AS B,
																centros_remision AS C,
																tipo_mpios AS D
															WHERE A.evolucion_id=".$var3[$i]['evolucion_id']."
															AND A.ingreso=".$var2['ingreso']."
															AND A.evolucion_id=B.evolucion_id
															AND A.ingreso=B.ingreso
															AND B.centro_remision=C.centro_remision
															AND C.tipo_pais_id=D.tipo_pais_id
															AND C.tipo_dpto_id=D.tipo_dpto_id
															AND C.tipo_mpio_id=D.tipo_mpio_id;";
								$result = $dbconn->Execute($query);//la ultima evolucion
								if ($dbconn->ErrorNo() != 0)
								{
										$this->error = "Error al Cargar el Modulo";
										$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
										return false;
								}
								if($result->RecordCount()>0)
								{
										$dat=$result->GetRowAssoc($ToUpper = false);
										$vect[57][0]=48;
										$vect[57][1]=335;
										$vect[57][2]=$_SESSION['soa1']['razonso'];//48,236 //PERSONA REMITIDA DE:

										$vect[58][0]=117;
										$vect[58][1]=335;
										$vect[58][2]=$var[muniempresa];//117,236 //PERSONA REMITIDA DE:

										$f2=explode('/',$var[fecha_remision]);

										$vect[59][0]=160;
										$vect[59][1]=335;
										$vect[59][2]=$f2[2];//155,236 fecha remision A?O

										$vect[60][0]=169;
										$vect[60][1]=335;
										$vect[60][2]=$f2[1];//164,236 fecha remision MES

										$vect[61][0]=174;
										$vect[61][1]=335;
										$vect[61][2]=$f2[0];//169,236 fecha remision DIA

										$vect[62][0]=48;
										$vect[62][1]=345;
										$vect[62][2]=$dat[descripcion];//48,240 PERSONA REMITIDA A:

										$vect[63][0]=117;
										$vect[63][1]=345;
										$vect[63][2]=$dat[municipio];//117,240 //PERSONA REMITIDA A:

										$f3=explode(' ',$var3[$i][fecha_cierre]);
										$f4=explode('-',$f3[0]);
										$vect[64][0]=160;
										$vect[64][1]=345;
										$vect[64][2]=$f4[0];//155,240 fecha a?o 

										$vect[65][0]=169;
										$vect[65][1]=345;
										$vect[65][2]=$f4[1];//164,240 fecha mes

										$vect[66][0]=174;
										$vect[66][1]=345;
										$vect[66][2]=$f4[2];//169,240 fecha dia
								}
							}
							//FIN - SI LA EVOLUCION DESDE  LA HC EXISTE
							//FIN DATOS REMISION DESDE LA HC
              if($var[descentro]==NULL AND empty($vect[57][2]))
							{
									$var[descentro]='****';
									$var[municentro]='****';
									$var[fecha_remision]='****';
	
									$vect[57][0]=48;
									$vect[57][1]=335;
									$vect[57][2]='---';//48,236 //PERSONA REMITIDA DE:
	
									$vect[58][0]=117;
									$vect[58][1]=335;
									$vect[58][2]='---';//117,236 //PERSONA REMITIDA DE:
	
									$vect[59][0]=160;
									$vect[59][1]=335;
									$vect[59][2]='--';//155,236 fecha remision
	
									$vect[60][0]=169;
									$vect[60][1]=335;
									$vect[60][2]='--';//164,236 fecha remision
	
									$vect[61][0]=174;
									$vect[61][1]=335;
									$vect[61][2]='--';//169,236 fecha remision
	
		/*            $vect1[3][5][1]='---';
									$vect1[3][5][2]='---';
									$vect1[3][5][3]='---';*/
							}
							else
              if(empty($vect[57][2]))
							{
											$vect[57][0]=48;
											$vect[57][1]=335;
											$vect[57][2]=$_SESSION['soa1']['razonso'];//48,236 //PERSONA REMITIDA DE:
	
											$vect[58][0]=117;
											$vect[58][1]=335;
											$vect[58][2]=$var[muniempresa];//117,236 //PERSONA REMITIDA DE:
	
											$f2=explode('/',$var[fecha_remision]);
	
											$vect[59][0]=160;
											$vect[59][1]=335;
											$vect[59][2]=$f2[2];//155,236 fecha remision
	
											$vect[60][0]=169;
											$vect[60][1]=335;
											$vect[60][2]=$f2[1];//164,236 fecha remision
	
											$vect[61][0]=174;
											$vect[61][1]=335;
											$vect[61][2]=$f2[0];//169,236 fecha remision
	
							}
							$vect[62][0]=48;
							$vect[62][1]=345;
							$vect[62][2]=$var[descentro];//48,240 PERSONA REMITIDA A:
	
							$vect[63][0]=117;
							$vect[63][1]=345;
							$vect[63][2]=$var[municentro];//117,240 //PERSONA REMITIDA A:
	
							$f3=explode('/',$var[fecha_remision]);
							$vect[64][0]=160;
							$vect[64][1]=345;
							$vect[64][2]=$f3[2];//155,240 fecha a?o
	
							$vect[65][0]=169;
							$vect[65][1]=345;
							$vect[65][2]=$f3[1];//164,240 fecha mes
	
							$vect[66][0]=174;
							$vect[66][1]=345;
							$vect[66][2]=$f3[0];//169,240 fecha dia
						}
//FIN EVOLUCION DEL INGRESO
        //VECTOR 4

								//FECHA AVISO - fecha accidente
								$f=explode('-', $fecha_accidente[0]);
								$vect[68][0]=65;
								$vect[68][1]=36;
								$vect[68][2]=$f[0]; //A?O

								$vect[71][0]=75;
								$vect[71][1]=36;
								$vect[71][2]=$f[1]; //MES

								$vect[72][0]=82;
								$vect[72][1]=36;
								$vect[72][2]=$f[2]; //DIA
								//

//DATOS SOBRE LA DEFUNCION DEL PACIENTE
                //for($j=0;$j<sizeof($var6);$j++)
                //{
										$j=sizeof($var6)-1;
										$vect[67][0]=70;
										$vect[67][1]=355;
										$vect[67][2]=$var6[$j]['diagnostico_nombre'];//65,258 causa_muerte

										$f1=explode(' ',$var6[$j]['fecha']);
										$fecha_muerte=explode('-',$f1[0]);
										$hora_muerte=explode(':',$f1[1]);

										$vect[73][0]=65;
										$vect[73][1]=365;
										$vect[73][2]=$fecha_muerte[0];//A?O DEFUNCION
									
										$vect[74][0]=90;
										$vect[74][1]=365;
										$vect[74][2]=$fecha_muerte[1];//MES DEFUNCION

										$vect[75][0]=100;
										$vect[75][1]=365;
										$vect[75][2]=$fecha_muerte[2];//DIA DEFUNCION

										if($hora_muerte[0] > 12)
										{
											$hora=$hora_muerte[0]-12;
											$vect[76][0]=150;
											$vect[76][1]=365;
											$vect[76][2]=$hora.':'.$hora_muerte[1];//HORA DEFUNCION

											$vect[77][0]=170;
											$vect[77][1]=365;
											$vect[77][2]='X';//PM
											
										}
										else
										{
											$vect[76][0]=150;
											$vect[76][1]=365;
											$vect[76][2]=$hora_muerte[0].':'.$hora_muerte[1];//HORA DEFUNCION

											$vect[77][0]=163;
											$vect[77][1]=365;
											$vect[77][2]='X';//AM
										}

									//DATOS DEL PROFESIONAL QUE FIRM? EL CERTIFICADO DE DEFUNCI?N
									$vect[78][0]=135;
									$vect[78][1]=375;
									$vect[78][2]=$var6[$j]['nombre'];//NOMBRE PREFESIONAL

									$vect[79][0]=50;
									$vect[79][1]=385;
									$vect[79][2]=$var6[$j]['registro_salud_departamental'];//REGISTRO MEDICO
										
                //}
                return $vect;
            }
//**********************************************

            // REPORTE SOAT 2
        function BuscarAtencionMedica($evento)//
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT A.razon_social,
                    A.direccion,
                    A.telefonos,
                    B.departamento AS deparempre,
                    C.municipio AS municempre
                    FROM empresas AS A,
                    tipo_dptos AS B,
                    tipo_mpios AS C
                    WHERE A.empresa_id='".$_SESSION['soa1']['empresa']."'
                    AND A.tipo_pais_id=B.tipo_pais_id
                    AND A.tipo_dpto_id=B.tipo_dpto_id
                    AND A.tipo_pais_id=C.tipo_pais_id
                    AND A.tipo_dpto_id=C.tipo_dpto_id
                    AND A.tipo_mpio_id=C.tipo_mpio_id;";
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
            $query = "SELECT C.nombres_declara,
                    C.apellidos_declara,
                    C.tipo_id_paciente,
                    C.declara_id,
                    C.extipo_pais_id,
                    C.extipo_dpto_id,
                    C.extipo_mpio_id,
                    C.fecha_ingreso,
                    C.datos1_ta,
                    C.datos2_fc,
                    C.datos3_fr,
                    C.datos4_te,
                    C.datos5_conciencia,
                    C.datos6_glasgow,
                    C.estado_embriaguez,
                    C.diagnostico1,
                    C.diagnostico2,
                    C.diagnostico3,
                    C.diagnostico4,
                    C.diagnostico5,
                    C.diagnostico6,
                    C.diagnostico7,
                    C.diagnostico8,
                    C.diagnostico9,
                    C.diagnostico_def,
                    C.tipo_id_tercero,
                    C.tercero_id,
                    C.fecha_registro,
                    C.usuario_id,
                    A.fecha_accidente,
                    D.tarjeta_profesional,
                    E.nombre_tercero,
                    F.municipio AS expedida
                    FROM soat_accidente AS A,
                    soat_eventos AS B
                    LEFT JOIN soat_atencion_medica AS C ON
                    (B.evento=C.evento)
                    LEFT JOIN profesionales AS D ON
                    (C.tipo_id_tercero=D.tipo_id_tercero
                    AND C.tercero_id=D.tercero_id)
                    LEFT JOIN terceros AS E ON
                    (C.tipo_id_tercero=E.tipo_id_tercero
                    AND C.tercero_id=E.tercero_id)
                    LEFT JOIN tipo_mpios AS F ON
                    (C.extipo_pais_id=F.tipo_pais_id
                    AND C.extipo_dpto_id=F.tipo_dpto_id
                    AND C.extipo_mpio_id=F.tipo_mpio_id)
                    WHERE B.evento=".$evento."
                    AND A.accidente_id=B.accidente_id;";
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
            $var['apellido']=$_SESSION['soat']['pacisoat']['primer_apellido'].' '.$_SESSION['soat']['pacisoat']['segundo_apellido'];
            $var['nombre']=$_SESSION['soat']['pacisoat']['primer_nombre'].' '.$_SESSION['soat']['pacisoat']['segundo_nombre'];
            $var['residencia_direccion']=$_SESSION['soat']['pacisoat']['residencia_direccion'];
            $var['residencia_telefono']=$_SESSION['soat']['pacisoat']['residencia_telefono'];
/*          $dpto=CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array(
            'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
            'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id']));*/
            IncludeClass("classModulo");
            $tmp = new classModulo ;
            $dpto=$tmp->CallMetodoExterno('app','Triage','user','nombre_dpto',$argumentos=array(
            'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
            'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id']));
            //$mpio=$this->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array(
            //'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
            //'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id'],
            //'Mpio'=>$_SESSION['soat']['pacisoat']['tipo_mpio_id']));
            $mpio=$tmp->CallMetodoExterno('app','Triage','user','nombre_ciudad',$argumentos=array(
            'Pais'=>$_SESSION['soat']['pacisoat']['tipo_pais_id'],
            'Dpto'=>$_SESSION['soat']['pacisoat']['tipo_dpto_id'],
            'Mpio'=>$_SESSION['soat']['pacisoat']['tipo_mpio_id']));
            $_SESSION['soat']['pacisoat']['departamento']=$var['departamento']=$dpto;
            $_SESSION['soat']['pacisoat']['municipio']=$var['municipio']=$mpio;
            $var['tipo_id_paciente_eve']=$_SESSION['soat']['evento']['TipoDocum'];
            $var['paciente_id_eve']=$_SESSION['soat']['evento']['Documento'];
            //$var['tipo_id_tercero2']=$var2['tipo_id_tercero'];
            //$var['id']=$var2['id'];
            $var['razon_social']=$var2['razon_social'];
            $var['direccion']=$var2['direccion'];
            $var['telefonos']=$var2['telefonos'];
            $var['deparempre']=$var2['deparempre'];
            $var['municempre']=$var2['municempre'];
            //
            $vect1['apellidos_declara']=$var['apellidos_declara'];
            $vect1['nombres_declara']=$var['nombres_declara'];
            $vect1['tipo_id_paciente']=$var['tipo_id_paciente'];
            $vect1['declara_id']=$var['declara_id'];
            $vect1['extipo_pais_id']=$var['extipo_pais_id'];
            $vect1['extipo_dpto_id']=$var['extipo_dpto_id'];
            $vect1['extipo_mpio_id']=$var['extipo_mpio_id'];
            $vect1['fecha_accidente']=$var['fecha_accidente'];
            $vect1['fecha_ingreso']=$var['fecha_ingreso'];
            $vect1['datos1_ta']=$var['datos1_ta'];
            $vect1['datos2_fc']=$var['datos2_fc'];
            $vect1['datos3_fr']=$var['datos3_fr'];
            $vect1['datos4_te']=$var['datos4_te'];
            $vect1['datos5_conciencia']=$var['datos5_conciencia'];
            $vect1['datos6_glasgow']=$var['datos6_glasgow'];
            $vect1['estado_embriaguez']=$var['estado_embriaguez'];
            $vect1['diagnostico1']=$var['diagnostico1'];
            $vect1['diagnostico2']=$var['diagnostico2'];
            $vect1['diagnostico3']=$var['diagnostico3'];
            $vect1['diagnostico4']=$var['diagnostico4'];
            $vect1['diagnostico5']=$var['diagnostico5'];
            $vect1['diagnostico6']=$var['diagnostico6'];
            $vect1['diagnostico7']=$var['diagnostico7'];
            $vect1['diagnostico8']=$var['diagnostico8'];
            $vect1['diagnostico9']=$var['diagnostico9'];
            $vect1['diagnostico_def']=$var['diagnostico_def'];
            $vect1['tipo_id_tercero']=$var['tipo_id_tercero'];
            $vect1['tercero_id']=$var['tercero_id'];
            $vect1['tarjeta_profesional']=$var['tarjeta_profesional'];
            $vect['DATOS']=$vect1;

            $vect[1][0]=60;
            $vect[1][1]=57;
            $vect[1][2]=$var[direccion];//60,57
            
            $vect[2][0]=140;
            $vect[2][1]=57;
            $vect[2][2]=$var[municempre];//140 57
            
            $vect[3][0]=55;
            $vect[3][1]=62;
            $vect[3][2]=$var[deparempre];//55,62
            
            $vect[4][0]=140;
            $vect[4][1]=62;
            $vect[4][2]=$var[telefonos] ;//140,62
            
            $vect[5][0]=110;
            $vect[5][1]=67;
            $vect[5][2]=$var[apellidos_declara].' '.$var[nombres_declara];//110 67

            if($var[tipo_id_paciente_eve]=='CC')
            {
                $vect[6][0]=60;
                $vect[6][1]=78;
                $vect[6][2]='X';// 60 74
            }
            elseif($var[tipo_id_paciente_eve]=='TI')
            {
                $vect[6][0]=72;
                $vect[6][1]=78;
                $vect[6][2]='X';//72 74
            }
            elseif($var[tipo_id_paciente_eve]=='CE')
            {
                $vect[6][0]=85;
                $vect[6][1]=78;
                $vect[6][2]='X';//85 74 
            }
            elseif($var[tipo_id_paciente_eve]=='PAS')
            {
                $vect[6][0]=92;
                $vect[6][1]=78;
                $vect[6][2]='X';//92 74 
            }

            $vect[7][0]=110;
            $vect[7][1]=76;
            $vect[7][2]=$var[paciente_id_eve];//110 74
    
            $vect[8][0]=145;
            $vect[8][1]=76;
            $vect[8][2]=$var[expedida];//145 74
            
            $vect[9][0]=55;
            $vect[9][1]=82;
            if($var[residencia_direccion]==NULL)
            {
                $var[residencia_direccion]='*****';
            }
            $vect[9][2]=$var[residencia_direccion];//55 78
            
            $vect[87][0]=135;
            $vect[87][1]=80;
            $vect[87][2]=$var[municipio];//135 78
            
            $vect[10][0]=55;
            $vect[10][1]=86;
            $vect[10][2]=$var[departamento];//55 82
            
            $vect[11][0]=135;
            $vect[11][1]=86;
            $vect[11][2]=$var[residencia_telefono];//135 82
            
            $vect[12][0]=75;
            $vect[12][1]=93;
            $vect[12][2]=$var[apellidos_declara].' '.$var[nombres_declara]; //75 87
            
            $vect[13][0]=45;
            $vect[13][1]=97;
            $vect[13][2]=$var[declara_id]; //45 90
            
            $vect[14][0]=95;
            $vect[14][1]=97;
            $vect[14][2]=$var[expedida]; //95 90
            
            $fecha=explode(' ',$var[fecha_accidente]);
            $fecha1=explode('-',$fecha[0]);
            $fecha2=explode(':',$fecha[1]);

            $vect[15][0]=65;
            $vect[15][1]=102;
            $vect[15][2]=$fecha1[2]; //65 98
            
            $vect[16][0]=80;
            $vect[16][1]=102;
            $vect[16][2]=$fecha1[1]; //80 98
            
            $vect[17][0]=95;
            $vect[17][1]=102;
            $vect[17][2]=$fecha1[0]; //95 98
            
            $vect[18][0]=112;
            $vect[18][1]=102;
            $vect[18][2]=$fecha2[0]." : ".$fecha2[1]; //112 98
            
            $fecha=explode(' ',$var[fecha_ingreso]);
            $fecha1=explode('-',$fecha[0]);
            $fecha2=explode(':',$fecha[1]);

            $vect[19][0]=65;
            $vect[19][1]=106;
            $vect[19][2]=$fecha1[2]; //65 102
            
            $vect[20][0]=80;
            $vect[20][1]=106;
            $vect[20][2]=$fecha1[1]; //80 102
            
            $vect[21][0]=95;
            $vect[21][1]=106;
            $vect[21][2]=$fecha1[0]; //95 102
            
            $vect[22][0]=112;
            $vect[22][1]=106;
            $vect[22][2]=$fecha2[0]." : ".$fecha2[1]; // 112 102
            
            if($var[datos1_ta]==NULL)
            {
                $var[datos1_ta]='****';
            }
            if($var[datos2_fc]==NULL)
            {
                $var[datos2_fc]='****';
            }
            if($var[datos3_fr]==NULL)
            {
                $var[datos3_fr]='****';
            }
            if($var[datos4_te]==NULL)
            {
                $var[datos4_te]='****';
            }
            if($var[datos5_conciencia]==NULL)
            {
                $var[datos5_conciencia]='****';
            }
            if($var[datos5_conciencia]==1)
            {
                $uno=' X ';
            }
            else
            {
                $uno=' __ ';
            }
            if($var[datos5_conciencia]==2)
            {
                $dos=' X ';
            }
            else
            {
                $dos=' __ ';
            }
            if($var[datos5_conciencia]==3)
            {
                $tres=' X ';
            }
            else
            {
                $tres=' __ ';
            }
            if($var[datos5_conciencia]==4)
            {
                $cuatro=' X ';
            }
            else
            {
                $cuatro=' __ ';
            }
            if($var[datos6_glasgow]==NULL)
            {
                $var[datos6_glasgow]='****';
            }

            $vect[23][0]=57;
            $vect[23][1]=111;
            $vect[23][2]=$var[datos1_ta]; //60 107
            
            $vect[24][0]=87;
            $vect[24][1]=111;
            $vect[24][2]=$var[datos2_fc]; //90 107
            
            $vect[25][0]=107;
            $vect[25][1]=111;
            $vect[25][2]=$var[datos3_fr]; // 110 107
            
            $vect[26][0]=132;
            $vect[26][1]=111;
            $vect[26][2]=$var[datos4_te]; // 135 107
            
            $vect[27][0]=77;
            $vect[27][1]=116;
            $vect[27][2]=$uno; // 80 112 
            
            $vect[28][0]=102;
            $vect[28][1]=114;
            $vect[28][2]=$dos; //102 112

            $vect[29][0]=127;
            $vect[29][1]=114;
            $vect[29][2]=$tres; //122 112

            $vect[30][0]=147;
            $vect[30][1]=114;
            $vect[30][2]=$cuatro; //145 112

            $vect[31][0]=173;
            $vect[31][1]=114;
            $vect[31][2]=$var[datos6_glasgow]; //170 112

            if($var[estado_embriaguez]==1)
            {
                $uno=' X ';
            }
            else
            {
                $uno=' __ ';
            }
            if($var[estado_embriaguez]==2)
            {
                $dos=' X ';
            }
            else
            {
                $dos=' __ ';
            }

            $vect[32][0]=72;
            $vect[32][1]=122;
            $vect[32][2]=$uno; //75 118

            $vect[33][0]=88;
            $vect[33][1]=122;
            $vect[33][2]=$dos; //90 118

            if($var[diagnostico1]==NULL)
            {
                $var[diagnostico1]='NORMAL';
            }
            $vect[34][0]=35;
            $vect[34][1]=147;
            $vect[34][2]=substr($var[diagnostico1],0,90); //35 137

            $vect[35][0]=35;
            $vect[35][1]=149;
            $vect[35][2]=substr($var[diagnostico1],90,115); //35 139

            $vect[36][0]=35;
            $vect[36][1]=151;
            $vect[36][2]=substr($var[diagnostico1],205,115); //35 141

            $vect[37][0]=35;
            $vect[37][1]=153;
            $vect[37][2]=substr($var[diagnostico1],320,115); //35 143

            $vect[38][0]=35;
            $vect[38][1]=155;
            $vect[38][2]=substr($var[diagnostico1],435,115); //35 145

            if($var[diagnostico2]==NULL)
            {
                $var[diagnostico2]='NORMAL';
            }
            $vect[39][0]=35;
            $vect[39][1]=163;
            $vect[39][2]=substr($var[diagnostico2],0,90); //35 153

            $vect[40][0]=35;
            $vect[40][1]=165;
            $vect[40][2]=substr($var[diagnostico2],90,115);//35 155

            $vect[41][0]=35;
            $vect[41][1]=167;
            $vect[41][2]=substr($var[diagnostico2],205,115);// 35 157

            $vect[42][0]=35;
            $vect[42][1]=169;
            $vect[42][2]=substr($var[diagnostico2],320,115); //35 159

            $vect[43][0]=35;
            $vect[43][1]=171;
            $vect[43][2]=substr($var[diagnostico2],435,115); //35 161

            if($var[diagnostico3]==NULL)
            {
                $var[diagnostico3]='NORMAL';
            }
            $vect[44][0]=35;
            $vect[44][1]=178;
            $vect[44][2]=substr($var[diagnostico3],0,90); //35 168

            $vect[45][0]=35;
            $vect[45][1]=180;
            $vect[45][2]=substr($var[diagnostico3],90,115);//35 170

            $vect[46][0]=35;
            $vect[46][1]=182;
            $vect[46][2]=substr($var[diagnostico3],205,115);// 35 172

            $vect[47][0]=35;
            $vect[47][1]=184;
            $vect[47][2]=substr($var[diagnostico3],320,115); //35 174

            $vect[48][0]=35;
            $vect[48][1]=186;
            $vect[48][2]=substr($var[diagnostico3],435,115); //35 176
            if($var[diagnostico4]==NULL)
            {
                $var[diagnostico4]='NORMAL';
            }
            $vect[49][0]=35;
            $vect[49][1]=192;
            $vect[49][2]=substr($var[diagnostico4],0,90); //35 182

            $vect[50][0]=35;
            $vect[50][1]=194;
            $vect[50][2]=substr($var[diagnostico4],90,115);//35 184

            $vect[51][0]=35;
            $vect[51][1]=196;
            $vect[51][2]=substr($var[diagnostico4],205,115);// 35 186

            $vect[52][0]=35;
            $vect[52][1]=198;
            $vect[52][2]=substr($var[diagnostico4],320,115); //35 188

            $vect[53][0]=35;
            $vect[53][1]=200;
            $vect[53][2]=substr($var[diagnostico4],435,115); //35 190
            if($var[diagnostico5]==NULL)
            {
                $var[diagnostico5]='NORMAL';
            }
            $vect[54][0]=35;
            $vect[54][1]=208;
            $vect[54][2]=substr($var[diagnostico5],0,90); //35 198

            $vect[55][0]=35;
            $vect[55][1]=210;
            $vect[55][2]=substr($var[diagnostico5],90,115);//35 200

            $vect[56][0]=35;
            $vect[56][1]=212;
            $vect[56][2]=substr($var[diagnostico5],205,115);// 35 202

            $vect[57][0]=35;
            $vect[57][1]=214;
            $vect[57][2]=substr($var[diagnostico5],320,115); //35 204

            $vect[58][0]=35;
            $vect[58][1]=216;
            $vect[58][2]=substr($var[diagnostico5],435,115); //35 206
            if($var[diagnostico6]==NULL)
            {
                $var[diagnostico6]='NORMAL';
            }
            $vect[59][0]=35;
            $vect[59][1]=222;
            $vect[59][2]=substr($var[diagnostico6],0,90); //35 212

            $vect[60][0]=35;
            $vect[60][1]=224;
            $vect[60][2]=substr($var[diagnostico6],90,115);//35 214

            $vect[61][0]=35;
            $vect[61][1]=226;
            $vect[61][2]=substr($var[diagnostico6],205,115);// 35 216

            $vect[62][0]=35;
            $vect[62][1]=228;
            $vect[62][2]=substr($var[diagnostico6],320,115); //35 218

            $vect[63][0]=35;
            $vect[63][1]=230;
            $vect[63][2]=substr($var[diagnostico6],435,115); //35 220
            if($var[diagnostico7]==NULL)
            {
                $var[diagnostico7]='NORMAL';
            }
            $vect[64][0]=35;
            $vect[64][1]=238;
            $vect[64][2]=substr($var[diagnostico7],0,90); //35 225

            $vect[65][0]=35;
            $vect[65][1]=240;
            $vect[65][2]=substr($var[diagnostico7],90,115);//35 227

            $vect[66][0]=35;
            $vect[66][1]=242;
            $vect[66][2]=substr($var[diagnostico7],205,115);// 35 229

            $vect[67][0]=35;
            $vect[67][1]=244;
            $vect[67][2]=substr($var[diagnostico7],320,115); //35 231

            $vect[68][0]=35;
            $vect[68][1]=245;
            $vect[68][2]=substr($var[diagnostico7],435,115); //35 233
            if($var[diagnostico8]==NULL)
            {
                $var[diagnostico8]='NORMAL';
            }
            $vect[69][0]=35;
            $vect[69][1]=253;
            $vect[69][2]=substr($var[diagnostico8],0,90); //35 243

            $vect[70][0]=35;
            $vect[70][1]=255;
            $vect[70][2]=substr($var[diagnostico8],90,115);//35 245

            $vect[71][0]=35;
            $vect[71][1]=257;
            $vect[71][2]=substr($var[diagnostico8],205,115);// 35 247

            $vect[72][0]=35;
            $vect[72][1]=259;
            $vect[72][2]=substr($var[diagnostico8],320,115); //35 249

            $vect[73][0]=35;
            $vect[73][1]=261;
            $vect[73][2]=substr($var[diagnostico8],435,115); //35 251
            if($var[diagnostico9]==NULL)
            {
                $var[diagnostico9]='NORMAL';
            }
            $vect[74][0]=35;
            $vect[74][1]=267;
            $vect[74][2]=substr($var[diagnostico9],0,90); //35 257

            $vect[75][0]=35;
            $vect[75][1]=269;
            $vect[75][2]=substr($var[diagnostico9],90,115);//35 259

            $vect[76][0]=35;
            $vect[76][1]=271;
            $vect[76][2]=substr($var[diagnostico9],205,115);// 35 261

            $vect[77][0]=35;
            $vect[77][1]=273;
            $vect[77][2]=substr($var[diagnostico9],320,115); //35 263

            $vect[78][0]=35;
            $vect[78][1]=275;
            $vect[78][2]=substr($var[diagnostico9],435,115); //35 265

            if($var[diagnostico_def]==NULL)
            {
                $var[diagnostico_def]='****';
            } 

            $vect[79][0]=35;
            $vect[79][1]=283;
            $vect[79][2]=substr($var[diagnostico_def],0,90); //35 275

            $vect[80][0]=35;
            $vect[80][1]=285;
            $vect[80][2]=substr($var[diagnostico_def],90,115);//35 277

            $vect[81][0]=35;
            $vect[81][1]=287;
            $vect[81][2]=substr($var[diagnostico_def],205,115);// 35 279

            $vect[82][0]=35;
            $vect[82][1]=289;
            $vect[82][2]=substr($var[diagnostico_def],320,115); //35 281

            $vect[83][0]=35;
            $vect[83][1]=291;
            $vect[83][2]=substr($var[diagnostico_def],435,115); //35 283

            $vect[84][0]=35;
            $vect[84][1]=293;
            $vect[84][2]=substr($var[diagnostico_def],665,115);//35 285

            $vect[85][0]=72;
            $vect[85][1]=328;
            $vect[85][2]=$var[nombre_tercero]; //72 298

            $vect[86][0]=60;
            $vect[86][1]=333;
            $vect[86][2]=$var[tarjeta_profesional]; // 60 303

            return $vect;
        }

            // REPORTE SOAT 3
        function BuscarReporteAmbulanciaSoat($ambulancia)//Busca la informaci?n de la ambulancia para imprimir
        {
            list($dbconn) = GetDBconn();
            $query = "SELECT A.tipo_id_paciente,
                    A.conductor_id,
                    A.nombre_conductor,
                    A.direccion,
                    A.telefono,
                    A.placa_ambulancia,
                    A.lugar_desde,
                    A.lugar_hasta,
                    B.poliza,
                    C.tipo_id_tercero,
                    C.id,
                    D.descripcion,
                    E.municipio AS exmunicipio,
                    F.municipio AS municipio,
                    G.fecha_accidente,
                    H.placa_vehiculo
                    FROM soat_ambulancias AS A,
                    soat_eventos AS B,
                    empresas AS C,
                    tipo_id_terceros AS D,
                    tipo_mpios AS E,
                    tipo_mpios AS F,
                    soat_accidente AS G,
                    soat_polizas AS H
                    WHERE A.ambulancia_id=".$ambulancia."
                    --AND A.ambulancia_id=B.ambulancia_id
                    AND A.evento=B.evento
                    AND B.empresa_id=C.empresa_id
                    AND C.tipo_id_tercero=D.tipo_id_tercero
                    AND A.extipo_pais_id=E.tipo_pais_id
                    AND A.extipo_dpto_id=E.tipo_dpto_id
                    AND A.extipo_mpio_id=E.tipo_mpio_id
                    AND A.tipo_pais_id=F.tipo_pais_id
                    AND A.tipo_dpto_id=F.tipo_dpto_id
                    AND A.tipo_mpio_id=F.tipo_mpio_id
                    AND B.accidente_id=G.accidente_id
                    AND B.poliza=H.poliza;";
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
            $var['empresa']=$_SESSION['soa1']['razonso'];
            $var['tipo_paciente_id']=$_SESSION['soat']['evento']['nombresoat']['tipo_id_paciente'];
            $var['paciente_id']=$_SESSION['soat']['evento']['nombresoat']['paciente_id'];
            $var['nombrpa']=$_SESSION['soat']['evento']['nombresoat']['primer_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_apellido']."".' '."".$_SESSION['soat']['evento']['nombresoat']['primer_nombre']."".' '."".$_SESSION['soat']['evento']['nombresoat']['segundo_nombre'];
            $fecha=explode(' ',$var['fecha_accidente']);
            $hora=$fecha[1];
            $fecha=explode('-',$fecha[0]);
            $fecha=$fecha[2].'/'.$fecha[1].'/'.$fecha[0].' '.' '.$hora;
            $var['fecha_accidente']=$fecha;
    
            $vect[0][0]=160;
            $vect[0][1]=15;
            $vect[0][2]='-';//162 25
    
            $fecha=explode(' ',$var[fecha_accidente]);
            $fecha_accidente=explode('/',$fecha[0]);
    
//             $vect[1][0]=160;
//             $vect[1][1]=20;
//             $vect[1][2]=$fecha_accidente[2][0];//167 32 A?O
// 
//             $vect[2][0]=163;
//             $vect[2][1]=20;
//             $vect[2][2]=$fecha_accidente[2][1];//167 32 A?O

            $vect[3][0]=166;
            $vect[3][1]=20;
            $vect[3][2]=$fecha_accidente[2][2];//167 32 A?O

            $vect[4][0]=169;
            $vect[4][1]=20;
            $vect[4][2]=$fecha_accidente[2][3];//167 32 A?O


            $vect[5][0]=172;
            $vect[5][1]=20;
            $vect[5][2]=$fecha_accidente[1][0];//175 32 MES

            $vect[6][0]=175;
            $vect[6][1]=20;
            $vect[6][2]=$fecha_accidente[1][1];//175 32 MES
    
            $vect[7][0]=178;
            $vect[7][1]=20;
            $vect[7][2]=$fecha_accidente[0][0];//183 32 DIA
    
            $vect[8][0]=181;
            $vect[8][1]=20;
            $vect[8][2]=$fecha_accidente[0][1];//183 32 DIA


            $vect[9][0]=166;
            $vect[9][1]=24;
            $vect[9][2]=$var[placa_vehiculo][0];//162 37
    
            $vect[10][0]=169;
            $vect[10][1]=24;
            $vect[10][2]=$var[placa_vehiculo][1];//162 37

            $vect[11][0]=172;
            $vect[11][1]=24;
            $vect[11][2]=$var[placa_vehiculo][2];//162 37

            $vect[12][0]=175;
            $vect[12][1]=24;
            $vect[12][2]=$var[placa_vehiculo][3];//162 37

            $vect[13][0]=178;
            $vect[13][1]=24;
            $vect[13][2]=$var[placa_vehiculo][4];//162 37

            $vect[14][0]=181;
            $vect[14][1]=24;
            $vect[14][2]=$var[placa_vehiculo][5];//162 37

            $vect[15][0]=145;
            $vect[15][1]=28;
            $vect[15][2]=$var[poliza][0];//140 42

            $vect[16][0]=148;
            $vect[16][1]=28;
            $vect[16][2]=$var[poliza][1];//140 42

            $vect[17][0]=151;
            $vect[17][1]=28;
            $vect[17][2]=$var[poliza][2];//140 42

            $vect[18][0]=154;
            $vect[18][1]=28;
            $vect[18][2]=$var[poliza][3];//140 42

            $vect[19][0]=157;
            $vect[19][1]=28;
            $vect[19][2]=$var[poliza][6];//140 42

            $vect[20][0]=160;
            $vect[20][1]=28;
            $vect[20][2]=$var[poliza][6];//140 42

            $vect[21][0]=163;
            $vect[21][1]=28;
            $vect[21][2]=$var[poliza][7];//140 42

            $vect[22][0]=166;
            $vect[22][1]=28;
            $vect[22][2]=$var[poliza][8];//140 42

            $vect[23][0]=169;
            $vect[23][1]=28;
            $vect[23][2]=$var[poliza][9];//140 42

            $vect[24][0]=172;
            $vect[24][1]=28;
            $vect[24][2]=$var[poliza][10];//140 42

            $vect[25][0]=175;
            $vect[25][1]=28;
            $vect[25][2]=$var[poliza][11];//140 42

            $vect[26][0]=178;
            $vect[26][1]=28;
            $vect[26][2]=$var[poliza][12];//140 42

            $vect[27][0]=181;
            $vect[27][1]=28;
            $vect[27][2]=$var[poliza][13];//140 42

    //      $vect[2][0]=32;
    //      $vect[2][1]=50;
    //      $vect[2][2]=$var[tipo_id_paciente];//32 50
    
            $vect[28][0]=32;
            $vect[28][1]=32;
            $vect[28][2]=$var[conductor_id];//32 50
    
            $vect[29][0]=70;
            $vect[29][1]=32;
            $vect[29][2]=$var[exmunicipio];//70 50
    
            $vect[30][0]=102;
            $vect[30][1]=39;
            $vect[30][2]=$var[nombre_conductor];//102 44
    
            $vect[31][0]=110;
            $vect[31][1]=62;
            $vect[31][2]=$var[direccion];//110 50
    
            $vect[32][0]=158;
            $vect[32][1]=62;
            $vect[32][2]=$var[municipio];//155 50
    
            $vect[33][0]=188;
            $vect[33][1]=62;
            $vect[33][2]=$var[telefono];//180 50
    
            $vect[34][0]=73;
            $vect[34][1]=68;
            $vect[34][2]=$var[lugar_desde];//73 57
    
            $vect[35][0]=125;
            $vect[35][1]=68;
            $vect[35][2]=$var[lugar_hasta];//125 57
    
            $vect[36][0]=72;
            $vect[36][1]=72;
            $vect[36][2]='X';//72 62
    
            $vect[37][0]=180;
            $vect[37][1]=72;
            $vect[37][2]=$var[placa_ambulancia];//170 62
    
            $vect[38][0]=142;
            $vect[38][1]=80;
            $vect[38][2]=$var[nombrpa];//142 69
    
            $vect[39][0]=67;
            $vect[39][1]=84;
            $vect[39][2]=$var[paciente_id];//67 74
    
            $vect[40][0]=30;
            $vect[40][1]=92;
            $vect[40][2]=$var[empresa];//30 83
    
            $vect[41][0]=105;
            $vect[41][1]=92;
            $vect[41][2]=$var[id];//105 83

            return $vect;
        }

/*
TIPO DOCUMENTO: 
RC


DOCUMENTO: 
96091127597341


PRIMER NOMBRE: 
NATALIA


SEGUNDO NOMBRE: 


PRIMER APELLIDO: 
CASTA?O


SEGUNDO APELLIDO: 
LOPEZ


FECHA NACIMIENTO: 
1996-09-11


DIRECCION: 
CLL 39 No. 27A-65 Barrio Av Cali


TELEFONO: 
2240241


NOMBRE MADRE: 


OCUPACION: 
ESTUDIANTE


SEXO: 
Femenino


CAUSA EXTERNA: 
Otra


VIA INGRESO: 
Urgencias

*/    }//end of class

?>
