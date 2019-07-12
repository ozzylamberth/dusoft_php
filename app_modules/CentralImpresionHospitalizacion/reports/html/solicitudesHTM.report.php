<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: solicitudesHTM.report.php,v 1.4 2010/02/23 13:43:22 hugo Exp $ 
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */
  /**
  * Clase Reporte: solicitudesHTM_report 
  * 
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  */
  class solicitudesHTM_report
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
  	function solicitudesHTM_report($datos=array())
  	{
      $this->datos=$datos;
      return true;
  	}


  	function GetMembrete()
  	{
      $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
                          'subtitulo'=>'',
                          'logo'=>'logocliente.png','align'=>'left'));
      return $Membrete;
  	}

      //FUNCION CrearReporte()
  	//FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
  	function CrearReporte()
  	{
      IncludeLib("tarifario_cargos");
      IncludeLib("funciones_central_impresion");
 
      if(!empty($this->datos['evolucion']))
      {
        $datos[0] = EncabezadoReporteEvolucion($this->datos['evolucion'],$this->datos['TipoDocumento'],$this->datos['Documento']);
        $dat = BuscarSolicitudesEvolucion($this->datos['evolucion']);
      }
      else
      {
        $datos[0]=EncabezadoReporteIngreso($this->datos['ingreso'],$this->datos['TipoDocumento'],$this->datos['Documento']);
        if($this->datos['peticion'] == 'SalidaPacientes')
        {
         $dat=BuscarSolicitudesHospitalariasAmbulatorias($this->datos['ingreso']);
        }
        else
        {
  				$dat=BuscarSolicitudesIngreso($this->datos['ingreso']);               
        }
      }
       
      $Salida .="<TABLE BORDER='0' WIDTH='100%' ALIGN='center'>";
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='CENTER' WIDTH='100%' class=\"titulo2\" colspan=\"3\"><b>".strtoupper($datos[0][razon_social])."</b>";
      $Salida.="</TD>";
      $Salida.="</TR>";
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='CENTER' WIDTH='100%' class=\"normal_10\" colspan=\"3\">".$datos[0][tipo_id_tercero].': '.$datos[0][id]."";
      $Salida.="</TD>";
      $Salida.="</TR>";
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='LEFT' WIDTH='25%' class=\"normal_10\">Fecha    : ".date('d/m/Y h:m')."";
      $Salida.="</TD>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Atendio : ".$datos[0][usuario_id].' - '.$datos[0][usuario]."";
      $Salida.="</TD>";
      $Salida.="</TR>";
      $Salida.="</TABLE>";
      $Salida .="<TABLE BORDER='0' WIDTH='100%' ALIGN='center'>";
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\" WIDTH='25%'>Identifi: ".$datos[0][tipo_id_paciente].' '.$datos[0][paciente_id]."";
      $Salida.="</TD>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\" WIDTH='35%'>Paciente: ".$datos[0][nombre]."";
      $Salida.="</TD>";
      $EdadArr=CalcularEdad($datos[0][fecha_nacimiento],'');
      $Edad=$EdadArr['edad_aprox'];
      $Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" WIDTH='20%'>Edad : ".$Edad."&nbsp;&nbsp; Sexo :".$datos[0][sexo_id]."";
      $Salida.="</TD>";
      $hc=$this->Historia($datos[0][tipo_id_paciente],$datos[0][paciente_id]);
      if(empty($hc[prefijo]) AND empty($hc[numero]))
      {  $hc[prefijo]=$datos[0][tipo_id_paciente];   $hc[numero]=$datos[0][paciente_id];  }
      $Salida.="<TD  ALIGN='LEFT' class=\"normal_10\" WIDTH='25%'>HC : ".$hc[prefijo]."".$hc[numero]."";
      $Salida.="</TD>";
      $Salida.="</TR>";
      $Salida.="</TABLE>";
      $Salida .="<TABLE BORDER='0' WIDTH='100%' ALIGN='center'>";
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='LEFT'class=\"normal_10\" WIDTH='25%'>Cliente : ".$datos[0][nombre_tercero]."";
      $Salida.="</TD>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Plan    : ".$datos[0][plan_descripcion]."";
      $Salida.="</TD>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Tipo Afiliado: ".$datos[0][tipo_afiliado_nombre]."";
      $Salida.="</TD>";
      $Salida.="</TR>";
      $Salida.="</TABLE>";
      $Salida .="<TABLE BORDER='0' WIDTH='100%' ALIGN='center'>";
      //$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
      $fech=explode(".",$datos[0][fecha]);
      $pro=$this->Profesional($dat[0][evolucion_id]);
      for($j=0; $j<sizeof($pro); $j++)
      {
                if($j==0)
                {  $espe.=$pro[$j][descripcion];  }
                else
                {  $espe.="   -  ".$pro[$j][descripcion];  }
      }
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\">Profesional: ".$pro[0][nombre_tercero]."";
      $Salida.="</TD>";
      $Salida.="<TD ALIGN='LEFT' class=\"normal_10\" >Especialidad: ".$espe."";
      $Salida.="</TD>";
      $Salida.="</TR>";
	  
      $diag = DiagnosticoCompleto($dat[0][evolucion_id]);
      /*if(!empty($diag))
      {
        $Salida .= "<TR>";
        $Salida .= "  <TD colspan=\"2\">\n";
        $Salida .= "    <table width=\"100%\" align=\"center\" class=\"normal_10\">\n";
        $rgt = sizeof($diag);
        $flag = true;
        foreach($diag as $key => $dtl)
        {
          $salida .= "      <tr >\n";
          if($flag)
          {
            $Salida .= "        <td ".(( $rgt> 1)? "rowspan=\"".$rgt."\"": "")." width=\"10%\">Diagnosticos:</td>\n";
            $flag = false;
          }
          $Salida .= "        <td>".$dtl['diagnostico_id']." - ".$dtl['diagnostico_nombre']."</td>\n";
          $Salida .= "      </tr>\n";
        }
        $Salida .= "    </table>";
        $Salida .= "  </TD>";
        $Salida .= "</TR>";
      }*/
      $Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
      $Salida.="<TR>";
      $Salida.="<TD ALIGN='CENTER' WIDTH='100%' class=\"normal_10N\" colspan=\"2\"><b>SOLICITUD DE SERVICIOS </b>";
      $Salida.="</TD>";
      $Salida.="</TR>";
       $diagS=DiagnosticoSolicitudCompleto($dat[0][hc_os_solicitud_id]);
        if(!empty($diagS))
          $diagnostico=$diagS;
        else
        	$diagnostico=$diag;
        
        $Salida.="<TR>";
        $Salida.="<TD colspan=\"2\"><b>DIAGNOSTICO(S):</b></TD>";
  			$Salida.="</TR>";
        
        foreach($diagnostico as $key => $dtl)
        {
     			$Salida.="<TR>";
     			$Salida.="<TD colspan=\"2\">".$dtl['diagnostico_id']." - ".$dtl['diagnostico_nombre']."</TD>";
      		$Salida.="</TR>";			
  			}
      
      for($i=0; $i<sizeof($dat);$i++)
      {
        $Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
        $inter=$this->Interconsulta($dat[$i][hc_os_solicitud_id]);
        $Salida.="<TR>";
        $Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">".$dat[$i][hc_os_solicitud_id].' - '.$dat[$i][cargos].' - ( '.$dat[$i][cantidad].' )'.$dat[$i][descar].' '.$inter."";
        $Salida.="</TD>";
        $Salida.="</TR>";
        /*$diagS=DiagnosticoSolicitudCompleto($dat[$i][hc_os_solicitud_id]);
        if(!empty($diagS))
          $diagnostico=$diagS;
        else
        	$diagnostico=$diag;
        
        $Salida.="<TR>";
        $Salida.="<TD colspan=\"2\"><b>DIAGNOSTICO(S):</b></TD>";
  			$Salida.="</TR>";
        
        foreach($diagnostico as $key => $dtl)
        {
     			$Salida.="<TR>";
     			$Salida.="<TD colspan=\"2\">".$dtl['diagnostico_id']." - ".$dtl['diagnostico_nombre']."</TD>";
      		$Salida.="</TR>";			
  			}*/
        if(!empty($dat[$i]['horas_estimadas']) || !empty($dat[$i]['minutos_estimados']))
        {
          $Salida .= "<TR class=\"normal_10\">";
          $Salida .= "  <TD colspan=\"2\">\n";
          $Salida .= "    <b>Tiempo estimado de la cirugía: </b>".(($dat[$i]['horas_estimadas'])? $dat[$i]['horas_estimadas']." Horas": "")." ".(($dat[$i]['minutos_estimados'])? $dat[$i]['minutos_estimados']." Minutos":"");
          $Salida .= "  </TD>";
          $Salida .= "</TR>";
        }           
          if(!empty($dat[$i][obsapoyo]))
           {
                     $Salida.="<TR>";
                     $Salida.="<TD colspan=\"2\"  ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observación: ".$dat[$i][obsapoyo]."";
                     $Salida.="</TD>";
                     $Salida.="</TR>";
           }
           if(!empty($dat[$i][obsinter]))
           {
                     $Salida.="<TR>";
                     $Salida.="<TD colspan=\"2\"  ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observación: ".$dat[$i][obsinter]."";
                     $Salida.="</TD>";
                     $Salida.="</TR>";
           }
           if(!empty($dat[$i][obsnoqx]))
           {
                     $Salida.="<TR>";
                     $Salida.="<TD colspan=\"2\" ALIGN='LEFT' WIDTH='100%' class=\"normal_10\">Observación: ".$dat[$i][obsnoqx]."";
                     $Salida.="</TD>";
                     $Salida.="</TR>";
           }
           if(!empty($dat[$i][trap]))
           {
                     $Salida.="<TR>";
                     $Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">".$dat[$i][trap].". días de Tramite";
                     $Salida.="</TD>";
                     $Salida.="</TR>";
           }
           elseif(!empty($dat[$i][tra]))
           {
                     $Salida.="<TR>";
                     $Salida.="<TD ALIGN='LEFT' WIDTH='100%' class=\"normal_10\" colspan=\"2\">".$dat[$i][tra].". días de Tramite";
                     $Salida.="</TD>";
                     $Salida.="</TR>";
           }
      }
	  
	  
	  
      $Salida.="<TR>";
	  $Salida.="<TD ALIGN='LEFT' WIDTH='100%' colspan=\"2\">";
      $Salida.="<IMG SRC='images/firmas_profesionales/".$pro[0][firma]."'>";
	  $Salida.="</TD>";
      $Salida.="</TR>";
	  $Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
	  $Salida.="<TR>";
      $Salida.="<TD ALIGN='LEFT' WIDTH='100%' colspan=\"2\">";
      $Salida.="________________________________________________";
	  $Salida.="</TD>";
      $Salida.="</TR>";
	  $Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
	  
	  
	  if($pro[0][tarjeta_profesional] != '')
      {
        $Salida.="<TR>";
        $Salida.="<TD ALIGN='LEFT' WIDTH='100%' colspan=\"2\">".strtoupper($pro[0][nombre_tercero])."<br>".$pro[0][tipo_id_tercero].': '.$pro[0][tercero_id].' - T.P.: '.$pro[0][tarjeta_profesional].' - '.$pro[0][descripcion]."</TD>";
        $Salida.="</TR>";
		$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
      }
      else
      {
        $Salida.="<TR>";
        $Salida.="<TD ALIGN='LEFT' WIDTH='100%' colspan=\"2\">".strtoupper($pro[0][nombre_tercero])."<br>".$pro[0][tipo_id_tercero].': '.$pro[0][tercero_id]." ".$pro[0][descripcion]."</TD>";
        $Salida.="</TR>";
		$Salida.="<TR><TD colspan=\"2\">&nbsp;</TD></TR>";
      }
      $Salida.="</TABLE>";
      return $Salida;
  	}

      //AQUI TODOS LOS METODOS QUE USTED QUIERA
      //---------------------------------------

      function Profesional($evolucion)
      {
  					list($dbconn) = GetDBconn();
  					$query = "select c.tipo_id_tercero, c.tercero_id, c.nombre_tercero, f.especialidad, g.descripcion, h.tipo_id_tercero,
						h.tarjeta_profesional,h.firma
						from hc_evoluciones as a, profesionales_usuarios as b, terceros as c,
						profesionales_especialidades as f, especialidades as g, profesionales h
						where a.evolucion_id=".$evolucion." and a.usuario_id=b.usuario_id
						and b.tipo_tercero_id=c.tipo_id_tercero and b.tercero_id=c.tercero_id
						and f.tipo_id_tercero=c.tipo_id_tercero and f.tercero_id=c.tercero_id
						and f.especialidad=g.especialidad
						and h.tipo_id_tercero = c.tipo_id_tercero
						and h.tercero_id = c.tercero_id";
  					$resulta=$dbconn->Execute($query);
  					if ($dbconn->ErrorNo() != 0) {
  									$this->error = "Error al Guardar en la Base de Datos";
  									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  									return false;
  					}
  					while(!$resulta->EOF)
  					{
  						$var[]=$resulta->GetRowAssoc($ToUpper = false);
  						$resulta->MoveNext();
  					}
  					$resulta->Close();
  					return $var;
      }

      function Interconsulta($hc_os_solicitud_id)
      {
              list($dbconn) = GetDBconn();
              $query = "select c.descripcion as especialidad_nombre
                        from hc_os_solicitudes as a, hc_os_solicitudes_interconsultas as b, especialidades as c
                        where a.hc_os_solicitud_id=b.hc_os_solicitud_id and b.especialidad=c.especialidad and
                        a.hc_os_solicitud_id = $hc_os_solicitud_id";

              $resulta=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error al Guardar en la Base de Datos";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }
              if(!$resulta->EOF)
              {  $var=$resulta->fields[0];  }
              return $var;
      }

      function Historia($tipo,$id)
      {
  					list($dbconn) = GetDBconn();
  					$query = "select historia_prefijo  as prefijo,
  										 historia_numero as numero
  										from historias_clinicas
  										where tipo_id_paciente='$tipo' and paciente_id='$id'";
  					$resulta=$dbconn->Execute($query);
  					if ($dbconn->ErrorNo() != 0) {
  									$this->error = "Error al Guardar en la Base de Datos";
  									$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
  									return false;
  					}

  					if(!$resulta->EOF)
  					{  $var=$resulta->GetRowAssoc($ToUpper = false);  }
  					$resulta->Close();
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
            return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
  //          return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
        }
    }

  }
?>