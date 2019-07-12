<?php
	
	function Medicamentos_Despacho($ingreso,$tipo_solicitud,$medicamento_sol)
	{
		       $objResponse = new xajaxResponse();  
            $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
           $datos = $slt->Despachados_por_Medicamento_Solicitado($ingreso,$tipo_solicitud,$medicamento_sol);
      if(!empty($datos))
      {
         $this->salida .= "  <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"\">\n";
          $this->salida .= "      <tr class=\"modulo_table_title\">\n";
          $this->salida.="<td align=\"center\" colspan=\"5\">MEDICAMENTOS DESPACHADOS</td>";
           $this->salida .= "</tr>";
           

              $this->salida.="<tr class=\"modulo_table_title\">";
             $this->salida.="  <td align=\"center\" colspan=\"1\">CODIGO</td>";
              $this->salida.="  <td align=\"center\" colspan=\"2\">PRODUCTO</td>";
              $this->salida.="  <td align=\"center\" colspan=\"2\">CANT</td>";
              $this->salida .= "</tr>";
              $total=0;
               foreach($datos as $i => $dtl)
            {
            
              $this->salida.="<tr class=\"modulo_list_oscuro\">";
              $this->salida.="  <td align=\"center\" colspan=\"1\" >".$dtl['codigo_producto']."</td>";
              $this->salida.="  <td align=\"center\" colspan=\"2\">".$dtl['descripcion']."</td>";
              $this->salida.="  <td align=\"center\"  colspan=\"2\">".floor($dtl['cantidad'])."</td>";
              
              $this->salida .= "</tr>";
             $total=$total+$dtl['cantidad'];
            }
              $this->salida.="<tr class=\"modulo_table_title\">";
              $this->salida.="  <td align=\"right\" colspan=\"3\">Total</td>";
              $this->salida.="  <td  align=\"left\" colspan=\"2\">".floor($total)."</td>";
           
              $this->salida .= "</tr>";
        
          $this->salida .= "</table><BR>";
    	}else
      {
               $this->salida .= "   <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
               $this->salida .= "       <tr >\n";
               $this->salida .= "           <td align=\"center\"><label class='label_mark'>NO HAY DESPACHO DE MEDICAMENTOS !</label></td>\n";
               $this->salida.="</tr></table>";
      
      
      }
      $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($this->salida));
			$objResponse->call("MostrarSpan");
          return $objResponse;
	}
      function GeneraReporte($entidad){
	    if(!empty($entidad))
	    {
		$_SESSION['entidad']=$entidad;
	    }
            else{
		if(empty($entidad) && isset($_SESSION['entidad']))
		{
		    unset($_SESSION['entidad']);
		}
	    }		
	}
	function Suministro_Rapido($ingreso,$estacion,$centro_utilidad,$empresa)
    {
      $objResponse = new xajaxResponse(); 

      $datos_estacion=array();
      // Array para Mostrar Suministros.
      $_Suministros = array();
      $_NomProducto = array();

      $datos_estacion['estacion_id']=$estacion;
      $datos_estacion['centro_utilidad']=$centro_utilidad;
      $datos_estacion['empresa_id']=$empresa;

      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      $datos_med=$slt->Consulta_Solicitud_Medicamentos($ingreso);
      if(!empty($datos_med))
      {
	$accion = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarSuministrosRapidos',array("tipo_solicitud"=>"M","datos_estacion"=>$datos_estacion,"datosPaciente[ingreso]"=>$ingreso));
	$this->salida .= "<form name=\"formadesr\" action=\"$accion\" method=\"post\">";
	$_SESSION['datos_producto']=$datos_med;
         $this->salida .= "  <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"\">\n";
          $this->salida .= "      <tr class=\"modulo_table_title\">\n";
          $this->salida.="<td align=\"center\" colspan=\"9\">MEDICAMENTOS DESPACHADOS</td>";
           $this->salida .= "</tr>";
	    $this->salida.="<tr class=\"modulo_table_title\">";
             $this->salida.="  <td align=\"center\" >MEDICAMENTOS</td>";
	     $this->salida.="  <td align=\"center\" >CODIGO MEDICAMENTO</td>";
	     $this->salida.="  <td align=\"center\" >DOSIS</td>";
	     $this->salida.="  <td align=\"center\" >CANT. BODEGA PACIENTE</td>";
              $this->salida.="  <td align=\"center\" >SUMINISTRAR</td>";
              $this->salida.="  <td align=\"center\" >DESECHOS</td>";
	      $this->salida.="  <td align=\"center\" >HORA</td>";
	      $this->salida.="  <td align=\"center\" >BODEGA</td>";
	      $this->salida.="  <td align=\"center\" >OBSERVACION</td>";	
              $this->salida .= "</tr>";
             
               foreach($datos_med as $i => $dtl)
	      {
	      if($dtl['sw_estado']==1){

		  $datos = $slt->GetEstacionBodega_Existencias($datos_estacion,2,$dtl['codigo_producto']);
		  // Informacion de Conteo de medicamentos Solicitados para validaciones.
		  $_BodegaPaciente = $slt->GetCantidades_BodegaPaciente($ingreso,$dtl['codigo_producto']);
		  //1 Cantidades reales en la Bodega del Paciente.
		  $_StockPaciente = $_BodegaPaciente[0]['stock_almacen'];
		  if($_StockPaciente == 0)
		  { $_StockPaciente = $_BodegaPaciente[0]['stock_paciente']; }
		  $_StockPaciente = $_StockPaciente - $_BodegaPaciente[0]['cantidad_en_devolucion'];
		  // Vectores de suministros
		  $_Ubodega = explode(" ",$dtl['dosis']);
		  $tipo_solicitud="M";
		  $control = $slt->Consultar_Control_Suministro($dtl['codigo_producto'], $ingreso, $tipo_solicitud);
		  array_push($_Suministros,$control);
		  array_push($_NomProducto,$dtl['producto']); 
		  //$catidadBodega[$x] = $_StockPaciente;*/
		if($_StockPaciente >0){
		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="  <td align=\"center\"  >".$dtl['producto']."</td>";
		$this->salida .= "<input type=\"hidden\" name=\"nom_prod[]\" value=\"".$dtl[producto]."\">";
		$this->salida .= "<input type=\"hidden\" name=\"datos_SUM[]\" value=\"".$dtl[codigo_producto]."\">";
		$this->salida .= "<input type=\"hidden\" name=\"ingreso_F[]\" value=\"".$dtl[ingreso]."\">";
		$this->salida .= "<input type=\"hidden\" name=\"num_F[]\" value=\"".$dtl[num_reg_formulacion]."\">";
		//Dosis
		$dosis = $dtl[dosis];
		$this->salida .= "<input type=\"hidden\" name=\"dosis[]\" value=\"$dosis\">";
		// BodegaPaciente
		$this->salida .= "<input type=\"hidden\" name=\"BodegaPaciente[]\" value=\"$_StockPaciente\">";
		// Cantidad Recetada. Las unidades totales q recomendo el profesional
		$cantidad_recetada = $dtl[cantidad];
		$this->salida .= "<input type=\"hidden\" name=\"cantidad_recetada[]\" value=\"$cantidad_recetada\">";
		$dosificacion = $slt->SeleccionUnidadSuministro($dtl[unidad_dosificacion], $dtl[cod_presentacion]);
		 if($dosificacion == 0)
		 {
			$factor = $slt->SeleccionFactorConversion($dtl[codigo_producto], $dtl[unidad_id], $dtl[unidad_dosificacion]);
			if(!empty($factor))
			{ 
				$existeFac = 1; 			
			}
			  if(!$factor[0][factor_conversion])
			 {
				$factor[0][factor_conversion] = 0;
			 }
			$this->salida .= "<input type=\"hidden\" name=\"FactorC[]\" value=\"".$factor[0][factor_conversion]."\">";
			
		}
		 else{
					$this->salida .= "<input type=\"hidden\" name=\"FactorC[]\" value=\"\">";
		  }
		$_PorSuministar = round($_StockPaciente*$factor[0][factor_conversion],5);
		$this->salida.="  <td align=\"center\" >".$dtl[codigo_producto]."</td>";
		$this->salida.="  <td align=\"center\" >".(int)$dosis." ".$dtl[unidad_dosificacion]."</td>";
		$this->salida.="  <td align=\"center\" >".$_PorSuministar." ".$dtl[unidad_dosificacion]."</td>";
		$this->salida.="  <td align=\"center\" ><input type=\"text\" class=\"input-text\" size=\"5\" name=\"cantidad_suministrada[]\" ></td>";
		$this->salida.="  <td align=\"center\"  ><input type=\"text\" class=\"input-text\" size=\"5\" name=\"perdidas[]\"></td>";		
		$this->salida.="  <td>";
		$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
               $hora_inicio_turno = "00:00:00";
               $rango_turno = date("H");
               if(date("H:i:s") <= $hora_inicio_turno)
               {
                    list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
                    list($h,$m,$s)=explode(":",$hora_control);
               }
               else
               {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
                    list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
                    list($h,$m,$s)=explode(":",$hora_control);
               }
     
               $i = 0;
               $rangomin = $rango_turno - 24;
               $this->salida.= "<select name='selectHora[]' class='select'>\n";
               for($j = $rangomin; $j<=$rango_turno; $j++)
               {
                    list($anno, $mes, $dia)=explode("-",$fecha_control);
                    if ($i==23)
                    {
                         list($h,$m,$s)=explode(":",$hora_inicio_turno);
                         $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                         $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                         $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
                    }
                    else
                    {
                         list($h,$m,$s)=explode(":",$hora_inicio_turno);
                         $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                         $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                         $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
                    }
                    if(empty($selectHora)){
                         if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    else
                    {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                         list($A,$B) = explode(" ",$selectHora);
                         if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    #################################################
                    list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
                    if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                         $show = "Hoy a las";
                    }
                    elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                         $show = "Ma�ana a las";
                    }
                    elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                         $show = "Ayer a las";
                    }
                    else{
                         $show = $fecha_control;
                    }
                    ###########################
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$i."' selected $selected>".$i."</option>\n";
                    list($yy,$mm,$dd)=explode(" ",$fecha_c);
                    if (-23<=$j AND $j<=-1){
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))));
                    }
                    else
                    {
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))));
                    }
                    $this->salida .= "<option value='".$fecha_c." ".$i."' selected $selected>".$i."</option>\n";
               }//fin for
               
               if(!empty($_REQUEST['selectHora']))
               {
                    $horas_R = explode(" ", $_REQUEST['selectHora']);
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
               }
               $this->salida.= "</select>:\n";  
	       $this->salida.= "<select name='selectMinutos[]' class='select'>\n";
               for($j=0; $j<=59; $j++)
               {
                    if(empty($selectMinutos)){
                         if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    else
                    {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                         list($A,$B) = explode(" ",$selectMinutos);
                         if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    if ($j<10){
                         $this->salida .= "         <option value='0$j:00' $selected>0$j</option>\n";
                    }
                    else{
                         $this->salida .= "         <option value='$j:00' $selected>$j</option>\n";
                    }
               }
               $this->salida .= "</select>\n";

		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!is_array($datos) && $_StockPaciente <= 0)
               {
                    $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA ESTE PRODUCTO";
                    $this->salida.="<img src=\"". GetThemePath() ."/images/preguntaac.png\" title='$title' border='0'>";
               }
               else
               {
                    $this->salida.="<select name=bodega class='select'>";
                    if(is_array($datos))
                    {
                         $this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";
                         for($i=0;$i<sizeof($datos);$i++)
                         {
                              $this->salida.="<option value=".FormatoValor($datos[$i][existencia]).",".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                         }
                    }
                    elseif(!is_array($datos) AND ($_StockPaciente > 0))
                    {$this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";}
                    $this->salida.="</select>";
               }
		$this->salida.="</td>";
		$this->salida.="  <td align=\"center\"  ><textarea class='textarea' name = 'observacion_suministro[]' cols = 30 rows = 3></textarea></td>";
		$this->salida .= "</tr>";
	       }
	    }
		
            }
             $this->salida .= "<tr>"; 
		$this->salida.="  <td align=\"center\" colspan=\"9\" ><input type=\"button\" value=\"Registrar Suministros\" onclick=\"valida_campos()\"></td>";
		$this->salida .= "</tr>";    
	
	      $this->salida .= "</table><BR>";
    	}else
	{
		$this->salida .= "   <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
		$this->salida .= "       <tr >\n";
		$this->salida .= "           <td align=\"center\"><label class='label_mark'>NO HAY DESPACHO DE MEDICAMENTOS !</label></td>\n";
		$this->salida.="</tr></table>";
	
	
	}
	$this->salida.="</form>"; 

      $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($this->salida));
      $objResponse->call("MostrarSpan");
     
      return $objResponse;
    }

function SuministroRapidoInsumos($ingreso,$estacion,$centro_utilidad,$empresa)
    {
      $objResponse = new xajaxResponse(); 

      $datos_estacion=array();
      // Array para Mostrar Suministros.
      $_Suministros = array();
      $_NomProducto = array();

      $datos_estacion['estacion_id']=$estacion;
      $datos_estacion['centro_utilidad']=$centro_utilidad;
      $datos_estacion['empresa_id']=$empresa;

      $slt = AutoCarga::factory("SolicitudesAutomaticas","classes","app","EE_AdministracionMedicamentos");
      $datos_med=$slt->Consulta_Solicitud_Insumos($ingreso);
      if(!empty($datos_med))
      {
	$accion = ModuloGetURL('app','EE_AdministracionMedicamentos','user','ConfirmarSuministrosRapidosInsumos',array("tipo_solicitud"=>"I","datos_estacion"=>$datos_estacion,"datosPaciente[ingreso]"=>$ingreso));
	$this->salida .= "<form name=\"formadesr\" action=\"$accion\" method=\"post\">";
	$_SESSION['datos_producto']=$datos_med;
         $this->salida .= "  <table align=\"center\" width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" class=\"\">\n";
          $this->salida .= "      <tr class=\"modulo_table_title\">\n";
          $this->salida.="<td align=\"center\" colspan=\"9\">MEDICAMENTOS DESPACHADOS</td>";
           $this->salida .= "</tr>";
	    $this->salida.="<tr class=\"modulo_table_title\">";
             $this->salida.="  <td align=\"center\" >SUMINISTRO</td>";
	     $this->salida.="  <td align=\"center\" >CODIGO SUMINISTRO</td>";
	      $this->salida.="  <td align=\"center\" >CANT. BODEGA PACIENTE</td>";
              $this->salida.="  <td align=\"center\" >SUMINISTRAR</td>";
              $this->salida.="  <td align=\"center\" >DESECHOS</td>";
	      $this->salida.="  <td align=\"center\" >HORA</td>";
	      $this->salida.="  <td align=\"center\" >BODEGA</td>";
	      $this->salida.="  <td align=\"center\" >OBSERVACION</td>";	
              $this->salida .= "</tr>";
             
          foreach($datos_med as $i => $dtl)
	      {
	      

		  $datos = $slt->GetEstacionBodega_Existencias($datos_estacion,2,$dtl['codigo_producto']);
		  // Informacion de Conteo de medicamentos Solicitados para validaciones.
		  $_BodegaPaciente = $slt->GetCantidades_BodegaPaciente($ingreso,$dtl['codigo_producto']);
		  //1 Cantidades reales en la Bodega del Paciente.
		  $_StockPaciente = $_BodegaPaciente[0]['stock_almacen'];
		  if($_StockPaciente == 0)
		  { $_StockPaciente = $_BodegaPaciente[0]['stock_paciente']; }
		  $_StockPaciente = $_StockPaciente - $_BodegaPaciente[0]['cantidad_en_devolucion'];
		  // Vectores de suministros
		  $_Ubodega = explode(" ",$dtl['dosis']);
		  $tipo_solicitud="I";
		  $control = $slt->Consultar_Control_Suministro($dtl['codigo_producto'], $ingreso, $tipo_solicitud);
		  array_push($_Suministros,$control);
		  array_push($_NomProducto,$dtl['producto']); 
		  //$catidadBodega[$x] = $_StockPaciente;*/
		if($_StockPaciente >0){
		$this->salida.="<tr class=\"modulo_list_oscuro\">";
		$this->salida.="  <td align=\"center\"  >".$dtl['descripcion']."</td>";
		$this->salida .= "<input type=\"hidden\" name=\"nom_prod[]\" value=\"".$dtl[descripcion]."\">";
		$this->salida .= "<input type=\"hidden\" name=\"datos_SUM[]\" value=\"".$dtl[codigo_producto]."\">";
		$this->salida .= "<input type=\"hidden\" name=\"ingreso_F[]\" value=\"".$dtl[ingreso]."\">";
		$this->salida .= "<input type=\"hidden\" name=\"num_F[]\" value=\"".$dtl[num_reg_formulacion]."\">";
		//Dosis
		$dosis = $dtl[dosis];
		$this->salida .= "<input type=\"hidden\" name=\"dosis[]\" value=\"$dosis\">";
		// BodegaPaciente
		$this->salida .= "<input type=\"hidden\" name=\"BodegaPaciente[]\" value=\"$_StockPaciente\">";
		// Cantidad Recetada. Las unidades totales q recomendo el profesional
		$cantidad_recetada = $dtl[cantidad];
		$this->salida .= "<input type=\"hidden\" name=\"cantidad_recetada[]\" value=\"$cantidad_recetada\">";
		$dosificacion = $slt->SeleccionUnidadSuministro($dtl[unidad_dosificacion], $dtl[cod_presentacion]);
		 if($dosificacion == 0)
		 {
			$factor = $slt->SeleccionFactorConversion($dtl[codigo_producto], $dtl[unidad_id], $dtl[unidad_dosificacion]);
			if(!empty($factor))
			{ 
				$existeFac = 1; 			
			}
			  if(!$factor[0][factor_conversion])
			 {
				$factor[0][factor_conversion] = 0;
			 }
			$this->salida .= "<input type=\"hidden\" name=\"FactorC[]\" value=\"".$factor[0][factor_conversion]."\">";
			
		}
		 else{
					$this->salida .= "<input type=\"hidden\" name=\"FactorC[]\" value=\"\">";
		  }
		$this->salida.="  <td align=\"center\" >".$dtl[codigo_producto]."</td>";
		$this->salida.="  <td align=\"center\" >$_StockPaciente</td>";
		$this->salida.="  <td align=\"center\" ><input type=\"text\" class=\"input-text\" size=\"5\" name=\"cantidad_suministrada[]\"></td>";
		$this->salida.="  <td align=\"center\"  ><input type=\"text\" class=\"input-text\" size=\"5\" name=\"perdidas[]\"></td>";		
		$this->salida.="  <td>";
		$rango_turno = ModuloGetVar('app','EstacionEnfermeria','RangoVerTurnosControles');
               $hora_inicio_turno = "00:00:00";
               $rango_turno = date("H");
               if(date("H:i:s") <= $hora_inicio_turno)
               {
                    list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s"));
                    list($h,$m,$s)=explode(":",$hora_control);
               }
               else
               {//si la hora actual es menor que la de inicio turno, se debe poner la fecha anterior
                    list($fecha_control,$hora_control)=explode(" ",date("Y-m-d H:i:s",mktime(date("h"),date("i"),date("s"),date("m"),(date("d")-1),date("Y"))));
                    list($h,$m,$s)=explode(":",$hora_control);
               }
     
               $i = 0;
               $rangomin = $rango_turno - 24;
               $this->salida.= "<select name='selectHora[]' class='select'>\n";
               for($j = $rangomin; $j<=$rango_turno; $j++)
               {
                    list($anno, $mes, $dia)=explode("-",$fecha_control);
                    if ($i==23)
                    {
                         list($h,$m,$s)=explode(":",$hora_inicio_turno);
                         $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                         $fecha2=date("Y-m-d H:i:s",mktime(24,0,0,$mes,$dia,$anno));
                         $fecha_control=date("Y-m-d",mktime(24,0,0,$mes,$dia,$anno));
                    }
                    else
                    {
                         list($h,$m,$s)=explode(":",$hora_inicio_turno);
                         $i=date("H",mktime($h+$j,$m,$s,$mes,$dia,$anno));
                         $fecha2=date("Y-m-d H:i:s",mktime($i,0,0,$mes,$dia,$anno));
                         $fecha_control=date("Y-m-d",mktime($i,0,0,$mes,$dia,$anno));
                    }
                    if(empty($selectHora)){
                         if($i == date("H")){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    else
                    {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                         list($A,$B) = explode(" ",$selectHora);
                         if($i == $B){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    #################################################
                    list($yy,$mm,$dd)=explode(" ",$fecha_control);//(date("m"),(date("d")),date("Y")));
                    if($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))))){
                         $show = "Hoy a las";
                    }
                    elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")+1),date("Y"))))){
                         $show = "Ma�ana a las";
                    }
                    elseif($fecha_control == (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))))){
                         $show = "Ayer a las";
                    }
                    else{
                         $show = $fecha_control;
                    }
                    ###########################
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$i."' selected $selected>".$i."</option>\n";
                    list($yy,$mm,$dd)=explode(" ",$fecha_c);
                    if (-23<=$j AND $j<=-1){
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")-1),date("Y"))));
                    }
                    else
                    {
                    $fecha_c = (date("Y-m-d", mktime(0,0,0,date("m"),(date("d")),date("Y"))));
                    }
                    $this->salida .= "<option value='".$fecha_c." ".$i."' selected $selected>".$i."</option>\n";
               }//fin for
               
               if(!empty($_REQUEST['selectHora']))
               {
                    $horas_R = explode(" ", $_REQUEST['selectHora']);
                    //$this->salida .= "<option value='".date("Y-m-d")." ".$horas_R[1]."' selected='true'>".$horas_R[1]."</option>\n";
               }
               $this->salida.= "</select>:\n";  
	       $this->salida.= "<select name='selectMinutos[]' class='select'>\n";
               for($j=0; $j<=59; $j++)
               {
                    if(empty($selectMinutos)){
                         if($j == date("i")){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    else
                    {//viene de insertar pero no lo hizo, asi que pongo por defecto los valores enviados
                         list($A,$B) = explode(" ",$selectMinutos);
                         if($j == $A){ $selected = "selected='true'";} else { $selected = "";}
                    }
                    if ($j<10){
                         $this->salida .= "         <option value='0$j:00' $selected>0$j</option>\n";
                    }
                    else{
                         $this->salida .= "         <option value='$j:00' $selected>$j</option>\n";
                    }
               }
               $this->salida .= "</select>\n";

		$this->salida.="</td>";
		$this->salida.="<td>";
		if(!is_array($datos) && $_StockPaciente <= 0)
               {
                    $title="NO HAY EXISTENCIAS PARA LA BODEGA DEL PACIENTE NI HAY EXISTENCIAS EN LAS OTRAS BODEGAS PARA ESTE PRODUCTO";
                    $this->salida.="<img src=\"". GetThemePath() ."/images/preguntaac.png\" title='$title' border='0'>";
               }
               else
               {
                    $this->salida.="<select name=bodega class='select'>";
                    if(is_array($datos))
                    {
                         $this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";
                         for($i=0;$i<sizeof($datos);$i++)
                         {
                              $this->salida.="<option value=".FormatoValor($datos[$i][existencia]).",".$datos[$i][bodega].">".$datos[$i][descripcion]."</option>";
                         }
                    }
                    elseif(!is_array($datos) AND ($_StockPaciente > 0))
                    {$this->salida.="<option value='*/*' SELECTED>BODEGA PACIENTE</option>";}
                    $this->salida.="</select>";
               }
		$this->salida.="</td>";
		$this->salida.="  <td align=\"center\"  ><textarea class='textarea' name = 'observacion_suministro[]' cols = 30 rows = 3></textarea></td>";
		$this->salida .= "</tr>";
	       }
	  
		
            }
             $this->salida .= "<tr>"; 
		$this->salida.="  <td align=\"center\" colspan=\"9\" ><input type=\"button\" value=\"Registrar Suministros\" onclick=\"valida_camposI()\"></td>";
		//$this->salida.="  <td align=\"center\" colspan=\"9\" ><input type=\"submit\" value=\"Registrar Suministros\"></td>";
		$this->salida .= "</tr>";    
	
	      $this->salida .= "</table><BR>";
    	}else
	{
		$this->salida .= "   <table align=\"center\" width=\"80%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\" >\n";
		$this->salida .= "       <tr >\n";
		$this->salida .= "           <td align=\"center\"><label class='label_mark'>NO HAY DESPACHO DE MEDICAMENTOS !</label></td>\n";
		$this->salida.="</tr></table>";
	
	
	}
	$this->salida.="</form>";
      $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($this->salida));
      $objResponse->call("MostrarSpan");
     
      return $objResponse;
    }
	
	
?>       