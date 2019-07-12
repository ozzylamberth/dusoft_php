<?php
  /**
  *Funcion xajax que permite escojer la clasificacion
  */
  function EscojerClasifica($estFz, $grado, $valClasFin)
  {
    $objResponse = new xajaxResponse();  
    $obPac = AutoCarga::factory("Pacientes","","app","DatosPaciente");
    $clasifica = $obPac->ObtenerClasifcacion($estFz, $grado);
    $objResponse = new xajaxResponse();
	//if($estFz == "M"){
	if(sizeof($clasifica) > 1)
	{
		//$html = "        <input type=\"hidden\" name=\"hClasif\"  value=\"\" > \n";
        $html = "      <select name=\"selClasf\" class=\"select\" onchange=\"asignarClasificacion(this.value)\">\n";
        $html .= "        <option value=\"-1\">-- Seleccionar --</option>\n";
        
		foreach($clasifica as $key => $clasf)
        {
          //($clasf['clasifi_finaci_id'] == "11")? $chk = "selected": $chk = "";
		  ($clasf['clasifi_finaci_id'] == $valClasFin)? $chk = "selected": $chk = "";
		  //$chk = "";
		  $html .= "          <option value=\"".$clasf['clasifi_finaci_id']."\" ".$chk.">".$clasf['descripcion']."</option>\n";  
		  $valClasf = "".$clasf['clasifi_finaci_id']."";
        }                  
        $html .= "      </select>\n";
		//$html .= "        <input type=\"hidden\" name=\"hClasif\"  value=\"".$valClasf."\" > \n";
		//$html .= "        <input type=\"hidden\" name=\"hClasif\"  value=\"\" > \n";
		//$html .= "<script> document.forma.hClasif.value = document.forma.selClasf.value; </script>"; 
	}
	else{
        foreach($clasifica as $key => $clasf)
		{
          //$clasf['clasifi_finaci_id']
          $html = $clasf['descripcion'];
		  $objResponse->assign("hClasif","value",$clasf['clasifi_finaci_id']);
		  //$html .= "<script> selCategoria(\"".$html."\"); </script>";
		  //$html .= "        <input type=\"hidden\" name=\"hClasif\"  value=\"".$clasf['clasifi_finaci_id']."\" > \n";
		}  
	}
	
	//$html .= "<script> document.forma.hClasif.value = document.forma.selClasf.value; </script>";

	$strVal = $html;
    $div = "clasif";
    
    $objResponse->assign($div, "innerHTML", $strVal);
   
 
    return $objResponse;
  }

  
//   function CambiarClasifica($valor){
//   	
// 	$objResponse = new xajaxResponse();
//   
// 	
// 	
// 	$objResponse->assign($div, "innerHTML", $strVal);
// 	
// 	return $objResponse; 
//   }

  /**
  *Funcion xajax que permite listas los grados
  */  
  function ListarGrados($val){
  

    $objResponse = new xajaxResponse();  
    //$objResponse->alert($estFz." ".$grado);
    $obPac = AutoCarga::factory("Pacientes","","app","DatosPaciente");
   
    $grado = $obPac->ObtenerGrado($val);
  
    $html .= "      <select name=\"selGrado\" class=\"select\" onChange=\"xajax_EscojerClasifica(document.forma.selEstFuer.value, document.forma.selGrado.value)\" >\n";
    $html .= "        <option value=\"-1\">-- Seleccionar --</option>\n";
    foreach($grado as $key => $arrGrd)
    {
      //($arrGrd['grado_id'] == "1")? $chk = "selected": $chk = "";
      
      $html .= "          <option value=\"".$arrGrd['grado_id']."\" $chk>".$arrGrd['descripcion']."</option>\n";
    }                  
    $html .= "      </select>\n";
  
/*    foreach($grado as $key => $arrGrd)
    {
      ($arrGrd['grado_id'] == "1")? $chk = "selected": $chk = "";
      
      $html .= "          <option value=\"".$arrGrd['grado_id']."\" $chk>".$arrGrd['descripcion']."</option>\n";
    }         
*/    
    $strVal = $html;   
    
    $div = "grado";
    $objResponse->assign($div, "innerHTML", $strVal);
   
    return $objResponse;    
    
  }
  
  /**
  *Funcion xajax que permite ajustar un nombre
  */
  function AjustarNombre($valor, $flag){
    $objResponse = new xajaxResponse();    
    
    //$html .= "<form id=\"forma23\" name=\"forma23\" action=\"#\" method=\"post\">\n";    
    
    $strNomb = explode(" ", $valor);
    
    $html .= "<form id=\"formNombre\" name=\"formNombre\" action=\"#\" method=\"post\">\n";    
    
    $html .= "<table border=\"1\" width=\"60%\" align=\"center\" class=\"modulo_table_list\" \n>";
    
    $html .= "<tr class=\"formulacion_table_list\">\n";    
    $html .= "  <td>TEXTO</td>\n";
    $html .= "  <td>PRIMER</td>\n";
    $html .= "  <td>SEGUNDO</td>\n";
    $html .= "  <td>PRIMER</td>\n";
    $html .= "  <td>SEGUNDO</td>\n";
    $html .= "</tr>\n";
    
    for ($i=0; $i<4; $i++){
    //foreach($zonas as $key => $valor){
      $html .= "  <tr> \n";
      $html .= "     <td> ".$strNomb[$i]." \n";
	  $html .= "        <input type=\"hidden\" name=\"strNomb[]\" value=\"".$strNomb[$i]."\" class=\"input-text\">\n";	     
	  $html .= "     </td> \n";	  

      //$chk = ""; if($valor['zona_residencia'] == $zona) $chk = "checked";	  
	  
	  $chk = "";	  
      //$chk = "checked";
	  //$chk = "defaultChecked";
	  
      $html .= "    <td class=\"modulo_list_claro\" >\n";
      $html .= "      <input type=\"checkbox\" name=\"ajNomb_".$i."\" value=\"0\" $chk onclick=\"Validacion()\" >Nombre ";    
      $html .= "    </td> \n";
      $html .= "    <td class=\"modulo_list_claro\" > \n";
      $html .= "      <input type=\"checkbox\" name=\"ajNomb_".$i."\" value=\"1\" onclick=\"Validacion()\" >Nombre ";
      $html .= "    </td> \n";
      $html .= "    <td class=\"modulo_list_claro\" >\n";
      $html .= "      <input type=\"checkbox\" name=\"ajNomb_".$i."\" value=\"2\" onclick=\"Validacion()\" >Apellido ";    
      $html .= "    </td> \n";
      $html .= "    <td class=\"modulo_list_claro\" > \n";
      $html .= "      <input type=\"checkbox\" name=\"ajNomb_".$i."\" value=\"3\" onclick=\"Validacion()\" >Apellido ";
      $html .= "    </td> \n";
      
      //$html .=  "<td>\n";
      //$html .=  "</td>\n";
      
      $html .= "  </tr> \n";
    }    
    
    $html .= "</table> \n";
    
    $html .= "</form>";  
    
    $html .= "  <table width=\"50%\" align=\"center\">\n";
    $html .= "    <tr>\n";
    $html .= "      <td align=\"center\">\n";
    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" id=\"btnAjus\" name=\"btnAjustar\" value=\"AJUSTAR\" onclick=\"xajax_ColocarNombre(xajax.getFormValues('formNombre'), ".$flag.")\">\n";

    $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" id=\"btnCerr\" name=\"btnCerrar\" value=\"CERRAR\" onclick=\"OcultarSpan();\">\n";

    $html .= "       </td>";    
    
    $html .= "     </tr>\n";
    $html .= "  </table>\n";    
    
    $objResponse->assign("ventana","innerHTML",$html);
	//$objResponse->assign("titulo","innerHTML","hola");
	$objResponse->call("MostrarSpan");     
    
    //$objResponse->alert("Hollaaaa");
    
    return $objResponse;
  }
  
  /**
  *Funcion xajax que permite colocar el Nombre completo de forma correcta, luego de ajustarlo
  */
  function ColocarNombre($arrNomb, $flag){
  	
  	$objResponse = new xajaxResponse();
  		
	$cadNomb .= "Val: ".$arrNomb['ajNomb_0']." \n ";
	$cadNomb .= "Val: ".$arrNomb['ajNomb_1']." \n ";
	$cadNomb .= "Val: ".$arrNomb['ajNomb_2']." \n ";
	$cadNomb .= "Val: ".$arrNomb['ajNomb_3']." \n ";
	//var_dump($arrNomb['ajNomb']);
	
	//var_dump($strNomb[0]);	
	//$objResponse->alert($cadStr);

	if($arrNomb['ajNomb_0'] == "0"){
		$strVal0 = $arrNomb['strNomb'][0];
	}
	if($arrNomb['ajNomb_0'] == "1"){
		$strVal1 = $arrNomb['strNomb'][0];
	}
	if($arrNomb['ajNomb_0'] == "2"){
		$strVal2 = $arrNomb['strNomb'][0];
	}
	if($arrNomb['ajNomb_0'] == "3"){
		$strVal3 = $arrNomb['strNomb'][0];
	}
	
	if($arrNomb['ajNomb_1'] == "0"){
		$strVal0 = $arrNomb['strNomb'][1];
	}
	if($arrNomb['ajNomb_1'] == "1"){
		$strVal1 = $arrNomb['strNomb'][1];
	}
	if($arrNomb['ajNomb_1'] == "2"){
		$strVal2 = $arrNomb['strNomb'][1];
	}
	if($arrNomb['ajNomb_1'] == "3"){
		$strVal3 = $arrNomb['strNomb'][1];
	}		
	
	if($arrNomb['ajNomb_2'] == "0"){
		$strVal0 = $arrNomb['strNomb'][2];
	}
	if($arrNomb['ajNomb_2'] == "1"){
		$strVal1 = $arrNomb['strNomb'][2];
	}
	if($arrNomb['ajNomb_2'] == "2"){
		$strVal2 = $arrNomb['strNomb'][2];
	}
	if($arrNomb['ajNomb_2'] == "3"){
		$strVal3 = $arrNomb['strNomb'][2];
	}
	
	if($arrNomb['ajNomb_3'] == "0"){
		$strVal0 = $arrNomb['strNomb'][3];
	}
	if($arrNomb['ajNomb_3'] == "1"){
		$strVal1 = $arrNomb['strNomb'][3];
	}
	if($arrNomb['ajNomb_3'] == "2"){
		$strVal2 = $arrNomb['strNomb'][3];
	}
	if($arrNomb['ajNomb_3'] == "3"){
		$strVal3 = $arrNomb['strNomb'][3];
	}	


	if($flag == "2"){
		//$html0 .= $strVal0;
		$html0 = "    	<input type=\"text\" class=\"input-text\" name=\"priNombM2\"  value=\"".$strVal0."\" > \n";
		
		//$html1 .= $strVal1;
		$html1 = "    	<input type=\"text\" class=\"input-text\" name=\"segNombM2\"  value=\"".$strVal1."\" > \n";
		
		//$html2 .= $strVal2;
		$html2 = "    	<input type=\"text\" class=\"input-text\" name=\"priApellM2\"  value=\"".$strVal2."\" > \n";
		
		//$html3 .= $strVal3;
		$html3 = "    	<input type=\"text\" class=\"input-text\" name=\"segApellM2\"  value=\"".$strVal3."\" > \n";
		
		$div0 = "priNombMil2";
		$div1 = "segNombMil2";
		$div2 = "priApellMil2";
		$div3 = "segApellMil2";

	}
	else if($flag == "3"){
		//$html0 .= $strVal0;
		$html0 = "    	<input type=\"text\" class=\"input-text\" name=\"priNombM3\"  value=\"".$strVal0."\" > \n";
		
		//$html1 .= $strVal1;
		$html1 = "    	<input type=\"text\" class=\"input-text\" name=\"segNombM3\"  value=\"".$strVal1."\" > \n";
		
		//$html2 .= $strVal2;
		$html2 = "    	<input type=\"text\" class=\"input-text\" name=\"priApellM3\"  value=\"".$strVal2."\" > \n";
		
		//$html3 .= $strVal3;
		$html3 = "    	<input type=\"text\" class=\"input-text\" name=\"segApellM3\"  value=\"".$strVal3."\" > \n";
		
		$div0 = "priNombMil3";
		$div1 = "segNombMil3";
		$div2 = "priApellMil3";
		$div3 = "segApellMil3";

	}
	else{

		$html0 = " <input type=\"text\" maxlength=\"20\" name=\"primernombre\"  value=\"".$strVal0."\" class=\"input-text\" size=\"30\">\n";
		$html1 = " <input type=\"text\" maxlength=\"20\" name=\"segundonombre\"  value=\"".$strVal1."\" class=\"input-text\" size=\"30\">\n";
		$html2 = " <input type=\"text\" maxlength=\"20\" name=\"primerapellido\"  value=\"".$strVal2."\" class=\"input-text\" size=\"30\">\n";
		$html3 = " <input type=\"text\" maxlength=\"20\" name=\"segundoapellido\"  value=\"".$strVal3."\" class=\"input-text\" size=\"30\">\n";		
		
		$div0 = "priNomb";
		$div1 = "segNomb";
		$div2 = "priApell";
		$div3 = "segApell";
	}
	

	$objResponse->assign($div0,"innerHTML",$html0);
	$objResponse->assign($div1,"innerHTML",$html1);
	$objResponse->assign($div2,"innerHTML",$html2);
	$objResponse->assign($div3,"innerHTML",$html3);
	
	//$objResponse->call("OcultarSpan");
	
  	return $objResponse;
  }
  
  /**
  *
  */
  function MostrarMilicia2($pct, $afiliado){
  
  	$objResponse = new xajaxResponse();
  
	$mdl = AutoCarga::factory('PacientesHTML','','app','DatosPaciente');
    $html .= $mdl->FormaMilicia2($pct, $afiliado);
	
	$objResponse->assign("formaM3","innerHTML",$html);
  
  	return $objResponse;
  }

  /**
  *
  */
  function MostrarMilicia3(){
  
  	$objResponse = new xajaxResponse();
  
	$mdl = AutoCarga::factory('PacientesHTML','','app','DatosPaciente');
    $html .= $mdl->FormaMilicia3();
	
	$objResponse->assign("formaM3","innerHTML",$html);
  
  	return $objResponse;
  }  
    
  
?>