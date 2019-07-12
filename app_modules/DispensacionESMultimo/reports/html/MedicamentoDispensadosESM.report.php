<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: MedicamentoPendienteESM.report.php,v 1.5 2010/07/08  
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

	class MedicamentoDispensadosESM_report 
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
		function MedicamentoDispensadosESM_report($datos=array())
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
			$est  = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-size:5px\"";
			$ods = new DispensacionESMSQL();
			
			$Cabecera_Formulacion=$ods->Consulta_Formulacion_Real_I($this->datos['formula_id']);
		
			$Datos_Fueza = $ods->ObtenerFuezaPaciente($Cabecera_Formulacion); 	
			$Datos_Ad=$ods->Dato_Adionales_afiliacion($Cabecera_Formulacion);
			$ESM_pac=$ods->Consultar_ESM_P($Cabecera_Formulacion);
			
			$medicamentos=$ods->Medicamentos_Dispensados_Esm_x_lote($this->datos['formula_id']);
			
 $pendientes_dis=$ods->pendientes_dispensados_ent($this->datos['formula_id']);
			
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			
			$html .= "								<tr >\n";
			
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
			$html .= "									</td>\n";
		
			
			
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
			$html .= "									</td>\n";
		
		
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
			$html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
			$html .= "									</td>\n";

			
			$html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
			$html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
			$html .= "									</td> \n";
								
			$html .= "								</tr>\n";
			$html .= "							</table>\n";
			
			
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			
			$html .= "									<td width=\"50%\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
			$html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
			$html .= "									</td>\n";
			
			$html .= "								</tr>\n";
			
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\"style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
			$html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
			$html .= "									</td>\n";
			
		
			$html .= "								</tr>\n";


			$html .= "							</table>\n";
	
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table  width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr>\n";
			$html .= "									<td style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
			$html .= "									<td colspan=\"1\">\n";
			$html .= "										".$Cabecera_Formulacion['tipo_id_paciente']."  ".$Cabecera_Formulacion['paciente_id']."\n";
			$html .= "									</td>\n";
			$html .= "									<td  style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
			$html .= "									<td  > ".$Cabecera_Formulacion['nombre_paciente']."\n";

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
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">EDAD</td>\n";
			$html .= "									<td >".$edad." &nbsp; $edad_t \n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">SEXO</td>\n";
			$html .= "									<td align=\"left\">".$sexo."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			$html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";

			if(empty($Datos_Fueza))
			{
			
               	$fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
				$fuerza .= "									</td>\n";			
			
			  
			}
			else
			{
				$fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
				$fuerza .= "									</td>\n";			
			
			
			}
			
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
			$html .= "								".$fuerza;
			$html .= "								</tr>\n";	
			
			
			$html .= "								<tr>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">TIPO PLAN</td>\n";
			$html .= "									<td >".$Datos_Ad['tipo_plan']."\n";
			$html .= "									</td>\n";
			$html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:3pt\">TIPO VINCULACION</td>\n";
			$html .= "									<td >".$Datos_Ad['vinculacion']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";	
			
			$html .= "								<tr >\n";
			$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
			$html .= "									<td colspan=\"1\">\n";
			$html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			
		
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
				$html .= "									<td colspan=\"1\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
				
				$html .= "									</td>\n";
				$html .= "								</tr>\n";
				
				$html .= "								<tr >\n";
				$html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
				$html .= "									<td colspan=\"1\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional_esm']." (".$Cabecera_Formulacion['descripcion_profesional_esm'].")\n";
				$html .= "						     </td>\n";
				$html .= "								</tr>\n";
								
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
			
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS DISPENSADOS</legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			
			$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
			$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
			$html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
			$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
			$html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			
			///	foreach($pendientes_dis as $it=>$fill)
			//	{
			foreach($medicamentos as $item=>$fila)
			{
			   
				   
			
				  
					$html .= "								<tr >\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['codigo_producto']."</td>\n";
					$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['descripcion_prod']."</td>\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['fecha_vencimiento']."</td>\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['lote']."</td>\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".round($fila['numero_unidades'])."</td>\n";

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
			
			
			if(!empty($pendientes_dis))
		{
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "			<fieldset class=\"fieldset\">\n";
			$html .= "				<legend class=\"normal_10AN\">MEDICAMENTOS PENDIENTES-DISPENSADOS </legend>\n";
			$html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td align=\"center\">\n";
			$html .= "							<table width=\"100%\" class=\"label\" $style>\n";
			$html .= "								<tr >\n";
			
			$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CODIGO</td>\n";
			$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >MEDICAMENTO</td>\n";
			$html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >FECHA VENC</td>\n";
			$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >LOTE</td>\n";
			$html .= "									<td colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >CANTIDAD</td>\n";
			$html .= "									</td>\n";
			$html .= "								</tr>\n";
			
			// $pendientes_dis=$ods->pendientes_dispensados_ent($this->datos['formula_id'],$fila['codigo_producto']);
			///	foreach($pendientes_dis as $it=>$fill)
			//	{
			foreach($pendientes_dis as $item=>$fila)
			{
			   
				   
			
				  
					$html .= "								<tr >\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['codigo_producto']."</td>\n";
					$html .= "									<td colspan=\"2\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['descripcion_prod']."</td>\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['fecha_vencimiento']."</td>\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".$fila['lote']."</td>\n";
					$html .= "									<td  colspan=\"1\" style=\"text-align:left;text-indent:3pt\" class=\"formulacion_table_list\" >".round($fila['numero_unidades'])."</td>\n";

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
			
			
			}
			
			
						
      $html .= "             <table align=\"center\"  width=\"35%\">\n";
      $html .= "             <tr class=\"label\"  valign=\"bottom\" >\n";
      $html .= "                <td align=\"LEFT\" height=\"50\">________________________________________</td>\n";
      $html .= "              </tr>\n";        
      $html .= "               <tr class=\"label\" >\n";
      $html .= "                <td align=\"LEFT\">FIRMA PACIENTE</td>\n";
      $html .= "               </tr>\n";
      $html .= "	</table>\n";
	  
	  
			$html .= "   <table align='right' border='0' width='95%'>";
			$html .= "       <tr align='right'>\n";
			$html .= "         <td width='50%' align=\"right\" $ESTILO20>";
			$html .= "           USUARIO  DIGITALIZA:";
			$html .= "       ".$Cabecera_Formulacion['nombre']."&nbsp;";
			$html .= "      - ".$Cabecera_Formulacion['descripcion']."&nbsp;";
			$html .= "      </td>\n";
			$html .= "       </tr>\n";
			$html .= "       <tr align='right'>\n";
			$html .= "         <td width='50%' align=\"right\" $ESTILO20>";
			$html .= "           USUARIO  REALIZA DESPACHO:";
			$html .= "       ".$medicamentos[0]['nombre']."&nbsp;";
			$html .= "      - ".$$medicamentos[0]['descripcion']."&nbsp;";
			$html .= "      </td>\n";
			$html .= "       </tr>\n";
			
			$html .= "       <tr align='right'>\n";
			$html .= "         <td width='50%' align=\"right\" $ESTILO20>";
			$html .= "       FECHA DE IMPRESION :".date("Y-m-d (H:i:s a)")."&nbsp;";
			$html .= "     </td>\n";
			$html .= "     </tr>\n";
			$html .= "    </table>\n";
			return $html;
		}
		
	}
?>