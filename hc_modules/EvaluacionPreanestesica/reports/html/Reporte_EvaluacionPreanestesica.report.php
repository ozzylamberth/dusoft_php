<?php

/**
 * $Id: Reporte_EvaluacionPreanestesica.report.php,v 1.4 2007/11/16 15:20:29 tizziano Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 */

 
 
IncludeClass("Preanestesia",null,"hc","EvaluacionPreanestesica"); 


class Reporte_EvaluacionPreanestesica_report
{
	var $datos;
	
	function Reporte_EvaluacionPreanestesica_report($datos=array())
	{
		$this->datos=$datos;
		return true;
	}

	
	//PARAMETROS PARA LA CONFIGURACION DEL REPORTE
	var $title       = '';
	var $author      = '';
	var $sizepage    = 'leter';
	var $Orientation = '';
	var $grayScale   = false;
	var $headers     = array();
	var $footers     = array();

	
	function GetMembrete()
	{
		$Membrete = array('file'=>false,'datos_membrete'=>array('titulo'=>'',
													 'subtitulo'=>'',
													 'logo'=>'logocliente.png',
													 'align'=>'left'));
		return $Membrete;
	}
	 
	function CrearReporte()
	{
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

		$HTML_WEB_PAGE.="<HTML><BODY>";
		$HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"center\" CLASS=\"normal_10N\">";
          $HTML_WEB_PAGE.="EVALUACION PREANESTESICA<BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="</TR>";
		$HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD>";
          $HTML_WEB_PAGE.="&nbsp;";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
		
          // Cargar variables para impresion de cabecera
          $this->CargarVariables();
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD ALIGN=\"center\" CLASS=\"normal_10\" WIDTH=\"100%\">";
          $HTML_WEB_PAGE.= $this->CabeceraImprimir();
          $HTML_WEB_PAGE.="<BR><BR>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<LABEL CLASS=\"normal_10N\">Especialidad: </LABEL><LABEL CLASS=\"normal_10\">".$CaracteristicasDatos['especialidad']."</LABEL>";
          $HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
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
          $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
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
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
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

          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
          $HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
          
          if(is_array($cardioDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">CARDIOVASCULAR:  PRESENCIA DE </LABEL>";
          
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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$cardioDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
     
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($RespDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">RESPIRATORIO: PRESENCIA DE</LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$RespDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
     
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
               

          if(is_array($MetabDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">METABOLICO: PRESENCIA DE </LABEL>";
  
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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$MetabDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
                         
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($GastroDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">GASTROINTESTINAL: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$GastroDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
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
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">RENAL: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$RenalDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                         
          if(is_array($NeuroDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">NEUROLOGICO: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$NeuroDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($EsqueDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">MUSCULO ESQUELETICO: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$EsqueDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($HemaDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">HEMATOLOGICO: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$HemaDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
          if(is_array($HepaDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">HEPATICO: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$HepaDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($GinecoDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">GINECO-OBSTETRICO: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$GinecoDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($IntuDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">PREDICCION INTUBACION: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$IntuDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if(is_array($OtrosDatos))
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE ALIGN=\"LEFT\" BORDER=\"0\" WIDTH=\"70%\">";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\">OTROS FACTORES: PRESENCIA DE </LABEL>";

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

               $HTML_WEB_PAGE.="</TR>";
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD ALIGN=\"LEFT\"><LABEL CLASS=\"normal_10N\"></LABEL><LABEL CLASS=\"normal_10\">".$OtrosDatos['Maestro']['descripcion']."</LABEL></TD>";
               $HTML_WEB_PAGE.="</TABLE><BR>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
     
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
		if($CaracteristicasDatos['anestesia_previa'])
          {
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TABLE WIDTH=\"100%\">";
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>ANESTESIAS PREVIAS (TECNICA - FECHA - COMPLICACIONES):</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['anestesia_previa']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if($CaracteristicasDatos['alergias'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>ALERGIAS:</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['alergias']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
          }
          
          if($CaracteristicasDatos['drogas'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>DROGAS:</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['drogas']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
     
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
          if($CaracteristicasDatos['ayudas_dx'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>AYUDAS DIAGNOSTICAS:</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['ayudas_dx']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
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
          
          $HTML_WEB_PAGE.="<TR>";
		$HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
          $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
          $HTML_WEB_PAGE.="</TD>";
		$HTML_WEB_PAGE.="</TR>";
          
          if($CaracteristicasDatos['reserva'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>RESERVA:</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['reserva']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
               
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
          
          if($CaracteristicasDatos['plan_anestesico'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>PLAN ANESTESICO:</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['plan_anestesico']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
     
               $HTML_WEB_PAGE.="<TR>";
               $HTML_WEB_PAGE.="<TD WIDTH=\"100%\" COLSPAN=\"2\">";
               $HTML_WEB_PAGE.="<HR width=\"100%\" BORDER=\"1\">";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
                    
          if($CaracteristicasDatos['premedicacion'])
          {
               $HTML_WEB_PAGE.="<TR ALIGN=\"left\">";
               $HTML_WEB_PAGE.="<TD COLSPAN=\"2\"><LABEL class='normal_10N'>PREMEDICACION:</LABEL>";
               $HTML_WEB_PAGE.="&nbsp;&nbsp;&nbsp;".$CaracteristicasDatos['premedicacion']."</TD>";
               $HTML_WEB_PAGE.="</TR>";          
               $HTML_WEB_PAGE.="</TABLE>";
               $HTML_WEB_PAGE.="</TD>";
               $HTML_WEB_PAGE.="</TR>";
		}
               
          $this->GetDatosProfesional();
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<BR><BR><TABLE ALIGN=\"center\" WIDTH=\"100%\">";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10N'>Nombres y Apellidos del Médico:&nbsp;&nbsp;".$this->datosProfesional['nombre']."</td>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"right\" CLASS='normal_10N'>Firma y Sello&nbsp;&nbsp;<LABEL>____________________________________</LABEL></td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="<TR>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"left\" CLASS='normal_10N'>Registro Médico No.:&nbsp;&nbsp;".$this->datosProfesional['tarjeta_profesional']."</td>";
          $HTML_WEB_PAGE.="<TD ALIGN=\"right\" CLASS='normal_10'>&nbsp;</td>";
          $HTML_WEB_PAGE.="</TR>";
          $HTML_WEB_PAGE.="</TABLE>";
          $HTML_WEB_PAGE.="</TD>";
          $HTML_WEB_PAGE.="</TR>";
          
          $HTML_WEB_PAGE.="</TABLE>";
		$HTML_WEB_PAGE.="</BODY></HTML>";
		
		return $HTML_WEB_PAGE;
	}

     
     function CabeceraImprimir()
     {
          // Var Datos paciente.
          $this->datosPaciente = SessionGetVar("DatosPaciente");
          
          $edad=CalcularEdad($this->datosPaciente['fecha_nacimiento'],$this->EvolucionGeneral['fecha']);
          
          $this->imprimir.="<table width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\">";
          $this->imprimir.="<TR>\n";
          $this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\" COLSPAN=\"2\"><label class=\"normal_10N\">PACIENTE:</label>&nbsp;<label class=\"normal_10\">".$this->datosPaciente['primer_nombre'].' '.$this->datosPaciente['segundo_nombre'].' '.$this->datosPaciente['primer_apellido'].' '.$this->datosPaciente['segundo_apellido']."</label></TD>\n";
          $this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><label class=\"normal_10N\">IDENTIFICACION:</label>&nbsp;<label class=\"normal_10\">".$this->datosPaciente['tipo_id_paciente']." ".$this->datosPaciente['paciente_id']."</label></TD>\n";
          $this->imprimir.="<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\" COLSPAN=\"2\"><label class=\"normal_10N\">HC:</label>&nbsp;";
          if($this->datosPaciente['historia_numero']!="")
          {
               if($this->datosPaciente['historia_prefijo']!="")
               {
                    $this->imprimir .= "<label class=\"normal_10\">".$this->datosPaciente['historia_numero']." - ". $this->datosPaciente['historia_prefijo']."</label>";
               }
               else
               {
                    $this->imprimir .= "<label class=\"normal_10\">".$this->datosPaciente['historia_numero']." - ".$this->datosPaciente['tipo_id_paciente']."</label>";
               }
          }
          else
          {
               $this->imprimir.= "<label class=\"normal_10\">".$this->datosPaciente['paciente_id']." - ".$this->datosPaciente['tipo_id_paciente']."</label>";
          }
          $this->imprimir.="</TD>\n";
          $this->imprimir.="</TR>\n";
     
     
          $this->imprimir .= "<TR>";
          $this->imprimir .= "<TD COLSPAN=\"5\">";
          $this->imprimir .= "<table border=\"0\" width=\"100%\">";
          
          $FechaNacimiento = $this->FechaStamp($this->datosPaciente['fecha_nacimiento']);          
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><label class=\"normal_10N\">FECHA DE NACIMIENTO:</label>&nbsp;<label class=\"normal_10\">".$FechaNacimiento."</label></TD>";
          
          $this->imprimir.="<TD ALIGN=\"CENTER\" WIDTH=\"20%\"><label class=\"normal_10N\">EDAD:</label>&nbsp;";
          $this->imprimir.="<label class=\"normal_10\">".$edad['anos']."&nbsp;Años</label>";
          $this->imprimir.="</TD>\n";
          
          $this->imprimir.="<TD ALIGN=\"CENTER\" WIDTH=\"20%\"><label class=\"normal_10N\">SEXO:</label>&nbsp;";
          $this->imprimir.="<label class=\"normal_10\">".$this->datosPaciente['sexo_id']."</label>";
          $this->imprimir.="</TD>\n";
          
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"20%\"><label class=\"normal_10N\">TIPO AFILIADO:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$this->Responsable[9]."</label></td>\n";
          
          $this->imprimir .= "</table>";
          $this->imprimir .= "</TD>";
          $this->imprimir .= "</TR>";          
          
          
          $this->imprimir .= "<TR>";
          $res = $this->datosPaciente['residencia_direccion'];
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"40%\" COLSPAN=\"2\"><label class=\"normal_10N\">RESIDENCIA:</label>&nbsp;<label class=\"normal_10\">".$res."</label></TD>";
     
          if($this->datosPaciente['pais']=="COLOMBIA")
          {
               $direccion.= $this->datosPaciente['departamento'].'-'.$this->datosPaciente['municipio'];
          }
          else
          {
               $direccion.= $this->datosPaciente['pais']." / ".$this->datosPaciente['departamento']." / ".$this->datosPaciente['municipio'];
          }
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><label class=\"normal_10\">".$direccion."</label></TD>";
     
          $tel = $this->datosPaciente['residencia_telefono'];
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"25%\" COLSPAN=\"2\"><label class=\"normal_10N\">TELEFONO: </label><label class=\"normal_10\">".$tel."</label></TD>";
          $this->imprimir .= "</TR>";
     
          
          $this->imprimir .= "<TR>";
          $this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><label class=\"normal_10N\">NOMBRE ACOMPAÑANTE: </label></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><label class=\"normal_10N\">PARENTESCO: </label></TD>";
          $this->imprimir .= "<TD ALIGN=\"JUSTIFY\" COLSPAN=\"2\" WIDTH=\"25%\"><label class=\"normal_10N\">TELEFONO: </label></TD>";
          $this->imprimir .= "</TR>";
          
     
          $this->imprimir.= "<TR>";
          $this->BuscarCamaActiva($this->ingreso);
     
          $FechaI = $this->FechaStamp($this->DatosIngreso_Paciente['fecha_registro']);
          $HoraI = $this->HoraStamp($this->DatosIngreso_Paciente['fecha_registro']);
          
          $FechaS = date('d/m/Y');
          $HoraS  = date('h:m');
          $this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><label class=\"normal_10N\">FECHA INGRESO:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$FechaI." - ".$HoraI."</label></TD>";
          $this->imprimir.= "<TD ALIGN=\"JUSTIFY\" WIDTH=\"35%\"><label class=\"normal_10N\">FECHA IMPRESION:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$FechaS." - ".$HoraS."</label></TD>";
          $this->imprimir.= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"25%\"><label class=\"normal_10N\">CAMA:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$this->DatosCama['cama']."</label></TD>";
          $this->imprimir.= "</TR>";
          
          
          $servicio=$this->GetServicio($this->DatosIngreso_Paciente['departamento_actual']);
          $this->imprimir.= "<TR>";
          $this->imprimir .= "<td COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><label class=\"normal_10N\">DEPARTAMENTO:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$this->DatosIngreso_Paciente['departamento_actual']."  -  ".$this->DatosIngreso_Paciente['descripcion']."</label></TD>\n";
          $this->imprimir .= "<td COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"60%\"><label class=\"normal_10N\">SERVICIO:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$servicio."</label></TD>\n";
          $this->imprimir .= "</tr>\n";
     
          
          $this->imprimir.= "<TR>";
          $this->imprimir .= "<TD COLSPAN=\"2\" ALIGN=\"JUSTIFY\" WIDTH=\"40%\"><label class=\"normal_10N\">CLIENTE:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$this->Responsable[8]."</label></TD>\n";
          $this->imprimir .= "<TD COLSPAN=\"3\" ALIGN=\"JUSTIFY\" WIDTH=\"60%\"><label class=\"normal_10N\">PLAN:</label>&nbsp;&nbsp;<label class=\"normal_10\">".$this->Responsable[4]."</label></TD>\n";
          $this->imprimir .= "</TR>\n";
          $this->imprimir.= "</table>";
          $this->imprimir.= "<BR><BR>";
          return $this->imprimir;
     }

     function GetDatosProfesional()
	{
          list($dbconn) = GetDBconn();
          $sql="SELECT A.tipo_id_tercero, A.tercero_id, A.nombre,
               	   A.tarjeta_profesional, B.especialidad, C.descripcion
                FROM profesionales AS A,
               	 profesionales_usuarios AS E
                LEFT JOIN profesionales_especialidades AS B
                ON(E.tipo_tercero_id=B.tipo_id_tercero AND E.tercero_id=B.tercero_id)
                LEFT JOIN especialidades AS C ON(C.especialidad = B.especialidad)
                WHERE A.usuario_id =".SessionGetVar("Usuario")."
                AND A.usuario_id = E.usuario_id
                AND E.tercero_id = A.tercero_id
                AND E.tipo_tercero_id = A.tipo_id_tercero;";
		$result = $dbconn->Execute($sql);
		if($dbconn->ErrorNo() != 0)
          {
               return false;
          }
		while(!$result->EOF)
		{
			$this->datosProfesional = $result->GetRowAssoc(false);
			$result->MoveNext();
		}
		return $this->datosProfesional;
	}
     
     function GetDatosResponsable()
	{
		list($dbconn) = GetDBconn();
         
          $sql="SELECT a.plan_id, a.tipo_afiliado_id, a.rango, a.semanas_cotizadas, b.plan_descripcion, b.tipo_tercero_id, b.tercero_id, b.num_contrato, c.nombre_tercero, X.tipo_afiliado_nombre
               FROM cuentas as a LEFT JOIN tipos_afiliado AS X ON (a.tipo_afiliado_id = X.tipo_afiliado_id), planes as b, terceros as c
               WHERE
               a.plan_id = b.plan_id
               AND b.tercero_id = c.tercero_id
               AND b.tercero_id = c.tercero_id
               AND a.numerodecuenta = ".$this->EvolucionGeneral['numerodecuenta'].";";

		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$resultado = $dbconn->Execute($sql);
		$ADODB_FETCH_MODE = ADODB_FETCH_NUM;

		if ($dbconn->ErrorNo() != 0) {
			$this->error = "Error en la consulta";
			$this->mensajeDeError = $sql.$dbconn->ErrorMsg();
			$this->fileError = __FILE__;
			$this->lineError = __LINE__;
			return false;
		}
		if(!$resultado->EOF)
		{
			$this->Responsable = $resultado->FetchRow();
		}
		return $this->Responsable;
	}
     
	function CargarVariables()
	{
     	$this->realimprimir=1;
          list($dbconn) = GetDBconn();
          
          //Var ingreso.
          $this->ingreso = SessionGetVar("Ingreso");
		
          $query = "SELECT MAX(A.evolucion_id) 
                    FROM hc_evoluciones AS A,
                         profesionales AS B 
                    WHERE A.ingreso='".$this->ingreso."'
                    AND A.usuario_id = B.usuario_id
                    AND B.tipo_profesional IN ('1','2');";
		$result = $dbconn->Execute($query);
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
 		list($evolucion) = $result->FetchRow();
          $this->evolucion = $evolucion;
          
          if(!$evolucion)
          {
               $query = "SELECT MAX(evolucion_id)
                         FROM hc_evoluciones
                         WHERE ingreso='".$this->ingreso."';";
               $result = $dbconn->Execute($query);
               if($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               list($evolucion) = $result->FetchRow();
               $this->evolucion = $evolucion;
          }

		if(!IncludeLib('datospaciente'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de pacientes.";
      		return false;
          }
          
		if(!IncludeLib('historia_clinica'))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "No se pudo cargar la libreria de datos de Historia Clinica";
			return false;
          }

		if(!IncludeFile('classes/modules/hc_classmodules.class.php',true))
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "El archivo 'includes/historia_clinica.inc.php' no existe.";
			return false;
          }

     	$this->EvolucionGeneral = GetDatosEvolucion($this->evolucion);
         
		$this->Datos_Ingreso();
 
          $this->GetDatosResponsable();
          
          return true;
     }
 
     
     function Datos_Ingreso()
     {
          list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
          
          $query = "SELECT A.fecha_registro, A.departamento, A.departamento_actual,
                              A.fecha_cierre, B.fecha, B.fecha_cierre AS cierre_evolucion,
                              C.descripcion 
                    FROM ingresos AS A
                    LEFT JOIN hc_evoluciones AS B ON (A.ingreso = B.ingreso)
                    LEFT JOIN departamentos AS C ON (A.departamento_actual = C.departamento)
                    WHERE A.ingreso='".$this->ingreso."'
                    AND B.evolucion_id = (SELECT MAX(evolucion_id) 
                                             FROM hc_evoluciones 
                                             WHERE ingreso = '".$this->ingreso."' 
                                             AND fecha_cierre IS NOT NULL);";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
          $result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
          if($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          else
          {
               $this->DatosIngreso_Paciente = $result->FetchRow();
               return $this->DatosIngreso_Paciente;
          }
     }
     
	function BuscarCamaActiva($ingreso)
     {
		list($dbconn) = GetDBconn();
          GLOBAL $ADODB_FETCH_MODE;
		$query = "SELECT fecha_egreso, cama
          		FROM movimientos_habitacion
				WHERE ingreso='".$ingreso."';";
          $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$result = $dbconn->Execute($query);
          $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
		if($dbconn->ErrorNo() != 0)
		{
			$this->error = "Error al Cargar el Modulo";
			$this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
			return false;
		}
		else
		{
			$this->DatosCama = $result->FetchRow();
			return true;
		}
     }

	function GetServicio($departamento)
	{
		list($dbconn) = GetDBconn();
		$sql="select b.descripcion from departamentos as a, servicios as b where a.servicio=b.servicio and a.departamento='".$departamento."';";
		$result = $dbconn->Execute($sql);
		return $result->fields[0];
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
     
	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}
	
}
?>