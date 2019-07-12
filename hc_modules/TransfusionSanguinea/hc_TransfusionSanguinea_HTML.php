<?php
//ESTE ES EL QUE VA A QUEDAR
/**
* Submodulo de Reserva de Sangre.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_TransfusionSanguinea_HTML.php,v 1.9 2006/12/19 21:00:15 jgomez Exp $
*/

class TransfusionSanguinea_HTML extends TransfusionSanguinea
{
    //clzc
	function TransfusionSanguinea_HTML()
	{
		$this->TransfusionSanguinea();//constructor del padre
		return true;
	}

  
  /**
* Esta función retorna los datos de concernientes a la version del submodulo
* @access private
*/

  function GetVersion(){
    $informacion=array(
    'version'=>'1',
    'subversion'=>'0',
    'revision'=>'0',
    'fecha'=>'01/27/2005',
    'autor'=>'LORENA ARAGON G.',
    'descripcion_cambio' => '',
    'requiere_sql' => false,
    'requerimientos_adicionales' => '',
    'version_kernel' => '1.0'
    );
    return $informacion;
  }
//////////////////////
  
  
/**
* SetStyle - Obtiene el estilo del error que va a salir en la pantalla
*
* @return text
* @param $campo texto del error que se va a desplegar
*/

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
* frmForma - funcion que muestra la forma principal del registro de las transfusiones sanguineas
*
* @return boolan
*/

	function frmForma()
	{
	  $pfj=$this->frmPrefijo;
		if(empty($this->titulo)){
			$this->salida= ThemeAbrirTablaSubModulo('DATOS TRANSFUSIONES SANGUINEAS');
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		if($_REQUEST['origen'.$pfj]==1){
			$dis='disabled';
			$read='readonly';
		}
    $accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ConfirmarComponentesTransfusion'));
		$this->salida.="<form name=\"forma\" action=\"$accion1\" method=\"post\">";
		$this->frmConsultaReservas();
    $this->salida.="</form>";
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'InsertarDatosTransfusion','origen'.$pfj=>$_REQUEST['origen'.$pfj]));
		$this->salida .= "<form name=\"frmTransfusiones\" action=\"$accion\" method=\"POST\"><br>\n";
		$this->salida .= "<table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"normal_10\">\n";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</table>\n";
		$this->salida .= "  <table align=\"center\" width=\"95%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"modulo_table_list\">\n";
		$this->salida .= "    <tr class=\"modulo_table_title\">\n";
		$this->salida .= "    <td>DESCRIPCION</td>\n";
		$this->salida .= "    <td colspan=\"2\">NUMERO DE IDENTIFICACION UNIDADES TRANSFUNDIDAS</td>\n";
		$this->salida .= "    </tr>\n";
		$this->salida .= "    <tr>\n";
		$this->salida .= "    <td class=\"label\">NUMERO DE SELLO NACIONAL DE CALIDAD:</td>\n";
		$this->salida .= "    <td> <input $read type=\"text\" class=\"input-text\" name=\"numSello$pfj\" value=\"".$_REQUEST['numSello'.$pfj]."\" size=\"20\" maxlength=\"32\"></td>\n";
		$this->salida .= "    <td>&nbsp;</td>\n";
		$this->salida .= "    </tr>\n";
		$this->salida .= "    <tr>\n";
		$this->salida .= "    <td class=\"label\">NUMERO DE BOLSA:</td>\n";
		$this->salida .= "		<td>";
		$this->salida .= "		<input $read type=\"text\" class=\"input-text\" name=\"cantBolsas$pfj\" value=\"".$_REQUEST['cantBolsas'.$pfj]."\" size=\"20\" maxlength=\"20\">";
		if($_REQUEST['origen'.$pfj]==1){
			$this->salida .= "			<label class=\"label\">No. ALICUOTA<label>";
			if(empty($_REQUEST['numeroAlicuota'.$pfj])){$numeroAlicuotaDes='PRINCIPAL';}else{$numeroAlicuotaDes=$_REQUEST['numeroAlicuota'.$pfj];}
			$this->salida .= "			<input $read type=\"text\" class=\"input-text\" name=\"numeroAlicuotaDes$pfj\" value=\"$numeroAlicuotaDes\" size=\"9\" maxlength=\"9\">";
		}
		$this->salida .= "			</td>\n";
		$this->salida .= "			<td>&nbsp;</td>\n";
		$this->salida .= "		  <input type=\"hidden\" name=\"IngresoBolsaId$pfj\" value=\"".$_REQUEST['IngresoBolsaId'.$pfj]."\">\n";
		$this->salida .= "		  <input type=\"hidden\" name=\"numeroAlicuota$pfj\" value=\"".$_REQUEST['numeroAlicuota'.$pfj]."\">\n";
		$this->salida .= "		  <input type=\"hidden\" name=\"numeroReserva$pfj\" value=\"".$_REQUEST['numeroReserva'.$pfj]."\">\n";
		$this->salida .= "		  </tr>\n";
		$this->salida .= "		  <tr>\n";
		$comp=$this->TraerComponentes();
		$this->salida .= "			<td class=\"label\">COMPONENTE SANGUINEO:</td>\n";
		$this->salida.="        <td><select $dis name=\"componente$pfj\" class=\"select\">";
		for($i=0;$i<sizeof($comp);$i++){
			if($_REQUEST['componente'.$pfj]==$comp[$i][hc_tipo_componente]){
				$select='selected';
			}else{
				$select='';
			}
			$this->salida.="<option $select value=\"".$comp[$i][hc_tipo_componente]."\">".$comp[$i][componente]."</option>";
		}
		$this->salida.="        </select>";
		$this->salida.="        </td></tr>";
		if($_REQUEST['origen'.$pfj]==1){
			$this->salida .= "    <input type=\"hidden\" name=\"componente$pfj\" value=\"".$_REQUEST['componente'.$pfj]."\">\n";
		}
		$this->salida .= "		  <tr>\n";
		$this->salida .= "			<td class=\"label\">FECHA DE VENCIMIENTO:</td>\n";
		$this->salida .= "			<td><input type=\"text\" class=\"input-text\" name=\"fechaVencimiento$pfj\" value=\"".$_REQUEST['fechaVencimiento'.$pfj]."\" size=\"10\" maxlength=\"10\" readonly>";
		if($_REQUEST['origen'.$pfj]!=1){
			$this->salida .= "			".ReturnOpenCalendario('frmTransfusiones','fechaVencimiento'.$pfj,'-')."";
		}
		$this->salida .= "			</td>\n";
		$this->salida .= "			<td>&nbsp;</td>\n";
		$this->salida .= "		  </tr>\n";
		$this->salida .= "		  <tr>\n";
		$this->salida .= "			<td class=\"label\">TIPO SANGUINEO:</td>\n";
		$this->salida .= "			<td>\n";
		$this->salida .= "			<select $dis name=\"tipoSanguineo$pfj\" class=\"select\">\n";
		$gruposSanguineos = $this->GetGruposSanguineos();
		foreach($gruposSanguineos as $key => $value){
			if($_REQUEST['tipoSanguineo'.$pfj] == $value['grupo_sanguineo'].".-.".$value['rh']){
				$selected = "selected";
			}else{
			  $selected = "";
			}
			$this->salida .= "			<option value=\"".$value['grupo_sanguineo'].".-.".$value['rh']."\" $selected>".$value[grupo_sanguineo]."  ".$value[rh]."</option>\n";
		}
		$this->salida .= "				</select>\n";
		$this->salida .= "			  </td>\n";
		if($_REQUEST['origen'.$pfj]==1){
			$this->salida .= "      <input type=\"hidden\" name=\"tipoSanguineo$pfj\" value=\"".$_REQUEST['tipoSanguineo'.$pfj]."\">\n";
		}
		$this->salida .= "			  <td>&nbsp;</td>\n";
		$this->salida .= "		    </tr>\n";
		$this->salida .= "		    <tr>\n";
		$this->salida .= "			  <td class=\"label\">ENTIDAD ORIGEN COMPONENTE:</td>\n";
		$this->salida .= "			  <td><input $read type=\"text\" size=\"45\" maxlength=\"250\" class=\"input-text\" name=\"origenComponente$pfj\" value=\"".$_REQUEST['origenComponente'.$pfj]."\"></td>\n";
		$this->salida .= "		    </tr>\n";
		$this->salida .= "		    <tr>\n";
		$this->salida .= "			  <td class=\"label\">FECHA Y HORA DE INICIO TRANSFUSION:</td>\n";
		if(empty($_REQUEST['fechaInicio'.$pfj])){
			$_REQUEST['fechaInicio'.$pfj]=date("d-m-Y");
		}
		$this->salida .= "			  <td>\n";
		$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fechaInicio$pfj\" value=\"".$_REQUEST['fechaInicio'.$pfj]."\" size=\"10\" maxlength=\"10\" readonly>".ReturnOpenCalendario('frmTransfusiones','fechaInicio'.$pfj,'-')."\n";
		$this->salida .= "				<select name=\"HoraInicio$pfj\" class=\"select\">\n";
		for($i=0; $i<24; $i++){
			$hora = date("H", mktime($i,0,0,date("m"),date("d"),date("Y")));
			if($hora == date("H")){
				$selected = 'selected="yes"';
			}else{
			  $selected = "";
			}
			$this->salida .= "				<option value=\"$hora\" $selected>$hora</option>\n";
		}
		$this->salida .= "				</select><b>&nbsp;:&nbsp;</b>\n";
		$this->salida .= "				<select name=\"MinutoInicio$pfj\" class=\"select\">\n";
		for($i=0; $i<60; $i++){
			$min = date("i",mktime(date("H"),$i,date("s"),date("m"),date("d"),date("Y")));
			if(date("i") == $min){
				$selected = 'selected="yes"';
			}else{
			  $selected = "";
			}
			$this->salida .= "			<option value='".$min.":00' $selected>$min</option>\n";
		}
		$this->salida .= "				</select>\n";
		$this->salida .= "			  </td>\n";
		$this->salida .= "			  <td>&nbsp;</td>\n";
		$this->salida .= "		    </tr>\n";
		$this->salida .= "		    <tr>\n";
		$this->salida .= "			  <td colspan=\"3\" align=\"center\">\n";
		$this->salida .= "				<input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"Save$pfj\" value=\"GUARDAR\">";
		//$this->salida .= "				<input class=\"input-submit\" type=\"submit\" class=\"input-submit\" name=\"Salir$pfj\" value=\"SALIR\">";
		$this->salida .= "			  </td>\n";
		$this->salida .= "		    </tr>\n";
		$this->salida .= "	      </table>\n\n";
		$this->salida .= "        </form>\n";
		$this->ShowTransfusiones();
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

/**
* frmForma - funcion que muestra la forma que consulta las transfusiones realizadas en el mismo ingreso del paciente
*
* @return boolean
*/

//pendiente cuadrarlo
	function frmConsulta(){
    $pfj=$this->frmPrefijo;
    $transfusionesPaciente = $this->GetTransfusiones();
    if(empty($contador)){
      $contador = sizeof($transfusionesPaciente);
    }
    if($transfusionesPaciente){
    $this->salida .= "<form name='frmShowTransfusiones' action='".$action."' method='POST'><br>\n";
    $this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
    $this->salida .= "		<tr class=\"modulo_table_title\">\n";
    $this->salida .= "			<td colspan=\"9\">BOLSAS TRANSFUNDIDAS</td>\n";
    $this->salida .= "		</tr>\n";
    $this->salida .= "		<tr class=\"modulo_table_title\">\n";
    $this->salida .= "			<td>FECHA INICIO</br>TRANSFUSION</td>\n";
    $this->salida .= "			<td>BOLSA<br>- ALICUOTA -</td>\n";
    $this->salida .= "			<td># SELLO<BR>CALIDAD</td>\n";
    $this->salida .= "			<td>FECHA DE<br>VENCIMIENTO</td>\n";
    $this->salida .= "			<td>COMPONENTE</td>\n";
    $this->salida .= "			<td>G.S.</td>\n";
    $this->salida .= "			<td>RH</td>\n";    
    $this->salida .= "			<td>FECHA FINAL</br>TRANSFUSION</td>\n";
    $this->salida .= "			<td>USUARIO</td>\n";
    $this->salida .= "		</tr>\n";
    $cont=1;
    $indice=0;
    while($cont <= sizeof($transfusionesPaciente) && $cont <= $contador){//echo "<br>cont=> $cont cantador=> $contador";
      //echo "<br><br>con=> $cont sizeof(transfusionesPaciente)=>".sizeof($transfusionesPaciente)." contador=> $contador";
      list($fecha,$hora) = explode(" ",$transfusionesPaciente[$cont-1][fecha]);//substr(,0,10);
      if($numero%2){$estilo='hc_submodulo_list_oscuro';}else{$estilo='hc_submodulo_list_claro';}
      $this->salida .= "		<tr class=\"$estilo\"  align=\"center\" valign=\"middle\">\n";
      if($fecha == date("Y-m-d")) {
        $fecha = "HOY $hora";
      }elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
        $fecha = "AYER $hora";
      }else{
        (list($ano,$mes,$dia)=explode('-',$fecha));
        $FechaConver=mktime(0,0,0,$mes,$dia,$ano);
        $fecha = strftime("%b %d de %Y",$FechaConver);
      }
      $this->salida .= "			<td>".$fecha."</td>\n";
      if($transfusionesPaciente[$cont-1][numero_alicuota]==0){$alicuota='PRINCIPAL';}else{$alicuota=$transfusionesPaciente[$cont-1][numero_alicuota];	}
      $this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_bolsas]."<br>- ".$alicuota." -</td>\n";
      $this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_sello_calidad]."</td>\n";
      (list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_vencimiento]));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      $FechaConver=mktime(0,0,0,$mes,$dia,$ano);
      $this->salida .= "			<td>".strftime("%b %d de %Y",$FechaConver)."</td>\n";
      $this->salida .= "			<td>".$transfusionesPaciente[$cont-1][componente]."</td>\n";
      $this->salida .= "			<td>".$transfusionesPaciente[$cont-1][grupo_sanguineo]."</td>\n";
      $this->salida .= "			<td>".$transfusionesPaciente[$cont-1][rh]."</td>\n";      
      $this->salida .= "			<td width=\"34%\" nowrap valign='middle'>";
      if(!empty($transfusionesPaciente[$cont-1][fecha_final])){
        (list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_final]));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($hh,$mm)=explode(':',$hora));
        $FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
        $this->salida .="    </noBR>".strftime("%b %d de %Y %H:%M",$FechaConver)."\n";
      }else{
        $this->salida .= "			&nbsp;\n";
      }
      $this->salida .= "			</td>\n";
      $this->salida .= "			<td>\n";
      //aqui colocamos lo del usuario...si es el mismo usuario y no ha pasado
      //el dia ..colocamos el link de modificar!
      $nom=$this->GetDatosUsuarioSistema($transfusionesPaciente[$cont-1][usuario]);
      $this->salida .= "			".$nom[0][usuario]."\n";
      $this->salida .= "			</td>\n";
      $this->salida .= "		</tr>\n";
      $cont++;
      $indice++;
    }
    $this->salida .= "		</tr>\n";
    $this->salida .= "	</table>\n\n";
    $this->salida .= "</form>\n";
    }
		return true;

	}


/**
* frmForma - funcion que muestra la forma que consulta las transfusiones realizadas en el mismo ingreso de paciente
*
* @return boolean
*/

	function frmHistoria(){
    $pfj=$this->frmPrefijo;
    $transfusionesPaciente = $this->GetTransfusiones();
    if(empty($contador)){
      $contador = sizeof($transfusionesPaciente);
    }
    if($transfusionesPaciente){
    $salida .= "<table align=\"center\" width=\"100%\" border=\"1\">\n";
    $salida .= "		<tr class=\"modulo_table_title\">\n";
    $salida .= "			<td colspan=\"9\" align=\"center\">BOLSAS TRANSFUNDIDAS</td>\n";
    $salida .= "		</tr>\n";
    $salida .= "		<tr class=\"modulo_table_title\">\n";
    $salida .= "			<td>FECHA INICIO</br>TRANSFUSION</td>\n";
    $salida .= "			<td>BOLSA<br>- ALICUOTA -</td>\n";
    $salida .= "			<td># SELLO<BR>CALIDAD</td>\n";
    $salida .= "			<td>FECHA DE<br>VENCIMIENTO</td>\n";
    $salida .= "			<td>COMPONENTE</td>\n";
    $salida .= "			<td>G.S.</td>\n";
    $salida .= "			<td>RH</td>\n";    
    $salida .= "			<td>FECHA FINAL</br>TRANSFUSION</td>\n";
    $salida .= "			<td>USUARIO</td>\n";
    $salida .= "		</tr>\n";
    $cont=1;
    $indice=0;
    while($cont <= sizeof($transfusionesPaciente) && $cont <= $contador){//echo "<br>cont=> $cont cantador=> $contador";
      //echo "<br><br>con=> $cont sizeof(transfusionesPaciente)=>".sizeof($transfusionesPaciente)." contador=> $contador";
      list($fecha,$hora) = explode(" ",$transfusionesPaciente[$cont-1][fecha]);//substr(,0,10);
      if($numero%2){$estilo='hc_submodulo_list_oscuro';}else{$estilo='hc_submodulo_list_claro';}
      $salida .= "		<tr class=\"$estilo\"  align=\"center\" valign=\"middle\">\n";
      if($fecha == date("Y-m-d")) {
        $fecha = "HOY $hora";
      }elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
        $fecha = "AYER $hora";
      }else{
        (list($ano,$mes,$dia)=explode('-',$fecha));
        $FechaConver=mktime(0,0,0,$mes,$dia,$ano);
        $fecha = strftime("%b %d de %Y",$FechaConver);
      }
      $salida .= "			<td>".$fecha."</td>\n";
      if($transfusionesPaciente[$cont-1][numero_alicuota]==0){$alicuota='PRINCIPAL';}else{$alicuota=$transfusionesPaciente[$cont-1][numero_alicuota];	}
      $salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_bolsas]."<br>- ".$alicuota." -</td>\n";
      $salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_sello_calidad]."</td>\n";
      (list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_vencimiento]));
      (list($ano,$mes,$dia)=explode('-',$fecha));
      $FechaConver=mktime(0,0,0,$mes,$dia,$ano);
      $salida .= "			<td>".strftime("%b %d de %Y",$FechaConver)."</td>\n";
      $salida .= "			<td>".$transfusionesPaciente[$cont-1][componente]."</td>\n";
      $salida .= "			<td>".$transfusionesPaciente[$cont-1][grupo_sanguineo]."</td>\n";
      $salida .= "			<td>".$transfusionesPaciente[$cont-1][rh]."</td>\n";      
      $salida .= "			<td width=\"34%\" nowrap valign='middle'>";
      if(!empty($transfusionesPaciente[$cont-1][fecha_final])){
        (list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_final]));
        (list($ano,$mes,$dia)=explode('-',$fecha));
        (list($hh,$mm)=explode(':',$hora));
        $FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
        $salida .="    </noBR>".strftime("%b %d de %Y %H:%M",$FechaConver)."\n";
      }else{
        $salida .= "			&nbsp;\n";
      }
      $salida .= "			</td>\n";
      $salida .= "			<td>\n";
      //aqui colocamos lo del usuario...si es el mismo usuario y no ha pasado
      //el dia ..colocamos el link de modificar!
      $nom=$this->GetDatosUsuarioSistema($transfusionesPaciente[$cont-1][usuario]);
      $salida .= "			".$nom[0][usuario]."\n";
      $salida .= "			</td>\n";
      $salida .= "		</tr>\n";
      $cont++;
      $indice++;
    }
    $salida .= "		</tr>\n";
    $salida .= "	</table><br>\n\n";
    }
		return $salida;
	}

/**
* ShowTransfusiones - Muestra los registros de transfusiones que tiene el paciente y permite ingresar la fecha
* finalizacion de la trasnfusion y reacciones adversase que presente el paciente
*
* @return boolean
*/

	function ShowTransfusiones(){
		//echo "<br><br>ShowTransfusiones<br>estacion<br>"; print_r($estacion);	echo "<br><br>datos estacion<br>"; print_r($datos_estacion);
		$pfj=$this->frmPrefijo;
		$transfusionesPaciente = $this->GetTransfusiones();
		if(!$transfusionesPaciente){
			return false;
		}elseif($transfusionesPaciente != "ShowMensaje"){
			if(empty($contador)){
				$contador = sizeof($transfusionesPaciente);
			}
			//$action = ModuloGetURL('app','EstacionE_ControlPacientes','user','InsertarFechaFinTransfusion',array("datos_estacion"=>$datos_estacion,"estacion"=>$estacion));
			//$this->salida .= "<form name='frmShowTransfusiones' action='".$action."' method='POST'><br>\n";
			$this->salida .= "<br><table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td colspan=\"10\">BOLSAS TRANSFUNDIDAS</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "			<td>FECHA INICIO</br>TRANSFUSION</td>\n";
			$this->salida .= "			<td>BOLSA<br>-ALICUOTA-</td>\n";
			$this->salida .= "			<td># SELLO<BR>CALIDAD</td>\n";
			$this->salida .= "			<td>FECHA DE<br>VENCIMIENTO</td>\n";
			$this->salida .= "			<td>COMPONENTE</td>\n";
			$this->salida .= "			<td>G.S.</td>\n";
			$this->salida .= "			<td>RH</td>\n";			
			$this->salida .= "			<td>FECHA FINAL<br>TRANSFUSION</td>\n";
			$this->salida .= "			<td>REACCIONES<BR>ADVERSAS</td>\n";
			$this->salida .= "			<td>USUARIO</td>\n";
			$this->salida .= "		</tr>\n";
			$cont=1;
			$indice=0;
			while($cont <= sizeof($transfusionesPaciente) && $cont <= $contador){//echo "<br>cont=> $cont cantador=> $contador";
				//echo "<br><br>con=> $cont sizeof(transfusionesPaciente)=>".sizeof($transfusionesPaciente)." contador=> $contador";
				list($fecha,$hora) = explode(" ",$transfusionesPaciente[$cont-1][fecha]);//substr(,0,10);
				if($numero%2){$estilo='hc_submodulo_list_oscuro';}else{$estilo='hc_submodulo_list_claro';}
				$this->salida .= "		<tr class=\"$estilo\"  align=\"center\" valign=\"middle\">\n";
				if($fecha == date("Y-m-d")) {
					$fecha = "HOY $hora";
				}elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
					$fecha = "AYER $hora";
				}else{
					(list($ano,$mes,$dia)=explode('-',$fecha));
					$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
					$fecha = strftime("%b %d de %Y",$FechaConver);
				}
				$this->salida .= "			<td>".$fecha."</td>\n";
				if($transfusionesPaciente[$cont-1][numero_alicuota]==0){$alicuota='PRINCIPAL';}else{$alicuota=$transfusionesPaciente[$cont-1][numero_alicuota];	}
				$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_bolsas]."<br>- ".$alicuota." -</td>\n";
				$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][numero_sello_calidad]."</td>\n";
				(list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_vencimiento]));
				(list($ano,$mes,$dia)=explode('-',$fecha));
				$FechaConver=mktime(0,0,0,$mes,$dia,$ano);
				$this->salida .= "			<td>".strftime("%b %d de %Y",$FechaConver)."</td>\n";
				$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][componente]."</td>\n";
				$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][grupo_sanguineo]."</td>\n";
				$this->salida .= "			<td>".$transfusionesPaciente[$cont-1][rh]."</td>\n";				
				$this->salida .= "			<td width=\"34%\" nowrap valign='middle'><noBR>\n";
				if(empty($transfusionesPaciente[$cont-1][fecha_final])){
					$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'GuardarFechaFinalTransfusion'));
					$this->salida .= "			<form name=\"FormaFechaFin".$indice."".$pfj."\" action=\"$accion\" method=\"post\">\n";
					$this->salida .= "				<input type=\"text\" class=\"input-text\" name=\"fechaFin".$indice."".$pfj."\" value=\"".$_REQUEST['fechaFin'.$indice.$pfj]."\" size=\"10\" maxlength=\"10\" readonly=\"yes\">".ReturnOpenCalendario('FormaFechaFin'.$indice.$pfj,'fechaFin'.$indice.$pfj,'-')."\n";
					$this->salida .= "				<select name=\"Horas".$indice."".$pfj."\" class=\"select\">\n";
					for($i=0; $i<24; $i++){
						$hora = date("H", mktime($i,0,0,date("m"),date("d"),date("Y")));
						if((empty($_REQUEST['Horas'.$indice.$pfj]) && $hora == date("H")) || $hora == $_REQUEST['Horas'.$indice.$pfj]){
							$selected = 'selected="yes"';
						}else{
						  $selected = "";
						}
						$this->salida .= "				<option value=\"$hora\" $selected>$hora</option>\n";
					}
					$this->salida .= "				</select>\n";
					$this->salida .= "				<select name=\"Minutos".$indice."".$pfj."\" class=\"select\">\n";
					for($i=0; $i<60; $i++){
						$min = date("i",mktime(date("H"),$i,date("s"),date("m"),date("d"),date("Y")));
						if((empty($_REQUEST['Minutos'.$indice.$pfj]) && (date("i") == $min)) || $min==$_REQUEST['Minutos'.$indice.$pfj]){
							$selected = 'selected="yes"';
						}else{
						  $selected = "";
						}
						$this->salida .= "				<option value=\"".$min.":".date("s")."\" $selected>$min</option>\n";
					}
					$this->salida .= "				</select>\n";
					$this->salida .= "				<input type=\"hidden\" name=\"indice$pfj\" value=\"".$indice."\">\n";
					$this->salida .= "				<input type=\"hidden\" name=\"fechaInicio".$indice."".$pfj."\" value=\"".$transfusionesPaciente[$cont-1][fecha]."\">\n";
					$this->salida .= "				<input type=\"image\" name=\"submit\" src=\"".GetThemePath()."/images/EstacionEnfermeria/guarda.png\" border=\"0\" alt=\"GUARDAR\">\n";//<input type='submit' name='submit' value='s'>
					$this->salida .= "			</form>\n";
				}else{
					(list($fecha,$hora)=explode(' ',$transfusionesPaciente[$cont-1][fecha_final]));
					(list($ano,$mes,$dia)=explode('-',$fecha));
					(list($hh,$mm)=explode(':',$hora));
					$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
					$this->salida .=" ".strftime("%b %d de %Y %H:%M",$FechaConver)."\n";
				}
				$this->salida .= "			</noBR></td>\n";
				$this->salida .= "			<td>\n";
				if(empty($transfusionesPaciente[$cont-1][reaccion_adversa])){
				$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmInsertarReaccionAdversa','datos'.$pfj=>$transfusionesPaciente[$cont-1]));
				$this->salida .= "			<a href=\"".$href."\">INSERTAR</a>\n";
				}
				else{
				  $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmConsultarReaccionAdversa','datos'.$pfj=>$transfusionesPaciente[$cont-1],"reaccion_adversa".$pfj=>$transfusionesPaciente[$cont-1][reaccion_adversa]));
				  $this->salida .= "			<a href=\"".$href."\"><img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\" title=\"Consultar Reaccion Transfusional\"><a></a>\n";
				}
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td>\n";
				//aqui colocamos lo del usuario...si es el mismo usuario y no ha pasado
				//el dia ..colocamos el link de modificar!
				$nom=$this->GetDatosUsuarioSistema($transfusionesPaciente[$cont-1][usuario]);
				$this->salida .= "			".$nom[0][usuario]."\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$cont++;
				$indice++;
			}
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n\n";
			//$this->salida .= "</form>\n";
			/*if ($contador<sizeof($transfusionesPaciente)) {//FrmTransfusiones($estacion,$datos_estacion)
				$href = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("estacion"=>$estacion,"datos_estacion"=>$datos_estacion,"cantidad"=>1));
				$this->salida .= "<div class=\"normal_10\" align=\"center\"><br><a href=\"".$href."\">Ver M&aacute;s</a><br>";
			}*/
			return true;
		}
	}//fin ShowTransfusiones


/**
* FrmInsertarReaccionAdversa - Formulario para el ingreso de la reaccion adversa del paciente en una treasnfusion x
*
* @return boolean
* @return $datos Datos del paciente
*/
	function FrmInsertarReaccionAdversa($datos)
	{//echo "<br>ingreso=> $ingreso"; echo "<br>datos=> ";print_r($datos); echo "<br><br>FrmInsertarReaccionAdversa<br>estacion<br>"; print_r($estacion);	echo "<br><br>datos estacion<br>"; print_r($datos_estacion);
	  $pfj=$this->frmPrefijo;
		$GS = $this->GetGrupoSanguineoPaciente();
		if(!$GS){
			return false;
		}
		if(empty($this->titulo)){
			$this->salida= ThemeAbrirTablaSubModulo("EDICI&Oacute;N DE LA PLANTILLA");
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		$this->salida.="        <script>";
		$this->salida.="        function desabilitar(frm,valor){";
		$this->salida.="          if(valor==true){";
    $this->salida.="            frm.liquidos$pfj.disabled=false;";
		$this->salida.="          }else{";
		$this->salida.="            frm.liquidos$pfj.disabled=true;";
		$this->salida.="          }";
		$this->salida.="        }";
		$this->salida.="        function CargarForma(frm,valor){";
    $this->salida.="          frm.codigoParaEliminar$pfj.value=valor;";
		$this->salida.="          frm.submit();";
		$this->salida.="        }";
    $this->salida.="        </script>";
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmInsertarReaccionAdversa','datos'.$pfj=>$datos));
		$this->salida .= "<form name=\"InsertarReaccionAdversa$pfj\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= "  <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
    $this->salida .= "	<tr><td colspan=\"4\">";
		$this->salida .=   $this->SetStyle("MensajeError",1);
    $this->salida .= "	</td></tr>";
		$this->salida .= "		<tr><td class=\"modulo_table_title\" colspan=\"4\">\n";
		if($GS === "ShowMensaje"){
		  $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmIngresarHemoclasificacionPaciente','datos'.$pfj=>$datos));
			$this->salida .= "			<a href=\"$href\">ingresar G.S. y R.H.</a>\n";
		}else{
			$this->salida .= "			HEMOCLASIFICACION: ".$GS['grupo_sanguineo']." ".$GS['rh']."</td>\n";
		}
    $this->salida .= "		</td></tr>";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td class=\"modulo_table_title\">FECHA</td>\n";
		$this->salida .= "			<td class=\"modulo_table_title\">BOLSAS</td>\n";
		$this->salida .= "			<td class=\"modulo_table_title\"># SELLO CALIDAD</td>\n";
		$this->salida .= "			<td class=\"modulo_table_title\">FECHA VENCIMIENTO</td>\n";
		$this->salida .= "		</tr>\n";
		list($fecha,$hora) = explode(" ",$datos[fecha]);//substr(,0,10);
		$this->salida .= "		<tr align='center'>\n";
		if($fecha == date("Y-m-d")) {
			$fecha = "HOY $hora";
		}elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
			$fecha = "AYER $hora";
		}else{
			$fecha = $fecha;
		}
		(list($fecha,$hora)=explode(' ',$datos[fecha]));
		(list($ano,$mes,$dia)=explode('-',$fecha));
		(list($hh,$mm)=explode(':',$hora));
		$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".strftime("%b %d de %Y %H:%M",$FechaConver)."</td>\n";
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".$datos[numero_bolsas]."</td>\n";
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".$datos[numero_sello_calidad]."</td>\n";
		(list($fecha,$hora)=explode(' ',$datos[fecha_vencimiento]));
		(list($ano,$mes,$dia)=explode('-',$fecha));
		(list($hh,$mm)=explode(':',$hora));
		$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".strftime("%b %d de %Y",$FechaConver)."</td>\n";

		$this->salida .= "</tr>\n";
		$this->salida .= "<br></table>\n";
		list($fecha,$hora) = explode(" ",$datos[fecha]);
    (list($ano,$mes,$dia)=explode('-',$fecha));
		$this->salida .= "  <BR><table align=\"center\" width=\"90%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
    $this->salida .= "		<tr><td class=\"modulo_table_title\" colspan=\"3\">DATOS REACCION TRANSFUSIONAL</td></tr>\n";
    $this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td width=\"25%\" class=\"".$this->SetStyle("fecha_inicio_reaccion".$pfj)."\">FECHA INICIO REACCION</td>";
		if(empty($_REQUEST['fecha_inicio_reaccion'.$pfj])){
      $_REQUEST['fecha_inicio_reaccion'.$pfj]=$dia.'-'.$mes.'-'.$ano;
		}
		$this->salida .="     <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fecha_inicio_reaccion'.$pfj]."\" name=\"fecha_inicio_reaccion$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('InsertarReaccionAdversa'.$pfj,'fecha_inicio_reaccion'.$pfj,'-')."</td>" ;
		$this->salida .="     <td align=\"left\">";
		$this->salida.="        <table>";
		$this->salida.="          <td class=".$this->SetStyle("hora_inicio_reaccion".$pfj)." align=\"left\">HORA (hh:mm)</td>";
	  $this->salida.="          <td>";
		$this->salida.="          <select size=\"1\" name=\"hora_inicio_reaccion$pfj\" class=\"select\">";
		$this->salida.="          <option value = -1> Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora_inicio_reaccion'.$pfj]==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora_inicio_reaccion'.$pfj]==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
		$this->salida.="          <td>";
    $this->salida.="          <select size=\"1\"  name=\"minutos_inicio_reaccion$pfj\" class=\"select\">";
	  $this->salida.="          <option value = -1> Minutos </option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos_inicio_reaccion'.$pfj]==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos_inicio_reaccion'.$pfj]==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
    $this->salida.="        </tr>";
		$this->salida.="        </table>";
    $this->salida.="        </td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td width=\"25%\" class=\"".$this->SetStyle("fecha_suspension_transfusion".$pfj)."\">FECHA SUSPENSION TRANSFUSION</td>";
		if(empty($_REQUEST['fecha_suspension_transfusion'.$pfj])){
      $_REQUEST['fecha_suspension_transfusion'.$pfj]=$dia.'-'.$mes.'-'.$ano;
		}
		$this->salida .="     <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fecha_suspension_transfusion'.$pfj]."\" name=\"fecha_suspension_transfusion$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('InsertarReaccionAdversa'.$pfj,'fecha_suspension_transfusion'.$pfj,'-')."</td>" ;
		$this->salida .="     <td align=\"left\">";
		$this->salida.="        <table>";
		$this->salida.="          <td class=".$this->SetStyle("hora_suspension_transfusion".$pfj)." align=\"left\">HORA (hh:mm)</td>";
	  $this->salida.="          <td>";
		$this->salida.="          <select size=\"1\" name=\"hora_suspension_transfusion$pfj\" class=\"select\">";
		$this->salida.="          <option value = -1> Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora_suspension_transfusion'.$pfj]==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora_suspension_transfusion'.$pfj]==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
		$this->salida.="          <td>";
    $this->salida.="          <select size=\"1\"  name=\"minutos_suspension_transfusion$pfj\" class=\"select\">";
	  $this->salida.="          <option value = -1> Minutos </option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos_suspension_transfusion'.$pfj]==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos_suspension_transfusion'.$pfj]==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
    $this->salida.="        </tr>";
		$this->salida.="        </table>";
    $this->salida.="        </td>";
    $this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td width=\"25%\" class=\"".$this->SetStyle("fecha_notificacion_medico".$pfj)."\">FECHA NOTIFICACION MEDICO</td>";
		if(empty($_REQUEST['fecha_notificacion_medico'.$pfj])){
      $_REQUEST['fecha_notificacion_medico'.$pfj]=$dia.'-'.$mes.'-'.$ano;
		}
		$this->salida .="     <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fecha_notificacion_medico'.$pfj]."\" name=\"fecha_notificacion_medico$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('InsertarReaccionAdversa'.$pfj,'fecha_notificacion_medico'.$pfj,'-')."</td>" ;
		$this->salida .="     <td align=\"left\">";
		$this->salida.="        <table>";
		$this->salida.="          <td class=".$this->SetStyle("hora_notificacion_medico".$pfj)." align=\"left\">HORA (hh:mm)</td>";
	  $this->salida.="          <td>";
		$this->salida.="          <select size=\"1\" name=\"hora_notificacion_medico$pfj\" class=\"select\">";
		$this->salida.="          <option value = -1> Hora </option>";
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['hora_notificacion_medico'.$pfj]==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['hora_notificacion_medico'.$pfj]==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
		$this->salida.="          <td>";
    $this->salida.="          <select size=\"1\"  name=\"minutos_notificacion_medico$pfj\" class=\"select\">";
	  $this->salida.="          <option value = -1> Minutos </option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutos_notificacion_medico'.$pfj]==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutos_notificacion_medico'.$pfj]==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
    $this->salida.="        </tr>";
		$this->salida.="        </table>";
    $this->salida.="        </td>";
    $this->salida .= "		</tr>";
		$this->salida.="      <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"3\">";
		$this->salida.="        <table width=\"98%\" border=\"0\" align=\"center\">";
		$this->salida.="        <tr class=\"modulo_list_oscuro\"><td class=\"label\" colspan=\"3\">DIAGNOSTICOS</td></tr>";
    $this->salida.="        <tr class=\"modulo_list_oscuro\">";
		$this->salida.="        <td align=\"center\" width=\"20%\" class=\"label\">CODIGO</td>";
		$this->salida.="        <td colspan=\"2\" align=\"center\" width=\"80%\" class=\"label\">DESCRIPCION</td>";
		$this->salida.="        </tr>";
		$this->salida.="        <input type=\"hidden\" name=\"codigoParaEliminar$pfj\">";
		foreach($_SESSION['REACCIONES_TRANSFUSIONALES'.$pfj]['DIAGNOSTICOS'] as $codigo=>$descripcion){
      $this->salida.="        <tr class=\"modulo_list_oscuro\">";
			$this->salida.="        <td width=\"20%\">$codigo</td>";
			$this->salida.="        <td width=\"75%\">$descripcion</td>";
      //$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'SeleccionDiagnostico',"EliminarDiagnostico".$pfj=>1,"codigoElimina".$pfj=>$codigo,"datos".$pfj=>$datos));
			$this->salida.="        <td width=\"5%\"><a href=\"javascript:CargarForma(document.InsertarReaccionAdversa$pfj,'$codigo')\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
			$this->salida.="        </tr>";
		}
		$this->salida.="        <tr class=\"modulo_list_oscuro\">";
		$this->salida.="        <td align=\"center\" colspan=\"3\" width=\"100%\"><input type=\"submit\" class=\"input-submit\" name=\"BUSCARDIAGNOSTICO$pfj\" value=\"BUSCAR\"></td>";
    $this->salida.="        </tr>";
    $this->salida.="        </table>";
		$this->salida.="        </td>";
		$this->salida .= "		</tr>";
    $this->salida.="      <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"3\">";
		$this->salida.="        <table aling=\"center\">";
		if($_REQUEST['uso_liquidos_endovenosos'.$pfj]==1 || empty($_REQUEST['uso_liquidos_endovenosos'.$pfj])){
      $des='checked';
		}
    $this->salida.="        <tr><td class=\"label\">USO LIQUIDOS ENDOVENOSOS&nbsp&nbsp;<input onclick=\"desabilitar(this.form,this.checked)\" type=\"checkbox\" $des value=\"1\" name=\"uso_liquidos_endovenosos$pfj\"></td></tr>";
		$this->salida.="        <tr><td><textarea name=\"liquidos$pfj\" class=\"textarea\" cols=\"100\" rows=\"5\">".$_REQUEST['liquidos'.$pfj]."</textarea></td></tr>";
    $this->salida.="        </table>";
		$this->salida.="        </td>";
		$this->salida .= "		</tr>";
		$this->salida.="      <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"3\">";
		$this->salida.="        <table aling=\"center\">";
    $this->salida.="        <tr><td class=\"".$this->SetStyle("reaccionAdversa".$pfj)."\">SIGNOS Y SINTOMAS DE LA REACCION</td></tr>";
		$this->salida.="        <tr><td><textarea name=\"reaccionAdversa$pfj\" class=\"textarea\" cols=\"100\" rows=\"5\">".$_REQUEST['reaccionAdversa'.$pfj]."</textarea></td></tr>";
    $this->salida.="        </table>";
		$this->salida.="        </td>";
		$this->salida .= "		</tr>";
		$this->salida.="      <tr class=\"modulo_list_claro\"><td colspan=\"3\">";
		$this->salida .= "    <table align=\"center\" width=\"30%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
		$this->salida.="      <tr class=\"modulo_table_title\" >";
		$this->salida.="      <td class=\"".$this->SetStyle("sel".$pfj)."\" colspan='6'>SELECCIONE REACCION</td></tr>";
		$this->salida .= "    </tr>\n";
		$this->salida .= "    <tr class='hc_submodulo_list_claro'>\n";
		if($_REQUEST['sel'.$pfj]=='1'){
      $che='checked';
		}elseif($_REQUEST['sel'.$pfj]=='2'){
      $che1='checked';
		}elseif($_REQUEST['sel'.$pfj]=='3'){
      $che2='checked';
		}
		$this->salida.="      <td>Positivo <img src=\"".GetThemePath()."/images/activo.gif\"  width=\"10\" height=\"10\">&nbsp;</td><td width=\"2%\"><input type=\"radio\" name=\"sel$pfj\" value=\"1\" $che></td>";
		$this->salida.="      <td>&nbsp;&nbsp;Neutral<img src=\"".GetThemePath()."/images/inactivoip.gif\"  width=\"10\" height='10'>&nbsp;</td><td width=\"2%\"><input type=\"radio\"  name=\"sel$pfj\" value=\"3\" $che2></td>";
		$this->salida.="      <td>&nbsp;&nbsp;Negativo<img src=\"".GetThemePath()."/images/inactivo.gif\"  width=\"10\" height='10'>&nbsp;</td><td width=\"2%\"><input type=\"radio\"  name=\"sel$pfj\" value=\"2\" $che1></td>";
		$this->salida.="      </tr>";
		$this->salida.="      </table><br>";
		$this->salida .= "		</td></tr>";

		/*
    $this->salida .= "			<td class=\"hc_submodulo_list_claro\">";
		$reaccion=$this->GetNotasReaccionAdversasPaciente($datos[fecha]);
		if($reaccion != 'ShowMensage'){
			//$this->salida .= "		<td><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;<label class='label_mark'>".$estaciones[$i][1]."</label></td>\n";
			$this->salida .= "<br><table align=\"center\" width=\"90%\"  bordercolor='gray' border=\"1\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td></td>\n";
			$this->salida .= "			<td>HORA</td>\n";
			$this->salida .= "			<td>OBSERVACION</td>\n";
			$this->salida .= "			<td>USUARIO</td>\n";
			$this->salida .= "		</tr>\n";
			for($j=0;$j<sizeof($reaccion);$j++){
				if($j % 2)  $estilo = "hc_submodulo_list_oscuro";  else $estilo = "hc_submodulo_list_claro";
				$this->salida .= "		<tr class='$estilo'>\n";
				if($reaccion[$j][sw_reaccion]==1){
					$img="activo.gif";
				}elseif($reaccion[$j][sw_reaccion]==2){
					$img='inactivo.gif';
				}elseif($reaccion[$j][sw_reaccion]==3){
					$img='inactivoip.gif';
				}
				$this->salida .= "			<td width=\"5%\" ><img src=\"".GetThemePath()."/images/$img\"  width='12' height='12'></td>\n";
				unset($img);
				$hora=explode(".",$reaccion[$j][fecha_registro]);
				$this->salida .= "			<td width=\"20%\"><label class='label_mark'>".$hora[0]."</label></td>\n";
				$this->salida .= "			<td width=\"70%\">".$reaccion[$j][observacion]."</td>\n";
				$this->salida .= "			<td width=\"10%\">\n";
				$nom=$this->GetDatosUsuarioSistema($reaccion[$j][usuario_id]);
				$this->salida .= "			".$nom[0][usuario]."\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
			}
			$this->salida .= "	</table><br>\n";
		}
		$this->salida .= "<textarea name=\"reaccionAdversa$pfj\" class=\"textarea\" cols=\"100\" rows=\"5\">".$_REQUEST['reaccionAdversa'.$pfj]."</textarea>";
		$this->salida .= "</td>\n";
		*/

    $this->salida .= "  </table><br>";
		$this->salida .= "<div class=\"normal_10\" align=\"center\"><br><input type=\"submit\" class=\"input-submit\" name=\"SaveReaccion$pfj\" value=\"GUARDAR\">";
    $this->salida .= "</form>\n";
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>''));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$action."'>Volver al listado de transfusiones</a><br>\n";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}//

	function FrmConsultarReaccionAdversa($datos,$reaccion_adversa){
    $pfj=$this->frmPrefijo;
		$GS = $this->GetGrupoSanguineoPaciente();
		$DatosReaccion=$this->GetDatosReaccionTransfusional($reaccion_adversa);
		$DiagnosticosReaccion=$this->GetDiagnosticosReaccionTransfusional($reaccion_adversa);
		if(!$GS){
			return false;
		}
		if(empty($this->titulo)){
			$this->salida= ThemeAbrirTablaSubModulo("CONSULTA DE LA REACCION TRANSFUSIONAL");
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmInsertarReaccionAdversa','datos'.$pfj=>$datos));
		$this->salida .= "<form name=\"InsertarReaccionAdversa$pfj\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= "  <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";
    $this->salida .= "	<tr><td colspan=\"4\">";
		$this->salida .=   $this->SetStyle("MensajeError",1);
    $this->salida .= "	</td></tr>";
		$this->salida .= "		<tr><td class=\"modulo_table_title\" colspan=\"4\">\n";
		if($GS === "ShowMensaje"){
		  $href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmIngresarHemoclasificacionPaciente','datos'.$pfj=>$datos));
			$this->salida .= "			<a href=\"$href\">ingresar G.S. y R.H.</a>\n";
		}else{
			$this->salida .= "			HEMOCLASIFICACION: ".$GS['grupo_sanguineo']." ".$GS['rh']."</td>\n";
		}
    $this->salida .= "		</td></tr>";
		$this->salida .= "		<tr>\n";
		$this->salida .= "			<td class=\"modulo_table_title\">FECHA</td>\n";
		$this->salida .= "			<td class=\"modulo_table_title\">BOLSAS</td>\n";
		$this->salida .= "			<td class=\"modulo_table_title\"># SELLO CALIDAD</td>\n";
		$this->salida .= "			<td class=\"modulo_table_title\">FECHA VENCIMIENTO</td>\n";
		$this->salida .= "		</tr>\n";
		list($fecha,$hora) = explode(" ",$datos[fecha]);//substr(,0,10);
		$this->salida .= "		<tr align='center'>\n";
		if($fecha == date("Y-m-d")){
			$fecha = "HOY $hora";
		}elseif($fecha == date("Y-m-d", mktime(0,0,0,date("m"), (date("d")-1), date("Y")))){
			$fecha = "AYER $hora";
		}else{
			$fecha = $fecha;
		}
		(list($fecha,$hora)=explode(' ',$datos[fecha]));
		(list($ano,$mes,$dia)=explode('-',$fecha));
		(list($hh,$mm)=explode(':',$hora));
		$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".strftime("%b %d de %Y %H:%M",$FechaConver)."</td>\n";
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".$datos[numero_bolsas]."</td>\n";
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".$datos[numero_sello_calidad]."</td>\n";
		(list($fecha,$hora)=explode(' ',$datos[fecha_vencimiento]));
		(list($ano,$mes,$dia)=explode('-',$fecha));
		(list($hh,$mm)=explode(':',$hora));
		$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
		$this->salida .= "			<td class=\"hc_submodulo_list_claro\">".strftime("%b %d de %Y",$FechaConver)."</td>\n";
		$this->salida .= "      </tr>\n";
    $this->salida .= "     </table><BR>";
    $this->salida .= "     <table align=\"center\" width=\"80%\" border=\"0\" class=\"normal_10\">\n";
    $this->salida .= "		  <tr><td class=\"modulo_table_title\" colspan=\"2\">DATOS REACCION TRANSFUSIONAL</td></tr>\n";
    $this->salida .= "		  <tr class=\"modulo_list_claro\">";
    $this->salida .= "		  <td width=\"20%\" nowrap class=\"label\">FECHA INICIO REACCION</td>";
    (list($fechaIn,$horaIn)=explode(' ',$DatosReaccion[fecha_inicio_reaccion]));
		(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
		(list($hhIn,$mmIn)=explode(':',$horaIn));
    $this->salida .= "		<td>".strtoupper(strftime("%b %d de %Y  %H:%M",mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn)))."</td>";
    $this->salida.="     </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td width=\"20%\" nowrap class=\"label\">FECHA SUSPENSION TRANSFUSION</td>";
		(list($fechaSu,$horaSu)=explode(' ',$DatosReaccion[fecha_suspension_transfusion]));
		(list($anoSu,$mesSu,$diaSu)=explode('-',$fechaSu));
		(list($hhSu,$mmSu)=explode(':',$horaSu));
    $this->salida .= "		<td>".strtoupper(strftime("%b %d de %Y  %H:%M",mktime($hhSu,$mmSu,0,$mesSu,$diaSu,$anoSu)))."</td>";
    $this->salida.="     </tr>";
		$this->salida .= "		<tr class=\"modulo_list_claro\">";
    $this->salida .= "		<td width=\"20%\" nowrap class=\"label\">FECHA NOTIFICACION MEDICO</td>";
		(list($fechaNot,$horaNot)=explode(' ',$DatosReaccion[fecha_notificacion_medico]));
		(list($anoNot,$mesNot,$diaNot)=explode('-',$fechaNot));
		(list($hhNot,$mmNot)=explode(':',$horaNot));
    $this->salida .= "		<td>".strtoupper(strftime("%b %d de %Y  %H:%M",mktime($hhNot,$mmNot,0,$mesNot,$diaNot,$anoNot)))."</td>";
    $this->salida.="     </tr>";
    if($DiagnosticosReaccion){
		$this->salida.="      <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"2\">";
		$this->salida.="        <table width=\"98%\" border=\"0\" align=\"center\">";
		$this->salida.="        <tr class=\"modulo_list_oscuro\"><td class=\"label\" colspan=\"2\">DIAGNOSTICOS</td></tr>";
    $this->salida.="        <tr class=\"modulo_list_oscuro\">";
		$this->salida.="        <td align=\"center\" width=\"20%\" class=\"label\">CODIGO</td>";
		$this->salida.="        <td align=\"center\" width=\"80%\" class=\"label\">DESCRIPCION</td>";
		$this->salida.="        </tr>";
		for($i=0;$i<sizeof($DiagnosticosReaccion);$i++){
      $this->salida.="        <tr class=\"modulo_list_oscuro\">";
			$this->salida.="        <td width=\"20%\">".$DiagnosticosReaccion[$i]['diagnostico_id']."</td>";
			$this->salida.="        <td width=\"75%\">".$DiagnosticosReaccion[$i]['diagnostico_nombre']."</td>";
      //$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'SeleccionDiagnostico',"EliminarDiagnostico".$pfj=>1,"codigoElimina".$pfj=>$codigo,"datos".$pfj=>$datos));
			//$this->salida.="        <td width=\"5%\"><a href=\"javascript:CargarForma(document.InsertarReaccionAdversa$pfj,'$codigo')\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
			$this->salida.="        </tr>";
		}
    $this->salida.="        </table>";
		$this->salida.="        </td>";
		$this->salida .= "		</tr>";
	  }

    $this->salida.="      <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"2\">";
		$this->salida.="        <table aling=\"center\">";
		if($DatosReaccion['sw_liquidos_endovenosos']==1){
      $des='checked';
		}
    $this->salida.="        <tr><td class=\"label\">USO LIQUIDOS ENDOVENOSOS&nbsp&nbsp;<input type=\"checkbox\" $des value=\"1\" name=\"uso_liquidos_endovenosos$pfj\" disabled></td></tr>";
		$this->salida.="        <tr><td><textarea name=\"liquidos$pfj\" class=\"textarea\" readonly cols=\"100\" rows=\"5\">".$DatosReaccion['liquidos_endovenosos']."</textarea></td></tr>";
    $this->salida.="        </table>";
		$this->salida.="        </td>";
		$this->salida .= "		</tr>";
		$this->salida.="      <tr class=\"modulo_list_claro\">";
    $this->salida.="        <td colspan=\"2\">";
		$this->salida.="        <table aling=\"center\">";
    $this->salida.="        <tr><td class=\"".$this->SetStyle("reaccionAdversa".$pfj)."\">SIGNOS Y SINTOMAS DE LA REACCION</td></tr>";
		$this->salida.="        <tr><td><textarea name=\"reaccionAdversa$pfj\" class=\"textarea\" readonly cols=\"100\" rows=\"5\">".$DatosReaccion['observacion']."</textarea></td></tr>";
    $this->salida.="        </table>";
		$this->salida.="        </td>";
		$this->salida .= "		</tr>";
		$this->salida.="      <tr class=\"modulo_list_claro\"><td colspan=\"2\">";
		$this->salida .= "    <table align=\"center\" width=\"30%\" cellpadding=\"2\" cellspacing=\"2\" border=\"1\">\n";
		$this->salida.="      <tr class=\"modulo_table_title\" >";
		$this->salida.="      <td class=\"".$this->SetStyle("sel".$pfj)."\" colspan='6'>SELECCIONE REACCION</td></tr>";
		$this->salida .= "    </tr>\n";
		$this->salida .= "    <tr class='hc_submodulo_list_claro'>\n";
		if($DatosReaccion['sw_reaccion']=='1'){
      $che='checked';
		}elseif($_REQUEST['sw_reaccion']=='2'){
      $che1='checked';
		}elseif($_REQUEST['sw_reaccion']=='3'){
      $che2='checked';
		}
		$this->salida.="      <td>Positivo <img src=\"".GetThemePath()."/images/activo.gif\"  width=\"10\" height=\"10\">&nbsp;</td><td width=\"2%\"><input type=\"radio\" name=\"sel$pfj\" value=\"1\" $che disabled></td>";
		$this->salida.="      <td>&nbsp;&nbsp;Neutral<img src=\"".GetThemePath()."/images/inactivoip.gif\"  width=\"10\" height='10'>&nbsp;</td><td width=\"2%\"><input type=\"radio\"  name=\"sel$pfj\" value=\"3\" $che2 disabled></td>";
		$this->salida.="      <td>&nbsp;&nbsp;Negativo<img src=\"".GetThemePath()."/images/inactivo.gif\"  width=\"10\" height='10'>&nbsp;</td><td width=\"2%\"><input type=\"radio\"  name=\"sel$pfj\" value=\"2\" $che1 disabled></td>";
		$this->salida.="      </tr>";
		$this->salida.="      </table><br>";
		$this->salida .= "		</td></tr>";

		/*
    $this->salida .= "			<td class=\"hc_submodulo_list_claro\">";
		$reaccion=$this->GetNotasReaccionAdversasPaciente($datos[fecha]);
		if($reaccion != 'ShowMensage'){
			//$this->salida .= "		<td><img src=\"".GetThemePath()."/images/flecha_der.gif\"  width='10' height='10'>&nbsp;<label class='label_mark'>".$estaciones[$i][1]."</label></td>\n";
			$this->salida .= "<br><table align=\"center\" width=\"90%\"  bordercolor='gray' border=\"1\">\n";
			$this->salida .= "		<tr class=\"modulo_table_title\">\n";
			$this->salida .= "			<td></td>\n";
			$this->salida .= "			<td>HORA</td>\n";
			$this->salida .= "			<td>OBSERVACION</td>\n";
			$this->salida .= "			<td>USUARIO</td>\n";
			$this->salida .= "		</tr>\n";
			for($j=0;$j<sizeof($reaccion);$j++){
				if($j % 2)  $estilo = "hc_submodulo_list_oscuro";  else $estilo = "hc_submodulo_list_claro";
				$this->salida .= "		<tr class='$estilo'>\n";
				if($reaccion[$j][sw_reaccion]==1){
					$img="activo.gif";
				}elseif($reaccion[$j][sw_reaccion]==2){
					$img='inactivo.gif';
				}elseif($reaccion[$j][sw_reaccion]==3){
					$img='inactivoip.gif';
				}
				$this->salida .= "			<td width=\"5%\" ><img src=\"".GetThemePath()."/images/$img\"  width='12' height='12'></td>\n";
				unset($img);
				$hora=explode(".",$reaccion[$j][fecha_registro]);
				$this->salida .= "			<td width=\"20%\"><label class='label_mark'>".$hora[0]."</label></td>\n";
				$this->salida .= "			<td width=\"70%\">".$reaccion[$j][observacion]."</td>\n";
				$this->salida .= "			<td width=\"10%\">\n";
				$nom=$this->GetDatosUsuarioSistema($reaccion[$j][usuario_id]);
				$this->salida .= "			".$nom[0][usuario]."\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
			}
			$this->salida .= "	</table><br>\n";
		}
		$this->salida .= "<textarea name=\"reaccionAdversa$pfj\" class=\"textarea\" cols=\"100\" rows=\"5\">".$_REQUEST['reaccionAdversa'.$pfj]."</textarea>";
		$this->salida .= "</td>\n";
		*/

    $this->salida .= "  </table><br>";
    $this->salida .= "</form>\n";
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>''));
		$this->salida .= "<div class='normal_10' align='center'><br><a href='".$action."'>Volver al listado de transfusiones</a><br>\n";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;

	}


	function FrmBusquedaDiagnosticos($codigoDes,$descripcionDes){

    $this->salida  = ThemeAbrirTablaSubModulo('BUSQUEDA DE DIAGNOSTICOS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico'));
		$this->salida .= "  <form name='formauno".$this->frmPrefijo."' action=$accion method='post'>";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">DIAGNOSTICOS</td></tr>";
    $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  <td width=\"10%\">CODIGO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"6\" type=\"text\" name=\"codigoDes".$this->frmPrefijo."\" value=\"$codigoDes\"></td>";
		$this->salida .= "  <td width=\"13%\">DESCRIPCION</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"60\" type=\"text\" name=\"descripcionDes".$this->frmPrefijo."\" value=\"$descripcionDes\"></td>";
		$this->salida .= "  <td width=\"10%\" class=\"input-submit\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		$diagnosticos=$this->RegistrosDiagnosticos($codigoDes,$descripcionDes);
		if($diagnosticos){
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\">";
			$this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($diagnosticos);$i++){
			  if($y % 2){$estilo='hc_submodulo_list_claro';}else{$estilo='hc_submodulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
        $this->salida .= "  <td width=\"15%\">".$diagnosticos[$i]['diagnostico_id']."</td>";
			  $this->salida .= "  <td>".$diagnosticos[$i]['diagnostico_nombre']."</td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',"codigoDiagnostico".$this->frmPrefijo.""=>$diagnosticos[$i]['diagnostico_id'],"nombreDiagnostico".$this->frmPrefijo.""=>$diagnosticos[$i]['diagnostico_nombre'],"seleccion".$this->frmPrefijo.""=>1));
				$this->salida .= "  <a href=\"$accion\" class=\"link\"><b><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></b></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table><BR>";
			$this->salida .=$this->RetornarBarra(1);
		}
		$this->salida .= "  <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"input-submit\" align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"SALIR\" name=\"salir".$this->frmPrefijo."\"></td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
  }

/**
* FrmIngresarHemoclasificacionPaciente - Forma que pide los datos para registrar la hemoclasificacion del paciente
*
* @return boolean
* @param $datos Datos de los pacientes
*/
	function FrmIngresarHemoclasificacionPaciente($datos)
	{
	  $pfj=$this->frmPrefijo;
		$gruposSanguineos = $this->GetGruposSanguineos();
		if(empty($this->titulo)){
			$this->salida= ThemeAbrirTablaSubModulo("EDICI&Oacute;N DE LA PLANTILLA");
		}else{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmIngresarHemoclasificacionPaciente','datos'.$pfj=>$datos));
		$this->salida .= "<form name=\"forma\" method=\"POST\" action=\"$action\"><br>\n";
		$this->salida .= "  <BR><table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
		$this->salida .= "	</table>\n";
		$this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida.="    <tr class=\"modulo_table_title\">";
		$this->salida.="    <td colspan=\"4\">DATOS DE LA HEMOCLASIFICACION</td>";
		$this->salida.="    </tr>";
		$this->salida.="    <tr class = \"hc_submodulo_list_claro\">";
		$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("grupo_sanguineo")."\">GRUPO SANGUINEO</td>";
		$this->salida .= "   <td><select name=\"grupo_sanguineo$pfj\" class=\"select\" $desabilitado>";
		$facts=$this->ConsultaFactor();
		$this->salida .=" <option value=\"-1\" selected>---Seleccione---</option>";
		for($i=0;$i<sizeof($facts);$i++){
			if($facts[$i]['grupo_sanguineo']==$_REQUEST['grupo_sanguineo'.$pfj]){
				$select='selected';
			}else{
				$select='';
			}
			$this->salida .=" <option $select value=\"".$facts[$i]['grupo_sanguineo']."\">".$facts[$i]['grupo_sanguineo']."</option>";
		}
		$this->salida .= "   </select></td>";
		$this->salida.="     <td align=\"left\" class=\"".$this->SetStyle("rh")."\">Rh </td>";
		$this->salida.="     <td align=\"left\" >";
		$this->salida.="     <select size=\"1\" name =\"rh$pfj\" class =\"select\" $desabilitado>";
		if($_REQUEST['rh'.$pfj]=='+'){
			$checkeado='selected';
		}elseif($_REQUEST['rh'.$pfj]=='-'){
			$checkeado1='selected';
		}
		$this->salida.="     <option value = -1>-Seleccione-</option>";
		$this->salida.="     <option value=\"+\" $checkeado> Positivo </option>";
		$this->salida.="     <option value=\"-\" $checkeado1> Negativo </option>";
		$this->salida.="     </select>";
		$this->salida.="     </td>";
		$this->salida.="     </tr>";
		$this->salida .="    <tr class = \"hc_submodulo_list_claro\">";
		if(!$_REQUEST['fecha_examen'.$pfj]){
			$_REQUEST['fecha_examen'.$pfj]=date('d-m-Y');
		}
		$this->salida .="    <td class=\"".$this->SetStyle("fecha_examen")."\" align=\"left\">FECHA DEL EXAMEN</td>";
		$this->salida .="    <td colspan=\"3\" align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['fecha_examen'.$pfj]."\" name=\"fecha_examen$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha_examen'.$pfj,'-')."</td>" ;
		$this->salida.="     </tr>";
		$this->salida .="    <tr class = \"hc_submodulo_list_claro\">";
		$this->salida.="     <td class=\"".$this->SetStyle("laboratorio")."\" align=\"left\">LABORATORIO</td>";
		$this->salida.="     <td colspan=\"3\" align=\"left\"><input type=\"text\" name=\"laboratorio$pfj\" value=\"".$_REQUEST['laboratorio'.$pfj]."\" size=\"40\" class=\"input-submit\"></td>";
		$this->salida.="     </tr>";
		$this->salida .="    <tr class = \"hc_submodulo_list_claro\">";
		$this->salida .= "   <td class=\"".$this->SetStyle("bacteriologo")."\">PROFESIONAL</td>";
		$this->salida .= "   <td colspan=\"3\"><select name=\"bacteriologo$pfj\" class=\"select\">";
		$bacteriologos=$this->TotalBacteriologos();
		$this->salida .=" <option value=\"-1\" selected>---Seleccione---</option>";
		for($i=0;$i<sizeof($bacteriologos);$i++){
			if($bacteriologos[$i]['tercero_id']."/".$bacteriologos[$i]['tipo_id_tercero']==$_REQUEST['bacteriologo'.$pfj]){
				$select='selected';
			}else{
				$select='';
			}
			$this->salida .=" <option $select value=\"".$bacteriologos[$i]['tercero_id']."/".$bacteriologos[$i]['tipo_id_tercero']."\">".$bacteriologos[$i]['nombre']."</option>";
		}
		//$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST['bacteriologo']);
		$this->salida .= "    </select></td>";
		$this->salida.="     </tr>";
		$this->salida .="    <tr class = \"hc_submodulo_list_claro\">";
		$this->salida.="     <td align=\"left\" colspan=\"4\"><b>OBSERVACIONES</b><br><textarea style=\"width:100%\" name=\"observaciones$pfj\" class=\"textarea\" rows=\"3\" cols=\"60\">".$_REQUEST['observaciones'.$pfj]."</textarea></td>";
		$this->salida.="     </tr>";
		$this->salida.="     </table>";
		$this->salida .= "<div class=\"normal_10\" align=\"center\"><br><input type=\"submit\" class=\"input-submit\" name=\"SaveHemoclasify$pfj\" value=\"GUARDAR\">";
		$this->salida .= "</form>\n";
		$action=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'CallFrmInsertarReaccionAdversa','datos'.$pfj=>$datos));
		$this->salida .= "<div class='normal_10' align='center'><br><a href=\"$action\">Volver al listado de transfusiones</a><br>\n";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}//FrmIngresarHemoclasificacionPaciente

/**
* frmConsultaReservas - Forma que muestra las reservas realizadas al paciente en el ingreso
*
* @return boolean
*/

	function frmConsultaReservas(){

		$pfj=$this->frmPrefijo;
		$vectorTot=$this->ConsultaReservaSangre();
    if($vectorTot){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"2\">RESERVAS REALIZADAS</td>";
			$this->salida.="  <td align=\"center\" colspan=\"4\">CANTIDADES</td>";
			$this->salida.="  <td align=\"center\" colspan=\"5\">BOLSAS ENTREGADAS TRANSFUSION</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"14%\" nowrap>No. SOLICITUD<BR>FECHA</td>";
			//$this->salida.="<td width=\"8%\" nowrap>NIVEL<br>URGENCIA</td>";
			//$this->salida.="<td width=\"8%\" nowrap>AOB / RH<BR>PACIENTE</td>";
			$this->salida.="<td width=\"20%\" nowrap>COMPONENTE</td>";
			$this->salida.="<td width=\"8%\" nowrap>SOLICITADAS</td>";
			$this->salida.="<td width=\"8%\" nowrap>PENDIENTES</td>";
			$this->salida.="<td width=\"8%\" nowrap>CONFIRMADAS</td>";
			$this->salida.="<td width=\"3%\" nowrap>&nbsp</td>";
			$this->salida.="<td width=\"12%\" nowrap>- BOLSAS -<br> ALICUOTA </td>";
			$this->salida.="<td width=\"5%\" nowrap>AOB/<BR>RH</td>";
			$this->salida.="<td width=\"8%\" nowrap>DESPACHO</td>";
			$this->salida.="<td width=\"8%\" nowrap>RECIBIR</td>";
			$this->salida.="<td width=\"6%\" nowrap>&nbsp;</td>";
			$this->salida.="</tr>";
			$solicitudAnt=-1;
			foreach($vectorTot as $solicitud=>$arreglo){
			  foreach($arreglo as $componente=>$vector){
					if($k % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
					if($solicitud!=$solicitudAnt){
						$this->salida.="<tr class=\"modulo_list_oscuro\">";
						$this->salida.="  <td align=\"center\" rowspan=\"".sizeof($arreglo)."\">- ".$vector['solicitud_reserva_sangre_id']." -<BR>";
						(list($fecha,$hora)=explode(' ',$vector[fecha_hora_reserva]));
						(list($ano,$mes,$dia)=explode('-',$fecha));
						(list($hh,$mm)=explode(':',$hora));
						$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
						$this->salida.="  ".strftime("%b %d de %Y %H:%M",$FechaConver)."</td>";
						/*if ($vector[sw_urgencia] == '1'){
							$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">ALTO</td>";
						}else{
							$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">NORMAL</td>";
						}
						if($vector[grupo_sanguineo] && $vector[rh]){
							if($vector[rh]=='+'){
								$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">".$vector[grupo_sanguineo]."&nbsp&nbsp;/&nbsp&nbsp;<label class=\"label\">POSITIVO</label></td>";
							}else{
								$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">".$vector[grupo_sanguineo]."&nbsp&nbsp;/&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
							}
						}else{
							$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">SIN REGISTRO</td>";
						}*/
						$this->salida.="<td>".$vector[componente]."</td>";
						$this->salida.="<td>".$vector[cantidad_componente]."</td>";
						if(($vector[cantidad_componente]-$vector[confirmadas])>0){
							$this->salida.="<td>".($vector[cantidad_componente]-$vector[confirmadas])."</td>";
							if($_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']] && array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'.$pfj])){
                $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".$_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'].$pfj]."\"></td>";
							}else{
							  $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\"></td>";
							}
              $this->salida.="<input type=\"hidden\" name=\"ValorPendiente".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\">";
							if(array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'.$pfj])){
                $che='checked';
							}else{
                $che='';
							}
							$this->salida.="<td align=\"center\"><input $che type=\"checkbox\" name=\"Solicitar".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\"></td>";
						}else{
              $this->salida.="<td class=\"label\" colspan=\"3\" align=\"center\">CONFIRMADAS</td>";
						}
						$this->salida.="<td colspan=\"5\">";
						$componentes=$this->UnidadesPatinaje($vector['solicitud_reserva_sangre_id'],$vector['tipo_componente_id']);
						if($componentes){
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"99%\">";
						for($i=0;$i<sizeof($componentes);$i++){
						  $this->salida.="<tr class=\"$estilo1\">";
							if($componentes[$i]['numero_alicuota']==0){$alic='PRINCIPAL';}else{$alic=$componentes[$i]['numero_alicuota'];}
								$this->salida.="<td width=\"30%\" nowrap align=\"center\" class=\"label\">- ".$componentes[$i]['bolsa_id']." -<BR>".$alic."</td>";
								$this->salida.="<td width=\"15%\" nowrap align=\"center\" class=\"label\">".$componentes[$i]['grupo_sanguineo']." / ".$componentes[$i]['rh']."</td>";
								if($componentes[$i]['despachado']==1){
                $this->salida.="<td width=\"20%\" nowrap  align=\"center\" class=\"label\"><img title=\"Despachado\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
								}else{
                $this->salida.="<td width=\"20%\" nowrap align=\"center\" class=\"label\">&nbsp;</td>";
								}
								if($componentes[$i]['recibido']==1){
									$this->salida.="<td width=\"20%\" nowrap align=\"center\" class=\"label\"><img title=\"Recibida\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
									if($componentes[$i]['estado_bolsa']!='2'){
										$fecha=explode('-',$componentes[$i]['fecha_vencimiento']);
										$fechaVence=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
										$tipoSanguineo=$componentes[$i]['grupo_sanguineo'].'.-.'.$componentes[$i]['rh'];
										$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'','numSello'.$pfj=>$componentes[$i]['sello_calidad'],
										'cantBolsas'.$pfj=>$componentes[$i]['bolsa_id'],'IngresoBolsaId'.$pfj=>$componentes[$i]['ingreso_bolsa_id'],'numeroAlicuota'.$pfj=>$componentes[$i]['numero_alicuota'],
										'componente'.$pfj=>$componentes[$i]['tipo_componente'],'fechaVencimiento'.$pfj=>$fechaVence,'tipoSanguineo'.$pfj=>$tipoSanguineo,"origen".$pfj=>1,'numeroReserva'.$pfj=>$vector['solicitud_reserva_sangre_id']));
										$this->salida.="<td width=\"15%\" nowrap align=\"center\" class=\"label\"><a href=\"$href\"><img title=\"Seleccion Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
									}else{
                    $this->salida.="<td width=\"20%\" nowrap align=\"center\" class=\"label\"><img title=\"Transfundida\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
									}
								}else{
									$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ResistroRecepcionBolsa','IngresoId'.$pfj=>$componentes[$i]['ingreso_bolsa_id'],'alicuota'.$pfj=>$componentes[$i]['numero_alicuota'],'bolsaId'.$pfj=>$componentes[$i]['bolsa_id']));
									$this->salida.="<td width=\"20%\" nowrap align=\"center\"><a href=\"$accion\"><img title=\"Recibir Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/entregabolsa.png\"></a></td>";
									$this->salida.="<td width=\"15%\" nowrap align=\"center\" class=\"label\">&nbsp;</td>";
								}
							  $this->salida.="</tr>";
						  }
						  $this->salida.="</table>";
						}
						$this->salida.="</td>";
						$this->salida.="</tr>";
						$solicitudAnt=$solicitud;
						$k=1;
					}else{
            $this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td>".$vector[componente]."</td>";
						$this->salida.="<td>".$vector[cantidad_componente]."</td>";
            if(($vector[cantidad_componente]-$vector[confirmadas])>0){
							$this->salida.="<td>".($vector[cantidad_componente]-$vector[confirmadas])."</td>";
							if($_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']] && array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'])){
                $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".$_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'].$pfj]."\"></td>";
							}else{
							  $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\"></td>";
							}
              $this->salida.="<input type=\"hidden\" name=\"ValorPendiente".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\">";
							if(array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'.$pfj])){
                $che='checked';
							}else{
                $che='';
							}
							$this->salida.="<td align=\"center\"><input type=\"checkbox\" name=\"Solicitar".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\" $che></td>";
						}else{
              $this->salida.="<td class=\"label\" colspan=\"3\" align=\"center\">CONFIRMADAS</td>";
						}
						$this->salida.="<td colspan=\"5\">";
						$componentes=$this->UnidadesPatinaje($vector['solicitud_reserva_sangre_id'],$vector['tipo_componente_id']);
						if($componentes){
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"99%\">";
						for($i=0;$i<sizeof($componentes);$i++){
						  $this->salida.="<tr class=\"$estilo1\">";
							if($componentes[$i]['numero_alicuota']==0){$alic='PRINCIPAL';}else{$alic=$componentes[$i]['numero_alicuota'];}
								$this->salida.="<td width=\"30%\" nowrap align=\"center\" class=\"label\">- ".$componentes[$i]['bolsa_id']." -<BR>".$alic."</td>";
								$this->salida.="<td width=\"15%\" nowrap align=\"center\" class=\"label\">".$componentes[$i]['grupo_sanguineo']." / ".$componentes[$i]['rh']."</td>";
								if($componentes[$i]['despachado']==1){
                $this->salida.="<td width=\"20%\" nowrap  align=\"center\" class=\"label\"><img title=\"Despachado\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
								}else{
                $this->salida.="<td width=\"20%\" nowrap align=\"center\" class=\"label\">&nbsp;</td>";
								}
								if($componentes[$i]['recibido']==1){
										$this->salida.="<td width=\"20%\" nowrap align=\"center\" class=\"label\"><img title=\"Recibida\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
									if($componentes[$i]['estado_bolsa']!='2'){
										$fecha=explode('-',$componentes[$i]['fecha_vencimiento']);
										$fechaVence=$fecha[2].'-'.$fecha[1].'-'.$fecha[0];
										$tipoSanguineo=$componentes[$i]['grupo_sanguineo'].'.-.'.$componentes[$i]['rh'];
										$href=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'','numSello'.$pfj=>$componentes[$i]['sello_calidad'],
										'cantBolsas'.$pfj=>$componentes[$i]['bolsa_id'],'IngresoBolsaId'.$pfj=>$componentes[$i]['ingreso_bolsa_id'],'numeroAlicuota'.$pfj=>$componentes[$i]['numero_alicuota'],
										'componente'.$pfj=>$componentes[$i]['tipo_componente'],'fechaVencimiento'.$pfj=>$fechaVence,'tipoSanguineo'.$pfj=>$tipoSanguineo,"origen".$pfj=>1,'numeroReserva'.$pfj=>$vector['solicitud_reserva_sangre_id']));
										$this->salida.="<td width=\"15%\" nowrap align=\"center\" class=\"label\"><a href=\"$href\"><img title=\"Seleccion Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
									}else{
                    $this->salida.="<td width=\"20%\" nowrap align=\"center\" class=\"label\"><img title=\"Transfundida\" border=\"0\" src=\"".GetThemePath()."/images/endturn.png\"></td>";
									}
								}else{
									$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ResistroRecepcionBolsa','IngresoId'.$pfj=>$componentes[$i]['ingreso_bolsa_id'],'alicuota'.$pfj=>$componentes[$i]['numero_alicuota'],'bolsaId'.$pfj=>$componentes[$i]['bolsa_id']));
									$this->salida.="<td width=\"20%\" nowrap align=\"center\"><a href=\"$accion\"><img title=\"Recibir Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/entregabolsa.png\"></a></td>";
									$this->salida.="<td width=\"15%\" nowrap align=\"center\" class=\"label\">&nbsp;</td>";
								}
							  $this->salida.="</tr>";
						  }
						  $this->salida.="</table>";
						}
						$this->salida.="</td>";
            $this->salida.="</tr>";
						$k++;
					}
        }
			}
			$this->salida.="<tr class=\"$estilo\">";
      $this->salida.="<td colspan=\"6\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"confirmar$pfj\" value=\"CONFIRMAR\"></td>";
      $this->salida.="<td colspan=\"5\">&nbsp;</td>";
			$this->salida.="</table>";
		}
		return true;
	}

/**
* FormaRegistroRecepcionBolsa - Forma donde se ingresan los datos para realizar el registro de la recepcion de la bolsa
*
* @return boolean
* @param $IngresoId numero unico que identifica el ingreso del paciente
* @param $alicuota numero de la alicuota de la bolsa
* @param $bolsaId numero de la bolsa
*/
	function FormaRegistroRecepcionBolsa($IngresoId,$alicuota,$bolsaId){
    $pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('RECEPCION COMPONENTE SANGUINEO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ResistroRecepcionBolsa',
		'IngresoId'.$pfj=>$IngresoId,'alicuota'.$pfj=>$alicuota,'bolsaId'.$pfj=>$bolsaId));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
		if($alicuota==0){$ali='PRINCIPAL';}else{$ali=$alicuota;	}
    $this->salida.="    <tr class=\"modulo_table_title\"><td align=\"center\">BOLSA No. $bolsaId <BR> ALICUOTA: $ali</td></tr>";
    $this->salida.="    <tr class = \"modulo_list_claro\"><td><label class=\"label\">OBSERVACIONES</label><BR>";
		$this->salida .= "  <textarea class =\"textarea\" rows =\"5\" cols =\"80\"  name=\"observaciones$pfj\"></textarea>";
		$this->salida.="    </td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "   <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
    $this->salida.="     <tr><td align=\"center\">";
		$this->salida.="     <input type=\"submit\" name=\"guardarDatos$pfj\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida.="     <input type=\"submit\" name=\"cancelarDatos$pfj\" value=\"VOLVER\" class=\"input-submit\">";
		$this->salida.="     </td></tr>";
    $this->salida.="     </table>";
    $this->salida.="     </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//cor - jea - ads
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//cor - jea - ads
	function RetornarBarra($origen)//Barra paginadora de los planes clientes
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
    if($origen==1){
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'SeleccionDiagnostico',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'codigoPro'.$pfj=>$_REQUEST['codigoPro'.$pfj],
		'descripcionPro'.$pfj=>$_REQUEST['descripcionPro'.$pfj],"Buscar".$pfj=>1));
    }

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







}

?>
