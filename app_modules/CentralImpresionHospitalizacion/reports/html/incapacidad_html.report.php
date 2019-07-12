<?php

/**
 * $Id: incapacidad_html.report.php,v 1.2 2009/12/01 13:43:54 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Reporte de prueba formato HTML
 */

class incapacidad_html_report
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
    function incapacidad_html_report($datos=array())
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
  		$datos = $this->ReporteIncapacidadMedica();
		$especialidad = $this->ObtenerProfesionalesEspecialidades($datos[0][usuario_id]);
  		$fechaI=$this->FechaStampT($datos[0][fecha_nacimiento]);
  		$fechaF=$this->FechaStampT($datos[0][fecha_cierre]);
  		$fechaIngreso=$this->FechaStamp($datos[0][fecha_ingreso]);
  		$fechaEvolucion=$this->FechaStamp($datos[0][fecha_cierre]);
  		$edad=CalcularEdad($fechaI,$fechaF);

      $Salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
      $Salida.="<tr>";
      $Salida.="  <td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">INCAPACIDAD MEDICA</td>";
      $Salida.="</tr>";
      for($t=1; $t<3;$t++)
      {
        $Salida.="<tr>";
        $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
        $Salida.="</tr>";
      }

		$Salida.="<table width=\"100%\" align=\"center\" border=\"1\" class=\"normal_10\" rules=\"all\">\n";
		$Salida.="<tr>\n";
		$Salida.="<td ALIGN=\"JUSTIFY\" WIDTH=\"25%\">IDENTIFICACION:&nbsp;".$datos[0][tipo_id_paciente]." ".$datos[0]['paciente_id']."</td>\n";
		$Salida.="<td ALIGN=\"JUSTIFY\" WIDTH=\"35%\">NOMBRE:&nbsp;".strtoupper($datos[0]['paciente'])."</td>\n";
		$Salida.="<td ALIGN=\"JUSTIFY\" WIDTH=\"20%\">HC:&nbsp;";
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
		$Salida.="<td ALIGN=\"JUSTIFY\"  WIDTH=\"10%\"><b>EDAD:</b>&nbsp;".$edad['anos']."&nbsp;Años</td>\n";
		$Salida .="<td ALIGN=\"JUSTIFY\" WIDTH=\"10%\"><b>SEXO:</b>&nbsp;".$datos[0]['sexo_id']."</td>\n";
		$Salida.="</tr>\n";
		$Salida.= "<TR>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" ><b>FECHA DE INGRESO:</b>&nbsp;&nbsp;".$fechaIngreso."</TD>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" ><b>No. INGRESO:</b>&nbsp;&nbsp;".$datos[0][ingreso]."</TD>";
		$Salida.= "<TD ALIGN=\"JUSTIFY\" colspan=\"3\"><b>FECHA DE SOLICITUD:</b>&nbsp;&nbsp;".$fechaEvolucion."</TD>";
		$Salida.= "</TR>";
		$Salida.= "<TR>";
		$Salida .= "<td ALIGN=\"JUSTIFY\" ><b>CLIENTE:</b>&nbsp;&nbsp;".strtoupper($datos[0][cliente])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" ><b>PLAN:</b>&nbsp;&nbsp;".strtoupper($datos[0][plan_descripcion])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" ><b>TIPO AFILIADO:</b>&nbsp;&nbsp;".strtoupper($datos[0][tipo_afiliado_nombre])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" colspan=\"2\"><b>RANGO:</b>&nbsp;".$datos[0][rango]."</td>\n";
		$Salida .= "</tr>\n";

    $Salida.= "<TR>";
		$Salida .= "<td ALIGN=\"JUSTIFY\" colspan=\"2\"><b>DEPENDENCIA</b>:&nbsp;".strtoupper($datos[0]['descripcion_dependencia'])."</td>\n";
		$Salida .= "<td ALIGN=\"JUSTIFY\" colspan=\"3\"><b>CIUDAD DONDE LABORA</b>:&nbsp;&nbsp;".strtoupper($datos[0]['ciudad_laboral'])."</td>\n";
		$Salida .= "</tr>\n";

		$Salida.= "</table>\n<br><br>";
		$Salida.="</td>";
		$Salida.="</tr>";
/********************************/
		$Salida.="<tr>";
		$Salida.="<td colspan=\"4\">";
		$Salida.="<table width=\"100%\">";          
          for($t=1; $t<3;$t++)
          {
               $Salida.="<tr>";
               $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
               $Salida.="</tr>";
          }
          $Salida.="<tr>";
          $Salida.="<td class=\"Normal_10N\" align=\"center\" width=\"100%\" colspan=\"4\">".strtoupper($datos[0][tipo_incapacidad_descripcion])."</td>";
          $Salida.="</tr>";
          for($t=1; $t<3;$t++)
          {
               $Salida.="<tr>";
               $Salida.="<td colspan=\"4\" width=\"100%\"></td>";
               $Salida.="</tr>";
          }

          $Salida.="<tr>";
          $fecha_ini = $this->FechaStamp($datos[0][fecha_inicio]);
          $Salida.="  <td class=\"Normal_10N\" align=\"left\" width=\"25%\">SERVICIO:<br>FECHA DE EMISION:<br>FECHA DE TERMINACION:<br>DURACION:</td>";
          $Salida.="  <td class=\"Normal_10\" align=\"left\" width=\"75%\">".$datos[0][servicio]."<br>".$fecha_ini."<br>".$datos[0][fecha_terminacion]."<br>".$datos[0][dias_de_incapacidad].' dias'."</td>";
          $Salida.="</tr>";


				for($t=1; $t<3;$t++)
				{
					$Salida.="<tr>";
					$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
					$Salida.="</tr>";
				}
				if($datos[0][observacion_incapacidad]!='')
				{
				  $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">".'OBSERVACION : '.$datos[0][observacion_incapacidad]."</td></tr>";
				}
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

				foreach ($datos[0][diagnostico_ingreso] as $k => $v)
				{
				   $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">$v[diagnostico_id]. - .$v[diagnostico_nombre]</td></tr>";
				}
        foreach ($datos[0][diagnostico_egreso] as $k => $v)
				{
           $Salida.="<tr><td class=\"Normal_10\" align=\"left\" width=\"100%\" colspan=\"4\">$v[diagnostico_id]. - .$v[diagnostico_nombre]</td></tr>";
				}
				for($t=1; $t<3;$t++)
				{
					$Salida.="<tr>";
					$Salida.="<td colspan=\"4\" width=\"100%\"></td>";
					$Salida.="</tr>";
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
                $Salida.="<tr>";
				$Salida.="<TD ALIGN=\"LEFT\" ><IMG SRC='images/firmas_profesionales/".$datos[0]['firma']."'></td>";
				$Salida.="</tr>";
				$largo = strlen($datos[0][nombre_tercero]);
				$cad = '___';
				for ($l=0; $l<$largo; $l++)
				{
					$cad = $cad.'_';
				}

				if($datos[0][tarjeta_profesional] != '')
				{
					$Salida.="<tr class=\"Normal_10N\">";
					$Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\" colspan = 4>".$cad."<br>".strtoupper($datos[0][nombre_tercero])."<br>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id].' T.P.: '.$datos[0][tarjeta_profesional]."<br>".$especialidad['descripcion']."</td>";
					$Salida.="</tr>";
				}
				else
				{
					$Salida.="<tr class=\"Normal_10N\">";
					$Salida.="<td align=\"left\" class=\"modulo_list_claro\" width=\"100%\" colspan = 4>".$cad."<br>".strtoupper($datos[0][nombre_tercero])."<br>".$datos[0][tipo_id_medico].': '.$datos[0][medico_id]."<br>".$datos[0][tipo_profesional]."<br>".$especialidad['descripcion']."</td>";
					$Salida.="</tr>";
				}
                    $Salida.="</table>";          
                    $Salida.="</td>";
				$Salida.="</tr>";

				return $Salida;
 }



//AQUI TODOS LOS METODOS QUE USTED QUIERA

     function ReporteIncapacidadMedica()
     {  //QUEDA PENDIENTE CUADRAR LOS QUERYS DE ESTE REPORTE.
          list($dbconn) = GetDBconn();
		  $query= "SELECT	btrim(y.primer_nombre||' '||y.segundo_nombre||' '||
                          y.primer_apellido||' '||y.segundo_apellido,'') as paciente,
                          z.tipo_id_paciente, 
                          z.paciente_id, b.ingreso,e.firma,
                          b.fecha_cierre, 
                          z.fecha_ingreso, 
                          p.cama, 
                          y.sexo_id, 
                          y.fecha_nacimiento,
                          q.historia_numero,	
                          q.historia_prefijo,
                          m.descripcion as servicio, 
                          g.rango, 
                          k.tipo_afiliado_nombre,
                          d.nombre_tercero, 
                          d.tipo_id_tercero as tipo_id_medico, 
                          d.tercero_id as
                          medico_id, 
                          e.tarjeta_profesional, 
                          f.descripcion as tipo_profesional,
                          j.nombre_tercero as cliente,  
                          h.plan_descripcion, 
                          a.evolucion_id,
                          a.tipo_incapacidad_id, 
                          n.descripcion as tipo_incapacidad_descripcion,
                          a.observacion_incapacidad, 
                          a.dias_de_incapacidad,
                          b.fecha,
                          b.usuario_id,						  
                          (date(a.fecha_inicio) + (a.dias_de_incapacidad - 1)) as fecha_terminacion,
                          a.fecha_inicio,
                          UD.descripcion_dependencia,
                          a.ciudad_laboral
                  FROM    hc_incapacidades as a 
                          LEFT JOIN uv_dependencias UD
                          ON(UD.codigo_dependencia_id = a.codigo_dependencia_id )	
                          left join hc_evoluciones as b on
                          (a.evolucion_id= b.evolucion_id) 
                          left join ingresos z on (b.ingreso=z.ingreso)

          left join movimientos_habitacion p
          on (z.ingreso = p.ingreso and p.fecha_egreso ISNULL)

          left join pacientes y on
          (z.tipo_id_paciente = y.tipo_id_paciente and  z.paciente_id = y.paciente_id)

          left join historias_clinicas q on ((y.paciente_id= q.paciente_id
          and y.tipo_id_paciente = q.tipo_id_paciente))

          left join profesionales_usuarios as c on
          (b.usuario_id = c.usuario_id) left join terceros as d on
          (c.tipo_tercero_id = d.tipo_id_tercero AND c.tercero_id = d.tercero_id)
          left join profesionales as e on (c.tipo_tercero_id = e.tipo_id_tercero
          AND c.tercero_id = e.tercero_id) left join tipos_profesionales as f on
          (e.tipo_profesional = f.tipo_profesional) left join cuentas as g on
          (b.numerodecuenta = g.numerodecuenta) left join planes as h on
          (g.plan_id = h.plan_id) left join terceros as j on
          (h.tipo_tercero_id = j.tipo_id_tercero AND h.tercero_id = j.tercero_id) left join
          tipos_afiliado as k on (g.tipo_afiliado_id = k.tipo_afiliado_id) left join
          departamentos as l on (l.departamento = z.departamento)
          left join servicios as m on  (l.servicio = m.servicio), hc_tipos_incapacidad as n

          WHERE a.evolucion_id = ".$this->datos['evolucion_id']."
          and a.tipo_incapacidad_id = n.tipo_incapacidad_id";


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
          $var[0][razon_social]=$_SESSION['CENTRALHOSP']['NOM_EMPRESA'];
          $var[0][tipo_id_tercero]=$_SESSION['CENTRALHOSP']['TIPO'];
          $var[0][id]=$_SESSION['CENTRALHOSP']['ID'];

          if($var[0][fecha])
          {
               $fech = strtok ($var[0][fecha],"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               $var[0][fecha] = ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
          }

          if($var[0][fecha_terminacion])
          {
               $fech = strtok ($var[0][fecha_terminacion],"-");
               for($l=0;$l<3;$l++)
               {
                    $date[$l]=$fech;
                    $fech = strtok ("-");
               }
               $var[0][fecha_terminacion] = ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
          }

		$queryD = "select b.diagnostico_id, b.diagnostico_nombre
                     FROM hc_incapacidades as a, diagnosticos as b
                     WHERE a.diagnostico_id = b.diagnostico_id and
                     a.evolucion_id=".$this->datos['evolucion_id']."";
          $result = $dbconn->Execute($queryD);
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
          $var[0][diagnostico_ingreso] = $dingreso;
          
          if(empty($dingreso))
          {
          	unset($dingreso);
               
               $query = "select b.diagnostico_id, b.diagnostico_nombre
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
     
               $query = "select b.diagnostico_id, b.diagnostico_nombre
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
          }
          
          return $var;
	//----------------fin reporte de claudia
	 }

	/**
    * Funcion donde se obtiene la Especialidad del Profesional
    *
    * @param integer $usuario Identificador del usuario
    *
    * @return mixed
    */
    function ObtenerProfesionalesEspecialidades($usuario)
    {
      list($dbconn) = GetDBconn();
	  
	  $sql  = "SELECT C.descripcion ";
      $sql .= "FROM   profesionales A ";
      $sql .= "       LEFT JOIN profesionales_especialidades B ";
      $sql .= "       ON( A.tipo_id_tercero = B.tipo_id_tercero AND ";
      $sql .= "           A.tercero_id = B.tercero_id ) ";
      $sql .= "       LEFT JOIN especialidades C ";
      $sql .= "       ON( B.especialidad = C.especialidad) ";
      $sql .= "WHERE  A.usuario_id = ".$usuario." ";
      
      $datos = array();
      $rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      
      if(!$rst->EOF)
      {
        $datos =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
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
