<?php

/**
 * $Id: app_TranscripcionesPsicologicas_userclasses_HTML.php,v 1.20 2007/10/03 23:11:26 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las autorizaciones.
 */

class app_TranscripcionesPsicologicas_userclasses_HTML extends app_TranscripcionesPsicologicas_user
{

     function app_TranscripcionesPsicologicas_user_HTML()
	{
          $this->salida='';
          $this->app_TranscripcionesPsicologicas_user();
          return true;
	}


  	function SetStyle($campo)
	{
          if ($this->frmError[$campo] || $campo=="MensajeError"){
               if ($campo=="MensajeError"){
                    return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
               }
               return ("label_error");
          }
          return ("label");
	}


	/**
	*
	*/
	function FormaMenus()
	{
          $this->salida .= ThemeAbrirTabla('TRANSCRIPCION DE PRUEBAS APLICADAS');
          $this->salida .= "            <br>";
          $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "               <tr>";
          $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU BIOESTADISTICA</td>";
          $this->salida .= "               </tr>";
          
          $this->salida .= "               <tr>";
          $accionF=ModuloGetURL('app','TranscripcionesPsicologicas','user','LlamarFormaBuscarPaciente');
          $this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a title='Permite realizar las busquedas de las pruebas aplicadas' href=\"$accionF\">BUSQUEDA DE PRUEBAS APLICADAS</a></td>";
          $this->salida .= "               </tr>";
          $this->salida .= "           </table>";
          
          $accion=ModuloGetURL('app','TranscripcionesPsicologicas','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></p>";
          $this->salida .= "</form>";
          $this->salida .= ThemeCerrarTabla();
          return true;
	}

	function FormaBuscarPaciente($arr)
	{
          $this->SetJavaScripts('DatosPaciente');
          $this->salida .= ThemeAbrirTabla('TRANSCRIPCIONES - BUSCAR PRUEBAS PACIENTE');
		//--------------------------
          $accion=ModuloGetURL('app','TranscripcionesPsicologicas','user','BuscarPaciente');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<table class=\"modulo_list_claro\" border=\"0\" width=\"40%\" align=\"center\">";
          $this->salida .= "<tr class=\"modulo_table_list_title\">";
          $this->salida .= "<td align = left colspan=\"2\">CRITERIOS DE BUSQUEDA:</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr class=\"modulo_list_claro\" >";
          $this->salida .= "<td width=\"40%\" colspan=\"2\">";
          $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
          $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
          $tipo_id=$this->TiposIdPacientes();
          $this->salida .=" <option value=\"\">----SELECCIONE---</option>";
          for($i=0; $i<sizeof($tipo_id); $i++)
          {
               $this->salida .=" <option value=\"".$tipo_id[$i]['tipo_id_paciente']."\">".$tipo_id[$i]['descripcion']."</option>";
               if($_REQUEST['TipoDocumento']==$tipo_id[$i]['tipo_id_paciente'])
               {
                    $this->salida .=" <option value=\"".$tipo_id[$i]['tipo_id_paciente']."\" selected>".$tipo_id[$i]['descripcion']."</option>";
               }
          }
          $this->salida .= "                  </select></td></tr>";
          $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
          $this->salida .= "  </table>";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr class=\"modulo_list_claro\">";
        	$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
          $this->salida .= "            </form>";
        	$accion=ModuloGetURL('app','TranscripcionesPsicologicas','user','FormaMenus');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        	$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td>";
          $this->salida .= "            </form>";
          $this->salida .= "</tr>";
          $this->salida .= "  </table>";
          //mensaje
          $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "  </table>";
          if($arr)
          {
               $this->salida .= "		   <br>";
               $this->salida .= "		<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
               $this->salida .= "				<td>IDENTIFICACION</td>";
               $this->salida .= "				<td>NOMBRE</td>";
               $this->salida .= "				<td>PRUEBA</td>";
               $this->salida .= "				<td>FECHA PRUEBA</td>";
               $this->salida .= "			</tr>";
               for($i=0;$i<sizeof($arr);$i++)
               {
                    if( $i % 2) $estilo='modulo_list_claro';
                    else $estilo='modulo_list_oscuro';
                    $this->salida .= "			<tr class=\"$estilo\">";
                    $this->salida .= "				<td width=\"20%\">".$arr[$i][tipo_id_paciente]."  ".$arr[$i][paciente_id]."</td>";
                    $dato=RetornarWinOpenDatosPaciente($arr[$i][tipo_id_paciente],$arr[$i][paciente_id],$arr[$i][nombre]);
                    $this->salida .= "				<td width=\"36%\">".$dato."</td>";
                    $accion1=ModuloGetURL('app','TranscripcionesPsicologicas','user','InsertTranscripcion',array('tipopaciente'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso],'evolucion'=>$arr[$i][evolucion_id],'prueba'=>$arr[$i][nombre_prueba],'tipo_motivo'=>$arr[$i][tipo_motivo_d],'nombre'=>$arr[$i][nombre],'trabajo'=>$arr[$i][trabajo_id]));
                    $this->salida .= "				<td align=\"left\" width=\"15%\"><a href=\"$accion1\">".strtoupper($arr[$i][nombre_prueba])."</a></td>";
                    $fecha = explode(" ", $arr[$i][fecha_registro]);
                    $this->salida .= "				<td align=\"center\" width=\"15%\">".$fecha[0]."</td>";                    
                    $this->salida .= "			</tr>";
               }//fin for
               $this->salida .= " </table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra();
          }
          $this->salida .= ThemeCerrarTabla();
          return true;
 	}
	
     
     function FormaInsertarTranscripcionPruebas($tipopaciente, $paciente, $ingreso, $evolucion, $prueba, $tipo_motivo, $nombre, $trabajo, $desc)
     {
          $this->salida .= ThemeAbrirTabla('TRANSCRIPCIONES - BUSCAR PRUEBAS PACIENTE');
          $this->Encabezado();
          $accion=ModuloGetURL('app','TranscripcionesPsicologicas','user','InsertarTranscripcion',array('tipopaciente'=>$tipopaciente,'paciente'=>$paciente,'ingreso'=>$ingreso,'evolucion'=>$evolucion,'tipo_motivo'=>$tipo_motivo,'nombre'=>$nombre,'trabajo'=>$trabajo,'prueba'=>$prueba));
          $this->salida .= "<form name=\"formainsert\" action=\"$accion\" method=\"post\">";
          //mensaje
          $this->salida .= "<BR><table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "  </table>";
          $this->salida .= "<BR><BR><table class=\"modulo_table_title\" border=\"0\" width=\"70%\" align=\"center\" >";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td colspan=\"4\">INFORMACION PRUEBA APLICADA</td>";
          $this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= "<td width=\"15%\" class=\"modulo_list_oscuro\">PACIENTE</td>";
          $this->salida .= "<td width=\"40%\" class=\"modulo_list_claro\">".$nombre."</td>";
		$this->salida .= "<td width=\"15%\" class=\"modulo_list_oscuro\">IDENTIFICACION</td>";
          $this->salida .= "<td width=\"40%\" class=\"modulo_list_claro\">".$tipopaciente." - ".$paciente."</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td width=\"15%\" class=\"modulo_list_oscuro\">INGRESO</td>";
          $this->salida .= "<td width=\"40%\" class=\"modulo_list_claro\">".$ingreso."</td>";
		$this->salida .= "<td width=\"15%\" class=\"modulo_list_oscuro\">EVOLUCION</td>";
          $this->salida .= "<td width=\"40%\" class=\"modulo_list_claro\">".$evolucion."</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td colspan=\"4\" class=\"modulo_list_oscuro\">PRUEBA APLICADA: ".strtoupper($prueba)."</td>";
          $this->salida .= "</tr>";
          $this->salida .= "</table>";
		
          $this->salida .= "<BR><BR><table border=\"0\" width=\"70%\" align=\"center\">";
          $this->salida .= "<tr>";
          $this->salida.= "	<td align=\"center\" colspan=\"2\"><textarea name=\"descripcion\" id=\"descripcion\" class=\"input-text\" cols=\"100%\" rows=\"3\">".$desc."</textarea></td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr>";
          $this->salida .= "<td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"insert\" value=\"INSERTAR\"></td>";
          $this->salida .= "</form>";
        	$accion=ModuloGetURL('app','TranscripcionesPsicologicas','user','FormaBuscarPaciente');
          $this->salida .= "<form name=\"formvolver\" action=\"$accion\" method=\"post\">";
        	$this->salida .= "<td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td>";
          $this->salida .= "</form>";
          $this->salida .= "</tr>";
		$this->salida .= "</table>";
          $this->salida .= ThemeCerrarTabla();
     }
     
     /*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
     function Encabezado()
	{
		$this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"70%\" align=\"center\" >";
		$this->salida .= " <tr class=\"modulo_table_title\">";
		$this->salida .= " <td>EMPRESA</td>";
		$this->salida .= " <td>MODULO</td>";
		$this->salida .= " <td>FECHA</td>";
		$this->salida .= " </tr>";
		$this->salida .= " <tr align=\"center\">";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['TRANS']['NOM_EMP']."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">TRANSCRIPCIONES PSICOLOGICAS</td>";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$this->FormateoFechaLocal(date("Y-m-d"))."</td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		return true;
	}


	function RetornarBarra(){
          if($this->limit>=$this->conteo){
               return '';
          }
          $paso=$_REQUEST['paso'];
          if(empty($paso)){
               $paso=1;
          }
          $vec='';
          foreach($_REQUEST as $v=>$v1)
          {
               if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
               {   $vec[$v]=$v1;   }
          }
          
          $accion=ModuloGetURL('app','BioEstadistica','user','BuscarPaciente',$vec);
          
          $barra=$this->CalcularBarra($paso);
          $numpasos=$this->CalcularNumeroPasos($this->conteo);
          $colspan=1;
          
          $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
          if($paso > 1){
               $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
               $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
               $colspan+=1;
          }
          $barra ++;
          if(($barra+10)<=$numpasos){
               for($i=($barra);$i<($barra+10);$i++){
               if($paso==$i){
                    $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
               }else{
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
               }
               $colspan++;
               }
               $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
               $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
               $colspan+=2;
          }else{
               $diferencia=$numpasos-9;
               if($diferencia<=0){$diferencia=1;}
               for($i=($diferencia);$i<=$numpasos;$i++){
               if($paso==$i){
                    $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
               }else{
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
               }
               $colspan++;
               }
               if($paso!=$numpasos){
               $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
               $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
               $colspan++;
               }else{
               // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
               //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
               }
          }
          if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
          {
               if($numpasos>10)
               {
               $valor=10+3;
               }
               else
               {
               $valor=$numpasos+3;
               }
               $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
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
	          $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table><br>";
          }
     }

  /**
  *
  */
  function CalcularNumeroPasos($conteo){
    $numpaso=ceil($conteo/$this->limit);
    return $numpaso;
  }

  function CalcularBarra($paso){
    $barra=floor($paso/10)*10;
    if(($paso%10)==0){
      $barra=$barra-10;
    }
    return $barra;
  }

  function CalcularOffset($paso){
    $offset=($paso*$this->limit)-$this->limit;
    return $offset;
  }

//----------------------------------------------------------------------------------------------------

}//fin clase

?>

