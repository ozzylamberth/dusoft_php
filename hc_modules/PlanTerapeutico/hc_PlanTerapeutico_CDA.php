<?php

/**
* $Id: hc_PlanTerapeutico_CDA.php,v 1.3 2005/07/25 18:32:33 tizziano Exp $
*/

/**
* Clase para consulta CDA-HL7 del submodulo PlanTerapeutico
*
* @author Tizziano Perea <tperea@ipsoft-sa.com>
* @version $Revision: 1.3 $
* @package SIIS
*/ 
class PlanTerapeutico_CDA extends Extenciones_CDA_HC
{
    /**
    * Variable que contendra el Parametro de Busqueda
    *
    * @var $datos
    * @access private
    */
    var $datos;
    
    /**
    * Variable que contendra el Parametro para el Metodo Busqueda
    *
    * @var $TipoMetodo
    * @access private
    */
    var $TipoMetodo;
 
    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public    
    */  
    function PlanTerapeutico_CDA()
    {
        $this->Extenciones_CDA_HC();
        return true;
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EVOLUCION
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Evolucion($evolucion_id)
    {
          if (empty($evolucion_id))
          {
               return '';
          }
          else
          {
			$this->datos[evolucion] = $evolucion_id;
               $this->TipoMetodo = '1';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una EPICRISIS DE UN INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Epicrisis($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->datos[ingreso] = $ingreso;
               $this->TipoMetodo = '2';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un INGRESO
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Ingreso($ingreso)
    {
          if (empty($ingreso))
          {
               return '';
          }
          else
          {
			$this->datos[ingreso] = $ingreso;
               $this->TipoMetodo = '3';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }    
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para una HISTORIA CLINICA DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Full_Historia($paciente_id,$tipoidpaciente)
    {
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '4';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }
    
    /**
    * Metodo que genera y retorna la estructura CDA-XML para un RESUMEN DE ATENCIONES DE UN PACIENTE
    *
    * @param string $evolucion_id Numero de Evolucion
    * @return string La estructura XML-CDA de la consulta
    * @access private    
    */     
    function &GetCDA_Resumen_Historia($paciente_id,$tipoidpaciente)
    { 
          if (empty($paciente_id) || empty($tipoidpaciente))
          {
               return '';
          }
          else
          {
               $this->datos[paciente_id] = $paciente_id;
               $this->datos[tipoidpaciente] = $tipoidpaciente;
               $this->TipoMetodo = '5';
               $XML = $this->GetConsultaSubmodulo($this->datos,$this->TipoMetodo);
               $this->salida = $XML;
               return $this->salida;
          }
    }        

	/*		GetXML_Local
     *
     *		Crea la vista de los datos en XML para su posterior traspaso
     *		a HTML y generacion de impresion.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param array => $XML_Consulta - Vector de datos.
     */

    function GetXML_Local($XML_Consulta)
    {
          $xx = 0;
		foreach($XML_Consulta as $k => $v)
          {
               $salida.="<TABLE border=\"1\" width=\"100%\">";
               if($xx != 1)
               {
                    $xx = 1;
                    $salida.="<COLGROUP align=\"center\">";
                    
                    $salida.="<THEAD valign=\"top\">";
                    $salida.="<TR>";
                    $salida.="<TH colspan=\"3\">MEDICAMENTOS SOLICITADOS</TH>";
                    $salida.="</TR>";
                     
                    $salida.="<COLGROUP align=\"center\">";
                    $salida.="<COLGROUP align=\"center\">";
                    $salida.="<COLGROUP align=\"center\">";
                    
                    $salida.="<TR>";
                    $salida.="<TH>CODIGO</TH>";
                    $salida.="<TH>PRODUCTO</TH>";
                    $salida.="<TH>PRINCIPIO ACTIVO</TH>";
                    $salida.="</TR>";
                    $salida.="</THEAD>";
               }
               $salida.="<TBODY>";
               $salida.="<TR>";
               if($v[item] == 'NO POS')
               {
                    $salida.="<TD width=\"17%\" align=\"center\">".$v[codigo_producto]." - NO POS</TD>";
               }
               else
               {
                    $salida.="<TD width=\"17%\" align=\"center\">".$v[codigo_producto]."</TD>";
               }

               $salida.="<TD width=\"53%\" align=\"justify\"><b>".$v[producto]." - ".$v[evolucion_id]."</b></TD>";
               $salida.="<TD width=\"30%\" align=\"justify\">".$v[principio_activo]."</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               
               $vectorMSH = $this->Consulta_Solicitud_Medicamentos_Historial($v[codigo_producto], $this->datos[ingreso], $this->datos[evolucion]);
               if ($vectorMSH)
               {
	               $salida.="<TBODY>";
                    $registros_historial = sizeof($vectorMSH);
                    $salida.="<TR>";
                    $salida.="<td colspan =\"3\" align=\"center\" width=\"63%\"><font color=\"#240000\">HISTORIAL (No. veces formulado: ".$registros_historial." --- Primer Formulacion: ".$this->FechaStamp($vectorMSH[0][fecha])." --- Ultima Formulacion: ".$this->FechaStamp($vectorMSH[$registros_historial-1][fecha]).")</font></td>";
                    $salida.="</tr>";
               	$salida.="</TBODY>";
               }

               $salida.="<TBODY>";
               $salida.="<TR>";
               $salida.="<TD width=\"30%\" align=\"justify\" colspan=\"3\">Via de Administracion:&nbsp;".$v[via];
               
               //PINTO DOSIS
               $salida.="<p></p>Dosis:  ";
               $dosis = $v[dosis]/floor($v[dosis]);
               if($dosis == 1)
               {
                    $salida.= floor($v[dosis])."  ".$v[unidad_dosificacion];
               }
               else
               {
                    $salida.= $v[dosis]."  ".$v[unidad_dosificacion];
               }
               //FIN PINTO DOSIS
               
               //PINTO POSOLOGIA
               $salida.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
               $vector_posologia = $this->Consulta_Solicitud_Medicamentos_Posologia($v[codigo_producto], $v[tipo_opcion_posologia_id], $v[evolucion_id]);

               //pintar formula para opcion 1
               if($v[tipo_opcion_posologia_id] == 1)
               {
                    $salida.= $vector_posologia[0][periocidad_id]." ".$vector_posologia[0][tiempo];
               }

               //pintar formula para opcion 2
               if($v[tipo_opcion_posologia_id] == 2)
               {
                    $salida.= $vector_posologia[0][descripcion];
               }

               //pintar formula para opcion 3
               if($v[tipo_opcion_posologia_id] == 3)
               {
                    $momento = '';
                    if($vector_posologia[0][sw_estado_momento]== '1')
                    {
                         $momento = 'antes de ';
                    }
                    else
                    {
                         if($vector_posologia[0][sw_estado_momento]== '2')
                         {
                              $momento = 'durante ';
                         }
                         else
                         {
                              if($vector_posologia[0][sw_estado_momento]== '3')
                              {
                                   $momento = 'despues de ';
                              }
                         }
                    }
                    $Cen = $Alm = $Des= '';
                    $cont= 0;
                    $conector = '  ';
                    $conector1 = '  ';
                    if($vector_posologia[0][sw_estado_desayuno]== '1')
                    {
                         $Des = $momento.'el Desayuno';
                         $cont++;
                    }
                    if($vector_posologia[0][sw_estado_almuerzo]== '1')
                    {
                         $Alm = $momento.'el Almuerzo';
                         $cont++;
                    }
                    if($vector_posologia[0][sw_estado_cena]== '1')
                    {
                         $Cen = $momento.'la Cena';
                         $cont++;
                    }
                    if ($cont== 2)
                    {
                         $conector = ' y ';
                         $conector1 = '  ';
                    }
                    if ($cont== 1)
                    {
                         $conector = '  ';
                         $conector1 = '  ';
                    }
                    if ($cont== 3)
                    {
                         $conector = ' , ';
                         $conector1 = ' y ';
                    }
                    $salida.= $Des."".$conector."".$Alm."".$conector1."".$Cen."";
               }
               
               //pintar formula para opcion 4
               if($v[tipo_opcion_posologia_id] == 4)
               {
                    $conector = '  ';
                    $frecuencia='';
                    $j=0;
                    foreach ($vector_posologia as $k => $v)
                    {
                         if ($j+1 ==sizeof($vector_posologia))
                         {
                              $conector = '  ';
                         }
                         else
                         {
                              if ($j+2 ==sizeof($vector_posologia))
                                   {
                                        $conector = ' y ';
                                   }
                              else
                                   {
                                        $conector = ' - ';
                                   }
                         }
                         $frecuencia = $frecuencia.$k.$conector;
                         $j++;
                    }
                    $salida.="a la(s): ".$frecuencia;
               }
			
               //pintar formula para opcion 5
               if($v[tipo_opcion_posologia_id] == 5)
               {
                    $salida.= $vector_posologia[0][frecuencia_suministro];
               }
               //FIN PINTO POSOLOGIA
               
               //PINTO CANTIDAD               
               $salida.="<p></p>Cantidad:  ";
               $Cantidad = $v[cantidad]/floor($v[cantidad]);
               if($v[contenido_unidad_venta])
               {
                    if($Cantidad == 1)
                    {
                         $salida.= floor($v[cantidad])." ".$v[descripcion]." por ".$v[contenido_unidad_venta];
                    }
                    else
                    {
                         $salida.= $v[cantidad]." ".$v[descripcion]." por ".$v[contenido_unidad_venta];
                    }
			}
               else
               {
                    if($Cantidad == 1)
                    {
                         $salida.= floor($v[cantidad])." ".$v[descripcion];
                    }
                    else
                    {
                         $salida.= $v[cantidad]." ".$v[descripcion];
                    }
               }
               //FIN PINTO CANTIDAD
               
               $salida.="</TD>";
               $salida.="</TR>";
               $salida.="</TBODY>";
               
			if (!empty($v[observacion]))
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TD align=\"justify\" colspan=\"3\"><b>Observación: </b>".$v[observacion]."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
			}
			
               if ($v[sw_uso_controlado] == 1)
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TD align=\"center\" colspan=\"3\"><b>MEDICAMENTO DE USO CONTROLADO</b></TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
			}

			if ($v[sw_ambulatorio] == 1)
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TD align=\"center\" colspan=\"3\"><b>MEDICAMENTO AMBULATORIO</b></TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
			}

               if (!empty($v[nombre_tercero]))
               {
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    $salida.="<TD align=\"center\"><b>Formuló:</b></TD>";
                    $salida.="<TD align=\"justify\" colspan=\"2\">".$v[nombre_tercero]."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
			}
               
               if($v[item] == 'NO POS')
               {     
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    if ($v[sw_paciente_no_pos] != '1')
                    {
                         $salida.="<TD align=\"center\" colspan=\"3\"><b>MEDICAMENTO JUSTIFICADO</b></TD>";
                    }
                    else
                    {
                         $salida.="<TD align=\"center\" colspan=\"3\"><b>MEDICAMENTO NO POS FORMULADO A PETICION DEL PACIENTE</b></TD>";                    
                    }
                    $salida.="</TR>";
                    $salida.="</TBODY>";
               }
               $salida.="</TABLE>";
          }
          //**pintar los medicamentos finalizados y suspendidos
          $vectorMSF = $this->Consulta_Solicitud_Medicamentos_Finalizados_y_Suspendidos($this->datos[ingreso], $this->datos[evolucion]);
          if ($vectorMSF)
          {
               for($i=0;$i<sizeof($vectorMSF);$i++)
               {               
               	$salida.="<BR><TABLE border=\"1\" width=\"100%\">";
                    if ($vectorMSF[$i][sw_estado] != $vectorMSF[$i-1][sw_estado] )
                    {
                         $salida.="<TR>";
                         if ($vectorMSF[$i][sw_estado]=='2')
                         {
                              $salida.="<TH align=\"center\" colspan=\"3\">MEDICAMENTOS SUSPENDIDOS</TH>";
                         }
                         else
                         {
                              $salida.="<TH align=\"center\" colspan=\"3\">MEDICAMENTOS FINALIZADOS</TH>";
                         }
                         $salida.="</tr>";
               
                         $salida.="<TR>";
                         $salida.="<TH>CODIGO</TH>";
                         $salida.="<TH>PRODUCTO</TH>";
                         $salida.="<TH>PRINCIPIO ACTIVO</TH>";
                         $salida.="</TR>";
                    }
                    
                    $salida.="<TBODY>";
                    $salida.="<TR>";
                    if($vectorMSF[$i][item] == 'NO POS')
                    {
                         $salida.="<TD width=\"17%\" align=\"center\">".$vectorMSF[$i][codigo_producto]." - NO POS</TD>";
                    }
                    else
                    {
                         $salida.="<TD width=\"17%\" align=\"center\">".$vectorMSF[$i][codigo_producto]."</TD>";
                    }
          
                    $salida.="<TD width=\"53%\" align=\"justify\"><b>".$vectorMSF[$i][producto]." - ".$vectorMSF[$i][evolucion_id]."</b></TD>";
                    $salida.="<TD width=\"30%\" align=\"justify\">".$vectorMSF[$i][principio_activo]."</TD>";
                    $salida.="</TR>";
                    $salida.="</TBODY>";
                    
                    if ($vectorMSF[$i][sw_estado] != $vectorMSF[$i+1][sw_estado] )
                    {
                         $salida.="</table><br>";
                    }
               }
          }
          return $salida;
    }
    
     /*		GetConsultaSubmodulo
     *
     *		Realiza la consulta de datos a partir de parametros como los datos 
     *		del paciente y el tipo de impresion a realizar.
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@param integer => ingreso, evolucion_id, paciente_id, tipoidpaciente.
     */

    function GetConsultaSubmodulo($Paramdatos, $ParamTipo)
    {
          GLOBAL $ADODB_FETCH_MODE;
          list($dbconn) = GetDBconn();

          switch($ParamTipo)
          {
               case '1':
               $query="SELECT a.sw_estado, k.sw_uso_controlado,
                         CASE WHEN k.sw_pos = 1 THEN 'POS'
                         ELSE 'NO POS' END AS item, a.codigo_producto, 
                         a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
                         a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                         h.descripcion as producto, c.descripcion AS principio_activo, 
                         h.contenido_unidad_venta, l.descripcion, a.evolucion_id, 
                         a.sw_ambulatorio, ter.nombre_tercero
                         FROM hc_medicamentos_recetados_hosp AS a
                         LEFT JOIN hc_vias_administracion AS m ON(a.via_administracion_id = m.via_administracion_id),
                         inv_med_cod_principios_activos AS c, inventarios_productos AS h, 
                         medicamentos AS k, unidades AS l, hc_evoluciones n
                         LEFT JOIN profesionales_usuarios pusu ON(pusu.usuario_id=n.usuario_id)
                         LEFT JOIN terceros ter ON(pusu.tipo_tercero_id=ter.tipo_id_tercero AND pusu.tercero_id=ter.tercero_id)
                         WHERE n.evolucion_id = ".$Paramdatos[evolucion]."
                         AND a.evolucion_id = n.evolucion_id 
                         AND a.sw_estado = '1'
                         AND k.cod_principio_activo = c.cod_principio_activo
                         AND h.codigo_producto = k.codigo_medicamento
                         AND a.codigo_producto = h.codigo_producto
                         AND h.codigo_producto = a.codigo_producto 
                         AND h.unidad_id = l.unidad_id
                         ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id;";
                              
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
			}
               while($medicamentos_hosp = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $medicamentos_hosp;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
			break;
                              
               case '2':
               $query="SELECT a.sw_estado, k.sw_uso_controlado,
                         CASE WHEN k.sw_pos = 1 THEN 'POS'
                         ELSE 'NO POS' END AS item, a.codigo_producto, 
                         a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
                         a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                         h.descripcion as producto, c.descripcion AS principio_activo, 
                         h.contenido_unidad_venta, l.descripcion, a.evolucion_id, 
                         a.sw_ambulatorio, ter.nombre_tercero
                         FROM hc_medicamentos_recetados_hosp AS a
                         LEFT JOIN hc_vias_administracion AS m ON(a.via_administracion_id = m.via_administracion_id),
                         inv_med_cod_principios_activos AS c, inventarios_productos AS h, 
                         medicamentos AS k, unidades AS l, hc_evoluciones n
                         LEFT JOIN profesionales_usuarios pusu ON(pusu.usuario_id=n.usuario_id)
                         LEFT JOIN terceros ter ON(pusu.tipo_tercero_id=ter.tipo_id_tercero AND pusu.tercero_id=ter.tercero_id)
                         WHERE n.ingreso = ".$Paramdatos[ingreso]."
                         AND a.evolucion_id = n.evolucion_id 
                         AND a.sw_estado = '1'
                         AND k.cod_principio_activo = c.cod_principio_activo
                         AND h.codigo_producto = k.codigo_medicamento
                         AND a.codigo_producto = h.codigo_producto
                         AND h.codigo_producto = a.codigo_producto 
                         AND h.unidad_id = l.unidad_id
                         ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id;";
                              
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($medicamentos_hosp = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $medicamentos_hosp;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;

               case '3':
               $query="SELECT a.sw_estado, k.sw_uso_controlado,
                         CASE WHEN k.sw_pos = 1 THEN 'POS'
                         ELSE 'NO POS' END AS item, a.codigo_producto, 
                         a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
                         a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                         h.descripcion as producto, c.descripcion AS principio_activo, 
                         h.contenido_unidad_venta, l.descripcion, a.evolucion_id, 
                         a.sw_ambulatorio, ter.nombre_tercero
                         FROM hc_medicamentos_recetados_hosp AS a
                         LEFT JOIN hc_vias_administracion AS m ON(a.via_administracion_id = m.via_administracion_id),
                         inv_med_cod_principios_activos AS c, inventarios_productos AS h, 
                         medicamentos AS k, unidades AS l, hc_evoluciones n
                         LEFT JOIN profesionales_usuarios pusu ON(pusu.usuario_id=n.usuario_id)
                         LEFT JOIN terceros ter ON(pusu.tipo_tercero_id=ter.tipo_id_tercero AND pusu.tercero_id=ter.tercero_id)
                         WHERE n.ingreso = ".$Paramdatos[ingreso]."
                         AND a.evolucion_id = n.evolucion_id 
                         AND a.sw_estado = '1'
                         AND k.cod_principio_activo = c.cod_principio_activo
                         AND h.codigo_producto = k.codigo_medicamento
                         AND a.codigo_producto = h.codigo_producto
                         AND h.codigo_producto = a.codigo_producto 
                         AND h.unidad_id = l.unidad_id
                         ORDER BY k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id;";
                              
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;               
               $resultado = $dbconn->Execute($query);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($medicamentos_hosp = $resultado->FetchRow())
               {
                    $XML_Consulta[] = $medicamentos_hosp;
               }
               $salida = $this->GetXML_Local($XML_Consulta);
			return $salida;
               break;
               
               case '4':
/*               $sql="SELECT ingreso
               	 FROM ingresos
                     WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
                     AND paciente_id='".$Paramdatos[paciente_id]."'
                     ORDER BY ingreso DESC;";
               $resulta = $dbconn->Execute($sql);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($data = $resulta->FetchRow())
               {
                    $ingreso[] = $data;
               }

               if(!empty($ingreso))
			{
				for($i=0; $i<sizeof($ingreso); $i++)
				{
                          $query="SELECT evolucion_id,
                                        descripcion,
                                        enfermedadactual,
                                        usuario_id,
                                        fecha_registro,
                                        ingreso
                              FROM hc_motivo_consulta
                              WHERE ingreso=".$ingreso[$i][0].";";
                         $resultado = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         while($motivo = $resultado->FetchRow())
                         {
                              $XML_Consulta[] = $motivo;
                         }
                    }
               }
                    
               $salida = $this->GetXML_Local($XML_Consulta);*/
			return true;
               break;

               case '5':
/*               $sql="SELECT ingreso
               	 FROM ingresos
                     WHERE tipo_id_paciente='".$Paramdatos[tipoidpaciente]."'
                     AND paciente_id='".$Paramdatos[paciente_id]."'
                     ORDER BY ingreso DESC;";
               $resulta = $dbconn->Execute($sql);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               while($data = $resulta->FetchRow())
               {
                    $ingreso[] = $data;
               }

               if(!empty($ingreso))
			{
				for($i=0; $i<sizeof($ingreso); $i++)
				{
                          $query="SELECT evolucion_id,
                                        descripcion,
                                        enfermedadactual,
                                        usuario_id,
                                        fecha_registro,
                                        ingreso
                              FROM hc_motivo_consulta
                              WHERE ingreso=".$ingreso[$i][0].";";
                         $resultado = $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                         }
                         while($motivo = $resultado->FetchRow())
                         {
                              $XML_Consulta[] = $motivo;
                         }
                    }
               }
                    
               $salida = $this->GetXML_Local($XML_Consulta);*/
			return true;
               break;

               default:
               return false;                        
           }
    }
    
    
     function Consulta_Solicitud_Medicamentos_Posologia($codigo_producto, $tipo_posologia, $evolucion_id)
     {
          $pfj=$this->frmPrefijo;
          list($dbconnect) = GetDBconn();
          $query == '';
          if ($tipo_posologia == 1)
          {
          	$query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
          if ($tipo_posologia == 2)
          {
               $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto' and a.duracion_id = b.duracion_id";
          }
          if ($tipo_posologia == 3)
          {
	          $query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
          if ($tipo_posologia == 4)
          {
     	     $query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }
          if ($tipo_posologia == 5)
          {
          	$query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$evolucion_id." and codigo_producto = '$codigo_producto'";
          }

          if ($query!='')
          {
               $result = $dbconnect->Execute($query);
               if ($dbconnect->ErrorNo() != 0)
               {
                    $this->error = "Error al buscar en la consulta de medicamentos recetados";
                    $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
                    return false;
               }
               else
               {
                    if ($tipo_posologia != 4)
                    {
                         while (!$result->EOF)
                         {
                              $vector[]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
                    else
                    {
                         while (!$result->EOF)
                         {
                              $vector[$result->fields[0]]=$result->GetRowAssoc($ToUpper = false);
                              $result->MoveNext();
                         }
                    }
               }
          }
          return $vector;
	}

    
    function BuscarIngreso($evolucion)
    {
          list($dbconn) = GetDBconn();
          if(!empty($evolucion))
          {
               $query="SELECT ingreso
                    FROM hc_evoluciones
                    WHERE evolucion_id = $evolucion;";
          }
          $resultado = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          list($this->ingreso) = $resultado->FetchRow();
          return true;
    }

     
     function Consulta_Solicitud_Medicamentos_Historial($codigo_producto, $ingreso, $evolucion)
     {
          $pfj=$this->frmPrefijo;
          if(!empty($ingreso))
          {
          	$this->ingreso = $ingreso;
          }
          elseif(!empty($evolucion))
          {
          	$this->BuscarIngreso($evolucion);
          }
          
	     list($dbconnect) = GetDBconn();
          $query= "select o.nombre, n.fecha, o.tipo_profesional,  a.sw_estado, k.sw_uso_controlado,
                    case when k.sw_pos = 1 then 'POS' else 'NO POS' end as item,
                    a.codigo_producto, a.sw_paciente_no_pos, a.cantidad, a.dosis, m.nombre as via,
                    a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id, h.descripcion
                    as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
                    l.descripcion, a.evolucion_id from hc_medicamentos_recetados_hosp as a left join
                    hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,
          
                    hc_evoluciones n, profesionales o, profesionales_usuarios p
          
                    where n.ingreso = ".$this->ingreso."
                    and a.evolucion_id = n.evolucion_id and
          
                    n.usuario_id = p.usuario_id and
                    p.tipo_tercero_id = o.tipo_id_tercero and
                    p.tercero_id = o.tercero_id and
                    a.sw_estado = '9' and
          
                    a.codigo_producto = '".$codigo_producto."' and
          
                    k.cod_principio_activo = c.cod_principio_activo and
                    h.codigo_producto = k.codigo_medicamento and
                    a.codigo_producto = h.codigo_producto and
                    h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                    order by a.evolucion_id, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto";
     
          $result = $dbconnect->Execute($query);

          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $vector[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $result->Close();
          return $vector;
     }


     function Consulta_Solicitud_Medicamentos_Finalizados_y_Suspendidos($ingreso, $evolucion)
     {
          if(!empty($ingreso))
          {
          	$this->ingreso = $ingreso;
          }
          elseif(!empty($evolucion))
          {
          	$this->BuscarIngreso($evolucion);
          }

          list($dbconnect) = GetDBconn();
          $query= "select a.sw_estado,
                    k.sw_uso_controlado, case when k.sw_pos = 1 then 'POS'
                    else 'NO POS' end as item, a.codigo_producto, a.sw_paciente_no_pos, a.cantidad,
                    a.dosis, m.nombre as via, a.unidad_dosificacion, a.observacion, a.tipo_opcion_posologia_id,
                    h.descripcion as producto, c.descripcion as principio_activo, h.contenido_unidad_venta,
                    l.descripcion, a.evolucion_id from hc_medicamentos_recetados_hosp as a left join
                    hc_vias_administracion as m on (a.via_administracion_id = m.via_administracion_id),
                    inv_med_cod_principios_activos as c, inventarios_productos as h, medicamentos as k,
                    unidades as l,
                    hc_evoluciones n     
                    where n.ingreso = ".$this->ingreso."
                    and a.evolucion_id = n.evolucion_id and
                    (a.sw_estado = '0' or a.sw_estado = '2')  and
                    k.cod_principio_activo = c.cod_principio_activo and
                    h.codigo_producto = k.codigo_medicamento and
                    a.codigo_producto = h.codigo_producto and
                    h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                    order by a.sw_estado, k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto, a.evolucion_id";
     
          $result = $dbconnect->Execute($query);
          if ($dbconnect->ErrorNo() != 0)
          {
               $this->error = "Error al buscar en la consulta de medicamentos recetados";
               $this->mensajeDeError = "Error DB : " . $dbconnect->ErrorMsg();
               return false;
          }
          else
          {
               $i=0;
               while (!$result->EOF)
               {
                    $vector[$i]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
                    $i++;
               }
          }
          $result->Close();
          return $vector;
     }

     
     /*		FechaStamp
     *
     *		Convierte los datos en Fechas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
     */

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

     /*		HoraStamp
     *
     *		Convierte los datos en Horas a partir de la Fecha Registro.
     *
     *		@Author Alexander Giraldo.
     *		@access Public
     *		@param integer => fecha_registro
     */
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
     
     /*		GetDatosUsuarioSistema
     *
     *		Obtiene el nombre de usuario del sistema
     *
     *		@Author Tizziano Perea O.
     *		@access Public
     *		@return bool
     *		@param integer => usuario_id
     */
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
     }/// GetDatosUsuarioSistema


}//fin de la clase

?>
