<?php
  /******************************************************************************
  * $Id: PaquetesCargosCtaHTML.class.php,v 1.2 2007/02/22 15:23:47 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Lorena Aragon Galindo
  ********************************************************************************/
  IncludeClass('PaquetesCargosCta','','app','Facturacion');
  
	class PaquetesCargosCtaHTML
	{
		function PaquetesCargosCtaHTML(){}
		/**********************************************************************************
		* Funcion donde se buscan crea la forma de los paquetes
		* 
		* @return array 
		***********************************************************************************/
		function CrearFormaPaquetesCargosCta($Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Fecha,$Ingreso,$Estado,$objeto,$accionVolver){	
      $file = 'app_modules/Facturacion/RemoteXajax/PaquetesCargosCuentas.php';
      $objeto->SetXajax(array("FacturarCargoPaquete","AdicionarCargoPaquete","InsertarCargoPaquete","EliminarCargoPaquete","InsertarNuevoPaquete","InsertarCargoNuevoPaquete","EliminarVistaCargosCuenta","InsertarTodosCargosPaquete"),$file);                   
    	$funciones = new PaquetesCargosCta;	
      $Nombres=$funciones->BuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$funciones->BuscarApellidosPaciente($TipoId,$PacienteId);    
      $html .= ThemeAbrirTabla('CUENTAS - PAQUETES DE CARGOS EN LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos.'');
      $html .="<script language='javascript'>";
      $html .= 'function mOvr(src,clrOver){';
      $html .= '  src.style.background = clrOver;';
      $html .= '}';
      $html .= 'function mOut(src,clrIn){';
      $html .= '  src.style.background = clrIn;';
      $html .= '}';
      $html .= 'function CallAjax(paquete,cuenta){';
      $html .= '  xajax_AdicionarCargoPaquete(paquete,cuenta);';
      $html .= '}';
      $html .= 'function CallAjaxUno(Cuenta,paqueteId,transaccion){';
      $html .= '  xajax_EliminarCargoPaquete(Cuenta,paqueteId,transaccion);';
      $html .= '}';
      $html .= 'function CallAjaxDos(Cuenta){';
      $html .= '  xajax_InsertarNuevoPaquete(Cuenta);';
      $html .= '}';
      $html .= 'function CallAjaxTres(){';
      $html .= '  xajax_EliminarVistaCargosCuenta();';
      $html .= '}';
      $html .= 'function CallAjaxCuatro(paquete,Cuenta,nuevoPaquete){';
      $html .= '  xajax_InsertarTodosCargosPaquete(paquete,Cuenta,nuevoPaquete);';
      $html .= '}';
      
      $html .= '</script>';
      //$accion=ModuloGetURL('app','Facturacion','user','BuscarDivision',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      $html .= "    <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $html .= "    <table border=\"0\" width=\"100%\" align=\"center\">";                  
      $html .= "    <tr><td id=\"PaquetesCargosCuentas\">";
      $det=$funciones->BuscarPaquetesCuenta($Cuenta);
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
            $html .= "          <td width=\"8%\" align=\"center\">".$this->FechaStamp($vectorPaquete[fecha_cargo])."</td>";
            $html .= "          <td width=\"5%\" align=\"center\">".$this->HoraStamp($vectorPaquete[fecha_cargo])."</td>";
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
      $html .= "    </td></tr>";
      $html .= "    <tr><td>";      
      $html .= "      <table border=\"0\" width=\"90%\" align=\"center\">";  
      $html .= "        <tr><td class=\"label\">";      
      $html .= "        <a href=\"javascript:CallAjaxDos('$Cuenta');\" class=\"link\">ADICIONAR PAQUETE A LA CUENTA</a>";
      $html .= "        </td></tr>";
      $html .= "      </table>";
      $html .= "    </td></tr>";
      $html .= "    </table><BR>";
     
      $html .= "    <table border=\"0\" width=\"100%\" align=\"center\">";                  
      $html .= "    <tr><td id=\"CargosCuentas\">";             
      $html .= "    </td></tr>";
      $html .= "    </table>";
              
      $html .= "    </form>";        
      $html .= "<form name=\"formabuscar\" action=\"$accionVolver\" method=\"post\">";
      $html .= "<p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER AL DETALLE\"></p>";
      $html .= "</form>";      
      $html .= ThemeCerrarTabla();
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
    
    
    
    
	}
?>