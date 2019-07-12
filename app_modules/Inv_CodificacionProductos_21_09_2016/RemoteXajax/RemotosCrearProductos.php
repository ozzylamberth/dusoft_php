<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosCrearProductos.php,v 1.2 2010/01/19 13:25:28 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
 
    /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  */  
  function MoleculasT($Sw_Medicamento)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
  
  
  
	$Moleculas=$sql->Listar_Moleculas($Sw_Medicamento);
  
   $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">TIPO DE INSUMOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"7%\">CODIGO</td>\n";
        $html .= "      <td width=\"25%\">NOMBRE</td>\n";
        /*$html .= "      <td width=\"10%\">MEDIDA/TALLA</td>\n";
		$html .= "      <td width=\"10%\">U. MEDIDA</td>\n";*/
		$html .= "      <td width=\"3%\">MOD</td>\n";
		$html .= "      <td width=\"3%\">OP</td>\n";
		
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Moleculas as $key => $mol)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$mol['molecula_id']."</td><td>".$mol['molecula']." </td>\n";
          //$html .= "      <td >".$mol['concentracion']."</td><td> ".$mol['unidad']."</td>";
			
          $html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarMolecula('".$mol['molecula_id']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
      
           
      
      if($mol['estado']==1)
				{
				$html .= "<td align=\"center\">
						  <a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','0','".$mol['molecula_id']."','molecula_id','".$Sw_Medicamento."')\">\n";
				$html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
				}
				else
					{
					$html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','1','".$mol['molecula_id']."','molecula_id','".$Sw_Medicamento."')\">\n";
					$html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
					}
										
		  
					
        }
        
        
        
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
          
          $objResponse->assign("Listado","innerHTML",$html);
          return $objResponse;
  }
  
 
  
   /*
  * Realiza las busqueda de Molecula por Nombre... utilizado por el Buscador
  */
    function BusquedaMolecula_Nombre($Nombre,$Codigo,$Sw_Medicamento)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
  
  $Moleculas=$sql->BuscarMoleculaNombre($Nombre,$Codigo,$Sw_Medicamento);
  
  $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">TIPO DE INSUMOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"7%\">CODIGO</td>\n";
        $html .= "      <td width=\"25%\">NOMBRE</td>\n";
        /*$html .= "      <td width=\"10%\">MEDIDA/TALLA</td>\n";
		$html .= "      <td width=\"10%\">U. MEDIDA</td>\n";*/
		$html .= "      <td width=\"3%\">MOD</td>\n";
		$html .= "      <td width=\"3%\">OP</td>\n";
		
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Moleculas as $key => $mol)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$mol['molecula_id']."</td><td>".$mol['molecula']." </td>\n";
          //$html .= "      <td >".$mol['concentracion']."</td><td> ".$mol['unidad']."</td>";
			
			$html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_ModificarMolecula('".$mol['molecula_id']."')\">\n";
          $html .= "          <img title=\"MODIFICAR\" src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
      
           
      
      if($mol['estado']==1)
				{
				$html .= "<td align=\"center\">
						  <a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','0','".$mol['molecula_id']."','molecula_id','".$Sw_Medicamento."')\">\n";
				$html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
				}
				else
					{
					$html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoMolecula('inv_moleculas','estado','1','".$mol['molecula_id']."','molecula_id','".$Sw_Medicamento."')\">\n";
					$html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
					}
										
		  
					
        }
        
        
        
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $objResponse->assign("Listado","innerHTML",$html);
        return $objResponse;
  }
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstadoMolecula($tabla,$campo,$valor,$id,$campo_id,$Sw_Medicamento)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->call("xajax_MoleculasT('$Sw_Medicamento')");
    return $objResponse;	
	}
 
 
 
/*
  Funcion Xajax para Modificar Laboratorios
  */
  function GuardarModMolecula($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
  
  $token=$sql->Modificar_Molecula($datos);
  if($token)
  {
  $objResponse->call("xajax_MoleculasT('".$datos['sw_medicamento']."')");
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->alert("Modificacion Exitosa!!");
  }
    else
        $objResponse->alert("Error al Modificar!!!");
        //$objResponse->call("xajax_ModificarLaboratorio(".$datos['laboratorio_id'].")");
        $objResponse->assign("Listado","innerHTML",$html);  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  return $objResponse;
  }





 
  /*
  Funcion Xajax para Modificar la informacion de una Molecula con un formulario
  cargado en un Xajax
  */
  function ModificarMolecula($CodigoMolecula_Id)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
    
    $Molecula=$sql->Buscar_Molecula($CodigoMolecula_Id);
    
    //Scripts Javascripts
    
    $datos=$sql->Listar_Unidades_Medida_Medicamento();
  
			$Arreglo = '<SELECT NAME="unidad_medida_medicamento_id" SIZE="1" class="input-text">';
			$i=0;
			foreach($datos as $key => $unidad_medida)
			{
				$Arreglo .= '<OPTION VALUE="'.$unidad_medida['unidad_medida_medicamento_id'].'" ';
        if($Molecula[0]['unidad_medida_medicamento_id']==$unidad_medida['unidad_medida_medicamento_id'])
        $Arreglo .="selected";
        $Arreglo .='>'.$unidad_medida['descripcion'].'</OPTION>';
				$i=$i+1;
			}
			$Arreglo .='</SELECT>';
		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioMolecula\" id=\"FormularioMolecula\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      Modificacion de Tipos de Insumos";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Codigo del Tipo de Insumo :";
		$html .= "      </td>";
		$html .= "      <td width=\"10%\">";
		$html .= '        <input class="input-text" type="Text" readonly="true" value="'.$Molecula[0]['molecula_id'].'" name="molecula_id" maxlength="10" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= '		    <input type="hidden" name="token1" value="0">';
    $html .= '		    <input type="hidden" name="sw_medicamento" value="'.$Molecula[0]['sw_medicamento'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Nombre Del Tipo de Insumo :";
		$html .= "      </td>";
		$html .= "      <td width=\"10%\">";
		$html .= '      <input class="input-text" type="Text" value="'.$Molecula[0]['molecula'].'" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		/*
		$html .= "      <br><div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
        $html .= "  </div>\n";*/
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
						<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Medida/Talla :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input class="input-text" type="Text" value="'.$Molecula[0]['concentracion'].'" name="concentracion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
  	$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Unidad de Medida :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .=       $Arreglo;
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="0">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioMolecula'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
  
 
 function InsertarMolecula($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
  
  $token=$sql->Insertar_Molecula($datos);
  
  if($token)
  {
  $objResponse->call("Cerrar('Contenedor')");
  $objResponse->call("xajax_MoleculasT('".$datos['sw_medicamento']."')");
  $objResponse->alert("Ingreso Exitoso!!");
  $objResponse->assign("Listado","innerHTML",$html);  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa que no exista el Tipo de Insumo!!");
  
  
  
  return $objResponse;
  }


  function IngresoMolecula()
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas","classes","app","Inv_CodificacionProductos");
  $datos=$sql->Listar_Unidades_Medida_Medicamento();
  
			$Arreglo = '<SELECT NAME="unidad_medida_medicamento_id" SIZE="1" class="input-text">';
			$i=0;
			foreach($datos as $key => $unidad_medida)
			{
				$Arreglo .= '<OPTION VALUE="'.$unidad_medida['unidad_medida_medicamento_id'].'">'.$unidad_medida['descripcion'].'</OPTION>';
				$i=$i+1;
			}
			$Arreglo .='</SELECT>';
		
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioMolecula\" id=\"FormularioMolecula\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      Creacion Del Tipo de Insumo";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Codigo del Tipo de Insumo :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input class="input-text" type="Text" name="molecula_id" maxlength="4" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= '		  <input type="hidden" name="token1" value="0">';
    $html .= '		  <input type="hidden" name="sw_medicamento" value="0">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"30%\">";
		$html .= "      Nombre Del Tipo de Insumo :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"10%\">";
		$html .= '      <input class="input-text" type="Text" name="descripcion" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">';
		/*
		$html .= "      <br><div id='Contenido' class='d2Content'>\n";
      //En ese espacio se visualiza la informacion extraida de la base de datos.
        $html .= "  </div>\n";*/
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
						<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Medida/Talla :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input class="input-text" type="Text" name="concentracion" maxlength="8" onkeyup="this.value=this.value.toUpperCase()">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
  	$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">";
		$html .= "      Unidad de Medida :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .=       $Arreglo;
		$html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= '      <input type="hidden" name="token" value="1">'; //esto es para definir si es Update o Insert
    $html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioMolecula'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
?>
