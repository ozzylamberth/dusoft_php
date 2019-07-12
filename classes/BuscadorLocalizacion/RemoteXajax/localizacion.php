<?php
	function CambiarPais($key)
	{
		//IncludeClass("BuscadorLocalizacion");
		$bsl = new BuscadorLocalizacionSql();
		$departamentos = $bsl->ObtenerDepartamentos($key);
		$pais = $bsl->ObtenerPaises($key);
		$html = "";
		
    $consLocal = $bsl->ObtenerLocalizacion($key);  
    
		$htmlI  = "<select name=\"mpio\"  class=\"select\">\n";
		$htmlI .= "	<option value=\"-1\">--SELECCIONAR--</option>\n";
		$htmlI .= "</select>\n";
		
		$htmlII  = "<select name=\"cmna\"  class=\"select\">\n";
		$htmlII .= " <option value=\"-1\">--SELECCIONAR--</option>\n";
		$htmlII .= "</select>\n";    
				
		$objResponse = new xajaxResponse();
		if($key != '-1')
		{
			/*if(empty($departamentos))
			{
				if($pais[0]['bloqueado_edicion'] == '0')
				{
					$html .= "<input type=\"text\" name=\"dept\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
					$htmlI = "<input type=\"text\" name=\"mpio\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
          $htmlII = "<input type=\"text\" name=\"cmna\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";          
				}
				else
				{
					$html .= "<label class=\"normal_10AN\">NO HAY DEPARTAMENTOS, EDICION BLOQUEADA</label>\n";
					$htmlI = "<label class=\"normal_10AN\">NO HAY MUNICIPIOS O CIUDADES, EDICION BLOQUEADA</label>\n";
          $htmlII = "<label class=\"normal_10AN\">NO HAY COMUNAS, EDICION BLOQUEADA</label>\n";
				}
			}
			else
			{*/
				$html .= "<select name=\"dept\" onChange=\"xajax_CambiarDepartamento(this.value,document.forma.pais.value)\" class=\"select\">\n";
				$html .= "	<option value=\"-1\">--SELECCIONAR--</option>\n";

				foreach($departamentos as $key => $deptno)
					$html .= "	<option value= \"".$deptno['tipo_dpto_id']."\" >".strtoupper($deptno['departamento'])."</option>\n";
				
				if($pais[0]['bloqueado_edicion'] == '0')
					//$html .= "	<option value= \"0\" >--OTRO--</option>\n";
				
				$html .= "</select>\n";
			//}
			$html = $objResponse->setTildes($html);
			$htmlI = $objResponse->setTildes($htmlI);
      $htmlII = $objResponse->setTildes($htmlII);
		}
		else
		{
			$html .= "<select name=\"dept\" onChange=\"xajax_CambiarDepartamento(this.value,document.forma.pais.value)\" class=\"select\">\n";
			$html .= "	<option value=\"-1\">--SELECCIONAR--</option>\n";				
			$html .= "</select>\n";
		}
		
    
    //$objResponse->alert(print_r($consLocal,true));
    
    if($consLocal){
      $strDepart = $consLocal['equiv_departamento'].":";
      $strCuidad = $consLocal['equiv_municipio'].":";
      $strComuna = $consLocal['equiv_comuna'].":";
    }
    else{
      $strDepart = "DEPARTAMENTO".":";
      $strCuidad = "CIUDAD".":";
      $strComuna = "COMUNA".":";
    }
    
    
    $objResponse->assign("nomDept","innerHTML",$strDepart);
    $objResponse->assign("nomCiud","innerHTML",$strCuidad);
    //$objResponse->assign("nomCmna","innerHTML",$strComuna);    
    
    $objResponse->assign("pais_deptno","innerHTML",$html);
		$objResponse->assign("deptno_ciudad","innerHTML",$htmlI);
    //$objResponse->assign("ciudad_comuna","innerHTML",utf8_decode($htmlII)); 
    		
    return $objResponse;
	}
	
	function CambiarDepartamento($key,$keyI)
	{
		$objResponse = new xajaxResponse();
		$html = "";
		if($key == '0')
		{
/*			$html .= "<input type=\"text\" name=\"dept\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
			$objResponse->assign("pais_deptno","innerHTML",$html);
			
			$html = "<input type=\"text\" name=\"mpio\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
			$objResponse->assign("deptno_ciudad","innerHTML",$html);
      
      $html = "<input type=\"text\" name=\"cmna\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
      $objResponse->assign("ciudad_comuna","innerHTML",$html); */     		
		}
		else if($key == '-1')
			{
				$html .= "<select name=\"mpio\"  class=\"select\" >\n";
				$html .= "	<option value=\"-1\">--SELECCIONAR--</option>\n";
				$html .= "</select>\n";
				$objResponse->assign("deptno_ciudad","innerHTML",$html);	
			}
			else
			{
				$bsl = new BuscadorLocalizacionSql();
				$pais = $bsl->ObtenerPaises($keyI);
				$ciudades = $bsl->ObtenerCiudades($keyI,$key);
        
        $htmlII  = "<select name=\"cmna\"  class=\"select\">\n";
        $htmlII .= " <option value=\"-1\">--SELECCIONAR--</option>\n";
        $htmlII .= "</select>\n";         
        
				if(!empty($ciudades))
				{
					$html .= "<select name=\"mpio\"  class=\"select\" >\n";
					$html .= "	<option value=\"-1\">--SELECCIONAR--</option>\n";

					foreach($ciudades as $key => $ciudad)
						$html .= "	<option value= \"".$ciudad['tipo_mpio_id']."\" $chk>".strtoupper($ciudad['municipio'])."</option>\n";
				
					if($pais[0]['bloqueado_edicion'] == '0')
						//$html .= "	<option value= \"0\" >--OTRO--</option>\n";
				
					$html .= "</select>\n";
				}
				else
				{
// 					$html = "<input type=\"text\" name=\"mpio\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
        $html .= "<select name=\"mpio\"  class=\"select\" >\n";
        $html .= "  <option value=\"-1\">--SELECCIONAR--</option>\n";
        $html .= "</select>\n";
				}
				//$html = $objResponse->setTildes($html);
				$objResponse->assign("deptno_ciudad","innerHTML",utf8_decode($html));
        //$objResponse->assign("ciudad_comuna","innerHTML",utf8_decode($htmlII)); 
			}
		return $objResponse;
	}
  
	
  function CambiarCiudad($key,$keyI,$keyII)
  {
    $objResponse = new xajaxResponse();
    
    $html = "";
    if($key == '0')
    {
      $html .= "<input type=\"text\" name=\"mpio\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
      $objResponse->assign("deptno_ciudad","innerHTML",$html);
      
      $html = "<input type=\"text\" name=\"cmna\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
      //$objResponse->assign("ciudad_comuna","innerHTML",$html);    
    }
    else if($key == '-1')
      {
        $html .= "<select name=\"cmna\"  class=\"select\" onChange=\"if(this.value == '0') xajax_CrearNuevaComuna()\">\n";
        $html .= "  <option value=\"-1\">--SELECCIONAR--</option>\n";
        $html .= "</select>\n";
        $objResponse->assign("deptno_ciudad","innerHTML",$html);  
      }
      else
      {
        $bsl = new BuscadorLocalizacionSql();
        $comunas = $bsl->ObtenerComunas($keyII, $keyI, $key);
        
        if(!empty($comunas))
        {
          $html .= "<select name=\"cmna\"  class=\"select\" onChange=\"if(this.value == '0') xajax_CrearNuevaComuna()\">\n";
          $html .= "  <option value=\"-1\">--SELECCIONAR--</option>\n";

          foreach($comunas as $key => $comuna)
            $html .= "  <option value= \"".$comuna['tipo_comuna_id']."\" $chk>".strtoupper($comuna['comuna'])."</option>\n";
        
           if($pais[0]['bloqueado_edicion'] == '0')
             $html .= "  <option value= \"0\" >--OTRO--</option>\n";
        
          $html .= "</select>\n";
        }
        else
        {
//           $html = "<input type=\"text\" name=\"cmna\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
        $html .= "<select name=\"cmna\"  class=\"select\" onChange=\"if(this.value == '0') xajax_CrearNuevaComuna()\">\n";
        $html .= "  <option value=\"-1\">--SELECCIONAR--</option>\n";
        $html .= "</select>\n";
        }
         $html = $objResponse->setTildes($html);
        
        //$objResponse->assign("ciudad_comuna","innerHTML",$html);
      }
    return $objResponse;
  }	
  
		
	function IngresarNuevoDepartamento($pais,$departamento,$municipio)
	{
		$objResponse = new xajaxResponse();
		$bsl = new BuscadorLocalizacionSql();
		$html = "";
		
		$rst = $bsl->ObtenerDepartamentos($pais,trim($departamento));
		if(!empty($rst))
		{
			$html = "<img src=\"".GetThemePath()."/images/fallo.png\" border=\"0\"> EL DEPARTAMENTO ".$departamento.", YA EXISTE PARA EL PA� INDICADO";
			$html = $objResponse->setTildes($html);
		}
		else
		{
			$datos = $bsl->IngresarDepartamentos($pais,$departamento,$municipio);
			if(!$datos)
				$html = "ERROR ".$bsl->frmError['MensajeError'];
			else
			{
				$objResponse->assign("municipio","value",$datos['municipio']);
				$objResponse->assign("departamento","value",$datos['departamento']);
				$objResponse->call("ContinuarSeleccionPais");
			}
		}
		$objResponse->assign("error","innerHTML",$html);
		return $objResponse;
	}
	
	function IngresarNuevoMunicipio($pais,$departamento,$municipio)
	{
		$objResponse = new xajaxResponse();
		$bsl = new BuscadorLocalizacionSql();
		$html = "";
		
		$rst = $bsl->ObtenerCiudades($pais,$departamento,trim($municipio));
		if(!empty($rst))
		{
			$html = "<img src=\"".GetThemePath()."/images/fallo.png\" border=\"0\"> EL MUNICIPIO ".$departamento.", YA EXISTE PARA EL DEPARTAMENTO INDICADO";
			$html = $objResponse->setTildes($html);
		}
		else
		{
			$datos = $bsl->IngresarMunicipios($pais,$departamento,$municipio);
			if(!$datos)
				$html = "ERROR ".$bsl->frmError['MensajeError'];
			else
			{
				$objResponse->assign("municipio","value",$datos['municipio']);
				$objResponse->call("ContinuarSeleccionPais");
			}
		}
    
		$objResponse->assign("error","innerHTML",$html);
		return $objResponse;
	}
	
	
	function CrearNuevoMunicipio()
	{
		$objResponse = new xajaxResponse();
		$html = "<input type=\"text\" name=\"mpio\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
		$objResponse->assign("deptno_ciudad","innerHTML",$html);
		return $objResponse;
	}
	
	
	function CrearNuevaComuna(){
	
		$objResponse = new xajaxResponse();
		$html = "<input type=\"text\" name=\"cmna\" style=\"width:80%\" maxlength=\"30\" class=\"input-text\">\n";
		//$objResponse->assign("ciudad_comuna","innerHTML",$html);
		return $objResponse;
	}
   
  
  	
?>