<?php
  /**
  * $Id: examenesresultados.report.php,v 1.2 2009/08/04 20:13:45 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * Reporte de prueba formato HTML
  */
  IncludeClass('ConexionBD');
  IncludeClass("Resultados","classes","app","ImpresionHC");
  class examenesresultados_report extends Resultados
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
    function examenesresultados_report($datos=array())
    {
		    $this->datos=$datos;
        return true;
    }
    /**
    * @return array
    */
    function GetMembrete()
    {
      $Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
																'subtitulo'=>'',
																'logo'=>'logocliente.png',
																'align'=>'left'));
      return $Membrete;
    }
    /**
    * FUNCION QUE RETORNA EL HTML DEL REPORTE (lo que va dentro del tag <BODY>)
    *
    * @return string
    */
    function CrearReporte()
    {
      $resultados = "";

      if($this->datos['resultado_id'])
        $resultados = $this->datos['resultado_id'];
      else if(!empty($this->datos['resultados']))
      {
        foreach($this->datos['resultados'] as $k => $dtl)
          ($resultados == "")? $resultados = $dtl :$resultados .= ",".$dtl;
      }
      
      $dts = array();
      if($this->datos['evolucion'] || $this->datos['evolucion_solicitud'])
      {
        $datosR = $this->ObtenerLecturaApoyos($this->datos);
        $dts = $this->datos;
        $dts['empresa_id'] = "01";
      }
      else
      {
        $datosR = $this->Obtenerresultados($resultados);
        $dts = $datosR[0];
      }
      $dtsAdicc = $this->ObtenerInformacionAdicional($dts);
      $edad_paciente = CalcularEdad($dtsAdicc['fecha_nacimiento'],date("Y-m-d"));
        
      $html .= "<table  align=\"center\" border=\"0\"  width=\"100%\">\n";
      $html .= "  <tr class=\"Normal_10\">\n";
      $html .= "    <td width=\"25%\" ><b>ENTIDAD :</b></td>\n";
      $html .= "    <td width=\"75%\" >".$dtsAdicc['razon_social'].'&nbsp;&nbsp;'.$dtsAdicc['tipo_id_tercero'].'&nbsp;&nbsp;'.$dtsAdicc['id']."</td>";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"Normal_10\">\n";
      $html .= "    <td ><b>PACIENTE :</b></td>\n";
      $html .= "    <td >".$dtsAdicc['tipo_id_paciente'].' '.$dtsAdicc['paciente_id'].' - '.strtoupper($dtsAdicc['primer_nombre']." ".$dtsAdicc['segundo_nombre']." ".$dtsAdicc['primer_apellido']." ".$dtsAdicc['segundo_apellido'])."</td>\n";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"Normal_10\">\n";
      $html .= "    <td ><b>EDAD PACIENTE :</b></td>\n";
      $html .= "    <td >".$edad_paciente['edad_aprox']."</td>";
      $html .= "  </tr>\n";
      $html .= "  <tr class=\"Normal_10\" >\n";
      $html .= "    <td><b>PLAN :</b></td>\n";
      $html .= "    <td>".$datosR[0]['plan_descripcion']."</td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      $tipos_apoyo = "";
      foreach($datosR as $k1 => $dtl)
      {
        if($tipos_apoyo != $dtl['apoyod_tipo_id'] && $tipos_apoyo != "")
          $html .= "<div style=\"page-break-after: always;\"></div>\n";
        
        $tipos_apoyo = $dtl['apoyod_tipo_id'];
        
        $vector = $this->ObtenerDetalles($dtl);
        
        $html .= "<table align=\"center\" border=\"1\"  bordercolor=\"#000000\" rules=\"all\" width=\"100%\">";
        $html .= "  <tr class=\"Normal_10N\" align=\"center\" >\n";
        $html .= "    <td colspan=\"4\" >".$dtl['cargo']." - ".strtoupper($dtl['titulo'])."</td>\n";
        $html .= "  </tr>\n";
        if($dtl['numero_orden_id'])
        {
          $html .= "  <tr class=\"Normal_10\">\n";
          $html .= "    <td width=\"20%\"><b>ORDEN :</b></td>\n";
          $html .= "    <td >".$dtl['numero_orden_id']."</td>\n";
          $html .= "    <td colspan=\"2\">&nbsp;</td>\n";
          $html .= "  </tr>\n";
        }
       // if($vector['datos_adicionales']['servicio'])
       // {
          $html .= "  <tr class=\"Normal_10\">\n";
          $html .= "    <td width=\"25%\"><b>SERVICIO :</b></td>\n";
          $html .= "    <td >".$vector['datos_adicionales']['servicio']."</td>\n";
          $html .= "    <td width=\"25%\"><b>FECHA RESULTADO :</b></td>\n";
          $html .= "    <td >".$vector['datos_adicionales']['fecha_resultado']."</td>\n";
          $html .= "  </tr>\n";
       // }        
        
        if($vector['datos_adicionales']['historia_numero'])
        {
          $html .= "  <tr class=\"Normal_10\">\n";
          $html .= "    <td ><b>HISTORIA :</b></td>\n";
          $html .= "    <td >".$vector['datos_adicionales']['historia_prefijo']." ".$vector['datos_adicionales']['historia_numero']."</td>\n";
          if($vector['datos_adicionales']['profesional'])
          {
            $html .= "    <td width=\"25%\"><b>PROFESIONAL :</b></td>\n";
            $html .= "    <td >".$vector['datos_adicionales']['profesional']."</td>\n";
          }
          else
            $html .= "      <td colspan=\"2\">&nbsp;</td>\n";
            
          $html .= "  </tr>\n";
        }        
        
        if($vector['datos_adicionales']['departamento'])
        {
          $html .= "  <tr class=\"Normal_10\">\n";
          $html .= "    <td ><b>DEPARTAMENTO :</b></td>\n";
          $html .= "    <td >".$vector['datos_adicionales']['departamento']." </td>\n";
          $html .= "    <td ><b>CAMA :</b></td>\n";
          $html .= "    <td >".$vector['datos_adicionales']['cama']."</td>\n";
          $html .= "  </tr>\n";
        } 
        
        if($vector['datos_adicionales']['comentario'])
        {
          $html .= "  <tr class=\"Normal_10\">\n";
          $html .= "    <td ><b>COMENTARIO :</b></td>\n";
          $html .= "    <td colspan=\"3\">".$vector['datos_adicionales']['comentario']." </td>\n";
          $html .= "  </tr>\n";
        }
        
 				$html .= "  <tr>\n";
 				$html .= "    <td colspan=\"4\" width=\"100%\">&nbsp;</td>\n";
 				$html .= "  </tr>\n";
        
  			if(!empty($vector[detalle]))
  			{
          $plan="";
          $html .= "  <tr>\n";
          $html .= "    <td colspan=\"4\">\n";
          foreach($vector[detalle] as $kr1 => $dtlr1)
					{
            
            if($plan!=$dtlr1[lab_plantilla_id])
            {
              $sw=true;
              $plan=$dtlr1[lab_plantilla_id];
            }
            else
            {
              $sw=false;
            }
            
            switch ($dtlr1[lab_plantilla_id])
            {
           		case "1":   
                $clase = "";
                $equis = "&nbsp;";
                if(is_null($dtlr1[rango_min]) || $dtlr1[rango_min] == '0')
                  $dtlr1[rango_min] = 0;
                    
                if ($dtlr1[sw_alerta] == '1')
                {
                  $clase = "class=\"label_error\"";
                  $equis = "X";
                }
                
                if($sw==true)
                {
                  $html .= "      <table align=\"center\" border=\"1\"  bordercolor=\"#000000\" rules=\"all\" width=\"100%\">\n";
                  $html .= "        <tr class=\"Normal_10N\" align=\"center\">\n";
                  $html .= "          <td width=\"35%\" >SUBEXAMEN</td>\n";
                  $html .= "          <td width=\"30%\" >RESULTADO</td>\n";
                  $html .= "          <td width=\"10%\" >V.MIN</td>\n";
                  $html .= "          <td width=\"10%\" >V.MAX</td>\n";
                  $html .= "          <td width=\"10%\" >UND</td>\n";
                  $html .= "          <td width=\"5%\"  >PAT.</td>\n";
                  $html .= "        </tr>\n";
                }
                              
                $html .= "        <tr class=\"Normal_10\">\n";
                $html .= "          <td >".strtoupper($dtlr1['nombre_examen'])."</td>\n";
                $html .= "          <td align=\"center\" ".$clase.">".$dtlr1['resultado']." &nbsp; ".$dtlr1['unidades']."</td>\n";
                $html .= "          <td align=\"center\">".$dtlr1['rango_min']."</td>\n";
                $html .= "          <td align=\"center\">".$dtlr1['rango_max']."</td>\n";
                $html .= "          <td align=\"center\">".$dtlr1['unidades']."</td>\n";
                $html .= "          <td align=\"center\">".$equis."</td>\n";
                $html .= "        </tr>\n";
                if($dtlr1['lab_plantilla_id'] != $vector['detalle'][$kr1+1]['lab_plantilla_id'])
                  $html .= "      </table>\n";
              break;
              case "2": 
                $clase = "";
                $equis = "&nbsp;";
                if ($dtlr1[sw_alerta] == '1')
                {
                  $clase = "class=\"label_error\"";
                  $equis = "X";
                }
                
                if($sw==true)
                { 
                  $html .= "      <table align=\"center\" border=\"1\"  bordercolor=\"#000000\" rules=\"all\" width=\"100%\">\n";
                  $html .= "        <tr class=\"Normal_10N\" align=\"center\">\n";
                  $html .= "          <td width=\"35%\" >SUBEXAMEN</td>\n";
                  $html .= "          <td width=\"40%\" colspan = \"2\">RESULTADO</td>\n";
                  $html .= "          <td width=\"20%\" colspan = \"2\">UND</td>\n";
                  $html .= "          <td width=\"5%\" >PAT.</td>\n";
                  $html .= "        </tr>\n";
                } 

                $html .= "        <tr class=\"Normal_10\">\n";
                $html .= "          <td align=\"left\" width=\"35%\" >".strtoupper($dtlr1['nombre_examen'])."</td>\n";
                $html .= "          <td align=\"center\" width=\"40%\" colspan = \"2\" ".$clase.">".$dtlr1[resultado]."</td>\n";
                $html .= "          <td align=\"center\" width=\"20%\" colspan = \"2\">".$dtlr1['unidades']."</td>\n";
                $html .= "          <td width=\"5%\" align=\"center\">".$equis."</td>\n";
                $html .= "        </tr>\n";
                
                if($dtlr1['lab_plantilla_id'] != $vector['detalle'][$kr1+1]['lab_plantilla_id'])
                  $html .= "      </table>\n";
              break;
              case "3": 
                $html .= "      <table align=\"center\" border=\"1\"  bordercolor=\"#000000\" rules=\"all\" width=\"100%\">\n";
                if ($dtlr1[sw_alerta] == '1')
                {
                  $html .= "        <tr class=\"Normal_10N\">\n";
                  $html .= "          <td align=\"left\">RESULTADO PATOLOGICO</td>\n";
                  $html .= "        </tr>\n";
                }

                $html .= "          <tr class=\"Normal_10N\">\n";
                $html .= "            <td align=\"justify\">\n";
                $html .= "              <blockquote>".$dtlr1[resultado]."</blockquote>";
                $html .= "            </td>\n";
                $html .= "          </tr>\n";
                
                if($dtlr1['lab_plantilla_id'] != $vector['detalle'][$kr1+1]['lab_plantilla_id'])
                  $html .= "      </table>\n";
              break;
              case "0": 
                $html .= "      <table align=\"center\" border=\"1\"  bordercolor=\"#000000\" rules=\"all\" width=\"100%\">\n";
                if($sw==true)
                {
                  $html .= "        <tr class=\"Normal_10N\">\n";
                  $html .= "          <td width=\"35%\" align=\"left\">SUBEXAMEN: ".strtoupper($dtlr1['nombre_examen'])."</td>\n";
                  $html .= "        </tr>\n";
                }
                if ($dtlr1[sw_alerta] == '1')
                {
                  $html .= "          <tr class=\"Normal_10N\">\n";
                  $html .= "            <td width=\"60%\" align=\"left\">RESULTADO PATOLOGICO</td>\n";
                  $html .= "          </tr>\n";
                }
                            
                $dtlr1[resultado]=str_replace("\x0a","<p>",$dtlr1[resultado]);
                $html .= "          <tr class=\"Normal_10N\">\n";
                $html .= "            <td align=\"justify\">\n";
                $html .= "              <blockquote>".$dtlr1[resultado]."</blockquote>";
                $html .= "            </td>\n";
                $html .= "          </tr>\n";
                
                if($dtlr1['lab_plantilla_id'] != $vector['detalle'][$kr1+1]['lab_plantilla_id'])
                  $html .= "      </table>\n";
              break;
              case "5": 
                $clase = "";
                $equis = "&nbsp;";
                if(is_null($dtlr1[rango_min]) || $dtlr1[rango_min] == '0')
                    $dtlr1[rango_min] = 0;
                    
                if ($dtlr1[sw_alerta] == '1')
                {
                  $clase = "class=\"label_error\"";
                  $equis = "X";
                }  
                if($sw==true)
                {
                  $html .= "      <table align=\"center\" border=\"1\"  bordercolor=\"#000000\" rules=\"all\" width=\"100%\">\n";
                  $html .= "        <tr class=\"Normal_10N\" align=\"center\">\n";
                  $html .= "          <td width=\"35%\" >SUBEXAMEN</td>\n";
                  $html .= "          <td width=\"30%\" >RESULTADO</td>\n";
                  $html .= "          <td width=\"10%\" >V.MIN</td>\n";
                  $html .= "          <td width=\"10%\" >V.MAX</td>\n";
                  $html .= "          <td width=\"10%\" >UND</td>\n";
                  $html .= "          <td width=\"5%\"  >PAT.</td>\n";
                  $html .= "        </tr>";
                }
               
                $html .= "        <tr class=\"Normal_10\">\n";
                $html .= "          <td align=\"left\"  >".strtoupper($dtlr1['nombre_examen'])."</td>\n";
                $html .= "          <td align=\"center\" ".$clase.">".$dtlr1['resultado']." &nbsp; ".$dtlr1['unidades']."</td>\n";
                $html .= "          <td align=\"center\">".$dtlr1['rango_min']."&nbsp;</td>\n";
                $html .= "          <td align=\"center\">".$dtlr1['rango_max']."&nbsp;</td>\n";
                $html .= "          <td align=\"center\">".$dtlr1['unidades']."&nbsp;</td>\n";
                $html .= "          <td align=\"center\">".$equis."</td>\n";
                $html .= "        </tr>\n";
                
                if($dtlr1['lab_plantilla_id'] != $vector['detalle'][$kr1+1]['lab_plantilla_id'])
                  $html .= "      </table>\n";
              break;
						}
					}

					$html .= "    </td>\n";
					$html .= "  </tr>\n";
          $htm1 = "";
					if($vector[informacion]!='')
					{
            $htm1 .= "        <tr class=\"Normal_10N\">\n";
            $htm1 .= "          <td widht=\"30\" class=\"hc_table_submodulo_list_title\" align=\"left\">INFORMACION: </td><td widht=\"70\" align=\"left\" class=\"Normal_10\"><font size='1'>".$vector[informacion]."</font></td>";
            $htm1 .= "        </tr>";
            $htm1 .= "        <tr>";
            $htm1 .= "          <td colspan=\"4\" width=\"100%\"></td>";
            $htm1 .= "        </tr>";
					}

					if($vector[observacion_prestacion_servicio]!='')
					{
						$htm1 .= "<tr class=\"Normal_10N\">";
						$htm1 .= "<td align=\"left\" colspan = 4 class=\"hc_table_submodulo_list_title\" width=\"100%\">OBSERVACION DEL PRESTADOR DEL SERVICIO:</td>";
						$htm1 .= "</tr>";
						$htm1 .= "<tr class=\"Normal_10\">";
						$htm1 .= "<td align=\"left\" class=\"$estilo\" width=\"100%\" colspan = 4>".$vector[observacion_prestacion_servicio]."</td>";
						$htm1 .= "</tr>";
						$htm1 .= "<tr>";
						$htm1 .= "<td colspan=\"4\" width=\"100%\"></td>";
						$htm1 .= "</tr>";
					}

					//listado de las observaciones adicionales al resultado
					if(sizeof($vector['observaciones_adicionales'])>=1)
					{
            $htm1 .= "<tr>";
            $htm1 .= "<td align=\"center\" colspan = 4>";
            $htm1 .= "<table  align=\"center\" border=\"1\" rules=\"all\" bordercolor=\"#000000\" width=\"100%\">";
            $htm1 .= "<tr class=\"Normal_10N\">";
            $htm1 .= "<td align=\"center\" class=\"hc_table_submodulo_list_title\" width=\"5%\">FECHA REGISTRO</td>";
            $htm1 .= "<td align=\"center\" class=\"hc_table_submodulo_list_title\" width=\"20%\">USUARIO QUE REALIZA LA OBSERVACION</td>";
            $htm1 .= "<td align=\"center\"  class=\"hc_table_submodulo_list_title\" width=\"60%\">OBSERVACION ADICIONAL AL RESULTADO</td>";
            $htm1 .= "</tr>";

            foreach($vector['observaciones_adicionales'] as $kr2 => $dtlr2)
            {
              $htm1 .= "<tr class=\"Normal_10\">";
              $htm1 .= "<td align=\"center\" class=\"modulo_list_claro\" width=\"5%\">".$this->FechaStampMostrar($dtlr2['fecha_registro_observacion'])." - ".$this->HoraStamp($dtlr2['fecha_registro_observacion'])."</td>";
              $htm1 .= "<td align=\"center\" class=\"modulo_list_claro\" width=\"30%\">".$dtlr2['usuario_observacion']."</td>";
              $htm1 .= "<td align=\"justify\" class=\"modulo_list_claro\" width=\"60%\">".$dtlr2['observacion_adicional']."</td>";
              $htm1 .= "</tr>";
            }
            $htm1 .= "</table>";
            $htm1 .= "</td>";
            $htm1 .= "</tr>";
					}

					if($this->datos['sw_firma']==true)
					{
            $htm1 .= "<tr class=\"Normal_10N\">";
						//si el que diagnostica y firma son diferentes
						if ( (($vector[usuario_id_profesional])!=($vector[usuario_id_profesional_autoriza]))
								&&($vector[usuario_id_profesional_autoriza]!=NULL) )
						{
							$htm1 .= "<td align=\"left\"  class=\"hc_table_submodulo_list_title\" width=\"50%\">DIAGNOSTICO PROFESIONAL :</td>";
							$htm1 .= "<td align=\"left\"  class=\"hc_table_submodulo_list_title\" width=\"50%\">REVISADO POR :</td>";
							$htm1 .= "</tr>";
							$htm1 .= "<tr>";
							$htm1 .= "<td colspan=\"4\" width=\"100%\"></td>";
							$htm1 .= "</tr>";
							$htm1 .= "<tr class=\"Normal_10N\">";
						
						
							if($vector[tarjeta_profesional]!='')
							{
								$htm1 .= "<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."<br>TP: ".$vector[tarjeta_profesional]."</td>";
							}
							else
							{
								$htm1 .= "<td align=\"left\"  class=\"modulo_list_claro\" width=\"50%\" ><br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."</td>";
							}
							
							if(($vector[usuario_id_profesional_autoriza]!=NULL)||(!empty($vector[usuario_id_profesional_autoriza])))
							{
								$prof_firma=$this->ConsultaFirmaMedico($vector[usuario_id_profesional_autoriza]);
								$htm1 .= "          <td align=\"left\" width=\"50%\" >\n";
                $htm1 .= "            <br>______________________________<br>";
                $htm1 .= "            ".strtoupper($prof_firma[nombre])."<br>";
                $htm1 .= "            ".strtoupper($prof_firma[descripcion])."";
                if($prof_firma[tarjeta_profesional]!='')
									$htm1 .= "            <br>TP: ".$prof_firma[tarjeta_profesional]."";
								$htm1 .= "          </td>\n";
							}
						}
						else
						{	
							$htm1 .= "          <td align=\"left\"  class=\"hc_table_submodulo_list_title\" width=\"50%\">DIAGNOSTICO PROFESIONAL ::</td>";
							$htm1 .= "        </tr>\n";
              $htm1 .= "        <tr>\n";
              $htm1 .= "          <td colspan=\"4\" width=\"100%\">&nbsp;</td>\n";
              $htm1 .= "        </tr>\n";
							$htm1 .= "        <tr class=\"Normal_10N\">\n";
              $htm1 .= "          <td align=\"left\"  width=\"50%\" >\n";
              $htm1 .= "            <br>______________________________<br>".strtoupper($vector[nombre_tercero])."<br>".$vector[descripcion]."";
							if($vector[tarjeta_profesional]!='')
								$htm1 .= "            <br>TP: ".$vector[tarjeta_profesional]."";
							$htm1 .= "          </td>\n";
						}
  					$htm1 .= "        </tr>\n";
					}
          
          if($htm1 != "")
          {
            $html .= "  <tr>\n";
            $html .= "    <td colspan=\"4\">\n";
  					$html .= "      <table  align=\"center\" border=\"0\"  width=\"100%\">\n";
  					$html .= $htm1;
  					$html .= "      </table>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
          }
        }
        $html .= "</table><br>\n";      
      }
      $usuario = $this->ConsultaNombreUsuario(UserGetUID());
      if ($usuario)
      {
        $html .= "<br>\n";
        $html .= "<table align=\"center\" border=\"0\"  width=\"100%\">\n";
        $html .= "  <tr class=\"Normal_10N\">\n";
        $html .= "    <td align=\"left\" width=\"50%\">Imprime: ".$usuario[nombre]."</td>\n";
        $html .= "    <td align=\"right\" width=\"50%\">Fecha Impresión: ".date('Y-m-d h:m')."</td>\n";
        $html .= "  </tr>\n";
        $html .= "</table>\n";
      }
			return $html;
    }  
    /**
    *
    */
    function FechaStampMostrar($fecha)
    {
      if($fecha)
      {
        $fech = strtok ($fecha,"-");
        for($l=0;$l<3;$l++)
        {
            $date[$l]=$fech;
            $fech = strtok ("-");
        }
        $mes = str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT);
        $dia = str_pad(ceil($date[2]), 2, 0, STR_PAD_LEFT);
        return  ceil($date[0])."-".$mes."-".$dia;
      }
    }
    /**
    * Separa la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
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
  }
?>