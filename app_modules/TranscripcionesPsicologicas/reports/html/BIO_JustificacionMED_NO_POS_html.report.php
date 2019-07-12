<?php

/**
 * $Id: BIO_JustificacionMED_NO_POS_html.report.php,v 1.1 2007/05/30 14:44:18 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class BIO_JustificacionMED_NO_POS_html_report
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
    function BIO_JustificacionMED_NO_POS_html_report($datos=array())
    {
     	$this->datos = $datos;
		return true;
    }

	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
													 'subtitulo'=>'',
													 'logo'=>'logocliente.png',
													 'align'=>'left'));
		return $Membrete;
	}

	function CrearReporte()
     {
		$Datos = $this->ObtenerDatosJustificaciones($this->datos['justificacion_id']);
          
          $DatosPaciente = $this->ObtenerIngresoPaciente($Datos['ingreso']);
		
		$fecha = explode(" ", $DatosPaciente[0]['fecha_ingreso']);
          $fechaF = explode("/", $fecha[0]);
          $EdadArr = CalcularEdad($DatosPaciente[0]['fecha_nacimiento'], $fechaF[2]."/".$fechaF[1]."/".$fechaF[0]);
          
          $Salida .= "	<table width=\"95%\" align=\"center\" border=\"0\">\n";
		$Salida .= "		<tr>\n";
		$Salida .= "			<td align=\"center\" width=\"25%\" height=\"30\"><b>JUSTIFICACION DE MEDICAMENTOS NO POS</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "	</table><br>";
          
          $Salida.="<table width=\"95%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$Salida.="<tr>\n";
		$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">IDENTIFICACION:&nbsp;".$DatosPaciente[0]['tipo_id_paciente']." ".$DatosPaciente[0]['paciente_id']."</FONT></td>\n";
		$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"30%\">NOMBRE:&nbsp;".strtoupper($DatosPaciente[0]['nombres'])." ".strtoupper($DatosPaciente[0]['apellidos'])."</FONT></td>\n";
		$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">HC:&nbsp;";
		if($DatosPaciente[0]['historia_numero']!="")
		{
			if($DatosPaciente[0]['historia_prefijo']!="")
			{
				$Salida .= $DatosPaciente[0]['historia_numero']." - ". $DatosPaciente[0]['historia_prefijo'];
			}
			else
			{
				$Salida .= $DatosPaciente[0]['paciente_id']." - ".$DatosPaciente[0]['historia_prefijo'];
			}
		}
		else
		{
			$Salida.= $DatosPaciente[0]['paciente_id']." - ".$DatosPaciente[0]['tipo_id_paciente'];
		}
		$Salida.="</td>\n";
		$Salida.="<td ALIGN=\"JUSTIFY\" WIDTH=\"20%\" class=\"Normal_10N\">EDAD:&nbsp;";
		$Salida.= $EdadArr['anos'].'&nbsp;Años';
		$Salida.="</td>\n";
		$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\" class=\"Normal_10N\">SEXO:&nbsp;";
		$Salida.= $DatosPaciente[0]['sexo_id'];
		$Salida.="</td>\n";
		$Salida.="</tr>\n";
		$Salida.="</table>\n";
          
		$Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"95%\" BORDER=\"0\">";
		$Salida.= "<TR>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">FECHA DE INGRESO:&nbsp;&nbsp;".$DatosPaciente[0]['fecha_ingreso']."</TD>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"40%\">No. INGRESO:&nbsp;&nbsp;".$DatosPaciente[0]['ingreso']."</TD>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">No. CUENTA:&nbsp;&nbsp;".$DatosPaciente[0]['numerodecuenta']."</TD>";
		$Salida.= "</TR>";
		$Salida.= "</TABLE>\n";

		$Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"95%\" BORDER=\"0\">";
		$Salida.= "<TR>";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">CLIENTE:&nbsp;&nbsp;".strtoupper($DatosPaciente[0]['cliente'])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"40%\">PLAN:&nbsp;&nbsp;".strtoupper($DatosPaciente[0]['plan_descripcion'])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"20%\">TIPO AFILIADO:&nbsp;&nbsp;".strtoupper($DatosPaciente[0]['tipo_afiliado_nombre'])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"10%\">RANGO:&nbsp;".$DatosPaciente[0]['rango']."</td>\n";
          $Salida .= "</tr>\n";
		$Salida.= "</table>\n<br><br>";
          
		$DatosMed = $this->ObtenerDatosMedicamentos($Datos['codigo_producto']);
          $Salida .= "	<table width=\"95%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr align=\"center\">\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\">PRODUCTO</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\">PRINCIPIO ACTIVO</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\">CONCENTRACION</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\">FORMA</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosMed['descripcion']."</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosMed['principio']."</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosMed['concentracion_forma_farmacologica']."</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$DatosMed['forma_farma']."</td>\n";
		$Salida .= "		</tr>\n";
          
	
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\">DOSIS POR DIA</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$Datos['dosis_dia']." día(s)</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\">DIAS DE TRATAMIENTO</td>\n";
		$Salida .= "			<td class=\"normal_10\" width=\"25%\">".$Datos['duracion']." día(s)</td>\n";		
          $Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td colspan=\"4\">&nbsp;</td>\n";
		$Salida .= "		</tr>\n";
		
		$DX = $this->ObtenerDiagnosticos($this->datos['justificacion_id']);
          $Salida .= "		<tr>\n";
		$Salida .= "			<td colspan=\"4\">\n";
		$Salida .= "				<table width=\"100%\" border=\"1\">\n";
		$Salida .= "					<tr>\n";
		$Salida .= "						<td colspan=\"2\" class=\"normal_10N\" align=\"center\">DIAGNOSTICOS</td>\n";
		$Salida .= "					</tr>\n";
		$Salida .= "					<tr>\n";
		$Salida .= "						<td width=\"20%\" class=\"normal_10N\" align=\"center\">CODIGO</td>\n";
		$Salida .= "						<td width=\"80%\" class=\"normal_10N\" align=\"center\">DESCRIPCION</td>\n";
          $Salida .= "					</tr>\n";
          for($i=0; $i<sizeof($DX); $i++)
          {
               $Salida .= "					<tr>\n";
               $Salida .= "						<td width=\"20%\" class=\"normal_10\">".$DX[$i]['diagnostico_id']."</td>\n";
               $Salida .= "						<td width=\"80%\" class=\"normal_10\">".$DX[$i]['diagnostico_nombre']."</td>\n";
               $Salida .= "					</tr>\n";
          }
          $Salida .= "				</table>\n";
          $Salida .= "			</td>\n";
		$Salida .= "		</tr>\n";
		
          if($Datos['descripcion_caso_clinico'])
          {
               $Salida .= "		<tr>\n";
               $Salida .= "			<td colspan=\"4\">&nbsp;</td>\n";
               $Salida .= "		</tr>\n";
          
               $Salida .= "		<tr>\n";
               $Salida .= "			<td colspan=\"4\" class=\"normal_10N\" align=\"center\">DESCRIPCION DEL CASO CLINICO</td>\n";
               $Salida .= "		</tr>\n";
               $Salida .= "		<tr>\n";
               $Salida .= "			<td colspan=\"4\" class=\"normal_10\">".$Datos['descripcion_caso_clinico']."</td>\n";
			$Salida .= "		</tr>\n";
		}
          
		$Alternativa = $this->ObtenerAlternativas_POS($this->datos['justificacion_id']);
          if($Alternativa)
          {
               $Salida .= "		<tr>\n";
               $Salida .= "			<td colspan=\"4\">&nbsp;</td>\n";
               $Salida .= "		</tr>\n";
               
               $Salida .= "		<tr>\n";
               $Salida .= "			<td colspan=\"4\" class=\"normal_10N\" align=\"center\">ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>\n";
               $Salida .= "		</tr>\n";
               for($i=0; $i<sizeof($Alternativa); $i++)
               {
                    if($i == 0)
                    	$posibilidad = "PRIMERA POSIBILIDAD TERAPEUTICA POS";
                    else
                    	$posibilidad = "SEGUNDA POSIBILIDAD TERAPEUTICA POS";
                    
                    $Salida .= "		<tr>\n";
                    $Salida .= "			<td colspan=\"4\" class=\"normal_10N\">".$posibilidad."</td>\n";
                    $Salida .= "		</tr>\n";
                    
                    $Salida .= "		<tr>\n";
                    $Salida .= "			<td class=\"normal_10N\">MEDICAMENTO</td>\n";
                    $Salida .= "			<td class=\"normal_10\">".$Alternativa[$i]['medicamento_pos']."</td>\n";
                    $Salida .= "			<td class=\"normal_10N\">PRINCIPIO ACTIVO</td>\n";
                    $Salida .= "			<td class=\"normal_10\">".$Alternativa[$i]['principio_activo']."</td>\n";
                    $Salida .= "		</tr>\n";
                    
                    $Salida .= "		<tr>\n";
                    $Salida .= "			<td class=\"normal_10N\">DOSIS POR DIA</td>\n";
                    $Salida .= "			<td class=\"normal_10\">".$Alternativa[$i]['dosis_dia_pos']."</td>\n";
                    $Salida .= "			<td class=\"normal_10N\">DIAS DE TRATAMIENTO</td>\n";
                    $Salida .= "			<td class=\"normal_10\">".$Alternativa[$i]['duracion_pos']."</td>\n";
                    $Salida .= "		</tr>\n";
                    
                    $Salida .= "		<tr>\n";
                    $Salida .= "			<td class=\"normal_10N\">MEJORA CON TRATAMIENTO</td>\n";
                    $X = "";
                    if($Alternativa[$i]['sw_no_mejoria'] == 0)
                    	$X = "X";
                    $Salida .= "			<td class=\"normal_10N\" align=\"center\">".$X."</td>\n";
                    $Salida .= "			<td class=\"normal_10N\">NO MEJORA CON TRATAMIENTO</td>\n";
                    $X = "";
                    if($Alternativa[$i]['sw_no_mejoria'] == 1)
                    	$X = "X";
                    $Salida .= "			<td class=\"normal_10N\" align=\"center\">".$X."</td>\n";
                    
                    $Salida .= "		</tr>\n";
                    
                    $Salida .= "		<tr>\n";
                    $R = "";
                    if($Alternativa[$i]['sw_reaccion_secundaria'] == 1)
                    	$R = "X";
                    $Salida .= "			<td class=\"normal_10N\">REACCION SECUNDARIA [ ".$R." ]</td>\n";
                    $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Alternativa[$i]['reaccion_secundaria']."</td>\n";
                    $Salida .= "		</tr>\n";
                    
                    $Salida .= "		<tr>\n";
                    $C = "";
                    if($Alternativa[$i]['sw_contraindicacion'] == 1)
                    	$C = "X";
                    $Salida .= "			<td class=\"normal_10N\">CONTRAINDICACION EXPRESA [ ".$C." ]</td>\n";
                    $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Alternativa[$i]['contraindicacion']."</td>\n";
                    $Salida .= "		</tr>\n";
                    
                    $Salida .= "		<tr>\n";
                    $Salida .= "			<td class=\"normal_10N\">OTRAS</td>\n";
                    $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Alternativa[$i]['otras']."</td>\n";
                    $Salida .= "		</tr>\n";
               }
          }
		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td colspan=\"4\">&nbsp;</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td colspan=\"4\" class=\"normal_10N\" align=\"center\">CRITERIOS DE JUSTIFICACION</td>\n";
		$Salida .= "		</tr>\n";
                    
          $Salida .= "		<tr>\n";
          $Salida .= "			<td class=\"normal_10N\">JUSTIFICACION DE LA SOLICITUD:</td>\n";
          $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Datos['justificacion']."</td>\n";
          $Salida .= "		</tr>\n";
          
          $Salida .= "		<tr>\n";
          $Salida .= "			<td class=\"normal_10N\">VENTAJAS DE ESTE MEDICAMENTO:</td>\n";
          $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Datos['ventajas_medicamento']."</td>\n";
          $Salida .= "		</tr>\n";

          $Salida .= "		<tr>\n";
          $Salida .= "			<td class=\"normal_10N\">VENTAJAS DEL TRATAMIENTO:</td>\n";
          $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Datos['ventajas_tratamiento']."</td>\n";
          $Salida .= "		</tr>\n";
          
          $Salida .= "		<tr>\n";
          $Salida .= "			<td class=\"normal_10N\">PRECAUCIONES:</td>\n";
          $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Datos['precauciones']."</td>\n";
          $Salida .= "		</tr>\n";
          
          $Salida .= "		<tr>\n";
          $Salida .= "			<td class=\"normal_10N\">CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>\n";
          $Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Datos['controles_evaluacion_efectividad']."</td>\n";
          $Salida .= "		</tr>\n";
          
          $Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">TIEMPO DE RESPUESTA ESPERADO</td>\n";
		$Salida .= "			<td class=\"normal_10\" colspan=\"2\">".$Datos['tiempo_respuesta_esperado']."&nbsp;Día(s).</td>\n";
		$Salida .= "		</tr>\n";

          $Salida .= "		<tr>\n";
          $I = "";
          if($Datos['sw_riesgo_inminente'] == 1)
               $I = "X";
		$Salida .= "			<td class=\"normal_10N\">RIESGO INMINENTE [ ".$I." ]</td>\n";
		$Salida .= "			<td class=\"normal_10\" colspan=\"3\">".$Datos['riesgo_inminente']."</td>\n";
		$Salida .= "		</tr>\n";
		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td colspan=\"4\">&nbsp;</td>\n";
		$Salida .= "		</tr>\n";
          
          $Salida .= "		<tr>\n";
          if($Datos['sw_agotadas_posibilidades_existentes'] == 1)
               $sia = "X";
          else
          	$noa = "X";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES:</td>\n";
		$Salida .= "			<td class=\"normal_10N\">SI  [ ".$sia." ]</td>\n";
		$Salida .= "			<td class=\"normal_10N\">NO  [ ".$noa." ]</td>\n";
		$Salida .= "		</tr>\n";
         
          $Salida .= "		<tr>\n";
          if($Datos['sw_homologo_pos'] == 1)
               $sih = "X";
          else
          	$noh = "X";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">TIENE HOMOLOGO EN EL POS:</td>\n";
		$Salida .= "			<td class=\"normal_10N\">SI  [ ".$sih." ]</td>\n";
		$Salida .= "			<td class=\"normal_10N\">NO  [ ".$noh." ]</td>\n";
		$Salida .= "		</tr>\n";

          $Salida .= "		<tr>\n";
          if($Datos['sw_comercializacion_pais'] == 1)
               $sic = "X";
          else
          	$noc = "X";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">ES COMERCIALIZADO EN EL PAIS:</td>\n";
		$Salida .= "			<td class=\"normal_10N\">SI  [ ".$sic." ]</td>\n";
		$Salida .= "			<td class=\"normal_10N\">NO  [ ".$noc." ]</td>\n";
		$Salida .= "		</tr>\n";
          
		$Salida .= "	</table>\n";
          
          $this->GetDatosProfesional($Datos['usuario_id_autoriza']);
          
          $Salida .="<BR><BR><BR><TABLE ALIGN=\"center\" WIDTH=\"95%\">";
          $Salida .="<TR>";
          $Salida .="<TD ALIGN=\"left\" CLASS='normal_10N'>Nombres y Apellidos del Médico:&nbsp;&nbsp;".$this->datosProfesional['nombre']."</td>";
          $Salida .="</TR>";
          $Salida .="<TR>";
          $Salida .="<TD ALIGN=\"left\" CLASS='normal_10N'>Especialidad:&nbsp;&nbsp;".$this->datosProfesional['descripcion']."</td>";
          $Salida .="</TR>";
          $Salida .="<TR>";
          $Salida .="<TD ALIGN=\"left\" CLASS='normal_10N'>Tarjeta Profesional No.:&nbsp;&nbsp;".$this->datosProfesional['tarjeta_profesional']."</td>";
          $Salida .="</TR>";
          $Salida .="</TABLE><br>";
          
          $this->User = $this->GetDatosUsuarioSistema(UserGetUID());          
          
          $fechita = date("d-m-Y H:i:s");
          $FechaImprime = $this->FechaStamp($fechita);
          $HoraImprime = $this->HoraStamp($fechita);
          
          $Salida .="<br><TABLE WIDTH=\"95%\" ALIGN=\"center\">";
		$Salida .="<TR>";
          $Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
		$Salida .="<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
		$Salida .="</TR>";
		$Salida .="</table>";
          
		return $Salida;			
	}	      
//*****************************************fin de termino
 
/************************************************************************************ 
* Funcion que permite traer la informacion de la glosa y el detalle del acta de 
* conciliacion (si la hay) de las factura pertenecientes a un cliente
* 
* @return array datos de las facturas
*************************************************************************************/
     
     function ObtenerIngresoPaciente($ingreso)
     {
          list($dbconn) = GetDBconn();
          $query  = "SELECT 	PC.paciente_id,
                              PC.tipo_id_paciente,
                              PC.primer_apellido ||' '|| PC.segundo_apellido AS apellidos,
                              PC.primer_nombre ||' '|| PC.segundo_nombre AS nombres,
                              PC.fecha_nacimiento,
                              PC.fecha_nacimiento_es_calculada,
                              PC.residencia_direccion,
                              PC.residencia_telefono,
                              PC.sexo_id,
                              IG.ingreso,
                              TO_CHAR(IG.fecha_ingreso,'DD/MM/YYYY HH12:MI am') AS fecha_ingreso,
                              CU.numerodecuenta,
                              CU.rango,
                              TA.tipo_afiliado_nombre,
                              VI.via_ingreso_nombre,
                              PL.plan_descripcion,
                              PL.tercero_id,
                              PL.tipo_tercero_id,
                              TE.nombre_tercero AS cliente
          		 FROM	pacientes PC,
                              vias_ingreso VI,
                              cuentas CU
                              LEFT JOIN tipos_afiliado AS TA ON (CU.tipo_afiliado_id = TA.tipo_afiliado_id),
                              planes PL,
                              ingresos IG,
                              terceros AS TE
                    WHERE	IG.paciente_id = PC.paciente_id
                    AND		IG.tipo_id_paciente = PC.tipo_id_paciente
                    AND		VI.via_ingreso_id = IG.via_ingreso_id
                    AND		CU.ingreso = IG.ingreso
                    AND		PL.plan_id = CU.plan_id
                    AND		PL.tercero_id = TE.tercero_id
                    AND		PL.tipo_tercero_id = TE.tipo_id_tercero
                    AND		IG.ingreso = ".$ingreso." ";

          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
               if(!$result->EOF)
               {
                    $Datingreso[0] = $result->GetRowAssoc($ToUpper = false);					
               }
               $result->Close();
          }	
          return $Datingreso;
     }
          
     
     function ObtenerDatosJustificaciones($justificacion)
     {
		list($dbconn) = GetDBconn();
		$sql = "SELECT * 
          	   FROM hc_justificaciones_no_pos_hospitalaria_medicamentos
                  WHERE justificacion_no_pos_id = ".$justificacion.";";
          $result = $dbconn->Execute($sql);
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
               if(!$result->EOF)
               {
                    $DatosJustificacion = $result->GetRowAssoc($ToUpper = false);
               }
               $result->Close();
          }
          return $DatosJustificacion;
     }
     
     
     function ObtenerDatosMedicamentos($codigo)
     {
		list($dbconn) = GetDBconn();
		$sql = "SELECT A.descripcion, 
               		B.cod_principio_activo, B.concentracion_forma_farmacologica, 
                         B.cod_forma_farmacologica,
                         C.descripcion AS principio,
                         D.descripcion AS forma_farma
          	   FROM   inventarios_productos AS A,
               	     medicamentos AS B,
                         inv_med_cod_principios_activos AS C,
                         inv_med_cod_forma_farmacologica AS D
                  WHERE  A.codigo_producto = '".$codigo."'
                  AND    A.codigo_producto = B.codigo_medicamento
                  AND    B.cod_principio_activo = C.cod_principio_activo
                  AND    B.cod_forma_farmacologica = D.cod_forma_farmacologica;";
          $result = $dbconn->Execute($sql);
          if($dbconn->ErrorNo() != 0){
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }else{		
               if(!$result->EOF)
               {
                    $DatosMed = $result->GetRowAssoc($ToUpper = false);
               }
               $result->Close();
          }
          return $DatosMed;
     }
     
     function ObtenerDiagnosticos($justificacion_id)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $sql = "SELECT A.*,
          			B.diagnostico_nombre
          	   FROM hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico AS A,
                  	   diagnosticos AS B
                  WHERE A.justificacion_no_pos_id = ".$justificacion_id."
                  AND A.diagnostico_id = B.diagnostico_id;";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;           
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $DatosDX[] = $data;
          }        
          return $DatosDX;
     }
     
     
     function ObtenerAlternativas_POS($justificacion_id)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          $sql = "SELECT *
          	   FROM hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa
                  WHERE justificacion_no_pos_id = ".$justificacion_id.";";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($sql);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;           
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          while ($data = $result->FetchRow())
          {
               $DatosAlternativa[] = $data;
          }        
          return $DatosAlternativa;
     }
     
     
     function GetDatosProfesional($usuario)
	{
          list($dbconn) = GetDBconn();
          $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion
                FROM profesionales AS A,
               	 profesionales_usuarios AS E
                LEFT JOIN profesionales_especialidades AS B
                ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                WHERE A.usuario_id = ".$usuario."
                AND A.usuario_id = E.usuario_id
                AND E.tercero_id = A.tercero_id
                AND E.tipo_tercero_id = A.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
	}
	//---------------------------------------
     
     function GetDatosUsuarioSistema($usuario)
     {
          $pfj=$this->frmPrefijo;
          $query = "SELECT usuario,
                    nombre
                    FROM system_usuarios
                    WHERE usuario_id = $usuario";
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al ejecutar la conexion";
               $this->mensajeDeError = "Ocurrió un error al intentar obtener los datos del usuario.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
          else
          {
               if($result->EOF){
                    return "ShowMensaje";
               }
               else
               {
                    while ($data = $result->FetchRow()){
                         $DatosUser[] = $data;
                    }
                    return $DatosUser;
               }
          }
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

}
?>