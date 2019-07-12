<?php

/**
 * $Id: $
 * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Contiene los metodos visuales para realizar las autorizaciones.
 */

class app_MantenimientoCuentas_userclasses_HTML extends app_MantenimientoCuentas_user
{

     function app_MantenimientoCuentas_user_HTML()
	{
          $this->salida='';
          $this->app_MantenimientoCuentas_user();
          return true;
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




/*
	function FormaMenus($arr)
	{
          $this->SetJavaScripts('DatosPaciente');
          $this->salida .= ThemeAbrirTabla('MANTENIMIENTO CUENTAS - BUSCAR DATOS PACIENTE','80%');
		//--------------------------
          $accion=ModuloGetURL('app','MantenimientoCuentas','user','BuscarPaciente');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<table class=\"modulo_list_claro\" border=\"0\" width=\"40%\" align=\"center\">";
          $this->salida .= "<tr class=\"modulo_table_list_title\">";
          $this->salida .= "<td align = left colspan=\"2\">CRITERIOS DE BUSQUEDA:</td>";
          $this->salida .= "</tr>";
          $this->salida .= "<tr class=\"modulo_list_claro\" >";
          $this->salida .= "<td width=\"40%\" colspan=\"2\">";
          $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
          $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
          $tipo_id=$this->TiposIdPacientes();
          $this->salida .=" <option value=\"\">----SELECCIONE---</option>";
          for($i=0; $i<sizeof($tipo_id); $i++)
          {
               $this->salida .=" <option value=\"".$tipo_id[$i]['tipo_id_paciente']."\">".$tipo_id[$i]['descripcion']."</option>";
               if($_REQUEST['TipoDocumento']==$tipo_id[$i]['tipo_id_paciente'])
               {
                    $this->salida .=" <option value=\"".$tipo_id[$i]['tipo_id_paciente']."\" selected>".$tipo_id[$i]['descripcion']."</option>";
               }
          }
          $this->salida .= "                  </select></td></tr>";
          $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
          $this->salida .= "<tr><td class=\"label\">Nro CUENTA</td><td><input type=\"text\" class=\"input-text\" name=\"Cuenta\" maxlength=\"8\"></td></tr>";
          $this->salida .= "  </table>";
          $this->salida .= "</td></tr>";
          $this->salida .= "<tr class=\"modulo_list_claro\">";
        	$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
          $this->salida .= "            </form>";
        	$accion=ModuloGetURL('app','MantenimientoCuentas','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        	$this->salida .= "<td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOVER\"></td>";
          $this->salida .= "            </form>";
          $this->salida .= "</tr>";
          $this->salida .= "  </table>";
          //mensaje
          $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "  </table>";
          if($arr)
          {
               $this->salida .= "		   <br>";
               $this->salida .= "		<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
               $this->salida .= "			<tr align=\"center\" class=\"modulo_table_list_title\">";
               $this->salida .= "				<td>IDENTIFICACION</td>";
               $this->salida .= "				<td>NOMBRE</td>";
               $this->salida .= "				<td></td>";
               $this->salida .= "			</tr>";
               for($i=0;$i<sizeof($arr);$i++)
               {
                    if( $i % 2) $estilo='modulo_list_claro';
                    else $estilo='modulo_list_oscuro';
                    $this->salida .= "			<tr class=\"$estilo\">";
                    $this->salida .= "				<td width=\"20%\">".$arr[$i][tipo_id_paciente]."  ".$arr[$i][paciente_id]."</td>";
                    $dato=RetornarWinOpenDatosPaciente($arr[$i][tipo_id_paciente],$arr[$i][paciente_id],$arr[$i][nombre]);
                    $this->salida .= "				<td width=\"36%\">".$dato."</td>";
                    $accion1=ModuloGetURL('app','BioEstadistica','user','LlamarModificarDatosPaciente',array('tipoid'=>$arr[$i][tipo_id_paciente],'paciente'=>$arr[$i][paciente_id],'ingreso'=>$arr[$i][ingreso]));
                    $this->salida .= "				<td align=\"center\" width=\"15%\"><a href=\"$accion1\">MODIFICAR DATOS</a></td>";
                    $this->salida .= "			</tr>";
               }//fin for
               $this->salida .= " </table>";
               $this->conteo=$_SESSION['SPY'];
               $this->salida .=$this->RetornarBarra();
          }
          $this->salida .= ThemeCerrarTabla();
          return true;
 	}*/
	
  function FormaMenus($arr)
  {
          $this->salida = ThemeAbrirTabla('MANTENIMIENTO CUENTAS - BUSCAR DATOS PACIENTE','80%');
    //--------------------------
          $accion=ModuloGetURL('app','MantenimientoCuentas','user','BuscarPaciente');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<table class=\"modulo_list_claro\" border=\"0\" width=\"40%\" align=\"center\">";
          $this->salida .= "<tr class=\"modulo_table_list_title\">";
          $this->salida .= "<td align = left colspan=\"3\">SELECIONE CUENTA</td>";
          $this->salida .= "</tr>";
          
          $this->salida .= "<tr><td class=\"label\">Nro CUENTA</td><td><input type=\"text\" class=\"input-text\" name=\"Cuenta\" value=\"$_REQUEST[Cuenta]\" maxlength=\"8\"></td><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td></tr>";
          
          $this->salida .= "<tr class=\"modulo_list_claro\">";
          $this->salida .= "</form>";
          $accion=ModuloGetURL('app','MantenimientoCuentas','user','main');
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $this->salida .= "<td align=\"center\"  colspan=\"3\"><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOVER\"></td>";
          $this->salida .= "</form>";
          $this->salida .= "</tr>";
          $this->salida .= "  </table>";
          //mensaje
          $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
          $this->salida .= $this->SetStyle("MensajeError");
          $this->salida .= "  </table>";
          
          if(is_array($arr))
          {
            $this->salida .= "      <br>";
            $this->salida .= "   <table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "     <tr align=\"center\" class=\"modulo_table_list_title\">";
            //$this->salida .= "       <td>IDENTIFICACION</td>";
            //$this->salida .= "       <td>NOMBRE</td>";
            $this->salida .= "       <td>CUENTA</td>";
            $this->salida .= "       <td>TOTAL</td>";            
            $this->salida .= "       <td>E</td>";
            //$this->salida .= "       <td></td>";
            $this->salida .= "     </tr>";
            $estilo='modulo_table_title';
            $this->salida .= "      <tr class=\"$estilo\">";
            //$this->salida .= "        <td width=\"30%\">".$arr[0][tipo_id_paciente]."  ".$arr[0][paciente_id]."</td>";
            //$this->salida .= "        <td width=\"50%\">".$arr[0][nombre]."</td>";
            $this->salida .= "        <td width=\"10%\">".$arr[0][numerodecuenta]."</td>";
            $this->salida .= "        <td width=\"10%\" align=\"right\">".FormatoValor($arr[0][total_cuenta])."</td>";
            $accion1=ModuloGetURL('app','MantenimientoCuentas','user','LlamarModificarCuenta',array('Cuenta'=>$arr[0][numerodecuenta],'Estado'=>$arr[0][estado],'modificar'=>'1'));
            if($arr[0][estado]=='0'  AND empty($arr[0][envio_id]) AND empty($arr[1][envio_id]))
            {
              $js = "<SCRIPT>";
              $js .= "  function JustificacionActivacion()\n";
              $js .= "  { \n";
              $js .= "    e = document.getElementById('Justificacion');\n";
              $js .= "   if(e.style.display == 'none'){\n";
              $js .= "    e.style.display = 'block';\n";
              $js .= "    }";
              $js .= "    else";
              $js .= "    {\n";
              $js .= "     if(e.style.display == \"block\"){\n";
              $js .= "      e.style.display = 'none';\n";
              $js .= "     }";
              $js .= "    }\n";
              //$js .= "    window.location = \"$accion1\";";
              $js .= "  }\n";
              $js .= "</SCRIPT>";
              $this->salida .= "$js";

              $estado = "<a href=\"javascript:JustificacionActivacion();\"><img title='FACTURADA' src=\"". GetThemePath() ."/images/checkS.gif\" border='0' width='14' height='14'></a>";
            }
            elseif($arr[0][estado]=='0'  AND (!empty($arr[0][envio_id]) OR !empty($arr[1][envio_id])))
            {
              $estado = "<img title='FACTURADA' src=\"". GetThemePath() ."/images/checkS.gif\" border='0' width='14' height='14'>";
            }
            else
            if($arr[0][estado]=='1')
            {
              //$estado = "<a href=\"$accion1&nojustificar=1\"><img title='ACTIVA' src=\"". GetThemePath() ."/images/pactivo.png\" border='0' width='14' height='14'></a>";
              $estado = "<img title='ACTIVA' src=\"". GetThemePath() ."/images/pactivo.png\" border='0' width='14' height='14'>";
            }
            else
            if($arr[0][estado]=='2')
            {
              //$estado = "<a href=\"$accion1&nojustificar=1\"><img title='INACTIVA' src=\"". GetThemePath() ."/images/pinactivo.png\" border='0' width='14' height='14'></a>";
              $estado = "<img title='ACTIVA' src=\"". GetThemePath() ."/images/pactivo.png\" border='0' width='14' height='14'>";
            }
                        
            $this->salida .= "        <td width=\"10%\" align=\"center\">$estado</td>";
            $this->salida .= "      </tr>";
            $this->salida .= " </table>";
            $this->salida .= "      <br>";
              
            $this->salida .= "  <div id='Justificacion' style=\"display:none\">";                                    
            //$this->salida .= "<table width=\"60%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            //$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\" >";
            //$this->salida .= " <td>";
            //$this->salida .= "   <a href='javascript:OcultarCargarCuenta();'>[&nbsp;JUSTIFICAR&nbsp;]</a>";                                    
            //$this->salida .= " </td>";
            //$this->salida .= "</tr>";
            //$this->salida .= "</table>";
            
            //
            $js = "<SCRIPT>";
            $js .= "  function EvaluarDatos(frm) \n";
            $js .= "  {\n";
            $js .= "    ele = document.getElementById('error');\n";
            $js .= "    if(frm.motivo_id.value == '-1')\n";
            $js .= "    {\n";
            $js .= "      ele.innerHTML = 'SE DEBE INDICAR EL MOTIVO POR EL CUAL SE ACTIVARA LA CUENTA.'\n";
            $js .= "      return;\n";
            $js .= "    }\n";
            $js .= "    frm.action = \"".$accion1."\";\n";
            $js .= "    frm.submit();\n";
            $js .= "  }\n";
            $js .= "</SCRIPT>";
            $this->salida .= "$js";
            $this->salida .= "<div id=\"error\" style=\"text-align:center\" class=\"label_error\"></div>\n";
            $this->salida .= "<form name=\"cambiar\" action=\"javascript:EvaluarDatos(document.cambiar)\" method=\"post\">\n";
            $this->salida .= "  <table width=\"60%\" align=\"center\" class=\"fieldset\" cellpadding=\"2\">\n";
            $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "      <td align=\"left\" >MOTIVO</td>\n";
            $this->salida .= "      <td align=\"left\" class=\"modulo_list_claro\">\n";
            $this->salida .= "        <select name=\"motivo_id\" class=\"select\" >\n";
            $this->salida .= "          <option value=\"-1\" >----Seleccionar----</option>\n";
            $motivos = $this->GetMotivosActivacionCuenta();
            foreach($motivos as $key => $mtv)
              $this->salida .= "          <option value=\"".$key."\" >".$mtv."</option>\n";
            
            $this->salida .= "        </select>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
            $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "      <td colspan=\"2\">OBSERVACIONES</td>\n";
            $this->salida .= "    </tr>\n";
            $this->salida .= "    <tr class=\"modulo_list_claro\">\n";
            $this->salida .= "      <td colspan=\"2\">\n";
            $this->salida .= "        <textarea name=\"observacion\" style=\"width:100%\" class=\"textarea\"></textarea>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
            $this->salida .= "  </table>\n";
            $this->salida .= "  <table align=\"center\" width=\"50%\" border=\"0\">\n";
            $this->salida .= "    <tr>\n";
            $this->salida .= "      <td align=\"center\">\n";
            $this->salida .= "        <input type=\"submit\" class=\"input-bottom\" name=\"aceptar\" value=\"Aceptar\">\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "  </tr>\n";
            $this->salida .= "    </form>\n";
            $this->salida .= "</table>\n";
            //
            
            $this->salida .= "  </div>";
            
            $this->salida .= "      <br>";
            $this->salida .= "   <table width=\"40%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "     <tr align=\"center\" class=\"modulo_table_list_title\" >";
            $this->salida .= "       <td>Factura</td>";
            $this->salida .= "       <td>TOTAL</td>";
            $this->salida .= "     </tr>";
            
            
              $bool = false;
            for($i=0;$i<sizeof($arr);$i++)
            {
              if(empty($arr[$i][envio_id]))
              {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "      <tr class=\"$estilo\">";
                $this->salida .= "        <td width=\"40%\">".$arr[$i][prefijo]."  ".$arr[$i][factura_fiscal]."</td>";
                $this->salida .= "        <td width=\"60%\"  align=\"right\">".FormatoValor($arr[$i][total_factura])."</td>";
                $this->salida .= "      </tr>";
              } 
              else
              {
              $i=sizeof($arr);
              $bool = true;
              }
            }//fin for
               
            if($bool)
            {
              $this->salida .= "      <tr class=\"modulo_list_claro\" colspan = \"2\">";
              $this->salida .= "        <td width=\"100%\" colspan = \"2\" align=\"center\">FACTURAS ENVIADAS</td>";
              $this->salida .= "      </tr>";
            }
            if($arr[0][estado] <> '0')
            {
            //$accion=ModuloGetURL('app','MantenimientoCuentas','user','BuscarPaciente',array('Cuenta'=>$arr[0][numerodecuenta],'Estado'=>$arr[0][estado],'modificar'=>'1'));
            $accion=ModuloGetURL('app','MantenimientoCuentas','user','LlamarModificarCuenta',array('Cuenta'=>$arr[0][numerodecuenta],'Estado'=>$arr[0][estado],'modificar'=>'1'));
            $this->salida .= "<form name=\"refrescar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "     <tr align=\"center\" class=\"modulo_table_list_title\" >";
            $this->salida .= "       <td align=\"center\"  colspan=\"2\"><input class=\"input-submit\" type=\"submit\" name=\"refrescar\" value=\"FACTURAR\"></td>";
            $this->salida .= "     </tr>";
            }
            
            $this->salida .= " </table>";
            $this->conteo=$_SESSION['SPY'];
            $this->salida .=$this->RetornarBarra();
          }
          $this->salida .= ThemeCerrarTabla();
          return true;
  }
  
  /*
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
     function Encabezado()
	{
		$this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"80%\" align=\"center\" >";
		$this->salida .= " <tr class=\"modulo_table_title\">";
		$this->salida .= " <td>EMPRESA</td>";
		$this->salida .= " <td>MODULO</td>";
		$this->salida .= " <td>FECHA</td>";
		$this->salida .= " </tr>";
		$this->salida .= " <tr align=\"center\">";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['BIO']['NOM_EMP']."</td>";
		$this->salida .= " <td class=\"modulo_list_claro\">BIOESTADISTICA</td>";
		$this->salida .= " <td class=\"modulo_list_claro\" >".$this->FormateoFechaLocal(date("Y-m-d"))."</td>";
		$this->salida .= " </tr>";
		$this->salida .= " </table>";
		return true;
	}

     function GetHtmlServicio($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($value==$TipoId){
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }
     }

     function GetHtmlHistoria($vect,$TipoId)
     {
          foreach($vect as $value=>$titulo)
          {
               if($titulo[hc_modulo]==$TipoId){
                    $this->salida .=" <option value=\"$titulo[hc_modulo]\" selected>".strtoupper($titulo[descripcion])."</option>";
               }else{
                    $this->salida .=" <option value=\"$titulo[hc_modulo]\">".strtoupper($titulo[descripcion])."</option>";
               }
          }
     }
	
	/**
	* Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
	* @access private
	* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId)
	{
          foreach($tipo_id as $value=>$titulo)
          {
               if($value==$TipoId){
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
               }else{
                    $this->salida .=" <option value=\"$value\">$titulo</option>";
               }
          }
	}
	
//----------------------------------------------------------------------------------------------------
  function RetornarBarra(){
    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(empty($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }

    $accion=ModuloGetURL('app','BioEstadistica','user','BuscarPaciente',$vec);

    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
       // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      }
    }
    if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
    {
      if($numpasos>10)
      {
        $valor=10+3;
      }
      else
      {
        $valor=$numpasos+3;
      }
      $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
    }
    else
    {
      if($numpasos>10)
      {
        $valor=10+5;
      }
      else
      {
        $valor=$numpasos+5;
      }
    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>P�ina $paso de $numpasos</td><tr></table><br>";
    }
}

  /**
  *
  */
  function CalcularNumeroPasos($conteo){
    $numpaso=ceil($conteo/$this->limit);
    return $numpaso;
  }

  function CalcularBarra($paso){
    $barra=floor($paso/10)*10;
    if(($paso%10)==0){
      $barra=$barra-10;
    }
    return $barra;
  }

  function CalcularOffset($paso){
    $offset=($paso*$this->limit)-$this->limit;
    return $offset;
  }

}//fin clase

?>

