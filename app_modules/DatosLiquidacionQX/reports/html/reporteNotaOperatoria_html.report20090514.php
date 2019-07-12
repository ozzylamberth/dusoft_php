<?php

/**
 * $Id: reporteNotaOperatoria_html.report.php,v 1.4 2006/04/18 19:31:57 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class reporteNotaOperatoria_html_report
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
    function reporteNotaOperatoria_html_report($datos=array())
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
	
		function CrearReporte(){
	
	//*******************************************termino	
		$Datos = $this->ObtenerIngresoPaciente($this->datos['ingreso'],$this->datos['tipoidpaciente'],$this->datos['paciente']);
		
		$EdadArr=CalcularEdad($Datos[0]['fecha_nacimiento'],$FechaFin);	
		
		$Salida .= "	<table width=\"95%\" align=\"center\" border=\"0\">\n";
		$Salida .= "		<tr>\n";
		$Salida .= "			<td align=\"center\" width=\"25%\" height=\"30\"><b>NOTA OPERATORIA</b></td>\n";		
		$Salida .= "		</tr>\n";
		$Salida .= "	</table><br>";
		
		$Salida .= "	<table width=\"95%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"center\" width=\"25%\" colspan=\"4\"><b>DATOS PACIENTE</b></td>\n";		
		$Salida .= "		</tr>\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\" ><b>Nº INGRESO</b></td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['ingreso']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\" ><b>FECHA INGRESO</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['fecha_ingreso']."</td>\n";
		$Salida .= "		</tr>\n";
	
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">Nº CUENTA</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['numerodecuenta']."</td>\n";
		$Salida .= "			<td colspan=\"2\">&nbsp;</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">PACIENTE</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['tipo_id_paciente']." ".$Datos[0]['paciente_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10\"colspan=\"2\">".$Datos[0]['nombres']." ".$Datos[0]['apellidos']."</td>\n";
		$Salida .= "		</tr>\n";
		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">EDAD</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$EdadArr['edad_aprox']."</td>\n";
		$Salida .= "			<td colspan= \"2\">&nbsp;</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">DIRECCION</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['residencia_direccion']."&nbsp;</td>\n";
		$Salida .= "			<td class=\"normal_10N\">TELÉFONO</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['residencia_telefono']."&nbsp;</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">ENTIDAD</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['tipo_tercero_id']." ".$Datos[0]['tercero_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10\"colspan=\"2\">".$Datos[0]['nombre_tercero']."</td>\n";
		$Salida .= "		</tr>\n";
		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">PLAN</td>\n";
		$Salida .= "			<td class=\"normal_10\"colspan=\"3\">".$Datos[0]['plan_descripcion']."</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">VIA DE INGRESO</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['via_ingreso_nombre']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" colspan=\"2\">RESPONSABLE: ".$Datos[0]['responsable']."</td>\n";
		$Salida .= "		</tr>\n";

		$Salida .= "	</table><br>\n";
			//print_r($Datos);
		$datos=$this->ConsultaNotasOperatoriasRealizadas($this->datos['programacion'],$this->datos['tipoidpaciente'],$this->datos['paciente']);
		//print_r($datos);
		if($datos){			
			$Salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
			$Salida .= "  <tr class=\"normal_10N\"><td colspan=\"4\" align=\"center\">DATOS DEL PROCEDIMIENTO</td></tr>";
			(list($fechaIn,$horaIn)=explode(' ',$datos['hora_inicio']));
			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
			(list($hhIn,$mmIn)=explode(':',$horaIn));				
			(list($fechaFn,$horaFn)=explode(' ',$datos['hora_fin']));				
			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
			(list($hhFn,$mmFn)=explode(':',$horaFn));
			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
			$Horas=(int)($segundos/60);				
			$Minutos=($segundos%60);
			$Salida .= "  <tr>";			
			$Salida .= "  <td width=\"20%\" class=\"normal_10N\">FECHA INICIO</td>";
			$Salida .= "  <td width=\"30%\" align=\"left\" class=\"normal_10\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
			$Salida .= "  <td width=\"20%\" class=\"normal_10N\">DURACION</td>";
			$Salida .= "  <td width=\"30%\" class=\"normal_10\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
			$Salida .= "  </tr>";
			$Salida .= "  <tr>";
			$Salida .= "  <td width=\"20%\" class=\"normal_10N\">QUIROFANO</td>";
			$Salida .= "  <td width=\"30%\" align=\"left\" colspan=\"3\" class=\"normal_10\">".$datos['nom_quirofano']."</td>";
			$Salida .= "  </tr>";
			$Salida .= "		<tr>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">VIA ACCESO</td>";
			if($datos['via']){
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['via']."</td>";		
			}else{
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">SIN ASIGNAR</td>";		
			}			
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">TIPO CIRUGIA</td>";
			if($datos['tipo']){
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['tipo']."</td>";		
			}else{
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">SIN ASIGNAR</td>";			
			}
			$Salida.= "     </tr>";
			$Salida .= "		<tr>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">AMBITO CIRUGIA</td>";
			if($datos['ambito']){
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['ambito']."</td>";
			}else{
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">SIN ASIGNAR</td>";	
			}
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">FINALIDAD CIRUGIA</td>";
			if($datos['finalidad']){
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['finalidad']."</td>";
			}else{
				$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">SIN ASIGNAR</td>";
			}
			
			$Salida.= "     </tr>";	
			
// 			$datos=$this->ConsultaNotasOperatoriasRealizadas($this->datos['programacion'],$this->datos['tipoidpaciente'],$this->datos['paciente']);
			
			$id_pro = $this->IdProfesionalesCirujano($this->datos['programacion']);
			//print_r($id_pro);
      			$nombre_pro=$this->NombreProfesional($id_pro['cirujano_id']);
			
			$Salida .= "		<tr>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">CIRUJANO</td>";
			$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$nombre_pro."</td>";
			$Salida.= "     </tr>";	
			$Salida .= "		<tr>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">ANESTESIOLOGO</td>";
			$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['anestesiologo']."</td>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">AYUDANTE</td>";
			$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['ayudante']."</td>";
			$Salida.= "     </tr>";		
			$Salida .= "		<tr>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">INSTRUMENTADOR</td>";
			$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['instrumentador']."</td>";
			$Salida .= "		<td width=\"20%\" class=\"normal_10N\">CIRCULANTE</td>";
			$Salida .= "		<td width=\"30%\" align=\"left\" class=\"normal_10\">".$datos['circulante']."</td>";
			$Salida.= "     </tr>";		
			$Salida.= "  </table><BR>";	
			unset($_SESSION['Liquidacion_QX']['GASES']);
			$gases = $this->ConsultarGases($datos['programacion_id'],$datos['evolucion_id']);
					if($gases)
					{
						for($i=0;$i<sizeof($gases);$i++)
						{
							$_SESSION['Liquidacion_QX']['GASES'][$i]['TipoGasDes']=$this->consultartipogas($gases[$i][0]);           
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGas']=$gases[$i][1];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MetodoGasDes']=$this->consultartiposuministro($gases[$i][1]);  
							$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGas']=$gases[$i][2];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['FrecuenciaGasDes']=$gases[$i][4];
							$_SESSION['Liquidacion_QX']['GASES'][$i]['MinutosGas']=$gases[$i][3];
						
						}
					}
      
      			$Salida.="	 <table  align=\"center\" border=\"1\" width=\"95%\">";
			$Salida.="	 <tr class=\"modulo_table_title\">";
			$Salida.="   <td align=\"center\" colspan=\"4\">GASES UTILIZADOS</td>";
			$Salida.="	 </tr>";	
			$Salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$Salida.="  <td >TIPO GAS</td>";
			$Salida.="  <td >METODO SUMINISTRO</td>";
			$Salida.="  <td >FRECUENCIA SUMINISTRO(L/m)</td>";
			$Salida.="  <td >MINUTOS</td>";
			$Salida.="	</tr>";
			foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector){
          			$Salida.="<tr class=\"normal_10\">";
          			$Salida.="<td width=\"30%\">".$vector[TipoGasDes]."</td>";
          			$Salida.="<td width=\"30%\">".$vector[MetodoGasDes]."</td>";
          			$Salida.="<td width=\"20%\">".$vector[FrecuenciaGas]."/".$vector[FrecuenciaGasDes]."</td>";
		        	$Salida.="<td width=\"20%\">".$vector[MinutosGas]."</td>";          
          			$Salida.="</tr>";
			}
			$Salida.= "  </table><br>"; 
					
			$Salida.="	 <table  align=\"center\" border=\"1\" width=\"95%\">";
			$Salida.="	 <tr class=\"normal_10N\">";
			$Salida.="   <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS REALIZADOSss</td>";
			$Salida.="	 </tr>";	
			$Salida.="	 <tr class=\"normal_10N\">";			
			$Salida.="  <td width=\"20%\">CARGO</td>";
			$Salida.="  <td colspan=\"2\">DESCRIPCION</td>";			
			$Salida.="	</tr>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($this->datos['programacion'],$this->datos['tipoidpaciente'],$this->datos['paciente']);
			for($j=0;$j<sizeof($procedimientos);$j++){
				$rows='2';
				if($procedimientos[$j]['observaciones']){
					$rows+=1;
				}
				$diag =$this->Diagnosticos_ProcedimientosNO($procedimientos[$j]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']);
				if(is_array($diag)){
					$rows+=1;
				}
				$Salida.="<tr class=\"normal_10\">";								
				$Salida.="  <td align=\"center\" width=\"20%\" rowspan=\"$rows\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$Salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";															
				$Salida.="</tr>";	
				$Salida.="<tr class=\"normal_10\">";
				$Salida.="  <td class=\"normal_10N\" colspan = 1 align=\"left\" width=\"10%\">PROFESIONAL</td>";								
				$Salida.="  <td align=\"left\">".$nombre_pro."&nbsp;&nbsp;&nbsp;<label class=\"normal_10N\"></td>";																			
				$Salida.="</tr>";	
				if($procedimientos[$j]['observaciones']){
					$Salida.="<tr class=\"normal_10\">";
					$Salida.="  <td class=\"normal_10N\" colspan = 1 align=\"left\" width=\"10%\">Observacion</td>";
					$Salida.="  <td class=\"normal_10\" align=\"left\" width=\"64%\">".$procedimientos[$j]['observaciones']."</td>";
					$Salida.="</tr>";
				}
				if($diag){					
					$Salida.="<tr class=\"normal_10\">";
					$Salida.="	<td class=\"normal_10N\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
					$Salida.="	<td class=\"normal_10\">";
					$Salida.="<table border=\"1\" width=\"100%\">";
					$Salida.="<tr class=\"normal_10N\">";
					$Salida.="<td width=\"10%\">PRIMARIO</td>";
					$Salida.="<td width=\"10%\">TIPO DX</td>";
					$Salida.="<td width=\"8%\">CODIGO</td>";
					$Salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$Salida.="</tr>";					
					for($m=0;$m<sizeof($diag);$m++){							
						$Salida.="<tr class=\"normal_10\">";
						if($diag[$m]['sw_principal']=='1'){
							$Salida.="<td align=\"center\" width=\"10%\">SI</td>";
						}else{								
							$Salida.="<td align=\"center\" width=\"10%\">NO</td>";
						}
						if($diag[$m]['tipo_diagnostico'] == '1'){
							$Salida.="<td align=\"center\" width=\"10%\">ID</td>";
						}elseif($diag[$m]['tipo_diagnostico'] == '2'){
							$Salida.="<td align=\"center\" width=\"10%\">CN</td>";
						}else{
							$Salida.="<td align=\"center\" width=\"10%\">CR</td>";
						}
						$Salida.="<td class=\"normal_10\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
						$Salida.="<td class=\"normal_10\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";																					
						$Salida.="<tr>";										
					}
					$Salida.="</table>";
					$Salida.="</td></tr>";					
				}				
			}				
			$Salida.= "		</table><BR>";
			if($datos['diag_nom'] || $datos['diag_nom1'] || $datos['diag_nom2']){
				$Salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$Salida .= "  <tr class=\"normal_10N\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
				
				if($datos['diag_nom']){
					$Salida .= "  <tr>";
					$Salida .= "  <td width=\"15%\" class=\"normal_10N\">POST QX</td>";
					$Salida .= "  <td align=\"left\" class=\"normal_10\">".$datos['diag_nom']."</td>";				
					$Salida .= "  <td width=\"15%\" class=\"normal_10N\">TIPO</td>";
					if($datos['tipo_diagnostico_post_qx'] == '1'){
						$Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">ID</td>";
					}elseif($datos['tipo_diagnostico_post_qx'] == '2'){
						$Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CN</td>";
					}else{
						$Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CR</td>";
					}			
					$Salida .= "  </tr>";			
				}
				if($datos['diag_nom1']){	
					$Salida .= "  <tr>";
					$Salida .= "  <td width=\"15%\" class=\"normal_10N\">COMPLICACION</td>";
					$Salida .= "  <td align=\"left\" class=\"normal_10\">".$datos['diag_nom1']."</td>";				
					$Salida .= "  <td width=\"15%\" class=\"normal_10N\">TIPO</td>";
					if($datos['tipo_diagnostico_complicacion'] == '1'){
						$Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">ID</td>";
					}elseif($datos['tipo_diagnostico_complicacion'] == '2'){
						$Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CN</td>";
					}else{
						$Salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CR</td>";
					}			
					$Salida .= "  </tr>";
				}							
				$Salida.= "</table><BR>";			
			}
			
			$Tecnicas=$this->DescripcionTecnicaQX($datos['ingreso'],$datos['programacion_id']);
			if($Tecnicas){
				$Salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$Salida .= "  <tr class=\"normal_10N\"><td align=\"center\">DESCRIPCIONES TECNICAS QUIRURGICAS</td></tr>";				
				for($j=0;$j<sizeof($Tecnicas);$j++){
					$Salida .= "  <tr>";
					$Salida .= "  <td class=\"normal_10N\">".$Tecnicas[$j]['nombre_tercero']."</td>";						
					$Salida .= "  </tr>";
					$Salida .= "  <tr>";
					$Salida .= "  <td class=\"normal_10\">".$Tecnicas[$j]['descripcion']."</td>";						
					$Salida .= "  </tr>";
				}														
				$Salida.= "		</table><BR>";			
			}	
			
			$Hallazgos=$this->HallazgosQX($datos['ingreso'],$datos['programacion_id']);
			if($Hallazgos){
				$Salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$Salida .= "  <tr class=\"normal_10N\"><td align=\"center\">HALLAZGOS QUIRURGICOS</td></tr>";				
				for($j=0;$j<sizeof($Hallazgos);$j++){
					$Salida .= "  <tr>";
					$Salida .= "  <td class=\"normal_10N\">".$Hallazgos[$j]['nombre_tercero']."</td>";						
					$Salida .= "  </tr>";
					$Salida .= "  <tr>";
					$Salida .= "  <td class=\"normal_10\">".$Hallazgos[$j]['descripcion']."</td>";						
					$Salida .= "  </tr>";
				}														
				$Salida.= "		</table><BR>";			
			}	
			
			$materialesPatologicos=$this->RegistroPatologias($datos['ingreso'],$datos['programacion_id']);			
			if($materialesPatologicos){
				$Salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$Salida .= "  <tr class=\"normal_10N\"><td align=\"center\">MATERIALES PATOLOGICOS</td></tr>";				
				for($j=0;$j<sizeof($materialesPatologicos);$j++){
					if($materialesPatologicos[$j]['descripcion']){					
						$Salida .= "  <tr>";
						if($materialesPatologicos[$j]['envio_patologico']==1){
							$Salida .= "  <td class=\"normal_10N\">".$materialesPatologicos[$j]['nombre']." - MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
						}else{
							$Salida .= "  <td class=\"normal_10N\">".$materialesPatologicos[$j]['nombre']." - MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
						}
						$Salida .= "  </tr>";
						$Salida .= "  <tr>";					
						$Salida .= "  <td class=\"normal_10\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$materialesPatologicos[$j]['descripcion']."</td>";						
						$Salida .= "  </tr>";
					}
				}
				$Salida.= "		</table><BR>";							
			}	
				
			$cultivos=$this->RegistroCultivos($datos['ingreso'],$datos['programacion_id']);
			if($cultivos){
				$Salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$Salida .= "  <tr class=\"normal_10N\"><td align=\"center\">CULTIVOS</td></tr>";								
				for($j=0;$j<sizeof($cultivos);$j++){					
					if($cultivos[$j]['descripcion']){
						$Salida .= "  <tr>";
						if($cultivos[$j]['envio_cultivo']==1){
							$Salida .= "  <td class=\"normal_10N\">".$cultivos[$j]['nombre']." - CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
						}else{
							$Salida .= "  <td class=\"normal_10N\">".$cultivos[$j]['nombre']." - CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
						}					
						$Salida .= "  </tr>";
						$Salida .= "  <tr>";
						$Salida .= "  <td class=\"normal_10\"><label class=\"normal_10N\">DESCRIPCION DEL CULTIVO:</label><br>".$cultivos[$j]['descripcion']."</td>";						
						$Salida .= "  </tr>";
					}	
				}					
				$Salida.= "		</table><BR>";							
			}			
			
		}
		return $Salida;			
	}	      
//*****************************************fin de termino
 
	/************************************************************************************ 
		* Funcion que permite traer la informacion de la glosa y el detalle del acta de 
		* conciliacion (si la hay) de las factura pertenecientes a un cliente
		* 
		* @return array datos de las facturas
		*************************************************************************************/
		
		function IdProfesionalesCirujano($programacion){
		list($dbconn) = GetDBconn();
		//echo '<br><br><br><br><br><br>DATOS CIRUJANO: '.
		$query="SELECT tipo_id_cirujano, cirujano_id FROM hc_notas_operatorias_cirugias WHERE programacion_id = ".$programacion."";
			$result = $dbconn->Execute($query);
			if ($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo en la tabla hc_notas_operatorias_cirugias";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{
				if(!$result->EOF){
					$datosP=$result->GetRowAssoc($toUpper=false);
				}
			}		

					
		return $datosP;
	}
	
	function ProgramacionActivaPaciente(){
		list($dbconn) = GetDBconn();
		//--AND x.hc_nota_operatoria_cirugia_id IS NULL 
		
		//echo '<br><br><br><br><br><br>'.
		$query="SELECT a.programacion_id  
		FROM qx_programaciones a
		LEFT JOIN hc_notas_operatorias_cirugias x ON (x.programacion_id=a.programacion_id AND x.usuario_id='".UserGetUID()."')
		,qx_quirofanos_programacion b,estacion_enfermeria_qx_pacientes_ingresados c
		WHERE a.tipo_id_paciente='".$this->tipoidpaciente."' AND a.paciente_id='".$this->paciente."' AND a.estado IN ('1','2') 
		AND a.programacion_id=b.programacion_id 
		AND b.qx_tipo_reserva_quirofano_id='3' 
		AND a.programacion_id=c.programacion_id AND 
		c.sw_estado IN ('1','0');";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo en la tabla qx_cumplimientos";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			if($result->RecordCount() > 0){
				return $result->fields[0];
			}
		}
		return 0;	
	}
	function consultartipogas($id)
	{
		list($dbconn) = GetDBconn();
		//echo '<br><br><br><br><br><br>'.
		$sql="select descripcion from tipos_gases where tipo_gas_id='".$id."'";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		
			if($result->RecordCount()>0)
			{
				$result->Close();
				return $result->fields[0];
			}
		}
		
		return true;
	
	}
	function consultartiposuministro($id){
	
		list($dbconn) = GetDBconn();
		 //echo '<br><br><br><br><br><br>'.
		 $sql="select descripcion from tipos_metodos_suministro_gases where tipo_suministro_id='".$id."'";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		
			if($result->RecordCount()>0)
			{
				$result->Close();
				return $result->fields[0];
			}
		}
		
		return true;
	
	}
	
	function ConsultarGases($programacion,$evolucion)
	{
		list($dbconn) = GetDBconn();
		
		$sql = "select h.tipo_gas_id, h.tipo_suministro_id, h.frecuencia_id, h.tiempo_suministro, g.unidad, 
		h.evolucionid, h.ingresoid, h.hc_notaqx_gases_anestesicos_id
		from hc_notaqx_gases_anestesicos h, tipos_frecuencia_gases g
		where h.ingresoid = ".$this->datos['ingreso']." and programacion_id = ".$programacion." and h.frecuencia_id=g.frecuencia_id";
		$result = $dbconn->Execute($sql);
		if($result->EOF){
			//echo '<br><br><br><br>unset';
			unset($_SESSION['Liquidacion_QX']['GASES']);}
			
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
		
			if($result->RecordCount()>0)
			{
				$i=0;
				while (!$result->EOF) {
					$datos[$i][0] =$result->fields[0]; //tipo_gas_id
					$datos[$i][1] =$result->fields[1]; //tipo_suministro_id
					$datos[$i][2] =$result->fields[2]; //frecuencia_id
					$datos[$i][3] =$result->fields[3]; //tiempo_suministro
					//jab
					$datos[$i][4] =$result->fields[4]; //unidad
					$datos[$i][5] =$result->fields[5]; //evolucionid
					$datos[$i][6] =$result->fields[6]; //ingresoid
					//$datos[$i][7] =$result->fields[7]; //hc_notaqx_gases_anestesicos_id
			  		$result->MoveNext();
					$i++;
				}
				return $datos;
			}
			else{
				return false;
			}
		}
	
		return true;
	}
	
	function TiposGasesAnestesicos(){
	
		list($dbconn) = GetDBconn();
		//echo '<br><br><br><br><br><br>'.
		$query = "SELECT tipo_gas_id,descripcion
		FROM tipos_gases";
		$result = $dbconn->Execute($query);
		if ($dbconn->ErrorNo() != 0) {
		$this->error = "Error al Cargar el Modulo";
		$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
		return false;
		}else{
	if($result->RecordCount()){
			while(!$result->EOF){
			$vars[$result->fields[0]]=$result->fields[1];
			$result->MoveNext();
			}
		}
		}
		$result->Close();
		return $vars;
	}
	
	function NombreProfesional($id_profesional){
    		list($dbconn) = GetDBconn();

		$query = "SELECT  nombre 
		FROM profesionales
		WHERE tercero_id = $id_profesional";
		
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		$profe = $result->fields[0];
		//echo $profe;
		return $profe;
	}
		
		function ObtenerIngresoPaciente($ingreso,$tipoidpaciente,$paciente)
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
												IG.ingreso,
												TO_CHAR(IG.fecha_ingreso,'DD/MM/YYYY HH12:MI am') AS fecha_ingreso,
												CU.numerodecuenta,
												VI.via_ingreso_nombre,
												TC.nombre_tercero,
												PL.plan_descripcion,
												PL.tercero_id,
												PL.tipo_tercero_id,
												PR.nombre,
												SU.nombre AS responsable
								FROM		pacientes PC,
												vias_ingreso VI,
												tipos_id_pacientes TI,
												system_usuarios SU,
												cuentas CU,
												planes PL,
												terceros TC,
												ingresos IG LEFT JOIN pacientes_urgencias PU
												ON(	IG.ingreso = PU.ingreso) 
												LEFT JOIN	profesionales PR
												ON(	PR.tipo_id_tercero = PU.tipo_id_tercero AND
														PR.tercero_id = PU.tercero_id)
								WHERE		PC.tipo_id_paciente = TI.tipo_id_paciente
								AND			IG.paciente_id = PC.paciente_id
								AND			IG.tipo_id_paciente = PC.tipo_id_paciente
								AND			VI.via_ingreso_id = IG.via_ingreso_id
								AND			SU.usuario_id = IG.usuario_id
								AND			CU.ingreso = IG.ingreso
								AND			PL.plan_id = CU.plan_id
								AND			PC.tipo_id_paciente = '".$tipoidpaciente."'
								AND			PC.paciente_id = '".$paciente."' 
								AND			PL.tipo_tercero_id = TC.tipo_id_tercero 
								AND			PL.tercero_id = TC.tercero_id
								AND		 IG.ingreso = ".$ingreso." ";

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
		
    //AQUI TODOS LOS METODOS QUE USTED QUIERA
	function ConsultaNotasOperatoriasRealizadas($programacion,$tipoidpaciente,$paciente){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.hc_nota_operatoria_cirugia_id,a.quirofano_id,x.descripcion as nom_quirofano,a.hora_inicio,a.hora_fin,
		a.via_acceso,b.descripcion as via,a.tipo_cirugia,c.descripcion as tipo,
		a.ambito_cirugia,d.descripcion as ambito,a.finalidad_procedimiento_id,e.descripcion as finalidad,
		a.justificacion_norealizados,		
		a.diagnostico_post_qx,diag.diagnostico_nombre as diag_nom,a.tipo_diagnostico_post_qx,
		a.diagnostico_id_complicacion,diag1.diagnostico_nombre as diag_nom1,a.tipo_diagnostico_complicacion,
		ter.nombre_tercero as instrumentador,ter1.nombre_tercero as circulante,
		ter2.nombre_tercero as anestesiologo,ter3.nombre_tercero as ayudante,a.evolucion_id,evol.ingreso,a.programacion_id
		
		FROM hc_notas_operatorias_cirugias a
		LEFT JOIN qx_quirofanos x ON (a.quirofano_id=x.quirofano)
		LEFT JOIN qx_vias_acceso b ON (a.via_acceso=b.via_acceso)
		LEFT JOIN qx_tipos_cirugia c ON (a.tipo_cirugia=c.tipo_cirugia_id)
		LEFT JOIN qx_ambitos_cirugias d ON (a.ambito_cirugia=d.ambito_cirugia_id)
		LEFT JOIN qx_finalidades_procedimientos e ON (a.finalidad_procedimiento_id=e.finalidad_procedimiento_id)		
		LEFT JOIN diagnosticos diag ON (a.diagnostico_post_qx=diag.diagnostico_id)
		LEFT JOIN diagnosticos diag1 ON (a.diagnostico_id_complicacion=diag1.diagnostico_id)
		LEFT JOIN terceros ter ON (a.tipo_id_instrumentista=ter.tipo_id_tercero AND a.instrumentista_id=ter.tercero_id)
		LEFT JOIN terceros ter1 ON (a.tipo_id_circulante=ter1.tipo_id_tercero AND a.circulante_id=ter1.tercero_id)
		LEFT JOIN terceros ter2 ON (a.tipo_id_anestesiologo=ter2.tipo_id_tercero AND a.anestesiologo_id=ter2.tercero_id)
		LEFT JOIN terceros ter3 ON (a.tipo_id_ayudante=ter3.tipo_id_tercero AND a.ayudante_id=ter3.tercero_id),
		hc_evoluciones evol,ingresos ing
		WHERE a.programacion_id='".$programacion."' AND
		a.evolucion_id=evol.evolucion_id AND evol.ingreso=ing.ingreso AND ing.tipo_id_paciente='".$tipoidpaciente."' AND ing.paciente_id='".$paciente."'
		ORDER BY a.hc_nota_operatoria_cirugia_id DESC";
		//LEFT JOIN diagnosticos diag2 ON (a.diagnostico_pre_qx=diag2.diagnostico_id)
		//a.diagnostico_pre_qx,diag.diagnostico_nombre as diag_nom2,a.tipo_diagnostico_pre_qx,
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{			
			$vars=$result->GetRowAssoc($toUpper=false);		
		}			
		return $vars;	
	}
	
	function ProcedimientosNotaOperatoria($programacion,$tipoidpaciente,$paciente){
		list($dbconn) = GetDBconn();
		$query ="SELECT c.hc_nota_operatoria_cirugia_id,a.procedimiento_qx,b.descripcion,a.observaciones,ter.nombre_tercero as profesional,usuprof.tarjeta_profesional
		FROM hc_notas_operatorias_procedimientos a,cups b,hc_notas_operatorias_cirugias c,
		hc_evoluciones evol,ingresos ing,profesionales_usuarios prof,terceros ter,profesionales usuprof
		WHERE c.programacion_id='".$programacion."' AND 
		c.evolucion_id=evol.evolucion_id AND evol.ingreso=ing.ingreso AND 
		ing.tipo_id_paciente='".$tipoidpaciente."' AND ing.paciente_id='".$paciente."' AND
		c.hc_nota_operatoria_cirugia_id=a.hc_nota_operatoria_cirugia_id AND 
		a.procedimiento_qx=b.cargo AND c.usuario_id=prof.usuario_id AND 
		prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id AND
		usuprof.tipo_id_tercero=ter.tipo_id_tercero AND usuprof.tercero_id=ter.tercero_id
		AND a.realizado='1'";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function Diagnosticos_ProcedimientosNO($NotaId,$procedimiento_qx){
		list($dbconn) = GetDBconn();
		$query ="SELECT a.diagnostico_id,b.diagnostico_nombre,a.tipo_diagnostico,
		a.sw_principal
		FROM hc_notas_operatorias_procedimientos_diags a,diagnosticos b
		WHERE a.hc_nota_operatoria_cirugia_id=".$NotaId." AND 
		a.procedimiento_qx=".$procedimiento_qx." AND a.diagnostico_id=b.diagnostico_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;		
	}
	
	function DescripcionTecnicaQX($ingreso,$programacion){
  //$VALOR=$this->ProgramacionActivaPaciente();
		list($dbconn) = GetDBconn();
		$query ="SELECT descripcion,nombre_tercero
		FROM hc_descripcion_cirugia a,profesionales_usuarios prof,terceros ter
		WHERE a.ingreso='".$ingreso."' 
    AND a.programacion_id = ".$programacion."
		AND a.usuario_id=prof.usuario_id AND 
		prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{		
			while(!$result->EOF){ 
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}	
	
	function HallazgosQX($ingreso,$programacion){
  //$VALOR=$this->ProgramacionActivaPaciente();
		list($dbconn) = GetDBconn();
		 $query ="SELECT descripcion,nombre_tercero
		FROM 	hc_hallazgos_quirurgicos a,profesionales_usuarios prof,terceros ter
		WHERE a.ingreso='".$ingreso."' 
    AND a.programacion_id = ".$programacion."
		AND a.usuario_id=prof.usuario_id AND 
		prof.tipo_tercero_id=ter.tipo_id_tercero AND prof.tercero_id=ter.tercero_id";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{		
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function RegistroPatologias($ingreso,$programacion){
  //$VALOR=$this->ProgramacionActivaPaciente();
		list($dbconn) = GetDBconn();
		$query ="SELECT A.patologia_id,
               		 A.fecha_registro, A.descripcion, B.nombre, B.usuario,A.envio_patologico
				FROM hc_patologia_quirurgicos AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$ingreso."'
        AND A.programacion_id = ".$programacion."
				AND B.usuario_id=A.usuario_id
				ORDER BY fecha_registro DESC
				";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{		
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}
	
	function RegistroCultivos($ingreso,$programacion){
  //$VALOR=$this->ProgramacionActivaPaciente();
		list($dbconn) = GetDBconn();
		$query= "SELECT A.cultivos_id,
               		 A.fecha_registro, A.descripcion, B.nombre, B.usuario,A.envio_cultivo
				FROM hc_cultivos_quirurgicos AS A,
					system_usuarios AS B
				WHERE A.ingreso='".$ingreso."'
        AND A.programacion_id = ".$programacion."
				AND B.usuario_id=A.usuario_id
				ORDER BY fecha_registro DESC";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0){
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{		
			while(!$result->EOF){
				$vars[]=$result->GetRowAssoc($toUpper=false);
				$result->MoveNext();
			}
		}			
		return $vars;	
	}	

  

    //---------------------------------------
}

?>
