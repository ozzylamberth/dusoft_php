<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosEstadosDocumentos.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function TercerosProveedoresT($empresa_id,$tercero_id,$nombre,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio","classes","app","Inv_ParametrosIniciales");
  
  $TercerosProveedores=$sql->Listar_TercerosProveedores($empresa_id,$tercero_id,$nombre,$offset);
  
  $action['paginador'] = "Paginador('".$empresa_id."','".$tercero_id."','".$nombre."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
      $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">PROVEEDORES</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">TIPO ID</td>\n";
      $html .= "      <td width=\"25%\">IDENTIFICACION</td>\n";
      $html .= "      <td width=\"20%\">NOMBRE</td>\n";
      $html .= "      <td width=\"20%\">UBICACION</td>\n";
      $html .= "      <td width=\"20%\">REPRESENTANTE VENTAS</td>\n";
      $html .= "      <td width=\"20%\">TELEFONO REP.</td>\n";
      $html .= "      <td width=\"20%\">TELEFONO EMP.</td>\n";
      $html .= "      <td width=\"20%\">CELULAR EMP.</td>\n";
      $html .= "      <td width=\"20%\">FAX</td>\n";
      $html .= "      <td width=\"20%\">E-M@IL EMP.</td>\n";
      $html .= "      <td width=\"20%\">SEL.</td>\n";
      
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($TercerosProveedores as $key => $tp)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "      <tr class=\"".$est."\" \n";
            $html .= "      <td >".$tp['tipo_id_tercero']."</td>";
            
            if($tp['tipo_id_tercero']=='NIT')
            {
            $html .= "      <td>".$tp['tercero_id']."-".$tp['dv']." </td>\n";
            }
            else
                {
                $html .= "      <td>".$tp['tercero_id']." </td>\n";
                }
             $html .= "      <td>".$tp['nombre_tercero']."</td>";
             $html .= "      <td>".$tp['ubicacion']."</td>";
             $html .= "      <td>".$tp['representante_ventas']."</td>";
             $html .= "      <td>".$tp['telefono_representante_ventas']."</td>";
             $html .= "      <td>".$tp['telefono']."</td>";
             $html .= "      <td>".$tp['celular']."</td>";
             $html .= "      <td>".$tp['fax']."</td>";
             $html .= "      <td>".$tp['email']."</td>";
                              
        
             $html .= "    <td align=\"center\"><a href=\"#\" onclick=\"xajax_IngresoPoliticasDevolucion('".$tp['tercero_id']."','".$tp['nombre_tercero']."','".$offset."')\"><img title=\"SELECCIONAR\" src=\"".GetThemePath()."/images/bestell.gif\" border=\"0\"></a></td>";
            $html .= "      </tr>";
           
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("ListadoTercerosProveedores","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  
  


  function IngresoPoliticasDevolucion($TerceroId,$NombreTercero,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio","classes","app","Inv_ParametrosIniciales");
  $TerceroProveedor=$sql->BuscarTerceroProveedor($TerceroId);		
    
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioCreacionEstadosDocumentos\" id=\"FormularioCreacionEstadosDocumentos\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      INGRESO DE POLITICAS DE DEVOLUCION DE PRODUCTOS";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      LABORATORIO/PROVEEDOR :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .=        $NombreTercero;
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      REPRESENTANTE DE VENTAS :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		if($TerceroProveedor[0]['representante_ventas']=="")
    {
    $html .= '      <div id="representante_ventas">
    <a href="#" onclick="xajax_FormaDinamica5(\''.$TerceroId.'\',\''.$TerceroProveedor[0]['representante_ventas'].'\',\''.$offset.'\');">No hay Representante de Ventas</a></div>';
    }
    else
          {
          $html .= '      <div id="representante_ventas">
                          <a href="#" onclick="xajax_FormaDinamica5(\''.$TerceroId.'\',\''.$TerceroProveedor[0]['representante_ventas'].'\',\''.$offset.'\');">'.$TerceroProveedor[0]['representante_ventas'].'</a></div>';
          }
    $html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      TELEFONO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		if($TerceroProveedor[0]['telefono_representante_ventas']=="")
    {
    $html .= '      <div id="telefono"><a href="#" onclick="xajax_FormaDinamica4(\''.$TerceroId.'\',\''.$TerceroProveedor[0]['telefono_representante_ventas'].'\',\''.$offset.'\');">No Hay Telefono Inscrito</a></div>';
    }
    else
    $html .= '      <div id="telefono"><a href="#" onclick="xajax_FormaDinamica4(\''.$TerceroId.'\',\''.$TerceroProveedor[0]['telefono_representante_ventas'].'\',\''.$offset.'\');">'.$TerceroProveedor[0]['telefono_representante_ventas'].'</a></div>';
    $html .= "      </td>";
    $html .= "      </tr>";
    
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      CORREO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .= '      <div id="correo"><a href="#" onclick="xajax_FormaDinamica3(\''.$TerceroId.'\',\''.$TerceroProveedor[0]['email'].'\',\''.$offset.'\');">'.$TerceroProveedor[0]['email'].'</a></div>';
    $html .= "      </td>";
    $html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      CORREOS ADICIONALES :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .= '      <div id="correos_adicionales"></div>';
    $html .= "      </td>";
		$html .= "      </tr>";
    
    $html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      POLITICAS DE DEVOLUCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"40%\">";
		$html .= '      <div id="politicas_devolucion"></div>';
    $html .= "      </td>";
    $html .= "      </tr>";
		
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		$html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"Confirmar(xajax.getFormValues('FormularioCreacionEstadosDocumentos'))\">";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "		</form>";
		$html .= "      </table>";
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    $objResponse->script("xajax_CorreosAdicionales('".$TerceroId."');
                          xajax_PoliticasDevolucion('".$TerceroId."');
                          ");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
  
  
  
   
  function FormaDinamica5($TerceroId,$representante_ventas,$offset)
  {
 
  $objResponse = new xajaxResponse();
  
  if($offset=="")
  $offset='0';
  $html .="<form name=\"forma_dinamica6\" id=\"forma_dinamica6\" >";
  $html .="<input type=\"text\" id=\"representante_ventas_1\" class=\"input-text\"  value=\"".$representante_ventas."\" maxlength=\"30\" name=\"telefono\">";
  $html .="<input type=\"hidden\" id=\"tercero_id_2\" name=\"tercero_id_2\" value='".$TerceroId."'>";
  $html .="<input type=\"hidden\" id=\"offset_2\" name=\"offset_2\" value='".$offset."'>";
  $html .="<input class=\"modulo_table_list\" type=\"button\" value='Guardar' onclick=\"xajax_GuardarDaticos5(document.getElementById('representante_ventas_1').value,document.getElementById('tercero_id_2').value,document.getElementById('offset_2').value)\">";
  $html .="</form>";
  
  //$objResponse->alert("'".$TerceroId."'");
  $objResponse->assign("representante_ventas","innerHTML",$objResponse->setTildes($html));
   return $objResponse;
  
  }
  
  
  function GuardarDaticos5($representante_ventas,$TerceroId,$Offset)
  {
    $objResponse = new xajaxResponse();
  //$Email,$TerceroId,$Offset
  $Formulario['offset']=$Offset;
  $Formulario['representante_ventas']=$representante_ventas;
  $Formulario['tercero_id']=$TerceroId;
  
  //print_r($Formulario);  
  
  $Tercero_Id=$Formulario['tercero_id'];
  $sql=AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio", "classes", "app","Inv_ParametrosIniciales");
  
  $sql->GuardarDaticos5($Formulario); 
   
  $datos=$sql->BuscarTerceroProveedor($Formulario['tercero_id']);  
  
  
   //$TerceroId,$telefono,$offset
  //$objResponse->alert($datos[0]['telefono_representante_ventas']);
  //$objResponse->alert($TerceroId);
  if($datos[0]['representante_ventas']=="")
    {
    $html .= "<a href=\"#\" onclick=\"xajax_FormaDinamica5('".$TerceroId."','".$datos[0]['representante_ventas']."','".$Formulario['offset']."');\">No Hay Telefono Inscrito</a>";
    }
    else
    $html .="<a href=\"#\" onclick=\"xajax_FormaDinamica5('".$TerceroId."','".$datos[0]['representante_ventas']."','".$Formulario['offset']."');\">".$datos[0]['representante_ventas']."</a>";
  
  
  $objResponse->script("xajax_TercerosProveedoresT('".SessionGetVar("empresa_id")."','".$Formulario['offset']."');");
  $objResponse->assign("representante_ventas","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }
  
  
  
  
  
  
  
  
  function FormaDinamica4($TerceroId,$telefono,$offset)
  {
 
  $objResponse = new xajaxResponse();
  
  if($offset=="")
  $offset='0';
  $html .="<form name=\"forma_dinamica5\" id=\"forma_dinamica5\" >";
  $html .="<input type=\"text\" id=\"telefono_1\" class=\"input-text\"  value=\"".$telefono."\" maxlength=\"30\" name=\"telefono\">";
  $html .="<input type=\"hidden\" id=\"tercero_id_1\" name=\"tercero_id_1\" value='".$TerceroId."'>";
  $html .="<input type=\"hidden\" id=\"offset_1\" name=\"offset_1\" value='".$offset."'>";
  $html .="<input class=\"modulo_table_list\" type=\"button\" value='Guardar' onclick=\"xajax_GuardarDaticos4(document.getElementById('telefono_1').value,document.getElementById('tercero_id_1').value,document.getElementById('offset_1').value)\">";
  $html .="</form>";
  
  //$objResponse->alert("'".$TerceroId."'");
  $objResponse->assign("telefono","innerHTML",$objResponse->setTildes($html));
   return $objResponse;
  
  }
  
  
  function GuardarDaticos4($Telefono,$TerceroId,$Offset)
  {
    $objResponse = new xajaxResponse();
  //$Email,$TerceroId,$Offset
  $Formulario['offset']=$Offset;
  $Formulario['telefono']=$Telefono;
  $Formulario['tercero_id']=$TerceroId;
  
  //print_r($Formulario);  
  
  $Tercero_Id=$Formulario['tercero_id'];
  $sql=AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio", "classes", "app","Inv_ParametrosIniciales");
  
  $sql->GuardarDaticos4($Formulario); 
   
  $datos=$sql->BuscarTerceroProveedor($Formulario['tercero_id']);  
  
  
   //$TerceroId,$telefono,$offset
  //$objResponse->alert($datos[0]['telefono_representante_ventas']);
  //$objResponse->alert($TerceroId);
  if($datos[0]['telefono_representante_ventas']=="")
    {
    $html .= "<a href=\"#\" onclick=\"xajax_FormaDinamica4('".$TerceroId."','".$datos[0]['telefono_representante_ventas']."','".$Formulario['offset']."');\">No Hay Telefono Inscrito</a>";
    }
    else
    $html .="<a href=\"#\" onclick=\"xajax_FormaDinamica4('".$TerceroId."','".$datos[0]['telefono_representante_ventas']."','".$Formulario['offset']."');\">".$datos[0]['telefono_representante_ventas']."</a>";
  
  
  $objResponse->script("xajax_TercerosProveedoresT('".SessionGetVar("empresa_id")."','".$Formulario['offset']."');");
  $objResponse->assign("telefono","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }

  
  
  
  
  
  
  
  

  function FormaDinamica3($TerceroId,$email,$offset)
  {
 
  $objResponse = new xajaxResponse();
  
  if($offset=="")
  $offset='0';
  $html .="<form name=\"forma_dinamica4\" id=\"forma_dinamica4\" >";
  $html .="<input type=\"text\"   id=\"email\" class=\"input-text\"  value=\"".$email."\" maxlength=\"30\" name=\"email\">";
  $html .="<input type=\"hidden\" id=\"tercero_id\" name=\"tercero_id\" value='".$TerceroId."'>";
  $html .="<input type=\"hidden\" id=\"offset\" name=\"offset\" value='".$offset."'>";
  $html .="<input class=\"modulo_table_list\" type=\"button\" value='Guardar' onclick=\"xajax_GuardarDaticos3(document.getElementById('email').value,document.getElementById('tercero_id').value,document.getElementById('offset').value)\">";
  $html .="</form>";
  
  //$objResponse->alert("'".$TerceroId."'");
  $objResponse->assign("correo","innerHTML",$objResponse->setTildes($html));
   return $objResponse;
  
  }
  
  
  function GuardarDaticos3($Email,$TerceroId,$Offset)
  {
    $objResponse = new xajaxResponse();
  //$Email,$TerceroId,$Offset
  $Formulario['offset']=$Offset;
  $Formulario['email']=$Email;
  $Formulario['tercero_id']=$TerceroId;
  
  //print_r($Formulario);  
  //$objResponse->alert($Formulario);
  $Tercero_Id=$Formulario['tercero_id'];
  $sql=AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio", "classes", "app","Inv_ParametrosIniciales");
  
  $sql->GuardarDaticos3($Formulario); 
   
  $datos=$sql->BuscarTerceroProveedor($Formulario['tercero_id']);  
  
  $html .="<a href=\"#\" onclick=\"xajax_FormaDinamica3('".$TerceroId."','".$datos[0]['email']."','".$Formulario['offset']."');\">".$datos[0]['email']."</a>";
   
  $objResponse->script("xajax_TercerosProveedoresT('".SessionGetVar("empresa_id")."','".$Formulario['offset']."');");
  $objResponse->assign("correo","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }










































  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CorreosAdicionales($TerceroId)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio","classes","app","Inv_ParametrosIniciales");
    
    $CorreosAdicionales=$sql->Listar_CorreosAdicionales($TerceroId);
    //$objResponse->alert("'".$TerceroId."'");
  
  $Select .= "<Select name=\"cambio_a\" class=\"select\" size=\"5\" style=\"width:100%;height:100%\" >";
        foreach($CorreosAdicionales as $key => $ca)
        {
        $Select .="<option value='".$ca['codigo']."' ondblclick=\"xajax_BorrarDaticos('inv_terceros_proveedores_correosadicionales','".$ca['codigo']."','tercero_proveedor_correoadicional','".$TerceroId."','CorreosAdicionales');\">";
        $Select .=$ca['email']." (Doble Click para Borrar)";
        $Select .="</option>";
        }
  
  $Select .= "</Select>";
  
  $html = $Select;
      
    $resultado .= "<div id=\"forma_dinamica\">";
    $resultado .= "<a href=\"#\" onclick=\"xajax_FormaDinamica('".$TerceroId."');\">Adicionar Nuevo Correo</a>";
    $resultado .= "</div>";
    
    $html .= $resultado;
       
    $objResponse->assign("correos_adicionales","innerHTML",$objResponse->setTildes($html));
    
    return $objResponse;	
	}
  
  
  
  function FormaDinamica($TerceroId)
  {
 
  $objResponse = new xajaxResponse();
  $html .="<form name=\"forma_dinamica\" id=\"forma_dinamica\" >";
  $html .="<input type=\"text\" class=\"input-text\" maxlength=\"30\" name=\"email\">";
  $html .="<input type=\"hidden\" name=\"tercero_id\" value='".$TerceroId."'>";
  $html .="<input class=\"modulo_table_list\" type=\"button\" name=\"Guardar\" value='Guardar' onclick=\"xajax_GuardarDaticos(xajax.getFormValues('forma_dinamica'))\">";
  $html .="</form>";
  
  //$objResponse->alert("'".$TerceroId."'");
  $objResponse->assign("forma_dinamica","innerHTML",$objResponse->setTildes($html));
   return $objResponse;
  
  }
  
  
  function GuardarDaticos($Formulario)
  {
  $objResponse = new xajaxResponse();
  $Tercero_Id=$Formulario['tercero_id'];
  $sql=AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio", "classes", "app","Inv_ParametrosIniciales");
  
  $sql->GuardarDaticos($Formulario); 
  
  $objResponse->script("xajax_CorreosAdicionales('".$Tercero_Id."');");  
  return $objResponse;
  }
  
  
  function BorrarDaticos($tabla,$id,$campo_id,$TerceroId,$FuncionXajax)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Borrar_Registro($tabla,$id,$campo_id);
    
    
    $objResponse->script("xajax_".$FuncionXajax."('".$TerceroId."');");  
    return $objResponse;
  
  }
  
  
  
  
    /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function PoliticasDevolucion($TerceroId)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio","classes","app","Inv_ParametrosIniciales");
    
    $PoliticasDevolucion=$sql->Listar_PoliticasDevolucion($TerceroId);
    
  
  $Select .= "<Select name=\"cambio_a\" class=\"select\" size=\"5\" style=\"width:100%;height:100%\" >";
        foreach($PoliticasDevolucion as $key => $pd)
        {
        $Select .="<option title='".$pd['descripcion']."' value='".$pd['codigo']."' ondblclick=\"xajax_BorrarDaticos('inv_terceros_proveedores_politicasdevolucion','".$pd['codigo']."','tercero_proveedor_politicadevolucion_id','".$TerceroId."','PoliticasDevolucion');\">";
        $Select .=$pd['descripcion']."(dbclik para Borrar)";
        $Select .="</option>";
        }
    $Select .= "</Select>";
  
  $html = $Select;
      
    $resultado .= "<div id=\"forma_dinamica2\">";
    $resultado .= "<a href=\"#\" onclick=\"xajax_FormaDinamica2('".$TerceroId."');\">Adicionar Nuevo Politica de Devolucion</a>";
    $resultado .="</div>";
    
    $html .= $resultado;
       
    $objResponse->assign("politicas_devolucion","innerHTML",$objResponse->setTildes($html));
    
    return $objResponse;	
	}
  
  
  
  
  
  
  
  function FormaDinamica2($TerceroId)
  {
 
  $objResponse = new xajaxResponse();
  $html .="<form name=\"forma_dinamica2\" id=\"forma_dinamica2\" >";
  $html .="<input type=\"text\" class=\"input-text\" maxlength=\"120\" name=\"descripcion\">";
  $html .="<input type=\"hidden\" name=\"tercero_id\" value='".$TerceroId."'>";
  $html .="<input class=\"modulo_table_list\" type=\"button\" name=\"Guardar\" value='Guardar' onclick=\"xajax_GuardarDaticos2(xajax.getFormValues('forma_dinamica2'));\">";
  $html .="</form>";
  
  //$objResponse->alert("'".$TerceroId."'");
  $objResponse->assign("forma_dinamica2","innerHTML",$objResponse->setTildes($html));
   return $objResponse;
  
  }
  
  
  function GuardarDaticos2($Formulario)
  {
  $objResponse = new xajaxResponse();
  //$objResponse->alert($Formulario['tercero_id']);  
  
  $Tercero_Id=$Formulario['tercero_id'];
  $sql=AutoCarga::factory("ConsultasAsignarCondicionesDeDevolucionProductosXLaboratorio", "classes", "app","Inv_ParametrosIniciales");
  
  $sql->GuardarDaticos2($Formulario); 
  
  $objResponse->script("xajax_PoliticasDevolucion('".$Tercero_Id."');");  
  return $objResponse;
  }
  
  
?>
