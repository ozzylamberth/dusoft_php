<?php
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.5 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Johanna Alarcon Duque
  */
  
   /**
      * Funcion que permite guarda y elimina los checked
      *
      * @param  var $noMarcados contiene el numero de los checked no marcados
      * @param  var $Marcados contiene el numero de los checked marcados
      * @param  array $cadenaMarc arreglo que contiene la cadena de los checked marcados 
      * @param  array $cadenitaNoMar arreglo que contiene la cadena de los checked no marcados 
      * @param  var $tipo contiene la informacion del estado
      * @return Object $objResponse objeto de respuesta al formulario  
      */
  function MostrarEstados($noMarcados,$Marcados,$cadenaMarc,$cadenitaNoMar,$tipo)
  {          
    $objResponse = new xajaxResponse();
    
    $numes=count($cadenitaNoMar);
    
    for($k=0;$k<$numes;$k++)
    {        
      $script_d .='xajax_EliminarEstados(\''.$tipo.'\');';   
    }
  
    $num=count($cadenaMarc);
    //$separar = explode(',',$cadenaMarc); 
    //print_r($cadenaMarc);
    for($k=0;$k<$num;$k++)
    {        
      $script .='       xajax_GuardarEstados(\''.$cadenaMarc[$k].'\',\''.$tipo.'\');'; 
    }
    $objResponse->script($script_d);
    $objResponse->script($script);
    //$objResponse->alert($Marcados);                        
    
    
    return $objResponse;
  }
  
   /**
      * Funcion que permite guarda y elimina los checked
      *
      * @param  var $cadenaMarc contiene el estado
      * @param  var $tipo_doc_general contiene el tipo del documento
      * @return Object $objResponse objeto de respuesta al formulario  
      */
   function GuardarEstados($cadenaMarc,$tipo_doc_general)
   {
     $objResponse = new xajaxResponse();
     //print_r($cadenaMarc); 
     $empresa_id = SessionGetVar("empresa_id");
     $mdl = AutoCarga::factory("ParametrizacionEstadosTiposDocumentos","classes","app","Inv_ParametrosIniciales");
     $permisos=$mdl->BuscarParameEstados($empresa_id ,UserGetUID(),$tipo_doc_general);
     $cont=count($cadenaMarc);
     //print_r($cadenaMarc[1]."CADENA");
     //for($k=0;$k<=$cont;$k++)
     //{
      $guardar=$mdl->AgregarEstados($tipo_doc_general,$empresa_id,$cadenaMarc,$permisos);
     //}
     //print_r($cadenaMarc);
     
     
    return $objResponse;
   }
   
   /**
      * Funcion que permite eliminar
      *
      * @param  var $tipo_doc_general contiene el tipo del documento
      * @return Object $objResponse objeto de respuesta al formulario  
      */
   function EliminarEstados($tipo_doc_general)
   {
     $objResponse = new xajaxResponse();
   
     $empresa_id = SessionGetVar("empresa_id");
     $mdl = AutoCarga::factory("ParametrizacionEstadosTiposDocumentos","classes","app","Inv_ParametrosIniciales");
     $eliminar=$mdl->EliminarParameEstados($empresa_id,$tipo_doc_general);
     
    return $objResponse;
   }
   
   /**
      * Funcion que muestra los estados del documento
      *
      * @param  array $frm contiene el arreglo de la forma
      * @return Object $objResponse objeto de respuesta al formulario  
      */
   function EstadosMod($frm)
   {
     $objResponse = new xajaxResponse();
     $empresa_id = SessionGetVar("empresa_id");
     $mdl = AutoCarga::factory("ParametrizacionEstadosTiposDocumentos","","app","Inv_ParametrosIniciales");
     $estados=$mdl->BuscarEstados();
     $permiso=$mdl->BuscarParameEstados($empresa_id,UserGetUID(),$frm['estados']);
     $html .= "  <table border=\"0\" width=\"30%\" align=\"center\" class=\"modulo_list_claro\">";
     $html .= "    <td colspan=\"2\"class=\"formulacion_table_list\"align=\"left\" width=\"50%\">ESTADOS";
     $html .= "    </td>";
     //$html .= "<pre>".print_r($permiso,true)."</pre>";
     $m=count($estados);
     $i=1;
     
     foreach($estados as $indic=>$valor1)
     { 
       $html .= "<tr>";
       $checked ="";
        
       foreach($permiso as $indic1=>$valor2)
       { 
         if($valor1['abreviatura']==$valor2['abreviatura'])
         $checked = "checked";
         $html .= "      <input type=\"hidden\" class=\"input-text\"  name=\"id_parame\" id=\"id_parame\" value=\"".$valor2['id_paramestadosdocum']."\">\n";
       }  
       $html .= "      <td>".$valor1['descripcion']."</td><td> <input type=\"checkbox\" name=\"chk_estados\" id=\"chk_estados".$i."\" value=\"".$i."\"".$checked."></td>";
       $html .= "      <input type=\"hidden\" class=\"input-text\"  name=\"id_estado".$i."\" id=\"id_estado".$i."\" value=\"".$valor1['abreviatura']."\">\n";
       $html .= "   </tr>";
       $i++;
     }
     $html .= "  </table>";
     $html .= "<table align=\"center\">\n";
     $html .= "   <tr>"; 
     $html .= "    <td colspan=\"2\" align=\"center\"><br>";
		 $html .= '      <input class="input-submit" type="button" name="guardar" value="Guardar" onClick="ValidarDatosProducto()">';
		 $html .= "    </td>";
     $html .= "   </tr>";
     $html .= "</table>\n";
     $objResponse->assign("estadosdoc","innerHTML",$objResponse->setTildes($html));
     return $objResponse;
   }
?>