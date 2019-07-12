<?php
/**
* Submodulo de Apoyos Diagnosticos.
*
* Submodulo para la toma de apoyos diagnosticos.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com>
* @version 1.0
* @package SIIS
* $Id: hc_Apoyos_Diagnosticos_Control_HTML.php,v 1.1 2009/07/30 12:38:06 johanna Exp $
*/
IncludeFile("classes/ApoyosDiagnosticos/ApoyosDiagnosticos_HTML.class.php");

class Apoyos_Diagnosticos_Control_HTML extends Apoyos_Diagnosticos_Control
{
	  //ad*
		function Apoyos_Diagnosticos_Control_HTML()
		{
				$this->Apoyos_Diagnosticos_Control();//constructor del padre
				return true;
		}
		
    //ad*
		
    /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion()
  {
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'',
    'autor'=>'CLAUDIA LILIANA ZUÑIGA CAÑON',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }

    
//////////////////////////////    
    
    function SetStyle($campo)
		{
				if ($this->frmError[$campo] || $campo=="MensajeError")
				{
						if ($campo=="MensajeError"){
						return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
						}
				return ("label");
		}
		//ad - jea*
		function CalcularNumeroPasos($conteo)
		{
				$numpaso=ceil($conteo/$this->limit);
				return $numpaso;
		}
		//ad - jea*
		function CalcularBarra($paso)
		{
				$barra=floor($paso/10)*10;
				if(($paso%10)==0)
				{
						$barra=$barra-10;
				}
				return $barra;
		}
		//ad - jea*
		function CalcularOffset($paso)
		{
				$offset=($paso*$this->limit)-$this->limit;
				return $offset;
		}
		//ad - jea*
		function RetornarBarraExamenes()//Barra paginadora
		{
				$pfj=$this->frmPrefijo;
				if($this->limit>=$this->conteo)
				{
						return '';
				}
				$paso=$_REQUEST['paso1'.$pfj];
				if(empty($paso))
				{
						$paso=1;
				}
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Buscar',
				'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
				'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],'criterio2'.$pfj=>$_REQUEST['criterio2'.$pfj],'busqueda'.$pfj=>$_REQUEST['busqueda'.$pfj]));
				$barra=$this->CalcularBarra($paso);
				$numpasos=$this->CalcularNumeroPasos($this->conteo);
				$colspan=1;
				$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
				if($paso > 1)
				{
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
						$colspan+=2;
				}
				$barra++;
				if(($barra+10)<=$numpasos)
				{
						for($i=($barra);$i<($barra+10);$i++)
						{
								if($paso==$i)
								{
										$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
								}
								else
								{
										$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
								}
								$colspan++;
						}
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
						$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
						$colspan+=2;
				}
				else
				{
						$diferencia=$numpasos-9;
						if($diferencia<=0)
						{
								$diferencia=1;
						}
						for($i=($diferencia);$i<=$numpasos;$i++)
						{
								if($paso==$i)
								{
										$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
								}
								else
								{
										$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
								}
								$colspan++;
						}
						if($paso!=$numpasos)
						{
								$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
								$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
								$colspan++;
						}
				}
				if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
				{
						if($numpasos>10)
						{
								$valor=10+3;
						}
						else
						{
								$valor=$numpasos+3;
						}
						$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
				}
				else
				{
						if($numpasos>10)
						{
								$valor=10+5;
						}
						else
						{
								$valor=$numpasos+5;
						}
						$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
				}
				return $salida;
		}


//ad - forma que pinta el estado de los examenes solicitados al paciente
//permite consultar y dar lectura a los examenes realizados y capturar nuevos
//examenes no soliictados *
function frmForma_Apoyod_leyenda()
{
     	unset($_SESSION['APOYO']);
		unset($_SESSION['PLANTILLA_SELECCIONADA']); //se setea la variable de sesion que se uso para guaradr la plantilla seleccionada en un examen
		unset($_SESSION['PLANTILLA_SELECCIONADA_MANUALES']); //se setea la variable de sesion que se uso para guaradr la plantilla seleccionada en un examen
		$this->SetJavaScripts('DatosAutorizacion');
		$pfj=$this->frmPrefijo;
		if(empty($this->titulo))
		{
				$this->salida= ThemeAbrirTablaSubModulo('LECTURA DE APOYOS DIAGNOSTICOS');
		}
		else
		{
				$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}

		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$medicos = $this->Consulta_Apoyod_delMedico();
		
		if ($medicos)
		{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"5\">EXAMENES SOLICITADOS AL PACIENTE POR:</td>";
				$this->salida.="</tr>";
				//variables que controlan el diseño del formulario, cada paso corresponde a uno de los ciclos.
				$paso = 1;	$paso1 = 1;	$paso2 = 1;
				for($i=0;$i<sizeof($medicos);$i++)
				{
						// el contenido de $medicos[$i][usuario_id] se obtiene de la tabla hc_evoluciones
						if ($medicos[$i][usuario_id] == UserGetUID())
						{
								$prefijo = 'caso1';
								if ($paso==1)
								{
										$texto_titulo ='';
										$nombre  = $this->ConsultaNombreMedico($medicos[$i][usuario_id]);
										$texto_titulo.="<tr class=\"modulo_table_title\">";
										$texto_titulo.="  <td align=\"left\" colspan=\"5\">".$nombre[descripcion]." - ".$nombre[nombre_tercero]."</td>";
										$texto_titulo.="</tr>";
										$texto_titulo.="<tr class=\"hc_table_submodulo_list_title\">";
										$texto_titulo.="<td width=\"5%\">EVOLUCION</td>";
										$texto_titulo.="<td width=\"5%\">FECHA EVOLUCION</td>";
										$texto_titulo.=" <td width=\"55%\" >EXAMEN</td>";
										$texto_titulo.=" <td width=\"10%\" >ESTADO</td>";
										$texto_titulo.="<td width=\"5%\">OPCION</td>";
										$texto_titulo.="</tr>";
										$cabecera_medico.=$texto_titulo;
										$paso++;
										$prefijo = 'caso1';
										$_SESSION['cerrar'.$prefijo] = 1;  //variable nueva para agrupar por evolucion
										$_SESSION['lectura_activa'.$prefijo] = 0;
								}
								$vector = $medicos[$i];
								$vector1 = $medicos[$i+1];
								$cabecera_medico.=$this->Pintar_Apoyo($vector, $vector1, $prefijo, $nombre);
						}
						// se esta sacando $medicos[$i][usuario_id] de la tabla hc_evoluciones
						if (($medicos[$i][usuario_id] != UserGetUID()) AND ($medicos[$i][departamento] == $this->departamento))
						{
								$prefijo = 'caso2';
								if ($paso1 ==1)
								{
										$texto_titulo='';
										$texto_titulo.="<tr class=\"modulo_table_title\">";
										$texto_titulo.="  <td align=\"left\" colspan=\"5\">OTROS PROFESIONAL DEL MISMO DEPARTAMENTO</td>";
										$texto_titulo.="</tr>";
										$texto_titulo.="<tr class=\"hc_table_submodulo_list_title\">";
										$texto_titulo.="<td width=\"5%\">EVOLUCION</td>";
										$texto_titulo.="<td width=\"5%\">FECHA EVOLUCION</td>";
										$texto_titulo.=" <td width=\"55%\" >EXAMEN</td>";
										$texto_titulo.=" <td width=\"10%\" >ESTADO</td>";
										$texto_titulo.="<td width=\"5%\">OPCION</td>";
										$texto_titulo.="</tr>";
										$cabecera_mismo_dpto.=$texto_titulo;
										$paso1++;
										$prefijo = 'caso2';
										$_SESSION['cerrar'.$prefijo] = 1;
										$_SESSION['lectura_activa'.$prefijo] = 0;
								}
								$vector = $medicos[$i];
								$vector1 = $medicos[$i+1];
								$cabecera_mismo_dpto.=$this->Pintar_Apoyo($vector, $vector1, $prefijo, $nombre);
						}
						// se esta sacando $medicos[$i][usuario_id] de la tabla hc_evoluciones
						if (($medicos[$i][usuario_id] != UserGetUID()) AND ($medicos[$i][departamento] != $this->departamento))
						{
								$prefijo = 'caso3';
								if ($paso2 == 1)
								{
										$texto_titulo='';
										$texto_titulo.="<tr class=\"modulo_table_title\">";
										$texto_titulo.="  <td align=\"left\" colspan=\"5\">OTROS PROFESIONALES DE OTROS DEPARTAMENTOS</td>";
										$texto_titulo.="</tr>";
										$texto_titulo.="<tr class=\"hc_table_submodulo_list_title\">";
										$texto_titulo.="<td width=\"5%\">EVOLUCION</td>";
										$texto_titulo.="<td width=\"5%\">FECHA EVOLUCION</td>";
										$texto_titulo.=" <td width=\"55%\">EXAMEN</td>";
										$texto_titulo.=" <td width=\"10%\" >ESTADO</td>";
										$texto_titulo.="<td width=\"5%\">OPCION</td>";
										$texto_titulo.="</tr>";
										$cabecera_otro_dpto.=$texto_titulo;
										$paso2++;
										$prefijo = 'caso3';
										$_SESSION['cerrar'.$prefijo] = 1;
										$_SESSION['lectura_activa'.$prefijo] = 0;
								}
								$vector = $medicos[$i];
								$vector1 = $medicos[$i+1];
								$cabecera_otro_dpto.=$this->Pintar_Apoyo($vector, $vector1, $prefijo, $nombre);
						}
				}
				$this->salida.=$cabecera_medico;
				$this->salida.=$cabecera_mismo_dpto;
				$this->salida.=$cabecera_otro_dpto;
				$this->salida.="</table>";
		}

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'for'));
		$this->salida.="<td class=\"Normal_10N\" align=\"left\" colspan=\"6\"><a href='$accion'>".'INGRESAR OTROS EXAMENES'."</a></td>";
		$this->salida.="</tr>";


		$NoSolicitados = $this->ConsultaResultadosNoSolicitados();
		if($NoSolicitados)
		{
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td width=\"5%\">EVOLUCION</td>";
				$this->salida.="<td width=\"5%\">FECHA EVOLUCION</td>";
				$this->salida.=" <td width=\"40%\">EXAMEN</td>";
				$this->salida.=" <td width=\"10%\">ESTADO</td>";
				$this->salida.="<td width=\"10%\">REVISION</td>";
				$this->salida.="<td width=\"10%\">FECHA DE REALIZACION</td>";
				$this->salida.="</tr>";

				for($i=0;$i<sizeof($NoSolicitados);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"center\" width=\"5%\">".$NoSolicitados[$i][evolucion_id]."</td>";
						$this->salida.="  <td align=\"center\" width=\"5%\">".$this->FechaStampMostrar($NoSolicitados[$i][fecha])."</td>";
						$this->salida.="  <td align=\"left\" width=\"40%\">".$NoSolicitados[$i][titulo_examen]."</td>";
						$this->salida.="  <td align=\"center\" width=\"10%\">Resultado</td>";
						if ($NoSolicitados[$i][sw_prof] == '1')
						{
								$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'consulta_resultados','resultado_id'.$pfj => $NoSolicitados[$i][resultado_id], 'sw_modo_resultado'.$pfj=>$NoSolicitados[$i][sw_modo_resultado]));
								$this->salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Leido</a></td>";
						}
						$this->salida.="  <td align=\"center\" width=\"10%\">".$NoSolicitados[$i][fecha_realizado]."</td>";
						$this->salida.="</tr>";
				}

		}
		$this->salida.="</table>";

		//EXAMEN SOLICITADOS SIN HISTORIA CLINICA
		$Manuales = $this->ConsultaSolicitudesManuales();
		if($Manuales)
		{
               $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
               $this->salida.="<tr class=\"modulo_table_title\">";
               $this->salida.="  <td align=\"center\" colspan=\"7\">EXAMENES SOLICITADOS FUERA DE LA HISTORIA CLINICA</td>";
               $this->salida.="</tr>";
               $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
               $this->salida.="<td width=\"10%\" colspan=\"2\">FECHA SOLICITUD</td>";
               $this->salida.=" <td width=\"40%\">EXAMEN</td>";
               $this->salida.=" <td width=\"10%\">ESTADO</td>";
               $this->salida.="<td width=\"10%\">REVISION</td>";
               $this->salida.="<td colspan=\"2\" width=\"10%\">FECHA DE REALIZACION</td>";
               $this->salida.="</tr>";
               for($i=0;$i<sizeof($Manuales);$i++)
               {
                    $var = 0;
                    if( $i % 2){ $estilo='modulo_list_claro';}
                    else {$estilo='modulo_list_oscuro';}
                    $fecha_evolucion = $this->FechaStampMostrar($Manuales[$i][fecha]);
                    $this->salida.="<tr class=\"$estilo\">";
                    $this->salida.="  <td align=\"center\" width=\"10%\" colspan=\"2\" >$fecha_evolucion</td>";
                    if($Manuales[$i][especialidad]!=NULL)
                    {
                         $this->salida.="  <td align=\"left\" width=\"40%\">".$Manuales[$i][especialidad_nombre]."</td>";
                    }
                    else
                    {
                         $this->salida.="  <td align=\"left\" width=\"40%\">".$Manuales[$i][titulo_examenes]."</td>";
                    }
                    if($Manuales[$i][realizacion] == '4')
                    {
                         if ($Manuales[$i][resultado_manual] != 0)
                         {
                              $this->salida.="  <td align=\"center\" width=\"10%\">Resultado Manual</td>";
                              $var = 1;
                         }
                         else
                         {
                              if ($Manuales[$i][resultados_sistema] != 0)
                              {
                                   $this->salida.="  <td align=\"center\" width=\"10%\">Resultado Sistema</td>";
                                   if (!empty($Manuales[$i][usuario_id_profesional]))
                                   {
                                        $var = 1;
                                   }
                                   else
                                   {
                                        $this->salida.="  <td align=\"center\" width=\"10%\">En Proceso</td>";
                                   }
                              }
                         else
                         {
                              $this->salida.="  <td align=\"center\" width=\"10%\">Resultado</td>";
                              $this->salida.="  <td align=\"center\" width=\"10%\"></td>";
                         }
                    }
                    if ($var == 1)
                    {
                         $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'consulta_resultados','resultado_id'.$pfj => $Manuales[$i][resultado_id], 'sw_modo_resultado'.$pfj=>$Manuales[$i][sw_modo_resultado]));
                         $this->salida.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Consultar Resultado</a></td>";
                    }
               }
	          else
               {
                    $this->salida.="  <td align=\"center\" width=\"10%\"></td>";
                    $this->salida.="  <td align=\"center\" width=\"10%\"></td>";
               }
               $this->salida.="  <td colspan=\"2\" align=\"center\" width=\"10%\">".$Manuales[$i][fecha_realizado]."</td>";
               $this->salida.="</tr>";
          }
          $this->salida.="</table>";
     }
     $this->salida .= "</form>";
     $this->salida .= ThemeCerrarTablaSubModulo();
     return true;
}


//ad - consulta la forma que contiene los resultados del examen*
function Consulta_Resultados($resultado_id, $sw_modo_resultado)
{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('CONSULTA DEL EXAMEN CLINICO');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		if (!class_exists('ApoyosDiagnosticos_HTML'))
		{
      return false;
		}
    $classApoyo = new ApoyosDiagnosticos_HTML;
		$this->salida.= $classApoyo->GetPlantillaApoyoDiagnostico($resultado_id, $sw_modo_resultado, $evolucion_id='');

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"$estilo\">";
		//BOTON DE VOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</form>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}


  /**
  * Funcion que permite la captura de los resultados de forma grupal
  * @return boolean
  */
function Capturar_Resultados($tipo_id_paciente, $paciente_id, $evolucion_id)
{
		$pfj=$this->frmPrefijo;        
    $this->salida= ThemeAbrirTablaSubModulo('CAPTURA DE RESULTADOS EN GRUPO');
    $sexo_paciente = $this->GetSexo($tipo_id_paciente, $paciente_id);
    $edad_paciente = $this->Obtener_Edad($tipo_id_paciente, $paciente_id);
    
    $this->salida.="</script>";
    $this->salida.="<script language=\"JavaScript\">;";
    $this->salida.="function ConsultaComponentes(frm,indice)";
    $this->salida.="{";
    $this->salida.="  frm.opcion$pfj.value='cambio_tecnica';";
    $this->salida.="  frm.posicion$pfj.value=indice;";
    $this->salida.="  frm.submit();";
    $this->salida.='}'."\n";
    $this->salida.="</script>";
    
    $this->salida.="<script language=\"JavaScript\">;";
    $this->salida.="function creacion_indice(frm,valor)";
    $this->salida.="{";
    $this->salida.="  frm.opcion$pfj.value='capturar_observacion';";
    $this->salida.="  frm.posicion$pfj.value=valor;";
    $this->salida.="  frm.submit();";
    $this->salida.='}'."\n";
    $this->salida.="</script>";
    
    $action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertarmanuales','evolucion_id'.$pfj => $evolucion_id));
		$this->salida .= "<form name=\"formacaptura$pfj\" action=\"$action\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
    
    //OBTENIENDO TODOS LOS APOYOS QUE LE FUERON ENVIADOS AL PACIENTE 
    $datos = $this->ConsultaOrdenesPaciente($paciente_id, $tipo_id_paciente, $evolucion_id, $sexo_paciente);
    /*if(!$datos)
    {
      //habria que hacer la prueba para ver si al enviar un examen que no exista que pasa.
      //si los otros salen o no
      //continuar aqui en agosto para ver como se mete el generico.
    }
    $datos = $this->ConsultaOrdenesPaciente($paciente_id, $tipo_id_paciente, $evolucion_id, $sexo_paciente);*/
		if($datos)
    {
        for($k=0;$k<sizeof($datos);$k++)
        { 
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="<td align=\"center\" width=\"5%\">CARGO</td>";
            $this->salida.="<td align=\"center\" width=\"35%\">EXAMEN</td>";
            $this->salida.="<td align=\"center\" width=\"12%\">TECNICA</td>";
            $this->salida.="<td align=\"center\" width=\"12%\" colspan=\"3\">OPCIONES</td>";
            $this->salida.="<td align=\"center\" width=\"26%\" class=\"".$this->SetStyle("fecha_realizado$k$pfj")."\">FECHA</td>";                        
            $this->salida.="</tr>";
            
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="<td align=\"center\" width=\"5%\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."</td>";
            $this->salida.="<td align=\"center\" width=\"35%\">".strtoupper($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo'])."</td>";

            $this->salida.="<td align=\"center\" width=\"12%\">";
            if (!empty($_REQUEST['posicion'.$pfj]) OR ($_REQUEST['posicion'.$pfj]== '0'))
            {
              if ($_REQUEST['posicion'.$pfj]==$k)
              {
                $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']=$_REQUEST['tecnica'.$k.$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']];
              }
            }
            $this->salida.="<select name=\"tecnica".$k."".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."\" class=\"select\" onChange=\"ConsultaComponentes(this.form,'$k')\">";
            for($j=0;$j<sizeof($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica']);$j++)
            {
                if($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']==$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id'])
                {
                    $this->salida.="<option value = ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id']." selected >".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['nombre_tecnica']."</option>";
                }
                else
                {
                    $this->salida.="<option value = ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['tecnica_id']." >".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica'][$j]['nombre_tecnica']."</option>";
                }
            }
            $this->salida.="</select>";
            $this->salida.="</td>";

            if($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['informacion']=='')
            {
                $this->salida.="<td align=\"center\" width=\"4%\"><img src=\"'".GetThemePath()."/images/Vacio.gif'\" title=\"sin Informacion \"  border=\"0\"></td>";
            }
            else
            {
                $this->salida.="<td align=\"center\" width=\"4%\"><img src=\"'".GetThemePath()."/images/EstacionEnfermeria/info.png'\" title=\"Informacion: ".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['informacion']."\"  border=\"0\"></td>";
            }

            $this->SetJavaScripts('DatosSolicitudApoyo');
            $this->salida.="<td align=\"center\" width=\"4%\"><a href=\"javascript:DatosSolicitudApoyo(".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['hc_os_solicitud_id'].", '".$tipo_id_paciente."', '".$paciente_id."', '".$nombre."', '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."', '".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo']."')\"><img src=\"".GetThemePath()."/images/resumen.gif\" border=\"0\" title=\"Ver Datos Solicitud\"> </a></td>";

            if (!empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']))
            {
                $this->salida.="<td align=\"center\" width=\"4%\"><a href=\"javascript:creacion_indice(document.formacaptura$pfj,'$k')\"> <img src=\"".GetThemePath()."/images/modificar.gif\" border=\"0\" title=\"Modificar Observacion\">  </a></td>";
            }
            else
            {
                $this->salida.="<td align=\"center\" width=\"4%\"><a href=\"javascript:creacion_indice(document.formacaptura$pfj,'$k')\"> <img src=\"".GetThemePath()."/images/modificar.gif\" border=\"0\" title=\"Agregar Observacion\">  </a></td>";
            }

            if ($_SESSION['CONSTRUCTOR_REQUEST']==1)
            {
                $_REQUEST['fecha_realizado'.$k.$pfj] = $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['fecha_realizado'];
            }
            else
            {
                if (empty($_REQUEST['fecha_realizado'.$k.$pfj]))
                {
                    $_REQUEST['fecha_realizado'.$k.$pfj] = date('d-m-Y');
                }
            }
            $this->salida.="<td align=\"center\" width=\"26%\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value = \"".$_REQUEST['fecha_realizado'.$k.$pfj]."\" name=\"fecha_realizado$k$pfj\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">".ReturnOpenCalendario('formacaptura'.$pfj,"fecha_realizado$k$pfj",'-')."</td>";
           
            $this->salida.="</tr>";
            $this->salida.="</table>";
         
		        //llama a la funcion que consulta los subexamens de cada apoyo solicitado al paciente
            unset($vector);
            $vector=$this->ConsultaComponentesExamen($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo'], $_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada'] ,$sexo_paciente, $edad_paciente[anos], $k, $tipo_id_paciente, $paciente_id, $_REQUEST['indice'],'1');
            if($vector)
            {
                $this->salida.="<input type=\"hidden\" name = \"vector$k$pfj\"  value=\"".sizeof($vector)."\">";
                $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";              
				        $indmin=1;
				        $e=0;
				        for($i=0;$i<sizeof($vector);$i++)
				        {
						        if( $i % 2)
                    {$estilo='modulo_list_claro';}
                    else
                    {$estilo='modulo_list_oscuro';}
        
                    if ($_SESSION['CONSTRUCTOR_REQUEST']==1)
                    {
                        $_REQUEST['resultado'.$k.$e.$pfj] = $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['resultado'];
                        $_REQUEST['sw_patologico'.$k.$e.$pfj] = $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['sw_patologico'];
                    }
                    
						        switch ($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_plantilla_id'])
						        {
                        case "1": {
									                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                      $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                      $this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
                                      $this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
                                      $this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
                                      $this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
                                      $this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
                                      $this->salida.="</tr>";
                                      if(is_null($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']) || $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min'] == '0')
                                      {
                                          $_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min'] = 0;
                                      }

                                      if ($_REQUEST['rmin'.$k.$e.$pfj])
                                      {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']= $_REQUEST['rmin'.$k.$e.$pfj];}

                                      if ($_REQUEST['rmax'.$k.$e.$pfj])
                                      {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']= $_REQUEST['rmax'.$k.$e.$pfj];}

                                      if ($_REQUEST['unidades'.$k.$e.$pfj])
                                      {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']= $_REQUEST['unidades'.$k.$e.$pfj];}

                                      $this->salida.="<tr class=\"$estilo\">";
                                      $this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e$pfj")."\">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                      $this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" name = \"resultado$k$e$pfj\" value =\"".$_REQUEST['resultado'.$k.$e.$pfj]."\">&nbsp;".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']."</td>";
                                      $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_min']."\"></td>";
                                      $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['rango_max']."\"></td>";
                                      $this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_1']."\"></td>";

                                      if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
                                      {
                                          $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                      }
                                      else
                                      {
                                          $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                      }
                                      $this->salida.="</tr>";

                                      $this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                      $e++;
                                      break;
                                   }             

                        case "2": {
                                      if ($indmin == 1)
                                      {
                                          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                          $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                          $this->salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
                                          $this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
                                          $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                          $this->salida.="</tr>";
                                          $this->salida.="<tr class=\"$estilo\">";
                                          $this->salida.="<td align=\"left\" width=\"40%\" class=".$this->SetStyle("resultado$k$e$pfj").">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                          $this->salida.="<td align=\"center\" width=\"45%\" colspan = \"2\">";
                                          $this->salida.="<select size = \"1\" name = \"resultado$k$e$pfj\"  class =\"select\">";
                                          $this->salida.="<option value = \"-1\" >--Seleccione--</option>";
                                          if($_REQUEST['resultado'.$k.$e.$pfj]==$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion'])
                                          {
                                              $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" selected>".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                          }
                                          else
                                          {
                                              $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" >".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                          }
                                          $indmin++;
                                      }
                                      else
                                      {
                                          if($_REQUEST['resultado'.$k.$e.$pfj]==$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion'])
                                          {
                                            $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" selected>".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                          }
                                          else
                                          {
                                            $this->salida.="<option value = \"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."\" >".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['opcion']."</option>";
                                          }
                                      }
                                      if($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']!=$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i+1]['lab_examen_id'])
                                      {
                                          $this->salida.="</select>";
                                          $this->salida.="</td>";

                                          if ($_REQUEST['unidades'.$k.$e.$pfj])
                                          {$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']= $_REQUEST['unidades'.$k.$e.$pfj];}

                                          $this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\"><input type=\"text\" class=\"input-text-center\" name=\"unidades$k$e$pfj\"  size=\"10\"   value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['unidades_2']."\"></td>";

                                          if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
                                          {
                                              $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                          }
                                          else
                                          {
                                              $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                          }
                                          $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                          $this->salida.="</tr>";
                                          $indmin=1;
                                          $e++;
                                      }
                                      break;
                                  }

                        case "3": {
                                      $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                      $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                      $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
                                      $this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
                                      $this->salida.="</tr>";

                                      $this->salida.="<tr class=\"$estilo\">";
                                      $this->salida.="  <td  align=\"center\" width=\"35%\" class=".$this->SetStyle("resultado$k$e$pfj").">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                      if($_REQUEST['resultado'.$k.$e.$pfj]==='' OR !empty($_REQUEST['resultado'.$k.$e.$pfj]))
                                      {
                                          $this->salida.="<td colspan = \"4\" align=\"center\" width=\"60%\"><textarea style = \"width:100%\" class=\"textarea\" name = \"resultado$k$e$pfj\" cols = \"150\" rows = \"30\">".$_REQUEST['resultado'.$k.$e.$pfj]."</textarea></td>";
                                      }
                                      else
                                      {
                                          $this->salida.="<td colspan = \"4\" align=\"center\" width=\"60%\"><textarea style = \"width:100%\" class=\"textarea\" name = \"resultado$k$e$pfj\" cols = \"150\" rows = \"30\">".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['detalle']."</textarea></td>";
                                      }

                                      if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
                                      {
                                          $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                      }
                                      else
                                      {
                                          $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                      }
                                      $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                      $this->salida.="</tr>";
                                      $e++;
                                      break;
                                  }

                        case "0": {
                                      $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                                      $this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
                                      $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
                                      $this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
                                      $this->salida.="</tr>";

                                      $this->salida.="<tr class=\"$estilo\">";
                                      $this->salida.="<td width=\"35%\" align=\"center\" class=\"".$this->SetStyle("resultado$k$e$pfj")."\">".strtoupper($_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['nombre_examen'])."</td>";
                                      $this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\"><textarea style = \"width:80%\" class=\"textarea\" name = \"resultado$k$e$pfj\" cols = \"60\" rows = \"10\">".$_REQUEST['resultado'.$k.$e.$pfj]."</textarea></td>";

                                      if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
                                      {
                                          $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                      }
                                      else
                                      {
                                          $this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
                                      }
                                      $this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$_SESSION['APOYO'][$k][$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['tecnica_seleccionada']]['subexamenes'][$i]['lab_examen_id']."\">";
                                      $this->salida.="</tr>";
                                      $e++;
                                      break;
                                  }       
                           }//cierra el switche       
				        }//cierra el for
                if (!empty($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']))
                {
                    $this->salida.="<tr>";
                    $this->salida.="<td class=\"hc_table_submodulo_list_title\" colspan = \"1\" align=\"center\" width=\"35%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
                    $this->salida.="<td class=\"$estilo\" colspan = \"5\" align=\"center\" width=\"65%\"><textarea readonly style = \"width:82%\" class=\"textarea\" name = \"observacion$pfj\" cols=\"60\" rows=\"3\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']."</textarea></td>" ;
                    $this->salida.="</tr>";
                }
                $this->salida.="</table>";
                $items = $e;
                $this->salida.="<input type=\"hidden\" name = \"items$k$pfj\"  value=\"$items\">";

                $this->salida.="<table align=\"center\" width=\"100%\" border=\"0\">";
                $this->salida.="<tr><td align=\"right\"><input class=\"input-submit\" name=\"insertar_resultado$k$pfj\" type=\"submit\" value=\"INSERTAR$k\"></td></tr>";
                $this->salida.="</table>";
              }//fin del if que verifica si el examen tiene componentes.
          }//fin del for de los apoyos
      } 
      
      unset ($_SESSION['CONSTRUCTOR_REQUEST']);
      $this->salida.="<input type=\"hidden\" name = \"posicion$pfj\" id= \"posicion\">";
      $this->salida.="<input type=\"hidden\" name = \"opcion$pfj\" id= \"opcion\">";
      
      //Evaluar todo lo que sigue    
      if ($_REQUEST['profesional'.$pfj] != '')
      {$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['profesional'] = $_REQUEST['profesional'.$pfj];}
     
      $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\">PROFESIONAL PRESTADOR DEL SERVICIO: </td>";      
      $this->salida.="</tr>";

      $this->salida.="<tr class=\"$estilo\">";
      $this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\"><input type=\"text\" name = \"profesional$pfj\" value =\"".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['profesional']."\"></td>";
      $this->salida.="</tr>";
      $this->salida.="</table>";           
          
      if ($_REQUEST['observacion_medico'.$pfj] != '')
      {$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['observacion_medico'] = $_REQUEST['observacion_medico'.$pfj];}
            
      $this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";      
      $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\">OBSERVACION DEL MEDICO</td>";      
      $this->salida.="</tr>";

      $this->salida.="<tr class=\"$estilo\">";
      $this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion_medico$pfj\" cols=\"60\" rows=\"5\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id]['observacion_medico']."</textarea></td>" ;      
      $this->salida.="</tr>";
      $this->salida.="</table><BR>";     
      //hasta aqui

      $this->salida.="<table align=\"center\" width=\"100%\" border=\"0\">";
      $this->salida.="<tr><td  align=\"center\"><input class=\"input-submit\" name=\"insertar_todos$pfj\" type=\"submit\" value=\"INSERTAR TODOS\"></td></tr>";
      $this->salida.="</table>";
      $this->salida.="</form>";

      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="<tr>";     
      //BOTON DE VOLVER      
      $accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
      $this->salida .= "<form name=\"forma$pfj\" action=\"$accion3\" method=\"post\">";
      $this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"Apoyod$pfj\" type=\"submit\" value=\"LISTA DE APOYO DIAG.\"></form></td>";
      $this->salida .="</tr>";
      $this->salida .="</table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
}


//ad*
//funcion que integra la busqueda de examenes existentes en apoyod_cargos
function frmForma($vector)
{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('INGRESO DE OTROS APOYOS DIAGNOSTICOS');
		$sexo      = $this->datosPaciente[sexo_id];
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Buscar',
		'Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],'criterio2'.$pfj=>$_REQUEST['criterio2'.$pfj],'busqueda'.$pfj=>$_REQUEST['busqueda'.$pfj]));

		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"left\" colspan=\"6\">BUSQUEDA</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

		$this->salida.="<td width=\"10%\">Buscar Por:</td>";

		$this->salida.="<td width=\"15%\" align = \"center\" >";
		$this->salida.="<select size = \"1\" name = 'criterio1$pfj' class =\"select\">";
		$this->salida.="<option value = -1>--Seleccione-- </option>";
		if (($_REQUEST['criterio1'.$pfj])  == 1)
				{$this->salida.="<option value = 1 selected> Titulo De Examen </option>";}
		else
				{$this->salida.="<option value = 1 > Titulo De Examen </option>";}

		if (($_REQUEST['criterio1'.$pfj])  == 2)
				{$this->salida.="<option value = 2 selected> Cargo </option>";}
		else
				{$this->salida.="<option value = 2 > Cargo </option>";}

		$this->salida.="</select>";
		$this->salida.="</td>";

		$this->salida .="<td width=\"30%\" align=\"center\"><input type=\"text\" class=\"input-text\"     name = 'busqueda$pfj' value =\"".$_REQUEST['busqueda'.$pfj]."\" ></td>" ;

		$this->salida.=" <td width=\"5%\">En</td>";
		$this->salida.="<td width=\"10%\" align = \"left\" >";
		$this->salida.="<select size = \"1\" name = 'criterio2$pfj' class =\"select\">";
		$this->salida.="<option value = 1 selected>Todos</option>";
		$categoria = $this->tipos();
		for($i=0;$i<sizeof($categoria);$i++)
		{
				$id = $categoria[$i][apoyod_tipo_id];
				$opcion = $categoria[$i][descripcion];

				if (($_REQUEST['criterio2'.$pfj])  != $id)
				{
						$this->salida.="<option value = $id>$opcion</option>";
				}
				else
				{
						$this->salida.="<option value = $id selected >$opcion</option>";
				}
		}
		$this->salida.="</select>";
		$this->salida.="</td>";
		$this->salida .= "<td  width=\"10%\" align=\"center\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"BUSCAR\"></td>";
		$this->salida.="</tr>";
		$this->salida .= "</form>";

		//BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = \"6\" align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";

		$this->salida.="</table><br>";

		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		if($vector)
		{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"2\">EXAMENES</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td>CARGO</td>";
				$this->salida.="  <td>EXAMEN</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($vector);$i++)
				{
						$cargo=$vector[$i][cargo];
						$examen=$vector[$i][titulo_examen];
						$informacion= $vector[$i][informacion];

						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr class=\"$estilo\">";
						$this->salida.="  <td align=\"center\" width=\"20%\">$cargo</td>";
						$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'forma', 'cargo'.$pfj => $cargo,'examen'.$pfj => $examen, 'informacion'.$pfj =>$informacion));
						$this->salida.="  <td align=\"left\" width=\"60%\"><a href='$accion'>".strtoupper($examen)."</a></td>";
						$this->salida.="</tr>";
				}
				$this->salida.="</table>";
				$var=$this->RetornarBarraExamenes();
				if(!empty($var))
				{
						$this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
						$this->salida .= "  <tr>";
						$this->salida .= "  <td width=\"100%\" align=\"center\">";
						$this->salida .=$var;
						$this->salida .= "  </td>";
						$this->salida .= "  </tr>";
						$this->salida .= "  </table><br>";
				}
		}
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}

//ad - funcion que pinta todos los examenes leidos en esta evolucion por este medico
function frmConsulta_Apoyod_leyenda()
{
    if (!class_exists('ApoyosDiagnosticos_HTML'))
		{
				return false;
		}
		$pfj=$this->frmPrefijo;
		$medicos = $this->Consulta_General();
         
		if (!empty($medicos))
		{ 
          		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"3\">EXAMENES SOLICITADOS AL PACIENTE POR:</td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
          		//primer ciclo de busqueda
				$paso = 1;	$paso1 = 1;	$paso2 = 1;
				for($i=0;$i<sizeof($medicos);$i++)
				{
						if ($medicos[$i][usuario_id] == UserGetUID())
						{
								if ($paso==1)
								{
										$nombre  = $this->ConsultaNombreMedico($medicos[$i][usuario_id]);
										$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td align=\"left\" colspan=\"3\">".$nombre[descripcion]." - ".$nombre[nombre_tercero]."</td>";
										$this->salida.="</tr>";
										$this->salida.="</table>";
										$paso++;
								}
								$classApoyo = new ApoyosDiagnosticos_HTML;
								$this->salida.=$classApoyo->GetPlantillaApoyoDiagnostico($medicos[$i][resultado_id], $medicos[$i][sw_modo_resultado]);
						}
				}

				//segundo ciclo de busqueda
				for($i=0;$i<sizeof($medicos);$i++)
				{
						if (($medicos[$i][usuario_id] != UserGetUID()) AND ($medicos[$i][departamento] == $this->departamento))
						{
								if ($paso1 ==1)
								{   $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td align=\"left\" colspan=\"3\">OTROS PROFESIONAL DEL MISMO DEPARTAMENTO</td>";
										$this->salida.="</tr>";
                    $this->salida.="</table>";
										$paso1++;
								}
								$classApoyo = new ApoyosDiagnosticos_HTML;
								$this->salida.=$classApoyo->GetPlantillaApoyoDiagnostico($medicos[$i][resultado_id], $medicos[$i][sw_modo_resultado]);
						}
				}

				//tercer ciclo de busqueda
				for($i=0;$i<sizeof($medicos);$i++)
				{
						if (($medicos[$i][usuario_id] != UserGetUID()) AND ($medicos[$i][departamento] != $this->departamento))
						{
								if ($paso2 == 1)
								{   $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td align=\"left\" colspan=\"4\">OTROS PROFESIONALES DE OTROS DEPARTAMENTOS</td>";
										$this->salida.="</tr>";
										$this->salida.="</table>";
										$paso2++;
								}
								$classApoyo = new ApoyosDiagnosticos_HTML;
								$this->salida.=$classApoyo->GetPlantillaApoyoDiagnostico($medicos[$i][resultado_id], $medicos[$i][sw_modo_resultado]);
						}
				}
		}
		$NoSolicitados = $this->ConsultaResultadosNoSolicitadosLeidos();
		if($NoSolicitados)
		{
		    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="<td align=\"left\" colspan=\"3\">OTROS APOYOS DIAGNOSTICOS </td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				for($i=0;$i<sizeof($NoSolicitados);$i++)
				{
						$classApoyo = new ApoyosDiagnosticos_HTML;
						$this->salida.=$classApoyo->GetPlantillaApoyoDiagnostico($NoSolicitados[$i][resultado_id], $NoSolicitados[$i][sw_modo_resultado]);
				}

		}
		return true;
}

     //ad - funcion para el historial que pinta todos los examenes leidos en esta evolucion por este medico
     function frmHistoria()
     { 
     	if (!class_exists('ApoyosDiagnosticos_HTML'))
          {
               return false;
          }

          $pfj=$this->frmPrefijo;
          $medicos = $this->Consulta_General();

	  //echo "<pre>";
          //print_r($medicos);


          if (!empty($medicos))
          {
	          $salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
               $salida.="<tr class=\"modulo_table_title\">";
               $salida.="  <td align=\"center\" colspan=\"3\">EXAMENES SOLICITADOS AL PACIENTE POR:</td>";
               $salida.="</tr>";
               $salida.="</table>";

               //primer ciclo de busqueda
               $paso = 1;	$paso1 = 1;	$paso2 = 1;
               $orden_id = $resultado_id = "";
               for($i=0;$i<sizeof($medicos);$i++)
               {
                    if ($medicos[$i][usuario_id] == UserGetUID() && $resultado_id != $medicos[$i][resultado_id] && $orden_id != $medicos[$i][numero_orden_id])
                    {     
                         $orden_id = $medicos[$i][numero_orden_id];
                         $resultado_id = $medicos[$i][resultado_id];

                         if ($paso==1)
                         {
                              $nombre  = $this->ConsultaNombreMedico($medicos[$i][usuario_id]);
                              $salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
                              $salida.="<tr class=\"modulo_table_title\">";
                              $salida.="  <td align=\"left\" colspan=\"3\">".$nombre[descripcion]." - ".$nombre[nombre_tercero]."</td>";
                              $salida.="</tr>";
                              $salida.="</table>";
                              $paso++;
                         }
                         $classApoyo = new ApoyosDiagnosticos_HTML;
                         $salida.=$classApoyo->GetPlantillaApoyoDiagnostico($medicos[$i][resultado_id], $medicos[$i][sw_modo_resultado]);
                    }
               }

               //segundo ciclo de busqueda
               for($i=0;$i<sizeof($medicos);$i++)
               {
                    if (($medicos[$i][usuario_id] != UserGetUID()) AND ($medicos[$i][departamento] == $this->departamento) && $resultado_id != $medicos[$i][resultado_id] && $orden_id != $medicos[$i][numero_orden_id])
                    {
                         $orden_id = $medicos[$i][numero_orden_id];
                         $resultado_id = $medicos[$i][resultado_id];
                         if ($paso1 ==1)
                         {
                              $salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
                              $salida.="<tr class=\"modulo_table_title\">";
                              $salida.="  <td align=\"left\" colspan=\"3\">OTROS PROFESIONAL DEL MISMO DEPARTAMENTO</td>";
                              $salida.="</tr>";
                              $salida.="</table>";
                              $paso1++;
                         }
                         $classApoyo = new ApoyosDiagnosticos_HTML;
                         $salida.=$classApoyo->GetPlantillaApoyoDiagnostico($medicos[$i][resultado_id], $medicos[$i][sw_modo_resultado]);
                    }
               }

               //tercer ciclo de busqueda
               for($i=0;$i<sizeof($medicos);$i++)
               {
                    if (($medicos[$i][usuario_id] != UserGetUID()) AND ($medicos[$i][departamento] != $this->departamento) && $resultado_id != $medicos[$i][resultado_id] && $orden_id != $medicos[$i][numero_orden_id])
                    {
                         $orden_id = $medicos[$i][numero_orden_id];
                         $resultado_id = $medicos[$i][resultado_id];
                         if ($paso2 == 1)
                         {   
                    		$salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
                              $salida.="<tr class=\"modulo_table_title\">";
                              $salida.="  <td align=\"left\" colspan=\"4\">OTROS PROFESIONALES DE OTROS DEPARTAMENTOS</td>";
                              $salida.="</tr>";
                              $salida.="</table>";
                              $paso2++;
                         }
                         $classApoyo = new ApoyosDiagnosticos_HTML;
                         $salida.=$classApoyo->GetPlantillaApoyoDiagnostico($medicos[$i][resultado_id], $medicos[$i][sw_modo_resultado]);
                    }
               }
          }

          $NoSolicitados = $this->ConsultaResultadosNoSolicitadosLeidos();
          if($NoSolicitados)
          {
               $salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
               $salida.="<tr class=\"modulo_table_title\">";
               $salida.="<td align=\"left\" colspan=\"3\">OTROS APOYOS DIAGNOSTICOS </td>";
               $salida.="</tr>";
               $salida.="</table>";
               for($i=0;$i<sizeof($NoSolicitados);$i++)
               {
                    $classApoyo = new ApoyosDiagnosticos_HTML;
                    $salida.=$classApoyo->GetPlantillaApoyoDiagnostico($NoSolicitados[$i][resultado_id], $NoSolicitados[$i][sw_modo_resultado]);
               }
          }
          
          $cadenaReemplazo = '<td align="center" colspan="1" width="8%">&nbsp;</td><td align="center" colspan="1" width="8%"></td>';
		$salida = preg_replace('|<td[^/]+INFO.*?<\/td><td.*?<\/td>|is',$cadenaReemplazo,$salida);
          return $salida;
     }


//**************************************funciones de la nueva version

//ad - permite que el profesional de lectura a los resultados de los examenes
function Lectura_Resultados_Grupo($evolucion_id)
{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('LECTURA POR GRUPO DE LOS EXAMENES CLINICOS');
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		//vector que carga todos los resultados de una evolucion
		$grupo = $this->ConsultaGrupoLectura($evolucion_id);
		if ($grupo)
		{
				for($q=0;$q<sizeof($grupo);$q++)
				{
// 					if ($grupo[$q][tipo_os_lista_id] != $grupo[$q-1][tipo_os_lista_id])
// 					{
// 							$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
// 							$this->salida.="<tr class=\"modulo_table_list_title\">";
// 							$this->salida.="  <td align=\"left\" colspan=\"4\">".$grupo[$q][nombre_lista]."</td>";
// 							$this->salida.="</tr>";
// 							$this->salida.="</table>";
// 					}
					$classApoyo = new ApoyosDiagnosticos_HTML;
					$accion_observacion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'observacion_medico','evolucion_id'.$pfj => $evolucion_id, 'resultado_id'.$pfj => $grupo[$q][resultado_id]));
  				$this->salida.= $classApoyo->GetPlantillaApoyoDiagnostico($grupo[$q][resultado_id], $grupo[$q][sw_modo_resultado], $evolucion_id, $accion_observacion);
				}
				$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_lectura_grupo','evolucion_id'.$pfj => $evolucion_id));
				$this->salida .= "<form name=\"formades$pfj\" action=\"$action\" method=\"post\">";

				$observaciones_grupales = $this->ConsultaObservacionesGrupales($evolucion_id);
				if($observaciones_grupales)
				{
						$this->salida.="<BR><table  align=\"center\" border=\"0\"  width=\"100%\">";
						$this->salida.="<tr class=\"modulo_table_title\">";
						$this->salida.="<td align=\"left\" colspan=\"5\" width=\"100%\" >LECTURAS GRUPALES DE LOS APOYOS SOLICITADOS EN LA EVOLUCION: ".$evolucion_id."</td>";
						$this->salida.="</tr>";
						$this->salida.="<tr class=\"modulo_list_claro\" >";
						$this->salida.="<td align=\"left\" colspan=\"5\" width=\"100%\" class=\"modulo_list_oscuro\">";
						$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
						$this->salida.="<tr>";
						$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"5%\">No.</td>";
						$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"10%\">EVOLUCION DE LECTURA</td>";
						$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"10%\">REGISTRO</td>";
						$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"25%\">PROFESIONAL</td>";
						$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"50%\">OBSERVACION ADICIONAL AL RESULTADO</td>";
						$this->salida.="</tr>";
						$cadena_observacion_grupal='';
						for($i=0;$i<sizeof($observaciones_grupales);$i++)
						{
								if( $i % 2)    {$estilo='modulo_list_claro';}
								else{$estilo='modulo_list_oscuro';}
								$this->salida.="<tr>";
								$this->salida.="<td align=\"center\" class=\"$estilo\" >".($i+1)."</td>";
								$this->salida.="<td align=\"center\" class=\"$estilo\" >".$observaciones_grupales[$i][evolucion_id]."</td>";
								$this->salida.="<td align=\"center\" class=\"$estilo\" >".$this->FechaStampMostrar($observaciones_grupales[$i][fecha_registro])." - ".$this->HoraStamp($observaciones_grupales[$i][fecha_registro])."</td>";
								$this->salida.="<td align=\"center\" class=\"$estilo\" >".$observaciones_grupales[$i][nombre_tercero]."</td>";
								$this->salida.="<td align=\"left\" class=\"$estilo\" >".$observaciones_grupales[$i][observacion_prof]."</td>";
								$this->salida.="</tr>";
								if($observaciones_grupales[$i][evolucion_id] == $this->evolucion)
								{
										$cadena_observacion_grupal .= $observaciones_grupales[$i][observacion_prof];
										$this->salida.="  <input type='hidden' name = 'update$evolucion_id$pfj'  value='1'>";
								}
						}
						$this->salida.="</table>";
						$this->salida.="</td>";
						$this->salida.="</tr>";
						$this->salida.="</table>";

				}
				$this->salida.="<BR><table  align=\"center\" border=\"0\"  width=\"50%\">";
				$this->salida.="<tr>";
				if ($cadena_observacion_grupal != '')
				{
						$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\" >OBSERVACIÓN DEL MÉDICO<BR><textarea style = \"width:80%\" class=\"textarea\" name=\"observacion_prof$pfj\" rows=\"5\" cols=\"60\">".$cadena_observacion_grupal."</textarea><br>&nbsp;</td>";
				}
				else
				{
						$this->salida.="<td align=\"center\" class=\"hc_table_submodulo_list_title\" >OBSERVACIÓN DEL MÉDICO<BR><textarea style = \"width:80%\" class=\"textarea\" name=\"observacion_prof$pfj\" rows=\"5\" cols=\"60\">".$_REQUEST['observacion_prof'.$pfj]."</textarea><br>&nbsp;</td>";
				}
				$this->salida.="</tr>";

				$this->salida.="</table>";

				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"50%\">";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida .= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
				$this->salida .= "</form>";
				//BOTON DEVOLVER
				$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
				$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
				$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td>";
				$this->salida .= "</form>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
		}

		//vector que carga todos los apoyos sin resultados de una evolucion
		$SinResultado = $this->ConsultaApoyosSinResultado($evolucion_id);
		if ($SinResultado)
		{
				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"left\" colspan=\"4\">INFORMACION--> APOYOS DE ESTA EVOLUCION SIN RESULTADOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"2\">EXAMEN</td>";
				$this->salida.="  <td align=\"center\" colspan=\"2\">ESTADO</td>";
				$this->salida.="</tr>";
				for($q=0;$q<sizeof($SinResultado);$q++)
				{
							$this->salida.="<tr class=\"modulo_list_claro\">";
							$this->salida.="  <td align=\"center\" colspan=\"2\">".$SinResultado[$q][titulo_examenes]."</td>";

							if($SinResultado[$q][realizacion] == '0')
							{
									$this->salida.="  <td align=\"center\" colspan=\"2\" >Sin Realizar</td>";
							}
							if($SinResultado[$q][realizacion] == '1')
							{
											$this->salida.="  <td align=\"center\" colspan=\"2\">Sin Pagar</td>";

							}
							if($SinResultado[$q][realizacion] == '2')
							{
									$this->salida.="  <td align=\"center\" colspan=\"2\">Pagado sin Cumplimiento</td>";

							}
							if($SinResultado[$q][realizacion] == '3')
							{
									$this->salida.="  <td align=\"center\" colspan=\"2\">Cumplido</td>";

							}
							if($SinResultado[$q][realizacion] == '4')
							{
									$this->salida.="  <td align=\"center\" colspan=\"2\">Resultado Completo</td>";
							}


							$this->salida.="</tr>";
			}
			$this->salida.="</table>";
		}
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}


function frmForma_Observacion($evolucion_id, $resultado_id)
{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('LECTURA POR APOYO DIAGNOSTICO');

		$examen = $this->Get_Nombre_Examen($resultado_id);

		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_lectura_examen','evolucion_id'.$pfj => $evolucion_id, 'resultado_id'.$pfj => $resultado_id));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$action\" method=\"post\">";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"2\">".$examen[descripcion]."</td>";
		$this->salida.="</tr>";


		if( $i % 2){ $estilo='modulo_list_claro';}
		else {$estilo='modulo_list_oscuro';}


		$observaciones = $this->ConsultaObservaciones($resultado_id);
		if ($observaciones)
		{
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan=\"1\" align=\"left\" class=\"hc_table_submodulo_list_title\" width=\"25%\">OBSERVACIONES MEDICAS REALIZADAS</td>";
				$this->salida.="<td colspan=\"1\" align=\"left\" width=\"75%\">";
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				for($i=0;$i<sizeof($observaciones);$i++)
				{
						$this->salida.="<tr>";
						$this->salida.="<td align=\"left\" class=\"hc_table_submodulo_list_title\" >".$observaciones[$i][descripcion]." - ".$observaciones[$i][nombre]."</td>";
						$this->salida.="</tr>";

						$this->salida.="<tr>";
						$this->salida.="<td align=\"left\"class=\"$estilo\" >".$observaciones[$i][observacion_prof]."</td>";
						$this->salida.="</tr>";
				}
				$this->salida.="</table>";
				$this->salida.="</td>";
				$this->salida.="</tr>";
		}

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td align=\"center\" width=\"15%\">OBSERVACION</td>";
		$this->salida .="<td align=\"center\" width=\"65%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"obs$pfj\" cols=\"60\" rows=\"5\">".$_REQUEST['obs'.$pfj]."</textarea></td>" ;
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"$estilo\">";
		$this->salida .= "<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida .= "</form>";

		//BOTON DEVOLVER
		$accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'Lectura_Resultados_Grupo', 'evolucion_id'.$pfj=>$evolucion_id));
		$this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<tr><td  colspan = 6 align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td></tr>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
}


function Pintar_Apoyo($vector, $vector1, $prefijo, $nombre)
{
		$pfj=$this->frmPrefijo;
		$texto ='';
		$estilo='modulo_list_claro';
		$texto.="<tr class=\"$estilo\">";

		//$_SESSION['cerrar'] ->variable nueva para agrupar por evolucion
		if ($_SESSION['cerrar'.$prefijo] == 1)
		{
				if ($prefijo == 'caso1')
				{
						$texto.="<td align=\"center\" width=\"5%\" colspan=\"1\">".$vector[evolucion_id]."</td>";
				}
				else
				{
						//$nombre  = $this->ConsultaNombreMedico($vector[usuario_id]);
						$texto.="<td align=\"center\" width=\"5%\" colspan=\"1\"><input type='image' name='submit' src='".GetThemePath()."/images/EstacionEnfermeria/info.png' border='0' title='".$nombre[nombre_tercero]." - ".$nombre[descripcion]."'>".$vector[evolucion_id]."</td>";
				}
				$texto.="<td align=\"center\" colspan=\"3\" width=\"70%\">";
				$texto.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$texto.="<tr class=\"$estilo\">";
				$_SESSION['cerrar'.$prefijo]=0;
		}
		//fin de la modificacion
		$texto.="  <td align=\"center\" width=\"11%\">".$this->FechaStampMostrar($vector[fecha])."</td>";
		$texto.="  <td align=\"left\" width=\"37%\">".$vector[titulo_examenes]." - ".$vector[realizacion]."</td>";
		//COMENTADO POR MI***
		// 									if($vector[autorizado] == '0' OR $vector[autorizado] == '2')
		// 									{
		// 											if ($vector[autorizado] == '0')
		// 											{
		// 													$texto.="  <td align=\"center\" width=\"10%\">Sin Autorizar</td>";
		// 											}
		// 											if ($vector[autorizado] == '2')
		// 											{
		// 													$a=RetornarWinOpenDatosAutorizacion($vector[autorizacion_int],$vector[autorizacion_ext],'No Fue Autorizado');
		// 													$texto.="  <td align=\"center\" width=\"10%\">$a</td>";
		// 											}
		// 											$texto.="  <td align=\"center\" width=\"10%\"></td>";
		// 									}
		// 									else
		// 											if($vector[autorizado] == '1')
		// 											{
		// 									{
		//HASTA AQUI COMENTE
		if($vector[realizacion] == '0')
		{
				$texto.="  <td align=\"center\" width=\"10%\">Sin Realizar</td>";
		}

		//lo nuevo que se crea para poder validar caso de la sos
		if (($_SESSION['RESULTADOS_MANUALES']['sw_ingreso_manual']) == '1')
		{
				if($vector[realizacion] == '1')
				{
						$_SESSION['LTRABAJOAPOYOD']['TIPO_PRESENTACION'] = 2;
            $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'capturar_resultados','evolucion_id'.$pfj => $vector[evolucion_id]));
						$texto.="  <td align=\"center\" width=\"10%\"><a href='$accion'>Tomado sin Resultados SOS</a></td>";
				}
		}
		else
		{
				if($vector[realizacion] == '1')
				{
						$texto.="  <td align=\"center\" width=\"10%\">Sin Pagar</td>";
				}
		}
		//fin de lo nuevo

		if($vector[realizacion] == '2')
		{
				$texto.="  <td align=\"center\" width=\"10%\">Pagado sin Cumplimiento</td>";
		}

		//MauroB
		$permiso=$this->PermisoConsultaSinFirma($vector[numero_orden_id]);
		if($vector[realizacion] == '3')
		{
			if($permiso=='1'){
				$_SESSION['lectura_activa'.$prefijo]++;
				$texto.="  <td align=\"center\" width=\"10%\"><font color='#4D6EAB'>Resultado Sin Firma</font></td>";
			}
			else{
				$texto.="  <td align=\"center\" width=\"10%\">Cumplido</td>";
			}
		}
		//fin MauroB
		if($vector[realizacion] == '4')
		{   $_SESSION['lectura_activa'.$prefijo]++;
				$texto.="  <td align=\"center\" width=\"10%\">Resultado Completo</td>";
		}
		//***COMENTADO POR MI PORQUE NO VALIDO LO AUTORIZADO
		/*	  }
		}*/

		$texto.="</tr>";
		//claudia entrega feb01/2005 - lo nuevo de apoyos
		//if que cierra el agrupaiento d elas evoluciones.
		if ($vector[evolucion_id] != $vector1[evolucion_id])
		{	
				$texto.="  </table>";
				$texto.="  </td>";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'lectura_resultados_grupo','evolucion_id'.$pfj => $vector[evolucion_id], $_SESSION['APOYO']['usuario_id'.$vector[evolucion_id]] = $vector[usuario_id], $_SESSION['APOYO']['departamento'.$vector[evolucion_id]] = $vector[departamento]));
				if ($_SESSION['lectura_activa'.$prefijo] >= 1)
				{
						$verificando_lectura = $this->RegistroLecturasGrupo($vector[evolucion_id]);
						if($verificando_lectura[sw_prof] == '' AND $verificando_lectura[sw_prof_dpto] == ''
						AND $verificando_lectura[sw_prof_todos] == '')
						{
								$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><img src=\"".GetThemePath()."/images/resultado.png\" border='0'> Leer</a></td>";
						}
						elseif($verificando_lectura[sw_prof] == '1')
						{
								$observaciones_grupales = $this->ConsultaObservacionesGrupales($vector[evolucion_id]);
								$cadena ='';
								for($k=0;$k<sizeof($observaciones_grupales);$k++)
								{
										$cadena.= $observaciones_grupales[$k][nombre_tercero]."\n";
										if ($k ==4){break;}
								}
								$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><input type='image' name='submit' src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0' title='$cadena'> Leido</a></td>";
								//$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><img src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0'> Leido</a></td>";
						}
						elseif($verificando_lectura[sw_prof] == '2')
						{
								$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><img src=\"".GetThemePath()."/images/EstacionEnfermeria/Listado.png\" border='0'> Nuevo(s) Resultado(s)</a></td>";
						}
						elseif($verificando_lectura[sw_prof_dpto] == '1')
						{
								$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><img src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0'> Leido Profesional Dpto</a></td>";
						}
						elseif($verificando_lectura[sw_prof_dpto] == '2')
						{
								$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><img src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0'> Leido Profesional Otro Dpto</a></td>";
						}
						elseif($verificando_lectura[sw_prof_todos] == '1')
						{
								$texto.="  <td align=\"center\" width=\"5%\"><a href='$accion'><img src=\"".GetThemePath()."/images/asignacion_citas.png\" border='0'> Leido Profesional Otro Dpto</a></td>";
						}
						$_SESSION['lectura_activa'.$prefijo] = 0;
				}
				else
				{
						$texto.="  <td align=\"center\" width=\"5%\">Desactivado para Lectura</td>";
				}
				$texto.="</tr>";
				$_SESSION['cerrar'.$prefijo] = 1;
		}
		return $texto;
	}

//NUEVAS FUNCIONES PARA AJUSTAR LA ULTIMA VERSION DE LISTAS DE TRABAJO. AGO-13-2005

/*
	* Esta funcion le permite al usuario seleccionar el tipo de
	* tecnica que usara para la transcripcion del examen
	* @return boolean
	*/
	function frmSeleccion_Tecnica($multitecnica)
  {
			$pfj=$this->frmPrefijo;
			$this->salida= ThemeAbrirTablaSubModulo('SELECCION DE TECNICA');
			$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'plant_forma'));
			$this->salida .= "<form name=\"formades$pfj\" action=\"$action\" method=\"post\">";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\">ID DEL PACIENTE</td>";
			$this->salida.="  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\">".$this->tipoidpaciente.": ".$this->paciente."</td>";
			$this->salida.="  <td align=\"center\">".$_SESSION['LISTA']['APOYO']['nombre']."</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";

			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"1\">".$_SESSION['LISTA']['APOYO']['titulo']."</td>";
			$this->salida.="</tr><br>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"1\">SELECCIONE LA TECNICA PARA EL EXAMEN</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";

			$this->salida.="<td width=\"35%\" align = \"center\" >";
			$this->salida.="<select size = \"1\" name = \"selector_multitecnica$pfj\"  class =\"select\">";
			if (empty($_SESSION['LISTA']['APOYO']['tecnica_id']))
			{
					for($i=0;$i<sizeof($multitecnica);$i++)
					{
							if ($multitecnica[$i][sw_predeterminado] != '1')
							{
									$this->salida.="<option value = ".$multitecnica[$i][tecnica_id].">".$multitecnica[$i][nombre_tecnica]."</option>";
							}
							else
							{
									$this->salida.="<option value = ".$multitecnica[$i][tecnica_id]." selected >".$multitecnica[$i][nombre_tecnica]."</option>";
							}
					}
			}
			else
			{
					for($i=0;$i<sizeof($multitecnica);$i++)
					{
							if ($_SESSION['LISTA']['APOYO']['tecnica_id'] != $multitecnica[$i][tecnica_id])
							{
									$this->salida.="<option value = ".$multitecnica[$i][tecnica_id].">".$multitecnica[$i][nombre_tecnica]."</option>";
							}
							else
							{
									$this->salida.="<option value = ".$multitecnica[$i][tecnica_id]." selected >".$multitecnica[$i][nombre_tecnica]."</option>";
							}
					}
			}
			$this->salida.="</select>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.= "<tr>";
			$this->salida.= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"siguiente$pfj\" type=\"submit\" value=\"SIGUIENTE\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";


			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= "<tr>";
			$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'for'));
			$this->salida .= "<form name=\"forma$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida .= ThemeCerrarTablaSubModulo();
			return true;
  }



  /*
	* Esta funcion le permite al usuario realizar la captura de un resultado de
	* forma individual
	* @return boolean
	*/
	//modificada
function frmCrearFormaE()
{
		$pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('CAPTURA DE RESULTADOS INDIVIDUALES');
		$sexo_paciente = $this->GetSexo($_SESSION['LISTA']['APOYO']['tipo_id_paciente'], $_SESSION['LISTA']['APOYO']['paciente_id']);
		$edad_paciente = $this->Obtener_Edad($_SESSION['LISTA']['APOYO']['tipo_id_paciente'], $_SESSION['LISTA']['APOYO']['paciente_id']);
		$k = 0;
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar'));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$action\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";


		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="<td align=\"center\" width=\"5%\">CARGO</td>";
		$this->salida.="<td align=\"center\" width=\"60%\">EXAMEN</td>";
		$this->salida.="<td align=\"center\" width=\"12%\" colspan=\"1\">OPCIONES</td>";
		$this->salida.="<td align=\"center\" width=\"23%\" class=\"".$this->SetStyle("fecha_realizado$k$pfj")."\">FECHA</td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align=\"center\" width=\"5%\">".$_SESSION['LISTA']['APOYO']['cargo']."</td>";
		$this->salida.="<td align=\"center\" width=\"60%\">".strtoupper($_SESSION['LISTA']['APOYO']['titulo'])."</td>";

          if($_SESSION['LISTA']['APOYO']['informacion']=='')
		{
               $this->salida.="<td align=\"center\" width=\"12%\"><img src=\"".GetThemePath()."/images/Vacio.gif\" border=0 title=\"sin Informacion \"></td>";
		}
		else
		{
               $this->salida.="<td align=\"center\" width=\"12%\"><img src=\"".GetThemePath()."/images/EstacionEnfermeria/info.png\" title=\"Informacion: ".$_SESSION['LISTA']['APOYO']['informacion']."\"  border=\"0\"></td>";
		}
    
		if (empty($_REQUEST['fecha_realizado'.$pfj]))
		{
				$_REQUEST['fecha_realizado'.$pfj] = date('d-m-Y');
		}
		$this->salida .="<td align=\"center\" width=\"23%\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value = \"".$_REQUEST['fecha_realizado'.$pfj]."\" name=\"fecha_realizado$pfj\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">".ReturnOpenCalendario('formades'.$pfj,'fecha_realizado'.$pfj,'-')."</td>" ;

		$this->salida.="</tr>";
		$this->salida.="</table>";

		//llama a la funcion que consulta los examenes que pertenecen a ese titulo examen
		$vector=$this->ConsultaComponentesExamen($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LISTA']['APOYO']['tecnica_id'], $sexo_paciente, $edad_paciente[anos], '', '', '','1');
		if(!$vector)
		{
				if($this->CrearGenerico($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LISTA']['APOYO']['titulo'])==true)
				{
						$_SESSION['LISTA']['APOYO']['tecnica_id'] = 1;
						$vector=$this->ConsultaComponentesExamen($_SESSION['LISTA']['APOYO']['cargo'], $_SESSION['LISTA']['APOYO']['tecnica_id'], $sexo_paciente, $edad_paciente[anos], '', '', '','0');
				}
		}
		if($vector)
		{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
				$indmin=1;
				$e=0;
				$k=0;
        for($i=0;$i<sizeof($vector);$i++)
				{
						if( $i % 2)
						{$estilo='modulo_list_claro';}
						else
						{$estilo='modulo_list_oscuro';}
						switch ($vector[$i][lab_plantilla_id])
						{
								case "1": {
															$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
															$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
															$this->salida.="<td width=\"30%\" align=\"center\">RESULTADO</td>";
															$this->salida.="<td width=\"10%\" align=\"center\">V.MIN</td>";
															$this->salida.="<td width=\"10%\" align=\"center\">V.MAX</td>";
															$this->salida.="<td width=\"10%\" align=\"center\">UND</td>";
															$this->salida.="<td width=\"5%\"  align=\"center\">PAT.</td>";
															$this->salida.="</tr>";

															if(is_null($vector[$i]['rango_min']) || $vector[$i]['rango_min'] == '0')
															{
																	$vector[$i]['rango_min'] = 0;
															}
															$this->salida.="<tr class=\"$estilo\">";
															$this->salida.="<td width=\"35%\" align=\"left\"  class=\"".$this->SetStyle("resultado$k$e$pfj")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";
															$this->salida.="<td width=\"30%\" align=\"center\"><input type=\"text\" name = \"resultado$k$e$pfj\" value =\"".$_REQUEST['resultado'.$k.$e.$pfj]."\">&nbsp;".$vector[$i]['unidades_1']."</td>";
															$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmin$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i]['rango_min']."\"></td>";
															$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"rmax$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i]['rango_max']."\"></td>";
															$this->salida.="<td width=\"10%\" align=\"center\"><input type=\"text\" name=\"unidades$k$e$pfj\" class=\"input-text-center\" size=\"10\"   value=\"".$vector[$i]['unidades_1']."\"></td>";
															if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
															{
																	$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
															}
															else
															{
																	$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
															}
															$this->salida.="</tr>";

															$this->salida.="  <input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i]['lab_examen_id']."\">";
															$e++;
															break;
													}

								case "2": {
															if ($indmin == 1)
															{
																	$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
																	$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
																	$this->salida.="<td width=\"40%\" align=\"center\" colspan = \"2\">RESULTADO</td>";
																	$this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\">UND</td>";
																	$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
																	$this->salida.="</tr>";
																	$this->salida.="<tr class=\"$estilo\">";
																	$this->salida.="<td align=\"left\" width=\"35%\" class=".$this->SetStyle("resultado$k$e$pfj").">".strtoupper($vector[$i]['nombre_examen'])."</td>";
																	$this->salida.="<td align=\"center\" width=\"40%\" colspan = \"2\">";

																	$this->salida.="<select size = \"1\" name = \"resultado$k$e$pfj\"  class =\"select\">";
																	$this->salida.="<option value = \"-1\" >--Seleccione--</option>";
																	if($_REQUEST['resultado'.$k.$e.$pfj]==$vector[$i]['opcion'])
																	{
																			$this->salida.="<option value = \"".$vector[$i]['opcion']."\" selected>".$vector[$i]['opcion']."</option>";
																	}
																	else
																	{
																			$this->salida.="<option value = \"".$vector[$i]['opcion']."\" >".$vector[$i]['opcion']."</option>";
																	}
																	$indmin++;
															}
															else
															{
																	if($_REQUEST['resultado'.$k.$e.$pfj]==$vector[$i]['opcion'])
																	{
																			$this->salida.="<option value = \"".$vector[$i]['opcion']."\" selected>".$vector[$i]['opcion']."</option>";
																	}
																	else
																	{
																			$this->salida.="<option value = \"".$vector[$i]['opcion']."\" >".$vector[$i]['opcion']."</option>";
																	}
															}
															if($vector[$i]['lab_examen_id']!=$vector[$i+1]['lab_examen_id'])
															{
																	$this->salida.="</select>";
																	$this->salida.="</td>";
																	$this->salida.="<td width=\"20%\" align=\"center\" colspan = \"2\"><input type=\"text\" class=\"input-text-center\" name=\"unidades$k$e$pfj\"  size=\"10\"   value=\"".$vector[$i]['unidades_2']."\"></td>";
																	if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
																	{
																			$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
																	}
																	else
																	{
																			$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
																	}
																	$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i]['lab_examen_id']."\">";
																	$this->salida.="</tr>";
																	$indmin=1;
																	$e++;
															}
															break;
													}

								case "3": {
															$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
															$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
															$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\" class=".$this->SetStyle("resultado$k$e$pfj").">".strtoupper($vector[$i]['nombre_examen'])."</td>";
															$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
															$this->salida.="</tr>";

															$this->salida.="<tr class=\"$estilo\">";
															if($_REQUEST['resultado'.$k.$e.$pfj]==='' OR !empty($_REQUEST['resultado'.$k.$e.$pfj]))
															{
																	//$this->salida.="<td colspan = \"5\" align=\"center\" width=\"95%\"><textarea style = \"width:90%\" class=\"textarea\" name = \"resultado$k$e$pfj\" cols = \"150\" rows = \"25\">".$_REQUEST['resultado'.$k.$e.$pfj]."</textarea></td>";
																	$this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
																	$this->salida .= getFckeditor("resultado$k$e$pfj",'200',"100%",$_REQUEST['resultado'.$k.$e.$pfj]);
																	$this->salida .= "</td>";
															}
															else
															{
																	//$this->salida.="<td colspan = \"5\" align=\"center\" width=\"95%\"><textarea style = \"width:90%\" class=\"textarea\" name = \"resultado$k$e$pfj\" cols = \"150\" rows = \"25\">".$vector[$i]['detalle']."</textarea></td>";
																	$this->salida .= "<td colspan = \"5\" align=\"center\" width=\"60%\">";
																	$this->salida .= getFckeditor("resultado$k$e$pfj",'200',"100%",$vector[$i]['detalle']);
																	$this->salida .= "</td>";
															}
															if($_REQUEST['sw_patologico'.$k.$e.$pfj]=='1')
															{
																	$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
															}
															else
															{
																	$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
															}

															$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i]['lab_examen_id']."\">";
															$this->salida.="</tr>";
															$e++;
															break;
													}

								case "0": {
															$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
															$this->salida.="<td width=\"35%\" align=\"center\">SUBEXAMEN</td>";
															$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\">RESULTADO</td>";
															$this->salida.="<td width=\"5%\" align=\"center\">PAT.</td>";
															$this->salida.="</tr>";

															$this->salida.="<tr class=\"$estilo\">";
															$this->salida.="<td width=\"35%\" align=\"center\" class=\"".$this->SetStyle("resultado$k$e$pfj")."\">".strtoupper($vector[$i]['nombre_examen'])."</td>";
															//$this->salida.="<td width=\"60%\" align=\"center\" colspan = \"4\"><textarea style = \"width:80%\" class=\"textarea\" name = \"resultado$k$e$pfj\" cols = \"60\" rows = \"10\">".$_REQUEST['resultado'.$k.$e.$pfj]."</textarea></td>";
															$this->salida .= "<td colspan = \"4\" align=\"center\" width=\"60%\">";
															$this->salida .= getFckeditor("resultado$k$e$pfj",'200',"100%",$_REQUEST['resultado'.$k.$e.$pfj]);
															$this->salida .= "</td>";
															if ($_REQUEST['sw_patologico'.$k.$e.$pfj] == '1')
															{
																	$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" checked name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
															}
															else
															{
																	$this->salida.="<td width=\"5%\"  align=\"center\"><input type=\"checkbox\" name=\"sw_patologico$k$e$pfj\" value=\"1\"></td>";
															}
															$this->salida.="<input type=\"hidden\" name = \"lab_examen$k$e$pfj\"  value=\"".$vector[$i]['lab_examen_id']."\">";
															$this->salida.="</tr>";
															$e++;
															break;
													}
						}//cierra el switche
				}//cierra el for
				$this->salida.="</table>";
				$items = $e;
				$this->salida.="  <input type=\"hidden\" name = \"items$k$pfj\"  value=\"$items\">";

				$this->salida.="<br><table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\">OBSERVACION DEL MEDICO</td>";
				$this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td rowspan = 3 colspan = \"1\" align=\"center\" width=\"40%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion_medico$pfj\" cols=\"60\" rows=\"5\">".$_REQUEST['observacion_medico'.$pfj]."</textarea></td>" ;
        $this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion$pfj\" cols=\"60\" rows=\"5\">".$_REQUEST['observacion'.$pfj]."</textarea></td>" ;
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\">LABORATORIO: <input type=\"text\" name = \"laboratorio$pfj\" value =\"".$_REQUEST['laboratorio'.$pfj]."\"></td>";
				$this->salida.="</tr>";

				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td colspan = \"1\" align=\"center\" width=\"40%\">PROFESIONAL: <input type=\"text\" name = \"profesional$pfj\" value =\"".$_REQUEST['profesional'.$pfj]."\"></td>";
				$this->salida.="</tr>";
 				$this->salida.="</table>";

				$this->salida.="<table align=\"center\" width=\"30%\" border=\"0\">";
				$this->salida .= "<tr>";
				$this->salida .= "<td  colspan = \"2\" align=\"center\"><br><input class=\"input-submit\" name=\"Insertar$pfj\" type=\"submit\" value=\"INSERTAR\"></td>";
				$this->salida .= "</form>";
				$this->salida .= "</tr>";
				$this->salida .= "<tr>";

				$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'forma', 'retorno'.$pfj =>'1'));
				$this->salida .= "<form name=\"forma$pfj\" action=\"$accion2\" method=\"post\">";
				$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER A LA TECNICA\"></form></td>";

				$accion3=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
				$this->salida .= "<form name=\"forma$pfj\" action=\"$accion3\" method=\"post\">";
				$this->salida .= "<td  align=\"center\"><br><input class=\"input-submit\" name=\"Apoyod$pfj\" type=\"submit\" value=\"LISTA DE APOYO DIAG.\"></form></td>";

				$this->salida .= "</tr>";
				$this->salida .=  "</table><br>";
				$this->salida .= ThemeCerrarTablaSubModulo();
				return true;
	}
 }
 
 /**
  * Funcion que le permite al prestador del servicio agregarle a un resultado una
  * observacion.  esta funcion es utilizada por la captura grupal.
  * @return boolean
  */
  //ALTERADA
  function frmForma_Observacion_Prestador_Servicio($paciente_id, $tipo_id_paciente, $evolucion_id, $k)
  {
      $pfj=$this->frmPrefijo;
      $this->salida= ThemeAbrirTablaSubModulo('OBSERVACION DEL PRESTADOR DEL SERVICIO');            
      $action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'insertar_observacion_prestador_servicio','indice'.$pfj=>$k, 'evolucion_id'.$pfj=>$evolucion_id, 'paciente_id'.$pfj=>$paciente_id, 'tipo_id_paciente'.$pfj=>$tipo_id_paciente));
      $this->salida .= "<form name=\"forma$pfj\" action=\"$action\" method=\"post\">";

      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.= $this->SetStyle("MensajeError");
      $this->salida.="</table>";

      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="<tr class=\"modulo_table_title\">";
      $this->salida.="  <td align=\"center\">ID DEL PACIENTE</td>";
      //$this->salida.="  <td align=\"center\">NOMBRE DEL PACIENTE</td>";
      $this->salida.="</tr>";
      $this->salida.="<tr class=\"modulo_table_title\">";
      $this->salida.="  <td align=\"center\">".$tipo_id_paciente.": ".$paciente_id."</td>";
      //$this->salida.="  <td align=\"center\">".$nombre."</td>";
      $this->salida.="</tr>";
      $this->salida.="</table><br>";

      if( $i % 2){ $estilo='modulo_list_claro';}
      else {$estilo='modulo_list_oscuro';}

      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="<tr class=\"modulo_table_title\">";
      $this->salida.="<td align=\"center\" width=\"15%\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['cargo']."</td>";
      $this->salida.="<td align=\"center\" width=\"65%\">".strtoupper($_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['titulo'])."</td>";
      $this->salida.="</tr>";
      $this->salida.="<tr class=\"$estilo\">";
      $this->salida.="<td align=\"center\" width=\"15%\">OBSERVACION DEL PRESTADOR DEL SERVICIO</td>";
      $this->salida.="<td align=\"center\" width=\"65%\"><textarea style = \"width:80%\" class=\"textarea\" name = \"observacion$pfj\" cols=\"60\" rows=\"5\">".$_SESSION['APOYO'][$tipo_id_paciente][$paciente_id][$k]['observacion']."</textarea></td>" ;
      $this->salida.="</tr>";
      $this->salida.="<tr class=\"$estilo\">";
      $this->salida.="<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"GUARDAR\"></td>";
      $this->salida.="</tr>";
      $this->salida.="</table>";
      $this->salida.="</form>";

      //BOTON DE VOLVER
      $this->salida.="<table align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="<tr>";      
      $accionV=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false, array('accion'.$pfj=>'capturar_resultados', 'paciente_id'.$pfj=>$paciente_id, 'tipo_id_paciente'.$pfj=>$tipo_id_paciente, 'evolucion_id'.$pfj=>$evolucion_id));
      $this->salida .= "<form name=\"formades$pfj\" action=\"$accionV\" method=\"post\">";
      $this->salida.="<td colspan = \"2\" align=\"center\"><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"VOLVER\"></form></td>";
      $this->salida.="</tr>";
      $this->salida.="</table>";
      $this->salida.= ThemeCerrarTablaSubModulo();
      return true;       
  } 
}
?>
