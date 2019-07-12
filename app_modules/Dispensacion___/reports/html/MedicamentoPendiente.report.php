<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MedicamentoPendiente.report.php,v 1.5 2010/07/08  
  * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */
  /**
  * Clase Reporte: MedicamentoPendiente_report
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.0
  * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */

	class MedicamentoPendiente_report 
	{ 
		var $datos;
	
		var $title       = '';
		var $author      = '';
		var $sizepage    = 'leter';
		var $Orientation = '';
		var $grayScale   = false;
		var $headers     = array();
		var $footers     = array();
		
	   /*Constructor de la clase- Metodo Privado No Modificar*/
		function MedicamentoPendiente_report($datos=array())
		{
			$this->datos=$datos;
	
			return true;
		}
		
		function GetMembrete()
		{
		  
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:14px\"";
			$titulo .= " <b $estilo>MEDICAMENTO(S) PENDIENTE(S)</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'logo'=>'logocliente.png',
							  'align'=>'left'));
			return $Membrete;
		}

		function CrearReporte()
		{
			IncludeClass('ConexionBD');
			IncludeClass('DispensacionSQL','','app','Dispensacion');
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$ods = new DispensacionSQL();
			
      $paciente = $ods->DatosPaciente($this->datos['tipo_id_paciente'],$this->datos['paciente_id']);
      $pendiente = $ods->ConsultarInformacionPediente($this->datos['paciente_id'],$this->datos['tipo_id_paciente'],$this->datos['evolucion']);
    
      $nombre=$ods->GetNombreUsuarioImprime();

			
			$sty = " style=\"text-align:left;text-indent:8pt\" ";
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\"";
			$estilo2 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px; text-indent:6pt\""; 
			$estilo3 = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:10px\""; 
		
			$Salida.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
			$Salida.="<tr>\n";
			$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">FARMACIA:&nbsp;".$this->datos[empresa]['descripcion1']." -".$this->datos[empresa]['descripcion2']."</FONT></td>\n";
			$Salida.="</tr>\n";
		
			$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">IDENTIFICACION:&nbsp;".$paciente[0]['tipo_id_paciente']." ".$paciente[0]['paciente_id']."</FONT></td>\n";
			$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"30%\">NOMBRE:&nbsp;".strtoupper($paciente[0]['primer_apellido'])." ".strtoupper($paciente[0]['segundo_apellido'])." ".strtoupper($paciente[0]['primer_nombre'])." ".strtoupper($paciente[0]['segundo_nombre'])."</FONT></td>\n";
			$Salida.="</tr>\n";
			
			$Salida.="<tr>\n";
			$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\" class=\"Normal_10N\">SEXO:&nbsp;";
			$Salida.= $paciente[0]['sexo_id'];
			$Salida.="</td>\n";
			$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\" class=\"Normal_10N\">EDAD:&nbsp;";
			$Salida.= $paciente[0]['edad'] ;
			$Salida.=" &nbsp; AÑOS </td>\n";
			$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\" class=\"Normal_10N\">DIRECCION:&nbsp;";
			$Salida.= $paciente[0]['residencia_direccion'];
			$Salida.="</td>\n";
			$Salida.="</tr>\n";
			
			$Salida.="<tr>\n";
			$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\" class=\"Normal_10N\">TELEFONO:&nbsp;";
			$Salida.= $paciente[0]['residencia_telefono'];
			$Salida.="</td>\n";
			$Salida.="</tr>\n";
			$Salida.="</table>\n";
				
			
			$Salida .= "	<table width=\"60%\" align=\"center\" cellpading=\"0\" cellspacing=\"0\" border=\"1\" bordercolor=\"#000000\">\n";
			$Salida .= "		<tr $estilo2 height=\"21\">\n";
			$Salida .= "			<td width=\"25%\" ><b>CODIGO</b></td>\n";
			$Salida .= "			<td width=\"55%\" ><b>MEDICAMENTO(S) PENDIENTE(S)</b></td>\n";
			$Salida .= "			<td ><b>CANTIDAD</b></td>\n";
			$Salida .= "		</tr>\n";
		
			foreach($pendiente as $key => $dtl)
			{
			    	$Salida .= "		<tr $estilo2 height=\"21\">\n";
					$Salida .= "			<td >".$dtl['codigo_medicamento']."</td>\n";
					$Salida .= "			<td >".$dtl['nombre_medicamento']."  ".$dtl['contenido_unidad_venta']."  ".$dtl['unidad']."</td>\n";
					$Salida .= "			<td >".$dtl['cantidad_acomulada']." ".$dtl['unidad']."</td>\n";
					$Salida .= "		</tr>\n";
			    
			}
      $Salida .= "    </table><BR>\n";
      $Salida .= "             <table align=\"center\"  width=\"35%\">\n";
      $Salida .= "             <tr class=\"label\"  valign=\"bottom\" >\n";
      $Salida .= "                <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
      $Salida .= "              </tr>\n";        
      $Salida .= "               <tr class=\"label\" >\n";
      $Salida .= "                <td align=\"LEFT\">FIRMA PACIENTE</td>\n";
      $Salida .= "               </tr>\n";
      $Salida .= "	</table>\n";
			$Salida .= "   <table align='right' border='0' width='95%'>";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" $ESTILO20>";
			$Salida .= "           USUARIO :";
			$Salida .= "       ".$nombre[0]['nombre']."&nbsp;";
			$Salida .= "      - ".$nombre[0]['descripcion']."&nbsp;";
			$Salida .= "      </td>\n";
			$Salida .= "       </tr>\n";
			$Salida .= "       <tr align='right'>\n";
			$Salida .= "         <td width='50%' align=\"right\" $ESTILO20>";
			$Salida .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
			$Salida .= "     </td>\n";
			$Salida .= "     </tr>\n";
			$Salida .= "    </table>\n";
			return $Salida;
		}
		
	}
?>