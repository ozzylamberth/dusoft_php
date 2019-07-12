<?php
//Reporte de Formulacion de medicamentos formato HTML
//este reporte es usado desde la estacion de enfermeria
//se genera dependiendo de los medicamnetos que se hallan
//seleccionado en la lista de chequeo para generar la formula
//
//Un reporte es una clase con el nombre de reporte y el sufijo '_report'
class formula_medica_estacion_html_report
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
    function formula_medica_estacion_html_report($datos=array())
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

	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
     function CrearReporte()
     {
          //*******************************************termino
		$datos = $this->ReporteFormulaMedica_Estacion();
		$Salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";

		//AQUI se colocarian LOS DATOS DE LA EMPRESA
		//FIN DE DATOS DE LA EMPRESA


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
		/*$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">DATOS DEL PACIENTE</td>";
		$Salida.="</tr>";*/

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\">No. INGRESO :<br>FECHA DE IMPRESION:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" >".$datos[0][ingreso]."<br>".date('d/m/Y h:i')."</td>";
		$Salida.="</tr>";

		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\">IDENTIFICACION:<br>PACIENTE:<br>CLIENTE:<br>PLAN:<br>TIPO AFILIADO:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\">".$datos[0][tipo_id_paciente]." : ".$datos[0][paciente_id]."<br>".strtoupper($datos[0][paciente])."<br>".strtoupper($datos[0][cliente])."<br>".strtoupper($datos[0][plan_descripcion])."<br>".strtoupper($datos[0][tipo_afiliado_nombre])." - RANGO: ".$datos[0][rango]."</td>";
		$Salida.="</tr>";
		/*
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">PACIENTE:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".strtoupper($datos[0][paciente])."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">CLIENTE:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".strtoupper($datos[0][cliente])."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">PLAN:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".strtoupper($datos[0][plan_descripcion])."</td>";
		$Salida.="</tr>";
		$Salida.="<tr>";
		$Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\" colspan=\"1\">TIPO AFILIADO:</td>";
		$Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\" colspan=\"3\">".strtoupper($datos[0][tipo_afiliado_nombre])." RANGO ".$datos[0][rango]."</td>";
		$Salida.="</tr>";
*/
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
		for($t=1; $t<6;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

//******************************FOR DE MED*********************************
		$spia = 0;
		for($i=0; $i<sizeof($datos);$i++)
		{
               if ($datos[$i][uso_controlado]==1)
               {
                    $subtitulo = 'MEDICAMENTO(S) DE USO CONTROLADO.';
                    $spia = 1;
               }
               else
               {
                    if($datos[$i][item]=='POS')
                    {
                         $subtitulo = 'MEDICAMENTO(S) POS FORMULADO(S).';
                         $spia = 2;
                    }

                    if($datos[$i][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='1')
                    {
                         $subtitulo = 'MEDICAMENTO(S) NO POS SOLICITADO(S) A PETICION DEL PACIENTE.';
                         $spia = 3;
                    }
                    elseif($datos[$i][item]=='NO POS' AND $datos[0][sw_paciente_no_pos]=='0')
                    {
                         $subtitulo = 'MEDICAMENTO(S) NO POS JUSTIFICADO(S).';
                         $spia = 4;
                    }
               }
               if($spia!= 0 and $analizador != $spia)
               {
                    $Salida.="<tr>";
                    $Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">".$subtitulo."</td>";
                    $Salida.="</tr>";
                    $analizador = $spia;
                    $spia = 0;
               }

			$Salida.="<tr><td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">".($i+1).'. '.strtoupper($datos[$i][producto])."</td></tr>";
		     if($datos[$i][via]!='')
			{
			  $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Via de Administracion : '.$datos[$i][via]."</td></tr>";
			}

		      $e=$datos[$i][dosis]/floor($datos[$i][dosis]);
			if($e==1)
			{
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Dosis : '.floor($datos[$i][dosis]).' '.$datos[$i][unidad_dosificacion]."</td></tr>";
			}
			else
			{
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Dosis : '.$datos[$i][dosis].' '.$datos[$i][unidad_dosificacion]."</td></tr>";
			}

      //pintar formula para opcion 1 //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 1)
			{
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">cada ".$datos[$i][posologia][0][periocidad_id]." ".$datos[$i][posologia][0][tiempo]."</td></tr>";
			}

      //pintar formula para opcion 2 //caso ok
			if($datos[$i][tipo_opcion_posologia_id]== 2)
			{
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$datos[$i][posologia][0][descripcion]."</td></tr>";
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
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".$Des.$conector.$Alm.$conector1.$Cen."</td></tr>";
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
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'a la(s): '.$frecuencia."</td></tr>";
			}

			//pintar formula para opcion 5 //ok
			if($datos[$i][tipo_opcion_posologia_id]== 5)
			{
				$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".' '.$datos[$i][posologia][0][frecuencia_suministro]."</td></tr>";
			}
	    //pintar cantidad
			$e=$datos[$i][cantidad]/floor($datos[$i][cantidad]);
			if ($datos[$i][contenido_unidad_venta])
			{
				if($e==1)
				{
					$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</td></tr>";
				}
				else
				{
					$Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion].' por '.$datos[$i][contenido_unidad_venta]."</td></tr>";
				}
			}
			else
			{
				if($e==1)
				{
				  $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Cantidad : '.floor($datos[$i][cantidad]).' '.$datos[$i][descripcion]."</td></tr>";
				}
				else
				{
				  $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'Cantidad : '.$datos[$i][cantidad].' '.$datos[$i][descripcion]."</td></tr>";
				}
			}
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

          if(!empty($datos[0][cuota_moderadora][cuota_moderadora]))
          {
               $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'CUOTA MODERADORA:'.$datos[0][cuota_moderadora][cuota_moderadora]."</td></tr>";
          }

		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

		$Salida.="<tr><td class=\"Normal_10N\" align=\"left\" width=\"100%\" colspan=\"4\">MEDICO TRATANTE:</td></tr>";
		for($t=1; $t<3;$t++)
		{
			$Salida.="<tr>";
			$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
			$Salida.="</tr>";
		}

		//medico de la ultima evolucion cerrada del ingreso - caso especial para hospitalizacion
     	$largo = strlen($datos[0][medico_evol_max][nombre_tercero]);
		$cad = '___';
		for ($l=0; $l<$largo; $l++)
		{
      		$cad = $cad.'_';
    		}

		if($datos[0][medico_evol_max][tarjeta_profesional] != '')
		{
			$Salida.="<tr class=\"Normal_10N\">";
			$Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"50%\">".$cad."<br>".strtoupper($datos[0][medico_evol_max][nombre_tercero])."<br>".$datos[0][medico_evol_max][tipo_id_medico].': '.$datos[0][medico_evol_max][medico_id].' T.P.: '.$datos[0][medico_evol_max][tarjeta_profesional]."<br>".$datos[0][medico_evol_max][tipo_profesional]."</td>";
			$Salida.="</tr>";
		}
		else
		{
			$Salida.="<tr class=\"Normal_10N\">";
			$Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"50%\">".$cad."<br>".strtoupper($datos[0][medico_evol_max][nombre_tercero])."<br>".$datos[0][medico_evol_max][tipo_id_medico].': '.$datos[0][medico_evol_max][medico_id]."<br>".$datos[0][medico_evol_max][tipo_profesional]."</td>";
			$Salida.="</tr>";
		}
          $Salida.="</table>";
  		return $Salida;
 	}

     
     //AQUI TODOS LOS METODOS QUE USTED QUIERA
     function ReporteFormulaMedica_Estacion()
     {
	     //condicion para el reporte de los medicamentos seleccionados
          if(sizeof($this->datos['op'])>0)
          {
               $search="";
               $union= "";
               $arr = $this->datos['op'];
               $indice = 1;
               foreach($arr as $x=>$y)
               {
                    $vector=explode (",",$y);
                    if($indice==1)
                    {
                         $union = ' and  ((';
                    }
                    else
                    {
                         $union = ' or (';
                    }
                    $search.= "$union a.codigo_producto= '".$vector[0]."' and a.evolucion_id= ".$vector[1].")";
                    $indice++;
               }
               $search.=")";
          }
          else
          {
               $AND="";
               $search="";
          }
     	//fin de la condicion
     
          list($dbconn) = GetDBconn();
          $query="SELECT	btrim(w.primer_nombre||' '||w.segundo_nombre||' '||
          w.primer_apellido||' '||w.segundo_apellido,'') as paciente,
          w.tipo_id_paciente, w.paciente_id,

          n.ingreso, n.fecha, w.residencia_direccion, w.residencia_telefono,
          v.tipo_afiliado_id, t.plan_id, sw_tipo_plan, s.rango,
          v.tipo_afiliado_nombre, p.nombre_tercero,	u.nombre_tercero as cliente,
          r.descripcion as tipo_profesional, p.tipo_id_tercero as tipo_id_medico,
          p.tercero_id as	medico_id, q.tarjeta_profesional,	t.plan_descripcion,
          a.evolucion_id, case when k.sw_pos = 1 then 'POS'	else 'NO POS' end as item,
          a.sw_paciente_no_pos, a.codigo_producto,  h.descripcion as producto,
          c.descripcion as principio_activo, m.nombre as via, a.dosis,
          a.unidad_dosificacion, a.tipo_opcion_posologia_id, a.cantidad, l.descripcion,
          h.contenido_unidad_venta,	a.observacion

          FROM hc_medicamentos_recetados_hosp as a left join hc_vias_administracion as m
          on (a.via_administracion_id = m.via_administracion_id)
          left join hc_evoluciones as n on (a.evolucion_id= n.evolucion_id) left join
          profesionales_usuarios as o on (n.usuario_id = o.usuario_id) left join
          terceros as p	on (o.tipo_tercero_id = p.tipo_id_tercero AND
          o.tercero_id = p.tercero_id) left join profesionales as q on
          (o.tipo_tercero_id = q.tipo_id_tercero AND o.tercero_id = q.tercero_id)
          left join tipos_profesionales as r on (q.tipo_profesional = r.tipo_profesional)
          left join cuentas as s on (n.numerodecuenta = s.numerodecuenta) left join
          planes as t	on (s.plan_id = t.plan_id) left join terceros as u on
          (t.tipo_tercero_id = u.tipo_id_tercero AND t.tercero_id	= u.tercero_id)
          left join tipos_afiliado as v on (s.tipo_afiliado_id = v.tipo_afiliado_id)
          left join pacientes as w on (w.paciente_id= '".$this->datos['datos_estacion']['paciente_id']."'
          and w.tipo_id_paciente = '".$this->datos['datos_estacion']['tipo_id_paciente']."'),
          inv_med_cod_principios_activos as c, inventarios_productos as h,
          medicamentos as k, unidades as l

          WHERE n.estado = '0' and a.sw_estado = '1' and
          k.cod_principio_activo = c.cod_principio_activo
          and h.codigo_producto = k.codigo_medicamento and
          a.codigo_producto = h.codigo_producto
          and h.codigo_producto = a.codigo_producto and h.unidad_id = l.unidad_id
          $search	order by k.sw_pos, a.sw_paciente_no_pos, a.codigo_producto;";

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
          $var[0][razon_social]=$_SESSION['ESTACION_ENFERMERIA']['EMP'];

		//obteniendo la cuota moderadora solo para cuando el plan es = 3 y sw_pos = 1
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

          //obteniendo la posologia para cada medicamento DE HOSPITALIZACION que se va a imprimir en la formula medica.
          for($i=0;$i<sizeof($var);$i++)
          {
               $query == '';
               unset ($vector);
               if ($var[$i][tipo_opcion_posologia_id] == 1)
               {
                    $query= "select periocidad_id, tiempo from hc_posologia_horario_op1_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 2)
               {
                    $query= "select a.duracion_id, b.descripcion from hc_posologia_horario_op2_hosp as a, hc_horario as b where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."' and a.duracion_id = b.duracion_id";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 3)
               {
                    $query= "select sw_estado_momento, sw_estado_desayuno, sw_estado_almuerzo, sw_estado_cena from hc_posologia_horario_op3_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 4)
               {
                    $query= "select hora_especifica from hc_posologia_horario_op4_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
               }
               if ($var[$i][tipo_opcion_posologia_id] == 5)
               {
                    $query= "select frecuencia_suministro from hc_posologia_horario_op5_hosp where evolucion_id = ".$var[$i][evolucion_id]." and codigo_producto = '".$var[$i][codigo_producto]."'";
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

          //hallando la evolucion maxima  caso especial para pacientes hospitalizados.
          $query= "SELECT a.evolucion_id, c.nombre_tercero,
          c.tipo_id_tercero as tipo_id_medico,
          c.tercero_id as medico_id, d.tarjeta_profesional,
          e.descripcion as tipo_profesional
          FROM hc_evoluciones a, profesionales_usuarios b, terceros c, profesionales d,
          tipos_profesionales e
          WHERE (select max (evolucion_id) from hc_evoluciones
          where ingreso = ".$var[0][ingreso]." and estado ='0') = a.evolucion_id
          and a.usuario_id = b.usuario_id
          and b.tipo_tercero_id = c.tipo_id_tercero AND b.tercero_id = c.tercero_id
          and b.tipo_tercero_id = d.tipo_id_tercero AND b.tercero_id = d.tercero_id
          and d.tipo_profesional = e.tipo_profesional";

          $result = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               $medico_evol_max=$result->GetRowAssoc($ToUpper = false);
          }
          $var[0][medico_evol_max]=$medico_evol_max;
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
     //---------------------------------------
     }

?>
