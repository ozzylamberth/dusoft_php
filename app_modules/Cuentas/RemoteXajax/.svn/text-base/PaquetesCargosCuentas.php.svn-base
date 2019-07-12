<?php
	
	  
  function FacturarCargoPaquete($valor,$chequeado){
    
    $objResponse = new xajaxResponse();     
    (list($cuenta,$paquete,$transaccion)=(explode('||//',$valor))); 
    if($chequeado=='true'){$facturado='1';}else{$facturado='0';}       
    ActualizarFacturadoCargo($cuenta,$paquete,$transaccion,$facturado); 
    $html=ActualizarFormaPaquetesCuenta($cuenta);   
    $objResponse->assign("PaquetesCargosCuentas","innerHTML",$html);
    $html='';
    $objResponse->assign("CargosCuentas","innerHTML",$html); 
    return $objResponse;
  }
  
  function AdicionarCargoPaquete($paquete,$cuenta){
    
    $objResponse = new xajaxResponse();     
    $html=ConsultarCargosCuenta($paquete,$cuenta,0);
    $objResponse->assign("CargosCuentas","innerHTML",$html); 
    return $objResponse;
  }
  
  function InsertarCargoPaquete($valor){
    
    $objResponse = new xajaxResponse();
    (list($cuenta,$paquete,$transaccion)=(explode('||//',$valor)));         
    ActualizarCargoPaquete($cuenta,$paquete,$transaccion);
    $html=ActualizarFormaPaquetesCuenta($cuenta);   
    $objResponse->assign("PaquetesCargosCuentas","innerHTML",$html); 
    $html=ConsultarCargosCuenta($paquete,$cuenta,0);
    $objResponse->assign("CargosCuentas","innerHTML",$html);              
    return $objResponse;
  }
  
  function EliminarCargoPaquete($cuenta,$paqueteId,$transaccion){
    
    $objResponse = new xajaxResponse();             
    EliminaRegistroCargo($cuenta,$paqueteId,$transaccion); 
    $html=ActualizarFormaPaquetesCuenta($cuenta);   
    $objResponse->assign("PaquetesCargosCuentas","innerHTML",$html);
    $html='';
    $objResponse->assign("CargosCuentas","innerHTML",$html);  
    return $objResponse;
  }
  
  function InsertarNuevoPaquete($cuenta){    
    $objResponse = new xajaxResponse();    
    $html=ConsultarCargosCuenta('',$cuenta,1);
    $objResponse->assign("CargosCuentas","innerHTML",$html);              
    return $objResponse;
  }
  
  function InsertarCargoNuevoPaquete($valor){
    $objResponse = new xajaxResponse();
    (list($cuenta,$transaccion)=(explode('||//',$valor))); 
    $query="SELECT nextval('cuentas_detalle_paquete_codigo_id_seq')";
    if(!$resultado = ConexionBaseDatos1($query))
    return false;    
    $paquete=$resultado->fields[0];
    ActualizarCargoPaquete($cuenta,$paquete,$transaccion);
    $html=ActualizarFormaPaquetesCuenta($cuenta);   
    $objResponse->assign("PaquetesCargosCuentas","innerHTML",$html); 
    $html=ConsultarCargosCuenta($paquete,$cuenta,0);
    $objResponse->assign("CargosCuentas","innerHTML",$html); 
    return $objResponse;    
  }
  
  function InsertarTodosCargosPaquete($paquete,$Cuenta,$nuevoPaquete){
    $objResponse = new xajaxResponse();    
    if($nuevoPaquete==1){
      $query="SELECT nextval('cuentas_detalle_paquete_codigo_id_seq')";
      if(!$resultado = ConexionBaseDatos1($query))
      return false;    
      $paquete=$resultado->fields[0];
    }
    $query='';
    $VCargos=BuscarCargosCuentaPaquetes($Cuenta);
    for($i=0;$i<sizeof($VCargos);$i++){      
      $query.="UPDATE cuentas_detalle
                 SET paquete_codigo_id='$paquete',sw_paquete_facturado='0'
                 WHERE numerodecuenta='$Cuenta'                 
                 AND transaccion='".$VCargos[$i]['transaccion']."';";        
      
    }                      
    if(!$resultado = ConexionBaseDatos1($query))
    return false;        
    $html=ActualizarFormaPaquetesCuenta($Cuenta);   
    $objResponse->assign("PaquetesCargosCuentas","innerHTML",$html); 
    $html='';
    $objResponse->assign("CargosCuentas","innerHTML",$html); 
    return $objResponse;  
      
  }
  
  function EliminarVistaCargosCuenta(){
    $objResponse = new xajaxResponse();
    $html='';
    $objResponse->assign("CargosCuentas","innerHTML",$html); 
    return $objResponse;  
  }
  
  
	 /**********************************************************************************
    * Actualiza el facturado del cargo en el paquete.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function ActualizarFacturadoCargo($cuenta,$paquete,$transaccion,$facturado){          
        $query = "UPDATE cuentas_detalle
                 SET sw_paquete_facturado='$facturado'
                 WHERE numerodecuenta='$cuenta'
                 AND paquete_codigo_id='$paquete' 
                 AND transaccion='$transaccion'";
        if(!$resultado = ConexionBaseDatos1($query))
        return false;         
        $resultado->Close();    
        return true;
    }
    
    /**********************************************************************************
    * Actualiza el cargo de la cuenta al paquete seleccionado.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function EliminaRegistroCargo($cuenta,$paqueteId,$transaccion){          
        $query = "UPDATE cuentas_detalle
                 SET paquete_codigo_id=NULL,sw_paquete_facturado='0'
                 WHERE numerodecuenta='$cuenta'                 
                 AND transaccion='$transaccion'
                 AND paquete_codigo_id='$paqueteId'";
        if(!$resultado = ConexionBaseDatos1($query))
        return false;         
        $resultado->Close();    
        return true;
    }
    
    /**********************************************************************************
    * Actualiza el cargo de la cuenta al paquete seleccionado.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function ActualizarCargoPaquete($cuenta,$paquete,$transaccion){          
        $query = "UPDATE cuentas_detalle
                 SET paquete_codigo_id='$paquete',sw_paquete_facturado='0'
                 WHERE numerodecuenta='$cuenta'                 
                 AND transaccion='$transaccion'";
        if(!$resultado = ConexionBaseDatos1($query))
        return false;         
        $resultado->Close();    
        return true;
    }
    
    /**********************************************************************************
    * Actualiza la forma que muestra los paquetes en la cuenta.
    *
    * @access public         
    * @return boolean
    ***********************************************************************************/ 
    
    function ActualizarFormaPaquetesCuenta($Cuenta){
      $det=BuscarPaquetesCuenta($Cuenta);
      if($det){        
        $html .= "   <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";                  
        foreach($det as $paqueteId=>$vector){
          $html .= "          <tr class=\"modulo_table_list_title\">";      
          $html .= "            <td align=\"left\" colspan=\"13\">CARGOS DEL PAQUETE No. $paqueteId&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:CallAjax('$paqueteId','$Cuenta');\" class=\"TurnoInactivo\">ADICIONAR CARGO AL PAQUETE</a></td>";
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
          $html .= "            <td width=\"5%\">&nbsp;</td>";                 
          $html .= "            <td width=\"5%\">&nbsp;</td>";                 
          $html .= "          </tr>"; 
          $i=0;
          foreach($vector as $transaccion => $vectorPaquete){ 
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }         
            if($vectorPaquete[sw_paquete_facturado]=='0'){                
              $estilo='agendadomfes';
            }
            $html .= "       <tr  class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";              
            $html .= "          <td width=\"7%\" align=\"center\">".$vectorPaquete[tarifario_id]."</td>";
            $html .= "          <td width=\"5%\" align=\"center\">".$vectorPaquete[cargo]."</td>";
            $html .= "          <td width=\"10%\" align=\"center\">".$vectorPaquete[codigo_producto]."</td>";
            $html .= "          <td>".$vectorPaquete[descripcion]."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FechaStamp($vectorPaquete[fecha_cargo])."</td>";
            $html .= "          <td width=\"5%\" align=\"center\">".HoraStamp($vectorPaquete[fecha_cargo])."</td>";
            $html .= "          <td width=\"7%\" align=\"center\">".FormatoValor($vectorPaquete[cantidad])."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FormatoValor($vectorPaquete[valor_cargo])."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FormatoValor($vectorPaquete[valor_nocubierto])."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FormatoValor($vectorPaquete[valor_cubierto])."</td>";                                         
            $html .= "          <td>".$vectorPaquete[departamento]."</td>";            
            $html .= "          <td align=\"center\"><a href=\"javascript:CallAjaxUno('$Cuenta','$paqueteId','$transaccion')\"><img src=\"".GetThemePath()."/images/elimina.png\" border='0' title=\"Eliminar Cargo\"></a></td>";            
            $che='';
            if($vectorPaquete[sw_paquete_facturado]=='1'){                
              $che='checked';
              $title='Facturado';
            }else{
              $title='No Facturado';
            }
            $html .= "          <td align=\"center\"><input title=\"$title\" $che type=\"checkbox\" value=\"".$Cuenta."||//".$paqueteId."||//".$transaccion."\" onclick=\"xajax_FacturarCargoPaquete(this.value,this.checked)\" name=\"paquete$paqueteId\"></td>";            
            $html .= "       </tr>";     
            $i++;
          }
        }
        $html .= "   </table>";
      }
      return $html;
    }
    
    function ConsultarCargosCuenta($paquete,$Cuenta,$nuevoPaquete){
      $det=BuscarCargosCuentaPaquetes($Cuenta);  
      if($det){              
        $html .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";          
        $html .= "          <tr class=\"modulo_table_list_title\">";  
        if($nuevoPaquete==1){    
          $html .= "            <td align=\"center\" colspan=\"11\">INSERTAR CARGOS AL NUEVO PAQUETE</td>";
          $html .= "            <td align=\"center\"><a href=\"javascript:CallAjaxTres();\"><img src=\"".GetThemePath()."/images/fallo.png\" border='0' title=\"Eliminar Vista Cargos\"></a></td>";
        }else{
          $html .= "            <td align=\"center\" colspan=\"11\">INSERTAR CARGOS AL PAQUETE No. $paquete</td>";
          $html .= "            <td align=\"center\"><a href=\"javascript:CallAjaxTres();\"><img src=\"".GetThemePath()."/images/fallo.png\" border='0' title=\"Eliminar Vista Cargos\"></a></td>";
        }
        $html .= "          </tr>";              
        $html .= "          <tr class=\"modulo_table_title\">";      
        $html .= "            <td align=\"center\" colspan=\"11\">CARGOS DE LA CUENTA ACTUAL</td>";
        $html .= "            <td align=\"center\"><a href=\"javascript:CallAjaxCuatro('$paquete','$Cuenta','$nuevoPaquete');\"><img src=\"".GetThemePath()."/images/arriba.png\" border='0' title=\"Insertar Todos Los Cargos al Paquete\"></a></td>";
        $html .= "          </tr>";              
        $html .= "          <tr class=\"modulo_table_title\">";
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
        $html .= "            <td width=\"5%\">&nbsp;</td>";                            
        $html .= "          </tr>";
        $car=$cubi=$nocub=0;
        if(!empty($det)){        
          for($i=0; $i<sizeof($det);$i++){          
            if($i % 2) {  $estilo="modulo_list_claro";  }
            else {  $estilo="modulo_list_oscuro";   }          
            //suma los totales del final
            $car+=$det[$i][valor_cargo];
            $cubi+=$det[$i][valor_cubierto];
            $nocub+=$det[$i][valor_nocubierto];                    
            $html .= "       <tr class=\"$estilo\">";
            $html .= "          <td width=\"7%\" align=\"center\">".$det[$i][tarifario_id]."</td>";
            $html .= "          <td width=\"5%\" align=\"center\">".$det[$i][cargo]."</td>";
            $html .= "          <td width=\"10%\" align=\"center\">".$det[$i][codigo_producto]."</td>";
            $html .= "          <td>".$det[$i][descripcion]."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FechaStamp($det[$i][fecha_cargo])."</td>";
            $html .= "          <td width=\"5%\" align=\"center\">".HoraStamp($det[$i][fecha_cargo])."</td>";
            $html .= "          <td width=\"7%\" align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
            $html .= "          <td width=\"8%\" align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";                                         
            $html .= "          <td>".$det[$i][departamento]."</td>";             
            if($nuevoPaquete==1){
              $html .= "          <td align=\"center\"><input type=\"checkbox\" value=\"".$Cuenta."||//".$det[$i][transaccion]."\" onclick=\"xajax_InsertarCargoNuevoPaquete(this.value)\" name=\"paquete".$det[$i][transaccion]."\"></td>";           
            }else{
              $html .= "          <td align=\"center\"><input type=\"checkbox\" value=\"".$Cuenta."||//".$paquete."||//".$det[$i][transaccion]."\" onclick=\"xajax_InsertarCargoPaquete(this.value)\" name=\"paquete".$det[$i][transaccion]."\"></td>";           
            }
            $html .= "       </tr>";         
          }                  
          if($i % 2) {  $estilo="modulo_list_claro";  }
          else {  $estilo="modulo_list_oscuro";   }
          $html .= "         <tr class=\"$estilo\">";
          $html .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
          $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
          $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
          $html .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";          
          $html .= "            <td>&nbsp;</td>";          
          $html .= "            <td>&nbsp;</td>";          
          $html .= "         </tr>";        
        }      
        $html .= "     </table>";        
      }
      return $html;
    }  
    
    /**********************************************************************************
    * Busca los cargos de la cuenta que no hacen parte de un paquete
    *
    * @access public 
    * @params int $Cuenta numero de la cuenta donde se modificaran los paquetes de cargos
    * @return array
    ***********************************************************************************/
    
    function BuscarCargosCuentaPaquetes($Cuenta){                   
        $query = "SELECT a.*,d.codigo_producto,
                        (CASE WHEN d.consecutivo IS NOT NULL 
                        THEN e.descripcion 
                        ELSE b.descripcion 
                        END) as descripcion,                          
                        (CASE a.facturado WHEN 1 
                        THEN a.valor_cargo 
                        ELSE 0 
                        END) as fac,dpto.descripcion as departamento
                  FROM cuentas_detalle as a
                  LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                  LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                  LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                  LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                  , tarifarios_detalle as b
                  WHERE a.numerodecuenta=$Cuenta 
                  AND a.cargo=b.cargo 
                  AND a.tarifario_id=b.tarifario_id                   
                  AND a.paquete_codigo_id IS NULL
                  ORDER BY a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";                  
        
        if(!$resultado = ConexionBaseDatos1($query))
        return false;
        while(!$resultado->EOF){
            $vars[]=$resultado->GetRowAssoc($toUpper=false);
            $resultado->MoveNext();
        }
        $resultado->Close();          
        return $vars;
    }
    
    /**********************************************************************************
    * Busca los paquetes creados para los cargos de la ceunta
    *
    * @access public 
    * @params int $Cuenta numero de la cuenta donde se modificaran los paquetes de cargos
    * @return array
    ***********************************************************************************/
      
    function BuscarPaquetesCuenta($Cuenta){
      GLOBAL $ADODB_FETCH_MODE;      
      $query = "SELECT a.*,d.codigo_producto,
                      (CASE WHEN d.consecutivo IS NOT NULL 
                      THEN e.descripcion 
                      ELSE b.descripcion 
                      END) as descripcion,                          
                      (CASE a.facturado WHEN 1 
                      THEN a.valor_cargo 
                      ELSE 0 
                      END) as fac,dpto.descripcion as departamento
                FROM cuentas_detalle as a
                LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                ,tarifarios_detalle as b
                WHERE a.numerodecuenta=$Cuenta 
                AND a.cargo=b.cargo 
                AND a.tarifario_id=b.tarifario_id                   
                AND a.paquete_codigo_id IS NOT NULL
                ORDER BY a.paquete_codigo_id,a.sw_paquete_facturado DESC,a.fecha_cargo,a.codigo_agrupamiento_id,a.transaccion";                          
      
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if(!$resultado = ConexionBaseDatos1($query))
        return false;
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;        
        while($datos=$resultado->FetchRow()){
          $vars[$datos['paquete_codigo_id']][$datos['transaccion']]=$datos;
        }
        $resultado->Close();                
        return $vars;          
    
    }
    
    /*****************************************************
    * Se encarga de separar la fecha del formato timestamp
    * @access private
    * @return string
    * @param date fecha
    ******************************************************/
    function FechaStamp($fecha){
    
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

    /********************************************************
    * Se encarga de separar la hora del formato timestamp
    * @access private
    * @return string
    * @param date hora
    *******************************************************/
    function HoraStamp($hora){
    
      $hor = strtok ($hora," ");
      for($l=0;$l<4;$l++){
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
  function ConexionBaseDatos1($sql)
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