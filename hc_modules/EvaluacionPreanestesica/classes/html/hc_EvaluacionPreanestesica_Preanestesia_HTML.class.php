<?php

/**
* Submodulo de Evaluacion Preanestesica
*
* @author Tizziano Perea O.
* @version 1.0
* @package SIIS
* $Id: hc_EvaluacionPreanestesica_Preanestesia_HTML.class.php,v 1.3 2007/11/14 22:00:47 tizziano Exp $
*/

class Preanestesia_HTML
{

     /**
     * Constructor
     *
     * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
     * @access public
     */
	function Preanestesia_HTML()
     {
          return true;
     }
     
     function frmHistoria()
     {
          IncludeClass("Preanestesia",null,"hc","EvaluacionPreanestesica"); 
          
		$evaluacion = new Preanestesia();
          
		$E_Cardio = $evaluacion->Get_EvaluadoresCardiovasculares();

		$E_Respiratorio = $evaluacion->Get_EvaluadoresRespiratorios();
          
		$E_Metabolicos = $evaluacion->Get_EvaluadoresMetabolicos();
                    
          $E_Gastro = $evaluacion->Get_EvaluadoresGastrointestinal();
          
          $E_Renal = $evaluacion->Get_EvaluadoresRenal();
          
          $E_Neuro = $evaluacion->Get_EvaluadoresNeurologicos();
          
          $E_Esqueletico = $evaluacion->Get_EvaluadoresEsqueleticos();
          
          $E_Hematologica = $evaluacion->Get_EvaluadoresHematologicos();
          
          $E_Hepatico = $evaluacion->Get_EvaluadoresHepatico();
          
          $E_Gineco = $evaluacion->Get_EvaluadoresGinecos();

          $E_Intubacion = $evaluacion->Get_EvaluadoresIntubacion();
          
          $E_OtrosF = $evaluacion->Get_EvaluadoresOtrosFactores();
          
          $VectorI = $evaluacion->GetRegistroDiagnosticosI();
          
          $VectorCups = $evaluacion->GetRegistroCargosCirugia();
          
          $CaracteristicasDatos = $evaluacion->GetDatos_DescripcionCaracteristicas();
          
          $SignosDatos = $evaluacion->GetDatos_SignosVitales();
          
          $cardioDatos = $evaluacion->GetDatos_EvaluacionCardio();
          
          $RespDatos = $evaluacion->GetDatos_EvaluacionRespiratorio();
          
          $MetabDatos = $evaluacion->GetDatos_EvaluacionMetabolica();
          
          $GastroDatos = $evaluacion->GetDatos_EvaluacionGastroIntestinal();
          
          $RenalDatos = $evaluacion->GetDatos_EvaluacionRenal();
          
          $NeuroDatos = $evaluacion->GetDatos_EvaluacionNeurologica();
          
          $EsqueDatos = $evaluacion->GetDatos_EvaluacionEsqueletico();
          
          $HemaDatos = $evaluacion->GetDatos_EvaluacionHematologico();
          
          $HepaDatos = $evaluacion->GetDatos_EvaluacionHepatico();
          
          $GinecoDatos = $evaluacion->GetDatos_EvaluacionGineco();
          
          $IntuDatos = $evaluacion->GetDatos_EvaluacionIntubacion();
          
          $OtrosDatos = $evaluacion->GetDatos_EvaluacionOtrosFactores();

		$HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\" BORDER=\"1\">";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"center\" CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="REPORTE DE EVALUACION PREANESTESICA<BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $datosPaciente = SessionGetVar("DatosPaciente");
          $nombrePaciente = $datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido'];
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<LABEL CLASS=\"normal_10N\">Especialidad: </LABEL><LABEL CLASS=\"normal_10\">".$CaracteristicasDatos['especialidad']."</LABEL>";
          $HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
                    
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\" COLSPAN=\"2\">DIAGNOSTICOS PREOPERATORIOS</TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD WIDTH=\"8%\">CODIGO</TD>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"60%\">DIAGNOSTICO</TD>";
          $HTML_WEB_PAGE.="</TR>";
          for($i=0;$i<sizeof($VectorI);$i++)
          {
               $HTML_WEB_PAGE.="<TR CLASS=\"normal_10\">";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\" ALIGN=\"LEFT\">".$VectorI[$i][diagnostico_id]."</TD>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"60%\" ALIGN=\"LEFT\">".$VectorI[$i][diagnostico_nombre]."</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          $HTML_WEB_PAGE.="</TABLE><BR><BR>";
		$HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\" COLSPAN=\"2\">CIRUGIA PROPUESTA</TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD WIDTH=\"8%\">CODIGO</TD>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"60%\">PROCEDIMIENTO</TD>";
          $HTML_WEB_PAGE.="</TR>";
          FOR($i=0;$i<sizeof($VectorCups);$i++)
          {
               $HTML_WEB_PAGE.="<TR CLASS=\"normal_10\">";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\" ALIGN=\"LEFT\">".$VectorCups[$i][cargo_cups]."</TD>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"60%\" ALIGN=\"LEFT\">".$VectorCups[$i][descripcion]."</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          $HTML_WEB_PAGE.="</TABLE><BR><BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $peso = round($SignosDatos['peso'], 0);
          $estatura = round($SignosDatos['estatura'], 0);
          $t_alta = round($SignosDatos['ta'], 0);
          $t_baja = round($SignosDatos['tb'], 0);
          $fc = round($SignosDatos['fc'], 0);
          $fr = round($SignosDatos['fr'], 0);
          $temp = round($SignosDatos['temp'], 0); 
          $imc = round($SignosDatos['imc'], 2);
          
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>SIGNOS VITALES:</LABEL></TD>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>PESO:</LABEL>&nbsp;&nbsp;".$peso."&nbsp;&nbsp;Kg.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>ESTATURA:</LABEL>&nbsp;&nbsp;".$estatura."&nbsp;&nbsp;cms</TD>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>IMC:</LABEL>&nbsp;&nbsp;".$imc."&nbsp;&nbsp;</td>";          
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>FC:</LABEL>&nbsp;&nbsp;".$fc."&nbsp;&nbsp;x min.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>FR:</LABEL>&nbsp;&nbsp;".$fr."&nbsp;&nbsp;x min.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>Tº:</LABEL>&nbsp;&nbsp;".$temp."&nbsp;&nbsp;ºC</td>";
          $HTML_WEB_PAGE.="<TD COLSPAN=\"2\" class='normal_10'><LABEL class='normal_10N'>TENSION:</LABEL>&nbsp;&nbsp;".$t_alta."&nbsp;&nbsp;&nbsp;<B>/</B>&nbsp;&nbsp;";
          $HTML_WEB_PAGE.="".$t_baja."&nbsp;&nbsp;mmHg</td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          if(is_array($cardioDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">CARDIOVASCULAR:  </LABEL><LABEL CLASS=\"normal_10\">".$cardioDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Cardio);$i++)
               {
                    $a++;
                    $b++;
                    if($cardioDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$cardioDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                         
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($RespDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">RESPIRATORIO:  </LABEL><LABEL CLASS=\"normal_10\">".$RespDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Respiratorio);$i++)
               {
                    $a++;
                    $b++;
                    if($RespDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$RespDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
               
          if(is_array($MetabDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">METABOLICO:  </LABEL><LABEL CLASS=\"normal_10\">".$MetabDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Metabolicos);$i++)
               {
                    $a++;
                    $b++;
                    if($MetabDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$MetabDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($GastroDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">GASTROINTESTINAL:  </LABEL><LABEL CLASS=\"normal_10\">".$GastroDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Gastro);$i++)
               {
                    $a++;
                    $b++;
                    if($GastroDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$GastroDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($RenalDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">RENAL:  </LABEL><LABEL CLASS=\"normal_10\">".$RenalDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Renal);$i++)
               {
                    $a++;
                    $b++;
                    if($RenalDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$RenalDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                         
          if(is_array($NeuroDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">NEUROLOGICO:  </LABEL><LABEL CLASS=\"normal_10\">".$NeuroDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Neuro);$i++)
               {
                    $a++;
                    $b++;
                    if($NeuroDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$NeuroDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($EsqueDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">MUSCULO ESQUELETICO:  </LABEL><LABEL CLASS=\"normal_10\">".$EsqueDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Esqueletico);$i++)
               {
                    $a++;
                    $b++;
                    if($EsqueDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$EsqueDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($HemaDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">HEMATOLOGICO:  </LABEL><LABEL CLASS=\"normal_10\">".$HemaDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Hematologica);$i++)
               {
                    $a++;
                    $b++;
                    if($HemaDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$HemaDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
          if(is_array($HepaDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">HEPATICO:  </LABEL><LABEL CLASS=\"normal_10\">".$HepaDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Hepatico);$i++)
               {
                    $a++;
                    $b++;
                    if($HepaDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$HepaDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($GinecoDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">GINECO-OBSTETRICO: </LABEL><LABEL CLASS=\"normal_10\">".$GinecoDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Gineco);$i++)
               {
                    $a++;
                    $b++;
                    if($GinecoDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$GinecoDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($IntuDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">PREDICCION INTUBACION:  </LABEL><LABEL CLASS=\"normal_10\">".$IntuDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_Intubacion);$i++)
               {
                    $a++;
                    $b++;
                    if($IntuDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$IntuDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($OtrosDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">OTROS FACTORES:  </LABEL><LABEL CLASS=\"normal_10\">".$OtrosDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               $b = 0;
               for($i=0;$i<sizeof($E_OtrosF);$i++)
               {
                    $a++;
                    $b++;
                    if($OtrosDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$OtrosDatos['Detalle'][$a]['tipo_descripcion']."</LABEL>";
                         if($b == 5)
                         { $HTML_WEB_PAGE.= "<br>"; $b = 0;}else{ $HTML_WEB_PAGE.= "&nbsp;&nbsp;"; }
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
		if($CaracteristicasDatos['anestesia_previa'])
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\"><BR>";
               $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>ANESTESIAS PREVIAS (TECNICA - FECHA - COMPLICACIONES):</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['anestesia_previa']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
          }
          
          if($CaracteristicasDatos['alergias'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>ALERGIAS:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['alergias']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
          }
          
          if($CaracteristicasDatos['drogas'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>DROGAS:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['drogas']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
		}
                    
          if($CaracteristicasDatos['ayudas_dx'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>AYUDAS DIAGNOSTICAS:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['ayudas_dx']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
          }

		if($CaracteristicasDatos['asa'] == '1')
          { $asa = '1'; }
          elseif($CaracteristicasDatos['asa'] == '2')
          { $asa = '2'; }
          elseif($CaracteristicasDatos['asa'] == '3')
          { $asa = '3'; }
          elseif($CaracteristicasDatos['asa'] == '4')
          { $asa = '4'; }
          elseif($CaracteristicasDatos['asa'] == '5')
          { $asa = '5'; }
           
          $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD WIDTH=\"30%\"><LABEL class='normal_10N'>ASA:  </LABEL><LABEL class='normal_10'>".$asa."</LABEL></TD>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"70%\"><LABEL class='normal_10N'>INDICE DE TRAUMA:  </LABEL><LABEL class='normal_10'>".$CaracteristicasDatos['indice_trauma']."</LABEL></TD>";
          $HTML_WEB_PAGE.="</TR>";          
          
          if($CaracteristicasDatos['reserva'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>RESERVA:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['reserva']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
		}
          
          if($CaracteristicasDatos['plan_anestesico'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>PLAN ANESTESICO:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['plan_anestesico']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
		}
                    
          if($CaracteristicasDatos['premedicacion'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>PREMEDICACION:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['premedicacion']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
               $HTML_WEB_PAGE.="</TABLE>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
          
          $this->salida = $HTML_WEB_PAGE;
          return $this->salida;
     }
     
     function frmConsulta()
     {
          IncludeClass("Preanestesia",null,"hc","EvaluacionPreanestesica"); 
          
		$evaluacion = new Preanestesia();
          
		$E_Cardio = $evaluacion->Get_EvaluadoresCardiovasculares();

		$E_Respiratorio = $evaluacion->Get_EvaluadoresRespiratorios();
          
		$E_Metabolicos = $evaluacion->Get_EvaluadoresMetabolicos();
                    
          $E_Gastro = $evaluacion->Get_EvaluadoresGastrointestinal();
          
          $E_Renal = $evaluacion->Get_EvaluadoresRenal();
          
          $E_Neuro = $evaluacion->Get_EvaluadoresNeurologicos();
          
          $E_Esqueletico = $evaluacion->Get_EvaluadoresEsqueleticos();
          
          $E_Hematologica = $evaluacion->Get_EvaluadoresHematologicos();
          
          $E_Hepatico = $evaluacion->Get_EvaluadoresHepatico();
          
          $E_Gineco = $evaluacion->Get_EvaluadoresGinecos();

          $E_Intubacion = $evaluacion->Get_EvaluadoresIntubacion();
          
          $E_OtrosF = $evaluacion->Get_EvaluadoresOtrosFactores();
          
          $VectorI = $evaluacion->GetRegistroDiagnosticosI();
          
          $VectorCups = $evaluacion->GetRegistroCargosCirugia();
          
          $CaracteristicasDatos = $evaluacion->GetDatos_DescripcionCaracteristicas();
          
          $SignosDatos = $evaluacion->GetDatos_SignosVitales();
          
          $cardioDatos = $evaluacion->GetDatos_EvaluacionCardio();
          
          $RespDatos = $evaluacion->GetDatos_EvaluacionRespiratorio();
          
          $MetabDatos = $evaluacion->GetDatos_EvaluacionMetabolica();
          
          $GastroDatos = $evaluacion->GetDatos_EvaluacionGastroIntestinal();
          
          $RenalDatos = $evaluacion->GetDatos_EvaluacionRenal();
          
          $NeuroDatos = $evaluacion->GetDatos_EvaluacionNeurologica();
          
          $EsqueDatos = $evaluacion->GetDatos_EvaluacionEsqueletico();
          
          $HemaDatos = $evaluacion->GetDatos_EvaluacionHematologico();
          
          $HepaDatos = $evaluacion->GetDatos_EvaluacionHepatico();
          
          $GinecoDatos = $evaluacion->GetDatos_EvaluacionGineco();
          
          $IntuDatos = $evaluacion->GetDatos_EvaluacionIntubacion();
          
          $OtrosDatos = $evaluacion->GetDatos_EvaluacionOtrosFactores();

		$HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\" BORDER=\"1\" CLASS=\"modulo_list_oscuro\">";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"center\" CLASS=\"modulo_table_title\">";
          $HTML_WEB_PAGE.="REPORTE DE EVALUACION PREANESTESICA<BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $datosPaciente = SessionGetVar("DatosPaciente");
          $nombrePaciente = $datosPaciente['primer_nombre']." ".$datosPaciente['segundo_nombre']." ".$datosPaciente['primer_apellido']." ".$datosPaciente['segundo_apellido'];
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<LABEL CLASS=\"normal_10N\">Especialidad: </LABEL><LABEL CLASS=\"normal_10\">".$CaracteristicasDatos['especialidad']."</LABEL>";
          $HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
                    
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\" COLSPAN=\"2\">DIAGNOSTICOS PREOPERATORIOS</TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD WIDTH=\"8%\">CODIGO</TD>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"60%\">DIAGNOSTICO</TD>";
          $HTML_WEB_PAGE.="</TR>";
          for($i=0;$i<sizeof($VectorI);$i++)
          {
               $HTML_WEB_PAGE.="<TR CLASS=\"normal_10\">";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\" ALIGN=\"LEFT\">".$VectorI[$i][diagnostico_id]."</TD>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"60%\" ALIGN=\"LEFT\">".$VectorI[$i][diagnostico_nombre]."</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          $HTML_WEB_PAGE.="</TABLE><BR><BR>";
		$HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\" COLSPAN=\"2\">CIRUGIA PROPUESTA</TD>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="<TD WIDTH=\"8%\">CODIGO</TD>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"60%\">PROCEDIMIENTO</TD>";
          $HTML_WEB_PAGE.="</TR>";
          FOR($i=0;$i<sizeof($VectorCups);$i++)
          {
               $HTML_WEB_PAGE.="<TR CLASS=\"normal_10\">";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\" ALIGN=\"LEFT\">".$VectorCups[$i][cargo_cups]."</TD>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"60%\" ALIGN=\"LEFT\">".$VectorCups[$i][descripcion]."</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          $HTML_WEB_PAGE.="</TABLE><BR><BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $peso = round($SignosDatos['peso'], 0);
          $estatura = round($SignosDatos['estatura'], 0);
          $t_alta = round($SignosDatos['ta'], 0);
          $t_baja = round($SignosDatos['tb'], 0);
          $fc = round($SignosDatos['fc'], 0);
          $fr = round($SignosDatos['fr'], 0);
          $temp = round($SignosDatos['temp'], 0); 
          $imc = round($SignosDatos['imc'], 2);
          
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR WIDTH=\"100%\" ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>SIGNOS VITALES:</LABEL></TD>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>PESO:</LABEL>&nbsp;&nbsp;".$peso."&nbsp;&nbsp;Kg.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>ESTATURA:</LABEL>&nbsp;&nbsp;".$estatura."&nbsp;&nbsp;cms</TD>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>IMC:</LABEL>&nbsp;&nbsp;".$imc."&nbsp;&nbsp;</td>";          
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>FC:</LABEL>&nbsp;&nbsp;".$fc."&nbsp;&nbsp;x min.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>FR:</LABEL>&nbsp;&nbsp;".$fr."&nbsp;&nbsp;x min.</td>";
          $HTML_WEB_PAGE.="<TD class='normal_10'><LABEL class='normal_10N'>Tº:</LABEL>&nbsp;&nbsp;".$temp."&nbsp;&nbsp;ºC</td>";
          $HTML_WEB_PAGE.="<TD COLSPAN=\"2\" class='normal_10'><LABEL class='normal_10N'>TENSION:</LABEL>&nbsp;&nbsp;".$t_alta."&nbsp;&nbsp;&nbsp;<B>/</B>&nbsp;&nbsp;";
          $HTML_WEB_PAGE.="".$t_baja."&nbsp;&nbsp;mmHg</td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          if(is_array($cardioDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">CARDIOVASCULAR:  </LABEL><LABEL CLASS=\"normal_10\">".$cardioDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Cardio);$i++)
               {
                    $a++;
                    if($cardioDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$cardioDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($RespDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">RESPIRATORIO:  </LABEL><LABEL CLASS=\"normal_10\">".$RespDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Respiratorio);$i++)
               {
                    $a++;
                    if($RespDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$RespDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
               
          if(is_array($MetabDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">METABOLICO:  </LABEL><LABEL CLASS=\"normal_10\">".$MetabDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Metabolicos);$i++)
               {
                    $a++;
                    if($MetabDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$MetabDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($GastroDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">GASTROINTESTINAL:  </LABEL><LABEL CLASS=\"normal_10\">".$GastroDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Gastro);$i++)
               {
                    $a++;
                    if($GastroDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$GastroDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($RenalDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">RENAL:  </LABEL><LABEL CLASS=\"normal_10\">".$RenalDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Renal);$i++)
               {
                    $a++;
                    if($RenalDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$RenalDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                         
          if(is_array($NeuroDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">NEUROLOGICO:  </LABEL><LABEL CLASS=\"normal_10\">".$NeuroDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Neuro);$i++)
               {
                    $a++;
                    if($NeuroDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$NeuroDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($EsqueDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">MUSCULO ESQUELETICO:  </LABEL><LABEL CLASS=\"normal_10\">".$EsqueDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Esqueletico);$i++)
               {
                    $a++;
                    if($EsqueDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$EsqueDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($HemaDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">HEMATOLOGICO:  </LABEL><LABEL CLASS=\"normal_10\">".$HemaDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Hematologica);$i++)
               {
                    $a++;
                    if($HemaDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$HemaDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
          if(is_array($HepaDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">HEPATICO:  </LABEL><LABEL CLASS=\"normal_10\">".$HepaDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Hepatico);$i++)
               {
                    $a++;
                    if($HepaDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$HepaDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($GinecoDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">GINECO-OBSTETRICO: </LABEL><LABEL CLASS=\"normal_10\">".$GinecoDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Gineco);$i++)
               {
                    $a++;
                    if($GinecoDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$GinecoDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($IntuDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">PREDICCION INTUBACION:  </LABEL><LABEL CLASS=\"normal_10\">".$IntuDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_Intubacion);$i++)
               {
                    $a++;
                    if($IntuDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$IntuDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($OtrosDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">OTROS FACTORES:  </LABEL><LABEL CLASS=\"normal_10\">".$OtrosDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"8%\"><LABEL CLASS=\"normal_10N\">PRESENCIA </LABEL><BR>";
               $a = 0;
               for($i=0;$i<sizeof($E_OtrosF);$i++)
               {
                    $a++;
                    if($OtrosDatos['Detalle'][$a])
                    {
                         $HTML_WEB_PAGE.="<B>-</B> <LABEL CLASS=\"normal_10\">".$OtrosDatos['Detalle'][$a]['tipo_descripcion']."</LABEL><BR>";
                    }
               }
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
		if($CaracteristicasDatos['anestesia_previa'])
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\"><BR>";
               $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>ANESTESIAS PREVIAS (TECNICA - FECHA - COMPLICACIONES):</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['anestesia_previa']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
          }
          
          if($CaracteristicasDatos['alergias'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>ALERGIAS:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['alergias']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
          }
          
          if($CaracteristicasDatos['drogas'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>DROGAS:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['drogas']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
		}
                    
          if($CaracteristicasDatos['ayudas_dx'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>AYUDAS DIAGNOSTICAS:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['ayudas_dx']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
          }

		if($CaracteristicasDatos['asa'] == '1')
          { $asa = '1'; }
          elseif($CaracteristicasDatos['asa'] == '2')
          { $asa = '2'; }
          elseif($CaracteristicasDatos['asa'] == '3')
          { $asa = '3'; }
          elseif($CaracteristicasDatos['asa'] == '4')
          { $asa = '4'; }
          elseif($CaracteristicasDatos['asa'] == '5')
          { $asa = '5'; }
           
          $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
          $HTML_WEB_PAGE.="<TD WIDTH=\"30%\"><LABEL class='normal_10N'>ASA:  </LABEL><LABEL class='normal_10'>".$asa."</LABEL></TD>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"70%\"><LABEL class='normal_10N'>INDICE DE TRAUMA:  </LABEL><LABEL class='normal_10'>".$CaracteristicasDatos['indice_trauma']."</LABEL></TD>";
          $HTML_WEB_PAGE.="</TR>";          
          
          if($CaracteristicasDatos['reserva'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>RESERVA:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['reserva']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
		}
          
          if($CaracteristicasDatos['plan_anestesico'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>PLAN ANESTESICO:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['plan_anestesico']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
		}
                    
          if($CaracteristicasDatos['premedicacion'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>PREMEDICACION:</LABEL><BR>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;<LABEL class='normal_10'>".$CaracteristicasDatos['premedicacion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TR>";          
               $HTML_WEB_PAGE.="</TABLE>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
          
          $this->salida = $HTML_WEB_PAGE;
          return $this->salida;
     }
     
     /**
     * Funcion que señaliza una palabra para simbolizar que esta en estado de alerta
     * @return boolean
     */
     function SetStyle($campo)
     {
          if ($this->frmError[$campo] || $campo=="MensajeError")
          {
               if ($campo=="MensajeError")
               {
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
               return ("label_error");
          }
          return ("label");
     }

     /**
     * Metodo para generar la vista HTML.
     *
     * @param array $E_Cardio Evaluadores cardiovasculares.
     * @param array $E_Respiratorio Evaluadores respiratorios.
     * @param array $E_Metabolicos Evaluadores metabolicos.
     * @param array $E_Gastro Evaluadores gastrointestinales.
     * @param array $E_Renal Evaluadores renales.
     * @param array $E_Neuro Evaluadores neurologicos.
     * @param array $E_Esqueletico Evaluadores musculo-esqueleticos.
     * @param array $E_Hematologica Evaluadores hematologicos.
     * @param array $E_Gineco Evaluadores ginecos
     * @param array $E_Intubacion Evaluadores intubacion
     * @param array $E_OtrosF Otros evaluadores preanestesicos
     * @param array $VectorI Diagnosticos preoperatorios insertados
     * @param int $enlace Valor para activacion o desactivacion de opciones
     * @param array $VectorCups Cargos de la cirugia a realizarle al paciente
     * @param int $enlace1 Valor para activacion o desactivacion de opciones
     * @return string
     * @access public
     */
     function frmForma($E_Cardio, $E_Respiratorio, $E_Metabolicos, $E_Gastro, $E_Renal, $E_Neuro, $E_Esqueletico, $E_Hematologica, $E_Hepatico, $E_Gineco, $E_Intubacion, $E_OtrosF,
     			   $VectorI, $enlace, $VectorCups, $enlace1, $CaracteristicasDatos, $SignosDatos, $cardioDatos, $RespDatos, $MetabDatos, $GastroDatos, $RenalDatos, $NeuroDatos,
                       $EsqueDatos, $HemaDatos, $HepaDatos, $GinecoDatos, $IntuDatos, $OtrosDatos)
     {
          $this->salida.= ThemeAbrirTablaSubModulo('EVALUACION PREANESTESICA');
          
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          $this->salida.= 		$this->SetStyle("MensajeError");
          $this->salida.= "	</table><br>";
          
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\">";
          
          $accion=ModuloHCGetURL(SessionGetVar("Evolucion"),SessionGetVar("Paso"),0,'',false,array('accion_EvaluacionPre'=>'InsertarDatos'));
          $this->salida.="<form name=\"forma_EvaluacionPre\" action=\"$accion\" method=\"POST\">";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          //Especialidad
          if($CaracteristicasDatos['especialidad'])
          { $_Especialidad = $CaracteristicasDatos['especialidad']; } else { $_Especialidad = NULL; }
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"10%\"><label class=\"label\">ESPECIALIDAD:</label></td>";
          $this->salida.= "	<td width=\"90%\"><input type=\"text\" class=\"input-text\" name=\"especialidad\" maxlength=\"20\" value=\"".$_Especialidad."\"></td>";
          $this->salida.= "	</tr>";
          //Dx Pre-operatorio
          $this->salida.= "<tr>";
          $this->salida.= "	<td width=\"10%\" align=\"center\"><label class=\"label\">DIAGNOSTICO PREOPERATORIO:</label></td>";
          $this->salida.= "	<td width=\"90%\"><br>";
          if($VectorI)
          {
               $this->salida.="<div id=\"DXAsignadosI\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"4\">DIAGNOSTICOS</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"8%\">CODIGO</td>";
               $this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($VectorI);$i++)
               {
                    if( $i % 2){$estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_claro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td width=\"8%\" align=\"center\">".$VectorI[$i][diagnostico_id]."</td>";
                    $this->salida.="<td width=\"60%\" align=\"left\">".$VectorI[$i][diagnostico_nombre]."</td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
               $this->salida.="</div>";
          }
          else
          {
               $javaAccion = "javascript:MostrarCapa('ContenedorDiagnosticos');IniciarCapaDX('BUSQUEDA DE DIAGNOSTICOS','ContenedorDiagnosticos');CargarContenedor('ContenedorDiagnosticos');";
               $this->salida.="<div id=\"enlace\" align=\"left\"><a href=\"$javaAccion\"><b>BUSCAR DIAGNOSTICOS</b></a></div>";
               $this->salida.="<div id=\"dx_insertarI\" align=\"center\"></div>";
          }
          
          //Capa Contenedor de DX.
          $this->salida.="<div id='ContenedorDiagnosticos' class='d2Container' style=\"display:none\">";
          $this->salida.= "    <div id='titulo' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida.= "    <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorDiagnosticos');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida.= "    <div id='ContenedorDiagnosticosII'>\n";
          
          $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"4\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type=\"text\" class=\"input-text\" size =\"6\" maxlength =\"6\" name=\"codigoDX\" id=\"codigo\" onkeyup=\"xajax_BusquedaDX(document.getElementById('codigo').value, '')\"></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type=\"text\" size=\"50\" class=\"input-text\" name=\"diagnosticoDX\" id=\"descripcion\" onkeyup=\"xajax_BusquedaDX('', document.getElementById('descripcion').value)\"></td>" ;
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
          
          $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\" width=\"100%\">";
          $this->salida.="	<div>\n";
          $this->salida.="		<div id=\"lista\"></div>\n";
          $this->salida.="	</div>\n";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</div>\n";     
          $this->salida.="</div>";
          
          $this->salida.="</td>";
          $this->salida.="</tr>";

          //Cirugia Propuesta
          $this->salida.= "<tr>";
          $this->salida.= "	<td width=\"10%\" align=\"center\"><label class=\"label\">CIRUGIA PROPUESTA:</label></td>";
          $this->salida.= "<td width=\"90%\"><br>";
          if($VectorCups)
          {
               $this->salida.="<div id=\"CupsAsignadosI\">";
               $this->salida.="<table  align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="<td align=\"center\" colspan=\"4\">CIRUGIA PROPUESTA</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"8%\">CODIGO</td>";
               $this->salida.="<td width=\"60%\">PROCEDIMIENTO</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($VectorCups);$i++)
               {
                    if( $i % 2){$estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_claro';}
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="<td width=\"8%\" align=\"center\">".$VectorCups[$i][cargo_cups]."</td>";
                    $this->salida.="<td width=\"60%\" align=\"left\">".$VectorCups[$i][descripcion]."</td>";
                    $this->salida.="</tr>";
               }
               $this->salida.="</table><br>";
               $this->salida.="</div>";
          }
          else
          {
               $javaAccionII = "javascript:MostrarCapa('ContenedorCups');IniciarCapaCups('BUSQUEDA DE CIRUGIAS','ContenedorCups');CargarContenedor('ContenedorCups');";
               $this->salida.="<div id=\"enlaceCUPS\" align=\"left\"><a href=\"$javaAccionII\"><b>BUSCAR CARGOS</b></a></div>";
               $this->salida.="<div id=\"CUPS_insertar\" align=\"center\"></div>";
          }
          
          //Capa Contenedor Cargos.
          $this->salida.="<div id='ContenedorCups' class='d2Container' style=\"display:none\">";
          $this->salida.= "    <div id='titulo_X' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
          $this->salida.= "    <div id='cerrar_X' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContenedorCups');\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
          $this->salida.= "    <div id='ContenedorCupsII'>\n";
          
          $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" colspan=\"4\">BUSQUEDA DE CIRUGIAS</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida.="<td width=\"5%\" align='center'><input type=\"text\" class=\"input-text\" size =\"6\" maxlength =\"6\" name=\"codigocups$pfj\" id=\"codigocups\" onkeyup=\"xajax_BusquedaCups(document.getElementById('codigocups').value, '')\"></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida.="<td width=\"55%\" align='center'><input type=\"text\" size=\"50\" class=\"input-text\" name=\"diagnosticocups$pfj\" id=\"descripcioncups\" onkeyup=\"xajax_BusquedaCups('', document.getElementById('descripcioncups').value)\"></td>" ;
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
          
          $this->salida.="<table  align=\"center\" border=\"0\" width=\"100%\">";
          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\" width=\"100%\">";
          $this->salida.="	<div>\n";
          $this->salida.="		<div id=\"listacups\"></div>\n";
          $this->salida.="	</div>\n";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="</table>";
          $this->salida.="</div>\n";     
          $this->salida.="</div>";
          //Fin Capa Contenedor Cargos.
		
                    
          $this->salida.="</td>";
          $this->salida.="</tr>";
          //Funciones JavaScript
          
          $javaC = "<script>\n";
          $javaC .= "   var contenedor;\n";
          $javaC .= "   var titulo = 'titulo';\n";
          $javaC .= "   var hiZ = 2;\n";
          $javaC.="	    Datos = new Array();\n";
          $javaC.="	    Diagnosticos = new Array();\n"; 
          $javaC.="	    var Retener;\n"; 
          
          $javaC .= "   function CargarContenedor(Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "        contenedor = Elemento;\n";
          $javaC .= "   }\n";

          $javaC .= "   function IniciarCapaDX(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 680, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/18, xScrollTop()+70);\n";
          $javaC .= "       document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       ele = xGetElementById('titulo');\n";
          $javaC .= "       xResizeTo(ele, 660, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrar');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 660, 0);\n";
          $javaC .= "   }\n";         
          
          $javaC .= "   function IniciarCapaCups(tit, Elemento)\n";
          $javaC .= "   {\n";
          $javaC .= "	   Capa = xGetElementById(Elemento);\n";
          $javaC .= "	   xResizeTo(Capa, 680, 'auto');\n";
          $javaC .= "       xMoveTo(Capa, xClientWidth()/18, xScrollTop()+70);\n";
          $javaC .= "       document.getElementById('titulo_X').innerHTML = '<center>'+tit+'</center>';\n";
          $javaC .= "       ele = xGetElementById('titulo_X');\n";
          $javaC .= "       xResizeTo(ele, 660, 20);\n";
          $javaC .= "       xMoveTo(ele, 0, 0);\n";
          $javaC .= "       xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
          $javaC .= "       ele = xGetElementById('cerrar_X');\n";
          $javaC .= "       xResizeTo(ele,20, 20);\n";
          $javaC .= "       xMoveTo(ele, 660, 0);\n";
          $javaC .= "   }\n";         
          
          $javaC .= "   function myOnDragStart(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "     window.status = '';\n";
          $javaC .= "     if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
          $javaC .= "     else xZIndex(ele, hiZ++);\n";
          $javaC .= "     ele.myTotalMX = 0;\n";
          $javaC .= "     ele.myTotalMY = 0;\n";
          $javaC .= "   }\n";
          
          $javaC .= "   function myOnDrag(ele, mdx, mdy)\n";
          $javaC .= "   {\n";
          $javaC .= "     if (ele.id == titulo) {\n";
          $javaC .= "       xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
          $javaC .= "     }\n";
          $javaC .= "     else {\n";
          $javaC .= "       xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
          $javaC .= "     }  \n";
          $javaC .= "     ele.myTotalMX += mdx;\n";
          $javaC .= "     ele.myTotalMY += mdy;\n";
          $javaC .= "   }\n";
               
          $javaC .= "   function myOnDragEnd(ele, mx, my)\n";
          $javaC .= "   {\n";
          $javaC .= "   }\n";
          
          $javaC.= "	function MostrarCapa(Elemento)\n";
          $javaC.= "	{\n;";
          $javaC.= "		capita = xGetElementById(Elemento);\n";
          $javaC.= "		capita.style.display = \"\";\n";
          $javaC.= "	}\n";
          
          $javaC.= "	function Cerrar(Elemento, Tipo)\n";
          $javaC.= "	{\n";
          $javaC.= "		capita = xGetElementById(Elemento);\n";          
          $javaC.= "		capita.style.display = \"none\";\n";
          $javaC.= "	}\n";                    

          $javaC.= "	function CerrarCapa()\n";
          $javaC.= "	{\n";
          $javaC.= "     	capita = xGetElementById('ContenedorDiagnosticos');\n";
          $javaC.= "     	capita.style.display = \"none\";\n";
          $javaC.= "     	document.getElementById('codigo').value = '';\n";
          $javaC.= "     	document.getElementById('descripcion').value = '';\n";
		$javaC.= "     	document.getElementById('lista').innerHTML = '';\n";
          $javaC.= "     	Datos = '';\n";
          $javaC.= "     	Datos = new Array();\n";
          $javaC.= "	}\n";                    
          
          $javaC.= "	function CerrarCapaCups()\n";
          $javaC.= "	{\n";
          $javaC.= "     	capita = xGetElementById('ContenedorCups');\n";
          $javaC.= "     	capita.style.display = \"none\";\n";
          $javaC.= "     	document.getElementById('codigocups').value = '';\n";
          $javaC.= "     	document.getElementById('descripcioncups').value = '';\n";
		$javaC.= "     	document.getElementById('listacups').innerHTML = '';\n";
          $javaC.= "     	Datos = '';\n";
          $javaC.= "     	Datos = new Array();\n";
          $javaC.= "	}\n";                    

          $javaC.="		function BusquedaDX(Code, Dx, pag)\n";
          $javaC.="		{\n";
          $javaC.="			xajax_BusquedaDX(Code, Dx, pag);\n";
          $javaC.="		}\n";
          
          $javaC.="		function BusquedaCups(Code, Dx, pag)\n";
          $javaC.="		{\n";
          $javaC.="			xajax_BusquedaCups(Code, Dx, pag);\n";
          $javaC.="		}\n";

          $javaC.="		function LlenarVectorDX(Code, Dx, Sw, Op)\n";
          $javaC.="		{\n";
          $javaC.="			if(Code != '')\n";
          $javaC.="			{\n";          
          $javaC.="				if(Datos.length == 0)\n";
          $javaC.="				{\n";
          $javaC.="					Datos[0] = Code;\n";
          $javaC.="					Diagnosticos[0] = Dx;\n";
          $javaC.="				}\n";
          $javaC.="				else\n";
          $javaC.="				{\n";
          $javaC.="					a = Datos.length ++;\n";
          $javaC.="					Datos[a] = Code;\n";
          $javaC.="					Diagnosticos[a] = Dx;\n";        
          $javaC.="				}\n";
          $javaC.="			}\n";
          $javaC.= "     	if(Sw == 1)\n";
          $javaC.= "     	{\n";
          $javaC.= "     		if(Op == 1)\n";
          $javaC.= "     		{\n";
          $javaC.= "     			xajax_VectorCups(Datos, Diagnosticos);\n";
          $javaC.= "     		}\n";
          $javaC.= "     		else\n";
          $javaC.= "     		{\n";
          $javaC.= "     			xajax_VectorDX(Datos, Diagnosticos);\n";
          $javaC.= "     		}\n";
          $javaC.= "     	}\n";
          $javaC.="		}\n";
          
          $javaC.="		function ActivarDesplegarConsulta(Evolucion, Identificador)\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_DesplegarConsulta(Evolucion, Identificador);\n";
          $javaC.="		}\n";
          
          $javaC.="		function OcultarCapa(Identificador)\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_DesplegarConsulta('', Identificador, 1);\n";
          $javaC.="		}\n";
          
          $javaC.="		function VarIngresoEgreso(Tipo)\n";
          $javaC.="		{\n";
          $javaC.="		     xajax_RegistrarVar(Tipo);\n";
          $javaC.="		}\n";
         
          $javaC.= "</script>\n";
          $this->salida.= $javaC;

          if(is_array($SignosDatos))
          {
          	$peso = round($SignosDatos['peso'], 0);
               $estatura = round($SignosDatos['estatura'], 0);
               $ta = round($SignosDatos['ta'], 0);
               $tb = round($SignosDatos['tb'], 0);
               $fc = round($SignosDatos['fc'], 0);
               $fr = round($SignosDatos['fr'], 0);
               $temp = round($SignosDatos['temp'], 0); 
               $imc = round($SignosDatos['imc'], 2);
          }
          else
          {
          	$peso = $estatura = $ta = $tb = $fc = $fr = $temp = $imc = NULL;
          }
          //Signos Vitales
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" colspan=\"2\" align=\"center\"><label class=\"label\">SIGNOS VITALES</label></td>";
          $this->salida.= "	</tr>";          
          $this->salida.= "	<tr>";          
          $this->salida.= "	<td width=\"100%\" colspan=\"2\">";
          $this->salida.= "	<table width=\"100%\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>PESO:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_peso\" value=\"".$peso."\" size=\"5\" maxlength=\"3\">&nbsp;Kg.";
          $this->salida.= "	</td>";
          $this->salida.= "	<td>ESTATURA:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_estatura\" value=\"".$estatura."\" size=\"5\" maxlength=\"3\">&nbsp;Cms.";
          $this->salida.= "	</td>";
          $this->salida.= "	<td colspan=\"2\">TENSION:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_ta\" size=\"5\" value=\"".$ta."\" maxlength=\"3\">&nbsp;&nbsp;<b>/</b>";
          $this->salida.= "	&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_tb\" size=\"5\" value=\"".$tb."\" maxlength=\"3\">";
          $this->salida.= "	</td>";
          $this->salida.= "	<td>FC:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_fc\" size=\"5\" value=\"".$fc."\" maxlength=\"2\">&nbsp;X min";
          $this->salida.= "	</td>";
          $this->salida.= "	<td>FR:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_fr\" size=\"5\" value=\"".$fr."\" maxlength=\"2\">&nbsp;X min";
          $this->salida.= "	</td>";
          $this->salida.= "	<td>TEMP:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_temp\" size=\"5\" value=\"".$temp."\" maxlength=\"4\">&nbsp;ºC";
          $this->salida.= "	</td>";
          $this->salida.= "	<td>IMC:&nbsp;<input type=\"text\" class=\"input-text\" name=\"sig_imc\" value=\"".$imc."\" size=\"5\" readoly>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Cardiovascular
		if(is_array($cardioDatos))
          { $_CardioDesc = $cardioDatos['Maestro']['descripcion']; } else { $_CardioDesc = NULL; }
          
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">CARDIOVASCULAR: </label>";
          $a = 0;
          $index = 0;
          $_CardioDet = "";
          for($i=0; $i<sizeof($E_Cardio); $i++)
          {
               $a++;
               $index++;
               if(is_array($cardioDatos))
               {
                    if($cardioDatos['Detalle'][$index])
                    { $_CardioDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_cardio[]\" id=\"e_cardio\" value=\"".$E_Cardio[$i]['tipo_evaluacion']."\" $_CardioDet>&nbsp;".$E_Cardio[$i]['descripcion']."";
               
               if($a == 7)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;&nbsp;"; }
               
               $_CardioDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_cardio\" id=\"desc_cardio\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_CardioDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Cardiovascular
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Respiratorio
		if(is_array($RespDatos))
          { $_RespDesc = $RespDatos['Maestro']['descripcion']; } else { $_RespDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">RESPIRATORIO: </label>";
          $a = 0;
          $index = 0;
          $_RespDet = "";
          for($i=0; $i<sizeof($E_Respiratorio); $i++)
          {
               $a++;
               $index++;
               if(is_array($RespDatos))
               {
                    if($RespDatos['Detalle'][$index])
                    { $_RespDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_respiratorio[]\" id=\"e_respiratorio\" value=\"".$E_Respiratorio[$i]['tipo_evaluacion']."\" $_RespDet>&nbsp;".$E_Respiratorio[$i]['descripcion']."";
               if($a == 7)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;&nbsp;"; }
               
               $_RespDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_respiratorio\" id=\"desc_respiratorio\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_RespDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Respiratorio
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Metabolico
		if(is_array($MetabDatos))
          { $_MetabDesc = $MetabDatos['Maestro']['descripcion']; } else { $_MetabDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">METABOLICO: </label>";
          $a = 0;
          $index = 0;
          $_MetabDet = "";
          for($i=0; $i<sizeof($E_Metabolicos); $i++)
          {
               $a++;
               $index++;
               if(is_array($MetabDatos))
               {
                    if($MetabDatos['Detalle'][$index])
                    { $_MetabDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_metabolico[]\" id=\"e_metabolico\" value=\"".$E_Metabolicos[$i]['tipo_evaluacion']."\" $_MetabDet>&nbsp;".$E_Metabolicos[$i]['descripcion']."";
               if($a == 7)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_MetabDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_metabolico\" id=\"desc_metabolico\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_MetabDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Metabolico
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Gastrointestinal
		if(is_array($GastroDatos))
          { $_GastroDesc = $GastroDatos['Maestro']['descripcion']; } else { $_GastroDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">GASTROINTESTINAL: </label>";
          $a = 0;
          $index = 0;
          $_GastroDet = "";
          for($i=0; $i<sizeof($E_Gastro); $i++)
          {
               $a++;
               $index++;
               if(is_array($GastroDatos))
               {
                    if($GastroDatos['Detalle'][$index])
                    { $_GastroDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_gastro[]\" id=\"e_gastro\" value=\"".$E_Gastro[$i]['tipo_evaluacion']."\" $_GastroDet>&nbsp;".$E_Gastro[$i]['descripcion']."";
               if($a == 5)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_GastroDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_gastro\" id=\"desc_gastro\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_GastroDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Gastrointestinal
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Renal
		if(is_array($RenalDatos))
          { $_RenalDesc = $RenalDatos['Maestro']['descripcion']; } else { $_RenalDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">RENAL: </label>";
          $a = 0;
          $index = 0;
          $_RenalDet = "";
          for($i=0; $i<sizeof($E_Renal); $i++)
          {
               $a++;
               $index++;
               if(is_array($RenalDatos))
               {
                    if($RenalDatos['Detalle'][$index])
                    { $_RenalDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_renal[]\" id=\"e_renal\" value=\"".$E_Renal[$i]['tipo_evaluacion']."\" $_RenalDet>&nbsp;".$E_Renal[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_RenalDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_renal\" id=\"desc_renal\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_RenalDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Renal
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Neurologico
		if(is_array($NeuroDatos))
          { $_NeuroDesc = $NeuroDatos['Maestro']['descripcion']; } else { $_NeuroDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">NEUROLOGICO: </label>";
          $a = 0;
          $index = 0;
          $_NeuroDet = "";
          for($i=0; $i<sizeof($E_Neuro); $i++)
          {
               $a++;
               $index++;
               if(is_array($NeuroDatos))
               {
                    if($NeuroDatos['Detalle'][$index])
                    { $_NeuroDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_neuro[]\" id=\"e_neuro\" value=\"".$E_Neuro[$i]['tipo_evaluacion']."\" $_NeuroDet>&nbsp;".$E_Neuro[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_NeuroDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_neuro\" id=\"desc_neuro\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_NeuroDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Neurologico
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Esqueletico
		if(is_array($EsqueDatos))
          { $_EsqueDesc = $EsqueDatos['Maestro']['descripcion']; } else { $_EsqueDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">MUSCULO ESQUELETICO: </label>";
          $a = 0;
          $index = 0;
          $_EsqueDet = "";
          for($i=0; $i<sizeof($E_Esqueletico); $i++)
          {
               $a++;
               $index++;
               if(is_array($EsqueDatos))
               {
                    if($EsqueDatos['Detalle'][$index])
                    { $_EsqueDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_esqueletico[]\" id=\"e_esqueletico\" value=\"".$E_Esqueletico[$i]['tipo_evaluacion']."\" $_EsqueDet>&nbsp;".$E_Esqueletico[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_EsqueDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_esqueletico\" id=\"desc_esqueletico\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_EsqueDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Esqueletico
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Hematologico
		if(is_array($HemaDatos))
          { $_HemaDesc = $HemaDatos['Maestro']['descripcion']; } else { $_HemaDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">HEMATOLOGICO: </label>";
          $a = 0;
          $index = 0;
          $_HemaDet = "";
          for($i=0; $i<sizeof($E_Hematologica); $i++)
          {
               $a++;
               $index++;
               if(is_array($HemaDatos))
               {
                    if($HemaDatos['Detalle'][$index])
                    { $_HemaDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_hematologico[]\" id=\"e_hematologico\" value=\"".$E_Hematologica[$i]['tipo_evaluacion']."\" $_HemaDet>&nbsp;".$E_Hematologica[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_HemaDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_hematologico\" id=\"desc_hematologico\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_HemaDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Hematologico
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Hepatico
		if(is_array($HepaDatos))
          { $_HepaDesc = $HepaDatos['Maestro']['descripcion']; } else { $_HepaDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">HEPATICO: </label>";
          $a = 0;
          $index = 0;
          $_HepaDet = "";
          for($i=0; $i<sizeof($E_Hepatico); $i++)
          {
               $a++;
               $index++;
               if(is_array($HepaDatos))
               {
                    if($HepaDatos['Detalle'][$index])
                    { $_HepaDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_hepatico[]\" id=\"e_hepatico\" value=\"".$E_Hepatico[$i]['tipo_evaluacion']."\" $_HepaDet>&nbsp;".$E_Hepatico[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_HepaDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_hepatico\" id=\"desc_hepatico\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_HepaDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Hepatico
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Gineco
		if(is_array($GinecoDatos))
          { $_GinecoDesc = $GinecoDatos['Maestro']['descripcion']; } else { $_GinecoDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">GINECO-OBSTETRICO: </label>";
          $a = 0;
          $index = 0;
          $_GinecoDet = "";
          for($i=0; $i<sizeof($E_Gineco); $i++)
          {
               $a++;
               $index++;
               if(is_array($GinecoDatos))
               {
                    if($GinecoDatos['Detalle'][$index])
                    { $_GinecoDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_gineco[]\" id=\"e_gineco\" value=\"".$E_Gineco[$i]['tipo_evaluacion']."\" $_GinecoDet>&nbsp;".$E_Gineco[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_GinecoDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_gineco\" id=\"desc_gineco\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_GinecoDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Gineco
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";

          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Intubacion
		if(is_array($IntuDatos))
          { $_IntuDesc = $IntuDatos['Maestro']['descripcion']; } else { $_IntuDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">PREDICCION INTUBACION: </label>";
          $a = 0;
          $index = 0;
          $_IntuDet = "";
          for($i=0; $i<sizeof($E_Intubacion); $i++)
          {
               $a++;
               $index++;
               if(is_array($IntuDatos))
               {
                    if($IntuDatos['Detalle'][$index])
                    { $_IntuDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_intubacion[]\" id=\"e_intubacion\" value=\"".$E_Intubacion[$i]['tipo_evaluacion']."\" $_IntuDet>&nbsp;".$E_Intubacion[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_IntuDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_intubacion\" id=\"desc_intubacion\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_IntuDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Intubacion
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";

          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Otros
		if(is_array($OtrosDatos))
          { $_OtrosDesc = $OtrosDatos['Maestro']['descripcion']; } else { $_OtrosDesc = NULL; }
          
		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">OTROS FACTORES: </label>";
          $a = 0;
          $index = 0;
          $_OtrosDet = "";
          for($i=0; $i<sizeof($E_OtrosF); $i++)
          {
               $a++;
               $index++;
               if(is_array($OtrosDatos))
               {
                    if($OtrosDatos['Detalle'][$index])
                    { $_OtrosDet = 'checked'; }
               }
               $this->salida.= "<input type=\"checkbox\" name=\"e_otros[]\" id=\"e_otros\" value=\"".$E_OtrosF[$i]['tipo_evaluacion']."\" $_OtrosDet>&nbsp;".$E_OtrosF[$i]['descripcion']."";
               if($a == 6)
               { $this->salida.= "<br>"; $a = 0;}else{ $this->salida.= "&nbsp;"; }
               
               $_OtrosDet = "";
          }
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_otros\" id=\"desc_otros\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_OtrosDesc</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Otros
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
                    
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Anestesia Previa
          if($CaracteristicasDatos['anestesia_previa'])
          { $_AnesPrev = $CaracteristicasDatos['anestesia_previa']; } else { $_AnesPrev = NULL; }

		$this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">ANESTESIAS PREVIAS (TECNICA - FECHA - COMPLICACIONES)</label>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_prevanestesia\" id=\"desc_prevanestesia\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_AnesPrev</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Anestesia Previa
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Alergias
          if($CaracteristicasDatos['alergias'])
          { $_Alergias = $CaracteristicasDatos['alergias']; } else { $_Alergias = NULL; }
		
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">ALERGIAS</label>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_alergias\" id=\"desc_alergias\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_Alergias</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Alergias
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Drogas
          if($CaracteristicasDatos['drogas'])
          { $_Drogas = $CaracteristicasDatos['drogas']; } else { $_Drogas = NULL; }
		
          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">DROGAS</label>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_drogas\" id=\"desc_drogas\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_Drogas</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Drogas
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Ayudas
          if($CaracteristicasDatos['ayudas_dx'])
          { $_AyudasDx = $CaracteristicasDatos['ayudas_dx']; } else { $_AyudasDx = NULL; }

          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"justyfi\"><label class=\"label\">AYUDAS DIAGNOSTICAS</label>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"100%\" align=\"center\">";
          $this->salida.= "		<textarea name=\"desc_ayudasdx\" id=\"desc_ayudasdx\" class=\"input-text\" cols=\"100%\" rows=\"4\">$_AyudasDx</textarea>";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          $this->salida.= "	</table><br>";
          //Alergias
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td>";
          //Asa
          if($CaracteristicasDatos['asa'] == '1')
          { $uno = 'checked'; }
          elseif($CaracteristicasDatos['asa'] == '2')
          { $dos = 'checked'; }
          elseif($CaracteristicasDatos['asa'] == '3')
          { $tres = 'checked'; }
          elseif($CaracteristicasDatos['asa'] == '4')
          { $cuatro = 'checked'; }
          elseif($CaracteristicasDatos['asa'] == '5')
          { $cinco = 'checked'; }
          

          $this->salida.= "	<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"50%\" align=\"left\"><label class=\"label\">ASA: </label>";
          $this->salida.= "		<input type=\"radio\" name=\"asa\" id=\"asa\" value=\"1\" $uno>&nbsp;<label class=\"label\">1</label>";
          $this->salida.= "		<input type=\"radio\" name=\"asa\" id=\"asa\" value=\"2\" $dos>&nbsp;<label class=\"label\">2</label>";
          $this->salida.= "		<input type=\"radio\" name=\"asa\" id=\"asa\" value=\"3\" $tres>&nbsp;<label class=\"label\">3</label>";
          $this->salida.= "		<input type=\"radio\" name=\"asa\" id=\"asa\" value=\"4\" $cuatro>&nbsp;<label class=\"label\">4</label>";
          $this->salida.= "		<input type=\"radio\" name=\"asa\" id=\"asa\" value=\"5\" $cinco>&nbsp;<label class=\"label\">5</label>";
          $this->salida.= "	</td>";
     
          if($CaracteristicasDatos['indice_trauma'])
          { $_indice = $CaracteristicasDatos['indice_trauma']; } else { $_indice = NULL; }
     
          $this->salida.= "	<td width=\"50%\" align=\"left\"><label class=\"label\">INDICE DE TRAUMA: </label>";
          $this->salida.= "		<input type=\"text\" name=\"indice_t\" id=\"indice_t\" class=\"input-text\" value=\"".$_indice."\" maxlength=\"2\" size=\"3\">";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>"; 

          if($CaracteristicasDatos['reserva'])
          { $_reserva = $CaracteristicasDatos['reserva']; } else { $_reserva = NULL; }
                             
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"50%\" align=\"left\"><label class=\"label\">RESERVA: </label>";
          $this->salida.= "		<input type=\"text\" class=\"input-text\" name=\"reserva\" id=\"reserva\" value=\"".$_reserva."\" size=\"45\">";
          $this->salida.= "	</td>";

          if($CaracteristicasDatos['plan_anestesico'])
          { $_PlanAnes = $CaracteristicasDatos['plan_anestesico']; } else { $_PlanAnes = NULL; }
                    
          $this->salida.= "	<td width=\"50%\" align=\"left\"><label class=\"label\">PLAN ANESTESICO: </label>";
          $this->salida.= "		<input type=\"text\" name=\"plan_anes\" id=\"plan_anes\" class=\"input-text\" value=\"".$_PlanAnes."\" size=\"45\">";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";          
          
          if($CaracteristicasDatos['premedicacion'])
          { $_Premedicacion = $CaracteristicasDatos['premedicacion']; } else { $_Premedicacion = NULL; }
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td width=\"50%\" align=\"center\" colspan=\"2\"><label class=\"label\">PREMEDICACION: </label>";
          $this->salida.= "		<input type=\"text\" class=\"input-text\" name=\"premedicacion\" id=\"premedicacion\" value=\"".$_Premedicacion."\" size=\"50\">";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";          
          $this->salida.= "	</table><br>";
          //Asa
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";
          
          $this->salida.= "	<tr>";
          $this->salida.= "	<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"INSERTAR\" name=\"save_evaluacion\">";
          $this->salida.= "	</td>";
          $this->salida.= "	</tr>";

          $reporte = new GetReports();
          $mostrar=$reporte->GetJavaReport('hc','EvaluacionPreanestesica','Reporte_EvaluacionPreanestesica',array(),array('rpt_name'=>'Evaluacion_Preanestesica'.SessionGetVar("Ingreso"),'rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
          $nombre_funcion=$reporte->GetJavaFunction();
          $this->salida.=$mostrar;

          $this->salida.="<tr>";
          $this->salida.="<td align=\"center\">";
          $this->salida.="<a href=\"javascript:$nombre_funcion\"><B><label class=\"hcLinkClaro\">IMPRIMIR REPORTE</label></B></a>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
                         
          $this->salida.= "	</form>";
          $this->salida.= "	</table><br>";
          
		$this->salida.= ThemeCerrarTablaSubModulo();
		return $this->salida;
     }
     
}
?>