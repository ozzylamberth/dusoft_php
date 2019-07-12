<?php

/**
 * $Id: ReporteCaracteristicasPaciente.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCaracteristicasPaciente_report
{
	function ReporteCaracteristicasPaciente_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

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

	function CrearReporte()  
	{       
          $_REQUEST['centroU']=$this->datos[variables][centroU];
          $_REQUEST['unidadF']=$this->datos[variables][unidadF];
          $_REQUEST['DptoSel']=$this->datos[variables][DptoSel];
          $_REQUEST['centroutilidad'] = $this->datos[variables][centroutilidad];
          $_REQUEST['unidadfunc'] = $this->datos[variables][unidadfunc];
          $_REQUEST['departamento'] = $this->datos[variables][departamento];
          $_REQUEST['profesional_escojer'] = $this->datos[variables][profesional_escojer];
          $_REQUEST['tipocita'] = $this->datos[variables][tipocita];
          $_REQUEST['feinictra'] = $this->datos[variables][feinictra];
          $_REQUEST['fefinctra'] = $this->datos[variables][fefinctra];
          $Rango_Edades = $this->RegistrosReporteCaracteristicasPaciente();
          
          

		$HTML_WEB_PAGE ="<HTML><BODY>";
          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CARACTERISTICAS DE PACIENTES</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
		
          if(!empty($_REQUEST['centroutilidad']) OR !empty($_REQUEST['unidadfunc']) OR !empty($_REQUEST['departamento']) OR ($_REQUEST['profesional_escojer']!='-1') OR !empty($_REQUEST['feinictra']) OR !empty($_REQUEST['fefinctra']))
          {
               $HTML_WEB_PAGE .= "<table border=\"0\" width=\"80%\" align=\"center\">";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><FONT SIZE='1'><b>DATOS DE LA BUSQUEDA</b></FONT></td>";
               $HTML_WEB_PAGE .= "</tr>";
               
               if(!empty($_REQUEST['centroutilidad']))
               {
	               $HTML_WEB_PAGE .= "<tr>";
               	$HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>CENTRO DE UTILIDAD<FONT SIZE='1'></td>";
                    $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['centroutilidad']."</FONT></td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
               
               if(!empty($_REQUEST['unidadfunc']))
               {
	               $HTML_WEB_PAGE .= "<tr>";
	               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>UNIDAD FUNCIONAL</FONT></td>";
 				$HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['unidadfunc']."</FONT></td>";
               	$HTML_WEB_PAGE .= "</tr>";
               }
               
               if(!empty($_REQUEST['departamento']))
               { 
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>DEPARTAMENTO</FONT></td>";
                    $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['departamento']."</FONT></td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
                    
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
     
               if(!empty($usuario_id[1]))
               { 
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>PROFESIONAL</FONT></td>";
			     $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$usuario_id[1]."</FONT></td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
                    
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA INICIAL</FONT></td>";
               if(!empty($_REQUEST['feinictra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['feinictra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\"><FONT SIZE='1'>FECHA FINAL</FONT></td>";
               if(!empty($_REQUEST['fefinctra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><FONT SIZE='1'>".$_REQUEST['fefinctra']."</FONT></td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\"><FONT SIZE='1'>SIN DATOS</FONT></label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "</table><br>";
          }
          
          $HTML_WEB_PAGE .= "      <br><table border=\"1\" width=\"80%\" align=\"center\">";
          $HTML_WEB_PAGE .= "      <tr>";
          $HTML_WEB_PAGE .= "      <td width=\"35%\" align=\"center\"><FONT SIZE='1'><b>EDAD PACIENTE</b></FONT></td>";
          $HTML_WEB_PAGE .= "      <td width=\"15%\" align=\"center\"><FONT SIZE='1'><b>FEMENINO</b></FONT></td>";
          $HTML_WEB_PAGE .= "      <td width=\"15%\" align=\"center\"><FONT SIZE='1'><b>MASCULINO</b></FONT></td>";
          $HTML_WEB_PAGE .= "      <td width=\"15%\" align=\"center\"><FONT SIZE='1'><b>CITAS POR RANGO</b></FONT></td>";
          $HTML_WEB_PAGE .= "      </tr>";

          $total_f = '0';
          $total_m = '0';
          $total_ne = '0';

          for($i=0; $i<sizeof($Rango_Edades); $i++)
          {                          
     		$estilo='modulo_list_claro';
          	
               $femenino = '0';
               $masculino = '0';
               $NE = '0';
                
               if($Rango_Edades[$i][tipo] != $Rango_Edades[$i-1][tipo]) 
               {
                    for($J=0; $J<sizeof($Rango_Edades); $J++)
                    {
                         if($Rango_Edades[$J][tipo] == $Rango_Edades[$i][tipo]) 
                         {
                              if($Rango_Edades[$J][sexo_id] == "F")
                              { $femenino = $Rango_Edades[$J][total_citas_edad];
                                $total_f = $total_f + $femenino; }
                                
                              if($Rango_Edades[$J][sexo_id] == "M")
                              { $masculino = $Rango_Edades[$J][total_citas_edad]; 
                                $total_m = $total_m + $masculino; }
                              
                              if($Rango_Edades[$J][sexo_id] == "0")
                              { $NE = $Rango_Edades[$J][total_citas_edad]; 
                                $total_ne = $total_ne + $NE; }
                         } 
                    }
                         
                    $HTML_WEB_PAGE .= "      <tr>";
                    $HTML_WEB_PAGE .= "      <td align=\"justify\"><FONT SIZE='1'><B>".$Rango_Edades[$i][tipo]."</B></FONT></td>";
                    $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'>$femenino</FONT></td>";
                    $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'>$masculino</FONT></td>";
                    $x_rango = $femenino + $masculino;
                    $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'><B>".$x_rango."</B></FONT></td>";                              
                    $HTML_WEB_PAGE .= "      </tr>";
               }
          }          
          
          
          $HTML_WEB_PAGE .= "      <tr>";
          $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'><b>CITAS POR SEXO: </FONT></td>";
          $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'><b>$total_f</b></FONT></td>";
          $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'><b>$total_m</b></FONT></td>";
          $HTML_WEB_PAGE .= "      <td align=\"center\">&nbsp;</td>";
          $HTML_WEB_PAGE .= "      </tr>";
    
          $HTML_WEB_PAGE .= "      <tr>";
          $HTML_WEB_PAGE .= "      <td align=\"center\"><FONT SIZE='1'><b>TOTAL DE CITAS: </b></FONT></td>";
          $total_citas = ($total_f + $total_m + $total_ne);
          $HTML_WEB_PAGE .= "      <td align=\"center\" colspan=\"3\"><FONT SIZE='1'><b>$total_citas</b></FONT></td>";
          $HTML_WEB_PAGE .= "      </tr>";
       
		$HTML_WEB_PAGE .= "</table>";
		return $HTML_WEB_PAGE;
	}
  
  function RegistrosReporteCaracteristicasPaciente()
     {
          GLOBAL $ADODB_FETCH_MODE;
          
          if (!empty($_REQUEST['feinictra']) AND !empty($_REQUEST['fefinctra']))
          {
               $feinictra = $this->FechaStamp($_REQUEST['feinictra']);
               $fefinctra = $this->FechaStamp($_REQUEST['fefinctra']);
               
               if(!empty($feinictra) AND !empty($fefinctra))
               { $sql_fecha = "AND date(B.fecha) BETWEEN '".$feinictra."' AND '".$fefinctra."'";}
          }
    
          $_SESSION['reconeccc']['razonso']=$_SESSION['recoex']['razonso'];
          $_SESSION['reconeccc']['empresa']=$_SESSION['recoex']['empresa'];
    
          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad) AND $centro_utilidad != '-1')
          { 
               $sql_centro = "AND dpto.centro_utilidad = '$centro_utilidad'";
          }
           
          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional) AND $unidad_funcional != '-1')
          { 
               $sql_unidad = "AND dpto.unidad_funcional = '$unidad_funcional'";
          }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento) AND $departamento != '-1')
          { 
               $sql_dpto = "AND dpto.departamento = '$departamento'"; 
          }
          
          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND B.usuario_id = ".$usuario_id[0].""; }
          }

          /*if(empty($_REQUEST['feinictra']) OR empty($_REQUEST['fefinctra'])){
               $this->frmError["feinictra"]=1;
               $this->frmError["MensajeError"]="DEBE LLENAR LA FECHA INICIAL Y LA FECHA FINAL.";
               $this->FormaReporteCaracteristicasPacientes();
               return true;
    }*/

                    
          list($dbconn) = GetDBconn();
          //ojo comente esta forma y lo hice como esta abajo porque no daban los resultados igual que 
          //el reporte de causas de consultas citas lorena
          /*$RangoI = (date("Y-m-d"));          
          $Rango1 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-1))));
    
          $Rango5 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-5))));
          $Rango14 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-14))));
          $Rango15 = (date("Y-m-d", mktime(0,0,0,date("m"),date("d"),(date("Y")-15))));
          
          $Rango0 = (date("Y-m-d"));
          $Rango0 = explode("-",$Rango0);

          $Rango44 = ($Rango0[0] - 44)."-".$Rango0[1]."-".$Rango0[2];
          $Rango45 = ($Rango0[0] - 45)."-".$Rango0[1]."-".$Rango0[2];
          $Rango69 = ($Rango0[0] - 69)."-".$Rango0[1]."-".$Rango0[2];
          $Rango70 = ($Rango0[0] - 70)."-".$Rango0[1]."-".$Rango0[2];
          
          $Rango_Edades = array();
          
          for($i=0; $i<6; $i++)
          {
            if($i == 0)
               { $periodo ="AND date(G.fecha_nacimiento) > '$Rango1'"; $edad = "MENOR DE 1 AÑO"; }
            if($i == 1)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango1' AND date(G.fecha_nacimiento) > '$Rango5'"; $edad = "ENTRE 1 Y 5 AÑOS";}
            if($i == 2)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango5' AND date(G.fecha_nacimiento) >= '$Rango14'"; $edad = "ENTRE 5 Y 14 AÑOS";}
            if($i == 3)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango15' AND date(G.fecha_nacimiento) >= '$Rango44'"; $edad = "ENTRE 15 Y 44 AÑOS"; }
            if($i == 4)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango45' AND date(G.fecha_nacimiento) >= '$Rango69'"; $edad = "ENTRE 45 Y 69 AÑOS";}
            if($i == 5)
               { $periodo ="AND date(G.fecha_nacimiento) <= '$Rango70'"; $edad = "MAYOR DE 70 AÑOS"; }
               
               
              $query_edad = "SELECT count(*) as total_citas_edad, F.descripcion, F.sexo_id, '$edad' as tipo
                FROM departamentos A, hc_evoluciones B, os_maestro C, os_cruce_citas D, agenda_citas_asignadas E, tipo_sexo F, pacientes G, ingresos I
                WHERE A.empresa_id = '".$_SESSION['recoex']['empresa']."'
                                        $sql_centro
                                        $sql_unidad
                                        $sql_dpto
                                        AND A.departamento = B.departamento 
                                        AND B.estado = '0' 
                                        $sql_usuario
                                        $sql_fecha                                          
                                        AND B.ingreso = I.ingreso
                                        AND G.paciente_id = I.paciente_id
                                        AND G.tipo_id_paciente = I.tipo_id_paciente
                                        AND F.sexo_id = G.sexo_id
                                     $periodo
                                        AND B.numerodecuenta = C.numerodecuenta 
                                        AND C.numero_orden_id = D.numero_orden_id 
                                        AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id 

                GROUP BY F.descripcion, F.sexo_id
                                        ORDER BY F.sexo_id DESC;";
               echo $query_edad;
               echo '<BR>';
               $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
               $resulta = $dbconn->Execute($query_edad);
               $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }else{
                    while ($data = $resulta->FetchRow())
                    {
                array_push ($Rango_Edades,$data);
                    }
               }
          }*/     
          
          $query="SELECT G.fecha_nacimiento,F.descripcion, F.sexo_id
                FROM departamentos dpto, userpermisos_repconsultaexterna rep,  hc_evoluciones B, os_maestro C, os_cruce_citas D, agenda_citas_asignadas E, tipo_sexo F, pacientes G, ingresos I
                WHERE dpto.empresa_id = '".$_SESSION['recoex']['empresa']."'
                
                AND dpto.empresa_id=rep.empresa_id
                AND dpto.centro_utilidad=rep.centro_utilidad
                AND dpto.unidad_funcional=rep.unidad_funcional
                AND dpto.departamento=rep.departamento
                AND rep.usuario_id='".UserGetUID()."'
                
                                        $sql_centro
                                        $sql_unidad
                                        $sql_dpto
                                        AND dpto.departamento = B.departamento 
                                        AND B.estado = '0' 
                                        $sql_usuario
                                        $sql_fecha                                          
                                        AND B.ingreso = I.ingreso
                                        AND G.paciente_id = I.paciente_id
                                        AND G.tipo_id_paciente = I.tipo_id_paciente
                                        AND F.sexo_id = G.sexo_id                                    
                                        AND B.numerodecuenta = C.numerodecuenta 
                                        AND C.numero_orden_id = D.numero_orden_id 
                                        AND D.agenda_cita_asignada_id = E.agenda_cita_asignada_id                 
                                        ORDER BY G.fecha_nacimiento,F.sexo_id DESC;";            
                                        
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }else{            
            while (!$result->EOF) {
              $vars[]=$result->GetRowAssoc($toUpper=false);
              $result->MoveNext();
            }
          }
          $vectorUnoF=0;$vectorDosF=0;$vectorTresF=0;$vectorCuatroF=0;$vectorCincoF=0;$vectorSeisF=0;
          $vectorUnoM=0;$vectorDosM=0;$vectorTresM=0;$vectorCuatroM=0;$vectorCincoM=0;$vectorSeisM=0;
          for($l=0;$l<sizeof($vars);$l++){
            $EdadArr=CalcularEdad($vars[$l]['fecha_nacimiento']);
            $edad=$EdadArr['anos'];
            if($edad<1){
              if($vars[$l]['sexo_id']=='F'){
                $vectorUnoF++;
              }else{
                $vectorUnoM++;
              } 
            }elseif($edad>=1 && $edad<5){
              if($vars[$l]['sexo_id']=='F'){
                $vectorDosF++;
              }else{
                $vectorDosM++;
              } 
            }elseif($edad>=5 && $edad<=14){
              if($vars[$l]['sexo_id']=='F'){
                $vectorTresF++;
              }else{
                $vectorTresM++;
              } 
            }elseif($edad>=15 && $edad<=44){
              if($vars[$l]['sexo_id']=='F'){
                $vectorCuatroF++;
              }else{
                $vectorCuatroM++;
              } 
            }elseif($edad>=45 && $edad<=69){
              if($vars[$l]['sexo_id']=='F'){
                $vectorCincoF++;
              }else{
                $vectorCincoM++;
              } 
            }else{
              if($vars[$l]['sexo_id']=='F'){
                $vectorSeisF++;
              }else{
                $vectorSeisM++;
              } 
            }           
          }
          $Rango_Edades1=array();
          $dat1['total_citas_edad']=$vectorUnoF;
          $dat1['descripcion']='FEMENINO';
          $dat1['sexo_id']='F';
          $dat1['tipo']='MENOR DE 1 AÑO';
          array_push ($Rango_Edades1,$dat1);
          $dat2['total_citas_edad']=$vectorUnoM;
          $dat2['descripcion']='MASCULINO';
          $dat2['sexo_id']='M';
          $dat2['tipo']='MENOR DE 1 AÑO';         
          array_push ($Rango_Edades1,$dat2);
          
          $dat3['total_citas_edad']=$vectorDosF;
          $dat3['descripcion']='FEMENINO';
          $dat3['sexo_id']='F';
          $dat3['tipo']='ENTRE 1 Y 5 AÑOS';
          array_push ($Rango_Edades1,$dat3);
          $dat4['total_citas_edad']=$vectorDosM;
          $dat4['descripcion']='MASCULINO';
          $dat4['sexo_id']='M';
          $dat4['tipo']='ENTRE 1 Y 5 AÑOS';
          array_push ($Rango_Edades1,$dat4);
          
          $dat5['total_citas_edad']=$vectorTresF;
          $dat5['descripcion']='FEMENINO';
          $dat5['sexo_id']='F';
          $dat5['tipo']='ENTRE 5 Y 14 AÑOS';
          array_push ($Rango_Edades1,$dat5);
          $dat6['total_citas_edad']=$vectorTresM;
          $dat6['descripcion']='MASCULINO';
          $dat6['sexo_id']='M';
          $dat6['tipo']='ENTRE 5 Y 14 AÑOS';
          array_push ($Rango_Edades1,$dat6);
          
          $dat7['total_citas_edad']=$vectorCuatroF;
          $dat7['descripcion']='FEMENINO';
          $dat7['sexo_id']='F';
          $dat7['tipo']='ENTRE 15 Y 44 AÑOS';
          array_push ($Rango_Edades1,$dat7);
          $dat8['total_citas_edad']=$vectorCuatroM;
          $dat8['descripcion']='MASCULINO';
          $dat8['sexo_id']='M';
          $dat8['tipo']='ENTRE 15 Y 44 AÑOS';
          array_push ($Rango_Edades1,$dat8);
          
          $dat9['total_citas_edad']=$vectorCincoF;
          $dat9['descripcion']='FEMENINO';
          $dat9['sexo_id']='F';
          $dat9['tipo']='ENTRE 45 Y 69 AÑOS';
          array_push ($Rango_Edades1,$dat9);
          $dat10['total_citas_edad']=$vectorCincoM;
          $dat10['descripcion']='MASCULINO';
          $dat10['sexo_id']='M';
          $dat10['tipo']='ENTRE 45 Y 69 AÑOS';
          array_push ($Rango_Edades1,$dat10);
          
          $dat11['total_citas_edad']=$vectorSeisF;
          $dat11['descripcion']='FEMENINO';
          $dat11['sexo_id']='F';
          $dat11['tipo']='MAYOR DE 70 AÑOS';
          array_push ($Rango_Edades1,$dat11);
          $dat12['total_citas_edad']=$vectorSeisM;
          $dat12['descripcion']='MASCULINO';
          $dat12['sexo_id']='M';
          $dat12['tipo']='MAYOR DE 70 AÑOS';
          array_push ($Rango_Edades1,$dat12);        
          
          return $Rango_Edades1;
  }
  
      function FechaStamp($fecha)
  {
      $fecha = explode ('/',$fecha);
          $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
          return $fecha;
          
  }
     

}

?>
