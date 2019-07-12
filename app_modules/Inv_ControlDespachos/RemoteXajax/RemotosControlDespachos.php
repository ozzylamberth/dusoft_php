<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosMensajeSistema.php,v 1.1 2009/10/23 19:20:17 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
 
    /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  */  
  function MensajesBuzonT($EmpresaId,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ControlDespachos","classes","app","Inv_ControlDespachos");
  
  
	$MensajesControlDespachos=$sql->ListadoMensajesBuzon($EmpresaId,$offset);
    $action['paginador'] = "Paginador('".$EmpresaId."'";
	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
	$html .= "<center>";
	$html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
	$html .= "  <legend class=\"normal_10AN\">MENSAJES DEL SISTEMA</legend>\n";
	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"20%\">FECHA</td>\n";
	$html .= "      <td width=\"40%\">ASUNTO</td>\n";
	//$html .= "      <td width=\"10%\">OP</td>\n";
	$html .= "    </tr>\n";

	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
        foreach($MensajesControlDespachos as $key => $ms)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
          if($ms['sw_leido']=='0')
             $color=" bgcolor=\"#E2DDB5\" ";
             else
                  $color= "class=\"".$est."\"";
          
          $html .= "    <tr  ".$color."   onclick=\"xajax_VerMensaje('".$ms['buzon_compras_id']."','".$EmpresaId."','".$offset."')\" >\n";
          $html .= "      <td ".$color.">".$ms['fecha_mensaje']."</td><td>".$ms['asunto']." </td>\n";
          $html .= "	</tr>";
        //print_r($color);
         }   
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
        //$objResponse->alert("SI?");
          $objResponse->assign("ListadoControlDespachos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
  }
  
 
  
  
 
 function CambioEstadoMensaje($buzon_compras_id,$EmpresaId)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ControlDespachos","classes","app","Inv_ControlDespachos");
  
  $token=$sql->CambioEstadoMensaje($buzon_compras_id);
  
  if($token)
  {
  $objResponse->script("OcultarSpan();");
  $objResponse->script("xajax_MensajesBuzonT('".$EmpresaId."');");
  
  }
    
  return $objResponse;
  }


  function VerMensaje($buzon_compras_id,$EmpresaId,$offset)
  {
  $objResponse = new xajaxResponse();
  
		$sql = AutoCarga::factory("ControlDespachos","classes","app","Inv_ControlDespachos");
  
  
		$MensajeControlDespachos=$sql->MensajeBuzon($buzon_compras_id);
		$sql->BuzonMensajeLeido($buzon_compras_id);
		$sql->LogLectura($buzon_compras_id,$EmpresaId);
  
		
		$NoLeidos=$sql->ContrarMensajesBuzon($EmpresaId);
    $DatoMail="(".$NoLeidos[0]['count']." <i>Mensajes Nuevos</i>)";
    
		//action del formulario= Donde van los datos del formulario.
		$html .= "  <form name=\"FormularioMensajeSistema\" id=\"FormularioMensajeSistema\" method=\"post\">";
		
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      MENSAJE DEL BUZON";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"10%\">";
		$html .= "      FECHA :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .= '      <input style="width:100%;height:100%" value="'.$MensajeControlDespachos[0]['fecha_mensaje'].'" class="input-text" type="Text" name="mensaje_sistema_id" disabled >';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"10%\">";
		$html .= "      ASUNTO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .= '      <input disabled style="width:100%;height:100%" class="input-text" type="Text" value="'.$MensajeControlDespachos[0]['asunto'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		
				
		$html .= "      <input type=\"hidden\" name=\"estado\" value=\"1\">
		         			<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\">"; 
		$html .= "      MENSAJE :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <textarea disabled style="width:100%;height:100%" class="input-text">'.$MensajeControlDespachos[0]['mensaje'].'</Textarea>';
		$html .= "      </td>";
		$html .= "      </tr>";
		
		if($_REQUEST['datos']['sw_modifica']=='1')
    {
    $html .= "<tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">"; 
		$html .= "      <a onclick=\"xajax_CambioEstadoMensaje('".$buzon_compras_id."','".$EmpresaId."');\">";
		$html .="<img title=\"BORRAR MENSAJE\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\">";
		$html .= "      </a>";
		$html .= "      </td>";
		$html .= "      </tr>";
    }
  //  print_r($_REQUEST);
		
		$html .= "      </table>";
		
    $objResponse->script("xajax_MensajesBuzonT('".$EmpresaId."','".$offset."');");
    $objResponse->assign("dato_mail","innerHTML",$DatoMail);
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  ?>