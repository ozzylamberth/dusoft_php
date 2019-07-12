<?php

/**
 * $Id: reporteNotaAdministrativa_html.report.php,v 1.1 2007/10/03 23:16:13 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class reporteNotaAdministrativa_html_report
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
    function reporteNotaAdministrativa_html_report($datos=array())
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
	function CrearReporte(){
	
	//*******************************************termino	
		$Datos = $this->ObtenerDatosPaciente($this->datos['tipoIdPaciente'],$this->datos['PacienteId']);
		
		$EdadArr=CalcularEdad($Datos[0]['fecha_nacimiento'],$FechaFin);	
		
		$Salida .= "	<table width=\"80%\" align=\"center\" border=\"0\">\n";
		$Salida .= "		<tr>\n";
		$Salida .= "			<td align=\"center\" width=\"25%\" height=\"30\"><b>NOTA ADMINISTRATIVA - INASISTENCIAS</b></td>\n";		
		$Salida .= "		</tr>\n";
		$Salida .= "	</table><br>";
		
		$Salida .= "	<table width=\"75%\" align=\"center\" border=\"1\">\n";		
		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" align=\"center\" width=\"25%\" colspan=\"6\"><b>DATOS PACIENTE</b></td>\n";		
		$Salida .= "		</tr>\n";		
/*		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\" ><b>Nº INGRESO</b></td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['ingreso']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\" width=\"25%\" ><b>FECHA INGRESO</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['fecha_ingreso']."</td>\n";
		$Salida .= "		</tr>\n";*/
	
// 		$Salida .= "		<tr>\n";
// 		$Salida .= "			<td class=\"normal_10N\">Nº CUENTA</td>\n";
// 		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['numerodecuenta']."</td>\n";
// 		$Salida .= "			<td colspan=\"2\">&nbsp;</td>\n";
// 		$Salida .= "		</tr>\n";

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">PACIENTE</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['tipo_id_paciente']." ".$Datos[0]['paciente_id']."</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$Datos[0]['nombres']." ".$Datos[0]['apellidos']."</td>\n";
		$Salida .= "			<td class=\"normal_10N\">EDAD</td>\n";
		$Salida .= "			<td class=\"normal_10\">".$EdadArr['edad_aprox']."</td>\n";
		$Salida .= "		</tr>\n";
		

		$Salida .= "		<tr>\n";
		$Salida .= "			<td class=\"normal_10N\">DIRECCION</td>\n";
		$Salida .= "			<td class=\"normal_10\" colspan=\"2\">".$Datos[0]['residencia_direccion']."&nbsp;</td>\n";
		$Salida .= "			<td class=\"normal_10N\">TELÉFONO</td>\n";
		$Salida .= "			<td class=\"normal_10\" colspan=\"2\">".$Datos[0]['residencia_telefono']."&nbsp;</td>\n";
		$Salida .= "		</tr>\n";

		
// 		$Salida .= "		<tr>\n";
// 		$Salida .= "			<td class=\"normal_10N\">PLAN</td>\n";
// 		$Salida .= "			<td class=\"normal_10\"colspan=\"3\">".$Datos[0]['plan_descripcion']."</td>\n";
// 		$Salida .= "		</tr>\n";


		$Salida .= "	</table><br>\n";
					
		if(is_array($Datos))
			{
				for($i=0; $i < sizeof($Datos); $i++)
				{
					$Profesional = $Datos[$i][nombre_tercero];
					$fecha_cita = explode(' ',$Datos[$i][fecha_cita]);
					$hora_cita = $Datos[$i][hora_cita];
					$FechaNota = $this->FechaStamp($Datos[$i][fecha_registro]);
					$HoraNota = $this->HoraStamp($Datos[$i][fecha_registro]);
					$observaciones = $Datos[$i][observaciones];
					
					$Salida .= "  <table border=\"1\" width=\"75%\" align=\"center\">";
					$Salida .= "  <tr class=\"normal_10N\"><td colspan=\"4\" align=\"center\">DATOS DE LA NOTA&nbsp;&nbsp;&nbsp;".$FechaNota."&nbsp;&nbsp;-&nbsp;&nbsp;".$HoraNota."</td></tr>";
					$Salida .= "  <tr>";			
					$Salida .= "  <td width=\"20%\" class=\"normal_10N\">FECHA CITA</td>";
					$Salida .= "  <td width=\"30%\" align=\"left\" class=\"normal_10\">".$fecha_cita[0]." - ".$hora_cita."</td>";
/*					$Salida .= "  <td width=\"20%\" class=\"normal_10N\">FECHA NOTA</td>";
					$Salida .= "  <td width=\"30%\" class=\"normal_10\">".$fechaNota."&nbsp;&nbsp;&nbsp;".$HoraNota."</td>";*/
					$Salida .= "  </tr>";
					$Salida .= "  <tr>";
					$Salida .= "   <td width=\"20%\" class=\"normal_10N\">PROFESIONAL</td>";
					$Salida .= "   <td width=\"80%\" align=\"left\" class=\"normal_10\" colspan = \"3\">".$Profesional."</td>";
					$Salida .= "  </tr>";
					$Salida .= "	<tr>";
					$Salida .= "		<td width=\"100%\" class=\"normal_10N\" colspan=\"4\">OBSERVACIONES</td>";
					$Salida .= "	</tr>";
					$Salida .= "	<tr>";
					$Salida .= "		<td width=\"100%\" align=\"left\" class=\"normal_10\" colspan=\"4\">&nbsp;&nbsp;&nbsp;&nbsp;".$observaciones."</td>";
					$Salida .= "  </tr>";
					$Salida .= "  </table><BR>";
				}
				$this->User = $this->GetDatosUsuarioSistema(UserGetUID());          
				$fechita = date("d-m-Y H:i:s");
				$FechaImprime = $this->FechaStamp($fechita);
				$HoraImprime = $this->HoraStamp($fechita);
				
				$Salida .="<br><TABLE WIDTH=\"75%\" ALIGN=\"center\">";
				$Salida .="<TR>";
				$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Imprimió:&nbsp;".$this->User[0]['nombre']." - ".$this->User[0]['usuario']."</FONT></td>\n";
				$Salida .="<td ALIGN=\"RIGHT\" WIDTH=\"50%\"><FONT SIZE='1' FACE='arial'>Fecha Impresión :&nbsp;&nbsp;".$FechaImprime." - ".$HoraImprime."</FONT></td>\n";
				$Salida .="</TR>";
				$Salida .="</table>";
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
		function ObtenerDatosPaciente($tipoidpaciente,$paciente)
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
												TC.nombre_tercero,
												TC.tipo_id_tercero,
												TC.tercero_id,
												--PR.nombre,
												--SU.nombre AS responsable,
												NACE.*
								FROM		pacientes PC,
												tipos_id_pacientes TI,
												--system_usuarios SU,
												
												--profesionales PR,
													notas_administrativas_consulta_externa NACE
													LEFT JOIN terceros TC ON (NACE.tipo_id_tercero = TC.tipo_id_tercero
																								AND NACE.tercero_id = TC.tercero_id )
								WHERE		PC.tipo_id_paciente = TI.tipo_id_paciente
								AND			PC.tipo_id_paciente = NACE.tipo_id_paciente
								AND			PC.paciente_id = NACE.paciente_id
								AND 		NACE.tipo_id_paciente = '".$tipoidpaciente."'
								AND 		NACE.paciente_id = '".$paciente."' ;";

			$result = $dbconn->Execute($query);
			if($dbconn->ErrorNo() != 0){
				$this->error = "Error al Cargar el Modulo";
				$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
				return false;
			}else{		
				while(!$result->EOF)
				{
					$Dat[] = $result->GetRowAssoc($ToUpper = false);
					$result->MoveNext();
				}
				$result->Close();
			}	
			return $Dat;
		}
		
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
    //---------------------------------------
}

?>
