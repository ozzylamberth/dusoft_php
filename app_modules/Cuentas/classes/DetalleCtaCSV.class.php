<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: RiesgosCaidaSQL.class.php,v 1.1 2011/03/25 14:23:57 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase: DetalleCtaCSV
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */  
	class DetalleCtaCSV extends ConexionBD
	{
    /**
    * Constructor de la clase
    */
		function DetalleCtaCSV(){}
		/**
		* Funcion donde se buscan crea la forma de los paquetes
		* 
		* @return array 
		*/
		function CrearFormaDetalleCta($Cuenta,$tipo_id_paciente,$paciente_id,$plan_id,$ingreso,$empresa_id)
    {   
      
      $funciones = new DetalleCta; 
      $vector=$funciones->BuscarDetalleCuenta($Cuenta);      
      if($vector){
        $html .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\" align=\"center\"  class=\"modulo_table_list\">";
        $html .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
        $html .= "        <td width=\"2%\">&nbsp;</td>";
        $html .= "        <td>FECHA</td>";
        $html .= "        <td width=\"46%\">CARGO</td>";
        $html .= "        <td width=\"8%\">PRECIO UNI.</td>";
        $html .= "        <td>CANT.</td>";
        $html .= "        <td width=\"8%\">VALOR</td>";
        $html .= "        <td width=\"11%\">VAL. NO CUBIERTO</td>";
        $html .= "        <td width=\"5%\">FIRMA</td>";        
        if($Cerradas!='Cerradas'){
          $html .= "      <td colspan=\"2\">ACCION</td>"; 
        }
        $html .= "        <td>INT</td>";
        $html .= "        <td>EXT</td>";
        $html .= "        <td>&nbsp;</td>";
        $html .= "    </tr>";
        for($i=0;$i<sizeof($vector);$i++){
          if($i % 2){
            $estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';
          }else{
            $estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';
          }
          $html .= "    <tr class=\"$estilo\" align=\"center\">";
          if(empty($vector[$i][transaccion])){                                                                                                                                                                                                               
            //$html .= "        <td id=\"Visualizar$i\" class=\"label\" align=\"center\"><a href=\"javascript:CallMostrarCargos('$Cuenta','".$vector[$i][descripcion]."','".$vector[$i][paquete_codigo_id]."','".$vector[$i][cuenta_liquidacion_qx_id]."','$i','$Cerradas','$estilo1','$accionM','$accionE','$TipoId','$PacienteId','$Nivel','$PlanId','$Fecha','$Ingreso');\"><b>(+)</b></a></td>";            
            $html .= "        <td class=\"label\" align=\"center\">";
            $html .= "        <div id=\"Visualizar$i\"><a href=\"javascript:CallMostrarCargos('$i',1);\"><b>(+)</b></a></div>";
            $html .= "        <div id=\"NoVisualizar$i\" style=\"display:none\"><a href=\"javascript:CallMostrarCargos('$i',2);\"><b>(-)</b></a></div>";
            $html .= "        </td>";            
          }else{
            $html .= "        <td class=\"label\" width=\"3%\">&nbsp;</td>";
          }
          $html .= "        <td class=\"label\">".$this->FechaStamp($vector[$i][fecha_cargo])."</td>";            
          if($vector[$i][paquete_codigo_id]){
            $cargoFacPaq=$funciones->BuscarCargoFacturado($Cuenta,$vector[$i][paquete_codigo_id]);
            $html .= "        <td class=\"label\">".$vector[$i][descripcion]." - ".$cargoFacPaq['descripcion']."</td>";
          }else{
            $html .= "        <td class=\"label\">".$vector[$i][descripcion]."</td>";
          }
          $html .= "        <td class=\"label\">".FormatoValor($vector[$i][precio])."</td>";
          $html .= "        <td class=\"label\">".$vector[$i][cantidad]."</td>";
          //Verifico la cantidad de los cargos no facturados para restarlo al total
          $paquetes='';$vectorQX='';$DatosQXEquipos='';$cargosQX='';$cargosDevQX='';$productos='';
          if($vector[$i][paquete_codigo_id]){
            $paquetes=$funciones->BuscarPaquetesCuentas($Cuenta,$vector[$i][paquete_codigo_id]);               
            $valCarNFact=0;
            $valCarNFactNC=0;
            $valParaQX=0;
            for($n=0;$n<sizeof($paquetes);$n++){
              if($paquetes[$n][facturado]=='0'){
                $valCarNFact+=$paquetes[$n][valor_cargo];
                $valCarNFactNC+=$paquetes[$n][valor_nocubierto];
              }
            }
          }elseif($vector[$i][cuenta_liquidacion_qx_id]){            
            $vectorQX = $funciones->DatosCirugia($vector[$i][cuenta_liquidacion_qx_id],$Cuenta);
            $DatosQXEquipos=$funciones->DatosEquiposQX($vector[$i][cuenta_liquidacion_qx_id]);
            $cargosQX= $funciones->CargosMedicamentosCuentaPaciente($vector[$i][cuenta_liquidacion_qx_id],$Cuenta);
            $cargosDevQX= $funciones->CargosMedicamentosCuentaPacienteDevol($vector[$i][cuenta_liquidacion_qx_id],$Cuenta);            
            $valCarNFact=0;
            $valCarNFactNC=0;
            foreach($vectorQX[0] as $indiceCirujano=>$Vector){
              foreach($Vector as $indiceProcedimiento=>$DatosQX){
                foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){
                  if($DatosDerecho['facturado']=='0'){
                    $valCar=$DatosDerecho[valor_cubierto]+$DatosDerecho[valor_no_cubierto];
                    $valCarNFact+=$valCar;
                    $valCarNFactNC+=$DatosDerecho[valor_no_cubierto];
                  }
                }
              }  
            }            
            for($n=0;$n<sizeof($DatosQXEquipos);$n++){
              if($DatosQXEquipos[$n]['facturado']=='0'){                
                $valCarNFact+=$DatosQXEquipos[$n]['valor_cargo'];
                $valCarNFactNC+=$DatosQXEquipos[$n]['valor_no_cubierto'];
              }
            } 
            for($n=0;$n<sizeof($cargosQX);$n++){
              if($cargosQX[$n]['facturado']=='0'){
                $valCarNFact+=$cargosQX[$n]['valor_cargo'];
                $valCarNFactNC+=$cargosQX[$n]['valor_nocubierto'];                
              }              
            }            
            for($n=0;$n<sizeof($cargosDevQX);$n++){
              if($cargosDevQX[$n]['facturado']=='0'){
                $valCarNFact+=$cargosDevQX[$n]['valor_cargo'];
                $valCarNFactNC+=$cargosDevQX[$n]['valor_nocubierto'];
              }              
            }            
          }else{          
            $productos= $funciones->BuscarCargosAgrupadosCuentas($Cuenta,$vector[$i][descripcion]);                        
            $valCarNFact=0;
            $valCarNFactNC=0;
            for($n=0;$n<sizeof($productos);$n++){
              if($productos[$n][facturado]=='0'){
                $valCarNFact+=$productos[$n][valor_cargo];
                $valCarNFactNC+=$productos[$n][valor_nocubierto];
              }
            }
          }
          $html .= "        <td class=\"label\">".FormatoValor($vector[$i][valor_cargo]-$valCarNFact)."</td>";
          $html .= "        <td class=\"label\">".FormatoValor($vector[$i][valor_nocubierto]-$valCarNFactNC)."</td>";
          //fin varificacion
          $res=FirmaResultado($vector[$i][transaccion]);
          $img='';
          //hay resultado
          if(!empty($res)){
            $reporte= new GetReports();
            $mostrar=$reporte->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$res),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
            $nombre_funcion=$reporte->GetJavaFunction();
            $html .=$mostrar;
            $html .= "        <td><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/checksi.png\"></a></td>";
            unset($reporte);
          }else{
            $html .= "      <td></td>";  
          }
          if($vector[$i][transaccion]){
            if($Cerradas!='Cerradas'){                                                     
              $accionModi=$accionM.UrlRequest(array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,'Transaccion'=>$vector[$i][transaccion],'Datos'=>$vector[$i],'EmpresaId'=>$EmpresaId,'CentroUtilidad'=>$CU));
              $html .= "    <td><a href=\"$accionModi\">MODI</a></td>";                                              
              //$html .= "    <td><a >MODI</a></td>";                                              
              $accionElim=$accionE.UrlRequest(array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Transaccion'=>$vector[$i][transaccion]));
              $html .= "    <td><a href=\"$accionElim\">ELIM</a></td>";
            }else{
              $html .= "    <td>&nbsp;</td>";
              $html .= "    <td>&nbsp;</td>";
            }  
          }else{
            $html .= "    <td>&nbsp;</td>";
            $html .= "    <td>&nbsp;</td>";
          }
          $D=$n=1;
          $imagenInt=$imagenExt='';
          if($vector[$i][interna]==='0'){
            $imagenInt="no_autorizado.png";   
            $D=1; 
          }elseif($vector[$i][interna] >100){
            $imagenInt="autorizado.png";   
            $D=0; 
          }elseif($vector[$i][interna] ==1){
            $imagenInt="autorizadosiis.png";   
            $D=1; 
          }
          if($vector[$i][externa]==='0'){
            $imagenExt="no_autorizado.png";   
            $n=1; 
          }elseif($vector[$i][externa] >100){
            $imagenExt="autorizado.png";   
            $n=0; 
          }elseif($vector[$i][externa] ==1){
            $imagenExt="autorizadosiis.png";   
            $n=1; 
          }
          $html .= "       <td>";
          if($imagenInt){
            $html .= "       <img src=\"".GetThemePath()."/images/$imagenInt\">"; 
          }
          $html .= "       </td>";
          $html .= "       <td>";
          if($imagenExt){
            $html .= "       <img src=\"".GetThemePath()."/images/$imagenExt\">";  
          }
          $html .= "       </td>";
          
          if($D==0 OR $n==0){
            $html .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'$TarifarioId','$Cargo',$Cuenta,".$vector[$i][interna].",0,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  
          }else{
            $html .= "       <td></td>";  
          }                    
          $html .= "    </tr>";          
          $html .= "    <tr class=\"$estilo\">";
          $html .= "    <td colspan=\"13\" >";                 
          $html .= "    <div id=\"Cargos$i\" style=\"display:none\">";          
          if(is_array($paquetes)){
            $html.= $this->MostrarDatosCargos($paquetes,$Cerradas,$estilo1,$accionM,$accionE,$accionDevol,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$modificacionCargos,$EmpresaId,$CU);              
          }elseif(is_array($vectorQX) || is_array($cargosQX) || is_array($cargosDevQX)){
            $html.= $this->MostrarActoQX($vector[$i][cuenta_liquidacion_qx_id],$vectorQX,$DatosQXEquipos,$cargosQX,$cargosDevQX,$estilo1);        
          }elseif(is_array($productos)){
            $html.= $this->MostrarDatosCargos($productos,$Cerradas,$estilo1,$accionM,$accionE,$accionDevol,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$modificacionCargos,$EmpresaId,$CU);           
          }
                     
          
          $html .= "    </div>";
          $html .= "    </td>";
          $html .= "    </tr>";          
        }       
        $html .= "</table>";
      }      
			return $html;		
		}
    
    /**********************************************************************************
    *Muestra los cargos a la cuenta
    *
    * @access public          
    * @return array
    ***********************************************************************************/ 
    
    function MostrarDatosCargos($productos,$Cerradas,$estilo1,$accionM,$accionE,$accionDevol,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$modificacionCargos,$EmpresaId,$CU){
      if($productos){
      $html .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" align=\"center\">";
      $html .= "<tr>";
      $html .= "<td width=\"2%\">&nbsp;</td>";
      $html .= "<td>";
      $html .= "<table border=\"1\" cellspacing=\"1\" cellpadding=\"1\" width=\"100%\" align=\"center\">";
      $html .= "  <tr align=\"center\" class=\"hc_table_submodulo\">";//hc_table_submodulo_list_title
      $html .= "    <td class=\"label\" width=\"6%\">FECHA</td>";
      $html .= "    <td class=\"label\" width=\"10%\">CARGO</td>";
      $html .= "    <td class=\"label\" >DESCRIPCION</td>";
      $html .= "    <td class=\"label\" width=\"7%\">PRECIO</td>";
      $html .= "    <td class=\"label\" width=\"5%\">CANT.</td>";
      $html .= "    <td class=\"label\" width=\"7%\">VALOR</td>";
      $html .= "    <td class=\"label\" width=\"7%\">NO CUBIER</td>";
      $html .= "    <td class=\"label\" width=\"7%\">CUBIERTO</td>";
      if($modificacionCargos==1 && $Cerradas!='Cerradas'){
        $html .= "    <td class=\"label\" colspan=\"2\" width=\"5%\">ACCION</td>";
      }
      $html .= "    <td class=\"label\" width=\"3%\">INT</td>";
      $html .= "    <td class=\"label\" width=\"3%\">EXT</td>";      
      $html .= "    <td class=\"label\" width=\"3%\">&nbsp;</td>";      
      $html .= "  </tr>";
      for($i=0;$i<sizeof($productos);$i++){
        if($productos[$i][facturado]=='0'){$estilo2='agendadomfes';}else{$estilo2=$estilo1;}
        $html .= "    <tr class=\"$estilo2\" align=\"center\">";  
        $html .= "        <td>".$this->FechaStamp($productos[$i][fecha_cargo])."</td>";      
        if($productos[$i][codigo_producto]){
          $html .= "        <td>".$productos[$i][codigo_producto]."</td>";
        }else{
          $html .= "        <td>".$productos[$i][des_tarifario]." ".$productos[$i][cargo]."</td>";
        }
        $html .= "        <td>".$productos[$i][descripcion]."</td>";
        if($productos[$i][facturado]=='0'){
          $html .= "        <td>0</td>";
        }else{
          $html .= "        <td>".FormatoValor($productos[$i][precio])."</td>";
        }
        $html .= "        <td>".$productos[$i][cantidad]."</td>";
        if($productos[$i][facturado]=='0'){
          $html .= "        <td>0</td>";
        }else{
          $html .= "        <td>".FormatoValor($productos[$i][valor_cargo])."</td>";
        }
        if($productos[$i][facturado]=='0'){
          $html .= "        <td>0</td>";
        }else{
          $html .= "        <td>".FormatoValor($productos[$i][valor_nocubierto])."</td>";
        }
        if($productos[$i][facturado]=='0'){
          $html .= "        <td>0</td>";
        }else{
          $html .= "        <td>".FormatoValor($productos[$i][valor_cubierto])."</td>";
        }  
        if($modificacionCargos==1 && $Cerradas!='Cerradas' && empty($productos[$i][cuenta_liquidacion_qx_id])){          
          if($productos[$i][codigo_producto]){
            $accionModi=$accionM.UrlRequest(array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,'Transaccion'=>$productos[$i][transaccion],'Datos'=>$productos[$i],'des'=>$productos[$i][des],'codigo'=>$productos[$i][codigo_agrupamiento_id],'doc'=>$productos[$i][bodegas_doc_id],'numeracion'=>$productos[$i][numeracion],'EmpresaId'=>$EmpresaId,'CentroUtilidad'=>$CU));         
            $html .= "    <td><a href=\"$accionModi\">MODI</a></td>"; 
            $html .= "    <td>&nbsp;</td>"; 
          }else{
            $accionModi=$accionM.UrlRequest(array("Cuenta"=>$Cuenta,"TipoId"=>$TipoId,"PacienteId"=>$PacienteId,"Nivel"=>$Nivel,"PlanId"=>$PlanId,"Fecha"=>$Fecha,"Ingreso"=>$Ingreso,'Transaccion'=>$productos[$i][transaccion],'Datos'=>$productos[$i],'des'=>$productos[$i][des],'codigo'=>$productos[$i][codigo_agrupamiento_id],'doc'=>$productos[$i][bodegas_doc_id],'numeracion'=>$productos[$i][numeracion],'EmpresaId'=>$EmpresaId,'CentroUtilidad'=>$CU));         
            $html .= "    <td><a href=\"$accionModi\">MODI</a></td>";  
            $accionElim=$accionE.UrlRequest(array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'Transaccion'=>$productos[$i][transaccion],'Datos'=>$productos[$i],'des'=>$productos[$i][des],'codigo'=>$productos[$i][codigo_agrupamiento_id],'doc'=>$productos[$i][bodegas_doc_id],'numeracion'=>$productos[$i][numeracion]));
            $html .= "    <td><a href=\"$accionElim\">ELIM</a></td>";
          }            
        }else{
          $html .= "    <td>&nbsp;</td>";
          $html .= "    <td>&nbsp;</td>"; 
        }
        $D=$n=1;
        $imagenInt=$imagenExt='';
        if($productos[$i][interna]==='0'){
          $imagenInt="no_autorizado.png";   
          $D=1; 
        }elseif($productos[$i][interna] >100){
          $imagenInt="autorizado.png";   
          $D=0; 
        }elseif($productos[$i][interna] ==1){
          $imagenInt="autorizadosiis.png";   
          $D=1; 
        }
        if($productos[$i][externa]==='0'){
          $imagenExt="no_autorizado.png";   
          $n=1; 
        }elseif($productos[$i][externa] >100){
          $imagenExt="autorizado.png";   
          $n=0; 
        }elseif($productos[$i][externa] ==1){
          $imagenExt="autorizadosiis.png";   
          $n=1; 
        }
        $html .= "       <td>";
        if($imagenInt){
          $html .= "       <img src=\"".GetThemePath()."/images/$imagenInt\">"; 
        }
        $html .= "       </td>";
        $html .= "       <td>";
        if($imagenExt){
          $html .= "       <img src=\"".GetThemePath()."/images/$imagenExt\">";  
        }
        $html .= "       </td>";
        
        if($D==0 OR $n==0){
          $html .= "       <td><a href=\"javascript:ConsultaAutorizacion('DATOS DE LA AUTORIZACION','reports/$VISTA/datosautorizacioncargo.php',1000,250,'$TarifarioId','$Cargo',$Cuenta,".$productos[$i][interna].",0,'Int')\"><img src=\"".GetThemePath()."/images/informacion.png\" border=\"0\"></a></td>";  
        }else{
          $html .= "       <td></td>";  
        } 
        $html .= "    </tr>";
      }
      if($modificacionCargos==1 && !empty($productos[0][consecutivo])){
        $accionDevolucion=$accionDevol.UrlRequest(array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $html .= "    <tr><td class=\"label\" align=\"right\" colspan=\"13\"><a class=\"Menu\" href=\"$accionDevolucion\">DEVOLUCION MEDICAMENTOS</a></td></tr>";
      }
      $html .= "</table>";
      $html .= "</td>";
      $html .= "</tr>";
      $html .= "</table><BR>";
      }
      return $html; 
    }
    
    /**********************************************************************************
    *Muestra los actos qx de la cuenta
    *
    * @access public          
    * @return array
    ***********************************************************************************/ 
    
    function MostrarActoQX($liquidacion,$vector,$DatosQXEquipos,$cargos,$cargosDev,$estilo1){
        
        $var=$vector[0];
        $funciones1 = new DetalleCta; 
        if($estilo1=='modulo_list_claro'){$estilo='modulo_list_oscuro';}else{$estilo='modulo_list_claro';}
        $html .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
        $html .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DEL ACTO QUIRURGICO No. ".$liquidacion."</td></tr>";
        $html .= "    <tr class=\"$estilo1\">";
        $html .= "    <td width=\"10%\" class=\"label\">ANESTESIOLOGO</td>";
        if($var[1][1]['liquidacion']['DA']['tipo_id_tercero'] && $var[1][1]['liquidacion']['DA']['tercero_id']){
          $nombreTercero=$funciones1->BuscarTercero($var[1][1]['liquidacion']['DA']['tipo_id_tercero'],$var[1][1]['liquidacion']['DA']['tercero_id']);
        }
        $html .= "    <td width=\"40%\">".$nombreTercero[0]."</td>";
        $html .= "    <td width=\"10%\" class=\"label\">AYUDANTE</td>";
        if($var[1][1]['liquidacion']['DY']['tipo_id_tercero'] && $var[1][1]['liquidacion']['DY']['tercero_id']){
          $nombreTercero=$funciones1->BuscarTercero($var[1][1]['liquidacion']['DY']['tipo_id_tercero'],$var[1][1]['liquidacion']['DY']['tercero_id']);
        }
        $html .= "    <td width=\"40%\">".$nombreTercero[0]."</td>";
        $html .= "    </tr>";
        foreach($var as $indiceCirujano=>$Vector){
          $html .= "        <tr class=\"modulo_table_title\">";
          $html .= "         <td width=\"10%\">CIRUJANO ".$indiceCirujano."</td>";
          if($Vector[1]['tipo_id_cirujano'] && $Vector[1]['cirujano_id']){
            $nombreTercero=$funciones1->BuscarTercero($Vector[1]['tipo_id_cirujano'],$Vector[1]['cirujano_id']);
          }
          $html .= "         <td colspan=\"3\">".$nombreTercero[0]."</td>";
          $html .= "       </tr>";
          foreach($Vector as $indiceProcedimiento=>$DatosQX){
            $html .= "    <tr class=\"$estilo1\">";
            $html .= "      <td colspan=\"4\">";
            $html .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
            $cups=NombreCargoCups($DatosQX['cargo_cups']);
            $html .= "       <tr class=\"$estilo\">";
            $html .= "        <td  width=\"10%\" class=\"label\">CARGO CUPS</td>";
            $html .= "        <td colspan=\"4\">".$DatosQX['cargo_cups']." - ".$cups."</td>";
            $html .= "       </tr>";
            $tarifario=NombreTarifario($DatosQX['tarifario_id']);
            $html .= "       <tr class=\"$estilo\">";
            $html .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
            $html .= "        <td colspan=\"4\">".$tarifario." ".$DatosQX['cargo']." - ".$DatosQX['descripcion']."</td>";
            $html .= "       </tr>";
            $html .= "          <tr class=\"modulo_table_list_title\">";
            $html .= "          <td width=\"10%\">".$indiceProcedimiento."</td>";
            $html .= "          <td width=\"20%\">CARGO</td>";
            $html .= "          <td width=\"10%\">%</td>";
            $html .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
            $html .= "          <td>VALOR NO CUBIERTO</td>";
            $html .= "          </tr>";            
            foreach($DatosQX['liquidacion'] as $derecho=>$DatosDerecho){
              if($DatosDerecho['facturado']==2){
                $html .= "        <tr class=\"hc_table_submodulo_list_title\">";
              }else{                
                $html .= "        <tr class=\"$estilo\">";
              }              
              $html .= "        <td class=\"label\">$derecho</td>";
              $tarifario=NombreTarifario($DatosDerecho['tarifario_id']);
              $html .= "        <td>".$tarifario." - ".$DatosDerecho['cargo']."</td>";
              $html .= "        <td align=\"right\">".$DatosDerecho['PORCENTAJE']."</td>";
              $html .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_cubierto'])."</td>";
              $html .= "        <td align=\"right\">".FormatoValor($DatosDerecho['valor_no_cubierto'])."</td>";
              $html .= "        </tr>";
            }
            $html .= "       </table>";
            $html .= "      </td>";
            $html .= "    </tr>";
        }
      }
      $html .= "    </table>";
      $DatosQXEquipos=$vector[1];
      if($DatosQXEquipos){
          $html .= "    <BR><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
          $html .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">CARGOS DE EQUIPOS DEL ACTO QUIRURGICO No. ".$NoLiquidacion."</td></tr>";
          for($i=0;$i<sizeof($DatosQXEquipos);$i++){
              $html .= "    <tr class=\"modulo_list_oscuro\">";
              $html .= "      <td colspan=\"4\">";
              $html .= "       <table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
              $html .= "       <tr class=\"modulo_list_claro\">";
              $html .= "        <td  width=\"10%\" class=\"label\">EQUIPO</td>";
              $html .= "        <td colspan=\"4\">".$DatosQXEquipos[$i]['descripcion_equipo']."&nbsp&nbsp&nbsp;<label class=\"label\">DURACION:&nbsp&nbsp&nbsp;</label>".$DatosQXEquipos[$i]['duracion']."</td>";
              $html .= "       </tr>";
              $descripciones=$funciones1->DescripcionCargosTarifario($DatosQXEquipos[$i]['tarifario_id']);
              $html .= "       <tr class=\"modulo_list_claro\">";
              $html .= "        <td  width=\"10%\" class=\"label\">EQUIVALENCIA</td>";
              $html .= "        <td colspan=\"4\">".$descripciones['tarifario']." ".$DatosQXEquipos[$i]['cargo']." - ".$DatosQXEquipos[$i]['descripcion']."</td>";
              $html .= "       </tr>";
              $html .= "          <tr class=\"modulo_table_list_title\">";
              $html .= "          <td width=\"10%\">TIPO EQUIPO</td>";
              $html .= "          <td width=\"10%\">CANTIDAD</td>";
              $html .= "          <td width=\"30%\">VALOR CUBIERTO</td>";
              $html .= "          <td width=\"30%\">VALOR NO CUBIERTO</td>";
              $html .= "          <td width=\"10%\">FACTURADO</td>";
              $html .= "          </tr>";
              $html .= "        <tr class=\"modulo_list_claro\">";
              if($DatosQXEquipos[$i]['tipo_equipo']=='fijo'){
                  $html .= "        <td align=\"center\">FIJO</td>";
              }else{
                  $html .= "        <td align=\"center\">MOVIL</td>";
              }
              $html .= "        <td>".$DatosQXEquipos[$i]['cantidad']."</td>";
              if($valoresManual==1){
                  $html .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."\"></td>";
              }else{
                  $html .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_cubierto'])."</td>";
              }
              if($valoresManual==1){
                  $html .= "        <td align=\"center\"><input align=\"right\" type=\"text\" class=\"input-text\" size=\"8\" name=\"valoresNoCubiertosEquipos[$i]\" value=\"".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."\"></td>";
              }else{
                  $html .= "        <td align=\"right\">".FormatoValor($DatosQXEquipos[$i]['valor_no_cubierto'])."</td>";
              }

              if($DatosQXEquipos[$i]['facturado']=='1'){
                  $html .= "        <td align=\"center\">SI</td>";
              }else{
                  $html .= "        <td align=\"center\">NO</td>";
              }
              $html .= "      </table>";
              $html .= "      </td>";
              $html .= "    </tr>";
          }
          $html .= "    </table>";
      }
      //consulta de los medicamentos en la cuenta del paciente
     
      if(is_array($cargos) || is_array($cargosDev)){
          $html .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list_title\">";
          $html .= "    <tr class=\"modulo_table_title\"><td colspan=\"6\">INSUMOS Y MEDICAMENTOS CARGADOS EN LA CUENTA</td></tr>";
          $html .= "    <tr class=\"modulo_table_title\">";
          $html .= "    <td width=\"15%\">CODIGO</td>";
          $html .= "    <td width=\"15%\">CANTIDAD</td>";
          $html .= "    <td>PRODUCTO</td>";
          $html .= "    <td width=\"15%\">VALOR NO CUBIERTO</td>";
          $html .= "    <td width=\"15%\">VALOR CUBIERTO</td>";
          $html .= "    <td width=\"15%\">FACTURADO</td>";
          $html .= "    </tr>";
          if(is_array($cargos)){
            $html .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DESPACHOS</td></tr>";
            for($i=0;$i<sizeof($cargos);$i++){
              if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
              $html .= "    <tr class=\"$estilo\">";
              $html .= "    <td align=\"left\">".$cargos[$i]['codigo_producto']."</td>";
              $divisor=(int)($cargos[$i]['cantidad']);
              if($cargos[$i]['cantidad']%$divisor){
                  $html .= "    <td align=\"left\">".$cargos[$i]['cantidad']."</td>";
              }else{
                  $html .= "    <td align=\"left\">".$divisor."</td>";
              }
              $html .= "    <td align=\"left\">".$cargos[$i]['descripcion']."</td>";
              $html .= "    <td align=\"left\">".$cargos[$i]['valor_nocubierto']."</td>";
              $html .= "    <td align=\"left\">".$cargos[$i]['valor_cubierto']."</td>";
              if($cargos[$i]['facturado']==1){
                $html .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
              }else{
                $html .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
              }
              $y++;
            }
          }
          if(is_array($cargosDev)){
            $html .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"6\">DEVOLUCIONES</td></tr>";
            for($i=0;$i<sizeof($cargosDev);$i++){
                if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                $html .= "    <tr class=\"$estilo\">";
                $html .= "    <td align=\"left\">".$cargosDev[$i]['codigo_producto']."</td>";
                $divisor=(int)($cargosDev[$i]['cantidad']);
                if($cargosDev[$i]['cantidad']%$divisor){
                    $html .= "    <td align=\"left\">".$cargosDev[$i]['cantidad']."</td>";
                }else{
                $html .= "    <td align=\"left\">".$divisor."</td>";
                }
                $html .= "    <td align=\"left\">".$cargosDev[$i]['descripcion']."</td>";
                $html .= "    <td align=\"left\">".$cargosDev[$i]['valor_nocubierto']."</td>";
                $html .= "    <td align=\"left\">".$cargosDev[$i]['valor_cubierto']."</td>";
                if($cargosDev[$i]['facturado']==1){
                  $html .= "    <td align=\"center\"><img title=\"Cargo Facturado\" border = 0 src=\"".GetThemePath()."/images/checksi.png\"></td>";
                }else{
                  $html .= "    <td align=\"center\"><img title=\"Cargo No Facturado\" border = 0 src=\"".GetThemePath()."/images/checkno.png\"></td>";
                }
                $y++;
            }
          }
          $html .= "    </table>";          
      }
      return $html;
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
    /**
    * Metodo donde se obtiene el detalle de las cuentas
    * 
    * @param integer $cuenta Identificador de la cuenta
    *
    * @return mixed
    */
    function ObtenerDetalleCuenta($cuenta)
    {                   
      $sql  = "SELECT GT.grupo_tarifario_descripcion,";
      $sql .= "       TO_CHAR(MIN(CU.fecha_cargo),'DD/MM/YYYY') AS fecha_cargo, ";
      $sql .= "       SUM(CU.cantidad) AS cantidad, ";
      $sql .= "       SUM(CU.valor_nocubierto) AS valor_nocubierto,";
      $sql .= "       SUM(CU.valor_cubierto) AS valor_cubierto,";
      $sql .= "       SUM(CU.porcentaje_descuento_paciente) AS porcentaje_descuento_paciente,";
      $sql .= "       SUM(CU.porcentaje_descuento_empresa) AS porcentaje_descuento_empresa, ";
      $sql .= "       SUM(CU.valor_descuento_empresa) AS valor_descuento_empresa,";
      $sql .= "       SUM(CU.valor_descuento_paciente) AS valor_descuento_paciente,";
      $sql .= "       CU.cargo_cups,";
      $sql .= "       CP.descripcion,";
      $sql .= "       CU.valor_cargo";
      $sql .= "FROM   cuentas_detalle CU, ";
      $sql .= "       tarifarios_detalle TD,";
      $sql .= "       subgrupos_tarifarios ST,";
      $sql .= "       grupos_tarifarios GT,";
      $sql .= "       cups CP ";
      $sql .= "WHERE  CU.numerodecuenta = ".$cuenta."  ";
      $sql .= "AND    CU.consecutivo IS NULL ";
      $sql .= "AND    CU.cargo_cups = CP.cargo ";
      $sql .= "AND    CU.cargo = TD.cargo ";
      $sql .= "AND    CU.tarifario_id = TD.tarifario_id ";
      $sql .= "AND    TD.grupo_tarifario_id = ST.grupo_tarifario_id ";
      $sql .= "AND    TD.subgrupo_tarifario_id = ST.subgrupo_tarifario_id ";
      $sql .= "AND    ST.grupo_tarifario_id = GT.grupo_tarifario_id  ";
      $sql .= "GROUP BY 1,10,11,12  ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos; 
    }
	}
?>