<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: FormatoFarmacov.report.php,v 1.5 2010/01/02  
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */
  /**
  * Clase Reporte: FormatoFarmacov_report
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */

	class FormatoFarmacov_report 
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
		function FormatoFarmacov_report($datos=array())
		{
			$this->datos=$datos;
		
			return true;
		}
		
		function GetMembrete()
		{
		    
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= " <b $estilo><center>FORMATO DE REPORTE DE SOSPECHA DE REACCION ADVERSA A MEDICAMENTOS</center></b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'subtitulo'=>' ',
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			//print_r($Membrete);
			return $Membrete;
		}

		//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
		function CrearReporte()
		{
			IncludeClass('ConexionBD');
			IncludeClass('Formulacion_ExternaESMSQL', '', 'app', 'Formulacion_ExternaESM');
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$ods = new Formulacion_ExternaESMSQL();
      
			$datos_farma = $ods->Consultar_cabe_Faramaco($this->datos['esm_farmaco_id']);
			$datos_farma_d = $ods->Farmaco_v_d_consulta($this->datos['esm_farmaco_id']);
			
			$usuario_id = $ods->Consultar_cabe_Faramaco_Usuario($this->datos['esm_farmaco_id']);
			$usuarios = $ods->Verificar_Usuario_Profesional($usuario_id[0]['usuario_id']);
		
		  if(empty($usuarios))
         {
			  $usuarios_n = $ods->Consultar_Usuario_NO_Profesional($usuario_id[0]['usuario_id']);
         }		 
		$estilo2  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";
        
    $Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
    $Salida .= "  <tr align=\"left\" >\n";
    $Salida .= "      <td  class=\"formulacion_table_list\"> <b>1</b></td>\n";
		$Salida .= "      <td>Reporte ante la más mínima sospecha que el medicamento pueda ser un factor contribuyente según el cuadro clínico del paciente.</td>";
    $Salida .= "  </tr>\n";
    $Salida .= "  <tr align=\"left\" >\n";
    $Salida .= "      <td  class=\"formulacion_table_list\"> <b>2</b></td>\n";
		$Salida .= "      <td>La información contenida en este reporte es información epidemiológica, por lo tanto tiene carácter confidencial y se utilizará únicamente con fines sanitarios. El Ministerio
                          de la Protección Social y el INVIMA son las únicas instituciones competentes para su divulgación. (Ley 9 de 1979)
                          Se considera que el reporte se encuentra completo y es util si contiene la siguiente información: DATOS DEL PACIENTE, MEDICAMENTOS SOSPECHOSOS Y
                          OTROS, DESCRIPCIÓN DE LA (S) SOSPECHA(S) DE REACCIÓN (ES) ADVERSA (S).</td>";
    $Salida .= "  </tr>\n";
    $Salida .= "</table><BR>";
    
    $Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
    $Salida .= "  <tr align=\"center\" >\n";
    $Salida .= "      <td  class=\"formulacion_table_list\" > <b>NUMERO DE DOCUMENTO: <u>#".$datos_farma[0]['esm_farmaco_id']."</u></b> </td>\n";
		$Salida .= "  </tr>\n";
        
		$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
		$Salida .= "  <tr align=\"center\" >\n";
		$Salida .= "      <td  class=\"formulacion_table_list\" colspan=\"13\" > <b>IDENTIFICACION</b> </td>\n";
		$Salida .= "  </tr>\n";
		
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td width=\"10%\" ><b>FECHA DE NOTIFICACION:</b></td>\n";
		$Salida .= "			<td width=\"15%\"><b>".$datos_farma[0]['fecha_notificacion']."</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>INSTITUCION:</b></td>\n";
		$Salida .= "			<td colspan=\"3\"><b>".$datos_farma[0]['esm_tipo_id_tercero']." - ".$datos_farma[0]['esm_tercero_id']."  ".$datos_farma[0]['nombre_tercero']."</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td width=\"10%\" ><b>ORIGEN</b></td>\n";
		$Salida .= "			<td colspan=\"6\"><b>".$datos_farma[0]['departamento']." - ".$datos_farma[0]['municipio']."</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "</table><BR>";
		
		$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td width=\"5%\" ><b>IDENTIFICACIÒN</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>".$datos_farma[0]['tipo_id_paciente']." -".$datos_farma[0]['paciente_id']."</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>PACIENTE</b></td>\n";
		$Salida .= "			<td colspan=\"4\"><b>".$datos_farma[0]['apellidos']." ".$datos_farma[0]['nombres']."&nbsp</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td width=\"10%\" ><b>FECHA NACIMIENTO</b></td>\n";
		$Salida .= "			<td colspan=\"2\"><b>".$datos_farma[0]['fecha_nacimiento']."</b></td>\n";
		$Salida .= "			<td width=\"5%\" ><b>SEXO</b></td>\n";
		$Salida .= "			<td colspan=\"2\"><b>".$datos_farma[0]['sexo_id']."</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "</table><br>";
		
		$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td width=\"5%\" ><b>NO FORMULA</b></td>\n";
		$Salida .= "			<td colspan=\"3\" ><b>".$datos_farma[0]['formula_papel']."</b></td>\n";
		$Salida .= "			<td  colspan=\"1\" ><b>FECHA INICIAL DE RAMs SOSPECHAS</b></td>\n";
		$Salida .= "			<td  colspan=\"3\"><b>".$datos_farma[0]['fecha_sospecha']." </b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "</table><br>\n";
		
		$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td colspan=\"1\"  ><b>REACCION(ES) ADVERSAS A MEDICAMENTOS(RAMs) SOSPECHADA (S)</b></td>\n";
		$Salida .= "			<td colspan=\"4\" ><b>".$datos_farma[0]['reaccion_adversa']."</b></td>\n";
		$Salida .= "		</tr>\n";
		$Salida .= "</table><br>\n";
			
		$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
		$Salida .= "  <tr align=\"center\" >\n";
		$Salida .= "      <td  class=\"formulacion_table_list\" colspan=\"13\" > <b>MEDICAMENTOS</b> </td>\n";
		$Salida .= "  </tr>\n";
		$Salida .= "		<tr $estilo2 height=\"21\">\n";
		$Salida .= "			<td width=\"10%\" ><b>CODIGO:</b></td>\n";
		$Salida .= "			<td colspan=\"2\" ><b>MEDICAMENTOS:</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>CANTIDAD</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>FECHA_VENC</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>LOTE</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>INDICACION O MOTIVO</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>FECHA INC</b></td>\n";
		$Salida .= "			<td width=\"10%\" ><b>FECHA FIN</b></td>\n";
		$Salida .= "		</tr>\n";
		foreach($datos_farma_d as $key => $detalle)
		{
				$Salida .= "		<tr $estilo2 height=\"21\">\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['codigo_producto_mini']."</b></td>\n";
				$Salida .= "			<td colspan=\"2\" ><b>".$detalle['producto']."</b></td>\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['frecuencia']."</b></td>\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['fecha_vencimiento']."</b></td>\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['lote']."</b></td>\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['indicacion_motivo']."</b></td>\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['fecha_inicio']."</b></td>\n";
				$Salida .= "			<td width=\"10%\" ><b>".$detalle['fecha_finalizacion']."</b></td>\n";
				$Salida .= "		</tr>\n";

		}
					
			$Salida .= "</table><BR>\n";
			$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"45%\" ><b>OTROS DIAGNOSTICOS </b></td>\n";
			$Salida .= "			<td colspan=\"4\" ><b>".$datos_farma[0]['diagnostico']."</b></td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "</table><br>\n";
			
			
			$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"45%\" ><b>OBSERVACIONES </b></td>\n";
			$Salida .= "			<td colspan=\"4\" ><b>".$datos_farma[0]['observacion']."</b></td>\n";
			$Salida .= "		</tr>\n";
			$Salida .= "</table><br>\n";


     $usuarios = $ods->Verificar_Usuario_Profesional($usuario_id[0]['usuario_id']);
		  
		  if(empty($usuarios))
         {

			  $usuarios_n = $ods->Consultar_Usuario_NO_Profesional($usuario_id[0]['usuario_id']);

  }		
      if(!empty($usuarios))
	  {
			$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "         <td width='20%' align=\"right\" $ESTILO20>";
			$Salida .= "           REPORTANTE:";
			$Salida .= "       ".$usuarios[0]['nombre']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "         <td width='15%' align=\"right\" $ESTILO20>";
			$Salida .= "           PROFESION :";
			$Salida .= "       ".$usuarios[0]['descripcion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "         <td width='25%' align=\"right\" $ESTILO20>";
			$Salida .= "           DIRECCION :";
			$Salida .= "       ".$usuarios[0]['direccion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "         <td width='15%' align=\"right\" $ESTILO20>";
			$Salida .= "           TELEFONO :";
			$Salida .= "       ".$usuarios[0]['telefono']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "    </table><br>\n";
		}else
		{
		
			$Salida .= "<table  width=\"100%\" align=\"center\" border=\"1\"  rules=\"all\" class=\"modulo_table_list\" >\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "         <td width='25%' align=\"right\" $ESTILO20>";
			$Salida .= "           REPORTANTE :";
			$Salida .= "       ".$usuarios_n[0]['nombre']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "         <td width='15%' align=\"right\" $ESTILO20>";
			$Salida .= "           PROFESION :";
			$Salida .= "       ".$usuarios_n[0]['descripcion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "         <td width='20%' align=\"right\" $ESTILO20>";
			$Salida .= "           DIRECCION :";
			$Salida .= "       ".$usuarios_n[0]['direccion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "         <td width='15%' align=\"right\" $ESTILO20>";
			$Salida .= "           TELEFONO :";
			$Salida .= "       ".$usuarios_n[0]['telefono']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "    </table><br>\n";
		
		}
		
		
            return $Salida;
		}
		
	}
?>