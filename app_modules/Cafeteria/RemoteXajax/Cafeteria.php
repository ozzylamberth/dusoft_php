<?php
	/**************************************************************************************
	* $Id: Cafeteria.php,v 1.1 2009/06/04 20:31:24 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
    
	IncludeClass('app_Cafeteria_user','','app','Cafeteria');

    function CambiarEstado($ingreso_id,$fecha_solicitud,$tipo_solicitud_dieta_id,$estacion_id,$sw,$user,$td)
    {
       global $VISTA;
       $objResponse = new xajaxResponse();
       $path = SessionGetVar("rutaImagenes");
       $consulta=new app_Cafeteria_user();
       $resultado=$consulta->ConfirmarSN($ingreso_id,$fecha_solicitud,$tipo_solicitud_dieta_id,$estacion_id,$sw,$user);
       if($resultado===true)
       {
//             if($sw=='0')
//             {                                                                                                            //     $ingreso_id,$fecha_solicitud,$tipo_solicitud_dieta_id,$estacion_id,$sw,$user,$td
//                 $cad1="            <a title='DIETA SIN CONFIRMAR POR EL USUARIO DE CAFETERIA' href=\"javascript:CambiarEstado('".$ingreso_id."','".$fecha_solicitud."','".$tipo_solicitud_dieta_id."','".$estacion_id."','1','".$user."','".$td."');\">";//
//                 $cad1.="           <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
//             }
//             else
            if($sw=='1')
            {
                $cad1="            <a title='DIETA CONFIRMADA USUARIO ".$user." FECHA DE CONFIR ".date("Y-m-d G:i:s")."' >";//href=\"javascript:CambiarEstado('".$ingreso_id."','".$fecha_solicitud."','".$tipo_solicitud_dieta_id."','".$estacion_id."','0','".$user."','".$td."');\"
                $cad1.="           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
            }
       }
       elseif($resultado===false)
       {
            $cad= $consulta->error = "Error al actualizar Confirmacion ";
            $cad.= $consulta->mensajeDeError = "Ocurrio un error al actualizar CierreDesayuno <br><br>".$dbconn->ErrorMsg()."<br><br>".$query;

       }
       
       
        //$objResponse->alert($td.$cad1);

       $objResponse->assign($td,"innerHTML",$cad1);
       $objResponse->assign('error',"innerHTML",$cad);
       return $objResponse;

}
?>