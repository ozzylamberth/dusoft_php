<?php
  /******************************************************************************
  * $Id: ModificacionCargoHTML.class.php,v 1.2 2007/03/01 13:11:29 lorena Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
  * $Revision: 1.2 $ 
  * 
  * @autor Lorena Aragon Galindo
  ********************************************************************************/
  IncludeClass('ModificacionCargo','','app','Facturacion');
  
  class ModificacionCargoHTML
  {
    function ModificacionCargoHTML(){}
    /**********************************************************************************
    * Funcion donde se solicitan los datos para realizar la modificacion de un cargo
    * 
    * @return array 
    ***********************************************************************************/
    function CrearFormaModificacionCargo($Transaccion,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$D,$mensaje,$accionCancelar,$accionModificar,$EmpresaId,$CentroU){  
                    
      $funciones = new ModificacionCargo;             
      $FechaCargo=$this->FechaStamp($D[fecha_cargo]);           
      $Nombres=$funciones->BuscarNombresPaciente($TipoId,$PacienteId);
      $Apellidos=$funciones->BuscarApellidosPaciente($TipoId,$PacienteId);              
      $html .= ThemeAbrirTabla('MODIFICAR CARGO DE LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);       
      $html .=$this->LiqManual();
      $html .= "    <form name=\"forma1\" action=\"$accionModificar\" method=\"post\">";
      $html .= "      <table border=\"0\" width=\"90%\" align=\"center\">";
      $html .= "            <p class=\"label_error\" align=\"center\">$mensaje</p>";
      $html .= "            <tr><td><fieldset><legend class=\"field\">MODIFICAR CARGO</legend>";
      $html .= "              <table height=\"74\" border=\"0\" width=\"96%\" align=\"center\"   class=\"normal_10\">";
      $html .= "              <input type=\"hidden\" name=\"TarifarioId\" value=\"".$D[tarifario_id]."\">";      
      $html .= "              <input type=\"hidden\" name=\"Gravamen\" value=\"".($D[gravamen_valor_nocubierto]+$D[gravamen_valor_cubierto])."\">";      
      $html .= "              <input type=\"hidden\" name=\"GravamenE\" value=\"".$D[gravamen_valor_cubierto]."\">";
      $html .= "              <input type=\"hidden\" name=\"GravamenP\" value=\"".$D[gravamen_valor_nocubierto]."\">";                  
      $html .= "              <input type=\"hidden\" name=\"Cobertura\" value=\"".(($D[valor_cubierto]/$D[valor_cargo])*100)."\">";            
      $html .= "              <input type=\"hidden\" name=\"Consecutivo\" value=\"".$D[consecutivo]."\">";
      $html .= "              <input type=\"hidden\" name=\"ValorCargo\" value=\"".$D[valor_cargo]."\">";
      $html .= "              <input type=\"hidden\" name=\"ValorDesEmp\" value=\"".$D[valor_descuento_empresa]."\">";
      $html .= "              <input type=\"hidden\" name=\"ValorDesPac\" value=\"".$D[valor_descuento_paciente]."\">";
      $html .= "              <input type=\"hidden\" name=\"Moderadora\" value=\"".$D[valor_cuota_moderadora]."\">";
      $html .= "              <input type=\"hidden\" name=\"Copago\" value=\"".$D[valor_cuota_paciente]."\">";
      $html .= "              <input type=\"hidden\" name=\"ValorCub\" value=\"".$D[valor_cubierto]."\">";        
      $html .= "             <tr>";
      $html .= "                <td class=\"label\" width=\"18%\" >DEPARTAMENTO: </td>";
      $html .= "                  <td><select name=\"Departamento\" class=\"select\">";
     
      
      $departamento=$funciones->Departamentos($EmpresaId,$CentroU);      
     
      $html .=" <option value=\"-1\" selected>--TODOS--</option>";
      foreach($departamento as $value=>$titulo){
        if($value==$D[departamento]){
            $sel='selected';
        }
        $html .=" <option value=\"$value\" $sel>$titulo</option>";          
      }
      $html .= "                  </select></td>";
      $html .= "                <td>&nbsp;&nbsp;</td>";
      $html .= "                <td class=\"".$this->SetStyle("Cargo")."\">CARGO: </td>";
      $html .= "                <td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"10\" value=\"".$D[cargo]."\" readonly></td>";        
      $html .= "                <td>&nbsp;&nbsp;</td>";
      $html .= "                <td class=\"".$this->SetStyle("Cantidad")."\">CANTIDAD: </td>";
      $read='';
      $SWCantidad=$funciones->SWCantidad($TarifarioId,$Cargo);
      if($SWCantidad==0){
        $read='readonly';        
      }
      $html .= "             <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"5\" value=\"".round($D[cantidad])."\" $read></td>";        
      $html .= "               </tr>";
      $html .= "              <tr>";
      $html .= "                <td class=\"label\">DESCRIPCION: </td>";
      $html .= "                <td colspan=\"6\"><textarea cols=\"120\" rows=\"3\" class=\"textarea\"name=\"Descripcion\" readonly>".$funciones->BuscarNombreCargo($D[tarifario_id],$D[cargo])."</textarea></td>";
      $html .= "              </tr>";
      $html .= "              <tr>";
      $html .= "                <td class=\"label\">PRECIO: </td>";
      $html .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorNo\" size=\"10\" value=\"".FormatoValor($D[precio])."\" readonly></td>";
      $html .= "                <td>&nbsp;</td>";
      if(!$_REQUEST['Manual']){$disabled='disabled';}
      $html .= "                <td class=\"label\">VAL. PACIENTE: </td>";if($_REQUEST['ValorPac']){$ValorPac=$_REQUEST['ValorPac'];}else{$ValorPac=$D[valor_nocubierto];}
      $html .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorPac\" size=\"10\" value=\"".FormatoValor($ValorPac)."\" $disabled></td>";
      $html .= "                <td>&nbsp;</td>";
      $html .= "                <td class=\"label\">VAL EMPRESA: </td>";if($_REQUEST['ValorEmp']){$ValorEmp=$_REQUEST['ValorEmp'];}else{$ValorEmp=$D[valor_cubierto];}
      $html .= "                <td><input type=\"text\" class=\"input-text\" name=\"ValorEmp\" size=\"10\" value=\"".FormatoValor($ValorEmp)."\" $disabled></td>";
      $html .= "              </tr>";
      $html .= "              <tr>";
      $html .= "                <td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: </td>";
      $html .= "                <td colspan=\"3\"><input type=\"text\" name=\"FechaCargo\" readonly value=\"$FechaCargo\" size=\"13\" class=\"input-text\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;&nbsp;";
      $html .= ReturnOpenCalendario('forma1','FechaCargo','/')."</td>";        
      $html .= "              </tr>";
      $html .= "              <tr>";
      $html .= "                <td class=\"label\">DESCUENTO PACIENTE: </td>";
      $html .= "                <td><input type=\"text\" class=\"input-text\" name=\"DescuentoPac\" size=\"5\" value=\"".FormatoValor($D[porcentaje_descuento_empresa])."\" >&nbsp;%</td>";
      $html .= "                <td>&nbsp;</td>";
      $html .= "                <td class=\"label\">DESCUENTO EMPRESA: </td>";
      $html .= "                <td><input type=\"text\" class=\"input-text\" name=\"DescuentoEmp\" size=\"5\" value=\"".FormatoValor($D[porcentaje_descuento_paciente])."\" >&nbsp;%</td>";
      $html .= "                <td>&nbsp;</td>";
      $che='';if($_REQUEST['Manual']){$che='checked';}
      $html .= "                <td class=\"label\" colspan=\"2\">MANUAL&nbsp;<input $che type=\"checkbox\" value=\"1\" name=\"Manual\" onclick=\"manual(this.form,this.value)\"></td>";
      $html .= "              </tr>";
      $html .= "             </table>";
      $html .= "          </fieldset></td></tr></table>";
      //justificacion de modificacion
      $html .= "<table width=\"50%\" align=\"center\" border=0>";
      $html .= "  <tr>";
      $html .= "    <td class=\"".$this->SetStyle("observacion")."\">JUSTIFICACION: </td>";
      $html .= "    <td align=\"left\"><textarea cols=\"45\" rows=\"3\" class=\"textarea\" name=\"observacion\">".$_REQUEST['observacion']."</textarea></td>";
      $html .= "  </tr>";
      $html .= "</table>";
      $html .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
      $html .= "    <tr align=\"center\">";
      $html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"MODIFICAR\"></td>";
      $html .= "    </form>";
      //if(empty($_REQUEST['codigo']))
      //{
      //  $accionCancelar=ModuloGetURL('app','Facturacion','user','Cuenta',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
      //}
      //else 
      //{
      // $accionCancelar=ModuloGetURL('app','Facturacion','user','DefinirForma',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'codigo'=>$_REQUEST['codigo'],'consecutivo'=>$consecutivo,'doc'=>$_REQUEST['doc'],'numeracion'=>$_REQUEST['numeracion'],'des'=>$des,'noFacturado'=>$noFacturado['facturado']));
      //}
      $html .= "    <form name=\"formaborrar\" action=\"$accionCancelar\" method=\"post\">";
      $html .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
      $html .= "    </form>";
      $html .= "    </tr>";
      $html .= " </table><br>";
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
      
    function LiqManual(){
    
      $html .= "<SCRIPT>";
      $html .= "function manual(forma,valor){ ";
      $html .= "  if(forma.Manual.checked){";
      $html .= "    forma.DescuentoEmp.disabled=true; ";
      $html .= "    forma.DescuentoPac.disabled=true; ";
      $html .= "    forma.ValorPac.disabled=false;";
      $html .= "    forma.ValorEmp.disabled=false;";
      $html .= "  }";
      $html .= "  else";
      $html .= "  {";
      $html .= "    forma.DescuentoEmp.disabled=false;";
      $html .= "    forma.DescuentoPac.disabled=false;";
      $html .= "    forma.ValorPac.disabled=true;";
      $html .= "    forma.ValorEmp.disabled=true;";
      $html .= "  }";
      $html .= "}";
      $html .= "</SCRIPT>";
      return $html;
    }
    
    
      
    function SetStyle($campo){
        if ($this->frmError[$campo] || $campo=="MensajeError"){
          if ($campo=="MensajeError"){
            return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
          }
          return ("label_error");
        }
        return ("label");
    }
    
    
    
    
    
    
    
    
  }
?>
