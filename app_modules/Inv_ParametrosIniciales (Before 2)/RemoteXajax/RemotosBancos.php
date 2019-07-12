<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosBancos.php,v 1.4 2010/01/27 20:21:03 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
 
    /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  */  
  function BancosT($offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
  
  
	$Bancos=$sql->ListadoBancos($offset);
  
     
    $action['paginador'] = "paginador(";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
  
  
  
    $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
   $html .= "  <legend class=\"normal_10AN\">BANCOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">MOD</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Bancos as $key => $b)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$b['codigo']."</td><td>".$b['descripcion']." </td>\n";
                          
					$html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarBanco('".$b['codigo']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
          
          
          
          if($b['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoBanco('bancos','estado','0','".$b['codigo']."','banco')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoBanco('bancos','estado','1','".$b['codigo']."','banco')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
          $html .= "      </td>\n";
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
          $objResponse->assign("ListadoBancos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
  }
  
 
  
   /*
  * Realiza las busqueda de Molecula por Nombre... utilizado por el Buscador
  */
    function BusquedaBancos($Banco,$Descripcion,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
  
  
	$Bancos=$sql->BusquedaBancos($Banco,$Descripcion,$offset);
  
     
    $action['paginador'] = "PaginadorBusquedas('".$Banco."','".$Descripcion."'";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
  
  
  
    $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
   $html .= "  <legend class=\"normal_10AN\">BANCOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">MOD</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Bancos as $key => $b)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$b['codigo']."</td><td>".$b['descripcion']." </td>\n";
                          
					$html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarBanco('".$b['codigo']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
          
          
          
          if($b['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoBanco('bancos','estado','0','".$b['codigo']."','banco')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoBanco('bancos','estado','1','".$b['codigo']."','banco')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
          $html .= "      </td>\n";
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
          $objResponse->assign("ListadoBancos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
  }
  
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstadoBanco($tabla,$campo,$valor,$id,$campo_id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->call("xajax_BancosT");
    return $objResponse;	
	}
 
 
 
/*
  Funcion Xajax para Modificar Laboratorios
  */
  function GuardarModBanco($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->ModificarBanco($datos);
  if($token)
  {
  $objResponse->call("xajax_BancosT");
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
        $objResponse->assign("Listado","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
        return $objResponse;
  }





 
  /*
  Funcion Xajax para Modificar la informacion de una Molecula con un formulario
  cargado en un Xajax
  */
  function ModificarBanco($CodigoBanco)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
  
  $Banco=$sql->BuscarBanco($CodigoBanco);
  $Departamentos=$sql->ListarDepartamentos(SessionGetVar("tipo_pais_id"));
  
  $select .="<select class=\"select\" name=\"tipo_depto_id\" onchange=\"xajax_SeleccionarMpio(this.value,'".SessionGetVar("tipo_pais_id")."');\">";
  $select .="<option value=\"\">";
  $select .="--Seleccionar--";
  $select .="</option>";
  foreach($Departamentos as $key => $dpto)
        {
        if($Banco[0]['tipo_dpto_id']==$dpto['tipo_dpto_id'])
        $selected=" selected ";
        else
        $selected=" ";
        $select .="<option value='".$dpto['tipo_dpto_id']."' ".$selected.">";
        $select .=$dpto['departamento'];
        $select .="</option>";
        }
  $select .="</select>";
  
  
  
  
  
  $html .= "  <form name=\"FormularioBanco\" id=\"FormularioBanco\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      MODIFICACION DE BANCOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO DE BANCO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input value="'.$Banco[0]['codigo'].'" style="width:100%;height:100%" class="input-text" type="Text" name="banco" maxlength="3" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    $html .= '      <input type="hidden" name="banco_old" value="'.$Banco[0]['codigo'].'">'; //esto es para definir si es Update o Insert
		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input value="'.$Banco[0]['descripcion'].'" class="input-text" style="width:100%;height:100%" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
  

  $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      SELECCIONE DEPARTAMENTO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .=        $select;
		$html .= "      </td>";
		$html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      SELECCIONE MUNICIPIO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= "      <div id=\"mpio\">";
    $html .= "      Seleccione Depto";
    $html .= "      <input name=\"tipo_mpio_id\" type=\"hidden\" value=\"\">";
    $html .= "      </div>";
		$html .= "      </td>";
		$html .= "      </tr>";
   
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      TELEFONO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input value="'.$Banco[0]['telefono'].'" style="width:100%;height:100%" class="input-text" type="Text" name="telefono" maxlength="30" onkeypress="return acceptNum(event)">';
		$html .= "      </td>";
		$html .= "      </tr>";   
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      DIRECCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input value="'.$Banco[0]['direccion'].'" style="width:100%;height:100%" class="input-text" type="Text" name="direccion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";   





  
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Validar(xajax.getFormValues('FormularioBanco'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
  //$Departamento,$Pais,$Municipio+
    $objResponse->script("xajax_SeleccionarMpio('".$Banco[0]['tipo_dpto_id']."','".SessionGetVar("tipo_pais_id")."','".$Banco[0]['tipo_mpio_id']."');");
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  
 
 function InsertarBanco($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->InsertarBanco($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->call("xajax_BancosT");
  $objResponse->alert("Ingreso Exitoso!!");
  $objResponse->assign("Listado","innerHTML",$objResponse->setTildes($html));  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  }
  else
  $objResponse->alert("Error en el Ingreso... !!");
  
  
  
  return $objResponse;
  }


  function IngresoBanco()
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
	$Departamentos=$sql->ListarDepartamentos(SessionGetVar("tipo_pais_id"));
  $select .="<select class=\"select\" name=\"tipo_depto_id\" onchange=\"xajax_SeleccionarMpio(this.value,'".SessionGetVar("tipo_pais_id")."');\">";
  $select .="<option value=\"\">";
  $select .="--Seleccionar--";
  $select .="</option>";
  foreach($Departamentos as $key => $dpto)
        {
        $select .="<option value='".$dpto['tipo_dpto_id']."'>";
        $select .=$dpto['departamento'];
        $select .="</option>";
        }
  $select .="</select>";
	
		
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioBanco\" id=\"FormularioBanco\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      CREACION DE BANCOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      CODIGO DE BANCO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="banco" maxlength="3" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input class="input-text" style="width:100%;height:100%" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      SELECCIONE DEPARTAMENTO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .=        $select;
		$html .= "      </td>";
		$html .= "      </tr>";
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      SELECCIONE MUNICIPIO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= "      <div id=\"mpio\">";
    $html .= "      Seleccione Depto";
    $html .= "      <input name=\"tipo_mpio_id\" type=\"hidden\" value=\"\">";
    $html .= "      </div>";
		$html .= "      </td>";
		$html .= "      </tr>";
   
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      TELEFONO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="telefono" maxlength="30" onkeypress="return acceptNum(event)">';
		$html .= "      </td>";
		$html .= "      </tr>";   
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      DIRECCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input style="width:100%;height:100%" class="input-text" type="Text" name="direccion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";   
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Validar(xajax.getFormValues('FormularioBanco'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  function SeleccionarMpio($Departamento,$Pais,$Municipio)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasBancos","classes","app","Inv_ParametrosIniciales");
	
  if($Departamento=="")
  {
    $html .= "      Seleccione Depto";
    $html .= "      <input name=\"tipo_mpio_id\" type=\"hidden\" value=\"\">";
  
  }
        else
        {
              $Municipios=$sql->ListarMunicipios($Pais,$Departamento);
              $select .="<select class=\"select\" name=\"tipo_mpio_id\">";
              $select .="<option value=\"\">";
              $select .="--Seleccionar--";
              $select .="</option>";
              foreach($Municipios as $key => $mpio)
                    {
              if($Municipio==$mpio['tipo_mpio_id'])
              $selected = " selected ";
              else
              $selected = " ";
                    $select .="<option value='".$mpio['tipo_mpio_id']."' ".$selected.">";
                    $select .=$mpio['municipio'];
                    $select .="</option>";
                    }
              $select .="</select>";
              
              $html .=$select;
          }
  
  
  $objResponse->assign("mpio","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }
  
?>
