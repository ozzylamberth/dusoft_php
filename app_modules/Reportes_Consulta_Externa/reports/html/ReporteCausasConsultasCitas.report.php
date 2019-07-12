<?php

/**
 * $Id: ReporteCausasConsultasCitas.report.php,v 1.1.1.1 2009/09/11 20:36:56 hugo Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Archivo que imprime el formulario anexo1 que se entrega al FOSYGA
 */

class ReporteCausasConsultasCitas_report
{
	function ReporteCausasConsultasCitas_report($datos=array())
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
          $this->RegistrosReporteCausasCitasMedicas();
          $datos_consulta = $this->Tipo_con;
          $Rango_Edades = $this->Rango_Edades;
          $Tipo_sexo = $this->Tipo_sexo;
          
		$_REQUEST['centroutilidad'] = $this->datos[variables][centroutilidad];
          $_REQUEST['unidadfunc'] = $this->datos[variables][unidadfunc];
          $_REQUEST['departamento'] = $this->datos[variables][departamento];
          $_REQUEST['profesional_escojer'] = $this->datos[variables][profesional_escojer];
          $_REQUEST['tipocita'] = $this->datos[variables][tipocita];
          $_REQUEST['feinictra'] = $this->datos[variables][feinictra];
          $_REQUEST['fefinctra'] = $this->datos[variables][fefinctra];

		$HTML_WEB_PAGE ="<HTML><BODY>";
          
          $HTML_WEB_PAGE .= "<br><br><center>";
		$HTML_WEB_PAGE .= "<label><font size='6' face='arial'>REPORTE ESTADISTICO CAUSAS DE CONSULTAS MEDICAS</font></label>";
		$HTML_WEB_PAGE .= "</center><br><br>";
          
          if(!empty($_REQUEST['centroutilidad']) OR !empty($_REQUEST['unidadfunc']) OR !empty($_REQUEST['departamento']) OR ($_REQUEST['profesional_escojer']!='-1') OR !empty($_REQUEST['feinictra']) OR !empty($_REQUEST['fefinctra']))
          {
               $HTML_WEB_PAGE .= "<table border=\"0\" width=\"80%\" align=\"center\">";
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td align=\"center\" colspan=\"2\"><b>DATOS DE LA BUSQUEDA</b></td>";
               $HTML_WEB_PAGE .= "</tr>";
               
               if(!empty($_REQUEST['centroutilidad']))
               {
	               $HTML_WEB_PAGE .= "<tr>";
               	$HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\">CENTRO DE UTILIDAD</td>";
                    $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\">".$_REQUEST['centroutilidad']."</td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
               
               if(!empty($_REQUEST['unidadfunc']))
               {
	               $HTML_WEB_PAGE .= "<tr>";
	               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\">UNIDAD FUNCIONAL</td>";
 				$HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\">".$_REQUEST['unidadfunc']."</td>";
               	$HTML_WEB_PAGE .= "</tr>";
               }
               
               if(!empty($_REQUEST['departamento']))
               { 
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\">DEPARTAMENTO</td>";
                    $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\">".$_REQUEST['departamento']."</td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
                    
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
     
               if(!empty($usuario_id[1]))
               { 
                    $HTML_WEB_PAGE .= "<tr>";
                    $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\">PROFESIONAL</td>";
			     $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\">".$usuario_id[1]."</td>";
	               $HTML_WEB_PAGE .= "</tr>";
               }
                    
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\">FECHA INICIAL</td>";
               if(!empty($_REQUEST['feinictra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\">".$_REQUEST['feinictra']."</td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "<tr>";
               $HTML_WEB_PAGE .= "<td width=\"40%\" align=\"left\">FECHA FINAL</td>";
               if(!empty($_REQUEST['fefinctra']))
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\">".$_REQUEST['fefinctra']."</td>"; }
               else
               { $HTML_WEB_PAGE .= "<td align=\"justify\" width=\"60%\"><label class=\"label_mark\">SIN DATOS</label></td>"; }
               $HTML_WEB_PAGE .= "</tr>";
     
               $HTML_WEB_PAGE .= "</table><br>";
          }
          if($datos_consulta){
					
						$HTML_WEB_PAGE .= "      <TABLE WIDTH=\"100%\" BORDER=1>";
						$HTML_WEB_PAGE .= "      <TR>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"5%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">CODIGO</FONT></TD>";
						$HTML_WEB_PAGE .= "      <TD ALIGN=\"CENTER\"><FONT SIZE=\"1\">DESCRIPCION</FONT></td>";
						foreach($Tipo_sexo as $k => $v){	
							$HTML_WEB_PAGE .= "      <TD WIDTH=\"5%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">".strtoupper($k)."</FONT></td>";
            }						
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">Menor de 1</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">Entre 1 y 5</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">Entre 5 y 14</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">Entre 15 y 44</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">Entre 45 y 69</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">Mayor de 70</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD WIDTH=\"8%\" ALIGN=\"CENTER\"><FONT SIZE=\"1\">TOTAL</FONT></td>";
						$HTML_WEB_PAGE .= "</tr>";
						$Sumamenor1='0';$Sumaentre1y5='0';$Sumaentre5y14='0';$Sumaentre15y44='0';$Sumaentre45y69='0';$Sumamayor70='0';$totFil='0';																					
						foreach($datos_consulta as $k1 => $V5 ){
							
							
							for($i=0; $i<sizeof($V5); $i++){  
								
								if ($V5[$i]['diagnostico_id'] != $V5[$i-1]['diagnostico_id']){	
									$a = $i;
									$estilo='hc_table_submodulo_list_title';								
									$estilo1='modulo_list_claro';								
									$HTML_WEB_PAGE .= "<TR>";
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$V5[$i]['diagnostico_id']."</FONT></td>";
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$V5[$i]['diagnostico_nombre']."</FONT></b></td>";
									foreach($Tipo_sexo as $k => $v){									
										
										if($k != $V5[$a]['sexo']){
											//$a = $a-1;
											$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">0</font></td>";
										}elseif($k == $V5[$a]['sexo']){
											$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$V5[$a]['cantidad']."</FONT></td>"; 
											$a ++;
										}
										
									}
									$menor1 =0;$entre1y5 =0; $entre5y14 =0; $entre15y44 =0; $entre45y69 =0; $mayor70 =0;                   								
									for($z=0; $z<sizeof($Rango_Edades); $z++){
										if($Rango_Edades[$z][tipo_diagnostico_id] == $V5[$i][diagnostico_id]){											
											if($Rango_Edades[$z][cantidad_menor_1]){
											if(empty($menor1)){
												$menor1 = $Rango_Edades[$z][cantidad_menor_1]; 
												$Sumamenor1+=$menor1;
											}
											}
											if($Rango_Edades[$z][cantidad_entre_1_5]){											
											if(empty($entre1y5)){
												$entre1y5 = $Rango_Edades[$z][cantidad_entre_1_5]; 
												$Sumaentre1y5+=$entre1y5;
											}
											}
											if($Rango_Edades[$z][cantidad_entre_5_14]){
											if(empty($entre5y14)){
												$entre5y14 = $Rango_Edades[$z][cantidad_entre_5_14]; 
												$Sumaentre5y14+=$entre5y14;
											}
											}
											if($Rango_Edades[$z][cantidad_entre_15_44]){
											if(empty($entre15y44)){
												$entre15y44 = $Rango_Edades[$z][cantidad_entre_15_44]; 
												$Sumaentre15y44+=$entre15y44;												
											}
											}
											if($Rango_Edades[$z][cantidad_entre_45_69]){
											if(empty($entre45y69)){
												$entre45y69 = $Rango_Edades[$z][cantidad_entre_45_69]; 
												$Sumaentre45y69+=$entre45y69;
											}
											}
											if($Rango_Edades[$z][cantidad_mayor_70]){
											if(empty($mayor70)){
												$mayor70 = $Rango_Edades[$z][cantidad_mayor_70]; 
												$Sumamayor70+=$mayor70;
											}
											}
										}
									}
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$menor1."</FONT></td>";																
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$entre1y5."</FONT></td>";                
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$entre5y14."</FONT></td>";								
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$entre15y44."</FONT></td>";								
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$entre45y69."</FONT></td>";               								
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$mayor70."</FONT></td>"; 									
									$HTML_WEB_PAGE .= "<TD ALIGN=\"LEFT\"><FONT SIZE=\"1\">".$totFil1=($menor1+$entre1y5+$entre5y14+$entre15y44+$entre45y69+$mayor70)."</FONT></td>"; 									
									$HTML_WEB_PAGE .= "</TR>";										
									$totFil+=$totFil1;
								}																	
							}	
						}
						$HTML_WEB_PAGE .= "     <TR>";	
						$HTML_WEB_PAGE .= "      <TD ALIGN=\"RIGHT\" COLSPAN=\"4\"><FONT SIZE=\"1\">TOTALES</FONT></td>";
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$Sumamenor1."</FONT></TD>";                
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$Sumaentre1y5."</FONT></TD>";								
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$Sumaentre5y14."</FONT></TD></td>";								
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$Sumaentre15y44."</FONT></TD>"; 
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$Sumaentre45y69."</FONT></TD>";               								
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$Sumamayor70."</FONT></TD>"; 
						$HTML_WEB_PAGE .= "      <TD><FONT SIZE=\"1\">".$totFil."</FONT></TD>";               																										
						$HTML_WEB_PAGE .= "		</TR>";		
						$HTML_WEB_PAGE .= "			</TABLE><BR>";						            
					}
					
         
		$HTML_WEB_PAGE.="</BODY></HTML>";
		return $HTML_WEB_PAGE;
	}
			
     
     function RegistrosReporteCausasCitasMedicas()
     {
		$_REQUEST['centroU'] = $this->datos[variables][centroU];
          $_REQUEST['unidadF'] = $this->datos[variables][unidadF];
          $_REQUEST['DptoSel'] = $this->datos[variables][DptoSel];
          $_REQUEST['profesional_escojer'] = $this->datos[variables][profesional_escojer];
          $_REQUEST['tipocita'] = $this->datos[variables][tipocita];
          $_REQUEST['feinictra'] = $this->datos[variables][feinictra];
          $_REQUEST['fefinctra'] = $this->datos[variables][fefinctra];
          
          GLOBAL $ADODB_FETCH_MODE;
		if(!empty($_REQUEST['feinictra'])){
			$fechas=explode('/',$_REQUEST['feinictra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
			  $_SESSION['reconeccc']['fechadesde']=$yea.'-'.$mon.'-'.$day;
		  }else{
			  $_REQUEST['feinictra']='';
				$this->frmError["feinictra"]=1;
		  }
		}else{
			$this->frmError["feinictra"]=1;
		}
		if(!empty($_REQUEST['fefinctra'])){
			$fechas=explode('/',$_REQUEST['fefinctra']);
			$day=$fechas[0];
			$mon=$fechas[1];
			$yea=$fechas[2];
			if(!(checkdate($mon, $day, $yea)==0)){
				$fech=date ("Y-m-d");
				if($_SESSION['reconeccc']['fechadesde'] <= date("Y-m-d", mktime(1,1,1,$mon,$day,$yea))){
				  $_SESSION['reconeccc']['fechahasta']=$yea.'-'.$mon.'-'.$day;
				}else{
					$_REQUEST['fefinctra']='';
					$this->frmError["fefinctra"]=1;
				}
			}else{
				$_REQUEST['fefinctra']='';
				$this->frmError["fefinctra"]=1;
			}
		}else{
			$this->frmError["fefinctra"]=1;
		}
		if($this->frmError["feinictra"]==1 || $this->frmError["fefinctra"]==1){
		  $this->frmError["MensajeError"]="FALTAN DATOS OBLIGATORIOS O LAS FECHAS NO SON VALIDAS";
			$this->FormaReporteCausasCitasMedicas();
			return true;
		}
		if($_SESSION['reconeccc']['fechadesde']<>NULL){
		  $fechaInFiltro="AND e.fecha_turno>='".$_SESSION['reconeccc']['fechadesde']."'";
		}
		if($_SESSION['reconeccc']['fechahasta']<>NULL){
		  $fechaFnFiltro="AND e.fecha_turno<='".$_SESSION['reconeccc']['fechahasta']."'";
		}
		$_SESSION['reconeccc']['razonso']=$_SESSION['recoex']['razonso'];
		$_SESSION['reconeccc']['empresa']=$_SESSION['recoex']['empresa'];

          $centro_utilidad = $_REQUEST['centroU'];
          if (!empty($centro_utilidad) AND $centro_utilidad != '-1')
          { 
               $sql_centro = "AND e.centro_utilidad = '$centro_utilidad'";
               $tabla = ", departamentos e"; 
               $dpto = "AND b.departamento=e.departamento";
          }
           
          $unidad_funcional = $_REQUEST['unidadF'];
          if (!empty($unidad_funcional) AND $unidad_funcional != '-1')
          { 
               $sql_unidad = "AND e.unidad_funcional = '$unidad_funcional'";
               $tabla = ", departamentos e";
               $dpto = "AND b.departamento=e.departamento";
          }
          
          $departamento = $_REQUEST['DptoSel'];
          if (!empty($departamento) AND $departamento != '-1')
          { 
               $sql_dpto = "AND e.departamento = '$departamento'"; 
               $tabla = ", departamentos e";
               $dpto = "AND b.departamento=e.departamento";
          }

          if($_REQUEST['profesional_escojer'] != '-1')     
          {
               $usuario_id = explode(',',$_REQUEST['profesional_escojer']);
               if(!empty($usuario_id[0]))
               { $sql_usuario = "AND b.usuario_id = ".$usuario_id[0].""; }
          }


          list($dbconn) = GetDBconn();
          $query="SELECT a.*
                    FROM 
                    (SELECT x.tipo_diagnostico_id as diagnostico_id, z.diagnostico_nombre,
					y.descripcion as sexo, x.cantidad
                    FROM 
                    (SELECT count(*) as cantidad, a.tipo_diagnostico_id, d.sexo_id
                     FROM hc_diagnosticos_ingreso a, hc_evoluciones b, 
                          ingresos c, pacientes d $tabla
                     WHERE a.evolucion_id=b.evolucion_id
			      AND b.fecha
                     BETWEEN '".$_SESSION['reconeccc']['fechadesde']."' AND '".$_SESSION['reconeccc']['fechahasta']."'
                     $sql_usuario
                     $sql_centro
                     $sql_unidad
                     $sql_dpto  
                     $dpto                
                     AND c.ingreso=b.ingreso
                     AND d.paciente_id = c.paciente_id
                     AND d.tipo_id_paciente = c.tipo_id_paciente          
                     GROUP BY a.tipo_diagnostico_id, d.sexo_id) as x, 
                     
                     tipo_sexo as y, diagnosticos as z 
                     
                     WHERE y.sexo_id = x.sexo_id
                     AND x.tipo_diagnostico_id = z.diagnostico_id
                     
                     ORDER BY x.tipo_diagnostico_id, sexo)as a 
                     ORDER BY a.cantidad DESC;";
          
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resulta = $dbconn->Execute($query);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
          {
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}else{
               while ($data = $resulta->FetchRow())
               {
                    $Tipo_con[$data['diagnostico_id']][] = $data;
                    $Tipo_sexo[$data['sexo']][] = $data;
               }
		}
          
          $RangoI = (date("Y-m-d"));          
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
               { $periodo ="AND date(d.fecha_nacimiento) > '$Rango1'"; $edad = "menor_1"; }
          	if($i == 1)
               { $periodo ="AND date(d.fecha_nacimiento)<= '$Rango1' AND date(d.fecha_nacimiento) > '$Rango5'"; $edad = "entre_1_5";}
          	if($i == 2)
               { $periodo ="AND d.fecha_nacimiento BETWEEN '$Rango14' AND '$Rango5'"; $edad = "entre_5_14";}
          	if($i == 3)
               { $periodo ="AND d.fecha_nacimiento BETWEEN '$Rango44' AND '$Rango15'"; $edad = "entre_15_44"; }
          	if($i == 4)
               { $periodo ="AND d.fecha_nacimiento BETWEEN '$Rango69' AND '$Rango45'"; $edad = "entre_45_69";}
          	if($i == 5)
               { $periodo ="AND d.fecha_nacimiento <= '$Rango70'"; $edad = "mayor_70"; }
               
               
                    $query_edad = "SELECT a.*
                            FROM (SELECT count(*) as cantidad_$edad, 
                              	        a.tipo_diagnostico_id 
                                    FROM hc_diagnosticos_ingreso a, hc_evoluciones b, 
                                    	 ingresos c, pacientes d $tabla
                                    WHERE a.evolucion_id=b.evolucion_id 
                                          AND b.fecha
                                          BETWEEN '".$_SESSION['reconeccc']['fechadesde']."' AND '".$_SESSION['reconeccc']['fechahasta']."'
                                          $sql_usuario
                                          $sql_centro
                                          $sql_unidad
                                          $sql_dpto  
                                          $dpto                
								  AND c.ingreso=b.ingreso 
                                          AND d.paciente_id = c.paciente_id
                                          AND d.tipo_id_paciente = c.tipo_id_paciente
                                          $periodo  
                                          GROUP BY a.tipo_diagnostico_id
                                          ORDER BY a.tipo_diagnostico_id) as a
                           ORDER BY  cantidad_$edad DESC;";
               
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
          }
		
          $this->Tipo_con = $Tipo_con;
          $this->Tipo_sexo = $Tipo_sexo;
          $this->Rango_Edades = $Rango_Edades;
		return true;
	}

}

?>
