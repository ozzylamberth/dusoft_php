<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MedicamentoPendienteFormulacionExterna.report.php,v 1.5 2010/07/08  
  * @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */
  /**
  * Clase Reporte: MedicamentoPendienteFormulacionExterna_report
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.0
  * @copyright (C) 2010  IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja T.
  */

	class MedicamentoPendienteFormulacionExterna_report 
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
		function MedicamentoPendienteFormulacionExterna_report($datos=array())
		{
			$this->datos=$datos;
	
			return true;
		}
		
		function GetMembrete()
		{
		  
			$estilo  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:5px\"";
			//$titulo .= " <b $estilo>MEDICAMENTOS ENTREGADOS Y/O PENDIENTES</b>";
			
			$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>$titulo,
							  'logo'=>'',
							  'align'=>'left'));
			return $Membrete;
		}

		function CrearReporte()
		{
			IncludeClass('ConexionBD');
			IncludeClass('DispensacionESMSQL','','app','DispensacionESM');
			$style  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:9px\"";
			$ods = new DispensacionESMSQL();
			
			$Cabecera_Formulacion=$ods->Consulta_Formulacion_Real_I($this->datos['formula_id']);
			$Datos_Ad=$ods->Dato_Adionales_afiliacion($Cabecera_Formulacion);
      $Dx=$ods->Diagnostico_Real($this->datos['formula_id']);
			$pendientes=$ods->Medicamentos_Pendientes_Esm($this->datos['formula_id']);
			
			
			
			$html .= "		<BR>	<fieldset class=\"fieldset\">\n";
		    $html .= "				<legend class=\"normal_10AN\">ENTREGA DE MEDICAMENTOS</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			
			$html .= "								<tr >\n";
			
			$html .= "									<td  align=\"left\" ><U>FECHA DE REGISTRO:</U></td>\n";
			$html .= "									<td  align=\"left\">".$Cabecera_Formulacion['fecha_registro']."\n";
			$html .= "									</td>\n";
				
			$html .= "									<td  align=\"left\"><U>FECHA DE FORMULA:</U></td>\n";
			$html .= "									<td align=\"left\">".$Cabecera_Formulacion['fecha_formula']."\n";
			$html .= "									</td>\n";
		
		
			$html .= "									<td align=\"left\" ><U>FORMULA No:<U></td>\n";
			$html .= "									<td  align=\"left\">".$Cabecera_Formulacion['formula_papel']."\n";
			$html .= "									</td>\n";

			
			$html .= "									<td align=\"left\"  ><U>HORA:</U></td>\n";
			$html .= "									<td   align=\"left\" >".$Cabecera_Formulacion['hora_formula']."\n";
			$html .= "									</td> \n";
								
			$html .= "								</tr>\n";
	
			$html .= "								<tr >\n";
			$html .= "									<td align=\"left\"><U>PROFESIONAL:</U></td>\n";
			$html .= "									<td  colspan=\"2\" align=\"left\">".$Cabecera_Formulacion['tipo_id_tercero']."".$Cabecera_Formulacion['tercero_id']." &nbsp;&nbsp; ".$Cabecera_Formulacion['profesional']."&nbsp;&nbsp;-".$Cabecera_Formulacion['descripcion_profesional']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
    
      $html .= "								<tr>\n";
			$html .= "									<td  align=\"left\"><U>IDENTIFICACION:</U></td>\n";
			$html .= "									<td  align=\"left\" >\n";
			$html .= "										".$Cabecera_Formulacion['tipo_id_paciente']."  ".$Cabecera_Formulacion['paciente_id']."\n";
			$html .= "									</td>\n";
			$html .= "									<td  colspan=\"4\" align=\"left\"   ><U>NOMBRE COMPLETO:</U>\n";
			$html .= "									 ".$Cabecera_Formulacion['nombre_paciente']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			if($Cabecera_Formulacion['sexo_id']=='M')
			{
			 $sexo='MASCULINO';
			
			}else
			{
			$sexo='FEMENINO';
			
			}
			list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);
						
			if($anio!=0)
			{
			
			 $edad_t='AÑOS';
			 $edad=$anio;
			}
			if($anio==0 and $mes!=0)
			{
			  $edad_t='MES';
			   $edad=$mes;
			}
			else
			{
		        if($anio==0 and $mes==0)
				{
				$edad_t='DIAS';
				    $edad=$dias;
				}
			
			}	
			
			$html .= "								<tr>\n";
			$html .= "									<td  align=\"left\"><U>EDAD:</U></td>\n";
			$html .= "									<td  align=\"left\" >".$edad." &nbsp; $edad_t \n";
			$html .= "									</td>\n";
			$html .= "									<td  colspan=\"2\" align=\"left\"><U>SEXO:</U>\n";
			$html .= "									".$sexo."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
		
			$html .= "								<tr>\n";
			$html .= "									<td  align=\"left\"><U>TIPO PLAN:</U></td>\n";
			$html .= "									<td  align=\"left\" >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td  colspan=\"5\" align=\"left\"><U>TIPO VINCULACION:</U>\n";
			$html .= "									".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
		
			$html .= "							</table>\n";
		 
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";

			$html .= "						</td>\n";
			$html .= "					</tr>\n";

			$html .= "				</table>\n";
			
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			
		
			$html .= "				   <tr >\n";
			$html .= "									<td   ><U>CODIGO:</U></td>\n";
			$html .= "									<td colspan=\"6\"  ><U>DIAGNOSTICO:</U></td>\n";
			$html .= "					</tr>\n";
			
			foreach($Dx as $item=>$fil)
			{
			   
				   
			
				  
					$html .= "								<tr >\n";
					$html .= "									<td   >".$fil['diagnostico_id']."</td>\n";
					$html .= "									<td colspan=\"6\"  >".$fil['diagnostico_nombre']."</td>\n";
					$html .= "									</td>\n";
					$html .= "								</tr>\n";
			}
			
			
		
     		
				
			$html .= "							</table>\n";
		 
			$html .= "						</td>\n";
			$html .= "					</tr>\n";

			$html .= "				</table>\n";
			
			
			
			$html .= "			</fieldset>\n";
		
			
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS PENDIENTES</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			
			$html .= "									<td  ><U>CODIGO</U></td>\n";
			$html .= "									<td colspan=\"6\"  ><U>MEDICAMENTO</U></td>\n";
			$html .= "									<td ><U>CANTIDAD</U></td>\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			
			foreach($pendientes as $item=>$fila)
			{
				$html .= "								<tr >\n";
				$html .= "									<td   >".$fila['codigo_medicamento']."</td>\n";
				$html .= "									<td colspan=\"6\">".$fila['descripcion_prod']."</td>\n";
				$html .= "									<td  >".round($fila['total'])."</td>\n";
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
			}
			
			
     		$html .= "							</table>\n";
	
			$html .= "						<td>\n";
			$html .= "					</tr>\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";

			$html .= "						</td>\n";
			$html .= "					</tr>\n";

			$html .= "				</table>\n";
			$html .= "			</fieldset>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>\n";
						
 
  	   $html .= "            <table width=\"100%\" class=\"label\" $style>\n";
			$html .= "             <tr class=\"label\"  valign=\"bottom\" >\n";
			$html .= "                <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
			$html .= "              </tr>\n";        
			$html .= "               <tr class=\"label\" >\n";
			$html .= "                <td align=\"LEFT\">FIRMA PACIENTE</td>\n";
			$html .= "               </tr>\n";
			$html .= "       <tr align='right'>\n";
			$html .= "         <td align=\"right\" $style>";
			$html .= "           USUARIO  DIGITALIZA:";
			$html .= "       ".$Cabecera_Formulacion['nombre']."&nbsp;";
			$html .= "      - ".$Cabecera_Formulacion['descripcion']."&nbsp;";
			$html .= "      </td>\n";
		
		
			$html .= "         <td width='50%' align=\"right\" $style>";
			$html .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
			$html .= "     </td>\n";
			$html .= "     </tr>\n";
			$html .= "    </table>\n";
			
			
			
			
			
			return $html;
		}
		
	}
?>