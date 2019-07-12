<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos.php,v 1.15 2010/01/19 13:23:00 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.15 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  /*
  * Funcion Que Refrescará el listado de Laboratorios a desplegar en la pagina.
  */  
   
  /*
  * Capita del Formulario de Ingreso de Laboratorios
  */
  
  function BorrarItem_Reservado($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla,$pedidoId,$farmacia,$usuarioPedido,$codigo,$cant_sol,$cant_pen)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");
    $datos=$sql->Buscar_PedidoEnBodega($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla);
    if(!empty($datos))
    {
    //action del formulario= Donde van los datos del formulario.
		$html .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"formulacion_table_list\">";
		$html .= "        <td align=\"center\" class=\"label_error\">";
		$html .= "          EL PEDIDO #".$solicitud_prod_a_bod_ppal_id." SE ENCUENTRA ABIERTO EN BODEGA, POR FAVOR CERRAR EL TEMPORAL PARA CONTINUAR!!";
		$html .= "        </td>";
		$html .= "      </tr>";
	  $html .= "      </table>";
    }
    else
        {
        $html .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
        $html .= "      <tr >";
        $html .= "        <td align=\"center\" class=\"label_error\">";
        $html .= "          <input class=\"input-submit\" type=\"button\" value=\"CONFIRMA BORRAR EL ITEM DEL PEDIDO #".$solicitud_prod_a_bod_ppal_id."?\" onclick=\"xajax_Borrar('".$solicitud_prod_a_bod_ppal_id."','".$solicitud_prod_a_bod_ppal_det_id."','".$tabla."','".$pedidoId."','".$farmacia."','".$usuarioPedido."','".$codigo."','".$cant_sol."','".$cant_pen."');\">";
        $html .= "        </td>";
        $html .= "      </tr>";
        $html .= "      </table>";
        }
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->script("MostrarSpan('BORRAR: ITEM');");
    return $objResponse;
  }
  
  
  
  
  
  
  
  
  function Borrar($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla,$pedidoId,$farmacia,$usuarioPedido,$codigo,$cant_sol,$cant_pen)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");
  $datos=$sql->Buscar_PedidoEnBodega($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla);
  
  //registrar accion eliminacion
  $user = UserGetUID();
  $nom = $sql->GetName($user);
  $nombre = $nom['nombre'];
  
  $registro = $sql->Registra_Accion_Delete($user,$pedidoId,$farmacia,$usuarioPedido,$codigo,$cant_sol,$cant_pen,$nombre);
  //
  if(!empty($datos))
    {
    $objResponse->script("xajax_BorrarItem_Reservado('".$solicitud_prod_a_bod_ppal_id."','".$solicitud_prod_a_bod_ppal_det_id."','".$tabla."');");
    }
      else
          {
          $token=$sql->Borrar($solicitud_prod_a_bod_ppal_id,$solicitud_prod_a_bod_ppal_det_id,$tabla);
          if($token)
            {
            $objResponse->alert("Borrado Exitoso!!");
            $objResponse->script("document.productos.submit();");
            }
            else
                $objResponse->alert("Error!!");
          }
   return $objResponse;
  }
  
  
?>
