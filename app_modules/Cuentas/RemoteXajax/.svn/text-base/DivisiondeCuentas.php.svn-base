<?php
	
	  
  function reqCambiarCargoPlan($transaccion,$valor){

    $objResponse = new xajaxResponse();     
    (list($indice,$plan)=(explode('||//',$valor)));        
    ActualizarPlanValor($transaccion,$indice,$plan);    
    return $objResponse;
  }
  
  
  function reqCambiarAbonoPlan($abono,$valor){
    
    $objResponse = new xajaxResponse();
    (list($prefijo,$reciboCaja)=(explode('||//',$abono)));
    (list($indice,$plan,$Cuenta,$fecha_ingcaja,$total_efectivo,$total_cheques,$total_tarjetas,$total_bonos,$total_abono)=(explode('||//',$valor)));     
    if($indice!='0'){      
      ActualizarPlanAbono($plan,$indice,$Cuenta,$prefijo,$reciboCaja,
                                $fecha_ingcaja,$total_efectivo,$total_cheques,
                                $total_tarjetas,$total_bonos,$total_abono);    
    }else{      
      EliminarPlanAbono($prefijo,$reciboCaja,$Cuenta);    
    }                            
    return $objResponse;
  }
  
  function reqCambiarCargoPlanTotalPage($seleccion,$Cuenta,$limite,$off,$valor,$plan_ini,$pagina_actual){
    
    $objResponse = new xajaxResponse();   
    
    
    if($seleccion=='true'){              
      (list($indice,$plan)=(explode('||//',$valor)));              
    }else{ 
      (list($indice,$plan)=(explode('||//',$valor)));             
      $indice='0';
      $plan=$plan_ini;
    }  
    
    
    $cargos=DetalleDivisionCuenta($Cuenta,$limite,$off);    
    unset($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']);
    $_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']['SELECCION_TOTAL'][$indice]=$pagina_actual;     
    for($i=0;$i<sizeof($cargos);$i++){
      ActualizarPlanValor($cargos[$i]['transaccion'],$indice,$plan);                            
    }
    
          
    $det=DetalleDivisionCuenta($Cuenta,$limite,$off);    
    $contcols=(sizeof($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'])); 
    $html .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";          
    $html .= "          <tr class=\"modulo_table_list_title\">";      
    $html .= "            <td align=\"center\" colspan=\"".(12+$contcols)."\">CARGOS DE LA CUENTA ACTUAL</td>";
    $html .= "          </tr>";      
    $html .= "          <tr class=\"modulo_table_list_title\">";      
    $html .= "            <td align=\"center\" colspan=\"11\">&nbsp;</td>";            
    ksort($_SESSION['DIVISION_CUENTA_VARIOS_PLANES']);
    foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
      foreach($vector as $plan=>$plan_nom){
        if($indice!='0'){
          $chequeado='';
          if($_SESSION['DIVISION_CUENTA_VARIOS_PLANES1']['SELECCION_TOTAL'][$indice]==$pagina_actual){$chequeado='checked';}
          $html .= "            <td align=\"center\"><input type=\"checkbox\" name=\"SeleccionTotal$indice\" value=\"".$indice."||//".$plan."\" onclick=\"xajax_reqCambiarCargoPlanTotalPage(this.checked,'$Cuenta','$limite','$off',this.value,'$plan_ini','$pagina_actual')\" align=\"center\" $chequeado></td>";
        }else{
          $plan_ini=$plan;
          $html .= "            <td align=\"center\">&nbsp;</td>";      
        }
      }
    }     
    $html .= "          </tr>";    
    $html .= "          <tr class=\"modulo_table_list_title\">";
    $html .= "            <td width=\"7%\">TARIFARIO</td>";
    $html .= "            <td width=\"5%\">CARGO</td>";
    $html .= "            <td width=\"10%\">CODIGO</td>";
    $html .= "            <td>DESCRIPCION</td>";
    $html .= "            <td width=\"8%\">FECHA CARGO</td>";
    $html .= "            <td width=\"5%\">HORA</td>";
    $html .= "            <td width=\"7%\">CANT</td>";
    $html .= "            <td width=\"8%\">VALOR CARGO</td>";
    $html .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
    $html .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
    $html .= "            <td width=\"10%\">DPTO.</td>";     
    foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
      foreach($vector as $plan=>$plan_nom){
        if($indice!='0'){$indice=$indice;}else{$indice='';}
        $html .= "            <td width=\"3%\">$indice</td>";
      }
    }    
    $html .= "          </tr>";
    $car=$cubi=$nocub=0;
    if(!empty($det))
    {          
      for($i=0;$i<sizeof($det);$i++){                 
        if($i % 2) {  $estilo="modulo_list_claro";  }
        else {  $estilo="modulo_list_oscuro";   }          
        //suma los totales del final
        $car+=$det[$i][valor_cargo];
        $cubi+=$det[$i][valor_cubierto];
        $nocub+=$det[$i][valor_nocubierto];                    
        $html .= "            <tr class=\"$estilo\">";
        $html .= "            <td width=\"7%\" align=\"center\">".$det[$i][tarifario_id]."</td>";
        $html .= "            <td width=\"5%\" align=\"center\">".$det[$i][cargo]."</td>";
        $html .= "            <td width=\"10%\" align=\"center\">".$det[$i][codigo_producto]."</td>";
        $html .= "            <td>".$det[$i][descripcion]."</td>";
        $html .= "            <td width=\"8%\" align=\"center\">".FechaStampDiv($det[$i][fecha_cargo])."</td>";
        $html .= "            <td width=\"5%\" align=\"center\">".HoraStampDiv($det[$i][fecha_cargo])."</td>";
        $html .= "            <td width=\"7%\" align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
        $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
        $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
        $html .= "            <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";                                         
        $html .= "            <td>".$det[$i][departamento]."</td>";
        $valor=$det[$i-1][transaccion].'||//'.$det[$i-1][cargo_cups].'||//'.$det[$i-1][codigo_agrupamiento_id].'||//'.$det[$i-1][consecutivo];
        //$html .= "            <td align=\"center\"><a href=\"javascript:CargoOtraCuenta(document.forma,'$valor','$Cuenta');\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";
        foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
          foreach($vector as $plan=>$plan_nom){
            $che='';                           
            if($det[$i][cuenta]==$indice){$che='checked';}          
            $html .= "            <td width=\"3%\" align=\"center\"><input $che title=\"$plan_nom\" type=\"radio\" name=\"".$det[$i][transaccion]."\" value=\"".$indice."||//".$plan."\" onclick=\"xajax_reqCambiarCargoPlan(this.name,this.value)\"></td>";              
          }
        }
        $html .= "            </tr>";                           
      }
      if($i % 2) {  $estilo="modulo_list_claro";  }
      else {  $estilo="modulo_list_oscuro";   }
      $html .= "          <tr class=\"$estilo\">";
      $html .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
      $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
      $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
      $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
      $html .= "            <td>&nbsp;</td>";
      //$html .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
      foreach($_SESSION['DIVISION_CUENTA_VARIOS_PLANES'] as $indice=>$vector){        
        foreach($vector as $plan=>$plan_nom){
          $html .= "            <td width=\"3%\"></td>";
        }
      } 
      $html .= "          </tr>"; 
    }      
    $html .= "          </table>"; 
    $objResponse->assign("capa_cargos","innerHTML",$html); 
    return $objResponse;
  }
  
  
	/**********************************************************************************
    * Actualiza el plan del cargo de la cuenta.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function ActualizarPlanValor($transaccion,$indice,$plan){          
        $query = "UPDATE tmp_division_cuenta
                 SET cuenta='".$indice."',plan_id='".$plan."'
                 WHERE transaccion='".$transaccion."'";
        if(!$resultado = ConexionBaseDatosDiv($query))
        return false;         
        $resultado->Close();    
        return true;
    }
    
    
    /**********************************************************************************
    * Actualiza el plan del abono en la cuenta.
    *
    * @access public        
    * @return boolean
    ***********************************************************************************/ 
    
    function ActualizarPlanAbono($plan,$indice,$Cuenta,$prefijo,$recibo_caja,
                                $fecha_ingcaja,$total_efectivo,$total_cheques,
                                $total_tarjetas,$total_bonos,$total_abono){
              
        $query = "INSERT INTO tmp_division_cuenta_abonos(plan_id, numerodecuenta,
                                                        recibo_caja, prefijo,
                                                        fecha_ingcaja, total_abono,
                                                        total_efectivo, total_cheques,
                                                        total_tarjetas, total_bonos,
                                                        cuenta)
                          VALUES(".$plan.",".$Cuenta.",".$recibo_caja.",'".$prefijo."',
                          '".$fecha_ingcaja."',".$total_abono.", ".$total_efectivo.",
                          ".$total_cheques.",".$total_tarjetas.",".$total_bonos.",
                          '".$indice."')";
        if(!$resultado = ConexionBaseDatosDiv($query))
        return false;         
        $resultado->Close();    
        return true; 
    }
    
    /**********************************************************************************
    * Elimina el abono en la cuenta.
    *
    * @access public          
    * @return array
    ***********************************************************************************/ 
    
    function EliminarPlanAbono($prefijo,$recibo_caja,$Cuenta){
              
        $query = "DELETE FROM tmp_division_cuenta_abonos
                 WHERE recibo_caja='".$recibo_caja."' 
                 AND prefijo='".$prefijo."'                 
                 AND numerodecuenta='".$Cuenta."'";
        if(!$resultado = ConexionBaseDatosDiv($query))
        return false;         
        $resultado->Close();    
        return true; 
    }
    
    /**********************************************************************************
    * Consulta el detalle de la division de la cuenta.
    *
    * @access public          
    * @return array
    ***********************************************************************************/ 
    
    function DetalleDivisionCuenta($Cuenta,$limite,$off){
              
        $query = "SELECT a.*,d.codigo_producto,
                          (CASE WHEN d.consecutivo IS NOT NULL 
                          THEN e.descripcion 
                          ELSE b.descripcion 
                          END) as descripcion,
                          c.plan_descripcion,
                          (CASE a.facturado WHEN 1 
                          THEN a.valor_cargo 
                          ELSE 0 
                          END) as fac,dpto.descripcion as departamento
                   FROM tmp_division_cuenta as a
                   LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                   LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                   LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                   LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                   , tarifarios_detalle as b, planes as c
                   WHERE a.numerodecuenta=$Cuenta AND
                         a.cargo=b.cargo 
                   AND a.tarifario_id=b.tarifario_id
                   AND a.plan_id=c.plan_id
                   ORDER BY a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";  
          $query.=" LIMIT " . $limite . " OFFSET ".$off."";         
          if(!$resultado = ConexionBaseDatosDiv($query))
          return false;
          while(!$resultado->EOF){
              $vars[]=$resultado->GetRowAssoc($toUpper=false);
              $resultado->MoveNext();
          }        
          $resultado->Close();    
          return $vars; 
    }  
    
    /****************************************************************
    * Se encarga de separar la fecha del formato timestamp
    * @access private
    * @return string
    * @param date fecha
    */
    
    function FechaStampDiv($fecha)
    {
      if($fecha){
        $fech = strtok ($fecha,"-");
        for($l=0;$l<3;$l++)
        {
          $date[$l]=$fech;
          $fech = strtok ("-");
        }
        //return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
        return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
      }
    }


    /****************************************************************
    * Se encarga de separar la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
    */
    function HoraStampDiv($hora)
    {
      $hor = strtok ($hora," ");
      for($l=0;$l<4;$l++)
      {
        $time[$l]=$hor;
        $hor = strtok (":");
      }
          $x=explode('.',$time[3]);
      return  $time[1].":".$time[2].":".$x[0];
    }
    
	
	 /**********************************************************************************
    * Funcion que permite realizar la conexion a la base de datos y ejecutar la 
    * consulta sql 
    *
    * @access public  
    * @param  string  $sql  sentencia sql a ejecutar 
    * @return rst 
    ************************************************************************************/
  function ConexionBaseDatosDiv($sql)
  {
    list($dbconn)=GetDBConn();
    //$dbconn->debug=true;
    $rst = $dbconn->Execute($sql);
      
    if ($dbconn->ErrorNo() != 0)
    {
      $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
      echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
      return false;
    }
    return $rst;
  }    

?>