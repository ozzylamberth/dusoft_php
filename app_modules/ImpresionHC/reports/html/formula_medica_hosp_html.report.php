<?php

/**
 * $Id: formula_medica_hosp_html.report.php,v 1.5 2009/07/17 12:52:03 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

//Reporte de Formulacion de medicamentos formato HTML
//este reporte es usado desde la central de impresion de hospitalizacion
//segun la orden se puede generar cuatro tipos distintos de
//formulas (pos, no pos justificados , no pos a peticion del paciente y de uso controlado)

class formula_medica_hosp_html_report
{
	//VECTOR DE DATOS O PARAMETROS PARA GENERAR EL REPORTE
	var $datos;
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

    //CONSTUCTOR DE LA CLASE - RECIBE EL VECTOR DE DATOS - METODO PRIVADO NO MODIFICAR
    function formula_medica_hosp_html_report($datos=array())
    {
          $this->datos=$datos;
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


	//Funciones de Vistas.
     function CrearReporte()
     {
          //Consulta de Medicamentos y datos del paciente.
		$datos = $this->ReporteFormulaMedica();
		
          $fechaI=$this->FechaStampT($datos[0][fecha_nacimiento]);
		$fechaF=$this->FechaStampT($datos[0][fecha_cierre]);
		$fechaIngreso=$this->FechaStamp($datos[0][fecha_ingreso]);
		$fechaEvolucion=$this->FechaStamp($datos[0][fecha_cierre]);
		$edad=CalcularEdad($fechaI,$fechaF);

          $titulo = 'FORMULA MEDICA';
		/****************/
		$Salida.="<table width=\"100%\" align=\"center\" border=\"0\" class=\"modulo_table\">";
		$Salida.="<tr>\n";
		$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">IDENTIFICACION:&nbsp;".$datos[0][tipo_id_paciente]." ".$datos[0]['paciente_id']."</FONT></td>\n";
		$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"30%\">NOMBRE:&nbsp;".strtoupper($datos[0]['paciente'])."</FONT></td>\n";
		$Salida.="<td class=\"Normal_10N\" ALIGN=\"JUSTIFY\" WIDTH=\"20%\">HC:&nbsp;";
		if($datos[0]['historia_numero']!="")
		{
			if($datos[0]['historia_prefijo']!="")
			{
				$Salida .= $datos[0]['historia_numero']." - ". $datos[0]['historia_prefijo'];
			}
			else
			{
				$Salida .= $datos[0]['paciente_id']." - ".$datos[0]['historia_prefijo'];
			}
		}
		else
		{
			$Salida.= $datos[0]['paciente_id']." - ".$datos[0]['tipo_id_paciente'];
		}
		$Salida.="</td>\n";
		$Salida.="<td ALIGN=\"JUSTIFY\" WIDTH=\"20%\" class=\"Normal_10N\">EDAD:&nbsp;";
		$Salida.=$edad['anos'].'&nbsp;Años';
		$Salida.="</td>\n";
		$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\" class=\"Normal_10N\">SEXO:&nbsp;";
		$Salida.= $datos[0]['sexo_id'];
		$Salida.="</td>\n";
		$Salida.="</tr>\n";
		$Salida.="</table>\n";

		$Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"0\" class=\"modulo_table\">";
		$Salida.= "<TR>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">FECHA DE INGRESO:&nbsp;&nbsp;".$fechaIngreso."</TD>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"40%\">No. INGRESO:&nbsp;&nbsp;".$datos[0][ingreso]."</TD>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">FECHA DE SOLICITUD:&nbsp;&nbsp;".$fechaEvolucion."</TD>";
		$Salida.= "</TR>";
		$Salida.= "</TABLE>\n";


		$Salida.= "<TABLE ALIGN=\"CENTER\" WIDTH=\"100%\" BORDER=\"0\" class=\"modulo_table\">";
		$Salida.= "<TR>";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"30%\">CLIENTE:&nbsp;&nbsp;".strtoupper($datos[0][cliente])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"40%\">PLAN:&nbsp;&nbsp;".strtoupper($datos[0][plan_descripcion])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"20%\">TIPO AFILIADO:&nbsp;&nbsp;".strtoupper($datos[0][tipo_afiliado_nombre])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" class=\"Normal_10N\" WIDTH=\"10%\">RANGO:&nbsp;".$datos[0][rango]."</td>\n";
          $Salida .= "</tr>\n";
		$Salida.= "</table>\n<br><br>";
          
		$Salida.= $this->FrmMedicamentos($datos[1], 'hosp');
          
          $Salida.="<br><table width=\"100%\">";
          $Salida.="<tr><td class=\"Normal_10N\" align=\"left\" width=\"100%\">MEDICO TRATANTE:</td></tr>";

          //unica parte donde este reporte es diferente con respecto al de hospitalizacion
          //por que el medico que se pinta aqui es el de una evolucion especifica
          //que trae el resultado del query.  y en hospitalizacion quien firma la formula
          //es el medico de la max evolucion cerrada del ingreso.
          $largo = strlen($datos[2][nombre_tercero]);
          $cad = '___';
          for ($l=0; $l<$largo; $l++)
          {
               $cad = $cad.'_';
          }

          if($datos[2][tarjeta_profesional] != '')
          {
               $Salida.="<tr class=\"Normal_10N\">";
               $Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\">".$cad."<br>".strtoupper($datos[2][nombre_tercero])."<br>".$datos[2][tipo_id_medico].': '.$datos[2][medico_id].' T.P.: '.$datos[2][tarjeta_profesional]."<br>".$datos[2][tipo_profesional]."</td>";
               $Salida.="</tr>";
          }
          else
          {
               $Salida.="<tr class=\"Normal_10N\">";
               $Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\">".$cad."<br>".strtoupper($datos[2][nombre_tercero])."<br>".$datos[2][tipo_id_medico].': '.$datos[2][medico_id]."<br>".$datos[2][tarjeta_profesional]."</td>";
               $Salida.="</tr>";
          }
          $Salida.= "</table>\n";
		return $Salida;
	}	

     function FrmMedicamentos($vector1, $tipo_formulacion)
     {
          if ($tipo_formulacion == "amb")
          { $tipo_medicamentos = "MEDICAMENTOS AMBULATORIOS"; }
          else
          { $tipo_medicamentos = "MEDICAMENTOS Y/O SOLUCIONES HOSPITALARIAS"; }
          
          $html.="<table align=\"center\" border=\"0\" width=\"100%\">";
          $html.="<tr>";
          $html.="  <td class=\"Normal_10N\" align=\"center\"><font size=\"2\">".$tipo_medicamentos."</font></td>";
          $html.="</tr>";
          
          $html.="<tr>";
          $html.="  <td width=\"100%\" align=\"left\">";
          
          $html.="    <br><table  align=\"center\" border=\"0\"  width=\"100%\">";
          
          $html.= $this->Pintar_FormulacionConsultada($vector1, $tipo_formulacion);
          
          $html.="    </table>";
         
          $html.="  </td>";
          $html.="</tr>";
          
          $html.="</table>";
          return $html;
     }// Fin FrmMedicamentos
     
     
     /*
     * Forma que permite dibujar la consulta de los medicamentos.
     *
     * @autor Tizziano Perea
     */
	function Pintar_FormulacionConsultada($vectorOriginal, $tipo_formulacion)
     {
          foreach($vectorOriginal as $k => $vector1)
          {
               for($i=0;$i<sizeof($vector1);$i++)
               {
                     $salida.="<tr>";
                    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    { 
                    	$salida.="<td width=\"40%\" class=\"Normal_10N\" colspan=\"3\">".$vector1[$i]['producto']." - ( ".$vector1[$i]['codigo_producto']." - ";
                         if(empty($vector1[$i]['codigo_pos']))
                         {
                         	$salida.="".$vector1[$i]['item']." )";
                         }else{
                         	$salida.="".$vector1[$i]['codigo_pos']." )";
                         }
                         $salida.="</td>";
                    }
                    else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<td width=\"40%\" colspan=\"3\" class=\"Normal_10N\">";
                              for($j=0; $j<sizeof($vector1); $j++)
                              {
                                   if($vector1[$i]['num_mezcla'] == $vector1[$j]['num_mezcla'])
                                   {
                                        $salida.="".$vector1[$j]['producto']." - ( ".$vector1[$j]['codigo_producto']." - <label class=\"label_mark\">".$vector1[$j]['dosis']." ".$vector1[$j]['unidad_suministro']."</label>)<br>";
                                   }
                              }
                              $salida.="</td>";
                         }
                    }
    
                    if($vector1[$i]['tipo_solicitud'] == "M")
                    {
                         $salida.="<tr>";
                         $salida.="<td colspan=\"6\">";
                         $salida.="<table>";
                         
                         $salida.="<tr>";
                         $salida.="<td colspan = 3 align=\"left\" width=\"9%\" class=\"Normal_10N\"><b>Via de Administracion:</b> <label class=\"Normal_10\">".$vector1[$i][via]."</label></td>";
                         $salida.="</tr>";
     
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="<td align=\"left\" width=\"9%\" class=\"Normal_10N\">Dosis:</td>";
                         $e=$vector1[$i][dosis]/floor($vector1[$i][dosis]);
                         if($e==1)
                         {
                              $salida.="  <td align=\"left\" width=\"10%\" class=\"Normal_10\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         else
                         {
                              $salida.="  <td align=\"left\" width=\"10%\" class=\"Normal_10\">".$vector1[$i][dosis]."  ".$vector1[$i][unidad_dosificacion]."</td>";
                         }
                         
                         $salida.="<td align=\"left\" width=\"20%\" class=\"Normal_10\">".$vector1[$i][frecuencia]."</td>";                         
                         $salida.="</tr>";
          
                         $salida.="<tr class=\"$estilo\">";
                         $salida.="  <td align=\"left\" width=\"9%\" class=\"Normal_10N\">Cantidad:</td>";
                         $e=$vector1[$i][cantidad]/floor($vector1[$i][cantidad]);
                         if($vector1[$i][contenido_unidad_venta])
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" class=\"Normal_10\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" class=\"Normal_10\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]." por ".$vector1[$i][contenido_unidad_venta]."</td>";
                              }
                         }
                         else
                         {
                              if($e==1)
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" class=\"Normal_10\">".floor($vector1[$i][cantidad])." ".$vector1[$i][unidad]."</td>";
                              }
                              else
                              {
                                   $salida.="  <td colspan=\"2\" align=\"left\" class=\"Normal_10\">".$vector1[$i][cantidad]." ".$vector1[$i][unidad]."</td>";
                              }
                         }
                         $salida.="</tr>";
                         if($vector1[$i][observacion] != "")
                         {
                              $salida.="<tr>";
                              $salida.="  <td align=\"left\" width=\"9%\" class=\"Normal_10N\">Observación:</td>";
                              $salida.="  <td align=\"left\" colspan=\"2\" class=\"Normal_10\">".$vector1[$i][observacion]."</td>";
                              $salida.="</tr>";
                         }
          
                         $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                         $salida.="<tr>";
                         $salida.="<td align=\"left\" width=\"9%\" class=\"Normal_10N\">Formuló:</td>";
                         $salida.="<td align=\"left\" colspan=\"2\" class=\"Normal_10\">".$Profesional."</td>";
                         $salida.="</tr>";
                         $salida.="</table><br>";
                         $salida.="</td>";
                         $salida.="</tr>";
     
                    }else
                    {
                         if($vector1[$i]['num_mezcla'] != $vector1[$i-1]['num_mezcla'])
                         {
                              $salida.="<tr>";
                              $salida.="<td colspan=\"6\">";
                              $salida.="<table>";

                              $salida.="<tr>";
                              $salida.="  <td align=\"left\" width=\"42%\" class=\"Normal_10N\">Cantidad Total:</td>";
                              $salida.="  <td align=\"left\" colspan=\"2\" class=\"Normal_10\">".floor($vector1[$i][cantidad])." SOLUCION(ES)</td>";
                              $salida.="</tr>";
                              
                              $salida.="<tr>";
                              $salida.="  <td align=\"left\" width=\"42%\" class=\"Normal_10N\">Volumen de Infusión:</td>";
                              $salida.="  <td align=\"left\" colspan=\"2\" class=\"Normal_10\">".floor($vector1[$i][volumen_infusion])." ".strtoupper($vector1[$i][unidad_volumen])."</td>";
                              $salida.="</tr>";
                         
                              if($vector1[$i][observacion] != "")
                              {
                                   $salida.="<tr>";
                                   $salida.="  <td align=\"left\" width=\"9%\" class=\"Normal_10N\">Observación:</td>";
                                   $salida.="  <td align=\"left\" colspan=\"2\" class=\"Normal_10\">".$vector1[$i][observacion]."</td>";
                                   $salida.="</tr>";
                              }
               
                              $Profesional = $this->ProfesionalFormulacion_Medicamento($vector1[$i][usuario_id]);
                              $salida.="<tr>";
                              $salida.="<td align=\"left\" width=\"9%\" class=\"Normal_10N\">Formuló:</td>";
                              $salida.="<td align=\"left\" colspan=\"2\" class=\"Normal_10\">".$Profesional."</td>";
                              $salida.="</tr>";
                              $salida.="</table><br>";
		                    $salida.="</td>";
                              $salida.="</tr>";
                         }
                    }
               } //fin del for muy importante
          }
     	return $salida;
     }

     
     
     //Funciones de Consulta.
     function ReporteFormulaMedica()
	{
          //cargando criterios.
          $criterio_paciente   = $this->datos['paciente_id'];
	     $criterio_tipo_id    = $this->datos['tipo_id_paciente'];
          $criterio_ingreso    = $this->datos['ingreso'];
          $criterio_evolucion  = $this->datos['evolucion_id'];
          //fin de criterios

		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          // Informacion de los datos del Paciente.
		$queryI="SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||w.primer_apellido||' '||w.segundo_apellido,'') AS paciente,
                         w.tipo_id_paciente, w.paciente_id, w.sexo_id, w.fecha_nacimiento,
                         w.residencia_direccion, w.residencia_telefono,
                         x.historia_numero, x.historia_prefijo, 
                         n.fecha_cierre, n.fecha, 
                         y.fecha_ingreso, y.ingreso, 
					v.tipo_afiliado_id, v.tipo_afiliado_nombre, 
                         t.plan_id, t.sw_tipo_plan, t.plan_descripcion, 
                         s.rango,
                         p.nombre_tercero, p.nombre_tercero AS cliente,
                         em.tipo_id_tercero AS tipo_empresa, em.id, em.razon_social
                         
          	  
                 FROM 	pacientes AS w
                 		LEFT JOIN historias_clinicas AS x ON (w.paciente_id = x.paciente_id AND w.tipo_id_paciente = x.tipo_id_paciente),
                 		ingresos AS y,
                         hc_evoluciones AS n,
                         cuentas AS s
                         LEFT JOIN tipos_afiliado AS v ON (s.tipo_afiliado_id = v.tipo_afiliado_id),
                         planes AS t,
                         terceros AS p,
                         empresas AS em
                 
                 WHERE   w.paciente_id = '".$criterio_paciente."'
                 AND 	w.tipo_id_paciente = '".$criterio_tipo_id."'
                 AND 	y.ingreso = ".$criterio_ingreso."
                 AND 	y.paciente_id = w.paciente_id
                 AND 	y.tipo_id_paciente = w.tipo_id_paciente
                 AND 	n.evolucion_id = ".$criterio_evolucion."
                 AND 	n.estado = '0'
                 AND 	n.ingreso = y.ingreso
                 AND 	n.numerodecuenta = s.numerodecuenta
                 AND 	em.empresa_id = s.empresa_id
                 AND 	s.plan_id = t.plan_id    
                 AND 	t.tercero_id = p.tercero_id
                 AND 	t.tipo_tercero_id = p.tipo_id_tercero;";          
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryI);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $datosPaciente = $result->FetchRow();
                         
          $queryM="SELECT B.evolucion_id,
          		   A.sw_estado, A.codigo_producto, A.cantidad, A.dosis, A.frecuencia,
                       A.unidad_dosificacion, A.observacion, B.fecha_registro, B.usuario_id,
                       H.descripcion as producto, 
                       C.descripcion as principio_activo,
                       K.sw_uso_controlado, 
                       CASE WHEN K.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
                       M.nombre AS via,
                       L.descripcion AS unidad,
                       'M' AS tipo_solicitud
          	 
                FROM   hc_formulacion_medicamentos AS A
                	   LEFT JOIN hc_vias_administracion AS M ON (A.via_administracion_id = M.via_administracion_id),
                	   hc_formulacion_medicamentos_eventos AS B,
                       inventarios_productos AS H,
                       medicamentos AS K,
                       inv_med_cod_principios_activos AS C,                       
                       unidades AS L,
                       hc_evoluciones N
                
                WHERE B.evolucion_id = ".$criterio_evolucion."
                AND   B.evolucion_id =  N.evolucion_id
                AND   N.estado = '0'
                AND   A.num_reg_formulacion = B.num_reg
                AND   A.sw_estado = '1'
                AND   H.codigo_producto = A.codigo_producto
                AND   K.codigo_medicamento = A.codigo_producto
                AND   K.cod_principio_activo = C.cod_principio_activo
                AND   H.unidad_id = L.unidad_id
                ORDER BY K.sw_pos, A.codigo_producto, B.evolucion_id;";                         
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryM);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }                         
 		while ($data = $result->FetchRow())
          {
               $vectorM[] = $data;
          }        
          
          // Creando Vector de Medicamentos
          $vectorOriginal = array();
          if($vectorM)
          { array_push($vectorOriginal, $vectorM); }
          
          $queryS="SELECT DISTINCT A.num_mezcla,
          		   B.evolucion_id,
          		   A.sw_estado, DET.codigo_producto, A.cantidad,
                       A.volumen_infusion, A.unidad_volumen, B.usuario_id,
                       A.observacion, DET.cantidad AS cantidad_producto, 
                       DET.unidad_dosificacion AS unidad_suministro,
                       DET.dosis,
                       H.descripcion as producto, 
                       C.descripcion as principio_activo,
                       K.sw_uso_controlado, 
                       CASE WHEN K.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
                       L.descripcion AS unidad,
                       'S' AS tipo_solicitud
          	 
                FROM   hc_formulacion_mezclas AS A,
                	   hc_formulacion_mezclas_eventos AS B,
                       hc_formulacion_mezclas_detalle AS DET,
                       inventarios_productos AS H,
                       medicamentos AS K,
                       inv_med_cod_principios_activos AS C,                       
                       unidades AS L,
                       hc_evoluciones N
                
                WHERE B.evolucion_id = ".$criterio_evolucion."
                AND   B.evolucion_id =  N.evolucion_id
                AND   N.estado = '0'
                AND   A.num_mezcla = B.num_mezcla
                AND   A.num_mezcla = DET.num_mezcla
                AND   A.sw_estado = '1'
                AND   H.codigo_producto = DET.codigo_producto
                AND   K.codigo_medicamento = DET.codigo_producto
                AND   K.cod_principio_activo = C.cod_principio_activo
                AND   H.unidad_id = L.unidad_id
                ORDER BY A.num_mezcla DESC;";
               
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($queryS);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }                         
 		while ($dataS = $result->FetchRow())
          {
               $vectorS[] = $dataS;
          }
          
          if($vectorS)
          { array_push($vectorOriginal, $vectorS); }
       
		
          $qdatosP = "SELECT	r.descripcion as tipo_profesional,
          			   	p.tipo_id_tercero as tipo_id_medico, p.tercero_id as medico_id, 
          				q.tarjeta_profesional, q.nombre AS nombre_tercero
                             
          		  FROM	hc_evoluciones AS n,
                      		profesionales AS q,
                              terceros AS p,
                              tipos_profesionales AS r
                              
                      WHERE	n.evolucion_id = ".$criterio_evolucion."
                      AND 	n.estado = '0'
                      AND 	n.usuario_id = q.usuario_id
                      AND 	q.tipo_id_tercero = p.tipo_id_tercero
                      AND 	q.tercero_id = p.tercero_id
                      AND 	q.tipo_profesional = r.tipo_profesional";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($qdatosP);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;          

          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          
          $datosProfesional = $result->FetchRow();
          
          $vectorGeneral = array();
          array_push($vectorGeneral, $datosPaciente);
          array_push($vectorGeneral, $vectorOriginal);
          array_push($vectorGeneral, $datosProfesional);

          return $vectorGeneral;
     }

     /*
     * Funcion Obtiene los datos del profesional que formulo los medicamentos a solicitar.
     */
     function ProfesionalFormulacion_Medicamento($usuario_id)
     {
          list($dbconn) = GetDBconn();
     	$query="SELECT usuario ||' - '|| nombre 
                  FROM system_usuarios
                  WHERE usuario_id = ".$usuario_id.";";
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error en la consulta";
               $this->mensajeDeError = "Ocurrió en error al intentar obtener los resultados.<br><br>".$dbconn->ErrorMsg()."<br><br>".$query;
               return false;
          }
     	list($Profesional) = $resultado->FetchRow();
          return $Profesional;
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

	function FechaStampT($fecha)
	{
		if($fecha){
               $fech = strtok ($fecha,"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               return  ceil($date[0])."/".ceil($date[1])."/".ceil($date[2]);
		}
	}


}
?>