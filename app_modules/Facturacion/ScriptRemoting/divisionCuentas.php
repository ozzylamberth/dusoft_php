<?php
  $VISTA = "HTML";
  $_ROOT="../../../";  
  include  "../../../includes/enviroment.inc.php";  
  include  "../../../classes/rs_server/rs_server.class.php";
  
  
  
  class procesos_admin extends rs_server
  {     
          
          function InsertarDatosDivisionCuentaCargos($arreglo){
              (list($transaccion,$cargo_cups,$codigo_agrupamiento_id,$consecutivo)=explode('||//',$arreglo[0]));  
              $plan=$arreglo[1];
              $Cuenta=$arreglo[2];
              $pagina=$arreglo[3];
              
              
              if(empty($codigo_agrupamiento_id) AND empty($consecutivo)){                
                $equi='';
                $equi=ValdiarEquivalencias($plan,$cargo_cups);
                if(empty($equi))
                {
                        $cadena .= "   <br><table border=\"0\" width=\"90%\" align=\"center\">";
                        $cadena .= "   <tr><td align=\"center\" width=\"100%\" class=\"label_error\">EL CARGO NO TIENE EQUIVALENCIAS O LAS EQUIVALENCIAS NO ESTAN CONTRATADAS</td></tr>";                        
                        $cadena .= "   </table>";
                        return $cadena;
                }
              }
                
              $query = "update tmp_division_cuenta set cuenta=1, plan_id=".$plan."
              where transaccion=$transaccion";
              list($dbconn) = GetDBconn();
              $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error update tmp_division_cuenta";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }
              
              $cadena=$this->FormaRegistrosNuevaCuenta($Cuenta,$plan,$pagina);              
              return $cadena;
                        
          }
          
          function InsertarDatosDivisionCuentaCargosInicial($arreglo){
            (list($transaccion,$codigo_agrupamiento_id,$consecutivo)=explode('||//',$arreglo[0]));  
            $plan=$arreglo[1];
            $Cuenta=$arreglo[2];
            $pagina=$arreglo[3];
            $query = "update tmp_division_cuenta set cuenta=0, plan_id=NULL
                            where transaccion=$transaccion";                            
            list($dbconn) = GetDBconn();                
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error update tmp_division_cuenta";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
            }
            $cadena=$this->FormaRegistrosNuevaCuenta($Cuenta,$plan,$pagina);              
            return $cadena;
          }
          
          function InsertarDatosDivisionCuentaAbonos($arreglo){
              (list($prefijo,$recibo_caja,$fecha_ingcaja,$total_efectivo,$total_cheques,$total_tarjetas,$total_bonos,$total_abono)=explode('||//',$arreglo[0]));  
              $plan=$arreglo[1];
              $Cuenta=$arreglo[2];
              $pagina=$arreglo[3];
              $query = "INSERT INTO tmp_division_cuenta_abonos(plan_id,
                                                                                            numerodecuenta,
                                                                                            recibo_caja,
                                                                                            prefijo,
                                                                                            fecha_ingcaja,
                                                                                            total_abono,
                                                                                            total_efectivo,
                                                                                            total_cheques,
                                                                                            total_tarjetas,
                                                                                            total_bonos)
                                  VALUES(".$plan.",".$Cuenta.",".$recibo_caja.",'".$prefijo."','".$fecha_ingcaja."',".$total_abono.",
                                  ".$total_efectivo.",".$total_cheques.",".$total_tarjetas.",".$total_bonos.")";
              list($dbconn) = GetDBconn();                    
              $dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                      $this->error = "Error update tmp_division_cuenta";
                      $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                      return false;
              }              
              $cadena=$this->FormaRegistrosNuevaCuenta($Cuenta,$plan,$pagina);              
              return $cadena;              
              
          
          }
          
          function InsertarDatosDivisionCuentaAbonosInicial($arreglo){
            (list($prefijo,$recibo_caja,$plan_id,$fecha_ingcaja,$total_efectivo,$total_cheques,$total_tarjetas,$total_bonos,$total_abono)=explode('||//',$arreglo[0]));  
            $plan=$arreglo[1];
            $Cuenta=$arreglo[2];
            $pagina=$arreglo[3];
            $query = "DELETE FROM tmp_division_cuenta_abonos WHERE recibo_caja=".$recibo_caja."
                      AND prefijo='".$prefijo."' and plan_id=".$plan_id."";
            list($dbconn) = GetDBconn();                    
            $dbconn->Execute($query);
            if ($dbconn->ErrorNo() != 0) {
                    $this->error = "Error update tmp_division_cuenta";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
            }
            $cadena=$this->FormaRegistrosNuevaCuenta($Cuenta,$plan,$pagina);              
            return $cadena;
          }
          
          function ActualizarBarraNavegador($arreglo){
            $Cuenta=$arreglo[0];
            $plan=$arreglo[1];
            $pagina=$arreglo[2];
            $cadena=$this->FormaRegistrosNuevaCuenta($Cuenta,$plan,$pagina);  
            return $cadena;
          }
          
          function FormaRegistrosNuevaCuenta($Cuenta,$plan,$pagina){
              IncludeLib("funciones_facturacion");
              $cadena .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
              $cadena .= "   <br><table border=\"0\" width=\"75%\" align=\"center\" class=\"modulo_table_list\">";
              $cadena .= "          <tr class=\"modulo_table_list_title\">";
              $cadena .= "            <td align=\"center\" colspan=\"8\">ABONOS DE LA CUENTA ACTUAL</td>";
              $cadena .= "          </tr>";
              unset($_SESSION['CUENTA']['ABONOS']['ACTUAL']);
              //if(!empty($abono[abonos]))
              $abono=PagosCuentaDivision($Cuenta);
              if(!empty($abono))
              {
                  $cadena .= "<tr class=\"modulo_table_list_title \">";
                  $cadena .= "  <td width=\"12%\">RECIBO CAJA</td>";
                  $cadena .= "  <td width=\"15%\">FECHA</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL BONOS</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL</td>";
                  $cadena .= "  <td width=\"4%\"></td>";
                  $cadena .= "</tr>";
                  $total=0;
                  for($j=0; $j<sizeof($abono); $j++)
                  {
                    if(empty($_SESSION['CUENTA']['ABONOS'][$abono[$j][prefijo].$abono[$j][recibo_caja]]))
                    {
                        $rcaja=$abono[$j][prefijo].$abono[$j][recibo_caja];
                        $fech=$abono[$j][fecha_ingcaja];
                        $Te=FormatoValor($abono[$j][total_efectivo]);
                        $Tc=FormatoValor($abono[$j][total_cheques]);
                        $Tt=FormatoValor($abono[$j][total_tarjetas]);
                        $Tb=FormatoValor($abono[$j][total_bonos]);
                        $TOTAL=FormatoValor($abono[$j][total_abono]);
                        if( $j % 2){ $estilo='modulo_list_claro';}
                        else {$estilo='modulo_list_oscuro';}
                        $cadena .= "<tr class=\"$estilo\" align=\"center\">";
                        $cadena .= "  <td>$rcaja</td>";
                        $cadena .= "  <td>$fech</td>";
                        $cadena .= "  <td>$Te</td>";
                        $cadena .= "  <td>$Tc</td>";
                        $cadena .= "  <td>$Tt</td>";
                        $cadena .= "  <td>$Tb</td>";
                        $cadena .= "  <td class=\"label_error\">$TOTAL</td>";
                        //$this->salida .= "  <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$abono[$j][prefijo].",".$abono[$j][recibo_caja].",".$abono[$j][fecha_ingcaja].",".$abono[$j][total_efectivo].",".$abono[$j][total_cheques].",".$abono[$j][total_tarjetas].",".$abono[$j][total_bonos].",".$abono[$j][total_abono]."\" name=\"actual".$abono[$j][prefijo]."".$abono[$j][recibo_caja]."\"></td>";
                        $valor=$abono[$j][prefijo]."||//".$abono[$j][recibo_caja]."||//".$abono[$j][fecha_ingcaja]."||//".$abono[$j][total_efectivo]."||//".$abono[$j][total_cheques]."||//".$abono[$j][total_tarjetas]."||//".$abono[$j][total_bonos]."||//".$abono[$j][total_abono];
                        $cadena .= "            <td align=\"center\"><a href=\"javascript:AbonoOtraCuenta(document.forma,'$valor','$Cuenta','$pagina');\"><img border=\"0\" src=\"".SessionGetVar("RutaImagen")."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";
                        $cadena .= "</tr>";
                        $total+=$abono[$j][total_abono];
                    }
                  }
                  $cadena .= "          <tr class=\"modulo_list_claro\">";
                  $cadena .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
                  $cadena .= "            <td align=\"center\" width=\"4%\"></td>";
                  $cadena .= "          </tr>";             
              }
              $cadena .= "     </table>";
              $det=$this->DetalleTotal($Cuenta,$pagina);
              $cadena .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
              $cadena .= $this->SetStyle("MensajeError");
              $cadena .= "     </table>";
              $cadena .= "   <br>  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
              $cadena .= "          <tr class=\"modulo_table_list_title\">";
              $cadena .= "            <td align=\"center\" colspan=\"12\">CARGOS DE LA CUENTA ACTUAL</td>";
              $cadena .= "          </tr>";
              $cadena .= "          <tr class=\"modulo_table_list_title\">";
              $cadena .= "            <td width=\"7%\">TARIFARIO</td>";
              $cadena .= "            <td width=\"5%\">CARGO</td>";
              $cadena .= "            <td width=\"10%\">CODIGO</td>";
              $cadena .= "            <td>DESCRIPCION</td>";
              $cadena .= "            <td width=\"8%\">FECHA CARGO</td>";
              $cadena .= "            <td width=\"5%\">HORA</td>";
              $cadena .= "            <td width=\"7%\">CANT</td>";
              $cadena .= "            <td width=\"8%\">VALOR CARGO</td>";
              $cadena .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
              $cadena .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
              $cadena .= "            <td width=\"10%\">DPTO.</td>";              
              $cadena .= "            <td width=\"3%\"></td>";          
              $cadena .= "          </tr>";
              $car=$cubi=$nocub=0;
              if(!empty($det))
              {
                  for($i=0; $i<sizeof($det);)
                  {
                      if($i % 2) {  $estilo="modulo_list_claro";  }
                      else {  $estilo="modulo_list_oscuro";   }                 
                      $cadena .= "          <tr class=\"$estilo\">";
                      //este codigo se comento para poder pasar los medicamentos de una cuenta a otra                   
                      /*if(!empty($det[$i][codigo_agrupamiento_id]) AND !empty($det[$i][consecutivo]))
                      {
                            $d=$i;
                            $Cantidad=$valor=$cub=$nocub=0;
                            while($det[$i][codigo_agrupamiento_id]==$det[$d][codigo_agrupamiento_id])   
                            {
                              $Cantidad+=$det[$d][cantidad];
                              $valor+=$det[$d][fac];          
                              $cub+=$det[$d][valor_cubierto]; 
                              $nocub+=$det[$d][valor_nocubierto];     
                              //suma los totales del final
                              $car+=$det[$d][valor_cargo];
                              $cubi+=$det[$d][valor_cubierto];
                              $nocub+=$det[$d][valor_nocubierto];                                                                                       
                              $d++;
                            } 
                            $des=$this->NombreCodigoAgrupamiento($det[$i][codigo_agrupamiento_id]);
                            $this->salida .= "            <td align=\"center\">".$det[$i][tarifario_id]."</td>";
                            $this->salida .= "            <td align=\"center\">".$det[$i][cargo]."</td>";
                            $this->salida .= "            <td>".$des[descripcion]."</td>";
                            $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($Cantidad)."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($valor)."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($nocub)."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($cub)."</td>";
                            $i=$d;              
                      }//fin if   
                      else
                      { */  
                      //fin codigo comentado
                      
                          //suma los totales del final          
                          $car+=$det[$i][valor_cargo];
                          $cubi+=$det[$i][valor_cubierto];
                          $nocub+=$det[$i][valor_nocubierto];                 
                          $cadena .= "            <td align=\"center\">".$det[$i][tarifario_id]."</td>";
                          $cadena .= "            <td align=\"center\">".$det[$i][cargo]."</td>";
                          $cadena .= "            <td align=\"center\">".$det[$i][codigo_producto]."</td>";
                          $cadena .= "            <td>".$det[$i][descripcion]."</td>";
                          $cadena .= "            <td align=\"center\">".$this->FechaStamp($det[$i][fecha_cargo])."</td>";
                          $cadena .= "            <td align=\"center\">".$this->HoraStamp($det[$i][fecha_cargo])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$i][cantidad])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$i][valor_cargo])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$i][valor_nocubierto])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$i][valor_cubierto])."</td>";
                          $cadena .= "            <td align=\"center\">".$det[$i][departamento]."</td>";                          
                          $i++;
                      //}
                      $f=0;
                      for($d=0; $d<sizeof($vars); $d++)
                      {
                          if($vars[$d][transaccion]==$det[$i-1][transaccion])
                          {
                              //$this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$i-1][transaccion].",".$det[$i-1][codigo_agrupamiento_id].",".$det[$i-1][consecutivo].",".$det[$i-1][cargo_cups]."\" name=\"New".$det[$i-1][codigo_agrupamiento_id].$det[$i-1][codigo_agrupamiento_id]."\" checked></td>";                          
                              $d=sizeof($vars);
                              $f=1;
                          }
                      }
                      if($f==0)
                      {
                          //$this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$i-1][transaccion].",".$det[$i-1][codigo_agrupamiento_id].",".$det[$i-1][consecutivo].",".$det[$i-1][cargo_cups]."\" name=\"New".$det[$i-1][transaccion].$det[$i-1][codigo_agrupamiento_id]."\"></td>";                      
                      }
                      $valor=$det[$i-1][transaccion].'||//'.$det[$i-1][cargo_cups].'||//'.$det[$i-1][codigo_agrupamiento_id].'||//'.$det[$i-1][consecutivo];
                      $cadena .= "            <td align=\"center\"><a href=\"javascript:CargoOtraCuenta(document.forma,'$valor','$Cuenta','$pagina');\"><img border=\"0\" src=\"".SessionGetVar("RutaImagen")."/images/abajo.png\" title=\"Cargar a Otra Cuenta\"></a></td>";
                      $cadena .= "          </tr>";
                  }
                  if($i % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $cadena .= "          <tr class=\"$estilo\">";
                  $cadena .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
                  //$this->salida .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma);\"><img border=\"0\" src=\"".GetThemePath()."/images/abajo.png\"></a></td>";
                  $cadena .= "            <td align=\"center\" colspan=\"2\">&nbsp;</td>";
                  $cadena .= "          </tr>";
                  $cadena .= "          <tr class=\"modulo_list_claro\">";  
                  $cadena .= "              <td colspan=\"11\" align=\"right\" class=\"label\">PLAN: &nbsp;<select name=\"plan\" class=\"select\">";
                  $cons = $this->Planes($_SESSION['CUENTA']['DIVISION_CUENTA']['PLAN']);
                  $cadena .=" <option value=\"-1\">---Seleccione---</option>";
                  for($k=0; $k<sizeof($cons); $k++)
                  {$s='';if($cons[$k][plan_id]==$plan){$s='selected';}
                      $cadena .=" <option value=\"".$cons[$k][plan_id]."\" $s>".$cons[$k][plan_descripcion]."</option>"; 
                  }
                  $cadena .= "          </select></td>";            
                  $cadena .= "              <td></td>";           
                  $cadena .= "          </tr>";
              }
              $cadena .= "     </table>";
              $action="new Array('$Cuenta','$plan'";
              $cadena .= $this->ObtenerPaginado($pagina,$action,SessionGetVar("RutaImagen"),1);     
                                 
              $cadena .= "<DIV align=\"center\" class=\"label_mark\">".$this->NombrePlan($plan)."</DIV>";
              $abono='';                
              $abono = $this->DivisionAbonosCuenta($Cuenta,$plan);                
              if($abono)
              {   
                  $cadena .= "   <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
                  $cadena .= "          <tr class=\"modulo_table_list_title\">";
                  $cadena .= "            <td align=\"center\" colspan=\"8\">ABONOS DE LA CUENTA NUEVA PLAN ".$this->NombrePlan($plan)."</td>";
                  $cadena .= "          </tr>";
                  $cadena .= "<tr class=\"modulo_table_list_title \">";
                  $cadena .= "  <td width=\"12%\">RECIBO CAJA</td>";
                  $cadena .= "  <td width=\"15%\">FECHA</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL EFECTIVO</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL CHEQUES</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL TARJETAS</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL BONOS</td>";
                  $cadena .= "  <td width=\"15%\">TOTAL</td>";
                  $cadena .= "  <td width=\"4%\"></td>";
                  $cadena .= "</tr>";                     
                  $total=0;
                  for($k=0; $k<sizeof($abono); $k++)
                  {
                      $total+=$abono[$k]['total_abono'];
                      if( $j % 2){ $estilo='modulo_list_claro';}
                      else {$estilo='modulo_list_oscuro';}
                      $cadena .= "<tr class=\"$estilo\" align=\"center\">";
                      $cadena .= "  <td>".$abono[$k]['prefijo']."".$abono[$k]['recibo_caja']."</td>";
                      $cadena .= "  <td>".$abono[$k]['fecha_ingcaja']."</td>";
                      $cadena .= "  <td>".FormatoValor($abono[$k]['total_efectivo'])."</td>";
                      $cadena .= "  <td>".FormatoValor($abono[$k]['total_cheques'])."</td>";
                      $cadena .= "  <td>".FormatoValor($abono[$k]['total_tarjetas'])."</td>";
                      $cadena .= "  <td>".FormatoValor($abono[$k]['total_bonos'])."</td>";
                      $cadena .= "  <td class=\"label_error\">".FormatoValor($abono[$k]['total_abono'])."</td>";
                      //$cadena .= "  <td align=\"center\" width=\"4%\"><input type=\"checkbox\" value=\"".$abono[$k]['prefijo'].",".$abono[$k]['recibo_caja'].",".$abono[$k]['plan_id'].",".$abono[$k]['fecha_ingcaja'].",".$abono[$k]['total_efectivo'].",".$abono[$k]['total_cheques'].",".$abono[$k]['total_tarjetas'].",".$abono[$k]['total_bonos'].",".$abono[$k]['total_abono']."\" name=\"nuevo".$abono[$k]['prefijo']."".$abono[$k]['recibo_caja']."\"></td>";
                      $valor=$abono[$k]['prefijo']."||//".$abono[$k]['recibo_caja']."||//".$abono[$k]['plan_id']."||//".$abono[$k]['fecha_ingcaja']."||//".$abono[$k]['total_efectivo']."||//".$abono[$k]['total_cheques']."||//".$abono[$k]['total_tarjetas']."||//".$abono[$k]['total_bonos']."||//".$abono[$k]['total_abono'];
                      $cadena .= "            <td align=\"center\"><a href=\"javascript:AbonoCuentaInicial('$valor','".$plan."','$Cuenta','$pagina');\"><img border=\"0\" src=\"".SessionGetVar("RutaImagen")."/images/arriba.png\" title=\"Regresar a la Cuenta Inicial\"></a></td>";                          
                      $cadena .= "</tr>";
                      $total+=$v[total];              
                  }
                  $cadena .= "          <tr class=\"modulo_list_claro\">";
                  $cadena .= "            <td align=\"right\" class=\"label\" colspan=\"6\">TOTAL ABONOS:  </td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($total)."</td>";
                  $cadena .= "            <td align=\"center\" width=\"4%\"></td>";
                  $cadena .= "          </tr>";
                  $cadena .= "     </table>";                               
              }//fin del abono
              unset($vecplan);                            
              $new=$this->DetalleNuevo($Cuenta);
              $det=$new;              
              for($j=0; $j<sizeof($det);)
              {   $vecplan[]=$det[$j]['plan_id'];
                  $cadena .= "   <br>  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
                  $cadena .= "          <tr class=\"modulo_table_list_title\">";
                  $cadena .= "            <td align=\"center\" colspan=\"12\">CARGOS DE LA NUEVA CUENTA PLAN ".$this->NombrePlan($plan)."</td>";
                  $cadena .= "          </tr>";
                  $cadena .= "          <tr class=\"modulo_table_list_title\">";
                  $cadena .= "            <td width=\"7%\">TARIFARIO</td>";
                  $cadena .= "            <td width=\"5%\">CARGO</td>";
                  $cadena .= "            <td width=\"10%\">CODIGO</td>";
                  $cadena .= "            <td>DESCRIPCION</td>";
                  $cadena .= "            <td width=\"8%\">FECHA CARGO</td>";
                  $cadena .= "            <td width=\"5%\">HORA</td>";
                  $cadena .= "            <td width=\"7%\">CANT</td>";
                  $cadena .= "            <td width=\"8%\">VALOR CARGO</td>";
                  $cadena .= "            <td width=\"8%\">VAL. NO CUBIERTO</td>";
                  $cadena .= "            <td width=\"8%\">VAL. CUBIERTO</td>";
                  $cadena .= "            <td width=\"10%\">DPTO.</td>";                  
                  $cadena .= "            <td width=\"3%\"></td>";
                  $cadena .= "          </tr>";             
                  $d=$j;
                  $car=$cubi=$nocub=0;
                  while($det[$j]['plan_id']==$det[$d]['plan_id'])
                  {
                      if($d % 2) {  $estilo="modulo_list_claro";  }
                      else {  $estilo="modulo_list_oscuro";   }
                      $cadena .= "          <tr class=\"$estilo\">";
                      //este codigo se comento para poder pasar los medicamentos de una cuenta a otra                   
                      /*if(!empty($det[$d][codigo_agrupamiento_id]) AND !empty($det[$d][consecutivo]))
                      {
                            $m=$d;
                            $Cantidad=$valor=$cub=$nocub=0;
                            while($det[$d][codigo_agrupamiento_id]==$det[$m][codigo_agrupamiento_id])   
                            {
                              $Cantidad+=$det[$m][cantidad];
                              $valor+=$det[$m][fac];          
                              $cub+=$det[$m][valor_cubierto]; 
                              $nocub+=$det[$m][valor_nocubierto];
                              //suma los totales del final
                              $car+=$det[$d][valor_cargo];
                              $cubi+=$det[$d][valor_cubierto];
                              $nocub+=$det[$d][valor_nocubierto];                                                                                               
                              $m++;
                            } 
                            $des=$this->NombreCodigoAgrupamiento($det[$d][codigo_agrupamiento_id]);
                            $this->salida .= "            <td align=\"center\">".$det[$d][tarifario_id]."</td>";
                            $this->salida .= "            <td align=\"center\">".$det[$d][cargo]."</td>";                        
                            $this->salida .= "            <td align=\"center\">".$det[$d][codigo_producto]."</td>";                        
                            $this->salida .= "            <td>".$des[descripcion]."</td>";
                            $this->salida .= "            <td align=\"center\">".$this->FechaStamp($det[$d][fecha_cargo])."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($Cantidad)."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($valor)."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($nocub)."</td>";
                            $this->salida .= "            <td align=\"center\">".FormatoValor($cub)."</td>";
                            $this->salida .= "            <td><input type=\"checkbox\" value=\"".$det[$d][transaccion].",".$det[$d][codigo_agrupamiento_id].",".$det[$d][consecutivo]."\" name=\"Go".$det[$d][transaccion].$det[$d][codigo_agrupamiento_id]."\"></td>";
                            $d=$m;              
                      }//fin if   
                      else
                      {*/
                      //fin codigo comentado          
                          //suma los totales del final
                          $car+=$det[$d][valor_cargo];
                          $cubi+=$det[$d][valor_cubierto];
                          $nocub+=$det[$d][valor_nocubierto];                           
                          $cadena .= "            <td align=\"center\">".$det[$d][tarifario_id]."</td>";
                          $cadena .= "            <td align=\"center\">".$det[$d][cargo]."</td>";
                          $cadena .= "            <td align=\"center\">".$det[$d][codigo_producto]."</td>";
                          $cadena .= "            <td>".$det[$d][descripcion]."</td>";
                          $cadena .= "            <td align=\"center\">".$this->FechaStamp($det[$d][fecha_cargo])."</td>";
                          $cadena .= "            <td align=\"center\">".$this->HoraStamp($det[$d][fecha_cargo])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$d][cantidad])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$d][valor_cargo])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$d][valor_nocubierto])."</td>";
                          $cadena .= "            <td align=\"center\">".FormatoValor($det[$d][valor_cubierto])."</td>";
                          $cadena .= "            <td align=\"center\">".$det[$d][departamento]."</td>";                          
                          //$cadena .= "            <td><input type=\"checkbox\" value=\"".$det[$d][transaccion].",".$det[$d][codigo_agrupamiento_id].",".$det[$d][consecutivo]."\" name=\"Go".$det[$d][transaccion].$det[$d][codigo_agrupamiento_id]."\"></td>";
                          $valor=$det[$d][transaccion]."||//".$det[$d][codigo_agrupamiento_id]."||//".$det[$d][consecutivo];
                          $cadena .= "            <td align=\"center\"><a href=\"javascript:CargoCuentaInicial('$valor','".$det[$j]['plan_id']."','$Cuenta','$pagina');\"><img border=\"0\" src=\"".SessionGetVar("RutaImagen")."/images/arriba.png\" title=\"Regresar a la Cuenta Inicial\"></a></td>";                          
                          $d++;
                      //}  
                      $cadena .= "          </tr>";
                  }
                  if($i % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $cadena .= "          <tr class=\"$estilo\">";
                  $cadena .= "            <td colspan=\"7\" class=\"label\"  align=\"right\">TOTALES:  </td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($car)."</td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($nocub)."</td>";
                  $cadena .= "            <td align=\"center\" class=\"label\">".FormatoValor($cubi)."</td>";
                  //$cadena .= "            <td align=\"center\"><a href=\"javascript:Bajar(document.forma2);\"><img border=\"0\" src=\"".GetThemePath()."/images/arriba.png\"></a></td>";
                  $cadena .= "            <td align=\"center\" colspan=\"2\">&nbsp;</td>";
                  $cadena .= "          </tr>";   
                  $cadena .= "     </table><br><br>";     
                  $j=$d;
              }
              $cadena .= "    </form>";
              $cadena .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
              $cadena .= "     <tr><td align=\"center\">";                          
              if(is_array($abono)||is_array($det)){                
                $accionEstado=$_SESSION['CUENTA']['DIVISION_CUENTA']['ACCION_FINALIZAR'];                    
                $cadena .= "    <form name=\"formabuscar\" action=\"$accionEstado\" method=\"post\">";
                $cadena .= "      <input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"TERMINAR DIVISION\">";
                $cadena .= "    </form>";
              }
              $cadena .= "     </td></tr></table><BR>";
              return $cadena; 
          
          
          }
          
          
          
          /**
          *
          */
          function DetalleNuevo($Cuenta)
          {
              list($dbconn) = GetDBconn();
              
              $query = "select a.*,d.codigo_producto, 
                        (CASE WHEN d.consecutivo IS NOT NULL THEN e.descripcion ELSE b.descripcion END) as descripcion,
                        c.plan_descripcion,
                                        case a.facturado when 1 then a.valor_cargo else 0 end as fac,
                                        dpto.descripcion as departamento,date(a.fecha_cargo) as fecha
                                        from tmp_division_cuenta as a
                                        LEFT JOIN cuentas_codigos_agrupamiento f ON (a.codigo_agrupamiento_id=f.codigo_agrupamiento_id)
                                        LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND f.bodegas_doc_id=d.bodegas_doc_id AND f.numeracion=d.numeracion)
                                        LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                                        LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                                        , tarifarios_detalle as b, planes as c
                        where a.numerodecuenta=$Cuenta and a.cuenta=1 and
                          a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                        and a.plan_id=c.plan_id
                                        order by a.plan_id,a.codigo_agrupamiento_id";
              $results = $dbconn->Execute($query);
              while (!$results->EOF) {
                  $var[]=$results->GetRowAssoc($ToUpper = false);
                  $results->MoveNext();
              }
              $results->Close();
              return $var;
          } 
          
          function DivisionAbonosCuenta($Cuenta,$Plan)
          {
                  if(!empty($Cuenta))
                  {   $x = "numerodecuenta=$Cuenta and "; }
      
            list($dbconn) = GetDBconn();
              $query = "select * from tmp_division_cuenta_abonos where $x plan_id=$Plan";
            $results = $dbconn->Execute($query);
            while (!$results->EOF) {
                $var[]=$results->GetRowAssoc($ToUpper = false);
                $results->MoveNext();
            }
            $results->Close();
            return $var;
          }   
          
          /**
          * Se encarga de separar la fecha del formato timestamp
          * @access private
          * @return string
          * @param date fecha
          */
        function FechaStamp($fecha)
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
        
        /**
        * Se encarga de separar la hora del formato timestamp
        * @access private
        * @return string
        * @param date hora
        */
        function HoraStamp($hora)
        {
          $hor = strtok ($hora," ");
          for($l=0;$l<4;$l++)
          {
            $time[$l]=$hor;
            $hor = strtok (":");
          }
              $x=explode('.',$time[3]);
          return  $time[1].":".$time[2];
        } 
        
        function NombrePlan($plan)
        {
              list($dbconn) = GetDBconn();
              $query="SELECT plan_descripcion FROM planes  WHERE plan_id=$plan";
              $result=$dbconn->Execute($query);
              if ($dbconn->ErrorNo() != 0) {
                $this->error = "Error al Cargar el Modulo";
                $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                return false;
              }
              return $result->fields[0];
        } 
        
        function Planes($Plan)
        {
                    list($dbconn) = GetDBconn();
                    $query="SELECT plan_id,plan_descripcion,tercero_id,tipo_tercero_id FROM planes
                                    WHERE fecha_final >= now() and estado=1 and fecha_inicio <= now()
                                    and plan_id not in($Plan) order by plan_descripcion";
                    $result = $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0) {
                            $this->error = "Error al Cargar el Modulo";
                            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                            return false;
                    }
    
                    while (!$result->EOF) {
                                    $var[]=$result->GetRowAssoc($ToUpper = false);
                                    $result->MoveNext();
                    }
                    $result->Close();
                    return $var;
        }
        
        function DetalleTotal($Cuenta,$pagina)
        {
            list($dbconn) = GetDBconn();
            $of=(GetLimitBrowser() * ($pagina-1)); 
            $query = "select count(*)
                                      from tmp_division_cuenta as a
                                      LEFT JOIN cuentas_codigos_agrupamiento c ON (a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
                                      LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND c.bodegas_doc_id=d.bodegas_doc_id AND c.numeracion=d.numeracion)
                                      LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                                      ,tarifarios_detalle as b
                      where a.numerodecuenta=$Cuenta and a.cuenta=0 and
                        a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                      ";
            $results = $dbconn->Execute($query); 
            $this->conteo=$results->fields[0];
            
            $query = "select a.*,d.codigo_producto, 
                      (CASE WHEN d.consecutivo IS NOT NULL THEN e.descripcion ELSE b.descripcion END) as descripcion,
                      case a.facturado when 1 then a.valor_cargo else 0 end as fac,dpto.descripcion as departamento,date(a.fecha_cargo) as fecha
                                      from tmp_division_cuenta as a
                                      LEFT JOIN cuentas_codigos_agrupamiento c ON (a.codigo_agrupamiento_id=c.codigo_agrupamiento_id)
                                      LEFT JOIN bodegas_documentos_d d ON (a.consecutivo=d.consecutivo AND c.bodegas_doc_id=d.bodegas_doc_id AND c.numeracion=d.numeracion)
                                      LEFT JOIN inventarios_productos e ON (e.codigo_producto=d.codigo_producto)
                                      LEFT JOIN departamentos dpto ON (a.departamento=dpto.departamento)
                                      ,tarifarios_detalle as b
                      where a.numerodecuenta=$Cuenta and a.cuenta=0 and
                        a.cargo=b.cargo and a.tarifario_id=b.tarifario_id
                                      order by a.codigo_agrupamiento_id
                                      limit ".GetLimitBrowser()." offset ".$of."";
            $results = $dbconn->Execute($query);
            while (!$results->EOF) {
                $var[]=$results->GetRowAssoc($ToUpper = false);
                $results->MoveNext();
            }
            $results->Close();
            return $var;
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
        
        function ObtenerPaginado($pagina,$action,$path,$op)
        {
          $TotalRegistros = $this->conteo;
          $TablaPaginado = "";
            
          if($limite == null)
          {
            $uid = UserGetUID();
            $LimitRow = intval(GetLimitBrowser());
          }
          else
          {
            $LimitRow = $limite;
          }
          if ($TotalRegistros > 0)
          {
            $columnas = 1;
            $NumeroPaginas = intval($TotalRegistros/$LimitRow);
            if($TotalRegistros%$LimitRow > 0)
            {
              $NumeroPaginas++;
            }
                
            $Inicio = $pagina;
            if($NumeroPaginas - $pagina < 9 )
            {
              $Inicio = $NumeroPaginas - 9;
            }
            else if($pagina > 1)
            {
              $Inicio = $pagina - 1;
            }
            
            if($Inicio <= 0)
            {
              $Inicio = 1;
            }
              
            $estilo = " style=\"font-family: Lucida Sans Unicode,sans_serif, Verdana, helvetica, Arial; font-size:15px;\" "; 
    
            $TablaPaginado .= "<tr>\n";
            if($NumeroPaginas > 1)
            {
              $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
              if($pagina > 1)
              {
                $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a href=\"javascript:CrearVariables(".$action.",'1'))\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                $TablaPaginado .= "     <a href=\"javascript:CrearVariables(".$action.",'".($pagina-1)."'))\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                $TablaPaginado .= "   </td>\n";
                $columnas +=2;
              }
              $Fin = $NumeroPaginas + 1;
              if($NumeroPaginas > 10)
              {
                $Fin = 10 + $Inicio;
              }
                
              for($i=$Inicio; $i< $Fin ; $i++)
              {
                if ($i == $pagina )
                {
                  $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
                }
                else
                {
                  $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:CrearVariables(".$action.",'".$i."'))\">".$i."</a></td>\n";
                }
                $columnas++;
              }
            }
            if($pagina <  $NumeroPaginas )
            {
              $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
              $TablaPaginado .= "     <a href=\"javascript:CrearVariables(".$action.",'".($pagina+1)."'))\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
              $TablaPaginado .= "   </td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
              $TablaPaginado .= "     <a href=\"javascript:CrearVariables(".$action.",'".$NumeroPaginas."'))\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
              $TablaPaginado .= "   </td>\n";
              $columnas +=2;
            }
            $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
            $aviso .= "     Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
            $aviso .= "   </tr>\n";
            
            if($op == 2)
            {
              $TablaPaginado .= $aviso;
            }
            else
            {
              $TablaPaginado = $aviso.$TablaPaginado;
            }
          }
          
          $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
          $Tabla .= $TablaPaginado;
          $Tabla .= "</table><br>";
    
          return $Tabla;
        }   
          
  }     
  
  $oRS = new procesos_admin( array( 'Tipo_Afiliado','Niveles'));

  // el metodo action es el que recoge los datos (POST) y actua en consideración ;-)
  $oRS->action();

?>