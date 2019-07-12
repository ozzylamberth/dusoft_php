<?php


  function ValidarFechaVencimiento($valor,$cantidad){
    
    $objResponse = new xajaxResponse();     
    $ventana=CrearVentanaFechasVencimientos($valor,$cantidad);   
    $objResponse->assign("d2Contents","innerHTML",$ventana);
    $objResponse->call('Iniciar');
    $objResponse->call('MostrarVentana');
    return $objResponse;
    
  }
  
  function CrearVentanaFechasVencimientos($valor,$cantidad,$mensaje,$fechaVencimiento,$lote,$cantidadLote){
    (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite)=explode('||//',$valor));
    $ventana.= "  <form name=\"formaFechaVence\" action=\"$action\" method=\"post\">";            
    $ventana.= "  <table align=\"center\">";
    if($mensaje){
      $ventana.= "    <tr align=\"center\"><td align=\"center\" class=\"label_error\" colspan=\"6\">$mensaje</td></tr>";
    }
    $ventana.= "    <tr align=\"center\">";
    $ventana.= "    <td align=\"center\" class=\"Menu\" colspan=\"6\"><b>CANTIDAD TOTAL A DEVOLVER: $cantidad</b></td>";
    $ventana.= "    </tr>";
    foreach($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto] as $loteT=>$arreglo){
        (list($cantidadLoteT,$fechaVencimientoT)=explode('||//',$arreglo));
        $sumacantidadLoteT+=$cantidadLoteT;
    }
    if($sumacantidadLoteT>0){
      $ventana.= "    <tr align=\"center\">";
      $ventana.= "    <td align=\"center\" class=\"Menu\" colspan=\"6\"><b>CANTIDAD INSERTADA EN LOTES: $sumacantidadLoteT</b></td>";
      $ventana.= "    </tr>";  
    }  
    $ventana.= "    <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">$codigo_producto - BODEGA: $nom_bodega</td></tr>\n";    
    $ventana.= "    <tr class=\"modulo_list_claro\">\n";
    $ventana.= "      <td class=\"label\">FECHA VENCIMIENTO</td>\n";
    $ventana.= "      <td align=\"center\">";    
    $ventana.= "        <input type=\"text\" name=\"fechaVencimiento\" readonly value=\"$fechaVencimiento\" class=\"input-text\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\">\n";
    $ventana.= "        <a href=\"javascript:LlamarCalendariofechaVencimiento()\"><img onMouseOver=\"window.status='Calendario';return true;\" onMouseOut=\"window.status=''; return true;\" src=\"themes/HTML/AzulXp/images/calendario/calendario.png\" border=0 alt=\"Ver Calendario\"></a> [dd/mm/aaaa]";
    $ventana.= "      </td>";
    $ventana.= "      <td class=\"label\">No. LOTE</td>\n";
    $ventana.= "      <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"lote\" value=\"$lote\"></td>\n";
    $ventana.= "      <td class=\"label\">CANTIDAD</td>\n";
    $ventana.= "      <td align=\"center\"><input class=\"input-text\" type=\"text\" name=\"cantidadLote\" value=\"$cantidadLote\"></td>\n";
    $ventana.= "    <tr><td></td></tr>\n";
    $ventana.= "    <tr><td colspan=\"6\" align=\"center\">\n";
    $ventana.= "    <input type=\"button\" class=\"input-submit\" name=\"insertar\" value=\"INSERTAR\" onclick=\"xajax_InsertarFechaVencimiento(document.formaFechaVence.fechaVencimiento.value,document.formaFechaVence.lote.value,document.formaFechaVence.cantidadLote.value,'$valor','$cantidad')\"></td></tr>\n";    
    $ventana.= "  </table><BR>"; 
    $ventana.= MostrarFechasVencimiento($codigo_producto,$valor,$cantidad);    
    $ventana.= "  </form>";
    return $ventana;
    
  }
  
  function MostrarFechasVencimiento($codigo_producto,$valor,$cantidad){
    (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite)=explode('||//',$valor));
    if($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto]){
      $ventana.= "   <table align=\"center\" width=\"70%\">";
      $ventana.= "    <tr class=\"modulo_table_title\" align=\"center\">";
      $ventana.= "      <td align=\"center\" width=\"30%\">FECHA VENCIMIENTO</td>\n";
      $ventana.= "      <td align=\"center\" width=\"40%\">No. LOTE</td>\n";
      $ventana.= "      <td align=\"center\">CANTIDAD</td>\n";
      $ventana.= "      <td align=\"center\" width=\"5%\">&nbsp;</td>\n";
      $ventana.= "    </tr>";
      $j=0;
      foreach($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto] as $loteT=>$arreglo){        
        (list($cantidadLoteT,$fechaVencimientoT)=explode('||//',$arreglo));
        if($j % 2){$estilo="modulo_list_claro";}else{$estilo="modulo_list_oscuro";   }         
        $ventana.= "    <tr class=\"$estilo\">";
        (list($dia,$mes,$ano)=explode('/',$fechaVencimientoT));
        $ventana.= "    <td align=\"center\">".$ano."-".$mes."-".$dia."</td>";
        $ventana.= "    <td>$loteT</td>";
        $ventana.= "    <td>$cantidadLoteT</td>";
        $ventana.= "    <td><a href=\"javascript:CallEliminaFechaVence('$codigo_producto','$loteT','$valor','$cantidad')\"><img border = 0 src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
        $ventana.= "    </tr>";                
        $j++;
      }
      $ventana.= "  </table>";
    }
    return $ventana;
    
  }
  
  function InsertarFechaVencimiento($fechaVencimiento,$lote,$cantidadLote,$valor,$cantidad){ 
    $inserta=0;
    $objResponse = new xajaxResponse();     
    (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite)=explode('||//',$valor));        
    if(empty($fechaVencimiento)||empty($lote)||empty($cantidadLote)){
      $mensaje='Todos los campos son obligatorios';      
    }else{
      (list($dia,$mes,$ano)=explode('/',$fechaVencimiento));      
      if((mktime(0,0,0,$mes,$dia,$ano)<mktime(0,0,0,date('m'),date('d'),date('Y')))){
        $mensaje='La Fecha de Vencimiento debe ser mayor que la fecha actual';
      }else{      
        foreach($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto] as $loteT=>$arreglo){
            (list($cantidadLoteT,$fechaVencimientoT)=explode('||//',$arreglo));
            $sumacantidadLoteT+=$cantidadLoteT;
        }
        if(($sumacantidadLoteT+$cantidadLote) > $cantidad){          
          $mensaje='la suma de las cantidades de los lotes no puede ser mayor a la cantidad total a devolver';          
        }elseif(($sumacantidadLoteT+$cantidadLote) < $cantidad){
          $mensaje='Recuerde: la suma de las cantidades de los lotes debe ser igual a la cantidad a devolver';
          $inserta=1;    
        }else{
          $inserta=1; 
          $igual=1; 
        }
      }      
    }
    if($inserta==1){   
      $_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto][$lote]=$cantidadLote.'||//'.$fechaVencimiento;    
      $fechaVencimiento='';$lote='';$cantidadLote='';
    }
    $ventana=CrearVentanaFechasVencimientos($valor,$cantidad,$mensaje,$fechaVencimiento,$lote,$cantidadLote);   
    $objResponse->assign("d2Contents","innerHTML",$ventana);
    $objResponse->call('Iniciar');
    $objResponse->call('MostrarVentana');    
    if($igual==1){
      $objResponse->call('Cerrar');
      $fechasVence=MostrarFechasVencimiento($codigo_producto,$valor,$cantidad);
      $objResponse->assign("MostrarFechas".$codigo_producto,"innerHTML",$fechasVence);
    }
    return $objResponse; 
    
  }
  
  function EliminaFechaVence($codigo_producto,$loteT,$valor,$cantidad){
    (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite)=explode('||//',$valor));
    unset($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto][$loteT]);
    $objResponse = new xajaxResponse();     
    $ventana=CrearVentanaFechasVencimientos($valor,$cantidad);   
    $objResponse->assign("d2Contents","innerHTML",$ventana);
    $objResponse->call('Iniciar');
    $objResponse->call('MostrarVentana');    
    return $objResponse;     
  }
  
  function EliminaFechaVenceForma($codigo_producto,$loteT,$valor,$cantidad){
    (list($none,$empresa,$centro_utilidad,$bodega,$codigo_producto,$nom_bodega,$cantidadLimite)=explode('||//',$valor));
    unset($_SESSION['FECHAS_VENCIMIENTO']['DEVOLUCION_CUENTAS'][$empresa."||//".$centro_utilidad."||//".$bodega][$codigo_producto][$loteT]);
    $objResponse = new xajaxResponse();     
    $fechasVence=MostrarFechasVencimiento($codigo_producto,$valor,$cantidad);
    $objResponse->assign("MostrarFechas".$codigo_producto,"innerHTML",$fechasVence);   
    return $objResponse;     
  }
  
  
?>  