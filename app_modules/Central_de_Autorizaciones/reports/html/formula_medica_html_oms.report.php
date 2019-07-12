<?php

/**
 * $Id: formula_medica_html.report.php,v 1.3 2009/11/24 15:12:30 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de formulamedica en formato html
 */


//Este reporte es usado desde la central de autorizaciones(modulo
//desde el cual se imprimen las formulas de los pacientes de consulta externa.)
//segun la orden se puede generar cuatro tipos distintos de
//formulas (pos, no pos justificados , no pos a peticion del paciente y de uso controlado)
//

//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class formula_medica_html_oms_report
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
     function formula_medica_html_oms_report($datos=array())
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
    //FUNCION CrearReporte()
    //FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    function CrearReporte()
    {
      $datos = $this->ReporteFormulaMedica();
      $edad=CalcularEdad($datos[0][fecha_nacimiento],$datos[0][fecha_cierre]);
      
      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
      $Salida.="<tr>";
      if ($datos[0][uso_controlado]==1)
      {
		    $Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">FORMULA MEDICA PARA DESPACHO DE MEDICAMENTOS DE USO CONTROLADO</td>";
      }
      else
      {
       	$Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">FORMULA MEDICA</td>";
      }
      $Salida.="</tr>";
      for($t=1; $t<3;$t++)
      {
        $Salida.="<tr>";
        $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
        $Salida.="</tr>";
      }
      //DATOS DEL PACIENTE

      $Salida.="<tr>";
      $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\">No. EVOLUCION :<br>FECHA DE IMPRESION:</td>";
      $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" >".$datos[0][evolucion_id]."<br>".date('d/m/Y h:i')."</td>";
      $Salida.="</tr>";
      $Salida.="<tr>";
      $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\">IDENTIFICACION:<br>PACIENTE:<br>EDAD:<br>SEXO:<br>CLIENTE:<br>PLAN:<br>TIPO AFILIADO:</td>";
      $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\">".$datos[0][tipo_id_paciente]." : ".$datos[0][paciente_id]."<br>".strtoupper($datos[0][paciente])."<br>".$edad['anos']." Años<br>".$datos[0][sexo_id]."<br>".strtoupper($datos[0][cliente])."<br>".strtoupper($datos[0][plan_descripcion])."<br>".strtoupper($datos[0][tipo_afiliado_nombre])." - RANGO: ".$datos[0][rango]."</td>";
      $Salida.="</tr>";

      if ($datos[0][uso_controlado]==1)
      {
        $Salida.="<tr>";
        $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">DIRECCION RES.:</td>";
        $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".strtoupper($datos[0][residencia_direccion])."</td>";
        $Salida.="</tr>";
        $Salida.="<tr>";
        $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">TELEFONO RES.:</td>";
        $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".strtoupper($datos[0][residencia_telefono])."</td>";
        $Salida.="</tr>";
      }
      for($t=1; $t<3;$t++)
      {
        $Salida.="<tr>";
        $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
        $Salida.="</tr>";
      }

      if ($datos[0][uso_controlado]==1)
      {
        $subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO.';
      }
      else
      {
        if($datos[0][item]=='POS')
        {
          $subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S).';
        }

        if($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
        {
          $subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICION DEL PACIENTE.';
        }
        elseif($datos[0][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
        {
          $subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S).';
        }
      }

      $Salida.="<tr>";
      $Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">".$subtitulo."</td>";
      $Salida.="</tr>";
      for($t=1; $t<3;$t++)
      {
        $Salida.="<tr>";
        $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
        $Salida.="</tr>";
      }
      IncludeClass("ClaseUtil");
      $ctl = new ClaseUtil();
      
      for($i=0; $i<sizeof($datos);$i++)
      {
        $Salida .= "  <tr>\n";
        $Salida .= "    <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">".($i+1).'. '.strtoupper($datos[$i][producto])." ".strtoupper($datos[$i][concentracion_forma_farmacologica])." ".strtoupper($datos[$i][forma])."</td>\n";
        $Salida .= "  </tr>\n";
        
        if($datos[$i][via]!='')
        {
          $Salida .= "    <tr>\n";
          $Salida .= "      <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">\n";
          $Salida .= "        Via de Administracion : ".$datos[$i][via]."\n";
          $Salida .= "      </td>\n";
          $Salida .= "    </tr>";
        }
        
        //pintar formula para opcion 1 //caso ok
        $caso = "";
        if($datos[$i][tipo_opcion_posologia_id]== 1)
        {
          //$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">cada ".$datos[$i][posologia][0][periocidad_id]." ".$datos[$i][posologia][0][tiempo]."</td></tr>";
          $caso .= "cada ".$datos[$i][posologia][0][periocidad_id]." ".$datos[$i][posologia][0][tiempo];
        }

        //pintar formula para opcion 2 //caso ok
        if($datos[$i][tipo_opcion_posologia_id]== 2)
        {
          //$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[$i][posologia][0][descripcion]."</td></tr>";
          $caso .= $datos[$i][posologia][0][descripcion];
        }

        //pintar formula para opcion 3  //caso ok
        if($datos[$i][tipo_opcion_posologia_id]== 3)
        {
          $momento = '';
          if($datos[$i][posologia][0][sw_estado_momento]== '1')
          {
            $momento = 'antes de ';
          }
          else
          {
            if($datos[$i][posologia][0][sw_estado_momento]== '2')
            {
              $momento = 'durante ';
            }
            else
            {
              if($datos[$i][posologia][0][sw_estado_momento]== '3')
              {
                $momento = 'despues de ';
              }
            }
          }
          $Cen = $Alm = $Des= '';
          $cont= 0;
          $conector = '  ';
          $conector1 = '  ';
          if($datos[$i][posologia][0][sw_estado_desayuno]== '1')
          {
            $Des = $momento.'el Desayuno';
            $cont++;
          }
          if($datos[$i][posologia][0][sw_estado_almuerzo]== '1')
          {
            $Alm = $momento.'el Almuerzo';
            $cont++;
          }
          if($datos[$i][posologia][0][sw_estado_cena]== '1')
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
          //$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$Des.$conector.$Alm.$conector1.$Cen."</td></tr>";
          $caso .= $Des.$conector.$Alm.$conector1.$Cen;
        }
        
        //pintar formula para opcion 4 ok
        if($datos[$i][tipo_opcion_posologia_id]== 4)
        {
          $conector = '  ';
          $frecuencia='';
          $j=0;
          foreach ($datos[$i][posologia] as $k => $v)
          {
            if ($j+1 ==sizeof($datos[$i][posologia]))
            {
              $conector = '  ';
            }
            else
            {
              if ($j+2 ==sizeof($datos[$i][posologia]))
                $conector = ' y ';
              else
                $conector = ' - ';
            }
            $frecuencia = $frecuencia.$k.$conector;
            $j++;
          }
          //$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'a la(s): '.$frecuencia."</td></tr>";
          $caso .= "a la(s): ".$frecuencia;
        }

        //pintar formula para opcion 5 //ok
        if($datos[$i][tipo_opcion_posologia_id]== 5)
        {
          //$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".' '.$datos[$i][posologia][0][frecuencia_suministro]."</td></tr>";
          $caso .= $datos[$i][posologia][0][frecuencia_suministro];
        }
        
        $e=$datos[$i][dosis]/floor($datos[$i][dosis]);
        $Salida .= "    <tr>\n";
        $Salida .= "      <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">\n";
        $Salida .= "        Dosis : ".(($e==1)? floor($datos[$i][dosis]):$datos[$i][dosis])." ".$datos[$i][unidad_dosificacion]." ".$caso."\n";
        $Salida .= "      </td>\n";
        $Salida .= "    </tr>\n";
        
          //pintar cantidad
          $e=$datos[$i][cantidad]/floor($datos[$i][cantidad]);
          
          $valor = $datos[$i][cantidad];
          if($e==1) $valor = floor($valor);
          
          $descr = $datos[$i][descripcion];
          if ($datos[$i][contenido_unidad_venta])
            $descr .= " por ".$datos[$i][contenido_unidad_venta];
                
          $Salida .= "  <tr>\n";
          $Salida .= "    <td colspan=\"4\" class=\"Normal_10\" align=\"left\">\n";
          $Salida .= "      Cantidad : ".$valor." (".$ctl->num2letras($valor,false).") ".$descr."\n";
          $Salida .= "    </td>\n";
          $Salida .= "  </tr>\n";
		  $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Dias de Tratamiento : '.$datos[$i][dias_tratamiento]."</td></tr>";
           
           if ($datos[$i][observacion]!='')
           {
                $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Observacion : '.$datos[$i][observacion]."</td></tr>";
           }

           for($t=1; $t<3;$t++)
           {
                $Salida.="<tr>";
                $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
                $Salida.="</tr>";
           }
      }
      

      
      //lo del diagnostico
      for($t=1; $t<3;$t++)
      {
               $Salida.="<tr>";
               $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
               $Salida.="</tr>";
      }
      if (($datos[0][diagnostico_ingreso]!='') OR ($datos[0][diagnostico_egreso]!=''))
      {
        $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">DIAGNOSTICO(S) : </td></tr>";
      }

      $diagnostico_ingreso = '';
      foreach ($datos[0][diagnostico_ingreso] as $k => $v)
      {
        if($diagnostico_ingreso == '')
        {
       		$diagnostico_ingreso .= $v[diagnostico_id];
        }
     		else
        {
       		$diagnostico_ingreso .= ' - '.$v[diagnostico_id];
        }
      }
       $nombre_diag_ingreso = '';
      foreach ($datos[0][diagnostico_ingreso] as $k => $v)
      {
           if($nombre_diag_ingreso == '')
           {
          $nombre_diag_ingreso .= $v[diagnostico_nombre];
           }
           else
           {
          $nombre_diag_ingreso .= ' - '.$v[diagnostico_nombre];
           }
      }
      
      
      
      if($diagnostico_ingreso != '')
      {
	      $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$diagnostico_ingreso." ".$nombre_diag_ingreso." </td></tr>";
      }

      $diagnostico_egreso = '';
      foreach ($datos[0][diagnostico_egreso] as $k => $v)
      {
           if($diagnostico_egreso == '')
           {
          $diagnostico_egreso .= $v[diagnostico_id];
           }
           else
           {
          $diagnostico_egreso .= ' - '.$v[diagnostico_id];
           }
      }
      
       $nombre_diag_egreso = '';
      foreach ($datos[0][diagnostico_egreso] as $k => $v)
      {
           if($nombre_diag_egreso == '')
           {
          $nombre_diag_egreso .= $v[diagnostico_nombre];
           }
           else
           {
          $nombre_diag_egreso .= ' - '.$v[diagnostico_nombre];
           }
      }
      
      
      
      
      if($diagnostico_egreso != '')
      {
        $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$v[diagnostico_id]." ".$nombre_diag_egreso." </td></tr>";
      }
      for($t=1; $t<3;$t++)
      {
        $Salida.="<tr>";
        $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
        $Salida.="</tr>";
      }
      //fin de los dignosticos

      if(!empty($datos[0][cuota_moderadora][cuota_moderadora]))
      {
       $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'CUOTA MODERADORA:'.$datos[0][cuota_moderadora][cuota_moderadora]."</td></tr>";
      }
          
      $dias_vencimiento = ModuloGetVar('app', 'Central_de_Autorizaciones','vencimiento_formula_medica');
      $x=explode(' ',$datos[0][fecha]);
      $fecha_vencimiento=date("Y-m-d",strtotime("+".($dias_vencimiento-1)." days",strtotime(date($x[0]))));

      $Salida .= "  <tr>\n";
      $Salida .= "    <td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">\n";
      $Salida .= "      VALIDEZ : ".$dias_vencimiento." Dias\n";
      $Salida .= "    </td>\n";
      $Salida .= "  </tr>";
     	$Salida .= "<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'FECHA DE VENCIMIENTO : '.$this->FechaStamp($fecha_vencimiento)."</td></tr>";


          for($t=1; $t<3;$t++)
          {
               $Salida.="<tr>";
               $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
               $Salida.="</tr>";
          }
          $Salida.="<tr><td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">MEDICO TRATANTE :</td></tr>";
          for($t=1; $t<3;$t++)
          {
               $Salida.="<tr>";
               $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
               $Salida.="</tr>";
          }

          //unica parte donde este reporte es diferente con respecto al de hospitalizacion
          //por que el medico que se pinta aqui es el de una evolucion especifica
          //que trae el resultado del query.  y en hospitalizacion quien firma la formula
          //es el medico de la max evolucion cerrada del ingreso.
          $largo = strlen($datos[0][nombre_tercero]);
          $cad = '___';
          for ($l=0; $l<$largo; $l++)
          {
               $cad = $cad.'_';
          }
		  $Salida.="<tr>";
		  $Salida.="<TD ALIGN=\"LEFT\" ><IMG SRC='images/firmas_profesionales/".$datos[0][firma]."' width=\"50%\" height=\"50%\"></td>";
		  $Salida.="</tr>";
          if($datos[0][tarjeta_profesional] != '')
          {
               $Salida.="<tr class=\"Normal_10N\">";
               $Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\" colspan = 4>".$cad."<br>".strtoupper($datos[0][nombre_tercero])."<br>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id].' T.P.: '.$datos[0][tarjeta_profesional]."<br>".$datos[0][tipo_profesional]."</td>";
               $Salida.="</tr>";
          }
          else
          {
               $Salida.="<tr class=\"Normal_10N\">";
               $Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\" colspan = 4>".$cad."<br>".strtoupper($datos[0][nombre_tercero])."<br>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id]."<br>".$datos[0][tarjeta_profesional]."</td>";
               $Salida.="</tr>";
          }
          return $Salida;
    }


	//AQUI TODOS LOS METODOS QUE USTED QUIERA
     function ReporteFormulaMedica()
     {
          $criterio=='';
          $uso_controlado = 0;
          if(($this->datos['sw_paciente_no_pos']==='0') OR ($this->datos['sw_paciente_no_pos']==1))
          {
               $criterio= "AND k.sw_pos = '".$this->datos['sw_pos']."' AND a.sw_paciente_no_pos = '".$this->datos['sw_paciente_no_pos']."'";
          }
          elseif($this->datos['sw_pos']=='1')
          {
               $criterio= "AND k.sw_pos = '".$this->datos['sw_pos']."'";
          }
          if ($criterio == '' AND $this->datos['sw_uso_controlado']=='1')
          {
               $criterio = "AND k.sw_uso_controlado = '".$this->datos['sw_uso_controlado']."'";
               $uso_controlado = 1;
          }

          list($dbconn) = GetDBconn();
          $query="SELECT btrim(w.primer_nombre||' '||w.segundo_nombre||' '||
                         w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
                         w.tipo_id_paciente, w.paciente_id, w.fecha_nacimiento, w.sexo_id,
					     q.firma,
                         n.fecha, n.fecha_cierre, w.residencia_direccion, w.residencia_telefono,
                         v.tipo_afiliado_id, t.plan_id, sw_tipo_plan, s.rango,
                         v.tipo_afiliado_nombre, p.nombre_tercero,	u.nombre_tercero as cliente,
                         r.descripcion as tipo_profesional, p.tipo_id_tercero as tipo_id_medico,
                         p.tercero_id as	medico_id, q.tarjeta_profesional,	t.plan_descripcion,
                         a.evolucion_id, case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
                         a.sw_paciente_no_pos, a.codigo_producto,  h.descripcion as producto,
                         c.descripcion as principio_activo, m.nombre as via, a.dosis,
                         a.unidad_dosificacion, a.tipo_opcion_posologia_id, 
                         CASE WHEN  a.cantidadperiocidad  IS NOT NULL THEN  a.cantidadperiocidad 
                           ELSE a.cantidad END AS cantidad, 
                         l.descripcion,
                         h.contenido_unidad_venta,	a.observacion,
						 a.dias_tratamiento,
                         k.concentracion_forma_farmacologica,
                         forma.descripcion as forma
                         
          
                         FROM hc_medicamentos_recetados_amb as a left join hc_vias_administracion as m
                         on (a.via_administracion_id = m.via_administracion_id)
                         left join hc_evoluciones as n on (a.evolucion_id= n.evolucion_id) left join
                         profesionales_usuarios as o on (n.usuario_id = o.usuario_id) left join
                         terceros as p	on (o.tipo_tercero_id = p.tipo_id_tercero AND
                         o.tercero_id = p.tercero_id) left join	profesionales as q on
                         (o.tipo_tercero_id = q.tipo_id_tercero AND o.tercero_id = q.tercero_id)
                         left join tipos_profesionales as r on (q.tipo_profesional = r.tipo_profesional)
                         left join cuentas as s on (n.numerodecuenta = s.numerodecuenta) left join
                         planes as t	on (s.plan_id = t.plan_id) left join terceros as u on
                         (t.tipo_tercero_id = u.tipo_id_tercero AND t.tercero_id	= u.tercero_id)
                         left join tipos_afiliado as v on (s.tipo_afiliado_id = v.tipo_afiliado_id)
                         left join pacientes as w on (w.paciente_id= '".$this->datos['paciente_id']."'
                         and w.tipo_id_paciente = '".$this->datos['tipo_id_paciente']."'),
                         inv_med_cod_principios_activos as c, inventarios_productos as h,
                         medicamentos as k, unidades as l,
                         inv_med_cod_forma_farmacologica forma
          
                         WHERE  a.evolucion_id = ".$this->datos['evolucion_id']."
                         and k.cod_principio_activo = c.cod_principio_activo
                         and h.codigo_producto = k.codigo_medicamento and
                         a.codigo_producto = h.codigo_producto
                         and k.cod_forma_farmacologica=forma.cod_forma_farmacologica
                         and h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
                         ".$criterio." order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $var[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          //$result->Close();
          $var[0][uso_controlado]=$uso_controlado;
          $var[0][razon_social]=$_SESSION['CENTRO']['NOM_EMP'];

          if($_REQUEST['sw_pos']=='1' AND $var[0][sw_tipo_plan]==3)
          {
               if((!empty($var[0][rango])) AND (!empty($var[0][plan_id])) AND
               (!empty($var[0][tipo_afiliado_id])))
               {
                    $query="select cuota_moderadora from planes_rangos
                    where plan_id = ".$var[0][plan_id]."
                    AND tipo_afiliado_id = '".$var[0][tipo_afiliado_id]."'
                    AND rango = '".$var[0][rango]."';";

                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                              $this->error = "Error al Cargar el Modulo";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return false;
                    }
                    else
                    {
                              $cuotam=$result->GetRowAssoc($ToUpper = false);
                    }
                    $var[0][cuota_moderadora]=$cuotam;
               }
          }

          for($i=0;$i<sizeof($var);$i++)
          {
               $query == '';
               unset ($vector);
               if ($var[$i][tipo_opcion_posologia_id] == 1)
               {
                         $query= "select periocidad_id, tiempo from hc_posologia_horario_op1 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 2)
               {
                         $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2 as a, hc_horario as b where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."' and a.duracion_id = b.duracion_id";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 3)
               {
                         $query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 4)
               {
                         $query= "select hora_especifica from hc_posologia_horario_op4 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 5)
               {
                         $query= "select frecuencia_suministro from hc_posologia_horario_op5 where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }

               if ($query!='')
               {
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al buscar en la consulta de medicamentos recetados";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return false;
                    }
                    else
                    {
                         if ($var[$i][tipo_opcion_posologia_id] != 4)
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
               $var[$i][posologia]=$vector;
               unset($vector);
          }

          //parte de los diagnosticos
          $query = "select distinct b.diagnostico_id, b.diagnostico_nombre
                    FROM hc_diagnosticos_ingreso as a, diagnosticos as b
                    WHERE a.tipo_diagnostico_id = b.diagnostico_id and
                    a.evolucion_id=".$this->datos['evolucion_id']."";
     	$result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al consultar los diagnosticos de ingreso";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $dingreso[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $var[0][diagnostico_ingreso]=$dingreso;
          unset($dingreso);

          $query = "select distinct b.diagnostico_id, b.diagnostico_nombre
          FROM hc_diagnosticos_egreso as a, diagnosticos as b
          WHERE a.tipo_diagnostico_id = b.diagnostico_id and
          a.evolucion_id=".$this->datos['evolucion_id']."";
          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al consultar los diagnosticos de egreso";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               while (!$result->EOF)
               {
                    $degreso[]=$result->GetRowAssoc($ToUpper = false);
                    $result->MoveNext();
               }
          }
          $var[0][diagnostico_egreso]=$degreso;
          unset($degreso);
		//fin de los diagnosticos
	     return $var;
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
}
?>

