<?php

/**
*MODULO para el Manejo de Programacion de cirugias del sistema
*
* @author Lorena Aragon
*/

// ----------------------------------------------------------------------
// SIIS v 0.1
// Copyright (C) 2003 InterSoftware Ltda.
// Emai: intersof@telesat.com.co
// ----------------------------------------------------------------------

/**
*Contiene los metodos visuales para realizar la programacion de cirugias
*/
class app_Banco_Sangre_userclasses_HTML extends app_Banco_Sangre_user
{
	/**
	*Constructor de la clase app_ProgramacionQX_user_HTML
	*El constructor de la clase app_ProgramacionQX_user_HTML se encarga de llamar
	*a la clase app_ProgramacionQX_user quien se encarga de el tratamiento
	*de la base de datos.
	*/

  function app_Banco_Sangre_user_HTML()
	{
		$this->salida='';
		$this->app_Banco_Sangre_user();
		return true;
	}

/**
* Funcion que muestra las distintas opciones del menu para el usuario
* @return boolean
*/
	function MenuConsultas(){
	  unset($_SESSION['BANCO']['SANGRE']);
		unset($_SESSION['PACIENTES']);
		unset($_SESSION['RESERVA_SANGRE']);

    $this->salida .= ThemeAbrirTabla('UNIDADES DE COMPONENTES SANGUINEOS');		//$this->salida .= "			      <br><br>";
    $action1=ModuloGetURL('system','Menu','user','main');
		$this->salida .= "    <form name=\"forma\" action=\"$action1\" method=\"post\">";
		$this->salida .= "			<table width=\"35%\" align=\"center\" class=\"modulo_table_list\">";
		$action=ModuloGetURL('app','Banco_Sangre','user','LlamaReservasSangreDiarias');
		$action1=ModuloGetURL('app','Banco_Sangre','user','LlamaIngresoBolsaSangre');
		$action2=ModuloGetURL('app','Banco_Sangre','user','LlamaConsultaBolsasSangre');
		$action3=ModuloGetURL('app','Banco_Sangre','user','LlamaComponentesAVencer');
		$action4=ModuloGetURL('app','Banco_Sangre','user','LlamaSolicitudExterna');
		//$confirmar=$this->ConfirmarUsuarioEntrega();
		//if($confirmar){
		$action5=ModuloGetURL('app','Banco_Sangre','user','LlamaEntregaBolsaSanguinea');
		//}
		//$action4=ModuloGetURL('app','Banco_Sangre','user','');
		$this->salida .= "				       <tr><td class=\"modulo_table_list_title\" align=\"center\">MENU</td></tr>";
    //$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action\" class=\"link\"><b>CONSULTA RESERVAS DE SANGRE</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action1\" class=\"link\"><b>INGRESO DE NUEVA UNIDAD SANGUINEA</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action2\" class=\"link\"><b>CONSULTA DE UNIDADES SANGUINEAS</b></a></td></tr>";
		$bolsas=$this->ComponentesCercaAVencer();
		if(sizeof($bolsas)>0){
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action3\" class=\"link\"><b>UNIDADES PROXIMAS A LA FECHA DE VENCIMIENTO   (".sizeof($bolsas).")</b></a></td></tr>";
		}
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action4\" class=\"link\"><b>SOLICITUD EXTERNA DE UNIDADES</b></a></td></tr>";
		$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action5\" class=\"link\"><b>ENTREGA DE UNIDADES</b></a></td></tr>";
		//$this->salida .= "				       <tr><td class=\"modulo_list_claro\" align=\"center\"><a href=\"$action4\" class=\"link\"><b>DAR DE BAJA A UNA UNIDAD</b></a></td></tr>";
    $this->salida .= "			     </table><BR>";
		$this->salida .= "     <table width=\"40%\" border=\"0\" align=\"center\"class=\"normal_10\" >";
    $this->salida .= "     <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" name=\"salir\" value=\"SALIR\"></td></tr>";
    $this->salida .= "     </table>";
		$this->salida .= "		</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
  }
/**
* Funcion que se encarga de visualizar un error en un campo
* @return string
*/
	function SetStyle($campo){
		if ($this->frmError[$campo] || $campo=="MensajeError"){
		  if ($campo=="MensajeError"){
				return ("<tr><td colspan=\"3\" class='label_error' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function MostrasSelect($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($arreglo as $value=>$titulo){
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  foreach($arreglo as $value=>$titulo){
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function GrupoSanguineo($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($arreglo);$i++){				  $clas='';
				  if($arreglo[$i]['rh']=='-'){
            $clas='label_error';
					}
					$value=$arreglo[$i]['grupo_sanguineo'].'/'.$arreglo[$i]['rh'];
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>".$arreglo[$i]['grupo_sanguineo']."&nbsp&nbsp&nbsp&nbsp;".$arreglo[$i]['descripcion']."</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">".$arreglo[$i]['grupo_sanguineo']."&nbsp&nbsp&nbsp&nbsp;".$arreglo[$i]['descripcion']."</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($arreglo);$i++){
				  $clas='';
				  if($arreglo[$i]['rh']=='-'){
            $clas='label_error';
					}
				  $value=$arreglo[$i]['grupo_sanguineo'].'/'.$arreglo[$i]['rh'];
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>".$arreglo[$i]['grupo_sanguineo']."&nbsp&nbsp&nbsp&nbsp;".$arreglo[$i]['descripcion']."</option>";
				  }
				  $this->salida .=" <option value=\"$value\">".$arreglo[$i]['grupo_sanguineo']."&nbsp&nbsp&nbsp&nbsp;".$arreglo[$i]['descripcion']."</option>";
			  }
			  break;
		  }
	  }
	}

	function FormaPedidoAlbaran($albaran,$descipcion_sgsss,$codigo_sgsss){

    $this->salida .= ThemeAbrirTabla('INGRESO DEL ALBARAN Y PROCEDENCIA DEL COMPONENTE');
		$action1=ModuloGetURL('app','Banco_Sangre','user','ValidarAlbaranProcedencia');
    $this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
		$this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ALBARAN</legend>";
		$this->salida .= "  <br><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "	 <td align=\"center\"><input size=\"20\" maxlength=\"20\" type=\"text\" name=\"albaran\" value=\"$albaran\" class=\"input-text\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td align=\"center\">";
		$this->salida .= "	 <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"BUSCAR PROCEDENCIA\">";
		$this->salida .= "	 <input type=\"submit\" class=\"input-submit\" name=\"Cancelar\" value=\"MENU\">";
		$this->salida .= "  </td></tr>";
		$this->salida .= "	 </table><BR>";
		$this->salida .= "	 </fieldset></td></tr>";
		$this->salida .= "	 </table><br>";
		$this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PROCEDENCIA</td></tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\">";
    $this->salida .= "    <td><label class=\"".$this->SetStyle("codigo_sgsss")."\"><input size=\"80\" class=\"text-input\" type=\"text\" name=\"descipcion_sgsss\" value=\"$descipcion_sgsss\" readonly></td>";
		$this->salida .= "    <input size=\"6\" class=\"text-input\" type=\"hidden\" name=\"codigo_sgsss\" value=\"$codigo_sgsss\" readonly>";
		$this->salida .= "    <td align=\"center\" valign=\"bottom\"><input type=\"submit\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
		$this->salida .= "    </tr>";
    $this->salida .= "    </table>";
		$procedencias=$this->SeleccionProcedencias($albaran);
		if($procedencias){
		  $this->salida .= "    <BR><table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr class=\"modulo_table_list_title\">";
      $this->salida .= "    <td colspan=\"2\">ENTIDAD</td>";
			$this->salida .= "    <td>&nbsp;</td>";
      $this->salida .= "    </tr>";
			for($i=0;$i<sizeof($procedencias);$i++){
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
				$this->salida .= "    <td>".$procedencias[$i]['entidad_origen']."</td>";
				$this->salida .= "    <td>".$procedencias[$i]['nombre_tercero']."</td>";
				$action=ModuloGetURL('app','Banco_Sangre','user','SeleccionAlbaranProcedencia',array("albaran"=>$albaran,"codigo"=>$procedencias[$i]['entidad_origen'],"nombreTercero"=>$procedencias[$i]['nombre_tercero']));
				$this->salida .= "    <td><a href=\"$action\" class=\"link\"><b>SELECCION</b></a></td>";
				$this->salida .= "    </tr>";
			}
      $this->salida .= "    </table>";
		}
//     $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
//     $this->salida .= "    <tr><td align=\"center\">";
//     $this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"SeleccionDatos\" value=\"ACEPTAR\">";
// 		$this->salida .= "    </td></tr>";
//     $this->salida .= "    </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function IngresoBolsaSangre($BolsaId,$selloCalidad,$tipoComponente,$grupo_sanguineo,$FechaVencimiento,$descipcion_sgsss,$codigo_sgsss,$FechaExtraccion,$origen,$albaran,$action1){
    $this->salida .= ThemeAbrirTabla('INGRESO DE UNIDADES SANGUINEAS');
		//$this->salida .= "			      <br><br>";
		if(!$action1){
    $action1=ModuloGetURL('app','Banco_Sangre','user','InsertarBolsaSangre');
		}
		$this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
    //$this->salida .= "  <input type=\"hidden\" name=\"diasCalculo\">";
		$this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">ALBARAN Y PROCEDENCIA DEL COMPONENTE</legend>";
		$this->salida .= "  <br><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">$albaran</td>";
		$this->salida .= "  <td class=\"label\">$descipcion_sgsss</td>";
    $this->salida .= "  <input type=\"hidden\" value=\"$albaran\" name=\"albaran\">";
		$this->salida .= "  <input type=\"hidden\" value=\"$descipcion_sgsss\" name=\"descipcion_sgsss\">";
		$this->salida .= "  <input type=\"hidden\" value=\"$codigo_sgsss\" name=\"codigo_sgsss\">";
		$this->salida .= "  </tr>";
    $this->salida .= "	 </table><BR>";
		$this->salida .= "	 </fieldset></td></tr>";
		$this->salida .= "	 </table><br>";
		$this->salida .= "  <table width=\"75%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
    $this->salida .= "  <tr class=\"modulo_table_title\">";
		$this->salida .= "  <td colspan=\"2\" align=\"center\">DATOS DEL COMPONENTE</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"30%\" class=\"".$this->SetStyle("tipoComponente")."\">TIPO COMPONENTE </td>";
		$this->salida .= "      <td><select name=\"tipoComponente\" class=\"select\">";
		$componentes=$this->ConsultaComponente();
		$this->MostrasSelect($componentes,'False',$tipoComponente);
		$this->salida .= "      </select></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"30%\" class=\"".$this->SetStyle("BolsaId")."\">No. BOLSA</td>";
		$this->salida .= "  <td><input type=\"text\" name=\"BolsaId\" value=\"$BolsaId\"class=\"input-text\" size=\"32\" maxlength=\"32\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"30%\" class=\"".$this->SetStyle("selloCalidad")."\">SELLO DE CALIDAD</td>";
		$this->salida .= "  <td><input type=\"text\" name=\"selloCalidad\" value=\"$selloCalidad\" class=\"input-text\" size=\"32\" maxlength=\"32\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "      <td width=\"30%\" class=\"".$this->SetStyle("grupo_sanguineo")."\">ABO / Rh</td>";
		$this->salida .= "      <td><select name=\"grupo_sanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactor();
		$this->GrupoSanguineo($facts,'False',$grupo_sanguineo);
		$this->salida .= "      </select></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "	 <td class=\"".$this->SetStyle("FechaVencimiento")."\">FECHA VENCIMIENTO</td>";
		$this->salida .= "	 <td><input size=\"10\" type=\"text\" name=\"FechaVencimiento\" value=\"$FechaVencimiento\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	 &nbsp&nbsp&nbsp;".ReturnOpenCalendario('forma','FechaVencimiento','/')."</td>";
		$this->salida .= "  </tr>";
		/*$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "	 <td class=\"".$this->SetStyle("albaran")."\">ALBARAN</td>";
		$this->salida .= "	 <td><input size=\"20\" maxlength=\"20\" type=\"text\" name=\"albaran\" value=\"$albaran\" class=\"input-text\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td colspan=\"2\">";
		$this->salida .= "      <table width=\"85%\" border=\"0\" align=\"center\">";
    $this->salida .= "      <tr class=\"modulo_list_claro\">";
    $this->salida .= "      <td><label class=\"".$this->SetStyle("codigo_sgsss")."\">PROCEDENCIA<BR><BR></label><input size=\"80\" class=\"text-input\" type=\"text\" name=\"descipcion_sgsss\" value=\"$descipcion_sgsss\" readonly></td>";
		$this->salida .= "      <input size=\"6\" class=\"text-input\" type=\"hidden\" name=\"codigo_sgsss\" value=\"$codigo_sgsss\" readonly>";
		$this->salida .= "      <td align=\"center\" valign=\"bottom\"><input type=\"submit\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
		$this->salida .= "      </tr>";
    $this->salida .= "      </table>";
    $this->salida .= "  </td></tr>";
		*/
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "	 <td class=\"".$this->SetStyle("FechaExtraccion")."\">FECHA EXTRACCION</td>";
		$this->salida .= "	 <td><input size=\"10\" maxlength=\"10\" type=\"text\" name=\"FechaExtraccion\" value=\"$FechaExtraccion\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	 &nbsp&nbsp&nbsp;".ReturnOpenCalendario('forma','FechaExtraccion','/')."";
		$this->salida .= "	 &nbsp&nbsp&nbsp;<input type=\"submit\" class=\"input-submit\" value=\"CALCULAR\" name=\"calcular\">";
		$this->salida .= "	 </td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td colspan=\"2\">";
		$this->salida .= "      <table width=\"90%\" border=\"0\" align=\"center\">";
		$motivosInsercion=$this->MotivosInsercionBolsa();
		for($i=0;$i<sizeof($motivosInsercion);$i++){
		  $var='';
      if($origen==$motivosInsercion[$i]['codigo_motivo']){$var='checked';}
			if(!$origen && $i==0){$var='checked';	}
			$this->salida .= "      <tr class=\"modulo_list_claro\">";
      $this->salida .= "      <td width=\"5%\"><input type=\"radio\" name=\"origen\" value=\"".$motivosInsercion[$i]['codigo_motivo']."\" $var></td><td class=\"".$this->SetStyle("FechaExtraccion")."\">".$motivosInsercion[$i]['descripcion']."</td>";
			$this->salida .= "      </tr>";
		}
    $this->salida .= "      </table>";
    $this->salida .= "  </td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"INSERTAR\" name=\"insertar\">";
		$this->salida .= "  <input class=\"input-submit\" type=\"submit\" value=\"CANCELAR\" name=\"cancelar\"></td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SeleccionEntidad($albaran,$entidades){

	  $this->salida .= ThemeAbrirTabla('ENTIDAD ORIGEN DEL COMPONENTE');
		//$this->salida .= "			      <br><br>";
    $action=ModuloGetURL('app','Banco_Sangre','user','BusquedaEntidad',array("albaran"=>$albaran));
		$this->salida .= "  <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\">";
		$this->salida .= "  <td colspan=\"6\" align=\"center\">DATOS DE LA PROCEDENCIA</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td><label class=\"label\">DESCRIPCION</label></td><td><input size=\"50\" class=\"text-input\" type=\"text\" name=\"descipcion_sgsssBus\"></td>";
		$this->salida .= "  <td><label class=\"label\">CODIGO</label></td><td><input size=\"6\" class=\"text-input\" type=\"text\" name=\"codigo_sgsssBus\"></td>";
		$this->salida .= "  <td align=\"center\" colspan=\"2\" valign=\"bottom\"><input type=\"submit\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		if($entidades){
      $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		  $this->salida .= "  <tr class=\"modulo_table_title\">";
      $this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($entidades);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
				$this->salida .= "  <td>".$entidades[$i]['codigo_sgsss']."</td>";
				$this->salida .= "  <td>".$entidades[$i]['nombre_tercero']."</td>";
				$action=ModuloGetURL('app','Banco_Sangre','user','BusquedaEntidad',array("albaran"=>$albaran,"codigo"=>$entidades[$i]['codigo_sgsss'],"nombretercero"=>$entidades[$i]['nombre_tercero'],"seleccionar"=>1));
				$this->salida .= "	 <td><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table>";
		}
		$this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"REGRESAR\" name=\"regresar\"></td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensajeConfirmacion($mensaje,$titulo,$BolsaId,$selloCalidad,
		$grupo_sanguineo,$codigo_sgsss,$descipcion_sgsss,$FechaVencimiento,$tipoComponente,$FechaExtraccion,$motivoInsercion,$albaran){

		$this->salida .= ThemeAbrirTabla($titulo);
		$accion=ModuloGetURL('app','Banco_Sangre','user','ConfirmarInsercionBolsas',array("BolsaId"=>$BolsaId,"selloCalidad"=>$selloCalidad,
	  "grupo_sanguineo"=>$grupo_sanguineo,"codigo_sgsss"=>$codigo_sgsss,"descipcion_sgsss"=>$descipcion_sgsss,"FechaVencimiento"=>$FechaVencimiento,"tipoComponente"=>$tipoComponente,
		"FechaExtraccion"=>$FechaExtraccion,"motivoInsercion"=>$motivoInsercion,'albaran'=>$albaran));

		$this->salida .= "   <table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "   <tr><td></td></tr>";
		$this->salida .= "   <tr><td><fieldset><legend class=\"field\">DATOS DEL COMPONENTE SANGUINEO</legend>";
    $this->salida .= "  <table width=\"98%\" border=\"0\" align=\"center\">";
    $this->salida .= "  <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"4\">DATOS INGRESADOS DEL COMPONENTE</td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"20%\" class=\"label\">No. BOLSA</td>";
    $this->salida .= "  <td>".$BolsaId."</td>";
		$this->salida .= "  <td class=\"label\">SELLO</td>";
    $this->salida .= "  <td width=\"20%\">".$selloCalidad."</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"20%\" class=\"label\">COMPONENTE</td>";
		$nombreComp=$this->nombreComponente($tipoComponente);
    $this->salida .= "  <td>".$nombreComp['componente']."</td>";
		$this->salida .= "  <td class=\"label\" width=\"20%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "  <td>".$FechaVencimiento."</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		(list($grupoSanguineo,$rh)=explode('/',$grupo_sanguineo));
		$this->salida .= "  <td class=\"label\" width=\"20%\">GRUPO</td>";
    $this->salida .= "  <td>".$grupoSanguineo."</td>";
		$this->salida .= "  <td class=\"label\" width=\"20%\">Rh</td>";
		if($rh=='+'){
      $this->salida .= "  <td>POSITIVO</td>";
		}elseif($rh=='-'){
      $this->salida .= "  <td class=\"label_error\">POSITIVO</td>";
		}
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"20%\" class=\"label\">ALBARAN</td>";
    $this->salida .= "  <td colspan=\"3\">".$albaran."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"20%\" class=\"label\">PROCEDENCIA</td>";
    $this->salida .= "  <td colspan=\"3\">".$descipcion_sgsss."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"20%\" class=\"label\">FECHA EXTRACCION</td>";
    $this->salida .= "  <td>".$FechaExtraccion."</td>";
		$this->salida .= "  <td width=\"20%\" class=\"label\">ORIGEN</td>";
		$motivo=$this->nombreMotivoInsercion($motivoInsercion);
    $this->salida .= "  <td>".$motivo['descripcion']."</td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "	</fieldset></td></tr></table><BR>";
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label_error\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\">";
		$this->salida .= "				       <input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CONFIRMAR\"></td></tr>";
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ConsultaInventariosBolsasSangre($codigoBolsa,$grupoSanguineo,$componente,$FechaVmto,$estado,$cruze,$albaran){

    $this->salida.= ThemeAbrirTabla('CONSULTA UNIDADES DE COMPONENTES SANGUINEOS');
		$this->salida .="<script language='javascript'>";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$accion=ModuloGetURL('app','Banco_Sangre','user','ConsultaBolsasSangre');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<br><table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td align=\"center\">CONSULTA INVENTARIO DE UNIDADES SANGUINEAS</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_table_list_title\">";
		$this->salida .= "<td align=\"left\">CRITERIOS DE BUSQUEDA</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr>";
		$this->salida .= "<td class=\"modulo_list_claro\"  width=\"40%\" >";
		$this->salida .= "<table width=\"95%\" border=\"0\" align=\"center\" border=\"0\">";
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td class=\"label\">CODIGO BOLSA </td><td><input type=\"text\" class=\"input-text\" name=\"codigoBolsa\" value=\"".$codigoBolsa."\" maxlength=\"32\"></td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\" ><td class=\"label\">ABO / Rh</td><td><select name=\"grupoSanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactor();
		$this->GrupoSanguineo($facts,'False',$grupoSanguineo);
		$this->salida .= "</select></td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\"><td class=\"label\">COMPONENTE </td><td><select name=\"componente\" class=\"select\">";
		$componentes=$this->ConsultaComponente();
		$this->MostrasSelect($componentes,'False',$componente);
		$this->salida .= "</select></td></tr>";
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td class=\"label\">ALBARAN</td><td><input type=\"text\" class=\"input-text\" name=\"albaran\" value=\"".$albaran."\" maxlength=\"20\"></td></tr>";
		if($_REQUEST['DiaEspe']){
      $dia=$_REQUEST['DiaEspe'];
		}else{
      $dia=$_REQUEST['FechaVmto'];
		}
    //$this->salida .= "<tr><td class=\"label\">FECHA VENCIMIENTO</td><td><input type=\"text\" readonly class=\"input-text\" name=\"FechaVmto\" value = \"".$dia."\"></td></tr>";
    $this->salida .= "<tr class=\"modulo_list_oscuro\" ><td colspan=\"2\">";
		$this->salida .= "<table align=\"center\" width=\"90%\" border=\"0\">";
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td width=\"20%\" class=\"label\">ESTADO</td></tr>";
		if(empty($estado)){$estado='1';}
		$estados=$this->EstadoBolsasComponentes();
    for($i=0;$i<sizeof($estados);$i++){
		  $var='';
		  if($estados[$i]['estado']==$estado){
        $var='checked';
			}
			$this->salida .= "<tr class=\"modulo_list_oscuro\"><td class=\"label\">&nbsp;</td><td class=\"normal_10\"><input type=\"radio\" name=\"estado\" value=\"".$estados[$i]['estado']."\" $var>".$estados[$i]['descripcion']."</td></tr>";
		}
    $this->salida .= "</td></tr>";
    $this->salida .= "</table>";
		/*$this->salida .= "<tr class=\"modulo_list_oscuro\" ><td colspan=\"2\">";
		$this->salida .= "<table width=\"90%\" align=\"center\">";
		$var='';
		if($cruze==1){
      $var='checked';
		}
		$this->salida .= "<tr class=\"modulo_list_oscuro\"><td width=\"40%\" class=\"label\"><input type=\"radio\" name=\"cruze\" value=\"1\" $var>CRUZADAS</td></tr>";
    $this->salida .= "</td></tr>";
    $this->salida .= "</table>";*/

    $this->salida .= "<tr><td colspan = 2 align=\"center\">";
		$this->salida .= "<table>";
		$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar_Reserva\" value=\"BUSCAR\"></td>";
		$this->salida .= "</form>";
		$actionM=ModuloGetURL('app','Banco_Sangre','user','MenuConsultas');
		$this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td>";
		$this->salida .= "</form>";
		$this->salida .= "</tr>";
		$this->salida .= "</table></td></tr>";
		$this->salida .= "</table>";
		$this->salida .= "</td>";
		$this->salida .= "</tr>";
		$this->salida .= "</table><br>";
		$bolsasTot=$this->SeleccionBolsasComponentes($codigoBolsa,$grupoSanguineo,$componente,$FechaVmto,$estado,$cruze,$albaran);
		if($bolsasTot){
      $this->salida .= "  <table width=\"100%\" border=\"0\" align=\"center\">";
		  $this->salida .= "  <tr class=\"modulo_table_list_title\">";
      $this->salida .= "  <td>No. BOLSA</td>";
			$this->salida .= "  <td>SELLO</td>";
			$this->salida .= "  <td>COMPONENTE</td>";
			$this->salida .= "  <td width=\"5%\">GRUPO</td>";
			$this->salida .= "  <td width=\"5%\">Rh</td>";
			$this->salida .= "  <td>PROCEDENCIA</td>";
			$this->salida .= "  <td width=\"10%\">ALBARAN</td>";
			$this->salida .= "  <td width=\"10%\">FECHA VENCIMIENTO</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  <td>ALICUOTAS</td>";
			$this->salida .= "  <td>CANTIDAD</td>";
			$this->salida .= "  <td>ESTADO</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			foreach($bolsasTot as $IngresoB=>$vector){
        foreach($vector as $AlicuotaB=>$bolsas){
				  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				  if(sizeof($vector)==1 || $AlicuotaB==0){
          //for($i=0;$i<sizeof($bolsas);$i++){
						$this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['bolsa_id']."</td>";
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['sello_calidad']."</td>";
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['componente']."</td>";
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['grupo_sanguineo']."</td>";
						if($bolsas['rh']=='+'){
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">POSITIVO</td>";
						}elseif($bolsas['rh']=='-'){
            $this->salida .= "  <td class=\"label_error\" rowspan=\"".sizeof($vector)."\">NEGATIVO</td>";
						}
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['nombre_tercero']."</td>";
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['albaran']."</td>";
						$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">".$bolsas['fecha_vencimiento']."</td>";
            $actionEditar=ModuloGetURL('app','Banco_Sangre','user','EditarDatosComponente',array("componenteId"=>$bolsas['ingreso_bolsa_id'],
						"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componenteDes"=>$componente,"FechaVmto"=>$FechaVmto,"estado"=>$estado,
						"cruze"=>$cruze,"albaranDes"=>$albaran,
						"bolsa_id"=>$bolsas['bolsa_id'],"sello_calidad"=>$bolsas['sello_calidad'],"componente"=>$bolsas['tipo_componente'],"grupo_sanguineo"=>$bolsas['grupo_sanguineo'],"rh"=>$bolsas['rh'],
						"fecha_vencimiento"=>$bolsas['fecha_vencimiento'],"entidad_origen"=>$bolsas['entidad_origen'],"nombre_tercero"=>$bolsas['nombre_tercero'],"albaran"=>$bolsas['albaran'],"fecha_extraccion"=>$bolsas['fecha_extraccion']));
						$this->salida .= "	 <td align=\"center\" rowspan=\"".sizeof($vector)."\"><a href=\"$actionEditar\"><img title=\"Modificar\" border=\"0\" src=\"".GetThemePath()."/images/modificar.png\"><a></td>";
						if($bolsas['cruzada']!='1'){
              if($bolsas['sw_estado']=='6'){
								$this->salida .= "	 <td align=\"center\" class=\"label\">ERROR<br>DIGITACION</td>";
							}else{
                $actionError=ModuloGetURL('app','Banco_Sangre','user','registrarErrorDigitacion',array("BolsaIn"=>$bolsas['ingreso_bolsa_id'],"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componente"=>$componente,"FechaVmto"=>$FechaVmto,"estado"=>$estado,"cruze"=>$cruze,"albaran"=>$albaran));
								$this->salida .= "	 <td rowspan=\"".sizeof($vector)."\" align=\"center\"><a href=\"$actionError\"><img title=\"Eliminar por Error de Digitacion\" border=\"0\" src=\"".GetThemePath()."/images/error_digitacion.png\"><a></td>";
							}
						}else{
            $this->salida .= "	 <td rowspan=\"".sizeof($vector)."\" align=\"center\">&nbsp;</td>";
						}
						if($bolsas['cruzada']=='1'){
							$cruzadas=ModuloGetURL('app','Banco_Sangre','user','ConsultaPacientesCruzes',array("ingresoBolsa"=>$bolsas['ingreso_bolsa_id'],"BolsaCruze"=>$bolsas['bolsa_id'],"NumBolsa"=>$bolsas['bolsa_id'],
							"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componente"=>$componente,"FechaVmto"=>$FechaVmto,"estado"=>$estado,"cruze"=>$cruze));
							$this->salida .= "	 <td rowspan=\"".sizeof($vector)."\"><a href=\"$cruzadas\"><b>CRUZADAS</b></td>";
						}else{
							$this->salida .= "  <td rowspan=\"".sizeof($vector)."\">&nbsp;</td>";
						}
						if($bolsas['numero_alicuota']=='0' && $bolsas['sw_estado']==1){
							$alicuotar=ModuloGetURL('app','Banco_Sangre','user','AlicuotarBolsa',array("ingresoBolsa"=>$bolsas['ingreso_bolsa_id'],
							"bolsaId"=>$bolsas['bolsa_id'],"sello"=>$bolsas['sello_calidad'],"componenteId"=>$bolsas['componente'],
							"FVence"=>$bolsas['fecha_vencimiento'],"Grupo"=>$bolsas['grupo_sanguineo'],"rhId"=>$bolsas['rh'],"Procedencia"=>$bolsas['nombre_tercero'],
							"cantidadPrincipal"=>$bolsas['cantidad'],"codigoBolsaBuscar"=>$codigoBolsa,"grupoSanguineoBuscar"=>$grupoSanguineo,"componenteBuscar"=>$componente,"FechaVmtoBuscar"=>$FechaVmto,"estadoBuscar"=>$estado,"cruzeBuscar"=>$cruze));
							$this->salida .= "  <td><a class=\"Menu\" href=\"$alicuotar\"><b>PRINCIPAL</b></td>";
							$this->salida .= "  <td>".$bolsas['cantidad']."&nbsp;ml.</td>";
							$this->salida .= "  <td>".$bolsas['nomestado']."</td>";
						}else{
							if($bolsas['sw_estado']=='4'){
								$class='label_error';
							}elseif($bolsas['sw_estado']=='3'){
								$class='label_mark';
							}else{
								$class='';
							}
							$this->salida .= "  <td><label class=\"label\">".$bolsas['numero_alicuota']."</label></td>";
							$this->salida .= "  <td>".$bolsas['cantidad']."&nbsp;ml.</td>";
							$this->salida .= "  <td class=\"$class\">".$bolsas['nomestado']."</td>";
						}
						if($bolsas['sw_estado']=='1' || $bolsas['sw_estado']=='5'){
							$actionBaja=ModuloGetURL('app','Banco_Sangre','user','DardeBajaBolsaSangre',array("BolsaIn"=>$bolsas['ingreso_bolsa_id'],
							"bolsaId"=>$bolsas['bolsa_id'],"sello"=>$bolsas['sello_calidad'],"componenteId"=>$bolsas['componente'],
							"Grupo"=>$bolsas['grupo_sanguineo'],"rhId"=>$bolsas['rh'],"Procedencia"=>$bolsas['nombre_tercero'],"FVence"=>$bolsas['fecha_vencimiento'],"alicuota"=>$bolsas['numero_alicuota']));
							$this->salida .= "	 <td align=\"center\"><a href=\"$actionBaja\"><img title=\"Incinerar\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
						}else{
						$this->salida .= "	 <td>&nbsp;</td>";
						}
						$this->salida .= "  </tr>";
						$y++;
						$cabecera=1;
					}else{
					  $this->salida .= "  <tr class=\"$estilo\">";
						if($cabecera!=1){
              $this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
              $this->salida .= "  <td>&nbsp;</td>";
							$this->salida .= "  <td>&nbsp;</td>";
						}
						if($bolsas['numero_alicuota']=='0' && $bolsas['sw_estado']==1){
							$alicuotar=ModuloGetURL('app','Banco_Sangre','user','AlicuotarBolsa',array("ingresoBolsa"=>$bolsas['ingreso_bolsa_id'],
							"bolsaId"=>$bolsas['bolsa_id'],"sello"=>$bolsas['sello_calidad'],"componenteId"=>$bolsas['componente'],
							"FVence"=>$bolsas['fecha_vencimiento'],"Grupo"=>$bolsas['grupo_sanguineo'],"rhId"=>$bolsas['rh'],"Procedencia"=>$bolsas['nombre_tercero'],
							"cantidadPrincipal"=>$bolsas['cantidad'],"codigoBolsaBuscar"=>$codigoBolsa,"grupoSanguineoBuscar"=>$grupoSanguineo,"componenteBuscar"=>$componente,"FechaVmtoBuscar"=>$FechaVmto,"estadoBuscar"=>$estado,"cruzeBuscar"=>$cruze));
							$this->salida .= "  <td><a class=\"Menu\" href=\"$alicuotar\"><b>PRINCIPAL</b></td>";
							$this->salida .= "  <td>".$bolsas['cantidad']."&nbsp;ml.</td>";
							$this->salida .= "  <td>".$bolsas['nomestado']."</td>";
						}else{
							if($bolsas['sw_estado']=='4'){
								$class='label_error';
							}elseif($bolsas['sw_estado']=='3'){
								$class='label_mark';
							}else{
								$class='';
							}
							$this->salida .= "  <td><label class=\"label\">".$bolsas['numero_alicuota']."</label></td>";
							$this->salida .= "  <td>".$bolsas['cantidad']."&nbsp;ml.</td>";
							$this->salida .= "  <td class=\"$class\">".$bolsas['nomestado']."</td>";
						}
						if($bolsas['sw_estado']=='1'){
							$actionBaja=ModuloGetURL('app','Banco_Sangre','user','DardeBajaBolsaSangre',array("BolsaIn"=>$bolsas['ingreso_bolsa_id'],
							"bolsaId"=>$bolsas['bolsa_id'],"sello"=>$bolsas['sello_calidad'],"componenteId"=>$bolsas['componente'],
							"Grupo"=>$bolsas['grupo_sanguineo'],"rhId"=>$bolsas['rh'],"Procedencia"=>$bolsas['nombre_tercero'],"FVence"=>$bolsas['fecha_vencimiento'],"alicuota"=>$bolsas['numero_alicuota']));
							$this->salida .= "	 <td align=\"center\"><a href=\"$actionBaja\"><img border=\"0\" title=\"Incinerar\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
						}else{
						$this->salida .= "	 <td>&nbsp;</td>";
						}
						$this->salida .= "  </tr>";
					}
				}
			}
			$this->salida .= "  </table>";
			$this->salida .=$this->RetornarBarra(1);
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaAlicuotarBolsa($ingresoBolsa,$bolsaId,$sello,$componenteId,$FVence,$Grupo,$rhId,$Procedencia,$cantidadPrincipal,
	$codigoBolsaBuscar,$grupoSanguineoBuscar,$componenteBuscar,$FechaVmtoBuscar,$estadoBuscar,$cruzeBuscar)
	{
    $this->salida.= ThemeAbrirTabla('COMPONENTE SANGUINEO');
		$accion=ModuloGetURL('app','Banco_Sangre','user','GuardarAlicuota',array("ingresoBolsa"=>$ingresoBolsa,"bolsaId"=>$bolsaId,"sello"=>$sello,"componenteId"=>$componenteId,"FVence"=>$FVence,"Grupo"=>$Grupo,"rhId"=>$rhId,"Procedencia"=>$Procedencia,
		"cantidadPrincipal"=>$cantidadPrincipal,"codigoBolsaBuscar"=>$codigoBolsaBuscar,"grupoSanguineoBuscar"=>$grupoSanguineoBuscar,"componenteBuscar"=>$componenteBuscar,"FechaVmtoBuscar"=>$FechaVmtoBuscar,"estadoBuscar"=>$estadoBuscar,"cruzeBuscar"=>$cruzeBuscar));
		$this->salida .= "  <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "	 <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	 </td><tr>";
		$this->salida .= "   <tr><td></td></tr>";
		$this->salida .= "   <tr><td><fieldset><legend class=\"field\">DATOS DEL COMPONENTE SANGUINEO</legend>";
    $this->salida .= "  <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">No. BOLSA</td>";
    $this->salida .= "  <td>".$bolsaId."</td>";
		$this->salida .= "  <td class=\"label\">SELLO</td>";
    $this->salida .= "  <td>".$sello."</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">COMPONENTE</td>";
    $this->salida .= "  <td>".$componenteId."</td>";
		$this->salida .= "  <td class=\"label\" width=\"10%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "  <td>".$FVence."</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\" width=\"5%\">GRUPO</td>";
    $this->salida .= "  <td>".$Grupo."</td>";
		$this->salida .= "  <td class=\"label\" width=\"5%\">Rh</td>";
		if($rhId=='+'){
    $this->salida .= "  <td>POSITIVO</td>";
		}elseif($rhId=='-'){
    $this->salida .= "  <td class=\"label_error\">NEGATIVO</td>";
		}
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">PROCEDENCIA</td>";
    $this->salida .= "  <td colspan=\"3\">".$Procedencia."</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "  <td colspan=\"2\" class=\"label\" width=\"5%\">CANTIDAD BOLSA PRINCIPAL</td>";
		if($cantidadPrincipal==0){
		  if(!$_REQUEST['cantidadTotal']){
        $_REQUEST['cantidadTotal']=250;
			}
			$this->salida .= "  <td class=\"label\" colspan=\"2\"><input size=\"4\" type=\"text\" name=\"cantidadTotal\" value=\"".$_REQUEST['cantidadTotal']."\" class=\"input-submit\">&nbsp;ml.</td>";
		}else{
      $this->salida .= "  <td class=\"label\" colspan=\"2\"><input size=\"4\" type=\"text\" name=\"cantidadTotal\" value=\"".$cantidadPrincipal."\" class=\"input-submit\">&nbsp;ml.</td>";
		}

    $this->salida .= "  </tr>";
		$numeroAlicuotas=$this->SeleccionNumeroAliciotas($ingresoBolsa);
		$this->salida .= "  <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "  <td colspan=\"2\" class=\"label\">CANTIDAD ALICUOTA No.&nbsp;".$numeroAlicuotas['suma']."</td>";
    $this->salida .= "  <input type=\"hidden\" value=\"".$numeroAlicuotas['suma']."\" name=\"numeroAlicuota\">";
    $this->salida .= "  <td colspan=\"2\"><input size=\"4\" class=\"text-input\" type=\"text\" name=\"cantidad\" value=\"".$_REQUEST['cantidad']."\"><b>&nbsp;ml.</b></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td colspan=\"4\" align=\"center\" class=\"label\">";
		$this->salida .= "  <input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida .= "  <input type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\" class=\"input-submit\">";
		$this->salida .= "  </td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		$this->salida .= "	</fieldset></td></tr></table><BR>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SeleccionMotivoDarBajaSangre($BolsaIn,$bolsas,$bolsaId,$sello,$componenteId,
		$Grupo,$rhId,$Procedencia,$FVence,$alicuota){
    if(empty($_REQUEST['FechaDevuelve'])){
      $_REQUEST['FechaDevuelve']=date('d-m-Y');
			$_REQUEST['horaPrueba']=date('H');
			$_REQUEST['minutosPrueba']=date('i');
		}
	  $this->salida.= ThemeAbrirTabla('COMPONENTE SANGUINEO');
		$this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "   <tr><td></td></tr>";
		$this->salida .= "   <tr><td><fieldset><legend class=\"field\">DATOS DEL COMPONENTE SANGUINEO</legend>";
    $this->salida .= "  <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">No. BOLSA</td>";
    $this->salida .= "  <td>".$bolsaId."</td>";
		$this->salida .= "  <td class=\"label\">SELLO</td>";
    $this->salida .= "  <td>".$sello."</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">COMPONENTE</td>";
    $this->salida .= "  <td>".$componenteId."</td>";
		$this->salida .= "  <td class=\"label\" width=\"10%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "  <td>".$FVence."</td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\" width=\"5%\">GRUPO</td>";
    $this->salida .= "  <td>".$Grupo."</td>";
		$this->salida .= "  <td class=\"label\" width=\"5%\">Rh</td>";
		if($rhId=='+'){
      $this->salida .= "  <td>POSITIVO</td>";
		}elseif($rhId=='-'){
      $this->salida .= "  <td class=\"label_error\">NEGATIVO</td>";
		}
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">PROCEDENCIA</td>";
    $this->salida .= "  <td colspan=\"3\">".$Procedencia."</td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		$this->salida .= "	</fieldset></td></tr></table><BR>";
		$accion=ModuloGetURL('app','Banco_Sangre','user','InsertarMotivoBajaSangre',array("BolsaIn"=>$BolsaIn,"bolsas"=>$bolsas,"alicuota"=>$alicuota));
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <BR><table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\">";
		$this->salida .= "  <td colspan=\"3\" align=\"center\">SELECCION DEL MOTIVO DE BAJA DE LA UNIDAD</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"label\">MOTIVO</td>";
		$this->salida .= "  <td colspan=\"2\"><select name=\"motivo\" class=\"select\">";
		$motivos=$this->MotivosdeBaja();
		$this->MostrasSelect($motivos,'False',$motivo);
		$this->salida .= "  </select></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\" width=\"5%\">PERSONA QUE DEVUELVE</td>";
    $this->salida .= "  <td colspan=\"2\"><input size=\"40\" type=\"text\" class=\"input-text\" name=\"presonaDevuelve\"></td>";
    $this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\" width=\"5%\">FECHA ENTREGA</td>";
		$this->salida .= "	 <td><input size=\"10\" type=\"text\" name=\"FechaDevuelve\" value=\"".$_REQUEST['FechaDevuelve']."\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">";
		$this->salida .= "	 &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formabuscar','FechaDevuelve','/')."</td>";
	  $this->salida.="            <td><select size=\"1\" name=\"horaPrueba\" class=\"select\" $desabilitar>";
		$this->salida.="            <option value = -1>Hora</option>";
	  for ($j=0;$j<=23; $j++){
      if(($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST['horaPrueba']==$hora){
				  $this->salida.="      <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="      <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST['horaPrueba']==$j){
					$this->salida.="      <option selected value = $j>$j</option>";
				}else{
					$this->salida.="      <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="            </select>&nbsp;";
		$this->salida.="            <select size=\"1\"  name=\"minutosPrueba\" class=\"select\" $desabilitar>";
	  $this->salida.="            <option value = -1>Minutos</option>";
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST['minutosPrueba']==$min){
					$this->salida.="      <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="      <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST['minutosPrueba']==$j){
					$this->salida.="      <option selected value=$j>$j</option>";
				}else{
					$this->salida.="      <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="            </select>";
    $this->salida .= "  </tr>";
		$this->salida .= "	 <tr class=\"modulo_list_claro\"><td class=\"label\" colspan=\"3\">OBSERVACIONES<BR><textarea name=\"observaciones\" cols=\"80\" rows=\"3\" class=\"textarea\"></textarea></td></tr>";
    $this->salida .= "  </table><BR>";
		$this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"ACEPTAR\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"CANCELAR\">";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ListadoComponentesAVencer(){
	  $this->salida.= ThemeAbrirTabla('UNIDADES PROXIMAS A LA FECHA DE VENCIMIENTO');
		$accion=ModuloGetURL('app','Banco_Sangre','user','MenuConsultas');
		$this->salida .= "   <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$bolsas=$this->ComponentesCercaAVencer();
		if($bolsas){
		$this->salida .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\">";
		$this->salida .= "    <td width=\"10%\">FECHA VENCIMIENTO</td>";
		$this->salida .= "    <td>No. BOLSA</td>";
		$this->salida .= "    <td>SELLO</td>";
		$this->salida .= "    <td>COMPONENTE</td>";
		$this->salida .= "    <td width=\"5%\">GRUPO</td>";
		$this->salida .= "    <td width=\"5%\">Rh</td>";
		$this->salida .= "    <td>PROCEDENCIA</td>";
		$this->salida .= "    </tr>";
		$y=0;
		for($i=0;$i<sizeof($bolsas);$i++){
			if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida .= "  <tr class=\"$estilo\">";
			$this->salida .= "  <td>".$bolsas[$i]['fecha_vencimiento']."</td>";
			$this->salida .= "  <td>".$bolsas[$i]['bolsa_id']."</td>";
			$this->salida .= "  <td>".$bolsas[$i]['sello_calidad']."</td>";
			$this->salida .= "  <td>".$bolsas[$i]['componente']."</td>";
			$this->salida .= "  <td>".$bolsas[$i]['grupo_sanguineo']."</td>";
			if($bolsas[$i]['rh']=='+'){
        $this->salida .= "  <td>POSITIVO</td>";
			}elseif($bolsas[$i]['rh']=='-'){
        $this->salida .= "  <td class=\"label_error\">NEGATIVO</td>";
			}
      $this->salida .= "  <td>".$bolsas[$i]['nombre_tercero']."</td>";
			$this->salida .= "  </tr>";
			$y++;
		}
		$this->salida .= "  </table><BR>";
		}else{
      $this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"40%\" align=\"center\">";
			$this->salida .= "  <tr><td align=\"center\" class=\"label_error\">NO EXISTEN UNIDADES SANGUINEAS PROXIMAS A VENCERSE</td></tr>";
			$this->salida .= "  </table>";
		}
		$this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"40%\" align=\"center\">";
    $this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"Aceptar\" value=\"REGRESAR\">";
		$this->salida .= "  </td></tr>";
    $this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

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

	 function RetornarBarra($origen){

		if($this->limit>=$this->conteo){
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(empty($paso)){
			$paso=1;
		}
		if($origen==1){
		  $cabecera='';
      $accion=ModuloGetURL('app','Banco_Sangre','user','ConsultaBolsasSangre',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"codigoBolsa"=>$_REQUEST['codigoBolsa'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"componente"=>$_REQUEST['componente'],"FechaVmto"=>$_REQUEST['FechaVmto'],"estado"=>$_REQUEST['estado'],"cruze"=>$_REQUEST['cruze']));
		}elseif($origen==2){
			$accion=ModuloGetURL('app','Banco_Sangre','user','LlamaSolicitudExternaDetalle',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"entidadExterna"=>$_REQUEST['entidadExterna'],"nombreEntidad"=>$_REQUEST['nombreEntidad']));
		}elseif($origen==3){
      $accion=ModuloGetURL('app','Banco_Sangre','user','LlamaSeleccionComponenteSangre',array('conteo'=>$this->conteo,"paso"=>$_REQUEST['paso'],"Of"=>$_REQUEST['Of'],"TipoDocumento"=>$_REQUEST['TipoDocumento'],"Documento"=>$_REQUEST['Documento'],"bolsa"=>$_REQUEST['bolsa'],"grupoSan"=>$_REQUEST['grupoSan'],"rh"=>$_REQUEST['rh'],"bolsaNum"=>$_REQUEST['bolsaNum'],"tipoComponente"=>$_REQUEST['tipoComponente'],"nombre"=>$_REQUEST['nombre'],
			"componenteDes"=>$_REQUEST['componenteDes'],"componenteId"=>$_REQUEST['componenteId'],"solicitudId"=>$_REQUEST['solicitudId'],"alicuota"=>$_REQUEST['alicuota'],"cantidadAli"=>$_REQUEST['cantidadAli']));
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1){
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
			$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
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
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
		}
		$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan='15' align='center'>Página $paso de $numpasos</td><tr></table>";
	}

	function SolicitudExterna($entidades){
		$this->salida .= ThemeAbrirTabla('ENTIDAD SOLICITANTE');
		//$this->salida .= "			      <br><br>";
    $action=ModuloGetURL('app','Banco_Sangre','user','BusquedaEntidadSolicitante');
		$this->salida .= "  <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\">";
		$this->salida .= "  <td colspan=\"6\" align=\"center\">DATOS DE LA PROCEDENCIA</td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td><label class=\"label\">DESCRIPCION</label></td><td><input size=\"50\" class=\"text-input\" type=\"text\" name=\"descipcion_sgsssBus\"></td>";
		$this->salida .= "  <td><label class=\"label\">CODIGO</label></td><td><input size=\"6\" class=\"text-input\" type=\"text\" name=\"codigo_sgsssBus\"></td>";
		$this->salida .= "  <td align=\"center\" colspan=\"2\" valign=\"bottom\"><input type=\"submit\" class=\"input-submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		if($entidades){
      $this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		  $this->salida .= "  <tr class=\"modulo_table_title\">";
      $this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($entidades);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
				$this->salida .= "  <td>".$entidades[$i]['codigo_sgsss']."</td>";
				$this->salida .= "  <td>".$entidades[$i]['nombre_tercero']."</td>";
				$action=ModuloGetURL('app','Banco_Sangre','user','LlamaSolicitudExternaDetalle',array("entidadExterna"=>$entidades[$i]['codigo_sgsss'],"nombreEntidad"=>$entidades[$i]['nombre_tercero']));
				$this->salida .= "	 <td><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table>";
		}
		$this->salida .= "	</form>";
		$action1=ModuloGetURL('app','Banco_Sangre','user','MenuConsultas');
		$this->salida .= "  <form name=\"forma\" action=\"$action1\" method=\"post\">";
		$this->salida .= "  <table width=\"60%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"REGRESAR\" name=\"regresar\"></td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "	</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SolicitudExternaDetalle($entidadExterna,$nombreEntidad,$codigoBolsa,$grupoSanguineo,$componente){

		$this->salida.= ThemeAbrirTabla('SOLICITUD EXTERNA DE UNIDADES');
		$accion=ModuloGetURL('app','Banco_Sangre','user','BusquedaFiltroBolsas',array("entidadExterna"=>$entidadExterna,"nombreEntidad"=>$nombreEntidad,
		"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componente"=>$componente));
		$this->salida .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "	        <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	      	</td><tr>";
		$this->salida .= "    <tr><td></td></tr>";
		$this->salida .= "    <tr><td><fieldset><legend class=\"field\">DATOS UNIDADES</legend>";

		$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td width=\"60%\">";
		$this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\">";
		$this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PARAMETROS DE BUSQUEDA DE UNIDADES</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">CODIGO BOLSA </td><td><input type=\"text\" class=\"input-text\" name=\"codigoBolsa\" value=\"".$codigoBolsa."\" maxlength=\"32\"></td></tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\" ><td class=\"label\">ABO / Rh</td><td><select name=\"grupoSanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactor();
		$this->GrupoSanguineo($facts,'False',$grupoSanguineo);
		$this->salida .= "    </select></td></tr>";
    $this->salida .= "    <tr class=\"modulo_list_claro\"><td class=\"label\">COMPONENTE </td><td><select name=\"componente\" class=\"select\">";
		$componentes=$this->ConsultaComponente();
		$this->MostrasSelect($componentes,'False',$componente);
		$this->salida .= "    </select></td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"filtrar\" value=\"FILTRAR\"></td></tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    </td>";
		$this->salida .= "    </form>";
    $accion=ModuloGetURL('app','Banco_Sangre','user','GuardarSolicitudExterna',array("entidadExterna"=>$entidadExterna,"nombreEntidad"=>$nombreEntidad,
		"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componente"=>$componente));
		$this->salida .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "    <td valign=\"top\">";
    $this->salida .= "    <table width=\"98%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr class=\"modulo_table_list_title\">";
    $this->salida .= "    <td colspan=\"2\" align=\"center\">ENTIDAD SOLICITANTE:<BR> ".$nombreEntidad."</td>";
    $this->salida .= "    </tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"5%\"><input type=\"radio\" name=\"motivo\" checked value=\"V\"></td><td class=\"label\">VENTA</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"5%\"><input type=\"radio\" name=\"motivo\" value=\"P\"></td><td class=\"label\">PRESTAMO</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td width=\"5%\"><input type=\"radio\" name=\"motivo\" value=\"D\"><td class=\"label\">DEVOLUCION</td></tr>";
		$this->salida .= "    <tr class=\"modulo_list_claro\"><td colspan=\"2\" width=\"5%\">&nbsp;</td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "	  </fieldset>";
		$this->salida .= "	  </td></tr>";
		$this->salida .= "	  </table><BR>";
		$this->salida .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
    $unidades=$this->TotalUnidadesSanguineas($codigoBolsa,$grupoSanguineo,$componente);
		if($unidades){
      $this->salida .= "  <table width=\"90%\" border=\"0\" align=\"center\">";
		  $this->salida .= "  <tr class=\"modulo_table_list_title\">";
      $this->salida .= "  <td>No. BOLSA</td>";
			$this->salida .= "  <td>SELLO</td>";
			$this->salida .= "  <td>COMPONENTE</td>";
			$this->salida .= "  <td width=\"5%\">GRUPO</td>";
			$this->salida .= "  <td width=\"5%\">Rh</td>";
			$this->salida .= "  <td>PROCEDENCIA</td>";
			$this->salida .= "  <td width=\"10%\">FECHA VENCIMIENTO</td>";
			$this->salida .= "  <td width=\"10%\">No. ALICUOTA</td>";
			$this->salida .= "  <td width=\"10%\">CANTIDAD</td>";
			$this->salida .= "  <td width=\"5%\">&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($unidades);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
				$this->salida .= "  <td>".$unidades[$i]['bolsa_id']."</td>";
				$this->salida .= "  <td>".$unidades[$i]['sello_calidad']."</td>";
				$this->salida .= "  <td>".$unidades[$i]['componente']."</td>";
				$this->salida .= "  <td>".$unidades[$i]['grupo_sanguineo']."</td>";
				if($unidades[$i]['rh']=='+'){
				  $this->salida .= "  <td>POSITIVO</td>";
				}elseif($unidades[$i]['rh']=='-'){
          $this->salida .= "  <td class=\"label_error\">NEGATIVO</td>";
				}
				$this->salida .= "  <td>".$unidades[$i]['nombre_tercero']."</td>";
				$this->salida .= "  <td>".$unidades[$i]['fecha_vencimiento']."</td>";
				$this->salida .= "  <td>".$unidades[$i]['numero_alicuota']."</td>";
				$this->salida .= "  <td>".$unidades[$i]['cantidad']."</td>";
				$this->salida .= "  <td align=\"center\"><input type=\"checkbox\" name=\"seleccion[]\" value=\"".$unidades[$i]['ingreso_bolsa_id']."|/".$unidades[$i]['numero_alicuota']."\"></td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table>";
			$this->salida .= "  <table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .= "  <tr><td align=\"right\"><input class=\"input-submit\" type=\"submit\" value=\"SOLICITAR\" name=\"ingresar\"></td></tr>";
			$this->salida .= "  </table>";
			$this->salida .=$this->RetornarBarra(2);
		}
    if($_SESSION['BANCO']['SANGRE']['SOLICITUDEXT']){
			$unidadesSolicitadas=$this->UnidadesSolicitadas();
			if($unidadesSolicitadas){
				$this->salida .= "  <BR><table width=\"70%\" border=\"0\" align=\"center\">";
				$this->salida .= "  <tr class=\"modulo_table_list_title\">";
				$this->salida .= "  <td colspan=\"6\">UNIDADES DE LA SOLICITUD</td>";
        $this->salida .= "  </tr>";
				$this->salida .= "  <tr class=\"modulo_table_list_title\">";
				$this->salida .= "  <td>No. BOLSA</td>";
				//$this->salida .= "  <td>SELLO</td>";
				$this->salida .= "  <td>COMPONENTE</td>";
				$this->salida .= "  <td width=\"5%\">GRUPO</td>";
				$this->salida .= "  <td width=\"5%\">Rh</td>";
				$this->salida .= "  <td>No. ALICUOTA</td>";
				//$this->salida .= "  <td>PROCEDENCIA</td>";
				//$this->salida .= "  <td width=\"10%\">FECHA VENCIMIENTO</td>";
				$this->salida .= "  <td width=\"5%\">&nbsp;</td>";
				$this->salida .= "  </tr>";
				$y=0;
				for($i=0;$i<sizeof($unidadesSolicitadas);$i++){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida .= "  <tr class=\"$estilo\">";
					$this->salida .= "  <td>".$unidadesSolicitadas[$i]['bolsa_id']."</td>";
					//$this->salida .= "  <td>".$unidadesSolicitadas[$i]['sello_calidad']."</td>";
					$this->salida .= "  <td>".$unidadesSolicitadas[$i]['componente']."</td>";
					$this->salida .= "  <td>".$unidadesSolicitadas[$i]['grupo_sanguineo']."</td>";
					$this->salida .= "  <td>".$unidadesSolicitadas[$i]['rh']."</td>";
					$this->salida .= "  <td>".$unidadesSolicitadas[$i]['numero_alicuota']."</td>";
					//$this->salida .= "  <td>".$unidadesSolicitadas[$i]['nombre_tercero']."</td>";
					//$this->salida .= "  <td>".$unidadesSolicitadas[$i]['fecha_vencimiento']."</td>";
					$action=ModuloGetURL('app','Banco_Sangre','user','EliminaBolsaSolicitud',array("bolsa"=>$unidadesSolicitadas[$i]['ingreso_bolsa_id'],"alicuota"=>$unidadesSolicitadas[$i]['numero_alicuota'],"entidadExterna"=>$_REQUEST['entidadExterna'],"nombreEntidad"=>$_REQUEST['nombreEntidad']));
					$this->salida .= "  <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
					$this->salida .= "  </tr>";
					$y++;
				}
				$this->salida .= "  </table>";
			}
		}
		$this->salida .= "  <BR><table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" value=\"SALIR\" name=\"salir\"></td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaConsultaPacientesCruzes($ingresoBolsa,$BolsaCruze,$NumBolsa,$codigoBolsa,$grupoSanguineo,$componente,
		$FechaVmto,$estado,$cruze){

		$this->salida.= ThemeAbrirTabla('PACIENTES CRUZADOS CON LA BOLSA No. '.$NumBolsa);
		$accion=ModuloGetURL('app','Banco_Sangre','user','ConsultaBolsasSangre',array("ingresoBolsa"=>$ingresoBolsa,"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componente"=>$componente,
		"FechaVmto"=>$FechaVmto,"estado"=>$estado,"cruze"=>$cruze));
		$this->salida .= " <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $cruzes=$this->TotalCruzesUnidadesSanguineas($ingresoBolsa);
		if($cruzes){
      $this->salida .= "        <table width=\"85%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr class=\"modulo_table_title\">";
			$this->salida .= "        <td>PACIENTE</td>";
			$this->salida .= "        <td>FECHA PRUEBA</td>";
			$this->salida .= "        <td>BOLSA</td>";
      $this->salida .= "        <td>AOB / Rh</td>";
			$this->salida .= "        <td>PROFESIONAL CRUCE</td>";
			$this->salida .= "        <td>COMPATIBLE</td>";
			$this->salida .= "        <td>&nbsp;</td>";
			$this->salida .= "        </tr>";
			$y=0;
			for($i=0;$i<sizeof($cruzes);$i++){
				if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida .= "        <tr class=\"$estilo\">";
				$this->salida .= "        <td>".$cruzes[$i]['tipo_id_paciente']." ".$cruzes[$i]['paciente_id']." - ".$cruzes[$i]['nombre']."</td>";
				$this->salida .= "        <td>".$cruzes[$i]['fecha_prueba']."</td>";
				$this->salida .= "        <td>".$cruzes[$i]['bolsa_id']."</td>";
				if($cruzes[$i]['rh']=='+'){
        $this->salida .= "        <td>".$cruzes[$i]['grupo_sanguineo']." / POSITIVO</td>";
				}elseif($cruzes[$i]['rh']=='-'){
        $this->salida .= "        <td>".$cruzes[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
				}
				$this->salida .= "        <td>".$cruzes[$i]['profesional']."</td>";
				if($cruzes[$i]['compatibilidad']==1){
					$pal='Si';
					$clase='';
				}else{
				  $clase='label_error';
					$pal='No';
				}
				$this->salida .= "        <td class=\"$clase\">$pal</td>";
				$action=ModuloGetURL('app','Banco_Sangre','user','ConsultaCruzeSangre',array("ingresoBolsa"=>$ingresoBolsa,"cruzeid"=>$cruzes[$i]['cruze_sanguineo_id'],"BolsaCruze"=>$BolsaCruze,"NumBolsa"=>$NumBolsa,
				"codigoBolsa"=>$codigoBolsa,"grupoSanguineo"=>$grupoSanguineo,"componente"=>$componente,"FechaVmto"=>$FechaVmto,"estado"=>$estado,"cruze"=>$cruze));
				$this->salida .= "	      <td><a href=\"$action\"><img title=\"Ver Detalle\" border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\"><a></td>";
				$this->salida .= "        </tr>";
			}
			$this->salida .= "		    </table><BR>";
		}
		$this->salida .= "        <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "		    <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"regresar\" value=\"REGRESAR\"></td></tr>";
    $this->salida .= "		    </table>";
    $this->salida .= " </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaResultadosCruze($bolsa,$tipoId,$paciente,$nombre,$fechaReserva,$responsable,$grupo,$rh,$reservaId,
		$bolsaNum,$sello,$fechaVence,$grupoBolsa,$rhBolsa,$nomTercero,$fechaExtraccion,$consulta){

    if($consulta==1){
      $desabilitar='disabled';
		}
		$this->salida .= ThemeAbrirTabla('REGISTRO RESULTADO COMPATIBLIDAD');
		$accion=ModuloGetURL('app','Banco_Sangre','user','ConsultaPacientesCruzes',array("ingresoBolsa"=>$_REQUEST['ingresoBolsa'],"BolsaCruze"=>$_REQUEST['BolsaCruze'],"NumBolsa"=>$_REQUEST['NumBolsa'],"codigoBolsa"=>$_REQUEST['codigoBolsa'],"grupoSanguineo"=>$_REQUEST['grupoSanguineo'],"componente"=>$_REQUEST['componente'],
		"FechaVmto"=>$_REQUEST['FechaVmto'],"estado"=>$_REQUEST['estado'],"cruze"=>$_REQUEST['cruze']));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida .= "  <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "<tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "</td><tr>";
		$this->salida .= "  <tr><td></td></tr>";
		$this->salida .= "  <tr><td><fieldset><legend class=\"field\">DATOS PRINCIPALES</legend>";
		$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td width=\"50%\">";
    $this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_title\"><td colspan=\"4\">DATOS DEL PACIENTE</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">PACIENTE</td>";
    $this->salida .= "        <td colspan=\"3\">".$tipoId." ".$paciente." ".$nombre."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">FECHA RESERVA</td>";
    $this->salida .= "        <td>".$fechaReserva."</td>";
		$this->salida .= "        <td width=\"25%\" class=\"label\">ABO / Rh</td>";
		if($rh=='+'){
    $this->salida .= "        <td>".$grupo." /&nbsp&nbsp&nbsp;POSITIVO</td>";
		}elseif($rh=='-'){
    $this->salida .= "        <td>".$grupo." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "        <td>".$grupo."</td>";
		}
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td width=\"25%\" class=\"label\">RESPONSABLE</td>";
    $this->salida .= "        <td colspan=\"3\">".$responsable."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "  </td></tr>";
		$this->salida .= "  <tr><td width=\"50%\">";
		$this->salida .= "        <table width=\"100%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_title\"><td colspan=\"4\">DATOS DE LA UNIDAD SANGUINEA</td></tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">NUMERO BOLSA</td>";
    $this->salida .= "        <td>".$bolsaNum."</td>";
		$this->salida .= "        <td class=\"label\">SELLO</td>";
    $this->salida .= "        <td>".$sello."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">ABO / Rh</td>";
		if($rhBolsa=='+'){
    $this->salida .= "        <td>".$grupoBolsa." /&nbsp&nbsp&nbsp;POSITIVO</td>";
		}elseif($rhBolsa=='-'){
    $this->salida .= "        <td>".$grupoBolsa." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
		}else{
    $this->salida .= "        <td>".$grupoBolsa."</td>";
		}
		$this->salida .= "        <td  class=\"label\">FECHA VENCIMIENTO</td>";
    $this->salida .= "        <td>".$fechaVence."</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">PROCEDENCIA</td>";
    $this->salida .= "        <td colspan=\"3\">".$nomTercero."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\">";
		$this->salida .= "        <td class=\"label\">FECHA EXTRACCION</td>";
    $this->salida .= "        <td colspan=\"3\">".$fechaExtraccion."</td>";
		$this->salida .= "        </tr>";
		$this->salida .= "        </table>";
		$this->salida .= "	      </fieldset></td></tr></table>";
    $this->salida .= "  </td></tr>";
		$this->salida .= "  </table><BR>";
		$this->salida .= "    <table width=\"85%\" border=\"0\" align=\"center\">";
		(list($dia,$mes,$ano)=explode('/',$_REQUEST['fechaPrueba']));
		$FechaConver1=mktime($_REQUEST['horaPrueba'],$_REQUEST['minutosPrueba'],0,$mes,$dia,$ano);
		$this->salida .= "    <tr class=\"modulo_table_title\"><td align=\"center\">RESULTADO DEL CRUCE</td></tr><tr class=\"modulo_table_title\"><td align=\"center\">FECHA PRUEBA&nbsp&nbsp&nbsp&nbsp;".strftime("%A %d de  %B de %Y a las %H Horas con %M minutos",$FechaConver1)."</td></tr>";
    $this->salida .= "    <tr><td class=\"modulo_list_oscuro\">";
		$this->salida .= "        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"2\">HEMOCLASIFICACION DEL PACIENTE</td></tr>";
    $this->salida .= "        <tr class=\"modulo_list_claro\"><td valign=\"top\">";
    $this->salida .= "            <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">MANUAL</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI A</td>";
		if($_REQUEST['hemoclasifyManualA']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualA_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyManualB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI B</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
		if($_REQUEST['hemoclasifyManualAB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualAB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI AB</td>";
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyManualD']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyManualD_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI D</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\"><td class=\"label\" align=\"center\" colspan=\"4\">&nbsp;</td></tr>";
		(list($grupoManual,$rhManual)=explode('/',$_REQUEST['grupoManual']));
		if($rhManual=='-'){$class='label_error';}else{$class='';}
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ABO</td>";
		$this->salida .= "            <td>$grupoManual</td>";
		$this->salida .= "            <td class=\"label\">Rh</td>";
		$this->salida .= "            <td class=\"$class\">&nbsp&nbsp;$rhManual</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"4\"><label class=\"label\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            ".$_REQUEST['profesionalmanual']."</td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "        </td><td>";
    $this->salida .= "            <table width=\"90%\" border=\"0\" align=\"center\">";
		$this->salida .= "            <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CON GEL</td></tr>";
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
    if($_REQUEST['hemoclasifyGelA']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelA_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI A</td>";
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyGelB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI B</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
		if($_REQUEST['hemoclasifyGelAB']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelAB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ANTI AB</td>";
		$this->salida .= "            <td>$var</td>";
		if($_REQUEST['hemoclasifyGelD']>0){$var='POSITIVO'.' '.$_REQUEST['hemoclasifyGelD_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "            <td class=\"label\">ANTI D</td>";
		$this->salida .= "            <td>$var</td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            <tr class=\"modulo_list_claro\"><td></td></tr>";
		if($_REQUEST['celulasA']>0){$var='POSITIVO'.' '.$_REQUEST['celulasA_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS A</td>";
		$this->salida .= "        <td>$var</td>";
		if($_REQUEST['celulasB']>0){$var='POSITIVO'.' '.$_REQUEST['celulasB_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "        <td class=\"label\">CELULAS B</td>";
		$this->salida .= "        <td>$var</td>";
    $this->salida .= "        </tr>";
		if($_REQUEST['celulas0']>0){$var='POSITIVO'.' '.$_REQUEST['celulas0_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "        <td class=\"label\">CELULAS O</td>";
		$this->salida .= "        <td colspan=\"3\">$var</td>";
    $this->salida .= "        </tr>";
		$this->salida .= "        <tr class=\"modulo_list_claro\"><td></td></tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\"><td class=\"label\" align=\"center\" colspan=\"4\">&nbsp;</td></tr>";
		(list($grupoGel,$rhGel)=explode('/',$_REQUEST['grupoGel']));
		if($rhGel=='-'){$class='label_error';}else{$class='';}
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ABO</td>";
		$this->salida .= "            <td>$grupoGel</td>";
		$this->salida .= "            <td class=\"label\">Rh</td>";
		$this->salida .= "            <td class=\"$class\">&nbsp&nbsp;$rhGel</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"4\"><label class=\"label\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            ".$_REQUEST['profesionalgel']."</td>";
    $this->salida .= "            </tr>";
    $this->salida .= "            </table><BR>";
    $this->salida .= "        </td></tr>";
    $this->salida .= "        </table><BR>";
    $this->salida .= "      <table width=\"95%\" border=\"0\" align=\"center\">";
    $this->salida .= "      <tr class=\"modulo_list_claro\"><td width=\"50%\">";
		$this->salida .= "        <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		$this->salida .= "        <td colspan=\"4\">PRUEBA CRUZADA O DE COMPATIBILIDAD</td>";
		$this->salida .= "        <tr class=\"modulo_table_list_title\">";
		if($_REQUEST['formaResultadoCruze']==2){$var='AUTOMATICA';}else{$var='VISUAL';}
		$this->salida .= "        <td colspan=\"4\">$var</td></tr>";

		if($_REQUEST['cDirecto']>0){$var='POSITIVO'.' '.$_REQUEST['cDirecto_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase=='';}
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td width=\"25%\" class=\"label\">FASE COOMBS</td>";
		$this->salida .= "          <td>$var</td>";
		if($_REQUEST['enz']>0){$var='POSITIVO'.' '.$_REQUEST['enz_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "          <td width=\"25%\" class=\"label\">ENZIMAS</td>";
		$this->salida .= "          <td>$var</td>";
    $this->salida .= "          </tr>";
		if($_REQUEST['compatibilidad']==2){$var='No';$class='label_error';}
		else{$var='Si';$class='label';}
		$this->salida .= "         <tr class=\"modulo_table_list_title\">";
		$this->salida .= "         <td colspan=\"4\">COMPATIBLE</td>";
		$this->salida .= "         </tr>";
		$this->salida .= "         <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "         <td colspan=\"4\" align=\"center\" class=\"$class\">$var</td>";
    $this->salida .= "         </tr>";
		/*$this->salida .= "            <tr class=\"modulo_list_oscuro\"><td class=\"label\" align=\"center\" colspan=\"4\">&nbsp;</td></tr>";
		(list($grupoCruze,$rhCruze)=explode('/',$_REQUEST['grupoCruze']));
		if($rhCruze=='-'){$class='label_error';}else{$class='';}
    $this->salida .= "            <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td class=\"label\">ABO</td>";
		$this->salida .= "            <td>$grupoCruze</td>";
		$this->salida .= "            <td class=\"label\">Rh</td>";
		$this->salida .= "            <td class=\"$class\">&nbsp&nbsp;$rhCruze</td>";
    $this->salida .= "            </tr>";
		$this->salida .= "        <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "            <td colspan=\"4\"><label class=\"label\">PROFESIONAL&nbsp&nbsp&nbsp;</label>";
		$this->salida .= "            ".$_REQUEST['profesionalcruze']."</td>";
    $this->salida .= "        </tr>";*/
    $this->salida .= "        </table><BR>";
    $this->salida .= "      </td>";
    $this->salida .= "      <td valign=\"top\">";
		$this->salida .= "          <BR><table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_table_list_title\">";
		$this->salida .= "          <td colspan=\"4\">RASTREO DE ANTICUERPOS(RAI)</td>";
    $this->salida .= "          </tr>";
    if($_REQUEST['CelI']>0){$var='POSITIVO'.' '.$_REQUEST['CelI_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">Cel I</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
		if($_REQUEST['CelII']>0){$var='POSITIVO'.' '.$_REQUEST['CelII_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <td class=\"label\">Cel II</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
    $this->salida .= "          </tr>";
		if($_REQUEST['Auto']>0){$var='POSITIVO'.' '.$_REQUEST['Auto_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <tr class=\"modulo_list_oscuro\">";
		$this->salida .= "          <td class=\"label\">AUTO</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
		if($_REQUEST['OtrosRai']>0){$var='POSITIVO'.' '.$_REQUEST['OtrosRai_des'];$clase='label_error';}
		else{$var='NEGATIVO';$clase='';}
		$this->salida .= "          <td class=\"label\">OTROS</td>";
		$this->salida .= "          <td class=\"$clase\">$var</td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          </table>";
		$this->salida .= "       </td></tr>";
    $this->salida .= "      </table><BR>";
		$this->salida .= "          <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_table_list_title\"><td colspan=\"4\">COMPLEMENTARIAS</td></tr>";
    if($_REQUEST['lectina']>0){$var='POSITIVO'.' '.$_REQUEST['lectina_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td width=\"10%\" class=\"label\">LECTINA</td>";
		$this->salida .= "          <td>$var</td>";
		if($_REQUEST['cde']>0){$var='POSITIVO'.' '.$_REQUEST['cde_des'];}
		else{$var='NEGATIVO';}
		$this->salida .= "          <td width=\"10%\" class=\"label\">CDE</td>";
		$this->salida .= "          <td>$var</td>";
    $this->salida .= "          </tr>";
    $this->salida .= "          </table><BR>";
		$this->salida .= "          <table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida.="            <td width=\"25%\" class=\"label\">OBSERVACIONES</td>";
		$this->salida.="            <td>".$_REQUEST['observaciones']."</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td><label class=\"label\">PROFESIONAL RESPONSABLE RESULTADOS</label></td>";
		$this->salida .= "          <td>".$_REQUEST['profesionalResponsable']."</td>";
    $this->salida .= "          </tr>";
		if($_REQUEST['profesionalentrega'] && $_REQUEST['profesionalrecibe'] && $_REQUEST['fechaRecibe'] && $_REQUEST['horaRecibe'] && $_REQUEST['minutosRecibe']){
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td><label class=\"label\">PROFESIONAL QUE ENTREGO</label></td>";
		$this->salida .= "          <td>".$_REQUEST['profesionalentrega']."</td>";
    $this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida .= "          <td><label class=\"label\">PROFESIONAL QUE RECIBIO</label></td>";
		$this->salida .= "          <td>".$_REQUEST['profesionalrecibe']."</td>";
		$this->salida .= "          </tr>";
		$this->salida .= "          <tr class=\"modulo_list_claro\">";
		$this->salida.="            <td width=\"25%\" class=\"label\">FECHA RECIBIDO</td>";
    (list($dia,$mes,$ano)=explode('/',$_REQUEST['fechaRecibe']));
		$FechaConver=mktime($_REQUEST['horaRecibe'],$_REQUEST['minutosRecibe'],0,$mes,$dia,$ano);
    $this->salida.="            <td>".strftime("%A %d de  %B de %Y a las %H Horas con %M minutos",$FechaConver)."</td>";
		$this->salida .= "          </tr>";
		}else{
      $this->salida .= "          <tr class=\"modulo_list_claro\">";
		  $this->salida.="            <td width=\"25%\" colspan=\"2\"class=\"label_error\">RESULTADOS NO ENTREGADOS</td>";
			$this->salida .= "          </tr>";

		}
    $this->salida .= "          </table><BR>";
	  $this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
    $this->salida .= "    <table width=\"90%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";
    $this->salida .= "    <input type=\"submit\" class=\"input-submit\" value=\"SALIR\" name=\"salir\">";
		$this->salida .= "    </td></tr>";
		$this->salida .= "    </table>";
		$this->salida .= "    </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

/**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales=''){

		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++){
				  $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
					if($value==$Profesionales){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  for($i=0;$i<sizeof($profesionales);$i++){
			    $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
				  if($value==$Profesionales){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	function EtregaBolsaSanguinea($TipoDocumento,$Documento,$codigoBolsa,$grupoSanguineo,$componente,$ingresoBolsa,$numeroAlicuota){

    $this->salida.= ThemeAbrirTabla('PACIENTES CON RESERVAS CONFIRMADAS DE COMPONENTES');
		$this->salida .="<script language='javascript'>";
		$this->salida .= 'function mOvr(src,clrOver){';
		$this->salida .= '  src.style.background = clrOver;';
		$this->salida .= '}';
		$this->salida .= 'function mOut(src,clrIn){';
		$this->salida .= '  src.style.background = clrIn;';
		$this->salida .= '}';
		$this->salida .= '</script>';
		$accion=ModuloGetURL('app','Banco_Sangre','user','PedirDatosPaciente');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
    $this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
    $this->salida .= "<tr><td align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
    $this->salida .= "<tr><td width=\"50%\">";

		$this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS PRINCIPALES DEL PACIENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$TipoDocumento);
		$this->salida .= "  </select></td></tr>";
		$this->salida .= "  <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td></tr>";
    $this->salida .= "  <tr><td><BR><BR></td></tr>";
		$this->salida .= "  <tr><td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input selected type=\"submit\" class=\"input-submit\" name=\"aceptar\" value=\"BUSCAR\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"MENU\">";
    $this->salida .= "  </td></tr>";
    $this->salida .= "  </table><BR>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "  </table>";

    $this->salida .= "</td>";
    $this->salida .= "<td width=\"50%\">";

    $this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">BUSQUEDA POR COMPONENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"label\">CODIGO BOLSA </td><td><input type=\"text\" class=\"input-text\" name=\"codigoBolsa\" value=\"".$codigoBolsa."\" maxlength=\"32\"></td></tr>";
    $this->salida .= "  <tr><td class=\"label\">ABO / Rh</td><td><select name=\"grupoSanguineo\" class=\"select\">";
		$facts=$this->ConsultaFactor();
		$this->GrupoSanguineo($facts,'False',$grupoSanguineo);
		$this->salida .= "  </select></td></tr>";
    $this->salida .= "  <tr><td class=\"label\">COMPONENTE </td><td><select name=\"componente\" class=\"select\">";
		$componentes=$this->ConsultaComponente();
		$this->MostrasSelect($componentes,'False',$componente);
		$this->salida .= "  </select></td></tr>";
		$this->salida .= "  <tr><td colspan=\"2\" align=\"center\">";
		$this->salida .= "  <input selected type=\"submit\" class=\"input-submit\" name=\"aceptarComponente\" value=\"BUSCAR\">";
		$this->salida .= "  <input type=\"submit\" class=\"input-submit\" name=\"cancelar\" value=\"MENU\">";
    $this->salida .= "  </td></tr>";
    $this->salida .= "  </table><BR>";
		$this->salida .= "  </fieldset></td></tr>";
		$this->salida .= "  </table>";

		$this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		if($ingresoBolsa){
      $this->salida .= "        <BR><table width=\"70%\" border=\"0\" align=\"center\">";
			$this->salida .= "        <tr class=\"modulo_table_title\">";
			$this->salida .= "        <td>BOLSA</td>";
			$this->salida .= "        <td>FECHA VENCIMIENTO</td>";
			$this->salida .= "        <td>AOB / Rh</td>";
			$this->salida .= "        <td>COMPONENTE</td>";
			$this->salida .= "        <td>ALICUOTA</td>";
			$this->salida .= "        <td>CANTIDAD</td>";
			$this->salida .= "        </tr>";
			$datosBolsa=$this->DatosBolsaSeleccion($ingresoBolsa,$numeroAlicuota);
      $this->salida .= "        <tr class=\"modulo_list_claro\">";
      $this->salida .= "        <td>".$datosBolsa['bolsa_id']."</td>";
			$this->salida .= "        <td>".$datosBolsa['fecha_vencimiento']."</td>";
			$this->salida .= "        <td>".$datosBolsa['grupo_sanguineo']." / ".$datosBolsa['rh']."</td>";
			$this->salida .= "        <td>".$datosBolsa['componente']."</td>";
			$this->salida .= "        <td>".$datosBolsa['numero_alicuota']."</td>";
			$this->salida .= "        <td>".$datosBolsa['cantidad']."</td>";
			$this->salida .= "        </tr>";
      $this->salida .= "        <input type=\"hidden\" value=\"$ingresoBolsa\" name=\"ingresoBolsa\">";
			$this->salida .= "        <input type=\"hidden\" value=\"$numeroAlicuota\" name=\"numeroAlicuota\">";
      $this->salida .= "        </table><BR>";
		}
		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
		if(!empty($codigoBolsa) || ($grupoSanguineo!=-1 AND !empty($grupoSanguineo)) || ($componente!=-1 AND !empty($componente))){
      $bolsas=$this->BusquedaBolsasParaEntregar($codigoBolsa,$grupoSanguineo,$componente);
			if($bolsas){
        $this->salida .= "        <BR><table width=\"70%\" border=\"0\" align=\"center\">";
				$this->salida .= "        <tr class=\"modulo_table_title\">";
				$this->salida .= "        <td>BOLSA</td>";
				$this->salida .= "        <td>FECHA VENCIMIENTO</td>";
				$this->salida .= "        <td>AOB / Rh</td>";
				$this->salida .= "        <td>COMPONENTE</td>";
				$this->salida .= "        <td>ALICUOTA</td>";
				$this->salida .= "        <td>CANTIDAD</td>";
				$this->salida .= "        <td>&nbsp;</td>";
				$this->salida .= "        </tr>";
				$y=0;
				$bolsaIngresoAnt=-1;
				for($i=0;$i<sizeof($bolsas);$i++){
				  $conta=0;
				  for($j=0;$j<sizeof($bolsas);$j++){
            if($bolsas[$i]['ingreso_bolsa_id']==$bolsas[$j]['ingreso_bolsa_id']){
              $conta++;
						}
					}
					if($bolsas[$i]['ingreso_bolsa_id']!=$bolsaIngresoAnt){
					  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
						$this->salida .= "        <tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
						$this->salida .= "        <td rowspan=\"".$conta."\">".$bolsas[$i]['bolsa_id']."</td>";
						$this->salida .= "        <td rowspan=\"".$conta."\">".$bolsas[$i]['fecha_vencimiento']."</td>";
						$this->salida .= "        <td rowspan=\"".$conta."\">".$bolsas[$i]['grupo_sanguineo']." / ".$bolsas[$i]['rh']."</td>";
						$this->salida .= "        <td rowspan=\"".$conta."\">".$bolsas[$i]['componente']."</td>";
						if($bolsas[$i]['numero_alicuota']=='0'){
            $this->salida .= "        <td><b>PRINCIPAL</b></td>";
						}else{
            $this->salida .= "        <td>".$bolsas[$i]['numero_alicuota']."</td>";
						}
						$this->salida .= "        <td>".$bolsas[$i]['cantidad']." <b>ml.</b></td>";
						$action=ModuloGetURL('app','Banco_Sangre','user','LlamaEntregaBolsaSanguinea',array("ingresoBolsa"=>$bolsas[$i]['ingreso_bolsa_id'],"numeroAlicuota"=>$bolsas[$i]['numero_alicuota']));
					  $this->salida .= "        <td><a href=\"$action\" class=\"link\"><img title=\"Seleccion\" border=\"0\" src=\"".GetThemePath()."/images/flecha.png\"></a></td>";
						$this->salida .= "        </tr>";
						$bolsaIngresoAnt=$bolsas[$i]['ingreso_bolsa_id'];
					}else{
					  $this->salida .= "        <tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
            if($bolsas[$i]['numero_alicuota']=='0'){
            $this->salida .= "        <td><b>PRINCIPAL</b></td>";
						}else{
            $this->salida .= "        <td>".$bolsas[$i]['numero_alicuota']."</td>";
						}
						$this->salida .= "        <td>".$bolsas[$i]['cantidad']." <b>ml.</b></td>";
						$action=ModuloGetURL('app','Banco_Sangre','user','LlamaEntregaBolsaSanguinea',array("ingresoBolsa"=>$bolsas[$i]['ingreso_bolsa_id'],"numeroAlicuota"=>$bolsas[$i]['numero_alicuota']));
					  $this->salida .= "        <td><a href=\"$action\" class=\"link\"><img title=\"Seleccion\" border=\"0\" src=\"".GetThemePath()."/images/flecha.png\"></a></td>";
					  $this->salida .= "        </tr>";
					}
					$y++;
				}
				$this->salida .= "        </table>";
			}
		}else{
			$reservas=$this->ReservasSinConfirmar($TipoDocumento,$Documento);
			if($reservas){
				$this->salida .= "        <BR><table width=\"70%\" border=\"0\" align=\"center\">";
				$this->salida .= "        <tr class=\"modulo_table_title\">";
				$this->salida .= "        <td>PACIENTE</td>";
				$this->salida .= "        <td>AOB / Rh</td>";
				$this->salida .= "        <td>&nbsp;</td>";
				$this->salida .= "        </tr>";
				$y=0;
				for($i=0;$i<sizeof($reservas);$i++){
					if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					$this->salida.="<tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
					$this->salida .= "        <td>".$reservas[$i]['tipo_id_paciente']." ".$reservas[$i]['paciente_id']." - ".$reservas[$i]['nombre']."</td>";
					if($reservas[$i]['rh']=='+'){
					$this->salida .= "        <td>".$reservas[$i]['grupo_sanguineo']." / POSITIVO</td>";
					}elseif($reservas[$i]['rh']=='-'){
					$this->salida .= "        <td>".$reservas[$i]['grupo_sanguineo']." / <label class=\"label_error\">NEGATIVO</label></td>";
					}else{
					$this->salida .= "        <td>SIN REGISTRO</label></td>";
					}
					$action=ModuloGetURL('app','Banco_Sangre','user','PedirDatosPaciente',array("TipoDocumento"=>$reservas[$i]['tipo_id_paciente'],"Documento"=>$reservas[$i]['paciente_id'],"ingresoBolsa"=>$ingresoBolsa,"numeroAlicuota"=>$numeroAlicuota));
					$this->salida .= "    <td><a href=\"$action\" class=\"link\"><img title=\"Seleccion\" border=\"0\" src=\"".GetThemePath()."/images/flecha.png\"></a></td>";
					$this->salida .= "        </tr>";
					$y++;
				}
				$this->salida .= "        </table>";
			}
		}
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/**
	* Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
	* @access private
	* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
		foreach($tipo_id as $value=>$titulo)
		{
			if($value==$TipoId){
				$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
			}else{
				$this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
	}

	function EleccionBolsaEntrega($reservas,$bolsa,$grupoSan,$rh,$bolsaNum,$componenteDes,$componenteId,$solicitudId,$alicuota,$cantidadAli,$reservaId){

    $this->salida.= ThemeAbrirTabla('COMPONENTES SANGUINEOS Y RESERVAS');
		$accion=ModuloGetURL('app','Banco_Sangre','user','GuardarBolsaEntregar',array("bolsa"=>$bolsa,"grupoSan"=>$grupoSan,"rh"=>$rh,"bolsaNum"=>$bolsaNum,"componenteDes"=>$componenteDes,"componenteId"=>$componenteId,"solicitudId"=>$solicitudId,"TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id'],"nombre"=>$reservas[0]['nombre'],"reservaId"=>$reservaId));
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "<br><table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<fieldset><legend class=\"field\">DATOS PRINCIPALES DEL PACIENTE</legend>";
		$this->salida .= "<BR><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "<tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">IDENTIFICACION </td><td>".$reservas[0]['tipo_id_paciente']." ".$reservas[0]['paciente_id']."</td></tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">NOMBRE </td><td>".$reservas[0]['nombre']."</td></tr>";
    $this->salida .= "</table><BR>";
		$this->salida .= "</fieldset></td></tr>";
		$this->salida .= "<tr><td align=\"center\">";
    $this->salida .= "</td></tr>";
		$this->salida .= "</table><br>";
		if($reservas[0]['tipo_componente_id']){
		$this->salida .= "  <table width=\"70%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "  <td colspan=\"4\">RESERVAS COMPONENTES  CONFIRMADOS</td>";
		$this->salida .= "  <td>&nbsp;</td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_table_list_title\">";
		$this->salida .= "  <td>COMPONENTE</td>";
		$this->salida .= "  <td>CANTIDAD</td>";
		$this->salida .= "  <td>CONFIRMADO</td>";
		$this->salida .= "  <td>UNIDADES<br>ENTREGADAS</td>";
		$this->salida .= "  <td>&nbsp;</td>";
		$this->salida .= "  </tr>";
    for($i=0;$i<sizeof($reservas);$i++){
      if($reservas[$i]['confirmado']=='2'){
		  $this->salida .= "  <tr class=\"modulo_list_claro\">";
		  $this->salida .= "  <td>".$reservas[$i]['componente']."</td>";
			$this->salida .= "  <td>".$reservas[$i]['cantidad_componente']."</td>";
			if($reservas[$i]['confirmado']==2){
			  $this->salida .= "  <td>Si</td>";
			}else{
        $this->salida .= "  <td>No</td>";
			}

			$this->salida .= "  <td>".$this->unidadesEntregadasSolicitud($reservas[$i]['solicitud_reserva_sangre_id'],$reservas[$i]['tipo_componente_id'])."</td>";
			if($reservas[$i]['sw_cruze']==1){
			  $cruces=$this->SeleccionCruzesReserva($reservas[$i]['solicitud_reserva_sangre_id']);
				if($cruces){
					$this->salida .= "  <td>";
					$this->salida .= "    <table width=\"99%\" border=\"0\" align=\"center\">";
					$this->salida .= "    <tr class=\"modulo_table_title\">";
					$this->salida .= "    <td colspan=\"5\" align=\"center\">CRUZES</td>";
					$this->salida .= "    </tr>";
					$this->salida .= "    <tr class=\"modulo_table_title\">";
					$this->salida .= "    <td align=\"center\">BOLSA</td>";
					$this->salida .= "    <td align=\"center\">AOB/Rh</td>";
					$this->salida .= "    <td align=\"center\">ALICUOTA</td>";
					$this->salida .= "    <td align=\"center\">CANTIDAD</td>";
					$this->salida .= "    <td align=\"center\">SELECCION</td>";
					$this->salida .= "    </tr>";
					for($j=0;$j<sizeof($cruces);$j++){
            $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
						$this->salida .= "    <td>".$cruces[$j]['bolsa_id']."</td>";
						$this->salida .= "    <td>".$cruces[$j]['grupo_sanguineo']." ".$cruces[$j]['rh']."</td>";
						if(empty($cruces[$j]['numero_alicuota']) || $cruces[$j]['numero_alicuota']==0){
						$this->salida .= "    <td><b>PRINCIPAL</b></td>";
						}else{
						$this->salida .= "    <td>".$cruces[$j]['numero_alicuota']."</td>";
						}
						$this->salida .= "    <td>".$cruces[$j]['cantidad']."&nbsp&nbsp&nbsp;<b>ml.</b></td>";
						$action=ModuloGetURL('app','Banco_Sangre','user','BuscarBolsasParaEngregar',array("bolsaNum"=>$cruces[$j]['bolsa_id'],"TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id'],"nombre"=>$reservas[0]['nombre'],"bolsa"=>$cruces[$j]['ingreso_bolsa_id'],"grupoSan"=>$cruces[$j]['grupo_sanguineo'],"rh"=>$cruces[$j]['rh'],"componenteDes"=>$reservas[$i]['componente'],"componenteId"=>$reservas[$i]['tipo_componente_id'],"solicitudId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"alicuota"=>$cruces[$j]['numero_alicuota'],"cantidadAli"=>$cruces[$j]['cantidad'],"reservaId"=>$reservaId));
						$this->salida .= "    <td align=\"center\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
						$this->salida .= "    </tr>";
					}
					$this->salida .= "    </table>";
					$this->salida .= "  </td>";
			  }else{
          $action1=ModuloGetURL('app','Banco_Sangre','user','LlamaSeleccionComponenteSangre',array("TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id'],"nombre"=>$reservas[0]['nombre'],"bolsa"=>$bolsa,"grupoSan"=>$grupoSan,"rh"=>$rh,"bolsaNum"=>$bolsaNum,"tipoComponente"=>$reservas[$i]['tipo_componente_id'],"nombre"=>$reservas[0]['nombre'],"componenteDes"=>$componenteDes,"componenteId"=>$reservas[$i]['tipo_componente_id'],"solicitudId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"alicuota"=>$alicuota,"cantidadAli"=>$cantidadAli,"reservaId"=>$reservaId));
			    $this->salida .= "  <td><a href=\"$action1\" class=\"link\"><b>SELECCION UNIDAD &nbsp&nbsp&nbsp;".$reservas[$i]['componente']."</b></a></td>";
				}
			}else{
        $action1=ModuloGetURL('app','Banco_Sangre','user','LlamaSeleccionComponenteSangre',array("TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id'],"nombre"=>$reservas[0]['nombre'],"bolsa"=>$bolsa,"grupoSan"=>$grupoSan,"rh"=>$rh,"bolsaNum"=>$bolsaNum,"tipoComponente"=>$reservas[$i]['tipo_componente_id'],"nombre"=>$reservas[0]['nombre'],"componenteDes"=>$componenteDes,"componenteId"=>$reservas[$i]['tipo_componente_id'],"solicitudId"=>$reservas[$i]['solicitud_reserva_sangre_id'],"alicuota"=>$alicuota,"cantidadAli"=>$cantidadAli,"reservaId"=>$reservaId));
			  $this->salida .= "  <td><a href=\"$action1\" class=\"link\"><b>SELECCION UNIDAD &nbsp&nbsp&nbsp;".$reservas[$i]['componente']."</b></a></td>";
			}
			$this->salida .= "  </tr>";
		}
		}
		$this->salida .= "    </table>";
		}else{
    $this->salida .= "    <table width=\"70%\" border=\"0\" class=\"normal_10n\" align=\"center\">";
    $this->salida .= "    <tr><td class=\"label_error\" align=\"center\">EL PACIENTE NO TIENEN NINGUNA RESERVA DE COMPONENTES CONFIRMADA</td></tr>";
    $this->salida .= "    </table><BR><BR>";
		}
    $this->salida .= "    <table width=\"70%\" border=\"0\" class=\"normal_10n\" align=\"center\">";
		$action1=ModuloGetURL('app','Banco_Sangre','user','LlamaSeleccionComponenteSangre',array("TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id'],"nombre"=>$reservas[0]['nombre'],"bolsa"=>$bolsa,"grupoSan"=>$grupoSan,"rh"=>$rh,"bolsaNum"=>$bolsaNum,"nombre"=>$reservas[0]['nombre'],"componenteDes"=>$componenteDes,"componenteId"=>$componenteId,"alicuota"=>$alicuota,"cantidadAli"=>$cantidadAli,"reservaId"=>$reservaId));
    $this->salida .= "    <tr><td><a href=\"$action1\" class=\"link\">SELECCION COMPONENTE</a></td></tr>";
    $this->salida .= "    </table>";
		$this->salida .= "    <BR><table width=\"65%\" border=\"0\" align=\"center\">";
		$this->salida .= "<tr><td colspan=\"6\" align=\"center\">";
		$this->salida .=  $this->SetStyle("MensajeError");
		$this->salida .= "</td></tr>";
		$this->salida .= "	   <tr class=\"modulo_table_title\"><td colspan=\"6\">DATOS DE LA BOLSA</td></tr>";
		$this->salida .= "	   <tr class=\"modulo_list_claro\">";
		$this->salida .= "	   <td class=\"label\">BOLSA</td><td><input READONLY size=\"32\" type=\"text\" name=\"bolsaNum\" class=\"text-input\" value=\"$bolsaNum\"></td>";
		$this->salida .= "	   <td class=\"label\">AOB</td><td><input READONLY size=\"3\" type=\"text\" name=\"grupo_Sanguineo\" class=\"text-input\" value=\"$grupoSan\"></td>";
		$this->salida .= "	   <td class=\"label\">Rh</td><td><input READONLY size=\"2\" type=\"text\" name=\"rh\" class=\"text-input\" value=\"$rh\"></td>";
		$this->salida .= "	   </tr>";
		$this->salida .= "	   <tr class=\"modulo_list_claro\">";
		$this->salida .= "	   <td class=\"label\">COMPONENTE</td><td><input READONLY size=\"32\" type=\"text\" name=\"componente\" class=\"text-input\" value=\"$componenteDes\"></td>";
		$this->salida .= "	   <td class=\"label\">ALICUOTA</td>";
		if($alicuota=='0'){
      $MostrarAlicuota='PRINCIPAL';
		}else{
      $MostrarAlicuota=$alicuota;
		}
		$this->salida .= "	   <td><input READONLY size=\"10\" type=\"text\" class=\"label\" name=\"MostrarAlicuota\" class=\"text-input\" value=\"$MostrarAlicuota\"></td>";
		$this->salida .= "	   <td colspan=\"2\"><input READONLY size=\"10\" type=\"text\" name=\"cantidadAli\" class=\"text-input\" value=\"$cantidadAli\"><b>ml.</b></td>";
		$this->salida .= "	   <input type=\"hidden\" name=\"componenteId\" value=\"$componenteId\">";
		$this->salida .= "	   <input type=\"hidden\" name=\"solicitudId\" value=\"$solicitudId\">";
		$this->salida .= "	   <input type=\"hidden\" name=\"bolsa\" value=\"$bolsa\">";
		$this->salida .= "	   <input type=\"hidden\" name=\"alicuota\" value=\"$alicuota\">";
		$this->salida .= "	   </tr>";
		//$this->salida .= "	   <tr class=\"modulo_list_claro\"><td class=\"label\">CANTIDAD</td><td colspan=\"5\"><input maxlength=\"50\" type=\"text\" name=\"quien_recibe\" class=\"text-input\"></td></tr>";
    //$this->salida .= "	   <tr class=\"modulo_list_claro\"><td class=\"label\">QUIEN RECIBE</td><td colspan=\"5\"><input size=\"50\" maxlength=\"50\" type=\"text\" name=\"quien_recibe\" class=\"text-input\"></td></tr>";
    $this->salida .= "	   <tr class=\"modulo_list_claro\"><td class=\"label\" colspan=\"6\">OBSERVACIONES<BR><textarea name=\"observaciones\" cols=\"80\" rows=\"3\" class=\"textarea\"></textarea></td></tr>";
		$this->salida .= "	   <tr class=\"modulo_list_claro\"><td align=\"right\" colspan=\"2\">";
		$this->salida .= "	   <input selected type=\"submit\" value=\"GUARDAR\" name=\"guardar\" class=\"input-submit\">";
		$this->salida .= "	   </td>";
    $this->salida .= "	   </form>";
		if($_SESSION['RESERVA_SANGRE']['RETORNO']){
		$accion=ModuloGetURL($_SESSION['RESERVA_SANGRE']['RETORNO']['contenedor'],$_SESSION['RESERVA_SANGRE']['RETORNO']['modulo'],$_SESSION['RESERVA_SANGRE']['RETORNO']['tipo'],$_SESSION['RESERVA_SANGRE']['RETORNO']['metodo']);
		}else{
    $accion=ModuloGetURL('app','Banco_Sangre','user','GuardarBolsaEntregar',array("bolsa"=>$bolsa,"grupoSan"=>$grupoSan,"rh"=>$rh,"bolsaNum"=>$bolsaNum,"componenteDes"=>$componenteDes,"componenteId"=>$componenteId,"solicitudId"=>$solicitudId,"TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id'],"nombre"=>$reservas[0]['nombre']));
		}
		$this->salida .= "<form name=\"formabuscar1\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	   <td align=\"left\" colspan=\"4\">";
		$this->salida .= "	   <input type=\"submit\" value=\"SALIR\" name=\"salir\" class=\"input-submit\">";
		$this->salida .= "	   </td></tr>";
    $this->salida .= "</form>";
		$this->salida .= "    </table>";
		$this->salida .= "<form name=\"formabuscar2\" action=\"$accionun\" method=\"post\">";
		$bolsasNoConfirmadas=$this->SeleccionBolsasSinConfirmar($reservas[0]['tipo_id_paciente'],$reservas[0]['paciente_id']);
		if($bolsasNoConfirmadas){
			$this->salida .= "    <BR><table width=\"70%\" border=\"0\" class=\"normal_10n\" align=\"center\">";
      $this->salida .= "	   <tr class=\"modulo_table_title\">";
			$this->salida .= "	   <td align=\"center\">BOLSA</td>";
			$this->salida .= "	   <td align=\"center\">SELLO</td>";
			$this->salida .= "	   <td align=\"center\">AOB / Rh</td>";
			$this->salida .= "	   <td align=\"center\">COMPONENTE</td>";
			$this->salida .= "	   <td align=\"center\">ALICUOTA</td>";
			$this->salida .= "	   <td align=\"center\">&nbsp;</td>";
			$this->salida .= "	   </tr>";
			$y=0;
			for($i=0;$i<sizeof($bolsasNoConfirmadas);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "	   <tr class=\"$estilo\">";
				$this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['bolsa_id']."</td>";
				$this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['sello_calidad']."</td>";
				if($bolsasNoConfirmadas[$i]['rh']){
        $this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSITIVO</td>";
				}elseif($bolsasNoConfirmadas[$i]['rh']){
        $this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
				}else{
        $this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['grupo_sanguineo']."</td>";
				}
				$this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['componente']."</td>";
				if($bolsasNoConfirmadas[$i]['numero_alicuota']==0){
				$this->salida .= "	   <td align=\"center\">PRINCIPAL</td>";
				}else{
        $this->salida .= "	   <td align=\"center\">".$bolsasNoConfirmadas[$i]['numero_alicuota']."</td>";
				}
				$action=ModuloGetURL('app','Banco_Sangre','user','EliminarBolsaEntrega',array("alicuota"=>$bolsasNoConfirmadas[$i]['numero_alicuota'],"ingresoBolsa"=>$bolsasNoConfirmadas[$i]['ingreso_bolsa_id'],"TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id']));
				$this->salida .= "	   <td><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td>";
				$this->salida .= "	   </tr>";
				$y++;
			}
			$this->salida .= "    </table>";
			$this->salida .= "    <table width=\"70%\" border=\"0\" class=\"normal_10n\" align=\"center\">";
			$action=ModuloGetURL('app','Banco_Sangre','user','LlamaConfirmarEntrega',array("nombre"=>$reservas[0]['nombre'],"TipoDocumento"=>$reservas[0]['tipo_id_paciente'],"Documento"=>$reservas[0]['paciente_id']));
			$this->salida .= "	   <tr><td><a href=\"$action\" class=\"link\"><b>CONFIRMAR ENTREGA</b></a></td></tr>";
			$this->salida .= "    </table>";
		}
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function SeleccionComponenteSangre($TipoDocumento,$Documento,$bolsa,$grupoSan,$rh,$bolsaNum,$tipoComponente,$nombre,$componenteDes,$componenteId,$solicitudId,$alicuota,$cantidadAli,$nombre,
	  $BolsaFiltro,$grupo_sanguineoFiltro,$AlicuotaNoFiltro,$reservaId){

	  $this->salida.= ThemeAbrirTabla('COMPONENTES SANGUINEOS DISPONIBLES');
		$action=ModuloGetURL('app','Banco_Sangre','user','BuscarBolsasParaEngregar',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"bolsa"=>$bolsa,"grupoSan"=>$grupoSan,"rh"=>$rh,"bolsaNum"=>$bolsaNum,"componenteDes"=>$componenteDes,"componenteId"=>$componenteId,"solicitudId"=>$solicitudId,"alicuota"=>$alicuota,"cantidadAli"=>$cantidadAli,"nombre"=>$nombre,
		"BolsaFiltro"=>$BolsaFiltro,"grupo_sanguineoFiltro"=>$grupo_sanguineoFiltro,"AlicuotaNoFiltro"=>$AlicuotaNoFiltro,"reservaId"=>$reservaId));
		$this->salida .= "<form name=\"formabuscar\" action=\"$action\" method=\"post\">";
		$this->salida .= "<table class=\"normal_10\" border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "<tr><td width=\"50%\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">DATOS PRINCIPALES DEL PACIENTE</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">IDENTIFICACION </td><td>$TipoDocumento $Documento</td></tr>";
		$datos=$this->DatosPacienteBD($TipoDocumento,$Documento);
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">NOMBRE </td><td>$nombre</td></tr>";
		$edad_paciente = CalcularEdad($datos[fecha_nacimiento],date("Y-m-d"));
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">EDAD </td><td>".$edad_paciente[edad_aprox]."</td></tr>";
		if(!empty($datos['grupo_sanguineo']) && !empty($datos['rh'])){
    $this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">FACTOR Rh </td><td>".$datos['grupo_sanguineo']." ".$datos['rh']."</td></tr>";
		}else{
    $this->salida .= "  <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">FACTOR Rh </td><td>SIN REGISTRO</td></tr>";
		}
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td colspan=\"2\">&nbsp;</td></tr>";
    $this->salida .= "  </table>";
		$this->salida .= "  </fieldset>";
    $this->salida .= "</td>";
		$this->salida .= "<td width=\"50%\" valign=\"top\">";
		$this->salida .= "  <fieldset><legend class=\"field\">FILTRO DE BUSQUEDA DE COMPONENTES</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"20%\" class=\"label\" >BOLSA</td>";
		$this->salida .= "  <td><input class=\"input-text\" type=\"text\" name=\"BolsaFiltro\" size=\"15\" value=\"$BolsaFiltro\"></td>";
		$this->salida .= "  <td width=\"30%\" class=\"".$this->SetStyle("grupo_sanguineo")."\">ABO / Rh</td>";
		$this->salida .= "  <td><select name=\"grupo_sanguineoFiltro\" class=\"select\">";
		$facts=$this->ConsultaFactor();
		$this->GrupoSanguineo($facts,'False',$grupo_sanguineoFiltro);
		$this->salida .= "  </select></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td width=\"30%\" class=\"".$this->SetStyle("tipoComponente")."\">TIPO COMPONENTE </td>";
		$this->salida .= "  <td><select name=\"tipoComponente\" class=\"select\">";
		$componentes=$this->ConsultaComponente();
		$this->MostrasSelect($componentes,'False',$tipoComponente);
		$this->salida .= "  </select></td>";
    $this->salida .= "  <td width=\"20%\" class=\"label\">No. ALICUOTA</td>";
		$this->salida .= "  <td><input class=\"input-text\" type=\"text\" name=\"AlicuotaNoFiltro\" size=\"15\" value=\"$AlicuotaNoFiltro\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td align=\"center\" colspan=\"4\"><input type=\"submit\" class=\"input-submit\" name=\"filtro\" value=\"FILTRAR\"></td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  </table>";
		$this->salida .= "</fieldset>";
    $this->salida .= "</td></tr>";
		$this->salida .= "</table>";
		$bolsasTotal=$this->TotalBolsasEntregar($tipoComponente,$BolsaFiltro,$grupo_sanguineoFiltro,$AlicuotaNoFiltro);
		if($bolsasTotal){
		$this->salida .= "    <BR><table width=\"40%\" border=\"0\" align=\"center\">";
		$this->salida .= "	   <tr class=\"modulo_table_title\"><td align=\"center\" colspan=\"6\">DATOS DE LA BOLSA</td></tr>";
		$this->salida .= "	   <tr class=\"modulo_table_title\">";
		$this->salida .= "	   <td align=\"center\">BOLSA</td>";
		$this->salida .= "	   <td align=\"center\">AOB / Rh</td>";
		$this->salida .= "	   <td align=\"center\">COMPONENTE</td>";
		$this->salida .= "	   <td align=\"center\">ALICUOTA</td>";
		$this->salida .= "	   <td align=\"center\">CANTIDAD</td>";
		$this->salida .= "	   <td align=\"center\">&nbsp;</td>";
		$this->salida .= "	   </tr>";
		for($i=0;$i<sizeof($bolsasTotal);$i++){
      $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
      $this->salida .= "    <td>".$bolsasTotal[$i]['bolsa_id']."</td>";
			if($bolsasTotal[$i]['rh']=='+'){
        $this->salida .= "    <td>".$bolsasTotal[$i]['grupo_sanguineo']."&nbsp&nbsp&nbsp;POSITIVO</td>";
			}elseif($bolsasTotal[$i]['rh']=='-'){
        $this->salida .= "    <td>".$bolsasTotal[$i]['grupo_sanguineo']."&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
			}
			$this->salida .= "    <td>".$bolsasTotal[$i]['componente']."</td>";
      if($bolsasTotal[$i]['numero_alicuota']==0){
			$this->salida .= "    <td><b>PRINCIPAL</b></td>";
			}else{
			$this->salida .= "    <td>".$bolsasTotal[$i]['numero_alicuota']."</td>";
			}
			if($bolsasTotal[$i]['cantidad']!=0){
			$this->salida .= "    <td>".$bolsasTotal[$i]['cantidad']."&nbsp&nbsp&nbsp;<b>ml.</b></td>";
			}else{
      $this->salida .= "    <td>&nbsp;</td>";
			}
			$action=ModuloGetURL('app','Banco_Sangre','user','BuscarBolsasParaEngregar',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"bolsa"=>$bolsasTotal[$i]['ingreso_bolsa_id'],"grupoSan"=>$bolsasTotal[$i]['grupo_sanguineo'],"rh"=>$bolsasTotal[$i]['rh'],"bolsaNum"=>$bolsasTotal[$i]['bolsa_id'],"componenteDes"=>$bolsasTotal[$i]['componente'],"componenteId"=>$bolsasTotal[$i]['tipo_componente'],"solicitudId"=>$solicitudId,"alicuota"=>$bolsasTotal[$i]['numero_alicuota'],"cantidadAli"=>$bolsasTotal[$i]['cantidad'],"reservaId"=>$reservaId));
			$this->salida .= "    <td align=\"3\"><a href=\"$action\"><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"><a></td>";
      $this->salida .= "    </tr>";
		}
		$this->salida .= "	   </table>";
		}else{
      $this->salida .= "    <BR><table width=\"40%\" border=\"0\" align=\"center\">";
			$this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO EXISTEN COMPONENTES DISPONIBLES</td></tr>";
			$this->salida .= "	   </table>";
		}
		$this->salida .= "    <BR><table width=\"40%\" border=\"0\" align=\"center\">";
    $this->salida .= "    <tr><td align=\"center\">";
		$this->salida .= "    <input type=\"submit\" class=\"input-submit\" name=\"cancelarun\" value=\"CANCELAR\">";
		$this->salida .= "    </td></tr>";
    $this->salida .= "	   </table>";
		$this->salida .=$this->RetornarBarra(3);
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/* La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
	* @return boolean
	* @param string mensaje a retornar para el usuario
	* @param string titulo de la ventana a mostrar
	* @param string lugar a donde debe retornar la ventana
	* @param boolean tipo boton de la ventana
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton){

		$this->salida .= ThemeAbrirTabla($titulo);
		$this->salida .= "			      <table class=\"normal_10\" width=\"60%\" align=\"center\">";
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "				       <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
		if($boton){
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
		}
	  else{
			$this->salida .= "				       <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
	  }
		$this->salida .= "			     </form>";
		$this->salida .= "			     </table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function ConfirmarEntrega($TipoDocumento,$Documento,$nombre,$todos){

		$this->salida.= ThemeAbrirTabla('ENTREGA COMPONENTES SANGUINEOS');
		$action=ModuloGetURL('app','Banco_Sangre','user','RegistroAutenticacionUsuario');
		$this->salida .= "  <form name=\"forma\" action=\"$action\" method=\"post\">";
		$this->salida .= "  <table width=\"50%\" border=\"0\" align=\"center\">";
		$this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">BUSQUEDA COMPONENTES</legend>";
		$this->salida .= "  <br><table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO PACIENTE</td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$TipoDocumento);
		$this->salida .= "  </select></td></tr>";
		$this->salida .= "  <tr  class=\"modulo_list_claro\"><td class=\"label\">DOCUMENTO PACIENTE</td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" value=\"$Documento\" maxlength=\"32\"></td></tr>";
    $this->salida .= "  <tr  class=\"modulo_list_claro\"><td class=\"label\">NOMBRE PACIENTE </td><td><input type=\"text\" class=\"input-text\" name=\"nombre\" value=\"$nombre\" size=\"30\"></td></tr>";
		if($todos){
      $chec='checked';
		}
    $this->salida .= "  <tr  class=\"modulo_list_claro\"><td class=\"label\">TODOS</td><td><input type=\"checkbox\" name=\"todos\" value=\"1\" $chec></td></tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td align=\"right\">";
		$this->salida .= "  <input type=\"submit\" value=\"BUSCAR\" name=\"Buscar\" class=\"input-submit\">";
    $this->salida .= "  </td>";
		if(empty($_SESSION['RESERVA_SANGRE']['RETORNO'])){
		$this->salida .= "  <td>";
		$this->salida .= "  <input type=\"submit\" value=\"MENU\" name=\"Menu\" class=\"input-submit\">";
    $this->salida .= "  </td>";
		$this->salida .= "  </form>";
		}else{
    $this->salida .= "  </form>";
    $action=ModuloGetURL($_SESSION['RESERVA_SANGRE']['RETORNO']['contenedor'],$_SESSION['RESERVA_SANGRE']['RETORNO']['modulo'],$_SESSION['RESERVA_SANGRE']['RETORNO']['tipo'],$_SESSION['RESERVA_SANGRE']['RETORNO']['metodo']);
		$this->salida .= "  <form name=\"forma1\" action=\"$action\" method=\"post\">";
    $this->salida .= "  <td align=\"left\">";
		$this->salida .= "  <input type=\"submit\" value=\"MENU\" name=\"Menu\" class=\"input-submit\">";
    $this->salida .= "  </td>";
		$this->salida .= "  </form>";
		}
		$this->salida .= "  </tr>";
		$this->salida .= "	 </table>";
		$this->salida .= "	 </fieldset></td></tr>";
		$this->salida .= "	 </table><br>";
		$action=ModuloGetURL('app','Banco_Sangre','user','RegistroAutenticacionUsuario',array("TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombre"=>$nombre,"todos"=>$todos));
		$this->salida .= "  <form name=\"forma2\" action=\"$action\" method=\"post\">";
    $bolsas=$this->SeleccionBolsasSinConfirmarParametros($TipoDocumento,$Documento,$nombre,$todos);
		if($bolsas){
		  if(!empty($Documento)){
				$check='checked';
			}
      $this->salida .= "  <table class=\"normal_10\" border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "	   <tr class=\"modulo_table_title\">";
			$this->salida .= "	   <td align=\"center\">BOLSA</td>";
			$this->salida .= "	   <td align=\"center\">SELLO</td>";
			$this->salida .= "	   <td align=\"center\">PACIENTE</td>";
			$this->salida .= "	   <td align=\"center\">AOB / Rh</td>";
			$this->salida .= "	   <td align=\"center\">COMPONENTE</td>";
			$this->salida .= "	   <td align=\"center\">ALICUOTA</td>";
			$this->salida .= "	   <td align=\"center\">&nbsp;</td>";
      $y=0;
			for($i=0;$i<sizeof($bolsas);$i++){
			  if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "	   <tr class=\"$estilo\">";
				$this->salida .= "	   <td align=\"center\">".$bolsas[$i]['bolsa_id']."</td>";
				$this->salida .= "	   <td align=\"center\">".$bolsas[$i]['sello_calidad']."</td>";
				$this->salida .= "	   <td>".$bolsas[$i]['tipo_id_paciente']." ".$bolsas[$i]['paciente_id']." - ".$bolsas[$i]['nombre']."</td>";
				if($bolsasNoConfirmadas[$i]['rh']){
        $this->salida .= "	   <td align=\"center\">".$bolsas[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;POSITIVO</td>";
				}elseif($bolsasNoConfirmadas[$i]['rh']){
        $this->salida .= "	   <td align=\"center\">".$bolsas[$i]['grupo_sanguineo']." /&nbsp&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
				}else{
        $this->salida .= "	   <td align=\"center\">".$bolsas[$i]['grupo_sanguineo']."</td>";
				}
				$this->salida .= "	   <td align=\"center\">".$bolsas[$i]['componente']."</td>";
				if($bolsasNoConfirmadas[$i]['numero_alicuota']==0){
				$this->salida .= "	   <td align=\"center\">PRINCIPAL</td>";
				}else{
        $this->salida .= "	   <td align=\"center\">".$bolsas[$i]['numero_alicuota']."</td>";
				}
				$valor=$bolsas[$i]['ingreso_bolsa_id']."/".$bolsas[$i]['numero_alicuota'];
				$chequeado='';
        if(in_array($valor,$_REQUEST['seleccion'])){
				  $chequeado='checked';
        }
				$this->salida .= "	   <td><input $check type=\"checkbox\" name=\"seleccion[]\" value=\"".$bolsas[$i]['ingreso_bolsa_id']."/".$bolsas[$i]['numero_alicuota']."\" $chequeado></td>";
				$this->salida .= "	   </tr>";
				$y++;
			}
			$this->salida .= "  </table>";
		}
		$this->salida .= "  <br><table class=\"normal_10\" border=\"0\" width=\"40%\" align=\"center\">";
		$this->salida .= "	 <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "	 </td><tr>";
    $this->salida .= "  <tr><td>";
		$this->salida .= "  <fieldset><legend class=\"field\">AUTENTICACION DEL USUARIO</legend>";
		$this->salida .= "  <table cellspacing=\"2\" class=\"normal_10\"  cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_list_claro\"><td class=\"label\">TIPO DOCUMENTO</td><td><select name=\"TipoDocumentoAutentic\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->BuscarIdPaciente($tipo_id,$TipoDocumentoAutentic);
		$this->salida .= "  </select></td></tr>";
		$this->salida .= "  <tr  class=\"modulo_list_claro\"><td class=\"label\">DOCUMENTO</td><td><input type=\"text\" class=\"input-text\" name=\"DocumentoAutentic\" value=\"$DocumentoAutentic\" maxlength=\"32\"></td></tr>";
    /*$this->salida .= "  <tr class=\"modulo_list_claro\">";
    $this->salida .= "  <td class=\"label\">login:</td>";
		$this->salida .= "  <td><input size=\"30\" type=\"text\" name=\"login\" class=\"input-submit\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  <tr class=\"modulo_list_claro\">";
		$this->salida .= "  <td class=\"label\">password:</td>";
		$this->salida .= "  <td><input size=\"30\" type=\"password\" name=\"passwordd\" class=\"input-submit\"></td>";
    $this->salida .= "  </tr>";
		*/
    $this->salida .= "  <tr class=\"modulo_list_claro\"><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
    $this->salida .= "	 </table>";
		$this->salida .= "	 </fieldset></td></tr>";
		$this->salida .= "	 </table><br>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}





//pg_dump -u -s SIIS2 > /home/lorena/Desktop/alex.sql
}//fin clase user
?>

